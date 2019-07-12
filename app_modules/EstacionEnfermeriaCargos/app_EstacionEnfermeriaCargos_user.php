<?

 /**
 * $Id: app_EstacionEnfermeriaCargos_user.php,v 1.9 2005/09/13 18:40:23 darling Exp $
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
class app_EstacionEnfermeriaCargos_user extends classModulo
{
	var $frmError = array();


	/**
	*		app_EstacionEnfermeria_user()
	*
	*		constructor
	*
	*		@Author Darling Liliana Dorado
	*		@access Public
	*		@return bool
	*/
	function app_EstacionEnfermeriaCargos_user()//Constructor padre
	{
		return true;
	}

	/**
	*
	*/
	function main($estacion,$tipo)
	{
			if(empty($estacion))
			{
					$estacion=$_REQUEST['estacion'];
					$tipo=$_REQUEST['tipoa'];
			}

			UNSET($_SESSION['CUENTAS']['E']);
			$_SESSION['CUENTAS']['E']['DATOS']=$estacion;

			if(!$this->FormaListadoPacientes($estacion,$tipo))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmInsumosPaciente\"";
				return false;
			}
			return true;
	}//FIN main

  /**
	* Busca si el cargo existe en la table cuentas_detalle
	* @access public
	* @return array
	* @param int numero de la cuenta
	* @param int codigo del cargo
	*/
	function ExisteCargo($Cuenta,$Cargo)
	{
				list($dbconn) = GetDBconn();
			 	$query="SELECT transaccion  FROM tmp_cuentas_detalle WHERE numerodecuenta=$Cuenta AND cargo='$Cargo'";
				$result=$dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$result->Close();
				if(!$result->EOF) return $result->fields[0];
				else return false;
	}



  /**
	* Llama la forma FormaCargos que insertar nuevos cargos.
	* @access public
	* @return boolean
	*/
	 function Cargos()
	 {
			$Cuenta=$_REQUEST['Cuenta'];
			$TipoId=$_REQUEST['TipoId'];
			$PacienteId=$_REQUEST['PacienteId'];
			$Nivel=$_REQUEST['Nivel'];
			$PlanId=$_REQUEST['PlanId'];

			$_SESSION['CUENTAS']['E']['tipo_id_paciente']=$TipoId;
			$_SESSION['CUENTAS']['E']['paciente_id']=$PacienteId;

			list($dbconn) = GetDBconn();
			$query = "select a.departamento,b.empresa_id, b.centro_utilidad
								from estaciones_enfermeria as a, departamentos as b
								where a.estacion_id='".$_REQUEST['estacion']['estacion_id']."'
								and a.departamento=b.departamento";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$query = "select a.departamento,b.empresa_id, b.centro_utilidad
								from estaciones_enfermeria as a, departamentos as b
								where a.estacion_id='".$_REQUEST['estacion']['estacion_id']."'
								and a.departamento=b.departamento";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$_SESSION['CUENTAS']['E']['CENTROUTILIDAD']=$result->fields[2];
			$_SESSION['CUENTAS']['E']['EMPRESA']=$result->fields[1];
			$_SESSION['CUENTAS']['E']['DEPTO']=$result->fields[0];
			$_SESSION['CUENTAS']['E']['INGRESO']=$_REQUEST['ingreso'];
			$_SESSION['CUENTAS']['E']['ESTACION']=$_REQUEST['estacion']['estacion_id'];
//			$_SESSION['CUENTA']['Insumos']=$_REQUEST['Insumos'];

			$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$_REQUEST['estacion']);
			return true;
	 }

	function DatosTmpCuentas($Cuenta)
	{
				$Usuario=UserGetUID();
				list($dbconn) = GetDBconn();
			  $query="SELECT * FROM tmp_cuentas_detalle WHERE numerodecuenta=$Cuenta AND usuario_id=$Usuario";
				$result=$dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
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

	function DatosTmpAyudas($Cuenta)
	{
				$Usuario=UserGetUID();
				list($dbconn) = GetDBconn();
			  $query="SELECT transaccion,cargo,precio,cantidad,valor_cargo,
								gravamen_valor_nocubierto,gravamen_valor_cubierto,valor_cuota_paciente,
								valor_nocubierto,valor_cubierto,fecha_registro,tarifario_id,consecutivo,
								numerodecuenta, departamento
				        FROM tmp_ayudas_diagnosticas
								WHERE numerodecuenta=$Cuenta AND usuario_id=$Usuario and sw_pasa=0";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

				while(!$result->EOF)
				{
					$var[]= $result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
				}
				$result->Close();
				return $var;
	}

	function DatosAyudasPasa($Cuenta)
	{
				$Usuario=UserGetUID();
				list($dbconn) = GetDBconn();
			  $query="SELECT *
				        FROM tmp_ayudas_diagnosticas WHERE numerodecuenta=$Cuenta
								AND usuario_id=$Usuario AND sw_pasa=1";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

				while(!$result->EOF)
				{
					$var[]= $result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
				}
		$result->Close();
		return $var;
	}

	function BuscarCagoAgrupado($Cargo)
	{
			list($dbconn) = GetDBconn();
			$query = "select cargo_agrupamiento_sistema from grupos_tipos_cargo
								where cargo_agrupamiento_sistema='$Cargo'";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
			}

			$var=$result->GetRowAssoc($ToUpper = false);
			return $var;
	}

	function BuscarNombreCargo($TarifarioId,$Cargo)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT descripcion,precio FROM tarifarios_detalle WHERE tarifario_id='$TarifarioId' AND cargo='$Cargo'";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$var[0]=$result->fields[0];
				$var[1]=$result->fields[1];
			$result->Close();
			return $var;
	}

	function BuscarNombreDpto($Departamento)
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT descripcion FROM departamentos WHERE departamento='$Departamento'";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			$result->Close();
			return $result->fields[0];
	}


	function TiposSolicitud()
	{
			list($dbconn) = GetDBconn();
			/*$query = " 	SELECT tipo_solicitud_id, descripcion
									FROM tipo_solicitud
									WHERE tipo_solicitud_id not in(0,4,5,7,6,9)";*/
			$query = " 	SELECT grupo_tipo_cargo, descripcion
									FROM grupos_tipos_cargo
									WHERE grupo_tipo_cargo!='SYS'";
			$result=$dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al eliminar en la Base de Datos";
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

	function BuscarDpto($Transaccion,$Cuenta)
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT departamento FROM tmp_cuentas_detalle WHERE transaccion=$Transaccion AND numerodecuenta=$Cuenta";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			$result->Close();
			return $result->fields[0];
	}

	function CoutaPaciente($PlanId,$Nivel)
	{
				list($dbconn) = GetDBconn();
				$sqlcuota="SELECT copago, cuota_moderadora, copago_maximo, copago_minimo
								FROM planes_rangos
								WHERE rango='$Nivel' AND plan_id='$PlanId'";
				$cuota=$dbconn->Execute($sqlcuota);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$Pac= $cuota->GetRowAssoc($ToUpper = false);
				$cuota->Close();
        return $Pac;
	}


	function EliminarTodosCargos()
	{
				$Cuenta=$_REQUEST['Cuenta'];
				$estacion=$_REQUEST['estacion'];
				$Transaccion=$_REQUEST['Transaccion'];

				list($dbconn) = GetDBconn();
				$query1 =" SELECT * FROM tmp_cuentas_detalle WHERE numerodecuenta=$Cuenta";
				$result=$dbconn->Execute($query1);
				$query =" DELETE FROM tmp_cuentas_detalle WHERE numerodecuenta=$Cuenta";
				$dbconn->BeginTrans();
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}
				else{
							$query =" DELETE FROM tmp_ayudas_diagnosticas WHERE numerodecuenta=$Cuenta";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en la Base de Datos";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
							}
							else{
											$dbconn->CommitTrans();
											$x=$result->RecordCount();
											if($x)
											{
													$accion = ModuloGetURL('app','EstacionEnfermeriaCargos','user','main',array("estacion"=>$estacion,'tipoa'=>1));
													//$accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
													$mensaje='Todos los cargos fueron borrados.';
													if(!$this->FormaMensaje($mensaje,'ELIMINAR TODOS LOS CARGOS',$accion,$boton)){
															return false;
													}
													return true;
											}
											else
											{
													if(!$this->main($_SESSION['CUENTAS']['E']['DATOS'],1)){
															return false;
													}
													return true;
											}
						}
			}
	}


	 function InsertarCargoTmp()
	 {
	 			IncludeLib("tarifario");
				$Departamento=$_REQUEST['Departamento'];
				$ValorNo=$_REQUEST['ValorNo'];
				$ValorPac=$_REQUEST['ValorPac'];
				$Precio=$_REQUEST['Precio'];
				$Cargo=$_REQUEST['Cargo'];
				$ValEmpresa=$_REQUEST['ValorEmp'];
				$TarifarioId=$_REQUEST['TarifarioId'];
				$GrupoTarifario=$_REQUEST['GrupoTarifario'];
				$SubGrupoTarifario=$_REQUEST['SubGrupoTarifario'];
				$Gravamen=$_REQUEST['Gravamen'];
				$Cantidad=$_REQUEST['Cantidad'];
				$Cuenta=$_REQUEST['Cuenta'];
				$Nivel=$_REQUEST['Nivel'];
				$PlanId=$_REQUEST['PlanId'];
				$Ingreso=$_REQUEST['Ingreso'];
				$Fecha=$_REQUEST['Fecha'];
				$TipoId=$_REQUEST['TipoId'];
				$PacienteId=$_REQUEST['PacienteId'];
				$FechaCargo=$_REQUEST['FechaCargo'];
				$ValCubierto=$_REQUEST['ValorCubierto'];
				$Cobertura=$_REQUEST['Cobertura'];
				$estacion=$_REQUEST['estacion'];
				$CUtilidad=$_SESSION['CUENTAS']['E']['CENTROUTILIDAD'];
				$EmpresaId=$_SESSION['CUENTAS']['E']['EMPRESA'];
				$SystemId=UserGetUID();

				$var[1]=$Departamento;
				$var[2]=$TarifarioId;
				$var[3]=$Cargo;
				$var[4]=$Cantidad;
				$var[5]=$Precio;
				$var[6]=$Gravamen;
				$var[9]=$GrupoTarifario;
				$var[10]=$SubGrupoTarifario;
				$var[11]=$FechaCargo;

				if(!$Cantidad || !$Cargo || !$FechaCargo){
						if(!$Cantidad){ $this->frmError["Cantidad"]=1; }
						if(!$Cargo){ $this->frmError["Cargo"]=1; }
						if(!$FechaCargo){ $this->frmError["FechaCargo"]=1; }
						$this->frmError["MensajeError"]="Faltan datos obligatorios.";
						if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$estacion,$D,$var,$Ayudas,$Cobertura)){
							return false;
						}
						return true;
				}

				list($dbconn) = GetDBconn();
				$query ="SELECT b.servicio
								FROM departamentos as b
								WHERE b.departamento='$Departamento'";
				$results = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$Servicio=$results->fields[0];

				$f = (int) $Cantidad;
				$y = $Cantidad - $f;
				if($y != 0){
						if($y != 0){ $this->frmError["Cantidad"]=1; }
						$this->frmError["MensajeError"]="La Cantidad debe ser entera.";
						if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$dpto,$estacion,'Modi')){
							return false;
						}
						return true;
				}
