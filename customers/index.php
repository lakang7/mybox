<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../recursos/bootstrap-3.1.1-dist/css/bootstrap.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Oswald:400,700' rel='stylesheet' type='text/css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="../recursos/bootstrap-3.1.1-dist/js/bootstrap.min.js"></script>
<?php
	require_once("../recursos/clases/funciones.php");
?>

<title>Portal del Cliente</title>
<style type="text/css">
.formulario{
background-color: #fff;
        padding: 20px;
        margin: 0 -20px; 
        -webkit-border-radius: 10px 10px 10px 10px;
           -moz-border-radius: 10px 10px 10px 10px;
                border-radius: 10px 10px 10px 10px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                box-shadow: 0 1px 2px rgba(0,0,0,.15);	
}	
</style>
</head>

<body style="background-color: #f5f5f5;">

    <div class="container">
    	<div class="row">
    		<div class="col-md-4"></div>
    		<div class="col-md-4" style="text-align: center">
    			    
      		<form class="form-signin formulario" method="post" role="form" style="margin-top: 50px;" action="../recursos/clases/acciones.php?action=10">
        		<h3 class="form-signin-heading" style="text-align: center">Inicio Sesión Empleado</h2>
        		<input name="email" id="email" type="email" class="form-control" placeholder="Email address" style="margin-bottom: 15px;" required autofocus>
        		<input name="passw" id="passw" type="password" class="form-control" placeholder="Password" style="margin-bottom: 15px;" required>
        		<button type="submit" class="btn btn-primary">Iniciar</button>
      		</form>    			    
    			    			
    		</div>    		    		
    	</div>
    	



    </div> <!-- /container -->


</body>
</html>