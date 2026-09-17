<?php

declare(strict_types=1);

/**
 * Punto de entrada HTTP del micro-HIS (sin framework).
 *
 * Uso: php -S localhost:8080 public/index.php
 * Respuestas JSON con códigos HTTP. El sujeto se simula con cabeceras:
 *   X-Tenant-Id, X-User, X-Role (solo demo; el HIS real usa JWT).
 */

use MicroHis\Application\BioquimicoValidationService;
use MicroHis\Application\LogCriticalAlertNotifier;
use MicroHis\Application\LogTecnicoNotifier;
use MicroHis\Domain\DomainException;
use MicroHis\Persistence\PdoLabResultRepository;

require __DIR__ . '/../src/autoload.php';

$config = require __DIR__ . '/../config/config.php';

$pdo = new PDO($config['db']['dsn']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$service = new BioquimicoValidationService(
    new PdoLabResultRepository($pdo),
    new LogCriticalAlertNotifier(),
    new LogTecnicoNotifier(),
);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path   = rtrim((string) $path, '/');

if ($path === '/dashboard.html' && $method === 'GET') {
    header('Content-Type: text/html; charset=utf-8');
    readfile(__DIR__ . '/dashboard.html');
    exit;
}

// Identidad simulada para la demo (datos ficticios).
$user = $_SERVER['HTTP_X_USER'] ?? 'bioquimico-demo';
$role = $_SERVER['HTTP_X_ROLE'] ?? 'bioquimico';
$tenant = $_SERVER['HTTP_X_TENANT_ID'] ?? $config['tenant']['default'];

$body = json_decode(file_get_contents('php://input') ?: '[]', true) ?? [];

$json = static function (int $status, array $data): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
};

try {
    if ($path === '/health') {
        $json(200, ['status' => 'ok', 'tenant' => $tenant, 'module' => 'ASII-20']);
    }

    if ($path === '/lab/results/pending' && $method === 'GET') {
        $json(200, ['pending' => array_map(fn ($r) => $r->toArray(), $service->listPending())]);
    }

    if ($path === '/lab/results/history' && $method === 'GET') {
        $json(200, ['history' => array_map(fn ($r) => $r->toArray(), $service->history())]);
    }

    if (preg_match('#^/lab/results/(\d+)/validate$#', $path, $m) && $method === 'POST') {
        $result = $service->validate((int) $m[1], $user, $role);
        $json(200, ['ok' => true, 'result' => $result->toArray()]);
    }

    if (preg_match('#^/lab/results/(\d+)/reject$#', $path, $m) && $method === 'POST') {
        $reason = (string) ($body['reason'] ?? '');
        $result = $service->reject((int) $m[1], $reason, $user, $role);
        $json(200, ['ok' => true, 'result' => $result->toArray()]);
    }

    if (preg_match('#^/lab/results/(\d+)/revalidate$#', $path, $m) && $method === 'POST') {
        $result = $service->revalidate((int) $m[1], $user, $role);
        $json(200, ['ok' => true, 'result' => $result->toArray()]);
    }

    $json(404, ['error' => 'Ruta no encontrada', 'path' => $path]);
} catch (DomainException $e) {
    $json($e->httpStatus, ['error' => $e->getMessage()]);
} catch (Throwable $e) {
    $json(500, ['error' => 'Error interno', 'detail' => $e->getMessage()]);
}
