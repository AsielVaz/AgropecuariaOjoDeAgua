<?php
require_once dirname(__DIR__) . '/auth.php';

$paginaActual = basename($_SERVER['PHP_SELF'] ?? 'index.php');
$paginasFacturas = [
    'index.php',
    'facturas.php',
    'facturas-pagadas.php',
    'facturas-eliminadas.php',
    'facturas-vencidas.php',
    'agregar-pago.php',
];
$facturasActivas = in_array($paginaActual, $paginasFacturas, true);

if (!function_exists('claseMenuActiva')) {
    function claseMenuActiva($pagina, $paginaActual)
    {
        return $pagina === $paginaActual ? ' active' : '';
    }
}
?>

<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar" aria-label="Navegación principal">
        <div class="berry-brand">
            <a href="index.php" class="berry-brand__link">
                <span class="berry-brand__mark"><img src="../logo.png" alt="Agropecuaria Ojo de Agua"></span>
                <span class="berry-brand__copy"><strong>AOA</strong><small>Berry Fields</small></span>
            </a>
            <button type="button" class="sidebarCollapse berry-sidebar-close" aria-label="Cerrar navegación">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6" /></svg>
            </button>
        </div>

        <div class="berry-orchard-card">
            <span class="berry-orchard-card__icon">●</span>
            <div><small>Panel de cultivo</small><strong>Ojo de Agua</strong></div>
            <span class="berry-orchard-card__leaf">⌁</span>
        </div>

        <div class="berry-menu-label">Resumen</div>
        <ul class="list-unstyled menu-categories">
            <li class="menu<?php echo claseMenuActiva('index.php', $paginaActual); ?>">
                <a href="index.php" class="dropdown-toggle">
                    <span class="berry-nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 11.5 12 4l9 7.5M5 10v10h14V10M9 20v-6h6v6" /></svg></span>
                    <span>Inicio</span>
                </a>
            </li>
        </ul>

        <div class="berry-menu-label">Gestión</div>
        <ul class="list-unstyled menu-categories" id="berryNavigation">
            <li class="menu berry-has-submenu<?php echo $facturasActivas ? ' active' : ''; ?>">
                <a href="#berryInvoices" data-bs-toggle="collapse" aria-expanded="<?php echo $facturasActivas ? 'true' : 'false'; ?>" class="dropdown-toggle<?php echo $facturasActivas ? '' : ' collapsed'; ?>">
                    <span class="berry-nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3h9l4 4v14H6zM14 3v5h5M9 13h7M9 17h5" /></svg></span>
                    <span>Facturas</span>
                    <svg class="berry-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6" /></svg>
                </a>
                <ul class="collapse submenu list-unstyled<?php echo $facturasActivas ? ' show' : ''; ?>" id="berryInvoices" data-bs-parent="#berryNavigation">
                    <li class="<?php echo claseMenuActiva('index.php', $paginaActual); ?>"><a href="index.php">Pago esta semana</a></li>
                    <li class="<?php echo claseMenuActiva('facturas.php', $paginaActual); ?>"><a href="facturas.php">Pagos programados</a></li>
                    <li class="<?php echo claseMenuActiva('facturas-pagadas.php', $paginaActual); ?>"><a href="facturas-pagadas.php">Pagadas</a></li>
                    <li class="<?php echo claseMenuActiva('facturas-eliminadas.php', $paginaActual); ?>"><a href="facturas-eliminadas.php">Eliminadas</a></li>
                </ul>
            </li>

            <li class="menu<?php echo claseMenuActiva('Provedores.php', $paginaActual) . claseMenuActiva('agregar-provedor.php', $paginaActual); ?>">
                <a href="Provedores.php" class="dropdown-toggle">
                    <span class="berry-nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7zM7 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4ZM18 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" /></svg></span>
                    <span>Proveedores</span>
                </a>
            </li>

            <li class="menu<?php echo claseMenuActiva('Usuarios.php', $paginaActual) . claseMenuActiva('agregar-usuario.php', $paginaActual); ?>">
                <a href="Usuarios.php" class="dropdown-toggle">
                    <span class="berry-nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2M9.5 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM17 8h4M19 6v4" /></svg></span>
                    <span>Usuarios</span>
                </a>
            </li>

            <li class="menu<?php echo claseMenuActiva('Evidencia-pagos.php', $paginaActual); ?>">
                <a href="Evidencia-pagos.php" class="dropdown-toggle">
                    <span class="berry-nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v14H4zM8 9h8M8 13h5M8 17h3" /></svg></span>
                    <span>Evidencias de pago</span>
                </a>
            </li>

            <li class="menu<?php echo claseMenuActiva('log-fac.php', $paginaActual); ?>">
                <a href="log-fac.php" class="dropdown-toggle">
                    <span class="berry-nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v14H4zM4 8l8 6 8-6" /></svg></span>
                    <span>Log de ingresos</span>
                </a>
            </li>
        </ul>

        <div class="berry-sidebar-footer">
            <span class="berry-sidebar-footer__berries">● ● ●</span>
            <p><strong>Huerta conectada</strong><br><small>Gestión AOA</small></p>
        </div>
    </nav>
</div>
