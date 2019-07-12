
<?php

/**
 * $Id: app_EstacionE_Pacientes_user.php,v 1.21 2005/09/26 18:10:51 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Estacion de Enfermeria (parte del tratamiento del paciente) 
 */




/**
* Modulo de EstacionE_Pacientes (PHP).
*
//*
*
* @author  <@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_EstacionE_Pacientes_user.php
*
//*
**/

class app_EstacionE_Pacientes_user extends classModulo
{
	var $uno;//para los errores

	function app_EstacionE_Pacientes_user()
	{
		return true;
	}

	function main()
	{
		return true;
	}






/***********************ESTA FUNCION DEBES ESTAR EN ESTACIONE-PACIENTES**************************////////
		/**
		*		CallListPacientesPorIngresar => Llama a la vista ListPacientesPorIngresar
		*
		*		llama a la vista que muestra el listado de pacientes a ingresar
		*
		*		@Author Rosa María Angel D.
		*		@access Public
		*		@return bool
		*/
		function CallListPacientesPorIngresar()
		{
			if(!$datos_estacion){
				$datos_estacion = $_REQUEST['datos_estacion'];
				$tipo= $_REQUEST['tipo_id_paciente'];
				$pac= $_REQUEST['paciente_id'];
			}

			if(!$this->ListPacientesPorIngresar($datos_estacion,$tipo,$pac))//1.1.1.H
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"CallListPacientesPorIngresar\"";
				return false;
			}
			return true;
		}//FIN CallListPacientesPorIngresar
/***********************ESTA FUNCION DEBES ESTAR EN ESTACIONE-PACIENTES**************************////////





//*********************esto debe ir en estacionE_pacientes***************************////////
		/**
		*		CallListPacientesPorEgresar => Llama a la vista ListPacientesPorEgresar
		*
		*		Muestra un listado de los pacientes con orden de egreso pendientes
		*
		*		@Author		Rosa María Angel D.
		*		@access Public
		*		@return bool
		*/
		function CallListPacientesPorEgresar()
		{
			if(!$datos_estacion){
				$datos_estacion = $_REQUEST['datos_estacion'];
				$tipo= $_REQUEST['tipo_id_paciente'];
				$pac= $_REQUEST['paciente_id'];
				$cama= $_REQUEST['cama'];
			}

			if(!$this->ListPacientesPorEgresar($datos_estacion,$tipo,$pac,$cama))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"ListPacientesPorEgresar\"";
				return false;
			}
			return true;
		}//FIN CallListPacientesPorIngresar
//*********************esto debe ir en estacionE_pacientes***************************////////




	/*****************esta funcion debe ir a estacionE_pacientes ******************************/
	/**
	*		VerificaExistenciaBodegaPaciente()
	*
	*		Consulta los medicamentos existentes en la bodega del paciente
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool - array
	*		@param integer corresponde al ingreso del paciente
	*/
	function VerificarExistenciasBodegaPaciente($ingreso)
	{
		$datosRemision = $_REQUEST['datosRemision'];
		$datos_estacion = $_REQUEST['datos_estacion'];

		$query = "SELECT BP.medicamento_id,
										 BP.cantidad_acum,
										 BP.ingreso
							FROM hc_bodega_paciente BP
							WHERE BP.ingreso = $ingreso";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar las existencias de la bodega del paciene.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
					$bodegaPaciente[] = $data;
				}
				return $bodegaPaciente;
			}
		}
	}//VerificaExistenciaBodegaPaciente()

		/*****************esta funcion debe ir a estacionE_pacientes ******************************/


	/*****************esta funcion debe ir a estacionE_pacientes ******************************/
	/**
	*		VerificaSolicitudesDevolucionPendientes($ingreso)
	*
	*		Verifica la existencia de solicitudes de depacho de medicamentos e isnumos que
	*		esten pendientes por ser atendidos por la bodega
	*
	*		@param integer ingreso del paciente
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function VerificaSolicitudesDevolucionPendientes($ingreso)
	{
		$query = "SELECT documento
							 FROM inv_solicitudes_devolucion
							 WHERE ingreso = $ingreso AND
										 estado = '0'";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar el estado de las solicitudes de devoluciones.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			else
			{
				return "DevPendiente";
			}
		}
	}//VerificaSolicitudesDevolucionPendientes($ingreso)

/*****************esta funcion debe ir a estacionE_pacientes ******************************/


/*****************esta funcion debe ir a estacionE_pacientes ******************************/
	/**
	*		GetDptoRemision
	*
	*		Consulta el departamento al cual el medico solicitó el traslado del paciente
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool-string-array
	*		@param integer numero de egreso del departamento
	*/
	function GetDptoRemision($egreso_dpto_id)
	{
		$query = "SELECT RI.departamento,
										 D.descripcion
							FROM remisiones_internas RI,
									 departamentos D
							WHERE RI.egreso_dpto_id = $egreso_dpto_id AND
										D.departamento = RI.departamento";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener el departamento de remision.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
					$dptoRemision = $data;
				}
				return $dptoRemision;
			}
		}
	}//GetDptoRemision



	function ReportePacientesEstacion($estacion)
	{
				if(!$estacion)
				{
						$estacion=$_REQUEST['estacion'];
				}
				$query = "SELECT Z.tipo_id_paciente,
												Z.paciente_id,
												primer_apellido,
												segundo_apellido,
												primer_nombre,
												segundo_nombre,
												Z.cuenta,
												Z.plan,
												Z.ing,
												Z.ing_dpto,
												Z.fec_ing,
												Z.orden_hosp
									FROM pacientes,
											( SELECT 	IC.ingreso,
																tipo_id_paciente,
																paciente_id,
																IC.plan_id as plan,
																IC.cuenta,
																IC.ing_dpto,
																IC.fec_ing,
																IC.ingreso as ing,
																IC.orden_hospitalizacion_id as orden_hosp
												FROM ingresos,
														(SELECT 	C.plan_id,
																			C.ingreso,
																			I.numerodecuenta as cuenta,
																			I.ingreso_dpto_id as ing_dpto,
																			I.fecha_ingreso as fec_ing,
																			I.orden_hospitalizacion_id
															FROM ingresos_departamento I, cuentas C
															WHERE I.estacion_id = '".$estacion."' AND
																		I.numerodecuenta = C.numerodecuenta AND
																		C.estado = '1'
															)	AS IC
												WHERE ingresos.ingreso=IC.ingreso
											)	AS Z
									WHERE pacientes.paciente_id = Z.paciente_id AND
												pacientes.tipo_id_paciente = Z.tipo_id_paciente
									ORDER BY primer_nombre, segundo_nombre, primer_apellido, segundo_apellido
									";
				list($dbconn) = GetDBconn();
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Erroa al intentar obtener los pacientes de la estacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
						$arr[$i]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
						$i++;
				}

					/*IncludeLib("reportes/estacion_enfermeria"); //car
				  GenerarListadoEstacion($var);
					return $var;*/
				//	return $arr;


	/*		if (IncludeFile("reports/html/Listado_PacientesHTM")) {
				$this->error = "No se pudo inicializar la Clase de Reportes html";
				$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
				return false;
		}*/
		//siempre
	 Include_once("reports/html/Listado_PacientesHTM.report.php");
	 $report=new Listado_PacientesHTM_report;
	 $ruta=$report->CrearReporte($arr);
	 return $ruta;

	}




		/*****************esta funcion debe ir a estacionE_pacientes ******************************/

   	/*****************esta funcion debe ir a estacionE_pacientes ******************************/
	/**
	*		GetPacientesEstacion
	*
	*		subproceso 3->"CambioCama" del proceso "ingreso de pacientes a la estación de enfermería"
	*		1.3.1.1.H => GetPacientesEstacion lista los pacientes de la estacion
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool-array-string
	*		@param integer => id de la estacion
	*/
	function GetPacientesEstacion($estacion)
	{
    if(!$estacion)
		{
				$estacion=$_REQUEST['estacion'];
		}
		$query = "SELECT Z.tipo_id_paciente,
										 Z.paciente_id,
										 primer_apellido,
										 segundo_apellido,
										 primer_nombre,
										 segundo_nombre,
										 Z.cuenta,
										 Z.plan,
										 Z.ing,
										 Z.ing_dpto,
										 Z.fec_ing,
										 Z.orden_hosp
							FROM pacientes,
									( SELECT 	IC.ingreso,
														tipo_id_paciente,
														paciente_id,
														IC.plan_id as plan,
														IC.cuenta,
														IC.ing_dpto,
														IC.fec_ing,
														IC.ingreso as ing,
														IC.orden_hospitalizacion_id as orden_hosp
										FROM ingresos,
												 (SELECT 	C.plan_id,
																	C.ingreso,
																	I.numerodecuenta as cuenta,
																	I.ingreso_dpto_id as ing_dpto,
																	I.fecha_ingreso as fec_ing,
																	I.orden_hospitalizacion_id
													FROM ingresos_departamento I, cuentas C
													WHERE I.estacion_id = '".$estacion."' AND
																I.numerodecuenta = C.numerodecuenta AND
																C.estado = '1'
													)	AS IC
										WHERE ingresos.ingreso=IC.ingreso
									)	AS Z
							WHERE pacientes.paciente_id = Z.paciente_id AND
										pacientes.tipo_id_paciente = Z.tipo_id_paciente
							ORDER BY primer_nombre, segundo_nombre, primer_apellido, segundo_apellido
							";
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Erroa al intentar obtener los pacientes de la estacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}

		$i=0;

		if($result->EOF)
		{
			return "ShowMensaje";
		}
			while ($data = $result->FetchNextObject())
			{
				$querys = " SELECT C.cama, C.pieza
										FROM camas C, movimientos_habitacion MH
										WHERE C.cama =  MH.cama AND
													MH.numerodecuenta = $data->CUENTA
													AND MH.fecha_egreso IS NULL";
				$resultado = $dbconn->Execute($querys);

				$Pacientes[$i][0]  = $data->PRIMER_NOMBRE." ".$data->SEGUNDO_NOMBRE;
				$Pacientes[$i][1]  = $data->PRIMER_APELLIDO." ".$data->SEGUNDO_APELLIDO;
				$Pacientes[$i][2]  = $data->PACIENTE_ID;
				$Pacientes[$i][3]  = $data->TIPO_ID_PACIENTE;
				$Pacientes[$i][4]  = $data->ING;
				$Pacientes[$i][5]  = $data->ORDEN_HOSP;
				$Pacientes[$i][6]  = $data->CUENTA;
				$Pacientes[$i][7]  = $data->PLAN;
				$Pacientes[$i][8]  = $resultado->fields[0];//cama
				$Pacientes[$i][9]  = $resultado->fields[1];//pieza
				$Pacientes[$i][10] = $data->ING_DPTO;
				$Pacientes[$i][11] = $data->FEC_ING;
				$i++;
			}
  return $Pacientes;
	}//GetPacientesEstacion
	/*****************esta funcion debe ir a estacionE_pacientes ******************************/


	function VerificarSalida($ingreso_dpto)
	{

		list($dbconn) = GetDBconn();
		$query = "SELECT count(*)
			 		   FROM egresos_departamento a,movimientos_habitacion b,
						 egresos_departamento_cuentas_x_liquidar c
					   WHERE
						 b.ingreso_dpto_id=a.ingreso_dpto_id
						 AND b.ingreso_dpto_id='$ingreso_dpto'
						 AND b.fecha_egreso IS NOT NULL
						 AND a.estado='2'
						 AND c.egreso_dpto_id =a.egreso_dpto_id;";

			$resulta = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if($resulta->fields[0] >0)
			{
					return '1';
			}
			else
			{
					return '0';
			}

	}






