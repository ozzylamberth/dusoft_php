<?php
	/**************************************************************************************
	* $Id: Facturar.class_08082007.php,v 1.1 2009/07/27 20:32:03 johanna Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.1 $ 	
	*
	* Manejador de la parte lógica del proceso de facturación e Impresión
	***************************************************************************************/
  IncludeClass('app_Facturacion_Permisos','','app','Facturacion_Fiscal');
	class Facturar
	{
		var $conteo = 0;
		var $pagina = 1;
		var $offset = 0;
		var $limit = 10;
		
		function Facturar(){}

		function ValidarUsuarioAjuste($usuario_siis, $usuario_validar,$pwd_validar)
		{
				$query="SELECT CAU.usuario_id
								FROM cuentas_ajustes_usuarios CAU 
										JOIN system_usuarios SU 
										ON (CAU.usuario_id = SU.usuario_id
												AND SU.usuario = '$usuario_validar');";
				if(!$result = $this->ConexionBaseDatos($query))
					return false;
				if($result->RecordCount() > 0)
				{
					$resultado_usuario=UserValidarUsuario($usuario_validar, $pwd_validar);
	
					if(!$resultado_usuario)
					{
						$this->mensaje="CLAVE ERRADA PARA ".$usuario_validar;
						return false;
					}
						$this->usuario_id = $result->fields[0];
					return true;
				}
				else
				{
					$this->mensaje="USUARIO SIN AUTORIZACIÓN PARA REALIZAR AJUSTE";
					return false;
				}
		}
    function VerificarFactura($cuenta,$sw)
    {
        //cuando es particular es 2  sw_tipo 1->cliente 0->paciente 2->particular
        $query="SELECT a.prefijo, a.factura_fiscal, a.empresa_id
                                FROM fac_facturas_cuentas as a, fac_facturas as b
                                WHERE a.numerodecuenta=$cuenta and a.sw_tipo=0
                                and a.empresa_id=b.empresa_id and a.prefijo=b.prefijo and a.factura_fiscal=b.factura_fiscal
                                AND b.estado not in('2')";
				if(!$result = $this->ConexionBaseDatos($query))
					return false;

				$result->Close();
				if(!$result->EOF)
				{
					return 1;
				}
				else
				{  return 0;    }
    }
  /**
  *
  */
  function FacturaAgrupada($PlanId)
  {
        $query="SELECT sw_facturacion_agrupada FROM planes
                WHERE plan_id='$PlanId'";
				if(!$result = $this->ConexionBaseDatos($query))
					return false;

        return $result->fields[0];
  }
    /**
    *
    */
    function BuscarTotalPaciente($cuenta)
    {
            $query = "select valor_total_paciente from cuentas
                                where numerodecuenta=$cuenta";
						if(!$result = $this->ConexionBaseDatos($query))
							return false;

            $result->Close();
            return $result->fields[0];
    }
  /**
  *
  */
  function SaldoPaciente($EmpresaId,$Cuenta,$Plan)
  {
      $query = "select (a.valor_total_paciente - (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo
                  from cuentas as a
                  where a.empresa_id='$EmpresaId'
                  $CU $est and a.plan_id='$Plan' and a.numerodecuenta=$Cuenta";
			if(!$result = $this->ConexionBaseDatos($query))
				return false;

        $var=$result->fields[0];
        $result->Close();
        return $var;
  }

  /**
  *METODO PARA AJUSTAR UNA CUENTA CON LA SELLECCION DE VARIOS CARGOS DE AJUSTE
  */
  function AjustarCuenta(&$FormaMensaje)
  {
			if($_REQUEST[Empresa] 
				AND $_REQUEST[CentroUtilidad]
				AND $_REQUEST[Cuenta])
			{
				$EmpresaId = $_REQUEST[Empresa];
				$CUtilidad = $_REQUEST[CentroUtilidad];
				$Cuenta = $_REQUEST[Cuenta];
				$Fecha = $_REQUEST[Fecha];
				$SystemId = UserGetUID();
				$Saldo = $_REQUEST[Saldo];
        $FechaRegistro = date("Y-m-d H:i:s");

        if($Saldo > 0)
        {
					$Cargo=ModuloGetVar('app','Facturacion_Fiscal','CargoDescuento');
					$MotivoId = '003';
					$_REQUEST[observacion] = 'CargoDescuento automatico del sistema';
				}
        elseif($Saldo < 0)
        {
					$Cargo=ModuloGetVar('app','Facturacion_Fiscal','CargoAprovechamiento');
					$MotivoId = '005';
					$_REQUEST[observacion] = 'CargoAprovechamiento automatico del sistema.';
				}
				$TarifarioId = 'SYS';
			}
			elseif($_REQUEST[REQUEST])
			{
        $Fecha = $_REQUEST[REQUEST][Arreglo][0][fecha_registro];
        $Cuenta = $_REQUEST[REQUEST][Arreglo][0][numerodecuenta];
        //$Saldo=($_REQUEST['Saldo'])*(-1);
        $Saldo = ($_REQUEST['Saldo']);
        $FechaRegistro = date("Y-m-d H:i:s");
        $SystemId = $_REQUEST[usuario_id];
				$EmpresaId = $_REQUEST[REQUEST][Arreglo][0][empresa_id];
				$CUtilidad = $_REQUEST[REQUEST][Arreglo][0][centro_utilidad];
				$dat = explode('||//',$_REQUEST['motivo_id']);
				$TarifarioId = $dat[0];
				$Cargo = $dat[1];
				$MotivoId = $dat[2];
			}
//         if($Saldo > 0)
//         {   $Cargo=ModuloGetVar('app','Facturacion_Fiscal','CargoDescuento');  }
//         elseif($Saldo < 0)
//         {  $Cargo=ModuloGetVar('app','Facturacion_Fiscal','CargoAprovechamiento');  }

        $query = "SELECT numerodecuenta FROM cuentas_detalle
                  WHERE tarifario_id='$TarifarioId' AND cargo='$Cargo' AND numerodecuenta=$Cuenta";
				if(!$result = $this->ConexionBaseDatos($query))
					return false;
        if(!$result->EOF)
        {
              $accion=SessionGetVar('ActionVolver');;
              $mensaje='La Cuenta No '.$Cuenta.' ya tiene un Cargo de Ajuste.';
              $FormaMensaje->FormaMensaje($mensaje,'AJUSTAR LA CUENTA',$accion,'ACEPTAR');
              return true;
        }

				$query = "SELECT numerodecuenta FROM cuentas_ajustes
									WHERE tarifario_id='$TarifarioId' AND cargo='$Cargo' AND numerodecuenta=$Cuenta";
				if(!$result = $this->ConexionBaseDatos($query))
					return false;
				if(!$result->EOF)
				{
							$accion=SessionGetVar('ActionVolver');;
							$mensaje='A la Cuenta No '.$Cuenta.' ya le han realizado Cargos de ajustes '.$Cargo.'';
							$FormaMensaje->FormaMensaje($mensaje,'AJUSTAR LA CUENTA',$accion,'ACEPTAR');
							return true;
				}

        $query ="SELECT b.servicio, c.departamento_actual
                FROM departamentos as b, cuentas as a, ingresos as c
                WHERE a.numerodecuenta=$Cuenta
                and a.ingreso=c.ingreso
                and c.departamento_actual=b.departamento";
				if(!$results = $this->ConexionBaseDatos($query))
					return false;
        $Servicio=$results->fields[0];
        $Dpto=$results->fields[1];

        $Saldo=($_REQUEST['Saldo'])*(-1);
        $query=" SELECT nextval('cuentas_detalle_transaccion_seq')";
				if(!$result = $this->ConexionBaseDatos($query))
					return false;
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
                      --valor_nocubierto,
                      usuario_id,
                      facturado,
                      fecha_cargo,
                      fecha_registro,
                      servicio_cargo,
                                            sw_cargue)
                  VALUES ($Transaccion,'$EmpresaId','$CUtilidad',$Cuenta,'$Dpto','$TarifarioId','$Cargo',1,$Saldo,$Saldo,
                          --$Saldo,
                          $SystemId,1,'$Fecha','$FechaRegistro',$Servicio,'3');";

				if(!$result = $this->ConexionBaseDatos($query))
					return false;

				$sql = " INSERT INTO cuentas_ajustes
									(
										numerodecuenta,
										tarifario_id,
										cargo,
										valor,
										motivos_ajuste_cuenta_id,
										observacion,
										fecha_registro,
										usuario_id
									)
									VALUES
									(
										$Cuenta,
										'$TarifarioId',
										'$Cargo',
										$Saldo,
										'$MotivoId',
										'$_REQUEST[observacion]', 
										'$FechaRegistro',
										$SystemId
									);";
				if(!$result = $this->ConexionBaseDatos($sql))
					return false;

        $accion=SessionGetVar('ActionVolver');
        $mensaje='La Cuenta No '.$Cuenta.' fue Ajustada.';
        $FormaMensaje->FormaMensaje($mensaje,'AJUSTAR LA CUENTA',$accion,'ACEPTAR');
        return true;
  }

    /**
    * Busca el tercero_id y el plan_descripcion de la table planes.
    * @access public
    * @return array
    * @param string id del plan
    * @param int ingreso
    */
     function BuscarPlanes($PlanId)
     {
                $query = "SELECT sw_tipo_plan FROM planes WHERE plan_id='$PlanId'";
								if(!$results = $this->ConexionBaseDatos($query))
									return false;
                $sw=$results->fields[0];
                //soat
                if($sw==1)
                {
											$query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
																FROM planes as a, terceros as b
																WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
                 }
                //cliente
                if($sw==0)
                {
											$query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
																FROM planes as a, terceros as b
																WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
                }
                //particular
                if($sw==2)
                {
											$query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
																FROM planes as a, terceros as b
																WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";

                }
                //capitado
                if($sw==3)
                {
											$query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
																FROM planes as a, terceros as b
																WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
                }
								if(!$result = $this->ConexionBaseDatos($query))
									return false;
                $var=$result->GetRowAssoc($ToUpper = false);
                $result->Close();
                return $var;
     }
	/**************************************************************************
	*metodo TraerReportesHojaCargos
	**************************************************************************/
	function TraerReportesHojaCargos($Empresa)
	{
			$query = "SELECT ruta_reporte, titulo
								FROM 	reportes_facturas_clientes_planes
								WHERE empresa_id='".$Empresa."'
								AND sw_hoja_cargos = '1';";
			if(!$result = $this->ConexionBaseDatos($query))
				return false;
			while(!$result->EOF)
			{
				$vars[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
			$result->Close();
			return $vars;
	}
	/**************************************************************************
	*metodo facturar
	**************************************************************************/
  function FacturarCuenta(&$FormaMensaje)
  {
        IncludeLib('funciones_facturacion');
        $PlanId=$_REQUEST['PlanId'];
        $TipoId=$_REQUEST['TipoId'];
        $PacienteId=$_REQUEST['PacienteId'];
        $Ingreso=$_REQUEST['Ingreso'];
        $Nivel=$_REQUEST['Nivel'];
        $Fecha=$_REQUEST['Fecha'];
        $Cuenta=$_REQUEST['Cuenta'];

        $SystemId=UserGetUID();
        $FechaRegistro=date("Y-m-d H:i:s");
				//DETALLE DE LA CUENTA
				$arreglo=$this->GetDatosCuenta($Cuenta);
				//
				$fct = new app_Facturacion_Permisos();
				$fact = $fct->DatosFactura('','',$_REQUEST[PuntoFacturacion]);
				$PrefijoCon = $fact['prefijo_fac_contado'];
				$PrefijoCre = $fact['prefijo_fac_credito'];
				$punto = $fact['punto_facturacion_id'];

				$EmpresaId=$arreglo[0][empresa_id];
				$ValorNoCubierto=$arreglo[0][valor_nocubierto];
				$ValorPac=$arreglo[0][valor_cuota_paciente];
				$ValorCubierto=$arreglo[0][valor_cubierto];
				$GravamenEmp=$arreglo[0][gravamen_valor_cubierto];
				$GravamenPac=$arreglo[0][gravamen_valor_nocubierto];
				$Gravamen=$GravamenEmp+$GravamenPac;
				$Descuento=$arreglo[0][valor_descuento_paciente]+$arreglo[0][valor_descuento_empresa];
				$TotalCuenta=$arreglo[0][total_cuenta];

        $datos=$this->BuscarPlanes($PlanId,$Ingreso);
        $Tercero=$datos[tercero_id];
        $TipoTercero=$datos[tipo_id_tercero];
        list($dbconn) = GetDBconn();
                //----------SI TIENE HABITACIONES SE CARGAN AUTOMATICAMENTE
                    if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php"))
                    {
                            die(MsgOut("Error al incluir archivo","El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
                    }

                    $liqHab = new LiquidacionHabitaciones;
                    $hab = $liqHab->LiquidarCargosInternacion($Cuenta,false);
                //---------FIN CARGUE DE CAMAS


                if(is_array($hab))
                {
                        //va  a includes a insertar en cuentas detalle CARGUE DE CAMAS
                        //---como la cuenta esta en un estado no valido la abrimos y luego la cerramos
                        $query = "UPDATE cuentas SET estado='1' WHERE numerodecuenta=$Cuenta";
                        $result=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $FormaMensaje->error = "Error al Guardar fac_facturas_cuentas";
                            $FormaMensaje->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            //$dbconn->RollbackTrans();
                            $FormaMensaje->fileError = __FILE__;
                            $FormaMensaje->lineError = __LINE__;
                            $FormaMensaje->GuardarNumero(false);
                            return false;
                        }
                        $cargue = CargarHabitacionCuenta('',$hab,true,&$dbconn,$EmpresaId,$Cuenta,0);
                        //ocurrio un error al insertar
                        if(empty($cargue))
                        {
                                $mensaje="OCURRIO UN ERROR AL INSERTAR LA HABITACIONES.";
                                //$this->Facturacion();
                                $accion=SessionGetVar('ActionVolver');
                                $FormaMensaje->FormaMensaje($mensaje,'FACTURAR CUENTA',$accion,'ACEPTAR');
                                return true;
                        }
                }

                //------busco cuanto a abonado el paciente para la validacion de la
                $query="SELECT (a.abono_efectivo + a.abono_cheque +
                                a.abono_tarjetas + a.abono_chequespf + a.abono_letras) as abono
                                FROM cuentas as a WHERE a.numerodecuenta=$Cuenta";
                $results = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $FormaMensaje->error = "Error al Cargar el Modulo";
                        $FormaMensaje->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $abono = $results->fields[0];
                $results->Close(); //$abono=0;
/*
                //----------------NUMERACION-------------------------
                //cambiamos numeraciones.
                $va=$this->AsignarNumero($_SESSION['FACTURACION']['PREFIJOCONTADO'],&$dbconn);
                $Facturapac=$va[numero];
                $prefijocon=$va[prefijo];
                //----------------FIN NUMERACION-----------------------
*/
                //-----------CONVENCIONES-------------
                //sw_tipo 1 es cliente y 0 es paciente


                //-------------------TRAER EL TERCERO DE LA FACTURA DEL PACIENTE Y PARTICULAR
                IncludeLib('funciones_facturacion');
                $retorno = ResponsableFacturaPaciente($TipoId,$PacienteId,$EmpresaId,&$dbconn);
                $tipoTerceroFacPaciente = $retorno[tipo_id_tercero];
                $idTerceroFacPaciente = $retorno[tercero_id];
                //-----------------------------------------------------------------------

                $query = "SELECT sw_tipo_plan, sw_facturacion_agrupada
                                                FROM planes
                                                WHERE estado='1' and plan_id='$PlanId'
                                                and fecha_final >= now() and fecha_inicio <= now()";
                $results = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $results->Close();
                $sw=$results->fields[0];
                $tiposfacturacion='';
                //si sw es 1 es soat 3 capitacion 2 particular  o es facturacion agrupada
                //if($sw==1 OR $sw==3 OR $sw==2 OR $results->fields[1]==1)
                if($sw==3 OR $sw==2 OR $results->fields[1]==1)
                {

                            //cuando es particular es 2  sw_tipo 1->cliente 0->paciente 2->particular
                            if( $sw==2)
                            {  $swtipo=2;  }
                            else
                            {  $swtipo=0;  }

                            //OJO ESTA VALIDACION SE REALIZO PARA EL CASO EN QUE SE
                            //ANULA UNA FACTURA Y QUEDAN OTRAS REGISTRADAS CON ESTADO 0
                            //Y NO DEBEN VOLVER A GENERARSE
                            $facturaExiste=0;
                            $query = "SELECT *
                                      FROM fac_facturas_cuentas a,fac_facturas b
                                      WHERE a.numerodecuenta=$Cuenta AND a.prefijo=b.prefijo
                                      AND a.factura_fiscal=b.factura_fiscal AND a.empresa_id='$EmpresaId'
                                      AND sw_tipo='$swtipo' AND b.estado='0'";
                            $results = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                $FormaMensaje->error = "Error al Cargar el Modulo";
                                $FormaMensaje->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }else{
                              if($results->RecordCount()>0){
                                $facturaExiste=1;
                              }
                            }
                            //FIN VALIDACION
                         if($facturaExiste!=1){
                            //----------------NUMERACION-------------------------
                            //cambiamos numeraciones.
                            $va=$this->AsignarNumero($EmpresaId,$PrefijoCon,&$dbconn);
                            $Facturapac=$va[numero];
                            $prefijocon=$va[prefijo];
                            //----------------FIN NUMERACION-----------------------


                            //$tipofac=$this->BuscarTipoFactura($_SESSION['FACTURACION']['PREFIJOCONTADO'],$EmpresaId);
                            //factura paciente
                            $query="INSERT INTO fac_facturas(
                                                                        empresa_id,
                                                                        prefijo,
                                                                        factura_fiscal,
                                                                        estado,
                                                                        usuario_id,
                                                                        fecha_registro,
                                                                        plan_id,
                                                                        tipo_id_tercero,
                                                                        tercero_id,
                                                                        sw_clase_factura,
                                                                        documento_id,
                                                                        tipo_factura)
                                                                VALUES('$EmpresaId','$prefijocon',$Facturapac,0,$SystemId,'$FechaRegistro',
                                                                '$PlanId','$tipoTerceroFacPaciente','$idTerceroFacPaciente',0,".$PrefijoCon.",'$swtipo')";
                            $result=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                $FormaMensaje->error = "Error al Guardar fac_facturas";
                                $FormaMensaje->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                //$dbconn->RollbackTrans();
                                $this->GuardarNumero(false);
                                return false;
                            }

                            $query="INSERT INTO fac_facturas_cuentas(
                                                                            empresa_id,
                                                                            prefijo,
                                                                            factura_fiscal,
                                                                            numerodecuenta,
                                                                            sw_tipo)
                                                                    VALUES('$EmpresaId','$prefijocon',$Facturapac,$Cuenta,'$swtipo')";
                            $result=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                $FormaMensaje->error = "Error al Guardar fac_facturas_cuentas";
                                $FormaMensaje->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                //$dbconn->RollbackTrans();
                                $this->GuardarNumero(false);
                                return false;
                            }
                          }
                            //despues de guardar en facturas se actualiza el estado de la cuenta
                            //si es particular se cierra la cuenta
                            if( $sw==2)
                            {  $estado=0;  }
                            else
                            {  $estado=3;  }
                            $query = "UPDATE cuentas SET estado=$estado,
                                                    fecha_cierre='now()',
                                                    usuario_id=".UserGetUID()."
                                                WHERE numerodecuenta=$Cuenta";
                            $result=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                $FormaMensaje->error = "Error al Guardar fac_facturas_cuentas";
                                $FormaMensaje->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                //$dbconn->RollbackTrans();
                                echo $FormaMensaje->error;
                                $this->GuardarNumero(false);
                                return false;
                            }
                            $tiposfacturacion=1;

                }
                else
                {    //el paciente no tiene que pagar nada       y no abono nada

                        if($arreglo[0]['valor_total_paciente']>0 AND $abono >0)
                        {
                                //OJO ESTA VALIDACION SE REALIZO PARA EL CASO EN QUE SE
                                //ANULA UNA FACTURA Y QUEDAN OTRAS REGISTRADAS CON ESTADO 0
                                //Y NO DEBEN VOLVER A GENERARSE
                                $facturaExiste=0;
                                $query = "SELECT *
                                          FROM fac_facturas_cuentas a,fac_facturas b
                                          WHERE a.numerodecuenta=$Cuenta AND a.prefijo=b.prefijo
                                          AND a.factura_fiscal=b.factura_fiscal AND a.empresa_id='$EmpresaId'
                                          AND sw_tipo='0' AND b.estado='0'";

                                $results = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al Cargar el Modulo";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                                }else{
                                  if($results->RecordCount()>0){
                                    $facturaExiste=1;
                                  }
                                }
                                //FIN VALIDACION
                              if($facturaExiste!=1){
                                //----------------NUMERACION-------------------------
                                //cambiamos numeraciones.
                                $va=$this->AsignarNumero($EmpresaId,$PrefijoCon,&$dbconn);
                                $Facturapac=$va[numero];
                                $prefijocon=$va[prefijo];
                                //----------------FIN NUMERACION-----------------------

                                //aqui el sw_tipo es cero 0
                                //$tipofac=$this->BuscarTipoFactura($_SESSION['FACTURACION']['PREFIJOCONTADO'],$EmpresaId);
                                //factura paciente
                                $query="INSERT INTO fac_facturas(
                                                                            empresa_id,
                                                                            prefijo,
                                                                            factura_fiscal,
                                                                            estado,
                                                                            usuario_id,
                                                                            fecha_registro,
                                                                            plan_id,
                                                                            tipo_id_tercero,
                                                                            tercero_id,
                                                                            sw_clase_factura,
                                                                            documento_id,
                                                                            tipo_factura)
                                                                    VALUES('$EmpresaId','$prefijocon',$Facturapac,0,$SystemId,'$FechaRegistro',
                                                                    '$PlanId','$tipoTerceroFacPaciente','$idTerceroFacPaciente',0,".$PrefijoCon.",'0')";
                                $result=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                    $FormaMensaje->error = "Error al Guardar fac_facturas";
                                    $FormaMensaje->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    //$dbconn->RollbackTrans();
                                    $this->GuardarNumero(false);
                                    return false;
                                }
                                //sw_tipo 1->cliente 0->paciente 2->particular
                                $query="INSERT INTO fac_facturas_cuentas(
                                                                                empresa_id,
                                                                                prefijo,
                                                                                factura_fiscal,
                                                                                numerodecuenta,
                                                                                sw_tipo)
                                                                        VALUES('$EmpresaId','$prefijocon',$Facturapac,$Cuenta,'0')";
                                $result=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                    $FormaMensaje->error = "Error al Guardar fac_facturas_cuentas";
                                    $FormaMensaje->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    //$dbconn->RollbackTrans();
                                    $this->GuardarNumero(false);
                                    return false;
                                }
                                $facPac=true;
                              }
                        }
                        if($arreglo[0]['valor_total_empresa']>0)
                        {

                                //OJO ESTA VALIDACION SE REALIZO PARA EL CASO EN QUE SE
                                //ANULA UNA FACTURA Y QUEDAN OTRAS REGISTRADAS CON ESTADO 0
                                //Y NO DEBEN VOLVER A GENERARSE
                                $facturaExiste=0;
                                $query = "SELECT *
                                          FROM fac_facturas_cuentas a,fac_facturas b
                                          WHERE a.numerodecuenta=$Cuenta AND a.prefijo=b.prefijo
                                          AND a.factura_fiscal=b.factura_fiscal AND a.empresa_id='$EmpresaId'
                                          AND sw_tipo='1' AND b.estado='0'";

                                $results = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                    $FormaMensaje->error = "Error al Cargar el Modulo";
                                    $FormaMensaje->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                                }else{
                                  if($results->RecordCount()>0){
                                    $facturaExiste=1;
                                  }
                                }
                                //FIN VALIDACION
                              if($facturaExiste!=1){
                                //$tipofac=$this->BuscarTipoFactura($_SESSION['FACTURACION']['PREFIJOCREDITO'],$EmpresaId);
                                //----------------NUMERACION-------------------------
                                //cambiamos numeraciones.
                                $var=$this->AsignarNumero($EmpresaId,$PrefijoCre,&$dbconn);
                                $Factura=$var[numero];
                                $Prefijo=$var[prefijo];
                                //----------------FIN NUMERACION-----------------------
                                //factura cliente
                                //sw_clase_factura=0 contado 1 credito
                                $query="INSERT INTO fac_facturas(
                                                                            empresa_id,
                                                                            prefijo,
                                                                            factura_fiscal,
                                                                            estado,
                                                                            usuario_id,
                                                                            fecha_registro,
                                                                            plan_id,
                                                                            tipo_id_tercero,
                                                                            tercero_id,
                                                                            sw_clase_factura,
                                                                            documento_id,
                                                                            tipo_factura)
                                                                    VALUES('$EmpresaId','$Prefijo',$Factura,0,$SystemId,'$FechaRegistro',
                                                                    '$PlanId','$TipoTercero','$Tercero',1,".$PrefijoCre.",'1')";
                                $result=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                    $FormaMensaje->error = "Error al Guardar fac_facturas";
                                    $FormaMensaje->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                                }
                                //sw_tipo 1->cliente 0->paciente 2->particular
                                $query="INSERT INTO fac_facturas_cuentas(
                                                                            empresa_id,
                                                                            prefijo,
                                                                            factura_fiscal,
                                                                            numerodecuenta,
                                                                            sw_tipo)
                                                                    VALUES('$EmpresaId','$Prefijo',$Factura,$Cuenta,'1')";
                                $result=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                    $FormaMensaje->error = "Error al Guardar fac_facturas_cuentas";
                                    $FormaMensaje->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                                }
                                $facClie=true;
                              }
                        }
                        //despues de guardar en facturas se actualiza el estado de la cuenta
                        $query = "UPDATE cuentas SET estado=0,
                                                fecha_cierre='now()',
                                                usuario_id=".UserGetUID()."
                                            WHERE numerodecuenta=$Cuenta";
                        $result=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $FormaMensaje->error = "Error al Guardar fac_facturas_cuentas";
                            $FormaMensaje->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            //$dbconn->RollbackTrans();
                            $this->GuardarNumero(false);
                            return false;
                        }
                        $tiposfacturacion=2;
                }

                $dbconn->CommitTrans();
                //solo factura paciente
                if($tiposfacturacion==1)
                {   $mensaje='La Cuenta No. '.$Cuenta.' ha sido FACTURADA, el Número de Factura Paciente Asignada fue: '.$prefijocon.' '.$Facturapac.'';   }
                elseif($tiposfacturacion==2)
                {   //no es agrupada pero hay que validar si se le hizo al paciente y al cliente o a cual
                        if(!empty($facClie) AND !empty($facPac))
                        {  $mensaje='La Cuenta No. '.$Cuenta.' ha sido FACTURADA, el Número de Factura Cliente Asignada fue: '.$Prefijo.' '.$Factura.', el Número de Factura Paciente Asignada fue: '.$prefijocon.' '.$Facturapac.'';   }
                        elseif(!empty($facClie))
                        {  $mensaje='La Cuenta No. '.$Cuenta.' ha sido FACTURADA, el Número de Factura Cliente Asignada fue: '.$Prefijo.' '.$Factura.', NO SE GENERO FACTURA PARA EL PACIENTE';   }
                        elseif(!empty($facPac))
                        {  $mensaje='La Cuenta No. '.$Cuenta.' ha sido FACTURADA, el Número de Factura Paciente Asignada fue: '.$prefijocon.' '.$Facturapac.', NO SE GENERO FACTURA PARA EL CLIENTE';   }
                        elseif(empty($facClie) AND empty($facPac))
                        {  $mensaje='La Cuenta No. '.$Cuenta.' ha sido CERRADA, no se genera Factura Cliente ni Factura Paciente, debido a que el valor a pagar es cero.';   }
                }

                $accion=SessionGetVar('ActionVolver');
                $FormaMensaje->LlamaFormaFacturarImpresion($EmpresaId,$mensaje,$Cuenta,$prefijocon,$Facturapac,$Prefijo,$Factura,$PlanId,$sw);
                /*if(!$this-> FormaMensaje($mensaje,' CUENTA No. '.$Cuenta,$accion,'ACEPTAR')){
                        return false;
                }*/
                return true;
    }

	/*
	CUADRAR CUENTA
	*/
	function CerrarCuenta($arreglo,&$RetornoMetodo)
  {
      $PlanId=$arreglo[0]['plan_id'];
      $TipoId=$arreglo[0]['tipo_id_paciente'];
      $PacienteId=$arreglo[0]['paciente_id'];
      $Ingreso=$arreglo[0]['ingreso'];
      //$Nivel=$arreglo[0]['Nivel'];
      //$Fecha=$arreglo[0]['Fecha'];
      $Cuenta=$arreglo[0]['numerodecuenta'];
            IncludeLib('funciones_facturacion');

      $query = "SELECT sw_apertura_admision
                FROM ingresos
                WHERE ingreso=$Ingreso";
			if(!$rst = $this->ConexionBaseDatos($query)) return false;
      if($rst->fields[0] == '1')
      {
        $msg='Esta seguro que desea CUADRAR la Cuenta No. '.$Cuenta;
        $arreglo=array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
        $RetornoMetodo->ReturnMetodoExterno('app','Facturar','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturar','me2'=>'','me'=>'LlamaCuadrarFactura','mensaje'=>$msg,'titulo'=>'CUADRAR CUENTA No. '.$Cuenta,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
        return true;
      }
      $query="SELECT count(numerodecuenta) FROM cuentas WHERE ingreso=$Ingreso";
			if(!$resul = $this->ConexionBaseDatos($query)) return false;
           //solo tiene una cuenta
            if($resul->fields[0] == 1)
            {
                        //revisa q no tenga cirugias por liquidar
                        $query="SELECT count(numerodecuenta) FROM cuentas_liquidaciones_qx
                                        WHERE numerodecuenta=$Cuenta and (estado='0' OR estado='1')";
												if(!$result = $this->ConexionBaseDatos($query)) return false;
                        $result->Close();
                        if($result->fields[0] > 0)
                        {           //tiene pendiente
                                    $mensaje='La Cuenta No '.$Cuenta.' no se puede Cuadrar: Tiene una Cirugía sin Liquidar.';
                                    $accion=SessionGetVar('ActionVolver');
                                    $RetornoMetodo->FormaMensaje($mensaje,'CUADRAR CUENTA',$accion,'ACEPTAR');
                                    return true;
                        }

                        $query="SELECT * FROM hc_evoluciones WHERE ingreso=$Ingreso and estado!=0";
												if(!$result = $this->ConexionBaseDatos($query)) return false;

												if(!$result->EOF)
														{      //mensaje evolucion ABIERTA
		
																				$mensaje='La Cuenta No '.$Cuenta.' no se puede Cuadrar: Tiene Evolución Abierta.';
                                        $accion=SessionGetVar('ActionVolver');
																				$RetornoMetodo->FormaMensaje($mensaje,'CUADRAR CUENTA',$accion,'ACEPTAR');
																				return true;
														}

                        //mira si tiene pendientes por cargar
                        $y=BuscarPendientesCargar($Ingreso);
                        if(!empty($y))
                        {      //mensaje tiene pendientes
                                    $mensaje='La Cuenta No '.$Cuenta.' no se puede Cuadrar: TIENE CARGOS PENDIENTES.';
                                    $accion=SessionGetVar('ActionVolver');
                                    $RetornoMetodo->FormaMensaje($mensaje,'CUADRAR CUENTA',$accion,'ACEPTAR');
                                    return true;
                        }
            }
            //ARRANQUE CALI

            //validacion de ordenes de servicio sin cumplir
            $query = "SELECT count(*)

            FROM os_maestro a, hc_os_solicitudes b
            LEFT JOIN hc_os_solicitudes_manuales e ON(b.hc_os_solicitud_id=e.hc_os_solicitud_id),
            hc_evoluciones c, ingresos i, cuentas d,
            system_modulos_variables f
            WHERE a.hc_os_solicitud_id=b.hc_os_solicitud_id
            AND b.evolucion_id=c.evolucion_id
            AND c.ingreso=i.ingreso
            AND i.ingreso=$Ingreso
            AND i.ingreso=d.ingreso
            AND d.numerodecuenta=$Cuenta
            AND (a.sw_estado='1' OR a.sw_estado='2')
            AND e.hc_os_solicitud_id IS NULL
            AND b.sw_ambulatorio='0'
            AND f.modulo='Facturacion_Fiscal'
            AND f.modulo_tipo='app'
            AND f.variable='ValidacionOSCuadreCuenta'
            AND f.valor='1'";
						if(!$result = $this->ConexionBaseDatos($query)) return false;
            if($result->fields[0] > 0)
            {
                $accion=SessionGetVar('ActionVolver');
                $mensaje='La Cuenta No. '.$Cuenta.' tiene Ordenes de Servicio sin cumplir. ';
                $RetornoMetodo->FormaMensaje($mensaje,'ERROR AL CUADRAR LA CUENTA',$accion,'ACEPTAR');
                return true;
            }
            //fin validacion

            $query = "SELECT estado
            FROM ingresos
            WHERE ingreso=$Ingreso";
						if(!$result = $this->ConexionBaseDatos($query)) return false;
            if($result->fields[0] == '0')
            {
                $msg='Esta seguro que desea CUADRAR la Cuenta No. '.$Cuenta;
                    $arreglo=array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
                    $RetornoMetodo->ReturnMetodoExterno('app','Facturar','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturar','me2'=>SessionGetVar('ActionVolver'),'me'=>'LlamaCuadrarFactura','mensaje'=>$msg,'titulo'=>'CUADRAR CUENTA No. '.$Cuenta,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                    return true;
            }

            $query = "SELECT *
            FROM hc_ordenes_medicas a
            WHERE a.ingreso=$Ingreso AND a.sw_estado IN ('0','1') AND a.hc_tipo_orden_medica_id IN ('99','06','07')";//a.sw_estado='0' ORDEN CONFIRMADA / '1' = ORDEN PENDIENTE
            global $ADODB_FETCH_MODE;                                                                                                           
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						if(!$result = $this->ConexionBaseDatos($query)) return false;
            //$result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if($result->EOF)
            {
                $accion=SessionGetVar('ActionVolver');
                $mensaje='La Cuenta No '.$Cuenta.' no se puede Cuadrar: El Paciente no tiene orden de salida.';
                $RetornoMetodo->FormaMensaje($mensaje,'ERROR AL CUADRAR LA CUENTA',$accion,'ACEPTAR');
                return true;
            }

            $datosOrdenMedica = $result->FetchRow();
            $result->Close();

            $query = "SELECT count(*)
            FROM hc_vistosok_salida_detalle a
            WHERE a.ingreso=$Ingreso AND a.evolucion_id=".$datosOrdenMedica['evolucion_id']." AND a.visto_id='01'";

						if(!$result = $this->ConexionBaseDatos($query)) return false;
            if($result->fields[0]<1)
            {
                $accion=SessionGetVar('ActionVolver');
                $mensaje='La Cuenta No '.$Cuenta.' no se puede Cuadrar: El Paciente no tiene Visto Bueno de la EE.';
                $RetornoMetodo->FormaMensaje($mensaje,'ERROR AL CUADRAR LA CUENTA',$accion,'ACEPTAR');
                return true;
            }
            //FIN ARRANQUE CALI

            //verifica que el paciente tiene movimientos de camas
            $query = " select numerodecuenta from movimientos_habitacion
                                    where numerodecuenta=$Cuenta";
						if(!$result = $this->ConexionBaseDatos($query)) return false;
            //no tiene movimeintos
            if($result->EOF)
            {
                                //--no tiene moviemientos y le dieron salida en urgencias
                                $msg='Esta seguro que desea CUADRAR la Cuenta No. '.$Cuenta;
                                $arreglo=array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
                                $RetornoMetodo->ReturnMetodoExterno('app','Facturar','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturar','me2'=>SessionGetVar('ActionVolver'),'me'=>'LlamaCuadrarFactura','mensaje'=>$msg,'titulo'=>'CUADRAR CUENTA No. '.$Cuenta,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                                return true;

            }
            else
            {
                            $query = "SELECT count(*)
                            FROM hc_ordenes_medicas a,hc_vistosok_salida_detalle b
                            WHERE a.ingreso=$Ingreso AND a.sw_estado='1' AND a.hc_tipo_orden_medica_id IN ('99','06','07') AND
                            a.ingreso=b.ingreso AND b.visto_id='01' AND a.evolucion_id=b.evolucion_id";//a.sw_estado='0'
													if(!$result = $this->ConexionBaseDatos($query)) return false;
													if(!$result->EOF)
													{
																			IncludeClass('LiquidacionHabitaciones');
																			$liqHab = new LiquidacionHabitaciones;
																			$hab = $liqHab->LiquidarCargosInternacion($Cuenta,false);
																			if(is_array($hab)){
																				$accion=SessionGetVar('ActionVolver');
																				$mensaje='La Cuenta No '.$Cuenta.' no se puede Cuadrar: Deben cargarse los cargos de habitaciones pendientes.';
																				$RetornoMetodo->FormaMensaje($mensaje,'ERROR AL CUADRAR LA CUENTA',$accion,'ACEPTAR');
																				return true;
																			}
																			$msg='Esta seguro que desea CUADRAR la Cuenta No. '.$Cuenta;
																			$arreglo=array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
																			$RetornoMetodo->ReturnMetodoExterno('app','Facturar','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturar','me2'=>SessionGetVar('ActionVolver'),'me'=>'LlamaCuadrarFactura','mensaje'=>$msg,'titulo'=>'CUADRAR CUENTA No. '.$Cuenta,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
																			return true;
													}//no se ha dado la orden de egreso
													else
													{
																			$accion=SessionGetVar('ActionVolver');
																			$mensaje='La Cuenta No '.$Cuenta.' no se puede Cuadrar: El Paciente no se encuentra en cuentas por liquidar, informe a sistemas de este mensaje GRACIAS.';
																			$RetornoMetodo->FormaMensaje($mensaje,'ERROR AL CUADRAR LA CUENTA',$accion,'ACEPTAR');
																			return true;
													}
            }
  }

		/**
		CUADRAR FACTURA
		**/
		function CuadrarFactura($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$Estado,&$formamensaje)
		{
					$query = "UPDATE ingresos SET estado='0',fecha_cierre='now()'
										WHERE ingreso=$Ingreso AND sw_apertura_admision='1';";
					if(!$result = $this->ConexionBaseDatos($query)) return false;
					$query = "UPDATE cuentas SET estado='3', fecha_cierre='now()',
																		usuario_cierre=".UserGetUID()."
										WHERE numerodecuenta=$Cuenta";
					if(!$result = $this->ConexionBaseDatos($query)) return false;
									$mensaje='La Cuenta No. '.$Cuenta.' ha sido CUADRADA.';
									$accion=SessionGetVar('ActionVolver');
									if(!$formamensaje-> FormaMensaje($mensaje,'CUADRAR CUENTA No. '.$Cuenta,$accion,'ACEPTAR')){
									return false;
									}
									return true;
		}

  /**
  *
  */
  function DatosFactura($Empresa,$Prefijo,$Factura,$Cuenta)
  {
              $query = "
                        SELECT
                        a.abonos,
                        a.numerodecuenta,
                        a.ingreso,
                        a.plan_id,
                        a.empresa_id,
                        b.plan_descripcion,
                        c.nombre_tercero,
                        c.tipo_id_tercero,
                        c.tercero_id,
                        d.tipo_id_paciente,
                        d.paciente_id,
                        --d.fecha_cierre,
                        y.fecha_egreso as fecha_cierre_movimientos_habitacion,
                        z.fecha_registro as fecha_cierre,
                        e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
                        e.residencia_telefono,
                        e.residencia_direccion,
                        a.prefijo,
                        a.factura_fiscal,
                        d.departamento_actual as dpto,
                        h.descripcion,
                        i.razon_social,
                        i.direccion,
                        i.telefonos,
                        i.tipo_id_tercero as tipoid,
                        i.id,
                        j.departamento,
                        k.municipio,
                        d.fecha_registro,
                        a.sw_tipo,
                        a.valor_cuota_paciente,
                        a.valor_nocubierto,
                        a.valor_cubierto,
                        a.valor_descuento_empresa,
                        a.valor_descuento_paciente,
                        a.total_cuenta,
                        a.abono_efectivo,
                        a.abono_cheque,
                        a.abono_tarjetas,
                        a.abono_chequespf,
                        a.abono_letras,
                        a.valor_total_paciente,
                        a.valor_total_empresa,
                        a.valor_cuota_moderadora,
                        x.texto1,
                        x.texto2,
                        x.mensaje,
                        x.numero_digitos,
                        a.fechafac,
                        c.direccion AS direccion_tercero,
                        c.telefono AS telefono_tercero

                        FROM
                        (
                            SELECT
                                a.empresa_id,
                                a.prefijo,
                                a.factura_fiscal,
                                a.fecha_registro AS fechafac,
                                a.documento_id,
                                b.sw_tipo,
                                (c.abono_efectivo + c.abono_cheque + c.abono_tarjetas + c.abono_chequespf + c.abono_bonos) as abonos,
                                c.numerodecuenta,
                                c.ingreso,
                                c.plan_id,
                                c.valor_cuota_paciente,
                                c.valor_nocubierto,
                                c.valor_cubierto,
                                c.valor_descuento_empresa,
                                c.valor_descuento_paciente,
                                c.total_cuenta,
                                c.abono_efectivo,
                                c.abono_cheque,
                                c.abono_tarjetas,
                                c.abono_chequespf,
                                c.abono_letras,
                                c.valor_total_paciente,
                                c.valor_total_empresa,
                                c.valor_cuota_moderadora
                            FROM
                            fac_facturas as a,
                            fac_facturas_cuentas as b,
                            cuentas as c

                            WHERE
                            --a.empresa_id = '".$Empresa."'
                            a.empresa_id = '".$Empresa."'
                            AND a.prefijo = '".$Prefijo."'
                            AND a.factura_fiscal = ".$Factura."
                            --AND b.empresa_id = '".$Empresa."'
                            AND b.empresa_id = '".$Empresa."'
                            AND b.prefijo = '".$Prefijo."'
                            AND b.factura_fiscal = ".$Factura."
                            AND b.numerodecuenta = $Cuenta
                            AND c.numerodecuenta = $Cuenta
                        ) as a,
                        planes as b,
                        terceros as c,
                        ingresos as d LEFT JOIN movimientos_habitacion y
                        ON (d.ingreso = y.ingreso)
                        LEFT JOIN ingresos_salidas z
                        ON (d.ingreso = z.ingreso),
                        pacientes as e,
                        departamentos as h,
                        empresas as i,
                        tipo_dptos as j,
                        tipo_mpios as k,
                        documentos as x

                        WHERE
                        b.plan_id = a.plan_id
                        AND c.tipo_id_tercero = b.tipo_tercero_id
                        AND c.tercero_id = b.tercero_id
                        AND d.ingreso = a.ingreso
                        AND e.paciente_id = d.paciente_id
                        AND e.tipo_id_paciente = d.tipo_id_paciente
                        AND h.departamento = d.departamento_actual
                        AND i.empresa_id = a.empresa_id
                        AND j.tipo_pais_id = i.tipo_pais_id
                        AND j.tipo_dpto_id = i.tipo_dpto_id
                        AND k.tipo_pais_id = i.tipo_pais_id
                        AND k.tipo_dpto_id = i.tipo_dpto_id
                        AND k.tipo_mpio_id = i.tipo_mpio_id
                        AND x.documento_id = a.documento_id
                        AND x.empresa_id = a.empresa_id
              ";

			if(!$result = $this->ConexionBaseDatos($query)) return false;

        $vars=$result->GetRowAssoc($ToUpper = false);

        $result->Close();
        return $vars;
  }

    function GetDatosCuenta($Cuenta)
    {
			$sql = "SELECT a.*, 
								(a.valor_total_paciente - (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo, 
								c.tipo_id_paciente, c.paciente_id, 
								c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre, 
								a.rango, case when a.estado='1' then 'A' when a.estado='2' then 'I' when a.estado='3' then 'C' end as estado 
							FROM cuentas as a, ingresos as b, pacientes as c 
							WHERE a.estado in('1','2','3') 
							AND a.numerodecuenta =$Cuenta 
							AND a.ingreso=b.ingreso 
							AND b.tipo_id_paciente=c.tipo_id_paciente 
							AND b.paciente_id=c.paciente_id;"; 
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			while (!$rst->EOF)
			{
				$arreglo[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $arreglo;
    }

		/**********************************************************************************
		* Funcion donde se obtienen los motivos por los cuales se hace el ajuste de cuenta
		*
		* returns array Arreglo asociativo de datos de los motivos
		***********************************************************************************/
		function ObtenerMotivosAjusteCuenta()
		{
			$sql  = "SELECT MAC.motivos_ajuste_cuenta_id AS motivo_id,";
			$sql .= "				TD.descripcion, MAC.tarifario_id, MAC.cargo ";
			$sql .= "FROM		motivos_ajuste_cuenta MAC, ";
			$sql .= "				tarifarios_detalle TD ";
			$sql .= "WHERE MAC.tarifario_id = TD.tarifario_id ";
			$sql .= "AND MAC.cargo = TD.cargo ";
			$sql .= "ORDER BY TD.descripcion ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/************************************************************************************ 
		*
		*************************************************************************************/
		function AsignarNumero($EmpresaId,$prefijo,&$dbconn)
    {
            if((!empty($prefijo)))
                {
                        $sql="LOCK TABLE documentos IN ROW EXCLUSIVE MODE";
                        $result = $dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() != 0) {
                            die(MsgOut("Error al iniciar la transaccion","Error DB : " . $dbconn->ErrorMsg()));
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        //actualizacion contado
                        $sql="UPDATE documentos set numeracion=numeracion + 1
                                    WHERE  documento_id=$prefijo and empresa_id='".$EmpresaId."'";
                        /*$sql="UPDATE fac_tipos_facturas set numeracion=numeracion + 1
                                    WHERE  prefijo='$prefijo' and empresa_id='".$_SESSION['FACTURACION']['EMPRESA']."'";*/
                        $result = $dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() != 0) {
                            die(MsgOut("Error al actualizar numeracion","Error DB : " . $dbconn->ErrorMsg()));
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        if($dbconn->Affected_Rows() == 0){
                            die(MsgOut("Error al actualizar numeracion","El prefijo '$prefijo' no existe."));
                            $dbconn->RollbackTrans();
                            return false;
                        }

                        //sacamos el numero de la factura de contado.
                        $sql="SELECT numeracion,prefijo FROM documentos
                                    WHERE documento_id=$prefijo  and empresa_id='".$EmpresaId."'";
                        /*$sql="SELECT numeracion,prefijo FROM fac_tipos_facturas
                                    WHERE prefijo='$prefijo'  and empresa_id='".$_SESSION['FACTURACION']['EMPRESA']."'";*/
                        $result = $dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() != 0) {
                            die(MsgOut("Error al traer numeracion","Error DB : " . $dbconn->ErrorMsg()));
                            $dbconn->RollbackTrans();
                            return false;
                        }

                        if ($result->EOF) {
                            die(MsgOut("Error al actualizar numeracion","El tipo de numeracion '$prefijo' no existe."));
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        list($numerodoc['numero'],$numerodoc['prefijo'])=$result->fetchRow();

                        return $numerodoc;
                }

                die(MsgOut("Error al actualizar numeracion","El prefijo &nbsp;['$prefijo']&nbsp; esta vacio."));
                return false;
    }
		/************************************************************************************ 
		*
		*************************************************************************************/
    /*
    * aqui finiquitamos la transaccion de
    * la insercion de las facturas
    * si enviamos TRUE,por el contrario si enviamos FALSE hara un rollback.
    *
    */
    function GuardarNumero($commit=true)
    {
            list($dbconn) = GetDBconn();
            if($commit)
            {
                $sql="COMMIT";
            }
            else
            {
                $sql="ROLLBACK";
            }

            $result = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                die(MsgOut("Error al terminar la transaccion","Error DB : " . $dbconn->ErrorMsg()));
                return false;
            }
            return true;
    }
		/************************************************************************************ 
		*
		*************************************************************************************/
		function ObtenerDatosPlan($plan)
		{
			$sql .= "SELECT	PL.plan_id, ";
			$sql .= "				PL.plan_descripcion, ";
			$sql .= "				TE.tercero_id, "; 
			$sql .= "				TE.tipo_id_tercero,"; 
			$sql .= "				TE.nombre_tercero  "; 
			$sql .= "FROM		planes PL, ";
			$sql .= "				terceros TE ";
			$sql .= "WHERE	PL.plan_id = ".$plan." ";	
			$sql .= "AND		TE.tercero_id = PL.tercero_id ";
			$sql .= "AND		TE.tipo_id_tercero = PL.tipo_tercero_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$planes = array();
			while (!$rst->EOF)
			{
				$planes = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $planes;
		}
		/************************************************************************************ 
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*************************************************************************************/
		function ProcesarSqlConteo($consulta,$limite=null)
		{
			$this->offset = 0;
			$this->pagina = 1;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
			}
			else
			{
				$this->limit = $limite;
			}
			
			if($this->requestoff)
			{
				$this->pagina = intval($this->requestoff);
				if($this->pagina > 1)
				{
					$this->offset = ($this->pagina - 1) * ($this->limit);
				}
			}		
			
			if(!$_REQUEST['registros'])
			{
				if(!$rst = $this->ConexionBaseDatos($consulta))
					return false;
	
				if(!$rst->EOF)
				{
					$this->conteo = $rst->fields[0];
					$rst->MoveNext();
				}
				$rst->Close();
			}
			else
			{
				$this->conteo = $_REQUEST['registros'];
			}
			return true;
		}
		/**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param 	string  $sql	sentencia sql a ejecutar 
		* @return rst 
		************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				//echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}
		/**********************************************************************************
		* Funcion que permite crear una transaccion 
		* @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
		* @param char $num Numero correspondiente a la sentecia sql - por defect es 1
		*
		* @return object Objeto de la transaccion - Al momento de iniciar la transaccion no 
		*								 se devuelve nada
		***********************************************************************************/
		function ConexionTransaccion($sql,$num = '1')
		{
			if(!$sql)
			{
				list($this->dbconn) = GetDBconn();
				//$this->dbconn->debug=true;
				$this->dbconn->BeginTrans();
			}
			else
			{
				$rst = $this->dbconn->Execute($sql);
				if ($this->dbconn->ErrorNo() != 0)
				{
					$this->frmError['MensajeError'] = "ERROR DB : " . $this->dbconn->ErrorMsg();
					echo "<b class=\"label\">Trasaccion: $num - ".$this->frmError['MensajeError']."</b>";
					$this->dbconn->RollbackTrans();
					return false;
				}
				return $rst;
			}
		}
	}
?>