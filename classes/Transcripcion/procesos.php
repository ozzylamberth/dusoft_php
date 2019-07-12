<?php
	/**************************************************************************************
	* $Id: procesos.php,v 1.1 2006/12/07 21:25:41 luis Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Hugo F. Manrique	
	**************************************************************************************/
	$VISTA = "HTML";
	$_ROOT = "../../";
	include  "../../classes/rs_server/rs_server.class.php";
	include	 "../../includes/enviroment.inc.php";
	include	 "../../classes/modules/hc_classmodules.class.php";
	$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
	IncludeFile($filename);
	include	 "../../hc_modules/Apoyos_Diagnosticos_Control/hc_Apoyos_Diagnosticos_Control_1.php";
	
	class procesos_admin extends rs_server
	{
		function RetornaDatos($variable)
		{
			$apdC=new Apoyos_Diagnosticos_Control_1();
			return $apdC->GetForma($variable[0],$variable[1],$variable[2],$variable[3],$variable[4],'1');
		}
		
		/********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*********************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
	$oRS = new procesos_admin( array( 'ActivarMenu', 'CrearTabla'));
	$oRS->action();	
?>