<?php

/**
* Submodulo de Antecedentes Ginecobstetricos.
*
* Submodulo para manejar los antecedentes ginecobstetricos de un paciente en una evolucion y las diferentes
* evoluciones que se necesiten.
* @author Luis Alejandro Vargas
* @version 1.0
* @package SIIS
* $Id: hc_AntecedentesGinecoObstetricos.class.php,v 1.3 2005/05/12 23:37:49 tizziano Exp $
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
	var $obj;
	
	function AntecedentesGO($objeto)
	{
			$this->obj=$objeto;
			return true;
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
							(	detalle,
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
		$obj=$this->obj;
		$dato=CalcularEdad($obj->datosPaciente['fecha_nacimiento']);
		
		$sql = "SELECT	d.nombre_tipo, 
										d.riesgo, 
										c.detalle, 
										c.destacar,
										a.evolucion_id, 
										d.hc_tipo_antecedente_gineco_id AS hctad,
										d.hc_tipo_antecedente_detg_id AS hctap, 
										e.descripcion, 
										e.sexo,
										e.edad_min, 
										e.edad_max, 
										c.sw_riesgo, 
										TO_CHAR(c.fecha_registro,'YYYY-MM-DD') AS fecha,
										COALESCE(c.ocultar,'0') AS ocultar,
										hc_antecedente_gineco_id AS hcid
						FROM 		hc_evoluciones AS a JOIN ingresos AS b 
										ON(	a.evolucion_id <=".$evolucion." AND 
												a.ingreso=b.ingreso AND 
												b.paciente_id='".$obj->paciente."' AND 
												b.tipo_id_paciente='".$obj->tipoidpaciente."')
										JOIN hc_antecedentes_ginecos AS c 
										ON(	a.evolucion_id=c.evolucion_id)
										RIGHT JOIN hc_tipos_antecedentes_detg AS d 
										ON(	c.hc_tipo_antecedente_detg_id=d.hc_tipo_antecedente_detg_id AND 
												c.hc_tipo_antecedente_gineco_id=d.hc_tipo_antecedente_gineco_id)
										RIGHT JOIN hc_tipos_antecedentes_ginecos AS e 
										ON(	d.hc_tipo_antecedente_gineco_id=e.hc_tipo_antecedente_gineco_id)
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
			$obj = $this->obj;
			
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
													a.ingreso=b.ingreso and b.paciente_id='".$obj->paciente."' and 
													b.tipo_id_paciente='".$obj->tipoidpaciente."')
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
			$obj=$this->obj;
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
	
			$query="SELECT *
							FROM pyp_inscripcion_cpn as a
							JOIN pyp_inscripciones_pacientes as b 
							ON(a.inscripcion_id=b.inscripcion_id 
							AND b.estado='1' 
							AND a.inscripcion_id=$inscripcion)";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo AntecedentesGinecosOstetricos- DatosInscripcionPacientes";
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
			$obj=$this->obj;
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
	
			$query="SELECT sum(d.puntaje_asociado)
							FROM pyp_inscripciones_pacientes as pypins,hc_evoluciones as a
							
							JOIN ingresos as b 
							on(a.evolucion_id<=".$evolucion." and 
							a.ingreso=b.ingreso and b.paciente_id='".$obj->paciente."' AND 
							b.tipo_id_paciente='".$obj->tipoidpaciente."')
							
							JOIN pyp_cpn_antecedentes_ginecos as c 
							ON(a.evolucion_id=c.evolucion_id)
							
							RIGHT JOIN pyp_cpn_antecedentes as d 
							ON(c.pyp_cpn_antecedente_id=d.pyp_cpn_antecedente_id AND 
							c.hc_tipo_antecedente_gineco_id=d.hc_tipo_antecedente_gineco_id)
							
							RIGHT JOIN hc_tipos_antecedentes_ginecos as e 
							ON(d.hc_tipo_antecedente_gineco_id=e.hc_tipo_antecedente_gineco_id) 
							WHERE d.puntaje_asociado is not null
							AND pypins.inscripcion_id=$inscripcion
							AND pypins.estado='1'
							AND c.sw_riesgo='1'";
			
			$result = $dbconn->Execute($query);
		
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo - ObtenerPuntajeAsociado";
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
			$obj=$this->obj;
			
			$sql = "SELECT	d.nombre_tipo, 
											d.riesgo, 
											c.detalle, 
											c.destacar,
											a.evolucion_id, 
											d.hc_tipo_antecedente_gineco_id AS hctad,
											d.hc_tipo_antecedente_detg_id AS hctap, 
											e.descripcion, 
											e.sexo,
											e.edad_min, 
											e.edad_max, 
											c.sw_riesgo, 
											TO_CHAR(c.fecha_registro,'YYYY-MM-DD') AS fecha,
											COALESCE(c.ocultar,'0') AS ocultar,
											hc_antecedente_gineco_id AS hcid
							FROM 		hc_evoluciones AS a JOIN ingresos AS b 
											ON(	a.evolucion_id <=".$evolucion." AND 
													a.ingreso=b.ingreso AND 
													b.paciente_id='".$obj->paciente."' AND 
													b.tipo_id_paciente='".$obj->tipoidpaciente."')
											JOIN hc_antecedentes_ginecos AS c 
											ON(	a.evolucion_id=c.evolucion_id)
											RIGHT JOIN hc_tipos_antecedentes_detg AS d 
											ON(	c.hc_tipo_antecedente_detg_id=d.hc_tipo_antecedente_detg_id AND 
													c.hc_tipo_antecedente_gineco_id=d.hc_tipo_antecedente_gineco_id)
											RIGHT JOIN hc_tipos_antecedentes_ginecos AS e 
											ON(	d.hc_tipo_antecedente_gineco_id=e.hc_tipo_antecedente_gineco_id)
							WHERE		d.hc_tipo_antecedente_detg_id = ".$hctap." 
							AND			d.hc_tipo_antecedente_gineco_id = ".$hctad."
							ORDER BY d.hc_tipo_antecedente_gineco_id, d.hc_tipo_antecedente_detg_id;";
	
			if(!$rst = $this->ConexionBaseDatos($sql.$where))	return false;
				
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
			$obj=$this->obj;
			
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
													b.paciente_id='".$obj->paciente."' AND 
													b.tipo_id_paciente='".$obj->tipoidpaciente."')
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
