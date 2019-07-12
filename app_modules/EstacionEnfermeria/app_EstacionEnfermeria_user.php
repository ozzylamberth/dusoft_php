 <?
 
 
 /**
 * $Id: app_EstacionEnfermeria_user.php,v 1.22 2006/02/20 20:54:22 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Estacion de Enfermeria modulo para la atencion del paciente 
 */

 
 
/**
*		class app_EstacionEnfermeria_user
*
*		Clase que maneja todas los metodos que llaman a las vistas relacionadas a la estación de Enfermería
*		ubicadas en la clase hija html
*		ubicacion => app_modules/EstacionEnfermeria/app_EstacionEnfermeria_user.php
*		fecha creación => 04/05/2004 10:35 am
*
*		@Author => jairo Duvan Diaz Martinez
*		@version =>
*		@package SIIS
*/
class app_EstacionEnfermeria_user extends classModulo
{
	var $frmError = array();


	/**
	*		app_EstacionEnfermeria_user()
	*
	*		constructor
	*
	*		@Author Arley Velásquez - Jairo Duvan Diaz
	*		@access Public
	*		@return bool
	*/
	function app_EstacionEnfermeria_user()//Constructor padre
	{
		return true;
	}


	/**
	*		main
	*
	*		Esta función permite seleccionar todas las estaciones de enfermeria
	*		organizadas por su empresa, centro de utilidad, unidad funcional y departamento
	*		a la cual pertenecen.
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	/*function main()
	{
		if(!$this->FrmLogueoEstacion($_REQUEST['jaime_modulo'],$_REQUEST['jaime_metodo']))
		{
			$this->error = "No se puede cargar la vista";
			$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmLogueoEstacion\"";
			return false;
		}
		return true;
	}//FIN main*/
	function main()
	{
		if(!$this->FrmLogueoEstacion($_REQUEST['modulo_externo'],$_REQUEST['metodo_externo']))
		{
			$this->error = "No se puede cargar la vista";
			$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmLogueoEstacion\"";
			return false;
		}
		return true;
	}//FIN main



	/**
	*		GetLogueoEstacion
	*
	*		Esta función obtiene las estaciones de enfermeria a las cuales eñ usuario puede ingresar.
	*
	*		@Author Arley Velásquez
	*		@access Public
	*		@return bool
	*/
	function GetLogueoEstacion($modulo,$metodo)
	{
     	ECHO $modulo."<BR>";
          ECHO $metodo;
          EXIT;
	 unset($_SESSION['IMAGEN']);//variable de session de generacion de la grafica.
	 $query =  "SELECT e.razon_social as descripcion1,
											cu.descripcion as descripcion2,
											uf.descripcion as descripcion3,
											c.descripcion as descripcion4,
											b.titulo_atencion_pacientes,
											c.empresa_id,
											c.centro_utilidad,
											c.unidad_funcional,
											c.departamento,
											a.estacion_id,
											b.departamento,
											b.descripcion as descripcion5,
											b.hc_modulo_medico,
											b.hc_modulo_enfermera,
											b.hc_modulo_consulta_urgencias
							FROM  estaciones_enfermeria_usuarios a,
										estaciones_enfermeria b,
										departamentos c,
										empresas e,
										centros_utilidad cu,
										unidades_funcionales uf
							WHERE a.usuario_id=".UserGetUID()." AND
										b.estacion_id=a.estacion_id AND
										c.departamento=b.departamento AND
										e.empresa_id=c.empresa_id AND
										cu.empresa_id=c.empresa_id AND
										cu.centro_utilidad=c.centro_utilidad AND
										uf.empresa_id=c.empresa_id AND
										uf.centro_utilidad=c.centro_utilidad AND
										uf.unidad_funcional=c.unidad_funcional
							ORDER BY c.empresa_id, c.centro_utilidad, c.unidad_funcional, c.departamento,a.estacion_id
										";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if (!$result) {
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}

		while ($data = $result->FetchRow())
		{
			$estaciones[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']][$data['descripcion4']][$data['descripcion5']]=$data;
		}

		$mtz[0]="EMPRESA";
		$mtz[1]="CENTRO UTILIDAD";
		$mtz[2]="UNIDAD FUNCIONAL";
		$mtz[3]="DEPARTAMENTO";
		$mtz[4]="ESTACION";

		if (!empty($modulo)){
			$url[0]='app';
			$url[1]=$modulo;
			$url[2]='user';
			$url[3]=$metodo;
			$url[4]='AtencionUrgencias';
		}
		else{

			unset($_SESSION['ESTACION']['VECTOR_SOL']);//var de session que tiene el vector de solicitud de medicamentos.
			unset($_SESSION['ESTACION']['VECTOR_DESP']);//var de session que tiene el vector de despacho de medicamentos.
			unset($_SESSION['ESTACION']['VECTOR_DEV']); //var de session q tiene el vector de devoluciones de medicamentos.
			unset($_SESSION['HISTORIACLINICA']['DATOS']['ESTACION']);//destruir los datos de la estacion q estan en session..
			unset($_SESSION['ESTACION_ENFERMERIA']);

			$url[0]='app';
			$url[1]='EstacionEnfermeria';
			$url[2]='user';
			$url[3]='CallMenu';
			$url[4]='estacion';
		}

		$Datos[0]=$mtz;
		$Datos[1]=$estaciones;
		$Datos[2]=$url;
		return $Datos;
	}


		/**
		*		CallMenu
		*
		*		Esta función lista el menu de la estacion de enfermeria.
		*
		*		@Author Jairo Duvan	Diaz	Martinez
		*		@access Public
		*		@param array datos de la estacion
		*		@return bool
		*/
		function CallMenu($datos_estacion,$grafic)
		{
      unset($_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']);
      //esta variable se inicializa cada vez q tengamos permisos de usuario
			//pero este modulo tendra tres modulos hijos que son tambien modulos
			//para entrar a los otros modulos obligatoriamente debreá entrar  a este modulo
			//ya que preguntaremos   por la variable de session en los otros modulos.
			//y si no esta inicializada no le daremos permisos para accesarlos.
			$_SESSION['ESTACION_ENFERMERIA']['MADRE']['ACTIVO']='INICIALIZADO';
			if(!$datos_estacion){
				$datos_estacion = $_REQUEST['estacion'];
			}
			if(!$this->Menu($datos_estacion,$grafic))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"Menu\"";
				return false;
			}
			return true;
		}


