<?php

declare(strict_types=1);

/**
 * Configuración del micro-HIS. Vive fuera del código (fuera de src/).
 * Todos los datos son ficticios y sin información clínica identificable.
 */
return [
    'db' => [
        'dsn' => 'sqlite:' . dirname(__DIR__) . '/var/his.sqlite',
    ],
    'auth' => [
        'allowed_roles' => ['bioquimico'],
    ],
    'tenant' => [
        'default' => 'TENANT-DEMO-01',
    ],
    'app' => [
        'name' => 'Micro-HIS Validacion de resultados por bioquimico',
        'version' => '1.0.0',
    ],
];