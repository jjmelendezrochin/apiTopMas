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
  if ((int)$request->idEmpresa < 1 || trim($request->nombre) == '' || trim($request->apellidos) == '' || trim($request->idusuario) == '') {
    return http_response_code(400);
  }


  // Sanitize.
  $idempresa    = mysqli_real_escape_string($con, (int)$request->idEmpresa);
  $idsupervisor    = mysqli_real_escape_string($con, (int)$request->idsupervisor);
  $nombre = mysqli_real_escape_string($con, trim($request->nombre));
  $apellidos = mysqli_real_escape_string($con, trim($request->apellidos));
  $idusuario = mysqli_real_escape_string($con, trim($request->idusuario));
  $rol = mysqli_real_escape_string($con, trim($request->rol));
  $correo = mysqli_real_escape_string($con, trim($request->correo));
  $estatus    = mysqli_real_escape_string($con, (int)$request->estatus);
  $QR = mysqli_real_escape_string($con, trim($request->QR));
  $idusohorario = mysqli_real_escape_string($con, (int)$request->idusohorario);
  $uda = mysqli_real_escape_string($con, trim($request->uda));
  $fda = date('Y-m-d H:i');
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');
  $pwd = mysqli_real_escape_string($con, trim($request->pwd));
  $idzona = mysqli_real_escape_string($con, (int)$request->idzona);

  // Create.
  $sql = "INSERT INTO `cat_promotor`(`idpromotor`,`idempresa`,`idsupervisor`,`nombre`,`apellidos`,`idusuario`,`rol`,`correo`,`estatus`,`QR`,`idusohorario`,`uda`,`fda`,`udc`,`fdc`,idestatus,`pwd`,`tipo`,`idzona`) 
  VALUES (null,{$idempresa},{$idsupervisor},'{$nombre}','{$apellidos}','{$idusuario}','{$rol}','{$correo}',{$estatus},'{$QR}','{$idusohorario}','{$uda}','{$fda}','{$udc}','{$fdc}',1,'{$pwd}','1','{$idzona}');";

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $catcadena = [
      'idempresa' => $idempresa,
      'idsupervisor' => $idsupervisor,
      'nombre' => $idsupervisor,
      'apellidos' => $idsupervisor,
      'idusuario' => $idsupervisor,
      'rol' => $idsupervisor,
      'correo' => $correo,
      'estatus' => $estatus,
      'QR' => $QR,
      'idusohorario' => $idusohorario,
      'uda' => $uda,
      'fda' => $fda,
      'udc' => $udc,
      'fdc' => $fdc,      
      'pwd' => $pwd,
      'idpromotor'    => mysqli_insert_id($con)      
    ];
    echo json_encode($catcadena);
  }
  else
  {
    http_response_code(422);
  }
}
?>