/*****************esta funcion debe ir a estacionE_pacientes ******************************/
	/**
	*		GetPacientesUrgencias
	*
	*		Consulta que pacientes se encuentran en urgencias (sin camas)
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@param integer => id de la estacion
	*		@return bool-array-string
	*/
	function GetPacientesUrgencias($estacion_id)
	{
	  if(!$estacion_id)
		{
			$estacion_id=$_REQUEST['estacion'];
		}
		$query = "SELECT I.tipo_id_paciente,
										I.paciente_id,
										I.ingreso,
										P.primer_apellido,
										P.segundo_apellido,
										P.primer_nombre,
										P.segundo_nombre,
										C.numerodecuenta,
										C.plan_id
							FROM  pacientes P,
										ingresos I,
										cuentas C,
										pacientes_urgencias PU
							WHERE I.estado = '1' AND
										I.ingreso = C.ingreso AND
										C.estado = '1' AND
										P.paciente_id = I.paciente_id AND
										P.tipo_id_paciente = I.tipo_id_paciente AND
										PU.ingreso = I.ingreso AND
										estacion_id = '$estacion_id'
										AND sw_estado='1'  --este estado es el activo
							ORDER BY P.primer_nombre, P.segundo_nombre, P.primer_apellido, P.segundo_apellido";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener los pacientes de urgencias<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
					$tmp[] = $data;
				}
				return $tmp;
			}
		}
	}//GetPacientesUrgencias

	/*****************esta funcion debe ir a estacionE_pacientes ******************************/






	 /*FUNCION QUE VA EN EL MOD ESTACIONE_PACIENTES*/
		/**
		*		CallListadoPacientesEstacion
		*
		*		subproceso 3->"CambioCama" del proceso "ingreso de pacientes a la estación de enfermería"
		*		CallListadoPacientesEstacion llama a la funcion ListadoPacientesEstacion para cammbio de cama
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallListadoPacientesEstacion($datos_estacion)
		{						//vista 5 => 1.3.1.H
			if(!$datos_estacion){
				$datos_estacion = $_REQUEST['datos_estacion'];
			}
			if(!$this->ListadoPacientesEstacion($datos_estacion))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"ListadoPacientesEstacion\"";
				return false;
			}
			return true;
		}//CambioCama()
    /*FUNCION QUE VA EN EL MOD ESTACIONE_PACIENTES*/



  //*********************esto debe ir en estacionE_pacientes***************************////////
	/**
	*		VerificaEgresoPendientePaciente
	*
	*		Verifica si el paciente tiene pendiente orden de egreso con tipo_orden diferente a 4,
	*		ya que la 4 no es un motivo de egreso
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool-string-array
	*		@param integer => ingreso del paciente
	*/
	function VerificaEgresoPendientePaciente($ingreso)
	{
		$query = " SELECT ED.egreso_dpto_id,
											ED.tipo_egreso,
											ED.estado,
											HE.ingreso
								FROM egresos_departamento ED,
										 hc_evoluciones HE
								WHERE HE.ingreso = $ingreso AND
											ED.evolucion_id = HE.evolucion_id AND
											ED.tipo_egreso != '4' AND
											ED.estado != 2";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar los egresos pencientes.<br><br>".$dbconn->ErrorMsg()."<br><br>".$despachos;
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
					$egresos = $data;
				}
				return $egresos;
			}
		}
	}//VerificaEgresoPendientePaciente

	//*********************esto debe ir en estacionE_pacientes***************************////////


	//*********************esto debe ir en estacionE_pacientes***************************////////
	/**
	*		GetDatosTrasladado
	*
	*		muestra a que estacion fue trasladado el paciente llamado desde Vista 5
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array
	*		@param integer => numero de orden_hospitalizacion del paciente
	*/
	function GetDatosTrasladado($orden_hospitalizacion)
	{
		$queryPxH = "SELECT P.estacion_destino, P.estacion_origen,
												( SELECT descripcion FROM estaciones_enfermeria
													WHERE P.estacion_destino = estacion_id) as estDestino,
												( SELECT descripcion FROM estaciones_enfermeria
													WHERE P.estacion_origen = estacion_id) as estOrigen
									FROM pendientes_x_hospitalizar P
									WHERE P.orden_hospitalizacion_id = ".$orden_hospitalizacion." AND
												P.traslado = '1'";
		GLOBAL $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		list($dbconn) = GetDBconn();
		$resultQuery = $dbconn->Execute($queryPxH);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener los datos del traslado.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			while ($data = $resultQuery->FetchRow()){
			$x[] = $data;
			}
			return $x;
		}
	}//fin GetDatosTrasladado

		//*********************esto debe ir en estacionE_pacientes***************************////////


		//------------------------------------------------------------------------------

    //funcion del modulo de estacion de enfermeria pacintes
		/**
		*		CallListadoEstaciones
		*
		*		subproceso 2->"Cambio estacion de enfermeria antes del ingreso al dpto" del proceso "ingreso de pacientes a la estación de enfermería"
		*		llamado desde la funcion 1.1.1.H ->remitir a estacion
		*		1.2.1.U => CallListadoEstaciones muestra la vista de las estaciones
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallListadoEstaciones()
		{
			$datos = $_REQUEST['datos']; //vector con los datos del paciente sizeof=10
			if(!$this->ListadoEstaciones($datos,$_REQUEST['SwTrasladoEE'],$_REQUEST['datos_estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"ListadoEstaciones\"";
				return false;
			}
			return true;
		}

		//funcion de modulo de estacion de enfermeria_pacientes
		/**
		*		UpdateTrasladoEstacion
		*
		*		Inserta en la tabla de pxh la estacion origen en la que se encuentr a el paciente y la destino
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function UpdateTrasladoEstacion()
		{
			$datos = $_REQUEST['datos'];
			$ee_destino = $_REQUEST['estacionDestino'];

			$datos_estacion = $_REQUEST['datos_estacion'];

			$query = "INSERT INTO pendientes_x_hospitalizar (	ingreso,
																												orden_hospitalizacion_id,
																												estacion_destino,
																												estacion_origen,
																												traslado)
																							VALUES (".$datos[4].",
																											".$datos[5].",
																											'".$ee_destino."',
																											'".$datos_estacion[estacion_id]."',
																											'1')";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			unset($query); unset($result); unset($ee_destino); unset($datos);
			$this->CallListadoPacientesEstacion($datos_estacion);
			return true;
		}// RemitirAEstacion()



		//funcion del modulo de estacion de enfermeria_pacientes
		/**
		*		UpdateCambioEstacion
		*
		*		subproceso 2->CambioEEAntesIngreso del proceso "ingreso de pacientes a la estación de enfermería"
		*		1.2.2.U => UpdateCambioEstacion actualiza la db con los datos de la nueva estacion
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function UpdateCambioEstacion()
		{

			/*$datos[0] = nombres							$datos[1] = apellidos			$datos[2] = PACIENTE_ID;
				$datos[3] = TIPO_ID_PACIENTE;		$datos[4] = INGRESO;			$datos[5] = ORDEN_HOSP;
				$datos[6] = CUENTA							$datos[7] = PLAN					$datos[8] = TRASLADO;
				$datos[9] = descrip_ee_origen		$datos[10] = id_ee_origen*/

			$datos = $_REQUEST['datos']; //vector con los datos del paciente sizeof=10
			$ee_destino = $_REQUEST['estacionDestino'];
			$datos_estacion = $_REQUEST['datos_estacion'];

			list($dbconn) = GetDBconn();

			if ($ee_destino == $datos[10])//lo quiero devolver a la misma estacion que lo remitió
			{
				$query = "DELETE
									FROM pendientes_x_hospitalizar
									WHERE ingreso = ".$datos[4];

				$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Error al intentar remitir a estacion destino<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}
			}
			else //remite a estacion diferente a la que originó el traslado
			{
				$query = "UPDATE pendientes_x_hospitalizar
									SET estacion_destino = '".$ee_destino."'
									WHERE ingreso = '".$datos[4]."'";
				$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Error al tratar de asignar la estacion seleccinada al paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}
			}
			unset($datos); unset($ee_destino); unset($query); unset($result);
			$this->ListPacientesPorIngresar($datos_estacion);
			//unset($datos);
			return true;
		}//UpdateCambioEstacion()


  //funcion del modulo estacion de enfermeria_pacientes
		/**
		*		CallListadoCamas
		*
		*		viene del link ingresar de la vista 1
		*		subproceso 1->"Asignar cama" del proceso "ingreso de pacientes a la estación de enfermería"
		*		1.1.2.U - CallListadoCamas recibe el paciente a ingresar al dpto de la tabla pendientes_x_remitir y llama a la funcion que me muestra el listado de las camas disponibles
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallListadoCamas()
		{
				unset($_SESSION['ESTACION_ENF']['AUDITORIA']['USUARIO_AUTO']);
			/*$datos[0] = nombres							$datos[1] = apellidos			$datos[2] = PACIENTE_ID;
				$datos[3] = TIPO_ID_PACIENTE;		$datos[4] = INGRESO;			$datos[5] = ORDEN_HOSP;
				$datos[6] = CUENTA							$datos[7] = PLAN					$datos[8] = TRASLADO;
				$datos[9] = descrip_ee_origen		$datos[9] = id_ee_origen*/
			$datos = $_REQUEST['datos']; //vector con los datos del paciente a ingresar

			//ojo esto es por si voy a hacer el subproceso 3 "cambio de cama" en lugar de "asignar cama" del subproceso 1
			if($_REQUEST['SwCambioCama']){
				$swCambioCama = '1';
			}

			if(!$this->ListadoCamas($datos,$swCambioCama,$_REQUEST['datos_estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"ListadoCamas\"";
				return false;
			}
			return true;
		}//fin CallListadoCamas



		//esta funcion nos lleva a la forma en donde muestra las camas q esta ocupadas
		//y nos permite realizar una reservación de la cama y en el momento que esta quede
		//desocupada nos mostrara un mensaje para indicarnos q ya esta libre.
		function CallReservaCama()
		{
			$datos = $_REQUEST['datos']; //vector con los datos del paciente a ingresar
				//ojo esto es por si voy a hacer el subproceso 3 "cambio de cama" en lugar de "asignar cama" del subproceso 1
			if($_REQUEST['SwCambioCama']){
				$swCambioCama = '1';
			}

			$this->FrmReservaCamas($datos,$swCambioCama,$_REQUEST['datos_estacion']);
			return true;
		}

/**
	*		GetDescripcionCargoCama
	*
	*		Trae la descripcion del cargo de la tabla 'camas' ó
	*		en caso de que esta tabla no lo tenga lo busca en cups..
	*
	*		@Author jaja
	*		@access Public
	*		@return varchar
	  	@param character =>cargo de la cama en la cual esta ubicado el paciente.
	*		@param character => cama en la q esta hospedado el paciente.
	*/

     function GetDescripcionCargoCama($cargo,$cama)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT retornar_desc_cargo('$cargo','$cama')";
     
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al traer la descripcion de la tabla camas/cups";
               return false;
          }
          return $result->fields[0]; //retornamos la descripcion.
     }


function GetDescripcionCama($cargo)
{
	list($dbconn) = GetDBconn();
			$query = "SELECT descripcion FROM cups WHERE cargo='$cargo'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al traer la descripcion de la tabla camas/cups";
				return false;
			}
				return $result->fields[0]; //retornamos la descripcion.
}



