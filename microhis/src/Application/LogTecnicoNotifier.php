<?php

declare(strict_types=1);

namespace MicroHis\Application;

use MicroHis\Domain\LabResult;

/**
 * Implementación concreta del notificador al técnico: registra en un log.
 * En el HIS real sería el canal interno (módulo 21).
 */
final class LogTecnicoNotifier implements TecnicoNotifier
{
    public function notifyRejection(LabResult $result, string $reason): void
    {
        file_put_contents(
            __DIR__ . '/../../var/notifications.log',
            sprintf(
                "[%s] NOTIFICACION TECNICO | resultado=%d motivo=%s\n",
                date('c'),
                $result->id,
                $reason
            ),
            FILE_APPEND
        );
    }
}