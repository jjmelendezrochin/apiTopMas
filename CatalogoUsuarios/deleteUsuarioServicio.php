<?php

require '../database.php';

// Extract, validate and sanitize the id.
$id = ($_GET['idUsuario'] !== null && (int)$_GET['idUsuario'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idUsuario']) : false;

if(!$id)
{
  return http_response_code(400);
}

// Delete.
$sql = "UPDATE `usuarios` SET activo='0' WHERE `idUsuario` ='{$id}' LIMIT 1";

if(mysqli_query($con, $sql))
{
  http_response_code(204);
}
else
{
  return http_response_code(422);
}
?>