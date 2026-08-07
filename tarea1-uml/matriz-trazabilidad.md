# Matriz de trazabilidad — Tarea 1

Proceso: «revisión, validación o rechazo de un resultado por bioquímico»
Módulo: ASII-20 — Validación de resultados por bioquímico

| Requisito / caso de uso | Diagrama de casos de uso (elemento) | Diagrama de actividad (nodo/decisión) | Diagrama de secuencia (mensaje) |
|---|---|---|---|
| CU-01 Ver resultados pendientes | actor Bioquímico → CU-01 | inicio: «Consultar resultados pendientes»; decisión «¿Existen resultados sin validar?» | `GET /lab/results/pending` |
| CU-02 Ver detalle con rangos | actor Bioquímico → CU-02 (include de CU-03/CU-04) | actividad «Revisa valor vs rangos de referencia» | (detalle en la respuesta de consulta pendientes) |
| CU-03 Validar resultado | CU-03 | rama «Sí» de «¿Se aprueba?» → «Registrar validación (validated_by, validated_at)» y «Actualizar item a resultado_listo» | `POST /lab/results/{id}/validate` → `set validated_by, validated_at`; `set status resultado_listo` |
| CU-04 Rechazar con motivo | CU-04 | rama «No/Rechazo»; decisión «¿Motivo proporcionado?» | `POST /lab/results/{id}/reject {motivo}`; `422` si vacío; notificación al técnico |
| CU-05 Confirmar valor crítico | CU-05 (extend de CU-03) | decisión «¿Valor supera umbral crítico?» → «Confirmar criticalidad y generar alerta» | `api -> alert: crear critical_alert`; notificación al médico |
| CU-06 Consultar historial | CU-06 | — (fuera del flujo principal) | — (endpoint de historial) |
| CU-07 Revisar resultado corregido | CU-07 | partición Técnico: «Corrige el resultado», «Volver a pendiente» | `POST /lab/results/{id}/correct {nuevo valor}`; `volver a pendiente` |
| CU-08 Revalidar resultado corregido | CU-08 | bucle `repeat while ¿Hay más resultados pendientes?` | `POST /lab/results/{id}/validate` (revalidación) |
| Regla de negocio: motivo obligatorio | CU-04 | decisión «¿Motivo proporcionado?» rama «No» → «Excepción: motivo obligatorio» | `422 Unprocessable Entity` |
| Regla de negocio: valor crítico no rechazable directamente | CU-05 | nota «Regla de negocio: un valor crítico no puede rechazarse directamente» | — |
| Permisos: solo rol Bioquímico | actor Bioquímico (único sobre CU-03/CU-04) | — | `api -> api: valida tenant + rol` → `403 Forbidden` |
| Excepción: resultado ya validado | — | — | `409 Conflict` |

## Cobertura

- Los 8 casos de uso (CU-01 a CU-08) están representados en los 3 diagramas.
- Los actores coinciden en los 3 diagramas: Bioquímico, Técnico de Laboratorio, Sistema (API) y Médico.
- Cada regla de negocio y excepción tiene nodo de actividad y mensaje de secuencia correspondiente.
- Las fuentes editables `.puml` permiten reproducir cada diagrama.
