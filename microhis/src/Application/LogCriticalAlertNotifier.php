<?php

declare(strict_types=1);

namespace MicroHis\Application;

/**
 * Implementación concreta del notificador de alertas críticas: registra en un
 * log. En el HIS real sería el canal de notificaciones (módulo 21).
 */
final class LogCriticalAlertNotifier implements CriticalAlertNotifier
{
    public function emit(\MicroHis\Domain\LabResult $result): void
    {
        file_put_contents(
            __DIR__ . '/../../var/alerts.log',
            sprintf(
                "[%s] ALERTA CRITICA | resultado=%d paciente=%s prueba=%s valor=%s %s\n",
                date('c'),
                $result->id,
                $result->patientName,
                $result->testName,
                $result->value,
                $result->unit
            ),
            FILE_APPEND
        );
    }
}