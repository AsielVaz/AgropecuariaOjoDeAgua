<?php
include "conectorBD.php";
include "notificador.php";

function descargarAdjuntosXML($correo, $contraseña, $servidor, $carpeta_destino)
{
  // Conexión al servidor IMAP
  $conector = new conector();
  $inbox = imap_open("{{$servidor}}INBOX", $correo, $contraseña) or die("No se pudo conectar al servidor: " . imap_last_error());

  // Buscar correos no leídos
  $emails = imap_search($inbox, 'UNSEEN');

  // Si no hay correos no leídos, terminar la función
  if (!$emails) {
    echo "No hay correos no leídos.";
    return;
  }
  $fecha = date("Y-m-d H:i:s");

  $raiz = $_SERVER['DOCUMENT_ROOT'];
  $carpeta_destino = $raiz . "/admin/xml";
  // Recorrer los correos no leídos
  foreach ($emails as $email_number) {
    // Obtener la información del correo
    $structure = imap_fetchstructure($inbox, $email_number);
    $correo_remitente = imap_fetch_overview($inbox, $email_number, 0);
    $correo_remitente = $correo_remitente[0]->from;
    // Verificar si hay partes adjuntas
    $encontrado = false;
    $encontrado2 = false;
    $random = rand(100000, 999999);



    if ($structure->parts) {
      
      if (isset($structure->parts) && count($structure->parts) > 0) {
        foreach ($structure->parts as $part_number => $part) {





          $attachment = array();
          if ($part->ifdparameters) {
            foreach ($part->dparameters as $object) {
              if (strtolower($object->attribute) == 'filename') {
                $attachment['filename'] = $object->value;
              }
            }
          }
          if ($part->ifparameters) {
            foreach ($part->parameters as $object) {
              if (strtolower($object->attribute) == 'name') {
                $attachment['name'] = $object->value;
              }
            }
          }
          if (isset($attachment['filename']) || isset($attachment['name'])) {
            // Aquí puedes hacer lo que quieras con el nombre del archivo adjunto
            // Por ejemplo, agregarlo a un array de archivos adjuntos
            $adjuntos[] = isset($attachment['filename']) ? $attachment['filename'] : $attachment['name'];
            $adjuntoX = isset($attachment['filename']) ? $attachment['filename'] : $attachment['name'];
          }

          $formato = substr($adjuntoX, -3);
          echo "El formato es " . $formato . "<br>";

          $random = rand(100000, 999999);

          // Verificar si es un archivo adjunto de tipo XML
          if ($formato == 'xml') {
            $encontrado = true;
            // Obtener el cuerpo del adjunto
            $attachment = imap_fetchbody($inbox, $email_number, $part_number + 1);

            // Decodificar el cuerpo del adjunto
            $attachment = base64_decode($attachment);

            // Obtener el nombre del archivo adjunto
            $filename = "factura" . $random  . "N.xml";

            // Guardar el archivo en la carpeta destino
            file_put_contents($carpeta_destino . '/' . $filename, $attachment);

            echo "Archivo XML descargado: $filename <br>";
            $rutaCompleta = $carpeta_destino . '/' . $filename;
            $rutaXml = $rutaCompleta;
          }
          if ($formato == 'pdf') {
            $encontrado2 = true;

            // Obtener el cuerpo del adjunto
            $attachment = imap_fetchbody($inbox, $email_number, $part_number + 1);

            // Decodificar el cuerpo del adjunto
            $attachment = base64_decode($attachment);

            // Obtener el nombre del archivo adjunto
            $filename = "factura" . $random  . "N.pdf";

            // Guardar el archivo en la carpeta destino
            file_put_contents($carpeta_destino . '/' . $filename, $attachment);

            echo "Archivo XML descargado: $filename <br>";
            $rutaCompleta = $carpeta_destino . '/' . $filename;
            $rutaPdf = $rutaCompleta;
          }




          
        }
      }
    }





    if (!$encontrado) {
      echo "No se encontraron archivos adjuntos XML.";
      $conector->ejecutar("INSERT INTO `log_facturas`(`correo`, `fecha`, `mensaje`) VALUES ('$correo_remitente','$fecha','No se encontraron archivos adjuntos XML.')");

      $usuario = encontrarUsuario($correo_remitente);
      $correo = $usuario['correo'];
      $nombre = $usuario['nombre'];
      $contenido = generaCorreo('NA', $nombre, "No se encontraron archivos adjuntos XML Y PDF.", 0);
      $conector->ejecutar("INSERT INTO `log_facturas`(`correo`, `fecha`, `mensaje`) VALUES ('$correo_remitente','$fecha','No se encontraron archivos adjuntos XML Y PDF.')");

      mailerNot($usuario['correo'], $usuario['nombre'], "Error recepción ", $contenido, "", "", "");
    } else if (!$encontrado2) {
      echo "No se encontraron archivos adjuntos PDF.";
      $usuario = encontrarUsuario($correo_remitente);
      $correo = $usuario['correo'];
      $nombre = $usuario['nombre'];
      $contenido = generaCorreo('NA', $nombre, "No se encontraron archivos adjuntos de tipo PDF", 1);
      $conector->ejecutar("INSERT INTO `log_facturas`(`correo`, `fecha`, `mensaje`) VALUES ('$correo_remitente','$fecha','No se encontraron archivos adjuntos de tipo PDF')");

      mailerNot($usuario['correo'], $usuario['nombre'], "Error recepción ", $contenido, "", "", "");
    } else {
      $conector->ejecutar("insert into xml_ingresados (ruta,ingresado,correo,ruta_pdf) values ('$rutaXml','0','$correo_remitente','$rutaPdf')");
      $conector->ejecutar("INSERT INTO `log_facturas`(`correo`, `fecha`, `mensaje`) VALUES ('$correo_remitente','$fecha','SE PROCESO CORRECTAMENTE EL XML')");

    }


    // Marcar el correo como leído
    imap_setflag_full($inbox, $email_number, "\\Seen");
  }

  // Cerrar la conexión IMAP
  imap_close($inbox);
}
// Ejemplo de uso
$correo = 'ingresofacturas@agropecuariaojodeagua.com';
$contraseña = 'Rz$c)K0U#&k7';
$servidor = 'efficientdata.mx:993/imap/ssl/novalidate-cert'; // Ajusta el servidor IMAP según tu proveedor de correo
$carpeta = ''; // Ruta absoluta a la carpeta donde se guardarán los archivos

