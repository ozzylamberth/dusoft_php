<?php
/**
* Modulo de Listas de Trabajo para DATALAB(PHP).
*
* Modulo para el manejo de listas de trabajo en Interface con DATALAB
*
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
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
		list($dbconn) = GetDBconn();
		$filtroTipoDocumento = '';
		$filtroDocumento='';
		$filtroNombres='';
		$filtroFecha='';

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
  }
	else
	{
			list($dbconn) = GetDBconn();
			$filtroTipoDocumento = $_SESSION['BUSQUEDA_ORDEN']['filtroTipoDocumento'];
			$filtroDocumento = $_SESSION['BUSQUEDA_ORDEN']['filtroDocumento'];
			$filtroNombres = $_SESSION['BUSQUEDA_ORDEN']['filtroNombres'];
			$filtroFecha = $_SESSION['BUSQUEDA_ORDEN']['filtroFecha'];
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
									$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroFecha)";
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
									$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroFecha
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

					$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroFecha) as A";
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
							$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroFecha
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
							$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroFecha
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

			$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroFecha
			order by a.fecha_cumplimiento, a.numero_cumplimiento
		  LIMIT ".$this->limit." OFFSET $Of;";
	}
  if ($_REQUEST['Buscar_Orden_Cargar_Session'] != '')
  {
			unset ($SESSION['BUSQUEDA_ORDEN']);
			$_SESSION['BUSQUEDA_ORDEN']['filtroTipoDocumento'] = $filtroTipoDocumento;
			$_SESSION['BUSQUEDA_ORDEN']['filtroDocumento'] = $filtroDocumento;
			$_SESSION['BUSQUEDA_ORDEN']['filtroNombres'] = $filtroNombres;
			$_SESSION['BUSQUEDA_ORDEN']['filtroFecha'] =  $filtroFecha;
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
								$query = "select * from ((SELECT g.tipo_os_lista_id,
								c.numero_orden_id, c.sw_estado,
								j.cargo, i.descripcion, g.nombre_lista, f.descripcion as servicio_descripcion
								FROM os_cumplimientos a, os_cumplimientos_detalle c, os_maestro d,
								os_ordenes_servicios e, servicios f, tipos_os_listas_trabajo g,
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
								$query.=  "union (SELECT g.tipo_os_lista_id,
								c.numero_orden_id, c.sw_estado, j.cargo,
								i.descripcion, g.nombre_lista, f.descripcion as servicio_descripcion
								FROM os_cumplimientos a, os_cumplimientos_detalle c, os_maestro d,
								os_ordenes_servicios e, servicios f, tipos_os_listas_trabajo g,
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
				$query = "SELECT a.numero_orden_id, a.sw_estado, c.cargo, c.descripcion
				FROM os_cumplimientos_detalle as a, os_maestro as b, cups as c
				WHERE a.numero_cumplimiento = ".$numero_cumplimiento." AND
				a.fecha_cumplimiento = '".$fecha_cumplimiento."' AND
				a.departamento = '".$departamento."' AND a.numero_orden_id = b.numero_orden_id
				AND b.cargo_cups = c.cargo  order by a.numero_orden_id asc";
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
	if($_REQUEST['sw_estado']== '1')
	{
			$sw_estado = '0';
	}
	else
	{
			$sw_estado = '1';
	}
	list($dbconn) = GetDBconn();
	$query="UPDATE os_cumplimientos_detalle SET
					sw_estado = '".$sw_estado."'
					WHERE numero_orden_id = ".$_REQUEST['numero_orden_id']."";
	$resulta=$dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0)
	{
			$this->error = "Error al actualizar el estado del apoyo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
	}

	//llamando a insertar solictud para datalab.
  /*$this->Insertar_Solicitud_Datalab($_REQUEST['numero_orden_id'],
	$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'], $_REQUEST['numero_cumplimiento'],
	$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento']);*/


	//LLAMANDO A INSERTAR resultado
	$this->Insertar_Resultado();

	$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
	$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
	$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
	$_REQUEST['nombre'], $_REQUEST['edad_paciente']);
	return true;
}
//PRUEBA DE INSERCCION EN LA TABLA DE SOLICITUD A DATALAB



