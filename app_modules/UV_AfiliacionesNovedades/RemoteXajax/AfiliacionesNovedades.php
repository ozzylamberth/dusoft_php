<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: AfiliacionesNovedades.php,v 1.1 2007/12/19 23:11:47 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */

  /**
  * Funcion que hace la busqueda de un afiliado en la base de datos,para verificar 
  * que el afiliado esta registrado en el sistema
  *
  * @param array $datos Vector con los datos de la identificacion del afiliado
  *
  * @return object
  */
  function BuscarAfiliado($datos)
  {
    $objResponse = new xajaxResponse();
    $afi = AutoCarga::factory("AfiliacionesNovedades", "", "app","UV_AfiliacionesNovedades");

    $rst = $afi->ExistenciaAfiliado($datos['afiliado_tipo_id'],$datos['afiliado_id']);

    if(!$rst)
    {
      $html = "EL AFILIADO CON IDENTIFICACION: ".$datos['afiliado_tipo_id']." ".$datos['afiliado_id'].", NO ESTA REGISTRADO EN EL SISTEMA";
      $objResponse->assign("error","innerHTML",$html);
    }
    else
    {
      $objResponse->call("continuar");
    }
    return $objResponse;
  }
  /**
  * Funcion que hace la busqueda de un afiliado en la base de datos para verificar
  * que no exista otro afiliado con el numero de identificacion nuevo que se esta
  * pasando
  *
  * @param array $datos Vector con los datos de la identificacion del afiliado
  *
  * @return object
  */
  function AfiliadoExiste($datos)
  {
    $objResponse = new xajaxResponse();
    $afi = AutoCarga::factory("AfiliacionesNovedades", "", "app","UV_AfiliacionesNovedades");

    $rst = $afi->ExistenciaAfiliado($datos['afiliado_tipo_id'],$datos['afiliado_id']);

    if($rst)
    {
      $html = "YA EXISTE UN AFILIADO CREADO CON LA IDENTIFICACION: ".$datos['afiliado_tipo_id']." ".$datos['afiliado_id']." ";
      $objResponse->assign("error","innerHTML",$html);
    }
    else
    {
      $objResponse->call("continuar");
    }
    return $objResponse;
  }
?>