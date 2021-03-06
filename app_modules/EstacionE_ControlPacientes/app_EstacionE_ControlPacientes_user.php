
<?php

/**
 * $Id: app_EstacionE_ControlPacientes_user.php,v 1.43 2005/11/30 13:15:09 mauricio Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Estacion de Enfermeria (parte de controles del paciente) 
 */


/**
* Modulo de EstacionE_Pacientes (PHP).
*
//*
*
* @author  <Planetjd@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_EstacionE_Pacientes_user.php
*
//*
**/

class app_EstacionE_ControlPacientes_user extends classModulo
{
	var $uno;//para los errores

	function app_EstacionE_ControlPacientes_user()
	{
		return true;
	}

	function main()
	{
		//$this->PrincipalCartera2();
		return true;
	}


	/**
		*		funcion de darling
		*/
		function BuscarEvolucion($ingreso)
		{
				list($dbconn) = GetDBconn();
				$query = "select b.evolucion_id,b.usuario_id from hc_evoluciones as b
									where b.ingreso='$ingreso'
									and b.estado='1'
									and b.fecha_cierre=(select max(fecha_cierre) from hc_evoluciones	where ingreso='$ingreso')";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				if($result->EOF)
				{  return "nada";  }


				if(!$result->EOF)
				{  $var[0]=$result->fields[0];
				   $var[1]=$result->fields[1];
				}
				$result->Close();
				return $var;
		}




		//aca sacamos las fechas de las evolcuiones pasadas y los nombres de los medicos
		//q atendieron esas evoluciones.[duvan]
		function Buscar_Evoluciones_Medicas($ingreso,$usuario_id)
		{
				if(!empty($usuario_id))
				{$filtro="AND G.usuario_id=$usuario_id";}else{$filtro='';}
				list($dbconn) = GetDBconn();
				$query="SELECT  H.nombre,to_char(G.fecha,'YYYY-MM-DD HH24:MI') as fecha,
								G.evolucion_id,C.numerodecuenta, G.usuario_id,D.ingreso
								FROM cuentas C,ingresos D
								left join hc_evoluciones as G on(D.ingreso=G.ingreso and G.estado=1 $filtro)
								left join system_usuarios as H on(G.usuario_id=H.usuario_id)
								WHERE D.ingreso=$ingreso
								AND C.ingreso = D.ingreso AND C.estado = '1'";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
					$VectorControl[$i]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
					$i++;
				}
				$resulta->Close();
				return $VectorControl;
	 }


	 //revisa si esta pendientepor ingresar a otra estacion,
		function Revisar_Si_esta_trasladado($ingreso)
		{
			 	list($dbconn) = GetDBconn();
	 			/*$sql = "SELECT COUNT(*) FROM ordenes_hospitalizacion
													WHERE hospitalizado = '0'
													AND ingreso=$ingreso";*/
					$sql="SELECT COUNT(*) FROM  pendientes_x_hospitalizar
								WHERE ingreso=$ingreso ";
			  $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
			  return $result->fields[0];
		}


		



	 /*funcion que debe estar en el mod de estacione_controlpaciente*/
		/**
		*
		*
		*		@Author Jairo Duvan Diaz Martinez
		*		@access Public
		*		@return bool
		*/
		function CallListRevisionPorSistemas($estacion)
		{
			if(!empty($_SESSION['HISTORIACLINICA']['DATOS']['ESTACION']))
			{$_REQUEST['estacion']=$_SESSION['HISTORIACLINICA']['DATOS']['ESTACION'];}

			if(!empty($estacion)){$_REQUEST['estacion']=$estacion;}
			if(!$this->ListRevisionPorSistemas($_REQUEST['estacion']))
			{
				unset($_SESSION['HISTORIACLINICA']['DATOS']['ESTACION']);//destruir los datos de la estacion q estan en session..
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"ListRevisionPorSistemas\"";
				return false;
			}
			return true;
		}
		/*funcion que debe estar en el mod de estacione_controlpaciente*/



    function ConteoOrdenesPaciente($ingreso)
		{
				list($dbconn) = GetDBconn();
				$sql="select count(a.hc_os_solicitud_id)
				from hc_os_solicitudes as a, hc_evoluciones as i, ingresos as j
				where j.ingreso='$ingreso' and i.ingreso=j.ingreso and i.evolucion_id=a.evolucion_id and a.sw_estado=1";
				$res=$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

			$contador=$res->fields[0];
			$res->Close();
			if($contador >0){return 1;}


				$sql="select count(a.hc_os_solicitud_id)
				from hc_os_solicitudes as a, hc_evoluciones as i, ingresos as j,
				os_maestro as c
				where j.ingreso='$ingreso' and i.ingreso=j.ingreso and i.evolucion_id=a.evolucion_id
				and a.hc_os_solicitud_id=c.hc_os_solicitud_id and c.sw_estado in(1,2,3)";
				$result=$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

			$contador2=$result->fields[0];
			$result->Close();
			if($contador2 >0){return 1;}else{return 0;}

		}


		/**
		*		Trae la fecha de nacimiento del paciente.
		*
		*		@Author Jairo Duvan Diaz
		*		@access Public
		*		@return bool
		*
		*/
		function GetFechaNacPaciente($ingreso)
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT fecha_nacimiento
				          FROM ingresos a,pacientes b
									WHERE a.ingreso='$ingreso'
									AND b.paciente_id=a.paciente_id
									AND b.tipo_id_paciente=a.tipo_id_paciente
									AND a.estado='1'";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				if($result->EOF)
				{  return "nada";  }


				if(!$result->EOF)
				  $fech=$result->fields[0];
					$result->Close();
					return $fech;
		}




		
/**
		*		VerificaPosicionesPaciente
		*
		*		@Author Arley Velasquez
		*		@access Public
		*		@return bool
		*
		*/
		function VerificaPosicionesPaciente($evolucion)
		{
			$query="SELECT * FROM hc_posicion_paciente WHERE evolucion_id=".$evolucion;
			GLOBAL $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			list($dbconn) = GetDBconn();
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Error al consultar las posiciones del paciente en \"hc_posicion_paciente\" con evolucion_id=".$evolucion;
				$this->mensajeDeError = $query;
				return false;
			}
			$data=$resultado->FetchRow();
			$resultado->Close();
			return $data;
		}//VerificaPosicionesPaciente


		
		/*
		*		GetControlCuraciones
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlCuraciones($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$curacion=array();
								$query = "SELECT * FROM hc_tipos_frecuencia_curaciones WHERE frecuencia_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
									if (!$resultado) {
										$this->error = "Error, no se encuentra el registro en \"hc_tipos_frecuencia_curaciones\" con la frecuencia_id \"$posicion_id\"";
										$this->mensajeDeError = $query;
										return false;
									}
								while ($data = $resultado->FetchRow()) {
									$curacion[]=$data;
								}
								$resultado->Close();
								return $curacion;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_frecuencia_curaciones";
								$resultado=$dbconn->Execute($query);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_frecuencia_curaciones no contiene registros";
										$this->mensajeDeError = $query;
										return false;
									}
									while ($data = $resultado->FetchNextObject($toUpper=false))
									{
										if ($data->frecuencia_id==$posicion_id)
											$option.="<option value='".$data->frecuencia_id."' selected>".$data->descripcion."</option>\n";
										else
											$option.="<option value='".$data->frecuencia_id."'>".$data->descripcion."</option>\n";
									}
									$resultado->Close();
								return $option;
				break;
			}
		}

		
		/*
		*		GetControlPerExtremidades
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlPerExtremidades($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$extremidad=array();
								$query = "SELECT * FROM hc_tipos_extremidades_paciente WHERE tipo_extremidad_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
									if (!$resultado) {
										$this->error = "Error, no se encuentra el registro en \"hc_tipos_extremidades_paciente\" con el tipo_extremidad_id \"$posicion_id\"";
										$this->mensajeDeError = $query;
										return false;
									}
								while ($data = $resultado->FetchRow()) {
									$extremidad[]=$data;
								}
								return $extremidad;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_extremidades_paciente";
								$resultado=$dbconn->Execute($query);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_extremidades_paciente no contiene registros";
										$this->mensajeDeError = $query;
										return false;
									}
									while ($data = $resultado->FetchNextObject($toUpper=false))
									{
										if ($data->tipo_extremidad_id==$posicion_id)
											$option.="<option value='".$data->tipo_extremidad_id."' selected>".$data->descripcion."</option>\n";
										else
											$option.="<option value='".$data->tipo_extremidad_id."'>".$data->descripcion."</option>\n";
									}
								return $option;
				break;
				case 2:
								$extremidad=array();
								$query = "SELECT * FROM hc_tipos_extremidades_paciente";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_extremidades_paciente no contiene registros";
										$this->mensajeDeError = $query;
										return false;
									}
								while ($data = $resultado->FetchRow()) {
									$extremidad[]=$data;
								}
								return $extremidad;
				break;
			}
		}

		/*
		*		GetControlParto
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlParto($evolucion_id)
		{
			$parto=array();
			$query = "SELECT * FROM hc_control_trabajo_parto WHERE evolucion_id='".$evolucion_id."'";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if (!$resultado) {
					$this->error = "Error";
					$this->mensajeDeError = "no se encuentra el registro en \"hc_control_trabajo_parto\" con la evolucion_id \"$evolucion_id\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}
			while ($data = $resultado->FetchRow()) {
				$parto[]=$data;
			}
			return $parto;
		}

		/*
		*		GetControlesPendientesXiniciar
		*
		*		Obtiene los pacientes de la estacion con ingreso activo y por cada uno de estos, obtiene los
		*		controles existentes en hc_controles_pacientes que no esten an agenda => que estan pendientes por iniciar
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@param integer => control_id
		*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
		*/
		function GetControlesPendientesXiniciar($control,$datos_estacion)
		{
			$pacientes = $this->GetPacientesEstacion($datos_estacion[estacion_id],$datos_estacion[departamento]);
			if(!$pacientes){
				return false;
			}
			if(is_array($pacientes))
			{
				$p=0;
				foreach($pacientes as $A => $B)
				{

					$query = "SELECT  CP.ingreso,
														CP.control_id,
														TCP.descripcion
										FROM hc_controles_paciente CP,
												(
													SELECT control_id
													FROM hc_controles_paciente
													WHERE control_id = '".$control."'
													EXCEPT
													SELECT control_id
													FROM hc_agenda_controles
													WHERE control_id = '".$control."'
												) AS A,
													hc_tipos_controles_paciente TCP,
													ingresos I
										WHERE CP.control_id = A.control_id AND
													TCP.control_id = CP.control_id AND
													I.ingreso = $B[4] AND
													CP.ingreso = I.ingreso AND
													I.estado = 1
										ORDER BY CP.ingreso,
													CP.control_id";

					GLOBAL $ADODB_FETCH_MODE;
					list($dbconn) = GetDBconn();
					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$result = $dbconn->Execute($query);
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurri? un error al intentar obtener controles pendientes.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						return false;
					}
					else
					{$controles = array();
						while ($data = $result->FetchRow()) {
							$controles[] = $data;
						}
						if(count($controles)){
							$VectorReal[$p][0] = $B;
							$VectorReal[$p][1] = $controles;
							$p++;
						}
					}
				}//fin foreach pacientes
			}//fin if is_array
			return $VectorReal;
		}//fin GetControlesPendientesXiniciar



		/**
		*		GetTipoLiquidosAdministrados
		*
		*		Consulta la tabla hc_tipo_liquidos_administrados
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool ? array
		*/
		function GetTipoLiquidosAdministrados()
		{
			$query = "SELECT * FROM hc_tipo_liquidos_administrados  ORDER BY tipo_liquido_administrado_id";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar obtener los tipos de liquidos administrados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
					while ($data = $result->FetchRow()) {
						$liquidos[] = $data;
					}
					return $liquidos;
			}
		}



		/**
		*		GetTipoLiquidosEliminados
		*
		*		Consulta la tabla hc_tipo_liquidos_eliminados
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool ? array
		*/
		function GetTipoLiquidosEliminados()
		{
			$query = "SELECT * FROM hc_tipo_liquidos_eliminados ORDER BY tipo_liquido_eliminado_id";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar obtener los tipos de liquidos eliminados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
					while ($data = $result->FetchRow()) {
						$liquidos[] = $data;
					}
					return $liquidos;
			}
		}


		/*
		*		GetPesoPaciente
		*
		*		Selecciona el peso mas reciente del paciente
		*
		*		@Author Arley Vel?squez
		*		@param integer => numero de ingreso del paciente
		*/
		function GetPesoPaciente($ingreso)
		{
			$peso=0;
			$query="SELECT peso, max(fecha) as fecha
							FROM hc_signos_vitales
							WHERE ingreso=$ingreso AND
										peso !=0
							GROUP BY peso ";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar obtener el peso del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			if ($result->RecordCount())
			{
				$peso=$result->FetchObject(false);
				return $peso->peso;
			}
			else	return -1;
		}
		/*
		*		GetBalancePrevio
		*
		*		Obtiene el balance del dia anterior
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool ? array
		*		@param integer => numero de ingreso del paciente
		*		@param timestamp => fecha final del rango
		*		@param timestamp => fecha inicial del rango
		*		@param time => hora de inicio del turno
		*		@param integer => rango del turno
		*/
		function GetBalancePrevio($ingreso,$fechaReciente,$fechaAnterior,$hora_inicio_turno,$rango_turno)
		{
			$vectorAdm = $this->GetTotalAdministrados($ingreso,$fechaAnterior,$fechaReciente);
			$vectorElim = $this->GetTotalEliminados($ingreso,$fechaAnterior,$fechaReciente);

			foreach ($vectorAdm as $key=>$value)
			{
				$Vector[totalAdmin] += $vectorAdm[$key][fila1];
				$Vector[totalElim] += $vectorElim[$key][fila1];
				if (!empty($vectorAdm[$key][fila1]) || !empty($vectorElim[$key][fila1]))
					$Vector[balance] = $Vector[totalAdmin]-$Vector[totalElim];
			}
			return $Vector;
		}//fin GetBalancePrevio

		/**
		*		GetTotalAdministrados
		*
		*		Esta funcion calcula el total de liquidos administrados por hora y su acumulado
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool ? array
		*		@param integer => numero de ingreso del paciente
		*		@param timestamp => fecha inicial del rango
		*		@param timestamp => fecha final del rango
		*/
		function GetTotalAdministrados($ingreso,$fechaReciente,$fechaProxima)
		{
     //$fechaProxima=date("Y-m-d H:i:s",strtotime("+"."1"." hour",strtotime(date($fechaReciente))));
			//ojo que se estan tomando horas de un rango de fechas, las horas no se repiten, por eso es importante el order by
		$query = "SELECT extract(hour from fecha) as horas,
												sum(cantidad) as sumas,
												substring(fecha from 1 for 10) as fechas
								FROM hc_control_liquidos_administrados
								WHERE ingreso = $ingreso AND
											((fecha between '$fechaReciente' AND '$fechaProxima')
											)
								GROUP BY fechas, horas ORDER BY horas;";
					/*echo			$query = "SELECT extract(hour from fecha) as horas,
												sum(cantidad) as sumas,
												substring(fecha from 1 for 10) as fechas
								FROM hc_control_liquidos_administrados
								WHERE ingreso = $ingreso AND
											((fecha between '$fechaReciente' AND '$fechaProxima')
											)
								GROUP BY fechas, horas;";*/

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar hacer balance de liquidos administrados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while ($data = $result->FetchRow())
				{
					$CanXHora += $data[sumas];
					$laHora = date("H:i:s",mktime($data[horas],0,0,date("m"),date("d"),date("Y")));
					$VectorReal[$laHora][fila1] = $data[sumas];
					$VectorReal[$laHora][total] = $CanXHora;
				}
				return $VectorReal;
			}
		}//fin GetTotalAdministrados
		



		/**
		*		GetTotalEliminados
		*
		*		Esta funcion calcula el total de liquidos eliminados por hora y su acumulado
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool ? array
		*		@param integer => numero de ingreso del paciente
		*		@param timestamp => fecha inicial del rango
		*		@param timestamp => fecha final del rango
		*/
		function GetTotalEliminados($ingreso,$fechaReciente,$fechaProxima)
		{	//ojo que se estan tomando horas de un rango de fechas, las horas no se repiten, por eso es importante el order by

			 //$fechaProxima=date("Y-m-d H:i:s",strtotime("+"."1"." hour",strtotime(date($fechaReciente))));

			$query = "SELECT 	extract(hour from fecha) as horas,
												sum(cantidad) as sumas,
												substring(fecha from 1 for 10) as fechas
								FROM hc_control_liquidos_eliminados
								WHERE ingreso = $ingreso AND
											(
												fecha >= (timestamp '$fechaReciente') AND
												fecha <= (timestamp '$fechaProxima')
											)
								GROUP BY fechas,horas";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar hacer balance de liquidos eliminados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while ($data = $result->FetchRow())
				{
					$CanXHora += $data[sumas];
					$laHora = date("H:i:s",mktime($data[horas],0,0,date("m"),date("d"),date("Y")));
					$VectorReal[$laHora][fila1] = $data[sumas];
					$VectorReal[$laHora][total] = $CanXHora;
				}

				return $VectorReal;
			}
		}//fin GetTotalEliminados

		/**
		*		GetDiuresis
		*
		*		Calcula el total de diuresis eliminada en el rango de fecha dada
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool ? integer
		*		@param integer => numero de ingreso del paciente
		*		@param timestamp => fecha inicial del rango
		*		@param timestamp => fecha final del rango
		*/
		function GetDiuresis($ingreso,$fechaReciente,$fechaProxima)
		{
			$query = "SELECT sum(cantidad)
								FROM hc_control_liquidos_eliminados
								WHERE ingreso = $ingreso AND
											(fecha between '$fechaReciente' AND '$fechaProxima') AND
											tipo_liquido_eliminado_id = '0'";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar hacer balance de liquidos administrados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if(!$result->EOF){
					return $result->fields[sum];
				}
			}
		}//fin GetDiuresis




		/*
		*		GetControlPerCefalico
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlPerCefalico($posicion_id)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$perCefalico=array();
			$query = "SELECT * FROM hc_control_perimetro_cefalico WHERE evolucion_id='".$posicion_id."'";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if (!$resultado) {
					$this->error = "Error, no se encuentra el registro en \"hc_control_perimetro_cefalico\" con la evolucion_id \"$posicion_id\"";
					$this->mensajeDeError = $query;
					return false;
				}
			while ($data = $resultado->FetchRow()) {
				$perCefalico[]=$data;
			}
			return $perCefalico;
		}
		
		
		/**
		*		VerificaTerapiasRespiratoriasPacientes
		*
		*		@Author Arley Velasquez
		*		@access Public
		*		@return bool
		*
		*/
		function VerificaTerapiasRespiratoriasPacientes($evolucion)
		{
			$query="SELECT * FROM hc_terapias_respiratorias WHERE evolucion_id=".$evolucion;
			GLOBAL $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			list($dbconn) = GetDBconn();
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_terapias_respiratorias\" con evolucion_id=".$evolucion;
				$this->mensajeDeError = $query;
				return false;
			}
			$data=$resultado->FetchRow();
			return $data;
		}//VerificaTerapiasRespiratoriasPacientes

		

		/*
		*		GetControlTerResp
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlTerResp($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$terapia=array();
								$query = "SELECT * FROM hc_tipos_frecuencia_terapia_respiratoria WHERE frecuencia_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								if (!$resultado) {
									$this->error = "Error, no se encuentra el registro en \"hc_tipos_frecuencia_terapia_respiratoria\" con la frecuencia_id \"$posicion_id\"";
									$this->mensajeDeError = $query;
									return false;
								}
								while ($data = $resultado->FetchRow()) {
									$terapia[]=$data;
								}
								return $terapia;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_frecuencia_terapia_respiratoria";
								$resultado=$dbconn->Execute($query);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_frecuencia_terapia_respiratoria no contiene registros";
										$this->mensajeDeError = $query;
										return false;
									}
									while ($data = $resultado->FetchNextObject($toUpper=false))
									{
										if ($data->frecuencia_id==$posicion_id)
											$option.="<option value='".$data->frecuencia_id."' selected>".$data->descripcion."</option>\n";
										else
											$option.="<option value='".$data->frecuencia_id."'>".$data->descripcion."</option>\n";
									}
								return $option;
				break;
			}
		}

			/*
		*		GetControlPosicion
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlPosicion($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$posicion=array();
								$query = "SELECT * FROM hc_tipos_posicion_paciente WHERE posicion_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								if (!$resultado) {
									$this->error = "Error, no se encuentra el registro en \"hc_tipos_posicion_paciente\" con la posicion \"$posicion_id\"";
									$this->mensajeDeError = $query;
									return false;
								}
								while ($data = $resultado->FetchRow()) {
									$posicion[]=$data;
								}
								return $posicion;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_posicion_paciente";
								$resultado=$dbconn->Execute($query);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_posicion_paciente no contiene registros";
										$this->mensajeDeError = $query;
										return false;
									}
									while ($data = $resultado->FetchNextObject($toUpper=false))
									{
										if ($data->posicion_id==$posicion_id)
											$option.="<option value='".$data->posicion_id."' selected>".$data->descripcion."</option>\n";
										else
											$option.="<option value='".$data->posicion_id."'>".$data->descripcion."</option>\n";
									}
								return $option;
				break;
			}
		}


		/*
		*		GetControlOxiFlujo
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlOxiFlujo($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$flujo=array();
								$query = "SELECT * FROM hc_tipos_flujos_oxigenoterapia WHERE flujo_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
									if (!$resultado) {
										$this->error = "Error, no se encuentra el registro en \"hc_tipos_flujos_oxigenoterapia\" con el flujo_id \"$posicion_id\"";
										$this->mensajeDeError = $query;
										return false;
									}
								while ($data = $resultado->FetchRow()) {
									$flujo[]=$data;
								}
								return $flujo;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_flujos_oxigenoterapia";
								$resultado=$dbconn->Execute($query);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_flujos_oxigenoterapia no contiene registros";
										$this->mensajeDeError = $query;
										return false;
									}
									while ($data = $resultado->FetchNextObject($toUpper=false))
									{
										if ($data->flujo_id==$posicion_id)
											$option.="<option value='".$data->flujo_id."' selected>".$data->descripcion."</option>\n";
										else
											$option.="<option value='".$data->flujo_id."'>".$data->descripcion."</option>\n";
									}
								return $option;
				break;
			}
		}


	/*
		*		GetControlReposo
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlReposo($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$reposo=array();
								$query = "SELECT * FROM hc_tipos_reposo_paciente WHERE tipo_reposo_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								if (!$resultado) {
									$this->error = "Error, no se encuentra el registro en \"hc_tipos_posicion_paciente\" con el tipo_reposo_id \"$posicion_id\"";
									$this->mensajeDeError = $query;
									return false;
								}
								while ($data = $resultado->FetchRow()) {
									$reposo[]=$data;
								}
								return $reposo;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_reposo_paciente";
								$resultado=$dbconn->Execute($query);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_reposo_paciente no contiene registros";
										$this->mensajeDeError = $query;
										return false;
									}
									while ($data = $resultado->FetchNextObject($toUpper=false))
									{
										if ($data->tipo_reposo_id==$posicion_id)
											$option.="<option value='".$data->tipo_reposo_id."' selected>".$data->descripcion."</option>\n";
										else
											$option.="<option value='".$data->tipo_reposo_id."'>".$data->descripcion."</option>\n";
									}
								return $option;
				break;
				case 2:
								$reposo=array();
								$query = "SELECT * FROM hc_tipos_reposo_paciente";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								if (!$resultado) {
									$this->error = "Error, la tabla hc_tipos_reposo_paciente no contiene registros";
									$this->mensajeDeError = $query;
									return false;
								}
								while ($data = $resultado->FetchRow()) {
									$reposo[]=$data;
								}
								return $reposo;
				break;
			}
		}

		/*
		*		GetControlOxiMetodo
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlOxiMetodo($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$metodo=array();
								$query = "SELECT * FROM hc_tipos_metodos_oxigenoterapia WHERE metodo_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								if (!$resultado) {
									$this->error = "Error, no se encuentra el registro en \"hc_tipos_metodos_oxigenoterapia\" con el metodo_id \"$posicion_id\"";
									$this->mensajeDeError = $query;
									return false;
								}
								while ($data = $resultado->FetchRow()) {
									$metodo[]=$data;
								}
								return $metodo;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_metodos_oxigenoterapia";
								$resultado=$dbconn->Execute($query);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_metodos_oxigenoterapia no contiene registros";
										$this->mensajeDeError = $query;
										return false;
									}
									while ($data = $resultado->FetchNextObject($toUpper=false))
									{
										if ($data->metodo_id==$posicion_id)
											$option.="<option value='".$data->metodo_id."' selected>".$data->descripcion."</option>\n";
										else
											$option.="<option value='".$data->metodo_id."'>".$data->descripcion."</option>\n";
									}
								return $option;
				break;
			}
		}




		/**
		*		VerificaOxigenoterapiaPaciente
		*
		*		@Author Arley Velasquez
		*		@access Public
		*		@return bool
		*
		*/
		function VerificaOxigenoterapiaPaciente($evolucion)
		{
			$query="SELECT * FROM hc_oxigenoterapia WHERE evolucion_id=".$evolucion;
			GLOBAL $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			list($dbconn) = GetDBconn();
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Error al consultar las posiciones del paciente en \"hc_oxigenoterapia\" con evolucion_id=".$evolucion;
				$this->mensajeDeError = $query;
				return false;
			}
			$data=$resultado->FetchRow();
			return $data;
		}//VerificaOxigenoterapiaPaciente



