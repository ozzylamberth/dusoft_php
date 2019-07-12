<?php
	/**
	* Archivo Xajax
	* Tiene como responsabilidad hacer el manejo de las funciones
	* que son invocadas por medio de xajax
	*
	* @package IPSOFT-SIIS
	* @version $Revision: 1.25 $
	* @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
  */
  
  /**
  * Funcion que permite Visualizar las formas de pago
  * @return object $objResponse objeto de respuesta al formulario
  */
    function TrasInformaPago($valorPagar,$Descuento,$Recibocaja_id,$cajafact_id)
		{
      $objResponse = new xajaxResponse();
      $valortotalPagar=$valorPagar-$Descuento;
			$html .= "<form name=\"pagos\" id=\"pagos\" method=\"post\" >\n";
			$html .= "  <table width=\"55%\"  border=\"0\"  align=\"center\">";
			$html .= "	  <tr  align=\"center\" class=modulo_list_oscuro  >\n";
			$html .= "      <td width=\"35%\"><b>VALOR TOTAL A PAGAR INCLUIDO DESCUENTO :</b></td>\n";
			$html .= "      <td width=\"15%\"><b>$".$valortotalPagar."</b></td>\n";
			$html .= "  </tr>\n";
			$html .= "	</table><br>\n";
			$html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\">FORMAS DE PAGO\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
	 		$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td align=\"center\">\n";
			$html .= "         <a href=\"#\" onclick=\"xajax_PagoEfectivoCompleto('".$valorPagar."','".$valortotalPagar."','".$Recibocaja_id."','".$cajafact_id."')\"  class=\"label_error\">EFECTIVO</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td align=\"center\">\n";
			$html .= "         <a href=\"#\" onclick=\"xajax_TipoPagoCheque('".$valortotalPagar."')\"  class=\"label_error\">CHEQUE</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td align=\"center\">\n";
			$html .= "         <a href=\"#\" onclick=\"xajax_TarjetaDebito('".$valortotalPagar."')\"  class=\"label_error\">TARJETA DEBITO</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td align=\"center\">\n";
			$html .= "         <a href=\"#\" onclick=\"xajax_TarjetaCredito('".$valortotalPagar."')\"  class=\"label_error\">TARJETA CREDITO</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "	</table><br>\n";
			$objResponse->assign("TiposPagos","innerHTML",$html);
			return $objResponse;
		}
 /**
  * Funcion que permite  ir a la forma del pago en efectivo
  * @return object $objResponse objeto de respuesta al formulario
  */
		function  PagoEfectivoCompleto($valorpagar,$valorconDescuento,$Recibocaja_id,$cajafact_id)
		{
			$objResponse = new xajaxResponse();
			$url=ModuloGetURL("app", "CajaGeneralEmpresa", "controller", "PagoEfectivoCompleto2",array("valorconDescuento"=>$valorconDescuento));
			$objResponse->script('
						 window.location="'.$url.'";
								');
	
			return $objResponse;
		}
 /**
  * Funcion que permite  ir a la forma del pago en con cheque
  * @return object $objResponse objeto de respuesta al formulario
  */
		function TipoPagoCheque($valorconDescuento)
		{
        $objResponse = new xajaxResponse();
        $url=ModuloGetURL("app", "CajaGeneralEmpresa", "controller", "PagoChequeCompleto",array("valorconDescuento"=>$valorconDescuento));
        $objResponse->script('
        window.location="'.$url.'";
        ');
	      return $objResponse;
	  }
  /**
  * Funcion que permite  ir a la forma del pago con tarjeta debito
  * @return object $objResponse objeto de respuesta al formulario
  */
		function TarjetaDebito($valorconDescuento)
		{ 
        $objResponse = new xajaxResponse();
        $url=ModuloGetURL("app", "CajaGeneralEmpresa", "controller", "PagoConTarjetaDebito",array("valorconDescuento"=>$valorconDescuento));
        $objResponse->script('
        window.location="'.$url.'";
        ');

        return $objResponse;
		}
  /**
  * Funcion que permite  ir a la forma del pago con tarjeta credito
  * @return object $objResponse objeto de respuesta al formulario
  */
		function TarjetaCredito($valorconDescuento)
		{
			$objResponse = new xajaxResponse();
			$url=ModuloGetURL("app", "CajaGeneralEmpresa", "controller", "PagoConTarjetaCredito",array("valorconDescuento"=>$valorconDescuento));
			$objResponse->script('
						 window.location="'.$url.'";
								');
	
			return $objResponse;
		}
  ?>