<?php
  /**************************************************************************************
  * $Id:
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * $Revision: 1.1 $   
  * @author Manuel Ruiz Fernandez
  ***************************************************************************************
  */
  
  class Familiograma extends hc_classModules
  {
    //Constructor Padre
    function Familiograma()
    {
      $this->hc_classModules();
      
      $this->frmError = array();
      $this->error = '';
      $this->empresa = SessionGetVar('SYSTEM_USUARIO_EMPRESA');
      $this->user_id = UserGetUID();
      return true;
    }
  }
?>