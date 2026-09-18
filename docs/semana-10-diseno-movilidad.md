# Semana 10 - diseno para movilidad

## Alcance

Se adapta el dashboard de `microhis/public/dashboard.html` sin cambiar el
contrato HTTP del micro-HIS. El flujo movil conserva filtro, detalle, validacion,
rechazo y mensajes de estado.

| Viewport | Diseno | Verificacion |
|---|---|---|
| 320px | Encabezado y detalle apilados; tabla desplazable controlada | No se pierde el valor ni la accion |
| 480px | Toolbar envuelve controles | El filtro sigue siendo operable |
| 760px | Breakpoint existente del dashboard | Lista y detalle se leen verticalmente |
| 1280px | Tabla y detalle en dos columnas | Se conserva la lectura rapida |

## Escenarios

### M1 - Consulta en telefono

El bioquimico filtra por prioridad critica y abre un resultado. Paciente, prueba,
valor, unidad y alerta deben continuar visibles sin usar zoom.

### M2 - Rechazo con teclado movil

El bioquimico abre el dialogo, escribe el motivo y confirma. El contador debe
mostrar el limite de 500 caracteres y el campo no puede enviarse vacio.

### M3 - API no disponible

La pantalla muestra datos demo y no queda vacia. El estado debe ser identificable
para no confundir una demo con datos persistidos.

## Criterios de aceptacion

- [ ] Probar 320px, 480px, 760px y 1280px.
- [ ] No aparece scroll horizontal fuera de la tabla.
- [ ] Las acciones tienen area tactil utilizable.
- [ ] El dialogo de rechazo permanece dentro del viewport.
- [ ] La prioridad critica se identifica con texto, no solo color.
