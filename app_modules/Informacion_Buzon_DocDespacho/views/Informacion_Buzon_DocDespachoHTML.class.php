<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id:Informacion_Buzon_DocDespachoHTM.class.php,v 1.0 
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres
	*/

	IncludeClass("ClaseHTML");
    IncludeClass("ClaseUtil");

	class Informacion_Buzon_DocDespachoHTML
	{
	/**
		* Constructor de la clase
	*/

	function  Informacion_Buzon_DocDespachoHTML()
	{}
	/*
		* Funcion donde se crea la forma para el menu de Consulta de Documentos o Parametrizacion
          * @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
        
	*/
		function FormaMenu($action,$cantidad)
		{
			$html  = ThemeAbrirTabla('INFORMACION');
			$ctl = AutoCarga::factory("ClaseUtil");
			$html .= $ctl->RollOverFilas();
			$html .= "<center>\n";
			$html .= "<fieldset class=\"fieldset\" style=\"width:35%\">\n";
			$html .= "<table width=\"95%\" class=\"modulo_table_list\" border=\"1\" align=\"center\" >\n";
			$html .= "  <tr class=\"formulacion_table_list\" >\n";
			$html .= "     <td align=\"center\">MENU\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"normal_10AN\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
			$html .= "      <td  align=\"center\">\n";
			$html .= "        <a href=\"".$action['Informa']."\">DOCUMENTOS DE DESPACHOS (NUEVOS &nbsp;".$cantidad.")</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "	</table>\n";
			$html .= "</fieldset><br>\n";
			$html .= "</center>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
			
		}
		function FormaListarDocumentosDes($action,$datos,$empname)
		{
		
		    $html  = ThemeAbrirTabla('INFORMACION DE DOCUMENTO DE DESPACHO');
			$ctl = AutoCarga::factory("ClaseUtil");
			$html .= $ctl->RollOverFilas();
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend class=\"normal_10AN\" align=\"center\">INFORMACIÒN DE LOS DOCUMENTOS DE DESPACHO GENERADOS </legend>\n";
			$html .= "<form name=\"Forma1\" id=\"Forma1\" method=\"post\" >\n";
			$html  .= "  <table class=\"modulo_table_list\" border=\"0\" align=\"center\" width=\"100%\">\n";
			$html .= "  <tr  class=\"formulacion_table_list\">\n";
			
			$html .= "      <td width=\"25%\" align=\"center\">EMPRESA(HACE EL DESPACHO):\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"5%\" align=\"center\">PREFIJO:\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"5%\" align=\"center\">NUMERO:\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"25%\" align=\"center\">FARMACIA(RECIBE DESPACHO):\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"5%\" align=\"center\">FECHA:\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"25%\" align=\"center\">USUARIO(REALIZO EL DESPACHO):\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"5%\" align=\"center\">OP:\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";
			$html .= "    <BR>\n";
			foreach($datos as $key => $dtl)
			{
				$html .= "  <tr  class=\"modulo_list_claro\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
				$html .= "      <td align=\"center\">".$empname."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">".$dtl['prefijo']."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">".$dtl['numero']."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">".$dtl['razon_social']."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">".$dtl['fecha_registro']."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">".$dtl['nombre']." -&nbsp;&nbsp;".$dtl['descripcion']."\n";
				$html .= "      </td>\n";
			
				$html .= "      <td align=\"center\">\n";
				$html .= "      <a href=\"".$action['verdetalle'].URLRequest(array("empresa"=>$dtl['empresa_id'],"prefijo"=>$dtl['prefijo'],"numero"=>$dtl['numero'],"farmacia_id"=>$dtl['farmacia_id']))."\">\n";
  				$html .= "        <img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\">\n";
  				$html .= "    </a>\n";
				$html .= "			</td>\n";
				
			}
			$html .= "  </table>\n";
			$html .= "  <br>\n";
						
			$html .= "</fieldset><br>\n";
			
			$html .= "<table align=\"RIGHT\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"RIGHT\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['DocRev']."\">.:: DOCUMENTOS DE DESPACHO REVISADOS ::..</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
		    return $html;
		}
		
		function FormaListarDescripcion($action,$datos)
		{
		
		    $html  = ThemeAbrirTabla('INFORMACION DE DOCUMENTO DE DESPACHO DETALLE');
			
			$html .= "<form name=\"Forma1\" id=\"Forma1\" method=\"post\" >\n";
			$html  .= "  <table class=\"modulo_table_list\" border=\"0\" align=\"center\" width=\"95%\">\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"10%\" align=\"center\">CODIGO:\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"45%\" align=\"center\">PRODUCTO:\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"10%\" align=\"center\">CANTIDAD:\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"5%\" align=\"center\">FECHA VENCIMIENTO\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"15%\" align=\"center\">LOTE\n";
			$html .= "      </td>\n";
			
			$html .= "    </tr>\n";
			$html .= "    <BR>\n";
			foreach($datos as $key => $dtl)
			{
				$html .= "    <tr class=\"modulo_list_claro\">\n";
				$html .= "      <td  align=\"center\">".$dtl['codigo_producto']."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">".$dtl['producto']."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">".FormatoValor($dtl['cantidad'])."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">".$dtl['fecha_vencimiento']."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">".$dtl['lote']."\n";
				$html .= "      </td>\n";
				
				
			}
			$html .= "  </table>\n";
			$html .= "  <br>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
		    return $html;
		}
	/*
		*
	*/
		function FormaConsultarInformado($action,$datos,$conteo,$pagina,$request,$empname)
		{
			$ctl = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->IsDate("-");
			$html .= $ctl->AcceptDate("-");
			$html .= $ctl->LimpiarCampos();
			$html  ="  <script>\n";
			$html .= "	  function LimpiarCampos(frm)\n";
			$html .= "	  {\n";
			$html .= "		  for(i=0; i<frm.length; i++)\n";
			$html .= "		  {\n";
			$html .= "			  switch(frm[i].type)\n";
			$html .= "			  {\n";
			$html .= "				  case 'text': frm[i].value = ''; break;\n";
			$html .= "				  case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$html .= "			  }\n";
			$html .= "		  }\n";
			$html .= "	  }\n";
			
            $html .= "		function mOvr(src,clrOver)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrOver;\n";
			$html .= "		}\n";
			$html .= "		function mOut(src,clrIn)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrIn;\n";
			$html .= "		}\n";
            $html .="  </script>\n";
			$html  .= ThemeAbrirTabla('CONSULTAR DOCUMENTOS DESPACHO');
			$html .= "<form name=\"FormaConsultar\" id=\"FormaConsultar\" action=\"".$action['buscador']."\"  method=\"post\" >\n";
			$html .= "<table  width=\"45%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
			$html .= "  <tr class=\"formulacion_table_list\">\n";
			$html .= "		<td width=\"10%\" align=\"left\" >FECHA INICIO:</td>\n";
			$html .= "		<td width=\"10%\" class=\"modulo_list_claro\" align=\"left\">\n";
			$html .= "		  <input type=\"text\" class=\"input-text\" name=\"buscador[fecha_inicio]\"   id=\"fecha_inicio\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\"  >\n";
			$html .= "		</td>\n";
			$html .= "    <td  width=\"15%\" class=\"modulo_list_claro\" >\n";
			$html .= "				".ReturnOpenCalendario('FormaConsultar','fecha_inicio','-')."\n";
			$html .= "		</td>\n";
			$html .= "  </tr >\n";
			$html .= "  <tr class=\"formulacion_table_list\">\n";
			$html .= "		<td width=\"10%\" align=\"left\" >FECHA FINAL:</td>\n";
			$html .= "		<td width=\"10%\" class=\"modulo_list_claro\" align=\"left\">\n";
			$html .= "		    <input type=\"text\" class=\"input-text\"  name=\"buscador[fecha_final]\"  id=\"fecha_final\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" READONLY value=\"\" \n";
			$html .= "		</td>\n";
			$html .= "    <td  class=\"modulo_list_claro\" >\n";
			$html .= "				".ReturnOpenCalendario('FormaConsultar','fecha_final','-')."\n";
			$html .= "		</td>\n";
			$html .= "  </tr >\n";
			
			$html .= "</table>\n";
			$html .= "			<table   width=\"30%\" align=\"center\" border=\"0\"   >";
			$html .= "  <tr>\n";
			$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
			$html .= "			         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"  >\n";
			$html .= "		          	</td>\n";
			$html .= "			<td  colspan=\"10\" align='center' >\n";
			$html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.FormaConsultar)\" value=\"Limpiar Campos\">\n";
			$html .= "	  	</td>\n";
			$html .= "		</tr>\n";
			$html .= "</table><br>\n";
			
			
					
			if(!empty($datos))
			{
				$pghtml = AutoCarga::factory('ClaseHTML');
				$html  .= "  <table class=\"modulo_table_list\" border=\"0\" align=\"center\" width=\"90%\">\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";
			$html .= "      <td width=\"25%\" align=\"center\">EMPRESA(HACE EL DESPACHO):\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"5%\" align=\"center\">PREFIJO:\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"5%\" align=\"center\">NUMERO:\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"25%\" align=\"center\">FARMACIA(RECIBE DESPACHO):\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"5%\" align=\"center\">FECHA:\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"25%\" align=\"center\">USUARIO(REALIZO EL DESPACHO):\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"5%\" align=\"center\">OP:\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";
			$html .= "    <BR>\n";
			foreach($datos as $key => $dtl)
			{
				$html .= "  <tr  class=\"modulo_list_claro\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
				$html .= "      <td align=\"center\">".$empname."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">".$dtl['prefijo']."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">".$dtl['numero']."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">".$dtl['razon_social']."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">".$dtl['fecha_registro']."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">".$dtl['nombre']."- ".$dtl['descripcion']."\n";
				$html .= "      </td>\n";
				
				$html .= "      <td align=\"center\">\n";
				$html .= "      <a href=\"".$action['verdetalleInfor'].URLRequest(array("empresa"=>$dtl['empresa_id'],"prefijo"=>$dtl['prefijo'],"numero"=>$dtl['numero'],"farmacia_id"=>$dtl['farmacia_id']))."\">\n";
				$html .= "        <img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\">\n";
				$html .= "    </a>\n";
				$html .= "			</td>\n";
				
			}
			
				$html .= "	</table><br>\n";
				$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
			}
			else
			{
				if($request)
				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
			}
			$html .= "</form>";
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver'].URLRequest(array( "noid"=>$valor['tercero_id'],"tipoid"=>$valor['tipo_id_tercero']))."\"  class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
	
	
			
	}
?>