<?php
	/**************************************************************************************
	* $Id: AtencionCitas.class.php,v 1.1 2010/01/20 20:58:30 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F  Manrique
	***************************************************************************************/
	class AtencionCitas
	{
		function AtencionCitas(){}
		/**************************************************************************************
		*
		***************************************************************************************/
		function ObtenerPacientes($tipo_id_paciente,$paciente_id,$departamento,$offset)
		{
			$sql .= "SELECT	TO_CHAR(A.fecha_turno,'DD/MM/YYYY') AS fecha, ";
			$sql .= "		    B.hora, ";
			$sql .= "				A.duracion, ";
			$sql .= "				A.profesional_id, ";
			$sql .= "		    A.tipo_id_profesional, ";
			$sql .= "		    A.tipo_consulta_id, ";
			$sql .= "		    A.consultorio_id, ";
			$sql .= "		    CL.consultorio, ";
			$sql .= "		    C.tipo_id_paciente, ";
			$sql .= "		    C.paciente_id, ";
			$sql .= "		    C.plan_id, ";
			$sql .= "		    C.tipo_cita, ";
			$sql .= "		    C.cargo_cita, ";
			$sql .= "		    C.observacion, ";
			$sql .= "		    D.numero_orden_id, ";
			$sql .= "		    PR.nombre, ";
			$sql .= "		    TC.descripcion AS consulta, ";
			$sql .= "		    OM.orden_servicio_id ";
			$sql .= "FROM   agenda_turnos A ";
			$sql .= "				LEFT JOIN consultorios  CL ";
			$sql .= "				ON (CL.consultorio_id = A.consultorio_id) , ";
			$sql .= "		    agenda_citas B, ";
			$sql .= "		    agenda_citas_asignadas C, ";
			$sql .= "		    os_cruce_citas D, ";
			$sql .= "		    os_maestro OM, ";
			$sql .= "		    tipos_consulta TC, ";
			$sql .= "		    tipos_cita F, ";
			$sql .= "		    profesionales PR ";
			$sql .= "WHERE  A.fecha_turno >= NOW()::date ";
			$sql .= "AND 		B.sw_estado IN ('1','2','0') ";
			$sql .= "AND 		C.agenda_cita_id = B.agenda_cita_id ";
			$sql .= "AND 		C.tipo_id_paciente = '".$tipo_id_paciente."' ";
			$sql .= "AND 		C.paciente_id='".$paciente_id."' ";
			$sql .= "AND 		B.agenda_turno_id = A.agenda_turno_id ";
			$sql .= "AND 		D.agenda_cita_asignada_id = C.agenda_cita_asignada_id ";
			$sql .= "AND 		TC.tipo_consulta_id = A.tipo_consulta_id ";
			$sql .= "AND 		F.tipo_cita = C.tipo_cita ";
			$sql .= "AND		A.profesional_id = PR.tercero_id ";
			$sql .= "AND    A.tipo_id_profesional = PR.tipo_id_tercero ";
			$sql .= "AND    D.numero_orden_id = OM.numero_orden_id ";
			$sql .= "AND    TC.departamento = '".$departamento."' ";
			$sql .= "AND    OM.sw_estado NOT IN ('3') ";
			
			//if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM ($sql) AS MD ",$offset))
			//	return false;
						
			$sql .= "ORDER BY fecha,hora  ";
			//$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
    *
    * @return array
    ***********************************************************************************/
    function ObtenerDatosPacientes($rqst = array(), $paciente = array(),$offset = 0)
    {
			$datos = array();
				
			$sql  = "SELECT	primer_apellido||' '||segundo_apellido AS apellido, "; 
			$sql .= "				primer_nombre||' '||segundo_nombre AS nombre, "; 
			$sql .= "				tipo_id_paciente, ";
			$sql .= "				paciente_id "; 
			$sql .= "FROM		pacientes ";
			if(!empty($paciente))
			{
				$sql .= "WHERE	tipo_id_paciente = 	'".$paciente['tipo_documento_id']."' ";
				$sql .= "AND		paciente_id =	'".$paciente['documento_id']."' ";
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

				while (!$rst->EOF)
				{
					$datos = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
				$rst->Close();			
			}
			else
			{
				$nombres = $rqst['nombres1']." ".$rqst['nombres2'];
				$apellidos = $rqst['apellidos1']." ".$rqst['apellidos2'];
				
				if(trim($nombres) == "" && trim($apellidos) == "")
					return false;
					
				IncludeClass('ClaseUtil');
				$sql .= "WHERE ".ClaseUtil::FiltrarNombres($nombres,$apellidos);
				
				if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM ($sql) AS MD ",$offset))
					return false;
						
				$sql .= " ORDER BY nombre,apellido  ";
				$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

				while (!$rst->EOF)
				{
					$datos[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
				$rst->Close();
			}
			
			return $datos;
    }
		/********************************************************************************
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*********************************************************************************/
		function ProcesarSqlConteo($consulta,$offset=null,$limite=null)
		{
			$this->offset = 0;
			$this->paginaActual = 1;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
			}
			else
			{
				$this->limit = $limite;
			}
			
			if($offset)
			{
				$this->paginaActual = intval($offset);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}		

			if(!$this->registros)
			{
				if(!$result = $this->ConexionBaseDatos($consulta))
					return false;
	
				if(!$result->EOF)
				{
					$this->conteo = $result->fields[0];
					$result->MoveNext();
				}
				$result->Close();
			}
			else
			{
				$this->conteo = $this->registros;
			}
			return true;
		}
		/**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param 	string  $sql	sentencia sql a ejecutar 
		* @return rst 
		************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug = true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				//echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}
	}
?>