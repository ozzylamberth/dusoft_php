<?php

/**
 * $Id: app_Os_Pacientes_Hospitalizados_user.php,v 1.5 2005/07/05 18:17:24 duvan Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Listas de Trabajo (PHP).
 * Modulo para el manejo de listas de trabajo
 */

class app_Os_Pacientes_Hospitalizados_user extends classModulo
{
    var $limit;
    var $conteo;//para saber cuantos registros encontró

    function app_Os_Pacientes_Hospitalizados_user()
    {
        $this->limit=GetLimitBrowser();
        return true;
    }

    /**
    * La funcion main es la principal y donde se llama FormaPrincipal
    * que muestra los diferentes tipos de busqueda de una cuenta para hospitalización.
    * @access public
    * @return boolean
    */
		function main()
		{
			if(!$this->BuscarPermisosUser())
			{
				return false;
			}
			return true;
	  }


function GetForma()
{
    unset($_SESSION['DATOS_PACIENTE']);
		$_SESSION['DATOS_PACIENTE']['nombre']=$_REQUEST['nombre'];
		$_SESSION['DATOS_PACIENTE']['tipo_id']=$_REQUEST['tipo_id_paciente'];
		$_SESSION['DATOS_PACIENTE']['paciente_id']=$_REQUEST['paciente_id'];
		$_SESSION['DATOS_PACIENTE']['edad']= $_REQUEST['edad_paciente'];
		$this->FrmOrdenar();
		return $this->salida;
}

  /**
    * La funcion BuscarPermisosUser recibe todas las variables de manejo y verifica si el
    * usuario posee los permisos para acceder al modulo del laboratorio.
    * Nota: las variables pueden llegar por REQUEST o por Parametros.
    * @access private
    * @return boolean
    */
    function BuscarPermisosUser()
    {
				list($dbconn) = GetDBconn();
				GLOBAL $ADODB_FETCH_MODE;
				$query="SELECT c.servicio,
				c.departamento, c.descripcion as dpto, d.descripcion as centro, e.empresa_id,
				e.razon_social as emp,d.centro_utilidad, a.usuario_id, c.servicio
				FROM userpermisos_os_pacientes_hospitalizados a,
				departamentos c,	centros_utilidad d, empresas e
				WHERE a.usuario_id = '".UserGetUID()."' AND a.departamento=c.departamento
				AND c.centro_utilidad=d.centro_utilidad AND d.empresa_id=e.empresa_id
				AND c.empresa_id=e.empresa_id ORDER BY centro";

				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resulta = $dbconn->Execute($query);
				while($data = $resulta->FetchRow())
				{
						$admon[$data['emp']][$data['centro']][$data['dpto']]=$data;
				}

				$url[0]='app';
				$url[1]='Os_Pacientes_Hospitalizados';
				$url[2]='user';
				$url[3]='Menuatencion';
				$url[4]='Os_Lista';

				$arreglo[0]='EMPRESA';
				$arreglo[1]='CENTRO UTILIDAD';
				$arreglo[2]='ORDENES DE SERVICIO PACIENTES HOSPITALIZADOS';

				$this->salida.= gui_theme_menu_acceso('ORDENES DE SERVICIO PACIENTES HOSPITALIZADOS',$arreglo,$admon,$url);
				return true;
    }


    /**
    * Menuatencion, esta funcion  es donde desemboca el menu iteractivo creado por arley.
    * @access public
    * @return boolean
    */
 function Menuatencion()
 {
			unset($_SESSION['DATALAB']);
			$_SESSION['OS_PACIENTES']['EMPRESA_ID']=$_REQUEST['Os_Lista']['empresa_id'];
			$_SESSION['OS_PACIENTES']['CENTROUTILIDAD']=$_REQUEST['Os_Lista']['centro_utilidad'];
			$_SESSION['OS_PACIENTES']['NOM_CENTRO']=$_REQUEST['Os_Lista']['centro'];
			$_SESSION['OS_PACIENTES']['NOM_EMP']=$_REQUEST['Os_Lista']['emp'];
			$_SESSION['OS_PACIENTES']['NOM_DPTO']=$_REQUEST['Os_Lista']['dpto'];
			$_SESSION['OS_PACIENTES']['DPTO']=$_REQUEST['Os_Lista']['departamento'];
			$_SESSION['OS_PACIENTES']['SERVICIO']=$_REQUEST['Os_Lista']['servicio'];

			//controlando el uso de datalab en este modulo
			list($dbconn) = GetDBconn();
			$query = "select departamento from interface_datalab_departamentos
			where departamento = '".$_SESSION['OS_PACIENTES']['DPTO']."'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error consultando si el departamento esta asociado con Datalab.";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$dpto_datalab=$result->GetRowAssoc($ToUpper = false);
			}
			if (!empty($dpto_datalab[departamento]))
			{
        $_SESSION['DATALAB']['DPTO_DATALAB']=1;
			}
			//fin del control de datalab.

			/*if(!$this->FormaMetodoBuscar())
			{
					return false;
			}*/
			if(!$this->BuscarOrden())
			{
					return false;
			}

			return true;
  }



	/*
*
*	esta funcion trae los estados dependiendo del tipo y paciente_id
* para que se vea reflejado en el listado ojo con esta funcion
*/
function Traer_Estados_Os_maestros($TipoId,$PacienteId)
{
	list($dbconn) = GetDBconn();
	$query="SELECT DISTINCT	(c.sw_estado)

				FROM os_ordenes_servicios a,os_maestro c,os_internas d

				WHERE
				c.numero_orden_id=d.numero_orden_id
				AND a.orden_servicio_id=c.orden_servicio_id
				AND d.departamento='".$_SESSION['OS_PACIENTES']['DPTO']."'
				AND	c.sw_estado IN('1','2','3','0')
				AND DATE(c.fecha_activacion) <= NOW()
				AND DATE(c.fecha_vencimiento) >= NOW()
				AND a.tipo_id_paciente='$TipoId'
				AND a.paciente_id='$PacienteId'";
				$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "ERROR AL CONSULTAR ESTADOS";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}
			$arr=array();
			while(!$result->EOF)
			{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}

			for($i=0;$i<sizeof($vars);$i++)
			{
					if($vars[$i][sw_estado]=='0' OR $vars[$i][sw_estado]=='1')
					{
						$arr[1]=1;//pagas
					}

					if($vars[$i][sw_estado]=='2')
					{
						$arr[2]=2;//cumplimiento
					}

					if($vars[$i][sw_estado]=='3')
					{
						$arr[3]=3;//atencion
					}
			}
			return $arr;
}





    /**
    * Realiza la busqueda según el plan,documento .. de los pacientes que
    * tienen ordenes de servicios pendientes
    * @access private
    * @return boolean
    */
