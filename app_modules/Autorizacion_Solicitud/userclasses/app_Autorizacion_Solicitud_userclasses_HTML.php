<?php
/**
* app_Autorizacion_Solicitud_HTML.php  17/01/2003
*
* Proposito del Archivo: Manejo logico de las autorizaciones.
* Copyright (C) 2003 InterSoftware Ltda.
* Email: intersof@telesat.com.co
* @autor: Darling Liliana Dorado M
* @version SIIS v 0.1
* @package SIIS
*/


/**
*Contiene los metodos visuales para realizar las autorizaciones.
*/

class app_Autorizacion_Solicitud_userclasses_HTML extends app_Autorizacion_Solicitud_user
{
	/**
	*Constructor de la clase app_Autorizacion_user_HTML
	*El constructor de la clase app_Autorizacion_user_HTML se encarga de llamar
	*a la clase app_Autorizacion_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function app_Autorizacion_Solicitud_user_HTML()
	{
				$this->salida='';
				$this->app_Autorizacion_Solicitud_user();
				return true;
	}


  function SetStyle($campo)
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("label");
	}


	/**
	*
	*/
	function FormaBuscar($TipoId,$PacienteId,$var,$Servicio)
	{
       	$this->salida .= ThemeAbrirTabla('AUTORIZACIONES - BUSCAR PACIENTE');
				$this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\">";
				$this->salida .= "          <tr><td><fieldset><legend class=\"field\">BUSCAR PACIENTE</legend>";
				$this->salida .= "			      <table width=\"80%\" align=\"center\" border=\"0\">";
				$action=ModuloGetURL('app','Autorizacion_Solicitud','user','BuscarIngresoPaciente');
				$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Servicio")."\">SERVICIOS: </td><td><select name=\"Servicio\" class=\"select\">";
				$ser=$this->BuscarServicios();
				$this->salida .=" <option value=\"-1\">------SELECCIONE------</option>";
				for($i=0; $i<sizeof($ser); $i++)
				{
						if($ser[$i][servicio]==$Servicio)
						{  $this->salida .=" <option value=\"".$ser[$i][servicio]."\" selected>".$ser[$i][descripcion]."</option>";  }
						else
						{  $this->salida .=" <option value=\"".$ser[$i][servicio]."\">".$ser[$i][descripcion]."</option>";  }
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "				       <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoId\" class=\"select\">";
        $tipo_id=$this->CallMetodoExterno('app','Triage','user','tipo_id_paciente','');
				foreach($tipo_id as $value=>$titulo)
				{
						if($value==$TipoId)
						{  $this->salida .=" <option value=\"$value\" selected>$titulo</option>";  }
						else
						{  $this->salida .=" <option value=\"$value\">$titulo</option>";  }
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("PacienteId")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"PacienteId\" maxlength=\"32\" value=\"$PacienteId\"></td></tr>";
				$this->salida .= "				       <tr><td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"><br></td></form>";
				$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarMenu');
				$this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></tr></form>";
				$this->salida .= "			     </table>";
				$this->salida .= "		  </fieldset></td></tr></table>";
				if(is_array($var))
				{
							$this->salida .= "		   <br>";
							$this->salida .= "		<table width=\"70%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
							$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
							$this->salida .= "				<td>IDENTIFICACION</td>";
							$this->salida .= "				<td>PACIENTE</td>";
							$this->salida .= "				<td>No. INGRESO</td>";
							$this->salida .= "				<td>No. CUENTA</td>";
							$this->salida .= "				<td></td>";
							$this->salida .= "			</tr>";
							for($i=0; $i<sizeof($var); $i++)
							{
											$Cuenta=$var[$i][numerodecuenta];
											$Ingreso=$var[$i][ingreso];
											$Nombre=$var[$i][nombre];
											$PlanId=$var[$i][plan_id];
											$Nivel=$var[$i][rango];
											$Afiliado=$var[$i][tipo_afiliado_id];
											if( $i % 2) $estilo='modulo_list_claro';
											else $estilo='modulo_list_oscuro';
											$this->salida .= "			<tr class=\"$estilo\">";
											$this->salida .= "				<td align=\"center\">$TipoId $PacienteId</td>";
											$this->salida .= "				<td align=\"center\">$Nombre</td>";
											$this->salida .= "				<td align=\"center\">$Ingreso</td>";
											$this->salida .= "				<td align=\"center\">$Cuenta</td>";
											$accionHRef=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarFormaSolicitudAutorizacion',array('TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Ingreso'=>$Ingreso,'Afiliado'=>$Afiliado,'Servicio'=>$Servicio,'cuenta'=>$var[$i][numerodecuenta],'cama'=>$var[$i][cama],'pieza'=>$var[$i][pieza]));
											$this->salida .= "				<td align=\"center\"><a href=\"$accionHRef\">Solicitar</a></td>";
											$this->salida .= "			</tr>";
							}
							$this->salida .= "			     </table><br>";
				}
				elseif(!empty($var) && !is_array($var))
				{
						$action=ModuloGetURL('app','Autorizacion_Solicitud','user','PedirDatosPaciente',array('TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Servicio'=>$Servicio));
						$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
						$this->salida .= "			      <br><table width=\"50%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"0\" cellpadding=\"0\">";
						$this->salida .= "				       <tr class=\"modulo_list_claro\">";
						$this->salida .= "				          <td align=\"center\" colspan=\"2\" class=\"label_error\"><br>EL Paciente no se encuentra registrado, Desea crearlo.</td>";
						$this->salida .= "				       </tr>";
						$this->salida .= "				       <tr class=\"modulo_list_claro\" align=\"center\"><td class=\"".$this->SetStyle("Responsable")."\" align=\"right\">PLAN: </td><td><select name=\"Responsable\" class=\"select\">";
						$responsables=$this->CallMetodoExterno('app','Triage','user','responsables');
						$this->MostrarResponsable($responsables,$Responsable);
						$this->salida .= "              </select></td></tr>";
						$this->salida .= "				       <tr>";
						$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\" colspan=\"2\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"><br></form></td>";
						$this->salida .= "				       </tr>";
						$this->salida .= "			     </table><BR>";
				}
        $this->salida .= ThemeCerrarTabla();
				return true;
	}


	/**
	* Muestra el nombre del tercero con sus respectivos planes
	* @access private
	* @return string
	* @param array arreglor con los tipos de responsable
	* @param int el responsable que viene por defecto
	*/
 function MostrarResponsable($responsables,$Responsable)
 {
      $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
			for($i=0; $i<sizeof($responsables); $i++)
			{
					if($responsables[$i][plan_id]==$Responsable){
							$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\" selected>".$responsables[$i][plan_descripcion]."</option>";
					}else{
							$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\">".$responsables[$i][plan_descripcion]."</option>";
					}
			}
 }


 /**
 *
 */
 function Encabezado()
 {
				$Ingreso=$_SESSION['SOLICITUDAUTORIZACION']['ingreso'];
				$datos=$this->BuscarNombresApellidosPaciente($Ingreso);
				if(empty($datos))
				{   $datos=$this->BuscarNombresApellidosPacienteSinIng();  }
				/*$_SESSION['SOLICITUDAUTORIZACION']['pieza']='501';
				$_SESSION['SOLICITUDAUTORIZACION']['cama']='501-001';
				$_SESSION['SOLICITUDAUTORIZACION']['estacion_id']=4;*/
				$Est=$this->DescripcionEstacion($_SESSION['SOLICITUDAUTORIZACION']['estacion_id']);
				$this->salida .= "		<table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"15%\" nowrap>IDENTIFICACION: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\">".$datos[tipo_id_paciente]." ".$datos[paciente_id]."</td>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"12%\" nowrap>PACIENTE: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"4\">".$datos[nombre]."</td>";
				$this->salida .= "			</tr>";
				if(!empty($Ingreso))
				{
						$this->salida .= "			<tr>";
						$this->salida .= "				<td class=\"modulo_table_list_title\">INGRESO: </td>";
						$this->salida .= "				<td class=\"modulo_list_claro\">$Ingreso</td>";
						$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"8%\">PIEZA: </td>";
						$this->salida .= "				<td class=\"modulo_list_claro\">".$_SESSION['SOLICITUDAUTORIZACION']['pieza']."</td>";
						$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"8%\">CAMA: </td>";
						$this->salida .= "				<td class=\"modulo_list_claro\" width=\"8%\">".$_SESSION['SOLICITUDAUTORIZACION']['cama']."</td>";
						$this->salida .= "				<td class=\"modulo_table_list_title\"  width=\"20%\">ESTACION ENFERMERIA: </td>";
						$this->salida .= "				<td class=\"modulo_list_claro\"  width=\"15%\">$Est</td>";
						$this->salida .= "			</tr>";
						$this->salida .= "			<tr>";
						$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"8%\">PLAN: </td>";
						$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"7\">".$this->NombrePlan($_SESSION['SOLICITUDAUTORIZACION']['plan_id'])."</td>";
						$this->salida .= "			</tr>";
						$this->salida .= "			<tr>";
						$this->salida .= "				<td class=\"modulo_table_list_title\">TIPO AFILIADO: </td>";
						$Afi=$this->NombreAfiliado($datos[tipo_afiliado_id]);
						$this->salida .= "				<td class=\"modulo_list_claro\">".$Afi[tipo_afiliado_nombre]."</td>";
						$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"8%\">RANGO: </td>";
						$this->salida .= "				<td class=\"modulo_list_claro\">".$datos[rango]."</td>";
						$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"8%\" colspan=\"2\">SEMANAS COTIZADAS: </td>";
						$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\">".$datos[semanas_cotizadas]."</td>";
						$this->salida .= "			</tr>";
				}
				$this->salida .= "		 </table>";
 }

 /**
 *
 */
 function SolicitudServicios($grupo,$tipo,$data,$nivel)
 {
      	$this->salida .= ThemeAbrirTabla('AUTORIZACIONES - PANTALLA AUTORIZACION PACIENTE ');
				//tabla de autorizaciones del plan
				$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "		 </table>";
				$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','InsertarServicio');
				$this->salida .= "      <form name=\"solicitud\" action=\"$accion\" method=\"post\">";
				$this->salida .= "     <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "						<tr class=\"modulo_table_list_title\">";
				$this->salida .= "							<td align=\"center\" width=\"25%\">GRUPO</td>";
				$this->salida .= "							<td align=\"center\" width=\"35%\">TIPO</td>";
				$ser=$this->CallMetodoExterno('app','Autorizacion_Solicitud','user','NivelesAtencion');
				for($i=0; $i<sizeof($ser); $i++)
				{ $this->salida .= "							<td width=\"8%\" align=\"center\">".$ser[$i][descripcion_corta]."</td>"; }
				$this->salida .= "            </tr>";
				$j=0;
				$d=0;
				foreach($nivel as $g => $t)
				{
							if($j % 2) {  $estilo="modulo_list_claro";  }
							else {  $estilo="modulo_list_oscuro";   }
							$this->salida .= "						<tr>";
							$this->salida .= "							<td colspan\"".$grupo[$g]."\" align=\"center\" class=\"$estilo\">$g</td>";
							$this->salida .= "							<td colspan=\"5\">";
							$f=0;
							foreach($t as $destipo => $desnivel)
							{
									if($f % 2) {  $estilo="modulo_list_claro";  }
									else {  $estilo="modulo_list_oscuro";   }
									$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" cellspacing=\"1\" cellpadding=\"1\">";
									$this->salida .= "						<tr class=\"$estilo\">";
									$this->salida .= "							<td  width=\"35%\" colspan\"".$tipo[$destipo]."\"  align=\"center\">$destipo</td>";
									$z=$j;
									$sw=0;
									for($i=0; $i<sizeof($ser); $i++)
									{
											if(empty($_SESSION['SOLICITUDAUTORIZACION']['TODO']))
											{
													if(!empty($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZAR']['CARGOS']['VECT'][$data[$d][grupo_tipo_cargo]][$data[$d][tipo_cargo]][$ser[$i][nivel]])
															AND $desnivel[$ser[$i][descripcion_corta]])
													{
															$this->salida .= "<td width=\"8%\" align=\"center\"><input type=\"checkbox\" value=\"".$data[$d][grupo_tipo_cargo].",".$data[$d][tipo_cargo].",".$ser[$i][nivel]."\" name=\"Nivel".$data[$d][grupo_tipo_cargo].$data[$d][tipo_cargo].$ser[$i][nivel]."\" checked></td>";
															$d++;
													}
													elseif(empty($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZAR']['CARGOS']['VECT'][$data[$d][grupo_tipo_cargo]][$data[$d][tipo_cargo]][$ser[$i][nivel]])
													       AND $desnivel[$ser[$i][descripcion_corta]])
													{
															$this->salida .= "<td width=\"8%\" align=\"center\"><input type=\"checkbox\" value=\"".$data[$d][grupo_tipo_cargo].",".$data[$d][tipo_cargo].",".$ser[$i][nivel]."\" name=\"Nivel".$data[$d][grupo_tipo_cargo].$data[$d][tipo_cargo].$ser[$i][nivel]."\" ></td>";
															$d++;
													}
													else
													{		$this->salida .= "<td width=\"8%\" align=\"center\"></td>";  }
											}
											else
											{
													if(!empty($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZAR']['CARGOS']['VECT'][$data[$d][grupo_tipo_cargo]][$data[$d][tipo_cargo]][$ser[$i][nivel]])
															AND $ser[$i][nivel]==$data[$d][nivel])
													{
															$this->salida .= "<td width=\"8%\" align=\"center\"><input type=\"checkbox\" value=\"".$data[$d][grupo_tipo_cargo].",".$data[$d][tipo_cargo].",".$ser[$i][nivel]."\" name=\"Nivel".$data[$d][grupo_tipo_cargo].$data[$d][tipo_cargo].$ser[$i][nivel]."\" checked></td>";
													}
													else
													{
															$this->salida .= "<td width=\"8%\" align=\"center\"><input type=\"checkbox\" value=\"".$data[$d][grupo_tipo_cargo].",".$data[$d][tipo_cargo].",".$ser[$i][nivel]."\" name=\"Nivel".$data[$d][grupo_tipo_cargo].$data[$d][tipo_cargo].$ser[$i][nivel]."\" ></td>";
													}
													$d++;
											}
									}
									$this->salida .= "            </tr>";
									$this->salida .= "			      </table>";
									$f++;
							}
							$this->salida .= "							</td>";
							$this->salida .= "            </tr>";

				}
				$this->salida .= "			      </table><br>";
				$this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\">";
				$this->salida .= "          <tr align=\"center\"><td><input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"GUARDAR\"></td>";
				$this->salida .= "      </form>";
				if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
				{  $accion=ModuloGetURL('app','Autorizacion_Solicitud','user','AutorizacionServicio');  }
				else
				{  $accion=ModuloGetURL('app','Autorizacion_Solicitud','user','FormaAutorizacionDirecta');  }
				$this->salida .= "      <form name=\"sol\" action=\"$accion\" method=\"post\">";
				$this->salida .= "          <td><input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"CANCELAR\"></td><tr>";
				$this->salida .= "      </form>";
				$this->salida .= "			 </table>";
       	$this->salida .= ThemeCerrarTabla();
				return true;
 }


 /**
 *
 */
 	function FormaCargos()
	{
      	$this->salida .= ThemeAbrirTabla('AUTORIZACIONES - PANTALLA SOLICITUD AUTORIZACION PACIENTE '.$datos[nombre]);
				$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','InsertarCargo');
				$this->salida .= "      <form name=\"solicitud\" action=\"$accion\" method=\"post\">";
				$this->Encabezado();
				$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "		 </table>";
				//tabla cargos autorizar
				$this->salida .= "			   <br><br> <table width=\"90%\" align=\"center\" border=\"0\" class=\"modulo_table_list\"  cellpadding=\"4\" cellpadding=\"4\">";
				$this->salida .= "             <tr class=\"modulo_table_list_title\" align=\"center\">";
				$this->salida .= "             		<td colspan=\"5\">SOLICITUD AUTORIZACION CARGOS</td>";
				$this->salida .= "             </tr>";
				$this->salida .= "             <tr class=\"modulo_table_list_title\" align=\"center\">";
				$this->salida .= "             		<td colspan=\"5\">CARGOS SOLICITADOS";
				$this->salida .= "								</td>";
				$this->salida .= "             </tr>";
				$this->salida .= "             </tr>";
				$this->salida .= "             <tr align=\"center\" class=\"modulo_list_oscuro\">";
				$this->salida .= "             		<td class=\"label\">CARGOS</td>";
				$this->salida .= "             		<td colspan=\"4\">";
				$this->salida .= "			      <table width=\"100%\" align=\"center\" border=\"0\"  cellpadding=\"3\">";
				foreach($_SESSION['SOLICITUDAUTORIZACION']['VECTOR'] as $k => $v)
				{
						if(!empty($v))
						{
								$this->salida .= "             <tr align=\"center\" class=\"modulo_table_list_title\">";
								$this->salida .= "             		<td width=\"10%\">CODIGO</td>";
								$this->salida .= "             		<td>CARGO</td>";
								$this->salida .= "             		<td width=\"5%\">CANT</td>";
								$this->salida .= "             		<td width=\"5%\"></td>";
								$this->salida .= "             </tr>";
								foreach($v as $cod => $cant)
								{
										foreach($cant as $cantidad => $cargo)
										{
														$this->salida .= "             <tr class=\"modulo_list_claro\">";
														$this->salida .= "             		<td align=\"center\">$cod</td>";
														$this->salida .= "             		<td>$cargo</td>";
														$this->salida .= "             		<td align=\"center\">$cantidad</td>";
														$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','EliminarCargo',array('TarifarioId'=>$k,'Codigo'=>$cod,'Cantidad'=>$cantidad,'Cargo'=>$cargo));
														$this->salida .= "             		<td><a href=\"$accion\">Eliminar</a></td>";
														$this->salida .= "             </tr>";
										}
								}
						}
				}
				$this->salida .= "			      </table>";
				$this->salida .= "             		</td>";
				$this->salida .= "             </tr>";
				global $_ROOT;
				$PlanId=$_SESSION['SOLICITUDAUTORIZACION']['plan_id'];
				$this->salida .= "\n<script language='javascript'>\n";
				$this->salida .= "var rem=\"\";\n";
				$this->salida .= "  function abrirVentana(){\n";
				$this->salida .= "    var nombre='';\n";
				$this->salida .= "      var url2='';\n";
				$this->salida .= "      var str='';\n";
				$this->salida .= "      var ALTO=screen.height;\n";
				$this->salida .= "      var ANCHO=screen.width;\n";
				$this->salida .= "      nombre=\"buscador_General\";\n";
				$this->salida .= "      str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
				$this->salida .= "      url2 ='".$_ROOT."classes/classbuscador/buscador.php?tipo=BuscarCargo&forma=solicitud&plan='+'$PlanId';\n";
				$this->salida .= "      rem = window.open(url2, nombre, str);\n";
			 	$this->salida .= "  }\n";
				$this->salida .= " </script>\n";
				$this->salida .= "		<input type=\"hidden\" name=\"TarifarioId\">";
				$this->salida .= "             <tr class=\"modulo_table_list_title\" align=\"center\">";
				$this->salida .= "             		<td>CODIGO</td>";
				$this->salida .= "             		<td>CARGO</td>";
				$this->salida .= "             		<td>CANT.</td>";
				$this->salida .= "             		<td></td>";
				$this->salida .= "             		<td></td>";
				$this->salida .= "             </tr>";
				$Cantidad=1;
				$this->salida .= "             <tr align=\"center\" class=\"modulo_list_claro\">";
				$this->salida .= "             		<td><input type=\"text\" class=\"input-text\" name=\"Codigo\" size=\"10\"></td>";
				$this->salida .= "             		<td><input type=\"text\" class=\"input-text\" name=\"Cargo\" size=\"86\" readonly></td>";
				$this->salida .= "             		<td><input type=\"text\" class=\"input-text\" name=\"Cantidad\" size=\"3\" value=\"$Cantidad\"></td>";
				$this->salida .= "             		<td><input type=\"button\" class=\"input-submit\" name=\"Buscar\" value=\"Buscar\" onclick=abrirVentana()></td>";
				$this->salida .= "             		<td width=\"5%\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Insertar\"></td>";
				$this->salida .= "             </tr>";
				$this->salida .= "			      </table><br>";
				$this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\">";
				$this->salida .= "          <tr align=\"center\"><td><input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"GUARDAR\"></td>";
				$this->salida .= "      </form>";
				if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
				{  $accion=ModuloGetURL('app','Autorizacion_Solicitud','user','AutorizacionServicio');  }
				else
				{  $accion=ModuloGetURL('app','Autorizacion_Solicitud','user','FormaAutorizacionDirecta');  }
				$this->salida .= "      <form name=\"sol\" action=\"$accion\" method=\"post\">";
				$this->salida .= "          <td><input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"CANCELAR\"></td><tr>";
				$this->salida .= "      </form>";
				$this->salida .= "			 </table>";
       	$this->salida .= ThemeCerrarTabla();
				return true;
	}


	/**
	*
	*/
	function FormaSolicitudAutorizacion($PlanId,$grupo,$tipo,$data,$nivelA,$Tipo)
	{
      	$this->salida .= ThemeAbrirTabla('AUTORIZACIONES - PANTALLA SOLICITUD AUTORIZACION PACIENTE '.$datos[nombre]);
				$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','InsertarSolicitud');
				$this->salida .= "      <form name=\"solicitud\" action=\"$accion\" method=\"post\">";
				$this->Encabezado();
				$this->salida .= "			      <table width=\"80%\" align=\"center\" border=\"0\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "			      </table>";
				//TIPO AFILIADO Y RANGO
				if(empty($_SESSION['SOLICITUDAUTORIZACION']['ingreso']) AND empty($_SESSION['SOLICITUDAUTORIZACION']['FACTURACION']))
				{
							$this->salida .= "    <br><table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
							$tipo_afiliado=$this->Tipo_Afiliado();
							$this->salida .= "		      <tr>";
							if(empty($TipoAfiliado))
							{ $TipoAfiliado=$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']['campo_tipo_afiliado']; }
							if(sizeof($tipo_afiliado)>1 && empty($_SESSION['SOLICITUDAUTORIZACION']['tipo_afiliado_id']))
							{
									$this->salida .= "				       <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
									$this->BuscarIdTipoAfiliado($tipo_afiliado,$TipoAfiliado);
									$this->salida .= "              </select></td>";
							}
							else
							{
									$this->salida .= "				    <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td>";
									$NomAfi=$this->NombreAfiliado($_SESSION['SOLICITUDAUTORIZACION']['tipo_afiliado_id']);
									$this->salida .= "	  	      <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$_SESSION['SOLICITUDAUTORIZACION']['tipo_afiliado_id']."\">".$NomAfi[tipo_afiliado_nombre]."</td>";
									$this->salida .= "	  	      <td></td>";
							}
							$niveles=$this->Niveles();
							if(empty($Nivel) && !empty($_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']['campo_nivel']))
							{ $Nivel=$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']['campo_nivel']; }
							elseif(empty($Nivel) && empty($_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']['campo_nivel']))
							{ $Nivel=$_REQUEST['Nivel']; }
							if(sizeof($niveles)>1 && empty($_SESSION['SOLICITUDAUTORIZACION']['rango']))
							{
								$this->salida .= "				       <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
								$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
								for($i=0; $i<sizeof($niveles); $i++)
								{
										if($niveles[$i][rango]==$Nivel){
											$this->salida .=" <option value=\"".$niveles[$i][rango]."\" selected>".$niveles[$i][rango]."</option>";
										}
										if($niveles[$i][rango]==$_SESSION['SOLICITUDAUTORIZACION']['RANGO'][$niveles[$i][rango]]){
											$this->salida .=" <option value=\"".$niveles[$i][rango]."\" selected>".$niveles[$i][rango]."</option>";
										}
										else{
												$this->salida .=" <option value=\"".$niveles[$i][rango]."\">".$niveles[$i][rango]."</option>";
										}
								}
							}
							else
							{
									$this->salida .= "				     <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
									$this->salida .= "	  	      <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$niveles[0][rango]."\">".$niveles[0][rango]."</td>";
									$this->salida .= "	  	      <td></td>";
							}
							$this->salida .= "	  	      <td class=\"".$this->SetStyle("Semanas")."\" width=\"23%\">SEMANAS COTIZADAS: </td>";
							if(!empty($_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']['campo_semanas_cotizadas']))
							{  $s=$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']['campo_semanas_cotizadas'];  }
							else
							{  $s=$_SESSION['SOLICITUDAUTORIZACION']['SEMANAS'][$_REQUEST['Semanas']];  }
							$this->salida .= "	  	      <td><input type=\"text\" name=\"Semanas\" size=\"8\" value=\"".$s."\"></td>";
							$this->salida .= "		      </tr>";
							$this->salida .= "			 </table><br>";
				}
				//CARGOS Y SERVICIOS SOLICITADOS
				$this->SolicitudAutorizacion();
				//tipo solicitud
				if(empty($_SESSION['SOLICITUDAUTORIZACION']['FACTURACION']))
				{
						$this->salida .= "     <br><table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
						$this->salida .= "      <tr align=\"center\" class=\"modulo_list_claro\">";
						$accionS=ModuloGetURL('app','Autorizacion_Solicitud','user','AdicionarServicio');
						$this->salida .= "	  	      <td><a href=\"$accionS\">ADICIONAR SERVICIOS</a></td>";
						$accionC=ModuloGetURL('app','Autorizacion_Solicitud','user','AdicionarCargo');
						$this->salida .= "	  	      <td><a href=\"$accionC\">ADICIONAR CARGOS</a></td>";
						$this->salida .= "		      </tr>";
						$this->salida .= "			 </table><br>";
				}
				$this->salida .= "      <table border=\"0\" width=\"45%\" align=\"center\">";
				$this->salida .= "       <tr><td><fieldset><legend class=\"field\">OBSERVACIONES</legend>";
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= "          <tr  align=\"center\"><td width=\"30%\"><textarea name=\"Observaciones\" cols=\"47\" rows=\"3\" class=\"textarea\">".$_REQUEST['Observaciones']."</textarea></td></tr>";
				$this->salida .= "			 </table>";
				$this->salida .= "		  </fieldset></td></tr></table><br>";
				$usu=$this->BuscarUsuariosAuto($PlanId);
				if($usu)
				{
						$this->salida .= "      <table border=\"0\" width=\"30%\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\">";
						$this->salida .= "          <tr align=\"center\" class=\"modulo_table_list_title\">";
						$this->salida .= "            <td>USUARIOS AUTORIZADORES</td>";
						$this->salida .= "            <td></td>";
						$this->salida .= "          </tr>";
						for($i=0; $i<sizeof($usu); $i++)
						{
								$this->salida .= "          <tr class=\"modulo_list_claro\">";
								$this->salida .= "            <td>".$usu[$i][nombre]."</td>";
								$this->salida .= "            <td align=\"center\"><input type=\"radio\" value=\"".$usu[$i][usuario_id]."\" name=\"Pto\"></td>";
								$this->salida .= "          </tr>";
						}
						$this->salida .= "			 </table><br>";
				}
				$this->salida .= "			      <p align=\"center\" class=\"label\">AUTORIZACION URGENTE<input type=\"checkbox\" value=\"1\" name=\"Urgente\"></p>";
				$this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\">";
				$this->salida .= "          <tr align=\"center\"><td><input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"GUARDAR\"></td>";
				$this->salida .= "      </form>";

				if(!empty($_SESSION['SOLICITUDAUTORIZACION']['RETORNO']))
				{
						$m=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['modulo'];
						$t=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['tipo'];
						$c=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['contenedor'];
						$me=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['metodo'];
						$argu=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['argumentos'];
						$accion=ModuloGetURL($c,$m,$t,$me,$argu);
				}
				else
				{  $accion=ModuloGetURL('app','Autorizacion_Solicitud','user','Principal');  }
				$this->salida .= "      <form name=\"sol\" action=\"$accion\" method=\"post\">";
				$this->salida .= "          <td><input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"CANCELAR\"></td><tr>";
				$this->salida .= "      </form>";
				$this->salida .= "			 </table>";
       	$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	*
	*/
	function FormaSolicitud($cargos,$data,$grupo,$tipo,$nivel)
	{
				if(!empty($cargos))
				{
						$this->salida .= "			      <table width=\"90%\" align=\"center\" border=\"0\"  cellpadding=\"3\" class=\"modulo_table_list\">";
						$this->salida .= "             <tr align=\"center\" class=\"modulo_table_list_title\">";
						$this->salida .= "             		<td colspan=\"4\">CARGOS SOLICITADOS</td>";
						$this->salida .= "             </tr>";
						$this->salida .= "             <tr align=\"center\" class=\"modulo_table_list_title\">";
						$this->salida .= "             		<td width=\"10%\">CODIGO</td>";
						$this->salida .= "             		<td>CARGO</td>";
						if(empty($_SESSION['SOLICITUDAUTORIZACION']['FACTURACION']))
						{
								$this->salida .= "             		<td width=\"5%\">CANT</td>";
								$this->salida .= "             		<td width=\"5%\"></td>";
						}
						else
						{  $this->salida .= "             		<td width=\"5%\" colspan=\"2\">CANT</td>";  }
						$this->salida .= "             </tr>";
						for($i=0; $i<sizeof($cargos); $i++)
						{
									$this->salida .= "             <tr class=\"modulo_list_claro\">";
									$this->salida .= "             		<td align=\"center\">".$cargos[$i][cargo]."</td>";
									$this->salida .= "             		<td>".$cargos[$i][descripcion]."</td>";
									if(empty($_SESSION['SOLICITUDAUTORIZACION']['FACTURACION']))
									{
											$this->salida .= "             		<td align=\"center\">".$cargos[$i][cantidad]."</td>";
											$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','EliminarCargoS',array('TarifarioId'=>$cargos[$i][tarifario_id],'Codigo'=>$cargos[$i][cargo],'Cantidad'=>$cargos[$i][cantidad],'Cargo'=>$cargos[$i][descripcion]));
											$this->salida .= "             		<td align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
									}
									else
									{  	$this->salida .= " <td align=\"center\" colspan=\"2\">".$cargos[$i][cantidad]."</td>";   }
									$this->salida .= "             </tr>";
						}
						$this->salida .= "			      </table>";
				}
				if(!empty($data))
				{
						$this->salida .= "     <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
						$this->salida .= "						<tr class=\"modulo_table_list_title\">";
						$this->salida .= "							<td align=\"center\" width=\"25%\">GRUPO</td>";
						$this->salida .= "							<td align=\"center\" width=\"35%\">TIPO</td>";
						$ser=$this->NivelesAtencion();
						for($i=0; $i<sizeof($ser); $i++)
						{ $this->salida .= "							<td width=\"8%\" align=\"center\">".$ser[$i][descripcion_corta]."</td>"; }
						$this->salida .= "            </tr>";
						$j=0;
						$d=0;
						foreach($nivel as $g => $t)
						{
									if($j % 2) {  $estilo="modulo_list_claro";  }
									else {  $estilo="modulo_list_oscuro";   }
									$this->salida .= "						<tr>";
									$this->salida .= "							<td colspan\"".$grupo[$g]."\" align=\"center\" class=\"$estilo\">$g</td>";
									$this->salida .= "							<td colspan=\"5\">";
									$f=0;
									foreach($t as $destipo => $desnivel)
									{
											if($f % 2) {  $estilo="modulo_list_claro";  }
											else {  $estilo="modulo_list_oscuro";   }
											$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
											$this->salida .= "						<tr class=\"$estilo\">";
											$this->salida .= "							<td  width=\"35%\" colspan\"".$tipo[$destipo]."\"  align=\"center\">$destipo</td>";
											for($i=0; $i<sizeof($ser); $i++)
											{
													$check='';
													if($desnivel[$ser[$i][descripcion_corta]])
													{ $check="<img src=\"".GetThemePath()."/images/endturn.png\">"; }
													$this->salida .= "									<td width=\"8%\" align=\"center\">".$check."</td>";
											}
											$this->salida .= "            </tr>";
											$this->salida .= "			      </table>";
											$f++;
									}
									$j++;
									$this->salida .= "							</td>";
									$this->salida .= "            </tr>";
									$d++;
						}
						$this->salida .= "			      </table>";
				}
	}

//-------------------------------------------------------------------------------------

	/**
	*
	*/
	function Habilitar()
	{
			$this->salida .= "<script language=\"javascript\">\n";
			$this->salida .= "  function Habilitar(obj,forma){\n";
			$this->salida .= "  var mirar=\"\";\n";
			$this->salida .= "  for(var i=0;i<forma.elements.length;i++)\n";
			$this->salida .= "  {\n";
			$this->salida .= "  mirar=forma.elements[i].name;\n";
			$this->salida .= "   if(mirar!=undefined)\n";
			$this->salida .= "   {\n";
			$this->salida .= "    if(mirar.search('DiasCama')!=-1)\n";
			$this->salida .= "    {\n";
			$this->salida .= "      if(forma.elements[i].type=='text')\n";
			$this->salida .= "      {\n";
			$this->salida .= "          forma.elements[i].type='hidden';\n";
			$this->salida .= "      }\n";
			$this->salida .= "    }\n";
			$this->salida .= "   }\n";
			$this->salida .= "  }\n";
			$this->salida .= "  if(obj.type=='hidden')\n";
			$this->salida .= "  {\n";
			$this->salida .= "     obj.type='text';\n";
			$this->salida .= "     obj.value='';\n";
			$this->salida .= "  }\n";
			$this->salida .= "  }\n";
			$this->salida .=  "</script>\n";
	}

	/**
	* Crear el combo de tipos de afiliados
	* @access private
	* @return string
	* @param array arreglo con los tipos de afiliados
	* @param int tipo de afiliado
	*/
	function BuscarIdTipoAfiliado($tipo_afiliado,$TipoAfiliado='')
	{
				$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0; $i<sizeof($tipo_afiliado); $i++)
				{
					if($tipo_afiliado[$i][tipo_afiliado_id]==$TipoAfiliado){
					 $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\" selected>".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
					}
					if($tipo_afiliado[$i][tipo_afiliado_id]==$_SESSION['SOLICITUDAUTORIZACION']['AFILIADO'][$tipo_afiliado[$i][tipo_afiliado_id]]){
					 $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\" selected>".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
					}
					else{
					 $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\">".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
					}
				}
	}


	/**
	*
	*/
	function FormaMenu()
	{
      	$this->salida .= ThemeAbrirTabla('AUTORIZACIONES - MENU AUTORIZACIONES');
				$this->salida .= "			      <br>";
				$this->salida .= "			      <table width=\"40%\" border=\"0\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">MENU AUTORIZACIONES</td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$accionB=ModuloGetURL('app','Autorizacion_Solicitud','user','principal');
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionB\">Solicitar Autorización</a></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$accionM=ModuloGetURL('app','Autorizacion_Solicitud','user','PermisosUsuario');
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionM\">Listar Autorizaciones Pendientes</a></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$accionM=ModuloGetURL('app','Autorizacion_Solicitud','user','FormaListadoAutorizacionesConfirmacion');
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionM\">Listar Autorizaciones Pendientes Confirmar</a></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$accionM=ModuloGetURL('app','Autorizacion_Solicitud','user','principal',array('Directa'=>'Directa'));
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionM\">Autorizaciones Directas</a></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$accionM=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarListadoAutorizaciones');
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionM\">Listar Autorizaciones</a></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "			     </table>";
				$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','main');
				$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></p>";
				$this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
				return true;
	}


	/**
	*
	*/
	function FormaListadoAutorizacionesPendientes()
	{
  			$this->salida .= ThemeAbrirTabla('AUTORIZACIONES - LISTADO AUTORIZACIONES PENDIENTES');
				$var=$this->ListadoAutorizacionesPendientes();
				//falta
				//$conf=$this->ListadoAutorizacionesConfirmacion();
				if($var)
				{
							for($i=0; $i<sizeof($var); $i++)
							{
									$this->salida .= "		<br><table width=\"70%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
									$this->salida .= "			<tr>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">SOLICITUD: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\">".$var[$i][solicitud_id]."</td>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">FECHA: </td>";
									$fecha=$this->CallMetodoExterno('app','Facturacion','user','FechaStamp',array($var[$i][fecha_registro]));
									$this->salida .= "				<td class=\"modulo_list_claro\">$fecha</td>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">HORA: </td>";
									$hora=$this->CallMetodoExterno('app','Facturacion','user','HoraStamp',array($var[$i][fecha_registro]));
									$this->salida .= "				<td class=\"modulo_list_claro\">$hora</td>";
									$this->salida .= "			</tr>";
									$this->salida .= "			<tr>";
									$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"23%\" nowrap>IDENTIFICACION: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\">".$var[$i][tipo_id_paciente]." ".$var[$i][paciente_id]."</td>";
									$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"15%\" nowrap>PACIENTE: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"3\">".$var[$i][nombre]."</td>";
									$this->salida .= "			</tr>";
									$this->salida .= "			<tr>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">ESTACION: </td>";
									$Est=$this->DescripcionEstacion($var[$i][estacion_id]);
									$this->salida .= "				<td class=\"modulo_list_claro\">$Est</td>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">PIEZA: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\">".$var[$i][pieza]."</td>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">CAMA: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\">".$var[$i][cama]."</td>";
									$this->salida .= "			</tr>";
									$this->salida .= "			<tr>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">INGRESO: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\">".$var[$i][ingreso]."</td>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">DEPARTAMENTO: </td>";
									$Dpto=$this->DescripcionDpto($var[$i][departamento]);
									$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"3\">$Dpto</td>";
									$this->salida .= "			</tr>";
									$this->salida .= "			<tr>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">OBSERVACION: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"5\">".$var[$i][observacion]."</td>";
									$this->salida .= "			</tr>";
									$this->salida .= "			<tr>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">USUARIO SOLICITUD: </td>";
									$usuario=$this->NombreUsuario($var[$i][usuario_id]);
									$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\">$usuario</td>";
									if($var[$i][sw_urgente]==1)
									{  $this->salida .= "				<td class=\"label_error\" align=\"center\">URGENTE</td>";  }
									else
									{  $this->salida .= "				<td class=\"modulo_list_claro\"></td>";  }
									$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','DetalleSolicitud',array('solicitud'=>$var[$i][solicitud_id],'datos'=>$var[$i]));
									$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\" align=\"center\"><a href=\"$accion\">VER DETALLE</a></td>";
									$this->salida .= "			</tr>";
									$this->salida .= "			     </table><br>";
						}
				}
				/*if($conf)
				{
							$this->FormaListadoAutorizacionesConfirmacion($conf);
				}*/
				if(!$var)
				{
						$this->salida .= "			      <br><table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
						$this->salida .= "				       <tr>";
						$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">NO HAY AUTORIZACIONES PENDIENTES</td>";
						$this->salida .= "				       </tr>";
						$this->salida .= "				       <tr>";
						$action=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarMenu');
						$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
						$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"><br></form></td>";
						$this->salida .= "				       </tr>";
						$this->salida .= "			     </table><BR>";
				}
				else
				{
						$this->salida .= "			      <table width=\"40%\" border=\"0\" align=\"center\" class=\"normla_10\" cellspacing=\"3\" cellpadding=\"3\">";
						$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarMenu');
						$this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
						$this->salida .= "				       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></td></tr></form>";
						$this->salida .= "			     </table>";
				}
       	$this->salida .= ThemeCerrarTabla();
				return true;
	}

	function FormaListadoAutorizacionesConfirmacion()
	{
  		$this->salida .= ThemeAbrirTabla('AUTORIZACIONES - LISTADO AUTORIZACIONES PENDIENTES');
			$conf=$this->ListadoAutorizacionesConfirmacion();
			for($i=0; $i<sizeof($conf); $i++)
			{
					$this->salida .= "		<br><table width=\"70%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
					$this->salida .= "			<tr>";
					$this->salida .= "				<td class=\"modulo_table_list_title\">AUTORIZACION: </td>";
					$this->salida .= "				<td class=\"modulo_list_claro\">".$conf[$i][autorizacion]."</td>";
					$this->salida .= "				<td class=\"modulo_table_list_title\">FECHA: </td>";
					$fecha=$this->CallMetodoExterno('app','Facturacion','user','FechaStamp',array($conf[$i][fecha_registro]));
					$this->salida .= "				<td class=\"modulo_list_claro\">$fecha</td>";
					$this->salida .= "				<td class=\"modulo_table_list_title\">HORA: </td>";
					$hora=$this->CallMetodoExterno('app','Facturacion','user','HoraStamp',array($conf[$i][fecha_registro]));
					$this->salida .= "				<td class=\"modulo_list_claro\">$hora</td>";
					$this->salida .= "			</tr>";
					$this->salida .= "			<tr>";
					$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"23%\" nowrap>IDENTIFICACION: </td>";
					$this->salida .= "				<td class=\"modulo_list_claro\">".$conf[$i][tipo_id_paciente]." ".$conf[$i][paciente_id]."</td>";
					$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"15%\" nowrap>PACIENTE: </td>";
					$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"3\">".$conf[$i][nombre]."</td>";
					$this->salida .= "			</tr>";
					$this->salida .= "			<tr>";
					$this->salida .= "				<td class=\"modulo_table_list_title\">INGRESO: </td>";
					$this->salida .= "				<td class=\"modulo_list_claro\">".$conf[$i][ingreso]."</td>";
					$this->salida .= "				<td class=\"modulo_table_list_title\">USUARIO: </td>";
					$usuario=$this->NombreUsuario($conf[$i][usuario_id]);
					$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"3\">$usuario</td>";
					$this->salida .= "			</tr>";
					$this->salida .= "			<tr>";
					$this->salida .= "				<td class=\"modulo_table_list_title\">OBSERVACION: </td>";
					$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"5\">".$conf[$i][observaciones]."</td>";
					$this->salida .= "			</tr>";
					$this->salida .= "			<tr>";
					$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','ConfirmarAutorizacion',array('autorizacion'=>$conf[$i][autorizacion],'Auto'=>'Si'));
					$this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					$this->salida .= "				<td class=\"modulo_table_list_title\">OBSERVACION: </td>";
					$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"5\"><textarea name=\"Observacion\" cols=\"76\" rows=\"3\" class=\"textarea\"></textarea></td>";
					$this->salida .= "			</tr>";
					$this->salida .= "			<tr>";
					$this->salida .= "				<td class=\"modulo_list_claro\"></td>";
					if($conf[$i][sw_urgente]==1)
					{  $this->salida .= "				<td class=\"label_error\" align=\"center\">URGENTE</td>";  }
					else
					{  $this->salida .= "				<td class=\"modulo_table_list_title\"></td>";  }

					$this->salida .= "				<td class=\"modulo_table_list_title\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"AUTORIZAR\"></td>";
					$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','ConfirmarAutorizacion',array('autorizacion'=>$conf[$i][autorizacion],'Auto'=>'No'));
					$this->salida .= "				<td class=\"modulo_table_list_title\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"NoAutorizar\" value=\"NO AUTORIZAR\"></td>";
					$this->salida .= "			</tr>";
					$this->salida .= "			</form>";
					$this->salida .= "			     </table><br>";
		}
		if(!$conf)
		{
				$this->salida .= "			      <br><table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">NO HAY AUTORIZACIONES PENDIENTES</td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$action=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarMenu');
				$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"><br></form></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "			     </table><BR>";
		}
		else
		{
				$this->salida .= "			      <table width=\"40%\" border=\"0\" align=\"center\" class=\"normla_10\" cellspacing=\"3\" cellpadding=\"3\">";
				$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarMenu');
				$this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "				       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></td></tr></form>";
				$this->salida .= "			     </table>";
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	*
	*/
	function FormaListadoAutorizaciones()
	{
				unset($_SESSION['SOLICITUDAUTORIZACION']);
  			$this->salida .= ThemeAbrirTabla('AUTORIZACIONES - LISTADO AUTORIZACIONES');
				$var=$this->ListadoAutorizaciones();
				$this->DetalleListadoAutorizaciones($var);
				if(!$var)
				{
						$this->salida .= "			      <br><table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
						$this->salida .= "				       <tr>";
						$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">NO HAY AUTORIZACIONES PENDIENTES</td>";
						$this->salida .= "				       </tr>";
						$this->salida .= "				       <tr>";
						$action=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarMenu');
						$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
						$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"><br></form></td>";
						$this->salida .= "				       </tr>";
						$this->salida .= "			     </table><BR>";
				}
				else
				{
						$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarMenu');
						$this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
						$this->salida .= "				       <p align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></p></form>";
				}
       	$this->salida .= ThemeCerrarTabla();
				return true;
	}


	/**
	*
	*/
	function DetalleListadoAutorizaciones($var)
	{
							for($i=0; $i<sizeof($var); $i++)
							{
									$this->salida .= "		<br><table width=\"70%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
									$this->salida .= "			<tr>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">SOLICITUD: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\">".$var[$i][solicitud_id]."</td>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">FECHA: </td>";
									$fecha=$this->CallMetodoExterno('app','Facturacion','user','FechaStamp',array($var[$i][fecha_registro]));
									$this->salida .= "				<td class=\"modulo_list_claro\">$fecha</td>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">HORA: </td>";
									$hora=$this->CallMetodoExterno('app','Facturacion','user','HoraStamp',array($var[$i][fecha_registro]));
									$this->salida .= "				<td class=\"modulo_list_claro\">$hora</td>";
									$this->salida .= "			</tr>";
									$this->salida .= "			<tr>";
									$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"23%\" nowrap>IDENTIFICACION: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\">".$var[$i][tipo_id_paciente]." ".$var[$i][paciente_id]."</td>";
									$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"15%\" nowrap>PACIENTE: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"3\">".$var[$i][nombre]."</td>";
									$this->salida .= "			</tr>";
									$this->salida .= "			<tr>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">ESTACION: </td>";
									$Est=$this->DescripcionEstacion($var[$i][estacion_id]);
									$this->salida .= "				<td class=\"modulo_list_claro\">$Est</td>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">PIEZA: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\">".$var[$i][pieza]."</td>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">CAMA: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\">".$var[$i][cama]."</td>";
									$this->salida .= "			</tr>";
									$this->salida .= "			<tr>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">INGRESO: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\">".$var[$i][ingreso]."</td>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">DEPARTAMENTO: </td>";
									$Dpto=$this->DescripcionDpto($var[$i][departamento]);
									$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"3\">$Dpto</td>";
									$this->salida .= "			</tr>";
									$this->salida .= "			<tr>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">OBSERVACION: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"5\">".$var[$i][observacion]."</td>";
									$this->salida .= "			</tr>";
									$this->salida .= "			<tr>";
									$this->salida .= "				<td class=\"modulo_table_list_title\">USUARIO SOLICITUD: </td>";
									$usuario=$this->NombreUsuario($var[$i][usuario_id]);
									$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\">$usuario</td>";
									if($var[$i][sw_urgente]==1)
									{  $this->salida .= "				<td class=\"label_error\" align=\"center\">URGENTE</td>";  }
									else
									{  $this->salida .= "				<td class=\"modulo_list_claro\"></td>";  }
									$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','DetalleSolicitudAuto',array('solicitud'=>$var[$i][solicitud_id],'datos'=>$var[$i]));
									$this->salida .= "				<td class=\"modulo_table_list_title\" colspan=\"2\"><a href=\"$accion\">VER DETALLE</a></td>";
									$this->salida .= "			</tr>";
									$this->salida .= "			     </table><br>";
						}
	}


	/**
	*
	*/
	function SinIngreso()
	{
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function SinIngreso(ingreso){";
		$this->salida .= " var str = 'width='+ancho+',height='+altura+',X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=yes';";
		$this->salida .= " var url2 = url+'&tipoId='+frm.TipoId.value+'&pacienteId='+frm.PacienteId.value;";
		$this->salida .= " rem = window.open(url2, nombre, str);";
		$this->salida .= "  if (rem != null) {";
		$this->salida .= "     if (rem.opener == null) {";
		$this->salida .= "       rem.opener = self;";
		$this->salida .= "     }";
		$this->salida .= "  }";
		$this->salida .= "}";
		$this->salida .= "</SCRIPT>";
	}

	/**
	*
	*/
	/*function FormaLink()
	{
				$this->salida .= "		<table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "			<tr>";
				$this->salida .= "			</tr>";
				$this->salida .= "		</table>";
	}*/



	/**
	*
	*/
	function EncabezadoAutorizacion()
	{
				$Est=$this->DescripcionEstacion($_SESSION['SOLICITUDAUTORIZACION']['estacion_id']);
				$datos=$_SESSION['SOLICITUDAUTORIZACION']['DATOS'];
				$Solicitud=$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'];
				$this->salida .= "		<table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td class=\"modulo_table_list_title\">SOLICITUD: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\">".$datos[solicitud_id]."</td>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"10%\">FECHA: </td>";
				$fecha=$this->CallMetodoExterno('app','Facturacion','user','FechaStamp',array($datos[fecha_registro]));
				$this->salida .= "				<td class=\"modulo_list_claro\">$fecha</td>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"10%\">HORA: </td>";
				$hora=$this->CallMetodoExterno('app','Facturacion','user','HoraStamp',array($datos[fecha_registro]));
				$this->salida .= "				<td class=\"modulo_list_claro\">$hora</td>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"13%\">DEPARTAMENTO: </td>";
				$Dpto=$this->DescripcionDpto($datos[departamento]);
				$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\" width=\"30%\">$Dpto</td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"15%\" nowrap>IDENTIFICACION: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\">".$datos[tipo_id_paciente]." ".$datos[paciente_id]."</td>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"12%\" nowrap>PACIENTE: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"4\">".$datos[nombre]."</td>";
			/*	$url=ModuloGetURL('app','Autorizacion_Solicitud','user','FormaLink');
				if($datos[ingreso])
				{
						$accion="ConIngreso('$url',".$datos[ingreso].")";
				}
				else
				{
						$accion="SinIngreso('$url','".$datos[tipo_id_paciente]."',".$datos[paciente_id].")";
				}*/
				$this->salida .= "				<td class=\"modulo_list_claro\" align=\"center\"></td>";
				//$this->salida .= "				<td class=\"modulo_list_claro\" align=\"center\"><a href=\"javascript:$accion\">VER</a></td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"15%\" nowrap>TIPO AFILIADO: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\">".$datos[tipo_afiliado_nombre]."</td>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"12%\" nowrap>RANGO: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\">".$datos[rango]."</td>";
				$this->salida .= "				<td class=\"modulo_table_list_title\">INGRESO: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\" width=\"23%\">".$datos[ingreso]."</td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"8%\">PLAN: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"8\">".$datos[plan_descripcion]."</td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"8%\">PIEZA: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\">".$_SESSION['SOLICITUDAUTORIZACION']['pieza']."</td>";
				$this->salida .= "				<td class=\"modulo_table_list_title\">CAMA: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\" width=\"8%\">".$_SESSION['SOLICITUDAUTORIZACION']['cama']."</td>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"20%\">ESTACION ENFERMERIA: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"4\">$Est</td>";
				$this->salida .= "			</tr>";
				$this->salida .= "		</table>";
	}

	/**
	*
	*/
	function ComboJustificacion()
	{
			$this->salida .= "<SCRIPT>\n";
			$this->salida .= "function ComboJustificacion(valor,forma){\n";
 			$this->salida .= "  if(valor!=-1){;\n";
			$this->salida .= "     forma.Observaciones.value=valor;\n";
			$this->salida .= "  }\n";
			$this->salida .= "}\n";
			$this->salida .= "</SCRIPT>\n";
	}

	/**
	*
	*/
	function FormaDetalleSolictud()
	{
				IncludeLib("tarifario");
				$this->salida .= ThemeAbrirTabla('AUTORIZACIONES - DETALLE SOLICITUD AUTORIZACION');
				$Solicitud=$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'];
 				$this->EncabezadoAutorizacion();
				$this->salida .= "		<br><table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "			<tr>";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "			</tr>";
				$this->salida .= "		</table>";
				$servicio=$this->DetalleServicio($Solicitud);
				$cargo=$this->DetalleCargo($Solicitud);
				$_SESSION['SOLICITUDAUTORIZACION']['TAMAÑO'] = sizeof($servicio) + sizeof($cargo);
				$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','Insertar',array('datos'=>$datos));
				$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
				$this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\" class=\"modulo_table_list\">";
				$this->salida .= "       <input type=\"hidden\" name=\"solicitud\" value=\"$Solicitud\">";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">";
				$this->salida .= "				<td>SOLICITUD AUTORIZACION</td>";
				$this->salida .= "      </tr>";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td>";
				if($servicio)
				{
							$grupo=$_SESSION['SOLICITUDAUTORIZACION']['SER']['GRUPO'];
							$tipo=$_SESSION['SOLICITUDAUTORIZACION']['SER']['TIPO'];
							$nivel=$_SESSION['SOLICITUDAUTORIZACION']['SER']['NIVEL'];
							$data=$servicio;
							$this->salida .= "     <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
							$this->salida .= "						<tr class=\"modulo_table_list_title\">";
							$this->salida .= "							<td align=\"center\" width=\"25%\">GRUPO</td>";
							$this->salida .= "							<td align=\"center\" width=\"35%\">TIPO</td>";
							$ser=$this->NivelesAtencion();
							for($i=0; $i<sizeof($ser); $i++)
							{ $this->salida .= "							<td width=\"8%\" align=\"center\">".$ser[$i][descripcion_corta]."</td>"; }
							$this->salida .= "            </tr>";
							$j=0;
							$d=0;
							foreach($nivel as $g => $t)
							{
										if($j % 2) {  $estilo="modulo_list_claro";  }
										else {  $estilo="modulo_list_oscuro";   }
										$this->salida .= "						<tr>";
										$this->salida .= "							<td colspan\"".$grupo[$g]."\" align=\"center\" class=\"$estilo\">$g</td>";
										$this->salida .= "							<td colspan=\"5\">";
										$f=0;
										foreach($t as $destipo => $desnivel)
										{
												if($f % 2) {  $estilo="modulo_list_claro";  }
												else {  $estilo="modulo_list_oscuro";   }
												$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" cellspacing=\"1\" cellpadding=\"0\">";
												$this->salida .= "						<tr class=\"$estilo\">";
												$this->salida .= "							<td  width=\"35%\" colspan\"".$tipo[$destipo]."\"  align=\"center\">$destipo</td>";
												for($i=0; $i<sizeof($ser); $i++)
												{
														$check='';
														if($desnivel[$ser[$i][descripcion_corta]])
														{
																$check="<input type=\"checkbox\" value=\"".$data[$d][grupo_tipo_cargo].",".$data[$d][tipo_cargo].",".$ser[$i][nivel].",".$data[$d][servicio]."\" name=\"Nivel".$data[$d][grupo_tipo_cargo].$data[$d][tipo_cargo].$ser[$i][nivel]."\"";
																$x=$_SESSION['SOLICITUDAUTORIZACION']['SERVICIO'][$data[$d][grupo_tipo_cargo]][$data[$d][tipo_cargo]][$ser[$i][nivel]];
																$y=$data[$d][grupo_tipo_cargo].$data[$d][tipo_cargo].$ser[$i][nivel];
																if($x==$y)
																{  $check .= "checked";  }
																$check .= ">";
																$d++;
														}
														$this->salida .= "									<td width=\"8%\" align=\"center\">".$check."</td>";
												}
												$this->salida .= "            </tr>";
												$this->salida .= "			      </table>";
												$f++;
										}
										$j++;
										$this->salida .= "							</td>";
										$this->salida .= "            </tr>";
							}
							$this->salida .= "			      </table>";
				}
				if($cargo)
				{
						$this->salida .= "			      <br><table width=\"95%\" border=\"0\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\" class=\"modulo_table_list\">";
						$this->salida .= "				       <tr align=\"center\" class=\"modulo_table_list_title\">";
						$this->salida .= "				          <td width=\"15%\">CODIGO</td>";
						$this->salida .= "				          <td>CARGO</td>";
						$this->salida .= "				          <td width=\"3%\">CANT.</td>";
						$this->salida .= "				          <td></td>";
						$this->salida .= "				       </tr>";
						for($i=0; $i<sizeof($cargo); $i++)
						{
								if($i % 2) {  $estilo="modulo_list_oscuro";  }
								else {  $estilo="modulo_list_claro";   }
								$this->salida .= "				       <tr align=\"center\" class=\"$estilo\">";
								$this->salida .= "				          <td>".$cargo[$i][cargo]."</td>";
								$this->salida .= "				          <td>".$cargo[$i][descripcion]."</td>";
								$this->salida .= "				          <td>".$cargo[$i][cantidad]."</td>";
								$this->salida .= "             		<td width=\"3%\"><input type=\"checkbox\" value=\"".$cargo[$i][tarifario_id].",".$cargo[$i][cargo].",".$cargo[$i][cantidad].",".$cargo[$i][servicio]."\" name=\"Cargos".$cargo[$i][tarifario_id].$cargo[$i][cargo]."\"";
								$x=$_SESSION['SOLICITUDAUTORIZACION']['CARGO'][$cargo[$i][tarifario_id]][$cargo[$i][cargo]];
								if($x==$cargo[$i][cargo])
								{  $this->salida .= "checked";  }
								$this->salida .= "></td>";
								$this->salida .= "				       </tr>";
						}
						$this->salida .= "			     </table>";
				}
				$this->salida .= "     <br><table border=\"0\" width=\"95%\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\"  class=\"modulo_table_list\">";
				$this->salida .= "       <tr><td width=\"25%\" class=\"modulo_table_list_title\">OBSERVACIONES SOLICITUD: </td>";
				$datos=$_SESSION['SOLICITUDAUTORIZACION']['DATOS'];
				$this->salida .= "          <td class=\"modulo_list_claro\" align=\"left\">".$datos[observacion]."</td></tr>";
				$this->salida .= "			 </table>";
				$this->salida .= "				</td>";
				$this->salida .= "			</tr>";
				$this->salida .= "		</table>";
				//si no tiene saldo a favor no se puede autorizar
				$saldo=$this->Saldo($datos[ingreso]);
				if($saldo < 0)
				{
						//TIPO AUTORIZACION
						$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
						$this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
						$this->salida .= "      <td width=\"33%\">SELECCIONE TIPO AUTORIZACION: </td>";
						$this->salida .= "      <td class=\"modulo_list_claro\"><select name=\"TipoAutorizacion\" class=\"select\">";
						$TiposAuto=$this->CallMetodoExterno('app','Autorizacion','user','TiposAuto');
						$this->BuscarTipoAutorizacion($TiposAuto,$_REQUEST['TipoAutorizacion']);
						$this->salida .= "      </select></td>";
						//$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','PedirAutorizacion');
						$this->salida .= "	    <td><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
						$this->salida .= "      </tr>";
						$this->salida .= "		 </table><BR>";
						//fecha de la autorizacion
						$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\" class=\"normal_10\">";
						$this->salida .= "      </tr>";
						$this->salida .= "	<td class=\"".$this->SetStyle("FechaAuto")."\">FECHA AUTORIZACION: </td>";
						if(!$FechaAuto){ $FechaAuto=date("d/m/Y"); }
						$this->salida .= "	<td><input type=\"text\" class=\"input-text\" name=\"FechaAuto\" size=\"12\" value=\"".$FechaAuto."\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
						$this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('forma','FechaAuto','/')."</td>";
						if(!$HoraAuto){ $HoraAuto=date('H'); }
						if(!$MinAuto){ $MinAuto=date('i'); }
						$this->salida .= "	<td class=\"".$this->SetStyle("HoraAuto")."\">HORA AUTORIZACION: </td>";
						$this->salida .= "	<td><input type=\"text\" class=\"input-text\" name=\"HoraAuto\" size=\"4\" value=\"".$HoraAuto."\" maxlength=\"2\">&nbsp;:&nbsp;<input type=\"text\" class=\"input-text\" name=\"MinAuto\" size=\"4\" value=\"".$MinAuto."\" maxlength=\"2\"></td>";
						$this->salida .= "      </tr>";
						$this->salida .= "		 </table>";
						//OBSERVACIONES
						$this->salida .= " <table border=\"0\" width=\"80%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
						$observacion=$this->Observaciones();
						//if(!empty($observacion))
						if($observacion!=' ' AND $observacion!='')
						{
								$this->salida .= "	<tr>";
								$this->salida .= "	<td  width=\"30%\" class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES DE LAS AUTORIZACION REALIZADAS: </td>";
								$this->salida .= "	<td><textarea  cols=\"80\" rows=\"4\" class=\"textarea\" name=\"ObservacionesT\" readonly>$observacion</textarea></td>";
								$this->salida .= "	</tr><br>";
						}
						$this->salida .= "	<tr>";
						$this->salida .= "	<td  width=\"30%\" class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES AUTORIZACION: </td>";
						$obs='';
						$this->salida .= "	<td><textarea  cols=\"80\" rows=\"4\" class=\"textarea\" name=\"ObservacionesA\">$obs".$_SESSION['AUTORIZACIONES']['ObservacionesA']."</textarea></td>";
						$this->salida .= "	</tr><br>";
						$this->salida .= "		 </table><BR>";
					   //OBSERVACIONES INGRESO
						$this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
						$this->salida .= "	<tr>";
						$this->salida .= "	<td   width=\"30%\" class=\"".$this->SetStyle("ObservacionesI")."\" align=\"left\">OBSERVACIONES INGRESO:<br>( Esta observación será mostrada durante todo el manejo de toda la cuenta. ) </td>";
						$this->salida .= "	<td><textarea  cols=\"80\" rows=\"3\" class=\"textarea\" name=\"ObservacionesI\">".$_SESSION['AUTORIZACIONES']['ObservacionesI']."</textarea></td>";
						$this->salida .= "	</tr><br>";
						$this->salida .= "		 </table><BR>";
						//url protocolo
						if(!empty($_SESSION['SOLICITUDAUTORIZACION']['DATOS']['plan_id']))
						{
								$p=$this->BuscarProtocolo($_SESSION['SOLICITUDAUTORIZACION']['DATOS']['plan_id']);
								if(!empty($p))
								{
										if(file_exists("protocolos/$p"))
										{
												$Protocolo=$p;
												$this->salida .= "<script>";
												$this->salida .= "function Protocolo(valor){";
												$this->salida .= "window.open('protocolos/'+valor,'PROTOCOLO','');";
												$this->salida .= "}";
												$this->salida .= "</script>";
												$accion="javascript:Protocolo('$Protocolo')";
												$this->salida .= "			    <br><table width=\"40%\" align=\"center\" border=\"0\" class=\"normal_10\" cellpadding=\"3\">";
												$this->salida .= "             <tr class=\"modulo_list_claro\">";
												$this->salida .= "             		<td width=\"30%\" class=\"label\">PROTOCOLO</td>";
												$this->salida .= "             		<td><a href=\"$accion\">$Protocolo</a></td>";
												$this->salida .= "             </tr>";
												$this->salida .= "			      </table><br>";
										}
								}
						}
						//botones
						$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
						$this->salida .= "      </tr>";
						$this->salida .= "      <tr align=\"center\">";
						$this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"AUTORIZAR\"></td>";
						$this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"NoAutorizar\" value=\"NO AUTORIZAR\"></td>";
						$this->salida .= "			</form>";
						$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','PermisosUsuario');
						$this->salida .= "	    <td><form name=\"forma2\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></form></td>";
						$this->salida .= "      </tr>";
						$this->salida .= "		 </table>";
				}
				else
				{
						$this->salida .= "			</form>";
						$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
						$this->salida .= "      <tr align=\"center\">";
						$this->salida .= "	    <td class=\"label_error\"><br>EL PACIENTE NO TIENE SALDO A FAVOR, SU SALDO ES $ ". FormatoValor($saldo)."</td>";
						$this->salida .= "      </tr>";
						$this->salida .= "      <tr align=\"center\">";
						$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','PermisosUsuario');
						$this->salida .= "	    <td><form name=\"forma2\" action=\"$accion\" method=\"post\"><BR><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></form></td>";
						$this->salida .= "      </tr>";
						$this->salida .= "		 </table>";
				}
				$this->salida .= ThemeCerrarTabla();
				return true;
	}


	/**
	*
	*/
	function FormaJustificar($auto)
	{
			$this->salida .= ThemeAbrirTabla('AUTORIZACIONES - JUSTIFICAR NO AUTORIAZCION');
			$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','JustificarNoAutorizacion',array('auto'=>$auto));
			$this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
			$jus=$this->Justificacion();
			$this->ComboJustificacion();
			$this->salida .= " <table border=\"0\" width=\"90%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "	<tr>";
			$this->salida .= "	<td class=\"label_error\" colspan=\"2\" align=\"center\">DEBE JUSTIFICAR PORQUE NO AUTORIZO</td>";
			$this->salida .= "	</tr>";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "	<tr>";
			$this->salida .= "	<td class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES: </td>";
			$this->salida .= "	<td class=\"label\">JUSTIFICACION: </td>";
			$this->salida .= "	</tr>";
			$this->salida .= "	<tr>";
			$this->salida .= "	<td><textarea  cols=\"85\" rows=\"7\" class=\"textarea\" name=\"Observaciones\">$Observaciones</textarea></td>";
			$this->salida .= "	<td><select name=\"Tipo\" class=\"select\" onchange=\"ComboJustificacion(this.value,this.form)\">";
			$this->salida .=" <option value=\"-1\">-----SELECCIONE-----</option>";
			for($j=0; $j<sizeof($jus); $j++)
			{
					$f=$r='';
					if($jus[$j][justificacion])
					{  $f='JUSTIFICACION: '.$jus[$j][justificacion];  }
					if($jus[$j][recomendaciones])
					{  $r='RECOMENDACIONES: '.$jus[$j][recomendaciones];  }
					$this->salida .=" <option value=\"".$f."\n\n".$r."\">".$jus[$j][nombre]."</option>";
			}
			$this->salida .= "	</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "	</table><BR>";
			$this->salida .= "<br><table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"40%\" align=\"center\">";
			$this->salida .= "	<tr>";
			$this->salida .= "	<td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"GUARDAR\"></td>";
			$this->salida .= "  </form>";
			if(empty($_SESSION['SOLICITUDAUTORIZACION']['NOAUTORIZACION']['REQUEST']))
			{  $accion=ModuloGetURL('app','Autorizacion_Solicitud','user','FormaAutorizacionDirecta');  }
			else
			{  $accion=ModuloGetURL('app','Autorizacion_Solicitud','user','FormaDetalleSolictud');  }
			$this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
			$this->salida .= "	<td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"CANCELAR\"></td>";
			$this->salida .= "  </form>";
			$this->salida .= "	</tr>";
			$this->salida .= "	</table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
	}

	/**
	*
	*/
	function FormaDetalleSolictudAuto()
	{
  		$this->salida .= ThemeAbrirTabla('AUTORIZACIONES - DETALLE SOLICITUD');
			$Solicitud=$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'];
			$servicio=$this->DetalleServicio($Solicitud);
			$cargo=$this->DetalleCargo($Solicitud);
			$this->EncabezadoAutorizacion();
			$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','AutorizacionSistema');
			$this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "   <BR> <table border=\"0\" width=\"90%\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\" class=\"modulo_table_list\">";
			$this->salida .= "       <input type=\"hidden\" name=\"solicitud\" value=\"$Solicitud\">";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">";
			$this->salida .= "				<td>SOLICITUD AUTORIZACION</td>";
			$this->salida .= "      </tr>";
			$this->salida .= "			<tr>";
			$this->salida .= "				<td>";
			if($servicio)
			{
						$grupo=$_SESSION['SOLICITUDAUTORIZACION']['SER']['GRUPO'];
						$tipo=$_SESSION['SOLICITUDAUTORIZACION']['SER']['TIPO'];
						$nivel=$_SESSION['SOLICITUDAUTORIZACION']['SER']['NIVEL'];
						$data=$servicio;
						$this->salida .= "     <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
						$this->salida .= "						<tr class=\"modulo_table_list_title\">";
						$this->salida .= "							<td align=\"center\" width=\"25%\">GRUPO</td>";
						$this->salida .= "							<td align=\"center\" width=\"35%\">TIPO</td>";
						$ser=$this->NivelesAtencion();
						for($i=0; $i<sizeof($ser); $i++)
						{ $this->salida .= "							<td width=\"8%\" align=\"center\">".$ser[$i][descripcion_corta]."</td>"; }
						$this->salida .= "            </tr>";
						$j=0;
						$d=0;
						foreach($nivel as $g => $t)
						{
									if($j % 2) {  $estilo="modulo_list_claro";  }
									else {  $estilo="modulo_list_oscuro";   }
									$this->salida .= "						<tr>";
									$this->salida .= "							<td colspan\"".$grupo[$g]."\" align=\"center\" class=\"$estilo\">$g</td>";
									$this->salida .= "							<td colspan=\"5\">";
									$f=0;
									foreach($t as $destipo => $desnivel)
									{
											if($f % 2) {  $estilo="modulo_list_claro";  }
											else {  $estilo="modulo_list_oscuro";   }
											$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" cellspacing=\"1\" cellpadding=\"0\">";
											$this->salida .= "						<tr class=\"$estilo\">";
											$this->salida .= "							<td  width=\"35%\" colspan\"".$tipo[$destipo]."\"  align=\"center\">$destipo</td>";
											for($i=0; $i<sizeof($ser); $i++)
											{
													$check='';
													if($desnivel[$ser[$i][descripcion_corta]])
													{ $check="<img src=\"".GetThemePath()."/images/endturn.png\">"; }
													$this->salida .= "									<td width=\"8%\" align=\"center\">".$check."</td>";
											}
											$this->salida .= "            </tr>";
											$this->salida .= "			      </table>";
											$f++;
									}
									$j++;
									$this->salida .= "							</td>";
									$this->salida .= "            </tr>";
						}
						$this->salida .= "			      </table>";
			}
			if($cargo)
			{
					$this->salida .= "			      <br><table width=\"95%\" border=\"0\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\" class=\"modulo_table_list\">";
					$this->salida .= "				       <tr align=\"center\" class=\"modulo_table_list_title\">";
					$this->salida .= "				          <td width=\"15%\">CODIGO</td>";
					$this->salida .= "				          <td>CARGO</td>";
					$this->salida .= "				          <td width=\"3%\">CANT.</td>";
					$this->salida .= "				       </tr>";
					for($i=0; $i<sizeof($cargo); $i++)
					{
							if($i % 2) {  $estilo="modulo_list_oscuro";  }
							else {  $estilo="modulo_list_claro";   }
							$this->salida .= "				       <tr align=\"center\" class=\"$estilo\">";
							$this->salida .= "				          <td>".$cargo[$i][cargo]."</td>";
							$this->salida .= "				          <td>".$cargo[$i][descripcion]."</td>";
							$this->salida .= "				          <td>".$cargo[$i][cantidad]."</td>";
							$this->salida .= "				       </tr>";
					}
					$this->salida .= "			     </table>";
			}
			$this->salida .= "     <br><table border=\"0\" width=\"95%\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\"  class=\"modulo_table_list\">";
			$this->salida .= "       <tr><td width=\"25%\" class=\"modulo_table_list_title\">OBSERVACIONES SOLICITUD: </td>";
			$datos=$_SESSION['SOLICITUDAUTORIZACION']['DATOS'];
			$this->salida .= "          <td class=\"modulo_list_claro\" align=\"left\">".$datos[observacion]."</td></tr>";
			$this->salida .= "			 </table>";
			$this->salida .= "				</td>";
			$this->salida .= "			</tr>";
			$this->salida .= "		</table>";
			$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "	<tr>";
			$this->salida .= "	<td class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES: </td>";
			$this->salida .= "	<td><textarea  cols=\"80\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">$Observaciones</textarea></td>";
			$this->salida .= "	</tr><br>";
			$this->salida .= "		 </table><BR>";
			//url protocolo
			if(!empty($_SESSION['SOLICITUDAUTORIZACION']['DATOS']['plan_id']))
			{
					$p=$this->BuscarProtocolo($_SESSION['SOLICITUDAUTORIZACION']['DATOS']['plan_id']);
					if(!empty($p))
					{
							if(file_exists("protocolos/$p"))
							{
									$Protocolo=$p;
									$this->salida .= "<script>";
									$this->salida .= "function Protocolo(valor){";
									$this->salida .= "window.open('protocolos/'+valor,'PROTOCOLO','');";
									$this->salida .= "}";
									$this->salida .= "</script>";
									$accion="javascript:Protocolo('$Protocolo')";
									$this->salida .= "			    <br><table width=\"40%\" align=\"center\" border=\"0\" class=\"normal_10\" cellpadding=\"3\">";
									$this->salida .= "             <tr class=\"modulo_list_claro\">";
									$this->salida .= "             		<td width=\"30%\" class=\"label\">PROTOCOLO</td>";
									$this->salida .= "             		<td><a href=\"$accion\">$Protocolo</a></td>";
									$this->salida .= "             </tr>";
									$this->salida .= "			      </table><br>";
							}
					}
			}
			$this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "      <tr align=\"center\">";
			$this->salida .= "      <td class=\"\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizacion\" value=\"AUTORIZAR\"></td>";
			$this->salida .= "			 </form>";
			$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','FormaListadoAutorizaciones');
			$this->salida .= "	    <td><form name=\"forma2\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></form></td>";
			$this->salida .= "      </tr>";
			$this->salida .= "		 </table><BR>";
      $this->salida .= ThemeCerrarTabla();
			return true;
	}


	/**
	*
	*/
	function FormaAutorizacion($Tipo)
	{
				$this->salida .= ThemeAbrirTabla('AUTORIZACIONES - DETALLE SOLICITUD AUTORIZACION');
				if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
 				{  $this->EncabezadoAutorizacion();  }
				else {	$this->Encabezado();  }
				$this->salida .= "		<br><table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "			<tr>";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "			</tr>";
				$this->salida .= "		</table>";
				$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','InsertarAutorizacion',array('Tipo'=>$Tipo));
				$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
				//elige el tipo de autorizacion
				if($Tipo=='01')
				{  $this->AutorizacionTele();      }
				if($Tipo=='02')
				{   $this->AutorizacionEscrita();  }
				if($Tipo=='04')
				{   $this->AutorizacionInterna();  }
				if($Tipo=='05')
				{   $this->AutorizacionElectronica(); }
				$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "      </tr>";
				$this->salida .= "      <tr align=\"center\">";
				$this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"ACEPTAR\"></td>";
				$this->salida .= "			</form>";
				if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
				{  $accion=ModuloGetURL('app','Autorizacion_Solicitud','user','DetalleSolicitud');  }
				else
				{  $accion=ModuloGetURL('app','Autorizacion_Solicitud','user','FormaAutorizacionDirecta');  }
				$this->salida .= "	    <td><form name=\"forma2\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></form></td>";
				$this->salida .= "      </tr>";
				$this->salida .= "		 </table>";
        $this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	*
	*/
	function AutorizacionTele()
	{
				$this->salida .= "<table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td class=\"modulo_table_list_title\">DATOS AUTORIZACION TELEFONICA</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td>";
				$this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td class=\"".$this->SetStyle("CodAuto")."\">COD. AUTORIZACION: </td>";
				$this->salida .= "	<td><input type=\"text\" class=\"input-text\" name=\"CodAuto\" size=\"12\" value=\"".$_REQUEST['CodAuto']."\"></td>";
				$this->salida .= "	<td class=\"".$this->SetStyle("Responsable")."\">RESPONSABLE: </td>";
				$this->salida .= "	<td><input type=\"text\" class=\"input-text\" name=\"Responsable\" size=\"20\" value=\"".$_REQUEST['Responsable']."\"></td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES: </td>";
				$this->salida .= "	<td colspan=\"3\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">".$_REQUEST['Observaciones']."</textarea></td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	</table>";
				$this->salida .= "	</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	</table>";
	}

	/**
	*
	*/
	function AutorizacionEscrita()
	{
				$this->salida .= "<br><table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td class=\"modulo_table_list_title\">DATOS AUTORIZACION ESCRITA</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td>";
				$this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td class=\"".$this->SetStyle("CodAuto")."\">COD. AUTORIZACION: </td>";
				$this->salida .= "	<td><input type=\"text\" class=\"input-text\" name=\"CodAuto\" size=\"12\" value=\"".$_REQUEST['CodAuto']."\"></td>";
				$this->salida .= "	<td class=\"".$this->SetStyle("Validez")."\">VALIDEZ: </td>";
				$this->salida .= "	<td><input type=\"text\" class=\"input-text\" name=\"Validez\" size=\"12\" value=\"".$_REQUEST['Validez']."\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
				$this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('forma','Validez','/')."</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES: </td>";
				$this->salida .= "	<td colspan=\"3\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">".$_REQUEST['Observaciones']."</textarea></td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	</table>";
				$this->salida .= "	</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	</table>";
	}

	/**
	*
	*/
	function AutorizacionInterna()
	{
				$this->salida .= "<br><table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td class=\"modulo_table_list_title\">DATOS AUTORIZACION INTERNA</td>";
				$this->salida .= "	</tr>";
				//usuarios
				$this->salida .= "	<tr>";
				$this->salida .= "	<td class=\"label_error\" align=\"center\">";
				$usu=$this->BuscarUsuarios($_SESSION['SOLICITUDAUTORIZACION']['plan_id']);
				if($usu)
				{
						$this->salida .= "      <table border=\"0\" width=\"30%\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\">";
						$this->salida .= "          <tr align=\"center\" class=\"modulo_table_list_title\">";
						$this->salida .= "            <td>USUARIOS AUTORIZADORES</td>";
						$this->salida .= "            <td></td>";
						$this->salida .= "          </tr>";
						for($i=0; $i<sizeof($usu); $i++)
						{
								$this->salida .= "          <tr class=\"modulo_list_claro\">";
								$this->salida .= "            <td>".$usu[$i][nombre]."</td>";
								$this->salida .= "            <td align=\"center\"><input type=\"radio\" value=\"".$usu[$i][usuario_id]."\" name=\"Responsable\"></td>";
								$this->salida .= "          </tr>";
						}
						$this->salida .= "			 </table><br>";
						$this->salida .= "	</td>";
						$this->salida .= "	</tr>";
						$this->salida .= "	<tr>";
						$this->salida .= "	<td>";
						$this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
						$this->salida .= "	<tr>";
						$this->salida .= "	<td class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES: </td>";
						$this->salida .= "	<td colspan=\"3\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">".$_REQUEST['Observaciones']."</textarea></td>";
						$this->salida .= "	</tr>";
						$this->salida .= "	</table>";
				}
				else
				{   $this->salida .= "NO HAY USUARIO AUTORIZADORES PARA ESTE PLAN";  }
				$this->salida .= "	</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	</table>";
	}


	/**
	*
	*/
	function AutorizacionElectronica()
	{
				$this->salida .= "<br><table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td class=\"modulo_table_list_title\">DATOS AUTORIZACION ELECTRONICA</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td>";
				$this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td class=\"".$this->SetStyle("CodAuto")."\">COD. AUTORIZACION: </td>";
				$this->salida .= "	<td><input type=\"text\" class=\"input-text\" name=\"CodAuto\" size=\"12\" value=\"".$_REQUEST['CodAuto']."\"></td>";
				$this->salida .= "	<td class=\"".$this->SetStyle("Validez")."\">VALIDEZ: </td>";
				$this->salida .= "	<td><input type=\"text\" class=\"input-text\" name=\"Validez\" size=\"12\" value=\"".$_REQUEST['Validez']."\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
				$this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('forma','Validez','/')."</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES: </td>";
				$this->salida .= "	<td colspan=\"3\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">".$_REQUEST['Observaciones']."</textarea></td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	</table>";
				$this->salida .= "	</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	</table>";
	}


	/**
	*
	*/
	function FormaMensaje($mensaje,$titulo,$accion)
	{
				$this->salida .= ThemeAbrirTabla($titulo);
				$this->salida .= "			      <table width=\"60%\" border=\"0\" align=\"center\" >";
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "				       <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
				$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
				$this->salida .= "			     </form>";
				$this->salida .= "			     </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	*
	*/
	function BuscarTipoAutorizacion($TiposAuto,$Tipo)
	{
			$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
			for($i=0; $i<sizeof($TiposAuto); $i++){
					if($TiposAuto[$i][tipo_autorizacion]==$Tipo){
						$this->salida .=" <option value=\"".$TiposAuto[$i][tipo_autorizacion]."\" selected>".$TiposAuto[$i][descripcion]."]</option>";
					}
					else{
						$this->salida .=" <option value=\"".$TiposAuto[$i][tipo_autorizacion]."\">".$TiposAuto[$i][descripcion]."</option>";
					}
		}
	}


	/**
	*
	*/
	function FormaAutorizacionDirecta()
	{
				$this->InsertarAutorizacionInicial();
      	$this->salida .= ThemeAbrirTabla('AUTORIZACION PACIENTE');
				$this->Encabezado();
				$this->salida .= "          <table width=\"90%\" align=\"center\" border=\"0\" cellpadding=\"3\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "          </table>";
				$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','InsertarAutorizacionDirecta');
				$this->salida .= "      <form name=\"forma\" action=\"$accion\" method=\"post\">";
				if(empty($_SESSION['SOLICITUDAUTORIZACION']['ingreso']))
				{
							$this->salida .= "    <br><table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
							$tipo_afiliado=$this->Tipo_Afiliado();
							$this->salida .= "		      <tr>";
							if(empty($TipoAfiliado))
							{ $TipoAfiliado=$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']['campo_tipo_afiliado']; }
							if(sizeof($tipo_afiliado)>1 && empty($_SESSION['SOLICITUDAUTORIZACION']['tipo_afiliado_id']))
							{
									$this->salida .= "				       <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
									$this->BuscarIdTipoAfiliado($tipo_afiliado,$TipoAfiliado);
									$this->salida .= "              </select></td>";
							}
							else
							{
									$this->salida .= "				    <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td>";
									$NomAfi=$this->NombreAfiliado($_SESSION['SOLICITUDAUTORIZACION']['tipo_afiliado_id']);
									$this->salida .= "	  	      <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$_SESSION['SOLICITUDAUTORIZACION']['tipo_afiliado_id']."\">".$NomAfi[tipo_afiliado_nombre]."</td>";
									$this->salida .= "	  	      <td></td>";
							}
							$niveles=$this->Niveles();
							if(empty($Nivel) && !empty($_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']['campo_nivel']))
							{ $Nivel=$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']['campo_nivel']; }
							elseif(empty($Nivel) && empty($_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']['campo_nivel']))
							{ $Nivel=$_REQUEST['Nivel']; }
							if(sizeof($niveles)>1 && empty($_SESSION['SOLICITUDAUTORIZACION']['rango']))
							{
								$this->salida .= "				       <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
								$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
								for($i=0; $i<sizeof($niveles); $i++)
								{
										if($niveles[$i][rango]==$Nivel){
											$this->salida .=" <option value=\"".$niveles[$i][rango]."\" selected>".$niveles[$i][rango]."</option>";
										}
										if($niveles[$i][rango]==$_SESSION['SOLICITUDAUTORIZACION']['RANGO'][$niveles[$i][rango]]){
											$this->salida .=" <option value=\"".$niveles[$i][rango]."\" selected>".$niveles[$i][rango]."</option>";
										}
										else{
												$this->salida .=" <option value=\"".$niveles[$i][rango]."\">".$niveles[$i][rango]."</option>";
										}
								}
							}
							else
							{
									$this->salida .= "				     <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
									$this->salida .= "	  	      <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$niveles[0][rango]."\">".$niveles[0][rango]."</td>";
									$this->salida .= "	  	      <td></td>";
							}
							$this->salida .= "	  	      <td class=\"".$this->SetStyle("Semanas")."\" width=\"23%\">SEMANAS COTIZADAS: </td>";
							if(!empty($_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']['campo_semanas_cotizadas']))
							{  $s=$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']['campo_semanas_cotizadas'];  }
							else
							{  $s=$_SESSION['SOLICITUDAUTORIZACION']['SEMANAS'][$_REQUEST['Semanas']];  }
							$this->salida .= "	  	      <td><input type=\"text\" name=\"Semanas\" size=\"8\" value=\"".$s."\"></td>";
							$this->salida .= "		      </tr>";
							$this->salida .= "			 </table><br>";
				}
				$this->CargosSolicitadosAutorizacion();
				//menua adicionar
				$this->salida .= "     <br><table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "      <tr align=\"center\" class=\"modulo_list_claro\">";
				$accionS=ModuloGetURL('app','Autorizacion_Solicitud','user','AdicionarServicio');
				$this->salida .= "	  	      <td><a href=\"$accionS\">ADICIONAR SERVICIOS</a></td>";
				$accionC=ModuloGetURL('app','Autorizacion_Solicitud','user','AdicionarCargo');
				$this->salida .= "	  	      <td><a href=\"$accionC\">ADICIONAR CARGOS</a></td>";
				$this->salida .= "		      </tr>";
				$this->salida .= "			 </table>";
				//TIPO AUTORIZACION
				$this->salida .= "     <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
				$this->salida .= "      <td width=\"33%\">SELECCIONE TIPO AUTORIZACION: </td>";
				$this->salida .= "      <td class=\"modulo_list_claro\"><select name=\"TipoAutorizacion\" class=\"select\">";
				$TiposAuto=$this->CallMetodoExterno('app','Autorizacion_Solicitud','user','TiposAuto');
				$this->BuscarTipoAutorizacion($TiposAuto,$_REQUEST['TipoAutorizacion']);
				$this->salida .= "      </select></td>";
				$accion=ModuloGetURL('app','Autorizacion','user','PedirAutorizacion');
				$this->salida .= "	    <td><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
				$this->salida .= "      </tr>";
				$this->salida .= "		 </table><BR>";
				//fecha de la autorizacion
				$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\" class=\"normal_10\">";
				$this->salida .= "      </tr>";
				$this->salida .= "	<td class=\"".$this->SetStyle("FechaAuto")."\">FECHA AUTORIZACION: </td>";
				if(!$FechaAuto){ $FechaAuto=date("d/m/Y"); }
				$this->salida .= "	<td><input type=\"text\" class=\"input-text\" name=\"FechaAuto\" size=\"12\" value=\"".$FechaAuto."\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
				$this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('forma','FechaAuto','/')."</td>";
				if(!$HoraAuto){ $HoraAuto=date('H'); }
				if(!$MinAuto){ $MinAuto=date('i'); }
				$this->salida .= "	<td class=\"".$this->SetStyle("HoraAuto")."\">HORA AUTORIZACION: </td>";
				$this->salida .= "	<td><input type=\"text\" class=\"input-text\" name=\"HoraAuto\" size=\"4\" value=\"".$HoraAuto."\" maxlength=\"2\">&nbsp;:&nbsp;<input type=\"text\" class=\"input-text\" name=\"MinAuto\" size=\"4\" value=\"".$MinAuto."\" maxlength=\"2\"></td>";
				$this->salida .= "      </tr>";
				$this->salida .= "		 </table>";
				//OBSERVACIONES
				$this->salida .= " <table border=\"0\" width=\"80%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
				$observacion=$this->Observaciones();
				//if(!empty($observacion))
				if($observacion!=' ' AND $observacion!='')
				{
						$this->salida .= "	<tr>";
						$this->salida .= "	<td  width=\"30%\" class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES DE LAS AUTORIZACION REALIZADAS: </td>";
						$this->salida .= "	<td><textarea  cols=\"80\" rows=\"4\" class=\"textarea\" name=\"ObservacionesT\" readonly>$observacion</textarea></td>";
						$this->salida .= "	</tr><br>";
				}
				$this->salida .= "	<tr>";
				$this->salida .= "	<td  width=\"30%\" class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES AUTORIZACION: </td>";
				$obs='';
				$this->salida .= "	<td><textarea  cols=\"80\" rows=\"4\" class=\"textarea\" name=\"ObservacionesA\">$obs".$_SESSION['AUTORIZACIONES']['ObservacionesA']."</textarea></td>";
				$this->salida .= "	</tr><br>";
				$this->salida .= "		 </table><BR>";
				//OBSERVACIONES INGRESO
				$this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td   width=\"30%\" class=\"".$this->SetStyle("ObservacionesI")."\" align=\"left\">OBSERVACIONES INGRESO:<br>( Esta observación será mostrada durante todo el manejo de toda la cuenta. ) </td>";
				$this->salida .= "	<td><textarea  cols=\"80\" rows=\"3\" class=\"textarea\" name=\"ObservacionesI\">".$_SESSION['AUTORIZACIONES']['ObservacionesI']."</textarea></td>";
				$this->salida .= "	</tr><br>";
				$this->salida .= "		 </table><BR>";
				//url protocolo
				if($_SESSION['SOLICITUDAUTORIZACION']['protocolo'])
				{
						if(file_exists("protocolos/".$_SESSION['SOLICITUDAUTORIZACION']['protocolo'].""))
						{
								$Protocolo=$_SESSION['SOLICITUDAUTORIZACION']['protocolo'];
								$this->salida .= "<script>";
								$this->salida .= "function Protocolo(valor){";
								$this->salida .= "window.open('protocolos/'+valor,'PROTOCOLO','');";
								$this->salida .= "}";
								$this->salida .= "</script>";
								$accion="javascript:Protocolo('$Protocolo')";
								$this->salida .= "			    <br><table width=\"40%\" align=\"center\" border=\"0\" class=\"normal_10\" cellpadding=\"3\">";
								$this->salida .= "             <tr class=\"modulo_list_claro\">";
								$this->salida .= "             		<td width=\"30%\" class=\"label\">PROTOCOLO</td>";
								$this->salida .= "             		<td><a href=\"$accion\">$Protocolo</a></td>";
								$this->salida .= "             </tr>";
								$this->salida .= "			      </table><br>";
						}
				}
				$this->salida .= "<br><table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"AUTORIZAR\"></td>";
				$this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"NoAutorizar\" value=\"NO AUTORIZAR\"></form></td>";
				$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarFormaBuscar');
				$this->salida .= "	<td align=\"center\"><form name=\"forma2\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></form></td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	</table>";
				$this->salida .= "      </form>";
       	$this->salida .= ThemeCerrarTabla();
				return true;
	}


//----------------------------------------------------------------------------------------------------

}//fin clase

?>