function Insertar_Solicitud_Datalab($numero_orden_id, $tipo_id_paciente, $paciente_id, $numero_cumplimiento, $fecha_cumplimiento, $departamento)
{
	list($dbconn) = GetDBconn();
	//funciones de equivalencia para datalab
	$tarifa = $this->Get_Tarifa($numero_orden_id);

	$pagador = $this->Get_Pagador($numero_orden_id);

	$servicio = $this->Get_Servicio($numero_orden_id);

	if ($servicio == '3')
	{
    $tipo_orden = 'R';
	}
	else
	{
    $tipo_orden = 'U';
	}

	$medico = $this->Get_Medico($numero_orden_id);

	$datos_paciente = $this->Get_Datos_Paciente($tipo_id_paciente, $paciente_id);

	$sexo = $this->Get_Sexo($datos_paciente[sexo_id]);

	$cama = $this->Get_Cama($numero_orden_id);

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

  $hc = $datos_paciente[historia_prefijo]|' '|$datos_paciente[historia_numero];

	$datos_solicitud = $this->Get_Datos_Solicitud($numero_orden_id);


//insertar en control
	$dbconn->BeginTrans();
	$query="SELECT nextval('interface_datalab_control_interface_datalab_control_id_seq')";
	$result=$dbconn->Execute($query);
	$control_id=$result->fields[0];

	echo $query="INSERT INTO interface_datalab_control
	        (interface_datalab_control_id, numero_orden_id,
          numero_cumplimiento, fecha_cumplimiento,
          departamento, tipo_id_paciente, paciente_id)
					VALUES(".$control_id.", ".$numero_orden_id.",
          ".$numero_cumplimiento.", '".$fecha_cumplimiento."',
          '".$departamento."', '".$tipo_id_paciente."',
         '".$paciente_id."')";

	$result=$dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0)
	{
			$this->error = "Error al insertar en interface_datalab_control";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
	}
	else
	{
		$query="INSERT INTO interface_datalab_solicitudes (hc, apellido, nombre, sexo,
		fecha_nacimiento, hc1, hc2,
		fecha_hora_envio, orden1, orden2, orden3,
		orden4, orden5, orden6, orden7, tipo_orden, ordcomentario,
		codigo_examen, medico_solicitante) VALUES ('".$hc."',
		'".$datos_paciente[apellidos]."',
		'".$datos_paciente[nombres]."', '".$sexo."',
		'".$datos_paciente[fecha_nacimiento]."',
		'".$datos_paciente[residencia_telefono]."',
		'".$tarifa."', now(),
		'".$pagador."', '".$turno."', '".$servicio."',
		'".$medico."', '".$cama."',
		".$control_id.", '', '".$tipo_orden."',
		'".$datos_solicitud[observacion]."',
		'".$datos_solicitud[cargo]."', '".$datos_solicitud[profesional]."')";


		$result=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al insertar en hc_os_solicitudes";
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
  $dbconn->CommitTrans();

	return true;
}

//funciones de equivalencia para datalab
function Get_Tarifa($numero_orden_id)
{
		list($dbconn) = GetDBconn();
		$query="select tarifario_id
		from os_maestro_cargos where numero_orden_id = $numero_orden_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta del Tarifario";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($tarifario_id)=$result->FetchRow();
			$result->Close();
		}else{
        return "ERROR";
		}

    $query="SELECT equivalencia FROM
		interface_datalab_tarifario WHERE tarifario_id = '".$tarifario_id."'";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de la equivalencia del Tarifario";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($equivalencia)=$result->FetchRow();
			$result->Close();
		}
    return $equivalencia;
}

