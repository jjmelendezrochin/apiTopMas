<?php
require '../database.php';

date_default_timezone_set('America/Mexico_City');


  // Validate.
  $idsupervisor = ($_GET['idsupervisor'] !== null && (int)$_GET['idsupervisor'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idsupervisor']) : false;
  $idpromotor = ($_GET['idpromotor'] !== null && (int)$_GET['idpromotor'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idpromotor']) : false;
  $uda = ($_GET['uda'] !== null && $_GET['uda'] !='')? mysqli_real_escape_string($con, $_GET['uda']) : false;

  if(!$idsupervisor || !$idpromotor){
    return http_response_code(400);
  }


  // Sanitize.
  $fa = date('Y-m-d H:i');
  
  // Create.
  $sql = "INSERT INTO `promotores_supervisor`(`idpromotorasignado`,`idsupervisor`,`idpromotor`,`uda`,`fechaasignacion`,`idestatus`) 
  VALUES (null,'{$idsupervisor}','{$idpromotor}','{$uda}','{$fa}','1');";

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $catcadena = [
      'idsupervisor' => $idsupervisor,
      'idpromotor' => $idpromotor,
      'fechaasignacion' => $fa,
      'idpromotorasignado'    => mysqli_insert_id($con)      
    ];
    echo json_encode($catcadena);
  }
  else
  {
    $catcadena = ["sql"=>$sql];
    echo json_encode($catcadena);
//    http_response_code(422);
  }

?>