//***ESTO VA AL MODULO DE ESTACION DE ENFERMERIA DE CONTROL DE PACIENTES*///
		/**
		*		CallControlesPacientes
		*
		*		Lista todos los pacientes de estacion y dependiendo del control, muestra
		*		el listado de los controles de los pacientes
		*
		*		@Author Arley Velasquez C
		*		@access Public
		*		@return bool
		*/
		function CallControlesPacientes($control,$estacion,$control_descripcion)
		{
			if(!$control){ $control = $_REQUEST['control_id']; }
			if(!$estacion){ $estacion = $_REQUEST['estacion']; }
			if(!$control_descripcion){ $control_descripcion = $_REQUEST['control_descripcion']; }

			//if(!$this->ControlesPacientes($caso,$estacion,$control,$_REQUEST[paciente]))
			//if(!$this->ControlesPacientes($control,$estacion,$control_descripcion))
			/*{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"CallControlesPacientes\"";
				return false;
			}*/
			$this->ControlesPacientes($control,$estacion,$control_descripcion);
			return true;
		}
//***ESTO VA AL MODULO DE ESTACION DE ENFERMERIA DE CONTROL DE PACIENTES*///


/**
		*		AgendaControlesXhoras
		*
		*		Llama a la vista "agenda de controles" en las que se muestran los horarios en los que estos fueron prog.
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return
		*/
		function CallAgendaControlesXhoras()
		{
			if(!$this->AgendaControlesXhoras($_REQUEST['estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"AgendaControlesXhoras\"";
				return false;
			}
			return true;
		}



  /****************************  esto se va para estacion enfermeria control_pacientes  ****************/
		/***
		*		GetAgendaPorHoras
		*
		*		Busca los pacientes que tienen controles por tomar a x hora en el rango del turno
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*/
		function GetAgendaPorHoras ($estacion,$fecha,$fechaProxima)
		{
		 $query = "SELECT AC.fecha,
											I.paciente_id,
											I.tipo_id_paciente,
											I.ingreso,
											P.primer_nombre,
											P.segundo_nombre,
											P.primer_apellido,
											P.segundo_apellido,
											AC.control_id,
											TCP.descripcion,
											C.numerodecuenta,
											MH.cama,
											B.pieza
								FROM hc_agenda_controles AC,
											ingresos I,
											pacientes P,
											hc_tipos_controles_paciente TCP,
											cuentas C,
											movimientos_habitacion AS MH,
											camas B
								WHERE (AC.fecha between '$fecha' AND '$fechaProxima') AND
											AC.estacion_id = '$estacion' AND
											AC.estado = '0' AND
											I.ingreso = AC.ingreso AND
											I.estado = 1 AND
											P.paciente_id = I.paciente_id AND
											P.tipo_id_paciente = I.tipo_id_paciente AND
											TCP.control_id = AC.control_id AND
											C.ingreso = I.ingreso AND
											MH.numerodecuenta = C.numerodecuenta AND
											MH.fecha_egreso IS NULL AND
											B.cama = MH.cama
								ORDER BY AC.fecha, I.ingreso";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar obtener los controles de la estaci?n.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					return "ShowMensaje";
				}
				else
				{
					while ($data = $result->FetchRow())
					{
						$tmp[$data[ingreso]][] = $data;
						$controles[$data[fecha]][] = $tmp;
						//$controles[$data[fecha]][] = $data;
						unset($tmp);
					}
					return $controles;
				}
			}
		}//fin GetAgendaPorHoras

		/****************************  esto se va para estacion enfermeria control_pacientes  ****************/



	/**
	*		GetDiasHospitalizacion
	*
	*		Calcula los d?as que lleva hospitalizada una persona, basandose en la fecha de ingreso.
	*		Esta funcion tamben es llamada desde el modulo censo
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return integer
	*		@param tiemstamp => fecha de ingreso del paciente
	*/
	function GetDiasHospitalizacion($fecha_ingreso)
	{

		if(empty($fecha_ingreso)){
			$fecha_ingreso = '';
			$fecha_ingreso = $_REQUEST['fecha_ingreso'];
		}


				$date1=date('Y-m-d H:i:s');

				$fecha_in=explode(".",$fecha_ingreso);
				$fecha_ingreso=$fecha_in[0];
				$date2=$fecha_ingreso;

				$s = strtotime($date1)-strtotime($date2);
				$d = intval($s/86400);
				$s -= $d*86400;
				$h = intval($s/3600);
				$s -= $h*3600;
				$m = intval($s/60);
				$s -= $m*60;

				$dif= (($d*24)+$h).hrs." ".$m."min";
				$dif2= $d.$space.dias." ".$h.hrs." ".$m."min";
		return $dif2;
	}





	  function GetPaciente_Consulta_Urgencia($Estacion,$datoscenso)
		{
			if($_SESSION['ESTACION_CONTROL']['INGRESO'])
			{
					$ingreso=$_SESSION['ESTACION_CONTROL']['INGRESO'];
					$sql_extra_ur="AND I.ingreso='$ingreso'";
			}

			 $query = "SELECT I.tipo_id_paciente,
											I.paciente_id,
											I.ingreso,
											I.fecha_ingreso,
											P.primer_apellido,
											P.segundo_apellido,
											P.primer_nombre,
											P.segundo_nombre,
											C.numerodecuenta,
											C.plan_id,
											G.plan_descripcion,
											G.tercero_id,
											G.tipo_tercero_id,
											H. nombre_tercero,
											C.rango
								FROM  pacientes P,
											ingresos I,
											cuentas C,
											pacientes_urgencias PU,
											planes G,
											terceros H
								WHERE I.ingreso = C.ingreso AND
											I.estado = 1 AND
											C.estado = 1 AND
											P.paciente_id = I.paciente_id AND
											P.tipo_id_paciente = I.tipo_id_paciente AND
											PU.ingreso = I.ingreso AND
											estacion_id = '$Estacion' AND
											G.plan_id = C.plan_id AND
											H.tercero_id = G.tercero_id AND
											H.tipo_id_tercero = G.tipo_tercero_id
											$sql_extra_ur
								ORDER BY P.primer_nombre,
													P.segundo_nombre,
													P.primer_apellido,
													P.segundo_apellido";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar consultar la tabla 'pacientes_urgencias'<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
			  if($result ->RecordCount() <=0)
				{
					return $datoscenso;
				}
				while ($data = $result->FetchRow()){
					$datoscenso[hospitalizacion][] = $data;//$i++;
				}
			}
			
  		return $datoscenso;
	}


		/*funcion del mod de etacione_control_pacientes*/

		/**
		*		CallFrmFrecuenciaControlesP
		*
		*		@Author Arley Vel?squez C.
		*		@access Public
		*		@return bool
		*/
		function CallFrmFrecuenciaControlesP()
		{
			if($_REQUEST['control']==8)
			{$_SESSION['GLOBAL']['VECTOR']=$_SESSION['GLOBAL']['VECT_GLUCO'];}
			elseif($_REQUEST['control']==10)
			{$_SESSION['GLOBAL']['VECTOR']=$_SESSION['GLOBAL']['VECT_NEURO'];}
			if(!$this->FrmFrecuenciaControlesP($_REQUEST['control'],$_REQUEST['descripcion'],$_REQUEST['estacion'],$_REQUEST['href_action_hora'],$_REQUEST['href_action_control'],$_REQUEST['ingreso']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmFrecuenciaControlesP\"";
				return false;
			}
			return true;
		}
    /*funcion del mod de etacione_control_pacientes*/


	/*funcion del mod estacione_controlpacientes*/
		/*
		*		GetControles
		*
		*		@Author Arley velasquez
		*		@access Public
		*/
		function GetControles($ingreso,$control_id)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			if($control_id)  //si existe un control, entonces filtraremos por el.
			{
				$query="SELECT COUNT(*)
							FROM  hc_controles_paciente cp,
										hc_tipos_controles_paciente tc
							WHERE cp.ingreso=".$ingreso." AND
										cp.control_id = tc.control_id
										AND cp.control_id='$control_id';";

				$resultado=$dbconn->Execute($query);
				if (!$resultado) {
							$this->error = "Error al buscar los controles del paciente en \"hc_controles_paciente\"<br>";
							$this->mensajeDeError = $query;
							return false;
						}
						$controles=$resultado->fields[0];

			}
			else
			{
				$controles=array();
				$query="SELECT cp.*,
										upper(tc.descripcion) as descripcion
							FROM  hc_controles_paciente cp,
										hc_tipos_controles_paciente tc
							WHERE cp.ingreso=".$ingreso." AND
										cp.control_id = tc.control_id;";


						$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						$resultado=$dbconn->Execute($query);
						$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
						if (!$resultado) {
							$this->error = "Error al buscar los controles del paciente en \"hc_controles_paciente\"<br>";
							$this->mensajeDeError = $query;
							return false;
						}
						while ($data = $resultado->FetchRow()) {
							$controles[]=$data;
						}

			}

			return $controles;
		}
/*funcion del mod estacione_controlpacientes*/



		/*
		*		CONTEO DE GetControles
		*
		*		@Author Arley velasquez
		*		@access Public
		*/
		function CountControles($ingreso)
		{
			list($dbconn) = GetDBconn();
			$controles=array();
			$query="SELECT COUNT(*)

							FROM  hc_controles_paciente

							WHERE ingreso=".$ingreso."";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado) {
				$this->error = "Error al buscar los controles del paciente en \"hc_controles_paciente\"<br>";
				$this->mensajeDeError = $query;
				return false;
			}

      if($resultado->fields[0]>0){return 1;}else{return 0;}

		}



		function GetCControlDietas($control)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$query="SELECT *
							FROM hc_solicitudes_dietas
							WHERE evolucion_id=".$control['evolucion_id'];

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			if (!$resultado)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return "ShowMensaje";
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $resultado->FetchRow();
		}


		//trae los detalles de las dietas del paciente.
		function GetCControlDietasDetalle($control)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$query="SELECT dietas_d.*,
											dietas.descripcion
							FROM	hc_solicitudes_dietas dietas_d,
										hc_tipos_dieta dietas
							WHERE dietas_d.evolucion_id=".$control['evolucion_id']." AND
										dietas.hc_dieta_id=dietas_d.hc_dieta_id";

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_dietas\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			while ($data = $resultado->FetchRow()) {
				$dietas_d[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $dietas_d;
		}


		function GetCControlTransfusiones($control)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT * FROM hc_transfusiones WHERE evolucion_id=".$control['evolucion_id'];
			$resultado=$dbconn->Execute($query);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_transfusiones\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado->FetchNextObject($toUpper=false);
		}





/*******************************************************************************/
//funcion del mod estacione_controlpacientes

	/*
	*		GetControlesProgramados()
	*
	*		@Author Arley Velasquez C.
	*		@access Private
	*		@return bool
	*/
	function GetControlesProgramadosNoCumplidos($estacion_id,$ingreso,$control)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		//Obtiene todos los controles no cumplidos
	/*	$query="SELECT  estado,
											fecha
							FROM hc_agenda_controles
							WHERE ingreso=$ingreso AND
										estacion_id='$estacion_id' AND
										control_id='$control' AND
										estado = '0' ";*/
		//Obtiene todos los controles no cumplidos con un rango de 12 horas hacia atras
		$query="SELECT  estado,
									fecha
					FROM hc_agenda_controles
						WHERE ingreso=$ingreso AND
									estacion_id='$estacion_id' AND
								control_id='$control' AND
									estado = '0' AND
									(
 										--fecha > (timestamp '".date ("Y-m-d H").":00:00' - interval '12 hours') AND
										fecha < (timestamp '".date ("Y-m-d H").":00:00')
 									)";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultC = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
			return "ShowMensaje";
		}
		while ($data = $resultC->FetchRow()) {
			$horas_no_cumplidas[]=$data;
		}
		return $horas_no_cumplidas;
	}
//funcion del mod estacione_controlpacientes


//funcion del mod estacione_controlpacientes
	/*
	*		GetControlesProgramados()
	*
	*		@Author Arley Velasquez C.
	*		@access Private
	*		@return bool
	*/
	function GetControlesProgramadosSiguientesTurnos($estacion_id,$ingreso,$control)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$next_turno=array();
		//Obtiene todos los siguientes controles
		$query="SELECT  estado,
										fecha
						FROM	hc_agenda_controles
						WHERE ingreso=$ingreso AND
									estacion_id='$estacion_id' AND
									estado = '0' AND
									control_id='$control' AND
									fecha >= (timestamp '".date ("Y-m-d H").":00:00') ";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultC = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
			return "ShowMensaje";
		}
		while ($data = $resultC->FetchRow()) {
			$next_turno[]=$data;
		}
		return $next_turno;
	}
//funcion del mod estacione_controlpacientes


//funcion del mod estacione_controlpacientes
	/**
	*
	*
	*		@Author Arley Vel?squez
	*		@access Public
	*		@return bool
	*/
	function GetControlProgramadoGlucometria($ingreso)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$liquidos_diario=array();
		$query="SELECT fecha
						FROM hc_control_diabetes
						WHERE ingreso=$ingreso ";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultLD = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if (!$resultLD) {
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return "ShowMensaje";
		}
		while ($data = $resultLD->FetchRow()) {
			$liquidos_diario[]=$data;
		}
		return $liquidos_diario;
	}
//funcion del mod estacione_controlpacientes


//funcion del mod estacione_controlpacientes
	/**
	*
	*
	*		@Author Arley Vel?squez
	*		@access Public
	*		@return bool
	*/
	function GetControlProgramadoHojaNeurologica($ingreso)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$liquidos_diario=array();
		$query="SELECT fecha
						FROM hc_controles_neurologia
						WHERE ingreso=$ingreso";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultLD = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if (!$resultLD) {
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}
		while ($data = $resultLD->FetchRow()) {
			$liquidos_diario[]=$data;
		}
		return $liquidos_diario;
	}
	//funcion del mod estacione_controlpacientes



	 //funcion de estacion de enfermeria control de pacientes
	/**
	*		CallListDietas
	*
	*		Funcion que llama a la vista de las dietas solicit
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function CallListDietasSolicitadas()
	{
		if(!$this->ListDietasSolicitadas($_REQUEST[datos_estacion]))
		{
			$this->error = "No se puede cargar la vista";
			$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"CallListDietasSolicitadas\"";
			return false;
		}
		return true;
	}//CallListDietas
 //funcion de estacion de enfermeria control de pacientes


 
	 //funcion de estacion de enfermeria control pacientes
		/**
		*		GetDatosClavePaciente
		*
		*		Con el ingreso del paciente obtengo los datos personales, la cama, el numero de cuenta
		*		el numero de ingreso al depto, entre otros.
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool - array - string
		*/
		function GetDatosClavePaciente($ingreso)
		{
		$query = "SELECT
											PAC.primer_apellido,
											PAC.segundo_apellido,
											PAC.segundo_nombre,
											PAC.primer_nombre,
											PAC.paciente_id,
											PAC.tipo_id_paciente,
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
								WHERE	INGS.ingreso = ".$ingreso." AND
											INGS.estado='1' AND
											PAC.paciente_id = INGS.paciente_id AND
											PAC.tipo_id_paciente = INGS.tipo_id_paciente AND
											CTS.ingreso = INGS.ingreso AND
											CTS.estado='1' AND
											MH.numerodecuenta = CTS.numerodecuenta AND
											MH.fecha_egreso IS NULL AND
											MH.fecha_ingreso IS NOT NULL AND
											MH.cama = CAMA.cama AND
											CAMA.pieza = PIEZA.pieza";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar obtener datos del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while (!$result->EOF){
					$datosPaciente = $result->FetchRow();
				}
				return $datosPaciente;
			}
		}//GetDatosClavePaciente



//funcion del mod de estacion de enfermeria control pacientes
	/**
	*
	*
	*		@Author Arley Vel?squez
	*		@access Public
	*		@return bool
	*/
	function GetFechasLiquidos($ingreso,$hora_inicio_turno,$rango_turno,$valor)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

		if (!$valor){
			$query="SELECT fecha
							FROM hc_control_liquidos_administrados
							WHERE ingreso=$ingreso AND
										(
										fecha >= (timestamp '".date ("Y-m-d")." ".$hora_inicio_turno."') AND
										fecha <= (timestamp '".date ("Y-m-d")." ".$hora_inicio_turno."' + interval '$rango_turno hours')
										)";

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultLD = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultLD) {
				$this->error = "Error al ejecutar la consulta.<br>";
				$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
				return false;
			}
			while ($data = $resultLD->FetchRow()) {
				$liquidos_diario[]=$data;
			}
		}
		else{
			$query="SELECT fecha
							FROM hc_control_liquidos_administrados
							WHERE ingreso=$ingreso";

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultLD = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultLD) {
				$this->error = "Error al ejecutar la consulta.<br>";
				$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
				return false;
			}
			$liquidos_diario=$resultLD;
		}
		return $liquidos_diario;
	}

//Find de lo nuevo de la estacion


//funciones del modulo de estacion de enfermeria
		/**
		*		GetPacientesControlTransfusiones
		*
		*		Busca los pacientes que tengan control de transfusiones
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool - array - string
		*/
		function GetPacientesControlTransfusiones($departamento,$estacion,$control)
		{//se cambio este query por elde abajo ya que las tranfusiones se pueden con o sin cama
			 /* $query = "SELECT  C.ingreso
								FROM movimientos_habitacion AS MH,
										(
											SELECT  ID.ingreso_dpto_id,
															ID.numerodecuenta
											FROM  ingresos_departamento ID,
														estaciones_enfermeria EE
											WHERE ID.estado = '1' AND
														ID.departamento = '".$departamento."' AND
														EE.estacion_id = ID.estacion_id AND
														EE.estacion_id = '$estacion'
										) AS A,
											camas B,
											cuentas C,
											ingresos D,
											hc_controles_paciente CTRL
								WHERE MH.ingreso_dpto_id = A.ingreso_dpto_id AND
											MH.fecha_egreso IS NULL AND
											MH.cama = B.cama AND
											C.numerodecuenta = A.numerodecuenta AND
											C.ingreso = D.ingreso AND
											C.estado = '1' AND
											C.ingreso= CTRL.ingreso AND
											CTRL.control_id='".$control."'";*/


				$query = "SELECT  DISTINCT(CTRL.ingreso)
											FROM
											cuentas C,
											ingresos D,
											hc_evoluciones E,
											hc_controles_paciente CTRL
								WHERE C.ingreso = D.ingreso AND
											E.ingreso=C.ingreso AND
											E.departamento='".$departamento."' AND
											C.estado = '1' AND
											C.ingreso= CTRL.ingreso AND
											CTRL.control_id='".$control."'";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar obtener los pacientes con control de trasnfusiones.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}

				if($result->EOF){
					return "ShowMensaje";
				}
				else
				{
					while (!$result->EOF){
						$Transfusiones[] = $result->FetchRow();
					}
					return $Transfusiones;
				}
		}//GetPacientesControlTranfusiones







		/*funcion que debe estar en el mod estacione_controlpacientes*/
	/**
	*		CallFrmPrescripcionDietas
	*
	*		Llama al formulario que realiza la solicitud de dietas a los pacienes de la estaci?n
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function CallFrmPrescripcionDietas()
	{print_r($_REQUEST);
		if(!$this->FrmPrescripcionDietas($_REQUEST['datos_estacion']))
		{
			$this->error = "No se puede cargar la vista";
			$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmPrescripcionDietas\"";
			return false;
		}
		return true;
	}//CallFrmPrescripcionDietas

	/*funcion que debe estar en el mod estacione_medicamentos*/


	/*funcion que debe estar en el mod estacione_controlpacientes*/
  	/**
	*		GetTiposDieta
	*
	*		Obtiene los diferentes tipos de dieta existentes
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool-array-string
	*/
	function GetTiposDieta()
	{
		$query = "SELECT hc_dieta_id, descripcion,  abreviatura
							FROM hc_tipos_dieta ORDER BY hc_dieta_id asc";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurri? un error al intentar consultar la tabla hc_tipos_dieta.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			else
			{
				while ($data = $result->FetchRow()){
					$tiposDieta[] = $data;
				}
				return $tiposDieta;
			}
		}
	}//GetTiposDieta
