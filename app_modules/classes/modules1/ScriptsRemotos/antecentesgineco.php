<?php
	/**************************************************************************************
	* $Id: antecentesgineco.php,v 1.1 2006/07/25 20:55:04 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Hugo F. Manrique	
	**************************************************************************************/
	$VISTA = "HTML";
	$_ROOT = "../../../";
	include  "../../../classes/rs_server/rs_server.class.php";
	include	 "../../../includes/enviroment.inc.php";
	include	 "../../../hc_modules/AntecedentesGinecoObstetricos/classes/hc_AntecedentesGinecoObstetricos_AntecedentesGO.class.php";

	class procesos_admin extends rs_server
	{
		function IngresarAntecenteGineco($param)
		{
 			$obj = new procesos_admin();
			$obj->paciente = SessionGetVar("IdPaciente");
			$obj->tipoidpaciente = SessionGetVar("TipoPaciente");
			$antecedente = new AntecedentesGO($obj);
			$result = $antecedente->InsertDatos(SessionGetVar("EvolucionHc"),$param);
			$nivel2 = $antecedente->BusquedaAntecedentesIndividual(SessionGetVar("EvolucionHc"),$param[0],$param[1]);
			
			$html = $this->CrearHtml($nivel2,$param);
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function OcultarAntecenteGineco($param)
		{
			$obj = new procesos_admin();
			$obj->paciente = SessionGetVar("IdPaciente");
			$obj->tipoidpaciente = SessionGetVar("TipoPaciente");
			$antecedente = new AntecedentesGO($obj);
			$result = $antecedente->UpdateDatos($param);
			
			$nivel2 = $antecedente->BusquedaAntecedentesIndividual(SessionGetVar("EvolucionHc"),$param[0],$param[1]);
			$html = $this->CrearHtml($nivel2,$param);
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearHtml($nivel2,$param,$metjs = "OcultarAntecedenteGineco")
		{
			$path = SessionGetVar("RutaImg");
			$i=0;
			
			$htmlO = "";
			$htmlV = "";
			foreach($nivel2 as $nivel3)
			{
				$op = "&nbsp;"; $styl = "style=\"text-transform:capitalize;\"";
				
				$arregloJs = "new Array('".$nivel3['hctap']."','".$nivel3['hctad']."','".$param[2]."','".$param[3]."','".$param[4]."','".$nivel3['hcid']."'";
					
				$check  = "<a href=\"javascript:CrearArregloCapas(new Array('Antecedente".$param[4]."','Ocultos".$param[4]."'));$metjs(".$arregloJs.",'1'))\" title=\"Ocultar Antecedente\">";
				$check .= "	<img src=\"".$path."/images/checkno.png\" height=\"14\" border=\"0\"></a>";
						
				$check1  = "<a href=\"javascript:CrearArregloCapas(new Array('Antecedente".$param[4]."','Ocultos".$param[4]."'));$metjs(".$arregloJs.",'0'))\" title=\"Mostrar Antecedente\">";
				$check1 .= "	<img src=\"".$path."/images/checkS.gif\" height=\"14\" width=\"14\" border=\"0\"></a>";
				
				if($nivel3['sw_riesgo'] == '0') $op = "NO";
				else if($nivel3['sw_riesgo'] == '1') $op = "SI";
				
				if(!$nivel3['detalle'])
				{
					$op = "&nbsp;";$check = "&nbsp;";
				}
				
				if($nivel3['destacar'] == '1') $styl = " style=\"font-weight : bold; text-transform:capitalize;\" ";
				
				$styl1 = " style=\"color:#000066;font-weight : bold; \" ";
				if($nivel3['riesgo'] == $nivel3['sw_riesgo']) $styl1 = " style=\"color:#C40000;font-weight : bold; \" ";
				
				if($nivel3['ocultar'] == '0')
				{
					$htmlV .= "									<tr class=\"".$param[2]."\">\n";
					$htmlV .= "										<td align=\"center\" $styl1 width=\"15%\"  >$op</td>\n";
					$htmlV .= "										<td align=\"justify\" $styl width=\"55%\" >".$nivel3['detalle']."</td>\n";
					$htmlV .= "										<td align=\"center\" $styl width=\"20%\">".$nivel3['fecha']."</td>\n";
					$htmlV .= "										<td align=\"center\" $styl width=\"10%\">$check</td>\n";
					$htmlV .= "									</tr>\n";
				}
				else if($nivel3['ocultar'] == '1')
				{
					$htmlO .= "						<tr class=\"".$param[3]."\">\n";
					$htmlO .= "							<td align=\"center\" $styl1 width=\"15%\"  >$op</td>\n";
					$htmlO .= "							<td align=\"justify\" $styl width=\"55%\" >".$nivel3['detalle']."</td>\n";
					$htmlO .= "							<td align=\"center\" $styl width=\"20%\">".$nivel3['fecha']."</td>\n";
					$htmlO .= "							<td align=\"center\" $styl width=\"10%\">$check1</td>\n";
					$htmlO .= "						</tr>\n";
				}
				
				$i++;
			}
			
			if($htmlV != "")
			{
				$htmlV  = "								<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n".$htmlV;			
				$htmlV .= "								</table>\n";
			}
			if($htmlO != "")
			{
				$htmlO  = "								<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n".$htmlO;
				$htmlO .= "								</table>\n";
			}
			
			$html .= "								<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n";
			$html .= "									<tr class=\"formulacion_table_list\" >\n";
			$html .= "										<td align=\"center\" width=\"15%\" >OP.</td>\n";
			$html .= "										<td align=\"center\" width=\"55%\" >DETALLE</td>\n";
			$html .= "										<td align=\"center\" width=\"20%\" >F. REGIS</td>\n";				
			$html .= "										<td align=\"center\" width=\"10%\" >OCUL</td>\n";
			$html .= "									</tr>\n";
			$html .= "								</table>\n";
			
			return $htmlV."ç".$htmlO."ç".$html;
		}
	}
	$oRS = new procesos_admin( array( 'ActivarMenu', 'CrearTabla'));
	$oRS->action();	
?>