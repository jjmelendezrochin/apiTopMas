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
  if (trim($request->nombreempresa) == '' || trim($request->contacto) == ''  || trim($request->alias) == '') {
    return http_response_code(400);
  }


  // Sanitize.
  $nombreempresa    = mysqli_real_escape_string($con, trim($request->nombreempresa));
  $contacto = mysqli_real_escape_string($con, trim($request->contacto));
  $alias = mysqli_real_escape_string($con, trim($request->alias));
  $uda = mysqli_real_escape_string($con, trim($request->uda));
  $fda = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fda));
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fdc));

  // Create.
  $sql = "INSERT INTO `cat_empresa`(`idempresa`,`nombreempresa`,`contacto`,`alias`,`uda`,`fda`,`udc`,`fdc`,idestatus) VALUES (null,'{$nombreempresa}','{$contacto}','{$alias}','{$uda}','{$fda}','{$udc}','{$fdc}',1);";

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $catcadena = [
      'nombreempresa' => $nombreempresa,
      'contacto' => $contacto,
      'uda' => $uda,
      'fda' => $fda,
      'udc' => $udc,
      'fdc' => $fdc,
      'idempresa'    => mysqli_insert_id($con)
    ];
    echo json_encode($catcadena);
  }
  else
  {
    http_response_code(422);
  }
}
?>