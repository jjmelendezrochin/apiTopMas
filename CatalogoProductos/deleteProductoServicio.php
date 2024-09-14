<?php

require '../database.php';

// Extract, validate and sanitize the id.
$id = ($_GET['idproducto'] !== null && (int)$_GET['idproducto'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idproducto']) : false;

if(!$id)
{
  return http_response_code(400);
}

// Delete.
$sql = "UPDATE `cat_productos` SET idestatus='0' WHERE `idproducto` ='{$id}' LIMIT 1";

if(mysqli_query($con, $sql))
{
  http_response_code(204);
}
else
{
  return http_response_code(422);
}
?>