<?php
	/********************************************************************************* 
 	* $Id: hc_PruebasLaboratorioReno_PruebasLaboratorio.class.php,v 1.2 2007/02/01 20:51:01 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_ProtocolosAtencion
	* 
 	**********************************************************************************/

	class PruebasLaboratorio
	{
		function PruebasLaboratorio()
		{
			return true;
		}
		
		function GetPruebasLaboratorio($programa=null)
		{
			list($dbconn) = GetDBconn();
			
			$where="WHERE a.programa_id=$programa";
			if(!$programa)
				$where="";
			
			$query="SELECT	b.cargo,
											b.descripcion,
											a.alias
							FROM		pyp_cargos a
							JOIN		cups as b 
											ON
											(
												a.cargo_cups=b.cargo
											)
							$where
							";
			
			$result = $dbconn->Execute($query);
	
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo PruebasLaboratorioReno - GetPruebasLaboratorio - SQL";
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