//----------esto es cuando digitan el codigo del cargo---------------
				if(!$TarifarioId)
				{
						$key1="cargo";
						$filtro = "( lower ($key1) like '%$Cargo' or lower ($key1) like '%$Cargo%' or lower ($key1) like '$Cargo%')";
						$campos_select = " tarifario_id, grupo_tarifario_id, subgrupo_tarifario_id, sw_cantidad ";
						$resulta = PlanTarifario($PlanId, '', '', '', '', '', '', $filtro, $campos_select, $fetch_mode_assoc=false,'','');
						if($resulta->RecordCount() > 1)
						{
								$this->frmError["MensajeError"]="Existen dos cargos con el mismo Codígo, Porfavor Busque el Cargo.";
								if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,'',$Cobertura)){
									return false;
								}
								return true;
						}
						else
						{
								$arreglo=$resulta->GetRowAssoc($ToUpper = false);
								$TarifarioId=$arreglo[tarifario_id];
								$GrupoTarifario=$arreglo[grupo_tarifario_id];
								$SubGrupoTarifario=$arreglo[subgrupo_tarifario_id];
						}
				}
//------------------------------------------------------------------------------
				if(empty($_REQUEST['Descripcion']))
				{
						$x=$this->BuscarNombreCargo($TarifarioId,$Cargo);
						$Descripcion=$x[0];
				}
				else
				{  $Descripcion=$_REQUEST['Descripcion'];  }

				list($dbconn) = GetDBconn();
				$query="SELECT cargo FROM tmp_cuentas_detalle
								WHERE cargo='$Cargo' AND tarifario_id='$TarifarioId' and numerodecuenta=$Cuenta";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				if(!$result->EOF){ $Existe=1; }
				$query="SELECT cargo FROM tmp_ayudas_diagnosticas
								WHERE cargo='$Cargo' AND tarifario_id='$TarifarioId' and numerodecuenta=$Cuenta";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				if(!$result->EOF){ $ExisteA=1; }

				if($Existe || $ExisteA)
				{
						$this->frmError["MensajeError"]="Este cargo ya existe, debe modificar la cantidad del cargo existente para agregar uno.";
						if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
							return false;
						}
						return true;
				}

				$Agrupado=$this->TipoSolicitud($Cargo,$TarifarioId,$GrupoTarifario,$SubGrupoTarifario);
				$CargoD=$Agrupado[cargo_agrupamiento_sistema];
				if(!empty($CargoD))
				{//se agrupan en cuentas detalle y cuentas
						$Apoyo=true;
				}
				else{
							$CargoD=$Cargo;
				}

				$SW=0;//no existe
				if($Apoyo)
				{
						$sql =	"SELECT c.transaccion FROM  cuentas_detalle as c
											WHERE c.cargo='$CargoD' AND c.numerodecuenta=$Cuenta";
						$result=$dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}

						unset($_SESSION['FACTURACION']['PASA']);
						if(!$result->EOF)
						{		$SW=$result->fields[0];   }
						else
						{
									$sql =	"SELECT c.transaccion FROM  tmp_cuentas_detalle as c
														WHERE c.cargo='$CargoD' AND c.numerodecuenta=$Cuenta";
									$result=$dbconn->Execute($sql);
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
									if(!$result->EOF)
									{
											$SW=$result->fields[0];
											$_SESSION['FACTURACION']['PASA']=true;
									}
									else
									{  $SW=$this->ExisteCargo($Cuenta,$CargoD);  }
						}
				}
//----------------------------esto es para los calculos-------------------------
				$Liq=LiquidarCargoCuenta($Cuenta,$TarifarioId,$Cargo,$Cantidad,0,0,false,false,'',$Servicio,$PlanId,'','',0,true,'','',0,true);
				$DescuentoEmp=$Liq[valor_descuento_empresa];
				$DescuentoPac=$Liq[valor_descuento_paciente];
				$Moderadora=$Liq[cuota_moderadora];
				$Precio=$Liq[precio_plan];
				$ValorCargo=$Liq[valor_cargo];
				$GravamenEmp=$Liq[gravamen_empresa];
				$GravamenPac=$Liq[gravamen_paciente];
				$ValorPac=$Liq[copago];
				$ValorNo=$Liq[valor_no_cubierto];
				$ValorCub=$Liq[valor_cubierto];
				$ValEmpresa=$Liq[valor_empresa];
				$PorEmp=$Liq[porcentaje_descuento_empresa];
				$PorPac=$Liq[porcentaje_descuento_paciente];
				$AutoExt=$Liq[autorizacion_ext];
				$AutoInt=$Liq[autorizacion_int];
