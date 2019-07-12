<?php
/**
* Modulo de Listas de Trabajo para DATALAB(PHP).
*
* Modulo para el manejo de listas de trabajo en Interface con DATALAB
*
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: app_Os_ListaTrabajoDatalab_userclasses_HTML.php,v 1.2 2005/03/11 22:42:07 claudia Exp $
*/

class app_Os_ListaTrabajoDatalab_userclasses_HTML extends app_Os_ListaTrabajoDatalab_user
{
	//Constructor de la clase app_Os_ListaTrabajoDatalab_userclasses_HTML
	function app_Os_ListaTrabajoDatalab_userclasses_HTML()
	{
							$this->salida='';
							$this->app_Os_ListaTrabajoDatalab_user();
							return true;
	}

	//aoltu
	function SetStyle($campo)
	{
			if ($this->frmError[$campo] || $campo=="MensajeError")
			{
					if ($campo=="MensajeError")
					{
							$arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
							return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
			}
			return ("label");
	}

	/*
	* aoltu
	* Funcion donde se visualiza el encabezado de la empresa.
	* @return boolean
	*/
	function Encabezado()
		{
				$this->salida .= "<br><table  border=\"0\" class=\"modulo_table_list\" width=\"80%\" align=\"center\" >";
				$this->salida .= " <tr class=\"modulo_table_title\">";
				$this->salida .= " <td>EMPRESA</td>";
				$this->salida .= " <td>CENTRO UTILIDAD</td>";
				$this->salida .= " <td>DEPARTAMENTO</td>";
				$this->salida .= " </tr>";
				$this->salida .= " <tr align=\"center\">";
				$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['LTRABAJO_DATALAB']['NOM_EMP']."</td>";
				$this->salida .= " <td class=\"modulo_list_claro\">".$_SESSION['LTRABAJO_DATALAB']['NOM_CENTRO']."</td>";
				$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['LTRABAJO_DATALAB']['NOM_DPTO']."</td>";
				$this->salida .= " </tr>";
				$this->salida .= " </table>";
				return true;
		}


//aoltu
/**
* Se utilizada listar en el combo los diferentes tipo de identificacion de los pacientes
* @access private
* @return void
*/
	function BuscarIdPaciente($tipo_id,$TipoId='')
	{
			foreach($tipo_id as $value=>$titulo)
			{
					if($value==$TipoId)
					{
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}
					else
					{
							$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
			}
	}


	/*
	* Esta funcion realiza la busqueda de cumplimientos de ordenes de servicio
	* según filtros como tipo, documento, nombre y plan
	* @return boolean
	*/
	function FormaMetodoBuscar($arr)
	{
			$this->salida.= ThemeAbrirTabla('ORDEN DE LISTA DE TRABAJO CON DATALAB');
			$accion=ModuloGetURL('app','Os_ListaTrabajoDatalab','user','BuscarOrden');
			$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->Encabezado();

			if ($_SESSION['LTRABAJO_DATALAB']['MOSTRAR_LISTAS']==1)
			{
					if (!$_SESSION['IMAGENES']['LISTAS'])
				  {
							$_SESSION['IMAGENES']['LISTAS'] = $this->GetListasTrabajo();
					}

					if ($_SESSION['IMAGENES']['LISTAS'])
					{
							$this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
							$this->salida .= "<tr class=\"modulo_table_title\">";
							$this->salida .= "<td align = center colspan = 3 >LISTAS DE TRABAJO ASIGNADAS</td>";
							$this->salida .= "</tr>";

							//seleccionar todas las listas
							$this->salida .= "<SCRIPT>";
							$this->salida .= "function chequeoTotal(frm,x){";
							$this->salida .= "  if(x==true){";
							$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
							$this->salida .= "      if(frm.elements[i].type=='checkbox' && frm.elements[i].name != 'allfecha'){";
							$this->salida .= "        frm.elements[i].checked=true";
							$this->salida .= "      }";
							$this->salida .= "    }";
							$this->salida .= "  }else{";
							$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
							$this->salida .= "      if(frm.elements[i].type=='checkbox' && frm.elements[i].name != 'allfecha'){";
							$this->salida .= "        frm.elements[i].checked=false";
							$this->salida .= "      }";
							$this->salida .= "    }";
							$this->salida .= "  }";
							$this->salida .= "}";
							$this->salida .= "</SCRIPT>";
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida .= "<td colspan = 2 align = right width=\"15%\">SELECCIONAR TODAS</td>";
							$this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= 'AllListas' onclick=chequeoTotal(this.form,this.checked)></td>";
							$this->salida .= "</tr>";
							//fin de listas

							$this->salida .= "<tr class=\"modulo_table_list_title\">";
							$this->salida .= "<td align = center width=\"15%\">NUMERO DE LISTA</td>";
							$this->salida .= "<td align = center width=\"55%\">LISTA DE TRABAJO</td>";
							$this->salida .= "<td align = center width=\"10%\">OPCION</td>";
							$this->salida .= "</tr>";

							$x=$_REQUEST['op'];
							for ($i=0; $i<sizeof($_SESSION['IMAGENES']['LISTAS']);$i++)
							{
									$this->salida .= "<tr class=\"modulo_list_claro\" >";
									$this->salida .= "<td align = center>".$_SESSION['IMAGENES']['LISTAS'][$i]['tipo_os_lista_id']."</td>";
									$this->salida .= "<td align = left>".$_SESSION['IMAGENES']['LISTAS'][$i]['nombre_lista']."</td>";
									if($x[$i]==$_SESSION['IMAGENES']['LISTAS'][$i]['tipo_os_lista_id'])
									{
											$this->salida.="  <td align=\"center\"><input type = checkbox name= 'op[$i]' value = ".$_SESSION['IMAGENES']['LISTAS'][$i]['tipo_os_lista_id']." checked></td>";
									}
									else
									{
											$this->salida.="  <td align=\"center\"><input type = checkbox name= 'op[$i]' value = ".$_SESSION['IMAGENES']['LISTAS'][$i]['tipo_os_lista_id']."></td>";
									}
									$this->salida .= "</tr>";
							}
							$this->salida .= "</table>";
				  }
			}

			$this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .= "<tr class=\"modulo_table_title\">";
			$this->salida .= "<td align = center colspan = 2 >CUMPLIMIENTOS</td>";
			$this->salida .= "</tr>";

			$this->salida .= "<tr class=\"modulo_table_list_title\">";
			$this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
			$this->salida .= "<td align = left >SELECCIONE LA FECHA:</td>";
			$this->salida .= "</tr>";

			$this->salida .= "<tr class=\"modulo_list_claro\" >";
			$this->salida .= "<td width=\"40%\" >";
			$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .= "<tr><td>";
			$this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
			//$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					$this->salida .= "<SCRIPT>";
					$this->salida .= "function Revisar(frm,x){";
					$this->salida .= "  if(x==true){";
					$this->salida .= "frm.Fecha.value='TODAS LAS FECHAS'";
					$this->salida .= "  }";
					$this->salida .= "else{";
					$this->salida .= "frm.Fecha.value=''";
					$this->salida .= "}";
					$this->salida .= "}";
					$this->salida .= "</SCRIPT>";
			$this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
			$tipo_id=$this->tipo_id_paciente();
			$this->salida .=" <option value= -1 selected>Todos</option>";
			$this->BuscarIdPaciente($tipo_id,$_REQUEST['TipoDocumento']);
			$this->salida .= "</select></td></tr>";

			$this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";

			$this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\"></td></tr>";

			$this->salida .= "<tr><td class=\"label\">FECHA</td><td><input type=\"text\" readonly class=\"input-text\" name=\"Fecha\" value = ".$_REQUEST['DiaEspe']."></td></tr>";


			$this->salida .= "<tr class=\"label\">";
			$this->salida .= "<td align = left >TODAS LAS FECHAS</td>";
			$this->salida.="  <td align=\"left\"><input type = checkbox name= 'allfecha' onclick=Revisar(this.form,this.checked)></td>";
			$this->salida .= "</tr>";



	    //filtro de pacientes
			$this->salida .= "<tr><td class=\"label\">PACIENTES: </td><td><select name=\"opcion_pacientes\" class=\"select\">";

			$this->salida .=" <option value= 1 selected>Pacientes sin Atender</option>";
			if ($_REQUEST['opcion_pacientes']==2)
			{
			$this->salida .=" <option value= 2 selected>Pacientes Atendidos - Solicitados Datalab</option>";
			}
			else
			{
				$this->salida .=" <option value= 2 >Pacientes Atendidos - Solicitados Datalab</option>";
			}
			if ($_REQUEST['opcion_pacientes']==3)
			{
			$this->salida .=" <option value= 3 selected>Todos los Pacientes</option>";
			}
			else
			{
				$this->salida .=" <option value= 3 >Todos los Pacientes</option>";
			}
			$this->salida .= "</select></td></tr>";
			//fin de filtros



			$this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
			$this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar_Orden_Cargar_Session\" value=\"BUSCAR\"></td>";
			$this->salida .= "</form>";

			$actionM=ModuloGetURL('app','Os_ListaTrabajoDatalab','user','main');
			$this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
			$this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
			$this->salida .= "</tr>";

			$this->salida .= "</table></td></tr>";

			$this->salida .= "</td></tr></table>";

			$this->salida .= "</table>";
			$this->salida .= "</td>";

			$this->salida .= "<td>";

			$this->salida .= "<BR><table border=\"0\" width=\"80%\" align=\"center\">";
//aqui inserte lo de lorena
			$this->salida .= "<tr><td>";

			//$_REQUEST['DiaEspe'];

			$this->salida.="\n".'<script>'."\n";
			$this->salida.='function year1(t)'."\n";
			$this->salida.='{'."\n";
			$this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
			foreach($_REQUEST as $v=>$v1)
			{
					if($v!='year' and $v!='meses' and $v!='DiaEspe')
					{
							if (is_array($v1)) {
											foreach($v1 as $k2=>$v2) {
													if (is_array($v2)) {
															foreach($v2 as $k3=>$v3) {
																	if (is_array($v3)) {
																			foreach($v3 as $k4=>$v4) {
																					$this->salida .= "&$v" . "[$k2][$k3][$k4]=$v4";
																			}
																	}else{
																			$this->salida .= "&$v" . "[$k2][$k3]=$v3";
																	}
															}
													}else{
															$this->salida .= "&$v" . "[$k2]=$v2";
													}
											}
									} else {
											$this->salida .= "&$v=$v1";
									}
					}
			}
			$this->salida.='";'."\n";
			$this->salida.='}'."\n";
			$this->salida.='</script>';

			$this->salida .='<form name="cosa">';
			$this->salida .="<table border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .='<tr align="center">';
			$this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
			if(empty($_REQUEST['year']))
			{
					$_REQUEST['year']=date("Y");
					$this->AnosAgenda(True,$_REQUEST['year']);
			}
			else
			{
					$this->AnosAgenda(true,$_REQUEST['year']);
					$year=$_REQUEST['year'];
			}
			$this->salida .= "</select></td>";
			$this->salida .="<td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
			if(empty($_REQUEST['meses']))
			{
					$mes=$_REQUEST['meses']=date("m");
					$this->MesesAgenda(True,$year,$mes);
			}
			else
			{
					$this->MesesAgenda(True,$year,$_REQUEST['meses']);
					$mes=$_REQUEST['meses'];
			}
			$this->salida .= "</select>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .='</form>';
			$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioEstandard');
			$this->salida .= "   </td></tr>";

			$this->salida .= "<tr class=\"modulo_table_list_title\">";
			$this->salida .= "<td>";

			$this->salida .= "</td>";
			$this->salida .= "</tr>";
/**************************************/
			$this->salida .= "</table>";

			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";

			if(!empty($arr))
			{
					$this->salida.= "<table border=\"0\" align=\"center\"  width=\"80%\">";
					$this->salida.= $this->SetStyle("MensajeError");
					$this->salida.= "</table><br>";
					$this->salida.= "<br><table width=\"80%\" border=\"0\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\" >";


	        //codigo para pintar en el resultado de la busqueda el filtro utilizado.
					$texto = '';
					if ($_REQUEST['opcion_pacientes'] == 1)
					{
						$texto = 'PACIENTES SIN ATENDER';
					}
					if ($_REQUEST['opcion_pacientes'] == 2)
					{
						$texto = 'PACIENTES ATENDIDOS - SOLICITADOS A DATALAB';
					}
					if ($_REQUEST['opcion_pacientes'] == 3)
					{
						$texto = 'TODOS LOS PACIENTES';
					}
					if ($texto != '')
					{
						$this->salida .= "<tr class=\"modulo_table_title\">";
						$this->salida.="<td colspan=6 align=\"center\">FILTRO DE BUSQUEDA: ".$texto."</td>";
						$this->salida.="</tr><br>";
					}
					//fin del pintado del filtro


					$this->salida.= "<tr align=\"center\" class=\"modulo_table_list_title\">";
					$this->salida.= "<td width=\"10%\">FECHA DEL CUMPLIMIENTO</td>";
					$this->salida.= "<td width=\"10%\">No. CUMPLIMIENTO</td>";
					$this->salida.= "<td width=\"10%\">SERVICIO</td>";
					$this->salida.= "<td width=\"5%\">IDENTIFICACION</td>";
					$this->salida.= "<td width=\"35%\">NOMBRE DEL PACIENTE</td>";
					$this->salida.= "<td width=\"10%\">OPCION</td>";
					$this->salida.= "</tr>";

					for($i=0;$i<sizeof($arr);$i++)
					{
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}

							//Edad
							$edad_paciente = CalcularEdad($arr[$i][fecha_nacimiento],date("Y-m-d"));
							$this->salida.="<tr class='$estilo' align='center'>";
							$this->salida.="  <td >".$arr[$i][fecha_cumplimiento]."</td>";
							$this->salida.="  <td ><font color='#4D6EAB'>".$arr[$i][numero_cumplimiento]." </font></td>";
							//$this->salida.="  <td ><font color='#4D6EAB'>".$arr[$i][numero_cumplimiento]." &nbsp; - &nbsp;".$arr[$i][numero_orden_id]."</font></td>";
							$this->salida.="  <td >".$arr[$i][servicio_descripcion]."</td>";
							$this->salida.="  <td >".$arr[$i][tipo_id_paciente]." - ".$arr[$i][paciente_id]."</td>";
							$this->salida.="  <td >".$arr[$i][nombre]."</td>";
							//$this->salida.="  <td >".$arr[$i][plan_descripcion]."</td>";
							$this->salida .= "<td width=\"10%\"><a href=".ModuloGetURL('app','Os_ListaTrabajoDatalab','user','GetForma',array('numero_cumplimiento'=>$arr[$i][numero_cumplimiento], 'fecha_cumplimiento'=>$arr[$i][fecha_cumplimiento], 'departamento'=>$arr[$i][departamento], 'tipo_id_paciente'=>$arr[$i][tipo_id_paciente],'paciente_id'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre], 'edad_paciente'=>$edad_paciente[edad_aprox]))."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;VER1</a></td>";
							$this->salida.="</tr>";
					}
					$this->salida.="</table>";
					$this->salida .=$this->RetornarBarra();
			}
			else
			{
				$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida.="</table><br>";
			}
			$this->salida .= ThemeCerrarTabla();
	return true;
	}


function Consultar_Cumplimiento($numero_cumplimiento, $fecha_cumplimiento, $departamento, $tipo_id_paciente, $paciente_id,$nombre, $edad_paciente)
{
    $this->salida= ThemeAbrirTablaSubModulo('CONSULTA DE CUMPLIMIENTOS');
		$accion=ModuloGetURL('app','Os_ListaTrabajoDatalab','user','UpdateDatos',array('numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$tipo_id_paciente, 'paciente_id'=>$paciente_id, 'nombre'=>$nombre,'edad_paciente'=>$edad_paciente));
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";

		$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"1\">ID DEL PACIENTE</td>";
		$this->salida.="  <td align=\"center\" colspan=\"2\">NOMBRE DEL PACIENTE</td>";
		$this->salida.="  <td align=\"center\" colspan=\"1\">EDAD</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"1\">".$paciente_id."</td>";
		$this->salida.="  <td align=\"center\" colspan=\"2\">".$nombre."</td>";
		$this->salida.="  <td align=\"center\" colspan=\"1\">".$edad_paciente."</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td align=\"left\" width=\"20%\">FECHA DE CUMPLIMIENTO: </td>";
		$this->salida.="  <td align=\"center\" width=\"10%\">".$fecha_cumplimiento."</td>";
		$this->salida.="  <td align=\"left\" width=\"20%\">NUMERO DE CUMPLIMIENTO: </td>";
		$this->salida.="  <td align=\"center\" width=\"10%\">".$numero_cumplimiento."</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";

		$vector = $this->ConsultaOrdenesPaciente($numero_cumplimiento, $fecha_cumplimiento, $departamento);
		if (sizeof($vector[0][diagnosticos])>0 OR sizeof($vector[0][diagnosticos_ingreso])>0)
		{
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td align=\"left\">DIAGNOSTICOS</td>";
				$this->salida.="</tr>";
				for($i=0;$i<sizeof($vector[0][diagnosticos]);$i++)
				{
						if( $i % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="  <td align=\"left\">".$vector[0][diagnosticos][$i][diagnostico_id]." - ".$vector[0][diagnosticos][$i][diagnostico_nombre]."</td>";
						$this->salida.="</tr>";
				}

				for($i=0;$i<sizeof($vector[0][diagnosticos_ingreso]);$i++)
				{
						if( $i % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="  <td align=\"left\">".$vector[0][diagnosticos_ingreso][$i][diagnostico_id]." - ".$vector[0][diagnosticos_ingreso][$i][diagnostico_nombre]."</td>";
						$this->salida.="</tr>";
				}

				$this->salida.="</table>";
		}

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		if ($_SESSION['LTRABAJO_DATALAB']['MOSTRAR_LISTAS'] != '1')
	  {
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td colspan = 7 align=\"center\" width=\"80%\">APOYOS DIAGNOSTICOS</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">NUM.</td>";
			$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">ORDEN DE SERVICIO</td>";
			$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">ITEM DE LA ORDEN</td>";
			$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">CARGO</td>";
			$this->salida.="  <td colspan = 1 align=\"center\" width=\"40%\">DESCRIPCION</td>";
			$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">DATALAB</td>";
			$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">OPCION</td>";
			$this->salida.="</tr>";
		}

		for($i=0;$i<sizeof($vector);$i++)
		{
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}

				if ($vector[$i][nombre_lista] != $vector[$i-1][nombre_lista])
				{
						$this->salida.="<tr class=\"modulo_table_title\">";
						$this->salida.="  <td colspan = 7 align=\"center\" width=\"10%\">".$vector[$i][nombre_lista]."</td>";
						$this->salida.="</tr>";
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">NUM.</td>";
						$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">ORDEN DE SERVICIO</td>";
						$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">ITEM DE LA ORDEN</td>";
						$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">CARGO</td>";
						$this->salida.="  <td colspan = 1 align=\"center\" width=\"34%\">DESCRIPCION</td>";
						$this->salida.="  <td colspan = 1 align=\"center\" width=\"13%\">DATALAB</td>";
						$this->salida.="  <td colspan = 1 align=\"center\" width=\"13%\">OPCION</td>";
						$this->salida.="</tr>";
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">".$numero_cumplimiento." - ".($i+1)."</td>";
				$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">".$vector[$i][orden_servicio_id]."</td>";
				$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">".$vector[$i][numero_orden_id]."</td>";
				$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">".$vector[$i][cargo]."</td>";
				$this->salida.="  <td colspan = 1 align=\"left\" width=\"34%\">".$vector[$i][descripcion]."</td>";

        if($vector[$i][sw_estado]=='1')
				{
          $this->salida.="  <td align=\"center\" width=\"13%\">".$vector[$i][codigo_datalab]."</td>";
					$imagen='checkS.gif';
					$this->salida .= "<td align=\"center\" width=\"13%\"><img src=\"". GetThemePath() ."/images/$imagen\" width=\"15\" border=\"0\" height=\"15\">&nbsp;&nbsp;TOMADO  Y ENVIADO A DATALAB</td>";
				}
				else
        {
						$spia = 1;
						$codigos=$this->Cargar_Codigos_Datalab($vector[$i][cargo]);
						if (sizeof($codigos) > 1)
						{
							$this->salida.="  <td colspan = 1 align=\"left\" width=\"13%\"><select name=\"codigo".$vector[$i][numero_orden_id]."\" class=\"select\">";
							$this->salida .=" <option value= '-1' selected>Seleccione</option>";
							$this->LLenarComboCodigos($codigos,$_REQUEST['codigo']);
							$this->salida .= "</select></td>";
						}
						elseif (sizeof($codigos) == 1)
						{
							$this->salida.="  <td colspan = 1 align=\"center\" width=\"13%\"><input readonly type=\"text\" class=\"input-text\" name=\"codigo".$vector[$i][numero_orden_id]."\" size = 15  value = ".$codigos[0][codigo_datalab]."></td>";
						}
						else
						{
							$this->salida.="  <td colspan = 1 align=\"center\" width=\"13%\">SIN EQUIVALENCIA</td>";
							$spia=0;
						}
						if($spia==1)
					  {
						  $this->salida.="  <td align=\"center\" width=\"13%\"><input type = checkbox name= 'op"."[$i]' value = ".$vector[$i][numero_orden_id]."></td>";
					  }
						else
						{
              $this->salida.="  <td align=\"center\" width=\"13%\"></td>";
						}
				}
				$this->salida.="</tr>";

				if ($vector[$i][orden_servicio_id] != $vector[$i+1][orden_servicio_id])
				{
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida .= "<td align=\"right\"  width=\"13%\" colspan=\"7\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
					$this->salida.="</tr>";
				}
		}

		$this->salida.="</table>";
    $this->salida .= "</form>";


		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"$estilo\">";
		//BOTON DE VOLVER
		//$accionV=ModuloGetURL('app','Os_ListaTrabajoDatalab','user','FormaMetodoBuscar');
		$accionV=ModuloGetURL('app','Os_ListaTrabajoDatalab','user','BuscarOrden');
		$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<td  colspan = 2 align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"DATOS ALMACENADOS\"></form></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
}

/*
* Esta funcion calcula el numero de pasos que saldran en la barra de navegación.
* @return boolean
*/
function CalcularNumeroPasos($conteo)
{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
}

/*
* Esta funcion calcula la barra de navegación.
* @return boolean
*/
function CalcularBarra($paso)
{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
				$barra=$barra-10;
		}
		return $barra;
}

/*
* Esta funcion calcula los segmentos en que se desplaza el apuntador de los registros
* de la base de datos.
* @return boolean
*/
function CalcularOffset($paso)
{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
}


/*
* Esta funcion integra (CalcularNumeroPasos,CalcularOffset,CalcularBarra), para asi
* crear una barra de navegacion, para los registros.
* @return boolean
*/
	function RetornarBarra()
	{
			//$this->conteo;
			//$this->limit;

			if($this->limit>=$this->conteo)
			{
					return '';
			}
			$paso=$_REQUEST['paso'];
			if(is_null($paso))
			{
			    $paso=1;
			}
	    $vec='';
			foreach($_REQUEST as $v=>$v1)
			{
					if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
					{
					  $vec[$v]=$v1;
					}
			}
			$accion=ModuloGetURL('app','Os_ListaTrabajoDatalab','user','BuscarOrden',$vec);
			$barra=$this->CalcularBarra($paso);
			$numpasos=$this->CalcularNumeroPasos($this->conteo);
			$colspan=1;

			$this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
			if($paso > 1)
			{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
					$colspan+=1;
			}
			else
			{
	// $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
		//$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
	    }
			$barra ++;
			if(($barra+10)<=$numpasos)
			{
					for($i=($barra);$i<($barra+10);$i++)
					{
							if($paso==$i)
							{
											$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
							}
							else
							{
											$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
							}
							$colspan++;
					}
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
		      $colspan+=2;
			}
			else
			{
		      $diferencia=$numpasos-9;
					if($diferencia<=0){$diferencia=1;}//cambiar en todas las barra
					for($i=($diferencia);$i<=$numpasos;$i++)
					{
							if($paso==$i)
							{
									$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
							}
							else
							{
									$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
							}
							$colspan++;
					}
					if($paso!=$numpasos)
					{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
							$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
							$colspan++;
					}
					else
					{
		// $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
			//$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
		      }
		  }
			if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
			{
					if($numpasos>10)
					{
							$valor=10+3;
					}
					else
					{
							$valor=$numpasos+3;
					}
					$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
			}
			else
			{
					if($numpasos>10)
					{
							$valor=10+5;
					}
					else
					{
							$valor=$numpasos+5;
					}
			    $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
			}
			//}
	}


//FUNCIONES QUE ACOMPAÑAN AL CALENDARIO
/**
* Funcion que Saca los años para el calendario a partir del año actual
* @return array
*/
function AnosAgenda($Seleccionado='False',$ano)
{
		$anoActual=date("Y");
		//$ano = $anoActual;
		$anoActual1=$anoActual-10;
    for($i=0;$i<=20;$i++)
		{
	      $vars[$i]=$anoActual1;
				$anoActual1=$anoActual1+1;
		}
		switch($Seleccionado)
		{
				case 'False':
				{
						foreach($vars as $value=>$titulo)
						{
			          if($titulo==$ano)
								{
									$this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
							  }
								else
								{
				          $this->salida .=" <option value=\"$titulo\">$titulo</option>";
							  }
						}
						break;
			  }
				case 'True':
						{
							foreach($vars as $value=>$titulo)
							{
									if($titulo==$ano)
									{
									  $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
								  }
									else
									{
									  $this->salida .=" <option value=\"$titulo\">$titulo</option>";
									}
							}
							break;
					  }
		}
}

function MesesAgenda($Seleccionado='False',$Año,$Defecto)
{
		$anoActual=date("Y");
		$vars[1]='ENERO';
		$vars[2]='FEBRERO';
		$vars[3]='MARZO';
		$vars[4]='ABRIL';
		$vars[5]='MAYO';
		$vars[6]='JUNIO';
		$vars[7]='JULIO';
		$vars[8]='AGOSTO';
		$vars[9]='SEPTIEMBRE';
		$vars[10]='OCTUBRE';
		$vars[11]='NOVIEMBRE';
		$vars[12]='DICIEMBRE';
		//$mesActual=date("m");
		switch($Seleccionado)
		{
				case 'False':
				{
					if($anoActual==$Año)
						{
						foreach($vars as $value=>$titulo)
								{
								if($value>=$mesActual)
										{
											if($value==$Defecto)
												{
														$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
												}else{
														$this->salida .=" <option value=\"$value\">$titulo</option>";
												}
										}
								}
						}
						else
						{
			foreach($vars as $value=>$titulo)
								{
										if($value==$Defecto)
										{
												$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
										}else{
																$this->salida .=" <option value=\"$value\">$titulo</option>";
										}
								}
						}
						break;
				}
				case 'True':
				{
					if($anoActual==$Año)
						{
							foreach($vars as $value=>$titulo)
								{
									if($value>=$mesActual)
										{

													if($value==$Defecto)
														{
																$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
														}else
														{
																$this->salida .=" <option value=\"$value\">$titulo</option>";
														}
												}
										}
								}
								else
								{
					foreach($vars as $value=>$titulo)
										{
												if($value==$Defecto)
												{
														$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
												}else
												{
														$this->salida .=" <option value=\"$value\">$titulo</option>";
												}
										}
								}
								break;
						}
				}
		}



//NUEVA FUNCIONES

function LLenarComboCodigos($codigos,$cod='')
{
		for($i=0; $i<sizeof($codigos); $i++)
		{
		    $valor = $codigos[$i][codigo_datalab];
				if($codigos[$i][codigo_datalab]==$cod)
				{
						$this->salida .=" <option value=\"$valor\" selected>".$valor."</option>";
				}
				else
				{
						$this->salida .=" <option value=\"$valor\">".$valor."</option>";
				}
		}
}
}//fin clase

?>
