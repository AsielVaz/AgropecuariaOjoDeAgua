<?php 
include_once "conectorBD.php";


class LogFactura{
    public $id;
    public $correo;
    public $fecha;
    public $mensaje;

}




class AdministradorLog extends conector{
    public function dameLog(){
        $query = "SELECT id, correo, fecha, mensaje FROM log_facturas";
        $resultado = $this->ejecutar($query);
        $logs = array();
        while($fila = $resultado->fetch_assoc()){
            $log = new LogFactura();
            $log->id = $fila['id'];
            $log->correo = $fila['correo'];
            $log->fecha = $fila['fecha'];
            $log->mensaje = $fila['mensaje'];

            array_push($logs, $log);
        }
        return $logs;
    }
}