function BuscarOrden()
{
	list($dbconn) = GetDBconn();

  $filtro='';

	if ($_SESSION['DATALAB']['DPTO_DATALAB']==1)
	{
      $filtro="AND (c.sw_estado='1')";
	}
	else
	{
      $filtro="AND (c.sw_estado='1' OR c.sw_estado='3')";
	}

	if(empty($_REQUEST['conteo'.$pfj]))
	{
			$query = "SELECT count(*)
			from (SELECT DISTINCT
			btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
			b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
			b.tipo_id_paciente,b.paciente_id, b.fecha_nacimiento, a.plan_id,
			g.sw_internacion,	h.sw_prioridad, h.servicio, h.descripcion as servicio_descripcion,
			f.departamento, g.descripcion as dpto

			FROM pacientes as b,os_ordenes_servicios a,	os_maestro c,os_internas d,
			hc_os_solicitudes e, hc_evoluciones f, departamentos g, servicios h

			WHERE	c.numero_orden_id=d.numero_orden_id
			AND a.orden_servicio_id=c.orden_servicio_id
			AND d.departamento='".$_SESSION['OS_PACIENTES']['DPTO']."'
      $filtro
			AND DATE(c.fecha_activacion) <= NOW()
			AND DATE(c.fecha_vencimiento) >= NOW()
			AND a.tipo_id_paciente=b.tipo_id_paciente
			AND a.paciente_id=b.paciente_id
      AND c.hc_os_solicitud_id = e.hc_os_solicitud_id
			AND e.evolucion_id = f.evolucion_id
			AND f.departamento = g.departamento
			AND g.servicio = h.servicio
			AND g.sw_internacion = '1'
			) as A";

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


	$query = "
	SELECT DISTINCT
	btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
	b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
	b.tipo_id_paciente,b.paciente_id, b.fecha_nacimiento, a.plan_id,
	g.sw_internacion,	h.sw_prioridad, h.servicio, h.descripcion as servicio_descripcion,
	f.departamento, g.descripcion as dpto


	FROM pacientes as b,os_ordenes_servicios a,	os_maestro c,os_internas d,
	hc_os_solicitudes e, hc_evoluciones f, departamentos g, servicios h

	WHERE	c.numero_orden_id=d.numero_orden_id
	AND a.orden_servicio_id=c.orden_servicio_id
	AND d.departamento='".$_SESSION['OS_PACIENTES']['DPTO']."'
	$filtro
	AND DATE(c.fecha_activacion) <= NOW()
	AND DATE(c.fecha_vencimiento) >= NOW()
	AND a.tipo_id_paciente=b.tipo_id_paciente
	AND a.paciente_id=b.paciente_id
	AND c.hc_os_solicitud_id = e.hc_os_solicitud_id
	AND e.evolucion_id = f.evolucion_id
	AND f.departamento = g.departamento
	AND g.servicio = h.servicio
	AND g.sw_internacion = '1'


  order by h.sw_prioridad, h.descripcion, f.departamento
	LIMIT ".$this->limit." OFFSET $Of;";

	$resulta = $dbconn->Execute($query);

	//$this->conteo=$resulta->RecordCount();
	if ($dbconn->ErrorNo() != 0)
	{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
	}
	$i=0;
	while(!$resulta->EOF)
	{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
	}
	if($this->conteo==='0')
	{
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
		  $this->FormaMetodoBuscar($var);
			return true;
	}
	$this->FormaMetodoBuscar($var);
	return true;
}

// $spia es una variable q si esta activa  va a realizar un record count del query
//si no va vacia y se realiza el query comun y corriente.
function TraerOrdenesServicio($TipoId,$PacienteId,$spia='')
{
	list($dbconn) = GetDBconn();

//	if($_SESSION['LABORATORIO']['SW_ESTADO']==1)
//	{
			//$filtro_cuenta=", os_cuenta_activa('$TipoId','$PacienteId',c.plan_id) as sw_cuenta";

	//}
	//else
	//{
		//$filtro_cuenta=', 0 as sw_cuenta';
	//}

      $query="SELECT
					c.plan_id,c.plan_descripcion,a.servicio,h.descripcion as serv_des,
					sw_cargo_multidpto as switche,
					CASE c.sw_tipo_plan
					WHEN '0' THEN d.nombre_tercero
					WHEN '1' THEN 'SOAT'
					WHEN '2' THEN 'PARTICULAR'
					WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
					ELSE e.descripcion END,

					a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,
					i.fecha_vencimiento, f.cargo as cargoi,g.descripcion as des1,i.cantidad,
					a.autorizacion_int,a.autorizacion_ext,a.observacion,
					k.tipo_afiliado_nombre,h.sw_cargo_multidpto

					FROM os_ordenes_servicios as a, pacientes as b, planes c,
					terceros d, tipos_planes as e, os_internas as f, cups g,
					servicios h,os_maestro i, tipos_afiliado k

					WHERE
					a.orden_servicio_id=i.orden_servicio_id
					AND i.numero_orden_id=f.numero_orden_id
					AND a.tipo_id_paciente=b.tipo_id_paciente
					AND a.paciente_id=b.paciente_id
					AND a.tipo_id_paciente='$TipoId'
					AND a.paciente_id='$PacienteId'
					AND a.servicio=h.servicio
					AND g.cargo=f.cargo
					AND c.plan_id=a.plan_id
					AND e.sw_tipo_plan=c.sw_tipo_plan
					AND c.tercero_id=d.tercero_id
					AND c.tipo_tercero_id=d.tipo_id_tercero
 					AND f.departamento='".$_SESSION['OS_PACIENTES']['DPTO']."'
					AND i.sw_estado='1'
					AND a.tipo_afiliado_id=k.tipo_afiliado_id
					AND DATE(i.fecha_activacion) <= NOW()
					AND DATE(i.fecha_vencimiento) >= NOW()
					ORDER BY c.plan_id,i.fecha_vencimiento,a.orden_servicio_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al traer las 0rdenes de servicios";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if($spia==true)
			{
				return $result->RecordCount();
			}
			while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					$result->Close();
					return $var;
}

function Revision_Cita($numero_orden_id, $cargo)
{
    list($dbconnect) = GetDBconn();
	 $query= " SELECT a.cargo, a.departamento, b.sw_cita, a.tipo_equipo_imagen_id
		FROM departamentos_cargos_citas a, os_imagenes_tipo_equipos b
		WHERE a.tipo_equipo_imagen_id = b.tipo_equipo_imagen_id
		AND a.cargo = '".$cargo."' and a.departamento = '".$_SESSION['OS_PACIENTES']['DPTO']."' ";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al crear subexamen generico";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		$a=$result->GetRowAssoc($ToUpper = false);
    if($a[sw_cita]=='1')
		{
			$query= " SELECT numero_orden_id FROM os_imagenes_citas WHERE numero_orden_id = ".$numero_orden_id."";
			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
				$this->error = "Error al crear subexamen generico";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}
			$b=$result->GetRowAssoc($ToUpper = false);
			$a[existe_cita]=$b;
		}
	  return $a;
}
/*
	* Esta funcion permite buscar una cuenta activa en un paciente.
	* si tiene una cuenta activa o no para tener la opcion de cargarla a la cuenta.
	* @return array
	*/
	function BuscarCuentaActiva($op,$plan,$codigos_datalab)
	{
      if(empty($op))
			{
				$plan=$_REQUEST['plan_id'];
				$op=$_REQUEST['op'];
			}
			if(empty($op))
			{
       	  $this->frmError["MensajeError"]="SELECCIONE MINIMO 1 CARGO";
					if(!empty($_REQUEST['Nforma']))
					{ $this->FormaCumplimiento();}
					else
					{  $this->FrmOrdenar();  }
					return true;
		  }
			foreach($_REQUEST['op'] as $index=>$codigo)
			{
			  	$valores=explode(",",$codigo);
					if ($_REQUEST['codigo'.$valores[0]] == '-1')
					{
							$this->frmError["MensajeError"]="SELECCIONE UN CODIGO DE DATALAB";
							if(!empty($_REQUEST['Nforma']))
							{ $this->FormaCumplimiento();}
							else
							{  $this->FrmOrdenar();  }
							return true;
					}
					else
					{
            $codigos_datalab['codigo'.$valores[0]] = $_REQUEST['codigo'.$valores[0]] ;
					}
			}

			$this->LiquidacionOrden($var,$op,$plan,$codigos_datalab,'',$_REQUEST['Nforma']);
			return true;
	}

