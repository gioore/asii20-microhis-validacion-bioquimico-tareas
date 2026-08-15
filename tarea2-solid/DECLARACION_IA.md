# DECLARACIÓN DE USO DE IA — Tarea 2

**Estudiante:** GERSON GIOVANNI ORELLANA VÉLIZ
**Módulo:** ASII-20 — Validación de resultados por bioquímico
**Tarea:** 2 — Aplicación de DIP (SOLID)

## 1. Declaración

Utilicé una herramienta de inteligencia artificial (asistente de programación) como apoyo en la elaboración de esta tarea. Declaro de forma transparente qué pedí, qué generó, qué acepté, qué modifiqué y cómo validé el resultado. El análisis del principio, la elección de DIP y las decisiones de diseño son responsabilidad mía y puedo explicarlas en la defensa oral.

## 2. Herramienta

- **Herramienta:** asistente de IA de desarrollo (OpenCode, modelo de lenguaje de propósito general).
- **Forma de uso:** diálogo conversacional desde la terminal; los archivos se editaron y versionaron con Git.

## 3. Propósito

- Generar borradores de los diagramas de clases PlantUML (antes/después) y su exportación a PNG.
- Redactar borradores de los RF/RNF, criterios de aceptación y del documento final.
- Asistir en los comandos de Git y la configuración del repositorio.

## 4. Prompts relevantes (literales, resumidos)

| # | Prompt (acciones principales) | Archivo afectado |
|---|---|---|
| P1 | «Crea un diagrama de clases PlantUML que muestre el diseño ANTES violando DIP para el flujo validación de resultados, con servicio creando directamente repositorio MySQL, notificador y publicador EMR» | `antes.puml` |
| P2 | «Crea un diagrama de clases PlantUML del rediseño DESPUÉS aplicando DIP: interfaces LabResultRepository, CriticalAlertNotifier, EmrPublisher inyectadas por constructor al servicio» | `despues.puml` |
| P3 | «Redacta los RF/RNF y criterios de aceptación del módulo enfocados en el diseño DIP» | `rf-rnf-dip.md` |
| P4 | «Redacta el documento final con portada, introducción, desarrollo, conclusión y bibliografía citando la fuente obligatoria» | `portada.md` |

## 5. Partes aceptadas, modificadas y corregidas

| Archivo | Parte aceptada tal cual | Parte modificada por mí | Validación humana |
|---|---|---|---|
| `antes.puml` | Estructura de clases del anti-patrón | Ajusté las responsabilidades de cada método al modelo real (`buscarPendientes`, `guardarValidacion`, `guardarRechazo`) y la nota explicativa | Generé el PNG y lo contrasté con el flujo real |
| `despues.puml` | Esquema de interfaces | Verifiqué que el servicio dependiera solo de interfaces y que las implementaciones concretas (`Mysql`, `InMemory`, `Email`, `EmrApi`) las implementaran | Generé el PNG y revisé las flechas de dependencia |
| `rf-rnf-dip.md` | Estructura de tablas | Alineé cada RF/RNF con las abstracciones del diseño y añadí criterios de aceptación verificables (CA-RNF-04 y CA-RNF-05) | Contrasté contra las RF/RNF de la semana 2 |
| `portada.md` | Borradores de secciones | Reescribí la conclusión según la guía (logro, decisión relevante, limitación, evidencia), añadí la cita exacta del artículo y el código PHP antes/después | Revisé el DOCX/PDF generado |

## 6. Validación del resultado

- Cada diagrama se generó desde su fuente editable `.puml` y se revisó visualmente antes de aceptarse.
- La **cita exacta del artículo** de MVP Cluster se verificó contra el contenido publicado.
- El código PHP antes/después se revisó manualmente para confirmar que refleja la inversión de dependencias (constructor injection).
- Los commits por propósito quedan en `EVIDENCIA_GIT.md`.

## 7. Compromisos

- Todos los datos son ficticios; no hay información clínica identificable ni secretos en el repositorio.
- Las fuentes editables (`.puml`, `.md`) están disponibles para reproducir cada artefacto.
- Declaro que comprendo el contenido y que puedo explicar cada decisión y modificar un elemento en la defensa oral.