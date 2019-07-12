<?php
	/**************************************************************************************
	* $Id: app_Cg_PlanesCuentas_user.php,v 1.1 2006/10/09 20:10:49 jgomez Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Jaime Gomez
  
	***************************************************************************************/
	IncludeClass('CuentasSQL','','app','Cg_PlanesCuentas');
	class app_Cg_PlanesCuentas_user extends classModulo
	{
       
    /**
    * @var $action Variable donde se guardan los action de las formsa
    **/
    var $action = array();
    /**
    * @var $lista Variable donde se guardan la lista de modulos disponibles para premisos
    **/
    var $lista = array();
    /**
    * @var $GSoluciones Variable donde se guardan los valores de los grupos de las soluciones
    **/
  
    
  	function app_Cg_PlanesCuentas_user(){}
	/**********************************************************************************
    * Funcion donde se crean las variables usadas en la funcion FormaMostrarDocumentos,
    * se averiguan los tipos de documentos
    ***********************************************************************************/
    function CrearElementos()
    {
         $cuenta="nada";
//       $slc = new PermisosSQL();
//       $this->lista = $slc->ListarModulos();
        SessionSetVar("rutaImagenes",GetThemePath());
//       //$this->Opcion = SessionGetVar("Opcion");
//       $this->actionOption1 = ModuloGetURL('app','ModuloPermisos','user','FormaMostrarModulos');
//       $this->action[0] = ModuloGetURL('app','ModuloPermisos','user','FormaPerfiles');
//       SessionSetVar("volver",$this->action[0]);
//       SessionDelVar("PermisosModulos");
//     
    } 

     /**********************************************************************************
    * Funcion donde se crean las variables usadas en la funcion FormaMostrarDocumentos,
    * se averiguan los tipos de documentos
    ***********************************************************************************/
    function MostrarModulos()
    {
      //$consulta=new PermisosSQL();
      //$this->Modulos = $consulta->ConsultarPermisos();
    }

    function SubMenu()
    { if(!SessionIsSetVar("PermisosModulos"))
        SessionSetVar("PermisosModulos",$_REQUEST["PerModulos"]);
      
       //$this->actionOption1 = ModuloGetURL('app','ModuloPermisos','user','FormaSubMenu'); 
       //$this->Datos = SessionGetVar("PermisosModulos");
    }
    
    
    
    
    
        
		
	}
?>