		//mandamos una arreglo con todos los ingresos para que verifique si tiene
		//un control de tranfusiones.
		function GetNoControlTransfusiones($arr_ingreso,$control,$dpto)
		{
					unset($sql);//reseteamos esta variable  q contiene los ingresos.
					for($i=0;$i<sizeof($arr_ingreso);$i++)
					{
							$sql.=$arr_ingreso[$i]['ingreso'];

							if($i<sizeof($arr_ingreso)-1)
							{
								$sql.=',';
							}
					}
			 $query = "SELECT COUNT(*) FROM	hc_controles_paciente a, hc_evoluciones b
										WHERE
										a.ingreso IN($sql) AND
										a.evolucion_id=b.evolucion_id
										AND b.departamento='$dpto'
										AND control_id='".$control."'";

					list($dbconn) = GetDBconn();
					$result = $dbconn->Execute($query);
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurrió un error al intentar obtener los pacientes con control de trasnfusiones.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						return false;
					}
						return $result->fields[0];
		}//GetPacientesContro

		


   /*
	 *  OJO ESTAS FUNCIONES ANTES ESTABAN EN ESTACION DE ENFERMERIA
 	 */
			/**
	*		GetCensoTipo
	*
	*		Obtienen los pacientes de la estacion para mostraelos en el censo
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array, string, bool
	*		@param string => id de la estacion
	*/
	//OJO A ESTA FUNCION HAY  QUE LLAMARLA GetCensoTipo1 YA Q HAY UN MODULO LLAMADO GetCensoTipo
	function GetCensoTipo1($Estacion)
	{//pacientes de hospitalizacion
      if(empty($Estacion))
			{
				$Estacion=$_REQUEST['estacion'];
			}

			$query = "SELECT  MH.movimiento_id,
												MH.ingreso_dpto_id,
												MH.numerodecuenta,
												MH.fecha_ingreso,
												MH.cama,
												A.estacion_id,
												A.descripcion,
												A.departamento,
												F.descripcion AS desc_departamento,
												B.pieza,
												C.ingreso,
												D.fecha_ingreso,
												D.paciente_id,
												D.tipo_id_paciente,
												E.primer_nombre,
												E.segundo_nombre,
												E.primer_apellido,
												E.segundo_apellido,
												G.plan_id,
												G.plan_descripcion,
												G.tercero_id,
												G.tipo_tercero_id,
												C.rango,
												H. nombre_tercero
								FROM movimientos_habitacion AS MH,
										(
											SELECT  ID.ingreso_dpto_id,
															ID.numerodecuenta,
															ID.departamento,
															ID.estacion_id,
															EE.descripcion,
															ID.orden_hospitalizacion_id
											FROM  ingresos_departamento ID,
														estaciones_enfermeria EE
											WHERE ID.estado = '1' AND
														EE.estacion_id = ID.estacion_id AND
														EE.estacion_id = '$Estacion'
										) AS A,
											camas B,
											cuentas C,
											ingresos D,
											pacientes E,
											departamentos F,
											planes G,
											terceros H
								WHERE MH.ingreso_dpto_id = A.ingreso_dpto_id AND
											MH.fecha_egreso IS NULL AND
											MH.cama = B.cama AND
											C.numerodecuenta = A.numerodecuenta AND
											C.ingreso = D.ingreso AND
											C.estado = '1' AND
											D.paciente_id = E.paciente_id AND
											D.tipo_id_paciente = E.tipo_id_paciente AND
											F.departamento = A.departamento AND
											G.plan_id = C.plan_id AND
											H.tercero_id = G.tercero_id AND
											H.tipo_id_tercero = G.tipo_tercero_id
								ORDER BY MH.cama, B.pieza";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar los pacientes en las estacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while ($data = $result->FetchRow())//while ($data = $result->FetchNextObject())
				{
						$datoscenso[hospitalizacion][] = $data;//$i++;
				}
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
											C.rango,
											H. nombre_tercero
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
				$this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla 'pacientes_urgencias'<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while ($data = $result->FetchRow()){
					$datoscenso[urgencias][] = $data;
				}
			}
  	if(!$datoscenso){
				return "ShowMensaje";
			}
  		return $datoscenso;
	}//fin GetCensoTipo



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
				$this->mensajeDeError = "Ocurrió un error al intentar obtener los controles de la estación.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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




	/*-------------------OJO CON ESTO QUE SE PASO AL MODULO EstacionE_ControlPacientes------------------------------*/
		/**
		*		QControlesEstacion
		*
		*		@Author Arley Velásquez Castillo
		*		@access Public
		*		@return bool
		*		@param string => id del departamento
		*		@param string => id de la estacion
		*		@param string => control_id
		*/
		function QControlesEstacion($departamento,$estacion,$control)
		{
               if(empty($departamento))
               {
                    $estacion=$_REQUEST['estacion_id'];
                    $departamento=$_REQUEST['departamento'];
                    $control=$_REQUEST['control'];
               }

			list($dbconn) = GetDBconn();
			if (empty($control))
			{

       /* $query="SELECT COUNT(*)

								FROM ingresos_departamento ID,
								movimientos_habitacion MH

								WHERE ID.estado = '1'
								AND ID.departamento = '0202'
								AND ID.estacion_id='4'
								AND  MH.ingreso_dpto_id = ID.ingreso_dpto_id
								AND MH.fecha_egreso IS NULL";*/

				$query = "SELECT C.ingreso
									FROM movimientos_habitacion AS MH,
											(
												SELECT ID.ingreso_dpto_id,
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
												C.ingreso= CTRL.ingreso	";


				$result = $dbconn->Execute($query);
				if (!$result) {
					$this->error = "Error al ejecutar la consulta.<br>";
					$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
					return false;
				}
				return $result->FetchRow();
			}
			else
			{
				/*$query="SELECT COUNT(*)

								FROM ingresos_departamento ID,
								movimientos_habitacion MH

								WHERE ID.estado = '1'
								AND ID.departamento = '0202'
								AND ID.estacion_id='4'
								AND  MH.ingreso_dpto_id = ID.ingreso_dpto_id
								AND MH.fecha_egreso IS NULL";*/

			$query = "SELECT  C.ingreso
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
												CTRL.control_id='".$control."'";

				$result = $dbconn->Execute($query);
				if (!$result) {
					$this->error = "Error al ejecutar la consulta.<br>";
					$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
					return false;
				}
				return $result->FetchRow();
			}
			return false;
		}
/*-------------------OJO CON ESTO QUE SE PASO AL MODULO EstacionE_ControlPacientes------------------------------*/



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
	function GetPacientesPendientesXHospitalizar($datos_estacion)
	{

	  if(!$datos_estacion)
		{
			$datos_estacion=$_REQUEST['datos'];
		}

		/*$query="SELECT ingreso

						FROM  pendientes_x_hospitalizar P
						WHERE
						P.estacion_destino = '".$datos_estacion[estacion_id]."'";*/

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
									(	SELECT  I.ingreso as ing_id,
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
							ORDER BY  primer_nombre,
												segundo_nombre,
												primer_apellido,
            segundo_apellido";//pacientes_x_ingreso_x_pxh*/

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
	function GetPacientesPendientesXEgresar($datos_estacion)
	{

	  if(!$datos_estacion){
				$datos_estacion = $_REQUEST['datos'];
			}

		 /*	$query="SELECT ED.egreso_dpto_id
							FROM egresos_departamento ED, ingresos_departamento ID

							WHERE ID.departamento = '".$datos_estacion[departamento]."'
							AND ID.estacion_id = '".$datos_estacion[estacion_id]."'
							AND ID.estado = '1'
							AND ED.tipo_egreso != '4'
							AND ED.estado != 2
							AND ID.ingreso_dpto_id=ED.ingreso_dpto_id";*/


    //Para q usar un gran query para habilitar un link.
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
										ED.estado != 2 AND
										TE.tipo_egreso = ED.tipo_egreso AND
										HE.evolucion_id = ED.evolucion_id AND
										HE.ingreso = I.ingreso AND
										P.paciente_id = I.paciente_id AND
										P.tipo_id_paciente = I.tipo_id_paciente";

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

//*********************esto debe ir en estacionE_pacientes***************************////////


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



	/*funcion del mod estacione_medicamentos*/
	/**
	*		GetInsumosPendientesPorRecibir
	*
	*		Obtiene todos los medicamentos pendientes por recibir (despachados)
	*		El primer subquery obtiene los medicamentos de los insumos solicitados
	*		El segundo subquery obtiene los insumos solicitados
	*		El tercer subquery obtiene los medicamentos de los insumos efectivamente despachados por bodega y su equivalente al solicitado en el caso que aplique
	*		El cuarto subquery obtiene los medicamentos de los insumos no despchados por bodega
	*		El quinto subquery obtiene los insumos efectivamente despachados por bodega y su equivalente al solicitado en el caso que aplique
	*		El sexto subquery obtiene los insumos no despchados por bodega
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool - array
	*		@param array => datos de la ubicacion actual: dpto, estacion, empresa, usuario, etc
	*/
	function GetInsumosPendientesPorRecibir($datos_estacion)
	{

		if(!$datos_estacion)
		{
			$datos_estacion=$_REQUEST['datos'];
		}
		$query="select  B.solicitud_id as solicitud_sol,
										B.fecha_solicitud as fecha_sol,
										B.ingreso,
										B.codigo_producto as codigo_producto_sol,
										B.cant_solicitada as cant_solicitada_sol,
										B.forma_farmaceutica as forma_farmaceutica_sol,
										B.nomMedicamento as nomMedicamento_sol,
										B.FF as FF_sol,
										A.solicitud_id as solicitud_id_des,
										A.fecha_solicitud as fecha_solicitud_des,
										A.bodega,
										A.codigo_producto as codigo_producto_des,
										A.forma_farmaceutica as forma_farmaceutica_des,
										A.nomMedicamento as nomMedicamento_des,
										A.FF as FF_des,
										A.documento as documento_des,
										A.cant_enviada,
										A.reemplazo,
										I.paciente_id,
										I.tipo_id_paciente,
										I.ingreso,
										P.primer_nombre,
										P.segundo_nombre,
										P.primer_apellido,
										P.segundo_apellido,
										CM.cama,
										PZ.pieza
						from
						(
								select
												SM.solicitud_id,
												SM.fecha_solicitud,
												SM.ingreso,
												SID.consecutivo_d,
												SID.codigo_producto,
												SID.cantidad as cant_solicitada,
												INV.descripcion as nomMedicamento,
												M.forma_farmaceutica,
												FF.descripcion as FF
								from
												hc_solicitudes_medicamentos SM,
												hc_solicitudes_insumos_d SID,
												medicamentos M,
												inventarios_productos INV,
												inventario_medicamentos invM,
												formas_farmaceuticas FF
								where
												SM.sw_estado = '1' AND
												SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
												SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
												SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
												SID.solicitud_id = SM.solicitud_id AND
												invM.codigo_producto = SID.codigo_producto AND
												INV.codigo_producto = invM.codigo_producto AND
												M.codigo_medicamento = invM.codigo_medicamento AND
												FF.forma_farmaceutica = M.forma_farmaceutica
								UNION
								select
												SM.solicitud_id,
												SM.fecha_solicitud,
												SM.ingreso,
												SID.consecutivo_d,
												SID.codigo_producto,
												SID.cantidad as cant_solicitada,
												INV.descripcion as nomMedicamento,
												NULL as forma_farmaceutica,
												NULL as FF
								from
												hc_solicitudes_medicamentos SM,
												hc_solicitudes_insumos_d SID,
												inventarios_productos INV,
												hc_insumos_estacion HIE,
												hc_tipos_insumo HTI
								where
												SM.sw_estado = '1' AND
												SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
												SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
												SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
												SID.solicitud_id = SM.solicitud_id AND
												INV.codigo_producto = SID.codigo_producto AND
												HIE.codigo_producto = SID.codigo_producto AND
												HIE.empresa_id = SM.empresa_id AND
												HIE.insumo_id = HTI.insumo_id AND
												HTI.tipo_insumo = 'I'
						) as B
						LEFT JOIN
						(
							(		select
													SM.solicitud_id,
													SM.fecha_solicitud,
													SM.ingreso,
													SM.bodega,
													SM.ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													M.forma_farmaceutica,
													FF.descripcion as FF,
													BDHS.documento,
													BDD.cantidad as cant_enviada,
													null as reemplazo
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													medicamentos M,
													inventarios_productos INV,
													inventario_medicamentos invM,
													formas_farmaceuticas FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.documento = BDHS.documento AND
													BDD.codigo_producto = SID.codigo_producto AND
													invM.codigo_producto = SID.codigo_producto AND
													INV.codigo_producto = invM.codigo_producto AND
													M.codigo_medicamento = invM.codigo_medicamento AND
													FF.forma_farmaceutica = M.forma_farmaceutica
									UNION
									select
													SM.solicitud_id,
													null as fecha_solicitud,
													SM.ingreso,
													null as bodega,
													null as ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													NULL as forma_farmaceutica,
													NULL as FF,
													BDHS.documento,
													BDD.cantidad as cant_enviada,
													BDD.codigo_producto
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													bodegas_documentos_d_equiv_ins BDDE,
													medicamentos M,
													inventarios_productos INV,
													inventario_medicamentos invM,
													formas_farmaceuticas FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.documento = BDHS.documento AND
													BDDE.consecutivo = BDD.consecutivo AND
													BDDE.consecutivo_d = SID.consecutivo_d  AND
													BDD.empresa_id = SM.empresa_id AND
													BDD.centro_utilidad = SM.centro_utilidad AND
													invM.codigo_producto = BDD.codigo_producto AND
													INV.codigo_producto = invM.codigo_producto AND
													M.codigo_medicamento = invM.codigo_medicamento AND
													FF.forma_farmaceutica = M.forma_farmaceutica
							)
							UNION
							(		select
													SM.solicitud_id,
													SM.fecha_solicitud,
													SM.ingreso,
													SM.bodega,
													SM.ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													NULL as forma_farmaceutica,
													NULL as FF,
													BDHS.documento,
													BDD.cantidad as cant_enviada,
													null as reemplazo
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													inventarios_productos INV,
													hc_insumos_estacion HIE,
													hc_tipos_insumo HTI
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.documento = BDHS.documento AND
													BDD.codigo_producto = SID.codigo_producto AND
													INV.codigo_producto = SID.codigo_producto AND
													HIE.empresa_id = SM.empresa_id AND
													HIE.insumo_id = HTI.insumo_id AND
													HIE.codigo_producto = SID.codigo_producto AND
													HTI.tipo_insumo = 'I'
									UNION
									select
													SM.solicitud_id,
													null as fecha_solicitud,
													SM.ingreso,
													null as bodega,
													null as ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													NULL as forma_farmaceutica,
													NULL as FF,
													BDHS.documento,
													BDD.cantidad as cant_enviada,
													BDD.codigo_producto
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													bodegas_documentos_d_equiv_ins BDDE,
													inventarios_productos INV,
													hc_insumos_estacion HIE,
													hc_tipos_insumo HTI
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.documento = BDHS.documento AND
													BDDE.consecutivo = BDD.consecutivo AND
													BDDE.consecutivo_d = SID.consecutivo_d  AND
													BDD.empresa_id = SM.empresa_id AND
													BDD.centro_utilidad = SM.centro_utilidad AND
													INV.codigo_producto = SID.codigo_producto AND
													HIE.empresa_id = SM.empresa_id AND
													HIE.insumo_id = HTI.insumo_id AND
													HIE.codigo_producto = SID.codigo_producto AND
													HTI.tipo_insumo = 'I'
							)
						) as A
						ON (B.codigo_producto = A.codigo_producto AND B.solicitud_id = A.solicitud_id),
								ingresos I,
								cuentas C,
								movimientos_habitacion MH,
								camas CM,
								piezas PZ,
								pacientes P
						WHERE I.ingreso = B.ingreso AND
									C.ingreso = I.ingreso AND
									MH.numerodecuenta = C.numerodecuenta AND
									MH.fecha_egreso IS NULL AND
									CM.cama = MH.cama AND
									CM.pieza = PZ.pieza AND
									Pz.estacion_id = '".$datos_estacion[estacion_id]."' AND
									P.paciente_id = I.paciente_id AND
									P.tipo_id_paciente = I.tipo_id_paciente
						ORDER BY B.solicitud_id";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar las solicitudes pendientes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
					$Solicitudes[$data['solicitud_sol']][] = $data;
				}
				return $Solicitudes;
			}
		}
	}//GetInsumosPendientesPorRecibir




	/*funcion del mod estacione_medicamentos*/
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
	//buscar aqui
	function GetPacientesConMedicamentosPorDesp($datos_estacion)
	{
          if(!$datos_estacion)
		{
			$datos_estacion=$_REQUEST['datos'];
		}
			list($dbconn) = GetDBconn();

		//tener perndientes estado='1' de cuentas....
		$query="SELECT ingreso FROM cuentas
                    WHERE numerodecuenta IN(SELECT a.numerodecuenta FROM  ingresos_departamento a,movimientos_habitacion b

                    WHERE b.fecha_egreso ISNULL
                    AND b.ingreso_dpto_id=a.ingreso_dpto_id
                    AND a.departamento='".$datos_estacion[departamento]."'
                    AND a.estado='1'
                    AND a.estacion_id='".$datos_estacion[estacion_id]."')";
                    $resulta=$dbconn->execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
 		         }

                    //	if($resulta->EOF){return "ShowMensaje";}

                    while(!$resulta->EOF)
                    {
                         $var[]=$resulta->GetRowAssoc($ToUpper = false);
                         $resulta->MoveNext();
	               }
						/***********************BUSCAMOS CONSULTA URGENCIAS***************************/
						
			$query="select b.ingreso 
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
                                   where a.sw_estado in('1','7')";
						
								$resulta=$dbconn->execute($query);
								if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
								}
		
								while(!$resulta->EOF)
								{
										
											$var[]=$resulta->GetRowAssoc($ToUpper = false);
											$resulta->MoveNext();
								}
						
						/***********************BUSCAMOS PENDIENTES X HOSP**********************/
											
			$query = "SELECT    ingreso
							FROM pacientes,
                                   (	SELECT  I.ingreso,
                                                            I.paciente_id as pac_id,
                                                            I.tipo_id_paciente as tipo_id,
                                                            P.estacion_destino as ee_destino
                                        FROM 	ingresos I,
                                                       cuentas x,
                                                       pendientes_x_hospitalizar P
                                        WHERE I.ingreso = P.ingreso AND
                                                       I.ingreso=x.ingreso AND
                                                       x.estado='1' AND
                                                       P.estacion_destino = '".$datos_estacion[estacion_id]."'
                                   ) as HOLA
							WHERE paciente_id = pac_id AND
                                   tipo_id_paciente = tipo_id AND
                                   ee_destino = '".$datos_estacion[estacion_id]."'";
               $resulta=$dbconn->execute($query);
               if ($dbconn->ErrorNo() != 0) {
                              $this->error = "Error al Cargar el Modulo";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              return false;
               }
               while(!$resulta->EOF)
               {
                         
                         $var[]=$resulta->GetRowAssoc($ToUpper = false);
                         $resulta->MoveNext();
               }
						
               //este for es para saber si hay medicamentos despachados	
               for($i=0;$i<sizeof($var);$i++)
               {
               	$query="SELECT COUNT(*) FROM hc_solicitudes_medicamentos
                                        WHERE ingreso='".$var[$i][ingreso]."'
                                        AND sw_estado IN('1','5')
                                        AND tipo_solicitud='M'
                                        AND estacion_id='".$datos_estacion[estacion_id]."'";
                    $resulta=$dbconn->execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                   $this->error = "Error al Cargar el Modulo";
                                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   return false;
                    }
                    if($resulta->fields[0]>0)
                    {
                         $vect[0]=1;//aca sabremos si hay medicamentos
                         break;
                    }
               }
							
               //este for es para saber si hay insumos despachados	
               for($i=0;$i<sizeof($var);$i++)
               {
                    $query="SELECT COUNT(*) FROM hc_solicitudes_medicamentos
                                             WHERE ingreso='".$var[$i][ingreso]."'
                                             AND sw_estado IN('1','5')
                                             AND tipo_solicitud='I'
                                             AND estacion_id='".$datos_estacion[estacion_id]."'";
                    $resulta=$dbconn->execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                   $this->error = "Error al Cargar el Modulo";
                                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   return false;
                    }
                    if($resulta->fields[0]>0)
                    {
                         $vect[1]=1;//aca sabremos si hay insumos
                         break;
                    }
               }
							
			return $vect;
		}





