<?php

/**
* Submodulo de Antecedentes Ginecobstetricos.
*
* Submodulo para manejar los antecedentes ginecobstetricos de un paciente en una evolucion y las diferentes
* evoluciones que se necesiten.
* @author Luis Alejandro Vargas
* @version 1.0
* @package SIIS
* $Id: hc_AntecedentesGinecoObstetricos_AntecedentesGO.class.php,v 1.6 2007/02/06 14:17:06 luis Exp $
*/


/**
* AntecedentesGinecoObstetricos
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de antecedentes ginecobstetricos.
*/

class AntecedentesGO
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function AntecedentesGO()
	{
		return true;
	}
	
	function ConsultaAntecedentesPyp()
	{
		$sql = "SELECT max(sw_cpn)
						FROM hc_tipos_antecedentes_detg";
						
		if(!$rst = $this->ConexionBaseDatos($sql))	return false;
		
		$valor=$rst->fields[0];
		$antecedentes = array();
		
		for($i=1;$i<=$valor;$i++)
		{
			$sql = "SELECT hc_tipo_antecedente_gineco_id as htg,hc_tipo_antecedente_detg_id as htd
							FROM hc_tipos_antecedentes_detg
							WHERE sw_cpn=$i";
			
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
			while(!$rst->EOF)
			{
				$antecedentes[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
		}
		
		return $antecedentes;
	}
	
	/**
	* Esta función inserta los datos del submodulo.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/
	
	function InsertDatos($evolucion,$param)
	{
		$sql = "INSERT INTO hc_antecedentes_ginecos
							(
								detalle,
								hc_tipo_antecedente_gineco_id,
								evolucion_id,
								destacar,
								hc_tipo_antecedente_detg_id,
								sw_riesgo,
								fecha_registro
							)
						VALUES(
								'".$param[6]."', 
								 ".$param[1].", 
								 ".$evolucion.", 
								'".$param[7]."', 
								 ".$param[0].",
								'".$param[5]."',
								NOW()); ";
		if(!$rst = $this->ConexionBaseDatos($sql))	return false;
		
		return true;
	}
	
	function InsertDatosPyp($evolucion,$param)
	{
		$sql = "INSERT INTO pyp_cpn_antecedentes_ginecos
							(	detalle,
								hc_tipo_antecedente_gineco_id,
								evolucion_id,
								destacar,
								pyp_cpn_antecedente_id,
								sw_riesgo,
								fecha_registro
							)
							VALUES
							(
								'".$param[11]."', 
								".$param[0].", 
								".$evolucion.", 
								'".$param[12]."', 
								".$param[1].",
								'".$param[10]."',
								NOW()
							); ";
		
								
		if(!$rst = $this->ConexionBaseDatos($sql))	return false;
		
		return true;
	}
	

	function UpdateDatos($param)
	{	
		$sql .= "UPDATE hc_antecedentes_ginecos ";
		$sql .= "SET		ocultar = '".$param[6]."' ";
		$sql .= "WHERE	hc_tipo_antecedente_detg_id = ".$param[0]." ";
		$sql .= "AND		hc_tipo_antecedente_gineco_id = ".$param[1]." ";
		$sql .= "AND		hc_antecedente_gineco_id = ".$param[5]." ";
		
		if(!$rst = $this->ConexionBaseDatos($sql))	return false;
		
		return true;
	}
	
	function UpdateDatosPyp($param)
	{
		
		$sql .= "UPDATE pyp_cpn_antecedentes_ginecos ";
		$sql .= "SET	sw_riesgo = '".$param[10]."', ";
		$sql .= "detalle = '".$param[11]."', ";
		$sql .= "destacar = '".$param[12]."' ";
		$sql .= "WHERE	pyp_cpn_antecedente_id = ".$param[1]." ";
		$sql .= "AND		hc_tipo_antecedente_gineco_id = ".$param[0]." ";
		$sql .= "AND		pyp_cpn_antecedente_gineco_id = ".$param[2]." ";
		
		if(!$rst = $this->ConexionBaseDatos($sql))	return false;
		
		return true;
	}

//OJO CAMBIO EN QUERY
	function BusquedaAntecedentes($evolucion)
	{
		$datosPaciente=SessionGetVar("DatosPaciente");
		
		if(!$datosPaciente)
			$datosPaciente=$this->datosPaciente;
		
		$sql = "SELECT	d.nombre_tipo, 
										d.riesgo, 
										c.detalle, 
										c.destacar,
										a.evolucion_id, 
										d.hc_tipo_antecedente_gineco_id AS hctad,
										d.hc_tipo_antecedente_detg_id AS hctap, 
										e.descripcion,
										COALESCE(c.ocultar,'0') AS ocultar, 
										hc_antecedente_gineco_id AS hcid,
										c.sw_riesgo,
										TO_CHAR(c.fecha_registro,'YYYY-MM-DD') AS fecha,
										e.sexo,
										e.edad_min, 
										e.edad_max
										--d.sw_dif as gpac
						FROM 		hc_evoluciones AS a JOIN ingresos AS b 
										ON(	a.evolucion_id <=".$evolucion." AND 
												a.ingreso=b.ingreso AND 
												b.paciente_id='".$datosPaciente['paciente_id']."' AND 
												b.tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."')
										JOIN hc_antecedentes_ginecos AS c 
										ON(	a.evolucion_id=c.evolucion_id)
										RIGHT JOIN hc_tipos_antecedentes_detg AS d 
										ON(	c.hc_tipo_antecedente_detg_id=d.hc_tipo_antecedente_detg_id AND 
												c.hc_tipo_antecedente_gineco_id=d.hc_tipo_antecedente_gineco_id)
										RIGHT JOIN hc_tipos_antecedentes_ginecos AS e 
										ON(	d.hc_tipo_antecedente_gineco_id=e.hc_tipo_antecedente_gineco_id)
						--WHERE 	d.sw_activo=1
						ORDER BY d.hc_tipo_antecedente_gineco_id, d.hc_tipo_antecedente_detg_id;";

		if(!$rst = $this->ConexionBaseDatos($sql))	return false;
	
		$antecedentes = array();
		while(!$rst->EOF)
		{
			$antecedentes[$rst->fields[7]][$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
		}
			
		return $antecedentes;
	}
	
		function BusquedaAntecedentesCPN($evolucion)
		{
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			$query="SELECT d.nombre_tipo, 
											c.detalle, 
											c.destacar,
											a.evolucion_id, 
											d.hc_tipo_antecedente_gineco_id AS hctag,
											d.pyp_cpn_antecedente_id AS pypan, 
											f.descripcion, 
											f.sexo,
											f.edad_min, 
											f.edad_max, 
											c.sw_riesgo, 
											TO_CHAR(c.fecha_registro,'YYYY-MM-DD') AS fecha,
											COALESCE(c.ocultar,'0') AS ocultar,
											c.pyp_cpn_antecedente_gineco_id as pypang,
											d.puntaje_asociado,
											g.pyp_cpn_grupo_antecedente_id AS pypid,
											g.valor_min,
											g.valor_max,
											g.puntaje_asociado,
											d.sw_calculado
							FROM 		hc_evoluciones as a JOIN ingresos as b 
											on(	a.evolucion_id<=".$evolucion." and 
													a.ingreso=b.ingreso and b.paciente_id='".$datosPaciente['paciente_id']."' and 
													b.tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."')
											JOIN pyp_cpn_antecedentes_ginecos as c 
											on(	a.evolucion_id = c.evolucion_id )
											RIGHT JOIN pyp_cpn_antecedentes as d 
											on(	c.pyp_cpn_antecedente_id = d.pyp_cpn_antecedente_id and 
													c.hc_tipo_antecedente_gineco_id = d.hc_tipo_antecedente_gineco_id)
											LEFT JOIN pyp_cpn_grupos_antecedentes as g 
											on(	g.pyp_cpn_antecedente_id = d.pyp_cpn_antecedente_id and 
													g.hc_tipo_antecedente_gineco_id = d.hc_tipo_antecedente_gineco_id)
											RIGHT JOIN hc_tipos_antecedentes_ginecos as f 
											on(	d.hc_tipo_antecedente_gineco_id = f.hc_tipo_antecedente_gineco_id)
							order by d.hc_tipo_antecedente_gineco_id, d.pyp_cpn_antecedente_id;";
				
			if(!$rst = $this->ConexionBaseDatos($query))	return false;
				
			$antecedentes = array();
			while(!$rst->EOF)
			{
				$antecedentes[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
				
			return $antecedentes;
		}
		
		function DatosInscripcionPacientes($inscripcion)
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
	
			$query="SELECT *
							FROM pyp_inscripcion_cpn as a
							JOIN pyp_inscripciones_pacientes as b 
							ON
							(
								a.inscripcion_id=b.inscripcion_id 
								AND a.inscripcion_id=$inscripcion
							)";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo AntecedentesGinecosOstetricos- DatosInscripcionPacientes";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				while(!$result->EOF)
				{
					$vars[] = $result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
				
			$dbconn->CommitTrans();
			return $vars;
		}
		
		
		function ObtenerPuntajeAsociado($evolucion,$inscripcion)
		{
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
	
			$query="SELECT sum(d.puntaje_asociado)
							FROM pyp_inscripciones_pacientes as pypins,hc_evoluciones as a
							
							JOIN ingresos as b 
							ON(a.evolucion_id<=".$evolucion." and 
							a.ingreso=b.ingreso and b.paciente_id='".$datosPaciente['paciente_id']."' AND 
							b.tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."')
							
							JOIN pyp_cpn_antecedentes_ginecos as c 
							ON(a.evolucion_id=c.evolucion_id)
							
							RIGHT JOIN pyp_cpn_antecedentes as d 
							ON(c.pyp_cpn_antecedente_id=d.pyp_cpn_antecedente_id AND 
							c.hc_tipo_antecedente_gineco_id=d.hc_tipo_antecedente_gineco_id)
							
							RIGHT JOIN hc_tipos_antecedentes_ginecos as e 
							ON(d.hc_tipo_antecedente_gineco_id=e.hc_tipo_antecedente_gineco_id) 
							WHERE d.puntaje_asociado is not null
							AND pypins.inscripcion_id=$inscripcion
							AND c.sw_riesgo='1'";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo AntecedentesGinecosObstetricos - ObtenerPuntajeAsociado - SQL ";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				while (!$result->EOF)
				{
					if(!empty($result->fields[0]))
					{
						$puntaje=$result->fields[0];
					}
					$result->MoveNext();
				}
			}
			$dbconn->CommitTrans();
			return $puntaje;
		}
	
		function ErrorDB()
		{
			$this->frmErrorBD=$this->error."<br>".$this->mensajeDeError;
			return $this->frmErrorBD;
		}
		
		function BusquedaAntecedentesIndividual($evolucion,$hctap,$hctad)
		{
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			if(!$datosPaciente)
				$datosPaciente=$this->datosPaciente;
			
			$sql = "SELECT 	d.nombre_tipo, 
											d.riesgo, 
											c.detalle, 
											c.destacar,
											a.evolucion_id, 
											d.hc_tipo_antecedente_gineco_id AS hctad,
											d.hc_tipo_antecedente_detg_id AS hctap, 
											e.descripcion,
											COALESCE(c.ocultar,'0') AS ocultar, 
											hc_antecedente_gineco_id AS hcid,
											c.sw_riesgo,
											TO_CHAR(c.fecha_registro,'YYYY-MM-DD') AS fecha,
											e.sexo,
											e.edad_min, 
											e.edad_max
											--d.sw_dif as gpac
							FROM 		hc_evoluciones AS a JOIN ingresos AS b 
											ON(	a.evolucion_id <=".$evolucion." AND 
													a.ingreso=b.ingreso AND 
													b.paciente_id='".$datosPaciente['paciente_id']."' AND 
													b.tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."')
											JOIN hc_antecedentes_ginecos AS c 
											ON(	a.evolucion_id=c.evolucion_id)
											RIGHT JOIN hc_tipos_antecedentes_detg AS d 
											ON(	c.hc_tipo_antecedente_detg_id=d.hc_tipo_antecedente_detg_id AND 
													c.hc_tipo_antecedente_gineco_id=d.hc_tipo_antecedente_gineco_id)
											RIGHT JOIN hc_tipos_antecedentes_ginecos AS e 
											ON(	d.hc_tipo_antecedente_gineco_id=e.hc_tipo_antecedente_gineco_id)
							WHERE		d.hc_tipo_antecedente_detg_id = ".$hctap." 
							AND			d.hc_tipo_antecedente_gineco_id = ".$hctad."
							-- AND 		d.sw_activo=1
							ORDER BY d.hc_tipo_antecedente_gineco_id, d.hc_tipo_antecedente_detg_id;";
	
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
				
			$antecedentes = array();
			while(!$rst->EOF)
			{
				$antecedentes[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
				
			return $antecedentes;
		}
		
		function BusquedaAntecedentesIndividualPyp($evolucion,$hctap,$hctad)
		{
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			$sql = "SELECT	d.nombre_tipo, 
											c.detalle, 
											c.destacar,
											a.evolucion_id, 
											d.hc_tipo_antecedente_gineco_id AS hctag,
											d.pyp_cpn_antecedente_id AS pypan, 
											e.descripcion, 
											e.sexo,
											e.edad_min, 
											e.edad_max, 
											c.sw_riesgo, 
											TO_CHAR(c.fecha_registro,'YYYY-MM-DD') AS fecha,
											COALESCE(c.ocultar,'0') AS ocultar,
											COALESCE(d.puntaje_asociado,-1) AS puntaje_asociado,
											pyp_cpn_antecedente_gineco_id AS pypang
							FROM 		hc_evoluciones AS a JOIN ingresos AS b 
											ON(	a.evolucion_id <=".$evolucion." AND 
													a.ingreso=b.ingreso AND 
													b.paciente_id='".$datosPaciente['paciente_id']."' AND 
													b.tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."')
											JOIN pyp_cpn_antecedentes_ginecos AS c 
											ON(	a.evolucion_id=c.evolucion_id)
											RIGHT JOIN pyp_cpn_antecedentes AS d 
											ON(	c.pyp_cpn_antecedente_id=d.pyp_cpn_antecedente_id AND 
													c.hc_tipo_antecedente_gineco_id=d.hc_tipo_antecedente_gineco_id)
											RIGHT JOIN hc_tipos_antecedentes_ginecos AS e 
											ON(	d.hc_tipo_antecedente_gineco_id=e.hc_tipo_antecedente_gineco_id)
							WHERE		d.pyp_cpn_antecedente_id = ".$hctap." 
							AND			d.hc_tipo_antecedente_gineco_id = ".$hctad."
							ORDER BY d.hc_tipo_antecedente_gineco_id, d.pyp_cpn_antecedente_id;";

			if(!$rst = $this->ConexionBaseDatos($sql.$where))	return false;
				
			$antecedentes = array();
			while(!$rst->EOF)
			{
				$antecedentes[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
				
			return $antecedentes;
		}
		
		function BusquedaAntecedentesConsulta()
		{
			
			$evolucion=SessionGetVar("Evolucion");
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			list($dbconn) = GetDBconn();
			unset($query);
			$query = "select d.nombre_tipo, d.riesgo, c.detalle, c.destacar,
							a.evolucion_id, d.hc_tipo_antecedente_gineco_id,
							d.hc_tipo_antecedente_detg_id, e.descripcion, c.ocultar,
							c.hc_antecedente_gineco_id, c.sw_riesgo, c.fecha_registro
						from hc_evoluciones as a
						join ingresos as b on(a.evolucion_id<=".$evolucion." and a.ingreso=b.ingreso and b.paciente_id='".$datosPaciente['paciente_id']."' and b.tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."')
						join hc_antecedentes_ginecos as c on(a.evolucion_id=c.evolucion_id)
						right join hc_tipos_antecedentes_detg as d on(c.hc_tipo_antecedente_detg_id=d.hc_tipo_antecedente_detg_id and c.hc_tipo_antecedente_gineco_id=d.hc_tipo_antecedente_gineco_id)
						right join hc_tipos_antecedentes_ginecos as e on(d.hc_tipo_antecedente_gineco_id=e.hc_tipo_antecedente_gineco_id)
						order by d.hc_tipo_antecedente_gineco_id, d.hc_tipo_antecedente_detg_id;";
			$result = $dbconn->Execute($query);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo AntecedentesGinecosObstetricos - BusquedaAntecedentesConsulta";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				while (!$result->EOF)
				{
					if(!empty($result->fields[2]))
					{
						$tipo_ant[0][$i]=$result->fields[0];
						$tipo_ant[1][$i]=$result->fields[1];
						$tipo_ant[2][$i]=$result->fields[2];
						$tipo_ant[3][$i]=$result->fields[3];
						$tipo_ant[4][$i]=$result->fields[4];
						$tipo_ant[5][$i]=$result->fields[5];
						$tipo_ant[6][$i]=$result->fields[6];
						$tipo_ant[7][$i]=$result->fields[7];
						$tipo_ant[8][$i]=$result->fields[8];
						$tipo_ant[9][$i]=$result->fields[9];
						$tipo_ant[10][$i]=$result->fields[10];
						$tipo_ant[11][$i]=$result->fields[11];
						$i++;
					}
					$result->MoveNext();
				}
			}
			return $tipo_ant;
		}
		
		function GetDatosHistorialEmbarazos($evolucion)
		{
			list($dbconn) = GetDBconn();
			
			$datosPaciente=SessionGetVar("DatosPaciente");
	
			$query="SELECT *
							FROM  hc_evoluciones AS a 
							JOIN ingresos AS b 
							ON
							(	
								a.evolucion_id <=".$evolucion." 
								AND a.ingreso=b.ingreso 
								AND b.paciente_id='".$datosPaciente['paciente_id']."' 
								AND b.tipo_id_paciente='".$datosPaciente['tipo_id_paciente']."'
							)
							JOIN pyp_plan_fliar_historial_embarazos AS c 
							ON
							(	
								a.evolucion_id=c.evolucion_id
							)";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo AntecedentesGinecosOstetricos - GetDatosHistorialEmbarazos - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				while(!$result->EOF)
				{
					$vars[] = $result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			
			return $vars;
		}
		
		function GuardarHistorialAntecedentesPF($evolucion,$datos)
		{
			list($dbconn) = GetDBconn();
	
			$query="INSERT INTO pyp_plan_fliar_historial_embarazos
							(
								evolucion_id,
								numero_hijo,
								año_terminacion,
								meses_gestacion,
								tipo_parto,
								estado_nacimiento
							)
							VALUES
							(
								$evolucion,
								".$datos[0].",
								".$datos[1].",
								".$datos[2].",
								".$datos[3].",
								'".$datos[4]."'
							)";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo AntecedentesGinecosOstetricos - GuardarHistorialAntecedentesPF - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			return true;
		}
		
		
		function GuardarRecibioConsegeria($inscripcion)
		{
			list($dbconn) = GetDBconn();
			
			$query="UPDATE pyp_inscripcion_planificacion_fliar
							SET recibio_consegeria='1'
							WHERE inscripcion_id=$inscripcion";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo AntecedentesGinecosOstetricos - GuardarHistGuardarRecibioConsegeria - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			return true;
		}
		
		function GetConsegeria($inscripcion)
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT inscripcion_id,num_hijos_vivos,recibio_consegeria
							FROM pyp_inscripcion_planificacion_fliar
							WHERE inscripcion_id=$inscripcion";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo AntecedentesGinecosOstetricos - GetConsegeria - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				while(!$result->EOF)
				{
					$vars[] = $result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			
			return $vars;
		}
		
		
		/************************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la consulta 
		* sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug = true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
}
?>