/**
	*		DarSalidaPaciente
	*
	*		Esta funcion inserta en la tabla egresos_departamento_cuentas_x_liquidar para
	*		avisarle al de cuentas que el paciente fue dado de alta y que se necesita liquidar la cuenta
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function DarSalidaPaciente()
	{
		$datosRemision = $_REQUEST['datosRemision'];
		$datos_estacion = $_REQUEST['datos_estacion'];

		

		$query = "UPDATE egresos_departamento
							SET estado = '1'
							WHERE egreso_dpto_id = ".$datosRemision['egreso_dpto_id'].";";

		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar actualizar el estado del egreso del departamento.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
			$query = "INSERT INTO egresos_departamento_cuentas_x_liquidar (
																																			egreso_dpto_id,
																																			usuario_id
																																		)
																														VALUES (
																																			".$datosRemision['egreso_dpto_id'].",
																																			".UserGetUID()."
																																		);";

			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar insertar los datos para cuentas pendientes por liquidar..<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				$dbconn->CommitTrans();
				$mensaje = "SOLICITUD REALIZADA CON EXITO";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionE_Pacientes','user','CallListPacientesPorEgresar',array("datos_estacion"=>$datos_estacion));
				$boton = "VOLVER A PACIENTES POR EGRESAR";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
		}
	}//DarSalidaPaciente


/**
	*		CallFrmRemitirDepartamento
	*
	*		LLama el formulario en el cual se selecciona el centro de remision  al cual será
	*		remitido el  paciente
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function CallFrmRemisionExterna()
	{
		if(!$this->FrmRemisionExterna($_REQUEST['datosRemision'],$_REQUEST['datos_estacion']))
		{
			$this->error = "No se puede cargar la vista";
			$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmRemisionExterna\"";
			return false;
		}
		return true;
	}



	//revisamos si hay un autorizador de cambios de cargos
	function BuscarAutorizacionParaCambioCargo($estacion,$usuario_id)
	{
			list($dbconn) = GetDBconn();

			if($usuario_id)
			{
			$query="SELECT COUNT(*)
							FROM estaciones_enfermeria_autorizadores_cargos
							WHERE usuario_id='$usuario_id'
							AND estacion_id='$estacion'";
			}
			else
			{
				$query="SELECT COUNT(*)
							FROM estaciones_enfermeria_autorizadores_cargos
							WHERE usuario_id=".UserGetUID()."
							AND estacion_id='$estacion'";
			}
							$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0){
									$this->error = "Error al traer la consulta de los cierres";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
								return $resulta->fields[0];
	}



	/**
	*		GetCentrosRemision
	*
	*		Obtienen los centros de remision disponibles (Mostrados para seleccionar el centro
	*		al cual se va a remitir el paciente)
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool - array - string
	*/
	function GetCentrosRemision()
	{
		$query = " SELECT CR.centro_remision,
											CR.descripcion,
											CR.tipo_pais_id,
											(SELECT pais FROM tipo_pais WHERE tipo_pais_id = CR.tipo_pais_id) AS pais,
											CR.tipo_dpto_id,
											(SELECT departamento FROM tipo_dptos WHERE tipo_pais_id = CR.tipo_pais_id AND tipo_dpto_id = CR.tipo_dpto_id) AS dpto,
											CR.tipo_mpio_id,
											(SELECT municipio FROM tipo_mpios WHERE tipo_pais_id = CR.tipo_pais_id AND tipo_dpto_id = CR.tipo_dpto_id AND tipo_mpio_id = CR.tipo_mpio_id) AS mpio,
											CR.nivel
							FROM centros_remision CR";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener los centros de remisión.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
					$centrosRemision[] = $data;
				}
				return $centrosRemision;
			}
		}
	}//GetCentrosRemision










		/**
	*		RemitirPacienteAcentroRemision
	*
	*		Esta funcion asigna un centro de remision al paciente cuando este es remitido y
	*		tambien solicita al de cuetnas liquidar la cuenta de dicho paciente
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function RemitirPacienteAcentroRemision()
	{
		$RemitirAcentro = $_REQUEST['RemitirAcentro'];
		$datos_estacion = $_REQUEST['datos_estacion'];

		if($RemitirAcentro)
		{
			list($egreso_dpto_id, $centro) = explode(".-.",$RemitirAcentro);
			$query = "INSERT INTO remisiones_externas (egreso_dpto_id,
																								centro_remision
																								)
																					VALUES ($egreso_dpto_id,'$centro');";

			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar generar la remision externa.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				$query = "UPDATE egresos_departamento
									SET estado = '1'
									WHERE egreso_dpto_id = ".$egreso_dpto_id.";";

				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al intentar actualizar el estado del egreso del departamento.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					$query = "INSERT INTO egresos_departamento_cuentas_x_liquidar (
																																					egreso_dpto_id,
																																					usuario_id
																																				)
																																VALUES (
																																					".$egreso_dpto_id.",
																																					".UserGetUID()."
																																				);";

					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurrió un error al intentar insertar los datos para cuentas pendientes por liquidar..<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						$dbconn->RollbackTrans();
						return false;
					}
					else
					{
						$dbconn->CommitTrans();
						$mensaje = "LA REMISION SE CREÓ CON EXITO";
						$titulo = "MENSAJE";
						$accion = ModuloGetURL('app','EstacionE_Pacientes','user','CallListPacientesPorEgresar',array("datos_estacion"=>$datos_estacion));
						$boton = "VOLVER A PACIENTES POR EGRESAR";
						$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
						return true;
					}
				}
			}
		}
	}//RemitirPacienteAcentroRemision


/**
	*		EgresosDevolucionMedicamentos()
	*
	*		Hace una solicitud de TODOS los medicamentos e insumos existentes en la BD
	*		con el numero de ingreso especificado
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function EgresosDevolucionMedicamentos()
	{
		list($dbconn) = GetDBconn();
		$datos_estacion = $_REQUEST['datos_estacion'];
		$ingreso = $_REQUEST['ingreso'];
		$vectorMedicamentos = $_REQUEST['vectorMedicamentos'];

		if(!$bodega = $this->GetBodegaDelDepartamento($datos_estacion))
		{
			$mensaje = "NO SE PUDO SELECCIONAR LA BODEGA DEL DEPARTAMENTO";
			$titulo = "MENSAJE";
			$boton = "REGRESAR";
			$accion = "javascript:histoy.back()";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}

		$query = "SELECT nextval('public.inv_solicitudes_devolucion_documento_seq')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener la secuencia de la solicitud de devolucion.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if(!$result->EOF)
			{
				$documetoDevolucion = $result->fields[0];
				$dbconn->BeginTrans();
				$puedoHacerCommit = array();
				$query = "INSERT INTO inv_solicitudes_devolucion (
																													empresa_id,
																													centro_utilidad,
																													documento,
																													bodega,
																													fecha,
																													observacion,
																													usuario_id,
																													fecha_registro,
																													estacion_id,
																													estado,
																													ingreso
																													)
																									VALUES (
																													'".$datos_estacion[empresa_id]."',
																													'".$datos_estacion[centro_utilidad]."',
																													$documetoDevolucion,
																													'".$bodega[bodega]."',
																													'".date("Y-m-d")."',
																													'Devolucion total de la bodega del paciente por solicitud de egreso',
																													".UserGetUID().",
																													'".date("Y-m-d H:i:s")."',
																													'".$datos_estacion[estacion_id]."',
																													'0',
																													".$ingreso.");";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al intentar realizar la solicitud de devolucion.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{//[codigo_producto], CantDevolver, $i, numerodecuenta, [facturado]
					foreach($vectorMedicamentos as $key => $value)
					{
						$query = "INSERT INTO inv_solicitudes_devolucion_d (
																																documento,
																																empresa_id,
																																codigo_producto,
																																cantidad,
																																centro_utilidad,
																																bodega
																																)
																												VALUES (
																																$documetoDevolucion,
																																'".$datos_estacion[empresa_id]."',
																																'".$value['medicamento_id']."',
																																".$value['cantidad_acum'].",
																																'".$datos_estacion[centro_utilidad]."',
																																'".$bodega[bodega]."');";

						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Ocurrió un error al intentar realizar el detalle de la solicitud de devolucion.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							$puedoHacerCommit[] = 0;
							return false;
						}
						else{
							$puedoHacerCommit[] = 1;
						}
					}//fin FORAECH MEDICAMNETO
				}//.sizeof($Medicamentos)."<br>"; print_r($Medicamentos); print_r($CheckMedicamentos);
				if(!in_array(0,$puedoHacerCommit))
				{
					$dbconn->CommitTrans();
					$mensaje = "LA SOLICITUD DE DEVOLUCIÓN DE MEDICAMENTOS SE REALIZÓ CON ÉXITO";
					$titulo = "MENSAJE";
					$boton = "VOLVER A PACIENTES POR EGRESAR";
					$action =  ModuloGetURL('app','EstacionE_Pacientes','user','CallListPacientesPorEgresar',array("datos_estacion"=>$datos_estacion));
					$this->FormaMensaje($mensaje,$titulo,$action,$boton);
					return true;
				}
			}//fin si hay nextval
		}//fin else si hizo nextvall
	}//EgresosDevolucionMedicamentos()


/**
	*		trasladarDpto
	*
	*		Realiza el traslado de departamento de un paciente cuya orden de egreso de
	*		departamento es creada por el medico
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function trasladarDpto()
	{
		$datosRemision = $_REQUEST['datosRemision'];
		$datos_estacion = $_REQUEST['datos_estacion'];

		$query = "SELECT nextval('ordenes_hospitalizacion_orden_hospitalizacion_id_seq');";
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar generar el consecutivo de la orden de hospitalizacion.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
			if(!$result->EOF)
			{
				$orden_id = $result->fields[0];
				$query = "INSERT INTO ordenes_hospitalizacion (
																												orden_hospitalizacion_id,
																												fecha_orden,
																												fecha_programacion,
																												hospitalizado,
																												paciente_id,
																												tipo_id_paciente,
																												departamento,
																												tipo_orden_id,
																												ingreso,
																												autorizacion
																											)
																								VALUES (
																												$orden_id,
																												'".$datosRemision['fecha_egreso']."',
																												'".$datosRemision['fecha_egreso']."',
																												'0',
																												'".$datosRemision['paciente_id']."',
																												'".$datosRemision['tipo_id_paciente']."',
																												'".$datosRemision['departamento']."',
																												'2',
																												".$datosRemision['ingreso'].",
																												NULL)";

				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al intentar crear la orden de hospitalizacion.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					$query = "INSERT INTO ordenes_hospitalizacion_traslado(
																																	orden_hospitalizacion_id,
																																	egreso_dpto_id
																																)
																												VALUES ($orden_id, ".$datosRemision['egreso_dpto_id'].")";

					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurrió un error al intentar insertar en ordenes de hospitalizacion de traslado.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						$dbconn->RollbackTrans();
						return false;
					}
					else
					{
						$query = "UPDATE egresos_departamento
											SET estado = '1'
											WHERE egreso_dpto_id = ".$datosRemision['egreso_dpto_id']."";

						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Ocurrió un error al intentar actualizar el estado del egreso del departamento.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							return false;
						}
						else
						{
							$dbconn->CommitTrans();
							$mensaje = "LA REMISION SE REALIZÓ CON EXITO";
							$titulo = "MENSAJE";
							$accion = ModuloGetURL('app','EstacionE_Pacientes','user','CallListPacientesPorEgresar',array("datos_estacion"=>$datos_estacion));
							$boton = "VOLVER A PACIENTES POR EGRESAR";
							$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
							return true;
						}
					}
				}
			}
		}
	}//trasladarDpto




		 //esto debe ir en estacionE_pacientes
	/**
	*		GetCamasDisponiblesSegunPlan
	*
	*		Busca las camas disponibles de la estacion y que son cubiertas por el plan del paciente
	*
	*		@Author Alexander
	*		@access Public
	*		@return array, bool
	*		@param character => estacion en la que se quiere consultar
	*		@param character => plan del paciente
	*		@param character => estado de la cama (1->disponible o->ocupada)
	*/
	function GetCamasDisponiblesSegunPlan ($estacion, $plan_id, $estadoCama)
	{
	/*
											c.descripcion AS desc_cama,
											e.descripcion AS desc_cargo,
											e.precio,g.por_cobertura,
											g.porcentaje,
											e.gravamen,
											g.sw_descuento,
											e.tarifario_id,
											e.subgrupo_tarifario_id,
											e.cargo,
											e.grupo_tarifario_id */
			/*$query="select e.cargo, e.tarifario_id from piezas as a, camas as b join tarifarios_equivalencias as e on(b.cargo=e.cargo_base) where a.estacion_id='$estacion' and b.estado='$estadoCama' and a.pieza=b.pieza";
			list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{ //echo $query. " ".$dbconn->ErrorMsg(); exit;//me imprime el error que existe
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener las camas disponibles de la estación de enfermería<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF){
			return "ShowMensaje";
		}*/
	/*echo $query="select e.cargo, e.tarifario_id
	from piezas as a, camas as b,
	tarifarios_equivalencias as e
	where a.estacion_id='$estacion'
	and b.estado='$estadoCama'
	and b.cargo=e.cargo_base
	and a.pieza=b.pieza";*/
	//excepciones('$plan_id',e.tarifario_id, e.cargo) AS algo,



//este es el query antepenultimo se quito el 11/01/2005 *********************************
 /*$query="(
							select a.pieza, b.cama, b.cargo as cargos, b.descripcion as desc_cargo, f.precio,
							d.por_cobertura, f.tarifario_id, e.cargo as cargoe, f.cargo as cargo_tar,
							c.grupo_tarifario_id, c.subgrupo_tarifario_id, d.tarifario_id,
							f.grupo_tarifario_id as grupoc, d.subgrupo_tarifario_id as subcont,e.cargo,
							h.por_cobertura as excepcober, h.sw_no_contratado
							from piezas as a,
							camas as b left join tarifarios_equivalencias as e on(b.cargo=e.cargo_base)
							left join tarifarios_detalle as f on(e.tarifario_id=f.tarifario_id and e.cargo=f.cargo)
							left join grupos_cargos_cama as c on (c.plan_id='$plan_id' and f.grupo_tarifario_id=c.grupo_tarifario_id and f.subgrupo_tarifario_id=c.subgrupo_tarifario_id)
							left join plan_tarifario as d on(d.plan_id='$plan_id' and f.grupo_tarifario_id=d.grupo_tarifario_id and f.subgrupo_tarifario_id=d.subgrupo_tarifario_id)
							left join excepciones as h on(h.plan_id='$plan_id' and h.tarifario_id=e.tarifario_id and h.cargo=e.cargo)
							where a.estacion_id='$estacion' and b.estado='$estadoCama'
							and a.pieza=b.pieza and c.plan_id='$plan_id' and d.plan_id='$plan_id'
							order by a.pieza)
				";
*///este es el query antepenultimo se quito el 11/01/2005 *******************************


 $query="(
							select a.pieza, b.cama, x.cargo as cargos, x.descripcion as desc_cargo, f.precio,
							d.por_cobertura, f.tarifario_id, e.cargo as cargoe, f.cargo as cargo_tar,
							c.grupo_tarifario_id, c.subgrupo_tarifario_id, d.tarifario_id,
							f.grupo_tarifario_id as grupoc, d.subgrupo_tarifario_id as subcont,e.cargo,
							h.por_cobertura as excepcober, h.sw_no_contratado,x.precio_lista,x.tipo_cama_id,b.sw_virtual
							from piezas as a,
							camas as b,tipos_camas x
							left join tarifarios_equivalencias as e on(x.cargo=e.cargo_base)
							left join tarifarios_detalle as f on(e.tarifario_id=f.tarifario_id and e.cargo=f.cargo)
							left join grupos_cargos_cama as c on (c.plan_id='$plan_id' and f.grupo_tarifario_id=c.grupo_tarifario_id and f.subgrupo_tarifario_id=c.subgrupo_tarifario_id)
							left join plan_tarifario as d on(d.plan_id='$plan_id' and f.grupo_tarifario_id=d.grupo_tarifario_id and f.subgrupo_tarifario_id=d.subgrupo_tarifario_id)
							left join excepciones as h on(h.plan_id='$plan_id' and h.tarifario_id=e.tarifario_id and h.cargo=e.cargo)
							where a.estacion_id='$estacion' and b.estado='$estadoCama'
							and a.pieza=b.pieza and c.plan_id='$plan_id' and d.plan_id='$plan_id'
							and b.tipo_cama_id=x.tipo_cama_id
							and b.sw_virtual='1'		order by a.pieza)
				";
			/*	UNION
				(
						select a.pieza, b.cama, b.cargo as cargos, b.descripcion as desc_cargo, f.precio,
						d.por_cobertura, f.tarifario_id, e.cargo as cargoe, f.cargo as cargo_tar,
						c.grupo_tarifario_id, c.subgrupo_tarifario_id, d.tarifario_id,
						f.grupo_tarifario_id as grupoc, d.subgrupo_tarifario_id as subcont,e.cargo
						from piezas as a,
						camas as b left join tarifarios_equivalencias as e on(b.cargo=e.cargo_base) left join tarifarios_detalle as f on(e.tarifario_id=f.tarifario_id and e.cargo=f.cargo)
						left join grupos_cargos_cama as c on (c.plan_id='$plan_id' and f.grupo_tarifario_id=c.grupo_tarifario_id and f.subgrupo_tarifario_id=c.subgrupo_tarifario_id)
						left join plan_tarifario as d on(d.plan_id='$plan_id' and f.grupo_tarifario_id=d.grupo_tarifario_id and f.subgrupo_tarifario_id=d.subgrupo_tarifario_id)
						where a.estacion_id='$estacion' and b.estado='$estadoCama'
						and a.pieza=b.pieza and c.plan_id='$plan_id' and d.plan_id='$plan_id'
						AND excepciones('$plan_id',e.tarifario_id, e.cargo) > 0
						order by a.pieza
				)*/

		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener las camas disponibles de la estación de enfermería<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF){
			return "ShowMensaje";
		}

		$i=0;
		$piezas = $piezasTemp = $camasTemp= $C = $datosCamas = $x= $t=array();

		while ($data = $result->FetchNextObject())
		{
			if(!in_array($data->PIEZA,$piezas))
			{
				array_push($piezas,$data->PIEZA);
//				array_push($piezasTemp,$data->PIEZA,$data->ESTACION_ID,$data->UBICACION);
				array_push($piezasTemp,$data->PIEZA,$data->UBICACION);
				array_push($camasTemp,$data->CAMA, $data->DESC_CARGO, $data->DESC_CAMA, $data->PRECIO, $data->POR_COBERTURA,
				$data->TARIFARIO_ID, $data->SUBGRUPO_TARIFARIO_ID,$data->CARGOS,$data->CARGO,
				$data->GRUPO_TARIFARIO_ID,$data->CARGOE,$data->GRUPOC,$data->EXCEPCOBER,
				$data->SW_NO_CONTRATADO,$data->PRECIO_LISTA,$data->TIPO_CAMA_ID,$data->SW_VIRTUAL);
				array_push($C,$camasTemp);
				$datosCamas[$i][0]=$piezasTemp;
				$datosCamas[$i][1]=$C;
				unset($camasTemp); unset($piezasTemp); unset($C);
				$camasTemp = $piezasTemp = $C = array();
				$i++;
			}
			else//ya existe la pieza
			{
				array_push($camasTemp,$data->CAMA, $data->DESC_CARGO, $data->DESC_CAMA, $data->PRECIO,
				$data->POR_COBERTURA, $data->TARIFARIO_ID, $data->SUBGRUPO_TARIFARIO_ID,
				$data->CARGOS,$data->CARGO,$data->GRUPO_TARIFARIO_ID,$data->CARGOE,$data->GRUPOC,
				$data->EXCEPCOBER,$data->SW_NO_CONTRATADO,$data->PRECIO_LISTA,$data->TIPO_CAMA_ID,$data->SW_VIRTUAL);
				$x = array_keys($piezas,$data->PIEZA);
				$t=$datosCamas[$x[0]][1];
				$t[sizeof($t)] = $camasTemp;
				$datosCamas[$x[0]][1] = $t;
				unset($camasTemp);
				unset($t);
				$camasTemp = $t = array();
			}
		}
	//	print_r($datosCamas);
		return $datosCamas;
	}//fin GetCamasDisponiblesSegunPlan




	function Revisar_Habitaciones_Existentes($estacion_id)
	{
			list($dbconn) = GetDBconn();
			$query="SELECT * FROM piezas WHERE estacion_id='$estacion_id' ORDER BY sw_virtual ASC";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al buscar la habitación";
				$this->mensajeDeError = "Ocurrió un error en la conexión de la bd<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			$i=0;
			while(!$result->EOF)
			{
				$var[$i]= $result->GetRowAssoc($ToUpper = false);
				$i++;
				$result->MoveNext();
			}
			return $var;
	}


	//generamos la cama virtual del paciente.
	function GenerarCamaVirtual()
	{
		$datos = $_REQUEST['datos'];
		$opcion = $_REQUEST['opcion'];
		$tipo_serv = $_REQUEST['tipoc'];
		$datos_estacion = $_REQUEST['datos_estacion'];
		$descripcion = $_REQUEST['desc'];
		$ubicacion = $_REQUEST['ubic'];
		$ubicacion_cama= $_REQUEST['ubic_cama'];

		//-------
		$arr_tipocama=$_REQUEST['tipo_cama'];
		$data=explode("*",$arr_tipocama);
		$tipocama=$data[0];
		$cargo=$data[1];
		$tipo_clase_cama_id=$data[2];
		//-------

		if(empty($tipo_serv))
		{
			$this->frmError["MensajeError"] = "SELECCIONE EL TIPO DE SERVICIO DE CAMA !";
			$this->CallCrear_Asignar_Cama_Virtual($datos,$datos_estacion);
			return true;
		}


		if(empty($opcion))
		{
			$this->frmError["MensajeError"] = "SELECCIONE LA PIEZA DONDE VA A CREAR LA CAMA !";
			$this->CallCrear_Asignar_Cama_Virtual($datos,$datos_estacion);
			return true;
		}


		if(strlen($descripcion) > 20)
		{
			$this->frmError["MensajeError"] = "LA DESCRIPCION DE LA PIEZA DEBE SER MENOR DE 20 CARACTERES !";
			$this->CallCrear_Asignar_Cama_Virtual($datos,$datos_estacion);
			return true;
		}

		if(strlen($ubicacion) > 20)
		{
			$this->frmError["MensajeError"] = "LA UBICACION DE LA PIEZA DEBE SER MENOR DE 20 CARACTERES !";
			$this->CallCrear_Asignar_Cama_Virtual($datos,$datos_estacion);
			return true;
		}


		if(strlen($ubicacion_cama) > 20)
		{
			$this->frmError["MensajeError"] = "LA UBICACION DE LA CAMA DEBE SER MENOR DE 20 CARACTERES !";
			$this->CallCrear_Asignar_Cama_Virtual($datos,$datos_estacion);
			return true;
		}




		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query="SELECT sw_virtual FROM piezas WHERE pieza='$opcion'
		--AND sw_virtual ISNULL
		AND estacion_id='".$datos_estacion['estacion_id']."'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer la habitación";
			$this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			$dbconn->RollbackTrans();
			return false;
		}
		if ($result->RecordCount() < 1 AND ($result->fields[0] <> '2' OR $result->fields[0] <> '3'))
		{
						//$NUMEROC=$this->AsignarCamaVirtual();
						$NUMERO=$this->AsignarPiezaVirtual();


						if($tipo_serv=='3')  //3 amb,//2//virtual
						{$nom='A';$sw=3;$nomc="A";}else{$nom='V';$sw=2;$nomc="V";}
						$query = "INSERT INTO piezas (pieza,
																					estacion_id,
																					descripcion,
																					cantidad_camas,
																					ubicacion,
																					sw_virtual)
											VALUES ('$nom$NUMERO',
															'".$datos_estacion['estacion_id']."',
															'$descripcion',
															1,
															'$ubicacion',
															'$sw')";

						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al crear la PIEZA";
							$this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							return false;
						}

						$NUMEROC=$this->AsignarCamaVirtual();
						$query = "INSERT INTO camas ( cama,
																		pieza,
																		sw_virtual,
																		tipo_cama_id,
																		ubicacion,
																		estado)
								VALUES (
													'$nomc$NUMEROC',
													'$nom$NUMERO',
													'$sw',
													'$tipocama',
													'$ubicacion_cama',
													'1')";

							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al insertar la cama";
								$this->mensajeDeError = "error de insercion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								$dbconn->RollbackTrans();
								return false;
							}


				//--para llenar el vector
						$query="SELECT descripcion FROM cups
											WHERE cargo='$cargo'";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al tratar de traer cargo de cups";
							$this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							return false;
						}

							$decripcion_cargo=$result->fields[0];
							//retornar a asignar la cama
							//este vector es muy importante ya que lo mandamos a
							//la parte de ingreso del paciente, tener mucho cuidado con el.
							$data_cama[0]=$nomc.$NUMEROC;
							$data_cama[1]=$decripcion_cargo;
							$data_cama[2]=$cargo;
							$data_cama[3]=$tipocama;
							$data_cama[4]=$tipo_clase_cama_id;
							$_PIEZA_ESPECIAL=$nom.$NUMERO; //debemos mandar la pieza a callingresopacientes
				//--para llenar el vector

		}
		else
		{//es por q existe la pieza

					$query="SELECT a.cama,a.pieza FROM camas a,piezas b
						WHERE (a.sw_virtual='2' OR  a.sw_virtual='3')
						AND (b.sw_virtual='2' OR  b.sw_virtual='3')
						AND a.estado='1'
						--AND a.pieza=b.pieza
						AND b.estacion_id='".$datos_estacion['estacion_id']."'
						ORDER BY cama ASC LIMIT 1 OFFSET 0 ";
						$result = $dbconn->Execute($query);
						$cama=$result->fields[0];
						$pieza=$result->fields[1];
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al tratar de crear la habitación";
							$this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							return false;
						}
						if($result->RecordCount() > 0)
						{
								if($tipo_serv=='3')  //3 amb,//2//virtual
								{$nom='A';$sw=3;$nomc="A";}else{$nom='V';$sw=2;$nomc="V";}

							$query = "UPDATE camas
								SET
								tipo_cama_id='$tipocama',
								sw_virtual='$sw',
								ubicacion='$ubicacion_cama',
								pieza='$opcion'
								WHERE cama = '$cama'
								AND pieza= '$pieza' ;";

								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al ejecutar la conexion";
									$this->mensajeDeError = "Error al intentar crear la cama<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
									$dbconn->RollbackTrans();
									return false;
								}






								$query = "SELECT COUNT(*)
													FROM piezas
													WHERE pieza = '$pieza'
													AND (sw_virtual='2' OR sw_virtual='2')";

									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al ejecutar la conexion";
										$this->mensajeDeError = "Error al intentar recalcular el numero de camas de la pieza<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
										$dbconn->RollbackTrans();
										return false;
									}

								//es por que la habitación era virtual o ambulatoria,si es normal no actualizamos.
								if($result->fields[0] > 0)
								{
										$query = "UPDATE piezas
										SET
										sw_virtual='$sw'
										WHERE pieza= '$pieza' ;";

										$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0)
										{
											$this->error = "Error al actualizar piezas";
											$this->mensajeDeError = "Error al intentar crear la cama<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
											$dbconn->RollbackTrans();
											return false;
										}


											$query = "SELECT COUNT(*)
															FROM camas
															WHERE pieza = '$pieza'";

											$result = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->error = "Error al ejecutar la conexion";
												$this->mensajeDeError = "Error al intentar recalcular el numero de camas de la pieza<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
												$dbconn->RollbackTrans();
												return false;
											}

										$query = "UPDATE piezas
															SET cantidad_camas= ".$result->fields[0]."
															WHERE pieza = '$pieza'";

											$result = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->error = "Error al ejecutar la conexion";
												$this->mensajeDeError = "Error al intentar recalcular el numero de camas de la pieza<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
												$dbconn->RollbackTrans();
												return false;
											}
								}





									//--para llenar el vector
									$query="SELECT descripcion FROM cups
														WHERE cargo='$cargo'";
									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al tratar de traer cargo de cups";
										$this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
										$dbconn->RollbackTrans();
										return false;
									}

										$decripcion_cargo=$result->fields[0];
										//retornar a asignar la cama
										//este vector es muy importante ya que lo mandamos a
										//la parte de ingreso del paciente, tener mucho cuidado con el.

										$data_cama[0]=$cama;
										$data_cama[1]=$decripcion_cargo;
										$data_cama[2]=$cargo;
										$data_cama[3]=$tipocama;
										$data_cama[4]=$tipo_clase_cama_id;
										$_PIEZA_ESPECIAL=$opcion; //debemos mandar la pieza a callingresopacientes

								//--para llenar el vector

						}
						else
						{
							$query="SELECT a.cama,a.pieza FROM camas a,piezas b
							WHERE (a.sw_virtual='2' OR  a.sw_virtual='3')
							AND a.estado='1'
							AND b.estacion_id='".$datos_estacion['estacion_id']."'
							ORDER BY cama ASC LIMIT 1 OFFSET 0 ";
							$result = $dbconn->Execute($query);
							$cama=$result->fields[0];
							$pieza=$result->fields[1];
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al tratar de crear la habitación";
								$this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								$dbconn->RollbackTrans();
								return false;
							}

               //es por q en realidad existe la cama pero es normal
							if($result->RecordCount() > 0)
							{
												if($tipo_serv=='3')  //3 amb,//2//virtual
								{$nom='A';$sw=3;$nomc="A";}else{$nom='V';$sw=2;$nomc="V";}

								$query = "UPDATE camas
									SET
									tipo_cama_id='$tipocama',
									sw_virtual='$sw',
									ubicacion='$ubicacion_cama',
									pieza='$opcion'
									WHERE cama = '$cama'
									AND pieza= '$pieza' ;";

									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al ejecutar la conexion";
										$this->mensajeDeError = "Error al intentar crear la cama<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
										$dbconn->RollbackTrans();
										return false;
									}


									$query="SELECT descripcion FROM cups
											WHERE cargo='$cargo'";
										$result = $dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0)
										{
											$this->error = "Error al tratar de traer cargo de cups";
											$this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
											$dbconn->RollbackTrans();
											return false;
										}

											$decripcion_cargo=$result->fields[0];
											//retornar a asignar la cama
											//este vector es muy importante ya que lo mandamos a
											//la parte de ingreso del paciente, tener mucho cuidado con el.
											$data_cama[0]=$cama;
											$data_cama[1]=$decripcion_cargo;
											$data_cama[2]=$cargo;
											$data_cama[3]=$tipocama;
											$data_cama[4]=$tipo_clase_cama_id;
											$_PIEZA_ESPECIAL=$opcion; //debemos mandar la pieza a callingresopacientes


										//este caso es cuando quitamos una cama de una pieza y la asociamos a otra
							}
							else //es por q en realidad existe la camapero es virtual
							{
												if($tipo_serv=='3')  //3 amb,//2//virtual
											{$nom='A';$sw=3;$nomc="A";}else{$nom='V';$sw=2;$nomc="V";}

											$NUMEROC=$this->AsignarCamaVirtual();
											$query = "INSERT INTO camas ( cama,
																						pieza,
																						sw_virtual,
																						tipo_cama_id,
																						ubicacion,
																						estado)
												VALUES (
																	'$nomc$NUMEROC',
																	'$opcion',
																	'$sw',
																	'$tipocama',
																	'$ubicacion_cama',
																	'1')";

												$result = $dbconn->Execute($query);
												if ($dbconn->ErrorNo() != 0)
												{
													$this->error = "Error al insertar la cama virtual";
													$this->mensajeDeError = "error de insercion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
													$dbconn->RollbackTrans();
													return false;
												}


											$query="SELECT descripcion FROM cups
											WHERE cargo='$cargo'";
										$result = $dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0)
										{
											$this->error = "Error al tratar de traer cargo de cups";
											$this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
											$dbconn->RollbackTrans();
											return false;
										}

											$decripcion_cargo=$result->fields[0];
											//retornar a asignar la cama
											//este vector es muy importante ya que lo mandamos a
											//la parte de ingreso del paciente, tener mucho cuidado con el.
											$data_cama[0]=$nomc.$NUMEROC;
											$data_cama[1]=$decripcion_cargo;
											$data_cama[2]=$cargo;
											$data_cama[3]=$tipocama;
											$data_cama[4]=$tipo_clase_cama_id;
											$_PIEZA_ESPECIAL=$opcion; //debemos mandar la pieza a callingresopacientes

						}




					}

			
		}


		$dbconn->CommitTrans();
		//$this->CallCrear_Asignar_Cama_Virtual($datos,$datos_estacion);
		$mensaje = "SE GENERO LA CAMA CORRECTAMENTE !";
		$titulo = "MENSAJE DEL SISTEMA";
		$accion = ModuloGetURL('app','EstacionE_Pacientes','user','CallIngresarPaciente',array("datos"=>$datos,"datosCamaPaciente"=>$data_cama,"pieza"=>$_PIEZA_ESPECIAL,"datos_estacion"=>$datos_estacion));
		$boton = "CONTINUAR";
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;

	}


	//revisa cuantas camas especiales, ya sean virtuales o ambulatorias posee la habitación.
	function Conteo_Camas_Especiales($pieza)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT COUNT(*)
								FROM camas
								WHERE pieza = '$pieza'
								AND sw_virtual IN('2','3')";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Error al intentar recalcular el numero de camas de la pieza<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}

			return $result->fields[0];
	}

	/**
  * Esta funcion asigna un numero de pieza virtual.
  * y en consulta externa.
  * @access private
  * @return boolean
  */
