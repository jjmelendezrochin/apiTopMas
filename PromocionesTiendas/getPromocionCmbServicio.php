<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

date_default_timezone_set('America/Mexico_City');

$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;

$condicion = "(Select valor from configuracion cfg where cfg.idconf = 2)";
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
if(intval($idEmpresa) > 0){
	$condicion = $idEmpresa;
}

$sql = "select distinct p.idpromocion,p.nombre from promocion p where p.idestatus=1 and p.idempresa = " . $condicion . " order by nombre;";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idpromocion']      =   $row['idpromocion'];
    $cat[$i]['nombre']     =   strtoupper($row['nombre']);
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>