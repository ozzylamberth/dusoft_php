<?php
    /**
    * $Id: app_Facturacion_user.php,v 1.137 2007/05/09 16:45:45 carlos Exp $
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @package IPSOFT-SIIS
    *
    * Proposito del Archivo: Manejo logico de la facturacion.
    */
    /**
    * Clase app_Facturacion_user
    *
    * Contiene los metodos para realizar el triage y admision de los pacientes de urgencias
    */
    IncludeClass('Facturacion','','app','Facturacion');
    IncludeClass('app_Facturacion_Permisos','','app','Facturacion_Fiscal');
    class app_Facturacion_user extends classModulo
    {
      var $limit;
      var $conteo;
      /**
      * Es el contructor de la clase
      * @return boolean
      */
      function app_Facturacion_user()
      {
        IncludeLib('funciones_facturacion');
        $this->limit=GetLimitBrowser();
        return true;
      }
        /****************************************************************************
        * Funcion donde se busca si el usuario posee o no permisos de entrada al
        * modulo
        *****************************************************************************/
        function PermisosUsuario()
        {
            unset($_SESSION['CUENTAS']);

            $fct = new Facturacion();
            $cuentas = $fct->ObtenerPermisos(UserGetUID());
            $url[0] = 'app';
            $url[1] = 'Facturacion';
            $url[2] = 'user';
            $url[3] = 'Menus';
            $url[4] = 'Cuenta';
            $arreglo[0] = 'EMPRESA';
								
            $_SESSION['SEGURIDAD']['FILTRO']['url'] = $url;
            $_SESSION['SEGURIDAD']['FILTRO']['cuenta'] = $cuentas;
            $_SESSION['SEGURIDAD']['FILTRO']['puntos'] = $cuentas['segurid'];
            $_SESSION['SEGURIDAD']['FILTRO']['arreglo'] = $arreglo;
						
						SessionDelVar("Opciones");
						SessionDelVar("DepartamentoCuentas");
						if(sizeof($cuentas[documento]) > 0)
						{
							if(!SessionIsSetVar("EmpresaCuentas"))
												SessionSetVar("EmpresaCuentas",$cuentas[empresa][0]);

							$url[3] = 'SetPtoFacturacion';
							$url[4] = 'permiso';
							$arreglo[0] = 'DOCUMENTOS';
							SessionDelVar("DocumentosCuentas");
							$fct = new Facturacion();
							$array = $fct->ObtenerTiposDocumentos($cuentas[empresa][0]);//$tipos,$emp,$punto
               // $_SESSION['CUENTAS']['CENTROUTILIDAD'] = $fact['centro_utilidad'];
							$this->salida = gui_theme_menu_acceso('CUENTAS',$arreglo,$array,$url,ModuloGetURL('system','Menu'));
            }
						elseif(sizeof($cuentas) > 1)
						{
							$this->salida = gui_theme_menu_acceso('MENU - CUENTAS',$arreglo,$cuentas,$url,ModuloGetURL('system','Menu'));
            }
						else
						{
							$rqs = array();
							foreach($cuentas as $ky => $var)
							{
								$rqs['empresa_id'] = $var['empresa_id'];
								$rqs['departamento'] = $var['departamento'];
								$rqs['centro_utilidad'] = $var['centro_utilidad'];
							}
							$_REQUEST['Cuenta'] = $rqs;
							SessionSetVar("Opciones","1"); 
							$this->Menus();
						}
						return true;
      }
        /**
        *METODO DE FACTURACION
        */
        function SetPtoFacturacion()
        {
          $cadena = str_replace("\\","",$_REQUEST['permiso']['documento_id']);

      if(!SessionIsSetVar("DocumentosCuentas"))
                SessionSetVar("DocumentosCuentas",$cadena);
                //SessionSetVar("DocumentosCuentas",$_REQUEST['permiso']['documento_id']);


                $fct = new app_Facturacion_Permisos();
                $fact = $fct->DatosFactura(SessionGetVar("EmpresaCuentas"),str_replace("'","",SessionGetVar("DocumentosCuentas")));

                $_SESSION['CUENTAS']['EMPRESA'] = SessionGetVar("EmpresaCuentas");
                $_SESSION['CUENTAS']['CENTROUTILIDAD'] = $_REQUEST['permiso']['centro_utilidad'];
                //$_SESSION['CUENTAS']['PREFIJOCONTADO'] = $fact['prefijo_fac_contado'];
                //$_SESSION['CUENTAS']['PREFIJOCREDITO'] = $fact['prefijo_fac_credito'];
                $_SESSION['CUENTAS']['PUNTOFACTURACION'] = $fact['punto_facturacion_id'];
            if(!$this->Menus())
            {
        return false;
      }
      return true;
        }

		/*******************************************************************************
      * Llama la forma del menu de facuracion
      * @access public
      * @return boolean
			********************************************************************************/
      function Menus($opcion)
      {
            if(empty($_SESSION['CUENTAS']['EMPRESA']))
            {
                $rqs = $_REQUEST['Cuenta'];

                $TipoCuenta = $rqs['cuenta_tipo_id'];
                $_SESSION['CUENTAS']['CU'] = $rqs['sw_todos_cu'];
                $_SESSION['CUENTAS']['EMPRESA'] = $rqs['empresa_id'];
                $_SESSION['CUENTAS']['TIPOCUENTA'] = $rqs['cuenta_tipo_id'];
                $_SESSION['CUENTAS']['FACTURACION'] = $rqs['cuenta_filtro_id'];
                $_SESSION['CUENTAS']['CENTROUTILIDAD'] = $rqs['centro_utilidad'];

                SessionSetVar("DepartamentoCuentas",$rqs['departamento']);

                if($_SESSION['CUENTAS']['TIPOCUENTA'] == '02')
                {
                    $this->main();
                    return true;
                }
            }
            $this->FormaMenus();
            return true;
      }
			/*******************************************************************************
			* La funcion main es la principal y donde se llama FormaMetodoBuscar
			* que muestra los diferentes tipos de busquesa de una cuenta
			* @access public
			* @return boolean
			********************************************************************************/
			function main()
			{
					UNSET($_SESSION['CUENTAS']['ARREGLO_NOFACTURADOS']);
					$this->Caja = $_REQUEST['Caja'];
					if($this->Caja)
					{
							SessionDelVar("DepartamentoCuentas");
							$_SESSION['CUENTAS']['CU'] = $_REQUEST['CU'];
							$_SESSION['CUENTAS']['CAJA'] = $_REQUEST['Caja'];
							$_SESSION['CUENTAS']['EMPRESA'] = $_REQUEST['Empresa'];
							$_SESSION['CUENTAS']['SWCUENTAS'] = $_REQUEST['SWCUENTAS'];
							$_SESSION['CUENTAS']['TIPOCUENTA'] = $_REQUEST['TipoCuenta'];
							$_SESSION['CUENTAS']['FACTURACION'] = $_REQUEST['facturacion'];
							$_SESSION['CUENTAS']['CENTROUTILIDAD'] = $_REQUEST['CentroUtilidad'];
					}
					if(!empty($_REQUEST['SWCUENTAS']))
					{
									$_SESSION['CUENTAS']['SWCUENTAS'] = $_REQUEST['SWCUENTAS'];
					}
					unset($_SESSION['ESTADO']);
					//$arreglo=$_REQUEST['arreglo'];
					$_SESSION['CUENTAS']['arreglo'] = $_REQUEST['arreglo'];

					if(!$TipoCuenta)
					{
									$_SESSION['CUENTAS']['TIPOCUENTA']=$arreglo[CuentaTipo];
					}
					if(!$this->FormaMetodoBuscar($arr))
					{
					//if(!$this->FormaMetodoBuscar($Busqueda,$mensaje,$D,$arr,$Departamento,$f,$LinkCargo,$Caja,$arreglo,$TipoCuenta,$new)){
							return false;
					}
					return true;
			}
			/*************************************************************************
			* Busca una cuenta dependiendo del metodo de busqueda que se eligio.
			* @access public
			* @return boolean
			**************************************************************************/
			function BuscarCuenta()
      {
            $CentroU = $_SESSION['CUENTAS']['CENTROUTILIDAD'];
            $Emp = $_SESSION['CUENTAS']['EMPRESA'];
						$this->Caja = $_SESSION['CUENTAS']['CAJA'];
						
            $this->rqs = $_REQUEST;
            $pst['Caja']    = $this->rqs['Caja'];
            $pst['conteo']  = $this->rqs['conteo'];
            $pst['Cuenta']  = $this->rqs['Cuenta'];
            $pst['arreglo'] = $this->rqs['arreglo'];
            $pst['Empresa'] = $this->rqs['Empresa'];
            $pst['Ingreso'] = $this->rqs['Ingreso'];
            $pst['Nombres'] = $this->rqs['Nombres'];
            $pst['Documento']      = $this->rqs['Documento'];
            $pst['Apellidos']      = $this->rqs['Apellidos'];
            $pst['TipoCuenta']     = $this->rqs['TipoCuenta'];
            $pst['Departamento']   = "'".$this->rqs['Departamento']."'";
            $pst['TipoDocumento']  = $this->rqs['TipoDocumento'];
            $pst['CentroUtilidad'] = $this->rqs['CentroUtilidad'];

            $cuentas = array();
            $fct = new Facturacion();
						
						if(empty($this->rqs['Departamento']))
						{
							$cadena = "";
							$dpto = SessionGetVar("DepartamentoCuentas"); 
							foreach($dpto as $key => $val)
								$cadena .= "'".$val['departamento']."' ";
							
							$cadena = trim($cadena);
							$cadena = str_replace(" ",",",$cadena);
							$pst['Departamento'] = $cadena;
						}
						
            if($pst['Cuenta'] || $pst['Ingreso'])
            {
                $cuentas = $fct->ObtenerCuentasXIngreso($pst,$Emp,$pst['Departamento'],$this->rqs['offset']);
            }
            else if($pst['Documento'])
                {
                    if(empty($pst['TipoDocumento']))
                        $this->frmError['MensajeError'] = "SE DEBE INDICAR EL TIPO DE DOCUMENTO";
                    else
                        $cuentas = $fct->ObtenerCuentasXIdPaciente($pst,$Emp,$pst['Departamento'],$this->rqs['offset']);
                }
                else if($pst['Nombres'] || $pst['Apellidos'])
                    {
                        $cuentas = $fct->ObtenerCuentasXNombrePaciente($pst,$Emp,$pst['Departamento'],$this->rqs['offset']);
                    }
                    else
                        {
                            $cuentas = $fct->ObtenerCuentas($pst,$Emp,$pst['Departamento'],$this->rqs['offset'],$this->rqs['conteo']);
                        }

            if($pst && empty($cuentas) && !$this->frmError['MensajeError'])     $this->frmError['MensajeError'] = "LA BUSQUEDA NO ARROJO NINGUN RESULTADO";

            $this->conteo = $fct->conteo;
            $pst['conteo'] = $fct->conteo;
            $this->paginaActual = $fct->paginaActual;
						
						if(empty($this->rqs['Departamento'])) $pst['Departamento'] = "";
						else $pst['Departamento'] = $this->rqs['Departamento'];
						
            $this->action2 = ModuloGetURL('app','Facturacion','user','BuscarCuenta',$pst);
            $this->FormaMetodoBuscar($cuentas);
            return true;
      }
        /**
        * Cuando es llamdo el buscador de cuentas desde el modulo de pagares
        */
        function LlamadoExterno()
        {
                if(empty($_SESSION['CUENTAS']['RETORNO']))
                {
                            $this->error = "CUENTAS ";
                            $this->mensajeDeError = "EL RETORNO DE LA CUENTA ESTA VACIO.";
                            return false;
                }

          if(!$this->FormaMetodoBuscar()){
              return false;
          }
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
                    WHERE fecha_final >= now() and estado=1 and fecha_inicio <= now()
                                    order by plan_descripcion";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            else{
              if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'planes' esta vacia ";
                return false;
              }
                $i=0;
                while (!$result->EOF) {
                $planes[$i]=$result->fields[0].'|/'.$result->fields[1].'|/'.$result->fields[2].'|/'.$result->fields[3];
                $result->MoveNext();
                $i++;
                }
          }
          $result->Close();
          return $planes;
        }

         /**
         *
         */
         function NombreCodigoAgrupamiento($codigo)
         {
                    list($dbconn1) = GetDBconn();
                    $result='';
                    $query1 = "SELECT * FROM cuentas_codigos_agrupamiento
                                        WHERE codigo_agrupamiento_id=$codigo;";
                    $result=$dbconn1->Execute($query1);
                    if ($dbconn1->ErrorNo() != 0) {
                            $this->error = "Error al eliminar en la Base de Datos";
                            $this->mensajeDeError = "Error DB : " . $dbconn1->ErrorMsg();
                            $dbconn->ErrorMsg();
                            return false;
                    }
                    $var=$result->GetRowAssoc(false);
                    $result->Close();
                    return $var;
         }

      /**
      * Tipos de los grupos de cargos existentes
      * @access public
      * @return boolean
      */
      function TiposSolicitud()
      {
          list($dbconn) = GetDBconn();
          $query = "   SELECT grupo_tipo_cargo, descripcion
                      FROM grupos_tipos_cargo
                      WHERE grupo_tipo_cargo!='SYS'
                                        order by descripcion";
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
        /*
      * Llama la forma del menu de facuracion
      * @access public
      * @return boolean
      * @param int caja_id
      */
      function DatosEncabezadoEmpresa($Caja)
      {
      $Ctu = $_SESSION['CUENTAS']['CENTROUTILIDAD'];
            $Emp = $_SESSION['CUENTAS']['EMPRESA'];

            $fct = new Facturacion();
            $datos = array();
            if($Caja)
        $datos = $fct->ObtenerDatosEmpresa($Emp,$Ctu,$Caja,0);
      else
        $datos = $fct->ObtenerDatosEmpresa($Emp,$Ctu,SessionGetVar("DepartamentoCuentas"),1);

            return $datos;
      }
      /**
      * Busca el nombre de un prodesional.
      * @access public
      * @return array
      */
      function GetNombreProfesional($Tipo,$Numero)
      {
          list($dbconn) = GetDBconn();
          $query = " SELECT nombre FROM profesionales
                      WHERE  tipo_id_tercero='$Tipo' AND tercero_id='$Numero'";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
          }
        $resulta->Close();
        return $resulta->fields[0];
      }

//--------------RELIQUIDAR-----------------

      /**
      * Busca el detalle de una cuenta para reliquidarla
      * @access public
      * @return boolean
      * @param int numero de cuenta
      */
      function CuentasDetalleR($Cuenta)
      {
          list($dbconn) = GetDBconn();

          $query = "SELECT
                        a.*,
                        td.descripcion as nombre_cargo,
                        d.servicio as servicio_al_cargar
                    FROM
                        cuentas_detalle as a
                        LEFT JOIN cuentas_codigos_agrupamiento b ON (a.codigo_agrupamiento_id=b.codigo_agrupamiento_id),
                        tarifarios_detalle td,
                        departamentos d
                    WHERE a.sw_liq_manual=0
                    AND (a.codigo_agrupamiento_id <> 1 OR a.codigo_agrupamiento_id IS NULL)
                    AND a.numerodecuenta=$Cuenta
                    AND a.tarifario_id!='SYS'
                    AND b.cuenta_liquidacion_qx_id IS NULL
                    AND td.tarifario_id=a.tarifario_id
                    AND td.cargo=a.cargo
                    AND d.departamento = a.departamento_al_cargar
                    ORDER BY a.fecha_cargo";

          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
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
      * Reliquida una cuenta
      * @access public
      * @return boolean
      * @param int numero de cuenta
      * @param string tipo de documento
      * @param int numero de documento
      * @param string rango
      * @param string plan
      * @param int ingreso
      * @param date fecha
      */
        function Reliquidar($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$paso)
        {
            if(empty($Cuenta) && empty($TipoId) && empty($PacienteId))
            {
                  $TipoId=$_REQUEST['TipoId'];
                  $PacienteId=$_REQUEST['PacienteId'];
                  $Nivel=$_REQUEST['Nivel'];
                  $PlanId=$_REQUEST['PlanId'];
                  $Pieza=$_REQUEST['Pieza'];
                  $Cama=$_REQUEST['Cama'];
                  $Fecha=$_REQUEST['Fecha'];
                  $Ingreso=$_REQUEST['Ingreso'];
                  $Cuenta=$_REQUEST['Cuenta'];
            }
            IncludeLib("tarifario_cargos");
            $var=$this->CuentasDetalleR($Cuenta);
            $x=0;
            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();
            for($i=0; $i<sizeof($var); $i++)
            {
                $Cargo=$var[$i][cargo];
                $des=$this->BuscarDescuentosCuenta($Cuenta,$var[$i][grupo_tipo_cargo]);
                $TarifarioId=$var[$i][tarifario_id];
                $Cantidad=$var[$i][cantidad];
                $transaccion=$var[$i][transaccion];
                $Liq = LiquidarCargoCuenta($Cuenta,$TarifarioId,$Cargo,$Cantidad,$des[descuento_empresa],$des[descuento_paciente],true,true,0,$PlanId,$var[$i]['servicio_al_cargar'],'','');
                if(!empty($Liq))
                {
                    $query =" UPDATE cuentas_detalle SET
                                     precio=".$Liq[precio_plan].",
                                     valor_cargo=".$Liq[valor_cargo].",
                                     valor_nocubierto=".$Liq[valor_no_cubierto].",
                                     valor_cubierto=".$Liq[valor_cubierto].",
                                     valor_descuento_empresa=".$Liq[valor_descuento_empresa].",
                                     valor_descuento_paciente=".$Liq[valor_descuento_paciente].",
                                     porcentaje_gravamen=".$Liq[porcentaje_gravamen].",
                                     sw_cuota_paciente=".$Liq[sw_cuota_paciente].",
                                     sw_cuota_moderadora=".$Liq[sw_cuota_moderadora].",
                                     facturado='".$Liq[facturado]."'
                              WHERE  numerodecuenta=$Cuenta and cargo='$Cargo' and tarifario_id='$TarifarioId' AND transaccion='$transaccion'";
                    $sql .= "<br>".$query;
                    $result=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "Error UPDATE cuentas_detalle";
                        $this->mensajeDeError = "Error DB 1: " . $dbconn->ErrorMsg()."<br> $sql";
                        $dbconn->RollbackTrans();
                        return false;
                    }
                    $result->Close();
                    $x++;
                }
            }//fin for

            $var=$this->BuscarInsumosReliquidar($_REQUEST['Cuenta']);
            for($i=0; $i<sizeof($var); $i++)
            {
                $Liq=LiquidarIyM($_REQUEST['Cuenta'] ,$var[$i]['codigo_producto'] ,$var[$i]['cantidad'] ,0 ,0 ,true ,true ,NULL ,$_REQUEST['PlanId'],false,$var[$i]['departamento_al_cargar'],$_SESSION['CUENTAS']['EMPRESA'],$var[$i]['evolucion_id']);
                            if($var[$i]['tipo_mov']=='DIMD'){
                                $valor_cargo=($Liq['valor_cargo']*-1);
                                $valor_nocubierto=($Liq['valor_nocubierto']*-1);
                                $valor_cubierto=($Liq['valor_cubierto']*-1);
                            }else{
                                $valor_cargo=$Liq['valor_cargo'];
                                $valor_nocubierto=$Liq['valor_nocubierto'];
                                $valor_cubierto=$Liq['valor_cubierto'];
                            }
                $query ="UPDATE cuentas_detalle SET
                                precio=".$Liq[precio_plan].",
                                valor_cargo='".$valor_cargo."',
                                valor_nocubierto='".$valor_nocubierto."',
                                valor_cubierto='".$valor_cubierto."',
                                valor_descuento_empresa=".$Liq[valor_descuento_empresa].",
                                valor_descuento_paciente=".$Liq[valor_descuento_paciente].",
                                porcentaje_gravamen=".$Liq[porcentaje_gravamen].",
                                sw_cuota_paciente=".$Liq[sw_cuota_paciente].",
                                sw_cuota_moderadora=".$Liq[sw_cuota_moderadora].",
                                facturado='".$Liq[facturado]."'
                        WHERE   numerodecuenta=$Cuenta and consecutivo=".$var[$i][consecutivo]."";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error UPDATE cuentas_detalle";
                    $this->mensajeDeError = "Error DB :2 " . $dbconn->ErrorMsg()."<br>$sql";
                    $dbconn->RollbackTrans();
                    return false;
                }
                $result->Close();
                $x++;
            }
            $dbconn->CommitTrans();

            if(empty($paso))
            {       //no es desde division
                $mensaje='Se reliquidaron '.$x.' items entre cargos, insumos y medicamentos de la Cuenta No. '.$Cuenta;
                $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$_REQUEST['Transaccion'],'Cuenta'=>$Cuenta,'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso'],'Estado'=>$Estado));
                if(!$this-> FormaMensaje($mensaje,'RELIQUIDAR CUENTA No. '.$Cuenta,$accion,''))
                {
                    return false;
                }
                return true;
            }
            else
            {
                return true;
            }
        }

        function BuscarInsumosReliquidar($cuenta)
        {
          list($dbconn) = GetDBconn();
           $query = "
                        SELECT A.*,e.solicitud_id, f.evolucion_id
                        FROM
                        (
                            SELECT
                            a.departamento,
                            a.cantidad,
                            a.consecutivo,
                            b.bodegas_doc_id,
                            b.numeracion,
                            c.codigo_producto,
                            a.fecha_cargo,a.cargo as tipo_mov,
                            x.precio_venta,
                            a.departamento_al_cargar
                            FROM
                            cuentas_detalle as a, cuentas_codigos_agrupamiento as b,
                            bodegas_documentos_d as c,inventarios x
                            WHERE
                            a.sw_liq_manual=0
                            AND a.numerodecuenta=$cuenta
                            AND a.consecutivo is not null
                            AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id
                            AND b.bodegas_doc_id=c.bodegas_doc_id
                            AND b.numeracion=c.numeracion
                            AND a.consecutivo=c.consecutivo
                            AND c.codigo_producto=x.codigo_producto
                            AND x.empresa_id=a.empresa_id
                            AND a.sw_liq_manual='0'
                            AND b.cuenta_liquidacion_qx_id IS NULL
                        ) as A LEFT JOIN hc_solicitudes_medicamentos as e ON(A.numeracion=e.numeracion and A.bodegas_doc_id=e.bodegas_doc_id)
                            LEFT JOIN hc_solicitudes_medicamentos_d as f ON (e.solicitud_id=f.solicitud_id)
                        order by A.fecha_cargo
                    ";

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
      * Reliquida medicamentos
      * @access public
      * @return boolean
      */
      function ReliquidarMedicamentos()
      {
                $Cuenta=$_REQUEST['Cuenta'];
                $Pieza=$_REQUEST['Pieza'];
                $Cama=$_REQUEST['Cama'];

                IncludeLib("tarifario_cargos");
                $var=$this->BuscarInsumosReliquidar($_REQUEST['Cuenta']);

                $x=0;
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          for($i=0; $i<sizeof($var); $i++)
          {
                            $Liq=LiquidarIyM($_REQUEST['Cuenta'] ,$var[$i]['codigo_producto'] ,$var[$i]['cantidad'] ,0 ,0 ,true ,true ,NULL,$_REQUEST['PlanId'],false,$var[$i]['departamento_al_cargar'],$_SESSION['CUENTAS']['EMPRESA'],$var[$i]['evolucion_id']);
                                                    if($var[$i]['tipo_mov']=='DIMD'){
                                                        $valor_cargo=($Liq['valor_cargo']*-1);
                                                        $valor_nocubierto=($Liq['valor_nocubierto']*-1);
                                                        $valor_cubierto=($Liq['valor_cubierto']*-1);
                                                    }else{
                                                        $valor_cargo=$Liq['valor_cargo'];
                                                        $valor_nocubierto=$Liq['valor_nocubierto'];
                                                        $valor_cubierto=$Liq['valor_cubierto'];
                                                    }
                                                    $query =" UPDATE cuentas_detalle SET
                                                                                precio=".$Liq[precio_plan].",
                                                                                valor_cargo='".$valor_cargo."',
                                                                                valor_nocubierto='".$valor_nocubierto."',
                                                                                valor_cubierto='".$valor_cubierto."',
                                                                                valor_descuento_empresa=".$Liq[valor_descuento_empresa].",
                                                                                valor_descuento_paciente=".$Liq[valor_descuento_paciente].",
                                                                                porcentaje_gravamen=".$Liq[porcentaje_gravamen].",
                                                                                sw_cuota_paciente=".$Liq[sw_cuota_paciente].",
                                                                                sw_cuota_moderadora=".$Liq[sw_cuota_moderadora].",
                                                                                facturado='".$Liq[facturado]."'
                                                WHERE numerodecuenta=$Cuenta and consecutivo=".$var[$i][consecutivo]."";
                            $result=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error UPDATE cuentas_detalle";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                                    return false;
                            }
                            $result->Close();
                            $x++;
                }
          $dbconn->CommitTrans();
                $mensaje='Los Medicamentos e Insumos de la Cuenta No. '.$Cuenta.' se reliquidaron.';
                $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$_REQUEST['Transaccion'],'Cuenta'=>$Cuenta,'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso'],'Estado'=>$Estado));
                if(!$this-> FormaMensaje($mensaje,'RELIQUIDAR MEDICAMENTOS No. '.$Cuenta,$accion,'')){
                return false;
                }
                return true;
      }

        function ReliquidarCargos()
        {
          IncludeLib("tarifario_cargos");
          $var=$this->CuentasDetalleR($_REQUEST['Cuenta']);
          $x=0;
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          for($i=0; $i<sizeof($var); $i++)
          {
                $Cargo=$var[$i][cargo];
                $des=$this->BuscarDescuentosCuenta($_REQUEST['Cuenta'],$var[$i][grupo_tipo_cargo]);
                $TarifarioId=$var[$i][tarifario_id];
                $Cantidad=$var[$i][cantidad];
                $transaccion=$var[$i][transaccion];

                $Liq=LiquidarCargoCuenta($_REQUEST['Cuenta'],$TarifarioId,$Cargo,$Cantidad,$des[descuento_empresa],$des[descuento_paciente],true,true,0,$PlanId,$var[$i]['servicio_al_cargar'],'','');
                if(!is_array($Liq)){
                    $mensaje='Verifique la contratacion del cargo '.$var[$i]['nombre_cargo'].' de la Cuenta No. '.$_REQUEST['Cuenta'];
                    $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$_REQUEST['Transaccion'],'Cuenta'=>$_REQUEST['Cuenta'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso'],'Estado'=>$Estado));
                    if(!$this-> FormaMensaje($mensaje,'RELIQUIDAR CUENTA No. '.$Cuenta,$accion,'')){
                    return false;
                    }
                    return true;
                }

                $query ="UPDATE cuentas_detalle SET
                                precio=".$Liq[precio_plan].",
                                valor_cargo=".$Liq[valor_cargo].",
                                valor_nocubierto=".$Liq[valor_no_cubierto].",
                                valor_cubierto=".$Liq[valor_cubierto].",
                                valor_descuento_empresa=".$Liq[valor_descuento_empresa].",
                                valor_descuento_paciente=".$Liq[valor_descuento_paciente].",
                                porcentaje_gravamen=".$Liq[porcentaje_gravamen].",
                                sw_cuota_paciente=".$Liq[sw_cuota_paciente].",
                                sw_cuota_moderadora=".$Liq[sw_cuota_moderadora].",
                                facturado='".$Liq[facturado]."'
                            WHERE numerodecuenta=".$_REQUEST['Cuenta']." and cargo='$Cargo' and tarifario_id='$TarifarioId' AND transaccion='$transaccion'";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error UPDATE cuentas_detalle";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
                $result->Close();
                $x++;
          }//fin for
          $dbconn->CommitTrans();
          $mensaje='Se reliquidaron '.$x.' cargos de la Cuenta No. '.$_REQUEST['Cuenta'];
                $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$_REQUEST['Transaccion'],'Cuenta'=>$_REQUEST['Cuenta'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso'],'Estado'=>$Estado));
          if(!$this-> FormaMensaje($mensaje,'RELIQUIDAR CUENTA No. '.$Cuenta,$accion,'')){
            return false;
          }
          return true;

        }

      /**
      * Actualiza e inserta descuentos que aplican a la cuenta para pacientes y empresas
      * @access public
      * @return boolean
      */
      function GuardarDescuentos()
      {
          $Cuenta=$_REQUEST['Cuenta'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];

          $f=0;
          foreach($_REQUEST as $k => $v)
          {
              if($f==0)
              {
                if(substr_count($k,'DesEmp'))
                {
                  if(!empty($v))
                  { $f=1; }
                }
                if(substr_count($k,'DesPac'))
                {
                  if(!empty($v))
                  { $f=1; }
                }
              }
          }

          if($f==0)
          {
              $this->frmError["MensajeError"]="Debe asignar algun tipo de Descuento.";
              $this->FormaDescuentos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha);
              return true;
          }
          else
          {
                list($dbconn) = GetDBconn();
                foreach($_REQUEST as $k => $v)
                {
                      if(substr_count($k,'DesEmp'))
                      {
                          $f=explode(',',$k);
                          $x=$_REQUEST['DesPac,'.$f[1].','.$f[2]];
                          if(!$v) {  $v=0;  }
                          if(!$x) {  $x=0;  }

                          $query = "select descuento_empresa,descuento_paciente
                                    from cuentas_descuentos
                                    where numerodecuenta=$Cuenta and grupo_tipo_cargo='$f[2]'";
                          $result=$dbconn->Execute($query);
                          if(!$result->EOF)
                          {
                                $query = "UPDATE cuentas_descuentos SET descuento_empresa=$v,
                                                                        descuento_paciente=$x
                                          WHERE numerodecuenta=$Cuenta and grupo_tipo_cargo='$f[2]'";
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error UPDATE cuentas_descuentos";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                                }
                          }
                          else
                          {
                            if($x!=0 || $v!=0)
                            {
                                $query = "INSERT INTO  cuentas_descuentos(numerodecuenta,
                                                                          grupo_tipo_cargo,
                                                                          descuento_empresa,
                                                                          descuento_paciente)
                                                VALUES($Cuenta,'$f[2]',$v,$x)";
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error INTO  cuentas_descuentos";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                                }
                            }
                          }
                      }
                }
                $this->Reliquidar($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha);
                return true;
          }
      }

//----------FIN RELIQUIDAR--------------------------------
      /**
      * Llama la formacuenta para mostrar el detalle de los medicamentos.
      * @access public
      * @return boolean
      */
      function LlamaForma()
      {
          $Transaccion=$_REQUEST['Transaccion'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Pieza=$_REQUEST['Pieza'];
          $Cama=$_REQUEST['Cama'];
          $Fecha=$_REQUEST['Fecha'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Cuenta=$_REQUEST['Cuenta'];

          list($dbconn) = GetDBconn();
          $query = "select b.empresa_id, b.codigo_producto, e.descripcion,b.cantidad,
                    c.total_costo, h.valor_cargo
                    from bodegas_documentos_d b, bodegas_documentos as c, inv_conceptos as d,
                    inventarios_productos as e, bodegas_documentos_d_cobertura as h
                    where c.transaccion=$Transaccion and b.documento=c.documento and
                    b.empresa_id=c.empresa_id and b.centro_utilidad=c.centro_utilidad and
                    b.bodega=c.bodega and b.prefijo=c.prefijo and c.concepto_inv=d.concepto_inv
                    and d.tipo_mov='E' and b.codigo_producto=e.codigo_producto
                    and h.consecutivo_detalle=b.consecutivo";
          $resulta=$dbconn->Execute($query);
          $i=0;
          while(!$resulta->EOF)
          {
              $vars[$i]=$resulta->GetRowAssoc($ToUpper = false);
              $resulta->MoveNext();
              $i++;
          }
          $resulta->Close();

          if(!$this->FormaCuenta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$vars,$Transaccion,$mensaje,$Dev))
          {
            return false;
          }
        return true;
      }


      /**
      * Llama la formacuenta para mostrar el detalle de los medicamentos.
      * @access public
      * @return boolean
      */
      function LlamaFormaDevolucionMedicamentos()
      {
          $Transaccion=$_REQUEST['Transaccion'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Pieza=$_REQUEST['Pieza'];
          $Cama=$_REQUEST['Cama'];
          $Fecha=$_REQUEST['Fecha'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Cuenta=$_REQUEST['Cuenta'];

          list($dbconn) = GetDBconn();
          $query = "select b.empresa_id, b.codigo_producto, e.descripcion,b.cantidad,
                    c.total_costo, h.valor_cargo
                    from bodegas_documentos_d b, bodegas_documentos as c, inv_conceptos as d,
                    inventarios_productos as e, bodegas_documentos_d_cobertura as h
                    where c.transaccion=$Transaccion and b.documento=c.documento and
                    b.empresa_id=c.empresa_id and b.centro_utilidad=c.centro_utilidad and
                    b.bodega=c.bodega and b.prefijo=c.prefijo and c.concepto_inv=d.concepto_inv
                    and d.tipo_mov='I'  and b.codigo_producto=e.codigo_producto
                    nd h.consecutivo_detalle=b.consecutivo";
          $resulta=$dbconn->Execute($query);
          while(!$resulta->EOF)
          {
              $Dev[]=$resulta->GetRowAssoc($ToUpper = false);
              $resulta->MoveNext();
          }
          $resulta->Close();
          if(!$this->FormaCuenta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$vars,$Transaccion,$mensaje,$Dev,$Estado))
          {
            return false;
          }
          return true;
      }

      /**
      * Buscar los totales de la cuenta.
      * @access public
      * @return array
      * @param int numero de la cuenta
      */
      function BuscarTotales($Cuenta)
      {
            $EmpresaId=$_SESSION['CUENTAS']['EMPRESA'];
            $CentroU=$_SESSION['CUENTAS']['CENTROUTILIDAD'];
            if($CentroU)
            { $CU="and centro_utilidad='$CentroU'"; }

            list($dbconn) = GetDBconn();
                    $dbconn -> debug = false;
            $query="
                SELECT
                    a.total_cuenta,
                    a.valor_cuota_paciente,
                    a.valor_nocubierto,
                    a.valor_cubierto,
                    a.valor_descuento_empresa,
                    a.valor_descuento_paciente,
                    a.valor_total_paciente,
                    a.valor_cuota_paciente,
                    a.valor_cuota_moderadora,
                    a.valor_total_empresa,
                    a.valor_cuota_moderadora,
                    a.gravamen_valor_cubierto,
                    a.gravamen_valor_nocubierto,
                    (a.valor_total_paciente - (a.abono_efectivo + a.abono_cheque +
                                    a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo,
                    a.abono_letras,
                    (a.abono_efectivo + a.abono_cheque +
                                    a.abono_tarjetas + a.abono_chequespf + a.abono_letras) as abono,
                    a.ingreso, a.plan_id
             FROM
                    cuentas as a
                WHERE
                a.numerodecuenta='$Cuenta'
                AND a.empresa_id='$EmpresaId'
                $CU";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $var=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $var;
      }

      /**
      * Busca los totales de un cargo de cirugia
      * @access public
      * @return array
      * @param int numero de cuenta
      * @param int numero de la transaccion
      */
        function TotalesCirugia($Cuenta,$Transaccion)
        {
            list($dbconn) = GetDBconn();
            $query = "select valor_cubierto, valor_nocubierto, valor_cuota_paciente, valor_cargo
                      from cuentas_detalle
                      where numerodecuenta=$Cuenta and transaccion=$Transaccion";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $var = $result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $var;
        }

      /**
      * Calcula la cantidad de cargos de una cuenta.
      * @access public
      * @return int
      * @param int numero de la cuenta
      */
      function TotalCargos($Cuenta)
      {
            list($dbconn) = GetDBconn();
            $query="SELECT sum(cantidad)
                    FROM cuentas_detalle WHERE numerodecuenta='$Cuenta'";
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
      * Forma un vector con los tipo de identificacion de los terceros.
      * @access public
      * @return array
      */
      function tipo_id_terceros()
      {
            list($dbconn) = GetDBconn();
            $query="SELECT tipo_id_tercero,descripcion FROM tipo_id_terceros  ORDER BY indice_de_orden";
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
      * Llama la forma que muestra los totales de la cuenta.
      * @access public
      * @return boolean
      */
      function LlamaTotalesCuenta()
      {
          $Cuenta=$_REQUEST['Cuenta'];
          $sw=$_REQUEST['link_modi'];//valor para no mostrar los enlaces modificar desde facturacion
          $this->TotalesCuenta($Cuenta,$sw);
          return true;
      }

      /**
      * Llama la forma FormaDetalleCuentaPV.
      * @access public
      * @return boolean
      */
      function LlamaFormaDetalleCuentaPV()
      {
          $NumCuenta=$_REQUEST['NumCuenta'];
          $TipoCuenta=$_REQUEST['TipoCuenta'];
          if(!$this->FormaDetalleCuentaPV($NumCuenta,$TipoCuenta)){
            return false;
          }
          return true;
      }

      /**
      * Busca el detalle de las devoluciones de una cuenta PV.
      * @access public
      * @return boolean
      * @param int numero de la cuenta
      * @param int si existe busca el sum(total_venta)
      */
      function DetalleCuentasPVDevoluciones($NumCuenta,$sum)
      {
          if($sum)
          {   $vars="sum(b.total_venta)";  }
          else
          {  $vars="b.empresa_id, b.codigo_producto, e.descripcion, b.precio_venta, b.despachada, b.gravamen, b.total_venta";  }
          list($dbconn) = GetDBconn();
          $query = "select $vars
                    from det_cuenta_pv a, bodegas_documentos_d b, bodegas_documentos as c,
                    bodegas_documentos_conceptos as d, inventarios as e
                    where a.cuenta_pv='$NumCuenta' and a.consecutivo=b.consecutivo and
                    b.documento=c.documento and b.empresa_id=c.empresa_id and
                    b.centro_utilidad=c.centro_utilidad and b.bodega=c.bodega and
                    b.prefijo=c.prefijo and c.concepto_inv=d.concepto_inv and d.tipo_mov='I' and
                    b.empresa_id=e.empresa_id and b.codigo_producto=e.codigo_producto";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
          }

          if(!$sum)
          {
              while(!$result->EOF)
              {
                  $var[]=$result->GetRowAssoc($ToUpper = false);
                  $result->MoveNext();
              }
          }
          else
          {  $var=$result->GetRowAssoc($ToUpper = false);  }
        $result->Close();
        return $var;
      }

      /**
      * Busca el detalle de los  una cuenta PV.
      * @access public
      * @return boolean
      */
      function DetalleCuentasPV($NumCuenta,$sum)
      {
          if($sum)
          {   $vars="sum(b.total_venta)";  }
          else
          {   $vars="b.empresa_id, b.codigo_producto, e.descripcion, b.precio_venta, b.despachada, b.gravamen, b.total_venta";   }
          list($dbconn) = GetDBconn();
          $query = "select $vars
                    from det_cuenta_pv a, bodegas_documentos_d b, bodegas_documentos as c, bodegas_documentos_conceptos as d, inventarios as e
                    where a.cuenta_pv='$NumCuenta' and a.consecutivo=b.consecutivo and b.documento=c.documento and b.empresa_id=c.empresa_id and
                    b.centro_utilidad=c.centro_utilidad and b.bodega=c.bodega and b.prefijo=c.prefijo
                    and c.concepto_inv=d.concepto_inv and d.tipo_mov='E' and b.empresa_id=e.empresa_id and b.codigo_producto=e.codigo_producto";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
          }

          if(!$sum)
          {
              while(!$result->EOF)
              {
                  $var[]=$result->GetRowAssoc($ToUpper = false);
                  $result->MoveNext();
              }
          }
          else
          {  $var=$result->GetRowAssoc($ToUpper = false);  }
        $result->Close();
        return $var;
      }

      /**
      * Llama la form LlamaFormaMensaje.
      * @access public
      * @return boolean
      */
      function LlamaFormaMensaje()
      {
            $mensaje=$_REQUEST['mensaje'];
            $titulo=$_REQUEST['titulo'];
            $accion=$_REQUEST['accion'];
            $boton=$_REQUEST['boton'];

            if(!$this-> FormaMensaje($mensaje,$titulo,$accion,$boton)){
            return false;
            }
            return true;
      }

      /**
      * Llama la forma para buscar  o crear los descuentos
      * @access public
      * @return boolean
      */
       function CrearDescuentos()
       {
          $Cuenta=$_REQUEST['Cuenta'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];

          $this->FormaDescuentos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha);
          return true;
       }

      /**
      * Busca los grupos de cargos a los que se les puede aplicar descuentos
      * @access public
      * @return array
      */
       function BuscarSolicitudesDescuentos()
       {
            list($dbconn) = GetDBconn();
            $query="select * from grupos_tipos_cargo where grupo_tipo_cargo!='SYS'";
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
      * Busca los descuentos que tiene un grupo de cargo especifico en la cuenta
      * @access public
      * @return boolean
      * @param int numero de cuenta
      * @param string tipo del grupo cargo
      */
       function BuscarDescuentosCuenta($Cuenta,$Tipo)
       {
            list($dbconn) = GetDBconn();
            $query="select * from cuentas_descuentos
                    where numerodecuenta=$Cuenta and grupo_tipo_cargo='$Tipo'";
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
       * Llama la forma para modificar un cargo de la cuenta en tmp_cuenta_detalles.
       * @ access public
       * @ return boolean
       */
       function LlamaFormaModificarCargoTmp()
       {
	
                    if(!$this->FormaCargos($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Ingreso'],$_REQUEST['Fecha'],$mensaje,$_REQUEST['Datos'],$var,$Ayuda,$C,$_REQUEST['codigo'])){
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
                    IncludeLib("tarifario_cargos");
            $Departamento=$_REQUEST['Departamento'];
            //$ValorNo=$_REQUEST['ValorNo'];
            //$ValorPac=$_REQUEST['ValorPac'];
            //$Precio=$_REQUEST['Precio'];
            $Cargo=$_REQUEST['CargoTarifario'];
            $Cantidad=$_REQUEST['Cantidad'];
            //$ValEmpresa=$_REQUEST['ValorEmp'];
            $TarifarioId=$_REQUEST['TarifarioId'];
            //$GrupoTarifario=$_REQUEST['GrupoTarifario'];
            //$SubGrupoTarifario=$_REQUEST['SubGrupoTarifario'];
            //$Gravamen=$_REQUEST['Gravamen'];
            $Cuenta=$_REQUEST['Cuenta'];
            $Nivel=$_REQUEST['Nivel'];
            $PlanId=$_REQUEST['PlanId'];
            $Ingreso=$_REQUEST['Ingreso'];
            $FechaCargo=$_REQUEST['FechaCargo'];
            $f=explode('/',$FechaCargo);
            $FechaCargo=$f[2].'-'.$f[1].'-'.$f[0];
            $Fecha=$_REQUEST['Fecha'];
            $TipoId=$_REQUEST['TipoId'];
            $PacienteId=$_REQUEST['PacienteId'];
            $Transaccion=$_REQUEST['Transaccion'];
            //$Consecutivo=$_REQUEST['Consecutivo'];
            //$ValorCargo=$_REQUEST['ValorCargo'];
            //$FechaCargo=$_REQUEST['FechaCargo'];
            //$Cons=$_REQUEST['Cons'];
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
                if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,'Modi',$Cobertura)){
                  return false;
                }
                return true;
            }
            $f = (int) $Cantidad;
            $y = $Cantidad - $f;
            if($y != 0){
                if($y != 0){ $this->frmError["Cantidad"]=1; }
                $mensaje='La Cantidad debe ser entera.';
                if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,'Modi',$Cobertura)){
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
//----------------------------esto es para los calculos-------------------------
            $Liq=LiquidarCargoCuenta($Cuenta,$TarifarioId,$Cargo,$Cantidad,0,0,true,true,0,$PlanId,'','','',true);
            $DescuentoEmp=$Liq[valor_descuento_empresa];
            $DescuentoPac=$Liq[valor_descuento_paciente];
            $Precio=$Liq[precio_plan];
            $ValorCargo=$Liq[valor_cargo];
            $ValorNo=$Liq[valor_no_cubierto];
            $ValorCub=$Liq[valor_cubierto];
                    if(empty($Liq[codigo_agrupamiento_id]))
                    {  $Liq[codigo_agrupamiento_id]='NULL';}
//-------------------------------------------------------------------------------

                    $query =" UPDATE tmp_cuentas_detalle SET
                                          departamento='$Departamento',
                                          tarifario_id='$TarifarioId',
                                          cargo='$Cargo',
                                          cantidad=$Cantidad,
                                          precio=$Precio,
                                          valor_cargo=$ValorCargo,
                                          valor_nocubierto=$ValorNo,
                                          valor_cubierto=$ValorCub,
                                          fecha_cargo='$FechaCargo',
                                          usuario_id=$SystemId,
                                          fecha_registro='now()',
                                          valor_descuento_empresa=$DescuentoEmp,
                                          valor_descuento_paciente=$DescuentoPac,
                                                                                porcentaje_gravamen=".$Liq[porcentaje_gravamen].",
                                                                                sw_cuota_paciente=".$Liq[sw_cuota_paciente].",
                                                                                sw_cuota_moderadora=".$Liq[sw_cuota_moderadora].",
                                                                                codigo_agrupamiento_id=".$Liq[codigo_agrupamiento_id].",
                                                                                servicio_cargo='$Servicio'
                        WHERE transaccion=$Transaccion AND numerodecuenta=$Cuenta";
              $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error UPDATE tmp_cuentas_detalle";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
              }

                        $query = "SELECT * FROM tmp_cuentas_detalle_profesionales
                                            WHERE transaccion=$Transaccion";
                        $resul=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error UPDATE tmp_cuentas_detalle";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
                        if(!$resul->EOF)
                        {
                                    //PROFESIONAL(hay algo en el combo)
                                    if(!empty($_REQUEST['MedInt']))
                                    {
                                            $p=explode('||',$_REQUEST['MedInt']);
                                            $query = "UPDATE tmp_cuentas_detalle_profesionales SET
                                                                tipo_tercero_id='".$p[0]."',
                                                                tercero_id='".$p[1]."'
                                                                WHERE transaccion=$Transaccion";
                                            $result = $dbconn->Execute($query);
                                            if($dbconn->ErrorNo() != 0) {
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                            }
                                    }
                                    else
                                    {
                                            $query = "DELETE FROM tmp_cuentas_detalle_profesionales
                                                                WHERE transaccion=$Transaccion";
                                            $result = $dbconn->Execute($query);
                                            if($dbconn->ErrorNo() != 0) {
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                            }
                                    }
                        }
                        else
                        {
                                    //PROFESIONAL(anted no habian elegido)
                                    if(!empty($_REQUEST['MedInt']))
                                    {
                                            $p=explode('||',$_REQUEST['MedInt']);
                                            $query = "INSERT INTO tmp_cuentas_detalle_profesionales(
                                                                                                                                            transaccion,
                                                                                                                                            tipo_tercero_id,
                                                                                                                                            tercero_id)
                                                                VALUES($Transaccion,'".$p[0]."','".$p[1]."')";
                                            $result = $dbconn->Execute($query);
                                            if($dbconn->ErrorNo() != 0) {
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                            }
                                    }
                        }
              $mensaje='El cargo se modifico';
              if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
                return false;
              }
              return true;
            //}
       }

      
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

      /**
      * Busca en la base de datos si la cantidad se puede cambiar(0).
      * @access public
      * @return array
      * @param string tarifario_id
      * @param string grupo
      * @param string subgrupo
      */
      function SWCantidad($TarifarioId,$Cargo)
      {
            list($dbconn) = GetDBconn();
            $sql="SELECT sw_cantidad FROM tarifarios_detalle WHERE tarifario_id='$Tarifario_id' AND cargo='$Cargo'";
            $result=$dbconn->Execute($sql);

            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            return $result->fields[0];
      }


      /**
      * Busca en la base de datos el valor que debe pagar el paciente.
      * @access public
      * @return array
      * @param string nivel
      * @param string plan_id
      */
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

      /**
      * Busca que el paciente no tenga otra cuenta activa para poder activar esta inactiva
      * @access public
      * @return boolean
      */
      function BuscarCuentaParaActivar()
      {
            $Transaccion=$_REQUEST['Transaccion'];
            $TipoId=$_REQUEST['TipoId'];
            $PacienteId=$_REQUEST['PacienteId'];
            $Nivel=$_REQUEST['Nivel'];
            $PlanId=$_REQUEST['PlanId'];
            $Pieza=$_REQUEST['Pieza'];
            $Cama=$_REQUEST['Cama'];
            $Fecha=$_REQUEST['Fecha'];
            $Ingreso=$_REQUEST['Ingreso'];
            $Cuenta=$_REQUEST['Cuenta'];
            $Estado=$_REQUEST['Estado'];
            $Ingreso=$_REQUEST['Ingreso'];

            list($dbconn) = GetDBconn();
            $query = "select a.numerodecuenta
                      from cuentas as a, ingresos as b
                      where b.tipo_id_paciente='$TipoId' and  paciente_id='$PacienteId'
                      and a.ingreso=b.ingreso and a.estado=1";
            $result=$dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            $result->Close();

            //se puede activar la cuenta
            if($result->EOF)
            {
                $mensaje='Esta seguro que desea Activar la Cuenta No. '.$Cuenta;
                $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
                $c='app';
                $m='Facturacion';
                $me='ActivarCuenta';
                $me2='Cuenta';
                $Titulo='ACTIVAR CUENTA No. '.$Cuenta;
                $boton1='ACEPTAR';
                $boton2='CANCELAR';

                $this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
                return true;
            }
            else
            {
                $mensaje='No se puede Activar la Cuenta No. '.$Cuenta.' el paciente ya tiene una Cuenta Abierta.';
                $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
                if(!$this-> FormaMensaje($mensaje,'ACTIVAR CUENTA No. '.$Cuenta,$accion,'')){
                return false;
                }
                return true;
            }
      }

      /**
      * Activa la cuenta
      * @access public
      * @return boolean
      */
      function ActivarCuenta($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha)
      {
          if(empty($PlanId) AND empty($Cuenta))
          {
            $Cuenta=$_REQUEST['Cuenta'];
            $Transaccion=$_REQUEST['Transaccion'];
            $TipoId=$_REQUEST['TipoId'];
            $PacienteId=$_REQUEST['PacienteId'];
            $Nivel=$_REQUEST['Nivel'];
            $PlanId=$_REQUEST['PlanId'];
            $Pieza=$_REQUEST['Pieza'];
            $Cama=$_REQUEST['Cama'];
            $Fecha=$_REQUEST['Fecha'];
            $Ingreso=$_REQUEST['Ingreso'];
            $Estado=$_REQUEST['Estado'];
            $Ingreso=$_REQUEST['Ingreso'];
          }

            list($dbconn) = GetDBconn();
            $query = "select a.numerodecuenta
                      from cuentas as a, ingresos as b
                      where b.tipo_id_paciente='$TipoId' and  paciente_id='$PacienteId'
                      and a.ingreso=b.ingreso and a.estado=1";
            $result=$dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            $result->Close();
            if(!$result->EOF)
            {
                $mensaje='No se puede Activar la Cuenta No. '.$Cuenta.' el paciente ya tiene una Cuenta Abierta.';
                $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
                if(!$this-> FormaMensaje($mensaje,'ACTIVAR CUENTA No. '.$Cuenta,$accion,'')){
                return false;
                }
                return true;
            }

            $query = "UPDATE cuentas SET estado='1' WHERE numerodecuenta=$Cuenta";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            else
            {
                                    $query = "SELECT a.numerodecuenta, a.ingreso, a.plan_id, b.tipo_id_paciente,
                                                        b.paciente_id, a.fecha_registro
                                                        FROM cuentas as a, ingresos as b
                                                        WHERE a.numerodecuenta=$Cuenta and a.ingreso=b.ingreso";
                                    $result=$dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                                    }

                                    $var= $result->GetRowAssoc($ToUpper = false);
                                    $result->Close();

                    $mensaje='La Cuenta No. '.$Cuenta.' ha sido Activada.';
                    $_SESSION['ESTADO']='A';
                    $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$var['numerodecuenta'],'TipoId'=>$var['tipo_id_paciente'],'PacienteId'=>$var['paciente_id'],'PlanId'=>$var['plan_id'],'Fecha'=>$var['fecha_registro'],'Ingreso'=>$var['ingreso']));
                    if(!$this-> FormaMensaje($mensaje,'ACTIVAR CUENTA No. '.$Cuenta,$accion,'')){
                    return false;
                    }
                    return true;
            }
      }

      /**
      * Cuenta la cantidad de cargos que tiene una cuenta
      * @access public
      * @return boolean
      * @param int numero de cuenta
      */
      function BuscarCantidadCargosCuenta($Cuenta)
      {
            list($dbconn) = GetDBconn();
            $query = "select a.numerodecuenta
                      from cuentas as a, ingresos as b
                      where b.tipo_id_paciente='$TipoId' and  paciente_id='$PacienteId'
                      and a.ingreso=b.ingreso and a.estado=1";
            $result=$dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            $result->Close();
      }

      /**
      * Anula una cuenta(5) una cuenta
      * @access public
      * @return boolean
      */
      function AnularCuenta()
      {
                    $Transaccion=$_REQUEST['Transaccion'];
                    $TipoId=$_REQUEST['TipoId'];
                    $PacienteId=$_REQUEST['PacienteId'];
                    $Nivel=$_REQUEST['Nivel'];
                    $PlanId=$_REQUEST['PlanId'];
                    $Pieza=$_REQUEST['Pieza'];
                    $Cama=$_REQUEST['Cama'];
                    $Fecha=$_REQUEST['Fecha'];
                    $Ingreso=$_REQUEST['Ingreso'];
                    $Cuenta=$_REQUEST['Cuenta'];
                    $Estado=$_REQUEST['Estado'];
                    $Ingreso=$_REQUEST['Ingreso'];

            list($dbconn) = GetDBconn();
            $query = "select count(*) from cuentas_detalle where numerodecuenta=$Cuenta";
            $result=$dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }

                    $query="SELECT * FROM movimientos_habitacion
                                    WHERE numerodecuenta=$Cuenta and fecha_egreso IS NOT NULL";
                    $results = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }

            if($result->fields[0] < 1 AND !$result->EOF)
            { //se puede anular
                                list($dbconn) = GetDBconn();
                                $query = "SELECT count(*) FROM cuentas
                                                    WHERE ingreso=$Ingreso AND estado not in(0,5)";
                                $result=$dbconn->Execute($query);
                                if($result->fields[0] == 1)
                                {
                                        $query = "UPDATE ingresos SET estado='0',fecha_cierre='now()'
                                                            WHERE ingreso=$Ingreso";
                                        $result=$dbconn->Execute($query);
                                        if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error al Guardar";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            return false;
                                        }
                                }

                                $query = "UPDATE cuentas SET estado='5' WHERE numerodecuenta=$Cuenta";
                                $result=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al Cargar el Modulo";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                                }
                                else
                                {
                                            $mensaje='La Cuenta No. '.$Cuenta.' ha sido Anulada.';
                                            $_SESSION['ESTADO']='I';
                                            $accion=ModuloGetURL('app','Facturacion','user','FormaMetodoBuscar',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$_SESSION['ESTADO']));
                                            if(!$this-> FormaMensaje($mensaje,'ANULAR CUENTA No. '.$Cuenta,$accion,'')){
                                            return false;
                                            }
                                            return true;
                                }
            }
            else
            {
                                $mensaje='No se puede Anular la Cuenta No. '.$Cuenta.' la Cuenta tiene Cargos o el Paciente tiene una Cama Activa.';
                                $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
                                if(!$this-> FormaMensaje($mensaje,'ANULAR CUENTA No. '.$Cuenta,$accion,'')){
                                return false;
                                }
                                return true;
            }
      }
      
//Funciones para la liquidacion de habitaciones
      
      function ValidarEgresoPaciente($Ingreso){
        
        list($dbconn) = GetDBconn();        
        $query = "SELECT count(*)
        FROM hc_ordenes_medicas a,hc_vistosok_salida_detalle b
        WHERE a.ingreso=$Ingreso AND a.sw_estado IN ('0','1') AND a.hc_tipo_orden_medica_id IN ('99','06','07') AND
        a.ingreso=b.ingreso AND b.visto_id='01' AND a.evolucion_id=b.evolucion_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
  
        $result->Close();
        return $result->fields[0];
        
      }
      
      function LlamarFormaLiquidacionManualHabitaciones(){              
        $this->FormaLiquidacionManualHabitaciones($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['PlanId'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['Ingreso']);
        return true;
      }
      
      
      function EliminarCargoHabitacion(){
        IncludeClass('LiquidacionHabitacionesCta','','app','Facturacion');
        $objeto = new LiquidacionHabitacionesCta();        
        $objeto->EliminarCargoHabitacionVector($_REQUEST['posicion']);        
        $mensaje="SE ELIMINO EL REGISTRO.";
        $this->FormaLiquidacionManualHabitaciones($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['PlanId'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$mensaje);
        return true;      
      }
      
      function ModificarCargoHabitacion(){
        IncludeClass('LiquidacionHabitacionesCta','','app','Facturacion');
        $objeto = new LiquidacionHabitacionesCta();        
        $objeto->ModificarCargoHabitacionVector($_REQUEST['precio_plan'],$_REQUEST['dias'],$_REQUEST['excedente'],$_REQUEST['cub'],$_REQUEST['noCub']);        
        $mensaje="SE MODIFICO EL REGISTRO SATISFACTORIAMENTE.";
        $this->FormaLiquidacionManualHabitaciones($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['PlanId'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$mensaje);
        return true;     
      }
      
      function InsertarCargoHabitacion(){
        IncludeClass('LiquidacionHabitacionesCta','','app','Facturacion');
        $objeto = new LiquidacionHabitacionesCta();        
        $objeto->InsertarCargoHabitacionVector($_REQUEST['tipocama'],$_REQUEST['dpto'],$_REQUEST['precioN'],$_REQUEST['diasN'],$_REQUEST['noCubN'],$_REQUEST['copago']);        
        $mensaje="SE INSERTO EL REGISTRO SATISFACTORIAMENTE.";
        $this->FormaLiquidacionManualHabitaciones($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['PlanId'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$mensaje);
        return true;  
      }
      
      function LlamadoCargarHabitacionCuenta(){
        unset($_SESSION['LIQUIDACION_HABITACIONES']);
        IncludeClass('LiquidacionHabitacionesCta','','app','Facturacion');
        $objeto = new LiquidacionHabitacionesCta(); 
        if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php")) 
        {
          die(MsgOut("Error al incluir archivo","El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
        }
        if(!is_array($_SESSION['LIQUIDACION_HABITACIONES'])){        
          $liquidacionHab = new LiquidacionHabitaciones;
          $_SESSION['LIQUIDACION_HABITACIONES'] = $liquidacionHab->LiquidarCargosInternacion($_REQUEST['Cuenta'],false);                                             
        }        
        if($objeto->CargarHabitacionCuenta($_REQUEST['EmpresaId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['PlanId'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$mensaje)==false){
          $mensaje="ERROR AL INSERTAR EN CUENTAS DETALLE.";          
        }else{        
          $mensaje="REGISTROS CARGADOS A LA CUENTA SATISFACTORIAMENTE.";          
        }
        unset($_SESSION['LIQUIDACION_HABITACIONES']);
        if(!$this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],'',$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','',$mensaje)){                   
          return false;
        }
        return true;          
      }
      
      function VolverDetalleCuenta(){
        IncludeClass('LiquidacionHabitacionesCta','','app','Facturacion');
        $objeto = new LiquidacionHabitacionesCta();        
        $objeto->CancelarCargueHabitacionCuenta();  
        if(!$this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],'',$_REQUEST['Fecha'],$_REQUEST['Ingreso'])){
            return false;
        }
        return true;
      }
            
//fin funciones  
    
//Funciones de la clase de listado de pacientes con Salida

      function CallFrmListaPacientesConSalida(){
        unset($_SESSION['LISTADO_PACIENTES_SALIDA']);
        $this->FrmListaPacientesConSalida();
        return true;      
      }
      
      function RegresarMenu(){
        $this->FormaMenus();
        return true;      
      }
      
//fin funciones      
      

      /**
      * Inactiva la cuenta
      * @access public
      * @return boolean
      */
      function InactivarCuenta()
      {
            $Cuenta=$_REQUEST['Cuenta'];
            $Transaccion=$_REQUEST['Transaccion'];
            $TipoId=$_REQUEST['TipoId'];
            $PacienteId=$_REQUEST['PacienteId'];
            $Nivel=$_REQUEST['Nivel'];
            $PlanId=$_REQUEST['PlanId'];
            $Pieza=$_REQUEST['Pieza'];
            $Cama=$_REQUEST['Cama'];
            $Fecha=$_REQUEST['Fecha'];
            $Ingreso=$_REQUEST['Ingreso'];
            $Estado=$_REQUEST['Estado'];

            list($dbconn) = GetDBconn();
                    $dbconn->BeginTrans();
            $query = "UPDATE cuentas SET estado='2' WHERE numerodecuenta=$Cuenta";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
              return false;
            }
            else
            {
                            $query = "INSERT INTO auditoria_inactivar_cuentas (
                                                                                                            numerodecuenta,
                                                                                                            fecha_registro,
                                                                                                            usuario_id)
                                                VALUES($Cuenta,'now()',".UserGetUID().")";
                            $result=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }
                            else
                            {
                                    $dbconn->CommitTrans();
                                    $mensaje='La Cuenta No. '.$Cuenta.' ha sido Inactivada.';
                                    $_SESSION['ESTADO']='I';
                                    $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$_SESSION['ESTADO']));
                                    if(!$this-> FormaMensaje($mensaje,'INACTIVAR CUENTA No. '.$Cuenta,$accion,'')){
                                    return false;
                                    }
                                    return true;
                            }
            }
      }

      /**
      * Busca las bodegas que tiene una empresa
      * @access public
      * @return boolean
      */
      function Bodegas()
      {
            $EmpresaId=$_SESSION['CUENTAS']['EMPRESA'];
            if($_SESSION['CUENTAS']['CU'])
            { $CU="and centro_utilidad='".$_SESSION['CUENTAS']['CENTROUTILIDAD']."'"; }

            list($dbconn) = GetDBconn();
            $query="SELECT * FROM bodegas WHERE empresa_id='$EmpresaId' $CU
                                    order by descripcion";
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
         * Busca las bodegas asociadas a un usuario
         *
         * @param integer UsuarioId
         */
        function BuscarBodegasPorUsuarioId($UsuarioId)
        {
            if(empty($UsuarioId))
            {
                $UsuarioId=UserGetUID();
            }
            $EmpresaId=$_SESSION['CUENTAS']['EMPRESA'];
            if($_SESSION['CUENTAS']['CU'])
            {
                $CU="AND b.centro_utilidad='".$_SESSION['CUENTAS']['CENTROUTILIDAD']."'";
            }
            list($dbconn) = GetDBconn();
            $query="
                SELECT
                    b.*
                FROM
                    bodegas b,
                    bodegas_usuarios bu
                WHERE
                    b.empresa_id=bu.empresa_id AND
                    b.centro_utilidad=bu.centro_utilidad AND
                    b.bodega=bu.bodega AND
                    bu.usuario_id=$UsuarioId AND
                    b.empresa_id='$EmpresaId'
                    $CU
                ORDER BY
                    b.descripcion";
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
        }//Fin BuscarBodegasPorUsuarioId
      /**
      * Busca los datos de los cargos que se acaban de agregar.
      * @access public
      * @return array
      * @param int numero de la cuenta
      */
      function DatosTmpCuentas($Cuenta)
      {
            $Usuario=UserGetUID();
            list($dbconn) = GetDBconn();
            $query="SELECT a.*, b.tipo_tercero_id, b.tercero_id
                                    FROM tmp_cuentas_detalle as a
                                    left join tmp_cuentas_detalle_profesionales as b on(a.transaccion=b.transaccion)
                                    WHERE a.numerodecuenta=$Cuenta AND a.usuario_id=$Usuario";
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



      /**
      * Busca los datos de los cargos que estan pendientes de un usuario
      * @access public
      * @return array
      */
      function DatosTmpCuentasPendientes()
      {
            $Usuario=UserGetUID();
            list($dbconn) = GetDBconn();
            $query = "(select distinct a.numerodecuenta, b.ingreso, b.plan_id, b.fecha_registro,
                        b.rango, c.paciente_id, c.tipo_id_paciente, d.primer_nombre, d.segundo_nombre,
                        d.primer_apellido, d.segundo_apellido from tmp_cuentas_detalle as a, cuentas as b, ingresos as c, pacientes as d
                        where a.usuario_id=$Usuario
                                            and a.numerodecuenta=b.numerodecuenta and b.ingreso=c.ingreso
                                            and c.paciente_id=d.paciente_id and c.tipo_id_paciente=d.tipo_id_paciente)";
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
      *
      */
      function LlamarFormaTiposCargos()
      {
          $Cuenta=$_REQUEST['Cuenta'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];

          $this->FormaTiposCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha);
          return true;
      }


      /**
      * Llama la forma FormaCargos que insertar nuevos cargos.
      * @access public
      * @return boolean
      */
       function Cargos()
       {
          $_SESSION['CUENTA']['Insumos']=$_REQUEST['Insumos'];
          $this->FormaCargos($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Ingreso'],$_REQUEST['Fecha'],$mensaje,'',$var,$ValEmpresa,$Cobertura);
          return true;
       }

      /**
      * Llama la forma Encabezado.
      * @access public
      * @return boolean
      */
       function LlamadaFormaEncabezado()
       {
          $this->Encabezado($_REQUEST['PlanId'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],'',$_REQUEST['Cuenta']);
          return true;
       }

      /**
      * Busca en tipo de solictud de un cargo
      * @access public
      * @return int
      * @param string codigo del cargo
      * @param string grupo del tarifario
      * @param string subgrupo del tarifario
      */
      function TipoSolicitud($Cargo,$TarifarioId,$Grupo,$Subgrupo)
      {
            list($dbconn) = GetDBconn();
            $sql =  "SELECT a.tarifario_id, a.grupo_tarifario_id, a.subgrupo_tarifario_id,
                        a.grupo_tipo_cargo, b.descripcion,
                        b.cargo_agrupamiento_sistema, b.grupo_tipo_cargo
                        FROM tarifarios_detalle as a, grupos_tipos_cargo as b
                        WHERE a.cargo='$Cargo' AND a.grupo_tipo_cargo=b.grupo_tipo_cargo";
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

        /**
        *
        */
        /*function BuscarGrupoTipoCargo($cups)
        {
            list($dbconn) = GetDBconn();
                    $query = "select b.codigo_agrupamiento_id
                                        from cups as a, grupos_tipos_cargo as b
                                        where a.cargo='$cups' and a.grupo_tipo_cargo=b.grupo_tipo_cargo
                                        and b.codigo_agrupamiento_id is not NULL";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }

                    if(!$result->EOF)
                    { $vars= $result->GetRowAssoc($ToUpper = false); }

                    $result->Close();
                    return $vars;
        }*/

      /**
       * Inserta un cargo a la cuenta en tmp_cuenta_detalles.
       * @access public
       * @return boolean
       */
       function InsertarCargoTmp()
       {
            IncludeLib("tarifario_cargos");
            IncludeLib("funciones_facturacion");
                    if (IncludeClass("rips"))
                    {
                            $Departamento=$_REQUEST['Departamento'];
                            $Precio=$_REQUEST['Precio'];
                            $CargoCups=$_REQUEST['Cargo'];
                            $Cantidad=$_REQUEST['Cantidad'];
                            $Cuenta=$_REQUEST['Cuenta'];
                            $Nivel=$_REQUEST['Nivel'];
                            $PlanId=$_REQUEST['PlanId'];
                            $Ingreso=$_REQUEST['Ingreso'];
                            $Fecha=$_REQUEST['Fecha'];
                            $TipoId=$_REQUEST['TipoId'];
                            $PacienteId=$_REQUEST['PacienteId'];
                            $FechaCargo=$_REQUEST['FechaCargo'];
                            $f=explode('/',$FechaCargo);
                            $FechaCargo=$f[2].'-'.$f[1].'-'.$f[0];
                            $CUtilidad=$_SESSION['CUENTAS']['CENTROUTILIDAD'];
                            $EmpresaId=$_SESSION['CUENTAS']['EMPRESA'];
                            $SystemId=UserGetUID();

                            $var[1]=$Departamento;
                            $var[2]=$TarifarioId;
                            $var[3]=$CargoCups;
                            $var[4]=$Cantidad;
                            $var[5]=$Precio;
                            $var[6]=$Gravamen;
                            $var[9]=$GrupoTarifario;
                            $var[10]=$SubGrupoTarifario;
                            $var[11]=$FechaCargo;

                            if(!$Cantidad || !$CargoCups || !$FechaCargo){
                                    if(!$Cantidad){ $this->frmError["Cantidad"]=1; }
                                    if(!$CargoCups){ $this->frmError["Cargo"]=1; }
                                    if(!$FechaCargo){ $this->frmError["FechaCargo"]=1; }
                                    $mensaje='Faltan datos obligatorios.';
                                    if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,'Modi',$Cobertura)){
                                        return false;
                                    }
                                    return true;
                            }
                            if(!$Departamento || $Departamento==-1){
                                $mensaje='Seleccione el Departamento.';
                                if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,'Modi',$Cobertura)){
                                    return false;
                                }
                                return true;
                            }

                            list($dbconn) = GetDBconn();
                            $query ="SELECT b.servicio FROM departamentos as b
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
                                    $mensaje='La Cantidad debe ser entera.';
                                    if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,'Modi',$Cobertura)){
                                        return false;
                                    }
                                    return true;
                            }
            //----------esto es cuando digitan el codigo del cargo---------------
                            if(empty($_REQUEST['Descripcion']))
                            {
                                    $key1="cargo";
                                    $filtro = "( lower ($key1) like '%$CargoCups' or lower ($key1) like '%$CargoCups%' or lower ($key1) like '$CargoCups%')";
                                    $campos_select = "a.descripcion, a.cargo ";

                                    $resulta = BuscardoCargosCups($filtro, $campos_select,'','');
                                    //$resulta = BuscardoCargosCups($PlanId, '', $filtro, $campos_select, $fetch_mode_assoc=false, '','');
                                    if($resulta->RecordCount() > 1)
                                    {
                                            $mensaje='Existen dos cargos con el mismo Codígo, Porfavor Busque el Cargo.';
                                            if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,'',$Cobertura)){
                                                return false;
                                            }
                                            return true;
                                    }
                                        elseif($resulta->RecordCount() == 0)
                                        {
                                            $mensaje='El Cargo No Existe, No tiene Equivalencias o No esta Contratado.';
                                            if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,'',$Cobertura)){
                                                return false;
                                            }
                                            return true;
                                        }
                                    /*else
                                    {
                                            $arreglo=$resulta->GetRowAssoc($ToUpper = false);
                                            $TarifarioId=$arreglo[tarifario_id];
                                            $GrupoTarifario=$arreglo[grupo_tarifario_id];
                                            $SubGrupoTarifario=$arreglo[subgrupo_tarifario_id];
                                    }*/
                            }
            //-----------------VALIDAR LAS EQUIVALENCIAS DEL CARGO CUPS --------------------
                                            //traer solo equivalencias contratadas
                                            $equi='';
                                            $equi=ValdiarEquivalencias($PlanId,$CargoCups);
                                            if(sizeof($equi)==1)
                                            {
                                                    $TarifarioId=$equi[0][tarifario_id];
                                                    $Cargo=$equi[0][cargo];
                                                    $GrupoTarifario=$equi[0][grupo_tarifario_id];
                                                    $SubGrupoTarifario=$equi[0][subgrupo_tarifario_id];
                                            }
                                            elseif(sizeof($equi) > 1)
                                            {       //tiene varias equivalencias
                                                    $this->FormaVariasEquivalencias($Departamento,$Servicio,$CargoCups,$_REQUEST['Descripcion'],$equi,$TipoId,$PacienteId,$Cuenta,$Nivel,$PlanId,$Fecha,$Ingreso,$Cantidad,$FechaCargo,$_REQUEST['MedInt']);
                                                    return true;
                                            }
                                            else
                                            {
                                                    $mensaje='EL CARGO NO TIENE EQUIVALENCIAS O NO ESTA CONTRATADO PARA ESTE PLAN.';
                                                    if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,'',$Cobertura)){
                                                            return false;
                                                    }
                                                    return true;
                                            }
            //------------------------------------------------------------------------------
                            list($dbconn) = GetDBconn();
                            /*$query="SELECT cargo FROM tmp_cuentas_detalle
                                            WHERE cargo='$Cargo' AND tarifario_id='$TarifarioId' and numerodecuenta=$Cuenta";
                            $result=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al Guardar en la Base de Datos";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                            }
                            if(!$result->EOF){ $Existe=1; }

                            if($Existe)
                            {
                                    $mensaje='Este cargo ya existe, debe modificar la cantidad del cargo existente para agregar uno.';
                                    if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
                                        return false;
                                    }
                                    return true;
                            }*/
                            $AutoInt=1;
                            $AutoExt='NULL';
                            //--------------------valida si no necesita autorizacion-------------------------
                            $msg='';
                            $query = "select autorizacion_cargo_cups_int($PlanId,'$CargoCups','$Servicio')";
                                //$query = "select autorizacion_cobertura('$PlanId','$TarifarioId','$Cargo','$Servicio')";
                            $result = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }
                            if($result->fields[0]!='NoRequiere')
                            {
                                    $msg .='<BR>EL CARGO NECESITA AUTORIZACION INTERNA';
                                    $autoInt=1;
                                    $AutoInt=0;
                            }
                            $query = "select autorizacion_cargo_cups_ext($PlanId,'$CargoCups','$Servicio')";
                            $result = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }
                            if($result->fields[0]!='NoRequiere')
                            {
                                $msg .='<BR>EL CARGO NECESITA AUTORIZACION EXTERNA';
                                $autoExt=1;
                                $AutoExt=0;
                            }
                                            //------------------fin validacion de autorizacion--------------------

                            //no tiene ninguna y necesita autorizacion
                                            $usu=$this->BuscarUsuarios($PlanId);
                            if(($autoExt==1 OR $autoInt==1) AND !empty($usu))
                            {
                                $auto[]=array('tarifario'=>$TarifarioId,'cargo'=>$Cargo,'cantidad'=>$Cantidad,'descripcion'=>$_REQUEST['Descripcion'],'cups'=>$CargoCups);

                                        unset($_SESSION['SOLICITUDAUTORIZACION']);
                                        unset($_SESSION['AUTORIZACIONES']);
                                                                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO']['CARGOS'][]=array('tarifario'=>$TarifarioId,'cargo'=>$Cargo,'cantidad'=>$Cantidad,'descripcion'=>$_REQUEST['Descripcion'],'cups'=>$CargoCups);
                                        //if(!empty($usu))
                                    // {
                                                                                            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$TipoId;
                                                                                            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$PacienteId;
                                                                                            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO']=$Cargo;
                                                                                            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TARIFARIO']=$TarifarioId;
                                                                                            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$PlanId;
                                                                                            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['cantidad']=$Cantidad;
                                                                                            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO']['CARGOS']=$auto;
                                                                                            $_SESSION['FACTURACION']['CARGO']=$Cargo;
                                                                                            $_SESSION['FACTURACION']['CUPS']=$CargoCups;
                                                                                            $_SESSION['FACTURACION']['CANTIDAD']=$Cantidad;
                                                                                            $_SESSION['FACTURACION']['TARIFARIO']=$TarifarioId;
                                                                                            $_SESSION['FACTURACION']['CargoD']=$CargoD;
                                                                                            $_SESSION['FACTURACION']['Apoyo']=$Apoyo;
                                                                                            $_SESSION['FACTURACION']['SW']=$SW;
                                                                                            $_SESSION['FACTURACION']['LIQ']=$Liq;
                                                                                            $_SESSION['FACTURACION']['DEPTO']=$Departamento;
                                                                                            $_SESSION['FACTURACION']['SERVICIO']=$Servicio;
                                                                                            $_SESSION['FACTURACION']['FECHACARGO']=$FechaCargo;

                                                                                            $mensaje='El Cargo: '.$_REQUEST['Descripcion'].' Necesita Autorización para ser Cargado.'.$msg;
                                                                                            $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
                                                                                            $c='app';
                                                                                            $m='Facturacion';
                                                                                            $me='AutorizarCargos';
                                                                                            $me2='Cargos';
                                                                                            $Titulo='AUTORIZAR CARGO';
                                                                                            $boton1='ACEPTAR';
                                                                                            $boton2='CANCELAR';
                                                                                            $this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
                                                                                            return true;
                                        /*}
                                                                    else
                                                                    {   $mensaje='Este Cargo Necesita Autorización';  }*/
                                    /* else
                                        {
                                                                                                    $_SESSION['DATOS']['SOLICITUDAUTORIZACION']['departamento']=$Departamento;
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['plan_id']=$PlanId;
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['ingreso']=$Ingreso;
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['rango']=$Nivel;
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['cuenta']=$Cuenta;
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['paciente_id']=$PacienteId;
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['tipo_id_paciente']=$TipoId;
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['tipo_afiliado_id']=$_REQUEST['Afiliado'];
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['servicio']=$Servicio;
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['cantidad']=$Cantidad;
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['cargo']=$Cargo;
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']=$auto;
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['tarifario_id']=$TarifarioId;
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['modulo']='Facturacion';
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['tipo']='user';
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['contenedor']='app';
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['metodo']='RetornoAutorizacionCargos';
                                                                                                    $_SESSION['FACTURACION']['DEPTO']=$Departamento;
                                                                                                    $mensaje='Se Necesita Autorización para ser Cargado, debe solicitar la Autorización.';
                                                                                                    $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
                                                                                                    $_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['argumentos']=$arreglo;
                                                                                                    $c='app';
                                                                                                    $m='Facturacion';
                                                                                                    $me='AutorizarCargos';
                                                                                                    $me2='Cargos';
                                                                                                    $Titulo='SOLICITAR AUTORIZACION CARGO';
                                                                                                    $boton1='SOLICITAR';
                                                                                                    $boton2='CANCELAR';
                                                                                                    $this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
                                                                                                    return true;
                                        }*/
                            }

                                            //PROFESIONAL
                                            if(!empty($_REQUEST['MedInt']))
                                            {
                                                $p=explode('||',$_REQUEST['MedInt']);
                                            }
                                            //MauroB
                                            //Paso todas las validaciones
                                            //pide datos adicionales necesrios para el rips
                                            $arreglo1[]=array('fecha_cargo'=>$FechaCargo,'cargo'=>$Cargo,'tarifario'=>$TarifarioId,'servicio'=>$Servicio,'aut_int'=>$autoInt,'aut_ext'=>$autoExt,'tipo_tercero'=>$p[0],'tercero'=>$p[1],'cups'=>$CargoCups,'cantidad'=>$Cantidad,'departamento'=>$Departamento,'sw_cargue'=>3);
                                            unset($_SESSION['TMP_DATOS']);
                                            $_SESSION['TMP_DATOS']['sw_pide_otro_frm']=0;
                                            $adicionalesRips=$this->PideDatosAdicionalesRips($CargoCups,$equi[0][tarifario_id],$EmpresaId,$arreglo1,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);

                                            if($adicionalesRips === 'sin_tipo_rips')
                                            {
                                                $arreglo[]=array('fecha_cargo'=>$FechaCargo,'cargo'=>$Cargo,'tarifario'=>$TarifarioId,'servicio'=>$Servicio,'aut_int'=>$autoInt,'aut_ext'=>$autoExt,'tipo_tercero'=>$p[0],'tercero'=>$p[1],'cups'=>$CargoCups,'cantidad'=>$Cantidad,'departamento'=>$Departamento,'sw_cargue'=>3);
                                                $insertar = InsertarTmpCuentasDetalle($_SESSION['CUENTAS']['EMPRESA'],$_SESSION['CUENTAS']['CENTROUTILIDAD'],$Cuenta,$PlanId,$arreglo);
                                                if(!empty($insertar))
                                                {
                                                  $mensaje="EL CARGO FUE GRABADO.";
                                                }
                                                else
                                                {  $mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR.";  }
                                                $this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
                                                return true;
                                            }
                                            else
                                            if($adicionalesRips)
                                            {
                                                //$arreglo[]=array('fecha_cargo'=>$FechaCargo,'cargo'=>$Cargo,'tarifario'=>$TarifarioId,'servicio'=>$Servicio,'aut_int'=>$autoInt,'aut_ext'=>$autoExt,'tipo_tercero'=>$p[0],'tercero'=>$p[1],'cups'=>$CargoCups,'cantidad'=>$Cantidad,'departamento'=>$Departamento,'sw_cargue'=>3);
                                                //$insertar = InsertarTmpCuentasDetalle($_SESSION['CUENTAS']['EMPRESA'],$_SESSION['CUENTAS']['CENTROUTILIDAD'],$Cuenta,$PlanId,$arreglo);
                                                //if(!empty($insertar))
                                                //{
                                                //  $mensaje="EL CARGO FUE GRABADO.";
                                                //}
                                                //else
                                                //{  $mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR.";  }
                                                //$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
                                                return true;
                                            }
                                            else
                                            {
                                                $mensaje="ERROR: DATOS NECESARIOS PARA RIPS NO VALIDOS.";
                                                $this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
                                                return true;
                                            }
/*                                      if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura))
                                            {
                                                    return false;
                                            }*/
                                                //Fin Mauro B.
//
                                            return true;
/*
            //----------------------------esto es para los calculos-------------------------
                        $liq=LiquidarCargoCuenta($Cuenta,$TarifarioId,$Cargo,$Cantidad,0,0,false,false,'',$Servicio,$PlanId,'','','',true,'','',0,true);
                                            //$TarifarioId=$liq[tarifario_id];
                                            //$Cargo=$liq[cargo];
                                            //$Cantidad=$liq[cantidad];
                                            $Precio=$liq[precio_plan];
                                            $ValorNo=$liq[valor_no_cubierto];
                                            $ValorCub=$liq[valor_cubierto];
                                            $ValorCargo=$liq[valor_cargo];
                                            $Facturado=$liq[facturado];
                                            $DescuentoEmp=$liq[valor_descuento_empresa];
                                            $DescuentoPac=$liq[valor_descuento_paciente];
                                            $codigo=$liq[codigo_agrupamiento_id];
                                            $fact=$liq[facturado];
                                            $codigo='NULL';
                                            $servicio=$liq[servicio_cargo];
            //-------------------------------------------------------------------------------

                                            $agru=BuscarGrupoTipoCargo($CargoCups);
                                            if(!empty($agru))
                                            {
                                                            $codigo=$agru[codigo_agrupamiento_id];
                                            }

                            $f=explode('/',$Fecha);
                            $Fecha=$f[2].'-'.$f[1].'-'.$f[0];

                                            $query="SELECT nextval('tmp_cuentas_detalle_transaccion_seq')";
                                            $result=$dbconn->Execute($query);
                                            $Transaccion=$result->fields[0];
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
                                                                                                                                                                    cargo_cups,
                                                                                                                                                                    sw_cargue)
                                                                                            VALUES ($Transaccion,'$EmpresaId','$CUtilidad',$Cuenta,'$Departamento','$TarifarioId','$Cargo',$Cantidad,$Precio,$ValorCargo,$ValorNo,$ValorCub,$SystemId,$fact,'now()',$DescuentoPac,$DescuentoEmp,$Servicio,$AutoInt,$AutoExt,".$liq[porcentaje_gravamen].",'".$liq[sw_cuota_paciente]."','".$liq[sw_cuota_moderadora]."',$codigo,'now()','$CargoCups','3')";
                                                    $dbconn->Execute($query);
                                                    if ($dbconn->ErrorNo() != 0) {
                                                                    $this->error = "Error al 4Guardar en la Base de Datos";
                                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                    return false;
                                                    }
                                                    //PROFESIONAL
                                                    if(!empty($_REQUEST['MedInt']))
                                                    {
                                                                            $p=explode('||',$_REQUEST['MedInt']);
                                                                            $query = "INSERT INTO tmp_cuentas_detalle_profesionales(
                                                                                                                                                                                                                                                                            transaccion,
                                                                                                                                                                                                                                                                            tipo_tercero_id,
                                                                                                                                                                                                                                                                            tercero_id)
                                                                                                                    VALUES($Transaccion,'".$p[0]."','".$p[1]."')";
                                                                            $result = $dbconn->Execute($query);
                                                                            if($dbconn->ErrorNo() != 0) {
                                                                                            $dbconn->RollbackTrans();
                                                                                            return false;
                                                                            }
                                                    }

                                                    $dbconn->CommitTrans();
                                                    if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
                                                            return false;
                                                    }
                                                    return true;*/
                    }
                    else
                    {
                        echo "No se pudo cargar la clase RIPS";
                    }
       }

         /**
         *
         */
         function InsertarCargoTmpEquivalencias()
         {
                        //cambio lorena porque se cae el programa cuando mandaban este vector por request
                        $vector=$_SESSION['FACTURACION']['VECTOR_EQUIVALENCIAS'];
                        //fin cambio
                        unset($_SESSION['FACTURACION']['VECTOR_EQUIVALENCIAS']);
                    IncludeLib("tarifario_cargos");
                    IncludeLib("funciones_facturacion");
                    $f=0;
            foreach($_REQUEST as $k => $v)
            {
                        if(substr_count($k,'cargo') AND $f==0)
                        {
                            $f++;
                            $var=explode('||',$v);
                            $CargoCups=$var[3];
                        }
            }

                    if($f==0)
                    {
                            $this->frmError["MensajeError"]="ERROR DATOS VACIOS: Debe elegir alguna Cargo Equivalente.";
                            $this->FormaVariasEquivalencias($_REQUEST['departamento'],$_REQUEST['servicio'],$_REQUEST['cups'],$_REQUEST['descripcion'],$vector,$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Cuenta'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['cantidad'],$_REQUEST['fechacargo'],$_REQUEST['profesional']);
                            return true;
                    }
                    $Cuenta=$_REQUEST['Cuenta'];
                    $Servicio=$_REQUEST['servicio'];
                    $PlanId=$_REQUEST['PlanId'];
                    $Departamento=$_REQUEST['departamento'];
                    $Cantidad=$_REQUEST['cantidad'];
                    $TipoId=$_REQUEST['TipoId'];
                    $PacienteId=$_REQUEST['PacienteId'];
                    $Nivel=$_REQUEST['Nivel'];
                    $Fecha=$_REQUEST['Fecha'];
                    $Ingreso=$_REQUEST['Ingreso'];
                    $FechaCargo=$_REQUEST['fechacar'];

                    $usu=$this->BuscarUsuarios($PlanId);
                    list($dbconn) = GetDBconn();
                    $AutoInt=1;
                    $AutoExt='NULL';
                    //--------------------valida si no necesita autorizacion-------------------------
                    $msg='';
                    $query = "select autorizacion_cargo_cups_int($PlanId,'$CargoCups','$Servicio')";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    if($result->fields[0]!='NoRequiere')
                    {
                            $msg .='<BR>EL CARGO NECESITA AUTORIZACION INTERNA';
                            $autoInt=1;
                            $AutoInt=0;
                    }
                    $query = "select autorizacion_cargo_cups_ext($PlanId,'$CargoCups','$Servicio')";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    if($result->fields[0]!='NoRequiere')
                    {
                            $msg .='<BR>EL CARGO NECESITA AUTORIZACION EXTERNA';
                            $autoExt=1;
                            $AutoExt=0;
                    }
                    //------------------fin validacion de autorizacion--------------------

                    //PROFESIONAL
                    if(!empty($_REQUEST['profesional']))
                    {       $p=explode('||',$_REQUEST['profesional']);   }

                    $auto='';
                    foreach($_REQUEST as $k => $v)
                    {
                                                if(substr_count($k,'cargo'))
                                                {           //2descripcion  3cargo_cups
                                                    $var=explode('||',$v);

                                                    if(($autoExt==1 OR $autoInt==1) AND !empty($usu))
                                                    {
                                                            $auto[]=array('tarifario'=>$var[0],'cargo'=>$var[1],'cantidad'=>$Cantidad,'descripcion'=>$var[2],'cups'=>$var[3]);
                                                    }
                                                    else
                                                    {
                                                            $arreglo[]=array('fecha_cargo'=>$_REQUEST['fechacar'],'cargo'=>$var[1],'tarifario'=>$var[0],'servicio'=>$_REQUEST['servicio'],'aut_int'=>$autoInt,'aut_ext'=>$autoExt,'tipo_tercero'=>$p[0],'tercero'=>$p[1],'cups'=>$var[3],'cantidad'=>$Cantidad,'departamento'=>$_REQUEST['departamento'],'sw_cargue'=>3);
                                                    }
                          }
                    }
                                    //MauroB
                                            //Paso todas las validaciones
                                            //pide datos adicionales necesrios para el rips
                                            unset($_SESSION['TMP_DATOS']);
                                            $_SESSION['TMP_DATOS']['sw_pide_otro_frm']=0;
                                            $adicionalesRips=$this->PideDatosAdicionalesRips($CargoCups,$equi[0][tarifario_id],$_SESSION['CUENTAS']['EMPRESA'],$arreglo,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
                                //MauroB
                /*  $auto='';
                    foreach($_REQUEST as $k => $v)
                    {
                if(substr_count($k,'cargo'))
                {           //2descripcion  3cargo_cups
                                        $var=explode('||',$v);
                                        $TarifarioId=$var[0];
                                        $Cargo=$var[1];
//----------------------------esto es para los calculos-------------------------
                                        $liq=LiquidarCargoCuenta($Cuenta,$TarifarioId,$Cargo,$Cantidad,0,0,false,false,0,$PlanId,$Servicio,'');
                                        //$Cantidad=$liq[cantidad];
                                        //$Precio=$liq[precio_plan];
                                        //$ValorNo=$liq[valor_no_cubierto];
                                        //$ValorCub=$liq[valor_cubierto];
                                        //$ValorCargo=$liq[valor_cargo];
                                        //$DescuentoEmp=$liq[valor_descuento_empresa];
                                        //$DescuentoPac=$liq[valor_descuento_paciente];
                                        //$fact=$liq[facturado];
                                        $codigo='NULL';
                                        //$servicio=$liq[servicio_cargo];
//-------------------------------------------------------------------------------
                                        //necesita autorizacion
                                if(($autoExt==1 OR $autoInt==1) AND !empty($usu))
                                        {
                                                    $auto[]=array('tarifario'=>$TarifarioId,'cargo'=>$Cargo,'cantidad'=>$Cantidad,'descripcion'=>$var[2],'cups'=>$var[3]);
                                        }
                                        else
                                        {
                                                    $agru=BuscarGrupoTipoCargo($CargoCups);
                                                    if(!empty($agru))
                                                    {
                                                            $codigo=$agru[codigo_agrupamiento_id];
                                                    }
                                                    $query="SELECT nextval('tmp_cuentas_detalle_transaccion_seq')";
                                                    $result=$dbconn->Execute($query);
                                                    $Transaccion=$result->fields[0];
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
                                                                                                                cargo_cups,
                                                                                                                sw_cargue)
                                                                            VALUES ($Transaccion,'".$_SESSION['CUENTAS']['EMPRESA']."','".$_SESSION['CUENTAS']['CENTROUTILIDAD']."',$Cuenta,'$Departamento','$TarifarioId','$Cargo',".$liq[cantidad].",".$liq[precio_plan].",".$liq[valor_cargo].",".$liq[valor_no_cubierto].",".$liq[valor_cubierto].",".UserGetUID().",".$liq[facturado].",'now()',".$liq[valor_descuento_paciente].",".$liq[valor_descuento_empresa].",".$liq[servicio_cargo].",$AutoInt,$AutoExt,".$liq[porcentaje_gravamen].",'".$liq[sw_cuota_paciente]."','".$liq[sw_cuota_moderadora]."',$codigo,'now()','".$var[3]."','3')";
                                                        $dbconn->Execute($query);
                                                        if ($dbconn->ErrorNo() != 0) {
                                                                $this->error = "Error al 4Guardar en la Base de Datos";
                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                return false;
                                                        }
                                            }
                }//fin is sub
            }//fin foreach

                    $dbconn->CommitTrans();*/
                    if(is_array($auto))
                    {//hay cargos que necesitan autorizacion
                                    unset($_SESSION['SOLICITUDAUTORIZACION']);
                                    unset($_SESSION['AUTORIZACIONES']);
                                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$TipoId;
                                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$PacienteId;
                                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO']=$Cargo;
                                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TARIFARIO']=$TarifarioId;
                                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO']['CARGOS']=$auto;
                                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$PlanId;
                                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['cantidad']=$Cantidad;
                                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['servicio']=$Servicio;
                                    $_SESSION['FACTURACION']['tercero']=$p[1];
                                    $_SESSION['FACTURACION']['tipo_tercero']=$p[0];
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

                                    $mensaje='Se Necesita Autorización para los Cargos.'.$msg;
                                    $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
                                    $c='app';
                                    $m='Facturacion';
                                    $me='AutorizarCargos';
                                    $me2='Cargos';
                                    $Titulo='AUTORIZAR CARGO';
                                    $boton1='ACEPTAR';
                                    $boton2='CANCELAR';
                                    $this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
                                    return true;
                            /*}
                            else
                            {
                                    //  $mensaje='Este Cargo Necesita Autorización';
                                        //if(!$this->FormaCargos($Cuenta,$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$PlanId,$_REQUEST['Ingreso'],$_REQUEST['Fecha'],$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
                                        //  return false;
                                    //  }
                                    //  return true;
                                        $_SESSION['DATOS']['SOLICITUDAUTORIZACION']['departamento']=$Departamento;
                                        $_SESSION['SOLICITUDAUTORIZACION']['plan_id']=$PlanId;
                                        $_SESSION['SOLICITUDAUTORIZACION']['ingreso']=$Ingreso;
                                        $_SESSION['SOLICITUDAUTORIZACION']['cuenta']=$Cuenta;
                                        $_SESSION['SOLICITUDAUTORIZACION']['rango']=$Nivel;
                                        $_SESSION['SOLICITUDAUTORIZACION']['paciente_id']=$PacienteId;
                                        $_SESSION['SOLICITUDAUTORIZACION']['tipo_id_paciente']=$TipoId;
                                        $_SESSION['SOLICITUDAUTORIZACION']['tipo_afiliado_id']=$_REQUEST['Afiliado'];
                                        $_SESSION['SOLICITUDAUTORIZACION']['servicio']=$Servicio;
                                        $_SESSION['SOLICITUDAUTORIZACION']['cantidad']=$Cantidad;
                                        $_SESSION['SOLICITUDAUTORIZACION']['cargo']=$Cargo;
                                        $_SESSION['SOLICITUDAUTORIZACION']['tarifario_id']=$TarifarioId;
                                        $_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']=$auto;
                                        $_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['modulo']='Facturacion';
                                        $_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['tipo']='user';
                                        $_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['contenedor']='app';
                                        $_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['metodo']='RetornoAutorizacionCargos';
                                        $_SESSION['FACTURACION']['DEPTO']=$Departamento;
                                        $mensaje='Se Necesita Autorización para ser Cargado, debe solicitar la Autorización.';
                                        $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
                                        $_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['argumentos']=$arreglo;
                                        $c='app';
                                        $m='Facturacion';
                                        $me='AutorizarCargos';
                                        $me2='Cargos';
                                        $Titulo='SOLICITAR AUTORIZACION CARGO';
                                        $boton1='SOLICITAR';
                                        $boton2='CANCELAR';
                                        $this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
                                        return true;
                            }*/
                    }
                    else
                    {
                            $mensaje=$msg;
//
                                            if($adicionalesRips === 'sin_tipo_rips')
                                            {
                                                //$arreglo[]=array('fecha_cargo'=>$_REQUEST['fechacar'],'cargo'=>$var[1],'tarifario'=>$var[0],'servicio'=>$_REQUEST['servicio'],'aut_int'=>$autoInt,'aut_ext'=>$autoExt,'tipo_tercero'=>$p[0],'tercero'=>$p[1],'cups'=>$var[3],'cantidad'=>$Cantidad,'departamento'=>$_REQUEST['departamento'],'sw_cargue'=>3);
                                                $insertar = InsertarTmpCuentasDetalle($_SESSION['CUENTAS']['EMPRESA'],$_SESSION['CUENTAS']['CENTROUTILIDAD'],$Cuenta,$PlanId,$arreglo);
                                                if(!empty($insertar))
                                                {
                                                  $mensaje="EL CARGO FUE GRABADO.";
                                                }
                                                else
                                                {  $mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR.";  }
                                                $this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
                                                return true;
                                            }
                                            else
                                            if($adicionalesRips)
                                            {
                                                return true;
                                            }
                                            else
                                            {
                                                $msg="ERROR: DATOS NECESARIOS PARA RIPS NO VALIDOS.";
                                                $this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$msg,$D,$var='',$ValEmpresa,$Cobertura);
                                                return true;
                                            }
/*                                      if($adicionalesRips)
                                            {
                                                $arreglo1=$arreglo;
                                                $insertar = InsertarTmpCuentasDetalle($_SESSION['CUENTAS']['EMPRESA'],$_SESSION['CUENTAS']['CENTROUTILIDAD'],$Cuenta,$PlanId,$arreglo1);
                                                if(!empty($insertar))
                                                {
                                                    $msg="EL CARGO FUE GRABADO..";
                                                    $this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$msg,$D,$var='',$ValEmpresa,$Cobertura);
                                                }
                                                else
                                                {
                                                    $msg="ERROR: OCURRIO UN ERROR AL INSERTAR.";
                                                    $this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$msg,$D,$var='',$ValEmpresa,$Cobertura);
                                                }
                                            }
                                            else
                                            {
                                                $msg="ERROR: DATOS NECESARIOS PARA RIPS NO VALIDOS.";
                                                $this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$msg,$D,$var='',$ValEmpresa,$Cobertura);
                                            }*/
/*                                      if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$msg,$D,$var='',$ValEmpresa,$Cobertura))
                                            {
                                                    return false;
                                            }*/
//
                       //return true;
                    }
         }

      /**
       *
       */
       function AutorizarCargos()
       {
            //$PlanId=$_REQUEST['PlanId'];
            //$TipoId=$_REQUEST['TipoId'];
            //$PacienteId=$_REQUEST['PacienteId'];
            //$Ingreso=$_REQUEST['Ingreso'];
            //$Nivel=$_REQUEST['Nivel'];
           // $Fecha=$_REQUEST['Fecha'];
            //$Transaccion=$_REQUEST['Transaccion'];
            //$Cuenta=$_REQUEST['Cuenta'];
            //$Estado=$_REQUEST['Estado'];
            $arreglo=array('Transaccion'=>$_REQUEST['Transaccion'],'Cuenta'=>$_REQUEST['Cuenta'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso'],'Estado'=>$_REQUEST['Estado']);

            if(empty($_SESSION['SOLICITUDAUTORIZACION']))
            {
                  $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']='FACTURACION';
                  $_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']=$_SESSION['FACTURACION']['SERVICIO'];
                  $_SESSION['AUTORIZACIONES']['RETORNO']['argumentos']=$arreglo;
                  $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
                  $_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='Facturacion';
                  $_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
                  $_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='RetornoAutorizacionCargos';

                  $this->ReturnMetodoExterno('app','Autorizacion','user','AutorizarCargos');
                  return true;
            }
            else
            {
                  $_SESSION['SOLICITUDAUTORIZACION']['FACTURACION']=true;
                  $this->ReturnMetodoExterno('app','Autorizacion_Solicitud','user','LlamarFormaSolicitudAutorizacionVarios');
                  return true;
            }
       }

       /**
       *
       */
       function RetornoAutorizacionCargos()
       {
                        IncludeLib("tarifario_cargos");
                        $PlanId=$_REQUEST['PlanId'];
                        $TipoId=$_REQUEST['TipoId'];
                        $PacienteId=$_REQUEST['PacienteId'];
                        $Ingreso=$_REQUEST['Ingreso'];
                        $Nivel=$_REQUEST['Nivel'];
                        $Fecha=$_REQUEST['Fecha'];
                        $Transaccion=$_REQUEST['Transaccion'];
                        $Cuenta=$_REQUEST['Cuenta'];
                        $Estado=$_REQUEST['Estado'];
                        $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
                        //es una solicitud de autorizacion
                        /*if(!empty($_SESSION['SOLICITUDAUTORIZACION']['FACTURACION']))
                        {
                                if(!empty($_SESSION['SOLICITUDAUTORIZACION']['RETORNO']['SOLICITUD']))
                                {
                                        $mensaje='La Solicitud se Realizo.';
                                        $AutoExt=$AutoInt=0;
                                        list($dbconn) = GetDBconn();
                                        $dbconn->BeginTrans();

                                        for($i=0; $i<sizeof($_SESSION['SOLICITUDAUTORIZACION']['ARREGLO']); $i++)
                                        {
                                                //----------------------------esto es para los calculos-------------------------
                                                $liq=LiquidarCargoCuenta($Cuenta,$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO'][$i][tarifario],$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO'][$i][cargo],$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO'][$i][cantidad],0,0,false,false,0,$PlanId,$_SESSION['SOLICITUDAUTORIZACION']['servicio'],'');
                                                $codigo='NULL';
                                                $agru=$this->BuscarGrupoTipoCargo($_SESSION['SOLICITUDAUTORIZACION']['ARREGLO'][$i][cups]);
                                                if(!empty($agru))
                                                {  $codigo=$agru[codigo_agrupamiento_id];  }

                                                $query="SELECT nextval('tmp_cuentas_detalle_transaccion_seq')";
                                                $result=$dbconn->Execute($query);
                                                $Transaccion=$result->fields[0];
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
                                                                                                            cargo_cups,
                                                                                                            sw_cargue)
                                                                        VALUES ($Transaccion,'".$_SESSION['CUENTAS']['EMPRESA']."','".$_SESSION['CUENTAS']['CENTROUTILIDAD']."',$Cuenta,'".$_SESSION['FACTURACION']['DEPTO']."','".$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO'][$i][tarifario]."','".$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO'][$i][cargo]."',".$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO'][$i][cantidad].",".$liq[precio_plan].",".$liq[valor_cargo].",".$liq[valor_no_cubierto].",".$liq[valor_cubierto].",".UserGetUID().",".$liq[facturado].",'now()',".$liq[valor_descuento_paciente].",".$liq[valor_descuento_empresa].",".$_SESSION['SOLICITUDAUTORIZACION']['servicio'].",$AutoInt,$AutoExt,".$liq[porcentaje_gravamen].",'".$liq[sw_cuota_paciente]."','".$liq[sw_cuota_moderadora]."',$codigo,'now()','".$_SESSION['SOLICITUDAUTORIZACION']['ARREGLO'][$i][cups]."','3')";
                                                $dbconn->Execute($query);
                                                if ($dbconn->ErrorNo() != 0) {
                                                        $this->error = "Error INSERT INTO tmp_cuentas_detalle 2589";
                                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                        $dbconn->RollbackTrans();
                                                        return false;
                                                }
                                        }//fin for

                                        $dbconn->CommitTrans();
                                        unset($_SESSION['SOLICITUDAUTORIZACION']);
                                        if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
                                            return false;
                                        }
                                        return true;
                                }
                                else
                                {   $mensaje='Se Cancelo La Solicitud.';  }
                                unset($_SESSION['SOLICITUDAUTORIZACION']);
                                if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
                                    return false;
                                }
                                return true;
                        }
                else
                        {*/     //es una autorizacion
                            if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']) AND !empty($_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion']))
                            {
                                    unset($_SESSION['AUTORIZACIONES']);
                                    $mensaje='El Cargo No fue Autorizado. No puede ser Cargado.';
                                    if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
                                        return false;
                                    }
                                    return true;
                            }

                            if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion']))
                            {
                                        $AutoInt=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
                                        if($_SESSION['AUTORIZACIONES']['RETORNO']['ext']==true)
                                        {   $AutoExt=$AutoInt;  }
                                        else
                                        {  $AutoExt=0;  }

                                        //list($dbconn) = GetDBconn();
                                        //$dbconn->BeginTrans();

                                        $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['CARGOS'];
                                        for($i=0; $i<sizeof($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']); $i++)
                                        {
                                                $arreglo[]=array('fecha_cargo'=>$_SESSION['FACTURACION']['FECHACARGO'],'cargo'=>$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'][$i][cargo],'tarifario'=>$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'][$i][tarifario],'servicio'=>$_REQUEST['servicio'],'aut_int'=>$AutoInt,'aut_ext'=>$AutoExt,'tipo_tercero'=>$_SESSION['FACTURACION']['tipo_tercero'],'tercero'=>$_SESSION['FACTURACION']['tercero'],'cups'=>$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'][$i][cups],'cantidad'=>$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'][$i][cantidad],'departamento'=>$_SESSION['FACTURACION']['DEPTO'],'sw_cargue'=>3);
                                                //----------------------------esto es para los calculos-------------------------
                                                /*$liq=LiquidarCargoCuenta($Cuenta,$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'][$i][tarifario],$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'][$i][cargo],$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'][$i][cantidad],0,0,false,false,0,$PlanId,$_SESSION['AUTORIZACIONES']['AUTORIZAR']['servicio'],'');
                                                $codigo='NULL';
                                                $agru=$this->BuscarGrupoTipoCargo($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'][$i][cups]);
                                                if(!empty($agru))
                                                {  $codigo=$agru[codigo_agrupamiento_id];  }

                                                $query="SELECT nextval('tmp_cuentas_detalle_transaccion_seq')";
                                                $result=$dbconn->Execute($query);
                                                $Transaccion=$result->fields[0];
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
                                                                                                            cargo_cups,
                                                                                                            sw_cargue)
                                                                        VALUES ($Transaccion,'".$_SESSION['CUENTAS']['EMPRESA']."','".$_SESSION['CUENTAS']['CENTROUTILIDAD']."',$Cuenta,'".$_SESSION['FACTURACION']['DEPTO']."','".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'][$i][tarifario]."','".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'][$i][cargo]."',".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'][$i][cantidad].",".$liq[precio_plan].",".$liq[valor_cargo].",".$liq[valor_no_cubierto].",".$liq[valor_cubierto].",".UserGetUID().",".$liq[facturado].",'".$_SESSION['FACTURACION']['FECHACARGO']."',".$liq[valor_descuento_paciente].",".$liq[valor_descuento_empresa].",".$_SESSION['FACTURACION']['SERVICIO'].",$AutoInt,$AutoExt,".$liq[porcentaje_gravamen].",'".$liq[sw_cuota_paciente]."','".$liq[sw_cuota_moderadora]."',$codigo,'now()','".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'][$i][cups]."','3')";
                                                $dbconn->Execute($query);
                                                if ($dbconn->ErrorNo() != 0) {
                                                        $this->error = "Error al 4Guardar en la Base de Datos";
                                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                        $dbconn->RollbackTrans();
                                                        return false;
                                                }*/
                                        }//fin for
                                                                            //MauroB
                                                                            //Paso todas las validaciones
                                                                            //pide datos adicionales necesrios para el rips
                                                                            unset($_SESSION['TMP_DATOS']);
                                                                            $_SESSION['TMP_DATOS']['sw_pide_otro_frm']=0;
                                                                            $adicionalesRips=$this->PideDatosAdicionalesRips($CargoCups,$equi[0][tarifario_id],$EmpresaId,$arreglo,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
//                                     $insertar = InsertarTmpCuentasDetalle($_SESSION['CUENTAS']['EMPRESA'],$_SESSION['CUENTAS']['CENTROUTILIDAD'],$Cuenta,$PlanId,$arreglo);
//                                     if(empty($insertar))
//                                     {  $mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR.";  }
//                                     else
//                                     {  $mensaje="EL CARGO FUE GUARDADO.";  }
//                                     //$dbconn->CommitTrans();
//                                     unset($_SESSION['AUTORIZACIONES']);
//                                     if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)){
//                                         return false;
//                                     }
//MauroB
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
                        //}
         }


       /**
       * Inserta los cargos en cuenta_detalles y ayudas_diagnosticas
       * @ access public
       * @ return boolean
       */
       function GuardarTodosCargos()
       {
                    //IncludeLib('funciones_facturacion');
            $Datos=$this->DatosTmpCuentas($_REQUEST['Cuenta']);

            list($dbconn) = GetDBconn();
            for($i=0; $i<sizeof($Datos); $i++)
            {
                                $arreglo[]=array('fecha_cargo'=>$Datos[$i][fecha_cargo],'cargo'=>$Datos[$i][cargo],'tarifario'=>$Datos[$i][tarifario_id],'servicio'=>$Datos[$i][servicio_cargo],'aut_int'=>$Datos[$i][autorizacion_int],'aut_ext'=>$Datos[$i][autorizacion_ext],'tipo_tercero'=>$Datos[$i][tipo_tercero_id],'tercero'=>$Datos[$i][tercero_id],'cups'=>$Datos[$i][cargo_cups],'cantidad'=>$Datos[$i][cantidad],'departamento'=>$Datos[$i][departamento],'sw_cargue'=>$Datos[$i][sw_cargue]);
                    }

                    $sql =" DELETE FROM tmp_cuentas_detalle WHERE numerodecuenta=".$_REQUEST['Cuenta']."";
                    $insertar = InsertarCuentasDetalle($_SESSION['CUENTAS']['EMPRESA'],$_SESSION['CUENTAS']['CENTROUTILIDAD'],$_REQUEST['Cuenta'],$_REQUEST['PlanId'],$arreglo,$sql);
                    if(!empty($insertar))
                    {
                            $Nombres=$this->BuscarNombresPaciente($_REQUEST['TipoId'],$_REQUEST['PacienteId']);
                            $Apellidos=$this->BuscarApellidosPaciente($_REQUEST['TipoId'],$_REQUEST['PacienteId']);
                            $mensaje='TODOS LOS CARGOS SE GUARDARON EN LA CUENTA No. '.$_REQUEST['Cuenta'].' '.$Nombres.' '.$Apellidos;
                    }
                    else
                    {  $mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR.";  }

                    $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$_REQUEST['Cuenta'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Pieza'=>$_REQUEST['Pieza'],'Cama'=>$_REQUEST['Cama'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso']));
                    if(!$this->FormaMensaje($mensaje,'AGREGAR TODOS LOS CARGOS',$accion,$boton)){
                            return false;
                    }
                    return true;

                 /* if($Datos[$i][autorizacion_int]==='0' OR $Datos[$i][autorizacion_int] >0)
                        {   $AutoInt=$Datos[$i][autorizacion_int];   }
                  else
                  {   $AutoInt=1;   }
                  if($Datos[$i][autorizacion_ext]==='0' OR $Datos[$i][autorizacion_ext] >0)
                        {   $AutoExt=$Datos[$i][autorizacion_ext];   }

                                if(empty($Datos[$i][codigo_agrupamiento_id]))
                                {  $Datos[$i][codigo_agrupamiento_id]='NULL';   }
                  //$servicio=$Datos[$i][servicio_cargo];
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
                                                                                            cargo_cups,
                                                                                            sw_cargue)
                                                        VALUES ($Transaccion,'$EmpresaId','$CUtilidad',$Cuenta,'".$Datos[$i][departamento]."','".$Datos[$i][tarifario_id]."','".$Datos[$i][cargo]."',".$Datos[$i][cantidad].",".$Datos[$i][precio].",".$Datos[$i][valor_cargo].",".$Datos[$i][valor_nocubierto].",".$Datos[$i][valor_cubierto].",".UserGetUID().",".$Datos[$i][facturado].",'".$Datos[$i][fecha_cargo]."',".$Datos[$i][valor_descuento_paciente].",".$Datos[$i][valor_descuento_empresa].",".$Datos[$i][servicio_cargo].",$AutoInt,$AutoExt,".$Datos[$i][porcentaje_gravamen].",'".$Datos[$i][sw_cuota_paciente]."','".$Datos[$i][sw_cuota_moderadora]."',".$Datos[$i][codigo_agrupamiento_id].",'now()','".$Datos[$i][cargo_cups]."','".$Datos[$i][sw_cargue]."')";
                  $dbconn->Execute($query);
                  if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error al cuentas_detalle";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      $dbconn->RollbackTrans();
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

                    $dbconn->CommitTrans();
                    $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
                    $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
                    $mensaje='Todos los cargos se guardaron en la cuenta No. '.$Cuenta.' '.$Nombres.' '.$Apellidos;
                    $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                    if(!$this->FormaMensaje($mensaje,'AGREGAR TODOS LOS CARGOS',$accion,$boton)){
                            return false;
                    }
                    return true;*/
       }

       

/**
* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
* @return array
*/
      function TiposDeSalas(){

            list($dbconn) = GetDBconn();
            $query = "SELECT tipo_sala_id,descripcion
            FROM qx_tipos_salas";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }else{
                if($result->RecordCount()){
            while (!$result->EOF){
                        $vars[]=$result->GetRowAssoc($toUpper=false);
                        $result->MoveNext();
                  }
                }
            }
            $result->Close();
            return $vars;
        }


        /**
       * Actualiza la orden de servicio cuando se realiza la eliminasion de un cargo desde la cuenta.
       * @ access public
       * @ return boolean
       */
        function EliminarCargoOrdenCumplida(){

            if($_REQUEST['Volver']){
/*                if(!$this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['vars'],$_REQUEST['Transaccion'],$_REQUEST['mensaje'],$_REQUEST['Dev'],$_REQUEST['Estado'])){
                    return false;
                }*/
            if(!$this->DefinirForma($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['vars'],$_REQUEST['Transaccion'],$mensaje,$_REQUEST['Dev'],$_REQUEST['Estado'],
                                   $_REQUEST['Pieza'],$_REQUEST['doc'],$_REQUEST['numeracion'],$_REQUEST['qx'],$_REQUEST['codigo'],$_REQUEST['des'],$_REQUEST['noFacturado'],$_REQUEST['Consecutivo'])){
                return false;
            }
                return true;
            }
            $FechaAct=date("Y-m-d H:i:s");
            $SystemId=UserGetUID();
            list($dbconn) = GetDBconn();
            $query =" SELECT a.os_maestro_cargos_id,b.sw_estado,b.numero_orden_id,c.hc_os_solicitud_id,c.evolucion_id
                                FROM os_maestro_cargos a,os_maestro b,hc_os_solicitudes c
                                WHERE a.transaccion=".$_REQUEST['Transaccion']." AND
                                a.numero_orden_id=b.numero_orden_id AND
                                b.hc_os_solicitud_id=c.hc_os_solicitud_id";

            $result = $dbconn->Execute($query);
            if($result->EOF){
                $this->error = "Error al ejecutar la consulta.<br>";
                $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
                return false;
            }else{
                if($result->RecordCount()>0){
                    while(!$result->EOF){
                        $datos[]=$result->GetRowAssoc($ToUpper = false);
                        $result->MoveNext();
                    }
                }
            }
            $dbconn->BeginTrans();
            $query ="UPDATE os_cumplimientos_detalle SET sw_estado='3' WHERE numero_orden_id=".$datos[0]['numero_orden_id']."";

            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al audit_cuentas_detalle1";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            if($_REQUEST['Activar']){
                $query ="UPDATE os_maestro SET sw_estado='1' WHERE numero_orden_id=".$datos[0]['numero_orden_id']."";

                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al audit_cuentas_detalle1";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
            }elseif($_REQUEST['Anular']){
                $query ="UPDATE os_maestro SET sw_estado='9' WHERE numero_orden_id=".$datos[0]['numero_orden_id']."";

                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al audit_cuentas_detalle1";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }else{
                    if(!empty($datos[0]['evolucion_id'])){
                        $query ="UPDATE hc_os_solicitudes SET sw_estado='1' WHERE hc_os_solicitud_id=".$datos[0]['hc_os_solicitud_id']."";

                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al audit_cuentas_detalle1";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }else{
                            $query ="DELETE FROM hc_os_autorizaciones WHERE hc_os_solicitud_id=".$datos[0]['hc_os_solicitud_id']."";

                            $result = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al audit_cuentas_detalle1";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }
                        }
                    }else{
                        $query ="UPDATE hc_os_solicitudes SET sw_estado='2' WHERE hc_os_solicitud_id=".$datos[0]['hc_os_solicitud_id']."";

                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al audit_cuentas_detalle1";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                    }
                }
            }
        $query ="UPDATE os_maestro_cargos SET transaccion=NULL WHERE transaccion=".$_REQUEST['Transaccion']."";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al audit_cuentas_detalle1";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }

            $query =" SELECT * FROM cuentas_detalle WHERE transaccion=".$_REQUEST['Transaccion']." AND numerodecuenta=".$_REQUEST['Cuenta']."";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $Dat=$result->GetRowAssoc($ToUpper = false);
            if(empty($Dat[autorizacion_int])){$int='NULL';}else{$int=$Dat[autorizacion_int];}
            if(empty($Dat[autorizacion_ext])){$ext='NULL';}else{$ext=$Dat[autorizacion_ext];}
            if(empty($Dat[codigo_agrupamiento_id])){$Dat[codigo_agrupamiento_id]='NULL';}
            if(empty($Dat[consecutivo])){$Dat[consecutivo]='NULL';}
            if(empty($Dat[paquete_codigo_id])){
              $Dat[paquete_codigo_id]='NULL';  
            }
            if(empty($Dat[sw_paquete_facturado])){
              $Dat[sw_paquete_facturado]='NULL';  
            }

            //sw_actualizacion 2 es eliminacion
            $query = "SELECT nextval('public.audit_cuentas_detalle_audit_cuenta_detalle_id_seq'::text)";
            $result=$dbconn->Execute($query);
            $consecutivoAudit=$result->fields[0];
            $query = "INSERT INTO audit_cuentas_detalle(
                                                    audit_cuenta_detalle_id,
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
                                                    fecha_registro,
                                                    usuario_id_act,
                                                    fecha_registro_act,
                                                    sw_actualizacion,
                                                    valor_descuento_empresa,
                                                    valor_descuento_paciente,
                                                    porcentaje_gravamen,
                                                    sw_liq_manual,
                                                    servicio_cargo,
                                                    autorizacion_int,
                                                    autorizacion_ext,
                                                    sw_cuota_paciente,
                                                    sw_cuota_moderadora,
                                                    codigo_agrupamiento_id,
                                                    consecutivo,
                                                    sw_cargue,
                                                    justificacion,
                                                    paquete_codigo_id,
                                                    sw_paquete_facturado)
                                            VALUES ($consecutivoAudit,$Dat[transaccion],'$Dat[empresa_id]','$Dat[centro_utilidad]',$Dat[numerodecuenta],'$Dat[departamento]','$Dat[tarifario_id]','$Dat[cargo]',$Dat[cantidad],$Dat[precio],$Dat[valor_cargo],$Dat[valor_nocubierto],$Dat[valor_cubierto],$Dat[usuario_id],$Dat[facturado],'$Dat[fecha_cargo]','$Dat[fecha_registro]',$SystemId,'$FechaAct',2,$Dat[valor_descuento_empresa],$Dat[valor_descuento_paciente],$Dat[porcentaje_gravamen],$Dat[sw_liq_manual],$Dat[servicio_cargo],$int,$ext,$Dat[sw_cuota_paciente],$Dat[sw_cuota_moderadora],$Dat[codigo_agrupamiento_id],$Dat[consecutivo],'".$Dat[sw_cargue]."','".$_REQUEST['observacion']."',$Dat[paquete_codigo_id],$Dat[sw_paquete_facturado])";

            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al audit_cuentas_detalle1";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
            }else{
                $query ="DELETE FROM cuentas_detalle_profesionales WHERE transaccion=".$_REQUEST['Transaccion']."";

                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error  DELETE FROM cuentas_detalle";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }else{
                    $query ="DELETE FROM cuentas_detalle_honorarios WHERE transaccion=".$_REQUEST['Transaccion']."";

                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error  DELETE FROM cuentas_detalle";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }else{
                        $query ="DELETE FROM cuentas_detalle WHERE transaccion=".$_REQUEST['Transaccion']." AND numerodecuenta=".$_REQUEST['Cuenta']."";

                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error  DELETE FROM cuentas_detalle";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                        }else{
                            $dbconn->CommitTrans();
                            $mensaje='El cargo se elimino.';
/*                            if(!$this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['vars'],$_REQUEST['Transaccion'],$_REQUEST['mensaje'],$_REQUEST['Dev'],$_REQUEST['Estado'])){
                                return false;
                            }*/
                          if(!$this->DefinirForma($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['vars'],$_REQUEST['Transaccion'],$mensaje,$_REQUEST['Dev'],$_REQUEST['Estado'],
                                                $_REQUEST['Pieza'],$_REQUEST['doc'],$_REQUEST['numeracion'],$_REQUEST['qx'],$_REQUEST['codigo'],$_REQUEST['des'],$_REQUEST['noFacturado'],$_REQUEST['Consecutivo'])){
                          return false;
                          }
                          return true;
                        }
                    }
                }
            }
        }


       /**
       * Elimina un cargo de la cuenta en tmp_cuenta_detalles.
       * @ access public
       * @ return boolean
       */
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

       /**
       * Elimina un todos los cargos que se acaba de guardar.
       * @ access public
       * @ return boolean
       */
      function EliminarTodosCargos()
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
            $Consecutivo=$_REQUEST['Consecutivo'];

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
                              $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                              $mensaje='Todos los cargos fueron borrados.';
                              if(!$this->FormaMensaje($mensaje,'ELIMINAR TODOS LOS CARGOS',$accion,$boton)){
                                  return false;
                              }
                              return true;
                          }
                          else
                          {
                              if(!$this->Cuenta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso)){
                                  return false;
                              }
                              return true;
                          }
                }
          }
      }


      /**
      * Determina la cantidad de cargos de cirugia que tiene la cirugia_detalle
      * @access public
      * @return int
      * @param int numero del consecutivo de cargos_cirugia
      */
      function CantidadConsecutivos($Consecutivo)
      {
          list($dbconn) = GetDBconn();
          $query = "SELECT * FROM cargos_cirugia WHERE consecutivo='$Consecutivo'";
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $result->Close();
          if($result->EOF){ return 0;  }
          else { return $result->RecordCount(); }
      }

      /**
      * Determina la cantidad de cargos de cirugia que tiene la cirugia_detalle
      * @access public
      * @return int
      * @param int numero del consecutivo de cargos_cirugia
      */
      function CantidadConsecutivosOtros($Consecutivo)
      {
          list($dbconn) = GetDBconn();
          $query = "SELECT * FROM cirugia_otros_cargos WHERE consecutivo='$Consecutivo'";
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $result->Close();
          if($result->EOF){ return 0;  }
          else { return $result->RecordCount(); }
      }


      /**
      * Se encarga de buscar en la tabla diagnostico la descricpion de este teniendo como
      * parametro el numero de diagnostico
      * @access public
      * @return text
      * @param string el diagnostico de la tabla diagnosticos
      */
      /*function BuscarDiagnsotico($Diagnostico)
      {
          list($dbconn) = GetDBconn();
          $query = "SELECT descripcion FROM diagnosticos WHERE diagnostico='$Diagnostico'";
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $result->Close();
          return $result->fields[0];
      }*/

      /**
      * Determina si la cuenta tiene detalle de medicamentos.
      * @access public
      * @return boolean
      * @param int numero de transaccion de la cuenta
      */
     /* function BuscarDetalle($Transaccion)
      {
          list($dbconn) = GetDBconn();
          $query = "select b.empresa_id, b.codigo_producto, e.descripcion,b.cantidad, c.total_costo
                    from bodegas_documentos_d b, bodegas_documentos as c, inv_conceptos as d,
                    inventarios_productos as e where c.transaccion=$Transaccion and b.documento=c.documento and
                    b.empresa_id=c.empresa_id and b.centro_utilidad=c.centro_utilidad and
                    b.bodega=c.bodega and b.prefijo=c.prefijo and c.concepto_inv=d.concepto_inv
                    and d.tipo_mov='E' and b.codigo_producto=e.codigo_producto";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $result->Close();
          if($result->EOF){ return false;   }
          else { return true; }
      }*/

      /**
      * Determina si la cuenta tiene detalle de medicamentos.
      * @access public
      * @return boolean
      * @param int numero de transaccion de la cuenta
      */
      /*function BuscarDetalleDev($Transaccion)
      {
          list($dbconn) = GetDBconn();
          $query = "select b.empresa_id, b.codigo_producto,  e.precio_venta, e.descripcion,b.cantidad, c.total_costo
                    from bodegas_documentos_d b, bodegas_documentos as c, inv_conceptos as d,
                    inventarios_productos as e where c.transaccion=$Transaccion and b.documento=c.documento and
                    b.empresa_id=c.empresa_id and b.centro_utilidad=c.centro_utilidad and
                    b.bodega=c.bodega and b.prefijo=c.prefijo and c.concepto_inv=d.concepto_inv
                    and d.tipo_mov='I' and b.codigo_producto=e.codigo_producto";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $result->Close();
          if($result->EOF){ return false;   }
          else {  return true; }
      }*/


      /**
      * Determina si la cuenta tiene detalle de cirugias.
      * @access public
      * @return boolean
      * @param int numero de transaccion de la cuenta
      */
      function BuscarCirugia($Transaccion)
      {
          list($dbconn) = GetDBconn();
          $query = "SELECT * FROM cirugias WHERE transaccion='$Transaccion'";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $result->Close();
          if($result->EOF){ return false;  }
          else {  return true; }
      }


      /**
      * Llama la forma FormaResultadosDiagnostico que muestra los datos del detalle de
      * diagnosticos de la cuenta
      * @access public
      * @return boolean
      */
      function ResultadosDiagnostico()
      {
          if(!$this->FormaResultadosDiagnostico($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['Transaccion'],$_REQUEST['Datos'])){
             return false;
          }
           return true;
      }

      /**
      *
      */
      function LlamaFormaCuentaCirugias()
      {
          if($_REQUEST['contenedor'])
          {
              $modulo=$_REQUEST['mod'];
              $tipo=$_REQUEST['tipo'];
              $metodo=$_REQUEST['met'];
              $accionQ=ModuloGetURL($_REQUEST['contenedor'],$modulo,$tipo,$metodo);
          }

          if(!$this->FormaCuentaCirugias($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$vars,$_REQUEST['Transaccion'],$_REQUEST['TotalCopago'],$_REQUEST['TotalNo'],$_REQUEST['TotalEmpresa'],$_REQUEST['ValTotal'],$accionQ)){
              return false;
          }
          return true;
      }

      /**
      *
      */
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
               $query = "SELECT  b.nombre_tercero, c.plan_descripcion, e.tipo_id_tercero, e.tercero_id, c.protocolos, d.saldo, c.sw_tipo_plan
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


     /**
      * Busca el detalle de las cirugias de una cuenta.
       * @access public
      * @return boolean
      */
      function DetalleCirugia($Transaccion)
      {
          list($dbconn) = GetDBconn();
          $query = " SELECT a.operacion, a.fecha_cirugia, a.diagnostico_pr, i.quirofano,
                      i.ayudante, i.tipo_id_ayudate, i.anestesista, i.tipo_id_anestesista,
                      i.circulante1, i.tipo_id_circulante1, i.circulante2, i.tipo_id_circulante2,
                      i.instrumentista, i.tipo_id_instrumentista, b.descripcion as desc1, c.diagnostico_nombre,
                      d.consecutivo, d.complicacion,  g.descripcion, h.nombre,
                      d.procedimiento, f.cargo, f.tarifario_id, f.cantidad, f.precio, f.valor_cargo,
                      j.descripcion as desc2, f.transaccion, f.valor_cuota_paciente, f.valor_nocubierto, f.valor_cubierto
                      FROM cirugias as a left join cirugias_quirofano as i
                      on (a.operacion=i.operacion), quirofanos as b,
                      diagnosticos c, cirugias_detalle d, procedimientos_qx e,
                      cargos_cirugia f, vias_acceso_cx g, profesionales h, tarifarios_detalle j
                      WHERE a.transaccion=$Transaccion and i.quirofano=b.quirofano and a.diagnostico_pr=c.diagnostico_id
                      and a.operacion=d.operacion and d.procedimiento=e.procedimiento
                      and d.via_acceso=g.via_acceso and d.consecutivo=f.consecutivo
                      and d.tipo_id_cirujano=h.tipo_id_tercero and d.cirujano=h.tercero_id
                      and e.procedimiento=j.cargo and e.tarifario_id=j.tarifario_id
                      order by d.secuencia,f.consecutivo, f.transaccion";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }

          if($result->EOF)
          {
              //cuando tiene en cirugia_otros_cargos
              $query="select a.operacion, a.fecha_cirugia, a.diagnostico_pr, d.consecutivo,
              d.complicacion, d.procedimiento, c.diagnostico_nombre, g.descripcion,
              j.descripcion as desc2, h.nombre, f.cargo, f.tarifario_id, f.cantidad, f.precio,
              f.valor_cargo, f.transaccion, f.valor_cuota_paciente, f.valor_nocubierto,
              f.valor_cubierto from cirugias as a, diagnosticos c, cirugias_detalle d, vias_acceso_cx g,
              procedimientos_qx e, profesionales as h, cirugias_otros_cargos as f, tarifarios_detalle j
              where a.transaccion=$Transaccion and a.operacion=d.operacion and a.diagnostico_pr=c.diagnostico_id and
              d.via_acceso=g.via_acceso and d.procedimiento=e.procedimiento and
              d.tipo_id_cirujano=h.tipo_id_tercero and d.cirujano=h.tercero_id and
              a.operacion=f.operacion and e.procedimiento=j.cargo and e.tarifario_id=j.tarifario_id
              order by d.secuencia";
              $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }
          }
          if($result->EOF)
          {
              //cuando tiene en cargos_cirugia pero no en cirugia_quirofano
              $query="select a.operacion, a.fecha_cirugia, a.diagnostico_pr, d.consecutivo,
              d.complicacion, d.procedimiento, c.diagnostico_nombre, g.descripcion,
              j.descripcion as desc2, h.nombre, f.cargo, f.tarifario_id, f.cantidad, f.precio,
              f.valor_cargo, f.transaccion, f.valor_cuota_paciente, f.valor_nocubierto,
              f.valor_cubierto
              from cirugias as a, diagnosticos c, cirugias_detalle d, vias_acceso_cx g,
              procedimientos_qx e, profesionales as h, cargos_cirugia as f, tarifarios_detalle j
              where a.transaccion=$Transaccion and a.operacion=d.operacion and
              a.diagnostico_pr=c.diagnostico_id and d.via_acceso=g.via_acceso and
              d.procedimiento=e.procedimiento and d.tipo_id_cirujano=h.tipo_id_tercero and d.cirujano=h.tercero_id
              and d.consecutivo=f.consecutivo and e.procedimiento=j.cargo and e.tarifario_id=j.tarifario_id
              order by d.secuencia,f.consecutivo, f.transaccion";
              $result = $dbconn->Execute($query);
          }
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $i=0;
          while(!$result->EOF)
          {
            $vars[$i]= $result->GetRowAssoc($ToUpper = false);
            $i++;
            $result->MoveNext();
          }
          $result->Close();
          return $vars;
      }

     /**
      * Busca el detalle de las cirugias de una cuenta.
       * @access public
      * @return boolean
      */
      function DetalleCirugiaOtros($Transaccion)
      {
          list($dbconn) = GetDBconn();
          $query = "select a.operacion, a.fecha_cirugia, a.diagnostico_pr, b.cargo,
                    b.tarifario_id, b.cantidad, b.precio, b.valor_cargo, b.transaccion,
                    b.valor_cuota_paciente, b.valor_nocubierto, b.valor_cubierto, c.descripcion
                    from cirugias as a,cirugias_otros_cargos b, tarifarios_detalle as c
                    where a.transaccion=$Transaccion and a.operacion=b.operacion and b.tarifario_id=c.tarifario_id
                    and b.cargo=c.cargo";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $i=0;
          while(!$result->EOF)
          {
            $vars[$i]= $result->GetRowAssoc($ToUpper = false);
            $i++;
            $result->MoveNext();
          }
          $result->Close();
          return $vars;
      }


     /**
     * Busca el nombre y el precio del cargo en la tabla tarifarios_detalle.
     * @access public
     * @return array
     * @param text numero del tarifario
     * @param text id del Cargo
     */
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


      /**
      * Busca el numero de ingreso de una paciente en la table ingresos.
      * @access public
      * @return int
      * @param string tipo de documento
      * @param int numero del documento
      */
      function BuscarIngreso($tipo,$documento,$Estado)
      {
          $Empresa=$_SESSION['CUENTAS']['EMPRESA'];
          $CU=$_SESSION['CUENTAS']['CENTROUTILIDAD'];
          if($Estado=='A'){ $est=1;}
          if($Estado=='I'){ $est=2;}
          if(!$Estado){ $est=0;}
          list($dbconn) = GetDBconn();
          $query = " SELECT a.ingreso
                    FROM ingresos as a, departamentos as b
                    WHERE a.tipo_id_paciente='$tipo' AND a.paciente_id='$documento' AND a.estado=$est AND
                    a.departamento=b.departamento AND b.empresa_id='$Empresa' AND b.centro_utilidad='$CU'";
          $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
          $var=$result->fields[0];
          $result->Close();
          return $var;
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

            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            else{
              if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla maestra 'tipos_id_pacientes' esta vacia ";
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
      * Busca el departamento y su descripcion en la tabla departamentos.
      * @access public
      * @return array
      */
      function Departamentos()
      {
          $EmpresaId=$_SESSION['CUENTAS']['EMPRESA'];
          $CentroU=$_SESSION['CUENTAS']['CENTROUTILIDAD'];
          if($CentroU)
          { $CU="and centro_utilidad='$CentroU'"; }

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
      * Busca el nombre del departamento
      * @access public
      * @return array
      * @param codigo del departamento
      */
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

      /**
      * Busca el  departamento en la tabla tmp_cuentas_detalle que corresponde a un cargo especial
      * @access public
      * @return array
      * @param int numero de la transaccion
      * @param int numero de la cuenta
      */
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

      /**
      *
      */
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

       /**
       * Busca los datos principales del tercero(responsable) nombre y tipo_id_tercero.
       * @access public
       * @return array
       * @param int id del tercero
       */
       function BuscarTercero($TipoTercero,$TerceroId)
       {
            list($dbconn) = GetDBconn();
            $query = "SELECT nombre_tercero,tipo_id_tercero FROM terceros WHERE tercero_id='$TerceroId' AND tipo_id_tercero='$TipoTercero'";
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

      /**
      * La funcion BuscarNombresPaciente se encarga de buscar en la base de datos los nombres de los pacientes.
      * @access public
      * @return array
      * @param string tipo de documento
      * @param int numero de documento
      */
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


      function BuscarNombreCompletoPaciente($tipo,$documento)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombre
                                    FROM pacientes
                                    WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
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
          $Nombres=$result->fields[0];
          $result->Close();
             return $Nombres;
         }


      /**
      * Se encarga de buscar en la base de datos los apellidos de los pacientes.
      * @access public
      * @return array
      * @param string tipo de documento
      * @param int numero de documento
      */
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

      /**
      * Busca el detalle de una cuenta en la tabla cuentas_detalle.
      * @access public
      * @return array
      * @param int numero de Cuenta
      */
      function BuscarDetalleCuenta($Cuenta)
      {
          list($dbconn) = GetDBconn();
          $query = "SELECT  *
                    FROM cuentas_detalle as a 
                    WHERE a.numerodecuenta='$Cuenta'
                    AND a.facturado=1";

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


      /**
      * Busca el detalle de una cuenta en la tabla cuentas_detalle.
      * @access public
      * @return array
      * @param int numero de Cuenta
      */
      function CargosNoFacturados()
      {
                $arre=$this->DetalleCuentaNoFacturados($_REQUEST['Cuenta']);
          if(!$this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$vars,$_REQUEST['Transaccion'],$mensaje,$Dev,$Estado,$arre))
          {
            return false;
          }
        return true;
      }

        /**
        *
        */
        function DetalleCuentaNoFacturados($Cuenta)
        {
                list($dbconn) = GetDBconn();
                $query = "SELECT  transaccion,
                                                cargo,
                                                cantidad,
                                                precio,
                                                valor_nocubierto,
                                                fecha_registro,
                                                fecha_cargo,
                                                tarifario_id,
                                                valor_cubierto,
                                                valor_cargo,
                                                porcentaje_descuento_paciente,
                                                porcentaje_descuento_empresa,
                                                valor_descuento_empresa,
                                                valor_descuento_paciente,
                                                case facturado when 1 then valor_cargo else 0 end as fac,
                                                autorizacion_int as interna,
                                                autorizacion_ext as externa,
                                                codigo_agrupamiento_id,
                                                consecutivo
                            FROM cuentas_detalle WHERE numerodecuenta='$Cuenta' and facturado=0
                            order by codigo_agrupamiento_id";
          $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
          while(!$result->EOF)
          {
              $arre[]=$result->GetRowAssoc($ToUpper = false);
              $result->MoveNext();
          }
          $result->Close();
                return $arre;
        }

      /**
      * Este metodo se encarga de forma una matriz con todos lo datos necesarios para
      * hacer el listado de las cuentas. La busqueda se filtra por departamento(var ambiente)
      * @access public
      * @return array
      */
      function ListadoCuentas($Caja,$TipoCuenta,$Departamento)
      {
          $Caja=$_REQUEST['Caja'];
          $TipoCuenta=$_REQUEST['TipoCuenta'];
          $NUM=$_REQUEST['Of'];
          if(!$NUM)
          {   $NUM='0';   }
          if($Departamento!=-1 AND $Departamento!='')
          {  $dpto="and b.departamento='$Departamento'";  }
          else
          {  $dpto='';}
          $EmpresaId=$_SESSION['CUENTAS']['EMPRESA'];
          $CentroU=$_SESSION['CUENTAS']['CENTROUTILIDAD'];
          $limit=$this->limit;
          if($CentroU && $TipoCuenta=='02')
          { $CU="and a.centro_utilidad_id='$CentroU'"; }

          if($CentroU && $TipoCuenta=='01')
          { $CU="and a.centro_utilidad='$CentroU'"; }

          list($dbconn) = GetDBconn();
          if($_SESSION['CUENTAS']['TIPOCUENTA']=='02')
          {
              $query = " select a.cuenta_pv,a.tipo_tercero_id,a.tercero_id,a.fecha_registro,a.total,b.nombre_tercero
                          from cab_cuenta_pv a, terceros b
                          where a.empresa_id='$EmpresaId' $CU
                          and a.tipo_tercero_id=b.tipo_id_tercero and a.tercero_id=b.tercero_id";
              $result = $dbconn->Execute($query);

              if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
              }
              $i=0;
              while(!$result->EOF)
              {
                  $var[$i]=$result->GetRowAssoc($ToUpper = false);
                  $i++;
                  $result->MoveNext();
              }
            return $var;
          }

          //if($_SESSION['CUENTAS']['TIPOCUENTA']=='01')
          //{       "swcyeuna=>".$_SESSION['CUENTAS']['SWCUENTAS'];
              if($_SESSION['CUENTAS']['SWCUENTAS']=='Cuentas')
              {
                  $query = " select c.descripcion,b.pieza,b.cama,b.numerodecuenta,
                            b.plan_id,b.rango,b.fecha_registro,b.total_cuenta,
                            b.valor_nocubierto,b.tipo_id_paciente,b.paciente_id,
                            b.primer_apellido,b.segundo_apellido,b.primer_nombre,
                            b.segundo_nombre, case b.estado when 1 then 'A' else 'I' end as estado, b.ingreso
                            from (select c.estacion_id,b.*
                            from (select c.pieza,b.* from (select b.cama,a.*
                            from (select a.numerodecuenta, a.plan_id, a.rango, b.ingreso, a.estado, a.fecha_registro,
                            a.total_cuenta, a.valor_nocubierto, c.tipo_id_paciente, c.paciente_id,
                            c.primer_apellido, c.segundo_apellido, c.primer_nombre, c.segundo_nombre
                            from cuentas a, ingresos b, pacientes c
                            where (a.estado=1 or a.estado=2) and b.ingreso=a.ingreso and c.tipo_id_paciente=b.tipo_id_paciente
                            and c.paciente_id=b.paciente_id
                            and a.empresa_id='$EmpresaId' $CU $dpto) as a
                            left join movimientos_habitacion as b on a.numerodecuenta=b.numerodecuenta and b.fecha_egreso is null order by a.ingreso,a.numerodecuenta)
                            as b left join camas as c on b.cama=c.cama) as b left join piezas as
                            c on b.pieza=c.pieza) as b left join estaciones_enfermeria as c on
                            b.estacion_id=c.estacion_id LIMIT $limit OFFSET $NUM";
              }
              elseif($_SESSION['CUENTAS']['SWCUENTAS']=='Cerradas')
              {
                  $query = " select c.descripcion,b.pieza,b.cama,b.numerodecuenta, b.plan_id,b.rango,b.fecha_registro,
                              b.total_cuenta, b.valor_nocubierto,b.tipo_id_paciente,b.paciente_id, b.primer_apellido,
                              b.prefijo||''||b.factura_fiscal  as factura, b.nombre,
                              b.segundo_apellido,b.primer_nombre, b.segundo_nombre, case b.estado when 0 then 'F' end as estado,
                              b.ingreso from (select c.estacion_id,b.* from (select c.pieza,b.* from (select b.cama,a.*
                              from (select a.numerodecuenta, a.plan_id, a.rango, b.ingreso, a.estado, d.fecha_registro,
                              e.factura_fiscal, e.prefijo, f.nombre,
                              a.total_cuenta, a.valor_nocubierto, c.tipo_id_paciente, c.paciente_id, c.primer_apellido,
                              c.segundo_apellido, c.primer_nombre, c.segundo_nombre from cuentas a, ingresos b, pacientes  c,
                              fac_facturas as d, fac_facturas_cuentas as e,  system_usuarios as f
                              where e.numerodecuenta=a.numerodecuenta and d.prefijo=e.prefijo and d.factura_fiscal=e.factura_fiscal
                              and d.usuario_id=f.usuario_id and
                              b.ingreso=a.ingreso and c.tipo_id_paciente=b.tipo_id_paciente and
                              c.paciente_id=b.paciente_id and a.empresa_id='$EmpresaId' $CU $dpto) as a left join movimientos_habitacion as b
                              on a.numerodecuenta=b.numerodecuenta and b.fecha_egreso is null order by a.numerodecuenta) as b
                              left join camas as c on b.cama=c.cama) as b left join piezas as c on b.pieza=c.pieza) as b
                              left join estaciones_enfermeria as c on
                              b.estacion_id=c.estacion_id LIMIT $limit OFFSET $NUM";
              }
              if(!empty($query))
              {
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
              }
              return $vars;
        //  }
      }

      /**
      * Este metodo se encarga de obtener el total de registros encontrados.
      * @access public
      * @return int
      * @param
      * @param
      */
      function RecordSearch($Caja,$TipoCuenta,$Departamento)
      {
          if($Departamento!=-1 AND $Departamento!='')
          {  $dpto="and b.departamento='$Departamento'";  }
          else
          {  $dpto='';}
          $EmpresaId=$_SESSION['CUENTAS']['EMPRESA'];
          $CentroU=$_SESSION['CUENTAS']['CENTROUTILIDAD'];
          if($CentroU && $TipoCuenta=='02')
          { $CU="and a.centro_utilidad_id='$CentroU'"; }

          if($CentroU && $TipoCuenta=='01')
          { $CU="and a.centro_utilidad='$CentroU'"; }

          list($dbconn) = GetDBconn();
          if($TipoCuenta=='02')
          {
              $query = " select a.cuenta_pv,a.tipo_tercero_id,a.tercero_id,a.fecha_registro,a.total,b.nombre_tercero
                          from cab_cuenta_pv a, terceros b
                          where a.empresa_id='$EmpresaId' $CU
                          and a.tipo_tercero_id=b.tipo_id_tercero and a.tercero_id=b.tercero_id";
              $result = $dbconn->Execute($query);

              if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
              }
            $var=$result->RecordCount();
            $result->Close();
            return $var;
          }
          //if($_SESSION['CUENTAS']['TIPOCUENTA']=='01')
          //{
              if($_SESSION['CUENTAS']['SWCUENTAS']=='Cuentas')
              {
                  $query = " select c.descripcion,b.pieza,b.cama,b.numerodecuenta,
                            b.plan_id,b.rango,b.fecha_registro,b.total_cuenta,
                            b.valor_nocubierto,b.tipo_id_paciente,b.paciente_id,
                            b.primer_apellido,b.segundo_apellido,b.primer_nombre,
                            b.segundo_nombre
                            from (select c.estacion_id,b.*
                            from (select c.pieza,b.* from (select b.cama,a.*
                            from (select a.numerodecuenta, a.plan_id, a.rango, a.fecha_registro,
                            a.total_cuenta, a.valor_nocubierto, c.tipo_id_paciente, c.paciente_id,
                            c.primer_apellido, c.segundo_apellido, c.primer_nombre, c.segundo_nombre
                            from cuentas a, ingresos b, pacientes c
                            where (a.estado=1 or a.estado=2) and b.ingreso=a.ingreso and c.tipo_id_paciente=b.tipo_id_paciente
                            and c.paciente_id=b.paciente_id
                            and a.empresa_id='$EmpresaId' $CU $dpto) as a
                            left join movimientos_habitacion as b on a.numerodecuenta=b.numerodecuenta and
                            b.fecha_egreso is null order by a.numerodecuenta)
                            as b left join camas as c on b.cama=c.cama) as b left join piezas as
                            c on b.pieza=c.pieza) as b left join estaciones_enfermeria as c on
                            b.estacion_id=c.estacion_id";
              }
              elseif($_SESSION['CUENTAS']['SWCUENTAS']=='Cerradas')
              {
                  $query = " select c.descripcion,b.pieza,b.cama,b.numerodecuenta, b.plan_id,b.rango,b.fecha_registro,
                              b.total_cuenta, b.valor_nocubierto,b.tipo_id_paciente,b.paciente_id, b.primer_apellido,
                              b.prefijo||''||b.factura_fiscal  as factura, b.nombre,
                              b.segundo_apellido,b.primer_nombre, b.segundo_nombre, case b.estado when 0 then 'F' end as estado,
                              b.ingreso from (select c.estacion_id,b.* from (select c.pieza,b.* from (select b.cama,a.*
                              from (select a.numerodecuenta, a.plan_id, a.rango, b.ingreso, b.estado, b.fecha_registro,
                              e.factura_fiscal, e.prefijo, f.nombre,
                              a.total_cuenta, a.valor_nocubierto, c.tipo_id_paciente, c.paciente_id, c.primer_apellido,
                              c.segundo_apellido, c.primer_nombre, c.segundo_nombre from cuentas a, ingresos b, pacientes  c,
                              fac_facturas as d, fac_facturas_cuentas as e,  system_usuarios as f
                              where e.numerodecuenta=a.numerodecuenta and d.prefijo=e.prefijo and d.factura_fiscal=e.factura_fiscal
                              and d.usuario_id=f.usuario_id and
                              b.ingreso=a.ingreso and c.tipo_id_paciente=b.tipo_id_paciente and
                              c.paciente_id=b.paciente_id and a.empresa_id='$EmpresaId' $CU $dpto) as a left join movimientos_habitacion as b
                              on a.numerodecuenta=b.numerodecuenta and b.fecha_egreso is null order by a.numerodecuenta) as b
                              left join camas as c on b.cama=c.cama) as b left join piezas as c on b.pieza=c.pieza) as b
                              left join estaciones_enfermeria as c on
                              b.estacion_id=c.estacion_id";
              }
              if(!empty($query))
              {
                  $result = $dbconn->Execute($query);

                  if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                  }
                  $vars=$result->RecordCount();
                  $result->Close();
              }
              return $vars;
          //}
      }
      

     /**
      * Se encarga de separar la fecha del formato timestamp
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
          //return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
          return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
       }
     }


     /**
      * Se encarga de separar la hora del formato timestamp
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
      }

//------------BUSQUEDAS PV--------------------


/**
      * Busca la cuenta PV cuando se conoce el tipo y numero de identificacion del tercero.
      * @access public
      * @return array
      * @param int tipo identificacion del tercero
      * @param int numero de documento del tercero
     */
      function RecordSearch1PV($TipoId,$PacienteId)
      {
          $EmpresaId=$_SESSION['CUENTAS']['EMPRESA'];
          $CentroU=$_SESSION['CUENTAS']['CENTROUTILIDAD'];
          if($CentroU)
          { $CU="and b.centro_utilidad_id='$CentroU'"; }

          list($dbconn) = GetDBconn();
          $query = " SELECT a.tipo_id_tercero,a.tercero_id,a.nombre_tercero,b.cuenta_pv,
                      b.fecha_registro,b.total
                      FROM terceros a, cab_cuenta_pv b
                      WHERE a.tipo_id_tercero='$TipoId' AND  a.tercero_id='$PacienteId' AND estado=1
                      AND a.tipo_id_tercero=b.tipo_tercero_id AND  a.tercero_id=b.tercero_id
                      AND b.empresa_id='$EmpresaId' $CU";
          $result=$dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al eliminar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
          }
          $conteo=$result->RecordCount();
          $result->Close();
          return $conteo;
      }



     /**
      * Busca la cuenta PV cuando se conoce el tipo y numero de identificacion del tercero.
      * @access public
      * @return array
      * @param int tipo identificacion del tercero
      * @param int numero de documento del tercero
     */
      function Buscar1PV($TipoId,$PacienteId,$NUM)
      {
          $EmpresaId=$_SESSION['CUENTAS']['EMPRESA'];
          $CentroU=$_SESSION['CUENTAS']['CENTROUTILIDAD'];
          $limit=$this->limit;
          if($CentroU)
          { $CU="and b.centro_utilidad_id='$CentroU'"; }

          list($dbconn) = GetDBconn();
          $query = " SELECT a.tipo_id_tercero,a.tercero_id,a.nombre_tercero,b.cuenta_pv,
                      b.fecha_registro,b.total
                      FROM terceros a, cab_cuenta_pv b
                      WHERE a.tipo_id_tercero='$TipoId' AND  a.tercero_id='$PacienteId' AND estado=1
                      AND a.tipo_id_tercero=b.tipo_tercero_id AND  a.tercero_id=b.tercero_id
                      AND b.empresa_id='$EmpresaId' $CU LIMIT $limit OFFSET $NUM";
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

/**
      * Busca la cuenta PV cuando se conoce el nombre del tercero.
      * @access public
      * @return array
      * @param string nombre del tercero
     */
      function RecordSearch2PV($nombres)
      {
          $EmpresaId=$_SESSION['CUENTAS']['EMPRESA'];
          $CentroU=$_SESSION['CUENTAS']['CENTROUTILIDAD'];
          if($CentroU)
          { $CU="and b.centro_utilidad_id='$CentroU'"; }

          list($dbconn) = GetDBconn();
          $query = " SELECT a.tipo_id_tercero,a.tercero_id,a.nombre_tercero,b.cuenta_pv,
                      b.fecha_registro,b.total
                      FROM terceros a, cab_cuenta_pv b
                      WHERE a.nombre_tercero like '%$nombres%' AND estado=1
                      AND a.tipo_id_tercero=b.tipo_tercero_id AND  a.tercero_id=b.tercero_id
                      AND b.empresa_id='$EmpresaId' $CU";
          $result=$dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al eliminar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
          }
          $conteo=$result->RecordCount();
          $result->Close();
          return $conteo;
      }



     /**
      * Busca la cuenta PV cuando se conoce el nombre del tercero.
      * @access public
      * @return array
      * @param string nombre del tercero
     */
      function Buscar2PV($nombres,$NUM)
      {
          $EmpresaId=$_SESSION['CUENTAS']['EMPRESA'];
          $CentroU=$_SESSION['CUENTAS']['CENTROUTILIDAD'];
          $limit=$this->limit;
          if($CentroU)
          { $CU="and b.centro_utilidad_id='$CentroU'"; }

          list($dbconn) = GetDBconn();
          $query = " SELECT a.tipo_id_tercero,a.tercero_id,a.nombre_tercero,b.cuenta_pv,
                      b.fecha_registro,b.total
                      FROM terceros a, cab_cuenta_pv b
                      WHERE a.nombre_tercero like '%$nombres%' AND estado=1
                      AND a.tipo_id_tercero=b.tipo_tercero_id AND  a.tercero_id=b.tercero_id
                      AND b.empresa_id='$EmpresaId' $CU LIMIT $limit OFFSET $NUM";
          $result=$dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al eliminar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
          }
          $i=0;
          while(!$result->EOF)
          {
              $var[$i]=$result->GetRowAssoc($ToUpper = false);
              $i++;
              $result->MoveNext();
          }
          return $var;
      }

      /**
      *
      */
      function VerAutorizaciones()
      {
            $Transaccion=$_REQUEST['Transaccion'];
            $TipoId=$_REQUEST['TipoId'];
            $PacienteId=$_REQUEST['PacienteId'];
            $Nivel=$_REQUEST['Nivel'];
            $PlanId=$_REQUEST['PlanId'];
            $Pieza=$_REQUEST['Pieza'];
            $Cama=$_REQUEST['Cama'];
            $Fecha=$_REQUEST['Fecha'];
            $Ingreso=$_REQUEST['Ingreso'];
            $Cuenta=$_REQUEST['Cuenta'];
            $Estado=$_REQUEST['Estado'];
            $Ingreso=$_REQUEST['Ingreso'];

            unset($_SESSION['AUTORIZACIONES']['RETORNO']);
            $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
            $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
            $_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='Facturacion';
            $_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
            $_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='Cuenta';
            $_SESSION['AUTORIZACIONES']['RETORNO']['argumentos']=$arreglo;
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['cuenta']=$Cuenta;

            $this->ReturnMetodoExterno('app','Autorizacion','user','DetalleAutorizacion');
            return true;
      }


      /**
      *
      */
      function VerAutorizacionesRealizadas()
      {
            $Transaccion=$_REQUEST['Transaccion'];
            $TipoId=$_REQUEST['TipoId'];
            $PacienteId=$_REQUEST['PacienteId'];
            $Nivel=$_REQUEST['Nivel'];
            $PlanId=$_REQUEST['PlanId'];
            $Pieza=$_REQUEST['Pieza'];
            $Cama=$_REQUEST['Cama'];
            $Fecha=$_REQUEST['Fecha'];
            $Ingreso=$_REQUEST['Ingreso'];
            $Cuenta=$_REQUEST['Cuenta'];
            $Estado=$_REQUEST['Estado'];
            $Ingreso=$_REQUEST['Ingreso'];

            unset($_SESSION['AUTORIZACIONES']['RETORNO']);
            $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
            $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
            $_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='Facturacion';
            $_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
            $_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='Cuenta';
            $_SESSION['AUTORIZACIONES']['RETORNO']['argumentos']=$arreglo;
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['cuenta']=$Cuenta;

            $this->ReturnMetodoExterno('app','Autorizacion','user','DetalleAutorizacionesRealizadas');
            return true;
      }
//----------FIN BUSQUEDA PV--------------------

      /**
      *
      */
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

//----------------------INSUMOS Y MEDICAMENTOS-------------------------------------------

      /**
      *
      */
      function InsertarInsumos()
      {
            IncludeLib("tarifario_cargos");
            $Departamento=$_REQUEST['Departamento'];
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
                if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$D)){
                  return false;
                }
                return true;
            }

            $f = (int) $Cantidad;
            $y = $Cantidad - $f;
            if($y != 0){
                if($y != 0){ $this->frmError["Cantidad"]=1; }
                $mensaje='La Cantidad debe ser entera.';
                if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$D)){
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
                    $precio = $_REQUEST['Precio'];

            if(empty($precio))
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
                        $precio = $results->fields[0] ;
            }
                    //1)determina si es insumo o medicamento
                    //2)pide los datos necesarios
                    //3)valida datos
//              $query = "
//                              SELECT  *
//                              FROM        medicamentos
//                              WHERE       codigo_medicamento = '".$Codigo."'
//              ";
//                  $results = $dbconn->Execute($query);
//                  if ($dbconn->ErrorNo() != 0) {
//                      $this->error = "Error al Cargar el Modulo";
//                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                      return false;
//                  }
//                  $datos = $results->fields[0] ;
//                  if($results->RecordCount() > 0)//medicamento
//                  {
//                      //pido datos AM
//                  }
//                  else// es un insumo
//                  {
//                      //pido datos AT
//                  }

                        //si todo bien

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
                              VALUES($Cuenta,'$Departamento','$bodega','$Codigo',$Cantidad,'$empresa','$cu',".$precio.",'".$_REQUEST['FechaCargo']."',$PlanId,'$Servicio')";

                  $result=$dbconn->Execute($query);
                  if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error al Guardar en la Base de Datos";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      return false;
                  }
                  $mensaje='El insumo se Guardo Correctamente.';
                  if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$D)){
                    return false;
                  }
                  return true;

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
                    $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                    $mensaje='Todos los cargos fueron borrados.';
                    if(!$this->FormaMensaje($mensaje,'ELIMINAR TODOS LOS CARGOS',$accion,$boton)){
                        return false;
                    }
                    return true;
                }
                else
                {
                    if(!$this->Cuenta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso)){
                        return false;
                    }
                    return true;
                }
          }
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
            if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$D)){
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

            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if($result->fields[0]==0)
            {
                $this->frmError["MensajeError"]="NO HA AGREGADO NINGUN INSUMO.";
                if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$D))
                {
                    return false;
                }
                return true;
            }

            $argu=array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);

            $_SESSION['INVENTARIOS']['RETORNO']['contenedor']='app';
            $_SESSION['INVENTARIOS']['RETORNO']['modulo']='Facturacion';
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
                $accion=ModuloGetURL('app','Facturacion','user','LlamarFormaTiposCargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
                if(!$this-> FormaMensaje($mensaje,'CREAR DOCUMENTO',$accion,'')){
                return false;
                }
                return true;
          }
          else
          {
                $mensaje='Los Documentos de Bodega No Fueron Creados.<br>';
                            if(!empty($_SESSION['INVENTARIOS']['RETORNO']['Mensaje_Error']))
                            {  $mensaje.=$_SESSION['INVENTARIOS']['RETORNO']['Mensaje_Error']; }
                            unset($_SESSION['INVENTARIOS']);
                $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
                if(!$this-> FormaMensaje($mensaje,'ERROR AL CREAR EL DOCUMENTO',$accion,'')){            return false;
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

            if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$Datos)){
              return false;
            }
            return true;
        }

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
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];

          if(!$this->FormaBodegas($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha)){
            return false;
          }
          return true;
      }

      /**
      *
      */
      function ModificarCargoTmpIyM()
      {
             IncludeLib("tarifario_cargos");
            $Departamento=$_REQUEST['Departamento'];
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
                if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$_REQUEST['Datos'])){
                  return false;
                }
                return true;
            }

            $f = (int) $Cantidad;
            $y = $Cantidad - $f;
            if($y != 0){
                if($y != 0){ $this->frmError["Cantidad"]=1; }
                $mensaje='La Cantidad debe ser entera.';
                if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$_REQUEST['Datos'])){
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
            if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,'')){
              return false;
            }
            return true;
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
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];
          unset($_SESSION['CUENTA']['BODEGA']);

          if($_REQUEST['Bodegas']==-1){
                if($_REQUEST['Bodegas']==-1){ $this->frmError["Bodegas"]=1; }
                $this->frmError["MensajeError"]="Debe Elegir la Bodega";
                if(!$this->FormaBodegas($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha)){
                  return false;
                }
                return true;
          }
          $_SESSION['CUENTA']['BODEGA']=$_REQUEST['Bodegas'];
          $this->Insumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha);
          return true;
       }

      /**
      * Llama la forma FormaInsumos que insertar nuevos cargos.
      * @access public
      * @return boolean
      */
       function Insumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha)
       {
//
          //unset($_SESSION['CUENTA']['BODEGA']);
echo warning;
         if($_REQUEST['Cuenta'])
         {
          $Cuenta=$_REQUEST['Cuenta'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];
         }
//
          $this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$D,$var);
          return true;
       }

       /**
       *
       */
       function NombreBodega($Bodega)
       {
            list($dbconn) = GetDBconn();
            $query = "SELECT descripcion  FROM bodegas
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
       function DptoBodega($Bodega)
       {
            list($dbconn) = GetDBconn();
            $query = "SELECT b.descripcion,a.departamento,a.sw_solicitar_departamento_al_cargar  FROM bodegas as a, departamentos as b
                WHERE a.bodega='$Bodega' and a.departamento=b.departamento";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $var=$result->GetRowAssoc($ToUpper = false);
            return $var;
       }
//------------------------------CAMBIO RESPONSABLE------------------------------------
      /**
      *
      */
      function CambioResponsable()
      {
          $this->FormaCambioResponsable($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha']);
          return true;
      }

      /**
      *
      */
      function NuevoResponsable()
      {
          if($_REQUEST['Responsable']==-1)
          {
              if($_REQUEST['Responsable']==-1){ $this->frmError["Responsable"]=-1; }
              $this->frmError["MensajeError"]="Debe Elegir el Nuevo Plan.";
              $this->FormaCambioResponsable($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha']);
              return true;
          }

          if($_REQUEST['Responsable']==$_REQUEST['PlanId'])
          {
              if($_REQUEST['Responsable']==-1){ $this->frmError["Responsable"]=-1; }
              $this->frmError["MensajeError"]="Debe Elegir el un Plan Diferente al que ya Tiene la Cuenta.";
              $this->FormaCambioResponsable($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha']);
              return true;
          }

          UNSET($_SESSION['CUENTA']['CAMBIO']);
          $_SESSION['CUENTA']['CAMBIO']['nuevo_plan']=$_REQUEST['Responsable'];
          $_SESSION['CUENTA']['CAMBIO']['indice']=$_REQUEST['indice'];
           
          list($dbconn) = GetDBconn();
          $query = "SELECT  sw_tipo_plan
                    FROM planes
                    WHERE plan_id='".$_REQUEST['Responsable']."'";
          $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
              return false;
          }
          //si no es soat
          if($results->fields[0]!=1)
          {
                unset($_SESSION['SOAT']);
                $this->FormaDatosAfiliado($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha']);
                return true;
          }
          else
          { //si el plan es soat
                $this->LlamarModuloSoat($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha']);
                return true;
          }
      }

      /**
      *
      */
      function LlamarModuloSoat($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha)
      {
          unset($_SESSION['SOAT']);
          $_SESSION['SOAT']['PACIENTE']['paciente_id']=$PacienteId;
          $_SESSION['SOAT']['PACIENTE']['tipo_id_paciente']=$TipoId;
          $_SESSION['SOAT']['CUENTA']=TRUE;
          $_SESSION['SOAT']['RETORNO']['argumentos']=array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
          $_SESSION['SOAT']['RETORNO']['contenedor']='app';
          $_SESSION['SOAT']['RETORNO']['modulo']='Facturacion';
          $_SESSION['SOAT']['RETORNO']['tipo']='user';
          $_SESSION['SOAT']['RETORNO']['metodo']='GuardarNuevoPlan';

          $this->ReturnMetodoExterno('app','Soat','user','SoatAdmision');
          return true;
      }

      /**
      *
      */
      function GuardarNuevoPlan($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha)
      {
          list($dbconn) = GetDBconn();
          //si no es cambio de plan a soat
          if(!empty($_SESSION['SOAT']['RETORNO']))
          {
                $Cuenta=$_REQUEST['Cuenta'];
                $TipoId=$_REQUEST['TipoId'];
                $PacienteId=$_REQUEST['PacienteId'];
                $Nivel=$_REQUEST['Nivel'];
                $PlanId=$_REQUEST['PlanId'];
                $Ingreso=$_REQUEST['Ingreso'];
                $Fecha=$_REQUEST['Fecha'];
                $TipoAfiliado=$_REQUEST['TipoAfiliado'];
                $Nivel=$_REQUEST['Nivel'];

                if(!empty($_SESSION['SOAT']['NOEVENTO']))
                {
                    unset($_SESSION['SOAT']);
                    $mensaje='El Paciente no tiene eventos creados, Debe tener Eventos para el Cambio de Plan.';
                    $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
                    if(!$this-> FormaMensaje($mensaje,'RELIQUIDAR CUENTA No. '.$Cuenta,$accion,'')){
                    return false;
                    }
                    return true;
                }
                else
                {
                    $query = "select * from ingresos_soat
                              where ingreso=$Ingreso and evento=".$_SESSION['SOAT']['RETORNO']['evento']."";
                    $result=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                          $this->error = "Error al Guardar en cambio_responsable";
                          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                          return false;
                    }
                    //si el evento no estaba guardo en ingresos_soat
                    if($result->EOF)
                    {
                        $query = "INSERT INTO ingresos_soat (ingreso,evento)
                                  VALUES($Ingreso,".$_SESSION['SOAT']['RETORNO']['evento'].")";
                        $result=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                              $this->error = "Error al Guardar en cambio_responsable2";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              return false;
                        }
                    }

                    unset($_SESSION['SOAT']);
                    $query="SELECT rango, tipo_afiliado_id
                            FROM planes_rangos
                            WHERE plan_id='".$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']."'";
                    $result=$dbconn->Execute($query);
                    $Nivel=$result->fields[0];
                    $TipoAfiliado=$result->fields[1];
                }
          }

          if(empty($_SESSION['SOAT']['RETORNO']))
          {
              if(empty($PlanId) AND empty($Cuenta))
              {
                  if($_REQUEST['TipoAfiliado']==-1 OR $_REQUEST['Nivel']==-1)
                  {
                      if($_REQUEST['TipoAfiliado']==-1){ $this->frmError["TipoAfiliado"]=-1; }
                      if($_REQUEST['Nivel']==-1){ $this->frmError["Nivel"]=-1; }
                      $this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
                      $this->FormaDatosAfiliado($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha']);
                      return true;
                  }

                  $Cuenta=$_REQUEST['Cuenta'];
                  $TipoId=$_REQUEST['TipoId'];
                  $PacienteId=$_REQUEST['PacienteId'];
                  $Nivel=$_REQUEST['Nivel'];
                  $PlanId=$_REQUEST['PlanId'];
                  $Ingreso=$_REQUEST['Ingreso'];
                  $Fecha=$_REQUEST['Fecha'];
                  $TipoAfiliado=$_REQUEST['TipoAfiliado'];
                  $Nivel=$_REQUEST['Nivel'];
              }
              else
              {
                  $query="SELECT rango, tipo_afiliado_id
                          FROM planes_rangos WHERE plan_id=".$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']."";
                  $result=$dbconn->Execute($query);
                  $Nivel=$result->fields[0];
                  $TipoAfiliado=$result->fields[1];
              }
          }

          $sem=$_REQUEST['Semanas'];
          if(empty($sem))
          { $sem=0; }

          list($dbconn) = GetDBconn();
        /*  $query="select a.cambio_responsable_id, b.cambio_responsable_detalle_actual_id
                  from cambio_Responsable as a, cambio_responsable_detalle_actual as b, cambio_responsable_detalle_nuevo as c
                  where a.numerodecuenta=$Cuenta and
                  b.cambio_responsable_id=a.cambio_responsable_id and b.cambio_responsable_detalle_actual_id=c.cambio_responsable_detalle_actual_id";
        */  $query="select a.cambio_responsable_id from cambio_Responsable as a
                        where a.numerodecuenta=$Cuenta and a.usuario_id_inicio=".UserGetUID()."";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en cambio_responsable3";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
          }

          if(!$result->EOF)
          {
              $query="delete from cambio_Responsable
                      where numerodecuenta=$Cuenta and usuario_id_inicio=".UserGetUID()."";
              $result=$dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al Guardar en cambio_responsable";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
              }
          }

          $query="SELECT nextval('cambio_responsable_cambio_responsable_id_seq')";
          $result=$dbconn->Execute($query);
          $cambio=$result->fields[0];

          $query = "INSERT INTO cambio_responsable(
                                          cambio_responsable_id,
                                          numerodecuenta,
                                          ingreso,
                                          plan_id_actual,
                                          plan_id_nuevo,
                                          usuario_id_inicio,
                                          fecha_registro_inicio,
                                          usuario_id_final,
                                          fecha_registro_final,
                                          tipo_afiliado_id,
                                          rango,
                                          semanas_cotizadas)
          VALUES($cambio,$Cuenta,$Ingreso,$PlanId,".$_SESSION['CUENTA']['CAMBIO']['nuevo_plan'].",".UserGetUID().",now(),0,NULL,'$TipoAfiliado','$Nivel',$sem)";
          $dbconn->BeginTrans();
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en 1cambio_responsable";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
              return false;
          }
                //no es desde division de cuenta
                if(empty($_SESSION['CUENTA']['DIVISION']))
                {
                        $query = "select a.*, c.codigo_agrupamiento_id, b.descripcion,
                                            d.descripcion as descripcion_agru,
                                            d.bodegas_doc_id as bodegas_doc_id_agru,
                                            d.numeracion as numeracion_agru,
                                            d.cuenta_liquidacion_qx_id
                                            from cuentas_detalle as a
                                            LEFT JOIN cuentas_codigos_agrupamiento d ON(a.codigo_agrupamiento_id=d.codigo_agrupamiento_id)
                                            , tarifarios_detalle as b,
                                            grupos_tipos_cargo as c 
                                            where ((a.numerodecuenta=$Cuenta and a.cargo=b.cargo
                                            and a.tarifario_id=b.tarifario_id and
                                            b.grupo_tipo_cargo=c.grupo_tipo_cargo and c.grupo_tipo_cargo!='SYS'
                                            and d.cuenta_liquidacion_qx_id IS NULL))
                                   order by a.codigo_agrupamiento_id         ";
                }
                else
                {
                        $query = "select a.*,
                                        d.descripcion as descripcion_agru,
                                        d.bodegas_doc_id as bodegas_doc_id_agru,
                                        d.numeracion as numeracion_agru,
                                        d.cuenta_liquidacion_qx_id
                                            from tmp_division_cuenta as a
                                            LEFT JOIN cuentas_codigos_agrupamiento d ON(a.codigo_agrupamiento_id=d.codigo_agrupamiento_id)
                                            where a.numerodecuenta=$Cuenta and a.cuenta='".$_SESSION['CUENTA']['CAMBIO']['indice']."' and a.plan_id=".$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']."
                                            order by a.plan_id,a.codigo_agrupamiento_id";
                }
          $resulta = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en cambio_responsable4";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
          }
          while(!$resulta->EOF)
          {
              $Datos[]=$resulta->GetRowAssoc($ToUpper = false);
              $resulta->MoveNext();
          }
          $resulta->Close();
          if(!empty($Datos))
          {
                for($i=0; $i<sizeof($Datos); $i++)
                {
                        if(!empty($Datos[$i][autorizacion_int]) AND $Datos[$i][autorizacion_int]==='0')
                        {   $AutoInt=$Datos[$i][autorizacion_int];   }
                        else
                        {   $AutoInt='NULL';   }
                        if(!empty($Datos[$i][autorizacion_ext]) AND $Datos[$i][autorizacion_ext]==='0')
                        {   $AutoExt=$Datos[$i][autorizacion_ext];  }
                        else
                        {   $AutoExt='NULL';   }

                        if(empty($Datos[$i][codigo_agrupamiento_id]))
                        {   $Datos[$i][codigo_agrupamiento_id]='NULL';   }
                        if(empty($Datos[$i][consecutivo]))
                        {   $Datos[$i][consecutivo]='NULL';   }

                        if(empty($Datos[$i][cuenta_liquidacion_qx_id]))
                        {   $Datos[$i][cuenta_liquidacion_qx_id]='NULL';   }


                         //esta validaciones solamente para los medicamentos


                        if($Datos[$i][consecutivo]==='NULL' && $Datos[$i][cuenta_liquidacion_qx_id]=='NULL'){
                          if(empty($Datos[$i][codigo_agrupamiento_id])){
                            $agrupamiento='NULL';
                          }else{
                            $agrupamiento=$Datos[$i][codigo_agrupamiento_id];
                          }
                        }else{
                          if(in_array($Datos[$i][codigo_agrupamiento_id],$CodigosAgrupamiento['anterior'])){
                            for($cont=0;$cont<sizeof($CodigosAgrupamiento['anterior']);$cont++){
                              if($Datos[$i][codigo_agrupamiento_id]==$CodigosAgrupamiento['anterior'][$cont]){
                                $agrupamiento=$CodigosAgrupamiento['nuevo'][$cont];
                                break;
                              }
                            }
                          }else{
                            if(empty($Datos[$i][bodegas_doc_id_agru]))
                            {   $Datos[$i][bodegas_doc_id_agru]='NULL';   }
                            if(empty($Datos[$i][numeracion_agru]))
                            {   $Datos[$i][numeracion_agru]='NULL';   }
                            $query="SELECT nextval('cuentas_codigos_agrupamiento_codigo_agrupamiento_id_seq')";
                            $result=$dbconn->Execute($query);
                            $Nuevoagrupamiento=$result->fields[0];
                            $query="INSERT INTO cuentas_codigos_agrupamiento(codigo_agrupamiento_id,
                                    descripcion,bodegas_doc_id,numeracion,cuenta_liquidacion_qx_id)
                                    VALUES($Nuevoagrupamiento,'".$Datos[$i][descripcion_agru]."',
                                    ".$Datos[$i][bodegas_doc_id_agru].",".$Datos[$i][numeracion_agru].",".$Datos[$i][cuenta_liquidacion_qx_id].")";

                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                              $this->error = "Error al Guardar en la Base de Datos";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                            }
                            $CodigosAgrupamiento['anterior'][]=$Datos[$i][codigo_agrupamiento_id];
                            $CodigosAgrupamiento['nuevo'][]=$Nuevoagrupamiento;
                            $agrupamiento=$Nuevoagrupamiento;

                          }

                        }
                        //fin validacion

                                    $query = "INSERT INTO cambio_responsable_detalle_actual(
                                                            cambio_responsable_id,
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
                                fecha_registro,
                                valor_descuento_empresa,
                                valor_descuento_paciente,
                                porcentaje_gravamen,
                                sw_liq_manual,
                                servicio_cargo,
                                autorizacion_int,
                                autorizacion_ext,
                                                            sw_cuota_paciente,
                                                            sw_cuota_moderadora,
                                                            codigo_agrupamiento_id,
                                                            consecutivo,
                                                            cargo_cups,
                                                            sw_cargue)
                                            VALUES ($cambio,".$Datos[$i][transaccion].",'".$Datos[$i][empresa_id]."','".$Datos[$i][centro_utilidad]."',$Cuenta,'".$Datos[$i][departamento]."','".$Datos[$i][tarifario_id]."','".$Datos[$i][cargo]."',".$Datos[$i][cantidad].",".$Datos[$i][precio].",".$Datos[$i][valor_cargo].",".$Datos[$i][valor_nocubierto].",".$Datos[$i][valor_cubierto].",".$Datos[$i][usuario_id].",".$Datos[$i][facturado].",'".$Datos[$i][fecha_cargo]."','".$Datos[$i][fecha_registro]."',".$Datos[$i][valor_descuento_empresa].",".$Datos[$i][valor_descuento_paciente].",".$Datos[$i][porcentaje_gravamen].",".$Datos[$i][sw_liq_manual].",".$Datos[$i][servicio_cargo].",$AutoInt,$AutoExt,".$Datos[$i][sw_cuota_paciente].",".$Datos[$i][sw_cuota_moderadora].",$agrupamiento,".$Datos[$i][consecutivo].",'".$Datos[$i][cargo_cups]."','".trim($Datos[$i][sw_cargue])."')";


                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al cuentas_detalle";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                }

          }

          $dbconn->CommitTrans();

                //cuando es division
                if(!empty($_SESSION['CUENTA']['DIVISION']))
                {
                        //cuando tiene cargos la cuenta de division
                        if(!empty($Datos))
                        {
                                $query = "SELECT tarifario_id,cargo,    cambio_responsable_detalle_actual_id
                                                    FROM cambio_responsable_detalle_actual
                                                    WHERE cambio_responsable_id=$cambio AND cargo in('IMD','DIMD')";
                                $resulta = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al cuentas_detalle";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                }
                                if(!$resulta->EOF)
                                {
                                        while(!$resulta->EOF)
                                        {
                                                $imd[]=$resulta->GetRowAssoc($ToUpper = false);
                                                $resulta->MoveNext();
                                        }
                                        $resulta->Close();
                                }
                                //no solo son medicamentos y va a equivalencias
                                if(sizeof($Datos) != sizeof($imd))
                                {

                                        $this->FormaEquivalencias($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha);
                                        return true;
                                }
                                else
                                {       //solo son medicamentos y no va a equivalencias y se debe llenar el vector
                                        $_REQUEST['Cambio']=$cambio;
                                         $_SESSION['CUENTA']['REQUEST']=$_REQUEST;
                                        foreach($_SESSION['CUENTA']['REQUEST'] as $k => $v)
                                        {
                                                if(substr_count($k,'New'))
                                                {   unset($_SESSION['CUENTA']['REQUEST'][$k]);  }
                                        }

                                        for($i=0; $i<sizeof($imd); $i++)
                                        {
                                                $_SESSION['CUENTA']['REQUEST']['New'.$imd[$i]['tarifario_id'].$imd[$i]['cargo'].$imd[$i]['cambio_responsable_detalle_actual_id']]=$imd[$i]['cambio_responsable_detalle_actual_id'].",".$imd[$i]['tarifario_id'].",".$imd[$i]['cargo'];
                                        }

                                        $this->GuardarEquivalenciasDivision();
                                        return true;
                                }

                        }
                        else
                        {   //es solo de abonos
                                $_REQUEST['Cambio']=$cambio;
                                $_SESSION['CUENTA']['REQUEST']=$_REQUEST;
                                foreach($_SESSION['CUENTA']['REQUEST'] as $k => $v)
                                {
                                        if(substr_count($k,'New'))
                                        {
                                            unset($_SESSION['CUENTA']['REQUEST'][$k]);
                                        }
                                }
                                //unset($_SESSION['CUENTA']['REQUEST']);
                                $this->GuardarEquivalenciasDivision();
                                return true;
                        }
                }

          //cuando tiene cargos la cuenta
          if(!empty($x) || !empty($Datos))
          {
              //$this->DetalleCambioACtual($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha);

              $this->FormaEquivalencias($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha);
              return true;
          }
          else
          {
              $this->InsertarSinCargos($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha'],$cambio);
              return true;
          }
      }


      /**
      *
      */
      function InsertarSinCargos($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha,$cambio)
      {
          IncludeLib("tarifario_cargos");
          $PlanId=$_SESSION['CUENTA']['CAMBIO']['nuevo_plan'];
          //ACTUALIZA CAMBIO RESPONSABLE
          list($dbconn) = GetDBconn();
          $query = "update cambio_responsable set
                                      usuario_id_final=".UserGetUID().",
                                      fecha_registro_final=now()
                    where cambio_responsable_id=$cambio";
              $dbconn->BeginTrans();
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }
          //ACTUALIZAR LA CUENTA
          $query = "select *
                    from cambio_responsable
                    where cambio_responsable_id=$cambio";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $vars=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->Close();

          $query = "update cuentas set
                              tipo_afiliado_id='".$vars[tipo_afiliado_id]."',
                              rango='".$vars[rango]."',
                              semanas_cotizadas=".$vars[semanas_cotizadas].",
                              plan_id=".$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']."
                        where numerodecuenta=$Cuenta";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error update cuentas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }

          $dbconn->CommitTrans();
          $mensaje='Se Cambio el Responsable de la Cuenta No. '.$Cuenta;
          $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
          if(!$this-> FormaMensaje($mensaje,'RELIQUIDAR CUENTA No. '.$Cuenta,$accion,'')){
          return false;
          }
          return true;
      }

      function DetalleCambioACtual($PlanId,$Cuenta)
      {
            list($dbconn) = GetDBconn();
            $query = "select a.cambio_responsable_id, b.cambio_responsable_detalle_actual_id, b.tarifario_id, b.cargo,b.cantidad,
                      d.codigo_producto, (CASE WHEN b.consecutivo IS NOT NULL THEN e.descripcion ELSE c.descripcion END) as descripcion
                      from cambio_Responsable as a, cambio_responsable_detalle_actual as b
                      LEFT JOIN cuentas_codigos_agrupamiento f ON (b.codigo_agrupamiento_id=f.codigo_agrupamiento_id)
                      LEFT JOIN bodegas_documentos_d d ON (b.consecutivo=d.consecutivo AND f.bodegas_doc_id=d.bodegas_doc_id AND f.numeracion=d.numeracion)
                      LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
                      join tarifarios_detalle as c on (b.cargo=c.cargo and b.tarifario_id=c.tarifario_id)
                      where a.numerodecuenta=$Cuenta and a.plan_id_actual=$PlanId and
                      b.cambio_responsable_id=a.cambio_responsable_id  and a.usuario_id_inicio=".UserGetUID()."";
            $result=$dbconn->Execute($query);
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

      function DetalleCambioNuevo($PlanId,$Cuenta)
      {
            list($dbconn) = GetDBconn();
            $query = "select a.cambio_responsable_id, b.cambio_responsable_detalle_actual_id, b.tarifario_id, b.cargo, c.descripcion
                      from cambio_Responsable as a, cambio_responsable_detalle_actual as b
                      left join tarifarios_detalle as c on (b.cargo=c.cargo and b.tarifario_id=c.tarifario_id)
                      where a.numerodecuenta=$Cuenta and a.plan_id_nuevo=$PlanId and
                      b.cambio_responsable_id=a.cambio_responsable_id  and a.usuario_id_inicio=".UserGetUID()."";
            $result=$dbconn->Execute($query);
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


      /**
      *
      */
      function Equivalencias($PlanId,$Cuenta,$cargo,$tarifario)
      {
            list($dbconn) = GetDBconn();
            $query = "SELECT distinct a.cargo as cargoact, a.tarifario_id as tarifarioact,
                      b.descripcion as desact, b.grupo_tarifario_id as grupoact, b.subgrupo_tarifario_id as subact, d.grupo_tarifario_id as gruponew,
                      d.subgrupo_tarifario_id as subnew, e.cargo as cargocups, e.tarifario_id as tarifariocups, q.cargo as cargonew, q.tarifario_id as tarifarionew,
                      g.descripcion as desnew
                      FROM tarifarios_detalle as b left join tarifarios_equivalencias as e on (b.cargo=e.cargo and b.tarifario_id=e.tarifario_id)
                      left join cups as p on(e.cargo_base=p.cargo)
                      left join tarifarios_equivalencias as q on (q.cargo_base=p.cargo)
                      left join tarifarios_detalle as g on(q.cargo=g.cargo and q.tarifario_id=g.tarifario_id),
                                        cambio_responsable_detalle_actual as a,
                      plan_tarifario as c
                      left join plan_tarifario as d on (d.plan_id=".$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']."
                      and d.grupo_tarifario_id=c.grupo_tarifario_id and d.subgrupo_tarifario_id=c.subgrupo_tarifario_id)
                      WHERE a.numerodecuenta=$Cuenta and a.cargo='$cargo'
                      and a.tarifario_id='$tarifario' and c.plan_id=$PlanId
                      and b.grupo_tarifario_id=c.grupo_tarifario_id
                      and b.subgrupo_tarifario_id=c.subgrupo_tarifario_id and a.cargo=b.cargo and a.tarifario_id=b.tarifario_id";
          $resulta = $dbconn->Execute($query);
            if(!$resulta->EOF)
            {
                while(!$resulta->EOF)
                {
                    $vars[]=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                }
            }
            $resulta->Close();
            return $vars;
      }

        /**
        *
        */
        function ValidarContratoEqui($tarifario,$cargo,$plan)
        {
              list($dbconn) = GetDBconn();

              $query = "(   select r.plan_id
                                                    from tarifarios_detalle as h, plan_tarifario as r
                                                    where h.cargo='$cargo' and h.tarifario_id='$tarifario'
                                                    and r.plan_id=$plan and h.grupo_tarifario_id=r.grupo_tarifario_id
                                                    and h.subgrupo_tarifario_id=r.subgrupo_tarifario_id
                                                    and h.tarifario_id=r.tarifario_id
                                                    and excepciones(r.plan_id,h.tarifario_id,h.cargo)=0
                                            )
                                            UNION
                                            (
                                                    SELECT b.plan_id
                                                    FROM tarifarios_detalle a, excepciones b, subgrupos_tarifarios e
                                                    WHERE a.cargo='$cargo' and a.tarifario_id='$tarifario'
                                                    and b.plan_id = $plan AND
                                                    b.tarifario_id = a.tarifario_id AND
                                                    b.sw_no_contratado = 0 AND
                                                    b.cargo = a.cargo AND
                                                    e.grupo_tarifario_id = a.grupo_tarifario_id AND
                                                    e.subgrupo_tarifario_id = a.subgrupo_tarifario_id
                        )";
              $result=$dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error al Guardar en la Tabal autorizaiones";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      return false;
              }

              if(!$result->EOF)
              {   $var=$result->RecordCount();  }

              return $var;
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
                      WHERE b.plan_id='".$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']."'
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
            *
            */
            function BuscarTipoAfiliado($cuenta)
            {
            list($dbconn) = GetDBconn();
            $query = "SELECT a.tipo_afiliado_nombre, b.rango
                      FROM tipos_afiliado as a, cuentas as b
                      WHERE b.numerodecuenta=$cuenta and b.tipo_afiliado_id=a.tipo_afiliado_id";
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
                    WHERE plan_id='".$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']."'";
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
          return $niveles;
       }

       /*
       *
       */
       function NombrePlan($plan)
       {
            list($dbconn) = GetDBconn();
             $query="SELECT plan_descripcion,tipo_tercero_id,tercero_id FROM planes  WHERE plan_id=$plan";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }else{
              $datos=$result->GetRowAssoc($ToUpper = false);
            }
            return $datos;
       }

       /**
       *
       */
       function CancelarCambio()
       {
            $Cuenta=$_REQUEST['Cuenta'];
            $TipoId=$_REQUEST['TipoId'];
            $PacienteId=$_REQUEST['PacienteId'];
            $Nivel=$_REQUEST['Nivel'];
            $PlanId=$_REQUEST['PlanId'];
            $Ingreso=$_REQUEST['Ingreso'];
            $Fecha=$_REQUEST['Fecha'];
            $TipoAfiliado=$_REQUEST['TipoAfiliado'];
            $Nivel=$_REQUEST['Nivel'];

            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();
            //para los agrupamientos nuevos que se crearon
            $query="SELECT DISTINCT a.codigo_agrupamiento_id
                         FROM cambio_responsable_detalle_actual a, cuentas_codigos_agrupamiento b
                         WHERE a.numerodecuenta=$Cuenta
                         AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id
                         AND (a.consecutivo IS NOT NULL OR b.cuenta_liquidacion_qx_id IS NOT NULL)";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
              return false;
            }else{
              while(!$result->EOF){
                $vector[]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
              }
              for($i=0;$i<sizeof($vector);$i++){
                $query="UPDATE cambio_responsable_detalle_actual SET codigo_agrupamiento_id=NULL
                        WHERE codigo_agrupamiento_id=".$vector[$i]['codigo_agrupamiento_id']."";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al Cargar el Modulo";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  $dbconn->RollbackTrans();
                  return false;
                }
              }
              //fin
              $query="DELETE FROM cambio_Responsable
                      WHERE numerodecuenta=$Cuenta";
              $result=$dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
              }
              for($i=0;$i<sizeof($vector);$i++){
                    $query="DELETE FROM cuentas_codigos_agrupamiento
                    WHERE codigo_agrupamiento_id=".$vector[$i]['codigo_agrupamiento_id']."";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al Cargar el Modulo";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  $dbconn->RollbackTrans();
                  return false;
                }
              }
              $query =" DELETE FROM tmp_division_cuenta WHERE numerodecuenta=".$Cuenta."";
              $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error DELETE FROM tmp_division_cuenta ";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      $dbconn->RollbackTrans();
                      return false;
              }

              $query =" DELETE FROM tmp_division_cuenta_abonos WHERE numerodecuenta=".$Cuenta."";
              $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error DELETE FROM tmp_division_cuenta_abonos ";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      $dbconn->RollbackTrans();
                      return false;
              }
            }
            $dbconn->CommitTrans();
            $this->Cuenta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$Estado);
            return true;
       }

      /**
      *
      */
      function InsertarNuevoPlan()
      {
            $Cuenta=$_REQUEST['Cuenta'];
            $TipoId=$_REQUEST['TipoId'];
            $PacienteId=$_REQUEST['PacienteId'];
            $Nivel=$_REQUEST['Nivel'];
            $PlanId=$_REQUEST['PlanId'];
            $Ingreso=$_REQUEST['Ingreso'];
            $Fecha=$_REQUEST['Fecha'];
            unset($_SESSION['CUENTA']['REQUEST']);
            $f=$d=0;

            foreach($_REQUEST as $k => $v)
            {
              if(substr_count($k,'New'))
              {
                if(!empty($v))
                { $f=1; $d++;}
              }
            }

            if($f==0)
            {
                $this->frmError["MensajeError"]="Debe Elegir los Cargos Equivalentes.";
                $this->FormaEquivalencias($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha);
                return true;
            }

            $_SESSION['CUENTA']['REQUEST']=$_REQUEST;
            if($_REQUEST['Cant'] > $d)
            {
                $mensaje='Existe '.($_REQUEST['Cant']-$d).' Cargos de Equivalencia sin elegir, Esta seguro de Continuar.';
                $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
                $c='app';
                $m='Facturacion';
                $me='GuardarEquivalencias';
                $me2='LlamarFormaEquivalencias';
                $Titulo='CAMBIO DE RESPONSBALE CUENTA No. '.$Cuenta;
                $boton1='ACEPTAR';
                $boton2='CANCELAR';

                $this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
                return true;
            }
            else
            {
                $this->GuardarEquivalencias();
                return true;
            }
      }

      /**
      *
      */
      function GuardarEquivalencias()
      {
                //--------revisa si es division para ir a otro metodo----------
                if(!empty($_SESSION['CUENTA']['DIVISION']))
                {
                        $_REQUEST=$_SESSION['CUENTA']['REQUEST'];
                        $this->GuardarEquivalenciasDivision();
                        return true;
                }
                //-----------fin desde division--------------------------------

          IncludeLib("tarifario_cargos");
          IncludeLib("funciones_facturacion");
          $_REQUEST=$_SESSION['CUENTA']['REQUEST'];
          $Cuenta=$_REQUEST['Cuenta'];          
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $Plan=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];
          $cambio=$_REQUEST['Cambio'];

          $PlanId=$_SESSION['CUENTA']['CAMBIO']['nuevo_plan'];
          //ACTUALIZA CAMBIO RESPONSABLE
          list($dbconn) = GetDBconn();
          $query = "update cambio_responsable set
                                      usuario_id_final=".UserGetUID().",
                                      fecha_registro_final=now()
                    where cambio_responsable_id=$cambio";
          $dbconn->BeginTrans();
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }
          //ACTUALIZAR LA CUENTA
          $query = "select *
                    from cambio_responsable
                    where cambio_responsable_id=$cambio";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $vars=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->Close();

          $query = "update cuentas set
                              tipo_afiliado_id='".$vars[tipo_afiliado_id]."',
                              rango='".$vars[rango]."',
                              semanas_cotizadas=".$vars[semanas_cotizadas].",
                              plan_id=".$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']."
                        where numerodecuenta=$Cuenta";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error update cuentas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }

          //guardar en la de lo nuevo
          $_REQUEST=$_SESSION['CUENTA']['REQUEST'];
          foreach($_REQUEST as $k => $v)
          {
              if(substr_count($k,'New'))
              {
                                    $vars='';
                    $n=explode(',',$v);

                    $query = "select b.*
                              from cambio_responsable_detalle_actual as b
                              where b.cambio_responsable_detalle_actual_id=$n[0]";
                    $result=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error al Guardar";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      return false;
                    }
                                    $vars=$result->GetRowAssoc($ToUpper = false);
                    $result->Close();

                    if(empty($vars[autorizacion_int]))
                    {   $vars[autorizacion_int]='NULL';   }
                    if(empty($vars[autorizacion_ext]))
                    {   $vars[autorizacion_ext]='NULL';   }

                                    if(empty($vars[consecutivo]))
                    {   $vars[consecutivo]='NULL';   }
                                    if(empty($vars[codigo_agrupamiento_id]))
                                    {  $vars[codigo_agrupamiento_id]='NULL';  }
                    $Cargo=$n[2];
                    $TarifarioId=$n[1];
//----------------------------esto es para los calculos-------------------------
                    $Liq=LiquidarCargoCuenta($Cuenta,$TarifarioId,$Cargo,$vars[cantidad],0,0,false,false,'',$Servicio,$PlanId,'','','',true,'','',0,true);
                                    $query = "INSERT INTO cambio_responsable_detalle_nuevo(
                                                    cambio_responsable_detalle_actual_id,
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
                                                    fecha_registro,
                                                    valor_descuento_empresa,
                                                    valor_descuento_paciente,
                                                    porcentaje_gravamen,
                                                    sw_liq_manual,
                                                    servicio_cargo,
                                                    autorizacion_int,
                                                    autorizacion_ext,
                                                    sw_cuota_paciente,
                                                    sw_cuota_moderadora,
                                                    codigo_agrupamiento_id,
                                                    consecutivo,
                                                    cargo_cups,
                                                    sw_cargue)
                                            VALUES (".$vars[cambio_responsable_detalle_actual_id].",".$vars[transaccion].",'".$vars[empresa_id]."','".$vars[centro_utilidad]."',$Cuenta,'".$vars[departamento]."','$TarifarioId','$Cargo',".$vars[cantidad].",".$Liq[precio_plan].",".$Liq[valor_cargo].",".$Liq[valor_no_cubierto].",".$Liq[valor_cubierto].",".$vars[usuario_id].",".$Liq[facturado].",'".$vars[fecha_cargo]."','".$vars[fecha_registro]."',".$Liq[valor_descuento_empresa].",".$Liq[valor_descuento_paciente].",".$Liq[porcentaje_gravamen].",".$vars[sw_liq_manual].",".$vars[servicio_cargo].",".$vars[autorizacion_int].",".$vars[autorizacion_ext].",".$Liq[sw_cuota_paciente].",".$Liq[sw_cuota_moderadora].",  ".$vars[codigo_agrupamiento_id].",".$vars[consecutivo].",'".$vars[cargo_cups]."','".trim($vars[sw_cargue])."')";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al cambio_responsable_detalle_nuevo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }

                                    $query = "UPDATE cuentas_detalle SET
                                                                        empresa_id='".$vars[empresa_id]."',
                                                                        centro_utilidad='".$vars[centro_utilidad]."',
                                                                        numerodecuenta=$Cuenta,
                                                                        departamento='".$vars[departamento]."',
                                                                        tarifario_id='$TarifarioId',
                                                                        cargo='$Cargo',
                                                                        cantidad=".$vars[cantidad].",
                                                                        precio=".$Liq[precio_plan].",
                                                                        valor_cargo=".$Liq[valor_cargo].",
                                                                        valor_nocubierto=".$Liq[valor_no_cubierto].",
                                                                        valor_cubierto=".$Liq[valor_cubierto].",
                                                                        usuario_id=".$vars[usuario_id].",
                                                                        facturado=".$Liq[facturado].",
                                                                        fecha_cargo='".$vars[fecha_cargo]."',
                                                                        valor_descuento_empresa=".$Liq[valor_descuento_paciente].",
                                                                        valor_descuento_paciente=".$Liq[valor_descuento_empresa].",
                                                                        servicio_cargo=".$vars[servicio_cargo].",
                                                                        autorizacion_int=".$vars[autorizacion_int].",
                                                                        autorizacion_ext=".$vars[autorizacion_ext].",
                                                                        porcentaje_gravamen=".$Liq[porcentaje_gravamen].",
                                                                        sw_cuota_paciente='".$Liq[sw_cuota_paciente]."',
                                                                        sw_cuota_moderadora='".$Liq[sw_cuota_moderadora]."',
                                                                        codigo_agrupamiento_id=".$vars[codigo_agrupamiento_id].",
                                                                        consecutivo=".$vars[consecutivo].",
                                                                        fecha_registro='".$vars[fecha_registro]."',
                                                                        cargo_cups='".$vars[cargo_cups]."',
                                                                        sw_cargue='3'
                                                        WHERE transaccion=".$vars[transaccion]."";
                                    $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                    }
              }
          }
            $dbconn->CommitTrans();
          $mensaje='Se Cambio el Responsable de la Cuenta No. '.$Cuenta;
          $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
          if(!$this-> FormaMensaje($mensaje,'RELIQUIDAR CUENTA No. '.$Cuenta,$accion,'')){
                return false;
          }
          return true;
       }

        function GuardarEquivalenciasDivision()
        {
                IncludeLib("tarifario_cargos");
                IncludeLib("funciones_facturacion");
                $cambio=$_REQUEST['Cambio'];
                $_REQUEST=$_SESSION['CUENTA']['REQUEST'];
                $CuentaAnt=$_REQUEST['Cuenta'];
                $TipoId=$_REQUEST['TipoId'];
                $PacienteId=$_REQUEST['PacienteId'];
                $Nivel=$_REQUEST['Nivel'];
                $Plan=$_REQUEST['PlanId'];
                $Ingreso=$_REQUEST['Ingreso'];
                $Fecha=$_REQUEST['Fecha'];
                if(empty($cambio))
                {  $cambio=$_REQUEST['Cambio'];  }
                $PlanId=$_SESSION['CUENTA']['CAMBIO']['nuevo_plan'];

                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
          //ACTUALIZAR LA CUENTA
          $query = "select * from cambio_responsable where cambio_responsable_id=$cambio";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $vars=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->Close();


          //consulta para saber si en la cuenta anterior liquido manualmente habitaciones
          $query = "SELECT sw_liquidacion_manual_habitaciones FROM cuentas WHERE numerodecuenta=$CuentaAnt";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $Habita=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->Close();
          //fin consulta


                $query="SELECT nextval('cuentas_numerodecuenta_seq')";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al traer la secuencia cuentas_numerodecuenta_seq ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
                $Cuenta=$result->fields[0];
                if(empty($vars[semanas_cotizadas]))
                {  $vars[semanas_cotizadas]=0;  }

                $query = "INSERT INTO cuentas (numerodecuenta,
                                                                                empresa_id,
                                                                                centro_utilidad,
                                                                                ingreso,
                                                                                plan_id,
                                                                                estado,
                                                                                usuario_id,
                                                                                fecha_registro,
                                                                                tipo_afiliado_id,
                                                                                rango,
                                                                                autorizacion_int,
                                                                                autorizacion_ext,
                                                                                semanas_cotizadas,
                                                                                sw_estado_paciente,
                                                                                fecha_cierre,
                                                                                usuario_cierre,
                                                                                sw_liquidacion_manual_habitaciones)
                                    VALUES($Cuenta,'".$_SESSION['CUENTAS']['EMPRESA']."','".$_SESSION['CUENTAS']['CENTROUTILIDAD']."',
                                    $Ingreso,".$_SESSION['CUENTA']['CAMBIO']['nuevo_plan'].",'2','".UserGetUID()."','now()',
                                    '".$vars[tipo_afiliado_id]."','".$vars[rango]."',NULL,NULL,".$vars[semanas_cotizadas].",0,NULL,NULL,
                                    '".$Habita['sw_liquidacion_manual_habitaciones']."')";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error cuentas";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->lineError = __LINE__;
                                $dbconn->RollbackTrans();
                                return false;
                }
                            //exit;
                $afiliado = $vars[tipo_afiliado_id];
                $rango = $vars[rango];
                $sem = $vars[semanas_cotizadas];

                //ACTUALIZA CAMBIO RESPONSABLE
                $query = "update cambio_responsable set
                                                                        usuario_id_final=".UserGetUID().",
                                                                        fecha_registro_final=now()
                                    where cambio_responsable_id=$cambio";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }

                //guardar en la de lo nuevo
                $_REQUEST=$_SESSION['CUENTA']['REQUEST'];
                foreach($_REQUEST as $k => $v)
                {
                        if(substr_count($k,'New'))
                        {
                                    $vars='';
                                    $n=explode(',',$v);

                                    $query = "select b.*
                                              from cambio_responsable_detalle_actual as b
                                              where b.cambio_responsable_detalle_actual_id=$n[0]

                                              ";

                                    $result=$dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                                    }
                                    $vars=$result->GetRowAssoc($ToUpper = false);
                                    $result->Close();

                                    if(empty($vars[autorizacion_int]))
                                    {   $vars[autorizacion_int]='NULL';   }
                                    if(empty($vars[autorizacion_ext]))
                                    {   $vars[autorizacion_ext]='NULL';   }

                                    if(empty($vars[consecutivo]))
                                    {   $vars[consecutivo]='NULL';   }
                                    if(empty($vars[codigo_agrupamiento_id]))
                                    {  $vars[codigo_agrupamiento_id]='NULL';  }
                                    $Cargo=$n[2];
                                    $TarifarioId=$n[1];
//----------------------------esto es para los calculos-------------------------
                                    //esto es para eliminar de la tablas cuentas codigo agrupamiento
                                    //los agrupamientos q ya no tengan o no agrupen registros en la
                                    //anterior cuenta
                                    $query = "select codigo_agrupamiento_id
                                          from cuentas_detalle
                                          where transaccion=".$vars[transaccion]."
                                          and numerodecuenta=".$CuentaAnt."";

                                    $result=$dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                                    }
                                    if($result->fields[0]){
                                      $agrupamientoAnt=$result->fields[0];
                                    }


                                    //fin
                                    //nuevo cambio por lo de paquetes pues a la nueva cuenta
                                    //llegan sin paquete  
                                    $query = "UPDATE cuentas_detalle SET
                                                                                        numerodecuenta=$Cuenta,
                                                                                        tarifario_id='$TarifarioId',
                                                                                        cargo='$Cargo',
                                                                                        codigo_agrupamiento_id=".$vars[codigo_agrupamiento_id].",
                                                                                        paquete_codigo_id=NULL,
                                                                                        sw_paquete_facturado=NULL
                                                        WHERE transaccion=".$vars[transaccion]."
                                                        and numerodecuenta=".$CuentaAnt."";

                                    $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                    }

                                    $query = "UPDATE audit_cuentas_detalle
                                              SET numerodecuenta=$Cuenta,
                                              tarifario_id='$TarifarioId',
                                              cargo='$Cargo',
                                              codigo_agrupamiento_id=".$vars[codigo_agrupamiento_id].",
                                              paquete_codigo_id=NULL,
                                              sw_paquete_facturado=NULL
                                              WHERE transaccion=".$vars[transaccion]."
                                              AND numerodecuenta=".$CuentaAnt."";

                                    $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                    }




                                    //fin cambio
                        /*          $cargos[]=array('tarifario_id'=>$TarifarioId,'cargo'=>$Cargo,'cantidad'=>$vars[cantidad],'autorizacion_int'=>'','autorizacion_ext'=>'');
                                    $Liq= LiquidarCargosCuentaVirtual($cargos,array(),array(),array(), $_SESSION['CUENTA']['CAMBIO']['nuevo_plan'], $afiliado, $rango,$sem, $Servicio, $TipoId, $PacienteId);
                                echo    $query = "UPDATE cuentas_detalle SET
                                                                        empresa_id='".$vars[empresa_id]."',
                                                                        centro_utilidad='".$vars[centro_utilidad]."',
                                                                        numerodecuenta=$Cuenta,
                                                                        departamento='".$vars[departamento]."',
                                                                        tarifario_id='$TarifarioId',
                                                                        cargo='$Cargo',
                                                                        cantidad=".$vars[cantidad].",
                                                                        precio=".$Liq[cargos][0][precio_plan].",
                                                                        valor_cargo=".$Liq[cargos][0][valor_cargo].",
                                                                        valor_nocubierto=".$Liq[cargos][0][valor_no_cubierto].",
                                                                        valor_cubierto=".$Liq[cargos][0][valor_cubierto].",
                                                                        usuario_id=".$vars[usuario_id].",
                                                                        facturado=".$Liq[cargos][0][facturado].",
                                                                        fecha_cargo='".$vars[fecha_cargo]."',
                                                                        valor_descuento_empresa=".$Liq[cargos][0][valor_descuento_paciente].",
                                                                        valor_descuento_paciente=".$Liq[cargos][0][valor_descuento_empresa].",
                                                                        servicio_cargo=".$vars[servicio_cargo].",
                                                                        autorizacion_int=".$vars[autorizacion_int].",
                                                                        autorizacion_ext=".$vars[autorizacion_ext].",
                                                                        porcentaje_gravamen=".$Liq[cargos][0][porcentaje_gravamen].",
                                                                        sw_cuota_paciente='".$Liq[cargos][0][sw_cuota_paciente]."',
                                                                        sw_cuota_moderadora='".$Liq[cargos][0][sw_cuota_moderadora]."',
                                                                        codigo_agrupamiento_id=".$vars[codigo_agrupamiento_id].",
                                                                        consecutivo=".$vars[consecutivo].",
                                                                        fecha_registro='".$vars[fecha_registro]."',
                                                                        cargo_cups='".$vars[cargo_cups]."',
                                                                        sw_cargue='3'
                                                        WHERE transaccion=".$vars[transaccion]."";*/


                                    if($agrupamientoAnt){
                                      $query = "SELECT *
                                            FROM  cuentas_detalle b
                                            WHERE b.codigo_agrupamiento_id=".$agrupamientoAnt."
                                            AND numerodecuenta=".$CuentaAnt."
                                            ";

                                      $resultadoAgrupa=$dbconn->Execute($query);
                                      if ($dbconn->ErrorNo() != 0) {
                                          $this->error = "Error al Guardar";
                                          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                          return false;
                                      }
                                      if($resultadoAgrupa->RecordCount()<1){
                                        $EliminaCuantasAgrupa[]=$agrupamientoAnt;
                                      }
                                    }

                        }
                }
                //hice esta actualizacion para q se dispare el trigger q actualiza los valores totales de
                //la cuenta vieja pues no se estaban actualizando
                $query = "UPDATE cuentas_detalle
                               SET numerodecuenta=$CuentaAnt
                               WHERE numerodecuenta=".$CuentaAnt."";

                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }

                //-------------GUARDAR LOS ABONOS-----------------
                $abono=$this->DivisionAbonosCuenta('',$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']);
                for($d=0; $d<sizeof($abono); $d++)
                {
                        $query ="UPDATE rc_detalle_hosp SET numerodecuenta=$Cuenta
                                            WHERE prefijo='".$abono[$d][prefijo]."' AND recibo_caja=".$abono[$d][recibo_caja]."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error UPDATE rc_detalle_hosp ";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                return false;
                        }
                }
                //--------------FIN ABONOS-----------------------

                $query =" DELETE FROM tmp_division_cuenta
                                    WHERE numerodecuenta=".$_REQUEST['Cuenta']." and plan_id=".$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error DELETE FROM tmp_division_cuenta ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }

                $query =" DELETE FROM tmp_division_cuenta_abonos
                                    WHERE numerodecuenta=".$_REQUEST['Cuenta']." and plan_id=".$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error DELETE FROM tmp_division_cuenta ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }

                for($c=0;$c<sizeof($EliminaCuantasAgrupa);$c++){
                  $query = "SELECT *
                            FROM cuentas_codigos_agrupamiento
                            WHERE codigo_agrupamiento_id =".$EliminaCuantasAgrupa[$c]."";
                  $result=$dbconn->Execute($query);
                  if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error al Guardar";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      return false;
                  }else{
                    if($result->RecordCount()<1){ 
                      $query = "DELETE FROM cuentas_codigos_agrupamiento WHERE codigo_agrupamiento_id =".$EliminaCuantasAgrupa[$c]."";
    
                      $dbconn->Execute($query);
                      if ($dbconn->ErrorNo() != 0) {
                          $this->error = "Error al Guardar";
                          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                          $dbconn->RollbackTrans();
                          return false;
                      }
                    }
                  }    
                }
                //va a reliquidar
                $this->Reliquidar($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,1);
                $dbconn->CommitTrans();
                //--------------GUARDA LA CUENTA-----------------
                    $_SESSION['DIVISION']['CUENTA'][]=array('cuenta'=>$Cuenta,'plan'=>$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']);
                    unset($_SESSION['CUENTA']['CAMBIO']['nuevo_plan']);
                //------valida si hay mas planes para dividir la cuenta
                
                $det = $this->DetalleNuevo($_REQUEST['Cuenta']);                
                for($i=0; $i<sizeof($det); $i++){
                  if($det[$i]['cuenta']!='0'){
                    $_REQUEST['Responsable']=$det[$i]['plan_id'];
                    $_REQUEST['indice']=$det[$i]['cuenta'];
                    $_REQUEST['descripcion_plan']=$det[$i]['plan_descripcion'];
                    $this->FormaCuentaGenerada($det[$i]['plan_id'],$det[$i]['plan_descripcion'],$_REQUEST['Cuenta']);
                    return true;
                  }                   
                }                        
                $det = $this->DivisionSoloAbonosCuenta($_REQUEST['Cuenta'],'');
                for($i=0; $i<sizeof($det); $i++){
                  if($det[$i]['cuenta']!='0'){                
                    $_REQUEST['Responsable']=$det[$i]['plan_id'];
                    $_REQUEST['descripcion_plan']=$det[$i]['plan_descripcion'];
                    $_REQUEST['indice']=$det[$i]['cuenta'];
                    $this->FormaCuentaGenerada($det[$i]['plan_id'],$det[$i]['plan_descripcion']);
                    return true;
                  }  
                }
                //ya no hay mas divisiones
                $this->FormaCuentasDivision();
                return true;
        }

       /**
       *
       */
       function LlamarFormaEquivalencias()
       {
            $this->FormaEquivalencias($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha']);
            return true;
       }

//-------------------------------DIVISION CUENTAS----------------------

      /**
      *
      */
      function TiposDivision()
      {
                list($dbconn) = GetDBconn();
                //------busca si tiene cuentas activas si tiene no deja dividir
            /*  $query ="SELECT numerodecuenta FROM cuentas
                                WHERE ingreso=".$_REQUEST['Ingreso']." and estado='1'";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error DELETE FROM tmp_division_cuenta_abonos ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
                //encontro cuentas
                if(!$result->EOF)
                {
                        while(!$result->EOF)
                        {
                                $y .= " ".$result->fields[0];
                                $result->MoveNext();
                        }
                        $this->frmError["MensajeError"]="EL INGRESO TIENE CUENTAS ACTIVAS: ".$y;
                        $this->Cuenta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$Estado);
                        return true;
                }       */
                //-----fin validacion cuentas activas

                $query =" DELETE FROM tmp_division_cuenta WHERE numerodecuenta=".$_REQUEST['Cuenta']."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error DELETE FROM tmp_division_cuenta ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }

                $query =" DELETE FROM tmp_division_cuenta_abonos WHERE numerodecuenta=".$_REQUEST['Cuenta']."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error DELETE FROM tmp_division_cuenta_abonos ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
                $this->FormaTiposDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],'');
                return true;
      }

      /**
      *
      */
      function BuscarDivision()
      {
                if($_REQUEST['Tipo']==-1)
                {
                        $this->LlamarFormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],'');
                        return true;
                }
                else
                {
                        $this->FormaTiposDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['Tipo']);
                        return true;
                }
      }

      /*
      *
      */
      function DivisionCuenta()
      {        //VALOR
              if($_REQUEST['Tipo']==1)
              {
                  if(!$_REQUEST['Valor'])
                  {
                      if(!$_REQUEST['Valor']){ $this->frmError["Valor"]=1; }
                      $this->frmError["MensajeError"]="Debe Digitar el Valor.";
                      $this->FormaTiposDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha'],$_REQUEST['Tipo']);
                      return true;
                  }
                  else
                  {
                      $this->DivisionValor($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha'],$_REQUEST['Valor']);
                      return true;
                  }
              }
              //FECHAS
              if($_REQUEST['Tipo']==2)
              {
                  if(!$_REQUEST['FechaF'] AND !$_REQUEST['FechaI'])
                  {
                      if(!$_REQUEST['FechaF']){ $this->frmError["FechaF"]=1; }
                      if(!$_REQUEST['FechaI']){ $this->frmError["FechaF"]=1; }
                      $this->frmError["MensajeError"]="Debe Digitar la Fecha.";
                      $this->FormaTiposDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha'],$_REQUEST['Tipo']);
                      return true;
                  }
                  else
                  {
                      $this->DivisionFecha($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha'],$_REQUEST['FechaF'],$_REQUEST['FechaI']);
                      return true;
                  }
              }
              //DEPARTAMENTO
              if($_REQUEST['Tipo']==3)
              {
                      $this->DivisionDpto($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha'],$_REQUEST['Departamento']);
                      return true;
              }
              //SERVICIO
              if($_REQUEST['Tipo']==4)
              {
                      $this->DivisionServicio($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['Ingreso'],$_REQUEST['Fecha'],$_REQUEST['Servicio']);
                      return true;
              }
      }

      /**
      *
      */
      function DivisionValor($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$Valor)
      {
            list($dbconn) = GetDBconn();
            $query = "select a.transaccion,a.valor_cargo from tmp_division_cuenta as a
                      where a.numerodecuenta=$Cuenta and a.cuenta=0
                      order by a.fecha_Cargo";
            $results = $dbconn->Execute($query);
            while (!$results->EOF) {
                $var[]=$results->GetRowAssoc($ToUpper = false);
                $results->MoveNext();
            }
            $results->Close();
            $suma=0;
            for($i=0; $i<sizeof($var);)
            {
                  $suma+=$var[$i][valor_cargo];
                  if($suma<$Valor)
                  {
                      $vars[$i]=$var[$i];
                      $i++;
                  }
                  else{  $i=sizeof($var);  }
            }

            $this->LlamarFormaListadoDivision($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$vars);
            return true;
      }
      /**
      *
      */
      function DivisionServicio($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$Serv)
      {
          if($Serv!=-1)
          {  $x=" and a.servicio_cargo='$Serv'";  }
          list($dbconn) = GetDBconn();
          $query = "select * from ((select a.transaccion, a.cargo, a.fecha_cargo,
                    b.descripcion
                    from cuentas_detalle as a, tarifarios_detalle as b
                    where a.numerodecuenta=$Cuenta and a.cargo=b.cargo
                    $x  and a.tarifario_id=b.tarifario_id and b.grupo_tipo_cargo!='SYS')
                    union
                    (select a.transaccion, a.cargo, a.fecha_cargo, b.descripcion
                    from ayudas_diagnosticas as a, tarifarios_detalle as b
                    where a.numerodecuenta=$Cuenta and a.cargo=b.cargo
                    $x  and a.tarifario_id=b.tarifario_id)) as a";
          $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          while (!$results->EOF) {
              $vars[]=$results->GetRowAssoc($ToUpper = false);
              $results->MoveNext();
          }
          $results->Close();

          $this->LlamarFormaListadoDivision($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$vars);
          return true;
      }

      /**
      *
      */
      function DivisionDpto($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$Dpto)
      {
          if($Dpto!=-1)
          {  $x=" and a.departamento='$Dpto'";  }
          list($dbconn) = GetDBconn();
          $query = "select * from ((select a.transaccion, a.cargo, a.fecha_cargo,
                    b.descripcion
                    from cuentas_detalle as a, tarifarios_detalle as b
                    where a.numerodecuenta=$Cuenta and a.cargo=b.cargo
                    $x  and a.tarifario_id=b.tarifario_id and b.grupo_tipo_cargo!='SYS')
                    union
                    (select a.transaccion, a.cargo, a.fecha_cargo, b.descripcion
                    from ayudas_diagnosticas as a, tarifarios_detalle as b
                    where a.numerodecuenta=$Cuenta and a.cargo=b.cargo
                    $x  and a.tarifario_id=b.tarifario_id)) as a";
          $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }

          while (!$results->EOF) {
              $vars[]=$results->GetRowAssoc($ToUpper = false);
              $results->MoveNext();
          }
          $results->Close();

          $this->LlamarFormaListadoDivision($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$vars);
          return true;
      }

      /**
      *
      */
      function DivisionFecha($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$FF,$FI)
      {
          if(!empty($FI))
          {
                 $f=explode('/',$FI);
                $FI=$f[2].'-'.$f[1].'-'.$f[0];
          }
          if(!empty($FF))
          {
                 $f=explode('/',$FF);
                $FF=$f[2].'-'.$f[1].'-'.$f[0];
          }

          if(!empty($FI) AND !empty($FF))
          {  $f="date(a.fecha_cargo) <= date('$FF') and date(a.fecha_cargo) >= date('$FI')"; }
          elseif(!empty($FI))
          { $f=" date(a.fecha_cargo) >= date('$FI')";}
          elseif(!empty($FF))
          { $f=" date(a.fecha_cargo) <= date('$FF')";}

          list($dbconn) = GetDBconn();
          $query = "select * from ((select a.transaccion, a.cargo, a.fecha_cargo,
                    b.descripcion
                    from cuentas_detalle as a, tarifarios_detalle as b
                    where a.numerodecuenta=$Cuenta and a.cargo=b.cargo
                    and a.tarifario_id=b.tarifario_id and b.grupo_tipo_cargo!='SYS')
                    union
                    (select a.transaccion, a.cargo, a.fecha_cargo, b.descripcion
                    from ayudas_diagnosticas as a, tarifarios_detalle as b
                    where a.numerodecuenta=$Cuenta and a.cargo=b.cargo
                    and a.tarifario_id=b.tarifario_id)) as a
                    where $f";
          $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }

          while (!$results->EOF) {
              $vars[]=$results->GetRowAssoc($ToUpper = false);
              $results->MoveNext();
          }
          $results->Close();

          $this->LlamarFormaListadoDivision($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$vars);
          return true;
      }

      /**
      *
      */
      function LlamarFormaListadoDivision($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$vars)
      {
          unset($_SESSION['CUENTA']['ABONOS']);
          unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);
          unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']); 
         // $_SESSION['CUENTA']['ABONOS']['abono_efectivo']=$_SESSION['CUENTA']['ABONOS']['abono_chequespf']=$_SESSION['CUENTA']['ABONOS']['abono_cheque']=$_SESSION['CUENTA']['ABONOS']['abono_letras']=$_SESSION['CUENTA']['ABONOS']['abono_tarjetas']=$_SESSION['CUENTA']['ABONOS']['abono_bonos']=0;

          list($dbconn) = GetDBconn();
          $query = "delete from tmp_division_cuenta where numerodecuenta=$Cuenta";
            $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }

          $query = "UPDATE cuentas SET estado='2' WHERE numerodecuenta=$Cuenta";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }

             $query = "SELECT a.*, b.descripcion,c.plan_id
                    FROM cuentas_detalle as a, tarifarios_detalle as b, cuentas c
                    WHERE a.numerodecuenta=$Cuenta 
                    AND a.cargo=b.cargo
                    AND a.tarifario_id=b.tarifario_id 
                    AND a.numerodecuenta=c.numerodecuenta 
                    ORDER BY a.codigo_agrupamiento_id";
          $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $this->InsertarTmpDivision($results,0);

          $this->FormaListadoDivision($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$vars);
          return true;
      }

      /**
      *
      */
      function BuscarAbonos($Cuenta)
      {
          list($dbconn) = GetDBconn();
         $query = "select (abono_efectivo + abono_cheque + abono_tarjetas + abono_chequespf + abono_letras + abono_bonos) as abonos,
                     abono_efectivo, abono_cheque, abono_tarjetas, abono_chequespf, abono_letras, abono_bonos
                    from cuentas where numerodecuenta=$Cuenta";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }

          $vars=$result->GetRowAssoc($ToUpper = false);
          return $vars;
      }

      /**
      *
      */
      function DetalleTotal($Cuenta)
      {
          list($dbconn) = GetDBconn();
          $query = "select a.*,d.codigo_producto,
                    (CASE WHEN d.consecutivo IS NOT NULL THEN e.descripcion ELSE b.descripcion END) as descripcion,
                    case a.facturado when 1 then a.valor_cargo else 0 end as fac
                                    from tmp_division_cuenta as a
                                    LEFT JOIN cuentas_codigos_agrupamiento c ON (a.codigo_agrupamiento_id=c.codigo_agrupamiento_id)
                                    LEFT JOIN bodegas_documentos_d d ON (a.consecutivo=d.consecutivo AND c.bodegas_doc_id=d.bodegas_doc_id AND c.numeracion=d.numeracion)
                                    LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
                                    ,tarifarios_detalle as b
                    where a.numerodecuenta=$Cuenta and a.cuenta=0 and
                      a.cargo=b.cargo and a.tarifario_id=b.tarifario_id
                                    order by a.codigo_agrupamiento_id";
          $results = $dbconn->Execute($query);
          while (!$results->EOF) {
              $var[]=$results->GetRowAssoc($ToUpper = false);
              $results->MoveNext();
          }
          $results->Close();
          return $var;
      }

      /**
      *
      */
      function DetalleNuevo($Cuenta,$paginador){
          
          //paginador
          $this->paginaActual = 1;
          $this->offset = 0;
          if($_REQUEST['offset']){
            $this->paginaActual = intval($_REQUEST['offset']);
            if($this->paginaActual > 1){
              $this->offset = ($this->paginaActual - 1) * ($this->limit);
            }
          }
          //fin paginador
          
          list($dbconn) = GetDBconn();
          $query = "SELECT a.*,d.codigo_producto,
                          (CASE WHEN d.consecutivo IS NOT NULL 
                          THEN e.descripcion 
                          ELSE b.descripcion 
                          END) as descripcion,
                          c.plan_descripcion,
                          (CASE a.facturado WHEN 1 
                          THEN a.valor_cargo 
                          ELSE 0 
                          END) as fac,dpto.descripcion as departamento
                   FROM tmp_division_cuenta as a
                   LEFT JOIN cuentas_codigos_agrupamiento f ON (a.codigo_agrupamiento_id=f.codigo_agrupamiento_id)
                   LEFT JOIN bodegas_documentos_d d ON (a.consecutivo=d.consecutivo AND f.bodegas_doc_id=d.bodegas_doc_id AND f.numeracion=d.numeracion)
                   LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
                   LEFT JOIN departamentos dpto ON (a.departamento=dpto.departamento)
                   , tarifarios_detalle as b, planes as c
                   WHERE a.numerodecuenta=$Cuenta AND
                         a.cargo=b.cargo 
                   AND a.tarifario_id=b.tarifario_id
                   AND a.plan_id=c.plan_id
                   ORDER BY a.fecha_cargo,a.codigo_agrupamiento_id,a.transaccion";         
          if(empty($_REQUEST['conteo'])){
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $this->conteo=$result->RecordCount();
          }else{
            $this->conteo=$_REQUEST['conteo'];
          }
          if($paginador==1){
            $query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";
          }
          
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

      /**
      *
      */
      function InsertarTmpDivision($results,$grupo)
      {
          list($dbconn) = GetDBconn();
          while (!$results->EOF) {
              $Datos[]=$results->GetRowAssoc($ToUpper = false);
              $results->MoveNext();
          }
          $results->Close();

          if(!empty($Datos))
          {
                for($i=0; $i<sizeof($Datos); $i++)
                {
                                            if(empty($Datos[$i][autorizacion_int]))
                                            {   $AutoInt='NULL';   }
                                            else
                                            {   $AutoInt=$Datos[$i][autorizacion_int];   }
                                            if(empty($Datos[$i][autorizacion_ext]))
                                            {   $AutoExt='NULL';   }
                                            else
                                            {   $AutoExt=$Datos[$i][autorizacion_ext];   }
                                            if(empty($Datos[$i][codigo_agrupamiento_id]))
                                            {   $Datos[$i][codigo_agrupamiento_id]='NULL';   }
                                            if(empty($Datos[$i][consecutivo]))
                                            {   $Datos[$i][consecutivo]='NULL';   }

                                            if(empty($Datos[$i][cargo_cups]))
                                            {   $Datos[$i][cargo_cups]='NULL';   }
                                            else
                                            {  $Datos[$i][cargo_cups]="'".$Datos[$i][cargo_cups]."'";  }

                                            $query = "INSERT INTO tmp_division_cuenta(
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
                                                            fecha_registro,
                                                            valor_descuento_empresa,
                                                            valor_descuento_paciente,
                                                            porcentaje_gravamen,
                                                            sw_liq_manual,
                                                            servicio_cargo,
                                                            autorizacion_int,
                                                            autorizacion_ext,
                                                            sw_cuota_paciente,
                                                            sw_cuota_moderadora,
                                                            codigo_agrupamiento_id,
                                                            consecutivo,
                                                            cuenta,
                                                            cargo_cups,
                                                            sw_cargue,
                                                            plan_id)
                                                    VALUES (".$Datos[$i][transaccion].",
                                                    '".$Datos[$i][empresa_id]."',
                                                    '".$Datos[$i][centro_utilidad]."',
                                                    ".$Datos[$i][numerodecuenta].",
                                                    '".$Datos[$i][departamento]."',
                                                    '".$Datos[$i][tarifario_id]."',
                                                    '".$Datos[$i][cargo]."',
                                                    ".$Datos[$i][cantidad].",
                                                    ".$Datos[$i][precio].",
                                                    ".$Datos[$i][valor_cargo].",
                                                    ".$Datos[$i][valor_nocubierto].",
                                                    ".$Datos[$i][valor_cubierto].",
                                                    ".$Datos[$i][usuario_id].",
                                                    ".$Datos[$i][facturado].",
                                                    '".$Datos[$i][fecha_cargo]."',
                                                    '".$Datos[$i][fecha_registro]."',
                                                    ".$Datos[$i][valor_descuento_empresa].",
                                                    ".$Datos[$i][valor_descuento_paciente].",
                                                    ".$Datos[$i][porcentaje_gravamen].",
                                                    ".$Datos[$i][sw_liq_manual].",
                                                    ".$Datos[$i][servicio_cargo].",
                                                    $AutoInt,
                                                    $AutoExt,
                                                    ".$Datos[$i][sw_cuota_paciente].",
                                                    ".$Datos[$i][sw_cuota_moderadora].",
                                                    ".$Datos[$i][codigo_agrupamiento_id].",
                                                    ".$Datos[$i][consecutivo].",
                                                    $grupo,".$Datos[$i][cargo_cups].",
                                                    '".trim($Datos[$i][sw_cargue])."',
                                                    '".$Datos[$i][plan_id]."')";
                                            $dbconn->Execute($query);
                                            if ($dbconn->ErrorNo() != 0) {
                                                    $this->error = "Error al cuentas_detalle2";
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    return false;
                                            }
                }
          }
      }

        function Planes($Plan)
        {
                    list($dbconn) = GetDBconn();
                    $query="SELECT plan_id,plan_descripcion,tercero_id,tipo_tercero_id FROM planes
                                    WHERE fecha_final >= now() and estado=1 and fecha_inicio <= now()
                                    and plan_id not in($Plan) order by plan_descripcion";
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
      *
      */
      function InsertarDivisionCuenta()
      {
        if($_REQUEST['SeleccionarNuevoPlan']){
          if($_REQUEST['planNuevo']!=-1){
            $datPlan=$this->NombrePlan($_REQUEST['planNuevo']);                            
            $indice=sizeof($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);              
            $_SESSION['DIVISION_CUENTA_VARIOS_PLANES'][$indice][$_REQUEST['planNuevo']]=$datPlan['plan_descripcion'];
          }
          ksort($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);
          $this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
          return true;          
        }
                
                unset($_SESSION['CUENTA']['ABONOS']);
                IncludeLib('funciones_facturacion');

          //si eligio un cargo para bajar
          if(!empty($_REQUEST['abajo']))
          {
                     if(empty($_REQUEST['plan']))
                         {
                                $this->frmError["MensajeError"]="Debe Elegir el Plan.";
                                $this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
                                return true;
                         }
              $f=0;
              //si elegio un abono actual
              foreach($_REQUEST as $k => $v)
              {
                                if(substr_count($k,'actual'))
                                {
                                    if(!empty($v))
                                    {
                                            $f=1;
                                            $var=explode(',',$v);
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['prefijo']=$var[0];
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['recibo']=$var[1];
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['fecha']=$var[2];
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['efectivo']=$var[3];
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['cheque']=$var[4];
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['tarjeta']=$var[5];
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['bonos']=$var[6];
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['total']=$var[7];
                                    }
                                }
              }
              foreach($_REQUEST as $k => $v)
              {
                  if($f==0)
                  {
                      if(substr_count($k,'New'))
                      {
                        if(!empty($v))
                        { $f=1; }
                      }
                  }
              }
            if($f==0)
            {
                $this->frmError["MensajeError"]="Debe Elegir algun Cargo o Abono para la División.";
                $this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
                return true;
            }
          }
          else
          {
              //si elegio un abono nuevo
              foreach($_REQUEST as $k => $v)
              {
                                    if(substr_count($k,'nuevo'))
                                    {
                                        if(!empty($v))
                                        {
                                                $f=1;
                                                list($dbconn) = GetDBconn();
                                                $var=explode(',',$v);
                                                //va ha borrar los abonos
                                                $query = "DELETE FROM tmp_division_cuenta_abonos WHERE recibo_caja=".$var[1]."
                                                                    and prefijo='".$var[0]."' and plan_id=".$var[2]."";
                                                $dbconn->Execute($query);
                                                if ($dbconn->ErrorNo() != 0) {
                                                        $this->error = "DELETE FROM tmp_division_cuenta_abonos";
                                                        $this->fileError = __FILE__;
                                                        $this->lineError = __LINE__;
                                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                        return false;
                                                }
                                        }
                                    }
              }
              foreach($_REQUEST as $k => $v)
              {
                  if($f==0)
                  {
                      if(substr_count($k,'Go'))
                      {
                        if(!empty($v))
                        { $f=1; }
                      }
                  }
              }
              if($f==0)
              {
                  $this->frmError["MensajeError"]="Debe Elegir algun Cargo o Abono de la Cuenta Nueva.";
                  $this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
                  return true;
              }
          }

         list($dbconn) = GetDBconn();
                //va ha insertar los abonos
                foreach($_SESSION['CUENTA']['ABONOS'] as $k => $v)
                {
                            $query = "INSERT INTO tmp_division_cuenta_abonos(plan_id,
                                                                                                numerodecuenta,
                                                                                                recibo_caja,
                                                                                                prefijo,
                                                                                                fecha_ingcaja,
                                                                                                total_abono,
                                                                                                total_efectivo,
                                                                                                total_cheques,
                                                                                                total_tarjetas,
                                                                                                total_bonos)
                                                VALUES(".$_REQUEST['plan'].",".$_REQUEST['Cuenta'].",".$v[recibo].",'".$v[prefijo]."','".$v[fecha]."',".$v[total].",
                                                ".$v[efectivo].",".$v[cheque].",".$v[tarjeta].",".$v[bonos].")";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error update tmp_division_cuenta";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                            }
                }

                /*
                        OJO los pagos son por recibo no por tipo
                */
          $f=0;
          $j=0;

          foreach($_REQUEST as $k => $v)
          {
                        //cuando los baja a la cuenta nueva
              if(substr_count($k,'New'))
              {         // 0 transaccion 1codigo_agrupamiento 2 consecutivo 3 cups
                    $d = explode(',',$v);
                    //este codigo se comento para poder pasar los medicamentos de una cuenta a otra
                    /*if(!empty($d[1]) AND !empty($d[2]))
                                    { //es un medicamento
                            $query = "update tmp_division_cuenta set cuenta=1, plan_id=".$_REQUEST['plan']."
                                    where codigo_agrupamiento_id=$d[1]";
                                            $dbconn->Execute($query);
                                            if ($dbconn->ErrorNo() != 0) {
                                                    $this->error = "Error update tmp_division_cuenta";
                                                    $this->fileError = __FILE__;
                                                    $this->lineError = __LINE__;
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    return false;
                                            }
                                    }
                                    else
                                    {*/
                                    //fin codigo comentado
                                    if(empty($d[1]) AND empty($d[2])){
                                                $equi='';
                                                $equi=ValdiarEquivalencias($_REQUEST['plan'],$d[3]);
                                                if(empty($equi))
                                                {
                                                        $this->frmError["MensajeError"]='ALGUNO(S) DE LOS CARGOS NO TIENE EQUIVALENCIAS O LAS EQUIVALENCIAS NO ESTAN CONTRATADAS';
                                                        $this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
                                                        return true;
                                                }
                                    }

                                                //valida is tiene quivalencias y esta contratado para q el cambio de responsable salga nien
                            $query = "update tmp_division_cuenta set cuenta=1, plan_id=".$_REQUEST['plan']."
                                    where transaccion=$d[0]";
                                            $dbconn->Execute($query);
                                            if ($dbconn->ErrorNo() != 0) {
                                                    $this->error = "Error update tmp_division_cuenta";
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    return false;
                                            }
                    //}
                    $f++;
              }
              if(substr_count($k,'Go'))
              {         // 0 transaccion 1codigo_agrupamiento 2 consecutivo
                    $d = explode(',',$v);
                    //este codigo se comento para poder pasar los medicamentos de una cuenta a otra
                    /*if(!empty($d[1]) AND !empty($d[2]))
                                    { //es un medicamento
                            $query = "update tmp_division_cuenta set cuenta=0, plan_id=NULL
                                    where codigo_agrupamiento_id=$d[1]";
                                    }
                                    else
                                    {*/
                     //fin codigo comentado
                            $query = "update tmp_division_cuenta set cuenta=0, plan_id=NULL
                                    where transaccion=$d[0]";
                                    //}
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error update tmp_division_cuenta";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    $j++;
              }
          }

          if(!empty($f))
          {  $msg.="Los $f Cargos fueron asignados a la nueva Cuenta.  ";  }
          if(!empty($j))
          {  $msg.="Los $j Cargos fueron reasigandos a la Cuenta Actual.";  }
          $this->frmError["MensajeError"]=$msg;
          $this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
          return true;
      }

/*
      function InsertarDivisionCuenta()
      {
          //si eligio un cargo para bajar
          if(!empty($_REQUEST['abajo']))
          {
              $f=0;
              //si elegio un abono actual
              foreach($_REQUEST as $k => $v)
              {
                                if(substr_count($k,'actual'))
                                {
                                    if(!empty($v))
                                    {
                                            $f=1;
                                            $var=explode(',',$v);
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['prefijo']=$var[0];
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['recibo']=$var[1];
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['fecha']=$var[2];
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['efectivo']=$var[3];
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['cheque']=$var[4];
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['tarjeta']=$var[5];
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['bonos']=$var[6];
                                            $_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]['total']=$var[7];
                                    }
                                }
              }
              foreach($_REQUEST as $k => $v)
              {
                  if($f==0)
                  {
                      if(substr_count($k,'New'))
                      {
                        if(!empty($v))
                        { $f=1; }
                      }
                  }
              }
            if($f==0)
            {
                $this->frmError["MensajeError"]="Debe Elegir algun Cargo o Abono para la División.";
                $this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
                return true;
            }
          }
          else
          {
              //si elegio un abono nuevo
              foreach($_REQUEST as $k => $v)
              {
                                    if(substr_count($k,'nuevo'))
                                    {
                                        if(!empty($v))
                                        {
                                                $f=1;
                                                $var=explode(',',$v);
                                                unset($_SESSION['CUENTA']['ABONOS'][$var[0].$var[1]]);
                                        }
                                    }
              }
              foreach($_REQUEST as $k => $v)
              {
                  if($f==0)
                  {
                      if(substr_count($k,'Go'))
                      {
                        if(!empty($v))
                        { $f=1; }
                      }
                  }
              }
              if($f==0)
              {
                  $this->frmError["MensajeError"]="Debe Elegir algun Cargo o Abono de la Cuenta Nueva.";
                  $this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
                  return true;
              }
          }

                //      OJO los pagos son por recibo no por tipo
          $f=0;
          $j=0;
          list($dbconn) = GetDBconn();
          foreach($_REQUEST as $k => $v)
          {
              if(substr_count($k,'New'))
              {         // 0 transaccion 1codigo_agrupamiento 2 consecutivo
                    $d = explode(',',$v);
                    if(!empty($d[1]) AND !empty($d[2]))
                                    { //es un medicamento
                            $query = "update tmp_division_cuenta set cuenta=1
                                    where codigo_agrupamiento_id=$d[1]";
                                    }
                                    else
                                    {
                            $query = "update tmp_division_cuenta set cuenta=1
                                    where transaccion=$d[0]";
                                    }
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error update tmp_division_cuenta";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    $f++;
              }
              if(substr_count($k,'Go'))
              {         // 0 transaccion 1codigo_agrupamiento 2 consecutivo
                    $d = explode(',',$v);
                    if(!empty($d[1]) AND !empty($d[2]))
                                    { //es un medicamento
                            $query = "update tmp_division_cuenta set cuenta=0
                                    where codigo_agrupamiento_id=$d[1]";
                                    }
                                    else
                                    {
                            $query = "update tmp_division_cuenta set cuenta=0
                                    where transaccion=$d[0]";
                                    }
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error update tmp_division_cuenta";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    $j++;
              }
          }

          if(!empty($f))
          {  $msg.="Los $f Cargos fueron asignados a la nueva Cuenta.  ";  }
          if(!empty($j))
          {  $msg.="Los $j Cargos fueron reasigandos a la Cuenta Actual.";  }
          $this->frmError["MensajeError"]=$msg;
          $this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
          return true;
      }
*/

        function DivisionAbonosCuenta($Cuenta,$Plan)
        {
                if(!empty($Cuenta))
                {   $x = "numerodecuenta=$Cuenta and "; }

          list($dbconn) = GetDBconn();
            $query = "select * from tmp_division_cuenta_abonos where $x plan_id=$Plan";
          $results = $dbconn->Execute($query);
          while (!$results->EOF) {
              $var[]=$results->GetRowAssoc($ToUpper = false);
              $results->MoveNext();
          }
          $results->Close();
          return $var;
        }

        function DivisionSoloAbonosCuenta($Cuenta,$vector)
        {       //$vector traer los planes q ya estan
                unset($plan);
                for($i=0; $i<sizeof($vector); $i++)
                {
                        if($i+1==sizeof($vector))
                        {  $plan.=$vector[$i];  }
                        else
                        {  $plan.=$vector[$i].',';  }
                }

                if(!empty($plan))
                {   $x= " and a.plan_id not in($plan)";  }

          list($dbconn) = GetDBconn();
          $query = "SELECT distinct a.plan_id, b.plan_descripcion,a.cuenta
                                    FROM tmp_division_cuenta_abonos as a, planes as b
                                    WHERE a.numerodecuenta=$Cuenta $x
                                    and a.plan_id=b.plan_id ORDER BY plan_id";
          $results = $dbconn->Execute($query);
          while (!$results->EOF) {
              $var[]=$results->GetRowAssoc($ToUpper = false);
              $results->MoveNext();
          }
          $results->Close();
          return $var;
        }
        
        function ObtenerFormaListadoDivision(){        
          $this->FormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['vars']);
          return true;          
        }

/*
      function DetalleNuevo($Cuenta)
      {
          list($dbconn) = GetDBconn();
          $query = "select a.*, b.descripcion,
                                    case a.facturado when 1 then a.valor_cargo else 0 end as fac
                                    from tmp_division_cuenta as a, tarifarios_detalle as b
                    where a.numerodecuenta=$Cuenta and a.cuenta=1 and
                      a.cargo=b.cargo and a.tarifario_id=b.tarifario_id
                                    order by a.codigo_agrupamiento_id";
          $results = $dbconn->Execute($query);
          while (!$results->EOF) {
              $var[]=$results->GetRowAssoc($ToUpper = false);
              $results->MoveNext();
          }
          $results->Close();
          return $var;
      }
*/

      /**
      *
      */
      function FinalizarDivision()
      {
                    unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']); 
                    unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']); 
                    $_SESSION['CUENTA']['DIVISION']=1;
                    unset($_SESSION['DIVISION']['CUENTA']);
                    $det = $this->DetalleNuevo($_REQUEST['Cuenta']);
                    $_SESSION['DIVISION']['CUENTA'][]=array('cuenta'=>$_REQUEST['Cuenta']);                    
                    for($i=0; $i<sizeof($det); $i++){                      
                      if($det[$i]['cuenta']!='0'){                        
                        $_REQUEST['Responsable']=$det[$i]['plan_id'];
                        $_REQUEST['indice']=$det[$i]['cuenta'];
                        $_REQUEST['descripcion_plan']=$det[$i]['plan_descripcion'];
                        $this->NuevoResponsable();
                        return true;
                      }
                    }                  
                    //revisa si solo son abonos
                    $det=$this->DivisionSoloAbonosCuenta($_REQUEST['Cuenta'],'');
                    $_SESSION['CUENTA']['DIVISION']['ABONOS']=1;                    
                    for($i=0; $i<sizeof($det); $i++){
                      if($det[$i]['cuenta']!='0'){
                        $_REQUEST['Responsable']=$det[$i]['plan_id'];
                        $_REQUEST['descripcion_plan']=$det[$i]['plan_descripcion'];
                        $_REQUEST['indice']=$det[$i]['cuenta'];
                        $this->NuevoResponsable();
                        return true;
                      }
                    }
                    //---------hay q ir a revisar las equivalencias y pedir los nuevos datos



            $Cuenta1=$_REQUEST['Cuenta'];
            $TipoId=$_REQUEST['TipoId'];
            $PacienteId=$_REQUEST['PacienteId'];
            $Nivel=$_REQUEST['Nivel'];
            $PlanId=$_REQUEST['PlanId'];
            $Ingreso=$_REQUEST['Ingreso'];
            $Fecha=$_REQUEST['Fecha'];
            $Nivel=$_REQUEST['Nivel'];
            $empresa=$_SESSION['CUENTAS']['EMPRESA'];
            //BUSCA LOS DATOS DE LA CUENTA ACTUAL
            list($dbconn) = GetDBconn();
            $query="SELECT * FROM cuentas
                    WHERE numerodecuenta='$Cuenta1' and empresa_id='$empresa'";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $var=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            //CREA LA NUEVA CUENTA
            if(empty($var[autorizacion_int]))
            {   $AutoInt='NULL';   }
            else
            {   $AutoInt=$var[autorizacion_int];   }
            if(empty($var[autorizacion_ext]))
            {   $AutoExt='NULL';   }
            else
            {   $AutoExt=$var[autorizacion_ext];   }

            $query=" SELECT nextval('cuentas_numerodecuenta_seq')";
            $result=$dbconn->Execute($query);
            $CN=$result->fields[0];
            $query = "INSERT INTO cuentas (numerodecuenta,
                                            empresa_id,
                                            centro_utilidad,
                                            ingreso,
                                            plan_id,
                                            estado,
                                            usuario_id,
                                            fecha_registro,
                                            tipo_afiliado_id,
                                            rango,
                                            autorizacion_int,
                                            autorizacion_ext,
                                            semanas_cotizadas,
                                            abono_efectivo,
                                            abono_cheque,
                                            abono_tarjetas,
                                            abono_chequespf,
                                            abono_letras,
                                            abono_bonos)
                      VALUES($CN,'".$var[empresa_id]."','".$var[centro_utilidad]."',".$var[ingreso].",'".$var[plan_id]."',".$var[estado].",'".UserGetUID()."','now()','".$var[tipo_afiliado_id]."','".$var[rango]."',$AutoInt,$AutoExt,".$var[semanas_cotizadas].",0,0,0,0,0,0)";
            $dbconn->BeginTrans();
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            //ELIMINA DE LAS TABLAS REALES LOS CARGOS QUE VAN HA PASAR A LA NUEVA CUENTA


            $query =" DELETE FROM tmp_division_cuenta WHERE numerodecuenta=".$Cuenta1."";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error DELETE FROM tmp_division_cuenta ";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
                    //abonos a la nueva cuenta
                    foreach($_SESSION['CUENTA']['ABONOS'] as $k => $v)
                    {
                            $query ="UPDATE rc_detalle_hosp SET numerodecuenta=$CN
                                             WHERE prefijo='".$v[prefijo]."' AND recibo_caja=".$v[recibo]."
                                             AND numerodecuenta=$Cuenta1";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error UPDATE rc_detalle_hosp ";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    $this->fileError = __FILE__;
                                    $this->lineError = __LINE__;
                                    return false;
                            }
                    }

                    //reiniciar valores de la cuenta vieja para recalcularlos
                    /*$query ="UPDATE cuentas SET total_cuenta=0,gravamen_valor_cubierto=0,
                                    valor_cuota_paciente=0,valor_nocubierto=0,valor_cubierto=0,porcentaje_descuento_empresa=0,
                                    gravamen_valor_nocubierto=0,valor_descuento_empresa=0,valor_descuento_paciente=0,
                                    valor_cuota_moderadora=0,porcentaje_descuento_paciente=0,abono_bonos=0,
                                    valor_total_paciente=0,valor_total_empresa=0,valor_descuento_cuota_paciente=0,
                                    valor_descuento_cuota_moderadora=0,valor_total_cargos=0
                                    WHERE numerodecuenta=$Cuenta1";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error DELETE FROM tmp_division_cuenta ";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            $this->fileError = __FILE__;
                            $this->lineError = __LINE__;
                            return false;
                    }
                    $query ="UPDATE rc_detalle_hosp SET numerodecuenta=$Cuenta1
                                    WHERE numerodecuenta=$Cuenta1";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error DELETE FROM tmp_division_cuenta ";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            $this->fileError = __FILE__;
                            $this->lineError = __LINE__;
                            return false;
                    }*/
                    //fin de reinicio de valores

                    //reliquidacion de la cuenta vieja para recalcular valores del paciente
                    IncludeLib("tarifario_cargos");
                    $var=$this->CuentasDetalleR($Cuenta1);
                    for($i=0; $i<sizeof($var); $i++)
                    {
                        $Cargo=$var[$i][cargo];
                        $des=$this->BuscarDescuentosCuenta($Cuenta,$var[$i][grupo_tipo_cargo]);
                        $TarifarioId=$var[$i][tarifario_id];
                        $Cantidad=$var[$i][cantidad];
                        $transaccion=$var[$i][transaccion];
                        $Liq=LiquidarCargoCuenta($Cuenta1,$TarifarioId,$Cargo,$Cantidad,$des[descuento_empresa],$des[descuento_paciente],true,true,0,$PlanId,$var[$i]['servicio_al_cargar'],'','');

                        $query ="   UPDATE cuentas_detalle
                                    SET
                                        precio=".$Liq[precio_plan].",
                                        valor_cargo=".$Liq[valor_cargo].",
                                        valor_nocubierto=".$Liq[valor_no_cubierto].",
                                        valor_cubierto=".$Liq[valor_cubierto].",
                                        valor_descuento_empresa=".$Liq[valor_descuento_empresa].",
                                        valor_descuento_paciente=".$Liq[valor_descuento_paciente].",
                                        porcentaje_gravamen=".$Liq[porcentaje_gravamen].",
                                        sw_cuota_paciente=".$Liq[sw_cuota_paciente].",
                                        sw_cuota_moderadora=".$Liq[sw_cuota_moderadora]."
                                    WHERE numerodecuenta=$Cuenta1
                                        AND cargo='$Cargo'
                                        AND tarifario_id='$TarifarioId'
                                        AND transaccion='$transaccion'";

                        $result=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error UPDATE cuentas_detalle";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                        }
                        $result->Close();
                        $x++;
                    }//fin for

                    $var=$this->BuscarInsumosReliquidar($Cuenta1);
                    for($i=0; $i<sizeof($var); $i++)
                    {
                                $Liq=LiquidarIyM($Cuenta1 ,$var[$i]['codigo_producto'] ,$var[$i]['cantidad'] ,0 ,0 ,true ,true ,NULL ,$_REQUEST['PlanId'],false,$var[$i]['departamento_al_cargar'],$_SESSION['CUENTAS']['EMPRESA'],$var[$i]['evolucion_id']);
                                                            if($var[$i]['tipo_mov']=='DIMD'){
                                                                $valor_cargo=($Liq['valor_cargo']*-1);
                                                                $valor_nocubierto=($Liq['valor_nocubierto']*-1);
                                                                $valor_cubierto=($Liq['valor_cubierto']*-1);
                                                            }else{
                                                                $valor_cargo=$Liq['valor_cargo'];
                                                                $valor_nocubierto=$Liq['valor_nocubierto'];
                                                                $valor_cubierto=$Liq['valor_cubierto'];
                                                            }
                                $query =" UPDATE cuentas_detalle SET
                                                                                    precio=".$Liq[precio_plan].",
                                                                                    valor_cargo='".$valor_cargo."',
                                                                                    valor_nocubierto='".$valor_nocubierto."',
                                                                                    valor_cubierto='".$valor_cubierto."',
                                                                                    valor_descuento_empresa=".$Liq[valor_descuento_empresa].",
                                                                                    valor_descuento_paciente=".$Liq[valor_descuento_paciente].",
                                                                                    porcentaje_gravamen=".$Liq[porcentaje_gravamen].",
                                                                                    sw_cuota_paciente=".$Liq[sw_cuota_paciente].",
                                                                                    sw_cuota_moderadora=".$Liq[sw_cuota_moderadora]."
                                                    WHERE numerodecuenta=$Cuenta1 and consecutivo=".$var[$i][consecutivo]."";
                                $result=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error UPDATE cuentas_detalle";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                }
                                $result->Close();
                                $x++;
                    }


                    //fin reliquidacion

            unset($_SESSION['CUENTA']['ABONOS']);
            $dbconn->CommitTrans();
            $mensaje='La Cuenta No. '.$Cuenta1.' quedo Dividida en la Cuenta No.'.$Cuenta.'.<br>Recuerde que las Cuentas estan Inactivas, Debe Activar una de las Dos Cuentas.<br>Desea Activar una de las Dos Cuentas.';
            $arreglo=array('Cuenta1'=>$Cuenta1,'Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
            $c='app';
            $m='Facturacion';
            $me='LlamarFormaActivarCuentaDivision';
            $me2='main';
            $Titulo='DIVISION DE LA CUENTA No. '.$Cuenta1;
            $boton1='ACTIVAR UNA CUENTA';
            $boton2='CANCELAR';
            $this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
            return true;
        }

      /**
      *
      */
      function LlamarFormaActivarCuentaDivision()
      {
          $this->FormaActivarCuentaDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta1'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha']);
          return true;
      }

      /**
      *
      */
      function ActivarCuentaDivision()
      {
            if(empty($_REQUEST['CuentaA']))
            {
                $this->frmError["MensajeError"]="Debe Elegir una Cuenta Para Activar.";
                $this->FormaActivarCuentaDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta1'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha']);
                return true;
            }


            $this->ActivarCuenta($_REQUEST['PlanId'],$_REQUEST['CuentaA'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha']);
            return true;
      }


      /**
      *
      */
      function CancelarDivision()
      {
                unset($_SESSION['CUENTA']['DIVISION']);
                unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);
                unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']); 
                list($dbconn) = GetDBconn();
          if($_SESSION['ESTADO']=='A')
          {
              $query = "UPDATE cuentas SET estado='1' WHERE numerodecuenta=".$_REQUEST['Cuenta']."";
              $result = $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
              }
          }

                $query =" DELETE FROM tmp_division_cuenta WHERE numerodecuenta=".$_REQUEST['Cuenta']."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error DELETE FROM tmp_division_cuenta ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }

                $query =" DELETE FROM tmp_division_cuenta_abonos WHERE numerodecuenta=".$_REQUEST['Cuenta']."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error DELETE FROM tmp_division_cuenta_abonos ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }

                $query="delete from cambio_Responsable where numerodecuenta=".$_REQUEST['Cuenta']."";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }

          if(!$this->Cuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$Cama,$_REQUEST['Fecha'],$_REQUEST['Ingreso'])){
              return false;
          }
          return true;
      }


      /**
      *
      */
      function ConsultaAbonos($tipo,$cuenta)
      {
          list($dbconn) = GetDBconn();
          switch($tipo)
          {    //efectivo
              case '1':
              $query = " select a.recibo_caja, b.total_efectivo
                        from rc_detalle_hosp as a, recibos_caja as b
                        where a.numerodecuenta=$cuenta and a.recibo_caja=b.recibo_caja and a.recibo_caja=b.recibo_caja and b.total_efectivo <> 0";
              break;

              //cheques
              case '2':
              $query = "select a.recibo_caja,c.fecha_cheque,c.total,c.girador,d.descripcion
                        from rc_detalle_hosp as a, cheques_mov as c,bancos as d
                        where a.numerodecuenta=$cuenta
                        and a.recibo_caja=c.recibo_caja AND  c.banco=d.banco";
              break;

              //tarjeta debito
              case '3':
              $query = "select a.recibo_caja,c.total,c.tarjeta_numero,d.descripcion
                        from rc_detalle_hosp as a, tarjetas_mov_debito as c,tarjetas as d
                        where a.numerodecuenta=$cuenta
                        and a.recibo_caja=c.recibo_caja AND c.tarjeta=d.tarjeta";
              break;

              //tarjeta credito
              case '4':
              $query = "select a.recibo_caja,c.fecha,c.total,c.tarjeta_numero,d.descripcion
                        from rc_detalle_hosp as a, tarjetas_mov_credito as c,tarjetas as d
                        where a.numerodecuenta=$cuenta
                        and a.recibo_caja=c.recibo_caja AND c.tarjeta=d.tarjeta";
              break;

              //tarjeta bonos
              case '5':
              $query = "select a.recibo_caja,c.valor_bono,d.descripcion
                        from rc_detalle_hosp as a, caja_bonos as c,tipos_bonos d
                        where a.numerodecuenta=$cuenta
                        and a.recibo_caja=c.recibo_caja AND c.tipo_bono=d.tipo_bono";
              break;
          }

          $resulta=$dbconn->Execute($query);
          while(!$resulta->EOF)
          {
              $vars[]=$resulta->GetRowAssoc($ToUpper = false);
              $resulta->MoveNext();
          }
          $resulta->Close();
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

        /**
        *
        */
        function TiposCuentas()
        {
                list($dbconn) = GetDBconn();
                $query = "select * from cuentas_tipos";
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
//-------------------------------LA FORMA CON CODIGO AGRUPAMIENTO--------------------

        /**
        *
        */
        function DefinirForma($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$vars,$Transaccion,$mensaje,$Dev,$Estado,
                             $Pieza,$doc,$numeracion,$qx,$codigo,$des,$noFacturado,$Consecutivo)
        {
            if($_REQUEST['TipoId'] AND $_REQUEST['PacienteId']
              AND $_REQUEST['PlanId'] AND $_REQUEST['Cuenta'])
            {
                $TipoId=$_REQUEST['TipoId'];
                $PacienteId=$_REQUEST['PacienteId'];
                $Nivel=$_REQUEST['Nivel'];
                $PlanId=$_REQUEST['PlanId'];
                $Pieza=$_REQUEST['Pieza'];
                $Cama=$_REQUEST['Cama'];
                $Fecha=$_REQUEST['Fecha'];
                $Ingreso=$_REQUEST['Ingreso'];
                $Cuenta=$_REQUEST['Cuenta'];
                $filtro='';
            }
            else
            {
                $_REQUEST['doc']=$doc;
                $_REQUEST['numeracion']=$numeracion;
                $_REQUEST['qx']=$qx;
                $_REQUEST['codigo']=$codigo;
            }

                if($_REQUEST['noFacturado']=='0')
                 {  $filtro=" and a.facturado='0'"; }
                 else
                 {  $filtro=" and a.facturado='1'"; }
                list($dbconn) = GetDBconn();
                if(empty($_REQUEST['doc']) AND empty($_REQUEST['numeracion']) AND empty($_REQUEST['qx']) AND empty($_REQUEST['codigo']))
                {
                        $query = "select a.*, b.descripcion
                                            from cuentas_detalle as a, tarifarios_detalle as b
                                            where a.numerodecuenta=$Cuenta
                                            and a.cargo=b.cargo and a.tarifario_id=b.tarifario_id
                                            $filtro";
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
                        if(!$this->FormaCuenta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$vars,$Transaccion,$mensaje,$Dev,$Estado)){
                         return false;
                        }
                        //$this->FormaDetalleCodigo($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$vars,$_REQUEST['des'],$_REQUEST['codigo'],$_REQUEST['doc'],$_REQUEST['numeracion'],'',$_REQUEST['noFacturado']);
                        return true;
                }//es una cirugia
                //no es nada de medicamentos ni cirugia
                elseif(empty($_REQUEST['doc']) AND empty($_REQUEST['numeracion']) AND empty($_REQUEST['qx']))
                {
                        $query = "select a.*, b.descripcion
                                            from cuentas_detalle as a, tarifarios_detalle as b
                                            where a.codigo_agrupamiento_id='".$_REQUEST['codigo']."'
                                            and a.numerodecuenta=$Cuenta
                                            and a.cargo=b.cargo and a.tarifario_id=b.tarifario_id
                                            $filtro";
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

                        $this->FormaDetalleCodigo($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$vars,$_REQUEST['des'],$_REQUEST['codigo'],$_REQUEST['doc'],$_REQUEST['numeracion'],'',$_REQUEST['noFacturado']);
                        return true;
                }//es una cirugia
                elseif(!empty($_REQUEST['qx']))
                {
                /*$query = "SELECT a.*, b.descripcion, c.tercero_id, c.tipo_tercero_id,
                                            d.valor, d.porcentaje_honorario, d.tipo_tercero_id as tipohono, d.tercero_id as idhono
                                            FROM cuentas_detalle as a LEFT JOIN cuentas_detalle_profesionales as c ON(a.transaccion=c.transaccion)
                                            LEFT JOIN cuentas_detalle_honorarios as d ON(a.transaccion=d.transaccion),
                                            tarifarios_detalle as b
                                            WHERE a.codigo_agrupamiento_id='".$_REQUEST['codigo']."'
                                            and a.numerodecuenta=$Cuenta and a.consecutivo ISNULL
                                            and a.cargo=b.cargo and a.tarifario_id=b.tarifario_id";
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

                        //busca si la cirugia tiene medicamentos
                        if(!empty($_REQUEST['doc']) AND !empty($_REQUEST['numeracion']))
                        {
                                $query = "select a.*, e.descripcion, c.codigo_producto
                                                from cuentas_detalle as a,
                                                bodegas_documentos_d as c, inventarios_productos as e
                                                where a.codigo_agrupamiento_id='".$_REQUEST['codigo']."'
                                                and a.numerodecuenta=$Cuenta
                                                and a.consecutivo=c.consecutivo
                                                and c.codigo_producto=e.codigo_producto
                                                $filtro";
                                $result = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                                }
                                while(!$result->EOF)
                                {
                                                $med[]=$result->GetRowAssoc($ToUpper = false);
                                                $result->MoveNext();
                                }
                                $result->Close();
                        }//fin buscar medicamentos cirugia
                        */

                        $this->FormaDetalleCirugia($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$vars,$med,$_REQUEST['des'],$_REQUEST['codigo'],$_REQUEST['qx']);
                        return true;
                }//es medicamentos
                else
                {
                $query = "select a.*, e.descripcion, c.codigo_producto
                                        from cuentas_detalle as a,
                                        bodegas_documentos_d as c, inventarios_productos as e
                                        where a.codigo_agrupamiento_id='".$_REQUEST['codigo']."'
                                        and a.numerodecuenta=$Cuenta
                                        and a.consecutivo=c.consecutivo
                                        and c.codigo_producto=e.codigo_producto
                                        $filtro";
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

                        $this->FormaDetalleCodigo($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$vars,$_REQUEST['des'],$_REQUEST['codigo'],$_REQUEST['doc'],$_REQUEST['numeracion'],'',$_REQUEST['noFacturado']);
                        return true;
                }
        }

        function DatosCirugia($NoLiquidacion,$Cuenta)
        {
                GLOBAL $ADODB_FETCH_MODE;
                list($dbconn) = GetDBconn();
                $query="SELECT d.tipo_id_cirujano,d.cirujano_id,d.cargo_cups,d.consecutivo_procedimiento,
                            c.tarifario_id as tarifario_id_procedimiento,c.cargo as cargo_procedimiento,
                            c.tipo_cargo_qx_id,b.tarifario_id,b.cargo,c.porcentaje,c.secuencia,b.valor_nocubierto,b.valor_cubierto,
                            e.tipo_tercero_id as tipo_id_profesional,e.tercero_id as profesional_id,f.descripcion,uv.uvrs
                            FROM cuentas_codigos_agrupamiento a,cuentas_detalle b
                            LEFT JOIN cuentas_detalle_profesionales e ON (b.transaccion=e.transaccion)
                            ,cuentas_cargos_qx_procedimientos c
                            JOIN cuentas_liquidaciones_qx_procedimientos_cargos uv ON (uv.consecutivo_procedimiento=c.consecutivo_procedimiento AND uv.tarifario_id=c.tarifario_id AND uv.cargo=c.cargo)
                            ,cuentas_liquidaciones_qx_procedimientos d,tarifarios_detalle f,tipos_cargos_qx g
                            WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND a.descripcion='ACTO QUIRURGICO' AND a.bodegas_doc_id IS NULL AND a.numeracion IS NULL AND
                            a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND
                            b.transaccion=c.transaccion AND c.consecutivo_procedimiento=d.consecutivo_procedimiento AND c.cargo=f.cargo AND
                            c.tarifario_id=f.tarifario_id AND c.tipo_cargo_qx_id=g.tipo_cargo_qx_id
                AND b.numerodecuenta='$Cuenta'
                            ORDER BY c.secuencia,g.indice_de_orden";

                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($query);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                while($cargo=$result->FetchRow())
                {
                        $secuencia=explode('-',$cargo['secuencia']);
                                            $v[$secuencia[0]][$secuencia[1]]['tipo_id_cirujano']=$cargo['tipo_id_cirujano'];
                                            $v[$secuencia[0]][$secuencia[1]]['cirujano_id']=$cargo['cirujano_id'];
                                            $v[$secuencia[0]][$secuencia[1]]['consecutivo_procedimiento']=$cargo['consecutivo_procedimiento'];
                                            $v[$secuencia[0]][$secuencia[1]]['cargo_cups']=$cargo['cargo_cups'];
                                            $v[$secuencia[0]][$secuencia[1]]['tarifario_id']=$cargo['tarifario_id_procedimiento'];
                                            $v[$secuencia[0]][$secuencia[1]]['cargo']=$cargo['cargo_procedimiento'];
                                            $v[$secuencia[0]][$secuencia[1]]['descripcion']=$cargo['descripcion'];
                                            $v[$secuencia[0]][$secuencia[1]]['uvrs']=$cargo['uvrs'];

                                            $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['tarifario_id']=$cargo['tarifario_id'];
                                            $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['cargo']=$cargo['cargo'];
                                            $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['valor_cubierto']=$cargo['valor_cubierto'];
                                            $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['valor_no_cubierto']=$cargo['valor_nocubierto'];
                                            $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['PORCENTAJE']=$cargo['porcentaje'];
                                            $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['SECUENCIA']=$cargo['secuencia'];
                                            if(!empty($cargo['tipo_id_profesional']) && !empty($cargo['profesional_id'])){
                                                $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['tipo_id_tercero']=$cargo['tipo_id_profesional'];
                                                $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['tercero_id']=$cargo['profesional_id'];
                                            }
                    }
                            $vector[0]=$v;

                            $query="SELECT a.*
                            FROM (SELECT c.precio as precio_plan,c.cantidad,c.valor_cargo,c.valor_cubierto,c.valor_nocubierto as valor_no_cubierto,c.porcentaje_gravamen,
                            e.descripcion,c.tarifario_id,c.cargo,c.facturado,c.sw_cuota_paciente,c.sw_cuota_moderadora,c.valor_descuento_empresa,
                            c.valor_descuento_paciente,'fijo' as tipo_equipo,b.equipo_id,
                            (SELECT te.descripcion FROM qx_equipos_quirofanos te WHERE te.equipo_id=b.equipo_id) as descripcion_equipo,
                            b.duracion,c.cargo_cups
                            FROM cuentas_liquidaciones_qx_equipos_fijos b,
                            cuentas_codigos_agrupamiento a,cuentas_detalle c,tarifarios_detalle e
                            WHERE b.cuenta_liquidacion_qx_id='".$NoLiquidacion."'  AND
                            b.cuenta_liquidacion_qx_id=a.cuenta_liquidacion_qx_id AND
                            a.codigo_agrupamiento_id=c.codigo_agrupamiento_id AND
                            b.transaccion=c.transaccion AND
                            c.cargo=e.cargo AND c.tarifario_id=e.tarifario_id
                AND c.numerodecuenta='$Cuenta'
                            UNION
                            SELECT c.precio as precio_plan,c.cantidad,c.valor_cargo,c.valor_cubierto,c.valor_nocubierto as valor_no_cubierto,c.porcentaje_gravamen,
                            e.descripcion,c.tarifario_id,c.cargo,c.facturado,c.sw_cuota_paciente,c.sw_cuota_moderadora,c.valor_descuento_empresa,
                            c.valor_descuento_paciente,'movil' as tipo_equipo,b.equipo_id,
                            (SELECT te.descripcion FROM qx_equipos_moviles te WHERE te.equipo_id=b.equipo_id) as descripcion_equipo,
                            b.duracion,c.cargo_cups
                            FROM cuentas_liquidaciones_qx_equipos_moviles b,
                            cuentas_codigos_agrupamiento a,cuentas_detalle c,tarifarios_detalle e
                            WHERE b.cuenta_liquidacion_qx_id='".$NoLiquidacion."'  AND
                            b.cuenta_liquidacion_qx_id=a.cuenta_liquidacion_qx_id AND
                            a.codigo_agrupamiento_id=c.codigo_agrupamiento_id AND
                            b.transaccion=c.transaccion AND
                            c.cargo=e.cargo AND c.tarifario_id=e.tarifario_id
                AND c.numerodecuenta='$Cuenta'
                            ) a
                            ORDER BY a.tipo_equipo";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }
                            while(!$result->EOF){
                                $vars[]=$result->GetRowAssoc($toUpper=false);
                                $result->MoveNext();
                            }

                             $vector[1]=$vars;
            return $vector;
        }


        function DatosCargosCirugia($transaccion)
        {
                list($dbconn) = GetDBconn();
                $query = "SELECT b.descripcion as descar, b.cargo
                          FROM cuentas_cargos_qx_procedimientos as a, cuentas_liquidaciones_qx_procedimientos as c,
                                    cups as b
                                    WHERE a.transaccion=$transaccion  and a.consecutivo_procedimiento=c.consecutivo_procedimiento
                                    and c.cargo_cups=b.cargo";
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

        function BuscarHonorariosCirugia($transaccion)
        {
                list($dbconn) = GetDBconn();
                $query = "SELECT
                          FROM cuentas_detalle_honorarios as a
                                    WHERE a.transaccion=$transaccion";
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

      function InsertarPendientesCargar(){

            //IncludeLib('funciones_facturacion');
            $f=0;
            $arreglo='';
            foreach($_REQUEST as $k => $v){
                if(substr_count($k,'cargo')){
                    //0tarifario 1 cargo 2cups 3int 4ext 5tipotercero 6tercero
                    $var=explode('||',$v);

                    $arreglo[]=array('cargo'=>$var[1],'tarifario'=>$var[0],'servicio'=>$_REQUEST['servicio'],'aut_int'=>$var[3],'aut_ext'=>$var[4],'tipo_tercero'=>$var[5],'tercero'=>$var[6],'cups'=>$var[2],'cantidad'=>1,'departamento'=>$_REQUEST['departamento'],'sw_cargue'=>3);
                    $f++;
                }
            }
            if($f==0){
                $mensaje="ERROR DATOS VACIOS: DEBE ELEGIR ALGUN CARGO EQUIVALENTE.";
                $this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','',$mensaje,'','');
                return true;
            }
            $arregloUnico=$arreglo[0];
            list($dbconn) = GetDBconn();
            $query="SELECT sw_tipo_cargo
            FROM hc_sub_procedimientos_realizados_cups_dpto
            WHERE cargo_cups='".$arreglo[0]['cups']."'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }else{

                if($result->fields[0]=='QX'){
                    if($_REQUEST['TipoSala']==-1){
                        $mensaje="ERROR DATOS VACIOS: SELECCIONE EL TIPO DE SALA.";
                        $this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','',$mensaje,'','');
                        return true;
                    }
                    $arregloUnico=$arreglo[0];
                    //Modificacion de Lorena para la liquidacion de procedimientos QX
                    $dbconn->BeginTrans();
                    $arr=$_SESSION['DATOS_ARREGLO']['CARGOS_PENDIENTES_CARGAR_CUENTA'][0];

                    (list($fech,$hour)=explode(' ',$arr['fecha']));
                    (list($ano,$mes,$dia)=explode('-',$fech));
                    (list($hh,$mm)=explode(':',$hour));
                    $_SESSION['Liquidacion_QX']['FECHA_CIRUGIA']=$dia.'/'.$mes.'/'.$ano;
                    $_SESSION['Liquidacion_QX']['HORA_INICIO']=$hh;
                    $_SESSION['Liquidacion_QX']['MIN_INICIO']=$mm;
                    $_SESSION['Liquidacion_QX']['HORA_DURACION']=0;
                    $_SESSION['Liquidacion_QX']['MIN_DURACION']=0;
                    if(!$arr['tipo_sala_id']){$arr['tipo_sala_id']=$_REQUEST['TipoSala'];}
                    $_SESSION['Liquidacion_QX']['TIPO_SALA']=$_REQUEST['TipoSala'];
                    $_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$arr['tipo_id_tercero'].'||//'.$arr['tercero_id'].'||//'.$arr['nombre_tercero']][0]=$arr['cargo_cups'].'||//'.$arr['descups'].'||//'.'0';
                    $_SESSION['LIQUIDACION_QX']['Departamento']=$arr['departamento'];
                    $_SESSION['LIQUIDACION_QX']['Empresa']=$_SESSION['CUENTAS']['EMPRESA'];
                    $_SESSION['Liquidacion_QX']['FINALIDAD_CIRUGIA']='2';
                    $_SESSION['Liquidacion_QX']['AMBITO_CIRUGIA']='01';
                    $_SESSION['Liquidacion_QX']['VIA_ACCESO']='1';
                    $_SESSION['Liquidacion_QX']['TIPO_CIRUGIA']='01';
                    unset($_SESSION['Liquidacion_QX']['LIQUIDACION_ID']);
                    if($this->CallMetodoExterno('app','DatosLiquidacionQX','user','LlamaGuardarDatosCuentaLiquidacion')===true){

                        $query="UPDATE cuentas_liquidaciones_qx SET sw_derechos_cirujano='1',sw_derechos_anestesiologo='1',
                        sw_derechos_ayudante='1',sw_derechos_sala='1',
                        sw_derechos_materiales='1',sw_equipos_medicos='1',sw_medicamentos_consumo='1'
                        WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."'";
                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }else{
                            $query="SELECT consecutivo_procedimiento FROM   cuentas_liquidaciones_qx_procedimientos
                            WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."' AND cargo_cups='".$arr['cargo_cups']."'";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }
                            $consecutivo=$result->fields[0];
                            $query="INSERT INTO cuentas_liquidaciones_qx_procedimientos_cargos(
                            consecutivo_procedimiento,tarifario_id,cargo,sw_bilateral)VALUES
                            ('".$consecutivo."','".$arregloUnico['tarifario']."','".$arregloUnico['cargo']."','0')";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }else{
                                if (!IncludeClass("LiquidacionQX")){
                                    $this->frmError["MensajeError"]=$a->ErrMsg();
                                    $this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','',$mensaje,'','');
                                    return true;
                                }else{
                                    $a= new LiquidacionQX;
                                    if($a->SetDatosLiquidacion($_SESSION['Liquidacion_QX']['LIQUIDACION_ID'])===false){
                                        $this->frmError["MensajeError"]=$a->ErrMsg();
                                        $this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','',$mensaje,'','');
                                        return true;
                                    }else{
                                        if(($retorno = $a->GetLiquidacion())===false){
                                            $this->frmError["MensajeError"]=$a->ErrMsg();
                                            $this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','',$mensaje,'','');
                                            return true;
                                        }else{
                                            if(is_array($retorno)){
                                                $_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS']=$retorno;
                                                $query='';
                                                if($this->CallMetodoExterno('app','DatosLiquidacionQX','user','LlamaGuardarCuentaDetalle')===true){
                                                    $this->FormaCuentaCargosLiquidadosQX($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','',$mensaje,'','','',$_REQUEST['ID']);
                                                    return true;
                                                }else{
                                                    $this->frmError["MensajeError"]="Imposible Liquidar Este Cargo.";
                                                    $this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','',$mensaje,'','');
                                                    return true;
                                                }
                                            }else{
                                                $this->frmError["MensajeError"]="No se liquido ningun Procedimiento.";
                                                $this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','',$mensaje,'','');
                                                return true;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    //fin modificacion
                }else{
                    $insertar = InsertarCuentasDetalle($_REQUEST['empresa'],$_REQUEST['cu'],$_REQUEST['Cuenta'],$_REQUEST['PlanId'],$arreglo,'');

                    if(!empty($insertar)){
                        $mensaje="EL CARGO FUE AGREGADO A LA CUENTA.";

                        list($dbconn) = GetDBconn();
                        $query = "DELETE FROM procedimientos_pendientes_cargar_det
                                                                WHERE procedimiento_pendiente_cargar_id=".$_REQUEST['ID']."";
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }

                        $query = "DELETE FROM procedimientos_pendientes_cargar
                                                                WHERE procedimiento_pendiente_cargar_id=".$_REQUEST['ID']."";
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }
                    }else{
                        $mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR.";
                    }
                }
            }
            $this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','',$mensaje,'','');
            return true;
      }

        function LlamaFormaCuantaPendientesCargar(){
            list($dbconn) = GetDBconn();
            $query="DELETE FROM     cuentas_liquidacion_cargos
            WHERE cuentas_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }else{
              $query="DELETE FROM cuentas_liquidaciones_qx_procedimientos_cargos
              WHERE consecutivo_procedimiento
              IN (SELECT consecutivo_procedimiento FROM cuentas_liquidaciones_qx_procedimientos WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."')";
              $result = $dbconn->Execute($query);
              if($dbconn->ErrorNo() != 0){
                  $this->error = "Error al Cargar el Modulo";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
              }else{
                    $query="DELETE FROM cuentas_liquidaciones_qx_procedimientos
                    WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."'";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }else{
                        $query="DELETE FROM cuentas_liquidaciones_qx
                        WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."'";
                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }
                    }
                }
            }
            unset($_SESSION['Liquidacion_QX']);
            unset($_SESSION['LIQUIDACION_QX']);
            unset($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS']);
            $this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','',$mensaje,'','');
            return true;
        }

        function CargarALaCuentaPaciente(){
            if($this->CallMetodoExterno('app','DatosLiquidacionQX','user','CargarALaCuentaPaciente')===true){

                $mensaje="EL CARGO FUE AGREGADO A LA CUENTA.";
                list($dbconn) = GetDBconn();
                $query = "DELETE FROM procedimientos_pendientes_cargar_det
                                                        WHERE procedimiento_pendiente_cargar_id=".$_REQUEST['id']."";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                }
                $query = "DELETE FROM procedimientos_pendientes_cargar
                                                        WHERE procedimiento_pendiente_cargar_id=".$_REQUEST['id']."";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                }

                $this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','',$mensaje,'','');
                return true;
            }
        }

        function NombreTercero($tipo_id_tercero,$tercero_id){
        list($dbconn) = GetDBconn();
        $query="SELECT nombre_tercero
        FROM terceros
        WHERE tipo_id_tercero='".$tipo_id_tercero."' AND tercero_id='".$tercero_id."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }else{
          if($result->RecordCount()>0){
            $vars=$result->GetRowAssoc($toUpper=false);
          }
        }
        $result->Close();
        return $vars;
      }

        function DescripcionCargosCups($cargo_cups){
        list($dbconn) = GetDBconn();
        $query="SELECT descripcion
        FROM cups
        WHERE cargo='".$cargo_cups."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }else{
          if($result->RecordCount()>0){
            $vars=$result->GetRowAssoc($toUpper=false);
          }
        }
        $result->Close();
        return $vars;
      }

        function DescripcionCargosTarifario($tarifario_id){
        list($dbconn) = GetDBconn();
       $query="SELECT a.descripcion as tarifario
        FROM tarifarios a
        WHERE a.tarifario_id='".$tarifario_id."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }else{
          if($result->RecordCount()>0){
            $vars=$result->GetRowAssoc($toUpper=false);
          }
        }
        $result->Close();
        return $vars;
      }

//------------------------REPORTES-------------------------------------------

      /**
      *
      */
      function DatosFactura($cuenta)
      {//f.tipo_factura=g.tipo_factura and lo que se corto del query
            list($dbconn) = GetDBconn();
            $query = "select (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos) as abonos,
                      a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
                      c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
                      e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
                      e.residencia_telefono, e.residencia_direccion, f.prefijo, f.factura_fiscal,  d.departamento_actual as dpto, h.descripcion,
                      i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid, i.id, j.departamento, k.municipio, d.fecha_registro
                      from cuentas as a, planes as b, terceros as c, pacientes as e, fac_facturas_cuentas as f,  departamentos as  h,
                      empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d
                      where a.numerodecuenta=$cuenta and a.plan_id=b.plan_id and b.tercero_id=c.tercero_id
                      and b.tipo_tercero_id=c.tipo_id_tercero
                      and d.ingreso=a.ingreso and d.tipo_id_paciente=e.tipo_id_paciente
                      and d.paciente_id=e.paciente_id and a.numerodecuenta=f.numerodecuenta
                      and a.empresa_id=i.empresa_id and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
                      and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id
                      and d.departamento_actual=h.departamento";
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

        /*
        *
        */
        function Profesionales()
        {
                    list($dbconn) = GetDBconn();
                    $query = "SELECT tipo_id_tercero,tercero_id,nombre FROM profesionales
                                        WHERE tipo_profesional in('1','2') ORDER BY nombre";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                    }

                    while(!$result->EOF)
                    {
                            $vars[]=$result->GetRowAssoc($ToUpper = false);;
                            $result->MoveNext();
                    }

                    $result->Close();
                    return $vars;
        }

//---------------------------------HABITACIONES------------------------

        function CargarHabitacion()
        {
                $f=0;
                foreach($_REQUEST as $k => $v)
                {
                        if(substr_count($k,'HAB'))
                        {
                                $posiciones[]=$v;
                                $f++;
                        }
                }

                if($f==0)
                {
                        $mensaje="ERROR DATOS VACIOS: DEBE ELEGIR ALGUN CARGO DE HABITACION.";
                        $this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','',$mensaje,'','');
                        return true;
                }

                $arreglo=array('posiciones'=>$posiciones,'Cuenta'=>$_REQUEST['Cuenta'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso']);
                $msg='ESTA SEGURO DE CARGAR ESTA HABITACION A LA CUENTA No. '.$_REQUEST['Cuenta'];
                $this->ConfirmarAccion('CARGAR HABITACION A LA CUENTA No. '.$_REQUEST['Cuenta'],$msg,'ACEPTAR','CANCELAR',$arreglo,'app','Facturacion','CargarHabitacionCuenta','Cuenta');
                return true;
        }

        /*function CargarHabitacionCuenta()
        {
                $posiciones = $_REQUEST['posiciones'];
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();

                $liq = $_SESSION['CUENTAS']['CAMA']['LIQ'];
                for($i=0; $i<sizeof($posiciones); $i++)
                {
                        $liq = '';
                    $liq = $_SESSION['CUENTAS']['CAMA']['LIQ'][$posiciones[$i]];
                        //$agru=BuscarGrupoTipoCargo($liq[cargo_cups]);
                        if(!empty($agru))
                        {  $codigo=$agru[codigo_agrupamiento_id];  }

                        $query=" SELECT nextval('cuentas_detalle_transaccion_seq')";
                        $result=$dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0) {
                                $this->error = "Error SELECT nextval cuentas_detalle";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                $dbconn->RollbackTrans();
                                return false;
                        }
                        $Transaccion=$result->fields[0];

                        $liq[aut_int]='NULL';
                        $liq[aut_ext]='NULL';
                        $liq[departamento]='010201';
                        $liq[servicio]='4';


                        $query = "INSERT INTO cuentas_detalle (
                                                                transaccion,empresa_id,
                                                                centro_utilidad,numerodecuenta,
                                                                departamento,tarifario_id,
                                                                cargo,cantidad,
                                                                precio,valor_cargo,
                                                                valor_nocubierto,valor_cubierto,
                                                                usuario_id,facturado,
                                                                fecha_cargo,valor_descuento_empresa,
                                                                valor_descuento_paciente,servicio_cargo,
                                                                autorizacion_int,autorizacion_ext,
                                                                porcentaje_gravamen,sw_cuota_paciente,
                                                                sw_cuota_moderadora,codigo_agrupamiento_id,
                                                                fecha_registro,cargo_cups,sw_cargue)
                                            VALUES ($Transaccion,'".$_SESSION['CUENTAS']['EMPRESA']."','".$_SESSION['CUENTAS']['CENTROUTILIDAD']."',".$_REQUEST['Cuenta'].",'".$liq[departamento]."','".$liq[tarifario_id]."','".$liq[cargo]."',".$liq[cantidad].",".$liq[precio_plan].",".$liq[valor_cargo].",".$liq[valor_no_cubierto].",".$liq[valor_cubierto].",".UserGetUID().",".$liq[facturado].",'now()',".$liq[valor_descuento_paciente].",".$liq[valor_descuento_empresa].",".$liq[servicio].",".$liq[aut_int].",".$liq[aut_ext].",".$liq[porcentaje_gravamen].",'".$liq[sw_cuota_paciente]."','".$liq[sw_cuota_moderadora]."',".$codigo.",'now()','".$liq[cargo_cups]."','3')";
                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0) {
                                $this->error = "INSERT INTO cuentas_detalle";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                $dbconn->RollbackTrans();
                                return false;
                        }

                        /*for($j=0; $j<sizeof($liq[$i]['movimientos']); $j++)
                        {
                                $query = "UPDATE movimientos_habitacion
                                                    SET transaccion=$Transaccion
                                                    WHERE movimiento_id=".$liq[$i]['movimientos'][$j]."";
                                $result = $dbconn->Execute($query);
                                if($dbconn->ErrorNo() != 0) {
                                        $this->error = "UPDATE movimientos_habitacion";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->fileError = __FILE__;
                                        $this->lineError = __LINE__;
                                        $dbconn->RollbackTrans();
                                        return false;
                                }
                        }
                }
exit;
          $dbconn->CommitTrans();
                unset($_SESSION['CUENTAS']['CAMA']['LIQ']);
                $this->frmError["MensajeError"]="SE CARGO EN LA CUENTA LOS COBROS DE HABITACION.";
                $this->Cuenta();
                return true;
        }*/


        function LlamarFormaParcialCamas()
        {
                    $this->FormaParcialCamas();
                    return true;
        }

        function LiquidarTodosCargosCamaSinCargar($Ingreso,$Plan,$Cuenta)
        {
                    IncludeLib('funciones_liquidacion_cargos');
                    $hab=GetDatosDias_X_Cargos($Ingreso,false);
                    $j=$total=0;
                    if(!empty($hab) AND !empty($hab[0]['tipo_clase_cama_id']))
                    {
                            for($i=0; $i<sizeof($hab);)
                            {
                                    $d=$i;
                                    $liq = BuscarLiqHabitacion($hab[$i][tipo_clase_cama_id],$Plan);
                                    while($hab[$i][tipo_clase_cama_id]==$hab[$d][tipo_clase_cama_id])
                                    {
                                            $x=0;
                                            $j=$d;
                                            $mov='';
                                            while($hab[$d][cargo]==$hab[$j][cargo])
                                            {
                                                    $mov[] = $hab[$j][movimiento_id];
                                                    $j++;
                                            }
                                            $d=$j-1;
                                            //valida equivalencias
                                            if(is_array($liq))
                                            {
                                                    $equi = ValdiarEquivalencias($Plan,$hab[$d][cargo]);
                                                    if(!empty($equi))
                                                    {
                                                            //liquidacion cama
                                                            $arreglo='';
                                                            $arreglo[0]=array('cargo_cups'=>$hab[$d][cargo],'fecha_ingreso'=>$hab[$d][fecha_ingreso],'fecha_egreso'=>$hab[$d][fecha_egreso],'precio'=>$hab[$d][precio],'cama'=>$hab[$d][cama],'tipo_clase_cama_id'=>$hab[$d][tipo_clase_cama_id],'cargo'=>$equi[0][cargo],'tarifario_id'=>$equi[0][tarifario_id],'departamento'=>$hab[$d][departamento],'movimiento_id'=>$mov);
                                                            $liq = LiquidarCamas($arreglo,$Plan,$Cuenta);
                                                            $total+=$liq[0][valor_cargo];
                                                            //fin liqudacion cama
                                                    }
                                            }
                                            $d++;
                                    }
                                    $j++;
                                    $i=$d;
                                }
                        }
                return $total;
        }

        

            //ARRANQUE CALI LORENA
            function LlamaFormaDevolverMedicamentos(){
                $this->FormaDevolverMedicamentos($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['desc'],$_REQUEST['codigo'],$_REQUEST['documento'],$_REQUEST['numeracion'],$_REQUEST['Transaccion'],$_REQUEST['noFacturado']);
                return true;
            }

        function BodegaDocumento($documento){
          list($dbconn) = GetDBconn();
          $query = "SELECT a.bodega,b.descripcion
          FROM  bodegas_doc_numeraciones a,bodegas b
          WHERE a.bodegas_doc_id=".$documento."
          AND a.empresa_id=b.empresa_id
          AND a.centro_utilidad=b.centro_utilidad
          AND a.bodega=b.bodega";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }else{
            $vars=$result->GetRowAssoc($ToUpper = false);
          }
          $result->Close();
          return $vars;
        }

        function ConfirmacionPermisoDevolucuionUsuario($bodega){
          list($dbconn) = GetDBconn();
          $query = "SELECT *
          FROM bodegas_usuarios_devoluciones_cuentas a
          WHERE a.bodega='".$bodega."'
          AND a.usuario_id='".UserGetUID()."'
          AND a.centro_utilidad='".$_SESSION['CUENTAS']['EMPRESA']."'
          AND a.empresa_id='".$_SESSION['CUENTAS']['CENTROUTILIDAD']."'";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }else{
            if($result->RecordCount()>0){
              return 1;
            }
          }
          $result->Close();
          return 0;
        }

            function MedicamentosDocumentoBodega($Cuenta,$codigo,$filtrar){
                 list($dbconn) = GetDBconn();
                 if($filtrar==1){
                        $filtro=" AND a.facturado='0'";
                 }else{
                        $filtro=" AND a.facturado='1'";
                 }


                 $query = "SELECT a.*,(a.cant_cargada-(coalesce(a.cantidad,0))) as cantidad
                                             FROM (select a.codigo_agrupamiento_id,a.consecutivo,a.departamento,a.transaccion,a.empresa_id,a.centro_utilidad,a.cantidad as cant_cargada,c.descripcion, b.codigo_producto,e.sw_control_fecha_vencimiento,f.descripcion as nom_bodega,f.bodega,
                                                    (SELECT sum(xx.cantidad) as cantidad FROM bodegas_documentos_devolucion_cuentas xx WHERE a.transaccion=xx.transaccion_cargue_cuenta) as cantidad
                                                    from cuentas_detalle as a,
                                                    bodegas_documentos_d as b,
                                                    inventarios_productos as c,
                                                    bodegas_doc_numeraciones d,
                                                    existencias_bodegas e,
                                                    bodegas f
                                                    where a.codigo_agrupamiento_id='".$codigo."'
                                                    and a.numerodecuenta=$Cuenta
                                                    and a.consecutivo=b.consecutivo
                                                    and b.codigo_producto=c.codigo_producto
                                                    and b.bodegas_doc_id=d.bodegas_doc_id
                                                    and d.empresa_id=e.empresa_id
                                                    and d.centro_utilidad=e.centro_utilidad
                                                    and d.bodega=e.bodega
                                                    and e.codigo_producto=b.codigo_producto
                                                    and d.bodega=f.bodega
                                                    $filtro) a
                                                    WHERE
                                                    (a.cant_cargada-(coalesce(a.cantidad,0))) > 0";
                    /*
                    (SELECT a.consecutivo_cargue_cuenta,b.cantidad
                                                    FROM bodegas_documentos_devolucion_cuentas a,cuentas_detalle b
                                                    WHERE a.codigo_agrupamiento_id_cargue='".$codigo."' AND
                                                    a.consecutivo_descargue_cuenta=b.consecutivo AND a.codigo_agrupamiento_id_descargue=b.codigo_agrupamiento_id) tabla
                    */
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

            function MedicamentosDocumentoBodegaNoDevolucion($Cuenta,$codigo,$noFacturado){
                 list($dbconn) = GetDBconn();
                 if($noFacturado==1){
                        $filtro=" AND a.facturado='0'";
                 }else{
                        $filtro=" AND a.facturado='1'";
                 }



                 $query = "select a.*,c.descripcion, b.codigo_producto,e.sw_control_fecha_vencimiento,f.descripcion as nom_bodega,f.bodega
                                                    from cuentas_detalle as a,
                                                    bodegas_documentos_d as b,
                                                    inventarios_productos as c,
                                                    bodegas_doc_numeraciones d,
                                                    existencias_bodegas e,
                                                    bodegas f
                                                    where a.codigo_agrupamiento_id='".$codigo."'
                                                    and a.numerodecuenta=$Cuenta
                                                    and a.consecutivo=b.consecutivo
                                                    and b.codigo_producto=c.codigo_producto
                                                    and b.bodegas_doc_id=d.bodegas_doc_id
                                                    and d.empresa_id=e.empresa_id
                                                    and d.centro_utilidad=e.centro_utilidad
                                                    and d.bodega=e.bodega
                                                    and e.codigo_producto=b.codigo_producto
                                                    and d.bodega=f.bodega
                                                    $filtro";
                    /*
                    (SELECT a.consecutivo_cargue_cuenta,b.cantidad
                                                    FROM bodegas_documentos_devolucion_cuentas a,cuentas_detalle b
                                                    WHERE a.codigo_agrupamiento_id_cargue='".$codigo."' AND
                                                    a.consecutivo_descargue_cuenta=b.consecutivo AND a.codigo_agrupamiento_id_descargue=b.codigo_agrupamiento_id) tabla
                    */
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

        function InsertarFechaVencimientoLote(){

            $cantidades=$_REQUEST['cantidadDevol'];
            $Consecutivos=$_REQUEST['Consecutivos'];
            $RequiereFechas=$_REQUEST['RequiereFechas'];
            $MotivosDevolucion=$_REQUEST['MotivosDevolucion'];
            if($_REQUEST['Devolver']){
                foreach($cantidades as $codigoProducto=>$cantidadSelect){
                    if($cantidadSelect!=-1){
                        $_SESSION['FACTURACION_CUENTAS']['motivosDevolucion']=$MotivosDevolucion;
                        $_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CONSECUTIVOS_DEV']=$Consecutivos;
                        $_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CANTIDADES_DEV'][$codigoProducto]=$cantidadSelect;
                        if($MotivosDevolucion[$codigoProducto]==-1){
                            $this->frmError["MensajeError"]="Error en el producto ".$codigoProducto." ;Debe Seleccionar el Motivo de la Devolucion";
                            $this->FormaDevolverMedicamentos($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['desc'],$_REQUEST['codigo'],$_REQUEST['documento'],$_REQUEST['numeracion'],$_REQUEST['Transaccion'],$_REQUEST['noFacturado']);
                            return true;
                        }
                        if($RequiereFechas[$codigoProducto]==1){
                            $sumaCantLotes=0;
                            foreach($_SESSION['FACTURACION_CUENTAS']['FECHAS_VENCE'][$codigoProducto] as  $lote=>$arreglo){
                                (list($cantidades,$fecha)=explode('||//',$arreglo));
                                $sumaCantLotes+=$cantidades;
                            }
                            if($sumaCantLotes < $cantidadSelect){
                                $this->frmError["MensajeError"]="Error en el producto ".$codigoProducto." ;Debe Registrar los lotes y fechas de vencimiento de la cantidades Seleccionadas";
                                $this->FormaDevolverMedicamentos($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['desc'],$_REQUEST['codigo'],$_REQUEST['documento'],$_REQUEST['numeracion'],$_REQUEST['Transaccion'],$_REQUEST['noFacturado']);
                                return true;
                            }
                        }
                    }else{
                        unset($_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CANTIDADES_DEV'][$codigoProducto]);
                        unset($_SESSION['FACTURACION_CUENTAS']['FECHAS_VENCE'][$codigoProducto]);
                        unset($_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CONSECUTIVOS_DEV'][$codigoProducto]);
                        unset($_SESSION['FACTURACION_CUENTAS']['motivosDevolucion'][$codigoProducto]);
                    }
                }
                $_SESSION['FACTURACION_CUENTAS']['CUENTA']=$_REQUEST['Cuenta'];
                $_SESSION['FACTURACION_CUENTAS']['PLAN']=$_REQUEST['PlanId'];
                $_SESSION['FACTURACION_CUENTAS']['Departamento']=$_REQUEST['Departamento'];
                $_SESSION['FACTURACION_CUENTAS']['Empresa']=$_SESSION['CUENTAS']['EMPRESA'];
              $_SESSION['FACTURACION_CUENTAS']['Centro_Utilidad']=$_SESSION['CUENTAS']['CENTROUTILIDAD'];
              $_SESSION['FACTURACION_CUENTAS']['Bodega']=$_REQUEST['BodegasProd'];
                $_SESSION['FACTURACION_CUENTAS']['codigoAgrupamientoCargue']=$_REQUEST['codigo'];
                $_SESSION['FACTURACION_CUENTAS']['motivosDevolucion']=$MotivosDevolucion;
                $_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CONSECUTIVOS_DEV']=$Consecutivos;

                $retorno=$this->CallMetodoExterno('app','InvBodegas','user','DevolucionIyMCargosCuenta');
                if($retorno==false){
                    $this->frmError["MensajeError"]=$_SESSION['FACTURACION_CUENTAS']['RETORNO']['Mensaje_Error'];
                }else{
                    $this->frmError["MensajeError"]="Devoluciones Realizadas Satisfactoriamente";
                }
                unset($_SESSION['FACTURACION_CUENTAS']['CUENTA']);
                unset($_SESSION['FACTURACION_CUENTAS']['PLAN']);
                unset($_SESSION['FACTURACION_CUENTAS']['RETORNO']['Mensaje_Error']);
                unset($_SESSION['FACTURACION_CUENTAS']['Departamento']);
                unset($_SESSION['FACTURACION_CUENTAS']['Empresa']);
                unset($_SESSION['FACTURACION_CUENTAS']['Centro_Utilidad']);
                unset($_SESSION['FACTURACION_CUENTAS']['Bodega']);
                unset($_SESSION['FACTURACION_CUENTAS']['codigoAgrupamientoCargue']);
                unset($_SESSION['FACTURACION_CUENTAS']['motivosDevolucion']);
                unset($_REQUEST['cantidadDevol']);
                unset($_REQUEST['Consecutivos']);
                unset($_REQUEST['RequiereFechas']);
                unset($_REQUEST['MotivosDevolucion']);
                $this->FormaDevolverMedicamentos($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['desc'],$_REQUEST['codigo'],$_REQUEST['documento'],$_REQUEST['numeracion'],$_REQUEST['Transaccion'],$_REQUEST['noFacturado']);
                return true;
            }
            if($_REQUEST['volver']){
                unset($_SESSION['FACTURACION_CUENTAS']);
                $var=$this->MedicamentosDocumentoBodegaNoDevolucion($_REQUEST['Cuenta'],$_REQUEST['codigo'],$_REQUEST['noFacturado']);
                $this->FormaDetalleCodigo($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$var,$_REQUEST['desc'],$_REQUEST['codigo'],$_REQUEST['documento'],$_REQUEST['numeracion'],$_REQUEST['Transaccion']);
                return true;
            }
            $_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CANTIDADES_DEV'][$_REQUEST['codigoProducto']]=$cantidades[$_REQUEST['codigoProducto']];
            $_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CONSECUTIVOS_DEV'][$_REQUEST['codigoProducto']]=$Consecutivos[$_REQUEST['codigoProducto']];
            $_SESSION['FACTURACION_CUENTAS']['motivosDevolucion'][$_REQUEST['codigoProducto']]=$MotivosDevolucion[$_REQUEST['codigoProducto']];
            $_SESSION['FACTURACION_CUENTAS']['FECHAS_VENCE'][$_REQUEST['codigoProducto']][$_REQUEST['lote']]=$_REQUEST['cantidad'].'||//'.$_REQUEST['fechaVencimiento'];
            $_REQUEST['lote']='';$_REQUEST['cantidad']='';$_REQUEST['fechaVencimiento']='';
            $this->FormaDevolverMedicamentos($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['desc'],$_REQUEST['codigo'],$_REQUEST['documento'],$_REQUEST['numeracion'],$_REQUEST['Transaccion'],$_REQUEST['noFacturado']);
            return true;

        }

        function MotivosDevolucionIyM(){
            list($dbconn) = GetDBconn();
            $query = "SELECT motivo_devolucion_id,descripcion
            FROM bodegas_documentos_devolucion_motivos";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }else{
                if($result->EOF){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "La tabla 'tmp_cuenta_imd' esta vacia ";
                    return false;
                }else{
            while(!$result->EOF){
              $vars[]=$result->GetRowAssoc($toUpper=false);
                      $result->MoveNext();
                  }
                }
            }
            return $vars;
        }

        function EliminarFechaVencimientos(){
        unset($_SESSION['FACTURACION_CUENTAS']['FECHAS_VENCE'][$_REQUEST['codigoProducto']][$_REQUEST['lote']]);
            $_REQUEST['lote']='';
        $this->FormaDevolverMedicamentos($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['desc'],$_REQUEST['codigo'],$_REQUEST['documento'],$_REQUEST['numeracion'],$_REQUEST['Transaccion'],$_REQUEST['noFacturado']);
            return true;
      }

        function CargosMedicamentosCuentaPaciente($NoLiquidacion,$Cuenta){
        $query="SELECT c.codigo_producto,sum(c.cantidad) as cantidad,sum(b.valor_cubierto) as valor_cubierto,
        sum(b.valor_nocubierto) as valor_nocubierto,b.facturado,
        (SELECT d.descripcion FROM inventarios_productos d WHERE c.codigo_producto=d.codigo_producto) as descripcion
        FROM cuentas_codigos_agrupamiento a,cuentas_detalle b,bodegas_documentos_d c
        WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND b.cargo='IMD' AND
        a.bodegas_doc_id=c.bodegas_doc_id AND a.numeracion=c.numeracion AND b.consecutivo=c.consecutivo
        AND b.numerodecuenta=$Cuenta
        GROUP BY c.codigo_producto,b.facturado";
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }else{
          if($result->RecordCount()>0){
            while(!$result->EOF){
              $vars[]=$result->GetRowAssoc($toUpper=false);
              $result->MoveNext();
            }
          }
        }
        return $vars;
      }


        function CargosMedicamentosCuentaPacienteDevol($NoLiquidacion,$Cuenta){
        $query="SELECT c.codigo_producto,sum(c.cantidad) as cantidad,sum(b.valor_cubierto) as valor_cubierto,
        sum(b.valor_nocubierto) as valor_nocubierto,b.facturado,
        (SELECT d.descripcion FROM inventarios_productos d WHERE c.codigo_producto=d.codigo_producto) as descripcion
        FROM cuentas_codigos_agrupamiento a,cuentas_detalle b,bodegas_documentos_d c
        WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND b.cargo='DIMD' AND
        a.bodegas_doc_id=c.bodegas_doc_id AND a.numeracion=c.numeracion AND b.consecutivo=c.consecutivo
        AND b.numerodecuenta=$Cuenta
        GROUP BY c.codigo_producto,b.facturado";
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }else{
          if($result->RecordCount()>0){
            while(!$result->EOF){
              $vars[]=$result->GetRowAssoc($toUpper=false);
              $result->MoveNext();
            }
          }
        }
        return $vars;
      }

            //FIN ARRANQUE CALI LORENA

        /**
         * Retorna un arreglo con los motivos de cambio de la cuota
         * paciente o copago, el arreglo debe tener la misma estructura del
         * metodo GetMotivosCambioCuotaModeradora
         *
         * Array([] =>array(motivo_cambio_id=>"valor" ,descripcion=>"valor"))
         *
         * @return array
         */
        function GetMotivosCambioCopago()
        {
            $sql = "
                SELECT
                    motivo_cambio_copago_id as motivo_cambio_id,
                    descripcion
                FROM
                    motivos_cambio_copago
                ORDER BY
                    2";
            list($dbconn) = GetDBconn();
            GLOBAL $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Consultar motivos_cambio_copago";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if($result->EOF)
                return array();
            $motivos = $result->GetRows();
            return $motivos;
        }//Fin GetMotivosCambioCopago

        /**
         * Retorna un arreglo con los motivos de cambio de la cuota moderadora
         * el arreglo debe tener la misma estructura del
         * metodo GetMotivosCambioCopago
         *
         * Array([] =>array(motivo_cambio_id=>"valor" ,descripcion=>"valor"))
         *
         * @return array
         */
        function GetMotivosCambioCuotaModeradora()
        {
            $sql = "
                SELECT
                    motivo_cambio_cuota_moderadora_id as motivo_cambio_id,
                    descripcion
                FROM
                    motivos_cambio_cuota_moderadora
                ORDER BY
                    2";
            list($dbconn) = GetDBconn();
            GLOBAL $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Consultar motivos_cambio_cuota_moderadora";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if($result->EOF)
                return array();
            $motivos = $result->GetRows();
            return $motivos;
        }//Fin GetMotivosCambioCuotaModeradora

        /**
         * Inserta o modifica un registro en la tabla cuentas_modificacion_copago
         *
         * @param int numero_cuenta
         * @param int valor
         * @param string tipo_id_motivo
         * @param string observacion
         * @return bool
         */
        function SetCuotaPaciente($numero_cuenta,$valor,$tipo_id_motivo,$observacion)
        {
            $sql = "
            SELECT COUNT(*)
            FROM cuentas_modificacion_copago
            WHERE numerodecuenta = $numero_cuenta";
            list($dbconn) = GetDBconn();
            $existe = (int)$dbconn->GetOne($sql);
            if($existe === 0)
            {
                $sql = "
                INSERT INTO cuentas_modificacion_copago
                (
                    numerodecuenta,
                    valor,
                    motivo_cambio_copago_id,
                    observacion,
                    fecha_registro,
                    usuario_id
                )
                VALUES
                (
                    $numero_cuenta,
                    $valor,
                    '$tipo_id_motivo',
                    '$observacion',
                    'now()',
                    ".UserGetUID()."
                )";
            }
            elseif($existe === 1)
            {
                $sql = "
                UPDATE cuentas_modificacion_copago
                SET
                    valor = $valor,
                    motivo_cambio_copago_id = '$tipo_id_motivo',
                    observacion = '$observacion',
                    fecha_registro = 'now()',
                    usuario_id = ".UserGetUID()."
                WHERE
                    numerodecuenta = $numero_cuenta";
            }
            else
            {
                $this->error = "Error al insertar o modificar en cuentas_modificacion_copago";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $rs = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al insertar o modificar en cuentas_modificacion_copago";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            return true;
        }//Fin SetCuotaPaciente

        /**
         * Inserta o modifica un registro en la tabla cuentas_modificacion_cuota_moderadora
         *
         * @param int numero_cuenta
         * @param int valor
         * @param string tipo_id_motivo
         * @param string observacion
         * @return bool
         */
        function SetCuotaModeradora($numero_cuenta,$valor,$tipo_id_motivo,$observacion)
        {
            $sql = "
            SELECT COUNT(*)
            FROM cuentas_modificacion_cuota_moderadora
            WHERE numerodecuenta = $numero_cuenta";
            list($dbconn) = GetDBconn();
            $existe = (int)$dbconn->GetOne($sql);
            if($existe === 0)
            {
                $sql = "
                INSERT INTO cuentas_modificacion_cuota_moderadora
                (
                    numerodecuenta,
                    valor,
                    motivo_cambio_cuota_moderadora_id,
                    observacion,
                    fecha_registro,
                    usuario_id
                )
                VALUES
                (
                    $numero_cuenta,
                    $valor,
                    '$tipo_id_motivo',
                    '$observacion',
                    'now()',
                    ".UserGetUID()."
                )";
            }
            elseif($existe === 1)
            {
                $sql = "
                UPDATE cuentas_modificacion_cuota_moderadora
                SET
                    valor = $valor,
                    motivo_cambio_cuota_moderadora_id = '$tipo_id_motivo',
                    observacion = '$observacion',
                    fecha_registro = 'now()',
                    usuario_id = ".UserGetUID()."
                WHERE
                    numerodecuenta = $numero_cuenta";
            }
            else
            {
                $this->error = "Error al insertar o modificar en cuentas_modificacion_copago";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $rs = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al insertar o modificar en cuentas_modificacion_copago";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            return true;
        }//Fin SetCuotaModeradora

        /**
         * Retorna true o false de acuerdo al tipo de liquidacion del plan que tenga
         * asignado la cuenta
         *
         * 1=>True
         * 2=>True
         * 3=>False
         *
         * @param int Cuenta
         * @return bool
         */
        function GetMostrarCopagoCuotaModeradora($Cuenta)
        {
            if(empty($Cuenta))
            {
                $this->error = "Error GetTipoLiquidacionCuenta";
                $this->mensajeDeError = "El parámetro Cuenta esta empty";
                return false;
            }
            $sql = "
                SELECT
                    tipo_liquidacion_cargo
                FROM
                    planes A,
                    cuentas B
                WHERE
                    A.plan_id=B.plan_id
                    AND B.numerodecuenta = $Cuenta";
            list($dbconn) = GetDBconn();
            $TipoLiquidacion = (int)$dbconn->GetOne($sql);
            switch($TipoLiquidacion)
            {
                case 3: return false;//Tipo de liquidacion(NO APLICA) que no cobra copago y cuota moderadora
                default: return true;
            }
            return true;
        }//Fin GetTipoLiquidacionCuenta
            //MauroB
            /**
            * Determina el tipo de rips del cargo para pedir los datos necesarios para el rips
            * solo los almacena al tmp de cuenta detalle si ingresa los datos necesarios del rips
            * Los llamados y asignaciones a los valores de la session $_SESSION['TMP_DATOS'] son
            * utilizados solo en este metodo
            * @param String cargos_cups
            * @return Array Datos
            */

            function PideDatosAdicionalesRips($cargos_cups,$tarifario_id,$EmpresaId,$arreglo,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)
            {
                if(!empty($_REQUEST[cargos_cups]))
                {
                    $cargos_cups=$_REQUEST[cargos_cups];
                }
                $accion = 'pidedatos';
                $mensaje_tmp = $mensaje;
                if(!empty($_REQUEST['datos']))
                    $accion = $_REQUEST['datos'];
                if(!empty($_SESSION['TMP_DATOS']['Cuenta']))
                {
                    $arreglo=$_SESSION['TMP_DATOS']['arreglo'];$Cuenta=$_SESSION['TMP_DATOS']['Cuenta'];$TipoId=$_SESSION['TMP_DATOS']['TipoId'];
                    $PacienteId=$_SESSION['TMP_DATOS']['PacienteId'];$Nivel=$_SESSION['TMP_DATOS']['Nivel'];$PlanId=$_SESSION['TMP_DATOS']['PlanId'];
                    $Ingreso=$_SESSION['TMP_DATOS']['Ingreso'];$Fecha=$_SESSION['TMP_DATOS']['Fecha'];$mensaje=$_SESSION['TMP_DATOS']['mensaje'];
                    $D=$_SESSION['TMP_DATOS']['D'];$var=$_SESSION['TMP_DATOS']['var'];$ValEmpresa=$_SESSION['TMP_DATOS']['ValEmpresa'];
                    $Cobertura=$_SESSION['TMP_DATOS']['Cobertura'];$datos_cups = $_SESSION['TMP_DATOS']['datos_cups'];
                    $tipo_rips = $_SESSION['TMP_DATOS']['tipo_rips'];
                }
                else
                {
                        $_SESSION['TMP_DATOS']['cargos_cups']=$cargos_cups ;$_SESSION['TMP_DATOS']['tarifario_id']=$tarifario_id ;$_SESSION['TMP_DATOS']['EmpresaId']= $EmpresaId;
                        $_SESSION['TMP_DATOS']['arreglo']= $arreglo;$_SESSION['TMP_DATOS']['Cuenta']=$Cuenta ;$_SESSION['TMP_DATOS']['TipoId']= $TipoId;
                        $_SESSION['TMP_DATOS']['PacienteId']= $PacienteId;$_SESSION['TMP_DATOS']['Nivel']=$Nivel ;$_SESSION['TMP_DATOS']['PlanId']= $PlanId;
                        $_SESSION['TMP_DATOS']['Ingreso']= $Ingreso;$_SESSION['TMP_DATOS']['Fecha']= $Fecha;$_SESSION['TMP_DATOS']['mensaje']= $mensaje;
                        $_SESSION['TMP_DATOS']['D']= $D;$_SESSION['TMP_DATOS']['var']= $var;$_SESSION['TMP_DATOS']['ValEmpresa']= $ValEmpresa;
                        $_SESSION['TMP_DATOS']['Cobertura']= $Cobertura;
                }
                if (IncludeClass("rips"))
                {
                    $rips = new rips;
                    $ConsultaTipoRips                   = ModuloGetVar('app','Facturacion_Fiscal','ConsultaTipoRips');
                    if(!$datos_cups){
                        $datos_cups                             = $rips->GetDatosCups($cargos_cups);
                    }
                    if(!$tipo_rips){
                        $tipo_rips                              = $rips->GetTipoRips($ConsultaTipoRips,$datos_cups[tipo_cargo],$datos_cups[grupo_tipo_cargo],$datos_cups[grupo_tarifario_id],$datos_cups[subgrupo_tarifario_id],$tarifario_id,$cargos_cups,$EmpresaId);
                    }
                    $viasingreso       = $rips->GetViasIngreso();
                    $_SESSION['TMP_DATOS']['datos_cups'] = $datos_cups;
                    $_SESSION['TMP_DATOS']['tipo_rips'] = $tipo_rips;
		    echo $accion;
                    if($accion == 'pidedatos')
                    {unset($_REQUEST);
                            $sw_dato_complementario     = $rips->GetSwDatosComplementarios($cargos_cups);
                            switch ($tipo_rips)
                            {
                                case 'AC':
                                {
                                            $this->FormaPideDatosAdicionalesRipsAC($cargos_cups,$mensaje_tmp,$sw_dato_complementario,$viasingreso);
                                            break;
                                }
                                case 'AP':
                                {
                                            $this->FormaPideDatosAdicionalesRipsAP($cargos_cups);
                                            break;
                                }
                                case 'AT':
                                {
                                            $this->FormaPideDatosAdicionalesRipsAT($cargos_cups,$datos_cups);
                                            break;
                                }
                                case 'AU':
                                {
                                            $this->FormaPideDatosAdicionalesRipsAU($cargos_cups,$datos_cups);
                                            break;
                                }
//                          case 'AM':
//                          {
//                                      $this->FormaPideDatosAdicionalesRipsAM();
//                                      break;
//                          }
                                case 'AH':
                                {
					$this->FormaPideDatosAdicionalesRipsAH($cargos_cups,$datos_cups,$viasingreso);
                                            break;
                                }
                                default:
                                {
                                            //break;
                                          return 'sin_tipo_rips';
                                }
                            }//fin switch
                    }
                    elseif($accion == 'adiciona')
                    {
                        $validacion = $this->validaInformacionRips($tipo_rips,$_REQUEST[dato_complementario]);
                        if($validacion)
                        {
                            $_REQUEST[numerodecuenta] = $Cuenta;

                            $inserta_rips = $rips->InsertaRipsCuentasDetalle();
                            if($inserta_rips)
                            {   //NO SE INSERTA EN TmpCuentasDetalle
                                    $mensaje="LOS DATOS DEL CARGO SE GUARDARON PARA RIPS(rips_cuentas_detalle).";
                                    unset($_SESSION['TMP_DATOS']);
                                    $insertar_tmp = InsertarTmpCuentasDetalle($_SESSION['CUENTAS']['EMPRESA'],$_SESSION['CUENTAS']['CENTROUTILIDAD'],$Cuenta,$PlanId,$arreglo);
                                    if(!empty($insertar_tmp))
                                    {
                                        $mensaje.=" - (tmp_cuentas_detalle).";
                                        unset($_SESSION['TMP_DATOS']);
                                    }
                                    else
                                    {
                                        $mensaje.=" - (no se guandaron en tmp_cuentas_detalle).";
                                        unset($_SESSION['TMP_DATOS']);
                                    }

                                    $this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
                                    return true;

/*                      $insertar = InsertarTmpCuentasDetalle($_SESSION['CUENTAS']['EMPRESA'],$_SESSION['CUENTAS']['CENTROUTILIDAD'],$Cuenta,$PlanId,$arreglo);
                                if(!empty($insertar))
                                {
                                    $mensaje="EL CARGO FUE GRABADO.";
                                    unset($_SESSION['TMP_DATOS']);
                                }
                                else
                                {
                                    $mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR EN TmpCuentasDetalle.";
                                    unset($_SESSION['TMP_DATOS']);
                                }*/
                            }
                            else
                            {
                                $mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR EN rips_cuentas_detalle.";
                                unset($_SESSION['TMP_DATOS']);
                                //$_REQUEST['datos'] = 'pidedatos';

                            }
                            //$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
                            //$_REQUEST['datos'] = 'pidedatos';
                            $this->PideDatosAdicionalesRips($cargos_cups,$tarifario_id,$EmpresaId,$arreglo,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,$ValEmpresa,$Cobertura);
                            unset($_SESSION['TMP_DATOS']);
                        }
                        else
                        {
                            $_REQUEST['datos'] = 'pidedatos';
                            $mensaje="ERROR: TODOS LOS DATOS PARA RIPS SON OBLIGATORIOS PARA AGREGAR UN CARGO.";
                            //$this->PideDatosAdicionalesRips($cargos_cups,$tarifario_id,$EmpresaId,$arreglo,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,$ValEmpresa,$Cobertura);
                            $this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
                            //$this->frmError["MensajeError"]='TODOS LOS CAMPOS SON OBLIGATORIOS(AU).';
                        }
                        return true;
                    }
                    elseif($accion == 'cancela')
                    {
                        $mensaje="SE CANCELO LA ADICION DEL CARGO";
                        $this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
                        unset($_SESSION['TMP_DATOS']);
                        return true;
                    }
                }
                else
                {
                    echo "No se pudo cargar la clase RIPS";
                }

                return true;

            }
            /**
            * Trae los tipos de finalidad de una consulta
            *   @return Array
            */
            function ConsultaTiposFinalidad()
            {
                    list($dbconn) = GetDBconn();

              $query = "
                    SELECT  tipo_finalidad_id,
                                    detalle
                    FROM        hc_tipos_finalidad
                    ORDER BY tipo_finalidad_id
                ";
                global $ADODB_FETCH_MODE;
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($query);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Consultar los tipos de finalidad";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    echo $this->mensajeDeError;
                    return false;
                }
                while(!$result->EOF)
                {
                    $dato = $result->FetchRow();
                    $var[]= $dato;
                }

                $result->Close();
                return $var;
            }

            /**
            * Trae los tipos de Causas externas de una consulta
            *   @return Array
            */
            function ConsultaCausaExterna()
            {
                    list($dbconn) = GetDBconn();

              $query = "
                    SELECT  causa_externa_id,
                                    descripcion
                    FROM        causas_externas
                    ORDER BY causa_externa_id
                ";
                global $ADODB_FETCH_MODE;
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($query);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Consultar los tipos de Causas externas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    echo $this->mensajeDeError;
                    return false;
                }
                while(!$result->EOF)
                {
                    $dato = $result->FetchRow();
                    $var[]= $dato;
                }

                $result->Close();
                return $var;
            }
            /**
            * Trae los tipos de Diagnostico para una consulta
            *   @return Array
            */
            function ConsultaDiagnostico()
            {
                    list($dbconn) = GetDBconn();

              $query = "
                    SELECT  diagnostico_id,
                                    diagnostico_nombre
                    FROM        diagnosticos
                    ORDER BY diagnostico_nombre
                ";
                global $ADODB_FETCH_MODE;
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($query);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Consultar los tipos de Diagnostico";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    echo $this->mensajeDeError;
                    return false;
                }
                while(!$result->EOF)
                {
                    $dato = $result->FetchRow();
                    $var[]= $dato;
                }

                $result->Close();
                return $var;
            }

            /**
            * Valida que ingresen los datos necesarios para los rips
            */
            function validaInformacionRips($tipo_rips,$sw_dato_complementario)
            {
                $filtroAH = '';
                $filtroAU = '';
                $filtroAN = '';
                switch ($tipo_rips)
                {
                    case 'AC':
                    {
                                $sw_valida = '0';
                                if(!empty($_REQUEST['ac_fechaconsulta']) AND !empty($_REQUEST['ac_tipofinalidad'])
                                        AND !empty($_REQUEST['ac_causaexterna']) AND !empty($_REQUEST['ac_diagnostico'])
                                        AND !empty($_REQUEST['ac_tipodiagnostico']) AND !empty($_REQUEST['autorizacion']) )
                                {
                                    $sw_valida = '1';
                                    if($sw_dato_complementario[sw_ah] == '1')
                                    {
                                        if( !empty($_REQUEST['ah_ViaIngreso']) AND !empty($_REQUEST['ah_fechaingreso'])
                                                AND !empty($_REQUEST['ah_causaexterna']) AND !empty($_REQUEST['ah_diagnosticoingreso'])
                                                AND !empty($_REQUEST['ah_diagnosticosalida']) AND !empty($_REQUEST['ah_estadosalida'])
                                                AND !empty($_REQUEST['ah_fechasalida']) )
                                        $sw_valida = '1'; else $sw_valida = '0';
                                    }
                                    elseif($sw_dato_complementario[sw_au] == '1')
                                    {
                                        if( !empty($_REQUEST['au_fechaingreso']) AND !empty($_REQUEST['au_horarioingreso'])
                                                AND !empty($_REQUEST['au_causaexterna']) AND !empty($_REQUEST['au_DiagnosticoSalida'])
                                                AND !empty($_REQUEST['au_destinosalida']) AND !empty($_REQUEST['au_estadosalida'])
                                                AND !empty($_REQUEST['au_fechasalida']) AND !empty($_REQUEST['au_horariosalida']) )
                                        $sw_valida = '1'; else $sw_valida = '0';
                                    }
//                              elseif($sw_dato_complementario[sw_an] == '1')
//                              {
//                                  if( !empty($_REQUEST['']) AND !empty($_REQUEST[''])
//                                          AND !empty($_REQUEST['']) AND !empty($_REQUEST[''])
//                                          AND !empty($_REQUEST['']) AND !empty($_REQUEST[''])
//                                          AND !empty($_REQUEST['']) AND !empty($_REQUEST['']) )
//                                  $sw_valida = '1'; else $sw_valida = '0';
//                              }
                                }
                                if($sw_valida == '1')
                                    return true;
                                else
                                    return false;
                                break;
                    }
                    case 'AP':
                    {
                                if(!empty($_REQUEST['ap_fechaprocedimiento']) AND !empty($_REQUEST['ap_ambitoprocedimiento'])
                                        AND !empty($_REQUEST['ap_finalidadprocedimiento']) AND !empty($_REQUEST['autorizacion']) )
                                {
                                    return true;
                                }
                                else
                                {
                                    return false;
                                }
                                break;
                    }
                    case 'AT':
                    {
                                if(!empty($_REQUEST['at_tiposervicio']) AND !empty($_REQUEST['autorizacion']) )
                                {
                                    return true;
                                }
                                else
                                {
                                    return false;
                                }
                                break;
                    }
                    case 'AM':
                    {
                                if(!empty($_REQUEST['am_tipomedicamento']) AND !empty($_REQUEST['autorizacion']) )
                                {
                                    return true;
                                }
                                else
                                {
                                    return false;
                                }
                                break;
                    }
                    case 'AU':
                    {
                                $_REQUEST['au_diagnosticosalida'] = $_REQUEST['codigo'];
                                if(!empty($_REQUEST['autorizacion']) AND !empty($_REQUEST['au_fechaingreso'])
                                        AND !empty($_REQUEST['au_horarioingreso']) AND !empty($_REQUEST['au_minuteroingreso'])
                                        AND !empty($_REQUEST['au_causaexterna']) AND !empty($_REQUEST['au_diagnosticosalida'])
                                        AND !empty($_REQUEST['au_destinosalida']) AND !empty($_REQUEST['au_estadosalida'])
                                        AND !empty($_REQUEST['au_fechasalida']) AND !empty($_REQUEST['au_horariosalida'])
                                        AND !empty($_REQUEST['au_minuterosalida']) )
                                {
                                    return true;
                                }
                                else
                                {
                                    return false;
                                }
                                break;
                    }
                    case 'AH':
                    {
                                if( !empty($_REQUEST['ah_ViaIngreso']) AND !empty($_REQUEST['ah_fechaingreso'])
                                                AND !empty($_REQUEST['ah_causaexterna']) AND !empty($_REQUEST['ah_diagnosticoingreso'])
                                                AND !empty($_REQUEST['ah_diagnosticosalida']) AND !empty($_REQUEST['ah_estadosalida'])
                                                AND !empty($_REQUEST['ah_fechasalida']) AND !empty($_REQUEST['ah_horarioingreso'])
                                                AND !empty($_REQUEST['ah_minuteroingreso']) AND !empty($_REQUEST['ah_horariosalida'])
                                                AND !empty($_REQUEST['ah_minuterosalida']))
                                {
                                    return true;
                                }
                                else
                                {
                                    return false;
                                }
                                break;
                    }
                    default:
                    {
                                return false;
                                break;
                    }
                }//fin switch
                return true;
            }


            /**
            *
            */
                        function TraerReportesHojaCargos()
                        {
                                list($dbconn) = GetDBconn();
                                $query = "SELECT ruta_reporte, titulo
                                                    FROM    reportes_facturas_clientes_planes
                                                    WHERE empresa_id='".$_SESSION['CUENTAS']['EMPRESA']."'
                                                    AND sw_hoja_cargos = '1';";
                                $result = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0)
                                {
                                        $this->error = "Error al Seleccionar fechas envios";
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

                        /**
                        *
                        *
                        */
                        function LlamarVentanaFinal($boton=false)
                        {
                                $cont='app';
                                $mod='Facturacion';
                                $tipo='user';
                                $metodo='Cuenta';

                                $array=array('Cuenta'=>$_REQUEST['numerodecuenta'],'TipoId'=>$_REQUEST['tipoid'],'PacienteId'=>$_REQUEST['pacienteid'],'PlanId'=>$_REQUEST['plan_id'],'Nivel'=>$_REQUEST['Nivel'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso'],'Transaccion'=>$_REQUEST['Transaccion'],'Estado'=>$_REQUEST['Estado'],'tipo_factura'=>$_REQUEST['tipo_factura'],'Dev'=>$_REQUEST['Dev'],'vars'=>$_REQUEST['vars'],'verhojas'=>'1');
                                //$metodo='FormaMetodoBuscar';
                                //$metodo='FormaBuscarFacturas';
                                $accion=ModuloGetURL($cont,$mod,$tipo,$metodo,$array);
                                if(!empty($_REQUEST['numerodecuenta']) AND $_REQUEST['tiporeporte']=='reportes' AND !empty($_REQUEST['reporteshojacargos']))
                                {
                                        $dat=explode(',',$_REQUEST['reporteshojacargos']);
                                        $boton=$_REQUEST['tiporeporte'];
                                        $msg=$dat[1].' GENERADA SATISFACTORIAMENTE';
                                        $arreglo=array('cuenta'=>$_REQUEST['numerodecuenta'],'plan_id'=>$_REQUEST['plan_id'],'tipoid'=>$_REQUEST['tipoid'],'pacienteid'=>$_REQUEST['pacienteid'],'switche_emp'=>$a,'ruta_hoja'=>$dat[0]);
                                }
                                else
                                {$msg='REPORTE NO SE GENERÓ.';}

                                $this->FormaMensajeImprimirHojasCargos($msg,'CONFIRMACION',$accion,'Volver',$boton,$arreglo);
                                return true;
                        }
            /**
            *
            */
            function PideDatosAdicionalesRipsAM()
            {

            }
            //fin MauroB
  //----------------------------------------------------------------------
		function ListadoHospitalizados()
		{
			$NORMAL=1;//CAMA NORMAL
			$VIRTUAL=2;//CAMA VIRTUAL
			
			list($dbconn) = GetDBconn();
			
			$query = "SELECT 
						i.departamento,
						i.descripcion as desc_departamento,
						h.estacion_id,
						h.descripcion as desc_estacion,
						b.pieza,
						a.cama,
						c.numerodecuenta,
						c.gravamen_valor_cubierto,
						c.valor_cubierto,
						d.ingreso,
						d.fecha_ingreso,
						d.tipo_id_paciente,
						d.paciente_id,
						e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo,
						f.plan_id,
						f.plan_descripcion,
						f.tipo_tercero_id,
						f.tercero_id,
						g.nombre_tercero,
						j.tipo_afiliado_nombre,
						k.rango
					FROM
						movimientos_habitacion a,
						camas b,
						cuentas c
						LEFT JOIN tipos_afiliado j
						ON
						(
							c.tipo_afiliado_id=j.tipo_afiliado_id
						)
						JOIN planes_rangos k
						ON
						(
							k.tipo_afiliado_id=c.tipo_afiliado_id
							AND k.plan_id=c.plan_id
							AND k.rango=c.rango
						),
						ingresos d,
						pacientes e,
						planes f,
						terceros g,
						estaciones_enfermeria h,
						departamentos i
					WHERE
						a.fecha_egreso IS NULL
						AND b.cama = a.cama
						AND b.sw_virtual IN('$NORMAL','$VIRTUAL')
						AND c.numerodecuenta = a.numerodecuenta
						AND d.ingreso = a.ingreso
						AND e.paciente_id = d.paciente_id
						AND e.tipo_id_paciente = d.tipo_id_paciente
						AND f.plan_id = c.plan_id
						AND g.tercero_id = f.tercero_id
						AND g.tipo_id_tercero = f.tipo_tercero_id
						AND h.estacion_id = a.estacion_id
						AND h.departamento =  i.departamento	
						AND i.empresa_id='".$_SESSION['CUENTAS']['EMPRESA']."'
					ORDER BY e.primer_nombre , e.segundo_nombre , e.primer_apellido";

			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo Facturacion - ListadoHospitalizados";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if($result->RecordCount()>0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[1]][$result->fields[3]][]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			return $vars;
		}
		
		function ListadoObservacionUrgencias()
    {
			list($dbconn) = GetDBconn();
			
			$query = "  SELECT a.*
									FROM
											(
													SELECT 
															i.departamento,
															i.descripcion as nom_departamento,
															h.estacion_id, 
															h.descripcion as nom_estacion,
															(SELECT verificacionpaciente_ecirugia(a.numerodecuenta)) as paciente_cirugia,
															a.movimiento_id,
															a.numerodecuenta,
															c.gravamen_valor_cubierto,
															c.valor_cubierto,
															a.fecha_ingreso AS fecha_hospitalizacion,
															b.pieza,
															a.cama,
															d.ingreso,
															d.fecha_ingreso,
															TO_CHAR(d.fecha_ingreso,'YYYY-MM-DD HH:MI') AS fecha_ingreso1,
															d.paciente_id,
															d.tipo_id_paciente,
															e.primer_nombre,
															e.segundo_nombre,
															e.primer_apellido,
															e.segundo_apellido,
															e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo,
															f.plan_id,
															f.plan_descripcion,
															f.tercero_id,
															f.tipo_tercero_id,
															g.nombre_tercero,
															j.tipo_afiliado_nombre,
															k.rango
													FROM
															movimientos_habitacion a,
															camas b,
															cuentas c
															LEFT JOIN tipos_afiliado j
															ON
															(
																c.tipo_afiliado_id=j.tipo_afiliado_id
															)
															JOIN planes_rangos k
															ON
															(
																k.tipo_afiliado_id=c.tipo_afiliado_id
																AND k.plan_id=c.plan_id
																AND k.rango=c.rango
															),
															ingresos d,
															pacientes e,
															planes f,
															terceros g,
															estaciones_enfermeria h,
															departamentos i
													WHERE
															a.fecha_egreso IS NULL
															AND a.estacion_id = h.estacion_id
															AND h.sw_observacion_urgencia='1'
															AND b.cama = a.cama
															AND c.numerodecuenta = a.numerodecuenta
															AND d.ingreso = a.ingreso
															AND e.paciente_id = d.paciente_id
															AND e.tipo_id_paciente = d.tipo_id_paciente
															AND f.plan_id = c.plan_id
															AND f.estado='1'
															AND g.tercero_id = f.tercero_id
															AND g.tipo_id_tercero = f.tipo_tercero_id
															AND h.departamento=i.departamento
											) AS a 
									ORDER BY a.ingreso,a.cama, a.pieza;";              
									
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo Facturacion - ListadoObservacionUrgencias";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if($result->RecordCount()>0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[1]][$result->fields[3]][]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			return $vars;
		}
		
		function ReportesCuentas($opcion)
    {
			switch($opcion)
			{
				case 1:
					$sqlestado="WHERE A.estado='1'";
				break;
				
				case 2:
					$sqlestado="WHERE A.estado='2'";
				break;
				case 3:
					$sqlestado="WHERE A.estado IN('1','2')";
				break;
			}
			
			list($dbconn) = GetDBconn();
			
			$query = "  SELECT A.*
											FROM
											(
													SELECT 
															i.departamento,
															i.descripcion as nom_departamento,
															h.estacion_id, 
															h.descripcion as nom_estacion,
															a.numerodecuenta,
															c.gravamen_valor_cubierto,
															c.valor_cubierto,
															c.estado,
															CASE c.estado 
															WHEN '1' THEN 'ACTIVA'
															WHEN '2' THEN 'INACTIVA'
															END as estado_cuenta,
															a.ingreso,
															b.pieza,
															a.cama,
															d.fecha_ingreso,
															d.paciente_id,
															d.tipo_id_paciente,
															e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo,
															f.plan_id,
															f.plan_descripcion,
															f.tercero_id,
															f.tipo_tercero_id,
															g.nombre_tercero,
															j.tipo_afiliado_nombre,
															k.rango
													FROM
															movimientos_habitacion a,
															camas b,
															cuentas c
															LEFT JOIN tipos_afiliado j
															ON
															(
																c.tipo_afiliado_id=j.tipo_afiliado_id
															)
															JOIN planes_rangos k
															ON
															(
																k.tipo_afiliado_id=c.tipo_afiliado_id
																AND k.plan_id=c.plan_id
																AND k.rango=c.rango
															),
															ingresos d,
															pacientes e,
															planes f,
															terceros g,
															estaciones_enfermeria h,
															departamentos i
													WHERE
															a.estacion_id = h.estacion_id
															AND b.cama = a.cama
															AND c.numerodecuenta = a.numerodecuenta
															AND d.ingreso = a.ingreso
															AND e.paciente_id = d.paciente_id
															AND e.tipo_id_paciente = d.tipo_id_paciente
															AND f.plan_id = c.plan_id
															AND f.estado='1'
															AND g.tercero_id = f.tercero_id
															AND g.tipo_id_tercero = f.tipo_tercero_id
															AND h.departamento=i.departamento
											) AS A
									$sqlestado 
									ORDER BY A.numerodecuenta;";              
								
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo Facturacion - ReportesCuentas";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if($result->RecordCount()>0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[1]][$result->fields[3]][]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			return $vars;
		}
		
		function GetDiasHospitalizacion($fecha_ingreso)
		{
			$date1=date('Y-m-d H:i:s');
			$fecha_in=explode(".",$fecha_ingreso);
			$fecha_ingreso=$fecha_in[0];
			$date2=$fecha_ingreso;
			$s = strtotime($date1)-strtotime($date2);
			$d = intval($s/86400);
			$s -= $d*86400;
			$h = intval($s/3600);
			$s -= $h*3600;
			$m = intval($s/60);
			$s -= $m*60;
			$dif= (($d*24)+$h).hrs." ".$m."min";
			$dif2= $d;
			return $dif2;
		}//Fin GetDiasHospitalizacion

    //----------------------------------------------------------------------

			/**
			**/
			function llamaFormaAgregarCargos()
			{
				IncludeClass('AgregarCargosHTML','','app','Facturacion');
				IncludeFile ('app_modules/Facturacion/RemoteXajax/datosbusquedaCargos.php');
				$this->SetXajax(array("reqObtenerDatos","reqAdicionarDatos","reqEliminarDatos"));
				$Cuenta = $_REQUEST['Cuenta'];
				$Nombres = $_REQUEST['Nombres'];
				$Apellidos = $_REQUEST['Apellidos'];
				$TipoId = $_REQUEST['TipoId'];
				$PacienteId = $_REQUEST['PacienteId'];
				$Nivel = $_REQUEST['Nivel'];
				$Ingreso = $_REQUEST['Ingreso'];
				$Fecha = $_REQUEST['Fecha'];
				$EmpresaId=$_SESSION[CUENTAS][EMPRESA];
				$CU=$_SESSION[CUENTAS][CENTROUTILIDAD];
				$Departamento = $_REQUEST['Departamento'];
				$Descripcion_dpto = $_REQUEST['Descripcion_dpto'];
				$PlanId=$_REQUEST['PlanId'];
				$accion=ModuloGetURL('app','Facturacion','user','LlamarFormaTiposCargos',array('Cuenta'=>$_REQUEST['Cuenta'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Pieza'=>$_REQUEST['Pieza'],'Cama'=>$_REQUEST['Cama'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso']));
				SessionSetvar('AccionVolverCargos',$accion);
				$Buscar = new AgregarCargosHTML();
				$this->salida  = $Buscar->FormaAgregarCargos(&$this,$EmpresaId,$CU,$PlanId,$Cuenta,'',$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha);
				return true;
			}

			/**
			**/
			function LlamaFormaBusquedaCargoIyM()
			{
				IncludeClass('BuscarCargoIYMHTML','','app','Facturacion');
				IncludeFile ('app_modules/Facturacion/RemoteXajax/datosbusquedaCargosIyM.php');
				$this->SetXajax(array("reqObtenerDatos","reqAdicionarDatos","reqEliminarDatos"));
				
				$UsuarioId = UserGetUID();
				$Cuenta = $_REQUEST['Cuenta'];
				$Nombres = $_REQUEST['Nombres'];
				$Apellidos = $_REQUEST['Apellidos'];
				$TipoId = $_REQUEST['TipoId'];
				$PacienteId = $_REQUEST['PacienteId'];
				$EmpresaId=$_SESSION[CUENTAS][EMPRESA];
				$CU=$_SESSION[CUENTAS][CENTROUTILIDAD];
				$Departamento = $_REQUEST['Departamento'];
				$Descripcion_dpto = $_REQUEST['Descripcion_dpto'];
				$PlanId=$_REQUEST['PlanId'];
				$Ingreso=$_REQUEST['Ingreso'];
				//action volver
				$accion=ModuloGetURL('app','Facturacion','user','LlamarFormaTiposCargos',array('Cuenta'=>$_REQUEST['Cuenta'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Pieza'=>$_REQUEST['Pieza'],'Cama'=>$_REQUEST['Cama'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso']));
				SessionSetVar('AccionVolverCargosIYM',$accion);

				$Buscar = new BuscarCargoIYMHTML();
				$this->salida  = $Buscar->FormaBusquedaCargoIyM(&$this,$EmpresaId,$CU,$UsuarioId,$Cuenta,$PlanId,$Ingreso,$TipoId,$PacienteId,$Nombres,$Apellidos);
				return true;
			}

			/**
			**/
			function LlamaInsertarCargosTmp()
			{
				IncludeClass('AgregarCargos','','app','Facturacion');
				IncludeFile ('app_modules/Facturacion/RemoteXajax/datosbusquedaCargos.php');			
				$this->SetXajax(array("reqObtenerDatos","reqAdicionarDatos","reqEliminarDatos"));							
				$fact = new AgregarCargos();
				$this->salida .= $fact->InsertarCargosTmp($_REQUEST[obj],$_REQUEST[EmpresaId],$_REQUEST[CU],$_REQUEST[PlanId],$_REQUEST[Cuenta]);
				return true;
			}
	
			/**
			**/
			function LlamaPideDatosAdicionalesRips()
			{
				IncludeClass('AgregarCargosHTML','','app','Facturacion');
				IncludeFile ('app_modules/Facturacion/RemoteXajax/datosbusquedaCargos.php');			
				$this->SetXajax(array("reqObtenerDatos","reqAdicionarDatos","reqEliminarDatos"));							
				$dat = new AgregarCargosHTML();
				$fact=$dat->PideDatosAdicionalesRips();
				foreach($_SESSION['CUENTAS']['ADD_CARGOS'] AS $i => $v)
				{
					foreach($fact AS $i1 => $v1)
					{
						if($v1[departamento]==$v[departamento]
								AND $v1[cups]==$v[codigo])
						{
							UNSET($_SESSION['CUENTAS']['ADD_CARGOS'][$i]);
						}
					}
				}
				$fact = new AgregarCargos();
				if(sizeof($_SESSION['CUENTAS']['ADD_CARGOS'])>0)
				{
					$_REQUEST['datos']='pidedatos';
					$this->salida .= $fact->InsertarCargosTmp();
					return true;
				}
				else
				{
					//$_REQUEST['datos']='adiciona';
					$fact = new AgregarCargos();
					$this->salida .= $fact->GuardarTodosCargos($_REQUEST['EmpresaId'],$_REQUEST[CU],$_REQUEST[PlanId],$_REQUEST['Cuenta']);
					return true;
				}
			}
	
			/**
			**/
			function LlamaInsertarCargoTmpEquivalencias()
			{
				IncludeClass('AgregarCargos','','app','Facturacion');
				IncludeFile ('app_modules/Facturacion/RemoteXajax/datosbusquedaCargos.php');			
				$this->SetXajax(array("reqObtenerDatos","reqAdicionarDatos","reqEliminarDatos"));							
				$dat = new AgregarCargos();
				$this->salida .= $dat->InsertarCargoTmpEquivalencias();
				return true;
			}

		/**
		**/
		function GetPlanes($estado=null)
		{
			list($dbconn) = GetDBconn();
			
			if(is_null($estado))
				$d_estado="WHERE estado='1'";
			else
			{
				switch($estado)
				{
					case 1:
						$d_estado="WHERE estado='1'";
					break;
					case 2:
						$d_estado="WHERE estado!='1'";
					break;
					case 3:
						$d_estado="";
					break;
				}
			}
			
			$query = "  SELECT DISTINCT plan_id,plan_descripcion,estado
									FROM planes
									$d_estado
									ORDER BY plan_descripcion";              
								
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo Facturacion - GetPlanes";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if($result->RecordCount()>0)
				{
					while(!$result->EOF)
					{
						$vars[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			return $vars;
		}
		
		function ListadoPacientesAtendidos($fecha_ini,$fecha_fin,$tplan,$tipo)
		{
			if(!empty($fecha_ini) AND !empty($fecha_fin))
			{
				$fechas="	AND date(c.fecha_cargo)>='$fecha_ini' 
									AND date(c.fecha_cargo)<='$fecha_fin'";
			}
		
			if(!empty($tplan))
			{
				$plan="AND e.plan_id=$tplan";
			}
			
			list($dbconn) = GetDBconn();

			switch($tipo)
			{
				case 1:
					 $query="	SELECT 
											a.numerodecuenta, 
										 	p.primer_nombre || ' ' || p.segundo_nombre || ' ' || p.primer_apellido || ' ' || p.segundo_apellido as nombre_completo,
											p.tipo_id_paciente, 
											p.paciente_id,
											a.valor_cuota_paciente,
											sum(c.valor_cubierto) as valor_cubierto
										FROM 
											cuentas a,  
											ingresos i, 
											pacientes p, 
											cuentas_detalle c, 
											departamentos d, 
											planes e
										WHERE 
											a.numerodecuenta = c.numerodecuenta
											and c.facturado = '1'
											and a.ingreso = i.ingreso
											and (i.tipo_id_paciente = p.tipo_id_paciente
											and i.paciente_id = p.paciente_id)
											and c.departamento_al_cargar = d.departamento
											and d.servicio in ('1','2','6')
											and a.plan_id = e.plan_id
											$fechas
											$plan
										group by 1,2,3,4,5
										order by 1,2
					 				";
					break;
					case 2:
							
								$query=" 
											SELECT 
												a.numerodecuenta, 
												p.primer_nombre || ' ' || p.segundo_nombre || ' ' || p.primer_apellido || ' ' || p.segundo_apellido as nombre_completo,
												p.tipo_id_paciente, 
												p.paciente_id,
												a.valor_cuota_paciente,
												sum(c.valor_cubierto) as valor_cubierto
											FROM 
												cuentas a,  
												ingresos i, 
												pacientes p, 
												cuentas_detalle c, 
												planes e
											WHERE c.departamento_al_cargar= '021501'
												and c.facturado = '1'
												and c.numerodecuenta = a.numerodecuenta
												and a.plan_id = e.plan_id
												and a.ingreso = i.ingreso
												and (i.tipo_id_paciente = p.tipo_id_paciente
												and i.paciente_id = p.paciente_id)
												$fechas
												$plan
										group by 1,2,3,4,5
										order by 1,2;
									";       
					break;
					case 3:
							$query="
									SELECT 
										a.numerodecuenta, 
										p.primer_nombre || ' ' || p.segundo_nombre || ' ' || p.primer_apellido || ' ' || p.segundo_apellido as nombre_completo,
										p.tipo_id_paciente, 
										p.paciente_id,
										a.valor_cuota_paciente,
										sum(c.valor_cubierto) as valor_cubierto
									FROM 
										cuentas a,  
										ingresos i, 
										pacientes p, 
										cuentas_detalle c, 
										planes e,
										os_maestro g,
										os_ordenes_servicios h
									WHERE 
										c.numerodecuenta = a.numerodecuenta
										and c.facturado = '1'
										and a.plan_id = e.plan_id
										and a.numerodecuenta  = g.numerodecuenta
										and a.ingreso = i.ingreso
										and (i.tipo_id_paciente = p.tipo_id_paciente
										and i.paciente_id = p.paciente_id)
										and h.orden_servicio_id = g.orden_servicio_id
										and h.servicio = '3'
										$fechas
										$plan
										group by 1,2,3,4,5
										order by 1,2;
							";
					break;
			} 

			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo Facturacion - ListadoPacientesAtendidos - $tipo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if($result->RecordCount()>0)
				{
					while(!$result->EOF)
					{
						$vars[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			return $vars;
		}
    
    //**********************************PAQUETES****************************
    
    function RealizarPaquetesCargos(){
      $this->FrmRealizarPaquetesCargos($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['Estado']);
      return true;    
    } 
    
    
    
    //**********************************FIN********************************
    //**********************************detalle cuenta*********************
    
    
    /*************************************************************************
    * Llama la forma para modificar un cargo de la cuenta en cuenta_detalles.
    * @ access public
    * @ return boolean
    */
    function LlamaFormaModificar(){
	    
	if(!$_REQUEST['Transaccion']){
        if($_REQUEST['Datos']['transaccion']){
          $_REQUEST['Transaccion']=$_REQUEST['Datos']['transaccion'];
        }
      }
      $Transaccion=$_REQUEST['Transaccion'];
      $TipoId=$_REQUEST['TipoId'];
      $Cuenta=$_REQUEST['Cuenta'];
      $PacienteId=$_REQUEST['PacienteId'];
      $Nivel=$_REQUEST['Nivel'];
      $PlanId=$_REQUEST['PlanId'];
      $Fecha=$_REQUEST['Fecha'];
      $Ingreso=$_REQUEST['Ingreso'];
      $Datos=$_REQUEST['Datos'];
      $Apoyo=$_REQUEST['Apoyo'];  
      if(!$this->FormaModificarCargo($Transaccion,$TipoId,$PacienteId,$Cuenta,$Nivel,$PlanId,$Fecha,$Ingreso,$Datos,$mensaje,$Apoyo)){
        return false;
      }
      return true;
    }
    
    /************************************************************************
    * Llama la formacuenta y le envia los parametros necesarios para mostrar una cuenta.
    * @access public
    * @return boolean
    */
    
    function Cuenta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$Estado){
      if($_REQUEST['listado']==1){
        $_SESSION['LISTADO_PACIENTES_SALIDA']=1;
      }            
      if($_REQUEST['ocultar'])//SI LLEGA DE OCULTAR CARGOS NO FACTURADOS
      {
        UNSET($_SESSION['CUENTAS']['ARREGLO_NOFACTURADOS']);
      }
      unset($_SESSION['CUENTA']['DIVISION']);
      unset($_SESSION['AUTORIZACIONES']['RETORNO']);
      if(!$Cuenta){
        $Transaccion=$_REQUEST['Transaccion'];
        $TipoId=$_REQUEST['TipoId'];
        $PacienteId=$_REQUEST['PacienteId'];
        $Nivel=$_REQUEST['Nivel'];
        $PlanId=$_REQUEST['PlanId'];
        $Pieza=$_REQUEST['Pieza'];
        $Cama=$_REQUEST['Cama'];
        $Fecha=$_REQUEST['Fecha'];
        $Ingreso=$_REQUEST['Ingreso'];
        $Cuenta=$_REQUEST['Cuenta'];
        if(empty($Cuenta)){
          $Cuenta=$_REQUEST['numero_cuenta'];
        }
        //  0 FACTURADA
        //  1 ACTIVA
        //  2 INACTIVA
        //  3 CUADRADA
        //  4 ANTICIPOS
        //  5 ANULADA
        $Estado=$_REQUEST['Estado'];
        //if(!$_SESSION['ESTADO']){ $_SESSION['ESTADO']=$_REQUEST['Estado']; }
        if(empty($_SESSION['ESTADO'])){  
          if($_REQUEST['Estado']=='1' OR $_REQUEST['Estado']=='A'){
            $_SESSION['ESTADO'] ='A';
          }elseif($_REQUEST['Estado']=='2' OR $_REQUEST['Estado']=='I'){
            $_SESSION['ESTADO'] ='I';
          }
        }
      }
      unset($_SESSION['PLAN1']);
      unset($_SESSION['NIVEL1']);               
      unset($_SESSION['FECHAS_VENCIMIENTO']);        
      if(!$this->FormaCuenta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$vars,$Transaccion,$mensaje,$Dev,$Estado)){
          return false;
      }
      return true;
    }
    
    /**************************************************************************
    * Modifica un cargo de la cuenta en cuenta_detalles.
    * @ access public
    * @ return boolean
    */
    function ValidarModificarCargo(){            
        
        $_REQUEST['ValorPac']=str_replace(".","",$_REQUEST['ValorPac']);
        $_REQUEST['ValorEmp']=str_replace(".","",$_REQUEST['ValorEmp']);              
        
        IncludeClass('ModificacionCargo','','app','Facturacion'); 
        $objeto = new ModificacionCargo;                             
        if($objeto->ModificarCargo($_REQUEST['Cuenta'],$_REQUEST['PlanId'],$_SESSION['CUENTAS']['EMPRESA'],$_REQUEST['Departamento'],$_REQUEST['Transaccion'],$_REQUEST['TarifarioId'],$_REQUEST['Cargo'],$_REQUEST['Consecutivo'],$_REQUEST['Cantidad'],$_REQUEST['observacion'],$_REQUEST['ValorPac'],$_REQUEST['ValorEmp'],$_REQUEST['FechaCargo'],$_REQUEST['Manual'],$_REQUEST['DescuentoEmp'],$_REQUEST['DescuentoPac'])==true){
          $mensaje='Cargo Modificado Satisfactoriamente';                                         
          $this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$vars,$_REQUEST['Transaccion'],$mensaje,$Dev,$Estado);              
          return true;
        }else{
          $mensaje=$objeto->ErrMsg();
          $_REQUEST['ValorPac']=str_replace(".","",$_REQUEST['ValorPac']);
          $_REQUEST['ValorEmp']=str_replace(".","",$_REQUEST['ValorEmp']);
          if(!$this->FormaModificarCargo($_REQUEST['Transaccion'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Cuenta'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['FechaCargo'],$_REQUEST['Ingreso'],$_REQUEST['Datos'],$mensaje)){
            return false;
          }
          return true;
        }  
        $this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Cama'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$vars,$_REQUEST['Transaccion'],$mensaje,$Dev,$Estado);              
        return true;        
                  
    }
    
    function LlamarFormaEliminarCargo(){
      if($_REQUEST['Datos']['transaccion'])
      {
        $_REQUEST['Transaccion']=$_REQUEST['Datos']['transaccion'];
      }
      $this->FormaEliminarCargo($_REQUEST['Transaccion'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['Datos'],$_REQUEST['des'],$_REQUEST['codigo'],$_REQUEST['doc'],$_REQUEST['numeracion'],$_REQUEST['noFacturado']);
      return true;
    }
    
    /*************************************************************************
    * Elimina un cargo de la cuenta en cuenta_detalles.
    * @ access public
    * @ return boolean
    */
    function ValidarEliminarCargo(){

      if(empty($_REQUEST['observacion'])){
          $this->frmError["observacion"]=1;
          $mensaje="DEBE ESCRIBIR LA JUSTIFICACION.";
          $this->FormaEliminarCargo($_REQUEST['Transaccion'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],'','','','','','',$mensaje);
          return true;
      }
      list($dbconn) = GetDBconn();
      /* if(!$Consecutivo)
      {*/
      //Esta es la validacion que se realizo para el arranque de Cali
      //1. Verifica si el cargo tiene asociado una orden de servicio
      $cambioTransaccion=0;
      $query =" SELECT a.os_maestro_cargos_id,b.sw_estado,b.numero_orden_id
                          FROM os_maestro_cargos a,os_maestro b
                          WHERE a.transaccion=".$_REQUEST['Transaccion']." AND
                          a.numero_orden_id=b.numero_orden_id";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      }else{
        if($result->RecordCount()>0){
          while(!$result->EOF){
            $datos[]=$result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
          }
          //1. Verifica si el cargo tiene asociado un examen firmado
          if($datos[0]['sw_estado']=='4'){
            $cambioTransaccion=1;
            //1. Verifica que desea hacer con la solicitud
          }else{
            //verifica si tiene varios cargo equivalentes
            $query =" SELECT count(*)as total_registros
                        FROM os_maestro_cargos a
                        WHERE a.numero_orden_id=".$datos[0]['numero_orden_id']."";

            $result = $dbconn->Execute($query);
            if($result->EOF){
                $this->error = "Error al ejecutar la consulta.<br>";
                $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
                return false;
            }else{
                $contador=$result->GetRowAssoc($ToUpper = false);
            }
            //Pregunta si tiene mas de una cargo
            $registrosCargos=0;
            if($contador['total_registros']>1){
                $registrosCargos=1;                
            }else{
                $this->FormaVerificacionAnulacionOS($_REQUEST['Transaccion'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['observacion'],
                                                    $Pieza,$_REQUEST['doc'],$_REQUEST['numeracion'],$_REQUEST['qx'],$_REQUEST['codigo'],$_REQUEST['des'],$_REQUEST['noFacturado'],$_REQUEST['Consecutivo']);
                return true;
            }
          }
        }
      }  
      IncludeClass('EliminaCargo','','app','Facturacion');
      $objeto = new EliminaCargo;                                                      
      if(($objeto->EliminarCargo($_REQUEST['Cuenta'],$_REQUEST['Transaccion'],$_REQUEST['observacion'],$cambioTransaccion,$datos,$registrosCargos))==true){                
        $mensaje='El cargo se elimino.';
        if(!$this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$Cama,$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$vars,$_REQUEST['Transaccion'],$mensaje,$Dev,$Estado)){      
          return false;
        }
        return true;
      }else{
        $mensaje='Error al eliminar el cargo.';
        $this->FormaEliminarCargo($_REQUEST['Transaccion'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['Datos'],$_REQUEST['des'],$_REQUEST['codigo'],$_REQUEST['doc'],$_REQUEST['numeracion'],$_REQUEST['noFacturado'],$mensaje);
        return true;
      }
    }
    
    function LlamaFormaDevolverIYMCta(){
      $this->FormaDevolverIYMCta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso']);
      return true;
    }
    
    function RealizarVevolucionMedicamentos(){  
      
      foreach($_REQUEST as $name=>$val){
        $nameVal=substr($name,0,8);        
        if($nameVal=='cantidad'){
          if($val>0){
            $vector[$name]=$val;
          }
        }
      }     
      
      IncludeClass('DevolucionCargosIyMCta','','app','Facturacion');
      $funciones = new DevolucionCargosIyMCta();
      $val=$funciones->ValidarInsercionDevolucion($vector);  
      if($val==1){
        $mensaje=$funciones->ErrMsg();        
        $this->FormaDevolverIYMCta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$mensaje);      
        return true;
      }else{
        $_SESSION['FACTURACION_CUENTAS']['CUENTA']=$_REQUEST['Cuenta'];
        $_SESSION['FACTURACION_CUENTAS']['PLAN']=$_REQUEST['PlanId']; 
        $_SESSION['FACTURACION_CUENTAS']['motivosDevolucion']=$_REQUEST['MotivoDevolucion']; 
        
        foreach($vector as $valor=>$cantidad){
          (list($none,$empresa,$centro_utilidad,$bodega,$codigo_producto,$nom_bodega,$cantidadLimite,$departamento_al_cargar)=explode('||//',$valor));
          $v['nombre_bodega']=$nom_bodega;
          $v['cantidad_limite']=$cantidadLimite;
          $v['cantidad_devolver']=$cantidad;
          $v['departamento_al_cargar']=$departamento_al_cargar;
          $dat[$empresa][$centro_utilidad][$bodega][$codigo_producto]=$v;
        }
        
        foreach($dat as $Empresa=>$v){
          $_SESSION['FACTURACION_CUENTAS']['Empresa']=$Empresa;
          foreach($v as $centroU=>$v1){
            $_SESSION['FACTURACION_CUENTAS']['Centro_Utilidad']=$centroU;
            foreach($v1 as $Bodega=>$v2){
              $_SESSION['FACTURACION_CUENTAS']['Bodega']=$Bodega;
              foreach($v2 as $codigo=>$v3){
                $_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CANTIDADES_DEV'][$codigo.'//||'.$v3['departamento_al_cargar']]=$v3['cantidad_devolver'];      
                //$_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CANTIDADES_DEV'][departamento_al_cargar]=$v3['departamento_al_cargar'];
              }
              $retorno=$this->CallMetodoExterno('app','InvBodegas','user','DevolucionIyMCargosCuenta');
              if($retorno==false){                  
                $mensaje=$_SESSION['FACTURACION_CUENTAS']['RETORNO']['Mensaje_Error'];
                $this->FormaDevolverIYMCta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$mensaje);      
                return true;
              }             
            }
          }         
        }        
        $mensaje="Devoluciones Realizadas Satisfactoriamente";
        if(!$this->FormaCuenta($_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$Cama,$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$vars,$_REQUEST['Transaccion'],$mensaje,$Dev,$Estado)){      
          return false;
        }
        return true;
      }      
    }
        
    
    
    
    
    //**********************************FIN********************************
    
    
    
    


		function GetCamas($estacion,$estado)
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT count(DISTINCT b.cama)
							FROM piezas as a
							JOIN camas as b
							ON
							(
								a.pieza=b.pieza
							)
							WHERE a.estacion_id='$estacion'
							AND b.estado='$estado'";
			
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo Facturacion - GetCamas";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$num_camas=$result->fields[0];
			}
			
			return $num_camas;
		}
    //**********************************FIN******************************** 
	}//fin clase user
?>
