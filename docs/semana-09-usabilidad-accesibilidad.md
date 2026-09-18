# Semana 9 - evaluacion de usabilidad y accesibilidad

## Flujo evaluado

Se evaluo el dashboard personal de `microhis/public/dashboard.html`:

1. Cargar la cola desde `GET /lab/results/pending`.
2. Filtrar por prioridad.
3. Abrir el detalle de un resultado.
4. Validar o rechazar el resultado.
5. Recuperarse de cola vacia o API no disponible.

## Checklist

| Area | Estado | Evidencia | Mejora priorizada |
|---|---|---|---|
| Estado de carga | Cumple | `#state` usa `role=status` | Mantener |
| Mensaje critico | Cumple | El detalle usa `role=alert` | Mantener |
| Foco visible | Cumple | Existe `:focus-visible` | Probar con teclado |
| Etiquetas | Cumple | Filtro y motivo tienen `label` asociado | Mantener |
| Uso del color | Parcial | Prioridad critica usa color y texto | Agregar indicador adicional |
| Recuperacion | Parcial | Existe fallback demo si falla el API | Anunciar el fallback |
| Rechazo | Cumple | Motivo requerido y maximo 500 caracteres | Mantener contador |
| Feedback | Cumple | `#notice` usa `role=status` | Identificar el resultado |

## Hallazgos

| ID | Severidad | Hallazgo | Correccion verificable |
|---|---|---|---|
| H1 | Alta | La fila seleccionada no tiene indicador persistente aparte del detalle | Mostrar seleccion y devolver foco al origen |
| H2 | Alta | El color de prioridad critica puede no bastar en escala de grises | Agregar icono o texto redundante |
| H3 | Media | El fallback demo no se anuncia como estado distinto del API | Anunciar `Modo demostracion` |
| H4 | Media | El resultado de una accion se retira aun si el API responde error | Solo retirar despues de respuesta 2xx |
| H5 | Baja | La tabla requiere desplazamiento horizontal en pantallas pequenas | Evaluar tarjetas para 320px |

## Backlog priorizado

1. Corregir H4 para no ocultar fallos de validacion.
2. Mejorar H1 y H2 para lectura segura del resultado.
3. Anunciar el fallback de demostracion.
4. Revisar la tabla en 320px con datos largos.
