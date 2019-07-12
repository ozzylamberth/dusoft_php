<?php
  /**************************************************************************************
  * $Id:
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * $Revision: 1.2 $   
  * @author Manuel Ruiz Fernandez
  ***************************************************************************************
  */
  
  class FichaFamiliograma extends hc_classModules
  {
    //Constructor Padre
    function FichaFamiliograma()
    {
      $this->hc_classModules(); //Constructor del Padre
      
      $this->frmError = array();
      $this->error = '';
      $this->empresa = SessionGetVar('SYSTEM_USUARIO_EMPRESA');
      $this->user_id = UserGetUID();
      return true;
    }
    /***********************************************************************
    * GetConsulta() llama a la funcion FrmConsulta del submoduloHijo HTML para obtiener el
    * HTML de listado y lo retorna a la funcion xxx del modulo
    *************************************************************************  
    function GetConsulta()
    {
      $this->FrmConsulta();
      return $this->salida;
    }
    /*************************************************************************
    * GetForma() llama a la funcion FrmForma del submoduloHijo HTML para 
    * obtiener el HTML del formulario y lo retorna a la funcion xxx del modulo
    **************************************************************************   
    function GetForma()
    {
      $pfj=$this->frmPrefijo;
      $action='';
      if (!empty($_REQUEST['subModuloAction']))
      {
        $action = $_REQUEST['subModuloAction'];
      }
      if (!empty($_REQUEST['accion'.$pfj]))
      {
        $action = $_REQUEST['accion'.$pfj];
      }
      
      $this->FrmForma($action);
      
      return $this->salida;
    }*/
    
  }

?> 
