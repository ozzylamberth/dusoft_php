<?php

/**
 * $Id: ordenservicioHTM.report.php,v 1.2 2010/02/23 13:43:22 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class ordenservicioHTM_report
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
	function ordenservicioHTM_report($datos=array())
	{
			$this->datos=$datos;
			return true;
	}


	function GetMembrete()
	{
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$_SESSION['DATOSRPT']['razon_social'].'<BR>'.$_SESSION['DATOSRPT']['tipo_id_tercero'],
												'subtitulo'=>'',
												'logo'=>'logocliente.png','align'=>'left','height'=>'80','width'=>'80'));
			UNSET($_SESSION['DATOSRPT']);
			return $Membrete;
	}
    /**
    *
    */
    function CrearReporte()
    {
        IncludeLib("tarifario_cargos");
				IncludeLib("funciones_facturacion");
				IncludeLib("funciones_central_impresion");
				$dat=ReporteOrdenServicio($this->datos[orden]);
				$datos[0]=EncabezadoReporteOrden($this->datos[orden]);
				$_SESSION['DATOSRPT']['razon_social']=$datos[0][razon_social];
				$_SESSION['DATOSRPT']['tipo_id_tercero']=$datos[0][tipo_id_tercero].': '.$datos[0][id];
				$Salida ="<TABLE BORDER='1' WIDTH='100%' ALIGN='center'>";
				$Salida.="<TR>";
                                
				$Salida.="<TD colspan=\"2\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10N\">ORDEN SERVICIO No. ".$dat[0][orden_servicio_id]."";
				$Salida.="</TD>";
				$Salida.="<TD colspan=\"2\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Atendio : ".$datos[0][usuario_id].' - '.$datos[0][usuario]."";
				$Salida.="</TD>";
				$Salida.="</TR>";
				$Salida.="<TR>";
				$Salida.="<TD  ALIGN='LEFT'  class=\"normal_10\" WIDTH='25%'>Identificaci�n: ".$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id]."";
				$Salida.="</TD>";
				$Salida.="<TD  ALIGN='LEFT' class=\"normal_10\" WIDTH='35%'>Paciente : ".$datos[0][nombre]."";
				$Salida.="</TD>";
				$EdadArr=CalcularEdad($datos[0][fecha_nacimiento],'');
				$Edad=$EdadArr['edad_aprox'];
				$Salida.="<TD  ALIGN='LEFT' class=\"normal_10\" WIDTH='26%'>Edad : ".$Edad."&nbsp;&nbsp; Sexo :".$datos[0][sexo_id]."";
				$Salida.="</TD>";
				$hc=$this->Historia($datos[0][tipo_id_paciente],$datos[0][paciente_id]);
				if(empty($hc[prefijo]) AND empty($hc[numero]))
				{  $hc[prefijo]=$datos[0][tipo_id_paciente];   $hc[numero]=$datos[0][paciente_id];  }
				$Salida.="<TD  ALIGN='LEFT' class=\"normal_10\" WIDTH='25%'>HC : ".$hc[prefijo]."".$hc[numero]."";
				$Salida.="</TD>";
				$Salida.="</TR>";
				//$Salida.="</TABLE>";
				//$Salida .="<TABLE BORDER='1' WIDTH='100%' ALIGN='center'>";
				$Salida.="<TR>";
				$Salida.="<TD ALIGN='LEFT'  class=\"normal_10\" WIDTH='25%'>Fecha Solicitud: ".$this->FechaStamp($datos[0][fechasolicitud])."";
				$Salida.="</TD>";
				$Salida.="<TD  ALIGN='LEFT' class=\"normal_10\">Fecha Ingreso: ".$this->FechaStamp($datos[0][fechaingreso])."";
				$Salida.="</TD>";
				if(!empty($datos[0][ingreso]))
				{  $cama=BuscarCamaActiva($datos[0][ingreso]);  }
				else
				{
						$res=$this->BuscarCama($this->datos[orden]);
						$cama=$res[cama];
				}
				$Salida.="<TD  ALIGN='LEFT' class=\"normal_10\" colspan=\"2\">Cama: $cama";
				$Salida.="</TD>";
				$Salida.="</TR>";
                                if(!empty($dat[0]['horas_estimadas']))
				  $Salida.="<TD  ALIGN='LEFT' class=\"normal_10\" colspan=\"2\">Tiempo estimado de la cirugía: ".$dat[0]['horas_estimadas']." Horas:".$dat[0]['minutos_estimados']." ";
				  $Salida.="</TD>";
				$Salida.="</TR>";
				$Salida.="<TR>";
				$Salida.="<TD ALIGN='LEFT'  class=\"normal_10\" WIDTH='25%'>Cliente     : ".$datos[0][nombre_tercero]."";
				$Salida.="</TD>";
				$Salida.="<TD  ALIGN='LEFT' class=\"normal_10\">Plan        : ".$datos[0][plan_descripcion]."";
				$Salida.="</TD>";
				$Salida.="<TD  ALIGN='LEFT' class=\"normal_10\" colspan=\"2\">Tipo Afiliado: ".$datos[0][tipo_afiliado_nombre]."";
				$Salida.="</TD>";
				$Salida.="</TR>";
				$Salida.="<TR>";
				$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">&nbsp;";
				$Salida.="</TD>";
				$Salida.="</TR>";
				//$Salida.="</TABLE>";

				//$Salida .="<TABLE BORDER='1' WIDTH='100%' ALIGN='center'>";

        $total=0;
				$profe='';
        $copago=$moderadora=$nocub=0;