function Get_Pagador($numero_orden_id)
{
		list($dbconn) = GetDBconn();
		$query="select c.plan_id, c.num_contrato
		from os_maestro a, os_ordenes_servicios b, planes c
		where
		a.numero_orden_id = $numero_orden_id and
		a.orden_servicio_id = b.orden_servicio_id
		and b.plan_id = c.plan_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta del Pagador";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($plan_id, $num_contrato)=$result->FetchRow();
			$result->Close();
		}else{
        return "ERROR";
		}
    $query="SELECT equivalencia FROM
		interface_datalab_pagador  WHERE
		plan_id = ".$plan_id."
		and num_contrato = '".$num_contrato."'";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de la equivalencia del Pagador";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($equivalencia)=$result->FetchRow();
			$result->Close();
		}
    return $equivalencia;
}

function Get_Servicio($numero_orden_id)
{
		list($dbconn) = GetDBconn();
		$query="select c.servicio
		from os_maestro a, os_ordenes_servicios b, servicios c
		where
		a.numero_orden_id = $numero_orden_id and
		a.orden_servicio_id = b.orden_servicio_id
		and b.servicio = c.servicio";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta del servicio";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($servicio)=$result->FetchRow();
			$result->Close();
		}else{
        return "ERROR";
		}
    $query="SELECT equivalencia FROM
		interface_datalab_servicio  WHERE
		servicio = ".$servicio."";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de la equivalencia del servicio";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($equivalencia)=$result->FetchRow();
			$result->Close();
		}
    return $equivalencia;
}

function Get_Medico($numero_orden_id)
{
		list($dbconn) = GetDBconn();
    $query="SELECT equivalencia FROM
		interface_datalab_medico  WHERE
		usuario_id = ".$usuario_id."";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de la equivalencia del medico";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($equivalencia)=$result->FetchRow();
			$result->Close();
		}
    return $equivalencia;
}

function Get_Datos_Paciente($tipo_id_paciente, $paciente_id)
{
		list($dbconn) = GetDBconn();
    $query="SELECT b.historia_numero, b.historia_prefijo,
		a.paciente_id, a.tipo_id_paciente,
		btrim(a.primer_nombre||' '||a.segundo_nombre, '') as nombres,
		btrim(a.primer_apellido||' '||a.segundo_apellido, '') as apellidos,
		a.fecha_nacimiento, a.residencia_telefono, a.sexo_id
		FROM pacientes a left join historias_clinicas b on
		(a.paciente_id = b.paciente_id and a.tipo_id_paciente = b.tipo_id_paciente)
		WHERE a.tipo_id_paciente = '".$tipo_id_paciente."'
		and a.paciente_id = '".$paciente_id."'";

    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error en la consulta de lecturas profesionales";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
		if (!$result->EOF)
		{
					$datos=$result->GetRowAssoc($ToUpper = false);
					$result->Close();
		}
		return $datos;
}

function Get_Sexo($sexo_id)
{
		list($dbconn) = GetDBconn();
    $query="SELECT equivalencia FROM
		interface_datalab_sexo  WHERE
		sexo_id = '".$sexo_id."'";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de la equivalencia del medico";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($equivalencia)=$result->FetchRow();
			$result->Close();
		}
    return $equivalencia;
}

function Get_Cama($numero_orden_id)
{
		list($dbconn) = GetDBconn();
		$query="select c.cama from os_maestro a, ingresos_departamento b,
		movimientos_habitacion c where a.numero_orden_id = ".$numero_orden_id." and
		a.numerodecuenta = b.numerodecuenta and
		b.ingreso_dpto_id = c.ingreso_dpto_id and c.fecha_egreso is NULL ";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta del Pagador";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($cama)=$result->FetchRow();
			$result->Close();
		}
    return $equivalencia;
}

