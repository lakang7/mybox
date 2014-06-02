<?php session_start();

	require_once ("funciones.php");
	
	/*Creación de Carpeta*/
	function crear_carpeta($idEmpresa,$nombreCarpeta,$padre){
		$con=Conexion();
		
		$sql_ultimaCarpeta="select last_value from carpeta_idcarpeta_seq";
		$result_ultimaCarpeta=pg_exec($con,$sql_ultimaCarpeta);
		$idCarpeta=pg_fetch_array($result_ultimaCarpeta);			
		
		if($idCarpeta[0]==1){
			$sql_cuenta="select count(*) from carpeta;";
			$result_cuenta=pg_exec($con,$sql_cuenta);
			$arreglo=pg_fetch_array($result_cuenta,0);
			if($arreglo[0]==0){
				$idCarpeta[0]=1;
			}				
			if($arreglo[0]==1){
				$idCarpeta[0]=2;
			}	
			if($arreglo[0]>1){
				$idCarpeta[0]++;
			}					
		}else{
			$idCarpeta[0]++;
		}
		
		$sql_insertCarpeta="insert into carpeta values (nextval('carpeta_idcarpeta_seq'),'".$idEmpresa."','".$nombreCarpeta."',".$padre.",".($idCarpeta[0]).",now());";
		$result_insertCarpeta = pg_exec($con,$sql_insertCarpeta);		
		
		if($padre==0){
			$directorio="../../archivos/Folder".($idCarpeta[0]);
		}else{
			
			$listUrls=array();
			$contaodrUrls=0;
			
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
			
			$directorio="../../archivos/";
			for($i=($contaodrUrls-1);$i>=0;$i--){
				if($i==0){
					$directorio=$directorio."Folder".$listUrls[$i]."/Folder".($idCarpeta[0]);
				}else{
					$directorio=$directorio."Folder".$listUrls[$i]."/";
				}				
			}
						
		}
		
		$dirmake = mkdir($directorio, 0777); 
		
		return $idCarpeta[0];
	}
	
	/*Registro de Empresas*/
	if($_GET["action"]==1){
		$con=Conexion();		
		$sql_insert="insert into empresa values(nextval('empresa_idempresa_seq'),'".$_POST["nombre_empresa"]."','".$_POST["rfc_empresa"]."',now())";
		$result_insert=pg_exec($con, $sql_insert);	
		
		$sql_ultimaEmpresa="select last_value from empresa_idempresa_seq";
		$result_ultimaEmpresa=pg_exec($con,$sql_ultimaEmpresa);
		$idEmpresa=pg_fetch_array($result_ultimaEmpresa);			
		crear_carpeta($idEmpresa[0],$_POST["nombre_empresa"],0);			
				
		?>
			<script type="text/javascript" language="JavaScript" >
				alert("Empresa Registrada Satisfactoriamente!!!");
				location.href="../../administracion/CrearEmpresa.php";
			</script>
		<?php							
	}
	
	/*Registro de Empleado*/
	if($_GET["action"]==2){
		$con=Conexion();		
		$sql_insert="insert into empleado values(nextval('empleado_idempleado_seq'),'".$_POST["empresa_empleado"]."','".$_POST["nombre_empleado"]."','".$_POST["email_empleado"]."','".$_POST["clave"]."','".$_POST["rfc_empleado"]."','".$_POST["tipo_empleado"]."',now(),null)";
		$result_insert=pg_exec($con, $sql_insert);
		
		$sql_carpetaPadre="select * from carpeta where idempresa='".$_POST["empresa_empleado"]."';";
		$result_carpetaPadre= pg_exec($con,$sql_carpetaPadre);
		$carpetaPadre=pg_fetch_array($result_carpetaPadre,0);		
		$idcarpeta=crear_carpeta($_POST["empresa_empleado"], $_POST["nombre_empleado"], $carpetaPadre[0]);
		
		$sql_ultimoEmpleado="select last_value from empleado_idempleado_seq;";
		$result_ultimoEmpleado=pg_exec($con,$sql_ultimoEmpleado);
		$ultimo=pg_fetch_array($result_ultimoEmpleado,0);
		$ultimoEmpleado=$ultimo[0];
		
		$sql_insertPermiso="insert into permiso_carpeta values(nextval('permiso_carpeta_idpermiso_carpeta_seq'),'".$ultimoEmpleado."','".$idcarpeta."',2)";
		$result_insertPermiso=pg_exec($con,$sql_insertPermiso);								
												
		?>
			<script type="text/javascript" language="JavaScript" >
				alert("Empleado Registrada Satisfactoriamente!!!");
				location.href="../../administracion/CrearEmpleado.php";
			</script>
		<?php							
	}
	
	/*Edición de Empresas*/
	if($_GET["action"]==3){
		$con=Conexion();
		$sql_update="update empresa set nombre='".$_POST["nombre_empresa"]."', rfc='".$_POST["rfc_empresa"]."' where idempresa='".$_POST["idempresa"]."'";
		$result_update=pg_exec($con,$sql_update);
		
		$sql_updateFolder="update carpeta set nombre='".$_POST["nombre_empresa"]."' where idempresa='".$_POST["idempresa"]."' and padre=0; ";
		$result_updateFolder=pg_exec($con,$sql_updateFolder);
		
		
		?>
			<script type="text/javascript" language="JavaScript" >
				alert("Empresa Editada Satisfactoriamente!!!");
				location.href="../../administracion/ListarEmpresas.php";
			</script>
		<?php				
	}	
	
	/*Editar Empleados*/
	if($_GET["action"]==4){
		$con=Conexion();
		$sql_update="update empleado set nombre='".$_POST["nombre_empleado"]."', email='".$_POST["email_empleado"]."', rfc='".$_POST["rfc_empleado"]."', clave='".$_POST["clave"]."'  where idempleado='".$_POST["idempleado"]."' ";		
		$result_update=pg_exec($con,$sql_update);
		?>
			<script type="text/javascript" language="JavaScript" >
				alert("Empledo Editado Satisfactoriamente!!!");
				location.href="../../administracion/ListarEmpleados.php";
			</script>
		<?php		
		
	}	
	
	/*Ajax para recargar el directorio*/
	if($_GET["action"]==5){
		//echo $_POST["carpeta"];
		$con=Conexion();
		
		?>
		
				<div class="col-md-10" style="border-bottom: 1px solid #E9E9E9; height: 60px; line-height: 60px;font-family: 'PT Sans Narrow', sans-serif; font-size: 22px;">
					Directorio de Archivos
				</div>
				<input type="hidden" id="mostrandoC" name="mostrandoC" value="<?php echo $_POST["carpeta"]; ?>">
				<div id="createfolder" name="createfolder" ></div>
				<div class="col-md-2"></div>
				
				<div class="col-md-10" style="border-bottom: 1px solid #E9E9E9; height: auto; padding-bottom: 15px; padding-top: 15px;">																				

					<div class="row">
						<div class="col-md-12" >
						
						<?php
						
							$padre=$_POST["carpeta"];
							$listUrls=array();
							$contaodrUrls=0;
					
							$listUrls[$contaodrUrls]=$_POST["carpeta"];
							$contaodrUrls++;
							$band=0;
			
							if($_POST["carpeta"]>0){
									
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
							
							}
							echo "<ol class=\"breadcrumb\" style=\"font-family: 'PT Sans Narrow', sans-serif; font-size: 16px;\">";
							if($_POST["carpeta"]==0){
								echo "<li onclick=directorio(0) style=\"cursor:pointer\">Root</li>";
							}else{
							
								echo "<li onclick=directorio(0) style=\"cursor:pointer\">Root</li>";							
								for($i=($contaodrUrls-1);$i>=0;$i--){
									$sql_carpeta="select * from carpeta where idcarpeta='".$listUrls[$i]."'";								
									$result_carpeta=pg_exec($con,$sql_carpeta);
									$carpeta=pg_fetch_array($result_carpeta,0);	
									if($i==0){
										echo "<li class=\"active\">".$carpeta[2]."</li>";
									}else{
										echo "<li onclick=directorio(".$carpeta[0].") style=\"cursor:pointer\">".$carpeta[2]."</li>";
									}		
								}
							
							}
							echo "</ol>";													
																		
						?>
																			  						  						  																									
						</div>
						<div class="col-md-8">
						
						<div class="table-responsive">
							<table class="table table-hover">
  								<tbody style="border-bottom: 1px solid #DDDDDD">
  									<?php
										$sql_listaCarpetas="select * from carpeta where padre='".$_POST["carpeta"]."' order by nombre";
										$result_listaCarpetas=pg_exec($con,$sql_listaCarpetas);
  						    			for($i=0;$i<pg_num_rows($result_listaCarpetas);$i++){
  						    				$carpeta=pg_fetch_array($result_listaCarpetas,$i);
  											echo "<tr style='cursor:pointer;' onclick=directorio(".$carpeta[0].")><td><img src='../recursos/imagenes/iconfolder.png' height='42' width='42'></td><td style='line-height: 40px; font-family: \"PT Sans Narrow\", sans-serif; font-size: 17px;'>".$carpeta[2]."</td><td></td><td></td></tr>";    	
  						    			}
										
										$sql_listaArchivos="select * from archivo where idcarpeta='".$_POST["carpeta"]."';";
										$result_listaArchivos=pg_exec($con,$sql_listaArchivos);
										for($i=0;$i<pg_num_rows($result_listaArchivos);$i++){
											$archivo=pg_fetch_array($result_listaArchivos,$i);	
											$sqltipo_archivo="select * from tipo_archivo where idtipo_archivo='".$archivo[2]."';";
											$resulttipo_archivo=pg_exec($con,$sqltipo_archivo);
											$tipo=pg_fetch_array($resulttipo_archivo,0);											
											
											$listUrls=array();
											$contaodrUrls=0;
					
											$padre=$_POST["carpeta"];
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
			
											$directorio="";
											for($j=($contaodrUrls-1);$j>=0;$j--){
												if($j==0){
													$ext=explode(".",$archivo[3]);
													$directorio=$directorio."Folder".$listUrls[$j]."/".$archivo[0].".".$tipo[3];
												}else{
													$directorio=$directorio."Folder".$listUrls[$j]."/";
												}				
											}		
											
											$directorio=$directorio."&idarchivo=".$archivo[0];										
											
											//echo $directorio;																																								

											echo "<tr style='cursor:pointer;'><td><img src='".$tipo[2]."' height='42' width='42'></td>";
											$date = date_create($archivo[4]);
											echo "<td style='font-family: \"PT Sans Narrow\", sans-serif; font-size: 17px;'><div class=\"col-md-12\">".$archivo[3]."</div><div class=\"col-md-12\" style=\"font-size: 13px;color:#848484\" >Subido el ".date_format($date,'d-m-Y H:i:s')."</div>";
											echo "</td>";
											echo "<td ><a href=\"download.php?file=".$directorio."\"><span class=\"glyphicon glyphicon-save\" style='margin-top: 10px;' title=\"Descargar Archivo\"></span></a></td><td ><span onclick=eliminarArchivo(".$archivo[0].") class=\"glyphicon glyphicon-trash\" style='margin-top: 10px;' title=\"Eliminar Archivo\"></span></td></tr>";  
										}
  									?>						
								</tbody>						
							</table>
						</div>
						
					
						<div class="col-md-12">
						<?php
							$band=0;
							if($_POST["carpeta"]>0){
								$sql_carpeta0="select * from carpeta where idcarpeta='".$_POST["carpeta"]."';";
								$result_carpeta0=pg_exec($con,$sql_carpeta0);
								$carpeta0=pg_fetch_array($result_carpeta0,0);							
								if($carpeta0[3]!=0){
								
									$sql_carpeta1="select * from carpeta where idcarpeta='".$carpeta0[0]."';";
									$result_carpeta1=pg_exec($con,$sql_carpeta1);								
									$carpeta1=pg_fetch_array($result_carpeta1,0);
								
									if($carpeta1[3]!=0){
										$band=1;
									}								
								}
							
							}
							if($band==1){
							?>
								
							<div class="col-md-5">
								<button onclick="crearcarpeta(<?php echo $_POST["carpeta"]; ?>)" type="button" class="btn btn-default btn-xs" style="font-family: 'PT Sans Narrow', sans-serif; font-size: 16px;"> <span class="glyphicon glyphicon-folder-open" style="margin-right: 10px;"></span>Crear Carpeta</button>																
							</div>
							<div class="col-md-7">
								<form>		
									<input id="file_upload" name="file_upload" type="file" multiple="true">
								</form>																
							</div>									
								
							<?php								
							}
							
						
						?>
					
												
						</div>
										
						
						</div>	
						<div class="col-md-4">
							<div class="row">
								<div class="col-md-12" style="border-top: 1px solid #DDDDDD;font-family: 'PT Sans Narrow', sans-serif; font-size: 16px; height: 35px; line-height: 35px;border-bottom: 1px solid #DDDDDD;">								
									Con permiso en el directorio
								</div>
								<?php
									if($_POST["carpeta"]>0){
									
									$sql_carpeta="select * from carpeta where idcarpeta='".$_POST["carpeta"]."';";
									$result_carpeta=pg_exec($con,$sql_carpeta);
									$carpeta=pg_fetch_array($result_carpeta,0);									
									$idEmpresa=$carpeta[1];
									
									$listaEmpleados=array();
									$cuenta=0;
									
									$sql_listaUsuarios="select * from empleado where idempresa='".$idEmpresa."' and tipo_empleado=2;";
									$result_listaUsuarios=pg_exec($con,$sql_listaUsuarios);
									for($i=0;$i<pg_num_rows($result_listaUsuarios);$i++){										
										$usuario=pg_fetch_array($result_listaUsuarios,$i);
										$band=0;
										for($j=0;$j<$cuenta;$j++){
											if($listaEmpleados[$j]==$usuario[0]){
												$band=1;
											}
										}
										if($band==0){
											$listaEmpleados[$cuenta]=$usuario[0];
											$cuenta++;
										}										
									}
									
									$sql_permisos="select * from permiso_carpeta where idcarpeta='".$_POST["carpeta"]."';";
									$result_permisos=pg_exec($con,$sql_permisos);
									for($i=0;$i<pg_num_rows($result_permisos);$i++){
										$permiso=pg_fetch_array($result_permisos,$i);
										$band=0;
										for($j=0;$j<$cuenta;$j++){
											if($listaEmpleados[$j]==$permiso[1]){
												$band=1;
											}
										}
										if($band==0){
											$listaEmpleados[$cuenta]=$permiso[1];
											$cuenta++;
										}										
									}
									
									
									for($i=0;$i<$cuenta;$i++){
										$sql_infoUsuario="select * from empleado where idempleado='".$listaEmpleados[$i]."'";
										$result_infoUsuario=pg_exec($con,$sql_infoUsuario);
										$usuario=pg_fetch_array($result_infoUsuario,0);
										echo "<div class=\"col-md-12\" style=\"font-family: 'PT Sans Narrow', sans-serif; font-size: 14px; height: 30px; line-height: 30px;border-bottom: 1px solid #DDDDDD;\">";								
										echo "<span class=\"glyphicon glyphicon-user\" style=\"margin-right: 10px;\"></span>".$usuario[2];
										echo "</div>";										
									}
									
									}
								?>																								
																
							</div>
						
						</div>												
					</div>																																																				
				</div>												
				<div class="col-md-2"></div>
				<?php								
	}

	
	/*Creación de carpeta desde el boton*/
	if($_GET["action"]==6){
		//echo $_POST["nombre"]."  ".$_POST["padre"];
		$con=Conexion();
		
		if($_POST["padre"]>0){
			
			$sql_carpeta="select * from carpeta where idcarpeta='".$_POST["padre"]."';";
			$result_carpeta=pg_exec($con,$sql_carpeta);
			$carpeta=pg_fetch_array($result_carpeta);
			$idcarpeta=crear_carpeta($carpeta[1],$_POST["nombre"],$_POST["padre"]);
			
			$sql_permisoPadre="select * from permiso_carpeta where idcarpeta='".$_POST["padre"]."';";
			$result_permisoPadre=pg_exec($con,$sql_permisoPadre);
			$permisoPadre=pg_fetch_array($result_permisoPadre,0);
			$idEmpleado=$permisoPadre[1];
			
			$sql_insertPermiso="insert into permiso_carpeta values(nextval('permiso_carpeta_idpermiso_carpeta_seq'),'".$idEmpleado."','".$idcarpeta."',2)";
			$result_insertPermiso=pg_exec($con,$sql_insertPermiso);									
		}
		
		
		pg_close($con);				
	}
	
	if($_GET["action"]==7){
		$con=Conexion();
		//echo $_POST["archivo"];
		$sql_Archivo="select * from archivo where idarchivo='".$_POST["archivo"]."';";
		$result_Archivo=pg_exec($con,$sql_Archivo);
		$archivo=pg_fetch_array($result_Archivo,0);
		
		$sql_Carpeta="select * from carpeta where idcarpeta='".$archivo[1]."'";
		$result_Carpeta=pg_exec($con,$sql_Carpeta);
		$carpeta=pg_fetch_array($result_Carpeta,0);
		$idcarpeta=$carpeta[0];
		$idarchivo=$_POST["archivo"];
		
		//echo $idcarpeta."</br>";
		
		$listUrls=array();
		$contaodrUrls=0;
			
		$padre=$idcarpeta;
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
			
		$directorio="../../archivos/";
		for($i=($contaodrUrls-1);$i>=0;$i--){
			if($i==0){
				$ext=explode(".",$archivo[3]);
				$directorio=$directorio."Folder".$listUrls[$i]."/".$archivo[0].".".$ext[1];
			}else{
				$directorio=$directorio."Folder".$listUrls[$i]."/";
			}				
		}		
		
		//echo $directorio;			
		if(file_exists($directorio)) 
		{
			//echo "el directorio existe";
			if(unlink($directorio)){ 
				print "El archivo fue borrado"; 
				$sql_eliminaArchivo="delete from archivo where idarchivo='".$archivo[0]."';";
				$result_eliminaArchivo=pg_exec($con,$sql_eliminaArchivo);				
			} 			
		}													
		pg_close($con);
	}
	
	/*Inicio de sesión administrador*/
	if($_GET["action"]==8){
		$con=Conexion();
		$sql_existe="select * from usuario where email='".$_POST["email"]."' and pass='".$_POST["passw"]."';";
		$result_existe=pg_exec($con,$sql_existe);
		if(pg_num_rows($result_existe)>0){
			$administrador=pg_fetch_array($result_existe,0);
			$_SESSION["administrador"]=$administrador[0];
			?>
				<script type="text/javascript" language="JavaScript">
					location.href="../../administracion/AdministrarArchivos.php";
				</script>
			<?php
		}else{
			?>
				<script type="text/javascript" language="JavaScript">
					alert("Usuario o Password Incorrectos.");
					location.href="../../administracion/index.php";
				</script>
			<?php			
		}
		
		pg_close($con);		
	}
	
	/*Cerrar Sesión Administrador*/
	if($_GET["action"]==9){		
		session_destroy();
		?>
			<script type="text/javascript" language="JavaScript">
				location.href="../../administracion/index.php";
			</script>			
		<?php		
	}
	
	/*Inicio de sesión cliente*/
	if($_GET["action"]==10){
		$con=Conexion();
		$sql_existe="select * from empleado where email='".$_POST["email"]."' and clave='".$_POST["passw"]."';";
		$result_existe=pg_exec($con,$sql_existe);
		if(pg_num_rows($result_existe)>0){
			$empleado=pg_fetch_array($result_existe,0);
			$_SESSION["empleado"]=$empleado[0];
			?>
				<script type="text/javascript" language="JavaScript">
					location.href="../../customers/AdministrarArchivos.php";
				</script>
			<?php
		}else{
			?>
				<script type="text/javascript" language="JavaScript">
					alert("Usuario o Password Incorrectos.");
					location.href="../../customers/index.php";
				</script>
			<?php			
		}
		
		pg_close($con);		
	}	
	
	/*Cerrar Sesión Cliente*/
	if($_GET["action"]==11){		
		session_destroy();
		?>
			<script type="text/javascript" language="JavaScript">
				location.href="../../customers/index.php";
			</script>			
		<?php		
	}	

	/*Ajax para recargar el directorio del cliente*/
	if($_GET["action"]==12){
		//echo $_POST["carpeta"];
		$con=Conexion();														
		$sql_empleado="select * from empleado where idempleado='".$_SESSION["empleado"]."'";
		$result_empleado=pg_exec($con,$sql_empleado);
		$empleado=pg_fetch_array($result_empleado,0);		
		
		?>
		
				<div class="col-md-10" style="border-bottom: 1px solid #E9E9E9; height: 60px; line-height: 60px;font-family: 'PT Sans Narrow', sans-serif; font-size: 22px;">
					Directorio de Archivos 										
				</div>
				<input type="hidden" id="mostrandoC" name="mostrandoC" value="<?php echo $_POST["carpeta"]; ?>">
				<div id="createfolder" name="createfolder" ></div>
				<div class="col-md-2"></div>
				
				<div class="col-md-10" style="border-bottom: 1px solid #E9E9E9; height: auto; padding-bottom: 15px; padding-top: 15px;">																				

					<div class="row">
						<div class="col-md-12" >
						
						<?php
						
							$padre=$_POST["carpeta"];
							$listUrls=array();
							$contaodrUrls=0;
					
							$listUrls[$contaodrUrls]=$_POST["carpeta"];
							$contaodrUrls++;
							$band=0;
			
							if($_POST["carpeta"]>0){
									
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
							
							}
							echo "<ol class=\"breadcrumb\" style=\"font-family: 'PT Sans Narrow', sans-serif; font-size: 16px;\">";
							if($_POST["carpeta"]==0){
								echo "<li onclick=directorio(0) style=\"cursor:pointer\">Root</li>";
							}else{
								$resta=0;
								if($empleado[6]==2){
									$resta=1;
								}else{
									$resta=2;
								}
								//echo "<li onclick=directorio(0) style=\"cursor:pointer\">Root</li>";							
								for($i=($contaodrUrls-$resta);$i>=0;$i--){
									$sql_carpeta="select * from carpeta where idcarpeta='".$listUrls[$i]."'";								
									$result_carpeta=pg_exec($con,$sql_carpeta);
									$carpeta=pg_fetch_array($result_carpeta,0);	
									if($i==0){
										echo "<li class=\"active\">".$carpeta[2]."</li>";
									}else{
										echo "<li onclick=directorio(".$carpeta[0].") style=\"cursor:pointer\">".$carpeta[2]."</li>";
									}		
								}
							
							}
							echo "</ol>";													
																		
						?>
																			  						  						  																									
						</div>
						<div class="col-md-12">
						
						<div class="table-responsive">
							<table class="table table-hover">
  								<tbody style="border-bottom: 1px solid #DDDDDD">
  									<?php
										$sql_listaCarpetas="select * from carpeta where padre='".$_POST["carpeta"]."' order by nombre";
										$result_listaCarpetas=pg_exec($con,$sql_listaCarpetas);
  						    			for($i=0;$i<pg_num_rows($result_listaCarpetas);$i++){
  						    				$carpeta=pg_fetch_array($result_listaCarpetas,$i);
  											echo "<tr style='cursor:pointer;' onclick=directorio(".$carpeta[0].")><td><img src='../recursos/imagenes/iconfolder.png' height='42' width='42'></td><td style='line-height: 40px; font-family: \"PT Sans Narrow\", sans-serif; font-size: 17px;'>".$carpeta[2]."</td><td></td></tr>";    	
  						    			}
										
										$sql_listaArchivos="select * from archivo where idcarpeta='".$_POST["carpeta"]."';";
										$result_listaArchivos=pg_exec($con,$sql_listaArchivos);
										for($i=0;$i<pg_num_rows($result_listaArchivos);$i++){
											$archivo=pg_fetch_array($result_listaArchivos,$i);	
											$sqltipo_archivo="select * from tipo_archivo where idtipo_archivo='".$archivo[2]."';";
											$resulttipo_archivo=pg_exec($con,$sqltipo_archivo);
											$tipo=pg_fetch_array($resulttipo_archivo,0);											
											
											$listUrls=array();
											$contaodrUrls=0;
					
											$padre=$_POST["carpeta"];
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
			
											$directorio="";
											for($j=($contaodrUrls-1);$j>=0;$j--){
												if($j==0){
													$ext=explode(".",$archivo[3]);
													$directorio=$directorio."Folder".$listUrls[$j]."/".$archivo[0].".".$tipo[3];
												}else{
													$directorio=$directorio."Folder".$listUrls[$j]."/";
												}				
											}		
											
											$directorio=$directorio."&idarchivo=".$archivo[0];										
											
											//echo $directorio;																																								

											echo "<tr style='cursor:pointer;'><td><img src='".$tipo[2]."' height='42' width='42'></td>";
											$date = date_create($archivo[4]);
											echo "<td style='font-family: \"PT Sans Narrow\", sans-serif; font-size: 17px;'><div class=\"col-md-12\">".$archivo[3]."</div><div class=\"col-md-12\" style=\"font-size: 13px;color:#848484\" >Subido el ".date_format($date,'d-m-Y H:i:s')."</div>";
											echo "</td>";
											echo "<td ><a href=\"download.php?file=".$directorio."\"><span class=\"glyphicon glyphicon-save\" style='margin-top: 10px;' title=\"Descargar Archivo\"></span></a></td></tr>";  
										}
  									?>						
								</tbody>						
							</table>
						</div>
						
						
																				
						</div>	
												
					</div>																																																				
				</div>												
				<div class="col-md-2"></div>
				<?php								
	}

?>