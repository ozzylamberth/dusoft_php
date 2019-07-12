<?php
	/**************************************************************************************
	* $Id: app_NCAutorizaciones_user.php,v 1.3 2009/11/13 12:05:25 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* $Revision: 1.3 $
	*
	* @author Hugo Freddy Manrique
	***************************************************************************************/
	class app_NCAutorizaciones_user extends classModulo
	{
		var $request = array();
		var $action = array('aceptar'=>'','cancelar'=>'');
		var $datos = array();
		
		function app_NCAutorizaciones_user(){		}
		/*************************************************************************************
		* @access private
		*************************************************************************************/
		function Main()
		{
			$this->EliminarVariablesSession();
			
			$this->SetBuscador(true);
			$this->SetActionVolver(ModuloGetURL('system','Menu'));
		}
		/*************************************************************************************
		* @access private
		*************************************************************************************/
		function EliminarVariablesSession()
		{
			SessionDelVar("Buscador");
			SessionDelVar("ActionError");
			SessionDelVar("ActionCerrar");
			//SessionDelVar("ActionVolver");
			SessionDelVar("ActionAceptar");
			SessionDelVar("DatosPaciente");
			//SessionDelVar("MostrarBuscador");
			SessionDelVar("IngresoPaciente");
			SessionDelVar("ClaseAutorizacion");
			SessionDelVar("ActionVolverModulo");
			SessionDelVar("CargosAutorizacion");
		}
		/*************************************************************************************
		* @access private
		*************************************************************************************/
		function CrearAutorizacion($datos,$cargos)
		{
			$this->action['cancelar'] = SessionGetVar("ActionVolver");
			$this->action['aceptar'] = ModuloGetURL('app','NCAutorizaciones','user','FormaIngresarAutorizacion');;
			$this->action['cerrar'] = SessionGetVar("ActionCerrar");
			$this->action['volver'] = SessionGetVar("ActionVolverModulo");
			
			if(!empty($datos)) 
				$this->request = $datos;
				else if(SessionIsSetVar("DatosPaciente"))
					$this->request = SessionGetVar("DatosPaciente");
					else
						$this->request = $_REQUEST;
			
			$this->_SetCargos($cargos);
			$this->_SetIngreso($this->request['ingreso']);
			$this->_SetActionCerrar("javascript:CerrarVentana(".$this->request['ingreso'].")");

			$p = SessionGetVar("DatosPaciente");
			if(empty($p))
				SessionSetVar("DatosPaciente",$this->request);

			$p = SessionGetVar("DatosPaciente");
			if(!empty($this->request)) return false;
			
		}
		/*************************************************************************************
		* Metodo en el se se sube a una variable de sesion el link de volver
		* @param $link String cadena del link al cual se hara el regreso cuando se de volver 
		* @access public
		*************************************************************************************/
		function SetActionVolver($link)
		{
			SessionDelVar("ActionVolver");
			SessionSetVar("ActionVolver",$link);
		}
		/*************************************************************************************
		* Metodo en el se se sube a una variable de sesion el link para cuando suceda un error
		* @param $link String cadena del link para cuando suceda un error 
		* @access public
		*************************************************************************************/
		function SetActionError($link)
		{
			SessionDelVar("ActionError");
			SessionSetVar("ActionError",$link);
		}
		/*************************************************************************************
		* Metodo en el se se sube a una variable de sesion el link para cuando el proceso se
		* realiza de manera correcta
		* @param $link String cadena del link para cuando se hagan las cossa de manera correcta 
		* @access public
		*************************************************************************************/
		function SetActionAceptar($link)
		{
			SessionDelVar("ActionAceptar");
			SessionSetVar("ActionAceptar",$link);
		}
		/*************************************************************************************
		* Metodo en el se se sube a una variable de sesion el link para cuando suceda un error
		* @param $link String cadena del link para cuando suceda un error 
		* @access private
		*************************************************************************************/
		function _SetActionCerrar($link)
		{
			SessionDelVar("ActionCerrar");
			SessionSetVar("ActionCerrar",$link);
		}
		/*************************************************************************************
		* Metodo en el se se sube a una variable de sesion el ingreso del paciente cuando 
		* existe
		* @param $ingreso Numero del ingreos a subir a sesion 
		* @access private
		*************************************************************************************/
		function _SetIngreso($ingreso = "NULL")
		{
			SessionDelVar("IngresoPaciente");
			SessionSetVar("IngresoPaciente",$ingreso);
		}
		/*************************************************************************************
		* Metodo en el se se sube a una variable de sesion los cargos que se deseanautorizar 
		* @param $cargos Array de cargos que se subiran a session,
		*					indices Numero de hac_solictud_id, Numero del cargo e indice consecutivo
		* @access private
		*************************************************************************************/
		function _SetCargos($cargos = "NULL")
		{
			SessionDelVar("CargosAutorizacion");
			SessionSetVar("CargosAutorizacion",$cargos);
		}
		/*************************************************************************************
		* Metodo en el se se sube a una variable de sesion el link de volver del modulo  
		* @param $link String cadena del link para cuando suceda un error 
		* @access private
		*************************************************************************************/
		function _SetActionVolver($link)
		{
			SessionDelVar("ActionVolverModulo");
			SessionSetVar("ActionVolverModulo",$link);
		}
		/*************************************************************************************
		* Metodo en el se se sube a una variable de sesion el link de volver del modulo  
		* @param $link String cadena del link para cuando suceda un error 
		* @access private
		*************************************************************************************/
		function SetBuscador($valor)
		{
			SessionDelVar("MostrarBuscador");
			SessionSetVar("MostrarBuscador",$valor);
			SessionSetVar("Buscador","entrada");
		}
		/*************************************************************************************
		* Metodo en el se se sube a una variable de sesion la clase de autorizacion que se hara
		* @param $clase String tipo de autorizacion,AD:admisiónn, OS:orden de servicio
		* @access public
		*************************************************************************************/
		function SetClaseAutorizacion($clase)
		{
			if($clase == 'AD' || $clase == 'OS' )
			{
				SessionDelVar("ClaseAutorizacion");
				SessionSetVar("ClaseAutorizacion",$clase);
				return true;
			}
			else
			{
				$this->frmError['MensajeError']  = "EL VALOR DE LA CLASE DE AUTORIZACION DEBE SER AD->ADMISION U OS->ORDEN DE SERVICIO";
				return false;
			}
		}
		/*************************************************************************************
		* @access private
		*************************************************************************************/
		function _ValidarSession($datos)
		{
			$cancelar = SessionGetVar("ActionVolver");
			$aceptar = SessionGetVar("ActionAceptar");
			$clase = SessionGetVar("ClaseAutorizacion");
			
			if(empty($cancelar)) 
				$this->action['cancelar'] = ModuloGetURL('system','Menu');
			else
				$this->action['cancelar'] = $cancelar;
			
			if(empty($cancelar) || empty($aceptar))
			{
				$this->frmError['MensajeError']  = "LOS DATOS DE LOS ACTION NO ESTAN COMPLETOS: <pre><b>".print_r($this->action,true)."</b></pre>";
				return false;
			}			
			
			if(empty($clase) || empty($aceptar))
			{
				$this->frmError['MensajeError']  = "NO SE HA ESPECIFICADO LA CLASE DE ADMISION QUE SE HARA";
				return false;
			}
			return true;
		}
		/*************************************************************************************
		* @access public
		*************************************************************************************/
		function ValidarAdmisionHospitalizacion($datos,$cargos = array())
		{
			$rst = $this->_ValidarSession();
			$this->automatico = false;
			if(!$rst) return false;

			if(empty($datos))
			{
				$this->frmError['MensajeError'] = "NO HAY DATOS PARA LA AUTORIZACION";
				return false;
			}
			
			if(empty($datos['idp']) || empty($datos['tipoid']) || empty($datos['plan_id']))
			{
				$this->frmError['MensajeError']  = "DATOS DE LA AUTORIZACIÓN INCOMPLETOS: <br>";
				$this->frmError['MensajeError'] .= "<label class='normal_10AN'>plan:</label> ".$datos['plan_id']."<br>";
				$this->frmError['MensajeError'] .= "<label class='normal_10AN'>pacienteid:</label> ".$datos['idp']."<br>";
				$this->frmError['MensajeError'] .= "<label class='normal_10AN'>tipoidpaciente:</label> ".$datos['tipoid']."<br>";
				return false;
			}
			
			IncludeClass('Autorizaciones','','app','NCAutorizaciones');
			$aut = new Autorizaciones();
			
			$planes = $aut->ObtenerTiposPlanes($datos['plan_id']);			
			$this->datos['sw_autorizacion_sin_bd'] = $planes['sw_autorizacion_sin_bd'];
			$this->datos['protocolo'] = $planes['protocolos'];
			$this->datos['auditor'] = $aut->ObtenerUsuariosAutorizacion($datos['plan_id'],UserGetUID());
			$this->datos['nivel'] = $aut->ObtenerNivelAutorizacion(UserGetUID());
			$this->datos['idp'] = $datos['idp'];
			$this->datos['tipoid'] = $datos['tipoid'];
			$this->datos['plan_id'] = $datos['plan_id'];
						
			$rst = $this->VerificarAutorizacion($planes);
			if(!empty($datos['afiliado']))
			{
				$this->datos['externo']['rango'] = $datos['afiliado']['rango'];
				$this->datos['externo']['tipo_afiliado'] = $datos['afiliado']['tipoafiliado'];
				$this->datos['externo']['semanas_cotizadas'] = $datos['afiliado']['Semanas'];
			}
			$this->action['aceptar'] = ModuloGetURL('app','NCAutorizaciones','user','FormaCrearAutorizacion');
			$this->action['cancelar'] = SessionGetVar("ActionVolver");
			$this->action['continuar'] = SessionGetVar("ActionAceptar");
			
			SessionDelVar("DatosPaciente");
			SessionSetVar("DatosPaciente",$this->datos);
			
      if($datos['tipo_servicio'] == 'CE' && $datos['orden_servicio_id'])
      {
        $this->auto_anteriores = $aut->ObtenerAutorizacionesOS($datos);
        if(!empty($auto_anteriores))
        {
          return true;
        }
      }
      
			if($planes['sw_autorizacion'] == '0')
			{
				$rqst = array();
				if(!empty($this->datos['externo']))
				{
					$rqst['tipoafiliado'] = $this->datos['externo']['tipo_afiliado'];
					$rqst['Semanas'] = $this->datos['externo']['semanas_cotizadas'];
					$rqst['rango'] = $this->datos['externo']['rango'];
				}
				else
				{
					$rqst['tipoafiliado'] = $this->datos['afiliados']['tipo_afiliado'];
					$rqst['Semanas'] = $this->datos['afiliados']['semanas_cotizadas'];
					$rqst['rango'] = $this->datos['afiliados']['rango'];
				}
				
				$rqst['hora'] = date("H");
				$rqst['fecha'] = date("d/m/Y");
				$rqst['minuto'] = date("i");
				$rqst['tipo_autorizacion'] = "A";
				$rqst['tipoautoriza_interna'] = "AT";
				
				if(!empty($cargos))	$this->_SetCargos($cargos);;
				
				$rst = $this->IngresarAutorizacion($planes,$rqst);
				$this->automatico = true;
			}
      
			return $rst;
		}
		/*************************************************************************************
		* @access private
		*************************************************************************************/
		function VerificarAutorizacion($planes)
		{
			if($planes['sw_afiliacion'] == 1)
			{
				if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
				{
					$this->frmError['MensajeError']  = "NO SE PUDO INCLUIR : classes/notas_enfermeria/revision_sistemas.class.php";
					return false;
				}
				
				if(!class_exists('BDAfiliados'))
				{
					$this->frmError['MensajeError']  = "NO EXISTE BD AFILIADOS";
					return false;
				}
				
				$class= New BDAfiliados($this->datos['tipoid'],$this->datos['idp'],$this->datos['plan_id']);
				$class->GetDatosAfiliado();
				
				if($class->GetDatosAfiliado() == false) 
					$this->frmError["MensajeError"] = $class->mensajeDeError;
					
				if(!empty($class->salida))
				{
					$afiliado = $class->salida;
					$this->datos['afiliados']['rango'] = $afiliado['campo_nivel'];
					$this->datos['afiliados']['tipo_afiliado'] = $afiliado['campo_tipo_afiliado'];
					$this->datos['afiliados']['semanas_cotizadas'] = $afiliado['campo_semanas_cotizadas'];
					$this->datos['afiliados']['tipo_afiliado_nombre'] = $afiliado['campo_tipo_afiliacion'];
					$this->datos['afiliados']['tipo_empleador'] = $afiliado['campo_tipo_empleador'];
					$this->datos['afiliados']['id_empleador'] = $afiliado['campo_id_empleador'];
					
					if(!empty($this->datos['afiliados']['campo_urgencias']))
						$this->frmError["MensajeError"] = "EL PACIENTE SE ENCUENTRA EN LA BASE DE DATOS DE LA ENTIDAD Y ESTA EN MES DE URGENCIAS.";
					else
						$this->frmError["MensajeError"] = "EL PACIENTE SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS DE LA ENTIDAD.";
					
					$this->frmError["MensajeError"] .= ", NECESITA AUTORIZACIÓN PARA LA HOSPITALIZACIÓN.";
				}
				else
				{
					if(!empty($planes['sw_autoriza_sin_bd']))
						$this->frmError["MensajeError"] = "EL PACIENTE NO SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS, NECESITA UNA AUTORIZACIÓN.";
					else
					{
						$this->frmError["MensajeError"] = "EL PACIENTE NO SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS DE LA ENTIDAD. NO PUEDE SER AUTORIZADO.";
						return false;
					}
					
					return true;
				}
			}
			else
			{
				$this->frmError["MensajeError"] = "EL PACIENTE NECESITA UNA AUTORIZACIÓN.";
				return true;
			}
			return true;
		}
		/*************************************************************************************
		* @access public
		*************************************************************************************/
		function IngresarAutorizacion($planes,$rqst = array())
		{
			IncludeClass('Autorizaciones','','app','NCAutorizaciones');
			
			if(!empty($rqst))
				$datos = $rqst;
			else
				$datos = $_REQUEST;
			
			$aut = new Autorizaciones();
			$paciente = SessionGetVar("DatosPaciente");
			$cargos = SessionGetVar("CargosAutorizacion");
			
			if($datos['Semanas'] <= 0 || !$datos['Semanas']) $datos['Semanas'] = '0';
			
			$rst = $aut->CrearAutorizacion($datos,UserGetUID(),SessionGetVar("ClaseAutorizacion"),$paciente['plan_id'],SessionGetVar("IngresoPaciente"),$cargos);
			
			if(!$rst)
			{
				$this->frmError['MensajeError'] = $aut->frmError['MensajeError'];
				return false;
			}
			
			$adicion  = "&autorizacion[numero_autorizacion]=".$rst;
			$adicion .= "&autorizacion[rango]=".$datos['rango'];
			$adicion .= "&autorizacion[semanas]=".$datos['Semanas'];
			$adicion .= "&autorizacion[tipoafiliado]=".$datos['tipoafiliado'];
			$adicion .= "&autorizacion[plan_id]=".$paciente['plan_id'];
			$adicion .= "&autorizacion[paciente_id]=".$paciente['idp'];
			$adicion .= "&autorizacion[tipo_id_paciente]=".$paciente['tipoid'];
			$adicion .= "&autorizacion[tipo_empleador]=".$paciente['tipo_empleador'];
			$adicion .= "&autorizacion[id_empleador]=".$paciente['id_empleador'];
			
			$this->datos['idp'] = $datos['idp'];
			$this->datos['tipoid'] = $datos['tipoid'];
			$this->datos['plan_id'] = $datos['plan_id'];

			$this->action['cancelar'] = SessionGetVar("ActionVolver");
			$this->action['aceptar'] = SessionGetVar("ActionAceptar");
			if(!SessionIsSetVar("ActionAceptar"))
				$this->action['aceptar'] = SessionGetVar("ActionCerrar");
			else
				$this->action['aceptar'] .= $adicion;
			
			
			$this->frmError['MensajeError'] = "LA AUTORIZACION SE HA GUARDADO CORRECTAMENTE ".$rst;
			$this->EliminarVariablesSession();
			return true;
		}
		/*************************************************************************************
		* @access private
		*************************************************************************************/
		function MostrarDatosIngreso($datos)
		{
			if(empty($datos)) 
				$this->request = $_REQUEST;
			else
				$this->request = $datos;
				
			IncludeClass('ConsultaAutorizaciones','','app','NCAutorizaciones');
			$this->action['cancelar'] = SessionGetVar("ActionCerrar");
			$this->action['aceptar'] = ModuloGetURL('app','NCAutorizaciones','user','FormaCrearAutorizacion');
			
			//SessionDelVar("DatosPaciente");
			SessionDelVar("ActionVolverModulo");
			$ctz = new ConsultaAutorizaciones();
			
			$cantidad = $ctz->ObtenerCuentasIngreso($this->request['autorizar']['ingreso']);
			if($cantidad['conteo'] > 1)
				$this->_SetActionVolver(ModuloGetURL('app','NCAutorizaciones','user','FormaMostrarDatosIngreso',array("autorizar"=>$this->request['autorizar'])));
			return $cantidad['conteo'];
		}
		/*************************************************************************************
		* @access public
		*************************************************************************************/
		function ConsultarAutorizaciones($datos)
		{
			(empty($datos))? $this->request = $_REQUEST: $this->request = $datos;
			
			$this->action['volver'] = SessionGetVar("ActionVolver");
			$this->action['buscar'] = ModuloGetURL('app','NCAutorizaciones','user','FormaConsultarAutorizaciones');
			$this->action['crear'] = ModuloGetURL('app','NCAutorizaciones','user','FormaMostrarDatosIngreso');
			
			$this->_SetActionCerrar("javascript:CerrarVentana(".$this->request['ingreso'].")");
			$this->SetClaseAutorizacion("AD");
			
			$this->buscador = true;
			if(SessionIsSetVar("Buscador")) $this->buscador = SessionGetVar("MostrarBuscador");
			
			return true;
		}
	}
?>