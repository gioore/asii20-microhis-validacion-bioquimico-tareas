# Semana 11 - prototipo navegable

## Prototipo

El prototipo navegable es el dashboard real del micro-HIS:

```text
microhis/public/dashboard.html
```

Para abrirlo:

```bash
cd microhis
php -S localhost:8080 public/index.php
```

Abrir `http://localhost:8080/dashboard.html`.

## Escenarios demostrables

1. Cargar resultados desde el endpoint o usar fallback demo.
2. Filtrar por prioridad.
3. Abrir el detalle de un paciente ficticio.
4. Validar el resultado y mostrar confirmacion.
5. Abrir rechazo, validar motivo obligatorio y contador.
6. Simular una pantalla pequena y revisar el layout responsive.

## Trazabilidad

| Requisito | Implementacion |
|---|---|
| Ver pendientes | `load()` y tabla `#results` |
| Filtrar | Selector `#priority` |
| Ver detalle | `showDetail()` y panel `#detail` |
| Validar | `action('validate')` |
| Rechazar | `#reject-dialog` y `action('reject')` |
| Feedback | `#notice` con `role=status` |
| Fallback | Datos `demo` cuando falla el API |

El dashboard usa datos ficticios y el backend conserva las reglas de tenant, rol,
motivo obligatorio, conflicto de doble validacion y alertas criticas.
