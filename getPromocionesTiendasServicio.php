  <?php
  /**
   * Regresa el usuario.
   */
  require '../database.php';
  //echo 'inicio';
  date_default_timezone_set('America/Mexico_City');
  
  // Get the posted data.
  $postdata = file_get_contents("php://input");

if(isset($postdata) && empty($postdata)){
/*Seccion de parametros de Testeo*/
$postdata = ['FechaInicial' => (isset($_GET['fi']))? $_GET['fi'] : '2020-10-06', 'FechaFinal' => (isset($_GET['FechaFinal']))? $_GET['FechaFinal'] : '2020-10-06', 'Tienda' => (isset($_GET['Tienda']))? $_GET['Tienda'] : [], 'idpromotor' => (isset($_GET['idpromotor']))? $_GET['idpromotor'] : [], 'idcadena' => (isset($_GET['idcadena']))? $_GET['idcadena'] : []];

$postdata = json_encode($postdata);

/********************************+*/
}


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
	  $Usuario = mysqli_real_escape_string($con, $request->Usuario);
	  $Fabricante = mysqli_real_escape_string($con, $request->Fabrincante);
	  $Modelo = mysqli_real_escape_string($con, $request->Modelo);
	 

	  //$usuario = [];
	  $sql = "Select distinct p.*,pt.fecha,pt.idpromotor,pt.aplica,cr.Tienda,f.formato,c.nombrecorto as cadena 
  from promocion p 
  inner join (
  promociones_tiendas pt inner join (
  cat_rutas cr inner join cat_cadena c on cr.idcadena =  c.idcadena 
  inner join cat_formato f on cr.idformato = f.idformato  
  ) on pt.idruta = cr.idruta
  ) on p.idpromocion = pt.idpromocion and p.idempresa = pt.idempresa 
   where p.idestatus = 1 and 
   Cast(pt.Fecha as date) between Cast('" . $FechaInicial . "' as date) and Cast('"  . $FechaFinal . "' as date) " .$condicion . ";";
  
  
	  //echo($sql);
	  //echo ('<br>');
	  if($result = mysqli_query($con,$sql))
	  {
		  $i = 0;
		  while($row = mysqli_fetch_assoc($result))
		  {
	  $cat[$i]['idpromocion']      =   $row['idpromocion'];
	  $cat[$i]['idempresa']     =   $row['idempresa'];
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
		  echo json_encode($cat);
	  }
	  else
	  {
		  // $cat = ["sql" => $sql];
		  echo json_encode($cat);
		  //http_response_code(404);
	  }
  }
  else{
   echo 'O';
  }
  ?>