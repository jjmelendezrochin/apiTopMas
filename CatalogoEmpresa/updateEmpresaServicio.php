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
  if (trim($request->nombreempresa) == '' || trim($request->contacto) == '' || trim($request->alias) == '') {
    return http_response_code(400);
  }

  // Sanitize.
  $idempresa    = mysqli_real_escape_string($con, (int)$request->idempresa);
  $nombreempresa    = mysqli_real_escape_string($con, trim($request->nombreempresa));
  $contacto = mysqli_real_escape_string($con, trim($request->contacto));
  $alias = mysqli_real_escape_string($con, trim($request->alias));
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fda));
  //$udc = mysqli_real_escape_string($con, trim($request->udc));
  //$fdc = mysqli_real_escape_string($con, trim($request->fdc));

  // Update.
  $sql = "UPDATE `cat_empresa` SET `nombreempresa`='$nombreempresa',`contacto`='$contacto',`alias`='$alias',`udc`='$udc',`fdc`='$fdc' WHERE `idempresa` = '{$idempresa}' LIMIT 1";

  if(mysqli_query($con, $sql))
  {
        $catempresa = [
	  'idempresa' => $idempresa,
      'nombreempresa' => $nombreempresa,
      'contacto' => $contacto,
      'alias' => $alias,
      'udc' => $udc,
      'fdc' => $fdc,
	  ];
	  echo json_encode($catempresa);
  }
  else
  {
    return http_response_code(422);
  }  
}
?>