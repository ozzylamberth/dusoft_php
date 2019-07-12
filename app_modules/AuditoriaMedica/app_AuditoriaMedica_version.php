<?php

/**
 * $Id: app_AuditoriaMedica_version.php,v 1.1 2005/08/31 12:55:33 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_AuditoriaMedica_version
{
	var $informacion =array();

	function app_AuditoriaMedica_version()
	{
          $this->informacion=array(
          'version'=>'1',
          'subversion'=>'0',
          'revision'=>'0',
          'fecha'=>'25/04/2005',
          'autor'=>'TIZZIANO PEREA OCORO',
          'descripcion_cambio' => '',
          'requiere_sql' => false,
          'modulos_requeridos' => ,
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

		
