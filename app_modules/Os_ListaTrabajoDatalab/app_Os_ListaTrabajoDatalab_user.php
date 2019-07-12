<?php
/**
* Modulo de Listas de Trabajo para DATALAB(PHP).
*
* Modulo para el manejo de listas de trabajo en Interface con DATALAB
*
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: app_Os_ListaTrabajoDatalab_user.php,v 1.2 2005/03/11 22:42:12 claudia Exp $
*/

class app_Os_ListaTrabajoDatalab_user extends classModulo
{
	var $limit;
	var $conteo;//para saber cuantos registros encontró

	function app_Os_ListaTrabajoDatalab_user()
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
			$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
			$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
		  $_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
			$_REQUEST['nombre'], $_REQUEST['edad_paciente']);
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
			      $query="SELECT b.sw_mostrar_listas, c.departamento,c.descripcion as dpto,
										d.descripcion as    centro, e.empresa_id,e.razon_social as emp,
										d.centro_utilidad,    b.usuario_id
										FROM userpermisos_os_lista_trabajo_interface_datalab b, departamentos c,
										centros_utilidad d,
										empresas e    WHERE    b.usuario_id=".UserGetUID()."
										AND    c.departamento=b.departamento
										AND    d.centro_utilidad=c.centro_utilidad    AND
										e.empresa_id=d.empresa_id
										AND    e.empresa_id=c.empresa_id    ORDER BY centro";
  					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						$resulta = $dbconn->Execute($query);
						while($data = $resulta->FetchRow())
						{
								$laboratorio[$data['emp']][$data['centro']][$data['dpto']]=$data;
						}
						$url[0]='app';
						$url[1]='Os_ListaTrabajoDatalab';
						$url[2]='user';
						$url[3]='Menuatencion';
						$url[4]='ListaTrabajoDatalab';

						$arreglo[0]='EMPRESA';
						$arreglo[1]='CENTRO UTILIDAD';
						$arreglo[2]='ATENCION DE LISTA DE TRABAJO';

						$this->salida.= gui_theme_menu_acceso('ATENCION DE LISTA DE TRABAJO CON DATALAB',$arreglo,$laboratorio,$url);
						return true;
		}


		/**
		* Menuatencion, esta funcion  es donde desemboca el menu iteractivo creado por arley.
		* @access public
		* @return boolean
		*/
function Menuatencion()
{
	//if(empty($_SESSION['LABORATORIO']['EMPRESA_ID']))
	//{
			$_SESSION['LTRABAJO_DATALAB']['EMPRESA_ID']=$_REQUEST['ListaTrabajoDatalab']['empresa_id'];
			$_SESSION['LTRABAJO_DATALAB']['CENTROUTILIDAD']=$_REQUEST['ListaTrabajoDatalab']['centro_utilidad'];
			$_SESSION['LTRABAJO_DATALAB']['NOM_CENTRO']=$_REQUEST['ListaTrabajoDatalab']['centro'];
			$_SESSION['LTRABAJO_DATALAB']['NOM_EMP']=$_REQUEST['ListaTrabajoDatalab']['emp'];
			$_SESSION['LTRABAJO_DATALAB']['NOM_DPTO']=$_REQUEST['ListaTrabajoDatalab']['dpto'];
			$_SESSION['LTRABAJO_DATALAB']['DPTO']=$_REQUEST['ListaTrabajoDatalab']['departamento'];
			$_SESSION['LTRABAJO_DATALAB']['MOSTRAR_LISTAS']=$_REQUEST['ListaTrabajoDatalab']['sw_mostrar_listas'];
	//}
		unset ($_SESSION['IMAGENES']['LISTAS']);
			if(!$this->FormaMetodoBuscar())
			{
					return false;
		}
			return true;
}


