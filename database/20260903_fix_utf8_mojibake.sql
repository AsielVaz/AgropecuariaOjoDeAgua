-- Corrige texto UTF-8 que anteriormente fue interpretado como Windows-1252.
-- La condición binaria evita modificar caracteres legítimos o filas ya corregidas.
START TRANSACTION;

UPDATE proveedores
   SET nombre = REPLACE(
       nombre,
       CONVERT(0xC383E28098 USING utf8mb4),
       CONVERT(0xC391 USING utf8mb4)
   )
 WHERE INSTR(CAST(nombre AS BINARY), 0xC383E28098) > 0;

COMMIT;
