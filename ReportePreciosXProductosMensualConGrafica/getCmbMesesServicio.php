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

//$usuario = [];
$sql = "Select m.idmes,
m.nombre
from cat_meses m order by m.idmes asc";
//echo($sql);
//echo ('<br>');
         
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idmes']   =   $row['idmes'];
    $cat[$i]['nombre']   =   $row['nombre'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  $i = 0;
  ///  $cat[$i]['sql']      =  $sql;        
    echo $sql;    
 // http_response_code(404);
}
?>