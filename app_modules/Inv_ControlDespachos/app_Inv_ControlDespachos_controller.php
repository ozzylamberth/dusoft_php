<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_Inv_ControlDespachos_controller.php,v 1.29 2010/02/17 14:46:54 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  /**
  * Clase Control: Inv_ControlDespachos
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.29 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
    class app_Inv_ControlDespachos_controller extends classModulo
    {
        /**
        * @var array $action  Vector donde se almacenan los links de la aplicacion
        */
        var $action = array();
        /**
        * @var array $request Vector donde se almacenan los datos pasados por request
        */
        var $request = array();
		
		
        /**
        * Constructor de la clase
        */
        function app_Inv_ControlDespachos_controller(){}
        /**
        * Funcion principal del modulo
        *
        * @return boolean
        */
        
		function main()
		{	
			
			$sql=AutoCarga::factory("Permisos", "", "app","Inv_ControlDespachos");
			
			$request = $_REQUEST;
						
			$url[0]='app';                         //Tipo de Modulo
			$url[1]='Inv_ControlDespachos';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='MenuControlDespachos';   //Metodo.
			$url[4]='datos';						//vector de $_request.
			$arreglo[0]='EMPRESA';					//Sub Titulo de la Tabla
			$arreglo[1] = 'CENTRO UTILIDAD';
			$arreglo[2] = 'BODEGA';			
			
			$datos=$sql->BuscarPermisos(); 
		
			$forma = gui_theme_menu_acceso("MODULO DE CONTROL DE DESPACHOS",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
			$this->salida=$forma;
				
			return true; 

		}
		
		/*
		* FUNCION DE MENU PRINCIPAL
		*/
		function MenuControlDespachos()
		{
  		/*Crear el Menú de Opciones*/
		$request = $_REQUEST;
			if($_REQUEST['datos']) 
				SessionSetVar("ControlDespachos",$_REQUEST['datos']);
	
		$datos = SessionGetVar("ControlDespachos");
		$Obj_Menu=AutoCarga::factory("ControlDespachos_MenuHTML", "views", "app","Inv_ControlDespachos");
  		$action[1] = ModuloGetURL("app","Inv_ControlDespachos","controller","DespacharMercancia");
  		$action[2] = ModuloGetURL("app","Inv_ControlDespachos","controller","RecibirMercancia");
  		$action['volver'] = ModuloGetURL("app","Inv_ControlDespachos","controller","main");
		$this->salida=$Obj_Menu->Menu($action,$datos,$num);
  		
  		return true;
		}
		
		
		/*FUNCION: DESPACHAR MERCANCÍA	*/
		function DespacharMercancia()
		{
		$request = $_REQUEST;
		$datos = SessionGetVar("ControlDespachos");
		
		$sql=AutoCarga::factory("ControlDespachos", "", "app","Inv_ControlDespachos");
		$resultado = $sql->Listar_DespachosAFarmacia($datos,$request['buscador'],$request['offset']);
				
		$html =AutoCarga::factory("ControlDespachos_HTML", "views", "app","Inv_ControlDespachos");
		$action['volver'] = ModuloGetURL("app","Inv_ControlDespachos","controller","MenuControlDespachos");
		$action['buscar'] = ModuloGetURL("app","Inv_ControlDespachos","controller","DespacharMercancia");
		$action['paginador'] = ModuloGetURL('app','Inv_ControlDespachos','controller','DespacharMercancia',array("buscador"=>$request['buscador']));
		$this->salida = $html->DespacharMercancia($request,$action,$resultado,$sql->conteo, $sql->pagina);
		return true;
		}
		
		/*FUNCION: DESPACHAR MERCANCÍA	*/
		function DespacharMercancia_Forma()
		{
		$request = $_REQUEST;
		$datos = SessionGetVar("ControlDespachos");
		$sql=AutoCarga::factory("ControlDespachos", "", "app","Inv_ControlDespachos");
		/*print_r($_REQUEST);*/
		
		if($_REQUEST['guardar']=="1")
		$token = $sql->Despachar($_REQUEST['buscador'],$_REQUEST);
		
		$transportadoras = $sql->Listar_Transportadoras();
		$resultado = $sql->Listar_DespachosAFarmacia($datos,$request['buscador'],$request['offset']);
				
		$html =AutoCarga::factory("ControlDespachos_HTML", "views", "app","Inv_ControlDespachos");
		$action['volver'] = ModuloGetURL("app","Inv_ControlDespachos","controller","DespacharMercancia",array("buscador"=>$request['buscador']));
		$action['guardar'] = ModuloGetURL("app","Inv_ControlDespachos","controller","DespacharMercancia_Forma",array("buscador"=>$request['buscador']));
		$this->salida = $html->DespacharMercancia_Forma($request,$action,$resultado,$transportadoras);
		return true;
		}
       
	   /*FUNCION: DESPACHAR MERCANCÍA	*/
		function RecibirMercancia()
		{
		$request = $_REQUEST;
		$datos = SessionGetVar("ControlDespachos");
		
		$sql=AutoCarga::factory("ControlDespachos", "", "app","Inv_ControlDespachos");
		$resultado = $sql->Listar_DespachosFisicosAFarmacia($datos,$request['buscador'],$request['offset']);
				
		$html =AutoCarga::factory("ControlDespachos_HTML", "views", "app","Inv_ControlDespachos");
		$action['volver'] = ModuloGetURL("app","Inv_ControlDespachos","controller","MenuControlDespachos");
		$action['buscar'] = ModuloGetURL("app","Inv_ControlDespachos","controller","RecibirMercancia");
		$action['paginador'] = ModuloGetURL('app','Inv_ControlDespachos','controller','RecibirMercancia',array("buscador"=>$request['buscador']));
		$this->salida = $html->RecibirMercancia($request,$action,$resultado,$sql->conteo, $sql->pagina);
		return true;
		}
    
		
		/*FUNCION: DESPACHAR MERCANCÍA	*/
		function RecibirMercancia_Forma()
		{
		$request = $_REQUEST;
		$datos = SessionGetVar("ControlDespachos");
		$sql=AutoCarga::factory("ControlDespachos", "", "app","Inv_ControlDespachos");
		/*print_r($_REQUEST);*/
		
		if($_REQUEST['guardar']=="1")
		$token = $sql->Recibir($_REQUEST['buscador'],$_REQUEST);
		
		$transportadoras = $sql->Listar_Transportadoras();
		$resultado = $sql->Listar_DespachosFisicosAFarmacia($datos,$request['buscador'],$request['offset']);
				
		$html =AutoCarga::factory("ControlDespachos_HTML", "views", "app","Inv_ControlDespachos");
		$action['volver'] = ModuloGetURL("app","Inv_ControlDespachos","controller","RecibirMercancia",array("buscador"=>$request['buscador']));
		$action['guardar'] = ModuloGetURL("app","Inv_ControlDespachos","controller","RecibirMercancia_Forma",array("buscador"=>$request['buscador']));
		$this->salida = $html->RecibirMercancia_Forma($request,$action,$resultado,$transportadoras);
		return true;
		}
		
    
}