//$Salida.="<pre>".print_r($dat,true)."</pre>";
				//$Salida.="<TR><TD colspan=\"2\">&nbsp;</TD></TR>";
				if(!empty($datos[$x][observacion]))
				{
						$Salida.="<TR>";
						$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Observaci�n: ".$datos[0][observacion]."";
						$Salida.="</TD>";
						$Salida.="</TR>";
						$Salida.="<TR><TD colspan=\"4\">&nbsp;</TD></TR>";
				}
		
        for($i=0; $i<sizeof($dat);)
        {
            $x=$i;
            while($dat[$i][cargo_cups]==$dat[$x][cargo_cups])
            {
                if(empty($dat[$x][evolucion_id]))
                {
										$pro=$dat[$x][profesional];
										if($pro!=$profe)
										{
												$profe=$pro;
												$Salida.="<TR>";
												$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Profesional: ".$dat[$x][profesional]."";
												$Salida.="</TD>";
												$Salida.="</TR>";
												//$Salida.="<TR><TD colspan=\"2\">&nbsp;</TD></TR>";
										}
                }
                else
                {
                    $pro=$this->Profesional($dat[$x][evolucion_id]);
										if($pro[0]['nombre_tercero']!=$profe)
										{
												$profe=$pro[0]['nombre_tercero'];
												$Salida.="<TR>";
												if($pro[0][tarjeta_profesional] != '')
												{
        											$Salida.="<TD colspan=\"2\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Profesional: ".$pro[0]['nombre_tercero']."<br>".$pro[0][tipo_id_tercero].': '.$pro[0][tercero_id].' - T.P.: '.$pro[0][tarjeta_profesional].' - '.$pro[0][descripcion]."";
												}
												else
												{
													$Salida.="<TD colspan=\"2\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Profesional: ".$pro[0]['nombre_tercero']."<br>".$pro[0][tipo_id_tercero].': '.$pro[0][tercero_id]." ".$pro[0][descripcion]."";
												}
												$Salida.="</TD>";
												$Salida.="<TD colspan=\"2\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\"><IMG SRC='images/firmas_profesionales/".$pro[0]['firma']."'>";
												$Salida.="</TD>";
												$Salida.="</TR>";
												//$Salida.="<TR><TD colspan=\"2\">&nbsp;</TD></TR>";
										}
                }
				//$Salida.="<TD ALIGN=\"LEFT\" ><IMG SRC='images/firmas_profesionales/".$pro['firma']."'></td>";
								//$diag=Diagnostico($dat[$x][evolucion_id]);
               /* $diag=DiagnosticoSolicitud($dat[$x][hc_os_solicitud_id]);
								if(!empty($diag))
								{
										$Salida.="<TR>";
										$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Diagnosticos: ".$diag."";
										$Salida.="</TD>";
										$Salida.="</TR>";
								}*/
                //$Salida .= "<pre>".print_r($dat,true)."</pre>";
                $diag=DiagnosticoCompleto($dat[$x][evolucion_id]);
                //$Salida .= "<pre>".print_r($diag,true)."</pre>";
                $diagS=DiagnosticoSolicitudCompleto($dat[$x][hc_os_solicitud_id]);

                //$Salida .= "<pre>".print_r($dat,true)."</pre>";
                
                //$Salida .= "<pre>".print_r($diagS,true)."</pre>";
                if(!empty($diagS))
                   $diagnostico=$diagS;
                else
        	   $diagnostico=$diag;
               	foreach($diagnostico as $key => $dtl)
                {
			$Salida.="<TR>";
			$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Diagnosticos: ".$dtl['diagnostico_id']." ".$dtl['diagnostico_nombre']." ";
			$Salida.="</TD>";
			$Salida.="</TR>";
		}
              
    						//$Salida.="<TR><TD colspan=\"2\">&nbsp;</TD></TR>";
							/*	$inter=$dat[$x][especialidad_nombre];
								$Salida.="<TR>";
   							$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\"><B>".$dat[$x][numero_orden_id].' - '.$dat[$x][cargo_cups].' -  ( '.$dat[$i][cantidad].' ) '.$dat[$x][descripcion].' '.$inter."<B>";
								$Salida.="</TD>";
								$Salida.="</TR>";
								//$Salida.="<TR><TD colspan=\"2\">&nbsp;</TD></TR>";*/
								
					$Salida.="<TR>";
                    $Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\"><B>".$dat[$x]['numero_orden_id'].' - '.$dat[$x]['cargo_cups'].' -  ( '.$dat[$x]['cantidad'].' ) '.$dat[$x]['descripcion'].' '.$dat[$x]['especialidad_nombre']."<B>";
                    $Salida.="</TD>";
					$Salida.="  </TR>\n";
                    $Salida.="<TR>" ;
                    $Salida.="<TD  ALIGN='LEFT' WIDTH='50%' class=\"normal_10\">Valida a Partir de  : ".$this->FechaStamp($dat[$x]['fecha_activacion'])."";
                    $Salida.="</TD>";
                    $Salida.="<TD ALIGN='LEFT' WIDTH='50%' class=\"normal_10\" colspan=\"3\">Fecha Vencimiento : ".$this->FechaStamp($dat[$x]['fecha_vencimiento'])."";
                    $Salida.="</TD>";
                    $Salida.="</TR>";
                
                /*foreach($dat as $key => $dtl)
                  {
                  
                    $Salida.="<TR>";
                    $Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\"><B>".$dtl['numero_orden_id'].' - '.$dtl['cargo_cups'].' -  ( '.$dtl['cantidad'].' ) '.$dtl['descripcion'].' '.$dtl['especialidad_nombre']."<B>";
                    $Salida.="</TD>";
					$Salida.="  </TR>\n";
                    $Salida.="<TR>" ;
                    $Salida.="<TD  ALIGN='LEFT' WIDTH='50%' class=\"normal_10\">Valida a Partir de  : ".$this->FechaStamp($dtl['fecha_activacion'])."";
                    $Salida.="</TD>";
                    $Salida.="<TD ALIGN='LEFT' WIDTH='50%' class=\"normal_10\" colspan=\"3\">Fecha Vencimiento : ".$this->FechaStamp($dtl['fecha_vencimiento'])."";
                    $Salida.="</TD>";
                    $Salida.="</TR>";
                    
                  }*/
								if(!empty($dat[$x][obsapoyo]))
								{
										$Salida.="<TR>";
										$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Observaci�n: ".$dat[$x][obsapoyo]."";
										$Salida.="</TD>";
										$Salida.="</TR>";
								}
								if(!empty($dat[$x][obsinter]))
								{
										$Salida.="<TR>";
										$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Observaci�n: ".$dat[$x][obsinter]."";
										$Salida.="</TD>";
										$Salida.="</TR>";
								}
								if(!empty($dat[$x][obsnoqx]))
								{
										$Salida.="<TR>";
										$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Observaci�n: ".$dat[$x][obsnoqx]."";
										$Salida.="</TD>";
										$Salida.="</TR>";
								}

							/*$Salida.="<TR>";
								$Salida.="<TD  ALIGN='LEFT' WIDTH='50%' class=\"normal_10\">Valida a Partir de  : ".$this->FechaStamp($dat[$x][fecha_activacion])."";
								$Salida.="</TD>";
								$Salida.="<TD ALIGN='LEFT' WIDTH='50%' class=\"normal_10\" colspan=\"3\">Fecha Vencimiento : ".$this->FechaStamp($dat[$x][fecha_vencimiento])."";
								$Salida.="</TD>";
								$Salida.="</TR>";*/

								if(!empty($dat[$x][requisitos]))
								{
										$Salida.="<BR>";
										$Salida.="<TR>";
										$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10N\">RECOMENDACIONES : ".$dat[$x][requisitos]."";
										$Salida.="</TD>";
										$Salida.="</TR>";
								}
								$x++;
								/*while($dat[$i][cargo_cups]==$dat[$x][cargo_cups])
								{  $x++;}*/
            }
						$i=$x;
        }
       	$Salida.="<TR><TD colspan=\"4\">&nbsp;</TD></TR>";
        //verifica si el proveedor es interno
        if(!empty($dat[0][desdpto]))
        {
						$Salida.="<TR>";
						$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%'  class=\"normal_10N\">PRESTADOR : ".$dat[0][desdpto].' - '.$datos[0][razon_social]."</B>";
						$Salida.="</TD>";
						$Salida.="</TR>";
						$Salida.="<TR>";
						$ubicacion=$datos[0][direccion];
						if(!empty($dat[0][ubidpto]))
						{  $ubicacion=$dat[0][ubidpto];  }
						$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Direcci�n : ".$ubicacion."";
						$Salida.="</TD>";
						$Salida.="</TR>";
						$Salida.="<TR>";
						$tel=$datos[0][telefonos];
						if(!empty($dat[0][teldpto]))
						{  $tel=$dat[0][teldpto];  }
						$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Telefonos : ".$tel."";
						$Salida.="</TD>";
						$Salida.="</TR>";
        }
        elseif(!empty($dat[0][nompro]))
        {
						$Salida.="<TR>";
						$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">".'PRESTADOR : '.$dat[0][nompro]."";
						$Salida.="</TD>";
						$Salida.="</TR>";
						$Salida.="<TR>";
						$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">".'Direcci�n : '.$dat[0][dirpro]."";
						$Salida.="</TD>";
						$Salida.="</TR>";
						$Salida.="<TR>";
						$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">".'Telefonos : '.$dat[0][telpro]."";
						$Salida.="</TD>";
						$Salida.="</TR>";
        }
        if($dat[0][sw_estado]==7)
        {
						$Salida.="<TR><TD colspan=\"4\">&nbsp;</TD></TR>";
						$Salida.="<TR>";
						$Salida.="<TD colspan=\"4\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10N\">".'NOTA: '.$datos[0][nombre_tercero].' por favor hacer Tramite de la Transcripci�n a '.$datos[0][razon_social]."";
						$Salida.="</TD>";
						$Salida.="</TR>";
        }
				$Salida.="<TR><TD colspan=\"4\">&nbsp;</TD></TR>";
        //$reporte->SaltoDeLinea();
				$cargo_liq=array();
				$d=0;
				while($d<sizeof($dat))
				{
						$cargo_liq[]=array('tarifario_id'=>$dat[$d]['tarifario_id'],'cargo'=>$dat[$d]['cargo'],'cantidad'=>1,'autorizacion_int'=>$dat[$d]['autorizacion_int'],'autorizacion_ext'=>$dat[$d]['autorizacion_ext']);
						$d++;
				}
				$cargo_fact=array();
				$cargo_fact=LiquidarCargosCuentaVirtual($cargo_liq,'','','',$datos[0][plan_id] ,$datos[0][tipo_afiliado_id] ,$datos[0][rango] ,$datos[0][semanas_cotizacion],$datos[0][servicio]);
				$copago=$cargo_fact[valor_cuota_paciente];
				$moderadora=$cargo_fact[valor_cuota_moderadora];
				$total=$cargo_fact[valor_total_paciente];
				$nocub=$cargo_fact[valor_no_cubierto];

				if($copago > 0)
				{
						$Salida.="<TR>";
						$Salida.="<TD  ALIGN='LEFT' WIDTH='25%' class=\"normal_10\">".$datos[0][nombre_copago]."";
						$Salida.="</TD>";
						$Salida.="<TD  ALIGN='LEFT' WIDTH='80%' class=\"normal_10\">$ ".$copago."";
						$Salida.="</TD>";
						$Salida.="</TR>";
				}
				if($moderadora > 0)
				{
						$Salida.="<TR>";
						$Salida.="<TD  ALIGN='LEFT' class=\"normal_10\">".$datos[0][nombre_cuota_moderadora]."";
						$Salida.="</TD>";
						$Salida.="<TD  ALIGN='LEFT' class=\"normal_10\">$ ".$moderadora."";
						$Salida.="</TD>";
						$Salida.="</TR>";
				}
				if($nocub > 0)
				{
						$Salida.="<TR>";
						$Salida.="<TD ALIGN='LEFT' class=\"normal_10\">'Valor no Cubierto'";
						$Salida.="</TD>";
						$Salida.="<TD ALIGN='LEFT' WIDTH='40%' class=\"normal_10\">$ ".$nocub."";
						$Salida.="</TD>";
						$Salida.="</TR>";
				}
				if($total > 0)
				{
						$Salida.="<TR>";
						$Salida.="<TD  ALIGN='LEFT' class=\"normal_10N\">TOTAL A PAGAR";
						$Salida.="</TD>";
						$Salida.="<TD  ALIGN='LEFT' WIDTH='40%' class=\"normal_10N\">$ ".$total."";
						$Salida.="</TD>";
						$Salida.="</TR>";
				}
				//$Salida.="<TR><TD colspan=\"2\">&nbsp;</TD></TR>";
				$Salida.="</TABLE>";
				return $Salida;
    }


    function Profesional($evolucion)
	{
		list($dbconn) = GetDBconn();
		$query = "select c.tipo_id_tercero, c.tercero_id, c.nombre_tercero, f.especialidad, g.descripcion, h.tipo_id_tercero,
						h.tarjeta_profesional,h.firma
						from hc_evoluciones as a, profesionales_usuarios as b, terceros as c,
						profesionales_especialidades as f, especialidades as g, profesionales h
						where a.evolucion_id=".$evolucion." and a.usuario_id=b.usuario_id
						and b.tipo_tercero_id=c.tipo_id_tercero and b.tercero_id=c.tercero_id
						and f.tipo_id_tercero=c.tipo_id_tercero and f.tercero_id=c.tercero_id
						and f.especialidad=g.especialidad
						and h.tipo_id_tercero = c.tipo_id_tercero
						and h.tercero_id = c.tercero_id";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
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

    function BuscarCama($orden)
    {
            list($dbconn) = GetDBconn();
            $query = "select cama,departamento
                      from hc_os_solicitudes_manuales_datos_adicionales where orden_servicio_id=$orden";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

						if(!$resulta->EOF)
						{  $var=$resulta->GetRowAssoc($ToUpper = false);  }
						$resulta->Close();
            return $var;
    }

    function Historia($tipo,$id)
    {
					list($dbconn) = GetDBconn();
					$query = "select historia_prefijo  as prefijo,
										 historia_numero as numero
										from historias_clinicas
										where tipo_id_paciente='$tipo' and paciente_id='$id'";
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}

					if(!$resulta->EOF)
					{  $var=$resulta->GetRowAssoc($ToUpper = false);  }
					$resulta->Close();
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

