<?php
require '../database.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);


  // Validate.
  if((int)$request->idpromotor < 1)
  {
    return http_response_code(400);
  }

  // Sanitize.
  $idpromotor = mysqli_real_escape_string($con, (int)$request->idpromotor);
  $observacion = mysqli_real_escape_string($con, trim($request->observacion));


$sql_verifica = "Select count(*) as cta from usuarios_sinactividad where idpromotor = '{$idpromotor}';";
  
  $cta = 0;
  
    if($result = mysqli_query($con,$sql_verifica)){
		while($row = mysqli_fetch_assoc($result))
        {
		  $cta = intval($row['cta']);			
        }
    }

  if($cta == 0){
  //Crea  
  $sql = "insert into usuarios_sinactividad(idusuariossinact,idpromotor,fecha,observaciones) values(null,'{$idpromotor}',cast(now() as date),'{$observacion}');";
  }else{
  //Actualiza 
  $sql = "update usuarios_sinactividad set observaciones = '{$observacion}', fecha = cast(now() as date) where idpromotor = '{$idpromotor}';";  
  }

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $cat = [
      'idpromotor' => $idpromotor,
      'observaciones' => $observacion,
      'idusuariossinact'    => mysqli_insert_id($con)
    ];
    echo json_encode($cat);
  }
  else
  {
    http_response_code(422);
  }
}
?>