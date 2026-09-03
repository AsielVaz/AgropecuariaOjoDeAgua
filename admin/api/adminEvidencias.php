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
        $query = "SELECT fe.id, fe.id_factura, fe.url, fe.fecha_ingresa, fe.usuario_ingresa,
            x.ruta_pdf AS ruta_fac, f.total_con_iva, p.nombre, p.rfc
        FROM factura_evidencia fe
        LEFT JOIN factura f ON f.id = fe.id_factura
        LEFT JOIN proveedores p ON p.id = f.id_provedor
        LEFT JOIN xml_ingresados x ON x.id = f.id_ingreso_xml";
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
        $resultado = $this->ejecutarPreparado(
            "SELECT id, id_factura, url, fecha_ingresa, usuario_ingresa
             FROM factura_evidencia WHERE id_factura = ?",
            'i',
            $id_factura
        );
        $evidencias = array();
        while($fila = $resultado->fetch_assoc()){
            $evidencia = new Evidencia($fila["id"], $fila["id_factura"], $fila["url"], $fila["fecha_ingresa"], $fila["usuario_ingresa"]);
            array_push($evidencias, $evidencia);
        }
        return $evidencias;
    }

    public function dameEvidencia($id){
        $resultado = $this->ejecutarPreparado(
            "SELECT id, id_factura, url, fecha_ingresa, usuario_ingresa
             FROM factura_evidencia WHERE id = ? LIMIT 1",
            'i',
            $id
        );
        $fila = $resultado->fetch_assoc();
        $evidencia = new Evidencia($fila["id"], $fila["id_factura"], $fila["url"], $fila["fecha_ingresa"], $fila["usuario_ingresa"]);
        return $evidencia;
    }

    public function agregaEvidencia($id_factura, $url, $usuario_ingresa){
        $resultado = $this->ejecutarPreparado(
            "INSERT INTO factura_evidencia (id_factura, url, usuario_ingresa) VALUES (?, ?, ?)",
            'isi',
            $id_factura,
            $url,
            $usuario_ingresa
        );
        return $resultado;
    }

    public function eliminaEvidencia($id){
        $resultado = $this->ejecutarPreparado(
            "DELETE FROM factura_evidencia WHERE id = ?",
            'i',
            $id
        );
        return $resultado;
    }


}
