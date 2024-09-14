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

$idsupervisor=(isset($_GET['idsupervisor']))? mysqli_real_escape_string($con, (int)$_GET['idsupervisor']) : false;

//$usuario = [];
$sql = "SELECT ps.*,concat(cp.nombre,' ',cp.apellidos) as nombre_completo FROM promotores_supervisor ps
LEFT JOIN cat_promotor cp on ps.idpromotor = cp.idpromotor 
where ps.idsupervisor='{$idsupervisor}' and ps.idestatus='1' order by 1 asc;";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idpromotorasignado'] = $row['idpromotorasignado'];
    $cat[$i]['idsupervisor'] = $row['idsupervisor'];
    $cat[$i]['idpromotor'] = $row['idpromotor'];
    $cat[$i]['uda'] = $row['uda'];
    $cat[$i]['fechaasignacion']     =   $row['fechaasignacion'];
    if($row['idestatus'] == '1'){
    $cat[$i]['estatus']     =   'Activo';
    $cat[$i]['nombre_completo']      =   $row['nombre_completo'];
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