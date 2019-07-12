 <?php

/**
 * $Id: app_Remisiones_user.php,v 1.2 2005/06/02 23:10:36 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */

/**
*Contiene los metodos para realizar las autorizaciones.
*/

class app_Remisiones_user extends classModulo
{

    var $limit;
    var $conteo;

		function app_Remisiones_user()
		{
    		$this->limit=GetLimitBrowser();
				//$this->limit=5;
				return true;
		}

		/**
		*
		*/
		function main()
		{
				if(empty($_SESSION['REMISIONES']))
				{
								$this->error = "REMISIONES NULA";
								$this->mensajeDeError = "Datos de la Remisión vacios.";
								return false;
				}

				if(empty($_SESSION['REMISIONES']['RETORNO']))
				{
								$this->error = "REMISIONES ";
								$this->mensajeDeError = "El retorno de la Remisión esta vacio.";
								return false;
				}

				if(empty($_SESSION['REMISIONES']['DATOS']))
				{
								$this->error = "REMISIONES ";
								$this->mensajeDeError = "Datos de la Remisión vacios.";
								return false;
				}

				if(empty($_SESSION['REMISIONES']['DATOS']['paciente_id']) || empty($_SESSION['REMISIONES']['DATOS']['tipo_id_paciente']) || empty($_SESSION['REMISIONES']['DATOS']['triage_id']))
				{
								$this->error = "REMISIONES ";
								$this->mensajeDeError = "Datos de la Remision incompletos.";
								return false;
				}

				unset($_SESSION['CENTROS']);
				unset($_SESSION['DIAGNOSTICO']);
				$this->FormaRemision();
				return true;
		}

		/**
		*
		*/
		function RetornarRemision()
		{

				//IncludeLib("funciones_admision");
				//$arr=DatosImpresionRemision($_SESSION['REMISIONES']['DATOS']['triage_id']);
				//$arr=$this->DatosImpresion($_SESSION['REMISIONES']['DATOS']['triage_id']);
				//$_SESSION['REMISIONES']['RETORNO']['ARREGLO']=$arr;
				$contenedor=$_SESSION['REMISIONES']['RETORNO']['contenedor'];
				$modulo=$_SESSION['REMISIONES']['RETORNO']['modulo'];
				$tipo=$_SESSION['REMISIONES']['RETORNO']['tipo'];
				$metodo=$_SESSION['REMISIONES']['RETORNO']['metodo'];
				$argumentos=$_SESSION['REMISIONES']['RETORNO']['argumentos'];

				$this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
				return true;
		}


		/**
		* Busca los niveles de atencion
		* @access public
		* @return array
		* @param string plan_id
		*/
		function Niveles()
		{
					list($dbconn) = GetDBconn();
					$query="SELECT distinct a.descripcion, a.nivel
					        FROM niveles_atencion as a, centros_remision as b
									WHERE a.nivel=b.nivel ORDER BY a.nivel";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}

					while(!$result->EOF){
							$niveles[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}

					$result->Close();
					return $niveles;
		}


	/**
	*
	*/
	function CentrosRemisionNivel($nivel)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM centros_remision WHERE nivel=$nivel";
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
	function CentrosRemision()
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM centros_remision";
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
	function DatosTriage($triage)
	{
				list($dbconn) = GetDBconn();
				$query = "SELECT a.empresa_id,a.nivel_triage_id, a.motivo_consulta, a.observacion_medico,
									a.punto_admision_id, a.tipo_id_paciente, a.paciente_id, b.descripcion
									FROM triages as a, departamentos as b
									WHERE a.triage_id=$triage and a.departamento=b.departamento";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				$vars=$result->GetRowAssoc($ToUpper = false);
				$result->Close();
				return $vars;
	}

	/**
	*
	*/
	function DatosPendientesAdmitir($triage)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM triages_pendientes_admitir
								WHERE triage_id=$triage";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$vars=$result->GetRowAssoc($ToUpper = false);

			$result->Close();
			return $vars;
	}

