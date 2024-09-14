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
$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : -2;

// Condición de selecci
if(!$idpromocion){
 return http_response_code(404);
 }

//$usuario = [];
$sql = "Select pf.*,DATE_FORMAT(pf.fda,'%d/%m/%Y') as fda1,
DATE_FORMAT(pf.fdc,'%d/%m/%Y') as fdc1,
p.nombre as promocion,e.nombreempresa,c.nombrecorto as cadena,f.formato from promocion_formato pf 
left join promocion p on pf.idpromocion = p.idpromocion
left join cat_empresa e on pf.idempresa = e.idempresa
left join cat_cadena c on pf.idcadena = c.idcadena
left join cat_formato f on pf.idformato = f.idformato
where pf.idestatus = 1 and pf.idpromocion = '" . $idpromocion . "' order by pf.idpromocion asc;";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idpromocionformato']      =   $row['idpromocionformato'];
    $cat[$i]['idpromocion']      =   $row['idpromocion'];
    $cat[$i]['idempresa']     =   $row['idempresa'];
    $cat[$i]['idcadena']   =   $row['idcadena'];
    $cat[$i]['idformato']        =   $row['idformato'];
	$cat[$i]['uda']           =   $row['uda'];
    $cat[$i]['fda']           =   $row['fda'];
    $cat[$i]['fda_m']         =   $row['fda1'];
    $cat[$i]['udc']           =   $row['udc'];
    $cat[$i]['fdc']           =   $row['fdc'];
    $cat[$i]['fdc_m']         =   $row['fdc1'];
    $cat[$i]['idestatus']     =   $row['idestatus'];    
    $cat[$i]['promocion'] =   $row['promocion'];
    $cat[$i]['nombreempresa'] =   $row['nombreempresa'];
    $cat[$i]['cadena'] =   $row['cadena'];
    $cat[$i]['formato'] =   $row['formato'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  echo json_encode($cat=["sql"=>$sql]);
//  http_response_code(404);
}
?>