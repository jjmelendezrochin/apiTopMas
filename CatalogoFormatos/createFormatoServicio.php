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
  if ((int)$request->idcadena < 1 || trim($request->formato) == '') {
    return http_response_code(400);
  }


  // Sanitize.
  $idcadena = mysqli_real_escape_string($con, (int)$request->idcadena);
  $formato = mysqli_real_escape_string($con, trim($request->formato));
  $uda = mysqli_real_escape_string($con, trim($request->uda));
  $fda = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fda));
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fdc));

  // Create.
  $sql = "INSERT INTO `cat_formato`(`idformato`,`idcadena`,`formato`,`uda`,`fda`,`udc`,`fdc`,idestatus) VALUES (null,'{$idcadena}','{$formato}','{$uda}','{$fda}','{$udc}','{$fdc}',1);";

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $catcadena = [
      'idcadena' => $idcadena,
      'formato' => $formato,
      'uda' => $uda,
      'fda' => $fda,
      'udc' => $udc,
      'fdc' => $fdc,
      'idformato'    => mysqli_insert_id($con)
    ];
    echo json_encode($catcadena);
  }
  else
  {
    echo json_encode($catcadena=["sql"=>$sql]);
    //http_response_code(422);
  }
}
?>