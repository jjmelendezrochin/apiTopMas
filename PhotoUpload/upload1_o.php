<?php
    require_once('../database.php');
    // *****************************
    // 
    // Estableciendo zona horario de México
    date_default_timezone_set('America/Mexico_City');
    $cta = 0;

	if($_SERVER['REQUEST_METHOD']=='POST'){		            
            $idpromotor     = $_POST['idpromotor'];
            $latitud        = $_POST['latitud'];
            $longitud       = $_POST['longitud'];
            $idusuario      = $_POST['idusuario'];
            $idoperacion    = $_POST['idoperacion'];
            $idruta         = $_POST['idruta'];
            $fechahora      = $_POST['fechahora'];
            $image          = $_POST['image'];

            $idusuario = 0;
            // *************************************
            // Obtener los datos del promotor   
            $sql = " Select IDUSUARIO from cat_promotor cp where idpromotor = " . $idpromotor;
            
            $res = mysqli_query($con,$sql);
            while($row = mysqli_fetch_array($res))
            {
                $idusuario = $row['IDUSUARIO'];
            }

            
			// if (strtolower($idusuario) == 'prueba1')
			{
                // *************************************
                // Inserciòn en bitacora
                $ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
                            " values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'Inserción bitácora');";
                
                mysqli_query($con,$ins);
			}
			

            // *************************************
            // Obtener los datos del promotor   
            $sql = " SELECT TIENDA as Tienda FROM vw_tiendas WHERE IDRUTA  = " . $idruta;
            
            $res = mysqli_query($con,$sql);
            while($row = mysqli_fetch_array($res))
            {
                $tienda = $row['Tienda'];
            }
			
            // if (strtolower($idusuario) == 'prueba1')
            {
    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'tienda $tienda');";
    			
    			mysqli_query($con,$ins);
            }
			
            {
                $ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
                " values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'latitud $latitud, longitud $longitud');";
                
                mysqli_query($con,$ins);
            }

            
            // *************************************
            // Obtener los datos del promotor
            $sql = "    Select concat(Fn_DistanciaEntreLatLongs(cr.latitud,cr.longitud, " . $latitud . "," . $longitud . "),' metros') AS Distancia_m "
                    . " from cat_rutas cr where cr.idruta = " . $idruta;
            
            $res = mysqli_query($con,$sql);
            while($row = mysqli_fetch_array($res))
            {
                $Distancia_m = $row['Distancia_m'];
            }
			
			// if (strtolower($idusuario) == 'prueba1')
            {
    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'distancia $Distancia_m');";
    			
    			mysqli_query($con,$ins);
			}
            

            // *************************************
            // Verifica si es una carga extemporanea
            if(is_null($_POST['version_app']))
            {
                $version_app = '0:0';
            }
            else{
                $version_app = $_POST['version_app'];
            }
			

            $version = explode(":", $version_app);
            $version_mayor=$version[0];
            $version_menor=$version[1];


            if ($version_app=="2" && $version_menor<"34")
            {
                echo "Debe de tener la versión 2:34 para poder subir imágenes";
                return;
            }

            // if (strtolower($idusuario) == 'prueba1')
            {			
    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'Versión $version_app');";
    			
    			mysqli_query($con,$ins);
            }
            
                            
            // *************************************
            // Verifica si es una carga extemporanea
            if(is_null($_POST['sindatos']))
            {
                $sindatos = '0';
            }
            else{
                $sindatos = $_POST['sindatos'];
            }
			
			$tipoconexion = ($sindatos=='0') ? "Con datos" : "Sin datos";
			
			// if (strtolower($idusuario) == 'prueba1')
            {
    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'tipoconexion $tipoconexion');";
    			
    			mysqli_query($con,$ins);
            }
            

            // *******************************
            // Verifica si trae datos la peticion
            if ($idpromotor==0  or $latitud==0 or $longitud==0 or $idoperacion==0 or $idruta==0)
            {

                    $error0 ="idoperacion: " . $idoperacion . ", latitud: " . $latitud . ", longitud: " . $longitud ;
					$error ="Los datos no pudieron ser enviados, favor de intentar nuevamente " . $error0;
					// if (strtolower($idusuario) == 'prueba1')
                    {
    					$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
    						   " values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'error $error');";
    			
    					mysqli_query($con,$ins);
                    }
					
					// echo $error;
                    // return;               
            }

            // ******************************
            // Consulta id
            $sql ="SELECT Max(id)+1 as id FROM photos ORDER BY id ASC";
            $res = mysqli_query($con,$sql);
            $id = 0;
            while($row = mysqli_fetch_array($res)){
                $id = $row['id'];
            }

            // $path = "uploads/" . $id . "_" . $idusuario . "_[". $tienda . "]_" . str_replace(":", ".", $fechahora) . "_[" .  $idoperacion . "]_.png";//
            $path = "fotos/" . $id . "_" . $idusuario . "_". str_replace(" ", "-", $tienda) . "_" . str_replace(":", ".", $fechahora) . "_" .  $idoperacion . "_.png";
            $path =str_replace(" ", "T", $path );

			// if (strtolower($idusuario) == 'prueba1')
            {
    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'ruta $path');";			

    			mysqli_query($con,$ins);
            }			

            // *****************************
            // Ingresar al servidor correspondiente
            $servidor = $_SERVER['SERVER_NAME'];
            
            if ($servidor== "www.jjcorp.com.mx"){
                $actualpath = "http://www.jjcorp.com.mx/TopMkt/PhotoUpload/$path";
            }
            else {
                $actualpath = "http://www.topmas.mx/TopMas/PhotoUpload/$path";
            }
            
            
			// if (strtolower($idusuario) == 'prueba1')
            {
    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'ruta actual $actualpath');";
                
                mysqli_query($con,$ins);
    			
    			            						
    			$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
                            " values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'operacion $idoperacion');";            
                mysqli_query($con,$ins);
            }
			
            // ******************************
            // Consulta si ya se habia hecho ckeckin o checkout el dia de hoy
             if ($idoperacion==1 or $idoperacion==2)
             {
                    $sql =" SELECT count(*) as cta FROM photos "
                        . " where idoperacion = " . trim($idoperacion) . " and idpromotor = " . trim($idpromotor) . " and idruta = " . trim($idruta)
                        . " and cast(FechaHora as Date) = Cast(current_timestamp() AS DATE)";
                        //echo $sql;

					// if (strtolower($idusuario) == 'prueba1')
                    {
        				 	$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
        							" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'$sql');";
        				 	mysqli_query($con,$ins);
                    }
 					
					
					$res = mysqli_query($con,$sql);
					while($row = mysqli_fetch_array($res))
					{
						$cta = $row['cta'];
					}
					
					// *********************************
					// Registra la cantidad de registros
					// if (strtolower($idusuario) == 'prueba1')
                        {
        					$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
                            " values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'cuenta $cta');";            
        		            mysqli_query($con,$ins);
                        }					

					if ($cta>0)
					{
                        // Este mensaje solo aplica para operaciones en linea
						$error = "Esta operación ya se habia realizado con anterioridad";
						
						// if (strtolower($idusuario) == 'prueba1')
                        {
        						$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
        					   " values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'error $error');";
        		
        						mysqli_query($con,$ins);  
                        }
						/**/
						echo "1";  // datos cargados exitosamente se pueden borrar
						// echo $error;
						return;
					}      
             }
			

         // *************************
        // Distancia mayor a un 1/4 de kilometro
        if( 
            (intval($Distancia_m)>=250)
        )
		{
            // *******************************
            // Inserción de datos           
            $mensaje = "El sitio desde donde tomo la foto rebasa la distancia permitida";
            $ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
                    " values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'" . str_replace("'","|",$mensaje) . "');";
                    
             mysqli_query($con,$ins);
             return;
        }
       
            
        // *************************
        // Solo inserta si $cta = 0
        if($cta==0){
            // *************************************
            // si tiene fecha la solicitud la inserta en lugar de la del sistema
            // Insertando los datos    
            if($sindatos==0){
                $sql = "INSERT INTO photos (image, idpromotor, latitud, longitud, idusuario, idoperacion, FechaHora, FechaHora1, idruta, sindatos, appver) 
                        VALUES ('$actualpath', '$idpromotor', '$latitud', '$longitud', '$idusuario', '$idoperacion', '$fechahora', current_timestamp , '$idruta', $sindatos, '$version_app')";
            }
            else{
                $sql = "INSERT INTO photos (image, idpromotor, latitud, longitud, idusuario, idoperacion, FechaHora, FechaHora1, idruta, sindatos, appver) 
                        VALUES ('$actualpath', '$idpromotor', '$latitud', '$longitud', '$idusuario', '$idoperacion', '$fechahora', current_timestamp, '$idruta', $sindatos, '$version_app')";																								         
            }

            // if (strtolower($idusuario) == 'prueba1')
                {				
    				// *******************************
    				// Inserción de datos			
    				$ins = " Insert into bitacora_fotos(idoperacion, idpromotor, idruta, FechaHora, instruccion) " .
    						" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'Datos cargados');";
    						
    				 mysqli_query($con,$ins);
                 }
			// *******************************				
			
            if(mysqli_query($con,$sql)){
                    file_put_contents($path,base64_decode($image));
                    echo "1";       // datos cargados exitosamente se pueden borrar
            }
            else{
                    echo "0";       // datos no cargados no se pueden borrar
            }
        }

        mysqli_close($con);
	}
        else{
		echo "0";                   // datos no cargados no se pueden borrar
	}
?>
