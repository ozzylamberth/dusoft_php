<?php

/**
 * $Id: ordenservicioPDF.report.php,v 1.1.1.1 2009/09/11 20:36:19 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de formulamedica para impresora en pdf desde la central de impresion hospitalizacion
 */

class ordenservicioPDF_report extends pdf_reports_class
{
	//constructor por default
	function ordenservicioPDF_report($orientacion,$unidad,$formato,$html)
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
				$html.="<TD ALIGN='CENTER' WIDTH='760'>";
				$html.="<font size='24'><B>ORDEN SERVICIO No. ".$datos[1][orden_servicio_id]."</B></font>";
				$html.="</TD>";
				$html.="</TR>";
				$html.="<BR>";
				$cad=substr('Atendio : '.$datos[0][usuario_id].' - '.$datos[0][usuario],0,42);
				$html.="<TR>";
				$html.="<TD ALIGN='LEFT' WIDTH='760'>";
				$html.="<font size='24'>$cad</font>";
				$html.="</TD>";
				$html.="</TR>";
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

        $total=0;
				$profe='';
        $copago=$moderadora=$nocub=0;
				$html.="<BR>";
				if(!empty($datos[$x][observacion]))
				{
						$html.="<TR>";
						$html.="<TD ALIGN='LEFT' WIDTH='760'>";
						$html.="<font size='24'>Observación: ".$datos[0][observacion]."</font>";
						$html.="</TD>";
						$html.="</TR>";
						$html.="<BR>";
				}

        for($i=1; $i<sizeof($datos);)
        {
            $x=$i;
            while($datos[$i][cargo_cups]==$datos[$x][cargo_cups])
            {
                if(empty($datos[$x][evolucion_id]))
                {
										$pro=$datos[$x][profesional];
										if($pro!=$profe)
                    {
												$profe=$pro;
												$html.="<TR>";
												$html.="<TD ALIGN='LEFT' WIDTH='760'>";
												$html.="<font size='24'>Profesional: ".$datos[$x][profesional]."</font>";
												$html.="</TD>";
												$html.="</TR>";
												$html.="<BR>";
										}
                }
                else
                {
                    $pro=$this->Profesional($datos[$x][evolucion_id]);
										if($pro!=$profe)
                    {
												$profe=$pro;
												$html.="<TR>";
												$html.="<TD ALIGN='LEFT' WIDTH='760'>";
												$html.="<font size='24'>Profesional: ".$pro."</font>";
												$html.="</TD>";
												$html.="</TR>";
												$html.="<BR>";
										}
                }
								$inter=$datos[$x][especialidad_nombre];
								$html.="<TR>";
								$html.="<TD ALIGN='LEFT' WIDTH='760'>";
								$html.="<font size='24'>".$datos[$x][numero_orden_id].' - '.$datos[$x][cargo_cups].' -  ( '.$datos[$i][cantidad].' ) '.$datos[$x][descripcion].' '.$inter."</font>";
								$html.="</TD>";
								$html.="</TR>";
								$html.="<BR>";

								if(!empty($datos[$x][obsapoyo]))
								{
										$html.="<TR>";
										$html.="<TD ALIGN='LEFT' WIDTH='760'>";
										$html.="<font size='24'>Observación: ".$datos[$x][obsapoyo]."</font>";
										$html.="</TD>";
										$html.="</TR>";
								}
								if(!empty($datos[$x][obsinter]))
								{
										$html.="<TR>";
										$html.="<TD ALIGN='LEFT' WIDTH='760'>";
										$html.="<font size='24'>Observación: ".$datos[$x][obsinter]."</font>";
										$html.="</TD>";
										$html.="</TR>";
								}
								if(!empty($datos[$x][obsnoqx]))
								{
										$html.="<TR>";
										$html.="<TD ALIGN='LEFT' WIDTH='760'>";
										$html.="<font size='24'>Observación: ".$datos[$x][obsnoqx]."</font>";
										$html.="</TD>";
										$html.="</TR>";
								}

								$html.="<TR>";
								$html.="<TD ALIGN='LEFT' WIDTH='760'>";
								$html.="<font size='24'>Valida a Partir de: ".$this->FechaStamp($datos[$x][fecha_activacion])."</font>";
								$html.="</TD>";
								$html.="</TR>";
								$html.="<TR>";
								$html.="<TD ALIGN='LEFT' WIDTH='760'>";
								$html.="<font size='24'>Fecha Vencimiento : ".$this->FechaStamp($datos[$x][fecha_vencimiento])."</font>";
								$html.="</TD>";
								$html.="</TR>";
								if(!empty($datos[$x][requisitos]))
								{
										$html.="<BR>";
										$html.="<TR>";
										$html.="<TD ALIGN='LEFT' WIDTH='760'>";
										$html.="<font size='24'>Recomendaciones: ".$datos[$x][requisitos]."</font>";
										$html.="</TD>";
										$html.="</TR>";
										$html.="<BR>";
								}
								$x++;
            }
						$i=$x;
         }
       	$html.="<BR>";
        //verifica si el proveedor es interno
        if(!empty($datos[1][desdpto]))
        {
						$html.="<TR>";
						$html.="<TD ALIGN='LEFT' WIDTH='760'>";
						$html.="<font size='24'><B>PRESTADOR : ".$datos[1][desdpto].' - '.$datos[0][razon_social]."</B></font>";
						$html.="</TD>";
						$html.="</TR>";
						$html.="<TR>";
						$html.="<TD ALIGN='LEFT' WIDTH='760'>";
						$html.="<font size='24'>Dirección : ".$datos[0][direccion]."</font>";
						$html.="</TD>";
						$html.="</TR>";
						$html.="<TR>";
						$html.="<TD ALIGN='LEFT' WIDTH='760'>";
						$html.="<font size='24'>Telefonos : ".$datos[0][telefonos]."</font>";
						$html.="</TD>";
						$html.="</TR>";
        }
        elseif(!empty($datos[1][nompro]))
        {
						$html.="<TR>";
						$html.="<TD ALIGN='LEFT' WIDTH='760'>";
						$html.="<font size='24'>".'PRESTADOR : '.$datos[1][nompro]."</font>";
						$html.="</TD>";
						$html.="</TR>";
						$html.="<TR>";
						$html.="<TD ALIGN='LEFT' WIDTH='760'>";
						$html.="<font size='24'>".'Dirección : '.$datos[1][dirpro]."</font>";
						$html.="</TD>";
						$html.="</TR>";
						$html.="<TR>";
						$html.="<TD ALIGN='LEFT' WIDTH='760'>";
						$html.="<font size='24'>".'Telefonos : '.$datos[1][telpro]."</font>";
						$html.="</TD>";
						$html.="</TR>";
        }
        if($datos[1][sw_estado]==7)
        {
						$html.="<BR>";
						$html.="<TR>";
						$html.="<TD ALIGN='LEFT' WIDTH='760'>";
						$html.="<font size='24'>".'NOTA: '.$datos[0][nombre_tercero].' por favor hacer Tramite de la Transcripción a '.$datos[0][razon_social]."</font>";
						$html.="</TD>";
						$html.="</TR>";
        }
				$html.="<BR>";
        //$reporte->SaltoDeLinea();
				$cargo_liq=array();
				$d=1;
				while($d<sizeof($datos))
				{
						$cargo_liq[]=array('tarifario_id'=>$datos[$d]['tarifario_id'],'cargo'=>$datos[$d]['cargo'],'cantidad'=>1,'autorizacion_int'=>$datos[$d]['autorizacion_int'],'autorizacion_ext'=>$datos[$d]['autorizacion_ext']);
						$d++;
				}
				$cargo_fact=array();
				$cargo_fact=LiquidarCargosCuentaVirtual($cargo_liq,'','','',$datos[0][plan_id] ,$datos[0][tipo_afiliado_id] ,$datos[0][rango] ,$datos[0][semanas_cotizacion],$datos[0][servicio]);
				$copago=$cargo_fact[valor_cuota_paciente];
				$moderadora=$cargo_fact[valor_cuota_moderadora];
				$total=$cargo_fact[valor_total_paciente];