function AsignarPiezaVirtual()
{
   list($dbconn) = GetDBconn();
  $sql="select lpad(nextval('asignarPiezavirtual_seq'),3,0)";
  $result = $dbconn->Execute($sql);
  $dato=$result->fields[0];
  if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->fileError = __FILE__;
					$this->lineError = __LINE__;
          return false;
        }
    return $dato;
}




/**
  * Esta funcion asigna un numero cama virtual.
  * y en consulta externa.
  * @access private
  * @return boolean
  */
function AsignarCamaVirtual()
{
   list($dbconn) = GetDBconn();
  $sql="select lpad(nextval('asignarcamavirtual_seq'),3,'0')";
  $result = $dbconn->Execute($sql);
  $dato=$result->fields[0];
  if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->fileError = __FILE__;
					$this->lineError = __LINE__;
          return false;
        }
    return $dato;
}





//funcion q llama a la asignacion de cama virtual.
function CallCrear_Asignar_Cama_Virtual($datos,$datos_estacion,$swCambioCama)
{
  if(empty($datos))
	{
		$datos = $_REQUEST['datos'];
		$datos_estacion = $_REQUEST['datos_estacion'];
		$swCambioCama = $_REQUEST['swCambioCama'];

	}

	$this->Crear_Asignar_Cama_Virtual($datos,$datos_estacion,$swCambioCama);
	return true;

}

function GetCamasDisponibles ($estacion,$plan)
{

GLOBAL $ADODB_FETCH_MODE;
list($dbconn) = GetDBconn();

if(!empty($estacion))
{
		$filtro="c.estacion_id='$estacion' and";
}


		//revisamos primero dando prioridad a la tabla tipos_camas_excepcion_plan
		 $query="	select  b.*,c.*,d.cargo,e.descripcion as desc_cargo,d.descripcion as des_tipo
						--,d.tipo_clase_cama_id
	 					from camas b,piezas c, tipos_camas_excepcion_plan d,cups e
						where $filtro b.pieza=c.pieza
						and d.tipo_cama_id=b.tipo_cama_id
						and e.cargo=d.cargo
						and hb_estado_cama(cama)=0
						and d.plan_id='$plan'
						and c.sw_virtual ISNULL
						and b.sw_virtual='1' order by b.pieza,b.cama asc";
						$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						$result = $dbconn->Execute($query);

						if($result->RecordCount() < 1)
						{
							 $query="	select  b.*,c.*,d.cargo,e.descripcion as desc_cargo,d.descripcion as des_tipo
												--,d.tipo_clase_cama_id
												from camas b,piezas c, tipos_camas d,cups e
												where $filtro b.pieza=c.pieza
												and d.tipo_cama_id=b.tipo_cama_id
												and e.cargo=d.cargo
												and c.sw_virtual ISNULL
												and hb_estado_cama(cama)=0
												and b.sw_virtual='1' order by b.pieza,b.cama asc";
												$result = $dbconn->Execute($query);
						}

		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer las camas disponibles";
			$this->mensajeDeError = "Error al intentar obtener las camas disponibles de la estación de enfermería<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		$datosCamas=$result->GetRows();
		foreach($datosCamas as  $k => $v)
		{
			$salida[$v['estacion_id']][$v['pieza']][$v['cama']]=$v;
		}
		return $salida;
	}//fin GetCamasDisponibles




