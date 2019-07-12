<?php
/**
* Submodulo de GraficasSeguimientoPFliar_GraficasPFliar
*
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* $Id: hc_GraficasSeguimientoPFliar_GraficasPFliar.class.php,v 1.2 2007/02/01 20:48:30 luis Exp $
*/
class GraficasPFliar
{
	function GraficasPFliar()
	{
		return true;
	}

	function GetConsulta()
	{
		if($this->frmConsulta()==false)
		{
			return true;
		}
		return $this->salida;
	}

	/**
	* Esta función retorna los datos de concernientes a la version del submodulo
	* @access private
	*/

	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'01/27/2005',
		'autor'=>'LUIS ALEJANDRO VARGAS',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}


	
	/**
	* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
	*
	* @access private
	* @return text Datos HTML de la pantalla.
	*/

	function GetEstado()
	{
		return true;
	}   
  
	function GetDatosGraficas($evolucion,$inscripcion)
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT a.peso,a.tabaja,a.taalta,TO_CHAR(b.fecha_registro,'MM-YY') as fecha
						FROM hc_signos_vitales_consultas AS a,
						pyp_plan_fliar_datos_evolucion AS b
						WHERE a.evolucion_id=b.evolucion_id
						AND b.evolucion_id<=$evolucion
						AND b.inscripcion_id=$inscripcion";
							
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo GraficasSegumientoPFliar - GetDatosGraficas - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		
		return $vars;
	}
	
	function ErrorDB()
	{
		$this->frmErrorBD=$this->error."<br>".$this->mensajeDeError;
		return $this->frmErrorBD;
	}
}
?>