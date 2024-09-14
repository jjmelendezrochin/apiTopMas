<?php

require '../database.php';

// Extract, validate and sanitize the id.
$id = ($_GET['idzona'] !== null && (int)$_GET['idzona'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idzona']) : false;

if(!$id)
{
  return http_response_code(400);
}

// Delete.
$sql = "UPDATE `cat_zonas` SET idestatus='0' WHERE `idzona` ='{$id}' LIMIT 1";

if(mysqli_query($con, $sql))
{
  http_response_code(204);
}
else
{
  return http_response_code(422);
}
?>