// [quitar]
function GetCamasOcupadas($estacion,$plan)
{

GLOBAL $ADODB_FETCH_MODE;
list($dbconn) = GetDBconn();

if(!empty($estacion))
{
		$filtro="c.estacion_id='$estacion' and";
}


		//revisamos primero dando prioridad a la tabla tipos_camas_excepcion_plan
		 $query="	select  b.*,c.*,d.cargo,e.descripcion as desc_cargo,d.descripcion as des_tipo,d.tipo_clase_cama_id
	 					from camas b,piezas c, tipos_camas_excepcion_plan d,cups e
						where $filtro b.pieza=c.pieza
						and d.tipo_cama_id=b.tipo_cama_id
						and e.cargo=d.cargo
						and hb_estado_cama(cama)=1
						and d.plan_id='$plan'
						and c.sw_virtual ISNULL
						and b.sw_virtual='1' order by b.pieza,b.cama asc";
						$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						$result = $dbconn->Execute($query);

						if($result->RecordCount() < 1)
						{
							$query="	select  b.*,c.*,d.cargo,e.descripcion as desc_cargo,d.descripcion as des_tipo,d.tipo_clase_cama_id
												from camas b,piezas c, tipos_camas d,cups e
												where $filtro b.pieza=c.pieza
												and d.tipo_cama_id=b.tipo_cama_id
												and e.cargo=d.cargo
												and c.sw_virtual ISNULL
												and hb_estado_cama(cama)=1
												and b.sw_virtual='1' order by b.pieza,b.cama asc";
												$result = $dbconn->Execute($query);
						}

		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer las camas disponibles";
			$this->mensajeDeError = "Error al intentar obtener las camas disponibles de la estación de enfermería<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		$datosCamas=$result->GetRows();
		foreach($datosCamas as  $k => $v)
		{
			$salida[$v['estacion_id']][$v['pieza']][$v['cama']]=$v;
		}
		return $salida;
	}//fin GetCamasDisponibles



	//funcion q revisa cuantas reservas hay por camas.[quitar]
  function RevisarReservas_X_cama($cama,$pieza)
	{
		list($dbconn) = GetDBconn();
		$sql = " SELECT COUNT(*) FROM
						estaciones_enfermeria_reservas_camas
						WHERE
						cama='$cama'
						AND pieza='$pieza'
						AND sw_estado='1'";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en estaciones_enfermeria_reservas_camas";
			$this->mensajeDeError = "Ocurrió una falla al buscar en  estaciones_enfermeria_reservas_camas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		return $result->fields[0];
	}


	//funcion q revisa reservas por el ingreso del paciente.[quitar]
	 function RevisarReservas_X_ingreso($ingreso)
	{
		list($dbconn) = GetDBconn();
		$sql = " SELECT COUNT(*) FROM
						estaciones_enfermeria_reservas_camas
						WHERE
						ingreso=$ingreso
						AND sw_estado='1'";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en estaciones_enfermeria_reservas_camas";
			$this->mensajeDeError = "Ocurrió una falla al buscar en  estaciones_enfermeria_reservas_camas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		return $result->fields[0];
	}




	//generamos la cama virtual del paciente.[quitar]
	function InsertarReserva()
	{
		$datos = $_REQUEST['datos'];
		$opcion = $_REQUEST['op'];
		$data_cama=explode("$",$opcion);
		$swCambioCama=$_REQUEST['swCambioCama'];
		$datos_estacion = $_REQUEST['datos_estacion'];

		if(empty($opcion))
		{
			$this->frmError["MensajeError"] = "SELECCIONE UNA CAMA PARA RESERVA !";
			$this->FrmReservaCamas($datos,$swCambioCama,$datos_estacion);
			return true;
		}


			list($dbconn) = GetDBconn();
			$sql = " SELECT COUNT(*) FROM
							estaciones_enfermeria_reservas_camas
							WHERE ingreso=$datos[4]
							AND cama='$data_cama[0]'
							AND pieza='$data_cama[1]'
							AND sw_estado='1'";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al buscar en estaciones_enfermeria_reservas_camas";
				$this->mensajeDeError = "Ocurrió una falla al buscar en  estaciones_enfermeria_reservas_camas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				//$dbconn->RollbackTrans();
				return false;
			}


			if($result->fields[0] > 0)
			{
					$this->frmError["MensajeError"] = "YA EXISTE UNA RESERVA PARA EL INGRESO &nbsp;$datos[4] , &nbsp;CAMA  &nbsp;$data_cama[0] &nbsp;EN LA HABITACIÓN  &nbsp;$data_cama[1] !";
					$this->FrmReservaCamas($datos,$swCambioCama,$datos_estacion);
					return true;
			}



		
		$sql = "INSERT INTO estaciones_enfermeria_reservas_camas(
						ingreso,
						fecha_reserva,
						estacion_id,
						pieza,
						cama,
						usuario_id,
						sw_estado
					)
	VALUES (
						".$datos[4].",
						'".date("Y-m-d h:i:s")."',
						'".$datos_estacion[estacion_id]."',
						'".$data_cama[1]."',
						'$data_cama[0]',
						".UserGetUID().",
						'1')";

		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al insertar en estaciones_enfermeria_reservas_camas";
			$this->mensajeDeError = "Ocurrió un error al intentar realizar la solicitud de dietas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			//$dbconn->RollbackTrans();
			return false;
		}


		$this->frmError["MensajeError"] = "RESERVA REALIZADA SATISFACTORIAMENTE !";
		$this->FrmReservaCamas($datos,$swCambioCama,$datos_estacion);
		return true;
}





		/*
		* Funcion que trae los tipos de servicios de camas
		*/
	function Traer_Tipos_Servicios_Cama($servicio)
	{

    list($dbconn) = GetDBconn();

		if(empty($servicio))
		{
	  	$query = "SELECT sw_virtual,descripcion
							FROM tipos_servicio_camas";
		}
		else
		{
	  	$query = "SELECT descripcion
							FROM tipos_servicio_camas WHERE sw_virtual='$servicio'";
		}
	  $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al traer los tipos de servicios de camas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;
      return false;
		}

			while (!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}

		return $var;
	}









	 //funcion del  modulo de estacion de enfermeria_pacientes
		/**
		*		CallListadoPacientesEstacion
		*
		*		subproceso 3->"CambioCama" del proceso "ingreso de pacientes a la estación de enfermería"
		*		UpdateCamaPaciente
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function UpdateCamaPaciente()
		{
			$datos = $_REQUEST['datos'];
			array_push($datos,$_REQUEST['cama'],$_REQUEST['pieza']);
		/*	$datos[0] = nombres						$datos[1] = apellidos			$datos[2] = PACIENTE_ID;
				$datos[3] = TIPO_ID_PACIENTE;		$datos[4] = INGRESO;			$datos[5] = ORDEN_HOSP;
				$datos[6] = CUENTA							$datos[7] = PLAN					$datos[8] = CAMA_ACTUAL;
				$datos[9] = PIEZA_ACTUAL				$datos[10] = ING_DPTO_ID	$datos[11] = FECHA_INGRESO
				$datos[12] = NUEVA_CAMA					$datos[13] = NUEVA_PIEZA*/

			$datosCamaPaciente = $_REQUEST['datosCamaPaciente'];
		/*	$datosCamaPaciente[0] = cama								$datosCamaPaciente[1] = desc_tipo_cama
				$datosCamaPaciente[2] = desc_cargo_cama			$datosCamaPaciente[3] = precio
				$datos[4] = plan_tarifario.por_cobertura		$datos[5] = tarifario_id
				$datos[6] = subgrupo_tarifario							$datos[7] = cargo;
				$datos[8] = grupo_tarifario_id*/

			/* este es el arreglo nuevo de $data_cama[].
					$data_cama[0]=$dato_cama[cama];
					$data_cama[1]=$dato_cama[desc_cargo];
					$data_cama[2]=$dato_cama[cargo];
					$data_cama[3]=$dato_cama[tipo_cama_id];
  		*/



			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			 $query = "select departamento,estacion_id from movimientos_habitacion
								WHERE fecha_egreso IS NULL AND
											numerodecuenta = $datos[6]
											AND ingreso=$datos[4]";
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar hacer el cierre de la habitacion actual<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				$dbconn->RollbackTrans();
				return false;
			}
			$var=$result->GetRowAssoc($ToUpper = false);			
			$result->Close();
			
			//1.hago cierre de habitacion
			 $query = "UPDATE movimientos_habitacion
								SET 	fecha_egreso = '".date("Y-m-d H:i:s")."'
								WHERE fecha_egreso IS NULL AND
											numerodecuenta = $datos[6]
											AND ingreso=$datos[4]";
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar hacer el cierre de la habitacion actual<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				//2.asgino nueva habitacion
				/*$query = "INSERT INTO movimientos_habitacion (
										ingreso_dpto_id,
										numerodecuenta,
										fecha_ingreso,
										fecha_egreso,
										cama )
									VALUES(
										'".$datos[10]."',
										'".$datos[6]."',
										'".$datos[11]."',
										NULL,
										'".$datos[12]."'
									);";*/


				 $query = "INSERT INTO movimientos_habitacion (
											ingreso_dpto_id,
											numerodecuenta,
											fecha_ingreso,
											fecha_egreso,
											cama,
											ingreso,
											precio,
											cargo,
											sw_excedente,
											tipo_cama_id,
											departamento,
											estacion_id,
											autorizacion_ext,
											autorizacion_int											
											)
										VALUES(
											'".$datos[10]."',
											'".$datos[6]."',
											'".date("Y-m-d H:i:s")."',
											NULL,
											'".$datos[12]."',
											'".$datos[4]."',
											0,
											'".$datosCamaPaciente[2]."',
											'0',
											$datosCamaPaciente[3],
											'".$var[departamento]."',
											'".$var[estacion_id]."',
											NULL,NULL
										);";


				$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Error al intentar asignar la nueva habitacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					//3. desocupar la cama que tiene actualmente
					$query = "UPDATE camas
                                   SET estado='1'
                                   WHERE cama = '".$datos[8]."'";

					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Error al intentar desocupar la habitacion anterior<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						$dbconn->RollbackTrans();
						return false;
					}
					else
					{
						//4. ocupar la nueva cama asignada
						$query = "UPDATE camas
											SET estado='0'
											WHERE cama = '".$datos[12]."'";

						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Error al intentar cambiar el estado de la nueva cama<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							return false;
						}
						else{
							$dbconn->CommitTrans();
						}
					}//ejecutó el update para desocupar la cama
				}//ejecutó el insert en mov_habitacion
			}//ejecutó el update cierre de habitacion
			unset($query); unset($result); unset($datos);
			$this->CallListadoPacientesEstacion($datos_estacion);
			return true;
		}


			/*
		* Funcion que trae los tipos de camas de tipos_camas_excepciones_plan
		*/
	function Traer_Tipos_Cama_excepciones($estacion,$plan_id)
	{

    list($dbconn) = GetDBconn();
	  $query = "SELECT a.tipo_cama_id,a.descripcion,precio_lista,cargo
							--,a.tipo_clase_cama_id
							FROM
							tipos_camas_excepcion_plan a,
							estaciones_tipos_camas_permitidos b
							WHERE
							a.plan_id='$plan_id'
							AND b.estacion_id='$estacion'
							and a.tipo_cama_id=b.tipo_cama_id";
	  $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al traer los tipos de camas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;
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
		* Funcion que trae los tipos de camas
		*/
	function Traer_Tipos_Cama($estacion)
	{

    list($dbconn) = GetDBconn();
	  $query = "SELECT a.tipo_cama_id,a.descripcion,precio_lista,cargo
							--,a.tipo_clase_cama_id
							FROM
							tipos_camas a,
							estaciones_tipos_camas_permitidos b
							WHERE
							b.estacion_id='$estacion'
							and a.tipo_cama_id=b.tipo_cama_id";
	  $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al traer los tipos de camas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;
      return false;
		}

			while (!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}

  return $var;
	}


		/**
		*		CallIngresarPaciente
		*
		*		subproceso 1->Asignar cama del proceso "ingreso de pacientes a la estación de enfermería"
		*		1.1.3.U => CallIngresarPaciente llama a la ultima interfaz en la que se piden los ultimos datos
		*		viene del link Asignar cama de la vista 2
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallIngresarPacienteCambioTipoCama()
		{
			$datos = $_REQUEST['datos']; //vector con los datos del paciente

				if(!$this->IngresarPaciente($datos,$_REQUEST['datos_estacion']))
				{
					$this->error = "No se puede cargar la vista";
					$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"IngresarPaciente\"";
					return false;
				}
			return true;
		}//fin CallIngresarPaciente



		/**
		*		CallCambioCargoIngresarPaciente
		*
		*		regresa a la pantalla de confirmacion para ingresar al paciente + cama.
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallCambioCargoIngresarPaciente()
		{

			//print_r($_REQUEST['opcion']);
			$datos = $_REQUEST['datos']; //vector con los datos del paciente

			if(empty($_REQUEST['opcion']))
			{
				$this->frmError["MensajeError"] = "ESCOGA UNA OPCION POR FAVOR";
				$this->DecisionCambioCargo($datos,$_REQUEST['datos_estacion'],$_REQUEST['spya']);
				return true;
			}

		
			$valores=explode("$",$_REQUEST['opcion']);
			$datos[13]=$valores[0];//aqui va la descripcion del cargo.
			$datos[14]=$valores[1]; //aqui va el cargo.
			$datos[15]=$valores[2]; //aqui va el tipo_cama_id.
			$datos[16]=$valores[3]; //aqui va el tipo_clase_cama_id.
			//print_r($datos);
			/*$datos[0] = nombres							$datos[1] = apellidos				$datos[2] = PACIENTE_ID;
				$datos[3] = TIPO_ID_PACIENTE;		$datos[4] = INGRESO;				$datos[5] = ORDEN_HOSP;
				$datos[6] = CUENTA							$datos[7] = PLAN						$datos[8] = TRASLADO;
				$datos[9] = descrip_ee_origen		$datos[10] = id_ee_origen		$datos[11] = PIEZA
				$datos[12] = CAMA;							$datos[13] = DESC_CARGO
				$datos[14] = cargo              $datos[15] = tipo_cama_id
				$datos[16] = tipo_clase_cama_id
				*/
