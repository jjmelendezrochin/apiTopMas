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

$idcadena=(isset($_GET['idcadena']))? mysqli_real_escape_string($con, (int)$_GET['idcadena']) : false;
/*$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : 0;

if($cadena){
$condicion = "and formato like'" . ltrim(rtrim($cadena)) . "%'";
}

// Condicion de ordenamiento
switch (intval($ord)){
  case 0:
      $orden = " order by e.nombreempresa asc,  c.cadena asc,  f.formato asc";
      break;
  case 1:
      $orden = " order by e.nombreempresa asc,  c.cadena asc,  f.formato asc ";
      break;
  case 2:
      $orden = " order by e.nombreempresa desc,  c.cadena asc,  f.formato asc ";
      break;
  case 3:
      $orden = " order by c.cadena asc";
      break;
  case 4:
      $orden = " order by c.cadena desc";
      break;   
  case 5:
      $orden = " order by f.formato asc";
      break;
  case 6:
      $orden = " order by f.formato desc";
      break;  
}*/

//$usuario = [];
$sql = "SELECT f.*,c.cadena,date_format(f.fda,'%d/%m/%Y') as fda1,date_format(f.fdc,'%d/%m/%Y') as fdc1 FROM cat_formato f left join cat_cadena c on f.idcadena=c.idcadena where f.idestatus=1 and c.idcadena='{$idcadena}';";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idformato']      =   $row['idformato'];
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
    $cat[$i]['cadena'] =   $row['cadena'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>