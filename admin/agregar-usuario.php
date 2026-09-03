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

    <script src="../assets/js/jquery-3.6.0.min.js"></script>
    <link href="css/berries-admin.css?v=20260903-4" rel="stylesheet" type="text/css">
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
    <?php include_once 'api/adminUsuarios.php';
    $adminUsuarios=new AdministradorUsuario();
    $usuariomodi = new Usuario();
    if(isset($_GET['id'])){
        $usuariomodi=$adminUsuarios->dameUsuarioId($_GET['id']);
        $editando=1;
    }else{
        $editando=0;
    }
    ?>

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
                        <form id="agregar-usuario">
                            <input type="text" hidden value="sistema" name="tipo-usu">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Nombre" aria-label="rfc" aria-describedby="basic-addon2" name="nombre" value="<?php echo $usuariomodi->nombre ?>">
                            </div>

                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="ejemplo@correo.com" aria-label="rfc" aria-describedby="basic-addon2" name="email" value="<?php echo $usuariomodi->email ?>">
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" id="contrasena" class="form-control" placeholder=<?php echo 'Contraseña' ?> aria-label="rfc" aria-describedby="basic-addon2" name="contrasena" value="<?php echo $usuariomodi->constrasena ?>">
                            </div>
                            <label id="letrero-error" for="basic-url" class="form-label" style="color: red; display: none;">Las contrasenas no coinciden</label>
                            <div class="input-group mb-3">
                                <input type="password" id="conf-contra" oninput="compararContrasenas()" class="form-control" placeholder="<?php echo 'Confirmación de contraseña' ?>" aria-label="rfc" aria-describedby="basic-addon2" name="conf-contrasena">
                            </div>

                            <div class="dt-buttons">
                                <button type="submit" class="dt-button btn btn-primary _effect--ripple waves-effect waves-light" tabindex="0" aria-controls="invoice-list">
                                    <span>Agregar</span>
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
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const formUsuario = document.getElementById("agregar-usuario");

        formUsuario.addEventListener("submit", function(e) {
            e.preventDefault();
            var datosUsuario = new FormData(formUsuario);
            if(<?php echo $editando?> ==1){
                datosUsuario.append('accion', 'modificar');
                datosUsuario.append('id','<?php echo $_GET['id'] ?>');
            }else{
                datosUsuario.append('accion', 'agregar');
            }
            fetch("api/apiUsuarios.php", {
                    method: "POST",
                    body: datosUsuario,
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
                        window.location.href = "Usuarios.php";
                    }, 3000);

                });
        });
    </script>
    <script>
        function compararContrasenas(){
            console.log('hola');
            var contrasena=document.getElementById("contrasena").value;
            var confContra=document.getElementById("conf-contra").value;
            var error=document.getElementById("letrero-error");
            if(contrasena===confContra){
               error.style.display='none';
            }else{
                error.style.display='block';
            }
        }

    </script>

</body>

</html>
