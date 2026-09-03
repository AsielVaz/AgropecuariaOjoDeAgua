<?php 
include_once "conectorBD.php";

class Aplazo {
    //SELECT `id_factura`, `plazo_anterior`, `plazo_posterior`, `usuario_inserta`, `fecha_inserta` FROM `aplazo_de_factura` WHERE 1
    public $id;
    public $id_factura;
    public $plazo_anterior;
    public $plazo_posterior;
    public $usuario_inserta;
    public $fecha_inserta;

}



class AdministradorAplazos extends conector {

    public function dameAplazos(){
        $query = "SELECT id, id_factura, plazo_anterior, plazo_posterior, usuario_inserta, fecha_inserta
                  FROM aplazo_de_factura";
        $result = $this->ejecutar($query);
        $aplazos = array();
        while ($row = $result->fetch_assoc()) {
            $aplazo = new Aplazo();
            $aplazo->id = $row['id'];
            $aplazo->id_factura = $row['id_factura'];
            $aplazo->plazo_anterior = $row['plazo_anterior'];
            $aplazo->plazo_posterior = $row['plazo_posterior'];
            $aplazo->usuario_inserta = $row['usuario_inserta'];
            $aplazo->fecha_inserta = $row['fecha_inserta'];
            $aplazos[] = $aplazo;
        }
        return $aplazos;
    }

    public function dameAplazo($id){
        $query = "SELECT id, id_factura, plazo_anterior, plazo_posterior, usuario_inserta, fecha_inserta
                  FROM aplazo_de_factura WHERE id = ? LIMIT 1";
        $result = $this->ejecutarPreparado($query, 'i', $id);
        $row = $result->fetch_assoc();
        $aplazo = new Aplazo();
        $aplazo->id = $row['id'];
        $aplazo->id_factura = $row['id_factura'];
        $aplazo->plazo_anterior = $row['plazo_anterior'];
        $aplazo->plazo_posterior = $row['plazo_posterior'];
        $aplazo->usuario_inserta = $row['usuario_inserta'];
        $aplazo->fecha_inserta = $row['fecha_inserta'];
        return $aplazo;
    }




}
