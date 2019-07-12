<?php
	/**************************************************************************************
	* $Id: app_Inv_MovimientosBodegasReportes_user.php,v 1.1 2007/07/17 22:24:14 jgomez Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Jaime Gomez
  
	***************************************************************************************/
	IncludeClass('MovBodegasReportSQL','','app','Inv_MovimientosBodegasReportes');
	class app_Inv_MovimientosBodegasReportes_user extends classModulo
	{
       
    /**
    * @var $action Variable donde se guardan los action de las formsa
    **/
    var $action = array();
    /**   
    */
    
    
  	function app_Inv_MovimientosBodegasReportes_user(){}
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
    }

     function MostrarEmpresas()
     {
       $consulta=new MovBodegasReportSQL();
       $this->TodasEmpresas=$consulta->ListarEmpresas();
     }

 }
?>