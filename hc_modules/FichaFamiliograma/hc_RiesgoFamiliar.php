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

  class RiesgoFamiliar extends hc_classModules
  {
    //Constructor Padre
    function RiesgoFamiliar()
    {
      $this->hc_classModules(); //Constructor del Padre
      
      $this->frmError = array();
      $this->error = '';
      $this->empresa = SessionGetVar('SYSTEM_USUARIO_EMPRESA');
      $this->user_id = UserGetUID();
      return true;
    }
  }
?> 
