<?php 

include_once 'admin/api/notificador.php';
$name=$_POST['name'];
$email=$_POST['email'];
mailerNot($email,'cliente','prueba',generarMail(),'','','');
function generarMail(){
    $mail='
    
		
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
<title>AOA</title>

<style type="text/css">

div, p, a, li, td { -webkit-text-size-adjust:none; }

*{
-webkit-font-smoothing: antialiased;
-moz-osx-font-smoothing: grayscale;
}

.ReadMsgBody
{width: 100%; background-color: #ffffff;}
.ExternalClass
{width: 100%; background-color: #ffffff;}
body{width: 100%; height: 100%; background-color: #ffffff; margin:0; padding:0; -webkit-font-smoothing: antialiased;}
html{width: 100%; background-color: #ffffff;}

@font-face {font-family: proxima_novalight;src: url(http://rocketway.net/themebuilder/products/font/proximanova-light-webfont.eot);src: url(http://rocketway.net/themebuilder/products/font/proximanova-light-webfont.eot?#iefix) format(embedded-opentype),url(http://rocketway.net/themebuilder/products/font/proximanova-light-webfont.woff) format(woff),url(http://rocketway.net/themebuilder/products/font/proximanova-light-webfont.ttf) format(truetype);font-weight: normal;font-style: normal;}

@font-face {font-family: proxima_nova_rgregular; src: url(http://rocketway.net/themebuilder/products/font/proximanova-regular-webfont.eot);src: url(http://rocketway.net/themebuilder/products/font/proximanova-regular-webfont.eot?#iefix) format(embedded-opentype),url(http://rocketway.net/themebuilder/products/font/proximanova-regular-webfont.woff) format(woff),url(http://rocketway.net/themebuilder/products/font/proximanova-regular-webfont.ttf) format(truetype);font-weight: normal;font-style: normal;}

@font-face {font-family: proxima_novasemibold;src: url(http://rocketway.net/themebuilder/products/font/proximanova-semibold-webfont.eot);src: url(http://rocketway.net/themebuilder/products/font/proximanova-semibold-webfont.eot?#iefix) format(embedded-opentype),url(http://rocketway.net/themebuilder/products/font/proximanova-semibold-webfont.woff) format(woff),url(http://rocketway.net/themebuilder/products/font/proximanova-semibold-webfont.ttf) format(truetype);font-weight: normal;font-style: normal;}
    
@font-face {font-family: proxima_nova_rgbold;src: url(http://rocketway.net/themebuilder/products/font/proximanova-bold-webfont.eot);src: url(http://rocketway.net/themebuilder/products/font/proximanova-bold-webfont.eot?#iefix) format(embedded-opentype),url(http://rocketway.net/themebuilder/products/font/proximanova-bold-webfont.woff) format(woff),url(http://rocketway.net/themebuilder/products/font/proximanova-bold-webfont.ttf) format(truetype);font-weight: normal;font-style: normal;}
	
@font-face {font-family: proxima_novathin;src: url(http://rocketway.net/themebuilder/products/font/proximanova-thin-webfont.eot);src: url(http://rocketway.net/themebuilder/products/font/proximanova-thin-webfont.eot?#iefix) format(embedded-opentype),url(http://rocketway.net/themebuilder/products/font/proximanova-thin-webfont.woff) format(woff),url(http://rocketway.net/themebuilder/products/font/proximanova-thin-webfont.ttf) format(truetype);font-weight: normal;font-style: normal;}
   
@font-face {font-family: proxima_novaextrabold;src: url(http://rocketway.net/themebuilder/products/font/proximanova-extrabold-webfont.eot);src: url(http://rocketway.net/themebuilder/products/font/proximanova-extrabold-webfont.eot?#iefix) format(embedded-opentype),url(http://rocketway.net/themebuilder/products/font/proximanova-extrabold-webfont.woff2) format(woff2),url(http://rocketway.net/themebuilder/products/font/proximanova-extrabold-webfont.woff) format(woff),url(http://rocketway.net/themebuilder/products/font/proximanova-extrabold-webfont.ttf) format(truetype);font-weight: normal;font-style: normal;}  


p {padding: 0!important; margin-top: 0!important; margin-right: 0!important; margin-bottom: 0!important; margin-left: 0!important; }

.hover:hover {opacity:0.85;filter:alpha(opacity=85);}
.underline:hover {text-decoration: underline!important;}
#ring{position: relative; z-index: 1;}
#ring::after {content: ""; position: absolute; background-size: 31px!important; z-index: -1; top: 0; bottom: 0; left: 0; right: 0; background-repeat: no-repeat!important; background: url(http://rocketway.net/themebuilder/products/templates/atom/images-mail/icon_ring.png) center center; opacity: .2;}
#ring:hover {background-repeat: no-repeat!important; position: none; background: url(http://rocketway.net/themebuilder/products/templates/atom/images-mail/icon_ring.png) center center; opacity: .9!important; z-index: 2; background-size: 31px!important;}
.jump:hover {opacity:0.75;filter:alpha(opacity=75);padding-top: 10px!important;}

a#rotator img {-webkit-transition: all 1s ease-in-out;-moz-transition: all 1s ease-in-out; -o-transition: all 1s ease-in-out; -ms-transition: all 1s ease-in-out; }
a#rotator img:hover { -webkit-transform: rotate(360deg); -moz-transform: rotate(360deg); -o-transform: rotate(360deg);-ms-transform: rotate(360deg); }

#Opacity {opacity: 0.95}


.image260 img {width: 260px; height: auto;}
.image194 img {width: 194px; height: auto;}
.image271 img {width: 271px; height: auto;}
.image16 img {width: 16px; height: auto;}
.image199 img {width: 199px; height: auto;}
.image270 img {width: 270px; height: auto;}
.image596 img {width: 596px; height: auto;}
.image298 img {width: 298px; height: auto;}
.icon37 img {width: 37px; height: auto;}
.icon80 img {width: 80px; height: auto;}
#logo img {width: 125px; height: auto;}

</style>


<!-- @media only screen and (max-width: 640px) 
		   {*/
		   -->
<style type="text/css"> @media only screen and (max-width: 640px){
		body{width:auto!important;}
		table[class=full] {width: 100%!important; clear: both; }
		table[class=mobile] {width: 100%!important; padding-left: 30px; padding-right: 30px; clear: both; }
		table[class=fullCenter] {width: 100%!important; text-align: center!important; clear: both; }
		td[class=fullCenter] {width: 100%!important; text-align: center!important; clear: both; }
		td[class=full] {width: 100%!important; clear: both; }
		.erase {display: none;}
		.buttonScale {float: none!important; text-align: center!important; display: inline-block!important; clear: both;}
		.image298 img {width: 100%!important;}
		.image260 img {width: 100%!important;}
		.image199 img {width: 100%!important;}
		.image194 img {width: 100%!important;}
		.image270 img {width: 100%!important;}
		.image596 img {width: 100%!important;}
		.image298 img {width: 100%!important;}
		td[class=pad20] {padding-left: 20px!important; padding-right: 20px!important; text-align: center!important; clear: both; }
		td[class=pad30] {padding-left: 30px!important; padding-right: 30px!important; text-align: center!important; clear: both; }
		*[class=h20] {width: 100%!important; height: 20px!important;}
		*[class=h45] {width: 100%!important; height: 45px!important;}
		table[class=mcenter] {text-align:center; vertical-align:middle; clear:both!important; float:none; margin: 0px!important;}
		
} </style>
<!--

@media only screen and (max-width: 479px) 
		   {
		   -->
<style type="text/css"> @media only screen and (max-width: 479px){
		body{width:auto!important;}
		table[class=full] {width: 100%!important; clear: both; }
		table[class=mobile] {width: 100%!important; padding-left: 20px; padding-right: 20px; clear: both; }
		table[class=fullCenter] {width: 100%!important; text-align: center!important; clear: both; }
		td[class=fullCenter] {width: 100%!important; text-align: center!important; clear: both; }
		.erase {display: none;}
		.buttonScale {float: none!important; text-align: center!important; display: inline-block!important; clear: both;}
		.eraseMob {display: none!important;}
		.image260 img {width: 100%!important;}
		.image199 img {width: 100%!important;}
		.image194 img {width: 100%!important;}
		.image270 img {width: 100%!important;}
		.image596 img {width: 100%!important;}
		.image298 img {width: 100%!important;}
		td[class=pad20] {padding-left: 20px!important; padding-right: 20px!important; text-align: center!important; clear: both; }
		td[class=pad30] {padding-left: 30px!important; padding-right: 30px!important; text-align: center!important; clear: both; }
		table[class=mcenter] {text-align:center; vertical-align:middle; clear:both!important; float:none; margin: 0px!important;}
		*[class=h20] {width: 100%!important; height: 20px!important;}
		*[class=h45] {width: 100%!important; height: 45px!important;}
		table[class=mcenter2] {text-align:center; vertical-align:middle; clear:both!important; float:none; margin: 0px!important;}
		
		}
} </style> 


</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" yahoo="fix">

<div class="ui-sortable" id="sort_them"> 
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" style="background-color: rgb(36, 192, 103);">
	<tr>
		<td width="100%" valign="top" align="center" style="background-image: url(https://agropecuariaojodeagua.com/images-mail/image_80px_1.jpg); background-position: center top; background-repeat:no-preat!important; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; background-position: center center; background-repeat: no-repeat;" id="BGheaderChange">
		<div mc:hideable>
			
			<!-- Wrapper -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
				<tr>
					<td align="center">
					
						<!-- Column Right -->
						<table width="50%" border="0" cellpadding="0" cellspacing="0" align="right" class="full">
							<tr>
								<td height="30" width="100%" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
							</tr>
							<tr>
								<td width="100%" align="right">
								
									<table width="40" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="erase" align="left" border="0" cellpadding="0" cellspacing="0" object="drag-module-small">
										<tr>
											<td height="40" width="100%" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
										</tr>
									</table>
								
									<!-- Nav --> 
									

								
								</td>
							</tr>
							<tr>
								<td height="100" width="100%" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
							</tr>
						</table><!-- End Column Right -->
						
						<!-- Column Left -->
						<table width="49%" border="0" cellpadding="0" cellspacing="0" align="left" class="full" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
							<tr>
								<td width="100%" align="center"id="Opacity" style="background-image: -webkit-linear-gradient(bottom, rgb(62, 128, 90), rgb(62, 128, 90)); background-color: rgb(36, 192, 103); background-position: initial initial; background-repeat: initial initial;">
								
									<!-- Space -->
									<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
										<tr>
											<td width="100%" height="30"></td>
										</tr>
									</table><!-- End Space -->
						
									<!-- Wrapper -->
									<table width="300" border="0" cellpadding="0" cellspacing="0" align="right" class="mobile">
										<tr>
											<td width="100%" align="center">
												
											<table width="250" border="0" cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center;" class="fullCenter" object="drag-module-small">
												<tr>
													<td height="40" valign="middle" align="left" width="100%" class="fullCenter" id="logo" >
														<a href="#" style="text-decoration: none;"> 
															<img src="https://agropecuariaojodeagua.com/images-mail/logo.png" width="125" alt="" border="0"  class="hover">
														</a>
													</td>
												</tr>
												<tr>
													<td width="100%" height="30" style="font-size: 1px; line-height: 1px;" class="h20">&nbsp;</td>
												</tr>
													<tr>
														<td valign="middle" width="100%" style="text-align: left; font-family: Domine, Helvetica, Arial, sans-serif; font-size: 43px; color: rgb(255, 255, 255); line-height: 50px; font-weight: 400; word-break: break-word;"class="fullCenter"  >
															¡Hola '.$_POST['name'].'! Hemos recibido tu mensaje.
														</td>
													</tr>
													<tr>
														<td width="100%" height="30"></td>
													</tr>
													<tr>
														<td valign="middle" width="100%" style="text-align: left; font-family: Roboto, Helvetica, Arial, sans-serif; font-size: 14px; color: rgb(255, 255, 255); line-height: 22px; font-weight: 400; font-style: italic;" class="fullCenter" >
															Gracias por ponerte en contacto con nosotros. Tu mensaje ha sido recibido correctamente.
														</td>
													</tr>
													<tr>
														<td width="100%" height="40"></td>
													</tr>
													<!----------------- Button ----------------->
													<tr>
														<td width="auto" align="left" class="buttonScale">
															
															<!-- SORTABLE -->
															<div class="sortable_inner ui-sortable">
															<table border="0" cellpadding="0" cellspacing="0" align="left" object="drag-module-small" class="buttonScale">
																<tr>
																	<td width="auto" align="center" height="40" bgcolor="#ffffff"style="font-weight: 500; font-family: roboto, Helvetica, Arial, sans-serif; color: rgb(36, 192, 103); border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; padding-left: 15px; padding-right: 15px; text-transform: uppercase; background-color: rgb(255, 255, 255);">
																		
																			<a href="#uno" style="color: rgb(36, 192, 103); font-size: 13px; text-decoration: none; line-height: 40px; width: 100%;">Sobre nosotros</a>
																
																	</td>
																</tr>
															</table>
															</div>
														
														</td>
													</tr>
													<!----------------- End Button Center ----------------->
													<tr>
														<td width="100%" height="80">									
														</td>
													</tr>
												</table><!-- End Wrapper -->
												
												
												
											</td>
										</tr>
									</table><!-- End Wrapper -->
								
								</td>
							</tr>
						</table><!-- End Column Left -->
						
					</td>
				</tr>
			</table><!-- End Wrapper -->
		
		</div>
		</td>
	</tr>
</table>


<table  id="uno" width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" bgcolor="#ffffff" style="background-color: rgb(255, 255, 255);">
	<tr>
		<td width="100%" valign="top" align="center">
		
			
			<!-- Wrapper -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile">
				<tr>
					<td align="center">
					
						<!-- SORTABLE -->
						<div class="sortable_inner ui-sortable">
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full" object="drag-module-small">
							<tr>
								<td width="100%" height="65"></td>
							</tr>
						</table>
						
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full" object="drag-module-small">
							<tr>
								<td width="100%" align="center">
								
									<!-- Image 271px - 1 -->
									<table width="271" border="0" cellpadding="0" cellspacing="0" align="right" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center;" class="fullCenter">
										<tr>
											<td width="100%" align="center" class="image271" >
												<a href="#" style="text-decoration: none;"><img src="https://agropecuariaojodeagua.com/images-mail/image_271_1.jpg" width="271" alt="" border="0" class="hover" ></a>
											</td>
										</tr>
									</table>
									
									<!-- Space -->
									<table width="1" border="0" cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="full">
										<tr>
											<td width="100%" height="35" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
										</tr>
									</table><!-- End Space --> 
									
									<!-- Image 285px - 1 -->
									<table width="270" border="0" cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: center;" class="fullCenter">
										<tr>
											<td valign="middle" width="100%" style="text-align: left; font-family: Domine, Helvetica, Arial, sans-serif; font-size: 18px; color: rgb(88, 89, 91); line-height: 30px; font-weight: normal;"class="fullCenter"  >
												Agropecuaria Ojo de Agua
											</td>
										</tr>
										<tr>
											<td width="100%" height="10" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
										</tr>
										<tr>
											<td width="100%" height="12" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
										</tr>
										<tr>
											<td valign="middle" width="100%" style="text-align: left; font-family: roboto, Helvetica, Arial, sans-serif; font-size: 14px; color: #8f8f8f; line-height: 22px; font-weigth: 400;" class="fullCenter" >
												Nos ubicamos en Jalisco, México, cultivamos zarzamoras y frambuesas de la más alta calidad. Nuestro compromiso con la excelencia y la sostenibilidad se refleja en cada etapa del proceso de cultivo. Con un equipo de agricultores expertos y un enfoque en prácticas agrícolas innovadoras, nos esforzamos por ofrecer productos frescos y deliciosos que satisfagan las expectativas de nuestros clientes. En "Ojo de Agua", cada baya que cultivamos es un testimonio de nuestro amor por la tierra y nuestro compromiso con la calidad.
											</td>
										</tr>
										
										
									</table>
									
								</td>
							</tr>
						</table>
						
						<!-- Space -->
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full" object="drag-module-small"> 
							<tr>
								<td width="100%" height="65"></td>
							</tr>
						</table><!-- End Space -->
						</div>
						
					</td>
				</tr>
			</table><!-- End Wrapper -->
		
		</div>
		</td>
	</tr>
</table>

<!-- Seperator 2 -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" bgcolor="#24c067" style="background-color: rgb(36, 192, 103);">
	<tr>
		<td align="center" style="background-image: url(images-mail/seperator02.jpg); background-position: center top; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; background-position: center center; background-repeat: no-repeat;" id="sep2"> 
		
			<!-- Wrapper -->
			<table class="mobile" align="center" border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center" style="background-color: rgb(98, 136, 82);">
					
						<!-- Space -->
						<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
							<tr>
								<td width="100%" height="42"></td>
							</tr>
						</table><!-- End Space -->
					
						<!-- Wrapper -->
						<table class="full" align="center" border="0" width="600" cellpadding="0" cellspacing="0">
							<tr>
								<td width="100%" align="center" class="image16">
			
									<!-- Text -->
									<table width="390" align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="full">
										<tr>
											<td valign="middle" width="100%" style="text-align: left; font-family: roboto, Helvetica, Arial, sans-serif; font-size: 26px; color: rgb(255, 255, 255); line-height: 32px; text-transform: uppercase; font-weight: 700;"  class="fullCenter">
												¡Visita nuestra página web!
											</td>
										</tr>
										
										
									</table>
									
									<table width="160" align="right" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="full">
										<tr>
											<td height="5" width="100%" style="font-size: 1px; line-height: 1px;" class="h45">&nbsp;</td>
										</tr>
										<tr>
											<td width="100%" align="right" class="buttonScale">

												<!-- SORTABLE -->
												<div class="sortable_inner ui-sortable">
												<table border="0" cellpadding="0" cellspacing="0" align="right" class="buttonScale" object="drag-module-small">
													<tr>
														<td align="center" height="36" bgcolor="#58595b"style="border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; padding-left: 15px; padding-right: 15px; font-weight: 500; font-family: Roboto, Helvetica, Arial, sans-serif; color: rgb(255, 255, 255); text-transform: uppercase; background-color: rgb(124 28 69);">
															<a href="https://agropecuariaojodeagua.com/"  style="color: rgb(209, 209, 209); font-size: 13px; text-decoration: none; line-height: 32px; width: 100%;">Visitar</a>
														</td>
													</tr>
												</table>
												</div>

											</td>
										</tr>
									</table>
						
								</td>
							</tr>
						</table>
						
						<!-- Space -->
						<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
							<tr>
								<td width="100%" height="42"></td>
							</tr>
						</table><!-- End Space -->
			
					</td>
				</tr>
			</table><!-- End Wrapper --> 
		
		</div>
		</td>
	</tr>
</table>
<!-- Seperator 1 End -->

<!-- Wrapper -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" bgcolor="#48494b" style="background-color: rgb(72, 73, 75);">
	<tr>
		<td width="100%" valign="top" align="center">
		
			
			<!-- Wrapper -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile">
				<tr>
					<td align="center">
					
						<!-- Space -->
						<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
							<tr>
								<td width="100%" height="10"></td>
							</tr>
						</table><!-- End Space -->
						
						<!-- Wrapper -->
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
							<tr>
								<td width="100%" align="center">
									
									<!-- Text Left -->
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
										<tr>
											<td valign="middle" width="100%" style="text-align: center; font-family: Roboto, Helvetica, Arial, sans-serif; font-size: 12px; color: #8f8f8f; line-height: 22px; font-weight: 400;" class="fullCenter" >
												© 2024 Todos los derechos reservados por EDworld - ALA
													
													
											</td>
										</tr>
									</table>
									
								</td>
							</tr>
						</table><!-- End Wrapper -->
						
						<!-- Space -->
						<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
							<tr>
								<td width="100%" height="10" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
							</tr>
							<tr>
								<td width="100%" height="1" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
							</tr>
						</table><!-- End Space -->
						
					</td>
				</tr>
			</table><!-- End Wrapper -->
		
		</div>
		</td>
	</tr>
</table><!-- Wrapper -->
</div>
</body>	<style>body{ background: none !important; } </style>

    ';
    return $mail;
}

?>