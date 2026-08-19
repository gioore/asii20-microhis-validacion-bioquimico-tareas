<?php

declare(strict_types=1);

/**
 * Mini runner de pruebas del micro-HIS, sin framework y sin dependencias.
 *
 * Uso: php tests/run.php
 * Imprime ✓/✗ por prueba y el total. Código de salida 0 = OK, 1 = fallos.
 */

require __DIR__ . '/../src/autoload.php';

final class TestRunner
{
    private int $passed = 0;
    private int $failed = 0;

    public function test(string $name, callable $fn): void
    {
        try {
            $fn();
            $this->passed++;
            echo "  [OK] $name\n";
        } catch (Throwable $e) {
            $this->failed++;
            echo "  [FALLO] $name -> {$e->getMessage()} @ " . basename($e->getFile()) . ':' . $e->getLine() . "\n";
        }
    }

    public function summary(): int
    {
        echo "\nResultado: {$this->passed} pasaron, {$this->failed} fallaron\n";
        return $this->failed === 0 ? 0 : 1;
    }
}

use MicroHis\Domain\LabResult;
use MicroHis\Domain\LabResultStatus;
use MicroHis\Domain\ValidationException;
use MicroHis\Domain\ForbiddenException;
use MicroHis\Domain\ConflictException;
use MicroHis\Application\BioquimicoValidationService;
use MicroHis\Application\CriticalAlertNotifier;
use MicroHis\Application\TecnicoNotifier;
use MicroHis\Persistence\InMemoryLabResultRepository;
use MicroHis\Persistence\FailingLabResultRepository;

$runner = new TestRunner();

// --- Dobles de notificación que solo cuentan llamadas (sin efectos reales). ---
final class SpyAlertNotifier implements CriticalAlertNotifier
{
    public int $emissions = 0;
    public function emit(LabResult $result): void { $this->emissions++; }
}

final class SpyTecnicoNotifier implements TecnicoNotifier
{
    public int $notifications = 0;
    public function notifyRejection(LabResult $result, string $reason): void { $this->notifications++; }
}

/** Resultado pendiente ficticio reutilizable. */
function makePendingResult(int $id, bool $critical = false): LabResult
{
    return new LabResult(
        id: $id,
        tenantId: 'TENANT-DEMO-01',
        patientName: 'Paciente Ficticio ' . chr(64 + $id),
        testName: 'Prueba ficticia',
        value: $critical ? '6.1' : '95',
        unit: 'mmol/L',
        referenceLow: '3.5',
        referenceHigh: '5.1',
        isCritical: $critical,
        status: LabResultStatus::Pending,
    );
}

// ============================================================
// 1. CAMINO FELIZ: validar un resultado normal
// ============================================================
$runner->test('Camino feliz: validar resultado pendiente -> resultado_listo', function () {
    $repo = new InMemoryLabResultRepository(makePendingResult(1));
    $alertas = new SpyAlertNotifier();
    $service = new BioquimicoValidationService($repo, $alertas, new SpyTecnicoNotifier());

    $result = $service->validate(1, 'bioquimico-demo', 'bioquimico');

    assert($result->status === LabResultStatus::Ready, 'El estado debe ser resultado_listo');
    assert($result->validatedBy === 'bioquimico-demo', 'Debe guardar quién validó (RF-03)');
    assert($result->validatedAt !== null, 'Debe guardar la fecha (RF-03)');
    assert($alertas->emissions === 0, 'No debe emitir alerta crítica si no es crítico');
});

$runner->test('Camino feliz: confirmar valor crítico genera alerta (CU-05)', function () {
    $repo = new InMemoryLabResultRepository(makePendingResult(2, critical: true));
    $alertas = new SpyAlertNotifier();
    $service = new BioquimicoValidationService($repo, $alertas, new SpyTecnicoNotifier());

    $service->validate(2, 'bioquimico-demo', 'bioquimico');

    assert($alertas->emissions === 1, 'Debe emitir exactamente 1 alerta crítica');
});