/*funcion que debe estar en el mod estacione_medicamentos*/



/*funcion del modulo estacione_controlpacientes/
	/**
	*		VerificaDietaRecetadaPaciente
	*
	*		Esta funcion verifica si el paciente tiene una dieta recetada inicialmente x el medico
	*
	*		@Author Jairo D.
	*		@access Public
	*		@param integer numero de ingrso del paciente
	*		@return bool
	*/
	function VerificaDietaRecetadaxMedico($ingreso)
	{
		  $query="SELECT a.observaciones, a.hc_dieta_id,x.descripcion, a.fecha_registro

                    FROM hc_solicitudes_dietas a,hc_tipos_dieta x
                    WHERE	 a.hc_dieta_id = x.hc_dieta_id
                    AND  a.ingreso = $ingreso
                    AND a.evolucion_id=(SELECT max(a.evolucion_id) from hc_solicitudes_dietas a,
                         			hc_evoluciones b
                    				WHERE a.ingreso=$ingreso AND
                                        a.evolucion_id=b.evolucion_id
                                        AND b.estado='0')";

          list($dbconn) = GetDBconn();
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la consulta.<br>";
               $this->mensajeDeError = "Ocurrio un error al intentar ejecutar la 											consulta.<br><br>".$dbconn->ErrorMsg()."<br>".$query;
               return false;
          }
          else
          {
               if($result->EOF)
               {return 'show';}
               while (!$result->EOF)
               {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
               return $var;
          }
	}//VerificaDietaRecetadaPaciente
	/*funcion del modulo estacione_medicamentos*/






	/*
	* Funcion que trae la informacion de ayunos del paciente
	*/
	function GetInformacionAyunoPaciente($ingreso)
	{
				$query="SELECT hora_inicio_ayuno,hora_fin_ayuno,motivo,usuario_id,fecha

								 FROM hc_solicitudes_dietas_ayunos
									WHERE
									ingreso = '$ingreso'
									AND fecha='".date("Y-m-d")."'";

				list($dbconn) = GetDBconn();
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la consulta.<br>";
					$this->mensajeDeError = "Ocurrio un error al intentar ejecutar la consulta.<br><br>".$dbconn->ErrorMsg()."<br>".$query;
					return false;
				}

				while (!$result->EOF)
				{
						$var[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}

				return $var;
	}





	/*
	* Funcion que me indica si tiene o no tiene un ayuno el paciente
	*/
	function VerificarAyunoPaciente($ingreso)
	{
				$query="SELECT COUNT(*)

								 FROM hc_solicitudes_dietas_ayunos
									WHERE
									ingreso = '$ingreso'
									AND fecha='".date("Y-m-d")."'";



				list($dbconn) = GetDBconn();
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la consulta.<br>";
					$this->mensajeDeError = "Ocurrio un error al intentar ejecutar la 											consulta.<br><br>".$dbconn->ErrorMsg()."<br>".$query;
					return false;
				}
				else
				{
						if($result->fields[0]>0)
							{return 1;}else	{return 0;}
				}
	}





	/*
	* Funcion que trae las dietas hecha pos la enfermera
	*/
	function VerificaDietaEnf($ingreso)
	{
		$query="SELECT a.observacion, a.hc_dieta_id,x.descripcion, a.fecha_registro

               	FROM hc_solicitudes_dietas_enfermeria a,hc_tipos_dieta x
                    WHERE	 a.hc_dieta_id= x.hc_dieta_id
                    AND  a.ingreso = $ingreso
                    AND fecha='".date("Y-m-d")."'";

          list($dbconn) = GetDBconn();
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la consulta.<br>";
               $this->mensajeDeError = "Ocurrio un error al intentar ejecutar la 											consulta.<br><br>".$dbconn->ErrorMsg()."<br>".$query;
               return false;
          }
          else
          {
               if($result->EOF)
               {return 'show';}

               while (!$result->EOF)
               {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
               return $var;
          }
	}//VerificaDietaRecetadaPaciente
	/*funcion del modulo estacione_medicamentos*/




/*funcion del modulo estacione_controlpacientes/
	/**
	*		VerificaDietaRecetadaPaciente
	*
	*		Esta funcion verifica si el paciente tiene una dieta recetada
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@param integer numero de ingrso del paciente
	*		@return bool
	*/
	function VerificaDietaRecetadaPaciente($ingreso)
	{
		 $query="		 SELECT a.observaciones, a.hc_dieta_id, b.ingreso
								,a.sw_ayuno

								 FROM hc_evoluciones b,hc_solicitudes_dietas a

									WHERE
									b.ingreso = $ingreso AND b.ingreso=a.ingreso";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = "Ocurrio un error al intentar ejecutar la consulta.<br><br>".$dbconn->ErrorMsg()."<br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			else{
				return $result->FetchRow();
			}
		}
	}//VerificaDietaRecetadaPaciente
	/*funcion del modulo estacione_medicamentos*/


//funcion de estacion_enfermeria_controlpacientes
	/**
	*		InsertarPrescripcionDieta
	*
	*		Llama al formulario que realiza la solicitud de dietas a los pacienes de la estaci?n
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function InsertarPrescripcionDieta()
	{
		$hora = $_REQUEST['hora'];//hora final del ayuno
		$horai = $_REQUEST['horai'];//hora inicial del ayuno
		$ayuno = $_REQUEST['ayuno'];
		$dietas = $_REQUEST['diet'];
		$observaciones = $_REQUEST['observaciones'];
		$motivo = $_REQUEST['motivo'];
		$value= $_REQUEST['value'];
		$datos_estacion = $_REQUEST['datos_estacion'];
		list($dbconn) = GetDBconn();


			if(empty($observaciones)){
				$ob = "";
			}else{$ob=$observaciones;}



				 $query="DELETE FROM hc_solicitudes_dietas_enfermeria
															WHERE ingreso=".$value[ingreso]."
														AND fecha='".date("Y-m-d")."'";
														$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->error = "Error al ejecutar la conexion 'hc_solicitudes_dietas'";
												$this->mensajeDeError = "Ocurri? un error al intentar realizar la solicitud de dietas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
												//$dbconn->RollbackTrans();
												return false;
											}


			foreach($dietas as $key => $llave )
			{


					/*		$query = "SELECT COUNT(*) FROM
													hc_solicitudes_dietas_enfermeria WHERE ingreso=".$value[ingreso]."
													AND fecha='".date("Y-m-d")."' AND hc_dieta_id=".$llave[hc_dieta_id]."";
								$result = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al ejecutar la conexion 'hc_solicitudes_dietas'";
									$this->mensajeDeError = "Ocurri? un error al intentar realizar la solicitud de dietas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
									//$dbconn->RollbackTrans();
									return false;
								}

								if($result->fields[0]<1)
								{
								$query = "INSERT INTO hc_solicitudes_dietas_enfermeria (
																															ingreso,
																															fecha,
																															fecha_registro,
																															hc_dieta_id,
																															observacion,
																															usuario_id

																														)
																										VALUES (
																															".$value[ingreso].",
																															'".date("Y-m-d")."',
																															now(),
																															".$llave[hc_dieta_id].",
																															'$ob',
																															".UserGetUID()."

																														);";
								}
								else
								{*/

                 	 $sql = "INSERT INTO hc_solicitudes_dietas_enfermeria (
																															ingreso,
																															fecha,
																															fecha_registro,
																															hc_dieta_id,
																															observacion,
																															usuario_id

																														)
																										VALUES (
																															".$value[ingreso].",
																															'".date("Y-m-d")."',
																															now(),
																															".$llave[hc_dieta_id].",
																															'$ob',
																															".UserGetUID()."

																														);";
								//} esta llave es del comentario
								$result = $dbconn->Execute($sql);
								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al ejecutar la conexion 'hc_solicitudes_dietas'";
									$this->mensajeDeError = "Ocurri? un error al intentar realizar la solicitud de dietas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
									//$dbconn->RollbackTrans();
									return false;
								}

			}
			//$dbconn->CommitTrans();
			$mensaje = "TODAS LAS SOLICITUDES SE REALIZARON CON EXITO";
			$titulo = "MENSAJE";
			$accion = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmPrescripcionDietas',array("datos_estacion"=>$datos_estacion));
			$boton = "VOLVER A PRESCRIPCI?N DE DIETAS";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;

	}//InsertarPrescripcionDieta





	//funcion de estacion de enfermeria controil pacientes
	/**
	*		GetDietasSolicitadas
	*
	*		Esta funcion busca las dietas solicitadas a los pacientes de la estaci?n, en la fecha actual
	*		Cabe anotar que en la tabla hc_control_dietas solo habr? UN registro por paciente
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*		@param array datos de la estacion
	*/
	function GetDietasSolicitadas($datos_estacion)
	{
	  if(!$datos_estacion){$datos_estacion=$_REQUEST['datos'];}
// 	echo	$query = " SELECT SD.solicitud_id,
// 											SD.ingreso,
// 											SD.fecha,
// 											SD.hc_dieta_id,
// 											SD.observaciones,
// 											SD.usuario_id,
// 											B.sw_ayuno,
// 											TD.descripcion,
// 											TD.abreviatura
// 								FROM hc_solicitudes_dietas as SD
// 								LEFT JOIN
// 								(
// 									SELECT ingreso, sw_ayuno, fecha
// 									FROM hc_solicitudes_dietas_ayunos
// 								) as B
// 								ON (SD.ingreso = B.ingreso AND SD.fecha = B.fecha),
// 								hc_tipos_dieta TD
// 								WHERE SD.estacion_id = '".$datos_estacion['estacion_id']."' AND
// 											substring(SD.fecha from 1 for 10) = '".date("Y-m-d")."' AND
// 											TD.hc_dieta_id = SD.hc_dieta_id";
$query="SELECT fecha_registro,ingreso,hc_dieta_id,observaciones,usuario_id,sw_ayuno
 FROM hc_solicitudes_dietas ";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurri? un error al intentar consultar las dietas solicitadas<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			else
			{
				while ($data = $result->FetchRow()){
					$dietas[] = $data;
				}
				return $dietas;
			}
		}
	}//GetDietasSolicitadas



		/**
		*		CallFrmSignosVitales
		*
		*		Esta funci?n lista a tdodos los pacientes de la estacion para poder insertar
		*		en un determinado horario el registro de los signos vitales
		*
		*		@Author Arley Vel?squez C.
		*		@access Public
		*		@return bool
		*/
		function CallFrmSignosVitales()
		{
		  if(empty($_REQUEST['datos_estacion']['control_descripcion']))
			{
				$_REQUEST['datos_estacion']['control_descripcion']='CONTROL MEDICAMENTOS DEL PACIENTE';
				$_REQUEST['datos_estacion']['control_id']=$_REQUEST['control_id'];
			}
			if(!$this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$_REQUEST['cantidad'],$_REQUEST['referer_name'],$_REQUEST['referer_parameters']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmSignosVitales\"";
				return false;
			}
			return true;
		}

		/*funcion del mod estacione_controlpacientes*/

		/*
		*		GetSignosVitalesSitios
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetSignosVitalesSitios($sitio='')
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$sitios=array();

      		if(empty($sitio))
			{
					$query="SELECT *
									FROM hc_signos_vitales_sitios
									ORDER BY indice_orden";

			}else{
					$query="SELECT *
									FROM hc_signos_vitales_sitios
									WHERE sitio_id='$sitio'
									ORDER BY sitio_id";
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado) {
				$this->error = "Error al consultar la tabla \"hc_signos_vitales_sitios\"<br>";
				$this->mensajeDeError = $query;
				return false;
			}
			if(empty($sitio))
			{
					while ($data = $resultado->FetchRow()) {
						$sitios[]=$data;
					}

			}
			else
			{
					$data = $resultado->FetchRow();
					$sitios[]=$data;
			}
			return $sitios;
		}

		/**
		*		GetSignosVitales
		*
		*		Obtiene los signos vitales de un ingreso X
		*
		*		@Author Arley Velasquez
		*		@return bool - array
		*		@access Public
		*/
		function GetSignosVitales ($ingreso)
		{
			$query="SELECT A.*, B.*
							FROM 		(SELECT a.*
											FROM	hc_signos_vitales a
											WHERE a.ingreso=".$ingreso."
											)AS A
											LEFT JOIN
											(SELECT b.*
											FROM	hc_signos_vitales_sitios B
											)AS B
											ON A.sitio_id = B.sitio_id
							ORDER BY A.fecha DESC LIMIT 10 OFFSET 0";
/*			SELECT a.*,
										 s.*
							FROM	hc_signos_vitales a,
										hc_signos_vitales_sitios s
							WHERE a.sitio_id=s.sitio_id AND
										a.ingreso=".$ingreso."
							ORDER BY a.fecha DESC";*/

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Atenci?n";
				$this->mensajeDeError = "Error al consultar la tabla \"hc_signos_vitales\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			if($resultado->EOF){
				return "ShowMensaje";
			}
			else
			{
				while($data = $resultado->FetchRow()){
					$vectorSignos[] = $data;
				}
				return $vectorSignos;
			}
		}//GetSignosVitales



		/**
		*		CallFrmAsistenciaVentilatoria
		*
		*		Esta funci?n lista a tdodos los pacientes de la estacion para poder insertar
		*		en un determinado horario el registro de los signos vitales
		*
		*		@Author Arley Vel?squez C.
		*		@access Public
		*		@return bool
		*/
		function CallFrmAsistenciaVentilatoria()
		{
			if(!$this->FrmAsistenciaVentilatoria($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$_REQUEST['cantidad']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmAsistenciaVentilatoria\"";
				return false;
			}
			return true;
		}

		/*
		*		GetAsistenciaVentilatoriaModos
		*
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetAsistenciaVentilatoriaModos()
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$modos=array();
			$query="SELECT *
							FROM hc_asistencia_ventilatoria_modos
							ORDER BY modo_id";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado) {
				$this->error = "Error al consultar la tabla \"hc_asistencia_ventilatoria_modos\"<br>";
				$this->mensajeDeError = $query;
				return false;
			}
			while ($data = $resultado->FetchRow()) {
				$modos[]=$data;
			}
			return $modos;
		}

			/*
		*		GetControlOxiConcentraciones
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlOxiConcentraciones($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$conc=array();
								$query = "SELECT * FROM hc_tipos_concentracion_oxigenoterapia WHERE concentracion_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								if (!$resultado) {
									$this->error = "Error, no se encuentra el registro en \"hc_tipos_concentracion_oxigenoterapia\" con la concentracion_id \"$posicion_id\"";
									$this->mensajeDeError = $query;
									return false;
								}
								while ($data = $resultado->FetchRow()) {
									$conc[]=$data;
								}
								return $conc;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_concentracion_oxigenoterapia";
								$resultado=$dbconn->Execute($query);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_concentracion_oxigenoterapia no contiene registros";
										$this->mensajeDeError = $query;
										return false;
									}
									while ($data = $resultado->FetchNextObject($toUpper=false))
									{
										if ($data->concentracion_id==$posicion_id)
											$option.="<option value='".$data->concentracion_id."' selected>".$data->descripcion."</option>\n";
										else
											$option.="<option value='".$data->concentracion_id."'>".$data->descripcion."</option>\n";
									}
								return $option;
				break;
			}
		}

/**
		*		GetAsistenciaVentilatoria
		*
		*		Obtine los datos de las asistencias del paciente para mostrarlas en pantalla
		*
		*		@Author Rosa Maria Angel D.
		*		@return bool - array
		*		@access Public
		*		@param integer => numero de ingreso del paciente
		*/
		function GetAsistenciaVentilatoria($ingreso)
		{
			$query="SELECT A.*, C.*
							FROM (		(SELECT a.*
												FROM  hc_asistencia_ventilatoria a
												WHERE A.ingreso=".$ingreso."
												) AS A
												LEFT JOIN
												(SELECT f.concentracion_id, f.descripcion as descripcion_f
												FROM hc_tipos_concentracion_oxigenoterapia f
												) AS B
												ON A.f102_id = B.concentracion_id
										) AS A
										LEFT JOIN
										( SELECT s.*
											FROM hc_asistencia_ventilatoria_modos S
										) AS C
										ON A.modo_id = C.modo_id
							ORDER BY A.fecha DESC";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado) {
				$this->error = "Atenci?n";
				$this->mensajeDeError = "Error al consultar la tabla \"hc_asistencia_ventilatoria\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			if($resultado->EOF){
				return "ShowMensaje";
			}
			else
			{
				while($data = $resultado->FetchRow()){
					$vectorAsistencia[] = $data;
				}
				return $vectorAsistencia;
			}
		}//GetAsistenciaVentilatoria



   /*
	 * funcion de borrado de los signos vitales
	 */
	 function BorradoSignosVitales()
	 {
				$fechaHora = $_REQUEST['fecha'];
				$ingreso=$_REQUEST['ingreso'];
				$datos_e=$_REQUEST['datos_estacion'];
				$cantidad=$_REQUEST['contador'];

				list($dbconn) = GetDBconn();
					//luego valido que no existan registros a esa hora
				$query = "DELETE
									FROM hc_signos_vitales
									WHERE ingreso=".$datos_e[ingreso]." AND
									fecha = '$fechaHora'";
									$dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurri? un error al intentar consultar la tabla \"hc_signos_vitales\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}

				$referer_name = $_REQUEST['referer_name'];
				$referer_parameters = $_REQUEST['referer_parameters'];


				$this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
				return true;

	 }






		/**
		*		InsertarSignosVitales
		*
		*		Inserta los signos vitales de cada paciente de la estacion
		*
		*		@Author Rosa Maria Angel.
		*		@access Public
		*		@return bool
		*/
		function InsertarSignosVitales()
		{


			list($dbconn) = GetDBconn();
			$fechaHora = $_REQUEST['selectHora'].":".$_REQUEST['selectMinutos'];
			//list($fecha,$hora) = explode(" ",$fechaHora);
			$fc=$_REQUEST['fc'];
			$fr=$_REQUEST['fr'];
			$pvc=$_REQUEST['pvc'];
			$taa=$_REQUEST['taa'];
			$tab=$_REQUEST['tab'];
			$sistole= $tab * 2;
			$diastole=$taa;
			$media= (($sistole + $diastole)/3);
			$sw_invasiva=$_REQUEST['sw_invasiva'];
			$sitio=$_REQUEST['sitio'];
			$tpiel=$_REQUEST['tpiel'];
			$servo=$_REQUEST['servo'];
			$observacion=$_REQUEST['observacion'];
			$manual=$_REQUEST['manual'];
			$eva=$_REQUEST['eva'];
			$pic=$_REQUEST['pic'];
			$peso=$_REQUEST['peso'];
			$ingreso=$_REQUEST['ingreso'];
			$sato=$_REQUEST['sato'];
			$control=$_REQUEST['datos_estacion']['control_id'];
			$control_descripcion=$_REQUEST['datos_estacion']['control_descripcion'];

			$referer_name = $_REQUEST['referer_name'];
			$referer_parameters = $_REQUEST['referer_parameters'];

			//valido que por lo menos digit? un dato
			if(empty($fc) && empty($sato) && empty($pvc) && empty($fr) && empty($eva) && empty($taa) && empty($tab) && ($sitio==-1) && empty($tpiel) && empty($servo) && empty($manual) && empty($pic) && empty($peso))
			{
				$this->frmError["MensajeError"] = "DEBE INGRESAR AL MENOS UN DATO";
				$this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
				//if(!$this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$_REQUEST['cantidad'],$_REQUEST['referer_name'],$_REQUEST['referer_parameters']))
				return true;
			}

     /* if($sitio==-1)
			{
				$this->frmError["MensajeError"] = "SELECCIONE EL STIO DE LA TOMA DE LA T.A";
				$this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
				//if(!$this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$_REQUEST['cantidad'],$_REQUEST['referer_name'],$_REQUEST['referer_parameters']))
				return true;
			}*/


			//valido que por lo menos digit? un dato
			if($taa)
			{

        if(!$tab)
				{
    		  $this->frmError["MensajeError"] = "TENSION BAJA &nbsp;SE DEBE LLENAR LAS CASILLAS EN AMBAS PARTES";
				  $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
					return true;
        }
				 	if($sitio==-1)
					{
						$this->frmError["MensajeError"] = "SELECCIONE EL SITIO DE LA TOMA DE LA T.A";
						$this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
						//if(!$this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$_REQUEST['cantidad'],$_REQUEST['referer_name'],$_REQUEST['referer_parameters']))
						return true;
					}
			}

			//valido que por lo menos digit? un dato
			if($tab)
			{

        if(!$taa)
				{
    		  $this->frmError["MensajeError"] = "TENSION ALTA &nbsp;SE DEBE LLENAR LAS CASILLAS EN AMBAS PARTES";
				  $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
					return true;
        }
				if($sitio==-1)
				{
				$this->frmError["MensajeError"] = "SELECCIONE EL STIO DE LA TOMA DE LA T.A";
				$this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
				//if(!$this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$_REQUEST['cantidad'],$_REQUEST['referer_name'],$_REQUEST['referer_parameters']))
				return true;
				}
			}

		 if(empty ($taa) AND empty ($tab) AND $sitio != -1)
          {
    			$this->frmError["MensajeError"] = "SELECCIONE EL SITIO DE LA TOMA DE T.A.";
               $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
               return true;
        	}

          $restriccion = $tab - $taa;
          if($restriccion > 0)
          {
               $this->frmError["MensajeError"] = "LA T.A. SISTOLICA DEBE SER MAYOR A LA T.A. DIASTOLICA";
               $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
               return true;
          }

          if($tpiel > 43)
          {
               $this->frmError["MensajeError"]="LA TEMPERATURA EXCEDE EL VALOR DEL RANGO NORMAL.";
               $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
               return true;
          }

			//luego valido que no existan registros a esa hora
			$query = "SELECT fecha
								FROM hc_signos_vitales
								WHERE ingreso=$ingreso AND
											fecha = '$fechaHora'
								ORDER BY fecha DESC";
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar consultar la tabla \"hc_signos_vitales\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if(!$result->EOF)
				{
					$this->frmError["MensajeError"] = "EN LA FECHA-HORA $fechaHora YA EXISTEN REGISTROS, ESPECIFIQUE UNA HORA DIFERENTE";
					$this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
					return true;
				}
			}

			if (empty($fc)) $fc=0;
			if (empty($fr)) $fr=0;
			if (empty($pvc)) $pvc=0;
			if (empty($taa)) $taa=0;
			if (empty($tab)) $tab=0;
			if (empty($media)) $media=0;
			if (empty($tpiel)) $tpiel=0;
			if (empty($servo)) $servo=0;
			if (empty($manual)) $manual=0;
			if (empty($eva)) $eva=0;
			if (empty($sato)) $sato=0;
			if (empty($pic)) $pic=0;
			if (empty($peso)) $peso=0;
			if ($sitio==-1) $sitio = "NULL"; else $sitio = "'$sitio'";

			if(empty($observacion)){$observacion= '';}

			if (is_numeric($fc) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO FRECUENCIA CARDIACA NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
			  $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
				return true;
			}
			$fc = floor ($fc);

			if (is_numeric($fr) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO FRECUENCIA CARDIACA NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
			  $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
				return true;
			}
			$fr = floor ($fr);

			if (is_numeric($pvc) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO PVC NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
			  $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
				return true;
			}
			$pvc = floor ($pvc);

			if (is_numeric($pic) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO PIC NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
				$this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
				return true;
			}

			if (is_numeric($taa) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO TENSION ARTERIAL ALTA NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
			  $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
				return true;
			}
			$taa = floor ($taa);

			if (is_numeric($tab) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO TENSION ARTERIAL BAJA NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
			  $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
				return true;
			}
			$tab = floor ($tab);

			if (is_numeric($peso) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO PESO NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
			  $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
				return true;
			}

			if (is_numeric($tpiel) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
			  $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
  			return true;
			}

			if (is_numeric($manual) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA MANUAL NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
				$this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
				//$this->frmForma();
				return true;
			}

			if (is_numeric($servo) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA DE INCUBADORA NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
			  $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
				return true;
			}

			if (is_numeric($sato) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO SAT 0<sub>2</sub> NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
			  $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
				return true;
			}

			if ($sato > '100')
			{
				$this->frmError["MensajeError"] = "EL CAMPO SATO DEBE SER MENOR O IGUAL AL 100%";
			  $this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
  			return true;
			}
			$media = floor ($media);

			$query="INSERT INTO hc_signos_vitales ( sitio_id,
													fecha,
													fc,
													pvc,
													ta_alta,
													ta_baja,
													media,
													temp_piel,
													servo,
													manual,
													presion_intracraneana,
													ingreso,
													usuario_id,peso,observacion,fecha_registro,sato2,evaluacion_dolor,fr)
											VALUES ($sitio,
													'$fechaHora',
													$fc,
													$pvc,
													$taa,
													$tab,
													$media,
													$tpiel,
													$servo,
													$manual,
													$pic,
													$ingreso,
													".UserGetUID().",
													$peso,'$observacion',now(),$sato,$eva,$fr)";

			$resultado = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}

			if($referer_name && $referer_parameters){//viene de liquidos
				$this->$referer_name($referer_parameters[paciente],$referer_parameters[estacion]);
				return true;
			}
			//else{//viene de signos vitales
				//$this->CallListRevisionPorSistemas($_REQUEST['estacion']);
			//}
			$this->FrmSignosVitales($_REQUEST['estacion'],$_REQUEST['datos_estacion'],$cantidad,$referer_name,$referer_parameters);
			return true;
		}