//-------------------------------------------------------------------------------
				//tiene auto externa
				if(!empty($AutoExt))
				{  $mensaje=$Liq[msg_requerimiento_aut_ext];   }
				//tiene auto interna
				if(!empty($AutoInt))
				{   $mensaje=$Liq[msg_requerimiento_aut_int];   }

				//valida si no necesita autorizacion
				$query = "select autorizacion_cobertura('$PlanId','$TarifarioId','$Cargo','$Servicio')";
								$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				if($result->fields[0]=='NoCobertura' || $result->fields[0]=='NULL')
				{   $msg='El Plan No Cubre el Cargo.';   }

				//no tiene ninguna y necesita autorizacion
				if(empty($AutoExt) AND empty($AutoInt) AND ($result->fields[0]!='NoRequiere'))
				{
							$usu=$this->BuscarUsuarios($PlanId);
							unset($_SESSION['SOLICITUDAUTORIZACION']);
							unset($_SESSION['AUTORIZACIONES']);
							if(!empty($usu))
							{
									$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['CUENTAS']['E']['tipo_id_paciente'];
									$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['CUENTAS']['E']['paciente_id'];
									$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO']=$Cargo;
									$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TARIFARIO']=$TarifarioId;
									$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$PlanId;
									$_SESSION['AUTORIZACIONES']['AUTORIZAR']['cantidad']=$Cantidad;
									$_SESSION['FACTURACION']['CARGO']=$Cargo;
									$_SESSION['FACTURACION']['CANTIDAD']=$Cantidad;
									$_SESSION['FACTURACION']['TARIFARIO']=$TarifarioId;
									$_SESSION['FACTURACION']['CargoD']=$CargoD;
									$_SESSION['FACTURACION']['Apoyo']=$Apoyo;
									$_SESSION['FACTURACION']['SW']=$SW;
									$_SESSION['FACTURACION']['LIQ']=$Liq;
									$_SESSION['FACTURACION']['DEPTO']=$Departamento;
									$_SESSION['FACTURACION']['SERVICIO']=$Servicio;
									$_SESSION['FACTURACION']['FECHACARGO']=$FechaCargo;

									$mensaje='El Cargo: '.$Descripcion.' Necesita Autorización para ser Cargado.';
									$arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$_SESSION['CUENTAS']['E']['tipo_id_paciente'],'PacienteId'=>$_SESSION['CUENTAS']['E']['paciente_id'],'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'estacion'=>$estacion);
									$c='app';
									$m='EstacionEnfermeriaCargos';
									$me='AutorizarCargo';
									$me2='Cargos';
									$Titulo='AUTORIZAR CARGO';
									$boton1='ACEPTAR';
									$boton2='CANCELAR';
									$this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
									return true;
							}
							else
							{
										$_SESSION['DATOS']['SOLICITUDAUTORIZACION']['departamento']=$Departamento;
										$_SESSION['SOLICITUDAUTORIZACION']['plan_id']=$PlanId;
										$_SESSION['SOLICITUDAUTORIZACION']['ingreso']=$_SESSION['CUENTAS']['E']['INGRESO'];
										$_SESSION['SOLICITUDAUTORIZACION']['rango']=$Nivel;
										$_SESSION['SOLICITUDAUTORIZACION']['paciente_id']=$_SESSION['CUENTAS']['E']['paciente_id'];
										$_SESSION['SOLICITUDAUTORIZACION']['tipo_id_paciente']=$_SESSION['CUENTAS']['E']['tipo_id_paciente'];
										$_SESSION['SOLICITUDAUTORIZACION']['tipo_afiliado_id']=$_REQUEST['Afiliado'];
										$_SESSION['SOLICITUDAUTORIZACION']['servicio']=$Servicio;
										$_SESSION['SOLICITUDAUTORIZACION']['cantidad']=$Cantidad;
										$_SESSION['SOLICITUDAUTORIZACION']['cargo']=$Cargo;
										$_SESSION['SOLICITUDAUTORIZACION']['tarifario_id']=$TarifarioId;
										$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['modulo']='EstacionEnfermeriaCargos';
										$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['tipo']='user';
										$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['contenedor']='app';
										$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['metodo']='RetornoAutorizacion';

										$mensaje='El Cargo: '.$Descripcion[0].' Necesita Autorización para ser Cargado, debe solicitar la Autorización.';
										$arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$_SESSION['CUENTAS']['E']['tipo_id_paciente'],'PacienteId'=>$_SESSION['CUENTAS']['E']['paciente_id'],'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'estacion'=>$estacion);
										$_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['argumentos']=$arreglo;
										$c='app';
										$m='EstacionEnfermeriaCargos';
										$me='AutorizarCargo';
										$me2='Cargos';
										$Titulo='SOLICITAR AUTORIZACION CARGO';
										$boton1='SOLICITAR';
										$boton2='CANCELAR';
										$this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
										return true;
							}
				}

				$pasa=$_SESSION['FACTURACION']['PASA'];
				//si el cargo ya existe en detalle cuenta y es de apoyos diagnosticos
				if( $SW!=0 && $Apoyo)
				{
							if($pasa)
							{ $pasa=0;}
							else
							{$pasa=1;}
							$query = "INSERT INTO tmp_ayudas_diagnosticas(
																					transaccion,
																					numerodecuenta,
																					tarifario_id,
																					cargo,
																					precio,
																					cantidad,
																					valor_cargo,
																					gravamen_valor_cubierto,
																					gravamen_valor_nocubierto,
																					valor_cuota_paciente,
																					valor_nocubierto,
																					valor_cubierto,
																					usuario_id,
																					fecha_registro,
																					porcentaje_descuento_paciente,
																					porcentaje_descuento_empresa,
																					valor_descuento_empresa,
																					valor_descuento_paciente,
																					valor_cuota_moderadora,
																					servicio_cargo,
																					autorizacion_int,
																					autorizacion_ext,
																					sw_pasa,
																					departamento,
																					facturado)
												VALUES ($SW,$Cuenta,'$TarifarioId','$Cargo',$Precio,$Cantidad,$ValorCargo,$GravamenEmp,$GravamenPac,$ValorPac,$ValorNo,$ValorCub,$SystemId,'$FechaCargo',$PorPac,$PorEmp,$DescuentoEmp,$DescuentoPac,$Moderadora,$Servicio,$AutoInt,$AutoExt,'$pasa','$Departamento',1)";
							$dbconn->Execute($query);

							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							else{
											if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
												return false;
											}
											return true;
							}
				}
				else
				{
							$query="SELECT nextval('tmp_cuentas_detalle_transaccion_seq')";
							$result=$dbconn->Execute($query);
							$Transaccion=$result->fields[0];

							if($SW==0 && $Apoyo)
							{
								 $query = "INSERT INTO tmp_cuentas_detalle (
																							transaccion,
																							empresa_id,
																							centro_utilidad,
																							numerodecuenta,
																							departamento,
																							tarifario_id,
																							cargo,
																							cantidad,
																							precio,
																							gravamen_valor_cubierto,
																							gravamen_valor_nocubierto,
																							valor_cargo,
																							valor_cuota_paciente,
																							valor_nocubierto,
																							valor_cubierto,
																							usuario_id,
																							facturado,
																							fecha_cargo,
																							porcentaje_descuento_paciente,
																							porcentaje_descuento_empresa,
																							valor_descuento_empresa,
																							valor_descuento_paciente,
																							valor_cuota_moderadora,
																							servicio_cargo,
																							autorizacion_int,
																							autorizacion_ext)
														VALUES ($Transaccion,'$EmpresaId','$CUtilidad',$Cuenta,'$Departamento','SYS','$CargoD',0,0,0,0,0,0,0,0,$SystemId,1,'$FechaCargo',$PorPac,$PorEmp,$DescuentoEmp,$DescuentoPac,$Moderadora,$Servicio,$AutoInt,$AutoExt)";
									$dbconn->BeginTrans();
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al 2Guardar en la Base de Datos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}
									else
									{
											$query = "INSERT INTO tmp_ayudas_diagnosticas(
																									transaccion,
																									numerodecuenta,
																									tarifario_id,
																									cargo,
																									precio,
																									cantidad,
																									valor_cargo,
																									gravamen_valor_cubierto,
																									gravamen_valor_nocubierto,
																									valor_cuota_paciente,
																									valor_nocubierto,
																									valor_cubierto,
																									usuario_id,
																									fecha_registro,
																									porcentaje_descuento_paciente,
																									porcentaje_descuento_empresa,
																									valor_descuento_empresa,
																									valor_descuento_paciente,
																									valor_cuota_moderadora,
																									servicio_cargo,
																									autorizacion_int,
																									autorizacion_ext,
																									sw_pasa,
																									departamento,
																									facturado)
																VALUES ($Transaccion,$Cuenta,'$TarifarioId','$Cargo',$Precio,$Cantidad,$ValorCargo,$GravamenEmp,$GravamenPac,$ValorPac,$ValorNo,$ValorCub,$SystemId,'$FechaCargo',$PorPac,$PorEmp,$DescuentoEmp,$DescuentoPac,$Moderadora,$Servicio,$AutoInt,$AutoExt,'0','$Departamento',1)";
											$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error al 3Guardar en la Base de Datos";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$dbconn->RollbackTrans();
													return false;
											}
											else
											{
														$dbconn->CommitTrans();
														if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
															return false;
														}
														return true;
											}
									}
							}
							else
							{
									$query = "INSERT INTO tmp_cuentas_detalle (
																							transaccion,
																							empresa_id,
																							centro_utilidad,
																							numerodecuenta,
																							departamento,
																							tarifario_id,
																							cargo,
																							cantidad,
																							precio,
																							gravamen_valor_cubierto,
																							gravamen_valor_nocubierto,
																							valor_cargo,
																							valor_cuota_paciente,
																							valor_nocubierto,
																							valor_cubierto,
																							usuario_id,
																							facturado,
																							fecha_cargo,
																							porcentaje_descuento_paciente,
																							porcentaje_descuento_empresa,
																							valor_descuento_empresa,
																							valor_descuento_paciente,
																							valor_cuota_moderadora,
																							servicio_cargo,
																							autorizacion_int,
																							autorizacion_ext)
														VALUES ($Transaccion,'$EmpresaId','$CUtilidad',$Cuenta,'$Departamento','$TarifarioId','$CargoD',$Cantidad,$Precio,$GravamenEmp,$GravamenPac,$ValorCargo,$ValorPac,$ValorNo,$ValorCub,$SystemId,1,'$FechaCargo',$PorPac,$PorEmp,$DescuentoEmp,$DescuentoPac,$Moderadora,$Servicio,$AutoInt,$AutoExt)";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al 4Guardar en la Base de Datos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
									}
									else
									{
												$dbconn->CommitTrans();
												if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
													return false;
												}
												return true;
									}
							}
				}
	 }

	 function AutorizarCargo()
	 {
          $PlanId=$_REQUEST['PlanId'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Nivel=$_REQUEST['Nivel'];
          $Fecha=$_REQUEST['Fecha'];
          $Transaccion=$_REQUEST['Transaccion'];
          $Cuenta=$_REQUEST['Cuenta'];
          $estacion=$_REQUEST['estacion'];
          $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'estacion'=>$estacion);

          if(empty($_SESSION['SOLICITUDAUTORIZACION']))
          {
                         $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']='FACTURACION';
                         $_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']=$_SESSION['FACTURACION']['SERVICIO'];
                         $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=$arreglo;
                         $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
                         $_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='EstacionEnfermeriaCargos';
                         $_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
                         $_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='RetornoAutorizacion';

                         $this->ReturnMetodoExterno('app','Autorizacion','user','AutorizarCargo');
                         return true;
          }
          else
          {
                         $_SESSION['SOLICITUDAUTORIZACION']['FACTURACION']=true;
                         $this->ReturnMetodoExterno('app','Autorizacion_Solicitud','user','LlamarFormaSolicitudAutorizacion');
                         return true;
          }
	 }


	 function RetornoAutorizacion()
	 {
				$PlanId=$_REQUEST['PlanId'];
				$TipoId=$_REQUEST['TipoId'];
				$PacienteId=$_REQUEST['PacienteId'];
				$Ingreso=$_REQUEST['Ingreso'];
				$Nivel=$_REQUEST['Nivel'];
				$Fecha=$_REQUEST['Fecha'];
				$Transaccion=$_REQUEST['Transaccion'];
				$Cuenta=$_REQUEST['Cuenta'];
				$estacion=$_REQUEST['estacion'];
				$arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'estacion'=>$estacion);

				$CargoD=$_SESSION['FACTURACION']['CargoD'];
				$Apoyo=$_SESSION['FACTURACION']['Apoyo'];
				$SW=$_SESSION['FACTURACION']['SW'];

				if(!empty($_SESSION['SOLICITUDAUTORIZACION']['FACTURACION']))
				{
							if(!empty($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']))
							{   $mensaje='La Solicitud se Realizo.';  }
							if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$dpto,$estacion,'')){
								return false;
							}
							return true;
				}
				else
				{
						if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']) AND !empty($_SESSION['SOLICITUDAUTORIZACION']['AUTORIZACION']))
						{
								unset($_SESSION['AUTORIZACIONES']);
								$mensaje='El Cargo No fue Autorizado. No puede ser Cargado.';
								if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$dpto,$estacion)){
									return false;
								}
								return true;
						}

						switch($_SESSION['AUTORIZACIONES']['RETORNO']['ACCIONAUTO'])
						{
								case 'NOREQUIERE':
										unset($_SESSION['AUTORIZACIONES']);
										$mensaje='El Cargo No Requiere Autorización';
										if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
											return false;
										}
										return true;
								break;

								case 'NOCUBRE':
										unset($_SESSION['AUTORIZACIONES']);
										$mensaje='El Cargo No lo Cubre el Plan';
										if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
											return false;
										}
										return true;
								break;

								case 'REQUIERE':
									if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion']))
									{
											$AutoInt=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
											unset($_SESSION['AUTORIZACIONES']);
											$this->InsertarTmpAutorizacion($AutoInt);
											return true;
									}
									else
									{
											unset($_SESSION['AUTORIZACIONES']);
											$mensaje='El Cargo Requiere Autorización, pero el proceso fallo o fue cancelado.';
											if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
												return false;
											}
											return true;
									}
								break;
						}//fin swich
				}
	 }

	function TipoSolicitud($Cargo,$TarifarioId,$Grupo,$Subgrupo)
	{
          list($dbconn) = GetDBconn();
          $sql =	"SELECT a.tarifario_id, a.grupo_tarifario_id, a.subgrupo_tarifario_id,
                                        a.grupo_tipo_cargo, b.descripcion,
                                        b.cargo_agrupamiento_sistema, b.grupo_tipo_cargo
                                        FROM tarifarios_detalle as a, grupos_tipos_cargo as b
                                        WHERE a.cargo='$Cargo'
                                        AND a.grupo_tipo_cargo=b.grupo_tipo_cargo";
          $result=$dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $S= $result->GetRowAssoc($ToUpper = false);
          $result->Close();
          return $S;
	}


	function BuscarUsuarios($PlanId)
	{
          list($dbconn) = GetDBconn();
          $query = " SELECT b.nombre, b.usuario_id
                                        FROM planes_auditores_int as a, system_usuarios as b
                                        WHERE a.plan_id='$PlanId' and a.usuario_id=".UserGetUID()."
                                        and a.usuario_id=b.usuario_id";
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


	 function InsertarTmpAutorizacion($AutoInt)
	 {
				$PlanId=$_REQUEST['PlanId'];
				$TipoId=$_REQUEST['TipoId'];
				$PacienteId=$_REQUEST['PacienteId'];
				$Ingreso=$_REQUEST['Ingreso'];
				$Nivel=$_REQUEST['Nivel'];
				$Fecha=$_REQUEST['Fecha'];
				$f=explode('/',$Fecha);
				$Fecha=$f[2].'-'.$f[1].'-'.$f[0];
				$Transaccion=$_REQUEST['Transaccion'];
				$Cuenta=$_REQUEST['Cuenta'];
				$Estado=$_REQUEST['Estado'];
				$CargoD=$_SESSION['FACTURACION']['CargoD'];
				$Apoyo=$_SESSION['FACTURACION']['Apoyo'];
				$SW=$_SESSION['FACTURACION']['SW'];
				$Liq=$_SESSION['FACTURACION']['LIQ'];
				$Departamento=$_SESSION['FACTURACION']['DEPTO'];
				$Cargo=$_SESSION['FACTURACION']['CARGO'];
				$TarifarioId=$_SESSION['FACTURACION']['TARIFARIO'];
				$Cantidad=$_SESSION['FACTURACION']['CANTIDAD'];
				$Servicio=$_SESSION['FACTURACION']['SERVICIO'];
				$pasa=$_SESSION['FACTURACION']['PASA'];
				$FechaCargo=$_SESSION['FACTURACION']['FECHACARGO'];
				$f=explode('/',$FechaCargo);
				$FechaCargo=$f[2].'-'.$f[1].'-'.$f[0];
				unset($_SESSION['FACTURACION']);
				$DescuentoEmp=$Liq[valor_descuento_empresa];
				$DescuentoPac=$Liq[valor_descuento_paciente];
				$Moderadora=$Liq[cuota_moderadora];
				$Precio=$Liq[precio_plan];
				$ValorCargo=$Liq[valor_cargo];
				$GravamenEmp=$Liq[gravamen_empresa];
				$GravamenPac=$Liq[gravamen_paciente];
				$ValorPac=$Liq[copago];
				$ValorNo=$Liq[valor_no_cubierto];
				$ValorCub=$Liq[valor_cubierto];
				$ValEmpresa=$Liq[valor_empresa];
				$PorEmp=$Liq[porcentaje_descuento_empresa];
				$PorPac=$Liq[porcentaje_descuento_paciente];
				$AutoExt=0;

				$CUtilidad=$_SESSION['CUENTAS']['CENTROUTILIDAD'];
				$EmpresaId=$_SESSION['CUENTAS']['EMPRESA'];
				$SystemId=UserGetUID();
				list($dbconn) = GetDBconn();
				//si el cargo ya existe en detalle cuenta y es de apoyos diagnosticos
				if( $SW!=0 && $Apoyo)
				{
							if($pasa)
							{ $pasa=0;}
							else
							{$pasa=1;}
							$query = "INSERT INTO tmp_ayudas_diagnosticas(
																					transaccion,
																					numerodecuenta,
																					tarifario_id,
																					cargo,
																					precio,
																					cantidad,
																					valor_cargo,
																					gravamen_valor_cubierto,
																					gravamen_valor_nocubierto,
																					valor_cuota_paciente,
																					valor_nocubierto,
																					valor_cubierto,
																					usuario_id,
																					fecha_registro,
																					porcentaje_descuento_paciente,
																					porcentaje_descuento_empresa,
																					valor_descuento_empresa,
																					valor_descuento_paciente,
																					valor_cuota_moderadora,
																					servicio_cargo,
																					autorizacion_int,
																					autorizacion_ext,
																					sw_pasa,
																					departamento,
																					facturado)
												VALUES ($SW,$Cuenta,'$TarifarioId','$Cargo',$Precio,$Cantidad,$ValorCargo,$GravamenEmp,$GravamenPac,$ValorPac,$ValorNo,$ValorCub,$SystemId,'$FechaCargo',$PorPac,$PorEmp,$DescuentoEmp,$DescuentoPac,$Moderadora,$Servicio,$AutoInt,$AutoExt,'$pasa','$Departamento',1)";
							$dbconn->Execute($query);

							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							else{
											if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
												return false;
											}
											return true;
							}
				}
				else
				{
							$query="SELECT nextval('tmp_cuentas_detalle_transaccion_seq')";
							$result=$dbconn->Execute($query);
							$Transaccion=$result->fields[0];

							if($SW==0 && $Apoyo)
							{
								 $query = "INSERT INTO tmp_cuentas_detalle (
																							transaccion,
																							empresa_id,
																							centro_utilidad,
																							numerodecuenta,
																							departamento,
																							tarifario_id,
																							cargo,
																							cantidad,
																							precio,
																							gravamen_valor_cubierto,
																							gravamen_valor_nocubierto,
																							valor_cargo,
																							valor_cuota_paciente,
																							valor_nocubierto,
																							valor_cubierto,
																							usuario_id,
																							facturado,
																							fecha_cargo,
																							porcentaje_descuento_paciente,
																							porcentaje_descuento_empresa,
																							valor_descuento_empresa,
																							valor_descuento_paciente,
																							valor_cuota_moderadora,
																							servicio_cargo,
																							autorizacion_int,
																							autorizacion_ext)
														VALUES ($Transaccion,'$EmpresaId','$CUtilidad',$Cuenta,'$Departamento','SYS','$CargoD',0,0,0,0,0,0,0,0,$SystemId,1,'$FechaCargo',$PorPac,$PorEmp,$DescuentoEmp,$DescuentoPac,$Moderadora,$Servicio,$AutoInt,$AutoExt)";
									$dbconn->BeginTrans();
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al 2Guardar en la Base de Datos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
									}
									else
									{
											$query = "INSERT INTO tmp_ayudas_diagnosticas(
																									transaccion,
																									numerodecuenta,
																									tarifario_id,
																									cargo,
																									precio,
																									cantidad,
																									valor_cargo,
																									gravamen_valor_cubierto,
																									gravamen_valor_nocubierto,
																									valor_cuota_paciente,
																									valor_nocubierto,
																									valor_cubierto,
																									usuario_id,
																									fecha_registro,
																									porcentaje_descuento_paciente,
																									porcentaje_descuento_empresa,
																									valor_descuento_empresa,
																									valor_descuento_paciente,
																									valor_cuota_moderadora,
																									servicio_cargo,
																									autorizacion_int,
																									autorizacion_ext,
																									sw_pasa,
																									departamento,
																									facturado)
																VALUES ($Transaccion,$Cuenta,'$TarifarioId','$Cargo',$Precio,$Cantidad,$ValorCargo,$GravamenEmp,$GravamenPac,$ValorPac,$ValorNo,$ValorCub,$SystemId,'$FechaCargo',$PorPac,$PorEmp,$DescuentoEmp,$DescuentoPac,$Moderadora,$Servicio,$AutoInt,$AutoExt,'0','$Departamento',1)";
											$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error al 3Guardar en la Base de Datos";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$dbconn->RollbackTrans();
													return false;
											}
											else
											{
														$dbconn->CommitTrans();
														if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
															return false;
														}
														return true;
											}
									}
							}
							else
							{
									$query = "INSERT INTO tmp_cuentas_detalle (
																							transaccion,
																							empresa_id,
																							centro_utilidad,
																							numerodecuenta,
																							departamento,
																							tarifario_id,
																							cargo,
																							cantidad,
																							precio,
																							gravamen_valor_cubierto,
																							gravamen_valor_nocubierto,
																							valor_cargo,
																							valor_cuota_paciente,
																							valor_nocubierto,
																							valor_cubierto,
																							usuario_id,
																							facturado,
																							fecha_cargo,
																							porcentaje_descuento_paciente,
																							porcentaje_descuento_empresa,
																							valor_descuento_empresa,
																							valor_descuento_paciente,
																							valor_cuota_moderadora,
																							servicio_cargo,
																							autorizacion_int,
																							autorizacion_ext)
														VALUES ($Transaccion,'$EmpresaId','$CUtilidad',$Cuenta,'$Departamento','$TarifarioId','$CargoD',$Cantidad,$Precio,$GravamenEmp,$GravamenPac,$ValorCargo,$ValorPac,$ValorNo,$ValorCub,$SystemId,1,'$FechaCargo',$PorPac,$PorEmp,$DescuentoEmp,$DescuentoPac,$Moderadora,$Servicio,$AutoInt,$AutoExt)";
									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al 4Guardar en la Base de Datos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
									}
									else
									{
												$dbconn->CommitTrans();
												if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$estacion,$D,$var,$Ayudas,$Cobertura)){
													return false;
												}
												return true;
									}
							}
				}
	 }


	 function EliminarCargoTmp()
	 {
				$Transaccion=$_REQUEST['Transaccion'];
				$Cuenta=$_REQUEST['Cuenta'];
				$Nivel=$_REQUEST['Nivel'];
				$PlanId=$_REQUEST['PlanId'];
				$Ingreso=$_REQUEST['Ingreso'];
				$Fecha=$_REQUEST['Fecha'];
				$TipoId=$_REQUEST['TipoId'];
				$PacienteId=$_REQUEST['PacienteId'];
				$Consecutivo=$_REQUEST['Consecutivo'];

				if(!$Consecutivo)
				{
						list($dbconn) = GetDBconn();
						$query =" DELETE FROM tmp_cuentas_detalle WHERE transaccion=$Transaccion AND numerodecuenta=$Cuenta";
						$dbconn->Execute($query);

						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Borrar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						$mensaje='El cargo se elimino.';
						if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,$ValEmpresa,$Cobertura)){
							return false;
						}
						return true;
				}
				else
				{
						list($dbconn) = GetDBconn();
						$query =" DELETE FROM tmp_ayudas_diagnosticas WHERE transaccion=$Transaccion AND numerodecuenta=$Cuenta AND consecutivo=$Consecutivo";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
									$this->error = "ERROR: DELETE FROM tmp_ayudas_diagnosticas";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
						}
						$mensaje='El cargo se elimino.';
						if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,$ValEmpresa,$Cobertura)){
							return false;
						}
						return true;
				}
	 }


	 function GuardarTodosCargos()
	 {
				$Cuenta=$_REQUEST['Cuenta'];
				$TipoId=$_REQUEST['TipoId'];
				$PacienteId=$_REQUEST['PacienteId'];
				$Nivel=$_REQUEST['Nivel'];
				$PlanId=$_REQUEST['PlanId'];
				$Pieza=$_REQUEST['Pieza'];
				$Cama=$_REQUEST['Cama'];
				$Fecha=$_REQUEST['Fecha'];
				$Ingreso=$_REQUEST['Ingreso'];
				$Datos=$this->DatosTmpCuentas($Cuenta);
				$EmpresaId=$_SESSION['CUENTAS']['EMPRESA'];
				$CUtilidad=$_SESSION['CUENTAS']['CENTROUTILIDAD'];
				$FechaRegistro=date("Y-m-d H:i:s");
				$SystemId=UserGetUID();

				list($dbconn) = GetDBconn();
				for($i=0; $i<sizeof($Datos); $i++)
				{
							$Transacciontmp=$Datos[$i][transaccion];
							$Departamento=$Datos[$i][departamento];
							$TarifarioId=$Datos[$i][tarifario_id];
							$Cargo=$Datos[$i][cargo];
							$Cantidad=$Datos[$i][cantidad];
							$Precio=$Datos[$i][precio];
							$GravamenEmp=$Datos[$i][gravamen_valor_cubierto];
							$GravamenPac=$Datos[$i][gravamen_valor_nocubierto];
							$ValorPac=$Datos[$i][valor_cuota_paciente];
							$ValorNo=$Datos[$i][valor_nocubierto];
							$ValorCubierto=$Datos[$i][valor_cubierto];
							$Fecha=$Datos[$i][fecha_cargo];
							$ValorCargo=$Datos[$i][valor_cargo];
							$Facturado=$Datos[$i][facturado];
							$DescuentoEmp=$Datos[$i][valor_descuento_empresa];
							$DescuentoPac=$Datos[$i][valor_descuento_paciente];
							$Moderadora=$Datos[$i][valor_cuota_moderadora];
							$PorEmp=$Datos[$i][porcentaje_descuento_empresa];
							$PorPac=$Datos[$i][porcentaje_descuento_paciente];
							if(empty($Datos[$i][autorizacion_int]))
							{   $AutoInt='NULL';   }
							else
							{   $AutoInt=$Datos[$i][autorizacion_int];   }
							if(empty($Datos[$i][autorizacion_ext]))
							{   $AutoExt='NULL';   }
							else
							{   $AutoExt=$Datos[$i][autorizacion_ext];   }
							$servicio=$Datos[$i][servicio_cargo];
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
														gravamen_valor_cubierto,
														gravamen_valor_nocubierto,
														valor_cargo,
														valor_cuota_paciente,
														valor_nocubierto,
														valor_cubierto,
														usuario_id,
														facturado,
														fecha_cargo,
														fecha_registro,
														porcentaje_descuento_paciente,
														porcentaje_descuento_empresa,
														valor_descuento_empresa,
														valor_descuento_paciente,
														valor_cuota_moderadora,
														autorizacion_int,
														autorizacion_ext,
														servicio_cargo)
												VALUES ($Transaccion,'$EmpresaId','$CUtilidad',$Cuenta,'$Departamento','$TarifarioId','$Cargo',$Cantidad,$Precio,$GravamenEmp,$GravamenPac,$ValorCargo,$ValorPac,$ValorNo,$ValorCubierto,$SystemId,$Facturado,'$Fecha','$FechaRegistro',$PorPac,$PorEmp,$DescuentoEmp,$DescuentoPac,$Moderadora,$AutoInt,$AutoExt,$servicio)";
							$dbconn->BeginTrans();
							$dbconn->Execute($query);

							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al cuentas_detalle";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}
							else
							{
										$Ayudas=$this->DatosAyudas($Cuenta,$Transacciontmp);
										for($j=0; $j<sizeof($Ayudas); $j++)
										{
												if(!$Ayudas[$j][sw_pasa])
												{
														$Cargo=$Ayudas[$j][cargo];
														$TarifarioId=$Ayudas[$j][tarifario_id];
														$Departamento=$Ayudas[$j][departamento];
														$Precio=$Ayudas[$j][precio];
														$Cantidad=$Ayudas[$j][cantidad];
														$ValorCargo=$Ayudas[$j][valor_cargo];
														$GravamenEmp=$Ayudas[$j][gravamen_valor_cubierto];
														$GravamenPac=$Ayudas[$j][gravamen_valor_nocubierto];
														$ValorPac=$Ayudas[$j][valor_cuota_paciente];
														$ValorNo=$Ayudas[$j][valor_nocubierto];
														$ValEmpresa=$Ayudas[$j][valor_cubierto];
														$Fecha=$Ayudas[$j][fecha_registro];
														$Facturado=$Ayudas[$j][facturado];
														if($Ayudas[$j][sw_pasa])
														{  $Transaccion=$Ayudas[$j][transaccion];  }
														$DescuentoEmp=$Ayudas[$j][valor_descuento_empresa];
														$DescuentoPac=$Ayudas[$j][valor_descuento_paciente];
														$Moderadora=$Ayudas[$j] [valor_cuota_moderadora];
														$PorEmp=$Ayudas[$j][porcentaje_descuento_empresa];
														$PorPac=$Ayudas[$j][porcentaje_descuento_paciente];
														$servicio=$Ayudas[$j][servicio_cargo];
														if(empty($Ayudas[$j][autorizacion_int]))
														{   $AutoInt='NULL';   }
														else
														{   $AutoInt=$Ayudas[$j][autorizacion_int];   }
														if(empty($Ayudas[$i][autorizacion_ext]))
														{   $AutoExt='NULL';   }
														else
														{   $AutoExt=$Ayudas[$j][autorizacion_ext];   }
														$query = "INSERT INTO ayudas_diagnosticas(
																												transaccion,
																												numerodecuenta,
																												cargo,
																												tarifario_id,
																												precio,
																												cantidad,
																												valor_cargo,
																												gravamen_valor_cubierto,
																												gravamen_valor_nocubierto,
																												valor_cuota_paciente,
																												valor_nocubierto,
																												valor_cubierto,
																												usuario_id,
																												fecha_registro,
																												valor_descuento_empresa,
																												valor_descuento_paciente,
																												porcentaje_descuento_paciente,
																												porcentaje_descuento_empresa,
																												valor_cuota_moderadora,
																												autorizacion_int,
																												autorizacion_ext,
																												servicio_cargo,
																												departamento,
																												facturado,
																												fecha_cargo)
																			VALUES ($Transaccion,$Cuenta,'$Cargo','$TarifarioId',$Precio,$Cantidad,$ValorCargo,$GravamenEmp,$GravamenPac,$ValorPac,$ValorNo,$ValEmpresa,$SystemId,'$FechaRegistro',$DescuentoEmp,$DescuentoPac,$PorPac,$PorEmp,$Moderadora,$AutoInt,$AutoExt,$servicio,'$Departamento',$Facturado,'$Fecha')";
													$dbconn->Execute($query);

													if ($dbconn->ErrorNo() != 0) {
															$this->error = "Error ayudas_diagnosticas";
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															$dbconn->RollbackTrans();
															return false;
													}
											}
									}
							}
				}

				$Ayudas=$this->DatosAyudasPasa($Cuenta);
				for($j=0; $j<sizeof($Ayudas); $j++)
				{
							$Cargo=$Ayudas[$j][cargo];
							$TarifarioId=$Ayudas[$j][tarifario_id];
							$Precio=$Ayudas[$j][precio];
							$Cantidad=$Ayudas[$j][cantidad];
							$ValorCargo=$Ayudas[$j][valor_cargo];
							$Departamento=$Ayudas[$j][departamento];
							$GravamenEmp=$Ayudas[$j][gravamen_valor_cubierto];
							$GravamenPac=$Ayudas[$j][gravamen_valor_nocubierto];
							$ValorPac=$Ayudas[$j][valor_cuota_paciente];
							$ValorNo=$Ayudas[$j][valor_nocubierto];
							$ValEmpresa=$Ayudas[$j][valor_cubierto];
							$Fecha=$Ayudas[$j][fecha_registro];
							$Facturado=$Ayudas[$j][facturado];
							if($Ayudas[$j][sw_pasa])
							{  $Transaccion=$Ayudas[$j][transaccion];  }
							$DescuentoEmp=$Ayudas[$j][valor_descuento_empresa];
							$DescuentoPac=$Ayudas[$j][valor_descuento_paciente];
							$Moderadora=$Ayudas[$j] [valor_cuota_moderadora];
							$PorEmp=$Ayudas[$j][porcentaje_descuento_empresa];
							$PorPac=$Ayudas[$j][porcentaje_descuento_paciente];
							$servicio=$Ayudas[$j][servicio_cargo];
							$AutoInt=$Ayudas[$j][autorizacion_int];
							$AutoExt=$Ayudas[$j][autorizacion_ext];
							$query = "INSERT INTO ayudas_diagnosticas(
																					transaccion,
																					numerodecuenta,
																					cargo,
																					tarifario_id,
																					precio,
																					cantidad,
																					valor_cargo,
																					gravamen_valor_cubierto,
																					gravamen_valor_nocubierto,
																					valor_cuota_paciente,
																					valor_nocubierto,
																					valor_cubierto,
																					usuario_id,
																					fecha_registro,
																					valor_descuento_empresa,
																					valor_descuento_paciente,
																					porcentaje_descuento_paciente,
																					porcentaje_descuento_empresa,
																					valor_cuota_moderadora,
																					autorizacion_int,
																					autorizacion_ext,
																					servicio_cargo,
																					departamento,
																					facturado,
																					fecha_cargo)
												VALUES ($Transaccion,$Cuenta,'$Cargo','$TarifarioId',$Precio,$Cantidad,$ValorCargo,$GravamenEmp,$GravamenPac,$ValorPac,$ValorNo,$ValEmpresa,$SystemId,'$FechaRegistro',$DescuentoEmp,$DescuentoPac,$PorPac,$PorEmp,$Moderadora,$AutoInt,$AutoExt,$servicio,'$Departamento',$Facturado,'$Fecha')";
						$dbconn->Execute($query);

						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error ayudas_diagnosticas2";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
				}

				$query =" DELETE FROM tmp_cuentas_detalle WHERE numerodecuenta=$Cuenta";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}
				else{
							$query =" DELETE FROM tmp_ayudas_diagnosticas WHERE numerodecuenta=$Cuenta";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en la Base de Datos";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
							}
							else{
										//$dbconn->CommitTrans();
										$Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
										$Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
										$mensaje='Todos los cargos se guardaron en la cuenta No. '.$Cuenta.' '.$Nombres.' '.$Apellidos;
										$accion=ModuloGetURL('app','EstacionEnfermeriaCargos','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
										if(!$this->FormaMensaje($mensaje,'AGREGAR TODOS LOS CARGOS',$accion,$boton)){
												return false;
										}
										return true;
							}
				}
	 }


	function DatosAyudas($Cuenta,$Transacciontmp)
	{
          $Usuario=UserGetUID();
          list($dbconn) = GetDBconn();
          $query="SELECT *
                         FROM tmp_ayudas_diagnosticas WHERE numerodecuenta=$Cuenta
                                   AND transaccion=$Transacciontmp AND usuario_id=$Usuario AND sw_pasa=0";
          $result=$dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while(!$result->EOF)
          {
               $var[]= $result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }
		$result->Close();
		return $var;
	}


     function BuscarNombresPaciente($tipo,$documento)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT primer_nombre,segundo_nombre FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
          $result = $dbconn->Execute($query);

               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               else{
                    if($result->EOF){
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
                         return false;
                    }
               }
          $Nombres=$result->fields[0]." ".$result->fields[1];
          $result->Close();
		return $Nombres;
	}


	function BuscarApellidosPaciente($tipo,$documento)
	{
          list($dbconn) = GetDBconn();
          $query = "SELECT primer_apellido,segundo_apellido FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
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
          $Apellidos=$result->fields[0]." ".$result->fields[1];
		return $Apellidos;
	}

	function CuentaParticular($Cuenta,$PlanId)
	{
          list($dbconn) = GetDBconn();
          $query = "SELECT a.tipo_id_tercero,a.tercero_id, b.nombre_tercero, c.plan_descripcion, c.protocolos
                                   FROM cuentas_responsable_particular as a, terceros as b, planes as c
                                   WHERE a.numerodecuenta='$Cuenta' AND a.tipo_id_tercero=b.tipo_id_tercero
                                   AND a.tercero_id=b.tercero_id AND c.plan_id='$PlanId' ";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          if(!$result->EOF)
          {
               $var=$result->GetRowAssoc($ToUpper = false);
          }
          $result->Close();
          return $var;
	}


     function BuscarPlanes($PlanId,$Ingreso)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT sw_tipo_plan FROM planes WHERE plan_id='$PlanId'";
          $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $sw=$results->fields[0];
          //soat
          if($sw==1)
          {
                    $query = "SELECT  b.nombre_tercero, c.plan_descripcion, e.tipo_id_tercero, e.tercero_id, c.protocolos
                                                  FROM ingresos_soat as a, terceros as b, planes as c,
                                                  soat_eventos as d, soat_polizas as e
                                                  WHERE a.ingreso=$Ingreso AND a.evento=d.evento AND e.tipo_id_tercero=b.tipo_id_tercero
                                                  AND e.tercero_id =b.tercero_id AND c.plan_id='$PlanId' AND d.poliza=e.poliza";
          }
          //cliente o capitacion
          if($sw==0 OR $sw==3)
          {
                    $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
                                                  FROM planes as a, terceros as b
                                                  WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
          }
          //particular
          if($sw==2)
          {
                    $query = "select b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre_tercero,
                                             c.plan_descripcion, b.tipo_id_paciente as tipo_id_tercero, b.paciente_id as tercero_id, c.protocolos
                                             from ingresos as a, pacientes as b, planes as c
                                             where a.ingreso='$Ingreso' and a.paciente_id=b.paciente_id and a.tipo_id_paciente=b.tipo_id_paciente
                                             and c.plan_id='$PlanId'";
          }
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

