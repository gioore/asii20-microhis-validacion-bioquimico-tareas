# Análisis de Sistemas II 2026 — Tarea 1: Diagramas UML por módulo

## Portada

| Campo | Valor |
|---|---|
| **Universidad / Curso** | Análisis de Sistemas II — 2026 |
| **Estudiante** | GERSON GIOVANNI ORELLANA VÉLIZ |
| **GitHub** | `gioore` |
| **Módulo oficial** | Validación de resultados por bioquímico |
| **Consigna individual** | Modele el proceso «revisión, validación o rechazo de un resultado por bioquímico» |
| **Repositorio** | https://github.com/gioore/asii20-microhis-validacion-bioquimico-tareas |
| **Rama evaluada** | `main` |
| **Commit / etiqueta evaluada** | `tarea-1-entrega` |
| **Documento entregable** | `tarea1-uml.pdf` / `tarea1-uml.docx` |

## Declaración de datos

Todos los datos, nombres de pacientes y resultados usados en este documento son **ficticios**. No se incluye información clínica identificable real.

## Índice

1. Introducción
2. Desarrollo
   2.1. Diagrama de casos de uso
   2.2. Diagrama de actividad
   2.3. Diagrama de secuencia
   2.4. Matriz de trazabilidad requisito → diagrama → elemento
3. Conclusión
4. Bibliografía

## 1. Introducción

La presente tarea corresponde al módulo **Validación de resultados por bioquímico** del sistema hospitalario integrado. El objetivo es modelar el proceso de negocio **«revisión, validación o rechazo de un resultado por bioquímico»** mediante tres perspectivas complementarias de UML: el diagrama de casos de uso, el diagrama de actividad y el diagrama de secuencia.

El proceso modelado se enmarca en el flujo de laboratorio clínico: un técnico ingresa resultados de pruebas pendientes, y un bioquímico es el responsable de revisar cada resultado contra los rangos de referencia antes de que quede disponible para el médico en el expediente electrónico (EMR). Durante esa revisión el resultado puede ser **validado** (queda `resultado_listo`) o **rechazado** con un motivo obligatorio, lo que dispara una corrección por parte del técnico y una posterior **revalidación**. Si el valor supera un umbral crítico, se genera una alerta crítica.

Los tres diagramas comparten los mismos actores (Bioquímico, Técnico de Laboratorio, Médico y Sistema de Alertas), los mismos pasos, mensajes y excepciones, y mantienen trazabilidad entre sí a través de la matriz requisito → diagrama → elemento. Todos los datos utilizados son ficticios y no incluyen información clínica identificable.

## 2. Desarrollo

### 2.1 Diagrama de casos de uso

![Diagrama de casos de uso](./casos-de-uso.png)

Fuente editable: [`casos-de-uso.puml`](./casos-de-uso.puml)

### 2.2 Diagrama de actividad

![Diagrama de actividad](./actividad.png)

Fuente editable: [`actividad.puml`](./actividad.puml)

### 2.3 Diagrama de secuencia

![Diagrama de secuencia](./secuencia.png)

Fuente editable: [`secuencia.puml`](./secuencia.puml)

### 2.4 Matriz de trazabilidad

Ver [`matriz-trazabilidad.md`](./matriz-trazabilidad.md).

## 3. Conclusión

Se logró un modelado completo y trazable del proceso de validación de resultados por bioquímico. Los tres diagramas representan perspectivas complementarias y actuales: el de casos de uso delimita actores y objetivos (CU-01 a CU-08), el de actividad expresa decisiones, excepciones y reglas de negocio, y el de secuencia muestra participantes, mensajes HTTP y validaciones con sus respuestas.

La decisión más relevante fue modelar la regla de negocio de que **un valor crítico no puede rechazarse directamente** y debe escalarse mediante alerta. Esta regla condiciona la dirección del `extend` entre CU-05 y CU-03, define la correspondiente divergencia en el diagrama de actividad y se refleja en la secuencia con la creación de `CriticalAlert` y la notificación al médico. Fue la decisión que mayor impacto tuvo en la coherencia entre los tres artefactos.

La principal limitación que permanece es que el modelo conceptual no incluye consideraciones de concurrencia ni de auditoría de movimientos, que quedan fuera del alcance de la semana y corresponden a otros módulos del sistema.

La evidencia del cumplimiento se respalda en: las fuentes editables `.puml` que permiten reproducir cada diagrama, la matriz de trazabilidad requisito → diagrama → elemento, el historial Git ordenado por propósitos y el repositorio compartido con el docente. La defensa oral permitirá justificar estas decisiones y demostrar la comprensión del proceso.

## 4. Bibliografía

- Object Management Group. *Unified Modeling Language (UML), versión 2.5.1*.
- Documentación oficial de PHP. *Supported Versions* y manual de PDO. https://www.php.net/docs.php (cuando corresponda).