//************************la funcion para clasificar el dpto de imagenes
//funcion que hace que el usuario de imagenologia pueda ver la clasificacion
//de las listas.
function GetListasTrabajo()
{
	list($dbconn) = GetDBconn();
	$query="SELECT f.nombre_lista, f.tipo_os_lista_id, c.departamento, c.descripcion
					as dpto, d.descripcion as centro, e.empresa_id, e.razon_social as emp,
					d.centro_utilidad, a.usuario_id
					FROM userpermisos_os_lista_trabajo_interface_datalab a,
					userpermisos_os_lista_trabajo_interface_datalab_detalle b, departamentos c,
					centros_utilidad d,
					empresas e, tipos_os_listas_trabajo f WHERE a.usuario_id = b.usuario_id
					AND a.departamento = b.departamento AND b.tipo_os_lista_id = f.tipo_os_lista_id
					AND b.departamento = f.departamento AND a.usuario_id=".UserGetUID()."
					AND a.departamento=c.departamento    AND c.centro_utilidad=d.centro_utilidad
					AND d.empresa_id=e.empresa_id    AND e.empresa_id=c.empresa_id
					AND a.departamento = '".$_SESSION['LTRABAJO_DATALAB']['DPTO']."'
					ORDER BY centro,b.tipo_os_lista_id";
	$result = $dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0)
	{
			$this->error = "Error al consultar las listas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
	}
	while(!$result->EOF)
	{
			$var[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
	}
	return $var;
}
//************************fin


		/**
		* Realiza la busqueda según el plan,documento .. de los pacientes que
		* tienen ordenes de servicios pendientes
		* @access private
		* @return boolean
		*/
