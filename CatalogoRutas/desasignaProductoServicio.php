<?php

require '../database.php';

// Extract, validate and sanitize the id.
$id = ($_GET['idproductoruta'] !== null && (int)$_GET['idproductoruta'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idproductoruta']) : false;

if(!$id)
{
  return http_response_code(400);
}

// Delete.
$sql = "UPDATE `producto_ruta` set idestatus='0' WHERE `idproductoruta` ='{$id}' LIMIT 1";

if(mysqli_query($con, $sql))
{
  http_response_code(204);
}
else
{
  return http_response_code(422);
}
?>