<?
/**
* Submodulo de Apoyos Diagnosticos.
*
* Submodulo para manejar los apoyos diagnosticos, permite la captura de resultados de los examenes, 
* y la lectura por parte del profesional.
* @author Luis Alejandro Vargas
* @version 1.0
* @package SIIS
* $Id: hc_Apoyos_Diagnosticos_Control_APDControl_HTML.class.php,v 1.1 2009/07/30 12:38:06 johanna Exp $
*/
IncludeFile("classes/ApoyosDiagnosticos/ApoyosDiagnosticos_HTML.class.php");
 
class APDControl_HTML
{
	function APDControl_HTML()
	{
		$this->frmPrefijo=$_SESSION['frmprefijo'];	
		$this->datosPaciente=$_SESSION['datospaciente'];
		$this->ingreso=$_SESSION['ingreso'];
		$this->evolucion=$_SESSION['evolucion'];
		$this->paso=$_SESSION['paso'];
		
		return true;
	}
	
	/**
	* Funcion que señaliza una palabra para simbolizar que esta en estado de alerta
	* @return boolean
	*/
  function SetStyle($campo)
	{
		if ($this->frmError[$campo] || $campo=="MensajeError")
		{
			if ($campo=="MensajeError")
			{
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}
	
	/*
	* Esta funcion le permite al usuario seleccionar el tipo de
	* tecnica que usara para la transcripcion del examen
	* @return boolean
	*/
	function frmSeleccion_Tecnica($multitecnica,$evolucion1)
  {
		$pfj=$this->frmPrefijo;
		$this->salida.="<script lenguage='javascript' src='../../javascripts/cross-browser/x/x_core.js'></script>";
		$this->salida.="<div id=\"seleccion_tecnica\">";

		$this->salida.= ThemeAbrirTablaSubModulo('SELECCION DE TECNICA','90%');
		
		$this->salida.="<form name=\"formades$pfj\" action=\"\" method=\"post\">";
		$this->salida.="	<table  align=\"center\" border=\"0\" width=\"80%\">";
		$this->salida.="		<tr class=\"modulo_table_title\">";
		$this->salida.="  		<td align=\"center\">ID DEL PACIENTE</td>";
		$this->salida.="  		<td align=\"center\">NOMBRE DEL PACIENTE</td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr class=\"modulo_list_oscuro\">";
		$this->salida.="  		<td align=\"center\">".$this->datosPaciente['tipo_id_paciente'].": ".$this->datosPaciente['paciente_id']."</td>";
		$this->salida.="  		<td align=\"center\">".$this->datosPaciente['primer_nombre']." ".$this->datosPaciente['segundo_nombre']." ".$this->datosPaciente['primer_apellido']." ".$this->datosPaciente['segundo_apellido']."</td>";
		$this->salida.="		</tr>";
		$this->salida.="	</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="	<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  	<td align=\"center\" colspan=\"1\">SELECCIONE LA TECNICA PARA EL EXAMEN</td>";
		$this->salida.="	</tr>";
		$this->salida.="	<tr class=\"hc_table_submodulo_list_title\">";

		$this->salida.="		<td width=\"35%\" align = \"center\" >";
		$this->salida.="			<select size = \"1\" name = \"selector_multitecnica$pfj\"  class =\"select\">";
		
		for($i=0;$i<sizeof($multitecnica);$i++)
		{
			if ($multitecnica[$i][sw_predeterminado] != '1')
			{
				$this->salida.="<option value = \"".$multitecnica[$i][tecnica_id]."\">".$multitecnica[$i][nombre_tecnica]."</option>";
			}
			else
			{
				$this->salida.="<option value = \"".$multitecnica[$i][tecnica_id]."\" selected >".$multitecnica[$i][nombre_tecnica]."</option>";
			}
		}
		$this->salida.="</select>";
		
		$this->salida.="		</td>";
		$this->salida.="	</tr>";
		$this->salida.= "	<tr>";
		$this->salida.= "		<td align=\"center\"><br><input class=\"input-submit\" name=\"siguiente$pfj\" type=\"button\" value=\"SIGUIENTE\" onclick=\"EnviarTecnica(document.formades$pfj,$evolucion1)\"></td>";
		$this->salida.="	</tr>";
		$this->salida.="	</table>";
		$this->salida.="</form>";

		$this->salida .= ThemeCerrarTablaSubModulo();
		$this->salida.="</div>";
		$this->salida.="<script>";
		$this->salida.=" var ele=document.getElementById('seleccion_tecnica');";
		$this->salida.=" window.resizeTo(700,xHeight(ele)+30);";
		$this->salida.="</script>";
		
		return $this->salida;
  }

	function frmCrearFormaE($cargo,$descripcion,$tecnica,$periodo,$vector,$trans,$evolucion1)
	{
		$pfj=$this->frmPrefijo;
		$this->salida.="<script lenguage='javascript' src='../../javascripts/cross-browser/x/x_core.js'></script>";
		$this->salida.="<div id=\"captura\">";

		$this->salida.=ThemeAbrirTablaSubModulo('CAPTURA DE RESULTADOS INDIVIDUALES','80%');
		
		$this->salida.="<form name=\"formaexamen$pfj\" action=\"$action\" method=\"post\">";
		
		if($this->ban==1)
		{
			$this->salida.="	<table  align=\"center\" border=\"0\"  width=\"95%\">";
			$this->salida.= 		$this->SetStyle("MensajeError");
			$this->salida.="	</table>";
		}
		
		$this->salida.="<script language=\"javascript\">";
		$this->salida.="	function LlamarCalendariofecha_realizadofrm_AtencionCPN()";
		$this->salida.="	{";
		$this->salida.="		window.open('../../classes/calendariopropio/Calendario.php?forma=formaexamen$pfj&campo=fecha_realizado$pfj&separador=-','CALENDARIO_SIIS','width=450,height=250,resizable=no,status=no,scrollbars=yes');";
		$this->salida.="	}";
		$this->salida.="</script>";
		
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"95%\">";
		$this->salida.="	<tr class=\"modulo_table_title\">";
		$this->salida.="		<td align=\"center\" width=\"5%\">CARGO</td>";
		$this->salida.="		<td align=\"center\" width=\"60%\">EXAMEN</td>";
		$this->salida.="		<td align=\"center\" width=\"23%\">FECHA</td>";
		$this->salida.="	</tr>";
		
		if (empty($_REQUEST['fecha_realizado'.$pfj]))
		{
			$_REQUEST['fecha_realizado'.$pfj] = date('d-m-Y');
		}
		$this->salida.="	<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="		<td align=\"center\">".$cargo."</td>";
		$this->salida.="		<td align=\"center\">".strtoupper($descripcion)."</td>";
		
		//$this->salida.="		<td align=\"center\"><input type=\"text\" class=\"input-text\" size=\"10\" maxlength=\"10\" value = \"".$_REQUEST['fecha_realizado'.$pfj]."\" name=\"fecha_realizado$pfj\" readonly><sub>".ReturnOpenCalendario("formaexamen".$pfj,"fecha_realizado".$pfj,"-")."</sub></td>";
		$this->salida.="		<td align=\"center\"><input type=\"text\" class=\"input-text\" size=\"8\" maxlength=\"10\" value = \"".$_REQUEST['fecha_realizado'.$pfj]."\" name=\"fecha_realizado$pfj\" readonly>
												<sub><a href=\"javascript:LlamarCalendariofecha_realizado$pfj()\"><img onMouseOver=\"window.status='Calendario';return true;\" onMouseOut=\"window.status=''; return true;\" src=\"../../themes/HTML/AzulXp/images/calendario/calendario.png\" border=\"0\"></a>[dd-mm-aaaa]</sub></td>";
		
		$this->salida.="	</tr>";
		$this->salida.="</table>";
		if($vector)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"95%\">";
			$indmin=1;
			$e=0;
			$k=0;
      $b=0;
			
			if($b%2==0)
			{
				$estilo='hc_submodulo_list_claro';
			}
			else
			{
				$estilo='hc_submodulo_list_oscuro';
			}

			for($i=0;$i<sizeof($vector);$i++)
			{
				switch ($vector[$i][lab_plantilla_id])
				{
					case "1": 
					{
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
						$this->salida.="<td width=\"30%\" align=\"center\">RESULTADO</td>";
						$this->salida.="<td width=\"10%\" align=\"center\">V.MIN</td>";
						$this->salida.="<td width=\"10%\" align=\"center\">V.MAX</td>";
						$this->salida.="<td width=\"10%\" align=\"center\">UND</td>";
						$this->salida.="<td width=\"5%\"  align=\"center\">PAT.</td>";
						$this->salida.="</tr>";

						if(is_null($vector[$i]['rango_min']) || $vector[$i]['rango_min'] == '0')
						{
								$vector[$i]['rango_min'] = 0;
						}
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td width=\"35%\" align=\"left\"  class=\"".$this->SetStyle("resultado$k$e$pfj")."\">".strtoupper($vector[$i][nombre_examen])."</td>";
						$this->salida.="<td width=\"30%\" align=\"center\"><input type=\"text\" name = \"resultado$k$e$pfj\" class=\"input-text-center\" value =\"".$_REQUEST['resultado'.$k.$e.$pfj]."\">&nbsp;".$vector[$i][unidades_1]."</td>";
						$this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"rmin$k$e$pfj\" class=\"input-text-center\" size=\"10\"   value=\"".$vector[$i][rango_min]."\"></td>";
						$this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"rmax$k$e$pfj\" class=\"input-text-center\" size=\"10\"   value=\"".$vector[$i][rango_max]."\"></td>";
						$this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"unidades$k$e$pfj\" class=\"input-text-center\" size=\"10\"   value=\"".$vector[$i][unidades_1]."\"></td>";
						
						if ($_REQUEST['sw_patologico'.$k.$e.$pfj] == '1')
						{
								$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
						}
						else
						{
								$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
						}
						$this->salida.="</tr>";

						$this->salida.="  <input type=\"hidden\" name = \"lab_examen$k$e$pfj\"  value=\"".$vector[$i][lab_examen_id]."\">";
						$e++;
						break;
					}
					case "2": 
					{
						if ($indmin == 1)
						{
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
							$this->salida.="<td width=\"40%\" align=\"center\" colspan = \"2\">RESULTADO</td>";
							$this->salida.="<td width=\"20%\" align=\"center\" colspan = \"2\">UND</td>";
							$this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
							$this->salida.="</tr>";
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida.="<td align=\"left\" width=\"35%\" class=".$this->SetStyle("resultado$k$e$pfj").">".strtoupper($vector[$i][nombre_examen])."</td>";
							$this->salida.="<td align=\"center\" width=\"40%\" colspan = \"2\">";

							$this->salida.="<select size = \"1\" name = \"resultado$k$e$pfj\"  class =\"select\">";
							$this->salida.="<option value = \"-1\" >--Seleccione--</option>";
							if($_REQUEST['resultado'.$k.$e.$pfj]==$vector[$i]['opcion'])
							{
									$this->salida.="<option value = \"".$vector[$i]['opcion']."\" selected>".$vector[$i][opcion]."</option>";
							}
							else
							{
									$this->salida.="<option value = \"".$vector[$i]['opcion']."\" >".$vector[$i][opcion]."</option>";
							}
							$indmin++;
						}
						else
						{
							if($_REQUEST['resultado'.$k.$e.$pfj]==$vector[$i]['opcion'])
							{
									$this->salida.="<option value = \"".$vector[$i]['opcion']."\" selected>".$vector[$i][opcion]."</option>";
							}
							else
							{
									$this->salida.="<option value = \"".$vector[$i]['opcion']."\" >".$vector[$i][opcion]."</option>";
							}
						}
						if($vector[$i]['lab_examen_id']!=$vector[$i+1]['lab_examen_id'])
						{
							$this->salida.="</select>";
							$this->salida.="</td>";
							$this->salida.="<td width=\"20%\" align=\"center\" colspan = \"2\"><input type=\"text\" class=\"input-text-center\" name=\"unidades$k$e$pfj\"  size=\"10\"   value=\"".$vector[$i][unidades_2]."\"></td>";
							if ($_REQUEST['sw_patologico'.$k.$e.$pfj] == '1')
							{
									$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
							}
							else
							{
									$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
							}
							$this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e$pfj\"  value=\"".$vector[$i][lab_examen_id]."\">";
							$this->salida.="</tr>";
							$indmin=1;
							$e++;
						}
						break;
					}
					case "3": 
					{
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="<td width=\"25%\" align=\"center\">SUBEXAMEN</td>";
						$this->salida.="<td width=\"70%\" align=\"center\" colspan = \"3\" class=".$this->SetStyle("resultado$k$e$pfj").">".strtoupper($vector[$i][nombre_examen])."</td>";
						$this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
						$this->salida.="</tr>";

						$this->salida.="<tr class=\"$estilo\">";
						if($_REQUEST['resultado'.$k.$e.$pfj]==='' OR !empty($_REQUEST['resultado'.$k.$e.$pfj]))
						{
							$this->salida .= "<td colspan = \"4\" align=\"center\" width=\"60%\">";
							$this->salida .= getFckeditor("resultado".$k.$e.$pfj,'200',"100%",$_REQUEST['resultado'.$k.$e.$pfj]);
							$this->salida .= "</td>";
						}
						else
						{
							$this->salida .= "<td colspan = \"4\" align=\"center\" width=\"60%\">";
							$this->salida .= getFckeditor("resultado".$k.$e.$pfj,'200',"100%",$vector[$i][detalle]);
							$this->salida .= "</td>";
						}
						if($_REQUEST['sw_patologico'.$k.$e.$pfj]=='1')
						{
							$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
						}
						else
						{
							$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
						}

						$this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e$pfj\"  value=\"".$vector[$i][lab_examen_id]."\">";
						$this->salida.="</tr>";
						$e++;
						break;
					}
					
					case "0": 
					{
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="<td width=\"20%\" align=\"center\">SUBEXAMEN</td>";
						$this->salida.="<td class=\"$estilo\" width=\"70%\" align=\"center\" class=\"".$this->SetStyle("resultado")."\">".strtoupper($vector[$i][nombre_examen])."</td>";
						$this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
						
						if ($_REQUEST['sw_patologico'.$k.$e.$pfj] == '1')
						{
							$this->salida.="<td class=\"$estilo\" width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e$pfj\" value=\"1\" checked></td>";
						}
						else
						{
							$this->salida.="<td class=\"$estilo\" width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
						}
						$this->salida.="</tr>";
						
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="	<td width=\"100%\" align=\"center\" colspan = \"4\">RESULTADO</td>";
						$this->salida.="</tr>";
						
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida .= "<td colspan = \"4\" align=\"center\" width=\"100%\">";
						$this->salida .= getFckeditor("resultado".$k.$e.$pfj,'120',"100%",$_REQUEST['resultado'.$k.$e.$pfj]);
						$this->salida .= "</td>";
						$this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e$pfj\"  value=\"".$vector[$i][lab_examen_id]."\">";
						$this->salida.="</tr>";
						$e++;
						
						break;
					}
				}
				$b++;
			}
			$this->salida.="</table>";
		}
		
		$items = $e;
		$this->salida.="  <input type=\"hidden\" name = \"items$k$pfj\"  value=\"$items\">";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"95%\">";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td align=\"center\" width=\"45%\">OBSERVACION DEL MEDICO</td>";
		$this->salida.="<td align=\"center\" width=\"45%\">OBSERVACION DEL PRESTADOR DEL SERVICIO</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="<td rowspan =\"3\" align=\"center\" width=\"45%\" valign=\"top\"><textarea style = \"width:90%\" class=\"textarea\" name = \"observacion_medico$pfj\" rows=\"4\">".$_REQUEST['observacion_medico'.$pfj]."</textarea></td>" ;
		$this->salida.="<td align=\"center\" width=\"45%\" valign=\"top\"><textarea style = \"width:90%\" class=\"textarea\" name = \"observacion$pfj\" rows=\"4\">".$_REQUEST['observacion'.$pfj]."</textarea></td>" ;
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="<td align=\"center\" width=\"45%\"><label class=\"label\">LABORATORIO: </label><input type=\"text\" name = \"laboratorio$pfj\" value =\"".$_REQUEST['laboratorio'.$pfj]."\"></td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="<td align=\"center\" width=\"45%\"><label class=\"label\">PROFESIONAL: </label><input type=\"text\" name = \"profesional$pfj\" value =\"".$_REQUEST['profesional'.$pfj]."\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";

		$this->salida.="<table align=\"center\" width=\"30%\" border=\"0\">";
		$this->salida .= "<tr>";
		
		$tipo_id_paciente=$this->datosPaciente['tipo_id_paciente'];
		$paciente_id=$this->datosPaciente['paciente_id'];
		
		$datos="new Array('$cargo','$tecnica','$tipo_id_paciente','$paciente_id','".$evolucion1."','$trans','$periodo')";
		$this->salida .= "<td colspan = \"2\" align=\"center\" valign=\"top\"><input class=\"input-submit\" name=\"Insertar$pfj\" type=\"button\" value=\"INSERTAR\" onclick=\"EnviarDatosT(document.formaexamen$pfj,$datos)\"></td>";
		$this->salida .= "</form>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";

		$this->salida .= ThemeCerrarTablaSubModulo();
		$this->salida .="</div>";
		$this->salida .="<script>";
		$this->salida .=" var ele=document.getElementById('captura');";
		$this->salida .=" window.resizeTo(700,xHeight(ele)+30);";
		$this->salida .="</script>";
		return $this->salida;
	}

	function Consulta_Resultados($resultado_id, $sw_modo_resultado,$examenes,$registro,$vector,$observaciones,$evolucion_id='')
	{
		$pfj=$this->frmPrefijo;

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$this->salida.="<script lenguage='javascript' src='../../javascripts/cross-browser/x/x_core.js'></script>";
		$this->salida.="<div id=\"ver_examen\">";
		$this->salida.= $this->Plantilla_Apoyos($resultado_id, $sw_modo_resultado,$examenes,$registro,$vector,$observaciones,$evolucion_id);
		$this->salida.="</div>";
		$this->salida.="<script>";
		$this->salida.=" var ele=document.getElementById('ver_examen');";
		$this->salida.=" window.resizeTo(700,xHeight(ele)+40);";
		$this->salida.="</script>";
		return $this->salida;
	}
	
	//inicio función plantillas
	function Plantilla_Apoyos($resultado_id, $sw_modo_resultado,$examenes,$registro,$vector,$observaciones,$evolucion_id)
	{
		$prof = 0;
		for($k=0;$k<sizeof($registro);$k++)
		{
				if ($registro[$k][sw_prof] == '1'){	$prof = 1;}
		}
		//fin de verificacion
		$salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$salida.="	<tr>";
		$salida.="		<td align=\"center\" width=\"100%\" class=\"hc_table_submodulo_list_title\">".$examenes[descripcion]."</td>";
		$salida.="		<td align=\"center\" width=\"100%\" class=\"hc_table_submodulo_list_title\">".$examenes[fecha_realizado]."</td>";
		$salida.="	</tr>";
		$salida.="</table>";

		if($vector)
		{
			$salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			for($i=0;$i<sizeof($vector);$i++)
			{ 
				switch ($vector[$i][lab_plantilla_id])
				{
					case "1": 
					{
						$salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$salida.="<td width=\"25%\">EXAMEN</td>";
						$salida.="<td width=\"55%\">RESULTADO</td>";
						$salida.="<td width=\"20%\">RANGO NORMAL</td>";
						$salida.="</tr>";

						$salida.="<tr class=\"modulo_list_claro\">";
						$salida.="<td align=\"center\" >".strtoupper($vector[$i][nombre_examen])."</td>";
						if ($vector[$i][sw_alerta] == '1')
						{
								$salida.="<td class=label_error align=\"center\">".$vector[$i][resultado]." ".$vector[$i][unidades]."</td>";
						}
						else
						{
								$salida.="<td align=\"center\">".$vector[$i][resultado]." ".$vector[$i][unidades]."</td>";
						}
						$salida.="<td align=\"center\" >".$vector[$i][rango_min]." - ".$vector[$i][rango_max]." ".$vector[$i][unidades]."</td>";
						$salida.="</tr>";
						break;
					}

					case "2": 
					{
						$salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$salida.="<td width=\"25%\">EXAMEN</td>";
						$salida.="<td width=\"55%\">RESULTADO</td>";
						$salida.="<td width=\"20%\">RANGO NORMAL</td>";
						$salida.="</tr>";
						$salida.="<tr class=\"modulo_list_claro\">";
						$salida.="<td align=\"center\" >".strtoupper($vector[$i][nombre_examen])."</td>";
						$salida.="<td align=\"center\" >".$vector[$i][resultado]."</td>";
						$salida.="<td align=\"center\">&nbsp;</td>";
						$salida.="</tr>";
						break;
					}
					
					case "3": 
					{
						$salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$salida.="  <td colspan=\"1\" width=\"25%\">EXAMEN</td>";
						$salida.="  <td colspan=\"2\" align=\"center\" width=\"75%\">".strtoupper($vector[$i][nombre_examen])."</td>";
						$salida.="</tr>";
						$salida.="<tr class=\"hc_submodulo_list_claro\">";
						$vector[$i][resultado]=str_replace("\x0a","<p></p>",$vector[$i][resultado]);
						$salida.="  <td colspan=\"3\" align=\"justify\" width=\"100%\">".$vector[$i][resultado]."</td>";
						$salida.="</tr>";
						break;
					}
	
					case "0": 
					{
						$salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$salida.="<td width=\"25%\" colspan=\"1\">EXAMEN</td>";
						$salida.="<td width=\"75%\" colspan=\"2\">RESULTADO</td>";
						$salida.="</tr>";
						$salida.="<tr class=\"hc_submodulo_list_claro\">";
						$salida.="<td align=\"center\" >".strtoupper($vector[$i][nombre_examen])."</td>";
						$salida.="<td align=\"center\" colspan=\"2\">".$vector[$i][resultado]."</td>";
						$salida.="</tr>";
						break;
					}	
				}//cierra el switche
			}//cierra el forierra el for

			if ($examenes[informacion]!= '' OR $examenes[observacion_prestacion_servicio]!= ''
			OR (!empty($observaciones)) OR (sizeof($examenes[observaciones_adicionales])>=1))
			{
					$salida.="<tr class=\"$estilo\">";
					$salida.="	<td colspan=\"3\">";
					$salida.="		<table  align=\"center\" border=\"0\"  width=\"100%\">";
					if ($examenes[informacion])
					{
						$salida.="<tr class=\"modulo_list_claro\" >";
						$salida.="<td width=\"25%\" align=\"left\" class=\"hc_table_submodulo_list_title\">INFORMACION: </td>";
						$salida.="<td colspan=\"2\" width=\"75%\" align=\"left\" class=\"hc_submodulo_list_oscuro\"><FONT size='1'>".$examenes[informacion]."</FONT></td>";
						$salida.="</tr>";
					}

					if ($examenes[observacion_prestacion_servicio])
					{
						$salida.="<tr>";
						$salida.="<td width=\"25%\" align=\"left\" class=\"hc_table_submodulo_list_title\">OBSERVACION</td>";
						$salida.="<td colspan=\"2\" width=\"75%\" align=\"left\" class=\"hc_submodulo_list_oscuro\">".$examenes[observacion_prestacion_servicio]."</td>";
						$salida.="</tr>";
					}

					//listado de las observaciones adicionales al resultado
					if(sizeof($examenes[observaciones_adicionales])>=1)
					{
							$salida.="<tr>";
							$salida.="<td align=\"left\" width=\"25%\" class=\"hc_table_submodulo_list_title\">OBSERVACIONES ADICIONALES REALIZADAS AL RESULTADO</TH>";
							$salida.="<td align=\"left\" colspan=\"2\" width=\"75%\" class=\"hc_submodulo_list_oscuro\">";
							$salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
							$salida.="<tr>";
							$salida.="	<td align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"5%\">No.</td>";
							$salida.="	<td align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"10%\">REGISTRO</td>";
							$salida.="	<td align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"30%\">PROFESIONAL</td>";
							$salida.="	<td align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"55%\">OBSERVACION ADICIONAL AL RESULTADO</td>";
							$salida.="</tr>";
							for($i=0;$i<sizeof($examenes[observaciones_adicionales]);$i++)
							{
									if( $i % 2)
									{
										$estilo='modulo_list_claro';
									}
									else
									{
										$estilo='modulo_list_oscuro';
									}
									$salida.="<tr>";
									$salida.="<td align=\"center\" class=\"$estilo\" >".($i+1)."</td>";
									$salida.="<td align=\"center\" class=\"$estilo\" >".$this->FechaStampMostrar($examenes[observaciones_adicionales][$i][fecha_registro_observacion])." - ".$this->HoraStamp($examenes[observaciones_adicionales][$i][fecha_registro_observacion])."</td>";
									$salida.="<td align=\"center\" class=\"$estilo\" >".$examenes[observaciones_adicionales][$i][usuario_observacion]."</td>";
									$salida.="<td align=\"left\" class=\"$estilo\" >".$examenes[observaciones_adicionales][$i][observacion_adicional]."</td>";
									$salida.="</tr>";
							}
							$salida.="</table>";
							$salida.="</td>";
							$salida.="</tr>";
					}
					//fin de las observaciones adicionales

					if ($observaciones)
					{
						$salida.="<tr>";
						$salida.="	<td width=\"25%\" align=\"left\" class=\"hc_table_submodulo_list_title\">OBSERVACIONES MEDICAS</TH>";
						$salida.="	<td colspan=\"2\" align=\"left\" width=\"75%\" class=\"hc_submodulo_list_claro\" >";
						$salida.="	<table  align=\"center\" border=\"0\"  width=\"100%\">";
						for($i=0;$i<sizeof($observaciones);$i++)
						{
								$salida.="<tr>";
								$salida.="<td align=\"left\" class=\"hc_table_submodulo_list_title\" >".$observaciones[$i][descripcion]." - ".$observaciones[$i][nombre]."</td>";
								$salida.="</tr>";

								$salida.="<tr>";
								$salida.="<td align=\"left\" class=\"modulo_list_claro\" >".$observaciones[$i][observacion_prof]."</td>";
								$salida.="</tr>";
						}
						$salida.="</table>";
						$salida.="</td>";
						$salida.="</tr>";
					}
					$salida.="</table>";
					$salida.="</td>";
					$salida.="</tr>";
			}
			$salida.="<tr>";
			$salida.="<td align=\"left\" class=\"hc_table_submodulo_list_title\">LABORATORIO</td>";
			$salida.="<td align=\"left\" colspan=\"2\" class=\"hc_submodulo_list_claro\">".$examenes[laboratorio]."</td>";
			$salida.="</tr>";
			$salida.="<tr>";
			$salida.="<td align=\"left\" class=\"hc_table_submodulo_list_title\">PROFESIONAL</td>";
			$salida.="<td align=\"left\" colspan=\"2\" class=\"hc_submodulo_list_oscuro\">".$examenes[profesional]."</td>";
			$salida.="</tr>";
			
			$salida.="</table>";
		}
		return $salida;
	}//fin de la funcion Plantilla_Apoyos
	
}
?>