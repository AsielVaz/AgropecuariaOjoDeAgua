<?php
require_once dirname(__DIR__) . '/session.php';
require_once __DIR__ . '/adminUsuarios.php';

header('Content-Type: application/json; charset=utf-8');


$accion = $_POST['accion'] ?? '';


$casoInicio = "inicio";
$casoAgregar = "agregar";
$casoModificar = "modificar";
$casoEliminar = "eliminar";


function iniciaSesion()
{
    $email = trim($_POST['usuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $adminUsuarios = new AdministradorUsuario();
    $usuario = $adminUsuarios->dameUsuario($email, $contrasena);
    if($usuario->id != 0)
    {
        session_regenerate_id(true);
        $_SESSION['usuario'] = $usuario->usuario ?: $usuario->email;
        $_SESSION['usuario_id'] = (int) $usuario->id;
        $_SESSION['tipo_usuario'] = $usuario->tipoUsuario;
        $_SESSION['id_cliente_14'] = $usuario->clienteId;

        $respuesta = array('estatus' => 'exito', 'mensaje' => 'Bienvenido ' . $usuario->nombre, 'tipo' => $usuario->tipoUsuario);
        session_write_close();
    }
    else{
        $respuesta = array("estatus" => "error", "mensaje" => "Usuario o contraseña incorrectos");
    }
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
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
