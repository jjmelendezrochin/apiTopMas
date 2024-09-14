<?php

require '../database.php';

// Extract, validate and sanitize the id.
$id = ($_GET['idformato'] !== null && (int)$_GET['idformato'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idformato']) : false;

if(!$id)
{
  return http_response_code(400);
}

// Delete.
$sql = "UPDATE `cat_formato` SET idestatus='0' WHERE `idformato` ='{$id}' LIMIT 1";

if(mysqli_query($con, $sql))
{
  http_response_code(204);
}
else
{
  return http_response_code(422);
}
?>