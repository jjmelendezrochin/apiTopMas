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
  if (trim($request->letrazona) == '' || trim($request->descripcion) == '' || trim($request->estados) == '') {
    return http_response_code(400);
  }

  // Sanitize.
  $idzona    = mysqli_real_escape_string($con, (int)$request->idzona);
  $letrazona    = mysqli_real_escape_string($con, trim($request->letrazona));
  $descripcion = mysqli_real_escape_string($con, trim($request->descripcion));
  $estados = mysqli_real_escape_string($con, trim($request->estados));
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fda));
  //$udc = mysqli_real_escape_string($con, trim($request->udc));
  //$fdc = mysqli_real_escape_string($con, trim($request->fdc));

  // Update.
  $sql = "UPDATE `cat_zonas` SET `letrazona`='$letrazona',`descripcion`='$descripcion',`estados`='$estados',`udc`='$udc',`fdc`='$fdc' WHERE `idzona` = '{$idzona}' LIMIT 1";

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