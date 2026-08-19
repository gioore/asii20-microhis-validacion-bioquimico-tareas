# DECLARACIÓN DE USO DE IA — Tarea 3

**Estudiante:** GERSON GIOVANNI ORELLANA VÉLIZ
**Módulo:** ASII-20 — Validación de resultados por bioquímico
**Tarea:** 3 — Micro-HIS (micro servicios y monolito, PHP 8.2+ vanilla en capas)

## 1. Declaración

Utilicé una herramienta de inteligencia artificial (asistente de programación) como apoyo en la elaboración de esta tarea. Declaro de forma transparente qué pedí, qué generó, qué acepté, qué modifiqué y cómo validé el resultado. El diseño de capas, las reglas de dominio y las decisiones de implementación son responsabilidad mía y puedo explicarlas en la defensa oral.

## 2. Herramienta

- **Herramienta:** asistente de IA de desarrollo (OpenCode, modelo de lenguaje de propósito general).
- **Forma de uso:** diálogo conversacional desde la terminal; los archivos se editaron y versionaron con Git.

## 3. Propósito

- Generar borradores del micro-HIS en PHP vanilla: estructura de capas, entidad de dominio, repositorio PDO y servicio de aplicación.
- Redactar borradores de las pruebas automatizadas (runner sin framework) y de los diagramas PlantUML.
- Redactar borradores del documento final y asistir en los comandos de Git y la configuración del repositorio.

## 4. Prompts relevantes (literales, resumidos)

| # | Prompt (acciones principales) | Archivo afectado |
|---|---|---|
| P1 | «Crea la estructura del micro-HIS con capas Presentation/Application/Domain/Persistence en PHP vanilla y configuración fuera del código» | `src/**`, `config/config.php` |
| P2 | «Implementa la entidad LabResult con las reglas de dominio del módulo (motivo obligatorio, crítico no rechazable, validación inmutable, rol)» | `src/Domain/LabResult.php`, `src/Domain/*Exception.php` |
| P3 | «Crea el repositorio PDO con sentencias preparadas y dobles en memoria para pruebas» | `src/Persistence/*` |
| P4 | «Crea el servicio BioquimicoValidationService con DIP y las pruebas run.php» | `src/Application/*`, `tests/run.php` |
| P5 | «Redacta el documento final con portada, índice, desarrollo, conclusión y bibliografía» | `portada.md` |

## 5. Partes aceptadas, modificadas y corregidas

| Archivo | Parte aceptada tal cual | Parte modificada por mí | Validación humana |
|---|---|---|---|
| `src/Domain/LabResult.php` | Estructura de la entidad | Ajusté los nombres de estado y métodos a los usados en T1/T2 (`resultado_listo`, `assertNotValidated`, `revalidate`) y las reglas HTTP (422/403/409/404) | Ejecuté pruebas y CLI |
| `src/Persistence/PdoLabResultRepository.php` | Esqueleto PDO | Verifiqué cada sentencia preparada contra el esquema real y corregí la DSN para que salga de config | Ejecuté `seed.php` y CLI |
| `src/Application/BioquimicoValidationService.php` | Borrador del servicio | Reforcé DIP (constructor injection), añadí `requireResult` con 404 y el flujo de alerta crítica | Ejecuté pruebas |
| `tests/run.php` | Estructura del runner | Escribí los casos por consigna (camino feliz, regla de dominio, error de persistencia) y corregí un aviso de variable | `php tests/run.php` → 8/8 |
| `portada.md` | Borradores de secciones | Reescribí la conclusión según la guía (logro, decisión, limitación, evidencia) y alineé la consigna con la actividad | Revisé el DOCX/PDF generado |

## 6. Validación del resultado

- El micro-HIS **se ejecutó realmente** en PHP 8.4.22: `database/seed.php`, `bin/consola.php` (flujo completo), servidor `php -S` con respuestas JSON (200, 403, 409, 422) y `tests/run.php` con **8 pruebas que pasan**.
- Cada diagrama se generó desde su fuente editable `.puml` y se revisó visualmente.
- El documento final se revisó en su versión DOCX/PDF.
- Los commits por propósito quedan en `EVIDENCIA_GIT.md`.

## 7. Compromisos

- Todos los datos son ficticios; no hay información clínica identificable ni secretos en el repositorio (la identidad se simula por cabecera solo en la demo).
- Las fuentes editables (`.puml`, `.md`, `src/`, `tests/`) están disponibles para reproducir cada artefacto.
- Declaro que comprendo el contenido y que puedo explicar cada decisión y modificar un elemento en la defensa oral.