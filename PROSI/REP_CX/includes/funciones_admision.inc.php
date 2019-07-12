<?php

/**
 * $Id: funciones_admision.inc.php,v 1.28 2006/03/24 19:29:03 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */
	function BuscarPacienteTriage($empresa,$tipo_id_paciente='',$paciente_id='',$prefijo='',$historia='',$nombres='',$cu='',$swcu='')
	{
			$filtroNombres='';
			$filtroDocumento='';
			$filtroHC='';
			$filtroPrefijo='';

			if(!empty($swcu))
			{   $filtroCU=" AND a.centro_utilidad='$cu'";  }

			if(!empty($tipo_id_paciente))
			{   $filtroTipoDocumento=" AND a.tipo_id_paciente = '$tipo_id_paciente'";   }

			if ($paciente_id != '')
			{   $filtroDocumento =" AND a.paciente_id = '$paciente_id'";   }

			if ($nombres != '')
			{
					$a=explode(' ',$nombres);
					foreach($a as $k=>$v)
					{
							if(!empty($v))
									{
											$filtroNombres =" and (upper(b.primer_nombre||' '||b.segundo_nombre||' '||
																						b.primer_apellido||' '||b.segundo_apellido) like '%".strtoupper($v)."%')";
									}
					}
			}

			if(!empty($prefijo))
			{  $filtroPrefijo = "and c.historia_prefijo='$prefijo'";  }


			if(!empty($historia))
			{  $filtroHC = "and c.historia_numero=".$historia."";  }

			list($dbconn) = GetDBconn();
			$query = "SELECT a.triage_id,a.punto_triage_id,a.tipo_id_paciente,a.paciente_id,
								b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
								d.descripcion, a.hora_llegada, c.historia_numero, c.historia_prefijo, a.plan_id, a.punto_admision_id
								FROM triages as a, puntos_triage as d, pacientes as b, historias_clinicas as c
								WHERE a.empresa_id='$empresa'
								and a.sw_estado='0'
								and a.punto_triage_id=d.punto_triage_id
								and a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id
								and a.tipo_id_paciente=c.tipo_id_paciente and a.paciente_id=c.paciente_id
								$filtroHC $filtroPrefijo $filtroNombres $filtroDocumento $filtroTipoDocumento
								$filtroCU
								ORDER BY a.tipo_id_paciente,a.paciente_id,a.hora_llegada desc";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{
					while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					//$var[]=$result->GetRowAssoc($ToUpper = false);
					//$result->MoveNext();
			}

			$result->Close();
			return $var;
	}

	/**
	*
	*/
	function BuscarPacientePteAdmision($empresa,$tipo_id_paciente='',$paciente_id='',$prefijo='',$historia='',$nombres='',$cu='',$swcu='')
	{
			$filtroNombres='';
			$filtroDocumento='';
			$filtroHC='';
			$filtroPrefijo='';

			if(!empty($swcu))
			{   $filtroCU=" AND a.centro_utilidad='$cu'";  }

			if(!empty($tipo_id_paciente))
			{   $filtroTipoDocumento=" AND a.tipo_id_paciente = '$tipo_id_paciente'";   }

			if ($paciente_id != '')
			{   $filtroDocumento =" AND a.paciente_id = '$paciente_id'";   }

			if ($nombres != '')
			{
					$a=explode(' ',$nombres);
					foreach($a as $k=>$v)
					{
							if(!empty($v))
									{
											$filtroNombres =" and (upper(b.primer_nombre||' '||b.segundo_nombre||' '||
																						b.primer_apellido||' '||b.segundo_apellido) like '%".strtoupper($v)."%')";
									}
					}
			}

			if(!empty($prefijo))
			{  $filtroPrefijo = "and c.historia_prefijo='$prefijo'";  }


			if(!empty($historia))
			{  $filtroHC = "and c.historia_numero=".$historia."";  }

			list($dbconn) = GetDBconn();
			$query = "SELECT a.*, d.descripcion,
								b.tipo_id_paciente, b.paciente_id,
								b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre
								FROM triages as a, pacientes as b, historias_clinicas as c, puntos_admisiones as d
								WHERE a.sw_estado in (1,4) AND a.empresa_id='$empresa'
								and a.sw_no_atender=0
								and a.punto_admision_id=d.punto_admision_id
								and a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id
								and a.tipo_id_paciente=c.tipo_id_paciente and a.paciente_id=c.paciente_id
								$filtroHC $filtroPrefijo $filtroNombres $filtroDocumento $filtroTipoDocumento
								$filtroCU
								ORDER BY a.hora_llegada desc";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{
					while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					//$var[]=$result->GetRowAssoc($ToUpper = false);
					//$result->MoveNext();
			}

			$result->Close();
			return $var;
	}


	/**
	*
	*/
	function BuscarPacienteEstacion($empresa,$tipo_id_paciente='',$paciente_id='',$prefijo='',$historia='',$nombres='',$cu='',$swcu='')
	{
			$filtroNombres='';
			$filtroDocumento='';
			$filtroHC='';
			$filtroPrefijo='';

			if(!empty($swcu))
			{   $filtroCU=" AND a.centro_utilidad='$cu'";  }

			if(!empty($tipo_id_paciente))
			{   $filtroTipoDocumento=" AND b.tipo_id_paciente = '$tipo_id_paciente'";   }

			if ($paciente_id != '')
			{   $filtroDocumento =" AND b.paciente_id = '$paciente_id'";   }

			if ($nombres != '')
			{
					$a=explode(' ',$nombres);
					foreach($a as $k=>$v)
					{
							if(!empty($v))
									{
											$filtroNombres =" and (upper(b.primer_nombre||' '||b.segundo_nombre||' '||
																						b.primer_apellido||' '||b.segundo_apellido) like '%".strtoupper($v)."%')";
									}
					}
			}

			if(!empty($prefijo))
			{  $filtroPrefijo = "and c.historia_prefijo='$prefijo'";  }


			if(!empty($historia))
			{  $filtroHC = "and c.historia_numero=".$historia."";  }

			list($dbconn) = GetDBconn();
			$query = "SELECT  c.historia_prefijo, c.historia_numero, e.descripcion,
								b.tipo_id_paciente, b.paciente_id, e.descripcion, g.cama,
								b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
								h.pieza, h.ubicacion, f.ingreso, i.egreso_dpto_id, i.evolucion_id
								FROM cuentas as a, pacientes as b, historias_clinicas as c,
								ingresos_departamento as d left join egresos_departamento as i
								on(d.ingreso_dpto_id=i.ingreso_dpto_id and i.tipo_egreso='3'
								and i.estado='0'),
								estaciones_enfermeria as e, ingresos as f,
								movimientos_habitacion as g, camas as h
								WHERE a.estado!='0' AND a.empresa_id='$empresa'
								and a.ingreso=f.ingreso and f.tipo_id_paciente=b.tipo_id_paciente
								and f.paciente_id=b.paciente_id and c.tipo_id_paciente=b.tipo_id_paciente
								and c.paciente_id=b.paciente_id and d.numerodecuenta=a.numerodecuenta
								and d.estacion_id=e.estacion_id
								and d.ingreso_dpto_id=g.ingreso_dpto_id
								and g.fecha_egreso is NULL
								and g.cama=h.cama
								$filtroHC $filtroPrefijo $filtroNombres $filtroDocumento $filtroTipoDocumento
								$filtroCU
								ORDER BY d.fecha_ingreso desc";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{
					while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					//$var[]=$result->GetRowAssoc($ToUpper = false);
					//$result->MoveNext();
			}
			$result->Close();
			return $var;
	}

	/**
	*
	*/
	function BuscarPacientePteSalidaEstacion($empresa,$tipo_id_paciente='',$paciente_id='',$prefijo='',$historia='',$nombres='',$cu='',$swcu='')
	{
			$filtroNombres='';
			$filtroDocumento='';
			$filtroHC='';
			$filtroPrefijo='';

			if(!empty($swcu))
			{   $filtroCU=" AND a.centro_utilidad='$cu'";  }

			if(!empty($tipo_id_paciente))
			{   $filtroTipoDocumento=" AND b.tipo_id_paciente = '$tipo_id_paciente'";   }

			if ($paciente_id != '')
			{   $filtroDocumento =" AND b.paciente_id LIKE '$paciente_id%'";   }

			if ($nombres != '')
			{
					$a=explode(' ',$nombres);
					foreach($a as $k=>$v)
					{
							if(!empty($v))
									{
											$filtroNombres =" and (upper(b.primer_nombre||' '||b.segundo_nombre||' '||
																						b.primer_apellido||' '||b.segundo_apellido) like '%".strtoupper($v)."%')";
									}
					}
			}

			if(!empty($prefijo))
			{  $filtroPrefijo = "and c.historia_prefijo='$prefijo'";  }


			if(!empty($historia))
			{  $filtroHC = "and c.historia_numero=".$historia."";  }

			list($dbconn) = GetDBconn();
			$query = "SELECT  c.historia_prefijo, c.historia_numero, e.descripcion,
								b.tipo_id_paciente, b.paciente_id, e.descripcion, g.cama,
								b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
								h.pieza, h.ubicacion, f.ingreso, i.egreso_dpto_id
								FROM cuentas as a, pacientes as b, historias_clinicas as c,
								ingresos_departamento as d, estaciones_enfermeria as e, ingresos as f,
								movimientos_habitacion as g, camas as h, egresos_departamento as i
								WHERE a.estado!='0' AND a.empresa_id='$empresa'
								and a.ingreso=f.ingreso and f.tipo_id_paciente=b.tipo_id_paciente
								and f.paciente_id=b.paciente_id and c.tipo_id_paciente=b.tipo_id_paciente
								and c.paciente_id=b.paciente_id and d.numerodecuenta=a.numerodecuenta
								and d.estacion_id=e.estacion_id
								and d.ingreso_dpto_id=g.ingreso_dpto_id
								and g.fecha_egreso is NOT NULL
								and g.cama=h.cama
								and d.ingreso_dpto_id=i.ingreso_dpto_id and i.tipo_egreso='3'
								$filtroHC $filtroPrefijo $filtroNombres $filtroDocumento $filtroTipoDocumento
								$filtroCU
								ORDER BY d.fecha_ingreso desc";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{
					while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					//$var[]=$result->GetRowAssoc($ToUpper = false);
					//$result->MoveNext();
			}
			$result->Close();
			return $var;
	}


	/**
	*
	*/
	function BuscarPacientePteAtencion($empresa,$tipo_id_paciente='',$paciente_id='',$prefijo='',$historia='',$nombres='',$cu='',$swcu='')
	{
			$filtroNombres='';
			$filtroDocumento='';
			$filtroHC='';
			$filtroPrefijo='';

			if(!empty($swcu))
			{   $filtroCU=" AND a.centro_utilidad='$cu'";  }


			if(!empty($tipo_id_paciente))
			{   $filtroTipoDocumento=" AND b.tipo_id_paciente = '$tipo_id_paciente'";   }

			if ($paciente_id != '')
			{   $filtroDocumento =" AND b.paciente_id = '$paciente_id'";   }

			if ($nombres != '')
			{
					$a=explode(' ',$nombres);
					foreach($a as $k=>$v)
					{
							if(!empty($v))
									{
											$filtroNombres =" and (upper(b.primer_nombre||' '||b.segundo_nombre||' '||
																						b.primer_apellido||' '||b.segundo_apellido) like '%".strtoupper($v)."%')";
									}
					}
			}

			if(!empty($prefijo))
			{  $filtroPrefijo = "and c.historia_prefijo='$prefijo'";  }


			if(!empty($historia))
			{  $filtroHC = "and c.historia_numero=".$historia."";  }

			list($dbconn) = GetDBconn();
			$query = "SELECT b.tipo_id_paciente,b.paciente_id,c.historia_numero, c.historia_prefijo,
								b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
								f.ingreso, a.triage_id, e.descripcion
								FROM pacientes_urgencias as a, pacientes as b, historias_clinicas as c, ingresos as f,
								estaciones_enfermeria as e
								WHERE a.sw_estado='1'
								and a.ingreso=f.ingreso
								and f.tipo_id_paciente=b.tipo_id_paciente and f.paciente_id=b.paciente_id
								and c.tipo_id_paciente=b.tipo_id_paciente and c.paciente_id=b.paciente_id
								and e.estacion_id=a.estacion_id
								$filtroHC $filtroPrefijo $filtroNombres $filtroDocumento $filtroTipoDocumento
								ORDER BY b.tipo_id_paciente,b.paciente_id,f.fecha_ingreso desc";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{
					while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					//$var[]=$result->GetRowAssoc($ToUpper = false);
					//$result->MoveNext();
			}

			$result->Close();
			return $var;
	}


	/**
	*
	*/
	function BuscarPacienteRemitido($empresa,$tipo_id_paciente='',$paciente_id='',$prefijo='',$historia='',$nombres='',$cu='',$swcu='')
	{
			$filtroNombres='';
			$filtroDocumento='';
			$filtroHC='';
			$filtroPrefijo='';

			if(!empty($swcu))
			{   $filtroCU=" AND a.centro_utilidad='$cu'";  }

			if(!empty($tipo_id_paciente))
			{   $filtroTipoDocumento=" AND a.tipo_id_paciente = '$tipo_id_paciente'";   }

			if ($paciente_id != '')
			{   $filtroDocumento =" AND a.paciente_id LIKE '$paciente_id%'";   }

			if ($nombres != '')
			{
					$a=explode(' ',$nombres);
					foreach($a as $k=>$v)
					{
							if(!empty($v))
									{
											$filtroNombres =" and (upper(b.primer_nombre||' '||b.segundo_nombre||' '||
																						b.primer_apellido||' '||b.segundo_apellido) like '%".strtoupper($v)."%')";
									}
					}
			}

			if(!empty($prefijo))
			{  $filtroPrefijo = "and c.historia_prefijo='$prefijo'";  }
				//en la tabla remisiones_pacientes ahce falta la empresa
				//a.empresa_id='$empresa'
			if(!empty($historia))
			{  $filtroHC = "and c.historia_numero=".$historia."";  }

			list($dbconn) = GetDBconn();
			$query = "SELECT b.tipo_id_paciente,b.paciente_id, a.triage_id,
								b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
								c.historia_numero, c.historia_prefijo
								FROM remisiones_pacientes as a, pacientes as b, historias_clinicas as c
								WHERE a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id
								and c.tipo_id_paciente=b.tipo_id_paciente and c.paciente_id=b.paciente_id
								$filtroHC $filtroPrefijo $filtroNombres $filtroDocumento $filtroTipoDocumento
								ORDER BY a.fecha_registro desc
								LIMIT 5 OFFSET 0";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}

			$result->Close();
			return $var;
	}


	/**
	*
	*/
	function BuscarPacientePteIngresar($empresa,$tipo_id_paciente='',$paciente_id='',$prefijo='',$historia='',$nombres='',$cu='',$swcu='')
	{
			$filtroNombres='';
			$filtroDocumento='';
			$filtroHC='';
			$filtroPrefijo='';

			if(!empty($swcu))
			{   $filtroCU=" AND a.centro_utilidad='$cu'";  }

			if(!empty($tipo_id_paciente))
			{   $filtroTipoDocumento=" AND b.tipo_id_paciente = '$tipo_id_paciente'";   }

			if ($paciente_id != '')
			{   $filtroDocumento =" AND b.paciente_id = '$paciente_id'";   }

			if ($nombres != '')
			{
					$a=explode(' ',$nombres);
					foreach($a as $k=>$v)
					{
							if(!empty($v))
									{
											$filtroNombres =" and (upper(b.primer_nombre||' '||b.segundo_nombre||' '||
																						b.primer_apellido||' '||b.segundo_apellido) like '%".strtoupper($v)."%')";
									}
					}
			}

			if(!empty($prefijo))
			{  $filtroPrefijo = "and c.historia_prefijo='$prefijo'";  }

			if(!empty($historia))
			{  $filtroHC = "and c.historia_numero=".$historia."";  }

			//pendientes_x_hospitalizar
			list($dbconn) = GetDBconn();
			$query = "SELECT  c.historia_prefijo, c.historia_numero, f.ingreso,
								b.tipo_id_paciente, b.paciente_id, e.descripcion,
								b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre
								FROM pendientes_x_hospitalizar as a, pacientes as b, historias_clinicas as c,
								estaciones_enfermeria as e, ingresos as f
								WHERE a.ingreso=f.ingreso and f.tipo_id_paciente=b.tipo_id_paciente
								and f.paciente_id=b.paciente_id and c.tipo_id_paciente=b.tipo_id_paciente
								and c.paciente_id=b.paciente_id
								and a.estacion_destino=e.estacion_id
								$filtroHC $filtroPrefijo $filtroNombres $filtroDocumento $filtroTipoDocumento
								ORDER BY f.fecha_ingreso desc";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{
					while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					//$var[]=$result->GetRowAssoc($ToUpper = false);
					//$result->MoveNext();
			}

			$result->Close();
			return $var;
	}

	/**
	*
	*/
	function BuscarPteRemisionMedica($empresa,$tipo_id_paciente='',$paciente_id='',$prefijo='',$historia='',$nombres='',$cu='',$swcu='')
	{
			$filtroNombres='';
			$filtroDocumento='';
			$filtroHC='';
			$filtroPrefijo='';

			if(!empty($swcu))
			{   $filtroCU=" AND c.centro_utilidad='$cu'";  }


			if(!empty($tipo_id_paciente))
			{   $filtroTipoDocumento=" AND b.tipo_id_paciente = '$tipo_id_paciente'";   }

			if ($paciente_id != '')
			{   $filtroDocumento =" AND b.paciente_id = '$paciente_id'";   }

			if ($nombres != '')
			{
					$a=explode(' ',$nombres);
					foreach($a as $k=>$v)
					{
							if(!empty($v))
									{
											$filtroNombres =" and (upper(b.primer_nombre||' '||b.segundo_nombre||' '||
																						b.primer_apellido||' '||b.segundo_apellido) like '%".strtoupper($v)."%')";
									}
					}
			}

			list($dbconn) = GetDBconn();
			$query = "SELECT
								b.tipo_id_paciente, b.paciente_id,
								b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre
								FROM triage_no_atencion as a, pacientes as b, triages as c
								WHERE a.triage_id=c.triage_id and c.nivel_triage_id='0'
								AND c.empresa_id='$empresa'
								and c.tipo_id_paciente=b.tipo_id_paciente and c.paciente_id=b.paciente_id
								$filtroHC $filtroPrefijo $filtroNombres $filtroDocumento $filtroTipoDocumento
								$filtroCU and c.sw_estado !='9'
								ORDER BY c.hora_llegada desc";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{
					while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					//$var[]=$result->GetRowAssoc($ToUpper = false);
					//$result->MoveNext();
			}

			$result->Close();
			return $var;
	}


	/**
	*
	*/
	function BuscarPteClasificacionMedica($empresa,$tipo_id_paciente='',$paciente_id='',$prefijo='',$historia='',$nombres='',$cu='',$swcu='')
	{
			$filtroNombres='';
			$filtroDocumento='';
			$filtroHC='';
			$filtroPrefijo='';

			if(!empty($swcu))
			{   $filtroCU=" AND a.centro_utilidad='$cu'";  }

			if(!empty($tipo_id_paciente))
			{   $filtroTipoDocumento=" AND a.tipo_id_paciente = '$tipo_id_paciente'";   }

			if ($paciente_id != '')
			{   $filtroDocumento =" AND a.paciente_id = '$paciente_id'";   }

			if ($nombres != '')
			{
					$a=explode(' ',$nombres);
					foreach($a as $k=>$v)
					{
							if(!empty($v))
									{
											$filtroNombres =" and (upper(b.primer_nombre||' '||b.segundo_nombre||' '||
																						b.primer_apellido||' '||b.segundo_apellido) like '%".strtoupper($v)."%')";
									}
					}
			}

			if(!empty($prefijo))
			{  $filtroPrefijo = "and c.historia_prefijo='$prefijo'";  }


			if(!empty($historia))
			{  $filtroHC = "and c.historia_numero=".$historia."";  }

			list($dbconn) = GetDBconn();
			$query = "SELECT a.triage_id,a.punto_triage_id,a.tipo_id_paciente,a.paciente_id,
								b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
								d.descripcion, a.hora_llegada, c.historia_numero, c.historia_prefijo, a.plan_id, a.punto_admision_id,
								e.descripcion as estacion
								FROM triages as a, puntos_triage as d, pacientes as b, historias_clinicas as c,
								triages_pendientes_admitir as f, estaciones_enfermeria as e
								WHERE a.empresa_id='$empresa'
								and a.sw_estado='5' and a.nivel_triage_asistencial is not null
								and a.nivel_triage_id=0
								and a.triage_id=f.triage_id and f.estacion_id=e.estacion_id
								and a.punto_triage_id=d.punto_triage_id
								and a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id
								and a.tipo_id_paciente=c.tipo_id_paciente and a.paciente_id=c.paciente_id
								$filtroHC $filtroPrefijo $filtroNombres $filtroDocumento $filtroTipoDocumento
								$filtroCU
								ORDER BY a.tipo_id_paciente,a.paciente_id,a.hora_llegada desc";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{
					while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					//$var[]=$result->GetRowAssoc($ToUpper = false);
					//$result->MoveNext();
			}

			$result->Close();
			return $var;
	}

