<?php	
	/**************************************************************************************
	* $Id: app_DatosPaciente_user.php,v 1.1 2009/11/10 19:33:17 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F  Manrique
	***************************************************************************************/
	class app_DatosPaciente_user extends classModulo
	{
		var $request = array();
		var $post = array();
		
		function app_DatosPaciente_user(){}
		/***************************************************************************************
		*
		***************************************************************************************/
		function SetActionVolver($link)
		{
			SessionDelVar("ActionVolver_Pacientes");
			SessionSetVar("ActionVolver_Pacientes",$link);
		}
		/***************************************************************************************
		*
		***************************************************************************************/
		function DatosPaciente($links)
		{
			//
			$dpto = GetVarConfigAplication('DefaultDpto');
			$mpio = GetVarConfigAplication('DefaultMpio');
			$bool = false;
			//
			
			$this->request = $_REQUEST;
			
			if($this->request['plan_id']==-1)
			{
				$this->frmError['MensajeError'] = "EL PLAN DEL PACIENTE NO ES VALIDO";
				return false;
			}

			IncludeClass('Pacientes','','app','DatosPaciente');
			$pct = new Pacientes();

			if($this->request['tipo_id_paciente'] !='MS' && $this->request['tipo_id_paciente'] != 'AS' )
			{
				if(!$this->request['paciente_id'] || !$this->request['tipo_id_paciente'])
				{
					$this->frmError['MensajeError'] = "LOS DATOS DE PACIENTES ESTAN INCOMPLETOS";
					return false;
				}
			}
			else
			{	
				if(empty($this->request['paciente_id']))
				{
					$this->request['paciente_id'] = $pct->ObtenerIdentifiacionNN($this->request);
					$bool = true;
				}
			}
			
			$afiliado  = $pct->ObtenerDatosPlan($this->request['plan_id']);
			
			//CUANDO EL PACIENTE NO TIENE IDENTIFICACION Y ES DE UN EVENTO
			if(($this->request['tipo_id_paciente'] =='MS' OR $this->request['tipo_id_paciente'] == 'AS') AND $afiliado['sw_tipo_plan'] == '1')
			{
				if($bool)
				{
					$dat = explode($this->request['tipo_id_paciente'],$this->request['paciente_id']);
					$this->request['paciente_id'] = $dpto.$mpio."NN".$dat[1];
				}
				else
				{
					$this->request['paciente_id'] = $dpto.$mpio."NN".$this->request['paciente_id'];
				}
			}
			//FIN CUANDO EL PACIENTE NO TIENE IDENTIFICACION Y ES DE UN EVENTO
			
			if($afiliado['sw_afiliados'] == 1 || $afiliado['sw_afiliados'] == 2 )
			{ 
      
     
        $inp = AutoCarga::factory('InformacionPacientes');
        $datosPaciente = $inp->ValidarInformacion($this->request);
       
        if(!is_array($datosPaciente))
        {
          if(is_numeric($datosPaciente))
            $this->frmError["MensajeError"] = $inp->ObtenerClasificacionErrores($datosPaciente);
          
          if($datosPaciente == 3)
          {
            $sla = AutoCarga::factory("InformacionAfiliados","","app","AgendaMedica");
            $datosPaciente = $sla->ObtenerDatosAfiliados($this->request);
           
            if($datosPaciente === false)
              $this->frmError["MensajeError"] = $sla->ErrMsg();
          }
        }
        
				if(!empty($datosPaciente))
				{
          $this->datos['afiliados'] = $datosPaciente;
          if(!$this->datos['afiliados']['sexo_id']) $this->datos['afiliados']['sexo_id']= $datosPaciente['tipo_sexo_id'];
          if(!$this->datos['afiliados']['tipo_afiliado']) $this->datos['afiliados']['tipo_afiliado'] = $datosPaciente['tipo_afiliado_atencion'];
          if(!$this->datos['afiliados']['rango']) $this->datos['afiliados']['rango'] = $datosPaciente['rango_afiliado_atencion'];
          if(!$this->datos['afiliados']['residencia_telefono']) $this->datos['afiliados']['residencia_telefono'] = $datosPaciente['telefono_residencia'];
          if(!$this->datos['afiliados']['residencia_direccion']) $this->datos['afiliados']['residencia_direccion'] = $datosPaciente['direccion_residencia'];
          
          $this->datos['afiliados']['afiliacion_activa'] = '1';
				}
			}
			if($this->request['tipoafiliado'])
    
				$this->datos['externa']['tipo_afiliado'] = $this->request['tipoafiliado'];
			
			if($this->request['rango'])
				$this->datos['externa']['rango'] = $this->request['rango'];
			
			if($this->request['Semanas'])
				$this->datos['externa']['semanas_cotizadas'] = $this->request['Semanas'];
			
			if($links)
				$this->action = $links;
			else
				$this->action['cancelar'] = SessionGetVar("CancelarLink");
			
			$this->datos['sw_tipo_plan'] = $afiliado['sw_tipo_plan'];
			 
			$this->action['aceptar'] = ModuloGetURL('app','DatosPaciente','user','FormaIngresarDatosPaciente',array("tipo_id_paciente"=>$this->request['tipo_id_paciente'],"paciente_id"=>$this->request['paciente_id']));
			SessionSetVar("CancelarLink",$this->action['cancelar']);
			return true;
		}
		/***************************************************************************************
		*
		***************************************************************************************/
		function IngresarDatosPaciente()
		{
			$this->request = $_REQUEST;
			
			IncludeClass('Pacientes','','app','DatosPaciente');
			$pct = new Pacientes();
			$rst = false;
		
			//sleep(5);
			
						
			if($this->request['actualizar'] > 0)
	         {    
			if(!empty($this->request['esm_pac']))
			{
						$esm_paciente= $pct->Insertar_PacientesESM($this->request);
			}
					if(!empty($this->request['tipo_fuerza_i']))
			{
					  $tipo_fuerza= $pct->Insertar_FuerzasESM($this->request);
			}
					 
			$rst = $pct->ActualizarDatosPaciente($this->request);
			}	
				
			else
			{
			
				$apellido = $this->request['primerapellido']." ".$this->request['segundoapellido'];
				$nombre  = $this->request['primernombre']." ".$this->request['segundonombre'];

				$this->pacientes = $pct->ObtenerPacientes($nombre,$apellido);

				if(empty($this->pacientes) || $this->request['continua'] == "1" 
					|| $this->request['tipo_id_paciente'] == 'AS' || $this->request['tipo_id_paciente'] == 'MS')
				{	
					if(!empty($this->request['esm_pac']))
					{
						$esm_paciente= $pct->Insertar_PacientesESM($this->request);
					}
					if(!empty($this->request['tipo_fuerza_i']))
					{
					  $tipo_fuerza= $pct->Insertar_FuerzasESM($this->request);
				
					}
					
					
					
					
					
					$rst = $pct->IngresarDatosPaciente($this->request);
			
				}	
				else
				{
					$this->post = $_POST;
					$this->post['tipo_id_paciente'] = $this->request['tipo_id_paciente'];
					$this->post['paciente_id'] = $this->request['paciente_id'];
					$this->post['actualizar'] = $this->request['actualizar'];
					$this->post['forma'] = $this->request['forma'];
					$this->post['continua'] = "1";
					
					$this->action['ver'] = ModuloGetURL('app','DatosPaciente','user','FormaInformacionPaciente');
					$this->action['verI'] = ModuloGetURL('app','DatosPaciente','user','FormaInformacionIngreso');
					$this->action['volver'] = ModuloGetURL('app','DatosPaciente','user','FormaDatosPaciente');
					$this->action['continuar'] = ModuloGetURL('app','DatosPaciente','user','FormaIngresarDatosPaciente',$this->post);
					return false;
				}
			}
			
			if(!$rst)
			{
				$this->frmError['MensajeError'] = $pct->mensajeDeError;
				$this->action['aceptar'] = SessionGetVar("CancelarLink");
				return false;
			}
			
			$this->action['aceptar']  = SessionGetVar("ActionVolver_Pacientes");
			if($this->request['paciente_id'])
				$this->action['aceptar'] .= "&paciente_id=".$this->request['paciente_id'];
			$this->action['aceptar'] .= "&afilia[rango]=".$this->request['rango'];
			$this->action['aceptar'] .= "&afilia[Semanas]=".$this->request['Semanas'];
			$this->action['aceptar'] .= "&afilia[tipoafiliado]=".$this->request['tipoafiliado'];
			
			return true;
		}
		/***************************************************************************************
		*
		***************************************************************************************/
		function InformacionPaciente()
		{
			$this->request = $_REQUEST;
			
			IncludeClass('Pacientes','','app','DatosPaciente');
			$pct = new Pacientes();
			$this->action['cerrar'] = "javascript:window.close()";
			$this->paciente = $pct->ObtenerDatosPaciente($this->request['tipo_id_paciente'],$this->request['paciente_id']);
		}
		
		function InformacionIngreso()
		{
			$this->request = $_REQUEST;
			
			IncludeClass('Pacientes','','app','DatosPaciente');
			$pct = new Pacientes();
			$this->action['cerrar'] = "javascript:window.close()";
			$this->ingreso = $pct->ObtenerDatosIngreso($this->request['tipo_id_paciente'],$this->request['paciente_id']);
		}
	}
?>