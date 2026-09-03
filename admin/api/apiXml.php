<?php 
include "conectorBD.php";

class Xml{
    //SELECT `id`, `ruta`, `ingresado`, `fecha_ingreso` FROM `xml_ingresados` WHERE 1
    public $id;
    public $ruta;
    public $ingresado;
    public $fecha_ingreso;
    
}




class AdministradorXml extends conector{


    public function dameXmls(){
        $query = "SELECT id, ruta, ingresado, fecha_ingreso FROM xml_ingresados";
        $result = $this->ejecutar($query);
        $xmls = array();
        while($row = mysqli_fetch_array($result)){
            $xml = new Xml();
            $xml->id = $row['id'];
            $xml->ruta = $row['ruta'];
            $xml->ingresado = $row['ingresado'];
            $xml->fecha_ingreso = $row['fecha_ingreso'];
            array_push($xmls, $xml);
        }
        return $xmls;
    }

    public function dameXml($id){
        $query = "SELECT id, ruta, ingresado, fecha_ingreso FROM xml_ingresados WHERE id = ? LIMIT 1";
        $result = $this->ejecutarPreparado($query, 'i', $id);
        $xmls = array();
        while($row = mysqli_fetch_array($result)){
            $xml = new Xml();
            $xml->id = $row['id'];
            $xml->ruta = $row['ruta'];
            $xml->ingresado = $row['ingresado'];
            $xml->fecha_ingreso = $row['fecha_ingreso'];
            array_push($xmls, $xml);
        }
        return $xmls;
    }


}
