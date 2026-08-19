<?php

declare(strict_types=1);

namespace MicroHis\Domain;

/** El resultado ya fue validado y la validación es inmutable (RNF-03). */
final class ConflictException extends DomainException
{
    public function __construct(string $message = 'El resultado ya fue validado.')
    {
        parent::__construct($message, 409);
    }
}