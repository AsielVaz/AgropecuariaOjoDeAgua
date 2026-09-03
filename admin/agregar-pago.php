<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Agro-Gestión </title>
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

    <script src="../assets/js/jquery-3.6.0.min.js"></script>
    <link href="css/berries-admin.css?v=20260903-4" rel="stylesheet" type="text/css">
</head>

<body class="layout-boxed">
    <style>
        .loading-spinner {
            width: 30px;
            height: 30px;
            border: 2px solid indigo;
            border-radius: 50%;
            border-top-color: #0001;
            display: inline-block;
            animation: loadingspinner .7s linear infinite;
        }

        @keyframes loadingspinner {
            0% {
                transform: rotate(0deg)
            }

            100% {
                transform: rotate(360deg)
            }
        }
    </style>
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
        <?php

        include_once 'api/adminFacturas.php';
        $adminFacturas = new AdministradorFacturas();
        $factura = $adminFacturas->dameFactura($_GET['id']);
        ?>
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <form id="agregar-pago">


                    <div class="middle-content container-xxl p-0">

                        <div class="row layout-top-spacing">
                            <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12 layout-spacing">
                                <div class="widget widget-card-five">
                                    <div class="widget-content">
                                        <div class="account-box">

                                            <div class="info-box">
                                                <div class="icon">
                                                    <span>
                                                        <img src="../src/assets/img/money-bag.png" alt="money-bag">
                                                    </span>
                                                </div>

                                                <div class="balance-info">
                                                    <h6>Total del pago</h6>
                                                    <p>$ <?php echo number_format($factura->total_con_iva, 2) ?></p>
                                                </div>
                                            </div>

                                            <div class="card-bottom-section">
                                                <div></div>
                                                <a href="facturas.php" class="">Regresar a facturas</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" aria-describedby="basic-addon2" name="archivo">
                            </div>
                            <div class="dt-buttons">
                                <button type="submit" class="dt-button btn btn-primary _effect--ripple waves-effect waves-light" tabindex="0" aria-controls="invoice-list">
                                    <span>Agregar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <!-- END MAIN CONTAINER -->

    <div class="modal" id="modal-loading" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="loading-spinner mb-2"></div>
                    <div>Loading</div>
                </div>
            </div>
        </div>
    </div>

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
    <script>
        const formUsuario = document.getElementById("agregar-pago");


        formUsuario.addEventListener("submit", function(e) {
            e.preventDefault();
            $('#modal-loading').modal('show');

            var datosPago = new FormData(formUsuario);
            datosPago.append('accion', 'agregar');
            datosPago.append('id', '<?php echo $_GET['id']; ?>')
            fetch("api/apiEvidencias.php", {
                    method: "POST",
                    body: datosPago,
                })
                .then((respuesta) => respuesta.json())
                .then((data) => {
                    console.log(data);
                    Swal.fire({
                        icon: data.estatus,
                        title: data.mensaje,
                        showConfirmButton: true,
                      
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (data.estatus = 'success') {
                            window.location.href = "Evidencia-pagos.php";
                        }
                        else{
                            $('#modal-loading').modal('hide');
                        }
                    })
                  

                });
        });
    </script>

</body>

</html>
