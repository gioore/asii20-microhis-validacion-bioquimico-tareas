<?php

declare(strict_types=1);

namespace MicroHis\Persistence;

use MicroHis\Domain\LabResult;
use MicroHis\Domain\LabResultStatus;

/**
 * Contrato de persistencia del módulo. La capa de alto nivel depende de esta
 * abstracción (DIP, Tarea 2), no de una implementación concreta.
 */
interface LabResultRepository
{
    /** @return LabResult[] */
    public function findPending(): array;

    public function findById(int $id): ?LabResult;

    public function save(LabResult $result): void;

    /** @return LabResult[] con historial (RF-06) */
    public function history(int $limit = 20): array;

    public function findByStatus(LabResultStatus $status): array;
}