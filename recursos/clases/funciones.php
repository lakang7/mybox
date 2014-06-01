<?php

	function Conexion(){
		$conexion=pg_connect("host=localhost user=postgres password='Jcglobal2013' dbname=mybox port=5432");
		return $conexion;
	}
	
	function MenuPrincipalAdmin(){
				
	echo '<div class="container-fluid">';
		echo '<div class="row">';
  			echo '<div class="col-md-8" style="background-color:#318CCA; height: 50px; border-bottom: 1px solid #267BB6">';
  				echo '<div class="col-md-2"></div>';
				echo '<div class="col-md-3" style="color: #FFFFFF; line-height: 50px; font-size: 25px;font-family: \'Oswald\', sans-serif;">';
					echo 'Gaag Archivos';
				echo '</div>';	
				echo '<div class="col-md-7">';
				echo '</div>';				
  			echo '</div>';
  			echo '<div class="col-md-4" style="background-color:#318CCA; height: 50px; border-bottom: 1px solid #267BB6">';
  				
  			echo '</div>';  			
  			echo '<div class="col-md-12" style="background-color:#EDEDED; height: 50px; border-bottom: 1px solid #CCCCCC"></div>';
		echo '</div>';
	echo '</div>';
	
					
	}
	
	function INFO($linea){
		$fp = fopen('log.txt','a');
		fwrite($fp, $linea);		
	}
	
	
	function MenuLateralAdmin($indice){
		
		echo '<div class="list-group" style="font-family: \'PT Sans Narrow\', sans-serif; font-size: 16px;">';
		if($indice==1){
			echo '<a href="../administracion/CrearEmpresa.php" class="list-group-item active">Creación de Empresas</a>';
		}else{
			echo '<a href="../administracion/CrearEmpresa.php" class="list-group-item">Creación de Empresas</a>';
		}
		
		if($indice==2){
			echo '<a href="../administracion/ListarEmpresas.php" class="list-group-item active">Listado de Empresas</a>';
		}else{
			echo '<a href="../administracion/ListarEmpresas.php" class="list-group-item">Listado de Empresas</a>';
		}
		
		if($indice==3){
			echo '<a href="../administracion/CrearEmpleado.php" class="list-group-item active">Creación de Empleados</a>';
		}else{
			echo '<a href="../administracion/CrearEmpleado.php" class="list-group-item">Creación de Empleados</a>';
		}
		
		if($indice==4){
			echo '<a href="../administracion/ListarEmpleados.php" class="list-group-item active">Listado de Empleados</a>';
		}else{
			echo '<a href="../administracion/ListarEmpleados.php" class="list-group-item">Listado de Empleados</a>';
		}		
		
		if($indice==5){
			echo '<a href="../administracion/AdministrarArchivos.php" class="list-group-item active">Administrar Archivos</a>';
		}else{
			echo '<a href="../administracion/AdministrarArchivos.php" class="list-group-item">Administrar Archivos</a>';
		}			
	  	
	  	echo '<a href="../recursos/clases/acciones.php?action=9" class="list-group-item">Cerrar Sesión</a>';  		  		  		  		  		
		echo '</div>';
				
	}
	
	function MenuLateralCliente($indice){
		echo '<div class="list-group" style="font-family: \'PT Sans Narrow\', sans-serif; font-size: 16px;">';
	  	echo '<a href="../recursos/clases/acciones.php?action=11" class="list-group-item">Cerrar Sesión</a>';  		  		  		  		  		
		echo '</div>';		
	}
	
?>