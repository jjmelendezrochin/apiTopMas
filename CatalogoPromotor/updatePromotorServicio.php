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
  if ((int)$request->idpromotor < 1 || (int)$request->idEmpresa < 1 || trim($request->nombre) == '' || trim($request->apellidos) == '' || trim($request->idusuario) == '') {
    return http_response_code(400);
  }

  // Sanitize.
  $idpromotor    = mysqli_real_escape_string($con, (int)$request->idpromotor);
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
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fda));
  //$udc = mysqli_real_escape_string($con, trim($request->udc));
  //$fdc = mysqli_real_escape_string($con, trim($request->fdc));
  $pwd = mysqli_real_escape_string($con, trim($request->pwd));

/*Verifica la existencia del usuario*/
$sql = "Select count(*) as cta from cat_promotor where idusuario = '{$idusuario}' and not idpromotor = '{$idpromotor}'";

$cta = 0;

if($result = mysqli_query($con,$sql))
  {
    while($row = mysqli_fetch_assoc($result))
    {
		$cta = intval($row['cta']);
    }
  }
 
/**************************************/ 
 
  if($cta == 0){

  // Update.
  $sql = "UPDATE `cat_promotor` SET `idempresa`='$idempresa',`idsupervisor`='$idsupervisor',`nombre`='$nombre',`apellidos`='$apellidos',`idusuario`='$idusuario',`idempresa`='$idempresa',`rol`='$rol',`correo`='$correo',`estatus`='$estatus',`QR`='$QR',`idusohorario`='$idusohorario',`udc`='$udc',`fdc`='$fdc',`pwd`='$pwd' WHERE `idpromotor` = '{$idpromotor}' LIMIT 1";

  if(mysqli_query($con, $sql))
  {
    http_response_code(204);
  }
  else
  {
    return http_response_code(422);
  }  
  }
  else{
	$catcadena = ['nombre' => 'El usuario ya existe'];
	echo json_encode($catcadena); 
  }
}
?>