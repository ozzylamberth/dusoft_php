<?php
	/**************************************************************************************
	* $Id: hc_EvolucionesNotas_HTML.php,v 1.2 2008/10/10 13:46:28 gerardo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.2 $ 	
	* @author Gerardo Amador Vidal
	*
	***************************************************************************************/
IncludeClass("ClaseHTML");

	class EvolucionesNotas_HTML extends EvolucionesNotas{
		
		function EvolucionesNotas_HTML(){
			$this->EvolucionesNotas();
			$this->cantMostrar=2;
			return true;
		}
		
		var $cantMostrar = 0;
		
		function GetForma(){
			$pfj = $this->frmPrefijo;
			$evento = $_REQUEST['accion'.$pfj];
			
			$impr = AutoCarga::factory('FormasEvolucionesNotasHTML','views','hc1','EvolucionesNotas');
			
			$obConn = AutoCarga::factory('EvolucionesNotasMetodos', '', 'hc1', 'EvolucionesNotas');
			
			if($evento == 'IngNotEvol'){
 				$request = $_REQUEST;
				
				$valInsNotEvol = $obConn->InsertarNotasEvoluc($request, $this->empresa_id, $this->usuario_id, $this->evolucion, $this->paciente, $this->tipoidpaciente, $this->ingreso);
				
 				if($valInsNotEvol)
 					$mensaje = "LA NOTA DE EVOLUCION HA SIDO INGRESADA EXITOSAMENTE !";
 				else
 					//$mensaje = "ERROR EN LA NOTA DE EVOLUCION !";
					$mensaje = $valInsNotEvol;
 				
 				$action['volver'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array());
				
 				return $this->salida = $impr->fmrMsjIngrNotaEvoluc($action, $mensaje);
				
 			}
			
			//$impr = AutoCarga::factory('FormasEvolucionesNotasHTML','views','hc1','EvolucionesNotas');
			
			$action['IngNotEvol'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>"IngNotEvol"));
			
			$datEmpre = $obConn->ConsulEntidad($this->empresa_id);
			$datUsuar = $obConn->ConsulProfesional($this->usuario_id);
			
			return $this->salida = $impr->FormaEvolucion($action, $datEmpre[0]['razon_social'], $datUsuar[0]['nombre'], $this->evolucion, $this->paciente, $this->tipoidpaciente, $this->ingreso);
			
		}
		
		
		/**
		*	Metodo con el que se consultan las las evolocuciones, para imprimirlas 
		*	en la Historia Clinica
		*/
		function GetReporte_Html(){
			$impr = AutoCarga::factory('FormasEvolucionesNotasHTML','views','hc1','EvolucionesNotas');
			
			return $impr->fmrNotasEvolucionHisto(false, $this->ingreso);
		}
		
		
		/**
		*	Metodo con el que se consultan las las evolocuciones
		*/
		function GetConsulta(){
			
			$impr = AutoCarga::factory('FormasEvolucionesNotasHTML','views','hc1','EvolucionesNotas');
			
			return $impr->fmrNotasEvolucion($this->evolucion);
		}
			
	}
?>