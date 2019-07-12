<?php

/**
 * $Id: modules.inc.php,v 1.8 2006/12/05 13:53:41 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * API para el Manejo de  los Modulos de la aplicacion
 */

/**
* Funcion que retorna informacion sobre un modulo de la aplicacion
* @return array
* @param string tipo de contenedor del modulo app,hc,hc_submodulo,system
* @param string Nombre del modulo
*/

function ModuloGetInfo($contenedor,$modulo)
{
  return array();
}


/**
* Funcion que retorna el estado (activo=true, Inactivo=false) de un modulo de la aplicacion
* @return boolean
* @param string tipo de contenedor del modulo app,hc,hc_submodulo,system
* @param string Nombre del modulo
*/

function ModuloGetEstado($contenedor,$modulo)
{
  list($dbconn) = GetDBconn();

  if($contenedor == 'system'){
    return true;
  }

    if($contenedor == 'hc'){
        $query = "SELECT activo
                  FROM system_hc_modulos
                  WHERE hc_modulo='$modulo'";

    }elseif($contenedor == 'hc_submodulo'){
        $query = "SELECT activo
                  FROM system_hc_submodulos
                  WHERE submodulo='$modulo'";

    }else{
        $query = "SELECT activo
                  FROM system_modulos
                  WHERE modulo='$modulo' AND modulo_tipo='$contenedor'";
    }

    $result = $dbconn->Execute($query);

    if($dbconn->ErrorNo() != 0) {
        return false;
    }

    if($result->EOF){
        return false;
    }

    list($estado) = $result->FetchRow();
    $result->Close();

    if($estado == '1'){
        return true;
    }

    return false;
}



function ModuloIncludeLib($contenedor,$modulo,$nombre_lib)
{
    if ((empty($contenedor)) || (empty($modulo)) || (empty($nombre_lib))) {
            return false;
    }
    $file=$contenedor."_modules/$modulo/lib/$nombre_lib".".lib.php";
    if(!IncludeFile($file,true)){
        return false;
    }
    return true;
}

function ModuloIncludeClass($contenedor, $modulo, $tipo, $nombre_class)
{
    if ((empty($contenedor)) || (empty($modulo)) || (empty($tipo)) || (empty($nombre_class))) {
            return false;
    }

     $file=$contenedor."_modules/$modulo/$tipo"."classes"."/$nombre_class".".class.php";

    if(!IncludeFile($file,true)){
        return false;
    }
    return true;
}

/**
* Funcion que retorna la URL a un paso de una evolucion de historia clinica
* @return string
* @param array Un arreglo asociativo con parametros para los submodulos del paso
* @param integer Numero de evolucion
* @param integer Numero de paso de la evolucion
*/

function ModuloHCGetURL($evolucion=0, $paso=0, $ingreso=0, $hc_modulo='', $hc_historico=false, $args=array())
{
 return ModuloGetURL('hc', '', '', '', $args, $ingreso, $hc_modulo, $evolucion, $paso, $hc_historico);
}


/**
* Funcion que retorna la URL a un metodo de un modulo de la aplicacion
* @return string
* @param string Tipo de contenedor - app,system,hc
* @param string Modulo al que pertenece la variable
* @param string Tipo de metodos del modulo user/admin opcional default user
* @param string Nombre del metodo opcional default main
* @param array Un arreglo asociativo con los parametros del metodo opcional -vacio
* @param integer Opcional solo para uso con el contendor='hc' es el numero de evolucion de Historia Clinica default 0
* @param integer Opcional solo para uso con el contendor='hc' es el numero de paso de la  evolucion de Historia Clinica default 0
*/

