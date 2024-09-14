<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$condicion = "";
$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/

$idpromotor=(isset($_GET['idpromotor']))? mysqli_real_escape_string($con, (int)$_GET['idpromotor']) : false;
$dia=(isset($_GET['dia']))? mysqli_real_escape_string($con, (string)$_GET['dia']) : false;

/*if($campos){
$condicion = "and p.nombre like'{$campos}%' or p.apellidos like'{$campos}%'";
}*/

//$usuario = [];
$sql = "select (count(*)+1) as total from rutas_promotor_dias 
where idpromotor='{$idpromotor}' and $dia=true;";
//echo($sql);
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['total']      =   $row['total'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>