				$html.="<TR>";
				$html.="<TD ALIGN='LEFT' WIDTH='660'>";
				$html.="<font size='24'>".$datos[0][nombre_copago]."</font>";
				$html.="</TD>";
				$html.="<TD ALIGN='LEFT' WIDTH='100'>";
				$html.="<font size='24'>".$copago."</font>";
				$html.="</TD>";
				$html.="</TR>";
				$html.="<TR>";
				$html.="<TD ALIGN='LEFT' WIDTH='660'>";
				$html.="<font size='24'>".$datos[0][nombre_cuota_moderadora]."</font>";
				$html.="</TD>";
				$html.="<TD ALIGN='LEFT' WIDTH='100'>";
				$html.="<font size='24'>".$moderadora."</font>";
				$html.="</TD>";
				$html.="</TR>";
				if($nocub > 0)
				{
						$html.="<TR>";
						$html.="<TD ALIGN='LEFT' WIDTH='660'>";
						$html.="<font size='24'>'Valor no Cubierto'</font>";
						$html.="</TD>";
						$html.="<TD ALIGN='LEFT' WIDTH='100'>";
						$html.="<font size='24'>".$nocub."</font>";
						$html.="</TD>";
						$html.="</TR>";
				}
				$html.="<TR>";
				$html.="<TD ALIGN='LEFT' WIDTH='660'>";
				$html.="<font size='24'>TOTAL A PAGAR</font>";
				$html.="</TD>";
				$html.="<TD ALIGN='LEFT' WIDTH='100'>";
				$html.="<font size='24'>".$total."</font>";
				$html.="</TD>";
				$html.="</TR>";
				$pdf->WriteHTML($html);
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


  /**
  * Separa la fecha del formato timestamp
  * @access private
  * @return string
  * @param date fecha
  */
   function FechaStamp($fecha)
   {
      if($fecha){
          $fech = strtok ($fecha,"-");
          for($l=0;$l<3;$l++)
          {
            $date[$l]=$fech;
            $fech = strtok ("-");
          }
          return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
//          return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
      }
  }
}
?>