function Get_Datos_Solicitud($numero_orden_id)
{
		list($dbconn) = GetDBconn();
		echo $query="select e.profesional, b.cargo, c.observacion from os_maestro a,
		hc_os_solicitudes b left join hc_os_solicitudes_manuales e on
		(b.hc_os_solicitud_id = e.hc_os_solicitud_id),
		hc_os_solicitudes_apoyod c
		where a.numero_orden_id = '".$numero_orden_id."'
		and a.hc_os_solicitud_id = b.hc_os_solicitud_id
		and b.hc_os_solicitud_id = c.hc_os_solicitud_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error en la consulta de lecturas profesionales";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
		if (!$result->EOF)
		{
					$datos=$result->GetRowAssoc($ToUpper = false);
					$result->Close();
		}
		return $datos;
}


//PRUEBA DE INSERCCION PARA DATALAB LA IDEA ES QUE ESTO SE INVOQUE CUANDOI LLEGUEN LOS DATOS PERO DESDE OTRO MODULO
function Insertar_Resultado($cargo, $fecha, $ob_b, $numero_orden_id, $usuario, $tipo_id_paciente, $paciente_id)
{
//DATOS REQUERIDOS
		$os_tipo_resultado = 'APD';
		$indice = 1;


    //$usuario = '2';
		//$multiplantilla = $this->Consultar_Plantillas_Examen($cargo);
    /*$componentes=$this->ConsultaComponentesExamen($cargo, $multiplantilla[0][lab_examen_id]);
		if(!$componentes)
		{
				$SubExamen=$this->SubExamenGenerico();
				if($this->CrearGenerico($cargo, '', $SubExamen[lab_examen_id])==false)
				{
					$this->salida.="<td>ESTE APOYO DIAGNOSTICO NO TIENE EXAMENES RELACIONADOS - LA INFORMACION DE ESTE APOYO ESTA SIENDO CARGADA</td>";
					return true;
        }
				else
				{
					$componentes=$this->ConsultaComponentesExamen($cargo);
				}
    }*/
		//print_r($multiplantilla);
		//print_r($componentes);
		//EL RESULTADO SE CARGA ABAJO.
//FIN DE DATOS


  list($dbconn) = GetDBconn();
	/*	$fecha= $_REQUEST['fecha_realizado'];
		$indice = $_REQUEST['items'];
		if($fecha =='')
		{
				$this->frmError["fecha"]=1;
				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
				$this->frmCrearFormaE($cargo,$_REQUEST["$fecha"]);
				return false;
		}
		else
		{
				$cad=explode ('-',$fecha);
				$dia = $cad[0];
				$mes = $cad[1];
				$ano = $cad[2];
				$fecha=$cad[2].'-'.$cad[1].'-'.$cad[0];
				if (date("Y-m-d",mktime(0,0,0,$mes,$dia,$ano)) > date("Y-m-d",mktime(0,0,0,date('m'),date('d'),date('Y'))))
				{
					$this->frmError["fecha"]=1;
					$this->frmError["MensajeError"]="FECHA INVALIDA.";
					$this->frmCrearFormaE($cargo,$_REQUEST["$fecha"]);
					return false;
				}
		}
		for ($i=0; $i< $indice; $i++)
		{
				$res='res'.$i;
				if ($_REQUEST[$res] === '')
				{
						$this->frmError["res"]=1;
						$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
						if($fecha =='')
						{
								$this->frmError["fecha"]=1;
								$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
								$this->frmCrearFormaE($cargo,$_REQUEST["$fecha"],$_REQUEST["$res"]);
								return false;
						}
						else
						{
								$this->frmCrearFormaE($cargo,$_REQUEST["$res"]);
								return false;
						}
				}
		}*/

		$dbconn->BeginTrans();



  //OBTENER EL TIPO DE RESULTADO - CAMBIO GENERADO PARA INSERTAR RESULTADOS DE PNQ
		/*$query= "SELECT c.apoyod_tipo_id as tipo_resultadoapd,
						d.grupo_tipo_cargo as tipo_resultadonqx
						FROM cups a, grupos_noqx_apoyod b left join apoyod_tipos c
						on (b.grupo_tipo_cargo = c.apoyod_tipo_id) left join
						no_qx_grupos_tipo_cargo d on (b.grupo_tipo_cargo = d.grupo_tipo_cargo)
						WHERE a.cargo = '".$cargo."' AND a.grupo_tipo_cargo = b.grupo_tipo_cargo";
		$result=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar los datos del examen";
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
						$this->frmError["MensajeError"]="ESTE EXAMEN NO TIENE ASOCIADO UN TIPO DE RESULTADO PRESIONE EL BOTON LISTA DE APOYO DIAG.";
						return false;
					}
			}
		}
		else
		{
			$this->frmError["MensajeError"]="ESTE EXAMEN NO TIENE ASOCIADO UN TIPO DE RESULTADO PRESIONE EL BOTON LISTA DE APOYO DIAG.";
			return false;
		}*/
	//FIN

//SELECCIONAR LOS DATOS DE LA TABAL DE DATALAB
		$query= "SELECT a.*, b.* FROM interface_datalab_resultados a,
		interface_datalab_control b, interface_datalab_solicitudes c
		WHERE a.interface_datalab_control_id = '18'
		and a.interface_datalab_control_id = b.interface_datalab_control_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar los diagnosticos asignados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
				$vector=$result->GetRowAssoc($ToUpper = false);
		}
		$result->Close();


		$usuario = $this->Get_Bacteriologo($vector[0][bacteriologo]);


	//realiza el id manual de la tabla
		$query="SELECT nextval('hc_resultados_resultado_id_seq')";
		$result=$dbconn->Execute($query);
		$resultado_id=$result->fields[0];
	//fin de la operacion


		echo $query="INSERT INTO hc_resultados (resultado_id, fecha_registro, usuario_id,
		 						 tipo_id_paciente, paciente_id, cargo, fecha_realizado, os_tipo_resultado,
								 observacion_prestacion_servicio)
								 VALUES(".$resultado_id.", now(), ".$usuario.",
                 '".$vector[tipo_id_paciente]."','".$vector[paciente_id]."',
								 '".$vector[codigo_examen]."','".$vector[fecha_resultado]."',
								 '".$os_tipo_resultado."','".$vector[comentario]."'
  							 	)";

		$resulta1=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al insertar en hc_resultados";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
	      $dbconn->RollbackTrans();
				return false;
		}
		else
		{
			echo $query="INSERT INTO hc_resultados_sistema
							(resultado_id, numero_orden_id, usuario_id_profesional)
							VALUES  (".$resultado_id.", ".$vector[numero_orden_id].",
							 '".$usuario."')";

			$resulta3=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al insertar en hc_resultados_nosolicitados";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
			}
			else
			{
			//cambia el estado en os_maestro
				echo $query="UPDATE os_maestro SET sw_estado = '4'
				WHERE numero_orden_id = ".$vector[numero_orden_id]."";
				$resulta1=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al actualizar el estado en os_maestro";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}
				else
				{
// 					for ($i=0; $i< $indice; $i++)
// 					{
// 							$nom='nom'.$i;
// 							$res='res'.$i;
//
// 							$_REQUEST[$res] = (30 + $i);

// 							if ((($_REQUEST['rmin']) != '') and (($_REQUEST['rmax']) != ''))
// 							{
// 									if (($_REQUEST[$res]>= $_REQUEST['rmin']) and ($_REQUEST[$res] <= $_REQUEST['rmax']))
// 									{
// 											$sw_alerta = '0';
// 									}
// 									else
// 									{
// 											$sw_alerta = '1';
// 									}
// 							}
// 							else
// 							{
// 									$sw_alerta = '0';
// 							}
							/*echo $query="INSERT INTO hc_apoyod_resultados_detalles
							(lab_examen_id, resultado_id, resultado, sw_alerta)
							VALUES  ('".$_REQUEST[$nom]."',$resultado_id,'".$_REQUEST[$res]."', $sw_alerta)";*/

// 							if($i==1)
// 							{
// 								$multiplantilla[0][lab_examen_id]=11;
// 							}

							echo $query="INSERT INTO hc_apoyod_resultados_detalles
							(lab_examen_id, resultado_id, resultado, sw_alerta)
							VALUES  (1000,".$resultado_id.",
							'".$vector[resultado]."', '".$vector[patologico]."')";


							$resulta4=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "Error al insertar en hc_apoyod_resultados_detalles";
									$this->frmError["MensajeError"]="ERROR AL INSERTAR EL RESULTADO.";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}
// 					}
			}
		 }
	}
	$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
	echo 'koooooooooooooooooooooooooooooooooooooooooo';
	$dbconn->CommitTrans();
	return true;
}

