<?php

/**
 * $Id: app_InterfazContable_version.php,v 1.1 2005/06/29 22:53:53 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */
 
class app_InterfazContable_version
{
	var $informacion =array();

	function app_InterfazContable_version()
	{
				$this->informacion=array(
				'version'=>'1',
				'subversion'=>'0',
				'revision'=>'0',
				'fecha'=>'01/27/2005',
				'autor'=>'DARLING LILIANA DORADO',
				'descripcion_cambio' => '',
				'requiere_sql' => false,
				'modulos_requeridos' => 'Facturacion_Fiscal 1.0,Pacientes 1.0,Autorizacion 1.0, Soat 1.0, funciones_admision.inc.php 1.0, datospaciente.inc.php 1.0, funciones_facturacion 1.0, funciones_central_impresion 1.0, malla_validadora.inc.php 1.0',
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

		
