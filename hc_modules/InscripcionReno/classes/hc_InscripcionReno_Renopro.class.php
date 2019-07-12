<?php
	/********************************************************************************* 
 	* $Id: hc_InscripcionReno_Renopro.class.php,v 1.2 2007/02/01 20:50:16 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_InscripcionCPN_Inscripcion
	* 
 	**********************************************************************************/
	
	class Renopro
	{
		function Renopro()
		{
			return true;
		}

		function InscribirReno($datos)
		{
			$pfj=SessionGetVar("Prefijo");
			$datosPaciente=SessionGetVar("DatosPaciente");
			$programa=SessionGetVar("Programa");
			$evolucion=SessionGetVar("Evolucion");
			
			list($dbconn) = GetDBconn();
			$query="SELECT nextval('pyp_inscripciones_pacientes_inscripcion_id_seq'::regclass)";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionReno - InscribirReno - SQL 1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$inscripcion=$result->fields[0];
				
				$query="INSERT INTO pyp_inscripciones_pacientes 
								(
									inscripcion_id,
									programa_id,
									tipo_id_paciente,
									paciente_id,
									usuario_id,
									fecha_inscripcion,
									estado
								)
								VALUES
								(
									$inscripcion,
									$programa,
									'".$datosPaciente['tipo_id_paciente']."',
									'".$datosPaciente['paciente_id']."',
									".UserGetUID().",
									now(),
									'1'
								)";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el SubModulo InscripcionReno - InscribirReno - SQL 2";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					if(!$datos['diabetes'.$pfj])
						$datos['diabetes'.$pfj]="NULL";
					
					if(!$datos['hta'.$pfj])
						$datos['hta'.$pfj]="NULL";
					
					$query="INSERT INTO pyp_inscripcion_renoproteccion
								(
									inscripcion_id,
									recibe_tto_diabetes,
									recibe_tto_hta
								)
								VALUES
								(
									$inscripcion,
									".$datos['diabetes'.$pfj].",
									".$datos['hta'.$pfj]."
								)";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el SubModulo InscripcionReno - InscribirReno - SQL 3 $query";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					else
					{
						if($datos['bandera'.$pfj])
						{
							$query="SELECT nextval('hc_signos_vitales_consultas_signos_vitales_consulta_id_seq'::regclass)";
							$result = $dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al Cargar el SubModulo InscripcionReno - InscribirReno - SQL 4";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
							
							$hc_signo_id=$result->fields[0];
							
							$query="INSERT INTO hc_signos_vitales_consultas
									(
										signos_vitales_consulta_id,
										peso,
										taalta,
										tabaja,
										evolucion_id,
										fecha_registro
									)
									VALUES
									(
										$hc_signo_id,
										".$datos['peso'.$pfj].",
										".$datos['ta_alta'.$pfj].",
										".$datos['ta_baja'.$pfj].",
										$evolucion,
										now()
									)";
							$result = $dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al Cargar el SubModulo InscripcionReno - InscribirReno - SQL 5";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
						}
						
						$query="INSERT INTO pyp_evoluciones_procesos
								(
									inscripcion_id,
									evolucion_id
								)
								VALUES
								(
									$inscripcion,
									$evolucion
								)";
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el SubModulo InscripcionReno - InscribirReno - SQL 6";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
					}
				}
			}
			
			return true;
		}

		function GetApoyosInicales($programa)
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT	b.cargo,
											b.descripcion,
											a.alias
							FROM		pyp_cargos a
							JOIN		cups as b 
											ON
											(
												a.cargo_cups=b.cargo
											)
							WHERE 	a.programa_id=$programa
							AND 		a.sw_inscripcion=1";
			
			$result = $dbconn->Execute($query);
	
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionReno - GetApoyosInicales - SQL";
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
		
		function ValidaInscripcionPaciente($tipo_id_paciente,$paciente_id,$programa)
		{
		
			list($dbconn) = GetDBconn();
			
			$query="SELECT a.inscripcion_id
							FROM 	pyp_inscripciones_pacientes as a
							WHERE a.tipo_id_paciente='$tipo_id_paciente'
							AND a.paciente_id='$paciente_id'
							AND a.programa_id=$programa
							AND a.estado='1'";
			
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionReno - ValidaInscripcionPaciente - SQL";
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
			
			if(empty($vars))
				return false;
			
			return $vars;	
		}
		
		function GetDatosSignos()
		{
			$evolucion=SessionGetVar("Evolucion");
			
			list($dbconn) = GetDBconn();
			$query="SELECT peso,tabaja,taalta
							FROM hc_signos_vitales_consultas
							WHERE evolucion_id=$evolucion";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo InscripcionReno - GetDatosSignos - SQL";
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
		
		function ConsultaSolicitudes()
		{
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			
			list($dbconn) = GetDBconn();

			$query="SELECT	a.hc_os_solicitud_id,
											b.cargo,
											b.evolucion_id
							FROM 		pyp_solicitudes_inscripciones as a
							JOIN 		hc_os_solicitudes AS b 
											ON 
											(
												a.hc_os_solicitud_id=b.hc_os_solicitud_id
											)
							WHERE a.evolucion_id=$evolucion
							AND a.inscripcion_id=$inscripcion
							AND a.programa_id=$programa";
			
			$result = $dbconn->Execute($query);
	
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo InscripcionReno - ConsultaSolicitudes - SQL";
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