# Guía breve para la defensa oral — Tarea 1

**Estudiante:** GERSON GIOVANNI ORELLANA VÉLIZ
**Módulo:** ASII-20 — Validación de resultados por bioquímico
**Consigna:** Modelar «revisión, validación o rechazo de un resultado por bioquímico».

## 1. Resumen en 60 segundos

- El módulo valida resultados de laboratorio antes de que queden `resultado_listo`.
- El bioquímico revisa el valor contra los rangos de referencia y **valida** o **rechaza** indicando un motivo.
- Si hay valor crítico, se genera **alerta**; si hay rechazo, el técnico **corrige** y el bioquímico **revalida**.

## 2. Los tres diagramas y su trazabilidad

**Casos de uso (CU-01..CU-08)**
- Actores: Bioquímico, Técnico de Laboratorio, Médico, Sistema de Alertas.
- `include` / `extend`: CU-02 (ver detalle) es `include` de CU-03 y CU-04; CU-05 es `extend` de CU-03 (solo si es crítico).

**Actividad**
- Swimlanes por actor (Bioquímico / Sistema / Técnico).
- Decisiones y excepciones:
  - **motivo de rechazo obligatorio** (si falta → nodo de error y `stop`);
  - **valor crítico no se rechaza directamente** (regla de negocio: requiere escalamiento/alerta);
  - **ciclo de corrección** CU-07/CU-08 con `repeat while (¿hay más pendientes?)`.

**Secuencia**
- Participantes: Bioquímico, Técnico, API, LabResult, LabOrderItem, CriticalAlert, Médico.
- Mensajes: `GET /lab/results/pending`, `POST /validate`, `POST /reject {motivo}`, `POST /correct {nuevo valor}`.
- Excepciones HTTP: `403` (sin permiso), `409` (ya validado), `422` (motivo vacío).
- Estados: `validated_by`, `validated_at`, `status = resultado_listo`.

**Trazabilidad**: cada CU tiene fila en la matriz con su nodo de actividad y su mensaje de secuencia.

## 3. Posibles preguntas y respuestas

| Pregunta | Respuesta |
|---|---|
| ¿Por qué CU-05 es `extend` y no `include`? | Porque es condicional (solo cuando el valor supera el umbral), no obligatorio en cada validación. |
| ¿Qué pasa si el bioquímico valida algo ya validado? | El sistema responde `409 Conflict`: no admite doble validación. |
| ¿Qué ocurre con un rechazo sin motivo? | Responde `422`: el motivo es obligatorio (regla del diagrama de actividad). |
| ¿Cómo se cierra el ciclo de corrección? | El técnico corrige → vuelve a pendiente → el bioquímico revalida (CU-08). |
| ¿Puede rechazarse directamente un valor crítico? | No: requiere escalamiento/alerta, es una regla de negocio. |

## 4. Prueba práctica (una modificación en vivo)

- Abrir `actividad.puml` y eliminar el nodo de alerta de valor crítico.
- Regenerar el PNG:
  `java -jar C:\Users\gerso\AppData\Local\Temp\opencode\plantuml.jar tarea1-uml\actividad.puml -o tarea1-uml`
- Explicar qué nodos cambiaron y el impacto en la matriz de trazabilidad (se pierde la regla de escalamiento).

## 5. Autoría y datos

- Todos los datos son ficticios; no hay información clínica identificable.
- Fuentes editables `.puml` reproducibles; evidencia Git en `EVIDENCIA_GIT.md`.
- La declaración de uso de IA está en `DECLARACION_IA.md` (archivo aparte, no en el documento entregable).