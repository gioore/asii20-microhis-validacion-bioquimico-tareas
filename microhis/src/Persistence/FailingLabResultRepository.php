<?php

declare(strict_types=1);

namespace MicroHis\Persistence;

use MicroHis\Domain\LabResult;
use MicroHis\Domain\LabResultStatus;

/**
 * Doble que simula un error de persistencia (por ejemplo, fallo del motor
 * o de la conexión). Sirve para probar el comportamiento ante error en el
 * camino de guardado, sin depender de una infraestructura real.
 */
final class FailingLabResultRepository implements LabResultRepository
{
    public function __construct(private readonly \RuntimeException $error)
    {
    }

    public function findPending(): array
    {
        return [];
    }

    public function findById(int $id): ?LabResult
    {
        throw $this->error;
    }

    public function save(LabResult $result): void
    {
        throw $this->error;
    }

    public function history(int $limit = 20): array
    {
        return [];
    }

    public function findByStatus(LabResultStatus $status): array
    {
        return [];
    }
}