<?php
	/**************************************************************************************
	* $Id: CierreCaso.php,v 1.2 2007/02/01 20:44:26 luis Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Hugo F. Manrique	
	**************************************************************************************/
	$VISTA = "HTML";
	$_ROOT = "../../../";
	include  $_ROOT."classes/rs_server/rs_server.class.php";
	include	 $_ROOT."includes/enviroment.inc.php";
	include	 $_ROOT."classes/modules/hc_classmodules.class.php";
	$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
	IncludeFile($filename);
	include	 $_ROOT."hc_modules/CierredeCaso/hc_CierredeCaso.php";
	include	 $_ROOT."hc_modules/RiesgoBiopsicosocial/hc_RiesgoBiopsicosocial.php";
	
	class procesos_admin extends rs_server
	{
		
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
		
		function FechaStamp($fecha)
		{
			if($fecha)
			{
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}
				
				return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
			}
		}
		
		function CierreCaso($datos)
		{
			switch($datos[0])
			{
				case 1:
					$html= $this->frmParto($datos);
				break;
				
				case 2:
					$html= $this->frmAborto($datos);
				breaK;
				
				case 3:
					$html= $this->frmMuerteMaterna($datos);
				break;
			}
			return $html;
		}
		
		function CalculoSemana($datos)
		{
			$riesgo = new RiesgoBS();
			$programa=SessionGetVar("Programa");
			$fechas=$riesgo->GetDatofum(SessionGetVar("Inscripcion_$programa"));
			$fum=$fechas[0][fecha_ultimo_periodo];
			$semana=intval($riesgo->CalcularSemanasGestante($fum,$this->FechaStamp($datos[0])));
			
			return $semana;
		}
		
		function AlmacenaCierreCaso($param)
		{
			$cierre=new Cierre();
			$datos=array();
			
			print_r($param);
			
			if(!$param[0])
			{
				switch($param[1])
				{
					case 1:
						$fecha_term=$param[2];//fecha terminacion
						$horas_term=$param[3];//hora terminacion
						$min_term=$param[4];//min terminacion
						$tipo_term=$param[5];
						$semana=$param[6];
						$nivel_aten=$param[7];
						$sw_epi=$param[8];
						$sw_des=$param[9];
						$sw_muerte=$param[10];
						$sw_parto=$param[11];
						$aten_parto=$param[12];
						$aten_neonato=$param[13];
						$num_hijos_v=$param[14];
						$num_hijos_m=$param[15];
						
						$fecha_term=$this->FechaStamp($fecha_term);
						if($horas_term<11 && $min_term<11)
							$fecha2="$fecha_term 0".($horas_term-1).":0".($min_term-1).":00";
						else
							$fecha2="$fecha_term ".($horas_term-1).":".($min_term-1).":00";
						
						$datos[]=$fecha2;//fecha term
						$datos[]=$tipo_term;//tipo de terminacion
						$datos[]=$semana;
						$datos[]=$nivel_aten;
						$datos[]=$sw_epi;
						$datos[]=$sw_des;
						$datos[]=$sw_muerte;
						$datos[]=$sw_parto;
						$datos[]=$aten_parto;
						$datos[]=$aten_neonato;
						$datos[]=$num_hijos_v;
						$datos[]=$num_hijos_m;
						
					break;
					case 2:
						$fecha_term=$this->FechaStamp($param[2]);
						$datos[]=$fecha_term;
					break;
					case 3:
						$fecha_term=$this->FechaStamp($param[2]);
						$feto_vivo=$param[3];
						$causa=$param[4];
						
						$datos[]=$fecha_term;			
						$datos[]= ($feto_vivo=='1')?'true':'false';	
						$datos[]=$causa;
					break;
				}
				$programa=SessionGetvar("Programa");
				$inscripcion=SessionGetvar("Inscripcion_$programa");
				$evolucion=SessionGetvar("Evolucion");
				
				if($cierre->GuardarCierreCaso($inscripcion,$evolucion,$datos,$param[1])==false)
				{
					return $cierre->ErrorDB();
				}
			}
			
			return "";
		}
		
		function frmParto($datos)
		{
			$this->salida="";
			$pfj=$datos[1];

			$this->salida.="<form name=\"formades$pfj\" action=\"\" method=\"post\">";
			
			$this->salida.="	<input type=\"hidden\" name=\"causa_cie$pfj\" value=\"".$datos[0]."\">";
			
			$this->salida.="	<table align=\"center\" border=\"0\" width=\"80%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td width=\"100%\" colspan=\"4\">TERMINACION</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_claro\" align=\"right\">";
			$this->salida.="			<td width=\"15%\">";
			$this->salida.="				<label class=\"label\">ESPONTANEA</label> <input type=\"radio\" name=\"terminacion$pfj\" value=\"1\">";
			$this->salida.="			</td>";
			$this->salida.="			<td width=\"15%\">";
			$this->salida.="				<label class=\"label\">CESARIA</label> <input type=\"radio\" name=\"terminacion$pfj\" value=\"2\">";
			$this->salida.="			</td>";
			$this->salida.="			<td width=\"35%\" align=\"center\">";
			$this->salida.="				<label class=\"label\">FECHA</label> <input type=\"text\" class=\"input-text\" name=\"fecha_term$pfj\" maxlength=\"10\" size=\"10\">
															<a href=\"javascript:LlamarCalendariofecha_term$pfj();\"><img onmouseover=\"window.status='Calendario';return true;\" onmouseout=\"window.status=''; return true; \" src=\"themes/HTML/AzulXp/images/calendario/calendario.png\" alt=\"Ver Calendario\" border=\"0\"></a> [dd-mm-aaaa]</sub>";
			$this->salida.="			</td>";
			$this->salida.="			<td rowspan=\"2\" width=\"25%\" align=\"center\">";
			$this->salida.="				<label class=\"label\"><a href=\"javascript:Semana(document.formades$pfj)\">SEMANA</a></label> <input type=\"text\" class=\"input-text\" name=\"semana$pfj\" value=\"\" maxlength=\"3\" size=\"5\" readonly>";
			$this->salida.="			</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_claro\" align=\"right\">";
			$this->salida.="			<td>";
			$this->salida.="				<label class=\"label\">FORCEPS</label> <input type=\"radio\" name=\"terminacion$pfj\" value=\"3\">";
			$this->salida.="			</td>";
			$this->salida.="			<td>";
			$this->salida.="				<label class=\"label\">OTRA</label> <input type=\"radio\" name=\"terminacion$pfj\" value=\"4\">";
			$this->salida.="			</td>";
			$this->salida.="		<td align=\"center\">";
			$this->salida.="		<label class=\"label\">HORA</label> ";
			$this->salida.="				<select name=\"horas_term$pfj\" class=\"select\">";
			$this->salida.="					<option value=\"\">--</option>";
			$j=1;
			for($i=0;$i<24;$i++)
			{
				if($i<=9)
				{
					$this->salida.="					<option value=\"0$j\">0$i</option>";
				}
				else
				{
					$this->salida.="					<option value=\"$j\">$i</option>";
				}
				$j++;
			}
			$this->salida.="				</select> : ";
			$this->salida.="				<select name=\"min_term$pfj\" class=\"select\">";
			$this->salida.="					<option value=\"\">--</option>";
			$j=1;
			for($i=0;$i<60;$i++)
			{
				if($i<=9)
				{
					$this->salida.="					<option value=\"0$j\">0$i</option>";
				}
				else
				{
					$this->salida.="					<option value=\"$j\">$i</option>";
				}
				$j++;
			}
			$this->salida.="				</select>";
			$this->salida.="			[hh:mm 24hs]</td>";
			$this->salida.="		</tr>";
			$this->salida.="	</table>";
			
			$this->salida.="	<br>";
			
			$this->salida.="	<table align=\"center\" border=\"0\" width=\"80%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td width=\"60%\" colspan=\"5\">NIVEL DE ATENCION</td>";
			$this->salida.="			<td width=\"40%\" colspan=\"2\"> </td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"modulo_list_oscuro\" align=\"right\">";
			$this->salida.="			<td width=\"10%\">";
			$this->salida.="				<label class=\"label\">1</label> <input type=\"radio\" name=\"nivel_atencion$pfj\" value=\"1\">";
			$this->salida.="			</td>";
			$this->salida.="			<td width=\"10%\">";
			$this->salida.="				<label class=\"label\">2</label> <input type=\"radio\" name=\"nivel_atencion$pfj\" value=\"2\">";
			$this->salida.="			</td>";	
			$this->salida.="			<td width=\"10%\">";
			$this->salida.="				<label class=\"label\">3</label> <input type=\"radio\" name=\"nivel_atencion$pfj\" value=\"3\">";
			$this->salida.="			</td>";
			$this->salida.="			<td width=\"20%\">";
			$this->salida.="				<label class=\"label\">DOMICILIARIA</label> <input type=\"radio\" name=\"nivel_atencion$pfj\" value=\"4\">";
			$this->salida.="			</td>";
			$this->salida.="			<td width=\"10%\">";
			$this->salida.="				<label class=\"label\">OTRO</label> <input type=\"radio\" name=\"nivel_atencion$pfj\" value=\"5\">";
			$this->salida.="			</td>";
			$this->salida.="			<td>";
			$this->salida.="				<label class=\"label\">EPISIOTOMIA</label> <input type=\"checkbox\" name=\"sw_episiotomia$pfj\" value=\"1\">";
			$this->salida.="			</td>";
			$this->salida.="			<td>";
			$this->salida.="				<label class=\"label\">DESGARROS</label> <input type=\"checkbox\" name=\"sw_desgarros$pfj\" value=\"1\">";
			$this->salida.="			</td>";
			$this->salida.="		</tr>";
			$this->salida.="	</table>";
			
			$this->salida.="	<br>";
			
			$this->salida.="	<table align=\"center\" border=\"0\" width=\"80%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td width=\"100%\" colspan=\"4\">MUERTE FETAL</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_claro\" align=\"right\">";
			$this->salida.="			<td width=\"20%\">";
			$this->salida.="				<label class=\"label\">MUERTE FETAL</label><input type=\"checkbox\" name=\"sw_muerte_fetal$pfj\" value=\"1\" onclick=\"ActivarMuerteFetal(this.form,this.checked)\">";
			$this->salida.="			</td>";
			$this->salida.="			<td width=\"12%\">";
			$this->salida.="				<label class=\"label\">PARTO</label><input type=\"radio\" name=\"parto$pfj\" value=\"1\" disabled=true onclick=\"Mostrar(true)\">";
			$this->salida.="			</td>";
			$this->salida.="			<td width=\"33%\">";
			$this->salida.="				<label class=\"label\">MOMENTO DESCONOCIDO</label><input type=\"radio\" name=\"parto$pfj\" value=\"2\" disabled=true onclick=\"Mostrar(false)\">";
			$this->salida.="			</td>";
			$this->salida.="			<td width=\"35%\">";
			$this->salida.="				<label class=\"label\">NUMERO DE HIJOS MUERTOS</label> <input type=\"text\" class=\"input-text\" name=\"num_hijos_muertos$pfj\" value=\"\" maxlength=\"2\" size=\"5\" disabled=true>";
			$this->salida.="			</td>";
			$this->salida.="		</tr>";
			$this->salida.="	</table>";
			
			$this->salida.="	<div id=\"atencion\" style=\"display:block\">";
			$this->salida.="	<br><table align=\"center\" border=\"0\" width=\"80%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td width=\"50%\" colspan=\"2\">ATENDIO</td>";
			$this->salida.="			<td width=\"25%\"></td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_oscuro\" align=\"right\">";
			$this->salida.="			<td width=\"25%\" align=\"center\">";
			$this->salida.="				<label class=\"label\">PARTO</label> ";
			$this->salida.="				<select name=\"aten_parto$pfj\" class=\"select\">";
			$this->salida.="					<option value=\"\">--SELECCIONE--</option>";
			$atendio=array("MEDICO","ENFERMERA","AUXILIAR","PARTERA","PROMOTOR","OTRO");
			for($i=0;$i<sizeof($atendio);$i++)
					$this->salida.="				<option value=\"".($i+1)."\">".$atendio[$i]."</option>";
			$this->salida.="				</select>";
			$this->salida.="			</td>";
			$this->salida.="			<td width=\"25%\" align=\"center\">";
			$this->salida.="				<label class=\"label\">NEONATO</label> ";
			$this->salida.="				<select name=\"aten_neonato$pfj\" class=\"select\">";
			$this->salida.="					<option value=\"\">--SELECCIONE--</option>";
			for($i=0;$i<sizeof($atendio);$i++)
					$this->salida.="				<option value=\"".($i+1)."\">".$atendio[$i]."</option>";
			$this->salida.="				</select>";
			$this->salida.="			</td>";
			$this->salida.="			<td width=\"25%\">";
			$this->salida.="				<label class=\"label\">NUMERO DE HIJOS VIVOS</label> <input type=\"text\" class=\"input-text\" name=\"num_hijos_vivos$pfj\" value=\"\" maxlength=\"2\" size=\"5\">";
			$this->salida.="			</td>";
			$this->salida.="		</tr>";
			$this->salida.="	</table>";
			$this->salida.="	</div>";
			
			$this->salida.="	<br>";
			
			$this->salida.="	<table align=\"center\">";
			$this->salida.="		<tr>";
			$this->salida.="			<td><input class=\"input-submit\" type=\"button\" name=\"guardar$pfj\" value=\"GUARDAR\" onclick=\"EnviarDatos(this.form,".$datos[0].");Enviar(this.form,".$datos[0].")\"></td>";
			$this->salida.="		<tr>";
			$this->salida.="	</table>";
			$this->salida.="</form>";
		 
		 	return $this->salida;
		}
		 
		function frmAborto($datos)
		{
			$this->salida="";
			$pfj=$datos[1];

			$this->salida.="<form name=\"formades$pfj\" action=\"\" method=\"post\">";
			$this->salida.="	<input type=\"hidden\" name=\"causa_cie$pfj\" value=\"".$datos[0]."\">";
			$this->salida.="	<table align=\"center\" border=\"0\" width=\"50%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td  colspan=\"2\" width=\"100%\">FECHA ABORTO</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"modulo_list_claro\">";
			$this->salida.="			<td width=\"20%\" align=\"center\">";
			$this->salida.="				<label class=\"label\">FECHA</label>";
			$this->salida.="			</td>";
			$this->salida.="			<td width=\"80%\" align=\"center\">";
			$this->salida.="				<input type=\"text\" class=\"input-text\" name=\"fecha_term$pfj\" maxlength=\"10\" size=\"10\">
															<a href=\"javascript:LlamarCalendariofecha_term$pfj()\"><img onmouseover=\"window.status='Calendario';return true;\" onmouseout=\"window.status=''; return true;\" src=\"themes/HTML/AzulXp/images/calendario/calendario.png\" alt=\"Ver Calendario\" border=\"0\"></a> [dd-mm-aaaa]</sub>";
			$this->salida.="			</td>";
			$this->salida.="		<tr>";
			$this->salida.="		<tr align=\"center\">";
			$this->salida.="			<td colspan=\"2\"><br><input class=\"input-submit\" type=\"button\" name=\"guardar$pfj\" value=\"GUARDAR\" onclick=\"EnviarDatos(this.form,".$datos[0].");Enviar(this.form,".$datos[0].")\"></td>";
			$this->salida.="		</tr>";
			$this->salida.="	</table>";
			$this->salida.="</form>";
			return $this->salida;
		}

		function frmMuerteMaterna($datos)
		{
		 	$this->salida="";
			$pfj=$datos[1];

			$this->salida.="<form name=\"formades$pfj\" action=\"\" method=\"post\">";
			$this->salida.="	<input type=\"hidden\" name=\"causa_cie$pfj\" value=\"".$datos[0]."\">";
			$this->salida.="	<table align=\"center\" border=\"0\" width=\"60%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td width=\"100%\" colspan=\"5\">MUERTE MATERNA</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_claro\" align=\"left\">";
			$this->salida.="			<td align=\"center\" colspan=\"2\">";
			$this->salida.="				<label class=\"label\">FECHA</label> <input type=\"text\" class=\"input-text\" name=\"fecha_term$pfj\" maxlength=\"10\" size=\"10\">
															<a href=\"javascript:LlamarCalendariofecha_term$pfj()\"><img onmouseover=\"window.status='Calendario';return true;\" onmouseout=\"window.status=''; return true;\" src=\"themes/HTML/AzulXp/images/calendario/calendario.png\" alt=\"Ver Calendario\" border=\"0\"></a> [dd-mm-aaaa]</sub>";
			$this->salida.="			</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_oscuro\" align=\"left\">";
			$this->salida.="			<td align=\"right\">";
			$this->salida.="				<label class=\"label\">FETO VIVO</label>";
			$this->salida.="			</td>";
			$this->salida.="			<td align=\"center\">";
			$this->salida.="				<label class=\"label\">CAUSA DE MUERTE</label>";
			$this->salida.="			</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_claro\" align=\"right\">";
			$this->salida.="			<td>";
			$this->salida.="				<label class=\"label\">Si</label><input type=\"radio\" name=\"feto_vivo$pfj\" value=\"1\">";
			$this->salida.="			</td>";
			$this->salida.="			<td rowspan=\"2\"align=\"center\">";
			$this->salida.="				<textarea class=\"input-text\" name=\"causa$pfj\" cols=\"30\" rows=\"3\"></textarea>";
			$this->salida.="			</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_submodulo_list_oscuro\" align=\"right\">";
			$this->salida.="			<td>";
			$this->salida.="				<label class=\"label\">No</label><input type=\"radio\" name=\"feto_vivo$pfj\" value=\"2\">";
			$this->salida.="			</td>";
			$this->salida.="		</tr>";
			$this->salida.="	</table>";
			$this->salida.="	<br>";
			$this->salida.="	<table align=\"center\">";
			$this->salida.="		<tr>";
			$this->salida.="			<td><input class=\"input-submit\" type=\"button\" name=\"guardar$pfj\" value=\"GUARDAR\" onclick=\"EnviarDatos(this.form,".$datos[0].");Enviar(this.form,".$datos[0].")\"></td>";
			$this->salida.="		<tr>";
			$this->salida.="	</table>";
			
			$this->salida.="</form>";

		 	return $this->salida;
		 }

		/********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*********************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
	$oRS = new procesos_admin();
	$oRS->action();	
?>