<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$condicion = "";
$orden = "";
$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;

if(!$idEmpresa)
{
return http_response_code(400);
}*/

/*$cadena=(isset($_GET['cadena']))? mysqli_real_escape_string($con, (string)$_GET['cadena']) : false;
$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : false;

// Condición de selecci
if($cadena){
$condicion = "and cadena like '" . ltrim(rtrim($cadena)) . "%'";
}

// Condicion de ordenamiento
switch (intval($ord)){
case 0:
$orden = " order by e.nombreempresa asc,  c.cadena asc";
break;
case 1:
$orden = " order by e.nombreempresa asc,  c.cadena asc ";
break;
case 2:
$orden = " order by e.nombreempresa desc,  c.cadena asc ";
break;
case 3:
$orden = " order by c.cadena asc";
break;
case 4:
$orden = " order by c.cadena desc";
break;
}*/

//$usuario = [];
$sql = "Select tmp.* from tmp_prueba tmp where not cadena is null " . $condicion . $orden . ";";
//echo($sql);
//echo ('<br>');
if ($result = mysqli_query($con, $sql)) {
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $cat[$i]['cadena'] = $row['Cadena'];
        $cat[$i]['elobjetivo'] = $row['El objetivo'];
        $cat[$i]['ctacheckin'] = $row['Cta Checkin'];
        $cat[$i]['ctacheckout'] = $row['Cta Checkout'];
        $i++;
    }

    echo json_encode($cat);
} else {
    echo json_encode($cat = ["sql" => $sql.' ; '. $con -> error]);
//  http_response_code(404);
}
