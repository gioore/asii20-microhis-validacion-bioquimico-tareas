<?php

declare(strict_types=1);

namespace MicroHis\Application;

use MicroHis\Domain\ForbiddenException;
use MicroHis\Domain\LabResult;
use MicroHis\Domain\LabResultStatus;
use MicroHis\Persistence\LabResultRepository;

/**
 * Servicio de aplicación (capa Application) que orquesta los casos de uso del
 * módulo ASII-20:
 *   - CU-03 Validar resultado
 *   - CU-04 Rechazar con motivo obligatorio (notifica al técnico)
 *   - CU-05 Confirmar valor crítico (genera alerta)
 *   - CU-08 Revalidar resultado corregido
 *
 * Aplica DIP (Tarea 2): depende de abstracciones inyectadas por constructor
 * (LabResultRepository, CriticalAlertNotifier, TecnicoNotifier), no de clases
 * concretas de persistencia ni de notificación.
 */
final class BioquimicoValidationService
{
    public function __construct(
        private readonly LabResultRepository $repo,
        private readonly CriticalAlertNotifier $alerts,
        private readonly TecnicoNotifier $tecnico,
    ) {
    }

    /** El sujeto debe tener el rol Bioquímico (RF-08, RNF-01). */
    private function assertBioquimico(string $role): void
    {
        if ($role !== 'bioquimico') {
            throw new ForbiddenException();
        }
    }

    /** CU-01: ver resultados pendientes. */
    public function listPending(): array
    {
        return $this->repo->findPending();
    }

    /** CU-03 + CU-05: validar un resultado, confirmando críticos. */
    public function validate(int $resultId, string $validatedBy, string $role): LabResult
    {
        $this->assertBioquimico($role);

        $result = $this->requireResult($resultId);
        $result->validate($validatedBy, new \DateTimeImmutable());
        $this->repo->save($result);

        if ($result->isCritical) {
            $this->alerts->emit($result);
        }

        return $result;
    }

    /** CU-04: rechazar con motivo obligatorio y notificar al técnico. */
    public function reject(int $resultId, string $reason, string $validatedBy, string $role): LabResult
    {
        $this->assertBioquimico($role);

        $result = $this->requireResult($resultId);
        $result->reject($reason, new \DateTimeImmutable());
        $this->repo->save($result);
        $this->tecnico->notifyRejection($result, $reason);

        return $result;
    }

    /** CU-08: revalidar un resultado corregido por el técnico. */
    public function revalidate(int $resultId, string $validatedBy, string $role): LabResult
    {
        $this->assertBioquimico($role);

        $result = $this->requireResult($resultId);
        $result->revalidate($validatedBy, new \DateTimeImmutable());
        $this->repo->save($result);

        return $result;
    }

    /** RF-06: historial de validaciones. */
    public function history(int $limit = 20): array
    {
        return $this->repo->history($limit);
    }

    /** Resultado no encontrado → 404. */
    private function requireResult(int $resultId): LabResult
    {
        $result = $this->repo->findById($resultId);
        if ($result === null) {
            throw new \MicroHis\Domain\NotFoundException("Resultado $resultId no encontrado.");
        }

        return $result;
    }
}