//-------------------modificar------------
	 function LlamaFormaModificarCargoTmp()
	 {
          $Datos=$_REQUEST['Datos'];
          $Cuenta=$_REQUEST['Cuenta'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];
          $Apoyo=$_REQUEST['Apoyo'];
          $Consecutivo=$_REQUEST['Consecutivo'];
          if($Apoyo)
          {
                    $Datos[1]='Si';
                    $Ayuda=true;
          }

          if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$estacion,$Datos,$var,$Ayuda,$C)){
               return false;
          }
          return true;
     }

	 /**
	 * Modifica un cargo de la cuenta en tmp_cuenta_detalles.
   * @ access public
	 * @ return boolean
	 */
	 function ModificarCargoTmp()
	 {
	 			IncludeLib("tarifario");
				$Departamento=$_SESSION['CUENTAS']['E']['DEPTO'];
				$ValorNo=$_REQUEST['ValorNo'];
				$ValorPac=$_REQUEST['ValorPac'];
				$Precio=$_REQUEST['Precio'];
				$Cargo=$_REQUEST['Cargo'];
				$Cantidad=$_REQUEST['Cantidad'];
				$ValEmpresa=$_REQUEST['ValorEmp'];
				$TarifarioId=$_REQUEST['TarifarioId'];
				$GrupoTarifario=$_REQUEST['GrupoTarifario'];
				$SubGrupoTarifario=$_REQUEST['SubGrupoTarifario'];
				$Gravamen=$_REQUEST['Gravamen'];
				$Cuenta=$_REQUEST['Cuenta'];
				$Nivel=$_REQUEST['Nivel'];
				$PlanId=$_REQUEST['PlanId'];
				$Ingreso=$_REQUEST['Ingreso'];
				$Fecha=$_REQUEST['Fecha'];
				$TipoId=$_REQUEST['TipoId'];
				$PacienteId=$_REQUEST['PacienteId'];
				$Transaccion=$_REQUEST['Transaccion'];
				$Consecutivo=$_REQUEST['Consecutivo'];
				$ValorCargo=$_REQUEST['ValorCargo'];
				$FechaCargo=$_REQUEST['FechaCargo'];
                    $f=explode('/',$FechaCargo);
                    $FechaCargo=$f[2].'-'.$f[1].'-'.$f[0];
				$Cons=$_REQUEST['Cons'];
				$CUtilidad=$_SESSION['CUENTAS']['CENTROUTILIDAD'];
				$EmpresaId=$_SESSION['CUENTAS']['EMPRESA'];
	 			$FechaRegistro=date("Y-m-d H:i:s");
				$Cobertura=$_REQUEST['Cobertura'];
				$SystemId=UserGetUID();
				$var[1]=$Departamento;
				$var[2]=$TarifarioId;
				$var[3]=$Cargo;
				$var[4]=$Cantidad;
				$var[5]=$Precio;
				$var[6]=$Gravamen;
				$var[9]=$GrupoTarifario;
				$var[10]=$SubGrupoTarifario;
				$var[11]=$FechaCargo;

				if(!$Cantidad || !$Cargo || !$FechaCargo){
						if(!$Cantidad){ $this->frmError["Cantidad"]=1; }
						if(!$Cargo){ $this->frmError["Cargo"]=1; }
						if(!$FechaCargo){ $this->frmError["FechaCargo"]=1; }
						$mensaje='Faltan datos obligatorios.';
						if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$D,$var,'Modi',$Cobertura)){
							return false;
						}
						return true;
				}
				$f = (int) $Cantidad;
				$y = $Cantidad - $f;
				if($y != 0){
						if($y != 0){ $this->frmError["Cantidad"]=1; }
						$mensaje='La Cantidad debe ser entera.';
						if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$D,$var,'Modi',$Cobertura)){
							return false;
						}
						return true;
				}

				list($dbconn) = GetDBconn();
