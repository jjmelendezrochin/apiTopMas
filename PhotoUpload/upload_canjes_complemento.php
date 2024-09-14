<?php
    require_once('../database.php');
    // *****************************
    // 
	// Procedimiento utilizado para la carga de información de canjes
    // Estableciendo zona horario de México
    date_default_timezone_set('America/Mexico_City');

	if($_SERVER['REQUEST_METHOD']=='POST'){		            
            $idpromotor     		= $_POST['idpromotor'];
			$latitud        		= $_POST['latitud'];
            $longitud       		= $_POST['longitud'];
			$idusuario      		= $_POST['idusuario'];
            $idoperacion    		= $_POST['idoperacion'];
            $idruta         		= $_POST['idruta'];
            $fechahora      		= $_POST['fechahora'];						
			$image          		= $_POST['image'];
			$image1         		= $_POST['image1'];
			$llave	         		= $_POST['llave'];
			$comentarios         	= $_POST['comentarios'];
            
			$arregloproductos       = $_POST['arregloproductos'];            
			
            $version_app 			= $_POST['version_app'];
			$sindatos 				= $_POST['sindatos'];
			                        
            $idusuario = 0;
            // *************************************
            // Obtener los datos del usuario   
            $sql = " Select IDUSUARIO from cat_promotor cp where idpromotor = " . $idpromotor. ";";
						
            
            $res = mysqli_query($con,$sql);
            while($row = mysqli_fetch_array($res))
            {
                $idusuario = $row['IDUSUARIO'];
            }
						                   
            // *************************************
            // Obtener los datos del promotor   
            $sql = " SELECT TIENDA as Tienda FROM vw_tiendas WHERE IDRUTA  = " . $idruta . ";";

            
            $res = mysqli_query($con,$sql);
            while($row = mysqli_fetch_array($res))
            {
                $tienda = $row['Tienda'];
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
    			" values (" .$idoperacion. "," .$idpromotor . "," .$idruta . ",CURRENT_TIMESTAMP,'$Distancia_m');";
    			
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

            
            // *************************
            // Distancia mayor a un 1/4 de kilometro
            
            //if (intval($Distancia_m)>=2500000000000000000)
            if (intval($Distancia_m)>=250)
    		{
                 echo "El sitio desde donde tomo la foto rebasa la distancia permitida";
            	 return;
            }
            
            
            // *************************************
            // Verifica si es una carga extemporanea
            if(is_null($_POST['sindatos']))
            {
                $sindatos = '1';
            }
            else{
                $sindatos = $_POST['sindatos'];
            }


  			// *******************************
            // Obteniendo los datos de foto1 para saber el nombre y sobreescribir la imagen
            $sql = "Select idfoto1 from canjes where llave = '" . $llave . "'";
			$res = mysqli_query($con,$sql);
            $id = 0;
            while($row = mysqli_fetch_array($res)){
				$idfoto2 = $row['idfoto1'];
            }
	
			// Cambiando el nombre del archivo
            $path2 = "fotos/" . $idfoto2 . ".png";

            
			// *************************************
			// Función Log
			$Hora = date("d-m-Y H:i:s");
			$file = fopen("logs/log_canjes_" . date("dmY") . ".txt", "a");
			$texto = 'Datos canjes';
			fwrite($file, "[" . $Hora . "] " . PHP_EOL);
			fwrite($file, "[" . $Hora . "] ----------------------------" . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $texto . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $idfoto2 . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $llave . PHP_EOL);
			fwrite($file, "[" . $Hora . "] " . $path2 . PHP_EOL);					
			fwrite($file, "[" . $Hora . "] " . $sql . PHP_EOL);
			fwrite($file, "[" . $Hora . "] ----------------------------" . PHP_EOL);
			file_put_contents($path2,base64_decode($image1)); 
			echo "Imagen cargada correctamente";
			
			fclose($file);
			// *************************************
						
            mysqli_close($con);
	}
    else{
		echo "Error";
	}
 ?>