-- Script para eliminar todos los registros de la tabla usuario_logros
DELETE FROM usuario_logros;

-- Insertar un mensaje en el log para confirmar la eliminación
INSERT INTO log_sistema (mensaje, fecha) VALUES ('Se han eliminado todos los registros de usuario_logros', NOW());