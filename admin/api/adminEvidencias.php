<?php 
include_once "conectorBD.php";

class Evidencia{
    //SELECT `id`, `id_factura`, `url`, `fecha_ingresa`, `usuario_ingresa` FROM `factura_evidencia` WHERE 1
    public $id; 
    public $id_factura;
    public $url;
    public $fecha_ingresa;
    public $usuario_ingresa;
    public $linkFactura;
    public $monto;
    public $proveedor;
    public $rfcProveedor;

    public function __construct($id, $id_factura, $url, $fecha_ingresa, $usuario_ingresa){
        $this->id = $id;
        $this->id_factura = $id_factura;
        $this->url = $url;
        $this->fecha_ingresa = $fecha_ingresa;
        $this->usuario_ingresa = $usuario_ingresa;
    }
}


class AdministradorEvidencias extends conector{


    public function dameEvidencias(){
        $query = "SELECT factura_evidencia.*, xml_ingresados.ruta_pdf as ruta_fac, factura.total_con_iva, proveedores.nombre, proveedores.rfc  FROM factura_evidencia
        left join factura
        on factura.id = factura_evidencia.id_factura
        left join proveedores 
        on proveedores.id = factura.id_provedor
        left join xml_ingresados
        on xml_ingresados.id = factura.id_ingreso_xml
        GROUP by factura_evidencia.id;";
        $resultado = $this->ejecutar($query);
        $evidencias = array();
        while($fila = $resultado->fetch_assoc()){
            $evidencia = new Evidencia($fila["id"], $fila["id_factura"], $fila["url"], $fila["fecha_ingresa"], $fila["usuario_ingresa"]);
            $evidencia->linkFactura = $fila["ruta_fac"];
            $evidencia->monto = $fila["total_con_iva"];
            $evidencia->proveedor = $fila["nombre"];
            $evidencia->rfcProveedor = $fila["rfc"];
            array_push($evidencias, $evidencia);
           
        }
        return $evidencias;
    }

    public function dameEvidenciasFac($id_factura){
        $query = "SELECT * FROM factura_evidencia WHERE id_factura = $id_factura";
        $resultado = $this->ejecutar($query);
        $evidencias = array();
        while($fila = $resultado->fetch_assoc()){
            $evidencia = new Evidencia($fila["id"], $fila["id_factura"], $fila["url"], $fila["fecha_ingresa"], $fila["usuario_ingresa"]);
            array_push($evidencias, $evidencia);
        }
        return $evidencias;
    }

    public function dameEvidencia($id){
        $query = "SELECT * FROM factura_evidencia WHERE id = $id";
        $resultado = $this->ejecutar($query);
        $fila = $resultado->fetch_assoc();
        $evidencia = new Evidencia($fila["id"], $fila["id_factura"], $fila["url"], $fila["fecha_ingresa"], $fila["usuario_ingresa"]);
        return $evidencia;
    }

    public function agregaEvidencia($id_factura, $url, $usuario_ingresa){
        $query = "INSERT INTO factura_evidencia (id_factura, url, usuario_ingresa) VALUES ($id_factura, '$url', '$usuario_ingresa')";
        $resultado = $this->ejecutar($query);
        return $resultado;
    }

    public function eliminaEvidencia($id){
        $query = "DELETE FROM factura_evidencia WHERE id = $id";
        $resultado = $this->ejecutar($query);
        return $resultado;
    }


}