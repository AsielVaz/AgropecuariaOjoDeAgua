<?php 
session_start();
include_once "adminEvidencias.php";
include_once "adminFacturas.php";

include "notificador.php";


$accion = $_POST["accion"];


$casoAgregar = "agregar";
$casoEliminar = "eliminar";
$casoReenviar = "reenviar";

function procesarImagen() {
    // Ruta donde se guardará la imagen
    
    $rutaBaseServidor = $_SERVER['DOCUMENT_ROOT'];
    $ruta_destino = $rutaBaseServidor .  '/admin/evidencias/';

    // Comprobar si se ha enviado algún archivo
    if ($_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        // Obtener información sobre el archivo
        $nombre_archivo = $_FILES['archivo']['name'];
        $tipo_archivo = $_FILES['archivo']['type'];
        $tamano_archivo = $_FILES['archivo']['size'];
        $tmp_archivo = $_FILES['archivo']['tmp_name'];

        // Verificar si el archivo es una imagen JPG, JPEG o PNG
        $permitidos = array('image/jpeg', 'image/jpg', 'image/png');
        if (!in_array($tipo_archivo, $permitidos)) {
            return "El archivo debe ser una imagen JPG, JPEG o PNG.";
        }
        // Mover el archivo a la ruta de destino
        $nombre_archivo = time() . $nombre_archivo;
        $ruta_final = $ruta_destino . $nombre_archivo;
        if (move_uploaded_file($tmp_archivo, $ruta_final)) {
            return $ruta_final;
        } else {
            return "2";
        }
    } else {
        return "3";
    }

}



function encontrarUsuario($cadena)
{
    // Inicializamos el arreglo usuario
    $usuario = array();

    // Buscamos el índice del símbolo "<"
    $indiceInicioNombre = strpos($cadena, '<');

    // Buscamos el índice del símbolo ">"
    $indiceFinCorreo = strpos($cadena, '>');

    // Verificamos si se encontraron los símbolos "<" y ">"
    if ($indiceInicioNombre !== false && $indiceFinCorreo !== false) {
        // Extraemos el nombre y el correo electrónico usando los índices encontrados
        $nombre = trim(substr($cadena, 0, $indiceInicioNombre));
        $correo = substr($cadena, $indiceInicioNombre + 1, $indiceFinCorreo - $indiceInicioNombre - 1);

        // Almacenamos el nombre y el correo electrónico en el arreglo usuario
        $usuario['nombre'] = $nombre;
        $usuario['correo'] = $correo;
    } else {
        // Si no se encontraron los símbolos "<" y ">", indicamos que el formato no es válido
        $usuario['error'] = 'Formato de cadena no válido';
    }

    // Retornamos el arreglo usuario
    return $usuario;
}

function acondicionarLink($link)
{
    $link = str_replace("/home/iohanes/agropecuariaojodeagua.com", "", $link);
    $link = str_replace(" ", "%20", $link);
    return $link;
}


function agregarEvidencia(){


    $adminFacturas = new AdministradorFacturas();
    $factura = $adminFacturas->dameFactura($_POST["id"]);
    $usuario = encontrarUsuario($factura->correo_ingreso);
    $id_factura = $_POST["id"];
    $url = procesarImagen();
    $usuario_ingresa = $_SESSION["usuario"];
    $adminEvidencias = new AdministradorEvidencias();

    if($url=="2"){
        $mensaje = array();
        $mensaje["estatus"] =  "error";
        $mensaje["mensaje"] = "El archivo debe ser una imagen JPG, JPEG o PNG.";
        echo json_encode($mensaje);
        
    }
    else if($url=="3"){
        $mensaje = array();
        $mensaje["estatus"] =  "error";
        $mensaje["mensaje"] = "No se ha enviado ningún archivo.";
        echo json_encode($mensaje);
        
    }
    else{
      $resultado = $adminEvidencias->agregaEvidencia($id_factura, $url, $usuario_ingresa);
      mailerNot($usuario['correo'], $usuario['nombre'], "Notificación pago de factura AOA", generaCorreo($factura->total_con_iva, $url), "", "", "");
      mailerNot('rgonzalez@agropecuariaojodeagua.com', $usuario['nombre'], "Notificación pago de factura AOA", generaCorreo($factura->total_con_iva, $url), "", "", "");

      //echo "Se envio imagen " . acondicionarLink($url);
      $mensaje = array();
      $mensaje["resultado"] = $resultado;
      $mensaje["estatus"] =  "success";
      $mensaje["mensaje"] = "Evidencia agregada correctamente";
      echo json_encode($mensaje);
    }



   

}
function eliminarEvidencia(){
    $id=$_POST['id'];
    $adminEvidencias = new AdministradorEvidencias();
    $adminEvidencias->eliminaEvidencia($id);
    $mensaje=array ();
    $mensaje['status']='success';
    $mensaje['mensaje']='El registro se ha eliminado con exito';
    $mensaje['submensaje']='exito';
    echo json_encode($mensaje);

}


