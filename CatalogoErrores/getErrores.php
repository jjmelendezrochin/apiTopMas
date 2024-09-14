<?php
/**
 * Regresa el usuario.
 */
require '../database.php';
require '../config.php';

$condicion = "";
$cat = [];

date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
	// Extract the data.
    $request = json_decode($postdata);
	
	$FechaInicial = mysqli_real_escape_string($con, trim($request->FechaInicial));
	$FechaFinal = mysqli_real_escape_string($con, trim($request->FechaFinal));
	$Usuario = mysqli_real_escape_string($con, trim($request->Usuario));
	$Fabricante = mysqli_real_escape_string($con, trim($request->Fabricante));
	$Modelo = mysqli_real_escape_string($con, trim($request->Modelo));
	
	
	if($Usuario != ""){
	  $condicion = " and (usuario like '%" . $Usuario . "%')";
	 }
	
	 if($Fabricante != ""){
	  $condicion .= " and (fabricante like '%" . $Fabricante . "%')";
	 }
	  
	 if($Modelo != ""){
		$condicion .= " and (modelo like '%" . $Modelo . "%')";
	 }
	
	
	//$usuario = [];
	$sql = "Select idError,fabricante,marca,modelo,board,hardware,serie,uid,android_id,resolucion,tamaniopantalla,densidad,
			bootloader, user_value,host_value,version,api_value,build_id,build_time,fingerprint,usuario,error,fechahora,
			fechahora1,seccion, atendido,atendidopor,	versionsolucion,ifnull(solucion, 'N/A') as solucion 
			from errores  
			where iderror >= 1 and Cast(fechahora as date) 
			between Cast('" . $FechaInicial . "' as date) and Cast('"  . $FechaFinal . "' as date) " .$condicion . 
	   		" order by 1 desc;";
	//echo($sql);
	//echo ('<br>');
			 
	if($result = mysqli_query($con,$sql))
	{
	  $i = 0;
	  while($row = mysqli_fetch_assoc($result))
	  {
		$cat[$i]['iderror']   		=   $row['idError'];
		$cat[$i]['fabricante']   	=   $row['fabricante'];
		$cat[$i]['marca']   		=   $row['marca'];
		$cat[$i]['modelo']   		=   $row['modelo'];
		$cat[$i]['board']   		=   $row['board'];
		$cat[$i]['hardware']   		=   $row['hardware'];
		$cat[$i]['serie']   		=   $row['serie'];
		$cat[$i]['uid']   			=   $row['uid'];
		$cat[$i]['android_id']   	=   $row['android_id'];
		$cat[$i]['resolucion']   	=   $row['resolucion'];
		$cat[$i]['tamaniopantalla'] =   $row['tamaniopantalla'];
		$cat[$i]['densidad']   		=   $row['densidad'];
		$cat[$i]['bootloader']   	=   $row['bootloader'];
		$cat[$i]['user_value']   	=   $row['user_value'];
		$cat[$i]['host_value']   	=   $row['host_value'];
		$cat[$i]['version']   		=   $row['version'];
		$cat[$i]['api_value']   	=   $row['api_value'];
		$cat[$i]['build_id']   		=   $row['build_id'];
		$cat[$i]['build_time']   	=   $row['build_time'];
		$cat[$i]['fingerprint']   	=   $row['fingerprint'];
		$cat[$i]['usuario']   		=   $row['usuario'];
		$cat[$i]['error']   		=   $row['error'];
		$cat[$i]['fechahora']   	=   $row['fechahora'];
		$cat[$i]['fechahora1']   	=   $row['fechahora1'];
		$cat[$i]['seccion']   		=   $row['seccion'];
		$cat[$i]['idatendido']   		=   $row['atendido'];
		$cat[$i]['idatendidopor']   	=   $row['atendidopor'];
		$cat[$i]['versionsolucion'] =   $row['versionsolucion'];
		$cat[$i]['solucion']   		=   $row['solucion'];
		
		$i++;
	  }
	  //$cat[]['sql'] = $sql;
	  echo json_encode($cat);
	}
	else
	{
	  $i = 0;
	  $cat[$i]['sql']      =  $sql;        
	  echo $sql;    
	 // http_response_code(404);
	}
}
else{
 echo 'O';
}
?>