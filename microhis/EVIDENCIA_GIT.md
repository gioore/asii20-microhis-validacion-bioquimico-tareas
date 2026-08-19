# Evidencia Git — Tarea 3

**Estudiante:** GERSON GIOVANNI ORELLANA VÉLIZ
**Módulo:** ASII-20 — Validación de resultados por bioquímico
**Repositorio:** https://github.com/gioore/asii20-microhis-validacion-bioquimico-tareas
**Rama:** `main` · **Tag:** `tarea-3-entrega`

## Estructura de archivos (Tarea 3)

```
microhis/
├── public/index.php              # HTTP JSON (router manual, php -S)
├── bin/consola.php               # CLI
├── src/
│   ├── autoload.php              # autoloader simple (sin Composer)
│   ├── Presentation/             # entrada HTTP/CLI (punto de entrada)
│   ├── Application/
│   │   ├── BioquimicoValidationService.php
│   │   ├── CriticalAlertNotifier.php / LogCriticalAlertNotifier.php
│   │   └── TecnicoNotifier.php / LogTecnicoNotifier.php
│   ├── Domain/
│   │   ├── LabResult.php / LabResultStatus.php
│   │   ├── DomainException.php
│   │   └── Validation/Forbidden/Conflict/NotFoundException.php
│   └── Persistence/
│       ├── LabResultRepository.php (interfaz)
│       ├── PdoLabResultRepository.php
│       └── InMemoryLabResultRepository.php / FailingLabResultRepository.php (dobles)
├── config/config.php             # configuración fuera del código
├── database/schema.sql / seed.php
├── tests/run.php                 # pruebas automatizadas (sin framework)
├── docs/capas.puml/.png          # diagrama de capas
├── docs/secuencia-validar.puml/.png
├── docs/secuencia-rechazar.puml/.png
├── portada.md                    # documento fuente
├── tarea3-microhis.docx/.pdf     # documento entregable
├── DECLARACION_IA.md
├── defensa-oral.md
└── EVIDENCIA_GIT.md              # este archivo
```

## Historial de commits

```
019aa4d tarea3: add final deliverable document (DOCX + PDF) and micro-HIS README
b11e43a tarea3: add editable layer and sequence diagrams (PlantUML + PNG)
9aff47f tarea3: add automated tests (happy path, domain rule, persistence error) without framework
8bb0fec tarea3: add presentation layer with HTTP JSON router and CLI console
3eb9cb8 tarea3: add application service with use cases and DIP (constructor injection)
bd432fe tarea3: add persistence layer with PDO prepared statements and test doubles
25bad6d tarea3: add domain layer with LabResult entity, status and rules (422/403/409/404)
b61b2cd tarea3: scaffold micro-HIS with config, schema, seed and autoloader
```

Commit evaluado: `019aa4d` (tag `tarea-3-entrega`).

## Notas

- La consigna individual y los requisitos técnicos están en `portada.md` (sección 2).
- El micro-HIS se ejecutó y validó en PHP 8.4.22 (CLI, HTTP y pruebas).
- Diagramas reproducibles desde `.puml`.
- Documento entregable: `tarea3-microhis.pdf` y `tarea3-microhis.docx`.