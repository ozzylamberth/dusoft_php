<?php

/**
 * $Id: app_Os_ListaTrabajo_user.php,v 1.21 2007/11/29 16:54:12 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Listas de Trabajo (PHP).
 * Modulo para el manejo de listas de trabajo
 */

	class app_Os_ListaTrabajo_user extends classModulo
	{
			var $limit;
			var $conteo;//para saber cuantos registros encontró

			function app_Os_ListaTrabajo_user()
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

  /**
	* Separa la Fecha del formato timestamp
	* @access private
	* @return string
	* @param date fecha
	*/
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
    }/**/

/*******************************************************************/	
	/**
	* Separa la hora del formato timestamp
	* @access private
	* @return string
	* @param date hora
	*/
	function HoraStamp($hora)
    {
    	$hor = strtok ($hora," ");
    	for($l=0;$l<4;$l++)
    	{
    	  $time[$l]=$hor;
    	  $hor = strtok (":");
    	}
		$x=explode('.',$time[3]);
    	return  $time[1].":".$time[2].":".$x[0];
   }/**/

	function GetForma()
	{
				$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
					$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
			$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
				$_REQUEST['nombre'], $_REQUEST['edad_paciente'], $_REQUEST['Fecha']);
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
									FROM userpermisos_os_lista_trabajo b, departamentos c, centros_utilidad d,
									empresas e    WHERE    b.usuario_id=".UserGetUID()."
									AND    c.departamento=b.departamento
									AND    d.centro_utilidad=c.centro_utilidad    AND    e.empresa_id=d.empresa_id
									AND    e.empresa_id=c.empresa_id    ORDER BY centro";

					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$resulta = $dbconn->Execute($query);
					while($data = $resulta->FetchRow())
					{
							$laboratorio[$data['emp']][$data['centro']][$data['dpto']]=$data;
					}

					$url[0]='app';
					$url[1]='Os_ListaTrabajo';
					$url[2]='user';
					$url[3]='Menuatencion';
					$url[4]='ListaTrabajo';

					$arreglo[0]='EMPRESA';
					$arreglo[1]='CENTRO UTILIDAD';
					$arreglo[2]='ATENCION DE LISTA DE TRABAJO';

					$this->salida.= gui_theme_menu_acceso('ATENCION DE LISTA DE TRABAJO',$arreglo,$laboratorio,$url);
					return true;
			}


	/**
	* Menuatencion, esta funcion  es donde desemboca el menu iteractivo creado por arley.
	* @access public
	* @return boolean
	*/
	function Menuatencion()
	{
			$_SESSION['LTRABAJO']['EMPRESA_ID']=$_REQUEST['ListaTrabajo']['empresa_id'];
			$_SESSION['LTRABAJO']['CENTROUTILIDAD']=$_REQUEST['ListaTrabajo']['centro_utilidad'];
			$_SESSION['LTRABAJO']['NOM_CENTRO']=$_REQUEST['ListaTrabajo']['centro'];
			$_SESSION['LTRABAJO']['NOM_EMP']=$_REQUEST['ListaTrabajo']['emp'];
			$_SESSION['LTRABAJO']['NOM_DPTO']=$_REQUEST['ListaTrabajo']['dpto'];
			$_SESSION['LTRABAJO']['DPTO']=$_REQUEST['ListaTrabajo']['departamento'];
			$_SESSION['LTRABAJO']['MOSTRAR_LISTAS']=$_REQUEST['ListaTrabajo']['sw_mostrar_listas'];

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
											d.centro_utilidad, a.usuario_id FROM userpermisos_os_lista_trabajo a,
											userpermisos_os_lista_trabajo_detalle b, departamentos c, centros_utilidad d,
											empresas e, tipos_os_listas_trabajo f WHERE a.usuario_id = b.usuario_id
											AND a.departamento = b.departamento AND b.tipo_os_lista_id = f.tipo_os_lista_id
											AND b.departamento = f.departamento AND a.usuario_id=".UserGetUID()."
											AND a.departamento=c.departamento AND c.centro_utilidad=d.centro_utilidad
											AND d.empresa_id=e.empresa_id    AND e.empresa_id=c.empresa_id
											AND a.departamento = '".$_SESSION['LTRABAJO']['DPTO']."'
											ORDER BY centro, b.tipo_os_lista_id";
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
			if ($_SESSION['LTRABAJO']['MOSTRAR_LISTAS'] == '1')
			{
				if (empty($_REQUEST['op']))
				{
					$this->frmError["MensajeError"]="DEBE SELECCIONAR UN TIPO DE LISTA PARA LA PROGRAMACION";
					$this->FormaMetodoBuscar($var);
					return true;
				}
			}

			$tipo_documento=$_REQUEST['TipoDocumento'];
			$documento =$_REQUEST['Documento'];
			$nombres = $_REQUEST['Nombres'];
			$numero_orden = $_REQUEST['Numero_Orden'];
			$historia_prefijo = $_REQUEST['Historia_Prefijo'];
			$historia_numero = $_REQUEST['Historia_Numero'];
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
			else
				$fecha=$this->FechaStamp($_REQUEST['Fecha']);//Convierte la fecha para asegurar que la fecha queda en formato yyyy-mm-dd
			//MauroB
			$cont=substr_count($_REQUEST['Cumplimiento'], "-");
			if($cont==1){
				$cumplimiento= explode("-",$_REQUEST['Cumplimiento']);
				$fecha=$cumplimiento[0];
				$cumplimiento= $cumplimiento[1];
				$fecha=date( "Y-m-d",strtotime ("$fecha"));
				$Mfecha = $fecha;
			}else{
				$cumplimiento = $_REQUEST['Cumplimiento'];
			}
			//Fin MauroB
			

			//filtro adicional de pacientes
			$opcion_pacientes=$_REQUEST['opcion_pacientes'];



			list($dbconn) = GetDBconn();
			$filtroTipoDocumento = '';
			$filtroDocumento='';
			$filtroNombres='';
			$filtroNumeroOrden='';
			$filtroHistoria_Prefijo='';
			$filtroHistoria_Numero='';
			$filtroCumplimiento='';
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

			if ($numero_orden != '')
			{
					$filtroNumeroOrden =" AND c.numero_orden_id = ".$numero_orden."";
			}

			if ($historia_prefijo != '')
			{
					$filtroHistoria_Prefijo ="AND (upper (p.historia_prefijo) LIKE '".strtoupper($historia_prefijo)."%')";
			}

			if ($historia_numero != '')
			{
					$filtroHistoria_Numero ="AND (upper (p.historia_numero) LIKE '".strtoupper($historia_numero)."%')";
			}

			if ($cumplimiento != '')
			{
					$filtroCumplimiento ="AND a.numero_cumplimiento = '".$cumplimiento."'";
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

			$filtroNumeroOrden = $_SESSION['BUSQUEDA_ORDEN']['filtroNumeroOrden'];
			$filtroHistoria_Prefijo = $_SESSION['BUSQUEDA_ORDEN']['filtroHistoria_Prefijo'];
			$filtroHistoria_Numero = $_SESSION['BUSQUEDA_ORDEN']['filtroHistoria_Numero'];
			$filtroCumplimiento = $_SESSION['BUSQUEDA_ORDEN']['filtroCumplimiento'];

			$filtroFecha = $_SESSION['BUSQUEDA_ORDEN']['filtroFecha'];
			$filtroPacientes = $_SESSION['BUSQUEDA_ORDEN']['filtroPacientes'];
			$_REQUEST['op'] = $_SESSION['BUSQUEDA_ORDEN']['listas'];
		}

		if(empty($_REQUEST['conteo'.$pfj]))
		{
				if ($_SESSION['LTRABAJO']['MOSTRAR_LISTAS'] == '1')
				{
						//CASO PARA IMAGENOLOGIA

						//condicion para traer los elementos de las listas seleccionadas
						if (!empty($_REQUEST['op']))
						{
								$search="";
								$union= "";
								$indice = 1;
								foreach($_REQUEST['op'] as $index=>$codigo)
								{
									$arreglo=explode(",",$codigo);
									if($indice==1)
									{
										$union = ' and  ((';
									}
									else
									{
										$union = ' or (';
									}
									$search.= "$union h.tipo_os_lista_id = '".$arreglo[0]."')";
									$indice++;
								}
								$search.=")";
						}
						else
						{
								$search="";
						}
						//fin de la condicion

						unset($query);
						unset($_SESSION['LISTAS_SELECCIONADAS']);
						$_SESSION['LISTAS_SELECCIONADAS']=$_REQUEST['op'];
						//g.nombre_lista
						$query = "SELECT count(*) from (SELECT DISTINCT
						p.historia_prefijo, p.historia_numero,
						b.fecha_nacimiento, f.descripcion as servicio_descripcion,
						a.numero_cumplimiento, a.departamento, a.tipo_id_paciente, a.paciente_id,
						a.fecha_cumplimiento, btrim(b.primer_nombre||' '||b.segundo_nombre||' '||
						b.primer_apellido|| ' '||b.segundo_apellido,'') as nombre

						FROM os_cumplimientos a  left join
						historias_clinicas p on (a.paciente_id = p.paciente_id
						AND a.tipo_id_paciente =	p.tipo_id_paciente),
						os_cumplimientos_detalle c, pacientes b,
						os_maestro d, os_ordenes_servicios e, servicios f,
						tipos_os_listas_trabajo g, tipos_os_listas_trabajo_detalle h, cups i,
						os_internas j
						WHERE a.paciente_id = b.paciente_id AND a.tipo_id_paciente =
						b.tipo_id_paciente AND a.departamento = '".$_SESSION['LTRABAJO']['DPTO']."'
						AND a.numero_cumplimiento = c.numero_cumplimiento
						AND a.fecha_cumplimiento = c.fecha_cumplimiento
						AND a.departamento = c.departamento AND
						c.numero_orden_id = d.numero_orden_id
						AND d.orden_servicio_id = e.orden_servicio_id AND
						e.servicio = f.servicio and c.numero_orden_id = j.numero_orden_id
						$search
						AND h.grupo_tipo_cargo = i.grupo_tipo_cargo
						AND h.tipo_cargo = i.tipo_cargo AND j.cargo=i.cargo
						AND g.tipo_os_lista_id = h.tipo_os_lista_id
						$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroNumeroOrden
						$filtroHistoria_Prefijo	$filtroHistoria_Numero $filtroCumplimiento
						$filtroFecha $filtroPacientes
						)as A";

				}
				else
				{
						$query = "SELECT count(*)
						from (SELECT DISTINCT p.historia_prefijo, p.historia_numero,
						b.fecha_nacimiento, f.descripcion as servicio_descripcion,
						a.numero_cumplimiento,	a.departamento, a.tipo_id_paciente, a.paciente_id,
						a.fecha_cumplimiento,	btrim(b.primer_nombre||' '||b.segundo_nombre||' '||
						b.primer_apellido|| ' '||b.segundo_apellido,'') as nombre

						FROM os_cumplimientos as a left join os_cumplimientos_detalle as c on
						(a.numero_cumplimiento = c.numero_cumplimiento AND a.fecha_cumplimiento =
						c.fecha_cumplimiento AND a.departamento = c.departamento) left join
						historias_clinicas p on (a.paciente_id = p.paciente_id
						AND a.tipo_id_paciente =	p.tipo_id_paciente),
						pacientes b, os_maestro as d, os_ordenes_servicios as e, servicios as f

						WHERE a.paciente_id = b.paciente_id
						AND a.tipo_id_paciente = b.tipo_id_paciente

						AND a.departamento = '".$_SESSION['LTRABAJO']['DPTO']."'

						AND c.numero_orden_id = d.numero_orden_id AND d.orden_servicio_id =
						e.orden_servicio_id AND e.servicio = f.servicio

						$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroNumeroOrden
						$filtroHistoria_Prefijo $filtroHistoria_Numero $filtroCumplimiento
						$filtroFecha $filtroPacientes) as A";
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

		if ($_SESSION['LTRABAJO']['MOSTRAR_LISTAS'] == '1')
		{
				unset($query);
				$query =     "select * from ((SELECT DISTINCT
				p.historia_prefijo, p.historia_numero,
				b.fecha_nacimiento, f.descripcion as servicio_descripcion,
				a.numero_cumplimiento, a.departamento, a.tipo_id_paciente, a.paciente_id,
				a.fecha_cumplimiento, btrim(b.primer_nombre||' '||b.segundo_nombre||' '||
				b.primer_apellido|| ' '||b.segundo_apellido,'') as nombre
				FROM os_cumplimientos a left join
				historias_clinicas p on (a.paciente_id = p.paciente_id
				AND a.tipo_id_paciente =	p.tipo_id_paciente),
				os_cumplimientos_detalle c, pacientes b,
				os_maestro d, os_ordenes_servicios e, servicios f,
				tipos_os_listas_trabajo g, tipos_os_listas_trabajo_detalle h, cups i,
				os_internas j
				WHERE a.paciente_id = b.paciente_id AND a.tipo_id_paciente =
				b.tipo_id_paciente AND a.departamento = '".$_SESSION['LTRABAJO']['DPTO']."'
				AND a.numero_cumplimiento = c.numero_cumplimiento
				AND a.fecha_cumplimiento = c.fecha_cumplimiento
				AND a.departamento = c.departamento AND
				c.numero_orden_id = d.numero_orden_id
				AND d.orden_servicio_id = e.orden_servicio_id AND
				e.servicio = f.servicio and c.numero_orden_id = j.numero_orden_id
				$search
				AND h.grupo_tipo_cargo = i.grupo_tipo_cargo
				AND h.tipo_cargo = i.tipo_cargo AND j.cargo=i.cargo
				AND g.tipo_os_lista_id = h.tipo_os_lista_id
				$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroNumeroOrden
				$filtroHistoria_Prefijo	$filtroHistoria_Numero
				$filtroCumplimiento $filtroFecha $filtroPacientes
				)) as a order by a.fecha_cumplimiento, a.numero_cumplimiento
				LIMIT ".$this->limit." OFFSET $Of;";
		}
		else
		{
				$query = "
				SELECT DISTINCT p.historia_prefijo, p.historia_numero,
				b.fecha_nacimiento, f.descripcion as servicio_descripcion,
				a.numero_cumplimiento, a.departamento, a.tipo_id_paciente, a.paciente_id,
				a.fecha_cumplimiento, btrim(b.primer_nombre||' '||b.segundo_nombre||' '||
				b.primer_apellido|| ' '||b.segundo_apellido,'') as nombre

				FROM os_cumplimientos as a left join os_cumplimientos_detalle as c on
				(a.numero_cumplimiento = c.numero_cumplimiento AND a.fecha_cumplimiento =
				c.fecha_cumplimiento AND a.departamento = c.departamento) left join
				historias_clinicas p on (a.paciente_id = p.paciente_id
				AND a.tipo_id_paciente =	p.tipo_id_paciente),
				pacientes b, os_maestro as d, os_ordenes_servicios as e, servicios as f
				WHERE a.paciente_id = b.paciente_id
				AND a.tipo_id_paciente = b.tipo_id_paciente

				AND a.departamento = '".$_SESSION['LTRABAJO']['DPTO']."'

				AND c.numero_orden_id = d.numero_orden_id AND d.orden_servicio_id =
				e.orden_servicio_id AND e.servicio = f.servicio

				$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroNumeroOrden
				$filtroHistoria_Prefijo	$filtroHistoria_Numero
				$filtroCumplimiento $filtroFecha $filtroPacientes
				order by a.fecha_cumplimiento, a.numero_cumplimiento
				LIMIT ".$this->limit." OFFSET $Of;";

				/*echo    $query = "
				SELECT a.numero_cumplimiento, b.numero_orden_id, a.tipo_id_paciente,
				a.paciente_id, a.fecha_cumplimiento, f.plan_id, d.plan_descripcion,
				btrim(c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||
				' '||c.segundo_apellido,'') as nombre

				FROM os_cumplimientos a,os_cumplimientos_detalle b,pacientes c,planes d,
				os_maestro e,os_ordenes_servicios f

				WHERE a.numero_cumplimiento=b.numero_cumplimiento
				AND a.fecha_cumplimiento= b.fecha_cumplimiento
				AND a.departamento=b.departamento
				AND a.paciente_id=c.paciente_id
				AND a.tipo_id_paciente=c.tipo_id_paciente
				AND b.numero_orden_id=e.numero_orden_id
				AND e.orden_servicio_id=f.orden_servicio_id
				AND f.plan_id=d.plan_id
				$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroNumeroOrden
				$filtroHistoria_Prefijo	$filtroHistoria_Numero $filtroCumplimiento
				$filtroPlan $filtroFecha $filtroPacientes
				order by a.fecha_cumplimiento, a.numero_cumplimiento, b.numero_orden_id
				LIMIT ".$this->limit." OFFSET $Of;";
				*/
		}


		if ($_REQUEST['Buscar_Orden_Cargar_Session'] != '')
		{
				unset ($_SESSION['BUSQUEDA_ORDEN']);
				$_SESSION['BUSQUEDA_ORDEN']['filtroTipoDocumento'] = $filtroTipoDocumento;
				$_SESSION['BUSQUEDA_ORDEN']['filtroDocumento'] = $filtroDocumento;
				$_SESSION['BUSQUEDA_ORDEN']['filtroNombres'] = $filtroNombres;

				$_SESSION['BUSQUEDA_ORDEN']['filtroNumeroOrden'] = $filtroNumeroOrden;
				$_SESSION['BUSQUEDA_ORDEN']['filtroHistoria_Prefijo'] = $filtroHistoria_Prefijo;
				$_SESSION['BUSQUEDA_ORDEN']['filtroHistoria_Numero'] = $filtroHistoria_Numero;
				$_SESSION['BUSQUEDA_ORDEN']['filtroCumplimiento'] = $filtroCumplimiento;

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

		if ($_SESSION['LTRABAJO']['MOSTRAR_LISTAS'] == '1')
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
				$query = "SELECT 	a.numero_orden_id, 
													a.sw_estado, 
													c.cargo, 
													c.descripcion
									FROM 		os_cumplimientos_detalle as a, 
													os_maestro as b, 
													cups as c
									WHERE 	a.numero_cumplimiento = ".$numero_cumplimiento." AND
													a.fecha_cumplimiento = '".$fecha_cumplimiento."' AND
													a.departamento = '".$departamento."' AND 
													a.numero_orden_id = b.numero_orden_id AND 
													b.cargo_cups = c.cargo  
									ORDER BY a.numero_orden_id asc";
			}

		$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
				{
					$this->error = "Error en la busqueda";
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
					//MauroB
					//query para traer las observaciones de la solicitud
					$query = "
										SELECT DISTINCT b.observacion
										FROM 	os_maestro a, 
													hc_os_solicitudes_apoyod b
										WHERE a.hc_os_solicitud_id = b.hc_os_solicitud_id
													$and $search
										order by b.observacion
					";

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
									$observ[$i]=$result->GetRowAssoc($ToUpper = false);
									$result->MoveNext();
									$i++;
							}
					}
					//fin MauroB
						$fact[0][diagnosticos_ingreso]=$diag_ingreso;
						$fact[0][diagnosticos_observacion]=$observ;
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

	/**
	*
	*/
	function UpdateDatos()
	{
			list($dbconn) = GetDBconn();
			if($_REQUEST['sw_estado']== '1'){
					$sw_estado = '0';//a esta asignacion nunca debe de entrar
			}else{
					$sw_estado = '1';
					//MauroB
					// 0 no tomado, 1:tomado
					//inserta por primera vez la ubicacion de la placa
					$permiso=$this->ConsultaPermisoControlPlaca();
					if($permiso=='1'){
							list($dbconn) = GetDBconn();
							$query = "INSERT INTO os_imagenes_control_placas
															(os_imagenes_control_placas_id,
															fecha,
															numero_orden_id,
															usuario_id_remite,
															departamento_actual,
															nuevo_departamento,
															usuario_id_recibe,
															comentario)
											VALUES (NEXTVAL ('os_imagenes_control_placas_id'),
															'now()',
															$_REQUEST[numero_orden_id],
															".UserGetUID().",
															'$_REQUEST[departamento]',
															'$_REQUEST[departamento]',
															".UserGetUID().",
															'PLACA TOMADA'
											)
											";
							$result=$dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0){
								$this->error = "Error al insertar en la tabla imagenes_control_placas";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
								}
								$result->Close();
					}
				//fin mauroB
			}
			//actualiza el estado delsw_estado en os_cumplimientos_detalle
			
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
			$resulta->Close();
			//$this->frmError["MensajeError"]="JUSTIFICACION GUARDADA SATISFACTORIAMENTE";
			//maurob
			//Se deshabilita temporalmente eñl control de insumos,
			//mientras se realiza un analisis y chequeo de los requerimientos
			/** %%Control Insumos %%*/
