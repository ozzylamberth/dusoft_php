<?php
	/*********************************************************************************************
	* $Id: app_Cuentas_user.php,v 1.14 2011/07/25 20:37:18 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.14 $
	*
	* @autor Hugo F. Manrique
	***********************************************************************************************/
	IncludeClass('BuscarCargoIYMHTML','','app','Cuentas');
	IncludeClass('OpcionesCuentasHTML','','app','Cuentas');
	IncludeClass('OpcionesCuentas','','app','Cuentas');
	IncludeClass('AgregarCargosQXHTML','','app','Cuentas');
	IncludeClass('HabitacionesHTML','','app','Cuentas');
	class app_Cuentas_user extends classModulo
	{
		/**
		* @var $action array Variable para guardar los links
		***/
		var $action = array();
		/**
		* @var $request array Variable para guardar los datos del request
		***/
		var $request = array();
		
		function app_Cuentas_user(){}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function SetActionVolver($link)
		{
			SessionDelVar("Cuentas_ActionVolver");
			SessionSetVar("Cuentas_ActionVolver",$link);
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function SetDatosCaja($datos)
		{
			SessionDelVar("DatosCaja");
			SessionSetVar("DatosCaja",$datos);
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function SetDatosEmpresa($EmpresaId,$CentroUtilidadId)
		{
			SessionDelVar("DatosEmpresaId");
			SessionSetVar("DatosEmpresaId",$EmpresaId);
			SessionDelVar("DatosCentroUtilidadId");
			SessionSetVar("DatosCentroUtilidadId",$CentroUtilidadId);
		}
		/****************************************************************************
		* Funcion donde se busca si el usuario posee o no permisos de entrada al
		* modulo
		*****************************************************************************/
		function Main()
		{
			$cnt = AutoCarga::factory('Cuenta','','app','Cuentas');
			$datos = $cnt->ObtenerPermisos(UserGetUID());
      
			$this->documentos = $cnt->ObtenerTiposDocumentos($datos['empresa'][0]);
			return true;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function BuscarCuentas()
		{
			$this->request = $_REQUEST;
			$this->rqst = $this->request['buscador'];
			
			IncludeClass('Cuenta','','app','Cuentas');
			$anc = new Cuenta();
			
			if($this->request['Cuenta'])
				$this->SetDatosCaja($this->request['Cuenta']);
			if($this->request['EmpresaId'] AND $this->request['CentroUtilidadId'])
				$this->SetDatosEmpresa($this->request['EmpresaId'],$this->request['CentroUtilidadId']);
			
		
			$this->Id = $anc->ObtenerTipoIdPaciente();
			
			$this->action['volver'] = ModuloGetURL('app','Cuentas','user','FormaMenu');
			$this->action['buscar'] = ModuloGetURL('app','Cuentas','user','FormaBuscarCuentas');			
			
			if(!empty($this->rqst)) $this->cuentas = $this->ObtenerCuentas($this->rqst,$anc);
		}
    
		/**********************************************************************************
		* 
		* @return boolean Indica si tiene permisos true, o no false
		***********************************************************************************/
		function ObtenerCuentas($buscador,$obj)
		{
			$cuentas = array();
			
			if($buscador['Cuenta'] || $buscador['Ingreso'])
				$cuentas = $obj->ObtenerCuentasXIngreso($buscador,$dat_empresa['empresa_id'],null,$this->request['offset']);
			else if($buscador['Documento'])
				$cuentas = $obj->ObtenerCuentasXIdPaciente($buscador,$dat_empresa['empresa_id'],null,$this->request['offset']);
				else if($buscador['Nombres'] || $buscador['Apellidos'])
					$cuentas = $obj->ObtenerCuentasXNombrePaciente($this->rqst,$dat_empresa['empresa_id'],null,$this->request['offset']);
					else
						$cuentas = $obj->ObtenerCuentas($buscador,$dat_empresa['empresa_id'],null,$this->request['offset']);
			
      $this->conteo = $obj->conteo;
      $this->pagina = $obj->paginaActual;
			$datos['conteo'] = $obj->conteo;
			$datos['buscador'] = $buscador;
      
			$this->action['pagina'] = ModuloGetURL('app','Cuentas','user','FormaBuscarCuentas',$datos);			
			$this->action['ver_cn'] = ModuloGetURL('app','Cuentas','user','FormaMostrarCuenta',$datos);			
			//$this->action['anular'] = ModuloGetURL('app','Cuentas','user','FormaAnularCuenta');			
			
			return $cuentas;
		}
    
    function LlamaEstablecerOrdenNoEjecutada()
    {
      $request = $_REQUEST;
      IncludeClass('CuentaHTML','','app','Cuentas');
      $cnth = new CuentaHTML();
      $this->salida .= $cnth->FormaEstablecerOrdenNoEjecutada($request);
      
      return true;
    } 
     
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function MostrarCuenta($numerocuenta)
		{
			$this->request = $_REQUEST;
			
			if($numerocuenta) $this->request['Cuenta'] = $numerocuenta;
				
			if(SessionIsSetVar("Cuentas_ActionVolver"))
				$this->action['volver'] =  SessionGetVar("Cuentas_ActionVolver");
			else
			if(SessionIsSetVar("Cuentas_ListatoPacinetesConSAlida"))
				$this->action['volver'] =  SessionGetVar("Cuentas_ListatoPacinetesConSAlida");
			else
				$this->action['volver'] =  ModuloGetURL('app','Cuentas','user','FormaBuscarCuentas',array("buscador"=>$this->request['buscador']));
			
			IncludeClass('Cuenta','','app','Cuentas');
			$cnt = new Cuenta();
			
			$this->caja = SessionGetVar("DatosCaja");
			$this->cuentas = $cnt->ObtenerInformacionCuenta($this->request['Cuenta'],$dat_empresa['empresa_id']);
			//estos action llegan a todas la formas, es qui donde se declaran        
			$this->action['cambiar'] = ModuloGetURL('app','Cuentas','user','FormaCambiarValores');			
			$this->action['facturar'] = ModuloGetURL('app','Cuentas','user','FormaCrearFactura',array("numerodecuenta"=>$this->request['Cuenta']));			
      $this->action['ModificarCargo']=ModuloGetURL('app','Cuentas','user','LlamaFormaModificar'); 
      $this->action['EliminarCargo']=ModuloGetURL('app','Cuentas','user','LlamarFormaEliminarCargo'); 
      $this->action['DevolverIYM']=ModuloGetURL('app','Cuentas','user','LlamaFormaDevolverIYMCta');                                
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function CambiarValores()
		{
			$this->request = $_REQUEST;
			
			$datos['numerodecuenta'] = $this->request['numerodecuenta'];
			$datos['tipo_cambio'] = $this->request['tipo_cambio'];
			$datos['id_campo'] = $this->request['id_campo'];
			
			$this->action['cancelar'] = "javascript:window.close();"; 
			$this->action['aceptar'] = ModuloGetURL('app','Cuentas','user','FormaIngresarCambioValores',$datos); 
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function IngresarCambioValores()
		{
			$this->request = $_REQUEST;
			IncludeClass('CuentaDetalle','','app','Cuentas');
			IncludeClass('Cuenta','','app','Cuentas');
			$cntd = new CuentaDetalle();
			$cnt = new Cuenta();
			$nombre = "";
			$titulo = "";
			
			switch($this->request['tipo_cambio'])
			{
				case '1': 
					$nombre = "copago"; 
					$titulo = "DE EL COPAGO";
				break;
				case '2': 
					$nombre = "cuota_moderadora";	
					$titulo = "DE LA CUOTA MODERADORA";
				break;
			}
			
			$cont = $cntd->ObtenerDatosCuotaCopago($this->request,$nombre);
			
			if(!$cont || $cont == 0)
				$rst = $cntd->IngresarCuotaCopago($this->request,$nombre);
			else
				$rst = $cntd->ActualizarCuotaCopago($this->request,$nombre);
		
			if($rst)
				$this->frmError['MensajeError'] = "EL VALOR ".$titulo.", FUE MODIFICADO ";
			else
				$this->frmError['MensajeError'] = $cntd->frmError['MensajeError'];
			
			$this->cuentas = $cnt->ObtenerInformacionCuenta($this->request['numerodecuenta'],$dat_empresa['empresa_id']);
			
			//$this->action['aceptar'] .= "javascript:window.close();";
			$this->action['aceptar'] .= "javascript:window.opener.location = '".SessionGetVar("AccionVolverCargos")."';window.close();";
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function PagarCuenta()
		{
			$datos = array();
			$this->request = $_REQUEST;	
			
			$datos['opcion'] = $this->request['opcion'];
			$datos['label'] = $this->request['label'];
			$datos['numerodecuenta'] = $this->request['numerodecuenta'];
			
			$this->action['aceptar'] = ModuloGetURL('app','Cuentas','user','FormaIngresarPagos',$datos);  
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function IngresarPagos()
		{
			$rst = false;
			$this->request = $_REQUEST;
			
			IncludeClass('Caja','','app','Cuentas');
			$cj = new Caja();
			$caja = SessionGetVar("DatosCaja");
			
			switch($this->request['opcion'])
			{
				case 'C':
					$rst = $cj->IngresarTemporalCheques($this->request,$caja['empresa_id'],$caja['centro_utilidad']);
				break;
				case 'R':
					$rst = $cj->IngresarTemporalTrajetaCredito($this->request,$caja['empresa_id'],$caja['centro_utilidad']);
				break;
				case 'D':
					$rst = $cj->IngresarTemporalTrajetaDebito($this->request,$caja['empresa_id'],$caja['centro_utilidad']);
				break;
			}
			$this->frmError['MensajeError'] = $cj->frmError['MensajeError'];
			return $rst;
		}

		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function CrearFactura()
		{
			IncludeClass('Cuenta','','app','Cuentas');
			IncludeClass('Caja','','app','Cuentas');
			
			$pago = 0;
			$this->request = $_REQUEST;
			
			if($this->request['h_total']) $pago = $this->request['h_total'];
			
			$cj = new Caja();
			$cnt = new Cuenta();
			
			$this->cuenta = $cnt->ObtenerInformacionCuenta($this->request['numerodecuenta'],$dat_empresa['empresa_id']);
			$fc_cliente = $this->cuenta['valor_total_paciente'] - $pago;
			
			if($fc_cliente > 0)
			{
				$cj->EliminarTemporales($this->request['numerodecuenta']);
			
				$this->action['pagar'] = ModuloGetURL('app','Cuentas','user','FormaPagarCuenta',array("valor_pago"=>$this->cuenta['valor_total_paciente'],"numerodecuenta"=>$this->cuenta['numerodecuenta']));			
				$this->action['cancelar'] = ModuloGetURL('app','Cuentas','user','FormaMostrarCuenta',array("Cuenta"=>$this->request['numerodecuenta']));			
				$this->action['aceptar'] = ModuloGetURL('app','Cuentas','user','FormaCrearFactura',array("numerodecuenta"=>$this->request['numerodecuenta']));			
			}
			else
			{
				$this->caja = SessionGetVar("DatosCaja");
				$this->retorno = $cj->IngresoPagosCuenta($this->cuenta,$this->request,$this->caja);
				$this->action['imprimir_pos'] = ModuloGetURL('app','Cuentas','user','FormaCrearFactura',array("numerodecuenta"=>$this->request['numerodecuenta']));			
				
				if(SessionIsSetVar("Cuentas_ActionVolver"))
					$this->action['volver'] =  SessionGetVar("Cuentas_ActionVolver");
				else
					$this->action['volver'] =  ModuloGetURL('app','Cuentas','user','FormaBuscarCuentas',array("buscador"=>$this->request['buscador']));

				if($this->retorno === false)
				{
					$this->frmError = $caja->frmError;
					return false;
				}
			}
			return $fc_cliente;
		}
		
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function ReportesPos()
		{
			IncludeClass('Reporte','','app','Cuentas');
			$rpt = new Reporte();
			
			$this->request = $_REQUEST;
			
			if (!IncludeFile("classes/reports/reports.class.php")) 
			{
				$this->error = "No se pudo inicializar la Clase de Reportes";
				$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
				return false;
			}
			
			$datos = array();
			$classReport = new reports;

			$impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte='pos');

			$encabezado = $rpt->ObtenerEncabezadoFactura($this->request['numerodecuenta']);
			$datos = $rpt->ObtenerFactura($cuenta);
			
			if(!empty($datos))
			{
				$datos[0] = $encabezado;

				$reporte = $classReport->PrintReport('pos','app','Cuentas','Factura',$datos,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
				if(!$reporte)
				{
					$this->frmError['MensajeError']  = $classReport->GetError();
					$this->frmError['MensajeError'] .= "<br>".$classReport->MensajeDeError();
					return false;
				}
				$resultado = $classReport->GetExecResultado();
			}
			
			if($this->request['factura_empresa'] == '1')
			{
				$datos = $rpt->ObtenerFacturasEmpresa($this->request['numerodecuenta']);
				$datos[0] = $encabezado;
				
				$impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
				$reporte = $classReport->PrintReport('pos','app','Cuentas','Factura',$datos,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
				if(!$reporte)
				{
					$this->frmError['MensajeError']  = $classReport->GetError();
					$this->frmError['MensajeError'] .= "<br>".$classReport->MensajeDeError();
					return false;
				}
				$resultado = $classReport->GetExecResultado();
			}
			$this->frmError['MensajeError'] .= $rpt->frmError['MensajeError'];
			
			if(empty($this->frmError['MensajeError']))
				$this->frmError['MensajeError'] = "LA FACTURA, SE HA GENERADO CORRECTAMENTE";
			
			return true;
		}
    
    /* 
    *  Llama la funci?n que actualiza el estado de las solicitudes y ?rdenes de servicio que no ser?n ejecutadas, al momento del cierre. 
    */
    function LlamaInsertarSolicitudesOrdenesNoEjecutadas()
    {
      IncludeClass('Cuenta','','app','Cuentas');
      $cnt = new Cuenta();
        
      $request = $_REQUEST;  
      
      $EstadoInserta = $cnt->RegistrarSolicitudesOrdenesANoEjecutar($request);
          
      $accion=SessionGetVar('ActionVolver');   
          
      if($EstadoInserta==1)
      {
        IncludeClass('CuentaHTML','','app','Cuentas');
        $cnth = new CuentaHTML();
        
        $mensaje = " SU SOLICITUD FUE REALIZADA SATISFACTORIAMENTE. ";
        
        $this->salida.=  $cnth->FormaEstablecerOrdenNoEjecutada($request, $mensaje);
      }
      if($EstadoInserta==2)      
      {
        IncludeClass('LiquidacionHabitacionesCta','','app','Cuentas');
        $objeto = new LiquidacionHabitacionesCta();        
        $objeto->CancelarCargueHabitacionCuenta();  
        if(!$this->FormaMostrarCuenta(&$this,$_REQUEST['Cuenta']))
        {
					return false;
        }
        return true;  
      } 
            
      return true;
    }

		//METODOS PARA AGREGAR CARGOS,INSUMOS / MEDICAMENTOS / OPCIONES / INFORMACION /FACTURAR
		/**
		**/
		function LlamaInsertarCargos()
		{
			IncludeClass('AgregarCargos','','app','Cuentas');
			IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');			
			//$this->SetXajax(array("reqObtenerDatosIyM","reqAdicionarDatosIyM","reqEliminarDatosIyM","reqObtenerDatosCargos","reqAdicionarDatosCargos","reqEliminarDatosCargos"));
			//$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM"));
			$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));
			$fact = new AgregarCargos();
			$this->salida .= $fact->InsertarCargosTmp($_REQUEST[obj],$_REQUEST[EmpresaId],$_REQUEST[CU],$_REQUEST[PlanId],$_REQUEST[Cuenta]);
			return true;
		}

		/**
		**/
		function LlamaInsertarCargosIyM()
		{
			IncludeClass('BuscarCargoIYM','','app','Cuentas');
			
      IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');			
			//$this->SetXajax(array("reqObtenerDatosIyM","reqAdicionarDatosIyM","reqEliminarDatosIyM","reqObtenerDatosCargos","reqAdicionarDatosCargos","reqEliminarDatosCargos"));
			//$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM"));
			$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"),null,'ISO-8859-1');
			$fact = new BuscarCargoIYM();
			$this->salida .= $fact->InsertarInsumos($_REQUEST[obj],$_REQUEST[EmpresaId],$_REQUEST[CU],$_REQUEST[PlanId],$_REQUEST[Cuenta]);
			return true;
		}

		/**
		**/
		function LlamaPideDatosAdicionalesRips()
		{
			IncludeClass('AgregarCargosHTML','','app','Cuentas');
			IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');			
			//$this->SetXajax(array("reqObtenerDatosIyM","reqAdicionarDatosIyM","reqEliminarDatosIyM","reqObtenerDatosCargos","reqAdicionarDatosCargos","reqEliminarDatosCargos"));
			//$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM"));
			$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));
			$dat = new AgregarCargosHTML();
        
			$fact=$dat->PideDatosAdicionalesRips();
			foreach($_SESSION['CUENTAS']['ADD_CARGOS'] AS $i => $v)
			{
				foreach($fact AS $i1 => $v1)
				{
					if($v1[departamento]==$v[departamento]
							AND $v1[cups]==$v[codigo])
					{
						UNSET($_SESSION['CUENTAS']['ADD_CARGOS'][$i]);
					}
				}
			}
			$fact = new AgregarCargos();
			if(sizeof($_SESSION['CUENTAS']['ADD_CARGOS'])>0)
			{
				$_REQUEST['datos']='pidedatos';
          
				$this->salida .= $fact->InsertarCargosTmp();
				return true;
			}
			else
			{
				//$_REQUEST['datos']='adiciona';
				$fact = new AgregarCargos();
				$this->salida .= $fact->GuardarTodosCargos($_REQUEST['EmpresaId'],$_REQUEST[CU],$_REQUEST[PlanId],$_REQUEST['Cuenta']);
				return true;
			}
		}

		/**
		**/
		function LlamaInsertarCargoTmpEquivalencias()
		{
			IncludeClass('AgregarCargos','','app','Cuentas');
			IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');			
			//$this->SetXajax(array("reqObtenerDatosIyM","reqAdicionarDatosIyM","reqEliminarDatosIyM","reqObtenerDatosCargos","reqAdicionarDatosCargos","reqEliminarDatosCargos","reqBuscarDatosCargos"));
			$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));
			$dat = new AgregarCargos();
			$this->salida .= $dat->InsertarCargoTmpEquivalencias();
			return true;
		}

		/**
		**/
		function LlamaFormaMensaje(&$obj,$Cuenta,$mensaje,$titulo,$accion,$boton)
		{
			$frm = new BuscarCargoIYMHTML();
			$html = $frm->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return $html;
		}

		/**
		**/
		function LlamaCrearDescuentos()
		{
			$Cuenta=$_REQUEST['Cuenta'];
			$fact = new OpcionesCuentasHTML();
			$this->salida = $fact->FormaDescuentos($Cuenta);
			return true;
		}

		/**
		**/
		function LlamaGuardarDescuentos()
		{
			$fact = new OpcionesCuentas();
			$this->salida = $fact->GuardarDescuentos($Cuenta);
			return true;
		}

		/**
		**/
		function LlamaNuevoResponsable()
		{
			$fact = new OpcionesCuentas();
			$this->salida = $fact->NuevoResponsable($_REQUEST[Cuenta],$_REQUEST[Ingreso],$_REQUEST[PlanId],$_REQUEST[TipoId],$_REQUEST[PacienteId],$_REQUEST[Responsable]);
			return true;
		}

		/**
		*
		*/
		function LlamaGuardarNuevoPlan()
		{	
			$fact = new OpcionesCuentas();
			$this->salida = $fact->GuardarNuevoPlan($_REQUEST[PlanId],$_REQUEST[Cuenta],$_REQUEST[Ingreso],$_REQUEST[TipoId],$_REQUEST[PacienteId],$Nivel,$Fecha,$_REQUEST[Responsable]); 
			return true;    
		} 

	/**
	*
	*/
		function LlamaInsertarNuevoPlan()
		{
			$Cuenta=$_REQUEST['Cuenta'];
			$TipoId=$_REQUEST['TipoId'];
			$PacienteId=$_REQUEST['PacienteId'];
			$Nivel=$_REQUEST['Nivel'];
			$PlanId=$_REQUEST['PlanId'];
			$Ingreso=$_REQUEST['Ingreso'];
			$Fecha=$_REQUEST['Fecha'];
			$Nuevo_Responsable=$_REQUEST['Nuevo_Responsable'];
			$fact = new OpcionesCuentas();
			$this->salida =  $fact->InsertarNuevoPlan($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$Nuevo_Responsable);
			return true;    
		}

		/**
		*
		*/
//      function LlamaFormaEquivalencias($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha,$Responsable)
//      {
//        $fact = new OpcionesCuentasHTML();
//        $this->salida =  $fact->FormaEquivalencias($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha,$Responsable);
//        return true;    
//      }

		/**
		*
		*/
     function LlamaGuardarEquivalencias()
     {
       $fact = new OpcionesCuentas();
       $this->salida =  $fact->GuardarEquivalencias();
       return true;    
     }

		/**
		*
		*/
		function LlamarFormaEquivalencias()
		{
				$fact = new OpcionesCuentasHTML();
				$this->salida = $fact->FormaEquivalencias($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha'],$_REQUEST['Nuevo_Responsable']);
				return true;
		}
		
		/**
		*
		*/
		function LlamaInactivarCuenta()
		{
				$fact = new OpcionesCuentas();
				$this->salida = $fact->InactivarCuenta();
				return true;
		}

		/**
		*
		*/
		function LlamaOpcionActivarCuenta()
		{
				$fact = new OpcionesCuentas();
				$this->salida = $fact->OpcionActivarCuenta();
				return true;
		}
    /**
		*
		*/
		function LlamaOpcionFechaIngreso()
		{
				$fact = new OpcionesCuentas();
        //print_r($_REQUEST);
				$this->salida = $fact->OpcionFechaIngreso();
				return true;
		}

		/**
		*
		*/
		function LlamaReliquidarMedicamentos()
		{
				$fact = new OpcionesCuentas();
				$this->salida = $fact->ReliquidarMedicamentos();
				return true;
		}

		/**
		*LlamaReliquidarCargos
		*/
		function LlamaReliquidarCargos()
		{
				$fact = new OpcionesCuentas();
				$this->salida = $fact->ReliquidarCargos();
				return true;
		}

		/**
		*LlamaReliquidarCargos
		*/
		function LlamaReliquidar()
		{
				$fact = new OpcionesCuentas();
				$this->salida = $fact->Reliquidar();
				return true;
		}

		/**
		*LlamaTiposDivision
		*/
		function LlamaTiposDivision()
		{
				IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
				$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));
				$fact = new OpcionesCuentas();
				$this->salida = $fact->TiposDivision();
				return true;
		}
		
		/**
		*LLamaTiposCortes
		*/
		function LLamaTiposCortes()
		{
				IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
				$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));
				$fact = new OpcionesCuentas();
				$this->salida = $fact->TiposCortes();
				return true;
		}
		
		/**
		*LlamarFormaListadoCorte
		*/
		function LlamarFormaListadoCorte()
		{
			$fact = new OpcionesCuentas();
			$this->salida = $fact->LlamarFormaListadoCorte($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],'');
			return true;
		}
		
		
		function LlamaInsertarCorteCuenta()
		{
			$fact = new OpcionesCuentas();
			$this->salida = $fact->InsertarCorteCuenta($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],'');
			return true;
		}
		/**
		*LlamaBuscarDivision
		*/
		function LlamaBuscarDivision()
		{
				//$file = 'app_modules/Cuentas/RemoteXajax/DivisiondeCuentas.php';
				IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
				$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));

				$fact = new OpcionesCuentasHTML();
				$this->salida = $fact->BuscarDivision();
				return true;
		}
		
		function LlamaDivisionCuenta()
		{
				
        IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
				$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));

				$fact = new OpcionesCuentasHTML();
				$this->salida = $fact->SeleccionCriterios();
				return true;
		}

		/**
		*LlamaBuscarDivision
		*/
		function LlamaBuscarCortes()
		{
				//$file = 'app_modules/Cuentas/RemoteXajax/DivisiondeCuentas.php';
				IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
				$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));

				$fact = new OpcionesCuentasHTML();
				$this->salida = $fact->BuscarCorte();
				return true;
		}

		/**
		*LlamaBuscarDivision
		*/
		function LlamaFormaListadoDivision()
		{
				//$file = 'app_modules/Cuentas/RemoteXajax/DivisiondeCuentas.php';
				IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
				$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));
				$fact = new OpcionesCuentasHTML();
				$this->salida = $fact->FormaListadoDivision();
				return true;
		}

		/**
		*InsertarDivisionCuenta
		*/
		function LlamaInsertarDivisionCuenta()
		{
				IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
				$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));
        $fact = new OpcionesCuentas();
				$this->salida = $fact->InsertarDivisionCuenta();
				return true;
		}

		/**
		*LlamaCortesCuenta
		*/
		function LlamaCortesCuenta()
		{
				IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
				$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));
				$fact = new OpcionesCuentasHTML();
				$this->salida = $fact->CortesCuenta();
				return true;
		}
		/**
		*
		*/
		function LlamaFinalizarDivision()
		{
				IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
				$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));
				$fact = new OpcionesCuentas();
				$this->salida = $fact->FinalizarDivision();
				return true;
		}

		/**
		*
		*/
		function LlamarFormaActivarCuentaDivision()
		{
				IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
				$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));
				$fact = new OpcionesCuentasHTML();
				$this->salida = $fact->FormaActivarCuentaDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta1'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['Corte']);
				return true;
		}

		/**
		*
		*/
		function ActivarCuentaDivision()
		{
			IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
			$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));
					if(empty($_REQUEST['CuentaA']))
					{
							$this->frmError["MensajeError"]="Debe Elegir una Cuenta Para Activar.";
							$fact = new OpcionesCuentasHTML();
							$this->salida = $fact->FormaActivarCuentaDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta1'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha']);
							return true;
					}

					$fact = new OpcionesCuentas();
					$this->salida = $fact->ActivarCuenta($_REQUEST['PlanId'],$_REQUEST['CuentaA'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha']);
					return true;
		}

		/**
		*
		*/
		function LlamaConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2)
		{
			$_SESSION['CUENTAS']['CORTE']['REQUEST'] = $_REQUEST;
			IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
			$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));
				if($_REQUEST[mensaje] AND $_REQUEST[arreglo])
				{
					$mensaje=$_REQUEST[mensaje];
					$arreglo=$_REQUEST[arreglo];
					$c=$_REQUEST[c];
					$m=$_REQUEST[m];
					$me=$_REQUEST[me];
					$me2=$_REQUEST[me2];
					$Titulo=$_REQUEST[titulo];
					$boton1=$_REQUEST[boton1];
					$boton2=$_REQUEST[boton2];
				}
				$fat = new OpcionesCuentas();
				$this->salida = $fat->LlamaConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
				return true;
		}

		/**
		*
		*/
		function LlamaActivarCuentaDivision()
		{
				$fact = new OpcionesCuentasHTML();
				$this->salida = $fact->ActivarCuentaDivision();
				return true;
		}
        
		/**
		*
		*/
		function LlamarModuloSoat($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha)
		{
				unset($_SESSION['SOAT']);
				$_SESSION['SOAT']['PACIENTE']['paciente_id']=$_REQUEST[PacienteId];
				$_SESSION['SOAT']['PACIENTE']['tipo_id_paciente']=$_REQUEST[TipoId];
				$_SESSION['SOAT']['CUENTA']=TRUE;
				$_SESSION['SOAT']['RETORNO']['argumentos']=array('Cuenta'=>$_REQUEST[Cuenta],'TipoId'=>$_REQUEST[TipoId],'PacienteId'=>$_REQUEST[PacienteId],'Nivel'=>$_REQUEST[Nivel],'PlanId'=>$_REQUEST[PlanId],'Fecha'=>$_REQUEST[Fecha],'Ingreso'=>$_REQUEST[Ingreso],'Responsable'=>$_REQUEST[Responsable]);
				$_SESSION['SOAT']['RETORNO']['contenedor']='app';
				$_SESSION['SOAT']['RETORNO']['modulo']='Cuentas';
				$_SESSION['SOAT']['RETORNO']['tipo']='user';
				$_SESSION['SOAT']['RETORNO']['metodo']='LlamaGuardarNuevoPlan';

				$this->ReturnMetodoExterno('app','Soat','user','SoatAdmision');
				return $this->salida;
		}

		/**
		*
		*/
    function LlamaRealizarPaquetesCargos()
		{
      $file = 'app_modules/Cuentas/RemoteXajax/PaquetesCargosCuentas.php';
			$this->SetXajax(array("FacturarCargoPaquete","AdicionarCargoPaquete","InsertarCargoPaquete","EliminarCargoPaquete","InsertarNuevoPaquete","InsertarCargoNuevoPaquete","EliminarVistaCargosCuenta","InsertarTodosCargosPaquete"),$file);
			IncludeClass('PaquetesCargosCtaHTML','','app','Cuentas');
			$html = new PaquetesCargosCtaHTML();
			$this->salida = $html->CrearFormaPaquetesCargosCta($_REQUEST[Cuenta],$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$Estado);                                       
			return true;    
		} 

		/**
		*
		*/
		function LlamaFormaMostrarCuenta(&$obj,$cuenta)
		{
			if($cuenta)
			{
				$_REQUEST[Cuenta] = $cuenta;
			}
		$this->FormaMostrarCuenta(&$this,$_REQUEST[Cuenta]);                                       
			return true;    
		} 

		//FIN METODOS PARA AGREGAR CARGOS,INSUMOS / MEDICAMENTOS  / OPCIONES / INFORMACION /FACTURAR
	
    /*************************************************************************
		/**********METODOS PARA AGREGAR PROCEDIMIENTOS QX
    /*************************************************************************
		
		/**
		*
		*/
		function LlamaInsertarDatosReqLiquidacion()
		{
			$fact = new AgregarCargosQXHTML();
			$this->salida = $fact->InsertarDatosReqLiquidacion(&$this,$_REQUEST[Cuenta]);                                       
			return true;    
		} 

		function LlamaSeleccionProfesionalBuscador()
		{
			if($_REQUEST[Volver])
			{
				$this->FormaMostrarCuenta(&$obj,$_REQUEST[cuenta]);
			}
			else
			{
				$fact = new AgregarCargosQXHTML();
				$this->salida = $fact->SeleccionProfesionalBuscador(&$this,$_REQUEST[Cuenta]);                                       
			}
				return true;    
		} 

		function LlamaEliminarCirDatosReqLiquidacion()
		{
				$fact = new AgregarCargosQXHTML();
				$this->salida = $fact->EliminarCirDatosReqLiquidacion();                                       
				return true;    
		} 

    /*************************************************************************
		/**********FIN METODOS PARA AGREGAR PROCEDIMIENTOS QX
    /*************************************************************************

    /*************************************************************************
    * Llama la forma para modificar un cargo de la cuenta.
    *
    * @ access public
    * @ return boolean
    ********************************************************************/
    function LlamaFormaModificar(){       
     
      if(!$this->FormaModificarCargo($_REQUEST['Transaccion'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Cuenta'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['Datos'],$mensaje,$_REQUEST['Apoyo'])){
        return false;
      }
      return true;
    }
    
    /**************************************************************************
    * Modifica un cargo de la cuenta en cuenta_detalles.
    *
    * @ access public
    * @ return boolean
    ****************************************************************************/
    function ValidarModificarCargo(){            
	        
				IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
				$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));

        $_REQUEST['ValorPac']=str_replace(".","",$_REQUEST['ValorPac']);
        $_REQUEST['ValorEmp']=str_replace(".","",$_REQUEST['ValorEmp']);              
        
        IncludeClass('ModificacionCargo','','app','Cuentas'); 
        $objeto = new ModificacionCargo;                             
        if($objeto->ModificarCargo($_REQUEST['Cuenta'],$_REQUEST['PlanId'],$_SESSION['CUENTAS']['EMPRESA'],$_REQUEST['Departamento'],$_REQUEST['Transaccion'],$_REQUEST['TarifarioId'],$_REQUEST['Cargo'],$_REQUEST['Consecutivo'],$_REQUEST['Cantidad'],$_REQUEST['observacion'],$_REQUEST['ValorPac'],$_REQUEST['ValorEmp'],$_REQUEST['FechaCargo'],$_REQUEST['Manual'],$_REQUEST['DescuentoEmp'],$_REQUEST['DescuentoPac'])==true){
          $mensaje='Cargo Modificado Satisfactoriamente';                                         
          if(!$this->FormaMostrarCuenta(&$this,$_REQUEST['Cuenta'])){
            return false;
          }              
          return true;
        }else{
          $mensaje=$objeto->ErrMsg();
          $_REQUEST['ValorPac']=str_replace(".","",$_REQUEST['ValorPac']);
          $_REQUEST['ValorEmp']=str_replace(".","",$_REQUEST['ValorEmp']);
          if(!$this->FormaModificarCargo($_REQUEST['Transaccion'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Cuenta'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['FechaCargo'],$_REQUEST['Ingreso'],$_REQUEST['Datos'],$mensaje)){
            return false;
          }
          return true;
        }       
    }
    
    /**************************************************************************
    * Muestra Forma para eliminar un cargo de la cuenta en cuenta_detalles.
    *
    * @ access public
    * @ return boolean
    ****************************************************************************/
    
    function LlamarFormaEliminarCargo(){      
      $this->FormaEliminarCargo($_REQUEST['Transaccion'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['Datos'],$_REQUEST['des'],$_REQUEST['codigo'],$_REQUEST['doc'],$_REQUEST['numeracion'],$_REQUEST['noFacturado']);
      return true;
    }
    
    /*************************************************************************
    * Elimina un cargo de la cuenta en cuenta_detalles.
    *
    * @ access public
    * @ return boolean
    ***************************************************************************/
    function ValidarEliminarCargo(){

			IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
			$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"));

      if(empty($_REQUEST['observacion'])){
          $this->frmError["observacion"]=1;
          $mensaje="DEBE ESCRIBIR LA JUSTIFICACION.";
          $this->FormaEliminarCargo($_REQUEST['Transaccion'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','','','','','',$mensaje);
          return true;
      }
      
      IncludeClass('EliminaCargo','','app','Cuentas');
      $objeto = new EliminaCargo;  
      
      $this->action['cancelar'] = ModuloGetURL('app','Cuentas','user','FormaMostrarCuenta',array("Cuenta"=>$_REQUEST['Cuenta']));     
      $this->action['RegistrarEliminarCargo']=ModuloGetURL('app','Cuentas','user','EliminarCargoOrdenCumplida',array('Transaccion'=>$_REQUEST['Transaccion'],
      'Cuenta'=>$_REQUEST['Cuenta'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Fecha'=>$_REQUEST['Fecha'],
      'Ingreso'=>$_REQUEST['Ingreso'],"observacion"=>$_REQUEST['observacion'],"Pieza"=>$Pieza,"doc"=>$_REQUEST['doc'],"numeracion"=>$_REQUEST['numeracion'],"qx"=>$_REQUEST['qx'],
      "codigo"=>$_REQUEST['codigo'],"des"=>$_REQUEST['des'],"noFacturado"=>$_REQUEST['noFacturado'],"Consecutivo"=>$_REQUEST['Consecutivo']));            
      $objeto->DefinirExistenciaOS($_REQUEST['Transaccion'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['observacion'],
                                  $Pieza,$_REQUEST['doc'],$_REQUEST['numeracion'],$_REQUEST['qx'],$_REQUEST['codigo'],$_REQUEST['des'],$_REQUEST['noFacturado'],$_REQUEST['Consecutivo'],$this->action);                                                          
                                  
      if($objeto->RecuperarVerificacionOrden()==1){
        IncludeClass('EliminaCargoHTML','','app','Cuentas');        
        $funcion=new EliminaCargoHTML;            
        $this->salida=$funcion->FormaVerificacionAnulacionOS($this->action['RegistrarEliminarCargo'],$this->action['cancelar']);
        return true;                                                  
      }
      if(($objeto->EliminarCargo($_REQUEST['Cuenta'],$_REQUEST['Transaccion'],$_REQUEST['observacion'],$objeto->RecuperarcambioTransaccion(),$objeto->Recuperarordenes_servicio(),$objeto->RecuperarregistrosCargos()))==true){                
        $mensaje='El cargo se elimino.';
        if(!$this->FormaMostrarCuenta(&$this,$_REQUEST['Cuenta'])){
          return false;
        }              
        return true;
      }else{
        $mensaje='Error al eliminar el cargo.';
        $this->FormaEliminarCargo($_REQUEST['Transaccion'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['Datos'],$_REQUEST['des'],$_REQUEST['codigo'],$_REQUEST['doc'],$_REQUEST['numeracion'],$_REQUEST['noFacturado'],$mensaje);
        return true;
      }
    }
    
    function RegistrarEliminarCargo(){
      IncludeClass('EliminaCargo','','app','Cuentas');
      $objeto = new EliminaCargo;  
      if($objeto->EliminarCargoOrdenCumplida($_REQUEST['Cuenta'],$_REQUEST['Transaccion'])==true){
        $mensaje='El cargo se elimino.';
        if(!$this->FormaMostrarCuenta(&$this,$_REQUEST['Cuenta'])){
          return false;
        }                    
        return true;
      }
    }
    
    /*************************************************************************
    * Elimina un cargo de una orden de complimiento
    *
    * @ access public
    * @ return boolean
    ***************************************************************************/
    
    function EliminarCargoOrdenCumplida(){
      IncludeClass('EliminaCargo','','app','Cuentas');
      $objeto = new EliminaCargo;        
      if($objeto->EliminarOSCumplida($_REQUEST['Transaccion'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['observacion'],
                         $Pieza,$_REQUEST['doc'],$_REQUEST['numeracion'],$_REQUEST['qx'],$_REQUEST['codigo'],$_REQUEST['des'],$_REQUEST['noFacturado'],$_REQUEST['Consecutivo'])==true){
        $mensaje='El cargo se elimino.';
        if(!$this->FormaMostrarCuenta(&$this,$_REQUEST['Cuenta'])){
          return false;
        }              
        return true;                                            
      } 
    }
    
    /*************************************************************************
    * Muestra Forma para devolver un insumo o medicamento de la cuenta.
    *
    * @ access public
    * @ return boolean
    ***************************************************************************/
    
    function LlamaFormaDevolverIYMCta(){
      $this->FormaDevolverIYMCta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso']);
      return true;
    }
    
    /*************************************************************************
    * Funcion que devuelve los productos en la cuenta a inventarios
    *
    * @ access public
    * @ return boolean
    ***************************************************************************/
    
    
    function RealizarVevolucionMedicamentos(){  
      
	  foreach($_REQUEST as $name=>$val){
        $nameVal=substr($name,0,8);
		if($nameVal=='cantidad'){
          if($val>0){
            $vector[$name]=$val;
          }
        }
      }     
      
      IncludeClass('DevolucionCargosIyMCta','','app','Cuentas');
      $funciones = new DevolucionCargosIyMCta();
      $val=$funciones->ValidarInsercionDevolucion($vector);  
      if($val==1){
        $mensaje=$funciones->ErrMsg();        
        $this->FormaDevolverIYMCta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$mensaje);      
        return true;
      }else{
      
        //NOTAAAA:
        //las variables de session se realizan porque las maneja el modulo 
        //de inventarios, esto debe modificarse y pasarlas por parametros
        
        $_SESSION['FACTURACION_CUENTAS']['CUENTA']=$_REQUEST['Cuenta'];
        $_SESSION['FACTURACION_CUENTAS']['PLAN']=$_REQUEST['PlanId']; 
        $_SESSION['FACTURACION_CUENTAS']['motivosDevolucion']=$_REQUEST['MotivoDevolucion']; 
        
        foreach($vector as $valor=>$cantidad){
          (list($none,$empresa,$centro_utilidad,$bodega,$codigo_producto,$nom_bodega,$cantidadLimite,$departamento_al_cargar,$fecha_vencimiento,$lote)=explode('||//',$valor));
          $v['nombre_bodega']=$nom_bodega;
          $v['cantidad_limite']=$cantidadLimite;
          $v['cantidad_devolver']=$cantidad;
          $v['departamento_al_cargar']=$departamento_al_cargar;
          $dat[$empresa][$centro_utilidad][$bodega][$lote][$fecha_vencimiento][$codigo_producto]=$v;
        }
        
        foreach($dat as $Empresa=>$v){
          $_SESSION['FACTURACION_CUENTAS']['Empresa']=$Empresa;
          foreach($v as $centroU=>$v1){
            $_SESSION['FACTURACION_CUENTAS']['Centro_Utilidad']=$centroU;
            foreach($v1 as $Bodega=>$v2){
				$_SESSION['FACTURACION_CUENTAS']['Bodega']=$Bodega;
				foreach($v2 as $Lote=>$v3){
					$_SESSION['FACTURACION_CUENTAS']['Lote']=$Lote;
					foreach($v3 as $FechaVencimiento=>$v4){
						$_SESSION['FACTURACION_CUENTAS']['FechaVencimiento']=$FechaVencimiento;
						
						foreach($v4 as $codigo=>$v5){
							$_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CANTIDADES_DEV'][$codigo.'//||'.$FechaVencimiento.'//||'.$Lote.'//||'.$v5['departamento_al_cargar']]=$v5['cantidad_devolver'];      
						//$_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CANTIDADES_DEV'][$codigo]=$v3['cantidad_devolver'];      
						}
						$retorno=$this->CallMetodoExterno('app','InvBodegas','user','DevolucionIyMCargosCuenta');
						if($retorno==false){                  
							$mensaje=$_SESSION['FACTURACION_CUENTAS']['RETORNO']['Mensaje_Error'];
							$this->FormaDevolverIYMCta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$mensaje);      
							return true;
						}
					}	
				}			
            }
          }         
        }        
        $mensaje="Devoluciones Realizadas Satisfactoriamente";
        if(!$this->FormaMostrarCuenta(&$this,$_REQUEST['Cuenta'])){
          return false;
        }              
        return true;
      }      
    }
  
		//HABITACIONES
		function LlamaFormaLiquidacionManualHabitaciones()
		{              
			$this->FormaLiquidacionManualHabitaciones($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['PlanId'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['Ingreso']);
			return true;
		}

		function FormaLiquidacionManualHabitaciones($Cuenta,$TipoId,$PacienteId,$PlanId,$Nivel,$Fecha,$Ingreso,$mensaje)
		{    
				IncludeClass('LiquidacionHabitacionesCtaHTML','','app','Cuentas');
				$html = new LiquidacionHabitacionesCtaHTML();
				$accionEliminar=ModuloGetURL('app','Cuentas','user','EliminarCargoHabitacion');
				
				$accionModificar=ModuloGetURL('app','Cuentas','user','ModificarCargoHabitacion',
				array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,
				'PlanId'=>$PlanId,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
				
				$accionInsertar=ModuloGetURL('app','Cuentas','user','InsertarCargoHabitacion',
				array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,
				'PlanId'=>$PlanId,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
				
				$accionCargarCuenta=ModuloGetURL('app','Cuentas','user','LlamadoCargarHabitacionCuenta',
				array('EmpresaId'=>$_SESSION['CUENTAS']['EMPRESA'],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,
				'PlanId'=>$PlanId,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
				
				$accionCancelar=ModuloGetURL('app','Cuentas','user','VolverDetalleCuenta',
				array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,
				'PlanId'=>$PlanId,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
				
				$this->salida .= $html->CrearFormaLiquidacionManualHabitaciones($_SESSION['CUENTAS']['EMPRESA'],$accionEliminar,$accionModificar,$accionInsertar,$accionCargarCuenta,$accionCancelar,
				$Cuenta,$TipoId,$PacienteId,$PlanId,$Nivel,$Fecha,$Ingreso,$mensaje);                        
				
				return true;
		}
		
		/**
		***
		**/
		function EliminarCargoHabitacion()
		{
			IncludeClass('LiquidacionHabitacionesCta','','app','Cuentas');
			$objeto = new LiquidacionHabitacionesCta();        
			$objeto->EliminarCargoHabitacionVector($_REQUEST['posicion']);        
			$mensaje="SE ELIMINO EL REGISTRO.";
			$this->FormaLiquidacionManualHabitaciones($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['PlanId'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$mensaje);
			return true;      
		}
		
		/**
		***
		**/
		function ModificarCargoHabitacion()
		{
			IncludeClass('LiquidacionHabitacionesCta','','app','Cuentas');
			$objeto = new LiquidacionHabitacionesCta();    
			$objeto->ModificarCargoHabitacionVector($_REQUEST['precio_plan'],$_REQUEST['dias'],$_REQUEST['excedente'],$_REQUEST['cub'],$_REQUEST['noCub']);        
			$mensaje="SE MODIFICO EL REGISTRO SATISFACTORIAMENTE.";
			$this->FormaLiquidacionManualHabitaciones($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['PlanId'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$mensaje);
			return true;     
		}
		
		/**
		***
		**/
		function InsertarCargoHabitacion()
		{
			IncludeClass('LiquidacionHabitacionesCta','','app','Cuentas');
			$objeto = new LiquidacionHabitacionesCta();        
			$objeto->InsertarCargoHabitacionVector($_REQUEST['tipocama'],$_REQUEST['dpto'],$_REQUEST['precioN'],$_REQUEST['diasN'],$_REQUEST['noCubN'],$_REQUEST['copago']);        
			$mensaje="SE INSERTO EL REGISTRO SATISFACTORIAMENTE.";
			$this->FormaLiquidacionManualHabitaciones($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['PlanId'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$mensaje);
			return true;  
		}
		
		/**
		***
		**/
		function LlamadoCargarHabitacionCuenta()
		{
			//unset($_SESSION['LIQUIDACION_HABITACIONES']);
			IncludeClass('LiquidacionHabitacionesCta','','app','Cuentas');
			$objeto = new LiquidacionHabitacionesCta(); 
			if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php")) 
			{
				die(MsgOut("Error al incluir archivo","El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
			}
			if(!is_array($_SESSION['LIQUIDACION_HABITACIONES'])){        
				$liquidacionHab = new LiquidacionHabitaciones;
				$_SESSION['LIQUIDACION_HABITACIONES'] = $liquidacionHab->LiquidarCargosInternacion($_REQUEST['Cuenta'],false);                                             
			}
      if(!$_REQUEST['EmpresaId'])
        $_REQUEST['EmpresaId'] = SessionGetVar("DatosEmpresaId");
        
			if($objeto->CargarHabitacionCuenta($_REQUEST['EmpresaId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['PlanId'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$mensaje)==false){
				$mensaje="ERROR AL INSERTAR EN CUENTAS DETALLE.";          
			}else{        
				$mensaje="REGISTROS CARGADOS A LA CUENTA SATISFACTORIAMENTE.";          
			}
			unset($_SESSION['LIQUIDACION_HABITACIONES']);
			if(!$this->FormaMostrarCuenta(&$this,$_REQUEST['Cuenta'],$mensaje)){                   
				return false;
			}
			return true;          
		}
		
		/**
		***
		**/
		function VolverDetalleCuenta()
		{
			IncludeClass('LiquidacionHabitacionesCta','','app','Cuentas');
			$objeto = new LiquidacionHabitacionesCta();        
			$objeto->CancelarCargueHabitacionCuenta();  
			if(!$this->FormaMostrarCuenta(&$this,$_REQUEST['Cuenta']))
			{
					return false;
			}
			return true;
		}
		//FIN HABITACIONES  

		/**
		***
		**/
		function GetLlamaFormaCuantaPendientesCargar()
		{
			IncludeClass('CargosPendientesPorCargar','','app','Cuentas');
			$objeto = new CargosPendientesPorCargar();        
			$this->salida = $objeto->LlamaFormaCuantaPendientesCargar(&$this);  
			return true;
		}

		/**
		***
		**/
		function GetCargarALaCuentaPaciente()
		{
			IncludeClass('CargosPendientesPorCargar','','app','Cuentas');
			$objeto = new CargosPendientesPorCargar();        
			$this->salida = $objeto->CargarALaCuentaPaciente(&$this);  
			return true;
		}

		/**
		***
		**/
		function LlamaInsertarPendientesCargar()
		{
			IncludeClass('CargosPendientesPorCargar','','app','Cuentas');
			$objeto = new CargosPendientesPorCargar();        
			$this->salida = $objeto->InsertarPendientesCargar(&$this);  
			return true;
		}

		/**
		***
		**/
		function LlamaFrmEliminarPendientesXCargar()
		{
			IncludeClass('CargosPendientesPorCargarHTML','','app','Cuentas');
			$objeto = new CargosPendientesPorCargarHTML();
			$Cuenta = $_REQUEST[Cuenta];
			$accionE = ModuloGetUrl("app","Cuentas","user","LlamaEliminarCargoPendiente",array('EmpresaId'=>$_REQUEST[EmpresaId],'CentroUtilidad'=>$_REQUEST[CentroUtilidad],'Cuenta'=>$_REQUEST[Cuenta],'cargo_cups'=>$_REQUEST[cargo_cups],'procedimiento_pendiente_cargar_id'=>$_REQUEST[procedimiento_pendiente_cargar_id]));
			$accionC = ModuloGetUrl("app","Cuentas","user","LlamaFormaMostrarCuenta",array('Cuenta'=>$_REQUEST[Cuenta]));
			$this->salida = $objeto->FrmEliminarPendientesXCargar($Cuenta,$accionE,$accionC,$mensaje);  
			return true;
		}

		/**
		***
		**/
		function LlamaEliminarCargoPendiente()
		{
			IncludeClass('CargosPendientesPorCargar','','app','Cuentas');
			$objeto = new CargosPendientesPorCargar();
			$this->salida = $objeto->EliminarCargoPendiente(&$this);
			return true;
		}
		/**
		***
		**/
    function LlamaFrmListaPacientesConSalida()
		{
			if($_REQUEST[EmpresaId])
				SessionSetVar("EmpresaIdListaPacientesConSalida",$_REQUEST[EmpresaId]);
			else
				$_REQUEST[EmpresaId] = SessionGetVar("EmpresaIdListaPacientesConSalida");
			IncludeClass('ListadoPacientesconSalidaHTML','','app','Cuentas');
			$html = new ListadoPacientesconSalidaHTML();
			$accionSalir=ModuloGetURL('app','Cuentas','user','FormaMenu');
			$acc = ModuloGetUrl('app','Cuentas','user','LlamaFrmListaPacientesConSalida',array('EmpresaId',$_REQUEST[EmpresaId]));
			SessionSetVar("Cuentas_ListatoPacinetesConSAlida",$acc);
			
			$this->salida = $html->CrearFrmListaPacientesConSalida($_REQUEST[EmpresaId],$accionSalir);
			return true;  
		}

		/**
		***
		**/
		function LlamaFrmGeneracionReportes()
		{
			IncludeClass('ReportesCensoHTML','','app','Cuentas');
			$html = new ReportesCensoHTML();
			$accionSalir=ModuloGetURL('app','Cuentas','user','FormaMenu');
			$this->salida = $html->FrmGeneracionReportes($accionSalir);
			return true;  
		}

		/**
		***
		**/
		function LlamaFrmMenuCenso()
		{
			IncludeClass('ReportesCensoHTML','','app','Cuentas');
			$html = new ReportesCensoHTML();
			$accionSalir=ModuloGetURL('app','Cuentas','user','LlamaFrmGeneracionReportes');
			$this->salida .= $html->FrmMenuCenso($accionSalir);
			return true;  
		}

		/**
		***
		**/
		function LlamaFrmListadoCenso()
		{
			IncludeClass('ReportesCensoHTML','','app','Cuentas');
			$html = new ReportesCensoHTML();
			$this->salida .= $html->FrmListadoCenso();
			return true;  
		}
	
		/**
		***
		**/
		function LlamaFrmConsultaPacientesTP()
		{
			IncludeClass('ReportesCensoHTML','','app','Cuentas');
			$html = new ReportesCensoHTML();
			$this->salida .= $html->FrmConsultaPacientesTP();
			return true;  
		}
	
		/**
		***
		**/
		function LlamaFrmTotalFacturaCredito()
		{
			IncludeClass('ReportesCensoHTML','','app','Cuentas');
			$html = new ReportesCensoHTML();
			$this->salida .= $html->FrmTotalFacturaCredito();
			return true;  
		}
	
		/**
		***
		**/
		function LlamaFrmListadoPacientesUHA()
		{
			IncludeClass('ReportesCensoHTML','','app','Cuentas');
			$html = new ReportesCensoHTML();
			$this->salida .= $html->FrmListadoPacientesUHA();
			return true;  
		}
		/**
		***
		**/
		function ObtenerFormaListadoDivision(){        
			$this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
			return true;          
		}
	
		/**
		***
		**/
		function LlamaFrmReporteFC()
		{
			if($_REQUEST[planes]=="")
			{
				$error='DEDE SELECIONAR UN PLAN';
				$this->LlamaFrmTotalFacturaCredito($error);
			}
			else
			{
				IncludeClass('ReportesCensoHTML','','app','Cuentas');
				$html = new ReportesCensoHTML();
				$this->salida .= $html->FrmReporteFC();
			}
			return true;  
		}
		/*
		* Se encarga de separar la hora del formato timestamp
		* @access private
		* @return string
		* @param date hora
		*/
		function HoraStamp($hora)
		{
			$hor = strtok ($hora," ");
			for($l=0;$l<4;$l++)
			{
				$time[$l]=$hor;
				$hor = strtok (":");
			}
					$x=explode('.',$time[3]);
			return  $time[1].":".$time[2].":".$x[0];
		}
    
    function BuscarDatos()
    {
        IncludeClass('ImprimirSQL','','app','Cuentas');
        IncludeClass('ImprimirHTML','','app','Cuentas');
        $cnt = new ImprimirSQL();
        $html = new ImprimirHTML();
        $datos = $cnt->BuscarDatos();
   
        //$imprimir_html->FormaImprimir($cuenta[plan_id],$cuenta[numerodecuenta],$cuenta[ingreso],$cuenta[estado],$cuenta[tipo_id_paciente],$cuenta[paciente_id]);
        return true;
    }
    /**
    * Metodo de control para realizar la unificacion de cuentas
    * que poseen el mismo ingreso y estan activas o inactivas.
    *
    * @return boolean
    */
    function UnificarCuenta()
    {
      $request = $_REQUEST;
      $opc = AutoCarga::factory("OpcionesCuentas","classes","app","Cuentas");
      $oph = AutoCarga::factory("OpcionesCuentasHTML","classes","app","Cuentas");
      $rst = $opc->UnificarCuentas($request['Cuenta'], $request['cuentaA']);

      $titulo = "MENSAJE";
      $mensaje = "LA UNIFICACION DE LA CUENTA ".$request['cuentaA']." CON LA CUENTA ".$request['Cuenta']." SE REALIZO EXITOSAMENTE";
      if(!$rst)
      {
        $mensaje = "<label class=\"label_error\">".$opc->error."<br>".$opc->mensajeDeError."</label>";
        $titulo = "MENSAJE DE ERROR";
      }
      $action = SessionGetVar("AccionVolverCargos");
      $this->salida .= $oph->FormaMensaje($mensaje,$titulo,$action);
      
      return true;
    }
    /**
    * Metodo de control para la realizacion de los descuentos sobre los grupos de cargos
    * de la cuenta
    *
    * @return boolean
    */
    function DescuentosCuentas()
    {
			$request = $_REQUEST;
      IncludeFileModulo("Cuentas","RemoteXajax","app","Cuentas");
      $this->SetXajax(array("EvaluarDatosDescuento"),null,"ISO-8859-1");

      $opc = AutoCarga::factory("OpcionesCuentas","classes","app","Cuentas");
      $oph = AutoCarga::factory("OpcionesCuentasHTML","classes","app","Cuentas");
			
      $empresa = SessionGetVar("DatosEmpresaId");
      $detalle = $opc->ObtenerDetalleAgrupado($request['Cuenta']);
      $action['volver'] = SessionGetvar("AccionVolverCargos");
      $this->salida .= $oph->FormaListarGruposCargos($action,$empresa,$request['Cuenta'],$detalle);
      return true;
    }

    /*
    *
    */
		function BuscarPacientesBD($ingreso)
		{
      $i = 0;
      $ingresosIn = $this->ingresos;
      $this->ingresos = str_replace(" ",",",trim($this->ingresos));
      /***************************************************************************
      * ingresos que estan relacionados con pacientes urgencias
      ****************************************************************************/
      $sql  = "SELECT	IG.ingreso, ";
      $sql .= "				TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
      $sql .= "				PC.paciente_id, ";
      $sql .= "				PC.tipo_id_paciente, ";
      $sql .= "				PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,";
      $sql .= "				PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres, ";
      $sql .= "				CU.estado ||' '|| CU.numerodecuenta AS estado, ";
      $sql .= "				DE.descripcion, ";
      $sql .= "				EF.descripcion AS estacion, ";
      $sql .= "				'URG' AS tabla ";
      $sql .= "FROM		ingresos IG, pacientes PC,pacientes_urgencias PU,cuentas CU, ";
      $sql .= "				departamentos DE, estaciones_enfermeria EF ";
      $sql .= "WHERE	IG.ingreso IN (".$ingreso.") ";
      $sql .= "AND		IG.estado ='1' ";
      $sql .= "AND		IG.paciente_id = PC.paciente_id ";
      $sql .= "AND		IG.tipo_id_paciente = PC.tipo_id_paciente ";
      $sql .= "AND		IG.ingreso = PU.ingreso ";
      $sql .= "AND		PU.sw_estado = '1' ";
      $sql .= "AND		IG.ingreso = CU.ingreso ";
      $sql .= "AND		IG.departamento_actual = DE.departamento ";
      $sql .= "AND		EF.departamento = DE.departamento ";
      $sql .= "AND		PU.estacion_id = EF.estacion_id ";
      $sql .= "ORDER BY 1 ";
      
      echo(" sql2: ".$sql."<br>");
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return true;
      
      while(!$rst->EOF)
      {
        $this->paciente[$i] = $rst->GetRowAssoc($ToUpper = false);
        $ingresosIn = str_replace($this->paciente[$i]['ingreso']." ","",$ingresosIn);
        
        $rst->MoveNext();
        $i++;
      }
    
      $rst->Close();
      /***************************************************************************
      * ingresos que estan relacionados con esatciones enfernerias
      ****************************************************************************/
      $sql  = "SELECT	IG.ingreso, ";
      $sql .= "				TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
      $sql .= "				PC.paciente_id, ";
      $sql .= "				PC.tipo_id_paciente, ";
      $sql .= "				PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,";
      $sql .= "				PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres, ";
      $sql .= "				CU.estado||' '|| CU.numerodecuenta AS estado, ";
      $sql .= "				DE.descripcion, ";
      $sql .= "				EF.descripcion AS estacion, ";
      $sql .= "				'EEF' AS tabla ";
      $sql .= "FROM		ingresos IG, pacientes PC, cuentas CU,";
      $sql .= "				estaciones_enfermeria_ingresos_pendientes EP, ";
      $sql .= "				departamentos DE, estaciones_enfermeria EF ";
      $sql .= "WHERE	IG.ingreso IN (".$this->ingresos.") ";
      $sql .= "AND		IG.estado ='1' ";
      $sql .= "AND		IG.paciente_id = PC.paciente_id ";
      $sql .= "AND		IG.tipo_id_paciente = PC.tipo_id_paciente ";
      $sql .= "AND		IG.ingreso = CU.ingreso ";
      $sql .= "AND		CU.numerodecuenta = EP.numerodecuenta ";
      $sql .= "AND		IG.departamento_actual = DE.departamento ";
      $sql .= "AND		EP.estacion_id = EF.estacion_id ";
      $sql .= "ORDER BY 1 ";
      
      echo(" sql3: ".$sql."<br>");
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return true;
      
    
      while(!$rst->EOF)
      {
        $this->paciente[$i] = $rst->GetRowAssoc($ToUpper = false);
        $ingresosIn = str_replace($this->paciente[$i]['ingreso']." ","",$ingresosIn);
        
        
        $rst->MoveNext();
        $i++;
      }
      $rst->Close();
      /***************************************************************************
      * ingresos que estan relacionados con movimientos habitacion
      ****************************************************************************/
      $sql  = "SELECT	IG.ingreso, ";
      $sql .= "				TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
      $sql .= "				PC.paciente_id, ";
      $sql .= "				PC.tipo_id_paciente, ";
      $sql .= "				PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,";
      $sql .= "				PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres, ";
      $sql .= "				CU.estado||' '|| CU.numerodecuenta AS estado, ";
      $sql .= "				DE.descripcion, ";
      $sql .= "				EF.descripcion AS estacion, ";
      $sql .= "				CA.pieza, CA.cama, CA.ubicacion, ";
      $sql .= "				'MVH' AS tabla ";
      $sql .= "FROM		ingresos IG, pacientes PC, cuentas CU,";
      $sql .= "				estaciones_enfermeria_ingresos_pendientes EP, ";
      $sql .= "				movimientos_habitacion MH, camas CA, ";
      $sql .= "				departamentos DE,estaciones_enfermeria EF ";
      $sql .= "WHERE	IG.ingreso IN (".$this->ingresos.") ";
      $sql .= "AND		IG.estado ='1' ";
      $sql .= "AND		IG.paciente_id = PC.paciente_id ";
      $sql .= "AND		IG.tipo_id_paciente = PC.tipo_id_paciente ";
      $sql .= "AND		MH.ingreso = IG.ingreso ";
      $sql .= "AND		IG.ingreso = CU.ingreso ";
      $sql .= "AND		IG.departamento_actual = DE.departamento ";
      $sql .= "AND		EP.estacion_id = EF.estacion_id ";
      $sql .= "AND		CA.cama = MH.cama ";
      $sql .= "ORDER BY 1 ";
      
      echo(" sql4: ".$sql."<br>");
      
      while(!$rst->EOF)
      {
        $this->paciente[$i] = $rst->GetRowAssoc($ToUpper = false);
        $ingresosIn = str_replace($this->paciente[$i]['ingreso']." ","",$ingresosIn);
        
        $rst->MoveNext();
        $i++;
      }
    
      $rst->Close();
      if($ingresosIn != "")
      {
        /***************************************************************************
        * ingresos que no tienen cuentas
        ****************************************************************************/
        $ingresosIn = str_replace(" ",",",trim($ingresosIn));
        $sql  = "SELECT	IG.ingreso, ";
        $sql .= "				TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
        $sql .= "				PC.paciente_id, ";
        $sql .= "				PC.tipo_id_paciente, ";
        $sql .= "				PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,";
        $sql .= "				PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres, ";
        $sql .= "				DE.descripcion, ";
        $sql .= "				CU.estado,";
        $sql .= "				CU.numerodecuenta,";
        $sql .= "				'CUE' AS tabla ";
        $sql .= "FROM		ingresos IG LEFT JOIN cuentas CU ";
        $sql .= "				ON(";
        $sql .= "					IG.ingreso = CU.ingreso  ";
        $sql .= "				),";
        $sql .= " 			pacientes PC, ";
        $sql .= "				departamentos DE ";
        $sql .= "WHERE	IG.ingreso IN (".$ingresosIn.") ";
        $sql .= "AND		IG.estado ='1' ";
        $sql .= "AND		IG.paciente_id = PC.paciente_id ";
        $sql .= "AND		IG.tipo_id_paciente = PC.tipo_id_paciente ";
        $sql .= "AND		IG.departamento_actual = DE.departamento ";
        $sql .= "ORDER BY 1 ";
        
        echo(" sql5: ".$sql."<br>");
        
        if(!$rst = $this->ConexionBaseDatos($sql))
          return true;
    
        while(!$rst->EOF)
        {
          $this->paciente[$i] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
          $i++;
        }
        $rst->Close();
      }	
			return true;
		}        
	}
?>