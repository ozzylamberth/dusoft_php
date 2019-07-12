<?php
	/********************************************************************************* 
 	* $Id: hc_Apoyos_Diagnosticos_Solicitud_APD_Solicitudes.class.php,v 1.2 2007/02/01 20:43:43 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_Apoyos_Diagnosticos_Solicitud_APD_Solicitudes 
	* 
 	**********************************************************************************/
	class APD_Solicitudes
	{
		function APD_Solicitudes()
		{
			return true;
		}
		
		/********************************************************************** 
		* Funcion que permite insertar varias solicitudes de apoyos diagnosticos
		* @param  array apoyos_cargos, arreglo de cargos cups
		* @param array apoyod_tipos, arreglo de apoyos_tipos
		* @return boolean
		***********************************************************************/
		
		function Insertar_Varias_Solicitudes($apoyod_cargos,$evolucion=null,$inscripcion=null,$programa,$periodo_id=null)
		{
			$pfj=SessionGetvar("Prefijo");
			$plan=SessionGetVar("Plan");
			$cpn=SessionGetVar("cpn");
			
			list($dbconn) = GetDBconn();
			
			for($i=0;$i<sizeof($apoyod_cargos);$i++)
			{
				$query="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq'::regclass);";
				$result=$dbconn->Execute($query);
				$hc_os_solicitud_id=$result->fields[0];
	
				$query="INSERT INTO hc_os_solicitudes
								( hc_os_solicitud_id, 
									evolucion_id, 
									cargo, 
									os_tipo_solicitud_id, 
									plan_id 
								)
								VALUES
								(
									$hc_os_solicitud_id,
									".$evolucion.",
									'".$apoyod_cargos[$i]."', 
									'".ModuloGetVar('','','TipoSolicitudApoyod')."',
									".$plan."
								);";
				
				$result=$dbconn->Execute($query);
			
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al insertar en Insertar_Varias_Solicitudes - hc_os_solicitudes";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					
					return false;
				}
				else
				{
					$query="INSERT INTO hc_os_solicitudes_apoyod
									(
										hc_os_solicitud_id, 
										apoyod_tipo_id
									)
									VALUES 
									(
										$hc_os_solicitud_id, 
										'".ModuloGetVar('','','TipoApoyod')."'
									);";
		
					$result=$dbconn->Execute($query);
					
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al insertar en Insertar_Varias_Solicitudes - hc_os_solicitudes_apoyod";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					else
					{
						
						$query="INSERT INTO pyp_solicitudes_inscripciones
										(
											evolucion_id, 
											inscripcion_id,
											hc_os_solicitud_id,
											programa_id
										)
										VALUES 
										(
											$evolucion, 
											$inscripcion,
											$hc_os_solicitud_id,
											$programa
										);";
	
						$result=$dbconn->Execute($query);
						
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al insertar en Insertar_Varias_Solicitudes - pyp_cpn_solicitudes_inscripciones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						else
						{
							if($cpn)
							{
								$query ="INSERT INTO pyp_procedimientos_solicitados
															(
																hc_os_solicitud_id,
																evolucion_id,
																inscripcion_id,
																periodo_sugerido,
																programa_id,
																cargo_cups,
																periodo_solicitud
															) 
															VALUES 
															(
																$hc_os_solicitud_id,
																$evolucion,
																$inscripcion,
																$periodo_id,
																$programa,
																'".$apoyod_cargos[$i]."',
																$periodo_id
															);";
	
								$result = $dbconn->Execute($query);
						
								if($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al Cargar el Modulo Insertar_Varias_Solicitudes - pyp_cpn_procedimientos_solicitados - SQL 4";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
							}
						}
					}
				}
			}
			return true;
		}
		
		function ErrorDB()
		{
			$this->frmErrorBD=$this->error."<br>".$this->mensajeDeError;
			return $this->frmErrorBD;
		}
		
	}
?>