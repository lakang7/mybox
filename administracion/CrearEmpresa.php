<?php session_start(); 
	  
	  if($_SESSION["administrador"]==NULL){
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
<?php
	require_once("../recursos/clases/funciones.php");
?>

<title>GAAG Archivos / Crear Empresa</title>
</head>

<body>
		
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
					
				<?php MenuLateralAdmin(1); ?>				
					
				</div>
								
				
			</div>			
			
			<div class="col-md-8" style="">
								
				<div class="col-md-10" style="border-bottom: 1px solid #E9E9E9; height: 60px; line-height: 60px;font-family: 'PT Sans Narrow', sans-serif; font-size: 22px;">
					Formulario Creación de Empresa
				</div>
				<div class="col-md-2"></div>
				
				<div class="col-md-10" style="border-bottom: 1px solid #E9E9E9; height: auto; padding-bottom: 30px; padding-top: 15px;">																				
					<form role="form" id="form_CreacionEmpresa" method="post" action="../recursos/clases/acciones.php?action=1">
  						<div class="form-group">
	    					<label for="nombre_empresa" style="font-family: 'PT Sans Narrow', sans-serif; font-size: 16px;">Nombre de la Empresa</label>
    						<input type="input" maxlength="130" class="form-control" name="nombre_empresa" id="nombre_empresa" placeholder="Ingrese el nombre de la empresa" required="required">
  						</div>
  						<div class="form-group">
	    					<label for="rfc_empresa" style="font-family: 'PT Sans Narrow', sans-serif;">RFC</label>
    						<input type="input" maxlength="30" class="form-control" name="rfc_empresa" id="rfc_empresa" placeholder="Ingrese el rfc de la empresa" required="required">
  						</div>
  						
  						<button type="submit" class="btn btn-default">Submit</button>
					</form>																																		
				</div>
				<div class="col-md-2"></div>				
				
			</div>

		</div>
	</div>


</body>
</html>