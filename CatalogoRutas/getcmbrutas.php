<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$idEmpresa = (isset($_GET['idEmpresa']) && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;

$condicion = "";
$cat = [];

$sql = "Select distinct r.idruta, r.Tienda
from cat_rutas r 
left join cat_formato cf on cf.idformato = r.idformato
left join cat_cadena cc on cc.idcadena = cf.idcadena
left join empresa_cadena ec on ec.idcadena = cc.idcadena
where r.idEstatus = 1 and ec.idempresa = " . idEmpresa . " order by 2 asc";

if ($result = mysqli_query($con, $sql)) {
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $cat[$i]["idruta"] = $row["idruta"];
        $cat[$i]["Tienda"] = $row["Tienda"];
        $i++;
    }
    echo json_encode($cat);
} else {
    http_response_code(404);
}
