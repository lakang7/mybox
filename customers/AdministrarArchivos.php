<?php session_start(); 
	  
	  if($_SESSION["empleado"]==NULL){
	  	?>
	  		<script type="text/javascript" language="JavaScript">
	  			location.href="index.php";
	  		</script>
	  	<?php
	  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../recursos/bootstrap-3.1.1-dist/css/bootstrap.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Oswald:400,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700' rel='stylesheet' type='text/css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="../recursos/bootstrap-3.1.1-dist/js/bootstrap.min.js"></script>
<script src="../recursos/js/bootbox.min.js"></script>

<script src="../recursos/js/jquery.uploadify.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../recursos/css/uploadify.css">


<style type="text/css">

	.modal-title{
		font-family: 'PT Sans Narrow', sans-serif; 
		font-size: 18px;
	}
	
	.swfupload{
	}	

</style>
<?php
	require_once("../recursos/clases/funciones.php");
?>

<title>GAAG Archivos / Administrar Archivos</title>
</head>

<body onload="cargar()">

	<?php MenuPrincipalAdmin(); ?>
	<div class="container-fluid">
		<div class="row">
			
			<div class="col-md-4">
				<div class="col-md-4"></div>
				<div class="col-md-8" style="border-bottom: 1px solid #E9E9E9; height: 60px; line-height: 60px;font-family: 'PT Sans Narrow', sans-serif; font-size: 22px;">
					Menú
				</div>				
				<div class="col-md-4"></div>
				<div class="col-md-8" style="border-bottom: 1px solid #E9E9E9; height: auto; padding-bottom: 15px; padding-top: 15px;">					
				<?php MenuLateralCliente(1); ?>									
				</div>												
			</div>			
			
			<div class="col-md-8" id="divdirectorio">
								
				<div class="col-md-10" style="border-bottom: 1px solid #E9E9E9; height: 60px; line-height: 60px;font-family: 'PT Sans Narrow', sans-serif; font-size: 22px;">
					Directorio de Archivos 
					<?php  
						$con=Conexion();
										
						$sql_empleado="select * from empleado where idempleado='".$_SESSION["empleado"]."'";
						$result_empleado=pg_exec($con,$sql_empleado);
						$empleado=pg_fetch_array($result_empleado,0);
						if($empleado[6]==2){/*Administrador*/
							$sql_raiz="select * from carpeta where idempresa='".$empleado[1]."' and padre=0;";
							$result_raiz=pg_exec($con,$sql_raiz);
							$raiz=pg_fetch_array($result_raiz,0);	
							$carpPadre=$raiz[0];							
							
						}else{ /*Usuario normal*/
							$sql_raiz="select * from permiso_carpeta where idempleado='".$_SESSION["empleado"]."' order by idcarpeta;";
							$result_raiz=pg_exec($con,$sql_raiz);
							$raiz=pg_fetch_array($result_raiz,0);
							$carpPadre=$raiz[2];														
						}
						 																
					?>
				</div>				
				<input type="hidden" id="mostrandoC" name="mostrandoC" value="<?php echo $carpPadre; ?>">
				<div id="createfolder" name="createfolder" ></div>
				<div class="col-md-2"></div>
				
				<div class="col-md-10" style="border-bottom: 1px solid #E9E9E9; height: auto; padding-bottom: 15px; padding-top: 15px;">																				

					<div class="row">
						<div class="col-md-12" >
						
						<ol class="breadcrumb" style="font-family: 'PT Sans Narrow', sans-serif; font-size: 16px;">
  							<li class="active">Root</li>
						</ol>						
						
						</div>
						<div class="col-md-12">
						
						<div class="table-responsive">
							<table class="table table-hover">
  								<tbody style="border-bottom: 1px solid #DDDDDD">
  									<?php  									
  										/*$con=Conexion();
										$sql_listaCarpetas="select * from carpeta where padre=0 order by nombre";
										$result_listaCarpetas=pg_exec($con,$sql_listaCarpetas);
  						    			for($i=0;$i<pg_num_rows($result_listaCarpetas);$i++){
  						    				$carpeta=pg_fetch_array($result_listaCarpetas,$i);
  											echo "<tr style='cursor:pointer;' onclick=directorio(".$carpeta[0].")><td><img src='../recursos/imagenes/iconfolder.png' height='42' width='42'></td><td style='line-height: 40px; font-family: \"PT Sans Narrow\", sans-serif; font-size: 17px;'>".$carpeta[2]."</td><td></td></tr>";    	
  						    			}*/
  									?>						
								</tbody>						
							</table>
						</div>
						
					
						<div class="col-md-12">						
						</div>
										
						
						</div>												
					</div>																																																				
				</div>												
				<div class="col-md-2"></div>				
				
			</div>

		</div>
	</div>

</body>
<script type="text/javascript" language="JavaScript">
	
	function eliminarArchivo(idArchivo){
		
		bootbox.confirm("¿Esta seguro que desea eliminar este archivo?", function(result) {
  			if(result){
				$("#createfolder").load("../recursos/clases/acciones.php?action=7",{archivo:idArchivo},function(){
					directorio(document.getElementById("mostrandoC").value);
				});  				
  			}  			
		}); 
		

	}

	function editar(id){
		location.href="EditarEmpresa.php?id="+id;
	}
	
	function crearcarpeta(id){
		bootbox.prompt("Ingrese el nombre para la nueva carpeta", function(result) {    
			if(result!=null && result!=""){				
				$("#createfolder").load("../recursos/clases/acciones.php?action=6",{nombre:result,padre:id},function(){
					directorio(id);
				});		
			}else{
				bootbox.hideAll();
				bootbox.alert("Carpeta no creada debe indicar un nombre!");
			}   
			      			
		});
	}
	
	function cargar(){
		var carpeta=document.getElementById("mostrandoC").value;
		directorio(carpeta);
	}
	
	function directorio(idCarpeta){  
		$("#divdirectorio").load("../recursos/clases/acciones.php?action=12",{carpeta:idCarpeta},function(){
		<?php $timestamp = time();?>
		$(function() {
			var idc = $("#mostrandoC").val();
			$('#file_upload').uploadify({
				'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				},
				'swf'      : 'uploadify.swf',
				'uploader' : 'uploadify.php?carpeta='+idc,
				'onQueueComplete' : function(file) {
            		$("#divdirectorio").load("../recursos/clases/acciones.php?action=5",{carpeta:idCarpeta},function(){
            			directorio(idCarpeta);
            		});
        		} 
			});
		});			
		});			
	}					
</script>

	<script type="text/javascript">
		<?php $timestamp = time();?>
		$(function() {
			var idc = $("#mostrandoC").val();
			$('#file_upload').uploadify({
				'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				},
				'swf'      : 'uploadify.swf',
				'uploader' : 'uploadify.php?carpeta='+idc
			});
		});
	</script>

</html>