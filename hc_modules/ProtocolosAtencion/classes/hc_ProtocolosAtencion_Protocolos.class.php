<?php
	/********************************************************************************* 
 	* $Id: hc_ProtocolosAtencion_Protocolos.class.php,v 1.2 2007/02/01 20:50:52 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_ProtocolosAtencion
	* 
 	**********************************************************************************/

	class Protocolos
	{

		function Protocolos()
		{
			return true;
		}
		
		function GetProtocolosAtencion($programa=null)
		{
		
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			if(!empty($programa))
				$sql="WHERE programa_id=".$programa;
			else
				$sql="";
			
			$query="SELECT *
							FROM pyp_protocolos_atencion
							$sql";
			
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo ProtocolosAtencion - GetProtocolosAtencion - SQL";
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
			
			$dbconn->CommitTrans();
			return $vars;
		}
		
		function ErrorDB()
		{
			$this->frmErrorBD=$this->error."<br>".$this->mensajeDeError;
			return $this->frmErrorBD;
		}
		
		function FechaStamp($fecha)
		{
			if($fecha)
			{
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}
	
				return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
			}
		}
	}
?>