<?php
	/**************************************************************************************
	* $Id: app_Inv_MovimientosBodegas_user.php,v 1.1 2009/07/17 19:08:17 johanna Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Jaime Gomez
  
	***************************************************************************************/
	IncludeClass('MovBodegasSQL','','app','Inv_MovimientosBodegas');
	class app_Inv_MovimientosBodegas_user extends classModulo
	{
       
    /**
    * @var $action Variable donde se guardan los action de las formsa
    **/
    var $action = array();
    /**   
    */
    
    
  	function app_Inv_MovimientosBodegas_user(){}
/**********************************************************************************
    * Funcion donde se crean las variables usadas en la funcion FormaMostrarDocumentos,
    * se averiguan los tipos de documentos
    ***********************************************************************/
    
    function CrearElementos()
    {  if($_REQUEST["Empresas"])
       {
          SessionSetVar("EMPRESAS",$_REQUEST["Empresas"]);
          
          //print_r($_REQUEST["Empresas"]);
          
          $this->Enterprice = SessionGetVar("EMPRESAS");
          SessionSetVar("EMPRESA",$this->Enterprice['empresa_id']);
          
       }   
       SessionSetVar("rutaImagenes",GetThemePath());
       SessionDelVar("Creardoc");
       $VECTOR[-1]=1;
       SessionSetVar("Verctorcillo",$VECTOR);
    } 

     function MostrarEmpresas()
     {
       $consulta=new MovBodegasSQL();
       $this->TodasEmpresas=$consulta->ListarEmpresas();
     }
     function SubMenu()
     { if(!SessionIsSetVar("Creardoc"))
         SessionSetVar("Creardoc",$_REQUEST["Docus"]);
         
         
     }
     
 }
?>