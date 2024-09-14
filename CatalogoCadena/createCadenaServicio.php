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
  if (trim($request->cadena) == '') {
    return http_response_code(400);
  }


  // Sanitize.
  $cadena = mysqli_real_escape_string($con, trim($request->cadena));
  $uda = mysqli_real_escape_string($con, trim($request->uda));
  $fda = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fda));
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fdc));
  $nombrecorto = mysqli_real_escape_string($con, trim($request->nombrecorto));
  // Create.
  $sql = "INSERT INTO `cat_cadena`(`idcadena`,`cadena`,`uda`,`fda`,`udc`,`fdc`,idestatus,nombrecorto) VALUES (null,'{$cadena}','{$uda}','{$fda}','{$udc}','{$fdc}',1,'{$nombrecorto}');";

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $catcadena = [
      'cadena' => $cadena,
      'uda' => $uda,
      'fda' => $fda,
      'udc' => $udc,
      'fdc' => $fdc,
      'idcadena'    => mysqli_insert_id($con)
    ];
    echo json_encode($catcadena);
  }
  else
  {
    http_response_code(422);
  }
}
?>