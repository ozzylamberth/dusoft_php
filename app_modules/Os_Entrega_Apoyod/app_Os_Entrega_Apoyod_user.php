<?php

/**
 * $Id: app_Os_Entrega_Apoyod_user.php,v 1.6 2006/02/20 14:39:01 ehudes Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de listas de trabajo para entrega de resultados
 */
	
	class app_Os_Entrega_Apoyod_user extends classModulo
	{
		var $limit;
		var $conteo;//para saber cuantos registros encontró

		function app_Os_Entrega_Apoyod_user()
		{
				$this->limit=GetLimitBrowser();
				return true;
		}

		/**
		* La funcion main es la principal y donde se llama FormaPrincipal
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
      unset ($_SESSION[Paciente_Entrega]);
      $_SESSION[Paciente_Entrega]['departamento']=$_REQUEST['departamento'];
      $_SESSION[Paciente_Entrega]['tipo_id_paciente']=$_REQUEST['tipo_id_paciente'];
      $_SESSION[Paciente_Entrega]['paciente_id']=$_REQUEST['paciente_id'];
      $_SESSION[Paciente_Entrega]['nombre']=$_REQUEST['nombre'];
      $_SESSION[Paciente_Entrega]['edad_paciente']=$_REQUEST['edad_paciente'];
      $this->Consultar_Examenes_Paciente();
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
				$query="SELECT b.sw_modo_impresion, c.departamento,c.descripcion as dpto,
								d.descripcion as centro, e.empresa_id,e.razon_social as emp,
								d.centro_utilidad, b.usuario_id
								FROM userpermisos_os_entrega_apoyod b, departamentos c,
								centros_utilidad d,	empresas e
								WHERE    b.usuario_id=".UserGetUID()."
								AND b.departamento=c.departamento
								AND c.centro_utilidad=d.centro_utilidad
								AND	d.empresa_id=e.empresa_id
								AND e.empresa_id=c.empresa_id
								ORDER BY centro";

				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resulta = $dbconn->Execute($query);
				while($data = $resulta->FetchRow())
				{
						$laboratorio[$data['emp']][$data['centro']][$data['dpto']]=$data;
				}
				$url[0]='app';
				$url[1]='Os_Entrega_Apoyod';
				$url[2]='user';
				$url[3]='Menuatencion';
				$url[4]='EntregaApoyod';

				$arreglo[0]='EMPRESA';
				$arreglo[1]='CENTRO UTILIDAD';
				$arreglo[2]='ATENCION DE LISTA DE TRABAJO';

				$this->salida.= gui_theme_menu_acceso('ATENCION ENTREGA DE RESULTADOS',$arreglo,$laboratorio,$url);
				return true;
		}

		/**
		* Menuatencion, esta funcion  es donde desemboca el menu iteractivo creado por arley.
		* @access public
		* @return boolean
		*/
		function Menuatencion()
		{
				$_SESSION['ENTREGA']['EMPRESA_ID']=$_REQUEST['EntregaApoyod']['empresa_id'];
				$_SESSION['ENTREGA']['CENTROUTILIDAD']=$_REQUEST['EntregaApoyod']['centro_utilidad'];
				$_SESSION['ENTREGA']['NOM_CENTRO']=$_REQUEST['EntregaApoyod']['centro'];
				$_SESSION['ENTREGA']['NOM_EMP']=$_REQUEST['EntregaApoyod']['emp'];
				$_SESSION['ENTREGA']['NOM_DPTO']=$_REQUEST['EntregaApoyod']['dpto'];
				$_SESSION['ENTREGA']['DPTO']=$_REQUEST['EntregaApoyod']['departamento'];
				$_SESSION['ENTREGA']['ACCESO']=$_REQUEST['EntregaApoyod']['sw_modo_impresion'];

				unset ($_SESSION['IMAGENES']['LISTAS']);
				if(!$this->FormaMetodoBuscar())
				{
						return false;
				}
				return true;
  	}


		/**
		* Realiza la busqueda según el plan,documento .. de los pacientes que
		* tienen ordenes de servicios pendientes
		* @access private
		* @return boolean
		*/
		function BuscarOrden()
		{
				/*LORENA*/
				  UNSET($_SESSION['PATOLOGIA']['SW_CADAVERES']);
					if($this->VerifyDeptoPatologia()==1){
						if($_REQUEST['infoCadaver']){
							$this->BusquedaDatosPatologiaCadaver($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Nombres'],$_REQUEST['Fecha'],$_REQUEST['opcion_entregados']);
							return true;
						}
						$this->BusquedaDatosPatologia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Nombres'],$_REQUEST['Fecha'],$_REQUEST['opcion_entregados']);
						return true;
					}
				/*FIN LORENA*/

				if ($_REQUEST['Buscar_Orden_Cargar_Session'] != '')
				{
					$tipo_documento=$_REQUEST['TipoDocumento'];
					$documento=$_REQUEST['Documento'];
					$nombres = strtolower($_REQUEST['Nombres']);
					$numero_orden = $_REQUEST['Numero_Orden'];
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
						$fecha=$this->FechaStamp($_REQUEST['Fecha']);
					//filtro adicional de examenes entregados
					$opcion_entregados=$_REQUEST['opcion_entregados'];

					list($dbconn) = GetDBconn();
					$filtroTipoDocumento = '';
					$filtroDocumento='';
					$filtroNombres='';
					$filtroNumeroOrden='';
					$filtroFecha='';
					$filtroEntregados='';

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
							//$filtroNumeroOrden =" AND c.numero_orden_id LIKE '$numero_orden%'";
							$filtroNumeroOrden =" AND c.numero_orden_id = ".$numero_orden."";
					}

					if ($fecha != '')
					{
							$filtroFecha ="AND date(a.fecha_cumplimiento) = date('$fecha')";
					}


					if ($opcion_entregados == 1)  //EXAMENES SIN ENTREGAR
					{
							$filtroEntregados ="AND d.apoyod_entrega_id IS NULL AND d.usuario_id_profesional IS NOT NULL";
					}
					elseif ($opcion_entregados == 2)  //EXAMENES EN PROCESO
					{
							$filtroEntregados ="AND d.apoyod_entrega_id IS NULL AND d.usuario_id_profesional IS NULL";
					}
					elseif ($opcion_entregados == 3)//EXAMENES ENTREGADOS
					{
							$filtroEntregados ="AND d.apoyod_entrega_id IS NOT NULL";
					}
					elseif ($opcion_entregados == 4)//TODOS LOS EXAMENES
					{
							$filtroEntregados ='';
					}
			  }
				else
				{
					list($dbconn) = GetDBconn();
					$filtroTipoDocumento = $_SESSION['BUSQUEDA_ORDEN']['filtroTipoDocumento'];
					$filtroDocumento = $_SESSION['BUSQUEDA_ORDEN']['filtroDocumento'];
					$filtroNombres = $_SESSION['BUSQUEDA_ORDEN']['filtroNombres'];
					$filtroNumeroOrden = $_SESSION['BUSQUEDA_ORDEN']['filtroNumeroOrden'];
					$filtroFecha = $_SESSION['BUSQUEDA_ORDEN']['filtroFecha'];
					$filtroEntregados = $_SESSION['BUSQUEDA_ORDEN']['$filtroEntregados'];
					$_REQUEST['op'] = $_SESSION['BUSQUEDA_ORDEN']['listas'];
					$numero_orden = $_SESSION['BUSQUEDA_ORDEN']['numero_orden'];
				}

				if(empty($_REQUEST['conteo'.$pfj]))
				{
					$query = "SELECT count(*)
					from (SELECT DISTINCT b.fecha_nacimiento, a.departamento, a.tipo_id_paciente,
					a.paciente_id,  btrim(b.primer_nombre||' '||b.segundo_nombre||' '||
					b.primer_apellido|| ' '||b.segundo_apellido,'') as nombre

					FROM os_cumplimientos as a, os_cumplimientos_detalle c left join
					hc_resultados_sistema d on (c.numero_orden_id = d.numero_orden_id),

					pacientes b

					WHERE a.numero_cumplimiento = c.numero_cumplimiento AND
					a.fecha_cumplimiento = c.fecha_cumplimiento AND a.departamento = c.departamento
					and a.paciente_id = b.paciente_id AND a.tipo_id_paciente = b.tipo_id_paciente
					AND a.departamento = '".$_SESSION['ENTREGA']['DPTO']."'
					$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroNumeroOrden $filtroFecha $filtroEntregados) as A";

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
				SELECT DISTINCT b.fecha_nacimiento, a.departamento, a.tipo_id_paciente,
				a.paciente_id,  btrim(b.primer_nombre||' '||b.segundo_nombre||' '||
				b.primer_apellido|| ' '||b.segundo_apellido,'') as nombre


				FROM os_cumplimientos as a, os_cumplimientos_detalle c left join
				hc_resultados_sistema d on (c.numero_orden_id = d.numero_orden_id),
				pacientes b

				WHERE a.numero_cumplimiento = c.numero_cumplimiento AND
				a.fecha_cumplimiento = c.fecha_cumplimiento AND a.departamento = c.departamento
				and a.paciente_id = b.paciente_id AND a.tipo_id_paciente = b.tipo_id_paciente
				AND a.departamento = '".$_SESSION['ENTREGA']['DPTO']."'
				$filtroTipoDocumento $filtroDocumento $filtroNombres $filtroNumeroOrden $filtroFecha $filtroEntregados
				order by nombre
				LIMIT ".$this->limit." OFFSET $Of;";

				if ($_REQUEST['Buscar_Orden_Cargar_Session'] != '')
				{
						unset ($_SESSION['BUSQUEDA_ORDEN']);
						$_SESSION['BUSQUEDA_ORDEN']['filtroTipoDocumento'] = $filtroTipoDocumento;
						$_SESSION['BUSQUEDA_ORDEN']['filtroDocumento'] = $filtroDocumento;
						$_SESSION['BUSQUEDA_ORDEN']['filtroNombres'] = $filtroNombres;
						$_SESSION['BUSQUEDA_ORDEN']['filtroNumeroOrden'] = $filtroNumeroOrden;
						$_SESSION['BUSQUEDA_ORDEN']['filtroFecha'] =  $filtroFecha;
						$_SESSION['BUSQUEDA_ORDEN']['$filtroEntregados'] =  $filtroEntregados;
						$_SESSION['BUSQUEDA_ORDEN']['listas']=$_REQUEST['op'];
						$_SESSION['BUSQUEDA_ORDEN']['numero_orden'] = $numero_orden;
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


		function ConsultaExamenesPaciente()
		{
				list($dbconnect) = GetDBconn();
				$filtro_cumplimiento = '';
        if (!empty($_SESSION['BUSQUEDA_ORDEN']['numero_orden']))
				{
						$query = "SELECT fecha_cumplimiento,  numero_cumplimiento, numero_orden_id
						FROM	os_cumplimientos_detalle
						WHERE numero_orden_id = ".$_SESSION['BUSQUEDA_ORDEN']['numero_orden']."";

						$result = $dbconnect->Execute($query);
						if ($dbconnect->ErrorNo() != 0)
						{
								$this->error = "Error en la busqueda";
								$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
								return false;
						}
						else
						{
							$datos=$result->GetRowAssoc($ToUpper = false);
						}
						$filtro_cumplimiento =" AND b.fecha_cumplimiento = '".$datos[fecha_cumplimiento]."' AND
						b.numero_cumplimiento = '".$datos[numero_cumplimiento]."'";
				}

				$query = "SELECT e.resultado_id, b.fecha_cumplimiento,  b.numero_cumplimiento,
				b.numero_orden_id, b.sw_estado, c.sw_estado as maestro, d.cargo, d.descripcion
				FROM os_cumplimientos a,	os_cumplimientos_detalle as b left join hc_resultados_sistema e
				on (b.numero_orden_id=e.numero_orden_id), os_maestro as c, cups as d
				WHERE a.numero_cumplimiento = b.numero_cumplimiento
				AND	a.fecha_cumplimiento = b.fecha_cumplimiento
				AND	a.departamento = b.departamento
				AND	a.tipo_id_paciente = '".$_SESSION[Paciente_Entrega]['tipo_id_paciente']."'
				and a.paciente_id = '".$_SESSION[Paciente_Entrega]['paciente_id']."'
				AND b.departamento = '".$_SESSION[Paciente_Entrega]['departamento']."'
				AND b.numero_orden_id = c.numero_orden_id
				AND c.cargo_cups = d.cargo
				AND e.apoyod_entrega_id is null
				$filtro_cumplimiento
				order by b.fecha_cumplimiento, b.numero_cumplimiento,
				b.numero_orden_id asc";

				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
				{
						$this->error = "Error en la busqueda";
						$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
						return false;
				}
				else
				{
	  				while (!$result->EOF)
						{
							$fact[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
						}
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


		function tiposParentescosPaciente()
		{
				list($dbconn) = GetDBconn();
				$query="SELECT tipo_parentesco_id,descripcion FROM tipos_parentescos";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al consultar hc_tipos_sanguineos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}else{
					$datos=$result->RecordCount();
					if($datos){
						while(!$result->EOF){
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
					}
				}
				return $vars;
		}

	//cor - clzc - ads
	function InsertarRegistroEntrega()
	{
//  	echo "InsertarRegistroEntrega";
// 	print_r($_REQUEST);
			list($dbconn) = GetDBconn();
      //$dbconn->debug=true;
			$dbconn->BeginTrans();
			if (($_REQUEST['op']=='' OR $_REQUEST['responsable']=='') AND ($_REQUEST['sin_respuesta']==''))
			{
					if ($_REQUEST['op']=='')
					{
						$this->frmError["MensajeError"]="DEBE SELECCIONAR POR LO MENOS UN EXAMEN.";
					}
					if ($_REQUEST['responsable']=='')
					{
						$this->frmError["responsable"]=1;
						$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
					}
					$this->Consultar_Examenes_Paciente();
					return true;
			}

			if ($_REQUEST['responsable']=='1')
			{
				$parentesco1='NULL';
			}
			elseif ($_REQUEST['responsable']=='2')
			{
					if ($_REQUEST['parentesco']=='-1'   OR $_REQUEST['nombre_recibe']=='' OR $_REQUEST['telefono']=='' )
					{
						if ($_REQUEST['parentesco']=='-1')
						{
							$this->frmError["parentesco"]=1;
						}
						if ($_REQUEST['nombre_recibe']=='')
						{
							$this->frmError["nombre_recibe"]=1;
						}
						if ($_REQUEST['telefono']=='')
						{
							$this->frmError["telefono"]=1;
						}
						$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS PARA OTRO RESPONSABLE.";
						$this->Consultar_Examenes_Paciente();
						return true;
					}
					else
					{
						$parentesco="'".$_REQUEST['parentesco']."'";
						$parentesco1="$parentesco";
					}
			}
			elseif ($_REQUEST['responsable']=='3')
			{
				  if ($_REQUEST['tipo_id_funcionario']=='-1' OR $_REQUEST['funcionario_id']==''  OR $_REQUEST['nombre_recibe']=='')
					{
							if ($_REQUEST['tipo_id_funcionario']=='-1' OR $_REQUEST['funcionario_id']=='')
							{
								$this->frmError["identificacion"]=1;
							}
							if ($_REQUEST['nombre_recibe']=='')
							{
								$this->frmError["nombre_recibe"]=1;
							}
							$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS PARA ENTREGAR A FUNCIONARIO.";
							$this->Consultar_Examenes_Paciente();
							return true;
					}
					else
					{
						$parentesco1='NULL';
					}
			}

			
			//realiza el id manual de la tabla
			$query="SELECT nextval('apoyod_entrega_resultados_apoyod_entrega_id_seq')";
			$result=$dbconn->Execute($query);
			$apoyod_entrega_id=$result->fields[0];
			//fin de la operacion

			 $query="INSERT INTO apoyod_entrega_resultados
			(apoyod_entrega_id, tipo_parentesco_id, nombre, telefono, observacion,
			fecha_entrega, sw_tipo_persona, tipo_id_paciente, paciente_id, tipo_id_funcionario,
			funcionario_id)
			VALUES (".$apoyod_entrega_id.", $parentesco1,
			'".$_REQUEST['nombre_recibe']."', '".$_REQUEST['telefono']."',
			'".$_REQUEST['observacion']."', now(), '".$_REQUEST['responsable']."',
			'".$_SESSION[Paciente_Entrega]['tipo_id_paciente']."',
			'".$_SESSION[Paciente_Entrega]['paciente_id']."',
			'".$_REQUEST['tipo_id_funcionario']."','".$_REQUEST['funcionario_id']."')";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar en apoyod_entrega_resultados";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{	//entrega de examenes con resultados
					foreach($_REQUEST['op'] as $index=>$codigo)
					{
						$arreglo=explode(",",$codigo);
						$query="INSERT INTO apoyod_entrega_resultados_detalle
										(apoyod_entrega_id, resultado_id)
										VALUES (".$apoyod_entrega_id.", '".$arreglo[0]."')";
						$resulta=$dbconn->Execute($query);

						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "apoyod_entrega_resultados_detalle1";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						else
						{
							$query="UPDATE hc_resultados_sistema SET
							apoyod_entrega_id = ".$apoyod_entrega_id."
							WHERE resultado_id = '".$arreglo[0]."'";
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
						}
					}
					$dbconn->CommitTrans();
					list($dbconn) = GetDBconn();
					//entrega de examenes sin resultados
					
					foreach($_REQUEST['sin_respuesta'] as $index=>$codigo)
					{
						$numero_orden_id=explode(",",$codigo);
						 $query="INSERT INTO os_imagenes_entrega_sin_resultados
											(os_imagenes_entrega_sin_resultados_id,
											numero_orden_id,
											tipo_parentesco_id,
											nombre,
											telefono,
											observacion,
											fecha_entrega,
											sw_tipo_persona,
											tipo_id_paciente,
											paciente_id,
											tipo_id_funcionario,
											funcionario_id)
							VALUES(	NEXTVAL ('os_imagenes_entrega_sin_resultados_id'),
											'".$numero_orden_id[0]."',
											$parentesco1,
											'".$_REQUEST['nombre_recibe']."',
											'".$_REQUEST['telefono']."',
											'".$_REQUEST['observacion']."',
											'now()',
											'".$_REQUEST['responsable']."',
											'".$_REQUEST['tipo_id_paciente']."',
											'".$_REQUEST['paciente_id']."',
											'".$_REQUEST['tipo_id_funcionario']."',
											'".$_REQUEST['funcionario_id']."'
							)";
						
						$resulta = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en examen_sin_resultados";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						$result->Close();
						//actualiza el estado de la orden de servicio con estado 'a' que es 
						//examen entregado sin resultado
						$this->CambiaEstadoEntregadoSinResultado($numero_orden_id[0],'a');
					}
					
			}
			
			$this->Consultar_Examenes_Paciente();
			return true;
	}


	function ConsultaExamenesEntregados($departamento, $tipo_id_paciente, $paciente_id)
	{
			list($dbconnect) = GetDBconn();
			//$filtroNumeroOrden = $_SESSION['BUSQUEDA_ORDEN']['filtroNumeroOrden'];
			$query = "SELECT a.apoyod_entrega_id, a.sw_tipo_persona,
			a.nombre, a.fecha_entrega, c.numero_orden_id
			FROM 	apoyod_entrega_resultados as a, apoyod_entrega_resultados_detalle as b,
			hc_resultados_sistema c, os_cumplimientos_detalle d
			WHERE a.tipo_id_paciente = '".$tipo_id_paciente."'
			and a.paciente_id = '".$paciente_id."'
			and a.apoyod_entrega_id = b.apoyod_entrega_id
			and	b.resultado_id = c.resultado_id
			and c.numero_orden_id = d.numero_orden_id
			and d.departamento = '".$departamento."'";
			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
					$this->error = "Error en la busqueda";
						$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
						return false;
			}
			else
			{
					while (!$result->EOF)
					{
						$fact[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
					}
			}
			return $fact;
	}

	function ConsultaDetalleExamenesEntregados($departamento, $tipo_id_paciente, $paciente_id, $apoyod_entrega_id)
	{
			list($dbconnect) = GetDBconn();
			//$filtroNumeroOrden = $_SESSION['BUSQUEDA_ORDEN']['filtroNumeroOrden'];

			$query = "SELECT a.tipo_id_funcionario, a.funcionario_id, h.descripcion as parentesco,
			a.telefono, a.apoyod_entrega_id, a.sw_tipo_persona,
			a.nombre, a.fecha_entrega, a.observacion,	b.resultado_id, d.fecha_cumplimiento,
			d.numero_cumplimiento,	d.numero_orden_id,
			f.cargo, f.descripcion

			FROM 	apoyod_entrega_resultados a
			left join tipos_parentescos h on (a.tipo_parentesco_id = h.tipo_parentesco_id),
			apoyod_entrega_resultados_detalle b, hc_resultados_sistema c, os_cumplimientos_detalle d,
			os_maestro as e, cups as f

			WHERE
			a.tipo_id_paciente = '".$tipo_id_paciente."'
			AND a.paciente_id = '".$paciente_id."'
			AND a.apoyod_entrega_id = $apoyod_entrega_id
			AND a.apoyod_entrega_id = b.apoyod_entrega_id
			AND c.apoyod_entrega_id is not null
			AND b.resultado_id = c.resultado_id
			AND c.numero_orden_id=d.numero_orden_id
			AND d.departamento = '".$departamento."'
			AND d.numero_orden_id=e.numero_orden_id
			AND e.cargo_cups = f.cargo
			AND e.sw_estado = 4
			order by d.fecha_cumplimiento, d.numero_cumplimiento,
			d.numero_orden_id asc";

			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
					$this->error = "Error en la busqueda";
						$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
						return false;
			}
			else
			{
					while (!$result->EOF)
					{
						$fact[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
					}
			}
		return $fact;
	}


	function ConsultaExamenesReEntregados($apoyod_entrega_id)
	{
			list($dbconnect) = GetDBconn();
			//$filtroNumeroOrden = $_SESSION['BUSQUEDA_ORDEN']['filtroNumeroOrden'];
			$query = "select a.fecha_entrega, a.observacion, a.apoyod_entrega_id, a.resultado_id,
			b.numero_orden_id, d.cargo, d.descripcion
			from apoyod_reentrega_resultados a, hc_resultados_sistema b, os_maestro c, cups d
			where a.apoyod_entrega_id = '".$apoyod_entrega_id."'
			and a.resultado_id = b.resultado_id and b.numero_orden_id = c.numero_orden_id
			and c.cargo_cups = d.cargo order by b.numero_orden_id, a.fecha_entrega";
			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
					$this->error = "Error en la busqueda";
						$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
						return false;
			}
			else
			{
					while (!$result->EOF)
					{
						$fact[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
					}
			}
			return $fact;
	}

	//cor - clzc - ads
	function InsertarRegistroReEntrega()
	{
			list($dbconn) = GetDBconn();
			if ($_REQUEST['observacion_reentrega']=='')
			{
				$this->frmError["observacion_reentrega"]=1;
				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
				$this->VerDetalleEntrega();
				return true;
			}
			$query="INSERT INTO apoyod_reentrega_resultados
			(apoyod_entrega_id, resultado_id, fecha_entrega, observacion)
			VALUES (".$_REQUEST['apoyod_entrega_id'].", ".$_REQUEST['resultado_id'].",
			now(), '".$_REQUEST['observacion_reentrega']."')";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar en apoyod_entrega_resultados";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
				$_REQUEST[reentrega]=0;
			}
			$this->VerDetalleEntrega();
			return true;
	}


	//DARLING
		/**
		* Separa la fecha del formato timestamp
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
						return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
				}
		}

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

				$x = explode (".",$time[3]);
				return  $time[1].":".$time[2].":".$x[0];
		}


	/*********************************CODIGO LORENA*********************/

		function BusquedaDatosPatologia($TipoDocumento,$Documento,$Nombres,$Fecha,$opcion_entregados){

			list($dbconn) = GetDBconn();
			if(empty($Fecha)){
				$Fecha = date("Y-m-d");
			}elseif($Fecha=='TODAS LAS FECHAS'){
				$Fecha = '';
			}
			$query="SELECT DISTINCT c.fecha_nacimiento,".$_SESSION['ENTREGA']['DPTO']." as departamento,
			c.tipo_id_paciente,c.paciente_id,
			btrim(c.primer_nombre||' '||c.segundo_nombre||' '||
			c.primer_apellido|| ' '||c.segundo_apellido,'') as nombre
			FROM patologias_resultados_solicitudes a,patologias_solicitudes b,pacientes c
			WHERE a.patologia_solicitud_id=b.patologia_solicitud_id AND
			b.tipo_id_paciente=c.tipo_id_paciente AND b.paciente_id=c.paciente_id";
			$query1="SELECT DISTINCT count(*)
			FROM patologias_resultados_solicitudes a,patologias_solicitudes b,pacientes c
			WHERE a.patologia_solicitud_id=b.patologia_solicitud_id AND
			b.tipo_id_paciente=c.tipo_id_paciente AND b.paciente_id=c.paciente_id";
			if($TipoDocumento && $Documento){
				$query.=" b.tipo_id_paciente='$TipoDocumento' AND b.paciente_id LIKE '$Documento%'";
				$query1.=" b.tipo_id_paciente='$TipoDocumento' AND b.paciente_id LIKE '$Documento%'";
			}
			if($Nombres){
				$query.=" AND (upper(c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido) LIKE '%".strtoupper($Nombres)."%')";
				$query1.=" AND (upper(c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido) LIKE '%".strtoupper($Nombres)."%')";
			}
			if($Fecha){
				$query.=" AND date(a.fecha_registro) = date('$Fecha')";
				$query1.=" AND date(a.fecha_registro) = date('$Fecha')";
			}
			if($opcion_entregados==1){
				$query.=" AND a.examen_firmado='1' AND a.entregado='0'";
				$query1.=" AND a.examen_firmado='1' AND a.entregado='0'";
			}elseif($opcion_entregados==2){
				$query.=" AND a.examen_firmado='0' AND a.entregado='0'";
				$query1.=" AND a.examen_firmado='0' AND a.entregado='0'";
			}elseif($opcion_entregados==3){
				$query.=" AND a.examen_firmado='1' AND a.entregado='1'";
				$query1.=" AND a.examen_firmado='1' AND a.entregado='1'";
			}
			if(empty($_REQUEST['conteo'])){
				$result = $dbconn->Execute($query1);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				list($this->conteo)=$result->fetchRow();
			}else{
				$this->conteo=$_REQUEST['conteo'];
			}
			if(!$_REQUEST['Of']){
					$Of='0';
			}else{
				$Of=$_REQUEST['Of'];
			}
			$query.=" LIMIT " . $this->limit . " OFFSET $Of";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
			$this->FormaMetodoBuscar($vars);
			return true;
		}

		function BusquedaDatosPatologiaCadaver($TipoDocumento,$Documento,$Nombres,$Fecha,$opcion_entregados){
			if(empty($Fecha)){
				$Fecha = date("Y-m-d");
			}elseif($Fecha=='TODAS LAS FECHAS'){
				$Fecha = '';
			}
			list($dbconn) = GetDBconn();
			$query="SELECT DISTINCT c.fecha_nacimiento,".$_SESSION['ENTREGA']['DPTO']." as departamento,
			c.tipo_id_paciente,c.paciente_id,
			btrim(c.primer_nombre||' '||c.segundo_nombre||' '||
			c.primer_apellido|| ' '||c.segundo_apellido,'') as nombre
			FROM cadaveres_informes a,cadaveres_recepcion b,pacientes c
			WHERE a.cadaver_id=b.cadaver_id AND
			b.tipo_id_paciente=c.tipo_id_paciente AND b.paciente_id=c.paciente_id";

			$query1="SELECT count(*)
			FROM cadaveres_informes a,cadaveres_recepcion b,pacientes c
			WHERE a.cadaver_id=b.cadaver_id AND
			b.tipo_id_paciente=c.tipo_id_paciente AND b.paciente_id=c.paciente_id";

			if($TipoDocumento && $Documento){
				$query.=" b.tipo_id_paciente='$TipoDocumento' AND b.paciente_id LIKE '$Documento%'";
				$query1.=" b.tipo_id_paciente='$TipoDocumento' AND b.paciente_id LIKE '$Documento%'";
			}
			if($Nombres){
				$query.=" AND (upper(c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido) LIKE '%".strtoupper($Nombres)."%')";
				$query1.=" AND (upper(c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido) LIKE '%".strtoupper($Nombres)."%')";
			}
			if($Fecha &&  $Fecha!='TODAS LAS FECHAS'){
				$query.=" AND date(a.fecha_registro) = date('$Fecha')";
				$query1.=" AND date(a.fecha_registro) = date('$Fecha')";
			}
			if($opcion_entregados==1){
				$query.=" AND a.examen_firmado='1' AND a.entregado='0'";
				$query1.=" AND a.examen_firmado='1' AND a.entregado='0'";
			}elseif($opcion_entregados==2){
				$query.=" AND a.examen_firmado='0' AND a.entregado='0'";
				$query1.=" AND a.examen_firmado='0' AND a.entregado='0'";
			}elseif($opcion_entregados==3){
				$query.=" AND a.examen_firmado='1' AND a.entregado='1'";
				$query1.=" AND a.examen_firmado='1' AND a.entregado='1'";
			}
			if(empty($_REQUEST['conteo'])){
				$result = $dbconn->Execute($query1);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				list($this->conteo)=$result->fetchRow();
			}else{
				$this->conteo=$_REQUEST['conteo'];
			}
			if(!$_REQUEST['Of']){
					$Of='0';
			}else{
				$Of=$_REQUEST['Of'];
			}
			$query.=" LIMIT " . $this->limit . " OFFSET $Of";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
			$_SESSION['PATOLOGIA']['SW_CADAVERES']=1;
			$this->FormaMetodoBuscar($vars);
			return true;
		}


	/**
		* Realiza la busqueda según el departamento donde se este logueado
		* @return boolean
		*/
		function VerifyDeptoPatologia(){
			list($dbconn) = GetDBconn();
			$query="SELECT *
			FROM patologia_departamento
			WHERE departamento='".$_SESSION['ENTREGA']['DPTO']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				$datos=$result->RecordCount();
				if($datos){
					return 1;
				}
			}
			return 0;
		}

		function LlamaEntregaResultadoPatologia(){
			$this->EntregaResultadoPatologia($_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente']);
			return true;
		}

		function LlamaEntregaResultadoPatologiaCad(){
			$this->EntregaResultadoPatologiaCad($_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente']);
			return true;
		}

		function ConsultaExamenesPatologia($tipo_id_paciente,$paciente_id){
		list($dbconn) = GetDBconn();
			$query="SELECT a.resultado_informe_id,a.prefijo,a.examen_firmado,d.descripcion
			FROM patologias_resultados_solicitudes a,patologias_solicitudes b,patologias_tipos_cargos c,tipos_cargos d
			WHERE b.tipo_id_paciente='$tipo_id_paciente' AND b.paciente_id='$paciente_id' AND
			a.patologia_solicitud_id=b.patologia_solicitud_id AND entregado='0' AND a.tipo_cargo=c.tipo_cargo AND
			a.grupo_tipo_cargo=c.grupo_tipo_cargo AND a.prefijo=c.prefijo AND c.tipo_cargo=d.tipo_cargo AND
			c.grupo_tipo_cargo=d.grupo_tipo_cargo";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
			return $vars;
		}

		function InsertarRegistroEntregaPatologia(){
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			if($_REQUEST['op']=='' OR $_REQUEST['responsable']==''){
				if($_REQUEST['op']==''){
					$this->frmError["MensajeError"]="DEBE SELECCIONAR POR LO MENOS UN EXAMEN.";
				}
				if($_REQUEST['responsable']==''){
					$this->frmError["responsable"]=1;
					$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
				}
				$this->EntregaResultadoPatologia($_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente']);
				return true;
			}

			if($_REQUEST['responsable']=='1'){
				$parentesco1='NULL';
			}elseif ($_REQUEST['responsable']=='2'){
				if($_REQUEST['parentesco']=='-1'   OR $_REQUEST['nombre_recibe']=='' OR $_REQUEST['telefono']=='' ){
					if($_REQUEST['parentesco']=='-1'){
						$this->frmError["parentesco"]=1;
					}
					if($_REQUEST['nombre_recibe']==''){
						$this->frmError["nombre_recibe"]=1;
					}
					if($_REQUEST['telefono']==''){
						$this->frmError["telefono"]=1;
					}
					$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS PARA OTRO RESPONSABLE.";
					$this->EntregaResultadoPatologia($_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente']);
					return true;
				}else{
					$parentesco=$_REQUEST['parentesco'];
					$parentesco1="'$parentesco'";
				}
			}elseif ($_REQUEST['responsable']=='3'){
				if($_REQUEST['tipo_id_funcionario']=='-1' OR $_REQUEST['funcionario_id']==''  OR $_REQUEST['nombre_recibe']==''){
					if($_REQUEST['tipo_id_funcionario']=='-1' OR $_REQUEST['funcionario_id']==''){
						$this->frmError["identificacion"]=1;
					}
					if($_REQUEST['nombre_recibe']==''){
						$this->frmError["nombre_recibe"]=1;
					}
					$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS PARA ENTREGAR A FUNCIONARIO.";
					$this->EntregaResultadoPatologia($_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente']);
					return true;
				}else{
					$parentesco1='NULL';
				}
			}
		
			//realiza el id manual de la tabla
			$query="SELECT nextval('apoyod_entrega_resultados_apoyod_entrega_id_seq')";
			$result=$dbconn->Execute($query);
			$apoyod_entrega_id=$result->fields[0];
			//fin de la operacion
			$query="INSERT INTO apoyod_entrega_resultados
			(apoyod_entrega_id, tipo_parentesco_id, nombre, telefono, observacion,
			fecha_entrega, sw_tipo_persona, tipo_id_paciente, paciente_id, tipo_id_funcionario,
			funcionario_id)
			VALUES (".$apoyod_entrega_id.", $parentesco1,
			'".$_REQUEST['nombre_recibe']."', '".$_REQUEST['telefono']."',
			'".$_REQUEST['observacion']."', now(), '".$_REQUEST['responsable']."',
			'".$_REQUEST['tipo_id_paciente']."',
			'".$_REQUEST['paciente_id']."',
			'".$_REQUEST['tipo_id_funcionario']."','".$_REQUEST['funcionario_id']."')";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar en apoyod_entrega_resultados";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				foreach($_REQUEST['op'] as $index=>$codigo){
					(list($prefijo,$resultado)=explode("||//",$codigo));
					$query="INSERT INTO apoyod_entrega_resultados_detalle_patologia
											(apoyod_entrega_id,resultado_informe_id,prefijo)
											VALUES (".$apoyod_entrega_id.", '".$resultado."','".$prefijo."')";

					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
						$this->error = "apoyod_entrega_resultados_detalle2";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}else{
						$query="UPDATE patologias_resultados_solicitudes SET
						entregado = '1'
						WHERE resultado_informe_id = '".$resultado."' AND prefijo='".$prefijo."'";
						$resulta=$dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al actualizar el estado del apoyo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
				}
				$dbconn->CommitTrans();
				$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
				$this->EntregaResultadoPatologia($_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente']);
				return true;
			}
			$this->Consultar_Examenes_Paciente();
			return true;
		}

		function ConsultaExamenesEntregadosPatologia($tipo_id_paciente,$paciente_id){

			list($dbconnect) = GetDBconn();
			//$filtroNumeroOrden = $_SESSION['BUSQUEDA_ORDEN']['filtroNumeroOrden'];
			$query = "SELECT a.apoyod_entrega_id, a.sw_tipo_persona,
			a.nombre, a.fecha_entrega
			FROM  apoyod_entrega_resultados as a
			WHERE a.tipo_id_paciente = '".$tipo_id_paciente."'
			AND a.paciente_id = '".$paciente_id."' ORDER BY a.fecha_entrega DESC";
			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0){
					$this->error = "Error en la busqueda";
						$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
						return false;
			}else{
				while (!$result->EOF){
					$fact[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			return $fact;
		}

		function VerDetalleEntregaPatologia(){
			$this->FormaDatelleEntregaPatologia($_REQUEST['apoyod_entrega_id'],$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente'],
			$_REQUEST['resultado_id'],$_REQUEST['prefijo'],$_REQUEST['descripcion'],$_REQUEST['reentrega']);
			return true;
		}

		function ConsultaDetalleExamenesEntregadosPat($tipo_id_paciente,$paciente_id,$apoyod_entrega_id){
			list($dbconnect) = GetDBconn();
			//$filtroNumeroOrden = $_SESSION['BUSQUEDA_ORDEN']['filtroNumeroOrden'];
			$query = "SELECT a.tipo_id_funcionario, a.funcionario_id, h.descripcion as parentesco,
			a.telefono, a.apoyod_entrega_id, a.sw_tipo_persona,
			a.nombre, a.fecha_entrega, a.observacion,b.prefijo, b.resultado_informe_id,
			d.descripcion
			FROM apoyod_entrega_resultados a
			LEFT JOIN tipos_parentescos h on (a.tipo_parentesco_id = h.tipo_parentesco_id),
			apoyod_entrega_resultados_detalle_patologia b, patologias_resultados_solicitudes c,tipos_cargos d
			WHERE
			a.tipo_id_paciente = '".$tipo_id_paciente."'
			AND a.paciente_id = '".$paciente_id."'
			AND a.apoyod_entrega_id = '".$apoyod_entrega_id."'
			AND a.apoyod_entrega_id = b.apoyod_entrega_id
			AND b.resultado_informe_id=c.resultado_informe_id
			AND b.prefijo=c.prefijo
			AND c.grupo_tipo_cargo=d.grupo_tipo_cargo
			AND c.tipo_cargo=d.tipo_cargo";
			$result = $dbconnect->Execute($query);
			if($dbconnect->ErrorNo() != 0){
				$this->error = "Error en la busqueda";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}else{
				while(!$result->EOF){
					$fact[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			return $fact;
		}

		function InsertarRegistroReEntregaPatologia(){

			list($dbconn) = GetDBconn();

			if ($_REQUEST['observacion_reentrega']==''){
				$this->frmError["observacion_reentrega"]=1;
				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
				$this->FormaDatelleEntregaPatologia($_REQUEST['apoyod_entrega_id'],$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente'],
				$_REQUEST['resultado_id'],$_REQUEST['prefijo'],$_REQUEST['descripcion'],$_REQUEST['reentrega']);
			}
			$query="INSERT INTO apoyod_reentrega_resultados_patologia
			(apoyod_entrega_id, resultado_informe_id,prefijo,fecha_entrega, observacion)
			VALUES (".$_REQUEST['apoyod_entrega_id'].",'".$_REQUEST['resultado_id']."','".$_REQUEST['prefijo']."',
			'".date("Y-m-d H:i:s")."', '".$_REQUEST['observacion_reentrega']."')";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar en apoyod_entrega_resultados";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
				$_REQUEST[reentrega]=0;
			}
			$this->FormaDatelleEntregaPatologia($_REQUEST['apoyod_entrega_id'],$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente']);
			return true;
		}

		function ConsultaExamenesReEntregadosPat($apoyod_entrega_id){
			list($dbconnect) = GetDBconn();
			//$filtroNumeroOrden = $_SESSION['BUSQUEDA_ORDEN']['filtroNumeroOrden'];
			$query = "SELECT a.fecha_entrega,a.observacion, a.apoyod_entrega_id, a.resultado_informe_id,a.prefijo,
			c.descripcion
			FROM apoyod_reentrega_resultados_patologia a, patologias_resultados_solicitudes b, tipos_cargos c
			WHERE a.apoyod_entrega_id = '".$apoyod_entrega_id."'
			AND a.resultado_informe_id = b.resultado_informe_id AND a.prefijo = b.prefijo AND
			b.tipo_cargo = c.tipo_cargo AND b.grupo_tipo_cargo=c.grupo_tipo_cargo";
			$result = $dbconnect->Execute($query);
			if($dbconnect->ErrorNo() != 0){
				$this->error = "Error en la busqueda";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}else{
				while (!$result->EOF){
					$fact[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			return $fact;
		}

		function ConsultaExamenesPatologiaCad($tipo_id_paciente,$paciente_id){
			list($dbconn) = GetDBconn();
			$query="SELECT a.resultado_informe_id,a.prefijo,a.examen_firmado,d.descripcion,a.entregado,a.cadaver_id,
			x.apoyod_entrega_id,y.tipo_id_funcionario, y.funcionario_id, h.descripcion as parentesco,
			y.telefono, y.apoyod_entrega_id, y.sw_tipo_persona,
			y.nombre, y.fecha_entrega, y.observacion
			FROM cadaveres_informes a
			LEFT JOIN apoyod_entrega_resultados_detalle_cadaveres x ON(a.resultado_informe_id=x.resultado_informe_id AND a.prefijo=x.prefijo)
			LEFT JOIN apoyod_entrega_resultados y ON(x.apoyod_entrega_id=y.apoyod_entrega_id)
			LEFT JOIN tipos_parentescos h on (y.tipo_parentesco_id = h.tipo_parentesco_id)
			,cadaveres_recepcion b,cadaveres_tipo_cargo c,tipos_cargos d
			WHERE b.tipo_id_paciente='$tipo_id_paciente' AND b.paciente_id='$paciente_id' AND
			a.cadaver_id=b.cadaver_id AND a.tipo_cargo=c.tipo_cargo AND
			a.grupo_tipo_cargo=c.grupo_tipo_cargo AND a.prefijo=c.prefijo AND c.tipo_cargo=d.tipo_cargo AND
			c.grupo_tipo_cargo=d.grupo_tipo_cargo";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				$vars=$result->GetRowAssoc($toUpper=false);
			}
			return $vars;
		}

		function InsertarRegistroEntregaPatologiaCad(){

			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			if($_REQUEST['op']=='' OR $_REQUEST['responsable']==''){
				if($_REQUEST['op']==''){
					$this->frmError["MensajeError"]="DEBE SELECCIONAR EL EXAMEN.";
				}
				if($_REQUEST['responsable']==''){
					$this->frmError["responsable"]=1;
					$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
				}
				$this->EntregaResultadoPatologiaCad($_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente']);
				return true;
			}

			if($_REQUEST['responsable']=='1'){
				$parentesco1='NULL';
			}elseif ($_REQUEST['responsable']=='2'){
				if($_REQUEST['parentesco']=='-1'   OR $_REQUEST['nombre_recibe']=='' OR $_REQUEST['telefono']=='' ){
					if($_REQUEST['parentesco']=='-1'){
						$this->frmError["parentesco"]=1;
					}
					if($_REQUEST['nombre_recibe']==''){
						$this->frmError["nombre_recibe"]=1;
					}
					if($_REQUEST['telefono']==''){
						$this->frmError["telefono"]=1;
					}
					$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS PARA OTRO RESPONSABLE.";
					$this->EntregaResultadoPatologiaCad($_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente']);
					return true;
				}else{
					$parentesco=$_REQUEST['parentesco'];
					$parentesco1="'$parentesco'";
				}
			}elseif ($_REQUEST['responsable']=='3'){
				if($_REQUEST['tipo_id_funcionario']=='-1' OR $_REQUEST['funcionario_id']==''  OR $_REQUEST['nombre_recibe']==''){
					if($_REQUEST['tipo_id_funcionario']=='-1' OR $_REQUEST['funcionario_id']==''){
						$this->frmError["identificacion"]=1;
					}
					if($_REQUEST['nombre_recibe']==''){
						$this->frmError["nombre_recibe"]=1;
					}
					$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS PARA ENTREGAR A FUNCIONARIO.";
					$this->EntregaResultadoPatologiaCad($_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente']);
					return true;
				}else{
					$parentesco1='NULL';
				}
			}
			//realiza el id manual de la tabla
			$query="SELECT nextval('apoyod_entrega_resultados_apoyod_entrega_id_seq')";
			$result=$dbconn->Execute($query);
			$apoyod_entrega_id=$result->fields[0];
			//fin de la operacion
			$query="INSERT INTO apoyod_entrega_resultados
			(apoyod_entrega_id, tipo_parentesco_id, nombre, telefono, observacion,
			fecha_entrega, sw_tipo_persona, tipo_id_paciente, paciente_id, tipo_id_funcionario,
			funcionario_id)
			VALUES (".$apoyod_entrega_id.", $parentesco1,
			'".$_REQUEST['nombre_recibe']."', '".$_REQUEST['telefono']."',
			'".$_REQUEST['observacion']."', now(), '".$_REQUEST['responsable']."',
			'".$_REQUEST['tipo_id_paciente']."',
			'".$_REQUEST['paciente_id']."',
			'".$_REQUEST['tipo_id_funcionario']."','".$_REQUEST['funcionario_id']."')";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar en apoyod_entrega_resultados";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				foreach($_REQUEST['op'] as $index=>$codigo){
					(list($prefijo,$resultado)=explode("||//",$codigo));
					$query="INSERT INTO apoyod_entrega_resultados_detalle_cadaveres
											(apoyod_entrega_id,resultado_informe_id,prefijo)
											VALUES (".$apoyod_entrega_id.", '".$resultado."','".$prefijo."')";

					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
						$this->error = "apoyod_entrega_resultados_detalle3";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}else{
						$query="UPDATE cadaveres_informes SET
						entregado = '1'
						WHERE resultado_informe_id = '".$resultado."' AND prefijo='".$prefijo."'";
						$resulta=$dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al actualizar el estado del apoyo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
				}
				$dbconn->CommitTrans();
				$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
				$this->EntregaResultadoPatologiaCad($_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente']);
				return true;
			}
			$this->Consultar_Examenes_Paciente();
			return true;
		}

		function InsertarRegistroReEntregaPatologiaCad(){

			list($dbconn) = GetDBconn();
			if ($_REQUEST['observacion_reentrega']==''){
				$this->frmError["observacion_reentrega"]=1;
				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
				$this->FormaDatelleEntregaPatologia($_REQUEST['apoyod_entrega_id'],$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente'],
				$_REQUEST['resultado_id'],$_REQUEST['prefijo'],$_REQUEST['descripcion'],$_REQUEST['reentrega']);
			}
			$query="INSERT INTO apoyod_reentrega_resultados_patologia_cad
			(apoyod_entrega_id, resultado_informe_id,prefijo,fecha_entrega, observacion)
			VALUES (".$_REQUEST['apoyod_entrega_id'].",'".$_REQUEST['resultado_id']."','".$_REQUEST['prefijo']."',
			'".date("Y-m-d H:i:s")."', '".$_REQUEST['observacion_reentrega']."')";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar en apoyod_entrega_resultados";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
				$_REQUEST[reentrega]=0;
			}
			$this->EntregaResultadoPatologiaCad($_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente']);
			return true;
		}

		function ConsultaExamenesReEntregadosPatCad($apoyod_entrega_id){
			list($dbconnect) = GetDBconn();
			//$filtroNumeroOrden = $_SESSION['BUSQUEDA_ORDEN']['filtroNumeroOrden'];
			$query = "SELECT a.fecha_entrega,a.observacion, a.apoyod_entrega_id, a.resultado_informe_id,a.prefijo,
			c.descripcion
			FROM apoyod_reentrega_resultados_patologia_cad a, cadaveres_informes b, tipos_cargos c
			WHERE a.apoyod_entrega_id = '".$apoyod_entrega_id."'
			AND a.resultado_informe_id = b.resultado_informe_id AND a.prefijo = b.prefijo AND
			b.tipo_cargo = c.tipo_cargo AND b.grupo_tipo_cargo=c.grupo_tipo_cargo";
			$result = $dbconnect->Execute($query);
			if($dbconnect->ErrorNo() != 0){
				$this->error = "Error en la busqueda";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}else{
				while (!$result->EOF){
					$fact[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			return $fact;
		}
		//******************************
		//MauroB
		/**
		* Inserta en la tabla os_imagenes_entrega_sin_resultados los datos
		* de las ordenes tomadas sin resultado.
		* @access public
		* @return true
		*/
		function EntregaSinResultado($numero_orden_id,$parentesco1,$nombre_recibe,$telefono,$observacion,
																$responsable,$tipo_id_paciente,$paciente_id,$tipo_id_funcionario,
																$funcionario_id){
			list($dbconnect) = GetDBconn();
			//$dbconn->BeginTrans();
			/*
			if ($_REQUEST['op']=='' OR $_REQUEST['responsable']=='')
			{
					if ($_REQUEST['op']=='')
					{
						$this->frmError["MensajeError"]="DEBE SELECCIONAR POR LO MENOS UN EXAMEN.";
					}
					if ($_REQUEST['responsable']=='')
					{
						$this->frmError["responsable"]=1;
						$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
					}
					$this->Consultar_Examenes_Paciente();
					return true;
			}

			if ($_REQUEST['responsable']=='1')
			{
				$parentesco1='NULL';
			}
			elseif ($_REQUEST['responsable']=='2')
			{
					if ($_REQUEST['parentesco']=='-1'   OR $_REQUEST['nombre_recibe']=='' OR $_REQUEST['telefono']=='' )
					{
						if ($_REQUEST['parentesco']=='-1')
						{
							$this->frmError["parentesco"]=1;
						}
						if ($_REQUEST['nombre_recibe']=='')
						{
							$this->frmError["nombre_recibe"]=1;
						}
						if ($_REQUEST['telefono']=='')
						{
							$this->frmError["telefono"]=1;
						}
						$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS PARA OTRO RESPONSABLE.";
						$this->Consultar_Examenes_Paciente();
						return true;
					}
					else
					{
						$parentesco=$_REQUEST['parentesco'];
						$parentesco1="'$parentesco'";
					}
			}
			elseif ($_REQUEST['responsable']=='3')
			{
				  if ($_REQUEST['tipo_id_funcionario']=='-1' OR $_REQUEST['funcionario_id']==''  OR $_REQUEST['nombre_recibe']=='')
					{
							if ($_REQUEST['tipo_id_funcionario']=='-1' OR $_REQUEST['funcionario_id']=='')
							{
								$this->frmError["identificacion"]=1;
							}
							if ($_REQUEST['nombre_recibe']=='')
							{
								$this->frmError["nombre_recibe"]=1;
							}
							$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS PARA ENTREGAR A FUNCIONARIO.";
							$this->Consultar_Examenes_Paciente();
							return true;
					}
					else
					{
						$parentesco1='NULL';
					}
					
			}
			/**/
			$parentesco=$_REQUEST['parentesco'];
			$query="INSERT INTO os_imagenes_entrega_sin_resultados
											(os_imagenes_entrega_sin_resultados_id,
											numero_orden_id,
											tipo_parentesco_id,
											nombre,
											telefono,
											observacion,
											fecha_entrega,
											sw_tipo_persona,
											tipo_id_paciente,
											paciente_id,
											tipo_id_funcionario,
											funcionario_id)
							VALUES(	NEXTVAL ('os_imagenes_entrega_sin_resultados_id'),
											'".$numero_orden_id."',
											'$parentesco1',
											'".$nombre_recibe."',
											'".$telefono."',
											'".$observacion."',
											'now()',
											'".$responsable."',
											'".$tipo_id_paciente."',
											'".$paciente_id."',
											'".$tipo_id_funcionario."',
											'".$funcionario_id."'
							)";
			
			$result = $dbconnect->Execute($query);
			if($dbconnect->ErrorNo() != 0){
				$this->error = "Error al insertar en examen_resultados";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}
			$result->Close();
			//actualiza el estado de la orden de servicio con estado 'a' que es 
			//examen entregado sin resultado
			$this->CambiaEstadoEntregadoSinResultado($_REQUEST['numero_orden_id'],'a');
			//$this->Consultar_Examenes_Paciente();
			return true;
		}//fin function EntregaSinResultado
		
		/**
		*	Se encarga de cantrolar la grabacion que se debe realizar.
		*	Se mira si es un examen con resultado o sin resultado
		* @access public
		* @return true
		*/
		function ControlGrabacion(){
			$this->InsertarRegistroEntrega();
			return true;
		}//fin function ControlGrabacion
		/**
		*
		* @access public
		* @return true
		*/
		
		function ConsultaExamenSinResultado($numero_orden_id){
			list($dbconnect) = GetDBconn();
			$query = "SELECT	sw_estado
								FROM		os_maestro
								WHERE 	numero_orden_id='$numero_orden_id'";
			$result = $dbconnect->Execute($query);
			if($dbconnect->ErrorNo() != 0){
				$this->error = "Error al consultar examne estregado sin respuesta";
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
		}//fin function ConsultaExamenSinResultado
		
		/**
		*
		* @access public
		* @return true
		*/
		function CambiaEstadoEntregadoSinResultado($numero_orden_id,$estado){
			$sw='';
			if(empty($numero_orden_id))
				{
						$numero_orden_id=$_REQUEST['numero_orden_id'];
						$estado=$_REQUEST['estado'];
						$sw='1';
				}
			list($dbconnect) = GetDBconn();
			 $query = "UPDATE 	os_maestro
								SET 		sw_estado='$estado'
								WHERE		numero_orden_id='$numero_orden_id'";
			$result = $dbconnect->Execute($query);
			if($dbconnect->ErrorNo() != 0){
				$this->error = "Error al actualizar CambiaEstadoEntregadoSinResultado";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}
			$result->Close();
			if($sw=='1'){
				$this->Consultar_Examenes_Paciente();
			}
			return true;
		}//fin function CambiaEstadoEntregadoSinResultado
		
		/**
		*
		* @access public
		* @return true
		*/
		function ActivarEntregaSinResultado(){
		
			$mensaje='ESTA SEGURO QUE DESEA REACTIVAR UN EXAMEN QUE FUE ENTREGADO SIN RESPUESTA PARA REALIZARLE EL DIAGNOSTICO?.';
			$arreglo=array('numero_orden_id'=>$_REQUEST['numero_orden_id'],'estado'=>'3');
			$c='app';
			$m='Os_Entrega_Apoyod';
			$me='CambiaEstadoEntregadoSinResultado';
			$me2='Consultar_Examenes_Paciente';
			$Titulo='REACTIVA EXAMEN SIN RESPUESTA';
			$boton1='ACEPTAR';
			$boton2='CANCELAR';
			$this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
			
			return true;
		}//fin function ActivarEntregaSinResultado
		
			/**
		* Llama la forma ConfirmarAccion (forma de mensaje de dos botones).
	* @ access public
		* @ return boolean
		*/
		function ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2)
		{
				if(empty($Titulo))
				{
						$arreglo=$_REQUEST['arreglo'];
						$Cuenta=$_REQUEST['Cuenta'];
						$c=$_REQUEST['c'];
						$m=$_REQUEST['m'];
						$me=$_REQUEST['me'];
						$me2=$_REQUEST['me2'];
						$mensaje=$_REQUEST['mensaje'];
						$Titulo=$_REQUEST['titulo'];
						$boton1=$_REQUEST['boton1'];
						$boton2=$_REQUEST['boton2'];
				}
				$this->salida=ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,array($c,$m,'user',$me,$arreglo),array($c,$m,'user',$me2,$arreglo));
				return true;
		}

		//fin MauroB


	/*********************************FIN****************************/
	}//fin clase user

	?>
