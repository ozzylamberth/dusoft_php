<?php

/**
* $Id: ContabilizarFacturas.class.php,v 1.7 2005/08/18 20:05:49 alex Exp $
*/

/**
* Clase para la contabilizacion y consulta de la misma de documentos de tipo (Facturas - fac_facturas)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.7 $
* @package SIIS
*/
class ContabilizarFacturas
{

    /**
    * Datos de la factura a contabilizar
    *
    * @var boolean
    * @access private
    */
    var $datosFactura=array();


    /**
    * Datos de la factura a contabilizar
    *
    * @var boolean
    * @access private
    */
    var $FacturaCGpuc=array();


    /**
    * Codigo de error
    *
    * @var string
    * @access private
    */
    var $error;


    /**
    * Mensaje de error
    *
    * @var string
    * @access private
    */
    var $mensajeDeError;


    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function ContabilizarFacturas()
    {
        return true;
    }


    /**
    * Metodo para recuperar el titulo del error
    *
    * @return string
    * @access public
    */
    function Err()
    {
        return $this->error;
    }


    /**
    * Metodo para recuperar el detalle del error
    *
    * @return string
    * @access public
    */
    function ErrMsg()
    {
        return $this->mensajeDeError;
    }


    /**
    * Metodo para contabilizar una factura
    *
    * @param string $empresa_id
    * @param string $prefijo
    * @param integer $factura_fiscal
    * @param boolean $reprocesar
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function ContabilizarFactura($empresa_id,$prefijo,$factura_fiscal,$reprocesar=false)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();


        if($reprocesar)
        {
            $sql = "DELETE FROM cg_temp_contabilizacion_facturas
                    WHERE empresa_id='$empresa_id' AND prefijo='$prefijo' AND numero=$factura_fiscal";

            $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR 1";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
        }

        $sql = "SELECT cg_contabilizacion_estado_id
                FROM cg_temp_contabilizacion_facturas
                WHERE empresa_id='$empresa_id' AND prefijo='$prefijo' AND numero=$factura_fiscal";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR 2";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $insertar_reg = true;
            $cg_estado = false;
        }
        else
        {
            list($cg_estado)=$result->FetchRow();
            $insertar_reg = false;
        }
        $result->Close();

        $sql = "SELECT a.*,b.numerodecuenta
                FROM fac_facturas a, fac_facturas_cuentas b
                WHERE a.empresa_id='$empresa_id'
                AND a.prefijo='$prefijo'
                AND a.factura_fiscal=$factura_fiscal
                AND b.empresa_id=a.empresa_id
                AND b.prefijo=a.prefijo
                AND b.factura_fiscal=a.factura_fiscal";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR 4";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $this->error = "CONTABILIZACION DE FACTURAS";
            $this->mensajeDeError = "No existe en la tabla de facturas el registro para $prefijo$factura_fiscal de la empresa $empresa_id";
            return false;
        }

        $first_reg=true;

        while($Fila=$result->FetchRow())
        {
            if($first_reg){

                if($Fila['documento_contable_id'])
                {
                    $this->error = "CONTABILIZACION DE FACTURAS";
                    $this->mensajeDeError = "Ya esta contabilizada la factura $prefijo$factura_fiscal de la empresa $empresa_id";
                    return false;
                }

                //EXAMINAR EL TIPO DE FACTURA SI ESTA PARAMETRIZADO

                switch($Fila['tipo_factura'])
                {
                    case '0': //FACTURA PACIENTE
                    case '1': //FACTURA CLIENTE
                    case '2': //FACTURA PARTICULAR
                    case '3': //FACTURA AGRUPADA CAPITACION
                    case '4': //FACTURA AGRUPADA
                    break;
                    default:

                    //SI NO ESTA DEFINIDO EL TIPO RETORNO FALSE.
                    if($insertar_reg)
                    {
                        $sql = "INSERT INTO cg_temp_contabilizacion_facturas(empresa_id, prefijo, numero, cg_contabilizacion_estado_id)
                                VALUES('$empresa_id','$prefijo',$factura_fiscal,'06')";
                    }
                    else
                    {
                        $sql = "UPDATE cg_temp_contabilizacion_facturas
                                SET cg_contabilizacion_estado_id='06'
                                WHERE empresa_id='$empresa_id' AND prefijo='$prefijo' AND numero=$factura_fiscal;";
                    }

                    $dbconn->Execute($sql);

                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR 5-A";
                        $this->mensajeDeError = $dbconn->ErrorMsg();
                        return false;
                    }

                    return false;
                }

                $sql="SELECT b.empresa_id, b.cuenta, b.naturaleza, b.sw_agrupar_cuentas,
                        c.empresa_id as empresa_id_docgeneral, c.cuenta as cuenta_docgeneral,
                        c.naturaleza  as naturaleza_docgeneral,
                        c.sw_agrupar_cuentas as sw_agrupar_cuentas_docgeneral
                        FROM documentos a
                            LEFT JOIN cg_parametros_documentos b
                                ON (a.documento_id=b.documento_id AND a.empresa_id=b.empresa_id)
                            LEFT JOIN  cg_parametros_tipos_doc_generales c
                                ON (a.tipo_doc_general_id = c.tipo_doc_general_id)
                        WHERE a.documento_id=".$Fila['documento_id']."
                        AND a.empresa_id='".$Fila['empresa_id']."'";

                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $resultado = $dbconn->Execute($sql);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR 5";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                if($resultado->EOF)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS";
                    $this->mensajeDeError = "No se encontro informacion del tipo de documento ".$Fila['documento_id'].", de la empresa ".$Fila['empresa_id'];
                    return false;
                }
                else
                {
                    $parametrosCG_Doc = $resultado->FetchRow();
                    $resultado->Close();
                    if(empty($parametrosCG_Doc['naturaleza']))
                    {
                        if(empty($parametrosCG_Doc['naturaleza_docgeneral']))
                        {
                            $this->error = "CONTABILIZACION DE FACTURAS";
                            $this->mensajeDeError = "No esta parametrizado el tipo de naturaleza para el tipo de documento ".$Fila['documento_id'].", de la empresa ".$Fila['empresa_id'];
                            return false;
                        }

                        $this->FacturaCGpuc['naturaleza']=$parametrosCG_Doc['naturaleza_docgeneral'];
                        $this->FacturaCGpuc['empresa_id']=$parametrosCG_Doc['empresa_id_docgeneral'];
                        $this->FacturaCGpuc['cuenta']=$parametrosCG_Doc['cuenta_docgeneral'];
                        $this->FacturaCGpuc['agrupamiento']=$parametrosCG_Doc['sw_agrupar_cuentas_docgeneral'];
                    }
                    else
                    {
                        $this->FacturaCGpuc['naturaleza']=$parametrosCG_Doc['naturaleza'];
                        $this->FacturaCGpuc['empresa_id']=$parametrosCG_Doc['empresa_id'];
                        $this->FacturaCGpuc['cuenta']=$parametrosCG_Doc['cuenta'];
                        $this->FacturaCGpuc['agrupamiento']=$parametrosCG_Doc['sw_agrupar_cuentas'];
                    }
                }

                $this->datosFactura=$Fila;
                unset($this->datosFactura['numerodecuenta']);
                $first_reg=false;
            }

            $cuentasFactura[]=$Fila['numerodecuenta'];
        }
        $result->Close();
        unset($first_reg);

        $sw_ok = true;

        if($cg_estado=='OK')
        {
            if(!$this->GenerarDatosContables())
            {
                $sw_ok = false;
            }
            return $sw_ok;
        }

        if($insertar_reg)
        {
            $sql = "INSERT INTO cg_temp_contabilizacion_facturas(empresa_id, prefijo, numero, cg_contabilizacion_estado_id)
                    VALUES('$empresa_id','$prefijo',$factura_fiscal,'00')";

            $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR 3";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

        }

        foreach($cuentasFactura as $k=>$cuenta)
        {
            if(!$this->ContabilizarCuenta($cuenta,$reprocesar))
            {
                $sw_ok = false;
            }
        }

        if($sw_ok)
        {
            if(!$this->GenerarDatosContables())
            {
                $sw_ok = false;
            }
        }

        return $sw_ok;
    }



    /**
    * Metodo para contabilizar una cuenta de una factura
    *
    * @param integer $cuenta
    * @param boolean $reprocesar
    * @return boolean
    * @access private
    */
    function ContabilizarCuenta($cuenta,$reprocesar)
    {
        GLOBAL $ADODB_FETCH_MODE;
        static $dbconn;

        if(!is_object($dbconn))
        {
            list($dbconn) = GetDBconn();
        }

        if($reprocesar)
        {
            $sql = "DELETE FROM cg_temp_contabilizacion_facturas_cuentas
                    WHERE tipo_factura='".$this->datosFactura['tipo_factura']."'
                    AND numerodecuenta=$cuenta";

            $dbconn->Execute($sql);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CC-1";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
        }

        $sql = "SELECT b.cg_contabilizacion_estado_id
                FROM cuentas a, cg_temp_contabilizacion_facturas_cuentas b
                WHERE a.numerodecuenta=$cuenta
                AND a.numerodecuenta=b.numerodecuenta";

        $result = $dbconn->Execute($sql);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CC-2";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $sql = "INSERT INTO cg_temp_contabilizacion_facturas_cuentas(tipo_factura,numerodecuenta,empresa_id, prefijo, numero,cg_contabilizacion_estado_id)
                    VALUES('".$this->datosFactura['tipo_factura']."',$cuenta,'".$this->datosFactura['empresa_id']."','".$this->datosFactura['prefijo']."',".$this->datosFactura['factura_fiscal'].",'00')";
            $dbconn->Execute($sql);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CC-3";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
        }
        else
        {
            list($cg_estado)=$result->FetchRow();
            $result->Close();
            if($cg_estado=='OK')
            {
                return true;
            }
        }

