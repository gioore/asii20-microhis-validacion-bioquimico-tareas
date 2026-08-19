<?php

declare(strict_types=1);

/**
 * Interfaz CLI del micro-HIS (sin framework).
 *
 * Uso:
 *   php bin/consola.php pendientes
 *   php bin/consola.php validar <id> [usuario]
 *   php bin/consola.php rechazar <id> "<motivo>" [usuario]
 *   php bin/consola.php revalidar <id> [usuario]
 *   php bin/consola.php historial
 */

use MicroHis\Application\BioquimicoValidationService;
use MicroHis\Application\LogCriticalAlertNotifier;
use MicroHis\Application\LogTecnicoNotifier;
use MicroHis\Domain\DomainException;
use MicroHis\Persistence\PdoLabResultRepository;

require __DIR__ . '/../src/autoload.php';

$config = require __DIR__ . '/../config/config.php';

$pdo = new PDO($config['db']['dsn']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$service = new BioquimicoValidationService(
    new PdoLabResultRepository($pdo),
    new LogCriticalAlertNotifier(),
    new LogTecnicoNotifier(),
);

$args    = array_slice($argv, 1);
$command = $args[0] ?? 'help';

$print = static function (array $rows): void {
    if ($rows === []) {
        echo "  (sin resultados)\n";
        return;
    }
    foreach ($rows as $r) {
        $crit = $r->isCritical ? ' [CRITICO]' : '';
        printf(
            "  #%d | %s | %s = %s %s | %s%s\n",
            $r->id,
            $r->patientName,
            $r->testName,
            $r->value,
            $r->unit,
            $r->status->value,
            $crit
        );
    }
};

try {
    switch ($command) {
        case 'pendientes':
            echo "Resultados pendientes de validacion:\n";
            $print($service->listPending());
            break;

        case 'validar':
            $id = (int) ($args[1] ?? 0);
            $user = $args[2] ?? 'bioquimico-demo';
            $r = $service->validate($id, $user, 'bioquimico');
            printf("Validado #%d (%s) por %s -> %s\n", $r->id, $r->testName, $user, $r->status->value);
            break;

        case 'rechazar':
            $id = (int) ($args[1] ?? 0);
            $reason = $args[2] ?? '';
            $user = $args[3] ?? 'bioquimico-demo';
            $r = $service->reject($id, $reason, $user, 'bioquimico');
            printf("Rechazado #%d (%s), motivo: %s. Tecnico notificado.\n", $r->id, $r->testName, $r->rejectedReason);
            break;

        case 'revalidar':
            $id = (int) ($args[1] ?? 0);
            $user = $args[2] ?? 'bioquimico-demo';
            $r = $service->revalidate($id, $user, 'bioquimico');
            printf("Revalidado #%d (%s) por %s -> %s\n", $r->id, $r->testName, $user, $r->status->value);
            break;

        case 'historial':
            echo "Historial de validaciones:\n";
            $print($service->history());
            break;

        default:
            echo "Comandos: pendientes | validar <id> | rechazar <id> \"<motivo>\" | revalidar <id> | historial\n";
    }
} catch (DomainException $e) {
    fwrite(STDERR, 'ERROR ' . $e->httpStatus . ': ' . $e->getMessage() . PHP_EOL);
    exit(1);
}