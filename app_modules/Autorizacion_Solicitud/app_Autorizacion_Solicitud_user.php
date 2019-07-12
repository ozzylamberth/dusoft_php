  <?php
/**
* app_Autorizacion_Solicitud_user.php  17/01/2003
*
* Proposito del Archivo: Manejo logico de las autorizaciones.
* Copyright (C) 2003 InterSoftware Ltda.
* Email: intersof@telesat.com.co
* @autor: Darling Liliana Dorado M
* @version SIIS v 0.1
* @package SIIS
*/


/**
*Contiene los metodos para realizar las autorizaciones.
*/

class app_Autorizacion_Solicitud_user extends classModulo
{

	function app_Autorizacion_Solicitud_user()
	{
    	$this->frmError=array();
			return true;
	}

	/**

	*/
	function main()
	{
			unset($_SESSION['SOLICITUDAUTORIZACION']);
			unset($_SESSION['SEGURIDAD']);
			unset($_SESSION['DATOS']);
			$SystemId=UserGetUID();
			if(!empty($_SESSION['SEGURIDAD']['PTOAUTORIZACION']['SOLICITUD']))
			{
						$this->salida.= gui_theme_menu_acceso('AUTORIZACIONES',$_SESSION['SEGURIDAD']['PTOAUTORIZACION']['SOLICITUD']['arreglo'],$_SESSION['SEGURIDAD']['PTOAUTORIZACION']['SOLICITUD']['auto'],$_SESSION['SEGURIDAD']['PTOAUTORIZACION']['SOLICITUD']['url'],ModuloGetURL('system','Menu'));
						return true;
			}
			list($dbconn) = GetDBconn();
			GLOBAL $ADODB_FETCH_MODE;
			$query = "select a.punto_id, a.ubicacion, a.exten, a.departamento,
								a.descripcion as descripcion2, d.razon_social as descripcion1, d.empresa_id
								from autorizaciones_puntos as a, userpermisos_autorizaciones_puntos as b,empresas as d
								where b.usuario_id=$SystemId and b.punto_id=a.punto_id
								and d.empresa_id=a.empresa_id";
			/* $query = "select a.punto_id, a.ubicacion, a.exten, a.departamento, c.servicio,
								a.descripcion as descripcion4, d.razon_social as descripcion1,
								e.descripcion as descripcion2, c.empresa_id, c.centro_utilidad, c.descripcion as
								descripcion3
								from autorizaciones_puntos as a, userpermisos_autorizaciones_puntos as b, departamentos as c, empresas as d, centros_utilidad as e
								where b.usuario_id=$SystemId and b.punto_id=a.punto_id
								and a.departamento=c.departamento and d.empresa_id=c.empresa_id
								and c.empresa_id=e.empresa_id and c.centro_utilidad=e.centro_utilidad
								order by c.empresa_id, c.centro_utilidad";*/
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resulta=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			while ($data = $resulta->FetchRow()) {
				/*$auto[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']][$data['descripcion4']]=$data;
				$seguridad[$data['empresa_id']][$data['centro_utilidad']][$data['departamento']][$data['punto_id']]=1;*/
				$auto[$data['descripcion1']][$data['descripcion2']]=$data;
				$seguridad[$data['empresa_id']][$data['punto_id']]=1;

			}

			$url[0]='app';
			$url[1]='Autorizacion_Solicitud';
			$url[2]='user';
			$url[3]='LlamarMenu';
			$url[4]='Auto';

			$arreglo[0]='EMPRESA';
			$arreglo[2]='AUTORIZACIONES';
			/*$arreglo[1]='CENTRO UTILIDAD';
			$arreglo[3]='DEPARTAMENTO';
			$arreglo[4]='AUTORIZACIONES';*/

			$_SESSION['SEGURIDAD']['PTOAUTORIZACION']['SOLICITUD']['arreglo']=$arreglo;
			$_SESSION['SEGURIDAD']['PTOAUTORIZACION']['SOLICITUD']['auto']=$auto;
			$_SESSION['SEGURIDAD']['PTOAUTORIZACION']['SOLICITUD']['url']=$url;
			$_SESSION['SEGURIDAD']['PTOAUTORIZACION']['SOLICITUD']['puntos']=$seguridad;
			$this->salida.= gui_theme_menu_acceso('AUTORIZACIONES',$_SESSION['SEGURIDAD']['PTOAUTORIZACION']['SOLICITUD']['arreglo'],$_SESSION['SEGURIDAD']['PTOAUTORIZACION']['SOLICITUD']['auto'],$_SESSION['SEGURIDAD']['PTOAUTORIZACION']['SOLICITUD']['url'],ModuloGetURL('system','Menu'));
			return true;
	}


	/**
	*
	*/
	function LlamarMenu()
	{
			if(!empty($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']))
			{
					list($dbconn) = GetDBconn();
					$query = " delete from autorizaciones_solicitudes
										 where solicitud_id=".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']."";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
			}

			if(!empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']) && !empty($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']))
			{
					list($dbconn) = GetDBconn();
					$query = " delete from autorizaciones
										 where autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
			}

			if(empty($_SESSION['DATOS']['SOLICITUDAUTORIZACION']['EMPRESA']))
			{
					/*if(empty($_SESSION['SEGURIDAD']['PTOAUTORIZACION']['SOLICITUD']['puntos'][$_REQUEST['Auto']['empresa_id']][$_REQUEST['Auto']['centro_utilidad']][$_REQUEST['Auto']['departamento']][$_REQUEST['Auto']['punto_id']]))
					{
							$this->error = "Error de Seguridad.";
							$this->mensajeDeError = "Violación a la Seguridad.";
							return false;
					}*/

					$_SESSION['DATOS']['SOLICITUDAUTORIZACION']['EMPRESA']=$_REQUEST['Auto']['empresa_id'];
					$_SESSION['DATOS']['SOLICITUDAUTORIZACION']['departamento']=$_REQUEST['Auto']['departamento'];
			}

			/*unset($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']);
			unset($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']);
			unset($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']);*/
			unset($_SESSION['SOLICITUDAUTORIZACION']);
			if(!$this->FormaMenu()){
					return false;
			}
  		return true;
	}

	/**
	*
	*/
	function Principal()
	{
			unset($_SESSION['PACIENTES']);
			if(!empty($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']))
			{
					list($dbconn) = GetDBconn();
					$query = " delete from autorizaciones_solicitudes
										 where solicitud_id=".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']."";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
			}

			if(!empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']) && !empty($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']))
			{
					list($dbconn) = GetDBconn();
					$query = " delete from autorizaciones
										 where autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
			}

		/*	if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
			{	*/
			$_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']=$_REQUEST['Directa']; // }

			if(!$this->FormaBuscar($TipoId,$PacienteId,'','')){
					return false;
			}
  		return true;
	}


	/**
	*
	*/
	function LlamarFormaBuscar()
	{
			if(!$this->FormaBuscar($TipoId,$PacienteId,'','')){
					return false;
			}
  		return true;
	}


	/**
	*
	*/
	function BuscarServicios()
	{
			list($dbconn) = GetDBconn();
			$query = " SELECT servicio, descripcion
					 FROM servicios WHERE sw_asistencial=1";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			$result->Close();
			return $var;
	}

	/**
	*
	*/
	function ValidarDatosPrincipales($TipoId,$PacienteId,$Servicio)
	{
					if(!$TipoId || !$PacienteId || $Servicio==-1){
							if(!$PacienteId){ $this->frmError["PacienteId"]=1; }
							if(!$TipoId){ $this->frmError["TipoId"]=1; }
							if($Servicio==-1){ $this->frmError["Servicio"]=1; }
							$this->frmError["MensajeError"]="Faltan datos obligatorios.";
							return false;
					}
					return true;
	}

