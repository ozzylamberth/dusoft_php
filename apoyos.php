
<?
	$VISTA='HTML';
	$_ROOT = 'SIIS_18032005/';
	include $_ROOT . 'includes/enviroment.inc.php';

	list($dbconn) = GetDBconn();

  //BLOQUE QUE CREA LAS COLUMNAS
	$query = 'ALTER TABLE "hc_apoyod_resultados_detalles" ADD COLUMN "rango_min" character varying(20);
						ALTER TABLE "hc_apoyod_resultados_detalles" ADD COLUMN "rango_max" character varying(20);
						ALTER TABLE "hc_apoyod_resultados_detalles" ADD COLUMN "unidades" character varying(10);';
	$resulta=$dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0)
	{
			$this->error = "Error actualizar consultorios";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
	}


	//CASO PLANTILLA 1 ->  BLOQUE PARA ACTUALIZAR DATOS EN LAS COLUMNAS CREADAS.
	$query= "SELECT a.lab_examen_id, a.resultado_id, d.lab_plantilla_id, c.fecha_nacimiento,
	b.fecha_realizado, c.sexo_id, d.unidades

	FROM hc_apoyod_resultados_detalles a, hc_resultados b, pacientes c,	lab_examenes d

	WHERE d.lab_plantilla_id = '1' and a.resultado_id = b.resultado_id and

	b.tipo_id_paciente = c.tipo_id_paciente and b.paciente_id = c.paciente_id and
	a.lab_examen_id = d.lab_examen_id order by d.lab_plantilla_id, a.lab_examen_id, c.sexo_id";

	$result = $dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0)
	{
			$this->error = "Error al consultar resultados tipo 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
	}
	else
	{
			while (!$result->EOF)
			{
				$datos[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
	}


	for($j=0;$j<sizeof($datos);$j++)
	{
			$query = "SELECT * from lab_plantilla1 where lab_examen_id = ".$datos[$j][lab_examen_id]."";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al consultar resultados tipo 1";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			else
			{
					while (!$result->EOF)
					{
						$vector[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
					}
			}
			$edad_paciente = CalcularEdad($datos[$j][fecha_nacimiento],$datos[$j][fecha_realizado]);

			$val='';
			$rango_minimo = '';
			$rango_maximo = '';
			for($i=0;$i<sizeof($vector);$i++)
	    {
					//obteniendo el rango adecuado
					if(is_null($vector[$i][rango_min]) || is_null($vector[$i][rango_max]))
					{
							if(is_null($vector[$i][rango_min]) || $vector[$i][rango_min] == '0')
							{
									$vector[$i][rango_min] = 0;
									$val="Rango :".$vector[$i][rango_min]."-".$vector[$i][rango_max]."&nbsp;".$vector[$i][unidades]."";
									$rango_minimo = $vector[$i][rango_min];
                  $rango_maximo = $vector[$i][rango_max];
							}
					}
					else
					{
							if(empty($vector[$i][sexo_id]) || $vector[$i][sexo_id] == '0')
							{
									$val="Rango :".$vector[$i][rango_min]."-".$vector[$i][rango_max]."&nbsp;".$vector[$i][unidades]."";
									$rango_minimo = $vector[$i][rango_min];
                  $rango_maximo = $vector[$i][rango_max];
							}
							else
							{
									if ($vector[$i][sexo_id] == $datos[$j][sexo_id])
									{
											if(strtoupper($vector[$i][sexo_id])=='F')
											{
													$val="Mujeres : ".$vector[$i][rango_min]."-".$vector[$i][rango_max]."&nbsp;".$vector[$i][unidades]."";
													$rango_minimo = $vector[$i][rango_min];
                          $rango_maximo = $vector[$i][rango_max];
											}
											elseif(strtoupper($vector[$i][sexo_id])=='M')
											{
													if (empty($vector[$i][edad_min]) AND empty($vector[$i][edad_max]))
													{
															$val="Hombres : ".$vector[$i][rango_min]."-".$vector[$i][rango_max]."&nbsp;".$vector[$i][unidades]."";
															$rango_minimo = $vector[$i][rango_min];
                              $rango_maximo = $vector[$i][rango_max];
													}
													elseif ((!empty($vector[$i][edad_min]) OR $vector[$i][edad_min] != '') AND (!empty($vector[$i][edad_max]) OR $vector[$i][edad_max] != ''))
													{
														if (($edad_paciente[anos] >= $vector[$i][edad_min]) AND ($edad_paciente[anos] <= $vector[$i][edad_max]))
														{
																$val="Hombres : ".$vector[$i][rango_min]."-".$vector[$i][rango_max]."&nbsp;".$vector[$i][unidades]."";
																$rango_minimo = $vector[$i][rango_min];
                                $rango_maximo = $vector[$i][rango_max];
														}
													}
											}
									}
							}
					}
					//fin del rango
			}

			unset ($vector);
			if ($val != '')
			{
						$query = "UPDATE hc_apoyod_resultados_detalles
					  SET rango_min = '".$rango_minimo."', rango_max = '".$rango_maximo."' , unidades = '". $datos[$j][unidades]."'
						WHERE lab_examen_id =  ".$datos[$j][lab_examen_id]."
						and resultado_id = ".$datos[$j][resultado_id]."";
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error actualizar consultorios";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
					ECHO "<br>"."ACTUALIZACION APOYO No. ".($j+1)." lab_examen_id-> ".$datos[$j][lab_examen_id]." resultado_id-> ".$datos[$j][resultado_id]." -> ".$val." ".$datos[$j][unidades].""."<br>";
			}
			else
			{
          ECHO "<br>"."ACTUALIZACION APOYO No. ".($j+1)." lab_examen_id-> ".$datos[$j][lab_examen_id]." resultado_id-> ".$datos[$j][resultado_id]." ------------> NO REQUIERE ".$datos[$j][unidades].""."<br>";

			}
	}
	ECHO "<br><BR>"." FELICITACIONES ***********ACTUALIZACION APOYOS TERMINADA -> CASO PLANTILLA 1*********";


	//CASO PLANTILLA 2 ->  BLOQUE PARA ACTUALIZAR DATOS EN LAS COLUMNAS CREADAS.
	$query= "SELECT a.lab_examen_id, a.resultado_id, a.resultado,
	d.lab_plantilla_id, c.fecha_nacimiento,
	b.fecha_realizado, c.sexo_id, d.unidades

	FROM hc_apoyod_resultados_detalles a, hc_resultados b, pacientes c,	lab_examenes d

	WHERE d.lab_plantilla_id = '2' and a.resultado_id = b.resultado_id and

	b.tipo_id_paciente = c.tipo_id_paciente and b.paciente_id = c.paciente_id and
	a.lab_examen_id = d.lab_examen_id order by d.lab_plantilla_id, a.lab_examen_id, c.sexo_id";

	$result = $dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0)
	{
			$this->error = "Error al consultar resultados tipo 2";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
	}
	else
	{
			while (!$result->EOF)
			{
				$datos1[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
	}


	for($j=0;$j<sizeof($datos1);$j++)
	{
			$query = "SELECT opcion from lab_plantilla2 where
      lab_examen_opcion_id = ".$datos1[$j][resultado]." and
			lab_examen_id = ".$datos1[$j][lab_examen_id]."	";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al consultar resultados tipo 1";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			else
			{
				$vector1=$result->GetRowAssoc($ToUpper = false);
			}

			if ($vector1[opcion])
			{
						$query = "UPDATE hc_apoyod_resultados_detalles
					  SET resultado = '".$vector1[opcion]."'
						WHERE lab_examen_id =  ".$datos1[$j][lab_examen_id]."
						and resultado_id = ".$datos1[$j][resultado_id]."";
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error actualizar el resultado en la plantilla 2";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
					  ECHO "<br>"."ACTUALIZACION APOYO No. ".($j+1)." lab_examen_id-> ".$datos1[$j][lab_examen_id]." resultado_id-> ".$datos1[$j][resultado_id]." -> ".$datos1[$j][resultado]." ".$vector1[opcion]." "."<br>";
			}
			else
			{
          ECHO "<br>"."ACTUALIZACION APOYO No. ".($j+1)." lab_examen_id-> ".$datos1[$j][lab_examen_id]." resultado_id-> ".$datos1[$j][resultado_id]." -> ".$datos1[$j][resultado]." ---------------------> NO APLICA "."<br>";

			}
			unset ($vector1);
	}
  ECHO "<br><BR>"." FELICITACIONES ***********ACTUALIZACION APOYOS TERMINADA -> CASO PLANTILLA 2*********";


?>