function Get_Bacteriologo($equivalencia)
{
		list($dbconn) = GetDBconn();
    $query="SELECT usuario_id FROM
		interface_datalab_bacteriologo  WHERE
		equivalencia = '".$equivalencia."'";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error en la consulta de la equivalencia del medico";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		if (!$result->EOF)
		{
			list($equivalencia)=$result->FetchRow();
			$result->Close();
		}
    return $equivalencia;
}

function Consultar_Plantillas_Examen($cargo)
		{
				list($dbconnect) = GetDBconn();
				$tipoid   = $this->tipoidpaciente;
				$paciente = $this->paciente;
				$query = "select a.sw_predeterminado, a.lab_examen_id, a.cargo, a.otro_nombre_examen,
				b.lab_plantilla_id, case when (c.titulo_examen = '' or c.titulo_examen ISNULL)
				then d.descripcion else c.titulo_examen end as titulo from lab_examenes_detalle as a,
				lab_examenes as b, apoyod_cargos as c, cups as d where a.cargo = '".$cargo."' and
				a.lab_examen_id =b.lab_examen_id and a.cargo = c.cargo and a.cargo = d.cargo


        and a.sw_predeterminado = '1'

				order by b.lab_plantilla_id";
				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
						{
								$this->error = "Error en la consulta de lecturas profesionales";
								$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
								return false;
						}
				else
						{ $i=0;
								while (!$result->EOF)
										{
												$fact[$i]=$result->GetRowAssoc($ToUpper = false);
												$result->MoveNext();
												$i++;
										}
						}
				return $fact;
		}

