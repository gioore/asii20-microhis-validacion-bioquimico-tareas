# Guía breve para la defensa oral — Tarea 1

**Estudiante:** GERSON GIOVANNI ORELLANA VÉLIZ
**Módulo:** ASII-20 — Validación de resultados por bioquímico
**Consigna:** Modelar «revisión, validación o rechazo de un resultado por bioquímico».

## 1. Eclipse del tema
<60 segundos>
- El módulo valida resultados de laboratorio antes de que queden `resultado_listo`.
- El bioquímico revisa el valor contra los rangos de referencia, y **valida** o **rechaza** con motivo.
- Si hay valor crítico, se genera alerta; si hay rechazo, el técnico corrige y se **revalida**.

## 2. Los tres diagramas y su trazabilidad

**Casos de uso** (CU-01 a CU-08)
- Actores: Bioquímico, Técnico de Laboratorio, Médico, Sistema.
- `include`: CU-03 y CU-04 aprenden de CU-02 (ver detalle para decidir).
- `extend`: CU-05 confirma valor crítico sobre CU-03.

**Actividad**
- Swimlanes por actor y decisiones con excepciones:
  - motivo rechazo obligatorio (si falta → nodo de error `stop`);
  - valor crítico **no** se rechaza directamente (regla de negocio);
  - ciclo de corrección CU-07/CU-08 con `repeat while` más pendientes.

**Secuencia**
- Participantes: Bioquímico, Técnico, API, LabResult, LabOrderItem, CriticalAlert, Médico.
- Mensajes HTTP reales: `GET pending`, `POST /validate`, `POST /reject`, `POST /correct`; excepciones `403/409/422`.

**Rato en la matriz**: cada CU ↔ nodo de actividad ↔ mensaje de secuencia.

## 3. Preguntas previstas y respuestas
1. ¿Por qué CU-05 es `extend` y no `include`? → Es condicional (valor crítico), no obligatorio.
2. ¿Qué pasa si el bioquímico intenta validar algo ya validado? → `409 Conflict`.
3. ¿Cómo se cierra el ciclo de corrección? → Técnico corrige, vuelve a `pendiente`, bioquímico lo revalida (CU-08); el bucle repite mientras haya pendientes.
4. ¿Dónde se guarda la validación? → `lab_results.validated_by` y `validated_at`; item con `status = resultado_listo`.
5. Un valor crítico es rechazado. ¿Está bien? → No: requiere escalamiento / alerta (regla de negocio).

## 4. Prueba práctica (muestre un cambio)
- Abrir `actividad.puml`, cambiar `stop` por un hacer que el nodo de “motivo vacío” retorne a la solicitud del motivo (validación en boca).
- Regenerar PNG:
  `java -jar C:\Users\gerso\AppData\Local\Temp\opencode\plantuml.jar tarea1-uml\actividad.puml -o tarea1-uml`
- Explicar el impacto en la matriz (misma excepción en secuencia → `422`).

## 5. Datos y autoría
- Todos los datos son ficticios; no hay info clínica identificable.
- Se usa Git verifiable: `git log --oneline`; fuente editable `.puml` para reproducir.
- La via de Trabajo está declarada en `DECLARACION_IA.md`; puedo explicar qué acepté/modifiqué y por qué.

<｜DSML｜tool_calls>
<｜DSML｜invoke name="write">
<｜DSML｜parameter name="content" string="true"># Guía — para la defensa oral — Tarea 1

**Estudiante:** GERSON GIOVANNI ORELLANA VÉLIZ
**Módulo:** ASII-20 — Validación de resultados por bioquímico
**Consigna:** Modelar «revisión, validación o rechazo de un resultado por bioquímico».

## 1. Resumen en 60 segundos

- El módulo valida resultados de laboratorio antes de que queden `resultado_listo`.
- El bioquímico revisa el valor contra los rangos de referencia y **valida** o **rechaza** indicando motivo.
- Si hay valor crítico, se genera **alerta**; si hay rechazo, el técnico **corrige** y el bioquímico **revalida**.

## 2. Los tres diagramas y su trazabilidad

**Casos de uso (CU-01..CU-08)**
- Actores: Bioquímico, Técnico de Laboratorio, Médico, Sistema de Alertas.
- `include` / `extend`: CU-02 (ver detalle) es `include` de CU-03 y CU-04; CU-05 es `extend` de CU-03 (solo si es crítico).

**Actividad**
- SwimLanes por actor (Bioquímico / Sistema / Técnico).
- Decisiones y excepciones:
  - **motivo de rechazo obligatorio** (si falta → nodo de error y `stop`);
  - **valor crítico no se rechaza directamente** (regla de negocio: requiere escalamiento/alerta);
  - **ciclo de corrección** CU-07/CU-08 con `repeat while (¿hay más pendientes?)`.

**Secuencia**
- Participantes: Bioquímico, Técnico, API, LabResult, LabOrderItem, CriticalAlert, Médico.
- Mensajes: `GET /lab/results/pending`, `POST /validate`, `POST /reject {motivo}`, `POST /correct {nuevo valor}`.
- Excepciones HTTP: `403` (sin permiso), `409` (ya validado), `422` (motivo vacío).
- Estados: `validated_by`, `validated_at`, `status = resultado_listo`.

**Trazabilidad**: cada CU-07↔CU-08 tiene fila en la matriz con su nodo de actividad y su mensaje de secuencia.

## 3. Posibles preguntas y respuestas

| Pregunta | Respuesta |
|---|---|
| ¿Por qué CU-05 es `extend` y no `include`? | Porque es condicional (solo cuando el valor supera el umbral), no obligatorio en cada validación. |
| ¿Qué pasa si el bioquímico valida algo ya validado? | El sistema responde `409 Conflict`: no admite doble validación. |
| ¿Qué ocurre con un rechazo sin motivo? | Rechazo `422`: el motivo es obligatorio (regla de la actividad). |
| ¿Cómo se cierra el ciclo de corrección? | El técnico corrige → vuelve a pendiente → el bioquímico revalida (CU-08). |
| ¿Puede rechazarse directamente un valor crítico? | No: requiere escalamiento/alerta, es una regla de negocio. |

## 4. Prueba práctica de bloque

Modifica `actividad.puml` en vivo (elimina la alerta de valor crítico) y explica que pierdera la regla de negocio de escalamiento; vuelve a generar el PNG:

```
java -jar C:\Users\gerso\AppData\Local\Temp\opencode\plantuml.jar tarea1-uml\actividad.puml -o tarea1-uml
```

- Explica qué nodos cambiaron y cómo afecta la matriz de trazabilidad.

## 5. Autoría y datos

- Todos los datos son ficticios; no hay info clínica identificable.
- Fuentes editables `.puml` reproducibles; evidencia Git en `EVIDENCIA_GIT.md`.
- La declaración de uso de IA está en `DECLARACION_IA.md` (reposida ahí, no en el documento).