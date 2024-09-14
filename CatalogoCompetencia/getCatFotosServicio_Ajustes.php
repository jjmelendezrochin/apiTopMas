<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");
if(!isset($postdata) || empty($postdata)){
	$postdata = ['FechaInicial' => (isset($_GET['FechaInicial'])) ? $_GET['FechaInicial']: '2020-10-07','FechaFinal' => (isset($_GET['FechaFinal'])) ? $_GET['FechaFinal'] : '2020-10-07'];
	$postdata = json_encode($postdata);
}

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);

$condicion = "";
$condicion1 = "(Select cfg.valor from configuracion cfg where cfg.idconf = 2)";
$cat = [];
// Obteniendo la empresa
/*$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : false;


if(!$idEmpresa)
{
  return http_response_code(400);
}*/

if(trim($request->FechaInicial) === '' &&  trim($request->FechaFinal) === ''){
  return http_response_code(400);
}


// Sanitize.
$FechaInicial = mysqli_real_escape_string($con, trim($request->FechaInicial));
$FechaFinal =  mysqli_real_escape_string($con, trim($request->FechaFinal));
$idoperacion = mysqli_real_escape_string($con, (int)sizeof($request->idoperacion));
$idpromotor = mysqli_real_escape_string($con, (int)sizeof($request->idpromotor));
$Tienda = mysqli_real_escape_string($con, (int)sizeof($request->Tienda));
$idcadena = mysqli_real_escape_string($con, (int)sizeof($request->idcadena));
$orden =  mysqli_real_escape_string($con, (int)$request->orden);
$idEmpresa =  mysqli_real_escape_string($con, (int)$request->idEmpresa);
//$orden=(isset($_GET['orden']))? mysqli_real_escape_string($con, (string)$_GET['orden']) : 0;


if(intval($idoperacion) > 0){
			$idoperacion = "";
	/*Construye estructura valida para el array in sql*/
    foreach($request->idoperacion as $_idoperacion){
	 if($_idoperacion != "0"){
	 $idoperacion .= "" . $_idoperacion . ",";
	 }
    }

       $idoperacion = substr($idoperacion,0,strlen($idoperacion)-1);//Remueve la ultima coma del final de la cadena
	   if($idoperacion != ""){
    $condicion.=" and p.idoperacion in(" . $idoperacion . ") ";
}
}
if(intval($Tienda) > 0){
			$Tienda = "";
	/*Construye estructura valida para el array in sql*/
    foreach($request->Tienda as $_tienda){
	 if($_tienda != "0"){
	 $Tienda .= "'" . $_tienda . "',";
	 }
    }

       $Tienda = substr($Tienda,0,strlen($Tienda)-1);//Remueve la ultima coma del final de la cadena
        if($Tienda != ""){
    $condicion.=" and cr.Tienda in(" . $Tienda . ") ";
		}
}

if(intval($idpromotor) > 0){
	$idpromotor = "";
	/*Construye estructura valida para el array in sql*/
    foreach($request->idpromotor as $_idpromotor){
	 if($_idpromotor != "0"){
	 $idpromotor .= "" . $_idpromotor . ",";
	 }
    }

       $idpromotor = substr($idpromotor,0,strlen($idpromotor)-1);//Remueve la ultima coma del final de la cadena
	   if($idpromotor != ""){
    $condicion.=" and p.idpromotor in(" . $idpromotor . ") ";
	   }
}

if(intval($idcadena) > 0){
			$idcadena = "";
	/*Construye estructura valida para el array in sql*/
    foreach($request->idcadena as $_idcadena){
	 if($_idcadena != "0"){
	 $idcadena .= "" . $_idcadena . ",";
	 }
    }

       $idcadena = substr($idcadena,0,strlen($idcadena)-1);//Remueve la ultima coma del final de la cadena
	   if($idcadena != ""){
    $condicion.=" and cr.idcadena in(" . $idcadena . ") ";
	   }
}

if(intval($orden) == 0){
  $ordenamiento = " order by p.FechaHora desc";
}
else{
  $ordenamiento = " order by p.FechaHora asc";
}

//******************************
// Insercion en bitacora
$bitacora = "Insert into bitacora (instruccion) values (' Valor de orden " . $orden . " valor de ordenamiento" . $ordenamiento . "')";
mysqli_query($con,$bitacora);

//******************************
// Verificando si es promotor o supervisor
if(intval($idEmpresa) > 0){
	$condicion1 = $idEmpresa;
}
$sql0 = "Select tipo from cat_promotor where idpromotor in(" . $idpromotor . ") and idempresa = " . $condicion1 . " ";
if($result = mysqli_query($con,$sql0))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $tipo       = $row['tipo'];
  }
}
else
{
	$x[]["sql"] = $sql0;
	echo json_encode($x);
  //http_response_code(404);
}


