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
  if ((int)$request->idcadena < 1 || trim($request->cadena) == '') {
    return http_response_code(400);
  }

  // Sanitize.
  $idcadena    = mysqli_real_escape_string($con, (int)$request->idcadena);
  $idEmpresa    = mysqli_real_escape_string($con, (int)$request->idEmpresa);
  $cadena = mysqli_real_escape_string($con, trim($request->cadena));
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fda));
  $nombrecorto = mysqli_real_escape_string($con, trim($request->nombrecorto));
  //$udc = mysqli_real_escape_string($con, trim($request->udc));
  //$fdc = mysqli_real_escape_string($con, trim($request->fdc));


  // Update.
  $sql = "UPDATE `cat_cadena` SET `cadena`='$cadena',`udc`='$udc',`fdc`='$fdc', nombrecorto='{$nombrecorto}' WHERE `idcadena` = '{$idcadena}' LIMIT 1";

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