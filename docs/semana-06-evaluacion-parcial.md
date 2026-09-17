# Semana 6 - Evaluacion parcial

## Alcance

Se consolida la evidencia del micro-HIS personal y se demuestra el flujo funcional del bioquimico usando el dominio, servicio de aplicacion, repositorio y endpoint HTTP.

## Cambio practico

El resultado puede validarse cuando esta pendiente; un rechazo exige motivo; un valor critico genera alerta; y un usuario sin rol de bioquimico recibe `403`. La persistencia y las notificaciones se abstraen mediante interfaces.

## Evidencia ejecutable

Desde `microhis/`:

```bash
php tests/run.php
```

Resultado registrado: `8 pasaron, 0 fallaron`.

Para revisar el flujo HTTP:

```bash
php -S localhost:8080 public/index.php
```

El dashboard de las Semanas 7-8 consume `/lab/results/pending`, `/validate` y `/reject`.

## Matriz de decisiones

| Decision | Evidencia |
|---|---|
| Dominio independiente del transporte | `microhis/src/Domain/` |
| Servicio de aplicacion con dependencias inyectadas | `BioquimicoValidationService` |
| Persistencia sustituible | `LabResultRepository`, PDO e InMemory |
| Seguridad por rol y tenant | `public/index.php`, tests de permiso |
| Alertas desacopladas | `CriticalAlertNotifier`, `LogCriticalAlertNotifier` |

## Criterios de aceptacion

- [x] Flujo principal ejecutable.
- [x] Reglas de dominio verificadas.
- [x] Error de persistencia cubierto.
- [x] Evidencia de prueba reproducible.
- [x] Datos de demostracion ficticios.
