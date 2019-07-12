<?php
	/**************************************************************************************
	* $Id: FrecuenciaMedicamentos.class.php,v 1.5 2011/02/17 13:21:27 hugo Exp $ 
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
			//$periodos = $this->Periocidad();
			$this->salida  = "<script>\n";
      $this->salida .= "	function IsNumeric(valor)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var log = valor.length; \n";
			$this->salida .= "		var sw='S';\n";
			$this->salida .= "		var puntos = 0;\n";
			$this->salida .= "		for (x=0; x<log; x++)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			v1 = valor.substr(x,1);\n";
			$this->salida .= "			v2 = parseInt(v1);\n";
			$this->salida .= "			//Compruebo si es un valor numérico\n";
			$this->salida .= "			if(v1 == '.')\n";
			$this->salida .= "			{\n";
			$this->salida .= "				puntos ++;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else if (isNaN(v2)) \n";
			$this->salida .= "			{ \n";
			$this->salida .= "				sw= 'N';\n";
			$this->salida .= "				break;\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		if(log == 0) sw = 'N';\n";
			$this->salida .= "		if(puntos > 1) sw = 'N';\n";
			$this->salida .= "		if(sw=='S') return true;\n";
			$this->salida .= "		return false;\n";
			$this->salida .= "	} \n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
      $this->salida .= "		return (key <= 13 || key == 46 || (key >= 48 && key <= 57));\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<div name=\"Error\" id=\"Error\"></div><br>\n";
			$this->salida .= "<form name=\"frecuencias\" action=\"\" method=\"post\">\n";
			$this->salida .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "  		<td align=\"center\" colspan=\"4\">ADICIONAR FRECUENCIA MEDICAMENTOS</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
			$this->salida .= "			<td align=\"center\"><b>CADA:</b></td>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input type=\"text\" name=\"periodicidad\" class=\"input-text\" value=\"\" onKeyPress=\"return acceptNum(event)\">\n";
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
			$this->salida .= "			<td align=\"center\">\n";
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
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" name=\"buscar\" type=\"button\" value=\"Aceptar\" onclick=\"EvaluarDatos2(document.frecuencias)\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= "<center><input class=\"input-submit\" name=\"buscar\" type=\"button\" value=\"Cerrar\" onclick=\"window.close();\"></center>\n";
			$this->salida .= "<script>\n";

 			$this->salida .= "	var periodo;\n";
			$this->salida .= "	var intensidad;\n";
			$this->salida .= "	var cadena;\n";
			$this->salida .= "	var tratamiento;\n";

			$this->salida .= "	function EvaluarDatos(objeto)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		mensaje = \"\" \n";
			$this->salida .= "		if(objeto.diario.value == \"0\")\n";
			$this->salida .= "			mensaje = \"SE DEBE SELECCIONAR LA PERIODICIDAD DEL MEDICAMENNTO\";\n";
			$this->salida .= "		else\n";
			$this->salida .= "		{\n";
			$this->salida .= "			tratamiento = '';\n";
			$this->salida .= "			periodo = objeto.periodicidad.value;\n";
			$this->salida .= "			intensidad = objeto.diario.value;\n";
			$this->salida .= "			if(!IsNumeric(objeto.periodicidad.value))\n";
      $this->salida .= "        periodo = '';\n";
			$this->salida .= "			cadena = 'Cada '+periodo+' '+intensidad;\n";
			$this->salida .= "			EnviarDatos(cadena);\n";
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
 			$this->salida .= "			periodo = 1;\n";
			$this->salida .= "			intensidad = 'Dia(s)';\n";
			$this->salida .= "			tratamiento = 1;\n";
			$this->salida .= "			cadena = objeto.dosisM.value;\n";
			$this->salida .= "			EnviarDatos(cadena)\n";
			$this->salida .= "		}\n";
			$this->salida .= "		document.getElementById('Error').innerHTML = \"<center><b class='label_error'>\"+mensaje+\"</b></center>\";\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function EnviarDatos(doc)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		window.opener.document.formulacion.Tfrecuenciadosis0".$codigo.".value = cadena;\n";
			$this->salida .= "		window.opener.document.formulacion.frecuenciadosis0".$codigo.".value = cadena;\n";
			$this->salida .= "		window.opener.document.formulacion.frecuencia_Numero".$codigo.".value = periodo;\n";
			$this->salida .= "		window.opener.document.formulacion.frecuencia_Intensidad".$codigo.".value = intensidad;\n";
			
      /* Se comenta  una parte de la condicion if  para que los dias de tratamiento sean editable */
      $this->salida .= "		if (tratamiento == 1)\n";
      $this->salida .= "		{\n";
      $this->salida .= "		  window.opener.document.formulacion.tratamiento".$codigo.".value = tratamiento;\n";
      //$this->salida .= "		  window.opener.document.formulacion.tratamiento".$codigo.".disabled = true;\n";
      $this->salida .= "		}\n";
      $this->salida .= "		else\n";
			$this->salida .= "		  window.opener.document.formulacion.tratamiento".$codigo.".disabled = false;\n";
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