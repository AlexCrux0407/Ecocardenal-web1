# Instrucciones para Actualizar la Tabla de Logros

Se ha detectado que la tabla `logros` en la base de datos no tiene todas las columnas necesarias para el correcto funcionamiento del sistema de logros. Este documento proporciona instrucciones para actualizar la tabla y resolver el problema.

## Problema Detectado

La tabla `logros` actualmente solo tiene las siguientes columnas:
- `id`
- `nombre`
- `descripcion`

Sin embargo, según el código de la aplicación, la tabla debería tener las siguientes columnas adicionales:
- `icono`
- `tipo` (actividades, puntos, experimentos, quizzes)
- `objetivo`
- `puntos_recompensa`
- `created_at`
- `updated_at`

Esta discrepancia está causando que los logros no se activen correctamente, ya que el sistema no puede calcular el progreso ni verificar si se cumplen los objetivos.

## Solución

Se han creado tres archivos para resolver este problema:

1. **Migración de Laravel**: `database/migrations/2024_06_20_000001_update_logros_table.php`
2. **Script Batch**: `actualizar_logros.bat`
3. **Script PHP**: `actualizar_logros.php`

### Opción 1: Usar el Script Batch (Windows)

1. Cierre el servidor de Laravel si está en ejecución.
2. Haga doble clic en el archivo `actualizar_logros.bat`.
3. Siga las instrucciones en pantalla.
4. Reinicie el servidor de Laravel.

### Opción 2: Usar el Script PHP

1. Cierre el servidor de Laravel si está en ejecución.
2. Abra una terminal en la raíz del proyecto.
3. Ejecute el siguiente comando:
   ```
   php actualizar_logros.php
   ```
4. Siga las instrucciones en pantalla.
5. Reinicie el servidor de Laravel.

### Opción 3: Ejecutar la Migración Manualmente

1. Cierre el servidor de Laravel si está en ejecución.
2. Abra una terminal en la raíz del proyecto.
3. Ejecute el siguiente comando:
   ```
   php artisan migrate --path=database/migrations/2024_06_20_000001_update_logros_table.php
   ```
4. Reinicie el servidor de Laravel.

## Verificación

Para verificar que la actualización se ha realizado correctamente, puede ejecutar la siguiente consulta SQL:

```sql
USE ecocardenal;
DESCRIBE logros;
SELECT id, nombre, tipo, objetivo, puntos_recompensa FROM logros LIMIT 5;
```

Debería ver todas las columnas y los datos actualizados.

## Notas Adicionales

- Si encuentra algún error durante la actualización, por favor contacte al administrador del sistema.
- Es recomendable hacer una copia de seguridad de la base de datos antes de realizar la actualización.
- Después de la actualización, es posible que necesite borrar la caché de la aplicación con `php artisan cache:clear`.