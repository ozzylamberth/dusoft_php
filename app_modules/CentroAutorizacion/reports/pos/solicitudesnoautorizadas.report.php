<?php

/**
 * $Id: solicitudesnoautorizadas.report.php,v 1.1.1.1 2009/09/11 20:36:20 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de solicitudesnoautorizadas para impresora pos
 */

class solicitudesnoautorizadas_report extends pos_reports_class
{

    //constructor por default
    function solicitudesnoautorizadas_report()
    {
        $this->pos_reports_class();
        return true;
    }

		/**
		*
		*/
    function CrearReporte()
    {
				IncludeLib("tarifario");
        $reporte=&$this->driver; //obtener el driver
        $datos=&$this->datos; //obtener los datos enviados al reporte.
				$reporte->PrintFTexto($datos[0][razon_social],true,$align='center',false,true);
				//$reporte->SaltoDeLinea();
				$reporte->PrintFTexto($datos[0][tipo_id_tercero].' '.$datos[0][id],false,'center',false,false);
				//$reporte->PrintFTexto($datos[0][direccion].' '.$datos[0][municipio].' '.$datos[0][departamento],false,'center',false,false);
				$reporte->SaltoDeLinea();
        $reporte->PrintFTexto('NO AUTORIZACION SOLICITUD No. '.$datos[1][hc_os_solicitud_id],true,'center',false,false);
				$reporte->SaltoDeLinea();
				//$reporte->PrintFTexto('Fecha    : '.date('d/m/Y h:m'),false,'left',false,false);
				//$reporte->PrintFTexto('Atendio  : '.$datos[0][usuario_id].' - '.$datos[0][usuario],false,'left',false,false);
				$cad=substr('Atendio : '.$datos[0][usuario_id].' - '.$datos[0][usuario],0,42);
        $reporte->PrintFTexto($cad,false,'left',false,false);
				$reporte->SaltoDeLinea();
				$reporte->PrintFTexto('Identifi : '.$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id],false,'left',false,false);
				$reporte->PrintFTexto('Paciente : '.$datos[0][nombre],false,'left',false,false);
				$reporte->PrintFTexto('Cliente  : '.$datos[0][nombre_tercero],false,'left',false,false);
				$reporte->PrintFTexto('Plan     : '.$datos[0][plan_descripcion],false,'left',false,false);
				$reporte->PrintFTexto('Tipo Afi : '.$datos[0][tipo_afiliado_nombre].'     Rango: '.$datos[0][rango],false,'left',false,false);
        $reporte->SaltoDeLinea();
				if(empty($datos[1][evolucion_id]))
				{                         $pro=$datos[1][profesional];
								$reporte->PrintFTexto('Profesional: '.$datos[1][profesional],false,'left',false,false);
								$reporte->SaltoDeLinea();
				}
				else
				{
						$pro=$this->Profesional($datos[1][evolucion_id]);
						$reporte->PrintFTexto('Profesional: '.$pro,false,'left',false,false);
						$reporte->SaltoDeLinea();
				}
        $reporte->PrintFTexto($datos[1][hc_os_solicitud_id].' - '.$datos[1][cargos].' - ( '.$datos[1][cantidad].' )'.$datos[1][descar],false,'left',false,false);
        $reporte->SaltoDeLinea();
        $reporte->PrintFTexto($datos[1][observaciones],false,'left',false,false);
				$reporte->SaltoDeLinea();
				$reporte->PrintEnd();
				//$reporte->OpenCajaMonedera();
				$reporte->PrintCutPaper();
        return true;
    }

    function Profesional($evolucion)
    {
            list($dbconn) = GetDBconn();
            $query = "select c.nombre_tercero
                      from hc_evoluciones as a, profesionales_usuarios as b, terceros as c
                      where a.evolucion_id=".$evolucion." and a.usuario_id=b.usuario_id and
                      b.tipo_tercero_id=c.tipo_id_tercero and b.tercero_id=c.tercero_id";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $var=$resulta->fields[0];
            return $var;    
    }
}
?>
