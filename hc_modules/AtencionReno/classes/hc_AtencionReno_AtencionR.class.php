<?php

/**
* Submodulo de AtencionReno
* $Id: hc_AtencionReno_AtencionR.class.php,v 1.2 2007/02/01 20:44:08 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
*/

class AtencionR
{
	function AtencionR()
	{
		return true;
	}
	
	function ErrorDB()
	{
		$this->frmErrorBD=$this->error."<br>".$this->mensajeDeError;
		return $this->frmErrorBD;
	}
	
}
?>