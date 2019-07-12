<?php
	/**************************************************************************************
	* $Id: app_Cg_LapsosContables_user.php,v 1.1 2007/03/06 18:18:19 jgomez Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Jaime Gomez
  
	***************************************************************************************/
	IncludeClass('LapsosSQL','','app','Cg_LapsosContables');
	class app_Cg_LapsosContables_user extends classModulo
	{
       
    /**
    * @var $action Variable donde se guardan los action de las formsa
    **/
    var $action = array();
    /**   
    */
    
    
  	function app_Cg_LapsosContables_user(){}
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
       
       SessionDelVar("Creardoc");
       $VECTOR[-1]=1;
       SessionSetVar("Verctorcillo",$VECTOR);
    } 

     function MostrarDocus()
     {
       $consulta=new LapsosSQL();
       $this->TipsDocumentos = $consulta->ListarTiposDocumentos();
     }
     
     function MostrarEmpresas()
     {
       $consulta=new LapsosSQL();
       $this->TodasEmpresas=$consulta->ListarEmpresas();
       SessionSetVar("rutaImagenes",GetThemePath());
     }
     
     
 }
?>