/*
	* Esta funcion trae los datos de la tabla
	* os_maestro,os_ordenes_servicios segun el numero de la orden.
	* @return array
	*/
	function DatosOs($orden)
	{
  		list($dbconn) = GetDBconn();
			$query = "SELECT *, b.cargo_cups,d.descripcion,c.os_maestro_cargos_id FROM os_ordenes_servicios       a,os_maestro b,os_maestro_cargos c,
			tarifarios_detalle as d
			WHERE a.orden_servicio_id=b.orden_servicio_id AND b.numero_orden_id=$orden
			AND b.numero_orden_id=c.numero_orden_id and c.cargo=d.cargo
			and c.tarifario_id=d.tarifario_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer las 0rdenes de servicios";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
			}

			$result->Close();
			return $var;
	}

	/*
	*  trae el nombre de copago y el de cuota moderadora.
	*/
	function BuscarNombreCop($plan)
	{
		  list($dbconn) = GetDBconn();
		  $query="SELECT nombre_copago,nombre_cuota_moderadora
		          FROM planes WHERE plan_id=$plan
							";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer los planes";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$result->GetRowAssoc($ToUpper = false);
			return $var;
	}

	/*
	* Esta funcion solo cambia el estado en os_maestro de 1 a 3
	* asi realiza el cumplimiento! funcion de duvan .
	* @return boolean
	*/
	function InsertarCargo()
	{
			list($dbconn) = GetDBconn();
		  $query="SELECT secuencia_os_cumplimiento('".$_SESSION['OS_PACIENTES']['DPTO']."')";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar en cuentas_detalle";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$serial=$result->fields[0];//generamos el numero de cumplimiento
			$dbconn->BeginTrans();
			$fecha=date("Y-m-d");
			$query="INSERT INTO os_cumplimientos
			(numero_cumplimiento,	fecha_cumplimiento,departamento,tipo_id_paciente,paciente_id)
			VALUES(".$serial.",'".$fecha."','".$_SESSION['OS_PACIENTES']['DPTO']."',
			'".$_SESSION['DATOS_PACIENTE']['tipo_id']."','".$_SESSION['DATOS_PACIENTE']['paciente_id']."')";

			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar en os_cumplimientos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}

		  foreach($_REQUEST['op'] as $index=>$codigo)
			{
				$valores=explode(",",$codigo);
				$query="UPDATE os_maestro SET sw_estado='3' where numero_orden_id='$valores[0]'";
				$dbconn->Execute($query);

							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al actualizar en os_maestro";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}
				//funcion q inserta en os_cumplimientos_detalle y os_cumplimientos.
				$this->InsertCumplimiento_Y_Detalle($_SESSION['OS_PACIENTES']['DPTO'],$valores[0],&$dbconn,$serial);
			}
			if($_SESSION['DATALAB']['DPTO_DATALAB']==1)
			{
				if ($this->UpdateDatos($_SESSION['OS_PACIENTES']['DPTO'],$_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id'],$serial,$fecha,&$dbconn)== true);
				{
					$dbconn->CommitTrans();
					$action2=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','RetornoCumplimiento');
					$this->FormaMensaje('CUMPLIMIENTO REALIZADO SATISFACTORIAMENTE!','INFORMACION',$action2,'volver');
				}
			}
			else
			{
					$dbconn->CommitTrans();
					$action2=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','RetornoCumplimiento');
					$this->FormaMensaje('CUMPLIMIENTO REALIZADO SATISFACTORIAMENTE!','INFORMACION',$action2,'volver');
			}
	    return true;
	}

	/*funcion que deja listo al paciente para que sea visto en las hojas de trabajo*/
	function InsertCumplimiento_Y_Detalle($dpto,$norden,&$dbconn,$serial,$user)
	{
		if($_SESSION['DATALAB']['DPTO_DATALAB']==1)
		{
				if(empty($user))
				{
					$query="INSERT INTO os_cumplimientos_detalle
								(numero_orden_id,numero_cumplimiento,
								fecha_cumplimiento,departamento)
								VALUES('$norden',".$serial.",
								'".date("Y-m-d")."','$dpto')";
				}
				else
				{
					$query="INSERT INTO os_cumplimientos_detalle
								(numero_orden_id,numero_cumplimiento,
								fecha_cumplimiento,departamento,usuario_id)
								VALUES('$norden',".$serial.",
								'".date("Y-m-d")."','$dpto',$user)";
				}
		}
		else
		{
       $sw_estado = '1';
				if(empty($user))
				{
					$query="INSERT INTO os_cumplimientos_detalle
								(numero_orden_id,numero_cumplimiento,
								fecha_cumplimiento,departamento, sw_estado)
								VALUES('$norden',".$serial.",
								'".date("Y-m-d")."','$dpto', $sw_estado)";
				}
				else
				{
					$query="INSERT INTO os_cumplimientos_detalle
								(numero_orden_id,numero_cumplimiento,
								fecha_cumplimiento,departamento,usuario_id, sw_estado)
								VALUES('$norden',".$serial.",
								'".date("Y-m-d")."','$dpto',$user, $sw_estado)";
				}
			}

			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al insertar en os_cumplimientos_detalle";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
			}
			return true;
	}


	function UpdateDatos($dpto,$tipoId,$PacId,$numero_cumplimiento,$fecha_cumplimiento,&$dbconn)
{
	IncludeLib('funciones_datalab');
	$paso=1;
//   if (empty($_REQUEST['op']))
// 	{
// 			$this->frmError["MensajeError"]="DEBE SELECCIONAR UN EXAMEN PARA EL ENVIO A DATALAB";
// 			//regresando al programa
// 			$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
// 			$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
// 			$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
// 			$_REQUEST['nombre'], $_REQUEST['edad_paciente']);
// 			return true;
// 	}
	foreach($_REQUEST['op'] as $index=>$codigo)
	{
			$arreglo=explode(",",$codigo);
	}
  //$arreglo[0] corresponde al numero_orden_id

		//funciones de equivalencia para datalab
		$tarifa = Get_Tarifa($arreglo[0]);

		$pagador = Get_Pagador($arreglo[0]);

		$pagador[plan_id]=str_pad($pagador[plan_id],4,0,STR_PAD_LEFT);
		$pagador[plan_id]= 'SIIS'.$pagador[plan_id];
// 		print_r($pagador);
// 		exit;

		$servicio = Get_Servicio($arreglo[0]);
		if ($servicio[servicio] == '3')
		{
			$tipo_orden = 'R';
		}
		else
		{
			$tipo_orden = 'U';
		}
		$servicio[servicio] = str_pad($servicio[servicio],2,0,STR_PAD_LEFT);
		$servicio[servicio] = 'SV'.$servicio[servicio];
// 		print_r($servicio);
// 		exit;

    //ojo se cambia para que no haga la busqueda con el request sino con los datso que le llegan por parametros.
		$datos_paciente = Get_Datos_Paciente($tipoId, $PacId);
		$sexo = Get_Sexo($datos_paciente[sexo_id]);

		//obtener turno
		$hora = date(H);
		if ($hora > 1 and date(H) < 12)
		{
			$turno = 'mañana';
		}
		elseif ($hora > 11 and date(H) < 19)
		{
			$turno = 'tarde';
		}
		elseif ($hora > 19 and date(H) < 24)
		{
			$turno = 'noche';
		}
    if($datos_paciente[historia_numero]!='')
		{
		  $hc = $datos_paciente[historia_prefijo].'||'.$datos_paciente[historia_numero];
		}
		else
		{
      $hc = $tipoId.'||'.$PacId;
		}
		$datos_solicitud = Get_Datos_Solicitud($arreglo[0]);

		if ($datos_solicitud[cama] == '')
		{
			$cama = Get_Cama($arreglo[0]);
		}
		else
		{
			$cama = $datos_solicitud[cama];
		}

		//cargarmos profesional con el nombre del medico que hizo la solicitud(ya sea manual o de evolucion)
		if ($datos_solicitud[usuario_id]!='' AND $datos_solicitud[profesional] == '')
		{
			$profesional = $datos_solicitud[nombre];
		}
		else
		{
			$profesional = $datos_solicitud[profesional];
		}
		//si es manual o no encuentra equivalencia medico se va vacio, sino medico se carga con la equivalencia.
		//$medico = $this->Get_Medico($datos_solicitud[usuario_id]);
    if($datos_solicitud[usuario_id]!='')
    {
      $medico = 'MUID'.$datos_solicitud[usuario_id];
		}
		else
		{
      $medico = 'MUIDNULL';
		}


 // list($dbconn) = GetDBconn();
	$sw_estado = '1';

	//$dbconn->BeginTrans();


//se hace una carga a una variable con el request del vector que se creo para los codigos de datalab
//en pacientes hospitalizados
	$codigos_datalab = $_REQUEST['codigos_datalab'];

	foreach($_REQUEST['op'] as $index=>$codigo)
	{
			$arreglo=explode(",",$codigo);
			if ($codigos_datalab['codigo'.$arreglo[0]] != '-1')
			{
					$query="UPDATE os_cumplimientos_detalle SET
					sw_estado = '".$sw_estado."'
					WHERE numero_orden_id = ".$arreglo[0]."";

					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error al actualizar el estado del apoyo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
					}
					else
					{
						$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
					}

      //*************PROCESO DE DATALAB*****************
      //insertar en control
			if($paso==1)
			{
					$query="SELECT nextval('interface_datalab_control_interface_datalab_control_id_seq')";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error al insertar en interface_datalab_control";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
					}
					$control_id=$result->fields[0];

					$query="INSERT INTO interface_datalab_control
									(interface_datalab_control_id,
									numero_cumplimiento, fecha_cumplimiento,
									departamento, tipo_id_paciente, paciente_id)
									VALUES(".$control_id.",
									".$numero_cumplimiento.", '".$fecha_cumplimiento."',
									'".$dpto."', '".$tipoId."',
								'".$PacId."')";

					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error al insertar en interface_datalab_control";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
					}
					$paso++;
			}
			$query="INSERT INTO interface_datalab_control_detalle
							(interface_datalab_control_id, numero_orden_id, codigo_datalab)
							VALUES(".$control_id.", ".$arreglo[0].", ".$codigos_datalab['codigo'.$arreglo[0]].")";

			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al insertar en interface_datalab_control_detalle";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
			}
			//en solicitudes el codigo de datalab se convierte a char y se
			//inserta en codigo_examen
			
			//aqui tambien se cambia para pacientes hospitalizados
      $codigo_examen = (string) $codigos_datalab['codigo'.$arreglo[0]];
			$control_id_char = (string) $control_id;

			 $query="INSERT INTO interface_datalab_solicitudes
			(hc, apellido, nombre,
			sexo,	fecha_nacimiento, hc1,
			hc2, fecha_hora_envio, orden1, orden2, orden3,
			orden4, orden5, orden6, orden7, tipo_orden,
			ordcomentario,	codigo_examen,
			orden1_char, orden3_char, orden4_char)
			VALUES ('".$hc."', '".$datos_paciente[apellidos]."', '".$datos_paciente[nombres]."',
			'".$sexo."', '".$datos_paciente[fecha_nacimiento]."', '".$datos_paciente[residencia_telefono]."',
			'".$tarifa."', now(),	'".$pagador[plan_id]."', '".$turno."', '".$servicio[servicio]."',
			'".$medico."', '".$cama."',	'".$control_id_char."', '', '".$tipo_orden."',
			'".$datos_solicitud[observacion]."',	'".$codigo_examen."',
			'".$pagador[plan_descripcion]."', '".$servicio[descripcion]."','".$profesional."')";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar en interface_datalab_solicitudes";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[1]." YA FUE ASIGNADO.";
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
					$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
			}
		}
  }
	//$dbconn->CommitTrans();

  //regresando al programa
// 	$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
// 	$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
// 	$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
// 	$_REQUEST['nombre'], $_REQUEST['edad_paciente']);
	return true;
}



	//ALERTA DE DONDE ME SACO ESTOS DATOS?
	/*
		* funcion que retorna el cumplimiento
		*/
		function RetornoCumplimiento()
		{
				//parte de duvan ...
					//$_SESSION['LABORATORIO']['SW_ESTADO']=1;
					$nom=$this->GetNombrePaciente($_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id']);


					//lo que comente
					// 					$conteo=$this->TraerOrdenesServicio($_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOLICITUD']['PACIENTE']['paciente_id'],true);
					// 					if($conteo >0)
					// 					{
					// 						$this->FrmOrdenar($nom,$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']);
					// 					}
					// 					else
					// 					{
					// 						$this->BuscarOrden();
					// 					}
					//fin de lo que comente

					if ($_SESSION['DATALAB']['DPTO_DATALAB']==1)
					{
              $this->BuscarOrden();
					}
					else
					{

							$this->FrmOrdenar();
					}
					return true;
		}

		/**
  * Busca el nombre del paciente
	* @access public
	* @return array
	*/
		function GetNombrePaciente($tipo,$paciente_id)
		{
					list($dbconn) = GetDBconn();
					$query="SELECT  btrim(primer_nombre||' '||segundo_nombre||' ' ||
                	primer_apellido||' '||segundo_apellido,'') as nombre
									FROM pacientes
									WHERE tipo_id_paciente='$tipo'
									AND paciente_id='$paciente_id' ";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}

					return $result->fields[0];
		}


		//si no va vacia y se realiza el query comun y corriente.
