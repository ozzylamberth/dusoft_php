<?php

/**
 * $Id: app_CreacionAgenda_version.php,v 1.2 2005/06/02 18:39:13 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Triage_version
{
	var informacion =array()

	function app_Triage_version()
	{
		$this->informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'fecha_creacion'=>'19/10/2003',
		'autor'=>'Alexander Giraldo'
		);
	}

	function GetVersion()
	{
		return $this->informacion['version'] . '.' . $this->informacion['subversion'];
	}

	function GetInformacion()
	{
		return $this->informacion;
	}
}
?>

		
