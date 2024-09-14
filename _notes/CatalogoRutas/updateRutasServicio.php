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
  if ((int)$request->idruta < 1/* || (int)$request->ruta < 1
   || (int)$request->determinante < 1 || (int)$request->idcadena < 1
   || trim($request->formato) == '' || trim($request->Tienda) == ''
   || trim($request->direccioncompleta) == '' || trim($request->idmunicipio) == ''
   || trim($request->idestado) == '' || (int)$request->cluster < 1 || trim($request->latitud) == '' 
   || trim($request->longitud) == ''*/) 
   {
    return http_response_code(400);
  }

  // Sanitize.
  $idruta    = mysqli_real_escape_string($con, (int)$request->idruta);
  $ruta    = mysqli_real_escape_string($con, (int)$request->ruta);
  $determinante    = mysqli_real_escape_string($con, (int)$request->determinante);
  $idcadena    = mysqli_real_escape_string($con, (int)$request->idcadena);
  $idformato = mysqli_real_escape_string($con, trim($request->idformato));
  $Tienda = mysqli_real_escape_string($con, trim($request->Tienda));
  $direccioncompleta = mysqli_real_escape_string($con, trim($request->direccioncompleta));
  $idmunicipio = mysqli_real_escape_string($con, trim($request->idmunicipio));
  $idestado = mysqli_real_escape_string($con, trim($request->idestado));
  $cluster    = mysqli_real_escape_string($con, $request->cluster);
  $intensidad = mysqli_real_escape_string($con, (int)$request->intensidad);  
  /*$uda = mysqli_real_escape_string($con, trim($request->uda));
  $fda = mysqli_real_escape_string($con, trim($request->fda));*/
  $udc = mysqli_real_escape_string($con, trim($request->udc));
  $fdc = date('Y-m-d H:i');
  $latitud = mysqli_real_escape_string($con, trim($request->latitud));
  $longitud = mysqli_real_escape_string($con, trim($request->longitud));

  // Update.
  $sql = "UPDATE `cat_rutas` "
          . "SET `ruta`='$ruta',`determinante`='$determinante',`idcadena`='$idcadena',"
          . "`idformato`='$idformato',`Tienda`='$Tienda',`direccioncompleta`='$direccioncompleta',"
          . "`idmunicipio`='$idmunicipio',`idestado`='$idestado',`cluster`='$cluster',`udc`='$udc',`fdc`='$fdc',"
          . "`latitud`='$latitud',`longitud`='$longitud',`intensidad`='$intensidad' "
          . "WHERE `idruta` = '{$idruta}' LIMIT 1";

  if(mysqli_query($con, $sql))
  {
    http_response_code(204);
  }
  else
  {
    return http_response_code(422);
  }  
}
?>