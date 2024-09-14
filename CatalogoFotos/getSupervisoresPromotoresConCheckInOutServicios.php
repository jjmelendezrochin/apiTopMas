<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

date_default_timezone_set('America/Mexico_City');

$condicion = "";
$vw = "";
$cat = [];
$cat_sp = [["supervisores" => [], "supervisores_promotores" => []]];
$operacion = (isset($_GET['operacion']))? $_GET['operacion'] : "0";
// Obteniendo la empresa
$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;

// ******************************
// Lista de todos los supervisores
$sql =($operacion == 0) ? "SELECT distinct * from vw_supervisores_actividad_hoy_0 where idEmpresa = " . $idEmpresa. " order by Actividad desc, letrazona asc":"";

// ******************************
// Lista de supervisores con actividad
$sql1 =($operacion == 1) ? "SELECT distinct * from vw_supervisores_con_actividad_hoy_0 where idEmpresa = " . $idEmpresa. ";":"";

// ******************************
// Lista de supervisores sin actividad
$sql2 = ($operacion == 2) ? "SELECT distinct * from vw_supervisores_sin_actividad_hoy_0 where idEmpresa = " . $idEmpresa. ";":"";

// ******************************
// Lista de supervisores sin actividad
$sql3 = ($operacion == 3) ? "SELECT distinct * from vw_supervisores_sin_actividad_hoy_0 where idEmpresa = " . $idEmpresa. " ;":"";

// ******************************
// Lista de todos los usuarios activos de hoy
$sql4 =($operacion == 0 || $operacion == 1) ? "SELECT distinct v.letrazona, v.Supervisor, v.Promotor, ifnull(c.cont,0) as cont 
    FROM `vw_supervisor_promotor_todos_0` v 
    left join vw_checkin_hoy_0 c on v.idpromotor = c.idpromotor and v.idempresa = c.idempresa
    Where ifnull(c.cont,0) = 1 and v.idempresa = " . $idEmpresa . "
    order by 1,2,3;":"";

// ******************************
// Consulta de usuarios inactivos de hoy
$sql5 = ($operacion == 0 || $operacion == 2) ? "SELECT distinct v.letrazona, v.Supervisor, v.Promotor, ifnull(c.idpromotor,0) as cont 
    FROM `vw_supervisor_promotor_todos_0` v 
    left join vw_checkin_hoy_0 c on v.idpromotor = c.idpromotor and v.idempresa = c.idempresa
    Where ifnull(c.idpromotor,0) = 0 and v.idempresa = " . $idEmpresa . "
    order by 1,2,3":"";

// ******************************
// Consulta de usuarios inactivos  de hoy
$sql6 = ($operacion == 3) ? "SELECT distinct v.letrazona, v.Supervisor, v.Promotor, ifnull(c.idpromotor,0) as cont 
FROM `vw_supervisor_promotor_todos_0` v 
left join vw_checkin_hoy_0 c on v.idpromotor = c.idpromotor  and v.idempresa = c.idempresa
Where ifnull(c.idpromotor,0) = 0 and v.idempresa = " . $idEmpresa . "
order by 1,2,3":"";

$i = 0;
  
//echo($sql);
//echo ('<br>');

