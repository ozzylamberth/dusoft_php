<?php

/**
 * $Id:  $
 * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 */

class app_MantenimientoCuentas_adminclasses_HTML extends app_MantenimientoCuentas_admin
{

  function app_MantenimientoCuentas_admin_HTML()
	{
		$this->salida='';
		$this->system_MantenimientoCuentas_admin();
		return true;
	}



}//fin clase user
?>

