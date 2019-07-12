<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: MensajesModuloHTML.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: MensajesModuloHTML
  * Clase encargada de crear las formas para mostrar el menu principal del modulo 
  * y los mensajes al usuario
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class MensajesModuloHTML	
	{
		/**
		* Constructor de la clase
		*/
		function MensajesModuloHTML(){}
		
		
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action Vector que continen los link de la aplicacion
		*
		* @return string
		*/
		function FormaMenuInicial($action)
		{
		$html  = ThemeAbrirTabla('AUTORIZACION AJUSTES - INVENTARIOS');
		$html .= "<table width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "	<tr>\n";
		$html .= "		<td align=\"center\" class=\"formulacion_table_list\" >";
		$html .= "			<b style=\"color:#ffffff\">MENU PRINCIPAL";
		$html .= "		</td>\n";
		$html .= "	<tr class=\"modulo_list_claro\">\n";
		$html .= "		<td align=\"center\">";
		$html .= "            <a href=\"".$action['autorizar']."\"><b>AUTORIZAR AJUSTES - INVENTARIOS</b></a>\n";
		$html .= "		</td>\n";
		$html .= "	</tr>\n";
		$html .= "	<tr class=\"modulo_list_claro\">\n";
		$html .= "		<td align=\"center\">";
		$html .= "            <a href=\"".$action['auditoria']."\"><b>AUDITORIA AJUSTES - INVENTARIOS</b></a>\n";
		$html .= "		</td>\n";
		$html .= "	</tr>\n";
		$html .= "	<tr class=\"modulo_list_claro\">\n";
		$html .= "		<td align=\"center\">";
		$html .= "            <a href=\"".$action['autorizar_despachos']."\"><b>AUTORIZAR DESPACHOS- CLIENTES Y FARMACIAS</b></a>\n";
		$html .= "		</td>\n";
		$html .= "	</tr>\n";
		$html .= "	<tr class=\"modulo_list_claro\">\n";
		$html .= "		<td align=\"center\"><br>\n";
		$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
		$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
		$html .= "			</form>";
		$html .= "		</td>";
		$html .= "	</tr>";
		$html .= "</table>";
		$html .= ThemeCerrarTabla();			
		return $html;
		}
		
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action Vector que continen los link de la aplicacion
		*
		* @return string
		*/
		function FormaAutorizarAjustes($action,$request,$datos,$conteo, $pagina)
		{
		$ctl = AutoCarga::factory("ClaseUtil");
		$pgn = AutoCarga::factory("ClaseHTML");
		$html  = $ctl->LimpiarCampos();
		$html .= $ctl->RollOverFilas();
		$html .= $ctl->AcceptDate('/');
		$html .= $ctl->AcceptNum(false);
		$html .= ThemeAbrirTabla('AUTORIZACION DE AJUSTES DE INVENTARIO');
		
		$html .= "<center>";
		$html .= "	<form name=\"buscador\" id=\"buscador\" method=\"POST\">";
		$html .= "		<fieldset style=\"width:50%\">";
		$html .= "			<legend class=\"normal_10AN\">BUSCADOR</legend>";
		$html .= "			<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
		$html .= "				<tr class=\"modulo_list_oscuro\">";
		$html .= "					<td class=\"normal_10AN\">";
		$html .= "						#DOC TEMPORAL";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<input type=\"text\" name=\"buscador[doc_tmp_id]\" id=\"doc_tmp_id\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['doc_tmp_id']."\">";
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "				<tr class=\"modulo_list_oscuro\">";
		$html .= "					<td class=\"normal_10AN\">";
		$html .= "						USUARIO";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<input type=\"text\" name=\"buscador[usuario]\" id=\"usuario\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['usuario']."\">";
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "				<tr class=\"modulo_list_oscuro\">";
		$html .= "					<td colspan=\"2\">";
		$html .= "							<table class=\"modulo_table_list\" width=\"100%\">";
		$html .= "            					<tr>\n";
		$html .= "              					<td class=\"normal_10AN\">FECHA INICIAL</td>\n";
		$html .= "              					<td>\n";
		$html .= "                						<input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['buscador']['fecha_inicio']."\" style=\"width:60%\">\n";
		$html .= "              					</td>\n";
		$html .= "		          					<td align=\"left\" class=\"label\" >".ReturnOpenCalendario('buscador','fecha_inicio','/',1)."</td>\n";
		$html .= "            					</tr>\n";
		$html .= "            					<tr>\n";
		$html .= "              					<td class=\"normal_10AN\">FECHA FINAL</td>\n";
		$html .= "              					<td>\n";
		$html .= "                						<input type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['buscador']['fecha_final']."\" style=\"width:60%\">\n";
		$html .= "              					</td>\n";
		$html .= "		          					<td align=\"left\" class=\"label\" >".ReturnOpenCalendario('buscador','fecha_final','/',1)."</td>\n";
		$html .= "            					</tr>\n";
		$html .= "							</table>";
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "				<tr class=\"modulo_list_oscuro\">";
		$html .= "					<td colspan=\"2\" align=\"center\">";
		$html .= "						<input type=\"submit\" class=\"input-submit\" value=\"BUSCAR\">";
		$html .= "					</td >";
		$html .= "				</tr>";
		$html .= "			</table>";
		$html .= "		</fieldset>";
		$html .= "	</form>";
		$html .= "</center>";
		
		$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
		$html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "	<tr class=\"formulacion_table_list\">";
		$html .= "		<td>";
		$html .= "			EMPRESA - CENTRO - BODEGA";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			DESCRIPCION";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			USUARIO";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			FECHA";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			OBSERVACION";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			C. INT";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			J. BOD";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			AUT.";
		$html .= "		</td>";
		$html .= "	</tr>";
		foreach($datos as $key =>$valor)
		{
		$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
		$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
        $html .= "	<tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
		$html .= "		<td>";
		$html .= "			".$valor['razon_social'];
		$html .= "			-".$valor['descripcion_centro'];
		$html .= "			-".$valor['descripcion_bodega'];
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			".$valor['descripcion'];
		$html .= "			-".$valor['prefijo'];
		$html .= "			: <b class=\"normal_10AN\">#TMP</b> - <b class=\"label_error\">".$valor['doc_tmp_id'];
		$html .= "				</b>";
		$html .= "		</td>";
		
		$html .= "		<td>";
		$html .= "			".$valor['usuario_id'];
		$html .= "			-".$valor['nombre'];
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			".$valor['fecha_registro'];
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			".$valor['observacion'];
		$html .= "		</td>";
		$html .= "		<td align=\"center\">";
		$html .= "			<img title=\"".$valor['titulo_control']."\" src=\"".GetThemePath()."/images/".$valor['icono_control_interno']."\">\n";
		$html .= "		</td>";
		$html .= "		<td align=\"center\">";
		$html .= "			<img title=\"".$valor['titulo_jefe']."\" src=\"".GetThemePath()."/images/".$valor['icono_jefe_bodega']."\">\n";
		$html .= "		</td>";
		$html .= "		<td align=\"center\">";
		$url = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','DocumentoAutorizar',array("documento"=>array("usuario_id"=>$valor['usuario_id'],"doc_tmp_id"=>$valor['doc_tmp_id'])));
		$html .= "			<a href=\"".$url."\" >";
		$html .= "				<img src=\"".GetThemePath()."/images/pautorizacion.png\" border=\"0\">\n";
		$html .= "			</a>";
		$html .= "		</td>";
		$html .= "	</tr>";
		}
		$html .= "</table>";
		
		$html .= "<table width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "	<tr>\n";
		$html .= "		<td align=\"center\"><br>\n";
		$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
		$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
		$html .= "			</form>";
		$html .= "		</td>";
		$html .= "	</tr>";
		$html .= "</table>";
		$html .= ThemeCerrarTabla();			
		return $html;
		}
		
		function DocumentoAutorizar($action,$datos,$empresa)
		{
		$ctl = AutoCarga::factory("ClaseUtil");
		$pgn = AutoCarga::factory("ClaseHTML");
		$html  = $ctl->LimpiarCampos();
		$html .= $ctl->RollOverFilas();
		$html .= $ctl->AcceptDate('/');
		$html .= $ctl->AcceptNum(false);
		$html .= ThemeAbrirTabla('AUTORIZAR - AJUSTES DE INVENTARIO');
		
		$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "		<tr class=\"formulacion_table_list\">";
		$html .= "			<td colspan=\"4\">";
		$html .= "				DOCUMENTO DE AJUSTE PARA AUTORIZAR";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				DESCRIPCION DOCUMENTO";
		$html .= "			</td>";
		$html .= "			<td  colspan=\"3\" class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['descripcion'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				LOCALIZACION";
		$html .= "			</td>";
		$html .= "			<td  colspan=\"3\" class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['razon_social'].": ".$datos['descripcion_centro']." - ".$datos['descripcion_bodega'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				#DOC. TMP ID";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['doc_tmp_id'];
		$html .= "			</td>";
		$html .= "			<td align=\"left\">";
		$html .= "				USUARIO";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['usuario_id']."-".$datos['nombre'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				PREFIJO - DOCUMENTO";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\" colspan=\"3\">";
		$html .= "				".$datos['prefijo'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				FECHA - TEMPORAL";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\" colspan=\"3\">";
		$html .= "				".$datos['fecha_registro'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				OBSERVACION";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\" colspan=\"3\">";
		$html .= "				".$datos['observacion'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				CONTROL INTERNO";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['control_interno'];
		$html .= "			</td>";
		$html .= "			<td align=\"left\">";
		$html .= "				COORDINADOR AUXILIAR - ESTABLECIMIENTO";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['coordinador_auxiliar'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				AUT. CONTROL INTERNO";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"center\">";
		if($datos['usuario_control_interno']=="" && $empresa['tipo_usuario']=='0')
		$html .= "				<a href=\"".$action['control_interno']."\">";
		$html .= "					<img border=\"0\" title=\"".$datos['titulo_control']."\" src=\"".GetThemePath()."/images/".$datos['icono_control_interno']."\">\n";
		$html .= "				</a>";
		$html .= "			</td>";
		$html .= "			<td align=\"left\">";
		$html .= "				AUT. JEFE BODEGA";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"center\">";
		if($datos['usuario_jefe_bodega']=="" && $empresa['tipo_usuario']=='1')
		$html .= "				<a href=\"".$action['jefe_bodega']."\">";
		$html .= "					<img border=\"0\" title=\"".$datos['titulo_jefe']."\" src=\"".GetThemePath()."/images/".$datos['icono_jefe_bodega']."\">\n";
		$html .= "				</a>";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"formulacion_table_list\">";
		$html .= "			<td colspan=\"4\">";
		$html .= "				PRODUCTOS";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr>";
		$html .= "			<td colspan=\"4\" class=\"modulo_list_claro\">";
		$html .= "				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "					<tr class=\"modulo_table_list_title\">";
		$html .= "						<td>";
		$html .= "							CODIGO PRODUCTO";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							DESCRIPCION";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							CANTIDAD";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							LOTE";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							FECHA VENCIMIENTO";
		$html .= "						</td>";
		$html .= "					</tr>";
		foreach($datos['documento'] as $key =>$valor)
		{
		$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
		$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
        $html .= "					<tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
		$html .= "						<td>";
		$html .= "							".$valor['codigo_producto'];
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							".$valor['producto'];
		$html .= "						</td>";
		$html .= "						<td align=\"center\" class=\"label\">";
		$html .= "							".FormatoValor($valor['cantidad']);
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							".$valor['lote'];
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							".$valor['fecha_vencimiento'];
		$html .= "						</td>";
		$html .= "					</tr>";
		}
		$html .= "				</table>";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "	</table>";
		
		$html .= "<br>";
		$html .= "<table width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "	<tr>\n";
		$html .= "		<td align=\"center\"><br>\n";
		$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
		$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
		$html .= "			</form>";
		$html .= "		</td>";
		$html .= "	</tr>";
		$html .= "</table>";
		$html .= ThemeCerrarTabla();	
		return $html;
		}
		
		
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action Vector que continen los link de la aplicacion
		*
		* @return string
		*/
		function FormaAuditoriaAjustes($action,$request,$datos,$conteo, $pagina)
		{
		$ctl = AutoCarga::factory("ClaseUtil");
		$pgn = AutoCarga::factory("ClaseHTML");
		$html  = $ctl->LimpiarCampos();
		$html .= $ctl->RollOverFilas();
		$html .= $ctl->AcceptDate('/');
		$html .= $ctl->AcceptNum(false);
		$html .= ThemeAbrirTabla('AUDITORIA DE AJUSTES DE INVENTARIO');
		
		$html .= "<center>";
		$html .= "	<form name=\"buscador\" id=\"buscador\" method=\"POST\">";
		$html .= "		<fieldset style=\"width:50%\">";
		$html .= "			<legend class=\"normal_10AN\">BUSCADOR</legend>";
		$html .= "			<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
		$html .= "				<tr class=\"modulo_list_oscuro\">";
		$html .= "					<td class=\"normal_10AN\">";
		$html .= "						USUARIO DOCUMENTO";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<input type=\"text\" name=\"buscador[usuario]\" id=\"usuario\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['usuario']."\">";
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "				<tr class=\"modulo_list_oscuro\">";
		$html .= "					<td colspan=\"2\">";
		$html .= "							<table class=\"modulo_table_list\" width=\"100%\">";
		$html .= "            					<tr>\n";
		$html .= "              					<td class=\"normal_10AN\">FECHA INICIAL</td>\n";
		$html .= "              					<td>\n";
		$html .= "                						<input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['buscador']['fecha_inicio']."\" style=\"width:60%\">\n";
		$html .= "              					</td>\n";
		$html .= "		          					<td align=\"left\" class=\"label\" >".ReturnOpenCalendario('buscador','fecha_inicio','/',1)."</td>\n";
		$html .= "            					</tr>\n";
		$html .= "            					<tr>\n";
		$html .= "              					<td class=\"normal_10AN\">FECHA FINAL</td>\n";
		$html .= "              					<td>\n";
		$html .= "                						<input type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['buscador']['fecha_final']."\" style=\"width:60%\">\n";
		$html .= "              					</td>\n";
		$html .= "		          					<td align=\"left\" class=\"label\" >".ReturnOpenCalendario('buscador','fecha_final','/',1)."</td>\n";
		$html .= "            					</tr>\n";
		$html .= "							</table>";
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "				<tr class=\"modulo_list_oscuro\">";
		$html .= "					<td colspan=\"2\" align=\"center\">";
		$html .= "						<input type=\"submit\" class=\"input-submit\" value=\"BUSCAR\">";
		$html .= "					</td >";
		$html .= "				</tr>";
		$html .= "			</table>";
		$html .= "		</fieldset>";
		$html .= "	</form>";
		$html .= "</center>";
		
		
		$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
		$html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "	<tr class=\"formulacion_table_list\">";
		$html .= "		<td>";
		$html .= "			EMPRESA - CENTRO - BODEGA";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			DESCRIPCION";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			USUARIO";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			FECHA";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			OBSERVACION";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			AUD.";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			OP.";
		$html .= "		</td>";
		$html .= "	</tr>";
		foreach($datos as $key =>$valor)
		{
		$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
		$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
        $html .= "	<tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
		$html .= "		<td>";
		$html .= "			".$valor['razon_social'];
		$html .= "			-".$valor['descripcion_centro'];
		$html .= "			-".$valor['descripcion_bodega'];
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			".$valor['descripcion'];
		$html .= "			-(".$valor['prefijo']."-".$valor['numero'].")";
		$html .= "		</td>";
		
		$html .= "		<td>";
		$html .= "			".$valor['usuario_documento'];
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			".$valor['fecha_registro'];
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			".$valor['observacion'];
		$html .= "		</td>";
		$html .= "		<td align=\"center\">";
		$html .= "			<img title=\"".$valor['titulo_auditado']."\" src=\"".GetThemePath()."/images/".$valor['icono_auditado']."\">\n";
		$html .= "		</td>";
		$html .= "		<td align=\"center\">";
		$url = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','DocumentoAuditoria',array(	"documento"=>array("empresa_id"=>$valor['empresa_id'],
																																														"prefijo"=>$valor['prefijo'],
																																														"numero"=>$valor['numero'])));
		$html .= "			<a href=\"".$url."\" >";
		$html .= "				<img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\">\n";
		$html .= "			</a>";
		$html .= "		</td>";
		$html .= "	</tr>";
		}
		$html .= "</table>";
		
		$html .= "<table width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "	<tr>\n";
		$html .= "		<td align=\"center\"><br>\n";
		$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
		$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
		$html .= "			</form>";
		$html .= "		</td>";
		$html .= "	</tr>";
		$html .= "</table>";
		$html .= ThemeCerrarTabla();			
		return $html;
		}
		
		function DocumentoAuditar($action,$datos,$empresa)
		{
		$ctl = AutoCarga::factory("ClaseUtil");
		$pgn = AutoCarga::factory("ClaseHTML");
		$html  = $ctl->LimpiarCampos();
		$html .= $ctl->RollOverFilas();
		$html .= $ctl->AcceptDate('/');
		$html .= $ctl->AcceptNum(false);
		$html .= ThemeAbrirTabla('AUDITAR - AJUSTES DE INVENTARIO');
		
		$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "		<tr class=\"formulacion_table_list\">";
		$html .= "			<td colspan=\"4\">";
		$html .= "				DOCUMENTO DE AJUSTE PARA AUTORIZAR";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				DESCRIPCION DOCUMENTO";
		$html .= "			</td>";
		$html .= "			<td  colspan=\"3\" class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['descripcion'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				LOCALIZACION";
		$html .= "			</td>";
		$html .= "			<td  colspan=\"3\" class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['razon_social'].": ".$datos['descripcion_centro']." - ".$datos['descripcion_bodega'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				#DOC. ";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['prefijo']." - ".$datos['numero'];
		$html .= "			</td>";
		$html .= "			<td align=\"left\">";
		$html .= "				USUARIO";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['usuario_documento'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				FECHA - TEMPORAL";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\" colspan=\"3\">";
		$html .= "				".$datos['fecha_registro'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				TOTAL COSTO";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\" colspan=\"3\">";
		$html .= "				<b class=\"normal_10AN\">$".FormatoValor($datos['total_costo'],2)."</b>";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				OBSERVACION";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\" colspan=\"3\">";
		$html .= "				".$datos['observacion'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				CONTROL INTERNO";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['control_interno'];
		$html .= "			</td>";
		$html .= "			<td align=\"left\">";
		$html .= "				COORDINADOR AUXILIAR - ESTABLECIMIENTO";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['coordinador_auxiliar'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$propiedad=explode("@",$datos['propiedades']);
		$html .= "		<tr class=\"modulo_list_claro\" >";
		$html .= "			<td align=\"left\" colspan=\"4\">";
		$html .= "				<form name=\"auditoria_documento\" id=\"auditoria_documento\" method=\"POST\" action=\"".$action['auditoria']."\">";
		$html .= "					<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">";
		$html .= "						<tr class=\"formulacion_table_list\">";
		$html .= "							<td colspan=\"2\">";
		$html .= "								AUDITORIA: AJUSTE INVENTARIOS";
		$html .= "							</td>";
		$html .= "						</tr>";
		$html .= "						<tr >";
		$html .= "							<td class=\"normal_10AN\" align=\"center\">";
		$html .= "								OBSERVACION";
		$html .= "							</td>";
		$html .= "							<td>";
		$html .= "								<textarea ".$propiedad[0]." name=\"auditoria[observacion_auditoria]\" id=\"observacion_auditoria\" class=\"textarea\" style=\"width:100%\">".$datos['observacion_auditoria']."</textarea>";
		$html .= "							</td>";
		$html .= "						</tr>";
		$html .= "						<tr >";
		$html .= "							<td class=\"normal_10AN\" align=\"center\">";
		$html .= "								USUARIO AUDITOR";
		$html .= "							</td>";
		$html .= "							<td>";
		$html .= "								".$datos['usuario_auditor'];
		$html .= "							</td>";
		$html .= "						</tr>";
		$html .= "						<tr >";
		$html .= "							<td class=\"normal_10AN\" align=\"center\">";
		$html .= "								FECHA AUDITORIA";
		$html .= "							</td>";
		$html .= "							<td>";
		$html .= "								".$datos['fecha_auditoria'];
		$html .= "							</td>";
		$html .= "						</tr>";
		$html .= "						<tr class=\"modulo_table_list_title\">";
		$html .= "							<td colspan=\"2\">";
		$html .= "								<input ".$propiedad[1]." type=\"submit\" value=\"".$datos['boton_auditado']."\" class=\"input-submit\">";
		$html .= "							</td>";
		$html .= "						</tr>";
		$html .= "					</table>";
		$html .= "				</form>";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"formulacion_table_list\">";
		$html .= "			<td colspan=\"4\">";
		$html .= "				PRODUCTOS";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr>";
		$html .= "			<td colspan=\"4\" class=\"modulo_list_claro\">";
		$html .= "				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "					<tr class=\"modulo_table_list_title\">";
		$html .= "						<td>";
		$html .= "							CODIGO PRODUCTO";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							DESCRIPCION";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							CANTIDAD";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							LOTE";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							FECHA VENCIMIENTO";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							TOTAL COSTO";
		$html .= "						</td>";
		$html .= "					</tr>";
		foreach($datos['documento'] as $key =>$valor)
		{
		$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
		$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
        $html .= "					<tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
		$html .= "						<td>";
		$html .= "							".$valor['codigo_producto'];
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							".$valor['producto'];
		$html .= "						</td>";
		$html .= "						<td align=\"center\" class=\"label\">";
		$html .= "							".FormatoValor($valor['cantidad']);
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							".$valor['lote'];
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							".$valor['fecha_vencimiento'];
		$html .= "						</td>";
		$html .= "						<td class=\"normal_10AN\">";
		$html .= "							$".FormatoValor($valor['total_costo'],2);
		$html .= "						</td>";
		$html .= "					</tr>";
		}
		$html .= "				</table>";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "	</table>";
		
		$html .= "<br>";
		$html .= "<table width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "	<tr>\n";
		$html .= "		<td align=\"center\"><br>\n";
		$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
		$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
		$html .= "			</form>";
		$html .= "		</td>";
		$html .= "	</tr>";
		$html .= "</table>";
		$html .= ThemeCerrarTabla();	
		return $html;
		}
		
			
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action Vector que continen los link de la aplicacion
		*
		* @return string
		*/
		function FormaAutorizarDespachos($action,$request,$datos,$conteo, $pagina)
		{
		$ctl = AutoCarga::factory("ClaseUtil");
		$pgn = AutoCarga::factory("ClaseHTML");
		$html  = $ctl->LimpiarCampos();
		$html .= $ctl->RollOverFilas();
		$html .= $ctl->AcceptDate('/');
		$html .= $ctl->AcceptNum(false);
		$html .= ThemeAbrirTabla('AUTORIZACION DE DESPACHOS A CLIENTES/FARMACIAS');
		
		$html .= "<center>";
		$html .= "	<form name=\"buscador\" id=\"buscador\" method=\"POST\">";
		$html .= "		<fieldset style=\"width:50%\">";
		$html .= "			<legend class=\"normal_10AN\">BUSCADOR</legend>";
		$html .= "			<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
		$html .= "				<tr class=\"modulo_list_oscuro\">";
		$html .= "					<td class=\"normal_10AN\">";
		$html .= "						#DOC TEMPORAL";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<input type=\"text\" name=\"buscador[doc_tmp_id]\" id=\"doc_tmp_id\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['doc_tmp_id']."\">";
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "				<tr class=\"modulo_list_oscuro\">";
		$html .= "					<td class=\"normal_10AN\">";
		$html .= "						#PEDIDO";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<input type=\"text\" name=\"buscador[pedido_id]\" id=\"pedido_id\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['pedido_id']."\">";
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "				<tr class=\"modulo_list_oscuro\">";
		$html .= "					<td class=\"normal_10AN\">";
		$html .= "						USUARIO";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<input type=\"text\" name=\"buscador[usuario]\" id=\"usuario\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['usuario']."\">";
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "				<tr class=\"modulo_list_oscuro\">";
		$html .= "					<td colspan=\"2\">";
		$html .= "							<table class=\"modulo_table_list\" width=\"100%\">";
		$html .= "            					<tr>\n";
		$html .= "              					<td class=\"normal_10AN\">FECHA INICIAL</td>\n";
		$html .= "              					<td>\n";
		$html .= "                						<input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['buscador']['fecha_inicio']."\" style=\"width:60%\">\n";
		$html .= "              					</td>\n";
		$html .= "		          					<td align=\"left\" class=\"label\" >".ReturnOpenCalendario('buscador','fecha_inicio','/',1)."</td>\n";
		$html .= "            					</tr>\n";
		$html .= "            					<tr>\n";
		$html .= "              					<td class=\"normal_10AN\">FECHA FINAL</td>\n";
		$html .= "              					<td>\n";
		$html .= "                						<input type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['buscador']['fecha_final']."\" style=\"width:60%\">\n";
		$html .= "              					</td>\n";
		$html .= "		          					<td align=\"left\" class=\"label\" >".ReturnOpenCalendario('buscador','fecha_final','/',1)."</td>\n";
		$html .= "            					</tr>\n";
		$html .= "							</table>";
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "				<tr class=\"modulo_list_oscuro\">";
		$html .= "					<td colspan=\"2\" align=\"center\">";
		$html .= "						<input type=\"submit\" class=\"input-submit\" value=\"BUSCAR\">";
		$html .= "					</td >";
		$html .= "				</tr>";
		$html .= "			</table>";
		$html .= "		</fieldset>";
		$html .= "	</form>";
		$html .= "</center>";
		
		$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
		$html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "	<tr class=\"formulacion_table_list\">";
		$html .= "		<td>";
		$html .= "			EMPRESA - CENTRO - BODEGA";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			DESCRIPCION";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			USUARIO";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			FECHA";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			DATO ADICIONAL";
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			AUT.";
		$html .= "		</td>";
		$html .= "	</tr>";
		foreach($datos as $key =>$valor)
		{
		$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
		$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
        $html .= "	<tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
		$html .= "		<td>";
		$html .= "			".$valor['razon_social'];
		$html .= "			-".$valor['descripcion_centro'];
		$html .= "			-".$valor['descripcion_bodega'];
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			".$valor['descripcion'];
		$html .= "			-".$valor['prefijo'];
		$html .= "			: <b class=\"normal_10AN\">#TMP</b> - <b class=\"label_error\">".$valor['doc_tmp_id'];
		$html .= "				</b>";
		$html .= "		</td>";
		
		$html .= "		<td>";
		$html .= "			".$valor['usuario_id'];
		$html .= "			-".$valor['nombre'];
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			".$valor['fecha_registro'];
		$html .= "		</td>";
		$html .= "		<td>";
		$html .= "			".$valor['cliente'];
		$html .= "			<b class=\"label_error\">#Pedido:</b> ".$valor['numero_pedido'];
		$html .= "		</td>";
		$html .= "		<td align=\"center\">";
		$url = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','DocumentoAutorizar_Despacho',array("documento"=>array("usuario_id"=>$valor['usuario_id'],"doc_tmp_id"=>$valor['doc_tmp_id'])));
		$html .= "			<a href=\"".$url."\" >";
		$html .= "				<img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\">\n";
		$html .= "			</a>";
		$html .= "		</td>";
		$html .= "	</tr>";
		}
		$html .= "</table>";
		
		$html .= "<table width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "	<tr>\n";
		$html .= "		<td align=\"center\"><br>\n";
		$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
		$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
		$html .= "			</form>";
		$html .= "		</td>";
		$html .= "	</tr>";
		$html .= "</table>";
		$html .= ThemeCerrarTabla();			
		return $html;
		}
		
		
		
		function DocumentoAutorizar_Despachos($action,$datos,$productos_autorizar,$empresa,$request)
		{
		$ctl = AutoCarga::factory("ClaseUtil");
		$pgn = AutoCarga::factory("ClaseHTML");
		$html  = $ctl->LimpiarCampos();
		$html .= $ctl->RollOverFilas();
		$html .= $ctl->AcceptDate('/');
		$html .= $ctl->AcceptNum(false);
		
		/*FUNCIONES JAVASCRIPT*/
		$html .= "	<script>";
		$html .= "		function AsignarInformacion(producto,codigo_producto,lote,fecha_vencimiento,cantidad,total_costo)";
		$html .= "		{";
		$html .= "			document.getElementById('nombre_producto').innerHTML=producto;";
		$html .= "			document.getElementById('codigo_producto').innerHTML=codigo_producto;";
		$html .= "			document.getElementById('lote_producto').innerHTML=lote;";
		$html .= "			document.getElementById('fecha_vencimiento_producto').innerHTML=fecha_vencimiento;";
		$html .= "			document.getElementById('cantidad_producto').innerHTML=cantidad;";
		$html .= "			document.getElementById('total_costo_producto').innerHTML=total_costo;";
		
		$html .= "			document.getElementById('codigo_producto').value=codigo_producto;";
		$html .= "			document.getElementById('lote').value=lote;";
		$html .= "			document.getElementById('fecha_vencimiento').value=fecha_vencimiento;";
		$html .= "		}";
		$html .= "		function Validar_Campos(Formulario)";
		$html .= "		{";
		$html .= "			if(document.getElementById('observacion').value.replace(/^\s+/g,'').replace(/\s+$/g,'') ==\"\")";
		$html .= "			{";
		$html .= "				alert('DEBE DILIGENCIAR LA OBSERVACION PARA AUTORIZAR EL DESPACHO');";
		$html .= "				return false;";
		$html .= "			}";
		$html .="		Formulario.submit();";
		$html .= "		}";
		$html .= "	</script>";
		/*FIN FUNCIONES JAVASCRIPT*/
		
		$html .= ThemeAbrirTabla('AUTORIZAR - DESPACHOS A CLIENTES/FARMACIAS');
		$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "		<tr class=\"formulacion_table_list\">";
		$html .= "			<td colspan=\"4\">";
		$html .= "				DOCUMENTO DE DESPACHO";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				DESCRIPCION DOCUMENTO";
		$html .= "			</td>";
		$html .= "			<td  colspan=\"3\" class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['descripcion'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				LOCALIZACION";
		$html .= "			</td>";
		$html .= "			<td  colspan=\"3\" class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['razon_social'].": ".$datos['descripcion_centro']." - ".$datos['descripcion_bodega'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				CLIENTE";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['cliente'];
		$html .= "			</td>";
		$html .= "			<td align=\"left\">";
		$html .= "				NUMERO PEDIDO";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				<B class=\"label_error\">".$datos['numero_pedido']."</B>";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				#DOC. TMP ID";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['doc_tmp_id'];
		$html .= "			</td>";
		$html .= "			<td align=\"left\">";
		$html .= "				USUARIO";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\">";
		$html .= "				".$datos['usuario_id']."-".$datos['nombre'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				PREFIJO - DOCUMENTO";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\" colspan=\"3\">";
		$html .= "				".$datos['prefijo'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				FECHA - TEMPORAL";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\" colspan=\"3\">";
		$html .= "				".$datos['fecha_registro'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"modulo_table_list_title\" >";
		$html .= "			<td align=\"left\">";
		$html .= "				OBSERVACION";
		$html .= "			</td>";
		$html .= "			<td class=\"modulo_list_oscuro\" align=\"left\" colspan=\"3\">";
		$html .= "				".$datos['observacion'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"formulacion_table_list\">";
		$html .= "			<td colspan=\"4\">";
		$html .= "				PRODUCTOS";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr>";
		$html .= "			<td colspan=\"4\" class=\"modulo_list_claro\">";
		$html .= "				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "					<tr class=\"modulo_table_list_title\">";
		$html .= "						<td>";
		$html .= "							CODIGO PRODUCTO";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							DESCRIPCION";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							CANTIDAD";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							LOTE";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							FECHA VENCIMIENTO";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							%IVA";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							TOTAL COSTO";
		$html .= "						</td>";
		$html .= "					</tr>";
		foreach($datos['documento'] as $key =>$valor)
		{
		$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
		$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
        $html .= "					<tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
		$html .= "						<td>";
		$html .= "							".$valor['codigo_producto'];
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							".$valor['producto'];
		$html .= "						</td>";
		$html .= "						<td align=\"center\" class=\"label\">";
		$html .= "							".FormatoValor($valor['cantidad']);
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							".$valor['lote'];
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							".$valor['fecha_vencimiento'];
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							%".$valor['porcentaje_gravamen'];
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							$".FormatoValor($valor['total_costo'],2);
		$html .= "						</td>";
		$html .= "					</tr>";
		}
		$html .= "				</table>";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "	</table>";
		
		$html .= "<br>";
		
		
		$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "		<tr class=\"formulacion_table_list\">";
		$html .= "			<td >";
		$html .= "				PRODUCTOS :AUTORIZACION";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr >";
		$html .= "			<td >";
		$html .= "				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "					<tr class=\"modulo_table_list_title\">";
		$html .= "						<td>";
		$html .= "							CODIGO PRODUCTO";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							DESCRIPCION";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							CANTIDAD";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							LOTE";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							FECHA VENCIMIENTO";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							%IVA";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							TOTAL COSTO";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							OP";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							EL";
		$html .= "						</td>";
		$html .= "					</tr>";
		foreach($productos_autorizar as $k =>$v)
		{
		$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
		$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
        $html .= "					<tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
		$html .= "						<td>";
		$html .= "							".$v['codigo_producto'];
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							".$v['producto'];
		$html .= "						</td>";
		$html .= "						<td align=\"center\" class=\"label\">";
		$html .= "							".FormatoValor($v['cantidad']);
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							".$v['lote'];
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							".$v['fecha_vencimiento'];
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							%".$v['porcentaje_gravamen'];
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							$".FormatoValor($v['total_costo'],2);
		$html .= "						</td>";
		
		$html .= "						<td align=\"center\">";
		if($v['sw_autorizado']=='0')
		$html .= "							<a href=\"javascript:MostrarSpan();AsignarInformacion('".$v['codigo_producto']." - ".$v['producto']."','".$v['codigo_producto']."','".$v['lote']."','".$v['fecha_vencimiento']."','".FormatoValor($v['cantidad'])."','$".FormatoValor($v['total_costo'],2)."')\" >";
		$html .= "								<img src=\"".GetThemePath()."/images/".$v['icono']."\" border=\"0\">\n";
		if($v['sw_autorizado']=='0')
		$html .= "							</a>";
		$html .= "						</td>";
		
		$url=ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','DocumentoAutorizar_Despacho',array(	"documento"=>
																																											array("usuario_id"=>$request['documento']['usuario_id'],
																																											"doc_tmp_id"=>$request['documento']['doc_tmp_id']),
																																											"campo"=>"usuario_control_interno",
																																											"eliminar_autorizacion"=>array("codigo_producto"=>$v['codigo_producto'],
																																											"lote"=>$v['lote'],
																																											"fecha_vencimiento"=>$v['fecha_vencimiento'],
																																											"empresa_id"=>$empresa['empresa_id'],
																																											"centro_utilidad"=>$empresa['centro_utilidad'],
																																											"bodega"=>$empresa['bodega'],
																																											"eliminar"=>"1")
																																											));	
		
		$html .= "						<td align=\"center\">";
		if($v['sw_autorizado']=='0')
		$html .= "							<a href=\"".$url."\" >";
		$html .= "								<img onClick=\"return confirm('DESEA ELIMINAR LA SOLICITUD DE AUTORIZACION DE: ".$v['codigo_producto']."-".$v['producto']." LOTE: ".$v['lote']." FV: ".$v['fecha_vencimiento']."?');\" title=\"ELIMINAR SOLICITUD AUTORIZACION\" src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\">\n";
		if($v['sw_autorizado']=='0')
		$html .= "							</a>";
		$html .= "						</td>";
		$html .= "					</tr>";
		}
		$html .= "				</table>";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "	</table>";
		
		
	  
      $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
      $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">AUTORIZACION DE DESPACHO</div>\n";
      $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
      $html .= "  <div id='Contenido' class='d2Content'>\n";
	  /*FORMULARIO DE REGISTRO*/
	  $html .= "		<form name=\"FormaAutorizar\" id=\"FormaAutorizar\" method=\"POST\" action=\"".$action['autorizar']."\"  onSubmit=\"Validar_Campos(document.FormaAutorizar); return false;\">";
	  $html .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
	  $html .= "				<tr class=\"formulacion_table_list\">";
	  $html .= "					<td colspan=\"2\">";
	  $html .= "						AUTORIZACION DE DESPACHO DE PRODUCTOS";
	  $html .= "					</td>";
	  $html .= "				</tr>";
	  $html .= "				<tr class=\"modulo_table_list_title\">";
	  $html .= "					<td align=\"left\" width=\"50%\">";
	  $html .= "						PRODUCTO";
	  $html .= "						<input type=\"hidden\" name=\"autorizar[codigo_producto]\" id=\"codigo_producto\" value=\"\">";
	  $html .= "						<input type=\"hidden\" name=\"autorizar[lote]\" id=\"lote\" value=\"\">";
	  $html .= "						<input type=\"hidden\" name=\"autorizar[fecha_vencimiento]\" id=\"fecha_vencimiento\" value=\"\">";
	  $html .= "						<input type=\"hidden\" name=\"autorizar[empresa_id]\" id=\"empresa_id\" value=\"".trim($empresa['empresa_id'])."\">";
	  $html .= "						<input type=\"hidden\" name=\"autorizar[centro_utilidad]\" id=\"centro_utilidad\" value=\"".trim($empresa['centro_utilidad'])."\">";
	  $html .= "						<input type=\"hidden\" name=\"autorizar[bodega]\" id=\"bodega\" value=\"".trim($empresa['bodega'])."\">";
	  $html .= "						<input type=\"hidden\" name=\"autorizar[autorizar]\" id=\"autorizar\" value=\"1\">";
	  $html .= "					</td>";
	  $html .= "					<td align=\"left\" class=\"modulo_list_claro\" id=\"nombre_producto\">";
	  $html .= "					</td>";
	  $html .= "				<tr class=\"modulo_table_list_title\">";
	  $html .= "					<td align=\"left\" width=\"50%\">";
	  $html .= "						CANTIDAD";
	  $html .= "					</td>";
	  $html .= "					<td align=\"left\" class=\"modulo_list_claro\" id=\"cantidad_producto\">";
	  $html .= "					</td>";
	  $html .= "				</tr>";
	  $html .= "				<tr class=\"modulo_table_list_title\">";
	  $html .= "					<td align=\"left\" width=\"50%\">";
	  $html .= "						LOTE";
	  $html .= "					</td>";
	  $html .= "					<td align=\"left\" class=\"modulo_list_claro\" id=\"lote_producto\">";
	  $html .= "					</td>";
	  $html .= "				</tr>";
	  $html .= "				<tr class=\"modulo_table_list_title\">";
	  $html .= "					<td align=\"left\" width=\"50%\">";
	  $html .= "						FECHA VENCIMIENTO";
	  $html .= "					</td>";
	  $html .= "					<td align=\"left\" class=\"modulo_list_claro\" id=\"fecha_vencimiento_producto\">";
	  $html .= "					</td>";
	  $html .= "				</tr>";
	  $html .= "				<tr class=\"modulo_table_list_title\">";
	  $html .= "					<td align=\"left\" width=\"50%\">";
	  $html .= "						TOTAL COSTO";
	  $html .= "					</td>";
	  $html .= "					<td align=\"left\" class=\"modulo_list_claro\" id=\"total_costo_producto\">";
	  $html .= "					</td>";
	  $html .= "				</tr>";
	  $html .= "				<tr class=\"modulo_table_list_title\">";
	  $html .= "					<td align=\"left\" width=\"50%\">";
	  $html .= "						OBSERVACION";
	  $html .= "					</td>";
	  $html .= "					<td align=\"left\" class=\"modulo_list_claro\" >";
	  $html .= "						<textarea style=\"width:100%\" name=\"autorizar[observacion]\" id=\"observacion\" class=\"textarea\"></textarea>";
	  $html .= "					</td>";
	  $html .= "				</tr>";
	  $html .= "				<tr class=\"modulo_table_list_title\">";
	  $html .= "					<td colspan=\"2\">";
	  $html .= "						<input type=\"submit\" class=\"input-submit\" value=\"AUTORIZAR DESPACHO DEL PRODUCTO\">";
	  $html .= "					</td>";
	  $html .= "				</tr>";
	  $html .= "			</table>";
	  $html .= "		</form>";
      /*FIN FORMULARIO DE REGISTRO*/
      $html .= "  </div>\n";
      $html .= "</div>\n"; 
		
		
		
		$html .= "<br>";
		$html .= "<table width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "	<tr>\n";
		$html .= "		<td align=\"center\"><br>\n";
		$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
		$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
		$html .= "			</form>";
		$html .= "		</td>";
		$html .= "	</tr>";
		$html .= "</table>";
		$html .= ThemeCerrarTabla();	
		$html .= $this->CrearVentana('640','AUTORIZACION DE DESPACHO');
		return $html;
		}
		
		
		function CrearVentana($tmn,$Titulo)
    {
      $html .= "<script>\n";
      $html .= "  var contenedor = 'Contenedor';\n";
      $html .= "  var titulo = 'titulo';\n";
      $html .= "  var hiZ = 4;\n";
      $html .= "  function OcultarSpan()\n";
      $html .= "  { \n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      e = xGetElementById('Contenedor');\n";
      $html .= "      e.style.display = \"none\";\n";
      $html .= "    }\n";
      $html .= "    catch(error){}\n";
      $html .= "  }\n";
      //Mostrar Span
	  $html .= "  function MostrarSpan()\n";
      $html .= "  { \n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      e = xGetElementById('Contenedor');\n";
      $html .= "      e.style.display = \"\";\n";
      $html .= "      Iniciar();\n";
      $html .= "    }\n";
      $html .= "    catch(error){alert(error)}\n";
      $html .= "  }\n";

      $html .= "  function MostrarTitle(Seccion)\n";
      $html .= "  {\n";
      $html .= "    xShow(Seccion);\n";
      $html .= "  }\n";
      $html .= "  function OcultarTitle(Seccion)\n";
      $html .= "  {\n";
      $html .= "    xHide(Seccion);\n";
      $html .= "  }\n";

      $html .= "  function Iniciar()\n";
      $html .= "  {\n";
      $html .= "    contenedor = 'Contenedor';\n";
      $html .= "    titulo = 'titulo';\n";
      $html .= "    ele = xGetElementById('Contenido');\n";
      $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
      $html .= "    ele = xGetElementById(contenedor);\n";
      $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
      $html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
      $html .= "    ele = xGetElementById(titulo);\n";
      $html .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
      $html .= "    xMoveTo(ele, 0, 0);\n";
      $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $html .= "    ele = xGetElementById('cerrar');\n";
      $html .= "    xResizeTo(ele,20, 20);\n";
      $html .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
      $html .= "  }\n";

      $html .= "  function myOnDragStart(ele, mx, my)\n";
      $html .= "  {\n";
      $html .= "    window.status = '';\n";
      $html .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
      $html .= "    else xZIndex(ele, hiZ++);\n";
      $html .= "    ele.myTotalMX = 0;\n";
      $html .= "    ele.myTotalMY = 0;\n";
      $html .= "  }\n";
      $html .= "  function myOnDrag(ele, mdx, mdy)\n";
      $html .= "  {\n";
      $html .= "    if (ele.id == titulo) {\n";
      $html .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
      $html .= "    }\n";
      $html .= "    else {\n";
      $html .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
      $html .= "    }  \n";
      $html .= "    ele.myTotalMX += mdx;\n";
      $html .= "    ele.myTotalMY += mdy;\n";
      $html .= "  }\n";
      $html .= "  function myOnDragEnd(ele, mx, my)\n";
      $html .= "  {\n";
      $html .= "  }\n";
      
      
      $html.= "function Cerrar(Elemento)\n";
           $html.= "{\n";
           $html.= "    capita = xGetElementById(Elemento);\n";
           $html.= "    capita.style.display = \"none\";\n";
           $html.= "}\n";
      
      
      $html .= "</script>\n";
      /*
      $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
      $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
      $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
      $html .= "  <div id='Contenido' class='d2Content'>\n";*/
      /*En ese espacio se visualiza la informacion extraida de la base de datos.*/
      /*$html .= "  </div>\n";
      $html .= "</div>\n";*/

      $html .= "<div id='Contenedor2' class='d2Container' style=\"display:none;z-index:4\">\n";
      $html .= "  <div id='titulo2' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
      $html .= "  <div id='cerrar2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
      $html .= "  <div id='Contenido2' class='d2Content'>\n";
      //En ese espacio se visualiza la informacion extraida de la base de datos.
      $html .= "  </div>\n";
      $html .= "</div>\n";

      return $html;
    }    
		
  }
?>