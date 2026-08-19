# Micro-HIS — Validación de resultados por bioquímico

Micro-monolito educativo en **PHP 8.2+ vanilla** (sin framework) que ejecuta el flujo «revisión, validación o rechazo de un resultado por bioquímico» del módulo ASII-20.

Todos los datos son **ficticios** y sin información clínica identificable.

## Requisitos

- PHP 8.2+ con PDO y SQLite (`php -m` debe mostrar `PDO` y `pdo_sqlite`).

## Instalación y ejecución

```bash
# 1. Crear la base y cargar datos ficticios
php database/seed.php

# 2. Probar por consola
php bin/consola.php pendientes
php bin/consola.php validar 2
php bin/consola.php rechazar 3 "Muestra hemolizada"
php bin/consola.php historial

# 3. Probar por HTTP (servidor integrado de PHP)
php -S localhost:8080 public/index.php
# GET  /health
# GET  /lab/results/pending
# POST /lab/results/{id}/validate
# POST /lab/results/{id}/reject   { "reason": "..." }
# POST /lab/results/{id}/revalidate
# GET  /lab/results/history

# 4. Correr las pruebas automatizadas (sin framework)
php tests/run.php
```

El sujeto se simula en la demo con cabeceras `X-Tenant-Id`, `X-User` y `X-Role` (el HIS real usa JWT).

## Arquitectura

| Capa | Responsabilidad |
|---|---|
| `Presentation` | `public/index.php` (HTTP JSON) y `bin/consola.php` (CLI) |
| `Application` | `BioquimicoValidationService` (casos de uso CU-03, CU-04, CU-05, CU-08) |
| `Domain` | `LabResult` + reglas (motivo obligatorio, crítico no rechazable, validación inmutable, rol) |
| `Persistence` | interfaz + `PdoLabResultRepository` (PDO preparado) + dobles de prueba |

La configuración (`config/config.php`) vive fuera del código. Diagramas editables en `docs/*.puml`.