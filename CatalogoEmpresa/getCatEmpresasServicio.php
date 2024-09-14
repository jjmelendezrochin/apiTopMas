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

$empresa=(isset($_GET['empresa']))? mysqli_real_escape_string($con, (string)$_GET['empresa']) : false;
$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : 0;

if($empresa){
$condicion = " and (e.nombreempresa like ltrim(rtrim('{$empresa}%')))";
}


// Condicion de ordenamiento
switch (intval($ord)){
    case 0:
        $orden = " order by e.nombreempresa asc";
        break;
    case 1:
        $orden = " order by e.nombreempresa asc ";
        break;
    case 2:
        $orden = " order by e.nombreempresa desc";
        break;
    case 3:
         $orden = " order by e.contacto asc";
        break;
    case 4:
        $orden = " order by e.contacto desc";
        break;
}

//$usuario = [];
$sql = "SELECT e.*, DATE_FORMAT(e.fda,'%d/%m/%Y') as fda1, DATE_FORMAT(e.fdc,'%d/%m/%Y') as fdc1 from cat_empresa e 
where idestatus = 1 " . $condicion  . $orden ;
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idempresa']      =   $row['idempresa'];
    $cat[$i]['nombreempresa']     =   $row['nombreempresa'];
    $cat[$i]['contacto']        =   $row['contacto'];
    $cat[$i]['alias']        =    $row['alias'];	
    $cat[$i]['uda']           =   $row['uda'];
    $cat[$i]['fda']           =   $row['fda'];
    $cat[$i]['fda_m']         =   $row['fda1'];
    $cat[$i]['udc']           =   $row['udc'];
    $cat[$i]['fdc']           =   $row['fdc'];
    $cat[$i]['fdc_m']         =   $row['fdc1'];
    $cat[$i]['idestatus']     =   $row['idestatus'];
    if(intval($row['idestatus']) == 0){
      $cat[$i]['estatus'] =  "Inactivo";
    }else if(intval($row['idestatus']) == 1){
      $cat[$i]['estatus'] =  "Activo";
    }
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>