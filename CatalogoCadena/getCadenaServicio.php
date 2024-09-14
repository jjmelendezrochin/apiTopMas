<?php
/**
 * Regresa el usuario.
 */
require '../database.php';
require '../config.php';

$idEmpresa = (isset($_GET['idEmpresa']) && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;


$condicion = "";

$cat = [];
// Obteniendo la empresa
$idEmpresa = (isset($_GET['idEmpresa']) && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;


$cadena=(isset($_GET['cadena']))? mysqli_real_escape_string($con, (string)$_GET['cadena']) : false;
$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : false;

// Condición de selecci
if($cadena){
    $condicion = "and cadena like '" . ltrim(rtrim($cadena)) . "%'";
}

// Condicion de ordenamiento
switch (intval($ord)){
    case 0:
         $orden = " order by c.cadena asc";
        break;
    case 1:
        $orden = " order by c.cadena desc";
        break;  
}

//$usuario = [];
//if(intval($idEmpresa) > 0){
//	$condicion1 = $idEmpresa;
//}

$sql = "Select distinct c.*,DATE_FORMAT(c.fda,'%d/%m/%Y') as fda1,DATE_FORMAT(c.fdc,'%d/%m/%Y') as fdc1,
ce.idempresa,
ce.nombreempresa
from cat_cadena c 
left join empresa_cadena ec on c.idcadena = ec.idcadena
left join cat_empresa ce on ec.idempresa = ce.idempresa
where c.idestatus = 1 and ce.idempresa = " . $idEmpresa . " " . $condicion .  $orden . ";";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idcadena']      =   $row['idcadena'];
    $cat[$i]['idempresa']     =   $row['idempresa'];
    $cat[$i]['nombrecorto']   =   $row['nombrecorto'];
    $cat[$i]['cadena']        =   $row['cadena'];
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
    $i++;
  }
    //$cat[]['sql'] = $sql;
  echo json_encode($cat);
}
else
{
  echo json_encode($cat=["sql"=>$sql]);
//  http_response_code(404);
}
?>