//trae las ordenes de servicio de estado 3 osea atender.
function TraerOrdenesServicio_estado3($TipoId,$PacienteId,$spia='')
{
	list($dbconn) = GetDBconn();

   $query="SELECT
					c.plan_id,c.plan_descripcion,a.servicio,h.descripcion as serv_des,
					sw_cargo_multidpto as switche,
					CASE c.sw_tipo_plan
					WHEN '0' THEN d.nombre_tercero
					WHEN '1' THEN 'SOAT'
					WHEN '2' THEN 'PARTICULAR'
					WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
					ELSE e.descripcion END,

					a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,
					i.fecha_vencimiento, f.cargo as cargoi,g.descripcion as des1,i.cantidad,
					a.autorizacion_int,a.autorizacion_ext,a.observacion,
					k.tipo_afiliado_nombre

					FROM os_ordenes_servicios as a, pacientes as b, planes c,
					terceros d, tipos_planes as e, os_internas as f, cups g,
					servicios h,os_maestro i,tipos_afiliado k

					WHERE
					a.orden_servicio_id=i.orden_servicio_id
					AND i.numero_orden_id=f.numero_orden_id
					AND a.tipo_id_paciente=b.tipo_id_paciente
					AND a.paciente_id=b.paciente_id
					AND a.tipo_id_paciente='$TipoId'
					AND a.paciente_id='$PacienteId'
					AND a.servicio=h.servicio
					AND g.cargo=f.cargo
					AND c.plan_id=a.plan_id
					AND e.sw_tipo_plan=c.sw_tipo_plan
					AND c.tercero_id=d.tercero_id
					AND c.tipo_tercero_id=d.tipo_id_tercero

 					AND f.departamento='".$_SESSION['OS_PACIENTES']['DPTO']."'
					AND i.sw_estado=3
					AND a.tipo_afiliado_id=k.tipo_afiliado_id
					AND DATE(i.fecha_activacion) <= NOW()
					AND DATE(i.fecha_vencimiento) >= NOW()
					ORDER BY f.numero_orden_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al traer las 0rdenes de servicios";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if($spia==true)
			{
				return $result->RecordCount();
			}
			while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					$result->Close();
					return $var;
}


	/*
		Funcion que trae el numero de cumplimiento generado
		al darse la atencion
	*/
	function TraerNumeroCumplimiento($orden_id)
	{
				list($dbconn) = GetDBconn();

				$query = "SELECT numero_cumplimiento,fecha_cumplimiento
				FROM os_cumplimientos_detalle
				WHERE numero_orden_id = ".$orden_id." AND
				departamento = '".$_SESSION['OS_PACIENTES']['DPTO']."' order by numero_cumplimiento asc";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al traer el numero de cumplimiento";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				return $var[]=$result->GetRowAssoc($ToUpper = false);
	}



	function ReporteFichaLaboratorio()
		{
				if (!IncludeFile("classes/reports/reports.class.php"))
				{
						$this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
						return false;
        }
				$AND=" ";
				$search="";
			//	print_r($_REQUEST['sel']);exit;
				$arr=$_REQUEST['sel'];
				if(sizeof($arr)>0)
				{		$search="";
						$union= "";
						for($k=0;$k<sizeof($arr);$k++)
            {
							if($k==0)
							{
								$union = ' and  (';
							}
							else
							{

								$union = ' or ';
							}
							$search.= "$union a.numero_orden_id= ".$arr[$k]."";
						}
						$search.=")";
				}
				else
				{		$AND=" ";
						$search="";
				}

				 list($dbconn) = GetDBconn();
/*
				 $query = "SELECT numero_cumplimiento, fecha_cumplimiento, departamento
				FROM os_cumplimientos_detalle
				WHERE numero_orden_id = ".$_REQUEST['numero_orden_id']." AND
				departamento = '".$_SESSION['OS_PACIENTES']['DPTO']."'";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
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
				}*/

				$query = "SELECT c.historia_prefijo, c.historia_numero,
				a.numero_cumplimiento, a.departamento,
				a.tipo_id_paciente,	a.paciente_id, a.fecha_cumplimiento,
				btrim(b.primer_nombre||' '||b.segundo_nombre||' '|| b.primer_apellido||
				' '||b.segundo_apellido,'')	as nombre FROM os_cumplimientos a
				left join historias_clinicas c on (a.paciente_id = c.paciente_id AND
				a.tipo_id_paciente =	c.tipo_id_paciente), pacientes b

				WHERE  a.paciente_id = b.paciente_id AND a.tipo_id_paciente =
				b.tipo_id_paciente AND a.numero_cumplimiento = ".$_REQUEST['numero']."
				AND a.fecha_cumplimiento = '".$_REQUEST['fecha_cumplimiento']."'
				AND	a.departamento = '".$_SESSION['OS_PACIENTES']['DPTO']."'

				order by a.fecha_cumplimiento, a.numero_cumplimiento";

				/*
        $query = "SELECT a.numero_cumplimiento, a.departamento,
				a.tipo_id_paciente,	a.paciente_id, a.fecha_cumplimiento,
				btrim(b.primer_nombre||' '||b.segundo_nombre||' '|| b.primer_apellido||
				' '||b.segundo_apellido,'')	as nombre FROM os_cumplimientos a,pacientes b
				WHERE  a.paciente_id = b.paciente_id AND a.tipo_id_paciente =
				b.tipo_id_paciente AND a.numero_cumplimiento = ".$datos[0][numero_cumplimiento]."
				AND a.fecha_cumplimiento = '".$datos[0][fecha_cumplimiento]."'
				AND	a.departamento = '".$datos[0][departamento]."'
        order by a.fecha_cumplimiento, a.numero_cumplimiento";
*/
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
						while (!$result->EOF)
						{
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
						}
				}


				$var[0][razon_social]=$_SESSION['OS_PACIENTES']['NOM_EMP'];

       /* $query = "SELECT a.numero_orden_id, b.sw_estado, c.cargo, c.descripcion
				FROM os_cumplimientos_detalle as a, os_maestro as b, cups as c
				WHERE a.numero_cumplimiento = ".$var[0][numero_cumplimiento]." AND
				a.fecha_cumplimiento = '".$var[0][fecha_cumplimiento]."' AND
				a.departamento = '".$var[0][departamento]."'
				AND a.numero_orden_id = b.numero_orden_id
				AND b.cargo_cups = c.cargo  order by a.numero_orden_id asc";*/

			 $query = "SELECT z.descripcion as dpto, a.numero_orden_id, b.sw_estado, e.tipo_os_lista_id, e.nombre_lista, c.cargo, c.descripcion
				FROM os_cumplimientos_detalle as a, os_maestro as b, cups as c left join
				tipos_os_listas_trabajo_detalle as d on (c.grupo_tipo_cargo = d.grupo_tipo_cargo
				AND c.tipo_cargo = d.tipo_cargo) left join tipos_os_listas_trabajo as e
				on (d.tipo_os_lista_id = e.tipo_os_lista_id), departamentos as z
				WHERE a.numero_cumplimiento = ".$var[0][numero_cumplimiento]." AND
				a.fecha_cumplimiento = '".$var[0][fecha_cumplimiento]."' AND
				a.departamento = '".$var[0][departamento]."' AND a.numero_orden_id = b.numero_orden_id
				and a.departamento=z.departamento
				AND b.cargo_cups = c.cargo
				AND a.departamento = e.departamento $search
				order by e.tipo_os_lista_id, a.numero_orden_id asc";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al buscar en la consulta de medicamentos recetados";
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
				$var[0][cargos]=$vector;


				unset($vector);

				$classReport = new reports;
				$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='Os_Atencion',$reporte_name='rotulo_laboratorio_lt',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
        if(!$reporte)
				{
             $this->error = $classReport->GetError();
             $this->mensajeDeError = $classReport->MensajeDeError();
             unset($classReport);
             return false;
        }

        $resultado=$classReport->GetExecResultado();
        unset($classReport);

        if(!empty($resultado[codigo]))
				{
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }
				//$this->ListadoPacientesEvolucionCerrada();
				if(empty($_REQUEST['destino']))
    		{  $this->FrmOrdenar(); }
				else
				{  $this->FormaImpresionCumplimiento($_REQUEST['orden']);  }

        return true;
		}


//NUEVA FUNCIONES
//**
function Cargar_Codigos_Datalab($cargo)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT * FROM interface_datalab_codigos WHERE codigo_cups = ".$cargo."";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de solictud de apoyos";
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
		$result->Close();
	  return $vector;
  }


