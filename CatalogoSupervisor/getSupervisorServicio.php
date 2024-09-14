<?php
/**
 * Regresa el usuario.
 */
require '../database.php';
require '../config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


$condicion = "";
$cat = [];
// Obteniendo la empresa
$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}

$campos=(isset($_GET['campos']))? mysqli_real_escape_string($con, (string)$_GET['campos']) : false;
$dia=(isset($_GET['dia']))? mysqli_real_escape_string($con, (string)$_GET['dia']) : false;
$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : false;

if($campos){
$condicion .= ($campos != ' ')? " and (p.nombre like'{$campos}%' or p.apellidos like'{$campos}%')":"";
}
if($dia){
  if($dia != "0"){
    switch(intval($dia)){
 case 1:
  $dia = "lunes";
 break;
 case 2:
  $dia = "martes";
 break;
 case 3:
  $dia = "miercoles";
 break;
 case 4:
  $dia = "jueves";
 break;
 case 5:
  $dia = "viernes";
 break;
 case 6:
  $dia = "sabado";
 break;
 case 7:
  $dia = "domingo";
 break;
    }
   $condicion .= ($condicion == '')? " and rpd.{$dia} = 1":" and rpd.{$dia} = 1";
  }
}

// Condicion de ordenamiento
switch (intval($ord)){
  case 0:
      $orden = " order by e.nombreempresa asc,  p.nombre asc";
      break;
  case 1:
      $orden = " order by e.nombreempresa asc,  p.nombre asc ";
      break;
  case 2:
      $orden = " order by e.nombreempresa desc,  p.nombre asc ";
      break;
  case 3:
       $orden = " order by p.nombre asc";
      break;
  case 4:
      $orden = " order by p.nombre desc";
      break;
  case 5:
      $orden = "order by p.apellidos asc";
      break;
  case 6:
      $orden = "order by p.apellidos desc";
      break;
  case 7:
      $orden = "order by p.idusuario asc";
      break;
  case 8:
      $orden = " order by p.idusuario desc";
      break;    
  case 9:
      $orden = " order by zona asc";
      break;
  case 10:
      $orden = " order by zona desc";
      break;
}

//$usuario = [];
$sql = "Select distinct p.*,DATE_FORMAT(p.fda,'%d/%m/%Y') as fda1,DATE_FORMAT(p.fdc,'%d/%m/%Y') as fdc1, e.`nombreempresa`,concat(p.`nombre`,' ',p.`apellidos`) as nombrecompleto_s,cz.estados as zona 
from cat_promotor p
left join cat_empresa e on p.idempresa = e.idempresa
left join rutas_promotor rp on rp.idpromotor = p.idpromotor
left join rutas_promotor_dias rpd on rpd.idpromotor= rp.idpromotor
left join cat_zonas cz on p.idzona = cz.idzona where p.tipo = 1 $condicion and p.idestatus = 1 and p.idempresa = '" . $idEmpresa . "' group by p.idpromotor $orden;";
//echo($sql);
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idpromotor']      =   $row['idpromotor'];
    $cat[$i]['idempresa']     =   $row['idempresa'];
    $cat[$i]['nombre']        =   $row['nombre'];
    $cat[$i]['apellidos']        =   $row['apellidos'];
    $cat[$i]['idusuario']        =   $row['idusuario'];
    $cat[$i]['rol']        =   $row['rol'];
    $cat[$i]['correo']        =   $row['correo'];
    $cat[$i]['estatus']        =   $row['estatus'];
    $cat[$i]['QR']        =   $row['QR'];
    $cat[$i]['idusohorario']        =   $row['idusohorario'];
    $cat[$i]['uda']           =   $row['uda'];
    $cat[$i]['fda']           =   $row['fda'];
    $cat[$i]['fda_m']           =   $row['fda1']; 
    $cat[$i]['udc']           =   $row['udc'];
    $cat[$i]['fdc']           =   $row['fdc'];
    $cat[$i]['fdc_m']           =   $row['fdc1']; 
    $cat[$i]['idestatus']     =   $row['idestatus'];
    if(intval($row['idestatus']) == 0){
      $cat[$i]['estatus_btn'] =  "Inactivo";
      $cat[$i]['btn_estilo']="i_estatus";
    }else if(intval($row['idestatus']) == 1){
      $cat[$i]['estatus_btn'] =  "Activo";
      $cat[$i]['btn_estilo']="a_estatus";
    }
    $cat[$i]['pwd']        =   $row['pwd'];
    $cat[$i]['nombreempresa'] =   $row['nombreempresa'];
    $cat[$i]['nombrecompleto_s']        =   $row['nombrecompleto_s'];
    $cat[$i]['idzona']         =   $row['idzona'];
    $cat[$i]['zona']         =   $row['zona'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>