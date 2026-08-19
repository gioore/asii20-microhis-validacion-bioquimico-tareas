<?php

declare(strict_types=1);

/**
 * Crea el esquema y carga datos FICTICIOS de ejemplo para poder ejecutar
 * el flujo «revisión, validación o rechazo de un resultado por bioquímico».
 *
 * Uso: php database/seed.php
 * No contiene información clínica identificable real.
 */

$config = require __DIR__ . '/../config/config.php';

$pdo = new PDO($config['db']['dsn']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec(file_get_contents(__DIR__ . '/schema.sql'));

$now = date('c');

$stmt = $pdo->prepare(
    'INSERT INTO lab_results
        (tenant_id, patient_name, test_name, value, unit, reference_low, reference_high,
         is_critical, status, created_at, updated_at)
     VALUES
        (:tenant_id, :patient_name, :test_name, :value, :unit, :reference_low, :reference_high,
         :is_critical, :status, :created_at, :updated_at)'
);

$rows = [
    // Pendientes, listos para revisión del bioquímico.
    ['TENANT-DEMO-01', 'Paciente Ficticio A', 'Glucosa en ayunas', '126', 'mg/dL', '70', '110', 0, 'pendiente'],
    ['TENANT-DEMO-01', 'Paciente Ficticio B', 'Potasio sérico', '6.1', 'mmol/L', '3.5', '5.1', 1, 'pendiente'],
    ['TENANT-DEMO-01', 'Paciente Ficticio C', 'Hemoglobina', '11.2', 'g/dL', '12.0', '16.0', 0, 'pendiente'],
];

foreach ($rows as [$tenant, $patient, $test, $value, $unit, $lo, $hi, $critical, $status]) {
    $stmt->execute([
        'tenant_id'      => $tenant,
        'patient_name'   => $patient,
        'test_name'      => $test,
        'value'          => $value,
        'unit'           => $unit,
        'reference_low'  => $lo,
        'reference_high' => $hi,
        'is_critical'    => $critical,
        'status'         => $status,
        'created_at'     => $now,
        'updated_at'     => $now,
    ]);
}

echo 'Seed aplicado. Resultados pendientes listos para validación.' . PHP_EOL;