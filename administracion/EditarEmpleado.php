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

<title>GAAG Archivos / Editar Empleado</title>
<?php
	$con=Conexion();
	$sql_consulta="select * from empleado where idempleado=".$_GET["id"];
	$result_consulta=pg_exec($con,$sql_consulta);
	$empleado=pg_fetch_array($result_consulta,0);
?>
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
					
				<?php MenuLateralAdmin(0); ?>				
					
				</div>
								
				
			</div>			
			
			<div class="col-md-8" style="">
								
				<div class="col-md-10" style="border-bottom: 1px solid #E9E9E9; height: 60px; line-height: 60px;font-family: 'PT Sans Narrow', sans-serif; font-size: 22px;">
					Formulario Edición de Empleado
				</div>
				<div class="col-md-2"></div>
				
				<div class="col-md-10" style="border-bottom: 1px solid #E9E9E9; height: auto; padding-bottom: 30px; padding-top: 15px;">																				
					<form role="form" id="form_CreacionEmpresa" method="post" action="../recursos/clases/acciones.php?action=4">
  						<input type="hidden" name="idempleado" id="idempleado" value="<?php echo $empleado[0] ?>" />
  						<div class="form-group">
	    					<label for="empresa_empleado" style="font-family: 'PT Sans Narrow', sans-serif; font-size: 16px;">Empresa</label>
							<select class="form-control" disabled="disabled" id="empresa_empleado" name="empresa_empleado" required="required">
								<?php
									$con=Conexion();
									$sql_listaEmpresas="select * from empresa order by nombre;";
									$result_listaEmpresas=pg_exec($con,$sql_listaEmpresas);
									for($i=0;$i<pg_num_rows($result_listaEmpresas);$i++){
										$empresa = pg_fetch_array($result_listaEmpresas,$i);
										if($empresa[0]==$empleado[1]){
											echo "<option selected=\"selected\" value=".$empresa[0].">".$empresa[1]."</option>";
										}else{
											echo "<option value=".$empresa[0].">".$empresa[1]."</option>";
										}																				
									}
								?>
  								
							</select>							
  						</div>						
  						<div class="form-group">
	    					<label for="tipo_empleado" style="font-family: 'PT Sans Narrow', sans-serif; font-size: 16px;">Tipo de Empleado</label>
	    					<select class="form-control" disabled="disabled" id="tipo_empleado" name="tipo_empleado" required="required">
	    						<?php
	    							if($empleado[7]==1){
	    								echo "<option value=\"1\" selected=\"selected\">Permiso parcial</option>";
	    								echo "<option value=\"2\">Permiso completo sobre el directorio de la empresa</option>";	    								
	    							}else{
	    								echo "<option value=\"1\" >Permiso parcial</option>";
	    								echo "<option value=\"2\" selected=\"selected\">Permiso completo sobre el directorio de la empresa</option>";	 	    								
	    							}
	    						
	    						?>

	    					</select>
  						</div>  						
  						<div class="form-group">
	    					<label for="nombre_empleado" style="font-family: 'PT Sans Narrow', sans-serif; font-size: 16px;">Nombre del Empleado</label>
    						<input type="input" value="<?php echo $empleado[2] ?>" maxlength="60" class="form-control" name="nombre_empleado" id="nombre_empleado" placeholder="Ingrese el nombre del empleado" required="required">
  						</div>

  						<div class="form-group">
	    					<label for="rfc_empleado" style="font-family: 'PT Sans Narrow', sans-serif; font-size: 16px;">Rfc del Empleado</label>
    						<input type="input"  value="<?php echo $empleado[5] ?>" maxlength="30" class="form-control" name="rfc_empleado" id="rfc_empleado" placeholder="Ingrese el rfc del empleado">
  						</div> 
  						<div class="form-group">
	    					<label for="email_empleado" style="font-family: 'PT Sans Narrow', sans-serif; font-size: 16px;">Email de Contacto</label>
    						<input type="email" value="<?php echo $empleado[3] ?>" maxlength="100" class="form-control" name="email_empleado" id="email_empleado" placeholder="Ingrese el email del empleado" required="required">
  						</div> 
  						<div class="form-group">
	    					<label for="clave" style="font-family: 'PT Sans Narrow', sans-serif; font-size: 16px;">Contraseña</label>
    						<input type="password" value="<?php echo $empleado[4] ?>" maxlength="12" class="form-control" name="clave" id="clave" placeholder="Ingrese la clave del usuario" required="required">
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