function ModuloGetURL($contenedor, $modulo='', $tipo='user', $metodo='main', $args=array(), $ingreso=0, $hc_modulo='', $evolucion=0, $paso=0, $hc_historico=false)
{
    if(empty($contenedor))
    {
        if(SessionGetVar('StyleFrames'))
        {
            $url = "Contenido.php";
        }
        else
        {
            $url = "index.php";
        }
        return GetBaseURL() . $url;
    }

    $urlargs[]   = session_name() . "=" . session_id();

    if($contenedor == 'hc'){
    $urlargs[] = "contenedor=hc";
    $urlargs[] = "evolucion=$evolucion";
    $urlargs[] = "paso=$paso";
    $urlargs[] = "hc_modulo=$hc_modulo";
    $urlargs[] = "ingreso=$ingreso";
    }
    else
    {
        if(empty($modulo))
        {
            return '';
        }

        if ((!empty($contenedor)) && ($contenedor != 'app'))
        {
            $urlargs[] = "contenedor=$contenedor";
        }

        if (!empty($modulo))
        {
            $urlargs[] = "modulo=$modulo";
        }

        if ((!empty($tipo)) && ($tipo != 'user'))
        {
            $urlargs[] = "tipo=$tipo";
        }

        if ((!empty($metodo)) && ($metodo != 'main'))
        {
            $urlargs[] = "metodo=$metodo";
        }
    }

    $urlargs = join('&', $urlargs);

    if(SessionGetVar('StyleFrames'))
    {
        $url = "Contenido.php?"."$urlargs";
    }
    else
    {
        $url = "index.php?$urlargs";
    }

    if (!is_array($args))
    {
        return false;
    }
    else
    {
        $url .= UrlRequest($args);
				/*foreach ($args as $k=>$v)
        {
            if (is_array($v))
            {
                foreach($v as $k2=>$v2)
                {
                    if (is_array($v2))
                    {
                        foreach($v2 as $k3=>$v3)
                        {
                            if (is_array($v3))
                            {
                                foreach($v3 as $k4=>$v4)
                                {
                                    $url .= "&$k" . "[$k2][$k3][$k4]=".urlencode($v4);
                                }
                            }
                            else
                            {
                                $url .= "&$k" . "[$k2][$k3]=".urlencode($v3);
                            }
                        }
                    }
                    else
                    {
                        $url .= "&$k" . "[$k2]=".urlencode($v2);
                    }
                }
            }
            else
            {
                if($k=='BOOKMARK')
                {
                    $BOOKMARK='#'.$v;
                }
                else
                {
                    $url .= "&$k=".urlencode($v);
                }
            }
        }*/
    }

    if(!empty($BOOKMARK))
    {
        $url .= $BOOKMARK;
    }

    return GetBaseURL() . $url;
}


/**
* Funcion para asignar o crear una variable de modulo
* @return boolean
* @param string Tipo de contenedor - app,system,hc
* @param string Modulo al que pertenece la variable
* @param string Nombre de la variable de modulo
* @param string Valor a guardar en la variable de modulo
*/

function ModuloSetVar($contenedor, $modulo, $variable, $valor)
{
    if (empty($variable)) {
        return false;
    }

    list($dbconn) = GetDBconn();

    $curvar = ModuloGetVar($contenedor, $modulo, $variable);

    if (!isset($curvar)) {
        if($contenedor == 'hc'){
            $query = "INSERT INTO system_hc_modulos_variables (hc_modulo, variable, valor)
                      VALUES ('$modulo','$variable','$valor');";

        }elseif($contenedor == 'hc_submodulo'){
            $query = "INSERT INTO system_hc_submodulos_variables (submodulo, variable, valor)
                      VALUES ('$modulo','$variable','$valor');";

        }else{
            $query = "INSERT INTO system_modulos_variables (modulo, modulo_tipo, variable, valor)
                      VALUES ('$modulo','$contenedor','$variable','$valor');";
        }

    } else {

        if($contenedor == 'hc'){
            $query = "UPDATE system_hc_modulos_variables
                      SET valor = '$valor'
                      WHERE hc_modulo = '$modulo'
                      AND variable = '$variable'";

        }elseif($contenedor == 'hc_submodulo'){
            $query = "UPDATE system_hc_submodulos_variables
                      SET valor = '$valor'
                      WHERE submodulo = '$modulo'
                      AND variable = '$variable'";

        }else{
            $query = "UPDATE system_modulos_variables
                      SET valor = '$valor'
                      WHERE modulo_tipo = '$contenedor'
                      AND modulo = '$modulo'
                      AND variable = '$variable'";
        }
    }

    $dbconn->Execute($query);

    if($dbconn->ErrorNo() != 0) {
        return false;
    }

    if($dbconn->Affected_Rows() > 0){
        global $VARIABLES_DE_MODULO;
        $VARIABLES_DE_MODULO[$contenedor][$modulo][$variable] = $valor;
        return true;
    }
    return false;
}


