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
 function GuardarJefe($doc_tmp_id,$sw_jefebodega,$sw_jefecontroli,$empresa_id)
{
   $objResponse = new xajaxResponse();
   //$objResponse->alert($torre);
   $mdl = AutoCarga::factory("ConsultasParamJefesAuto","","app","Inv_ParametrosIniciales");
   $jefe=$mdl->BuscarparDoc_Tmp($empresa_id,$doc_tmp_id);
   //print_r($jefe);
   $contar=count($jefe);
  //print_r($sw_jefebodega);
   //print_r($sw_jefecontroli);
    for($i=0;$i<$contar;$i++)
    {
     if($jefe[$i]['doc_tmp_id']==$doc_tmp_id)
     {
        $actualparam=$mdl->ActuParam($doc_tmp_id,$sw_jefebodega,$sw_jefecontroli,$empresa_id);
     }
    }
     if(empty($jefe))
     {
       $GuardarParam=$mdl->GuardarParGrabar($doc_tmp_id,$sw_jefebodega,$sw_jefecontroli,$empresa_id);
     }
  $url=ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "AutorJefes",array("variable"=>$variable));
                       $objResponse->script('
                              window.location="'.$url.'";
                                                               ');
   return $objResponse;
}

?>