<?php
/**
* Reporte Evolución Odontologica.
*
* @author Carlos A. Henao <carlosarturohenao@gmail.com>
* @version 1.0
* @package SIIS
* $Id: evolucionodontologica.inc.php,v 1.3 2006/09/07 16:38:23 carlos Exp $
*/
//Reporte de prueba formato HTML
//
//Un reporte es una clase con el nombre de reporte y el sufijo '_report'

    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR

    function GetMembrete()
    {
        $Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
                                                                'subtitulo'=>'',
                                                                'logo'=>'logocliente.png',
                                                                'align'=>'left'));
        return $Membrete;
    }

//FUNCION CrearReporte()
//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
function CrearReporteEvolucionOdontologica($ingreso, $evolucion, $cuenta, $usuario_id, $plan, $servicio, $tipoidpaciente, $paciente)
{
        IncludeLib("funciones_facturacion");
        IncludeLib("tarifario_cargos");
        $Dir="cache/evolucionodontologica".$cuenta.".pdf";
        require("classes/fpdf/html_class.php");
        define('FPDF_FONTPATH','font/');
        $pdf=new PDF();
        $pdf->AddPage();
				$pdf->SetFont('Arial','',12);

        //print_r ($this->datos);
        //exit;
        $infoEvolucion = Get_Evoluciones_Odontologicas($tipoidpaciente,$paciente);
        $Cuentas = BuscarCuentas($cuenta);
        $usuario = NombreUs($usuario_id);
        //$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar', 'inscripcion'.$pfj=>$programas));
        //$Salida.="<form name=\"evoluciones_odontologicas$pfj\" action=\"$accion\" method=\"post\">";

        //$Salida.="<br><br><center>";
        //$Salida.="<label><font size='14' face='arial'>EVOLUCION ODONTOLOGICA</font></label>";
        //$Salida.="</center><br><br>";
        $Salida.="<table border='0' width ='830' align='center'>";
        $Salida.="<tr>";
        $Salida.="<td width='760' colspan='9' align='CENTER'><font size='12' face='arial'><B>EVOLUCION ODONTOLOGICA</B></font></td>";
        $Salida.="</tr>";
        $Salida.="<tr>";
        $Salida.="<td width='760' colspan='9' align='CENTER'><font size='10' face='arial'>EVOLUCION HISTORIA CLINICA ODONTOLOGICA</font></td>";
        $Salida.="</tr>";
        $Salida.="<tr align='center'>";
        //$Salida.="<td width='60' align='center'>FECHA</td>";
        //$Salida.="<tr>";
        //$Salida.="<td width='75' align='center'><b><font size='6' face='arial'>$fecha</font></b></td>";
        //$Salida.="</tr>";
        $Salida.="<td width='760' colspan='8' align='CENTER'>DATOS EVOLUCION</td>";
        $Salida.="</tr>";
				$Salida.="<tr>";
				$Salida.="<td width='760' align='CENTER'>------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>";
				$Salida.="</tr>";
        foreach($infoEvolucion as $k => $v)
        {
            $Salida.="<tr>";
            $Salida.="<td width='60' align='center'><b><font size='7' face='arial'>FECHA</font></b></td>";
            $Salida.="<td width='300'>";
            $Salida.="<table border='0' width='250'>";

            $Salida.="<tr align=\"center\">";
            $Salida.="<td width='65'><b><font size='7' face='arial'>EVOLUCION</font></b></td>";
            $Salida.="<td width='40'><b><font size='7' face='arial'>DIENTE</font></b></td>";
            $Salida.="<td width='60'><b><font size='7' face='arial'>SUPERF.</font></b></td>";
            $Salida.="<td width='240'><b><font size='7' face='arial'>DESCRIPCION DEL PROCEDIMIENTO EJECUT.</font></b></td>";
            $Salida.="<td width='78'><b><font size='7' face='arial'>AUTORIZACION</font></b></td>";
            $Salida.="<td width='75'><b><font size='7' face='arial'>FACTURA</font></b></td>";
            $Salida.="<td width='50'><b><font size='7' face='arial'>CM/CP</font></b></td>";
            $Salida.="<td width='80'><b><font size='7' face='arial'>ODONTOLOGO</font></b></td>";
            $Salida.="</tr>";
						$Salida.="<tr>";
						$Salida.="<td width='760' align='CENTER'>----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>";
						$Salida.="</tr>";


            for($j=0; $j<sizeof($v[evo]); $j++)
            {
								//$usuario1 = $this->NombreUs($v[evo][$j][11]);
								
								list($fecha,$hora) = explode(" ",$v[evo][$j][8]);
                /*OBTENER EQUIVALENCIAS*/
                $validados=ValdiarEquivalencias($plan,$v[evo][$j][5]);
                /*FIN OBTENER EQUIVALENCIAS*/

                if(!empty($validados))
                {
                    $cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>1,'autorizacion_int'=>'','autorizacion_ext'=>'');
    
                    /*LIQUIDAR CUENTA*/
                    $resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$plan,$Cuentas[0],$Cuentas[1],$Cuentas[2],$servicio,$tipoidpaciente,$paciente,'','');
                    /*FIN LIQUIDAR CUENTA*/
    
                    $Salida.="<tr>";
                    $Salida.="<td width='60' align='center'>".$fecha."</td>";
                    $Salida.="<td width='65' align='left'><font size='7' face='arial'>".$v[evo][$j][9]."</font></td>";
                    $Salida.="<td width='20' align='left'><font size='7' face='arial'>".$v[evo][$j][0]."</font></td>";
                    $Salida.="<td width='120' align='center'><font size='7' face='arial'>".$v[evo][$j][2]."</font></td>";
                    //$Salida.="<td width='210' align='center'><font size='7' face='arial'>".$v[evo][$j][6]."-".$v[evo][$j][3]."-(".trim($v[evo][$j][4]).")";
                    $Salida.="<td width='200' align='center'><font size='7' face='arial'>".trim($v[evo][$j][4])."";
                    $Salida.="</font></td>";
                    $Salida.="<td width='75' align='center'>&nbsp;</td>";
                    $Salida.="<td width='75' align='center'><font size='7' face='arial'>".$cuenta."</font></td>";
                    $Salida.="<td width='50' align='center'><font size='7' face='arial'>".$resul['valor_total_paciente']."</font></td>";
                    $Salida.="<td width='100' align='center'><font size='7' face='arial'>".substr($usuario,0,15)."</font></td>";
                    $Salida.="</tr>";
                }
                else
                {
                    $Salida.="<tr>";
                    $Salida.="<td width='60' align='center'>&nbsp;</td>";
                    $Salida.="<td width='65' align='left'><font size='7' face='arial'>".$v[evo][$j][7]."</font></td>";
                    $Salida.="<td width='20' align='left'><font size='7' face='arial'>".$v[evo][$j][0]."</font></td>";
                    $Salida.="<td width='120' align='center'><font size='7' face='arial'>".$v[evo][$j][2]."</font></td>";
                    //$Salida.="<td width='210' align='center'><font size='7' face='arial'>".$v[evo][$j][6].'--'.$v[evo][$j][3]."-(".trim($v[evo][$j][4]).")";
                    $Salida.="<td width='200' align='center'><font size='7' face='arial'>".trim($v[evo][$j][4])."";
                    $Salida.="</font></td>";
                    $Salida.="<td width='75' align='center'>&nbsp;</td>";
                    $Salida.="<td width='75' align='center'><font size='7' face='arial'>".$cuenta."</font></td>";
                    $Salida.="<td width='50' align='center'><font size='7' face='arial'>Sin Equivalencia.</font></td>";
                    $Salida.="<td width='100' align='center'><font size='7' face='arial'>".substr($usuario,0,15)."</font></td>";
                    $Salida.="</tr>";
                }
            }
            $Salida.="</table>";
            $Salida.="</td>";
            $Salida.="</tr>";
        }
        $Salida.="</table>";
				//ADICIÓN DE CARGOS PRESUPUESTADOS
        $presup=GetBuscarPresupuestosOdontograma($tipoidpaciente, $paciente);
				if($presup<>NULL)
				{
							$Salida.="<table border=\"0\" class=\"hc_table_list\" width=\"100%\">";

							$Salida.="<tr>";
							$Salida.="<td width='760' colspan='9' align='CENTER'>----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>";
							$Salida.="</tr>";
							$Salida.="<tr class=\"hc_table_list_title\" align=\"center\">";
							$Salida.="<td width=\"70%\">EVOLUCION</td>";
							$Salida.="<td width=\"40%\">FECHA</td>";
							$Salida.="<td width=\"300%\">DESCRIPCION DEL PROCEDIMIENTO EJECUTADO</td>";
							$Salida.="<td width=\"80%\">AUTORIZACION</td>";
							$Salida.="<td width=\"65%\">FACTURA</td>";
							$Salida.="<td width=\"40%\">CAN.</td>";
							$Salida.="<td width=\"50%\">CM / CP</td>";
							$Salida.="<td width=\"40%\">DX</td>";                    
							$Salida.="<td width=\"50%\">ODONTOLOGO</td>";
							$Salida.="</tr>";
							$Salida.="<tr>";
							$Salida.="<td width='760' align='CENTER'>----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>";
							$Salida.="</tr>";

							for($i=0;$i<sizeof($presup);$i++)
							{
									if( $i % 2)
									{
												$estilo='modulo_list_claro';
									}
									else
									{
												$estilo='modulo_list_oscuro';
									}
									
									/*OBTENER EQUIVALENCIAS*/
									$validados=ValdiarEquivalencias($plan,$presup[$i]['cargo']);
									/*FIN OBTENER EQUIVALENCIAS*/

									if(!empty($validados))
									{
												$dx_ppto = Select_DX_Presupuestos($presup[$i]['cargo'], $presup[$i]['hc_odontograma_primera_vez_id']);
												foreach($dx_ppto as $k => $dx_ppto)
												{
														$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$dx_ppto['cantidad_realizada'],'autorizacion_int'=>'','autorizacion_ext'=>'');
		
														/*LIQUIDAR CUENTA*/
														$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$plan,$Cuentas[0],$Cuentas[1],$Cuentas[2],$servicio,$tipoidpaciente,$paciente,'','');
														/*FIN LIQUIDAR CUENTA*/
		
														$USppto = NombreUs($dx_ppto['usuarioid_ppto']);
														
														$Salida.="<tr class=\"$estilo\">";
														//$Salida.="<td align=\"center\">".$presup[$i]['evolucion_id']."";
														$Salida.="<td width=\"50%\" align=\"center\">".$dx_ppto['evolucion_ppto']."";
														$Salida.="</td>";
														
														//list($fecha2,$hora) = explode(" ",$presup[$i]['fecha']);
														list($fecha2,$hora) = explode(" ",$dx_ppto['fechareg_ppto']);
				
														$Salida.="<td width=\"55%\" align=\"center\">".$fecha2."";
														$Salida.="</td>";
														$descripcion=substr($presup[$i]['descripcion'],0,50);
														$Salida.="<td width=\"300%\" align=\"justify\">".$descripcion."";
														$Salida.="</td>";
														$Salida.="<td width=\"85%\" align=\"center\">&nbsp;";
														$Salida.="</td>";
														$Salida.="<td  width=\"65%\" align=\"center\">".$cuenta."";
														$Salida.="</td>";
														
														$Salida.="<td width=\"40%\" align=\"center\">".$dx_ppto['cantidad_realizada']."";
														$Salida.="</td>";
														
														$Salida.="<td width=\"50%\" align=\"center\">".$resul['valor_total_paciente']."";
														$Salida.="</td>";
														
														//$Salida.="<td align=\"center\">".$dx_ppto."";
														$Salida.="<td  width=\"33%\" align=\"center\">".$dx_ppto['diagnostico_id']."";
														$Salida.="</td>";
														
														$Salida.="<td width=\"50%\" align=\"center\">".substr($USppto,0,15).".";
														$Salida.="</td>";
														$Salida.="</tr>";
										}
									}
									else
									{
												$dx_ppto = Select_DX_Presupuestos($presup[$i]['cargo'], $presup[$i]['hc_odontograma_primera_vez_id']);
												foreach($dx_ppto as $k => $dx_ppto)
												{
														$USppto = NombreUs($dx_ppto['usuarioid_ppto']);
														
														$Salida.="<tr class=\"$estilo\">";
														//$Salida.="<td align=\"center\">".$presup[$i]['evolucion_id']."";
														$Salida.="<td width=\"50%\" align=\"center\">".$dx_ppto['evolucion_ppto']."";
														$Salida.="</td>";
														
														//list($fecha2,$hora) = explode(" ",$presup[$i]['fecha']);
														list($fecha2,$hora) = explode(" ",$dx_ppto['fechareg_ppto']);
														$Salida.="<td width=\"55%\" align=\"center\">".$fecha2."";
														$Salida.="</td>";
														$descripcion=substr($presup[$i]['descripcion'],0,50);
														$Salida.="<td width=\"300%\" align=\"justify\">".$descripcion."";
														$Salida.="</td>";
														$Salida.="<td width=\"85%\" align=\"center\">&nbsp;";
														$Salida.="</td>";
														$Salida.="<td  width=\"65%\" align=\"center\">".$cuenta."";
														$Salida.="</td>";
		
														$Salida.="<td width=\"40%\" align=\"center\">".$Cantidad."";
														$Salida.="</td>";
																								
														$Salida.="<td width=\"50%\" align=\"center\">Sin Equivalencia.";
														$Salida.="</td>";
														
														
														//$Salida.="<td align=\"center\">".$dx_ppto."";
														$Salida.="<td width=\"40%\" align=\"center\">".$dx_ppto['cantidad_realizada']."";
														$Salida.="</td>";
														
														$Salida.="<td width=\"50%\" align=\"center\">".substr($USppto,0,15).".";
														$Salida.="</td>";
														$Salida.="</tr>";
												}
									}
							}
							$Salida.="</table><br>";
				}
				//FIN ADICIÓN DE CARGOS PRESUPUESTADOS
        //$Salida.="</form>";
        $pdf->WriteHTML($Salida);
        $pdf->SetLineWidth(0.2);
        $pdf->RoundedRect(7, 7, 196, 284, 3.5, '');      
        $pdf->Output($Dir,'F');
    return true;
 }


		//AQUI TODOS LOS METODOS QUE USTED QUIERA

		function GetBuscarPresupuestosOdontograma($tipoidpaciente, $paciente)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT A.hc_odontograma_primera_vez_id,
								A.evolucion_id,
								B.cargo,
								B.cantidad,
								B.estado,
								C.descripcion,
								D.fecha,
								B.cantidad_pend
						FROM hc_odontogramas_primera_vez AS A 
							LEFT JOIN hc_evoluciones AS D on (A.evolucion_id=D.evolucion_id),
								hc_odontogramas_primera_vez_presupuesto AS B,
								cups AS c
						WHERE A.tipo_id_paciente='".$tipoidpaciente."'
						AND A.paciente_id='".$paciente."'
						AND A.sw_activo='1'
						AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
									AND (B.estado='0' OR (B.estado='1' AND B.cantidad_pend > 0))
						AND B.cargo=C.cargo;";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while(!$resulta->EOF)
			{
				$var[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
			return $var;
		}

		function Select_DX_Presupuestos($cargo, $odontograma)
		{
				GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$query="SELECT diagnostico_id, evolucion_id AS evolucion_ppto, 
						fecha_registro AS fechareg_ppto, usuario_id AS usuarioid_ppto,
						cantidad_realizada
			FROM hc_odontogramas_tratamientos_evolucion_presupuesto
			WHERE cargo = $cargo
			AND hc_odontograma_primera_vez_id = $odontograma
						AND sw_principal='1';";
						$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
						$resulta = $dbconn->Execute($query);
						$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while($data = $resulta->FetchRow())
						{
							$dx_ppto[] = $data;
						}
						return $dx_ppto;
		}

		function Get_Evoluciones_Odontologicas($tipoidpaciente,$paciente)
		{
				$pfj=$this->frmPrefijo;
				list($dbconn) = GetDBconn();
				$query="SELECT A.hc_odontograma_primera_vez_id, A.evolucion_id, B.fecha
				FROM hc_odontogramas_primera_vez AS A, hc_evoluciones AS B
				WHERE A.tipo_id_paciente='".$tipoidpaciente."'
				AND A.paciente_id='".$paciente."'
				AND A.sw_activo='1'
				AND A.evolucion_id = B.evolucion_id
				ORDER BY A.hc_odontograma_primera_vez_id ASC;";
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while($data = $resulta->FetchRow())
				{
						$odonto[] = $data;
				}

				$query="SELECT A.hc_odontograma_tratamiento_id
				FROM hc_odontogramas_tratamientos AS A
				WHERE A.tipo_id_paciente='".$tipoidpaciente."'
				AND A.paciente_id='".$paciente."'
				AND A.sw_activo='1';";
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$odontotra=$resulta->fields[0];
	
				if(!empty($odonto))
				{
						for($i=0; $i<sizeof($odonto); $i++)
						{
							$query="(SELECT A.hc_tipo_ubicacion_diente_id,
							A.hc_odontograma_primera_vez_id AS odontogramas,
													B.descripcion AS des1,
							C.descripcion AS des2,
							D.descripcion AS des3,
							E.cargo,
							F.descripcion AS des4,
													H.diagnostico_id,
													H.fecha_registro,
													H.evolucion_id,
													I.diagnostico_nombre,
													H.usuario_id,
													1 AS control
							FROM hc_odontogramas_primera_vez_detalle AS A,
							hc_tipos_cuadrantes_dientes AS B,
							hc_tipos_problemas_dientes AS C,
							hc_tipos_productos_dientes AS D,
							hc_tipos_problemas_soluciones_dientes AS E,
							cups AS F, hc_odontogramas_tratamientos_evolucion_primera_vez AS H,
													diagnosticos AS I
							WHERE A.hc_odontograma_primera_vez_id=".$odonto[$i][0]."
							AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
							AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
							AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
							AND A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id
							AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id
													AND A.hc_odontograma_primera_vez_detalle_id=H.hc_odontograma_primera_vez_detalle_id
													AND H.diagnostico_id=I.diagnostico_id
													AND H.sw_principal = 1
							AND (A.estado='0'
													)
							AND E.cargo=F.cargo
							ORDER BY A.hc_tipo_ubicacion_diente_id, control)

									UNION

							(SELECT A.hc_tipo_ubicacion_diente_id,
							A.hc_odontograma_tratamiento_id AS odontogramas,
													B.descripcion AS des1, 
													C.descripcion AS des2, 
													D.descripcion AS des3, 
													E.cargo, 
													F.descripcion AS des4, 
													H.diagnostico_id, 
													H.fecha_registro, 
													H.evolucion_id, 
													I.diagnostico_nombre,
													H.usuario_id,
													2 AS control
			
							FROM hc_odontogramas_tratamientos_detalle AS A, 
													hc_tipos_cuadrantes_dientes AS B, hc_tipos_problemas_dientes AS C,
													hc_tipos_productos_dientes AS D, 
													hc_tipos_problemas_soluciones_dientes AS E, cups AS F, 
													hc_odontogramas_tratamientos_evolucion_tratamiento AS H, 
													diagnosticos AS I 
			
							WHERE A.hc_odontograma_tratamiento_id=".$odontotra."
													AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id 
													AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id 
													AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id 
													AND A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id 
													AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id 
													AND A.hc_odontograma_tratamiento_detalle_id=H.hc_odontograma_tratamiento_detalle_id 
													AND H.diagnostico_id=I.diagnostico_id
													AND H.sw_principal = 1
													AND (A.estado='0'
													OR A.estado='4')
													AND E.cargo=F.cargo 
													ORDER BY A.hc_tipo_ubicacion_diente_id, control);";
								$resulta = $dbconn->Execute($query);
								if($dbconn->ErrorNo() != 0)
								{
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
								}
								while($var = $resulta->FetchRow())
								{
										$odonto[$i][evo][] = $var;
								}
						}
				}
				return $odonto;
		}

    function BuscarCuentas($cuenta)
    {
        list($dbconn) = GetDBconn();
        $query="SELECT tipo_afiliado_id,
        rango,
        semanas_cotizadas
        FROM cuentas
        WHERE numerodecuenta=".$cuenta.";";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto[0]=$resulta->fields[0];
        $odonto[1]=$resulta->fields[1];
        $odonto[2]=$resulta->fields[2];
        return $odonto;
    }

    function NombreUs($user)
    {
        list($dbconn) = GetDBconn();
        $query="SELECT nombre
        FROM system_usuarios
        WHERE usuario_id=".$user.";";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        list($usuario) = $resulta->FetchRow();
        return $usuario;
    }

function FechaStamp($fecha)
    {
            if($fecha){
                    $fech = strtok ($fecha,"-");
                    for($l=0;$l<3;$l++)
                    {
                        $date[$l]=$fech;
                        $fech = strtok ("-");
                    }
                    return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
            }
    }

    function HoraStamp($hora)
        {
                        $hor = strtok ($hora," ");
                        for($l=0;$l<4;$l++)
                        {
                                $time[$l]=$hor;
                                $hor = strtok (":");
                        }

                        $x = explode (".",$time[3]);
                        return  $time[1].":".$time[2].":".$x[0];
        }
    //---------------------------------------
?>
