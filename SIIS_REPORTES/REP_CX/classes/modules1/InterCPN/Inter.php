<?php
	/**************************************************************************************
	* $Id: Inter.php,v 1.1 2006/12/07 21:26:16 luis Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Hugo F. Manrique	
	**************************************************************************************/
	$VISTA = "HTML";
	$_ROOT = "../../../";
	include  "../../../classes/rs_server/rs_server.class.php";
	include	 "../../../includes/enviroment.inc.php";
	include	 ".../../../classes/modules/hc_classmodules.class.php";
	$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
	IncludeFile($filename);
	include	 "../../../hc_modules/Interconsulta/classes/hc_Interconsulta_SolicitudInterconsultas.class.php";
	
	class procesos_admin extends rs_server
	{
		function SolicitudInter($datos)
		{
			$evolucion=SessionGetVar("Evolucion");
			$inter=new SolicitudInterconsultas();
			
			if($inter->Insertar_Especialidad($datos,$evolucion))
			{
				$html=$this->CreaHTML($datos);
				return $html;
			}
			else
			{
				echo $inter->ErrorDB();
			}
			
			return "";
		}
		
		function CreaHTML($datos)
		{
			$html="";
			$html.="<label class=\"label\">Si</label>";
			$html.="<input type=\"hidden\" name=\"".$datos[5]."\" value=\"1ç".$datos[6]."\">";
			return $html;
		}
		
		
		function TraerForma($datos)
		{
			$html="";
			$html .= "			<table  align=\"center\" border=\"0\"  width=\"60%\">";
			$html .= "				<tr class=\"modulo_table_title\">";
			$html .= "  				<td align=\"center\" colspan=\"3\">DATOS DE LA SOLICITUD DE INTERCONSULTA</td>";
			$html .= "				</tr>";
			$html .= "				<tr class=\"hc_table_submodulo_list_title\">";
			$html .= "  				<td width=\"15%\">CODIGO DE ESPECIALIDAD</td>";
			$html .= "  				<td width=\"60%\">ESPECIALIDAD</td>";
			$html .= "  				<td width=\"5%\">CANTIDAD SOLICITADA</td>";
			$html .= "				</tr>";
      $html .="					<tr class=\"modulo_list_oscuro\">";
      $html .="  					<td align=\"center\">".$datos[0]."</td>";
      $html .="  					<td align=\"center\">".$datos[1]."</td>";	
			$html .="  					<td align=\"center\"><input type=\"text\" name=\"cantidad\" class=\"input-text\" size=\"5\" maxlength=\"3\" value=\"1\"></td>";		
      $html .="					</tr>";
			$html .="					<tr class=\"modulo_list_claro\">";
      $html .="  					<td align=\"center\" width=\"15%\">OBSERVACION</td>";
			$html .="						<td width=\"65%\" colspan=\"2\" align=\"center\"><textarea class=\"textarea\" name=\"observacion\" cols=\"50\" rows=\"3\"></textarea></td>";
			$html .="					</tr>";
			$html .="					<tr class=\"modulo_list_oscuro\">";
			$html .="						<td align=\"center\" colspan=\"3\"><input class=\"input-submit\" name=\"guardar\" type=\"button\" value=\"GUARDAR\" onclick=\"Evaluar()\"></td>";
			$html .="					</tr>";
			$html .="				</table>";
			
			return $html;
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
	$oRS = new procesos_admin(array( 'ActivarMenu', 'CrearTabla'));
	$oRS->action();	
?>