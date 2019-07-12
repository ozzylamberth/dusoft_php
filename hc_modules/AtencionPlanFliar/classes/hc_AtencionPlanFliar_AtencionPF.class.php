<?php

/**
* Submodulo de AtencionReno
* $Id: hc_AtencionPlanFliar_AtencionPF.class.php,v 1.2 2007/02/01 20:44:02 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
*/

class AtencionPF
{
	function AtencionPF()
	{
		return true;
	}
	
	function ObtenerInscripcion($evolucion)
	{
	
		list($dbconn) = GetDBconn();

		$query="SELECT inscripcion_id
						FROM pyp_evoluciones_procesos
						WHERE evolucion_id=$evolucion";
							
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo AtencionPF - ObtenerInscripcion - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
			
		$inscripcion=$result->fields[0];
		
		return $inscripcion;
	}
	
	function ErrorDB()
	{
		$this->frmErrorBD=$this->error."<br>".$this->mensajeDeError;
		return $this->frmErrorBD;
	}
	
	
}
?>