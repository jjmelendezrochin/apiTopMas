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
  if ((int)$request->idruta < 1) {
    return http_response_code(400);
  }

  // Sanitize.
  $idruta    = mysqli_real_escape_string($con, (int)$request->idruta);
  $idEstatus    = mysqli_real_escape_string($con, (int)$request->idEstatus);
  
  // Update.
  $sql = "UPDATE `cat_rutas` SET `idEstatus`='$idEstatus' WHERE `idruta` = '{$idruta}' LIMIT 1";

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