/*funcion del mod estacione_medicamentos*/
	/**
	*		GetPacMedicamentosPorSolicitar
	*
	*		obtiene los pacientes que tengan medicamentos recetados vigentes
	*
	*		@Author Jairo Duvan Diaz M.
	*		@access Public
	*		@return array, false ? string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	//buscar aqui
	function GetPacMedicamentosPorSolicitar($ingreso)
	{

			list($dbconn) = GetDBconn();
     $query="SELECT COUNT(*) FROM hc_medicamentos_recetados_hosp
											WHERE ingreso='".$ingreso."'
											AND sw_estado='1'
											AND (sw_ambulatorio ISNULL OR sw_ambulatorio='0')";
											$resulta=$dbconn->execute($query);
								if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
								}
								if($resulta->fields[0]>0)
								{
										return 1;
								}

		return "ShowMensaje";
	}



			/**
		*		InsertarAsistenciaVentilatoria
		*
		*		Esta funci?n lista a tdodos los pacientes de la estacion para poder insertar
		*		en un determinado horario el registro de la asistencia  ventilatoria
		*
		*		@Author Rosa Maria Angel D
		*		@access Public
		*		@return bool
		*/
		function InsertarAsistenciaVentilatoria()
		{
			$fechaHora = $_REQUEST['selectHora'].":".$_REQUEST['selectMinutos'];
			//list($fecha,$hora) = explode(" ",$fechaHora);
			$modo_id = $_REQUEST['modo'];
			$f102_id = $_REQUEST['f102'];
			$fr_respiratoria = $_REQUEST['fr_respiratoria'];
			$fr_ventilatoria = $_REQUEST['fr_ventilatoria'];
			$expontanea = $_REQUEST['expontanea'];
			$volumen = $_REQUEST['volumen'];
			$sens = $_REQUEST['sens'];
			$p_insp = $_REQUEST['p_insp'];
			$ti = $_REQUEST['ti'];
			$i_e = $_REQUEST['i_e'];
			$peep = $_REQUEST['peep'];
			$pip = $_REQUEST['pip'];
			$paw = $_REQUEST['paw'];
			$t_via_a = $_REQUEST['t_via_a'];
			$co2 = $_REQUEST['co2'];
			$sat02 = $_REQUEST['sat02'];
			$pp = $_REQUEST['pp'];
			$pm = $_REQUEST['pm'];
			$etco2 = $_REQUEST['etco2'];
			$ingreso = $_REQUEST['ingreso'];
			$usuario_id = $_REQUEST['usuario_id'];
			$control = $_REQUEST['datos_estacion']['control_id'];
			$control_descripcion = $_REQUEST['datos_estacion']['control_descripcion'];
			list($dbconn) = GetDBconn();

			//valido que por lo menos digit? un dato
			if(empty($modo_id) && ($f102_id==0) && empty($fr_respiratoria) && empty($fr_ventilatoria) && empty($expontanea) && ($volumen) && empty($sens) && empty($p_insp) && empty($ti) && empty($i_e) && empty($peep) && empty($pip) && empty($paw) && empty($t_via_a) && empty($co2) && empty($sat02) && empty($pp) && empty($pm) && empty($etco2))
			{
				$this->frmError["MensajeError"] = "DEBE INGRESAR AL MENOS UN DATO";
				$this->FrmAsistenciaVentilatoria($_REQUEST['estacion'],$_REQUEST['datos_estacion']);
				return true;
			}
			//luego valido que no existan registros a esa hora
			$query = "SELECT fecha
								FROM hc_asistencia_ventilatoria
								WHERE ingreso=$ingreso AND
											fecha = '$fechaHora'
								ORDER BY fecha DESC";
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar consultar la tabla \"hc_asistencia_ventilatoria\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if(!$result->EOF)
				{
					$this->frmError["MensajeError"] = "EN LA FECHA-HORA $fechaHora YA EXISTEN REGISTROS, ESPECIFIQUE UNA HORA DIFERENTE";
					$this->FrmAsistenciaVentilatoria($_REQUEST['estacion'],$_REQUEST['datos_estacion']);
					return true;
				}
			}

			if ($f102_id == 0) $f102_id = "NULL"; else $f102_id = "'$f102_id'";
			if (empty($fr_respiratoria)) $fr_respiratoria=0;
			if (empty($fr_ventilatoria)) $fr_ventilatoria=0;
			if (empty($expontanea)) $expontanea=0;
			if (empty($volumen)) $volumen=0;
			if (empty($sens)) $sens=0;
			if (empty($p_insp)) $p_insp=0;
			if (empty($ti)) $ti=0;
			if (empty($i_e)) $i_e=0;
			if (empty($peep)) $peep=0;
			if (empty($pip)) $pip=0;
			if (empty($paw)) $paw=0;
			if (empty($t_via_a)) $t_via_a=0;
			if (empty($co2)) $co2=0;
			if (empty($sat02)) $sat02=0; else $sat02=$sat02/100;
			if (empty($pp)) $pp = 0;
			if (empty($pm)) $pm = 0;
			if (empty($etco2)) $etco2 = 0;

			$query="INSERT INTO hc_asistencia_ventilatoria (fecha,
																											modo_id,
																											f102_id,
																											fr_respiratoria,
																											fr_ventilatoria,
																											expontanea,
																											volumen,
																											sens,
																											p_insp,
																											ti,
																											i_e,
																											peep,
																											pip,
																											paw,
																											t_via_a,
																											co2,
																											sat02,
																											pp,
																											pm,
																											etco2,
																											ingreso,
																											usuario_id)
																							VALUES ('$fechaHora',
																											'$modo_id',
																											$f102_id,
																											$fr_respiratoria,
																											$fr_ventilatoria,
																											$expontanea,
																											$volumen,
																											$sens,
																											$p_insp,
																											$ti,
																											'$i_e',
																											$peep,
																											$pip,
																											$paw,
																											$t_via_a,
																											$co2,
																											$sat02,
																											$pp,
																											$pm,
																											$etco2,
																											$ingreso,
																											".UserGetUID().")";

			$resultado = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar ingresar la asistencia ventilatoria.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			$this->CallListRevisionPorSistemas($_REQUEST['estacion']);
			return true;
		}


 		/**
		*		CallFrmIngresarDatosLiquidos
		*
		*		Hace un llamado al formulario de captura de datos de liquidos
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmIngresarDatosLiquidos()
		{

		if(!$this->FrmIngresarDatosLiquidos($_REQUEST['referer_parameters'],$_REQUEST["referer_name"],$_REQUEST['datos_estacion'],$_REQUEST['estacion'],'','','','',''))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmIngresarDatosLiquidos\"";
				return false;
			}
			return true;
		}

		/**
		*		GetTipoLiquidosAdministrados
		*
		*		Consulta la tabla hc_tipo_liquidos_administrados
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool ? array
		*/
		
		
		

		

		/*
		*		InsertarDatosLiquidos
		*
		*		Inserta los datos del control de liquidos al paciente X
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function InsertarDatosLiquidos()
		{
			$datosPaciente = $_REQUEST['datos_estacion'];
			$datos_estacion = $_REQUEST['estacion'];
			$cantAdmin = $_REQUEST['cantAdmin'];
			$cantElim = $_REQUEST['cantElim'];
			$selectElim = $_REQUEST['selectElim'];
			$selectHora = $_REQUEST['selectHora'];
			$selectMinutos = $_REQUEST['selectMinutos'];
			$control= $_REQUEST['datos_estacion']['control_id'];
			$control_descripcion= $_REQUEST['datos_estacion']['control_descripcion'];
			$referer_parameters=$_REQUEST['referer_parameters'];
			$referer_name=$_REQUEST['referer_name'];
			$liquidoA = $_REQUEST['liquidoA'];
			
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			//antes de insertar valido que no existan liquidos almacenados a esa hora,
			//si existen devuelvo a la forma anterior indicando que ya hay liquidos a esa hora
			/*foreach ($cantAdmin as $key => $value)
			{
				if(!empty($value))
				{
					$query = "SELECT fecha
										FROM hc_control_liquidos_administrados
										WHERE ingreso = ".$datosPaciente['ingreso']."  AND
													tipo_liquido_administrado_id = ".$key." AND
													fecha = '".$selectHora.":".$selectMinutos."'";
					$result = $dbconn->Execute($query);
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurri? un error al intentar verificar que los liquidos existentes antes de insertar.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						return false;
					}
					else
					{
						if(!$result->EOF)
						{
							while ($data = $result->FetchRow()) {
								$LiquidosAdminExistentes[] = $data;
							}
						}
					}
				}//fin if
			}*/
			if(sizeof($LiquidosAdminExistentes))
			{
				$this->frmError["MensajeError"] = "EN LA FECHA-HORA: '".$selectHora.":".$selectMinutos."' YA EXISTEN REGISTROS DE LIQUIDOS ADMINISTRADOS, ESPECIFIQUE UNA HORA DIFERENTE";
				$this->FrmIngresarDatosLiquidos($referer_parameters,$referer_name,$datosPaciente,$datos_estacion,$cantAdmin,$cantElim,$selectElim,$selectHora,$selectMinutos);
				return true;
			}

			foreach ($cantElim as $key => $value)
			{
				if(!empty($value))
				{
					$query = "SELECT *
										FROM hc_control_liquidos_eliminados
										WHERE ingreso = ".$datosPaciente['ingreso']."  AND
													tipo_liquido_eliminado_id = ".$key." AND
													fecha = '".$selectHora.":".$selectMinutos."'";
					GLOBAL $ADODB_FETCH_MODE;
					list($dbconn) = GetDBconn();
					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$result = $dbconn->Execute($query);
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurri? un error al intentar verificar que los liquidos eliminados existentes antes de insertar.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						return false;
					}
					else
					{
						if(!$result->EOF)
						{
							while ($data = $result->FetchRow()) {
								$LiquidosElimExistentes[] = $data;
							}
						}
					}
				}//fin if
			}

			foreach($selectElim as $keyE => $value)
			{
					$query = "SELECT *
										FROM hc_control_liquidos_eliminados
										WHERE ingreso = ".$datosPaciente['ingreso']."  AND
													tipo_liquido_eliminado_id = ".$key." AND
													fecha = '".$selectHora.":".$selectMinutos."'";
					GLOBAL $ADODB_FETCH_MODE;
					list($dbconn) = GetDBconn();
					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$result = $dbconn->Execute($query);
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurri? un error al intentar verificar que los liquidos eliminados existentes antes de insertar.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						return false;
					}
					else
					{
						if(!$result->EOF)
						{
							while ($data = $result->FetchRow()) {
								$LiquidosElimExistentes[] = $data;
							}
						}
					}
			}

			if(sizeof($LiquidosElimExistentes))
			{
				$this->frmError["MensajeError"] = "EN LA FECHA-HORA: '".$selectHora.":".$selectMinutos."' YA EXISTEN REGISTRO DE LIQUIDOS ELIMINADOS, ESPECIFIQUE UNA HORA DIFERENTE";
				$this->FrmIngresarDatosLiquidos($referer_parameters,$referer_name,$datosPaciente,$datos_estacion,$cantAdmin,$cantElim,$selectElim,$selectHora,$selectMinutos);
				return true;
			}

			############################# inserto los liquidos ########################################
			$dbconn->BeginTrans();
			$puedoHacerCommit = array();
			foreach ($cantAdmin as $key => $value)
			{
				if(!empty($value))
				{
					$XXX=$_REQUEST['liquidoA'.$key];
					 $query = "INSERT INTO hc_control_liquidos_administrados (
																																		ingreso,
																																		fecha,
																																		tipo_liquido_administrado_id,
																																		cantidad,
																																		usuario_id,
																																		fecha_registro,
																																		detalle
																																	)
																													VALUES (
																																		".$datosPaciente['ingreso'].",
																																		'".$selectHora.":".$selectMinutos."',
																																		".$key.",
																																		".$value.",
																																		".UserGetUID().",
																																		now(),
																																		'$XXX'
																																		)
																																		;";

					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurri? un error al intentar ingresar los datos de liquidos administrados<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						$dbconn->RollbackTrans();
						$puedoHacerCommit[] = 0;
						return false;
					}
					else{
						$puedoHacerCommit[] = 1;
					}
				}//fin si esta lleno
			}//fin foreach

			//insert liquidos eliminados
			foreach ($cantElim as $keyE => $valueE)
			{
				if(!empty($valueE))
				{
					//se revisa si es por eliminaci?n urinaria que es 0
					//via 1 espontanea
					// 2 sonda
					 if($keyE==0){$via=$_REQUEST['eliminacionu'];}else{$via='';}
					 $query = "INSERT INTO hc_control_liquidos_eliminados (
																																ingreso,
																																fecha,
																																tipo_liquido_eliminado_id,
																																cantidad,
																																usuario_id,
																																fecha_registro,
																																via
																																)
																												VALUES (
																																".$datosPaciente['ingreso'].",
																																'".$selectHora.":".$selectMinutos."',
																																".$keyE.",
																																".$valueE.",
																																".UserGetUID().",
																																now(),
																																'$via')
																																;
																																";

					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurri? un error al intentar ingresar los datos de liquidos eliminados<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						$dbconn->RollbackTrans();
						$puedoHacerCommit[] = 0;
						return false;
					}
					else{
						$puedoHacerCommit[] = 1;
					}
				}//fin si esta lleno
			}//fin foreach

			foreach($selectElim as $keyE => $valueE)
			{
					$query = "INSERT INTO hc_control_liquidos_eliminados (
																																		ingreso,
																																		fecha,
																																		tipo_liquido_eliminado_id,
																																		cantidad,
																																		deposicion,
																																		usuario_id,
																																		fecha_registro
																																	)
																													VALUES (
																																		".$datosPaciente['ingreso'].",
																																		'".$selectHora.":".$selectMinutos."',
																																		".$keyE.",
																																		0,
																																		'$valueE',
																																		".UserGetUID().",
																																		now());";

					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurri? un error al intentar ingresar los datos de liquidos eliminados<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						$dbconn->RollbackTrans();
						$puedoHacerCommit[] = 0;
						return false;
					}
					else{
						$puedoHacerCommit[] = 1;
					}
			}

			if(!in_array(0,$puedoHacerCommit))
			{
				$query = "UPDATE hc_agenda_controles
									SET estado='1'
									WHERE ingreso = ".$datosPaciente['ingreso']." AND
												estacion_id = '".$datos_estacion['estacion_id']."' AND
												control_id = '".$control."' AND
												fecha = '".$selectHora.":".$selectMinutos."';";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurri? un error al intentar intentar marcar como cumplido el control<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
				else{
					//$dbconn->RollbackTrans();
					$dbconn->CommitTrans();
				}
			}
			unset($cantAdmin); unset($cantElim); unset($selectElim); unset($selectHora); unset($selectMinutos);

			if ($referer_name=="ControlesPacientes"){
				$this->ControlesPacientes($referer_parameters["control_id"],$referer_parameters["estacion"],$referer_parameters[control_descripcion]);
			}
			elseif ($referer_name=="AgendaControlesXhoras"){
				$this->frmError["MensajeError"] = "REGISTRO DE LIQUIDOS GUARDADOS";
				$this->AgendaControlesXhoras($referer_parameters["estacion"]);
			}
			return true;
		}//InsertarDatosLiquidos


			/*
		*		CallFrmControlLiquidosXDias
		*
		*		Llama a la vista que muestra el balance acumulado de los liquidos del paciente
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmControlLiquidosXDias()
		{
			if(!$this->FrmControlLiquidosXDias($_REQUEST['paciente'],$_REQUEST['estacion'],$_REQUEST['control_id']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmControlLiquidosXDias\"";
				return false;
			}
			return true;
		}


		/**
		*		CallFrmControlLiquidos
		*
		*		Llama la  vista que muestra un listado con los totales de liquidos adm y elim del d?a
		*		y llama al balance diario
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmControlLiquidos()
		{
			if(!$this->FrmControlLiquidos($_REQUEST['paciente'],$_REQUEST['estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmControlLiquidos\"";
				return false;
			}
			return true;
		}


		/**
		*		InsertarFechaFinTransfusion
		*
		*		Actualiza el registro de una transfusion para ponerle la fecha de finalizaci?n de la misma
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function InsertarFechaFinTransfusion()
		{
		  $indice = $_REQUEST['indice'];
			$fechaFin = $_REQUEST['fechaFin'.$indice];
			if(!empty($fechaFin))
		 	{
				$f=explode('-',$fechaFin);
				$fechaFin=$f[2].'-'.$f[1].'-'.$f[0];
			}

			$Horas = $_REQUEST['Horas'.$indice];
			$Minutos = $_REQUEST['Minutos'.$indice];
			$ingreso = $_REQUEST['ingreso'.$indice];
			$fechaInicio = $_REQUEST['fechaInicio'.$indice];

      /*if(!empty($fechaInicio))
			{
				$f=explode('-',$fechaInicio);
				$fechaInicio=$f[2].'-'.$f[1].'-'.$f[0];
			}*/

			$estacion = $_REQUEST['estacion'];
			$datos_estacion = $_REQUEST['datos_estacion'];
			if(empty($fechaFin) || empty($Horas) || empty($Minutos) || empty($ingreso) || empty($fechaInicio))
			{
				$this->frmError["MensajeError"] = "DEBE INGRESAR TODOS LOS DATOS PARA LA FECHA FINAL DE LA TRANSFUSION";
				$this->FrmTransfusiones($estacion,$datos_estacion);
				return true;
			}

		 $query = "UPDATE hc_control_transfusiones
								SET fecha_final = '".$fechaFin." ".$Horas.":".$Minutos."'
								WHERE ingreso = $ingreso AND
											fecha = '".$fechaInicio."'";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar ingresar la fecha final de la transfusion <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else{
				$this->FrmTransfusiones($estacion,$datos_estacion);
			}
			return true;
		}//InsertarFechaFinTransfusion



		/**
		*		InsertarTransfusion
		*
		*		Inserta los datos dela transfusion sanguinea
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function InsertarTransfusion()
		{
			$cantBolsas = $_REQUEST['cantBolsas'];
   		$numSello = $_REQUEST['numSello'];
			$fechaVencimiento = $_REQUEST['fechaVencimiento'];
			if(!empty($fechaVencimiento)){
				$f=explode('-',$fechaVencimiento);
				$fechaVencimiento=$f[2].'-'.$f[1].'-'.$f[0];
			}
			$tipoSanguineo = $_REQUEST['tipoSanguineo'];
			list($grupoSanguineo,$rh) = explode(".-.",$tipoSanguineo);
			$fechaInicio = $_REQUEST['fechaInicio'];
			if(!empty($fechaInicio)){
				$f=explode('-',$fechaInicio);
				$fechaInicio=$f[2].'-'.$f[1].'-'.$f[0];
			}
			$HoraInicio = $_REQUEST['HoraInicio'];
			$MinutoInicio = $_REQUEST['MinutoInicio'];
			$ingreso = $_REQUEST['ingreso'];
			$datos_estacion = $_REQUEST['datos_estacion'];
			$estacion = $_REQUEST['estacion'];
			$componente=$_REQUEST['componente'];

			if(empty($cantBolsas) || empty($numSello) || empty($fechaVencimiento) || empty($tipoSanguineo) || empty($fechaInicio) || empty($HoraInicio) || empty($MinutoInicio)){
				$this->frmError["MensajeError"] = "TODOS LOS CAMPOS SON OBLIGATORIOS";
				$this->FrmTransfusiones($estacion,$datos_estacion);
				return true;
			}

			//luego valido que no existan registros a esa hora
			$query = "SELECT fecha
								FROM hc_control_transfusiones
								WHERE ingreso = $ingreso AND
											fecha = '".$fechaInicio." ".$HoraInicio.":".$MinutoInicio."'
								ORDER BY fecha DESC";
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar consultar la tabla \"hc_control_transfusiones\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				$dbconn->RollbackTrans();
				return false;
			}else{
				if(!$result->EOF){
					$this->frmError["MensajeError"] = "EN LA FECHA-HORA '".$selectHora.":".$selectMinutos."' YA EXISTEN REGISTROS, ESPECIFIQUE UNA HORA DIFERENTE";
					$this->FrmTransfusiones($estacion,$datos_estacion);
					return true;
				}
				if(!empty($_REQUEST['IngresoBolsaId'])){$ingresoBolsaId="'".$_REQUEST['IngresoBolsaId']."'";}else{$ingresoBolsaId='NULL';}
				if(!empty($_REQUEST['numeroAlicuota'])){$numeroAlicuota=$_REQUEST['numeroAlicuota'];}else{$numeroAlicuota='0';}
			  $query = "INSERT INTO hc_control_transfusiones (ingreso,
																											fecha,
																											numero_bolsas,
																											numero_sello_calidad,
																											fecha_vencimiento,
																											grupo_sanguineo,
																											rh,
																											fecha_final,
																											usuario,
																											hc_tipo_componente,
																											ingreso_bolsa_id,
																											numero_alicuota,
																											entidad_origen
																											)
																							VALUES ($ingreso,
																											'".$fechaInicio." ".$HoraInicio.":".$MinutoInicio."',
																											'$cantBolsas',
																											'$numSello',
																											'$fechaVencimiento 00:00:00',
																											'$grupoSanguineo',
																											'$rh',
																											NULL,
																											".UserGetUID().",
																											$componente,
																											$ingresoBolsaId,
																											'$numeroAlicuota',
																											'".$_REQUEST['origenComponente']."')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Error al intentar ingresar los datos <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}else{//ModuloGetURL('app','EstacionEnfermeria','user','CallControlesPacientes',array("control_id"=>24,"control_descripcion"=>"CONTROL DE TRANSFUSIONES","estacion"=>$datos))
				  if($ingresoBolsaId!='NULL'){
					  $query="UPDATE banco_sangre_bolsas_alicuotas SET sw_estado='2' WHERE ingreso_bolsa_id=$ingresoBolsaId AND numero_alicuota='$numeroAlicuota'";
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Error al intentar ingresar los datos <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							return false;
						}else{
						  if(!empty($_REQUEST['numeroReserva'])){
              $query="SELECT a.tipo_componente_id,a.cantidad_componente
							FROM banco_sangre_reserva_detalle a
							WHERE a.solicitud_reserva_sangre_id='".$_REQUEST['numeroReserva']."'";
							$result = $dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0){
								$this->error = "Error al ejecutar la conexion";
								$this->mensajeDeError = "Error al intentar ingresar los datos <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								$dbconn->RollbackTrans();
								return false;
							}else{
								if($result->RecordCount()>0){
									while(!$result->EOF){
										$vars[]=$result->GetRowAssoc($toUpper=false);
										$result->MoveNext();
									}
								}
								for($i=0;$i<sizeof($vars);$i++){
									$query="SELECT CASE WHEN (SELECT count(*)
									FROM banco_sangre_entrega_bolsas a,hc_control_transfusiones b
									WHERE a.ingreso_bolsa_id=b.ingreso_bolsa_id AND a.numero_alicuota=b.numero_alicuota AND
									a.tipo_componente_id='".$vars[$i]['tipo_componente_id']."' AND a.solicitud_reserva_sangre_id='".$_REQUEST['numeroReserva']."')='".$vars[$i]['cantidad_componente']."' THEN 1
									ELSE '0' END";
									$result = $dbconn->Execute($query);
									if($dbconn->ErrorNo() != 0){
										$this->error = "Error al ejecutar la conexion";
										$this->mensajeDeError = "Error al intentar ingresar los datos <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
										$dbconn->RollbackTrans();
										return false;
									}else{
                    if($result->fields[0]!='1'){
										  $salir=1;
                      break;
										}
									}
								}
								if($salir!='1'){
                  $query="UPDATE banco_sangre_reserva SET sw_estado='4' WHERE solicitud_reserva_sangre_id='".$_REQUEST['numeroReserva']."'";
									$result = $dbconn->Execute($query);
									if($dbconn->ErrorNo() != 0){
										$this->error = "Error al ejecutar la conexion";
										$this->mensajeDeError = "Error al intentar ingresar los datos <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
										$dbconn->RollbackTrans();
										return false;
									}
								}
							}
						}
						}
					}
          unset($_REQUEST['cantBolsas']);
					unset($_REQUEST['numSello']);
					unset($_REQUEST['fechaVencimiento']);
					unset($_REQUEST['tipoSanguineo']);
					unset($_REQUEST['fechaInicio']);
					unset($_REQUEST['HoraInicio']);
					unset($_REQUEST['MinutoInicio']);
					unset($_REQUEST['datos_estacion']);
					unset($_REQUEST['estacion']);
					unset($_REQUEST['componente']);
					unset($_REQUEST['IngresoBolsaId']);
				  unset($_REQUEST['numeroAlicuota']);
				  unset($_REQUEST['origenComponente']);
					unset($_REQUEST['origen']);
				}
				$dbconn->CommitTrans();
			}
			$this->FrmTransfusiones($estacion,$datos_estacion);
			return true;
		}//InsertarTransfusion




		/**
		*		GetFechaIngreso
		*
		*		Obtiene la fecha de ingreso de un numero de ingreso X
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool ? array
		*		@param integer => numero de ingreso del paciente
		*/
		function GetFechaIngreso($ingreso)
		{
			$query = "SELECT fecha_ingreso
								FROM ingresos
								WHERE ingreso = $ingreso";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar hacer balance de liquidos administrados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else{
				return $result->fields[fecha_ingreso];
			}
		}//GetFechaIngreso


