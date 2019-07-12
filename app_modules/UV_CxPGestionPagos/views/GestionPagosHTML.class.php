<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: GestionPagosHTML.class.php,v 1.2 2008/10/23 22:09:09 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: GestionPagosHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class GestionPagosHTML
  {
    /**
    * Constructosr de la clase
    */
    function GestionPagosHTML(){}
    /**
		* Funcion donde se crea la forma que imprime el listado de facturas
    *
    * @param array $action Arreglo con los links de la aplicacion 
    * @param array $request Arreglo con los datos del request
    * @param array $tiposdocumentos Arreglo con los datos de los tipos de documentos
    * @param array $facturas Arreglo con los datos de las facturas
    * @param integer $conteo Referencia a la cantidad de datos devueltos
    * @param integer $pagina Referencia a la pagina del paginador
    *
		* @return string 
		*/
		function FormaListadoFacturas($action,$request,$tiposdocumentos,$facturas,$conteo,$pagina)
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
			$html .= "		frm.submit();\n";
			$html .= "	}\n";
			$html .= "</script>\n";			
      $html .= ThemeAbrirTabla("CUENTAS X PAGAR - GESTION DE PAGOS");
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
   			$html .= "<form name=\"crear_og\" action=\"".$action['crear_op']."\" method=\"post\">\n";
        $html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
        $html .= "			<tr class=\"formulacion_table_list\" height=\"21\">\n";
        $html .= "				<td width=\"6%\">Nª RADIC.</td>\n";
        $html .= "				<td width=\"8%\">FACTURA</td>\n";
        $html .= "				<td width=\"8%\">F. FACTURA</td>\n";
        $html .= "				<td width=\"8%\">F. RADIC.</td>\n";
        $html .= "				<td width=\"%\" >CLIENTE</td>\n";
        $html .= "				<td width=\"9%\">IVA</td>\n";
        $html .= "				<td width=\"9%\">TOTAL</td>\n";
        $html .= "				<td width=\"15%\">TIPO CUENTA</td>\n";
        $html .= "				<td width=\"3%\">\n";
        $html .= "          <input type=\"checkbox\" name=\"todos\" onclick=\"SeleccionarCheckBox(document.crear_og,this.checked)\">\n";
        $html .= "        </td>\n";
        $html .= "			</tr>";
        
        $estilo='modulo_list_oscuro'; 
        $background = "#CCCCCC";
        
        foreach($facturas as $key => $detalle )
        {
          ($estilo == 'modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; ;  
          ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
                  
          $html .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
          $html .= "				<td >".$detalle['cxp_radicacion_id']."</td>\n";
          $html .= "				<td >".$detalle['prefijo_factura']." ".$detalle['numero_factura']."</td>\n";
          $html .= "				<td align=\"center\" >".$detalle['fecha_documento']."</td>\n";
          $html .= "				<td align=\"center\" >".$detalle['fecha_radicacion']."</td>\n";
          $html .= "				<td >".$detalle['tipo_id_tercero']." ".$detalle['tercero_id']." <b>".$detalle['nombre_tercero']."</b></td>\n";
          $html .= "				<td align=\"right\"  >".formatoValor($detalle['valor_iva'])."</td>\n";
          $html .= "				<td align=\"right\"  >".formatoValor($detalle['valor_total'])."</td>\n";
          $html .= "				<td align=\"justify\">".$detalle['tipo_cxp_descripcion']."</td>\n";
          $html .= "				<td align=\"center\" >\n";
          $html .= "          <input type=\"checkbox\" name=\"factura[".$detalle['prefijo']."][".$detalle['numero']."]\">\n";
          $html .= "        </td>\n";
          $html .= "			</tr>\n";
        }
        $html .= "	</table><br>\n";
        $html .= "  <center>\n";
				$html .= "    <div id=\"error\" class=\"label_error\"></div>\n";
				$html .= "  </center>\n";
        $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" >\n";
  			$html .= "	  <tr>\n";
  			$html .= "		  <td align=\"right\"><br>\n";
  			$html .= "				<input class=\"input-submit\" type=\"button\" name=\"retirar\" value=\"Generar Orden de Pago\" onclick=\"EvaluarDatos(document.crear_og)\">\n";
  			$html .= "		  </td>\n";
  			$html .= "	  </tr>\n";
  			$html .= "  </table>\n";
        $html .= "</form>\n"; 
        $html .= $this->CrearVentana("AceptarValor()");
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
    /**
    * Funcion donde se crea la forma que presenta el formato de revision
    *
    * @param array $action Arreglo con los links de la aplicacion 
    * @param array $detalle Arreglo con los datos de la preorden de pago
    * @param array $tercero Arreglo con los datos del tercero asociado
    * @param array $facturas Arreglo con los datos de las facturas
    * @param array $cuentas Arreglo con los datos de las cuentas
    * @param array $estamentos Arreglo con los datos de los estamentos asociados
    * @param string $empresa Referencia a la empresa
    * @param integer $cxp_orden_pago_id Referencia al identificador de la preorden
    *
    * @return string
    */
    function FormaFormatoRevision($action,$detalle,$tercero,$facturas,$cuentas,$estamentos,$empresa,$cxp_orden_pago_id)
    {
      $total = $total_iva = 0;
      
      $rpt = new GetReports();
      $mst = $rpt->GetJavaReport('app','UV_CxPGestionPagos','FormtoRevision',array("cxp_orden_pago_id"=>$cxp_orden_pago_id,"empresa"=>$empresa),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      $fnc = $rpt->GetJavaFunction();
      
      $html .= ThemeAbrirTabla("FORMATO REVISION");
      $html .= "<table class=\"modulo_table_list\" width=\"80%\" align=\"center\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td align=\"left\" width=\"50%\" colspan=\"2\">NOMBRE DEL BENEFICIARIO</td>\n";
      $html .= "    <td width=\"25%\">DOCUMENTO DE IDENTIDAD</td>\n";
      $html .= "    <td width=\"25%\">ORDEN DE GASTO Nº</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"2\">".$tercero['nombre_tercero']."</td>\n";
      $html .= "    <td>".$tercero['tipo_id_tercero']." ".$tercero['tercero_id']."</td>\n";
      $html .= "    <td>".$detalle['cxp_orden_pago_id']."</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td align=\"left\" colspan=\"2\">DIRECCION</td>\n";
      $html .= "    <td align=\"left\" colspan=\"2\">TELÉFONO</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"2\">".$tercero['direccion']."</td>\n";
      $html .= "    <td colspan=\"2\">".$tercero['telefono']."</td>\n";
      $html .= "  </tr>\n";    
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td align=\"left\" colspan=\"2\">PERIODO DEL SERVICIO</td>\n";
      $html .= "    <td align=\"left\" colspan=\"2\">ESPECIALIDAD</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"2\">DE ".$detalle['fecha_inicial']." A ".$detalle['fecha_final']."</td>\n";
      $html .= "    <td colspan=\"2\">".$detalle['descripcion_especialidad']."</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td colspan=\"4\">FACTURAS Nos.</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"4\" align=\"justify\">\n";
      foreach($facturas as $key => $dtl)
      {
        $html .= (($key == 0)? $dtl['prefijo_factura']." ".$dtl['numero_factura']: " ,".$dtl['prefijo_factura']." ".$dtl['numero_factura']);
        $total += $dtl['valor_total'];
        $total_iva += $dtl['valor_iva'];
      }
      
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td width=\"25%\">FECHA RECEPCION</td>\n";
      $html .= "    <td width=\"25%\">FECHA DE ELABORACION</td>\n";
      $html .= "    <td width=\"25%\">NA</td>\n";
      $html .= "    <td width=\"25%\">RD</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td>".$detalle['fecha_radicacion']."</td>\n";
      $html .= "    <td>".$detalle['fecha_elab_preorden']."</td>\n";
      $html .= "    <td>&nbsp;</td>\n";
      $html .= "    <td>".$detalle['num_orden_gasto']."</td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
            
      if(!empty($cuentas))
      {
        $html .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td width=\"25%\">CUENTA</td>\n";
        $html .= "    <td width=\"12%\">VALOR</td>\n";
        $html .= "    <td width=\"13%\">PORCENTAJE</td>\n";
        $html .= "    <td width=\"%\"></td>\n";
        $html .= "  </tr>\n";
      
        foreach($cuentas as $key => $detl)
        {
          $html .= "  <tr>\n";
          $html .= "    <td class=\"formulacion_table_list\">".$key."</td>\n";
          $html .= "    <td align=\"right\">".formatoValor($detl['valor_total'])."</td>\n";
          $html .= "    <td >".number_format(($detl['valor_total'] * 100/$total),3,',','.')." %</td>\n";
          $html .= "    <td >&nbsp</td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "</table>\n";
      }      
      
      if(!empty($estamentos))
      {
        $html .= "<br>\n";
        $html .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td width=\"25%\">ESTAMENTO</td>\n";
        $html .= "    <td width=\"15%\">No ORDENES</td>\n";
        $html .= "    <td width=\"15%\">VALOR INICIAL</td>\n";
        $html .= "    <td width=\"15%\">NOTA CREDITO</td>\n";
        $html .= "    <td width=\"15%\">DESCUENTO</td>\n";
        $html .= "    <td width=\"15%\">VALOR TOTAL</td>\n";
        $html .= "  </tr>\n";
        
        $tot = $cant = 0;
        foreach($estamentos as $key => $detal)
        {
          $html .= "  <tr>\n";
          $html .= "    <td class=\"formulacion_table_list\">".$key."</td>\n";
          $html .= "    <td align=\"right\">".$detal['cantidad']."</td>\n";
          $html .= "    <td align=\"right\">$".formatoValor($detal['valor'])."</td>\n";
          $html .= "    <td align=\"right\"></td>\n";
          $html .= "    <td align=\"right\"></td>\n";
          $html .= "    <td align=\"right\">$".formatoValor($detal['valor'])."</td>\n";
          $html .= "  </tr>\n";
          
          $cant += $detal['cantidad'];
          $tot += $detal['valor'];
        }
        $html .= "  <tr>\n";
        $html .= "    <td class=\"formulacion_table_list\">GRAN TOTAL</td>\n";
        $html .= "    <td align=\"right\">".$cant."</td>\n";
        $html .= "    <td align=\"right\">$".formatoValor($tot)."</td>\n";
        $html .= "    <td align=\"right\"></td>\n";
        $html .= "    <td align=\"right\"></td>\n";
        $html .= "    <td align=\"right\">$".formatoValor($tot)."</td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
      }
      
      $html .= "<center><br>\n";
      $html .= "  ".$mst;
      $html .= "	<a href=\"javascript:".$fnc."\" class=\"label_error\">\n";
      $html .= "    <img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">IMPRIMIR PRE-ORDEN\n";
      $html .= "  </a\n";
      $html .= "</center><br>\n";  
      
			$html .= "<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "  <table width=\"90%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" id='lll'>\n";
			$html .= "	      <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "	    </td>\n";
      $html .= "    </tr>\n";
			$html .= "  </table>\n";
			$html .= "</form>\n";
			$html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion donde se crea la forma que muestra la creacion de la preorden de pago
    *
    * @param array $action Arreglo con los links de la aplicacion 
    * @param integer $orden_pago Referencia al numero de la preorden de pago
    *
    * @return string
    */
    function FormaImprimir($action,$orden_pago)
    {
    	$html  = ThemeAbrirTabla('MENSAJE');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"justify\">\n";
      $html .= "            LA PRE-ORDEN DE PAGO SE GENERO CORRECTAMENTE.<br>\n";
      $html .= "            NUMERO DE LA PRE-ORDEN :".$orden_pago."\n";
      $html .= "		      </td>\n";
      $html .= "		    </tr>\n";
      $html .= "		    <tr class=\"normal_10AN\">\n";
      $html .= "		      <td align=\"center\">\n";
      
      $csv = Autocarga::factory("ReportesCsv");
      $html .= $csv->GetJavacriptReporte('app','UV_CxPGestionPagos','Facturas',array("orden_pago"=>$orden_pago),'comas',array("interface"=>1,"cabecera"=>1,"nombre"=>"OrdenPago_".$orden_pago,"extension"=>"csv"));
      $fncn  = $csv->GetJavaFunction();
      $html .= "		      	<center>\n";
      $html .= "		      	  <a href=\"javascript:".$fncn."\" class=\"label_error\">\n";
      $html .= "		        	  <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>DESCARGAR PRE-ORDEN DE PAGO\n";
      $html .= "		      	  </a>\n";
      $html .= "		      	</center>\n";
      $html .= "          </td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
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
        $html .= "				<td width=\"10%\">ESTADO</td>\n";
        $html .= "				<td width=\"%\"  >OBSERVACION ESTADO</td>\n";
        $html .= "				<td width=\"10%\">ORDEN GASTO</td>\n";
        $html .= "				<td width=\"10%\" colspan=\"4\">\n";
        $html .= "          OPCIONES\n";
        $html .= "        </td>\n";
        $html .= "			</tr>";
        
        $estilo='modulo_list_oscuro'; 
        $background = "#CCCCCC";
        
        $rpt = new GetReports();
        
        foreach($ordpg as $key => $detalle )
        {
          ($estilo == 'modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; ;  
          ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
                  
          $mst = $rpt->GetJavaReport('app','UV_CxPGestionPagos','FormtoRevision',array("cxp_orden_pago_id"=>$detalle['cxp_orden_pago_id'],"empresa"=>$detalle['empresa_id']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $fnc = $rpt->GetJavaFunction();
          
          $html .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
          $html .= "				<td >".$detalle['cxp_orden_pago_id']."</td>\n";
          $html .= "				<td align=\"center\">".$detalle['fecha_registro']."</td>\n";
          $html .= "				<td >".$detalle['nombre']."</td>\n";
          $html .= "				<td>\n";
          if($detalle['sw_estado'] == '1')
            $html .= "CREADA"; 
          else if($detalle['sw_estado'] == '2')
            $html .= "RADICADA";
            else if($detalle['sw_estado'] == '3')
            $html .= "PAGADA";
            
          $html .= "        </td>\n";
          $html .= "				<td ><label id=\"observacion_".$detalle['cxp_orden_pago_id']."\">".$detalle['observacion_estado']."</label></td>\n";
          $html .= "				<td ><label id=\"numero_".$detalle['cxp_orden_pago_id']."\">".$detalle['num_orden_gasto']."</label></td>\n";
          $html .= "				<td align=\"center\" >\n";
          if($detalle['sw_estdo'] != "3")
          {
            $html .= "				  <a href=\"javascript:IngresarNumeroRadicacion('".$detalle['cxp_orden_pago_id']."','".$detalle['num_orden_gasto']."')\" title=\"INGRESAR NUMERO DE ORDEN DE GASTO\">\n";
            $html .= "            <img src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\">\n";
            $html .= "          </a\n";
          }
          $html .= "        </td>\n";        
          $html .= "				<td align=\"center\" >\n";
          if($detalle['sw_estdo'] != "3")
          {
            $html .= "				  <a href=\"javascript:IngresarEstadoObservacion('".$detalle['cxp_orden_pago_id']."')\" title=\"INGRESAR OBSERVACION AL ESTADO DE LA PRE-ORDEN\">\n";
            $html .= "            <img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
            $html .= "          </a\n";
          }
          $html .= "        </td>\n";        
          $html .= "				<td align=\"center\" >\n";
          $html .= "          ".$mst;
          $html .= "				  <a href=\"javascript:".$fnc."\" title=\"IMPRIMIR\">\n";
          $html .= "            <img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
          $html .= "          </a\n";
          $html .= "        </td>\n";          
          $html .= "				<td align=\"center\" >\n";
          $html .= "				  <a href=\"javascript:DetallePreOrden('".$detalle['cxp_orden_pago_id']."')\" title=\"VER DETALLE DE LA PRE-ORDEN\">\n";
          $html .= "            <img src=\"".GetThemePath()."/images/mvto_con.png\" border=\"0\">\n";
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
      $html .= $this->CrearVentana();
			return $html;
		}
    /**
		* Funcion donde se crea una forma con una ventana con capas para mostrar informacion
    * en pantalle
    *
    * @param int $tmn Tamaño que tendra la ventana
    *
    * @return string
		*/
		function CrearVentana($tmn = 350)
		{
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 5;\n";
			$html .= "	function OcultarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"block\";\n";
			$html .= "		  Iniciar();\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";	
      $html .= "	function MostrarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xShow(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function OcultarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xHide(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function Iniciar()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Contenedor';\n";
			$html .= "		titulo = 'titulo';\n";
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
      $html .= "		ele.innerHTML = 'MENSAJE';\n";
			$html .= "	  xResizeTo(ele,".($tmn - 20).", 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrar');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,".($tmn - 20).", 0);\n";
			$html .= "	}\n";
			$html .= "	function myOnDragStart(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	  window.status = '';\n";
			$html .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "	  else xZIndex(ele, hiZ++);\n";
			$html .= "	  ele.myTotalMX = 0;\n";
			$html .= "	  ele.myTotalMY = 0;\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag(ele, mdx, mdy)\n";
			$html .= "	{\n";
			$html .= "	  if (ele.id == titulo) {\n";
			$html .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "	  }\n";
			$html .= "	  else {\n";
			$html .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "	  }  \n";
			$html .= "	  ele.myTotalMX += mdx;\n";
			$html .= "	  ele.myTotalMY += mdy;\n";
			$html .= "	}\n";
			$html .= "	function myOnDragEnd(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:5\">\n";
			$html .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center;\">CONFIRMACIÓN</div>\n";
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content'>\n";
			$html .= "		<form name=\"oculta\" id=\"oculta\" method=\"post\">\n";
			$html .= "		  <div id=\"ventana\" ></div>\n";
      $html .= "		  <div id=\"erroro\" class=\"label_error\" style=\"text-align:center\"></div>\n";
			$html .= "		</form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";

			return $html;
		}
    /**
    * Funcion donde se crea un mensaje, que sera mostrado en una capa
    *
    * @param string $mensaje Texto que se mostrara en pantalla
    *
    * @param string
    */
    function FormaMensaje($mensaje)
		{
			$html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$mensaje."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<input  type=\"button\" class=\"input-submit\" name=\"cancelar\" value=\"Aceptar\" onclick=\"OcultarSpan()\" >\n";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			return $html;
		}
    /**
    * Funcion que crea una forma para hacer el ingreso del numero de radiacion externo
    *
    * @param integer $pre_orden Identificador de la preorden de pago
    * @param integer $offset Referencia al conteo del paginador
    *
    * @return string
    */
    function FormaNumeroRadicacionExterno($pre_orden,$offset)
    {
      $html  = "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend class=\"label\">INGRESO NUMERO ORDEN DE GASTO</legend>\n";
			$html .= "	<table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";
 			$html .= "		  <td colspan=\"2\" align=\"justify\">NUMERO DE ORDEN DE GASTO CORRESPONDIENTE A UN SISTEMA EXTERNO PARA LA PRE-ORDEN Nª ".$pre_orden."</td>\n";
      $html .= "	  </tr>\n";
      $html .= "	  <tr>\n";
      $html .= "	    <td class=\"formulacion_table_list\">Nº ORDEN DE GASTO</td>\n";
      $html .= "			<td >\n";
			$html .= "		    <input type=\"text\" class=\"input-text\" name=\"numero_orden\" style=\"width:70%\" value=\"".$orden_gasto."\">\n";
			$html .= "			</td>\n";
      $html .= "	  </tr>\n";
      $html .= "	</table>\n";
      $html .= "</fieldset>\n";
      $html .= "<table align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "	  <td align=\"center\" >\n";
			$html .= "		  <input  type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\" onclick=\"xajax_RegistrarNumeroRadicacion(xajax.getFormValues('oculta'),'".$pre_orden."','".$offset."',xajax.getFormValues('buscador'))\">\n";
			$html .= "		</td>\n";
			$html .= "		<td align=\"center\" >\n";
			$html .= "			<input  type=\"button\" class=\"input-submit\" name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan()\" >\n";
			$html .= "		</td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
      
      return $html;
    }    
    /**
    * Funcion que crea una forma para hacer el ingreso de la observacion al estado de la
    * preorden
    *
    * @param integer $pre_orden Identificador de la preorden de pago
    * @param integer $offset Referencia al conteo del paginador
    *
    * @return string
    */
    function FormaObservacionEstado($pre_orden,$offset)
    {
      $html  = "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend class=\"label\">INGRESO OBSERVACION A LA PRE-ORDEN Nº ".$pre_orden."</legend>\n";
			$html .= "	<table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";
 			$html .= "		  <td >OBSERVACION AL ESTADO DE LA PRE-ORDEN</td>\n";
      $html .= "	  </tr>\n";
      $html .= "	  <tr>\n";
      $html .= "			<td >\n";
			$html .= "		    <textarea name=\"observacion\" rows=\"3\" style=\"width:100%\"></textarea>\n";
			$html .= "			</td>\n";
      $html .= "	  </tr>\n";
      $html .= "	</table>\n";
      $html .= "</fieldset>\n";
      $html .= "<table align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "	  <td align=\"center\" >\n";
			$html .= "		  <input  type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\" onclick=\"xajax_RegistrarEstadoObservacion(xajax.getFormValues('oculta'),'".$pre_orden."','".$offset."',xajax.getFormValues('buscador'))\">\n";
			$html .= "		</td>\n";
			$html .= "		<td align=\"center\" >\n";
			$html .= "			<input  type=\"button\" class=\"input-submit\" name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan()\" >\n";
			$html .= "		</td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
      
      return $html;
    }
  }
?>