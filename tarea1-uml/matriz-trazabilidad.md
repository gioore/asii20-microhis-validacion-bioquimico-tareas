# Matriz de trazabilidad — Tarea 1

Proceso: «revisión, validación o rechazo de un resultado por bioquímico»
Módulo: ASII-20 — Validación de resultados por bioquímico

| Requisito / caso de uso | Diagrama de casos de uso (elemento) | Diagrama de actividad (nodo/decisión) | Diagrama de secuencia (mensaje) |
|---|---|---|---|
| CU-01 Ver resultados pendientes | actor Bioquímico → CU-01 | inicio: «consulta resultados pendientes»; decisión «¿existen resultados sin validar?» | `GET /lab/results/pending` |
| CU-02 Ver detalle con rangos | actor Bioquímico → CU-02 (include de CU-03/CU-04) | actividad «revisa valor vs rangos de referencia» | `api -> result: buscar resultados` |
| CU-03 Validar resultado | CU-03 | rama «Sí» de «¿se aprueba?» → «Validar (validated_by, validated_at)» | `POST /lab/results/{id}/validate` → `set validated_by, validated_at` |
| CU-04 Rechazar con motivo | CU-04 | rama «no/rechazo»; decisión «¿motivo proporcionado?» | `POST /lab/results/{id}/reject {motivo}` → `422` si vacío |
| CU-05 Confirmar valor crítico | CU-05 (extend de CU-03) | decisión «¿valor crítico?» → «confirmar criticalidad y generar alerta» | `api -> alert: crear critical_alert` |
| CU-06 Consultar historial | CU-06 | — (fuera del flujo principal) | — (endpoint de historial) |
| CU-07 Revisar corregido | CU-07 | rama «el técnico corrige y vuelve a pendiente» | notificación al técnico |
| CU-08 Revalidar corregido | CU-08 | bucle «¿hay más pendientes?» | `POST /lab/results/{id}/validate` revalidación |
| Regla de negocio: motivo obligatorio | CU-04 | decisión «¿motivo proporcionado?» → rama «No» error | `422 Unprocessable Entity` |
| Regla de negocio: valor crítico requiere alerta | CU-05 | decisión «¿valor crítico?» | `crear critical_alert` |
| Permisos: solo rol Bioquímico | actor Bioquímico (único sobre CU-03/CU-04) | — | `api -> api: valida tenant + rol` → `403 Forbidden` |
| Excepción: resultado ya validado | — | — | `409 Conflict` |

## Cobertura

- Los 8 casos de uso (CU-01 a CU-08) están representados en los 3 diagramas.
- Cada regla de negocio y excepción tiene nodo y mensaje correspondiente.
- Actores y pasos son consistentes entre los tres diagramas.
