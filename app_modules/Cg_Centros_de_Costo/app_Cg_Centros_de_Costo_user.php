<?php
	/**************************************************************************************
	* $Id: app_Cg_Centros_de_Costo_user.php,v 1.1 2007/02/21 16:47:53 jgomez Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Jaime Gomez
  
	***************************************************************************************/
	IncludeClass('CentrosSQL','','app','Cg_Centros_de_Costo');
	class app_Cg_Centros_de_Costo_user extends classModulo
	{
       
    /**
    * @var $action Variable donde se guardan los action de las formsa
    **/
    var $action = array();
    /**   
    */
    
    
  	function app_Cg_Centros_de_Costo_user(){}
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
       $consulta=new MovimientosSQL();
       $this->TipsDocumentos = $consulta->ListarTiposDocumentos();
     }
     
     function MostrarEmpresas()
     {
       $consulta=new CentrosSQL();
       $this->TodasEmpresas=$consulta->ListarEmpresas();
       SessionSetVar("rutaImagenes",GetThemePath());
     }
     
     
 }
?>