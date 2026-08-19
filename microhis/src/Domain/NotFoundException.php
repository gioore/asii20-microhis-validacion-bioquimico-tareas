<?php

declare(strict_types=1);

namespace MicroHis\Domain;

/** El recurso solicitado no existe (HTTP 404). */
final class NotFoundException extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 404);
    }
}