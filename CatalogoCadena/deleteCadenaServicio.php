<?php

require '../database.php';

// Extract, validate and sanitize the id.
$id = ($_GET['idcadena'] !== null && (int)$_GET['idcadena'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idcadena']) : false;

if(!$id)
{
  return http_response_code(400);
}

// Delete.
$sql = "UPDATE `cat_cadena` SET idestatus='0' WHERE `idcadena` ='{$id}' LIMIT 1";

if(mysqli_query($con, $sql))
{
  http_response_code(204);
}
else
{
  return http_response_code(422);
}
?>