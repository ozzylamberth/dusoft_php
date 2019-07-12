 <?php

 /**
 * $Id: app_CajaRapida_user.php,v 1.3 2006/03/01 13:28:29 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */

/**
*Contiene los metodos para realizar las autorizaciones.
*/

class app_CajaRapida_user extends classModulo
{
		var $limit;
		var $conteo;

    function app_CajaRapida_user()
    {
            $this->limit=GetLimitBrowser();
            return true;
    }

    /**
		*
    */
    function main()
    {
				list($dbconn) = GetDBconn();
				unset($_SESSION['AUTORIZACIONES']);
				unset($_SESSION['CAJARAPIDA']);
				unset($_SESSION['ARREGLO']);
				unset($_SESSION['SEGURIDAD']['CAJARAPIDA']);
				unset($_SESSION['AUTORIZACIONES']);
				unset($_SESSION['ARREGLO']);
				unset($_SESSION['LABORATORIO']);
				if(!empty($_SESSION['SEGURIDAD']['CAJARAPIDA']))
				{
							$this->salida.= gui_theme_menu_acceso('CAJA RAPIDA',$_SESSION['SEGURIDAD']['CAJARAPIDA']['arreglo'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['centro'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'],ModuloGetURL('system','Menu'));
							return true;
				}
				list($dbconn) = GetDBconn();
				GLOBAL $ADODB_FETCH_MODE;
				$query = "SELECT e.descripcion, d.servicio, d.via_ingreso, a.caja_id, d.departamento, d.descripcion as descripcion3, d. tipo_num_facturas,  d.tipo_num_recibos,
									c.descripcion as descripcion2, c.centro_utilidad, c.empresa_id, b.razon_social as descripcion1,
									d.tipo_factura_id
									FROM userpermisos_cajas_rapidas as a, empresas as b, departamentos as c,
									cajas_rapidas as d, centros_utilidad as e
									WHERE a.usuario_id=".UserGetUID()." and d.departamento=c.departamento
									and c.empresa_id=b.empresa_id and a.caja_id=d.caja_id
									and e.centro_utilidad=c.centro_utilidad
									and e.empresa_id=c.empresa_id";
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resulta=$dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}
				while ($data = $resulta->FetchRow()) {
						$centro[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
						$seguridad[$data['empresa_id']][$data['departamento']][$data['caja_id']]=1;
				}
				$url[0]='app';
				$url[1]='CajaRapida';
				$url[2]='user';
				$url[3]='Principal';
				$url[4]='Caja';
				$arreglo[0]='EMPRESA';
				$arreglo[1]='DEPARTAMENTO';
				$arreglo[2]='CAJA RAPIDA';

				$_SESSION['SEGURIDAD']['CAJARAPIDA']['arreglo']=$arreglo;
				$_SESSION['SEGURIDAD']['CAJARAPIDA']['caja']=$centro;
				$_SESSION['SEGURIDAD']['CAJARAPIDA']['url']=$url;
				$_SESSION['SEGURIDAD']['CAJARAPIDA']['puntos']=$seguridad;
				$this->salida.= gui_theme_menu_acceso('CAJA RAPIDA',$_SESSION['SEGURIDAD']['CAJARAPIDA']['arreglo'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['caja'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'],ModuloGetURL('system','Menu'));
				return true;
    }

		/**
		*
		*/
		function Principal()
		{
				if(empty($_SESSION['CAJARAPIDA']))
				{
							if(empty($_SESSION['SEGURIDAD']['CAJARAPIDA']['puntos'][$_REQUEST['Caja']['empresa_id']][$_REQUEST['Caja']['departamento']][$_REQUEST['Caja']['caja_id']]))
							{
											$this->error = "Error de Seguridad.";
											$this->mensajeDeError = "Violación a la Seguridad.";
											return false;
							}
							$_SESSION['CAJARAPIDA']['EMPRESA']=$_REQUEST['Caja']['empresa_id'];
							$_SESSION['CAJARAPIDA']['CU']=$_REQUEST['Caja']['centro_utilidad'];
							$_SESSION['CAJARAPIDA']['DPTO']=$_REQUEST['Caja']['departamento'];
							$_SESSION['CAJARAPIDA']['SERVICIO']=$_REQUEST['Caja']['servicio'];
							$_SESSION['CAJARAPIDA']['CAJAID']=$_REQUEST['Caja']['caja_id'];
							$_SESSION['CAJARAPIDA']['DPTONOMBRE']=$_REQUEST['Caja']['descripcion2'];
							$_SESSION['CAJARAPIDA']['TIPOFACTURACION']=$_REQUEST['Caja']['tipo_factura_id'];
							$_SESSION['CAJARAPIDA']['TIPOFACTURA']=$_REQUEST['Caja']['tipo_num_facturas'];
							$_SESSION['CAJARAPIDA']['TIPORECIBO']=$_REQUEST['Caja']['tipo_num_recibos'];
							$_SESSION['CAJARAPIDA']['NOM_EMP']=$_REQUEST['Caja']['descripcion1'];
							$_SESSION['CAJARAPIDA']['NOM_CENTRO']=$_REQUEST['Caja']['descripcion'];
				}

				unset($_SESSION['ARREGLO']);
				unset($_SESSION['CAJARAPIDA']['PACIENTE']);
				$this->FormaBuscar();
				return true;
		}

		/**
		*
		*/
		function LlamarFormaBuscar()
		{
				unset($_SESSION['AUTORIZACIONES']);
				unset($_SESSION['ARREGLO']);
				unset($_SESSION['CAJARAPIDA']['PACIENTE']);
				unset($_SESSION['CAJARAPIDA']['VECTOR']);
				unset($_SESSION['LABORATORIO']);

				$_SESSION['CAJARAPIDA']['EMPRESA']=$_REQUEST['Caja']['empresa_id'];
				$_SESSION['CAJARAPIDA']['DPTO']=$_REQUEST['Caja']['departamento'];
				$_SESSION['CAJARAPIDA']['SERVICIO']=$_REQUEST['Caja']['servicio'];
				$_SESSION['CAJARAPIDA']['CAJAID']=$_REQUEST['Caja']['caja_id'];
				$_SESSION['CAJARAPIDA']['DPTONOMBRE']=$_REQUEST['Caja']['descripcion2'];

				$this->FormaBuscar();
				return true;
		}

		/**
		*
		*/
		function LlamarFormaBuscarExt()
		{
				unset($_SESSION['AUTORIZACIONES']);
				unset($_SESSION['ARREGLO']);
				$Contenedor=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['contenedor'];
				$Modulo=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['modulo'];
				$Tipo=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['tipo'];
				$Metodo=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['metodo'];
				$argu=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['argumentos'];

				if(empty($Contenedor) || empty($Modulo) || empty($Tipo) || empty($Metodo))
				{
								$this->error = "CAJA RAPIDA ";
								$this->mensajeDeError = "Los datos de retorno de la caja no son correctos.";
								return false;
				}

				$_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id']=$_SESSION['CAJARAPIDA']['EXT']['PACIENTE']['paciente_id'];
				$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente']=$_SESSION['CAJARAPIDA']['EXT']['PACIENTE']['tipo_id_paciente'];
				$_SESSION['CAJARAPIDA']['PACIENTE']['plan_id']=$_SESSION['CAJARAPIDA']['EXT']['PACIENTE']['plan_id'];

				$this->FormaBuscar();
				return true;
		}

		/**
		*
		*/
		function BuscarPaciente()
		{
				if(!$_REQUEST['Tipo'] || !$_REQUEST['Documento'] || $_REQUEST['plan']==-1){
								if(!!$_REQUEST['Documento']){ $this->frmError["Documento"]=1; }
								if(!$_REQUEST['Tipo']){ $this->frmError["Tipo"]=1; }
								if($Plan==-1){ $this->frmError["plan"]=1; }
								$this->frmError["MensajeError"]="Faltan datos obligatorios.";
								$this->FormaBuscar();
								return true;
				}

				$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_REQUEST['Documento'];
				$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['Tipo'];
				$_SESSION['PACIENTES']['PACIENTE']['plan_id']=$_REQUEST['plan'];
				$_SESSION['PACIENTES']['RETORNO']['argumentos']=array();
				$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
				$_SESSION['PACIENTES']['RETORNO']['modulo']='CajaRapida';
				$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
				$_SESSION['PACIENTES']['RETORNO']['metodo']='RetornoPaciente';

				$this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
				return true;
		}


		/**
		*
		*/
		function RetornoPaciente()
		{			//si se cancelo en proceso de tomar datos del paciente
					if(empty($_SESSION['PACIENTES']['RETORNO']['PASO']))
					{
							unset($_SESSION['PACIENTES']);
							//si lo llama duvan
							if(!empty($_SESSION['CAJARAPIDA']['EXT']))
							{
										$contenedor=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['contenedor'];
										$modulo=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['modulo'];
										$tipo=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['tipo'];
										$metodo=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['metodo'];
										$argumentos=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['argumentos'];

										$this->ReturnMetodoExterno($Contenedor,$Modulo,$Tipo,$Metodo,$argu);
										return true;
							}
							else
							{
									$this->FormaBuscar();
									return true;
							}
					}
					else
					{
								$_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id']=$_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
								$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente']=$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
								$_SESSION['CAJARAPIDA']['PACIENTE']['plan_id']=$_SESSION['PACIENTES']['PACIENTE']['plan_id'];
								unset($_SESSION['PACIENTES']);

								$this->SolitudCargos();
								return true;
					}
		}

		/**
		*
		*/
		function SolitudCargos()
		{
					unset($_SESSION['ARREGLO']);
					//print_r($_SESSION['ARREGLO']);
					$this->frmForma();
					return true;
		}


    /**
  	* Busca los diferentes tipos de responsable (planes)
    * @access public
    * @return array
    */
		function responsables()
		{
					list($dbconn) = GetDBconn();
					$query="SELECT plan_id,plan_descripcion,tercero_id,tipo_tercero_id FROM planes
													WHERE fecha_final >= now() and estado=1 and fecha_inicio <= now() ";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
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

    /**
    * Busca el nombre del paciente
    * @access public
    * @return array
    * @param string tipo de documento
    * @param int numero de documento
    */
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
		function NombrePlan($plan)
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT plan_descripcion
									FROM planes
									WHERE plan_id=$plan";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}
				$vars=$resulta->GetRowAssoc($ToUpper = false);
				return $vars;
		}
//-------------------------APOYOS-----------------------------------------------

		/**
		*
		*/
		function Apoyos()
		{
					$this->frmForma();
					return true;
		}

		/**
		*
		*/
		function tipos()
		{
				list($dbconnect) = GetDBconn();
				$query= "SELECT apoyod_tipo_id, descripcion
								FROM apoyod_tipos";
				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
				{
					$this->error = "Error al buscar en la tabla apoyod_tipos";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
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
				return $vector;
		}

		/**
		*
		*/
		function GetForma()
		{
				if($_REQUEST['accionapoyo']=='Busqueda_Avanzada')
				{
							$vectorA= $this->Busqueda_Avanzada();
							$this-> frmForma_Seleccion_Apoyos($vectorA);
							return true;
				}

				if($_REQUEST['accionapoyo']=='insertar_varias')
				{
						if(empty($_REQUEST['opapoyo']))
						{
								$this->frmError["MensajeError"]="Debe realizar alguna solicitud.";
								$this->frmForma();
								return true;
						}
						$this->Insertar_Varias_Solicitudes();
						return true;
				}

				if($_REQUEST['accionapoyo']=='eliminar')
				{
						$this->Eliminar_Apoyod_Solicitado($_REQUEST['hc_os_solicitud_idapoyo']);
						return true;
				}

				if($_REQUEST['accionapoyo']=='observacion')
				{
						$this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_idapoyo'],$_REQUEST['cargoapoyo'],$_REQUEST['descripcionapoyo'], $_REQUEST['observacionapoyo']);
						return true;
				}

				if($_REQUEST['accionapoyo']=='modificar')
				{
							$this->Modificar_Apoyod_Solicitado($_REQUEST['hc_os_solicitud_idapoyo']);
							return true;
				}

				if($_REQUEST['accionapoyo']=='Busqueda_Avanzada_Diagnosticos')
				{
							$vectorD= $this->Busqueda_Avanzada_Diagnosticos();
							$this-> frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_idapoyo'],$_REQUEST['cargoapoyo'],$_REQUEST['descripcionapoyo'], $_REQUEST['observacionapoyo'],$vectorD);
							return true;
				}

				if($_REQUEST['accionapoyo']=='insertar_varios_diagnosticos')
				{
						$this->Insertar_Varios_Diagnosticos();
						$this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_idapoyo'],$_REQUEST['cargoapoyo'],$_REQUEST['descripcionapoyo'], $_REQUEST['observacionapoyo']);
						return true;
				}

				if($_REQUEST['accionapoyo']=='eliminar_diagnostico')
				{
						$this->Eliminar_Diagnostico_Solicitado($_REQUEST['hc_os_solicitud_idapoyo'], $_REQUEST['codigoapoyo']);
						$this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_idapoyo'],$_REQUEST['cargoapoyo'],$_REQUEST['descripcionapoyo'], $_REQUEST['observacionapoyo']);
						return true;
				}
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

			if($opcion != '001' && !empty($opcion))
			{
				$filtroTipoCargo=" AND a.grupo_tipo_cargo = '$opcion'";
			}

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
						$query = "SELECT count(*)
						FROM cups a,apoyod_tipos b, departamentos_cargos c
						WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id AND c.cargo = a.cargo
						AND c.departamento = '".$_SESSION['CAJARAPIDA']['DPTO']."'
						$filtroTipoCargo	$busqueda1 $busqueda2";
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
			{   $this->conteo=$_REQUEST['conteoapoyo'];  }
			if(!$_REQUEST['Ofapoyo'])
			{   $Of='0';  }
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

	 		$query = "SELECT a.cargo, a.descripcion, b.apoyod_tipo_id, b.descripcion as tipo
			FROM cups a,apoyod_tipos b, departamentos_cargos c
			WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id AND c.cargo = a.cargo
			AND c.departamento = '".$_SESSION['CAJARAPIDA']['DPTO']."'
			$filtroTipoCargo	$busqueda1 $busqueda2 order by b.apoyod_tipo_id, a.cargo
			LIMIT ".$this->limit." OFFSET $Of;";
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
				$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
			}
			$resulta->Close();

			if($this->conteo==='0')
			{       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
							return false;
			}
			return $var;
	}

		/**
		*
		*/
		function Insertar_Varias_Solicitudes()
		{
				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
											foreach($_REQUEST['opapoyo'] as $index=>$codigo)
				{
						$query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
						$result=$dbconn->Execute($query1);
						$hc_os_solicitud_id=$result->fields[0];
						$arreglo=explode(",",$codigo);
						$query2="INSERT INTO hc_os_solicitudes
										(hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id, paciente_id, tipo_id_paciente)
										VALUES($hc_os_solicitud_id,NULL,
											  '".$arreglo[0]."', 'APD',
											  ".$_SESSION['CAJARAPIDA']['PACIENTE']['plan_id'].",
                                                         '".$_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id']."',
                                                         '".$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente']."')";
						$resulta=$dbconn->Execute($query2);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al insertar en hc_os_solicitudes";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						else
						{
								$resulta->Close();
								$query3="INSERT INTO hc_os_solicitudes_apoyod
																(hc_os_solicitud_id, apoyod_tipo_id)
											VALUES($hc_os_solicitud_id, '".$arreglo[1]."');";
								$resulta1=$dbconn->Execute($query3);
								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al insertar en hc_os_solicitudes_apoyod";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
								}
								else
								{
											$resulta1->Close();
											$query3="INSERT INTO hc_os_solicitudes_caja_rapida
															VALUES($hc_os_solicitud_id,'".$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente']."',
															'".$_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id']."',
															'now()','".UserGetUID()."','".$_SESSION['CAJARAPIDA']['CAJAID']."');";
											$resulta1=$dbconn->Execute($query3);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->error = "Error al insertar en hc_os_solicitudes_apoyod";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollbackTrans();
												return false;
											}
											$resulta1->Close();
								}
						}
				}
				$dbconn->CommitTrans();
				$_SESSION['ARREGLO']['AUTORIZACIONES'][$hc_os_solicitud_id][$arreglo[0]]['TARIFARIO'][$_SESSION['CAJARAPIDA']['SERVICIO']]['']=$hc_os_solicitud_id;
				$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
				$this->Apoyos();
				return true;
		}


		function Eliminar_Apoyod_Solicitado($hc_os_solicitud_id)
		{
						list($dbconn) = GetDBconn();
						$dbconn->BeginTrans();
						$query="DELETE FROM hc_os_solicitudes_diagnosticos
									WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0){
							$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
							$dbconn->RollbackTrans();
							return false;
						}
						else
						{
									$query1="DELETE FROM hc_os_solicitudes_apoyod
									WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
									$resulta1=$dbconn->Execute($query1);
									if ($dbconn->ErrorNo() != 0)
									{
										$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
										$dbconn->RollbackTrans();
										return false;
									}
									else
									{
											$query2="DELETE FROM hc_os_solicitudes_caja_rapida
											WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
											$resulta1=$dbconn->Execute($query2);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
												$dbconn->RollbackTrans();
												return false;
											}

											$query2="DELETE FROM hc_os_solicitudes
											WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
											$resulta1=$dbconn->Execute($query2);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
												$dbconn->RollbackTrans();
												return false;
											}
											else
											{
													$dbconn->CommitTrans();
													unset($_SESSION['ARREGLO']['AUTORIZACIONES'][$hc_os_solicitud_id]);
													$this->frmError["MensajeError"]="SOLICITUD ELIMINADA SATISFACTORIAMENTE.";
											}
									}
						}
						$this->Apoyos();
						return true;
		}

		/**
		*
		*/
		function Modificar_Apoyod_Solicitado($hc_os_solicitud_id)
		{
					list($dbconn) = GetDBconn();
					$obs = $_REQUEST['obsapoyo'];
					$query= "UPDATE hc_os_solicitudes_apoyod SET observacion = '$obs'
									WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al actualizar la observacion en hc_os_solicitudes_apoyod";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					$this->Apoyos();
					return true;
		}

		/**
		*
		*/
		function Insertar_Varios_Diagnosticos()
		{
				list($dbconn) = GetDBconn();
				foreach($_REQUEST['opDapoyo'] as $index=>$codigo)
				{
						$arreglo=explode(",",$codigo);
							$query="INSERT INTO hc_os_solicitudes_diagnosticos
												(hc_os_solicitud_id, diagnostico_id)
												VALUES
													('".$arreglo[0]."', '".$arreglo[1]."')";
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al insertar en hc_os_solicitudes";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$this->frmError["MensajeError"]="EL DIAGNOSTICO YA FUE ASIGNADO.";
							return false;
						}
						else
						{
								$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
						}
				}

			return true;
		}

	/**
	*
	*/
	function Busqueda_Avanzada_Diagnosticos()
	{
				list($dbconn) = GetDBconn();
				$codigo       = STRTOUPPER ($_REQUEST['codigoapoyo']);
				$diagnostico  =STRTOUPPER($_REQUEST['diagnosticoapoyo']);

				$busqueda1 = '';
				$busqueda2 = '';

				if ($codigo != '')
				{
					$busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
				}

				if (($diagnostico != '') AND ($codigo != ''))
				{
					$busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
				}

				if (($diagnostico != '') AND ($codigo == ''))
				{
					$busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
				}

				if(empty($_REQUEST['conteoapoyo']))
				{
					$query = "SELECT count(*)
								FROM diagnosticos
								$busqueda1 $busqueda2";
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
					$this->conteo=$_REQUEST['conteoapoyo'];
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
					$query = "
							SELECT diagnostico_id, diagnostico_nombre
							FROM diagnosticos
							$busqueda1 $busqueda2 order by diagnostico_id
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
				{       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
								return false;
				}
				return $var;
	}

		/*
		*
		*/
		function Consulta_Solicitud_Apoyod($k)
		{
				list($dbconnect) = GetDBconn();
			 	$query= "SELECT a.*, b.cargo, b.plan_id, b.os_tipo_solicitud_id, e.observacion,
									c.descripcion, d.descripcion as tipo,
									informacion_cargo('".$_SESSION['SOLICITUD']['PACIENTE']['plan_id']."',b.cargo,'')
									FROM hc_os_solicitudes_caja_rapida as a, hc_os_solicitudes as b
									left join hc_os_solicitudes_apoyod e on (b.hc_os_solicitud_id = e.hc_os_solicitud_id),
									cups c, apoyod_tipos d
									WHERE a.tipo_id_paciente='".$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente']."'
									and a.paciente_id='".$_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id']."'
									and a.hc_os_solicitud_id=$k
									and a.hc_os_solicitud_id=b.hc_os_solicitud_id and b.sw_estado=1
									and b.cargo=c.cargo and e.apoyod_tipo_id=d.apoyod_tipo_id
									order by a.hc_os_solicitud_id";
				$result = $dbconnect->Execute($query);

				if ($dbconnect->ErrorNo() != 0)
				{
					$this->error = "Error al buscar en la consulta de solictud de apoyos";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
					return false;
				}
				else
				{
						$vector=$result->GetRowAssoc($ToUpper = false);
				}
				return $vector;
		}

		/**
		*
		*/
		function Diagnosticos_Solicitados($hc_os_solicitud_id)
		{
				list($dbconnect) = GetDBconn();
				$query= "select a.diagnostico_id, a.diagnostico_nombre
				FROM diagnosticos a, hc_os_solicitudes_diagnosticos b
				WHERE b.hc_os_solicitud_id = $hc_os_solicitud_id AND a.diagnostico_id = b.diagnostico_id";
				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
				{
					$this->error = "Error al buscar en la tabla apoyod_tipos";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
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
				return $vector;
		}


		/**
		*
		*/
		function Cancelar()
		{
				if(!empty($_SESSION['ARREGLO']['AUTORIZACIONES']))
				{
						list($dbconn) = GetDBconn();
						foreach($_SESSION['ARREGLO']['AUTORIZACIONES'] as $k => $v)
						{
								$dbconn->BeginTrans();
								$query="DELETE FROM hc_os_solicitudes_diagnosticos
											WHERE hc_os_solicitud_id = $k";
								$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0){
									$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
									$dbconn->RollbackTrans();
									return false;
								}
								else
								{
											$query1="DELETE FROM hc_os_solicitudes_apoyod
											WHERE hc_os_solicitud_id = $k";
											$resulta1=$dbconn->Execute($query1);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
												$dbconn->RollbackTrans();
												return false;
											}
											else
											{
													$query2="DELETE FROM hc_os_solicitudes_caja_rapida
													WHERE hc_os_solicitud_id = $k";
													$resulta1=$dbconn->Execute($query2);
													if ($dbconn->ErrorNo() != 0)
													{
														$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
														$dbconn->RollbackTrans();
														return false;
													}

													$query2="DELETE FROM hc_os_solicitudes
													WHERE hc_os_solicitud_id = $k";
													$resulta1=$dbconn->Execute($query2);
													if ($dbconn->ErrorNo() != 0)
													{
														$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
														$dbconn->RollbackTrans();
														return false;
													}
											}
								}
						}
						$dbconn->CommitTrans();
			}
			if(!empty($_SESSION['CAJARAPIDA']['EXT']))
			{
					$contenedor=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['contenedor'];
					$modulo=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['modulo'];
					$tipo=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['tipo'];
					$metodo=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['metodo'];
					$argumentos=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['argumentos'];
					$this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
					return true;
			}
			else
			{
					$this->Principal();
					return true;
			}
	}


