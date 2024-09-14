<?php
require '../database.php';

date_default_timezone_set('America/Mexico_City');


  // Validate.
  $idpromotor = ($_GET['idpromotor'] !== null && (int)$_GET['idpromotor'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idpromotor']) : false;
  $idruta = ($_GET['idruta'] !== null && (int)$_GET['idruta'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idruta']) : false;
  $uda = ($_GET['uda'] !== null && $_GET['uda'] !='')? mysqli_real_escape_string($con, $_GET['uda']) : false;

  if(!$idpromotor || !$idruta){
    return http_response_code(400);
  }


  // Sanitize.
  $fa = date('Y-m-d H:i');
  
  // Create.
  $sql = "INSERT INTO `rutas_promotor`(`idrutaasignada`,`idpromotor`,`idruta`,`uda`,`fechaasignacion`,`idestatus`) 
  VALUES (null,'{$idpromotor}','{$idruta}','{$uda}','{$fa}','1');";

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $catcadena = [
      'idpromotor' => $idpromotor,
      'idruta' => $idruta,
      'fechaasignacion' => $fa,
      'idrutaasignada'    => mysqli_insert_id($con)      
    ];
    echo json_encode($catcadena);
  }
  else
  {
    http_response_code(422);
  }

?>