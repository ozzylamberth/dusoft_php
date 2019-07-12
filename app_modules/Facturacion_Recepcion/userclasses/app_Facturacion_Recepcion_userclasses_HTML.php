<?php
 /**
 * $Id: app_Facturacion_Recepcion_userclasses_HTML.php,v 1.3 2007/06/26 23:29:14 carlos Exp $
 * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de la recepcion de facturas credito
 */

/**
*  app_Facturacion_Recepcion_userclasses_HTML.php
*/
class app_Facturacion_Recepcion_userclasses_HTML extends app_Facturacion_Recepcion_user
{
	/**
	* @return boolean
	*/

		function app_Facturacion_Recepcion_userclasses_HTML()
		{
					$this->salida='';
					$this->app_Facturacion_Recepcion_user();
					return true;
		}


		function SetStyle($campo)
		{
					if ($this->frmError[$campo] || $campo=="MensajeError"){
						if ($campo=="MensajeError"){
				$arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
						}
						return ("label_error");
					}
				return ("label");
		}
}//fin clase
?>