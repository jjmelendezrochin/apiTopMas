<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, user-scalable=no">
    <title>Lista de imagenes cargadas</title>
</head>
<body>
<h1>Imagenes cargadas</h1>
<?php
//Establecemos zona horaria por defecto
date_default_timezone_set('America/Mexico_City');
//preguntamos la zona horaria
$zonahoraria = date_default_timezone_get();
echo 'Zona horaria predeterminada: ' . $zonahoraria;

$sorting_order = SCANDIR_SORT_DESCENDING;
$scan = scandir('./uploads', $sorting_order);
foreach($scan as $file)
{
    if(!is_dir($file)){
        echo '<h3>'.$file.'</h3>' ;
        echo '<img src="./uploads/' .$file . '" style="width: 400px;"/><br />';
    }
}
?>
</body>
</html>