<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$condicion_rutas="";
$condicion = "";
$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/

$cadena=(isset($_GET['cadena']))? mysqli_real_escape_string($con, (string)$_GET['cadena']) : false;
$idpromotor=(isset($_GET['idpromotor']))? mysqli_real_escape_string($con, (int)$_GET['idpromotor']) : false;

$sql0="Select idruta from rutas_promotor where idpromotor='{$idpromotor}' and idestatus=1";

$idrutas='(';

if($result = mysqli_query($con,$sql0))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $idrutas .= "'".$row['idruta']."',";
  }
  $idrutas = substr_replace($idrutas,'',strlen($idrutas)-1,strlen($idrutas));
  $idrutas .= ")";
}

if($idrutas != ")"){
$condicion_rutas="and not cr.idruta in{$idrutas}";
}

if($cadena){
$condicion = "where (cr.Tienda like '{$cadena}%' or cr.direccioncompleta like '{$cadena}%' "
. "or concat(cc.nombrecorto, ' ', cf.formato, ' ' , cr.Tienda) like '{$cadena}%')  {$condicion_rutas}";
}

//$usuario = [];
$sql = "Select cr.idruta, cr.determinante,   cr.tienda as Tienda, cr.direccioncompleta as Direccion,
cr.determinante, concat(cc.nombrecorto, ' ', cf.formato, ' ' , cr.Tienda) as Tienda1
from cat_rutas cr 
left join cat_cadena ca on cr.idcadena = ca.idcadena 
left join cat_cadena cc on cr.idcadena = cc.idcadena 
left join cat_formato cf on cr.idformato = cf.idformato " .
$condicion . " order by 1;";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idruta'] = $row['idruta'];
    $cat[$i]['determinante'] = $row['determinante'];
    $cat[$i]['Tienda']      =   $row['Tienda'];
    $cat[$i]['Direccion']     =   $row['Direccion'];
    $cat[$i]['Tienda1']      =   $row['Tienda1'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>