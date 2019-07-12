<?php

/**
 * $Id: validacion_salida.inc.php,v 1.4 2005/06/07 19:03:54 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

	function RevisarSalidaPaciente($ingreso)
	{
			IncludeLib('funciones_facturacion');
			$resultado=array();
			$resultado['mensaje']='';

			//mira q el ingreso tenga todas las cuentas cerradas
			$arr = VerificarCuentasCerradas($ingreso);
			if(!empty($arr))
			{
					$resultado['mensaje']='NO SALIDA: ESTE INGRESO TIENE CUENTAS PENDIENTES POR FACTURAR: '.$arr;
					return $resultado;
			}

			//busca q no tenga pendientes por cargar
			$var = BuscarPendientesCargar($ingreso);
			if($var > 0)
			{
					$resultado['mensaje']='NO SALIDA: EXISTEN '.$var.' CARGOS PENDIENTES POR CARGAR A ESTE PACIENTE';
					return $resultado;
			}

			$hab = PendientesCamas($ingreso);
			if(!empty($hab))
			{
					$resultado['mensaje']='NO SALIDA: ';
					return $resultado;
			}

			//busca si hay OS pendientes
			$os = OrdenesPendientes($ingreso);
			if(!empty($os))
			{
					$resultado['mensaje']='NO SALIDA: ';
					return $resultado;
			}

			//si pasa por todas estas validaciones se actualiza en ingreso
			/*list($dbconn) = GetDBconn();
			$query = "UPDATE ingresos SET estado='2',fecha_cierre='now()'
								WHERE ingreso=$ingreso";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$resultado['mensaje']='ERROR EN ACTUALIZAR EL INGRESO'. $dbconn->ErrorMsg();
					return $resultado;
			}*/

			//si retorna un arreglo es q no paso la validacon
			return true;
	}

	function VerificarCuentasCerradas($ingreso)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT numerodecuenta FROM cuentas
								WHERE ingreso=$ingreso AND estado not in('0','3')";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			$vars='';
			while(!$result->EOF)
			{
					$vars .= $result->fields[0]. ' - ';
					$result->MoveNext();
			}

			$result->Close();
			return $vars;
	}

	function OrdenesPendientes($ingreso)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT
								FROM cuentas as a, os_maestro as b
								WHERE a.ingreso=$ingreso AND a.numerodecuenta=b.numerodecuenta
								AND";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			$result->Close();
			return ;
	}

	function PendientesCamas($ingreso)
	{

	}

//------------------------------------------------------------------------------------

?>
