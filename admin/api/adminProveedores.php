<?php 
include_once "conectorBD.php";

class Proveedor { 
    //SELECT `id`, `correo`, `rfc`, `periodo_pago`, `nombre`, `direccion` FROM `proveedores` WHERE 1
    public $id;
    public $correo;
    public $rfc;
    public $periodo_pago;
    public $nombre;
    public $direccion;
    public $periodo_dias;

    public function __construct($id, $correo, $rfc, $periodo_pago, $nombre, $direccion) {
        $this->id = $id;
        $this->correo = $correo;
        $this->rfc = $rfc;
        $this->periodo_pago = $periodo_pago;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
    }
}



class AdministradorProveedores extends conector {


    public function agregarProveedor($rfc, $nombre, $correo, $direccion, $periodo_pago){
        return $this->ejecutarPreparado(
            "INSERT INTO proveedores (correo, rfc, periodo_dias, nombre, direccion) VALUES (?, ?, ?, ?, ?)",
            'ssiss',
            $correo,
            $rfc,
            $periodo_pago,
            $nombre,
            $direccion
        );
    }
    public function modificarProveedor($rfc, $nombre, $correo, $direccion, $periodo_pago,$id){
        return $this->ejecutarPreparado(
            "UPDATE proveedores
             SET correo = ?, rfc = ?, periodo_dias = ?, nombre = ?, direccion = ?
             WHERE id = ?",
            'ssissi',
            $correo,
            $rfc,
            $periodo_pago,
            $nombre,
            $direccion,
            $id
        );

    }

    public function dameProveedores(){
        $query = "SELECT id, correo, rfc, periodo_dias, nombre, direccion FROM proveedores";
        $result = $this->ejecutar($query);
        $proveedores = array();
        while($row = $result->fetch_assoc()) {
            $proveedor = new Proveedor($row["id"], $row["correo"], $row["rfc"], $row["periodo_dias"], $row["nombre"], $row["direccion"]);
            array_push($proveedores, $proveedor);
        }
        return $proveedores;
    }


    public function dameProveedor($id){
        $query = "SELECT id, correo, rfc, periodo_dias, nombre, direccion
                  FROM proveedores WHERE id = ? LIMIT 1";
        $result = $this->ejecutarPreparado($query, 'i', $id);
        $row = $result->fetch_assoc();
        $proveedor = new Proveedor($row["id"], $row["correo"], $row["rfc"], $row["periodo_dias"], $row["nombre"], $row["direccion"]);
        return $proveedor;
    }
    public function eliminarProvedor($id){
        return $this->ejecutarPreparado("DELETE FROM proveedores WHERE id = ?", 'i', $id);
        

    }

    
}
