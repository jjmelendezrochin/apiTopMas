<?php
    require_once('../database.php');
    // *****************************
    // 
    // Estableciendo zona horario de México
    date_default_timezone_set('America/Mexico_City');
    $cta = 0;

	if($_SERVER['REQUEST_METHOD']=='POST')
	{		                        
		$fabricante 		= $_POST['fabricante'];
		$marca 				= $_POST['marca'];
		$modelo 			= $_POST['modelo'];
		$board 				= $_POST['board'];
		$hardware 			= $_POST['hardware'];
		$serie 				= $_POST['serie'];
		$uid 				= $_POST['uid'];
		$android_id 		= $_POST['android_id'];
		$resolucion 		= $_POST['resolucion'];
		$tamaniopantalla 	= $_POST['tamaniopantalla'];
		$densidad 			= $_POST['densidad'];
		$bootloader 		= $_POST['bootloader'];
		$user_value 		= $_POST['user_value'];
		$host_value 		= $_POST['host_value'];
		$version 			= $_POST['version'];
		$api_value 			= $_POST['api_value'];
		$build_id 			= $_POST['build_id'];
		$build_time 		= $_POST['build_time'];
		$fingerprint 		= $_POST['fingerprint'];
		$usuario 			= $_POST['usuario'];
		$seccion 			= $_POST['seccion'];
		$error				= $_POST['error'];
		$fechahora			= $_POST['fechahora'];
                                        
		// *************************************
		// Insercion en la tabla competencia
		$sql1 = "INSERT INTO errores
				(fabricante, marca, modelo, board, hardware, serie, uid, 
				android_id, resolucion, tamaniopantalla, 
				densidad, bootloader, user_value, host_value, 
				version, api_value, build_id, build_time, 
				fingerprint, usuario, seccion, error, fechahora) 
				VALUES ('$fabricante', '$marca', '$modelo', '$board', '$hardware', '$serie', '$uid', 
				'$android_id', '$resolucion', '',
				'$densidad', '$bootloader', '$user_value', '$host_value', 
				'$version', '$api_value', '$build_id', '$build_time', 
				'$fingerprint', '$usuario', '$seccion','$error', '$fechahora');";


		$Hora = date("d-m-Y H:i:s");
		$file = fopen("log_errores_" . date("dmY") . ".txt", "a");
		fwrite($file,  PHP_EOL . PHP_EOL);
		fwrite($file,  '*****************************************************
		// Registro de error'. PHP_EOL);
		fwrite($file,  $sql1 . PHP_EOL . PHP_EOL);
		fclose($file);

		// $bitacora = "Insert into bitacora (instruccion) values (' Valor de orden " . $orden . " valor de ordenamiento" . $ordenamiento . "')";
		$bitacora = "Insert into bitacora (instruccion) values ('" . str_replace("'", "|", $sql1) . "')";
		mysqli_query($con,$bitacora);

		if(mysqli_query($con,$sql1))
		{
			echo "1";
		}
		else
		{
			echo "0";                    
		}
	
        mysqli_close($con);
	}
    else{
		echo "0";
	}
 ?>