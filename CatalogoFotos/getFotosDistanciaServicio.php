<?php
/**
 * Regresa el usuario.
 */
require '../database.php';
date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
    // Extract the data.
    $request = json_decode($postdata);
    //echo 'si';
    
    $condicion = "";
    $cat = [];

    // Sanitize Parameters
    $FechaInicial = mysqli_real_escape_string($con, trim($request->FechaInicial));
    $FechaFinal = mysqli_real_escape_string($con, trim($request->FechaFinal));
    $Tienda = mysqli_real_escape_string($con, (int)trim($request->Tienda));
    $idpromotor = mysqli_real_escape_string($con, (int)$request->idpromotor);
    $Actividad = mysqli_real_escape_string($con, (int)$request->Actividad);
   
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
        $condicion.=" and ci.idruta = '{$Tienda}' ";
        $condicion1.=" and cot.idruta = '{$Tienda}' ";
    }

    if(intval($idpromotor) > 0){
        $condicion.=" and ci.idpromotor = '{$idpromotor}' ";
        $condicion1.=" and cot.idpromotor = '{$idpromotor}' ";
    }

    if(intval($Actividad) > 0){
        $condicion.=" and ci.idoperacion = '{$Actividad}' ";
        $condicion1.=" and cot.idoperacion = '{$Actividad}' ";
    }

    //$usuario = [];
    $sql = "Select ci.idpromotor, ci.idruta, ci.nombre, ci.tienda, ci.Actividad, ci.FechaHora, ci.Distancia, 
    (CASE WHEN ifnull(co.FechaHora,'') = '' THEN '' ELSE 'Check out' END) as Actividad1, 
    ifnull(co.FechaHora,'') FechaHora1, ifnull(co.Distancia,'') as Distancia1,if(ifnull(co.FechaHora,'') = '','Activo','Completado') as Estatus,
    ifnull(TIMEDIFF(co.Fecha, ci.Fecha),'') as Estancia,ci.idoperacion
    from vw_reportedetalle_checkin ci 
    LEFT join vw_reportedetalle_checkout co on ci.idpromotor=co.idpromotor and ci.idruta = co.idruta 
    and Cast(ci.Fecha as date) = Cast(co.Fecha as date)
    where Cast(ci.Fecha as date) between Cast('" . $FechaInicial . "' as date) and Cast('"  . $FechaFinal . "' as date) " .$condicion . "
    UNION
    Select cot.idpromotor, cot.idruta, cot.nombre, cot.tienda, cot.Actividad, cot.FechaHora, cot.Distancia, 
    '' as Actividad1, '' AS FechaHora1, '' as Distancia1,'' as Estatus,'' as Estancia,
    cot.idoperacion 
    from vw_reportedetalle_otros cot
    where Cast(cot.Fecha as date) between Cast('" . $FechaInicial . "' as date) and Cast('"  . $FechaFinal . "' as date) " .$condicion1 . "
    order by 6 desc, 3 asc, 4 asc, 5 asc";


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
        echo json_encode($cat);
    }
    else
    {
        $cat = ["sql" => $sql];
        echo json_encode($cat);
        //http_response_code(404);
    }
}
else{
 echo 'O';
}
?>