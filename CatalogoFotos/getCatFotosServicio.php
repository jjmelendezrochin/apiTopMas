<?php

/**
 * Regresa el usuario.
 */
require '../database.php';

date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $request = json_decode($postdata);

    $condicion = "";
    $cat = [];
// Obteniendo la empresa
    /* $idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


      if(!$idEmpresa)
      {
      return http_response_code(400);
      } */

    if (trim($request->FechaInicial) === '' && trim($request->FechaFinal) === '') {
        return http_response_code(400);
    }

// Sanitize.
    $FechaInicial = mysqli_real_escape_string($con, trim($request->FechaInicial));
    $FechaFinal = mysqli_real_escape_string($con, trim($request->FechaFinal));
    $idoperacion = mysqli_real_escape_string($con, (int) $request->idoperacion);
    $idpromotor = mysqli_real_escape_string($con, (int) $request->idpromotor);
    $Tienda = mysqli_real_escape_string($con, trim($request->Tienda));
    $idcadena = mysqli_real_escape_string($con, (int) $request->idcadena);
    $orden = mysqli_real_escape_string($con, (int) $request->orden);
//$orden=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : 0;


    if (intval($idoperacion) > 0) {
        $condicion .= " and p.idoperacion='{$idoperacion}' ";
    }

    if ($Tienda != "0") {
        $condicion .= " and cr.Tienda='{$Tienda}' ";
    }

    if (intval($idpromotor) > 0) {
        $condicion .= " and p.idpromotor='{$idpromotor}' ";
    }

    if (intval($idcadena) > 0) {
        $condicion .= " and cr.idcadena='{$idcadena}' ";
    }

    if (intval($orden) == 0) {
        $ordenamiento = " order by p.FechaHora desc";
    } else {
        $ordenamiento = " order by p.FechaHora asc";
    }

//******************************
// Insercion en bitacora
    $bitacora = "Insert into bitacora (instruccion) values (' Valor de orden " . $orden . " valor de ordenamiento" . $ordenamiento . "')";
    mysqli_query($con, $bitacora);

// ****************************
//******************************
// Verificando si es promotor o supervisor
    $sql0 = "Select tipo from cat_promotor where idpromotor = " . $idpromotor;
    if ($result = mysqli_query($con, $sql0)) {
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $tipo = $row['tipo'];
        }
    } else {
        http_response_code(404);
    }

    if ($tipo == 0) {
        // Tipo promotor
        $objeto = " LEFT JOIN rutas_promotor rp ON cp.idpromotor = rp.idpromotor";
    } else {
        // Tipo supervisor
        $objeto = " LEFT JOIN vw_rutas_supervisor rp ON cp.idpromotor = rp.idsupervisor";
    }

