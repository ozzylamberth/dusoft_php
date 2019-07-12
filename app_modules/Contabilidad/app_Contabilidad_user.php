
<?php

/**
* Modulo de Contabilidad (PHP).
*
*
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Contabilidad_user.php
*
**/

class app_Contabilidad_user extends classModulo
{
	function app_Contabilidad_user()
	{
		return true;
	}

	function main()
	{
		$this->PrincipalParaHC();
		return true;
	}

}//fin de la clase
?>
