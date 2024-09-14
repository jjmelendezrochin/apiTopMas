<?php
/**
 * Regresa los datos de la rutas a las que debe de asistir el promotor
 */

require '../database.php';
date_default_timezone_set('America/Mexico_City');
$resultado = array();

// *****************************
// Extract, idpromotor y tienda
$idpromotor = ($_GET['idpromotor'] !== null && strlen($_GET['idpromotor']) > 0) ? mysqli_real_escape_string($con, (string) $_GET['idpromotor']) : false;
$tienda = ($_GET['tienda'] !== null && strlen($_GET['tienda']) > 0) ? mysqli_real_escape_string($con, (string) $_GET['tienda']) : false;
$idempresa = ($_GET['idempresa'] !== null && strlen($_GET['idempresa']) > 0) ? mysqli_real_escape_string($con, (string) $_GET['idempresa']) : false;
//echo 'idPromotor ' . $idpromotor;
if (!$idpromotor) {
    return http_response_code(400);
}

$serverName = $_SERVER['SERVER_NAME'];

// *****************************
// Verificando si es promotor o supervisor
$sql0 = "Select tipo from cat_promotor where idpromotor = " . $idpromotor . " and idempresa = '" . $idempresa . "'";
if ($result = mysqli_query($con, $sql0)) {
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $tipo = $row['tipo'];
    }
} else {
    http_response_code(404);
}

// *****************************
// Estableciendo huso horario
$sql0 = "SET time_zone = 'America/Mexico_City';";
$result = mysqli_query($con, $sql0);

$Hora = date("d-m-Y H:i:s");
$file = fopen("log_" . date("dmY") . ".txt", "a");
fwrite($file, PHP_EOL);
fwrite($file, PHP_EOL);
fwrite($file, PHP_EOL);
fwrite($file, '---------------------------------------------------------' . PHP_EOL. PHP_EOL. PHP_EOL);
fwrite($file, "[" . $Hora . "] " . $sql0 . PHP_EOL);
fwrite($file, 'Servidor ' . $serverName . PHP_EOL);
fclose($file);

// ******************************************
// Obteniendo la version de la App en la base de datos
$sql = "Select version, WEEKDAY(current_timestamp) as dia from versionapp;";
//echo $sql . "<br>";
if ($result = mysqli_query($con, $sql)) {
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $version 	= $row['version'];
        $dia 		= $row['dia'];		
    }
}

/*	$Hora = date("d-m-Y H:i:s");
    $file = fopen("log_" . date("dmY") . ".txt", "a");
	fwrite($file, "[" . $Hora . "] " . '// Obteniendo la version de la App en la base de datos
// $sql = "Select version, WEEKDAY(CURRENT_DATE()) as dia from versionapp;";'. PHP_EOL);
    fwrite($file, "[" . $Hora . "] " . $sql . PHP_EOL);
    fclose($file); */

//echo $version;
// *****************************
// Obteniendo el nùmero de dia
//$dia = date("w");
switch ($dia) {
    case 6:
        $criterio = " and rpd.domingo = 1";
        $criterio1 = " and rpd.domingo = 1";
        $orden = " order by 3, 14 asc";
        break;
    case 0:
        $criterio = " and rpd.lunes = 1";
        $criterio1 = " and rpd.lunes = 1";
        $orden = " order by 3, 15 asc";
        break;
    case 1:
        $criterio = " and rpd.martes = 1";
        $criterio1 = " and rpd.martes = 1";
        $orden = " order by 3, 16 asc";
        break;
    case 2:
        $criterio = " and rpd.miercoles = 1";
        $criterio1 = " and rpd.miercoles = 1";
        $orden = " order by 3, 17 asc";
        break;
    case 3:
        $criterio = " and rpd.jueves = 1";
        $criterio1 = " and rpd.jueves = 1";		
        $orden = " order by 3, 18 asc";
        break;
    case 4:
        $criterio = " and rpd.viernes = 1";
        $criterio1 = " and rpd.viernes = 1";		
        $orden = " order by 3, 19 asc";
        break;
    case 5:
        $criterio = " and rpd.sabado = 1";
        $criterio1 = " and rpd.sabado = 1";		
        $orden = " order by 3, 20 asc";
        break;
}