// ============================================================
// 2. REGLA DE DOMINIO
// ============================================================
$runner->test('Regla de dominio: rechazo sin motivo es inválido (422)', function () {
    $repo = new InMemoryLabResultRepository(makePendingResult(3));
    $service = new BioquimicoValidationService($repo, new SpyAlertNotifier(), new SpyTecnicoNotifier());

    try {
        $service->reject(3, '   ', 'bioquimico-demo', 'bioquimico');
        throw new \LogicException('Debió lanzar ValidationException');
    } catch (ValidationException $e) {
        assert($e->httpStatus === 422, 'Código HTTP debe ser 422');
    }
});

$runner->test('Regla de dominio: valor crítico no es rechazable directamente (CU-05)', function () {
    $repo = new InMemoryLabResultRepository(makePendingResult(4, critical: true));
    $service = new BioquimicoValidationService($repo, new SpyAlertNotifier(), new SpyTecnicoNotifier());

    try {
        $service->reject(4, 'Motivo ficticio', 'bioquimico-demo', 'bioquimico');
        throw new \LogicException('Debió lanzar ValidationException');
    } catch (ValidationException $e) {
        assert($e->httpStatus === 422, 'Código HTTP debe ser 422');
    }
});

$runner->test('Regla de dominio: rechazo válido notifica al técnico (CU-04)', function () {
    $repo = new InMemoryLabResultRepository(makePendingResult(5));
    $tecnico = new SpyTecnicoNotifier();
    $service = new BioquimicoValidationService($repo, new SpyAlertNotifier(), $tecnico);

    $result = $service->reject(5, 'Muestra hemolizada', 'bioquimico-demo', 'bioquimico');

    assert($result->status === LabResultStatus::Rejected, 'Estado debe ser rechazado');
    assert($result->rejectedReason === 'Muestra hemolizada', 'Debe guardar el motivo');
    assert($tecnico->notifications === 1, 'El técnico debe ser notificado');
});

$runner->test('Regla de dominio: validar algo ya validado es conflicto (409)', function () {
    $repo = new InMemoryLabResultRepository(makePendingResult(6));
    $service = new BioquimicoValidationService($repo, new SpyAlertNotifier(), new SpyTecnicoNotifier());

    $service->validate(6, 'bioquimico-demo', 'bioquimico');
    try {
        $service->validate(6, 'bioquimico-demo', 'bioquimico');
        throw new \LogicException('Debió lanzar ConflictException');
    } catch (ConflictException $e) {
        assert($e->httpStatus === 409, 'Código HTTP debe ser 409');
    }
});

$runner->test('Regla de dominio: rol sin permiso recibe 403 (RF-08)', function () {
    $repo = new InMemoryLabResultRepository(makePendingResult(7));
    $service = new BioquimicoValidationService($repo, new SpyAlertNotifier(), new SpyTecnicoNotifier());

    try {
        $service->validate(7, 'medico-demo', 'medico');
        throw new \LogicException('Debió lanzar ForbiddenException');
    } catch (ForbiddenException $e) {
        assert($e->httpStatus === 403, 'Código HTTP debe ser 403');
    }
});

// ============================================================
// 3. ERROR DE PERSISTENCIA (doble que falla)
// ============================================================
$runner->test('Error de persistencia: guardar falla y el error se propaga', function () {
    $repo = new FailingLabResultRepository(new \RuntimeException('Fallo simulado de conexión'));
    $service = new BioquimicoValidationService($repo, new SpyAlertNotifier(), new SpyTecnicoNotifier());

    try {
        $service->validate(99, 'bioquimico-demo', 'bioquimico');
        throw new \LogicException('Debió lanzar RuntimeException');
    } catch (\RuntimeException $e) {
        assert(str_contains($e->getMessage(), 'Fallo simulado'), 'Debe propagar el error de persistencia');
    }
});

exit($runner->summary());