//******************************
    if ($idpromotor == 165) {
        $sql = "SELECT distinct p.image as foto,
  p.idpromotor,
  p.latitud,
  p.longitud,
  p.idusuario,
  p.idruta,
  concat(cp.nombre,' ',cp.apellidos,' [',cp.idusuario,'] (', p.appver , ')', ' {' , (case when p.sindatos=0 Then 'CONECTADO' ELSE  'DESCONECTADO' end), '} ' ,
  ifnull(com.producto,''), ' ' ,  ifnull(com.presentacion,'') , ' ' , ifnull(com.precio,''), ' ' , ifnull(ac.actividad,'')) as promotor,
  concat(cc.nombrecorto, ' ', cf.formato, ' ' , cr.Tienda) as Tienda,
  cc.cadena,
  DATE_FORMAT(p.FechaHora, '%d/%m/%Y %H:%i:%s') as FechaHora,
  co.descripcion as actividad,
  cr.latitud as latitud_tienda,
  cr.longitud as longitud_tienda,
  p.latitud as latitud_ubicacion,
  p.longitud as longitud_ubicacion,
  cr.direccioncompleta as Direccion,
  concat(Fn_DistanciaEntreLatLongs(cr.latitud,cr.longitud,p.latitud,p.longitud),' metros') AS Distancia_m,
  Fn_DistanciaEntreLatLongs(cr.latitud,cr.longitud,p.latitud,p.longitud) AS Distancia
  FROM photos p
  LEFT JOIN cat_promotor cp ON p.idpromotor=cp.idpromotor
  LEFT JOIN cat_rutas cr on cr.idruta = p.idruta 
  LEFT JOIN cat_cadena cc on cr.idcadena=cc.idcadena
  LEFT JOIN cat_formato cf on cr.idformato = cf.idformato
  LEFT JOIN cat_operacion co on p.idoperacion=co.idoperacion
  LEFT JOIN competencia com on p.id = com.idfoto 
  LEFT JOIN cat_actividad ac on com.idactividad = ac.idactividad  
  where not cr.Tienda is null 
  and Cast(FechaHora as date) BETWEEN  cast('{$FechaInicial}' as date) AND cast('{$FechaFinal}' as date) "
                . $condicion . $ordenamiento;
    } else {
        $sql = "SELECT distinct p.image as foto,
  p.idpromotor,
  p.latitud,
  p.longitud,
  p.idusuario,
  p.idruta,
  concat(cp.nombre,' ',cp.apellidos,' [',cp.idusuario,'] (', p.appver , ')', ' {' , (case when p.sindatos=0 Then 'CONECTADO' ELSE  'DESCONECTADO' end), '} ' ,
  ifnull(com.producto,''), ' ' ,  ifnull(com.presentacion,'') , ' ' , ifnull(com.precio,''), ' ' , ifnull(ac.actividad,'')) as promotor,
  concat(cc.nombrecorto, ' ', cf.formato, ' ' , cr.Tienda) as Tienda,
  cc.cadena,
  DATE_FORMAT(p.FechaHora, '%d/%m/%Y %H:%i:%s') as FechaHora,
  co.descripcion as actividad,
  cr.latitud as latitud_tienda,
  cr.longitud as longitud_tienda,
  p.latitud as latitud_ubicacion,
  p.longitud as longitud_ubicacion,
  cr.direccioncompleta as Direccion,
  concat(Fn_DistanciaEntreLatLongs(cr.latitud,cr.longitud,p.latitud,p.longitud),' metros') AS Distancia_m,
  Fn_DistanciaEntreLatLongs(cr.latitud,cr.longitud,p.latitud,p.longitud) AS Distancia
  FROM photos p
  LEFT JOIN cat_promotor cp ON p.idpromotor=cp.idpromotor
  " . $objeto . "
  LEFT JOIN cat_rutas cr on rp.idruta = cr.idruta and p.idruta = cr.idruta
  LEFT JOIN cat_cadena cc on cr.idcadena=cc.idcadena
  LEFT JOIN cat_formato cf on cr.idformato = cf.idformato
  LEFT JOIN cat_operacion co on p.idoperacion=co.idoperacion
  LEFT JOIN competencia com on p.id = com.idfoto 
  LEFT JOIN cat_actividad ac on com.idactividad = ac.idactividad 
  where not cr.Tienda is null 
  and Cast(FechaHora as date) BETWEEN  cast('{$FechaInicial}' as date) AND cast('{$FechaFinal}' as date) "
                . $condicion . $ordenamiento;
    }
//******************************
// Insercion en bitacora
    $bitacora = "Insert into bitacora (instruccion) values ('" . str_replace("'", "|", $sql) . "')";
    mysqli_query($con, $bitacora);

// ****************************

    if ($result = mysqli_query($con, $sql)) {
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $cat[$i]['foto'] = $row['foto'];
            $cat[$i]['promotor'] = strtoupper($row['promotor']);
            $cat[$i]['Tienda'] = $row['Tienda'];
            $cat[$i]['FechaHora'] = $row['FechaHora'];
            $cat[$i]['actividad'] = $row['actividad'];
            $cat[$i]['Distancia'] = $row['Distancia_m'];
            $cat[$i]['latitud_tienda'] = $row['latitud_tienda'];
            $cat[$i]['longitud_tienda'] = $row['longitud_tienda'];
            $cat[$i]['latitud_ubicacion'] = $row['latitud_ubicacion'];
            $cat[$i]['longitud_ubicacion'] = $row['longitud_ubicacion'];
            $cat[$i]['Direccion'] = $row['Direccion'];
            $cat[$i]['sql'] = $sql; 
            $i++;
        }

        echo json_encode($cat);
    } else {

        http_response_code(404);
    }
}
?>