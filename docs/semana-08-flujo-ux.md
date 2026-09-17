# Semana 8 - Flujo UX

Se completa el flujo de revision del dashboard personal con filtros, detalle, validacion y rechazo.

## Flujo funcional

- Filtrar resultados por prioridad.
- Abrir el detalle sin abandonar la cola.
- Validar un resultado y recibir confirmacion.
- Rechazar con motivo obligatorio y contador de caracteres.
- Conservar un layout usable en movil mediante tabla desplazable y detalle apilado.

## Accesibilidad y estados

- `role=status` para carga y confirmacion.
- `role=alert` para valores criticos.
- Dialogo modal con cierre por Escape y retorno del foco.
- Indicador de foco visible y labels asociados a controles.
- Mensajes de cola vacia y fallback demo cuando el API no responde.

## Evidencia

```bash
php -S localhost:8080 public/index.php
```

Abrir `http://localhost:8080/dashboard.html` desde `microhis/` y probar filtro, detalle, validar y rechazar.
