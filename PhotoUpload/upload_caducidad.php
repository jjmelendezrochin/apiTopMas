<?php
    require_once('../database.php');
    // *****************************
    // 
    // Estableciendo zona horario de México
    date_default_timezone_set('America/Mexico_City');
    $cta = 0;

	if($_SERVER['REQUEST_METHOD']=='POST'){		                        
            $idproducto     = $_POST['idproducto'];
            $lote           = $_POST['lote'];
            $caducidad      = $_POST['caducidad'];            
            $piezas         = $_POST['piezas'];
            
            $idruta         = $_POST['idruta'];                        
            $idpromotor     = $_POST['idpromotor'];            
            $latitud        = $_POST['latitud'];
            $longitud       = $_POST['longitud'];
            $idusuario      = $_POST['idusuario'];
            $idoperacion    = $_POST['idoperacion'];
            
            $image          = $_POST['image'];
            $version_app    = $_POST['version_app'];
            $sindatos       = $_POST['sindatos'];
                                    
            $idusuario = 0;
            // *************************************
            // Obtener los datos del promotor   
            $sql = " Select IDUSUARIO from cat_promotor cp where idpromotor = " . $idpromotor;
            
            $res = mysqli_query($con,$sql);
            while($row = mysqli_fetch_array($res))
            {
                $idusuario = $row['IDUSUARIO'];
            }
                   
            // *************************************
            // Obtener los datos del promotor   
            $sql = " SELECT TIENDA as Tienda FROM vw_tiendas WHERE IDRUTA  = " . $idruta;
            
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
                            
            // *************************************
            // Verifica si es una carga extemporanea
            if(is_null($_POST['sindatos']))
            {
                $sindatos = '0';
            }
            else{
                $sindatos = $_POST['sindatos'];
            }
            

            // *******************************
            // Verifica si trae datos la peticion
            if ($idpromotor==0  or $latitud==0 or $longitud==0 or $idoperacion==0 or $idruta==0)
            {
                    echo "Los datos no pudieron ser enviados, favor de intentar nuevamente ";
                    return;
            }

            // ******************************
            // Consulta id
            $sql ="SELECT Max(id)+1 as id FROM photos ORDER BY id ASC";
            $res = mysqli_query($con,$sql);
            $id = 0;
            while($row = mysqli_fetch_array($res)){
                $id = $row['id'];
            }

            $path = "fotos/" . $id . "_" . $idusuario . "_[". $tienda . "]_" . str_replace(":", ".", "") . "_[" .  $idoperacion . "]_.png";

            // *****************************
            // Ingresar al servidor correspondiente
            $servidor = $_SERVER['SERVER_NAME'];
            
            if ($servidor== "www.jjcorp.com.mx"){
                $actualpath = "http://www.jjcorp.com.mx/TopMkt/PhotoUpload/$path";
            }
            else {
                $actualpath = "http://www.topmas.mx/TopMas/PhotoUpload/$path";
            }           
            
            
            // *************************
            // Distancia mayor a un 1/4 de kilometro
            
            //if (intval($Distancia_m)>=2500000000000000000)
            if (intval($Distancia_m)>=250)
    		{
                 echo "El sitio desde donde tomo la foto rebasa la distancia permitida";
            	 return;
            }
             
            // *************************
            // Solo inserta si $cta = 0
            if($cta==0){
                // *************************************
                // si tiene fecha la solicitud la inserta en lugar de la del sistema
                // Insertando los datos    
                if($sindatos==0){
                    $sql = "INSERT INTO photos (image, idpromotor, latitud, longitud, idusuario, idoperacion, FechaHora, idruta, sindatos, appver) 
                            VALUES ('$actualpath', '$idpromotor', '$latitud', '$longitud', '$idusuario', '$idoperacion', CURRENT_TIMESTAMP, '$idruta', $sindatos, '$version_app')";
                }
                else{
                    $sql = "INSERT INTO photos (image, idpromotor, latitud, longitud, idusuario, idoperacion, FechaHora, idruta, sindatos, appver) 
                            VALUES ('$actualpath', '$idpromotor', '$latitud', '$longitud', '$idusuario', '$idoperacion', CURRENT_TIMESTAMP, '$idruta', $sindatos, '$version_app')";
                    
                }
                
                // *************************************
                // Insercion en la tabla competencia
                $sql1 = "INSERT INTO caducidad(idproducto, lote, caducidad, piezas, idruta, idpromotor, id) 
                        VALUES ('$idproducto', '$lote', '$caducidad', '$piezas', '$idruta', '$idpromotor', '$id')";

                if(mysqli_query($con,$sql)){                                    
                    if(mysqli_query($con,$sql1)){                        
                        file_put_contents($path,base64_decode($image));                    
                        echo "Imagen cargada correctamente";
                    }
                    else
                    {
                        echo "Error al insertar imagen " . $sql1;                    
                    }
                }
                else
                {
                    echo "Error al insertar imagen " . $sql;
                }
            }

            mysqli_close($con);
	}
        else{
		echo "Error";
	}
    ?>