<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: Compras_Orden_ComprasHTML.class.php,v 1.0 
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres
	*/

	IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");

	class Compras_Orden_ComprasHTML
	{
	/**
		* Constructor de la clase
	*/
	function  Compras_Orden_ComprasHTML()
	{}
	/*
	* Funcion donde se crea la forma para el menu la Rotacion Productos
          * @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
        
	*/
		function FormaMenu($action)
		{
			
			$html  = ThemeAbrirTabla('COMPRAS');
			$html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\">MENU\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td   class=\"label\" align=\"center\">\n";
			$html .= "        <a href=\"".$action['Pre-orden']."\">BUSCAR PRE-ORDEN</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td   class=\"label\" align=\"center\">\n";
			$html .= "        <a href=\"".$action['BuscarPre']."\">CONSULTAR ORDEN DE COMPRAS</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td align=\"center\">\n";
			$html .= "         <a href=\"#\" onclick=\"xajax_EmpresaOrdenPedido()\"  class=\"label_error\">CREAR DOCUMENTO POR PRODUCTOS PENDIENTES(ORDEN-COMPRA)</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			/*$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td   class=\"label\" align=\"center\">\n";
			$html .= "        <a href=\"".$action['buscarDoc']."\">BUSCAR DOCUMENTO (Asignar a un Pedido)</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";*/
						
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= $this->CrearVentana(650,"PROVEEDORES");
			$html .= ThemeCerrarTabla();
		    return $html;
		}
	
/*
		* Funcion donde se crea una Forma con una Ventana con capas para mostrar informacion
		* en pantalla
		* @param int $tmn Tamaño que tendra la ventana
		* @return string
    */
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
			$html .= "  </div>\n";
			$html .= "</div>\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor2' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "  <div id='titulo2' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
			$html .= "  <div id='cerrar2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "  <div id='Contenido2' class='d2Content'>\n";
			$html .= "  </div>\n";
			$html .= "</div>\n";
            return $html;
        }    
		function FormaBuscarDocumento($action,$request,$datos,$conteo,$pagina)
		{
			$ctl = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->IsDate("-");
			$html .= $ctl->AcceptDate("-");
			$html .= $ctl->LimpiarCampos();
			$bodegades = SessionGetVar("bodegaDesc");
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
			$html  .= ThemeAbrirTabla('CONSULTAR PRE-ORDEN');
			$html .= "<form name=\"FormaConsultar\" id=\"FormaConsultar\" action=\"".$action['buscador']."\"  method=\"post\" >\n";
			$html .= "<table  width=\"45%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
		
			$html .= "  <tr  colspan=\"5\" class=\"modulo_table_list_title\" >\n";
			$html .= "      <td align=\"left\">NUMERO PRE-ORDEN:</td>\n";
			$html .= "      <td colspan=\"5\" align=\"left\"  class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\"  name=\"buscador[orden]\"  id=\"txtncontrato\"   value=\"\" size=\"30%\" maxlength=\"30\" >\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  colspan=\"5\" class=\"modulo_table_list_title\" >\n";
			$html .= "      <td align=\"left\">FARMACIA:</td>\n";
			$html .= "      <td colspan=\"5\" align=\"left\"  class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\"  name=\"buscador[farmacia]\"  id=\"txtncontrato\"   value=\"\" size=\"30%\" maxlength=\"30\" >\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
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
				$html .= "  <table width=\"95%\" class=\"modulo_table_list_title\" align=\"center\">";
				$html .= "	  <tr align=\"CENTER\"    class=\"modulo_list_claro\" >\n";
				$html .= "      <td    width=\"10%\">NUMERO DOC.</td>\n";
                $html .= "      <td   width=\"25%\">EMPRESA</td>\n";
				$html .= "      <td   width=\"25%\">OBSERVACION</td>\n";
				$html .= "      <td  width=\"30%\">USUARIO</td>\n";
				$html .= "      <td   width=\"15%\">FECHA REGISTRO</td>\n";
				$html .= "	      <td  width=\"12%\">";
				$html .= "      INFORMACION</td>\n";
				$html .= "  </tr>\n";
							
                $est = "modulo_list_claro"; $back = "#DDDDDD";
				foreach($datos as $key => $dtl)
				{
          $html .= "	  <tr  align=\"CENTER\" class=\"".$est."\" >\n";
          $html .= "      <td   align=\"center\">".$dtl['preorden_id']."</td>\n";
          $html .= "      <td align=\"left\">".$dtl['razon_social']."</td>\n";
          $html .= "      <td align=\"left\">".$dtl['observacion']."</td>\n";
          $html .= "      <td align=\"left\">".$dtl['nombre']."</td>\n";
          $html .= "      <td align=\"left\">".$dtl['fecha_registro']."</td>\n";
					$mdl = AutoCarga::factory("Compras_orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
					$inft=$mdl->consultarSw_Unificados($dtl['preorden_id']);
					if(!empty($inft))
					{
						$html .= "      <td align=\"center\">\n";
						$html .= "      <a href=\"".$action['detalle'].URLRequest(array("preorden_id"=>$dtl['preorden_id'],"farmacia_id"=>$dtl['farmacia_id']))."\">\n";
						$html .= "       VER\n";
						$html .= "    </a>\n";
					    $html .= "			</td>\n";
					}
					else
					{
					
						$html .= "      <td align=\"center\">\n";
					    $html .= "    OK";
						$html .= "			</td>\n";
					}					
				}
				$html .= "			</tr>\n";
				$html .= "	</table><br>\n";
				
				$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
			}
			else
			{
				if($request)
				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
			}
			
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver'].URLRequest(array( "bodegades"=>$bodegades))."\"  class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
		
		function FormaDetalleDocumento($action,$dat,$datos,$conteo,$pagina,$preorden_id,$empresa)
		{
			
			$html  ="  <script>\n";
			$html .="    function ValidarInformaOrdenCompra(frm)";
			$html .="    {";
			$html .= " 	   xajax_InformacionOrdenComp(document.getElementById('proveedor').value,'".$preorden_id."',document.getElementById('empresa').value,'".$empresa."'); ";
			$html .= "      return;\n";
			$html .="   }";
			$html .= " function ValidarDtos(frm,preorden_id,orden_pedido_id,empresa)";
			$html .= " {" ;
			$html .= "    xajax_TrasferirInformacion(frm.observar.value,preorden_id,orden_pedido_id,empresa); ";
			$html .= "      return;\n";
			$html .="   }";
			$html .="  </script>\n";
			$html  .= ThemeAbrirTabla('DETALLE DEL DOCUMENTO DE PRE -ORDEN');
			$html .= "<form name=\"FormaDetalle\" id=\"FormaDetalle\"  action=\"".$action['buscador']."\" method=\"post\" >\n";
			$html .= "			<table   width=\"35%\" align=\"center\" border=\"2\"  >";
			$html .= "		</tr>\n";
			$html .= "   <tr> \n";
			$html .= "			<td align=\"center\"  class=\"normal_10AN\"><b> PROVEEDOR:</B></td>\n";
			$html .= "			<td  class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "					<select name=\"buscador[proveedor_id]\" class=\"select\">\n";
			$html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
				foreach($dat as $indice => $valor)
				{
					if($valor['codigo_proveedor_id']==$request['codigo_proveedor_id'])
					$sel = "selected";
					else  $sel="";
					$html .= "  <option value=\"".$valor['codigo_proveedor_id']."\" ".$sel.">".$valor['nombre_tercero']."</option>\n";
				}
		    $html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html .= "	 </tr>\n";
			$html .= "</table><br>\n";
			$html .= "			<table   width=\"40%\"  class=\"normal_10AN\" align=\"center\" border=\"0\"  >";
			$html .= "		<tr>\n";
			$html .= "	   	<td align='center'>\n";
			$html .= "			<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "			</td>\n";
			
			$html .= "			</tr>\n";
			$html .= "</table><br>\n";
				
			if(!empty($datos))
			{
				$pghtml = AutoCarga::factory('ClaseHTML');
				$html .= "  <table width=\"90%\" class=\"modulo_table_list_title\" align=\"center\">";
    			$html .= "	  <tr align=\"CENTER\"    class=\"modulo_list_claro\" >\n";
				$html .= "      <td width=\"10%\">LABORATORIO</td>\n";
				$html .= "      <td width=\"25%\">MOLECULA</td>\n";
				$html .= "      <td width=\"10%\">IDENTIFICACION</td>\n";
				$html .= "      <td width=\"25%\">PROVEEDOR</td>\n";
				$html .= "      <td width=\"15%\">CODIGO</td>\n";
				$html .= "      <td width=\"25%\">DESCRIPCION</td>\n";
				$html .= "      <td width=\"7%\">CANTIDA</td>\n";
				$html .= "      <td width=\"7%\">VALOR_PACTADO</td>\n";
				$html .= "  </tr>\n";
                 
				$html .= "     <input type=\"hidden\" name=\"proveedor\" id=\"proveedor\" value=\"".$datos ['0']['codigo_proveedor_id']."\">\n";
				$html .= "     <input type=\"hidden\" name=\"empresa\" id=\"empresa\" value=\"".$datos ['0']['farmacia_id']."\">\n";

				$est = "modulo_list_claro"; $back = "#DDDDDD";
				foreach($datos as $key => $dtl)
				{
			    	 
					$html .= "	  <tr  align=\"CENTER\"    class=\"modulo_list_claro\" >\n";  					
					$html .= "      <td   align=\"center\">".$dtl['laboratorio']."</td>\n";
					$html .= "      <td  align=\"left\">".$dtl['molecula']."</td>\n";
					$html .= "      <td align=\"left\">".$dtl['tipo_id_tercero']."  ".$dtl['tercero_id']."</td>\n";
					$html .= "      <td align=\"left\">".$dtl['nombre_tercero']."</td>\n";
					$html .= "      <td align=\"left\">".$dtl['codigo_producto']."</td>\n";
					$html .= "      <td align=\"left\">".$dtl['descripcion']."</td>\n";
					$html .= "      <td align=\"left\">".$dtl['cantidad']."</td>\n";
					$html .= "      <td align=\"left\">".$dtl['valor_total_pactado']."</td>\n";
				
				
				}
				$html .= "  </tr>\n";
				$html .= "	</table><br>\n";
				$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
				$html .= "		<tr>\n";
				$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
				$html .= "			         <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"GENERAR ORDEN COMPRA\" onClick=\"ValidarInformaOrdenCompra(document.FormaDetalle);\"  >\n";
				$html .= "		          	</td>\n";
				$html .= "		<tr>\n";
				$html .= "	</table><br>\n";
				
				$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
			}
				
			else
			{
				if($request)
				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
			}
			
			$html .= " <br>";
				  			
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver'].URLRequest(array( "bodega"=>$bod))."\"  class=\"label_error\">\n";
			$html .= "       VOLVER \n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= "				    </form>\n";
			$html .= $this->CrearVentana(650,"MENSAJE");
			$html .= ThemeCerrarTabla();
			return $html;
		}
		
		function FormaBuscarDocumentoOrdenCompra($action,$request,$datos,$conteo,$pagina,$tiposdoc)
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
			$html  .= ThemeAbrirTabla('CONSULTAR ORDEN-COMPRAS');
			$html .= "<form name=\"FormaConsultar2\" id=\"FormaConsultar2\" action=\"".$action['buscador']."\"  method=\"post\" >\n";
			$html .= "<table  width=\"45%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td  class=\"modulo_table_list_title\" width=\"40%\">TIPO DOCUMENTO: </td>\n";
			$html .= "			<td  align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "					<select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
			$html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($tiposdoc as $indice => $valor)
			{
			if($valor['tipo_id_tercero']==$request['tipo_id_tercero'])
			$sel = "selected";
			else   $sel = "";
			$html .= "  <option value=\"".$valor['tipo_id_tercero']."\" ".$sel.">".$valor['descripcion']."</option>\n";
			}
			$html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html .= "	 </tr>\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td  width=\"40%\" class=\"modulo_table_list_title\">DOCUMENTO:</td>\n";
			$html .= "	    <td class=\"modulo_list_claro\" align=\"left\" colspan=\"4\">\n";
			$html .= "     <input type=\"text\" class=\"input-text\" name=\"buscador[tercero_id]\" maxlength=\"32\" value=".$request['tercero_id']."></td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr>\n";
			$html .= "			<td class=\"modulo_table_list_title\">PROVEEDOR:</td>\n";
			$html .= "			<td  align=\"left\" colspan=\"4\" class=\"modulo_list_claro\" ><input type=\"text\" class=\"input-text\" name=\"buscador[nombre_tercero]\" maxlength=\"32\" value=".$request['nombre_tercero']."></td>\n";
			$html .= "		</tr>\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td  width=\"30%\" >FECHA REGISTRO:</td>\n";
			$html .= "		<td width=\"15%\" align=\"left\" class=\"modulo_list_claro\" >\n";
			$html .= "		  <input type=\"text\" class=\"input-text\" name=\"buscador[fecha_inicio]\"   id=\"fecha_inicio\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\"  >\n";
			$html .= "		</td>\n";
			$html .= "    <td  width=\"15%\" class=\"modulo_list_claro\" >\n";
			$html .= "				".ReturnOpenCalendario('FormaConsultar2','fecha_inicio','-')."\n";
			$html .= "		</td>\n";
			$html .= "  </tr >\n";
			$html .= "  <tr  colspan=\"5\" class=\"modulo_table_list_title\" >\n";
			$html .= "      <td  >NUMERO ORDEN COMPRA:</td>\n";
			$html .= "      <td colspan=\"4\" align=\"left\" class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\"  name=\"buscador[orden]\"  id=\"txtncontrato\"   value=\"\" size=\"30%\" maxlength=\"30\" >\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  colspan=\"4\"  class=\"modulo_table_list_title\" >\n";
			$html .= "      <td >FARMACIA:</td>\n";
			$html .= "      <td colspan=\"4\"  align=\"left\"  class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\"  name=\"buscador[farmacia]\"  id=\"txtncontrato\"   value=\"\" size=\"30%\" maxlength=\"30\" >\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
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
				$html .= "  <table width=\"100%\" class=\"modulo_table_list_title\" align=\"center\">";
				$html .= "	  <tr align=\"CENTER\"    class=\"modulo_list_claro\" >\n";
				$html .= "      <td    width=\"5%\">NUMERO.</td>\n";
				$html .= "      <td    width=\"10%\">IDENTIFICACION.</td>\n";
				$html .= "      <td    width=\"30%\">PROVEEDOR.</td>\n";
				$html .= "      <td   width=\"25%\">OBSERVACION</td>\n";
				$html .= "      <td  width=\"15%\">USUARIO</td>\n";
				$html .= "      <td   width=\"35%\">FECHA REGISTRO</td>\n";
				$html .= "	      <td  width=\"10%\">";
				$html .= "      INF</td>\n";
				$html .= "	      <td  width=\"10%\">";
				$html .= "      ASIG</td>\n";
				$html .= "	      <td  width=\"10%\">";
				$html .= "      OP</td>\n";
				$html .= "  </tr>\n";
				$xml = Autocarga::factory("ReportesCsv");			
                $est = "modulo_list_claro"; $back = "#DDDDDD";
				foreach($datos as $key => $dtl)
				{
	 				$html .= "	  <tr  align=\"CENTER\" class=\"".$est."\" >\n";
     				$html .= "      <td   align=\"center\">".$dtl['orden_pedido_id']."</td>\n";
					$html .= "      <td   align=\"center\">".$dtl['tipo_id_tercero']."   ".$dtl['tercero_id']."</td>\n";
					$html .= "      <td   align=\"center\">".$dtl['nombre_tercero']."</td>\n";
					$html .= "      <td align=\"left\">".$dtl['observacion']."</td>\n";
					$html .= "      <td align=\"left\">".$dtl['nombre']."</td>\n";
					$html .= "      <td align=\"left\">".$dtl['fecha_registro']."</td>\n";
					
					$html .= "      <td align=\"center\">\n";
					$html .= "      <a href=\"".$action['detalle2'].URLRequest(array("orden_pedido_id"=>$dtl['orden_pedido_id'],"empresa_id"=>$dtl['empresa_id'],"tipo_id_tercero"=>$dtl['tipo_id_tercero'],"tercero_id"=>$dtl['tercero_id'],"nombre"=>$dtl['nombre'],"razon_social"=>$dtl['razon_social']))."\">\n";
					$html .= "        <img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\">\n";
					$html .= "    </a>\n";
					$html .= "			</td>\n";
				
					$html .= "      <td align=\"center\">\n";
					$html .= "      <a href=\"".$action['asignar'].URLRequest(array("orden_pedido_id"=>$dtl['orden_pedido_id'],"empresa_id"=>$dtl['empresa_id']))."\">\n";
					$html .= "        <img src=\"".GetThemePath()."/images/news.gif\" border=\"0\">\n";
					$html .= "    </a>\n";
					$html .= "			</td>\n";
					$datos['orden_pedido_id'] = $dtl['orden_pedido_id'];
					$datos['tipo_id_tercero'] = $dtl['tipo_id_tercero'];
					$datos['tercero_id'] = $dtl['tercero_id'];
					$datos['empresa_id'] = $dtl['empresa_id'];
					$datos['codigo_proveedor_id']=$dtl['codigo_proveedor_id'];
					
					
					$html .= $xml->GetJavacriptReporteFPDF('app','Compras_Orden_Compras','InformeOrdenPedido',$datos,array("interface"=>5));
					$fnc1  = $xml->GetJavaFunction();
				
				
					$html .= "    <td>\n";
					$html .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir_doc\" value=\"IMPRIMIR\" onclick=\"".$fnc1."\">\n";
					$html .= "    </td>\n";
                  	$html .= "			</td>\n";
    			}					
				
				$html .= "			</tr>\n";
				$html .= "	</table><br>\n";
				
				$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
			}
			else
			{
				if($request)
				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
			}
			
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver'].URLRequest(array( "bodegades"=>$bodegades))."\"  class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
		
			function FormaDetalleDocumentoOrdenCompra($action,$datos,$conteo,$pagina,$tipo_id_tercero,$tercero_id,$nombre,$razon,$orden_pedido_id)
		{
			
			
			$html  .= ThemeAbrirTabla('DETALLE DEL DOCUMENTO DE ORDEN-COMPRA');
			$html .= "<form name=\"FormaDetalle2\" id=\"FormaDetalle2\"  action=\"".$action['buscador']."\" method=\"post\" >\n";
			$html .= "<table  width=\"50%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td  class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION: </td>\n";
			$html .= "			<td  width=\"40%\" align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "				".$tipo_id_tercero."  ".$tercero_id."		  </td>\n";
			$html .= "			<td  class=\"modulo_table_list_title\" width=\"20%\">PROVEEDOR: </td>\n";
			$html .= "			<td  align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "				".$nombre."  </td>\n";
			$html .= "	 </tr>\n";

			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td  class=\"modulo_table_list_title\" width=\"20%\">EMPRESA: </td>\n";
			$html .= "			<td  width=\"40%\" align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "				".$razon."  </td>\n";
			$html .= "			<td  class=\"modulo_table_list_title\" width=\"30%\">ORDEN DE COMPRA No : </td>\n";
			$html .= "			<td  align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "				".$orden_pedido_id."  </td>\n";
			$html .= "	 </tr>\n";
			
			$html .= "</table><br>\n";
			
			if(!empty($datos))
			{
				$pghtml = AutoCarga::factory('ClaseHTML');
				$html .= "  <table width=\"90%\" class=\"modulo_table_list_title\" align=\"center\">";
    			$html .= "	  <tr align=\"CENTER\"    class=\"modulo_list_claro\" >\n";
				$html .= "      <td width=\"10%\">LABORATORIO</td>\n";
				$html .= "      <td width=\"25%\">MOLECULA</td>\n";
				$html .= "      <td width=\"15%\">CODIGO</td>\n";
				$html .= "      <td width=\"25%\">DESCRIPCION</td>\n";
				$html .= "      <td width=\"7%\">CANTIDA</td>\n";
				$html .= "      <td width=\"7%\">VALOR_PACTADO</td>\n";
				$html .= "  </tr>\n";
                			
				$est = "modulo_list_claro"; $back = "#DDDDDD";
				foreach($datos as $key => $dtl)
				{
			    	 
					$html .= "	  <tr  align=\"CENTER\"    class=\"modulo_list_claro\" >\n";  					
					$html .= "      <td   align=\"center\">".$dtl['laboratorio']."</td>\n";
					$html .= "      <td  align=\"left\">".$dtl['molecula']."</td>\n";
					$html .= "      <td align=\"left\">".$dtl['codigo_producto']." </td>\n";
					$html .= "      <td align=\"left\">".$dtl['producto']."</td>\n";
					$html .= "      <td align=\"left\">".$dtl['numero_unidades']."</td>\n";
					$html .= "      <td align=\"left\">".$dtl['valor']."</td>\n";
								
				}
				$html .= "  </tr>\n";
				$html .= "	</table><br>\n";
							
				
			}
				
			else
			{
				if($request)
				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
			}
			
			$html .= " <br>";
				  			
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver'].URLRequest(array( "bodega"=>$bod))."\"  class=\"label_error\">\n";
			$html .= "       VOLVER \n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= "				    </form>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
		
		function FormaAsiganarCondiciones($action,$orden_pedid,$empresa_id)
		{
				
				
				$html .="<script >\n";
				$html .= "  function max(e){  ";
				$html .= "  tecla = (document.all) ? e.keyCode : e.which; ";
				$html .= "  if (tecla==8) return true;";
				$html .= "  if (tecla==13) return false;";
				$html .= " }";
				$html .= " function ValidarCondicion(frm)";
				$html .= " {" ;
				$html .="   if(frm.observar.value==\"\"){ ";
				$html .= "    document.getElementById('error').innerHTML = 'DEBE INGRESAR LA CONDICION DE COMPRA DE PRODUCTOS';\n";
                $html .= "      return;\n";
				$html .= "    }\n";
				$html .="   if(frm.observar.value!=\"\"){ ";
				$html .= "    xajax_TrasferirCondicion(frm.observar.value,'".$orden_pedid."','".$empresa_id."'); ";
				$html .= "      return;\n";
				$html .="   }";
				$html .="   }";
				$html .="  </script>\n";
				$html  .= ThemeAbrirTabla('DETALLE DEL DOCUMENTO DE ORDEN-COMPRA');
				$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";

				$html  .= "<fieldset class=\"fieldset\">\n";
				$html .= "  <legend class=\"normal_10AN\" align=\"center\">CONDICIONES DE COMPRAS DEPRODUCTOS</legend>\n";
				$html .= " <form name=\"Forma13\" id=\"Forma13\" method=\"post\" >\n";
				$html  .= "  <table class=\"modulo_table_list_title\" border=\"1\" align=\"center\" width=\"80%\">\n";
				$html .= "    <tr class=\"modulo_table_list_title\">\n";
				$html .= "      <td width=\"10%\" align=\"center\">* CONDICIONES\n";
				$html .= "      </td>\n";
				$html .= "    </tr>\n";
				$html .= "    <tr class=\"modulo_table_list_title\">\n";
				$html .= "      <td colspan=\"5\"  align=\"center\" class=\"modulo_list_claro\">\n";
				$html .= "        <textarea onkeypress=\"return max(event)\"  name=\"observar\" rows=\"2\" style=\"width:100%\"></textarea>\n";
				$html .= "      </td>\n";
				$html .= "    </tr>\n";
				$html .= "  </table>\n";
				$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
				$html .= "		<tr>\n";
				$html .= "      <td align=\"center\" class=\"normal_10AN\" >\n";
				$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"GUARDAR\" onclick=\"ValidarCondicion(document.Forma13);\">\n";
				$html .= " </td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
				$html .= "<table align=\"center\" width=\"50%\">\n";
				$html .= "  <tr>\n";
				$html .= "    <td align=\"center\">\n";
				$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
				$html .= "       VOLVER \n";
				$html .= "      </a>\n";
				$html .= "    </td>\n";
				$html .= "  </tr>\n";
				$html .= "</table>\n";
				$html .= "				    </form>\n";
				$html .= "</fieldset><br>\n";
	
				$html .= ThemeCerrarTabla();
				return $html;
	
		}
		
		function FormaDocumentoOrdenPedido($action,$orden_pedido_id,$empresa_id,$nombre_tercero,$tipo_id_tercero,$tercero_id,$razon_social)
		{
					$url=ModuloGetURL("app", "ConsultarOrdenes", "controller", "UnificarYcreasDocumento");
					$html  .= ThemeAbrirTabla('DOCUMENTO DE ORDEN DE COMPRA ');
					$html .="  <script>\n";
					$html .= "	  function GrabarDocumentosOrden(frm)\n";
					$html .= "	  {\n";
					$html .= " 	xajax_TransfeOrdenPedido(frm.observar.value);";
					$html .= "      return;\n";
					$html .= "    frm.submit();\n";
					$html .= "    }\n";
					$html .="  </script>\n";
					$html .= "<form name=\"Forma17\" id=\"Forma17\"  method=\"post\" >\n";
					$html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "                   <tr class=\"modulo_table_list_title\">\n";
					$html .= "                      <td align=\"center\">\n";
					$html .= "                         IDENTIFICACION ";
					$html .= "                       </td>\n";
					$html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
					$html .= "                        ".$tipo_id_tercero." ".$tercero_id." ";
					$html .= "                       </td>\n";
					$html .= "                     <td align=\"center\">\n";
					$html .= "                        <a title='farmacia'>PROVEEDOR:<a>";
					$html .= "                      </td>\n";
					$html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
					$html .= "                          ".$nombre_tercero;
					$html .= "                       </td>\n";
					$html .= "                     <tr>\n";
					$html .= "                   <tr class=\"modulo_table_list_title\">\n";
					$html .= "                      <td align=\"center\">\n";
					$html .= "                         EMPRESA ";
					$html .= "                       </td>\n";
					$html .= "                      <td   colspan=\"3\"  align=\"left\" class=\"modulo_list_claro\">\n";
					$html .= "                        ".$razon_social;
					$html .= "                       </td>\n";
					
					$html .= "                     <tr>\n";
					$html .= "                        <td rowspan='1' colspan='10' align=\"center\" class=\"modulo_list_claro\"> \n";
					$html .= "                          <fieldset>";
					$html .= "                           <legend>OBSERVACIONES</legend>";
					$html .= "                              <TEXTAREA id='observar' ROWS='2' COLS=55 ></TEXTAREA>\n";
					$html .= "                        </td>\n";
					$html .= "                     </tr>\n";
					$html .= "                          </fieldset>";
					$html .= "</table><br>\n";
					$html .= "			<table   width=\"30%\" align=\"center\" border=\"0\"   >";
					$html .= "  <tr>\n";
					$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
					$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CREAR DOCUMENTO\" onclick=\"GrabarDocumentosOrden(document.Forma17);\" >\n";
					$html .= " </td>\n";
					$html .= "		</tr>\n";
					$html .= "</table><br>\n";
					$html .= ThemeCerrarTabla();
					return $html;
		
		}
		
		function  FormaDocumentoDePedido($action,$id,$empresa,$nombre_tercero,$tipo_id_tercero,$tercero_id,$razon_social,$numero)
		{
					$html  .= ThemeAbrirTabla('DOCUMENTO DE ORDEN DE COMPRA ');
					$html .= "<form name=\"Forma18\" id=\"Forma18\"  method=\"post\" >\n";
					$html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "                   <tr class=\"modulo_table_list_title\">\n";
					$html .= "                      <td align=\"center\">\n";
					$html .= "                         IDENTIFICACION ";
					$html .= "                       </td>\n";
					$html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
					$html .= "                        ".$tipo_id_tercero." ".$tercero_id." ";
					$html .= "                       </td>\n";
					$html .= "                     <td align=\"center\">\n";
					$html .= "                        <a title='farmacia'>PROVEEDOR:<a>";
					$html .= "                      </td>\n";
					$html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
					$html .= "                          ".$nombre_tercero;
					$html .= "                       </td>\n";
					$html .= "                     <tr>\n";
					$html .= "</table>\n";
					$html .= "<br>\n";
					$html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";

					$html .= "                   <tr class=\"modulo_table_list_title\">\n";
					$html .= "                      <td align=\"center\">\n";
					$html .= "                         SE CREO EL DOCUMENTO DE PRODUCTO PENDIENTES No ".$id."  ";
					$html .= "                       </td>\n";
					$html .= "                     <tr>\n";
					$html .= "                   <tr class=\"modulo_table_list_title\">\n";
					$html .= "                      <td align=\"center\">\n";
					$html .= "                         SE CREO LA ORDEN   DE PEDIDO No".$numero."  ";
					$html .= "                       </td>\n";
					$html .= "                     <tr>\n";
					$html .= "</table>\n";
					
					$html .= "<table align=\"center\" width=\"50%\">\n";
					$html .= "  <tr>\n";
					$html .= "    <td align=\"center\">\n";
					$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
					$html .= "       VOLVER \n";
					$html .= "      </a>\n";
					$html .= "    </td>\n";
					$html .= "  </tr>\n";
					$html .= "</table>\n";
					$html .= ThemeCerrarTabla();
					return $html;
				
		}
		
		function FormaBuscarDocumentoPedido()
		{
		
		
		
		
		
		
		
		
		
		}

		
	}
?>