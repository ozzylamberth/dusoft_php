<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: GestionEstadosHTML.class.php,v 1.1 2008/10/10 22:27:29 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: GestionPagosHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class GestionEstadosHTML
  {
    /**
    * Constructosr de la clase
    */
    function GestionEstadosHTML(){}
    /**
		* Funcion donde se crea la forma que imprime el listado de facturas
    *
    * @param array $action Arreglo con los links de la aplicacion 
    * @param array $request Arreglo con los datos del request
    * @param array $tiposdocumentos Arreglo con los datos de los tipos de documentos
    * @param array $facturas Arreglo con los datos de las facturas
    * @param array $$estados Arreglo con los datos de los estados de la facturas
    * @param integer $conteo Referencia a la cantidad de datos devueltos
    * @param integer $pagina Referencia a la pagina del paginador
    *
		* @return string 
		*/
		function FormaListadoFacturas($action,$request,$tiposdocumentos,$facturas,$estados,$conteo,$pagina)
		{
      $ctl = AutoCarga::factory('ClaseUtil');
      $html  = $ctl->LimpiarCampos();
      $html .= $ctl->RollOverFilas();
      $html .= $ctl->AcceptDate("/");
			$html .= "<script language=\"javascript\">\n";
      $html .= "	function SeleccionarCheckBox(frm,valor)\n";
			$html .= "	{\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'checkbox': \n";
      $html .= "          frm[i].checked = valor; \n";
      $html .= "        break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "	}\n";			
      $html .= "	function EvaluarDatos(frm)\n";
			$html .= "	{\n";
			$html .= "	  flag = false\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'checkbox': \n";
      $html .= "          if(frm[i].checked == true) \n";
      $html .= "          {\n";
      $html .= "            flag = true;\n";
      $html .= "            break;\n";
      $html .= "          }\n";
      $html .= "        break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(flag == false)\n";
			$html .= "		{\n";
			$html .= "		  document.getElementById('error').innerHTML = 'NO SE HA SELECCIONADO NINGUN DOCUMENTO PARA SER ADICIONADO A LA ORDEN DE PAGO';\n";
			$html .= "		  return;\n";
			$html .= "		}\n";
			$html .= "		if(frm.nuevo_estado.value == '-1')\n";
			$html .= "		{\n";
			$html .= "		  document.getElementById('error').innerHTML = 'SE DEBE SELECCIONR EL ESTADO AL CUAL PASARAN LAS FACTURAS SELECCIONADAS';\n";
			$html .= "		  return;\n";
			$html .= "		}\n";
			$html .= "		frm.submit();\n";
			$html .= "	}\n";
			$html .= "</script>\n";			
      $html .= ThemeAbrirTabla("CUENTAS X PAGAR - CAMBIOS DE ESTADO");
			$html .= "<form name=\"buscador\" action=\"".$action['buscar']."\" method=\"post\">\n";
			$html .= "	<table width=\"70%\" align=\"center\">\n";
			$html .= "	  <tr>\n";
			$html .= "	    <td>\n";
			$html .= "	      <fieldset class=\"fieldset\">\n";
			$html .= "	        <legend class=\"normal_10AN\">BUSCAR FACTURAS</legend>\n";
			$html .= "	        <table class=\"normal_10AN\" width=\"100%\" align=\"center\">\n";
			$html .= "            <tr>\n";
 			$html .= "			        <td width=\"20%\" >PREFIJO</td>\n";
      $html .= "			        <td width=\"%\" >\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[prefijo]\"  style=\"width:70%\" value=\"".$request['prefijo']."\">\n";
			$html .= "			        </td>\n";
 			$html .= "			        <td width=\"20%\" >NUMERO</td>\n";
			$html .= "			        <td width=\"25%\" >\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[factura]\" style=\"width:70%\" onkeypress=\"return acceptNum(event)\" value=\"".$request['factura']."\">\n";
			$html .= "			        </td>\n";
      $html .= "            </tr>\n";
      $html .= "            <tr>\n";
			$html .= "			        <td >Nº RADICACION</td>\n";
      $html .= "			        <td colspan=\"3\">\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[numero_radicacion]\"  style=\"width:31%\" value=\"".$request['numero_radicacion']."\">\n";
			$html .= "			        </td>\n";
      $html .= "            </tr>\n";
      $html .= "			      <tr>\n";
      $html .= "              <td width=\"25%\">TIPO DOCUMENTO</td>\n";
      $html .= "				      <td>\n";
      $html .= "					      <select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
      $html .= "						      <option value='-1'>-----SELECCIONAR-----</option>\n";
      
      $chk = "";
      foreach($tiposdocumentos as $key => $dtl)
      {
        ($dtl['tipo_id_tercero'] == $request['tipo_id_tercero'])? $chk = "selected": $chk = "";
        $html.= "						      <option value='".$dtl['tipo_id_tercero']."' $chk >".$dtl['descripcion']."</option>\n";			
      }
      
      $html .= "						    </select>\n";
      $html .= "				      </td>\n";
      $html .= "				      <td >DOCUMENTO</td>\n";
      $html .= "				      <td>\n";
      $html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[tercero_id]\" style=\"width:70%\" maxlength=\"32\" value=\"".$request['tercero_id']."\">\n";
      $html .= "				      </td>\n";
      $html .= "			      </tr>\n";
      $html .= "			      <tr>\n";
			$html .= "				      <td >FECHAS RADICACION</td>\n";
			$html .= "				      <td>\n";
			$html .= "					      <input type=\"text\" class=\"input-text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicio']."\">\n";
			$html .= "				        ".ReturnOpenCalendario('buscador','fecha_inicio','/',1)."\n";
      $html .= "              </td>\n";			
      $html .= "				      <td colspan=\"2\">\n";
			$html .= "					      <input type=\"text\" class=\"input-text\" name=\"buscador[fecha_fin]\" id=\"fecha_fin\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_fin']."\">\n";
			$html .= "				        ".ReturnOpenCalendario('buscador','fecha_fin','/',1)."\n";
      $html .= "              </td>\n";
			$html .= "			      </tr>\n";
      $html .= "            <tr>\n";
			$html .= "			        <td colspan=\"4\" align=\"center\">\n";
			$html .= "			          <table align=\"center\" >\n";
			$html .= "			            <tr >\n";
			$html .= "			              <td align=\"center\" >\n";
			$html .= "				              <input  type=\"submit\" class=\"input-submit\" name=\"buscador[buscar]\" value=\"Buscar\">\n";
			$html .= "		                </td>\n";
			$html .= "			              <td align=\"center\" >\n";
			$html .= "				              <input  type=\"button\" class=\"input-submit\" name=\"buscador[limpiar]\" value=\"LimpiarCampos\" onclick=\"LimpiarCampos(document.buscador)\">\n";
			$html .= "		                </td>\n";
			$html .= "		              </tr>\n";
			$html .= "		            </table>\n";
			$html .= "		          </td>\n";
      $html .= "            </tr>\n";
			$html .= "	        </table>\n";
			$html .= "        </fieldset>\n"; 
			$html .= "      </td>\n"; 
			$html .= "    </tr>\n"; 
			$html .= "  </table>\n"; 
			$html .= "</form>\n"; 
			
      if(sizeof($facturas) > 0)
      {
   			$html .= "<form name=\"crear_og\" action=\"".$action['cambiar_estado']."\" method=\"post\">\n";
        $html .= "  <div id=\"cambio_estado\" style=\"display:none\">\n";
        $html .= "    <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "      <tr>\n";
        $html .= "        <td width=\"30%\" class=\"formulacion_table_list\">CAMBIAR ESTADOA A:</td>\n";
        $html .= "		    <td>\n";
        $html .= "			    <select name=\"nuevo_estado\" class=\"select\">\n";
        $html .= "				    <option value='-1'>-----SELECCIONAR-----</option>\n";
        
        $chk = "";
        foreach($estados as $key => $dtl)
        {
          $html.= "					  <option value='".$dtl['cxp_estado']."'>".$dtl['cxp_estado_descripcion']."</option>\n";			
        }
        
        $html .= "			    </select>\n";
        $html .= "			  </td>\n";
        $html .= "      </tr>\n";
        $html .= "    </table><br>\n";
        $html .= "  </div><br>\n";
        $html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
        $html .= "			<tr class=\"formulacion_table_list\" height=\"21\">\n";
        $html .= "				<td width=\"5%\">Nª RADIC.</td>\n";
        $html .= "				<td width=\"7%\">FACTURA</td>\n";
        $html .= "				<td width=\"8%\">F. FACTURA</td>\n";
        $html .= "				<td width=\"8%\">F. RADIC.</td>\n";
        $html .= "				<td width=\"8%\">ESTADO</td>\n";
        $html .= "				<td width=\"8%\">ULTIMO ESTADO</td>\n";
        $html .= "				<td width=\"%\" >CLIENTE</td>\n";
        $html .= "				<td width=\"9%\">TOTAL</td>\n";
        $html .= "				<td width=\"15%\">TIPO CUENTA</td>\n";
        $html .= "				<td width=\"3%\">\n";
        $html .= "          <input type=\"checkbox\" name=\"todos\" onclick=\"SeleccionarCheckBox(document.crear_og,this.checked)\">\n";
        $html .= "        </td>\n";
        $html .= "			</tr>";
        
        $estilo='modulo_list_oscuro'; 
        $background = "#CCCCCC";
        
        $est = ""; $dsp = "block";
        foreach($facturas as $key => $detalle )
        {
          ($estilo == 'modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; ;  
          ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
          if($est == "") $est = $detalle['cxp_estado_descripcion'];
          
          $html .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
          $html .= "				<td >".$detalle['cxp_radicacion_id']."</td>\n";
          $html .= "				<td >".$detalle['prefijo_factura']." ".$detalle['numero_factura']."</td>\n";
          $html .= "				<td align=\"center\" >".$detalle['fecha_documento']."</td>\n";
          $html .= "				<td align=\"center\" >".$detalle['fecha_radicacion']."</td>\n";
          $html .= "				<td >".$detalle['cxp_estado_descripcion']."</td>\n";
          $f = $detalle['fecha_estado'];
          if($f == "") $f = $detalle['fecha_registro'];
          $html .= "				<td align=\"center\">".$f."</td>\n";
          
          $html .= "				<td >".$detalle['tipo_id_tercero']." ".$detalle['tercero_id']." <b>".$detalle['nombre_tercero']."</b></td>\n";
          $html .= "				<td align=\"right\"  >".formatoValor($detalle['valor_total'])."</td>\n";
          $html .= "				<td align=\"justify\">".$detalle['tipo_cxp_descripcion']."</td>\n";
          $html .= "				<td align=\"center\" >\n";
          $html .= "          <input type=\"checkbox\" name=\"factura[".$detalle['prefijo']."][".$detalle['numero']."]\">\n";
          $html .= "        </td>\n";
          $html .= "			</tr>\n";
          
          if($est != $detalle['cxp_estado_descripcion']) $dsp = "none";
        }
        $html .= "	</table>\n";
        $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" >\n";
  			$html .= "	  <tr>\n";
  			$html .= "		  <td align=\"right\"><br>\n";
  			$html .= "				<input class=\"input-submit\" type=\"button\" name=\"cambio\" value=\"Cambiar Estado\" onclick=\"EvaluarDatos(document.crear_og)\">\n";
  			$html .= "		  </td>\n";
  			$html .= "	  </tr>\n";
  			$html .= "  </table>\n";
  			$html .= "  <center>\n";
  			$html .= "    <div id=\"error\" class=\"label_error\"></div>\n";
  			$html .= "  </center>\n";
        $html .= "</form><br>\n";         
				$pghtml = AutoCarga::factory("ClaseHTML");
        $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
    		$html .= "<br>\n";
      }
      else
      {
        if(!empty($request))
        {
          $html .= "<center>";
          $html .= "  <label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
          $html .= "</center>";
        }
      }
			
			$html .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\" id='lll'>\n";
			$html .= "				<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "				</form>\n";
			$html .= "			</td></tr>\n";
			$html .= "		</table>\n";
      $html .= "<script>\n";
      $html .= "  document.getElementById('cambio_estado').style.display = '".$dsp."'\n";
      $html .= "</script>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
    /**
		* Funcion donde se crea la forma que lista las preordenes de pago
    *
    * @param array $action Arreglo con los links de la aplicacion 
    * @param array $request Arreglo con los datos del request
    * @param integer $offset Referencia al conteo del paginador
    * @param array $ordpg Arreglo con los datos de las preordenes de pagos
    * @param integer $conteo Referencia a la cantidad de datos devueltos
    * @param integer $pagina Referencia a la pagina del paginador
    *
		* @return string 
		*/
		function FormaListadoOrdenesPago($action,$request,$offset,$ordpg,$conteo,$pagina)
		{
      $ctl = AutoCarga::factory('ClaseUtil');
      $html  = $ctl->LimpiarCampos();
      $html .= $ctl->RollOverFilas();
      $html .= $ctl->AcceptDate("/");
      $html .= $ctl->AcceptNum(false);
      $html .= "<script>\n";
      $html .= "  function IngresarNumeroRadicacion(cxp_orden_pago_id,num_orden_gasto)\n";
      $html .= "  {\n";
      $html .= "    xajax_IngresarNumeroRadicacion(cxp_orden_pago_id,num_orden_gasto,'".$offset."');\n";
      $html .= "  }\n";
      $html .= "  function IngresarEstadoObservacion(cxp_orden_pago_id)\n";
      $html .= "  {\n";
      $html .= "    xajax_IngresarEstadoObservacion(cxp_orden_pago_id,'".$offset."');\n";
      $html .= "  }\n";
      $html .= "  function DetallePreOrden(cxp_orden_pago_id)\n";
      $html .= "  {\n";
      $html .= "    url = \"".$action['detalle']."\"+\"&cxp_orden_pago_id=\"+cxp_orden_pago_id; \n";
      $html .= "    document.location = url;\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      
      $html .= ThemeAbrirTabla("CUENTAS X PAGAR - LISTADO PRE-ORDENES DE PAGOS");
			$html .= "<form name=\"buscador\" id=\"buscador\" action=\"".$action['buscar']."\" method=\"post\">\n";
			$html .= "	<table width=\"70%\" align=\"center\">\n";
			$html .= "	  <tr>\n";
			$html .= "	    <td>\n";
			$html .= "	      <fieldset class=\"fieldset\">\n";
			$html .= "	        <legend class=\"normal_10AN\">BUSCAR FACTURAS</legend>\n";
			$html .= "	        <table class=\"normal_10AN\" width=\"100%\" align=\"center\">\n";
			$html .= "            <tr>\n";
 			$html .= "			        <td width=\"20%\" >PREFIJO</td>\n";
      $html .= "			        <td width=\"%\" >\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[prefijo]\"  style=\"width:70%\" value=\"".$request['prefijo']."\">\n";
			$html .= "			        </td>\n";
 			$html .= "			        <td width=\"20%\" >NUMERO</td>\n";
			$html .= "			        <td width=\"25%\" >\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[factura]\" style=\"width:70%\" onkeypress=\"return acceptNum(event)\" value=\"".$request['factura']."\">\n";
			$html .= "			        </td>\n";
      $html .= "            </tr>\n";
      $html .= "            <tr>\n";
			$html .= "			        <td >Nº PRE-ORDEN</td>\n";
      $html .= "			        <td >\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[numero_orden_pago]\" onkeypress=\"return acceptNum(event)\" style=\"width:70%\" value=\"".$request['numero_orden_pago']."\">\n";
			$html .= "			        </td>\n";			
      $html .= "			        <td >Nº RADICACION</td>\n";
      $html .= "			        <td >\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[numero_radicacion]\" onkeypress=\"return acceptNum(event)\" style=\"width:70%\" value=\"".$request['numero_radicacion']."\">\n";
			$html .= "			        </td>\n";
      $html .= "            </tr>\n";      
      $html .= "            <tr>\n";
			$html .= "			        <td >Nº ORDEN GASTO</td>\n";
      $html .= "			        <td colspan=\"3\">\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[numero_radicacion_ext]\" onkeypress=\"return acceptNum(event)\" style=\"width:30%\" value=\"".$request['numero_radicacion_ext']."\">\n";
			$html .= "			        </td>\n";
      $html .= "            </tr>\n";
      $html .= "			      <tr>\n";
			$html .= "				      <td >FECHAS PRE-ORDEN</td>\n";
			$html .= "				      <td>\n";
			$html .= "					      <input type=\"text\" class=\"input-text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicio']."\">\n";
			$html .= "				        ".ReturnOpenCalendario('buscador','fecha_inicio','/',1)."\n";
      $html .= "              </td>\n";			
      $html .= "				      <td colspan=\"2\">\n";
			$html .= "					      <input type=\"text\" class=\"input-text\" name=\"buscador[fecha_fin]\" id=\"fecha_fin\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_fin']."\">\n";
			$html .= "				        ".ReturnOpenCalendario('buscador','fecha_fin','/',1)."\n";
      $html .= "              </td>\n";
			$html .= "			      </tr>\n";
      $html .= "            <tr>\n";
			$html .= "			        <td colspan=\"4\" align=\"center\">\n";
			$html .= "			          <table align=\"center\" >\n";
			$html .= "			            <tr >\n";
			$html .= "			              <td align=\"center\" >\n";
			$html .= "				              <input  type=\"submit\" class=\"input-submit\" name=\"buscador[buscar]\" value=\"Buscar\">\n";
			$html .= "		                </td>\n";
			$html .= "			              <td align=\"center\" >\n";
			$html .= "				              <input  type=\"button\" class=\"input-submit\" name=\"buscador[limpiar]\" value=\"LimpiarCampos\" onclick=\"LimpiarCampos(document.buscador)\">\n";
			$html .= "		                </td>\n";
			$html .= "		              </tr>\n";
			$html .= "		            </table>\n";
			$html .= "		          </td>\n";
      $html .= "            </tr>\n";
			$html .= "	        </table>\n";
			$html .= "        </fieldset>\n"; 
			$html .= "      </td>\n"; 
			$html .= "    </tr>\n"; 
			$html .= "  </table>\n"; 
			$html .= "</form>\n"; 
			
      if(sizeof($ordpg) > 0)
      {
        $pghtml = AutoCarga::factory("ClaseHTML");
        $html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
        $html .= "			<tr class=\"formulacion_table_list\" height=\"21\">\n";
        $html .= "				<td width=\"10%\" >Nª PRE-ORDEN</td>\n";
        $html .= "				<td width=\"10%\" >F. PRE-ORDEN</td>\n";
        $html .= "				<td width=\"20%\">RESPONSABLE</td>\n";
        $html .= "				<td width=\"%\"  >OBSERVACION ESTADO</td>\n";
        $html .= "				<td width=\"10%\">ORDEN GASTO</td>\n";
        $html .= "				<td width=\"10%\" colspan=\"2\">\n";
        $html .= "          OPCIONES\n";
        $html .= "        </td>\n";
        $html .= "			</tr>";
        
        $estilo='modulo_list_oscuro'; 
        $background = "#CCCCCC";
        
        foreach($ordpg as $key => $detalle )
        {
          ($estilo == 'modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; ;  
          ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
                  
          $html .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
          $html .= "				<td >".$detalle['cxp_orden_pago_id']."</td>\n";
          $html .= "				<td align=\"center\">".$detalle['fecha_registro']."</td>\n";
          $html .= "				<td >".$detalle['nombre']."</td>\n";
          $html .= "				<td >".$detalle['observacion_estado']."</td>\n";
          $html .= "				<td >".$detalle['num_orden_gasto']."</td>\n";       
          $html .= "				<td align=\"center\" >\n";
          $html .= "				  <a href=\"".$action['acciones'].URLRequest(array("opcion"=>"pagar","cxp_orden_pago_id"=>$detalle['cxp_orden_pago_id']))."\" title=\"CAMBIAR ESTADO A PAGADA\">\n";
          $html .= "            <img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
          $html .= "          </a\n";
          $html .= "        </td>\n";        
          $html .= "				<td align=\"center\" >\n";
          $html .= "				  <a href=\"".$action['acciones'].URLRequest(array("opcion"=>"anular","cxp_orden_pago_id"=>$detalle['cxp_orden_pago_id']))."\" title=\"ANULAR PRE-ORDEN\">\n";
          $html .= "            <img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
          $html .= "          </a\n";
          $html .= "        </td>\n";
          $html .= "			</tr>\n";
        }
        $html .= "	</table>\n";
        $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
    		$html .= "<br>\n";
      }
      else
      {
        if(!empty($request))
        {
          $html .= "<center>";
          $html .= "  <label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
          $html .= "</center>";
        }
      }
			
			$html .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\" id='lll'>\n";
			$html .= "				<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "				</form>\n";
			$html .= "			</td></tr>\n";
			$html .= "		</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
  }
?>