<?php

/**
 * $Id: solicitudesnoautorizadasPDF.report.php,v 1.1.1.1 2009/09/11 20:36:19 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de formulamedica para impresora en pdf desde la central de impresion hospitalizacion
 */

class solicitudesnoautorizadasPDF_report extends pdf_reports_class
{
	//constructor por default
	function solicitudesnoautorizadasPDF_report($orientacion,$unidad,$formato,$html)
	{
			$this->pdf_reports_class($orientacion,$unidad,$formato,$html);
			return true;
	}


    /**
    *
    */
    function CrearReporte()
    {
        IncludeLib("tarifario_cargos");
				$_SESSION['REPORTES']['VARIABLE']='';				
				$pdf=&$this->driver; //obtener el driver
				$datos=&$this->datos; //obtener los datos enviados al reporte.
				$pdf->AddPage();
				$pdf->SetFont('Arial','B',9);

				//ENCABEZADO DE LA PAGINA
				$html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='LEFT'>";
				if(is_file('images/logocliente.png'))
				{
					$html.="".$pdf->image('images/logocliente.png',10,6,18)."";
				}

				$html.="<TR>";
				$html.="<TD ALIGN='CENTER' WIDTH='760'><br><br>";
				$html.="<font size='24'><b>".strtoupper($datos[0][razon_social])."</b></font>";
				$html.="</TD>";
				$html.="</TR>";
				$html.="<TR>";
				$html.="<TD ALIGN='CENTER' WIDTH='760'>";
				$html.="<font size='24'>".$datos[0][tipo_id_tercero].': '.$datos[0][id]."</font>";
				$html.="</TD>";
				$html.="</TR>";
				$html.="<BR>";
				$html.="<TR>";
				$html.="<TD ALIGN='LEFT' WIDTH='760'>";
				$html.="<font size='24'>Fecha    : ".date('d/m/Y h:m')."</font>";
				$html.="</TD>";
				$html.="</TR>";
				$cad=substr('Atendio : '.$datos[0][usuario_id].' - '.$datos[0][usuario],0,42);
				$html.="<TR>";
				$html.="<TD ALIGN='LEFT' WIDTH='760'>";
				$html.="<font size='24'>$cad</font>";
				$html.="</TD>";
				$html.="</TR>";
				$html.="<BR>";
				$html.="<TR>";
				$html.="<TD ALIGN='LEFT' WIDTH='760'>";
				$html.="<font size='24'>Identifi: ".$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id]."</font>";
				$html.="</TD>";
				$html.="</TR>";
				$html.="<TR>";
				$html.="<TD ALIGN='LEFT' WIDTH='760'>";
				$html.="<font size='24'>Paciente: ".$datos[0][nombre]."</font>";
				$html.="</TD>";
				$html.="</TR>";
				$html.="<TR>";
				$html.="<TD ALIGN='LEFT' WIDTH='760'>";
				$html.="<font size='24'>Cliente : ".$datos[0][nombre_tercero]."</font>";
				$html.="</TD>";
				$html.="</TR>";
				$html.="<TR>";
				$html.="<TD ALIGN='LEFT' WIDTH='760'>";
				$html.="<font size='24'>Plan    : ".$datos[0][plan_descripcion]."</font>";
				$html.="</TD>";
				$html.="</TR>";
				$html.="<TR>";
				$html.="<TD ALIGN='LEFT' WIDTH='760'>";
				$html.="<font size='24'>Tipo Afi: ".$datos[0][tipo_afiliado_nombre]."</font>";
				$html.="</TD>";
				$html.="</TR>";
				$html.="</TABLE>";

				$html.="<BR>";
				if(empty($datos[1][evolucion_id]))
				{
								$pro=$datos[1][profesional];
								$html.="<TR>";
								$html.="<TD ALIGN='LEFT' WIDTH='760'>";
								$html.="<font size='24'>Profesional: ".$datos[1][profesional]."</font>";
								$html.="</TD>";
								$html.="</TR>";
								$html.="<BR>";

				}
				else
				{
						$pro=$this->Profesional($datos[1][evolucion_id]);
						$html.="<TR>";
						$html.="<TD ALIGN='LEFT' WIDTH='760'>";
						$html.="<font size='24'>Profesional: ".$pro."</font>";
						$html.="</TD>";
						$html.="</TR>";
						$html.="<BR>";
				}
				$html.="<TR>";
				$html.="<TD ALIGN='LEFT' WIDTH='760'>";
				$html.="<font size='24'> ".$datos[1][hc_os_solicitud_id].' - '.$datos[1][cargos].' - ( '.$datos[1][cantidad].' )'.$datos[1][descar]."</font>";
				$html.="</TD>";
				$html.="</TR>";
				$html.="<BR>";
				$html.="<TR>";
				$html.="<TD ALIGN='LEFT' WIDTH='760'>";
				$html.="<font size='24'>".$datos[1][observaciones]."</font>";
				$html.="</TD>";
				$html.="</TR>";
				$html.="<BR>";
				$pdf->WriteHTML($html);
        return true;
    }


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

