<?php
include_once 'conectorBD.php';

class Factura
{
    public $id;
    public $id_provedor;
    public $folio_fiscal;
    public $serie;
    public $folio;
    public $fecha_de_ingreso;
    public $metodo_de_pago;
    public $total_con_iva;
    public $total_sin_iva;
    public $estatus_factura;
    public $fecha_timbrado;
    public $uso_cfdi;
    public $uuid;
    public $ruta_xml;
    public $ruta_pdf;
    public $id_xml;
    public $correo_ingreso;
    public $fecha_pago;
    public $nombre_proveedor;
    public $moneda;
}

class AdministradorFacturas extends conector
{
    private function columnasFactura()
    {
        return 'f.id, f.id_provedor, f.folio_fiscal, f.serie, f.folio,
            f.fecha_de_ingreso, f.metodo_de_pago, f.total_con_iva,
            f.total_sin_iva, f.estatus_factura, f.fecha_timbrado,
            f.uso_cfdi, f.uuid, f.id_ingreso_xml, f.eliminado,
            f.fecha_vencimiento, f.moneda,
            x.ruta, x.ruta_pdf,
            p.nombre AS nombre_proveedor';
    }

    private function crearFactura(array $row)
    {
        $factura = new Factura();
        $factura->id = $row['id'];
        $factura->id_provedor = $row['id_provedor'];
        $factura->folio_fiscal = $row['folio_fiscal'];
        $factura->serie = $row['serie'];
        $factura->folio = $row['folio'];
        $factura->fecha_de_ingreso = $row['fecha_de_ingreso'];
        $factura->metodo_de_pago = $row['metodo_de_pago'];
        $factura->total_con_iva = $row['total_con_iva'];
        $factura->total_sin_iva = $row['total_sin_iva'];
        $factura->estatus_factura = $row['estatus_factura'];
        $factura->fecha_timbrado = $row['fecha_timbrado'];
        $factura->uso_cfdi = $row['uso_cfdi'];
        $factura->uuid = $row['uuid'];
        $factura->ruta_xml = $row['ruta'] ?? null;
        $factura->ruta_pdf = $row['ruta_pdf'] ?? null;
        $factura->id_xml = $row['id_xml'] ?? $row['id_ingreso_xml'] ?? null;
        $factura->correo_ingreso = $row['correo'] ?? null;
        $factura->fecha_pago = $row['fecha_vencimiento'];
        $factura->nombre_proveedor = $row['nombre_proveedor'] ?? null;
        $factura->moneda = $row['moneda'];

        return $factura;
    }

    private function obtenerFacturas($sql, $types = '', ...$params)
    {
        $result = $types === ''
            ? $this->ejecutar($sql)
            : $this->ejecutarPreparado($sql, $types, ...$params);

        $facturas = [];
        while ($row = $result->fetch_assoc()) {
            $facturas[] = $this->crearFactura($row);
        }

        return $facturas;
    }

    private function consultaBase()
    {
        return 'SELECT ' . $this->columnasFactura() . '
            FROM factura f
            LEFT JOIN xml_ingresados x ON x.id = f.id_ingreso_xml
            LEFT JOIN proveedores p ON p.id = f.id_provedor';
    }

    public function dameFacturasPagadas()
    {
        return $this->obtenerFacturas(
            $this->consultaBase() . '
            WHERE EXISTS (
                SELECT 1
                FROM factura_evidencia fe
                WHERE fe.id_factura = f.id
            )'
        );
    }

    public function dameFacturas()
    {
        $limite = date('Y-m-d', strtotime('next sunday'));

        return $this->obtenerFacturas(
            $this->consultaBase() . '
            WHERE f.eliminado = 0
              AND f.fecha_vencimiento > ?
              AND NOT EXISTS (
                  SELECT 1
                  FROM factura_evidencia fe
                  WHERE fe.id_factura = f.id
              )',
            's',
            $limite
        );
    }

