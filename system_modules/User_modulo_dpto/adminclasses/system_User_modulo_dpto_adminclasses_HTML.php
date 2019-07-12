<?php
/**
*MODULO para el Manejo de Usuarios del Sistema
*
* @ Jairo Duvan Diaz Martinez
* ultima actualizacion: Jairo Duvan Diaz Martinez -->lunes 1 de marzo 2004
*/

// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos visuales para realizar la administracion de usuarios
*/


class system_User_modulo_dpto_adminclasses_HTML extends system_User_modulo_dpto_admin
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function system_User_modulo_dpto_admin_HTML()
	{
		$this->salida='';
		$this->system_User_modulo_dpto_admin();
		return true;
	}



/*fucnion que lista los modulos a los cuales el tiene permisos*/
	
	function MenuModulos($dats)
	{

	$this->salida.="<center>\n";
	if($dats)
				{
					$this->salida .= ThemeMenuAbrirTabla("MODULOS SISTEMA SIIS","50%");
					for($i=0;$i<sizeof($dats);$i++)
					{

						$tipo=$dats[$i][modulo_tipo];
						$modulo=strtoupper($dats[$i][modulo]);
						$mod=$dats[$i][modulo];
						$desc=$dats[$i][descripcion];
						$sw=$dats[$i][sw_admin];

						$this->salida.="<table border='0' width='100%'>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left' class='normal_10N'>";
						$this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;<a href=\"".ModuloGetURL('system','User_modulo_dpto','admin','Decision',array("mod"=>$mod))."\">($tipo)&nbsp;&nbsp;$modulo</a>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left'>";
						$this->salida.="			<div class='normal_10_menu' valign='middle'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;".$desc."</div>";
						$this->salida.="		</td>";
						$this->salida.="	</tr>";
						$this->salida.="</table>";
						$this->salida .="<br>";
					}

					$this->salida.="<table border='0' align='center'>";
					$this->salida.="	<tr><td>";
					$this->salida.="	</td></tr>";
					$this->salida.="</table>";
					$this->salida.="<table align=\"center\" width='20%' border=\"0\">";
					$action2=ModuloGetURL('system','Menu','user','main');
					$this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";
					$this->salida .= ThemeMenuCerrarTabla();
				}
				else
				{
					$this->salida .= ThemeMenuAbrirTabla("MODULOS SITEMA SIIS","50%");
					$this->salida.="<table  align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"85%\">\n";
					$this->salida.="	<tr>\n";
					$this->salida.="		<td align=\"center\" class=\"label_error\">No existen Modulos.</td>\n";
					$this->salida.="	</tr>\n";
					$this->salida.="</table>\n";
					$this->salida.="<table align=\"center\" width='20%' border=\"0\">";
					$action2=ModuloGetURL('system','Menu','user','main');
					$this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";
					$this->salida .= ThemeMenuCerrarTabla();
				}
				//$this->salida.="</center>\n";
  return true;
	}



/*funcion que visualiza los departamentos segun los permisos del usuario*/

	function MenuDpto($mod)
	{
	if(empty($_SESSION['USER_ADMIN_MOD']['MODULO']))
	{
		$_SESSION['USER_ADMIN_MOD']['MODULO']=$mod;
	}
	$dats=$this->TraerDpto();
	$this->salida.="<center>\n";
	if($dats)
				{
					$this->salida .= ThemeMenuAbrirTabla("PERMISOS DEPARTAMENTOS","50%");
					for($i=0;$i<sizeof($dats);$i++)
					{

						$dpto=$dats[$i][departamento];
						$desc=strtoupper($dats[$i][descripcion]);
						$centroU=$dats[$i][centro_utilidad];

						$this->salida.="<table border='0' width='100%'>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left' class='normal_10N'>";
						$this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;<a href=\"".ModuloGetURL('system','User_modulo_dpto','admin','DatosRetorno',array("dpto"=>$dpto,"desc"=>$desc))."\">$desc</a>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left'>";
						$this->salida.="			<div class='normal_10_menu' valign='middle'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>Codigo&nbsp;:".$dpto."</div>";
						$this->salida.="		</td>";
						$this->salida.="	</tr>";
						$this->salida.="</table>";
						$this->salida .="<br>";
					}

					$this->salida.="<table border='0' align='center'>";
					$action3=ModuloGetURL('system','User_modulo_dpto','admin','main');
					$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
					$this->salida.="	<tr>";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Menu\"></td></tr>";
					$this->salida.="</table>";


					$this->salida .= ThemeMenuCerrarTabla();
				}
				else
				{
					$this->salida .= ThemeMenuAbrirTabla("PERMISOS DEPARTAMENTOS","50%");
					$this->salida.="<table  align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"85%\">\n";
					$this->salida.="	<tr>\n";
					$this->salida.="		<td align=\"center\" class=\"label_error\">No existen Departamentos.</td>\n";
					$this->salida.="	</tr>\n";
					$this->salida.="</table>\n";
					$this->salida .= ThemeMenuCerrarTabla();
				}
				//$this->salida.="</center>\n";
  return true;
	}



