<?php
	/**************************************************************************************
	* $Id: app_CrearProveedores_controller.php,v 1.1 2007/07/04 17:01:50 jgomez Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Jaime Gomez
  
	***************************************************************************************/
	IncludeClass('CrearSQL','','app','CrearProveedores');
	class app_CrearProveedores_controller extends classModulo
	{
       
    /**
	* @var $action Variable donde se guardan los action de las formsa
	**/
    var $action = array();
	/**   
	*/
    
    
  	function app_CrearProveedores_controller(){}
 
     
     function main()
     {
       $html = AutoCarga::factory("CrearProveedores_HTML", "views", "app", "CrearProveedores");
       $sql = AutoCarga::factory("CrearSQL", "", "app", "CrearProveedores");
	   $permisos = $sql->ListarEmpresas();    
			$ttl_gral = "CREAR TERCEROS Y TERCEROS PROVEEDORES";
			$mtz[0]='EMPRESAS';
			$url[0] = 'app';
			$url[1] = 'CrearProveedores'; 
			$url[2] = 'controller';
			$url[3] = 'Menu'; 
			$url[4] = 'datos'; 
			$action['volver'] = ModuloGetURL('system', 'Menu');
			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
			return true;
    } 
	
     function Menu()
     {
       if($_REQUEST['datos']) 
		SessionSetVar("Datos",$_REQUEST['datos']);
		$datos = SessionGetVar("Datos");
		
		//print_r($datos);
		
	   $html = AutoCarga::factory("CrearProveedores_HTML", "views", "app", "CrearProveedores");
       $sql = AutoCarga::factory("CrearSQL", "", "app", "CrearProveedores");
	   
	   $action['volver'] = ModuloGetURL("app", "CrearProveedores", "controller", "main");
	   $action['terceros'] = ModuloGetURL("app", "CrearProveedores", "controller", "Terceros");
	   $action['proveedores'] = ModuloGetURL("app", "CrearProveedores", "controller", "Proveedores");
	   $this->salida = $html->Menu($action);
	   return true;
    }
	
	
     function Terceros()
     {
       $request = $_REQUEST;
	   $datos = SessionGetVar("Datos");
		
		IncludeFileModulo("definirProv","RemoteXajax","app","CrearProveedores");
		$this->SetXajax(array("EstadosTercero"),null,"ISO-8859-1");
		/*print_r($_REQUEST);*/
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");
       
	   $html = AutoCarga::factory("CrearProveedores_HTML", "views", "app", "CrearProveedores");
       $sql = AutoCarga::factory("CrearSQL", "", "app", "CrearProveedores");
	   $tipos_id=$sql->Terceros_id();
	    $datosN = $sql->Terceros($request['buscador'],$datos['empresa_id'],$request['offset']);
      // print_r($_REQUEST);   
	   
	   $action['volver'] = ModuloGetURL("app", "CrearProveedores", "controller", "Menu");
	   $action['buscar'] = ModuloGetURL("app", "CrearProveedores", "controller", "Terceros");
	   $action['FormaTerceros'] = ModuloGetURL("app", "CrearProveedores", "controller", "FormaTercero");
	   $action['FormaTercerosBancos'] = ModuloGetURL("app", "CrearProveedores", "controller", "FormaTercerosBancos");
	   $action['paginador'] = ModuloGetURL('app','CrearProveedores','controller','Terceros',array("buscador"=>$request['buscador']));
	   $this->salida = $html->Terceros($action,$tipos_id,$request['buscador'],$datosN,$sql->conteo, $sql->pagina);
	   return true;
    }
	
     function FormaTercero()
     {
       $request = $_REQUEST;
	   $datos = SessionGetVar("Datos");
		
		IncludeFileModulo("definirProv","RemoteXajax","app","CrearProveedores");
		$this->SetXajax(array("Buscar_Departamento","Buscar_Municipio","Buscar_Actividad"),null,"ISO-8859-1");

		$sql = AutoCarga::factory("CrearSQL", "", "app", "CrearProveedores");
		
		$token = 0;
		if($_REQUEST['consulta']=='1')
		{	
			$token=$sql->InsertarTercero($_REQUEST);
		}
		else
			if($_REQUEST['consulta']=='0')
			{
			$token=$sql->ModificarTercero($_REQUEST);
			}
	   if(!$token)
		$mensaje[]=$sql->mensajeDeError;
		else
			$mensaje[0] ="EXITO EN LA CONSULTA!!";
       
	   $html = AutoCarga::factory("CrearProveedores_HTML", "views", "app", "CrearProveedores");
       
	   $tipos_id=$sql->Terceros_id();
	   $tercero=$sql->Tercero($_REQUEST,$datos['empresa_id']);
	   $paises=$sql->Paises();
	   $UnidadesNegocio=$sql->UnidadesNegocio();
	   $GrupoActividades=$sql->ListaGruposActividades();
	   $TiposClientes=$sql->Lista_TiposClientes();
	   
	   $action['volver'] = ModuloGetURL("app", "CrearProveedores", "controller", "Terceros");
	   $action['guardar'] = ModuloGetURL("app", "CrearProveedores", "controller", "FormaTercero");
	   $action['modificar'] = ModuloGetURL("app", "CrearProveedores", "controller", "FormaTercero");
	   $action['paginador'] = ModuloGetURL('app','CrearProveedores','controller','Terceros',array("buscador"=>$request['buscador']));
	   $this->salida = $html->FormaTerceros($action,$tipos_id,$paises,$request,$datos,$tercero,$UnidadesNegocio,$GrupoActividades,$mensaje,$TiposClientes);
	  	  
	   return true;
    }
	
	
	
	     function FormaTercerosBancos()
     {
       $request = $_REQUEST;
	   $datos = SessionGetVar("Datos");
		
		IncludeFileModulo("definirProv","RemoteXajax","app","CrearProveedores");
		$this->SetXajax(array("Buscar_Departamento","Buscar_Municipio","Buscar_Actividad"),null,"ISO-8859-1");
		/*print_r($_REQUEST);*/
		$sql = AutoCarga::factory("CrearSQL", "", "app", "CrearProveedores");
		
		$tercero=$sql->Tercero($_REQUEST,$datos['empresa_id']);
		
		$token = 0;
		if($_REQUEST['consulta_bancos']=='1')
		{
		$token=$sql->InsertarTerceroBanco($_REQUEST);
		}
		
		if($_REQUEST['consulta_cuentas']=='1')
		{
			for($i=0;$i<$_REQUEST['registros'];$i++)
			{
			if(trim($_REQUEST['numero_cuenta'][$i])!="")
				{
				$token=$sql->InsertarTerceroBancoCuenta($tercero,$_REQUEST['numero_cuenta'][$i],$_REQUEST['banco'][$i],$_REQUEST['tipo_de_cuenta_id'][$i]);
				}
			}
		}
		
		if($_REQUEST['borrar_banco']=='1')
		{
		$token=$sql->Borrar_TerceroBanco($_REQUEST);
		}
		
		if($_REQUEST['borrar_bancocuenta']=='1')
		{
		$token=$sql->Borrar_TerceroBancoCuenta($_REQUEST);
		}
		
	   if(!$token)
		$mensaje[]=$sql->mensajeDeError;
		else
			$mensaje[0] ="EXITO EN LA CONSULTA!!";
       $bancos=$sql->ListarBancos($_REQUEST['tipo_id_tercero'],$_REQUEST['tercero_id']);
       $tipos_cuentas=$sql->ListarTiposCuentas($_REQUEST['tipo_id_tercero'],$_REQUEST['tercero_id']);
       $bancos_tercero=$sql->ListarBancosTercero($_REQUEST['tipo_id_tercero'],$_REQUEST['tercero_id']);
	   $bancos_cuentas=$sql->ListarBancosCuentas($_REQUEST['tipo_id_tercero'],$_REQUEST['tercero_id']);
	   
	   /*print_r($bancos_cuentas);*/
	   
	   $html = AutoCarga::factory("CrearProveedores_HTML", "views", "app", "CrearProveedores");
      
	   $action['volver'] = ModuloGetURL("app", "CrearProveedores", "controller", "Terceros");
	   $action['guardar'] = ModuloGetURL("app", "CrearProveedores", "controller", "FormaTercerosBancos");

	   $this->salida = $html->FormaTercerosBancos($action,$tercero,$bancos,$bancos_tercero,$tipos_cuentas,$bancos_cuentas);
	  	  
	   return true;
    }
	
	
	
     function Proveedores()
     {
       $datos = SessionGetVar("Datos");
		//print_r($datos);
	   $empresa=$emp['empresa_id'];
	   $html = AutoCarga::factory("CrearProveedores_HTML", "views", "app", "CrearProveedores");
       $sql = AutoCarga::factory("CrearSQL", "", "app", "CrearProveedores");
	   
	   $action['volver'] = ModuloGetURL("app", "CrearProveedores", "controller", "Menu");
	   $action['terceros'] = ModuloGetURL("app", "CrearProveedores", "controller", "Terceros");
	   $action['proveedores'] = ModuloGetURL("app", "CrearProveedores", "controller", "Proveedores");
	   $this->salida = $html->Menu($action);
	   return true;
    }
     
     
 }
?>