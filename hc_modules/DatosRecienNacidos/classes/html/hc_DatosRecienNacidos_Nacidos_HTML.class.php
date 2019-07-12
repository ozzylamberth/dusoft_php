<?php
	/********************************************************************************* 
 	* $Id: hc_DatosRecienNacidos_Nacidos_HTML.class.php,v 1.1 2006/12/07 21:14:10 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_InscripcionCPN_Inscripcion_HTML
	* 
 	**********************************************************************************/
	
	class Nacidos_HTML
	{

		function Nacidos_HTML()
		{
			return true;
		}
		
		function frmHistoria()
		{
			$this->salida="";
			return $this->salida;
		}
		
		function frmConsulta()
		{
			return true;
		}

		function frmForma($num_hijos)
		{
			
			$pfj=SessionGetVar("Prefijo");
			$evolucion=SessionGetVar("Evolucion");
			$paso=SessionGetVar("Paso");
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			$this->salida.= ThemeAbrirTablaSubModulo('REGISTRO DE DATOS DEL RECIEN NACIDO');
			
			if($this->ban==1)
			{
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "      </table><br>";
				if($this->req==1)
				{
					$_REQUEST=null;
				}
			}
			
			$this->salida .= "<script>";
			$this->salida .= "	function ActivarRadios(frm,x){";
			$this->salida .= "  		if(x==true){";
			$this->salida .= "    			for(i=0;i<frm.elements.length;i++){";
			$this->salida .= "      			if(frm.elements[i].type=='radio' &&  frm.elements[i].name == 'muerte_materno$pfj'){";
			$this->salida .= "        				frm.elements[i].disabled=false;";
			$this->salida .= "				}";
			$this->salida .= "    			}";
			$this->salida .= " 			}else{";
			$this->salida .= "    			for(i=0;i<frm.elements.length;i++){";
			$this->salida .= "      			if(frm.elements[i].type=='radio' &&  frm.elements[i].name == 'muerte_materno$pfj'){";
			$this->salida .= "        				frm.elements[i].disabled=true;";
			$this->salida .= "      			}";
			$this->salida .= "    			}";
			$this->salida .= "  		}";
			$this->salida .= "	}";
			$this->salida .= "</script>";
			
			$accion=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'DatosRecienNacidos'));

			$this->salida.="<form name=\"formanacidos$pfj\" action=\"$accion\" method=\"post\">";
			$this->salida.="	<table align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td align=\"center\" colspan=\"4\">DATOS RECIEN NACIDOS</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_oscuro\" align=\"left\">";
			$this->salida.="			<td><label class=\"label\">N. HISTORIA CLINICA RN</label></td>";
			$this->salida.="			<td><input type=\"text\" class=\"input-text\" name=\"num_hc$pfj\" value=\"".$datosPaciente['paciente_id']."-".$num_hijos."\" maxlength=\"20\" size=\"20\" readonly></td>";
			$this->salida.="			<td><label class=\"".$this->SetStyle("nom_rn")."\">NOMBRE RECIEN NACIDO</label></td>";	
			$this->salida.="			<td><input type=\"text\" class=\"input-text\" name=\"nom_rn$pfj\" value=\"".$_REQUEST['nom_rn'.$pfj]."\" maxlength=\"50\" size=\"50\"></td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_claro\" align=\"left\">";
			$this->salida.="			<td><label class=\"".$this->SetStyle("nom_madre")."\">NOMBRE DE LA MADRE</label></td>";
			$nombre_madre=trim($datosPaciente['primer_nombre']," ")." ".trim($datosPaciente['segundo_nombre']," ")." ".trim($datosPaciente['primer_apellido']," ")." ".trim($datosPaciente['segundo_apellido']," ");
			$this->salida.="			<td><input type=\"text\" class=\"input-text\" name=\"nom_madre$pfj\" value=\"".$nombre_madre."\" maxlength=\"100\" size=\"50\"></td>";
			$this->salida.="			<td><label class=\"".$this->SetStyle("nom_padre")."\">NOMBRE DEL PADRE</label></td>";
			$this->salida.="			<td><input type=\"text\" class=\"input-text\" name=\"nom_padre$pfj\" value=\"".$_REQUEST['nom_padre'.$pfj]."\" maxlength=\"50\" size=\"50\"></td>";
			$this->salida.="		</tr>";
			$this->salida.="	</table>";
			$this->salida.="	</br>";
			$this->salida.="	<table align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\" align=\"center\">";
			$this->salida.="			<td width=\"25%\">SEXO</td>";
			$this->salida.="			<td width=\"25%\">ESTADO RN</td>";
			$this->salida.="			<td width=\"25%\">&nbsp;</td>";
			$this->salida.="			<td width=\"25%\">HEMOCLASIFICACION</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_claro\" align=\"right\">";
			$check1="";
			$check2="";
			if($_REQUEST['sexo'.$pfj]==1)
			{
				$check1="checked";
			}
			else if($_REQUEST['sexo'.$pfj]==2)
					{
						$check2="checked";
					}
			
			$this->salida.="			<td align=\"center\" rowspan=\"3\"><label class=\"".$this->SetStyle("sexo")."\">F</label> <input type=\"radio\" name=\"sexo$pfj\" value=\"1\" $check1> <label class=\"".$this->SetStyle("sexo")."\">M</label> <input type=\"radio\" name=\"sexo$pfj\" value=\"2\" $check2></td>";
			if($_REQUEST['tipo_egreso'.$pfj]==1)
				$check="checked";
			else
				$check="";
			$this->salida.="			<td><label class=\"label\">SANO</label> <input type=\"radio\" name=\"tipo_egreso$pfj\" value=\"1\" $check></td>";
			$this->salida.="			<td><label class=\"label\">PESO AL NACER</label> <input type=\"text\" class=\"input-text\" name=\"peso_nacer$pfj\" value=\"".$_REQUEST['peso_nacer'.$pfj]."\" maxlength=\"20\" size=\"10\"> gr</td>";
			$this->salida.="			<td align=\"center\" rowspan=\"3\"><label class=\"".$this->SetStyle("grupo")."\">GRUPO</label> ";
			$grupo=array("A","B","AB","O");
			$this->salida.="			<select name=\"grupo_san$pfj\" class=\"select\">";
			$this->salida.="				<option value=\"\">--</option>";
			for($i=0;$i<sizeof($grupo);$i++)
			{
				if($_REQUEST['grupo_san'.$pfj]==($i+1))
					$this->salida.="			<option value=\"".($i+1)."\" selected>".$grupo[$i]."</option>";
				else
					$this->salida.="			<option value=\"".($i+1)."\">".$grupo[$i]."</option>";
			}
			$this->salida.="			</select>";
			$this->salida.="			 - <label class=\"".$this->SetStyle("rh")."\">RH</label> ";
			$select1="";
			$select2="";
			if($_REQUEST['rh'.$pfj]==1)
				$select1="selected";
			else if($_REQUEST['rh'.$pfj]==2)
						$select2="selected";
						
			$this->salida.="			<select name=\"rh$pfj\" class=\"select\">";
			$this->salida.="				<option value=\"\">--</option>";
			$this->salida.="				<option value=\"1\" $select1>+</option>";
			$this->salida.="				<option value=\"2\" $select2>-</option>";
			$this->salida.="			</select>";
			$this->salida.="			</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_oscuro\" align=\"right\">";
			if($_REQUEST['tipo_egreso'.$pfj]==2)
				$check="checked";
			else
				$check="";
			$this->salida.="			<td><label class=\"label\">CON PATOLOGIA</label> <input type=\"radio\" name=\"tipo_egreso$pfj\" value=\"2\" $check></td>";
			$this->salida.="			<td><label class=\"label\">TALLA AL NACER</label> <input type=\"text\" class=\"input-text\" name=\"talla$pfj\" value=\"".$_REQUEST['talla'.$pfj]."\" size=\"10\" maxlength=\"10\"> cm</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_claro\" align=\"right\">";
			if($_REQUEST['tipo_egreso'.$pfj]==3)
				$check="checked";
			else
				$check="";
			$this->salida.="			<td><label class=\"label\">FALLECE</label> <input type=\"radio\" name=\"tipo_egreso$pfj\" value=\"3\" $check></td>";
			$this->salida.="			<td><label class=\"label\">PER CEF</label> <input type=\"text\" class=\"input-text\" name=\"percef$pfj\" value=\"".$_REQUEST['percef'.$pfj]."\" size=\"10\" maxlength=\"10\"> cm</td>";
		
			$this->salida.="		</tr>";
			$this->salida.="	</table>";
			$this->salida.="	<br>";
			$this->salida.="	<table align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\" align=\"center\">";
			$this->salida.="			<td width=\"20%\">VDRL</td>";
			$this->salida.="			<td width=\"20%\">TSH</td>";
			$this->salida.="			<td width=\"30%\" colspan=\"2\">VACUNAS</td>";
			$this->salida.="			<td width=\"30%\"></td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_oscuro\" align=\"right\">";
			if($_REQUEST['vdrl'.$pfj]==1)
				$check="checked";
			else
				$check="";	
			$this->salida.="			<td><label class=\"label\">POSITIVO</label> <input type=\"radio\" name=\"vdrl$pfj\" value=\"1\" $check></td>";
			if($_REQUEST['tsh'.$pfj]==1)
				$check="checked";
			else
				$check="";	
			$this->salida.="			<td><label class=\"label\">NORMAL</label> <input type=\"radio\" name=\"tsh$pfj\" value=\"1\" $check></td>";
			if($_REQUEST['sw_bcg'.$pfj])
				$check="checked";
			else
				$check="";	
			$this->salida.="			<td><label class=\"label\">BCG</label> <input type=\"checkbox\" name=\"sw_bcg$pfj\" value=\"1\" $check></td>";
			if($_REQUEST['sw_hepatitis'.$pfj])
				$check="checked";
			else
				$check="";	
			$this->salida.="			<td><label class=\"label\">HEPATITIS</label> <input type=\"checkbox\" name=\"sw_hepatitis$pfj\" value=\"1\" $check></td>";
			$this->salida.="			<td><label class=\"label\">EDAD POR EXAMEN FISICO</label> <input type=\"text\" class=\"input-text\" name=\"edad$pfj\" value=\"".$_REQUEST['edad'.$pfj]."\" maxlength=\"20\" size=\"10\"></td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_claro\" align=\"right\">";
			if($_REQUEST['vdrl'.$pfj]==2)
				$check="checked";
			else
				$check="";	
			$this->salida.="			<td><label class=\"label\">NEGATIVO</label> <input type=\"radio\" name=\"vdrl$pfj\" value=\"2\" $check></td>";
			if($_REQUEST['tsh'.$pfj]==2)
				$check="checked";
			else
				$check="";
			$this->salida.="			<td><label class=\"label\">ANORMAL</label> <input type=\"radio\" name=\"tsh$pfj\" value=\"2\" $check></td>";
			if($_REQUEST['sw_polio'.$pfj])
				$check="checked";
			else
				$check="";
			$this->salida.="			<td><label class=\"label\">POLIO</label> <input type=\"checkbox\" name=\"sw_polio$pfj\" value=\"1\" $check></td>";
			if($_REQUEST['sw_vitk'.$pfj])
				$check="checked";
			else
				$check="";
			$this->salida.="			<td><label class=\"label\">VITAMINA K</label> <input type=\"checkbox\" name=\"sw_vitk$pfj\" value=\"1\" $check></td>";
			$this->salida.="			<td><label class=\"label\">PESO/EG</label>";
			$this->salida.="				<select name=\"peso_eg$pfj\" class=\"select\">";
			$this->salida.="					<option value=\"\">--SELECCIONE--</option>";
			$check1="";
			$check2="";
			$check3="";
			
			if($_REQUEST['peso_eg'.$pfj]==1)
				$check1="selected";
			if($_REQUEST['peso_eg'.$pfj]==2)
					$check2="selected";
			if($_REQUEST['peso_eg'.$pfj]==3)
				$check3="selected";
				
			$this->salida.="					<option value=\"1\" $check1>ADECUADO</option>";
			$this->salida.="					<option value=\"2\" $check2>PEQUEÑO</option>";
			$this->salida.="					<option value=\"3\" $check3>GRANDE</option>";
			$this->salida.="				</select>";
			$this->salida.="			</td>";	
			$this->salida.="		</tr>";
			$this->salida.="	</table>";
			$this->salida.="	<br>";
			$this->salida.="	<table align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\" align=\"center\">";
			$this->salida.="			<td width=\"20%\"></td>";
			$this->salida.="			<td width=\"40%\" colspan=\"2\">EDAD ALTA/TRASLADO</td>";
			$this->salida.="			<td width=\"40%\" colspan=\"2\">EDAD AL FALLECER</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_oscuro\" align=\"right\">";
			if($_REQUEST['sw_rnmadre'.$pfj])
				$check="checked";
			else
				$check="";
			$this->salida.="			<td><label class=\"label\">RN CON LA MADRE</label> <input type=\"checkbox\" name=\"sw_rnmadre$pfj\" value=\"1\" $check></td>";
			$this->salida.="			<td><label class=\"label\">DIAS</label> <input type=\"text\" class=\"input-text\" name=\"dias_traslado$pfj\" value=\"".$_REQUEST['dias_traslado'.$pfj]."\" maxlength=\"5\" size=\"10\"></td>";
			$this->salida.="			<td><label class=\"label\">HORAS</label>";
			$this->salida.="					<select name=\"horas_traslado$pfj\" class=\"select\">";
			$this->salida.="						<option value=\"\">--</option>";
			if($_REQUEST['horas_traslado'.$pfj]==-1)
				$this->salida.="						<option value=\"-1\" selected>0 hrs</option>";
			else
				$this->salida.="						<option value=\"-1\">0 hrs</option>";
			
			for($i=1;$i<=23;$i++)
			{
				if($_REQUEST['horas_traslado'.$pfj]==$i)
					$this->salida.="						<option value=\"$i\" selected>$i hrs</option>";
				else
					$this->salida.="						<option value=\"$i\">$i hrs</option>";
			}
			$this->salida.="					</select>";
			$this->salida.="			</td>";
			$this->salida.="			<td><label class=\"label\">DIAS</label> <input type=\"text\" class=\"input-text\" name=\"dias_fallece$pfj\" value=\"".$_REQUEST['dias_fallece'.$pfj]."\" maxlength=\"5\" size=\"10\"></td>";
			$this->salida.="			<td><label class=\"label\">HORAS</label>";
			$this->salida.="					<select name=\"horas_fallece$pfj\" class=\"select\">";
			$this->salida.="						<option value=\"\">--</option>";
			if($_REQUEST['horas_fallece'.$pfj]==-1)
				$this->salida.="						<option value=\"-1\" selected>0 hrs</option>";
			else
				$this->salida.="						<option value=\"-1\">0 hrs</option>";
			
			for($i=1;$i<=23;$i++)
			{
				if($_REQUEST['horas_fallece'.$pfj]==$i)
					$this->salida.="						<option value=\"$i\" selected>$i hrs</option>";
				else
					$this->salida.="						<option value=\"$i\">$i hrs</option>";
			}
			$this->salida.="					</select>";
			$this->salida.="			</td>";
			$this->salida.="		</tr>";
			$this->salida.="	</table>";
			$this->salida.="	<br>";
			$this->salida.="	<table align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\" align=\"center\">";
			$this->salida.="			<td width=\"45%\">&nbsp;</td>";
			$this->salida.="			<td width=\"55%\" colspan=\"3\">&nbsp;</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_oscuro\" align=\"right\">";
			$this->salida.="			<td align=\"center\" rowspan=\"2\"><label class=\"label\">ALIMENTACION</label>";
			$this->salida.="					<select name=\"alimentacion$pfj\" class=\"select\">";
			$this->salida.="						<option value=\"\">--SELECCIONE--</option>";
			
			$select1="";
			$select2="";
			$select3="";
			
			if($_REQUEST['alimentacion'.$pfj]==1)
				$select1="selected";
			if($_REQUEST['alimentacion'.$pfj]==2)
					$select2="selected";
			if($_REQUEST['alimentacion'.$pfj]==3)
				$select3="selected";
			
			$this->salida.="						<option value=\"1\" $select1>LACTANCIA MATERNA</option>";
			$this->salida.="						<option value=\"2\" $select2>MIXTO</option>";
			$this->salida.="						<option value=\"3\" $select3>ARTIFICIAL</option>";
			$this->salida.="					</select>";
			$this->salida.="			</td>";
			
			if($_REQUEST['sw_muerte_materno'.$pfj])
				$this->salida.="			<td colspan=\"3\"><label class=\"label\">MUERTE MATERNA</label> <input type=\"checkbox\" name=\"sw_muerte_materno$pfj\" value=\"1\" onclick=\"ActivarRadios(this.form,this.checked)\" checked></td>";
			else
				$this->salida.="			<td colspan=\"3\"><label class=\"label\">MUERTE MATERNA</label> <input type=\"checkbox\" name=\"sw_muerte_materno$pfj\" value=\"1\" onclick=\"ActivarRadios(this.form,this.checked)\"></td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_claro\" align=\"right\">";
			
			$check1="";
			$check2="";
			$check3="";
			
			if($_REQUEST['sw_muerte_materno'.$pfj])
			{
				if($_REQUEST['muerte_materno'.$pfj]==1)
					$check1="checked";
				if($_REQUEST['muerte_materno'.$pfj]==2)
						$check2="checked";
				if($_REQUEST['muerte_materno'.$pfj]==3)
					$check3="checked";
					
				$this->salida.="			<td width=\"15%\"><label class=\"label\">PARTO</label> <input type=\"radio\" name=\"muerte_materno$pfj\" value=\"1\" $check1></td>";
				$this->salida.="			<td width=\"15%\"><label class=\"label\">EMBARAZO</label> <input type=\"radio\" name=\"muerte_materno$pfj\" value=\"2\" $check2></td>";
				$this->salida.="			<td width=\"15%\"><label class=\"label\">PUERPERIO</label> <input type=\"radio\" name=\"muerte_materno$pfj\" value=\"3\" $check3></td>";
			}
			else
			{
				$this->salida.="			<td width=\"15%\"><label class=\"label\">PARTO</label> <input type=\"radio\" name=\"muerte_materno$pfj\" value=\"1\" disabled></td>";
				$this->salida.="			<td width=\"15%\"><label class=\"label\">EMBARAZO</label> <input type=\"radio\" name=\"muerte_materno$pfj\" value=\"2\" disabled></td>";
				$this->salida.="			<td width=\"15%\"><label class=\"label\">PUERPERIO</label> <input type=\"radio\" name=\"muerte_materno$pfj\" value=\"3\" disabled></td>";
			}
			$this->salida.="		</tr>";
			$this->salida.="	</table>";
			$this->salida.="	<table align=\"center\" cellspacing=\"20\">";
			$this->salida.="	<tr>";
			$this->salida.="		<td><input class=\"input-submit\" type=\"submit\" name=\"guardar$pfj\" value=\"GUARDAR\"></td>";	
			$this->salida.="</form>";
			$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'RegistroEvolucionGestacion'));
				
			$this->salida.="<form name=\"formavolver$pfj\" action=\"$accion2\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"volver1$pfj\" value=\"VOLVER A REGISTRO EVOLUCION\"></td>";
			$this->salida.="</form>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			$this->salida.="</form>";

			$this->salida.=ThemeCerrarTablaSubModulo();
			
			return $this->salida;
		}
		
		
		function frmDatosConsultaNacidos($datos)
		{
			
			$pfj=SessionGetVar("Prefijo");
			$evolucion=SessionGetVar("Evolucion");
			$paso=SessionGetVar("Paso");

			$this->salida.= ThemeAbrirTablaSubModulo('DATOS DEL RECIEN NACIDO');

			foreach($datos as $NacidosR)
			{
				$this->salida.="	<table align=\"center\" border=\"0\" width=\"50%\">";
				$this->salida.="		<tr>";
				$this->salida.="			<td class=\"modulo_list_oscuro\" width=\"30%\">No HISTORIA RN</td>";
				$this->salida.="			<td class=\"modulo_list_claro\" width=\"70%\">".$NacidosR['no_historia_rn']."</td>";
				$this->salida.="		</tr>";
				$this->salida.="		<tr>";
				$this->salida.="			<td class=\"modulo_list_oscuro\">NOMBRE RECIEN NACIDO</td>";
				$this->salida.="			<td class=\"modulo_list_claro\">".$NacidosR['nombre_rn']."</td>";
				$this->salida.="		</tr>";
				$this->salida.="		<tr>";
				$this->salida.="			<td class=\"modulo_list_oscuro\">SEXO</td>";
				$this->salida.="			<td class=\"modulo_list_claro\">".$NacidosR['sexo']."</td>";
				$this->salida.="		</tr>";
				$this->salida.="		<tr>";
				$this->salida.="			<td class=\"modulo_list_oscuro\">NOMBRE MADRE</td>";
				$this->salida.="			<td class=\"modulo_list_claro\">".$NacidosR['nombre_madre']."</td>";
				$this->salida.="		</tr>";
				$this->salida.="		<tr>";
				$this->salida.="			<td class=\"modulo_list_oscuro\">NOMBRE PADRE</td>";
				$this->salida.="			<td class=\"modulo_list_claro\">".$NacidosR['nombre_padre']."</td>";
				$this->salida.="		</tr>";
				$this->salida.="	</table><br>";
				
			}
			
			$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'CierredeCaso'));
			
			$this->salida.="<table align=\"center\" cellspacing=\"20\">";
			$this->salida.="	<tr>";
			$this->salida.="		<form name=\"formavolver$pfj\" action=\"$accion2\" method=\"post\">";
			$this->salida.="   		<td><input class=\"input-submit\" type=\"submit\" name=\"volver1$pfj\" value=\"VOLVER\"></td>";
			$this->salida.="		</form>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			
			$this->salida.=ThemeCerrarTablaSubModulo();
			
			return $this->salida;
		}
		
		function SetStyle($campo)
		{
			if ($this->frmError[$campo]||$campo=="MensajeError")
			{
				if ($campo=="MensajeError")
				{
					return ("<tr><td align=\"center\" class=\"label_error\">".$this->frmError["MensajeError"]."</td></tr>");
				}
				return ("label_error");
			}
			return ("label");
		}
	}
?>