        $sql = "SELECT b.cg_contabilizacion_estado_id, a.transaccion, a.numerodecuenta,
                a.tarifario_id, a.cargo, a.cargo_cups, a.valor_cargo, a.valor_nocubierto,
                a.valor_cubierto, a.departamento, a.facturado, a.consecutivo, d.plan_id, d.valor_total_empresa,
                c.grupo_tarifario_id, c.subgrupo_tarifario_id, e.sw_tipo_plan, f.tipo_tercero_id,
                f.tercero_id, f.valor, f.porcentaje_honorario, g.servicio, g.unidad_funcional, g.centro_utilidad
                FROM cuentas_detalle a LEFT JOIN cg_temp_contabilizacion_facturas_cargos b ON (a.transaccion=b.transaccion)
                LEFT JOIN cuentas_detalle_honorarios f ON(a.transaccion=f.transaccion),
                tarifarios_detalle c, cuentas d, planes e, departamentos g
                WHERE a.numerodecuenta=$cuenta
                AND a.facturado = '1'
                AND c.tarifario_id=a.tarifario_id
                AND c.cargo=a.cargo
                AND d.numerodecuenta=a.numerodecuenta
                AND e.plan_id=d.plan_id
                AND g.departamento=a.departamento";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CC-4";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $sw_ok_transacciones = true;

        while($Fila=$result->FetchRow())
        {
            if($Fila['cg_contabilizacion_estado_id']!='OK')
            {
                $varRetorno=true;

                switch($this->datosFactura['tipo_factura'])
                {
                    case '0': //FACTURA DEL PACIENTE
                        //Si no se genera factura del cliente para que contabilize todo en la del paciente.
                        if($Fila['valor_total_empresa']===0)
                        {
                            $valor = $Fila['valor_cargo'];
                        }
                        else
                        {
                            $valor = $Fila['valor_nocubierto'];
                        }

                        break;

                    case '1': //FACTURA DE LA ENTIDAD
                        $valor = $Fila['valor_cubierto'];
                        break;

                    case '2': //FACTURA DE PACIENTE PARTICULAR
                        $valor = $Fila['valor_cargo'];
                        break;

                    case '3': //FACTURA DE CAPITACION AGRUPADA
                        $valor = $Fila['valor_cubierto'];
                        break;

                    case '4': //FACTURA DE LA ENTIDAD AGRUPADA
                        $valor = $Fila['valor_cubierto'];
                        break;

                    default:

                        $this->error = "TIPO DE FACTURA NO DEFINIDO";
                        $this->mensajeDeError = "El tipo de factura ".$this->datosFactura['tipo_factura']." no esta definido en la interfase de contabilizacion.";
                        return false;
                }

                if($valor>0 || ($valor<0 && $Fila['tarifario_id']=='SYS'))
                {
                    $varRetorno = $this->ContabilizarCuentaTransacciones(&$Fila, $valor);
                }

                if(!$varRetorno) $sw_ok_transacciones = false;
            }
        }

