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

    <script src="../assets/js/jquery-3.6.0.min.js"></script>
    <link href="css/berries-admin.css?v=20260903-3" rel="stylesheet" type="text/css">
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

    <?php include_once 'api/adminProveedores.php';
    $adminProveedores = new AdministradorProveedores();
    if(isset($_GET['id'])){
        $proveedor=$adminProveedores->dameProveedor($_GET['id']);
        $editando=1;
    }else{
        $editando=0;
    }
    ?>

    <?php include_once "template/head.php"; ?>

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <?php include_once "template/sidebar.php" ?>
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">

                    <div class="row layout-top-spacing">
                        <form id="agregar-provedor">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="RFC" aria-label="rfc" aria-describedby="basic-addon2" name="rfc" value="<?php echo $proveedor->rfc ?>">
                            </div>

                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Proveedor" aria-label="rfc" aria-describedby="basic-addon2" name="nombre" value="<?php echo $proveedor->nombre ?>">
                            </div>

                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="ejemplo@correo.com" aria-label="rfc" aria-describedby="basic-addon2" name="correo" value="<?php echo $proveedor->correo ?>">
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder=<?php echo 'Dirección' ?> aria-label="rfc" aria-describedby="basic-addon2" name="direccion" value="<?php echo $proveedor->direccion ?>">
                            </div>
                            <label for="basic-url" class="form-label">Periodo de pago (Dias)</label>
                            <div class="input-group mb-3">


                                <input type="number" class="form-control" placeholder="periodo de pago" aria-label="rfc" aria-describedby="basic-addon2" name="periodo_pago" value="<?php echo $proveedor->periodo_pago ?>">
                            </div>
                            <div class="dt-buttons">
                                <button type="submit" class="dt-button btn btn-primary _effect--ripple waves-effect waves-light" tabindex="0" aria-controls="invoice-list">
                                    <span>Guardar</span>
                                </button>
                            </div>
                        </form>
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
    <script>
        const formUsuario = document.getElementById("agregar-provedor");

        formUsuario.addEventListener("submit", function(e) {
            e.preventDefault();
            var datosProvedor = new FormData(formUsuario);
           
            if(<?php echo $editando?> ==1){
                datosProvedor.append('accion', 'modificar');
                datosProvedor.append('id','<?php echo $_GET['id'] ?>');
            }else{
                datosProvedor.append('accion', 'agregar');
            }
            fetch("api/apiProveedores.php", {
                    method: "POST",
                    body: datosProvedor,
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
                        window.location.href = "Provedores.php";
                    }, 3000);

                });
        });
    </script>

</body>

</html>