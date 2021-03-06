<?php
   /**
  * @package IPSOFT-SIIS
  * @version $Id: ContratacionProductosClienteHTML.class.php,v 1.14 2010/01/26 22:40:56 sandra Exp $Revision: 1.14 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");
  IncludeClass("CalendarioHtml");
  
  class FacturacionProveedorHTML
  {
     /**
     * Constructor de la clase
     */
     
    function  FacturacionProveedorHTML(){}
    
     /**
	* Funcion forma que permite realizar  la busqueda de los proveedores
	* @param array $action vector que contiene los link de la aplicacion
	* @return string $html retorna la cadena con el codigo html de la pagina
    */ 
		function Formabusquedaproveedores($action,$tipodocumento)                             
		{
     		$html .= ThemeAbrirTabla('SELECCION - PROVEEDOR');
			
			$html .= "<script>";
			$html .= "function Paginador(tipo_id_tercero,tercero_id,nombre_tercero,offset)";
			$html .= "{";
			$html .= "xajax_ListadoTerceros(tipo_id_tercero,tercero_id,nombre_tercero,offset);";
			$html .= "}";
			$html .= "</script>";
			
			$html .= "		<form name=\"formabuscar\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "<table   width=\"40%\" align=\"center\" border=\"0\"  >";
            $html .= "	</tr>\n";
			$html .= "   <tr> \n";
			$html .= "		<td class=\"modulo_table_list_title\" width=\"40%\">TIPO DOCUMENTO: </td>\n";
			$html .= "		<td  class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "			<select id=\"tipo_id_tercero\" class=\"select\">\n";
			$html .= "             	<option value = ''>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($tipodocumento as $indice => $valor)
			{
	            if($valor['tipo_id_tercero']==$request['tipo_id_tercero'])
				$sel = "selected";
				else   $sel = "";
				$html .= " 			<option value=\"".$valor['tipo_id_tercero']."\" ".$sel.">".$valor['descripcion']."</option>\n";
			}
			$html .= "            </select>\n";
			$html .= "	   </td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td  width=\"40%\" class=\"modulo_table_list_title\">DOCUMENTO:</td>\n";
			$html .= "	    <td class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "  	   <input type=\"text\" class=\"input-text\" id=\"tercero_id\" maxlength=\"32\"></td>\n";
			$html .= "	</tr>\n";
			$html .= "		<tr>\n";
			$html .= "			<td class=\"modulo_table_list_title\">NOMBRE:</td>\n";
			$html .= "			<td  colspan=\"4\" class=\"modulo_list_claro\" ><input type=\"text\" class=\"input-text\" id=\"nombre_tercero\" maxlength=\"32\"></td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "	   	<td align='center'>\n";
			$html .= "			<input onclick=\"xajax_ListadoTerceros(document.getElementById('tipo_id_tercero').value,document.getElementById('tercero_id').value,document.getElementById('nombre_tercero').value);\" class=\"input-submit\" type=\"button\" value=\"Buscar\">\n";
			$html .= "		</td>\n";
			$html .= "		<td align='center' colspan=\"1\">\n";
			$html .= "			<input class=\"input-submit\" type=\"reset\" value=\"Limpiar Campos\">\n";
			$html .= "	  	</td>\n";
			$html .= "</form>\n";
         	$html .= "<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		  <td align='center' >\n";
			$html .= "		      <input class=\"input-submit\" type=\"submit\" value=\"Volver\">\n";
			$html .= "		  </td>\n";
			$html .= " </form>\n";
			$html .= "	 </tr>\n";
			$html .= "</table><br>\n";
			$html .= "<div id=\"ListadoTerceros\"></div>";
	        $html .= $this->CrearVentana();
	        $html .= ThemeCerrarTabla();
	        return $html;
        }
	
		function FormaMenu($action)                             
		{
     		$html .= ThemeAbrirTabla('MENU - AUDITORIA FACTURACION PROVEEDORES');
		
		//print_r($_REQUEST);
		
		$accion=$action['volver'];
		$html  = ThemeAbrirTabla('MENU - AUDITORIA FACTURACION PROVEEDORES');
		$html .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$html .= "  <tr><td>";
		$html .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\">";
		$html .= "      MEN?";
		$html .= "      </td>";
		$html .= "      </tr>";
		//print_r($_REQUEST);
		$url  .= "&datos[empresa]=".$_REQUEST['datos']['empresa']."&codigo_proveedor_id=".$_REQUEST['codigo_proveedor_id']."";
	
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_AuditoriaFacturasProveedor','controller','ListarFacturas')."".$url."\">FACTURAS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
		
    /*
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_AuditoriaFacturasProveedor','controller','ReporteAuditor?a') ."".$url."\">REPORTE DE VERIFICACION</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
		*/
		$html .= "      </table>";
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

    /**
      * Funcion donde se crea una Forma con una Ventana con capas para mostrar informacion
           * en pantalla
           * @param array $action vector que contiene los link de la aplicacion      
	 * @param int $tmn Tama?o que tendra la ventana
     * @return string
    */
    function CrearVentana($tmn,$Nombre)
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
      $html .= "</script>\n";
      $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
      $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Nombre."</div>\n";
      $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
      $html .= "  <div id='Contenido' class='d2Content'>\n";
      $html .= "  </div>\n";
      $html .= "</div>\n";
      return $html;
    }
		
	
   /**
      * Funcion donde se crea la Forma del Contrato a llenar  
      * @param array $datos vector que contiene la informacion de Los Proveedores
      * @param array $action vector que contiene los link de la aplicacion
      * @param array $datos_empresa vector que contiene la informacion de Empresa que contrata al proveedor
      * @param string $fecha contiene la informacion de la fecha actual.
      * @return string $html retorna la cadena con el codigo html de la pagina
    */ 
		function FormaOrdenesCompra($action,$TerceroProveedor)
		{
	  $ctl = AutoCarga::factory("ClaseUtil");
      
 	  $html  = $ctl->LimpiarCampos();
      $html .= $ctl->RollOverFilas();
 	  $html .= $ctl->AcceptDate('/');
	  $html .= $ctl->AcceptNum();
      
	  $html .= "<script>";
	  $html .= "function Validar(formulario)";
	  $html .= "{";
	  $html .="		formulario.valor_descuento=parseInt(formulario.valor_descuento);";
	  $html .="		formulario.valor_flete=parseInt(formulario.valor_flete);";
				
	$html .="	if(formulario.numero_factura == '')";
	$html .="		{";
	$html .="		document.getElementById('error').innerHTML='Error... No Hay Registrada Una Factura!!';";
	$html .="		return(false);";
	$html .="		}";
	
	$html .="	if(formulario.valor_descuento < 0)";
	$html .="		{";
	$html .="		document.getElementById('error').innerHTML='Error... No Es Posible Valores Negativos En El Descuento!!';";
	$html .="		return(false);";
	$html .="		}";
	
	$html .="	if(formulario.valor_flete < 0)";
	$html .="		{";
	$html .="		document.getElementById('error').innerHTML='Error... No Es Posible Valores Negativos En El Flete!!';";
	$html .="		return(false);";
	$html .="		}";
	
	$html .="		xajax_Facturar(formulario,document.getElementById('orden_pedido_id').value);";
	
	  $html .= "}";
	  $html .= "</script>";
	  
	  $html .= ThemeAbrirTabla('FACTURACION DE RECEPCIONES PARCIALES - ORDENES DE COMPRA');
	  //Empieza Tab
	  $html .= "<center>";
      $html .= "	<table width=\"98%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<table width=\"100%\" align=\"center\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td>\n";
			$html .= "							<div class=\"tab-pane\" id=\"facturacion_proveedor\">\n";
			$html .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"facturacion_proveedor\" )); </script>\n";
			$html .= "								<div class=\"tab-page\" id=\"ordenes_compra\">\n";
			$html .= "									<h2 class=\"tab\">ORDENES DE COMPRA</h2>\n";
            $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"ordenes_compra\")); </script>\n";
	  
	  
      $html .= "<form name=\"productos\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "		      <table width=\"100%\">\n";
      $html .= "            <tr>\n";
      $html .= "              <td align=\"center\">\n";
      $html .= "                <fieldset class=\"fieldset\" style=\"width:98%\">\n";
      $html .= "                  <legend class=\"normal_10AN\">\n";
      $html .= "                    <img src=\"".GetThemePath()."/images/informacion.png\">NOTA\n";
      $html .= "                  </legend>\n";
      $html .= "                  <center>\n";
      $html .= "                    <label class=\"normal_10AN\">Busqueda de Ordenes de Compra De ".$TerceroProveedor[0]['nombre_tercero']."</label>\n";
      $html .= "                  </center>\n";
      $html .= "                </fieldset><br>\n";
 			
      $html .= "              </td>\n";
      $html .= "            </tr>\n";
      $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FECHA INICIAL</td>\n";
      $html .= "              <td>\n";
      $html .= "                <input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicio']."\">\n";
      $html .= "              </td>\n";
 	  $html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_inicio','/',1)."</td>\n";
      $html .= "            </tr>\n";
      $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FECHA FINAL</td>\n";
      $html .= "              <td>\n";
      $html .= "                <input type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_final']."\">\n";
      $html .= "              </td>\n";
 		$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_final','/',1)."</td>\n";
      $html .= "            </tr>\n";
	  $html .= "			      <tr>\n";
	  $html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
	  $html .= "		          <input type=\"hidden\" id=\"orden_pedido_id\">";
	  $html .= "					      <input type=\"button\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\" onclick=\"xajax_BuscarOrdenDeCompra('".$_REQUEST['datos']['empresa']."','".$_REQUEST['codigo_proveedor_id']."',document.getElementById('fecha_inicio').value,document.getElementById('fecha_final').value);\">\n";
   	  $html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.productos)\">\n";
      $html .= "				      </td>\n";
	  $html .= "			      </tr>\n";
	  $html .= "		      </table>\n";
		//$html .= "	      </fieldset>\n";
	   $html .= "	    </td>\n";
	   $html .= "	  </tr>\n";
	   $html .= "	</table>\n";
	   $html .= "</form>\n";
      
	  $html .= "<div id=\"OrdenesCompra\"></div>";
	  $html .= "								</div>\n";
	
	//Apertura 2do Tab
	$html .= "								<div class=\"tab-page\" id=\"recepciones_parcialesf\">\n";
    $html .= "									<h2 class=\"tab\">FACTURACION DE RECEPCIONES PARCIALES</h2>\n";
    $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"recepciones_parcialesf\")); </script>\n";
	//Cierre de 2do Tab y Cierre de TABS
	
	$html .= "					<div id=\"RecepcionesParciales\"></div>";
	
	$html .= "							</div>\n";
	$html .= "						</td>\n";
	$html .= "					</tr>\n";
	$html .= "				</table>\n";
	$html .= "			</td>\n";
	$html .= "		</tr>\n";
	$html .= "  </table>\n";
	
	
	
	
	
	
      $html .= "	<table width=\"90%\" align=\"center\">\n";
	  $html .= "		<tr><td align=\"center\">\n";
	  $html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
	  $html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
	  $html .= "			</form>\n";
	  $html .= "		</td></tr>\n";
	  $html .= "	</table>\n";
      $html .= ThemeCerrarTabla();
	  $html .= $this->CrearVentana(900,"RECEPCIONES PARCIALES");
      return $html;
	}

		function ListarFacturasProveedor($action,$TerceroProveedor)
		{
	  $ctl = AutoCarga::factory("ClaseUtil");
      
 	  $html  = $ctl->LimpiarCampos();
      $html .= $ctl->RollOverFilas();
 	  $html .= $ctl->AcceptDate('/');
	  $html .= $ctl->AcceptNum();
      
	  $html .= ThemeAbrirTabla('FACTURAS DEL PROVEEDOR');
	  //Empieza Tab
	  
	  
      $html .= "<form name=\"productos\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "		      <table width=\"100%\">\n";
      $html .= "            <tr>\n";
      $html .= "              <td align=\"center\">\n";
      $html .= "                <fieldset class=\"fieldset\" style=\"width:98%\">\n";
      $html .= "                  <legend class=\"normal_10AN\">\n";
      $html .= "                    <img src=\"".GetThemePath()."/images/informacion.png\">NOTA\n";
      $html .= "                  </legend>\n";
      $html .= "                  <center>\n";
      $html .= "                    <label class=\"normal_10AN\">Busqueda Facturas Del Proveedor : ".$TerceroProveedor[0]['nombre_tercero']."</label>\n";
      $html .= "                  </center>\n";
      $html .= "                </fieldset><br>\n";
 			
      $html .= "              </td>\n";
      $html .= "            </tr>\n";
      $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FECHA INICIAL</td>\n";
      $html .= "              <td>\n";
      $html .= "                <input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicio']."\">\n";
      $html .= "              </td>\n";
 	  $html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_inicio','/',1)."</td>\n";
      $html .= "            </tr>\n";
      $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FECHA FINAL</td>\n";
      $html .= "              <td>\n";
      $html .= "                <input type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_final']."\">\n";
      $html .= "              </td>\n";
 		$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_final','/',1)."</td>\n";
      $html .= "            </tr>\n";
	  $html .= "			      <tr>\n";
	  $html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
	  $html .= "		          <input type=\"hidden\" id=\"orden_pedido_id\">";
	  $html .= "					      <input type=\"button\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\" onclick=\"xajax_ListarFacturasProveedor('".$_REQUEST['datos']['empresa']."','".$_REQUEST['codigo_proveedor_id']."',document.getElementById('fecha_inicio').value,document.getElementById('fecha_final').value);\">\n";
   	  $html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.productos)\">\n";
      $html .= "				      </td>\n";
	  $html .= "			      </tr>\n";
	  $html .= "		      </table>\n";
		//$html .= "	      </fieldset>\n";
	   $html .= "	    </td>\n";
	   $html .= "	  </tr>\n";
	   $html .= "	</table>\n";
	   $html .= "</form>\n";
      
	  $html .= "<div id=\"OrdenesCompra\"></div>";
	 
	  $html .= "	<table width=\"90%\" align=\"center\">\n";
	  $html .= "		<tr><td align=\"center\">\n";
	  $html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
	  $html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
	  $html .= "			</form>\n";
	  $html .= "		</td></tr>\n";
	  $html .= "	</table>\n";
      $html .= ThemeCerrarTabla();
	  $html .= $this->CrearVentana(900,"RECEPCIONES PARCIALES");
      return $html;
	}
	
	function VerFacturaProveedor($action,$TerceroProveedor,$FacturaProveedorCabecera,$FacturaProveedorDetalle,$Empresa,$UsuarioVerificador,$parametros_retencion)
		{
	  $ctl = AutoCarga::factory("ClaseUtil");
      
 	  $html  = $ctl->LimpiarCampos();
      $html .= $ctl->RollOverFilas();
 	  $html .= $ctl->AcceptDate('/');
	  $html .= $ctl->AcceptNum();
      
	  
	  $html .= "<script>";
	  $html .= "function Validar(formulario)";
	  $html .= "{";
	  		
	$html .="	if(formulario.numero_factura == '')";
	$html .="		{";
	$html .="		document.getElementById('error').innerHTML='Error... No Hay Registrada Una Factura!!';";
	$html .="		return(false);";
	$html .="		}";
	
	$html .="	if(formulario.observacion_verificacion == '')";
	$html .="		{";
	$html .="		document.getElementById('error').innerHTML='Error... Es Necesario Registrar La Observacion!!';";
	$html .="		return(false);";
	$html .="		}";
	
	$html .="		xajax_Verificar(formulario);";
	
	  $html .= "}";
	  $html .= "</script>";
	  
	  $html .= ThemeAbrirTabla('FACTURA DEL PROVEEDOR');
	  //Empieza Tab
	  $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";  
      $html .= "		<tr >\n";
	  $html .= "			<td align=\"center\" width=\"10%\" colspan=\"6\"><b>".$Empresa['razon_social']."</b></td>\n";
	  $html .= "		</tr>\n";
	  $html .= "		<tr >\n";
	  $html .= "			<td align=\"center\" width=\"10%\" colspan=\"6\"><b>".$Empresa['tipo_id_tercero']."-".$Empresa['id']."</b></td>\n";
	  $html .= "		</tr>\n";
	  $html .= "		<tr >\n";
	  $html .= "			<td align=\"center\" width=\"10%\" colspan=\"6\"><b>".$Empresa['direccion']."</b></td>\n";
	  $html .= "		</tr>\n";
	  $html .= "		<tr >\n";
	  $html .= "			<td align=\"center\" width=\"10%\" colspan=\"6\"><b>".$Empresa['telefono']."</b></td>\n";
	  $html .= "		</tr>\n";
	  
      $html .= "		<tr >\n";
	  $html .= "			<td width=\"10%\" class=\"formulacion_table_list\">#FACTURA</td>\n";
	  $html .= "			<td width=\"20%\">".$_REQUEST['numero_factura']."</td>\n";
	  $html .= "			<td width=\"10%\" class=\"formulacion_table_list\">FECHA</td>\n";
	  $html .= "			<td width=\"20%\">".$FacturaProveedorCabecera[0]['fecha_registro']."</td>\n";
	  $html .= "			<td width=\"10%\" class=\"formulacion_table_list\">USUARIO</td>\n";
	  $html .= "			<td width=\"20%\">".$FacturaProveedorCabecera[0]['nombre']."</td>\n";
	  $html .= "		</tr>\n";
	  $html .= "  </table>\n";
	  $html .= "  <br>";
	  $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";  
      $html .= "		<tr >\n";
	  $html .= "			<td width=\"10%\" class=\"formulacion_table_list\">PROVEEDOR </td>\n";
	  $html .= "			<td width=\"20%\">".$TerceroProveedor[0]['nombre_tercero']."</td>\n";
	  $html .= "			<td width=\"10%\">".$TerceroProveedor[0]['tipo_id_tercero']."-".$TerceroProveedor[0]['tercero_id']."</td>\n";
	  $html .= "			<td width=\"10%\" class=\"formulacion_table_list\">DIRECCION</td>\n";
	  $html .= "			<td width=\"20%\">".$TerceroProveedor[0]['direccion']."</td>\n";
	  $html .= "			<td width=\"10%\" class=\"formulacion_table_list\">Fecha Documento</td>\n";
	  $html .= "			<td width=\"20%\">".$FacturaProveedorCabecera[0]['fecha_registro']."</td>\n";
	  $html .= "		</tr>\n";
	  $html .= "  </table>\n";
    $html .= "  <br>";
                	 $html .= "                <fieldset class=\"fieldset\" style=\"width:50%\">\n";
                   $html .= "                  <legend class=\"normal_10AN\">\n";
                   $html .= "                    <img src=\"".GetThemePath()."/images/informacion.png\">OBSERVACIONES\n";
                	  $html .= "                  </legend><b>\n";
                  	$html .= $FacturaProveedorCabecera[0]['observaciones'];
                	  $html .= "                 </b> </fieldset>\n";
    $html .= "<br>";
	  $html .= "<hr>";
	  $html .= "<center>\n";
	  $html .= "  <label class=\"label_error\">CRUCE DE RECEPCIONES POR ORDEN DE COMPRA</label>\n";
	  $html .= "</center>\n";
	  
	  $html .= "	<hr>";
	  $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\" rules=\"all\">\n";  
		//print_r($FacturaProveedorDetalle);
		$html .= "		<tr class=\"formulacion_table_list\">\n";
		$html .= "			<td width=\"5%\">Recp.Parcial</td>\n";
		$html .= "			<td >Producto</td>\n";
		$html .= "			<td >Lote</td>\n";
		$html .= "			<td >Fecha Vencimiento</td>\n";
		$html .= "			<td >Cantidad</td>\n";
		$html .= "			<td >Unitario</td>\n";
		$html .= "			<td >%Iva</td>\n";
		$html .= "			<td >SubTotal</td>\n";
		$html .= "			<td >Total</td>\n";
		$html .= "		</tr>\n";
		foreach($FacturaProveedorDetalle as $k=>$valor)
		{
		
		$html .= "   	<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" align=\"center\">\n";
		$html .= "			<td >".$valor['recepcion_parcial_id']."</td>\n";
		$html .= "			<td align=\"left\">".$valor['codigo_producto']." ".$valor['descripcion']."</td>\n";
		$html .= "			<td >".$valor['lote']."</td>\n";
		$html .= "			<td >".$valor['fecha_vencimiento']."</td>\n";
		$html .= "			<td >".$valor['cantidad']."</td>\n";
		$html .= "			<td align=\"left\">$".FormatoValor($valor['valor_unitario'],4)."</td>\n";
		$html .= "			<td align=\"left\">".$valor['porc_iva']."</td>\n";
    	$subtotal = $subtotal+$valor['subtotal'];
		$IvaTotal = $IvaTotal + $valor['iva_total'];
		$Total = $Total + $valor['total'];
		$html .= "			<td align=\"left\">$".FormatoValor(($valor['subtotal']),4)."</td>\n";
		$html .= "			<td align=\"left\">$".FormatoValor($valor['total'],4)."</td>\n";
		$html .= "		</tr>\n";
		}
		$html .= "  </table>\n";
		$html .= "	<hr>";
		
		if($parametros_retencion['sw_rtf']=='2' || $parametros_retencion['sw_rtf']=='3')
					if($subtotal >= $parametros_retencion['base_rtf'])
					$retencion_fuente = $subtotal*($FacturaProveedorCabecera[0]['porc_rtf']/100);
					
				if($parametros_retencion['sw_ica']=='2' || $parametros_retencion['sw_ica']=='3')
					if($subtotal >= $parametros_retencion['base_ica'])
					$retencion_ica = $subtotal*($FacturaProveedorCabecera[0]['porc_ica']/1000);
					
				if($parametros_retencion['sw_reteiva']=='2' ||$parametros_retencion['sw_reteiva']=='3')
					if($subtotal >= $parametros_retencion['base_reteiva'])
						$retencion_iva = $IvaTotal*($FacturaProveedorCabecera[0]['porc_rtiva']/100);
						
		$html .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "				<tr align=\"center\" class=\"label\">";
		$html .= "					<td>";
		$html .= "						<u>SUBTOTAL</u>";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<u>IVA</u>";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<u>RET-FTE</u>";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<u>RETE-ICA</u>";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<u>RETE-IVA</u>";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<u>DESCUENTO</u>";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<u>VALOR TOTAL</u>";
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "				<tr align=\"center\" >";
		$html .= "					<td>";
		$html .= "						$".FormatoValor($subtotal,4);
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						$".FormatoValor($IvaTotal,4);
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						$".FormatoValor($retencion_fuente,4);
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						$".FormatoValor($retencion_ica,4);
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						$".FormatoValor($retencion_iva,4);
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						$".FormatoValor($FacturaProveedorCabecera[0]['valor_descuento'],4);
		$html .= "					</td>";
		$html .= "					<td>";
		$total = ((((($Total)-$retencion_fuente)-$retencion_ica)-$retencion_iva)-$FacturaProveedorCabecera[0]['valor_descuento']);
		$html .= "						$".FormatoValor($total,4);
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "			</table>";
	 /* $html .= "	<table align=\"rigth\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";  
	  $Total = $SubTotales+$IvaTotal;
	  $ValorRTE= $SubTotalSinIva*($FacturaProveedorCabecera[0]['porc_rtf']/100);
    $ValorICA= $SubTotalSinIva*($FacturaProveedorCabecera[0]['porc_ica']/1000);
	  
    $html .= "		<tr >\n";
	  $html .= "			<td width=\"10%\"><b>Impuesto :</b>$".FormatoValor($IvaTotal,4)."</td>\n";
	  $html .= "		</tr >\n";
	  $html .= "		<tr >\n";
	  $html .= "			<td width=\"30%\"><b>SubTotal :</b>$".FormatoValor(($SubTotales-$IvaTotal),4)."</td>\n";
	  $html .= "		</tr >\n";
	  $html .= "		<tr >\n";
    $html .= "		<tr >\n";
	  $html .= "			<td width=\"30%\"><b>Flete :</b>$".FormatoValor($FacturaProveedorCabecera[0]['valor_flete'],4)."</td>\n";
	  $html .= "		</tr >\n";
	  $html .= "		<tr >\n";
	  $html .= "			<td width=\"10%\"><b>Descuento :</b>$".FormatoValor($FacturaProveedorCabecera[0]['valor_descuento'],4)."</td>\n";
	  $html .= "		</tr >\n";
	  $html .= "		<tr >\n";
	  $html .= "			<td width=\"5%\"><b>Rete. Fuente :</b>$".FormatoValor($ValorRTE,4)."</td>\n";
	  $html .= "		</tr >\n";
    $html .= "		<tr >\n";
	  $html .= "			<td width=\"5%\"><b>ICA :</b>$".FormatoValor($ValorICA,4)."</td>\n";
	  $html .= "		</tr >\n";
	  $html .= "		<tr >\n";
	  $html .= "			<td width=\"5%\"><b>Valor Total :$".FormatoValor((($SubTotales-$FacturaProveedorCabecera[0]['valor_descuento'])-$ValorRTE)-$ValorICA,4)."</b></td>\n";
	  $html .= "		</tr>\n";
	  //print_r($FacturaProveedorCabecera);
	  $html .= "  </table>\n";*/
	  $html .= "<br>";
	  $disabled="";
	  if($FacturaProveedorCabecera[0]['sw_verificado']=='1')
	  {
			$disabled=" disabled ";
			if($FacturaProveedorCabecera[0]['calificacion_verificacion']=='1')
				$Calificacion = "<b><u><font color=\"green\">BIEN</font></u></b> ";
				else
					$Calificacion = "<b><u><font color=\"red\">MAL</font></u></b> ";
	  
	  $html .= "                <fieldset class=\"fieldset\" style=\"width:50%\">\n";
      $html .= "                  <legend class=\"normal_10AN\">\n";
      $html .= "                    <img src=\"".GetThemePath()."/images/informacion.png\">DOCUMENTO VERIFICADO\n";
	  $html .= "                  </legend>\n";
     
	 $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";  
	 $html .= "		<tr class=\"formulacion_table_list\" >\n";
	$html .= "			<td width=\"20%\" colspan=\"7\">Resultados De La Verificacion</td>\n";
	$html .= "		</tr>\n";
	$html .= "		<tr >\n";
	$html .= "			<td width=\"40%\"><b>CALIFICACION VERIFICACION</b></td><td width=\"60%\">".$Calificacion."</td>\n";
	$html .= "		</tr >\n";
	$html .= "		<tr >\n";
	$html .= "			<td width=\"40%\"><b>OBSERVACIONES</b></td><td width=\"60%\">".$FacturaProveedorCabecera[0]['observacion_verificacion']."</td>\n";
	$html .= "		</tr >\n";
	$html .= "		<tr >\n";
	$html .= "			<td width=\"40%\"><b>RESPONSABLE VERIFICACION</b></td><td width=\"60%\">".$UsuarioVerificador['nombre']."</td>\n";
	$html .= "		</tr >\n";
	$html .= "		<tr >\n";
	$html .= "			<td width=\"40%\"><b>FECHA VERIFICACION</b></td><td width=\"60%\">".$FacturaProveedorCabecera[0]['fecha_verificacion']."</td>\n";
	$html .= "		</tr >\n";
	$html .= "		<tr ".$clase." onmouseout=mOut(this,\"".$bck."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
	$html .= "			<td align=\"center\" colspan=\"2\">\n";
	$html .= "			</td>";
	$html .= "		</tr>\n";
	$html .= "	</table>";
	$html .= "                  </fieldset>\n";
	  
	  }
	  
	  $html .= "	<table width=\"90%\" align=\"center\">\n";
	  $html .= "		<tr><td align=\"center\">\n";
	  $html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
	  $html .= "				<input type=\"button\" ".$disabled." class=\"input-submit\" value=\"Verificar\" onclick=\"xajax_VerificarFactura('".$_REQUEST['numero_factura']."','".$_REQUEST['codigo_proveedor_id']."');\"><input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
	  $html .= "			</form>\n";
	  $html .= "		</td></tr>\n";
	  $html .= "	</table>\n";
      $html .= ThemeCerrarTabla();
	  $html .= $this->CrearVentana(640,"OBSERVACIONES - CONTROL INTERNO");
      return $html;
	}
    
	
	
	}
?>