<?php

require '../database.php';



date_default_timezone_set('America/Mexico_City');



// Get the posted data.

$postdata = file_get_contents("php://input");



if(isset($postdata) && !empty($postdata))

{

  // Extract the data.

  $request = json_decode($postdata);





  // Validate.

  /*if ((int)$request->idEmpresa < 1 || trim($request->cadena) == '') {

    return http_response_code(400);

  }*/





  // Sanitize.

    

 $upc =  mysqli_real_escape_string($con, (string)$request->upc);                       

 $descripcion =  mysqli_real_escape_string($con, (string)$request->descripcion);                       

 $descripcion1 =  mysqli_real_escape_string($con, (string)$request->descripcion1);             

 $cantidad_caja =  mysqli_real_escape_string($con, (string)$request->cantidad_caja);             

 $cantidad_kgs =  mysqli_real_escape_string($con, (string)$request->cantidad_kgs);             

 $idempresa =  mysqli_real_escape_string($con, (string)$request->idempresa);       

 $categoria1 =  mysqli_real_escape_string($con, (string)$request->categoria1);             

 $categoria2 =  mysqli_real_escape_string($con, (string)$request->categoria2);           

// $idcadena =  mysqli_real_escape_string($con, (string)$request->idcadena1); 

 $uda =  mysqli_real_escape_string($con, (string)$request->uda);                   

// $precio =  mysqli_real_escape_string($con, (string)$request->precio);           

 $fda =  date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fda));                   

 $udc =  mysqli_real_escape_string($con, (string)$request->udc);                   

 $fdc =  date('Y-m-d H:i');//mysqli_real_escape_string($con, trim($request->fdc));                  

 

  // Create.
  $sql = "INSERT INTO `cat_productos`(`idproducto`,
  `upc`,
  `descripcion`,
  `descripcion1`,
  `cantidad_caja`,
  `cantidad_kgs`,
  `idempresa`,
  `categoria1`,
  `categoria2`,
  `udc`,
  `fdc`,
  `uda`,
  `fda`,
  `idestatus`) VALUES (null,
'{$upc}',
'{$descripcion}',
'{$descripcion1}',
'{$cantidad_caja}',
'{$cantidad_kgs}',
'{$idempresa}',
'{$categoria1}',
'{$categoria2}',
'{$udc}',
'{$fdc}',
'{$uda}',
'{$fda}',
'1');";

  if(mysqli_query($con,$sql))
  {          
	$idproducto = mysqli_insert_id($con);
    $sql1 = 'Call Proc_PrecioProductoEmpresa (' . $idproducto . ')';
	mysqli_query($con,$sql1);
    http_response_code(201);

    $catcadena = [
      'upc' => $upc,
      'descripcion' => $descripcion,
      'descripcion1' => $descripcion1,
      'cantidad_caja' => $cantidad_caja,
      'cantidad_kgs' => $cantidad_kgs,
      'idempresa' => $idempresa,
      'categoria1' => $categoria1,
      'categoria2' => $categoria2,
//      'idcadena' => 0, //$idcadena,
//      'precio' => $precio,
      'udc' => $udc,
      'fdc' => $fdc,
      'uda' => $uda,
      'fda' => $fda,
      'idestatus' => 1,
      'idproducto' => $idproducto
    ];

    echo json_encode($catcadena);
  }
  else
  {
	 $catcadena = [
	  'upc' => $sql,
      'descripcion' => $descripcion,
      'descripcion1' => $descripcion1,
      'cantidad_caja' => $cantidad_caja,
      'cantidad_kgs' => $cantidad_kgs,
      'idempresa' => $idempresa,
      'categoria1' => $categoria1,
      'categoria2' => $categoria2,
      //'idcadena' => 0, //$idcadena,
      //'precio' => $precio,
      'udc' => $udc,
      'fdc' => $fdc,
      'uda' => $uda,
      'fda' => $fda,
      'idestatus' => 1,
      'idproducto' => mysqli_insert_id($con)
 
	  ];
    echo $sql;
    http_response_code(422);

  }

}

?>