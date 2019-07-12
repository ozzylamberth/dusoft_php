<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: CajaHTML.class.php,v 1.1 2010/06/03 20:43:44 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sEA.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : CajaHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sEA.com)
  * @author Hugo F  Manrique
  */	
  class CajaHTML
	{
    /**
    * Constructor de la clase
    */
		function CajaHTML(){}
		/**
		* Funcion que muesta la interface, para la realizacion de los pagos
    *
		* @params array $action Arerglo con los links de los botones y/o imagenes
		* @params array $request Arreglo de datos con la informacion de request
		* @params array $productos Arreglo de datos con la informacion de los productos seleccionados
		* @params array $pagos Arreglo de datos con la informacion de los pagos realizados
		* @params array $empresa Arreglo de datos con la informacion de la empresa
		*
		* @return String
		*/
	 function FormaPagos($action,$request,$productos,$pagos,$empresa)
	 {
	   $est = "style=\"text-align:left;text-indent:4pt\"";
       $ctl = AutoCarga::factory("ClaseUtil");
       $rpt = AutoCarga::factory("ReporteFacturaSQL","classes", "app", "VentaFarmacia");
	  
      $html .= $ctl->AcceptNum();
      $html .= $ctl->AcceptDate();
      $html .= $ctl->IsNumeric();
      $html .= "<script>\n";
      $html .= "  function Realizarpago(opcion)\n";
      $html .= "  {\n";
      $html .= "    xajax_RealizarPago(xajax.getFormValues('realizar_pagos'),opcion)\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeAbrirTabla("PAGOS");
      $html .= "<div id=\"capa_datos\" style=\"text-align:center;display:none;background-color:gray;opacity:0.65;width:100%;height:100%;position:absolute;top:0px;left:0px;\">\n";
      $html .= "</div>\n";
      $html .= "<center>\n";
      $html .= "  <div id=\"contenido_total\" style=\"display:none;width:90%;text-align:center;background-color:#DFDFDF;position:fixed\">\n";
      $html .= "    <center>\n";
      $html .= "      <div class=\"d2Container\" id=\"contenido_pagos\" style=\"width:98%;\"></div>\n";
      $html .= "    </center>\n";
      $html .= "  </div>\n";
      $html .= "</center>\n";
      $html .= "<table width=\"100%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"2\">\n";
      $html .= "      <fieldset class=\"fieldset\">\n";
      $html .= "        <legend class=\"normal_10AN\">ITEMS SELECCIONADOS</legend>\n";
      $html .= "        <table width=\"98%\" class=\"modulo_table_list\" align=\"center\">";
      $html .= "	        <tr class=\"modulo_table_list_title\" >\n";
      $html .= "            <td width=\"20%\">LABORATORIO</td>\n";
      $html .= "            <td width=\"20%\">MOLECULA</td>\n";
      $html .= "            <td width=\"10%\">CODIGO</td>\n";
      $html .= "            <td width=\"25%\">NOMBRE PRODUCTOOO</td>\n";
      $html .= "            <td width=\"10%\">VENCIMIENTO</td>\n";
      $html .= "            <td width=\"10%\">LOTE</td>\n";
      $html .= "            <td width=\"10%\">CANT.</td>\n";
      $html .= "            <td width=\"15%\">VR.UNI</td>\n";
      $html .= "            <td width=\"15%\">VR.TOTAL</td>\n";
      $html .= "          </tr>\n";
	  
	$total = 0;	
	$totIva = 0; //added acumulador valores iva
	$totalFin = 0; // total + iva si aplica
	$vaLiva = 0; // valor iva
	$saldo = 0;
	foreach($productos as $key => $dtl)
	{
        $est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
		$iva = $rpt->Valida_IvaProd($dtl['codigo_producto']);
		
		if(FormatoValor($iva['porc_iva']) <> 0)
		{
		 //valor del iva si toca adicionarlo al producto
		 $vaLiva = round((($dtl['cantidad']*$dtl['total_costo']) * ($iva['porc_iva']/100)),0);
		}		
		
        $html .= "          <tr class=\"".$est."\" height=\"16\">\n";
        $html .= "            <td align=\"center\">".$dtl['laboratorio']."</td>\n";
        $html .= "            <td align=\"center\">".$dtl['molecula']."</td>\n";
        $html .= "            <td class=\"normal_10AN\">".$dtl['codigo_producto']."</td>\n";
        $html .= "            <td class=\"label\" align=\"left\">\n";
        $html .= "              ".$ctl->NombreProducto($dtl,$dtl['empresa_id'])."\n";
        $html .= "            </td>\n";
        $html .= "            <td align=\"center\" class=\"normal_10AN\">".$dtl['fecha_vencimiento']."</td>\n";
        $html .= "            <td align=\"left\" class=\"normal_10AN\">".$dtl['lote']."</td>\n";
        $html .= "            <td class=\"label\" align=\"right\">".$dtl['cantidad']."</td>\n";
        $html .= "            <td class=\"label\" align=\"right\">$".$dtl['total_costo']."</td>\n";
        $html .= "            <td class=\"label\" align=\"right\">$".round(($dtl['cantidad']*$dtl['total_costo']),0)."</td>\n";
        $html .= "          </tr>\n";
        $total += $dtl['cantidad']*$dtl['total_costo'];
	    $totIva += $vaLiva;
    }
	    $totalFin = $total + $totIva;
		
		$html .= "          <tr class=\"formulacion_table_list\">\n";
		//$html .= "	          <td colspan=\"8\" align=\"right\">TOTAL A PAGAR</td>\n";
		$html .= "	          <td colspan=\"8\" align=\"right\">SUBTOTAL</td>\n";
        $html .= "            <td class=\"modulo_list_claro\" align=\"right\">$".$total."</td>\n";
		$html .= "	        </tr>\n";		
		$html .= "	        <tr class=\"formulacion_table_list\">\n";		
		$html .= "	          <td colspan=\"8\" align=\"right\">TOTAL IVA</td>\n";
        $html .= "            <td class=\"modulo_list_claro\" align=\"right\">$".$totIva."</td>\n";		
		$html .= "	        </tr>\n";
		$html .= "	        <tr class=\"formulacion_table_list\">\n";		
		$html .= "	          <td colspan=\"8\" align=\"right\">TOTAL A PAGAR</td>\n";
        $html .= "            <td class=\"modulo_list_claro\" align=\"right\">$".$totalFin."</td>\n";		
		$html .= "	        </tr>\n";
		
		$html .= "	      </table>\n";
		$html .= "	    </fieldset>\n";
		$html .= "	  </td>\n";
		$html .= "  </tr>\n";
		$html .= "</table><br>\n";
      $valor_real = FormatoValor($total,null,false);
	  
      $html .= "<center>\n";
			$html .= "	<form name=\"realizar_pagos\" id=\"realizar_pagos\" action=\"javascript:IngresarPagos('realizar_pagos')\" method=\"post\">\n";
			$html .= "    <input type=\"hidden\" name=\"empresa_id\" value=\"".$empresa['empresa_id']."\">\n";
			$html .= "    <input type=\"hidden\" name=\"centro_utilidad\" value=\"".$empresa['centro_utilidad']."\">\n";
			$html .= "    <input type=\"hidden\" name=\"bodega\" value=\"".$empresa['bodega']."\">\n";
			$html .= "    <input type=\"hidden\" name=\"bodegas_doc_id\" value=\"".$empresa['bodegas_doc_id']."\">\n";
			$html .= "    <input type=\"hidden\" name=\"usuario_id\" value=\"".$empresa['usuario_id']."\">\n";
			$html .= "    <input type=\"hidden\" name=\"documento\" value=\"".$request['documento']."\">\n";
			$html .= "    <input type=\"hidden\" name=\"tercero_id\" value=\"".$request['tercero_id']."\">\n";
			$html .= "    <input type=\"hidden\" name=\"tipo_id_tercero\" value=\"".$request['tipo_id_tercero']."\">\n";
      $html .= "		<fieldset class=\"fieldset\" style=\"width:50%;padding:8pt\"><legend class=\"normal_10AN\">REGISTRO DE PAGOS</legend>\n";
			$html .= "			<div id=\"error_pagos\" class=\"label_error\"></div>\n";
			$html .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "				<tr height=\"19\" class=\"modulo_table_list_title\">\n";
			$html .= "					<td colspan=\"3\">VALOR TOTAL A PAGAR: $".$totalFin."</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr class=\"modulo_table_list_title\">\n";
			$html .= "					<td $est width=\"50%\" >PAGO EN EFECTIVO</td>\n";
			$html .= "					<td style=\"text-align:right\" class=\"modulo_list_claro\">\n";
			$html .= "						$<label class=\"normal_10AN\" id=\"label_efectivo\">".(($pagos['total_efectivo'])? $pagos['total_efectivo']:"0")."</label>\n";
			$html .= "						<input type=\"hidden\"  id=\"h_efectivo\" name=\"h_efectivo\" value=\"".(($pagos['total_efectivo'])? $pagos['total_efectivo']:"0" )."\">\n";
			$html .= "					</td>\n";
			$html .= "					<td width=\"1%\" class=\"modulo_list_claro\">\n";
			$html .= "						<a title=\"REALIZAR PAGOS EN EFECTIVO\" href=\"javascript:Realizarpago('E')\" class=\"label_error\">\n";
			$html .= "							<img src=\"".GetThemePath()."/images/plata.png\" border=\"0\">\n";
			$html .= "						</a>\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr class=\"modulo_table_list_title\">\n";
			$html .= "					<td $est >PAGO CON CHEQUE</td>\n";
			$html .= "					<td style=\"text-align:right\" class=\"modulo_list_claro\">\n";
			$html .= "						$<label class=\"normal_10AN\" id=\"label_cheque\">".(($pagos['total_cheque'])? $pagos['total_cheque']:"0")."</label>\n";
		  $html .= "						<input type=\"hidden\"  id=\"h_cheque\" name=\"h_cheque\" value=\"".(($pagos['total_cheque'])? $pagos['total_cheque']:"0" )."\">\n";
      $html .= "					</td>\n";
			$html .= "					<td class=\"modulo_list_claro\">\n";
			$html .= "						<a title=\"REALIZAR PAGOS CON CHEQUE\" href=\"javascript:Realizarpago('C')\" class=\"label_error\">\n";
			$html .= "							<img src=\"".GetThemePath()."/images/plata.png\" border=\"0\">\n";
			$html .= "						</a>\n";			
			$html .= "					</td>\n";			
			$html .= "				</tr>\n";
			$html .= "				<tr class=\"modulo_table_list_title\">\n";
			$html .= "					<td $est>PAGO CON TARJETA CREDITO</td>\n";
			$html .= "					<td style=\"text-align:right\" class=\"modulo_list_claro\">\n";
			$html .= "						$<label class=\"normal_10AN\" id=\"label_credito\">".(($pagos['total_credito'])? $pagos['total_credito']:"0")."</label>\n";
		  $html .= "						<input type=\"hidden\"  id=\"h_credito\" name=\"h_credito\" value=\"".(($pagos['total_credito'])? $pagos['total_credito']:"0" )."\">\n";
      $html .= "					</td>\n";
			$html .= "					<td class=\"modulo_list_claro\">\n";
			$html .= "						<a title=\"REALIZAR PAGOS CON TARJETA CREDITO\" href=\"javascript:Realizarpago('R')\" class=\"label_error\">\n";
			$html .= "							<img src=\"".GetThemePath()."/images/plata.png\" border=\"0\">\n";
			$html .= "						</a>\n";			
			$html .= "					</td>\n";			
			$html .= "				</tr>\n";
			$html .= "				<tr class=\"modulo_table_list_title\">\n";
			$html .= "					<td $est>PAGO EN TARJETA DEBITO</td>\n";
			$html .= "					<td style=\"text-align:right\" class=\"modulo_list_claro\">\n";
			$html .= "						$<label class=\"normal_10AN\" id=\"label_debito\">".(($pagos['total_debito'])? $pagos['total_debito']:"0")."</label>\n";
		  $html .= "						<input type=\"hidden\"  id=\"h_debito\" name=\"h_debito\" value=\"".(($pagos['total_debito'])? $pagos['total_debito']:"0" )."\">\n";
      $html .= "					</td>\n";
			$html .= "					<td class=\"modulo_list_claro\">\n";
			$html .= "						<a title=\"REALIZAR PAGOS CON TARJETA DEBITO\" href=\"javascript:Realizarpago('D')\" class=\"label_error\">\n";
			$html .= "							<img src=\"".GetThemePath()."/images/plata.png\" border=\"0\">\n";
			$html .= "						</a>\n";			
			$html .= "					</td>\n";			
			$html .= "				</tr>\n";
			$html .= "				<tr class=\"modulo_table_list_title\">\n";
			$html .= "					<td $est>PAGO CON BONOS</td>\n";
			$html .= "					<td style=\"text-align:right\" class=\"modulo_list_claro\">\n";
			$html .= "						$<label class=\"normal_10AN\" id=\"label_bono\">".(($pagos['total_bono'])? $pagos['total_bono']:"0")."</label>\n";
			$html .= "						<input type=\"hidden\"  id=\"h_bono\" name=\"h_bono\" value=\"".(($pagos['total_bono'])? $pagos['total_bono']:"0" )."\">\n";
      $html .= "					</td>\n";
			$html .= "					<td class=\"modulo_list_claro\">\n";
			$html .= "						<a title=\"REALIZAR PAGOS CON BONOS\" href=\"javascript:Realizarpago('B')\" class=\"label_error\">\n";
			$html .= "							<img src=\"".GetThemePath()."/images/plata.png\" border=\"0\">\n";
			$html .= "						</a>\n";			
			$html .= "					</td>\n";			
			$html .= "				</tr>\n";
      $total1 = 0;
      if(!empty($pagos))
          $total1 = $pagos['total_efectivo']+$pagos['total_cheque']+$pagos['total_credito']+$pagos['total_debito']+$pagos['total_bono'];
          $saldo = $totalFin - $total1;     
      //$saldo = $total-$total1;

			$html .= "				<tr height=\"19\" class=\"modulo_table_list_title\">\n";
			$html .= "					<td $est>TOTAL</td>\n";
			$html .= "					<td style=\"text-align:right\" class=\"modulo_list_claro\">\n";
			$html .= "						$<label class=\"normal_10AN\" id=\"total\">".$total1."</label>\n";
			$html .= "						<input type=\"hidden\"  id=\"valor_cancelar\" name=\"valor_cancelar\" value=\"".$valor_real."\">\n"; //valor antes de iva
			//campo oculto iva y total final
			$html .= "						<input type=\"hidden\"  id=\"valor_iva\" name=\"valor_iva\" value=\"".$totIva."\">\n";
			$html .= "						<input type=\"hidden\"  id=\"valor_final\" name=\"valor_final\" value=\"".$totalFin."\">\n"; //valor final de la compra + iva
			
			$html .= "						<input type=\"hidden\"  id=\"saldo\" name=\"saldo\" value=\"".$saldo."\">\n";
			$html .= "					</td>\n";
			$html .= "					<td class=\"modulo_list_claro\" ></td>\n";
			$html .= "				</tr>\n";
      
      $html .= "				<tr height=\"19\" class=\"formulacion_table_list\">\n";
			$html .= "					<td $est>CAMBIO</td>\n";
			$html .= "					<td style=\"text-align:right\" class=\"modulo_list_oscuro\">\n";
			$html .= "						$<label class=\"label_error\" id=\"cambio\">".(($saldo < 0)? $saldo*(-1) :"0" )."</label>\n";
			//$html .= "						$<label class=\"label_error\" id=\"cambio\">".$saldo."</label>\n";
			$html .= "					</td>\n";
			$html .= "					<td class=\"modulo_list_oscuro\" ></td>\n";
			$html .= "				</tr>\n";
      
			$html .= "			</table>\n";
			$html .= "		</fieldset>\n";
			$html .= "		<table align=\"center\" width=\"50%\">\n";
			$html .= "			<tr>\n";
			$html .= "				<td align=\"center\">\n";
			$html .= "						<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$html .= "				</td>\n";
			$html .= "				</form>\n";
			$html .= "				<form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<td align=\"center\">\n";
			$html .= "						<input type=\"submit\" class=\"input-submit\" name=\"cancela\" value=\"Cancelar\" >\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "		</table>\n";
			$html .= "	</form>\n";
      $html .= "</center>\n";

      $html .= "<script>\n";
			$html .= "	function Continuar()\n";
			$html .= "	{\n";
			$html .= "    document.realizar_pagos.action = \"".$action['pagar']."\";\n";
			$html .= "    document.realizar_pagos.submit();\n";
			$html .= "  }\n";
			$html .= "	function CerraPagos()\n";
			$html .= "  {\n";
			$html .= "    document.getElementById('capa_datos').style.display = 'none';\n";
			$html .= "    document.getElementById('contenido_total').style.display = 'none';\n";
			$html .= "  }\n";
 			$html .= "  function EvaluarDatos(forma,opcion)\n";
			$html .= "	{\n";
			$html .= "	  xajax_EvaluarDatos(xajax.getFormValues(forma),xajax.getFormValues('realizar_pagos'),opcion);\n";
			$html .= "  }\n"; 			
      $html .= "  function IngresarPagos(forma)\n";
			$html .= "	{\n";
			$html .= "	  xajax_IngresarPagos(xajax.getFormValues(forma),xajax.getFormValues('realizar_pagos'));\n";
			$html .= "  }\n";
			$html .= "</script>\n";
      $html .= ReturnOpenCalendarioScript("cheques","fecha_confirma","/");
      $html .= ReturnOpenCalendarioScript("cheques","fecha_cheque","/");
      $html .= ReturnOpenCalendarioScript("cheques","fecha_transaccion","/");
      $html .= ReturnOpenCalendarioScript('tarjeta','fecha_expiracion','/');
      $html .= ReturnOpenCalendarioScript('tarjeta','fecha_transaccion','/');
      $html .= ReturnOpenCalendarioScript('tarjeta','fecha_confirma','/');
      $html .= ThemeCerrarTabla();
			return $html;
		}
		/**
		* Funcion donde se crea la forma para ingresar el pago en efectivo
    *
		* @params array $form Arreglo de datos con la informacion de request
    *
	* @return String
	*/
	 function FormaPagoEfectivo($form)
	 {
      //$valor = $form['valor_cancelar'] - $form['h_cheque'] - $form['h_credito'] - $form['h_debito'] -$form['h_bono']; 
      $valor = $form['valor_final'] - $form['h_cheque'] - $form['h_credito'] - $form['h_debito'] -$form['h_bono']; 
      $html  = "<table width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td class=\"titulo_tabla\" >REGISTRO DE PAGOS EN EFECTIVO</td>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"modulo_list_claro\">\n";
	  $html .= "<div class=\"normal_10AN\" style=\"text-align:center\">\n";
	  //$html .= "  VALOR A CANCELAR: $".FormatoValor($form['valor_cancelar'])."\n";
	  $html .= "  VALOR A CANCELAR: $".$form['valor_final']."\n";
	  $html .= "</div>\n";
	  $html .= "<form name=\"efectivo\" id=\"efectivo\" action=\"javascript:EvaluarDatos('efectivo','E')\" method=\"post\">\n";
	  //$html .= "<input type=\"hidden\"  id=\"valor_cancelar\" name=\"valor_cancelar\" value=\"".$valor."\">\n";
	  $html .= "<input type=\"hidden\"  id=\"valor_cancelar\" name=\"valor_cancelar\" value=\"".$form['valor_final']."\">\n";
      $html .= "<table width=\"100%\" align=\"center\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<fieldset style=\"width:80%\" class=\"fieldset\">\n";
			$html .= "				<legend>PAGO EN EFECTIVO</legend>\n";
			$html .= "				<div id=\"error_efectivo\" class=\"label_error\"></div>\n";
			$html .= "				<table width=\"100%\" align=\"center\">\n";
			$html .= "					<tr class=\"normal_10AN\">\n";
			$html .= "						<td width=\"25%\">VALOR A PAGAR:</td>\n";
			$html .= "						<td>\n";
			$html .= "							$<input type=\"text\" class=\"input-text\" name=\"valor\" style=\"width:60%\" value=\"".$form['h_efectivo']."\" onkeypress=\"return acceptNum(event)\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "				<table align=\"center\" width=\"100%\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$html .= "						</td>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"CerraPagos()\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
      $html .= "    </td>\n"; 
      $html .= "  </tr>\n"; 
      $html .= "</table>\n"; 
			return $html;
		}
		/**
		* Funcion donde se crea la forma para ingresar el pago con cheque
    *
		* @params array $form Arreglo de datos con la informacion de request
		* @params array $confirman Arreglo de datos con la informacion de las entidades que confirman
		* @params array $bancos Arreglo de datos con la informacion de los bancos
    *
		* @return String
		*/
		function FormaPagoCheque($form,$confirman,$bancos)
		{
			$est  = "style=\"text-align:left;padding:4pt\"";
			
      $html  = "<table width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td class=\"titulo_tabla\" >REGISTRO DE PAGOS CON CHEQUE</td>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"modulo_list_claro\">\n";
			$html .= "<div class=\"normal_10AN\" style=\"text-align:center\">\n";
			//$html .= "  VALOR A CANCELAR: $".FormatoValor($form['valor_cancelar'])."\n";
			$html .= "  VALOR A CANCELAR: $".$form['valor_final']."\n";
			$html .= "</div>\n";
      $html .= "<form name=\"cheques\" id=\"cheques\" action=\"javascript:EvaluarDatos('cheques','C')\" method=\"post\">\n";
			$html .= "<table width=\"100%\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<div id=\"error_cheque\" style=\"text-align:center\" class=\"label_error\"></div>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend>PAGO CON CHEQUE</legend>\n";
			$html .= "				<table width=\"100%\" class=\"modulo_table_list\" >\n";
			$html .= "					<tr class=\"modulo_table_list_title\">\n";
			$html .= "						<td colspan=\"4\">DATOS AUTORIZACION CHEQUE</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html	.= "						<td width=\"19%\">ENTIDAD CONFIRMA:</td>\n";
			$html .= "						<td width=\"30%\" class=\"modulo_list_claro\">\n";
			$html .= "							<select name=\"entidad\" class=\"select\">\n";
			$html .= "								<option value='-1'>---SELECCIONAR---</option>\n";
			
			foreach($confirman as $key => $dtl)
				$html .="								<option value=\"".$dtl['entidad_confirma']."\" ".(($form['entidad_confirma'] == $dtl['entidad_confirma'])? "selected":"" ).">".$dtl['descripcion']."</option>\n";
			
			$html .= "							</select>\n";

			$html .= "						</td>\n";
			$html .= "   					<td width=\"20%\">F. CONFIRMA</td>\n";
			$html .= "						<td class=\"modulo_list_claro\">\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"fecha_confirma\" style=\"width:35%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".(($form['fecha_comfirmacion'])? $form['fecha_comfirmacion']:date("d/m/Y"))."\">".ReturnOpenCalendarioHTML('cheques','fecha_confirma','/')."\n";

			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td>FUNCIONARIO CONFIRMA</td>\n";
			$html .= "						<td class=\"modulo_list_claro\">\n";
			$html .= "							<input name=\"funcionario\" class=\"input-text\" type=\"text\" style=\"width:90%\" maxlength=\"40\" value=\"".$form['funcionario_confirma']."\">\n";
			$html .= "						</td>\n";
			$html .= "						<td>Nº CONFIRMACIÓN</td>\n";
			$html .= "						<td class=\"modulo_list_claro\">\n";
			$html .= "							<input name=\"numero\" class=\"input-text\" type=\"text\" style=\"width:90%\" maxlength=\"15\" value=\"".$form['numero_confirmacion']."\">\n";
      $html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table><br>\n";
			$html .= "				<table width=\"100%\" class=\"modulo_table_list\" >\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td >Nº CHEQUE:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" colspan=\"3\">\n";
			$html .= "							<input type=\"text\" name=\"numero_cheque\" class=\"input-text\" size=\"20\" maxlength=\"10\" value=\"".$form['cheque']."\">\n";

			$html .= "						</td>\n";
			$html .= "					</tr>\n";			
			$html .= "					<tr $est class=\"modulo_table_list_title\" >\n";
			$html .= "						<td >BANCO</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" colspan=\"3\">\n";
			$html .= "							<select name=\"banco\" class=\"select\">\n";
			$html .= "								<option value='-1'>--------SELECCIONAR--------</option>\n";

			foreach($bancos as $key => $dtl)
				$html .= "								<option value=\"".$dtl['banco']."\" ".(($form['banco'] == $dtl['banco'])? "selected":"" ).">".$dtl['descripcion']."</option>\n";
			
			$html .= "							</select>\n";

			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td width=\"19%\">Nº CTA. CORRIENTE:</td>\n";
			$html .= "						<td width=\"30%\" class=\"modulo_list_claro\" >\n";
			$html .= "							<input type=\"text\" name=\"numero_cuenta\" class=\"input-text\" style=\"width:90%\" maxlength=\"40\" value=\"".$form['cta_cte']."\">\n";

			$html .= "						</td>\n";
			$html .= "						<td width=\"20%\" align=\"left\">GIRADOR</td>\n";
			$html .= "						<td width=\"31%\" class=\"modulo_list_claro\">\n";
			$html .= "							<input type=\"text\" name=\"girador\" class=\"input-text\" style=\"width:90%\" maxlength=\"30\" value=\"".$form['girador']."\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td title=\"FECHA CHEQUE\">F. CHEQUE:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" >\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"fecha_cheque\" style=\"width:35%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$form['fecha_cheque']."\">\n";
			$html .= "							".ReturnOpenCalendarioHTML('cheques','fecha_cheque','/')."\n";			
			$html .= "						</td>\n";
			$html .= "						<td>F. TRANSACCION:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" >\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"fecha_transaccion\" style=\"width:35%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".(($form['fecha'])? $form['fecha']:date("d/m/Y"))."\">\n";
			$html .= "							".ReturnOpenCalendarioHTML('cheques','fecha_transaccion','/')."\n";			
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td>TOTAL:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" colspan=\"3\">\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"valor\" style=\"width:35%\" onkeypress=\"return acceptNum(event)\" value=\"".FormatoValor($form['total'],null,false)."\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<table align=\"center\" width=\"100%\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$html .= "		</td>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"CerraPagos()\">\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
      $html .= "    </td>\n"; 
      $html .= "  </tr>\n"; 
      $html .= "</table>\n"; 
			return $html;
		}
		/**
		* Funcion donde se crea la forma para ingresar el pago con tarjeta credito
    *
		* @params array $form Arreglo de datos con la informacion de request
		* @params array $confirman Arreglo de datos con la informacion de las entidades que confirman
		* @params array $tarjetas Arreglo de datos con la informacion de las tarjetas
    *
		* @return String
		*/
    function FormaPagoTarjetaCredito($form,$confirman,$tarjetas)
		{
			$est  = "style=\"text-align:left;padding:4pt\"";

      $html  = "<table width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td class=\"titulo_tabla\" >REGISTRO DE PAGOS CON TARJETA CREDITO</td>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"modulo_list_claro\">\n";
			$html .= "<div class=\"normal_10AN\" style=\"text-align:center\">\n";
			//$html .= "  VALOR A CANCELAR: $".FormatoValor($form['valor_cancelar'])."\n";
			$html .= "  VALOR A CANCELAR: $".$form['valor_final']."\n";
			$html .= "</div>\n";
      $html .= "<form name=\"tarjeta\" id=\"tarjeta\" action=\"javascript:EvaluarDatos('tarjeta','R')\" method=\"post\">\n";
			$html .= "<table width=\"100%\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<div id=\"error_credito\" style=\"text-align:center\" class=\"label_error\"></div>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend>PAGO CON TARJETA CREDITO</legend>\n";
			$html .= "				<table width=\"100%\" class=\"modulo_table_list\" >\n";
			$html .= "					<tr class=\"modulo_table_list_title\">\n";
			$html .= "						<td colspan=\"4\">DATOS AUTORIZACION TARJETA</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html	.= "						<td width=\"19%\">ENTIDAD CONFIRMA:</td>\n";
			$html .= "						<td width=\"30%\" class=\"modulo_list_claro\">\n";
			$html .= "							<select name=\"entidad\" class=\"select\">\n";
			$html .= "								<option value=\"-1\">------SELECCIONAR------</option>\n";

			foreach($confirman as $key => $dtl)
				$html .="								<option value=\"".$dtl['entidad_confirma']."\" ".(($dtl['entidad_confirma'] == $form['entidad_confirma'])? "selected":"")." >".$dtl['descripcion']."</option>\n";

			$html .= "							</select>\n";

			$html .= "						</td>\n";
			$html .= "   					<td width=\"20%\">FECHA</td>\n";
			$html .= "						<td class=\"modulo_list_claro\">\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"fecha_confirma\" style=\"width:35%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".(($form['fecha_confirmacion'])? $form['fecha_confirmacion']:date("d/m/Y"))."\">".ReturnOpenCalendarioHTML('tarjeta','fecha_confirma','/')."\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td>FUNCIONARIO CONFIRMA</td>\n";
			$html .= "						<td class=\"modulo_list_claro\">\n";
			$html .= "							<input name=\"funcionario\" class=\"input-text\" type=\"text\" style=\"width:90%\" maxlength=\"40\" value=\"".$form['funcionario_confirma']."\">\n";
			$html .= "						</td>\n";
			$html .= "						<td>Nº CONFIRMACIÓN</td>\n";
			$html .= "						<td class=\"modulo_list_claro\">\n";
			$html .= "							<input name=\"numero\" class=\"input-text\" type=\"text\" style=\"width:90%\" maxlength=\"15\" value=\"".$form['numero_confirmacion']."\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table><br>\n";
			
			$html .= "				<table width=\"100%\" class=\"modulo_table_list\" >\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td width=\"19%\">TARJETA</td>\n";
			$html .= "						<td colspan=\"3\" class=\"modulo_list_claro\" >\n";
			$html .= "							<select name=\"tarjeta\" class=\"select\" >\n";
			$html .= "								<option value=\"-1\">-SELECCIONAR-</option>\n";
			
			foreach($tarjetas['C'] as $key => $dtl)
				$html .= "								<option value=\"".$dtl['tarjeta']."\" ".(($dtl['tarjeta'] == $form['tarjeta'])? "selected":"").">".$dtl['descripcion']."</option>\n";
			
			$html .= "							</select>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td width=\"20%\">Nº TARJETA</td>\n";
			$html .= "						<td width=\"30%\" class=\"modulo_list_claro\" >\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"num_tarjeta\" style=\"width:90%\" maxlength=\"20\" value=\"".$form['tarjeta_numero']."\">\n";
			$html .= "						</td>\n";
			$html .= "						<td width=\"20%\">SOCIO</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" >\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"socio\" style=\"width:90%\" maxlength=\"40\" value=\"".$form['socio']."\">\n";
			
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td>F. EXPIRACIÓN:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" >\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"fecha_expiracion\" style=\"width:35%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$form['fecha_expira']."\">\n";
			$html .= "							".ReturnOpenCalendarioHTML('tarjeta','fecha_expiracion','/')."\n";			

			$html .= "						</td>\n";
			$html .= "						<td>F. TRANSACCION:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" >\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"fecha_transaccion\" style=\"width:35%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".(($form['fecha'])? $form['fecha']:date("d/m/Y"))."\">\n";
			$html .= "							".ReturnOpenCalendarioHTML('tarjeta','fecha_transaccion','/')."\n";			
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td>TOTAL:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" colspan=\"3\">\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"valor\" style=\"width:35%\" onkeypress=\"return acceptNum(event)\" value=\"".FormatoValor($form['total'],null,false)."\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<table align=\"center\" width=\"100%\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$html .= "		</td>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"CerraPagos()\">\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
      $html .= "    </td>\n"; 
      $html .= "  </tr>\n"; 
      $html .= "</table>\n"; 

			return $html;
		}
		/**
		* Funcion donde se crea la forma para ingresar el pago con tarjeta debito
    *
		* @params array $form Arreglo de datos con la informacion de request
		* @params array $tarjetas Arreglo de datos con la informacion de las tarjetas
    *
		* @return String
		*/
		function FormaPagoTarjetaDebito($form,$tarjetas)
		{
			$est  = "style=\"text-align:left;padding:4pt\"";
			
      $html  = "<table width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td class=\"titulo_tabla\" >REGISTRO DE PAGOS CON TARJETA DEBITO</td>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"modulo_list_claro\">\n";
			$html .= "<div class=\"normal_10AN\" style=\"text-align:center\">\n";
			//$html .= "  VALOR A CANCELAR: $".FormatoValor($form['valor_cancelar'])."\n";
			$html .= "  VALOR A CANCELAR: $".$form['valor_final']."\n";
			$html .= "</div>\n";
      $html .= "<form name=\"tarjetas\" id=\"tarjeta\" action=\"javascript:EvaluarDatos('tarjeta','D')\" method=\"post\">\n";
			$html .= "<table width=\"100%\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<div id=\"error_debito\" style=\"text-align:center\" class=\"label_error\"></div>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend>PAGO CON TARJETA DEBITO</legend>\n";
			$html .= "				<table width=\"100%\" class=\"modulo_table_list\" >\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td >TARJETA</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" width=\"27%\">\n";
			$html .= "							<select name=\"tarjeta\" class=\"select\" >\n";
			$html .= "								<option value='-1'>-SELECCIONAR-</option>\n";
			
			foreach($tarjetas['D'] as $key => $dtl)
				$html .= "								<option value=\"".$dtl['tarjeta']."\" ".(($dtl['tarjeta'] == $form['tarjeta'])? "selected":"")." >".$dtl['descripcion']."</option>\n";

			$html .= "							</select>\n";

			$html .= "						</td>\n";
			$html .= "						<td>Nº TARJETA</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" width=\"27%\">\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"num_tarjeta\" style=\"width:90%\" maxlength=\"20\" value=\"".$form['tarjeta_numero']."\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td >Nº AUTORIZACIÓN</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" >\n";
			$html .= "								<input type=\"text\" class=\"input-text\" name=\"num_autorizacion\" style=\"width:90%\" maxlength=\"15\" value=\"".$form['autorizacion']."\">\n";
			$html .= "						</td>\n";
			$html .= "						<td>TOTAL:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\">\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"valor\" style=\"width:90%\" onkeypress=\"return acceptNum(event)\" value=\"".FormatoValor($form['total'],null,false)."\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<table align=\"center\" width=\"100%\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$html .= "		</td>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"CerraPagos()\">\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
      $html .= "    </td>\n"; 
      $html .= "  </tr>\n"; 
      $html .= "</table>\n"; 
			return $html;
		}
		/**
		* Funcion donde se crea la forma para ingresar el pago con bonos
    *
		* @params array $form Arreglo de datos con la informacion de request
    *
		* @return String
		*/
		function FormaPagoBonos($form)
		{
      $html  = "<table width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td class=\"titulo_tabla\" >REGISTRO DE PAGOS CON BONOS</td>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"modulo_list_claro\">\n";
			$html .= "<div class=\"normal_10AN\" style=\"text-align:center\">\n";
			//$html .= "  VALOR A CANCELAR: $".FormatoValor($form['valor_cancelar'])."\n";
			$html .= "  VALOR A CANCELAR: $".$form['valor_final']."\n";
			$html .= "</div>\n";
      $html .= "<form name=\"bonos\" id=\"bonos\" action=\"javascript:EvaluarDatos('bonos','B')\" method=\"post\">\n";
			$html .= "<table width=\"100%\" align=\"center\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<fieldset style=\"width:80%\" class=\"fieldset\">\n";
			$html .= "				<legend>PAGO CON BONOS</legend>\n";
			$html .= "				<div id=\"error_bono\" class=\"label_error\"></div>\n";
			$html .= "				<table width=\"100%\" align=\"center\">\n";
			$html .= "					<tr class=\"normal_10AN\">\n";
			$html .= "						<td width=\"25%\">VALOR A PAGAR:</td>\n";
			$html .= "						<td>\n";
			$html .= "							$<input type=\"text\" class=\"input-text\" name=\"valor\" value=\"".$form['h_bono']."\" style=\"width:60%\" onkeypress=\"return acceptNum(event)\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "				<table align=\"center\" width=\"100%\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$html .= "						</td>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"CerraPagos()\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
      $html .= "    </td>\n"; 
      $html .= "  </tr>\n"; 
      $html .= "</table>\n"; 
			return $html;
		}
		
		/**
		* Funcion donde se crea la forma para imprimir la factura de venta
    *
    * @param array $action Arreglo de datos con los links
    * @param array $factura Arreglo de datos con la informacion de la factura
    *
		* @return String
		*/
		function FormaImprimirFactura($action,$factura)
		{
            $xml   = AutoCarga::factory("ReportesCsv");
            $html .= $xml->GetJavacriptReporteFPDF('app','VentaFarmacia','FacturaVenta',$factura,array("interface"=>5));
			$fnc = $xml->GetJavaFunction();
			$html .= ThemeAbrirTabla("CONFIRMACION","50%");
			$html .= "<table width=\"100%\" align=\"center\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td class=\"normal_10AN\" align=\"center\">\n";
			$html .= "			FACTURA GENERADA SATISFACTORIAMENTE\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "  <table align=\"center\" width=\"50%\">\n";
			$html .= "    <tr>\n";
			$html .= "	    <td align=\"center\" width=\"50%\">\n";
			$html .= "		    <input type=\"button\" class=\"input-submit\" name=\"imprimir\" value=\"Imprimir Factura\" onclick=\"".$fnc."\">\n";
			$html .= "		  </td>\n";
			$html .= "	    <td align=\"center\" width=\"50%\">\n";
			$html .= "		    <input type=\"submit\" class=\"input-submit\" name=\"cancela\" value=\"Volver\" >\n";
			$html .= "		  </td>\n";
			$html .= "	  </tr>\n";
			$html .= "  </table>\n";
			$html .= "</form>\n";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
		
		
		/*****************************************************************************
		* Forma ingreso de datos para reimpresion de facturas -08312012
		******************************************************************************/
		function FormaDatosReimpresion($action,$empresa,$centro,$bodega)
		{
		    $cls = AutoCarga::factory("ReporteFacturaSQL", "classes", "app", "VentaFarmacia");
		    $pref = $cls->ObtenerPrefijo($empresa,$bodega);
			$ctl = AutoCarga::factory("ClaseUtil"); 
			
			$html  = $ctl->AcceptNum();
			$html .= ThemeAbrirTabla('ZONA SALUD','50%');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"formulacion_table_list\">\n";
			$html .= "		      <td align=\"center\">DATOS DE FACTURA</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
 			$html .= "		     <td align=\"center\">\n";    
			
			$html .= "<form name=\"volver\" action=\"".$action['reimpresion_final']."\" method=\"post\">\n";
			$html .= "<table>";
			$html .= "<tr class=\"formulacion_table_list\">";
			$html .= "  <td>";
	        $html .= "   PREFIJO ";		
			$html .= "  </td>";
			$html .= "  <td align=\"center\">";
            $html .= "  <input type=\"text\" class=\"input-text\" align=\"center\" readonly=\"readonly\" name=\"prefijo\" style=\"width:100%\" value=\"".$pref['prefijo']."\">\n";			
			$html .= "  <input type=\"hidden\" name=\"empresa_id\" value=\"".$empresa."\">\n";
			$html .= "  <input type=\"hidden\" name=\"centro_utilidad\" value=\"".$centro."\">\n";
			$html .= "  <input type=\"hidden\" name=\"bodega\" value=\"".$bodega."\">\n";
			$html .= "  </td>";
			$html .= "  <td>";
            $html .= "  NUMERO FACTURA";
			$html .= "  </td>";
			$html .= "  <td><input type=\"text\" class=\"input-text\" maxlength=\"5\" onkeypress=\"return acceptNum(event)\" name=\"factura_fiscal\" style=\"width:100%\" value=\"\"></td>\n";
			$html .= "</tr>";
			$html .= "</table>";
			$html .= "	<input type=\"submit\" class=\"input-submit\" name=\"enviar\" value=\"Enviar\">\n";
			$html .= "</form>\n";		
			
 			$html .= "		     </td>\n"; 			
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
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
		
		
		
		/*****************************************************************************
		* Forma mensajes de sistema -08312012
		******************************************************************************/
		function Mensajes($action,$mensaje)
		{
	
			$html  = ThemeAbrirTabla('ZONA SALUD','50%');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"formulacion_table_list\">\n";
			$html .= "		      <td align=\"center\">".$mensaje."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
 			$html .= "		     <td align=\"center\">\n";    
 			$html .= "		     </td>\n"; 			
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
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
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}
?>