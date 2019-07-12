<?php
	/**************************************************************************************
	* $Id: app_Facturar_user.php,v 1.2 2010/11/25 18:24:34 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* $Revision: 1.2 $
	***************************************************************************************/
	class app_Facturar_user extends classModulo
	{
		var $request = array();
		var $action = array('aceptar'=>'','cancelar'=>'');
		var $datos = array();
		
		function app_Facturar_user(){		}
		/*************************************************************************************
		* @access private
		*************************************************************************************/
		function Main()
		{
			$this->EliminarVariablesSession();
			
			//$this->SetBuscador(true);
			$this->SetActionVolver(ModuloGetURL('system','Menu'));
			$this->PrincipalFacturar();
			return true;
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
		}
		/*************************************************************************************
		* @access private
		*************************************************************************************/
		function SetVerificarFactura($Cuenta,$estado)
		{
			IncludeClass('Facturar','','app','Facturar');
			$VerificarFactura = new Facturar();
			$Verificar = $VerificarFactura->VerificarFactura($Cuenta,$estado);
			return $Verificar;
		}

		function SetFacturaAgrupada($PlanId)
		{
			IncludeClass('Facturar','','app','Facturar');
			$VerificarFactura = new Facturar();
			$Verificar = $VerificarFactura->FacturaAgrupada($PlanId);
			return $Verificar;
		}

		function SetBuscarTotalPaciente($Cuenta)
		{
			IncludeClass('Facturar','','app','Facturar');
			$VerificarFactura = new Facturar();
			$Verificar = $VerificarFactura->BuscarTotalPaciente($Cuenta);
			return $Verificar;
		}

		function SetSaldoPaciente($Empresa,$Cuenta,$PlanId)
		{
			IncludeClass('Facturar','','app','Facturar');
			$VerificarFactura = new Facturar();
			$Verificar = $VerificarFactura->SaldoPaciente($Empresa,$Cuenta,$PlanId);
			return $Verificar;
		}

		function LlamaFacturarCuenta()
		{
			IncludeClass('Facturar','','app','Facturar');
			$Facturar = new Facturar();
			$Verificar = $Facturar->FacturarCuenta(&$this);
			return $Verificar;
		}

		function LlamaGetDatosCuenta($Cuenta)
		{
			IncludeClass('Facturar','','app','Facturar');
			$Facturar = new Facturar();
			$Verificar = $Facturar->GetDatosCuenta($Cuenta);
			return $Verificar;
		}

		function LlamaAjustarCuenta()
		{
			//array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado,'Saldo'=>$s);
			IncludeClass('Facturar','','app','Facturar');
			$ajustar = new Facturar();
			$Verificar = $ajustar->AjustarCuenta(&$this);
			return $Verificar;
		}

		function LlamaCerrarCuenta()
		{
			IncludeClass('Facturar','','app','Facturar');
			$Cerrar = new Facturar();
			$Verificar = $Cerrar->CerrarCuenta($_REQUEST['arreglo'],&$this);
			return $Verificar;
		}

		function LlamaCuadrarFactura()
		{
			IncludeClass('Facturar','','app','Facturar');
			$cuadrar = new Facturar();
			$Verificar = $cuadrar->CuadrarFactura($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['Estado'],&$this);
			return $Verificar;
		}

		function LlamaFormaFacturarImpresion($EmpresaId,$mensaje,$Cuenta,$prefijoPac,$facturaPac,$prefijoCli,$facturaCli,$PlanId,$sw_tipo_plan)
		{
 			if($_REQUEST['Mensaje'])
			{
				$EmpresaId=$_REQUEST['EmpresaId'];
				$mensaje=$_REQUEST['Mensaje'];
				$Cuenta=$_REQUEST['Cuenta'];
				$prefijoPac=$_REQUEST['prefijoPac'];
				$facturaPac=$_REQUEST['facturaPac'];
				$prefijoCli=$_REQUEST['prefijoCli'];
				$facturaCli=$_REQUEST['facturaCli'];
				$PlanId=$_REQUEST['PlanId'];
			}

			IncludeClass('FacturarHTML','','app','Facturar');
			$facturar = new FacturarHTML();
			$this->salida  = $facturar->FormaFacturarImpresion($EmpresaId,$mensaje,$Cuenta,$prefijoPac,$facturaPac,$prefijoCli,$facturaCli,$PlanId,&$this,$sw_tipo_plan);
			return true;
		}

		function LlamarVentanaFinal($boton=false)
		{
			$cont='app';
			$mod='Facturar';
			$tipo='user';
			$metodo='LlamaFormaFacturarImpresion';
			$array=array('EmpresaId'=>$_REQUEST['EmpresaId'],'Cuenta'=>$_REQUEST['numerodecuenta'],'TipoId'=>$_REQUEST['tipoid'],'PacienteId'=>$_REQUEST['pacienteid'],'PlanId'=>$_REQUEST['plan_id'],'prefijoPac'=>$_REQUEST['prefijoPac'],'facturaPac'=>$_REQUEST['facturaPac'],'prefijoCli'=>$_REQUEST['prefijoCli'],'facturaCli'=>$_REQUEST['facturaCli'],'Mensaje'=>$_REQUEST['Mensaje']);
			$accion=ModuloGetURL($cont,$mod,$tipo,$metodo,$array);

			if(!empty($_REQUEST['numerodecuenta']) AND $_REQUEST['tiporeporte']=='reportes' AND !empty($_REQUEST['reporteshojacargos']))
			{
					$dat=explode(',',$_REQUEST['reporteshojacargos']);
					$boton=$_REQUEST['tiporeporte'];
					$msg=$dat[1].' GENERADA SATISFACTORIAMENTE';
					$arreglo=array('cuenta'=>$_REQUEST['numerodecuenta'],'plan_id'=>$_REQUEST['plan_id'],'tipoid'=>$_REQUEST['tipoid'],'pacienteid'=>$_REQUEST['pacienteid'],'switche_emp'=>$a,'ruta_hoja'=>$dat[0]);
			}
			$this->FormaMensaje($msg,'CONFIRMACION',$accion,'Volver',$boton,$arreglo);
			return true;
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
				echo $this->frmError['MensajeError']  = "EL VALOR DE LA CLASE DE AUTORIZACION DEBE SER AD->ADMISION U OS->ORDEN DE SERVICIO";
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
	}
?>