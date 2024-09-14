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

/*$campos=(isset($_GET['campos']))? mysqli_real_escape_string($con, (string)$_GET['campos']) : false;
$dia=(isset($_GET['dia']))? mysqli_real_escape_string($con, (string)$_GET['dia']) : false;

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
}*/

$ord=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : 0;
// Condicion de ordenamiento
switch (intval($ord)){
  case 0:
      $orden = " order by 1, 2 ";
      break;
  case 1:
      $orden = " order by Supervisor asc ";
      break;
  case 2:
      $orden = " order by Supervisor desc ";
      break;
  case 3:
       $orden = " order by Promotor asc";
      break;
  case 4:
      $orden = " order by Promotor desc";
      break;
  }

//$usuario = [];
$sql = "Select Supervisor, Promotor from vw_supervisor_promotor union Select 'Sin Supervisor' as Supervisor, CONCAT(`p`.`nombre`, ' ', `p`.`apellidos`) AS `Promotor` from cat_promotor p where p.idpromotor not in (SELECT idpromotor FROM `vw_supervisor_promotor`) and tipo = 0 and idestatus = 1
and p.idempresa = (Select cfg.valor from configuracion cfg where cfg.idconf = 2)
 $orden;";
//echo($sql);
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['Supervisor']      =   $row['Supervisor'];
    $cat[$i]['Promotor']     =   $row['Promotor'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>