/**
		*		GetBalancesAcum
		*
		*		Calcula los totales diarios de liquidos administrados y eliminados desde la fecha de ingreso
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool ? array
		*		@param integer => numero de ingreso del paciente
		*		@param date => fecha de ingreso del paciente
		*		@param time => hora de inicio del turno de la EE
		*		@param date => duracion del turno

		*/
		function GetBalancesAcum($ingreso,$fechaIngreso,$hora_inicio_turno,$rango_turno)
		{
			$fecha = $fechaAnterior = $fechaIngreso;

			while ($fecha <= date("Y-m-d H:i:s"))
			{
				list($h,$m,$s)=explode(":",$hora_inicio_turno);
				list($fecha,$thora) = explode(" ",$fecha);
				$Rfecha = explode("-",$fecha);
				$fecha = date("Y-m-d H:i:s",mktime($h,($m-1),$s,$Rfecha[1],($Rfecha[2]+1),$Rfecha[0]));
				$ojo = $this->GetBalancePrevio($ingreso,$fecha,$fechaAnterior,$hora_inicio_turno,$rango_turno);
				$eje = $this->GetDiuresis($ingreso,$fechaAnterior,$fecha);

				$fechaAnterior =date("Y-m-d H:i:s",mktime($h,$m,$s,$Rfecha[1],($Rfecha[2]+1),$Rfecha[0]));
				if($ojo || $eje)
				{
					$X[$Rfecha[0]."-".$Rfecha[1]."-".$Rfecha[2]][0] = $ojo;
					$X[$Rfecha[0]."-".$Rfecha[1]."-".$Rfecha[2]][1] = $eje;
					unset($ojo); unset($eje);
				}
			}
			return $X;
		}//fin GetBalancesAcumFromFechaIngresao

		/*
		*		GetBalancePrevio
		*
		*		Obtiene el balance del dia anterior
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool ? array
		*		@param integer => numero de ingreso del paciente
		*		@param timestamp => fecha final del rango
		*		@param timestamp => fecha inicial del rango
		*		@param time => hora de inicio del turno
		*		@param integer => rango del turno
		*/
		
		


		/**
		*		CallFrmLiquidosAdministrados
		*
		*		Muestra el detalle de los liquidos que se le han administrados al paciente
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmLiquidosAdministrados()
		{
			if(!$this->FrmLiquidosAdministrados($_REQUEST['paciente'],$_REQUEST['estacion'],$_REQUEST['datosAlternos']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmLiquidosAdministrados\"";
				return false;
			}
			return true;
		}





		/**
		*		GetLiquidosEliminados
		*
		*		Muestra los liquidos eliminados en el rango de fecha dada al ingreso x
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool ? array
		*		@param integer => numero de ingreso del paciente
		*		@param timestamp => fecha inicial del rango
		*		@param timestamp => fecha final del rango
		*/
		function GetLiquidosEliminados($ingreso, $fechaReciente, $fechaProxima)
		{
			$query = "SELECT A.*,
											B.descripcion
								FROM (
											SELECT extract(hour from fecha) as horas,
														sum(cantidad) as sumas,
														tipo_liquido_eliminado_id,
														substring(fecha from 1 for 10) as fechas,
														deposicion,via
											FROM hc_control_liquidos_eliminados
											WHERE ingreso = $ingreso AND
														(fecha between '$fechaReciente' AND '$fechaProxima')
											GROUP BY horas,
														tipo_liquido_eliminado_id,
														fechas,
														deposicion,via
											) as A,
											hc_tipo_liquidos_eliminados B
								WHERE		A.tipo_liquido_eliminado_id = B.tipo_liquido_eliminado_id
								ORDER BY A.fechas, A.horas ";


			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar hacer balance de liquidos administrados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while ($data = $result->FetchRow()){
					$vLiquido[$data[horas]][] = $data;
				}
				return $vLiquido;
			}
		}//fin GetLiquidosAdministrados




			/**
		*		CallFrmLiquidosEliminados
		*
		*		Muestra el detalle de los liquidos que el paciente X ha eliminado
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmLiquidosEliminados()
		{
			if(!$this->FrmLiquidosEliminados($_REQUEST['paciente'],$_REQUEST['estacion'],$_REQUEST['datosAlternos']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmLiquidosEliminados\"";
				return false;
			}
			return true;
		}

		/**
		*		GetLiquidosAdministrados
		*
		*		Muestra los liquidos administrados en la fecha dada al ingreso x
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool ? array
		*		@param integer => numero de ingreso del paciente
		*		@param timestamp => fecha inicial del rango
		*		@param timestamp => fecha final del rango
		*/
		function GetLiquidosAdministrados($ingreso,$fechaReciente,$fechaProxima)
		{
			 $query = "SELECT A.*,
											B.descripcion
								FROM (
											SELECT extract(hour from fecha) as horas,
														sum(cantidad) as sumas,detalle,
														tipo_liquido_administrado_id,
														substring(fecha from 1 for 10) as fechas
											FROM hc_control_liquidos_administrados
											WHERE ingreso = $ingreso AND
														(fecha between '$fechaReciente' AND '$fechaProxima')
											GROUP BY horas, detalle,tipo_liquido_administrado_id, fechas
											) as A,
											hc_tipo_liquidos_administrados B
								WHERE		A.tipo_liquido_administrado_id=B.tipo_liquido_administrado_id
								ORDER BY A.fechas, A.horas";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar hacer balance de liquidos administrados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while ($data = $result->FetchRow()){
					$vLiquido[$data[horas]][] = $data;
				}
				return $vLiquido;
			}
		}//fin GetLiquidosAdministrados



		/**
		*
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*		@return bool
		*/
		function GetRevisionSistemas()
		{
			if (!IncludeFile("classes/notas_enfermeria/revision_sistemas.class.php"))
			{
				$this->error = "Error";
				$this->mensajeDeError = "No se puo incluir : classes/notas_enfermeria/revision_sistemas.class.php";
				return false;
			}
			$url=ModuloGetURL('app','EstacionE_ControlPacientes','user','GetRevisionSistemas',array("estacion"=>$_REQUEST['estacion'],"ingreso"=>$_REQUEST['ingreso']));
			$url_origen=ModuloGetURL('app','EstacionE_ControlPacientes','user','CallListRevisionPorSistemas',array("estacion"=>$_REQUEST['estacion']));
			$revision_sistemas = new RevisionSistemas($_REQUEST['estacion']['estacion_id'],$_REQUEST['ingreso'],$url,$url_origen);
			if (!$revision_sistemas->Iniciar()){
				$this->error = $revision_sistemas->Error();
				$this->mensajeDeError = $revision_sistemas->ErrorMsg();
				return false;
			}
			$this->salida=$revision_sistemas->GetSalida();
			return true;
		}//End

/**
		*
		*
		*		@Author Arley Velasquez
		*		@access Public
		*		@return bool
		*
		*/
		function verificaControlNeurologicoPaciente($evolucion)
		{
			$query="SELECT * FROM hc_control_neurologico WHERE evolucion_id=".$evolucion;
			GLOBAL $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			list($dbconn) = GetDBconn();
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_neurologico\" con evolucion_id=".$evolucion;
				$this->mensajeDeError = $query;
				return false;
			}
			$data=$resultado->FetchRow();
			return $data;
		}

/*
		*		GetControlNeurologico
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlNeurologico($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$neuro=array();
								$query = "SELECT * FROM hc_tipos_frecuencia_control_neurologico WHERE frecuencia_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
									if (!$resultado) {
										$this->error = "Error, no se encuentra el registro en \"hc_tipos_frecuencia_control_neurologico\" con la frecuencia_id \"$posicion_id\"";
										$this->mensajeDeError = $query;
										return false;
									}
								while ($data = $resultado->FetchRow()) {
									$neuro[]=$data;
								}
								return $neuro;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_frecuencia_control_neurologico";
								$resultado=$dbconn->Execute($query);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_frecuencia_control_neurologico no contiene registros";
										$this->mensajeDeError = $query;
										return false;
									}
									while ($data = $resultado->FetchNextObject($toUpper=false))
									{
										if ($data->frecuencia_id==$posicion_id)
											$option.="<option value='".$data->frecuencia_id."' selected>".$data->descripcion."</option>\n";
										else
											$option.="<option value='".$data->frecuencia_id."'>".$data->descripcion."</option>\n";
									}
								return $option;
				break;
			}
		}


		/*
		*		GetControlPerAbdominal
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlPerAbdominal($posicion_id)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$perAbd=array();
			$query = "SELECT * FROM hc_control_perimetro_abdominal WHERE evolucion_id='".$posicion_id."'";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if (!$resultado) {
					$this->error = "Error, no se encuentra el registro en \"hc_control_perimetro_abdominal\" con la evolucion_id \"$posicion_id\"";
					$this->mensajeDeError = $query;
					return false;
				}
			while ($data = $resultado->FetchRow()) {
				$perAbd[]=$data;
			}
			return $perAbd;
		}
			/**
		*		VerificaCurvasTermicasPaciente
		*
		*		@Author Arley Velasquez
		*		@access Public
		*		@return bool
		*
		*/
		function VerificaCurvasTermicasPaciente($evolucion)
		{
			$query="SELECT * FROM hc_curvas_termicas WHERE evolucion_id=".$evolucion;
			GLOBAL $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			list($dbconn) = GetDBconn();
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Error al consultar las posiciones del paciente en \"hc_posicion_paciente\" con evolucion_id=".$evolucion;
				$this->mensajeDeError = $query;
				return false;
			}
			$data=$resultado->FetchRow();
			return $data;
		}//VerificaCurvasTermicasPaciente

/*
		*		GetControlCurTerm
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlCurTerm($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$curTerm=array();
								$query = "SELECT * FROM hc_tipos_frecuencia_curva_termica WHERE frecuencia_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								if (!$resultado) {
									$this->error = "Error, no se encuentra el registro en \"hc_tipos_frecuencia_curva_termica\" con la frecuencia_id \"$posicion_id\"";
									$this->mensajeDeError = $query;
									return false;
								}
								while ($data = $resultado->FetchRow()) {
									$curTerm[]=$data;
								}
								return $curTerm;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_frecuencia_curva_termica";
								$resultado=$dbconn->Execute($query);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_frecuencia_curva_termica no contiene registros";
										$this->mensajeDeError = $query;
										return false;
									}
									while ($data = $resultado->FetchNextObject($toUpper=false))
									{
										if ($data->frecuencia_id==$posicion_id)
											$option.="<option value='".$data->frecuencia_id."' selected>".$data->descripcion."</option>\n";
										else
											$option.="<option value='".$data->frecuencia_id."'>".$data->descripcion."</option>\n";
									}
								return $option;
				break;
			}
		}

		/**
		*		VerificaControlLiquidosPaciente
		*
		*		@Author Arley Velasquez
		*		@access Public
		*		@return bool
		*
		*/
		function VerificaControlLiquidosPaciente($evolucion)
		{
			$query="SELECT * FROM hc_control_liquidos WHERE evolucion_id=".$evolucion;
			GLOBAL $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			list($dbconn) = GetDBconn();
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_liquidos\" con evolucion_id=".$evolucion;
				$this->mensajeDeError = $query;
				return false;
			}
			$data=$resultado->FetchRow();
			return $data;
		}//VerificaControlLiquidosPaciente

		/*
		*		GetControlLiquidos
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlLiquidos($posicion_id)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$liquidos=array();
			$query = "SELECT * FROM hc_control_liquidos WHERE evolucion_id='".$posicion_id."'";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado) {
				$this->error = "Error, no se encuentra el registro en \"hc_control_liquidos\" con la evolucion_id \"$posicion_id\"";
				$this->mensajeDeError = $query;
				return false;
			}
			while ($data = $resultado->FetchRow()) {
				$liquidos[]=$data;
			}
			return $liquidos;
		}

		/**
		*		verificaTensionArterialPaciente
		*
		*		@Author Arley Velasquez
		*		@access Public
		*		@return bool
		*
		*/
		function verificaTensionArterialPaciente($evolucion)
		{
			$query="SELECT * FROM hc_control_tension_arterial WHERE evolucion_id=".$evolucion;
			GLOBAL $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			list($dbconn) = GetDBconn();
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_tension_arterial\" con evolucion_id=".$evolucion;
				$this->mensajeDeError = $query;
				return false;
			}
			$data=$resultado->FetchRow();
			return $data;
		}//verificaTensionArterialPaciente


		/**
		*		verificaGlucometriaPaciente
		*
		*		@Author Arley Velasquez
		*		@access Public
		*		@return bool
		*
		*/
		function verificaGlucometriaPaciente($evolucion)
		{

			$query="SELECT * FROM hc_control_glucometria WHERE evolucion_id=".$evolucion;
			GLOBAL $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			list($dbconn) = GetDBconn();
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_glucometria\" con evolucion_id=".$evolucion;
				$this->mensajeDeError = $query;
				return false;
			}
			$data=$resultado->FetchRow();
			return $data;
		}//verificaGlucometriaPaciente


		/**
		*		verificaControlCuracionesPaciente
		*
		*		@Author Arley Velasquez
		*		@access Public
		*		@return bool
		*
		*/
		function verificaControlCuracionesPaciente($evolucion)
		{
			$query="SELECT * FROM hc_control_curaciones WHERE evolucion_id=".$evolucion;
			GLOBAL $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			list($dbconn) = GetDBconn();
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_curaciones\" con evolucion_id=".$evolucion;
				$this->mensajeDeError = $query;
				return false;
			}
			$data=$resultado->FetchRow();
			return $data;
		}//verificaControlCuracionesPaciente

			/**
		*		verificaPerimetroAbdominalPaciente
		*
		*		@Author Arley Velasquez
		*		@access Public
		*		@return bool
		*
		*/
		function verificaPerimetroAbdominalPaciente($evolucion)
		{
			$query="SELECT * FROM hc_control_perimetro_abdominal WHERE evolucion_id=".$evolucion;
			GLOBAL $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			list($dbconn) = GetDBconn();
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_perimetro_abdominal\" con evolucion_id=".$evolucion;
				$this->mensajeDeError = $query;
				return false;
			}
			$data=$resultado->FetchRow();
			return $data;
		}//verificaPerimetroAbdominalPaciente

		/**
		*
		*
		*		@Author Arley Velasquez
		*		@access Public
		*		@return bool
		*
		*/
		function verificaPerimetroCefalicoPaciente($evolucion)
		{
			$query="SELECT * FROM hc_control_perimetro_cefalico WHERE evolucion_id=".$evolucion;
			GLOBAL $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			list($dbconn) = GetDBconn();
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_perimetro_cefalico\" con evolucion_id=".$evolucion;
				$this->mensajeDeError = $query;
				return false;
			}
			$data=$resultado->FetchRow();
			return $data;
		}

/*
		*		GetControlTA
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlTA($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$ta=array();
								$query = "SELECT * FROM hc_tipos_frecuencia_ta WHERE frecuencia_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								if (!$resultado) {
									$this->error = "Error, no se encuentra el registro en \"hc_tipos_frecuencia_ta\" con la frecuencia_id \"$posicion_id\"";
									$this->mensajeDeError = $query;
									return false;
								}
								while ($data = $resultado->FetchRow()) {
									$ta[]=$data;
								}
								return $ta;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_frecuencia_ta";
								$resultado=$dbconn->Execute($query);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_frecuencia_ta no contiene registros";
										$this->mensajeDeError = $query;
										return false;
									}
									while ($data = $resultado->FetchNextObject($toUpper=false))
									{
										if ($data->frecuencia_id==$posicion_id)
											$option.="<option value='".$data->frecuencia_id."' selected>".$data->descripcion."</option>\n";
										else
											$option.="<option value='".$data->frecuencia_id."'>".$data->descripcion."</option>\n";
									}
								return $option;
				break;
			}
		}

/*
		*		GetControlGlucometria
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*/
		function GetControlGlucometria($posicion_id,$valor)
		{
  		GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();

			$option="";
			switch ($valor)
			{
				case 0:
								$gluco=array();
								$query = "SELECT * FROM hc_tipos_frecuencia_glucometrias WHERE frecuencia_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
									if (!$resultado) {
										$this->error = "Error, no se encuentra el registro en \"hc_tipos_frecuencia_glucometrias\" con la frecuencia_id \"$posicion_id\"";
										$this->mensajeDeError = $query;
										return false;
									}
								while ($data = $resultado->FetchRow()) {
									$gluco[]=$data;
								}
								return $gluco;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_frecuencia_glucometrias";
								$resultado=$dbconn->Execute($query);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_frecuencia_glucometrias no contiene registros";
										$this->mensajeDeError = $query;
										return false;
									}
									while ($data = $resultado->FetchNextObject($toUpper=false))
									{
										if ($data->frecuencia_id==$posicion_id)
											$option.="<option value='".$data->frecuencia_id."' selected>".$data->descripcion."</option>\n";
										else
											$option.="<option value='".$data->frecuencia_id."'>".$data->descripcion."</option>\n";
									}
								return $option;
				break;
			}
		}

		/**
		*		GetAlarmaRangoControl
		*
		*		Verifica si el valor del control se encuentra dentrol del rango para ese control
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool - string
		*		@param integer => control_id
		*		@param character => sexo del paciente
		*		@param integer => edad del paciente
		*		@param integer => valor a verificar
		*/
		function GetAlarmaRangoControl($control,$sexo,$edad,$temp)
		{
			$query = "SELECT *
								FROM hc_rangos_controles
								WHERE control_id = $control AND
											sexo = '".$sexo."' AND
											($edad BETWEEN edad_min AND edad_max AND
											$temp BETWEEN rango_min AND rango_max)";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar obtener la fecha de nacimiento del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if(($result->EOF)){
					return "Alarma";
				}
				else{
					return "Normal";
				}
			}
			return true;
		}//GetAlarmaRangoControl





	/**
	*		GetDatosUsuarioSistema
	*
	*		Obtiene el nombre de usuario del sistema
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*		@param integer => usuario_id
	*/
	function	GetNotasReaccionAdversasPaciente($ingreso,$fecha)
	{
		list($dbconnect) = GetDBconn();
		$query="SELECT ingreso,fecha,observacion,usuario_id,fecha_registro,sw_reaccion
						FROM hc_control_transfusiones_notas_reaccion_adversas
						WHERE ingreso='$ingreso'
						AND date(fecha)='$fecha'
						ORDER BY fecha_registro DESC";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al traer las notas de reacciones adversas";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
      return false;
		}

		 if($result->EOF)
		 {return 'ShowMensage';}

		 $i=0;
			while (!$result->EOF)
			{
				$vector[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		return $vector;
	}



			/**
		*		GetDatosUsuarioSistema
		*
		*		Obtiene el nombre de usuario del sistema
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*		@param integer => usuario_id
		*/
		function GetDatosUsuarioSistema($usuario)
		{
			$query = "SELECT usuario,
					nombre
								FROM system_usuarios
								WHERE usuario_id = $usuario";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar obtener los datos del usuario.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					return "ShowMensaje";
				}
				else
				{
					while ($data = $result->FetchRow()){
						$DatosUser[] = $data;
					}
					return $DatosUser;
				}
			}
		}/// GetDatosUsuarioSistema

