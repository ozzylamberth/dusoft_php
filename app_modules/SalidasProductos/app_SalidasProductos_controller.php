<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: app_SalidasProductos_controller.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  
 class app_SalidasProductos_controller extends classModulo
 {
   /**
        * Constructor de la clase
        */
   function app_SalidasProductos_controller(){}
    
   /**
        *  Funcion principal del modulo
        *  @return boolean
        */
   function Main()
   {
     $request = $_REQUEST;
     $parametrizacionBod = AutoCarga::factory('SalidasProductosSQL', '', 'app', 'SalidasProductos');
     $action['volver'] = ModuloGetURL('system', 'Menu');
     $permisos = $parametrizacionBod->ObtenerPermisos();    
      
     $ttl_gral = "PARAMETRIZACION DE SALIDAS PRODUCTOS";
     $titulo[0] = 'EMPRESAS';
     $url[0] = 'app';
     $url[1] = 'SalidasProductos'; 
     $url[2] = 'controller';
     $url[3] = 'Menu'; 
     $url[4] = 'permiso_SalidasProductos'; 
     $this->salida = gui_theme_menu_acceso($ttl_gral, $titulo, $permisos, $url, $action['volver']);
     return true;
   }
    
   /**
         *   Funcion de control para el menu inicial
        */
   function Menu()
   {
     $request = $_REQUEST;
     $action['parametrizar_listadoproductos'] = ModuloGetURL("app", "SalidasProductos", "controller", "ListadoProductos");
     $action['escanear_imagen']=ModuloGetURL("app", "SalidasProductos", "controller", "SoporteEscaneado");
     $action['volver'] = ModuloGetURL("app", "SalidasProductos", "controller", "Main");
      
     if($request['permiso_SalidasProductos']['empresa'])
      SessionSetVar("empresa_id",$request['permiso_SalidasProductos']['empresa']);
      
     $act = AutoCarga::factory("SalidasProductosHTML", "views", "app", "SalidasProductos");
     $this->salida = $act->formaMenu($action); 
     return true;      
   }
    
   /**
         *  Funcion que busca el listado de productos
        */
   function ListadoProductos()
   {
     $this->SetXajax(array("GuardarTmp","ProductosListas","GuardarTmpD","GuardarDocumentoReal","GuardarOtroTmp"),"app_modules/SalidasProductos/RemoteXajax/RemotosListaProductos.php","ISO-8859-1");
     $request = $_REQUEST;
     
     $this->IncludeJS("CrossBrowser");
     $this->IncludeJS("CrossBrowserEvent");
     $this->IncludeJS("CrossBrowserDrag");
     $empresa_id = SessionGetVar("empresa_id");
     $offset=$request['offset'];
   
     $conteo =$pagina=0;
     $action['volver'] = ModuloGetURL("app", "SalidasProductos", "controller", "Menu");
     $action['salidas_productos']=ModuloGetURL("app", "SalidasProductos", "controller", "ListadoProductos");
     $action['paginador'] = ModuloGetURL("app", "SalidasProductos", "controller", "ListadoProductos");
      
     $mdl = AutoCarga::factory("SalidasProductosSQL","","app","SalidasProductos");
     $ListarProductos=$mdl->ListarProductos($empresa_id,$offset);
     $conteo= $mdl->conteo;
		 $pagina= $mdl-> pagina;
      
     $act = AutoCarga::factory("SalidasProductosHTML", "views", "app", "SalidasProductos");
     
     $this->salida = $act->formaListaProductos($action,$empresa_id,$conteo,$pagina,$ListarProductos);
     
     return true; 
   }
    
  /**
      *  Funcion que busca el listado de usuarios
     */
	function Subirimagen()
	{
		$request = $_REQUEST;
    $empresa_id = SessionGetVar("empresa_id");
    //print_r($request);
    
		$action['imagensu'] = ModuloGetURL("app", "SalidasProductos", "controller", "SoporteEscaneado",array("prefijo"=>$request['prefijo'],"numero"=>$request['numero'],"empresa"=>$empresa_id));
		$action['volver'] = ModuloGetURL("app", "SalidasProductos", "controller", "ListadoProductos");
    $mdl = AutoCarga::factory("SalidasProductosSQL","","app","SalidasProductos");
    $prefijo='ESP';
   
    $resultado=$mdl->SacarDocumento($empresa_id,$prefijo);
    $numero=count($resultado);
    $resultado1=$mdl->SacarDocumento1($empresa_id,$prefijo,$numero);
   
    $contratoca=$resultado1[0]['prefijo'];
		$tipood=$_REQUEST['numero'];
		$Noid=$_REQUEST['prefijo'];
			
		$act = AutoCarga::factory("SalidasProductosHTML", "views", "app", "SalidasProductos");
		$this->salida = $act->FormaSubir($action,$tipood,$Noid);
    return true;
	}
  
  /**
      *  Funcion soporte escaneado
     */  
  function SoporteEscaneado()
  {
    $this->SetXajax(array(""),"app_modules/SalidasProductos/RemoteXajax/RemotosListaProductos.php");
    $request = $_REQUEST;
    $empresa_id = SessionGetVar("empresa_id");
    //print_r($request);
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserEvent");
    $this->IncludeJS("CrossBrowserDrag");
      
    $offset=$request['offset'];
    $conteo =$pagina=0;
      
    $action['volver'] = ModuloGetURL("app", "SalidasProductos", "controller", "ListadoProductos");
    $action['paginador'] = ModuloGetURL("app", "SalidasProductos", "controller", "ListadoProductos");
      
    $mdl = AutoCarga::factory("SalidasProductosSQL","","app","SalidasProductos");
    //$request['prefijo']=$prefijo;
    $prefijo= ModuloGetVar('app','SalidasProductos','documento_prefijo_'.$empresa_id);
    $resultado=$mdl->SacarDocumento($empresa_id,$prefijo);
    $numero=$request['numero'];
    $resultado1=$mdl->SacarDocumento1($empresa_id,$prefijo,$numero);
    //print_r($resultado1);
    $contratoca=$resultado1[0]['prefijo'];
	  $tipood=$resultado1[0]['tipo_doc_bodega_id'];
		$Noid=$resultado1[0]['documento_id'];
    $numero=$resultado1[0]['numero'];
    $empresa_id = SessionGetVar("empresa_id");
    $tmp_name = $_FILES["archivo"]["tmp_name"];
    //$numero = rand(0,1000);

	  $type = $_FILES["archivo"]["type"];
		$size = $_FILES["archivo"]["size"];
		$nombre = basename($_FILES["archivo"]["name"]);

		
		$renombrada = $contratoca.$Noid.$numero.".".$dataimg[1];
    //$nombre = $renombrada;
    $dataimg = explode(".",$nombre);
		$fp = fopen($tmp_name, "rb");
		$buffer = fread($fp, filesize($tmp_name));
		fclose($fp);
		$buffer=addslashes($buffer);
		$path_upload = 'cartas/';
		if (is_uploaded_file($_FILES['archivo']['tmp_name']))
		{
			$renombrada = $contratoca.$Noid.$numero.".".$dataimg[1];
			$ruta_archivo =  GetVarConfigAplication('DIR_SIIS')."/app_modules/SalidasProductos/cartas".$envio.'/'.$renombrada;
			$this->nombre_archivos[$envio][$key] = $renombrada;
			move_uploaded_file ( $_FILES['archivo']['tmp_name'], $ruta_archivo );
			$lines = file($ruta_archivo);
		}
    $dat=$mdl->Insertar($renombrada, $size, $type, $buffer,$contratoca,$tipood,$Noid,$empresa_id,$numero);
		if(!$dat)
		{
       $msg1 = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>".$ingc->mensajeDeError;
		} 
		else
		{
       $msg1 = "EL INGRESO SE HA REALIZADO SATISFACTORIAMENTE";
		}
	
    $conteo= $mdl->conteo;
		$pagina= $mdl-> pagina;
      
    $act = AutoCarga::factory("SalidasProductosHTML", "views", "app", "SalidasProductos");
      
    $this->salida = $act->FormaMensajeIngresocartas($action, $msg0, $msg1);
    return true; 
   }  
 }
?>