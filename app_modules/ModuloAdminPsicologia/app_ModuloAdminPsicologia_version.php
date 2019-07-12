<?php

/**
 * $Id: app_Notas_y_Monitoreo_version.php,v 1.1 2005/09/09 16:26:01 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_ModuloAdminPsicologia_version
{
	var $informacion =array();

	function app_ModuloAdminPsicologia_version()
	{
          $this->informacion=array(
          'version'=>'1',
          'subversion'=>'0',
          'revision'=>'0',
          'fecha'=>'01/09/2005',
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

		
