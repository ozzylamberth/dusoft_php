....<?php
/**
* Submodulo de Examenes Clinicos.
*
* Submodulo para manejar la reserva y/o cruzada de sangre.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_Examenes_HTML.php,v 1.3 2006/12/19 21:00:13 jgomez Exp $
*/

class Examenes_HTML extends Examenes
{
    //clzc - dd -ok
	  function Examenes_HTML()
	  {
	    $this->Examenes();//constructor del padre
      return true;
	  }

//clzc - dd -ok
  function SetStyle($campo)
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("label");
	}


  /**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

  function GetVersion()
  {
    $informacion=array(
    'version'=>'1',
    'subversion'=>'0',
    'revision'=>'0',
    'fecha'=>'01/27/2005',
    'autor'=>'CLAUDIA LILIANA ZUÑIGA CAÑON',
    'descripcion_cambio' => '',
    'requiere_sql' => false,
    'requerimientos_adicionales' => '',
    'version_kernel' => '1.0'
    );
    return $informacion;
  }
////////////////
  
  
  
  
  
//clzc-dd-ok
	function frmForma()
	{
		$pfj=$this->frmPrefijo;
		if(empty($this->titulo))
		{
			$this->salida= ThemeAbrirTablaSubModulo('SOLICITUD DE EXAMENES');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

	//llama a la funcion que consulta todos los titulos de los examenes
		$vector=$this->ConsultaExamen();
        if($vector)
		{
			$this->salida .= "<form name=\"formades\" action=\"$action\" method=\"post\">";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"2\">EXAMENES</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td>CODIGO</td>";
			$this->salida.="  <td>EXAMEN</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vector);$i++)
			{
              $id=$vector[$i][lab_examen_cargo_id];
							$examen=$vector[$i][titulo_examen];
							$informacion= $vector[$i][informacion];

							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida.="  <td align=\"center\" width=\"20%\">$id</td>";
							$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'forma','id'=>$id,'examen'=>$examen, 'informacion' =>$informacion));
							$this->salida.="  <td align=\"left\" width=\"60%\"><a href='$accion'>".strtoupper($examen)."</a></td>";
							$this->salida.="</tr>";
					}
					$this->salida.="</table>";
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
 					$this->salida .= "<tr>";
					$action2=ModuloGetURL('system','Usuarios','admin','ListadoPerfiles');
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";

				}
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


function frmCrearFormaE($id,$examen,$informacion)
{
  $pfj=$this->frmPrefijo;
	$this->salida= ThemeAbrirTablaSubModulo('EXAMEN CLINICO');
 	$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
  $this->salida .="<td align='center'><font size='4'>$examen</font></td>" ;
	$this->salida.="</table><br>";

  $action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar','id'=>$id,'examen'=>$examen));
	$this->salida .= "<form name=\"formades\" action=\"$action\" method=\"post\">";



	$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
	$this->salida .= $this->SetStyle("MensajeError");
	$this->salida.="</table>";


	$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"70%\" class=\"normal_10\">";
  $this->salida .="<tr><td class='label' align = right>Fecha del Examen:  </td><td align='center'><input type='text' class='input-text' size='10' maxlength='10' name='fecha_lab' onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">".ReturnOpenCalendario('formades','fecha_lab','/')."</td>" ;
  $this->salida .="<td class='label'>Laboratorio:  </td><td align='center'><input type='text' class='input-text' name='laboratorio'></td></tr>" ;
	$this->salida.="</table><br>";


	$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"90%\">";
	$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
	$this->salida.="  <td>NOMBRE DEL EXAMEN</td>";
	$this->salida.="  <td>RESULTADO</td>";
	$this->salida.="  <td>RANGO NORMAL</td>";
	$this->salida.="</tr>";


	$vector=$this->ConsultaComponentesExamen($id);
	if($vector)
	 {
		$p = 1;
		$indmax = sizeof($vector);
		$indmin=1;
		$items = sizeof($vector);
		$this->salida.="  <input type='hidden' name = items  value='$items'>";
		for($i=0;$i<sizeof($vector);$i++)
		 {

			$spia = $vector[$i][lab_plantilla_id];
		  switch ($spia)
	     {
        case "1": {
									 $nombre=$vector[$i][nombre_examen];
									 $cod=$vector[$i][lab_examen_id];
									 $rangoMin=$vector[$i][rango_min];
									 $rangoMax=$vector[$i][rango_max];
								   $unidad=$vector[$i][unidades];
									 $sexo=$vector[$i][sexo_id];

									 if(is_null($rangoMin) || is_null($rangoMax))
										{
										 $val='';
										 if(is_null($rangoMin) || $rangoMin == '0')
											{
											 $rangoMin = 0;
											 $val="Rango :".$rangoMin."-".$rangoMax."&nbsp;".$unidad."";
											}
										}
									 else
										{
										 if(empty($sexo) || $sexo == '0')
											{
												$val="Rango :".$rangoMin."-".$rangoMax."&nbsp;".$unidad."";
											}
										 else
											{ $p=0;
												if ($sexo == $this->datosPaciente[sexo_id])
													{   if(strtoupper($sexo)=='F')
											 				 {
																$val="Mujeres : ".$rangoMin."-".$rangoMax."&nbsp;".$unidad."";
																if( $i % 2)
																	{
																		$estilo='modulo_list_claro';
																	}
																else
																	{
																		$estilo='modulo_list_oscuro';
																	}
																$this->salida.="<tr class=\"$estilo\">";
																$this->salida.="  <td align=\"center\" width=\"40%\">$nombre</td>";
																$this->salida.="  <input type='hidden' name = nom$i  value='$cod'>";
																$this->salida.="  <td align=\"center\" width=\"30%\"><input type='text' name = res$i>&nbsp;$unidad</td>";
																$this->salida.="  <td align=\"center\" width=\"30%\">$val</td>";
																$this->salida.="</tr>";
															 }
															elseif(strtoupper($sexo)=='M')
																{
																	$val="Hombres : ".$rangoMin."-".$rangoMax."&nbsp;".$unidad."";
																	if( $i % 2)
																		{
																			$estilo='modulo_list_claro';
																		}
																	else
																		{
																			$estilo='modulo_list_oscuro';
																		}
																	$this->salida.="<tr class=\"$estilo\">";
																	$this->salida.="  <td align=\"center\" width=\"40%\">$nombre</td>";
																  $this->salida.="  <input type='hidden' name = nom$i  value='$cod'>";
																	$this->salida.="  <td align=\"center\" width=\"30%\"><input type='text' name=res$i>&nbsp;$unidad</td>";
																	$this->salida.="  <td align=\"center\" width=\"30%\">$val</td>";
																	$this->salida.="</tr>";
																}
													}
											}
										}
							     if( $i % 2)
										 {
										  $estilo='modulo_list_claro';
										 }
									 else
										 {
											$estilo='modulo_list_oscuro';
										 }
									 if ($p == 1)
										 {
										  	$this->salida.="<tr class=\"$estilo\">";
											  $this->salida.="  <td align=\"center\" width=\"40%\">$nombre</td>";
												$this->salida.="  <input type='hidden' name = nom$i  value='$cod'>";
											  $this->salida.="  <td align=\"center\" width=\"30%\"><input type='text' name=res$i>&nbsp;$unidad</td>";
											  $this->salida.="  <td align=\"center\" width=\"30%\">$val</td>";
											  $this->salida.="</tr>";
										 }
										break;
									}

			  case "2": {
                   $val = '';
									 $nombre=$vector[$i][nombre_examen];
									 $cod=$vector[$i][lab_examen_id];
									 $opc = $vector[$i][opcion];
									 $cod_opc = $vector[$i][lab_examen_opcion_id];
                   if( $i % 2)
											{
												$estilo='modulo_list_claro';
											}
										else
											{
												$estilo='modulo_list_oscuro';
											}

										if ($indmin == 1)
										  {
                         $this->salida.="<tr class=\"$estilo\">";
										     $this->salida.="<td align=\"center\" width=\"40%\">$nombre</td>";
												 $this->salida.="<input type='hidden' name = nom$i  value='$cod'>";

                         $this->salida.="<td align=\"center\" width=\"30%\">";
												 $this->salida.="<select size = 1 name = res$i class =\"select\">";
		                     $this->salida.="<option value = -1>--Seleccione-- </option>";
												 $this->salida.="<option value = $cod_opc>$opc</option>";
												 $indmin++;
										  }
                    else
                      {
                         if ($indmin == $indmax)
                             {
                               $this->salida.="<option value = $cod_opc>$opc</option>";
															 $this->salida.="</select>";
                               $this->salida.="</td>";
                               $this->salida.="  <td align=\"center\" width=\"30%\">$val</td>";
									             $this->salida.="</tr>";
                              }
													else
													{
                             $this->salida.="<option value = $cod_opc>$opc</option>";
                             $indmin++;
													}


											}
											break;
			            }
				}//cierra el switche

      //res$i;

		 }//cierra el for
    $this->salida.="</table>";

		$this->salida.="<br><table  align=\"center\" border=\"0\" >";
		$this->salida.="<tr>";
		$this->salida.="<td class='label_error'>Informacion:</td><td align=\"center\"><font size='1'>$informacion</font></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";

		$this->salida.="<br><table  align=\"center\" border=\"0\" >";
    $this->salida.="<tr>";
    $this->salida.="<td align='left' class='label'>Observacion Medico</td><td width='10%'>&nbsp;&nbsp;</td><td align='left' class='label'>Observacion Bacteriologo</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\"><textarea class='textarea' name='ob_m' rows='5' cols=50></textarea></td>";
		$this->salida.="<td>&nbsp;&nbsp;</td>";
		$this->salida.="<td align=\"center\"><textarea class='textarea' name='ob_b' rows='5' cols=50></textarea></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";

		$this->salida.="<br><table align=\"center\" width='30%' border=\"0\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"Insertar\" type=\"submit\" value=\"Insertar\"></td>";
		$this->salida .= "</form>";
		$accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "<form name=\"forma\" action=\"$accion2\" method=\"post\">";
		$this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
 	  $this->salida .= "</tr>";
  	$this->salida .=  "</table><br>";



  	$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
  }
}


function frmConsulta()
{
  $examenes = $this->ConsultaExamenesPaciente();
	if ($examenes == false)
	{
    return false;
	}
  $pfj=$this->frmPrefijo;
	$this->salida = ThemeAbrirTablaSubmodulo('CONSULTA EXAMENES CLINICOS');
  if($examenes)
	{
    			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";

					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"4\">EXAMENES REALIZADOS</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td>EXAMEN</td>";
					$this->salida.="  <td>LABORATORIO</td>";
					$this->salida.="  <td>FECHA DE LA PRUEBA</td>";
          $this->salida.="  <td>OPCION</td>";
					$this->salida.="</tr>";

					for($i=0;$i<sizeof($examenes);$i++)
					{
						$examen        =  $examenes[$i][titulo_examen];
						$laboratorio   =  $examenes[$i][laboratorio];
						$fecha_lab     =  $examenes[$i][fecha_lab];
						$lab_id        =  $examenes[$i][lab_id];
            $observacion_m =  $examenes[$i][observacion_m];
						$observacion_b =  $examenes[$i][observacion_b];
						$informacion   =  $examenes[$i][informacion];

						if( $i % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$this->salida.="<tr class=\"$estilo\">";
						//pendiente ojo cuadrar la siguiente linea
            $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'=>'consultad',
						'lab_id'=>$lab_id,'examen'=>$examen, 'fecha_lab'=>$fecha_lab, 'laboratorio'=>$laboratorio,
						'observacion_m'=>$observacion_m, 'observacion_b'=>$observacion_b, 'informacion'=>$informacion));
						$this->salida.="  <td align=\"left\" width=\"25%\">".strtoupper($examen)."</td>";
					  $this->salida.="  <td align=\"left\" width=\"20%\">".strtoupper($laboratorio)."</td>";
						$this->salida.="  <td align=\"center\" width=\"10%\">".$fecha_lab."</td>";
					  $this->salida.="  <td align=\"center\" width=\"5%\"><img src=\"".GetThemePath()."/images/flecha_der.gif\" width=\"15\" height=\"15\"><a href='$accion'>&nbsp;RESULTADO</a></td>";

						$this->salida.="</tr>";
					}
					$this->salida.="</table>";
		}
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
}




/*funcion que muestra los resultados de cada examen seleccionado.*/
function FormaDetalleExamenes($lab_id,$examen, $fecha_lab, $laboratorio, $observacion_m, $observacion_b, $informacion)
{

	$pfj=$this->frmPrefijo;
	$this->salida = ThemeAbrirTablaSubmodulo('RESULTADOS DEL EXAMEN:&nbsp; '.strtoupper($examen).'');

	$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
  $this->salida .="<td align='center'><font size='4'>$examen</font></td>" ;
	$this->salida.="</table><br>";

  $action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar','id'=>$id,'examen'=>$examen));


//no es necesaria esta forma

	$this->salida .= "<form name=\"formades\" action=\"$action\" method=\"post\">";

	$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
	$this->salida .= $this->SetStyle("MensajeError");
	$this->salida.="</table>";


	$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"70%\" class=\"normal_10\">";
  $this->salida .="</tr><td class='label'>Fecha del Examen:  </td><td class='label'>$fecha_lab</td>" ;
  $this->salida .="<td class='label'>Laboratorio:  </td><td class='label'>$laboratorio</td></tr>" ;
	$this->salida.="</table><br>";


	$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"90%\">";
	$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
	$this->salida.="  <td>NOMBRE DEL EXAMEN</td>";
	$this->salida.="  <td>RESULTADO</td>";
	$this->salida.="  <td>RANGO NORMAL</td>";
	$this->salida.="</tr>";


	$vector = $this->ConsultaDetalle($lab_id);
	if ($vector == false)
	{
    return false;
	}

	if($vector)
	 {
		$p = 1;
		for($i=0;$i<sizeof($vector);$i++)
		 {

			$spia = $vector[$i][lab_plantilla_id];
		  switch ($spia)
	     {
        case "1": {
									 $nombre=$vector[$i][nombre_examen];
									 $rangoMin=$vector[$i][rango_min];
									 $rangoMax=$vector[$i][rango_max];
								   $unidad=$vector[$i][unidades];
									 $resultado = $vector[$i][resultado];

									 if(is_null($rangoMin) || is_null($rangoMax))
										{
										 $val='';
										 if(is_null($rangoMin) || $rangoMin == '0')
											{
											 $rangoMin = 0;
											 $val="Rango :".$rangoMin."-".$rangoMax."&nbsp;".$unidad."";
											}
										}
									 else
										{
										 if(empty($sexo) || $sexo == '0')
											{
												$val="Rango :".$rangoMin."-".$rangoMax."&nbsp;".$unidad."";
											}
										 else
											{ $p=0;
												if ($sexo == $this->datosPaciente[sexo_id])
													{   if(strtoupper($sexo)=='F')
											 				 {
																$val="Mujeres : ".$rangoMin."-".$rangoMax."&nbsp;".$unidad."";
																if( $i % 2)
																	{
																		$estilo='modulo_list_claro';
																	}
																else
																	{
																		$estilo='modulo_list_oscuro';
																	}
																$this->salida.="<tr class=\"$estilo\">";
																$this->salida.="  <td align=\"center\" width=\"40%\">$nombre</td>";
																$this->salida.="  <td align=\"center\" width=\"30%\">$resultado&nbsp;$unidad</td>";
																$this->salida.="  <td align=\"center\" width=\"30%\">$val</td>";
																$this->salida.="</tr>";
															 }
															elseif(strtoupper($sexo)=='M')
																{
																	$val="Hombres : ".$rangoMin."-".$rangoMax."&nbsp;".$unidad."";
																	if( $i % 2)
																		{
																			$estilo='modulo_list_claro';
																		}
																	else
																		{
																			$estilo='modulo_list_oscuro';
																		}
																	$this->salida.="<tr class=\"$estilo\">";
																	$this->salida.="  <td align=\"center\" width=\"40%\">$nombre</td>";
																  $this->salida.="  <td align=\"center\" width=\"30%\">$resultado&nbsp;$unidad</td>";
																	$this->salida.="  <td align=\"center\" width=\"30%\">$val</td>";
																	$this->salida.="</tr>";
																}
													}
											}
										}
							     if( $i % 2)
										 {
										  $estilo='modulo_list_claro';
										 }
									 else
										 {
											$estilo='modulo_list_oscuro';
										 }
									 if ($p == 1)
										 {
										  	$this->salida.="<tr class=\"$estilo\">";
											  $this->salida.="  <td align=\"center\" width=\"40%\">$nombre</td>";
												$this->salida.="  <td align=\"center\" width=\"30%\">$resultado&nbsp;$unidad</td>";
												$this->salida.="  <td align=\"center\" width=\"30%\">$val</td>";
											  $this->salida.="</tr>";
										 }
										break;
									}

			  case "2": {
										$val = '';
										$nombre=$vector[$i][nombre_examen];
										$opc = $vector[$i][opcion];
										$cod_opc = $vector[$i][lab_examen_opcion_id];
										$id = $vector[$i][lab_examen_id];
										$resultado = $vector[$i][resultado];

										$opcion = $this->ConversionOpcion($resultado, $id);
                    $opcion = $opcion[0][opcion];
										if( $i % 2)
												{
													$estilo='modulo_list_claro';
												}
											else
												{
													$estilo='modulo_list_oscuro';
												}

											$this->salida.="<tr class=\"$estilo\">";
											$this->salida.="<td align=\"center\" width=\"40%\">$nombre</td>";
											$this->salida.="<td align=\"center\" width=\"30%\">$opcion</td>";
											$this->salida.="  <td align=\"center\" width=\"30%\">$val</td>";
											$this->salida.="</tr>";
											break;
			              }
				}//cierra el switche

      //res$i;

		 }//cierra el for
    $this->salida.="</table>";

		$this->salida.="<br><table  align=\"center\" border=\"0\" >";
		$this->salida.="<tr>";



		$this->salida.="<td class='label_error'>Informacion:</td><td align=\"center\"><font size='1'>$informacion</font></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";

		$this->salida.="<br><table  align=\"center\" border=\"0\" >";
    $this->salida.="<tr>";
    $this->salida.="<td align='left'  class=\"hc_table_submodulo_list_title\" width='30%'>Observacion Medico
		                </td><td width='10%'>&nbsp;&nbsp;</td><td align='left' class=\"hc_table_submodulo_list_title\"width='30%'>
										Observacion Bacteriologo</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr>";
		$this->salida.="<td align=\"center\" class=\"$estilo\" width='30%'>$observacion_m</td>";
		$this->salida.="<td>&nbsp;&nbsp;</td>";
		$this->salida.="<td align=\"center\"class=\"$estilo\" width='30%'>$observacion_b</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";

		$this->salida.="<br><table align=\"center\" width='30%' border=\"0\">";
		$this->salida .= "<tr>";
		$this->salida .= "</form>";


		$accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "<form name=\"forma\" action=\"$accion2\" method=\"post\">";
		$this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
 	  $this->salida .= "</tr>";
  	$this->salida .=  "</table><br>";


		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}
}
}
?>
