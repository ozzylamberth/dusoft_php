<?php	
	/**************************************************************************************
	* $Id: app_DatosPaciente_userclasses_HTML.php,v 1.1 2009/11/10 19:33:17 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F  Manrique
	***************************************************************************************/
	class app_DatosPaciente_userclasses_HTML extends app_DatosPaciente_user
	{
		var $request = array();
		
		function app_DatosPaciente_userclasses_HTML()
		{
			$this->app_DatosPaciente_user();
		}
		/***************************************************************************************
		*
		***************************************************************************************/
		function FormaDatosPaciente($links)
		{
						
			$this->DatosPaciente($links);
			
			IncludeClass('PacientesHTML','','app','DatosPaciente');
			$pch = new PacientesHTML();
			
			if(empty($links)) $this->SetJavaScripts("Ocupaciones");
			
			$this->salida .= ThemeAbrirTabla('DATOS DEL PACIENTE');
			$this->salida .= $pch->FormaPedirDatosPaciente($this->request,$this->datos,$this->action,&$this,$this->request['afiliacion']);
			$this->salida .= ThemeCerrarTabla(); 
			
			return true;
		}
		/***************************************************************************************
		*
		***************************************************************************************/
		function FormaIngresarDatosPaciente()
		{
			$rst = $this->IngresarDatosPaciente();
		
			if($rst === false)
			{
				if($this->pacientes)
				{
					IncludeClass('PacientesHTML','','app','DatosPaciente');
					$pch = new PacientesHTML();
					$this->salida = $pch->FormaNombresHomonimos($this->pacientes,$this->post,$this->action);
					return true;
				}
				$this->salida .= ThemeAbrirTabla('DATOS DEL PACIENTE');
				$this->salida .= "<form name=\"formabuscar\" action=\"".$this->action['aceptar']."\" method=\"post\">\n";   
				$this->salida .= "	<table width=\"100%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr class=\"normal_10AN\">\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				".$this->frmError['MensajeError']."\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";
				$this->salida .= "	<table width=\"60%\" border=\"0\" align=\"center\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td align=\"center\">\n";
				$this->salida .= "				<input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Aceptar\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
				$this->salida .= "</form>\n";
				$this->salida .= ThemeCerrarTabla(); 	
			}
			else
			{	//sleep(5);
				$this->salida .= "<script>\n";
				$this->salida .= "	location.href = \"".$this->action['aceptar']."\"\n";
				$this->salida .= "</script>\n";
			}
					
			return true;
		}
		/***************************************************************************************
		*
		***************************************************************************************/
		function FormaInformacionPaciente()
		{
			$this->InformacionPaciente();
			
			IncludeClass('PacientesHTML','','app','DatosPaciente');
			$pch = new PacientesHTML();
			
			$this->salida .= ThemeAbrirTabla('INFORMACION - PACIENTE');
			$this->salida .= $pch->FormaInformacionPaciente($this->paciente,$this->action);
			$this->salida .= ThemeCerrarTabla(); 
			
			return true;
		}
		
		function FormaInformacionIngreso()
		{
			$this->InformacionIngreso();
			
			IncludeClass('PacientesHTML','','app','DatosPaciente');
			$pch = new PacientesHTML();
			
			$this->salida .= ThemeAbrirTabla('INFORMACION - INGRESO');
			$this->salida .= $pch->FormaInformacionIngreso($this->ingreso,$this->action);
			$this->salida .= ThemeCerrarTabla(); 
			
			return true;
		}
	}
?>