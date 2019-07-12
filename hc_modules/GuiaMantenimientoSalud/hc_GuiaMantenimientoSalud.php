<?php
	
	IncludeClass("GuiaMantenimiento_HTML","html","hc","GuiaMantenimientoSalud");
	IncludeClass("GuiaMantenimiento",null,"hc","GuiaMantenimientoSalud");
	
	class GuiaMantenimientoSalud extends hc_classModules
	{
		function GuiaMantenimientoSalud()
		{
			return true;
		}
		
		/**
		* Esta funci� retorna los datos de concernientes a la version del submodulo
		* @access private
		*/
	
		function GetVersion()
		{
			$informacion=array(
			'version'=>'1',
			'subversion'=>'0',
			'revision'=>'0',
			'fecha'=>'30/06/2008',
			'autor'=>'LORENA ARAGON',
			'descripcion_cambio' => '',
			'requiere_sql' => false,
			'requerimientos_adicionales' => '',
			'version_kernel' => '1.0'
			);
			return $informacion;
		}
	
	
		/**
		* Esta funci� retorna los datos de la impresi� de la consulta del submodulo.
		*
		* @access private
		* @return text Datos HTML de la pantalla.
		*/
		function GetConsulta()
		{				
			
			if($consulta==false)
				return "";
			
			return $consulta;
		}
			
		/**
		* Esta metodo captura los datos de la impresi� de la Historia Clinica.
		* @access private
		* @return text Datos HTML de la pantalla.
		*/
	
		function GetReporte_Html()
		{
			
			
			if($imprimir==false)
				return "";
				
			return $imprimir;
		}
	
		/**
		* Esta funci� verifica si este submodulo fue utilizado para la atencion de un paciente.
		*
		* @access private
		* @return text Datos HTML de la pantalla.
		*/
	
		function GetEstado()
		{
			return true;
		}
			
		function GetForma()
		{		
			//echo '<BR><BR><BR><BR>';			
			$guiaMtoSalud_html=new GuiaMantenimiento_HTML();
			$guiaMtoSalud=new GuiaMantenimiento();
			$actividades=$guiaMtoSalud->GetActividades();
			$etapas=$guiaMtoSalud->GetEtapas();
			$parametrizacion=$guiaMtoSalud->GetParametrizacion();						
			$evolucion=$this->evolucion;			
			$pfj=$this->frmPrefijo;
			$paso=$this->paso;
			$edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],date("Y/m/d"));			
			
			$calculoedad=($edad['anos']*12)+$edad['meses'];
			if($calculoedad<=24){$e=$calculoedad;}else{$e=$edad['anos'];}
			$accion=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'insertar','edadPac'.$pfj=>$e));						
			
			if($_REQUEST['accion'.$pfj]=='insertar')
			{			
				$rst=$guiaMtoSalud->IngresarDatosMtoSalud($_REQUEST,$evolucion,$this->ingreso,$pfj,$this->tipoidpaciente,$this->paciente,$this->plan_id);
				if(!$rst)
				{
					$guiaMtoSalud_html->frmError["MensajeError"]="ERROR AL GUARDAR LOS DATOS";					
				}
				else
				{
					$guiaMtoSalud_html->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";					
				}  
				
			}		
			
			if($calculoedad<=24)
			{				
				$meses=$calculoedad;	
				foreach($etapas as $etapaId=>$arrE)
				{
					if($meses>=$arrE[edadinicio] && $arrE[tipoedad]=='M')
					{
					
					}
					else{
						unset($etapas[$etapaId]);
					}
				}									
			}
			else
			{
				foreach($etapas as $etapaId=>$arrE)
				{									
					if($etapaId!=0 && $etapaId!=1){
						if($edad['anos']>=$arrE[edadinicio])
						{
							
						}
						else
						{							
							unset($etapas[$etapaId]);
						}						
					}
				}	
			}				
			
			$_SESSION['datospaciente']=$this->datosPaciente;
			$parametrizacionHC=$guiaMtoSalud->GetParametrizacionHC($this->tipoidpaciente,$this->paciente);			
			$resultados=$guiaMtoSalud->ConsultaResultados($this->tipoidpaciente,$this->paciente);
			$this->salida .= $guiaMtoSalud_html->frmForma($accion,$actividades,$etapas,$parametrizacion,$parametrizacionHC,$evolucion,$edad,$pfj,$resultados);
			return true;
						
		}
	}
?> 