// *****************************
// Verificando si hay tienda
if (strlen($tienda) > 0) {
    $criterio1 = " and ((concat(cc.nombrecorto, ' ', cf.formato, ' ' , cr.Tienda) like '%" . $tienda . "%') or (cr.`direccioncompleta` like '%" . $tienda . "%'))";
    $criterio .= " and ((concat(cc.nombrecorto, ' ', cf.formato, ' ' , cr.Tienda) like '%" . $tienda . "%') or (cr.`direccioncompleta` like '%" . $tienda . "%'))";
}

// *****************************************************
// Consulta de acuerdo al tipo
// Tipo promotor
if ($tipo == 0) {
    $sql = "
    Select distinct
    cr.idruta, IFNULL(cr.determinante,0) as determinante,
    concat(IFNULL(cc.nombrecorto,''), ' ', IFNULL(cf.formato,''), ' ' , IFNULL(cr.Tienda,'')) as tienda,
    cr.`direccioncompleta`, cr.latitud, cr.longitud,
    rpd.lunes, rpd.martes, rpd.miercoles, rpd.jueves, rpd.viernes, rpd.sabado, rpd.domingo,
    rpd.lunesp, rpd.martesp, rpd.miercolesp, rpd.juevesp, rpd.viernesp, rpd.sabadop, rpd.domingop, 
    '" . $version . "' as version , CURDATE() as fecha 
    from cat_rutas cr inner join rutas_promotor rp on cr.idruta = rp.idruta
    left join rutas_promotor_dias rpd on rp.idpromotor = rpd.idpromotor and rp.idruta = rpd.idruta
    left join vw_consultastiendaspromotorfecha v on cr.idruta = v.idruta and  rp.idruta = v.idruta   and v.idpromotor='" . $idpromotor . "'  and cast(v.Fecha as date) = cast(curdate() AS DATE)
    left join cat_formato cf on cr.idformato = cf.idformato
    left join cat_cadena cc on cf.idcadena = cc.idcadena
    where rp.idpromotor = '" . $idpromotor . "' 
	and cr.idestatus = 1 
    and rp.idestatus = 1 
	and ifnull(v.Cta,0) <2 "
    . $criterio;
	
    
    // *******************************************************
    // Descartando las rutas agregadas para no visita temporal
    $sql .= "
    and cr.idruta not in (
    Select rpt.idruta from rutas_promotor_temporal rpt 
    where idpromotor='" . $idpromotor . "'
    and cast(current_date as date) = cast(dia as date) and asiste=0 and idestatus = 1)";

    // *******************************************************
    // Incorporando las rutas agregadas para visita temporal
    $sql .= " Union ";
    $sql .= " Select distinct
    cr.idruta, IFNULL(cr.determinante,0) as determinante,
    concat(IFNULL(cc.nombrecorto,''), ' ', IFNULL(cf.formato,''), ' ' , IFNULL(cr.Tienda,'')) as tienda,
    cr.`direccioncompleta`, cr.latitud, cr.longitud,
    1 as lunes, 1 as martes, 1 as miercoles, 1 as jueves, 1 as viernes, 1 as sabado, 1 as domingo,
    -1 as lunesp, -1 as martesp, -1 as miercolesp, -1 as juevesp, -1 as viernesp, -1 as sabadop, -1 as domingop,
    '" . $version . "' as version , CURDATE() as fecha 
    from rutas_promotor_temporal rp 
    inner join cat_rutas cr on rp.idruta = cr.idruta
    left join cat_formato cf on cr.idformato = cf.idformato
    left join cat_cadena cc on cf.idcadena = cc.idcadena
    where rp.idpromotor = '" . $idpromotor . "' 
	and cast(rp.dia as date) = cast(current_date() as date) 
    and rp.asiste = 1 and rp.idestatus = 1 ". $orden; 
	
	$Hora = date("d-m-Y H:i:s");
    $file = fopen("log_" . date("dmY") . ".txt", "a");
	fwrite($file, "[" . $Hora . "] " . '/// *****************************************************
// Consulta de acuerdo al tipo
// Tipo promotor
'. PHP_EOL);
    fwrite($file, "[" . $Hora . "] " . $sql . PHP_EOL);
    fclose($file);

}

// *****************************************************
// Tipo supervisor
if ($tipo == 1) {
    $sql = "Select distinct a.idruta, IFNULL(a.determinante,0) as determinante, a.tienda, a.direccioncompleta, a.latitud, a.longitud, a.version, a.fecha
    From 
    (
    Select distinct
    cr.idruta, cr.determinante, 
    concat(IFNULL(cc.nombrecorto,''), ' ', IFNULL(cf.formato,''), ' ' , IFNULL(cr.Tienda,'')) as tienda, 
    cr.`direccioncompleta`, cr.latitud, cr.longitud,
    cc.cadena, cf.formato, 
    '" . $version . "' as version, CURDATE() as fecha   
    from cat_rutas cr 
    inner join rutas_promotor rp on cr.idruta = rp.idruta
    left join rutas_promotor_dias rpd on rp.idpromotor = rpd.idpromotor and rp.idruta = rpd.idruta
    left join vw_consultastiendaspromotorfecha v on cr.idruta = v.idruta and  rp.idruta = v.idruta   and v.idpromotor='" . $idpromotor . "'  and cast(v.Fecha as date) = cast(curdate() AS DATE)
    left join cat_formato cf on cr.idformato = cf.idformato
    left join cat_cadena cc on cf.idcadena = cc.idcadena
    where rp.idpromotor in 
    (Select idpromotor from vw_supervisor_promotor_vapp 
	where idsupervisor = '" . $idpromotor . "')  
    and cr.idestatus = 1 and rp.idestatus = 1 " . $criterio1 . " 
    and ifnull(v.Cta,0) <2 
    ) as a
    order  by a.cadena asc, a.formato asc, a.Tienda asc ";

	$Hora = date("d-m-Y H:i:s");
    $file = fopen("log_" . date("dmY") . ".txt", "a");
	fwrite($file, "[" . $Hora . "] " . '/// *****************************************************
    / Tipo supervisor'. PHP_EOL);
    fwrite($file, "[" . $Hora . "] " . $sql . PHP_EOL);
    fclose($file);

}

// *****************************************************
// Tipo supervisor que tiene acceso a todas las tiendas
if ($tipo == 1 && $idpromotor == 748) {
    $sql = "Select distinct a.idruta, IFNULL(a.determinante,0) as determinante, a.tienda, 
        a.direccioncompleta, a.latitud, a.longitud, a.version, a.fecha
    From 
    (
    Select distinct
    cr.idruta, cr.determinante, 
    concat(IFNULL(cc.nombrecorto,''), ' ', IFNULL(cf.formato,''), ' ' , IFNULL(cr.Tienda,'')) as tienda,
    cr.`direccioncompleta`, cr.latitud, cr.longitud,
    cc.cadena, cf.formato, '" . $version . "' as version, CURDATE() as fecha  
    from cat_rutas cr    
    left join cat_formato cf on cr.idformato = cf.idformato
    left join cat_cadena cc on cf.idcadena = cc.idcadena

    where cr.idestatus = 1 "  . $criterio1 . "
    )
    as a
    order  by a.cadena asc, a.formato asc, a.Tienda asc Limit 1000";
	
		$Hora = date("d-m-Y H:i:s");
    $file = fopen("log_" . date("dmY") . ".txt", "a");
	fwrite($file, "[" . $Hora . "] " . '/// *****************************************************
    // Tipo supervisor que tiene acceso a todas las tiendas'. PHP_EOL);
    fwrite($file, "[" . $Hora . "] " . $sql . PHP_EOL);
    fclose($file);

}
//echo $sql;
// *****************************************************
// Obteniendo los datos de tiendas
if ($result = mysqli_query($con, $sql)) {
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $pro['idruta'] = $row['idruta'];
        $pro['determinante'] = $row['determinante'];
        $pro['tienda'] = str_replace("'", "", $row['tienda']);
        $pro['direccioncompleta'] = str_replace("'", "", $row['direccioncompleta']);
        $pro['latitud'] = $row['latitud'];
        $pro['longitud'] = $row['longitud'];
        $pro['versionapp'] = $row['version'];
        $pro['fecha'] = $row['fecha'];
        //$pro['sql'] = $sql;
        array_push($resultado, array('RUTA' => $pro));
        $i++;
    }
    //echo json_encode(array("RESPUESTA"=>$resultado));   // Envìa el resultado
} else {
    http_response_code(404);
}


// *****************************************************
// Obteniendo los datos del cat_productos
$sql1 = "Select cp.* from cat_productos cp where cp.idestatus = 1 and cp.idempresa = '" . $idempresa . "' order by descripcion asc;";

/*	$Hora = date("d-m-Y H:i:s");
    $file = fopen("log_" . date("dmY") . ".txt", "a");
	fwrite($file, "[" . $Hora . "] " . '/// *****************************************************
    // Obteniendo los datos del producto'. PHP_EOL);
    fwrite($file, "[" . $Hora . "] " . $sql1 . PHP_EOL);
    fclose($file); */

if ($result1 = mysqli_query($con, $sql1)) {
    $j = 0;
    while ($row1 = mysqli_fetch_assoc($result1)) {

        $prod['idproducto'] = $row1['idproducto'];
        $prod['upc'] = $row1['upc'];
        $prod['descripcion'] = str_replace("'", "", $row1['descripcion']);
        $prod['descripcion1'] = str_replace("'", "", $row1['descripcion1']);
        $prod['cantidad_caja'] = $row1['cantidad_caja'];
        $prod['cantidad_kgs'] = $row1['cantidad_kgs'];
        $prod['idempresa'] = $row1['idempresa'];
        $prod['categoria1'] = str_replace("'", "", $row1['categoria1']);
        $prod['categoria2'] = str_replace("'", "", $row1['categoria2']);
        $prod['udc'] = $row1['udc'];
        $prod['fdc'] = $row1['fdc'];
        $prod['uda'] = $row1['uda'];
        $prod['fda'] = $row1['fda'];
        $prod['ruta_archivo'] = $row1['ruta'];
        //$prod['sql'] = $sql;
        array_push($resultado, array('PROD' => $prod));
        $j++;
    }
    //echo json_encode(array("RESPUESTA"=>$resultado));   // Envìa el resultado
} else {
    http_response_code(404);
}

// *****************************************************
// Obteniendo los datos de producto formato precio
$sql2 = "   Select p.*, c.idempresa from producto_formato_precio p 
inner join cat_productos pro on p.idproducto = pro.idproducto
inner join cat_empresa c on pro.idempresa = c.idempresa
where p.precio>0 and c.idempresa = '" . $idempresa . "' ;";

	/*$Hora = date("d-m-Y H:i:s");
    $file = fopen("log_" . date("dmY") . ".txt", "a");
	fwrite($file, "[" . $Hora . "] " . '/// *****************************************************
    // Obteniendo los datos de producto formato precio'. PHP_EOL);
    fwrite($file, "[" . $Hora . "] " . $sql2 . PHP_EOL);
    fclose($file);*/


if ($result2 = mysqli_query($con, $sql2)) {
    $k = 0;
    while ($row2 = mysqli_fetch_assoc($result2)) {
        $prodf['idproductoformatoprecio'] = $row2['idproductoformatoprecio'];
        $prodf['idproducto'] = $row2['idproducto'];
        $prodf['idformato'] = $row2['idformato'];
        $prodf['idempresa'] = $row2['idempresa'];
        $prodf['precio'] = $row2['precio'];
        $prodf['udc'] = $row2['udc'];
        $prodf['fdc'] = $row2['fdc'];
        $prodf['uda'] = $row2['uda'];
        $prodf['fda'] = $row2['fda'];
        array_push($resultado, array('PROD_FTO' => $prodf));
        $k++;
    }
    //echo json_encode(array("RESPUESTA"=>$resultado));   // Envìa el resultado
} else {
    http_response_code(404);
}

// *****************************************************
// Obteniendo los datos de las rutas
$sql3 = " Select cr.idruta, cr.idformato, cc.idcadena 
    from cat_rutas cr  
    inner join cat_formato cf on cr.idformato = cf.idformato
    inner join cat_cadena cc on cf.idcadena = cc.idcadena
    where cr.idestatus = 1 
    and cr.idruta in 
    (
        Select idruta from vw_promotorruta 
        where idpromotor = " . $idpromotor . " and (
        Select 
			(
				Select distinct v1.idempresa 
				from cat_cadena c1 inner join vw_ruta_empresa v1
				where c1.idcadena = v1.idcadena and v1.idempresa = " . $idempresa . "
			) from cat_rutas c where c.idruta = vw_promotorruta.idruta
        ) > 0
    );";
	
	/*$Hora = date("d-m-Y H:i:s");
    $file = fopen("log_" . date("dmY") . ".txt", "a");
	fwrite($file, "[" . $Hora . "] " . '/// *****************************************************
    // Obteniendo los datos de las rutas'. PHP_EOL);
    fwrite($file, "[" . $Hora . "] " . $sql3 . PHP_EOL);
    fclose($file);*/

if ($result3 = mysqli_query($con, $sql3)) {
    $l = 0;
    while ($row3 = mysqli_fetch_assoc($result3)) {
        $cruta1['idruta'] = $row3['idruta'];
        $cruta1['idformato'] = $row3['idformato'];
        $cruta1['idcadena'] = $row3['idcadena'];
        array_push($resultado, array('RUTA_CAT' => $cruta1));
        $l++;
    }
    // echo json_encode(array("RESPUESTA"=>$resultado));   // Envìa el resultado
} else {
    http_response_code(404);
}


// *****************************************************
// Obteniendo los datos de la vista de vw_producto_ruta_fecha
$sql4 = "Select * from vw_producto_ruta_fecha where (
        Select 
		(
			Select distinct ec.idempresa from cat_cadena c1 
            inner join empresa_cadena ec on c1.idcadena = ec.idcadena
			where c1.idcadena = cf.idcadena and ec.idempresa = " . $idempresa . "
        ) from cat_rutas c inner join 
        cat_formato cf on c.idformato = cf.idformato 
        where c.idruta = vw_producto_ruta_fecha.idruta
        ) > 0 limit 100;";
		
	/*		$Hora = date("d-m-Y H:i:s");
    $file = fopen("log_" . date("dmY") . ".txt", "a");
	fwrite($file, "[" . $Hora . "] " . '/// *****************************************************
    // Obteniendo los datos de la vista de precios'. PHP_EOL);
    fwrite($file, "[" . $Hora . "] " . $sql4 . PHP_EOL);
    fclose($file); */

		
if ($result4 = mysqli_query($con, $sql4)) {
    $l = 0;
    while ($row4 = mysqli_fetch_assoc($result4)) {
        $vw['idproducto'] = $row4['idproducto'];
        $vw['idruta'] = $row4['idruta'];
        $vw['precioreal'] = $row4['precioreal'];
        $vw['fda'] = $row4['fda'];
        array_push($resultado, array('VISTA' => $vw));
        $l++;
    }
    // echo json_encode(array("RESPUESTA"=>$resultado));   // Envìa el resultado
} else {
    http_response_code(404);
}


/// *****************************************************
// Obteniendo los datos de cadena
$sql5 = "Select distinct cc.idcadena, v.idempresa, v.nombrecorto 
from cat_cadena cc inner join vw_ruta_empresa v
on cc.idcadena = v.idcadena 
where cc.idestatus = 1 and v.idempresa = '" . $idempresa . "';";

	/*$Hora = date("d-m-Y H:i:s");
    $file = fopen("log_" . date("dmY") . ".txt", "a");
	fwrite($file, "[" . $Hora . "] " . '/// *****************************************************
    // Obteniendo los datos de cadena'. PHP_EOL);
    fwrite($file, "[" . $Hora . "] " . $sql5 . PHP_EOL);
    fclose($file);*/

if ($result5 = mysqli_query($con, $sql5)) {
    $m = 0;
    while ($row5 = mysqli_fetch_assoc($result5)) {
        $cadena['idcadena'] = $row5['idcadena'];
        $cadena['idempresa'] = $row5['idempresa'];
        $cadena['nombrecorto'] = $row5['nombrecorto'];
        array_push($resultado, array('CADENA' => $cadena));
        $m++;
    }
    // echo json_encode(array("RESPUESTA"=>$resultado));   // Envìa el resultado
} else {
    http_response_code(404);
}

/// *****************************************************
// Obteniendo los datos del catalogo cat_observa_precios
$sql6 = "Select idobs, Observaciones from cat_observa_precios where idestatus=1;";

	/*$Hora = date("d-m-Y H:i:s");
    $file = fopen("log_" . date("dmY") . ".txt", "a");
	fwrite($file, "[" . $Hora . "] " . '/// *****************************************************
    // Obteniendo los datos del catalogo cat_observa_precios'. PHP_EOL);
    fwrite($file, "[" . $Hora . "] " . $sql6 . PHP_EOL);
    fclose($file); */

if ($result6 = mysqli_query($con, $sql6)) {
    $n = 0;
    while ($row6 = mysqli_fetch_assoc($result6)) {
        $observa['idobs'] = $row6['idobs'];
        $observa['observaciones'] = $row6['Observaciones'];
        array_push($resultado, array('OBSERV' => $observa));
        $n++;
    }
    //echo json_encode(array("RESPUESTA"=>$resultado));   // Envìa el resultado
} else {
    http_response_code(404);
}

/// *****************************************************
// Obteniendo los datos del catalogo vw_promociones
$sql7 = "
Select distinct
vw.idpromocion,
vw.idempresa, 
vw.nombre,
vw.capacidad, 
vw.canal, 
vw.actividad, 
vw.alcance, 
vw.inicio, 
vw.final, vw.periodo, 
vw.actividad,
vw.precioregular, 
vw.preciopromocion, 
vw.idformato,
vw.ruta
from vw_promociones vw 
where vw.idempresa = '" . $idempresa . "'";

	/*$Hora = date("d-m-Y H:i:s");	
    $file = fopen("log_" . date("dmY") . ".txt", "a");
	fwrite($file, "[" . $Hora . "] " . '/// *****************************************************
    // Obteniendo los datos del catalogo vw_promociones'. PHP_EOL);
    fwrite($file, "[" . $Hora . "] " . $sql7 . PHP_EOL);
    fclose($file);*/


if ($result7 = mysqli_query($con, $sql7)) {
    $o = 0;
    while ($row7 = mysqli_fetch_assoc($result7)) {
        $prom['idpromocion'] = $row7['idpromocion'];
        $prom['idempresa'] = $row7['idempresa'];
        $prom['nombre'] = $row7['nombre'];
        $prom['capacidad'] = $row7['capacidad'];
        $prom['canal'] = $row7['canal'];
        $prom['alcance'] = $row7['alcance'];
        $prom['inicio'] = $row7['inicio'];
        $prom['final'] = $row7['final'];
        $prom['periodo'] = $row7['periodo'];
        $prom['actividad'] = $row7['actividad'];
        $prom['alcance'] = $row7['alcance'];
        $prom['precioregular'] = $row7['precioregular'];
        $prom['preciopromocion'] = $row7['preciopromocion'];
        $prom['idformato'] = $row7['idformato'];
        $prom['idproducto'] = "0";
        $prom['ruta'] = $row7['ruta'];
        array_push($resultado, array('PROM' => $prom));
        $o++;
    }
    //echo json_encode(array("RESPUESTA"=>$resultado));   // Envìa el resultado
} else {
    http_response_code(404);
}

/// *****************************************************
// Obteniendo los datos del catalogo cat_actividad
$sql8 = "Select idactividad, actividad from cat_actividad where idestatus=1 order by 1;";

	/*$Hora = date("d-m-Y H:i:s");
    $file = fopen("log_" . date("dmY") . ".txt", "a");
	fwrite($file, "[" . $Hora . "] " . '/// *****************************************************
    // Obteniendo los datos del catalogo cat_actividad'. PHP_EOL);
    fwrite($file, "[" . $Hora . "] " . $sql8 . PHP_EOL);
    fclose($file); */

if ($result8 = mysqli_query($con, $sql8)) {
    $n = 0;
    while ($row8 = mysqli_fetch_assoc($result8)) {
        $activ['idactividad'] = $row8['idactividad'];
        $activ['actividad'] = $row8['actividad'];
        array_push($resultado, array('ACTIV' => $activ));
        $p++;
    }
    //echo json_encode(array("RESPUESTA"=>$resultado));   // Envìa el resultado
} else {
    http_response_code(404);
}

/// *****************************************************
// Obteniendo los datos del catalogo cat_empaque
$sql9 = "Select idempaque, empaque from cat_empaque where idestatus=1 order by 1;";

	/*$Hora = date("d-m-Y H:i:s");
    $file = fopen("log_" . date("dmY") . ".txt", "a");
	fwrite($file, "[" . $Hora . "] " . '/// *****************************************************
    // Obteniendo los datos del catalogo cat_empaque'. PHP_EOL);
    fwrite($file, "[" . $Hora . "] " . $sql9 . PHP_EOL);	
    fclose($file); */

if ($result9 = mysqli_query($con, $sql9)) {
    $n = 0;
    while ($row9 = mysqli_fetch_assoc($result9)) {
        $empa['idempaque'] = $row9['idempaque'];
        $empa['empaque'] = $row9['empaque'];
        array_push($resultado, array('EMPAQUE' => $empa));
        $p++;
    }
    //echo json_encode(array("RESPUESTA"=>$resultado));   // Envìa el resultado
} else {
    http_response_code(404);
}

/// *****************************************************
// Obteniendo los datos de configuracion
$sql10 = "Select valor as solicitainventario from configuracion where id = 1 and idempresa= '" . $idempresa . "';";
if ($result10 = mysqli_query($con, $sql10)) {
    $n = 0;
    while ($row10 = mysqli_fetch_assoc($result10)) {
        $solicitainventario['solicita'] = $row10['solicitainventario'];
        array_push($resultado, array('SOLICITAINV' => $solicitainventario));
        $q++;
    }
    // echo json_encode(array("RESPUESTA" => $resultado));   // Envìa el resultado
} else {
    http_response_code(404);
}

/// *****************************************************
// Obteniendo los datos de respuestas
$sql11 = "Select i.idinc, i.idincidencia, i.idfoto, i.idpromotor, i.idruta, i.fechahora, i.observaciones, i.respuesta, i.fechahora_respuesta, p.image 
from incidencias i
inner join photos p on i.idfoto = p.id
where i.enviada is null and not i.fechahora_respuesta is null and i.idpromotor = " . $idpromotor . "
order by 1 desc limit 10";
if ($result11 = mysqli_query($con, $sql11)) {
    $n = 0;
    while ($row11 = mysqli_fetch_assoc($result11)) {
        $res_incidencia['idinc']        = $row11['idinc'];
        $res_incidencia['idincidencia'] = $row11['idincidencia'];
        $res_incidencia['idfoto']       = $row11['idfoto'];
        $res_incidencia['idPromotor']   = $row11['idpromotor'];
        $res_incidencia['idruta']       = $row11['idruta'];
        $res_incidencia['fechahora']    = $row11['fechahora'];
        $res_incidencia['observaciones'] = $row11['observaciones'];
        $res_incidencia['RESPUESTA']    = $row11['respuesta'];
        $res_incidencia['fechahora_respuesta'] = $row11['fechahora_respuesta'];
        $res_incidencia['image']        = $row11['image'];
        $res_incidencia['leida']        = '0';
        array_push($resultado, array('RESP_INCIDENCIA' => $res_incidencia));
        $q++;
    }
    // echo json_encode(array("RESPUESTA" => $resultado));   // Envìa el resultado
} else {
    http_response_code(404);
}


/// *****************************************************
// Obteniendo los datos de los tipos de incidencias
$sql12 = "Select idincidencia, descripcion from cat_incidencias order by descripcion;";
if ($result12 = mysqli_query($con, $sql12)) {
    $n = 0;
    while ($row12 = mysqli_fetch_assoc($result12)) {
        $inc['idincidencia'] = $row12['idincidencia'];
        $inc['descripcion'] = $row12['descripcion'];
        array_push($resultado, array('INCIDENCIA' => $inc));
        $n++;
    }
    echo json_encode(array("RESPUESTA" => $resultado));   // Envìa el resultado
} else {
    http_response_code(404);
}
?>