//--------------------------------------SOLICITUD MANUAL DARLING--------------------------
		/**
		*
		*/
		function BuscarPaciente()
		{
						list($dbconn) = GetDBconn();
						$query = "SELECT sw_tipo_plan FROM planes
											WHERE estado='1' and plan_id=".$_REQUEST['plan']."
											and fecha_final >= now() and fecha_inicio <= now()";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}
						//1 soat
						if($result->fields[0]==1)
						{
									$this->frmError["MensajeError"]="Los Planes Soat deben Realizar el proceso en la Central de Autorizaciones.";
									$this->FormaBuscar();
									return true;
						}

						if($_REQUEST['plan']==-1)
						{
									if($Plan==-1){ $this->frmError["plan"]=1; }
									$this->frmError["MensajeError"]="Faltan datos obligatorios.";
									$this->FormaBuscar();
									return true;
						}

            if($_REQUEST['Tipo']=='AS' OR $_REQUEST['Tipo']=='MS')
            {  $_REQUEST['Documento']=$this->CallMetodoExterno('app','Pacientes','user','IdentifiacionNN');  }

						if($_REQUEST['Tipo']==-1 AND !$_REQUEST['Documento'] AND !$_REQUEST['prefijo'] AND !$_REQUEST['historia'])
						{
									$this->frmError["MensajeError"]="DEBE ELEGIR CRITERIOS PARA LA BUSQUEDA.";
									$this->FormaBuscar();
									return true;
						}

						if($_REQUEST['prefijo'] OR $_REQUEST['historia'])
						{
									$query = "SELECT tipo_id_paciente, paciente_id FROM historias_clinicas
														WHERE historia_numero='".$_REQUEST['historia']."'
														AND historia_prefijo='".strtoupper($_REQUEST['prefijo'])."'";
									$result=$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error al Guardar SELECT en historias_clinicas";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													return false;
									}
									if($result->EOF)
									{
											$this->frmError["MensajeError"]="LA HISTORIA NO EXISTE.";
											$this->FormaBuscar();
											return true;
									}
									else
									{
												$_REQUEST['Documento']=$result->fields[1];
												$_REQUEST['Tipo']=$result->fields[0];
									}
									$result->Close();
						}
						elseif($_REQUEST['Tipo']==-1 OR !$_REQUEST['Documento'])
						{
									if($_REQUEST['Tipo']==-1){ $this->frmError["Tipo"]=1; }
									if(!$_REQUEST['Documento']){ $this->frmError["Documento"]=1; }
									$this->frmError["MensajeError"]="PARA BUSCA POR DOCUMENTOS DEBE DIGITAR LAS DOS OPCIONES.";
									$this->FormaBuscar();
									return true;
						}

						unset($_SESSION['PACIENTES']);
						$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_REQUEST['Documento'];
						$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['Tipo'];
						$_SESSION['PACIENTES']['PACIENTE']['plan_id']=$_REQUEST['plan'];
						$_SESSION['PACIENTES']['RETORNO']['argumentos']=array();
						$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
						$_SESSION['PACIENTES']['RETORNO']['modulo']='Os_Pacientes_Hospitalizados';
						$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
						$_SESSION['PACIENTES']['RETORNO']['metodo']='RetornoPaciente';

						$this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
						return true;
		}

		/**
		*
		*/
		function RetornoPaciente()
		{
				unset($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
				$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO'];
				unset($_SESSION['PACIENTES']['PACIENTE']['ARREGLO']);
				//si se cancelo en proceso de tomar datos del paciente
				if(empty($_SESSION['PACIENTES']['RETORNO']['PASO']))
				{
							unset($_SESSION['PACIENTES']);
							$this->FormaBuscar();
							return true;
				}
				else
				{
							unset($_SESSION['DATOS_PACIENTE']);
							//$_SESSION['DATOS_PACIENTE']['nombre']=$_REQUEST['nombre'];
							$_SESSION['DATOS_PACIENTE']['tipo_id']=$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
							$_SESSION['DATOS_PACIENTE']['paciente_id']=$_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
							//$_SESSION['DATOS_PACIENTE']['edad']= $_REQUEST['edad_paciente'];
							$_SESSION['DATOS_PACIENTE']['plan_id']=$_SESSION['PACIENTES']['PACIENTE']['plan_id'];

							list($dbconn) = GetDBconn();
							$query = "SELECT plan_descripcion,sw_tipo_plan FROM planes
							WHERE plan_id=".$_SESSION['DATOS_PACIENTE']['plan_id']."";
							$result=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
															$this->error = "Error al Guardar en la Base de Datos";
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															return false;
							}
							$_SESSION['DATOS_PACIENTE']['plan_descripcion']=$result->fields[0];

							//unset($_SESSION['PACIENTES']);
							$this->PedirAutorizacion();
							return true;
				}
		}

    /**
    *
    */
    function PedirAutorizacion()
    {
					unset($_SESSION['AUTORIZACIONES']);
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['DATOS_PACIENTE']['paciente_id'];
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['DATOS_PACIENTE']['tipo_id'];
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_SESSION['DATOS_PACIENTE']['plan_id'];
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array();
					$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
					$_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='Os_Pacientes_Hospitalizados';
					$_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
					$_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='RetornoAutorizacion';

					$this->ReturnMetodoExterno('app','Autorizacion','user','SolicitudAutorizacionAmbulatoria');
					return true;
    }

    /**
    * Llama el modulo de autorizaciones
    * @access public
    * @return boolean
    */
   	function RetornoAutorizacion()
    {
					$_SESSION['DATOS_PACIENTE']['PACIENTE']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
					$_SESSION['DATOS_PACIENTE']['PACIENTE']['rango']=$_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
					$_SESSION['DATOS_PACIENTE']['PACIENTE']['semanas']=$_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
					$_SESSION['DATOS_PACIENTE']['PACIENTE']['observacion_ingreso']=$_SESSION['AUTORIZACIONES']['RETORNO']['observacion_ingreso'];

					if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['ext'])){  $_SESSION['AUTORIZACIONES']['RETORNO']['ext']='NULL'; }
					$_SESSION['DATOS_PACIENTE']['PACIENTE']['AUTORIZACIONEXT']=$_SESSION['AUTORIZACIONES']['RETORNO']['ext'];
					$_SESSION['DATOS_PACIENTE']['PACIENTE']['AUTORIZACION']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];

					$_SESSION['DATOS_PACIENTE']['Autorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion'];
					$_SESSION['DATOS_PACIENTE']['NumAutorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];

					if(empty($_SESSION['DATOS_PACIENTE']['NumAutorizacion']))
					{  $_SESSION['DATOS_PACIENTE']['NumAutorizacion']='NULL';  }

					unset($_SESSION['AUTORIZACIONES']);

					if(empty($_SESSION['DATOS_PACIENTE']['Autorizacion'])
							AND empty($_SESSION['DATOS_PACIENTE']['NumAutorizacion']))
					{
								if(empty($_SESSION['DATOS_PACIENTE']['NumAutorizacion']))
								{   $Mensaje = 'No se pudo realizar la Autorización.';   }
								$accion=ModuloGetURL('app','CentroAutorizacion','user','DetalleSolicitud');
								if(!$this-> FormaMensaje($Mensaje,'AUTORIZACION SOLICITUD MANUAL',$accion,'')){
								return false;
								}
								return true;
					}

					if(empty($_SESSION['DATOS_PACIENTE']['Autorizacion'])
					AND !empty($_SESSION['DATOS_PACIENTE']['NumAutorizacion']))
					{
											$Mensaje = 'No se Autorizo al Paciente.';
											$accion=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','FormaBuscar');
											if(!$this-> FormaMensaje($Mensaje,'AUTORIZACIONES',$accion,'')){
											return false;
											}
											return true;
					}

					$this->FormaDatosSolicitud();
					return true;
    }

		/**
		*
		*/
		function responsables()
		{
						list($dbconn) = GetDBconn();
						/*$query = "select sw_todos_planes from userpermisos_solicitud_manual
											where usuario_id=".UserGetUID()." and sw_todos_planes=1
											and punto_solicitud_manual_id=".$_SESSION['SOLICITUD']['PTOSOL']." ";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}
						if(!$result->EOF)
						{	*/		//tiene todos los planes
									$query="SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
													FROM planes as a
													WHERE a.fecha_final >= now() and a.estado=1
													and a.fecha_inicio <= now()
													and empresa_id='".$_SESSION['OS_PACIENTES']['EMPRESA_ID']."'
													order by a.plan_descripcion";
						/*}
						else
						{			//planes especificos
									$query="SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
													FROM planes as a, userpermisos_solicitud_manual as b
													WHERE a.fecha_final >= now() and a.estado=1 and a.fecha_inicio <= now()
													and b.usuario_id=".UserGetUID()."
													and b.plan_id=a.plan_id
													and b.punto_solicitud_manual_id=".$_SESSION['SOLICITUD']['PTOSOL']."
													and a.empresa_id='".$_SESSION['SOLICITUD']['EMPRESA']."'
													order by a.plan_descripcion";
						}*/
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}
						if(!$result->EOF)
						{
								while (!$result->EOF)
								{
										$var[]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
								}
						}
						$result->Close();
						return $var;
		}

	function tipo_id_paciente()
  {
					list($dbconn) = GetDBconn();
					$query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
					$result = $dbconn->Execute($query);

					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					else{
							if($result->EOF){
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
									return false;
							}
									while (!$result->EOF) {
											$vars[$result->fields[0]]=$result->fields[1];
											$result->MoveNext();
									}
					}
					$result->Close();
          return $vars;
	}

		/**
		*
		*/
		function BuscarCamposObligatorios()
		{
					list($dbconn) = GetDBconn();
					$query="SELECT campo,sw_mostrar,sw_obligatorio FROM pacientes_campos_obligatorios";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}

					while(!$result->EOF){
							$var[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}

					$result->Close();
					return $var;
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

    function NombrePaciente($TipoDocumento,$Documento)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombre
                                            FROM pacientes
                                            WHERE paciente_id='$Documento' AND tipo_id_paciente ='$TipoDocumento'";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $vars=$resulta->GetRowAssoc($ToUpper = false);
            return $vars;
    }

	/**
	*
	*/
	function Profesionales()
	{
					list($dbconn) = GetDBconn();
					$query = "SELECT * FROM profesionales
										WHERE tipo_profesional in(1,2)
										ORDER BY nombre";
					$result = $dbconn->Execute($query);

					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					else{
							if($result->EOF){
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
									return false;
							}
									while (!$result->EOF) {
											$vars[]=$result->GetRowAssoc($ToUpper = false);;
											$result->MoveNext();
									}
					}
					$result->Close();
          return $vars;
	}

	/**
	*
	*/
	function BuscarDepartamento()
	{
					list($dbconn) = GetDBconn();
					$query = "SELECT a.* FROM departamentos as a, servicios as b
										WHERE a.empresa_id= '".$_SESSION['OS_PACIENTES']['EMPRESA_ID']."'
										and a.servicio=b.servicio and b.sw_asistencial=1
										ORDER BY descripcion";
					$result = $dbconn->Execute($query);

					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					else{
							if($result->EOF){
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
									return false;
							}
									while (!$result->EOF) {
											$vars[]=$result->GetRowAssoc($ToUpper = false);;
											$result->MoveNext();
									}
					}
					$result->Close();
          return $vars;
	}

		/**
		*
		*/
		function GuardarDatosSolicitud()
		{
						if($_REQUEST['Serv']==-1 || !$_REQUEST['Fecha'])
						{
								if($_REQUEST['Serv']==-1){ $this->frmError["Serv"]=1; }
								if(!$_REQUEST['Fecha']){ $this->frmError["Fecha"]=1; }
								$this->frmError["MensajeError"]="Faltan datos obligatorios.";
								if(!$this->FormaDatosSolicitud()){
												return false;
								}
								return true;
						}

						/*if($_REQUEST['Fecha'] > date('d/m/Y'))
						{
										$this->frmError["Fecha"]=1;
										$this->frmError["MensajeError"]="La Fecha debe ser anterior a la actual.";
										if(!$this->FormaDatosSolicitud()){
														return false;
										}
										return true;
						}*/

						$_SESSION['OS_PACIENTES']['DATOS']['MEDICO']=$_REQUEST['Medico'];
						$f=explode('/',$_REQUEST['Fecha']);
						$Fecha=$f[2].'-'.$f[1].'-'.$f[0];
						$_SESSION['OS_PACIENTES']['DATOS']['FECHA']=$Fecha;
						$_SESSION['OS_PACIENTES']['DATOS']['ENTIDAD']=$_REQUEST['Origen'];
						$_SESSION['OS_PACIENTES']['DATOS']['SERVICIO']=$_REQUEST['Serv'];
						$_SESSION['OS_PACIENTES']['DATOS']['OBSERVACION']=$_REQUEST['Observacion'];
						$_SESSION['OS_PACIENTES']['SERVICIO']=$_REQUEST['Serv'];

						$_SESSION['OS_PACIENTES']['DATOS']['CAMA']=$_REQUEST['cama'];

						if(!empty($_REQUEST['MedInt']))
						{
								$_SESSION['OS_PACIENTES']['DATOS']['MEDICO']=$_REQUEST['MedInt'];
						}

						if(!empty($_REQUEST['departamento']))
						{
								$_SESSION['OS_PACIENTES']['DATOS']['DEPARTAMENTO']=$_REQUEST['departamento'];
						}

						$this->Apoyos();
						return true;
		}

	function Apoyos()
	{
					$this->frmForma();
					return true;
	}

		/**
		*
		*/
		function BuscarDatosTmp()
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT a.*, b.*
									FROM tmp_solicitud_manual as a, tmp_solicitud_manual_detalle as b
									WHERE a.codigo=".$_SESSION['OS_PACIENTES']['SERIAL']."
									AND a.tipo_id_paciente='".$_SESSION['DATOS_PACIENTE']['tipo_id']."'
									AND a.paciente_id='".$_SESSION['DATOS_PACIENTE']['paciente_id']."'
									AND a.tmp_solicitud_manual_id=b.tmp_solicitud_manual_id
									AND a.usuario_id=".UserGetUID()."
									order by a.cargo_cups";
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
		function LlamarFormaBuscar()
		{
				unset($_SESSION['DATOS_PACIENTE']['PACIENTE']);
				unset($_SESSION['DATOS_PACIENTE']['DATOS']);

				list($dbconn) = GetDBconn();
				$sql="select nextval('asignacuentavirtual_seq')";
				$result = $dbconn->Execute($sql);
				$dato=$result->fields[0];
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				$_SESSION['OS_PACIENTES']['SERIAL']=$dato;

				if(!$this->FormaBuscar()){
						return false;
				}
				return true;
		}

		/**
		*
		*/
		function Busqueda_Avanzada()
		{
					list($dbconn) = GetDBconn();
					$opcion      = ($_REQUEST['criterio1apoyo']);
					$cargo       = ($_REQUEST['cargoapoyo']);
					$descripcion =STRTOUPPER($_REQUEST['descripcionapoyo']);

					$filtroTipoCargo = '';
					$busqueda1 = '';
					$busqueda2 = '';

					if ($cargo != '')
					{
							$busqueda1 =" AND a.cargo LIKE '$cargo%'";
					}

					if ($descripcion != '')
					{
							$busqueda2 ="AND a.descripcion LIKE '%$descripcion%'";
					}

					if(empty($_REQUEST['conteoapoyo']))
					{
							if($opcion == '002')
							{		//frecuentes
									$query = "SELECT count(a.cargo)
									FROM cups a,apoyod_tipos b, departamentos_cargos as c, apoyod_solicitud_frecuencia as d
									WHERE a.cargo = d.cargo and d.departamento='".$_SESSION['OS_PACIENTES']['DPTO']."'
									and a.grupo_tipo_cargo = b.apoyod_tipo_id
									and a.cargo=c.cargo and c.departamento='".$_SESSION['OS_PACIENTES']['DPTO']."'
									$dpto $espe $busqueda1 $busqueda2";
							}
							else
							{
									$query = "SELECT count(a.cargo)
									FROM cups a,apoyod_tipos b, departamentos_cargos as c
									WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
									and a.cargo=c.cargo and c.departamento='".$_SESSION['OS_PACIENTES']['DPTO']."'
									$filtroTipoCargo    $busqueda1 $busqueda2";
							}
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							list($this->conteo)=$resulta->FetchRow();
					}
					else
					{
							$this->conteo=$_REQUEST['conteoapoyo'];
							$_SESSION['SPY']=$this->conteo;
					}
					if(!$_REQUEST['Ofapoyo'])
					{
							$Of='0';
					}
					else
					{
							$Of=$_REQUEST['Ofapoyo'];
							if($Of > $this->conteo)
							{
									$Of=0;
									$_REQUEST['Ofapoyo']=0;
									$_REQUEST['paso1apoyo']=1;
							}
					}
					if($opcion == '002')
					{
							$query = "SELECT DISTINCT a.cargo, a.descripcion, b.apoyod_tipo_id, b.descripcion as tipo
							FROM cups a,apoyod_tipos b, departamentos_cargos as c, apoyod_solicitud_frecuencia as d
							WHERE a.cargo = d.cargo and  d.departamento='".$_SESSION['OS_PACIENTES']['DPTO']."'
							and a.grupo_tipo_cargo = b.apoyod_tipo_id
							and a.cargo=c.cargo and c.departamento='".$_SESSION['OS_PACIENTES']['DPTO']."'
							$dpto $espe $busqueda1 $busqueda2
							order by a.descripcion, a.cargo
							LIMIT ".$this->limit." OFFSET $Of;";
					}
					else
					{
							$query = "SELECT a.cargo, a.descripcion, b.apoyod_tipo_id, b.descripcion as tipo
							FROM cups a,apoyod_tipos b, departamentos_cargos as c
							WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
							and a.cargo=c.cargo and c.departamento='".$_SESSION['OS_PACIENTES']['DPTO']."'
							$filtroTipoCargo    $busqueda1 $busqueda2
							order by b.apoyod_tipo_id, a.cargo
							LIMIT ".$this->limit." OFFSET $Of;";
					}
					$resulta = $dbconn->Execute($query);

					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
                                        //$this->conteo=$resulta->RecordCount();
					$i=0;
					while(!$resulta->EOF)
					{
							$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
							$resulta->MoveNext();
							$i++;
					}

					if($this->conteo==='0')
					{
									$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
					}

					$this->frmForma($var);
					return true;
		}

		/**
		*
		*/
		function GuardarApoyo()
		{
				IncludeLib("malla_validadora");
				$x=ValidarCargoMalla($_REQUEST['cargo'],$_SESSION['DATOS_PACIENTE']['plan_id'],$_SESSION['OS_PACIENTES']['SERVICIO']);
				//pasa la malla
				if(is_numeric($x))
				{
						//varias equivalencias
						if($x==2)
						{
								$this->FormaVariasEquivalencias();
								return true;
						}
				}
				elseif(is_array($x))
				{		//paso la malla
						//un vector [] tarifario cargo descricpion cantidad
						$vector[0]=array('tarifario'=>$x[tarifario],'cargo'=>$x[cargo],'descripcion'=>$x[descripcion],'cantidad'=>1);
						$insertar=$this->InsertarTmp($_REQUEST['cargo'],$_REQUEST['apoyod_tipo_id'],$vector);
						if(!empty($insertar))
						{		$this->frmError["MensajeError"]="SOLICITUD GUARDADA";  }
						else
						{		$this->frmError["MensajeError"]="ERROR EN LA INSERCCION";  }

						$this->frmForma('');
						return true;
				}
				else
				{		//se quedo en la malla
						$this->frmError["MensajeError"]="NO SE PUEDE SOLICITAR PORQUE: $x";
						$this->frmForma('');
						return true;
				}
		}


		/**
		*
		*/
		function GuardarEquivalencias()
		{
				$f=0;
				foreach($_REQUEST as $k => $v)
				{
						if(substr_count($k,'Equi'))
						{  $f++;  }
				}

				if($f==0)
				{
						$this->frmError["MensajeError"]="DEBE ELEGIR AL MENOS UNA EQUIVALENCIA";
						$this->FormaVariasEquivalencias();
						return true;
				}

				$vector='';
				foreach($_REQUEST as $k => $v)
				{
						if(substr_count($k,'Equi'))
						{
								//0 tarifario 1cargo 2 descripcion 3 cantidad
								$dat=explode('//',$v);
								$vector[]=array('tarifario'=>$dat[0],'cargo'=>$dat[1],'descripcion'=>$dat[2],'cantidad'=>1);
						}
				}

				$insertar=$this->InsertarTmp($_REQUEST['cups'],$_REQUEST['apoyod_tipo_id'],$vector);
				if(!empty($insertar))
				{		$this->frmError["MensajeError"]="SOLICITUD GUARDADA";  }
				else
				{		$this->frmError["MensajeError"]="ERROR EN LA INSERCCION";  }

				$this->frmForma('');
				return true;
		}

	/**
	*
	*/
	function EliminarCargo()
	{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();

			$query =" DELETE FROM tmp_solicitud_manual_detalle WHERE tmp_solicitud_manual_id=".$_REQUEST['id']."
								AND	tmp_solicitud_manual_detalle_id=".$_REQUEST['idDetalle']."";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error DELETE FROM tmp_solicitud_manual_detalle";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
			}

			$query =" SELECT * FROM tmp_solicitud_manual_detalle WHERE tmp_solicitud_manual_id=".$_REQUEST['id']."";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error SELECT";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			if($result->EOF)
			{
					$query =" DELETE FROM tmp_solicitud_manual WHERE tmp_solicitud_manual_id=".$_REQUEST['id']."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error DELETE FROM tmp_solicitud_manual";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
					}
			}

			$dbconn->CommitTrans();
			$this->frmError["MensajeError"]="SE ELIMINO";

			$this->frmForma('');
			return true;
	}

		/**
		*
		*/
		function InsertarTmp($cups,$tipo,$vector)
		{
						list($dbconn) = GetDBconn();
						$dbconn->BeginTrans();

						$query=" SELECT nextval('tmp_solicitud_manual_tmp_solicitud_manual_id_seq')";
						$result=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error INSERT INTO cuentas_detalle ";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}
						$id=$result->fields[0];

						$query = "INSERT INTO tmp_solicitud_manual (
													tmp_solicitud_manual_id,
													codigo,
													tipo_id_paciente,
													paciente_id,
													apoyod_tipo_id,
													cargo_cups,
													fecha_registro,
													usuario_id)
											VALUES ($id,".$_SESSION['OS_PACIENTES']['SERIAL'].",'".$_SESSION['DATOS_PACIENTE']['tipo_id']."','".$_SESSION['DATOS_PACIENTE']['paciente_id']."','$tipo','$cups','now()',".UserGetUID().")";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error INSERT INTO cuentas_detalle ";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}

						for($i=0; $i<sizeof($vector); $i++)
						{
								$query = "INSERT INTO tmp_solicitud_manual_detalle (
															tmp_solicitud_manual_id,
															tarifario_id,
															cargo,
															descripcion,
															cantidad)
													VALUES ($id,'".$vector[$i]['tarifario']."','".$vector[$i]['cargo']."','".$vector[$i]['descripcion']."',".$vector[$i]['cantidad'].")";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error INSERT INTO cuentas_detalle ";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
								}
						}
						$dbconn->CommitTrans();
						return true;
		}

	/**
	*
	*/
	function CrearOs()
	{
				list($dbconn) = GetDBconn();
				$query = "SELECT a.*
									FROM tmp_solicitud_manual as a
									WHERE a.codigo=".$_SESSION['OS_PACIENTES']['SERIAL']."
									AND a.tipo_id_paciente='".$_SESSION['DATOS_PACIENTE']['tipo_id']."'
									AND a.paciente_id='".$_SESSION['DATOS_PACIENTE']['paciente_id']."'
									AND a.usuario_id=".UserGetUID()."
									order by a.cargo_cups";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while (!$result->EOF)
				{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
				$result->Close();


				$query="SELECT nextval('os_ordenes_servicios_orden_servicio_id_seq')";
				$result=$dbconn->Execute($query);
				$orden=$result->fields[0];
				$query = "INSERT INTO os_ordenes_servicios
															(orden_servicio_id,
															autorizacion_int,
															autorizacion_ext,
															plan_id,
															tipo_afiliado_id,
															rango,
															semanas_cotizadas,
															servicio,
															tipo_id_paciente,
															paciente_id,
															usuario_id,
															fecha_registro,
															observacion)
				VALUES($orden,1,NULL,".$_SESSION['DATOS_PACIENTE']['plan_id'].",'".$_SESSION['DATOS_PACIENTE']['PACIENTE']['tipo_afiliado_id']."',
				'".$_SESSION['DATOS_PACIENTE']['PACIENTE']['rango']."',".$_SESSION['DATOS_PACIENTE']['PACIENTE']['semanas'].",
				'".$_SESSION['OS_PACIENTES']['SERVICIO']."','".$_SESSION['DATOS_PACIENTE']['tipo_id']."','".$_SESSION['DATOS_PACIENTE']['paciente_id']."',
				".UserGetUID().",'now()','')";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error INSERT INTO os_ordenes_servicios";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}

				$query3="INSERT INTO hc_os_solicitudes_manuales_datos_adicionales(
																	orden_servicio_id,
																	cama,
																	departamento)
						VALUES($orden,'".$_SESSION['DATOS_PACIENTE']['DATOS']['CAMA']."','".$_SESSION['DATOS_PACIENTE']['DATOS']['DEPARTAMENTO']."')";
				$dbconn->Execute($query3);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al insertar en hc_os_solicitudes_manuales";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}

				$query="SELECT secuencia_os_cumplimiento('".$_SESSION['OS_PACIENTES']['DPTO']."')";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
							$this->error = "Error al insertar en cuentas_detalle";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
				}
				$serial=$result->fields[0];//generamos el numero de cumplimiento

				$query = "INSERT INTO os_cumplimientos(
																numero_cumplimiento,
																fecha_cumplimiento,
																departamento,
																tipo_id_paciente,
																paciente_id)
									VALUES(".$serial.",'".date("Y-m-d")."','".$_SESSION['OS_PACIENTES']['DPTO']."','".$_SESSION['DATOS_PACIENTE']['tipo_id']."','".$_SESSION['DATOS_PACIENTE']['paciente_id']."')";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
				}

				for($i=0; $i<sizeof($var); $i++)
				{
						$query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
						$result=$dbconn->Execute($query1);
						$hc_os_solicitud_id=$result->fields[0];

						$query2="INSERT INTO hc_os_solicitudes
														(hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id)
										VALUES($hc_os_solicitud_id,NULL,'".$var[$i][cargo_cups]."', 'APD',
										".$_SESSION['DATOS_PACIENTE']['plan_id'].")";
						$dbconn->Execute($query2);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error al insertar en hc_os_solicitudes";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}

						$query3="INSERT INTO hc_os_solicitudes_apoyod
														(hc_os_solicitud_id, apoyod_tipo_id)
										VALUES($hc_os_solicitud_id, '".$var[$i][apoyod_tipo_id]."');";
						$dbconn->Execute($query3);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error al insertar en hc_os_solicitudes_apoyod";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}

						$query3="INSERT INTO hc_os_solicitudes_manuales(
											hc_os_solicitud_id,fecha,
											servicio,profesional,prestador,observaciones,
											tipo_id_paciente,paciente_id,fecha_resgistro,
											usuario_id,empresa_id,tipo_afiliado_id,rango,semanas_cotizadas)
								VALUES($hc_os_solicitud_id, '".$_SESSION['OS_PACIENTES']['DATOS']['FECHA']."',
								'".$_SESSION['OS_PACIENTES']['SERVICIO']."','".$_SESSION['OS_PACIENTES']['DATOS']['MEDICO']."',
								'".$_SESSION['OS_PACIENTES']['DATOS']['ENTIDAD']."','".$_SESSION['OS_PACIENTES']['DATOS']['OBSERVACION']."',
								'".$_SESSION['DATOS_PACIENTE']['tipo_id']."','".$_SESSION['DATOS_PACIENTE']['paciente_id']."',
								'now()',".UserGetUID().",'".$_SESSION['OS_PACIENTES']['EMPRESA_ID']."',
								'".$_SESSION['DATOS_PACIENTE']['PACIENTE']['tipo_afiliado_id']."','".$_SESSION['DATOS_PACIENTE']['PACIENTE']['rango']."',".$_SESSION['DATOS_PACIENTE']['PACIENTE']['semanas'].");";
						$dbconn->Execute($query3);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error al insertar en hc_os_solicitudes_manuales";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}

						$plan=$_SESSION['DATOS_PACIENTE']['plan_id'];
						$query = "select * from os_tipos_periodos_planes
											where plan_id=".$plan." and cargo='".$var[$i][cargo_cups]."'";
						$result=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														//echo "salida 2 <br>";
														return false;
						}
						if(!$result->EOF)
						{
								$vars=$result->GetRowAssoc($ToUpper = false);
								$Fecha=$this->FechaStamp($fecha_solicitud);
								$infoCadena = explode ('/',$Fecha);
								$intervalo=$this->HoraStamp($fecha_solicitud);
								$infoCadena1 = explode (':', $intervalo);
								$fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
								if($fechaAct < date("Y-m-d H:i:s"))
								{  $fechaAct=date("Y-m-d H:i:s");  }
								$Fecha=$this->FechaStamp($fechaAct);
								$infoCadena = explode ('/',$Fecha);
								$intervalo=$this->HoraStamp($fechaAct);
								$infoCadena1 = explode (':', $intervalo);
								$venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
								//fecha refrendar
								$Fecha=$this->FechaStamp($venc);
								$infoCadena = explode ('/',$Fecha);
								$intervalo=$this->HoraStamp($venc);
								$infoCadena1 = explode (':', $intervalo);
								$refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
						}
						else
						{                //si no hay unos tiempos especificos para el cargo toma los genericos
								$query = "select * from os_tipos_periodos_tramites
													where cargo='".$var[$i][cargo_cups]."'";
								$result=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
								      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																//echo "salida 3 <br>";
																return false;
								}
								if(!$result->EOF)
								{
										$vars=$result->GetRowAssoc($ToUpper = false);
										$Fecha=$this->FechaStamp($fecha_solicitud);
										$infoCadena = explode ('/',$Fecha);
										$intervalo=$this->HoraStamp($fecha_solicitud);
										$infoCadena1 = explode (':', $intervalo);
										$fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
										if($fechaAct < date("Y-m-d H:i:s"))
										{  $fechaAct=date("Y-m-d H:i:s");  }
										$Fecha=$this->FechaStamp($fechaAct);
										$infoCadena = explode ('/',$Fecha);
										$intervalo=$this->HoraStamp($fechaAct);
										$infoCadena1 = explode (':', $intervalo);
										$venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
										//fecha refrendar
										$Fecha=$this->FechaStamp($venc);
										$infoCadena = explode ('/',$Fecha);
										$intervalo=$this->HoraStamp($venc);
										$infoCadena1 = explode (':', $intervalo);
										$refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
								}
								else
								{
										$tramite=ModuloGetVar('app','CentroAutorizacion','dias_tramite_os');
										$vigencia=ModuloGetVar('app','CentroAutorizacion','dias_vigencia');
										$vars=$result->GetRowAssoc($ToUpper = false);
										$Fecha=$this->FechaStamp($fecha_solicitud);
										$infoCadena = explode ('/',$Fecha);
										$intervalo=$this->HoraStamp($fecha_solicitud);
										$infoCadena1 = explode (':', $intervalo);
										$fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$tramite),$infoCadena[2]));
										if($fechaAct < date("Y-m-d H:i:s"))
										{  $fechaAct=date("Y-m-d H:i:s");  }
										$Fecha=$this->FechaStamp($fechaAct);
										$infoCadena = explode ('/',$Fecha);
										$intervalo=$this->HoraStamp($fechaAct);
										$infoCadena1 = explode (':', $intervalo);
										$venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$vigencia),$infoCadena[2]));
										//fecha refrendar
										$Fecha=$this->FechaStamp($venc);
										$infoCadena = explode ('/',$Fecha);
										$intervalo=$this->HoraStamp($venc);
										$infoCadena1 = explode (':', $intervalo);
										$refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
								}

								$query="SELECT nextval('os_maestro_numero_orden_id_seq')";
								$result=$dbconn->Execute($query);
								$numorden=$result->fields[0];

								$query = "INSERT INTO os_maestro
																		(numero_orden_id,
																		orden_servicio_id,
																		sw_estado,
																		fecha_vencimiento,
																		hc_os_solicitud_id,
																		fecha_activacion,
																		cantidad,
																		cargo_cups,
																		fecha_refrendar)
								VALUES($numorden,$orden,1,'$venc',".$hc_os_solicitud_id.",'$fechaAct',1,'".$var[$i][cargo_cups]."','$refrendar')";
								$result=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollbackTrans();
												return false;
								}
								//insertar en hc_os_autorizaciones para que le aparezca a claudia
								$query = "INSERT INTO hc_os_autorizaciones
																				(autorizacion_int,autorizacion_ext,
																				hc_os_solicitud_id)
																		VALUES(1,1,'".$hc_os_solicitud_id."')";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																$dbconn->RollbackTrans();
																//echo "salida 5 <br>";
																return false;
								}

								$query = "SELECT a.*, b.*
													FROM tmp_solicitud_manual as a, tmp_solicitud_manual_detalle as b
													WHERE a.codigo=".$_SESSION['OS_PACIENTES']['SERIAL']."
													AND a.tipo_id_paciente='".$_SESSION['DATOS_PACIENTE']['tipo_id']."'
													AND a.paciente_id='".$_SESSION['DATOS_PACIENTE']['paciente_id']."'
													AND a.tmp_solicitud_manual_id=b.tmp_solicitud_manual_id
													AND a.usuario_id=".UserGetUID()." AND a.cargo_cups='".$var[$i][cargo_cups]."'
													order by a.cargo_cups";
								$result = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
								}
								while (!$result->EOF)
								{
									$arr[]=$result->GetRowAssoc($ToUpper = false);
									$result->MoveNext();
								}

								for($j=0; $j<sizeof($arr); $j++)
								{
											$query = "INSERT INTO os_maestro_cargos
																				(numero_orden_id,
																				tarifario_id,
																				cargo)
																VALUES($numorden,'".$arr[$j][tarifario_id]."','".$arr[$j][cargo]."')";
											$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
												   					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																		$dbconn->RollbackTrans();
																		//echo "salida 6<br>";
																		return false;
											}
								}//fin for os
								$query = "INSERT INTO os_internas(numero_orden_id,
																										cargo,
																										departamento)
													VALUES($numorden,'".$var[$i][cargo_cups]."','".$_SESSION['OS_PACIENTES']['DPTO']."')";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																$dbconn->RollbackTrans();
																//echo "salida 7<br>";
																return false;
								}
								//actualiza a 0 para indicar que ya paso por el proceso de autorizacion
								$query = "UPDATE hc_os_solicitudes SET    sw_estado=0
													WHERE hc_os_solicitud_id=".$hc_os_solicitud_id."";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
																//echo "salida 8<br>";
																$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollbackTrans();
												return false;
								}
						}
				}
				$dbconn->CommitTrans();
				$this->frmError["MensajeError"] = 'LA ORDEN No. '.$orden.' FUE GENERADA.';
				$_SESSION['OS_PACIENTES']['ORDEN']=$orden;
				$this->FormaCumplimiento();
				return true;
	}

	function TraerOrdenesServicioAmb($TipoId,$PacienteId,$OS)
	{
			list($dbconn) = GetDBconn();
			$query="SELECT
					c.plan_id,c.plan_descripcion,a.servicio,h.descripcion as serv_des,
					sw_cargo_multidpto as switche,
					CASE c.sw_tipo_plan
					WHEN '0' THEN d.nombre_tercero
					WHEN '1' THEN 'SOAT'
					WHEN '2' THEN 'PARTICULAR'
					WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
					ELSE e.descripcion END,
					a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,
					i.fecha_vencimiento, f.cargo as cargoi,g.descripcion as des1,i.cantidad,
					a.autorizacion_int,a.autorizacion_ext,a.observacion,
					k.tipo_afiliado_nombre,h.sw_cargo_multidpto
					FROM os_ordenes_servicios as a, pacientes as b, planes c,
					terceros d, tipos_planes as e, os_internas as f, cups g,
					servicios h,os_maestro i, tipos_afiliado k
					WHERE a.orden_servicio_id=$OS
					AND a.orden_servicio_id=i.orden_servicio_id
					AND i.numero_orden_id=f.numero_orden_id
					AND a.tipo_id_paciente=b.tipo_id_paciente
					AND a.paciente_id=b.paciente_id
					AND a.tipo_id_paciente='$TipoId'
					AND a.paciente_id='$PacienteId'
					AND a.servicio=h.servicio
					AND g.cargo=f.cargo
					AND c.plan_id=a.plan_id
					AND e.sw_tipo_plan=c.sw_tipo_plan
					AND c.tercero_id=d.tercero_id
					AND c.tipo_tercero_id=d.tipo_id_tercero
 					AND f.departamento='".$_SESSION['OS_PACIENTES']['DPTO']."'
					AND i.sw_estado='1'
					AND a.tipo_afiliado_id=k.tipo_afiliado_id
					AND DATE(i.fecha_activacion) <= NOW()
					AND DATE(i.fecha_vencimiento) >= NOW()
					ORDER BY c.plan_id,i.fecha_vencimiento,a.orden_servicio_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al traer las 0rdenes de servicios";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
			}
			$result->Close();
			return $var;
	}


	function Edad($TipoId,$PacienteId)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT fecha_nacimiento FROM pacientes WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
			$result = $dbconn->Execute($query);

					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					else{

							if($result->EOF){
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "La tabla 'paciente' esta vacia ";
									return false;
							}
					}
			$result->Close();
			$FechaNacimiento=$result->fields[0];
			return $FechaNacimiento;
	}
//-----------------------------------FIN SOLICITUD MANUAL	DARLING-------------------------

}

?>
