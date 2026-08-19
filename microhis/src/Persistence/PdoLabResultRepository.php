<?php

declare(strict_types=1);

namespace MicroHis\Persistence;

use MicroHis\Domain\LabResult;
use MicroHis\Domain\LabResultStatus;

/**
 * Persistencia con PDO y sentencias preparadas (sin framework).
 * SQLite: la DSN sale de la configuración, fuera del código.
 */
final class PdoLabResultRepository implements LabResultRepository
{
    public function __construct(private readonly \PDO $pdo)
    {
    }

    private function rowToResult(array $row): LabResult
    {
        return new LabResult(
            id: (int) $row['id'],
            tenantId: $row['tenant_id'],
            patientName: $row['patient_name'],
            testName: $row['test_name'],
            value: $row['value'],
            unit: $row['unit'],
            referenceLow: $row['reference_low'],
            referenceHigh: $row['reference_high'],
            isCritical: (bool) $row['is_critical'],
            status: LabResultStatus::from($row['status']),
            validatedBy: $row['validated_by'],
            validatedAt: $row['validated_at'],
            rejectedReason: $row['rejected_reason'],
        );
    }

    public function findPending(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM lab_results WHERE status = :status ORDER BY id ASC'
        );
        $stmt->execute(['status' => LabResultStatus::Pending->value]);

        return array_map(fn (array $row) => $this->rowToResult($row), $stmt->fetchAll());
    }

    public function findById(int $id): ?LabResult
    {
        $stmt = $this->pdo->prepare('SELECT * FROM lab_results WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }

        return $this->rowToResult($row);
    }

    public function save(LabResult $result): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE lab_results
                SET status = :status,
                    validated_by = :validated_by,
                    validated_at = :validated_at,
                    rejected_reason = :rejected_reason,
                    updated_at = :updated_at
              WHERE id = :id'
        );
        $stmt->execute([
            'id'               => $result->id,
            'status'           => $result->status->value,
            'validated_by'     => $result->validatedBy,
            'validated_at'     => $result->validatedAt,
            'rejected_reason'  => $result->rejectedReason,
            'updated_at'       => date('c'),
        ]);
    }

    public function history(int $limit = 20): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM lab_results WHERE status != :status ORDER BY updated_at DESC LIMIT :limit'
        );
        $stmt->bindValue('status', LabResultStatus::Pending->value);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return array_map(fn (array $row) => $this->rowToResult($row), $stmt->fetchAll());
    }

    public function findByStatus(LabResultStatus $status): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM lab_results WHERE status = :status ORDER BY id ASC'
        );
        $stmt->execute(['status' => $status->value]);

        return array_map(fn (array $row) => $this->rowToResult($row), $stmt->fetchAll());
    }
}