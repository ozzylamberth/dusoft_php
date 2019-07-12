<?php
	/**************************************************************************************
	* $Id: IngresoSolicitud.php,v 1.2 2007/02/01 20:44:37 luis Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Hugo F. Manrique	
	**************************************************************************************/
	$VISTA = "HTML";
	$_ROOT = "../../../";
	include  $_ROOT."classes/rs_server/rs_server.class.php";
	include	 $_ROOT."includes/enviroment.inc.php";
	include	 $_ROOT."classes/modules/hc_classmodules.class.php";
	$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
	IncludeFile($filename);
	include	 $_ROOT."hc_modules/CronogramaCitasyProcedimientos/hc_CronogramaCitasyProcedimientos.php";
	
	class procesos_admin extends rs_server
	{
		function IngresoSolicitud($datos)
		{
			$cronograma=new CitasyProcedimientos();
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$path=SessionGetVar("ImgRuta");
			$proc[0]=$datos[0];
			
			if($cronograma->GuardarProcedimientosSolicitados($evolucion,$inscripcion,$programa,$proc,$datos[1],$datos[2]))
				$salida.="	<label class=\"label\"><img src=\"".$path."/images/checksi.png\" border=\"0\"></label>";
			else
					echo $cronograma->error;
			return $salida;
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
	$oRS = new procesos_admin();
	$oRS->action();	
?>