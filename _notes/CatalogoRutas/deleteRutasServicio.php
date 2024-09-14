<?php

require '../database.php';

// Extract, validate and sanitize the id.
$id = ($_GET['idruta'] !== null && (int)$_GET['idruta'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idruta']) : false;

if(!$id)
{
  return http_response_code(400);
}

// Delete.
$sql = "UPDATE `cat_rutas` set idEstatus='0' WHERE `idruta` ='{$id}' LIMIT 1";

if(mysqli_query($con, $sql))
{
  http_response_code(204);
}
else
{
  return http_response_code(422);
}
?>