//----------------------------esto es para los calculos-------------------------
				$Liq=LiquidarCargoCuenta($Cuenta,$TarifarioId,$Cargo,$Cantidad,0,0,true,true,0,$PlanId,'','',0,true);
				$DescuentoEmp=$Liq[valor_descuento_empresa];
				$DescuentoPac=$Liq[valor_descuento_paciente];
				$Moderadora=$Liq[cuota_moderadora];
				$Precio=$Liq[precio_plan];
				$ValorCargo=$Liq[valor_cargo];
				$GravamenEmp=$Liq[gravamen_empresa];
				$GravamenPac=$Liq[gravamen_paciente];
				$ValorPac=$Liq[copago];
				$ValorNo=$Liq[valor_no_cubierto];
				$ValorCub=$Liq[valor_cubierto];
				$ValEmpresa=$Liq[valor_empresa];
				$PorEmp=$Liq[porcentaje_descuento_empresa];
				$PorPac=$Liq[porcentaje_descuento_paciente];
//-------------------------------------------------------------------------------
				if($Cons=='Si')
				{
					$query =" UPDATE tmp_ayudas_diagnosticas SET
																				cantidad=$Cantidad,
																				gravamen_valor_cubierto=$GravamenEmp,
																				gravamen_valor_nocubierto=$GravamenPac,
																				valor_cargo=$ValorCargo,
																				valor_cuota_paciente=$ValorPac,
																				valor_nocubierto=$ValorNo,
																				valor_cubierto=$ValEmpresa,
																				fecha_registro='$FechaCargo',
																				porcentaje_descuento_paciente=$PorPac,
																				porcentaje_descuento_empresa=$PorEmp,
																				valor_descuento_empresa=$DescuentoEmp,
																				valor_descuento_paciente=$DescuentoPac,
																				valor_cuota_moderadora=$Moderadora
												WHERE transaccion=$Transaccion AND numerodecuenta=$Cuenta AND consecutivo=$Consecutivo";
						$dbconn->Execute($query);

						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error UPDATE tmp_ayudas_diagnosticas";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						$mensaje='El cargo se modifico';
						if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$D,$var='',$ValEmpresa,$Cobertura)){
							return false;
						}
						return true;
				}
				else
				{
					$query =" UPDATE tmp_cuentas_detalle SET
																			departamento='$Departamento',
																			tarifario_id='$TarifarioId',
																			cargo='$Cargo',
																			cantidad=$Cantidad,
																			precio=$Precio,
																			gravamen_valor_cubierto=$GravamenEmp,
																			gravamen_valor_nocubierto=$GravamenPac,
																			valor_cargo=$ValorCargo,
																			valor_cuota_paciente=$ValorPac,
																			valor_nocubierto=$ValorNo,
																			valor_cubierto=$ValEmpresa,
																			fecha_cargo='$FechaCargo',
																			usuario_id=$SystemId,
																			fecha_registro='now()',
																			porcentaje_descuento_paciente=$PorPac,
																			porcentaje_descuento_empresa=$PorEmp,
																			valor_descuento_empresa=$DescuentoEmp,
																			valor_descuento_paciente=$DescuentoPac,
																			valor_cuota_moderadora=$Moderadora
										WHERE transaccion=$Transaccion AND numerodecuenta=$Cuenta";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error UPDATE tmp_cuentas_detalle";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					$mensaje='El cargo se modifico';
					if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$D,$var='',$ValEmpresa,$Cobertura)){
						return false;
					}
					return true;
				}
	 }