//print_r($datos);
			if($datos[8] == '1' || $datos[8] == '2')
			{//$datos[8] == '1' => es traslado de estacion, $datos[8] == '2' => es traslado de dpto y no necesito insertar observacion
				if(!$this->InsertarPaciente($datos,$_REQUEST['datos_estacion']))
				{
					$this->error = "No se puede cargar la vista";
					$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"InsertarPaciente\"";
					return false;
				}
			}
			else
			{
				if(!$this->IngresarPaciente($datos,$_REQUEST['datos_estacion']))
				{
					$this->error = "No se puede cargar la vista";
					$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"IngresarPaciente\"";
					return false;
				}
			}
			return true;
		}//fin Call



		/**
		*		CallIngresarPaciente
		*
		*		subproceso 1->Asignar cama del proceso "ingreso de pacientes a la estación de enfermería"
		*		1.1.3.U => CallIngresarPaciente llama a la ultima interfaz en la que se piden los ultimos datos
		*		viene del link Asignar cama de la vista 2
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallIngresarPaciente()
		{
			$datos = $_REQUEST['datos']; //vector con los datos del paciente
			$datosCamaPaciente = $_REQUEST['datosCamaPaciente'];

			//antes era datos[16] ojo con eso
			if($datos[15]=='')
			{
				array_push($datos,$_REQUEST['pieza'],$datosCamaPaciente[0],$datosCamaPaciente[1],$datosCamaPaciente[2],$datosCamaPaciente[3],$datosCamaPaciente[4]); // y la cama a asignar
			}
			else
			{
					$datos[11]=$_REQUEST['pieza'];
					$datos[12]=$datosCamaPaciente[0];
					$datos[13]=$datosCamaPaciente[1];
					$datos[14]=$datosCamaPaciente[2];
					$datos[15]=$datosCamaPaciente[3];
					$datos[16]=$datosCamaPaciente[4];//tipo de clase de cama.
			}
			/*$datos[0] = nombres							$datos[1] = apellidos				$datos[2] = PACIENTE_ID;
				$datos[3] = TIPO_ID_PACIENTE;		$datos[4] = INGRESO;				$datos[5] = ORDEN_HOSP;
				$datos[6] = CUENTA							$datos[7] = PLAN						$datos[8] = TRASLADO;
				$datos[9] = descrip_ee_origen		$datos[10] = id_ee_origen		$datos[11] = PIEZA
				$datos[12] = CAMA;							$datos[13] = DESC_CARGO
				$datos[14] = cargo              $datos[15] = tipo_cama_id
				$datos[16] = tipo_clase_cama_id
				$datos[17] = fecha              $datos[18] = observacion
				*/
			//print_r($datos);
			if($datos[8] == '1' || $datos[8] == '2')
			{//$datos[8] == '1' => es traslado de estacion, $datos[8] == '2' => es traslado de dpto y no necesito insertar observacion
				if(!$this->InsertarPaciente($datos,$_REQUEST['datos_estacion']))
				{
					$this->error = "No se puede cargar la vista";
					$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"InsertarPaciente\"";
					return false;
				}
			}
			else
			{
				if(!$this->IngresarPaciente($datos,$_REQUEST['datos_estacion']))
				{
					$this->error = "No se puede cargar la vista";
					$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"IngresarPaciente\"";
					return false;
				}
			}
			return true;
		}//fin CallIngresarPaciente



		/**
		*		CallIngresarPaciente
		*
		*		subproceso 1->Asignar cama del proceso "ingreso de pacientes a la estación de enfermería"
		*		1.1.3.U => CallIngresarPaciente llama a la ultima interfaz en la que se piden los ultimos datos
		*		viene del link Asignar cama de la vista 2
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallRetornoIngresarPaciente()
		{
			$datos = $_REQUEST['datos']; //vector con los datos del paciente

			if($datos[8] == '1' || $datos[8] == '2')
			{//$datos[8] == '1' => es traslado de estacion, $datos[8] == '2' => es traslado de dpto y no necesito insertar observacion
				if(!$this->InsertarPaciente($datos,$_REQUEST['datos_estacion']))
				{
					$this->error = "No se puede cargar la vista";
					$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"InsertarPaciente\"";
					return false;
				}
			}
			else
			{
				if(!$this->IngresarPaciente($datos,$_REQUEST['datos_estacion']))
				{
					$this->error = "No se puede cargar la vista";
					$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"IngresarPaciente\"";
					return false;
				}
			}
			return true;
		}//fin CallIngresarPaciente




	    //esta funcion revisa que debe hacer cuando se va a ingresar a un paciente a la
		//estacion de enfermeria.
		//se modificaria $datos[8] = TRASLADO;
		//si es ->0 entonces creamos un ingreso nuevo del paciente.
		//si es ->1 entonces es un traslado de estacion a estacion dentro del departamento.
		//si es ->2 entonces es un traslado de departamento.
          function Revisar_decision_paciente_estacion($datos,$datos_estacion)
          {
               list($dbconn) = GetDBconn();

               //obtengo el numero de ingreso al dpto para obtener el numero de egreso... $datos[6] = cuenta
               $query = "SELECT count(*)
                         FROM ingresos_departamento
                         WHERE numerodecuenta = $datos[6]
                         AND ingreso = $datos[4];";
                    
/*               $query = "SELECT count(*)
                         FROM ingresos_departamento a,egresos_departamento b
                         WHERE a.numerodecuenta = $datos[6]
                         AND a.estacion_id='".$datos_estacion[estacion_id]."'
                         AND a.ingreso = $datos[4]
                         AND a.ingreso_dpto_id != b.ingreso_dpto_id";*/

               $result = $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al contar en ingresos_departamento";
                    $this->mensajeDeError = "Ocurrió un error al intentar consultar el numero de ingreso al departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
               if($result->fields[0] < 1)
               {
                    $datos[8]='0';
                    return $datos;
               }


               //obtengo el numero de ingreso al dpto para obtener el numero de egreso... $datos[6] = cuenta
               $query = "SELECT departamento
                         FROM estaciones_enfermeria
                         WHERE
                         estacion_id = '$datos[10]';";
               $result = $dbconn->Execute($query);
               $depto_estacion_origen=$result->fields[0];
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al contar en ingresos_departamento";
                    $this->mensajeDeError = "Ocurrió un error al intentar consultar el numero de ingreso al departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }


               $query = "SELECT departamento
                         FROM estaciones_enfermeria
                         WHERE
                         estacion_id ='".$datos_estacion[estacion_id]."';";
               $result = $dbconn->Execute($query);
               $depto_estacion=$result->fields[0];

               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al contar en ingresos_departamento";
                    $this->mensajeDeError = "Ocurrió un error al intentar consultar el numero de ingreso al departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }

               if($depto_estacion_origen==$depto_estacion)
               {
                    //si el departamento es igual es que el traslado es interno
                    $datos[8]='1';
                    return $datos;
               }
               else
               {
                    //si es diferente el dpto_origen=dpto_destino es por q es por departamento
                    //el traslado
                    $datos[8]='2';
                    return $datos;
               }
          }



		//funcion del mod estacion de enfermeria_pacientes
		/**
		*		InsertarPaciente
		*
		*		subproceso 1->Asignar cama del proceso "ingreso de pacientes a la estación de enfermería"
		*		1.1.4.U => InsertarPaciente llama a la ultima interfaz en la que se piden los ultimos datos
		*		viene del link ingresar de la vista 3
		*
		*		@Author [duvan]
		*		@access Public
		*		@return bool
		*		@param array => matriz con los datos del paciente a insertar
		*		@param array => datos de la estacion y clinica
		*/
		function InsertarPaciente($datos,$datos_estacion)
		{
			if(!$datos_estacion){
				$datos_estacion = $_REQUEST['datos_estacion'];
			}

			/*$datos[0] = nombres							$datos[1] = apellidos				$datos[2] = PACIENTE_ID;
				$datos[3] = TIPO_ID_PACIENTE;		$datos[4] = INGRESO;				$datos[5] = ORDEN_HOSP;
				$datos[6] = CUENTA							$datos[7] = PLAN						$datos[8] = TRASLADO;
				$datos[9] = descrip_ee_origen		$datos[10] = id_ee_origen		$datos[11] = PIEZA
				$datos[12] = CAMA;							$datos[13] = DESC_TIPO_CAMA	$datos[14] = cargo
				$datos[15] = tipo_cama_id       $datos[16] = tipo_clase_cama_id
				$datos[17] = OBSERVACIONES  $datos[18] = FECHA_hoy */

			if(!is_array($datos))
               {
				$datos = $_REQUEST['datos']; //vector con los datos del paciente y la cama asignada
				array_push($datos,$_REQUEST['observaciones'],date("Y-m-d H:i:s"));
               }

               //funcion que retorna la decision del paciente dentro de la estación.
			$datos=$this->Revisar_decision_paciente_estacion($datos,$datos_estacion);

			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$PuedoHacerCommit = array();

			if($datos[8] == 0)//no es un traslado de estacion, es un ingreso al departamento
			{
				$query = "SELECT nextval('ingresos_departamento_ingreso_dpto_id_seq');";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al intentar consultar el numero de ingreso al departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}
				else
				{
					if(!$result->EOF)
					{
						$ing_dpto_id = $result->fields[0];
						$query = "INSERT INTO ingresos_departamento (
												ingreso_dpto_id,
												numerodecuenta,
												departamento,
												estacion_id,
												fecha_ingreso,
												orden_hospitalizacion_id,
												observacion,
												estado,
												ingreso)
											VALUES (
												'".$ing_dpto_id."',
												'".$datos[6]."',
												'".$datos_estacion[departamento]."',
												'".$datos_estacion[estacion_id]."',
												'".$datos[18]."',
												'".$datos[5]."',
												'".$datos[17]."',
												'1',
												$datos[4]
											)";
						$result = $dbconn->Execute($query);

						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Ocurrió un error al intentar ingresar el paciente al departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							$PuedoHacerCommit[] = 0;
							return false;
						}
						else
						{
							$query = "UPDATE ingresos
                                             SET departamento_actual = '".$datos_estacion[departamento]."'
                                             WHERE ingreso = ".$datos[4].";";

							$result = $dbconn->Execute($query);

							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al ejecutar la conexion";
								$this->mensajeDeError = "error al intentar definir el departamento ultimo en la tabla ingresos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								$dbconn->RollbackTrans();
								$PuedoHacerCommit[] = 0;
								return false;
							}
							else
							{
								$query = "UPDATE ordenes_hospitalizacion
                                                  SET hospitalizado = '1'
                                                  WHERE orden_hospitalizacion_id = '".$datos[5]."';";
								
                                        $result = $dbconn->Execute($query);

								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al ejecutar la conexion";
									$this->mensajeDeError = "error al intentar definir el estado hospitalizado de la orden de hospitalización<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
									$dbconn->RollbackTrans();
									$PuedoHacerCommit[] = 0;
									return false;
								}
								else
                                        {
									$PuedoHacerCommit[] = 1;//no hago el commir aqui porque faltan los de abajo del else
                                        }    
							}
						}
					}
				}
			}
			else//ES UN TRASLADO DE UNA ESTACION A OTRA
			{
     	          //$datos[8] == 1 => traslado dentro del dpto, $datos[8] == 2 => traslado a otro dpto
				
				/*$datosCamaPaciente[0] = cama				$datosCamaPaciente[1] = desc_tipo_cama
				$datosCamaPaciente[2] = desc_cargo_cama			$datosCamaPaciente[3] = precio
				$datos[4] = plan_tarifario.por_cobertura		$datos[5] = tarifario_id
				$datos[6] = subgrupo_tarifario				$datos[7] = cargo;
				$datos[8] = grupo_tarifario_id*/

				$datosCamaPaciente = $_REQUEST['datosCamaPaciente'];
				$NoCubre = $_REQUEST['NoCubre'];
				
				//1. SELECCIONAR LA CAMA ACTUAL PARA LUEGO DESOCUPARLA - OK
				$query = "SELECT cama, ingreso_dpto_id
                              FROM  movimientos_habitacion
                              WHERE fecha_egreso IS NULL 
                              AND numerodecuenta = $datos[6]";
				$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "No se pudo seleccionar la cama en que actualmente se ubica el paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}
				else
				{
					if(!$result->EOF)
					{
						$camaActual = $result->fields[0]; //utilizado en el paso 3
						$ing_dpto_id = $result->fields[1];//utilizado en el insert de movimientos
						
                              //2.hago cierre de habitacion - OK
					     $query = "UPDATE movimientos_habitacion
                                        SET 	fecha_egreso = '".date("Y-m-d H:i:s")."'
                                        WHERE fecha_egreso IS NULL 
                                        AND numerodecuenta = $datos[6]";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Error al intentar hacer el cierre de la habitacion actual<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							$PuedoHacerCommit[] = 0;
							return false;
						}
						else
						{
							//3. desocupar la cama que tiene actualmente - OK
							$query = "UPDATE camas
                                             SET estado='1'
                                             WHERE cama = '".$camaActual."'"; 
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al ejecutar la conexion";
								$this->mensajeDeError = "Error al intentar desocupar la habitacion actual<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								$dbconn->RollbackTrans();
								$PuedoHacerCommit[] = 0;
								return false;
							}
							else
                                   {
								$PuedoHacerCommit[] = 1;
                                   }
						}//ejecutó update en mov_habitacion para desocupar la cama
					}//retornó resultados el select en "mov_hab" para obtener la cama
				}//ejecutó el select en "mov_hab" para obtener la cama
			}//--------------------------------------------------------fin es traslado
			if(!in_array(0,$PuedoHacerCommit))
			{
				//actualizar la estacion a la que llega, osea en la que estoy
				$query = "UPDATE ingresos_departamento
                              SET estacion_id='".$datos_estacion[estacion_id]."'
                              WHERE numerodecuenta=".$datos[6];
				$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Error al intentar asignar la nueva cama al paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
							//----cambio dar se agregaron dos campos nuevos estacion_id,departamento
				 $query = "INSERT INTO movimientos_habitacion (
											ingreso_dpto_id,
											numerodecuenta,
											fecha_ingreso,
											fecha_egreso,
											cama,
											ingreso,
											precio,
											cargo,
											sw_excedente,
											tipo_cama_id,
											departamento,
											estacion_id,
											autorizacion_ext,
											autorizacion_int
											)
										VALUES(
											'".$ing_dpto_id."',
											'".$datos[6]."',
											'".$datos[18]."',
											NULL,
											'".$datos[12]."',
											'".$datos[4]."',
											0,
											'".$datos[14]."',
											'0',
											$datos[15],
											'".$datos_estacion[departamento]."',
											'".$datos_estacion[estacion_id]."',
											NULL,NULL
										);";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Error al intentar asignar la nueva cama al paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						$dbconn->RollbackTrans();
						return false;
					}
					else
					{
						//OCUPAR LA CAMA ASIGNADA
						$query = "UPDATE camas
                                        SET estado = '0'
                                        WHERE cama = '".$datos[12]."';";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Error al intentar definir el estado ocupado de la nueva cama asignada al paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							return false;
						}
						else
						{
							$query = "DELETE
                                             FROM pendientes_x_hospitalizar
                                             WHERE ingreso = ".$datos[4]."";
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al ejecutar la conexion";
								$this->mensajeDeError = "Error al intentar eliminar al paciente de la lista de pendientes_x_hospitalizar<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								$dbconn->RollbackTrans();
								return false;
							}
							else
							{
								if($datos[8] == 1 || empty($datos[8]))
								{
									$dbconn->CommitTrans();
									unset($datos); unset($query); unset($result); unset($PuedoHacerCommit);
									$this->ListPacientesPorIngresar($_REQUEST['datos_estacion']);
								}
								elseif($datos[8] == 2)
								{//cuando es traslado a otro dpto actualizo el estado de egresos_dpto
									//obtengo el numero de ingreso al dpto para obtener el numero de egreso... $datos[6] = cuenta
									$query = "SELECT ingreso_dpto_id
                                                       FROM ingresos_departamento
                                                       WHERE numerodecuenta = $datos[6];";
									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al ejecutar la conexion";
										$this->mensajeDeError = "Error al intentar obtener el numero de ingreso del dpto<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
										$dbconn->RollbackTrans();
										return false;
									}
									else
									{
										$ingreso_dpto_id = $result->fields[0];
										$query = "SELECT egreso_dpto_id
                                                            FROM egresos_departamento
                                                            WHERE ingreso_dpto_id = $ingreso_dpto_id;";

										$result = $dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0)
										{
											$this->error = "Error al ejecutar la conexion";
											$this->mensajeDeError = "Error al intentar obtener el numero de egreso del dpto<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
											$dbconn->RollbackTrans();
											return false;
										}
                                                  $egreso_id = $result->fields[0];
                                                  
                                                  /*PARTE NUEVA DONDE SE INGRESA EL EGRESO DEL DPTO, YA QUE AVECES NO ESTA.*/
                                                  if($egreso_id == '')
                                                  {
                                                  	$sql="SELECT max(evolucion_id) FROM hc_evoluciones
                                                       	 WHERE ingreso = $datos[4]
                                                             AND estado = '0';";
                                                       $result = $dbconn->Execute($sql); 
                                                       if ($dbconn->ErrorNo() != 0)
                                                       {
                                                            $this->error = "Error al ejecutar la conexion";
                                                            $this->mensajeDeError = "Error al intentar obtener el numero de egreso del dpto<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                                                            $dbconn->RollbackTrans();
                                                            return false;
                                                       }
                                                       list($evolucion) = $result->FetchRow();
                                                  	
                                                       $query = "INSERT INTO egresos_departamento
                                                       				  (ingreso_dpto_id,
                                                                              fecha_egreso,
                                                                              evolucion_id,
                                                                              observacion,
                                                                              tipo_egreso,
                                                                              usuario_id,
                                                                              estado)
                                                  				VALUES ($ingreso_dpto_id,
                                                                      	   now(),
                                                                              $evolucion,
                                                                              'TRASLADO DE ESTACION',
                                                                              '5',
                                                                              ".UserGetUID().",
                                                                              '2');";
                                                                               
                                                       $result = $dbconn->Execute($query); 
                                                       if ($dbconn->ErrorNo() != 0)
                                                       {
                                                            $this->error = "Error al ejecutar la conexion";
                                                            $this->mensajeDeError = "Error al intentar obtener el numero de egreso del dpto<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                                                            $dbconn->RollbackTrans();
                                                            return false;
                                                       }
 											else
											{
												$dbconn->CommitTrans();
                                                            $this->ListPacientesPorEgresar($datos_estacion,$datos[3],$datos[2]);
                                                            //$this->ListPacientesPorIngresar($_REQUEST['datos_estacion']);
												unset($datos); unset($query); unset($result);
											}
                                                      
                                                  }
                                                  /*PARTE NUEVA DONDE SE INGRESA EL EGRESO DEL DPTO, YA QUE AVECES NO ESTA.*/
										
                                                  else
										{
											
											$query = "UPDATE egresos_departamento
                                                                 SET estado = '2'
                                                                 WHERE egreso_dpto_id = '$egreso_id';";

											$result = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->error = "Error al ejecutar la conexion";
												$this->mensajeDeError = "Error al intentar actualizar el estado del egreso del departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
												$dbconn->RollbackTrans();
												return false;
											}
											else
											{
												$dbconn->CommitTrans();
                                                            $this->ListPacientesPorEgresar($datos_estacion,$datos[3],$datos[2]);
                                                            //$this->ListPacientesPorIngresar($_REQUEST['datos_estacion']);
												unset($datos); unset($query); unset($result);
											}
										}
									}
								}
;							}
						}//ejecutó el delete de "pxh"
					}//ejecutó insert en mov_habitacion
				}//actualizó la estacion de enfermería
			}//como hizo bien los querys de la primera parte, se metió a hacer la segunda parte
			return true;
		}//fin InsertarPaciente

     //funcion del mod de estacion enfermeria_pacientes
	/**
	*		GetEstacionesDpto =>
	*
	*		obtiene las EE (diferentes a la actual) del departamento.
	*
	*		llamado desde el subproceso 2->"Cambio estacion de enfermeria antes del ingreso al dpto" del proceso "ingreso de pacientes a la estación de enfermería"
	*		1.2.1.1.H => GetEstacionesDpto obtiene las EE del departamento
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool-array-string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetEstacionesDpto($datos_estacion)
	{
		$query = "SELECT estacion_id, descripcion
							FROM estaciones_enfermeria
							WHERE departamento = '".$datos_estacion[departamento]."' AND
										estacion_id != '".$datos_estacion[estacion_id]."'
							ORDER BY descripcion;";
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar seleccionar las estaciones del departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF){
			return "ShowMensaje";
		}

		$i=0;
		while ($data = $result->FetchNextObject())
		{
			//$cuenta = $this->get_cuenta_x_ingreso($data->ING_ID);
			$estaciones[$i][0] = $data->ESTACION_ID;
			$estaciones[$i][1] = $data->DESCRIPCION;
			$i++;
		}
		return $estaciones;
	}//GetEstacionesDpto


	//funcion de estacion de enfermeria_pacientes
		/**
		*		CallMostrarDatosIngreso
		*
		*		Llamado desde la vista 1 -> link ver datos ingreso
		*		CallMostrarDatosIngreso
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallMostrarDatosIngreso()
		{
		
			if(!$this->MostrarDatosIngreso($_REQUEST['ingresoID'],$_REQUEST['retorno'],$_REQUEST['datos_estacion'],$_REQUEST['modulito'],$_REQUEST['datos']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"MostrarDatosIngreso\"";
				return false;
			}
			return true;
		}


		//funcion del mod estacion de enfermeria_pacientes
		/**
		*		GetContactosPaciente
		*
		*		Obtiene los familiares de conttacto del paciente de la tabla 'hc_contactos_paciente',
		*		los cuales se muestran en la opcion 'VER DATOS' del listado de pacientes de la estacion
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool - array
		*
		*/
		function GetContactosPaciente($ingreso)
		{
			$query = "SELECT	C.nombre_completo,
												C.telefono,
												C.direccion,
												T.descripcion AS parentesco
								FROM hc_contactos_paciente C,
											tipos_parentescos T
								WHERE C.ingreso = $ingreso AND
											T.tipo_parentesco_id = C.tipo_parentesco_id";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar seleccionar el contacto del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					return "ShowMensaje";
				}
				else
				{
					while (!$result->EOF){//while ($data = $result->FetchNextObject())
						$ContactosPaciente[] = $result->FetchRow();
					}
					return $ContactosPaciente;
				}
			}
		}


		/**************OJO ESTA SE VA PARA EL MOD ESTACIONE_PACIENTES***************/////
	/**
	*		GetPacientesPendientesXHospitalizar => Obtiene los pendientes por hospitalizar
	*
	*		llamado desde vista 1=> el subproceso1->"ingresar paciente" del proceso "ingreso de pacientes a la estación de enfermería"
	*		1.1.1.1.H => GetPacientesPendientesXHospitalizar()
	*		Obtiene los pacientes pendientes por ingresar al dpto almacenados en la tabla "pendientes_x_hospitalizar"
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool-array-string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetPacientesPendientesXHospitalizar($datos_estacion,$tipo,$pac)
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
                              estacion_origen
                         FROM pacientes,
                         (	SELECT    I.ingreso as ing_id,
                                        I.paciente_id as pac_id,
                                        I.tipo_id_paciente as tipo_id,
                                        P.estacion_destino as ee_destino,
                                        P.orden_hospitalizacion_id as orden_hosp,
                                        P.traslado as traslado,
                                        P.estacion_origen as estacion_origen
                              FROM 	ingresos I,
                                        pendientes_x_hospitalizar P
                              WHERE I.ingreso = P.ingreso AND
                                             P.estacion_destino = '".$datos_estacion[estacion_id]."'
                         ) as HOLA
                         WHERE paciente_id = pac_id AND
                                        tipo_id_paciente = tipo_id AND
                                        ee_destino = '".$datos_estacion[estacion_id]."'
                                        AND tipo_id_paciente='$tipo'
                                        AND paciente_id='$pac'
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
		return $Pacientes;
	}//fin GetPacientesPendientesXHospitalizar

/**************OJO ESTA SE VA PARA EL MOD ESTACIONE_PACIENTES***************/////


