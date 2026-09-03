<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION["usuario_id"])) {
    if (!headers_sent()) {
        header('Location: login.php');
    } else {
        echo "<script>window.location='login.php'</script>";
    }
    exit;
}

include_once 'api/adminUsuarios.php';
$adminUsuarios = new AdministradorUsuario();
$usuario = $adminUsuarios->dameUsuarioId($_SESSION["usuario_id"]);
$nombreUsuario = $usuario->nombre ?? 'Usuario AOA';
$inicialUsuario = function_exists('mb_substr')
    ? mb_strtoupper(mb_substr($nombreUsuario, 0, 1, 'UTF-8'), 'UTF-8')
    : strtoupper(substr($nombreUsuario, 0, 1));
?>

<div class="header-container container-xxl">
    <header class="header navbar berry-navbar">
        <div class="berry-navbar__left">
            <button type="button" class="sidebarCollapse berry-icon-button" aria-label="Abrir o cerrar navegación">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" /></svg>
            </button>
            <div class="berry-page-heading">
                <span>AOA · Control agrícola</span>
                <strong>Administración de la huerta</strong>
            </div>
        </div>

        <div class="berry-navbar__right">
            <div class="berry-season-status" title="Sistema disponible">
                <span class="berry-season-status__dot"></span>
                Temporada activa
            </div>

            <div class="dropdown berry-user-menu">
                <button class="berry-user-button" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="berry-user-avatar"><?php echo htmlspecialchars($inicialUsuario, ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="berry-user-copy">
                        <strong><?php echo htmlspecialchars($nombreUsuario, ENT_QUOTES, 'UTF-8'); ?></strong>
                        <small>Administrador</small>
                    </span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5" /></svg>
                </button>
                <div class="dropdown-menu dropdown-menu-end berry-user-dropdown">
                    <div class="berry-user-dropdown__head">
                        <span class="berry-user-avatar berry-user-avatar--large"><?php echo htmlspecialchars($inicialUsuario, ENT_QUOTES, 'UTF-8'); ?></span>
                        <div>
                            <strong><?php echo htmlspecialchars($nombreUsuario, ENT_QUOTES, 'UTF-8'); ?></strong>
                            <small>Cuenta administrativa</small>
                        </div>
                    </div>
                    <a class="dropdown-item berry-logout" href="login.php">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3M14 4h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4" /></svg>
                        Cerrar sesión
                    </a>
                </div>
            </div>
        </div>
    </header>
</div>