// 			if(($sw_estado=='1')&&($permiso=='1')){
// 				$this->Control_Insumos($_REQUEST['codigo_cups'],
// 				$_REQUEST['numero_cumplimiento'],
// 				$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
// 				$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
// 				$_REQUEST['nombre'], $_REQUEST['edad_paciente'], 
// 				$_REQUEST['numero_orden_id']);
// 			}else{
// 				$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
// 				$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
// 				$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
// 				$_REQUEST['nombre'], $_REQUEST['edad_paciente']);
// 			}
			if($_REQUEST['sw_get_forma'] != '0')
			{
				if(($sw_estado=='1')&&($permiso=='1')){
					//echo "<br>Entro a la validacion de Control de Insumos";
					/** Momentaneo, por levantamiento de requerimientos*/
					$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
					$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
					$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
					$_REQUEST['nombre'], $_REQUEST['edad_paciente'], $_REQUEST['Fecha']);
				}else{
					$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
					$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
					$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
					$_REQUEST['nombre'], $_REQUEST['edad_paciente'], $_REQUEST['Fecha']);
				}
			}
			else
			{
				$this->BuscarOrden($_REQUEST['Fecha']);
			}
			//fin maurob
			return true;
	}

	
		/**
	* Se consulta si el departamento tiene permisos para
	* ejecutar el control de las placas de radiologia
	* @access public
  * @return Char
	*/
	function ConsultaPermisoControlPlaca(){
		list($dbconn) = GetDBconn();
		$empresa_id=$_SESSION['LTRABAJO']['EMPRESA_ID'];
		$c_utilidad=$_SESSION['LTRABAJO']['CENTROUTILIDAD'];
		$departamento=$_SESSION['LTRABAJO']['DPTO'];
		$query = "SELECT sw_control_placas
							FROM departamentos
							WHERE empresa_id='$empresa_id' AND
										centro_utilidad='$c_utilidad' AND
										departamento='$departamento'
							";
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar permiso control placas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      while(!$result->EOF){
			  $cad=$result->fields[0];
			  $result->MoveNext();
			}
      $result->Close();
		}
		return $cad;
	}//fin function ConsultaPermisoControlPlaca
	
	/**
	* Maneja la adicion de los insumos de un cargo determinado
	*/
	function AdicionaInsumos(){
		$codigo_cups=$_REQUEST['codigo_cups'];
		$numero_cumplimiento=$_REQUEST['numero_cumplimiento'];
		$fecha_cumplimiento=$_REQUEST['fecha_cumplimiento'];
		$departamento=$_REQUEST['departamento'];
		$tipo_id_paciente=$_REQUEST['tipo_id_paciente'];
		$paciente_id= $_REQUEST['paciente_id'];
		$nombre=$_REQUEST['nombre'];
		$edad_paciente= $_REQUEST['edad_paciente'];
		$numero_orden_id=$_REQUEST['numero_orden_id'];
		$referencia=$_REQUEST['referencia'];
		$descripcion=$_REQUEST['descripcion'];
		$prueba=$_REQUEST['prueba'];
		
	
		if($_REQUEST['accion']=='adiciona')
		{
			$this->FormAdicionaInsumos($codigo_cups,$numero_cumplimiento,$fecha_cumplimiento,$departamento,
																	$tipo_id_paciente, $paciente_id,$nombre, $edad_paciente,$numero_orden_id,
																	$vector);
		}elseif($_REQUEST['accion']=='buscar_referencia'){
			$vector=$this->ConsultaReferencias($referencia,$descripcion);
			$this->FormAdicionaInsumos($codigo_cups,$numero_cumplimiento,$fecha_cumplimiento,$departamento,
																	$tipo_id_paciente, $paciente_id,$nombre, $edad_paciente,$numero_orden_id,
																	$vector);
		}elseif($_REQUEST['accion']=='update'){
			for($i=0;$i<sizeof($prueba);$i++){
				if($prueba[$i]['op']==1)
				$this->AdicionaInsumo($codigo_cups,$prueba[$i]['referencia'],$prueba[$i]['cant']);
			}
			$this->Control_Insumos($codigo_cups,$numero_cumplimiento, $fecha_cumplimiento, $departamento, 
															$tipo_id_paciente, $paciente_id,$nombre, $edad_paciente, $numero_orden_id);
		}elseif($_REQUEST['accion']=='control'){
			$this->Control_Insumos($codigo_cups,$numero_cumplimiento, $fecha_cumplimiento, $departamento, 
															$tipo_id_paciente, $paciente_id,$nombre, $edad_paciente, $numero_orden_id);
		}else{
			$this->Consultar_Cumplimiento($numero_cumplimiento,$fecha_cumplimiento,$departamento,
																		$tipo_id_paciente, $paciente_id,$nombre, $edad_paciente);
		}
		return true;
	}//fin AdicionaInsumos
	
	/**
	* Se encarga de insertar en la base de datos un nuevo insumo a un cargo determinado
	*/
	function AdicionaInsumo($cargo,$referencia,$cantidad){
		list($dbconn) = GetDBconn();
		$query="INSERT INTO	os_control_insumos
										(empresa_id,codigo_cups,codigo_producto,cantidad_sugerida,sw_insumo_incluido)
						VALUES	('".$_SESSION[LTRABAJO][EMPRESA_ID]."',
										 '$cargo',
										 '$referencia',
										 $cantidad,
										 '0')
		";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar la referencias";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$result->Close();
	}
	/**
	* Consulta las posibles referencias, dado los parametros de busqueda, que puede insertar
	* a un determinado cargo para realizar el control de insumos
	* @param $referencia	posible referencia a buscar
	* @param $descripcion	posible descripcion de la referencia a bsucar
	* @return	$vector con las referencias resultantes de la busqueda
	*/
	function ConsultaReferencias($referencia,$descripcion){
		$con_referencia='';
		$con_descripcion='';
		$referencia=trim($referencia);
		$descripcion=trim($descripcion);
		$descripcion=strtoupper($descripcion);
		//para que no traiga nada
		if((empty($referencia))&&(empty($descripcion))){
			$con_referencia="AND a.codigo_producto = ''";
		}
		if(!empty($referencia)){
			$con_referencia="AND a.codigo_producto = '".$referencia."'";
		}
		if(!empty($descripcion)){
			$con_descripcion="AND b.descripcion LIKE '%".$descripcion."%'";
		}
		
		list($dbconn) = GetDBconn();
		 $query="SELECT	a.codigo_producto, 
										b.descripcion
						FROM		inventarios a
										LEFT JOIN os_control_insumos as c on (c.codigo_producto = a.codigo_producto),
										inventarios_productos b
						WHERE		a.codigo_producto = b.codigo_producto
										AND c.codigo_producto isNULL
										$con_referencia
										$con_descripcion
		";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar la referencias";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			 while(!$result->EOF){
			  $vector[]=$result->GetRowAssoc($ToUpper = false);
			  $result->MoveNext();
			}
			$result->Close();
		}
		return $vector;
	}
	//funciones MauroB
	/**
	* Consulta la descripcion de un cargo especifico para mostrarlo en el formulario
	* @param		$codigo_cups  cargo cups
	* @return		string resultante de la busqueda
	*/
	function ConsultaDescripcionCargo($codigo_cups){
		list($dbconn) = GetDBconn();
		$query="SELECT	descripcion
						FROM		cups
						WHERE		cargo = '$codigo_cups'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar la descripcion del cargo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$desc=$result->fields[0];
			$result->Close();
		}
		return $desc;
	}//fin ConsultaDescripcionCargo
	/**
	* Lista los departamentos existentes
	* @access public
  * @return vector
	*/
	function BuscarDepartamento(){
    list($dbconn) = GetDBconn();
		$empresa_id=$_SESSION['LTRABAJO']['EMPRESA_ID'];
		$centroutilidad=$_SESSION['LTRABAJO']['CENTROUTILIDAD'];
		$query="SELECT departamento, descripcion
						FROM departamentos
						WHERE empresa_id= '$empresa_id' AND
									centro_utilidad='$centroutilidad'
						ORDER BY descripcion
						";
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar la tabla departamentos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      while(!$result->EOF){
			  $vector[]=$result->GetRowAssoc($ToUpper = false);
			  $result->MoveNext();
			}
      $result->Close();
		}
		return $vector;
  }//fin function BuscarDepartamento
	
	
	
	
	/**
	* Consulta el nombre de un departamento dado el codigo del departamento
	* @access public
  * @return String
	*/
	function BuscaNombreDepartamento($depto){
		list($dbconn) = GetDBconn();
		if(($depto=='')||($depto=='NULL')){
			$cad='PERDIDA';
		}else{
			$query = "SELECT descripcion 
								FROM departamentos 
								WHERE departamento='$depto'
								";
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar la tabla departamentos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				while(!$result->EOF){
					$cad=$result->fields[0];
					$result->MoveNext();
				}
				$result->Close();
			}
		}
		return $cad;
	}//fin function BuscaNombreDepartamento

	/**
	* Consulta el nombre de un profesional dada la
	* identificacion del Usuario
	* @access public
  * @return String
	*/
	function ConsultaNombreProfesional($usuario_id){
		list($dbconn) = GetDBconn();
		if(($usuario_id=='')||($usuario_id=='NULL')){
			$cad='PERDIDA';
		}else{
			$query = "SELECT nombre
								FROM system_usuarios
								WHERE usuario_id=$usuario_id
								";
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar nombre profesional";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				while(!$result->EOF){
					$cad=$result->fields[0];
					$result->MoveNext();
				}
				$result->Close();
			}
		}
		return $cad;
	}//function ConsultaNombreProfesional
	
	
	function ConsultaInsumos($empresa_id,$codigo_cups){
		list($dbconn) = GetDBconn();
		$query="SELECT 	a.codigo_producto,
										a.cantidad_sugerida,
										b.descripcion
						FROM		os_control_insumos a,
										inventarios_productos b
						WHERE  	a.empresa_id = '$empresa_id' AND
										a.codigo_cups = '$codigo_cups' AND
										a.codigo_producto = b.codigo_producto";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Consultar Insumos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      while(!$result->EOF){
			  $vector[]=$result->GetRowAssoc($ToUpper = false);
			  $result->MoveNext();
			}
      $result->Close();
		}
		
		return $vector;
	}//fin function ConsultaInsumos
	
	/**
	* Almacena las cantidas de insumos utilizadas en un cargo durante un procedimiento
	* @return boolean
	*/
	function UpdateControlInsumos()
	{
		list($dbconn) = GetDBconn();
		$date=DATE("Y-m-d");
		$datos=$_REQUEST[datos];
		$empresa_id=$_SESSION['LTRABAJO']['EMPRESA_ID'];
		for($i=0;$i<sizeof($datos);$i++){
			$comentario=$datos[$i]['comentario'];
			if($comentario==null){$comentario='';}
			$query="INSERT INTO os_control_insumos_detalle(
													empresa_id,
													numero_orden_id,
													usuario_id,
													fecha,
													codigo_cups,
													codigo_producto,
													cantidad_utilizada,
													cantidad_danada,
													comentario)
									VALUES(	'$empresa_id',
													$_REQUEST[numero_orden_id],
													".UserGetUID().",
													'$date',
													'".$_REQUEST[codigo_cups]."',
													'".$datos[$i][codigo_producto]."',
													".$datos[$i]['cant_utilizada'].",
													".$datos[$i]['cant_danada'].",
													'$comentario')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Insertar Insumos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}//fin for
		
		
		$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
		$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
		$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
		$_REQUEST['nombre'], $_REQUEST['edad_paciente']);
		
		return $this->salida;
		//return true;
	}//fin UpdateControlInsumos
	//MauroB
		/**
		* Concatena el numero_cumplimiento y la fecha_cumplimiento para crear le numero
		*  de cumplimiento que debe ser visto por el medico y que servira de guia para
		* el controld e los examenes, en el formato que se configuro en la tabla departamentos
		*  para cada uno d elos departamentos existentes en la empresa
		* @param $fecha_cumplimiento
		* @param $numero_cumplimiento
		* @param $departamento
		* @return $cumplimiento
		*/
		function ConvierteCumplimiento($fecha_cumplimiento,$numero_cumplimiento,$departamento){
			list($dbconn) = GetDBconn();
			$query="SELECT	formato_cumplimiento
							FROM		departamentos
							WHERE		departamento = '$departamento'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					echo "<br>Error BD " . $dbconn->ErrorMsg();
					return false;
			}
			$res=$result->fields[0];
			$result->Close();
			if($res=='0'){
				$fecha=substr(str_replace("-","",$fecha_cumplimiento),2);
			}elseif($res=='1'){
				$fecha=substr(str_replace("-","",$fecha_cumplimiento),2,-2);
			}elseif($res=='2'){
				$fecha=substr(str_replace("-","",$fecha_cumplimiento),2,-4);
			}
