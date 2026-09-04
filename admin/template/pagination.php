<?php

if (!function_exists('renderBerryPagination')) {
    function renderBerryPagination($paginaActual, $totalPaginas, $totalRegistros, $porPagina, $parametro = 'pagina')
    {
        $paginaActual = max(1, (int) $paginaActual);
        $totalPaginas = max(1, (int) $totalPaginas);
        $totalRegistros = max(0, (int) $totalRegistros);
        $porPagina = max(1, (int) $porPagina);
        $desde = $totalRegistros === 0 ? 0 : (($paginaActual - 1) * $porPagina) + 1;
        $hasta = min($paginaActual * $porPagina, $totalRegistros);

        $crearUrl = static function ($pagina) use ($parametro) {
            $parametros = $_GET;
            $parametros[$parametro] = max(1, (int) $pagina);
            return '?' . htmlspecialchars(http_build_query($parametros), ENT_QUOTES, 'UTF-8');
        };

        $paginasVisibles = [1, $totalPaginas];
        for ($pagina = max(1, $paginaActual - 2); $pagina <= min($totalPaginas, $paginaActual + 2); $pagina++) {
            $paginasVisibles[] = $pagina;
        }
        $paginasVisibles = array_values(array_unique($paginasVisibles));
        sort($paginasVisibles);
        ?>
        <nav class="berry-pagination" aria-label="Navegación de resultados">
            <p class="berry-pagination__summary">
                Mostrando <strong><?php echo $desde; ?>–<?php echo $hasta; ?></strong> de
                <strong><?php echo $totalRegistros; ?></strong> registros
            </p>

            <?php if ($totalPaginas > 1) { ?>
                <div class="berry-pagination__pages">
                    <?php if ($paginaActual > 1) { ?>
                        <a class="berry-pagination__control" href="<?php echo $crearUrl($paginaActual - 1); ?>" aria-label="Página anterior">‹</a>
                    <?php } else { ?>
                        <span class="berry-pagination__control is-disabled" aria-hidden="true">‹</span>
                    <?php } ?>

                    <?php
                    $paginaAnterior = 0;
                    foreach ($paginasVisibles as $pagina) {
                        if ($paginaAnterior && $pagina > $paginaAnterior + 1) {
                            echo '<span class="berry-pagination__ellipsis" aria-hidden="true">…</span>';
                        }
                        if ($pagina === $paginaActual) {
                            echo '<span class="berry-pagination__page is-current" aria-current="page">' . $pagina . '</span>';
                        } else {
                            echo '<a class="berry-pagination__page" href="' . $crearUrl($pagina) . '">' . $pagina . '</a>';
                        }
                        $paginaAnterior = $pagina;
                    }
                    ?>

                    <?php if ($paginaActual < $totalPaginas) { ?>
                        <a class="berry-pagination__control" href="<?php echo $crearUrl($paginaActual + 1); ?>" aria-label="Página siguiente">›</a>
                    <?php } else { ?>
                        <span class="berry-pagination__control is-disabled" aria-hidden="true">›</span>
                    <?php } ?>
                </div>
            <?php } ?>
        </nav>
        <?php
    }
}