//-----------------------INSUMOS------------------------------------
	/**
	*
	*/
	function LlamarFormaBodegas()
	{
          $Cuenta=$_REQUEST['Cuenta'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];

          $_SESSION['CUENTAS']['E']['tipo_id_paciente']=$TipoId;
          $_SESSION['CUENTAS']['E']['paciente_id']=$PacienteId;

          list($dbconn) = GetDBconn();
          $query = "select a.departamento,b.empresa_id, b.centro_utilidad
                                   from estaciones_enfermeria as a, departamentos as b
                                   where a.estacion_id='".$_REQUEST['estacion']['estacion_id']."'
                                   and a.departamento=b.departamento";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          $query = "select a.departamento,b.empresa_id, b.centro_utilidad
                                   from estaciones_enfermeria as a, departamentos as b
                                   where a.estacion_id='".$_REQUEST['estacion']['estacion_id']."'
                                   and a.departamento=b.departamento";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          $_SESSION['CUENTAS']['E']['CENTROUTILIDAD']=$result->fields[2];
          $_SESSION['CUENTAS']['E']['EMPRESA']=$result->fields[1];
          $_SESSION['CUENTAS']['E']['DEPTO']=$result->fields[0];
          $_SESSION['CUENTAS']['E']['INGRESO']=$_REQUEST['ingreso'];
          $_SESSION['CUENTAS']['E']['ESTACION']=$_REQUEST['estacion']['estacion_id'];


          if(!$this->FormaBodegas($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha)){
               return false;
          }
          return true;
	}

	/**
	*
	*/
	function Bodegas()
	{
				$EmpresaId=$_SESSION['CUENTAS']['E']['EMPRESA'];
				$CU="and a.centro_utilidad='".$_SESSION['CUENTAS']['E']['CENTROUTILIDAD']."'
						 and b.centro_utilidad='".$_SESSION['CUENTAS']['E']['CENTROUTILIDAD']."'";

				list($dbconn) = GetDBconn();
			 $query="SELECT a.* FROM bodegas as a,bodegas_estaciones as b
								WHERE a.empresa_id='$EmpresaId' $CU
								AND b.empresa_id='$EmpresaId' AND a.bodega=b.bodega
								AND b.estacion_id='".$_SESSION['CUENTAS']['E']['ESTACION']."'";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while(!$result->EOF)
				{
					$var[]= $result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
				}
				$result->Close();
				return $var;
	}

  /**
	* Llama la forma con el combo de las bodegas.
	* @access public
	* @return boolean
	*/
	 function BodegaInsumos()
	 {
			$Cuenta=$_REQUEST['Cuenta'];
			$TipoId=$_REQUEST['TipoId'];
			$PacienteId=$_REQUEST['PacienteId'];
			$Nivel=$_REQUEST['Nivel'];
			$PlanId=$_REQUEST['PlanId'];
			unset($_SESSION['CUENTA']['E']['BODEGA']);

			if($_REQUEST['Bodegas']==-1){
						if($_REQUEST['Bodegas']==-1){ $this->frmError["Bodegas"]=1; }
						$this->frmError["MensajeError"]="Debe Elegir la Bodega";
						if(!$this->FormaBodegas($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha)){
							return false;
						}
						return true;
			}

			$_SESSION['CUENTA']['E']['BODEGA']=$_REQUEST['Bodegas'];
			$this->Insumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId);
			return true;
	 }

  /**
	* Llama la forma FormaInsumos que insertar nuevos cargos.
	* @access public
	* @return boolean
	*/
	 function Insumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId)
	 {
			$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$D,$var);
			return true;
	 }


	/**
	*
	*/
	function DatosTmpInsumos($Cuenta)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.*, c.descripcion, b.descripcion as desdpto, d.descripcion as desbodega
								FROM tmp_cuenta_insumos as a, departamentos as b, inventarios_productos as c, bodegas as d
								WHERE a.numerodecuenta=$Cuenta and a.departamento=b.departamento
								and a.codigo_producto=c.codigo_producto and a.bodega=d.bodega";
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
			return $var;
	}


	/**
	* Busca el departamento y su descripcion en la tabla departamentos.
	* @access public
	* @return array
	*/
	function Departamentos()
  {
			$EmpresaId=$_SESSION['CUENTAS']['E']['EMPRESA'];
			$CentroU=$_SESSION['CUENTAS']['E']['CENTROUTILIDAD'];
			$CU="and centro_utilidad='$CentroU'";

			list($dbconn) = GetDBconn();
			$query = "SELECT a.departamento,a.descripcion
									FROM departamentos as a, servicios as b WHERE a.empresa_id='$EmpresaId'
									and a.servicio=b.servicio and b.sw_asistencial=1";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'departamentos' esta vacia ";
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
	 function NombreBodega($Bodega)
	 {
				list($dbconn) = GetDBconn();
				$query = "SELECT descripcion	FROM bodegas
						WHERE bodega='$Bodega'";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$var=$result->GetRowAssoc($ToUpper = false);
				return $var;
	 }


	/**
	*
	*/
	function EliminarTodosCargosIyM()
	{
				$Cuenta=$_REQUEST['Cuenta'];
				$Cancelar=$_REQUEST['Cancelar'];
				$Transaccion=$_REQUEST['Transaccion'];
				$Nivel=$_REQUEST['Nivel'];
				$PlanId=$_REQUEST['PlanId'];
				$Ingreso=$_REQUEST['Ingreso'];
				$Fecha=$_REQUEST['Fecha'];
				$TipoId=$_REQUEST['TipoId'];
				$PacienteId=$_REQUEST['PacienteId'];

				list($dbconn) = GetDBconn();
				$query1 =" SELECT * FROM tmp_cuenta_insumos WHERE numerodecuenta=$Cuenta";
				$result=$dbconn->Execute($query1);
				$query =" DELETE FROM tmp_cuenta_insumos WHERE numerodecuenta=$Cuenta";
				$dbconn->BeginTrans();
				$dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}
				else
				{
						$dbconn->CommitTrans();
						$x=$result->RecordCount();
						if($x)
						{
								$accion = ModuloGetURL('app','EstacionEnfermeriaCargos','user','main',array("estacion"=>$_SESSION['CUENTAS']['E']['DATOS'],'tipoa'=>2));
								$mensaje='Todos los cargos fueron borrados.';
								if(!$this->FormaMensaje($mensaje,'ELIMINAR TODOS LOS CARGOS',$accion,$boton)){
										return false;
								}
								return true;
						}
						else
						{
									$this->main($_SESSION['CUENTAS']['E']['DATOS'],2);
									return true;
								/*if(!$this->Cuenta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso)){
										return false;
								}
								return true;*/
						}
			}
	}



	/**
	*
	*/
	function InsertarInsumos()
	{
	 			IncludeLib("tarifario");
				$Departamento=$_SESSION['CUENTAS']['E']['DEPTO'];
				$Precio=$_REQUEST['Precio'];
				$Codigo=$_REQUEST['Codigo'];
				$TarifarioId=$_REQUEST['TarifarioId'];
				$Gravamen=$_REQUEST['Gravamen'];
				$Cantidad=$_REQUEST['Cantidad'];
				$Cuenta=$_REQUEST['Cuenta'];
				$Nivel=$_REQUEST['Nivel'];
				$PlanId=$_REQUEST['PlanId'];
				$Ingreso=$_REQUEST['Ingreso'];
				$Fecha=$_REQUEST['Fecha'];
				$TipoId=$_REQUEST['TipoId'];
				$PacienteId=$_REQUEST['PacienteId'];
				$empresa=$_REQUEST['EmpresaId'];
				$cu=$_REQUEST['CU'];
				$bodega=$_REQUEST['Bodegas'];
        $f=explode('/',$_REQUEST['FechaCargo']);
        $_REQUEST['FechaCargo']=$f[2].'-'.$f[1].'-'.$f[0];

				$SystemId=UserGetUID();

				if(!$Cantidad || !$Codigo || !$_REQUEST['FechaCargo']){
						if(!$Cantidad){ $this->frmError["Cantidad"]=1; }
						if(!$Codigo){ $this->frmError["Codigo"]=1; }
						if(!$FechaCargo){ $this->frmError["FechaCargo"]=1; }
						$this->frmError["MensajeError"]="Faltan datos obligatorios.";
						if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$D)){
							return false;
						}
						return true;
				}

				$f = (int) $Cantidad;
				$y = $Cantidad - $f;
				if($y != 0){
						if($y != 0){ $this->frmError["Cantidad"]=1; }
						$mensaje='La Cantidad debe ser entera.';
						if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$D)){
							return false;
						}
						return true;
				}

				list($dbconn) = GetDBconn();
				$query ="SELECT b.servicio
								FROM departamentos as b
								WHERE b.departamento='$Departamento'";
				$results = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$Servicio=$results->fields[0];

				/*if(empty($_REQUEST['Precio']))
				{
						$query = "SELECT A.precio_venta,A.codigo_producto, B.descripcion,
										B.porc_iva, C.cantidad_max,
										D.empresa_id,D.centro_utilidad,D.bodega
										FROM inventarios AS A,
										inventarios_productos AS B,
										plan_tarifario_inv AS C,
										existencias_bodegas AS D
										WHERE C.plan_id = ".$PlanId."
										AND C.empresa_id = A.empresa_id
										AND C.grupo_id = B.grupo_id
										AND C.clase_id = B.clase_id
										AND C.subclase_id = B.subclase_id
										AND D.empresa_id = '$empresa'
										AND D.centro_utilidad = '$cu'
										AND D.bodega = '$bodega'
										AND A.empresa_id = D.empresa_id
										AND A.codigo_producto = D.codigo_producto
										AND A.codigo_producto=B.codigo_producto
										AND A.codigo_producto='$Codigo'
										AND excepciones_inventarios(C.plan_id, A.codigo_producto)=0";
						$results = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						if(!$results->EOF)
						{
									$query = " INSERT INTO tmp_cuenta_insumos(
																									numerodecuenta,
																									departamento,
																									bodega,
																									codigo_producto,
																									cantidad,
																									empresa_id,
																									centro_utilidad,
																									precio,
																									fecha_cargo,
																									plan_id,
																									servicio_cargo)
															VALUES($Cuenta,'$Departamento','$bodega','$Codigo',$Cantidad,'$empresa','$cu',".$results->fields[0].",'".$_REQUEST['FechaCargo']."',$PlanId,'$Servicio')";
									$result=$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Guardar en la Base de Datos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
									}
									$mensaje='El insumo se Guardo Correctamente.';
									if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$D)){
										return false;
									}
									return true;
						}
						else
						{
									$mensaje='El Codigo es incorrecto.';
									if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$D)){
										return false;
									}
									return true;
						}
				}
				else
				{*/
							$query = " INSERT INTO tmp_cuenta_insumos(
																							numerodecuenta,
																							departamento,
																							bodega,
																							codigo_producto,
																							cantidad,
																							empresa_id,
																							centro_utilidad,
																							precio,
																							fecha_cargo,
																							plan_id,
																							servicio_cargo)
													VALUES($Cuenta,'$Departamento','$bodega','$Codigo',$Cantidad,'$empresa','$cu',".$_REQUEST['Precio'].",'".$_REQUEST['FechaCargo']."',$PlanId,'$Servicio')";
							$result=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							$mensaje='El insumo se Guardo Correctamente.';
							if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$D)){
								return false;
							}
							return true;
				//}
	}


	 /**
	 * Elimina un cargo de la cuenta en tmp_cuenta_insumos.
   * @ access public
	 * @ return boolean
	 */
	 function EliminarCargoTmpIyM()
	 {
				$Cuenta=$_REQUEST['Cuenta'];
				$Nivel=$_REQUEST['Nivel'];
				$PlanId=$_REQUEST['PlanId'];
				$Ingreso=$_REQUEST['Ingreso'];
				$Fecha=$_REQUEST['Fecha'];
				$TipoId=$_REQUEST['TipoId'];
				$PacienteId=$_REQUEST['PacienteId'];

				list($dbconn) = GetDBconn();
				$query =" DELETE FROM tmp_cuenta_insumos
									WHERE tmp_cuenta_insumos_id=".$_REQUEST['ID']."
									AND numerodecuenta=$Cuenta";
				$dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Borrar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				$this->frmError["MensajeError"]="El Cargo se Elimino.";
				if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$D)){
					return false;
				}
				return true;
	 }

	/**
	*
	*/
	function GuardarTodosCargosIyM()
	{
			$Cuenta=$_REQUEST['Cuenta'];
			$Nivel=$_REQUEST['Nivel'];
			$PlanId=$_REQUEST['PlanId'];
			$Ingreso=$_REQUEST['Ingreso'];
			$Fecha=$_REQUEST['Fecha'];
			$TipoId=$_REQUEST['TipoId'];
			$PacienteId=$_REQUEST['PacienteId'];

      list($dbconn) = GetDBconn();
      $query = "SELECT count(a.numerodecuenta)
                FROM tmp_cuenta_insumos as a WHERE a.numerodecuenta=$Cuenta";
     $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Guardar en la Base de Datos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      }

			if($result->fields[0]==0)
			{
					$this->frmError["MensajeError"]="NO HA AGREGADO NINGUN INSUMO.";
					if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$D)){
						return false;
					}
					return true;
			}


			$argu=array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
			$_SESSION['INVENTARIOS']['RETORNO']['contenedor']='app';
			$_SESSION['INVENTARIOS']['RETORNO']['modulo']='EstacionEnfermeriaCargos';
			$_SESSION['INVENTARIOS']['RETORNO']['tipo']='user';
			$_SESSION['INVENTARIOS']['RETORNO']['metodo']='RetornoInsumos';
			$_SESSION['INVENTARIOS']['RETORNO']['argumentos']=$argu;
			$_SESSION['INVENTARIOS']['CUENTA']=$Cuenta;

			$this->ReturnMetodoExterno('app','InvBodegas','user','LiquidacionMedicamentos');
			return true;
	}

	/**
	*
	*/
	function RetornoInsumos()
	{
			$Cuenta=$_REQUEST['Cuenta'];
			$Nivel=$_REQUEST['Nivel'];
			$PlanId=$_REQUEST['PlanId'];
			$Ingreso=$_REQUEST['Ingreso'];
			$Fecha=$_REQUEST['Fecha'];
			$TipoId=$_REQUEST['TipoId'];
			$PacienteId=$_REQUEST['PacienteId'];

			if(!empty($_SESSION['INVENTARIOS']['RETORNO']['Bodega']))
			{
						unset($_SESSION['INVENTARIOS']);
						$mensaje='Los Documentos de Bodega han sido Creados Satisfactoriamente.';
						$accion = ModuloGetURL('app','EstacionEnfermeriaCargos','user','main',array("estacion"=>$_SESSION['CUENTAS']['E']['DATOS'],'tipoa'=>2));
						if(!$this-> FormaMensaje($mensaje,'CREAR DOCUMENTO',$accion,'')){
						return false;
						}
						return true;
			}
			else
			{
						unset($_SESSION['INVENTARIOS']);
						$mensaje='ERROR INSERTAR: Los Documentos de Bodega No Fueron Creados.';
						$accion = ModuloGetURL('app','EstacionEnfermeriaCargos','user','main',array("estacion"=>$_SESSION['CUENTAS']['E']['DATOS'],'tipoa'=>2));
						if(!$this-> FormaMensaje($mensaje,'ERROR AL CREAR EL DOCUMENTO',$accion,'')){						return false;
						}
						return true;
			}
	}


  /**
	 * Llama la forma para modificar un cargo de la cuenta en tmp_cuenta_insumos
   * @ access public
	 * @ return boolean
	 */
	 function LlamaFormaModificarCargoTmpIyM()
	 {
				$Datos=$_REQUEST['Datos'];
				$Cuenta=$_REQUEST['Cuenta'];
				$TipoId=$_REQUEST['TipoId'];
				$PacienteId=$_REQUEST['PacienteId'];
				$Nivel=$_REQUEST['Nivel'];
				$PlanId=$_REQUEST['PlanId'];
				$Ingreso=$_REQUEST['Ingreso'];
				$Fecha=$_REQUEST['Fecha'];


				if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Datos)){
					return false;
				}
				return true;
		}

	/**
	*
	*/
	function ModificarCargoTmpIyM()
	{
	 			IncludeLib("tarifario");
				$Departamento=$_SESSION['CUENTAS']['E']['DEPTO'];
				$Precio=$_REQUEST['Precio'];
				$Codigo=$_REQUEST['Codigo'];
				$TarifarioId=$_REQUEST['TarifarioId'];
				$Gravamen=$_REQUEST['Gravamen'];
				$Cantidad=$_REQUEST['Cantidad'];
				$Cuenta=$_REQUEST['Cuenta'];
				$Nivel=$_REQUEST['Nivel'];
				$PlanId=$_REQUEST['PlanId'];
				$Ingreso=$_REQUEST['Ingreso'];
				$Fecha=$_REQUEST['Fecha'];
				$TipoId=$_REQUEST['TipoId'];
				$PacienteId=$_REQUEST['PacienteId'];
				$empresa=$_REQUEST['EmpresaId'];
				$cu=$_REQUEST['CU'];
				$bodega=$_REQUEST['Bodegas'];
				$f=explode('/',$_REQUEST['FechaCargo']);
				$_REQUEST['FechaCargo']=$f[2].'-'.$f[1].'-'.$f[0];

				$SystemId=UserGetUID();
				if(!$Cantidad || !$Codigo || !$_REQUEST['FechaCargo']){
						if(!$Cantidad){ $this->frmError["Cantidad"]=1; }
						if(!$Codigo){ $this->frmError["Codigo"]=1; }
						if(!$FechaCargo){ $this->frmError["FechaCargo"]=1; }
						$this->frmError["MensajeError"]="Faltan datos obligatorios.";
						if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$_REQUEST['Datos'])){
							return false;
						}
						return true;
				}

				$f = (int) $Cantidad;
				$y = $Cantidad - $f;
				if($y != 0){
						if($y != 0){ $this->frmError["Cantidad"]=1; }
						$mensaje='La Cantidad debe ser entera.';
						if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$_REQUEST['Datos'])){
							return false;
						}
						return true;
				}

				list($dbconn) = GetDBconn();
				$query ="SELECT b.servicio
								FROM departamentos as b
								WHERE b.departamento='$Departamento'";
				$results = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$Servicio=$results->fields[0];

				$query = " UPDATE tmp_cuenta_insumos SET
																				cantidad=$Cantidad,
																				fecha_cargo='".$_REQUEST['FechaCargo']."'
									WHERE tmp_cuenta_insumos_id=".$_REQUEST['id']."";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$mensaje='El insumo se Modifico Correctamente.';
				if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,'')){
					return false;
				}
				return true;
	}


