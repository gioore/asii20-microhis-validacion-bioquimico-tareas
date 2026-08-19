<?php

declare(strict_types=1);

namespace MicroHis\Persistence;

use MicroHis\Domain\LabResult;
use MicroHis\Domain\LabResultStatus;

/**
 * Doble en memoria (doble de prueba) del repositorio. No toca la base de datos.
 * Permite probar el servicio sin infraestructura (RNF-05, CA-RNF-05).
 */
final class InMemoryLabResultRepository implements LabResultRepository
{
    /** @var array<int, LabResult> */
    private array $items = [];

    public function __construct(LabResult ...$results)
    {
        foreach ($results as $result) {
            $this->items[$result->id] = $result;
        }
    }

    public function findPending(): array
    {
        return array_values(array_filter(
            $this->items,
            fn (LabResult $r) => $r->status === LabResultStatus::Pending
        ));
    }

    public function findById(int $id): ?LabResult
    {
        return $this->items[$id] ?? null;
    }

    public function save(LabResult $result): void
    {
        $this->items[$result->id] = $result;
    }

    public function history(int $limit = 20): array
    {
        $items = array_values(array_filter(
            $this->items,
            fn (LabResult $r) => $r->status !== LabResultStatus::Pending
        ));

        return array_slice($items, 0, $limit);
    }

    public function findByStatus(LabResultStatus $status): array
    {
        return array_values(array_filter(
            $this->items,
            fn (LabResult $r) => $r->status === $status
        ));
    }
}