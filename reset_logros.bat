@echo off
echo Reseteando logros de usuarios...
echo.

REM Cambiar al directorio del proyecto
cd /d "%~dp0"

REM Ejecutar el comando artisan para resetear los logros
php artisan logros:reset

echo.
echo Si el comando anterior falló, es posible que necesites ejecutar manualmente:
echo php artisan logros:reset
echo.
echo O acceder al panel de administración en /admin y usar el botón de reseteo.

pause