<?php
/*
* Clase para el manejo de la notas de enfermeria
*
* Esta clase contiene los metodos de acceso que retornan la informacion
* de las notas de enfermeria para cada paciente.
*
* @access public
*/

class notas_enfermeria
{
	var $salida=array();
	var $ingreso;
	var $usuario_id;
	var $hora_turno;
	var $duracion_turno;
	var $fecha;
	var $url;


	function notas_enfermeria($ingreso,$fecha,$url){
		$this->hora_turno=ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
		$this->duracion_turno=ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
		$this->ingreso=$ingreso;
		$this->fecha=$fecha;
		$this->url=$url;
		return true;
	}


	//Para obtener los dias de las notas de enfermeria y los controles que
	//se le han realizado
	function GetPrimerDia()
	{
		list($h,$m,$s)=explode(":",$this->hora_turno);
		$fechas=$this->ConsultasSQL('Todos',$h);
		//print_r($fechas);
		return $fechas[0]['fechas'];
	}

	function GetDiasControles()
	{
		$salida="";
		$this->IncludeVista('cabecera');
		list($h,$m,$s)=explode(":",$this->hora_turno);
		$fechas=$this->ConsultasSQL('Todos',$h);
		$datos_paciente=$this->ConsultasSQL('datosPaciente','');
		if (!is_array($datos_paciente)){
			return $salida;
		}
		$salida.=NE_Cabecera($fechas,$this->url,$datos_paciente);
		return $salida;
	}


	function GetSalida(){
	  $this->IncludeVista('cabecera');
  	$this->IncludeVista('controles');

		$this->GenerarNotas();
		$mtz=array();
		if(empty($this->salida)){
			return '<br><br>EL PACIENTE NO TIENE NOTAS DE ENFERMERIA PARA ESTA FECHA.<br><br>';
		}

		foreach($this->salida as $key=> $value){
			foreach($value as $k=> $val){
				$mtz[$key]=$k;
			}
		}
		arsort($mtz);
		foreach($mtz as $k => $value){
			$salida .=$this->salida[$k][$value];
		}
		return $salida;
	}

	function IncludeVista($control){
	  global $VISTA;
		IncludeFile("classes/notas_enfermeria/$VISTA/$control.$VISTA.php");
    return true;
	}