/**
		*		CallFrmIngresarDatosGlucometr?a
		*
		*		Llama al formulario de captura de datos de glucometria
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmIngresarDatosGlucometria()
		{
			if(!$this->FrmIngresarDatosGlucometria($_REQUEST['referer_parameters'],$_REQUEST["referer_name"],$_REQUEST['datos_estacion'],$_REQUEST['estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmIngresarDatosGlucometria\"";
				return false;
			}
			return true;
		}


		// CallControlNeurologico()


		/**
		*		InsertHojaNeurologica
		*
		*		Inserta los datos del control neurologico del paciente X
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function InsertHojaNeurologica()
		{
			list($dbconn) = GetDBconn();
			$datosPaciente = $_REQUEST['datos_estacion'];
			$datos_estacion = $_REQUEST['estacion'];
			$TallasPupilaD = $_REQUEST['TallasPupilaD'];
			$TallasPupilaI = $_REQUEST['TallasPupilaI'];
			$ReaccionPupilaI = $_REQUEST['ReaccionPupilaI'];
			$ReaccionPupilaD = $_REQUEST['ReaccionPupilaD'];
			$NivelConciencia = $_REQUEST['NivelConciencia'];
			$FuerzaBrazoD = $_REQUEST['FuerzaBrazoD'];
			$FuerzaPiernaD = $_REQUEST['FuerzaPiernaD'];
			$FuerzaBrazoI = $_REQUEST['FuerzaBrazoI'];
			$FuerzaPiernaI = $_REQUEST['FuerzaPiernaI'];
			$AperturaOcular = $_REQUEST['AperturaOcular'];
			$RespuestaVerbal = $_REQUEST['RespuestaVerbal'];
			$RespuestaMotora = $_REQUEST['RespuestaMotora'];

			$control= $_REQUEST['datos_estacion']['control_id'];
			$control_descripcion= $_REQUEST['datos_estacion']['control_descripcion'];
			$referer_parameters=$_REQUEST['referer_parameters'];
			$referer_name=$_REQUEST['referer_name'];



			if(!$NivelConciencia || !$AperturaOcular || !$RespuestaVerbal || !$RespuestaMotora)
			{
				if(!$NivelConciencia){ $this->frmError["NivelConciencia"] = 1; }
				if(!$AperturaOcular){ $this->frmError["AperturaOcular"] = 1; }
				if(!$RespuestaVerbal){ $this->frmError["RespuestaVerbal"] = 1; }
				if(!$RespuestaMotora){ $this->frmError["RespuetaMotora"] = 1; }

				$this->frmError["MensajeError"] = "FALTAN DATOS OBLIGATORIOS";
				$this->FrmControlNeurologico($referer_parameters,$referer_name,$datosPaciente,$datos_estacion,$TallasPupilaD,$TallasPupilaI,$ReaccionPupilaI,$ReaccionPupilaD,$NivelConciencia,$FuerzaBrazoD,$FuerzaPiernaD,$FuerzaBrazoI,$FuerzaPiernaI,$AperturaOcular,$RespuestaVerbal,$RespuestaMotora);
				return true;
			}

				$dbconn->BeginTrans();
				$query = "UPDATE hc_agenda_controles
									SET estado='1'
									WHERE ingreso = ".$datosPaciente['ingreso']." AND
												estacion_id = '".$datos_estacion['estacion_id']."' AND
												control_id = '".$control."' AND
												fecha = '".$datosPaciente['Hora']."' ";

				$result = $dbconn->Execute($query);
				if ($result){
			/*	$query = "INSERT INTO hc_hoja_neurologica (
																	sistema_neurologico_id,
																	fecha,
																	pupila_talla_d,
																	pupila_talla_i,
																	pupila_reaccion_d,
																	pupila_reaccion_i,
																	tipo_nivel_consciencia_id,
																	fuerza_brazo_d,
																	fuerza_brazo_i,
																	fuerza_pierna_d,
																	fuerza_pierna_i,
																	tipo_apertura_ocular_id,
																	tipo_respuesta_verbal_id,
																	tipo_respuesta_motora_id,
																	usuario_id,
																	ingreso
																)
												VALUES (
																	nextval('public.hc_hoja_neurologica_sistema_neurologico_id_seq'::text),
																	'".$datosPaciente['Hora']."',
																	'".$TallasPupilaD."',
																	'".$TallasPupilaI."',
																	'".$ReaccionPupilaD."',
																	'".$ReaccionPupilaI."',
																	'".$NivelConciencia."',
																	'".$FuerzaBrazoD."',
																	'".$FuerzaBrazoI."',
																	'".$FuerzaPiernaD."',
																	'".$FuerzaPiernaI."',
																	'".$AperturaOcular."',
																	'".$RespuestaVerbal."',
																	'".$RespuestaMotora."',
																	".UserGetUID().",
																	".$datosPaciente['ingreso'].")";*/

						//cambiamos por el de tizziano..
						$query="INSERT INTO hc_controles_neurologia (fecha,
													pupila_talla_d,
													pupila_talla_i,
													pupila_reaccion_d,
													pupila_reaccion_i,
													tipo_nivel_consciencia_id,
													fuerza_brazo_d,
													fuerza_brazo_i,
													fuerza_pierna_d,
													fuerza_pierna_i,
													tipo_apertura_ocular_id,
													tipo_respuesta_verbal_id,
													tipo_respuesta_motora_id,
													usuario_id,
													ingreso,
													fecha_registro)
											VALUES (
															'".$datosPaciente['Hora']."',
															'".$TallasPupilaD."',
															'".$TallasPupilaI."',
															'".$ReaccionPupilaD."',
															'".$ReaccionPupilaI."',
															'".$NivelConciencia."',
															'".$FuerzaBrazoD."',
															'".$FuerzaBrazoI."',
															'".$FuerzaPiernaD."',
															'".$FuerzaPiernaI."',
															'".$AperturaOcular."',
															'".$RespuestaVerbal."',
															'".$RespuestaMotora."',
															".UserGetUID().",
															".$datosPaciente['ingreso'].",
															now())";



					$result = $dbconn->Execute($query);
					if (!$result)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurri? un error al intentar ingresar los datos del control neurologico<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						$dbconn->RollbackTrans();
						return false;
					}
					else{
						$dbconn->CommitTrans();
					}
				}
				if (!$result)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurri? un error al intentar ingresar marcar como cumplido el control<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}

			if ($referer_name=="FrmFrecuenciaControlesP"){
				$this->FrmFrecuenciaControlesP($referer_parameters["control"],$referer_parameters["descripcion"],$datos_estacion,$referer_parameters["href_action_hora"],$referer_parameters["href_action_control"],$_REQUEST['ingreso']);
			}
			elseif ($referer_name=="AgendaControlesXhoras"){
				$this->AgendaControlesXhoras($referer_parameters["estacion"]);
			}
			return true;
		}//InsertHojaNeurologica


/**
		*		GetViasInsulina
		*
		*		Obtiene las vias de administracion de insulina
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*/
		function GetViasInsulina()
		{
			$query = "SELECT tipo_via_insulina_id, descripcion
								FROM hc_tipos_vias_insulina";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar obtener las v?as de administracion de insulina.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					return "ShowMensaje";
				}
				else
				{
					while ($data = $result->FetchRow()){
						$ViasInsulina[] = $data;
					}
					return $ViasInsulina;
				}
			}
		}//GetViasInsulina

		/**
		*		GetResumenHojaNeurologica
		*
		*		Obtiene los registros del control de glucometira del ingreso X
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*		@param integer => ingreso paciente
		*/
		function GetResumenGlucometria($ingreso)
		{
			/*$query = "SELECT CDG.*, CG.*
								FROM
								(
									hc_control_diabetes CD
									LEFT JOIN
									(	SELECT descripcion as tipoInsulina, tipo_insulina_id
										FROM hc_tipos_insulina
									) AS TI
									ON (TI.tipo_insulina_id=CD.tipo_insulina_id)
								) as CDG,
								(
									hc_control_diabetes CD1
									LEFT JOIN
									(	SELECT descripcion as via,tipo_via_insulina_id
										FROM hc_tipos_vias_insulina
									) AS TVI
									ON (TVI.tipo_via_insulina_id=CD1.via)
								) as CG
								WHERE CDG.ingreso = $ingreso AND
											CDG.ingreso = CG.ingreso AND
											CDG.fecha = CG.fecha
								ORDER BY CDG.fecha DESC;";*/
			$query = "SELECT CDG.ingreso,
											 CDG.fecha,
											 CDG.glucometria,
											 CDG.valor_cristalina,
											 CDG.via_cristalina,
											 TVIA.descripcion as viacristalina,
											 CDG.valor_nph,
											 CDG.via_nph,
											 TVIB.descripcion as vianph
								FROM hc_control_diabetes CDG
								LEFT JOIN hc_tipos_vias_insulina TVIA ON (TVIA.tipo_via_insulina_id = CDG.via_cristalina)
								LEFT JOIN hc_tipos_vias_insulina TVIB ON (TVIB.tipo_via_insulina_id = CDG.via_nph)
								WHERE CDG.ingreso = $ingreso
								ORDER BY CDG.fecha DESC;";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar obtener los controles de diabetes del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					return "ShowMensaje";
				}
				else
				{
					while ($data = $result->FetchRow())
					{
						$controles[$data[fecha]][] = $data;
					}
					return $controles;
				}
			}
		}//Fin GetResumenGlucometria

		/**
		*		GetTiposInsulina
		*
		*		Obtiene los tipos de insulina existentes
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*/
		function GetTiposInsulina()
		{
			$query = "SELECT tipo_insulina_id,descripcion
								FROM hc_tipos_insulina
								ORDER BY descripcion";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar obtener los tipos de insulina.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					return "ShowMensaje";
				}
				else
				{
					while ($data = $result->FetchRow()){
						$TiposInsulina[] = $data;
					}
					return $TiposInsulina;
				}
			}
		}//GetTiposInsulina

		/**
		*		InsertarDatosGlucometria
		*
		*		Ingresa a la BD los datos del control de glucometria.
		*		La glucometria es independiente de la insulina no son mutuamente requeridas
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function InsertarDatosGlucometria()
		{
			$datosPaciente = $_REQUEST['datos_estacion'];
			$datos_estacion = $_REQUEST['estacion'];
			$fecha = $_REQUEST['fecha'];

			$Glucometria = $_REQUEST['Glucometria']; 
			$checkInsulina = $_REQUEST['checkInsulina'];
			$textInsulina = $_REQUEST['textInsulina'];
			$ViaInsulina = $_REQUEST['ViaInsulina'];

			$control = $_REQUEST['datos_estacion']['control_id'];
			$control_descripcion = $_REQUEST['datos_estacion']['control_descripcion'];
			$referer_parameters = $_REQUEST['referer_parameters'];
			$referer_name = $_REQUEST['referer_name'];


			########### valido que los valores se hayan llenado correctamente ####
			if(empty($Glucometria) && (!is_array($checkInsulina)))// && in_array("",$textInsulina)
			{//todos vacios
				if(!is_array($checkInsulina) || !is_array($textInsulina) || in_array("-1",$ViaInsulina)){ $this->frmError["Insulina"] = 1; }
				if(empty($Glucometria)){ $this->frmError["Glucometria"] = 1; }
				if(!is_array($checkInsulina))  { $this->frmError["SelectInsulina"] = 1; }
				if(in_array("",$textInsulina))   { $this->frmError["TextInsulina"] = 1; }
				if(in_array("-1",$ViaInsulina)){ $this->frmError["ViaInsulina"] = 1; }
				$this->frmError["MensajeError"] = "FALTAN DATOS OBLIGATORIOS";
				$this->FrmIngresarDatosGlucometria($referer_parameters,$referer_name,$datosPaciente,$datos_estacion,$Glucometria,$selectInsulina,$textInsulina,$ViaInsulina);
				return true;
			}
			elseif(is_array($checkInsulina))//estos siempre son array || is_array($textInsulina) || !in_array("-1",$ViaInsulina)
			{//con que uno sea array los dem?s deben serlo
				if(is_array($checkInsulina) && is_array($textInsulina))// && !in_array("-1",$ViaInsulina)
				{//con que uno sea array los dem?s deben serlo
					foreach($checkInsulina as $key => $value)
					{
						if(empty($textInsulina[$value]) || $ViaInsulina[$value] == -1)//
						{
							if(empty($textInsulina[$value])){ $this->frmError["TextInsulina"] = 1; }
							if($ViaInsulina[$value] == -1)  { $this->frmError["ViaInsulina"] = 1; }
							$this->frmError["MensajeError"] = "FALTAN VALORES PARA LA INSULINA SELECCIONADA";
							$this->FrmIngresarDatosGlucometria($referer_parameters,$referer_name,$datosPaciente,$datos_estacion,$Glucometria,$selectInsulina,$textInsulina,$ViaInsulina);
							return true;
						}
						else
						{
								if(!is_numeric($textInsulina[$value]))
								{
										$this->frmError["TextInsulina"] = 1;
										$this->frmError["MensajeError"] = "DIGITE NUMEROS EN ESTA CASILLA POR FAVOR!";
										$this->FrmIngresarDatosGlucometria($referer_parameters,$referer_name,$datosPaciente,$datos_estacion,$Glucometria,$selectInsulina,$textInsulina,$ViaInsulina);
										return true;
								}
						}
					}
				}
				else
				{//si uno es array los dem?s deben serlo
					if(!is_array($checkInsulina))  { $this->frmError["SelectInsulina"] = 1; }
					if(in_array("",$textInsulina))  { $this->frmError["TextInsulina"] = 1; }
					if(in_array("-1",$ViaInsulina)){ $this->frmError["ViaInsulina"] = 1; }
					$this->frmError["MensajeError"] = "LOS DATOS PARA LA INSULINA SELECCIONADA SON INSUFICIENTES";
					$this->FrmIngresarDatosGlucometria($referer_parameters,$referer_name,$datosPaciente,$datos_estacion,$Glucometria,$selectInsulina,$textInsulina,$ViaInsulina);
					return true;
				}
			}


			if(!is_numeric($Glucometria))
			{
					$this->frmError["Glucometria"] = 1;
					$this->frmError["MensajeError"] = "DIGITE NUMEROS EN ESTA CASILLA POR FAVOR!";
					$this->FrmIngresarDatosGlucometria($referer_parameters,$referer_name,$datosPaciente,$datos_estacion,$Glucometria,$selectInsulina,$textInsulina,$ViaInsulina);
					return true;
			}

			#### una vez validado puedo insertar #######
			list($dbconn) = GetDBconn();

			if(empty($Glucometria)){
				$Glucometria = "NULL";
			}
			if($textInsulina['cristalina']){
				$valor_cristalina = $textInsulina['cristalina'];
			}
			else{
				$valor_cristalina = "NULL";
			}
			if($textInsulina['nph']){
				$valor_nph = $textInsulina['nph'];
			}
			else{
				$valor_nph = "NULL";
			}
			if($ViaInsulina['cristalina']!=-1){
				$via_cristalina = $ViaInsulina['cristalina'];
			}
			else{
				$via_cristalina = "NULL";
			}
			if($ViaInsulina['nph']!=-1){
				$via_nph = $ViaInsulina['nph'];
			}
			else{
				$via_nph = "NULL";
			}

			$query = "INSERT INTO hc_control_diabetes (
																				ingreso,
																				fecha,
																				glucometria,
																				valor_cristalina,
																				via_cristalina,
																				valor_nph,
																				via_nph,
																				usuario
																			)
															VALUES (
																				".$datosPaciente['ingreso'].",
																				'".$fecha."',
																				".$Glucometria.",
																				".ltrim($valor_cristalina).",
																				".ltrim($via_cristalina).",
																				".ltrim($valor_nph).",
																				".ltrim($via_nph).",
																				".UserGetUID().")";

			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar ingresar los datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				$query = "UPDATE hc_agenda_controles
									SET estado='1'
									WHERE ingreso = ".$datosPaciente['ingreso']." AND
												estacion_id = '".$datos_estacion['estacion_id']."' AND
												control_id = '".$control."' AND
												fecha = '".$fecha."' ";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurri? un error al intentar ingresar marcar como cumplido el control<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
				else{//$dbconn->RollbackTrans();
					$dbconn->CommitTrans();
				}
			}


			/*	foreach($checkInsulina as $key => $value)
				{
					if(empty($Glucometria)){
						$Glucometria = "NULL";
					}

					$query = "INSERT INTO hc_control_diabetes (
																										ingreso,
																										fecha,
																										glucometria,
																										valor_cristalina,
																										via_cristalina,
																										valor_nph
																										via_nph,
																										usuario
																									)
																					VALUES (
																										".$datosPaciente['ingreso'].",
																										'".$fecha."',
																										".$Glucometria.",
																										".$value.",
																										".$textInsulina[$value].",
																										".$ViaInsulina[$value].",
																										".UserGetUID().")";

					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurri? un error al intentar ingresar los datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						$dbconn->RollbackTrans();
						$PuedoHacerCommit[] = 0;
						return false;
					}
				}
				if(!in_array(0,$PuedoHacerCommit))
				{
					$query = "UPDATE hc_agenda_controles
										SET estado='1'
										WHERE ingreso = ".$datosPaciente['ingreso']." AND
													estacion_id = ".$datos_estacion['estacion_id']." AND
													control_id = '".$control."' AND
													fecha = '".$fecha."' ";

					$result = $dbconn->Execute($query);
					/*if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurri? un error al intentar ingresar marcar como cumplido el control<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						$dbconn->RollbackTrans();
						return false;
					}
					else{//$dbconn->RollbackTrans();
						$dbconn->CommitTrans();
					}
				}
			}//fin ingreso insulina con o sin gluco*/
			
			
			if ($referer_name=="FrmFrecuenciaControlesP"){
				$this->Call3FrmFrecuenciaControlesP($referer_parameters["control"],$referer_parameters["descripcion"],$datos_estacion,$referer_parameters["href_action_hora"],$referer_parameters["href_action_control"],$_REQUEST['ingreso']);
			}
			elseif ($referer_name=="AgendaControlesXhoras"){
				$this->AgendaControlesXhoras($referer_parameters["estacion"]);
			}
			return true;
		}//InsertarDatosGlucometria

					
					
					
					
		//esta funcion retorna a la funcion de frecuenciacontrolesp, solo que de una manera
		//especial OJO
		function Call3FrmFrecuenciaControlesP($control,$control_descripcion,$estacion,$href_action_hora,$href_action_control,$ingreso_id)
		{
               unset($_SESSION['GLOBAL']['VECTOR']);
               if($control==8)
               {$_SESSION['GLOBAL']['VECTOR']=$_SESSION['GLOBAL']['VECT_GLUCO'];}
               elseif($control==10)
               {$_SESSION['GLOBAL']['VECTOR']=$_SESSION['GLOBAL']['VECT_NEURO'];}
               
               if(!$this->FrmFrecuenciaControlesP($control,$control_descripcion,$estacion,$href_action_hora,$href_action_control,$ingreso_id))
               {
                                   $this->error = "No se puede cargar la vista";
                                   $this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmFrecuenciaControlesP\"";
                                   return false;
               }
               return true;
		}
	
					
					
					
					
					
					
					
			/**
		*		CallFrmProgramarTurnos
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*		@return bool
		*/
		function CallFrmProgramarTurnos()
		{
			if (!$this->FrmProgramarTurnos($_REQUEST['rango'],$_REQUEST['estacion'],$_REQUEST['datos_estacion'],$_REQUEST['turnos_prgmar'],$_REQUEST['turno_fecha_rango'],$_REQUEST['href_action_hora'],$_REQUEST['href_action_control'],$_REQUEST['ingreso']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmProgramarTurnos\"";
				return false;
			}
			return true;
		}
		/**
	*
	*
	*		@Author Arley Vel?squez
	*		@access Public
	*		@return bool
	*/
	function GetTurnosEstacion($estacion_id)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

		$query="SELECT extract(hour from hora)as hora
						FROM hc_turnos_estacion
						WHERE estacion_id='$estacion_id'";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if (!$result) {
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}
		while ($data = $result->FetchRow()) {
			$horas[]=$data['hora'];
		}
		return $horas;
	}

	/**
		*		CallInsertarAgendaTurnos
		*
		*		@Author Arley Vel?squez
		*		@access Public
		*		@return bool
		*/
		function CallInsertarAgendaTurnos()
		{
			list($dbconn) = GetDBconn();
			$estacion=$_REQUEST['estacion'];
			$datos_estacion=$_REQUEST['datos_estacion'];
			$href_action_hora=$_REQUEST['href_action_hora'];

			if(!$href_action_hora)
			{$href_action_hora=$_SESSION['ESTACION']['DIRECCION']['URL'];}

			$href_action_control=$_REQUEST['href_action_control'];
			if(!$href_action_control)
			{
				$href_action_control=$_SESSION['ESTACION']['DIRECCION']['CONTROL'];
				unset($_SESSION['ESTACION']['DIRECCION']['CONTROL']);
			}
			$horas=$_POST['hora'];
			$fecha=$_POST['fecha'];
			$control=$_REQUEST['datos_estacion']['control_id'];
			$control_descripcion=$_REQUEST['datos_estacion']['control_descripcion'];
			$turno_fecha_rango=$_REQUEST['turno_fecha_rango'];
			if($_SESSION['ESTACION_CONTROL']['INGRESO'])
			{
				$ingreso_id=$_SESSION['ESTACION_CONTROL']['INGRESO'];//[duvan]
			}
			
			$estado=0;

			if (!empty($turno_fecha_rango)){
				$query="DELETE
								FROM hc_agenda_controles
								WHERE ingreso=".$datos_estacion['ingreso']." AND
											estacion_id='".$estacion['estacion_id']."' AND
											estado='$estado' AND
											control_id='$control' AND
											(
											fecha >= '".$turno_fecha_rango[0]."' AND fecha <= '".$turno_fecha_rango[1]."'
											);";
				$resultado = $dbconn->Execute($query);
				if (!$resultado)
				{
					$this->error = "Error al borrar en hc_agenda_controles.<br>";
					$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
					return false;
				}
				for ($i=0;$i<sizeof($horas);$i++) {
					 $query="SELECT fecha
									FROM hc_agenda_controles
									WHERE ingreso=".$datos_estacion['ingreso']." AND
												control_id='$control' AND
												fecha='".$horas[$i]."' AND
												estado='1'";
					$resultado = $dbconn->Execute($query);
					if ($resultado->EOF)
					{
						$query="INSERT INTO hc_agenda_controles(ingreso,estacion_id,control_id,fecha,estado)
										VALUES (".$datos_estacion['ingreso'].",'".$estacion['estacion_id']."','$control','".$horas[$i]."','$estado');";

						$resultado = $dbconn->Execute($query);
						if ($resultado)
						{
							//$this->error = "Error al insertar en hc_agenda_controles.<br>";
							//$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
							//return false;
							//preguntamos de donde viene si de glucometria,neu
							if(strtolower($_SESSION['ESTACION']['NOMBRE_CONTROL'])=='control neurologico')
							{
								$str_actividad="Actividad Neurologico:Se debe realizar el control Neurologico para este paciente en la fecha &nbsp;"."$horas[$i]";
							}
							else
							{
								$str_actividad="Actividad Glucometria:Se debe realizar el control de glucometria para este paciente en la fecha &nbsp;"."$horas[$i]";
							}
							$querys="INSERT INTO
								hc_control_apoyosd_pendientes
								(ingreso,fecha,sw_ayuno,observacion,usuario_id,fecha_registro)
								VALUES(".$datos_estacion['ingreso'].",'$horas[$i]','0','".$str_actividad."'
								,".UserGetUID().",'".date("Y-m-d H:m")."')	";
								$resulta = $dbconn->Execute($querys);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar el hc_control_apoyod_pendientes";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
								}
						}
					}
				}
			}
			else {
				for ($i=0;$i<sizeof($horas);$i++) {
					$query="INSERT INTO hc_agenda_controles(ingreso,estacion_id,control_id,fecha,estado)
									VALUES (".$datos_estacion['ingreso'].",'".$estacion['estacion_id']."','$control','".$horas[$i]."','$estado');";
					$resultado = $dbconn->Execute($query);
					if (!$resultado)
					{
						$this->error = "Error al ejecutar la consulta.<br>";
						$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
						return false;
					}

							//preguntamos de donde viene si de glucometria,neu
							if(strtolower($_SESSION['ESTACION']['NOMBRE_CONTROL'])=='control neurologico')
							{
								$str_actividad="Actividad Neurologico:Se debe realizar el control Neurologico para este paciente en la fecha &nbsp;"."$horas[$i]";
							}
							else
							{
								$str_actividad="Actividad Glucometria:Se debe realizar el control de glucometria para este paciente en la fecha &nbsp;"."$horas[$i]";
							}

							$querys="INSERT INTO
								hc_control_apoyosd_pendientes
								(ingreso,fecha,sw_ayuno,observacion,usuario_id,fecha_registro)
								VALUES(".$datos_estacion['ingreso'].",'$horas[$i]','0','".$str_actividad."'
								,".UserGetUID().",'".date("Y-m-d H:m")."')	";
								$resulta = $dbconn->Execute($querys);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar el hc_control_apoyod_pendientes";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
								}


				}
			}
			
		$this->Call2FrmFrecuenciaControlesP($control,$control_descripcion,$estacion,$href_action_hora,$href_action_control,$ingreso_id);
		return true;
	}

	
		//esta funcion retorna a la funcion de frecuenciacontrolesp, solo que de una manera
		//especial OJO
		function Call2FrmFrecuenciaControlesP($control,$control_descripcion,$estacion,$href_action_hora,$href_action_control,$ingreso_id)
		{
               unset($_SESSION['GLOBAL']['VECTOR']);
			if($control==8)
			{$_SESSION['GLOBAL']['VECTOR']=$_SESSION['GLOBAL']['VECT_GLUCO'];}
			elseif($control==10)
			{$_SESSION['GLOBAL']['VECTOR']=$_SESSION['GLOBAL']['VECT_NEURO'];}
			if(!$this->FrmFrecuenciaControlesP($control,$control_descripcion,$estacion,$href_action_hora,$href_action_control,$ingreso_id))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmFrecuenciaControlesP\"";
				return false;
			}
			return true;
		}
	
	
	
	
	
	
