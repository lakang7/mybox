<?php
	require_once("../recursos/clases/funciones.php");
	$con=Conexion();
	
	if (!isset($_GET['file']) || empty($_GET['file'])) {
 		exit();	
	}
	
	//echo $_GET["idarchivo"];
	
	$root = "../archivos/";
	$file = $_GET['file'];
	$path = $root.$file;
	$type = '';
    	
	if (is_file($path)) {
 		$size = filesize($path);
 		if (function_exists('mime_content_type')) {
 			$type = mime_content_type($path);
 		} else if (function_exists('finfo_file')) {
 			$info = finfo_open(FILEINFO_MIME);
 			$type = finfo_file($info, $path);
 			finfo_close($info);
 		}
 		if ($type == '') {
 			$type = "application/force-download";
 		}
		
		$sql_archivo="select * from archivo where idarchivo='".$_GET["idarchivo"]."';";
		$result_archivo=pg_exec($con,$sql_archivo);
		$arch=pg_fetch_array($result_archivo,0);
 		// Definir headers
 		header("Content-Type: $type");
 		header("Content-Disposition: attachment; filename=".$arch[3]."");
 		header("Content-Transfer-Encoding: binary");
 		header("Content-Length: " . $size);
 		// Descargar archivo
 		readfile($path);
	} else {
 		die("El archivo no existe.");
	}
?>
