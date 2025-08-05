@echo off
echo Actualizando tabla de logros...

REM Buscar PHP en el sistema
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo PHP no encontrado en el PATH. Buscando en ubicaciones comunes...
    
    REM Comprobar ubicaciones comunes de PHP
    set PHP_PATHS=C:\xampp\php\php.exe C:\wamp64\bin\php\php8.1.0\php.exe C:\wamp\bin\php\php8.1.0\php.exe C:\laragon\bin\php\php-8.1.0\php.exe
    
    for %%p in (%PHP_PATHS%) do (
        if exist %%p (
            echo Encontrado PHP en: %%p
            set PHP_PATH=%%p
            goto :found_php
        )
    )
    
    echo No se pudo encontrar PHP. Por favor, instale PHP o agregue su ubicación al PATH.
    goto :error
) else (
    set PHP_PATH=php
    echo Usando PHP del PATH del sistema.
)

:found_php
echo Ejecutando migración para actualizar la tabla de logros...

"%PHP_PATH%" artisan migrate --path=database/migrations/2024_06_20_000001_update_logros_table.php

if %ERRORLEVEL% NEQ 0 (
    echo Error al ejecutar la migración.
    goto :error
)

echo Migración completada con éxito.
echo.
echo Verificando estructura de la tabla logros...
"%PHP_PATH%" -r "echo shell_exec('mysql -u root -p1917248zzz -e \"USE ecocardenal; DESCRIBE logros;\"');"

echo.
echo Verificando datos de la tabla logros...
"%PHP_PATH%" -r "echo shell_exec('mysql -u root -p1917248zzz -e \"USE ecocardenal; SELECT id, nombre, tipo, objetivo, puntos_recompensa FROM logros LIMIT 5;\"');"

echo.
echo Proceso completado.
echo Para activar los logros, reinicie el servidor de Laravel.
pause
goto :end

:error
echo.
echo Ocurrió un error durante el proceso.
pause

:end