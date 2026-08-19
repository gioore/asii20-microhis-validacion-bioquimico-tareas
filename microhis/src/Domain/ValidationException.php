<?php

declare(strict_types=1);

namespace MicroHis\Domain;

/** Rechazo sin motivo, valor crítico rechazado directamente, etc. */
final class ValidationException extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 422);
    }
}