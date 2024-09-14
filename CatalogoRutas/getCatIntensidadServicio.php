<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

$condicion = "";
$cat = [];


$sql = "Select i.* from cat_intensidad i order by descripcion asc;";
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['idintensidad']      =   $row['idintensidad'];
    $cat[$i]['intensidad']     =   $row['idintensidad'];
    $cat[$i]['descripcion']        =   $row['descripcion'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>