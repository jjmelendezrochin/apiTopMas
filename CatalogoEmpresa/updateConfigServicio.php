<?php
require '../database.php';

date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);

  // Validate.
  if ((int)$request->idconfig == 0 || trim($request->valor) == '') {
	return http_response_code(400);
  }

  // Sanitize.
  $idconfig    = mysqli_real_escape_string($con, (int)$request->idconfig);
  $valor    = mysqli_real_escape_string($con, trim($request->valor));
  //$udc = mysqli_real_escape_string($con, trim($request->udc));
  //$fdc = mysqli_real_escape_string($con, trim($request->fdc));

  // Update.
  $sql = "UPDATE `configuracion` SET `valor`='$valor' WHERE `idconf` = '{$idconfig}' LIMIT 1";

  if(mysqli_query($con, $sql))
  {
    http_response_code(204);
  }
  else
  {
    return http_response_code(422);
  }  
}
?>