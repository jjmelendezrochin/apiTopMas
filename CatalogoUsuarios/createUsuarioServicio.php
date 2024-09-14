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
  if (trim($request->usuario) == '' || trim($request->clave) == '' || (int)$request->idperfil < 1) {
    return http_response_code(400);
  }


  // Sanitize.
  $usuario    = mysqli_real_escape_string($con, trim($request->usuario));
  $clave = mysqli_real_escape_string($con, trim($request->clave));
  $idperfil = mysqli_real_escape_string($con, trim($request->idperfil));
  $idempresa = mysqli_real_escape_string($con, trim($request->idEmpresa));
  $uda = mysqli_real_escape_string($con, trim($request->uda));
  $fda = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fda));
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fdc));

  // Create.
  $sql = "INSERT INTO `usuarios`(`idUsuario`,`usuario`,`clave`,`idperfil`,`uda`,`fda`,`udc`,`fdc`,`activo`,`idempresa`) VALUES (null,'{$usuario}','{$clave}','{$idperfil}','{$uda}','{$fda}','{$udc}','{$fdc}',1,'{$idempresa}');";

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $catcadena = [
      'usuario' => $usuario,
      'clave' => $clave,
      'idperfil' => $idperfil,
      'uda' => $uda,
      'fda' => $fda,
      'udc' => $udc,
      'fdc' => $fdc,
      'idUsuario'    => mysqli_insert_id($con)
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