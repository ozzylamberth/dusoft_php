<?php
	/**
	* Archivo Xajax
	* Tiene como responsabilidad hacer el manejo de las funciones
	* que son invocadas por medio de xajax
	*
	* @package IPSOFT-SIIS
	* @version $Revision: 1.25 $
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	

  /* PARAMETRIZAR LAS FARMACIAS */
 /*
		* Funcion que Muestra un menu para parametrizar la farmacia
		* @return object $objResponse objeto de respuesta al formulario
	*/
		function UpdateTipoAtencion ($valor,$farmacia,$actual)
		{
			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("ParametrizacionFarmaciaSQL", "", "app", "ParametrizacionFarmacia");
		    $rst =$sel->ActualizarTipoAtencion($valor,$farmacia,$actual);
			
			$url=ModuloGetURL("app", "ParametrizacionFarmacia", "controller", "ParametrizacionFarmacia");
			$objResponse->script('
						 window.location="'.$url.'";
							');
			
			return $objResponse;
		}
	
?>