<?php
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  
/**
    * Funcion guarda los registros de pacientes
    *
    /**
       * Funcion donde se almacena la informacion las bodegas virtuales
       *
       * @param  var $bodega contiene la bodega
       * @param  var $departamento contiene el departamento
       * @param  var $sw_virtual contiene el sw si es virtual
       * @return booleano
      */ 
 function GuardarBodegaVirtual($bodega,$departamento,$sw_virtual)
 {
   $objResponse = new xajaxResponse();
   $mdl = AutoCarga::factory("ConsultasBodegasVirtuales","","app","Inv_ParametrosIniciales");
  
   $BuscarProductos=$mdl->AgregarBodegaVirtual($bodega,$departamento,$sw_virtual);
   
   $url=ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "BodegasVirtuales");
			$objResponse->script('
					 window.location="'.$url.'";
								');
   return $objResponse;
  }
?>