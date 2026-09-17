# Semana 5 - Contrato API e integracion

## Modulo

ASII-20 - Validacion de resultados por bioquimico.

## Cliente-servidor

El micro-HIS personal expone un API HTTP vanilla PHP. El usuario de demostracion se simula mediante cabeceras para hacer reproducible la prueba local:

- `X-Tenant-Id`: identifica el hospital.
- `X-User`: identifica al usuario.
- `X-Role`: controla el rol `bioquimico`.

En el HIS integrado estas cabeceras se sustituyen por JWT y middleware de tenant.

## Contrato

| Metodo | Ruta | Permiso/Rol | Respuesta |
|---|---|---|---|
| GET | `/health` | Publica | Estado del servicio y tenant |
| GET | `/lab/results/pending` | `bioquimico` | Resultados pendientes |
| GET | `/lab/results/history` | `bioquimico` | Historial de validaciones |
| POST | `/lab/results/{id}/validate` | `bioquimico` | Resultado validado |
| POST | `/lab/results/{id}/reject` | `bioquimico` | Resultado rechazado |
| POST | `/lab/results/{id}/revalidate` | `bioquimico` | Resultado corregido revalidado |

El endpoint de rechazo recibe `{ "reason": "..." }`. Las respuestas son JSON con `Content-Type` y errores HTTP `403`, `404`, `409` o `422` según la regla incumplida.

## Integracion

- `BioquimicoValidationService` coordina los casos de uso.
- `PdoLabResultRepository` persiste resultados.
- `LogCriticalAlertNotifier` registra alertas de valores criticos.
- `LogTecnicoNotifier` registra avisos de rechazo al tecnico.
- El dashboard de `microhis/public/dashboard.html` consume el contrato.

## Ejecucion

```bash
cd microhis
php -S localhost:8080 public/index.php
```

La implementacion usa datos ficticios y no contiene informacion clinica real.
