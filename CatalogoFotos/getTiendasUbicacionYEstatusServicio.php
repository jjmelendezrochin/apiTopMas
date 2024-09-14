<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

date_default_timezone_set('America/Mexico_City');

$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;

$vw = "";
$condicion = "";
$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/

//$fecha=date('Y/m/d');

/*if($cadena){
$condicion = "and cadena like'{$cadena}%'";
}*/

//$usuario = [];
if(intval($idEmpresa) > 0){
	$vw = "_" . $idEmpresa;
}

$sql = "SELECT * FROM `vw_visitas_tiendas_hoy" .  $vw . "`";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idpromotor']      =   $row['idpromotor'];
    $cat[$i]['idruta']      =   $row['idruta'];
    $cat[$i]['nombre']     =   $row['nombre'];
    $cat[$i]['Tienda']     =   $row['Tienda'];
    $cat[$i]['latitud']     =   $row['latitud'];
    $cat[$i]['longitud']        =   $row['longitud'];
    $cat[$i]['cont1']           =   $row['cont1'];
    $cat[$i]['cont2']           =   $row['cont2'];
    $cat[$i]['cuenta']           =   $row['cuenta'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>