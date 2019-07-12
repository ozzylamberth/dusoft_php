<?php

/**
 * $Id: app_RespuestasAuditor_version.php,v 1.1 2005/11/09 19:23:35 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_RespuestasAuditor_version
{
	var $informacion =array();

	function app_RespuestasAuditor_version()
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

		