//------------------------SALIDA DE PACIENTES-------------------------------------

	/**
	* Busca los pacientes que estan en la EE, y que estan pendientes por dar de
     * alta.
	*/
	function PacienteSalidaEstacion($empresa,$tipo_id_paciente='',$paciente_id='',$prefijo='',$historia='',$nombres='',$cu='',$swcu='',$limit='',$of='')
	{
			$filtroNombres='';
			$filtroDocumento='';
			$filtroHC='';
			$filtroPrefijo='';

			if(!empty($limit))
			{
                    $barra=" LIMIT $limit OFFSET $of";
			}

			if(!empty($swcu))
			{   $filtroCU=" AND a.centro_utilidad='$cu'";  }

			if(!empty($tipo_id_paciente))
			{   $filtroTipoDocumento=" AND b.tipo_id_paciente = '$tipo_id_paciente'";   }

			if ($paciente_id != '')
			{   $filtroDocumento =" AND b.paciente_id LIKE '$paciente_id%'";   }

			if ($nombres != '')
			{
					$a=explode(' ',$nombres);
					foreach($a as $k=>$v)
					{
							if(!empty($v))
									{
											$filtroNombres =" and (upper(b.primer_nombre||' '||b.segundo_nombre||' '||
																						b.primer_apellido||' '||b.segundo_apellido) like '%".strtoupper($v)."%')";
									}
					}
			}

			if(!empty($prefijo))
			{  $filtroPrefijo = "and c.historia_prefijo='$prefijo'";  }


			if(!empty($historia))
			{  $filtroHC = "and c.historia_numero=".$historia."";  }

			list($dbconn) = GetDBconn();
			$query = "SELECT DISTINCT mh.numerodecuenta, a.rango, a.plan_id, 
               				b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre, 
                                   b.tipo_id_paciente, b.paciente_id, c.historia_prefijo, 
                                   c.historia_numero, e.descripcion, e.descripcion, 
                                   f.ingreso, f.estado as estado_ingreso, 
                                   case when a.estado=1 then 'A' when a.estado=2 then 'I' when a.estado=3 then 'C' else '0' end as estado, 
                                   mh.fecha_egreso, a.fecha_registro
                              FROM 
                                   hc_vistosok_salida_detalle as vistos, 
                                   hc_ordenes_medicas as om 
                                   left join movimientos_habitacion as mh on (mh.ingreso=om.ingreso and mh.fecha_egreso IS NOT NULL and mh.movimiento_id = (SELECT max(movimiento_id) from movimientos_habitacion where ingreso = om.ingreso)), 
                                   cuentas as a, 
                                   pacientes as b, 
                                   historias_clinicas as c, 
                                   estaciones_enfermeria as e, 
                                   ingresos as f 
                                   
                              WHERE 
                                   a.empresa_id='$empresa'
                                   and om.hc_tipo_orden_medica_id in ('06','07','99')
                                   and om.sw_estado = '0'
                                   and om.ingreso = f.ingreso 
                                   and a.ingreso=f.ingreso 
                                   and vistos.ingreso=om.ingreso 
                                   and mh.estacion_id = e.estacion_id 
                                   and f.estado != '2' 
                                   and f.tipo_id_paciente=b.tipo_id_paciente 
                                   and f.paciente_id=b.paciente_id 
                                   and c.tipo_id_paciente=b.tipo_id_paciente 
                                   and c.paciente_id=b.paciente_id 
                                   $filtroHC $filtroPrefijo $filtroNombres $filtroDocumento $filtroTipoDocumento
                                   $filtroCU
                                   ORDER BY b.tipo_id_paciente,b.paciente_id desc $barra;";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{
					while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
			}
			$result->Close();
			return $var;
	}

	/**
	* Busca los pacientes que estan en la EE en Consulta de Urgencias, y que estan pendientes por dar de
     * alta.
	*/
	function PacienteSalidaUrgencias($empresa,$tipo_id_paciente='',$paciente_id='',$prefijo='',$historia='',$nombres='',$cu='',$swcu='',$limit='',$of='')
	{
			$filtroNombres='';
			$filtroDocumento='';
			$filtroHC='';
			$filtroPrefijo='';

			if(!empty($limit))
			{
					$barra=" LIMIT $limit OFFSET $of";
			}

			if(!empty($swcu))
			{   $filtroCU=" AND g.centro_utilidad='$cu'";  }


			if(!empty($tipo_id_paciente))
			{   $filtroTipoDocumento=" AND b.tipo_id_paciente = '$tipo_id_paciente'";   }

			if ($paciente_id != '')
			{   $filtroDocumento =" AND b.paciente_id = '$paciente_id'";   }

			if ($nombres != '')
			{
					$a=explode(' ',$nombres);
					foreach($a as $k=>$v)
					{
							if(!empty($v))
									{
											$filtroNombres =" and (upper(b.primer_nombre||' '||b.segundo_nombre||' '||
																						b.primer_apellido||' '||b.segundo_apellido) like '%".strtoupper($v)."%')";
									}
					}
			}

			if(!empty($prefijo))
			{  $filtroPrefijo = "and c.historia_prefijo='$prefijo'";  }


			if(!empty($historia))
			{  $filtroHC = "and c.historia_numero=".$historia."";  }

			list($dbconn) = GetDBconn();
			$query = "SELECT DISTINCT b.tipo_id_paciente,b.paciente_id,
               					c.historia_numero, c.historia_prefijo, 
                                        b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre, 
                                        f.ingreso, a.triage_id, e.descripcion, g.numerodecuenta, 
                                        a.historia_clinica_tipo_cierre_id, g.plan_id, 
                                        g.rango, g.fecha_registro, f.estado as estado_ingreso, 
                                        case when g.estado='1' then 'A' when g.estado='2' then 'I' when g.estado='3' then 'C' else '0' end as estado,
                                        f.fecha_ingreso 
                              FROM 
                                   hc_vistosok_salida_detalle as vistos, 
                                   hc_ordenes_medicas as om 
                                   left join pacientes_urgencias as a on (a.ingreso=om.ingreso), 
                                   pacientes as b, 
                                   historias_clinicas as c, 
                                   ingresos as f, 
                                   estaciones_enfermeria as e, 
                                   cuentas as g 
                                   
                              WHERE 
                                   f.ingreso=om.ingreso 
                                   and om.hc_tipo_orden_medica_id in ('06','07','99')
                                   and om.sw_estado = '0'
                                   and a.sw_estado in('4','5','6') 
                                   and a.ingreso=f.ingreso 
                                   and vistos.ingreso=om.ingreso 
                                   and f.estado != '2' 
                                   and f.tipo_id_paciente=b.tipo_id_paciente 
                                   and f.paciente_id=b.paciente_id 
                                   and c.tipo_id_paciente=b.tipo_id_paciente 
                                   and c.paciente_id=b.paciente_id 
                                   and e.estacion_id=a.estacion_id 
                                   and f.ingreso=g.ingreso 
                                   and g.empresa_id='$empresa'
                                   $filtroHC $filtroPrefijo $filtroNombres $filtroDocumento $filtroTipoDocumento
                                   $filtroCU
							ORDER BY b.tipo_id_paciente,b.paciente_id,f.fecha_ingreso desc;";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{
					while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					//$var[]=$result->GetRowAssoc($ToUpper = false);
					//$result->MoveNext();
			}

			$result->Close();
			return $var;
	}

     /**
	* Busca los pacientes que estan en la EE de Cirugia, y que estan pendientes por dar de
     * alta.
	*/
	function PacienteSalidaCirugia($empresa,$tipo_id_paciente='',$paciente_id='',$prefijo='',$historia='',$nombres='',$cu='',$swcu='',$limit='',$of='')
	{
          $filtroNombres='';
          $filtroDocumento='';
          $filtroHC='';
          $filtroPrefijo='';

          if(!empty($limit))
          {
               $barra=" LIMIT $limit OFFSET $of";
          }

          if(!empty($swcu))
          {   $filtroCU=" AND C.centro_utilidad='$cu'";  }

          if(!empty($tipo_id_paciente))
          {   $filtroTipoDocumento=" AND P.tipo_id_paciente = '$tipo_id_paciente'";   }

          if ($paciente_id != '')
          {   $filtroDocumento =" AND P.paciente_id LIKE '$paciente_id%'";   }

          if ($nombres != '')
          {
               $a=explode(' ',$nombres);
               foreach($a as $k=>$v)
               {
                    if(!empty($v))
                    {
                              $filtroNombres =" AND (upper(P.primer_nombre||' '||P.segundo_nombre||' '||
                                                           P.primer_apellido||' '||P.segundo_apellido) like '%".strtoupper($v)."%')";
                    }
               }
          }

          if(!empty($prefijo))
          {  $filtroPrefijo = "AND HC.historia_prefijo='$prefijo'";  }


          if(!empty($historia))
          {  $filtroHC = "AND HC.historia_numero=".$historia."";  }

          list($dbconn) = GetDBconn();
          $query = "SELECT    tabla.*,
                              P.primer_nombre||' '||P.segundo_nombre||' '||P.primer_apellido||' '||P.segundo_apellido as nombre,
                              P.tipo_id_paciente, P.paciente_id, 
                              HC.historia_prefijo, HC.historia_numero, 
                              E.descripcion
                         
                     FROM      
                              (SELECT DISTINCT 
                                   EC.numerodecuenta, 
                                   EC.fecha_egreso,
                                   EC.departamento,
                                   C.rango, C.plan_id, 
                                   C.fecha_registro,
                                   CASE WHEN C.estado = 1 THEN 'A' 
                                        WHEN C.estado = 2 THEN 'I' 
                                        WHEN C.estado = 3 THEN 'C' ELSE '0' END AS estado,
                                   I.ingreso, I.estado as estado_ingreso,
                                   I.paciente_id, I.tipo_id_paciente
                              
                              FROM
                                   estacion_enfermeria_qx_pacientes_ingresados AS EC,
                                   cuentas AS C,
                                   ingresos AS I
                                   
                              WHERE EC.sw_estado = '2'
                              AND   EC.fecha_egreso IS NOT NULL
                              AND   C.empresa_id='$empresa'
                              $filtroCU
                              AND   EC.numerodecuenta = C.numerodecuenta
                              AND   C.ingreso = I.ingreso     
                              AND   I.estado != '2') AS tabla,
                              
                              hc_ordenes_medicas AS OM,
                              pacientes AS P,
                              historias_clinicas AS HC,
                              hc_vistosok_salida_detalle AS vistos,
                              departamentos AS E
                              
                    WHERE P.paciente_id = tabla.paciente_id
                    AND   P.tipo_id_paciente = tabla.tipo_id_paciente
                    $filtroNombres $filtroDocumento $filtroTipoDocumento
                    AND   HC.paciente_id = tabla.paciente_id
                    AND   HC.tipo_id_paciente = tabla.tipo_id_paciente
                    $filtroHC $filtroPrefijo
                    AND   E.departamento = tabla.departamento
                    AND   OM.ingreso = tabla.ingreso 
                    AND   OM.hc_tipo_orden_medica_id = '99' 
                    AND   OM.sw_estado = '0'
                    AND   vistos.ingreso = tabla.ingreso
                    ORDER BY P.tipo_id_paciente, P.paciente_id DESC $barra;";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Tabal autorizaiones";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          if(!$result->EOF)
          {
               while (!$result->EOF) {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          $result->Close();
          return $var;
	}


//-------------------------------------------------------------------------------

	/**
	*
	*/
	function BuscarEstacionesPuntosAdmisiones($PtoAdmon)
	{
					list($dbconn) = GetDBconn();
					$query = "SELECT a.estacion_id, b.descripcion, b.departamento
										FROM puntos_admisiones_estaciones as a, estaciones_enfermeria as b
										WHERE a.punto_admision_id='$PtoAdmon' AND a.estacion_id=b.estacion_id";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					while(!$result->EOF)
					{
									$var[]=$result->GetRowAssoc($ToUpper = false);
									$result->MoveNext();
					}
					$result->Close();
					return $var;
	}

     /**
     *
     */
     function BuscarProtocolo($Plan)
     {
						list($dbconn) = GetDBconn();
						$query = "SELECT protocolos FROM planes WHERE plan_id='$Plan'";
						$results = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}

						$var=$results->GetRowAssoc($ToUpper = false);
						return $var;
     }


    /**
  	* Busca los diferentes tipos de vias de ingreso
    * @access public
    * @return array
    */
		function ViasIngreso()
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT * FROM vias_ingreso order by via_ingreso_id";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				else
				{
						if($result->EOF){
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "La tabla maestra 'vias_ingreso' esta vacia ";
								return false;
						}
						while (!$result->EOF)
						{
								$vars[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
						}
				}
				$result->Close();
				return $vars;
		}

		/**
		* Busca los diferentes tipos de afiliados
		* @access public
		* @return array
		*/
		function TiposAfiliado($plan)
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id
									FROM tipos_afiliado as a, planes_rangos as b
									WHERE b.plan_id=$plan and b.tipo_afiliado_id=a.tipo_afiliado_id";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while(!$resulta->EOF)
				{
								$vars[]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
				}
				$resulta->Close();
				return $vars;
		}

    /**
    * Busca los niveles del plan del responsable del paciente
    * @access public
    * @return array
    * @param string plan_id
    */
     function Niveles($plan)
     {
					list($dbconn) = GetDBconn();
					$query="SELECT DISTINCT rango
									FROM planes_rangos
									WHERE plan_id=$plan";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					while(!$result->EOF){
							$niveles[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					return $niveles;
     }

		/**
		* Busca el tipo de is del tercero y la descripcion
		* @access public
		* @return array
		*/
		function TipoIdTerceros()
		{
					list($dbconn) = GetDBconn();
					$query = "SELECT tipo_id_tercero,descripcion FROM tipo_id_terceros ORDER BY indice_de_orden";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					else
					{
							if($result->EOF){
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
									return false;
							}

							while (!$result->EOF) {
									$vars[]=$result->GetRowAssoc($ToUpper = false);
									$result->MoveNext();
							}
					}
					$result->Close();
					return $vars;
		}

	/**
	*
	*/
	function DatosTriage($triage)
	{							
				list($dbconn) = GetDBconn();
				$query = "SELECT distinct a.empresa_id,a.nivel_triage_id, a.motivo_consulta, a.observacion_medico,
									a.punto_admision_id, a.tipo_id_paciente, a.paciente_id, b.descripcion,
									c.fecha_nacimiento, c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido,
									a.impresion_diagnostica, a.hora_llegada, f.nombre_tercero as nombre,
									e.tipo_tercero_id, e.tercero_id, g.tarjeta_profesional,
									h.descripcion as tipo_profesional, j.descripcion as especialidad
									FROM triages as a, profesionales_usuarios as e									
									LEFT JOIN profesionales_especialidades AS i
									ON(e.tipo_tercero_id=i.tipo_id_tercero AND e.tercero_id=i.tercero_id)
									LEFT JOIN especialidades AS j ON(j.especialidad = i.especialidad),
									terceros f, profesionales g, tipos_profesionales h, 									
									departamentos as b, pacientes as c
									WHERE a.triage_id=$triage and a.departamento=b.departamento
									and a.paciente_id=c.paciente_id and a.tipo_id_paciente=c.tipo_id_paciente
									and a.usuario_clasificacion=e.usuario_id
									AND f.tipo_id_tercero = e.tipo_tercero_id 
									AND f.tercero_id = e.tercero_id 
									AND g.tipo_id_tercero = e.tipo_tercero_id
									AND g.tercero_id = e.tercero_id
									AND h.tipo_profesional = g.tipo_profesional ";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				$vars=$result->GetRowAssoc($ToUpper = false);
				$result->Close();
				return $vars;
	}

	/**
	* Elige el color del nivel del triage segun el nivel
	* @access private
	* @return boolean
	* @param int nivel del triage
	*/
	function ColorTriage($nivel){

				if($nivel==1){$estilo='nivel1_oscuro';}
				elseif($nivel=='2'){ $estilo='nivel2_oscuro'; }
				elseif($nivel=='3'){$estilo='nivel3_oscuro'; }
				elseif($nivel=='4'){ $estilo='nivel4_oscuro'; }

				return $estilo;
	}

	/**
	* Elige el color de las causas del triage segun el nivel
	* @access private
	* @return boolean
	* @param int nivel del triage
	*/
	function ColorTriageClaro($nivel){

				if($nivel==1){$estiloClaro='nivel1_claro';}
				elseif($nivel=='2'){ $estiloClaro='nivel2_claro'; }
				elseif($nivel=='3'){$estiloClaro='nivel3_claro'; }
				elseif($nivel=='4'){ $estiloClaro='nivel4_claro'; }

				return $estiloClaro;

	}

		/**
		*
		*/
		function BuscarCausas($triage)
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT a.*, b.descripcion
									FROM chequeo_triages as a, causas_probables as b
									WHERE a.triage_id=".$triage."
									AND a.causa_probable_id=b.causa_probable_id";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while (!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}

				$result->Close();
				return $vars;
		}


    /*Busca los signos vitales de un paciente
    * @access public
    * @return array
    * @param string tipo documento
    * @param int numero documento
    */
		function BuscarSignosVitalesTriage($triage)
		{
					list($dbconn) = GetDBconn();
					$query = "SELECT * FROM signos_vitales_triages
										WHERE triage_id=".$triage."";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}

					$vars=$result->GetRowAssoc($ToUpper = false);
					$result->Close();
					return $vars;
		}

		/**
		*
		*/
		function BuscarDiagnosticoTriage($triage)
		{
				list($dbconn) = GetDBconn();
				$query = " SELECT a.diagnostico_id, b.diagnostico_nombre
									FROM triages_diagnosticos as a, diagnosticos as b
									WHERE a.triage_id=$triage and a.diagnostico_id=b.diagnostico_id";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				while (!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}

				$result->Close();
				return $vars;
		}

		/**
		*
		*/
    function BuscarTodasEstaciones()
    {
				list($dbconn) = GetDBconn();
				$query = "SELECT * FROM estaciones_enfermeria as b order by descripcion";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while(!$result->EOF)
				{
								$var[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
				}
				$result->Close();
				return $var;
    }

	function BuscarRemisionPaciente($ingreso)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT evolucion_id FROM hc_conducta_remision
								WHERE ingreso=$ingreso";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{  $var=$result->GetRowAssoc($ToUpper = false);  }

			$result->Close();
			return $var;
	}

	/**
	*
	*/
	function DatosRemision($ingreso)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.traslado_ambulancia, a.observaciones, a.descripcion_otro_motivo,
								a.nivel_centro_remision, a.tipo_remision, b.descripcion, a.sw_remision,
								d.descripcion as motivo, e.centro_remision, f.descripcion as centro,
								f.direccion, f.telefono, f.nivel, a.ingreso, a.evolucion_id, a.observacion_remision
								FROM hc_conducta_remision as a
								left join hc_conducta_remision_motivos as c  on (a.ingreso=c.ingreso and a.evolucion_id=c.evolucion_id)
								left join hc_motivos_remision as d on(c.motivo_remision_id=d.motivo_remision_id)
								left join hc_conducta_remision_centros as e on(a.ingreso=e.ingreso and a.evolucion_id=e.evolucion_id)
								left join centros_remision as f on(e.centro_remision=f.centro_remision),
								hc_tipo_remision as b
								WHERE a.ingreso=$ingreso and a.tipo_remision=b.tipo_remision_id";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{
					while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
			}

			$result->Close();
			return $var;
	}

		/*
		*
		*/
		function DatosImpresionRemision($triage)
		{
				list($dbconn) = GetDBconn();
				//datos principales
				$query = "SELECT a.remision_paciente_id, a.tipo_id_paciente, a.paciente_id,
									a.motivo_consulta, a.observacion_medico, a.observacion_remision, a.fecha_registro, d.*,
									b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
									e.razon_social, c.nivel_triage_id, f.nombre as medico, g.descripcion,
									b.fecha_nacimiento
									FROM remisiones_pacientes as a, signos_vitales_triages as d,
									pacientes as b, triages as c, empresas as e, system_usuarios as f,
									departamentos as g
									WHERE a.triage_id=$triage and d.triage_id=$triage
									and a.tipo_id_paciente=b.tipo_id_paciente
									and a.paciente_id=b.paciente_id
									and c.triage_id=$triage and c.empresa_id=e.empresa_id
									and a.usuario_id=f.usuario_id and c.departamento=g.departamento";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

				$arr[]=$result->GetRowAssoc($ToUpper = false);
				$result->Close();

				//diagnosticos (del de arriba tomamos remision_paciente_id)
				$query = "SELECT a.diagnostico_id, b.diagnostico_nombre
									FROM remisiones_pacientes_diagnosticos as a, diagnosticos as b
									WHERE a.remision_paciente_id=".$arr[0][remision_paciente_id]."
									and a.diagnostico_id=b.diagnostico_id";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$result->EOF){
						$var[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
				$result->Close();
				$arr[]=$var;

				//centros (del primero tomamos remision_paciente_id)
				$query = "SELECT a.centro_remision, b.descripcion, b.nivel, b.direccion, b.telefono
									FROM remisiones_pacientes_centros as a, centros_remision as b
									WHERE a.remision_paciente_id=".$arr[0][remision_paciente_id]."
									and a.centro_remision=b.centro_remision";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$result->EOF){
						$cen[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
				$result->Close();

				$arr[]=$cen;

				//causas probables (del primero tomamos remision_paciente_id)
				$query = "SELECT a.*, b.descripcion
									FROM chequeo_triages as a, causas_probables as b
									WHERE a.triage_id=$triage
									AND a.causa_probable_id=b.causa_probable_id";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while (!$result->EOF)
				{
					$cau[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}

				$arr[]=$cau;
				return $arr;
		}

		/**
		* Busca los niveles del triage
		* @access public
		* @return array
		*/
		function NivelesTriage()
		{
					list($dbconn) = GetDBconn();
					$query = "SELECT * FROM niveles_triages WHERE nivel_triage_id!='0' ORDER BY indice_de_orden";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}

					while (!$result->EOF) {
							$vars[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}

					$result->Close();
					return $vars;
		}


	function BuscarConsultoriosEstacion($estacion)
	{
				list($dbconn) = GetDBconn();
				$query = "SELECT paciente_urgencia_consultorio_id,estacion_id,
									descripcion, estado, descripcion2
									FROM pacientes_urgencias_consultorio
									WHERE estacion_id='$estacion' and estado='1'";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				/*if($result->EOF)
				{  return 1;}*/

				while (!$result->EOF) {
						$vars[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}

				$result->Close();
				return $vars;
	}

	function CantidadPacientesUrgenciasConsultorios($consultorio,$estacion)
	{
				list($dbconn) = GetDBconn();
				$query="SELECT count(a.ingreso)
								FROM pacientes_urgencias as a
								join ingresos as b  on (a.ingreso=b.ingreso and b.estado='1'
								and a.estacion_id='$estacion')
								left join triages as d on (a.triage_id=d.triage_id and d.sw_estado!='9')
								WHERE a.sw_estado='1' and a.paciente_urgencia_consultorio_id=$consultorio;";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				$result->Close();
				return $result->fields[0];
	}

	/**
	*
	*/
	function BuscarSignosObligatorios()
	{
				list($dbconn) = GetDBconn();
				$query = "SELECT signo,sw_mostrar,sw_obligatorio, sw_cero
									FROM triage_signos_vitales_obligatorios";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				while (!$result->EOF) {
						$vars[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}

				$result->Close();
				return $vars;
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

					return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
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
		$x=explode('.',$time[3]);
		return  $time[1].":".$time[2];
	}

	/**
	*
	*/
	function BuscarDatosPacienteIngreso($ingreso)
	{
					list($dbconn) = GetDBconn();
					$query = "SELECT b.tipo_id_paciente, b.paciente_id,
										b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre
										FROM ingresos as a, pacientes as b
										WHERE a.ingreso=$ingreso
										and a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id";
					$results = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}

					$var=$results->GetRowAssoc($ToUpper = false);
					return $var;
	}


	/**
	*
	*/
	function PacienteSalidaUrgenciasProcesoEst($empresa,$tipo_id_paciente='',$paciente_id='',$cu='',$swcu='')
	{
			$filtroNombres='';
			$filtroDocumento='';
			$filtroHC='';
			$filtroPrefijo='';

			if(!empty($swcu))
			{   $filtroCU=" AND g.centro_utilidad='$cu'";  }

			if(!empty($tipo_id_paciente))
			{   $filtroTipoDocumento=" AND b.tipo_id_paciente = '$tipo_id_paciente'";   }

			if ($paciente_id != '')
			{   $filtroDocumento =" AND b.paciente_id = '$paciente_id'";   }


			list($dbconn) = GetDBconn();
			$query = "SELECT b.tipo_id_paciente,b.paciente_id,
								b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
								f.ingreso, a.triage_id, e.descripcion, g.numerodecuenta, a.historia_clinica_tipo_cierre_id,
								g.plan_id, g.rango, g.fecha_registro, f.estado as estado_ingreso,
								case when g.estado=1 then 'A' when g.estado=2 then 'I' when g.estado=3 then 'C' else '0' end as estado

								FROM pacientes_urgencias as a, pacientes as b, ingresos as f,
								estaciones_enfermeria as e, cuentas as g

								WHERE a.sw_estado in('7')
								and a.ingreso=f.ingreso
								and f.tipo_id_paciente=b.tipo_id_paciente and f.paciente_id=b.paciente_id
								and e.estacion_id=a.estacion_id
								and f.ingreso=g.ingreso
								and g.empresa_id='$empresa'
								$filtroHC $filtroPrefijo $filtroNombres $filtroDocumento $filtroTipoDocumento
								$filtroCU
								ORDER BY b.tipo_id_paciente,b.paciente_id,f.fecha_ingreso desc";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{
					while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					//$var[]=$result->GetRowAssoc($ToUpper = false);
					//$result->MoveNext();
			}

			$result->Close();
			return $var;
	}


    /**
    * Busca el tercero_id y el plan_descripcion de la table planes.
    * @access public
    * @return array
    * @param string id del plan
    * @param int ingreso
    */
   function BuscarPlanes($PlanId,$Ingreso)
     {
                list($dbconn) = GetDBconn();
                $query = "SELECT sw_tipo_plan FROM planes WHERE plan_id='$PlanId'";
                $results = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $sw=$results->fields[0];
                //soat
                if($sw==1)
                {
                     $query = "SELECT  b.nombre_tercero, c.plan_descripcion, e.tipo_id_tercero, e.tercero_id, c.protocolos
                                                FROM ingresos_soat as a, terceros as b, planes as c,
                                                soat_eventos as d, soat_polizas as e
                                                WHERE a.ingreso=$Ingreso AND a.evento=d.evento AND e.tipo_id_tercero=b.tipo_id_tercero
                                                AND e.tercero_id =b.tercero_id AND c.plan_id='$PlanId' AND d.poliza=e.poliza";

                }
                //cliente
                if($sw==0)
                {
                   	 $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
                                                FROM planes as a, terceros as b
                                                WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
                }
                //particular
                if($sw==2)
                {
                        $query = "select b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre_tercero,
																	c.plan_descripcion, b.tipo_id_paciente as tipo_id_tercero, b.paciente_id as tercero_id, c.protocolos
																	from ingresos as a, pacientes as b, planes as c
																	where a.ingreso='$Ingreso' and a.paciente_id=b.paciente_id and a.tipo_id_paciente=b.tipo_id_paciente
																	and c.plan_id='$PlanId'";
                }
                //capitado
                if($sw==3)
                {
                     $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
																FROM planes as a, terceros as b
																WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
                }
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $var=$result->GetRowAssoc($ToUpper = false);
                $result->Close();
                return $var;
     }

		 function OcupacionPaciente($tipo,$id)
		 {
						list($dbconn) = GetDBconn();
						$query = "SELECT b.ocupacion_descripcion
											FROM pacientes as a, ocupaciones as b
										  WHERE a.tipo_id_paciente='$tipo' and a.paciente_id='$id'
											and a.ocupacion_id=b.ocupacion_id";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						$var=$result->GetRowAssoc($ToUpper = false);
						$result->Close();
						return $var;
		 }

	function TiposDocumentosPacientes()
  {
				list($dbconn) = GetDBconn();
				$query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->fileError = __FILE__;
						$this->lineError = __LINE__;
						return false;
				}

				while (!$result->EOF)
				{
					$vars[]= $result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}

				$result->Close();
				return $vars;
    }

    /**
    *
    */
    function TiposServicios()
    {
				list($dbconn) = GetDBconn();
				$query = "select servicio, descripcion from servicios where sw_asistencial=1";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				while(!$result->EOF)
				{
								$vars[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
				}
				$result->Close();
				return $vars;
    }
		
		function PlanParticulares($PlanId,$tipoPaciente,$idPaciente)
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT sw_tipo_plan FROM planes WHERE plan_id='$PlanId' and sw_tipo_plan='2'";
				$results = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->fileError = __FILE__;
						$this->lineError = __LINE__;						
						return false;
				}
				//no es un plan particular
				if($results->EOF)
				{   return false;		}
				else
				{		//es un particular
						$query = "SELECT b.residencia_telefono, b.residencia_direccion, b.tipo_pais_id, b.tipo_dpto_id, b.tipo_mpio_id,
											b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
											a.nombre_tercero
											FROM pacientes as b left join terceros as a on(a.tipo_id_tercero=b.tipo_id_paciente and a.tercero_id=b.paciente_id)
											WHERE b.tipo_id_paciente='$tipoPaciente' and b.paciente_id='$idPaciente'";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;								
								return false;
						}
						//ya existe en tercero
						if(!empty($result->fields[6]))		
						{		//actualiza terceros
								$query = "UPDATE terceros SET nombre_tercero='".$result->fields[5]."',
																							tipo_pais_id='".$result->fields[2]."',
																							tipo_dpto_id='".$result->fields[3]."',
																							tipo_mpio_id='".$result->fields[4]."',	
																							direccion='".$result->fields[1]."',
																							telefono='".$result->fields[0]."'																																													
													WHERE tipo_id_tercero='$tipoPaciente' and tercero_id='$idPaciente'";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;								
										return false;
								}								
						}				
						else
						{		//no existe
								$query = "INSERT INTO terceros(tipo_id_tercero,tercero_id,nombre_tercero,tipo_pais_id,tipo_dpto_id,
													tipo_mpio_id,direccion,telefono,fax,email,celular,sw_persona_juridica,
													cal_cli,usuario_id,fecha_registro,busca_persona)
													VALUES('$tipoPaciente','$idPaciente','".$result->fields[5]."','".$result->fields[2]."',
													'".$result->fields[3]."','".$result->fields[4]."','".$result->fields[1]."',
													'".$result->fields[0]."','','','','1','0',".UserGetUID().",'now()','')";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;								
										return false;
								}							
						}
						return true;
				}
		}

//------------------------------------------------------------------------------------

?>
