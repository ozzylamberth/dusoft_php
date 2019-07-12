<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosAsignarDocumentosABodegas.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */
  /*
  * Funcion donde se ingresan los dias
  *
  * @param array $form Dtaos de la forma
  *
  * @return object
  */  
  function IngresarDias($form)
  {
    $objResponse = new xajaxResponse();
    //$objResponse->alert(print_r($form,true));
    
    $cls = AutoCarga::factory("TiposProductosSQL","classes","app","Inv_ParametrosIniciales");
    $rst = $cls->IngresarDiasEnvio($form);
    $mensaje = "<label class=\"normal_10AN\">DATOS INGRESADOS CORRECTAMENTE</label>";
    if(!$rst)
      $mensaje = "<label class=\"label_error\">".$cls->mensajeDeError."</label>";
    
    $objResponse->assign("error","innerHTML",$mensaje);
    return $objResponse;
  }
?>