function ConsultaComponentesExamen($cargo, $examen_multiplantilla)
{
		list($dbconnect) = GetDBconn();
		$criterio_multiplantilla = '';
		if ($examen_multiplantilla != '')
		{
				$criterio_multiplantilla = "AND b.lab_examen_id = $examen_multiplantilla";
		}

//meter plantilla 3ok
		$query = "SELECT  e.detalle, a.lab_plantilla_id,a.nombre_examen,a.unidades,
		          b.otro_nombre_examen,	b.indice_de_orden,c.sexo_id,c.rango_min,
							c.rango_max,c.lab_examen_id,c.edad_min,c.edad_max,a.lab_examen_id,
							d.lab_examen_opcion_id,d.opcion

							FROM lab_examenes a,lab_examenes_detalle b left join
							lab_plantilla1 c on (b.lab_examen_id = c.lab_examen_id) left join
							lab_plantilla2 as d on (b.lab_examen_id = d.lab_examen_id)
							left join lab_plantilla3 as e on (b.lab_examen_id = e.lab_examen_id)

							WHERE b.cargo='".$cargo."'
							AND b.lab_examen_id=a.lab_examen_id $criterio_multiplantilla

							ORDER BY b.indice_de_orden";
    $result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al consultar los componentes del examen";
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
		return $fact;
}


