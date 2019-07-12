<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: ESM_ParametrosIniciales_MenuHTML
  * Clase Contiene Metodos para el Ingreso de Parametros Iniciales de Inventario
  *
  * @package IPSOFT-SIIS
  * @version $Revision:
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class ESM_ParametrosIniciales_MenuHTML
	{
		/**
		* Constructor de la clase
		*/
		function ESM_ParametrosIniciales_MenuHTML(){}
		 
     
		function Menu($action)
		{
		$accion=$action['volver'];
		$html  = ThemeAbrirTabla('PARAMETROS INICIALES - INVENTARIOS');
		$html .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$html .= "  <tr><td>";
		$html .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\">";
		$html .= "      MENÚ";
		$html .= "      </td>";
		$html .= "      </tr>";
    
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_ParametrosIniciales','controller','Parametrizar_TipoEvento') ."\">PARAMETRIZAR TIPOS DE EVENTOS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_ParametrosIniciales','controller','Parametrizar_Fuerzas') ."\">PARAMETRIZAR TIPOS DE FUERZAS MILITARES</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_ParametrosIniciales','controller','Parametrizar_ESM') ."\">PARAMETRIZAR ESM (Establecimientos de Sanidad Militar)</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
		
    
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_ParametrosIniciales','controller','Parametrizar_Medico_ESM') ."\">ASOCIAR MEDICOS CON ESMs</a>";
		$html .= "      </td>";
		$html .= "      </tr>";

    
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_ParametrosIniciales','controller','Parametrizar_ProductoClasificacion')."\">PARAMETRIZAR PRODUCTO - CLASIFICACION</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_ParametrosIniciales','controller','Parametrizar_TiposFormulas') ."\">PARAMETRIZAR TIPOS DE FORMULA</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
        
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_ParametrosIniciales','controller','Parametrizar_Topes') ."\">PARAMETRIZAR TOPES POR TIPO - DISPENSACION</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
        		

		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_ParametrosIniciales','controller','Parametrizar_TiposRequisicion') ."\">PARAMETRIZAR TIPOS DE ORDENES REQUISICION</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_ParametrosIniciales','controller','Parametrizar_Afiliados_ESM') ."\">ASOCIAR AFILIADOS A ESMs</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_ParametrosIniciales','controller','Parametrizar_Afiliados_Fuerzas') ."\">ASOCIAR AFILIADOS A FUERZAS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_ParametrosIniciales','controller','Parametrizar_IPS') ."\">PARAMETRIZAR IPS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_ParametrosIniciales','controller','Parametrizar_IPS_ESM') ."\">ASOCIAR IPS A ESMs</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','ESM_ParametrosIniciales','controller','Parametrizar_Medico_IPS') ."\">ASOCIAR MEDICOS CON IPS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";

		
    
		$html .= "      </table>\n";
		$html .= "  </td></tr>";
		$html .= ' 	<form name="forma" action="'.$action['volver'].'" method="post">';
		$html .= "  <tr>";
		$html .= "  <td align=\"center\"><br>";
		$html .= '  <input class="input-submit" type="submit" name="volver" value="Volver">';
		$html .= "  </td>";
		$html .= "  </form>";
		$html .= "  </tr>";
		$html .= "  </table>";
		$html .= ThemeCerrarTabla();
		
		return $html;
	
		}
		
		
		function Forma_Topes($request,$action,$datos,$TiposFormulas)
		{
		/*echo(" <pre> 1 arreglo ".print_r($request, true)." </pre> ");*/
		$ctl = AutoCarga::factory("ClaseUtil"); 
		$html .= $ctl->IsDate("-");
		$html .= $ctl->AcceptDate("-");
		$html .= $ctl->AcceptNum(false);
		$html .= $ctl->LimpiarCampos();
		$html .= $ctl->RollOverFilas();
		$html .= ThemeAbrirTabla('TOPES DE DISPESACION');
		
		$html .= "	<center>";
		$html .= "		<fieldset style=\"width:100%\">";
		$html .= "		<legend class=\"normal_10AN\">";
		$html .= "			BUSCADOR";
		$html .= "		</legend>";
		$html .= "			<form name=\"FormaBuscador\" id=\"FormaBuscador\" action=\"".$action['buscador']."\" method=\"POST\">";
		$html .= "				<table class=\"modulo_table_list\" width=\"100%\">";
		$html .= "					<tr class=\"modulo_list_oscuro\">";
		$html .= "						<td class=\"modulo_table_list_title\">";
		$html .= "							DESCRIPCION FARMACIA";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<input type=\"text\" name=\"buscador[descripcion]\" id=\"descripcion\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['descripcion']."\">";
		$html .= "						</td>";
		$html .= "						<td class=\"modulo_table_list_title\">";
		$html .= "							CODIGO EMPRESA";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<input type=\"text\" name=\"buscador[empresa_id]\" id=\"empresa_id\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['empresa_id']."\">";
		$html .= "						</td>";
		$html .= "						<td class=\"modulo_table_list_title\">";
		$html .= "							CODIGO CENTRO";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<input type=\"text\" name=\"buscador[centro_utilidad]\" id=\"centro_utilidad\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['centro_utilidad']."\">";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "					<tr class=\"modulo_list_oscuro\">";
		$html .= "						<td colspan=\"6\" align=\"center\">";
		$html .= "							<input type=\"submit\" class=\"input-submit\" value=\"BUSCAR FARMACIA\">";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "				</table>";
		$html .= "			</form>";
		$html .= "		</fieldset>";
		$html .= "	</center>";
		$html .= "	<br>";
		
		$html .= "	<form name=\"FormaTope\" method=\"POST\" action=\"".$action['guardar']."\">";
		$html .= "		<table width=\"100%\" class=\"modulo_table_list\">";
		$html .= "			<tr class=\"formulacion_table_list\">";
		$html .= "				<td colspan=\"2\">";
		$html .= "					CENTROS DE UTILIDAD";
		$html .= "				</td>";
		$html .= "			</tr>";
		$i=0;
		$j=0;
		foreach($datos as $key => $valor)
		{
		$arreglo = "";
		$arreglo = explode('@',$key);
		
		if($i==2)
		{
		$html .= "			<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
		$i=0;
		}
		$html .= "				<td>";
		$html .= "					<table width=\"100%\" class=\"modulo_table_list\">";
		$html .= "						<tr class=\"modulo_table_list_title\">";
		$html .= "							<td width=\"10%\">";
		$html .= "								".$arreglo[0]." - ".$arreglo[1];
		$html .= "							</td>";
		$html .= "							<td width=\"80%\" align=\"left\">";
		$html .= "								".$arreglo[2];
		$html .= "							</td>";
		$html .= "						</tr>";
		$html .= "						<tr>";
		$html .= "							<td >";
		$html .= "							</td>";
		$html .= "							<td>";
		$html .= "								<table width=\"100%\">";
		$html .= "									<tr class=\"modulo_table_list_title\">";
		$html .= "										<td>";
		$html .= "											T. DISPENSACION.";
		$html .= "										</td>";
		$html .= "										<td>";
		$html .= "											TOPE MENSUAL";
		$html .= "										</td>";
		$html .= "										<td>";
		$html .= "											SEL.";
		$html .= "										</td>";
		$html .= "									</tr>";
		foreach($TiposFormulas as $k => $v)
			{
		$html .= "									<tr class=\"modulo_list_claro\">";
		$html .= "										<td width=\"70%\">";
		$html .= "											<li>".$v['descripcion']."</li>";
		$html .= "										</td>";
		$html .= "										<td width=\"25%\">";
		$html .= "											<input value=\"".($datos[$key][$v['tipo_formula_id']]['tope_mensual'])."\" type=\"text\" name=\"tope".$j."\" id=\"tope".$j."\" class=\"input-text\" style=\"width:100%\" onkeypress=\"return acceptNum(event)\">";
		$html .= "											<input type=\"hidden\" name=\"empresa_id".$j."\" value=\"".$arreglo[0]."\">";
		$html .= "											<input type=\"hidden\" name=\"centro_utilidad".$j."\" value=\"".$arreglo[1]."\">";
		$html .= "										</td>";
		$html .= "										<td width=\"5%\" align=\"center\">";
		$html .= "											<input type=\"hidden\" id=\"operacion".$j."\"  name=\"operacion".$j."\" value=\"".(($datos[$key][$v['tipo_formula_id']]['operacion']=="")? "0":$datos[$key][$v['tipo_formula_id']]['operacion'])."\">";
		$html .= "											<input ".$datos[$key][$v['tipo_formula_id']]['checked']." type=\"checkbox\" class=\"checkbox\" id=\"tipo_formula_id".$j."\"  name=\"tipo_formula_id".$j."\" value=\"".$v['tipo_formula_id']."\">";
		$html .= "										</td>";
		$html .= "									</tr>";
		$j++;
			}
		$html .= "								</table>";
		$html .= "							</td>";
		$html .= "						</tr>";
		$html .= "					</table>";
		$html .= "				</td>";
		/*$html .= "			<tr>";*/
				$i++;
				
		}
		$html .= "			<tr>";
		$html .= "				<td colspan=\"2\" align=\"center\">";
		$html .= "					<input type=\"submit\" value=\"GUARDAR TOPES\" class=\"input-submit\">";
		$html .= "					<input type=\"hidden\" name=\"registros\" value=\"".$j."\">";
		$html .= "				</td>";
		$html .= "			</tr>";
		
		$html .= "		</table>";
		$html .= "	</form>";
		
		
		
		$html .= " <form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">";
		$html .= "		<table  align=\"center\" width=\"50%\" class=\"modulo_table_list\" border=\"0\">";
		$html .= "  		<tr>";
		$html .= "  			<td align=\"center\">";
		$html .= "  				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$html .= "  			</td>";
		$html .= "  		</tr>";
		$html .= "  	</table>";
		$html .= "  </form>";
		$html .= ThemeCerrarTabla();
		return $html;
		}
 
	}
?>