/*funcion del mod estacione_medicamentos*/
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
	//buscar aqui
	function GetPacientesConMedicamentosPorSolicitar($datos_estacion)
	{
 		 if(!$datos_estacion)
		{
			$datos_estacion=$_REQUEST['datos'];
		}
			list($dbconn) = GetDBconn();

			$query="SELECT ingreso FROM cuentas
						WHERE numerodecuenta IN(SELECT a.numerodecuenta FROM  ingresos_departamento a,movimientos_habitacion b

						WHERE b.fecha_egreso ISNULL
						AND b.ingreso_dpto_id=a.ingreso_dpto_id
						AND a.departamento='".$datos_estacion[departamento]."'
						AND a.estado='1'
						AND a.estacion_id='".$datos_estacion[estacion_id]."')";
						$resulta=$dbconn->execute($query);
						if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
						}

						//if($resulta->EOF){return "ShowMensaje";}

						while(!$resulta->EOF)
						{
								$var[]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
     				}
						
						/************************ mostramos los de urgencias ****************/
						
			$query="select b.ingreso 
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
									";
						
								$resulta=$dbconn->execute($query);
								if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
								}
		
								while(!$resulta->EOF)
								{
									$var[]=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->MoveNext();
								}
						
						/************************ mostramos los de pendientes X hospitalizar********/
						
				$query = "SELECT 
											ingreso
							FROM pacientes,
									(	SELECT  I.ingreso,
														I.paciente_id as pac_id,
														I.tipo_id_paciente as tipo_id,
														P.estacion_destino as ee_destino
										FROM 	ingresos I,
													cuentas x,
													pendientes_x_hospitalizar P
										WHERE I.ingreso = P.ingreso AND
													I.ingreso=x.ingreso AND
													x.estado='1' AND
													P.estacion_destino = '".$datos_estacion[estacion_id]."'
									) as HOLA
							WHERE paciente_id = pac_id AND
										tipo_id_paciente = tipo_id AND
										ee_destino = '".$datos_estacion[estacion_id]."'";
						$resulta=$dbconn->execute($query);
								if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
								}
						while(!$resulta->EOF)
						{
								$var[]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
     				}
						
									
						//esta es la parte de solicitudes de medicamentos
						for($i=0;$i<sizeof($var);$i++)
						{
						 $query="SELECT COUNT(*) FROM hc_solicitudes_medicamentos
											WHERE ingreso='".$var[$i][ingreso]."'
											AND sw_estado='0'
											AND tipo_solicitud='M'";
											$resulta=$dbconn->execute($query);
								if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
								}
								if($resulta->fields[0]>0)
								{
										$vect[0]=1;
										break;
								}

							}
							
						//esta es la parte de solicitudes de insumos
						for($i=0;$i<sizeof($var);$i++)
						{
						 $query="SELECT COUNT(*) FROM hc_solicitudes_medicamentos
											WHERE ingreso='".$var[$i][ingreso]."'
											AND sw_estado='0'
											AND tipo_solicitud='I'";
											$resulta=$dbconn->execute($query);
								if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
								}
								if($resulta->fields[0]>0)
								{
										$vect[1]=1;
										break;
								}

							}
							
					return $vect;
}

