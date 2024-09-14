<?php
require '../database.php';

date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);

  $iderror = mysqli_real_escape_string($con, trim($request->iderror));
  /*
  $fabricante = mysqli_real_escape_string($con, trim($request->fabricante));
  $marca = mysqli_real_escape_string($con, trim($request->marca));
  $modelo = mysqli_real_escape_string($con, trim($request->modelo));
  $board = mysqli_real_escape_string($con, trim($request->board));
  $hardware = mysqli_real_escape_string($con, trim($request->hardware));
  $serie = mysqli_real_escape_string($con, trim($request->serie));
  $uid = mysqli_real_escape_string($con, trim($request->uid));
  $android_id = mysqli_real_escape_string($con, trim($request->android_id));
  $resolucion = mysqli_real_escape_string($con, trim($request->resolucion));
  $tamaniopantalla = mysqli_real_escape_string($con, trim($request->tamaniopantalla));
  $densidad = mysqli_real_escape_string($con, trim($request->densidad));
  $bootloader = mysqli_real_escape_string($con, trim($request->bootloader));
  $user_value = mysqli_real_escape_string($con, trim($request->user_value));
  $host_value = mysqli_real_escape_string($con, trim($request->host_value));
  $version = mysqli_real_escape_string($con, trim($request->version));
  $api_value = mysqli_real_escape_string($con, trim($request->api_value));
  $build_id = mysqli_real_escape_string($con, trim($request->build_id));
  $build_time = mysqli_real_escape_string($con, trim($request->build_time));
  $fingerprint = mysqli_real_escape_string($con, trim($request->fingerprint));
  $usuario = mysqli_real_escape_string($con, trim($request->usuario));
  $error = mysqli_real_escape_string($con, trim($request->error));
  $fechahora = mysqli_real_escape_string($con, trim($request->fechahora));
  $seccion = mysqli_real_escape_string($con, trim($request->seccion));
  */
  $idatendido = mysqli_real_escape_string($con, trim($request->idatendido));
  $idatendidopor = mysqli_real_escape_string($con, trim($request->idatendidopor));
  $versionsolucion = mysqli_real_escape_string($con, trim($request->versionsolucion));
  $solucion = mysqli_real_escape_string($con, trim($request->solucion));


  $sql = "UPDATE errores
	SET atendido = $idatendido, 
	atendidopor = $idatendidopor, 
	versionsolucion = '$versionsolucion',
	solucion = '$solucion'
	WHERE idError = $iderror;";

if(mysqli_query($con,$sql))
{
  echo json_encode([
    'idResp'=>0,
    'Mensaje'=> 'Registro actualizado satisfactoriamente'
  ]);
}else{
  echo json_encode([
    'idResp'=>1,
    'Mensaje'=> 'Ocurrio un error al intentar registrar',
    'sql'=> $sql
  ]);
}

}

?>