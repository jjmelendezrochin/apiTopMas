<?php

require '../database.php';

date_default_timezone_set('America/Mexico_City');

// Get the posted data.
//$postdata = file_get_contents("php://input");

//echo 'POST DATA' . $postdata;

//if(isset($postdata) && !empty($postdata))
if ($_POST)
{
  // Extract the data.
    //$request = json_decode($postdata);
    extract($_POST);
    // Sanitize.
    $FechaInicial = mysqli_real_escape_string($con, trim($FechaInicial));
    $FechaFinal =  mysqli_real_escape_string($con, trim($FechaFinal));
    $idcadena = mysqli_real_escape_string($con, (int)$idcadena);

    $consulta = "";

    $consulta = "Select ifnull(a.Fecha ,'N/A') Fecha, ifnull(a.Promotor,'N/A') Promotor, 
        v.formato, v.Tienda, ifnull(a.Actividad,'N/A') Actividad, 
        ifnull(a.FechaHora,'N/A') FechaHora, 
        ifnull(a.Distancia_m,'N/A') Distancia_m, v.nombrecorto, v.idcadena 
    from vw_cadena_formato_ruta v
    left join (    
      Select  cast(f.FechaHora as date)  as Fecha, 
      Concat(p.nombre, ' ', p.apellidos) as Promotor,
      fo.formato, r.Tienda,
      o.descripcion AS Actividad, 
      FechaHora,
      concat(Fn_DistanciaEntreLatLongs(f.latitud,f.longitud,r.latitud,r.longitud),' metros') AS Distancia_m,
      c.nombrecorto, c.idcadena, fo.idformato, r.idruta
      from photos f 
      inner join cat_promotor p on f.idpromotor=p.idpromotor
      inner join cat_rutas r on f.idruta = r.idruta
      inner join cat_formato fo on r.idformato = fo.idformato 
      inner join cat_cadena c on r.idcadena = c.idcadena
      inner join cat_operacion o on f.idoperacion = o.idoperacion 
      where Cast(f.FechaHora as Date) between  '" . $FechaInicial . "' and '". $FechaFinal.  "'
    ) as a on v.idcadena = a.idcadena and v.idformato = a.idformato and v.idruta = a.idruta";

    if($idcadena>0){
        $consulta .= " \n where v.idcadena = " . $idcadena;
    }

    $consulta .= " \n order by 5 asc;";


    if($result1 = mysqli_query($con,$consulta)){
      $i = 0;
    while($row = mysqli_fetch_assoc($result1))
    {
        $cadenas_regs[$i]['Fecha'] = $row['Fecha'];
        $cadenas_regs[$i]['Promotor'] = $row['Promotor'];
        $cadenas_regs[$i]['formato'] = $row['formato'];
        $cadenas_regs[$i]['Tienda'] = $row['Tienda'];
        $cadenas_regs[$i]['Actividad'] = $row['Actividad'];
        $cadenas_regs[$i]['FechaHora'] = $row['FechaHora'];
        $cadenas_regs[$i]['Distancia_m'] = $row['Distancia_m'];
        $cadenas_regs[$i]['Cadena'] = $row['nombrecorto'];
        $i++;
    }
    $cadenas = ['Cadenas' => $cadenas_regs];
    echo json_encode($cadenas_regs);
    }
    else{
      echo json_encode(array(['sql'=>mysqli_error($con)]));
    }

}
 else {
     echo "no";
 }
?>

