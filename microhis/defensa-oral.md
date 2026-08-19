# Guía breve para la defensa oral — Tarea 3

**Estudiante:** GERSON GIOVANNI ORELLANA VÉLIZ
**Módulo:** ASII-20 — Validación de resultados por bioquímico
**Tarea:** 3 — Micro-HIS (micro servicios y monolito, PHP 8.2+ vanilla en capas)

## 1. Resumen en 60 segundos

- Es un micro-monolito en PHP **sin framework**, que ejecuta el flujo «revisión, validación o rechazo de un resultado por bioquímico».
- Cuatro capas: **Presentation** (HTTP JSON + CLI), **Application** (servicio de casos de uso), **Domain** (entidad `LabResult` con reglas) y **Persistence** (PDO con sentencias preparadas + dobles para pruebas).
- Se probó con un runner propio: **camino feliz, regla de dominio y error de persistencia** → 8/8 pasan.

## 2. Estructura

```
microhis/
├── public/index.php          # HTTP JSON (router manual)
├── bin/consola.php           # CLI
├── src/
│   ├── Presentation/         # (entrada HTTP/CLI)
│   ├── Application/          # BioquimicoValidationService + notificadores
│   ├── Domain/               # LabResult, estados, excepciones
│   └── Persistence/          # interfaz + PDO + dobles
├── config/config.php         # configuración fuera del código
├── database/schema.sql + seed.php
├── tests/run.php             # runner sin framework
├── docs/*.puml/.png          # diagramas editables
└── portada.md → tarea3-microhis.docx/.pdf
```

## 3. Posibles preguntas y respuestas

| Pregunta | Respuesta |
|---|---|
| ¿Por qué separar en capas? | Para que el dominio no dependa de la entrada ni de la base de datos; se puede cambiar el motor o la interfaz sin tocar las reglas. |
| ¿Cómo aplica DIP (Tarea 2)? | El servicio depende de interfaces `LabResultRepository`, `CriticalAlertNotifier`, `TecnicoNotifier` inyectadas por constructor; las implementaciones concretas (PDO, log) las implementan. |
| ¿Cómo usaste PDO? | Con sentencias preparadas y bind de parámetros (`prepare` + `execute`), sin concatenar SQL; la DSN sale de `config/config.php`. |
| ¿Por qué SQLite y no MySQL? | La consigna pide PDO preparado sin framework; SQLite es PDO, no requiere servidor y es portable. En el HIS real sería MySQL. |
| ¿Qué probaste y con qué? | Un runner propio sin dependencias: camino feliz (validar, crítico), reglas de dominio (422, 403, 409) y error de persistencia con un doble `FailingLabResultRepository`. |
| ¿Cuál es la limitación actual? | La autenticación se simula por cabecera `X-Role` (no hay JWT real), no hay UI y la validación de tenant está acotada a la configuración. |

## 4. Prueba práctica (una modificación en vivo)

- Abrir `src/Domain/LabResult.php` y cambiar el método `assertValidRejectionReason` para exigir un motivo de al menos 5 caracteres.
- Ejecutar `php tests/run.php` y comprobar que la prueba «rechazo sin motivo» sigue fallando (o pasa) según el cambio.
- Explicar por qué el cambio vive en el dominio y no afecta a Presentation ni Persistence.

## 5. Autoría y datos

- Todos los datos son ficticios; no hay información clínica identificable ni secretos.
- Fuentes editables `.puml`, `.md`, `src/`, `tests/` reproducibles.
- Declaración de uso de IA en `DECLARACION_IA.md` (archivo aparte, no en el documento entregable).