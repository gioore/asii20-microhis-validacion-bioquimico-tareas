<?php

declare(strict_types=1);

namespace MicroHis\Domain;

/**
 * Entidad de dominio: un resultado de laboratorio pendiente de validación.
 * Encapsula las reglas de negocio del módulo ASII-20.
 */
final class LabResult
{
    public function __construct(
        public readonly int $id,
        public readonly string $tenantId,
        public readonly string $patientName,
        public readonly string $testName,
        public readonly string $value,
        public readonly string $unit,
        public readonly ?string $referenceLow,
        public readonly ?string $referenceHigh,
        public readonly bool $isCritical,
        public LabResultStatus $status,
        public ?string $validatedBy = null,
        public ?string $validatedAt = null,
        public ?string $rejectedReason = null,
    ) {
    }

    /** Un valor crítico no puede rechazarse directamente (regla CU-05). */
    public function assertRejectable(): void
    {
        if ($this->isCritical) {
            throw new ValidationException(
                'Un valor crítico no puede rechazarse directamente: debe validarse y escalarse.'
            );
        }
    }

    /** El rechazo exige motivo obligatorio (regla CU-04). */
    public static function assertValidRejectionReason(string $reason): void
    {
        if (trim($reason) === '') {
            throw new ValidationException('El motivo del rechazo es obligatorio.');
        }
    }

    /** La validación es inmutable (RNF-03). */
    public function assertNotValidated(): void
    {
        if ($this->status !== LabResultStatus::Pending) {
            throw new ConflictException('El resultado ya fue validado.');
        }
    }

    /** Aplica la validación del bioquímico (CU-03). */
    public function validate(string $validatedBy, \DateTimeInterface $at): void
    {
        $this->assertNotValidated();
        $this->status = LabResultStatus::Ready;
        $this->validatedBy = $validatedBy;
        $this->validatedAt = $at->format('c');
    }

    /** Aplica el rechazo con motivo (CU-04). */
    public function reject(string $reason, \DateTimeInterface $at): void
    {
        self::assertValidRejectionReason($reason);
        $this->assertNotValidated();
        $this->assertRejectable();
        $this->status = LabResultStatus::Rejected;
        $this->rejectedReason = $reason;
        $this->validatedAt = $at->format('c');
    }

    /** Revalidación de un resultado corregido (CU-08). */
    public function revalidate(string $validatedBy, \DateTimeInterface $at): void
    {
        $this->status = LabResultStatus::Ready;
        $this->validatedBy = $validatedBy;
        $this->validatedAt = $at->format('c');
        $this->rejectedReason = null;
    }

    /** Vista serializable para la capa Presentation. */
    public function toArray(): array
    {
        return [
            'id'             => $this->id,
            'tenant_id'      => $this->tenantId,
            'patient_name'   => $this->patientName,
            'test_name'      => $this->testName,
            'value'          => $this->value,
            'unit'           => $this->unit,
            'reference'      => $this->referenceLow . ' - ' . $this->referenceHigh,
            'is_critical'    => $this->isCritical,
            'status'         => $this->status->value,
            'validated_by'   => $this->validatedBy,
            'validated_at'   => $this->validatedAt,
            'rejected_reason'=> $this->rejectedReason,
        ];
    }
}