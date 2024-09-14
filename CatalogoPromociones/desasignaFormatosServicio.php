<?php

require '../database.php';

// Extract, validate and sanitize the id.
$id = ($_GET['idpromocionformato'] !== null && (int)$_GET['idpromocionformato'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idpromocionformato']) : false;

if(!$id)
{
  return http_response_code(400);
}

// Delete.
$sql = "UPDATE `promocion_formato` set idestatus='0' WHERE `idpromocionformato` ='{$id}' LIMIT 1";

if(mysqli_query($con, $sql))
{
  http_response_code(204);
}
else
{
  return http_response_code(422);
}
?>