<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">

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

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="../src/plugins/src/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="../src/assets/css/light/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <link href="../src/assets/css/dark/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
     <!-- BEGIN PAGE LEVEL STYLES -->
     <link rel="stylesheet" type="text/css" href="../src/plugins/src/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/light/table/datatable/dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/css/dark/table/datatable/dt-global_style.css">
    <!-- END PAGE LEVEL STYLES -->


    <script src="../assets/js/jquery-3.6.0.min.js"></script>
    <link href="css/berries-admin.css?v=20260904-1" rel="stylesheet" type="text/css">
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
                                $facturas = $adminFacturas->dameFacturasEstaSemana();
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
                                ?>
                                <div class="table-responsive">
                                    <table id="xml" class="table dt-table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="invoice-list_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">id</th>
                                                <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">Folio fiscal</th>
                                                <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">Metodo de pago</th>
                                                <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">Total con IVA</th>
                                                <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Emil: activate to sort column ascending" style="width: 23px;">Total sin IVA</th>
                                                <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Emavil: activate to sort column ascending" style="width: 23px;">Estatus</th>
                                                <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">Fecha timbrado</th>
                                                <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Emvail: activate to sort column ascending" style="width: 23px;">Uso CFDI</th>
                                                <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">UUID</th>
                                                <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;"><?php echo 'Acción' ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($facturas as $factura) {
                                            ?>
                                                <tr role="row">
                                                    <td><?php echo $factura->id ?> </td>
                                                    <td><?php echo $factura->folio_fiscal ?> </td>
                                                    <td><?php echo $factura->metodo_de_pago ?> </td>
                                                    <td><?php echo number_format($factura->total_con_iva, 2) ?> </td>
                                                    <td><?php echo number_format($factura->total_sin_iva, 2) ?> </td>
                                                    <td><?php echo $factura->estatus_factura ?> </td>
                                                    <td><span class="inv-date"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                                            </svg><?php echo formatearFecha($factura->fecha_timbrado) ?> </span></td>
                                                    <td><?php echo $factura->uso_cfdi ?> </td>
                                                    <td><?php echo $factura->uuid ?> </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-light-info">Cargar</span>
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
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../src/plugins/src/waves/waves.min.js"></script>
    <script src="js/berries-admin.js?v=20260903-3"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="../src/plugins/src/apex/apexcharts.min.js"></script>
    <script src="../src/assets/js/dashboard/dash_1.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
     <!-- BEGIN PAGE LEVEL SCRIPTS -->
     <script src="../src/plugins/src/table/datatable/datatables.js"></script>
    <script>
        $('#xml').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
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

</body>

</html>
