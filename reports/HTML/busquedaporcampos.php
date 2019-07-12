<?php
	$_ROOT='../../';
	$VISTA='HTML';
	include $_ROOT.'includes/enviroment.inc.php';
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	IncludeFile($fileName);

	$_REQUEST['Nombres']=strtoupper($_REQUEST['Nombres']);
	$_REQUEST['Apellidos']=strtoupper($_REQUEST['Apellidos']);
	print(ReturnHeader('DATOS AFILIADO'));
	print(ReturnBody());
	echo "<script>\n";
	echo "function BajarDatos(plan, tipodoc, documento, noAutorizacion)\n";
	echo "{\n";
	if(!empty($_REQUEST['forma']))
	{
		echo "	window.opener.document.".$_REQUEST['forma'].".Documento.value=documento;\n";
	}
	else
	{
		echo "	window.opener.document.formabuscar.Documento.value=documento;\n";
	}
	if(!empty($_REQUEST['forma']))
	{
		echo "	window.opener.document.".$_REQUEST['forma'].".Responsable.value=plan;\n";
	}
	else
	{
		echo "	window.opener.document.formabuscar.Responsable.value=plan;\n";
	}
	if(!empty($_REQUEST['forma']))
	{
		echo "	window.opener.document.".$_REQUEST['forma'].".TipoDocumento.value=tipodoc;\n";
	}
	else
	{
		echo "	window.opener.document.formabuscar.TipoDocumento.value=tipodoc;\n";
	}
	if(!empty($_REQUEST['forma']))
	{
		echo "if(window.opener.document.".$_REQUEST['forma'].".NoAutorizacion!=undefined)\n";
	}
	else
	{
		echo "if(window.opener.document.formabuscar.NoAutorizacion!=undefined)\n";
	}
	echo "{\n";
	echo "if(noAutorizacion!=undefined)\n";
	echo "{\n";
	if(!empty($_REQUEST['forma']))
	{
		echo "window.opener.document.".$_REQUEST['forma'].".NoAutorizacion.value=noAutorizacion;\n";
	}
	else
	{
		echo "window.opener.document.formabuscar.NoAutorizacion.value=noAutorizacion;\n";
	}
	echo "}\n";
	echo "else\n";
	echo "{\n";
	if(!empty($_REQUEST['forma']))
	{
		echo "window.opener.document.".$_REQUEST['forma'].".NoAutorizacion.value='0';\n";
	}
	else
	{
		echo "window.opener.document.formabuscar.NoAutorizacion.value='0';\n";
	}
	echo "}\n";
	echo "}\n";
	echo "	window.close();\n";
	echo "}\n";
	
	
	
	echo "function BuscarDatos(a)\n";
	echo "{\n";
	//echo "alert('".$_ROOT."reports/$VISTA/busquedaporcampos.php?Responsable='+a+'&departamento='+".$_REQUEST['departamento'].");\n";
	//echo "document.location='".$_ROOT."reports/$VISTA/busquedaporcampos.php?Responsable='+a+'&departamento='+".$_REQUEST['departamento'].";\n";
	echo "document.formabuscar.submit();\n";
	echo "}\n";
	
	
	
	
	echo "</script>\n";
	echo ThemeAbrirTabla('DATOS PACIENTE');
	echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
	echo "<form name=\"formabuscar\" action=\"\" method=\"post\">";
	echo "<input type='hidden' value='".$_REQUEST['departamento']."' name='departamento'>";
	echo "				       <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
	list($dbconn) = GetDBconn();
	$query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
	$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			else{
					if($result->EOF){
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
							return false;
					}
							while (!$result->EOF) {
									$tipo_id[$result->fields[0]]=$result->fields[1];
									$result->MoveNext();
							}
			}

	foreach($tipo_id as $value=>$titulo)
	{
			if($value==$_REQUEST['TipoDocumento'])
			{  echo " <option value=\"$value\" selected>$titulo</option>";  }
			else
			{  echo " <option value=\"$value\">$titulo</option>";  }
	}
	echo "              </select></td></tr>";
	echo "<tr>";
	if($_REQUEST['Responsable']==-1)
	{
		echo "<td class=\"label_error\">";
	}
	else
	{
		echo "<td class=\"label\">";
	}
	$query="SELECT plan_id,plan_descripcion,tercero_id,tipo_tercero_id, num_contrato FROM planes WHERE fecha_final >= now() and estado=1 and fecha_inicio <= now() and (sw_tipo_plan=3 or (sw_tipo_plan=0 and sw_afiliacion=1)) order by plan_descripcion;";
	echo "PLAN: </td><td><select name=\"Responsable\" class=\"select\" onchange='BuscarDatos(this.value)'>";
	$result = $dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
	}

	while (!$result->EOF) {
					$responsables[]=$result->GetRowAssoc($ToUpper = false);
					$responsables1[$result->fields[0]]=$result->fields[4];
					$result->MoveNext();
	}
	$result->Close();
	list($dbconn) = GetDBconn();
	echo " <option value=\"-1\">-------SELECCIONE-------</option>";
			for($i=0; $i<sizeof($responsables); $i++)
			{
					if($responsables[$i][plan_id]==$_REQUEST['Responsable']){
							echo " <option value=\"".$responsables[$i][plan_id]."\" selected>".$responsables[$i][plan_descripcion]."</option>";
					}else{
							echo " <option value=\"".$responsables[$i][plan_id]."\">".$responsables[$i][plan_descripcion]."</option>";
					}
			}

	echo "              </select></td></tr>";
	//print_r($responsables1);
	echo "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_REQUEST['Documento']."\"></td></tr>";
	echo "<tr><td class=\"label\">NOMBRES: </td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\" value=\"".$_REQUEST['Nombres']."\"></td></tr>";
	echo "<tr><td class=\"label\">APELLIDOS: </td><td><input type=\"text\" class=\"input-text\" name=\"Apellidos\" maxlength=\"64\" value=\"".$_REQUEST['Apellidos']."\"></td></tr>";
	echo "<tr><td class=\"label\">COTIZANTE: </td><td><input type=\"text\" class=\"input-text\" name=\"Cotizante\" maxlength=\"32\" value=\"".$_REQUEST['Cotizante']."\"></td></tr>";
	if($_REQUEST['Responsable']!=-1)
	{
		if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
		{
				echo "Error";
				echo "No se pudo incluir : classes/notas_enfermeria/revision_sistemas.class.php";
		}
		if(!class_exists('BDAfiliados'))
		{
				echo "Error";
				echo "no existe BDAfiliados";
		}
		$class=New BDAfiliados('','',$_REQUEST['Responsable']);
		$result=$class->TraerSedes($_REQUEST['departamento']);
		if(!empty($result))
		{
			echo "<tr><td class=\"label\">OFICINAS: </td><td>";
			echo "<select class=\"select\" name=\"Prestador\">";
			foreach($result as $k=>$v)
			{
				if(!empty($_REQUEST['Prestador']))
				{
					if($_REQUEST['Prestador']==$k)
					{
						echo "<option value='".$k."' selected>".$v['descripcion']."</option>";
					}
					else
					{
						echo "<option value='".$k."'>".$v['descripcion']."</option>";
					}
				}
				else
				{
					if($v['activo']==1)
					{
						echo "<option value='".$k."' selected>".$v['descripcion']."</option>";
					}
					else
					{
						echo "<option value='".$k."'>".$v['descripcion']."</option>";
					}
				}
			}
			echo "</select>";
			echo "</td></tr>";
		}
	}
	echo "<tr><td class=\"label\">NUMERO AUTORIZACION: </td><td><input type=\"text\" class=\"input-text\" name=\"NoAutorizacion\" maxlength=\"32\" value=\"".$_REQUEST['NoAutorizacion']."\"></td></tr>";
	echo "<tr><td align=\"center\" colspan=\"2\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"><br></td></form>";
	echo "</tr>";
	echo "			     </table>";
		if((!empty($_REQUEST['Documento']) or !empty($_REQUEST['Nombres']) or !empty($_REQUEST['Apellidos']) or !empty($_REQUEST['Cotizante']) or !empty($_REQUEST['NoAutorizacion'])) and ($_REQUEST['Responsable']!=-1))
		{
			if(!empty($_REQUEST['Documento']))
			{
				$class= New BDAfiliados($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable']);
				if($class->GetDatosAfiliado()===false)
				{
						echo "<table align=center><tr align=center><td><label class=label_error>".$class->error."</label></td></tr>";
						echo "<tr align=center><td><label class=label_error>".$class->mensajeDeError."</label></td></tr></table>";
				}
				
				$x=$class->salida;
				unset($class);
				if(!empty($x))
				{
					echo "<table width=\"100%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
					echo "<tr class=\"modulo_table_list_title\">";
					echo "<td colspan=\"2\" align=\"center\">Tipo Documento</td>";
					echo "<td colspan=\"2\" align=\"center\">Documento</td>";
					echo "<td colspan=\"2\" align=\"center\">Nombre</td>";
					echo "<td colspan=\"2\" align=\"center\">Estado</td>";
					echo "<td colspan=\"2\" align=\"center\">Plan</td>";
					echo "<td colspan=\"2\" align=\"center\">Accion</td>";
					echo "</tr>";
					echo "<tr class=\"modulo_list_oscuro\">";
					echo "<td colspan=\"2\" align=\"center\">".$x['campo_tipodocumento']."</td>";
					echo "<td colspan=\"2\" align=\"center\">".$x['campo_documento']."</td>";
					if(empty($x['campo_nombre_completo']))
					{
						echo "<td colspan=\"2\" align=\"center\">".$x['campo_Primer_nombre'].' '.$x['campo_Segundo_nombre'].' '.$x['campo_Primer_apellido'].' '.$x['campo_Segundo_apellido']."</td>";
					}
					else
					{
						echo "<td colspan=\"2\" align=\"center\">".$x['campo_nombre_completo']."</td>";
					}
					echo "<td colspan=\"2\" align=\"center\">".$x['campo_estado_bd']."</td>";
					echo "<td colspan=\"2\" align=\"center\">".$x['campo_descripcion_plan']."</td>";
					echo "<td colspan=\"2\" align=\"center\"><a href='javascript:BajarDatos(\"".$_REQUEST['Responsable']."\",\"".trim($x['campo_tipodocumento'])."\",\"".trim($x['campo_documento'])."\",\"".trim($_REQUEST['NoAutorizacion'])."\");'>Elegir</a></td>";
					echo "</tr>";
					echo "</table>";
				}
			}
			else
			{
				if(!empty($_REQUEST['Cotizante']))
				{
					$class= New BDAfiliados('','',$_REQUEST['Responsable']);
					$result=$class->BusquedaCotizantePaciente($_REQUEST['TipoDocumento'],$_REQUEST['Cotizante']);
					if($result==false)
					{
							echo "<table align=center><tr align=center><td><label class=label_error>".$class->error."</label></td></tr>";
							echo "<tr align=center><td><label class=label_error>".$class->mensajeDeError."</label></td></tr></table>";
							echo ThemeCerrarTabla();
							print(ReturnFooter());
							return true;
					}
					if(!$result->EOF)
					{
						echo "<table width=\"100%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
						echo "<tr class=\"modulo_table_list_title\">";
						echo "<td colspan=\"2\" align=\"center\">Tipo Documento</td>";
						echo "<td colspan=\"2\" align=\"center\">Documento</td>";
						echo "<td colspan=\"2\" align=\"center\">Nombre</td>";
						echo "<td colspan=\"2\" align=\"center\">Estado</td>";
						echo "<td colspan=\"2\" align=\"center\">Plan</td>";
						echo "<td colspan=\"2\" align=\"center\">Accion</td>";
						echo "</tr>";
						$spy=0;
						while(!$result->EOF)
						{
							$x=$result->GetRowAssoc(false);
							if($spy==0)
							{
								echo "<tr class=\"modulo_list_oscuro\">";
								$spy=1;
							}
							else
							{
								echo "<tr class=\"modulo_list_claro\">";
								$spy=0;
							}
							echo "<td colspan=\"2\" align=\"center\">".$x['cdgo_tpo_idntfccn']."</td>";
							echo "<td colspan=\"2\" align=\"center\">".$x['nmro_idntfccn']."</td>";
							echo "<td colspan=\"2\" align=\"center\">".$x['nmbre_afldo']."</td>";
							echo "<td colspan=\"2\" align=\"center\">".$x['estdo']."</td>";
							echo "<td colspan=\"2\" align=\"center\">".$x['dscrpcn_pln']."</td>";
							$responsable=array_search($x[cnsctvo_cdgo_pln],$responsables1);
							if($responsable===false)
							{
								$responsable="-1";
							}
							echo "<td colspan=\"2\" align=\"center\"><a href='javascript:BajarDatos(\"".$responsable."\",\"".trim($x['cdgo_tpo_idntfccn'])."\",\"".trim($x['nmro_idntfccn'])."\",\"".trim($_REQUEST['NoAutorizacion'])."\");'>Elegir</a></td>";
							echo "</tr>";
							$result->MoveNext();
						}
						$result->close();
						echo "				       </tr>";
						echo "			     </table>";
					}
					else
					{
						echo "<table align=center><tr align=center><td><label class=label_error>NO EXISTE NINGUN COTIZANTE</label></td></tr>";
						echo "<tr align=center><td><label class=label_error>TIPO DOCUMENTO= ".$_REQUEST['TipoDocumento']." Y DOCUMENTO=".$_REQUEST['Cotizante']."</label></td></tr></table>";
					}
				}
				else
				{
					if(!empty($_REQUEST['NoAutorizacion']))
					{
						$class= New BDAfiliados('','',$_REQUEST['Responsable']);
						$result=$class->BusquedaNumeroAutorizacion($_REQUEST['NoAutorizacion'],$_REQUEST['Prestador']);
						if($result==false)
						{
								echo "<table align=center><tr align=center><td><label class=label_error>".$class->error."</label></td></tr>";
								echo "<tr align=center><td><label class=label_error>".$class->mensajeDeError."</label></td></tr></table>";
								echo ThemeCerrarTabla();
								print(ReturnFooter());
								return true;
						}
						if(!$result->EOF)
						{
							echo "<table width=\"100%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
							echo "<tr class=\"modulo_table_list_title\">";
							echo "<td colspan=\"2\" align=\"center\">Tipo Documento</td>";
							echo "<td colspan=\"2\" align=\"center\">Documento</td>";
							echo "<td colspan=\"2\" align=\"center\">Nombre</td>";
							echo "<td colspan=\"2\" align=\"center\">Estado</td>";
							echo "<td colspan=\"2\" align=\"center\">Accion</td>";
							echo "</tr>";
							$spy=0;
							while(!$result->EOF)
							{
								$x=$result->GetRowAssoc(false);
								if($spy==0)
								{
									echo "<tr class=\"modulo_list_oscuro\">";
									$spy=1;
								}
								else
								{
									echo "<tr class=\"modulo_list_claro\">";
									$spy=0;
								}
								echo "<td colspan=\"2\" align=\"center\">".$x['cdgo_tpo_idntfccn']."</td>";
								echo "<td colspan=\"2\" align=\"center\">".$x['nmro_idntfccn']."</td>";
								echo "<td colspan=\"2\" align=\"center\">".$x['nmbre']."</td>";
								echo "<td colspan=\"2\" align=\"center\">".$x['dscrpcn_estdo_drcho']."</td>";
								echo "<td colspan=\"2\" align=\"center\"><a href='javascript:BajarDatos(\"".$_REQUEST['Responsable']."\",\"".trim($x['cdgo_tpo_idntfccn'])."\",\"".trim($x['nmro_idntfccn'])."\",\"".trim($_REQUEST['NoAutorizacion'])."\");'>Elegir</a></td>";
								echo "</tr>";
								$result->MoveNext();
							}
							$result->close();
							echo "				       </tr>";
							echo "			     </table>";
						}
						else
						{
							echo "<table align=center><tr align=center><td><label class=label_error>NO EXISTE NINGUNA AUTORIZACION DE NUMERO=".$_REQUEST['NoAutorizacion']." EN EL OFICINA=".$_REQUEST['Prestador']."</label></td></tr></table>";
						}
					}
					else
					{
						if(!empty($_REQUEST['Nombres']) or !empty($_REQUEST['Apellidos']))
						{
							$class= New BDAfiliados('','',$_REQUEST['Responsable']);
							$result=$class->BusquedaNombresPaciente($_REQUEST['Nombres'],$_REQUEST['Apellidos']);
							if($result===false)
							{
									echo "<table align=center><tr align=center><td><label class=label_error>".$class->error."</label></td></tr>";
									echo "<tr align=center><td><label class=label_error>".$class->mensajeDeError."</label></td></tr></table>";
									echo ThemeCerrarTabla();
									print(ReturnFooter());
									return true;
							}
							if(!empty($result))
							{
								echo "<table width=\"100%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
								echo "<tr class=\"modulo_table_list_title\">";
								echo "<td colspan=\"2\" align=\"center\">Tipo Documento</td>";
								echo "<td colspan=\"2\" align=\"center\">Documento</td>";
								echo "<td colspan=\"2\" align=\"center\">Nombre</td>";
								echo "<td colspan=\"2\" align=\"center\">Estado</td>";
								if(!empty($result[0]['plan']))
								{
									echo "<td colspan=\"2\" align=\"center\">Plan</td>";
								}
								echo "<td colspan=\"2\" align=\"center\">Accion</td>";
								echo "</tr>";
								$spy=0;
								foreach($result as $a=>$x)
								{
									if($spy==0)
									{
										echo "<tr class=\"modulo_list_oscuro\">";
										$spy=1;
									}
									else
									{
										echo "<tr class=\"modulo_list_claro\">";
										$spy=0;
									}
									echo "<td colspan=\"2\" align=\"center\">".$x['tipodocumento']."</td>";
									echo "<td colspan=\"2\" align=\"center\">".$x['documento']."</td>";
									echo "<td colspan=\"2\" align=\"center\">".$x['nombre']."</td>";
									echo "<td colspan=\"2\" align=\"center\">".$x['estado']."</td>";
									if(!empty($result[0]['plan']))
									{
										echo "<td colspan=\"2\" align=\"center\">".$x['plan']."</td>";
									}
									echo "<td colspan=\"2\" align=\"center\"><a href='javascript:BajarDatos(\"".$_REQUEST['Responsable']."\",\"".trim($x['tipodocumento'])."\",\"".trim($x['documento'])."\",\"".trim($_REQUEST['NoAutorizacion'])."\");'>Elegir</a></td>";
									echo "</tr>";
								}
								unset($result);
								echo "				       </tr>";
								echo "			     </table>";
							}
							else
							{
								echo "<table align=center><tr align=center><td><label class=label_error>NO EXISTE NINGUN PACIENTE</label></td></tr>";
								echo "<tr align=center><td><label class=label_error>CON NOMBRE=".$_REQUEST['Nombres']." O APELLIDO=".$_REQUEST['Apellidos']."</label></td></tr></table>";
							}
						}
					}
				}
			}
		}



	echo ThemeCerrarTabla();
	print(ReturnFooter());
	?>