//******************************
   $idpromotor1 = 0;
   foreach($request->idpromotor as $_idpromotor){
    if(intval($_idpromotor) == 165){
	$idpromotor1 = 165;
	}
   }
   
   if($idpromotor1==165){
  $sql = "SELECT distinct p.image as foto,
  comp.producto as competencia,
  comp.precio as precio,
  comp.presentacion as presentacion,
  if((Select c.demostrador from competencia c where c.idfoto = p.id) = 0,'No','Si') demo,
  (Select e.empaque from competencia c left join cat_empaque e on c.idempaque = e.idempaque where c.idfoto = p.id) as empaque,
  if((Select c.exhibidor from competencia c where c.idfoto = p.id) = 0,'No','Si') as exhib,
  if((Select c.emplaye from competencia c where c.idfoto = p.id) = 0,'No','Si') as emplaye, 
  p.idpromotor,
  p.latitud,
  p.longitud,
  p.idusuario,
  p.idruta,
  concat(cp.nombre,' ',cp.apellidos,' [',cp.idusuario,'] (', p.appver , ')', ' {' , (case when p.sindatos=0 Then 'CONECTADO' ELSE  'DESCONECTADO' end), '}' ) as promotor,
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
  LEFT JOIN cat_rutas cr on p.idruta = cr.idruta and p.idruta = cr.idruta
  LEFT JOIN cat_formato cf on cr.idformato = cf.idformato
  LEFT JOIN cat_cadena cc on cf.idcadena=cc.idcadena
  LEFT JOIN empresa_cadena ec on cc.idcadena = ec.idcadena
  LEFT JOIN cat_operacion co on p.idoperacion=co.idoperacion
  LEFT JOIN competencia comp on  comp.idfoto = p.id
  where not cr.Tienda is null and not comp.producto is null   
  and Cast(FechaHora as date) BETWEEN  cast('{$FechaInicial}' as date) AND cast('{$FechaFinal}' as date) 
  and ec.idempresa = " . $condicion1 . " 
  and cp.idempresa = " . $condicion1 . " "
  . $condicion . $ordenamiento;
}
else{  
  $sql = "SELECT distinct p.image as foto,
  comp.producto as competencia,
  comp.precio as precio,
  comp.presentacion as presentacion,
  if((Select c.demostrador from competencia c where c.idfoto = p.id) = 0,'No','Si') demo,
  (Select e.empaque from competencia c left join cat_empaque e on c.idempaque = e.idempaque where c.idfoto = p.id) as empaque,
  if((Select c.exhibidor from competencia c where c.idfoto = p.id) = 0,'No','Si') as exhib,
  if((Select c.emplaye from competencia c where c.idfoto = p.id) = 0,'No','Si') as emplaye, 
  p.idpromotor,
  p.latitud,
  p.longitud,
  p.idusuario,
  p.idruta,
  concat(cp.nombre,' ',cp.apellidos,' [',cp.idusuario,'] (', p.appver , ')', ' {' , (case when p.sindatos=0 Then 'CONECTADO' ELSE  'DESCONECTADO' end), '}' ) as promotor,
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
  LEFT JOIN cat_rutas cr on p.idruta = cr.idruta and p.idruta = cr.idruta
  LEFT JOIN cat_formato cf on cr.idformato = cf.idformato
  LEFT JOIN cat_cadena cc on cf.idcadena=cc.idcadena
  LEFT JOIN empresa_cadena ec on cc.idcadena = ec.idcadena
  LEFT JOIN cat_operacion co on p.idoperacion=co.idoperacion
  LEFT JOIN competencia comp on  comp.idfoto = p.id
  where not cr.Tienda is null  and not comp.producto is null
  and Cast(FechaHora as date) BETWEEN  cast('{$FechaInicial}' as date) AND cast('{$FechaFinal}' as date) 
  and ec.idempresa = " . $condicion1 . " 
  and cp.idempresa = " . $condicion1 . " "
  . $condicion .  $ordenamiento;
}
//******************************
// Insercion en bitacora
$bitacora = "Insert into bitacora (instruccion) values ('" . str_replace("'", "|", $sql) . "')";
mysqli_query($con,$bitacora);

// ****************************

if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['foto']      		=   $row['foto'];
	$cat[$i]['competencia']     =   $row['competencia'];
	$cat[$i]['precio']      	=   $row['precio'];
	$cat[$i]['presentacion']    =   $row['presentacion'];
	$cat[$i]['demo']      		=   $row['demo'];
	$cat[$i]['empaque']      	=   $row['empaque'];
	$cat[$i]['exhib']      		=   $row['exhib'];
	$cat[$i]['emplaye']      	=   $row['emplaye'];
    $cat[$i]['promotor']     	=   strtoupper($row['promotor']);
    $cat[$i]['Tienda']        	=   $row['Tienda'];
    $cat[$i]['FechaHora']       =   $row['FechaHora'];
    $cat[$i]['actividad']       =   $row['actividad'];
    $cat[$i]['Distancia']           =   $row['Distancia_m'];
    $cat[$i]['latitud_tienda']      =   $row['latitud_tienda'];
    $cat[$i]['longitud_tienda']     =   $row['longitud_tienda'];
    $cat[$i]['latitud_ubicacion']   =   $row['latitud_ubicacion'];
    $cat[$i]['longitud_ubicacion']  =   $row['longitud_ubicacion'];
    $cat[$i]['Direccion']           =   $row['Direccion'];
    $i++;
  }
 // $cat[]["sql"] = $sql;
  echo json_encode($cat);
}
else
{
	$cat[]["sql"] = $sql;
echo json_encode($cat);
  //http_response_code(404);
}
}
?>