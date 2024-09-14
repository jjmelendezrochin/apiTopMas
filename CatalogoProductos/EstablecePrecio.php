<?php
require '../database.php';

date_default_timezone_set('America/Mexico_City');


// Get the posted data.
$postdata = file_get_contents("php://input");
// print_r($postdata);


if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);
    

  // Validate.
  if (trim($request->idproductoformatoprecio) == '0' || trim($request->valor) == '') {
    return http_response_code(400);
  }
  

  // Sanitize.
  $idproductoformatoprecio = (trim($request->idproductoformatoprecio));
  $valor = (trim($request->valor));
 
  // *****************************
  // Update.
  $sql = "UPDATE producto_formato_precio  SET precio='$valor' WHERE idproductoformatoprecio = '{$idproductoformatoprecio}';";

  if($result = $con->query($sql))
  {
    http_response_code(204);
  }
  else
  {
    echo json_encode($catusuario=["sql"=>$sql]);
    //return http_response_code(422);
  }  
}
?>