<?php

require '../database.php';

date_default_timezone_set('America/Mexico_City');


  // Validate.  
  $idpromotor = ($_GET['idpromotor'] !== null && (int)$_GET['idpromotor'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idpromotor']) : 209;
  $idruta = ($_GET['idruta'] !== null && (int)$_GET['idruta'] > 0)? mysqli_real_escape_string($con, (int)$_GET['idruta']) : 16;
    $dia = ($_GET['dia'] !== null && trim($_GET['dia']) !== '')? mysqli_real_escape_string($con, trim($_GET['dia'])) : '2020-10-07';  
  $observaciones = ($_GET['observaciones'] !== null && trim($_GET['observaciones']) !== '')? mysqli_real_escape_string($con, trim($_GET['observaciones'])) : 'obs1';  
  $uda = ($_GET['uda'] !== null && $_GET['uda'] !='')? mysqli_real_escape_string($con, $_GET['uda']) : 'jose';
  $asiste = ($_GET['asiste'] !== null && $_GET['asiste'] !='')? mysqli_real_escape_string($con, $_GET['asiste']) : '1';

  if(!$idpromotor || !$idruta || !$dia){
	return http_response_code(400);
  }

$sql ="select count(*) as cta from rutas_promotor_temporal where idpromotor = '{$idpromotor}' and idruta = '{$idruta}' and idestatus = 1;";

$cta = 0;

  if($result = mysqli_query($con,$sql))
  {
    while($row = mysqli_fetch_assoc($result))
    {
		$cta = intval($row['cta']);
    }
  }

  // Sanitize.
  $fa = date('Y-m-d H:i');
  $dia = date_format(date_create($dia),'Y-m-d');
  
  //if($cta == 0)
  {
  //Crea
  $sql = "INSERT INTO `rutas_promotor_temporal`(`idrutaasignada`,`idpromotor`,`idruta`,`dia`,`uda`,`fechaasignacion`,`asiste`,`observaciones`,`idestatus`) 
  VALUES (null,'{$idpromotor}','{$idruta}',cast('{$dia}' as date),'{$uda}','{$fa}','{$asiste}','{$observaciones}','1');";
  }
  /*
  else{
  //Actualiza
  $sql = "UPDATE `rutas_promotor_temporal` SET `dia` = cast('{$dia}' as date), `observaciones` = '{$observaciones}', `uda` = '{$uda}',`asiste` = '{$asiste}',`fechaasignacion` = '{$fa}',`idestatus` = '1' where idpromotor = '{$idpromotor}' and idruta = '{$idruta}';";
  }
  */
  
  
  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $catcadena = [
      'idpromotor' => $idpromotor,
      'idruta' => $idruta,
      'fechaasignacion' => $fa,
	  'dia' => $dia,
	  'observaciones' => $observaciones,
      'idrutaasignada'    => mysqli_insert_id($con)      
    ];
    echo json_encode($catcadena);
  }
  else
  {
	 echo json_encode(['sql' => $sql]); 
    //http_response_code(422);
  }

?>