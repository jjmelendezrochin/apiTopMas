<?php
/**
 * Regresa el usuario.
 */
require '../database.php';
require '../config.php';

$condicion = "";
$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/

$idempresa=(isset($_GET['idempresa']))? mysqli_real_escape_string($con, (string)$_GET['idempresa']) : 1;
$cadena=(isset($_GET['cadena']))? mysqli_real_escape_string($con, (string)$_GET['cadena']) : false;
$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : 0;

if($cadena){
$condicion = "and formato like'" . ltrim(rtrim($cadena)) . "%'";
}

// Condicion de ordenamiento
switch (intval($ord)){
  case 0:
      $orden = " order by c.cadena asc";
      break;
  case 1:
      $orden = " order by c.cadena desc";
      break;   
  case 2:
      $orden = " order by f.formato asc";
      break;
  case 3:
      $orden = " order by f.formato desc";
      break;  
}

//$usuario = [];
$sql = "Select distinct f.*,
ce.idempresa,
ce.nombreempresa,
c.nombrecorto as cadena,DATE_FORMAT(c.fda,'%d/%m/%Y') as fda1,DATE_FORMAT(c.fdc,'%d/%m/%Y') as fdc1
from cat_formato f 
inner join cat_cadena c on f.idcadena = c.idcadena
inner join empresa_cadena ec on f.idcadena = ec.idcadena
inner join cat_empresa ce on ec.idempresa = ce.idempresa 
where f.idestatus = 1 and ec.idempresa = " . $idempresa . " " . $condicion . $orden .";";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idformato']      =   $row['idformato'];
    $cat[$i]['idempresa']     =   $row['idempresa'];
    $cat[$i]['idcadena']     =   $row['idcadena'];
    $cat[$i]['formato']        =   $row['formato'];
    $cat[$i]['uda']           =   $row['uda'];
    $cat[$i]['fda']           =   $row['fda'];
    $cat[$i]['fda_m']         =   $row['fda1'];
    $cat[$i]['udc']           =   $row['udc'];
    $cat[$i]['fdc']           =   $row['fdc'];
    $cat[$i]['fdc_m']         =   $row['fdc1'];
    $cat[$i]['idestatus']     =   $row['idestatus'];
    if(intval($row['idestatus']) == 0){
      $cat[$i]['estatus'] =  "Inactivo";
      $cat[$i]['btn_estilo']="i_estatus";
    }else if(intval($row['idestatus']) == 1){
      $cat[$i]['estatus'] =  "Activo";
      $cat[$i]['btn_estilo']="a_estatus";
    }
    $cat[$i]['nombreempresa'] =   $row['nombreempresa'];
    $cat[$i]['cadena'] =   $row['cadena'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
	$cat['sql'] = $sql;
	echo json_encode($cat);
  //http_response_code(404);
}
?>