/*departamentos_bodegas por bodegas_estaciones*/

/*

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
			}
		}
		return $datos;
	}//fin GetPacientesConMedicamentosPorSolicitar

	*/

/*funcion del mod estacione_controlpacientes*/



//funcion del mod medicamentos(estacion e)
	/**
	*		FrmDevolucionMedicamentos
	*
	*		Muestra los medicamentos que pueden ser devueltos => Alex me dió esta formula:
	*		a la suma de medicamentos solicitados le resto la suma de los medicamentos devueltos
	*		ya sea que estén en espera de aceptacion de devoluciion o que ya hayan sido procesados
	*
	*		@Author Jairo Duvan Diaz Martinez
	*		@access Public
	*		@return boolean
	*		@param array => pacientes con ordenes de medicamentos
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetDevolucionMedicamentos($datos_estacion)
	{

		 if(!$datos_estacion)
		{
			$datos_estacion=$_REQUEST['datos'];
		}
			list($dbconn) = GetDBconn();

     $query="SELECT ingreso FROM cuentas
						WHERE numerodecuenta IN(SELECT a.numerodecuenta FROM  ingresos_departamento a,movimientos_habitacion b

						WHERE b.fecha_egreso ISNULL
						AND b.ingreso_dpto_id=a.ingreso_dpto_id
						AND a.departamento='".$datos_estacion[departamento]."'
						AND a.estado='1'
						AND a.estacion_id='".$datos_estacion[estacion_id]."')";
						$resulta=$dbconn->execute($query);
						if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
						}

						while(!$resulta->EOF)
						{
								$var[]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
     				}

						for($i=0;$i<sizeof($var);$i++)
						{
							 $query="SELECT COUNT(*) FROM inv_solicitudes_devolucion
											WHERE ingreso='".$var[$i][ingreso]."'
											AND sw_estado='1'
											AND empresa_id='".$datos_estacion[empresa_id]."'
											AND estacion_id='".$datos_estacion[estacion_id]."'";
											$resulta=$dbconn->execute($query);
								if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
								}
								if($resulta->fields[0]>1)
								{
										return 1;
								}

							}
					return "ShowMensaje";
	}













/*
	-----FUNCION DE ARLEY PARA SACAR LAS DEVOLUCIONES HAY Q ANALIZAR SI ESTO FUNCIONA-------
	function GetDevolucionMedicamentos($PacientesConOrdenes,$datos_estacion)
	{


	 if(!$PacientesConOrdenes){$PacientesConOrdenes=$_REQUEST['PacientesConOrdenes'];}
	 if(!$datos_estacion){$datos_estacion=$_REQUEST['datos'];}

		//A B C
			//1 0 0 => A
			//1 0 1 => A-C
			//1 1 0 => A-B
			//1 1 1 => A-B-C
			//query A => med e ins despachados
			////query B => med e ins devueltos
			//query C => solicitudes de med e ins pendientes por ser aceptados "Devueltos" (en la bodega)
		//
		foreach ($PacientesConOrdenes as $key=>$value)
		{
			$qDespachados ="SELECT A.*,
														 case when A.sum is not null and B.sum is null and C.sum is null then A.sum
																	when A.sum is not null and B.sum is null and C.sum is not null then A.sum-C.sum
																	when A.sum is not null and B.sum is not null and C.sum is null then A.sum-B.sum
																	when A.sum is not null and B.sum is not null and C.sum is not null then A.sum-B.sum-C.sum
																	else NULL
															end as suma
											FROM	(
															(	SELECT  d.codigo_producto,
																				sum(d.cantidad),
																				I.descripcion as nomMedicamento,
																				L.concentracion_forma_farmacologica,
																				F.descripcion as nomFF
																FROM 	cuentas_detalle as a,
																			bodegas_documentos as b,
																			inv_conceptos as c,
																			bodegas_documentos_d as d,
																			bodegas_documentos_d_cobertura e,
																			inventarios_productos I,
																			medicamentos L,
																			inventarios invM,
																			inv_med_cod_forma_farmacologica F
																WHERE	a.numerodecuenta = $value[numerodecuenta] and
																			b.empresa_id = '".$datos_estacion[empresa_id]."' and
																			b.centro_utilidad = '".$datos_estacion[centro_utilidad]."' and
																			b.transaccion = a.transaccion and
																			b.concepto_inv = c.concepto_inv and
																			d.documento = b.documento and
																			d.empresa_id = b.empresa_id and
																			d.centro_utilidad = b.centro_utilidad and
																			d.bodega = b.bodega and
																			d.prefijo = b.prefijo and
																			c.concepto_inv = 'DME' AND
																			e.consecutivo_detalle = d.consecutivo AND
																			I.codigo_producto = d.codigo_producto AND
																			invM.codigo_producto = d.codigo_producto AND
																			L.codigo_medicamento = invM.codigo_producto AND
																			F.cod_forma_farmacologica = L.cod_forma_farmacologica
																GROUP BY d.codigo_producto, I.descripcion, L.concentracion_forma_farmacologica,F.descripcion

																UNION

																SELECT  d.codigo_producto,
																				sum(d.cantidad),
																				I.descripcion as nomMedicamento,
																				null as concentracion_forma_farmacologica,
																				null as nomFF
																FROM 	cuentas_detalle as a,
																			bodegas_documentos as b,
																			inv_conceptos as c,
																			bodegas_documentos_d as d,
																			bodegas_documentos_d_cobertura e,
																			inventarios_productos I,
																			hc_insumos_estacion HIE,
																			hc_tipos_insumo HTI
																WHERE	a.numerodecuenta = $value[numerodecuenta] and
																			b.empresa_id = '".$datos_estacion[empresa_id]."' and
																			b.centro_utilidad = '".$datos_estacion[centro_utilidad]."' and
																			b.transaccion = a.transaccion and
																			b.concepto_inv = c.concepto_inv and
																			d.documento = b.documento and
																			d.empresa_id = b.empresa_id and
																			d.centro_utilidad = b.centro_utilidad and
																			d.bodega = b.bodega and
																			d.prefijo = b.prefijo and
																			c.concepto_inv = 'DME' AND
																			e.consecutivo_detalle = d.consecutivo AND
																			I.codigo_producto = d.codigo_producto AND
																			HIE.insumo_id = HTI.insumo_id AND
																			HIE.codigo_producto = d.codigo_producto AND
																			HTI.tipo_insumo = 'I'
																GROUP BY d.codigo_producto, I.descripcion
															)
														) AS A
														LEFT JOIN
														(
															( SELECT  d.codigo_producto,
																				sum(d.cantidad),
																				I.descripcion as nomMedicamento,
																				L.concentracion_forma_farmacologica,
																				F.descripcion as nomFF
																FROM 	cuentas_detalle as a,
																			bodegas_documentos as b,
																			inv_conceptos as c,
																			bodegas_documentos_d as d,
																			bodegas_documentos_d_cobertura e,
																			inventarios_productos I,
																			medicamentos L,
																			inventarios invM,
																			inv_med_cod_forma_farmacologica F
																WHERE	a.numerodecuenta = $value[numerodecuenta] and
																			b.empresa_id = '".$datos_estacion[empresa_id]."' and
																			b.centro_utilidad = '".$datos_estacion[centro_utilidad]."' and
																			b.transaccion = a.transaccion and
																			b.concepto_inv = c.concepto_inv and
																			d.documento=b.documento and
																			d.empresa_id = b.empresa_id and
																			d.centro_utilidad = b.centro_utilidad and
																			d.bodega = b.bodega and
																			d.prefijo = b.prefijo and
																			c.concepto_inv = 'DEV' AND
																			e.consecutivo_detalle = d.consecutivo AND
																			I.codigo_producto = d.codigo_producto AND
																			invM.codigo_producto = d.codigo_producto AND
																			L.codigo_medicamento = invM.codigo_producto AND
																			F.cod_forma_farmacologica = L.cod_forma_farmacologica
																GROUP BY d.codigo_producto, I.descripcion, L.concentracion_forma_farmacologica,F.descripcion

																UNION

																SELECT  d.codigo_producto,
																				sum(d.cantidad),
																				I.descripcion as nomMedicamento,
																				null as concentracion_forma_farmacologica,
																				null as nomFF
																FROM 	cuentas_detalle as a,
																			bodegas_documentos as b,
																			inv_conceptos as c,
																			bodegas_documentos_d as d,
																			bodegas_documentos_d_cobertura e,
																			inventarios_productos I,
																			hc_insumos_estacion HIE,
																			hc_tipos_insumo HTI
																WHERE	a.numerodecuenta = $value[numerodecuenta] and
																			b.empresa_id = '".$datos_estacion[empresa_id]."' and
																			b.centro_utilidad = '".$datos_estacion[centro_utilidad]."' and
																			b.transaccion = a.transaccion and
																			b.concepto_inv = c.concepto_inv and
																			d.documento = b.documento and
																			d.empresa_id = b.empresa_id and
																			d.centro_utilidad = b.centro_utilidad and
																			d.bodega = b.bodega and
																			d.prefijo = b.prefijo and
																			c.concepto_inv = 'DEV' AND
																			e.consecutivo_detalle = d.consecutivo AND
																			I.codigo_producto = d.codigo_producto AND
																			HIE.insumo_id = HTI.insumo_id AND
																			HIE.codigo_producto = d.codigo_producto AND
																			HTI.tipo_insumo = 'I'
																GROUP BY d.codigo_producto, I.descripcion
															)
														)AS B
											USING(codigo_producto)
											LEFT JOIN
														(
															SELECT  d.codigo_producto,
																			sum(d.cantidad),
																			I.descripcion as nomMedicamento,
																			L.concentracion_forma_farmacologica,
																			F.descripcion as nomFF
															FROM	inv_solicitudes_devolucion as b,
																		inv_solicitudes_devolucion_d as d,
																		inventarios_productos I,
																		medicamentos L,
																		inventarios invM,
																		inv_med_cod_forma_farmacologica F
															WHERE b.estado = '0' AND
																		b.documento = d.documento AND
																		b.ingreso = $value[ingreso] AND
																		I.codigo_producto = d.codigo_producto AND
																		invM.codigo_producto = d.codigo_producto AND
																		L.codigo_medicamento = invM.codigo_producto AND
																		b.empresa_id = '".$datos_estacion[empresa_id]."' AND
																		F.cod_forma_farmacologica = L.cod_forma_farmacologica
															GROUP BY d.codigo_producto, I.descripcion, L.concentracion_forma_farmacologica,F.descripcion
														)AS C
											USING(codigo_producto)";//ORDER BY codigo_producto

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($qDespachados);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar obtener los documentos de bodega de las ordenes de médicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$qDespachados;
				return false;
			}
			else
			{
				while ($data = $result->FetchRow())
				{
					if(($data['suma'] > 0) || ($data['suma'] == NULL)){
						$tmp[] = $data;
					}
				}
			}
			if(sizeof($tmp))
			{
				$VectorReal[$value[numerodecuenta]][0] = $tmp;
				$VectorReal[$value[numerodecuenta]][1] = $value;
				unset($tmp);
			}
		}
		return ($VectorReal);
	}//fin FrmDevolucionMedicamentos()
//funcion del mod medicamentos(estacion e)
-----FUNCION DE ARLEY PARA SACAR LAS DEVOLUCIONES HAY Q ANALIZAR SI ESTO FUNCIONA-------










		 //funcion de medicamento estacione_medicamentos
	/**
	*		GetMedicamentosPendientesPorRecibir
	*
	*		obtiene los pacientes y sus respectivos medicamentos que ya fueron solicitados a bodega
	*		y despachados por la misma y que estan pendientes por recibir
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetMedicamentosPendientesPorRecibir($datos_estacion)
	{
    if(!$datos_estacion){$datos_estacion=$_REQUEST['datos'];}

	/*
		El primer subquery obtiene los medicamentos solicitados
		El tercer subquery obtiene los medicamentos no despchados por bodega
		El segundo subquery obtiene los medicamentos efectivamente despachados por bodega y su equivalente al solicitado en el caso que aplique
		*/
		$query="select  B.solicitud_id as solicitud_sol,
										B.fecha_solicitud as fecha_sol,
										B.medicamento_id as medicamento_id_sol,
										B.cant_solicitada as cant_solicitada_sol,
										B.forma_farmaceutica as forma_farmaceutica_sol,
										B.nomMedicamento as nomMedicamento_sol,
										B.FF as FF_sol,
										B.ingreso,
										A.evolucion_id,
										I.paciente_id,
										I.tipo_id_paciente,
										P.primer_nombre,
										P.segundo_nombre,
										P.primer_apellido,
										P.segundo_apellido,
										CM.cama,
										PZ.pieza,
										A.solicitud_id as solicitud_id_des,
										A.fecha_solicitud as fecha_solicitud_des,
										A.bodega,
										A.medicamento_id as medicamento_id_des,
										A.forma_farmaceutica as forma_farmaceutica_des,
										A.nomMedicamento as nomMedicamento_des,
										A.FF as FF_des,
										A.documento as documento_des,
										A.cant_enviada,
										A.reemplazo
						from
						(
								select
												SM.solicitud_id,
												SM.fecha_solicitud,
												SM.ingreso,
												SMD.consecutivo_d,
												SMD.medicamento_id,
												SMD.cant_solicitada,
												M.forma_farmaceutica,
												INV.descripcion as nomMedicamento,
												FF.descripcion as FF
								from
												hc_solicitudes_medicamentos SM,
												hc_solicitudes_medicamentos_d SMD,
												medicamentos M,
												inventarios_productos INV,
												inventario_medicamentos invM,
												formas_farmaceuticas FF
								where
												SM.sw_estado = '1' AND
												SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
												SMD.solicitud_id=SM.solicitud_id AND
												SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
												SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
												invM.codigo_producto = SMD.medicamento_id AND
												INV.codigo_producto = invM.codigo_producto AND
												M.codigo_medicamento = invM.codigo_medicamento AND
												FF.forma_farmaceutica = M.forma_farmaceutica
						) as B
						left join
						(
									select
													SM.solicitud_id,
													SM.fecha_solicitud,
													SM.ingreso,
													SM.bodega,
													SMD.consecutivo_d,
													SMD.evolucion_id,
													SMD.medicamento_id,
													SMD.cant_solicitada,
													M.forma_farmaceutica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF,
													BDHS.documento,
													BDD.cantidad as cant_enviada,
													null as reemplazo
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_medicamentos_d SMD,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													medicamentos M,
													inventarios_productos INV,
													inventario_medicamentos invM,
													formas_farmaceuticas FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SMD.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.documento = BDHS.documento AND
													BDD.codigo_producto = SMD.medicamento_id AND
													invM.codigo_producto = SMD.medicamento_id AND
													INV.codigo_producto = invM.codigo_producto AND
													M.codigo_medicamento = invM.codigo_medicamento AND
													FF.forma_farmaceutica = M.forma_farmaceutica
									UNION

									select
													SM.solicitud_id,
													null as fecha_solicitud,
													null as ingreso,
													null as bodega,
													null as evolucion_id,
													SMD.consecutivo_d,
													SMD.medicamento_id,
													SMD.cant_solicitada,
													M.forma_farmaceutica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF,
													BDHS.documento,
													BDD.cantidad as cant_enviada,
													BDD.codigo_producto
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_medicamentos_d SMD,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													bodegas_documentos_d_equiv_med BDDE,
													medicamentos M,
													inventarios_productos INV,
													inventario_medicamentos invM,
													formas_farmaceuticas FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SMD.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.documento = BDHS.documento AND
													BDDE.consecutivo = BDD.consecutivo AND
													BDDE.consecutivo_d = SMD.consecutivo_d  AND
													BDD.empresa_id = SM.empresa_id AND
													BDD.centro_utilidad = SM.centro_utilidad AND
													invM.codigo_producto = BDD.codigo_producto AND
													INV.codigo_producto = invM.codigo_producto AND
													M.codigo_medicamento = invM.codigo_medicamento AND
													FF.forma_farmaceutica = M.forma_farmaceutica
						) as A
						on (B.medicamento_id = A.medicamento_id AND B.solicitud_id = A.solicitud_id),
								ingresos I,
								cuentas C,
								movimientos_habitacion MH,
								camas CM,
								piezas PZ,
								pacientes P
						WHERE I.ingreso = B.ingreso AND
									C.ingreso = I.ingreso AND
									MH.numerodecuenta = C.numerodecuenta AND
									MH.fecha_egreso IS NULL AND
									CM.cama = MH.cama AND
									CM.pieza = PZ.pieza AND
									Pz.estacion_id = '".$datos_estacion[estacion_id]."' AND
									P.paciente_id = I.paciente_id AND
									P.tipo_id_paciente = I.tipo_id_paciente
						ORDER BY B.solicitud_id";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar las solicitudes pendientes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
					$Solicitudes[$data['solicitud_sol']][] = $data;
				}
				return $Solicitudes;
			}
		}
	}//fin GetMedicamentosPendientesPorRecibir
 //funcion de medicamento estacione_medicamentos



 //medicamentos del mod estacione_medicamentos
	/*
	*		GetMedicamentosPendientesPorRecibir
	*
	*		obtiene los pacientes y sus respectivos medicamentos, mezclas y medicamentos de estas
	*		que ya fueron solicitados al paciente y que estan pendientes por recibir
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetMedicamentosPendientesPorRecibirPaciente($datos_estacion)
	{
		if(!$datos_estacion){$datos_estacion=$_REQUEST['datos'];}
		$qMedPac= "SELECT J.*,
											C.numerodecuenta,
											CM.cama,
											CM.pieza,
											I.paciente_id,
											I.tipo_id_paciente,
											P.primer_nombre,
											P.segundo_nombre,
											P.primer_apellido,
											P.segundo_apellido,
											M.forma_farmaceutica,
											INV.descripcion,
											FF.descripcion as nombre
							FROM  (
											SELECT  SMP.consecutivo,
															NULL as mezcla_recetada_id,
															SMP.medicamento_id,
															SMP.evolucion_id,
															SMP.cant_solicitada,
															SMP.fecha_solicitud,
															SMP.ingreso
											FROM hc_solicitudes_medicamentos_pacientes SMP,
														hc_medicamentos_recetados MR
											WHERE SMP.sw_estado = '0' AND
														MR.sw_estado = '2' AND
														MR.medicamento_id = SMP.medicamento_id
											UNION
											SELECT  SMP.consecutivo,
															SMP.mezcla_recetada_id,
															SMP.medicamento_id,
															SMP.evolucion_id,
															SMP.cant_solicitada,
															SMP.fecha_solicitud,
															SMP.ingreso
											FROM hc_solicitudes_mezclas_pacientes SMP,
													 hc_mezclas_recetadas MR,
													 hc_mezclas_recetadas_medicamentos MRM
											WHERE SMP.sw_estado = '0' AND
														MR.sw_estado = '2' AND
														MR.mezcla_recetada_id = SMP.mezcla_recetada_id AND
														MRM.mezcla_recetada_id = MR.mezcla_recetada_id AND
														MRM.medicamento_id = SMP.medicamento_id
										) AS J,
										cuentas C,
										movimientos_habitacion MH,
										camas CM,
										piezas PZ,
										ingresos I,
										pacientes P,
										medicamentos M,
										inventario_medicamentos invM,
										inventarios_productos INV,
										formas_farmaceuticas FF
							WHERE C.ingreso = J.ingreso AND
										MH.numerodecuenta = C.numerodecuenta AND
										MH.fecha_egreso IS NULL AND
										CM.cama = MH.cama AND
										PZ.pieza = CM.pieza AND
										PZ.estacion_id = '".$datos_estacion['estacion_id']."' AND
										I.ingreso = J.ingreso AND
										P.paciente_id = I.paciente_id AND
										P.tipo_id_paciente = I.tipo_id_paciente AND
										invM.codigo_producto = J.medicamento_id AND
										INV.codigo_producto = invM.codigo_producto AND
										invM.codigo_medicamento = M.codigo_medicamento AND
										C.empresa_id = '".$datos_estacion['empresa_id']."' AND
										C.centro_utilidad  = '".$datos_estacion['centro_utilidad']."' AND
										FF.forma_farmaceutica = M.forma_farmaceutica
							ORDER BY J.consecutivo
							";


		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($qMedPac);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener los medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$qMedPac;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			$i=0;
			while (!$result->EOF)
			{

				$Paciente[$i] = $result->GetRowAssoc($ToUpper = false);//mi primer GetRow
				$i++;
				$result->MoveNext();
			}
			return $Paciente;
		}
	}//GetMedicamentosPendientesPorRecibir

	 //medicamentos del mod estacione_medicamentos




	 //medicamentos estacione_medicamentos
	/**
	*		GetMedicamentosMezclasPendientesPorRecibir
	*
	*		obtiene los pacientes y sus respectivos medicamentos de mezclas
	*		que ya fueron solicitados a bodega y que estan pendientes por recibir
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetMedicamentosMezclasPendientesPorRecibir($datos_estacion)
	{
    if(!$datos_estacion){$datos_estacion=$_REQUEST['datos'];}

	/*
		El primer subquery obtiene los medicamentos solicitados
		El tercer subquery obtiene los medicamentos no despchados por bodega
		El segundo subquery obtiene los medicamentos efectivamente despachados por bodega y su equivalente al solicitado en el caso que aplique
		*/
		$query="select  B.solicitud_id as solicitud_sol,
										B.fecha_solicitud as fecha_sol,
										B.mezcla_recetada_id,
										B.medicamento_id as medicamento_id_sol,
										B.cant_solicitada as cant_solicitada_sol,
										B.forma_farmaceutica as forma_farmaceutica_sol,
										B.nomMedicamento as nomMedicamento_sol,
										B.FF as FF_sol,
										B.ingreso,
										A.evolucion_id,
										I.paciente_id,
										I.tipo_id_paciente,
										P.primer_nombre,
										P.segundo_nombre,
										P.primer_apellido,
										P.segundo_apellido,
										CM.cama,
										PZ.pieza,
										A.solicitud_id as solicitud_id_des,
										A.fecha_solicitud as fecha_solicitud_des,
										A.bodega,
										A.medicamento_id as medicamento_id_des,
										A.forma_farmaceutica as forma_farmaceutica_des,
										A.nomMedicamento as nomMedicamento_des,
										A.FF as FF_des,
										A.documento as documento_des,
										A.cant_enviada,
										A.reemplazo
						from
						(
								SELECT
												SM.fecha_solicitud,
												SM.solicitud_id,
												SM.ingreso,
												SMD.consecutivo_d,
												SMD.mezcla_recetada_id,
												SMD.medicamento_id,
												SMD.cant_solicitada,
												M.forma_farmaceutica,
												INV.descripcion as nomMedicamento,
												FF.descripcion as FF
								FROM  hc_solicitudes_medicamentos SM,
											hc_solicitudes_medicamentos_mezclas_d SMD,
											medicamentos M,
											inventario_medicamentos invM,
											inventarios_productos INV,
											formas_farmaceuticas FF
								WHERE SM.sw_estado='1' AND
											SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
											SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
											SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
											SMD.solicitud_id = SM.solicitud_id AND
											invM.codigo_producto = SMD.medicamento_id AND
											INV.codigo_producto = invM.codigo_producto AND
											M.codigo_medicamento = invM.codigo_medicamento AND
											M.codigo_medicamento = invM.codigo_medicamento AND
											FF.forma_farmaceutica = M.forma_farmaceutica
						) as B
						left join
						(
									select
													SM.solicitud_id,
													SM.fecha_solicitud,
													SM.ingreso,
													SM.bodega,
													SMD.consecutivo_d,
													SMD.evolucion_id,
													SMD.mezcla_recetada_id,
													SMD.medicamento_id,
													SMD.cant_solicitada,
													M.forma_farmaceutica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF,
													BDHS.documento,
													BDD.cantidad as cant_enviada,
													null as reemplazo
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_medicamentos_mezclas_d SMD,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													medicamentos M,
													inventarios_productos INV,
													inventario_medicamentos invM,
													formas_farmaceuticas FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SMD.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.documento = BDHS.documento AND
													BDD.codigo_producto = SMD.medicamento_id AND
													invM.codigo_producto = SMD.medicamento_id AND
													INV.codigo_producto = invM.codigo_producto AND
													M.codigo_medicamento = invM.codigo_medicamento AND
													FF.forma_farmaceutica = M.forma_farmaceutica
									UNION

									select
													SM.solicitud_id,
													null as fecha_solicitud,
													null as ingreso,
													null as bodega,
													null as evolucion_id,
													SMD.consecutivo_d,
													SMD.mezcla_recetada_id,
													SMD.medicamento_id,
													SMD.cant_solicitada,
													M.forma_farmaceutica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF,
													BDHS.documento,
													BDD.cantidad as cant_enviada,
													BDD.codigo_producto
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_medicamentos_mezclas_d SMD,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													bodegas_documentos_d_equiv_mez BDDE,
													medicamentos M,
													inventarios_productos INV,
													inventario_medicamentos invM,
													formas_farmaceuticas FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SMD.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.documento = BDHS.documento AND
													BDD.empresa_id = '".$datos_estacion[empresa_id]."' AND
													BDD.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													BDDE.consecutivo = BDD.consecutivo AND
													BDDE.consecutivo_d = SMD.consecutivo_d  AND
													invM.codigo_producto = BDD.codigo_producto AND
													INV.codigo_producto = invM.codigo_producto AND
													M.codigo_medicamento = invM.codigo_medicamento AND
													FF.forma_farmaceutica = M.forma_farmaceutica
						) as A
						ON (B.medicamento_id=A.medicamento_id AND B.solicitud_id=A.solicitud_id),
								ingresos I,
								cuentas C,
								movimientos_habitacion MH,
								camas CM,
								piezas PZ,
								pacientes P
						WHERE I.ingreso = B.ingreso AND
									C.ingreso = I.ingreso AND
									MH.numerodecuenta = C.numerodecuenta AND
									MH.fecha_egreso IS NULL AND
									CM.cama = MH.cama AND
									CM.pieza = PZ.pieza AND
									Pz.estacion_id = '".$datos_estacion[estacion_id]."' AND
									P.paciente_id = I.paciente_id AND
									P.tipo_id_paciente = I.tipo_id_paciente
						ORDER BY B.solicitud_id,B.mezcla_recetada_id,B.medicamento_id";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar las solicitudes pendientes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
					$ContMezclas[$data['mezcla_recetada_id']]++;
					$Solicitudes[$data['solicitud_sol']][$data['mezcla_recetada_id']][] = $data;
				}
				return $Solicitudes;
			}
		}
	}//fin GetMedicamentosMezclasPendientesPorRecibir



