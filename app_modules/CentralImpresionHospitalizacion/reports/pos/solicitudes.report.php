<?php

/**
 * $Id: solicitudes.report.php,v 1.1.1.1 2009/09/11 20:36:19 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de ordenservicio para impresora pos
 */

class solicitudes_report extends pos_reports_class
{

    //constructor por default
    function solicitudes_report()
    {
        $this->pos_reports_class();
        return true;
    }

		/**
		*
		*/
    function CrearReporte()
    {

        $reporte=&$this->driver; //obtener el driver
        $datos=&$this->datos; //obtener los datos enviados al reporte.
	$reporte->PrintFTexto($datos[0][razon_social],true,$align='center',false,true);
	$reporte->PrintFTexto($datos[0][tipo_id_tercero].' '.$datos[0][id],false,'center',false,false);
	//$reporte->PrintFTexto($datos[0][direccion].' '.$datos[0][municipio].' '.$datos[0][departamento],false,'center',false,false);
	$reporte->SaltoDeLinea();
      	$reporte->PrintFTexto('Fecha    : '.date('d/m/Y h:m'),false,'left',false,false);
	//var=$this->NombreUsuario();
	$cad=substr('Atendio : '.$datos[0][usuario_id].' - '.$datos[0][usuario],0,42);
        $reporte->PrintFTexto($cad,false,'left',false,false);
        //$reporte->PrintFTexto('Atendio  : '.$datos[0][usuario_id].' - '.$datos[0][usuario],false,'left',false,false);
	$reporte->SaltoDeLinea();
	$reporte->PrintFTexto('Identifi: '.$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id],false,'left',false,false);
	$reporte->PrintFTexto('Paciente: '.$datos[0][nombre],false,'left',false,false);
	$reporte->PrintFTexto('Cliente : '.$datos[0][nombre_tercero],false,'left',false,false);
	$reporte->PrintFTexto('Plan    : '.$datos[0][plan_descripcion],false,'left',false,false);
	$reporte->PrintFTexto('Tipo Afi: '.$datos[0][tipo_afiliado_nombre].'     Rango: '.$datos[0][rango],false,'left',false,false);
	$fech=explode(".",$datos[0][fecha]);
	$reporte->SaltoDeLinea();
	$pro=$this->Profesional($datos[1][evolucion_id]);
	$reporte->PrintFTexto('Profesional: '.$pro[0][nombre_tercero],false,'left',false,false); 
	for($j=0; $j<sizeof($pro); $j++)
	{
			$reporte->PrintFTexto('Espec: '.$pro[$j][descripcion],false,'left',false,false); 
	}
	$reporte->SaltoDeLinea();   
        $reporte->PrintFTexto('SOLICITUD DE AUTORIZACIONES',true,'center',false,false);

        for($i=1; $i<sizeof($datos);$i++)
	{
	 $reporte->SaltoDeLinea();
	 $inter=$this->Interconsulta($datos[$i][hc_os_solicitud_id]);
	 $reporte->PrintFTexto($datos[$i][hc_os_solicitud_id].' - '.$datos[$i][cargos].' - ( '.$datos[$i][cantidad].' )'.$datos[$i][descar].' '.$inter,false,'left',false,false);
	   if(!empty($datos[$i][trap]))
	   {  $reporte->PrintFTexto($datos[$i][trap].' d�as de Tramite.',false,'left',false,false);  }
	   elseif(!empty($datos[$i][tra]))
	   {  $reporte->PrintFTexto($datos[$i][tra].' d�as de Tramite.',false,'left',false,false);  }
	}
	$reporte->SaltoDeLinea();
	//verifica si el proveedor es interno
	$reporte->PrintEnd();
	//$reporte->OpenCajaMonedera();
	$reporte->PrintCutPaper();
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
    
    function Interconsulta($hc_os_solicitud_id)
    {
            list($dbconn) = GetDBconn();
            $query = "select c.descripcion as especialidad_nombre
                      from hc_os_solicitudes as a, hc_os_solicitudes_interconsultas as b, especialidades as c
                      where a.hc_os_solicitud_id=b.hc_os_solicitud_id and b.especialidad=c.especialidad and 
                      a.hc_os_solicitud_id = $hc_os_solicitud_id";
                      
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            if(!$resulta->EOF)
            {  $var=$resulta->fields[0];  }
            return $var;    
    }

}
?>
