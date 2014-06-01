<?php
require_once("../recursos/clases/funciones.php");
$targetFolder = '/mybox/archivos/'; // Relative to the root
$verifyToken = md5('unique_salt' . $_POST['timestamp']);
$var=$_GET["carpeta"];

$con=Conexion();

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
 	
 	$estructura=explode(".",$_FILES['Filedata']['name']);
	$sql_selecTipo="select * from tipo_archivo where extencion='".$estructura[1]."';";
	$result_selecTipo=pg_exec($con,$sql_selecTipo);
	$tipo=pg_fetch_array($result_selecTipo,0);
	
	$sql_ultimoArchivo="select last_value from archivo_idarchivo_seq";
	$result_ultimoArchivo=pg_exec($con,$sql_ultimoArchivo);
	$idArchivo=pg_fetch_array($result_ultimoArchivo);
	
	INFO("El ultimo valor de la secuencia es: ".$idArchivo[0].PHP_EOL);
	
	$sql_cuenta="select count(*) from archivo;";
	$result_cuenta=pg_exec($con,$sql_cuenta);
	$arreglo=pg_fetch_array($result_cuenta,0);
	
	INFO("el numero de registros son:".$arreglo[0].PHP_EOL);
	
	if($arreglo[0]==0){
		$idArchivo[0]=1;
	}				
	if($arreglo[0]==1){
		$idArchivo[0]=2;
	}	
	if($arreglo[0]>1){
		$idArchivo[0]++;
	}		

	INFO("El id del archivo vale: ".$idArchivo[0].PHP_EOL);
	
	/*Calculo de la Ruta del Archivo*/
	$listUrls=array();
	$contaodrUrls=0;
	
	$padre=	$_GET["carpeta"];	
	
	$listUrls[$contaodrUrls]=$padre;
	$contaodrUrls++;
	$band=0;
			
	while($band==0){
			
		$sql_carpetaPadre="select * from carpeta where idcarpeta='".$padre."';";
		$result_carpetaPadre= pg_exec($con,$sql_carpetaPadre);
		$carpetaPadre=pg_fetch_array($result_carpetaPadre,0);
				
		if($carpetaPadre[3]==0){
			$band=1;					
		}else{
			$listUrls[$contaodrUrls]=$carpetaPadre[3];
			$contaodrUrls++;
			$padre=$carpetaPadre[3];
		}	
			
	}
			
	$directorio=$targetPath;
	for($i=($contaodrUrls-1);$i>=0;$i--){
		if($i==0){
			$directorio=$directorio."Folder".$listUrls[$i]."/";
		}else{
			$directorio=$directorio."Folder".$listUrls[$i]."/";
		}				
	}	
	
	INFO("Directorio: ".$directorio);
	
	
	$nombreGuardar=$idArchivo[0].".".$tipo[3];						
	$targetFile = rtrim($targetPath,'/') . '/' . $nombreGuardar;
	$directorio=$directorio.$nombreGuardar;
	
	$fileTypes = array('jpg','jpeg','gif','png','JPG','pdf','JPEG','docx'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	$sql_valida="select * from tipo_archivo where extencion='".$estructura[1]."';";
	$result_valida=pg_exec($con,$sql_valida);
	if(pg_num_rows($result_valida)>0){
		
		move_uploaded_file($tempFile,$directorio);		
		$sql_insertArchivo="insert into archivo values(nextval('archivo_idarchivo_seq'),'".$_GET["carpeta"]."','".$tipo[0]."','".$_FILES['Filedata']['name']."',now());";		
		$result_insertArchivo=pg_exec($con,$sql_insertArchivo);		
		echo '1';
		
	}else{
		
		echo 'Invalid file type.';
		
	}
}
?>