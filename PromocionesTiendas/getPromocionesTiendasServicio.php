  <?php
  header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
  /**
   l-Allow-Headers: Origin, X-Requested-With, Content-Type, Accep* Regresa el usuario.
   */
  require '../database.php';
  //echo 'inicio';
  date_default_timezone_set('America/Mexico_City');
  
  // Get the posted data.
  $postdata = file_get_contents("php://input");

if(isset($postdata) && empty($postdata)){
/*Seccion de parametros de Testeo*/
$postdata = ['FechaInicial' => (isset($_GET['FechaInicial']))? $_GET['FechaInicial'] : '2020-10-06', 'FechaFinal' => (isset($_GET['FechaFinal']))? $_GET['FechaFinal'] : '2020-10-06', 'Tienda' => (isset($_GET['Tienda']))? $_GET['Tienda'] : [], 'idpromotor' => (isset($_GET['idpromotor']))? $_GET['idpromotor'] : [], 'idcadena' => (isset($_GET['idcadena']))? $_GET['idcadena'] : [],'idEmpresa' => (isset($_GET['idEmpresa']))? $_GET['idEmpresa'] :1];

$postdata = json_encode($postdata);

/********************************+*/
}

  if(isset($postdata) && !empty($postdata))
  {
	  // Extract the data.
	  $request = json_decode($postdata);
	  //echo 'si';
	  
	  $condicion = "";
	  $condicion1 = "(Select valor from configuracion cfg where cfg.idconf = 2)";
	  $cat = [];
  
	  // Sanitize Parameters
	  $FechaInicial = mysqli_real_escape_string($con, trim($request->FechaInicial));
	  $FechaFinal = mysqli_real_escape_string($con, trim($request->FechaFinal));
	  $Tienda = mysqli_real_escape_string($con, (int)sizeof($request->Tienda));
	  $idpromotor = mysqli_real_escape_string($con, (int)sizeof($request->idpromotor));
	  $idpromocion = mysqli_real_escape_string($con, (int)sizeof($request->idpromocion));
	  $idcadena = mysqli_real_escape_string($con, (int)sizeof($request->idcadena));
	  $idEmpresa = mysqli_real_escape_string($con, (int)$request->idEmpresa);

	 
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
		  $Tienda = "";
	  /*Construye estructura valida para el array in sql*/
	  foreach($request->Tienda as $_tienda){
	   if($_tienda != "0"){
	   $Tienda .= "" . $_tienda . ",";
	   }
	  }
  
		 $Tienda = substr($Tienda,0,strlen($Tienda)-1);//Remueve la ultima coma del final de la cadena
		  if($Tienda != ""){
		  $condicion.=" and cr.idruta in(" . $Tienda . ") ";
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
		  $condicion.=" and pt.idpromotor in(" . $idpromotor . ") ";
		 }
	  }
	  
	  if(intval($idpromocion) > 0){
	  $idpromocion = "";
	  /*Construye estructura valida para el array in sql*/
	  foreach($request->idpromocion as $_idpromocion){
	   if($_idpromocion != "0"){
	   $idpromocion .= "" . $_idpromocion . ",";
	   }
	  }
  
		 $idpromocion = substr($idpromocion,0,strlen($idpromocion)-1);//Remueve la ultima coma del final de la cadena
		 if($idpromocion != ""){
		  $condicion.=" and pt.idpromocion in(" . $idpromocion . ") ";
		 }
	  }
  
	  if(intval($idcadena) > 0){
		  $idcadena = "";
	  //Construye estructura valida para el array in sql
	  foreach($request->idcadena as $_idcadena){
	   if($_idcadena != "0"){
	   $idcadena .= "" . $_idcadena . ",";
	   }
	  }
  
		 $idcadena = substr($idcadena,0,strlen($idcadena)-1);//Remueve la ultima coma del final de la cadena
		 if($idcadena != ""){
		  $condicion.=" and c.idcadena in(" . $idcadena . ") ";
		 }
	  }
	  //$usuario = [];
      if(intval($idEmpresa) > 0){
	       $condicion1 = $idEmpresa;
      }

	  $sql = "Select distinct p.*,DATE_FORMAT(pt.fecha,'%d/%m/%Y') as fecha,pt.idpromotor,if(pt.aplica = 0,'No aplica','Aplica') as aplica,cr.Tienda,f.formato,c.nombrecorto as cadena, 
	  DATE_FORMAT(p.fda,'%d/%m/%Y') as fda1,DATE_FORMAT(p.fdc,'%d/%m/%Y') as fdc1
  from promocion p 
  inner join (
  promociones_tiendas pt inner join (   
  cat_rutas cr 
  inner join cat_formato f on cr.idformato = f.idformato
  inner join cat_cadena c on f.idcadena = c.idcadena
  inner join empresa_cadena ec on c.idcadena =  ec.idcadena 
  ) on pt.idruta = cr.idruta
  ) on p.idpromocion = pt.idpromocion and p.idempresa = pt.idempresa 
   where p.idestatus = 1 
   and p.idempresa = " . $condicion1 . " 
   and pt.idempresa = " . $condicion1 . " 
   and ec.idempresa = " . $condicion1 . " 
   and Cast(pt.Fecha as date) between Cast('" . $FechaInicial . "' as date) and Cast('"  . $FechaFinal . "' as date) " .$condicion . ";";
  
  
	  //echo($sql);
	  //echo ('<br>');
	  if($result = mysqli_query($con,$sql))
	  {
		  $i = 0;
		  while($row = mysqli_fetch_assoc($result))
		  {
	  $cat[$i]['idpromocion']      =   $row['idpromocion'];
	  $cat[$i]['idempresa']     =   $row['idempresa'];
	  $cat[$i]['ruta']     =   $row['ruta'];
	  $cat[$i]['nombre']   =   $row['nombre'];
	  $cat[$i]['capacidad']        =   $row['capacidad'];
	  $cat[$i]['canal']        =   $row['canal'];
	  $cat[$i]['alcance']        =   $row['alcance'];
	  $cat[$i]['inicio']        =   $row['inicio'];
	  $cat[$i]['final']        =   $row['final'];
	  $cat[$i]['periodo']        =   $row['periodo'];    
	  $cat[$i]['actividad']        =   $row['actividad'];
	  $cat[$i]['precioregular']        =   $row['precioregular'];
	  $cat[$i]['preciopromocion']        =   $row['preciopromocion'];
	  $cat[$i]['uda']           =   $row['uda'];
	  $cat[$i]['fda']           =   $row['fda'];
	  $cat[$i]['fda_m']         =   $row['fda1'];
	  $cat[$i]['udc']           =   $row['udc'];
	  $cat[$i]['fdc']           =   $row['fdc'];
	  $cat[$i]['fdc_m']         =   $row['fdc1'];
	  $cat[$i]['idestatus']     =   $row['idestatus'];
	  $cat[$i]['fecha']     =   $row['fecha']; 
	  $cat[$i]['idpromotor']     =   $row['idpromotor'];
	  $cat[$i]['aplica']     =   $row['aplica'];
	  $cat[$i]['tienda']     =   $row['Tienda'];
	  $cat[$i]['formato']     =   $row['formato'];
	  $cat[$i]['cadena']     =   $row['cadena']; 
	  $i++;
		  }		 
		  //$cat = ["sql" => $sql];
		  //echo json_encode(array("Resultado"=>$cat));
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