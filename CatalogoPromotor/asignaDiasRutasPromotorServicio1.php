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
  if ((int)$request->iddias < 1 || (int)$request->idpromotor < 1 || (int)$request->idruta < 1) {
    return http_response_code(400);
  }

  // Sanitize.
  $iddias   = mysqli_real_escape_string($con, (int)$request->iddias);
  $idpromotor    = mysqli_real_escape_string($con, (int)$request->idpromotor);
  $idruta    = mysqli_real_escape_string($con, (int)$request->idruta);
  $lunes = ($request->lunes!=NULL || $request->lunes != "0") ? mysqli_real_escape_string($con, (boolean)trim($request->lunes)):0;
  $martes = ($request->martes!=NULL || $request->martes != "0") ? mysqli_real_escape_string($con, (boolean)trim($request->martes)):0;
  $miercoles = ($request->miercoles!=NULL || $request->miercoles != "0") ? mysqli_real_escape_string($con, (boolean)trim($request->miercoles)):0;
  $jueves = ($request->jueves!=NULL || $request->jueves != "") ? mysqli_real_escape_string($con, (boolean)trim($request->jueves)):0;
  $viernes = ($request->viernes!=NULL || $request->viernes != "") ? mysqli_real_escape_string($con, (boolean)trim($request->viernes)):0;
  $sabado  = ($request->sabado!=NULL || $request->sabado != "") ? mysqli_real_escape_string($con, (boolean)$request->sabado):0;
  $domingo =  ($request->domingo!=NULL || $request->domingo != "") ? mysqli_real_escape_string($con, (boolean)trim($request->domingo)):0;
  $lunesp = ($request->lunesp!=NULL || $request->lunesp != "") ? mysqli_real_escape_string($con, (int)trim($request->lunesp)): 0;
  $martesp = ($request->martesp!=NULL || $request->martesp != "") ? mysqli_real_escape_string($con, (int)trim($request->martesp)): 0;
  $miercolesp = ($request->miercolesp!=NULL || $request->miercolesp != "") ? mysqli_real_escape_string($con, (int)trim($request->miercolesp)): 0;
  $juevesp = ($request->juevesp!=NULL || $request->juevesp != "") ? mysqli_real_escape_string($con, (int)trim($request->juevesp)): 0;
  $viernesp = ($request->viernesp!=NULL || $request->viernesp != "") ? mysqli_real_escape_string($con, (int)trim($request->viernesp)): 0;
  $sabadop = ($request->sabadop!=NULL || $request->sabadop != "") ? mysqli_real_escape_string($con, (int)trim($request->sabadop)): 0;
  $domingop = ($request->domingop!=NULL || $request->domingop != "") ? mysqli_real_escape_string($con, (int)trim($request->domingop)): 0;

  // Create.
$sql = "UPDATE rutas_promotor_dias 
set idpromotor='{$idpromotor}',idruta='{$idruta}',
lunes={$lunes},
martes={$martes},
miercoles={$miercoles},
jueves={$jueves},
viernes={$viernes},
sabado={$sabado},
domingo={$domingo},
lunesp={$lunesp},
martesp={$martesp},
miercolesp={$miercolesp},
juevesp={$juevesp},
viernesp={$viernesp},
sabadop={$sabadop},
domingop={$domingop} 
where iddias={$iddias} LIMIT 1;";

  if(mysqli_query($con,$sql))
  {
    http_response_code(204);
  }
  else
  {
	echo  json_encode($sql);
    //http_response_code(422);
  }
}
?>