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
  if ((int)$request->idpromotor < 1) {
    return http_response_code(400);
  }

  // Sanitize.
  $idpromotor    = mysqli_real_escape_string($con, (int)$request->idpromotor);
  $idestatus    = mysqli_real_escape_string($con, (int)$request->idestatus);
  
  // Update.
  $sql = "UPDATE `cat_promotor` SET `idestatus`='{$idestatus}' WHERE `idpromotor` = '{$idpromotor}' LIMIT 1";

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