function RevisarSi_Es_Egresado($ingreso_dpto)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT estado FROM egresos_departamento
							WHERE
							--estado = '1'
						  ingreso_dpto_id='$ingreso_dpto'
							AND tipo_egreso != '4'
							AND	estado != '2'
							";
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar el sql ingresos_departamento";
				$this->mensajeDeError = "---";
				return false;
			}
   		$info[0]=$result->RecordCount();//sabemos el conteo de los registros
			$info[1]=$result->fields[0];//guardamos la información del estado del egreso
			return $info;

		}

			//revisa si esta pendientepor ingresar a otra estacion,
		function Revisar_Si_esta_trasladado($ingreso)
		{
			 	list($dbconn) = GetDBconn();
	 			/*$sql = "SELECT COUNT(*) FROM ordenes_hospitalizacion
													WHERE hospitalizado = '0'
													AND ingreso=$ingreso";*/
					$sql="SELECT COUNT(*) FROM  pendientes_x_hospitalizar
								WHERE ingreso=$ingreso ";
			  $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
			  return $result->fields[0];
		}



			function BuscarPacientesConsulta_Urgencias($datos_estacion)
		{

				//GLOBAL $ADODB_FETCH_MODE;
        //$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        list($dbconn) = GetDBconn();

				//cambio dar
				//se agrego a.sw_estado=7 paciente de alta de consulta de urg
				//pero q tienen procesos en la estacion
				//fin cambio
				$sql="select c.paciente_id, c.tipo_id_paciente, c.primer_nombre ,
				 c.segundo_nombre , c.primer_apellido ,c.segundo_apellido , e.nivel_triage_id, 				 d.hora_llegada, e.tiempo_atencion, b.ingreso,
				 f.evolucion_id, to_char(f.fecha,'YYYY-MM-DD HH24:MI') as fecha,
				 f.usuario_id, h.nombre, a.estacion_id, d.nivel_triage_id, d.plan_id,
				 d.triage_id, d.punto_triage_id, d.punto_admision_id, d.sw_no_atender,
				 i.numerodecuenta, z.egresos_no_atencion_id, a.sw_estado,i.rango
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
						--and e.nivel_triage_id ISNULL --tener esto en cuenta
						order by e.indice_de_orden, d.hora_llegada;";

        $result = $dbconn->Execute($sql);
        $i=0;
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al traer lospacientes de consulta de urgencias";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

				if($result->EOF)
				{
					return "ShowMensaje";
				}

				while ($data = $result->FetchNextObject())
				{

					$x = $this->get_cuenta_x_ingreso($data->INGRESO);
					$Pacientes[$i][0]  = $data->PRIMER_NOMBRE." ".$data->SEGUNDO_NOMBRE;
					$Pacientes[$i][1]  = $data->PRIMER_APELLIDO." ".$data->SEGUNDO_APELLIDO;
					$Pacientes[$i][2]  = $data->PACIENTE_ID;
					$Pacientes[$i][3]  = $data->TIPO_ID_PACIENTE;
					$Pacientes[$i][4]  = $data->INGRESO;
					$Pacientes[$i][5]  = $data->ORDEN_HOSP;
					$Pacientes[$i][6]  = $x[0]; //CUENTA
					$Pacientes[$i][7]  = $x[1]; //PLAN
					$Pacientes[$i][8]  = $data->TRASLADO;
					$Pacientes[$i][9]  = $desc->fields[0];//descripcion ee origen
					$Pacientes[$i][10] = $data->ESTACION_ORIGEN;//id estacion origen
					$Pacientes[$i][11] = $data->SW_ESTADO;
					$Pacientes[$i][12] = $data->RANGO;
					$i++;
				}
				$result->Close();
				return $Pacientes;

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
            segundo_apellido";//pacientes_x_ingreso_x_pxh
		
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
								WHERE estacion_id = '$data->ESTACION_ORIGEN'";
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
  	$result->Close();
		return $Pacientes;
	}//fin GetPacientesPendientesXHospitalizar




//-----------------------------------------------------------------------------
}//fin class
?>

