# DECLARACIÓN DE USO DE IA — Tarea 1

**Estudiante:** GERSON GIOVANNI ORELLANA VÉLIZ
**Módulo:** ASII-20 — Validación de resultados por bioquímico
**Tarea:** 1 — Diagramas UML por módulo

## 1. Declaración

Utilicé una herramienta de inteligencia artificial (asistente de programación) como apoyo en la elaboración de esta tarea. Declaro de forma transparente qué pedí, qué generó, qué acepté, qué modifiqué y cómo validé el resultado, conforme a lo solicitado por la guía. No utilizo la IA para suplir el análisis del problema ni las decisiones de diseño: esas responsabilidades son mías y puedo explicarlas en la defensa oral.

## 2. Herramienta

- **Herramienta:** asistente de IA de desarrollo (OpenCode, modelo de lenguaje de propósito general).
- **Forma de uso:** diálogo conversacional desde la terminal; los archivos resultantes se editaron y versionaron con Git.

## 3. Propósito

- Generar la sintaxis base de los diagramas PlantUML (casos de uso, actividad y secuencia) y su exportación a imagen PNG.
- Redactar borradores de la matriz de trazabilidad y de la estructura del documento.
- Asistir en los comandos de Git y la configuración del repositorio.

## 4. Prompts relevantes (literales, resumidos)

| # | Prompt (acciones principales) | Archivo afectado |
|---|---|---|
| P1 | «Genera un diagrama de casos de uso PlantUML del modulo Validación de resultados por bioquímico con actores Bioquímico, Técnico de Laboratorio, Médico y Sistema de Alertas» | `casos-de-uso.puml` |
| P2 | «Crea un diagrama de secuencia del flujo consulta de pendientes, validación, rechazo y corrección con mensajes HTTP y validaciones» | `secuencia.puml` |
| P3 | «Crea un diagrama de actividad UML con decisiones y excepciones para el proceso revisión/validación/rechazo» | `actividad.puml` |
| P4 | «Genera la matriz de trazabilidad requisito → diagrama → elemento» | `matriz-trazabilidad.md` |

## 5. Partes aceptadas, modificadas y corregidas

| Archivo | Parte aceptada tal cual | Parte modificada por mí | Validación humana |
|---|---|---|---|
| `casos-de-uso.puml` | Esqueleto de actores y casos de uso | Corregí la dirección de `include`/`extend`: `CU-05 ..> CU-03 : extend` (antes apuntaba al revés); acomodé las relaciones de actores secundarios | Revisé el PNG y contrasté con el modelo real |
| `actividad.puml` | Esquema de decisiones | La primera versión tenía un error de redacción y un árbol de decisión incompleto. La rehice: agregué swimlanes de actores (Bioquímico/Sistema/Técnico), la regla de negocio de valor crítico no rechazable, la excepción de motivo obligatorio con su nodo de error, y el ciclo de corrección CU-07/CU-08 | Revisé que el PNG se genere sin errores y que el ciclo cierre correctamente |
| `secuencia.puml` | Secuencia de validación/rechazo | Agregué el actor Técnico de Laboratorio y el flujo de corrección (CU-07) y revalidación (CU-08), que antes faltaban e inconsistían con la matriz | Revisé el PNG y verifiqué que participa el técnico y los códigos HTTP |
| `matriz-trazabilidad.md` | Estructura de la tabla | Realineé cada fila para que apuntara solo a nodos y mensajes que realmente existen en los diagramas corregidos; eliminé referencias a nodos inexistentes | Contrasté la matriz contra los tres `.puml` finales |

## 6. Validación del resultado

- Cada diagrama se generó desde su fuente editable `.puml` y se revisó visualmente antes de aceptarse.
- Deteje un error en revisión: el primer `actividad.puml` contenía un texto erróneo y no modelaba todas las excepciones; lo corregí por completo (ver tabla).
- Los comandos de Git (commits por propósito, `git log --oneline`) evidencian el avance; los incluyo en `EVIDENCIA_GIT.md`.
- El flujo y las reglas se contrastaron con los requerimientos del módulo y las tablas reales (`lab_results`, `lab_order_items`, `validated_by`, `validated_at`).

## 7. Compromisos

- Todos los datos son ficticios; no hay información clínica identificable ni secretos en el repositorio.
- Las fuentes editables (`.puml`, `.md`) están disponibles para reproducir cada artefacto.
- Declaro que comprendo el contenido y que puedo explicar cada decisión y modificar un elemento en la defensa oral.