<?php

/**
 * $Id: app_Mige_user.php,v 1.2 2005/12/15 14:23:59 ehudes Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 * 
 * Módulo para abrir el MIGE desde una sesion de SIIS
 */

/**
 * Clase user para instanciar un objeto de tipo Ras
 * y conectarse al Ras y el Mige
 *
 * @author    Ehudes F. García Gil <ehufer@hotmail.com>
 * @version   $Revision: 1.2 $
 * @package   IPSOFT-SIIS
 */
class app_Mige_user extends classModulo
{
	/**
	 * Variable para almacenar un objeto Ras
	 * @var object
	 * @access public
	 */
	var $ras;
	
	/**
	 * Constructor
	 *
	 * @access public
	 */
	function app_Mige_user()
	{
		IncludeClass('Ras');
		$this->ras=new Ras(ModuloGetVar('app','Mige','ServerMige'),ModuloGetVar('app','Mige','PuertoMige'));
	}//Fin del constructor
}//Fin de la clase app_Mige_user
?>