	function ConsultasSQL($consulta,$param_datos)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		list($h,$m,$s)=explode(":",$this->hora_turno);
		switch($consulta)
		{
			case 'Ctrl_15':
				$query="SELECT A.*,
											(
												SELECT descripcion
												FROM hc_signos_vitales_sitios
												WHERE sitio_id=A.sitio_id
											) as sitio_id
								FROM hc_signos_vitales A
								WHERE ingreso= ".$this->ingreso." AND
											(
												A.fecha >= (timestamp '".$this->fecha." ".$this->hora_turno."') AND
												A.fecha <= (timestamp '".$this->fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '".$this->duracion_turno." hours')
											)
											";
				//echo "<br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$datos[]=$data;
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'Ctrl_8':
				$query="SELECT A.*, ( SELECT descripcion FROM hc_tipos_vias_insulina WHERE tipo_via_insulina_id=A.via_nph) as via, ( SELECT descripcion FROM hc_tipos_insulina WHERE tipo_insulina_id=A.via_cristalina )as tipo_insulina_id FROM hc_control_diabetes A WHERE ingreso= ".$this->ingreso." AND ( A.fecha >= (timestamp '".$this->fecha." ".$this->hora_turno."') AND A.fecha <= (timestamp '".$this->fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '".$this->duracion_turno." hours') );";
				//echo "<br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA CTRL_8<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$datos[]=$data;
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'Ctrl_10':
				$query="SELECT A.*,
											 (
												SELECT descripcion
												FROM hc_tipos_talla_pupila
												WHERE talla_pupila_id=A.pupila_talla_d
											 ) as pupila_talla_d,
											 (
												SELECT descripcion
												FROM hc_tipos_talla_pupila
												WHERE talla_pupila_id=A.pupila_talla_i
											 ) as pupila_talla_i,
											 (
												SELECT descripcion
												FROM hc_tipos_reaccion_pupila
												WHERE reaccion_pupila_id=A.pupila_reaccion_d
											 )as pupila_reaccion_d,
											 (
												SELECT descripcion
												FROM hc_tipos_reaccion_pupila
												WHERE reaccion_pupila_id=A.pupila_reaccion_i
											 )as pupila_reaccion_i,
											 (
												SELECT descripcion
												FROM hc_tipos_nivel_consciencia
												WHERE nivel_consciencia_id=A.tipo_nivel_consciencia_id
											 )as tipo_nivel_consciencia_id,
											 (
												SELECT descripcion
												FROM hc_tipos_fuerza
												WHERE fuerza_id=A.fuerza_brazo_d
											 )as fuerza_brazo_d,
											 (
												SELECT descripcion
												FROM hc_tipos_fuerza
												WHERE fuerza_id=A.fuerza_brazo_i
											 )as fuerza_brazo_i,
											 (
												SELECT descripcion
												FROM hc_tipos_fuerza
												WHERE fuerza_id=A.fuerza_pierna_d
											 )as fuerza_pierna_d,
											 (
												SELECT descripcion
												FROM hc_tipos_fuerza
												WHERE fuerza_id=A.fuerza_pierna_i
											 )as fuerza_pierna_i,
											 (
												SELECT descripcion
												FROM hc_tipos_apertura_ocular
												WHERE apertura_ocular_id=A.tipo_apertura_ocular_id
											 )as tipo_apertura_ocular_ids,
											 (
												SELECT descripcion
												FROM hc_tipos_respuesta_verbal
												WHERE respuesta_verbal_id=A.tipo_respuesta_verbal_id
											 )as tipo_respuesta_verbal_ids,
											 (
												SELECT descripcion
												FROM hc_tipos_respuesta_motora
												WHERE respuesta_motora_id=A.tipo_respuesta_motora_id
											 )as tipo_respuesta_motora_ids
								FROM hc_hoja_neurologica A
								WHERE ingreso= ".$this->ingreso." AND
											(
												A.fecha >= (timestamp '".$this->fecha." ".$this->hora_turno."') AND
												A.fecha <= (timestamp '".$this->fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '".$this->duracion_turno." hours')
											)
											";
				//echo "<br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA CTRL_8<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$datos[]=$data;
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'Ctrl_16':
				$query="SELECT GA.*,
											(
												SELECT descripcion
												FROM hc_tipos_concentracion_oxigenoterapia
												WHERE concentracion_id=GA.fio2
											) as fio2_art
								FROM hc_gases_arteriales GA
								WHERE ingreso= ".$this->ingreso." AND
											(
												GA.fecha >= (timestamp '".$this->fecha." ".$this->hora_turno."') AND
												GA.fecha <= (timestamp '".$this->fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '".$this->duracion_turno." hours')
											)
								";
				//echo "<br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA CTRL_16<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$datos[]=$data;
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'Ctrl_16_1':
				$query="SELECT GV.*,
											(
												SELECT descripcion
												FROM hc_tipos_concentracion_oxigenoterapia
												WHERE concentracion_id=GV.fio2
											) as fio2_ven
								FROM hc_gases_venosos GV
								WHERE ingreso= ".$this->ingreso." AND
											(
												GV.fecha >= (timestamp '".$this->fecha." ".$this->hora_turno."') AND
												GV.fecha <= (timestamp '".$this->fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '".$this->duracion_turno." hours')
											)
								";
				//echo "<br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA CTRL_16_1<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$datos[]=$data;
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'controles':
				$query="SELECT descripcion
								FROM  hc_tipos_controles_paciente
								WHERE control_id='$param_datos';";
				//echo "<br><br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA CONTROLES<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				$datos=$resultado->FetchRow();
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'Ctrl_23':
				$query="SELECT AV.*,
											(
												SELECT descripcion
												FROM hc_asistencia_ventilatoria_modos
												WHERE modo_id=AV.modo_id
											) as modo_ids,
											(
												SELECT descripcion
												FROM hc_tipos_concentracion_oxigenoterapia
												WHERE concentracion_id=AV.f102_id
											) as f102_ids
								FROM hc_asistencia_ventilatoria AV
								WHERE ingreso= ".$this->ingreso." AND
											(
												AV.fecha >= (timestamp '".$this->fecha." ".$this->hora_turno."') AND
												AV.fecha <= (timestamp '".$this->fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '".$this->duracion_turno." hours')
											)
								";
				//echo "<br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA CTRL_16<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$datos[]=$data;
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'Todos'://Falta colocar el resto de controles
				$query="SELECT distinct(A.fecha) as fechas
								FROM
								(
									SELECT fecha,ingreso
									FROM hc_signos_vitales
									UNION
									SELECT fecha,ingreso
									FROM hc_control_diabetes
									UNION
									SELECT fecha,ingreso
									FROM hc_asistencia_ventilatoria
									UNION
									SELECT fecha,ingreso
									FROM hc_hoja_neurologica
									UNION
									(
									SELECT
												hsmn.fecha,
												ing.ingreso
									FROM
												hc_evoluciones evo,
												ingresos ing,
												hc_suministro_medicamentos_notas hsmn
									WHERE
												hsmn.evolucion_id=evo.evolucion_id AND
												ing.ingreso=evo.ingreso AND
												hsmn.tipo_nota='2'
									)
									UNION
									(
									SELECT
												hsmn.fecha,
												ing.ingreso
									FROM
												hc_evoluciones evo,
												ingresos ing,
												hc_suministro_mezclas_notas hsmn
									WHERE
												hsmn.evolucion_id=evo.evolucion_id AND
												ing.ingreso=evo.ingreso AND
												hsmn.tipo_nota='2'
									)
									UNION
									SELECT fecha,ingreso
									FROM hc_notas_enfermeria
									UNION
									(
									SELECT
												hmr.fecha,
												ing.ingreso
									FROM
												hc_evoluciones evo,
												ingresos ing,
												hc_medicamentos_recetados hmr
									WHERE
												hmr.evolucion_id=evo.evolucion_id AND
												ing.ingreso=evo.ingreso AND
												hmr.sw_estado='0'
									)
									UNION
									(
									SELECT
												hmzr.fecha,
												ing.ingreso
									FROM
												hc_evoluciones evo,
												ingresos ing,
												hc_mezclas_recetadas hmzr
									WHERE
												hmzr.evolucion_id=evo.evolucion_id AND
												ing.ingreso=evo.ingreso AND
												hmzr.sw_estado='0'
									)
								) as A
								WHERE A.ingreso=".$this->ingreso."
								ORDER BY fechas DESC
								";
				//echo "<br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				$cont=0;
				$datos_fecha2=array();

				while ($data=$resultado->FetchRow()){
					list($fecha,$tiempo)=explode(" ",$data['fechas']);
					if (substr($tiempo,0,2) >= $param_datos){
						if (!in_array($fecha,$datos_fecha2)){
							$datos_fecha[]['fechas']=$fecha;
							array_push($datos_fecha2,$fecha);
						}
					}
					else{
						list($Y,$m,$d)=explode("-",$fecha);
						if (!in_array(date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y)),$datos_fecha2)){
							$datos_fecha[]['fechas']=date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y));
							array_push($datos_fecha2,date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y)));
						}
					}
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos_fecha;
			break;
			case 'notas_medicamentos':
				$query = "SELECT evo.ingreso, sum_notas.*
									FROM  hc_suministro_medicamentos_notas sum_notas,
												hc_evoluciones evo
									WHERE	evo.evolucion_id=sum_notas.evolucion_id AND
												sum_notas.tipo_nota='2' AND
												evo.ingreso=".$this->ingreso." AND
												(
													sum_notas.fecha >= (timestamp '".$this->fecha." ".$this->hora_turno."') AND
													sum_notas.fecha <= (timestamp '".$this->fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '".$this->duracion_turno." hours')
												)";
				//echo "<br><br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado = $dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA NOTAS DE MEDICAMENTO<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$datos[]=$data;
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'notas_mezclas':
				$query = "SELECT evo.ingreso, sum_notas.*
									FROM  hc_suministro_mezclas_notas sum_notas,
												hc_evoluciones evo
									WHERE	evo.evolucion_id=sum_notas.evolucion_id AND
												sum_notas.tipo_nota='2' AND
												evo.ingreso=".$this->ingreso." AND
												(
													sum_notas.fecha >= (timestamp '".$this->fecha." ".$this->hora_turno."') AND
													sum_notas.fecha <= (timestamp '".$this->fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '".$this->duracion_turno." hours')
												)";
				//echo "<br><br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado = $dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA NOTAS DE MEDICAMENTO<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$datos[]=$data;
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'datosMedicamento':
				$query="SELECT inv.descripcion, form_far.descripcion as forma, med.concentracion, med.principio_activo
								FROM  inventarios inv,
											formas_farmaceuticas form_far,
											medicamentos med,
											inventario_medicamentos inv_med
								WHERE	inv.codigo_producto = '$param_datos' AND
											inv_med.codigo_producto = inv.codigo_producto AND
											inv_med.codigo_medicamento = med.codigo_medicamento  AND
											med.forma_farmaceutica = form_far.forma_farmaceutica
								";
				//echo "<br><br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA DATOS MEDICAMENTOS<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				$datos=$resultado->FetchRow();
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'datosMedicamentosMezcla':
				$query="SELECT mzcla_med.*, med.*
								FROM  hc_mezclas_recetadas_medicamentos mzcla_med,
											(
												SELECT inv.codigo_producto, inv.descripcion, form_far.descripcion as forma, med.concentracion, med.principio_activo
												FROM  inventarios inv,
															formas_farmaceuticas form_far,
															medicamentos med,
															inventario_medicamentos inv_med
												WHERE inv_med.codigo_producto = inv.codigo_producto AND
															inv_med.codigo_medicamento = med.codigo_medicamento  AND
															med.forma_farmaceutica = form_far.forma_farmaceutica
											) as med
								WHERE mzcla_med.mezcla_recetada_id = '$param_datos' AND
											med.codigo_producto = mzcla_med.medicamento_id
								";
				//echo "<br><br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA DATOS MEDICAMENTOS<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$datos[]=$data;
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'medicamentos_suspendidos':
				$query = "SELECT evo.ingreso, med_s.*
									FROM  hc_medicamentos_recetados med_s,
												hc_evoluciones evo
									WHERE	med_s.sw_estado='0' AND
												evo.evolucion_id=med_s.evolucion_id AND
												evo.ingreso=".$this->ingreso."  AND
												(
													med_s.fecha >= (timestamp '".$this->fecha." ".$this->hora_turno."') AND
													med_s.fecha <= (timestamp '".$this->fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '".$this->duracion_turno." hours')
												)";
				//echo "<br><br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado = $dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA MEDICAMENTOS SUSPENDIDOS<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$datos[]=$data;
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'Ctrl_Notas_E':
				$query = "SELECT *
									FROM
												hc_notas_enfermeria
									WHERE
												ingreso=".$this->ingreso."  AND
												(
													fecha >= (timestamp '".$this->fecha." ".$this->hora_turno."') AND
													fecha <= (timestamp '".$this->fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '".$this->duracion_turno." hours')
												)";
				//echo "<br><br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado = $dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA NOTAS DE ENFERMERIA<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$datos[]=$data;
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'mezclas_suspendidas':
				$query = "SELECT evo.ingreso, mzcla_s.*
									FROM  hc_mezclas_recetadas mzcla_s,
												hc_evoluciones evo
									WHERE	mzcla_s.sw_estado='0' AND
												evo.evolucion_id=mzcla_s.evolucion_id AND
												evo.ingreso=".$this->ingreso."  AND
												(
													mzcla_s.fecha >= (timestamp '".$this->fecha." ".$this->hora_turno."') AND
													mzcla_s.fecha <= (timestamp '".$this->fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '".$this->duracion_turno." hours')
												)";
				//echo "<br><br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado = $dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA MEZCLAS SUSPENDIDOS<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$datos[]=$data;
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'maestro_vias_admon':
				$query="SELECT *
								FROM hc_vias_administracion
								WHERE via_administracion_id='$param_datos'";
				//echo "<br><br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA MAESTRO VIAS ADMON<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				$datos=$resultado->FetchRow();
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos['nombre'];
			break;
			case 'maestro_vias_admon_uds':
				$query="SELECT *
								FROM hc_vias_administracion_uds
								WHERE via_administracion_id='".$param_datos[0]."' AND
											via_uds_id='".$param_datos[1]."'";
				//echo "<br><br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA MAESTRO VIAS ADMON UDS<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				$datos=$resultado->FetchRow();
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos['descripcion'];
			break;
			case 'maestro_uds_frecuencia':
				$query="SELECT *
								FROM hc_tipo_unidades_frecuencia
								WHERE tipo_unidad_fr_id='".$param_datos[0]."' ";
				//echo "<br><br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA MAESTRO UDS FR.<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				$datos=$resultado->FetchRow();
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos['descripcion'];
			break;
			case 'maestro_horario':
				$query="SELECT descripcion
								FROM hc_horario
								WHERE duracion_id='$param_datos'";
				//echo "<br><br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA MAESTRO HORARIOS<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				$datos=$resultado->FetchRow();
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos['descripcion'];
			break;
			case 'maestro_rango_controles':
				$controles=array();
	/*			$query="SELECT *
								FROM  hc_rangos_controles
								WHERE control_id='$control_id'";
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error al buscar el tipo de control en \"hc_tipos_controles_paciente\"<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				$controles = $resultado->FetchRow();
				if (!$empty($controles)){
				}
				*/
				$query="SELECT *
								FROM  hc_rangos_tipos_controles
								WHERE control_id='$param_datos'";
				//echo "<br>".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado) {
					echo "<br><br>ERROR EN LA CONSULTA MAESTRO RANGOS CONTRLES<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				$datos = $resultado->FetchRow();
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			case 'datosUser':
				$query="SELECT *
								FROM system_usuarios
								WHERE usuario_id=".$param_datos;
				//echo "<br><br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA DATOS USUARIO<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				$datos=$resultado->FetchRow();
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			case 'datosPaciente':
				$query = "SELECT
												PAC.primer_apellido,
												PAC.segundo_apellido,
												PAC.segundo_nombre,
												PAC.primer_nombre,
												INGS.ingreso,
												CTS.numerodecuenta,
												MH.ingreso_dpto_id,
												CAMA.cama,
												PIEZA.pieza
									FROM  movimientos_habitacion MH,
												cuentas CTS,
												camas CAMA,
												piezas PIEZA,
												ingresos INGS,
												pacientes PAC
									WHERE	INGS.ingreso=".$this->ingreso." AND
												INGS.estado='1' AND
												PAC.paciente_id=INGS.paciente_id AND
												PAC.tipo_id_paciente=INGS.tipo_id_paciente AND
												CTS.ingreso=INGS.ingreso AND
												CTS.estado='1' AND
												MH.numerodecuenta=CTS.numerodecuenta AND
												MH.fecha_egreso IS NULL AND
												MH.fecha_ingreso IS NOT NULL AND
												MH.cama=CAMA.cama AND
												CAMA.pieza=PIEZA.pieza";
				//echo "<br><br>Q->".$query;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado = $dbconn->Execute($query);
				if (!$resultado){
					echo "<br><br>ERROR EN LA CONSULTA DATOS DEL PACIENTE<br>".$dbconn->ErrorMsg()."<br>";
					return false;
				}
				$datos=$resultado->FetchRow();
				//echo "<br><br>miralo";print_r($datos);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos;
			break;
			default:
				return false;
			break;
		}//End switch
	}//End Function


  function GenerarNotas()
	{
		if (empty($this->fecha)){
			$this->fecha=$this->GetPrimerDia();
		}

    //GENERAR NOTAS NOTAS ENFERMERIA
		$datos=$this->ConsultasSQL('Ctrl_Notas_E');
		if (is_array($datos) && !empty($datos)){
			foreach($datos as $key=>$value){
				$datos_user=$this->ConsultasSQL('datosUser',$value['usuario_id']);
				if (!is_array($datos_user)){
					return $salida;
				}
				$this->salida[count($this->salida)][$value['fecha']]=Notas_Enfermeria($value,strtoupper("NOTAS DE ENFERMERIA"),$datos_user);
			}
		}


    //GENERAR NOTAS SIGNOS VITALES
		$datos=$this->ConsultasSQL('Ctrl_15');
		$control_descripcion=$this->ConsultasSQL('controles',15);
		if (is_array($datos) && !empty($datos)){
			foreach($datos as $key=>$value){
				$datos_user=$this->ConsultasSQL('datosUser',$value['usuario_id']);
				$fc=$this->ConsultasSQL('maestro_rango_controles',21);
				$value['min_fc']=$fc['rango_min'];
				$value['max_fc']=$fc['rango_max'];
				$temp=$this->ConsultasSQL('maestro_rango_controles',20);
				$value['min_temp']=$temp['rango_min'];
				$value['max_temp']=$temp['rango_max'];
				$pvc=$this->ConsultasSQL('maestro_rango_controles',18);
				$value['min_pvc']=$temp['rango_min'];
				$value['max_pvc']=$temp['rango_max'];
				if (!is_array($datos_user)){
					return $salida;
				}
				$this->salida[count($this->salida)][$value['fecha']]=NE_SignosVitales($value,strtoupper("control ".$control_descripcion['descripcion']),$datos_user);
			}
		}

    //GENERAR NOTAS GLUCOMETRIAS
		$datos=$this->ConsultasSQL('Ctrl_8');
		$control_descripcion=$this->ConsultasSQL('controles',8);
		if (is_array($datos) && !empty($datos)){
			foreach($datos as $key=>$value){
				$datos_user=$this->ConsultasSQL('datosUser',$value['usuario']);
				$gluco=$this->ConsultasSQL('maestro_rango_controles',8);
				$value['min_gluco']=$temp['rango_min'];
				$value['max_gluco']=$temp['rango_max'];
				if (!is_array($datos_user)){
					return $salida;
				}
				$this->salida[count($this->salida)][$value['fecha']]=NE_Glucometria($value,strtoupper("control ".$control_descripcion['descripcion']),$datos_user);
			}
		}

    //GENERAR NOTAS HOJA NEUROLOGICA
		$datos=$this->ConsultasSQL('Ctrl_10');
		$control_descripcion=$this->ConsultasSQL('controles',10);
		if (is_array($datos) && !empty($datos)){
			foreach($datos as $key=>$value){
				$datos_user=$this->ConsultasSQL('datosUser',$value['usuario_id']);
				if (!is_array($datos_user)){
					return $salida;
				}
				$this->salida[count($this->salida)][$value['fecha']]=NE_HojaNeurologica($value,strtoupper("control ".$control_descripcion['descripcion']),$datos_user);
			}
		}

    //GENERAR NOTAS GASES ARTERIALES
		$datos=$this->ConsultasSQL('Ctrl_16');
		$control_descripcion=$this->ConsultasSQL('controles',16);
		if (is_array($datos) && !empty($datos)){
			foreach($datos as $key=>$value){
				$datos_user=$this->ConsultasSQL('datosUser',$value['usuario_id']);
				if (!is_array($datos_user)){
					return $salida;
				}
				$this->salida[count($this->salida)][$value['fecha']]=NE_GasesArteriales($value,strtoupper("control ".$control_descripcion['descripcion']),$datos_user);
			}
		}

    //GENERAR NOTAS GASES VENOSOS
		$datos=$this->ConsultasSQL('Ctrl_16_1');
		if (is_array($datos) && !empty($datos)){
			foreach($datos as $key=>$value){
				$datos_user=$this->ConsultasSQL('datosUser',$value['usuario_id']);
				if (!is_array($datos_user)){
					return $salida;
				}
				$this->salida[count($this->salida)][$value['fecha']]=NE_GasesVenosos($value,strtoupper("control gases venosos"),$datos_user);
			}
		}

    //GENERAR NOTAS ASISTENCIA VENTILATORIA
		$datos=$this->ConsultasSQL('Ctrl_23');
		$control_descripcion=$this->ConsultasSQL('controles',23);
		if (is_array($datos) && !empty($datos)){
			foreach($datos as $key=>$value){
				$datos_user=$this->ConsultasSQL('datosUser',$value['usuario_id']);
				$av=$this->ConsultasSQL('maestro_rango_controles',23);
				$value['min_av']=$temp['rango_min'];
				$value['max_av']=$temp['rango_max'];
				if (!is_array($datos_user)){
					return $salida;
				}
				$this->salida[count($this->salida)][$value['fecha']]=NE_AsistenciaVentilatoria($value,strtoupper("control ".$control_descripcion['descripcion']),$datos_user);
			}
		}

    //GENERAR NOTAS NOTAS DE MEDICAMENTOS
		$datos=$this->ConsultasSQL('notas_medicamentos');
		if (is_array($datos) && !empty($datos)){
			foreach($datos as $key=>$value){
				$datos_user=$this->ConsultasSQL('datosUser',$value['usuario_id']);
				$datos_medicamento=$this->ConsultasSQL('datosMedicamento',$value['medicamento_id']);
				$value['dat_medicamento']=$datos_medicamento['descripcion']." ".$datos_medicamento['forma']." ".$datos_medicamento['concentracion'];
				if (!is_array($datos_user)){
					return $salida;
				}
				$this->salida[count($this->salida)][$value['fecha']]=NE_NotasMedicamentos($value,strtoupper("notas de medicamentos"),$datos_user);
			}
		}

    //GENERAR NOTAS NOTAS DE MEDICAMENTOS
		$datos=$this->ConsultasSQL('notas_mezclas');
		if (is_array($datos) && !empty($datos)){
			foreach($datos as $key=>$value){
				$datos_user=$this->ConsultasSQL('datosUser',$value['usuario_id']);
				$datos_medicamento=$this->ConsultasSQL('datosMedicamentosMezcla',$value['mezcla_recetada_id']);
				foreach ($datos_medicamento as $key =>$medicamento){
					$value['dat_medicamento'.$key]=$medicamento['descripcion']." ".$medicamento['forma']." ".$medicamento['concentracion'];
				}
				$value['dat_medicamentos']=sizeof($datos_medicamento);
				if (!is_array($datos_user)){
					return $salida;
				}
				$this->salida[count($this->salida)][$value['fecha']]=NE_NotasMezclas($value,strtoupper("notas de liquidos"),$datos_user);
			}
		}

    //GENERAR NOTAS MEDICAMENTOS SUSPENDIDOS
		$datos=$this->ConsultasSQL('medicamentos_suspendidos');
		if (is_array($datos) && !empty($datos)){
			foreach($datos as $key=>$value){
				if (!is_null($value['duracion_id'])){
					$value['duracion_id']=$this->ConsultasSQL('maestro_horario',$value['duracion_id']);
				}
				$datos_user=$this->ConsultasSQL('datosUser',$value['usuario_id']);
				$datos_medicamento=$this->ConsultasSQL('datosMedicamento',$value['medicamento_id']);
				$value['dat_medicamento']=$datos_medicamento['descripcion']." ".$datos_medicamento['forma']." ".$datos_medicamento['concentracion'];
				if (!is_array($datos_user)){
					return $salida;
				}
				$this->salida[count($this->salida)][$value['fecha']]=NE_Medicamentos_Suspendidos($value,strtoupper("medicamento suspendido"),$datos_user);
			}
		}

    //GENERAR NOTAS NOTAS DE MEDICAMENTOS
		$datos=$this->ConsultasSQL('mezclas_suspendidas');
		if (is_array($datos) && !empty($datos)){
			foreach($datos as $key=>$value){
				$datos_user=$this->ConsultasSQL('datosUser',$value['usuario_id']);
				$datos_medicamento=$this->ConsultasSQL('datosMedicamentosMezcla',$value['mezcla_recetada_id']);
				$via_id=$value['via_administracion_id'];
				$uds=$this->ConsultasSQL('maestro_vias_admon',$value['via_administracion_id']);
				$value['via_administracion_id']=$uds;
				$uds=$this->ConsultasSQL('maestro_vias_admon_uds',array(0=>$via_id,1=>$value['unidad_via']));
				$value['unidad_via']=$uds;
				$uds=$this->ConsultasSQL('maestro_uds_frecuencia',$value['unidad_calculo']);
				$value['unidad_calculo']=$uds;
				foreach ($datos_medicamento as $key =>$medicamento){
					$value['dat_medicamento'.$key]=$medicamento['descripcion']." ".$medicamento['forma']." ".$medicamento['concentracion'];
				}
				$value['dat_medicamentos']=sizeof($datos_medicamento);
				if (!is_array($datos_user)){
					return $salida;
				}
				$this->salida[count($this->salida)][$value['fecha']]=NE_Mezclas_Suspendidas($value,strtoupper("liquidos suspendidos"),$datos_user);
			}
		}


	}//End function

}//End Class

?>