function reenviarPago(){
    $adminFacturas = new AdministradorFacturas();
    $adminEvidencias = new AdministradorEvidencias();
    $evidencia = $adminEvidencias->dameEvidenciasFac($_POST["id"]);
    $factura = $adminFacturas->dameFactura($_POST["id"]);
    $usuario = encontrarUsuario($factura->correo_ingreso);
    
    //echo var_dump($factura);

    mailerNot($usuario['correo'], $usuario['nombre'], "Notificación pago de factura AOA", generaCorreo($factura->total_con_iva, $evidencia[0]->url), "", "", "");
    $mensaje = array();
    $mensaje["estatus"] =  "success";
    $mensaje["mensaje"] = "Correo reenviado correctamente";
    echo json_encode($mensaje);

}



switch($accion){
    case $casoAgregar:
        agregarEvidencia();
        break;
    case $casoEliminar:
        eliminarEvidencia();
        break;
        case $casoReenviar:
            reenviarPago();
            break;
    default:
        break;
}






function generaCorreo($total, $imagen)
{

    $correo = '
    
    <!DOCTYPE html PUBLIC>
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta content="width=mobile-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no" name="viewport">
  <meta content="#F9FAFB" name="sr bgcolor">  
	<title>Dorota - Newsletter Template</title>
	<style type="text/css">
    html, body{width:100%; margin:0;}
    .ExternalClass{width:100%;}
    .ReadMsgBody{width:100%;}
    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
  	table, td{ border-collapse: collapse; margin: 0; mso-table-lspace: 0; mso-table-rspace: 0; padding: 0;}
    a{color:#00A1DF;text-decoration:none;}
    a:hover{text-decoration:underline; opacity:0.7; transition:all .25s ease-in-out}
      
      @media only screen and (max-width: 800px){
        table[class=container800]{width:100% !important;}
        table[class=container800_img]{width:50% !important;}
        table[class=half-container800]{width:50% !important;}
        table[class=preheader]{width:90% !important;}
        td[class=hide800]{ display:none!important}
      }

      @media only screen and (max-width: 640px){
        table[class=full], table[class=container800]{width:100%!important;}
        table[class=container800], table[class=container600], table[class=container590], table[class=container500]{width:370px !important;}
        table[class=container186], table[class=container290], table[class=container200]{width:320px !important;}
        table[class=container800_img], table[class=half-container800]{width:100% !important;}
        table[class=half-container600], table[class=container120], td[class=container120]{width:49% !important;}
        table[class=about180]{width:250px !important;}
      	td[class=center]{text-align:center!important;vertical-align:middle!important}
        img[class=img_scale], td[class=img_scale]{width:100%!important;height:auto!important}
        td[class=hide], br[class=hide]{ display:none!important}
        td[class=show]{display:block!important}
        td[class=padding_10]{padding: 0 10px!important}
        td[class=padding_20]{padding: 0 20px!important}
        td[class=padding_20_show]{padding: 20!important}
        img[class=divider]{width:100%!important;height:1px!important}
        td[class=mobile-width]{padding:0 25% !important}
        td[class=main-section-header], div[class=main-section-header]{font-size:30px!important; line-height: 38px!important}
        td[class=mobile_para]{font-size:14px!important; line-height: 26px!important}
      } 

      @media only screen and (max-width:480px){
        table[class=full], table[class=container800]{width:100%!important;}
        table[class=container800], table[class=container600], table[class=container590], table[class=container500]{width:250px !important;}
        table[class=container186], table[class=container290], table[class=container200]{width:200px !important;}
        table[class=container800_img], table[class=half-container800], table[class=container120], td[class=container120]{width:100% !important;}
        table[class=half-container600], table[class=container120], td[class=container120]{width:49% !important;}
        table[class=about180]{width:140px !important;}
        td[class=center], td[class=align-center]{text-align:center!important;vertical-align:middle!important}
        img[class=img_scale], td[class=img_scale]{width:100%!important;height:auto!important}
        td[class=hide], br[class=hide]{ display:none!important}
        td[class=show]{display:block!important}
        td[class=padding_20]{padding: 0!important}
        td[class=padding_20_show]{padding: 20!important}
        img[class=divider]{width:100%!important;height:1px!important}
        td[class=main-section-header], div[class=main-section-header]{font-size:26px!important;line-height: 30px!important}
        td[class=mobile_para]{font-size:14px!important; line-height: 26px!important}
      }


      /**
       * @tab Body
       * @section background color
       */
      body .body {
        /*@editable*/
        background-color: #F9FAFB !important;
      }
      /**
       * @tab Body
       * @section font
       */
      body .body {
        /*@editable*/
        font-family: "Montserrat", "Open Sans", "Arial", "Calibri", sans-serif !important;
      }

      /**
       * @tab Container
       * @section background color
       * @section white
       */
      .whiteBg {
        /*@editable*/
        background-color: #FFFFFF !important;
      }

      /**
       * @tab Container
       * @section background color
       * @section whitesmoke
       */
      .greyBg {
        /*@editable*/
        background-color: #F5F6F8 !important;
      }

      /**
       * @tab Button
       * @section background color
       * @section color
       */
      .button {
        /*@editable*/
        background-color: #00A1DF !important;
      }

      /**
       * @tab Button
       * @section border color
       * @section color
       */
      .buttonBorder {
        /*@editable*/
        border-color: #00A1DF !important;
      }

      /**
       * @tab Table
       * @section background color
       * @section color
       */
      .table {
        /*@editable*/
        background-color: #00A1DF !important;
      }

  </style>
  </head>
  <body yahoo="fix" style="margin:0; padding:0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
    <!-- Main Header --> 
    <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#F9FAFB" mc:variant="" mc:repeatable="" class="body">
      <tbody>
          <tr>
              <td align="center" class="padding_20">
                <table border="0" align="center" width="100%" style="width:100%; max-width:800px; background-image: url(images/bg1.png); background-repeat: no-repeat; background-position: top center; background-size: cover;" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" background="images/bg1.png" class="background1">
                    <tbody>

                      <!-- Header Nav -->
                     
                      <!-- end section -->

                      <!-- Main Hero -->
                      <tr>
                        <td align="center">
                          <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container500" data-bgcolor="Hero Center">
                              <tbody>
                                  <tr>
                                      <td height="50" style="font-size: 50px; line-height: 50px;">&nbsp;</td>
                                  </tr>
                                  
                                  <tr><td height="20" class="hide" style="font-size: 20px; line-height: 20px;">&nbsp;</td></tr>

                                  <tr>
		                            <td align="center" mc:edit="mc1" style="font-size: 14px; color: #BFBFBF; font-weight: 500; line-height: 18px; font-family: "Montserrat", "Calibri", sans-serif; text-transform: uppercase;" data-color="Colored Text" data-size="Colored Text" data-min="12" data-max="28">
		                                <!-- ======= section text ====== -->
		                                
		                                <div class="editable_text" style="line-height: 24px">
		                                    <span class="text_container">
		                                    <multiline>
                                        <h1>Sistema de alertas automatizado de recepción de facturas</h1> 
		                                    </multiline>
		                                    </span>
		                                </div>
		                            </td>   
		                          </tr>

		                          <tr>
                                      <td height="5" style="font-size:5px; line-height: 5px;">&nbsp;</td>
                                  </tr>

                                  <tr>
	                                    <td align="center" mc:edit="mc2" style="color: #FFFFFF; font-size: 40px; font-family: "Montserrat", "Calibri", sans-serif; line-height: 40px; font-weight:500;" class="main-section-header" data-size="Header title" data-max="72" data-color="Header title">
	                                        <!-- ======= section text ====== -->
	                                        
	                                        <div class="main-section-header" style="line-height: 40px">
	                                            <span class="text_container">
	                                            <multiline>
	                                                Su factura ha sido pagada
	                                            </multiline>
	                                            </span>
	                                        </div>
	                                    </td>   
                                  </tr>

                                   <tr>
                                      <td height="20" style="font-size:20px; line-height: 20px;">&nbsp;</td>
                                  </tr>

                                  <tr>
		                            <td align="center" mc:edit="mc3" style="font-family: "Open Sans", sans-serif; font-size: 14px; font-weight: 500; color: #BFBFBF; line-height: 26px;" data-color="Text" data-size="Text" data-min="12" data-max="28">
		                                <!-- ======= section text ====== -->
		                                <div class="editable_text" style="line-height: 26px">
		                                  <span class="text_container">
		                                    <multiline>
		                                    Motivo: Su factura con monto  $' . number_format($total, 2)  . ' fue pagada
		                                    </multiline>
		                                  </span>
		                                </div>
		                            </td>
		                          </tr>
                                  
                                  <tr><td height="30" style="font-size: 30px; line-height: 30px;">&nbsp;</td></tr>

                                  <!-- button -->
                                  <tr>
                                    <td align="center">
                                     
                                    <img editable="true"  src="https://agropecuariaojodeagua.com/logo.png" alt="img" width="280" height="280" class="img_scale" style="border-radius: 5px;" />

                                    </td>
                                  </tr>
                                  <!-- end section -->

                                  <tr>
                                      <td height="50" style="font-size: 50px; line-height: 50px;">&nbsp;</td>
                                  </tr>

                                  <tr><td height="20" class="hide" style="font-size: 20px; line-height: 20px;">&nbsp;</td></tr>
                              </tbody>
                          </table>
                        </td>
                      </tr>
                      <!-- end section -->

                    </tbody>
                </table>
              </td>
          </tr>
      </tbody>
    </table>
    <!-- end section -->



    <!-- Article-left-->
    <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#F9FAFB" mc:variant="" mc:repeatable="" class="body">
      <tbody>
        <tr>
          <td align="center" class="padding_20">
              <table border="0" align="center" width="100%" bgcolor="#FFFFFF" style="width:100%; max-width:800px;" cellpadding="0" cellspacing="0" class="whiteBg">
                  <tbody>
                  	<tr><td height="50" style="font-size: 50px; line-height: 50px;">&nbsp;</td></tr>
                    <tr>
                      <td align="center">
                          <table class="container600" width="600" align="center" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                              <tr>
                                <td>
                                    <table class="container590" align="right" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tr>
                                            <td align="center" mc:edit="mcimg17">
                                                <img editable="true"  src="https://agropecuariaojodeagua.com/'. acondicionarLink($imagen) .'" alt="img" width="280" height="280" class="img_scale" style="border-radius: 5px;" />
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- SPACE -->

                                    <table class="full" width="1" align="right" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tr>
                                            <td width="1" height="30" style="font-size: 30px; line-height: 30px;">
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- END SPACE -->

                                    <table class="container590" width="280" align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"> 
                                        <tr>
                                          <td align="left" mc:edit="mc25" style="font-family: "Montserrat", sans-serif; font-size: 24px; font-weight: 300; color: #FFFFFF; line-height: 34px;" data-color="Headline" data-size="Headline" data-min="12" data-max="28">
                                              <!-- ======= section text ====== -->
                                              <div class="editable_text" style="line-height: 34px">
                                                  <span class="text_container">
                                                  <multiline>
                                                  Que hacer? 
                                                  </multiline>
                                                  </span>
                                              </div>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td align="left" mc:edit="mc26" style="font-size: 12px; color: #00A1DF; font-weight: 500; line-height: 18px; font-family: "Montserrat", "Calibri", sans-serif;" data-color="Colored Text" data-size="Colored Text" data-min="12" data-max="28">
                                              <!-- ======= section text ====== -->
                                              <div class="editable_text" style="line-height: 34px">
                                                  <span class="text_container">
                                                  <multiline>
                                                  by Agro Admin
                                                  </multiline>
                                                  </span>
                                              </div>
                                          </td>
                                        </tr>
                                      
                                        <tr>
                                          <td align="left" mc:edit="mc27" style="font-family: "Open Sans", sans-serif; font-size: 14px; font-weight: 400; color: #8f96a1; line-height: 26px;" data-color="Text" data-size="Text" data-min="12" data-max="28">
                                              <!-- ======= section text ====== -->
                                              <div class="editable_text" style="line-height: 26px">
                                                <span class="text_container">
                                                  <multiline>
                                                   Su factura con el monto  $' . number_format($total, 2)  . ' fue pagada de forma exitosa, para cualquier aclaración o duda favor de contactar a soporte.
                                                  </multiline>
                                                  </span>
                                              </div>
                                          </td>
                                        </tr>
                                        <tr>
                                            <td height="30" style="font-size: 30px; line-height: 30px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td>
                                            <table border="0" align="left" width="160" cellpadding="0" cellspacing="0" data-bgcolor="Blue-Button" class="button" style="background-color:#00A1DF;border-radius: 5px;">
                                             
                                            </table>                              
                                          </td>
                                        </tr>
                                    </table>
                                </td>
                              </tr>
                          </table>
                      </td>
                  	</tr>
                  	<tr><td height="50" style="font-size: 50px; line-height: 50px;">&nbsp;</td></tr>
                  </tbody>
              </table>
          </td>
        </tr>
      </tbody>
    </table>
    <!-- end section -->



    <!-- FAQ -->
    <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#F9FAFB" mc:variant="" mc:repeatable="" class="body">
        <tbody>
          <tr>
            <td align="center" class="padding_20">
                <table border="0" align="center" width="100%" bgcolor="#FFFFFF" style="width:100%; max-width:800px;" cellpadding="0" cellspacing="0" class="whiteBg">
                    <tbody>
                    <tr>
                        <td align="center">
                            <table class="container600" width="600" align="center" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td height="50" style="font-size: 50px; line-height: 50px;">
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                  <td>
                                      <table class="container590" width="250" align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"> 
                                          <tbody>
                                          <tr>
                                            <td align="left" mc:edit="mc109" style="font-family: "Montserrat", sans-serif; font-size: 15px; font-weight: 500; color: #FFFFFF; line-height: 34px;" class="center" data-color="Headline" data-size="Headline" data-min="12" data-max="28">
                                                <!-- ======= section text ====== -->
                                                <div class="editable_text" style="line-height: 34px">
                                                    <span class="text_container">
                                                    <multiline>
                                                    Dirección 
                                                    </multiline>
                                                    </span>
                                                </div>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="left" mc:edit="mc110" style="font-family: "Open Sans", sans-serif; font-size: 14px; font-weight: 400; color: #8f96a1; line-height: 26px;" class="center" data-color="Text" data-size="Text" data-min="12" data-max="28">
                                                <!-- ======= section text ====== -->
                                                <div class="editable_text" style="line-height: 26px">
                                                  <span class="text_container">
                                                    <multiline>
                                                      Carretera libre Sayula-GDL km6 1A
                                                    </multiline>
                                                    </span>
                                                </div>
                                            </td>
                                          </tr>
                                          
                                          <tr>
                                            <td align="left" mc:edit="mc111" style="font-family: "Montserrat", sans-serif; font-size: 15px; font-weight: 500; color: #FFFFFF; line-height: 34px;" class="center" data-color="Headline" data-size="Headline" data-min="12" data-max="28">
                                                <!-- ======= section text ====== -->
                                                <div class="editable_text" style="line-height: 34px">
                                                    <span class="text_container">
                                                    <multiline>
                                                    Teléfono :
                                                    </multiline>
                                                    </span>
                                                </div>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="left" mc:edit="mc112" style="font-family: "Open Sans", sans-serif; font-size: 14px; font-weight: 400; color: #8f96a1; line-height: 26px;" class="center" data-color="Text" data-size="Text" data-min="12" data-max="28">
                                                <!-- ======= section text ====== -->
                                                <div class="editable_text" style="line-height: 26px">
                                                  <span class="text_container">
                                                    <multiline>
                                                    +523421011449
                                                    <br>
                                                      contacto@agropecuariaojodeagua.com
                                                  </multiline>
                                                    </span>
                                                </div>
                                            </td>
                                          </tr>
                                          
                                      </tbody></table>
                                      
                                      <!-- SPACE -->
                                      <table class="full" width="1" align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                          <tbody><tr>
                                              <td width="1" height="30" style="font-size: 30px; line-height: 30px;">
                                              </td>
                                          </tr>
                                      </tbody></table>
                                      <!-- END SPACE -->

                                      <table width="230" align="right" border="0" cellpadding="0" cellspacing="0" class="container590"> 
                                        <tbody>
                                        <tr>
                                          <td align="left" mc:edit="mc113" style="font-family: "Montserrat", sans-serif; font-size: 15px; font-weight: 500; color: #FFFFFF; line-height: 34px;" class="center" data-color="Headline" data-size="Headline" data-min="12" data-max="28">
                                              <!-- ======= section text ====== -->
                                              <div class="editable_text" style="line-height: 34px">
                                                  <span class="text_container">
                                                  <multiline>
                                                  Sobre nosotros 
                                                  </multiline>
                                                  </span>
                                              </div>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td align="left" mc:edit="mc115" style="font-family: "Open Sans", sans-serif; font-size: 14px; font-weight: 400; color: #8f96a1; line-height: 26px;" data-color="Text" data-size="Text" data-min="12" data-max="28" class="center">
                                              <!-- ======= section text ====== -->
                                              <div class="editable_text" style="line-height: 26px">
                                                <span class="text_container">
                                                  <multiline>
                                                  Agropecuaria Ojo de Agua es una empresa dedicada a la producción y comercialización de productos agrícolas.
                                                  </multiline>
                                                  </span>
                                              </div>
                                          </td>
                                        </tr>
                                      </tbody></table>                                      
                                  </td>
                                </tr>

                                <tr>
                                  <td height="50" style="font-size: 50px; line-height: 50px;">&nbsp;</td>
                                </tr>

                              
                                
                            </tbody></table>
                        </td>
                    </tr>
                    <tr>
                        <td height="50" style="font-size: 50px; line-height: 50px;">
                            &nbsp;
                        </td>
                    </tr>
                    
                  </tbody>
                </table>
            </td>
          </tr>
        </tbody>
    </table>
    <!-- end section -->

    <!-- Footer -->
    <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#F9FAFB" mc:variant="" mc:repeatable="" class="body">
      <tbody>
        <tr>
            <td align="center" class="padding_20">
                <table border="0" align="center" width="100%" bgcolor="#FFFFFF" style="width:100%; max-width:800px; background-image: url(images/bg2.png); background-size: cover; background-repeat: no-repeat; background-position: top center; background-color: #FFFFFF" background="images/bg2.png" cellpadding="0" cellspacing="0" class="">
                  <tbody>
                    <tr>
                      <td align="center">
                        <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
                          <tbody>
                            <tr><td height="30" style="font-size: 30px; line-height: 30px;">&nbsp;</td></tr>

                            <tr>
                              <td align="center" mc:edit="unsubscribe-link" style="color: #FFFFFF; font-size: 14px; font-family: "Open Sans", sans-serif; line-height: 24px; font-weight: 500;" data-color="White Text" data-size="White Text" data-min="12" data-max="28">
                                <div class="editable_text" style="line-height: 24px;">
                                  <span class="text_container">
                                    <multiline>
                                      Copyrigth © Agropecuaria Ojo de Agua.<br> 
                                      EDWORLD - Skull <br>
                                      <a href="*|UNSUB|*" target="_blank" style="text-decoration: none; color: #00A1DF;" >Ver</a>
                                    </multiline>
                                  </span>
                                </div>  
                              </td>
                            </tr>

                            <tr><td height="30" style="font-size: 30px; line-height: 30px;">&nbsp;</td></tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody>
                </table>
            </td>
        </tr>
      </tbody>
    </table>
    <!-- end section -->



    <!-- Shadow/Divider --> 
    <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#F9FAFB" mc:variant="" mc:repeatable="" class="body"> 
      <tbody>
          <tr>
              <td align="center" class="padding_20">
                  <table border="0" align="center" width="100%" style="width:100%; max-width:800px; " cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="whiteBg">
                      <tbody>
                        <tr><td height="15" style="font-size: 15px; line-height: 15px;" mc:edit="mcimg36">
                         
                        </td></tr>
                      </tbody>
                  </table>
              </td>
          </tr>
      </tbody>
    </table>
    <!-- end section -->
</body>
</html>

    
    ';
    return $correo;
}