    public function dameFacturasVencidas()
    {
        $hoy = date('Y-m-d');

        return $this->obtenerFacturas(
            $this->consultaBase() . '
            WHERE f.eliminado = 0
              AND f.fecha_vencimiento < ?
              AND NOT EXISTS (
                  SELECT 1
                  FROM factura_evidencia fe
                  WHERE fe.id_factura = f.id
              )',
            's',
            $hoy
        );
    }

    public function dameFacturasEstaSemana()
    {
        $limite = date('Y-m-d', strtotime('next sunday'));

        return $this->obtenerFacturas(
            $this->consultaBase() . '
            WHERE f.eliminado = 0
              AND f.fecha_vencimiento <= ?
              AND NOT EXISTS (
                  SELECT 1
                  FROM factura_evidencia fe
                  WHERE fe.id_factura = f.id
              )',
            's',
            $limite
        );
    }

    public function sumaFacturasEstaSemana()
    {
        $limite = date('Y-m-d', strtotime('next sunday'));
        $result = $this->ejecutarPreparado(
            'SELECT COALESCE(SUM(f.total_con_iva), 0) AS total
             FROM factura f
             WHERE f.eliminado = 0
               AND f.fecha_vencimiento <= ?
               AND NOT EXISTS (
                   SELECT 1
                   FROM factura_evidencia fe
                   WHERE fe.id_factura = f.id
               )',
            's',
            $limite
        );
        $row = $result->fetch_assoc();

        return (float) $row['total'];
    }

    public function sumaTodo()
    {
        $limite = date('Y-m-d', strtotime('next sunday'));
        $result = $this->ejecutarPreparado(
            'SELECT COALESCE(SUM(f.total_con_iva), 0) AS total
             FROM factura f
             WHERE f.eliminado = 0
               AND f.fecha_vencimiento > ?
               AND NOT EXISTS (
                   SELECT 1
                   FROM factura_evidencia fe
                   WHERE fe.id_factura = f.id
               )',
            's',
            $limite
        );
        $row = $result->fetch_assoc();

        return (float) $row['total'];
    }

    public function eliminarFactura($id)
    {
        return $this->ejecutarPreparado(
            'UPDATE factura SET eliminado = 1 WHERE id = ?',
            'i',
            $id
        );
    }

    public function dameFacturasEliminadas()
    {
        return $this->obtenerFacturas(
            $this->consultaBase() . '
            WHERE f.eliminado = 1'
        );
    }

    public function dameFactura($id_fac)
    {
        $result = $this->ejecutarPreparado(
            'SELECT ' . $this->columnasFactura() . ',
                x.id AS id_xml, x.correo
             FROM factura f
             LEFT JOIN xml_ingresados x ON x.id = f.id_ingreso_xml
             LEFT JOIN proveedores p ON p.id = f.id_provedor
             WHERE f.id = ?
             LIMIT 1',
            'i',
            $id_fac
        );
        $row = $result->fetch_assoc();

        return $row ? $this->crearFactura($row) : new Factura();
    }

    public function aplazarFactura($id, $dias)
    {
        $usuario = $_SESSION['usuario'] ?? '';

        $this->iniciarTransaccion();
        try {
            $this->ejecutarPreparado(
                'INSERT INTO aplazo_de_factura
                    (id_factura, plazo_anterior, plazo_posterior, usuario_inserta, fecha_inserta)
                 SELECT id, fecha_vencimiento, ?, ?, NOW()
                 FROM factura
                 WHERE id = ?',
                'ssi',
                $dias,
                $usuario,
                $id
            );
            $this->ejecutarPreparado(
                'UPDATE factura SET fecha_vencimiento = ? WHERE id = ?',
                'si',
                $dias,
                $id
            );
            $this->confirmarTransaccion();
        } catch (Throwable $error) {
            $this->revertirTransaccion();
            throw $error;
        }
    }

    public function dameUltimoId()
    {
        $result = $this->ejecutar('SELECT MAX(id) AS id FROM factura');
        $row = $result->fetch_assoc();

        return (int) ($row['id'] ?? 0);
    }
}
