<?php
include_once "conectorBD.php";

class Pago
{
    //SELECT `id`, `id_factura`, `monto`, `fecha`, `metodo_pago`, `usuario_inserta`, `fecha_inserta` FROM `pagos` WHERE 1
    public $id;
    public $id_factura;
    public $monto;
    public $fecha;
    public $metodo_pago;
    public $usuario_inserta;
    public $fecha_inserta;
}


class AdministradorPagos extends conector
{
    public function agregaPago($id_factura, $monto, $metodo_pago, $fecha, $usuario_inserta)
    {
        $query = "INSERT INTO iohanes_ojo.pagos
        ( id_factura, monto, fecha, metodo_pago, usuario_inserta, fecha_inserta)
        VALUES('$id_factura' , '$monto', '$fecha', '$metodo_pago', '$usuario_inserta', current_timestamp());";
        $result =  $this->ejecutar($query);
    }

    public function damePagos()
    {
        $query = "SELECT * FROM pagos";
        $result =  $this->ejecutar($query);
        $pagos = array();
        while ($row = mysqli_fetch_array($result)) {
            $pago = new Pago();
            $pago->id = $row['id'];
            $pago->id_factura = $row['id_factura'];
            $pago->monto = $row['monto'];
            $pago->fecha = $row['fecha'];
            $pago->metodo_pago = $row['metodo_pago'];
            $pago->usuario_inserta = $row['usuario_inserta'];
            $pago->fecha_inserta = $row['fecha_inserta'];
            array_push($pagos, $pago);
        }
        return $pagos;
    }

    public function damePago($id_pago)
    {
        $query = "SELECT * FROM pagos WHERE id = $id_pago";
        $result = $this->ejecutar($query);
        $pago = new Pago();
        while ($row = mysqli_fetch_array($result)) {
            $pago->id = $row['id'];
            $pago->id_factura = $row['id_factura'];
            $pago->monto = $row['monto'];
            $pago->fecha = $row['fecha'];
            $pago->metodo_pago = $row['metodo_pago'];
            $pago->usuario_inserta = $row['usuario_inserta'];
            $pago->fecha_inserta = $row['fecha_inserta'];
        }
        return $pago;
    }

    public function eliminarPago($id){
        $query="DELETE FROM iohanes_ojo.pagos
        WHERE id=$id;";
        $result =  $this->ejecutar($query);
        

    }
}