	/**
	*
	*/
	function NombreEmpresa($empresa)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT razon_social FROM empresas WHERE empresa_id='$empresa'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$vars=$result->fields[0];
			$result->Close();
			return $vars;
	}

	/**
	*
	*/
	function NombrePaciente($tipo,$id)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombre
								FROM pacientes
								WHERE tipo_id_paciente='$tipo' AND paciente_id='$id'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$vars=$result->fields[0];
			$result->Close();
			return $vars;
	}

		/**
		*
		*/
		function BuscarRemision()
		{
				list($dbconn) = GetDBconn();
				$query = " 	SELECT a.centro_remision, b.descripcion
										FROM pacientes_remitidos as a, centros_remision as b
										WHERE a.triage_id=".$_SESSION['REMISIONES']['DATOS']['triage_id']."
										AND a.ingreso is null AND a.centro_remision=b.centro_remision";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al eliminar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				if(!$result->EOF)
				{  $vars=$result->GetRowAssoc($ToUpper = false);  }
				$result->Close();
				return $vars;
		}

    /*Busca los signos vitales de un paciente
    * @access public
    * @return array
    * @param string tipo documento
    * @param int numero documento
    */
		function BuscarSignosVitales()
		{
					list($dbconn) = GetDBconn();
					$query = "SELECT * FROM signos_vitales_triages
										WHERE triage_id=".$_SESSION['REMISIONES']['DATOS']['triage_id']."";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}

					$vars=$result->GetRowAssoc($ToUpper = false);
					$result->Close();
					return $vars;
		}

		/**
		*
		*/
		function Busqueda($opcion,$codigo,$descripcion)
		{
					list($dbconn) = GetDBconn();
					$descripcion =STRTOUPPER($descripcion);
					if(empty($opcion) AND  empty($codigo))
					{
							$opcion=$_REQUEST['criterio'];
							$codigo=$_REQUEST['codigo'];
							$descripcion=STRTOUPPER($_REQUEST['descripcion']);
					}

					$filtroTipoCodigo = '';
					$busqueda1 = '';
					$busqueda2 = '';

					if ($codigo != '')
					{  $busqueda1 =" AND centro_remision LIKE '%$codigo%'";  }

					if ($descripcion != '')
					{  $busqueda2 ="AND descripcion LIKE '%$descripcion%'";  }

					if ($opcion != 'Todas')
					{  $filtroTipoCodigo ="AND nivel='$opcion'";  }

					if(empty($_REQUEST['conteo']))
					{
							$query = "SELECT count(*) FROM centros_remision
												WHERE centro_remision is not null
												$busqueda1 $busqueda2 $filtroTipoCodigo";
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
					{  $this->conteo=$_REQUEST['conteo'];  }
					if(!$_REQUEST['Of'])
					{
							$Of='0';
					}
					else
					{
							$Of=$_REQUEST['Of'];
							if($Of > $this->conteo)
							{
									$Of=0;
									$_REQUEST['Of']=0;
									$_REQUEST['paso']=1;
							}
					}

					$query = "SELECT * FROM centros_remision
										WHERE centro_remision is not null
										$busqueda1 $busqueda2 $filtroTipoCodigo
										order by nivel LIMIT ".$this->limit." OFFSET $Of;";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					if(!$resulta->EOF)
					{
							while(!$resulta->EOF)
							{
									$var[]=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->MoveNext();
							}
					}

					if($this->conteo==='0')
					{
									$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
					}

					$this->FormaRemision($_REQUEST['datos'],$var);
					return true;
		}


		/**
		*
		*/
		function BusquedaDiagnostico($codigo,$descripcion)
		{
					list($dbconn) = GetDBconn();
					$descripcion =STRTOUPPER($descripcion);
					if(empty($opcion) AND  empty($codigo))
					{
							$codigo=$_REQUEST['codigoDiag'];
							$descripcion=STRTOUPPER($_REQUEST['descripcionDiag']);
					}

					$busqueda1 = '';
					$busqueda2 = '';

					if ($codigo != '')
					{  $busqueda1 =" AND diagnostico_id LIKE '$codigo%'";  }

					if ($descripcion != '')
					{  $busqueda2 ="AND diagnostico_nombre LIKE '%$descripcion%'";  }

					if(empty($_REQUEST['conteo']))
					{
							$query = "SELECT count(*) FROM diagnosticos
												WHERE diagnostico_id is not null
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
					{  $this->conteo=$_REQUEST['conteo'];  }
					if(!$_REQUEST['Of'])
					{
							$Of='0';
					}
					else
					{
							$Of=$_REQUEST['Of'];
							if($Of > $this->conteo)
							{
									$Of=0;
									$_REQUEST['Of']=0;
									$_REQUEST['paso']=1;
							}
					}

					$query = "SELECT * FROM diagnosticos
										WHERE diagnostico_id is not null
										$busqueda1 $busqueda2
										order by nivel LIMIT ".$this->limit." OFFSET $Of;";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					while(!$resulta->EOF)
					{
							$var[]=$resulta->GetRowAssoc($ToUpper = false);
							$resulta->MoveNext();
					}

					if($this->conteo==='0')
					{
									$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
					}

					$this->FormaRemision($_REQUEST['datos'],'',$var);
					return $var;
		}


		/**
		*
		*/
		function GuardarCentro($vector)
		{
				foreach($vector as $k => $v)
				{
						if(substr_count($k,'centro'))
						{
								//0 centro 1 des 2 nivel
								$var=explode('||',$v);
								$_SESSION['CENTROS'][$var[0]][$var[1]][$var[2]]=$var[0];
						}
				}
				$this->FormaRemision($_SESSION['DATOS'],'');
				return true;
		}

		/**
		*
		*/
		function GuardarDiagnostico($vector)
		{
				foreach($vector as $k => $v)
				{
						if(substr_count($k,'diag'))
						{
								//0 dig 1 nombre
								$var=explode('||',$v);
								$_SESSION['DIAGNOSTICO'][$var[0]][$var[1]]=$var[0];
						}
				}
				$this->FormaRemision($_SESSION['DATOS'],'','');
				return true;
		}

		/**
		*
		*/
		function AccionesRemision()
		{
				if($_REQUEST['Buscar'])
				{
						$this->Busqueda($_REQUEST['criterio'],$_REQUEST['codigo'],$_REQUEST['descripcion']);
						return true;
				}
				elseif($_REQUEST['Diagnostico'])
				{
						$this->BusquedaDiagnostico($_REQUEST['codigoDiag'],$_REQUEST['descripcionDiag']);
						return true;
				}
				elseif($_REQUEST['Guardar'])
				{
						$this->GuardarCentro($_REQUEST);
						return true;
				}
				elseif($_REQUEST['GuardarDiag'])
				{
						$this->GuardarDiagnostico($_REQUEST);
						return true;
				}
				elseif($_REQUEST['Aceptar'])
				{
						if(empty($_REQUEST['MotivoConsulta'])){
										$this->frmError["MotivoConsulta"]=1;
										$this->frmError["MensajeError"]="Debe escribir el Motivo de la Consulta.";
										if(!$this->FormaRemision('','','')){
														return false;
										}
										return true;
						}

						list($dbconn) = GetDBconn();
						$dbconn->BeginTrans();

						$query = " SELECT numero_remision FROM pacientes_remitidos
											 WHERE triage_id=".$_SESSION['REMISIONES']['DATOS']['triage_id']."";
						$results = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error remisiones_pacientes ";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}
						//no llego remitido a la institucion
						if($results->EOF)
						{
								$query=" SELECT nextval('numero_remision')";
								$result=$dbconn->Execute($query);
								$remisionID=$result->fields[0];
						}
						else
						{
								$remisionID=$results->fields[0];
						}

						$query=" SELECT nextval('remisiones_pacientes_remision_paciente_id_seq')";
						$result=$dbconn->Execute($query);
						$remision=$result->fields[0];

						$query = "INSERT INTO remisiones_pacientes (
													remision_paciente_id,
													triage_id,
													tipo_id_paciente,
													paciente_id,
													fecha_registro,
													motivo_consulta,
													observacion_medico,
													observacion_remision,
													usuario_id,
													remision_id)
											VALUES ($remision,".$_SESSION['REMISIONES']['DATOS']['triage_id'].",'".$_SESSION['REMISIONES']['DATOS']['tipo_id_paciente']."','".$_SESSION['REMISIONES']['DATOS']['paciente_id']."','now()','".$_REQUEST['MotivoConsulta']."','".$_REQUEST['observacion']."','".$_REQUEST['observacionRemision']."',".UserGetUID().",$remisionID)";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error remisiones_pacientes ";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}

						foreach($_SESSION['DIAGNOSTICO'] as $k => $v)
						{
									$query = "INSERT INTO remisiones_pacientes_diagnosticos (
																remision_paciente_id,
																diagnostico_id)
														VALUES ($remision,'$k')";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error INSERT INTO remisiones_pacientes_diagnosticos ";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}
						}

						foreach($_SESSION['CENTROS'] as $k => $v)
						{
								$query = "INSERT INTO remisiones_pacientes_centros (
															remision_paciente_id,
															centro_remision)
													VALUES ($remision,'$k')";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error INSERT INTO remisiones_pacientes_centros ";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
								}
						}

						$query = "update triages set sw_estado='6'
											where triage_id=".$_SESSION['REMISIONES']['DATOS']['triage_id']."";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en triages";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
						}
						$dbconn->CommitTrans();
						$this->RetornarRemision();
						return true;
				}
		}

		/**
		*
		*/
		function EliminarDiagnostico()
		{
				unset($_SESSION['DIAGNOSTICO'][$_REQUEST['codigoED']]);
				$_REQUEST=$_REQUEST['dat'];

				$this->FormaRemision($_SESSION['DATOS'],'','');
				return true;
		}

		/**
		*
		*/
		function EliminarCentro()
		{
				unset($_SESSION['CENTROS'][$_REQUEST['codigoEC']]);
				$_REQUEST=$_REQUEST['dat'];				
				$this->FormaRemision($_SESSION['DATOS'],'','');
				return true;
		}

		/**
		*
		*/
		function BuscarCausas()
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT a.*, b.descripcion
									FROM chequeo_triages as a, causas_probables as b
									WHERE a.triage_id=".$_SESSION['REMISIONES']['DATOS']['triage_id']."
									AND a.causa_probable_id=b.causa_probable_id";
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
		function BuscarDiagnosticoTriage($triage)
		{
				list($dbconn) = GetDBconn();
				$query = " SELECT a.diagnostico_id, b.diagnostico_nombre
									FROM triages_diagnosticos as a, diagnosticos as b
									WHERE a.triage_id=$triage and a.diagnostico_id=b.diagnostico_id";
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


		/*
		*
		*/
		/*function DatosImpresion($triage)
		{
				list($dbconn) = GetDBconn();
				//datos principales
				$query = "SELECT a.remision_paciente_id, a.tipo_id_paciente, a.paciente_id,
									a.motivo_consulta, a.observacion_medico, a.observacion_remision, a.fecha_registro, d.*,
									b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
									e.razon_social, c.nivel_triage_id, f.nombre as medico, g.descripcion
									FROM remisiones_pacientes as a, signos_vitales_triages as d,
									pacientes as b, triages as c, empresas as e, system_usuarios as f,
									departamentos as g
									WHERE a.triage_id=$triage and d.triage_id=$triage
									and a.tipo_id_paciente=b.tipo_id_paciente
									and a.paciente_id=b.paciente_id
									and c.triage_id=$triage and c.empresa_id=e.empresa_id
									and a.usuario_id=f.usuario_id and c.departamento=g.departamento";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

				$arr[]=$result->GetRowAssoc($ToUpper = false);
				$result->Close();

				//diagnosticos (del de arriba tomamos remision_paciente_id)
				$query = "SELECT a.diagnostico_id, b.diagnostico_nombre
									FROM remisiones_pacientes_diagnosticos as a, diagnosticos as b
									WHERE a.remision_paciente_id=".$arr[0][remision_paciente_id]."
									and a.diagnostico_id=b.diagnostico_id";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$result->EOF){
						$var[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
				$result->Close();
				$arr[]=$var;

				//centros (del primero tomamos remision_paciente_id)
				$query = "SELECT a.centro_remision, b.descripcion, b.nivel, b.direccion, b.telefono
									FROM remisiones_pacientes_centros as a, centros_remision as b
									WHERE a.remision_paciente_id=".$arr[0][remision_paciente_id]."
									and a.centro_remision=b.centro_remision";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$result->EOF){
						$cen[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
				$result->Close();

				$arr[]=$cen;

				//causas probables (del primero tomamos remision_paciente_id)
				$query = "SELECT a.*, b.descripcion
									FROM chequeo_triages as a, causas_probables as b
									WHERE a.triage_id=$triage
									AND a.causa_probable_id=b.causa_probable_id";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while (!$result->EOF)
				{
					$cau[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}

				$arr[]=$cau;
				return $arr;
		}*/

//------------------------------------------------------------------------------
}//fin clase user
?>

