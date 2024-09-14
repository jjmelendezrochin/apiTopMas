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
  $letrazona    = mysqli_real_escape_string($con, trim($request->letrazona));
  $descripcion = mysqli_real_escape_string($con, trim($request->descripcion));
  $estados = mysqli_real_escape_string($con, trim($request->estados));
  $uda = mysqli_real_escape_string($con, trim($request->uda));
  $fda = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fda));
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fdc));

  // Create.
  $sql = "INSERT INTO `cat_zonas`(`idzona`,`letrazona`,`descripcion`,`estados`,`uda`,`fda`,`udc`,`fdc`,idestatus) VALUES (null,'{$letrazona}','{$descripcion}','{$estados}','{$uda}','{$fda}','{$udc}','{$fdc}',1);";

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $catcadena = [
      'letrazona' => $letrazona,
      'descripcion' => $descripcion,
      'estados' => $estados,
      'uda' => $uda,
      'fda' => $fda,
      'udc' => $udc,
      'fdc' => $fdc,
      'idzona'    => mysqli_insert_id($con)
    ];
    echo json_encode($catcadena);
  }
  else
  {
    http_response_code(422);
  }
}
?>