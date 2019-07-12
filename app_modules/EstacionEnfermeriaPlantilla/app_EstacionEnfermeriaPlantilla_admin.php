<?php

/**hc_modules/EstacionEnfermeria/app_EstacionEnfermeria_admin.php  31/10/2003 9 am
* ----------------------------------------------------------------------
* Autor: Rosa Maria Angel Diez
* Proposito del Archivo: Manejo de las actividades de la Estacion de enfermería
* ----------------------------------------------------------------------
*/

	/**
	*Contiene los metodos para realizar el triage y admision de los pacientes
	*/
class app_EstacionEnfermeria_admin extends classModulo
{
	var $frmError = array();


	/**
	*		app_EstacionEnfermeria_admin()
	*
	*		constructor
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function app_EstacionEnfermeria_admin()//Constructor padre
	{
		return true;
	}

	/**
	*		main()
	*
	*		Llama a la vista de las habitaciones
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function main()
	{
			if($_SESSION['USER_ADMIN_MOD']['MODULO'])
			{
					$this->Menu();
					return true;
			}
			else
			{
					if(!$this->ListPiezas($_REQUEST['datos_estacion'])){//1.1.1.H
						return false;
					}
					return true;
			}
	}//FIN MAIN


	/**
	*		callFrmCreatePieza()
	*
	*		crear piezas en cualquier estacion de enfermería
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function callFrmCreatePieza()
	{
	 	if(!$this->FrmCreatePieza($_REQUEST['datos_estacion'],"","","","",""))
		{
     	return false;
    }
    return true;
	}





/*Esta funcion se devuleve al modulo en donde se pueden ver los modulos y los
 * departamentos segun el permiso del usuario.
*/
function Retornar()
{
	$this->ReturnMetodoExterno($_SESSION['USER_ADMIN_MOD']['CONTENEDOR'], $_SESSION['USER_ADMIN_MOD']['MOD'], $_SESSION['USER_ADMIN_MOD']['TIPO'], $_SESSION['USER_ADMIN_MOD']['METODO'],array("mod"=>$_SESSION['USER_ADMIN_MOD']['MODULO']));
	return true;
}

function RetornarPermisos()
{
	$this->ReturnMetodoExterno($_SESSION['USER_ADMIN_MOD']['CONTENEDOR'], $_SESSION['USER_ADMIN_MOD']['MOD'], $_SESSION['USER_ADMIN_MOD']['TIPO'], 'TraerDatos',array("tabla"=>'estaciones_enfermeria_usuarios',"permiso"=>'ESTACION DE ENFERMERIA'));
	return true;
}




	/**
	*		CallCreateCamasyPiezas
	*
	*		una vez dados los datos de la pieza a crear, se crean las camas
	*
	*		@author Rosa Maria Angel D.
	*		@access Public
	*		@return bool
	*/
	function CallFrmCreateCamasyPiezas()
	{
		$datosPieza = array();
		$piezaId = $_REQUEST['piezaId'];
		$descripcion = $_REQUEST['descripcion'];
		$ubicacion = $_REQUEST['ubicacion'];
		$cantCamas = $_REQUEST['cantCamas'];
		$camaPrefijo = $_REQUEST['camaPrefijo'];
		$cantCamasPieza = $_REQUEST['cantCamasPieza'];//total camas de la pieza en la BD
		$datos_estacion = $_REQUEST['datos_estacion'];

		if($_REQUEST['NoPieza'] == 1)//NO SE VA A CREAR PIEZAS SINO CAMAS A LA PIEZA EXIST
		{
			$estEnf = $_REQUEST['estEnf'];
			array_push($datosPieza,$piezaId,$ubicacion,$estEnf,1,$cantCamasPieza,$descripcion);
		}
		else
		{
			//validar que los campos no estén vacios
			if(!$piezaId || !$cantCamas || !$ubicacion || !$descripcion || !$camaPrefijo)
			{
				if(!$piezaId){ $this->frmError["piezaId"] = 1; }
				if(!$cantCamas){ $this->frmError["cantCamas"] = 1; }
				if(!$ubicacion){ $this->frmError["ubicacion"] = 1; }
				if(!$descripcion){ $this->frmError["descripcion"] = 1; }
				if(!$camaPrefijo){ $this->frmError["camaPrefijo"] = 1; }

				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
				if(!$this->FrmCreatePieza($datos_estacion,$piezaId,$descripcion,$ubicacion,$cantCamas,$camaPrefijo)){
					return false;
				}
				return true;
			}

			$x = explode('$',$_REQUEST['estEnf']);
			array_push($datosPieza,$piezaId,$ubicacion,$x[1],$cantCamas,$cantCamasPieza);//$cantCamasPieza no se si si vaya
			list($dbconn) = GetDBconn();
			$query = "INSERT INTO piezas (pieza,
																		estacion_id,
																		descripcion,
																		cantidad_camas,
																		ubicacion)
								VALUES ('$piezaId',
												'$x[0]',
												'$descripcion',
												$cantCamas,
												'$ubicacion')";

			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al tratar de crear la habitación";
				$this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
		}

