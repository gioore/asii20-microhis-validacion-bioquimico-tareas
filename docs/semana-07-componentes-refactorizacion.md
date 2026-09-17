# Semana 7 - Componentes y refactorizacion

Se agrega `public/dashboard.html` como cliente funcional del micro-HIS personal.

## Componentes

- Carga de pendientes mediante `GET /lab/results/pending`.
- Tabla de resultados y panel de detalle.
- Acciones de validacion y rechazo sobre los endpoints existentes.
- Datos demo como fallback cuando el servidor API no esta disponible.
- Layout responsive sin dependencia externa.

## Evidencia

Ejecutar desde `microhis/`:

```bash
php -S localhost:8080 -t public
```

Luego abrir `http://localhost:8080/dashboard.html`. La prueba automatizada existente se ejecuta con `php tests/run.php`.