function BuscarOrden()
{
	if ($_REQUEST['Buscar_Orden_Cargar_Session'] != '')
	{
		if ($_SESSION['LTRABAJO_DATALAB']['MOSTRAR_LISTAS'] == '1')
		{
			if (empty($_REQUEST['op']))
			{
				$this->frmError["MensajeError"]="DEBE SELECCIONAR UN TIPO DE LISTA PARA LA PROGRAMACION";
				$this->FormaMetodoBuscar($var);
				return true;
			}
		}

		$tipo_documento=$_REQUEST['TipoDocumento'];
		$documento=$_REQUEST['Documento'];
		$nombres = strtolower($_REQUEST['Nombres']);
		$fecha=$_REQUEST['Fecha'];
		$Mfecha = $fecha;
		if (empty($fecha))
		{
				$fecha = date("Y-m-d");
				$Mfecha = $fecha;
		}
		elseif($fecha=='TODAS LAS FECHAS')
		{
				$fecha = '';
		}


		//filtro adicional de pacientes
    $opcion_pacientes=$_REQUEST['opcion_pacientes'];

		list($dbconn) = GetDBconn();
		$filtroTipoDocumento = '';
		$filtroDocumento='';
		$filtroNombres='';
		$filtroFecha='';
		$filtroPacientes='';

    if((!empty($tipo_documento)) AND ($tipo_documento != -1))		
		{
				$filtroTipoDocumento=" AND a.tipo_id_paciente = '$tipo_documento'";
		}

		if ($documento != '')
		{
				$filtroDocumento =" AND a.paciente_id LIKE '$documento%'";
		}

		if ($nombres != '')
		{
				$a=explode(' ',$nombres);
				foreach($a as $k=>$v)
				{
						if(!empty($v))
						{
							$filtroNombres.=" and (upper(b.primer_nombre||' '||b.segundo_nombre||' '||
																b.primer_apellido||' '||b.segundo_apellido) like '%".strtoupper($v)."%')";
						}
				}
		}

		if ($fecha != '')
		{
				$filtroFecha ="AND date(a.fecha_cumplimiento) = date('$fecha')";
		}


		if ($opcion_pacientes == 1)  //PACIENTES SIN ATENDER
		{
				$filtroPacientes ="AND c.sw_estado = '0'";
		}
		elseif ($opcion_pacientes == 2)//PACIENTES ATENDIDOS
		{
        $filtroPacientes ="AND c.sw_estado = '1'";
		}
    elseif ($opcion_pacientes == 3)//TODOS LOS PACIENTES
		{
        $filtroPacientes ='';
		}

  }
	else
	{
			list($dbconn) = GetDBconn();
			$filtroTipoDocumento = $_SESSION['BUSQUEDA_ORDEN']['filtroTipoDocumento'];
			$filtroDocumento = $_SESSION['BUSQUEDA_ORDEN']['filtroDocumento'];
			$filtroNombres = $_SESSION['BUSQUEDA_ORDEN']['filtroNombres'];
			$filtroFecha = $_SESSION['BUSQUEDA_ORDEN']['filtroFecha'];
			$filtroPacientes = $_SESSION['BUSQUEDA_ORDEN']['filtroPacientes'];
			$_REQUEST['op'] = $_SESSION['BUSQUEDA_ORDEN']['listas'];
	}

	if(empty($_REQUEST['conteo'.$pfj]))
	{
			if ($_SESSION['LTRABAJO_DATALAB']['MOSTRAR_LISTAS'] == '1')
			{
          //caso para imagenologia
					unset($query);
					unset($_SESSION['LISTAS_SELECCIONADAS']);
					$_SESSION['LISTAS_SELECCIONADAS']=$_REQUEST['op'];
					foreach($_REQUEST['op'] as $index=>$codigo)
					{
							$arreglo=explode(",",$codigo);
							if($query=='')
							{//g.nombre_lista
						      $query = "SELECT count(*) from ((SELECT DISTINCT
									b.fecha_nacimiento, f.descripcion as servicio_descripcion,
									a.numero_cumplimiento, a.departamento, a.tipo_id_paciente, a.paciente_id,
									a.fecha_cumplimiento, btrim(b.primer_nombre||' '||b.segundo_nombre||' '||
									b.primer_apellido|| ' '||b.segundo_apellido,'') as nombre
									FROM os_cumplimientos a, os_cumplimientos_detalle c, pacientes b,
									os_maestro d, os_ordenes_servicios e, servicios f,
									tipos_os_listas_trabajo g, tipos_os_listas_trabajo_detalle h, cups i,
									os_internas j
									WHERE a.paciente_id = b.paciente_id AND a.tipo_id_paciente =
									b.tipo_id_paciente AND a.departamento = '".$_SESSION['LTRABAJO_DATALAB']['DPTO']."'
									AND a.numero_cumplimiento = c.numero_cumplimiento
									AND a.fecha_cumplimiento = c.fecha_cumplimiento
									AND a.departamento = c.departamento AND
									c.numero_orden_id = d.numero_orden_id
									AND d.orden_servicio_id = e.orden_servicio_id AND
									e.servicio = f.servicio and c.numero_orden_id = j.numero_orden_id
									AND h.tipo_os_lista_id ='".$arreglo[0]."'
									AND h.grupo_tipo_cargo = i.grupo_tipo_cargo
									AND h.tipo_cargo = i.tipo_cargo AND j.cargo=i.cargo
									AND g.tipo_os_lista_id = h.tipo_os_lista_id
									$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroFecha $filtroPacientes)";
							}
							else
							{
									$query.="union (SELECT DISTINCT
									b.fecha_nacimiento,f.descripcion as servicio_descripcion,
									a.numero_cumplimiento, a.departamento, a.tipo_id_paciente, a.paciente_id,
									a.fecha_cumplimiento, btrim(b.primer_nombre||' '||b.segundo_nombre||' '||
									b.primer_apellido|| ' '||b.segundo_apellido,'') as nombre
									FROM os_cumplimientos a, os_cumplimientos_detalle c, pacientes b,
									os_maestro d, os_ordenes_servicios e, servicios f,
									tipos_os_listas_trabajo g, tipos_os_listas_trabajo_detalle h, cups i,
									os_internas j
                  WHERE a.paciente_id = b.paciente_id AND a.tipo_id_paciente =
									b.tipo_id_paciente AND a.departamento = '".$_SESSION['LTRABAJO_DATALAB']['DPTO']."'
									AND a.numero_cumplimiento = c.numero_cumplimiento
									AND a.fecha_cumplimiento = c.fecha_cumplimiento
									AND a.departamento = c.departamento AND
									c.numero_orden_id = d.numero_orden_id
									AND d.orden_servicio_id = e.orden_servicio_id AND
									e.servicio = f.servicio and c.numero_orden_id = j.numero_orden_id
									AND h.tipo_os_lista_id ='".$arreglo[0]."'
									AND h.grupo_tipo_cargo = i.grupo_tipo_cargo
									AND h.tipo_cargo = i.tipo_cargo AND j.cargo=i.cargo
									AND g.tipo_os_lista_id = h.tipo_os_lista_id
									$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroFecha $filtroPacientes
									)";
							}
					}
					$query.=")as A";
			}
			else
			{
					$query = "SELECT count(*)
					from (SELECT DISTINCT b.fecha_nacimiento, f.descripcion as servicio_descripcion, a.numero_cumplimiento,
					a.departamento, a.tipo_id_paciente, a.paciente_id, a.fecha_cumplimiento,
					btrim(b.primer_nombre||' '||b.segundo_nombre||' '||
					b.primer_apellido|| ' '||b.segundo_apellido,'') as nombre

					FROM os_cumplimientos as a left join os_cumplimientos_detalle as c on
					(a.numero_cumplimiento = c.numero_cumplimiento AND a.fecha_cumplimiento =
					c.fecha_cumplimiento AND a.departamento = c.departamento),
					pacientes b, os_maestro as d, os_ordenes_servicios as e, servicios as f

					WHERE a.paciente_id = b.paciente_id
					AND a.tipo_id_paciente = b.tipo_id_paciente

					AND a.departamento = '".$_SESSION['LTRABAJO_DATALAB']['DPTO']."'

					AND c.numero_orden_id = d.numero_orden_id AND d.orden_servicio_id =
					e.orden_servicio_id AND e.servicio = f.servicio

					$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroFecha $filtroPacientes) as A";
			}


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

	if ($_SESSION['LTRABAJO_DATALAB']['MOSTRAR_LISTAS'] == '1')
	{
			unset($query);
			foreach($_REQUEST['op'] as $index=>$codigo)
			{
					$arreglo=explode(",",$codigo);
					if($query=='')
					{
		          $query = "select * from ((SELECT DISTINCT
							b.fecha_nacimiento, f.descripcion as servicio_descripcion,
							a.numero_cumplimiento, a.departamento, a.tipo_id_paciente, a.paciente_id,
							a.fecha_cumplimiento, btrim(b.primer_nombre||' '||b.segundo_nombre||' '||
							b.primer_apellido|| ' '||b.segundo_apellido,'') as nombre
							FROM os_cumplimientos a, os_cumplimientos_detalle c, pacientes b,
							os_maestro d, os_ordenes_servicios e, servicios f,
							tipos_os_listas_trabajo g, tipos_os_listas_trabajo_detalle h, cups i,
							os_internas j
							WHERE a.paciente_id = b.paciente_id AND a.tipo_id_paciente =
							b.tipo_id_paciente AND a.departamento = '".$_SESSION['LTRABAJO_DATALAB']['DPTO']."'
							AND a.numero_cumplimiento = c.numero_cumplimiento
							AND a.fecha_cumplimiento = c.fecha_cumplimiento
							AND a.departamento = c.departamento AND
							c.numero_orden_id = d.numero_orden_id
							AND d.orden_servicio_id = e.orden_servicio_id AND
							e.servicio = f.servicio and c.numero_orden_id = j.numero_orden_id
							AND h.tipo_os_lista_id ='".$arreglo[0]."'
							AND h.grupo_tipo_cargo = i.grupo_tipo_cargo
							AND h.tipo_cargo = i.tipo_cargo AND j.cargo=i.cargo
							AND g.tipo_os_lista_id = h.tipo_os_lista_id
							$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroFecha $filtroPacientes
							)";
					}
					else
					{
							$query.=  "union (SELECT DISTINCT
							b.fecha_nacimiento, f.descripcion as servicio_descripcion,
							a.numero_cumplimiento, a.departamento, a.tipo_id_paciente, a.paciente_id,
							a.fecha_cumplimiento, btrim(b.primer_nombre||' '||b.segundo_nombre||' '||
							b.primer_apellido|| ' '||b.segundo_apellido,'') as nombre
							FROM os_cumplimientos a, os_cumplimientos_detalle c, pacientes b,
							os_maestro d, os_ordenes_servicios e, servicios f,
							tipos_os_listas_trabajo g, tipos_os_listas_trabajo_detalle h, cups i,
							os_internas j
							WHERE a.paciente_id = b.paciente_id AND a.tipo_id_paciente =
							b.tipo_id_paciente AND a.departamento = '".$_SESSION['LTRABAJO_DATALAB']['DPTO']."'
							AND a.numero_cumplimiento = c.numero_cumplimiento
							AND a.fecha_cumplimiento = c.fecha_cumplimiento
							AND a.departamento = c.departamento AND
							c.numero_orden_id = d.numero_orden_id
							AND d.orden_servicio_id = e.orden_servicio_id AND
							e.servicio = f.servicio and c.numero_orden_id = j.numero_orden_id
							AND h.tipo_os_lista_id ='".$arreglo[0]."'
							AND h.grupo_tipo_cargo = i.grupo_tipo_cargo
							AND h.tipo_cargo = i.tipo_cargo AND j.cargo=i.cargo
							AND g.tipo_os_lista_id = h.tipo_os_lista_id
							$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroFecha $filtroPacientes
							)";
					}
			}
			$query.=") as a order by a.fecha_cumplimiento, a.numero_cumplimiento LIMIT ".$this->limit." OFFSET $Of;";
	}
	else
	{
			$query = "
			SELECT DISTINCT b.fecha_nacimiento, f.descripcion as servicio_descripcion,
			a.numero_cumplimiento, a.departamento, a.tipo_id_paciente, a.paciente_id,
			a.fecha_cumplimiento, btrim(b.primer_nombre||' '||b.segundo_nombre||' '||
			b.primer_apellido|| ' '||b.segundo_apellido,'') as nombre

			FROM os_cumplimientos as a left join os_cumplimientos_detalle as c on
			(a.numero_cumplimiento = c.numero_cumplimiento AND a.fecha_cumplimiento =
			c.fecha_cumplimiento AND a.departamento = c.departamento),
			pacientes b, os_maestro as d, os_ordenes_servicios as e, servicios as f
			WHERE a.paciente_id = b.paciente_id
			AND a.tipo_id_paciente = b.tipo_id_paciente

			AND a.departamento = '".$_SESSION['LTRABAJO_DATALAB']['DPTO']."'

			AND c.numero_orden_id = d.numero_orden_id AND d.orden_servicio_id =
			e.orden_servicio_id AND e.servicio = f.servicio

			$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroFecha $filtroPacientes
			order by a.fecha_cumplimiento, a.numero_cumplimiento
		  LIMIT ".$this->limit." OFFSET $Of;";
	}


  if ($_REQUEST['Buscar_Orden_Cargar_Session'] != '')
  {
			unset ($_SESSION['BUSQUEDA_ORDEN']);
			$_SESSION['BUSQUEDA_ORDEN']['filtroTipoDocumento'] = $filtroTipoDocumento;
			$_SESSION['BUSQUEDA_ORDEN']['filtroDocumento'] = $filtroDocumento;
			$_SESSION['BUSQUEDA_ORDEN']['filtroNombres'] = $filtroNombres;
			$_SESSION['BUSQUEDA_ORDEN']['filtroFecha'] =  $filtroFecha;
			$_SESSION['BUSQUEDA_ORDEN']['filtroPacientes'] = $filtroPacientes;
			$_SESSION['BUSQUEDA_ORDEN']['listas']=$_REQUEST['op'];
  }

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
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO PARA $Mfecha";
			$this->FormaMetodoBuscar($var);
			return true;
	}
	$this->FormaMetodoBuscar($var);
	return true;
}


