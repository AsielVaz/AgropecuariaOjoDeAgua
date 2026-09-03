<?php 
session_start();
include "adminFacturas.php";

$accion = $_POST['accion'];

$casoAgregar = "agregar";
$casoModificar = "modificar";
$casoEliminar = "eliminar";
$casoAplazar = "aplazar";

function eliminar(){
    $id = $_POST['id'];
    $admin = new AdministradorFacturas();
    $admin->eliminarFactura($id);
    $mensaje = array();
    $mensaje['mensaje'] = "Factura eliminada";
    $mensaje['status'] = "success";
    echo json_encode($mensaje);
}

function aplazar(){
    $id = $_POST['id'];
    $dias = $_POST['dias'];
    $admin = new AdministradorFacturas();
    $admin->aplazarFactura($id, $dias);
    $mensaje = array();
    $mensaje['mensaje'] = "Factura aplazada";
    $mensaje['status'] = "success";
    echo json_encode($mensaje);
}

switch($accion){
    case $casoEliminar:
        eliminar();
        break;
    case $casoAplazar:
        aplazar();
        break;
}