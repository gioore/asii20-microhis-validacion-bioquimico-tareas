# Tarea 2 — RF/RNF y criterios de aceptación (DIP)

**Estudiante:** GERSON GIOVANNI ORELLANA VÉLIZ
**Módulo:** ASII-20 — Validación de resultados por bioquímico
**Consigna:** Aplique DIP al diseño del flujo «revisión, validación o rechazo de un resultado por bioquímico».

## Requerimientos funcionales (RF)

| ID | Requerimiento | Alcance en el diseño DIP |
|---|---|---|
| RF-01 | Ver panel de resultados pendientes de validación | `LabResultRepository::buscarPendientes()` (abstracción) |
| RF-02 | Ver detalle del resultado con valores y rangos de referencia | `LabResultRepository::obtenerPorId()` (abstracción) |
| RF-03 | Validar un resultado, guardando `validated_by` y `validated_at`, y actualizando estado a `resultado_listo` | `LabResultRepository::guardarValidacion()` (abstracción) |
| RF-04 | Rechazar un resultado con motivo obligatorio y notificar al técnico | `LabResultRepository::guardarRechazo()` + `TecnicoNotifier::notificar()` (abstracciones) |
| RF-05 | Confirmar un valor crítico y generar alerta | `CriticalAlertNotifier::emitir()` (abstracción) |
| RF-06 | Consultar historial de validaciones con filtros | `LabResultRepository::historial()` (abstracción) |
| RF-07 | Revisar y revalidar un resultado corregido | `LabResultRepository::revalidar()` (abstracción) |
| RF-08 | Restringir las acciones de validación al rol Bioquímico | validación de rol en el servicio (alto nivel) |
| RF-09 | Aislar datos por tenant mediante `X-Tenant-ID` | filtro de tenant en el repositorio concreto |
| RF-10 | Registrar trazabilidad de quién y cuándo se validó o rechazó | `LabResultRepository::guardarValidacion()` registra auditoría |

## Requerimientos no funcionales (RNF)

| ID | Categoría | Requerimiento |
|---|---|---|
| RNF-01 | Seguridad | Solo el rol Bioquímico puede validar o rechazar; la abstracción del servicio exige el sujeto autenticado. |
| RNF-02 | Seguridad | El acceso a datos clínicos se protege con autenticación JWT y control por tenant. |
| RNF-03 | Integridad | La validación es inmutable: `validated_by` y `validated_at` se guardan una sola vez. |
| RNF-04 | Mantenibilidad | El alto nivel no depende de detalles de persistencia ni de notificación (DIP). |
| RNF-05 | Testabilidad | Los repositorios y notificadores concretos son intercambiables por dobles en pruebas. |
| RNF-06 | Auditabilidad | Toda validación o rechazo queda registrado para auditoría. |
| RNF-07 | Disponibilidad | El módulo disponible en horario de laboratorio con respaldo diario. |
| RNF-08 | Compatibilidad | La interfaz funciona en navegadores modernos actualizados. |

## Criterios de aceptación

| ID | Criterio de aceptación |
|---|---|
| CA-RF-01 | El servicio de validación no instancia directamente ningún repositorio ni notificador concreto; todos se inyectan. |
| CA-RF-03 | Tras validar, `validated_by` y `validated_at` quedan guardados y el item pasa a `resultado_listo`. |
| CA-RF-04 | Un rechazo sin motivo no se acepta; el motivo queda registrado y el técnico es notificado. |
| CA-RF-05 | Al confirmar un valor crítico se crea una alerta a través de la abstracción de notificación. |
| CA-RF-08 | Un usuario sin rol Bioquímico recibe error 403. |
| CA-RF-09 | Un tenant no puede ver ni validar resultados de otro tenant. |
| CA-RNF-04 | Cambiar la persistencia (MySQL → InMemory) no requiere modificar el servicio de alto nivel. |
| CA-RNF-05 | Las pruebas pueden sustituir el repositorio real por un doble sin tocar el servicio. |

## Trazabilidad

- RF-03, RF-04, RF-05 y RF-07 mapean los casos de uso CU-03, CU-04, CU-05 y CU-08 de la Tarea 1.
- El diseño antes/después (DIP) y el código PHP se muestran en `portada.md`, sección Desarrollo.