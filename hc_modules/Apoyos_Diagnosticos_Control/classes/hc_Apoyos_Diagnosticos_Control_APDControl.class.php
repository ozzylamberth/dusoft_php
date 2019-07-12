<?
/**
* Submodulo de Apoyos Diagnosticos.
*
* Submodulo para manejar los apoyos diagnosticos, permite la captura de resultados de los examenes, 
* y la lectura por parte del profesional.
* @author Luis Alejandro Vargas
* @version 1.0
* @package SIIS
* $Id: hc_Apoyos_Diagnosticos_Control_APDControl.class.php,v 1.1 2009/07/30 12:38:06 johanna Exp $
*/

class APDControl
{

	function APDControl()
	{
		$this->frmPrefijo=$_SESSION['frmprefijo'];	
		$this->datosPaciente=$_SESSION['datospaciente'];
		$this->ingreso=$_SESSION['ingreso'];
		$this->evolucion=$_SESSION['evolucion'];
		$this->paso=$_SESSION['paso'];
		return true;
	}
	
	function ErrorDB()
	{
		$this->frmErrorBD=$this->error."<br>".$this->mensajeDeError;
		return $this->frmErrorBD;
	}
	
	function Consultar_Tecnicas_Examen($cargo)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT cargo, tecnica_id, nombre_tecnica, sw_predeterminado
						FROM apoyod_cargos_tecnicas 
						WHERE cargo = '".$cargo."' 
						ORDER BY sw_predeterminado desc";
	
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en  hc_Apoyos_Diagnosticos_Control_APDControl - Consultar_Tecnicas_Examen - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$vars[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		
		$dbconn->CommitTrans();
		return $vars;
	}

