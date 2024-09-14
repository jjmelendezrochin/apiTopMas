<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

date_default_timezone_set('America/Mexico_City');

$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;

$condicion = "(Select cfg.valor from configuracion cfg where cfg.idconf = 2)";
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

$sql = "select distinct idpromotor,latitud,longitud,idusuario from photos where Cast(FechaHora as date)=date_format('{$fecha}','%Y/%m/%d') 
and (Select count(*) from cat_promotor p where p.idpromotor = photos.idpromotor and p.idempresa = " . $condicion . " limit 1) > 0 
order by 1;";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idpromotor']      =   $row['idpromotor'];
    $cat[$i]['latitud']     =   $row['latitud'];
    $cat[$i]['longitud']        =   $row['longitud'];
    $cat[$i]['idusuario']           =   $row['idusuario'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>