//aqui generamos las graficas cuando existen muchos pacientes de la estacion.
//es muy pesado y toca generar un link para mostrar las graficas.
function GenerarGraphica()
{
	$datos=$_REQUEST['estacion'];
	$arr_conteo=array($_SESSION['ESTACION_ENF']['CONTEO']['INGRESO'],$_SESSION['ESTACION_ENF']['CONTEO']['HOSP'],$_SESSION['ESTACION_ENF']['CONTEO']['CONSULTA'],$_SESSION['ESTACION_ENF']['CONTEO']['EGRESO']);
	IncludeLib("jpgraph/Barras_Estacion"); //cargamos la libreria de presion diastolica.
	$graphic=GraficarBarras($arr_conteo);
	$this->CallMenu($datos,$graphic);
}

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
     
     
     function BusquedaSolicitudes_Estacion($datos)
     {
          list($dbconn) = GetDBconn();
		$query = "SELECT count(*)
                    FROM hc_solicitudes_suministros_estacion A, 
                         hc_solicitudes_suministros_estacion_detalle B
                    WHERE A.estacion_id= '".$datos[estacion_id]."'
                    AND (B.sw_estado = '2' OR B.sw_estado = '1' OR B.sw_estado = '0');";
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al obtener el numero de cuenta del ingreso<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		$result = $dbconn->Execute($query);
          list($conteo) = $result->FetchRow();
          return $conteo;
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
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmIngresarDatosLiquidos\"";
				return false;
			}
			return true;
		}


		/**
		*		CallFrmControlLiquidos
		*
		*		Llama la  vista que muestra un listado con los totales de liquidos adm y elim del día
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
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmControlLiquidos\"";
				return false;
			}
			return true;
		}
          
	
     function TraerUsuario($usuario)
     {
          GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

          $query ="SELECT usuario, nombre
          		FROM system_usuarios
                    WHERE usuario_id = ".$usuario.";";
                    
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          $data = $result->FetchRow();
          return $data;
     }
     
     
     function TraerEstacion($EE)
     {
          GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

          $query  ="SELECT descripcion
          		FROM estaciones_enfermeria
                    WHERE estacion_id = ".$EE.";";
                    
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          $data = $result->FetchRow();
          return $data;
     }
     
     function TraerBodega($bodega,$estacion)
     {
          GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

          $query ="SELECT descripcion
          		FROM bodegas
                    WHERE bodega = '".$bodega."'
                    AND empresa_id = '".$estacion[empresa_id]."'
                    AND centro_utilidad = '".$estacion[centro_utilidad]."';";
                    
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          $data = $result->FetchRow();
          return $data;
     }

         
     function BuscarDatos_ResponsableIyM($estacion)
     {
          GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
          $query  ="SELECT SUM(B.cantidad-B.cantidad_ajustada) AS cantidad, A.usuario_id, 
          				  A.bodega, A.estacion_id, B.codigo_producto, C.descripcion
                         FROM inv_solicitudes_iym_responsable AS A, 
                         	inv_solicitudes_iym_responsable_d AS B, inventarios_productos AS C
                         WHERE A.responsable_solicitud = ".UserGetUID()."
                         AND A.estacion_id = '".$estacion[estacion_id]."'
                         AND A.inv_solicitudes_iym_id = B.inv_solicitudes_iym_id
                         AND B.codigo_producto = C.codigo_producto
                         AND B.sw_estado = '1'
                         GROUP BY A.usuario_id, A.bodega, A.estacion_id, 
                         	 B.codigo_producto, C.descripcion
                         ORDER BY cantidad DESC, C.descripcion;";
                    
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while($data = $result->FetchRow())
          {
          	$datos_IyM[] = $data;
          }
          return $datos_IyM;
     }


}//fin class
?>
