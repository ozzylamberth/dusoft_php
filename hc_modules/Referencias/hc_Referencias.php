<?php
	/**************************************************************************************
	* $Id: hc_Referencias.php,v 1.1 2008/10/10 19:46:01 gerardo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.1 $ 	
	* @author Gerardo Amador Vidal
	*
	***************************************************************************************/
class Referencias extends hc_classModules{
	
	//Constructor que esta Padre
	function Referencias(){
	
		$this->hc_classModules(); //constructor del padre
		
		$this->frmError = array();
		$this->error = '';
		$this->empresa = SessionGetVar('SYSTEM_USUARIO_EMPRESA');
		$this->user_id = UserGetUID();
		
		return true;
		
	}
	
}
?>