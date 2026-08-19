<?php

declare(strict_types=1);

namespace MicroHis\Domain;

/** El sujeto que ejecuta la acción no tiene el rol Bioquímico (RNF-01). */
final class ForbiddenException extends DomainException
{
    public function __construct(string $message = 'Acción no permitida para el rol.')
    {
        parent::__construct($message, 403);
    }
}