/*funcion que visualiza las empresas segun los permisos del usuario*/
	function MenuEmpresa()
	{
    if(strtoupper($_SESSION['USER_ADMIN_MOD']['MODULO'])!='CONTRATACION')
		{
			$URL='MenuCentro';
		}
		else
		{
			$URL='DatosRetorno';
		}

		$dats=$this->TraerEmpresa();
		$this->salida.="<center>\n";
	if($dats)
				{
					$this->salida .= ThemeMenuAbrirTabla("PERMISOS EMPRESAS","50%");
					for($i=0;$i<sizeof($dats);$i++)
					{

						$emp=$dats[$i][empresa_id];
						$desc=strtoupper($dats[$i][razon_social]);
						$id=$dats[$i][id];
						$web=$dats[$i][website];
						$sw_emp=$dats[$i][sw_usuarios_multiempresa];

						$this->salida.="<table border='0' width='100%'>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left' class='normal_10N'>";
						$this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;<a href=\"".ModuloGetURL('system','User_modulo_dpto','admin',$URL,array("emp"=>$emp,"sw_e"=>$sw_emp,"espia"=>true,"desc"=>$desc))."\">$desc</a>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left'>";
						$this->salida.="			<div class='normal_10_menu' valign='middle'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>Codigo&nbsp;:".$id."&nbsp;&nbsp;$web</div>";
						$this->salida.="		</td>";
						$this->salida.="	</tr>";
						$this->salida.="</table>";
						$this->salida .="<br>";
					}

					$this->salida.="<table border='0' align='center'>";
					$action3=ModuloGetURL('system','User_modulo_dpto','admin','main');
					$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
					$this->salida.="	<tr>";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Menu\"></td></tr>";
					$this->salida.="</table>";


					$this->salida .= ThemeMenuCerrarTabla();
				}
				else
				{
					$this->salida .= ThemeMenuAbrirTabla("PERMISOS EMPRESAS","50%");
					$this->salida.="<table  align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"85%\">\n";
					$this->salida.="	<tr>\n";
					$this->salida.="		<td align=\"center\" class=\"label_error\">No existen Modulos.</td>\n";
					$this->salida.="	</tr>\n";
					$this->salida.="</table>\n";
					$this->salida .= ThemeMenuCerrarTabla();
				}
				//$this->salida.="</center>\n";
  return true;
	}



/*funcion que visualiza los centros de utilidad segun los permisos del usuario*/
	function MenuCentro()
	{
		if($_REQUEST['espia'])
		{
			$_SESSION['USER_ADMIN_MOD']['EMPRESA']=$_REQUEST['emp'];
			$_SESSION['USER_ADMIN_MOD']['SW_EMPRESA']=$_REQUEST['sw_e'];
		}
    $dats=$this->TraerCentro();
		$this->salida.="<center>\n";
	if($dats)
				{
					$this->salida .= ThemeMenuAbrirTabla("PERMISOS CENTROS UTILIDAD","50%");
					for($i=0;$i<sizeof($dats);$i++)
					{

						$emp=$dats[$i][empresa_id];
						$desc=strtoupper($dats[$i][descripcion]);
						$centro=$dats[$i][centro_utilidad];

						$this->salida.="<table border='0' width='100%'>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left' class='normal_10N'>";
						$this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;<a href=\"".ModuloGetURL('system','User_modulo_dpto','admin','DatosRetornoCentro',array("cu"=>$centro))."\">$desc</a>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left'>";
						$this->salida.="			<div class='normal_10_menu' valign='middle'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>Codigo&nbsp;:".$centro."&nbsp;&nbsp;$web</div>";
						$this->salida.="		</td>";
						$this->salida.="	</tr>";
						$this->salida.="</table>";
						$this->salida .="<br>";
					}

					$this->salida.="<table border='0' align='center'>";
					$action3=ModuloGetURL('system','User_modulo_dpto','admin','MenuEmpresa');
					$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
					$this->salida.="	<tr>";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Menu\"></td></tr>";
					$this->salida.="</table>";


					$this->salida .= ThemeMenuCerrarTabla();
				}
				else
				{
					$this->salida .= ThemeMenuAbrirTabla("PERMISOS EMPRESAS","50%");
					$this->salida.="<table  align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"85%\">\n";
					$this->salida.="	<tr>\n";
					$this->salida.="		<td align=\"center\" class=\"label_error\">No existen Modulos.</td>\n";
					$this->salida.="	</tr>\n";
					$this->salida.="</table>\n";
					$this->salida .= ThemeMenuCerrarTabla();
				}
				//$this->salida.="</center>\n";
  return true;
	}





function SetStyle($campo)
		{
					if ($this->frmError[$campo] || $campo=="MensajeError"){
						if ($campo=="MensajeError"){
				$arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
						}
						return ("label_error");
					}
				return ("label");
		}