// 			echo "<br>-->".$fecha;exit;
			$cumplimiento=$fecha."-".$numero_cumplimiento;
			return $cumplimiento;
		}//fin ConvierteCumplimiento

		/**
		*
		*/
		function Imprimir_Pos()
          {
               list($dbconn) = GetDBconn();
				
               //Vector de datos de impresion
               $var['cumplimiento']=$_REQUEST['cumplimiento'];
			$var['departamento']=$_REQUEST['departamento'];
			$var['tipo_id_paciente']=$_REQUEST['tipo_id_paciente'];
			$var['paciente_id']=$_REQUEST['paciente_id'];
			$var['nombre']=$_REQUEST['nombre'];
			$var['razon_social']=$_SESSION['LTRABAJO']['NOM_EMP'];
			$var['nom_depto']=$_SESSION['LTRABAJO']['NOM_DPTO'];
			$var['fecha_cumplimiento']=$_REQUEST['fecha_cumplimiento'];
			$var['descripcion']=$_REQUEST['descripcion'];
			$var['servicio']=$_REQUEST['servicio'];
			$var['numero_orden_id']=$_REQUEST['numero_orden_id'];
               $var['cargo']=$_REQUEST['cargo'];
			
               $query = "SELECT B.plan_descripcion
               		FROM hc_os_solicitudes AS A, planes AS B
                         WHERE A.paciente_id = '".$var['paciente_id']."'
                         AND   A.tipo_id_paciente = '".$var['tipo_id_paciente']."'
                         AND   A.plan_id = B.plan_id
                         LIMIT 1;";
               $result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
                    echo "<br>Error BD " . $dbconn->ErrorMsg();
                    return false;
			}
			$var['plan_descripcion'] = $result->fields[0];

               $query = "SELECT nombre
               		FROM system_usuarios
                         WHERE usuario_id = ".UserGetUID().";";
               $result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
                    echo "<br>Error BD " . $dbconn->ErrorMsg();
                    return false;
			}
			$var['tecnico'] = $result->fields[0];
               
               $query = "SELECT B.cama
               		FROM ingresos AS A, movimientos_habitacion AS B
                         WHERE A.paciente_id = '".$var['paciente_id']."'
                         AND   A.tipo_id_paciente = '".$var['tipo_id_paciente']."'
                         AND   A.estado = '1'
                         AND   B.fecha_egreso IS NULL
                         AND   A.ingreso = B.ingreso;";
               $result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
                    echo "<br>Error BD " . $dbconn->ErrorMsg();
                    return false;
			}
			$var['cama'] = $result->fields[0];
               
               $query = "SELECT numero_orden_id
               		FROM os_maestro_cargos
                         WHERE numero_orden_id = ".$var['numero_orden_id']."
                         AND   cargo = '".$var['cargo']."';";
               $result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
                    echo "<br>Error BD " . $dbconn->ErrorMsg();
                    return false;
			}
			$var['numero_orden'] = $result->fields[0];
               
               if (!IncludeFile("classes/reports/reports.class.php")) {
				$this->error = "No se pudo inicializar la Clase de Reportes";
				$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
				return false;
			}
			
               
			$classReport = new reports;
			$impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
			$reporte=$classReport->PrintReport('pos','app','Os_ListaTrabajo','Imprime_Cumplimiento',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
			if(!$reporte){
					$this->error = $classReport->GetError();
					$this->mensajeDeError = $classReport->MensajeDeError();
					unset($classReport);
					return false;
			}
	
			$resultado=$classReport->GetExecResultado();
			unset($classReport);
			
			if($_REQUEST['sw_get_forma'] != '0')
			{
				$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
				$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
				$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
				$_REQUEST['nombre'], $_REQUEST['edad_paciente'], $_REQUEST['Fecha']);
				
			}
			else
			{
				$this->BuscarOrden($_REQUEST['Fecha']);
				
			}
			return true;
		}
	//fin funciones MauroB
	}//fin clase user

	?>