/**
	*		get_cuenta_x_ingreso
	*
	*		llamado desde el subproceso1->"Asignar cama" del proceso "ingreso de pacientes a la estación de enfermería"
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
			$this->mensajeDeError = "Ocurrió un error al intentar consultar la vía de ingreso del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
		*		CancelarPendientePorHospitalizar
		*
		*		Llama a una vista que permite confirmar la cancelación de una orden de hospitalización
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmCancelarPendientePorHospitalizar()
		{
			if(!$this->FrmCancelarPendientePorHospitalizar($_REQUEST['datos'],$_REQUEST['datos_estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmCancelarPendientePorHospitalizar\"";
				return false;
			}
			return true;
		}//CancelarPendientePorHospitalizar


			/**
		*		CancelarPendientePorHospitalizar
		*
		*		Cancela un orden de hospitalización antes de que el paciente este hospitalizado
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CancelarPendientePorHospitalizar()
		{
			$orden_hosp = $_REQUEST['orden_hosp'];
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();

			$query = "DELETE FROM pendientes_x_hospitalizar
								WHERE orden_hospitalizacion_id = $orden_hosp;";
			$result = $dbconn->Execute($query);
			if (!$result)
			{
				$this->error = "Error al ejecutar la consulta.<br>";
				$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
				return false;
			}
			else
			{
				$query = "UPDATE ordenes_hospitalizacion
									SET hospitalizado = '0'
									WHERE orden_hospitalizacion_id = $orden_hosp;";
				$result = $dbconn->Execute($query);
				if (!$result)
				{
					$this->error = "Error al ejecutar la consulta.<br>";
					$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
				else{
					$dbconn->CommitTrans();
				}
			}
			$this->CallListPacientesPorIngresar($datos_estacion);
			return true;
		}//CancelarPendientePorHospitalizar


	/**
	*		GetPacientesConMedicamentosPorSolicitar
	*
	*		obtiene los pacientes que tengan medicamentos recetados vigentes
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetPacientesConMedicamentosPorSolicitar($datos_estacion)
	{
 		 if(!$datos_estacion)
		{
			$datos_estacion=$_REQUEST['datos'];
		}

		/* bodegas_estaciones => departamentos_bodegas M*/
		$query = "(SELECT DISTINCT ON (C.paciente_id) C.paciente_id,
											F.pieza,
											E.cama,
											C.tipo_id_paciente,
											D.primer_nombre,
											D.primer_apellido,
											D.segundo_nombre,
											D.segundo_apellido,
											B.ingreso,
											B.numerodecuenta
								FROM  ingresos_departamento A,
											cuentas B,
											ingresos C,
											pacientes D,
											movimientos_habitacion E,
											camas F,
											hc_evoluciones G,
											hc_medicamentos_recetados H,
											empresas I,
											centros_utilidad J,
											bodegas K,
											bodegas_estaciones M
								WHERE A.estacion_id = '".$datos_estacion[estacion_id]."' AND
											B.numerodecuenta = A.numerodecuenta AND
											C.ingreso = B.ingreso  AND
											D.tipo_id_paciente = C.tipo_id_paciente AND
											D.paciente_id = C.paciente_id AND
											E.ingreso_dpto_id = A.ingreso_dpto_id AND
											E.fecha_egreso IS NULL AND
											F.cama = E.cama AND
											G.ingreso = C.ingreso AND
											H.evolucion_id = G.evolucion_id AND
											H.sw_estado = '2'  AND
											H.empresa_id = '".$datos_estacion[empresa_id]."' AND
											H.centro_utilidad = '".$datos_estacion[empresa_id]."' AND
											I.empresa_id = H.empresa_id AND
											J.centro_utilidad = H.centro_utilidad AND
											K.bodega = H.bodega AND
											H.bodega = M.bodega
								ORDER BY C.paciente_id,F.pieza, E.cama
								)
								UNION
								(SELECT DISTINCT ON (C.paciente_id) C.paciente_id,
												F.pieza,
												E.cama,
												C.tipo_id_paciente,
												D.primer_nombre,
												D.primer_apellido,
												D.segundo_nombre,
												D.segundo_apellido,
												B.ingreso,
												B.numerodecuenta
								FROM		ingresos_departamento A,
												cuentas B,
												ingresos C,
												pacientes D,
												movimientos_habitacion E,
												camas F,
												hc_evoluciones G,
												hc_mezclas_recetadas MR,
												hc_mezclas_recetadas_medicamentos MRM,
												empresas I,
												centros_utilidad J,
												bodegas K,
												bodegas_estaciones M
								WHERE 	A.estacion_id = '".$datos_estacion[estacion_id]."' AND
												B.numerodecuenta = A.numerodecuenta AND
												C.ingreso = B.ingreso  AND
												D.tipo_id_paciente = C.tipo_id_paciente AND
												D.paciente_id = C.paciente_id AND
												E.ingreso_dpto_id = A.ingreso_dpto_id AND
												E.fecha_egreso IS NULL AND
												F.cama = E.cama AND
												G.ingreso = C.ingreso AND
												MR.evolucion_id = G.evolucion_id AND
												MR.sw_estado = '2' AND
												MRM.mezcla_recetada_id = MR.mezcla_recetada_id AND
												MRM.empresa_id = '".$datos_estacion[empresa_id]."' AND
												MRM.centro_utilidad = '".$datos_estacion[empresa_id]."' AND
												I.empresa_id = MRM.empresa_id AND
												J.centro_utilidad = MRM.centro_utilidad AND
												K.bodega = MRM.bodega AND
												MRM.bodega = M.bodega
											);";
