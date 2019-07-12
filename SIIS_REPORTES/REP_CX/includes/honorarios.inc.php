<?php

/**
 * $Id: honorarios.inc.php,v 1.2 2005/06/07 19:03:54 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

   function GetHonorariosCargo($empresa,$cargo,$tipo_id_profesional,$profesional_id,$plan='',$servicio='')
   {
       if(empty($empresa) || empty($cargo) || empty($tipo_id_profesional) || empty($profesional_id)){
            return false;
       }
       return true;
   }

   /*function ExaminarHorarioEspecial($fecha=date("Y-m-d"), $hora="00:00")
   {
        //if()
        return true;
   }*/

function BuscarCargoEquivalente($cargo_base,$plan_id)
{
	if(empty($cargo_base) || empty($plan_id))
	{
		return false;
	}
	$Salida=array();
	list($dbconn) = GetDBconn();
	global $ADODB_FETCH_MODE;
	$query = "SELECT a.tarifario_id,
			a.cargo,
			a.descripcion,
			a.precio,
			d.descripcion AS destar
			FROM tarifarios_detalle AS a,
			plan_tarifario AS b,
			(SELECT tarifario_id,
			cargo
			FROM tarifarios_equivalencias
			WHERE cargo_base='$cargo_base') AS c,
			tarifarios AS d
			WHERE b.plan_id = $plan_id
			AND b.tarifario_id = a.tarifario_id
			AND b.grupo_tarifario_id = a.grupo_tarifario_id
			AND b.subgrupo_tarifario_id = a.subgrupo_tarifario_id
			AND c.tarifario_id =  a.tarifario_id
			AND c.cargo = a.cargo
			AND c.tarifario_id = d.tarifario_id;";
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$resultado = $dbconn->Execute($query);
	$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
	if ($dbconn->ErrorNo() != 0)
	{
		die("SQL " . $dbconn->ErrorMsg());
		return false;
	}
	$i=0;
	while($cargo_equivalente = $resultado->FetchRow())
	{
		$query = "SELECT COUNT(*)
				FROM excepciones
				WHERE plan_id = $plan_id
				AND tarifario_id = '$cargo_equivalente[tarifario_id]'
				AND cargo = '$cargo_equivalente[cargo]'
				AND sw_no_contratado <> '0';";
		$resultado_count = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			die("SQL " . $dbconn->ErrorMsg());
			return false;
		}
		list($no_contratado) = $resultado_count->FetchRow();
		$resultado_count->Close();
		if(!$no_contratado)
		{//un equivalente
			$Salida['cargos'][$i]['tarifario']=$cargo_equivalente['tarifario_id'];
			$Salida['cargos'][$i]['cargo']=$cargo_equivalente['cargo'];
			$Salida['cargos'][$i]['descripcion']=$cargo_equivalente['descripcion'];
			$Salida['cargos'][$i]['precio']=$cargo_equivalente['precio'];
			$Salida['cargos'][$i]['destar']=$cargo_equivalente['destar'];
			$i++;
		}
	}
	$resultado->Close();
	return $Salida['cargos'];
}

function BuscarCargoEquivalenteHonorario($cargo_base)
{
	if(empty($cargo_base))
	{
		return false;
	}
	list($dbconn) = GetDBconn();
	$query = "SELECT a.tarifario_id,
			a.cargo,
			a.descripcion,
			a.precio,
			d.descripcion AS destar
			FROM tarifarios_detalle AS a,
			(SELECT tarifario_id,
			cargo
			FROM tarifarios_equivalencias
			WHERE cargo_base = '$cargo_base') AS c,
			tarifarios AS d
			WHERE c.tarifario_id = a.tarifario_id
			AND c.cargo = a.cargo
			AND c.tarifario_id = d.tarifario_id
			ORDER BY a.cargo DESC;";
	$resultado = $dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0)
	{
		die("SQL " . $dbconn->ErrorMsg());
		return false;
	}
	while(!$resultado->EOF)
	{
		$datos[]=$resultado->GetRowAssoc($ToUpper = false);
		$resultado->MoveNext();
	}
	return $datos;
}

?>
