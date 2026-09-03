-- Índices para las consultas del panel AOA.
-- Compatible con MariaDB 10.5+ / 12.x y seguro para ejecutar más de una vez.

CREATE INDEX IF NOT EXISTS idx_factura_estado_vencimiento
    ON factura (eliminado, fecha_vencimiento);

CREATE INDEX IF NOT EXISTS idx_factura_xml
    ON factura (id_ingreso_xml);

CREATE INDEX IF NOT EXISTS idx_factura_proveedor
    ON factura (id_provedor);

CREATE INDEX IF NOT EXISTS idx_evidencia_factura
    ON factura_evidencia (id_factura);

CREATE INDEX IF NOT EXISTS idx_xml_pendientes
    ON xml_ingresados (ingresado, id);

CREATE INDEX IF NOT EXISTS idx_proveedores_rfc
    ON proveedores (rfc);

CREATE INDEX IF NOT EXISTS idx_pagos_factura
    ON pagos (id_factura);

CREATE INDEX IF NOT EXISTS idx_aplazos_factura
    ON aplazo_de_factura (id_factura);

CREATE INDEX IF NOT EXISTS idx_usuarios_login
    ON usuarios (email, password);
