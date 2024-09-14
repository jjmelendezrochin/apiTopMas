<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

date_default_timezone_set('America/Mexico_City');

$condicion = "";
$cat = [];


$fecha=date('Y/m/d');

$sql = "
Select 0 as idAtendido, 'Sin Atender' as valor
union
Select 1 as idAtendido, 'En Revisión' as valor
union
Select 2 as idAtendido, 'Atendido' as valor";

if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idAtendido']      =   $row['idAtendido'];
    $cat[$i]['valor']     		=   $row['valor'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>