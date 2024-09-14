<?php

require '../database.php';

// Extract, validate and sanitize the id.
$id = ($_GET['idrutaasignada'] !== null && (int)$_GET['idrutaasignada'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idrutaasignada']) : false;

if(!$id)
{
  return http_response_code(400);
}

// Delete.
$sql = "UPDATE `rutas_promotor` set idestatus='0' WHERE `idrutaasignada` ='{$id}' LIMIT 1";

if(mysqli_query($con, $sql))
{
  http_response_code(204);
}
else
{
  return http_response_code(422);
}
?>