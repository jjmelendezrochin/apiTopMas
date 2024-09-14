<?php
/**
 * Regresa el usuario.
 */
require '../database.php';
//echo 'inicio';
date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
    // Extract the data.
    $request = json_decode($postdata);
    //echo 'si';
    
    

    // Sanitize Parameters
    $FechaInicial = mysqli_real_escape_string($con, trim($request->FechaInicial));
    $FechaFinal = mysqli_real_escape_string($con, trim($request->FechaFinal));
    $Tienda = mysqli_real_escape_string($con, (int)sizeof($request->Tienda));
    $idpromotor = mysqli_real_escape_string($con, (int)sizeof($request->idpromotor));
    $Actividad = mysqli_real_escape_string($con, (int)sizeof($request->Actividad));
    $idEmpresa =  mysqli_real_escape_string($con, (int)$request->idEmpresa);  
	
	$condicion = "";
	$condicion1 = "";
	$condicion2 =  $idEmpresa;
    $cat = [];
	 
/*    $FechaInicial = date_format($_GET['FechaInicial'],'Y/m/d');
    $FechaFinal = date_format($_GET['FechaFinal'],'Y/m/d');
    $Tienda = $_GET['Tienda'];
    $idpromotor = $_GET['idpromotor'];*/
   
    /*
    $FechaInicial = mysqli_real_escape_string($con, trim($request->FechaInicial));
    $FechaFinal = mysqli_real_escape_string($con, trim($request->FechaFinal));
    $Tienda = mysqli_real_escape_string($con, (int)trim($request->Tienda));
    $idpromotor = mysqli_real_escape_string($con, (int)$request->idpromotor);
    */
    
    if(intval($Tienda) > 0){
		$Tienda = "";
	/*Construye estructura valida para el array in sql*/
    foreach($request->Tienda as $_tienda){
	 if($_tienda != "0"){
	 $Tienda .= "" . $_tienda . ",";
	 }
    }

       $Tienda = substr($Tienda,0,strlen($Tienda)-1);//Remueve la ultima coma del final de la cadena
        if($Tienda != ""){
	    $condicion.=" and ci.idruta in(" . $Tienda . ") ";
        $condicion1.=" and cot.idruta in(" . $Tienda . ") ";
		}
    }

    if(intval($idpromotor) > 0){
	$idpromotor = "";
	/*Construye estructura valida para el array in sql*/
    foreach($request->idpromotor as $_idpromotor){
	 if($_idpromotor != "0"){
	 $idpromotor .= "" . $_idpromotor . ",";
	 }
    }

       $idpromotor = substr($idpromotor,0,strlen($idpromotor)-1);//Remueve la ultima coma del final de la cadena
	   if($idpromotor != ""){
        $condicion.=" and ci.idpromotor in(" . $idpromotor . ") ";
        $condicion1.=" and cot.idpromotor in(" .$idpromotor . ") ";
	   }
    }

    if(intval($Actividad) > 0){
		$Actividad = "";
	/*Construye estructura valida para el array in sql*/
    foreach($request->Actividad as $_actividad){
	 if($_actividad != "0"){
	 $Actividad .= "" . $_actividad . ",";
	 }
    }

       $Actividad = substr($Actividad,0,strlen($Actividad)-1);//Remueve la ultima coma del final de la cadena
	   if($Actividad != ""){
        $condicion.=" and ci.idoperacion in(" . $Actividad . ") ";
        $condicion1.=" and cot.idoperacion in(" . $Actividad . ") ";
	   }
    }
    //$usuario = [];
	$vw = "";
    if(intval($idEmpresa) > 0){
		$vw = "_" . $idEmpresa;
		$condicion2 = $idEmpresa;
    }

	$sql = "Select ci.idpromotor, ci.idruta, ci.nombre, ci.tienda, ci.Actividad, ci.FechaHora, ci.Distancia, 
    (CASE WHEN ifnull(co.FechaHora,'') = '' THEN '' ELSE 'Check out' END) as Actividad1, 
    ifnull(co.FechaHora,'') FechaHora1, ifnull(co.Distancia,'') as Distancia1,
	if(ifnull(co.FechaHora,'') = '','Activo','Completado') as Estatus,
    ifnull(TIMEDIFF(co.Fecha, ci.Fecha),'') as Estancia,ci.idoperacion
    from vw_reportedetalle_checkin" . $vw . " ci 
    LEFT join vw_reportedetalle_checkout" . $vw . " co on ci.idpromotor=co.idpromotor and ci.idruta = co.idruta 
    and Cast(ci.Fecha as date) = Cast(co.Fecha as date)
    where Cast(ci.Fecha as date) 
	between Cast('" . $FechaInicial . "' as date) and Cast('"  . $FechaFinal . "' as date) " .$condicion . 
	" order by  ci.nombre asc, ci.FechaHora desc";
	
	// $cat[0]['Sql']       =  $sql;
    // echo json_encode($cat);
	// return;


    //echo($sql);
    //echo ('<br>');
    if($result = mysqli_query($con,$sql))
    {
        $i = 0;
        while($row = mysqli_fetch_assoc($result))
        {
          $cat[$i]['Nombre']        =  $row['nombre'];
          $cat[$i]['Tienda']        =  $row['tienda'];
          $cat[$i]['Actividad']     =  $row['Actividad'];
          $cat[$i]['FechaHora']     =  $row['FechaHora'];
          $cat[$i]['Distancia']     =  $row['Distancia'];
          $cat[$i]['Actividad1']    =  $row['Actividad1'];
          $cat[$i]['FechaHora1']    =  $row['FechaHora1'];
          $cat[$i]['Distancia1']    =  $row['Distancia1'];
          $cat[$i]['Estancia']      =  $row['Estancia'];
          $cat[$i]['Estatus']       =  $row['Estatus'];
          $i++;
        }
	    //echo json_encode($cat);
    }
    else
    {
		$cat['sql'] = $sql; 
        echo json_encode($cat);
        //http_response_code(404);
    }
	
	
	$sql = "Select cot.idpromotor, cot.idruta, cot.nombre, cot.tienda, cot.Actividad, cot.FechaHora, cot.Distancia, 
    '' as Actividad1, '' AS FechaHora1, '' as Distancia1,'' as Estatus,'' as Estancia,
    cot.idoperacion 
    from vw_reportedetalle_otros" . $vw . " cot 
    where Cast(cot.Fecha as date) between Cast('" . $FechaInicial . "' as date) and Cast('"  . $FechaFinal . "' as date) " .$condicion1 . "
    order by 6 desc, 3 asc, 4 asc, 5 asc";
	if($result1 = mysqli_query($con,$sql))
    {
        //$i = 0;
        while($row = mysqli_fetch_assoc($result1))
        {
          $cat[$i]['Nombre']        =  $row['nombre'];
          $cat[$i]['Tienda']        =  $row['tienda'];
          $cat[$i]['Actividad']     =  $row['Actividad'];
          $cat[$i]['FechaHora']     =  $row['FechaHora'];
          $cat[$i]['Distancia']     =  $row['Distancia'];
          $cat[$i]['Actividad1']    =  $row['Actividad1'];
          $cat[$i]['FechaHora1']    =  $row['FechaHora1'];
          $cat[$i]['Distancia1']    =  $row['Distancia1'];
          $cat[$i]['Estancia']      =  $row['Estancia'];
          $cat[$i]['Estatus']       =  $row['Estatus'];
          $i++;
        }
	    echo json_encode($cat);
    }
    else
    {
		$cat['sql'] = $sql; 
        echo json_encode($cat);
        //http_response_code(404);
    }
	
	
	
	
	
	
}
else{
 echo 'O';
}
?>