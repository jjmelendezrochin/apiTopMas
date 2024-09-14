<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$condicion_promotores="";
$condicion = "";
$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/

$cadena=(isset($_GET['cadena']))? mysqli_real_escape_string($con, (string)$_GET['cadena']) : false;
$idsupervisor=(isset($_GET['idsupervisor']))? mysqli_real_escape_string($con, (int)$_GET['idsupervisor']) : false;

$sql0="Select idpromotor from promotores_supervisor where idsupervisor='{$idsupervisor}' and idestatus=1";

$idpromotores='(';

if($result = mysqli_query($con,$sql0))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $idpromotores .= "'".$row['idpromotor']."',";
  }
  $idpromotores = substr_replace($idpromotores,'',strlen($idpromotores)-1,strlen($idpromotores));
  $idpromotores .= ")";
}

if($idpromotores != ")"){
$condicion_promotores="and not cp.idpromotor in{$idpromotores}";
}

if($cadena){
$condicion = "where cp.idestatus = '1' and concat(cp.nombre,' ',cp.apellidos) like '{$cadena}%'  {$condicion_promotores}";
}

//$usuario = [];
$sql = "SELECT cp.idpromotor,concat(cp.nombre,' ',cp.apellidos) as nombre_completo FROM cat_promotor cp " .
$condicion . " order by 1;";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idpromotor'] = $row['idpromotor'];
    $cat[$i]['nombre_completo'] = $row['nombre_completo'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>