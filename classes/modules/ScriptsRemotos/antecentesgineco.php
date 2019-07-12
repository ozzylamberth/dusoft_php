<?php
	/**************************************************************************************
	* $Id: antecentesgineco.php,v 1.1.1.1 2010/03/30 15:15:44 hugo Exp $ 
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
			$antecedente = new AntecedentesGO();
			$result = $antecedente->InsertDatos(SessionGetVar("Evolucion"),$param);
			$nivel2 = $antecedente->BusquedaAntecedentesIndividual(SessionGetVar("Evolucion"),$param[0],$param[1]);
			
			$html = $this->CrearHtml($nivel2,$param);
			return $html;
		}

		function IngresarAntecenteGinecoPyp($param)
		{
			$antecedente = new AntecedentesGO();
;
			if(!$param[9])
			{
				$result1 = $antecedente->UpdateDatosPyp($param);
			}
			else
			{
				$result2 = $antecedente->InsertDatosPyp(SessionGetVar("Evolucion"),$param);
			}
			
			$nivel2 = $antecedente->BusquedaAntecedentesIndividualPyp(SessionGetVar("Evolucion"),$param[1],$param[0]);
			
			$html = $this->CrearHtmlPyp($nivel2,$param);
			$htmlP = $this->Puntaje($param);
			
			return $html."ç".$htmlP;
		}
		
		
		/********************************************************************************
		*
		*********************************************************************************/
		function OcultarAntecenteGineco($param)
		{
			$antecedente = new AntecedentesGO();
			$result = $antecedente->UpdateDatos($param);
			
			$nivel2 = $antecedente->BusquedaAntecedentesIndividual(SessionGetVar("Evolucion"),$param[0],$param[1]);
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
			return $htmlV."ç".$htmlO."ç".$html;
		}
	
		function CrearHtmlPyp($nivel2,$param)
		{
			$i=0;
			$tabla1="";
			$tabla0= "";
			$punto=0;
			
			$path = SessionGetVar("RutaImg");
			foreach($nivel2 as $nivel3)
			{
				$ed="";
				$op = "&nbsp;"; $styl = "style=\"text-transform:capitalize;\"";
				
				if($nivel3['sw_riesgo'] == '0') $op = "NO";
				else if($nivel3['sw_riesgo'] == '1') $op = "SI";
				
				if(!$nivel3['detalle'])
				{
					$op = "&nbsp;";$check = "&nbsp;";
				}
					
				if($nivel3['destacar'] == '1') $styl = " style=\"font-weight : bold; text-transform:capitalize;\" ";
				
				$styl1 = " style=\"color:#000066;font-weight : bold; \" ";
				if($nivel3['riesgo'] == $nivel3['sw_riesgo']) $styl1 = " style=\"color:#C40000;font-weight : bold; \" ";
	
				if(SessionGetVar("EvolucionHc")==$nivel3['evolucion_id'])
				{
					$arregloj = "new Array('".$nivel3['hctag']."','".$nivel3['pypan']."','".$nivel3['pypang']."','".$nivel3['pypid']."','".$nivel3['detalle']."','".$nivel3['sw_riesgo']."','".$nivel3['destacar']."','".$param[7]."','".$param[8]."','0')";
					
					if($nivel3['pypan']==5)
					{
							$ed= "			<a href=\"javascript:MostrarSpan('PypCpn1');IniciarPyp1('".$nivel3['nombre_tipo']."',$arregloj);Update1($arregloj)\" class=\"label\"><img src=\"".$path."/images/editar.png\" border=\"0\"></a>\n";
					}
					else
					{
							$ed= "			<a href=\"javascript:MostrarSpan('PypCpn');IniciarPyp('".$nivel3['nombre_tipo']."',$arregloj);Update($arregloj)\" class=\"label\"><img src=\"".$path."/images/editar.png\" border=\"0\"></a>\n";
					}
				}
				
				$tabla0.= "									<tr class=\"hc_submodulo_list_claro\">\n";
				$tabla0.= "										<td align=\"center\" $styl1 width=\"15%\"  >$op</td>\n";
				$tabla0.= "										<td align=\"justify\" $styl width=\"55%\" >".$nivel3['detalle']."</td>\n";
				$tabla0.= "										<td align=\"center\" $styl width=\"20%\">".$nivel3['fecha']."</td>\n";
				$tabla0.= "										<td align=\"center\" $styl width=\"20%\">$ed</td>\n";
				$tabla0.= "									</tr>\n";

				$i++;
			}
			
			$tabla1 .= "								<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n";
			$tabla1 .= " 									".$tabla0;
			$tabla1 .= "								</table>\n";
			
			return $tabla1;
		}

		function Puntaje($param)
		{
			$html="";
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			
			$antecedente = new AntecedentesGO();
			$puntaje=$antecedente->ObtenerPuntajeAsociado($evolucion,$inscripcion);
			$puntaje+=$param[8];
			$_SESSION['puntaje_gineco']=$puntaje;
			$html= "	PUNTAJE: $puntaje";
			return $html;
		}
		
	}
	$oRS = new procesos_admin( array( 'ActivarMenu', 'CrearTabla'));
	$oRS->action();	
?>