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

$cadena=(isset($_GET['cadena']))? mysqli_real_escape_string($con, (string)$_GET['cadena']) : false;
$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : 0;

if($cadena){
$condicion = " and (cz.letrazona like '" . ltrim(rtrim($cadena . "%")) . "' or cz.descripcion like '" . ltrim(rtrim($cadena .'%')) . "' 
or cz.estados like '" . ltrim(rtrim($cadena.'%')) . "') ";
}


// Condicion de ordenamiento
switch (intval($ord)){
    case 0:
        $orden = " order by cz.letrazona asc";
        break;
    case 1:
        $orden = " order by cz.letrazona asc ";
        break;
    case 2:
        $orden = " order by cz.letrazona desc";
        break;
    case 3:
         $orden = " order by cz.estados asc";
        break;
    case 4:
        $orden = " order by cz.estados desc";
        break;
}

//$usuario = [];
$sql = "SELECT cz.*, DATE_FORMAT(cz.fda,'%d/%m/%Y') as fda1, DATE_FORMAT(cz.fdc,'%d/%m/%Y') as fdc1 from cat_zonas cz 
where idestatus = 1 " . $condicion  . $orden ;
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idzona']      =   $row['idzona'];
    $cat[$i]['letrazona']     =   $row['letrazona'];
    $cat[$i]['descripcion']        =   $row['descripcion'];
    $cat[$i]['estados']        =   $row['estados'];
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
	$cat[]['letrazona'] = $sql;
	echo json_encode($cat);
  //http_response_code(404);
}
?>