<?php
	/**************************************************************************************
	* $Id: MPF.php,v 1.3 2007/02/01 20:54:43 luis Exp $ 
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
	include	 $_ROOT."hc_modules/InscripcionPlanFamiliar/hc_InscripcionPlanFamiliar.php";
	IncludeClass("APD_Solicitudes",null,"hc","Apoyos_Diagnosticos_Solicitud");
	
	class procesos_admin extends rs_server
	{
		function metodosPF($datos)
		{
			$pfj=SessionGetVar("Prefijo");
			$salida="";
			
			$ins=new InscripcionPF();
			
			if(!$datos[2])
			{
				$metodos=$ins->GetMetodosPF();
				if($datos[1])
					$salida.=" 							<input type=\"text\" class=\"input-text\" name=\"otro_metodo$pfj\" size=\"40\">";
			}
			else
			{
				$motivos_susp=$ins->GetMotivosSuspencionPF();
				if($datos[1])
					$salida.=" 							<td><input type=\"text\" class=\"input-text\" name=\"otro_motivo$pfj\" size=\"40\">";
			}
			
			return $salida."º".$datos[1]."º".$datos[2];
		}
		
		function PlanificacionF($datos)
		{
			$pfj=SessionGetVar("Prefijo");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$mensaje="";
			$sel=0;
			$ins=new InscripcionPF();
			$salida="";
			
			$f_ini=$ins->FechaStamp($datos[2]);
			$f_fin=$ins->FechaStamp($datos[3]);
			
			if($f_ini > date("Y-m-d"))
				$mensaje="LA FECHA INICIAL NO PUEDE SER MAYOR QUE LA ACTUAL";
			elseif($f_fin > date("Y-m-d"))
				$mensaje="LA FECHA FINAL NO PUEDE SER MAYOR QUE LA ACTUAL";
			elseif($f_ini > $f_fin)
				$mensaje="LA FECHA FINAL NO PUEDE SER MAYOR QUE LA FECHA FINAL";
			
			$salida=$mensaje;
			
			if(!$mensaje)
			{
				if(!$ins->GuardarHistorialMPF($inscripcion,$datos))
					echo $ins->ErrorDB();
					
				$registros_mpf=$ins->GetDatosHistorialMetodosPF($inscripcion);
				
				$salida = $this->CrearHtml($datos,$registros_mpf);
				$sel=1;
			}
			
			return $salida."ç".$sel;
		}
		
		function CrearHtml($datos,$registros_mpf)
		{
			$salida="";
			
			$salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			$salida.=" <tr class=\"hc_table_submodulo_list_title\">";
			$salida.="		<td>METODO</td>";
			$salida.="		<td>FECHA INICIO</td>";
			$salida.="		<td>FECHA FIN</td>";
			$salida.="		<td>MOTIVO SUSPENSION</td>";
			$salida.="	</tr>";
			$k=0;
			foreach($registros_mpf as $mpf)
			{
				if($k%2==0)
					$estilo="hc_submodulo_list_claro";
				else
					$estilo="hc_submodulo_list_oscuro";
				
				$metodo=$mpf['desc_metodo'];
				if($mpf['sw_otro_met'])
					$metodo=$mpf['otro_metodo'];
				
				$motivo=$mpf['desc_motivo'];
				if($mpf['sw_otro_mot'])
					$motivo=$mpf['otro_motivo_suspencion'];
				
					$salida.=" <tr class=\"$estilo\" align=\"center\">";
					$salida.="		<td>$metodo</td>";
					$salida.="		<td>".$mpf['fecha_ini']."</td>";
					$salida.="		<td>".$mpf['fecha_fin']."</td>";
					$salida.="		<td>$motivo</td>";
					$salida.="	</tr>";
					$k++;
			}
			$salida.="	</table>";
			
			return $salida;
		}
		
		function SolicitarExamen($datos)
		{
			$apd=new APD_Solicitudes();
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$path=SessionGetVar("RutaImg");
			
			if($apd->Insertar_Varias_Solicitudes($datos,$evolucion,$inscripcion,$programa))
				return "<img src=\"".$path."/images/checksi.png\" border=\"0\">";
			else
				return " ERROR - Solicitudes";
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