function ConsultaOrdenesPaciente($numero_cumplimiento, $fecha_cumplimiento, $departamento)
{
		list($dbconnect) = GetDBconn();
//el siguiente query consulta sobrer los estados de os cumplimientos detalle lo unico que cambi fue
//b.sw_estado por a.sw_estado
	  if ($_SESSION['LTRABAJO_DATALAB']['MOSTRAR_LISTAS'] == '1')
	  {
				unset($query);
				foreach($_SESSION['LISTAS_SELECCIONADAS'] as $index=>$codigo)
				{
						$arreglo=explode(",",$codigo);
						if($query=='')
						{
								$query = "select * from ((SELECT d.orden_servicio_id,	c.numero_orden_id,
								c.sw_estado, j.cargo, i.descripcion, k.codigo_datalab,
								g.tipo_os_lista_id, g.nombre_lista, f.descripcion as servicio_descripcion

								FROM os_cumplimientos a, os_cumplimientos_detalle c

								left join interface_datalab_control_detalle k
				        on (c.numero_orden_id = k.numero_orden_id),

								os_maestro d,	os_ordenes_servicios e, servicios f,
								tipos_os_listas_trabajo g,
								tipos_os_listas_trabajo_detalle h, cups i, os_internas j
								WHERE a.numero_cumplimiento = c.numero_cumplimiento AND
								a.fecha_cumplimiento = c.fecha_cumplimiento AND a.departamento =
								c.departamento    AND c.numero_cumplimiento = ".$numero_cumplimiento."
								AND c.fecha_cumplimiento = '".$fecha_cumplimiento."'
								AND c.departamento = '".$departamento."' AND c.numero_orden_id =
								d.numero_orden_id    AND d.orden_servicio_id = e.orden_servicio_id
								AND e.servicio = f.servicio    and c.numero_orden_id = j.numero_orden_id
								AND h.tipo_os_lista_id ='".$arreglo[0]."'    AND h.grupo_tipo_cargo = i.grupo_tipo_cargo
								AND h.tipo_cargo = i.tipo_cargo
								AND j.cargo=i.cargo AND g.tipo_os_lista_id = h.tipo_os_lista_id
								)";
						}
						else
						{
								$query.=  "union (SELECT d.orden_servicio_id,	c.numero_orden_id,
								c.sw_estado, j.cargo, i.descripcion, k.codigo_datalab,
								g.tipo_os_lista_id, g.nombre_lista, f.descripcion as servicio_descripcion

								FROM os_cumplimientos a, os_cumplimientos_detalle c
                left join interface_datalab_control_detalle k
				        on (c.numero_orden_id = k.numero_orden_id),
								os_maestro d,	os_ordenes_servicios e, servicios f,
								tipos_os_listas_trabajo g,
								tipos_os_listas_trabajo_detalle h, cups i, os_internas j

								WHERE a.numero_cumplimiento = c.numero_cumplimiento AND
								a.fecha_cumplimiento = c.fecha_cumplimiento AND a.departamento =
								c.departamento    AND c.numero_cumplimiento = ".$numero_cumplimiento."
								AND c.fecha_cumplimiento = '".$fecha_cumplimiento."'
								AND c.departamento = '".$departamento."' AND c.numero_orden_id =
								d.numero_orden_id AND d.orden_servicio_id = e.orden_servicio_id
								AND e.servicio = f.servicio and c.numero_orden_id = j.numero_orden_id
								AND h.tipo_os_lista_id ='".$arreglo[0]."'    AND h.grupo_tipo_cargo = i.grupo_tipo_cargo
								AND h.tipo_cargo = i.tipo_cargo    AND j.cargo=i.cargo
								AND g.tipo_os_lista_id = h.tipo_os_lista_id
								)";
						}
				}
				$query.=") as a order by a.tipo_os_lista_id, a.numero_orden_id asc";
		}
		else
		{
				$query = "SELECT b.orden_servicio_id, a.numero_orden_id,
				a.sw_estado, c.cargo, c.descripcion, d.codigo_datalab

				FROM os_cumplimientos_detalle as a
				left join interface_datalab_control_detalle d
				on (a.numero_orden_id = d.numero_orden_id),
				os_maestro as b, cups as c

				WHERE a.numero_cumplimiento = ".$numero_cumplimiento." AND
				a.fecha_cumplimiento = '".$fecha_cumplimiento."' AND
				a.departamento = '".$departamento."' AND a.numero_orden_id = b.numero_orden_id
				AND b.cargo_cups = c.cargo  order by b.orden_servicio_id asc, a.numero_orden_id asc";
		}


	  $result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error en la busqueda";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else
		{
		    $i=0;
				while (!$result->EOF)
				{
					$fact[$i]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
					$i++;
				}
		}

		//TRAYENDO LOS DIAGNOSTICOS DE CADA SOLICITUD Y LOS DE INGRESO
		$and="";
		$search="";
		if(sizeof($fact)>0)
		{
			for($k=0;$k<sizeof($fact);$k++)
			{
				if($k==0)
				{
						$union = ' and  (';
				}
				else
				{
						$union = ' or  ';
				}
				$search.= "$union  a.numero_orden_id= ".$fact[$k][numero_orden_id]."";
		  }
			$search.=")";
			//query para traer los diagnosticos de acda solicitud
			$query = "SELECT DISTINCT c.diagnostico_id, c.diagnostico_nombre
			FROM os_maestro a, hc_os_solicitudes_diagnosticos b, diagnosticos c
			WHERE a.hc_os_solicitud_id = b.hc_os_solicitud_id
			AND b.diagnostico_id = c.diagnostico_id $and $search
			order by c.diagnostico_id asc";

			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
					$this->error = "Error en la busqueda";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
					return false;
			}
			else
			{
					$i=0;
					while (!$result->EOF)
					{
							$diag[$i]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
							$i++;
					}
			}
			$fact[0][diagnosticos]=$diag;

			//query para traer los diagnosticos de ingreso
			$query = "SELECT DISTINCT  d.diagnostico_id, d.diagnostico_nombre
			FROM os_maestro a, hc_os_solicitudes b, hc_diagnosticos_ingreso c,
			diagnosticos d WHERE a.hc_os_solicitud_id = b.hc_os_solicitud_id
			AND b.evolucion_id = c.evolucion_id AND
			c.tipo_diagnostico_id = d.diagnostico_id $and $search
			order by d.diagnostico_id asc";

			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
					$this->error = "Error en la busqueda";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
					return false;
			}
			else
			{
					$i=0;
					while (!$result->EOF)
					{
							$diag_ingreso[$i]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
							$i++;
					}
			}
			$fact[0][diagnosticos_ingreso]=$diag_ingreso;
	  }
	  return $fact;
}

