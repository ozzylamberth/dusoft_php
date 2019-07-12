<?php
/**
* Submodulo de GraficasSeguimientoCPN_Graficas
*
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* $Id: hc_GraficasSeguimientoCPN_Graficas.class.php,v 1.2 2007/02/01 20:55:43 luis Exp $
*/
class Graficas
{

	function Graficas()
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
		$dbconn->BeginTrans();
		
		$query="SELECT a.peso,a.tabaja,a.taalta,b.altura_uterina,b.semana_sugerida,b.semana_actual 
						FROM hc_signos_vitales_consultas AS a,
						pyp_cpn_conducta AS b
						WHERE a.evolucion_id=b.evolucion_id
						AND b.evolucion_id<=$evolucion
						AND b.inscripcion_id=$inscripcion";
							
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo GraficasSegumientoCPN - GetDatosGraficas - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
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
	
		$dbconn->CommitTrans();
		return $vars;
	}
	
	function ErrorDB()
	{
		$this->frmErrorBD=$this->error."<br>".$this->mensajeDeError;
		return $this->frmErrorBD;
	}

	/*esta funcion evita que afecte el cache en las graficas, asi que hay que tener cuidado
	* le adiciona al nombre una numeracion para asi visualizar el archivo.
	*/
	function AsignaNombreVirtual()
	{
		list($dbconn) = GetDBconn();
		$query="select nextval('asignanombrevirtualgraph_seq');";
		$resulta=$dbconn->execute($query);
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error al traer la secuencia";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $resulta->fields[0];
	}
	
}
?>
