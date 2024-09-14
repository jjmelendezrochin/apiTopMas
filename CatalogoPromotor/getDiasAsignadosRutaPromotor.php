<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/

$idpromotor=(isset($_GET['idpromotor']))? mysqli_real_escape_string($con, (int)$_GET['idpromotor']) : false;
$idruta=(isset($_GET['idruta']))? mysqli_real_escape_string($con, (int)$_GET['idruta']) : false;

//$usuario = [];
$sql = "select rd.iddias,rd.idpromotor,rd.idruta,rd.lunes,rd.martes,rd.miercoles,rd.jueves,rd.viernes,rd.sabado,rd.domingo,if(rd.lunesp=0,'',rd.lunesp) as lunesp,if(rd.martesp=0,'',rd.martesp) as martesp,if(rd.miercolesp=0,'',rd.miercolesp) as miercolesp,if(rd.juevesp=0,'',rd.juevesp) as juevesp,if(rd.viernesp=0,'',rd.viernesp) as viernesp,if(rd.sabadop=0,'',rd.sabadop) as sabadop,if(rd.domingop=0,'',rd.domingop) as domingop from rutas_promotor_dias rd where rd.idpromotor='{$idpromotor}' and rd.idruta='{$idruta}' order by 1;";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['iddias'] = $row['iddias'];
    $cat[$i]['idruta'] = $row['idruta'];
    $cat[$i]['idpromotor']      =   $row['idpromotor'];
    $cat[$i]['lunes']     =   $row['lunes'];
    $cat[$i]['martes']     =   $row['martes'];
    $cat[$i]['miercoles']     =   $row['miercoles'];
    $cat[$i]['jueves']     =   $row['jueves'];
    $cat[$i]['viernes']     =   $row['viernes'];
    $cat[$i]['sabado']     =   $row['sabado'];
    $cat[$i]['domingo']     =   $row['domingo'];
    $cat[$i]['lunesp']     =   $row['lunesp'];
    $cat[$i]['martesp']     =   $row['martesp'];
    $cat[$i]['miercolesp']     =   $row['miercolesp'];
    $cat[$i]['juevesp']     =   $row['juevesp'];
    $cat[$i]['viernesp']     =   $row['viernesp'];
    $cat[$i]['sabadop']     =   $row['sabadop'];
    $cat[$i]['domingop']     =   $row['domingop'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>