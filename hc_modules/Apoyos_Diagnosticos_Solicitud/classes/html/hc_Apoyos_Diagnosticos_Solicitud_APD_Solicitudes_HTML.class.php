 <?php
	/********************************************************************************* 
 	* $Id: hc_Apoyos_Diagnosticos_Solicitud_APD_Solicitudes_HTML.class.php,v 1.2 2007/02/01 20:43:43 luis Exp $ 
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
		var $obj;
		
		function APD_Solicitudes($objeto)
		{
			$this->obj=$objeto;
			return true;
		}
		
		/********************************************************************** 
		* Funcion que permite insertar varias solicitudes de apoyos diagnosticos
		* @param  array apoyos_cargos, arreglo de cargos cups
		* @param array apoyod_tipos, arreglo de apoyos_tipos
		* @return boolean
		***********************************************************************/
		function Insertar_Varias_Solicitudes($apoyod_cargos,$apoyod_tipos,$apoyos_todos)
		{ 
			$obj=$this->obj;
			$pfj=$obj->frmPrefijo;
			
			list($dbconn) = GetDBconn();
			global $ADODB_FETCH_MODE;
			
			for($i=0;$i<sizeof($apoyos_todos);$i++)
			{
				$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
				
				$query="SELECT hc_os_solicitud_id
				FROM hc_os_solicitudes
				WHERE cargo='".$apoyos_todos[$i]."'
				AND evolucion_id=".$obj->evolucion."
				AND plan_id=".$obj->plan_id."
				And paciente_id='".$obj->paciente."'
				AND tipo_id_paciente='".$obj->tipoidpaciente."'";
				
				$result=$dbconn->Execute($query);
				$hc_os_solicitud_id=$result->fields[0];
				
				$query="DELETE
				FROM hc_os_solicitudes_apoyod
				WHERE hc_os_solicitud_id=".$hc_os_solicitud_id;
				
				$result=$dbconn->Execute($query);
				
				$query="DELETE
				FROM hc_os_solicitudes
				WHERE hc_os_solicitud_id=".$hc_os_solicitud_id;
				
				$result=$dbconn->Execute($query);
			}
			
			for($i=0;$i<sizeof($apoyod_cargos);$i++)
			{
				$ADODB_FETCH_MODE = ADODB_FETCH_ROW;

				//realiza el id manual de la tabla
				$query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
	
				$result=$dbconn->Execute($query1);
				$hc_os_solicitud_id=$result->fields[0];
				//fin de la operacion
	
				$query2="INSERT INTO hc_os_solicitudes
					(hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id, paciente_id, tipo_id_paciente)
					VALUES
					($hc_os_solicitud_id,".$obj->evolucion.",
					'".$apoyod_cargos[$i]."', '".ModuloGetVar('','','TipoSolicitudApoyod')."',
					".$obj->plan_id.",
					'".$obj->paciente."',
					'".$obj->tipoidpaciente."')";
				
				$resulta=$dbconn->Execute($query2);
			
				if ($dbconn->ErrorNo() != 0)
				{
					$obj->error = "Error al insertar en hc_os_solicitudes";
					$obj->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$obj->ban=1;
					return false;
				}
				else
				{
					$query3="INSERT INTO hc_os_solicitudes_apoyod
						(hc_os_solicitud_id, apoyod_tipo_id)
						VALUES ($hc_os_solicitud_id, '".$apoyod_tipos[$i]."');";
		
					$resulta1=$dbconn->Execute($query3);
					if ($dbconn->ErrorNo() != 0)
					{
						$obj->error = "Error al insertar en hc_os_solicitudes_apoyod";
						$obj->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$obj->ban=1;
						return false;
					}
				}
			}
			return true;
		}
	}
?>