		if(!$this->FrmCreateCamasyPiezas($datosPieza,0,$_REQUEST['camaPrefijo'],$_REQUEST['numeracion'],$datos_estacion))
		{
    	return false;
   	}
		return true;
	}

	/**
	*		InsertCamasPieza
	*
	*		Inserta en la bd las camas especificadas
	*
	*		@author Rosa Maria Angel D.
	*		@access Public
	*		@return bool
	*/
	function InsertCamasPieza()
	{
		$piezaId = $_REQUEST['piezaId'];
		$cantCamas = $_REQUEST['cantCamas'];
		$cantCamasPieza = $_REQUEST['cantCamasPieza'];//total camas pieza en la BD
		$datos_estacion = $_REQUEST['datos_estacion'];


		//-----------solo para remandar los datos de la pieza si faltaron datos de las camas
		$ubicacion = $_REQUEST['ubicacion'];
		$x = explode('$',$_REQUEST['estEnf']);
		$datosPieza = array();
		array_push($datosPieza,$piezaId,$ubicacion,$x[0],$cantCamas);
		//-----------------------------------------

		//--------------------------
		//validar que no falten datos de las camas
		//--------------------------
		for($i=0; $i<$cantCamas; $i++)
		{
			$camaId = $_REQUEST['camaId'.$i];
			$estado = $_REQUEST['estadoCama'.$i];
			$tipoCama = $_REQUEST['tipoCama'.$i];

			//validar que los campos no estén vacios
			if(!$camaId || !$cantCamas || !$ubicacion)
			{
				if(!$camaId){ $this->frmError["camaId"] = 1; }

				$this->frmError["MensajeError"]="Faltan datos obligatorios.";
				if(!$this->FrmCreateCamasyPiezas($datosPieza,$camaId,$datos_estacion)){
					return false;
				}
				return true;
			}
		}

		for($i=0; $i<$cantCamas; $i++)
		{
			$camaId = $_REQUEST['camaId'.$i];
			$estado = $_REQUEST['estadoCama'.$i];
			$tipoCama = $_REQUEST['tipoCama'.$i];

			$query = "INSERT INTO camas ( cama,
																		pieza,
																		tipo_cama,
																		estado)
								VALUES (
													'$camaId',
													'$piezaId',
													'$tipoCama',
													'$estado')";

			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar crear la cama<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				$x = ($cantCamasPieza+$cantCamas);
				$query = "UPDATE piezas
									SET cantidad_camas= $x
									WHERE pieza = $piezaId";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Error al intentar recalcular el numero de camas de la pieza<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
				else{
					$dbconn->CommitTrans();
				}
			}
		}
		$this->ListPiezas($datos_estacion);
		return true;
	}//InsertCamasPieza


	/*
	*		CallFrmBloquearCama() Llamado desde el link bloquear cama del listado de piezas
	*
	*		LLama al formulario que permite cambiar el estado de las camas de una pieza
	*
	*		@author Rosa Maria Angel D.
	*		@access Public
	*		@return bool
	*/
	function CallFrmBloquearCama()
	{
		$camas = unserialize(stripslashes($_REQUEST['camas']));

		if(!$this->FrmBloquearCama($_REQUEST['pieza'],$camas,$_REQUEST['datos_estacion']))
		{
			return false;
		}
		return true;
	}

	/*
	*		UpdateEstadosCamas()
	*
	*		Cambia el estado de las camas especificadas
	*
	*		@author Rosa Maria Angel D.
	*		@access Public
	*		@return bool
	*/
	function UpdateEstadosCamas()
	{
		$camas = $_REQUEST['camas'];
		$estadoCama = $_REQUEST['estadoCama'];
		//echo "<br>camas ".print_r($camas);
		//echo "<br>estados ".print_r($estadoCama);
		list($dbconn) = GetDBconn();

		for($i=0; $i<sizeof($camas); $i++)
		{
			$query = "UPDATE camas
								SET estado = '".$estadoCama[$i]."'
								WHERE cama = '".$camas[$i]."';";

			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar crear la cama<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
		}
		$this->main();
		return true;
	}


	/**
	*		CrearTurnos
	*
	*
	*
	*		@author Arley Velásquez C.
	*		@access Public
	*		@return bool
	*/
	function CallCrearTurnos($fecha,$dia,$horario,$hora_inicio_turno,$rango,$rangoTurno,$horas)
	{
		return $this->CrearTurnos($fecha,$dia,$horario,$hora_inicio_turno,$rango,$rangoTurno,$horas);
	}



	/**
	*		GetPiezas8 => Obtiene los datos de todas las piezas y sus respectivas camas asociadas
	*		llamada para mostrar en el listado de piezas
	*
	*		@author Rosa Maria Angel D.
	*		@access Public
	*		@return bool
	*/
	function GetPiezas($datos_estacion)
	{//if(!$datos_estacion){$datos_estacion = $_REQUEST[datos_estacion];} esto solo seria necesario en el caso en que se necesite llamar esta funcion desde el user
		$query = "SELECT TCE.descripcion as estado,
										K.desc_tipo_cama,
										K.estacion,
										K.pieza,
										K.ubicacion,
										K.desc_pieza,
										K.estacion_id,
										K.cama,
										K.estado as estado_id,
										K.tipo_cama
							FROM  tipo_camas_estados AS TCE,
									(
										SELECT EE.descripcion as estacion, J.desc_tipo_cama, J.pieza, J.ubicacion, J.desc_pieza, J.estacion_id, J.cama, J.estado, J.tipo_cama, DPTOS.departamento
										FROM  estaciones_enfermeria AS EE,
													departamentos DPTOS,
												(
													SELECT E.descripcion as desc_tipo_cama, D.pieza, D.ubicacion, D.desc_pieza, D.estacion_id, D.cama, D.estado,D.tipo_cama
													FROM (
																	SELECT P.pieza, P.ubicacion, P.descripcion as desc_pieza, P.estacion_id, C.cama, C.estado, C.tipo_cama
																	FROM piezas P
																	LEFT JOIN camas C
																	ON P.pieza = C.pieza
																) AS D
													LEFT JOIN tipos_cama as E
													ON E.tipo_cama = D.tipo_cama
												) AS J
										WHERE EE.estacion_id = J.estacion_id AND
													DPTOS.departamento = EE.departamento AND
													DPTOS.empresa_id = '".$datos_estacion[empresa_id]."' AND
													DPTOS.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													DPTOS.unidad_funcional = '".$datos_estacion[unidad_funcional]."'
										) AS K
							WHERE TCE.tipo_cama_estado_id = K.estado
							ORDER BY K.estacion_id, K.pieza, K.cama";
		//echo $query; exit;
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener las piezas<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF){
			return "ShowMensaje";
		}
		else
		{
			$i=0;
			$piezas = $piezasTemp = $camasTemp= $C = $datosCamas = $x= $t=array();
			while ($data = $result->FetchNextObject())
			{
				if(!in_array($data->PIEZA,$piezas))
				{
					array_push($piezas,$data->PIEZA);
					array_push($piezasTemp,$data->PIEZA,$data->UBICACION,$data->ESTACION,$data->DESC_PIEZA);
					array_push($camasTemp,$data->CAMA,$data->ESTADO,$data->DESC_TIPO_CAMA,$data->ESTADO_ID,$data->TIPO_CAMA);
					array_push($C,$camasTemp);
					$datosCamas[$i][0]=$piezasTemp;
					$datosCamas[$i][1]=$C;
					unset($camasTemp); unset($piezasTemp); unset($C);
					$camasTemp=$piezasTemp=$C=array();
					$i++;
				}
				else
				{
					array_push($camasTemp,$data->CAMA,$data->ESTADO,$data->DESC_TIPO_CAMA,$data->ESTADO_ID,$data->TIPO_CAMA);
					$x = array_keys($piezas,$data->PIEZA);
					$t=$datosCamas[$x[0]][1];
					$t[sizeof($t)]=$camasTemp;
					$datosCamas[$x[0]][1]=$t;
					unset($camasTemp);
					unset($t);
					$camasTemp=$t=array();
				}
			}
			return $datosCamas;
		}
	}//fin


	/**
	*		GetEstaciones()=> Obtiene todas las estaciones de enfermería
	*		llamada para mostrar en "crear piezas" un combo con las estaciones
	*
	*		@author Rosa Maria Angel D.
	*		@access Public
	*		@return bool
	*/
	function GetEstaciones($datos_estacion)
	{
		$query = "SELECT 	EE.estacion_id,
											EE.descripcion
							FROM estaciones_enfermeria EE,
										departamentos D
							WHERE D.departamento = EE.departamento AND
										D.empresa_id = '".$datos_estacion[empresa_id]."' AND
										D.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
										D.unidad_funcional = '".$datos_estacion[unidad_funcional]."'
							ORDER BY EE.descripcion;";

		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener las estaciones<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		$i=0;
		while ($data = $result->FetchNextObject())
		{
			$estaciones[$i][0] = $data->ESTACION_ID;
			$estaciones[$i][1] = $data->DESCRIPCION;
			$i++;
		}
		return $estaciones;
	}// GetEstaciones


	/*
	*		GetTiposCamas() => llamada desde createcamasypiezas para mostrar el combo
	*
	*		Obtiene los diferentes tipos de camas
	*
	*		@author Rosa Maria Angel D.
	*		@access Public
	*		@return bool
	*/
	function GetTiposCamas()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_cama, descripcion
							FROM tipos_cama
							ORDER BY descripcion;";

		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener los tipos de camas<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		$i=0;
		while ($data = $result->FetchNextObject())
		{
			$estados[$i][0] = $data->TIPO_CAMA;
			$estados[$i][1] = $data->DESCRIPCION;
	  $i++;
		}
		return $estados;
	}


	/**
	*		GetEstadosCamas=> Obtiene todas las estados de la tabla estados_camas
	*		llamada para mostrar en "crear camas de las piezas" un combo
	*
	*		Obtiene los diferentes estados de las camas
	*
	*		@author Rosa Maria Angel D.
	*		@access Public
	*		@return bool
	*/
	function GetEstadosCamas()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_cama_estado_id, descripcion
							FROM tipo_camas_estados
							ORDER BY descripcion;";

		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtner los estados de las camas<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		$i=0;
		while ($data = $result->FetchNextObject())
		{
			$estados[$i][0] = $data->	TIPO_CAMA_ESTADO_ID;
			$estados[$i][1] = $data->DESCRIPCION;
			$i++;
		}
		return $estados;
	}

	/*
	*		GetSerieLetras => crea la numeracion en letras para las camas
	*		llamada desde SetNumeracionCamas
	*
	*		@author Rosa Maria Angel D.
	*		@access Public
	*		@return bool
	*/
	function GetSerieLetras($cc,$vl)
	{// numero max de camas en una habitacion 17602
		$fijo="";
		$pasada=floor(($cc/sizeof($vl)));
		if ($pasada>sizeof($vl))
		 {
				if ($pasada>pow(sizeof($vl),1) && $pasada<=pow(sizeof($vl),2))
				{
					return $fijo=$vl[0].$vl[($pasada-1)-26];
				}
				if ($pasada>pow(sizeof($vl),2) && $pasada<=pow(sizeof($vl),3))
				{
					return $fijo=$vl[0].$vl[1].$vl[($pasada-1)-26];
				}
		 }
		else
		 {
		   if ($pasada)
				return $fijo=$vl[$pasada-1];
		 }
	}

	/*
	*		CallFrmImpresionLiquidosParenterales
	*
	*		Llama al formulario que permite administrar la plantilla de RxS
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function CallFrmMantenimientoPltSistemas()
	{
		if (!$this->FrmMantenimientoPltSistemas($_REQUEST['datos_estacion'])){
			return false;
		}
		return true;
	}

	/*
	*		GetPlantillasEstacion
	*
	*		Obtiene la plantilla de RxS de la estacion
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function GetPlantillasEstacion($estacion_id)
	{
		$query = "SELECT A.hc_ne_plantilla_sistema_id,
										 B.descripcion
							FROM  hc_ne_plantillas_estacion A,
										hc_ne_plantillas_sistema B
							WHERE A.estacion_id = '$estacion_id' AND
										B.hc_ne_plantilla_sistema_id = A.hc_ne_plantilla_sistema_id";//echo "<br>".$query; exit;
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al consultar las tablas \"hc_ne_plantillas_estacion\" y \"hc_ne_plantillas_sistema\"<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
					$plantillasEstacion[] = $result->FetchRow();
				}
				return $plantillasEstacion;
			}
		}
	}//GetPlantillasEstacion


	/*
	*		GetPlantillaGeneral
	*
	*		Obtiene todos los datos de la plantilla general de Revision por sistemas
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function GetPlantillaGeneral()//GetRevisinPorSistemas
	{
		$query = "SELECT  A.hc_ne_sistema_id,
											B.hc_ne_sistema_revision_id,
											C.hc_ne_detalle_id,
											A.descripcion as sistema,
											B.descripcion as revision,
											C.descripcion as detalle,
											C.sw_complemento,
											A.indice_orden,
											C.indice_orden
							FROM hc_ne_sistemas as A
							LEFT JOIN hc_ne_sistemas_revision as B
							USING(hc_ne_sistema_id)
							LEFT JOIN hc_ne_sistemas_revision_detalle as C
							ON (B.hc_ne_sistema_id = C.hc_ne_sistema_id AND
									B.hc_ne_sistema_revision_id = C.hc_ne_sistema_revision_id)
							ORDER BY A.indice_orden,B.hc_ne_sistema_revision_id,C.indice_orden;";
		//echo "<br>".$query;
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al consultar las tablas de Revision por Sistemas<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
				{//while (!$result->EOF){
					$sizeofSistema[$data['hc_ne_sistema_id']]+=1;
					$sizeofRevision[$data['hc_ne_sistema_id']][$data['hc_ne_sistema_revision_id']]+=1;
					$plantillas[$data['hc_ne_sistema_id']][$data['hc_ne_sistema_revision_id']][] = $data;// $data[detalle];
				}
				$plantillasEstacion['sizeofSistema'] = $sizeofSistema;
				$plantillasEstacion['sizeofRevision'] = $sizeofRevision;
				$plantillasEstacion['datos'] = $plantillas;
				return $plantillasEstacion;
			}
		}
	}

	/*
	*		UpdatePlantilla
	*
	*		Actuliza la plantilla de revision por sistemas
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function UpdatePlantilla()
	{
		$sistema_id = $_REQUEST[sistema_id];
		$categoria_id = $_REQUEST[categoria_id];
		$detalle_id = $_REQUEST[detalle_id];
		$datos_estacion = $_REQUEST[datos_estacion];
		$Nuevo = $_REQUEST[Nuevo];
		$cambiar =  $_REQUEST[cambiar];
		$sw_complemento = $_REQUEST[sw_complemento];

		if($cambiar === "detalle")
		{
			$query =  "UPDATE hc_ne_sistemas_revision_detalle
									SET descripcion = '$Nuevo',
											sw_complemento = '$sw_complemento'
									WHERE hc_ne_detalle_id = $detalle_id AND
													hc_ne_sistema_id = $sistema_id AND
													hc_ne_sistema_revision_id = $categoria_id";
		}
		elseif($cambiar === "revision")
		{
			$query = "UPDATE hc_ne_sistemas_revision
								SET descripcion = '$Nuevo'
								WHERE hc_ne_sistema_id = $sistema_id AND
											hc_ne_sistema_revision_id = $categoria_id";
		}
		elseif($cambiar === "sistema")
		{
			$query = "UPDATE hc_ne_sistemas
								SET descripcion = '$Nuevo'
								WHERE hc_ne_sistema_id = $sistema_id";
		}//echo "<br>el query de actualizar es<br>".$query;
		if($query)
		{
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar actualizar la plantilla<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else{
				$this->frmError["MensajeError"] = "LA ACTUALIZACIÓN SE REALIZÓ CON EXITO";
			}
		}
		$this->CallFrmMantenimientoPltSistemas($datos_estacion);
		return true;
	}//UpdatePlantilla


	/**
	*		ElimRxSdetalle
	*
	*		Elimina un item de la plantilla de RxS
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function ElimRxSdetalle()
	{
		$sistema_id = $_REQUEST[sistema_id];
		$categoria_id = $_REQUEST[categoria_id];
		$detalle_id = $_REQUEST[detalle_id];
		$datos_estacion = $_REQUEST[datos_estacion];
		$Nuevo = $_REQUEST[Nuevo];
		$borrar =  $_REQUEST[borrar];
		$plantilla =  $_REQUEST[plantilla];
		list($dbconn) = GetDBconn();

		if($borrar === "detalle")
		{
			$query2 =  "SELECT hc_ne_detalle_id
									FROM hc_ne_plantilla_detalle
									WHERE hc_ne_sistema_id = $sistema_id AND
												hc_ne_sistema_revision_id = $categoria_id AND
												hc_ne_detalle_id = $detalle_id";//echo "<br>".$query2;
			$result = $dbconn->Execute($query2);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar actualizar la plantilla<br><br>".$dbconn->ErrorMsg()."<br><br>".$query2;
				return false;
			}
			else
			{
				if($result->EOF)
				{//solo puedo borrar los detalles sino estan en plantillas
					$query = "DELETE FROM hc_ne_sistemas_revision_detalle
										WHERE hc_ne_detalle_id = $detalle_id AND
													hc_ne_sistema_id = $sistema_id AND
													hc_ne_sistema_revision_id = $categoria_id";
				}
				else{
					$this->frmError["MensajeError"] = "NO SE PUEDE BORRAR UNA OPCIÓN SI ESTÁ RELACIONADA CON ALGNA PLANTILLA";
				}
			}
		}
		elseif($borrar === "revision")
		{
			$query2 = "SELECT hc_ne_detalle_id
								FROM hc_ne_sistemas_revision_detalle
								WHERE hc_ne_sistema_id = $sistema_id AND
								hc_ne_sistema_revision_id = $categoria_id";//echo "<br>".$query2;
			$result = $dbconn->Execute($query2);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar actualizar la plantilla<br><br>".$dbconn->ErrorMsg()."<br><br>".$query2;
				return false;
			}
			else
			{//solo puedo borra categoria (revision) si no tiene detalles
				if($result->EOF)
				{
					$query = "DELETE FROM hc_ne_sistemas_revision
										WHERE hc_ne_sistema_id = $sistema_id AND
													hc_ne_sistema_revision_id = $categoria_id";
				}
				else{
					$this->frmError["MensajeError"] = "NO SE PUEDE BORRAR UNA CATEGORIA MIENTRAS ESTÉ RELACIONADA CON OPCIONES";
				}
			}
		}
		elseif($borrar === "sistema")
		{
			$query2 = "SELECT hc_ne_sistema_revision_id
								 FROM hc_ne_sistemas_revision
								 WHERE hc_ne_sistema_id = $sistema_id";//echo "<br>".$query2;
			$result = $dbconn->Execute($query2);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar actualizar la plantilla<br><br>".$dbconn->ErrorMsg()."<br><br>".$query2;
				return false;
			}
			else
			{//solo puedo borrar el sistema si no tiene categorias (revision)
				if($result->EOF)
				{
					$query = "DELETE FROM hc_ne_sistemas
										WHERE hc_ne_sistema_id = $sistema_id";
				}
				else{
					$this->frmError["MensajeError"] = "NO SE PUEDE BORRAR UN SISTEMA MIENTRAS ESTÉ RELACIONADO CON CATEGORIAS";
				}
			}
		}//echo "<br>el query de eliminar $borrar es<br>".$query;
		if($query)
		{
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar actualizar la plantilla<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else{
				$this->frmError["MensajeError"] = "ELIMINACIÓN REALIZADA CON EXITO";
			}
		}
		$this->CallFrmMantenimientoPltSistemas($datos_estacion);
		return true;
	}//ElimRxSdetalle


	/*
	*		CallFrmUpdateRxSdetalle
	*
	*		Llama al formulario que actualiza la plantilla de RxS
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function CallFrmUpdateRxSdetalle()
	{
		if(!$this->FrmUpdateRxSdetalle($_REQUEST['cambiar'],$_REQUEST['sistema_id'],$_REQUEST['sistema'],$_REQUEST['categoria_id'],$_REQUEST['categoria'],$_REQUEST['detalle_id'],$_REQUEST['detalle'],$_REQUEST['sw_complemento'],$_REQUEST['datos_estacion'])){
			return false;
		}
		return true;
	}//UpdateRxSdetalle

	/*
	*		VerificaDetalle
	*
	*		Busca si existe en la plantilla de la estacion el detalle x
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@param integer id de la plantilla
	*		@param integer id del sistema
	*		@param integer id del la categora
	*		@param integer id del detalle
	*		@return bool - string
	*/
	function VerificaDetalle($plantilla,$sistema,$categoria,$detalle)
	{
		$query = "SELECT sw_complemento
							FROM hc_ne_plantilla_detalle
							WHERE hc_ne_plantilla_sistema_id = $plantilla AND
										hc_ne_sistema_id = $sistema AND
										hc_ne_sistema_revision_id = $categoria AND
										hc_ne_detalle_id = $detalle";//echo "<br>".$query;
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al consultar la tabla hc_ne_sistemas_revision_detalle<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "vacio";
			}
			else{
				return $result->fields[sw_complemento];
			}
		}
		return true;
	}//VerificaDetalle

	/*
	*		MantenimientoPlantilla
	*
	*		Adiciona o elimina detalles a la plantilla de la estacion y su respectivo sw_complemento
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function MantenimientoPlantilla()
	{
		$CantDetalles = $_REQUEST[CantDetalles];
		$datos_estacion = $_REQUEST[datos_estacion];
		$swComplemento = $_REQUEST[sw_complemento];
		$detalles = $_REQUEST[seleccionado];
		$Plantilla = $_REQUEST[ Plantilla];

		############################# crear la plantilla a la estacion si no tiene ######################
		if(empty($Plantilla))
		{
			$query = "SELECT nextval('hc_ne_plantillas_sistema_hc_ne_plantilla_sistema_id_seq') as plantilla;";
			//echo "<br>".$query;
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar obtener el valor de la secuencia \"hc_ne_plantillas_sistema_hc_ne_plantilla_sistema_id_seq\"<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else{
				$Plantilla = $result->fields[plantilla];
			}

			$query = "INSERT INTO hc_ne_plantillas_sistema (hc_ne_plantilla_sistema_id,descripcion)
																											VALUES ($Plantilla,'Plantilla de la estación ".$datos_estacion[estacion_id]."');";
			//echo "<br>".$query;
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar asignar la plantilla a la estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				$query = "INSERT INTO hc_ne_plantillas_estacion (hc_ne_plantilla_sistema_id,estacion_id)
																								VALUES ($Plantilla,'".$datos_estacion[estacion_id]."');";//echo "<br>".$query;
				$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al intentar asignar la plantilla a la estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
				else{
					$dbconn->CommitTrans();
				}
			}
		}//fin no existe plantilla
		############################# insertar los detalles #############################
		list($dbconn) = GetDBconn();
		/*if(sizeof($detalles) != $CantDetalles)
		{//echo "<br>SI MODIFICO ESO<BR>numero de checks=> ".sizeof($detalles)." contador=> ".$CantDetalles;*/
			$query = "DELETE FROM hc_ne_plantilla_detalle
								WHERE hc_ne_plantilla_sistema_id = $Plantilla";//echo "<br><br>".$query."<br>";
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar realizar los cambios en los detalles de la plantilla<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}

			$i=0;
			foreach($detalles as $key => $value)
			{
				list($sistema,$revision,$detalle) = explode(".-.",$value);
				//list($sistemaComplemento,$revisionComplemento,$detalleComplemento) = explode(".-.",$swComplemento[$i]);
				if(in_array("$sistema.-.$revision.-.$detalle",$swComplemento)){//echo "<br>SI tiene complemento";
					$complemento = '1';
				}
				else {//echo "<br>NO tiene complemento";
					$complemento = '0';
				}
				$query = "INSERT INTO hc_ne_plantilla_detalle (hc_ne_plantilla_sistema_id,
																											hc_ne_detalle_id,
																											hc_ne_sistema_id,
																											hc_ne_sistema_revision_id,
																											sw_complemento
																											)
																								VALUES ($Plantilla,
																												$detalle,
																												$sistema,
																												$revision,
																												$complemento);";//echo "<br>".$query;
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al intentar asignar los detalles a la plantilla de la estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
				$i++;
			}
		//}
		//else{//echo "<br>NO MODIFICO ESO<BR>";}
		$this->CallFrmMantenimientoPltSistemas($datos_estacion);
		return true;
	}//MantenimientoPlantilla


	/*
	*		CallFrmAddItemsPlantilla()
	*
	*		Llama al formulario que permite adicionar items a la plantilla de RxS
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function CallFrmAddItemsPlantilla()
	{
		if(!$this->FrmAddItemsPlantilla($_REQUEST['add'],$_REQUEST['sistema_id'],$_REQUEST['sistema'],$_REQUEST['categoria_id'],$_REQUEST['categoria'],$_REQUEST['datos_estacion'])){
			return false;
		}
		return true;
	}//CallFrmAddItemsPlantilla


	/*
	*		InsertarItemPlantilla
	*
	*		Inserta un item a la plantilla
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function InsertarItemPlantilla()
	{
		$add = $_REQUEST[add];
		$sistema_id = $_REQUEST[sistema_id];
		$categoria_id = $_REQUEST[categoria_id];
		$sw_complemento = $_REQUEST[sw_complemento];
		$Nuevo = $_REQUEST[Nuevo];
		$datos_estacion = $_REQUEST[datos_estacion];

		if($add === "revision")
		{
			$query =  "INSERT INTO hc_ne_sistemas_revision (hc_ne_sistema_id,
																											descripcion
																											)
																							VALUES	($sistema_id,
																											 '$Nuevo');";
		}
		elseif($add === "opcion")
		{
			$query = "INSERT INTO hc_ne_sistemas_revision_detalle (
																															hc_ne_sistema_id,
																															hc_ne_sistema_revision_id,
																															descripcion,
																															sw_complemento
																														)
																										VALUES (
																															$sistema_id,
																															$categoria_id,
																															'$Nuevo',
																															'".$sw_complemento."'
																														);";
		}
		elseif($add === "sistema"){
			$query = "INSERT INTO hc_ne_sistemas (descripcion) VALUES ('$Nuevo');";
		}//echo "<br>el query de Insertar $add es<br>".$query;
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar actualizar la plantilla<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		$this->CallFrmMantenimientoPltSistemas($datos_estacion);
		return true;
	}//InsertarItemPlantilla



/*************************************************************************************/
/*************************************************************************************/
	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function CallFrmAdminMezclas()
	{
		if (!$this->FrmAdminMezclas($_REQUEST['datos_estacion'])){
			return false;
		}
		return true;
	}

		/*
		* GetConsulta() llama a la funcion FrmConsulta del submoduloHijo HTML para obtiener el
		* HTML de listado y lo retorna a la funcion xxx del modulo
		*/
		function MakeMezcla()//Obtiene el HTML de tipo consulta
		{
			if(!$this->FrmMezcla())
			{
				$this->mensajeDeError.= " - Error en MakeMezcla retornado de FrmMezcla";
				return false;
			}
			return true;
		}//End

	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
		function GetMzclaQueryBodegas($empresa,$c_u,$estacion_id)
		{
			$queryBgas=urlencode("SELECT
																		CASE	WHEN pos=1 THEN 'SI'
																					ELSE 'NO'
																		END as pos,
																		codigo_producto,
																		descripcion,
																		presentacion,
																		formfarmnombre,
																		concentracion,
																		principio_activo,
																		unidescripcion,
																		bodega
														FROM 		medicamentos_bodega
														WHERE 	empresa_id='".$empresa."' AND
																		centro_utilidad='".$c_u."' AND
																		estacion_id='".$estacion_id."'  ");
			return $queryBgas;
		}

		/*
		*
		*
		*		@Author Arley Velásquez C.
		*		@access Private
		*		@return bool
		*/
		function GetMzclaBodegasEstacion($empresa,$cu,$estacion)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$bodegas=array();
			$cont=0;
			$query = "SELECT  b.bodega,
												b.descripcion
								FROM  bodegas_estaciones a,
											bodegas b
								WHERE b.bodega=a.bodega AND
											b.centro_utilidad=a.centro_utilidad AND
											b.empresa_id=a.empresa_id AND
											a.empresa_id='$empresa' AND
											a.centro_utilidad='$cu' AND
											a.estacion_id='$estacion' AND
											a.sw_bodega_principal='1'";
			//echo "<br>BD->".$query;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if (!$resultado)
				{
					$this->error = "Error al tratar de realizar la consulta.<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
			$bodegas[] = $resultado->FetchRow();
			return $bodegas;
		}


  /*
		*
		*
		*		@Author Arley Velásquez C.
		*		@access Private
	*/
		function GetMzclaLista($empresa,$cu,$estacion)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$mezcla_grupo=array();
			$cont=0;
			$query = "SELECT  a.mezcla_grupo_id,
												a.descripcion,
												a.bodega,
												b.medicamento_id,
												c.descripcion as desc_medicamento,
												c.presentacion,
												c.formfarmnombre,
												c.concentracion,
												c.unidescripcion,
												b.cantidad,
												b.indicaciones_suministro
								FROM  hc_mezcla_grupos a,
											hc_mezcla_medicamentos b,
											nombre_medicamento c
								WHERE a.mezcla_grupo_id=b.mezcla_grupo_id AND
											a.empresa_id='$empresa' AND
											a.empresa_id=c.empresa_id AND
											b.medicamento_id=c.codigo_producto AND
											a.centro_utilidad='$cu' AND
											a.estacion_id='$estacion' ";
			//echo "<br>BD->".$query;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if (!$resultado)
				{
					$this->error = "Error al tratar de realizar la consulta.<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}

			while ($data = $resultado->FetchRow()){
				$mezcla_grupo[$data['descripcion']][]=$data;
			}
			return $mezcla_grupo;
		}

	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
		function InsertarMzclaMedicamentos()
		{
			list($dbconn) = GetDBconn();
			$nomMezcla=$_REQUEST['NombreMezcla'];
			$empresa=$_REQUEST['datos_estacion']['empresa_id'];
			$centroUtilidad=$_REQUEST['datos_estacion']['centro_utilidad'];

			$mezcla_medicamentos=SessionGetVar('mtz_mezclas');
			$bodega=SessionGetVar('mtz_mezclas_bodega');

			if (empty($nomMezcla) && sizeof($mezcla_medicamentos)>1)
			{
				$this->frmError["NombreMezcla"]=1;
				$this->error=1;
			}
			if (!empty($this->error))
			{
				$this->frmError["MensajeError"]="Verfique los campos en rojo";
				$this->FrmAdminMezclas($_REQUEST['datos_estacion']);
				return true;
			}

			if (sizeof($mezcla_medicamentos))
			{
				$serie="SELECT nextval('public.hc_mezcla_grupos_mezcla_grupo_id_seq'::text)";
				$resultado=$dbconn->Execute($serie);
					if (!$resultado)
					{
						$this->error = "Error al tratar de realizar la consulta.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
				$serie=$resultado->fields[0];

				$dbconn->BeginTrans();
				if (sizeof($mezcla_medicamentos)==1){
					$query="INSERT INTO hc_mezcla_grupos(mezcla_grupo_id,descripcion,empresa_id,centro_utilidad,bodega,estacion_id)
									VALUES($serie,'".$mezcla_medicamentos[0]['nombre']."','$empresa','$centroUtilidad','".$bodega[0]['bodegas']."','".$_REQUEST['datos_estacion']['estacion_id']."');";
				}
				else{
					$query="INSERT INTO hc_mezcla_grupos(mezcla_grupo_id,descripcion,empresa_id,centro_utilidad,bodega,estacion_id)
									VALUES($serie,'$nomMezcla','$empresa','$centroUtilidad','".$bodega[0]['bodegas']."','".$_REQUEST['datos_estacion']['estacion_id']."');";
				}
				//echo "<br>Q->".$query;
				$resultado=$dbconn->Execute($query);
				if ($resultado)
				{
						foreach($mezcla_medicamentos as $key => $value){
							$serie2="SELECT nextval('public.hc_mezcla_medicamentos_mezcla_medicamento_id_seq'::text)";
							$resultado=$dbconn->Execute($serie2);
								if (!$resultado)
								{
									$this->error = "Error al tratar de realizar la consulta.<br>";
									$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
								}
							$serie2=$resultado->fields[0];

							$query="INSERT INTO hc_mezcla_medicamentos(mezcla_medicamento_id,mezcla_grupo_id,medicamento_id,cantidad,empresa_id,indicaciones_suministro)
											VALUES ($serie2,$serie,'".$value['codigo']."',".$value['cantidad'].",'$empresa','".$value['ind_suministro']."');";
							$resultado=$dbconn->Execute($query);
							if (!$resultado)
							{
								$this->error = "Error al tratar de realizar la insercion.<br>";
								$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}
						}
					$dbconn->CommitTrans();
					SessionDelVar('mtz_mezclas');
					SessionDelVar('mtz_mezclas_bodega');
					$this->FrmAdminMezclas($_REQUEST['datos_estacion']);
				}
				else
				{
					$this->mensajeDeError= " - Error en la consulta al no poder insertar el grupo de la mezcla.";
					return false;
				}
			}
			else
			{
				SessionDelVar('mtz_mezclas');
				SessionDelVar('mtz_mezclas_bodega');
				$this->FrmAdminMezclas($_REQUEST['datos_estacion']);
			}

			return true;
		}
/*************************************************************************************/
/*************************************************************************************/

	/*
	*
	*
	*		@Author Arley Velásquez Castillo
	*		@access Public
	*		@return bool
	*/
	function CallPlantillaSuministros()
	{
		if (!$this->PlantillaSuministros($_REQUEST['datos_estacion'])){
			return false;
		}
		return true;
	}

	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function GetSuministros($empresa_id,$c_u,$bodega,$estacion_id)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$query= "SELECT insumo.insumo_id, insumo.tipo_insumo, insumo.descripcion as ins_descripcion, det_insumo.codigo_producto, det_insumo.descripcion FROM hc_tipos_insumo insumo LEFT JOIN ( SELECT est_insumo.insumo_id, bod.empresa_id, bod.bodega, bod.centro_utilidad, est_insumo.estacion_id, est_insumo.codigo_producto, inv.descripcion FROM hc_insumos_estacion est_insumo, inventarios_productos inv, bodegas bod, existencias_bodegas exi_bod WHERE bod.empresa_id='$empresa_id' and bod.centro_utilidad='$c_u' AND bod.bodega='$bodega' AND exi_bod.bodega=bod.bodega AND exi_bod.centro_utilidad=bod.centro_utilidad AND est_insumo.estacion_id='$estacion_id' AND exi_bod.codigo_producto=est_insumo.codigo_producto AND inv.codigo_producto=est_insumo.codigo_producto ORDER BY inv.descripcion ) AS det_insumo ON insumo.insumo_id=det_insumo.insumo_id and det_insumo.centro_utilidad='01' AND det_insumo.estacion_id='$estacion_id';";

		//echo "<br><br>Q->".$query;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Error al tratar de realizar la consulta.<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			while ($data = $resultado->FetchRow()){
				$suministros[$data['ins_descripcion']][]=$data;
			}
		return $suministros;
	}

	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function Bodegas($empresa_id,$c_u,$estacion_id)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$bodegas=array();
		$query = "SELECT  b.bodega,
											b.descripcion,
											b.empresa_id,
											b.centro_utilidad,
											a.estacion_id
							FROM  bodegas_estaciones a,
										bodegas b
							WHERE b.bodega=a.bodega AND
										b.centro_utilidad=a.centro_utilidad AND
										b.empresa_id=a.empresa_id AND
										a.estacion_id='$estacion_id' AND
										a.empresa_id='$empresa_id' AND
										a.centro_utilidad='$c_u' AND
										b.estado='1'";
		//echo "<br><br>Q->".$query;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Error al tratar de realizar la consulta.<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}

			while ($data = $resultado->FetchRow()) {
				$bodegas[]=$data;
			}//End While
		return $bodegas;
	}


	/*
	*  GetNombMedicamentos($CodiMedicamento,$empresa_id)
	* $CodiMedicamento es el codigo del medicamento a buscar
	* Se busca en la tabla inventarios y medicamentos para obtener el nombre + concentración
	* retorna el nombre del medicamento
	*/
	function GetNombMedicamentos($CodiMedicamento,$empresa_id)
	{
		//---------------- obtengo el nombre del medicamento ----------------
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$query="SELECT descripcion, presentacion, formfarmnombre, concentracion
						FROM nombre_medicamento
						WHERE empresa_id='".$empresa_id."' AND codigo_producto='".$CodiMedicamento."'";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if (!$resultado)
		{
			$this->error = "Error al tratar de realizar la consulta.<br>";
			$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
			return false;
		}
			if (empty($resultado->fields['concentracion'])){
				return ($resultado->fields['descripcion']." ".$resultado->fields['formfarmnombre']." ".$resultado->fields['presentacion']);
			}
			else
				return ($resultado->fields['descripcion']." ".$resultado->fields['concentracion']." ".$resultado->fields['formfarmnombre']." ".$resultado->fields['presentacion']);
	}//End


	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function GetQueryBodegas($empresa,$c_u,$pos=false,$estacion_id)
	{
			if ($pos){
				$queryBgas=urlencode("SELECT 	pos,
																			codigo_producto,
																			descripcion,
																			presentacion,
																			formfarmnombre,
																			concentracion,
																			principio_activo,
																			unidescripcion,
																			bodega
															FROM 		medicamentos_bodega
															WHERE 	empresa_id='".$empresa."' AND
																			pos='$pos' AND
																			centro_utilidad='".$c_u."' AND
																			estacion_id='".$estacion_id."'
														");
			}
			else{
				$queryBgas=urlencode("SELECT
																			CASE	WHEN pos=1 THEN 'SI'
																						ELSE 'NO'
																			END as pos,
																			codigo_producto,
																			descripcion,
																			presentacion,
																			formfarmnombre,
																			concentracion,
																			principio_activo,
																			unidescripcion,
																			bodega
															FROM 		medicamentos_bodega
															WHERE 	empresa_id='".$empresa."' AND
																			centro_utilidad='".$c_u."' AND
																			estacion_id='".$estacion_id."'
														");
			}

		return $queryBgas;
	}

	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function CallAddInsumoProducto()
	{
		if (!$this->AddInsumoProducto($_REQUEST['datos_estacion'],$_REQUEST['tipo_insumo'],$_REQUEST['insumo_id'])){
			return false;
		}
		return true;
	}

	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function CallAddInsumoTSuministro()
	{
		if (!$this->AddInsumoTSuministro($_REQUEST['datos_estacion'],$_REQUEST['tipo_insumo'])){
			return false;
		}
		return true;
	}


	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function InsertarInsumoProducto()
	{
		list($dbconn) = GetDBconn();

		if ($_REQUEST['tipo_insumo']==="M"){
			if (empty($_REQUEST['ManPlantillaIdMedicamento'])){
				$this->frmError[$this->frmPrefijo."MedicamentoID"]=1;
				$this->error="Error";
			}
		}
		else{
			if (empty($_REQUEST['codigo'])){
				$this->frmError[$this->frmPrefijo."codigo"]=1;
				$this->error="Error";
			}
		}

		if (!empty($this->error)){
			$this->frmError["MensajeError"]="Verfique los campos en rojo";
			$this->CallAddInsumoProducto();
			return true;
		}

		if ($_REQUEST['tipo_insumo']==="M"){
			$query="INSERT INTO hc_insumos_estacion(insumo_id,codigo_producto,empresa_id,estacion_id)
							VALUES (".$_REQUEST['insumo_id'].",'".$_REQUEST['ManPlantillaIdMedicamento']."','".$_REQUEST['datos_estacion']['empresa_id']."','".$_REQUEST['datos_estacion']['estacion_id']."')";
			//echo "<br><br>Q->".$query;
			$resultado2=$dbconn->Execute($query);
			if (!$resultado2){
				$this->error = "Error al tratar de realizar la inserción.<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
		}
		else{
			$query="INSERT INTO hc_insumos_estacion(insumo_id,codigo_producto,empresa_id,estacion_id)
							VALUES (".$_REQUEST['insumo_id'].",'".$_REQUEST['codigo']."','".$_REQUEST['datos_estacion']['empresa_id']."','".$_REQUEST['datos_estacion']['estacion_id']."')";
			//echo "<br><br>Q->".$query;
			$resultado2=$dbconn->Execute($query);
			if (!$resultado2){
				$this->error = "Error al tratar de realizar la inserción.<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
		}

		$this->CallPlantillaSuministros();
		return true;
	}

	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function InsertarTipoSuministro()
	{
		list($dbconn) = GetDBconn();

		if (empty($_REQUEST['tipo_suministro'])){
			$this->frmError[$this->frmPrefijo."tipo_suministro"]=1;
			$this->error="Error";
		}

		if (!empty($this->error)){
			$this->frmError["MensajeError"]="Verfique los campos en rojo";
			$this->CallAddInsumoTSuministro();
			return true;
		}

		$query="INSERT INTO hc_tipos_insumo(descripcion,tipo_insumo)
						VALUES ('".$_REQUEST['tipo_suministro']."','".$_REQUEST['tipo_insumo']."')";
		//echo "<br><br>Q->".$query;
		$resultado2=$dbconn->Execute($query);
		if (!$resultado2){
			$this->error = "Error al tratar de realizar la inserción.<br>";
			$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
			return false;
		}

		$this->CallPlantillaSuministros();
		return true;
	}



	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function EliminarInsumo()
	{
		list($dbconn) = GetDBconn();

		$query="DELETE
						FROM hc_insumos_estacion
						WHERE
									insumo_id=".$_REQUEST['insumo_id']." AND
									codigo_producto='".$_REQUEST['codigo_producto']."' AND
									empresa_id='".$_REQUEST['datos_estacion']['empresa_id']."' AND
									estacion_id='".$_REQUEST['datos_estacion']['estacion_id']."' ";
		//echo "<br><br>Q->".$query;
		$resultado=$dbconn->Execute($query);
			if (!$resultado)
			{
				$this->error = "Error al tratar de realizar la actualización.<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
		$this->CallPlantillaSuministros();
		return true;
	}


	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function CallEditTipoSuministro()
	{
		if (!$this->EditTipoSuministro($_REQUEST['datos_estacion'],$_REQUEST['descripcion'],$_REQUEST['insumo_id'])){
			return false;
		}
		return true;
	}


	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function EditarTipoSuministro()
	{
		list($dbconn) = GetDBconn();

		if (empty($_REQUEST['descripcion'])){
			$this->frmError[$this->frmPrefijo."descripcion"]=1;
			$this->error="Error";
		}

		if (!empty($this->error)){
			$this->frmError["MensajeError"]="Verfique los campos en rojo";
			$this->CallEditTipoSuministro();
			return true;
		}

		$query="UPDATE
									hc_tipos_insumo
						SET
									descripcion='".$_REQUEST['descripcion']."'
						WHERE
									insumo_id='".$_REQUEST['insumo_id']."'  ";
		//echo "<br><br>Q->".$query;
		$resultado=$dbconn->Execute($query);
			if (!$resultado)
			{
				$this->error = "Error al tratar de realizar la actualización.<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
		$this->CallPlantillaSuministros();
		return true;
	}


	/**
	*
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return string
	*/
	function DelTipoSuministro()
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

		$query="SELECT count(insumo_id) as cantidad
						FROM
									hc_insumos_estacion
						WHERE
									insumo_id=".$_REQUEST['insumo_id']." ";
		//echo "<br><br>Q->".$query;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado)
			{
				$this->error = "Error al tratar de realizar la consulta.<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
		$data=$resultado->FetchRow();
		if (empty($data['cantidad'])){
			$query="DELETE
							FROM hc_tipos_insumo
							WHERE
										insumo_id=".$_REQUEST['insumo_id']." ";
			//echo "<br><br>Q->".$query;
			$resultado=$dbconn->Execute($query);
			if (!$resultado)
			{
				$this->error = "Error al tratar de realizar la actualización.<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
		}
		else{
			$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR POR ENCONTRARSE RELACIONADO EN OTRAS ESTACIONES.";
		}
		$this->CallPlantillaSuministros();
		return true;
	}

	/*
	*
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/

	/*
	*
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/

/*
saca solo las piezas que tengan camas
		$query = "SELECT	P.pieza_id,
											P.ubicacion,
											P.estado,
											P.estacion_id,
											C.cama,
											E.descripcion,
											C.tipo_cama_id
							FROM piezas P, camas C, estados_cama E
							WHERE P.pieza_id = C.pieza_id AND E.estado_cama_id = C.estado_cama_id
							ORDER BY P.estacion_id, P.pieza_id, C.cama_id;";
*/

}//fin class

?>
