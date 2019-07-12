<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: GestionPagos.php,v 1.2 2008/10/23 22:09:09 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */

  /**
  * Funcion para hacer el ingreso del numero de orden de gasto
  * para la pre-orden de pago
  *
  * @param integer $pre_orden Identificador de la pre-orden
  * @param integer $orden_gasto Numero de la orden de gasto (opcional)
  * @param string $offset Referencia del paginador
  *
  * @return object
  */
  function IngresarNumeroRadicacion($pre_orden,$orden_gasto,$offset)
  {
    $objResponse = new xajaxResponse();
    
    $gph = AutoCarga::factory('GestionPagosHTML','views','app','UV_CxPGestionPagos');
    $html = $gph->FormaNumeroRadicacionExterno($pre_orden,$offset);
    
    $objResponse->assign("ventana","innerHTML",$html);
    $objResponse->assign("erroro","innerHTML","");
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }    
  /**
  * Funcion para hacer el ingreso de la observacion del estado de la 
  * pre-orden
  *
  * @param integer $pre_orden Identificador de la pre-orden
  * @param string $offset Referencia del paginador
  *
  * @return object
  */
  function IngresarEstadoObservacion($pre_orden,$offset)
  {
    $objResponse = new xajaxResponse();
    
    $gph = AutoCarga::factory('GestionPagosHTML','views','app','UV_CxPGestionPagos');
    $html = $gph->FormaObservacionEstado($pre_orden,$offset);
    
    $objResponse->assign("ventana","innerHTML",$html);
    $objResponse->assign("erroro","innerHTML","");
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }  
  /**
  * Funcion para hacer el registro del numero de radicacion de la 
  * pre-orden de pago
  *
  * @param array $form Vector con los datos de la forma
  * @param integer $pre_orden Identificador de la pre-orden
  * @param string $offset Referencia del paginador
  * @param array $buscador Vector con los datos del buscador
  *
  * @return object
  */
  function RegistrarNumeroRadicacion($form,$pre_orden,$offset,$buscador)
  {
    $objResponse = new xajaxResponse();
    if($form['numero_orden'] == "")
    {
      $mensaje = "SE DEBE INGRESAR UN NUMERO DE ORDEN DE GASTO ";
      $objResponse->assign("erroro","innerHTML",$mensaje);
    }
    else
    {
      $gp = AutoCarga::factory('GestionPagos','','app','UV_CxPGestionPagos');
      $gph = AutoCarga::factory('GestionPagosHTML','views','app','UV_CxPGestionPagos');
      
      $rst = $gp->RegistrarNumeroRadicacion($form['numero_orden'],$pre_orden);
      $mensaje = "EL NUMERO DE RADICACION SE HA INGRESADO CORRECTAMENTRE ";
      if(!$rst)
        $mensaje = "ERROR: ".$gp->mensajeDeError;
      else
      {
        $objResponse->assign("numero_".$pre_orden,"innerHTML",$form['numero_orden']);
      }
      $html = $gph->FormaMensaje($mensaje);
    
      $objResponse->assign("ventana","innerHTML",$html);
    }
    
    return $objResponse;
  }  
  /**
  * Funcion para hacer el registro de la observacion al estado
  *
  * @param array $form Vector con los datos de la forma
  * @param integer $pre_orden Identificador de la pre-orden
  * @param string $offset Referencia del paginador
  * @param array $buscador Vector con los datos del buscador
  *
  * @return object
  */
  function RegistrarEstadoObservacion($form,$pre_orden,$offset,$buscador)
  {
    $objResponse = new xajaxResponse();
    if($form['observacion'] == "")
    {
      $mensaje = "SE DEBE INGRESAR LA OBSERVACION ";
      $objResponse->assign("erroro","innerHTML",$mensaje);
    }
    else
    {
      $gp = AutoCarga::factory('GestionPagos','','app','UV_CxPGestionPagos');
      $gph = AutoCarga::factory('GestionPagosHTML','views','app','UV_CxPGestionPagos');
      
      $rst = $gp->RegistrarEstadoObservacion(utf8_decode($form['observacion']),$pre_orden);
      $mensaje = "LA OBSERVACION AL ESTADO DE LA RADICACION SE REALIZO CORRECTAMENTE ";
      if(!$rst)
        $mensaje = "ERROR: ".$gp->mensajeDeError;
      else
      {
        $objResponse->assign("observacion_".$pre_orden,"innerHTML",$form['observacion']);
      }
      $html = $gph->FormaMensaje($mensaje);
    
      $objResponse->assign("ventana","innerHTML",$html);
    }
    return $objResponse;
  }
?>