        $result->Close();

        if($sw_ok_transacciones)
        {
            $sql = "UPDATE cg_temp_contabilizacion_facturas_cuentas
                    SET cg_contabilizacion_estado_id='OK'
                    WHERE tipo_factura='".$this->datosFactura['tipo_factura']."'
                    AND numerodecuenta = $cuenta";

            $dbconn->Execute($sql);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CC-6";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
        }
        return $sw_ok_transacciones;
    }


    /**
    * Metodo para contabilizar las transacciones de una cuenta
    *
    * @param array $datosTransaccion
    * @return boolean
    * @access private
    */
    function ContabilizarCuentaTransacciones($datosTransaccion,$valor)
    {
        GLOBAL $ADODB_FETCH_MODE;
        static $dbconn;

        if(!is_object($dbconn))
        {
            list($dbconn) = GetDBconn();
        }

        // Revisar el estado de contabilizacion de este cargo
        $sql = "SELECT cg_contabilizacion_estado_id FROM cg_temp_contabilizacion_facturas_cargos
                WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion]";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-1";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $sql = "INSERT INTO cg_temp_contabilizacion_facturas_cargos(tipo_factura,transaccion,numerodecuenta,cg_contabilizacion_estado_id)
                    VALUES('".$this->datosFactura['tipo_factura']."',$datosTransaccion[transaccion],$datosTransaccion[numerodecuenta],'00')";
            $dbconn->Execute($sql);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-2";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
        }
        else
        {
            list($cg_estado)=$result->FetchRow();
            $result->Close();
            //si ya esta contabilizado no hago nada mas.
            if($cg_estado=='OK')
            {
                return true;
            }
        }

        // Contabilizacion de cargos especiales "SISTEMA"
        if($datosTransaccion['tarifario_id']=='SYS')
        {
            //si esta relacionado con un movimiento de bodega
            if($datosTransaccion['consecutivo'])
            {
                $sql = "SELECT a.codigo_producto, b.grupo_id, b.clase_id, b.subclase_id
                        FROM bodegas_documentos_d a, inventarios_productos b
                        WHERE consecutivo = $datosTransaccion[consecutivo]
                        AND b.codigo_producto = a.codigo_producto ;";

                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($sql);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-IM1";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                if($result->EOF)
                {
                    $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='08'
                            WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

                    $dbconn->Execute($sql);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-IM2";
                        $this->mensajeDeError = $dbconn->ErrorMsg();
                        return false;
                    }

                    return false;
                }

                $vProducto=$result->FetchRow();
                $result->Close();

                $sql = "SELECT d.empresa_id, d.cuenta, d.naturaleza, e.sw_tercero, e.sw_centro_costo, e.sw_cuenta_movimiento
                        FROM cg_parametros_inv_productos_excepciones d, cg_plan_de_cuentas e
                        WHERE d.empresa_id ='".$this->datosFactura['empresa_id']."'
                        AND d.codigo_producto='".$vProducto['codigo_producto']."'
                        AND e.empresa_id = d.empresa_id
                        AND e.cuenta = d.cuenta";
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($sql);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-IM3";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                if($result->EOF)
                {
                    $result->Close();

                    $sql = "SELECT d.empresa_id, d.cuenta, d.naturaleza, e.sw_tercero, e.sw_centro_costo, e.sw_cuenta_movimiento
                            FROM cg_parametros_inv_productos d, cg_plan_de_cuentas e
                            WHERE d.empresa_id = '".$this->datosFactura['empresa_id']."'
                            AND d.grupo_id = '".$vProducto['grupo_id']."'
                            AND d.clase_id = '".$vProducto['clase_id']."'
                            AND d.subclase_id = '".$vProducto['subclase_id']."'
                            AND e.empresa_id = d.empresa_id
                            AND e.cuenta = d.cuenta";

                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $result = $dbconn->Execute($sql);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-IM4";
                        $this->mensajeDeError = $dbconn->ErrorMsg();
                        return false;
                    }

                    if($result->EOF)
                    {
                        $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='03', cg_contabilizacion_msg='".$vProducto['codigo_producto']."'
                                WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

                        $dbconn->Execute($sql);
                        if($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-IM5";
                            $this->mensajeDeError = $dbconn->ErrorMsg();
                            return false;
                        }
                        return false;
                    }
                }

                $cuentaProducto=$result->FetchRow();
                $result->Close();

                if($cuentaProducto['sw_cuenta_movimiento']!='1')
                {
                    $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='07', cg_contabilizacion_msg='".$cuentaProducto['cuenta']."'
                            WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

                    $dbconn->Execute($sql);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-IM6";
                        $this->mensajeDeError = $dbconn->ErrorMsg();
                        return false;
                    }

                    return false;
                }

                if($cuentaProducto['naturaleza']=='D')
                {
                    $vd=$valor;
                    $vc=0;
                }
                else
                {
                    $vd=0;
                    $vc=$valor;
                }

                //Si la cuenta requiere tercero
                if($cuentaProducto['sw_tercero']!='0')
                {
                    $t_tp="'".$this->datosFactura['tipo_id_tercero']."'";
                    $t_id="'".$this->datosFactura['tercero_id']."'";
                }
                else
                {
                    $t_tp="NULL";
                    $t_id="NULL";
                }

                //Revisar si la cuenta requiere centro de costo
                if($CargoSistema['sw_centro_costo']=='1')
                {
                    $cc_dp="'".$datosTransaccion['departamento']."'";
                    $cc_uf="'".$datosTransaccion['unidad_funcional']."'";
                    $cc_cu="'".$datosTransaccion['centro_utilidad']."'";
                }
                else
                {
                    $cc_dp="NULL";
                    $cc_uf="NULL";
                    $cc_cu="NULL";
                }

                $sql="INSERT INTO cg_temp_contabilizacion_facturas_cargos_cuentaspuc(tipo_factura,transaccion,empresa_id,cuenta,tipo_id_tercero,tercero_id,debito,credito,departamento,unidad_funcional,centro_utilidad,consecutivo)
                        VALUES( '".$this->datosFactura['tipo_factura']."',
                                $datosTransaccion[transaccion],
                                '".$cuentaProducto['empresa_id']."',
                                '".$cuentaProducto['cuenta']."',
                                $t_tp,
                                $t_id,
                                $vd,
                                $vc,
                                $cc_dp,
                                $cc_uf,
                                $cc_cu,
                                $datosTransaccion[consecutivo]
                        );";

                $dbconn->Execute($sql);

                if($dbconn->ErrorNo() != 0)
                {
                    $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='03'
                            WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

                    $dbconn->Execute($sql);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-IM7";
                        $this->mensajeDeError = $dbconn->ErrorMsg();
                        return false;
                    }

                    return false;
                }


                $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='OK'
                        WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

                $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-IM8";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                return true;

            }//fin de Insumos y Medicamentos


            //PARAMETROS DE CARGOS DEL SISTEMA
            $sql = "SELECT a.empresa_id, a.cuenta, a.naturaleza, b.sw_tercero, b.sw_centro_costo, b.sw_cuenta_movimiento
                    FROM cg_parametros_cargos_sistema a, cg_plan_de_cuentas b
                    WHERE a.empresa_id ='".$this->datosFactura['empresa_id']."'
                    AND a.tarifario_id ='SYS'
                    AND a.cargo = '".$datosTransaccion['cargo']."'
                    AND (a.departamento='".$datosTransaccion['departamento']."' OR a.departamento IS NULL)
                    AND a.empresa_id=b.empresa_id AND  a.cuenta=b.cuenta
                    ORDER BY departamento;";


            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-7";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            if($result->EOF)
            {
                $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='02'
                        WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

                $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-8";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                return false;
            }

            $CargoSistema=$result->FetchRow();
            $result->Close();

            if($CargoSistema['sw_cuenta_movimiento']!='1')
            {
                $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='07', cg_contabilizacion_msg='".$CargoSistema['cuenta']."'
                        WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

                $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-8";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                return false;
            }

            $valor=abs($valor);

            //contabilizar en la naturaleza de la cuenta
            if($CargoSistema['naturaleza']=='D')
            {
                $vd=$valor;
                $vc=0;

            }
            else
            {
                $vd=0;
                $vc=$valor;
            }

            //Si la cuenta requiere tercero
            if($CargoSistema['sw_tercero']!='0')
            {
                $t_tp="'".$this->datosFactura['tipo_id_tercero']."'";
                $t_id="'".$this->datosFactura['tercero_id']."'";
            }
            else
            {
                $t_tp="NULL";
                $t_id="NULL";
            }

            //Revisar si la cuenta requiere centro de costo
            if($CargoSistema['sw_centro_costo']=='1')
            {
                $cc_dp="'".$datosTransaccion['departamento']."'";
                $cc_uf="'".$datosTransaccion['unidad_funcional']."'";
                $cc_cu="'".$datosTransaccion['centro_utilidad']."'";
            }
            else
            {
                $cc_dp="NULL";
                $cc_uf="NULL";
                $cc_cu="NULL";
            }

            $sql="INSERT INTO cg_temp_contabilizacion_facturas_cargos_cuentaspuc(tipo_factura,transaccion,empresa_id,cuenta,tipo_id_tercero,tercero_id,debito,credito,departamento,unidad_funcional,centro_utilidad)
                    VALUES( '".$this->datosFactura['tipo_factura']."',
                            $datosTransaccion[transaccion],
                            '".$CargoSistema['empresa_id']."',
                            '".$CargoSistema['cuenta']."',
                            $t_tp,
                            $t_id,
                            $vd,
                            $vc,
                            $cc_dp,
                            $cc_uf,
                            $cc_cu
                    );";

            $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='02'
                        WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

                $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-9";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                return false;
            }


            $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='OK'
                    WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

            $dbconn->Execute($sql);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-10";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            return true;
        }//fin del manejo de cargos del sistema


        //CARGOS NORMALES
        $sql = "SELECT a.empresa_id, a.cuenta, a.naturaleza, b.sw_tercero, b.sw_centro_costo, b.sw_cuenta_movimiento
                FROM cg_parametros_cargos_excepciones a, cg_plan_de_cuentas b
                WHERE a.empresa_id ='".$this->datosFactura['empresa_id']."'
                AND a.tarifario_id='".$datosTransaccion['tarifario_id']."'
                AND a.cargo='".$datosTransaccion['cargo']."'
                AND (a.departamento='".$datosTransaccion['departamento']."' OR a.departamento IS NULL)
                AND a.empresa_id=b.empresa_id AND  a.cuenta=b.cuenta
                ORDER BY departamento;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-11-A";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $result->Close();

            $sql = "SELECT a.empresa_id, a.cuenta, a.naturaleza, b.sw_tercero, b.sw_centro_costo, b.sw_cuenta_movimiento
                    FROM cg_parametros_cargos a, cg_plan_de_cuentas b
                    WHERE a.empresa_id='".$this->datosFactura['empresa_id']."'
                    AND a.grupo_tarifario_id='".$datosTransaccion['grupo_tarifario_id']."'
                    AND a.subgrupo_tarifario_id='".$datosTransaccion['subgrupo_tarifario_id']."'
                    AND (a.departamento='".$datosTransaccion['departamento']."' OR a.departamento IS NULL)
                    AND a.empresa_id=b.empresa_id AND  a.cuenta=b.cuenta
                    ORDER BY departamento;";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-11";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            if($result->EOF)
            {
                $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='01'
                        WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

                $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-12";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                return false;
            }
        }

        //CUENTA Y NATURALEZA EN LA QUE SE VA CONTABILIZAR EL CARGO
        $ParametrosCG = $result->FetchRow();
        $result->Close();

        if($ParametrosCG['sw_cuenta_movimiento']!='1')
        {
            $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='07', cg_contabilizacion_msg='".$ParametrosCG['cuenta']."'
                    WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

            $dbconn->Execute($sql);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-8";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            return false;
        }

        //SI HAY VALOR DE HONORARIO LIQUIDADO
        if($datosTransaccion['porcentaje_honorario'])
        {
            $sql = "SELECT a.empresa_id, a.cuenta, a.naturaleza, b.sw_tercero, b.sw_centro_costo, b.sw_cuenta_movimiento
                    FROM cg_parametros_honorarios a, cg_plan_de_cuentas b
                    WHERE a.empresa_id='".$this->datosFactura['empresa_id']."'
                    AND a.empresa_id=b.empresa_id AND a.cuenta=b.cuenta;";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if($dbconn->ErrorNo() != 0)
            {
                $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='05'
                        WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

                $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-13";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                return false;
            }

            if($result->EOF)
            {
                $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='04'
                        WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

                $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-14";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                return false;
            }

            $Cuenta_Honorario= $result->FetchRow();
            $result->Close();

            if($Cuenta_Honorario['sw_cuenta_movimiento']!='1')
            {
                $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='07', cg_contabilizacion_msg='".$Cuenta_Honorario['cuenta']."'
                        WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

                $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-8";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                return false;
            }

            $vh=RedondearValores($valor * $datosTransaccion['porcentaje_honorario']/100);

            if($Cuenta_Honorario['naturaleza']=='D')
            {
                $vd=$vh;
                $vc=0;
            }
            else
            {
                $vd=0;
                $vc=$vh;
            }

            //Si la cuenta requiere tercero
            if($Cuenta_Honorario['sw_tercero']!='0')
            {
                $t_tp="'".$datosTransaccion['tipo_tercero_id']."'";
                $t_id="'".$datosTransaccion['tercero_id']."'";
            }
            else
            {
                $t_tp="NULL";
                $t_id="NULL";
            }

            //Revisar si la cuenta requiere centro de costo
            if($Cuenta_Honorario['sw_centro_costo']=='1')
            {
                $cc_dp="'".$datosTransaccion['departamento']."'";
                $cc_uf="'".$datosTransaccion['unidad_funcional']."'";
                $cc_cu="'".$datosTransaccion['centro_utilidad']."'";
            }
            else
            {
                $cc_dp="NULL";
                $cc_uf="NULL";
                $cc_cu="NULL";
            }

            $sql="INSERT INTO cg_temp_contabilizacion_facturas_cargos_cuentaspuc(tipo_factura,transaccion,empresa_id,cuenta,tipo_id_tercero,tercero_id,debito,credito,departamento,unidad_funcional,centro_utilidad)
                    VALUES( '".$this->datosFactura['tipo_factura']."',
                            $datosTransaccion[transaccion],
                            '".$Cuenta_Honorario['empresa_id']."',
                            '".$Cuenta_Honorario['cuenta']."',
                            $t_tp,
                            $t_id,
                            $vd,
                            $vc,
                            $cc_dp,
                            $cc_uf,
                            $cc_cu
                        );";

            $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='05'
                        WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

                $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-16";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                return false;
            }

            $valor = $valor - $vh;
        }//FIN DE LA CONTABILIZACION DE HONORARIOS DEL CARGO

        if($ParametrosCG['naturaleza']=='D')
        {
            $vd=&$valor;
            $vc=0;
        }
        else
        {
            $vd=0;
            $vc=&$valor;
        }

        //Si la cuenta requiere tercero
        if($ParametrosCG['sw_tercero']!='0')
        {
            $t_tp="'".$this->datosFactura['tipo_id_tercero']."'";
            $t_id="'".$this->datosFactura['tercero_id']."'";
        }
        else
        {
            $t_tp="NULL";
            $t_id="NULL";
        }

        //Revisar si la cuenta requiere centro de costo
        if($ParametrosCG['sw_centro_costo']=='1')
        {
            $cc_dp="'".$datosTransaccion['departamento']."'";
            $cc_uf="'".$datosTransaccion['unidad_funcional']."'";
            $cc_cu="'".$datosTransaccion['centro_utilidad']."'";
        }
        else
        {
            $cc_dp="NULL";
            $cc_uf="NULL";
            $cc_cu="NULL";
        }

        $sql="INSERT INTO cg_temp_contabilizacion_facturas_cargos_cuentaspuc(tipo_factura,transaccion,empresa_id,cuenta,tipo_id_tercero,tercero_id,debito,credito,departamento,unidad_funcional,centro_utilidad)
                VALUES( '".$this->datosFactura['tipo_factura']."',
                        $datosTransaccion[transaccion],
                        '".$ParametrosCG['empresa_id']."',
                        '".$ParametrosCG['cuenta']."',
                        $t_tp,
                        $t_id,
                        $vd,
                        $vc,
                        $cc_dp,
                        $cc_uf,
                        $cc_cu
                    );";

        $dbconn->Execute($sql);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-17";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $sql = "UPDATE cg_temp_contabilizacion_facturas_cargos SET cg_contabilizacion_estado_id='OK'
                WHERE tipo_factura='".$this->datosFactura['tipo_factura']."' AND transaccion=$datosTransaccion[transaccion];";

        $dbconn->Execute($sql);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR CCT-18";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        return true;
    }//fin metodo




    /**
    * Metodo para contabilizar una factura
    *
    * @return
    * @access private
    */
    function GenerarDatosContables()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        if($this->FacturaCGpuc['agrupamiento'])
        {
            $sql="SELECT a.empresa_id, a.cuenta, a.tipo_id_tercero, a.tercero_id,
                    a.departamento, a.unidad_funcional, a.centro_utilidad,
                    SUM(a.debito) as debito, SUM(a.credito) as credito

                    FROM
                    cg_temp_contabilizacion_facturas_cargos_cuentaspuc a,
                    cg_temp_contabilizacion_facturas_cargos b,
                    cg_temp_contabilizacion_facturas_cuentas c,
                    cg_temp_contabilizacion_facturas d

                    WHERE
                    d.empresa_id='".$this->datosFactura['empresa_id']."'
                    AND d.prefijo='".$this->datosFactura['prefijo']."'
                    AND d.numero=".$this->datosFactura['factura_fiscal']."

                    AND c.empresa_id=d.empresa_id
                    AND c.prefijo=d.prefijo
                    AND c.numero=d.numero

                    AND b.tipo_factura=c.tipo_factura
                    AND b.numerodecuenta=c.numerodecuenta

                    AND a.tipo_factura=b.tipo_factura
                    AND a.transaccion=b.transaccion
                    AND a.consecutivo IS NULL

                    GROUP BY a.empresa_id, a.cuenta, a.tipo_id_tercero, a.tercero_id,
                    a.departamento, a.unidad_funcional, a.centro_utilidad";
        }
        else
        {
            $sql="SELECT a.empresa_id, a.cuenta, a.debito , a.credito, a.tipo_id_tercero, a.tercero_id,
                    a.departamento, a.unidad_funcional, a.centro_utilidad,
                    'FACTURA No. ' || d.empresa_id || ' ' || d.prefijo || '-' || d.numero || ' CUENTA No. ' || b.numerodecuenta || ' - '  || b.transaccion AS detalle

                    FROM
                    cg_temp_contabilizacion_facturas_cargos_cuentaspuc a,
                    cg_temp_contabilizacion_facturas_cargos b,
                    cg_temp_contabilizacion_facturas_cuentas c,
                    cg_temp_contabilizacion_facturas d

                    WHERE
                    d.empresa_id='".$this->datosFactura['empresa_id']."'
                    AND d.prefijo='".$this->datosFactura['prefijo']."'
                    AND d.numero=".$this->datosFactura['factura_fiscal']."

                    AND c.empresa_id=d.empresa_id
                    AND c.prefijo=d.prefijo
                    AND c.numero=d.numero

                    AND b.tipo_factura=c.tipo_factura
                    AND b.numerodecuenta=c.numerodecuenta

                    AND a.tipo_factura=b.tipo_factura
                    AND a.transaccion=b.transaccion
                    AND a.consecutivo IS NULL
                    ";
        }

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR GDC-1";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if(!$result->EOF)
        {
            $d=$result->GetRows();
        }
        $result->Close();


        $abonosCredito=0;
        $abonosDebito=0;
        //CONTABILIZO LA CUOTA MODERADORA
        if($this->datosFactura['valor_cuota_moderadora']>0)
        {
            $sql="SELECT a.empresa_id, a.cuenta_cuota_moderadora, a.cuenta_cuota_moderadora_naturaleza,
                    b.sw_tercero, b.sw_centro_costo, b.sw_cuenta_movimiento
                    FROM cg_parametros_generales_contabilidad a, cg_plan_de_cuentas b
                    WHERE a.empresa_id='".$this->datosFactura['empresa_id']."'
                    AND b.empresa_id=a.empresa_id
                    AND b.cuenta=a.cuenta_cuota_moderadora";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR GDC-2";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
            if($result->EOF)
            {

                $sql = "UPDATE cg_temp_contabilizacion_facturas SET cg_contabilizacion_estado_id='08'
                        WHERE  empresa_id='".$this->datosFactura['empresa_id']."' AND prefijo='".$this->datosFactura['prefijo']."' AND numero=".$this->datosFactura['factura_fiscal'].";";

                $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR GDC-2A";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }
                return false;
            }

            $datosCM=$result->FetchRow();
            $result->Close();

            if(empty($datosCM['cuenta_cuota_moderadora']) || empty($datosCM['cuenta_cuota_moderadora_naturaleza']))
            {
                $sql = "UPDATE cg_temp_contabilizacion_facturas SET cg_contabilizacion_estado_id='08'
                        WHERE  empresa_id='".$this->datosFactura['empresa_id']."' AND prefijo='".$this->datosFactura['prefijo']."' AND numero=".$this->datosFactura['factura_fiscal'].";";

                $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR GDC-2A";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }
                return false;
            }

            //Segun el tipo de factura contabilizo la CM como Credito o Debito
            if($this->datosFactura['tipo_factura']==='0')
            {
                if($datosCM['cuenta_cuota_moderadora_naturaleza']=='D')
                {
                    $vd=$this->datosFactura['valor_cuota_moderadora'];
                    $vc=0;
                }
                else
                {
                    $vd=0;
                    $vc=$this->datosFactura['valor_cuota_moderadora'];
                }

                //AQUI CONTABILIZAR CASO ESPECIAL
                //CUANDO LA CUOTA ES MAYOR QUE EL VALOR DEL CARGO
                //CARGO DE APROVECHAMIENTO.

            }
            else
            {
                if($datosCM['cuenta_cuota_moderadora_naturaleza']=='C')
                {
                    $vd=$this->datosFactura['valor_cuota_moderadora'];
                    $vc=0;
                }
                else
                {
                    $vd=0;
                    $vc=$this->datosFactura['valor_cuota_moderadora'];
                }
                $abonosCredito=$vc;
                $abonosDebito=$vd;
            }

            $CM['empresa_id']=$datosCM['empresa_id'];
            $CM['cuenta']=$datosCM['cuenta_cuota_moderadora'];

            //Si la cuenta requiere tercero
            if($datosCM['sw_tercero']!='0')
            {
                $CM['tipo_id_tercero']=$this->datosFactura['tipo_id_tercero'];
                $CM['tercero_id']=$this->datosFactura['tercero_id'];
            }
            else
            {
                $CM['tipo_id_tercero']='';
                $CM['tercero_id']='';
            }

            $CM['debito']=$vd;
            $CM['credito']=$vc;
            $CM['departamento']='';
            $CM['unidad_funcional']='';
            $CM['centro_utilidad']='';
            $CM['detalle']='C.M. FACTURA No. '.$this->datosFactura['empresa_id'] . ' ' . $this->datosFactura['prefijo'] . '-' . $this->datosFactura['factura_fiscal'];

            $d[]=$CM;
            unset($CM);
            unset($datosCM);
        }

        //CONTABILIZO EL COPAGO
        if($this->datosFactura['valor_cuota_paciente']>0)
        {
            $sql="SELECT a.empresa_id, a.cuenta_copago, a.cuenta_copago_naturaleza,
                    b.sw_tercero, b.sw_centro_costo, b.sw_cuenta_movimiento
                    FROM cg_parametros_generales_contabilidad a, cg_plan_de_cuentas b
                    WHERE a.empresa_id='".$this->datosFactura['empresa_id']."'
                    AND b.empresa_id=a.empresa_id
                    AND b.cuenta=a.cuenta_copago";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR GDC-3";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
            if($result->EOF)
            {
               $sql = "UPDATE cg_temp_contabilizacion_facturas SET cg_contabilizacion_estado_id='09'
                        WHERE  empresa_id='".$this->datosFactura['empresa_id']."' AND prefijo='".$this->datosFactura['prefijo']."' AND numero=".$this->datosFactura['factura_fiscal'].";";

                $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR GDC-2A";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }
                return false;

            }
            $datosCP=$result->FetchRow();
            $result->Close();

            if(empty($datosCP['cuenta_copago']) || empty($datosCP['cuenta_copago_naturaleza']))
            {
               $sql = "UPDATE cg_temp_contabilizacion_facturas SET cg_contabilizacion_estado_id='09'
                        WHERE  empresa_id='".$this->datosFactura['empresa_id']."' AND prefijo='".$this->datosFactura['prefijo']."' AND numero=".$this->datosFactura['factura_fiscal'].";";

                $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR GDC-2A";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }
                return false;
            }

            //Segun el tipo de factura contabilizo la CP como Credito o Debito
            if($this->datosFactura['tipo_factura']==='0')
            {
                if($datosCP['cuenta_copago_naturaleza']=='D')
                {
                    $vd=$this->datosFactura['valor_cuota_paciente'];
                    $vc=0;
                }
                else
                {
                    $vd=0;
                    $vc=$this->datosFactura['valor_cuota_paciente'];
                }
            }
            else
            {
                if($datosCP['cuenta_copago_naturaleza']=='C')
                {
                    $vd=$this->datosFactura['valor_cuota_paciente'];
                    $vc=0;
                }
                else
                {
                    $vd=0;
                    $vc=$this->datosFactura['valor_cuota_paciente'];
                }
                $abonosCredito+=$vc;
                $abonosDebito+=$vd;
            }

            $CP['empresa_id']=$datosCP['empresa_id'];
            $CP['cuenta']=$datosCP['cuenta_copago'];


            //Si la cuenta requiere tercero
            if($datosCP['sw_tercero']!='0')
            {
                $CP['tipo_id_tercero']=$this->datosFactura['tipo_id_tercero'];
                $CP['tercero_id']=$this->datosFactura['tercero_id'];
            }
            else
            {
                $CP['tipo_id_tercero']='';
                $CP['tercero_id']='';
            }


            $CP['debito']=$vd;
            $CP['credito']=$vc;
            $CP['departamento']='';
            $CP['unidad_funcional']='';
            $CP['centro_utilidad']='';
            $CP['detalle']='COPAGO FACTURA No. ' . $this->datosFactura['empresa_id'] . ' ' . $this->datosFactura['prefijo'] . '-' . $this->datosFactura['factura_fiscal'];

            $d[]=$CP;
            unset($CP);
            unset($datosCP);
        }

        //CONTABILIZACION DE INSUMOS Y MEDICAMENTOS
        $sql="SELECT a.empresa_id, a.cuenta, a.tipo_id_tercero, a.tercero_id,
                a.departamento, a.unidad_funcional, a.centro_utilidad,
                SUM(a.debito) as debito, SUM(a.credito) as credito

                FROM
                cg_temp_contabilizacion_facturas_cargos_cuentaspuc a,
                cg_temp_contabilizacion_facturas_cargos b,
                cg_temp_contabilizacion_facturas_cuentas c,
                cg_temp_contabilizacion_facturas d

                WHERE
                d.empresa_id='".$this->datosFactura['empresa_id']."'
                AND d.prefijo='".$this->datosFactura['prefijo']."'
                AND d.numero=".$this->datosFactura['factura_fiscal']."

                AND c.empresa_id=d.empresa_id
                AND c.prefijo=d.prefijo
                AND c.numero=d.numero

                AND b.tipo_factura=c.tipo_factura
                AND b.numerodecuenta=c.numerodecuenta

                AND a.tipo_factura=b.tipo_factura
                AND a.transaccion=b.transaccion
                AND a.consecutivo IS NOT NULL

                GROUP BY a.empresa_id, a.cuenta, a.tipo_id_tercero, a.tercero_id, a.departamento,
                a.unidad_funcional, a.centro_utilidad";


        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR GDC-3A";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        while($datosIM=$result->FetchRow())
        {
            $IM['empresa_id']=$datosIM['empresa_id'];
            $IM['cuenta']=$datosIM['cuenta'];
            $IM['tipo_id_tercero']=$datosIM['tipo_id_tercero'];
            $IM['tercero_id']=$datosIM['tercero_id'];
            $IM['departamento']=$datosIM['departamento'];
            $IM['unidad_funcional']=$datosIM['unidad_funcional'];
            $IM['centro_utilidad']=$datosIM['centro_utilidad'];
            $IM['debito']=$datosIM['debito'];
            $IM['credito']=$datosIM['credito'];
            $IM['detalle']='IyM FACTURA No. ' . $this->datosFactura['empresa_id'] . ' ' . $this->datosFactura['prefijo'] . '-' . $this->datosFactura['factura_fiscal'];

            $d[]=$IM;
            unset($IM);
        }
        $result->Close();


        //CONTABILIZACION DEL DOCUMENTO

        $totalFactura['empresa_id']=$this->FacturaCGpuc['empresa_id'];
        $totalFactura['cuenta']=$this->FacturaCGpuc['cuenta'];
        $totalFactura['tipo_id_tercero']=$this->datosFactura['tipo_id_tercero'];
        $totalFactura['tercero_id']=$this->datosFactura['tercero_id'];
        $totalFactura['debito']=$this->datosFactura['total_factura'];
        $totalFactura['credito']=0;
        $d[]=$totalFactura;
        unset($totalFactura);

        $sumCredito=0;
        $sumDebito=0;
        foreach($d as $k=>$v)
        {
            $sumCredito += $v['credito'];
            $sumDebito  += $v['debito'];
        }

        if($this->datosFactura['tipo_factura']==='3')
        {
            if($sumCredito>$sumDebito)
            {
                $CfacAgrupada['empresa_id']='01';
                $CfacAgrupada['cuenta']='141505';
                $CfacAgrupada['tipo_id_tercero']=$this->datosFactura['tipo_id_tercero'];
                $CfacAgrupada['tercero_id']=$this->datosFactura['tercero_id'];
                $CfacAgrupada['debito']=$sumCredito-$sumDebito;
                $CfacAgrupada['credito']=0;
                $d[]=$CfacAgrupada;
                unset($CfacAgrupada);
                $sumDebito += $sumCredito-$sumDebito;
            }
            elseif($sumCredito<$sumDebito)
            {
                $CfacAgrupada['empresa_id']='01';
                $CfacAgrupada['cuenta']='141505';
                $CfacAgrupada['tipo_id_tercero']=$this->datosFactura['tipo_id_tercero'];
                $CfacAgrupada['tercero_id']=$this->datosFactura['tercero_id'];
                $CfacAgrupada['debito']=0;
                $CfacAgrupada['credito']=$sumDebito-$sumCredito;
                $d[]=$CfacAgrupada;
                unset($CfacAgrupada);
                $sumCredito += $sumDebito-$sumCredito;
            }

        }

        if($sumCredito != $sumDebito)
        {
               $sql = "UPDATE cg_temp_contabilizacion_facturas SET cg_contabilizacion_estado_id='11'
                        WHERE  empresa_id='".$this->datosFactura['empresa_id']."' AND prefijo='".$this->datosFactura['prefijo']."' AND numero=".$this->datosFactura['factura_fiscal'].";";

                $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR GDC-2B";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }
                return false;


/*            echo "<br><br>";
            echo "Suma Credito : $sumCredito";
            echo "<br><br>";
            echo "Suma Debito  : $sumDebito";
            echo "<br><br>";
            die("DESCUADRE DEBITO CREDITO - AQUI DEBO RETORNAR CODIGO DE NO CONTABILIZACION");*/

        }

        $sql="SELECT b.tipo_movimiento_id, a.documento_id
                FROM documentos a, tipos_doc_generales b
                WHERE a.documento_id =".$this->datosFactura['documento_id']."
                AND a.empresa_id='".$this->datosFactura['empresa_id']."'
                AND b.tipo_doc_general_id=a.tipo_doc_general_id";


        $result = $dbconn->Execute($sql);


        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR GDC-4";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            die('NO PUDO ESTAR VACIO - CONTROLAME');
        }
        list($tipo_movimiento_id,$documento_id)=$result->FetchRow();
        $result->Close();

        $dbconn->BeginTrans();

        $sql="select nextval('public.cg_movimientos_contables_documento_contable_id_seq'::text);";
        $result = $dbconn->Execute($sql);
        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR GDC-5";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $dbconn->RollbackTrans();
            die('NO PUDO ESTAR VACIO - CONTROLAME');
        }
        list($documento_contable_id)=$result->FetchRow();
        $result->Close();


        $sql ="INSERT INTO cg_movimientos_contables(
                    documento_contable_id,
                    fecha_documento,
                    fecha_registro,
                    empresa_id,
                    documento_id,
                    tipo_movimiento_id,
                    total_debitos,
                    total_creditos,
                    tipo_id_tercero,
                    tercero_id,
                    usuario_id
                )VALUES(
                    $documento_contable_id,
                    '".$this->datosFactura['fecha_registro']."',
                    'NOW()',
                    '".$this->datosFactura['empresa_id']."',
                    $documento_id,
                    '$tipo_movimiento_id',
                    $sumDebito,
                    $sumCredito,
                    '".$this->datosFactura['tipo_id_tercero']."',
                    '".$this->datosFactura['tercero_id']."',
                    '".UserGetUID()."'
                );";

        $sql .="INSERT INTO cg_movimientos_contables_facturas(
                   documento_contable_id,
                   empresa_id,
                   prefijo,
                   numero
                )VALUES(
                    $documento_contable_id,
                    '".$this->datosFactura['empresa_id']."',
                    '".$this->datosFactura['prefijo']."',
                    ".$this->datosFactura['factura_fiscal']."
                );";

        foreach($d as $k=>$v)
        {
            if($v['tipo_id_tercero'])
            {
                $tipo_id_tercero="'$v[tipo_id_tercero]'";
                $tercero_id="'$v[tercero_id]'";
            }
            else
            {
                $tipo_id_tercero = 'NULL';
                $tercero_id = 'NULL';
            }

            if($v['departamento'])
            {
                $dpto="'$v[departamento]'";
            }
            else
            {
                $dpto = 'NULL';
            }

            if($v['unidad_funcional'] && $v['centro_utilidad'])
            {
                $uf="'$v[unidad_funcional]'";
                $cu="'$v[centro_utilidad]'";
            }
            else
            {
                $uf = 'NULL';
                $cu = 'NULL';
            }

            $sql .="INSERT INTO cg_movimientos_contables_facturas_d(
                        documento_contable_id,
                        empresa_id,
                        cuenta,
                        tipo_id_tercero,
                        tercero_id,
                        debito,
                        credito,
                        detalle,
                        departamento,
                        unidad_funcional,
                        centro_utilidad
                    )VALUES(
                        $documento_contable_id,
                        '".$v['empresa_id']."',
                        '".$v['cuenta']."',
                        ".$tipo_id_tercero.",
                        ".$tercero_id.",
                        ".$v['debito'].",
                        ".$v['credito'].",
                        '',
                        ".$dpto.",
                        ".$uf.",
                        ".$cu."
                    );";
        }

        $sql .="UPDATE fac_facturas
                SET documento_contable_id=$documento_contable_id
                WHERE empresa_id='".$this->datosFactura['empresa_id']."'
                AND prefijo='".$this->datosFactura['prefijo']."'
                AND factura_fiscal=".$this->datosFactura['factura_fiscal'].";";

        $sql .="DELETE FROM cg_temp_contabilizacion_facturas
                WHERE empresa_id='".$this->datosFactura['empresa_id']."'
                AND prefijo='".$this->datosFactura['prefijo']."'
                AND numero=".$this->datosFactura['factura_fiscal'].";";


        $result = $dbconn->Execute($sql);


        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->error = "CONTABILIZACION DE FACTURAS - SQL ERROR GDC-6";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $dbconn->CommitTrans();
            return true;
        }

        return true;
    }
}//fin de la clase

