<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */
  /**
  * Clase Vista: UnidadesNegocioHTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */
	class UnidadesNegocioHTML
	{
		/**
		* Constructor de la clase
		*/
		function UnidadesNegocioHTML(){}
		/*
		* Funcion donde se crea la Forma del Contrato a llenar  
		* @param array $datos vector que contiene la informacion de Los Proveedores
		* @param array $action vector que contiene los link de la aplicacion
		* @param array $datos_empresa vector que contiene la informacion de Empresa que contrata al proveedor
		* @param string $fecha contiene la informacion de la fecha actual.
		* @return string $html retorna la cadena con el codigo html de la pagina
		*/ 
		
		function Forma($action,$Listado_UnidadesNegocio,$conteo, $pagina)
		{
			$html  = ThemeAbrirTabla(' UNIDADES DE NEGOCIO ');
			//Que solo aparezca Cuando NO hay un contrato activo y/o existente para ese tercero selecionado
			$_ROOT=GetBaseURL();
			$ctl = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->IsDate("-");
			$html .= $ctl->AcceptDate("-");
			$html .= $ctl->AcceptNum("-");
			$html .= $ctl->LimpiarCampos();
			$html .= $ctl->RollOverFilas();
			$html .="<script >\n";
			$html .= "  function max(e){  ";
			$html .= "  tecla = (document.all) ? e.keyCode : e.which; ";
			$html .= "  if (tecla==8) return true;";
			$html .= "  if (tecla==13) return false;";
			$html .= " }";
			$html .= "</script>\n";
			
			$html .= "<table class=\"modulo_table_list\" rules=\"all\" align=\"center\">";
			$html .= "	<tr class=\"modulo_list_claro\">";
			$html .= "    <td align=\"center\" class=\"normal_10AN\">\n";
			$html .= "		NUEVA UNIDAD DE NEGOCIO";
			$html .= "    </td>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "		<center class=\"label_error\"><a href=\"#\" onclick=\"xajax_Formulario_UnidadesNegocio('---');\"><img src=\"".GetThemePath()."/images/hcinicio.png\" border='0'></a></center>";
			$html .= "    </td>\n";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= "<br>";
			
			$html .= "<form name=\"Buscador\" id=\"Buscador\" action=\"".$action['buscador']."\" method=\"post\">";
			$html .= "<table  width=\"50%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "  <tr align=\"center\" >\n";
			$html .= "      <td  class=\"formulacion_table_list\">CODIGO UNIDAD DE NEGOCIO</td>\n";
			$html .= "      <td  class=\"formulacion_table_list\"><input name=\"buscador[codigo_unidad_negocio]\" id=\"buscador[codigo_unidad_negocio]\" class=\"input-text\" style=\"width:100%\"></td>\n";
			$html .= "      <td  class=\"formulacion_table_list\">NOMBRE</td>\n";
			$html .= "      <td  class=\"formulacion_table_list\"><input name=\"buscador[descripcion]\" id=\"buscador[descripcion]\" class=\"input-text\" style=\"width:100%\"></td>\n";
			$html .= "	</tr>";
			$html .= "  <tr align=\"center\" >\n";
			$html .= "      <td  class=\"formulacion_table_list\" colspan=\"6\"><input type=\"submit\" value=\"BUSCAR UNIDAD DE NEGOCIO\" class=\"input-submit\"></td>\n";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= "</form>";
			$html .= "<br>";
			$pgn = AutoCarga::factory("ClaseHTML");
			$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
			$html .= "                  <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "                    <tr class=\"modulo_table_list_title\">\n";
			$html .= "                      <td align=\"center\" width=\"6%\">\n";
			$html .= "                        <a title=''>CODIGO </a>";
			$html .= "                      </td>\n";
			$html .= "                      <td align=\"center\" >\n";
			$html .= "                        <a title='NOMBRE'>NOMBRE</a>";
			$html .= "                      </td>\n";
			$html .= "                      <td align=\"center\" >\n";
			$html .= "                        <a title='IMAGEN'>IMAGEN</a>";
			$html .= "                      </td>\n";
			$html .= "                      <td align=\"center\" width=\"3%\">\n";
			$html .= "                        <a title='MODIFICAR'>MOD.<a>";
			$html .= "                      </td>\n";
			$html .= "                      <td align=\"center\" width=\"3%\">\n";
			$html .= "                        <a title='ESTADO'>EST.<a>";
			$html .= "                      </td>\n";
			$html .= "                    </tr>\n";
			foreach($Listado_UnidadesNegocio as $key => $valor)
			{
			$html .= "                  <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
			$html .= "						<td>".$valor['codigo_unidad_negocio']."</td>";
			$html .= "						<td>".$valor['descripcion']."</b></td>";
			$html .= "						<td>";
			$html .= "							<table class=\"modulo_table_list\" width=\"100%\">";
			$html .= "								<tr>";
			$html .= "									<td class=\"label\">IMAGEN:</td>";
			$html .= "									<td>/images/<b>".$valor['imagen']."</b></td>";
			$html .= "								</tr>";
			$html .= "								<tr align=\"center\">";
			$html .= "									<td colspan=\"2\"><img src=\"".$_ROOT."/images/".$valor['imagen']."\" border='0'></td>";
			$html .= "								</tr>";
			$html .= "							</table>";
			$html .= "						</td>";
			$html .= "						<td>";
			$html .= "						<center><a href=\"#\" class=\"label_error\" title=\"MODIFICAR UNIDAD DE NEGOCIO\" onclick=\"xajax_Formulario_UnidadesNegocio('".$valor['codigo_unidad_negocio']."');\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></center>\n";
			$html .= "						</td>";
			if($valor['estado']=='1')
				{
			$html .= "						<td>";
			$html .= "						<center><a onClick=\"return confirm('INACTIVAR LA UNIDAD DE NEGOCIO: ".$valor['descripcion']."?');\" href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','UnidadesNegocio',array("codigo_unidad_negocio"=>$valor['codigo_unidad_negocio'],"cambiar_estado"=>"0","buscador"=>$_REQUEST['buscador']))."\" class=\"label_error\"  title=\"UNIDAD DE NEGOCIO ACTIVA, CAMBIAR A INACTIVA\"><img src=\"".GetThemePath()."/images/pactivo.png\" border='0'></a></center>\n";
			$html .= "						</td>";
				}
			else
				{
			$html .= "						<td>";
			$html .= "							<center><a onClick=\"return confirm('ACTIVAR LA UNIDAD DE NEGOCIO: ".$valor['descripcion']."?');\" href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','UnidadesNegocio',array("codigo_unidad_negocio"=>$valor['codigo_unidad_negocio'],"cambiar_estado"=>"1","buscador"=>$_REQUEST['buscador']))."\" class=\"label_error\"  title=\"UNIDAD DE NEGOCIO INACTIVA, CAMBIAR A ACTIVA\"><img title=\"CONTRATO INACTIVO\" src=\"".GetThemePath()."/images/pinactivo.png\" border='0' ></a></center>\n";
			$html .= "						</td>";
				}
			$html .= "                   </tr>\n";
			}
			$html .= "				</table>";
			
			$html .= "<br>";
			
			
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
			$html .= "        VOLVER\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			
			$html .= ThemeCerrarTabla();
			$html .= $this->CrearVentana('600','UNIDADES DE NEGOCIO');
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
		$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
		$html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
		$html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
		$html .= "  <div id='Contenido' class='d2Content'>\n";
		//En ese espacio se visualiza la informacion extraida de la base de datos.
		$html .= "  </div>\n";
		$html .= "</div>\n";



		return $html;
		} 	
	
  }
?>