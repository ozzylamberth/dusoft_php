<?php
	/**************************************************************************************
	* $Id: hc_FichaFamiliar.php,v 1.2 2008/10/10 13:44:25 gerardo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.2 $ 	
	* @author Gerardo Amador Vidal
	*
	***************************************************************************************/
	class FichaFamiliar extends hc_classModules{
	
		//Constructor que esta Padre
		function FichaFamiliar(){
			$this->hc_classModules(); //constructor del padre
			
			$this->frmError = array();
			$this->error = '';
			$this->empresa = SessionGetVar('SYSTEM_USUARIO_EMPRESA');
			$this->user_id = UserGetUID();
			
			return true;
		}
		
		/*
		function GetForma(){
			$pfj = $this->frmPrefijo;
			$action = '';
			
			if(!empty($_REQUEST['subModuloAction'])){
				$action = $_REQUEST['subModuloAction'];
			}
			if(!empty($_REQUEST['accion'.$pfj])){
				$action = $_REQUEST['accion'.$pfj];
			}
			
			
			$this->FrmForma($action);
			
			return $this->salida;
		}*/
	
	}
?>