/**
* Funcion que retorna el valor de una variable de modulo
* @return string
* @param string Tipo de contenedor - app,system,hc
* @param string Modulo al que pertenece la variable
* @param string Nombre de la variable de modulo
*/

function ModuloGetVar($contenedor, $modulo, $variable)
{
    if (empty($variable)){
        return false;
    }

    global $VARIABLES_DE_MODULO;
    if (isset($VARIABLES_DE_MODULO[$contenedor][$modulo][$variable])) {
        return $VARIABLES_DE_MODULO[$contenedor][$modulo][$variable];
    }

    list($dbconn) = GetDBconn();

    if($contenedor == 'hc'){
        $query = "SELECT valor
                  FROM system_hc_modulos_variables
                  WHERE hc_modulo = '$modulo'
                  AND variable = '$variable'";

    }elseif($contenedor == 'hc_submodulo'){
        $query = "SELECT valor
                  FROM system_hc_submodulos_variables
                  WHERE submodulo = '$modulo'
                  AND variable = '$variable'";
    }else{
        $query = "SELECT valor
                  FROM system_modulos_variables
                  WHERE modulo_tipo = '$contenedor'
                  AND modulo = '$modulo'
                  AND variable = '$variable'";
    }

    $result = $dbconn->Execute($query);

    if($dbconn->ErrorNo() != 0) {
        return '';
    }

    if ($result->EOF) {
        return; // no debe retornar nada solo return;
    }

    list($valor) = $result->FetchRow();
    $result->Close();

    $VARIABLES_DE_MODULO[$contenedor][$modulo][$variable] = $valor;
    return $valor;
}


/**
* Funcion para borrar una variable de modulo
* @return boolean
* @param string Tipo de contenedor - app,system,hc
* @param string Modulo al que pertenece la variable
* @param string Nombre de la variable de modulo
*/

function ModuloDelVar($contenedor, $modulo, $variable)
{
    if (empty($variable)) {
        return false;
    }

    list($dbconn) = GetDBconn();

    if($contenedor == 'hc'){
        $query = "DELETE FROM system_hc_modulos_variables
                  WHERE hc_modulo = '$modulo'
                  AND variable = '$variable'";

    }elseif($contenedor == 'hc_submodulo'){
        $query = "DELETE FROM system_hc_submodulos_variables
                  WHERE submodulo = '$modulo'
                  AND variable = '$variable'";
    }else{
        $query = "DELETE FROM system_modulos_variables
                  WHERE modulo_tipo = '$contenedor'
                  AND modulo = '$modulo'
                  AND variable = '$variable'";
    }

    $dbconn->Execute($query);

    if($dbconn->ErrorNo() != 0) {
        return;
    }

    global $VARIABLES_DE_MODULO;
    if (isset($VARIABLES_DE_MODULO[$contenedor][$modulo][$variable])) {
        unset($VARIABLES_DE_MODULO[$contenedor][$modulo][$variable]);
    }
    return true;
}
	/****************************************************************************
	* Funcion para serializar un vector de datos
	* @param $array array Vector de datos a serializar
	*
	* @returns $url Cadena serializada de datos a adiciuonar a la cadena del link
	******************************************************************************/
	function UrlRequest($array)
	{
		$url = "";
		if(is_array($array))
		{
			foreach($array as $key => $var)
			{
				if(is_array($var))
					$url .= SerializarRequest($var,"$key");
				else
					$url .= "&$key=".urlencode($var);
			}
		}
		return $url;
	}
	/****************************************************************************
	* Funcion recursiva para serializar los datos del reques a partir del segundo 
	* nivel del array de datos
	* @param $array array Vector de datos a serializar
	* @param $nombre String nombre del vector que se vera en el request
	* @returns $url Cadena serializada de datos a adiciuonar a la cadena del link
	******************************************************************************/
	function SerializarRequest($array,$nombre)
	{
		$url = "";
		foreach($array as $key => $var)
		{
			if(is_array($var))
				$url .= SerializarRequest($var,$nombre."[$key]");
			else
				$url .= "&".$nombre."[$key]=".urlencode($var);
		}
		return $url;
	}
?>
