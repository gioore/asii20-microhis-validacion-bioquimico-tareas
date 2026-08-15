# Guía breve para la defensa oral — Tarea 2

**Estudiante:** GERSON GIOVANNI ORELLANA VÉLIZ
**Módulo:** ASII-20 — Validación de resultados por bioquímico
**Tarea:** 2 — Aplicación de DIP (SOLID)

## 1. Resumen en 60 segundos

- El módulo valida resultados de laboratorio: el bioquímico **revisa, valida o rechaza**.
- En el diseño **antes**, `BioquimicoValidationService` creaba directamente el repositorio MySQL, el notificador y el publicador EMR → alto acoplamiento.
- En el diseño **después**, el servicio depende de **interfaces** (`LabResultRepository`, `CriticalAlertNotifier`, `EmrPublisher`) inyectadas por constructor → se invierte la dependencia.

## 2. El principio DIP

- **Cita exacta del artículo (MVP Cluster):** «las clases de alto nivel no tienen que depender de otras de bajo nivel, sino que ambas dependan de abstracciones, así como que las abstracciones no deben depender de los detalles, sino al contrario».
- Aplicado: alto nivel = `BioquimicoValidationService`; bajo nivel = repositorios y notificadores; ambas dependen de interfaces.

## 3. Posibles preguntas y respuestas

| Pregunta | Respuesta |
|---|---|
| ¿Qué dice el DIP? | Alto y bajo nivel dependen de abstracciones; las abstracciones no dependen de los detalles. |
| ¿Cuál era el problema del diseño antes? | El servicio instanciaba clases concretas (MySQL, email, EMR) → alto acoplamiento, cambio costoso y difícil de probar. |
| ¿Cómo se invierte la dependencia? | Con constructor injection de interfaces; el servicio ya no sabe qué motor o canal usa. |
| ¿Qué se gana? | Testabilidad (sustituir por `InMemoryLabResultRepository` o un notificador falso) y mantenibilidad (cambiar MySQL→Postgres sin tocar el servicio). |
| ¿DIP es lo mismo que inyección de dependencias? | No exactamente: la inyección es un mecanismo; DIP es el principio que exige depender de abstracciones. |

## 4. Prueba práctica (una modificación en vivo)

- Abrir `despues.puml` y añadir una nueva implementación (p. ej. `SmsCriticalAlertNotifier implements CriticalAlertNotifier`).
- Regenerar el PNG:
  `java -jar C:\Users\gerso\AppData\Local\Temp\opencode\plantuml.jar tarea2-solid\despues.puml -o tarea2-solid`
- Explicar por qué esto **no** modifica el servicio (DIP cumple su objetivo).

## 5. Autoría y datos

- Todos los datos son ficticios; no hay información clínica identificable.
- Fuentes editables `.puml` reproducibles; evidencia Git en `EVIDENCIA_GIT.md`.
- La declaración de uso de IA está en `DECLARACION_IA.md` (archivo aparte, no en el documento entregable).