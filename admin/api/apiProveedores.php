<?php
require_once dirname(__DIR__) . '/session.php';
require_once __DIR__ . '/adminProveedores.php';

$accion = $_POST['accion'];

$casoAgregar = "agregar";
$casoModificar = "modificar";
$casoEliminar = "eliminar";



function agregarProveedor(){
    $adminProveedores = new AdministradorProveedores();
    $rfc = $_POST['rfc'];
    $nombre = $_POST['nombre'];
    $email = $_POST['correo'];
    $direccion = $_POST['direccion'];
    $periodo = $_POST['periodo_pago'];
    $adminProveedores->agregarProveedor($rfc, $nombre, $email, $direccion, $periodo);
    $mensaje = array();
    $mensaje['mensaje'] = "Proveedor agregado";
    $mensaje['status'] = "success";
    $mensaje['submensaje']='exito';
    echo json_encode($mensaje);
}
function eliminarProveedor(){
    $id=$_POST['id'];
    $adminProveedores= new AdministradorProveedores();
    $adminProveedores->eliminarProvedor($id);
    $mensaje=array ();
    $mensaje['status']='success';
    $mensaje['mensaje']='El proveedor se ha eliminado con exito';
    $mensaje['submensaje']='exito';
    echo json_encode($mensaje);

}
function modificarProveedor(){
    $adminProveedores = new AdministradorProveedores();
    $rfc = $_POST['rfc'];
    $nombre = $_POST['nombre'];
    $email = $_POST['correo'];
    $direccion = $_POST['direccion'];
    $periodo = $_POST['periodo_pago'];
    $id=$_POST['id'];
    $adminProveedores->modificarProveedor($rfc, $nombre, $email, $direccion, $periodo,$id);
    $mensaje = array();
    $mensaje['mensaje'] = "Proveedor modificado";
    $mensaje['status'] = "success";
    $mensaje['submensaje']='exito';
    echo json_encode($mensaje);
}
switch($accion){
    case $casoAgregar: agregarProveedor();
    break;
    case $casoEliminar: eliminarProveedor();
    break;
    case $casoModificar: modificarProveedor();
    break;
}