	function ConsultaComponentesExamen($cargo,$tecnica_id,$datosPaciente,$sw_plantilla0)
	{
			//Como contingencia para la SOS se corrigio este query
			//Se corrigio cuando el cargo tiene varios subexamenes
			//no trae el lab_examen_id adecuado o duplica los
			//examenes traidos
			//cuando la pantilla es != 0 se ejecutael primer query
			//si el = 0   se ejecuta el segundo
			//replantear estructura MB. Hay momentos enque el query viejo
			//(segundo) si funciona con cualquier plantilla
			//examinar si es por parametrizacion
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();			
			
			$edad=$datosPaciente['edad_paciente']['anos'];
			$sexo_id=$datosPaciente['sexo_id'];

			if($sw_plantilla0=='1')
			{
				$query = "
							(SELECT  a.cargo, a.tecnica_id, a.lab_examen_id,
											a.lab_plantilla_id, a.nombre_examen, a.indice_de_orden,
											b.sexo_id, b.rango_min, b.rango_max,
											b.edad_min, b.edad_max, b.unidades as unidades_1,
											NULL AS opcion,  NULL AS unidades_2, NULL AS detalle
											
							FROM 
									lab_examenes a ,
									lab_plantilla1 b 
							WHERE 	a.cargo='".$cargo."' 
											and a.cargo = b.cargo 
											and a.tecnica_id = '".$tecnica_id."'
											and a.tecnica_id = b.tecnica_id 
											and a.lab_examen_id = b.lab_examen_id 
											and (b.sexo_id = '".$sexo_id."'  OR b.sexo_id = '0')
											and (".$edad." >= b.edad_min  OR b.edad_min = 0)
											and (".$edad." <= b.edad_max  OR b.edad_min = 0)
							)
							UNION
							(
							SELECT  a.cargo, a.tecnica_id, a.lab_examen_id,
											a.lab_plantilla_id, a.nombre_examen, a.indice_de_orden,
											NULL AS sexo_id,NULL AS rango_min,NULL AS rango_max,
											NULL AS edad_min,NULL AS edad_max,NULL AS unidades_1,
											c.opcion, c.unidades as unidades_2, NULL AS detalle
							FROM 
									lab_examenes a ,
									lab_plantilla2 c
							WHERE 	a.cargo='".$cargo."' 
											and a.cargo = c.cargo 
											and a.tecnica_id = '".$tecnica_id."'
											and a.tecnica_id = c.tecnica_id 
											and a.lab_examen_id = c.lab_examen_id 
							)		
							UNION
							(
							SELECT  a.cargo, a.tecnica_id, a.lab_examen_id,
											a.lab_plantilla_id, a.nombre_examen, a.indice_de_orden,
											NULL AS sexo_id,NULL AS rango_min,NULL AS rango_max,
											NULL AS edad_min,NULL AS edad_max,NULL AS unidades_1,
											NULL AS opcion, NULL AS unidades_2,
											d.detalle
							FROM 
									lab_examenes a ,
									lab_plantilla3 d
							WHERE 	a.cargo='".$cargo."' 
											and a.cargo = d.cargo 
											and a.tecnica_id = '".$tecnica_id."'
											and a.tecnica_id = d.tecnica_id 
											and a.lab_examen_id = d.lab_examen_id 
							)
			";
			}
			else
			{
				$query = "SELECT  a.cargo, a.tecnica_id, a.lab_examen_id,
							a.lab_plantilla_id, a.nombre_examen, a.indice_de_orden,
							b.sexo_id, b.rango_min, b.rango_max,
							b.edad_min, b.edad_max, b.unidades as unidades_1,
							c.opcion,	c.unidades as unidades_2,	d.detalle
							FROM lab_examenes a left join lab_plantilla1 b on
							(a.cargo = b.cargo and a.tecnica_id = b.tecnica_id and
							a.lab_examen_id = b.lab_examen_id and (b.sexo_id = '".$sexo_id."' OR
							b.sexo_id isNULL OR b.sexo_id = '0')
							and (".$edad." >= b.edad_min OR b.edad_min isNULL OR b.edad_min = 0)
							and (".$edad." <= b.edad_max OR b.edad_max isNULL OR b.edad_min = 0))
							left join lab_plantilla2 c on (a.cargo = c.cargo and a.tecnica_id = c.tecnica_id
							and a.lab_examen_id = c.lab_examen_id)
							left join lab_plantilla3 d  on (a.cargo = d.cargo and a.tecnica_id = d.tecnica_id
							and a.lab_examen_id = d.lab_examen_id)
							WHERE a.cargo='".$cargo."' and a.tecnica_id = ".$tecnica_id."
							order by a.indice_de_orden";
			}

			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error en el hc_Apoyos_Diagnosticos_Control_1 - ConsultaComponentesExamen - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				while (!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			return $vars;
	}
	
	function CrearGenerico($cargo, $titulo)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT COUNT(*) FROM apoyod_cargos_tecnicas
						WHERE cargo = '".$cargo."' and tecnica_id = 1";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de apoyod_cargos_tecnicas";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		
		list($var_existe_apoyo_tecnica)=$result->FetchRow();

		if ($var_existe_apoyo_tecnica == 0)
		{
			$query="SELECT COUNT(*) FROM apoyod_cargos
							WHERE cargo = '".$cargo."'";
			
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error en la consulta del Pagador";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			
			list($var_existe_apoyo)=$result->FetchRow();
			
			if ($var_existe_apoyo == 0)
			{
				$query="INSERT INTO apoyod_cargos(cargo,titulo_examen, sexo_id, apoyod_tipo_id)
								VALUES  ('".$cargo."', '".$titulo."', 0,
								(SELECT grupo_tipo_cargo FROM cups WHERE cargo = '".$cargo."'))";

				$result=$dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al insertar en apoyod_cargos";
					$this->frmError["MensajeError"]="Error al insertar en apoyod_cargos.";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}

				$query="INSERT INTO apoyod_cargos_tecnicas
								(tecnica_id, cargo, nombre_tecnica, sw_predeterminado)
								VALUES  (1, '".$cargo."', 'Tecnica Generica', 0)";

				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al insertar en apoyod_cargos_tecnicas";
						$this->frmError["MensajeError"]="Error al insertar en apoyod_cargos_tecnicas.";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}
			}
			else
			{
				$query="INSERT INTO apoyod_cargos_tecnicas
								(tecnica_id, cargo, nombre_tecnica, sw_predeterminado)
								VALUES  (1, '".$cargo."', 'Tecnica Generica', 0)";

				$result=$dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al insertar en apoyod_cargos_tecnicas";
						$this->frmError["MensajeError"]="Error al insertar en apoyod_cargos_tecnicas.";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}
			}
		}
		
		$query="SELECT COUNT(*) FROM lab_examenes
						WHERE tecnica_id = 1 and cargo = '".$cargo."' and
						lab_examen_id = 0";
		
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en la consulta del lab_examenes";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		list($var_existe_lab_examen)=$result->FetchRow();
		
		if ($var_existe_lab_examen == 0)
		{
			$query="INSERT INTO lab_examenes
							(tecnica_id, cargo, lab_examen_id, lab_plantilla_id, nombre_examen)
							VALUES  (1, '".$cargo."', 0, 0, 'GENERICO')";

			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar en lab_examenes";
				$this->frmError["MensajeError"]="Error al insertar en lab_examenes.";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		
		$dbconn->CommitTrans();
		
		return true;
	}
	
	function Insertar($datos)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$pfj=$this->frmPrefijo;
		$k=0;
		
		$cargo=$datos['datos'][0];
		$tecnica=$datos['datos'][1];
		
		$tipo_id_paciente=$datos['datos'][2];
		$paciente_id=$datos['datos'][3];
		$evolucion=$datos['datos'][4];
		
		$fechaI=$datos['fecha_realizado'.$pfj];
		$observacion=$datos['observacion'.$pfj];
		$observacion2=$datos['observacion_medico'.$pfj];
		
		$subindice=$datos['items'.$k.$pfj];
		
		if (!$datos['items'.$k.$pfj])
		{
				$subindice=1;
		}

		$query="SELECT c.apoyod_tipo_id as tipo_resultadoapd,
						d.grupo_tipo_cargo as tipo_resultadonqx
						FROM cups a, grupos_noqx_apoyod b left join apoyod_tipos c
						on (b.grupo_tipo_cargo = c.apoyod_tipo_id) left join
						no_qx_grupos_tipo_cargo d on (b.grupo_tipo_cargo = d.grupo_tipo_cargo)
						WHERE a.cargo = '".$cargo."'
						AND a.grupo_tipo_cargo = b.grupo_tipo_cargo";
		
		$result=$dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar el tipo de resultado para el examen, hc_Apoyos_Diagnosticos_Control_1 - Insertar - SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$tipo_resultado=$result->GetRowAssoc($ToUpper = false);
		
		if ($tipo_resultado)
		{
			if ($tipo_resultado[tipo_resultadoapd]!= NULL OR $tipo_resultado[tipo_resultadoapd]!= '')
			{
					$os_tipo_resultado = ModuloGetVar('','','TipoSolicitudApoyod');
			}
			else
			{
				if ($tipo_resultado[tipo_resultadonqx]!= NULL OR $tipo_resultado[tipo_resultadonqx]!= '')
				{
					$os_tipo_resultado = 'PNQ';
				}
				else
				{
					$this->frmError["MensajeError"]="ESTE EXAMEN NO TIENE ASOCIADO UN TIPO DE RESULTADO.";
					return false;
				}
			}
		}
		else
		{
			$this->frmError["MensajeError"]="ESTE EXAMEN NO TIENE ASOCIADO UN TIPO DE RESULTADO";
			return false;
		}
		
		$query="SELECT nextval('hc_resultados_resultado_id_seq')";
		$result=$dbconn->Execute($query);
		$resultado_id=$result->fields[0];
		
		$fecha=$this->FechaStamp($fechaI);
		
		$query="INSERT INTO hc_resultados 
						(resultado_id,
						cargo, tecnica_id,
						fecha_registro, usuario_id, tipo_id_paciente, paciente_id,
						fecha_realizado,
						os_tipo_resultado,
						observacion_prestacion_servicio, sw_modo_resultado)
						VALUES(".$resultado_id.",
						'".$cargo."', ".$tecnica.",
						now(), ".UserGetUID().", '".$tipo_id_paciente."',
						'".$paciente_id."',
						'".$fecha."', '".$os_tipo_resultado."', '".$observacion."', '3')";

		$resulta1=$dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Insertar en hc_Apoyos_Diagnosticos_Control_1 - Insertar -SQL 2";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
			$sw_prof = '1';
			$sw_prof_dpto = '0';
			$sw_prof_todos = '0';
			$query="INSERT INTO hc_apoyod_lecturas_profesionales
							(resultado_id, evolucion_id, sw_prof, sw_prof_dpto, sw_prof_todos,
							observacion_prof)
							VALUES  (".$resultado_id.", ".$evolucion.", '".$sw_prof."',
							'".$sw_prof_dpto."', '".$sw_prof_todos."', '".$observacion2."')";
			
			$resulta2=$dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "$query - <br>Error al insertar en hc_apoyod_lecturas_profesionales - Insertar";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
			}
			else
			{
				$laboratorio=$datos['laboratorio'.$pfj];
				$profesional=$datos['profesional'.$pfj];
					
				$query="INSERT INTO hc_resultados_nosolicitados
					(resultado_id, laboratorio, profesional)
					VALUES  (".$resultado_id.", '".$laboratorio."', '".$profesional."')";
	
				$resulta3=$dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al insertar en hc_resultados_nosolicitados - Insertar";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}
				else
				{
					for($i=0;$i<$subindice;$i++)
					{
						$resultado=$datos['resultado'.$k.$i.$pfj];
						$lab_examen=$datos['lab_examen'.$k.$i.$pfj];
						$pat=$datos['sw_patologico'.$k.$i.$pfj];
						$rmin=$datos['rmin'.$k.$i.$pfj];
						$rmax=$datos['rmax'.$k.$i.$pfj];
						$unidades=$datos['unidades'.$k.$i.$pfj];
						
						if ($pat)
						{
							$sw_alerta = '1';
						}
						else
						{
							if (($rmin != '') and ($rmax != ''))
							{
								if (($resultado>= $rmin) and ($resultado <= $rmax))
								{
										$sw_alerta = '0';
								}
								else
								{
										$sw_alerta = '1';
								}
							}
							else
							{
									$sw_alerta = '0';
							}
						}
						if ($rmin == 'NULL'){$rmin ='';}
						if ($rmax == 'NULL'){$rmax='';}
						if ($unidades == 'NULL'){$unidades='';}
	
	
						$query="INSERT INTO hc_apoyod_resultados_detalles
						(cargo, tecnica_id, lab_examen_id, resultado_id, resultado,
						sw_alerta, rango_min, rango_max, unidades)
						VALUES  ('".$cargo."',
						".$tecnica.",
						".$lab_examen.",".$resultado_id.",'".$resultado."', '".$sw_alerta."',
						'".$rmin."',	'".$rmax."',	'".$unidades."')";
	
						$resulta4=$dbconn->Execute($query);
						
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al insertar en hc_apoyod_resultados_detalles - Insertar";
							$this->frmError["MensajeError"]="ERROR AL INSERTAR EL RESULTADO.";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
				}
			}
		}
		$dbconn->CommitTrans();
		return $resultado_id;
	}
	
	function ConsultaExamenesPaciente($resultado_id, $sw_modo_resultado)
	{
		list($dbconnect) = GetDBconn();

		//esta consulta la referencia a los examens en resultado manual, en resultados
		//no solicitados y en resultados sistema.
    $query = '';
		if ($sw_modo_resultado == '1')
		{
				$query="  SELECT b.numero_orden_id, a.resultado_id, a.fecha_realizado,
				          a.observacion_prestacion_servicio,
									i.nombre_tercero as profesional, case when f.razon_social is not null then
									f.razon_social else k.nombre_tercero end as laboratorio,l.descripcion, m.informacion

									FROM hc_resultados as a, hc_resultados_sistema as b, profesionales_usuarios as g,
									profesionales as h, terceros as i, os_maestro as c left join os_internas as d on
									(c.numero_orden_id=d.numero_orden_id) left join departamentos as e on
									(d.departamento=e.departamento) left join empresas as f on(e.empresa_id=f.empresa_id)
									left join os_externas as j on(c.numero_orden_id=j.numero_orden_id) left join
									terceros as k on(j.tipo_id_tercero=k.tipo_id_tercero and j.tercero_id=k.tercero_id), cups l, apoyod_cargos m

									WHERE a.resultado_id = b.resultado_id and a.resultado_id = ".$resultado_id." and
									b.numero_orden_id=c.numero_orden_id and b.usuario_id_profesional=g.usuario_id and
									g.tipo_tercero_id=h.tipo_id_tercero and g.tercero_id=h.tercero_id and
									h.tipo_id_tercero=i.tipo_id_tercero and h.tercero_id=i.tercero_id
									and c.cargo_cups = l.cargo and l.cargo = m.cargo
							;";

		}
		elseif ($sw_modo_resultado == '2')
		{
				$query="  SELECT b.numero_orden_id, a.resultado_id, a.fecha_realizado,
				          a.observacion_prestacion_servicio,
									b.profesional, case when f.razon_social is not null then f.razon_social else
									k.nombre_tercero end as laboratorio

									,l.descripcion, m.informacion

									FROM hc_resultados as a, hc_resultados_manuales as b,    os_maestro as c
									left join os_internas as d on(c.numero_orden_id=d.numero_orden_id) left join
									departamentos as e on(d.departamento=e.departamento) left join empresas as f
									on(e.empresa_id=f.empresa_id) left join os_externas as j on
									(c.numero_orden_id=j.numero_orden_id)    left join terceros as k on
									(j.tipo_id_tercero=k.tipo_id_tercero and j.tercero_id=k.tercero_id), cups l,	apoyod_cargos m
									
									WHERE a.resultado_id = b.resultado_id and a.resultado_id = ".$resultado_id."
									and b.numero_orden_id=c.numero_orden_id
                  and c.cargo_cups = l.cargo and l.cargo = m.cargo
							;";
		}
		elseif ($sw_modo_resultado == '3')
		{
				$query="  SELECT a.resultado_id, a.fecha_realizado,
									a.observacion_prestacion_servicio, b.profesional, b.laboratorio,l.descripcion, m.informacion
									
									FROM hc_resultados as a, hc_resultados_nosolicitados as b, cups l,	apoyod_cargos m
									
									WHERE a.resultado_id = b.resultado_id and a.resultado_id = ".$resultado_id."
									and a.cargo = l.cargo and l.cargo = m.cargo
							;";
		}

		if ($query !='')
		{
				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
				{
					$this->error = "Error al Consultar los datos del examen";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
					return false;
				}
				$a=$result->GetRowAssoc($ToUpper = false);

				//cargando las observaciones adicionales
				$query="SELECT a.resultado_id, a.observacion_adicional,
				a.fecha_registro_observacion, c.nombre_tercero as usuario_observacion
				FROM hc_resultados_observaciones_adicionales as a,
				profesionales_usuarios as b, terceros as c
				WHERE resultado_id = ".$resultado_id." AND
				a.usuario_id = b.usuario_id
				and b.tipo_tercero_id = c.tipo_id_tercero and b.tercero_id = c.tercero_id
				order by a.observacion_resultado_id";

				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
				{
						$this->error = "Error al consultar las observaciones adicionales al resultado del apoyo";
						$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
						return false;
				}
				else
				{ while (!$result->EOF)
						{
								$vector[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
						}
				}
				$a[observaciones_adicionales]=$vector;
			//fin de las observaciones adicionales
				$result->Close();
				return $a;
		}
		else
		{
        return false;
		}
	}

	function ConsultaResultados($evolucion=null,$inscripcion=null)
	{
			$pfj=$this->frmPrefijo;
			list($dbconnect) = GetDBconn();
		
			
			$query = "SELECT DISTINCT  
										b.sw_modo_resultado, 
										b.cargo, 
										b.fecha_realizado, 
										b.resultado_id,
										c.titulo_examen, 
										c.informacion, 
										d.sw_prof, 
										d.evolucion_id, 
										e.fecha,
										h.periodo_sugerido,
										h.periodo_solicitud,
										i.sw_alerta
								FROM hc_resultados_nosolicitados AS a
								LEFT JOIN hc_resultados AS b 
								ON
								(
									a.resultado_id = b.resultado_id
								)
								LEFT JOIN apoyod_cargos AS c 
								ON 
								(
									b.cargo = c.cargo
								) 
								LEFT JOIN hc_apoyod_resultados_detalles as i
								ON
								(
									b.resultado_id = i.resultado_id
									AND
									c.cargo = i.cargo
								)
								LEFT JOIN hc_apoyod_lecturas_profesionales AS d 
								ON 
								(
									b.resultado_id = d.resultado_id
								)
								LEFT JOIN hc_evoluciones AS e 
								ON 
								(
									d.evolucion_id = e.evolucion_id
								)
								LEFT JOIN pyp_solicitudes_inscripciones AS f
								ON 
								(
									e.evolucion_id=f.evolucion_id
								)
								LEFT JOIN pyp_evoluciones_procesos AS g
								ON 
								(
									f.evolucion_id=g.evolucion_id
									AND
									f.inscripcion_id=g.inscripcion_id
								)
								LEFT JOIN pyp_procedimientos_solicitados AS h
								ON 
								(
									g.evolucion_id=h.evolucion_id
									AND
									g.inscripcion_id=h.inscripcion_id
								)
								WHERE f.evolucion_id<=$evolucion
											AND f.inscripcion_id=$inscripcion";
											/*OR b.tipo_id_paciente = '".$this->datosPaciente['tipo_id_paciente']."' 
											AND b.paciente_id = '".$this->datosPaciente['paciente_id']."';";*/
	
			$result = $dbconnect->Execute($query);
	
			if ($dbconnect->ErrorNo() != 0)
			{
					$this->error = "Error en la consulta de examenes no solicitados";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
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
			$result->Close();
			
			return $vector;
	}
	
	
	function ConsultaResultadosPyp($evolucion,$inscripcion)
	{
			$pfj=$this->frmPrefijo;
			list($dbconnect) = GetDBconn();
			
			$query="SELECT DISTINCT  
								b.sw_modo_resultado, 
								b.cargo, 
								b.fecha_realizado, 
								b.resultado_id,
								c.titulo_examen, 
								c.informacion, 
								d.sw_prof, 
								d.evolucion_id, 
								e.fecha,
								i.sw_alerta
								FROM hc_resultados_nosolicitados AS a
								LEFT JOIN hc_resultados AS b 
								ON
								(
									a.resultado_id = b.resultado_id
								)
								LEFT JOIN apoyod_cargos AS c 
								ON 
								(
									b.cargo = c.cargo
								) 
								LEFT JOIN hc_apoyod_resultados_detalles as i
								ON
								(
									b.resultado_id = i.resultado_id
									AND
									c.cargo = i.cargo
								)
								LEFT JOIN hc_apoyod_lecturas_profesionales AS d 
								ON 
								(
									b.resultado_id = d.resultado_id
								)
								LEFT JOIN hc_evoluciones AS e 
								ON 
								(
									d.evolucion_id = e.evolucion_id
								)
								LEFT JOIN pyp_solicitudes_inscripciones AS f
								ON 
								(
									e.evolucion_id=f.evolucion_id
								)
								LEFT JOIN pyp_evoluciones_procesos AS g
								ON 
								(
									f.evolucion_id=g.evolucion_id
									AND
									f.inscripcion_id=g.inscripcion_id
								)
								WHERE f.evolucion_id<=$evolucion
								AND f.inscripcion_id=$inscripcion";
											
	
			$result = $dbconnect->Execute($query);
	
			if ($dbconnect->ErrorNo() != 0)
			{
					$this->error = "Error en la consulta de examenes no solicitados";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
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
			$result->Close();
			
			return $vector;
	}
	

	//ad*
	//esta funcion busca en la tabla hc_lecturas_profesionales el registro de las lecturas
	// realizadas para cada resultado_id
	function RegistroLecturas($resultado_id)
	{
			list($dbconnect) = GetDBconn();
			$query = "select resultado_id, sw_prof, sw_prof_dpto, sw_prof_todos, evolucion_id
			from hc_apoyod_lecturas_profesionales where resultado_id = ".$resultado_id."
			order by resultado_id";
	
			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
				$this->error = "Error en la consulta de lecturas profesionales";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}
			else
			{
				while (!$result->EOF)
				{
					$fact[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
			return $fact;
	}
	//pendiente borrarlo en el origen
	function ConsultaObservaciones($resultado_id)
	{
			list($dbconnect) = GetDBconn();
			$query =" SELECT a.resultado_id, a.evolucion_id, a.observacion_prof, d.nombre, e.descripcion
								FROM hc_apoyod_lecturas_profesionales as a, hc_evoluciones as b,
								profesionales_usuarios as c, profesionales d, tipos_profesionales e
								WHERE a.resultado_id = ".$resultado_id." AND a.evolucion_id = b.evolucion_id
								AND b.usuario_id = c.usuario_id AND c.tipo_tercero_id = d.tipo_id_tercero
								AND    c.tercero_id = d.tercero_id AND d.tipo_profesional = e.tipo_profesional";
			$result = $dbconnect->Execute($query);
	
			if ($dbconnect->ErrorNo() != 0)
			{
					$this->error = "Error al consultar las observaciones realizadas al Examen";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
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
			$result->Close();
			return $vector;
	}
	
	function ConsultaDetalle($resultado_id)
	{
		list($dbconnect) = GetDBconn();
		$query=   "SELECT DISTINCT
								a.lab_examen_id, a.resultado_id, a.resultado,	a.sw_alerta,
								a.rango_max, a.rango_min, a.unidades,
								b.lab_plantilla_id, b.nombre_examen
								FROM hc_apoyod_resultados_detalles a, lab_examenes b
								WHERE  a.resultado_id = ".$resultado_id." AND a.lab_examen_id=b.lab_examen_id";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al consultar los resultados de los examenes";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
				while (!$result->EOF)
				{
						$fact[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
		}
		$result->Close();
		return $fact;
	}
	
	function FechaStamp($fecha)
	{
		if($fecha)
		{
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}

			return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
		}
	}
	
}	
?>