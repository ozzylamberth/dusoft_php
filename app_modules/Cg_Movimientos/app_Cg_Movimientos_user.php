<?php
	/**************************************************************************************
	* $Id: app_Cg_Movimientos_user.php,v 1.3 2007/03/29 18:36:39 jgomez Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.3 $
	*
	* @autor Jaime Gomez
  
	***************************************************************************************/
	IncludeClass('MovimientosSQL','','app','Cg_Movimientos');
	class app_Cg_Movimientos_user extends classModulo
	{
       
    /**
    * @var $action Variable donde se guardan los action de las formsa
    **/
    var $action = array();
    /**   
    */
    
    
  	function app_Cg_Documentos_user(){}
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
       $consulta=new MovimientosSQL();
       $this->TodasEmpresas=$consulta->ListarEmpresas();
     }
     function SubMenu()
     { if(!SessionIsSetVar("Creardoc"))
         SessionSetVar("Creardoc",$_REQUEST["Docus"]);
         $this->actionOption21=ModuloGetURL('app','Cg_Documentos','user','ListaDocumentos'); 
         $this->Datos = SessionGetVar("Creardoc");
     }
     
 }
?>