/**
		*		CallFrmResumenGlucometria
		*
		*		LLama la vista del resumen de glucometria
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmResumenGlucometria()
		{
			if(!$this->FrmResumenGlucometria($_REQUEST['paciente'],$_REQUEST['estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmResumenGlucometria\"";
				return false;
			}
			return true;
		}

		/**
		*		GetRangoControl
		*
		*		@Author Arley Vel?squez Castillo
		*		@access Public
		*		@return bool
		*/
		function GetRangoControl($control_id,$datos_paciente)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$controles=array();

			$query="SELECT *
								FROM  hc_rangos_controles
								WHERE control_id='$control_id' AND
											sexo = '".$datos_paciente["sexo"]."' AND
											".$datos_paciente["edad"]["anos"]." BETWEEN edad_min AND edad_max";

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado = $dbconn->Execute($query);
			if (!$resultado) {
				$this->error = "Error al buscar el tipo de control en \"hc_tipos_controles_paciente\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$controles = $resultado->FetchRow();
			if (!empty($controles["control_id"])){
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $controles;
			}
			else{
				$query="SELECT *
								FROM  hc_rangos_tipos_controles
								WHERE control_id='$control_id'";

				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado = $dbconn->Execute($query);
				if (!$resultado) {
					$this->error = "Error al buscar el tipo de control en \"hc_tipos_controles_paciente\"<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				$controles = $resultado->FetchRow();
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $controles;
			}
		}

/*
		*		CallFrmControlNeurologico
		*
		*		Llama al formulario de captura de datos del control neurologico
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmControlNeurologico()
		{//,$_REQUEST[TallasPupilaD],$_REQUEST[TallasPupilaI],$_REQUEST[ReaccionPupilaI],$_REQUEST[ReaccionPupilaD],$_REQUEST[NivelConciencia],$_REQUEST[FuerzaBrazoD],$_REQUEST[FuerzaPiernaD],$_REQUEST[FuerzaBrazoI],$_REQUEST[FuerzaPiernaI],$_REQUEST[AperturaOcular],$_REQUEST[RespVerbal],$_REQUEST[RespMotora]
			if(!$this->FrmControlNeurologico($_REQUEST['referer_parameters'],$_REQUEST["referer_name"],$_REQUEST['datos_estacion'],$_REQUEST['estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmControlNeurologico\"";
				return false;
			}
			return true;
		}

		/**
		*		GetTallasPupilas
		*
		*		Obtiene las diferentes tipos de talla de pupilas
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*		@param array,
		*		@param array
		*/
		function GetTallasPupilas($datosPaciente,$estacion)
		{
			$query = "SELECT talla_pupila_id,descripcion
								FROM hc_tipos_talla_pupila";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar consultar las tallas de pupilas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_talla_pupila'";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmControlNeurologico',array("datosPaciente"=>$datosPaciente,"estacion"=>$estacion));
					$boton = "REGRESAR";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					while ($data = $result->FetchRow()){
						$Tallas[] = $data;
					}
				}
				return $Tallas;
			}
		}//GetTallasPupilas

		/**
		*		GetReaccionPupilas
		*
		*		Obtiene los tipos de reaccion de pupila
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*		@param array,
		*		@param array
		*/
		function GetReaccionPupilas($datosPaciente,$estacion)
		{
			$query = "SELECT reaccion_pupila_id, descripcion
								FROM hc_tipos_reaccion_pupila";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar consultar las tallas de pupilas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_reaccion_pupila'";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmControlNeurologico',array("datosPaciente"=>$datosPaciente,"estacion"=>$estacion));
					$boton = "REGRESAR";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					while ($data = $result->FetchRow()){
						$Reaccion[] = $data;
					}
				}
				return $Reaccion;
			}
		}//GetReaccionPupilas

		/**
		*		GetNivelesConciencia
		*
		*		Obtiene los tipos de niveles de consciencia
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*		@param array,
		*		@param array
		*/
		function GetNivelesConciencia($datosPaciente,$estacion)
		{
			$query = "SELECT nivel_consciencia_id, descripcion
								FROM hc_tipos_nivel_consciencia";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar consultar la tabla 'hc_tipos_nivel_consciencia'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_nivel_consciencia'";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmControlNeurologico',array("datosPaciente"=>$datosPaciente,"estacion"=>$estacion));
					$boton = "REGRESAR";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					while ($data = $result->FetchRow()){
						$NivelesConciencia[] = $data;
					}
				}
				return $NivelesConciencia;
			}
		}//GetNIvelesConciencia


		/**
		*		GetTiposFuerza
		*
		*		Obtiene los tipos de fuerza
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*		@param array,
		*		@param array
		*/
		function GetTiposFuerza($datosPaciente,$estacion)
		{
			$query = "SELECT fuerza_id, descripcion
								FROM hc_tipos_fuerza";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar consultar la tabla 'hc_tipos_fuerza'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_fuerza'";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmControlNeurologico',array("datosPaciente"=>$datosPaciente,"estacion"=>$estacion));
					$boton = "REGRESAR";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					while ($data = $result->FetchRow()){
						$TiposFuerza[] = $data;
					}
				}
				return $TiposFuerza;
			}
		}//fin TiposFuerza

		/**
		*		GetTipoAperturaOcular
		*
		*		Obtiene los tipos de apertura ocular
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*		@param array,
		*		@param array
		*/
		function GetTipoAperturaOcular($datosPaciente,$estacion)
		{
			$query = "SELECT apertura_ocular_id, descripcion
								FROM hc_tipos_apertura_ocular";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar consultar la tabla 'hc_tipos_apertura_ocular'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_apertura_ocular'";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmControlNeurologico',array("datosPaciente"=>$datosPaciente,"estacion"=>$estacion));
					$boton = "REGRESAR";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					while ($data = $result->FetchRow()){
						$TipoAperturaOcular[] = $data;
					}
				}
				return $TipoAperturaOcular;
			}
		}//fin GetTipoAperturaOcular

		/**
		*		GetRespuestaVerbal
		*
		*		Obtiene los direfentes tipos de respuesta verbal
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*		@param array,
		*		@param array
		*/
		function GetRespuestaVerbal($datosPaciente,$estacion)
		{
			$query = "SELECT respuesta_verbal_id, descripcion
								FROM hc_tipos_respuesta_verbal";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar consultar la tabla 'hc_tipos_respuesta_verbal'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_respuesta_verbal'";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmControlNeurologico',array("datosPaciente"=>$datosPaciente,"estacion"=>$estacion));
					$boton = "REGRESAR";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					while ($data = $result->FetchRow())
					{
						$RespuestaVerbal[] = $data;
					}
				}
				return $RespuestaVerbal;
			}
		}//fin GetRespuestaVerbal

		/**
		*		GetRespuestaMotora
		*
		*		Selecciona los tipos de respuesta motora
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*		@param array,
		*		@param array
		*/
		function GetRespuestaMotora($datosPaciente,$estacion)
		{
			$query = "SELECT respuesta_motora_id, descripcion
								FROM hc_tipos_respuesta_motora";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar consultar la tabla 'hc_tipos_respuesta_motora'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_respuesta_motora'";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmControlNeurologico',array("datosPaciente"=>$datosPaciente,"estacion"=>$estacion));
					$boton = "REGRESAR";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					while ($data = $result->FetchRow())
					{
						$RespuestaMotora[] = $data;
					}
				}
				return $RespuestaMotora;
			}
		}//fin GetRespuestaMotora


		/**
		*		CallFrmResumenHojaNeurologica
		*
		*		Llama a la vista resument del control de hoja neurologica de un paciente
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmResumenHojaNeurologica()
		{
			if(!$this->FrmResumenHojaNeurologica($_REQUEST['paciente'],$_REQUEST['estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmResumenHojaNeurologica\"";
				return false;
			}
			return true;
		}

		/**
		*		GetResumenHojaNeurologica
		*
		*		Obtiene los registros del control de neurologia de un ingreso X
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*		@return bool-array-string
		*		@param integer => ingreso paciente
		*		@param integer => limit del query
		*		@param integer => ingreso offset del query
		*/
		function GetResumenHojaNeurologica($ingreso,$limit,$offset)
		{
			$query = "SELECT  HN.fecha,
												HN.tipo_apertura_ocular_id,
												HN.tipo_respuesta_verbal_id,
												HN.tipo_respuesta_motora_id,
												(SELECT descripcion FROM hc_tipos_talla_pupila WHERE talla_pupila_id=HN.pupila_talla_d) as tallaPupilaDer,
												(SELECT descripcion FROM hc_tipos_talla_pupila WHERE talla_pupila_id=HN.pupila_talla_i) as tallaPupilaIzq,
												(SELECT descripcion FROM hc_tipos_reaccion_pupila WHERE reaccion_pupila_id=HN.pupila_reaccion_d) as ReaccionPupilaDer,
												(SELECT descripcion FROM hc_tipos_reaccion_pupila WHERE reaccion_pupila_id=HN.pupila_reaccion_i) as ReaccionPupilaIzq,
												(SELECT descripcion FROM hc_tipos_nivel_consciencia WHERE nivel_consciencia_id=HN.tipo_nivel_consciencia_id) as NivelConciencia,
												(SELECT descripcion FROM hc_tipos_fuerza WHERE fuerza_id=HN.fuerza_brazo_d) as FuerzaBrazoDer,
												(SELECT descripcion FROM hc_tipos_fuerza WHERE fuerza_id=HN.fuerza_brazo_i) as FuerzaBrazoIzq,
												(SELECT descripcion FROM hc_tipos_fuerza WHERE fuerza_id=HN.fuerza_pierna_d) as FuerzaPiernaDer,
												(SELECT descripcion FROM hc_tipos_fuerza WHERE fuerza_id=HN.fuerza_pierna_i) as FuerzaPiernaIzq,
												(SELECT descripcion FROM hc_tipos_apertura_ocular WHERE apertura_ocular_id=HN.tipo_apertura_ocular_id) as AperturaOcular,
												(SELECT descripcion FROM hc_tipos_respuesta_verbal WHERE respuesta_verbal_id=HN.tipo_respuesta_verbal_id) as RespuestaVerbal,
												(SELECT descripcion FROM hc_tipos_respuesta_motora WHERE respuesta_motora_id=HN.tipo_respuesta_motora_id) as RespuestaMotora
								FROM hc_controles_neurologia HN
								WHERE HN.ingreso = $ingreso
								ORDER BY HN.fecha DESC
								LIMIT $limit OFFSET $offset;";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar obtener los controles del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					return "ShowMensaje";
				}
				else
				{
					while ($data = $result->FetchRow()){
						$controles[$data[fecha]][] = $data;
					}
					return $controles;
				}
			}
		}//Fin GetResumenHojaNeurologica
		/**
		*		CallFrmTransfusiones
		*
		*		Llama al formulario de captura de datos de transfusiones sanguineas
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmTransfusiones()
		{
			if(!$this->FrmTransfusiones($_REQUEST['estacion'],$_REQUEST['datos_estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmTransfusiones\"";
				return false;
			}
			return true;
		}//CallFrmTransfusiones


   /*
			esta funcion trae los datos demn los componentes sanguineos
	 */
	 function TraerComponentes()
	 {

			list($dbconn) = GetDBconn();
		  $query = "SELECT * FROM  hc_tipos_componentes ORDER BY hc_tipo_componente asc";
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al traer los componentes";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else{
				if($result->EOF){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "La tabla maestra 'tipos_id_pacientes' esta vacia ";
					return false;
				}
					$i=0;
						while (!$result->EOF) {
									$vars[$i]= $result->GetRowAssoc($ToUpper = false);
									$result->MoveNext();
									$i++;
						}
			}
			$result->Close();
			return $vars;

	 }




		/**
		*		CallFrmIngresarHemoclasificacionPaciente
		*
		*		Llama al formulario en el que se ingresan los datos de la hemoclasificaci?n del paciente
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmIngresarHemoclasificacionPaciente()
		{
			if(!$this->FrmIngresarHemoclasificacionPaciente($_REQUEST['estacion'],$_REQUEST['datos_estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmIngresarHemoClasificacionPaciente\"";
				return false;
			}
			return true;
		}//CallFrmIngresarHemoclasificacionPaciente



		/**
		*		IngresarHemoclasificacion
		*
		*		Inserta los datos del grupo sanguineo y rh de un paciente X
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function IngresarHemoclasificacion()
		{
			$tipoSanguineo = $_REQUEST['tipoSanguineo'];
			list($grupo_sanguineo,$rh) = explode(".-.",$tipoSanguineo);
			$estacion = $_REQUEST['estacion'];
			$datos_estacion = $_REQUEST['datos_estacion'];
			$laboratorio = $_REQUEST['laboratorio'];
			if($_REQUEST['grupo_sanguineo']==-1 || $_REQUEST['rh']==-1 || !$_REQUEST['fecha_examen']){
				if($_REQUEST['grupo_sanguineo']==-1){$this->frmError["grupo_sanguineo"]=1;}
				if($_REQUEST['rh']==-1){$this->frmError["rh"]=1;}
				if(!$_REQUEST['fecha_examen']){$this->frmError["fecha_examen"]=1;}
				$this->frmError["MensajeError"]="Complete los Datos Obligatorios.";
				$this->FrmIngresarHemoclasificacionPaciente($estacion,$datos_estacion);
				return true;
		  }
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query="UPDATE pacientes_grupo_sanguineo
			SET estado='0'
			WHERE tipo_id_paciente='".$datos_estacion['tipo_id_paciente']."' AND paciente_id='".$datos_estacion['paciente_id']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				$fechaExamen=ereg_replace("-","/",$_REQUEST['fecha_examen']);
				(list($diaExa,$mesExa,$anoExa)=explode('/',$fechaExamen));
				if($_REQUEST['bacteriologo']!=-1){
				(list($bacteriologo,$Tipobacteriologo)=explode('/',$_REQUEST['bacteriologo']));
				$bacteriologo="'".$bacteriologo."'";
				$Tipobacteriologo="'".$Tipobacteriologo."'";
				}else{
        $bacteriologo='NULL';
				$Tipobacteriologo='NULL';
				}
				$query="INSERT INTO pacientes_grupo_sanguineo(tipo_id_paciente,paciente_id,grupo_sanguineo,rh,laboratorio,observaciones,fecha_examen,
				tipo_id_bacteriologo,bacteriologo_id,usuario_id,fecha_registro,estado)VALUES('".$datos_estacion['tipo_id_paciente']."','".$datos_estacion['paciente_id']."',
				'".$_REQUEST['grupo_sanguineo']."','".$_REQUEST['rh']."','".$_REQUEST['laboratorio']."','".$_REQUEST['observaciones']."','$anoExa-$mesExa-$diaExa',
				$Tipobacteriologo,$bacteriologo,'".UserGetUID()."','".date('Y-m-d H:i:s')."','1')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}

      /*if(empty($laboratorio)){
				$laboratorio = "NULL";
			}
			else{
				$laboratorio = "'$laboratorio'";
			}
			$query = "INSERT INTO hc_pacientes_hemoclasificacion (
																														paciente_id,
																														tipo_id_paciente,
																														grupo_sanguineo,
																														rh,
																														fecha_registro,
																														laboratorio,
																														usuario_id
																														)
																										VALUES (
																														'".$datos_estacion['paciente_id']."',
																														'".$datos_estacion['tipo_id_paciente']."',
																														'".$grupo_sanguineo."',
																														'".$rh."',
																														'".date("Y-m-d H:i:s")."',
																														$laboratorio,
																														".UserGetUID()."
																														)";//echo "<br>$query";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar ingresar los datos sanguineos del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}*/
			$dbconn->CommitTrans();
			$this->FrmTransfusiones($estacion,$datos_estacion);
			return true;
		}//IngresarHemoclasificacion

		/**
		*		IConsultaFactor
		*
		*		Busca los facotres para la hemoclasificacion
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function ConsultaFactor(){
		list($dbconn) = GetDBconn();
		$query = "SELECT DISTINCT grupo_sanguineo FROM hc_tipos_sanguineos";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
	  return $vars;
  }
  /**
		*		IConsultaFactor
		*
		*		Bacteriologos del Banco de Sangre
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
	function TotalBacteriologos(){

		list($dbconn) = GetDBconn();
		$query="SELECT b.tipo_id_tercero,b.tercero_id,b.nombre FROM banco_sangre_profesionales a,profesionales b
		WHERE a.tipo_id_tercero=b.tipo_id_tercero AND a.tercero_id=b.tercero_id AND b.tipo_profesional='6' AND b.estado=1 ORDER BY nombre";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF) {
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
	}




		/**
		*		GetGruposSanguineos
		*
		*		Obtiene los datos de los diferentes grupos sanguineos
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool - array
		*/
		function GetGruposSanguineos()
		{
			$query = "SELECT grupo_sanguineo, rh
								FROM hc_tipos_sanguineos";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar obtener los tipos de grupos sanguineos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while (!$result->EOF){
					$grupoSanguineos[] = $result->FetchRow();
				}
				return $grupoSanguineos;
			}
			return true;
		}//GetGruposSanguineso


/**
		*		GetGrupoSanguineoPaciente
		*
		*		Obtiene los datos del grupo sanguineo del paciente
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*/
		function GetGrupoSanguineoPaciente($estacion,$datos_estacion)
		{
			$query =  "SELECT grupo_sanguineo,
												rh,
												fecha_registro,
												laboratorio,
												usuario_id
								FROM pacientes_grupo_sanguineo
								WHERE paciente_id = '".$datos_estacion[paciente_id]."' AND
											tipo_id_paciente = '".$datos_estacion[tipo_id_paciente]."' AND
											estado='1';";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar obtener el G.S. y RH del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					return "ShowMensaje";
				}
				else
					return $result->FetchRow();
			}
		}//GetGrupoSanguineoPaciente

