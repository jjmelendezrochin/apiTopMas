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

$idpromocion=(isset($_GET['idpromocion']))? mysqli_real_escape_string($con, (string)$_GET['idpromocion']) : false;
$promocion=(isset($_GET['promocion']))? mysqli_real_escape_string($con, (string)$_GET['promocion']) : '';
//$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : -2;

// Condición de selecci
if(!$idpromocion){
 return http_response_code(404);
}

/*Verifica la lista de formatos asignados para la promocion solicitada*/
$sql = "Select pf.idformato from promocion_formato pf where pf.idestatus = 1 and pf.idpromocion = '" . $idpromocion . "';";

$formatos = "";

if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
	 $formatos .= "'" . $row['idformato'] . "',";
  }
}

$formatos = substr($formatos,0,strlen($formatos)-1);

if($formatos != ""){
	$formatos = "and not f.idformato in(" . $formatos . ")";
}

//$usuario = [];
$sql = "Select f.*,c.nombrecorto,DATE_FORMAT(f.fda,'%d/%m/%Y') as fda1,
DATE_FORMAT(f.fdc,'%d/%m/%Y') as fdc1,
(Select cfg.valor from configuracion cfg where cfg.idconf = 2) as idempresa 
from cat_formato f 
left join cat_cadena c on f.idcadena = c.idcadena where f.idestatus = 1 " . $formatos . " and (f.formato like '" . $promocion . "%' or 
f.formato like '%" . $promocion . "' or 
f.formato like '%" . $promocion . "%' or 
f.formato like '" . $promocion . "') 
order by f.idformato asc;";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idformato']      =   $row['idformato'];
    $cat[$i]['idempresa']      =   $row['idempresa'];
    $cat[$i]['idcadena']   =   $row['idcadena'];
    $cat[$i]['formato']        =   $row['formato'];
	$cat[$i]['uda']           =   $row['uda'];
    $cat[$i]['fda']           =   $row['fda'];
    $cat[$i]['fda_m']         =   $row['fda1'];
    $cat[$i]['udc']           =   $row['udc'];
    $cat[$i]['fdc']           =   $row['fdc'];
    $cat[$i]['fdc_m']         =   $row['fdc1'];
    $cat[$i]['idestatus']     =   $row['idestatus'];    
    $cat[$i]['cadena']     =   $row['nombrecorto'];    
    $i++;
  }

  echo json_encode($cat);
}
else
{
	$cat[]['sql'] = $sql;
  echo json_encode($cat);
//  http_response_code(404);
}
?>