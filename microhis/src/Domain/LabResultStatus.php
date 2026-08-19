<?php

declare(strict_types=1);

namespace MicroHis\Domain;

/** Estados del resultado de laboratorio en el módulo ASII-20. */
enum LabResultStatus: string
{
    case Pending = 'pendiente';
    case Ready = 'resultado_listo';
    case Rejected = 'rechazado';
}