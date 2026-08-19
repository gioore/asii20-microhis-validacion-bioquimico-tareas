<?php

declare(strict_types=1);

namespace MicroHis\Application;

use MicroHis\Domain\LabResult;

/**
 * Abstracción para emitir alerta cuando se confirma un valor crítico (CU-05).
 */
interface CriticalAlertNotifier
{
    public function emit(LabResult $result): void;
}