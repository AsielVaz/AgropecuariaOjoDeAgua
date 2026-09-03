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
        $query = "INSERT INTO `proveedores` (`correo`, `rfc`, `periodo_dias`, `nombre`, `direccion`) VALUES ( '$correo', '$rfc', '$periodo_pago', '$nombre', '$direccion')";
        $this->ejecutar($query);
    }
    public function modificarProveedor($rfc, $nombre, $correo, $direccion, $periodo_pago,$id){
        $query="UPDATE iohanes_ojo.proveedores
        SET correo='$correo', rfc='$rfc', periodo_dias='$periodo_pago', nombre='$nombre', direccion='$direccion'
        WHERE id=$id;";
        $this->ejecutar($query);

    }

    public function dameProveedores(){
        $query = "SELECT `id`, `correo`, `rfc`, `periodo_dias`, `nombre`, `direccion` FROM `proveedores` WHERE 1";
        $result = $this->ejecutar($query);
        $proveedores = array();
        while($row = $result->fetch_assoc()) {
            $proveedor = new Proveedor($row["id"], $row["correo"], $row["rfc"], $row["periodo_dias"], $row["nombre"], $row["direccion"]);
            array_push($proveedores, $proveedor);
        }
        return $proveedores;
    }


    public function dameProveedor($id){
        $query = "SELECT `id`, `correo`, `rfc`, `periodo_dias`, `nombre`, `direccion` FROM `proveedores` WHERE id = $id";
        $result = $this->ejecutar($query);
        $row = $result->fetch_assoc();
        $proveedor = new Proveedor($row["id"], $row["correo"], $row["rfc"], $row["periodo_dias"], $row["nombre"], $row["direccion"]);
        return $proveedor;
    }
    public function eliminarProvedor($id){
        $query="DELETE FROM iohanes_ojo.proveedores
        WHERE id=$id;";
        $result =  $this->ejecutar($query);
        

    }

    
}