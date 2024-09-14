<?php

require '../database.php';

// Extract, validate and sanitize the id.
$id = ($_GET['idpromotorasignado'] !== null && (int)$_GET['idpromotorasignado'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idpromotorasignado']) : false;

if(!$id)
{
  return http_response_code(400);
}

// Delete.
$sql = "UPDATE `promotores_supervisor` set idestatus='0' WHERE `idpromotorasignado` ='{$id}' LIMIT 1";

if(mysqli_query($con, $sql))
{
  http_response_code(204);
}
else
{
  return http_response_code(422);
}
?>