//--------------------------------AUTORIZACION-----------------------------------------
    /**
    *
    */
    function PedirAutorizacion()
    {
					if(empty($_SESSION['ARREGLO']['AUTORIZACIONES']))
					{
							$this->frmError["MensajeError"]="Debe realizar alguna solicitud.";
							$this->frmForma();
							return true;
					}
					unset($_SESSION['AUTORIZACIONES']);
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO']=$_SESSION['ARREGLO']['AUTORIZACIONES'];
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']='NULL';
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id'];
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente'];
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']='CAJARAPIDA';
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_SESSION['CAJARAPIDA']['PACIENTE']['plan_id'];
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array();
					$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
					$_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='CajaRapida';
					$_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
					$_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='RetornoAutorizacion';

					$this->ReturnMetodoExterno('app','CentroAutorizacion','user','ValidarCentroAutorizacion');
					return true;
    }

    /**
    * Llama el modulo de autorizaciones
    * @access public
    * @return boolean
    */
    function RetornoAutorizacion()
    {
					$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
					$_SESSION['CAJARAPIDA']['PACIENTE']['rango']=$_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
					$_SESSION['CAJARAPIDA']['PACIENTE']['semanas']=$_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
					$Mensaje=$_SESSION['AUTORIZACIONES']['RETORNO']['Mensaje'];
					$_SESSION['CAJARAPIDA']['Autorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion'];
					$_SESSION['CAJARAPIDA']['NumAutorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
					$_SESSION['CAJARAPIDA']['observacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['ObservacionesOS'];

					list($dbconn) = GetDBconn();

					if(!empty($_SESSION['CAJARAPIDA']['Autorizacion'])
						AND	empty($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']))
					{
							$this->FormaBuscar();
							return true;
					}

					unset($_SESSION['AUTORIZACIONES']);
					if(empty($_SESSION['CAJARAPIDA']['Autorizacion']))
					{
								if(empty($_SESSION['CAJARAPIDA']['NumAutorizacion']))
								{   $Mensaje = 'No se pudo realizar la Autorización para la Orden.';   }
								$accion=ModuloGetURL('app','CajaRapida','user','Apoyos');
								if(!$this-> FormaMensaje($Mensaje,'CAJA RAPIDA',$accion,'')){
								return false;
								}
								return true;
					}

					$query = "    (select a.hc_os_solicitud_id
													from hc_os_autorizaciones as a
													where (a.autorizacion_int=".$_SESSION['CAJARAPIDA']['NumAutorizacion']." OR
													a.autorizacion_ext=".$_SESSION['CAJARAPIDA']['NumAutorizacion']."))
													union
													(select a.hc_os_solicitud_id
													from hc_os_autorizaciones as a
													where (a.autorizacion_int=".$_SESSION['CAJARAPIDA']['NumAutorizacion']." OR
													a.autorizacion_ext=".$_SESSION['CAJARAPIDA']['NumAutorizacion']."))";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error select ";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					while(!$result->EOF)
					{
									$query = "UPDATE hc_os_solicitudes SET
																			sw_estado=0
																			WHERE hc_os_solicitud_id=".$result->fields[0]."";
									$results=$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error UPDATE  hc_os_solicitudes ";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$dbconn->RollbackTrans();
													return false;
									}
									$result->MoveNext();
									$results->Close();
					}
					$result->Close();

					if(empty($_SESSION['CAJARAPIDA']['Autorizacion'])
					AND !empty($_SESSION['CAJARAPIDA']['NumAutorizacion']))
					{
											$Mensaje = 'No se Autorizo la Orden.';
											$accion=ModuloGetURL('app','CajaRapida','user','LlamarFormaBuscar');
											if(!$this-> FormaMensaje($Mensaje,'CAJA RAPIDA',$accion,'')){
											return false;
											}
											return true;
					}

					$query = "(select e.fecha_registro as fecha, a.hc_os_solicitud_id,b.cargo as cargos,b.plan_id,b.os_tipo_solicitud_id,
													n.cargo,n.tarifario_id,h.descripcion, r.descripcion as descar
													from hc_os_autorizaciones as a,hc_os_solicitudes as b
													left join tarifarios_equivalencias as n on(n.cargo_base=b.cargo)
													left join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id),
													hc_os_solicitudes_caja_rapida as e, cups as r
													where (a.autorizacion_int=".$_SESSION['CAJARAPIDA']['NumAutorizacion']." OR
													a.autorizacion_ext=".$_SESSION['CAJARAPIDA']['NumAutorizacion'].") and a.hc_os_solicitud_id=b.hc_os_solicitud_id
													and a.hc_os_solicitud_id=e.hc_os_solicitud_id
													and r.cargo=b.cargo order by a.hc_os_solicitud_id)";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error select ";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}

					while(!$result->EOF)
					{
									$var[]=$result->GetRowAssoc($ToUpper = false);
									$result->MoveNext();
					}
					$result->Close();
					$this->FormaListadoCargos($var);
					return true;
    }

