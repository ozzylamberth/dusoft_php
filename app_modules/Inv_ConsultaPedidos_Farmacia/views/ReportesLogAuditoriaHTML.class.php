<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: ReportesLogAuditoriaHTML.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ReportesLogAuditoriaHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class ReportesLogAuditoriaHTML	
	{
    /**
    * Constructor de la clase
    */
    function ReportesLogAuditoriaHTML(){}
    /**
    *
    * @return string
    */
    function Forma($action,$request,$lista,$esm_empresas,$conteo, $pagina)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      //print_r($_REQUEST);
      //print_r($request['empresa_id']);
	  $empresa = $request['empresa_id'];
	  
 			$html  = $ctl->LimpiarCampos();
 			$html .= $ctl->RollOverFilas();
 			$html .= $ctl->AcceptDate('/');
      $html .= ThemeAbrirTabla('PEDIDOS DE FARMACIA');
      $html .= "<form name=\"productos\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"50%\" align=\"center\">\n";
      
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
			$html .= "		      <table width=\"70%\">\n";
      /*
      $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">ESM</td>\n";
      $html .= "              <td colspan=\"2\">\n";
      $html .= "                <select name=\"buscador[esm_empresa]\" id=\"esm_empresa\" class=\"select\" style=\"width:80%\">\n";
      $html .= "              <option value=\"\">--- TODOS ---</option>";
                      foreach($esm_empresas as $key=>$valor)
                        {
      $html .= "              <option value=\"".$valor['tipo_id_tercero']."@".$valor['tercero_id']."\">".$valor['nombre_tercero']."-[".$valor['tercero_id']."]</option>";
                        }
      $html .= "                </select>";
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
      $html .= "            <tr>";
      $html .= "              <td class=\"normal_10AN\">DESPACHO POR</td>\n";
      $html .= "              <td class=\"normal_10AN\">DISTRIBUCION <input type=\"radio\" name=\"buscador[radio]\" value=\"inv_bodegas_movimiento_despacho_campania\" class=\"input-radio\">\n";
      $html .= "              SUMINISTRO <input type=\"radio\" name=\"buscador[radio]\" value=\"inv_bodegas_movimiento_traslados_esm\" class=\"input-radio\"></td>\n";
      $html .= "			      </tr>\n";
      */
      $html .= "            <tr>";
      $html .= "              <td class=\"normal_10AN\">NUMERO DE PEDIDO</td>\n";
      $html .= "                <td><input type=\"text\" name=\"buscador[pedido_id]\" id=\"pedido_id\" class=\"input-text\" value=\"".$request['pedido_id']."\" style=\"width:60%\"></td>\n";
      $html .= "            </tr>";
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
			$html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
   		$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.productos)\">\n";
      $html .= "				      </td>\n";
			$html .= "			      </tr>\n";
			$html .= "		      </table>\n";
			$html .= "	      </fieldset>\n";
			$html .= "	    </td>\n";
			$html .= "	  </tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
      
      if(!empty($lista))
      {
		$reporGral = ModuloGetURL('app','Inv_ConsultaPedidos_Farmacia','controller','MostrarPedidosGral')."&pedido=".$request['pedido_id'];
		
        $html .= "	<table align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr>\n";
        $html .= "		   <td>\n";
        $html .= "          <a href=\"".$reporGral."\" target=\"_new\" class=\"label_error\"  title=\"REPORTE GRAL\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' >&nbsp;Impresi&oacute;n gral</a>\n";
        $html .= "		   </td>\n";
        $html .= "		</tr>\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "			<td width=\"5%\">NUMERO DE PEDIDO</td>\n";
		$html .= "			<td width=\"10%\">FARMACIA</td>\n";
		$html .= "			<td width=\"10%\">USUARIO</td>\n";
		$html .= "			<td align=\"center\" width=\"5%\">FECHA REGISTRO</td>\n";
		$html .= "			<td width=\"5%\">OP</td>\n";
  			
        $html .= "		</tr>\n";
        $reporte = new GetReports();
        foreach($lista as $k1 => $dtl)
        {
          
				
          $mostrar = $reporte->GetJavaReport('app','PedidosFarmacia_A_BodegaPrincipal','Pedido',
																							array("solicitud_prod_a_bod_ppal_id"=>$dtl['solicitud_prod_a_bod_ppal_id'],"empresa_id"=>$dtl['farmacia_id'],"bodega"=>$dtl['bodega'],"centroU"=>$dtl['centro_utilidad']),
																							array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $funcion = $reporte->GetJavaFunction();
          
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";

          $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
  				$html .= "			<td>".$dtl['solicitud_prod_a_bod_ppal_id']."</td>\n";
  				$html .= "			<td>".$dtl['razon_social']."</td>\n";
  				$html .= "			<td>".$dtl['nombre']."</td>\n";
  				$html .= "			<td align=\"center\">".$dtl['fecha']."</td>\n";
  				$html .= "				<td align=\"center\" >\n";
          $html .= "				".$mostrar."\n";
          $html .= "					<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE DEL PEDIDO\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' >\n";
          $html .= "					[ IMPRIMIR PEDIDO]</a></center>\n";
          $html .= "			</td>\n";	
          $html .= "		</tr>\n";
        }
        $html .= "		</table>\n";
				$html .= "		<br>\n";
        $pgn = AutoCarga::factory("ClaseHTML");
				$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
      else if(!empty($request))
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
        $html .= "</center>\n";
      }
      $html .= "	<table width=\"90%\" align=\"center\">\n";
			$html .= "		<tr><td align=\"center\">\n";
			$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "			</form>\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
      $html .= ThemeCerrarTabla();

      return $html;
    }
         
    /**
    *
    * @return string
    */
    function Forma_EliminarReserva($action,$request,$lista,$esm_empresas,$conteo, $pagina)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      //print_r($_REQUEST);
 			$html  = $ctl->LimpiarCampos();
 			$html .= $ctl->RollOverFilas();
 			$html .= $ctl->AcceptDate('/');
      $html .= ThemeAbrirTabla('PEDIDOS DE FARMACIA');
      $html .= "<form name=\"productos\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"50%\" align=\"center\">\n";
      
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">BUSQUEDA DE PEDIDOS</legend>\n";
			$html .= "		      <table width=\"70%\" align=\"center\">\n";
      $html .= "            <tr>";
      $html .= "              <td class=\"normal_10AN\">NUMERO DE PEDIDO</td>\n";
      $html .= "                <td><input type=\"text\" name=\"buscador[pedido_id]\" id=\"pedido_id\" class=\"input-text\" value=\"".$request['pedido_id']."\" style=\"width:60%\"></td>\n";
      $html .= "            </tr>";
      $html .= "			      <tr align=\"center\">\n";
      $html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
			$html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
   		$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.productos)\">\n";
      $html .= "				      </td>\n";
			$html .= "			   </tr>\n";
			$html .= "		      </table>\n";
			$html .= "	      </fieldset>\n";
			$html .= "	    </td>\n";
			$html .= "	  </tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
      
      if(!empty($lista))
      {
       
        
        $html .= "	<table align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
  			$html .= "			<td width=\"1%\">#</td>\n";
  			$html .= "			<td width=\"10%\">FARMACIA</td>\n";
  			$html .= "			<td width=\"2%\">USUARIO</td>\n";
  			$html .= "			<td width=\"20%\">PRODUCTO</td>\n";
  			$html .= "			<td width=\"2%\">CANT/S</td>\n";
  			$html .= "			<td width=\"2%\">CANT/P</td>\n";
  			$html .= "			<td width=\"2%\">OP</td>\n";
  			
        $html .= "		</tr>\n";
        $reporte = new GetReports();
        foreach($lista as $k1 => $dtl)
        {
          
         $pedidoId = $dtl['solicitud_prod_a_bod_ppal_id'];
		 $farmacia = $dtl['razon_social'];
		 $usuarioPedido = $dtl['usuario'];
		 $codigo = $dtl['codigo_producto'];
		 $cant_sol = $dtl['cantidad_solicitada'];
		 $cant_pen = $dtl['cantidad_pendiente'];
		 
          $mostrar = $reporte->GetJavaReport('app','PedidosFarmacia_A_BodegaPrincipal','Pedido',
																							array("solicitud_prod_a_bod_ppal_id"=>$dtl['solicitud_prod_a_bod_ppal_id'],"empresa_id"=>$dtl['farmacia_id'],"bodega"=>$dtl['bodega'],"centroU"=>$dtl['centro_utilidad']),
																							array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $funcion = $reporte->GetJavaFunction();
          
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";

          $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
			$html .= "			<td >".$dtl['solicitud_prod_a_bod_ppal_id']."</td>\n";
			$html .= "			<td >".$dtl['razon_social']."</td>\n";
			$html .= "			<td >".$dtl['usuario']."</td>\n";
			$html .= "			<td >".$dtl['producto']."</td>\n";
			$html .= "			<td >".FormatoValor($dtl['cantidad_solicitada'],0)."</td>\n";
			$html .= "			<td >".FormatoValor($dtl['cantidad_pendiente'],0)."</td>\n";
			$html .= "				<td align=\"center\" >\n";
          $html .= "				".$mostrar."\n";
          $html .= "					<a onclick=\"xajax_BorrarItem_Reservado('".$dtl['solicitud_prod_a_bod_ppal_id']."','".$dtl['solicitud_prod_a_bod_ppal_det_id']."','".$dtl['tabla']."','".$pedidoId."','".$farmacia."','".$usuarioPedido."','".$codigo."','".$cant_sol."','".$cant_pen."')\" class=\"label_error\"  title=\"ELIMINAR PEDIDO\"><img src=\"".GetThemePath()."/images/delete2.gif\" border='0' >\n";
          $html .= "					</a></center>\n";
          $html .= "			</td>\n";	
          $html .= "		</tr>\n";
        }
        $html .= "		</table>\n";
				$html .= "		<br>\n";
        $pgn = AutoCarga::factory("ClaseHTML");
				$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
      else if(!empty($request))
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
        $html .= "</center>\n";
      }
      $html .= "	<table width=\"90%\" align=\"center\">\n";
			$html .= "		<tr><td align=\"center\">\n";
			$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "			</form>\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
      $html .= ThemeCerrarTabla();
      $html .= $this->CrearVentana(640,'RESERVA - PEDIDOS');
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
	
	
    /*****************************************************************************
    *Forma consultar pedidos reservados eliminados             20092012
	@return string                                
    ******************************************************************************/
    function Forma_ConsultaEliminados($action,$lista,$conteo,$pagina)
    {
      $ctl = AutoCarga::factory("ClaseUtil");

	  $html  = $ctl->LimpiarCampos();
	  $html .= $ctl->RollOverFilas();
	  $html .= $ctl->AcceptDate('/');
      $html .= ThemeAbrirTabla('PEDIDOS RESERVADOS ELIMINADOS');
      
      if(!empty($lista))
      {
        
	    $html .= "	<table align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">\n";
	    $html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "			<td width=\"1%\"># PED.</td>\n";
		$html .= "			<td width=\"10%\">FARMACIA</td>\n";
		$html .= "			<td width=\"2%\">USUARIO SOLICITUD</td>\n";
		$html .= "			<td width=\"20%\">PRODUCTO</td>\n";
		$html .= "			<td width=\"2%\">CANT/S</td>\n";
		$html .= "			<td width=\"2%\">CANT/P</td>\n";
		$html .= "			<td width=\"2%\">ELIMINADO POR</td>\n";
		$html .= "			<td width=\"2%\">FECHA</td>\n";
        $html .= "		</tr>\n";
		
        foreach($lista as $k1 => $dtl)
        {  
          
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
             
          $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
		  $html .= "			<td >".$dtl['pedido_id']."</td>\n";
		  $html .= "			<td >".$dtl['farmacia']."</td>\n";
		  $html .= "			<td >".$dtl['usuario_solicitud']."</td>\n";
		  $html .= "			<td ><a title=\""."COD: ".$dtl['codigo_producto']."\">".$dtl['descripcion']."</a></td>\n";
		  $html .= "			<td >".FormatoValor($dtl['cant_solicita'],0)."</td>\n";
	      $html .= "			<td >".FormatoValor($dtl['cant_pendiente'],0)."</td>\n";
		  $html .= "			<td ><a title=\""."ID: ".$dtl['usuario_id']."\">".$dtl['usuario_ejecuta']."</a></td>\n";
		  $html .= "			<td >".$dtl['fecha_registro']."</td>\n";
          $html .= "		</tr>\n";
        }
        $html .= " </table>\n";
		$html .= "		<br>\n";
        $pgn = AutoCarga::factory("ClaseHTML");
		$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
      else
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS</label>\n";
        $html .= "</center>\n";
      }
      $html .= "	<table width=\"90%\" align=\"center\">\n";
	  $html .= "		<tr><td align=\"center\">\n";
	  $html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
	  $html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
	  $html .= "			</form>\n";
	  $html .= "		</td></tr>\n";
	  $html .= "	</table>\n";
      $html .= ThemeCerrarTabla();
     
      return $html;
    }	
	
       
  }
?>