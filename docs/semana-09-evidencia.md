# Semana 9 - evidencia y criterios

## Pruebas

| Caso | Accion | Resultado esperado |
|---|---|---|
| U01 | Filtrar por `Critica` | Solo aparecen resultados criticos |
| U02 | Seleccionar un resultado | El detalle conserva paciente, prueba y valor |
| U03 | Rechazar sin motivo | El formulario impide enviar |
| U04 | Validar un resultado | Se muestra confirmacion y se actualiza la cola |
| U05 | Detener el API | Se muestra el fallback demo sin pantalla vacia |
| U06 | Navegar con Tab | Se distingue el foco en controles y acciones |

## Criterios de aceptacion

- [ ] Cada hallazgo referencia un elemento real del dashboard.
- [ ] La evaluacion cubre teclado, foco, etiquetas, color y mensajes.
- [ ] Cada correccion tiene una prueba reproducible.
- [ ] No se usan datos reales de pacientes en la evidencia.