descargarAdjuntosXML($correo, $contraseña, $servidor, $carpeta);



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


function generaCorreo($rfc, $nombre, $error, $consejo)
{

  $consejos = array();
  $titulos = array();
  $titulos[] = "Verifica los archivos adjuntos";
  $consejos[] = "Verifica que que el correo contenga un archivo con XML y un archivo PDF para que sea correctamente procesado";
  $titulos[] = "Verifica el archivo PDF";
  $consejos[] = "Verifica que el archivo PDF se encuentre adjunto en el mensaje";

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
                <table align="center" background="https://agropecuariaojodeagua.com/imagenes/campo1.jpg" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%" style="background-image:url(https://agropecuariaojodeagua.com/imagenes/campo_noche.jpg); background-position:center; background-repeat:no-repeat;">
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
                              SU FACTURA HA SIDO RECHAZADA 	
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
                                    Hola estimado ' . $nombre . ' <br>
                                    Su factura fue rechazada debido a: <br> 
                                    ' . $error . '
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
                          ¿Que hacer si mi factura fue rechazada?
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
                                                ' . $titulos[$consejo] . '
                                              </td>
                                            </tr>														
                                            <tr>
                                              <td height="10"></td>
                                            </tr>
                                            <tr>
                                              <td align="left" class="MsoNormal" style="color:#444444;font-family:Segoe UI,sans-serif,Arial,Helvetica,Lato;font-size:14px;line-height:24px;">
                                                ' . $consejos[$consejo] . '
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
