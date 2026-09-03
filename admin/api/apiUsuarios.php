<?php 
include "adminUsuarios.php";
session_start();


$accion = $_POST["accion"];


$casoInicio = "inicio";
$casoAgregar = "agregar";
$casoModificar = "modificar";
$casoEliminar = "eliminar";


function iniciaSesion()
{
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];
    $adminUsuarios = new AdministradorUsuario();
    $usuario = $adminUsuarios->dameUsuario($usuario, $contrasena);
    if($usuario->id != 0)
    {
        $_SESSION["usuario"] = $usuario->usuario;
        $_SESSION["admindif_admin_id"] = $usuario->admindif_admin_id;
        $_SESSION["usuario_id"] = $usuario->id;
        $_SESSION["tipo_usuario"] = $usuario->tipo_usuario;
        $_SESSION["id_cliente_14"] = $usuario->id_cliente_14;

        $respuesta = array("estatus" => "exito", "mensaje" => "Bienvenido " . $usuario->usuario, "tipo" => $usuario->tipo_usuario);
    }
    else{
        $respuesta = array("estatus" => "error", "mensaje" => "Usuario o contraseña incorrectos");
    }
    echo json_encode($respuesta);
}

function agregarUsuario(){
    $adminUsuarios=new AdministradorUsuario();
    $tipo=$_POST['tipo-usu'];
    $nombre=$_POST['nombre'];
    $email=$_POST['email'];
    $pass=$_POST['contrasena'];
    $adminUsuarios->agregarUsuario($nombre,"","",$email,$pass,"",$tipo,0);
    $mensaje = array();
    $mensaje['mensaje'] = "Usuario agregado";
    $mensaje['status'] = "success";
    $mensaje['submensaje']='exito';
    echo json_encode($mensaje);

}
function eliminarUsuario(){
    $id=$_POST['id'];
    $adminUsuarios=new AdministradorUsuario();
    $adminUsuarios->eliminaUsuario($id);
    $mensaje=array ();
    $mensaje['status']='success';
    $mensaje['mensaje']='El usuario se ha eliminado con exito';
    $mensaje['submensaje']='exito';
    echo json_encode($mensaje);

}
function modificarUsuario(){
    $id=$_POST['id'];
    $adminUsuarios=new AdministradorUsuario();
    $tipo=$_POST['tipo-usu'];
    $nombre=$_POST['nombre'];
    $email=$_POST['email'];
    $pass=$_POST['contrasena'];
    $adminUsuarios->modificarUsaurio($id,$nombre,"","",$email,$pass,"",$tipo,0); 
    $mensaje['mensaje'] = "Usuario modificado";
    $mensaje['status'] = "success";
    $mensaje['submensaje']='exito';
    echo json_encode($mensaje);
}


switch ($accion) {
    case $casoInicio:iniciaSesion();
    break;
    case $casoAgregar: agregarUsuario();
    break;
    case $casoEliminar: eliminarUsuario();
    break;
    case $casoModificar: modificarUsuario();
    break;
}
