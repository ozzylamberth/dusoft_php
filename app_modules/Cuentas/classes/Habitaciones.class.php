<?php
  /******************************************************************************
  * $Id: Habitaciones.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.7 $ 
	* 
	* @autor
  ********************************************************************************/
  IncludeClass('HabitacionesHTML','','app','Cuentas');
  IncludeLib("funciones_facturacion");
  
	class Habitaciones
	{
		function Habitaciones(){}
		/**
		***LlamaValidarEgresoPaciente
		**/
		//Funciones para la liquidacion de habitaciones
      
		function ValidarEgresoPaciente($Ingreso)
		{
			list($dbconn) = GetDBconn();        
			$query = "SELECT count(*)
			FROM hc_ordenes_medicas a , hc_vistosok_salida_detalle b 
			WHERE a.ingreso=$Ingreso AND a.sw_estado IN ('0','1')
			AND a.hc_tipo_orden_medica_id IN ('99','06','07')
			AND a.ingreso = b.ingreso
			AND b.visto_id='01'
			AND a.evolucion_id=b.evolucion_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			$result->Close();
			return $result->fields[0];
			
		}
		
		function ValidarCuentaCorte($cuenta)
		{
			list($dbconn) = GetDBconn();        
			$query = "SELECT numerodecuenta_corte
 										FROM  movimientos_habitacion 
										WHERE numerodecuenta = $cuenta";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$result->Close();
			if($result->fields[0])
					return true;
			else
					return false;
		}		//fin funciones  
	}
?>