/*************************************************************************/
/* Seccion de obtencion de supervisores con o sin actividad */
if($sql != ""){
  if($result = mysqli_query($con,$sql))
  {
    while($row = mysqli_fetch_assoc($result))
    {
      $cat[$i]['letrazona']      =   $row['letrazona'];
      $cat[$i]['Supervisor']     =   $row['nombre'];
      $cat[$i]['Promotor']        =   "";
      $cat[$i]['cont']           =   $row['Actividad'];
      if(intval($row['Actividad']) == 1){
        $cat[$i]['color']       =   'green';
       }
       else if(intval($row['Actividad']) == 0){
        $cat[$i]['color']       =   'red';
       }
      $i++;
    }
      $cat_sp[0]["supervisores"] = $cat;  
  }
  else
  {
    http_response_code(404);
  }
  }
  
  if($sql1 != ""){
  if($result = mysqli_query($con,$sql1))
  {
       while($row = mysqli_fetch_assoc($result))
   {
    $cat[$i]['letrazona']      =   $row['letrazona'];
    $cat[$i]['Supervisor']     =   $row['nombre'];
    $cat[$i]['Promotor']        =   "";
    $cat[$i]['cont']           =   $row['Actividad'];
    if(intval($row['Actividad']) == 1){
     $cat[$i]['color']       =   'green';
    }
    else if(intval($row['Actividad']) == 0){
     $cat[$i]['color']       =   'red';
    }
    $i++;
  }
  $cat_sp[0]["supervisores"] = $cat;
  }
  else{
    http_response_code(404);
  }
  }
  
  if($sql2 != ""){
    if($result = mysqli_query($con,$sql2))
    {
         while($row = mysqli_fetch_assoc($result))
     {
      $cat[$i]['letrazona']      =   $row['letrazona'];
      $cat[$i]['Supervisor']     =   $row['nombre'];
      $cat[$i]['Promotor']        =   "";
      $cat[$i]['cont']           =   $row['Actividad'];
      if(intval($row['Actividad']) == 1){
       $cat[$i]['color']       =   'green';
      }
      else if(intval($row['Actividad']) == 0){
       $cat[$i]['color']       =   'red';
      }
      $i++;
    }
      //$cat[$i]['cuenta'] = $i+1;
      $cat_sp[0]["supervisores"] = $cat;  
    }
    else{
      http_response_code(404);
    }
    }

    if($sql3 != ""){
      if($result = mysqli_query($con,$sql3))
      {
           while($row = mysqli_fetch_assoc($result))
       {
      $cat[$i]['letrazona']      =   $row['letrazona'];
      $cat[$i]['Supervisor']     =   $row['nombre'];
      $cat[$i]['Promotor']        =   "";
      $cat[$i]['cont']           =   $row['Actividad'];
      if(intval($row['Actividad']) == 1){
         $cat[$i]['color']       =   'green';
        }
        else if(intval($row['Actividad']) == 0){
         $cat[$i]['color']       =   'red';
        }
        $i++;
      }
      $cat_sp[0]["supervisores"] = $cat;
      }
      else{
        http_response_code(404);
      }
      }
/*************************************************************************/
/* Seccion de obtencion de supervisores y promotores con o sin actividad */
$cat = [];
$i=0;
if($sql4 != ""){
if($result = mysqli_query($con,$sql4))
{
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['letrazona']      =   $row['letrazona'];
    $cat[$i]['Supervisor']     =   $row['Supervisor'];
    $cat[$i]['Promotor']        =   $row['Promotor'];
    $cat[$i]['cont']           =   $row['cont'];
    if(intval($row['cont']) == 1){
      $cat[$i]['color']       =   'green';
     }
     else if(intval($row['cont']) == 0){
      $cat[$i]['color']       =   'red';
     }
    $i++;
  }
  if($operacion == 1){
    $cat_sp[0]["supervisores_promotores"] = $cat;
    echo json_encode($cat_sp);
  }  
}
else
{
  http_response_code(404);
}
}

if($sql5 != ""){
if($result = mysqli_query($con,$sql5))
{
  $i = ($operacion == 2)?0:$i;
 while($row = mysqli_fetch_assoc($result))
 {
  $cat[$i]['letrazona']      =   $row['letrazona'];
  $cat[$i]['Supervisor']     =   $row['Supervisor'];
  $cat[$i]['Promotor']        =   $row['Promotor'];
  $cat[$i]['cont']           =   $row['cont'];
  if(intval($row['cont']) == 1){
   $cat[$i]['color']       =   'green';
  }
  else if(intval($row['cont']) == 0){
   $cat[$i]['color']       =   'red';
  }
  $i++;
}
if($operacion == 0 || $operacion == 2){
  //$cat[$i]['cuenta'] = $i+1;
  $cat_sp[0]["supervisores_promotores"] = $cat;
  echo json_encode($cat_sp);
}
}
else{
  http_response_code(404);
}
}

if($sql6 != ""){
if($result = mysqli_query($con,$sql6))
{
  $i = ($operacion == 3)?0:$i;
 while($row = mysqli_fetch_assoc($result))
 {
  $cat[$i]['letrazona']      =   $row['letrazona'];
  $cat[$i]['Supervisor']     =   $row['Supervisor'];
  $cat[$i]['Promotor']        =   $row['Promotor'];
  $cat[$i]['cont']           =   $row['cont'];
  if(intval($row['cont']) == 1){
   $cat[$i]['color']       =   'green';
  }
  else if(intval($row['cont']) == 0){
   $cat[$i]['color']       =   'red';
  }
  $i++;
}
if($operacion == 3){
  //$cat_sp = json_encode($cat_sp);
  $cat_sp[0]["supervisores_promotores"] = $cat;
  echo json_encode($cat_sp);
}
}
else{
  http_response_code(404);
}
}
?>