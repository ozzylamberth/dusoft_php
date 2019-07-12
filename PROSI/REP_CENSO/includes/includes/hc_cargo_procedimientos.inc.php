<?php

/**
 * $Id: hc_cargo_procedimientos.inc.php,v 1.2 2005/06/07 19:03:54 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

// malla_validadora.inc.php  05/07/2004
// ----------------------------------------------------------------------

function ValidarCargoProcedimiento($cuenta,$cargo_base,$plan_id,$Servicio,$cantidad=1,$autorizacion_int=0,$autorizacion_ext=0)
{

	if(empty($cuenta) || empty($cargo_base) || empty($plan_id) || empty($Servicio)){
		return "DATOS INCOMPLETOS PARA LA VALIDACION EN LA MALLA.";
    }

    list($dbconn) = GetDBconn();
    global $ADODB_FETCH_MODE;

    $query = "SELECT estado FROM cuentas WHERE numerodecuenta=$cuenta";

    $resultado = $dbconn->Execute($query);

	if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

	if($resultado->EOF){
		return "LA CUENTA $cuenta NO ES VALIDA";
	}

	list($estado_cuenta)=$resultado->FetchRow();
    $resultado->Close();

	if( ($estado_cuenta<>1) && ($estado_cuenta<>2) ){
        return "LA CUENTA $cuenta NO ESTA EN UN ESTADO VALIDO PARA AGREGAR NUEVOS CARGOS";
	}

    $query = "SELECT a.tarifario_id, a.cargo

            FROM tarifarios_detalle AS a, plan_tarifario AS b,
            (SELECT tarifario_id,cargo FROM tarifarios_equivalencias
            WHERE cargo_base='$cargo_base') AS c

            WHERE b.plan_id = $plan_id
            AND b.tarifario_id = a.tarifario_id
            AND b.grupo_tarifario_id = a.grupo_tarifario_id
            AND b.subgrupo_tarifario_id = a.subgrupo_tarifario_id
            AND c.tarifario_id =  a.tarifario_id
            AND c.cargo = a.cargo";



    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    $NumeroCargos=$resultado->RecordCount();
    if($NumeroCargos == 0){
        $resultado->Close();
        return "EL CARGO $cargo_base NO TIENE EQUIVALENCIAS O NO ESTA CONTRATADO";
    }
    
    if($NumeroCargos > 1){
        $resultado->Close();
        return "EL CARGO $cargo_base TIENE $NumeroCargos EQUIVALENCIAS";
    }

    list($tarifario,$cargo)=$resultado->FetchRow();
    $resultado->Close();

    $query = "
                SELECT b.sw_no_contratado, b.por_cobertura
                FROM tarifarios_detalle a, excepciones b
                WHERE

                b.plan_id = $plan_id AND
                a.tarifario_id = '$tarifario' AND
                a.cargo = '$cargo' AND
                b.tarifario_id = a.tarifario_id AND
                b.cargo = a.cargo ";

    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    if(!$resultado->EOF){

        list($sw_no_contratado,$cobertura)=$resultado->FetchRow();
        $resultado->Close();

        if($sw_no_contratado){
            return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario ESTA MARCADO COMO NO CONTRATADO EN LA TABLA DE EXCEPCIONES.";
        }

        if($cobertura != 100 && !$autorizacion_int){
            return "SE REQUIERE AUTORIZACION INTERNA PORQUE EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario SOLO TIENE COBERTURA DEL ".round($cobertura,2)." % EN LA TABLA DE EXCEPCIONES.";
        }

    }else{

        $resultado->Close();
        $query = "
                    SELECT b.por_cobertura
                    FROM tarifarios_detalle a, plan_tarifario b
                    WHERE    
                    b.plan_id = $plan_id and
                    a.tarifario_id = '$tarifario' AND
                    a.cargo = '$cargo' AND
                    b.grupo_tarifario_id = a.grupo_tarifario_id AND
                    b.subgrupo_tarifario_id = a.subgrupo_tarifario_id AND
                    b.tarifario_id = a.tarifario_id ";

        $resultado = $dbconn->Execute($query);
    
        if ($dbconn->ErrorNo() != 0) {
            return "SQL " . $dbconn->ErrorMsg();
        }

        if($resultado->EOF){
            return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario NO ESTA INCLUIDO EN EL PLAN TARIFARIO.";
        }

        list($cobertura)=$resultado->FetchRow();
        $resultado->Close();

        if($cobertura != 100 && !$autorizacion_int){
            return "SE REQUIERE AUTORIZACION INTERNA PORQUE EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario SOLO TIENE COBERTURA DEL ".round($cobertura,2)." % EN EL PLAN TARIFARIO.";
        }
    }
    if(!$autorizacion_int){
		$query = "SELECT count(*)
					FROM excepciones_aut_int
					WHERE plan_id = $plan_id AND
					tarifario_id = '$tarifario' AND
					cargo = '$cargo' AND
					servicio = $Servicio AND
					sw_autorizado=0";

		$resultado = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			return "SQL " . $dbconn->ErrorMsg();
		}

		list($NumeroCargos)=$resultado->FetchRow();
		$resultado->Close();

		if($NumeroCargos != 0){
			return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario REQUIERE AUTORIZACION INTERNA (EXCEPCION) EN EL SERVICIO $Servicio";
		}

		$query = "SELECT count(*)
					FROM tarifarios_detalle as a, planes_autorizaciones_int as b
					WHERE b.plan_id = $plan_id AND
					a.tarifario_id = '$tarifario' AND
					a.cargo = '$cargo' AND
					b.servicio = $Servicio AND
					a.grupo_tipo_cargo = b.grupo_tipo_cargo AND
					a.tipo_cargo=b.tipo_cargo AND
					a.nivel = b.nivel";

		$resultado = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			return "SQL " . $dbconn->ErrorMsg();
		}

		list($NumeroCargos)=$resultado->FetchRow();
		$resultado->Close();

		if($NumeroCargos != 0){
			return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario REQUIERE AUTORIZACION INTERNA (GRUPO)";
		}
	}


    if(!$autorizacion_ext){
				$query = "SELECT count(*)
							FROM excepciones_aut_ext
							WHERE plan_id = $plan_id AND
							tarifario_id = '$tarifario' AND
							cargo = '$cargo' AND
							servicio = $Servicio AND
							sw_autorizado=0";

				$resultado = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					return "SQL " . $dbconn->ErrorMsg();
				}

				list($NumeroCargos)=$resultado->FetchRow();
				$resultado->Close();

				if($NumeroCargos != 0){
					return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario REQUIERE AUTORIZACION EXTERNA (EXCEPCION)";
				}

				$query = "SELECT count(*)
							FROM tarifarios_detalle as a, planes_autorizaciones_ext as b
							WHERE b.plan_id = $plan_id AND
							a.tarifario_id = '$tarifario' AND
							a.cargo = '$cargo' AND
							b.servicio = $Servicio AND
							a.grupo_tipo_cargo = b.grupo_tipo_cargo AND
							a.tipo_cargo=b.tipo_cargo AND
							a.nivel = b.nivel";

				$resultado = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					return "SQL " . $dbconn->ErrorMsg();
				}

				list($NumeroCargos)=$resultado->FetchRow();
				$resultado->Close();

				if($NumeroCargos != 0){
					return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario REQUIERE AUTORIZACION EXTERNA (GRUPO)";
				}
    }

		IncludeLib('tarifario_cargos');
		$cargo_liq=LiquidarCargoCuenta($cuenta ,$tarifario ,$cargo);
		if(!is_array($cargo_liq))
		{
				return "NO SE PUDO LIQUIDAR EL CARGO PARA AGREGARLO A LA CUENTA";
		}

    return $cargo_liq;
}

function CargarProcedimiento($cuenta, $cargo_liquidado, $cargo_cups, $departamento, $servicio)
{
    if(empty($cuenta) || !is_array($cargo_liquidado)){
        return false;
    }

		list($dbconn) = GetDBconn();
		$query="SELECT empresa_id, centro_utilidad FROM cuentas WHERE numerodecuenta=$cuenta";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Guardar en la Base de Datos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}

		$query="SELECT nextval('cuentas_detalle_transaccion_seq')";
		$result=$dbconn->Execute($query);
		$Transaccion=$result->fields[0];

		$query = "select b.codigo_agrupamiento_id
							from cups as a, grupos_tipos_cargo as b
							where a.cargo='$cargo_cups' and a.grupo_tipo_cargo=b.grupo_tipo_cargo
							and b.codigo_agrupamiento_id is not NULL";
		$result=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if(!$result->EOF)
		{  $codigo=$agru[codigo_agrupamiento_id];  }
		else
		{  $codigo='NULL';  }

		$user=UserGetUID();

		$query = "INSERT INTO cuentas_detalle (
																	transaccion,
																	empresa_id,
																	centro_utilidad,
																	numerodecuenta,
																	departamento,
																	tarifario_id,
																	cargo,
																	cantidad,
																	precio,
																	valor_cargo,
																	valor_nocubierto,
																	valor_cubierto,
																	usuario_id,
																	facturado,
																	fecha_cargo,
																	valor_descuento_empresa,
																	valor_descuento_paciente,
																	servicio_cargo,
																	autorizacion_int,
																	autorizacion_ext,
																	porcentaje_gravamen,
																	sw_cuota_paciente,
																	sw_cuota_moderadora,
																	codigo_agrupamiento_id,
																	fecha_registro,
																	cargo_cups)
							VALUES ($Transaccion,'".$resulta->fields[0]."','".$resulta->fields[1]."',$cuenta,'$departamento','$cargo_liquidado[tarifario_id]','$cargo_liquidado[cargo]',$cargo_liquidado[cantidad],$cargo_liquidado[precio_plan],$cargo_liquidado[valor_cargo],$cargo_liquidado[valor_no_cubierto],$cargo_liquidado[valor_cubierto],$user,$cargo_liquidado[facturado],'now()',$cargo_liquidado[valor_descuento_paciente],$cargo_liquidado[valor_descuento_empresa],$servicio,1,NULL,".$cargo_liquidado[porcentaje_gravamen].",'".$cargo_liquidado[sw_cuota_paciente]."','".$cargo_liquidado[sw_cuota_moderadora]."',$codigo,'now()','$cargo_cups')";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
				die($this->error = "Error al 4Guardar en la Base de Datos");
		}

	 return $Transaccion;
}

function DescargarProcedimiento($transaccion)
{
		list($dbconn) = GetDBconn();
		$query = "DELETE FROM cuentas_detalle WHERE transaccion=$transaccion";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al 4Guardar en la Base de Datos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}

		return true;
}
?>
