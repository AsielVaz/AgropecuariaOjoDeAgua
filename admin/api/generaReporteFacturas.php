<?php
 header('Content-Type: application/vnd.ms-excel');
 header('Content-Disposition: attachment; filename="FACTURAS_SEMANA.xls"');
include_once 'adminFacturas.php';
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



function acondicionarLink($link)
{
    $link = str_replace("/home/iohanes/agropecuariaojodeagua.com/admin/", "", $link);
    $link = str_replace(" ", "%20", $link);
    return $link;
}

function acortaTexto($texto)
{
    if (strlen($texto) > 10) {
        $texto = substr($texto, 0, 10) . "...";
    }
    return $texto;
}



?>

<table id="facturas" class="table dt-table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="invoice-list_info" border="1px">
    <thead>
        <tr role="row">
            <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">ID FOLIO</th>
            <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">Total con IVA</th>
            <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">Proveedor</th>

            <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">Fecha ingreso</th>
            <th class="sorting" tabindex="0" aria-controls="invoice-list" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 23px;">Fecha vencimiento</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($facturas as $factura) {
        ?>



            <tr role="row">
                <td>A000<?php echo $factura->id ?> </td>
                <td>$<?php echo number_format($factura->total_con_iva, 2) ?> </td>
                <td><?php echo  acortaTexto($factura->nombre_proveedor) ?> </td>

                <td><span class="inv-date"><?php echo formatearFecha($factura->fecha_de_ingreso) ?> </span></td>



                <td><span class="inv-date"><?php echo formatearFecha($factura->fecha_pago) ?> </span></td>


              
            </tr>
        <?php }
        ?>
    </tbody>
</table>