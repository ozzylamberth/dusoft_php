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
 function GuardarTorreProd($codigo_producto,$descripcion,$empresa_id,$torre,$due_torre)
 {
   $objResponse = new xajaxResponse();
   $objResponse->alert($torre);
   $mdl = AutoCarga::factory("ConsultasParamTorresP","","app","Inv_ParametrosIniciales");
   $BuscarParam=$mdl->Buscarparamprod($empresa_id,$codigo_producto);
   if($BuscarParam)
   {
     $ActuParamT=$mdl->ActuParamT($codigo_producto,$descripcion,$empresa_id,$torre,$due_torre);
   }
   else
   {
     $AgregarTorre=$mdl->AgregarTorreP($codigo_producto,$descripcion,$empresa_id,$torre,$due_torre);
   }
   return $objResponse;
  }
?>