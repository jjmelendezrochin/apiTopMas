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
  if ((int)$request->idUsuario < 1 ||trim($request->usuario) == '' || trim($request->clave) == '' || (int)$request->idperfil < 1) {
    return http_response_code(400);
  }

  // Sanitize.
  $idUsuario    = mysqli_real_escape_string($con, (int)$request->idUsuario);
  $usuario    = mysqli_real_escape_string($con, trim($request->usuario));
  $clave = mysqli_real_escape_string($con, trim($request->clave));
  $idperfil = mysqli_real_escape_string($con, trim($request->idperfil));
  $idempresa = mysqli_real_escape_string($con, trim($request->idEmpresa));
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fda));
  //$udc = mysqli_real_escape_string($con, trim($request->udc));
  //$fdc = mysqli_real_escape_string($con, trim($request->fdc));

  // Update.
  $sql = "UPDATE `usuarios` SET `usuario`='$usuario',`clave`='$clave',`idperfil`='$idperfil',`udc`='$udc',`fdc`='$fdc',`idempresa`='$idempresa' WHERE `idUsuario` = '{$idUsuario}' LIMIT 1";

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