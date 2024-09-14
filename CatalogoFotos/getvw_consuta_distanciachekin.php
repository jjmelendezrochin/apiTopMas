<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

// Obteniendo la empresa
$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;

$vw = "";
$cat = [];

if(intval($idEmpresa) > 0){
	$vw = "_" . $idEmpresa;
}

$sql = "Select Nombre, Tienda,  cast(Distancia_m as signed) as Distancia, accion as Accion
from vw_consulta_distanciachekin_chekout_0
where cast(FechaHora as date) = cast(current_timestamp() as date)
and cast(Distancia_m as signed) >= 500
and idempresa = " . $idEmpresa . "
order by cast(Distancia_m as signed) desc;";

if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['Nombre']      =   $row['Nombre'];
    $cat[$i]['Tienda']      =   $row['Tienda'];
    $cat[$i]['distancia']     =   $row['Distancia'];
    $cat[$i]['accion']     =   $row['Accion'];
    $i++;
  }
  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>