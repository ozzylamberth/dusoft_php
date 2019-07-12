<?php

/**
 * $Id: medicamentonoposindividualformulamedica.report.php,v 1.1.1.1 2009/09/11 20:36:19 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de formulamedica para impresora pos
 */

class formulamedica_report extends pos_reports_class
{
    //constructor por default
    function formulamedica_report()
    {
        $this->pos_reports_class();
        return true;
    }

    function CrearReporte()
    {
				IncludeLib("tarifario");
        $reporte=&$this->driver; //obtener el driver
        $datos=&$this->datos; //obtener los datos enviados al reporte.

				if(($datos[0][item]=='POS') OR ($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='1'))
				{
						$reporte->PrintFTexto($datos[0][razon_social],true,$align='center',false,true);
						$reporte->SaltoDeLinea();
						$reporte->PrintFTexto($datos[0][tipo_id_tercero].' '.$datos[0][id],false,'center',false,false);
						$reporte->PrintFTexto($datos[0][direccion].' '.$datos[0][municipio].' '.$datos[0][departamento],false,'center',false,false);
						$reporte->SaltoDeLinea();
						$reporte->PrintFTexto('FORMULA MEDICA',true,$align='center',false,true);
						$reporte->SaltoDeLinea();
						$reporte->PrintFTexto('Fecha      : '.date('d/m/Y h:m'),false,'left',false,false);
						$reporte->PrintFTexto('Atencion   : '.$datos[0][evolucion_id],false,'left',false,false);
						$reporte->SaltoDeLinea();

						$reporte->PrintFTexto('Identifi   : '.$datos[0][tipo_id].' '.$datos[0][paciente_id],false,'left',false,false);
						$reporte->PrintFTexto('Paciente   : '.$datos[0][paciente],false,'left',false,false);
						$reporte->PrintFTexto('Cliente    : '.$datos[0][plan_descripcion],false,'left',false,false);
						$reporte->PrintFTexto('Plan       : '.$datos[0][cliente],false,'left',false,false);
						$reporte->PrintFTexto('Tipo Afi   : '.$datos[0][tipo_afiliado_nombre].'     Rango: '.$datos[0][rango],false,'left',false,false);

					// $cadena = substr($cadena, 0,31)

						$reporte->SaltoDeLinea();
						if($datos[0][item]=='POS')
						{
							$subtitulo = 'MEDICAMENTOS POS FORMULADOS.';
						}

						if($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='1')
						{
							$subtitulo = 'MEDICAMENTOS NO POS SOLICITADOS A PETICION DEL PACIENTE.';
						}
						elseif($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='0')
						{
							$subtitulo = 'MEDICAMENTOS NO POS JUSTIFICADOS.';
						}
						$reporte->PrintFTexto($subtitulo.$datos[0][orden_servicio_id],true,'center',false,false);
						$reporte->SaltoDeLinea();
				}

				for($i=0; $i<sizeof($datos);$i++)
				{

            if($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='0')
						{
							$reporte->PrintFTexto($datos[0][razon_social],true,$align='center',false,true);
							$reporte->SaltoDeLinea();
							$reporte->PrintFTexto($datos[0][tipo_id_tercero].' '.$datos[0][id],false,'center',false,false);
							$reporte->PrintFTexto($datos[0][direccion].' '.$datos[0][municipio].' '.$datos[0][departamento],false,'center',false,false);
							$reporte->SaltoDeLinea();
							$reporte->PrintFTexto('FORMULA MEDICA',true,$align='center',false,true);
							$reporte->SaltoDeLinea();
							$reporte->PrintFTexto('Fecha      : '.date('d/m/Y h:m'),false,'left',false,false);
							$reporte->PrintFTexto('Atencion   : '.$datos[0][evolucion_id],false,'left',false,false);
							$reporte->SaltoDeLinea();

							$reporte->PrintFTexto('Identifi   : '.$datos[0][tipo_id].' '.$datos[0][paciente_id],false,'left',false,false);
							$reporte->PrintFTexto('Paciente   : '.$datos[0][paciente],false,'left',false,false);
							$reporte->PrintFTexto('Cliente    : '.$datos[0][plan_descripcion],false,'left',false,false);
							$reporte->PrintFTexto('Plan       : '.$datos[0][cliente],false,'left',false,false);
							$reporte->PrintFTexto('Tipo Afi   : '.$datos[0][tipo_afiliado_nombre].'     Rango: '.$datos[0][rango],false,'left',false,false);

						// $cadena = substr($cadena, 0,31)

							$reporte->SaltoDeLinea();
							if($datos[0][item]=='POS')
							{
								$subtitulo = 'MEDICAMENTOS POS FORMULADOS.';
							}

							if($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='1')
							{
								$subtitulo = 'MEDICAMENTOS NO POS SOLICITADOS A PETICION DEL PACIENTE.';
							}
							elseif($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='0')
							{
								$subtitulo = 'MEDICAMENTOS NO POS JUSTIFICADOS.';
							}
							$reporte->PrintFTexto($subtitulo.$datos[0][orden_servicio_id],true,'center',false,false);
							$reporte->SaltoDeLinea();
            }

					$reporte->PrintFTexto(($i+1).'. '.$datos[$i][producto],true,'left',false,false);
					$reporte->PrintFTexto('Via de Administracion: '.$datos[$i][via],false,'left',false,false);

					$e=$datos[$i][dosis]/floor($datos[$i][dosis]);
					if($e==1)
					{
						$reporte->PrintFTexto('Dosis : '.floor($datos[$i][dosis]).' '.$datos[$i][unidad_dosificacion],false,'left',false,false);
					}
					else
					{
					  $reporte->PrintFTexto('Dosis : '.$datos[$i][dosis].' '.$datos[$i][unidad_dosificacion],false,'left',false,false);
					}

					//pintar formula para opcion 1 //caso ok
								if($datos[$i][tipo_opcion_posologia_id]== 1)
								{
									$reporte->PrintFTexto('cada '.$datos[$i][posologia][0][periocidad_id].' '.$datos[$i][posologia][0][tiempo],false,'left',false,false);
								}

          //pintar formula para opcion 2 //caso ok
								if($datos[$i][tipo_opcion_posologia_id]== 2)
								{
                  $reporte->PrintFTexto(' '.$datos[$i][posologia][0][descripcion],false,'left',false,false);
								}

          //pintar formula para opcion 3  //caso ok
								if($datos[$i][tipo_opcion_posologia_id]== 3)
								{
										$momento = '';
										if($datos[$i][posologia][0][sw_estado_momento]== '1')
										{
											$momento = 'antes de ';
										}
										else
										{
											if($datos[$i][posologia][0][sw_estado_momento]== '2')
											{
												$momento = 'durante ';
											}
											else
											{
												if($datos[$i][posologia][0][sw_estado_momento]== '3')
													{
														$momento = 'despues de ';
													}
											}
										}
										$Cen = $Alm = $Des= '';
										$cont= 0;
										$conector = '  ';
										$conector1 = '  ';
										if($datos[$i][posologia][0][sw_estado_desayuno]== '1')
										{
											$Des = $momento.'el Desayuno';
											$cont++;
										}
										if($datos[$i][posologia][0][sw_estado_almuerzo]== '1')
										{
											$Alm = $momento.'el Almuerzo';
											$cont++;
										}
										if($datos[$i][posologia][0][sw_estado_cena]== '1')
										{
											$Cen = $momento.'la Cena';
											$cont++;
										}
										if ($cont== 2)
										{
											$conector = ' y ';
											$conector1 = '  ';
										}
										if ($cont== 1)
										{
											$conector = '  ';
											$conector1 = '  ';
										}
										if ($cont== 3)
										{
											$conector = ' , ';
											$conector1 = ' y ';
										}
										$reporte->PrintFTexto($Des.$conector.$Alm.$conector1.$Cen,false,'left',false,false);
								}

            //pintar formula para opcion 4 ok
								if($datos[$i][tipo_opcion_posologia_id]== 4)
								{
									$conector = '  ';
									$frecuencia='';
									$j=0;
									foreach ($datos[$i][posologia] as $k => $v)
									{
										if ($j+1 ==sizeof($datos[$i][posologia]))
										{
											$conector = '  ';
										}
										else
										{
												if ($j+2 ==sizeof($datos[$i][posologia]))
													{
														$conector = ' y ';
													}
												else
													{
														$conector = ' - ';
													}
										}
										$frecuencia = $frecuencia.$k.$conector;
										$j++;
									}
									$reporte->PrintFTexto('a la(s): '.$frecuencia,false,'left',false,false);
								}

            //pintar formula para opcion 5 //ok
								if($datos[$i][tipo_opcion_posologia_id]== 5)
								{
                  $reporte->PrintFTexto(' '.$datos[$i][posologia][0][frecuencia_suministro],false,'left',false,false);
								}
            //pintar cantidad
                $e=$datos[$i][cantidad]/floor($datos[$i][cantidad]);
								if ($datos[$i][contenido_unidad_venta])
								{
									if($e==1)
									{
									  $reporte->PrintFTexto('Cantidad : '.floor($datos[$i][cantidad]).' '.$datos[$i][descripcion].' por '.$datos[$i][contenido_unidad_venta],false,'left',false,false);

									}
									else
									{
									  $reporte->PrintFTexto('Cantidad : '.$datos[$i][cantidad].' '.$datos[$i][descripcion].' por '.$datos[$i][contenido_unidad_venta],false,'left',false,false);
									}
								}
								else
								{
									if($e==1)
									{
									  $reporte->PrintFTexto('Cantidad : '.floor($datos[$i][cantidad]).' '.$datos[$i][descripcion],false,'left',false,false);
									}
									else
									{
										$reporte->PrintFTexto('Cantidad : '.$datos[$i][cantidad].' '.$datos[$i][descripcion],false,'left',false,false);
									}
								}
					$reporte->PrintFTexto('Observacion : '.$datos[$i][observacion],false,'left',false,false);
					$reporte->SaltoDeLinea();

					if($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='0')
						{
								$reporte->SaltoDeLinea();
								$reporte->PrintFTexto('MEDICAMENTO(S) FORMULADOS POR:',true,$align='center',false,false);
								$reporte->PrintFTexto('Tipo Prof. : '.$datos[0][tipo_profesional],false,'center',false,false);
								$reporte->PrintFTexto('Profesional: '.$datos[0][nombre_tercero],false,'center',false,false);
								$reporte->PrintEnd();
						}

				}
				if(($datos[0][item]=='POS') OR ($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='1'))
				{
						$reporte->SaltoDeLinea();
						$reporte->PrintFTexto('MEDICAMENTO(S) FORMULADOS POR:',true,$align='center',false,false);
						$reporte->PrintFTexto('Tipo Prof. : '.$datos[0][tipo_profesional],false,'center',false,false);
						$reporte->PrintFTexto('Profesional: '.$datos[0][nombre_tercero],false,'center',false,false);
						$reporte->PrintEnd();
				}

        $reporte->OpenCajaMonedera();
        $reporte->PrintCutPaper();
        return true;
    }
}
?>
