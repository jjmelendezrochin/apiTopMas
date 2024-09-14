<?php

require '../database.php';

// Extract, validate and sanitize the id.
$latitud = (isset($_GET['latitud']))? mysqli_real_escape_string($con, $_GET['latitud']) : false;
$longitud = (isset($_GET['longitud']))? mysqli_real_escape_string($con, $_GET['longitud']) : false;
$direccion = ($_GET['direccion'])? mysqli_real_escape_string($con, $_GET['direccion']) : false;



if(!$latitud && !$longitud && !$direccion)
{
  return http_response_code(400);
}

// Delete.
$sql = "UPDATE `cat_rutas` set latitud='{$latitud}',longitud='{$longitud}' WHERE `direccioncompleta` like '%{$direccion}%' LIMIT 1;";

if(mysqli_query($con, $sql))
{
  http_response_code(204);
}
else
{
  return http_response_code(422);
}
?>