//funciones medicamentos
function GetMedicamentos()
  {

    list($dbconn) = GetDBconn();
    $evolucion=$_SESSION['CENTRAL']['PACIENTE']['evolucion_id'];
         $sql="select k.sw_uso_controlado, case when k.sw_pos = 1 then 'POS' else 'NO POS'
				  end as item,  a.sw_paciente_no_pos, a.codigo_producto,  h.descripcion as producto,
          c.descripcion as principio_activo, m.nombre as via, a.dosis, a.unidad_dosificacion,
          a.tipo_opcion_posologia_id, a.cantidad, l.descripcion, h.contenido_unidad_venta,
          a.observacion from hc_medicamentos_recetados_hosp as a left join
					hc_vias_administracion as m
          on (a.via_administracion_id = m.via_administracion_id),
					inv_med_cod_principios_activos as c,
          inventarios_productos as h, medicamentos as k, unidades as l
					where a.evolucion_id = ".$evolucion."
          and k.cod_principio_activo = c.cod_principio_activo
          and h.codigo_producto = k.codigo_medicamento
					and a.codigo_producto = h.codigo_producto
          and h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id
          order by k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto;";

      $result = $dbconn->Execute($sql);
      $i=0;
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
      return $var;


  }


  function Consulta_Solicitud_Medicamentos_Posologia($codigo_producto, $tipo_posologia)
{
    $pfj=$this->frmPrefijo;
    list($dbconnect) = GetDBconn();
    $query == '';
    if ($tipo_posologia == 1)
    {
        $query= "select periocidad_id, tiempo from hc_posologia_horario_op1_hosp where evolucion_id = ".$_SESSION['CENTRAL']['PACIENTE']['evolucion_id']." and codigo_producto = '$codigo_producto'";
    }
    if ($tipo_posologia == 2)
    {
        $query= "select a.duracion_id, b.descripcion from hc_posologia_horario_op2_hosp as a, hc_horario as b where evolucion_id = ".$_SESSION['CENTRAL']['PACIENTE']['evolucion_id']." and codigo_producto = '$codigo_producto' and a.duracion_id = b.duracion_id";
    }
    if ($tipo_posologia == 3)
    {
        $query= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3_hosp where evolucion_id = ".$_SESSION['CENTRAL']['PACIENTE']['evolucion_id']." and codigo_producto = '$codigo_producto'";
    }
    if ($tipo_posologia == 4)
    {
        $query= "select hora_especifica from hc_posologia_horario_op4_hosp where evolucion_id = ".$_SESSION['CENTRAL']['PACIENTE']['evolucion_id']." and codigo_producto = '$codigo_producto'";
    }
    if ($tipo_posologia == 5)
    {
        $query= "select frecuencia_suministro from hc_posologia_horario_op5_hosp where evolucion_id = ".$_SESSION['CENTRAL']['PACIENTE']['evolucion_id']." and codigo_producto = '$codigo_producto'";
    }

    if ($query!='')
    {
        $result = $dbconnect->Execute($query);
        if ($dbconnect->ErrorNo() != 0)
        {
          $this->error = "Error al buscar en la consulta de medicamentos recetados";
          $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
          return false;
        }
        else
        {
          if ($tipo_posologia != 4)
          {
            $i=0;
            while (!$result->EOF)
            {
              $vector[$i]=$result->GetRowAssoc($ToUpper = false);
              $result->MoveNext();
              $i++;
            }
          }
          else
          {
            while (!$result->EOF)
            {
              $vector[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
              $result->MoveNext();
              $i++;
            }
          }
        }
        /*if($result)
        {
          $result->Close();
        }*/
    }
    return $vector;
}

//funciones medicamentos.


/* insertanto en resulgtados de datalab.
INSERT INTO interface_datalab_resultados (numero_orden_id,  codigo_seccion,  nombre_seccion,
codigo_examen, codigo_datalab, abreviatura, nombre_examen, resultado, unidades,
normal_minima, normal_maxima, patologico, comentario, muestra_microbiologia,
microorganismo, antibiotico, resultado_antibiotico, bacteriologo,
fecha_resultado, interface_datalab_control_id) VALUES ('18', '1', 'QUIMICA', '903873',
1015, 'TRI', 'TRIGLICERIDOS', '220', 'mg/dl', '0', '200', '1', 'sin comentarios',
'','','','','2', '2004/11/17 04:30:00', 18)

*/
}//fin clase user

?>
