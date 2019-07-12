<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: Compras_Orden_ComprasHTML.class.php,v 1.0 
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres
	*/

	class Compras_OrdenesPedidosHTML
	{
	/**
		* Constructor de la clase
	*/
	function  Compras_OrdenesPedidosHTML()
	{}
	
/*
		* Funcion donde se crea una Forma con una Ventana con capas para mostrar informacion
		* en pantalla
		* @param int $tmn Tamaño que tendra la ventana
		* @return string
    */
		
   /*
	* Funcion donde se crea la forma para el documento de pedido por orden de compra
          * @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
      */
		
		function  FormaOC($action,$InfoProveedor)
		{
					//print_r($_REQUEST);			
					//print_r($InfoProveedor);
          $ctl = AutoCarga::factory("ClaseUtil");
          $html .= $ctl->LimpiarCampos();
					$html .= "<script>";
					$html .= "function AdicionarProducto(empresa_id,codigo_producto,Descripcion,iva,costo_ultima_compra)";
					$html .= "{";
					$html .= "	document.getElementById('codigo_producto').value=codigo_producto;";
					$html .= "	document.getElementById('DescripcionProducto').innerHTML=Descripcion;";
					$html .= "	document.getElementById('porc_iva').value=iva;";
          $html .= "	document.getElementById('valor').value=costo_ultima_compra;";
					$html .= "	OcultarSpan();";
					$html .= "}";
					$html .= "</script>";
					
					$html .= "<script>";
					$html .= "function QuitarProducto()";
					$html .= "{";
					$html .= "	document.getElementById('codigo_producto').value='';";
					$html .= "	document.getElementById('DescripcionProducto').innerHTML='';";
					$html .= "	document.getElementById('numero_unidades').value='';";
					$html .= "	document.getElementById('valor').value='';";
					$html .= "	document.getElementById('porc_iva').value='';";
					$html .= "}";
					$html .= "</script>";
					
					$html .= "<script>";
					$html .= "function Paginador(CodigoProducto,Descripcion,Concentracion,Empresa_Id,ClaseId,Subclase_id,offset)";
					$html .= "{";
					$html .= "	xajax_BuscarProductos(CodigoProducto,Descripcion,Concentracion,Empresa_Id,ClaseId,Subclase_id,offset);";
					$html .= "}";
					$html .= "</script>";
					
					
					$html .= ThemeAbrirTabla('DOCUMENTO DE ORDEN DE COMPRA ');
					$html .= "<form name=\"Forma18\" id=\"Forma18\"  method=\"post\" >\n";
					$html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "                   <tr class=\"modulo_table_list_title\">\n";
					$html .= "                      <td align=\"center\">\n";
					$html .= "                         IDENTIFICACION ";
					$html .= "                       </td>\n";
					$html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
					$html .= "                        ".$InfoProveedor[0]['tipo_id_tercero']." ".$InfoProveedor[0]['tercero_id']." ";
					$html .= "                       </td>\n";
					$html .= "                     <td align=\"center\">\n";
					$html .= "                        <a title='farmacia'>PROVEEDOR:<a>";
					$html .= "                      </td>\n";
					$html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
					$html .= "                          ".$InfoProveedor[0]['nombre_tercero'];
					$html .= "                       </td>\n";
					$html .= "                     <tr>\n";
					$html .= "</table>\n";
					$html .= "<br>\n";
					$html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "                   <tr class=\"modulo_table_list_title\">\n";
					$html .= "                      <td align=\"center\">\n";
					$html .= "                         SE CREO LA ORDEN DE PEDIDOS No ".$_REQUEST['orden_pedido_id']."  ";
					$html .= "                       </td>\n";
					$html .= "                     <tr>\n";
					
					$html .= "</table>\n";
					$html .= "<br>";
					//	item_id 	orden_pedido_id 	codigo_producto 	numero_unidades 	valor 	porc_iva 	estado 	acta_autorizacion 	numero_unidades_recibidas 	lote_temp 	fecha_vencimiento_temp 	preorden_detalle_id 	valor_unitario 	valor_unitario_factura 	cantidad_devuelta
					$html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "				  <tr>";
					
					$html .= "				  <tr>";
					$html .= "				 	 <td>";
					$html .= "				      <b>PRODUCTO:</b>";
					$html .= "				  	 </td>";
					$html .= "				 	 <td>";
					$html .= "				      <a onclick=\"xajax_SeleccionDeProductos('".$_REQUEST['empresa_id']."')\">";
					$html .="<img title=\"SELECCIONAR PRODUCTOS\" src=\"".GetThemePath()."/images/producto.png\" border=\"0\"></a>\n";
					$html .= "				  	 </td>";
					$html .= "				  </tr>";
					
					$html .= "				  <tr>";
					$html .= "				 	 <td>";
					$html .= "				      <b>Codigo Producto:</b>";
					$html .= "				  	 </td>";
					$html .= "				 	 <td>";
					$html .= "				      <input id=\"codigo_producto\" type=\"text\" class=\"input-text\" readonly>";
					$html .= "				      <input id=\"cantidad_productosOC\" type=\"hidden\" >";
					$html .= "				  	 </td>";
					$html .= "				  </tr>";
					
					$html .= "				  <tr>";
					$html .= "				 	 <td>";
					$html .= "				      <b>Descripcion:</b>";
					$html .= "				  	 </td>";
					$html .= "				 	 <td>";
					$html .= "				      <div id=\"DescripcionProducto\" class=\"label_error\"></div>";
					$html .= "				  	 </td>";
					$html .= "				  </tr>";
					
					$html .= "				  <tr>";
					$html .= "				 	 <td>";
					$html .= "				      <b>IVA:</b>";
					$html .= "				  	 </td>";
					$html .= "				 	 <td>";
					$html .= "				      <input id=\"porc_iva\" type=\"text\" class=\"input-text\" readonly>";
					$html .= "				  	 </td>";
					$html .= "				  </tr>";
					
					
					$html .= "				  <tr>";
					$html .= "				 	 <td>";
					$html .= "				      <b>NUMERO DE UNIDADES:</b>";
					$html .= "				  	 </td>";
					$html .= "				 	 <td>";
					$html .= "				      <input type=\"text\" class=\"input-text\" id=\"numero_unidades\">";
					$html .= "				  	 </td>";
					$html .= "				  </tr>";
					
					
					$html .= "				 	 <td>";
					$html .= "				      <b>PRECIO DE COMPRA:</b>";
					$html .= "				  	 </td>";
					$html .= "				 	 <td>";
					$html .= "				      <input type=\"text\" class=\"input-text\" id=\"valor\">";
					$html .= "				  	 </td>";
					$html .= "				  </tr>";
					
					
					$html .= "				  <tr>";
					$html .= "				 	 <td align=\"center\" colspan=\"2\">";
					$java  = "'".$_REQUEST['orden_pedido_id']."','".$_REQUEST['empresa_id']."',document.getElementById('codigo_producto').value,document.getElementById('numero_unidades').value,document.getElementById('valor').value,document.getElementById('porc_iva').value";
					
					$html .= "				      <input type=\"button\" onclick=\"xajax_AgregarItemOC(".$java.")\"  class=\"modulo_table_list\" value=\"Adicionar\">";
					$html .= "				  	 </td>";
					$html .= "				  </tr>";
					
					$html .= "					</table>";
					
					$html .= "<br>";
					
					$html .= "<div id=\"DetalleOC\"></div>";
					
					//href=\"".$action['volver']."\"
					$html .= "<table  align=\"center\" width=\"50%\">\n";
					$html .= "  <tr>\n";
					$html .= "    <td align=\"center\">\n";
					//Solo se mostrará en caso de que tenga Productos Asociados
					$html .= "		<div id=\"link_confirmar\"></div>";
										
					$html .= "    </td>\n";
					$html .= "    <td align=\"center\">\n";
					$html .= "      <a onclick=\"xajax_ConfirmarOC('".$_REQUEST['orden_pedido_id']."','".$_REQUEST['empresa_id']."',document.getElementById('cantidad_productosOC').value,'2');\" class=\"label_error\">\n";
					$html .= "       [[::ELIMINAR DOCUMENTO::]] \n";
					$html .= "      </a>\n";
					$html .= "    </td>\n";
					
					$html .= "  </tr>\n";
					$html .= "</table>\n";
					$html .= "</form>";
					$html .= ThemeCerrarTabla();
					$html .= $this->CrearVentana(800,"SELECCIONE PRODUCTO");
					
					$html .="<script>";
					$html .="xajax_DetalleOC('".$_REQUEST['orden_pedido_id']."');";
					$html .="</script>";
					
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
      $html .= "    catch(error){alert(\"vaya\"+error)}\n";
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