	/**
	*
	*/
	function BuscarIngresoPaciente()
	{
			$TipoId=$_REQUEST['TipoId'];
			$PacienteId=$_REQUEST['PacienteId'];
			$Servicio=$_REQUEST['Servicio'];

			unset($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']);
			//unset($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']);
			unset($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']);

			$validar=$this->ValidarDatosPrincipales($TipoId,$PacienteId,$Servicio);
			if($validar)
			{
					list($dbconn) = GetDBconn();
					 $query = " SELECT h.cama, i.pieza, a.ingreso, c.tipo_afiliado_id, c.rango, c.plan_id, c.numerodecuenta, b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre
											FROM ingresos as a, cuentas as c
											left join movimientos_habitacion as h on(h.numerodecuenta=c.numerodecuenta and
											h.fecha_egreso is null) left join camas as i on (h.cama=i.cama),
											pacientes as b
											WHERE a.estado=1 and a.paciente_id='$PacienteId' AND a.tipo_id_paciente ='$TipoId'
											AND a.ingreso=c.ingreso AND c.empresa_id='".$_SESSION['DATOS']['SOLICITUDAUTORIZACION']['EMPRESA']."' AND c.estado=1
											AND b.paciente_id='$PacienteId' AND b.tipo_id_paciente ='$TipoId'";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					else
					{
							if(!$result->EOF)
							{
										while(!$result->EOF)
										{
												$var[]=$result->GetRowAssoc($ToUpper = false);
												$result->MoveNext();
										}
										if(!$this->FormaBuscar($TipoId,$PacienteId,$var,$Servicio)){
												return false;
										}
										return true;
							}
							else
							{
										$this->frmError["MensajeError"]="La Busqueda no arrojo resultados.";
										if(!$this->FormaBuscar($TipoId,$PacienteId,'no',$Servicio)){
												return false;
										}
										return true;
							}
					}
			}
			else
			{
					if(!$this->FormaBuscar($TipoId,$PacienteId,'',$Servicio)){
							return false;
					}
					return true;
			}
	}


	/**
	* Llama la forma de pedir datos del modulo pacientes
	* @access public
	* @return boolean
	* @param string tipo de documento
	* @param int numero de documento
	* @param int plan_id
	* @param int nivel del plan
	*/
	function PedirDatosPaciente()
	{
			$TipoId=$_REQUEST['TipoId'];
			$PacienteId=$_REQUEST['PacienteId'];
			$PlanId=$_REQUEST['Responsable'];
			$Servicio=$_REQUEST['Servicio'];

			unset($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']);
			unset($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']);
			unset($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']);

			if($PlanId==-1){
						if($PlanId==-1){ $this->frmError["Responsable"]=1; }
						$this->frmError["MensajeError"]="Debe elegir el Plan del Paciente.";
						if(!$this->FormaBuscar($TipoId,$PacienteId,$PlanId,$Servicio)){
								return false;
						}
						return true;
			}

			$_SESSION['SOLICITUDAUTORIZACION']['plan_id']=$_REQUEST['Responsable'];
			$_SESSION['SOLICITUDAUTORIZACION']['servicio']=$_REQUEST['Servicio'];
			$_SESSION['SOLICITUDAUTORIZACION']['paciente_id']=$_REQUEST['PacienteId'];
			$_SESSION['SOLICITUDAUTORIZACION']['tipo_id_paciente']=$_REQUEST['TipoId'];

			list($dbconn) = GetDBconn();
			$query = "SELECT sw_tipo_plan, sw_afiliacion
								FROM planes
								WHERE estado='1' and plan_id='".$PlanId."'
								and fecha_final >= now() and fecha_inicio <= now()";
			$results = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($TipoPlan,$swAfiliados,$swCapitacion)=$results->FetchRow();

			if($swAfiliados==1 && $TipoPlan!=1 && $TipoPlan!=2)
			{
					if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
					{
						$this->error = "Error";
						$this->mensajeDeError = "No se pudo incluir : classes/notas_enfermeria/revision_sistemas.class.php";
						return false;
					}
					if(!class_exists('BDAfiliados'))
					{
						$this->error="Error";
						$this->mensajeDeError="no existe BDAfiliados";
						return false;
					}

					$class= New BDAfiliados($TipoId,$PacienteId,$PlanId);
					if($class->GetDatosAfiliado()==false)
					{
						$this->error=$class->error;
						$this->mensajeDeError=$class->mensajeDeError;
						return false;
					}

					if(!empty($class->salida))
					{
							$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']=$class->salida;
							if($_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']['campo_activo']==1)
							{   $_SESSION['SOLICITUDAUTORIZACION']['TODO']=0;   }
							else
							{   $_SESSION['SOLICITUDAUTORIZACION']['TODO']=1;   }
					}
					else
					{
							$_SESSION['SOLICITUDAUTORIZACION']['TODO']=1;
					}
			}
			elseif($TipoPlan==2)
			{
							$_SESSION['SOLICITUDAUTORIZACION']['TODO']=1;
			}

			$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$PacienteId;
			$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$TipoId;
			$_SESSION['PACIENTES']['PACIENTE']['plan_id']=$PlanId;
			$_SESSION['PACIENTES']['RETORNO']['argumentos']=array();
			$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
			$_SESSION['PACIENTES']['RETORNO']['modulo']='Autorizacion_Solicitud';
			$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
			$_SESSION['PACIENTES']['RETORNO']['metodo']='LlamarFormaSolicitudAutorizacion';
			$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']=$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO'];

			$this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
			return true;
	}


	/**
	*
	*/
  function LlamarFormaSolicitudAutorizacion()
	{
			if(!empty($_SESSION['PACIENTES']) && !$_SESSION['PACIENTES']['RETORNO']['PASO'])
			{
						$mensaje='No se termino en proceso de Pedir Datos del Paciente.';
						$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarFormaBuscar');
						if(!$this->FormaMensaje($mensaje,'SOLICITUD AUTORIZACION',$accion,$boton)){
									return false;
						}
						return true;
			}

			if(empty($_SESSION['PACIENTES']))
			{ $_SESSION['SOLICITUDAUTORIZACION']['TODO']=0; }

			//if(empty($_SESSION['SOLICITUDAUTORIZACION']['plan_id']))
			//{
					$_SESSION['SOLICITUDAUTORIZACION']['pieza']=$_REQUEST['pieza'];
					$_SESSION['SOLICITUDAUTORIZACION']['cuenta']=$_REQUEST['cuenta'];
					$_SESSION['SOLICITUDAUTORIZACION']['cama']=$_REQUEST['cama'];
					$_SESSION['SOLICITUDAUTORIZACION']['plan_id']=$_REQUEST['PlanId'];
					$_SESSION['SOLICITUDAUTORIZACION']['ingreso']=$_REQUEST['Ingreso'];
					$_SESSION['SOLICITUDAUTORIZACION']['rango']=$_REQUEST['Nivel'];
					$_SESSION['SOLICITUDAUTORIZACION']['paciente_id']=$_REQUEST['PacienteId'];
					$_SESSION['SOLICITUDAUTORIZACION']['tipo_id_paciente']=$_REQUEST['TipoId'];
					$_SESSION['SOLICITUDAUTORIZACION']['tipo_afiliado_id']=$_REQUEST['Afiliado'];
					$_SESSION['SOLICITUDAUTORIZACION']['servicio']=$_REQUEST['Servicio'];
			//}
			unset($_SESSION['PACIENTES']);
			list($dbconn) = GetDBconn();
			$query = "select protocolos from planes
								where plan_id='".$_SESSION['SOLICITUDAUTORIZACION']['plan_id']."'";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$_SESSION['SOLICITUDAUTORIZACION']['protocolo']=$result->fields[0];
			$result->Close();

			if(!empty($_SESSION['SOLICITUDAUTORIZACION']['FACTURACION'])
				 AND empty($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']))
			{
						if(empty($estacion))
						{ $estacion='NULL'; }
						else
						{ $estacion="'$estacion'"; }

						if(empty($pieza))
						{ $pieza='NULL'; }
						else
						{ $pieza="'$pieza'"; }

						if(empty($cama))
						{ $cama='NULL'; }
						else
						{ $cama="'$cama'"; }

						$query="SELECT nextval('autorizaciones_solicitudes_solicitud_id_seq')";
						$result=$dbconn->Execute($query);
						$Transaccion=$result->fields[0];
						$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']=$result->fields[0];
						$query = "INSERT INTO  autorizaciones_solicitudes(solicitud_id,
																															ingreso,
																															observacion,
																															usuario_id,
																															fecha_registro,
																															sw_urgente,
																															departamento,
																															estacion_id,
																															pieza,
																															cama,
																															usuario_id_autorizador,
																															numerodecuenta)
														VALUES(".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'].",".$_SESSION['SOLICITUDAUTORIZACION']['ingreso'].",'',".UserGetUID().",'now()',0,'".$_SESSION['DATOS']['SOLICITUDAUTORIZACION']['departamento']."',$estacion,$pieza,$cama,NULL,".$_SESSION['SOLICITUDAUTORIZACION']['cuenta'].")";
						$dbconn->BeginTrans();
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error autorizaciones_solicitudes";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}
						else
						{
									$query = "INSERT INTO  autorizaciones_solicitudes_ingreso_cargos
																																(solicitud_id,
																																tarifario_id,
																																cargo,
																																servicio,
																																cantidad)
									VALUES(".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'].",'".$_SESSION['SOLICITUDAUTORIZACION']['tarifario_id']."','".$_SESSION['SOLICITUDAUTORIZACION']['cargo']."','".$_SESSION['SOLICITUDAUTORIZACION']['servicio']."',".$_SESSION['SOLICITUDAUTORIZACION']['cantidad'].")";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error autorizaciones_solicitudes_ingreso_cargos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}
									else
									{   $dbconn->CommitTrans();   }
						}
			}

			if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
			{
					if(!$this->AutorizacionServicio()){
							return false;
					}
					return true;
			}
			else
			{
					if(!$this->FormaAutorizacionDirecta()){
							return false;
					}
					return true;
			}
	}

	
	
	/**
	*
	*/
  function LlamarFormaSolicitudAutorizacionVarios()
	{
			if(!empty($_SESSION['PACIENTES']) && !$_SESSION['PACIENTES']['RETORNO']['PASO'])
			{
						$mensaje='No se termino en proceso de Pedir Datos del Paciente.';
						$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarFormaBuscar');
						if(!$this->FormaMensaje($mensaje,'SOLICITUD AUTORIZACION',$accion,$boton)){
									return false;
						}
						return true;
			}
			
			if(empty($_SESSION['PACIENTES']))
			{ $_SESSION['SOLICITUDAUTORIZACION']['TODO']=0; }

			//if(empty($_SESSION['SOLICITUDAUTORIZACION']['plan_id']))
			//{
					$_SESSION['SOLICITUDAUTORIZACION']['pieza']=$_REQUEST['pieza'];
					$_SESSION['SOLICITUDAUTORIZACION']['cama']=$_REQUEST['cama'];
					$_SESSION['SOLICITUDAUTORIZACION']['plan_id']=$_REQUEST['PlanId'];
					$_SESSION['SOLICITUDAUTORIZACION']['ingreso']=$_REQUEST['Ingreso'];
					$_SESSION['SOLICITUDAUTORIZACION']['rango']=$_REQUEST['Nivel'];
					$_SESSION['SOLICITUDAUTORIZACION']['paciente_id']=$_REQUEST['PacienteId'];
					$_SESSION['SOLICITUDAUTORIZACION']['tipo_id_paciente']=$_REQUEST['TipoId'];
					$_SESSION['SOLICITUDAUTORIZACION']['tipo_afiliado_id']=$_REQUEST['Afiliado'];

			//}
			unset($_SESSION['PACIENTES']);
			list($dbconn) = GetDBconn();
			$query = "select protocolos from planes
								where plan_id='".$_SESSION['SOLICITUDAUTORIZACION']['plan_id']."'";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$_SESSION['SOLICITUDAUTORIZACION']['protocolo']=$result->fields[0];
			$result->Close();

			if(!empty($_SESSION['SOLICITUDAUTORIZACION']['FACTURACION'])
				 AND empty($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']))
			{
						if(empty($estacion))
						{ $estacion='NULL'; }
						else
						{ $estacion="'$estacion'"; }

						if(empty($pieza))
						{ $pieza='NULL'; }
						else
						{ $pieza="'$pieza'"; }

						if(empty($cama))
						{ $cama='NULL'; }
						else
						{ $cama="'$cama'"; }

						$query="SELECT nextval('autorizaciones_solicitudes_solicitud_id_seq')";
						$result=$dbconn->Execute($query);
						$Transaccion=$result->fields[0];
						$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']=$result->fields[0];
						
						$query = "INSERT INTO  autorizaciones_solicitudes(solicitud_id,
																															ingreso,
																															observacion,
																															usuario_id,
																															fecha_registro,
																															sw_urgente,
																															departamento,
																															estacion_id,
																															pieza,
																															cama,
																															usuario_id_autorizador,
																															numerodecuenta,
																															cargo_cups)
														VALUES(".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'].",".$_SESSION['SOLICITUDAUTORIZACION']['ingreso'].",'',".UserGetUID().",'now()',0,'".$_SESSION['DATOS']['SOLICITUDAUTORIZACION']['departamento']."',$estacion,$pieza,$cama,NULL,".$_SESSION['SOLICITUDAUTORIZACION']['cuenta'].",'".$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO'][0][cups]."')";
						$dbconn->BeginTrans();
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error autorizaciones_solicitudes";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}						

						for($i=0; $i<sizeof($_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']); $i++)
						{								
										$query = "INSERT INTO  autorizaciones_solicitudes_ingreso_cargos
																																	(solicitud_id,
																																	tarifario_id,
																																	cargo,
																																	servicio,
																																	cantidad)
										VALUES(".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'].",'".$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO'][$i][tarifario]."','".$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO'][$i][cargo]."','".$_SESSION['SOLICITUDAUTORIZACION']['servicio']."',".$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO'][$i][cantidad].")";
										$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error autorizaciones_solicitudes_ingreso_cargos";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollbackTrans();
												return false;
										}
						}//fin for
						$dbconn->CommitTrans();  
			}

			if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
			{
					if(!$this->AutorizacionServicio()){
							return false;
					}
					return true;
			}
			else
			{
					if(!$this->FormaAutorizacionDirecta()){
							return false;
					}
					return true;
			}
	}	
	/**
	*
	*/
	function BuscarNombresApellidosPacienteSinIng()
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.primer_nombre||' '||a.segundo_nombre||' '||a.primer_apellido||' '||a.segundo_apellido as nombre,
								a.tipo_id_paciente, a.paciente_id
								FROM pacientes as a
								WHERE a.tipo_id_paciente='".$_SESSION['SOLICITUDAUTORIZACION']['tipo_id_paciente']."'
								AND a.paciente_id='".$_SESSION['SOLICITUDAUTORIZACION']['paciente_id']."'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$var=$result->GetRowAssoc($ToUpper = false);
			$result->Close();
 			return $var;
	}

	/**
	*
	*/
	function AutorizacionServicio()
	{
			unset($_SESSION['SOLICITUDAUTORIZACION']['VECTOR']);
			$SystemId=UserGetUID();
			$FechaRegistro=date("Y-m-d H:i:s");
			$Ingreso=$_SESSION['SOLICITUDAUTORIZACION']['ingreso'];
 			$estacion=$_SESSION['SOLICITUDAUTORIZACION']['estacion_id'];
 			$pieza=$_SESSION['SOLICITUDAUTORIZACION']['pieza'];
 			$cama=$_SESSION['SOLICITUDAUTORIZACION']['cama'];

			if(empty($estacion))
			{ $estacion='NULL'; }
			else
			{ $estacion="'$estacion'"; }

			if(empty($pieza))
			{ $pieza='NULL'; }
			else
			{ $pieza="'$pieza'"; }

			if(empty($cama))
			{ $cama='NULL'; }
			else
			{ $cama="'$cama'"; }

			if(empty($Ingreso))
			{ $Ingreso='NULL'; }

			list($dbconn) = GetDBconn();
			if(empty($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']))
			{
						$query="SELECT nextval('autorizaciones_solicitudes_solicitud_id_seq')";
						$result=$dbconn->Execute($query);
						$Transaccion=$result->fields[0];
						$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']=$result->fields[0];
						$query = "INSERT INTO  autorizaciones_solicitudes(solicitud_id,
																															ingreso,
																															observacion,
																															usuario_id,
																															fecha_registro,
																															sw_urgente,
																															departamento,
																															estacion_id,
																															pieza,
																															cama,
																															usuario_id_autorizador,
																															numerodecuenta)
														VALUES(".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'].",$Ingreso,'$Observacion',$SystemId,'$FechaRegistro',0,'".$_SESSION['DATOS']['SOLICITUDAUTORIZACION']['departamento']."',$estacion,$pieza,$cama,NULL,".$_SESSION['SOLICITUDAUTORIZACION']['cuenta'].")";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error autorizaciones_solicitudes";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
			}

			if(!$this->FormaSolicitudAutorizacion($_SESSION['SOLICITUDAUTORIZACION']['plan_id'],$grupo,$tipo,$var,$nivel,'')){
					return false;
			}
			return true;
	}


	/**
	* Busca los niveles del plan del responsable del paciente
	* @access public
	* @return array
	* @param string plan_id
	*/
	 function Niveles()
	 {
				list($dbconn) = GetDBconn();
				 $query="SELECT DISTINCT rango
								FROM planes_rangos
								WHERE plan_id='".$_SESSION['SOLICITUDAUTORIZACION']['plan_id']."'";
				$result=$dbconn->Execute($query);
				while(!$result->EOF){
					$niveles[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			return $niveles;
	 }

	/**
	*
	*/
	function NivelesAtencion()
	{
				list($dbconn) = GetDBconn();
				$query="SELECT * FROM niveles_atencion order by nivel";
				$result=$dbconn->Execute($query);
				while(!$result->EOF){
					$niveles[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			return $niveles;
	}

	 /**
	 *
	 */
	 function AdicionarServicio()
	 {
			list($dbconn) = GetDBconn();
			GLOBAL $ADODB_FETCH_MODE;
			if(empty($_SESSION['SOLICITUDAUTORIZACION']['TODO']))
			{
					/*if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
					{
								$query = "select a.nivel, a.grupo_tipo_cargo, a.tipo_cargo, b.descripcion,
													c.descripcion as destipo, d.descripcion_corta, e.solicitud_id
													from planes_autorizaciones_int as a left join
													autorizaciones_solicitudes_ingreso_grupo_cargos as e on
													(a.nivel=e.nivel and a.grupo_tipo_cargo=e.grupo_tipo_cargo and a.tipo_cargo=e.tipo_cargo
													and e.solicitud_id=".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']."),
													grupos_tipos_cargo as b, tipos_cargos as c, niveles_atencion as d
													where a.plan_id='".$_SESSION['SOLICITUDAUTORIZACION']['plan_id']."'
													and a.servicio='".$_SESSION['SOLICITUDAUTORIZACION']['servicio']."'
													and a.grupo_tipo_cargo=b.grupo_tipo_cargo and a.grupo_tipo_cargo=c.grupo_tipo_cargo
													and a.tipo_cargo=c.tipo_cargo and a.nivel=d.nivel and e.solicitud_id is null
													order by a.grupo_tipo_cargo, c.descripcion, a.tipo_cargo";
					}
					else
					{*/
						$query = "select a.nivel, a.grupo_tipo_cargo, a.tipo_cargo, b.descripcion,
											c.descripcion as destipo, d.descripcion_corta
											from planes_autorizaciones_int as a, grupos_tipos_cargo as b, tipos_cargos as c,
											niveles_atencion as d
											where a.plan_id='".$_SESSION['SOLICITUDAUTORIZACION']['plan_id']."'
											and a.servicio='".$_SESSION['SOLICITUDAUTORIZACION']['servicio']."'
											and a.grupo_tipo_cargo=b.grupo_tipo_cargo and a.grupo_tipo_cargo=c.grupo_tipo_cargo and
											a.tipo_cargo=c.tipo_cargo and a.nivel=d.nivel
											order by a.grupo_tipo_cargo, c.descripcion, a.tipo_cargo";
			}
			else
			{
					/*	$query = "SELECT A.descripcion, B.descripcion AS destipo,
											A.grupo_tipo_cargo, B.tipo_cargo, C.servicio, C.nivel
											FROM grupos_tipos_cargo AS A,
											tipos_cargos AS B
											left join autorizaciones_solicitudes_ingreso_grupo_cargos AS C ON
											(
												B.grupo_tipo_cargo=C.grupo_tipo_cargo
												AND B.tipo_cargo=C.tipo_cargo
												AND C.solicitud_id=".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']."
											)
											WHERE A.grupo_tipo_cargo=B.grupo_tipo_cargo
											AND A.grupo_tipo_cargo<>'SYS'
											order by a.grupo_tipo_cargo, b.descripcion, b.tipo_cargo, C.nivel";*/
				$query = "select d.nivel, b.grupo_tipo_cargo, c.tipo_cargo, b.descripcion,
							c.descripcion as destipo, d.descripcion_corta from grupos_tipos_cargo as b,
							tipos_cargos as c, niveles_atencion as d
							where b.grupo_tipo_cargo=c.grupo_tipo_cargo and b.grupo_tipo_cargo <> 'SYS'
							order by b.grupo_tipo_cargo, c.descripcion, c.tipo_cargo";
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while ($data = $result->FetchRow()) {
				$grupo[$data['descripcion']]+= 1;
				$tipo[$data['descripcion']][$data['destipo']] += 1;
				$nivel[$data['descripcion']][$data['destipo']][$data['descripcion_corta']] = 1;
			}

			$resulta=$dbconn->Execute($query);
			while(!$resulta->EOF)
			{
					$var[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
			}
			$result->Close();
			$resulta->Close();
			$this->SolicitudServicios($grupo,$tipo,$var,$nivel);
			return true;
	 }

	 /**
	 *
	 */
	function InsertarServicio()
	{
			$_SESSION['SOLICITUDAUTORIZACION']['AFILIADO'][$_REQUEST['TipoAfiliado']]=$_REQUEST['TipoAfiliado'];
			$_SESSION['SOLICITUDAUTORIZACION']['RANGO'][$_REQUEST['Nivel']]=$_REQUEST['Nivel'];

			$f=0;
			foreach($_REQUEST as $k => $v)
			{
				if(substr_count($k,'Nivel'))
				{
						$f=1;
				}
			}

			if($f==0)
			{
					$this->frmError["MensajeError"]="ERROR DATOS VACIOS: Debe elegir el Servicio a solicitar.";
					$this->AdicionarServicio();
					return true;
			}

			unset($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZAR']['CARGOS']['VECT']);
			foreach($_REQUEST as $k => $v)
			{
					if(substr_count($k,'Nivel'))
					{
							$servicio=explode(',',$v);
							$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZAR']['CARGOS']['VECT'][$servicio[0]][$servicio[1]][$servicio[2]]=$servicio[0];
					}
			}

			list($dbconn) = GetDBconn();
			if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
			{
					$query = "delete from autorizaciones_solicitudes_ingreso_grupo_cargos
										where solicitud_id=".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']."";
			}
			else
			{
					$query = "delete from autorizaciones_ingreso_grupo_cargos
										where solicitud_id=".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']."";
			}
			$dbconn->Execute($query);

			foreach($_REQUEST as $k => $v)
			{
				if(substr_count($k,'Nivel'))
				{
							$servicio=explode(',',$v);
							if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
							{
										$query = "INSERT INTO  autorizaciones_solicitudes_ingreso_grupo_cargos
																																								(solicitud_id,
																																								grupo_tipo_cargo,
																																								tipo_cargo,
																																								servicio,
																																								nivel)
										VALUES(".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'].",'$servicio[0]','$servicio[1]','".$_SESSION['SOLICITUDAUTORIZACION']['servicio']."',$servicio[2])";
							}
							else
							{
										$query = "INSERT INTO  autorizaciones_ingreso_grupo_cargos
																																		(autorizacion,
																																		grupo_tipo_cargo,
																																		tipo_cargo,
																																		servicio,
																																		nivel)
										VALUES(".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION'].",'$servicio[0]','$servicio[1]','".$_SESSION['SOLICITUDAUTORIZACION']['servicio']."',$servicio[2])";
							}
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}
				}
			}
					$this->frmError["MensajeError"]="Los Servicios se Crearon Correctamente.";
			if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
			{  $this->AutorizacionServicio();  }
			else
			{  $this->FormaAutorizacionDirecta();  }
			return true;
	 }


	/**
	*
	*/
	function AdicionarCargo()
	{
			unset($_SESSION['SOLICITUDAUTORIZACION']['VECTOR']);
			if(!$this->FormaCargos()){
					return false;
			}
  		return true;
	}

	/**
	*
	*/
	function InsertarCargo()
	{
				IncludeLib("tarifario");
				$_SESSION['SOLICITUDAUTORIZACION']['AFILIADO'][$_REQUEST['TipoAfiliado']]=$_REQUEST['TipoAfiliado'];
				$_SESSION['SOLICITUDAUTORIZACION']['RANGO'][$_REQUEST['Nivel']]=$_REQUEST['Nivel'];
				$_SESSION['SOLICITUDAUTORIZACION']['SEMANAS'][$_REQUEST['Semanas']]=$_REQUEST['Semanas'];

				if(empty($_REQUEST['Guardar']))
				{
						if(empty($_REQUEST['Cargo']) && !empty($_REQUEST['Codigo']))
						{
									$key1="cargo";
									$filtro = "( lower ($key1)='".$_REQUEST['Codigo']."')";
									$campos_select = " tarifario_id, grupo_tarifario_id, subgrupo_tarifario_id, sw_cantidad , descripcion ";
									$resulta = PlanTarifario($_SESSION['SOLICITUDAUTORIZACION']['plan_id'], '', '', '', '', '', '', $filtro, $campos_select, $fetch_mode_assoc=false,'','');
									$arreglo=$resulta->GetRowAssoc($ToUpper = false);
									$_REQUEST['TarifarioId']=$arreglo[tarifario_id];
									$_REQUEST['Cargo']=$arreglo[descripcion];
						}
						if($_REQUEST['Cargo'] && $_REQUEST['Codigo'])
						{
								list($dbconn) = GetDBconn();
								$query = "select autorizacion_cobertura('".$_SESSION['SOLICITUDAUTORIZACION']['plan_id']."','".$_REQUEST['TarifarioId']."','".$_REQUEST['Codigo']."','".$_SESSION['SOLICITUDAUTORIZACION']['servicio']."')";
								$result = $dbconn->Execute($query);

								if($result->fields[0]=='NULL' || $result->fields[0]=='NoRequiere')
								{
										if($result->fields[0]=='NULL')
										{
													$this->frmError["MensajeError"]="AUTORIZACION: El Plan No Cubre el Cargo ".$_REQUEST['Cargo'];
													if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
													{  $this->AutorizacionServicio();
															return true;
													}
													else
													{  $this->FormaAutorizacionDirecta();
															return true;
													}
													//$this->AutorizacionServicio();
													//return true;
										}
										if($result->fields[0]=='NoRequiere')
										{
													$this->frmError["MensajeError"]="AUTORIZACION: El Cargo ".$_REQUEST['Cargo']." No Requiere Autorización.";
													if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
													{  $this->AutorizacionServicio();
															return true;
													}
													else
													{  $this->FormaAutorizacionDirecta();
															return true;
													}
												//	$this->AutorizacionServicio();
												//	return true;
										}
								}
								elseif($_SESSION['SOLICITUDAUTORIZACION']['ingreso'])
								{
											$query = "select count(*) FROM autorizaciones_ingreso_cargos as a, autorizaciones as b
																WHERE a.plan_id = '".$_SESSION['SOLICITUDAUTORIZACION']['plan_id']."'
																AND a.tarifario_id = '".$_REQUEST['TarifarioId']."' AND a.cargo = '".$_REQUEST['Codigo']."'
																and a.servicio = '".$_SESSION['SOLICITUDAUTORIZACION']['servicio']."'
																and b.ingreso=".$_SESSION['SOLICITUDAUTORIZACION']['ingreso']." and
																b.autorizacion=a.autorizacion";
											$result = $dbconn->Execute($query);
											if($result->fields[0]>0)
											{
													$this->frmError["MensajeError"]="ERROR AUTORIZACION: El Cargo ".$_REQUEST['Cargo']." ya esta Autorizado.";
													$this->AutorizacionServicio();
													return true;
											}
											$query = "select count(*)
																FROM tarifarios_detalle as a, autorizaciones_ingreso_grupo_cargos as b,
																autorizaciones as c
																WHERE b.plan_id = '".$_SESSION['SOLICITUDAUTORIZACION']['plan_id']."'
																AND a.tarifario_id = '".$_REQUEST['TarifarioId']."' AND
																a.cargo = '".$_REQUEST['Codigo']."' and b.servicio = '".$_SESSION['SOLICITUDAUTORIZACION']['servicio']."'
																and a.nivel=b.nivel and c.ingreso=".$_SESSION['SOLICITUDAUTORIZACION']['ingreso']."
																and b.autorizacion=a.autorizacion";
											$result = $dbconn->Execute($query);
											if($result->fields[0]>0)
											{
													$this->frmError["MensajeError"]="ERROR AUTORIZACION: El Cargo ".$_REQUEST['Cargo']." ya esta Autorizado.";
													if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
													{  $this->AutorizacionServicio();  }
													else {  $this->FormaAutorizacionDirecta();  }
													return true;
											}
								}
								//forma el arreglo
								$_SESSION['SOLICITUDAUTORIZACION']['VECTOR'][$_REQUEST['TarifarioId']][$_REQUEST['Codigo']][$_REQUEST['Cantidad']]=$_REQUEST['Cargo'];
						}
						$this->FormaCargos();
						return true;
				}

				list($dbconn) = GetDBconn();
				foreach($_SESSION['SOLICITUDAUTORIZACION']['VECTOR'] as $k => $v)
				{
								foreach($v as $cod => $cant)
								{
										foreach($cant as $cantidad => $cargo)
										{
												if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
												{
														 $query = "INSERT INTO  autorizaciones_solicitudes_ingreso_cargos
																																				(solicitud_id,
																																				tarifario_id,
																																				cargo,
																																				servicio,
																																				cantidad)
													VALUES(".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'].",'$k','$cod','".$_SESSION['SOLICITUDAUTORIZACION']['servicio']."',$cantidad)";
													$dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0) {
															$this->error = "Error autorizaciones_solicitudes_ingreso_cargos";
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															return false;
													}
												}
												else
												{
													$query = "INSERT INTO  autorizaciones_ingreso_cargos
																																				(autorizacion,
																																				tarifario_id,
																																				cargo,
																																				servicio,
																																				cantidad)
													VALUES(".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION'].",'$k','$cod','".$_SESSION['SOLICITUDAUTORIZACION']['servicio']."',$cantidad)";
													$dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0) {
															$this->error = "Error  autorizaciones_ingreso_cargos";
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															return false;
													}

												}
										}
								}
				}
				$this->frmError["MensajeError"]="El Cargo se Guardo Correctamente";
				if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
				{  $this->AutorizacionServicio();
						return true;
				}
				else
				{  $this->FormaAutorizacionDirecta();
						return true;
				}
	}

	/**
	*
	*/
	function EliminarCargo()
	{
			unset($_SESSION['SOLICITUDAUTORIZACION']['VECTOR'][$_REQUEST['TarifarioId']][$_REQUEST['Codigo']]);
			if(sizeof($_SESSION['SOLICITUDAUTORIZACION']['VECTOR'][$_REQUEST['TarifarioId']][$_REQUEST['Codigo']])==0)
			{
					unset($_SESSION['SOLICITUDAUTORIZACION']['VECTOR'][$_REQUEST['TarifarioId']][$_REQUEST['Codigo']]);
			}

			$this->FormaCargos();
			return true;
	}

	/**
	*
	*/
	function SolicitudAutorizacion()
	{
			list($dbconn) = GetDBconn();
			$query = "select a.*, b.descripcion
										from autorizaciones_solicitudes_ingreso_cargos as a, tarifarios_detalle as b
										where a.solicitud_id=".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']." and a.tarifario_id=b.tarifario_id and a.cargo=b.cargo";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			if(!$result->EOF)
			{
					while(!$result->EOF)
					{
							$cargos[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
			}

			//servicios
			GLOBAL $ADODB_FETCH_MODE;
			$query = "select a.*, b.descripcion, c.descripcion as destipo, d.descripcion_corta
									from autorizaciones_solicitudes_ingreso_grupo_cargos as a, grupos_tipos_cargo as b,tipos_cargos as c, niveles_atencion as d
									where a.solicitud_id=".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']."
									and a.grupo_tipo_cargo=b.grupo_tipo_cargo and a.grupo_tipo_cargo=c.grupo_tipo_cargo and
									a.tipo_cargo=c.tipo_cargo and a.nivel=d.nivel order by a.grupo_tipo_cargo,
									c.descripcion, a.tipo_cargo";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if(!$result->EOF)
			{
					while ($data = $result->FetchRow()) {
						$grupo[$data['descripcion']]+= 1;
						$tipo[$data['descripcion']][$data['destipo']] += 1;
						$nivel[$data['descripcion']][$data['destipo']][$data['descripcion_corta']] = 1;
					}

					$result=$dbconn->Execute($query);
					while(!$result->EOF)
					{
							$servicio[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
			}

			$this->FormaSolicitud($cargos,$servicio,$grupo,$tipo,$nivel);
			return true;

	}


	/**
	*
	*/
	function PermisosUsuario()
	{
			if(!empty($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']))
			{
					list($dbconn) = GetDBconn();
					$query = " delete from autorizaciones
										 where autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
			}
			//$depto=$_SESSION['DATOS']['SOLICITUDAUTORIZACION']['departamento'];
			unset($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']);
			$_SESSION['DATOS']['SOLICITUDAUTORIZACION']['departamento'];

			if(!$this->FormaListadoAutorizacionesPendientes()){
					return false;
			}
  		return true;
	}


	/**
	*
	*/
	function	LlamarListadoAutorizaciones()
	{
			//$depto=$_SESSION['DATOS']['SOLICITUDAUTORIZACION']['departamento'];
			//unset($_SESSION['SOLICITUDAUTORIZACION']);
			//$_SESSION['DATOS']['SOLICITUDAUTORIZACION']['departamento']=$depto;

			if(!$this->FormaListadoAutorizaciones()){
					return false;
			}
  		return true;
	}

	/**
  * Busca los diferentes tipos de afiliados
	* @access public
	* @return array
	*/
		function Tipo_Afiliado()
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id
									FROM tipos_afiliado as a, planes_rangos as b
									WHERE b.plan_id='".$_SESSION['SOLICITUDAUTORIZACION']['plan_id']."'
									and b.tipo_afiliado_id=a.tipo_afiliado_id";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

				while(!$resulta->EOF)
				{
						$vars[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
				}
				$resulta->Close();
				return $vars;
		}

	/**
  * Busca los diferentes tipos de afiliados
	* @access public
	* @return array
	*/
		function NombreAfiliado($Tipo)
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id
									FROM tipos_afiliado as a, planes_rangos as b
									WHERE b.plan_id='".$_SESSION['SOLICITUDAUTORIZACION']['plan_id']."'
									and a.tipo_afiliado_id='$Tipo'";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$vars=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->Close();
				return $vars;
		}


	/**
	*
	*/
	function BuscarNivelesAtencion()
	{
			list($dbconn) = GetDBconn();
			$query = " SELECT nivel,descripcion,descripcion_corta FROM niveles_atencion";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			$result->Close();
			return $var;
	}


	/**
	*
	*/
	function BuscarTiposCama()
	{
			list($dbconn) = GetDBconn();
			$query = " SELECT tipo_cama, descripcion FROM tipos_cama";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			$result->Close();
			return $var;
	}

	/**
	*
	*/
	function InsertarSolicitud()
	{
					$TipoAfiliado=$_REQUEST['TipoAfiliado'];
					$Nivel=$_REQUEST['Nivel'];
					$_SESSION['SOLICITUDAUTORIZACION']['AFILIADO'][$_REQUEST['TipoAfiliado']]=$_REQUEST['TipoAfiliado'];
					$_SESSION['SOLICITUDAUTORIZACION']['RANGO'][$_REQUEST['Nivel']]=$_REQUEST['Nivel'];
					$_SESSION['SOLICITUDAUTORIZACION']['SEMANAS'][$_REQUEST['Semanas']]=$_REQUEST['Semanas'];

					if(!empty($_SESSION['SOLICITUDAUTORIZACION']['ingreso']))
					{
							if( $TipoAfiliado==-1 || $Nivel==-1)
							{
									if($TipoAfiliado==-1){ $this->frmError["TipoAfiliado"]=1; }
									if($Nivel==-1){ $this->frmError["Nivel"]=1; }
									$this->frmError["MensajeError"]="ERROR DATOS VACIOS:  Debe elegir el Tipo de Afiliado y su Rango.";
									$this->AutorizacionServicio();
									return true;
							}
					}

					list($dbconn) = GetDBconn();
					$query = "(select solicitud_id from autorizaciones_solicitudes_ingreso_grupo_cargos
										where solicitud_id=".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'].")
										union
										(select solicitud_id from autorizaciones_solicitudes_ingreso_cargos
										where solicitud_id=".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'].")";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar 1en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
					}

					if($result->EOF)
					{
							$this->frmError["MensajeError"]="ERROR DATOS VACIOS:  Debe Adicionar Servicios o Cargos a la Solicitud.";
							$this->AutorizacionServicio();
							return true;
					}

					$Pto=$_REQUEST['Pto'];
					if(empty($_REQUEST['Pto']))
					{
							$this->frmError["MensajeError"]="ERROR DATOS VACIOS:  Debe elegir el Usuario al que solicita la Autorización.";
							$this->AutorizacionServicio();
							return true;
					}

					$PlanId=$_SESSION['SOLICITUDAUTORIZACION']['plan_id'];
					$Ingreso=$_SESSION['SOLICITUDAUTORIZACION']['ingreso'];
					$dpto=$_SESSION['DATOS']['SOLICITUDAUTORIZACION']['departamento'];
					$estacion=$_SESSION['SOLICITUDAUTORIZACION']['estacion_id'];
					$pieza=$_SESSION['SOLICITUDAUTORIZACION']['pieza'];
					$cama=$_SESSION['SOLICITUDAUTORIZACION']['cama'];

					if(empty($Ingreso))
					{ $Ingreso='NULL'; }

					$TipoId=$_SESSION['SOLICITUDAUTORIZACION']['tipo_id_paciente'];
					if(empty($_SESSION['SOLICITUDAUTORIZACION']['tipo_id_paciente']))
					{ $TipoId='NULL'; }
					else
					{ $TipoId="'$TipoId'"; }

					$PacienteId=$_SESSION['SOLICITUDAUTORIZACION']['paciente_id'];
					if(empty($_SESSION['SOLICITUDAUTORIZACION']['paciente_id']))
					{ $PacienteId='NULL'; }
					else
					{ $PacienteId="'$PacienteId'"; }

					if(empty($_REQUEST['Urgente']))
					{ $Urgente=0; }
					else
					{ $Urgente=$_REQUEST['Urgente']; }

					if(empty($cama))
					{ $cama='NULL'; }
					else
					{ $cama="'$cama'"; }

					if(empty($estacion))
					{ $estacion='NULL'; }
					else
					{ $estacion="'$estacion'"; }

					if(empty($pieza))
					{ $pieza='NULL'; }
					else
					{ $pieza="'$pieza'"; }

					$Observacion=$_REQUEST['Observaciones'];
					$query = "UPDATE  autorizaciones_solicitudes SET
																									ingreso=$Ingreso,
																									observacion='$Observacion',
																									sw_urgente=$Urgente,
																									estacion_id=$estacion,
																									pieza=$pieza,
																									cama=$cama,
																									usuario_id_autorizador=$Pto
												WHERE solicitud_id=".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']."";
					$dbconn->BeginTrans();
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar 1en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
					}
					else
					{
							if($Ingreso=='NULL')
							{
									$query = "INSERT INTO  autorizaciones_solicitudes_externas
																																	( solicitud_id,
																																		rango,
																																		tipo_afiliado_id,
																																		tipo_id_paciente,
																																		paciente_id,
																																		plan_id,
																																		semanas_cotizadas)
																	VALUES(".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'].",'$Nivel','$TipoAfiliado',$TipoId,
																	$PacienteId,'$PlanId',".$_REQUEST['Semanas'].")";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Guardar 11en la Base de Datos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}
									else
									{
												$dbconn->CommitTrans();
            						$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['SOLICITUD']=$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'];
												unset($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']);
												$mensaje='La Solicitud fue Creada Satisfactoriamente.';
												if(!empty($_SESSION['SOLICITUDAUTORIZACION']['RETORNO']))
												{
														$m=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['modulo'];
														$t=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['tipo'];
														$c=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['contenedor'];
														$me=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['metodo'];
														$argu=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['argumentos'];
														$accion=ModuloGetURL($c,$m,$t,$me,$argu);
												}
												else
												{   $accion=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarFormaBuscar');   }
												if(!$this->FormaMensaje($mensaje,'SOLICITUD AUTORIZACION',$accion,$boton)){
															return false;
												}
												return true;
									}
							}
							else
							{
										$dbconn->CommitTrans();
										$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['SOLICITUD']=$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'];
										unset($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']);
										$mensaje='La Solicitud fue Creada Satisfactoriamente.';
										if(!empty($_SESSION['SOLICITUDAUTORIZACION']['RETORNO']))
										{
												$m=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['modulo'];
												$t=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['tipo'];
												$c=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['contenedor'];
												$me=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['metodo'];
												$argu=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['argumentos'];
												$accion=ModuloGetURL($c,$m,$t,$me,$argu);
										}
										else
										{  $accion=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarFormaBuscar');  }
										if(!$this->FormaMensaje($mensaje,'SOLICITUD AUTORIZACION',$accion,$boton)){
													return false;
										}
										return true;
							}
					}
	}


	/**
	*
	*/
	function BuscarUsuarios($PlanId)
	{
			list($dbconn) = GetDBconn();
			$query = " SELECT b.nombre, b.usuario_id
									FROM planes_auditores_int as a, system_usuarios as b
									WHERE a.plan_id='$PlanId' and a.usuario_id=b.usuario_id";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			$result->Close();
			return $var;
	}

	/**
	*
	*/
	function BuscarUsuariosAuto($PlanId)
	{
			list($dbconn) = GetDBconn();
			$query = " SELECT b.nombre, b.usuario_id
									FROM planes_auditores_int as a, system_usuarios as b
									WHERE a.plan_id='$PlanId' and a.usuario_id=b.usuario_id
									and a.usuario_id=b.usuario_id and a.usuario_id!=".UserGetUID()."";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			$result->Close();
			return $var;
	}

	/**
	*
	*/
	function BuscarNombresApellidosPaciente($Ingreso)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.primer_nombre||' '||a.segundo_nombre||' '||a.primer_apellido||' '||a.segundo_apellido as nombre,
								a.tipo_id_paciente, a.paciente_id, c.semanas_cotizadas, c.tipo_afiliado_id, c.rango
								FROM pacientes as a, ingresos as b, cuentas as c
								WHERE b.ingreso=$Ingreso AND a.tipo_id_paciente=b.tipo_id_paciente AND
								a.paciente_id=b.paciente_id AND c.ingreso=b.ingreso";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$var=$result->GetRowAssoc($ToUpper = false);
			$result->Close();
 			return $var;
	}



	/**
	*
	*/
	function DetalleServicio($Solicitud)
	{
			list($dbconn) = GetDBconn();
			GLOBAL $ADODB_FETCH_MODE;
			$query = "select a.*, b.descripcion, c.descripcion as destipo, d.descripcion_corta
									from autorizaciones_solicitudes_ingreso_grupo_cargos as a, grupos_tipos_cargo as b,tipos_cargos as c, niveles_atencion as d
									where a.solicitud_id=$Solicitud
									and a.grupo_tipo_cargo=b.grupo_tipo_cargo and a.grupo_tipo_cargo=c.grupo_tipo_cargo and
									a.tipo_cargo=c.tipo_cargo and a.nivel=d.nivel order by a.grupo_tipo_cargo,
									c.descripcion, a.tipo_cargo";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			if(!$result->EOF)
			{
					while ($data = $result->FetchRow()) {
						$grupo[$data['descripcion']]+= 1;
						$tipo[$data['descripcion']][$data['destipo']] += 1;
						$nivel[$data['descripcion']][$data['destipo']][$data['descripcion_corta']] = 1;
					}

					$result=$dbconn->Execute($query);
					while(!$result->EOF)
					{
							$servicio[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
			}
			$_SESSION['SOLICITUDAUTORIZACION']['SER']['GRUPO']=$grupo;
			$_SESSION['SOLICITUDAUTORIZACION']['SER']['TIPO']=$tipo;
			$_SESSION['SOLICITUDAUTORIZACION']['SER']['NIVEL']=$nivel;

			return $servicio;
	}


	/**
	*
	*/
	function DetalleCargo($Solicitud)
	{
			list($dbconn) = GetDBconn();
			$query = "select a.*, b.descripcion
										from autorizaciones_solicitudes_ingreso_cargos as a, tarifarios_detalle as b
										where a.solicitud_id=$Solicitud and a.tarifario_id=b.tarifario_id and a.cargo=b.cargo";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while(!$result->EOF)
			{
					$cargo[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			return $cargo;
	}


	/**
	*
	*/
	function DetalleSolicitud()
	{
			if(empty($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']))// && empty($_SESSION['SOLICITUDAUTORIZACION']['DATOS']))
			{
					$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']=$_REQUEST['solicitud'];
					$_SESSION['SOLICITUDAUTORIZACION']['DATOS']=$_REQUEST['datos'];
			}

			$servicio=$this->DetalleServicio($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']);
			$cargo=$this->DetalleCargo($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']);
			if(empty($servicio) && empty($cargo))
			{
					if(!$this->FormaListadoAutorizacionesPendientes()){
							return false;
					}
					return true;
			}

			if(!$this->FormaDetalleSolictud()){
					return false;
			}
  		return true;
	}

	function LlamarFormaDetalleSolictud()
	{
			unset($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']);
			unset($_SESSION['SOLICITUDAUTORIZACION']['DATOS']);
			if(!$this->FormaListadoAutorizacionesPendientes()){
					return false;
			}
  		return true;
	}

	/**
	*
	*/
	function DetalleSolicitudAuto($mensaje)
	{
			if(empty($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']) && empty($_SESSION['SOLICITUDAUTORIZACION']['DATOS']))
			{
					$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']=$_REQUEST['solicitud'];
					$_SESSION['SOLICITUDAUTORIZACION']['DATOS']=$_REQUEST['datos'];
			}

			$servicio=$this->DetalleServicio($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']);
			$cargo=$this->DetalleCargo($_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']);
			if(empty($servicio) && empty($cargo))
			{
					if(!$this->FormaListadoAutorizaciones()){
							return false;
					}
					return true;
			}

			if(!$this->FormaDetalleSolictudAuto($servicio,$cargo,$estancia,$Solicitud,$datos,$mensaje)){
					return false;
			}
  		return true;
	}


	/**
	*
	*/
	function AutorizacionSistema()
	{
				$Observaciones=$_REQUEST['Observaciones'];
				if(!$Observaciones)
				{
						if(!$Observaciones){ $this->frmError["Observaciones"]=1; }
						$this->frmError["MensajeError"]="La observación es obligatoria.";
						if(!$this->FormaDetalleSolictudAuto()){
								return false;
						}
						return true;
				}

				$datos=$_SESSION['SOLICITUDAUTORIZACION']['DATOS'];
				$Ingreso=$datos[ingreso];
				if(empty($Ingreso))
				{  $Ingreso='NULL';}
				$Solicitud=$datos[solicitud_id];
				$swU=$datos[sw_urgente];
				$UsuAuto=$datos[usuario_id_autorizador];
				$TipoId=$datos[tipo_id_paciente];
				$PacienteId=$datos[paciente_id];
				$Solicitud=$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'];
				$servicio=$this->DetalleServicio($Solicitud);
				$cargo=$this->DetalleCargo($Solicitud);
				$Tipo=4;
				$SystemId=UserGetUID();
	 			$Fecha=date("Y-m-d H:i:s");
				list($dbconn) = GetDBconn();
				$query="SELECT nextval('autorizaciones_autorizacion_seq')";
				$result=$dbconn->Execute($query);
				$Autorizacion=$result->fields[0];
				$query = "INSERT INTO autorizaciones(
																		autorizacion,
																		fecha_autorizacion,
																		observaciones,
																		usuario_id,
																		fecha_registro,
																		sw_estado,
																		ingreso)
									VALUES ($Autorizacion,'$Fecha','$Observaciones',$SystemId,'$Fecha','1',$Ingreso)";
				$dbconn->BeginTrans();
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error INSERT INTO autorizaciones";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}
				else
				{
									$query = "INSERT INTO autorizaciones_por_sistema(
																								autorizacion,
																								usuario_id,
																								solicitud,
																								fecha_confirmacion,
																								observaciones,
																								sw_confirmacion,
																								sw_urgente)
															VALUES ($Autorizacion,'$UsuAuto','$Solicitud',NULL,'$Observaciones',1,$swU)";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Guardar en la Base de Datos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}
									else
									{
											if(!empty($cargo))
											{
												for($i=0; $i<sizeof($cargo); $i++)
												{
																$cant=$cargo[$i][cantidad];
																$tarifario=$cargo[$i][tarifario_id];
																$cod=$cargo[$i][cargo];
																$ser=$cargo[$i][servicio];
																$query = "INSERT INTO  autorizaciones_ingreso_cargos(
																																									autorizacion,
																																									tarifario_id,
																																									cargo,
																																									servicio,
																																									cantidad)
																											VALUES($Autorizacion,'$tarifario','$cod','$ser',$cant)";
																$dbconn->Execute($query);
																if ($dbconn->ErrorNo() != 0) {
																		$this->error = "Error autorizaciones_aprobadas_cargos";
																		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																		$dbconn->RollbackTrans();
																		return false;
																}
												}//fin for cargos
											}//fin if

											if(!empty($servicio))
											{
												for($i=0; $i<sizeof($servicio); $i++)
												{
																$grupo=$servicio[$i][grupo_tipo_cargo];
																$tipo=$servicio[$i][tipo_cargo];
																$nivel=$servicio[$i][nivel];
																$ser=$servicio[$i][servicio];
																$query = "INSERT INTO  autorizaciones_ingreso_grupo_cargos(autorizacion,
																																														grupo_tipo_cargo,
																																														tipo_cargo,
																																														servicio,
																																														nivel)
																VALUES($Autorizacion,'$grupo','$tipo','$ser',$nivel)";
																$dbconn->Execute($query);
																if ($dbconn->ErrorNo() != 0) {
																		$this->error = "Error autorizaciones_ingreso_grupo_cargos";
																		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																		$dbconn->RollbackTrans();
																		return false;
																}
												}//fin for servicio
											}//fin if

											$query = "delete from autorizaciones_solicitudes where solicitud_id=$Solicitud";
											$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0) {
													$this->error = "delete autorizaciones_solicitudes_cargos";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$dbconn->RollbackTrans();
													return false;
											}
											else
											{
													$dbconn->CommitTrans();
													$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','DetalleSolicitudAuto');
													$mensaje='La Solicitud fue Autorizada.';
													$this->FormaMensaje($mensaje,'SOLICTUD AUTORIZACION',$accion);
													return true;
											}
									}
				}
	}


	/**
	*
	*/
	function ListadoAutorizacionesPendientes()
	{
			$SystemId=UserGetUID();
			list($dbconn) = GetDBconn();
			$query = "select * from (select a.*, h.plan_descripcion, d.plan_id, d.tipo_afiliado_id, d.rango,
								e.tipo_afiliado_nombre,b.tipo_id_paciente, b.paciente_id, c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
								from autorizaciones_solicitudes as a left join ingresos as b on (a.ingreso=b.ingreso)
								left join cuentas as d on (b.ingreso=d.ingreso)
								left join planes as h on(d.plan_id=h.plan_id), pacientes as c, tipos_afiliado as e
								where a.usuario_id_autorizador=$SystemId and b.tipo_id_paciente=c.tipo_id_paciente and b.paciente_id=c.paciente_id
								and d.tipo_afiliado_id=e.tipo_afiliado_id
								union select a.*, h.plan_descripcion, b.plan_id, b.tipo_afiliado_id, b.rango, d.tipo_afiliado_nombre, b.tipo_id_paciente, b.paciente_id, c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
								from autorizaciones_solicitudes as a left join autorizaciones_solicitudes_externas as b
								on (a.solicitud_id=b.solicitud_id )
								left join planes as h on(b.plan_id=h.plan_id), pacientes as c, tipos_afiliado as d
								where a.usuario_id_autorizador=$SystemId and b.tipo_id_paciente=c.tipo_id_paciente
								and b.paciente_id=c.paciente_id and b.tipo_afiliado_id=d.tipo_afiliado_id) as a
								order by a.sw_urgente desc,a.fecha_registro asc";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			$result->Close();
			return $var;
	}

	/**
	*
	*/
	function ListadoAutorizacionesConfirmacion()
	{
			$SystemId=UserGetUID();
			list($dbconn) = GetDBconn();
			$query =" select a.sw_urgente, d.*, b.tipo_id_paciente, b.paciente_id, c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
								from autorizaciones_por_sistema as a, autorizaciones as d, ingresos as b, pacientes as c
								where a.usuario_id=$SystemId and sw_confirmacion=1 and
								a.autorizacion=d.autorizacion and d.ingreso=b.ingreso and
								b.tipo_id_paciente=c.tipo_id_paciente and b.paciente_id=c.paciente_id;";

			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			$result->Close();
			return $var;
	}


	/**
	*
	*/
	function ConfirmarAutorizacion()
	{
			$autorizacion=$_REQUEST['autorizacion'];
			$observacion=$_REQUEST['Observacion'];
			$Si=$_REQUEST['Autorizar'];
			$No=$_REQUEST['NoAutorizar'];
			if(!empty($Si)){ $sw=0;}
			if(!empty($No)){ $sw=2;}
			$SystemId=UserGetUID();
			$FechaRegistro=date("Y-m-d H:i:s");

			list($dbconn) = GetDBconn();
			$query =" UPDATE autorizaciones_por_sistema SET
																sw_confirmacion=$sw,
																fecha_confirmacion='$FechaRegistro',
																observaciones='$observacion'
								where autorizacion=$autorizacion";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			//aqui
			//$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','DetalleSolicitud');
			$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarFormaDetalleSolictud');
			$mensaje='La Autorizada fue confirmada.';
			$this->FormaMensaje($mensaje,'CONFIRMACION AUTORIZACION',$accion);
			return true;
	}



	/**
	*
	*/
	function ListadoAutorizaciones()
	{
			$SystemId=UserGetUID();
			list($dbconn) = GetDBconn();
			$query = "select * from (select a.*, h.plan_descripcion, d.plan_id, d.tipo_afiliado_id, d.rango,
								e.tipo_afiliado_nombre,b.tipo_id_paciente, b.paciente_id, c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
								from autorizaciones_solicitudes as a left join ingresos as b on (a.ingreso=b.ingreso)
								left join cuentas as d on (b.ingreso=d.ingreso) left join planes as h on(d.plan_id=h.plan_id),
								pacientes as c, tipos_afiliado as e
								where a.usuario_id=$SystemId and b.tipo_id_paciente=c.tipo_id_paciente and b.paciente_id=c.paciente_id
								and d.tipo_afiliado_id=e.tipo_afiliado_id
								union
								select a.*, h.plan_descripcion, b.plan_id, b.tipo_afiliado_id, b.rango, d.tipo_afiliado_nombre, b.tipo_id_paciente, b.paciente_id, c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
								from autorizaciones_solicitudes as a left join autorizaciones_solicitudes_externas as b
								on (a.solicitud_id=b.solicitud_id ) left join planes as h on(b.plan_id=h.plan_id),
								pacientes as c, tipos_afiliado as d
								where a.usuario_id=$SystemId and b.tipo_id_paciente=c.tipo_id_paciente
								and b.paciente_id=c.paciente_id and b.tipo_afiliado_id=d.tipo_afiliado_id) as a
								order by a.sw_urgente desc,a.fecha_registro asc";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			$result->Close();
			return $var;
	}


	/**
	*
	*/
	function DescripcionEstacion($Est)
	{
			list($dbconn) = GetDBconn();
			$query = "select descripcion from estaciones_enfermeria
								where estacion_id='$Est'";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$result->Close();
			return $result->fields[0];
	}

	/**
	*
	*/
	function DescripcionDpto($dpto)
	{
			list($dbconn) = GetDBconn();
			$query = "select descripcion from departamentos
								where departamento='$dpto'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$result->Close();
			return $result->fields[0];
	}

	/**
	*
	*/
	function NombreUsuario($usuario)
	{
			list($dbconn) = GetDBconn();
			$query = "select nombre from system_usuarios
								where usuario_id='$usuario'";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$result->Close();
			return $result->fields[0];
	}


	/**
	*
	*/
	function PedirAutorizacion()
	{//echo "dd=>".$_REQUEST['TipoAutorizacion'];
					//$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']=$_REQUEST['solicitud'];
					//$_SESSION['SOLICITUDAUTORIZACION']['INGRESO']=$_REQUEST['datos'][ingreso];
					//$_SESSION['SOLICITUDAUTORIZACION']['DATOS']=$_REQUEST['datos'];
					$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['contenedor']='app';
					$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['modulo']='Autorizacion_Solicitud';
					$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['tipo']='user';
					$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['metodo']='RetornoAutorizacion';

					$this->ElegirAccion($_REQUEST['TipoAutorizacion']);
					return true;
	}



	/**
	*
	*/
/*	function ElegirAccion($Tipo)
	{
			if($Tipo=='1')
			{
			  if(!$this->AutorizacionTele()){
						return false;
				}
        return true;
			}
			if($Tipo=='2')
			{
			  if(!$this->AutorizacionEscrita()){
						return false;
				}
        return true;
			}
			if($Tipo=='3')
			{
			  if(!$this->Autorizaciondb()){
						return false;
				}
        return true;
			}
			if($Tipo=='04')
			{
			  if(!$this->AutorizacionInterna()){
						return false;
				}
        return true;
			}
			if($Tipo=='5')
			{
			  if(!$this->AutorizacionElectronica()){
						return false;
				}
        return true;
			}
	}*/


	/**
	*
	*/
	function ValidarAutorizacion($Tipo,$CodAuto,$Responsable,$Validez)
	{
				if($Tipo=='1')
				{
							if(!$Responsable){
										if(!$Responsable){ $this->frmError["Responsable"]=1; }
										$this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
										return false;
							}
							return true;
				}
				if($Tipo=='2')
				{
							if(!$Validez){
										if(!$CodAuto){ $this->frmError["CodAuto"]=1; }
										$this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
										return false;
							}
							return true;
				}

				if($Tipo=='4')
				{
							if(!$Responsable){
										if(!$Responsable){ $this->frmError["FechaAuto"]=1; }
										$this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
										return false;
							}
							return true;
				}
				if($Tipo=='5')
				{
							if(!$Validez){
										if(!$Validez){ $this->frmError["Validez"]=1; }
										$this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
										return false;
							}
							return true;
				}
	}


	/**
	*
	*/
	/*function ValidarAutorizacionNo($Tipo,$FechaAuto,$HoraAuto,$MinAuto,$CodAuto,$Responsable)
	{
				if($Tipo=='1')
				{
							if(!$FechaAuto || !$HoraAuto || !$MinAuto || !$CodAuto || !$Responsable){
										if(!$FechaAuto){ $this->frmError["FechaAuto"]=1; }
										if(!$HoraAuto){ $this->frmError["HoraAuto"]=1; }
										if(!$MinAuto){ $this->frmError["HoraAuto"]=1; }
										if(!$CodAuto){ $this->frmError["CodAuto"]=1; }
										if(!$Responsable){ $this->frmError["Responsable"]=1; }
										$this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
										return false;
							}
							return true;
				}
				if($Tipo=='2')
				{
							if(!$FechaAuto || !$HoraAuto || !$MinAuto || !$CodAuto){
										if(!$FechaAuto){ $this->frmError["FechaAuto"]=1; }
										if(!$HoraAuto){ $this->frmError["HoraAuto"]=1; }
										if(!$MinAuto){ $this->frmError["HoraAuto"]=1; }
										if(!$CodAuto){ $this->frmError["CodAuto"]=1; }
										if(!$Validez){ $this->frmError["Validez"]=1; }
										$this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
										return false;
							}
							return true;
				}
				if($Tipo=='3')
				{
							if(!$FechaAuto || !$HoraAuto || !$MinAuto || !$CodAuto){
										if(!$FechaAuto){ $this->frmError["FechaAuto"]=1; }
										if(!$HoraAuto){ $this->frmError["HoraAuto"]=1; }
										if(!$MinAuto){ $this->frmError["HoraAuto"]=1; }
										$this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
										return false;
							}
							return true;
				}
				if($Tipo=='4')
				{
							if(!$FechaAuto || !$HoraAuto || !$MinAuto){
										if(!$FechaAuto){ $this->frmError["FechaAuto"]=1; }
										if(!$HoraAuto){ $this->frmError["HoraAuto"]=1; }
										if(!$MinAuto){ $this->frmError["HoraAuto"]=1; }
										$this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
										return false;
							}
							return true;
				}
				if($Tipo=='5')
				{
							if(!$FechaAuto || !$HoraAuto || !$MinAuto || !$CodAuto){
										if(!$FechaAuto){ $this->frmError["FechaAuto"]=1; }
										if(!$HoraAuto){ $this->frmError["HoraAuto"]=1; }
										if(!$MinAuto){ $this->frmError["HoraAuto"]=1; }
										if(!$CodAuto){ $this->frmError["CodAuto"]=1; }
										if(!$Validez){ $this->frmError["Validez"]=1; }
										$this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
										return false;
							}
							return true;
				}
	}*/

	/**
	*
	*/
	function InsertarCuenta($dbconn,$Cuenta,$TarifarioId,$Cargo,$Cantidad,$Servicio,$auto)
	{
			IncludeLib('tarifario_cargos');

			$liq=LiquidarCargoCuenta($Cuenta,$TarifarioId,$Cargo,$Cantidad,0,0,false,false,0,$PlanId,$Servicio,'');
			$Precio=$liq[precio_plan];
			$ValorNo=$liq[valor_no_cubierto];
			$ValorCub=$liq[valor_cubierto];
			$ValorCargo=$liq[valor_cargo];
			$Facturado=$liq[facturado];
			$DescuentoEmp=$liq[valor_descuento_empresa];
			$DescuentoPac=$liq[valor_descuento_paciente];

			$query = "select b.codigo_agrupamiento_id
								from cups as a, grupos_tipos_cargo as b
								where a.cargo='$cups' and a.grupo_tipo_cargo=b.grupo_tipo_cargo
								and b.codigo_agrupamiento_id is not NULL";
			$resul=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$cups=$_SESSION['SOLICITUDAUTORIZACION']['DATOS']['cargo_cups'];
			$query = "select b.codigo_agrupamiento_id
								from cups as a, grupos_tipos_cargo as b
								where a.cargo='$cups' and a.grupo_tipo_cargo=b.grupo_tipo_cargo
								and b.codigo_agrupamiento_id is not NULL";
			$resul=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$codigo=$resul->fields[0];
			//$codigo=$liq[codigo_agrupamiento_id];echo "cod".$liq[codigo_agrupamiento_id];exit;
			if(empty($codigo))
			{ $codigo='NULL'; }
			else
			{  $codigo="'$codigo'"; }
			if(empty($auto))
			{   $AutoInt='NULL';   }
			else
			{   $AutoInt=$auto;   }
			if(empty($liq[autorizacion_ext]))
			{   $AutoExt='NULL';   }
			else
			{   $AutoExt=$liq[autorizacion_ext];   }

			$Departamento=$_SESSION['SOLICITUDAUTORIZACION']['DATOS']['departamento'];
			$query = "SELECT empresa_id, centro_utilidad FROM departamentos
								WHERE departamento='$Departamento'";
			$results=$dbconn->Execute($query);
			/*if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Tabla autorizaiones";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}*/
			$EmpresaId=$results->fields[0];
			$CUtilidad=$results->fields[1];

			$query=" SELECT nextval('cuentas_detalle_transaccion_seq')";
			$result=$dbconn->Execute($query);
			$Transaccion=$result->fields[0];
			$query = "INSERT INTO cuentas_detalle (
																		transaccion,
																		empresa_id,
																		centro_utilidad,
																		numerodecuenta,
																		departamento,
																		tarifario_id,
																		cargo,
																		cantidad,
																		precio,
																		valor_cargo,
																		valor_nocubierto,
																		valor_cubierto,
																		usuario_id,
																		facturado,
																		fecha_cargo,
																		valor_descuento_empresa,
																		valor_descuento_paciente,
																		servicio_cargo,
																		autorizacion_int,
																		autorizacion_ext,
																		porcentaje_gravamen,
																		sw_cuota_paciente,
																		sw_cuota_moderadora,
																		codigo_agrupamiento_id,
																		fecha_registro,
																		cargo_cups)
									VALUES ($Transaccion,'$EmpresaId','$CUtilidad',$Cuenta,'$Departamento','$TarifarioId','$Cargo',$Cantidad,$Precio,$ValorCargo,$ValorNo,$ValorCub,".UserGetUID().",$Facturado,'now()',$DescuentoPac,$DescuentoEmp,$Servicio,$AutoInt,$AutoExt,".$liq[porcentaje_gravamen].",'".$liq[sw_cuota_paciente]."','".$liq[sw_cuota_moderadora]."',".$codigo.",'now()','$cups')";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Guardar en la Tabla autorizaiones";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			//return true;
	}


	/**
	*
	*/
	function Insertar()
	{
				$Tipo=$_REQUEST['Tipo'];
				$FechaAuto=$_REQUEST['FechaAuto'];
				$f=explode('/',$FechaAuto);
				$FechaAuto=$f[2].'-'.$f[1].'-'.$f[0];
				$HoraAuto=$_REQUEST['HoraAuto'];
				$MinAuto=$_REQUEST['MinAuto'];
				$Observaciones=$_REQUEST['Observaciones'];
				$Fecha=$FechaAuto." ".$HoraAuto.":".$MinAuto;

				$datos=$_SESSION['SOLICITUDAUTORIZACION']['DATOS'];
				$Ingreso=$datos[ingreso];
				if(empty($Ingreso))
				{ $Ingreso='NULL';}

				unset($_SESSION['SOLICITUDAUTORIZACION']['CARGO']);
				unset($_SESSION['SOLICITUDAUTORIZACION']['SERVICIO']);
				foreach($_REQUEST as $k => $v)
				{
						if(substr_count($k,'Cargos'))
						{
									$cargo=explode(',',$v);
									$_SESSION['SOLICITUDAUTORIZACION']['CARGO'][$cargo[0]][$cargo[1]]=$cargo[1];
						}

						if(substr_count($k,'Nivel'))
						{
									$servicio=explode(',',$v);
									$_SESSION['SOLICITUDAUTORIZACION']['SERVICIO'][$servicio[0]][$servicio[1]][$servicio[2]]=$servicio[0]."".$servicio[1]."".$servicio[2];
						}
				}

				if(empty($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']))
				{
							list($dbconn) = GetDBconn();
							$FechaRegistro=date("Y-m-d H:i:s");
							$SystemId=UserGetUID();
							$query="SELECT nextval('autorizaciones_autorizacion_seq')";
							$result=$dbconn->Execute($query);
							$Autorizacion=$result->fields[0];
							$query = "INSERT INTO autorizaciones(
																					autorizacion,
																					fecha_autorizacion,
																					observaciones,
																					usuario_id,
																					fecha_registro,
																					sw_estado,
																					ingreso)
												VALUES ($Autorizacion,'$Fecha','$Observaciones',$SystemId,'$FechaRegistro',0,$Ingreso)";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Tabla autorizaiones";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}
							$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']=$Autorizacion;
				}

				//valida si elegio el tipo de autorizacion
				if(!empty($_REQUEST['Aceptar']) && $_REQUEST['TipoAutorizacion']==-1)
				{
						$this->frmError["MensajeError"]="Debe elegir el Tipo de Autorización.";
						$this->FormaDetalleSolictud();
						return true;
				}
				elseif(!empty($_REQUEST['Aceptar']) && $_REQUEST['TipoAutorizacion']!=-1)
				{
						$this->FormaAutorizacion($_REQUEST['TipoAutorizacion']);
						return true;
				}

				//valida si se eligio algo para autorizar de la solicitud
				$f=0;
				foreach($_REQUEST as $k => $v)
				{
						if($f==0)
						{
							if(substr_count($k,'Nivel'))
							{
								if(!empty($v))
								{ $f=1; }
							}
							if(substr_count($k,'Cargos'))
							{
								if(!empty($v))
								{ $f=1; }
							}
						}
				}
				if($f==0)
				{			$this->frmError["MensajeError"]="Debe elegir la Solicitud a Autorizar.";
							$this->FormaDetalleSolictud();
							return true;
				}

				if(!$FechaAuto || !$HoraAuto || !$MinAuto)
				{
							if(!$FechaAuto){ $this->frmError["FechaAuto"]=1; }
							if(!$HoraAuto){ $this->frmError["HoraAuto"]=1; }
							if(!$MinAuto){ $this->frmError["HoraAuto"]=1; }
							$this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
							$this->FormaDetalleSolictud();
							return true;
				}

				if(!empty($_REQUEST['NoAutorizar'])) {  $sw=1;  }
				else {  $sw=0; }

				list($dbconn) = GetDBconn();
				$query = "select count(*)
									from autorizaciones_escritas as a full join autorizaciones_telefonicas as b on (a.autorizacion=b.autorizacion) full join autorizaciones_por_sistema as c on (b.autorizacion=c.autorizacion) full join autorizaciones_electronicas as d on (c.autorizacion=d.autorizacion)
									where a.autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."
									or b.autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."
									or c.autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."
									or d.autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."";
				$results = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Tabal autorizaiones";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				if($results->fields[0]==0)
				{

						$query = "select count(*)
											from autorizaciones_escritas as a full join autorizaciones_telefonicas as b on (a.autorizacion=b.autorizacion) full join autorizaciones_por_sistema as c on (b.autorizacion=c.autorizacion) full join autorizaciones_electronicas as d on (c.autorizacion=d.autorizacion)
											where a.autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."
											or b.autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."
											or c.autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."
											or d.autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."";
						$results = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Tabal autorizaiones";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						if($results->fields[0]==0)
						{
									$query = "INSERT INTO autorizaciones_por_sistema(
																								autorizacion,
																								usuario_id,
																								solicitud,
																								fecha_confirmacion,
																								observaciones,
																								sw_confirmacion)
														VALUES (".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION'].",'".UserGetUID()."','$Solicitud',NULL,'$Observaciones',0)";
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Guardar en autorizaciones_por_sistema";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
									}
						}
							/*$this->frmError["MensajeError"]="Debe Realizar algun tipo de autorizacion.";
							$this->FormaDetalleSolictud();
							return true;*/
				}


				//actualiza la autorizacion inicial
				$t=$o='';
				list($dbconn) = GetDBconn();
				if(!empty($ObservacionesT))
				{  $t="OBSERVACIONES DE LAS AUTORIZACIONES: ".$ObservacionesT;  }
				if(!empty($Observaciones))
				{  $o=" OBSERVACIONES DE LA AUTORIZACION: ".$Observaciones;  }
				$obs=$t.$o;
				$query = "UPDATE autorizaciones SET
																		fecha_autorizacion='$Fecha',
																		observaciones='$obs',
																		observacion_ingreso='$ObservacionesI',
																		sw_estado=$sw
									WHERE autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."";
				$dbconn->BeginTrans();
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Tabal autorizaiones";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}
				if(empty($_REQUEST['NoAutorizar']))
				{
							$Autorizacion=$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION'];
							$Solicitud=$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'];
							$d=0;
							foreach($_REQUEST as $k => $v)
							{
										if(substr_count($k,'Nivel'))
										{
											if(!empty($v))
											{ $d++; }
										}
										if(substr_count($k,'Cargos'))
										{
											if(!empty($v))
											{ $d++; }
										}
							}
							$contador = $_SESSION['SOLICITUDAUTORIZACION']['TAMAÑO'] - $d;
							/*if($contador>0 && empty($Observaciones))
							{
									if(!$Observacion){ $this->frmError["Observaciones"]=1; }
									$this->frmError["MensajeError"]="Debe escribir la observación.";
									$this->FormaDetalleSolictud();
									return true;
							}*/
							$d=0;
							foreach($_REQUEST as $k => $v)
							{
									if(substr_count($k,'Cargos'))
									{
											$cargo=explode(',',$v);
												$query = "INSERT INTO  autorizaciones_ingreso_cargos(
																																				autorizacion,
																																				tarifario_id,
																																				cargo,
																																				servicio,
																																				cantidad)
																						VALUES($Autorizacion,'$cargo[0]','$cargo[1]','$cargo[3]',$cargo[2])";
											$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0) {
													$this->error = "INSERT INTO  autorizaciones_ingreso_cargos";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$dbconn->RollbackTrans();
													return false;
											}
											if(!empty($_SESSION['SOLICITUDAUTORIZACION']['DATOS']['numerodecuenta']))
											{
													$this->InsertarCuenta(&$dbconn,$datos[numerodecuenta],$cargo[0],$cargo[1],$cargo[2],$cargo[3],$Autorizacion);
											}
											$d++;
									}//fin if niveles

									if(substr_count($k,'Nivel'))
									{
												$servicio=explode(',',$v);
												$query = "INSERT INTO  autorizaciones_ingreso_grupo_cargos
																																										(autorizacion,
																																										grupo_tipo_cargo,
																																										tipo_cargo,
																																										servicio,
																																										nivel)
												VALUES($Autorizacion,'$servicio[0]','$servicio[1]','$servicio[3]',$servicio[2])";
												$dbconn->Execute($query);
												if ($dbconn->ErrorNo() != 0) {
														$this->error = "Error autorizaciones_ingreso_grupo_cargos";
														$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														$dbconn->RollbackTrans();
														return false;
												}
												$d++;
									}
							}

							$query = "delete from autorizaciones_solicitudes where solicitud_id=$Solicitud";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "delete autorizaciones_solicitudes_cargos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}
				}
				else
				{  $_SESSION['SOLICITUDAUTORIZACION']['NOAUTORIZACION']['REQUEST']=$_REQUEST;  }

				$dbconn->CommitTrans();
				if(!empty($_REQUEST['NoAutorizar']))
				{
							$this->FormaJustificar($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']);
							return true;
				}
				else
				{
						unset($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']);
						$mensaje='El proceso de Autorización se termino satisfactoriamente.';
						$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','PermisosUsuario');
						if(!$this->FormaMensaje($mensaje,'AUTORIZACIONES - AUTORIZACION SOLICITUD',$accion,$boton)){
									return false;
						}
						return true;
				}
	}


	/**
	*
	*/
	function InsertarAutorizacion()
	{
				$Tipo=$_REQUEST['Tipo'];
				$CodAuto=$_REQUEST['CodAuto'];
				$Responsable=$_REQUEST['Responsable'];
				$Validez=$_REQUEST['Validez'];
				$Registro=$_REQUEST['Registro'];
				$Observaciones=$_REQUEST['Observaciones'];

				IF(!empty($Registro))
				{
						$f=explode('/',$Registro);
						$Registro=$f[2].'-'.$f[1].'-'.$f[0];
				}
				if(!empty($Validez))
				{
						$f=explode('/',$Validez);
						$Validez=$f[2].'-'.$f[1].'-'.$f[0];
				}

				/*if($Tipo!='4')
				{*/
				$Validar=$this->ValidarAutorizacion($Tipo,$CodAuto,$Responsable,$Validez); /*}
				else
				{  $Validar=true;  }*/
				if($Validar)
				{
							$Autorizacion=$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION'];
							$Solicitud=$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'];
							$SystemId=UserGetUID();
							list($dbconn) = GetDBconn();
							if($Tipo=='1')
							{
								$query = "INSERT INTO autorizaciones_telefonicas(
																							autorizacion,
																							responsable,
																							codigo_autorizacion,
																							observaciones)
														VALUES ($Autorizacion,'$Responsable','$CodAuto','$Observaciones')";
							}
							if($Tipo=='2')
							{
									$query = "INSERT INTO autorizaciones_escritas(
																							autorizacion,
																							validez,
																							codigo_autorizacion,
																							observaciones)
														VALUES ($Autorizacion,'$Validez','$CodAuto','$Observaciones')";
							}
							/*if($Tipo=='3')
							{
									$query = "INSERT INTO autorizaciones_bd(
																							autorizacion,
																							registro)
														VALUES ($Autorizacion,'$Registro')";
							}*/
							if($Tipo=='4')
							{
								if($Responsable!=UserGetUID())
								{  $sw=1;  }
								else
								{  $sw=0;  }
								$query = "INSERT INTO autorizaciones_por_sistema(
																							autorizacion,
																							usuario_id,
																							solicitud,
																							fecha_confirmacion,
																							observaciones,
																							sw_confirmacion)
														VALUES ($Autorizacion,'$Responsable','$Solicitud',NULL,'$Observaciones',$sw)";
							}
							if($Tipo=='5')
							{
									$query = "INSERT INTO autorizaciones_electronicas(
																							autorizacion,
																							validez,
																							codigo_autorizacion,
																							observaciones)
														VALUES ($Autorizacion,'$Validez','$CodAuto','$Observaciones')";
							}
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al guardar en autorizaciones";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							else
							{
										$this->frmError["MensajeError"]="La Autorización se guardo correctamente.";
										if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
										{  $this->FormaDetalleSolictud();   }
										else
										{  $this->FormaAutorizacionDirecta();   }
										return true;
							}
				}
				else
				{
						$this->frmError["MensajeError"]="ERROR DATOS VACIOS: Faltan datos obligatorios.";
						$this->FormaAutorizacion($Tipo);
						return true;
				}
	}

	/**
	*
	*/
	/*function FinalizarAutorizacion()
	{
				$contenedor=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['contenedor'];
				$modulo=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['modulo'];
				$tipo=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['tipo'];
				$metodo=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['metodo'];

				$this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo);
				return true;
	}*/

	/**
	*
	*/
	function Autorizar($Autorizacion)
	{
			$REQ=$_SESSION['SOLICITUDAUTORIZACION']['REQUEST'];
			$Solicitud=$REQ['solicitud'];
			list($dbconn) = GetDBconn();

			foreach($REQ as $k => $v)
			{
					if(substr_count($k,'Cargos,'))
					{
							$tari=explode(',',$k);
							$cargo=explode(',',$v);
							if($REQ['CargosS'.$tari[1]]!=-1)
							{
										$query = "INSERT INTO  autorizaciones_apro_cargos_provee(autorizacion,
																																							tarifario_id,
																																							cargo,
																																							cantidad,
																																							plan_proveedor_id)
																		VALUES($Autorizacion,'$cargo[0]','$cargo[1]',$cargo[2],'".$REQ['CargosS'.$tari[1]]."')";

							}
							else
							{
										$query = "INSERT INTO  autorizaciones_aprobadas_cargos(autorizacion,
																																							tarifario_id,
																																							cargo,
																																							cantidad)
																		VALUES($Autorizacion,'$cargo[0]','$cargo[1]',$cargo[2])";
							}
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "INSERT INTO  autorizaciones_apro_cargos_provee";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}
							else
							{
									$query = "delete from autorizaciones_solicitudes_cargos where solicitud_id=$Solicitud
														and tarifario_id='$cargo[0]' and cargo='$cargo[1]'";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "delete autorizaciones_solicitudes_cargos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}
							}
					}//fin if niveles

					if(substr_count($k,'Niveles'))
					{
							$servicio=explode(',',$v);
							if($REQ['Servicio'.$servicio[0]]!=-1)
							{
									$ser=explode(',',$REQ['Servicio'.$servicio[0]]);
									$query = "INSERT INTO  autorizaciones_apro_servicios_provee(autorizacion,
																																	servicio,
																																	nivel,
																																	plan_proveedor_id)
												VALUES($Autorizacion,'$servicio[0]','$servicio[1]','".$REQ['Servicio'.$servicio[0]]."')";
							}
							else
							{
										$query = "INSERT INTO  autorizaciones_aprobadas_servicios(autorizacion,
																																	servicio,
																																	nivel)
												VALUES($Autorizacion,'$servicio[0]','$servicio[1]')";
							}
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error autorizaciones_aprobadas_servicios";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}
							else
							{
									$query = "delete from autorizaciones_solicitudes_servicios where solicitud_id=$Solicitud
														and servicio='$servicio[0]'";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error delete autorizaciones_solicitudes_servicios";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}
							}
					}//fin if niveles
			}//fin foreach

			if($REQ['TipoCama'])
			{
					$TipoCama=$REQ['TipoCama'];
					if(!empty($REQ['DiasCama']))
					{  $DiasCama=$REQ['DiasCama'];  }
					else
					{  $DiasCama=0;  }
					if(!empty($REQ['Cama']))
					{
							$query = "INSERT INTO  autorizaciones_apro_estancia_provee(autorizacion,
																																				tipo_cama,
																																				dias,
																																				plan_proveedor_id)
															VALUES($Autorizacion,'$TipoCama',$DiasCama,'".$REQ['Cama']."')";
					}
					else
					{
							$query = "INSERT INTO  autorizaciones_aprobadas_estancia(autorizacion,
																																				tipo_cama,
																																				dias)
															VALUES($Autorizacion,'$TipoCama',$DiasCama)";
					}
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
					}
					else
					{
							$query = "delete from autorizaciones_solicitudes_estancia where solicitud_id=$Solicitud
												and tipo_cama='$TipoCama'";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}

					}
			}

			$query = " select a.solicitud_id,b.solicitud_id,c.solicitud_id
								from autorizaciones_solicitudes_servicios as a full join autorizaciones_solicitudes_cargos as b
								on (a.solicitud_id=b.solicitud_id) full join
								autorizaciones_solicitudes_estancia as c on (b.solicitud_id=c.solicitud_id)
								where a.solicitud_id=$Solicitud or b.solicitud_id=$Solicitud or c.solicitud_id=$Solicitud";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			else
			{
					if($result->EOF)
					{
							if(empty($_SESSION['SOLICITUDAUTORIZACION']['INGRESO']))
							{
										$query = "delete from autorizaciones_solicitudes_externas where solicitud_id=$Solicitud";
										$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0){
												return false;
										}
							}

							$query = "delete from autorizaciones_solicitudes where solicitud_id=$Solicitud";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0){
									return false;
							}
							return true;
					}
					else
					{  return true;  }
			}
	}

//-------------------------------------------------------------------------------------

	/**
	*
	*/
	function RetornoAutorizacion()
	{
				//$Solicitud=$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'];
				//$datos=$_SESSION['SOLICITUDAUTORIZACION']['DATOS'];
				$Autorizacion=$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['autorizacion'];
				$boton=$_SESSION['SOLICITUDAUTORIZACION']['NOAUTORIZACION'];
				unset($_SESSION['SOLICITUDAUTORIZACION']['RETORNO']);
				unset($_SESSION['SOLICITUDAUTORIZACION']['REQUEST']);
				unset($_SESSION['SOLICITUDAUTORIZACION']['NOAUTORIZACION']);
				if(!empty($_REQUEST['CancelarAutorizacion']))
				{
						$this->LlamarFormaDetalleSolictud();
						return true;
				}
				else
				{
						if($boton)
						{
									list($dbconn) = GetDBconn();
									$query = "update autorizaciones set sw_estado=1 where autorizacion=$Autorizacion";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Guardar en la Base de Datos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}
									//aqui
									//$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','DetalleSolicitud');
									$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarFormaDetalleSolictud');
									$mensaje='La Solicitud No fue Autorizada.';
									$this->FormaMensaje($mensaje,'SOLICTUD AUTORIZACION',$accion);
									return true;
						}
						else
						{
									//aqui
									//$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','DetalleSolicitud');
									$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarFormaDetalleSolictud');
									$mensaje='La Solicitud fue Autorizada.';
									$this->FormaMensaje($mensaje,'SOLICTUD AUTORIZACION',$accion);
									return true;
						}
				}
	}


	/**
	*
	*/
	/*function EliminarCargo()
	{
			unset($_SESSION['SOLICITUDAUTORIZACION']['VECTOR'][$_REQUEST['TipoSolicitud']][$_REQUEST['TarifarioId']][$_REQUEST['Codigo']]);
			if(sizeof($_SESSION['SOLICITUDAUTORIZACION']['VECTOR'][$_REQUEST['TipoSolicitud']][$_REQUEST['TarifarioId']])==0)
			{
					unset($_SESSION['SOLICITUDAUTORIZACION']['VECTOR'][$_REQUEST['TipoSolicitud']]);
			}
			$PlanId=$_SESSION['SOLICITUDAUTORIZACION']['plan_id'];
			$Ingreso=$_SESSION['SOLICITUDAUTORIZACION']['ingreso'];
			$_REQUEST['TipoSolicitud']='';

			$this->FormaSolicitudAutorizacion($PlanId,'','','','','');
			return true;
	}*/

	/**
	*
	*/
	function ComboServicio($Servicio)
	{
				list($dbconn) = GetDBconn();
				$query = " select distinct b.plan_descripcion, b.plan_proveedor_id from planes_proveedores_servicios as a,
									 planes_proveedores as b where a.servicio='$Servicio'
									 and b.plan_proveedor_id=a.plan_proveedor_id";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				while(!$resulta->EOF)
				{
						$vars[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
				}
				$resulta->Close();
				return $vars;
	}

	/**
	*
	*/
	function EquivCargoCama($Tipo)
	{
				list($dbconn) = GetDBconn();
				$query = " select b.* from tipos_cama as a, equiv_cargo_cama as b
										where a.tipo_cama='$Tipo'
										and a.cargo_cama=b.cargo_cama";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				$vars=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->Close();
				return $vars;
	}

	/**
	*
	*/
	function ComboCargo($Tarifario,$Cargo)
	{
				list($dbconn) = GetDBconn();
				$query = " select distinct * from (select plan_proveedor_id from
									 tarifarios_detalle as a join plan_tarifario_proveedores as b
									 on(a.tarifario_id=b.tarifario_id and a.grupo_tarifario_id=b.grupo_tarifario_id and
									 a.subgrupo_tarifario_id=b.subgrupo_tarifario_id) where a.tarifario_id='$Tarifario'
									 and a.cargo='$Cargo' union select plan_proveedor_id from excepciones_proveedores
									 as a where a.cargo='$Cargo' and a.tarifario_id='$Tarifario') as a join planes_proveedores as b
									 on (a.plan_proveedor_id=b.plan_proveedor_id);";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				while(!$resulta->EOF)
				{
						$vars[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
				}
				$resulta->Close();
				return $vars;
	}

//	select distinct * from (select plan_proveedor_id from tarifarios_detalle as a join plan_tarifario_proveedores as b on(a.tarifario_id=b.tarifario_id and a.grupo_tarifario_id=b.grupo_tarifario_id and a.subgrupo_tarifario_id=b.subgrupo_tarifario_id) where a.tarifario_id='SOAT' and a.cargo='29121' union select plan_proveedor_id from excepciones_proveedores as a where a.cargo='29121' and a.tarifario_id='SOAT') as a join planes_proveedores as b on (a.plan_proveedor_id=b.plan_proveedor_id);

//--select plan_proveedor_id from excepciones_proveedores as a where a.cargo='29121' and a.tarifario_id='SOAT'
	/**
	*
	*/
	function TiposAuto()
	{
				list($dbconn) = GetDBconn();
				$query = " SELECT tipo_autorizacion,descripcion FROM tipos_autorizacion
									 WHERE tipo_autorizacion not in(3)";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				while(!$resulta->EOF)
				{
						$vars[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
				}
				$resulta->Close();
				return $vars;
	}
//------------------------------------------------------------------------


	/**
	*
	*/
	function InsertarAutorizacionInicial()
	{
			if(empty($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']))
			{
						if(empty($_SESSION['SOLICITUDAUTORIZACION']['INGRESO']))
						{  $Ingreso='NULL';  }
						else
						{  $Ingreso=$_SESSION['SOLICITUDAUTORIZACION']['INGRESO'];  }
						$FechaRegistro=date("Y-m-d H:i:s");
						$SystemId=UserGetUID();
						list($dbconn) = GetDBconn();
						$query="SELECT nextval('autorizaciones_autorizacion_seq')";
						$result=$dbconn->Execute($query);
						$Autorizacion=$result->fields[0];
						$query = "INSERT INTO autorizaciones(
																				autorizacion,
																				fecha_autorizacion,
																				observaciones,
																				usuario_id,
																				fecha_registro,
																				sw_estado,
																				ingreso)
											VALUES ($Autorizacion,'$FechaRegistro','',$SystemId,'$FechaRegistro',0,$Ingreso)";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error INSERT INTO autorizaciones";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']=$Autorizacion;
			}
	}

	/**
	*
	*/
	function CargosSolicitadosAutorizacion()
	{
			list($dbconn) = GetDBconn();
			$query = "select a.*, b.descripcion
										from autorizaciones_ingreso_cargos as a, tarifarios_detalle as b
										where a.autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."
										and a.tarifario_id=b.tarifario_id and a.cargo=b.cargo";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			if(!$result->EOF)
			{
					while(!$result->EOF)
					{
							$cargos[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
			}

			//servicios
			GLOBAL $ADODB_FETCH_MODE;
			$query = "select a.*, b.descripcion, c.descripcion as destipo, d.descripcion_corta
									from autorizaciones_ingreso_grupo_cargos as a, grupos_tipos_cargo as b,tipos_cargos as c, niveles_atencion as d
									where a.autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."
									and a.grupo_tipo_cargo=b.grupo_tipo_cargo and a.grupo_tipo_cargo=c.grupo_tipo_cargo and
									a.tipo_cargo=c.tipo_cargo and a.nivel=d.nivel order by a.grupo_tipo_cargo,
									c.descripcion, a.tipo_cargo";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if(!$result->EOF)
			{
					while ($data = $result->FetchRow()) {
						$grupo[$data['descripcion']]+= 1;
						$tipo[$data['descripcion']][$data['destipo']] += 1;
						$nivel[$data['descripcion']][$data['destipo']][$data['descripcion_corta']] = 1;
					}

					$result=$dbconn->Execute($query);
					while(!$result->EOF)
					{
							$servicio[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
			}

			$this->FormaSolicitud($cargos,$servicio,$grupo,$tipo,$nivel);
			return true;
	}


		/**
	*
	*/
	function InsertarAutorizacionDirecta()
	{
				$Tipo=$_REQUEST['Tipo'];
				$FechaAuto=$_REQUEST['FechaAuto'];
				$HoraAuto=$_REQUEST['HoraAuto'];
				$MinAuto=$_REQUEST['MinAuto'];
				$Observaciones=$_REQUEST['Observaciones'];
				$ObservacionesI=$_REQUEST['ObservacionesI'];
				$f=explode('/',$FechaAuto);
				$FechaAuto1=$f[2].'-'.$f[1].'-'.$f[0];
				$Fecha=$FechaAuto1." ".$HoraAuto.":".$MinAuto;
				$Ingreso='NULL';
				$_SESSION['SOLICITUDAUTORIZACION']['AFILIADO'][$_REQUEST['TipoAfiliado']]=$_REQUEST['TipoAfiliado'];
				$_SESSION['SOLICITUDAUTORIZACION']['RANGO'][$_REQUEST['Nivel']]=$_REQUEST['Nivel'];
				$_SESSION['SOLICITUDAUTORIZACION']['SEMANAS'][$_REQUEST['Semanas']]=$_REQUEST['Semanas'];

				//valida si elegio el tipo de autorizacion
				if(!empty($_REQUEST['Aceptar']) && $_REQUEST['TipoAutorizacion']==-1)
				{
						$this->frmError["MensajeError"]="Debe elegir el Tipo de Autorización.";
						$this->FormaAutorizacionDirecta();
						return true;
				}
				elseif(!empty($_REQUEST['Aceptar']) && $_REQUEST['TipoAutorizacion']!=-1)
				{
						$this->FormaAutorizacion($_REQUEST['TipoAutorizacion']);
						return true;
				}

				list($dbconn) = GetDBconn();
				$query = "(select autorizacion from autorizaciones_ingreso_grupo_cargos
									where autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION'].")
									union
									(select autorizacion from autorizaciones_ingreso_cargos
									where autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION'].")";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar 1en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}
				if($result->EOF)
				{
						$this->frmError["MensajeError"]="ERROR DATOS VACIOS:  Debe Adicionar Servicios o Cargos a la Autorización.";
						$this->AutorizacionServicio();
						return true;
				}

				if(!$FechaAuto || !$HoraAuto || !$MinAuto)
				{
							if(!$FechaAuto){ $this->frmError["FechaAuto"]=1; }
							if(!$HoraAuto){ $this->frmError["HoraAuto"]=1; }
							if(!$MinAuto){ $this->frmError["HoraAuto"]=1; }
							$this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
							$this->FormaAutorizacionDirecta();
							return true;
				}

				$query = "select count(*)
									from autorizaciones_escritas as a full join autorizaciones_telefonicas as b on (a.autorizacion=b.autorizacion) full join autorizaciones_por_sistema as c on (b.autorizacion=c.autorizacion) full join autorizaciones_electronicas as d on (c.autorizacion=d.autorizacion)
									where a.autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."
									or b.autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."
									or c.autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."
									or d.autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."";
				$results = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Tabal autorizaiones";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				if($results->fields[0]==0)
				{
							$query = "INSERT INTO autorizaciones_por_sistema(
																						autorizacion,
																						usuario_id,
																						solicitud,
																						fecha_confirmacion,
																						observaciones,
																						sw_confirmacion)
												VALUES (".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION'].",'".UserGetUID()."','$Solicitud',NULL,'$Observaciones',0)";
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en autorizaciones_por_sistema";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
						/*	$this->frmError["MensajeError"]="Debe Realizar algun tipo de autorizacion.";
							$this->FormaAutorizacionDirecta();
							return true;*/
				}

				if($_REQUEST['NoAutorizar'] && empty($Observaciones)) {  $sw=1;  }
				else {  $sw=0; }

				//actualiza la autorizacion inicial
				//list($dbconn) = GetDBconn();
				$t=$o='';
				if(!empty($ObservacionesT))
				{  $t="OBSERVACIONES DE LAS AUTORIZACIONES: ".$ObservacionesT;  }
				if(!empty($Observaciones))
				{  $o=" OBSERVACIONES DE LA AUTORIZACION: ".$Observaciones;  }
				$obs=$t.$o;
				$query = "UPDATE autorizaciones SET
																		fecha_autorizacion='$Fecha',
																		observaciones='$obs',
																		observacion_ingreso='$ObservacionesI',
																		sw_estado=$sw
									WHERE autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."";
				$dbconn->BeginTrans();
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Tabal autorizaiones";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}
				else
				{
						$dbconn->CommitTrans();
						if(!empty($_REQUEST['NoAutorizar']))
						{
									$this->FormaJustificar($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']);
									return true;
						}
						else
						{
								$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','LlamarFormaBuscar');
								$mensaje='El Número de Autorizacion es '.$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION'];
								unset($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']);
								$this->FormaMensaje($mensaje,'AUTORIZACION',$accion);
								return true;
						}
				}
	}


	/**
	*
	*/
	function Saldo($Ingreso)
	{
				list($dbconn) = GetDBconn();
				 $query = "select (a.total_cuenta - a.valor_cubierto - (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo
									from cuentas as a, ingresos as b
									where b.ingreso=$Ingreso and b.ingreso=a.ingreso";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$var=$result->fields[0];
				$result->Close();
				return $var;
	}

	/**
	*
	*/
	function Justificacion()
	{
				list($dbconn) = GetDBconn();
				$query = "select * from autorizacion_justificacion order by autorizacion_justificacion_id";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				while(!$result->EOF)
				{
						$var[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
				$result->Close();
				return $var;
	}


	/**
	*
	*/
	function Observaciones()
	{
				list($dbconn) = GetDBconn();
				$query = "select observaciones from autorizaciones_telefonicas
									where autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while(!$result->EOF)
				{
						$obs.=$result->fields[0]." ";
						$result->MoveNext();
				}
				$query = "select observaciones from autorizaciones_escritas
									where autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while(!$result->EOF)
				{
						$obs.=$result->fields[0]." ";
						$result->MoveNext();
				}
				$query = "select observaciones from autorizaciones_electronicas
									where autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while(!$result->EOF)
				{
						$obs.=$result->fields[0]." ";
						$result->MoveNext();
				}
				$query = "select observaciones from autorizaciones_por_sistema
									where autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				while(!$result->EOF)
				{
						$obs.=$result->fields[0]." ";
						$result->MoveNext();
				}
				return $obs;
	}


	/**
	*
	*/
	function JustificarNoAutorizacion()
	{
				if(empty($_REQUEST['Observaciones']))
				{
						$this->frmError["MensajeError"]="Debe Elegir o Digitar la justificación de la No Autorización.";
						$this->FormaJustificar($_REQUEST['auto']);
						return true;
				}

				list($dbconn) = GetDBconn();
				$query = "select observaciones from autorizaciones where autorizacion=".$_REQUEST['auto']."";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Tabal autorizaiones";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$obs = $result->fields[0];
				$obs .=$_REQUEST['Observaciones'];

				$query = "UPDATE autorizaciones SET
																		observaciones='$obs'
									WHERE autorizacion=".$_REQUEST['auto']."";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Tabal autorizaiones";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				if(!empty($_SESSION['SOLICITUDAUTORIZACION']['NOAUTORIZACION']['REQUEST']))
				{
							$Autorizacion=$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION'];
							$Solicitud=$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD'];
							$_REQUEST=$_SESSION['SOLICITUDAUTORIZACION']['NOAUTORIZACION']['REQUEST'];
							$d=0;
							foreach($_REQUEST as $k => $v)
							{
										if(substr_count($k,'Nivel'))
										{
											if(!empty($v))
											{ $d++; }
										}
										if(substr_count($k,'Cargos'))
										{
											if(!empty($v))
											{ $d++; }
										}
							}
							$contador = $_SESSION['SOLICITUDAUTORIZACION']['TAMAÑO'] - $d;
							/*if($contador>0 && empty($Observaciones))
							{
									if(!$Observacion){ $this->frmError["Observaciones"]=1; }
									$this->frmError["MensajeError"]="Debe escribir la observación.";
									$this->FormaDetalleSolictud();
									return true;
							}*/
							$d=0;
							foreach($_REQUEST as $k => $v)
							{
									if(substr_count($k,'Cargos'))
									{
											$cargo=explode(',',$v);
											 $query = "INSERT INTO  autorizaciones_ingreso_cargos(
																																				autorizacion,
																																				tarifario_id,
																																				cargo,
																																				servicio,
																																				cantidad)
																						VALUES($Autorizacion,'$cargo[0]','$cargo[1]','$cargo[3]',$cargo[2])";
											$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0) {
													$this->error = "INSERT INTO  autorizaciones_ingreso_cargos";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$dbconn->RollbackTrans();
													return false;
											}
											$d++;
									}//fin if niveles

									if(substr_count($k,'Nivel'))
									{
												$servicio=explode(',',$v);
												$query = "INSERT INTO  autorizaciones_ingreso_grupo_cargos
																																										(autorizacion,
																																										grupo_tipo_cargo,
																																										tipo_cargo,
																																										servicio,
																																										nivel)
												VALUES($Autorizacion,'$servicio[0]','$servicio[1]','$servicio[3]',$servicio[2])";
												$dbconn->Execute($query);
												if ($dbconn->ErrorNo() != 0) {
														$this->error = "Error autorizaciones_ingreso_grupo_cargos";
														$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														$dbconn->RollbackTrans();
														return false;
												}
												$d++;
									}
						}
						$query = "delete from autorizaciones_solicitudes where solicitud_id=$Solicitud";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "delete autorizaciones_solicitudes_cargos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
						}
						$dbconn->CommitTrans();
				}

				unset($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']);
				$mensaje='El proceso de Autorización se termino satisfactoriamente.';
				$accion=ModuloGetURL('app','Autorizacion_Solicitud','user','PermisosUsuario');
				if(!$this->FormaMensaje($mensaje,'AUTORIZACIONES - AUTORIZACION SOLICITUD',$accion,$boton)){
							return false;
				}
				return true;
	}


	/**
	*
	*/
	function EliminarCargoS()
	{
			list($dbconn) = GetDBconn();
			if(empty($_SESSION['SOLICITUDAUTORIZACION']['DIRECTA']))
			{
					$query = "delete from  autorizaciones_solicitudes_ingreso_cargos
											where solicitud_id=".$_SESSION['SOLICITUDAUTORIZACION']['SOLICITUD']."
											and tarifario_id='".$_REQUEST['TarifarioId']."'
											and cargo='".$_REQUEST['Codigo']."'
											and servicio=".$_SESSION['SOLICITUDAUTORIZACION']['servicio']."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error autorizaciones_solicitudes_ingreso_cargos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
			}
			else
			{
					$query = "delete from  autorizaciones_ingreso_cargos
											where autorizacion=".$_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']."
											and tarifario_id='".$_REQUEST['TarifarioId']."'
											and cargo='".$_REQUEST['Codigo']."'
											and servicio=".$_SESSION['SOLICITUDAUTORIZACION']['servicio']."";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error autorizaciones_solicitudes_ingreso_cargos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
			}

			$this->LlamarFormaSolicitudAutorizacion();
			return true;
	}

	/**
	*
	*/
	function BuscarProtocolo($PlanId)
	{
       	list($dbconn) = GetDBconn();
				$query = "select protocolos from planes
														where plan_id='$PlanId'";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}
				$result->Close();
				return $result->fields[0];
	}

	/**
	*
	**/
	function NombrePlan($PlanId)
	{
       	list($dbconn) = GetDBconn();
				$query = "select plan_descripcion from planes
														where plan_id='$PlanId'";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}
				$result->Close();
				return $result->fields[0];
	}
//------------------------------------------------------------------------------
}//fin clase user
?>