//-------------------------OS-------------------------------------------

    /**
    *
    */
    function CrearOrdenServicio()
    {
            $f=0;
            foreach($_REQUEST as $k => $v)
            {
                if(substr_count($k,'Combo'))
                {
											if($v!=-1)
											{		//0 hc_os_solicitud_id
													$arr=explode(',',$v);
													$d=0;
													foreach($_REQUEST as $ke => $va)
													{
															if(substr_count($ke,'Op'))
															{		// 0 solicitud_id
																	$var=explode(',',$va);
																	if($var[0]==$arr[0])
																	{  $d=1;  }
															}
													}
													if($d==0)
													{
																	$this->frmError["MensajeError"]="ERROR DATOS VACIOS: Debe elegir algun Cargo.";
																	$this->FormaListadoCargos($_REQUEST['datos']);
																	return true;
													}
											}
                }
            }
						$auto=$_SESSION['CAJARAPIDA']['NumAutorizacion'];
						$plan=$_SESSION['CAJARAPIDA']['PACIENTE']['plan_id'];
						$rango=$_SESSION['CAJARAPIDA']['PACIENTE']['rango'];
						$empresa=$_SESSION['CAJARAPIDA']['EMPRESA'];
						$afiliado=$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_afiliado_id'];
						$semana=$_SESSION['CAJARAPIDA']['PACIENTE']['semanas'];
						$paciente=$_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id'];
						$tipo=$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente'];
						$msg=$_SESSION['CAJARAPIDA']['observacion'];
						$servicio=$_SESSION['CAJARAPIDA']['SERVICIO'];

						list($dbconn) = GetDBconn();
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
						VALUES($orden,".$auto.",NULL,".$plan.",'".$afiliado."',
						'".$rango."',".$semana.",'".$servicio."','".$tipo."','".$paciente."',".UserGetUID().",'now()','".$msg."')";
						$dbconn->BeginTrans();
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error INSERT INTO os_ordenes_servicios";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
						}
						else
						{
								foreach($_REQUEST as $k => $v)
								{
												if(substr_count($k,'Combo'))
												{
																//0 hc_os_solicitud_id 2 tipo_id_tercero o dpto 1 tercero_id o departo si es departamento(interna)
																//3 tarifario 4 cargo 5 cargocups 6 fecha 7 plan_proveedor
																//$arr[6]=date('Y-m-d');
																$arr=explode(',',$v);
																$query = "select * from os_tipos_periodos_planes
																										where plan_id=".$plan."
																										and cargo='$arr[5]'";
																$result=$dbconn->Execute($query);
																if ($dbconn->ErrorNo() != 0) {
																				$this->error = "Error os_tipos_periodos_planes";
																				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																				return false;
																}
																if(!$result->EOF)
																{
																				$var=$result->GetRowAssoc($ToUpper = false);
																				$Fecha=$this->FechaStamp($arr[6]);
																				$infoCadena = explode ('/',$Fecha);
																				$intervalo=$this->HoraStamp($arr[6]);
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
																				$query = "select * from os_tipos_periodos_tramites
																														where cargo='$arr[5]'";
																				$result=$dbconn->Execute($query);
																				if ($dbconn->ErrorNo() != 0) {
																								$this->error = "Error os_tipos_periodos_tramites";
																								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																								return false;
																				}
																				if(!$result->EOF)
																				{
																										$var=$result->GetRowAssoc($ToUpper = false);
																										$Fecha=$this->FechaStamp($arr[6]);
																										$infoCadena = explode ('/',$Fecha);
																										$intervalo=$this->HoraStamp($arr[6]);
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
																										$var=$result->GetRowAssoc($ToUpper = false);
																										$Fecha=$this->FechaStamp($arr[6]);
																										$infoCadena = explode ('/',$Fecha);
																										$intervalo=$this->HoraStamp($arr[6]);
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
																VALUES($numorden,$orden,1,'$venc',$arr[0],'$fechaAct',1,'$arr[5]','$refrendar')";
																$dbconn->Execute($query);
																if ($dbconn->ErrorNo() != 0) {
																				$this->error = "Error INSERT INTO os_maestro";
																				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																				$dbconn->RollbackTrans();
																				return false;
																}
																else
																{
																				foreach($_REQUEST as $ke => $va)
																				{
																						if(substr_count($ke,'Op'))
																						{		// 0 solicitud_id 1 cargo 2 tarifario
																								$var=explode(',',$va);
																								if($var[0]==$arr[0])
																								{
																										$query = "INSERT INTO os_maestro_cargos
																																						(numero_orden_id,
																																						tarifario_id,
																																						cargo)
																										VALUES($numorden,'$var[2]','$var[1]')";
																										$dbconn->Execute($query);
																										if ($dbconn->ErrorNo() != 0) {
																														$this->error = "Error INSERT INTO os_maestro_cargos";
																														$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																														$dbconn->RollbackTrans();
																														return false;
																										}
																								}
																						}
																				}

																				$query = "    (select e.fecha,a.hc_os_solicitud_id,b.cargo as cargos,b.plan_id,b.os_tipo_solicitud_id,
																																n.cargo,n.tarifario_id,h.descripcion
																																from hc_os_autorizaciones as a,hc_os_solicitudes as b left join tarifarios_equivalencias as n on(n.cargo_base=b.cargo)
																																left join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id), hc_evoluciones as e
																																where (a.autorizacion_int=".$auto." OR
																																a.autorizacion_ext=".$auto.") and a.hc_os_solicitud_id=b.hc_os_solicitud_id and e.evolucion_id=b.evolucion_id)
																																union
																																(select e.fecha,a.hc_os_solicitud_id,b.cargo as cargos,b.plan_id,b.os_tipo_solicitud_id,
																																n.cargo,n.tarifario_id,h.descripcion
																																from hc_os_autorizaciones as a,hc_os_solicitudes as b left join tarifarios_equivalencias as n on(n.cargo_base=b.cargo)
																																left join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id), hc_os_solicitudes_manuales as e
																																where (a.autorizacion_int=".$auto." OR
																																a.autorizacion_ext=".$auto.") and a.hc_os_solicitud_id=b.hc_os_solicitud_id and a.hc_os_solicitud_id=e.hc_os_solicitud_id)";
																				$result = $dbconn->Execute($query);
																				if ($dbconn->ErrorNo() != 0) {
																								$this->error = "Error select ";
																								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																								return false;
																				}
																				while(!$result->EOF)
																				{
																								$query = "UPDATE hc_os_solicitudes SET
																																																sw_estado=0
																																		WHERE hc_os_solicitud_id=".$result->fields[1]."";
																								$dbconn->Execute($query);
																								if ($dbconn->ErrorNo() != 0) {
																												$this->error = "Error UPDATE  hc_os_solicitudes ";
																												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																												$dbconn->RollbackTrans();
																												return false;
																								}
																								$result->MoveNext();
																				}
																				$result->Close();
																				//si es interna
																				if($arr[2]=='dpto')
																				{
																										$query = "INSERT INTO os_internas
																																												(numero_orden_id,
																																												cargo,
																																												departamento)
																																				VALUES($numorden,'$arr[5]','$arr[1]')";
																				}
																				else
																				{
																												$query = "INSERT INTO os_externas
																																												(numero_orden_id,
																																												empresa_id,
																																												tipo_id_tercero,
																																												tercero_id,
																																												cargo,
																																												plan_proveedor_id)
																																				VALUES($numorden,'".$empresa."','$arr[2]','$arr[1]','$arr[5]',$arr[7])";
																				}
																				$dbconn->Execute($query);
																				if ($dbconn->ErrorNo() != 0) {
																								$this->error = "Error INTO os_externas o  os_internas";
																								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																								$dbconn->RollbackTrans();
																								return false;
																				}
																}//else
												}//fin sub
								}//foreach del request

								$_SESSION['LABORATORIO']['EMPRESA_ID']=$_SESSION['CAJARAPIDA']['EMPRESA'];
								$_SESSION['LABORATORIO']['DPTO']=$_SESSION['CAJARAPIDA']['DPTO'];
								$_SESSION['LABORATORIO']['CENTROUTILIDAD']=$_SESSION['CAJARAPIDA']['CU'];
								$_SESSION['LABORATORIO']['CAJAID']=$_SESSION['CAJARAPIDA']['CAJAID'];
								$_SESSION['LABORATORIO']['TIPOFACTURA']=$_SESSION['CAJARAPIDA']['TIPOFACTURA'];
								$_SESSION['LABORATORIO']['TIPORECIBO']=$_SESSION['CAJARAPIDA']['TIPORECIBO'];
								$_SESSION['LABORATORIO']['NOM_CENTRO']=$_SESSION['CAJARAPIDA']['NOM_CENTRO'];
								$_SESSION['LABORATORIO']['NOM_EMP']=$_SESSION['CAJARAPIDA']['NOM_EMP'];
								$_SESSION['LABORATORIO']['NOM_DPTO']=$_SESSION['CAJARAPIDA']['DPTONOMBRE'];
								$_SESSION['LABORATORIO']['TIPOFACTURACION']=$_SESSION['CAJARAPIDA']['TIPOFACTURACION'];
								$_SESSION['LABORATORIO']['CAJARAPIDA']=TRUE;
								$_SESSION['LABORATORIO']['RETORNO']['contenedor']='app';
								$_SESSION['LABORATORIO']['RETORNO']['modulo']='CajaRapida';
								$_SESSION['LABORATORIO']['RETORNO']['tipo']='user';
								$_SESSION['LABORATORIO']['RETORNO']['metodo']='LlamarFormaBuscar';
								$dbconn->CommitTrans();
								//$this->ReturnMetodoExterno('app','Os_Atencion','user','FrmOrdenar',array('nombre'=>$_SESSION['CAJARAPIDA']['PACIENTE']['nombre'],'tipoid'=>$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente'],'idp'=>$_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id'],'cajarapida'=>'retorno'));
								//return true;
								$Mensaje = 'La Orden de Servicio No. '.$orden.' Fue Generada.';
								$accion=ModuloGetURL('app','Os_Atencion','user','FrmOrdenar',array('nombre'=>$_SESSION['CAJARAPIDA']['PACIENTE']['nombre'],'tipoid'=>$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente'],'idp'=>$_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id'],'cajarapida'=>'retorno'));
								if(!$this-> FormaMensaje($Mensaje,'ORDENES DE SERVICIO',$accion,'')){
									return false;
								}
								return true;
					}//else
    }
//------------------------------------------------------------------------------------

}//fin clase user

?>

