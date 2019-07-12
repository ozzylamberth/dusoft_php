<?php

/**
 * $Id: app_Central_de_Autorizaciones_version.php,v 1.1.1.1 2009/09/11 20:36:19 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Central_de_Autorizaciones_version
{
	var $informacion =array();

	function app_ListaTrabajo_version()
	{
				$this->informacion=array(
				'version'=>'1',
				'subversion'=>'0',
				'revision'=>'0',
				'fecha'=>'01/27/2005',
				'autor'=>'DARLING LILIANA DORADO, JAIRO DUVAN DIAZ, CLAUDIA ZUÑIGA',
				'descripcion_cambio' => '',
				'requiere_sql' => false,
				'modulos_requeridos' => 'funciones_central_impresion.inc.php 1.0, malla_validadora 1.0',
				'requerimientos_adicionales' => '',
				'version_kernel' => '1.0'
				);
	}

	function GetVersion()
	{
        return $this->informacion;
	}
}
?>

		
