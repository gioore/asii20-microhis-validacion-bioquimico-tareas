<?php

declare(strict_types=1);

/**
 * Autoloader simple para el micro-HIS (sin framework, sin Composer).
 * Mapea el espacio de nombres MicroHis\ al directorio src/.
 */
spl_autoload_register(static function (string $class): void {
    $prefix = 'MicroHis\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = __DIR__ . '/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});