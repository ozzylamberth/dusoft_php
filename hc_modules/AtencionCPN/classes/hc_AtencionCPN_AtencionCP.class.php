<?php

/**
* Submodulo de AtencionCPN
* $Id: hc_AtencionCPN_AtencionCP.class.php,v 1.3 2007/02/01 20:43:56 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
*/

class AtencionCP
{
	function AtencionCP()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}
}
?>