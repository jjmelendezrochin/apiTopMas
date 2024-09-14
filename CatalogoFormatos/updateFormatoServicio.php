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
  if ((int)$request->idformato < 1 || (int)$request->idcadena < 1 || trim($request->formato) == '') {
    return http_response_code(400);
  }

  // Sanitize.
  $idformato    = mysqli_real_escape_string($con, (int)$request->idformato);
  $idcadena = mysqli_real_escape_string($con, (int)$request->idcadena);
  $formato = mysqli_real_escape_string($con, trim($request->formato));
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fda));
  //$udc = mysqli_real_escape_string($con, trim($request->udc));
  //$fdc = mysqli_real_escape_string($con, trim($request->fdc));

  // Update.
  $sql = "UPDATE `cat_formato` SET `idcadena`='$idcadena',`formato`='$formato',`udc`='$udc',`fdc`='$fdc' WHERE `idformato` = '{$idformato}' LIMIT 1";

  if(mysqli_query($con, $sql))
  {
    http_response_code(204);
  }
  else
  {
    echo json_encode($catcadena=["sql"=>$sql]);
    //return http_response_code(422);
  }  
}
?>