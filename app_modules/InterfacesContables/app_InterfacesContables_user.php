<?php
	/**************************************************************************************
	* $Id: app_InterfacesContables_user.php,v 1.1 2007/01/18 13:30:03 jgomez Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Jaime Gomez
  
	***************************************************************************************/
	IncludeClass('InterfacesSQL','','app','InterfacesContables');
	class app_InterfacesContables_user extends classModulo
	{
       
    /**
    * @var $action Variable donde se guardan los action de las formsa
    **/
    var $action = array();
    /**   
    */
    
    
  	function app_InterfacesContables_user(){}
/**********************************************************************************
    * Funcion donde se crean las variables usadas en la funcion FormaMostrarDocumentos,
    * se averiguan los tipos de documentos
    ***********************************************************************/
    
    function CrearElementos()
    {  if($_REQUEST["Empresas"])
       {
          SessionSetVar("EMPRESAS",$_REQUEST["Empresas"]);
          
          $this->Enterprice = SessionGetVar("EMPRESAS");
          SessionSetVar("EMPRESA",$this->Enterprice['empresa_id']);
          
       }   
       SessionSetVar("rutaImagenes",GetThemePath());
       SessionDelVar("Creardoc");
       
    } 

     function MostrarDocus()
     {
       $consulta=new InterfacesSQL();
       $this->TipsDocumentos = $consulta->ListarTiposDocumentos();
     }
     
     function MostrarEmpresas()
     {
       $consulta=new InterfacesSQL();
       $this->TodasEmpresas=$consulta->ListarEmpresas();
     }
     function SubMenu()
     { if(!SessionIsSetVar("Creardoc"))
         SessionSetVar("Creardoc",$_REQUEST["Docus"]);
         $this->actionOption21=ModuloGetURL('app','InterfacesContables','user','ListaDocumentos'); 
         $this->Datos = SessionGetVar("Creardoc");
     }
     
 }
?>