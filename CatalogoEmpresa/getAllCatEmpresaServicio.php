<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$emp = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;
s

if(!$idEmpresa)
{
  return http_response_code(400);
}*/

//$usuario = [];
$sql = "Select e.* from cat_empresa e where e.idestatus = 1 order by nombreempresa;";
//echo($sql);
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $emp[$i]['idEmpresa']     =   $row['idempresa'];
    $emp[$i]['nombreempresa'] =   $row['nombreempresa'];
    $emp[$i]['idestatus']     =   $row['idestatus'];
    $i++;
  }

  echo json_encode($emp);
}
else
{
  http_response_code(404);
}
?>