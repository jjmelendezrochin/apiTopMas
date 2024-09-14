<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

date_default_timezone_set('America/Mexico_City');

$condicion = "";
$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/

$fecha=date('Y/m/d');

/*if($cadena){
$condicion = "and cadena like'{$cadena}%'";
}*/

//$usuario = [];
$sql = "select * from cat_operacion where not idoperacion = 2 order by idoperacion asc;";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idoperacion']      =   $row['idoperacion'];
    $cat[$i]['descripcion']     =   $row['descripcion'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>