/*funcion que muestra los grupos de tablas de permisos con sus usuarios
* por ejemplo cajas_usuarios , muestra caja hospitalaria,caja pto venta,
*/
function FormaMostrarElementos($id,$tabla,$campo,$datos,$tipo,$usuario,$campo_vect)
{ 
					$this->salida .= ThemeAbrirTabla("MODULOS SISTEMA SIIS");
					if($_SESSION['USER_ADMIN_MOD']['DPTO'])
					{
							$this->salida.="<br><table border=\"0\"    align=\"center\"   width=\"80%\" >";
							$this->salida.="<tr>";
							$this->salida .= "<td align=\"center\" class=\"modulo_table_title\" >ADMINISTRACION </td><td  align=\"center\" class=\"modulo_table_title\" >DEPARTAMENTO</td>";
							$this->salida.="</tr>";
							$this->salida.="<tr>";
							$this->salida .= "<td class=\"modulo_list_oscuro\"  align=\"center\">".$_SESSION['USER_ADMIN_MOD']['TIPO_MENU']."</td><td class=\"modulo_list_oscuro\"  align=\"center\">COD&nbsp;: ".$_SESSION['USER_ADMIN_MOD']['DPTO']."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['USER_ADMIN_MOD']['NOMBRE']."";
							$this->salida.="</td>";
							$this->salida.="</table><br>";
					}
					if($_SESSION['USER_ADMIN_MOD']['EMPRESA'])
					{

					$this->salida.="<br><table border=\"0\" align=\"center\"   width=\"80%\" >";
					$this->salida.="<tr>";
					$this->salida .= "<td align=\"center\" class=\"modulo_table_title\" >ADMINISTRACION </td><td  align=\"center\" class=\"modulo_table_title\" >EMPRESA</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr>";
					$this->salida .= "<td class=\"modulo_list_oscuro\"  align=\"center\">".$_SESSION['USER_ADMIN_MOD']['TIPO_MENU']."</td><td align='center' class=\"modulo_list_oscuro\" >EMPRESA:&nbsp;&nbsp;".$this->TraerNombre($_SESSION['USER_ADMIN_MOD']['EMPRESA'])."";
					$this->salida.="</td>";
					$this->salida.="</table>";
					}

					$action3=ModuloGetURL('system','Usuarios','admin','InsertarPermisosU',array("uid"=>$uid,'NombreUsuario'=>$NombreUsuario,'usuario'=>$Usuario,"empID"=>$empresa,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
					if(!empty($usuario))
					{
								$this->salida.="<table  align=\"center\" border=\"0\" width=\"90%\">"; // class=\"modulo_table_list\"
								$this->salida .= $this->SetStyle("MensajeError");
								$this->salida.="<tr class=\"modulo_table_title\">";
								$this->salida.="  <td align=\"left\" colspan=\"3\">PERMISOS&nbsp; ".strtoupper($tabla)."</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
								$this->salida.="  <td width=\"25%\">".strtoupper ($campo)."</td>";
								$this->salida.="  <td width=\"20%\">LOGIN</td>";
								$this->salida.="  <td width=\"50%\">USUARIO</td>";
								//$this->salida.="  <td width=\"50%\">UID</td>";
								$this->salida.="</tr>";
								$d=0;
					
									foreach($usuario as $l=> $u)
									{
											if( $d % 2){ $estilo='modulo_list_claro';}
											else {$estilo='modulo_list_oscuro';}
											$this->salida.="<tr>";
											$this->salida.="  <td  align=\"center\" class=\"$estilo\" width=\"40%\" colspan\"".$tipo[$l]."\" >$l</td>";
											$this->salida.="  <td colspan=\"2\">";
											$i=0;
											foreach($u as $usu => $x)
											{
												if( $i % 2){ $estilo2='modulo_list_claro';}
												else {$estilo2='modulo_list_oscuro';}
												$this->salida.=" <table width=\"100%\" class=\"normal_10\" cellpanding=\"1\" cellpascing=\"1\">";
												$this->salida.="  <tr class=\"$estilo2\">";
												$this->salida.="  <td width=\"30%\">$usu</td>";
												foreach($x as $n => $z)
												{  $this->salida.="  <td width=\"50%\" align=\"center\">$n</td>"; }
												$accion=ModuloGetURL('system','User_modulo_dpto','admin','BorrarUsuarios',array('campo_vect'=>$campo_vect,'tabla'=>$tabla,'campo'=>$id,'usuario'=>$datos[$d][puntero],'id'=>$datos[$d][$id]));
												//$this->salida.="  <td width=\"10%\">".$datos[$i][puntero]."</td>";
												$this->salida.="  <td width=\"10%\"><a href=\"$accion\">BORRAR</a></td>";
												$this->salida.="</tr></table>";
												$i++;
												$d++;
											}
											$this->salida.="  </td>";
											$this->salida.=" </td>";
											$this->salida.="</tr>";
									}
					}
					else
					{
						$this->salida.=" <table align='center' width=\"25%\" class=\"normal_10\" cellpanding=\"1\" cellpascing=\"1\">";
						$this->salida.="  <tr>";
						$this->salida.="  <td><label class='label_mark'>NO TIENE USUARIOS ESTE MODULO</label></td>";
						$this->salida.="  </tr>";



					}
					$this->salida .= "			 </table>";
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					$action2=ModuloGetURL('system','User_modulo_dpto','admin','TraerDatos',array("tabla"=>$tabla));
					$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";
					$this->salida .= ThemeCerrarTabla();
					return true;
	}



/*Funcion que realiza las matrices de la tabla que se esta trabajando segun
 * el administrador del modulo por ejemplo facturacion,caja,agenda,contratación.
..*/
function TraerDatos($tabla) //$tabla trae el nombre de la tabla a buscar.
{
		//borramos la variable de session, solo nos importa en el caso q la
		//tabla sea atencion ordenes de servicio.
		unset($_SESSION['USER_ADMIN_MOD']['SW_OS']);
		//borramos la variable de session, solo nos importa en el caso q la
		//tabla sea soat->.	userpermisos_soat
		unset($_SESSION['USER_ADMIN_MOD']['SW_SOAT']);
		//borramos la variable de session, solo nos importa en el caso q la
		//tabla sea soat->.	userpermisos_central
		unset($_SESSION['USER_ADMIN_MOD']['SW_CENTRAL']);

   if(empty($tabla))
		{
			$tabla=$_REQUEST['tabla'];
		}
		if(empty($_SESSION['USER_ADMIN_MOD']['TIPO_MENU']))
		{
			$_SESSION['USER_ADMIN_MOD']['TIPO_MENU']=$_REQUEST['permiso'];
		}
   	list($dbconn) = GetDBconn();
		unset($_SESSION['ADMINISTRADOR']['VAR']);
		$numerico=0;
		$i=1;
		while(true)
		{
	  $query = "SELECT tabla,campos[$i] as dato1,campos[$i] as dato2,
							relacion[$i] as relacion1,relacion[$i] as relacion2,descripcion[$i] as descripcion,
							caso[$i] as caso
							from userpermisos_admin where tabla='".$tabla."' ";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al buscar en userpermisos_admin";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}
		if(is_null($result->fields[1]))
		{
		 break;
		}
		$i++;
		$e=0;
		while (!$result->EOF)
					{
						$var[$e]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
						$e++;
					}

		switch($var[0][caso])
		{

			case 'caso1':
									if($tabla=="userpermisos_soat")
									{
										$emp_id=$_SESSION['USER_ADMIN_MOD']['EMPRESA'];
										$_SESSION['USER_ADMIN_MOD']['SW_SOAT']="WHERE empresa_id='$emp_id'";
									}
									if($tabla=="userpermisos_central")
									{
										$emp_id=$_SESSION['USER_ADMIN_MOD']['DPTO'];
										$_SESSION['USER_ADMIN_MOD']['SW_CENTRAL']="WHERE departamento='$emp_id'";
										$_SESSION['USER_ADMIN_MOD']['SW_OS']="AND d.departamento='$emp_id'";
									}
									 $query="select ".$var[0]['dato1']." as puntero,".$var[0]['descripcion']." as desc from ".$var[0]['relacion1']."
									".$_SESSION['USER_ADMIN_MOD']['SW_SOAT']."".	$_SESSION['USER_ADMIN_MOD']['SW_CENTRAL']." order by ".$var[0]['dato1']." asc";
									$result = $dbconn->Execute($query);
									$d=0;
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al buscar en ".$var[0]['relacion1']."";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
													while (!$result->EOF)
									{
										$datos[$d]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
										$d++;
									}
         					$result->Close();
									if(!empty($datos))
									{
											$salida1="<table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"90%\">";
											$salida1.="<tr class=\"hc_table_submodulo_list_title\">";
											$salida1.="  <td></td>";
											$salida1.="  <td>".$var[0]['dato1']." </td>";
											$salida1.="  <td>".$var[0]['descripcion']."</td>";
											for($k=0;$k<sizeof($datos);$k++)
											{

												$id=$datos[$k][puntero];
												$desc=$datos[$k][desc];
												if( $k % 2){ $estilo='modulo_list_claro';}
												else {$estilo='modulo_list_oscuro';}
												$salida1.="<tr class=\"$estilo\" align=\"center\">";
												$salida1.="  <td align=\"left\"><input type='checkbox' name='sel1[$k]' value='$id'></td>";
												$salida1.="  <td align=\"left\">$id</td>";
												$salida1.="  <td align=\"left\">$desc</td>";
											}
												$salida1.="</tr>";
												$salida1.="</table>";
										}
										else
										{
												$salida1="<table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"60%\">";
												$salida1.="<tr align=\"center\"><td><label class='label_mark'>TABLA VACIA<br>'$tabla'</label></td></tr>";
												$salida1.="</table>";
										}
										$vector_campos[$numerico]=$var[0]['dato1'];
										//$vector_descripcion[$numerico]=$var[0]['descripcion'];
										$vect[$i]=$salida1;
										$numerico ++;
										unset($datos);//sino la reseteamos se llenan las tablas con basura
	                  break;

			case 'caso2': //caso unico de usuarios, filtrado por departamentos o empresas
										$variable=explode("-",$var[0][descripcion]);

									if(!empty($_SESSION['USER_ADMIN_MOD']['EMPRESA']))
									{
										  $query="select a.".$var[0]['dato1']." as puntero,a.".$variable[0]." as desc,a.".$variable[1]." as desc1 from ".$var[0]['relacion1']." a
														,system_usuarios_empresas as e
														WHERE a.sw_admin =0 AND   a.".$var[0]['dato1']."  <> 0 AND  a.".$var[0]['dato1']." = e.".$var[0]['dato1']."
														and e.empresa_id='".$_SESSION['USER_ADMIN_MOD']['EMPRESA']."'  order by a.".$var[0]['dato1']."";
									}
									else
									{
								 		$query="select a.".$var[0]['dato1']." as puntero,a.".$variable[0]." as desc,a.".$variable[1]." as desc1 from ".$var[0]['relacion1']." a
														,system_usuarios_departamentos as e
														WHERE a.sw_admin =0 AND   a.".$var[0]['dato1']."  <> 0 AND  a.".$var[0]['dato1']." = e.".$var[0]['dato1']."
														and e.departamento='".$_SESSION['USER_ADMIN_MOD']['DPTO']."'  order by a.".$var[0]['dato1']."";
									}
									$n=0;
									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al buscar en ".$var[0]['relacion1']."";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
													while (!$result->EOF)
									{
										$datos[$n]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
										$n++;
									}
									$result->Close();
									$salida1="<br><table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"90%\">";
									$salida1.="<tr class=\"hc_table_submodulo_list_title\">";
									$salida1.="  <td>Uid</td>";
									$salida1.="  <td>usuario</td>";
									$salida1.="  <td>Nombre</td>";
									$salida1.="  <td></td>";
									for($s=0;$s<sizeof($datos);$s++)
									{
										$id=$datos[$s][puntero];
										$user=$datos[$s][desc];
										$nom=$datos[$s][desc1];
										if( $s % 2){ $estilo='modulo_list_claro';}
										else {$estilo='modulo_list_oscuro';}
										$salida1.="<tr class=\"$estilo\" align=\"center\">";
										$salida1.="  <td  align=\"left\">$id</td>";
										$salida1.="  <td align=\"left\">$user</td>";
										$salida1.="  <td align=\"left\">$nom</td>";
										$salida1.="  <td align=\"left\"><input type='checkbox' name='sel2[$s]' value='$id'></td>";
									}
										$salida1.="</tr>";
										$salida1.="</table>";
										$vector_campos[$numerico]=$var[0]['dato1'];
										//$vector_descripcion[$numerico]=$variable[0][descripcion];
          					$vect[$i]=$salida1;
										$numerico ++;
										unset($datos);//sino la reseteamos se llenan las tablas con basura

									break;

			case 'caso3': //caso de centro de utilidad, y otras opciones.

									if($tabla=="userpermisos_soat")
									{
										$emp_id=$_SESSION['USER_ADMIN_MOD']['EMPRESA'];
										$_SESSION['USER_ADMIN_MOD']['SW_SOAT']="WHERE empresa_id='$emp_id'";
									}

								$query="select ".$var[0]['dato1']." as puntero,".$var[0]['descripcion']." as desc from ".$var[0]['relacion1']."
							".$_SESSION['USER_ADMIN_MOD']['SW_SOAT']." order by ".$var[0]['dato1']." asc";

									$n=0;
									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al buscar en ".$var[0]['relacion1']."";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
													while (!$result->EOF)
									{
										$datos1[$n]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
										$n++;
									}
										$result->Close();
										if(!empty($datos1))
										{
													$salida1="<br><table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"90%\">";
													$salida1.="<tr class=\"hc_table_submodulo_list_title\">";
													$salida1.="  <td>Centro_utilidad</td>";
													$salida1.="  <td>Descripcion</td>";
													$salida1.="  <td></td>";
													for($s=0;$s<sizeof($datos1);$s++)
													{
														$cu=$datos1[$s][puntero];
														$desc=$datos1[$s][desc];
														if( $s % 2){ $estilo='modulo_list_claro';}
														else {$estilo='modulo_list_oscuro';}
														$salida1.="<tr class=\"$estilo\" align=\"center\">";
														$salida1.="  <td  align=\"left\">$cu</td>";
														$salida1.="  <td align=\"left\">$desc</td>";
														$salida1.="  <td align=\"left\"><input type='checkbox' name='sel3[$s]' value='$cu'></td>";
													}
														$salida1.="</tr>";
														$salida1.="</table>";
										}
										else
										{
												$salida1="<table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"60%\">";
												$salida1.="<tr align=\"center\"><td><label class='label_mark'>TABLA VACIA<br>'$tabla'</label></td></tr>";
												$salida1.="</table>";
										}
										$vector_campos[$numerico]=$var[0]['dato1'];
										//$vector_descripcion[$numerico]=$var[0]['descripcion'];
          					$vect[$i]=$salida1;
										$numerico ++;
										unset($datos1);//sino la reseteamos se llenan las tablas con basura

										break;

			case 'caso4': //caso de las tablas de jaime.
													$query="select a.detalle,b.tipo_cargo_amb_id,b.".$var[0]['descripcion']." as desc,b.tipo_servicio_amb_id as puntero
													from tipos_cargos_ambulatorios a,
													tipos_servicios_ambulatorios b,tipos_consulta c
													where a.tipo_cargo_amb_id=b.tipo_cargo_amb_id
													AND  b.tipo_cargo_amb_id  ='01'
													AND c.".$var[0]['dato1']."=b.tipo_servicio_amb_id order by b.tipo_servicio_amb_id";
									$n=0;
									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al buscar en ".$var[0]['relacion1']."";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
													while (!$result->EOF)
									{
										$datos1[$n]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
										$n++;
									}
									$result->Close();
									if(!empty($datos1))
									{
													$salida1="<br><table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"90%\">";
													$salida1.="<tr class=\"hc_table_submodulo_list_title\">";
													$salida1.="  <td>Serv.Ambulatorios</td>";
													$salida1.="  <td>Cargos</td>";
													$salida1.="  <td>Descripcion</td>";
													$salida1.="  <td></td>";
													for($s=0;$s<sizeof($datos1);$s++)
													{
														$cu=$datos1[$s][puntero];
														$cargos=$datos1[$s][detalle];
														$desc=$datos1[$s][desc];
														if( $s % 2){ $estilo='modulo_list_claro';}
														else {$estilo='modulo_list_oscuro';}
														$salida1.="<tr class=\"$estilo\" align=\"center\">";
														$salida1.="  <td  align=\"center\">$cu</td>";
														$salida1.="  <td  align=\"left\">$cargos</td>";
														$salida1.="  <td align=\"left\">$desc</td>";
														$salida1.="  <td align=\"left\"><input type='checkbox' name='sel1[$s]' value='$cu'></td>";
													}
														$salida1.="</tr>";
														$salida1.="</table>";
										}
										else
										{
												$salida1="<table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"60%\">";
												$salida1.="<tr align=\"center\"><td><label class='label_mark'>TABLA VACIA<br>'$tabla'</label></td></tr>";
												$salida1.="</table>";
										}
										$vector_campos[$numerico]=$var[0]['dato1'];
										//$vector_descripcion[$numerico]=$var[0]['descripcion'];
          					$vect[$i]=$salida1;
										$numerico ++;
										$_SESSION['ADMINISTRADOR']['VAR']='tipo_servicio_amb_id'; //esta variable hace cambiar la direccion destino
										//para mostrar los usuarios que estan en el la tabla de este caso
										//solo cambia un poco la estandarizacion ya que el sql del modulo
										//MostrarElementos cambiaria un poco para estos casos....
										unset($datos1);//sino la reseteamos se llenan las tablas con basura
									break;


									//Este caso es especial para cuando filtramos a la tabla Empresas
									//pero segun los permisos del usuario
			case 'caso5':
									$query="select b.".$var[0]['dato1']." as puntero,b.".$var[0]['descripcion']." as desc from ".$var[0]['relacion1']." AS b
 													,system_usuarios_empresas a WHERE
													 a.".$var[0]['dato1']."='".$_SESSION['USER_ADMIN_MOD']['EMPRESA']."'
									 				 AND a.usuario_id=".UserGetUID()." AND b.".$var[0]['dato1']."= a.".$var[0]['dato1']."
													 order by ".$var[0]['descripcion']."";
                  //b.sw_usuarios_multiempresa,  PREGUNTAR ESTO POR FAVOR
									$result = $dbconn->Execute($query);
									$d=0;
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al buscar en ".$var[0]['relacion1']."";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
													while (!$result->EOF)
									{
										$datos[$d]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
										$d++;
									}
									$result->Close();
									if(!empty($datos))
									{
												$salida1="<table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"90%\">";
												$salida1.="<tr class=\"hc_table_submodulo_list_title\">";
												$salida1.="  <td></td>";
												$salida1.="  <td>".$var[0]['dato1']." </td>";
												$salida1.="  <td>".$var[0]['descripcion']."</td>";
												for($k=0;$k<sizeof($datos);$k++)
												{

													$id=$datos[$k][puntero];
													$desc=$datos[$k][desc];
													if( $k % 2){ $estilo='modulo_list_claro';}
													else {$estilo='modulo_list_oscuro';}
													$salida1.="<tr class=\"$estilo\" align=\"center\">";
													$salida1.="  <td align=\"left\"><input type='checkbox' name='sel1[$k]' value='$id'></td>";
													$salida1.="  <td align=\"left\">$id</td>";
													$salida1.="  <td align=\"left\">$desc</td>";
												}
													$salida1.="</tr>";
													$salida1.="</table>";
										}
										else
										{
												$salida1="<table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"60%\">";
												$salida1.="<tr align=\"center\"><td><label class='label_mark'>TABLA VACIA<br>'$tabla'</label></td></tr>";
												$salida1.="</table>";
										}
										$vector_campos[$numerico]=$var[0]['dato1'];
										//$vector_descripcion[$numerico]=$var[0]['descripcion'];
										$vect[$i]=$salida1;
										$numerico ++;
										unset($datos);//sino la reseteamos se llenan las tablas con basura

	                  break;

			case 'caso6': //caso de centro de utilidad, y opciones como userpermisos_censo
										//el cual tiene un objeto listmenu con los centros de utilidadn si o no.
									$query="select ".$var[0]['dato1']." as puntero,".$var[0]['descripcion']." as desc from ".$var[0]['relacion1']." order by ".$var[0]['dato1']." asc";
									$n=0;
									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al buscar en ".$var[0]['relacion1']."";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
													while (!$result->EOF)
									{
										$datos1[$n]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
										$n++;
									}
									$result->Close();
									if(!empty($datos1))
									{
											$salida1="<br><table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"90%\">";
											$salida1.="<tr class=\"hc_table_submodulo_list_title\">";
											$salida1.="  <td>Centro_utilidad</td>";
											$salida1.="  <td>Descripcion</td>";
											$salida1.="  <td></td>";
											for($s=0;$s<sizeof($datos1);$s++)
											{
												$cu=$datos1[$s][puntero];
												$desc=$datos1[$s][desc];
												if( $s % 2){ $estilo='modulo_list_claro';}
												else {$estilo='modulo_list_oscuro';}
												$salida1.="<tr class=\"$estilo\" align=\"center\">";
												$salida1.="  <td  align=\"left\">$cu</td>";
												$salida1.="  <td align=\"left\">$desc</td>";
												$salida1.="  <td align=\"left\"><input type='checkbox' name='sel3[$s]' value='$cu'></td>";
											}
												$salida1.="</tr>";
												$salida1.="</table>";
										}
										else
										{
												$salida1="<table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"60%\">";
												$salida1.="<tr align=\"center\"><td><label class='label_mark'>TABLA VACIA<br>'$tabla'</label></td></tr>";
												$salida1.="</table>";
										}
										$vector_campos[$numerico]=$var[0]['dato1'];
										//$vector_descripcion[$numerico]=$var[0]['descripcion'];
          					$vect[$i]=$salida1;
										$numerico ++;
										unset($datos1);//sino la reseteamos se llenan las tablas con basura
										break;

			case 'caso7':
									//caso de estacion_de_enfermeria,estacion_enfermeria admin
									// caso de atencion ordenes de servicio


									//ESTE CASO ES SOLO SI LA TABLA ES "userpermisos_os_atencion"
									if($tabla=="userpermisos_os_atencion")
									{
										$depto=$_SESSION['USER_ADMIN_MOD']['DPTO'];
										$_SESSION['USER_ADMIN_MOD']['SW_OS']="AND d.departamento='$depto'";
									}

									$query="select ".$var[0]['dato1']." as puntero,".$var[0]['descripcion']." as desc from ".$var[0]['relacion1']."
									WHERE departamento='".$_SESSION['USER_ADMIN_MOD']['DPTO']."'   order by ".$var[0]['dato1']." asc";
									$result = $dbconn->Execute($query);
									$d=0;
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al buscar en ".$var[0]['relacion1']."";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
													while (!$result->EOF)
									{
										$datos[$d]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
										$d++;
									}
									$result->Close();
									if(!empty($datos))
									{
											$salida1="<table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"90%\">";
											$salida1.="<tr class=\"hc_table_submodulo_list_title\">";
											$salida1.="  <td></td>";
											$salida1.="  <td>".$var[0]['dato1']." </td>";
											$salida1.="  <td>".$var[0]['descripcion']."</td>";
											for($k=0;$k<sizeof($datos);$k++)
											{

												$id=$datos[$k][puntero];
												$desc=$datos[$k][desc];
												if( $k % 2){ $estilo='modulo_list_claro';}
												else {$estilo='modulo_list_oscuro';}
												$salida1.="<tr class=\"$estilo\" align=\"center\">";
												$salida1.="  <td align=\"left\"><input type='checkbox' name='sel1[$k]' value='$id'></td>";
												$salida1.="  <td align=\"left\">$id</td>";
												$salida1.="  <td align=\"left\">$desc</td>";
											}
												$salida1.="</tr>";
												$salida1.="</table>";
										}
										else
										{
												$salida1="<table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"60%\">";
												$salida1.="<tr align=\"center\"><td><label class='label_mark'>TABLA VACIA<br>'$tabla'<br>No hay estaciones<br>con el dpto&nbsp;".$_SESSION['USER_ADMIN_MOD']['DPTO']."</label></td></tr>";
												$salida1.="</table>";
										}
										$vector_campos[$numerico]=$var[0]['dato1'];
										//$vector_descripcion[$numerico]=$var[0]['descripcion'];
										$vect[$i]=$salida1;
										$numerico ++;
										unset($datos);//sino la reseteamos se llenan las tablas con basura
	               	break;
							}

		}

		$this->salida.= ThemeAbrirTabla('ADICION DE USUARIOS A MODULOS SIIS');

		//con el sizeof nos damos cuenta a cuantos casos entro y asi mismo
		//sabremos cuantas tablas debemos mostrar.
	if(sizeof($vect)==2)
	{

		$accion=ModuloGetURL('system','User_modulo_dpto','admin','InsertarP',array("tabla"=>$tabla,"vectorcampos"=>$vector_campos));
		$this->salida.="<form name='general' action='$accion' method='post'>";

    if($_SESSION['USER_ADMIN_MOD']['DPTO'])
	 {
			$this->salida.="<br><table border=\"0\"  class=\"modulo_table_list\"  align=\"center\"   width=\"80%\" >";
			$this->salida.="<tr>";
	    $this->salida .= "<td align=\"center\" class=\"modulo_table_title\" >ADMINISTRACION </td><td  align=\"center\" class=\"modulo_table_title\" >DEPARTAMENTO</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida .= "<td class=\"modulo_list_oscuro\"  align=\"center\">".$_SESSION['USER_ADMIN_MOD']['TIPO_MENU']."</td><td class=\"modulo_list_oscuro\"  align=\"center\">COD&nbsp;: ".$_SESSION['USER_ADMIN_MOD']['DPTO']."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['USER_ADMIN_MOD']['NOMBRE']."";
			$this->salida.="</td>";
			$this->salida.="</table>";
	 }

	 if($_SESSION['USER_ADMIN_MOD']['EMPRESA'])
		{
			$this->salida.="<br><table border=\"0\"  align=\"center\"   width=\"80%\" >";
			$this->salida.="<tr>";
	    $this->salida .= "<td align=\"center\" class=\"modulo_table_title\" >ADMINISTRACION </td><td  align=\"center\" class=\"modulo_table_title\" >EMPRESA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida .= "<td class=\"modulo_list_oscuro\"  align=\"center\">".$_SESSION['USER_ADMIN_MOD']['TIPO_MENU']."</td><td align='center' class=\"modulo_list_oscuro\" >EMPRESA:&nbsp;&nbsp;".$this->TraerNombre($_SESSION['USER_ADMIN_MOD']['EMPRESA'])."";
			$this->salida.="</td>";
			$this->salida.="</table>";
		}

		$this->salida.="<br><table align='center' width='100%'>";
    $this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="<tr>";
		$this->salida.="<td align='center'>$vect[2]</td>";
		$this->salida.="<td align='center'>$vect[3]</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<br><td colspan='2' align='center'><a href=".ModuloGetURL('system','User_modulo_dpto','admin','MostrarElementos',array("tabla"=>$tabla,"vectorcampos"=>$vector_campos)).">Revisar usuarios inscriptos</a></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";


	}
	elseif(sizeof($vect)==3)
	{
		$accion=ModuloGetURL('system','User_modulo_dpto','admin','InsertarPnivel3',array("tabla"=>$tabla,"vectorcampos"=>$vector_campos));
		$this->salida.="<form name='general' action='$accion' method='post'>";
		$this->salida.="<br><table align='center' width='100%'>";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="<tr>";
		$this->salida.="<td align='center'>$vect[2]</td>";
		$this->salida.="<td align='center'>$vect[4]</td>";
		$this->salida.="<td align='center'>$vect[3]</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="<td colspan='3' align='center'><a href=".ModuloGetURL('system','User_modulo_dpto','admin','MostrarElementos',array("tabla"=>$tabla,"vectorcampos"=>$vector_campos)).">Revisar usuarios inscriptos</a></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";

	}

	$this->salida.="<br><table width='25%' align='center'>";
	$this->salida.="<tr>";
	$this->salida.="<td align='center'><input type='submit' class='input-submit' name='mandar' value='Guardar'></form></td>";

	$acc=ModuloGetURL('system','User_modulo_dpto','admin','Retornar_A_Modulo',array("tabla"=>$tabla,"vectorcampos"=>$vector_campos));
	$this->salida.="<form name='modulovolver' action='$acc' method='post'>";
	$this->salida.="<td align='center'><input type='submit'class='input-submit'  name='Volver' value='Volver'></td>";
	$this->salida.="</tr>";
	$this->salida.="</table>";
	$this->salida.= ThemeCerrarTabla();
return true;
}



}//fin clase user
?>

