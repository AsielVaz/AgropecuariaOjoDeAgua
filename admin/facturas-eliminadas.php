<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?php echo 'Agro-Gestión' ?> </title>
    <link rel="icon" type="image/x-icon" href="../logo.png" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="../src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="../src/plugins/src/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/light/table/datatable/dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/table/datatable/dt-global_style.css">
    <!-- END PAGE LEVEL STYLES -->




    <script src="../assets/js/jquery-3.6.0.min.js"></script>
    <link href="css/berries-admin.css?v=20260904-4" rel="stylesheet" type="text/css">
</head>

<body class="layout-boxed">
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <?php include_once "template/head.php" ?>

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <?php include_once "template/sidebar.php" ?>
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">

                    <div class="row layout-top-spacing">
                        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                            <div class="widget-content widget-content-area br-8">
                                <div class="inv-list-top-section">
                                    <div class="row">


                                    </div>
                                </div>
                                <?php
                                include_once 'api/adminFacturas.php';
                                $adminFacturas = new AdministradorFacturas();
                                $facturas = $adminFacturas->dameFacturasEliminadas();
                                function formatearFecha($fecha)
                                {
                                    $fecha = explode(" ", $fecha);
                                    $fecha = $fecha[0];
                                    $fecha = explode("-", $fecha);
                                    $mes = $fecha[1];
                                    switch ($mes) {
                                        case 1:
                                            $mes = "Enero";
                                            break;
                                        case 2:
                                            $mes = "Febrero";
                                            break;
                                        case 3:
                                            $mes = "Marzo";
                                            break;
                                        case 4:
                                            $mes = "Abril";
                                            break;
                                        case 5:
                                            $mes = "Mayo";
                                            break;
                                        case 6:
                                            $mes = "Junio";
                                            break;

                                        case 7:
                                            $mes = "Julio";
                                            break;
                                        case 8:
                                            $mes = "Agosto";
                                            break;
                                        case 9:
                                            $mes = "Septiembre";
                                            break;
                                        case 10:
                                            $mes = "Octubre";
                                            break;
                                        case 11:
                                            $mes = "Noviembre";
                                            break;
                                        case 12:
                                            $mes = "Diciembre";
                                            break;
                                    }
                                    $fecha = $fecha[2] . "/" . $mes . "/" . $fecha[0];
                                    return $fecha;
                                }



                                function acondicionarLink($link)
                                {
                                    $link = str_replace("/home/iohanes/agropecuariaojodeagua.com/admin/", "", $link);
                                    $link = str_replace(" ", "%20", $link);
                                    return $link;
                                }


                                ?>
                                <div class="table-responsive">
                                <table id="facturas" class="table dt-table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="invoice-list_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">ID FOLIO</th>
                                                <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">Total con IVA</th>
                                                <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">Fecha ingreso</th>
                                                <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">Fecha vencimiento</th>
                                                <th class="sorting invoice-actions-heading" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Acciones disponibles">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($facturas as $factura) {
                                            ?>
                                                <tr role="row">
                                                    <td>A000<?php echo $factura->id ?> </td>
                                                    <td>$<?php echo number_format($factura->total_con_iva, 2) ?> </td>

                                                    <td><span class="inv-date"><?php echo formatearFecha($factura->fecha_de_ingreso) ?> </span></td>



                                                    <td><span class="inv-date"><?php echo formatearFecha($factura->fecha_pago) ?> </span></td>


                                                    <td class="invoice-actions">
                                                        <div class="invoice-actions__row">
                                                        <a href="agregar-pago.php?id=<?php echo $factura->id ?>" class="invoice-action invoice-action--pay" title="Registrar pago" aria-label="Registrar pago">
                                                            <svg style="width: 40px;" class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17.3a5 5 0 0 0 2.6 1.7c2.2.6 4.5-.5 5-2.3.4-2-1.3-4-3.6-4.5-2.3-.6-4-2.7-3.5-4.5.5-1.9 2.7-3 5-2.3 1 .2 1.8.8 2.5 1.6m-3.9 12v2m0-18v2.2" />
                                                            </svg>
                                                        </a>

                                                        <a onclick="cargarFactura('<?php echo acondicionarLink($factura->ruta_pdf) ?>')" data-bs-toggle="modal" data-bs-target=".bd-example-modal-xl" href="<?php echo acondicionarLink($factura->ruta_pdf) ?>" class="invoice-action invoice-action--view" title="Ver factura" aria-label="Ver factura">
                                                            <svg style="width: 40px;" class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4 6-9 6s-9-4.8-9-6c0-1.2 4-6 9-6s9 4.8 9 6Z" />
                                                                <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                            </svg>
                                                        </a>

                                                        <a data-bs-toggle="modal" data-bs-target=".bd-example-modal-xl<?php echo $factura->id ?>" href="<?php echo acondicionarLink($factura->ruta_pdf) ?>" class="invoice-action invoice-action--defer" title="Aplazar factura" aria-label="Aplazar factura">
                                                            <svg style="width: 40px;" class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                                                            </svg>
                                                        </a>


                                                        <a onclick="eliminar('<?php echo ($factura->id) ?>')" class="invoice-action invoice-action--delete" title="Eliminar factura" aria-label="Eliminar factura" role="button">
                                                            <svg  style="width: 40px;" class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                            </svg>
                                                        </a>
                                                        </div>


                                                        <div class="modal fade bd-example-modal-xl<?php echo $factura->id ?>" tabindex="-1" aria-labelledby="myExtraLargeModalLabel" aria-modal="true" role="dialog">
                                                            <div class="modal-dialog modal-xl" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header" style="background-color: white;">
                                                                        <h5 class="modal-title" id="myExtraLargeModalLabel">Aplazar la factura A000<?php echo $factura->id ?></h5>

                                                                    </div>
                                                                    <div class="modal-body" style="background-color: white;">

                                                                        <label for="">Dias a aplazar</label>
                                                                        <input class="form-control" type="date" id="dias<?php echo $factura->id ?>">


                                                                    </div>
                                                                    <div class="modal-footer" style="background-color: white;">
                                                                        <button onclick="aplazar('<?php echo $factura->id ?>')" class="btn btn-light-dark _effect--ripple waves-effect waves-light">Aplazar</button>
                                                                        <button class="btn btn-light-dark _effect--ripple waves-effect waves-light" data-bs-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </td>
                                                </tr>
                                            <?php }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    </div>


    <div class="modal fade bd-example-modal-xl" tabindex="-1" aria-labelledby="myExtraLargeModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">Documento de factura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="espacioFactura" style="height: 500px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light-dark _effect--ripple waves-effect waves-light" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../src/plugins/src/waves/waves.min.js"></script>
    <script src="js/berries-admin.js?v=20260903-3"></script>
    <script src="../src/plugins/src/table/datatable/datatables.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="../src/plugins/src/apex/apexcharts.min.js"></script>
    <script src="../src/assets/js/dashboard/dash_1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    
    <script>
        $('#facturas').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                "<'table-responsive'tr>" +
                "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
                "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 10
        });
    </script>
    <!-- END PAGE LEVEL SCRIPTS -->

    <script>
        function cargarFactura(link) {
            document.getElementById('espacioFactura').innerHTML = "";
            var iframe = document.createElement('iframe');
            iframe.src = link;
            iframe.style.width = "100%";
            iframe.style.height = "100%";
            document.getElementById('espacioFactura').appendChild(iframe);
        }
    </script>
    <script>
        function eliminar(id) {
            Swal.fire({
                title: "¿Desea eliminar esta factura?",
                showDenyButton: true,
                confirmButtonText: "si",

            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    console.log(id);
                    var datosPago = new FormData();
                    datosPago.append('accion', 'eliminar');
                    datosPago.append('id', id);
                    fetch("api/apiFacturas.php", {
                            method: "POST",
                            body: datosPago,
                        })
                        .then((respuesta) => respuesta.json())
                        .then((data) => {
                            console.log(data);
                            Swal.fire({
                                icon: data.status,
                                title: data.mensaje,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            setTimeout(function() {
                                window.location.reload();
                            }, 3000);

                        });
                }
            });

        }
    </script>

</body>

</html>
