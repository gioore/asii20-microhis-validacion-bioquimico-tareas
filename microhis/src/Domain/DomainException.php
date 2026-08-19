<?php

declare(strict_types=1);

namespace MicroHis\Domain;

/**
 * Base para las excepciones de dominio. La capa Presentation la convierte
 * en el código HTTP correspondiente (422, 403, 409).
 */
abstract class DomainException extends \RuntimeException
{
    public function __construct(string $message, public readonly int $httpStatus)
    {
        parent::__construct($message);
    }
}