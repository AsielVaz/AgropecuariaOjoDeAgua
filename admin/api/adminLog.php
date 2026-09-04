<?php 
include_once "conectorBD.php";


class LogFactura{
    public $id;
    public $correo;
    public $fecha;
    public $mensaje;

}




class AdministradorLog extends conector{
    public function dameLog($limite = 20, $offset = 0){
        $limite = max(1, min(100, (int) $limite));
        $offset = max(0, (int) $offset);
        $query = "SELECT id, correo, fecha, mensaje
                  FROM log_facturas
                  ORDER BY id DESC
                  LIMIT ? OFFSET ?";
        $resultado = $this->ejecutarPreparado($query, 'ii', $limite, $offset);
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

    public function contarLogs(){
        $resultado = $this->ejecutar("SELECT COUNT(*) AS total FROM log_facturas");
        $fila = $resultado->fetch_assoc();
        return (int) ($fila['total'] ?? 0);
    }
}