/**
	* La funcion tipo_id_paciente se encarga de obtener de la base de datos
	* los diferentes tipos de identificacion de los paciente.
	* @access public
	* @return array
*/
	function tipo_id_paciente()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		else
		{
				if($result->EOF)
				{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipos_id_pacientes' esta vacia ";
						return false;
				}
				while (!$result->EOF)
				{
						$vars[$result->fields[0]]=$result->fields[1];
						$result->MoveNext();
				}
    }
		$result->Close();
		return $vars;
  }


function UpdateDatos()
{
  IncludeLib('funciones_datalab');
	$paso=1;
  if (empty( $_REQUEST['op']))
	{
			$this->frmError["MensajeError"]="DEBE SELECCIONAR UN EXAMEN PARA EL ENVIO A DATALAB";
			//regresando al programa
			$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
			$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
			$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
			$_REQUEST['nombre'], $_REQUEST['edad_paciente']);
			return true;
	}
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

		$datos_paciente = Get_Datos_Paciente($_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id']);
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
      $hc = $_REQUEST['tipo_id_paciente'].'||'.$_REQUEST['paciente_id'];
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
		//$medico = Get_Medico($datos_solicitud[usuario_id]);
    if($datos_solicitud[usuario_id]!='')
    {
      $medico = 'MUID'.$datos_solicitud[usuario_id];
		}
		else
		{
      $medico = 'MUIDNULL';
		}


  list($dbconn) = GetDBconn();
	$sw_estado = '1';

	$dbconn->BeginTrans();
	foreach($_REQUEST['op'] as $index=>$codigo)
	{
			$arreglo=explode(",",$codigo);
			if ($_REQUEST['codigo'.$arreglo[0]] != '-1')
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
									".$_REQUEST['numero_cumplimiento'].", '".$_REQUEST['fecha_cumplimiento']."',
									'".$_REQUEST['departamento']."', '".$_REQUEST['tipo_id_paciente']."',
								  '".$_REQUEST['paciente_id']."')";

					$result=$dbconn->Execute($query);
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
							VALUES(".$control_id.", ".$arreglo[0].", ".$_REQUEST['codigo'.$arreglo[0]].")";

			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al insertar en interface_datalab_control_detalle";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
			}
			//en solicitudes el codigo de datalab se convierte a char y se
			//inserta en codigo_examen
      $codigo_examen = (string) $_REQUEST['codigo'.$arreglo[0]];
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
	$dbconn->CommitTrans();

  //regresando al programa
	$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
	$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
	$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
	$_REQUEST['nombre'], $_REQUEST['edad_paciente']);
	return true;
}

//funciones adicionales

/**
	* La funcion tipo_id_paciente se encarga de obtener de la base de datos
	* los diferentes tipos de identificacion de los paciente.
	* @access public
	* @return array
*/
	function Cargar_Codigos_Datalab($cargo)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT * FROM interface_datalab_codigos WHERE codigo_cups = '".$cargo."'";
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



}//fin clase user

?>
