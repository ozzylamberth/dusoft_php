<?php
	/**************************************************************************************
	* $Id: FrecuenciaMedicamentos.class.php,v 1.3 2006/08/04 22:02:36 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Hugo F. Manrique
	**************************************************************************************/	

	class FrecuenciaMedicamentos
	{
		var $salida = "";
		var $frec = array("Minuto(s)","Hora(s)","Dia(s)","Semana(s)");
		
		function FrecuenciaMedicamentos(){}
		/********************************************************************************
		*
		*********************************************************************************/
		function Inicializar()
		{			
			$this->salida .= ReturnHeader('Frecuencias',$scripts);
      $this->salida .= ReturnBody()."\n";	
			$this->CuerpoFrecuencias();
			$this->salida .=ReturnFooter();
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CuerpoFrecuencias()
		{
			$codigo = $_REQUEST['codigo'];
			$periodos = $this->Periocidad();

			$this->salida .= "<div name=\"Error\" id=\"Error\"></div><br>\n";
			$this->salida .= "<form name=\"frecuencias\" action=\"\" method=\"post\">\n";
			$this->salida .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "  		<td align=\"center\" colspan=\"4\">ADICIONAR FRECUENCIA MEDICAMENTOS</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
			$this->salida .= "			<td align=\"center\"><b>CADA:</b></td>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<select name=\"periodicidad\" class=\"select\">\n";
			$this->salida .= "					<option value=\"0\">-Seleccionar-</option>\n";
			
			for($i=0; $i<sizeof($periodos); $i++)
			{
				$this->salida .= "					<option value=\"".$periodos[$i]['periocidad_id']."\">".$periodos[$i]['periocidad_id']."</option>\n";
			}
			
			$this->salida .= "				</select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<select name=\"diario\" class=\"select\">\n";
			$this->salida .= "					<option value=\"0\">-Seleccionar-</option>\n";
			
			for($i=0; $i<sizeof($this->frec); $i++)
			{
				$this->salida .= "					<option value=\"".$this->frec[$i]."\">".$this->frec[$i]."</option>\n";
			}
			
			$this->salida .= "				</select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td colspan=\"3\" align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" name=\"buscar\" type=\"button\" value=\"Aceptar\" onclick=\"EvaluarDatos(document.frecuencias)\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
			$this->salida .= "			<td align=\"center\" colspan=\"3\">\n";
			$this->salida .= "				<select name=\"dosisM\" class=\"select\">\n";
			$this->salida .= "					<option value=\"0\">-Seleccionar-</option>\n";
			
			$dosis = $this->DosisM();
			
			for($i=0; $i<sizeof($dosis); $i++)
			{
				$this->salida .= "					<option value=\"".$dosis[$i]['descripcion']."\">".$dosis[$i]['descripcion']."</option>\n";
			}
			
			$this->salida .= "				</select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td colspan=\"3\" align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" name=\"buscar\" type=\"button\" value=\"Aceptar\" onclick=\"EvaluarDatos2(document.frecuencias)\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= "<center><input class=\"input-submit\" name=\"buscar\" type=\"button\" value=\"Cerrar\" onclick=\"window.close();\"></center>\n";
			$this->salida .= "<script>\n";
			$this->salida .= "	function EvaluarDatos(objeto)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		mensaje = \"\" \n";
			$this->salida .= "		if(objeto.diario.value == \"0\")\n";
			$this->salida .= "			mensaje = \"SE DEBE SELECCIONAR LA PERIODICIDAD DEL MEDICAMENNTO\";\n";
			$this->salida .= "		else\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var periodo = objeto.periodicidad.value+' ';\n";
			$this->salida .= "			if(objeto.periodicidad.value == \"0\") periodo = '';\n";
			$this->salida .= "			cadena = \"Cada \"+periodo+objeto.diario.value;\n";
			$this->salida .= "			EnviarDatos(cadena)\n";
			$this->salida .= "		}\n";
			$this->salida .= "		document.getElementById('Error').innerHTML = \"<center><b class='label_error'>\"+mensaje+\"</b></center>\";\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function EvaluarDatos2(objeto)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		mensaje = \"\" \n";
			$this->salida .= "		if(objeto.dosisM.value == \"0\")\n";
			$this->salida .= "			mensaje = \"SE DEBE SELECCIONAR LA PERIODICIDAD DEL MEDICAMENNTO\";\n";
			$this->salida .= "		else\n";
			$this->salida .= "		{\n";
			$this->salida .= "			cadena = objeto.dosisM.value;\n";
			$this->salida .= "			EnviarDatos(cadena)\n";
			$this->salida .= "		}\n";
			$this->salida .= "		document.getElementById('Error').innerHTML = \"<center><b class='label_error'>\"+mensaje+\"</b></center>\";\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function EnviarDatos(doc)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		window.opener.document.formulacion.frecuenciadosis0".$codigo.".value = cadena;\n";
			$this->salida .= "		window.close();\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function Periocidad()
    {
			$sql = "SELECT periocidad_id FROM hc_periocidad ORDER BY periocidad_indice_orden ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$periodos = array();
     	while (!$rst->EOF)
			{
        $periodos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
			}
			
			$rst->Close();
     	return $periodos;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function DosisM()
    {
	 	  $sql = "SELECT descripcion FROM hc_horario ORDER BY descripcion ";
			
     	if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$periodos = array();
     	while (!$rst->EOF)
			{
        $periodos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
			}
			
			$rst->Close();
     	return $periodos;

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
			//$dbconn->debug = true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
	/*********************************************************************************/
	$VISTA='HTML';
	$_ROOT='../../';
	include $_ROOT.'includes/enviroment.inc.php';
		
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	IncludeFile($fileName);

	$frecuencia = new FrecuenciaMedicamentos();
	$frecuencia->Inicializar();
	echo $frecuencia->salida; 
?>