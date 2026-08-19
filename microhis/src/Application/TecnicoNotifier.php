<?php

declare(strict_types=1);

namespace MicroHis\Application;

use MicroHis\Domain\LabResult;

/**
 * Abstracción para notificar al técnico cuando hay rechazo (RF-04).
 * La capa de alto nivel depende de esta interfaz, no de un canal concreto.
 */
interface TecnicoNotifier
{
    public function notifyRejection(LabResult $result, string $reason): void;
}