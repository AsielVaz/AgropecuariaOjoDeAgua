<?php 
session_start();
include "adminPagos.php";

$accion = $_POST['accion'];

$casoAgregar = "agregar";
$casoModificar = "modificar";
$casoEliminar = "eliminar";


function agregarPago(){
    $adminPagos= new AdministradorPagos();
    // public function agregaPago($id_factura, $monto, $metodo_pago, $fecha, $usuario_inserta)

    $id_factura=$_POST['id'];
    $metodo_pago=$_POST['metodo_pago'];
    $fecha=$_POST['fecha'];
    $monto=$_POST['monto'];

    $adminPagos->agregaPago($id_factura,$monto,$metodo_pago,$fecha,$_SESSION["usuario_id"]);
    

    $mensaje=array ();
    $mensaje['status']='success';
    $mensaje['mensaje']='el registro se agrego con exito';
    $mensaje['submensaje']='exito';
    echo json_encode($mensaje);
}
function eliminarPago(){
    $id=$_POST['id'];
    $adminPagos= new AdministradorPagos();
    $adminPagos->eliminarPago($id);
    $mensaje=array ();
    $mensaje['status']='success';
    $mensaje['mensaje']='El registro se ha eliminado con exito';
    $mensaje['submensaje']='exito';
    echo json_encode($mensaje);

}
switch($accion){
    case $casoAgregar: agregarPago();
    break;
    case $casoEliminar:eliminarPago();
    break;
}