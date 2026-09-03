<?php

include_once('conectorBD.php');


class Usuario
{
  public $nombre;
  public $id;
  public $apellidoPaterno;
  public $apellidoMaterno;
  public $email;
  public $constrasena;
  public $telefono;
  public $calle;
  public $ciudad;
  public $pais;
  public $direccion;
  public $tipoUsuario;
  public $imagen;
  public $permisos;
  public $usuario;
  public $clienteId;
  public $accion;
  public $fa;


  public function __construct()
  {
    $this->id = 0;
  }
}

class Recuperacion
{
  public $id;
  public $idUsuario;
  public $token;
  public $fecha;
  public function __construct()
  {
    $this->id = 0;
  }
}

class AdministradorUsuario extends conector
{


  public function modificarImagen($id, $imagen)
  {
    $sql = 'UPDATE usuarios SET imagen = "' . $imagen . '" WHERE id = ' . $id . ';';
    $this->ejecutar($sql);
  }

  public function cuentaFacturas($usuario)
  {
    $sql = 'SELECT COUNT(*) as total FROM facturas WHERE usuario_crea = ' . $usuario . ';';
    $result = $this->ejecutar($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        return $row['total'];
      }
    } else {
      return 0;
    }
  }

  public function cuentaFacturasTotales()
  {
    $sql = 'SELECT COUNT(*) as total FROM facturas where usuario_crea != null or  usuario_crea != "";';
    $result = $this->ejecutar($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        return $row['total'];
      }
    } else {
      return 0;
    }
  }

  public function cuentaAsignados($usuario)
  {
    $sql = 'SELECT count(*) as conteo from pagos where usuario_registra = ' . $usuario . ';';
    $result = $this->ejecutar($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        return $row['conteo'];
      }
    } else {
      return 0;
    }
  }

  public function cuentaAsignadosTotales()
  {
    $sql = 'SELECT count(*) as conteo from pagos where nombre != null and tipo_movimiento != "Abono por comisión" ;';
    $result = $this->ejecutar($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        return $row['conteo'];
      }
    } else {
      return 0;
    }
  }

  public function agregarUsuario($nombre, $appat, $apmat, $email, $pass, $usuario, $tipo, $cliente)
  {
    //agregarUsuario($nombre,"",)
    //INSERT INTO `usuarios`(`id`, `nombre`, `appat`, `apmat`, `email`, `fecha_nac`, `password`, `usuario`, `tipo`, `dependencia`, `area`, `cliente`, `esclavo`, `esclavo_padre`, `razon_social`, `empresa_asignada`, `2fa`, `codigo_ver`)
    $sql = 'INSERT INTO `usuarios`(`nombre`, `appat`, `apmat`, `email`, `password`, `usuario`, `tipo`, `cliente`)' .
      ' VALUES ("' . $nombre . '","' . $appat . '","' . $apmat . '","' . $email . '","' . (sha1(md5(sha1($email . $pass)))) . '","' . $usuario . '","' . $tipo . '",' . $cliente . ');';
    $this->ejecutar($sql);
  
  }

  public function modificarUsaurio($id, $nombre, $appat, $apmat, $email, $pass, $usuario, $tipo, $cliente)
  {
    $passactu=$this->validarContrasena($id);
    if($passactu == $pass){
      $passmod=$pass;
    }else{
      $passmod=(sha1(md5(sha1($email . $pass))));
    }
    $sql = 'UPDATE `usuarios` SET `nombre`="' . $nombre . '",`appat`="' . $appat . '",`apmat`="' . $apmat . '",`email`="' . $email . '",`password`="' . $passmod . '",`usuario`="' . $usuario . '",`tipo`="' . $tipo . '",`cliente`=' . $cliente . ' WHERE id = ' . $id . ';';
    $this->ejecutar($sql);
  }

  public function validarContrasena($id){
    $usuario = new Usuario();
    $sql = 'SELECT `id`, `nombre`, `appat`, `apmat`, `email`, `fecha_nac`, `password`, `usuario`, `tipo`, `dependencia`, `area`, `cliente`, `esclavo`, `esclavo_padre`, `razon_social`, `empresa_asignada`, `2fa`, `codigo_ver`, `imagen`, `telefono`, `calle`, `ciudad`, `pais`, `direccion`, `permiso` FROM `usuarios` WHERE id = ' . $id . ';';
    $result = $this->ejecutar($sql);
   
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {

        $usuario->id = $row['id'];
        $usuario->nombre = $row['nombre'];
        $usuario->apellidoPaterno = $row['appat'];
        $usuario->apellidoMaterno = $row['apmat'];
        $usuario->email = $row['email'];
        $usuario->constrasena = $row['password'];
        $usuario->telefono = $row['telefono'];
        $usuario->calle = $row['calle'];
        $usuario->ciudad = $row['ciudad'];
        $usuario->pais = $row['pais'];
        $usuario->direccion = $row['direccion'];
        $usuario->tipoUsuario = $row['tipo'];
        $usuario->permisos = explode(",", $row['permiso']);
        $usuario->usuario = $row['usuario'];
        $usuario->fa = $row['2fa'];
        $usuario->accion = '';
        $usuario->imagen = $row['imagen'];
      }
      return $usuario->constrasena;
    } else {
      return $usuario->constrasena;
    }

    //print_r(json_encode($arregloProductos));
    return  $usuario->constrasena;
  }
  
  public function insertaRecuperacion($idUsuario, $token, $fecha)
  {
    $sql = 'INSERT INTO recuperacion (id_usuario,token,fecha) VALUES (' . $idUsuario . ',' . $token . ',"' . $fecha . '");';
    $this->ejecutar($sql);
  }

  public function dameRecuperacion($token)
  {
    $rec = new Recuperacion();
    $sql = 'SELECT * FROM recuperacion WHERE token = ' . $token . ' ORDER BY fecha;';
    $result = $this->ejecutar($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $rec->id = $row['id'];
        $rec->idUsuario = $row['id_usuario'];
        $rec->token = $row['token'];
        $rec->fecha = $row['fecha'];
      }
    } else {
    }

    //print_r(json_encode($arregloProductos));
    return $rec;
  }

  public function verificaUsuario($id, $nombre, $email)
  {
    $usuario = new Usuario();
    $sql = 'SELECT COUNT(*) as total FROM usuarios WHERE email = "' . $email . '" and nombre ="' . $nombre . '" and id = ' . $id . ';';
    $result = $this->ejecutar($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        if ($row['total'] > 0) {
          return true;
        } else {
          return false;
        }
      }
    } else {
      return false;
    }
  }


  public function dameUsuarioSuper($email, $pass)
  {

    $usuario = new Usuario();
    $sql = 'SELECT *
    FROM usuarios
    WHERE email = "' . $email . '" and password ="' . (sha1(md5(sha1($email . $pass)))) . '" and (tipo = "supercapturista" or tipo = "administrador"or tipo = "administrador_b");';
    //echo $sql;
    $result = $this->ejecutar($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $usuario->id = $row['id'];
        $usuario->imagen = $row['imagen'];
        $usuario->nombre = $row['nombre'];
        $usuario->apellidoPaterno = $row['apellido_paterno'];
        $usuario->apellidoMaterno = $row['apellido_materno'];
        $usuario->email = $row['email'];
        $usuario->constrasena = $row['password'];
        $usuario->telefono = $row['telefono'];
        $usuario->calle = $row['calle'];
        $usuario->ciudad = $row['ciudad'];
        $usuario->pais = $row['pais'];
        $usuario->direccion = $row['direccion'];
        $usuario->tipoUsuario = $row['tipo'];
        $usuario->permisos = $row['permiso'];
        $usuario->clienteId = $row['cliente'];
        $usuario->usuario = $row['usuario'];
        $usuario->imagen = $row['imagen'];
      }
      return $usuario;
    } else {
      return $usuario;
    }

    //print_r(json_encode($arregloProductos));
    return $usuario;
  }

  public function dameUsuario($email, $pass)
  {

    $usuario = new Usuario();
    $sql = 'SELECT *
    FROM usuarios
    WHERE email = "' . $email . '" and password ="' . (sha1(md5(sha1($email . $pass)))) . '";';
    //echo $sql;
    $result = $this->ejecutar($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $usuario->id = $row['id'];
        $usuario->imagen = $row['imagen'];
        $usuario->nombre = $row['nombre'];
        $usuario->apellidoPaterno = $row['apellido_paterno'];
        $usuario->apellidoMaterno = $row['apellido_materno'];
        $usuario->email = $row['email'];
        $usuario->constrasena = $row['password'];
        $usuario->telefono = $row['telefono'];
        $usuario->calle = $row['calle'];
        $usuario->ciudad = $row['ciudad'];
        $usuario->pais = $row['pais'];
        $usuario->direccion = $row['direccion'];
        $usuario->tipoUsuario = $row['tipo'];
        $usuario->permisos = $row['permiso'];
        $usuario->clienteId = $row['cliente'];
        $usuario->usuario = $row['usuario'];
        $usuario->fa = $row['2fa'];
      }
      return $usuario;
    } else {
      return $usuario;
    }

    //print_r(json_encode($arregloProductos));
    return $usuario;
  }

  public function mataToken($token)
  {
    $sql = 'DELETE FROM recuperacion WHERE token = ' . $token . ';';
    $this->ejecutar($sql);
  }
  public function dameUsuarioId($id)
  {
    $usuario = new Usuario();
    $sql = 'SELECT `id`, `nombre`, `appat`, `apmat`, `email`, `fecha_nac`, `password`, `usuario`, `tipo`, `dependencia`, `area`, `cliente`, `esclavo`, `esclavo_padre`, `razon_social`, `empresa_asignada`, `2fa`, `codigo_ver`, `imagen`  FROM `usuarios` WHERE id = ' . $id . ';';
    $result = $this->ejecutar($sql);
   
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {

        $usuario->id = $row['id'];
        $usuario->nombre = $row['nombre'];
        $usuario->apellidoPaterno = $row['appat'];
        $usuario->apellidoMaterno = $row['apmat'];
        $usuario->email = $row['email'];
        $usuario->constrasena = $row['password'];
        $usuario->telefono = $row['telefono'];
        $usuario->calle = $row['calle'];
        $usuario->ciudad = $row['ciudad'];
        $usuario->pais = $row['pais'];
        $usuario->direccion = $row['direccion'];
        $usuario->tipoUsuario = $row['tipo'];
        $usuario->permisos = explode(",", $row['permiso']);
        $usuario->usuario = $row['usuario'];
        $usuario->fa = $row['2fa'];
        $usuario->accion = '';
        $usuario->imagen = $row['imagen'];
      }
      return $usuario;
    } else {
      return $usuario;
    }

    //print_r(json_encode($arregloProductos));
    return $usuario;
  }
  public function eliminaUsuario($id)
  {
    $sql = 'DELETE FROM usuarios WHERE id = ' . $id . ';';
    $this->ejecutar($sql);
  }
  function buscaUsuario($buscar)
  {
    $arregloUsuarios = array();
    $sql = 'SELECT id, nombre, apellido_paterno, apellido_materno, email, contrasena, telefono, calle, ciudad, pais, direccion, tipo_usuario, imagen
    FROM usuario WHERE nombre like "%' . $buscar . '%" or apellido_materno like "%' . $buscar . '%" or apellido_paterno like "%' . $buscar . '%" or telefono like "%' . $buscar . '%" or email like "%' . $buscar . '%";';
    $result = $this->ejecutar($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $usuario = new Usuario();
        $usuario->id = $row['id'];
        $usuario->nombre = $row['nombre'];
        $usuario->imagen = $row['imagen'];
        $usuario->apellidoPaterno = $row['apellido_paterno'];
        $usuario->apellidoMaterno = $row['apellido_materno'];
        $usuario->email = $row['email'];
        $usuario->constrasena = $row['contrasena'];
        $usuario->telefono = $row['telefono'];
        $usuario->calle = $row['calle'];
        $usuario->ciudad = $row['ciudad'];
        $usuario->pais = $row['pais'];
        $usuario->direccion = $row['direccion'];
        $usuario->tipoUsuario = $row['tipo_usuario'];
        $arregloUsuarios[] = $usuario;
      }
      return $arregloUsuarios;
    } else {
      return $arregloUsuarios;
    }

    //print_r(json_encode($arregloProductos));
    return $arregloUsuarios;
  }
  function dameUsuarios()
  {
    $arregloUsuarios = array();
    $sql = 'SELECT *
    FROM usuarios order by id asc;';
    $result = $this->ejecutar($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $usuario = new Usuario();
        $usuario->id = $row['id'];
        $usuario->nombre = $row['nombre'];
        $usuario->imagen = $row['imagen'];
        $usuario->apellidoPaterno = $row['appat'];
        $usuario->apellidoMaterno = $row['apmat'];
        $usuario->email = $row['email'];
        $usuario->constrasena = $row['contrasena'];
        $usuario->telefono = $row['telefono'];
        $usuario->calle = $row['calle'];
        $usuario->ciudad = $row['ciudad'];
        $usuario->pais = $row['pais'];
        $usuario->direccion = $row['direccion'];
        $usuario->tipoUsuario = $row['tipo'];
        $usuario->permisos = explode(",", $row['permiso']);
        $usuario->accion = '';
        $arregloUsuarios[] = $usuario;
      }
      return $arregloUsuarios;
    } else {
      return $arregloUsuarios;
    }

    //print_r(json_encode($arregloProductos));
    return $arregloUsuarios;
  }

  public function cuentaUsuarios()
  {
    $sql = 'SELECT COUNT(*) as total FROM usuarios;';
    $result = $this->ejecutar($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        return $row['total'];
      }
    } else {
      return 0;
    }
  }

  function actualizarUsuario($id, $nombre, $apellidoPaterno, $apellidoMaterno, $email, $telefono, $calle, $ciudad, $pais, $direccion, $permisos)
  {

    $sql = 'UPDATE usuario SET nombre = "' . $nombre . '", apellido_paterno = "' . $apellidoPaterno . '", apellido_materno = "' . $apellidoMaterno . '", email = "' . $email . '", permiso = "' . $permisos . '" WHERE id = ' . $id . ';';
    $this->ejecutar($sql);
  }

  function actualizarContrasena($id, $contrasena)
  {
    $sql = 'UPDATE usuario SET contrasena = "' . sha1($contrasena) . '"  WHERE id = ' . $id . ';';
    $this->ejecutar($sql);
  }

  function actualizarDireccion($id, $email, $calle, $ciudad, $pais, $direccion)
  {
    $sql = 'UPDATE usuario SET calle = "' . $calle . '",  email = "' . $email . '" ,  ciudad = "' . $ciudad . '", pais = "' . $pais . '", direccion = "' . $direccion . '" WHERE id = ' . $id . ';';
    $this->ejecutar($sql);
  }

  function asignarImagen($id, $imagen)
  {
    $sql = 'UPDATE usuario SET imagen = "' . $imagen . '" WHERE id = ' . $id . ';';
    $this->ejecutar($sql);
  }
  function acenderUsuario($id)
  {
    $sql = 'UPDATE usuario SET tipo_usuario = 100 WHERE id = ' . $id . ';';
    $this->ejecutar($sql);
  }

  function desenderUsuario($id)
  {
    $sql = 'UPDATE usuario SET tipo_usuario = 0 WHERE id = ' . $id . ';';
    $this->ejecutar($sql);
  }

  public function modificaProducto()
  {
  }
}
