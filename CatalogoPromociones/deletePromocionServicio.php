<?php

require '../database.php';

// Extract, validate and sanitize the id.
$id = ($_GET['idpromocion'] !== null && (int)$_GET['idpromocion'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idpromocion']) : false;

if(!$id)
{
  return http_response_code(400);
}

// Delete.
$sql = "UPDATE `promocion` SET idestatus='0' WHERE `idpromocion` ='{$id}' LIMIT 1";

if(mysqli_query($con, $sql))
{
  http_response_code(204);
}
else
{
  return http_response_code(422);
}
?>