/**
		*		GetTransfusiones
		*
		*		Obtiene los registros de transfusiones
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool - array - string
		*		@param integer => numero de ingreso del paciente
		*/
		function GetTransfusiones($ingreso)
		{
			$query = "SELECT a.*,b.componente
								FROM hc_control_transfusiones a,hc_tipos_componentes b WHERE
								a.ingreso='$ingreso' AND a.hc_tipo_componente=b.hc_tipo_componente
								ORDER BY fecha DESC";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar obtener los registros de transfusiones del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					return "ShowMensaje";
				}
				else
				{
					while ($data = $resultado->FetchRow()) {
						$transfusionesPaciente[] = $data;
					}
					return $transfusionesPaciente;
				}
			}
		}//GetTransfusiones

		/**
		*		CallFrmInsertarReaccionAdversa
		*
		*		Llama al formulario que captura la reaccion adversa de una transfusion sanguinea
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmInsertarReaccionAdversa()
		{
			if(!$this->FrmInsertarReaccionAdversa($_REQUEST['ingreso'],$_REQUEST['datos'],$_REQUEST['estacion'],$_REQUEST['datos_estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmInsertarReaccionAdversa\"";
				return false;
			}
			return true;
		}//CallFrmInsertarReaccionAdversa

/**
		*		InsertarReaccionAdversa
		*
		*		Inserta la reaccion advera de una transfusion sanguinea
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function InsertarReaccionAdversa()
		{
			$reaccionAdversa = $_REQUEST['reaccionAdversa'];
			$seleccion = $_REQUEST['sel'];
			$datos = $_REQUEST['datos'];
			$estacion = $_REQUEST['estacion'];
			$datos_estacion = $_REQUEST['datos_estacion'];
			$ingreso= $_REQUEST['ingreso'];

			if(empty($reaccionAdversa) || empty($datos) || empty($seleccion))
			{
				$this->frmError["MensajeError"] = "FALTAN DATOS OBLIGATORIOS";
				$this->FrmInsertarReaccionAdversa($ingreso,$datos,$estacion,$datos_estacion);
				return true;
			}

		/*	$query = "UPDATE hc_control_transfusiones
								SET reaccion_adversa = '$reaccionAdversa'
								WHERE ingreso = ".$datos[ingreso]." AND
											fecha = '".$datos[fecha]."'";//ECHO "<BR> $query";*/

			$query="INSERT INTO hc_control_transfusiones_notas_reaccion_adversas
							(ingreso,
							 fecha,
							 usuario_id,
							 observacion,
							 fecha_registro,
							 sw_reaccion
							 	)
								VALUES
								(
									".$datos[ingreso].",
									'".$datos[fecha]."',
									".UserGetUID().",
									'$reaccionAdversa',
									now(),
									'$seleccion'
								)";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar ingresar la reacci?n adversa<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else{
				$this->FrmTransfusiones($estacion,$datos_estacion);
			}
			return true;

		}//InsertarReaccionAdversa



		function GetFechasHcApoyos($ingreso)
		{
			list($dbconn) = GetDBconn();
			$query = "SELECT fecha
								FROM hc_control_apoyosd_pendientes WHERE ingreso='$ingreso'
								AND usuario_confirma ISNULL
								AND fecha_registro_confirma ISNULL";

			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al traer las fechas de los apoyos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      	return false;
			}

			while (!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}

				return $var;
		}





		function BuscarPacientesConsulta_Urgencias($datos_estacion)
		{

				//GLOBAL $ADODB_FETCH_MODE;
        //$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        list($dbconn) = GetDBconn();

				//cambio dar
				//se agrego a.sw_estado=7 paciente de alta de consulta de urg
				//pero q tienen procesos en la estacion
				//fin cambio
			$sql="select c.paciente_id, c.tipo_id_paciente, c.primer_nombre ,
				 c.segundo_nombre , c.primer_apellido ,c.segundo_apellido , e.nivel_triage_id, 				 d.hora_llegada, e.tiempo_atencion, b.ingreso,
				 f.evolucion_id, to_char(f.fecha,'YYYY-MM-DD HH24:MI') as fecha,
				 f.usuario_id, h.nombre, a.estacion_id, d.nivel_triage_id, d.plan_id,
				 d.triage_id, d.punto_triage_id, d.punto_admision_id, d.sw_no_atender,
				 i.numerodecuenta, z.egresos_no_atencion_id, a.sw_estado
				 FROM pacientes_urgencias as a join
				 ingresos as b  on (a.ingreso=b.ingreso and
				 a.estacion_id='".$datos_estacion[estacion_id]."') join
				 pacientes as c on (b.paciente_id=c.paciente_id and
				 b.tipo_id_paciente=c.tipo_id_paciente and b.estado='1') left join triages as d
				 on (a.triage_id=d.triage_id) left join niveles_triages as e on
				 (d.nivel_triage_id=e.nivel_triage_id and e.nivel_triage_id !=0 and
				  d.sw_estado!='9') left join hc_evoluciones as f on (b.ingreso=f.ingreso and
					f.estado='1') left join profesionales_usuarios as g on
					(f.usuario_id=g.usuario_id) left join profesionales as h on
					(g.tercero_id=h.tercero_id and g.tipo_tercero_id=h.tipo_id_tercero) left
					 join cuentas as i on(a.ingreso=i.ingreso and i.estado='1') left join
					 egresos_no_atencion as z on(z.ingreso=b.ingreso or z.triage_id=d.triage_id)
					  where a.sw_estado in('1','7')
						--and e.nivel_triage_id ISNULL --tener esto en cuenta
						order by e.indice_de_orden, d.hora_llegada;";

        $result = $dbconn->Execute($sql);
        $i=0;
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al traer lospacientes de consulta de urgencias";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

				if($result->EOF)
				{
					return "ShowMensaje";
				}

				while ($data = $result->FetchNextObject())
				{

					$x = $this->get_cuenta_x_ingreso($data->INGRESO);
					$Pacientes[$i][0]  = $data->PRIMER_NOMBRE." ".$data->SEGUNDO_NOMBRE;
					$Pacientes[$i][1]  = $data->PRIMER_APELLIDO." ".$data->SEGUNDO_APELLIDO;
					$Pacientes[$i][2]  = $data->PACIENTE_ID;
					$Pacientes[$i][3]  = $data->TIPO_ID_PACIENTE;
					$Pacientes[$i][4]  = $data->INGRESO;
					$Pacientes[$i][5]  = $data->ORDEN_HOSP;
					$Pacientes[$i][6]  = $x[0]; //CUENTA
					$Pacientes[$i][7]  = $x[1]; //PLAN
					$Pacientes[$i][8]  = $data->TRASLADO;
					$Pacientes[$i][9]  = $desc->fields[0];//descripcion ee origen
					$Pacientes[$i][10] = $data->ESTACION_ORIGEN;//id estacion origen
					$Pacientes[$i][11] = $data->SW_ESTADO;
					$i++;
				}
				$result->Close();
				return $Pacientes;

		}

			/**************OJO ESTA SE VA PARA EL MOD ESTACIONE_PACIENTES***************/////
	/**
	*		GetPacientesPendientesXHospitalizar => Obtiene los pendientes por hospitalizar
	*
	*		llamado desde vista 1=> el subproceso1->"ingresar paciente" del proceso "ingreso de pacientes a la estaci?n de enfermer?a"
	*		1.1.1.1.H => GetPacientesPendientesXHospitalizar()
	*		Obtiene los pacientes pendientes por ingresar al dpto almacenados en la tabla "pendientes_x_hospitalizar"
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool-array-string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetPacientesPendientesXHospitalizar($datos_estacion)
	{

	  if(!$datos_estacion)
		{
			$datos_estacion=$_REQUEST['datos'];
		}
		$query = "SELECT 	paciente_id,
											tipo_id_paciente,
											primer_apellido,
											segundo_apellido,
											primer_nombre,
											segundo_nombre,
											ing_id,
											ee_destino,
											orden_hosp,
											traslado,
											estacion_origen,
											plan_id,
											numerodecuenta
							FROM pacientes,
									(	SELECT  I.ingreso as ing_id,
														I.paciente_id as pac_id,
														I.tipo_id_paciente as tipo_id,
														P.estacion_destino as ee_destino,
														P.orden_hospitalizacion_id as orden_hosp,
														P.traslado as traslado,
														P.estacion_origen as estacion_origen,
														x.plan_id,
														x.numerodecuenta
										FROM 	ingresos I,
													cuentas x,
													pendientes_x_hospitalizar P
										WHERE I.ingreso = P.ingreso 
													AND I.ingreso=x.ingreso
													AND x.estado ='1' AND
													P.estacion_destino = '".$datos_estacion[estacion_id]."'
									) as HOLA
							WHERE paciente_id = pac_id AND
										tipo_id_paciente = tipo_id AND
										ee_destino = '".$datos_estacion[estacion_id]."'
							ORDER BY  primer_nombre,
												segundo_nombre,
												primer_apellido,
            segundo_apellido";//pacientes_x_ingreso_x_pxh

		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener los pacientes pendientes por hospitalizar<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}

		if($result->EOF)
		{
			return "ShowMensaje";
		}

		$i=0;
		while ($data = $result->FetchNextObject())
		{
  		$query = "SELECT descripcion
								FROM estaciones_enfermeria
								WHERE estacion_id = $data->ESTACION_ORIGEN";
			$desc = $dbconn->Execute($query);

			$x = $this->get_cuenta_x_ingreso($data->ING_ID);
			$Pacientes[$i][0]  = $data->PRIMER_NOMBRE." ".$data->SEGUNDO_NOMBRE;
			$Pacientes[$i][1]  = $data->PRIMER_APELLIDO." ".$data->SEGUNDO_APELLIDO;
			$Pacientes[$i][2]  = $data->PACIENTE_ID;
			$Pacientes[$i][3]  = $data->TIPO_ID_PACIENTE;
			$Pacientes[$i][4]  = $data->ING_ID;
			$Pacientes[$i][5]  = $data->ORDEN_HOSP;
			$Pacientes[$i][6]  = $x[0]; //CUENTA
			$Pacientes[$i][7]  = $x[1]; //PLAN
			$Pacientes[$i][8]  = $data->TRASLADO;
			$Pacientes[$i][9]  = $desc->fields[0];//descripcion ee origen
			$Pacientes[$i][10] = $data->ESTACION_ORIGEN;//id estacion origen
			$i++;
 	 	}
  	$result->Close();
		return $Pacientes;
	}//fin GetPacientesPendientesXHospitalizar







	/**
	*		GetPacientesPendientesXHospitalizar_Plantilla => Obtiene los pendientes por hospitalizar
	*
	*   Se diferencia de la funcion anterior, en la forma de sacar el vector.
	*		@Author JAJA
	*		@access Public
	*		@return bool-array-string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetPacientesPendientesXHospitalizar_Plantilla($datos_estacion,$spy,$datoscenso,$ingreso)
	{

	  	if(!$datos_estacion)
		{
			$datos_estacion=$_REQUEST['datos'];
		}
		if(!empty($ingreso))
		{
			$sql="AND I.ingreso='$ingreso'";
		}
		$query = "SELECT 	paciente_id,
                              tipo_id_paciente,
                              primer_apellido,
                              segundo_apellido,
                              primer_nombre,
                              segundo_nombre,
                              ingreso,
                              ee_destino,
                              orden_hosp,
                              traslado,
                              estacion_origen
                         FROM pacientes,
                         (	SELECT  I.ingreso,
                                                  I.paciente_id as pac_id,
                                                  I.tipo_id_paciente as tipo_id,
                                                  P.estacion_destino as ee_destino,
                                                  P.orden_hospitalizacion_id as orden_hosp,
                                                  P.traslado as traslado,
                                                  P.estacion_origen as estacion_origen
										FROM 	ingresos I,
                                                            cuentas X,
                                                            pendientes_x_hospitalizar P
										WHERE I.ingreso = P.ingreso 
										AND X.ingreso=I.ingreso
										AND X.estado='1'
										$sql
										AND	P.estacion_destino = '".$datos_estacion[estacion_id]."'
                         ) as HOLA
                         WHERE paciente_id = pac_id AND
                                        tipo_id_paciente = tipo_id AND
                                        ee_destino = '".$datos_estacion[estacion_id]."'
                         ORDER BY  primer_nombre,
                                   segundo_nombre,
                                   primer_apellido,
                                   segundo_apellido";//pacientes_x_ingreso_x_pxh
	
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar consultar los pacientes en las estacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}

			//esta variable esporsi se llama desde otro lado
			if($spy==1)
			{
                    if($result ->RecordCount() <=0)
                    {
                         return $datoscenso;
                    }
                    while ($data = $result->FetchRow()){
                         $datoscenso[hospitalizacion][] = $data;//$i++;
                    }
                    return $datoscenso;
			}
			else
			{
                    if($result->EOF)
                    {
                         return "";
                    }
     
                    while ($data = $result->FetchRow())
                    {
                              $datoscenso[ingresar][] = $data;
                    }
                    return $datoscenso;
			}
		}//fin GetPacientesPendientesXHospitalizar








	/*
	**
	*		GetViaIngresoPaciente
	*
	*		Con el ingreso del paciente obtengo la via de ingreso
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool-array-string
	*		@param integer => numero de ingreso
	*/
	function GetViaIngresoPaciente($ingreso)
	{
		$query = "SELECT I.via_ingreso_id, VI.via_ingreso_nombre
							FROM ingresos I,
									 vias_ingreso VI
							WHERE I.ingreso = $ingreso AND
										VI.via_ingreso_id =  I.via_ingreso_id;";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurri? un error al intentar consultar la v?a de ingreso del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if(!$result->EOF)
			{
				$viaIngreso = $result->FetchRow();
				return $viaIngreso;
			}
			else{
				return "ShowMensaje";
			}
		}
	}//GetViaIngresoPaciente





	/**
	*		get_cuenta_x_ingreso
	*
	*		llamado desde el subproceso1->"Asignar cama" del proceso "ingreso de pacientes a la estaci?n de enfermer?a"
	*		1.1.1.2.H => get_cuenta_x_ingreso()
	*		Obtiene la cuenta del paciente con el numero de ingreso
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array
	*		@param integer => ingreso del paciente
	*/
	function get_cuenta_x_ingreso($ingreso)
	{
		$query = "SELECT C.numerodecuenta, C.plan_id
							FROM cuentas C
							JOIN planes P
							ON  C.ingreso = '".$ingreso."' AND
									P.plan_id = C.plan_id";
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al obtener el numero de cuenta del ingreso<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}

		if($result->EOF)
		{
			$this->error = "Error al cargar el modulo";
			$this->mensajeDeError = "No se pudo obtener el plan de la cuenta del paciente";
			return false;
		}
		else
		{
			$x[0] = $result->fields[0]; //cuenta
			$x[1] = $result->fields[1]; //plan
			return $x;
		}
	}// fin get_cuenta_x_ingreso


		function GetConteo_Hc_control_apoyod($ingreso)
		{
			$query = "SELECT hc_control_apoyod($ingreso)";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al traer los controles de apoyos";
				$this->mensajeDeError = "Error al obtener el numero de cuenta del ingreso<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			return $result->fields[0];
		}




		function GetPacientesControles($Estacion)
		{

      if(empty($Estacion))
			{
				return "ShowMensaje";
			}
//[cambiar query por proxima lentitud duvan].
				$query = "SELECT  MH.fecha_ingreso,
												MH.cama,
												B.pieza,
												C.ingreso,
												C.numerodecuenta,
												D.paciente_id,
												D.tipo_id_paciente,
												E.primer_nombre,
												E.segundo_nombre,
												E.primer_apellido,
												E.segundo_apellido,
            --hc_control_apoyod(C.ingreso),
												MH.ingreso_dpto_id

								FROM movimientos_habitacion AS MH,
										(
											SELECT  ID.ingreso_dpto_id,
															ID.numerodecuenta

											FROM  ingresos_departamento ID,
														estaciones_enfermeria EE
											WHERE ID.estado = '1' AND
														EE.estacion_id = ID.estacion_id AND
														EE.estacion_id = '$Estacion'
										) AS A,
											camas B,
											cuentas C,
											ingresos D,
											pacientes E

								WHERE MH.ingreso_dpto_id = A.ingreso_dpto_id AND
											MH.fecha_egreso IS NULL AND
											MH.cama = B.cama AND
											C.numerodecuenta = A.numerodecuenta AND
											C.ingreso = D.ingreso AND
											C.estado = '1' AND
											D.paciente_id = E.paciente_id AND
											D.tipo_id_paciente = E.tipo_id_paciente

								ORDER BY MH.cama, B.pieza";


			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurri? un error al intentar consultar los pacientes en las estacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while ($data = $result->FetchRow())
				{
						$datoscenso[hospitalizacion][] = $data;
				}
			}

			if(!$datoscenso){
				return "ShowMensaje";
			}
			$result->Close();
  		return $datoscenso;

		}//GetPacientesControles




		function RevisarSi_Es_Egresado($ingreso_dpto)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT estado FROM egresos_departamento
							WHERE
							--estado = '1'
						  ingreso_dpto_id='$ingreso_dpto'
							AND tipo_egreso != '4'
							AND	estado != '2'
							";
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar el sql ingresos_departamento";
				$this->mensajeDeError = "---";
				return false;
			}
   		$info[0]=$result->RecordCount();//sabemos el conteo de los registros
			$info[1]=$result->fields[0];//guardamos la informaci?n del estado del egreso
			$result->Close();
			return $info;

		}


	/*		Borrar_ControlNeuro
	*
	*		Borra los registros de la tabla de Control Neurologico
	*
	*		@Author Tizziano Perea O.
	*		@access Public
	*		@return bool-array-string
	*		@param array,
	*		@param array
	*/

	function Borrar_ControlNeuro()
	{
		$datos_paciente=$_REQUEST['paciente'];
		$estacion=$_REQUEST['estacion'];
		list($dbconn) = GetDBconn();
  	$sql="DELETE FROM  hc_controles_neurologia
			  WHERE ingreso=".$datos_paciente[ingreso]."
			  AND fecha_registro='".$_REQUEST['fechar']."';";

		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->frmError["MensajeError"] = "NO SE PUDO BORRAR EL REGISTRO";
			$this->FrmResumenHojaNeurologica($datos_paciente,$estacion);
			return false;
		}
		$this->frmError["MensajeError"] = "REGISTRO BORRADO SATISFACTORIAMENTE";
		$this->FrmResumenHojaNeurologica($datos_paciente,$estacion);
		return true;
	}



		/**
	*		Listar_ControlesNeurologicos
	*
	*		Lista los resultados de las inserciones
	*
	*		@Author Tizziano Perea O.
	*		@access Public
	*		@return bool-array-string
	*		@param array,
	*		@param array
	*/

	function Listar_ControlesNeurologicos($ingreso)
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo'.$pfj]))
		{
			 $query = "SELECT count(*)
			 		   FROM hc_controles_neurologia
					   WHERE ingreso='".$ingreso."';";

			$resulta = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'.$pfj];
			if($Of > $this->conteo)
			{
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}

		$query = "SELECT A.*, B.descripcion
				 FROM hc_controles_neurologia
				 AS A left join hc_tipos_nivel_consciencia AS B
				 on (B.nivel_consciencia_id=A.tipo_nivel_consciencia_id)
				 WHERE ingreso='".$ingreso."'
				 ORDER BY fecha_registro
				 DESC ";
				 //.$this->limit." OFFSET $Of;";

		$resulta = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$VectorControl[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $VectorControl;
	}

	/**
	*		GetReservasPacientes
	*
	*		Lista las reservas de compoenentes sanguineos
	*
	*		@Author LORENA ARAGON G
	*		@access Public
	*		@return bool-array-string
	*		@param array,
	*		@param array
	*/

	function GetReservasPacientes($tipoId,$PacienteId){
    list($dbconn) = GetDBconn();
		$query="SELECT a.solicitud_reserva_sangre_id,a.ingreso_bolsa_id,a.numero_alicuota,a.solicitud_reserva_sangre_id,a.tipo_componente_id,d.componente,
		d.componente,b.bolsa_id,b.sello_calidad,b.grupo_sanguineo,b.rh,b.fecha_vencimiento,
		(SELECT 1 FROM banco_sangre_entrega_bolsas_enrega_confirmacion w WHERE w.ingreso_bolsa_id=a.ingreso_bolsa_id AND w.numero_alicuota=a.numero_alicuota) as despachada,
    (SELECT x.fecha_recepcion FROM banco_sangre_recepcion_bolsas x WHERE x.ingreso_bolsa_id=a.ingreso_bolsa_id AND x.numero_alicuota=a.numero_alicuota) as recibida_fecha,
		(SELECT nombre FROM banco_sangre_recepcion_bolsas y,system_usuarios z WHERE y.ingreso_bolsa_id=a.ingreso_bolsa_id AND y.numero_alicuota=a.numero_alicuota AND y.usuario_id=z.usuario_id) as recibida_usuaio
		FROM banco_sangre_entrega_bolsas a,banco_sangre_bolsas b,banco_sangre_bolsas_alicuotas c,
		hc_tipos_componentes d
		WHERE a.ingreso_bolsa_id=b.ingreso_bolsa_id AND a.ingreso_bolsa_id=c.ingreso_bolsa_id AND
		a.numero_alicuota=c.numero_alicuota AND a.tipo_componente_id=d.hc_tipo_componente AND c.sw_estado='5' AND
		a.tipo_id_paciente='$tipoId' AND a.paciente_id='$PacienteId'";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($resulta->RecordCount()>0){
        while(!$resulta->EOF){
					$vars[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
			}
		}
		return $vars;
	}

	/**
	*		LlamaRegistroRecepcionBolsa
	*
	*		Lamma ka forma que va a registrar las observaciones de la recepcion de la bolsa
	*
	*		@Author LORENA ARAGON G
	*		@access Public
	*		@return bool-array-string
	*		@param array,
	*		@param array
	*/

	function LlamaRegistroRecepcionBolsa(){

    $this->FormaRegistroRecepcionBolsa($_REQUEST['datos_estacion'],$_REQUEST['estacion'],$_REQUEST['IngresoBolsaId'],$_REQUEST['numeroAlicuota'],$_REQUEST['BolsaId']);
		return true;
	}

	/**
	*		RegistrarDatosRecepcionBolsa
	*
	*		Inserta las observaciones de la recepcion de la bolsa
	*
	*		@Author LORENA ARAGON G
	*		@access Public
	*		@return bool-array-string
	*		@param array,
	*		@param array
	*/

	function GuardarRegistroRecepcionBolsa(){

		list($dbconn) = GetDBconn();
		if($_REQUEST['guardarDatos']){
			$query="INSERT INTO banco_sangre_recepcion_bolsas (ingreso_bolsa_id,numero_alicuota,observaciones,fecha_recepcion,usuario_id)
			VALUES('".$_REQUEST['IngresoBolsaId']."','".$_REQUEST['numeroAlicuota']."','".$_REQUEST['observaciones']."','".date("Y-m-d H:i:s")."','".UserGetUID()."')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		$this->FrmTransfusiones($_REQUEST['estacion'],$_REQUEST['datos_estacion']);
		return true;
	}
	
	function VerificacionPaciente_ECirugia($numeroCuenta){
		list($dbconn) = GetDBconn();
		$query="SELECT verificacionpaciente_ecirugia($numeroCuenta)";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			return $result->fields[0];
		}
	}


}//fin de la clase
?>
