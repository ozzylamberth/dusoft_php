<?php
	/**************************************************************************************
	* $Id: hc_EvolucionesNotas.php,v 1.2 2008/10/10 13:46:28 gerardo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.2 $ 	
	* @author Gerardo Amador Vidal
	*
	***************************************************************************************/
	
	class EvolucionesNotas extends hc_classModules{
	
		function EvolucionesNotas(){
			$this->hc_classModules();
			
			$this->frmError = array();
			$this->error = '';
			$this->empresa = SessionGetVar('SYSTEM_USUARIO_EMPRESA');
			$this->user_id = UserGetUID();
			
			return true;
		}
	
	}
?>