<?php
/**
* Submodulo de GraficasSeguimientoReno_GraficasReno
*
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* $Id: hc_GraficasSeguimientoReno_GraficasReno.class.php,v 1.2 2007/02/01 20:48:35 luis Exp $
*/
class GraficasReno
{
	function GraficasReno()
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
						pyp_renoproteccion_conducta AS b
						WHERE a.evolucion_id=b.evolucion_id
						AND b.evolucion_id<=$evolucion
						AND b.inscripcion_id=$inscripcion";
							
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo GraficasSegumientoReno - GetDatosGraficas - SQL";
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
	
	
	function GetDatosGraficaCreatinina($evolucion,$inscripcion)
	{
		list($dbconn) = GetDBconn();
		
		$query="	SELECT a.resultado,TO_CHAR(b.fecha_registro,'MM-YY') as fecha
							FROM hc_apoyod_resultados_detalles AS a
							JOIN hc_resultados AS b
							ON
							(
								a.resultado_id = b.resultado_id
								AND a.cargo='903823'
							)
							JOIN hc_apoyod_lecturas_profesionales AS c 
							ON 
							(
								b.resultado_id = c.resultado_id
							)
							JOIN hc_evoluciones AS d
							ON
							(
								c.evolucion_id=d.evolucion_id
							)
							JOIN pyp_evoluciones_procesos AS e
							ON
							(
								d.evolucion_id=e.evolucion_id
							)
							JOIN pyp_inscripciones_pacientes AS f
							ON
							(
								e.inscripcion_id=f.inscripcion_id
							)
							WHERE e.evolucion_id<=$evolucion
							AND e.inscripcion_id=$inscripcion
						";
							
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo GraficasSegumientoReno - GetDatosGraficasCreatinina - SQL";
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