<?php

/**
 * $Id: funciones_pagares.inc.php,v 1.11 2005/10/03 16:24:44 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */


	function AbonosPagares($empresa,$prefijo,$numeropagare)
	{ 
		if(empty($empresa) || empty($prefijo) || empty($numeropagare))
		{
			return false;
		}
	//
		$salida=array();
		list($dbconn) = GetDBconn();
		$query = "SELECT SUM(B.total_abono) AS abono
									FROM rc_detalle_pagare A, recibos_caja B, pagares C
									WHERE A.empresa_id='$empresa'
												AND A.prefijo='$prefijo'
												AND A.numero=$numeropagare
												AND A.recibo_caja=B.recibo_caja
												AND A.empresa_id=B.empresa_id
												AND A.centro_utilidad=B.centro_utilidad
												AND A.recibo_caja=B.recibo_caja
												AND A.prefijo=B.prefijo
												AND A.empresa_id=C.empresa_id
												AND A.prefijo=C.prefijo
												AND A.numero=C.numero";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$abono = $result->fields[0];
				$result->Close();
				return $abono;
	//
	}


	function BuscarPagaresPaciente($tipo,$id)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT b.prefijo, b.numero, b.fecha_registro, b.empresa_id,
								d.descripcion as formapago, b.vencimiento, b.valor
								FROM pagares b, tipos_formas_pago d, cuentas as e, ingresos as f
								WHERE b.sw_estado='1' and b.tipo_forma_pago_id=d.tipo_forma_pago_id
								and b.numerodecuenta=e.numerodecuenta and e.ingreso=f.ingreso
								and f.paciente_id='$id' and f.tipo_id_paciente='$tipo'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			$result->Close();
			return $var;			
	}

	
	function BuscarDatosPagares($empresa,$prefijo,$numero)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT b.prefijo, b.numero, b.fecha_registro, b.empresa_id, b.numerodecuenta, b.observacion,
								d.descripcion as formapago, b.vencimiento, b.valor,
								g.primer_nombre||' '||g.segundo_nombre||' '||g.primer_apellido||' '||g.segundo_apellido as paciente,
								g.tipo_id_paciente, g.paciente_id, g.residencia_telefono, g.residencia_direccion,
								i.direccion as direccion_trabajo, i.telefono as telefono_trabajo
								FROM pagares b, tipos_formas_pago d, cuentas as e, 
								ingresos as f LEFT JOIN ingresos_empleadores as h on(f.ingreso=h.ingreso)
								LEFT JOIN empleadores as i on(h.tipo_id_empleador=i.tipo_id_empleador and h.empleador_id=i.empleador_id),
								pacientes as g
								WHERE  b.empresa_id='$empresa' and b.prefijo='$prefijo'
								and b.numero=$numero
								and b.tipo_forma_pago_id=d.tipo_forma_pago_id
								and b.numerodecuenta=e.numerodecuenta and e.ingreso=f.ingreso
								and f.paciente_id=g.paciente_id and f.tipo_id_paciente=g.tipo_id_paciente";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$var=$result->GetRowAssoc($ToUpper = false);
			$result->Close();
			return $var;			
	}	
	
	function BuscarResponsablesPagare($empresa,$prefijo,$numero)
	{
			list($dbconn) = GetDBconn();
/*
			$query = "SELECT b.tipo_id_tercero, b.tercero_id, c.nombre_tercero, c.telefono, c.direccion
								FROM pagares_responsables b, terceros c
								WHERE b.prefijo='$prefijo' and b.numero=$numero and b.empresa_id='$empresa'
								and c.tipo_id_tercero=b.tipo_id_tercero and c.tercero_id=b.tercero_id";
*/			
			$query = "SELECT b.tipo_id_tercero, b.tercero_id, b.nombre, b.telefono_residencia, b.direccion_residencia,
								b.direccion_trabajo, b.telefono_trabajo
								FROM pagares_responsables b
								WHERE b.prefijo='$prefijo' and b.numero=$numero and b.empresa_id='$empresa'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			$result->Close();
			return $var;			
	}	

?>
