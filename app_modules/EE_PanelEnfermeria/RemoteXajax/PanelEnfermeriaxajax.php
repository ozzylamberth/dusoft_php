<?php
	/**************************************************************************************
	* $Id: EvolucionXajax.php,v 1.2 2010/04/13 17:13:24 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Tizziano Perea O.
	**************************************************************************************/
	function ActualizarEstados($cadena){
  //  $objResponse = new xajaxResponse();
      list($dbconn) = GetDBconn();
      //TRAE EL ESTADO DEL INGRESO
      $resultapa = $dbconn->Execute($cadena);
/*
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      while (!$resultapa->EOF)
      {
        $useper[]=$resultapa->GetRowAssoc($ToUpper = false);
        $resultapa->MoveNext();
        return true;
      }
*/
//    return $objResponse;    
  }
  
  function getEstadosIngresos($ingreso){
    list($dbconn) = GetDBconn();
		//TRAE EL ESTADO DEL INGRESO
		$querypi = "SELECT estado, estado_act_ina FROM ingresos WHERE ingreso = ".$ingreso;
		$resultapa = $dbconn->Execute($querypi);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while (!$resultapa->EOF)
		{
			$useper[]=$resultapa->GetRowAssoc($ToUpper = false);
			$resultapa->MoveNext();
		}
    return $useper;
  }
  
  function getEstadosCuentas($ingcue){
    list($dbconn) = GetDBconn();
		//TRAE EL ESTADO DEL INGRESO
		$querypi = "SELECT estado, estado_act_ina FROM cuentas WHERE numerodecuenta = ".$ingcue;
		$resultapa = $dbconn->Execute($querypi);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while (!$resultapa->EOF)
		{
			$useper[]=$resultapa->GetRowAssoc($ToUpper = false);
			$resultapa->MoveNext();
		}
    return $useper;
  }
	function GetRegIngresoPaciente($paciente_id)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT * FROM ingresos WHERE paciente_id = ".$paciente_id." AND (estado = 1) ORDER BY ingreso DESC LIMIT 1";
		$resultado = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "EE_PanelEnfermeria - main - SQL ERROR 1";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}

		while (!$resultado->EOF)
		{
			$vector2[]=$resultado->GetRowAssoc($ToUpper = false);
			$resultado->MoveNext();
		}

		return $vector2;
	}
  
  function Activar_IngCue($ingreso, $ingcue, $estadoi, $estadoc, $paciente_id)
  {
    $objResponse = new xajaxResponse();
    $GetRegPac = GetRegIngresoPaciente($paciente_id);
    $VanIna = 0;

    if(count($GetRegPac) > 0){
      if ($ingreso < $GetRegPac[0]['ingreso']){
        $VanIna = 1;
      }
    }

//    $VanIna = 0;
    if($VanIna == 0){
      $VecIng = getEstadosIngresos($ingreso);
      $estadod = $VecIng[0]['estado'];
      $esteaid = $VecIng[0]['estado_act_ina'];

      $VecCue = getEstadosCuentas($ingcue);
      $estadoe = $VecIng[0]['estado'];
      $esteaie = $VecIng[0]['estado_act_ina'];
      
      
      if ($estadoi == 1 and $estadoc == 1){
        //ingreso
        $query  = "UPDATE ingresos SET estado_act_ina = '$estadod', estado = '2' WHERE ingreso = ".$ingreso;
        //cuenta
        $queryc = "UPDATE cuentas  SET estado_act_ina = '$estadoe', estado = '2' WHERE numerodecuenta = ".$ingcue;
        ActualizarEstados($query);
        ActualizarEstados($queryc);
      }else{
        if ($estadoi == 2 and $estadoc == 2){
          //ingreso
          $query = "UPDATE ingresos SET estado = '$esteaid', estado_act_ina = '0' WHERE ingreso = ".$ingreso;
          //cuenta
          $queryc = "UPDATE cuentas SET estado = '$esteaie', estado_act_ina = '0' WHERE numerodecuenta = ".$ingcue;
          ActualizarEstados($query);
          ActualizarEstados($queryc);
        }
      }
      
    }else{
      $cad23 = 'El paciente esta en otra estación';
      $cad23 = eliminarCaracteresEspeciales($cad23);
      $objResponse->alert($cad23);
    }
//    $objResponse->alert($ingreso." - ".$GetRegPac[0]['ingreso']." - ".$paciente_id." - ".$VanIna);
		return $objResponse;
  }
  
	function GetIngresoPaciente($paciente_id)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT * FROM ingresos WHERE paciente_id = ".$paciente_id." AND estado = 1 ORDER BY ingreso DESC LIMIT 1";
		$resultado = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "EE_PanelEnfermeria - main - SQL ERROR 1";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}

		while (!$resultado->EOF)
		{
			$vector2[]=$resultado->GetRowAssoc($ToUpper = false);
			$resultado->MoveNext();
		}
    
		return $vector2;
	}
  
  function eliminarCaracteresEspeciales($cadena){

    $aux_cadena = "";
    $aux_acentos = "??????????????????????????????";
    $aux_validos = "aeiouAEIOUaeiouAEIOUaeiouAEIOU";

    $aux_cadena = trim($cadena);
    $aux_cadena = eregi_replace("\r]", ' ', $aux_cadena);
    $aux_cadena = str_replace("''", '', $aux_cadena);
    $aux_cadena = strtr($aux_cadena, $aux_acentos, $aux_validos);

    // para el control de caracteres especiales como la ?,?; es necesario codificar la cadena en UTF8 (para la correcta escritura en excel)
    //if(strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?'))    
    $aux_cadena = @utf8_encode($aux_cadena);

    return $aux_cadena;

  } // fin-function eliminarCaracteresEspeciales
  
?>