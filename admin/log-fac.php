<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?php echo 'Agro-Gestión' ?></title>
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
                    <div class="col-xl-12 col-lg-12 col-sm-12 layout-top-spacing layout-spacing">
                        <div class="secondary-nav">
                            <div class="breadcrumbs-container" data-page-heading="Analytics">
                                <header class="header navbar navbar-expand-sm">

                                    <ul class="navbar-nav flex-row ms-auto breadcrumb-action-dropdown">
                                        <li class="nav-item more-dropdown">
                                            <div class="dropdown  custom-dropdown-icon">
                                                <a style="background-color: #5A9F19;" class="btn btn-succes mb-2 me-4 _effect--ripple waves-effect waves-light" href="agregar-usuario.php">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48px" height="48px">
                                                        <path fill="#4caf50" d="M44,24c0,11.045-8.955,20-20,20S4,35.045,4,24S12.955,4,24,4S44,12.955,44,24z"></path>
                                                        <path fill="#fff" d="M21,14h6v20h-6V14z"></path>
                                                        <path fill="#fff" d="M14,21h20v6H14V21z"></path>
                                                    </svg>

                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                </header>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area br-8">
                            <div id="invoice-list_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                <div class="inv-list-top-section">
                                    <div class="row">

                                        <div class="col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0 mt-3">

                                            <div class="dt-action-buttons align-self-center">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php include_once 'api/adminLog.php';
                                include_once 'template/pagination.php';
                                $adminLog = new AdministradorLog();
                                $registrosPorPagina = 20;
                                $paginaLogs = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT);
                                $paginaLogs = ($paginaLogs !== false && $paginaLogs !== null && $paginaLogs > 0)
                                    ? $paginaLogs
                                    : 1;
                                $totalLogs = $adminLog->contarLogs();
                                $totalPaginasLogs = max(1, (int) ceil($totalLogs / $registrosPorPagina));
                                $paginaLogs = min($paginaLogs, $totalPaginasLogs);
                                $offsetLogs = ($paginaLogs - 1) * $registrosPorPagina;
                                $logs = $adminLog->dameLog($registrosPorPagina, $offsetLogs);
                                ?>


                                <div class="table-responsive">
                                <table id="tabla-pagos" class="table dt-table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="invoice-list_info">
                                <thead>
                                    <tr role="row">

                                        <th class="sorting_asc" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Invoice Id: activate to sort column descending" style="width: 112px;"> Id</th>
                                        <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" style="width: 224px;">Correo</th>
                                        <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">Fecha</th>
                                        <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 90px;">Mensaje   </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($logs as $log) {
                                    ?>

                                        <tr role="row">
                                            <td><?php echo $log->id ?> </td>
                                            <td><?php echo $log->correo ?> </td>
                                            <td><?php echo $log->fecha ?> </td>
                                            <td><?php echo $log->mensaje ?> </td>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                                </div>
                                <?php renderBerryPagination(
                                    $paginaLogs,
                                    $totalPaginasLogs,
                                    $totalLogs,
                                    $registrosPorPagina
                                ); ?>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="../src/plugins/src/table/datatable/datatables.js"></script>
    <script>
        $('#tabla-pagos').DataTable({
            "dom": "t",
            "paging": false,
            "info": false,
            "searching": false,
            "ordering": false,
            "stripeClasses": []
        });
    </script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
        function eliminar(id) {
            Swal.fire({
                title: "Desea eliminar este registro?",
                showDenyButton: true,
                confirmButtonText: "si",

            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    console.log(id);
                    var datosPago = new FormData();
                    datosPago.append('accion', 'eliminar');
                    datosPago.append('id', id);
                    fetch("api/apiUsuarios.php", {
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