/*departamentos_bodegas por bodegas_estaciones*/

		list($dbconn) = GetDBconn();
		$resultEmp = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "conexion fallida al intentar consultar los medicamentos recetados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if ($resultEmp->EOF){
				return "ShowMensaje";
			}
			else
			{
				$i=0;
				while (!$resultEmp->EOF)//while ($data = $resultEmp->FetchNextObject())
				{
					$datos[$i] = $resultEmp->GetRowAssoc($ToUpper = false);//mi primer GetRow
					$i++;
					$resultEmp->MoveNext();
				}
				return $datos;
			}
		}
	}//fin GetPacientesConMedicamentosPorSolicitar


		/* funcion del modulo estacione_medicamento
	//#######################################################################################
	// plan terapeutico
	//#######################################################################################
		/**
		*		CallListMedicamentosPendientesXSolicitar
		*
		*		Hace un llamado a la vista que muestra los pacientes con medicamentos recetados
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallListMedicamentosPendientesXSolicitar()
		{
			if(!$this->ListMedicamentosPendientesXSolicitar($_REQUEST['datos_estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"ListMedicamentosPendientesXSolicitar\"";
				return false;
			}
			return true;
		}


		/**
		*		CallVerMedicamentosPorSolicitarPaciente
		*
		*		Hace un lladado  a la vista que muestra los medicamentos recetados de un paciente x
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallVerMedicamentosPorSolicitarPaciente()
		{
			//$datosPaciente = $_REQUEST["Paciente"];
			if(!$this->VerMedicamentosPorSolicitarPaciente($_REQUEST["Paciente"],$_REQUEST['datos_estacion']))//$_REQUEST['Paciente'],
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"VerMedicamentosPorSolicitarPaciente\"";
				return false;
			}
			return true;
		}

		/**
	*		GetMedicamentosPendientesSolicitadosBodega
	*
	*		Obtiene las solicitudes de medicamentos y mezclas de un ingreso X
	*		que han sido solicitados a bodega
	*		y que aun no se han recibido con estado en 0->sin depacho o 1->despachado
	*		utilizado para mostrarlo en el listado de medicamentos por solicitar
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@param integer => numero de ingreso del paciente
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetMedicamentosPendientesSolicitadosBodega($Ingreso,$datos_estacion)
	{
		$query = "SELECT J.*,
											SM.fecha_solicitud,
											SM.sw_estado
							FROM hc_solicitudes_medicamentos SM,
									(SELECT SMD.solicitud_id,
													SMD.consecutivo_d,
													NULL as mezcla_recetada_id,
													SMD.medicamento_id,
													SMD.evolucion_id,
													SMD.cant_solicitada,
													M.forma_farmaceutica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF
									FROM	hc_solicitudes_medicamentos_d SMD,
												medicamentos M,
												inventarios_productos INV,
												inventario_medicamentos invM,
												formas_farmaceuticas FF
									WHERE invM.codigo_producto = SMD.medicamento_id AND
												INV.codigo_producto = invM.codigo_producto AND
												M.codigo_medicamento = invM.codigo_medicamento AND
												FF.forma_farmaceutica = M.forma_farmaceutica
									UNION
									SELECT SMD.solicitud_id,
													SMD.consecutivo_d,
													SMD.mezcla_recetada_id,
													SMD.medicamento_id,
													SMD.evolucion_id,
													SMD.cant_solicitada,
													M.forma_farmaceutica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF
										FROM	hc_solicitudes_medicamentos_mezclas_d SMD,
													medicamentos M,
													inventario_medicamentos invM,
													inventarios_productos INV,
													formas_farmaceuticas FF
										WHERE invM.codigo_producto = SMD.medicamento_id AND
													INV.codigo_producto = invM.codigo_producto AND
													M.codigo_medicamento = invM.codigo_medicamento AND
													FF.forma_farmaceutica = M.forma_farmaceutica
									) AS J
							WHERE (SM.sw_estado != 2) AND
										SM.solicitud_id = J.solicitud_id AND
										SM.ingreso = $Ingreso AND
										SM.empresa_id = '".$datos_estacion['empresa_id']."' AND
										SM.centro_utilidad = '".$datos_estacion['centro_utilidad']."'
							ORDER BY J.solicitud_id DESC";//(SM.sw_estado = 0 OR SM.sw_estado = 1 OR SM.sw_estado = 2)


		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{ 
			$this->error = "Atención";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener las solicitudes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF){
			return "ShowMensaje";
		}
		else
		{
			$k = 0;
			while (!$result->EOF)
			{
				$datos[$k] = $result->GetRowAssoc($ToUpper = false);//mi primer GetRow
				$result->MoveNext();
				$k++;
			}
			return $datos;
		}
	}//fin  GetMedicamentosPendientesSolicitadosBodega($ingreso)

	/*
	*		GetMedicamentosPendientesSolicitadosBodega
	*
	*		Obtiene las solicitudes de medicamentos pedidos al paciente que aun no han sido recididos por EE
	*		en el ingreso utilizado para mostrarlo en el listado de medicamentos por solicitar
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@param integer => numero de ingreso del paciente
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetMedicamentosPendientesSolicitadosPaciente($Ingreso,$datos_estacion)
	{
		$query = 	"(SELECT SMP.consecutivo,
											NULL as mezcla_recetada_id,
											SMP.medicamento_id,
											SMP.evolucion_id,
											SMP.cant_solicitada,
											SMP.fecha_solicitud,
											SMP.ingreso,
											M.forma_farmaceutica,
											INV.descripcion as nomMedicamento,
											FF.descripcion as FF
								FROM hc_solicitudes_medicamentos_pacientes SMP,
											medicamentos M,
											inventario_medicamentos invM,
											inventarios_productos INV,
											formas_farmaceuticas FF
								WHERE SMP.sw_estado = '0' AND
											SMP.ingreso = ".$Ingreso." AND
											invM.codigo_producto = SMP.medicamento_id AND
											M.codigo_medicamento = invM.codigo_medicamento AND
											INV.codigo_producto = invM.codigo_producto AND
											FF.forma_farmaceutica = M.forma_farmaceutica
								ORDER BY SMP.consecutivo
							)
							UNION
							(SELECT SMP.consecutivo,
											SMP.mezcla_recetada_id,
											SMP.medicamento_id,
											SMP.evolucion_id,
											SMP.cant_solicitada,
											SMP.fecha_solicitud,
											SMP.ingreso,
											M.forma_farmaceutica,
											INV.descripcion as nomMedicamento,
											FF.descripcion as FF
								FROM  hc_solicitudes_mezclas_pacientes SMP,
											medicamentos M,
											inventario_medicamentos invM,
											inventarios_productos INV,
											formas_farmaceuticas FF
								WHERE SMP.sw_estado = '0' AND
											SMP.ingreso = ".$Ingreso." AND
											invM.codigo_producto = SMP.medicamento_id AND
											M.codigo_medicamento = invM.codigo_medicamento AND
											INV.codigo_producto = invM.codigo_producto AND
											FF.forma_farmaceutica = M.forma_farmaceutica
								ORDER BY SMP.consecutivo
							)";

		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Atención";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener las solicitudes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF)
		{
			return "ShowMensaje";
		}
		else
		{
			$k = 0;
			while (!$result->EOF)
			{
				$datos[$k] = $result->GetRowAssoc($ToUpper = false);//mi primer GetRow
				$result->MoveNext();
				$k++;
			}
			return $datos;
		}
	}

/**
	*		GetMedicamentosRecetados
	*
	*		obtiene los medicamentos recetados y vigentes del paciente X según el # ingreso
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array, boolean ó string
	*		@param integer => es el numero de ingreso del paciente
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetMedicamentosRecetados($ingreso,$datos_estacion)
	{
	/* unidad_dosis,cantidad, horario,sw_rango,duracion_id,hora_especifica
											H.empresa_id,
											H.centro_utilidad,
											H.bodega,
											H.medicamento_id,
											H.cantidad_total,
											H.indicacion_suministro
 */
  $query = "SELECT DISTINCT ON (G.evolucion_id,H.medicamento_id)
											G.evolucion_id,
											N.razon_social,
											J.descripcion as nomCentro,
											K.descripcion as nomBodega,
											I.descripcion as nomMedicamento,
											L.concentracion,
											V.nombre as viaAdmin,
											L.pos,
											L.forma_farmaceutica,
											F.descripcion as nomFF,
											H.*
							FROM  hc_evoluciones G,
										hc_medicamentos_recetados H,
										bodegas K,
										bodegas_estaciones M,
										medicamentos L,
										inventario_medicamentos invM,
										inventarios_productos I,
										formas_farmaceuticas F,
										empresas N,
										centros_utilidad J,
										hc_vias_administracion V
							WHERE G.ingreso = $ingreso AND
										H.evolucion_id = G.evolucion_id AND
										H.sw_estado = '2' AND
										H.empresa_id = '".$datos_estacion[empresa_id]."' AND
										H.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
										K.bodega = H.bodega AND
										K.bodega = M.bodega AND
										H.medicamento_id = invM.codigo_producto AND
										invM.codigo_producto = I.codigo_producto AND
										L.codigo_medicamento = invM.codigo_medicamento AND
										F.forma_farmaceutica = L.forma_farmaceutica AND
										N.empresa_id = H.empresa_id AND
										J.centro_utilidad = H.centro_utilidad AND
										V.via_administracion_id = H.via_administracion_id";

		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener los datos de los medicamentos recetados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			else
			{
				$i=0;
				while (!$result->EOF)//while ($data = $result->FetchNextObject())
				{
					$Medicamentos[$i] = $result->GetRowAssoc($ToUpper = false);//mi primer GetRow
					$i++;
					$result->MoveNext();
				}
				return $Medicamentos;
			}
		}
	}//fin GetMedicamentosRecetados()

	/**
	*		GetMezclasRecetadas
	*
	*		obtiene las mezclas recetadas al paciente según el # ingreso
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array, boolean ó string
	*		@param integer => es el numero de ingreso del paciente
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetMezclasRecetadas($ingreso,$datos_estacion)
	{
	/*
											MR.mezcla_recetada_id,
											MR.via_administracion_id,
											MR.evolucion_id,
											MR.observaciones,
	*/
		$query = "SELECT  MR.*,
											TUF. descripcion as des_tipo_calculo,
											MRM.mezcla_recetada_id,
											MRM.medicamento_id,
											MRM.empresa_id,
											MRM.centro_utilidad,
											MRM.bodega,
											MRM.cantidad,
											MRM.sw_pos,
											invM.codigo_medicamento,
											I.descripcion as nomMedicamento,
											L.forma_farmaceutica,
											FF.descripcion as nomFF,
											B.descripcion as nombodega,
											CU.descripcion as nomCentro,
											E.razon_social
							FROM 	hc_mezclas_recetadas MR,
									 	hc_mezclas_recetadas_medicamentos MRM,
										hc_evoluciones G,
										medicamentos L,
										inventario_medicamentos invM,
										inventarios_productos I,
										formas_farmaceuticas FF,
										bodegas B,
										centros_utilidad CU,
										empresas E,
										hc_tipo_unidades_frecuencia TUF
							WHERE MR.sw_estado = '2' AND
										MRM.mezcla_recetada_id = MR.mezcla_recetada_id AND
										G.ingreso = $ingreso AND
										MR.evolucion_id = G.evolucion_id AND
										MRM.medicamento_id = I.codigo_producto AND
										invM.codigo_medicamento = L.codigo_medicamento AND
										invM.codigo_producto = I.codigo_producto AND
										MRM.medicamento_id = invM.codigo_producto AND
										MRM.empresa_id = B.empresa_id AND
										MRM.centro_utilidad = B.centro_utilidad AND
										FF.forma_farmaceutica = L.forma_farmaceutica AND
										MRM.bodega = B.bodega AND
										MRM.empresa_id = '".$datos_estacion[empresa_id]."' AND
										MRM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
										CU.centro_utilidad = MRM.centro_utilidad AND
										CU.empresa_id = MRM.empresa_id AND
										E.empresa_id = MRM.empresa_id AND
										TUF.tipo_unidad_fr_id = MR.unidad_calculo
							ORDER BY MR.mezcla_recetada_id";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			else
			{
				/*$i=0;
				while (!$result->EOF)//while ($data = $result->FetchNextObject())
				{
					$Mezclas[$i] = $result->GetRowAssoc($ToUpper = false);//mi primer GetRow
					$i++;
					$result->MoveNext();
				}*/
				while ($data = $result->FetchRow())
				{
					$solucion = "SELECT sw_solucion
											 FROM medicamentos
											 WHERE codigo_medicamento = '".$data['codigo_medicamento']."'";

					GLOBAL $ADODB_FETCH_MODE;
					list($dbconn) = GetDBconn();
					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$resultado = $dbconn->Execute($solucion);
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
					$data['solucion'] = $resultado->fields[sw_solucion];
					$Mezclas[] = $data;
				}
				return $Mezclas;
			}
		}
	}//fin GetMezclasRecetadas($ingreso)


		/**
		*		CallFrmImpresionTarjetasDroga
		*
		*		Llama a la vista de impresion de las tarjetas de droga
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmImpresionTarjetasDroga()
		{
			if(!$this->FrmImpresionTarjetasDroga($_REQUEST['datos_estacion'],$_REQUEST['datos_paciente']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmImpresionTarjetaDroga\"";
				return false;
			}
			return true;
		}
		/**
		*		CallFrmImpresionLiquidosParenterales
		*
		*		Llama a la vista formato de impresion de liquidos parenterales
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmImpresionLiquidosParenterales()
		{
			if(!$this->FrmImpresionLiquidosParenterales($_REQUEST['datos_estacion'],$_REQUEST['datos_paciente']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmImpresionLiquidosParenterales\"";
				return false;
			}
			return true;
		}


		/**
		*		funcion de darling
		*/
		function BuscarEvolucion($ingreso)
		{
				list($dbconn) = GetDBconn();
				 $query = "select b.evolucion_id from hc_evoluciones as b
									where b.ingreso='$ingreso'
									and b.estado='1'
									and b.fecha_cierre=(select max(fecha_cierre) from hc_evoluciones	where ingreso='$ingreso')";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

			//	if(!$result->EOF)
			//	{  return $result->fields[0];
				   //$var[1]=$result->fields[1];
			//	}

				return $result->fields[0];
		}




	//la funcion anterior esta obsoleta... revisar q modulos llaman a esta funcion para
	//poder quitarla.....
	function BuscarEvolucion_Pac($ingreso,$sw)
	{
			list($dbconn) = GetDBconn();
      //sw es un switche q sirve para sacar toda la informacion del medico, si este esta null
			//solo hariamos el conteo las evoluciones abiertas
			if(empty($sw))
			{
			
					$query = "select COUNT(evolucion_id) from hc_evoluciones
												where ingreso='$ingreso'
												and estado='1'";
							$result=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Guardar en la Base de Datos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
							}
		
							return $result->fields[0];
			}
			
						$query = "select usuario_id,evolucion_id from hc_evoluciones
												where ingreso='$ingreso'
												and estado='1'";
							$result=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Guardar en la Base de Datos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
							}
							
									$i=0;
									while (!$result->EOF)
									{
											$vector[$i]=$result->GetRowAssoc($ToUpper = false);
											$result->MoveNext();
											$i++;
									}
		
						for($r=0;$r<sizeof($vector);$r++)
						{
						$query = "
										SELECT x.tipo_profesional,x.descripcion,b.nombre,c.fecha,c.evolucion_id
										FROM profesionales_usuarios a, profesionales b,hc_evoluciones c,
										tipos_profesionales x
										WHERE a.tipo_tercero_id=b.tipo_id_tercero and
										a.tercero_id=b.tercero_id and
										a.usuario_id=".$vector[$r][usuario_id]."
										AND c.evolucion_id=".$vector[$r][evolucion_id]."
										AND x.tipo_profesional=b.tipo_profesional";
										$result=$dbconn->Execute($query);
										while (!$result->EOF)
										{
											$vector2[]=$result->GetRowAssoc($ToUpper = false);
											$result->MoveNext();
										}
									
						}		
						return $vector2;
	}


	/*
	* Cambiamos el formato timestamp a un formato de fecha legible para el usuario
	*/
	function FormateoFechaLocal($fecha)
	{

			if(!empty($fecha))
			{
					$f=explode(".",$fecha);
					$fecha_arreglo=explode(" ",$f[0]);
					$fecha_real=explode("-",$fecha_arreglo[0]);
					return strftime("%A, %d de %B de %Y",strtotime($fecha_arreglo[0]));

			}
			else
			{
				return "-----";
			}

			return true;
	}
	
	

		//damos salida al paciente [esta es de prueba]
		function DarSalida()
		{
				$ingreso=$_REQUEST['ingreso'];
				$egreso_dpto_id=$_REQUEST['egreso_dpto_id'];
				$cama= $_REQUEST['cama'];

				$query = "UPDATE egresos_departamento
                              SET estado = '2'
                              WHERE egreso_dpto_id = ".$egreso_dpto_id.";";

				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al intentar actualizar el estado del egreso del departamento.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}

					$query = "INSERT INTO egresos_departamento_cuentas_x_liquidar (
																																			egreso_dpto_id,
																																			usuario_id
																																		)
																														VALUES (
																																			".$egreso_dpto_id.",
																																			".UserGetUID()."
																																		);";

				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al intentar insertar los datos para cuentas pendientes por liquidar..<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}


					$query = "UPDATE camas
											SET estado = '0'
											WHERE cama = '".$cama."';";
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Ocurrió un error al actualizar datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							return false;
						}



					$query = "UPDATE movimientos_habitacion
											SET 	fecha_egreso = '".date("Y-m-d H:i:s")."'
											WHERE fecha_egreso ISNULL AND
														ingreso = $ingreso";
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Ocurrió un error al actualizar datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							return false;
						}


				$dbconn->CommitTrans();
				$mensaje = "SOLICITUD REALIZADA SATISFACTORIAMENTE";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("datos_estacion"=>$datos_estacion));
				$boton = "VOLVER AL MENÚ DE ESTACIÓN";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
		}


		//funcion en la cual insertamos o modificamos la nota de enfermeria final.
		function Insertar_Nota_Enfermeria()
		{
			list($dbconn) = GetDBconn();
			$datos_estacion=$_REQUEST['datos_estacion'];
			$tipo=$_REQUEST['tipo_id'];
			$pac=$_REQUEST['pac'];
			$ingreso=$_REQUEST['ing'];
			$nombre=$_REQUEST['nombre'];
			$cama= $_REQUEST['cama'];

			$query = "SELECT COUNT(*)
									FROM  hc_notas_enfermeria_descripcion
									WHERE ingreso='$ingreso'
									--AND evolucion_id='".$this->BuscarEvolucion($ingreso)."'
									AND date(fecha_registro)='".date("Y-m-d")."'";
			$result=$dbconn->Execute($query);
			if($result->fields[0]>0)
			{
					$query = "UPDATE hc_notas_enfermeria_descripcion
									SET descripcion='".$_REQUEST['obs']."'
									WHERE ingreso='$ingreso'
								--	AND evolucion_id='".$this->BuscarEvolucion($ingreso)."'
									AND date(fecha_registro)='".date("Y-m-d")."'";
									$result=$dbconn->Execute($query);
			}
			else
			{
						$evol=$this->BuscarEvolucion($ingreso);
						if(empty($evol)){$evol='NULL';}
						$query = "INSERT INTO hc_notas_enfermeria_descripcion
									(descripcion,
									evolucion_id,
									usuario_id,
									fecha_registro,
									ingreso)VALUES
									('".$_REQUEST['obs']."',
										$evol,
									".UserGetUID().",
									'".date("Y-m-d")."',
									$ingreso
									)";
									$result=$dbconn->Execute($query);
			}

		if($_REQUEST['retorno']==1)
		{
			$this->FormaSacarPacienteConsultaUrg($datos_estacion,$tipo,$pac,$nombre,$ingreso);
			return true;
		}
		else
		{
			$this->ListPacientesPorEgresar($datos_estacion,$tipo,$pac,$cama);
			return true;
		}	
}





	//*********************esto debe ir en estacionE_pacientes***************************////////
	/**
	*		GetPacientesPendientesXEgresar
	*
	*		Obtiene los pacientes que tienen orden de egreso (tipo_egreso != 4 que no es
	*		en realizadad un egreso) y != 2 que es el finalizado
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*		@param array => datos de la ubicacion actual: dpto, estacion, empresa, usuario, etc
	*/
	function GetPacientesPendientesXEgresar($datos_estacion,$tipo,$pac)
	{
		$query = " SELECT ED.egreso_dpto_id,
                              ED.ingreso_dpto_id,
                              ED.fecha_egreso,
                              ED.evolucion_id,
                              ED.observacion,
                              ED.tipo_egreso,
                              ED.estado,
                              TE.descripcion as descEgreso,
                              HE.ingreso,
                              I.paciente_id,
                              I.tipo_id_paciente,
                              P.primer_nombre,
                              P.segundo_nombre,
                              P.primer_apellido,
                              P.segundo_apellido
                    	FROM egresos_departamento ED,
                              tipos_egresos TE,
                              hc_evoluciones HE,
                              ingresos I,
                              pacientes P
                         WHERE ED.ingreso_dpto_id IN (SELECT ingreso_dpto_id
                                                       FROM ingresos_departamento
                                                       WHERE departamento = '".$datos_estacion[departamento]."' AND
                                                            estacion_id = '".$datos_estacion[estacion_id]."' AND
                                                            estado = '1') AND
                              ED.tipo_egreso != '4' AND
                              ED.estado != '2' AND
                              TE.tipo_egreso = ED.tipo_egreso AND
                              HE.evolucion_id = ED.evolucion_id AND
                              HE.ingreso = I.ingreso AND
                              P.paciente_id = I.paciente_id AND
                              P.tipo_id_paciente = I.tipo_id_paciente
                              AND P.tipo_id_paciente='$tipo'
                              AND P.paciente_id='$pac'";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener los pacientes por egresar<br><br>".$dbconn->ErrorMsg()."<br><br>".$despachos;
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
					$egresos[] = $data;
				}
				return $egresos;
			}
		}
	}//GetPacientesPendientesXEgresar


	//CAMBIO DAR
//modulo=EstacionEnfermeria&metodo=CallMenu&control_id=2&estacion[descripcion1]=CLINICA+DE+OCCIDENTE+TULUA+S.A.&estacion[descripcion2]=TULUA&estacion[descripcion3]=URGENCIAS&estacion[descripcion4]=URGENCIAS&estacion[titulo_atencion_pacientes]=&estacion[empresa_id]=01&estacion[centro_utilidad]=01&estacion[unidad_funcional]=02&estacion[departamento]=010201&estacion[estacion_id]=1&estacion[descripcion5]=URGENCIAS&estacion[hc_modulo_medico]=Hospitalizacion&estacion[hc_modulo_enfermera]=EstacionEnfermeria&estacion[hc_modulo_consulta_urgencias]=UrgenciasConsulta&control_descripcion=CONTROL+MEDICAMENTOS+PACIENTE

	function SacarPacienteConsultaUrgencias()
	{
			$this->FormaSacarPacienteConsultaUrg($_REQUEST['datos_estacion'],$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['ingreso']);
			return true;
	}

	function DarSalidaCosultaUrgencias()
	{
				list($dbconn) = GetDBconn();
				$query = "UPDATE pacientes_urgencias	SET sw_estado = '4'
									WHERE ingreso = '".$_REQUEST['ingreso']."';";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al actualizar datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}

				$mensaje = "SOLICITUD REALIZADA SATISFACTORIAMENTE";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("datos_estacion"=>$_REQUEST['datos_estacion']));
				$boton = "VOLVER AL MENÚ DE ESTACIÓN";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
	}
	//FIN CAMBIO DAR
	
	
	
	function GetDatosPaciente_Info($ingreso)
	{
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$query="SELECT a.ingreso, b.historia_numero, b.historia_prefijo,c.primer_apellido,
			c.segundo_apellido, c.primer_nombre, c.segundo_nombre, sexo_id, c.fecha_nacimiento,
			c.residencia_direccion, c.residencia_telefono, c.tipo_pais_id, c.tipo_dpto_id,
			c.tipo_mpio_id, i.pais, j.departamento, h.municipio,e.tercero_id, e.tipo_tercero_id,
			g.nombre_tercero, e.plan_id, e.plan_descripcion, f.tipo_afiliado_nombre, c.paciente_id,
			c.tipo_id_paciente, a.estado, gestacion.estado as gestacion
			FROM ingresos as a, historias_clinicas as b
			left join gestacion on
			(b.paciente_id=gestacion.paciente_id and b.tipo_id_paciente=gestacion.tipo_id_paciente),
			pacientes as c
			left join tipo_mpios as h on (c.tipo_pais_id=h.tipo_pais_id and c.tipo_dpto_id=h.tipo_dpto_id and	c.tipo_mpio_id=h.tipo_mpio_id)
			left join tipo_pais as i on (c.tipo_pais_id=i.tipo_pais_id)
			left join tipo_dptos as j on (c.tipo_pais_id=j.tipo_pais_id and
			c.tipo_dpto_id=j.tipo_dpto_id),
			cuentas as d left join tipos_afiliado as f on (d.tipo_afiliado_id=f.tipo_afiliado_id),
			planes as e, terceros as g
			WHERE a.ingreso=".$ingreso." and a.tipo_id_paciente=b.tipo_id_paciente and
			a.paciente_id=b.paciente_id and a.tipo_id_paciente=c.tipo_id_paciente and
			a.paciente_id=c.paciente_id and d.ingreso=a.ingreso and d.plan_id=e.plan_id and
			e.tipo_tercero_id=g.tipo_id_tercero and e.tercero_id=g.tercero_id;";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				return false;
			}
			else {
				if (!$result) {
					$this->error = "Error al tratar de realizar la consulta.<br>";
					$this->mensajeDeError = $query;
					return false;
				}
				$paciente = $result->GetRowAssoc($ToUpper = false);
			}
			return $paciente;
	}

}//fin de la clase
?>
