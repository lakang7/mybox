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

<title>GAAG Archivos / Listado de Empresas</title>
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
					
				<?php MenuLateralAdmin(2); ?>				
					
				</div>
								
				
			</div>			
			
			<div class="col-md-8" style="">
								
				<div class="col-md-10" style="border-bottom: 1px solid #E9E9E9; height: 60px; line-height: 60px;font-family: 'PT Sans Narrow', sans-serif; font-size: 22px;">
					Listado de Empresas Registradas
				</div>
				<div class="col-md-2"></div>
				
				<div class="col-md-10" style="border-bottom: 1px solid #E9E9E9; height: auto; padding-bottom: 30px; padding-top: 15px;">																				
					<form role="form" id="form_CreacionEmpresa" method="post" action="../recursos/clases/acciones.php?action=2">
					
					<div class="table-responsive">
					<table class="table table-striped">
 						<thead>
  							<tr>
     							<th>RFC</th>
     							<th>Nombre Empresa</th>
     							<th></th>
  							</tr>
  						</thead>
  						<tbody>
  						<?php
  							$con=Conexion();
							$sql_listaEmpresas="select * from empresa order by idempresa";
							$result_listaEmpresas=pg_exec($con,$sql_listaEmpresas);
  						    for($i=0;$i<pg_num_rows($result_listaEmpresas);$i++){
  						    	$empresa=pg_fetch_array($result_listaEmpresas,$i);
  								echo "<tr><td>".$empresa[2]."</td><td>".$empresa[1]."</td><td><span onclick=editar(".$empresa[0].") title=\"Editar Registro\" class=\"glyphicon glyphicon-pencil\" style=\"cursor:pointer\"></span></td></tr>";    	
  						    }
  						?>						
						</tbody>						
					</table>
					</div>
					</form>																																		
				</div>
				<div class="col-md-2"></div>				
				
			</div>

		</div>
	</div>


</body>
<script type="text/javascript" language="JavaScript">
	function editar(id){
		location.href="EditarEmpresa.php?id="+id;
	}
	
</script>
</html>