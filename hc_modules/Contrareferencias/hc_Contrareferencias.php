<?php
	/**************************************************************************************
	* $Id: hc_Contrareferencias.php,v 1.2 2009/03/09 21:40:27 gerardo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.2 $ 	
	* @author Gerardo Amador Vidal
	*
	***************************************************************************************/
class Contrareferencias extends hc_classModules{

    //Constructor que esta Padre
    function Contrareferencias(){
    
        $this->hc_classModules(); //constructor del padre
        
        $this->frmError = array();
        $this->error = '';
        $this->empresa = SessionGetVar('SYSTEM_USUARIO_EMPRESA');
        $this->user_id = UserGetUID();
        
        return true;
        
    }

}
?>