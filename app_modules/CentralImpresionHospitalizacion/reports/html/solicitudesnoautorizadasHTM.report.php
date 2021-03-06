<?php

/**
 * $Id: solicitudesnoautorizadasHTM.report.php,v 1.1.1.1 2009/09/11 20:36:19 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

 
class solicitudesnoautorizadasHTM_report
{
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function solicitudesnoautorizadasHTM_report($datos=array())
	{
			$this->datos=$datos;
			return true;
	}


	function GetMembrete()
	{
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
												'subtitulo'=>'',
												'logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
	}
    /**
    *
    */
    function CrearReporte()
    {
				IncludeLib("tarifario_cargos");
				IncludeLib("funciones_central_impresion");
				$datos[0]=EncabezadoReporteSolicitud($this->datos['solicitud'],$this->datos['TipoDocumento'],$this->datos['Documento']);
				$dat=ReporteSolicitudNoAuto($this->datos['solicitud']);

				$Salida .="<TABLE BORDER='0' WIDTH='100%' ALIGN='LEFT'>";
				$Salida.="<TR>";
				$Salida.="<TD ALIGN='CENTER' WIDTH='100%' class=\"titulo2\" colspan=\"2\">".strtoupper($datos[0][razon_social])."</b>";
				$Salida.="</TD>";
				$Salida.="</TR>";
				$Salida.="<TR>";
				$Salida.="<TD ALIGN='CENTER' WIDTH='100%' class=\"normal_10\" colspan=\"2\">".$datos[0][tipo_id_tercero].': '.$datos[0][id]."";
				$Salida.="</TD>";
				$Salida.="</TR>";
				$Salida.="<TR>";
				$Salida.="<TD ALIGN='CENTER' WIDTH='100%' class=\"titulo2\" colspan=\"2\">NO AUTORIZACION SOLICITUD No. ".$dat[hc_os_solicitud_id]."";
				$Salida.="</TD>";
				$Salida.="</TR>";
				$Salida.="<TR><TD>&nbsp;</TD></TR>";
				$Salida.="<TR>";
				$Salida.="<TD ALIGN='LEFT' WIDTH='30%' class=\"normal_10\">Fecha    : ".date('d/m/Y h:m')."";
				$Salida.="</TD>";
				$Salida.="<TD ALIGN='LEFT' class=\"normal_10\">Atendio : ".$datos[0][usuario_id]." - ".$datos[0][usuario]."";
				$Salida.="</TD>";
				$Salida.="</TR>";
				$Salida.="<TR>";
				$Salida.="<TD ALIGN='LEFT' class=\"normal_10\">Identifi: ".$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id]."";
				$Salida.="</TD>";
				$Salida.="<TD ALIGN='LEFT' class=\"normal_10\">Paciente: ".$datos[0][nombre]."";
				$Salida.="</TD>";
				$Salida.="</TR>";
				$Salida.="<TR>";
				$Salida.="<TD ALIGN='LEFT' class=\"normal_10\">Cliente : ".$datos[0][nombre_tercero]."";
				$Salida.="</TD>";
				$Salida.="<TD ALIGN='LEFT' class=\"normal_10\">Plan    : ".$datos[0][plan_descripcion]."";
				$Salida.="</TD>";
				$Salida.="</TR>";
				$Salida.="<TR>";
				$Salida.="<TD ALIGN='LEFT' class=\"normal_10\">Tipo Afi: ".$datos[0][tipo_afiliado_nombre]."";
				$Salida.="</TD>";
				$Salida.="</TR>";
				$Salida.="<TR><TD>&nbsp;</TD></TR>";
				if(empty($dat[evolucion_id]))
				{
						$pro=$dat[profesional];
						$Salida.="<TR>";
						$Salida.="<TD ALIGN='LEFT' WIDTH='100%' class=\"normal_10\" colspan=\"2\">Profesional: ".$dat[profesional]."";
						$Salida.="</TD>";
						$Salida.="</TR>";
				}
				else
				{
						$pro=$this->Profesional($dat[evolucion_id]);
						$Salida.="<TR>";
						$Salida.="<TD ALIGN='LEFT' WIDTH='100%' class=\"normal_10\" colspan=\"2\">Profesional: ".$pro[0][nombre_tercero]."";
						$Salida.="</TD>";
						$Salida.="</TR>";
				}
				$diag=Diagnostico($dat[evolucion_id]);
				$Salida.="<TR>";
				$Salida.="<TD ALIGN='LEFT' WIDTH='100%' class=\"normal_10\" colspan=\"2\">Diagnosticos: ".$diag."";
				$Salida.="</TD>";
				$Salida.="</TR>";
				$Salida.="<TR><TD>&nbsp;</TD></TR>";
				$Salida.="<TR>";
				$Salida.="<TD ALIGN='LEFT' WIDTH='100%' class=\"normal_10\" colspan=\"2\">".$dat[hc_os_solicitud_id].' - '.$dat[cargos].' - ( '.$dat[cantidad].' )'.$dat[descar]."";
				$Salida.="</TD>";
				$Salida.="</TR>";
				$Salida.="<TR><TD>&nbsp;</TD></TR>";
				$dat[observaciones]=str_replace("\x0a","<br>",$dat[observaciones]);
				$Salida.="<TR>";
				$Salida.="<TD ALIGN='LEFT' WIDTH='100%' class=\"normal_10\" colspan=\"2\">".$dat[observaciones]."";
				$Salida.="</TD>";
				$Salida.="</TR>";
				$Salida.="</TABLE>";
				return $Salida;
    }

		/**
		*
		*/
		function Profesional($evolucion)
		{
					list($dbconn) = GetDBconn();
					$query = "select c.nombre_tercero, f.especialidad, g.descripcion
										from hc_evoluciones as a, profesionales_usuarios as b, terceros as c,
										profesionales_especialidades as f, especialidades as g
										where a.evolucion_id=".$evolucion." and a.usuario_id=b.usuario_id
										and b.tipo_tercero_id=c.tipo_id_tercero and b.tercero_id=c.tercero_id
										and f.tipo_id_tercero=c.tipo_id_tercero and f.tercero_id=c.tercero_id
										and f.especialidad=g.especialidad";
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					while(!$resulta->EOF)
					{
						$var[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
					}
					$resulta->Close();
					return $var;
		}
}
?>

