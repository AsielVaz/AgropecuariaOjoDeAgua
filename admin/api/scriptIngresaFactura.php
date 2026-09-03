<?php
include_once "conectorBD.php";
include_once "adminProveedores.php";
include_once "adminFacturas.php";
include_once "notificador.php";

$conector = new conector();

$xml = $conector->ejecutar("SELECT * FROM xml_ingresados WHERE ingresado = '0'");

$adminProveedores = new AdministradorProveedores();
$proveedores = $adminProveedores->dameProveedores();






function determinarMes($fecha, $dias)
{
  // Convertir la fecha dada en un objeto DateTime
  $fecha_obj = new DateTime($fecha);

  // Clonar el objeto de fecha para no modificar la original
  $fecha_modificada = clone $fecha_obj;

  // Agregar la cantidad de días especificada
  $fecha_modificada->modify("+$dias days");

  // Obtener el mes de la fecha original
  $mes_original = $fecha_obj->format('m');

  // Obtener el mes de la fecha modificada
  $mes_modificado = $fecha_modificada->format('m');

  // Determinar si el mes modificado es igual al mes original
  if ($mes_modificado == $mes_original) {
    return true;
  } else {
    return false;
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


function procesarXMLyImprimirComoJS($ruta_archivo, $id_xml, $correo_remitente)
{
  global $proveedores;
  $concetorF = new conector();
  // Comprobar si el archivo existe
  if (!file_exists($ruta_archivo)) {
    echo "El archivo no existe.";
    return;
  }

  // Cargar el archivo XML
  $xml = simplexml_load_file($ruta_archivo);
  echo "Se esta procesando " . $ruta_archivo . "<br>";
  // Verificar si se cargó correctamente
  if (!$xml) {
    echo "Error al cargar el archivo XML.";


    $usuario = encontrarUsuario($correo_remitente);
    $correo = $usuario['correo'];
    $nombre = $usuario['nombre'];
    $concetorF->ejecutar("INSERT INTO `log_facturas`(`correo`, `fecha`, `mensaje`) VALUES ('$correo_remitente','$fecha','Proveedor no encontrado: $rfc se envio a $correo nombre $nombre')");

    echo "XML irreconocible se envio a $correo nombre $nombre <br>";
    $concetorF->ejecutar("UPDATE `xml_ingresados` SET `ingresado` = '1' WHERE `xml_ingresados`.`id` = $id_xml");
    $contenido = generaCorreo('NA', $nombre, "XML irreconocible", 0, 0);
    //mailerNot($usuario['correo'], $usuario['nombre'], " Error Factura", $contenido, "", "", "");


    return;
  }

  // Convertir el XML a formato JSON
  $json = json_encode($xml);

  //echo $json;
  echo "<tr>";
  echo "<td>" . $xml->children('cfdi', true)->Emisor->attributes()->Rfc . "</td>";
  echo "<td>" .  $xml->children('cfdi', true)->Receptor->attributes()->Rfc . "</td>";
  echo "<td>" .  $xml->children('cfdi', true)->Emisor->attributes()->Nombre . "</td>";
  echo "<td>" .  $xml->children('cfdi', true)->Receptor->attributes()->Nombre . "</td>";
  echo "<td>" .  $xml->attributes()->Folio . "</td>";
  echo "<td>" . $moneda =  $xml->attributes()->Moneda . "</td>";
  echo "<td>" .  $metodoPago = $xml->attributes()->MetodoPago . "</td>";
  echo "<td>" . $usoCfdi =  $xml->children('cfdi', true)->Receptor->attributes()->UsoCFDI . "</td>";
  echo "<td>" . $folioFiscal =  $xml->children('cfdi', true)->Complemento->children('tfd', true)->attributes()->UUID . "</td>";
  echo "<td>" . $fechaTimbrado =  $xml->attributes()->Fecha  . "</td>";
  echo "<td>" . $subTotal =  $xml->attributes()->SubTotal  . "</td>";
  echo "<td>" . $total =  $xml->attributes()->Total  . "</td>";


  echo "</tr>";
  $hoy = date("Y-m-d H:i:s");
  //SELECT `id`, `id_provedor`, `folio_fiscal`, `serie`, `folio`, `fecha_de_ingreso`, `metodo_de_pago`, `total_con_iva`, `total_sin_iva`, `estatus_factura`, `fecha_timbrado`, `uso_cfdi`, `uuid` FROM `factura` WHERE 1
  //$concetorF->ejecutar("INSERT INTO `factura` (`id_provedor`, `folio_fiscal`, `serie`, `folio`, `fecha_de_ingreso`, `metodo_de_pago`, `total_con_iva`, `total_sin_iva`, `estatus_factura`, `fecha_timbrado`, `uso_cfdi`, `uuid`) VALUES ('1', '$xml->attributes()->Folio', 'serie', 'folio', '$xml->attributes()->Fecha', '$xml->attributes()->MetodoPago', '$xml->attributes()->Total', '$xml->attributes()->SubTotal', '1', '2021-06-01', '$xml->children('cfdi', true)->Receptor ->attributes()->UsoCFDI', '$xml->children('cfdi', true)->Complemento->children('tfd', true) ->attributes()->UUID')");
  $rfc = $xml->children('cfdi', true)->Emisor->attributes()->Rfc;
  $usoCfdi =  $xml->children('cfdi', true)->Receptor->attributes()->UsoCFDI;
  $folioFiscal =  $xml->children('cfdi', true)->Complemento->children('tfd', true)->attributes()->UUID;
  $fechaTimbrado =  $xml->attributes()->Fecha;
  $subTotal =  $xml->attributes()->SubTotal;
  $total =  $xml->attributes()->Total;
  $metodoPago = $xml->attributes()->MetodoPago;
  $encontrado = false;
  foreach ($proveedores as $proveedor) {
    //echo "Analizando proveedor: $proveedor->rfc  contra $rfc<br>";
    $fecha = date("Y-m-d H:i:s");
    $dias = $proveedor->periodo_pago;
    $fechaVencimiento = date("Y-m-d H:i:s", strtotime($fecha . "+ $dias days"));
    if (strtolower($proveedor->rfc) == strtolower($rfc)) { 
      if ($metodoPago != "PUE") {
        $id_proveedor = $proveedor->id;
        $concetorF->ejecutar("INSERT INTO `factura` (`id_provedor`, `folio_fiscal`, `serie`, `folio`, `fecha_de_ingreso`, `metodo_de_pago`, `total_con_iva`, `total_sin_iva`, `estatus_factura`, `fecha_timbrado`, `uso_cfdi`, `uuid`,`id_ingreso_xml`,`fecha_vencimiento` ,`moneda`) VALUES ('$id_proveedor', '$folioFiscal', 'serie', 'folio', '$hoy', '$metodoPago', '$total', '$subTotal', '1', '$fechaTimbrado', '$usoCfdi', '$folioFiscal', '$id_xml', '$fechaVencimiento', '$moneda')");
        $concetorF->ejecutar("UPDATE `xml_ingresados` SET `ingresado` = '1' WHERE `xml_ingresados`.`id` = $id_xml");
        $concetorF->ejecutar("INSERT INTO `log_facturas`(`correo`, `fecha`, `mensaje`) VALUES ('$correo_remitente','$fecha','SE INGRESO DE FORMA CORRECTA LA FACTURA')");

        $encontrado = true;
        break;
      }
      else{
        $hoy = date("Y-m-d H:i:s");
        if(determinarMes($hoy, $dias)){
          $id_proveedor = $proveedor->id;
          $concetorF->ejecutar("INSERT INTO `factura` (`id_provedor`, `folio_fiscal`, `serie`, `folio`, `fecha_de_ingreso`, `metodo_de_pago`, `total_con_iva`, `total_sin_iva`, `estatus_factura`, `fecha_timbrado`, `uso_cfdi`, `uuid`,`id_ingreso_xml`,`fecha_vencimiento`, `moneda` ) VALUES ('$id_proveedor', '$folioFiscal', 'serie', 'folio', '$hoy', '$metodoPago', '$total', '$subTotal', '1', '$fechaTimbrado', '$usoCfdi', '$folioFiscal', '$id_xml', '$fechaVencimiento', '$moneda')");
          $concetorF->ejecutar("UPDATE `xml_ingresados` SET `ingresado` = '1' WHERE `xml_ingresados`.`id` = $id_xml");
          $concetorF->ejecutar("INSERT INTO `log_facturas`(`correo`, `fecha`, `mensaje`) VALUES ('$correo_remitente','$fecha','SE INGRESO DE FORMA CORRECTA LA FACTURA')");
         
         
          $encontrado = true;
          break;
        }
        else{
          $usuario = encontrarUsuario($correo_remitente);
          $correo = $usuario['correo'];
          $nombre = $usuario['nombre'];
          $concetorF->ejecutar("INSERT INTO `log_facturas`(`correo`, `fecha`, `mensaje`) VALUES ('$correo_remitente','$fecha','Fecha posterior: $rfc se envio a $correo nombre $nombre')");

          echo "Fecha de pago posterior al plazo del cliente: $rfc se envio a $correo nombre $nombre <br>";
          $concetorF->ejecutar("UPDATE `xml_ingresados` SET `ingresado` = '1' WHERE `xml_ingresados`.`id` = $id_xml");
          $contenido = generaCorreo($rfc, $nombre, "La factura en modalidad PUE que trata de ingresar a nuestro sistema, no alcanzaría a ser pagada a tiempo, le rogamos la sustituya a modo PPD para su ingreso. Que tenga un buen día.", 0, 0);
          mailerNot($usuario['correo'], $usuario['nombre'], " Error Factura", $contenido, "", "", "");
          //echo $contenido;
          break;
        
        }
      }
      //echo "Proveedor encontrado: $proveedor->rfc <br>";

    }
  }

  if (!$encontrado) {
    $usuario = encontrarUsuario($correo_remitente);
    $correo = $usuario['correo'];
    $nombre = $usuario['nombre'];
    echo "Proveedor no encontrado: $rfc se envio a $correo nombre $nombre <br>";
    $concetorF->ejecutar("INSERT INTO `log_facturas`(`correo`, `fecha`, `mensaje`) VALUES ('$correo_remitente','$fecha','Proveedor no encontrado: $rfc se envio a $correo nombre $nombre')");

    $concetorF->ejecutar("UPDATE `xml_ingresados` SET `ingresado` = '1' WHERE `xml_ingresados`.`id` = $id_xml");
    $contenido = generaCorreo($rfc, $nombre, "Estimado cliente, los datos de tu factura no han sido dados de alta en nuestro sistema, por favor contacta al personal directamente para que los datos estén en nuestro sistema. Que tengas un buen día." , 1, 0);
    mailerNot($usuario['correo'], $usuario['nombre'], " Error Factura", $contenido, "", "", "");
    //echo $contenido;
  } else {
    $adminFacturas = new  AdministradorFacturas();
    $ultimoIdFactura = $adminFacturas->dameUltimoId();

    $usuario = encontrarUsuario($correo_remitente);
    $correo = $usuario['correo'];
    $nombre = $usuario['nombre'];
    echo "Proveedor encontrado: $rfc se envio a $correo nombre $nombre <br>";
    $concetorF->ejecutar("INSERT INTO `log_facturas`(`correo`, `fecha`, `mensaje`) VALUES ('$correo_remitente','$fecha','Proveedor encontrado: $rfc se envio a $correo nombre $nombre')");

    $contenido = generaCorreo($rfc, $nombre, "Tu factura a se a admitido a nuestro sistema de manera satisfactoria con el folio A000$ultimoIdFactura  Que tengas un buen dia." ,2 ,1);
    mailerNot($usuario['correo'], $usuario['nombre'], "Factura aceptada", $contenido, "", "", "");
    //echo $contenido;
  }




  // Imprimir el contenido como JavaScript
  echo "<script>";
  echo "var xmlData = $json;";
  echo "console.log(xmlData);"; // Imprimir los datos en la consola del navegador
  echo "</script>";
}



function generaCorreo($rfc, $nombre, $error, $consejo, $imagen)
{

    $consejos = array();
    $titulos = array();
    $titulos[] = "Cambiar tipo de factura";
    $consejos[] = "Cambiar la factura a PPD para que pueda ser ingresada en nuestro sistema";
    $titulos[] = "Contactar al personal";
    $consejos[] = "Contactar al personal para que los datos estén en nuestro sistema";
    $titulos[] = "Su factura fue aceptada";
    $consejos[] = $error;

    $imagenes= array();
    $imagenes[] = "https://agropecuariaojodeagua.com/imagenes/campo_noche.jpg";
    $imagenes[] = "https://agropecuariaojodeagua.com/imagenes/campo.jfif";

    if($consejo == 2){
      $tituloCorreo = "FACTURA ACEPTADA";
      $queHacer = '';
    }
    else{
      $tituloCorreo = "SU FACTURA HA SIDO RECHAZADA";
      $queHacer = '¿Que hacer si mi factura fue rechazada?';
    }

    $correo = 
    '
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
        <title>Quake</title>
    
        <meta  name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0," /> 
    
        <link href=https://fonts.googleapis.com/css?family=Signika:300,400,600,700 rel=stylesheet type=text/css/>
        <link href=https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic rel=stylesheet type=text/css/>
        
        <style type="text/css">	
                
           html {
            width: 100%;
          }
          body {
            margin: 0;
            padding: 0;
            width: 100%;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
          }
          img {
            display: block !important;
            border: 0;
            -ms-interpolation-mode: bicubic;
          }
           .ReadMsgBody {
            width: 100%;
          }
          .ExternalClass {
            width: 100%;
          }			
          .ExternalClass,
          .ExternalClass p,
          .ExternalClass span,
          .ExternalClass font,
          .ExternalClass td,
          .ExternalClass div {
            line-height: 100%;
          }
           .images {
            display: block !important;
            width: 100% !important;
          }
          .heading {
            font-family:Signika,Arial, Helvetica Neue, Helvetica, sans-serif !important;
          }	
          .MsoNormal {
            font-family:Open Sans,Arial, Helvetica Neue, Helvetica, sans-serif !important;
          }
          p {
            margin: 0 !important;
            padding: 0 !important;
          }
          a {
            font-family:Open Sans, Arial, Helvetica Neue, Helvetica, sans-serif !important;
          }
          .button td,
          .button a {
            font-family:Open Sans, Arial, Helvetica Neue, Helvetica, sans-serif !important; 
          }						
          .button a:hover {
            text-decoration: none !important;
          }
    
          /* MEDIA QUIRES */
    
          @media only screen and (max-width:640px) {
            body {
              width:auto !important;
            }
            table[class=display-width] {
              width:100% !important;
            }	
            table[class=nulltable] {
              display:none !important;
            }	
            .null {
              display:none !important;
            }
          }
    
          @media only screen and (max-width:480px) {
            table[class=display-width] table {
              width:100% !important;
            }
            table[class=display-width] .button-width .button {
              width:auto !important;
            }				
          }
    
        </style>
    
      </head>
    
      <body>   
        
      
        
        
        
        <!-- HEADER & BANNER STARTS -->
        
        <table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td align="center">	
              <!--[if gte mso 9]>
              <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="mso-width-percent:1000; height:495px; margin:auto;">
              <v:fill type="tile" src="http://www.pennyblacktemplates.com/demo/quake/images/1280x500.jpg" color="#f6f8f7" />
              <v:textbox inset="0,0,0,0">
              <![endif]-->
              <div style="margin:auto;">
                <table align="center" background="https://agropecuariaojodeagua.com/imagenes/campo1.jpg" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%" style="background-image:url('.$imagenes[$imagen].'); background-position:center; background-repeat:no-repeat;">
                  <tr>
                    <td height="100"></td>
                  </tr>
                  <tr>
                    <td align="center" valign="top" style="padding:0 20px;">
                      <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="600">
                        <tbody>
                          <tr>
                            <td align="center" valign="middle">												
                              <img src="https://agropecuariaojodeagua.com/logo.png" alt="64x64" width="64" style="display:block;margin:0;padding:0; width: 300px;	" />
                            </td>
                          </tr>
                          <tr>
                            <td height="30"></td>
                          </tr>
                          <tr>
                            <td align="center" class="heading" style="color:#ffffff;font-family:Segoe UI,sans-serif,Arial,Helvetica,Lato;font-size:30px;letter-spacing:2px;line-height:40px;">
                             '.$tituloCorreo.' 	
                            </td>
                          </tr>
                          <tr>
                            <td height="30"></td>
                          </tr>
                          <tr>
                            <td align="center">
                              <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="80%">
                                <tr>
                                  <td align="center" class="MsoNormal" style="color:#ffffff; font-family:Segoe UI,sans-serif,Arial,Helvetica,Lato;font-size:16px;letter-spacing:1px;line-height:24px;">
                                    Hola estimado '.$nombre.' <br>
                                    
                                    '.$error.'
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td height="50"></td>
                          </tr>
                         											
                        </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td height="100"></td>
                  </tr>
                </table>
              </div>
              <!--[if gte mso 9]> </v:textbox> </v:rect> <![endif]-->
            </td>
          </tr>
        </table>
        
        <!-- HEADER & BANNER ENDS -->		
      
        
        <!-- TWO COLUMN : MAIN SERVICES STARTS -->
        
        <table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td align="center">
              <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%">					
                <tr>
                  <td height="50"></td>
                </tr>
                <tr>
                  <td align="center" style="padding:0 20px;">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="600">
                      <tr>
                        <td align="center" class="heading" style="color:#444444;font-family:Segoe UI,sans-serif,Arial,Helvetica,Lato;font-size:30px;letter-spacing:1px;line-height:36px;">
                          '.$queHacer.'
                        </td>
                      </tr>
                      <tr>
                        <td height="10"></td>
                      </tr>
                      
                      <tr>
                        <td height="50"></td>
                      </tr>
                      <tr>
                        <td> 										
                          <!-- TABLE LEFT -->									
                          
                          <table align="left" border="0" cellpadding="0" cellspacing="0" class="display-width" width="47%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                            <tbody>
                              <tr>
                                <td>
                                  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" >
                                                      
                                    <tr> 
                                      <td height="40"></td>
                                    </tr>
                                    <tr>
                                      <td valign="top">	
                                        <img src="https://agropecuariaojodeagua.com/imagenes/che.png" alt="64x64x2" width="64" height="64"/>
                                      </td>
                                      <td width="20">&nbsp;</td>
                                      <td>
                                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                                          <tbody>
                                            <tr>
                                              <td align="left" class="heading" style="color:#444444;font-family:Segoe UI,sans-serif,Arial,Helvetica,Lato;font-size:16px;font-weight:bold;letter-spacing:1px;line-height:24px;text-transform:uppercase;">
                                                '.$titulos[$consejo].'
                                              </td>
                                            </tr>														
                                            <tr>
                                              <td height="10"></td>
                                            </tr>
                                            <tr>
                                              <td align="left" class="MsoNormal" style="color:#444444;font-family:Segoe UI,sans-serif,Arial,Helvetica,Lato;font-size:14px;line-height:24px;">
                                                '.$consejos[$consejo].'
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>													
                            </tbody>
                          </table>											
                          
                           <table align="left" border="0" cellpadding="0" cellspacing="0" width="1" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                            <tbody>
                              <tr>
                                <td style="line-height:40px;" height="40" width="1"></td>
                              </tr>
                            </tbody>
                          </table>
                          
                          <!-- TABLE RIGHT -->
                          
                          
                        </td>
                      </tr>																	
                    </table>
                  </td>
                </tr>
                <tr>
                  <td height="50"></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        
        <!-- TWO COLUMN : MAIN SERVICES ENDS -->
        
        
        
        <!-- DOWNLOAD QUACK STARTS -->
        
        
        <!-- FOOTER STARTS -->
        
        <table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td align="center">
              <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%">
                <tr>
                  <td height="50"></td>
                </tr>
                <tr>
                  <td align="center" style="padding:0 20px;">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="600">
                      <tbody>
                        
                        <tr>
                          <td height="30"></td>
                        </tr>
                        <tr>
                          <td>  
                            <!-- TABLE LEFT -->									
                            
                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="display-width" width="35%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;width:auto;" >
                              <tbody>													
                                <tr>
                                  <td align="center" class="MsoNormal" style="color:#444444;font-family:Segoe UI,sans-serif,Arial,Helvetica,Lato;font-size:14px;letter-spacing:1px;line-height:20px;">
                                    SISTEMA AUTOMATIZADO DE RECEPCIÓN DE FACTURAS
                                  </td>
                                </tr>										
                              </tbody>
                            </table>
                            
                             <table align="left" border="0" cellpadding="0" cellspacing="0" width="1" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                              <tbody>
                                <tr>
                                  <td style="line-height:20px;" height="20" width="1"></td>
                                </tr>
                              </tbody>
                            </table>
                                    
                            <!-- TABLE RIGHT -->
                            
                            <table align="right" border="0" cellpadding="0" cellspacing="0" class="display-width" width="27%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;width:auto;">
                              <tbody>																		
                                <tr>
                                  <td align="center" class="MsoNormal" style="color:#444444;font-family:Segoe UI,sans-serif,Arial,Helvetica,Lato;font-size:14px;letter-spacing:1px;line-height:20px;">
                                    <a href="#" style="color:#444444;text-decoration:none;">EDWORLD</a>
                                  </td>
                                </tr> 													
                              </tbody>
                            </table>
                          </td>
                        </tr>	
                        <tr>
                          <td height="50"></td>
                        </tr>
                      </tbody>
                    </table>                    
                  </td>
                </tr>		
              </table>
            </td>
          </tr>			
        </table>
    
        <!-- FOOTER ENDS -->
      </body>
    </html>
    
    ';
   
    return $correo;
}


?>


<table border="2px">
  <tr>
    <th>RFC EMISOR</th>
    <th>RFC RECEPTOR</th>
    <th>Nombre EMISOR</th>
    <th>Nombre RECEPTOR</th>
    <th>FOLIO</th>
    <th>MÉTODO DE PAGO</th>
    <th>USO DE CFDI</th>
    <th>UUID</th>
    <th>Fecha</th>
    <th>Subtotal</th>
    <th>Total</th>
  </tr>

  <?php

  while ($row = mysqli_fetch_array($xml)) {

    $ruta = $row['ruta'];
    $id = $row['id'];
    $correo_remitente = $row['correo'];
    //echo $ruta;

    procesarXMLyImprimirComoJS($ruta, $id, $correo_remitente);
    sleep(4);
  }


  ?>
</table>