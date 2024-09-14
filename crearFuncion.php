<?php
require 'database.php';


  // Sanitize.
  $number = mysqli_real_escape_string($con, trim($request->number));
  $amount = mysqli_real_escape_string($con, (int)$request->amount);


  // Create.
  $sql_drop = "DROP FUNCTION IF Exists `Fn_DistanciaEntreLatLongs`;";
  $sql = "CREATE FUNCTION `Fn_DistanciaEntreLatLongs`(lat1 double, lng1 double,lat2 double,lng2 double) RETURNS double
  BEGIN
    -- Funcion que regresa el númerio de dias de cada mes   
      DECLARE R int;
      DECLARE dLat double;    
      DECLARE dLon double;    
      DECLARE a double;
      DECLARE c double;            
      DECLARE d double;
  
      SET R = 6371;
      SET dLat = (((lat2-lat1) * PI()) /180);    
      SET dLon = (((lng2-lng1) * PI()) /180);
      SET a = (sin(dLat / 2) * sin(dLat / 2)) + (cos(lat1) * cos(lat2)) * (sin(dLon / 2) * sin(dLon / 2));    
      SET c = 2 * atan2(SQRT(a), SQRT(1-a));    
      SET d = round((R * c) * (1000 / 1));
       
      RETURN d;
  END;  
  /*CREATE FUNCTION `Fn_DistanciaEntreLatLongs`(lat1 double, lng1 double,lat2 double,lng2 double) RETURNS double
  BEGIN
    -- Funcion que regresa el númerio de dias de cada mes   
      DECLARE R int;
      DECLARE dLat double;    
      DECLARE dLon double;    
      DECLARE a double;
      DECLARE c double;            
      DECLARE d double;
  
      SET R = 6371;
      SET dLat = ((lat2-lat1) * PI() / 180);    
      SET dLon = ((lng2-lng1) * PI() / 180);
      SET a = sin(dLat / 2) * sin(dLat / 2) + cos(((lat1) * PI() / 180)) * cos(((lat2) * PI() / 180)) * sin(dLon / 2) * sin(dLon / 2);    
      SET c = 2 * atan2(SQRT(a), SQRT(1-a));    
      SET d = round((R * c) * (1000 / 1));
       
      RETURN d;
  END;*/";

  if(mysqli_query($con,$sql_drop))
  {   
    echo "Funcion eliminada";
  }
  else
  {
    echo $sql;
  }

  if(mysqli_query($con,$sql))
  {   
    echo "Funcion creada";
  }
  else
  {
    echo $sql;
  }

?>