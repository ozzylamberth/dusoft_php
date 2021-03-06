<?php
/**
* $Id: Contabilizar_FV01_01.class.php,v 1.2 2007/06/22 01:17:58 alexgiraldo Exp $
*/

/**
* Clase para la contabilizacion de documentos de tipo general FV (Facturas - fac_facturas)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.2 $
* @package SIIS
*/
class Contabilizar_FV01_01 extends ContabilizarDocumento
{

    /**
    * Datos de la factura a contabilizar
    *
    * @var boolean
    * @access private
    */
    var $DatosFactura=array();

    /**
    * Datos de las cuentas de la factura a contabilizar
    *
    * @var boolean
    * @access private
    */
    var $CuentasFactura=array();


    /**
    * Datos de la factura a contabilizar
    *
    * @var boolean
    * @access private
    */
    var $FacturaCGpuc=array();


    /**
    * Vector con los numero de las cuentas contabilizadas
    *
    * @var array
    * @access private
    */
    var $NumeroDeCuentasFactura=array();



    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function Contabilizar_FV01_01()
    {
        $this->ContabilizarDocumento();
        return true;
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
    function ContabilizarDoc($empresa_id,$prefijo,$numero,$actualizar=false)
    {
        if(empty($empresa_id)  || empty($prefijo) || empty($numero))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTAN ARGUMENTOS (empresa_id=$empresa_id, prefijo=$prefijo, numero=$numero)";
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();


        //BUSCAR LA FACTURA
        $sql = "
                SELECT a.*,
                       a.factura_fiscal as numero,
                       a.fecha_registro as fecha_documento,
                       'FV01' as tipo_doc_general_id,
                       b.total_abono,
                       b.total_efectivo,
                       b.total_cheques,
                       b.total_tarjetas,
                       b.total_bonos,
                       CASE WHEN b.prefijo IS NULL THEN 'HOSPITALARIA' ELSE 'CAJA RAPIDA' END AS tipo_factura_contado

                FROM public.fac_facturas AS a
                     LEFT JOIN fac_facturas_contado AS b
                     ON (   b.empresa_id = a.empresa_id
                            AND b.factura_fiscal = a.factura_fiscal
                            AND b.prefijo = a.prefijo
                        )
                WHERE
                    a.empresa_id = '$empresa_id'
                    AND a.prefijo = '$prefijo'
                    AND a.factura_fiscal = $numero;
                ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE ENCONTRARON REGISTROS PARA LA FACTURA (empresa_id=$empresa_id, prefijo=$prefijo, numero=$numero)";
            return false;
        }

        unset($this->$DatosFactura);
        $this->DatosFactura = $result->FetchRow();
        $result->Close();

        //ESTABLECER LOS DATOS DEL DOCUMENTO A CONTABILIZAR
        if($this->SetDocumento($this->DatosFactura)===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo SetDocumento() retorno false";
            }

            return false;
        }

        $retorno =& $this->ValidarActualizacionDelDocumentoContable($actualizar);
        if($retorno !== null) return $retorno;



        //ALGUNA FORMA DE BORRAR LAS TABLAS TEMPORALES DE LA CONTABILIZACION..
        if($this->DelTemporalesFacturacion()===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo DelTemporalesFacturacion() retorno false";
            }

            return false;
        }


        switch($this->DatosFactura['estado'])
        {
            case '0': //ACTIVO
            case '1': //ACTIVA - FACTURAS CANCELADAS (CARTERA RECAUDADA)
            case '3': //ANULADO CON NOTA DEBE CONTABILIZAR NORMALMENTE
            break;

            case '2': //ANULADO
                if($this->GenerarDocumentoAnulado()===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GenerarDocumentoAnulado() retorno false";
                    }

                    return false;
                }

                //RETORNO LA CONTABILIZACION DEL DOCUMENTO ANULADO
                return $this->RetornarDocumentoContable();

                break;

            default: //ESTADOS INVALIDOS DE LA FACTURA

                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El estado de la factura no es valido [".$this->DatosFactura['estado']."].";
                return false;
        }


        //VALIDAR EL TIPO DE FACTURA

        switch($this->DatosFactura['tipo_factura'])
        {
            case '0': //FACTURA PACIENTE
            case '1': //FACTURA CLIENTE
            case '2': //FACTURA PARTICULAR
            case '3': //FACTURA AGRUPADA CAPITACION
            case '4': //FACTURA AGRUPADA
            break;

            default:
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "EL VALOR DEL CAMPO [public.fac_facturas.tipo_factura] = [".$this->DatosFactura['tipo_factura']."] NO ES UN VALOR VALIDO.";
            return false;
        }

        //OBTENER LAS CUENTAS DE LA FACTURA
        $sql = "SELECT a.*, d.sw_estado as sw_estado_contabilizacion
                FROM
                (
                    SELECT  a.empresa_id,
                            a.prefijo,
                            a.factura_fiscal,
                            c.valor_cuota_moderadora,
                            c.valor_cuota_paciente,
                            c.valor_total_cargos,
                            c.valor_cubierto,
                            (c.valor_cuota_moderadora + c.valor_cuota_paciente) as valor_cuotas,
                            b.numerodecuenta,
                            c.total_cuenta
                    FROM    public.fac_facturas a,
                            public.fac_facturas_cuentas b,
                            public.cuentas c

                    WHERE a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
                    AND a.prefijo = '".$this->DatosDocumento['prefijo']."'
                    AND a.factura_fiscal = ".$this->DatosDocumento['numero']."
                    AND b.empresa_id=a.empresa_id
                    AND b.prefijo=a.prefijo
                    AND b.factura_fiscal=a.factura_fiscal
                    AND c.numerodecuenta=b.numerodecuenta
                ) AS a
                LEFT JOIN cg_conf.tmp_contabilizacion_facturas_cuentas d
                        ON  (
                                d.empresa_id = a.empresa_id
                                AND d.prefijo = a.prefijo
                                AND d.numero = a.factura_fiscal
                                AND d.numerodecuenta = a.numerodecuenta
                            )
                ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE ENCONTRARON CUENTAS PARA LA FACTURA (empresa_id=$empresa_id, prefijo=$prefijo, numero=$numero)";
            return false;
        }

        unset($this->CuentasFactura);
        while($Fila=$result->FetchRow())
        {
            $this->CuentasFactura[$Fila['numerodecuenta']]=$Fila;
        }
        $result->Close();

        //MANEJO DE TEMPORALES DE CONTABILIZACION
        $CUENTAS_OK = true;

        //CONTABILIZAR CADA UNA DE LAS CUENTAS QUE FORMAN LA FACTURA.
        foreach ($this->CuentasFactura as $NumeroDeCuenta=>$DatosCuenta)
        {
            if(!$DatosCuenta['sw_estado_contabilizacion'])
            {
                $RetornoContabilizarCuenta = $this->Contabilizar_Cuenta($NumeroDeCuenta);

                if($RetornoContabilizarCuenta===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo Contabilizar_Cuenta() retorno false";
                    }


                    if($this->AddTmpCuenta($NumeroDeCuenta, $error=true)===false)
                    {
                        if(empty($this->error))
                        {
                            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                            $this->mensajeDeError = "El metodo AddTmpCuenta() retorno false";
                        }

                        return false;
                    }

                    $CUENTAS_OK = false;
                }
                elseif($RetornoContabilizarCuenta===true)
                {
                    $CM_OK=true;

                    //CONTABILIZO EL ANTICIPO DE CUOTA MODERADORA POR CADA CUENTA (para evitar contabilizar como anticipo las cuotas moderadoras que son mayores que los cargos de la cuenta.)
                    if($DatosCuenta['valor_cuotas']>0)
                    {
                        $ApovechamientoCM = ($DatosCuenta['valor_cuotas'] - $DatosCuenta['valor_cubierto']);

                        if($ApovechamientoCM >= 0) //SI LA CUOTA MODERADORA ES MAYOR QUE EL TOTAL DE LA CUENTA.
                        {
                            $this->DatosFactura['aprovechamientos_cuotas'] += $ApovechamientoCM;
                            $this->DatosFactura['aprovechamientos_cuotas_cuentas'] .= $NumeroDeCuenta . " ";
                        }
                        else
                        {
                            if($DatosCuenta['valor_cuota_moderadora']>0)
                            {
                                if($this->Contabilizar_CuotaModeradora($NumeroDeCuenta,$DatosCuenta['valor_cuota_moderadora'])===false)
                                {
                                    if(empty($this->error))
                                    {
                                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                                        $this->mensajeDeError = "El metodo Contabilizar_CuotaModeradora() retorno false";
                                    }

                                    $CM_OK = false;
                                }
                            }

                            if($DatosCuenta['valor_cuota_paciente']>0)
                            {
                                if($this->Contabilizar_CuotaPaciente($NumeroDeCuenta,$DatosCuenta['valor_cuota_paciente'])===false)
                                {
                                    if(empty($this->error))
                                    {
                                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                                        $this->mensajeDeError = "El metodo Contabilizar_CuotaPaciente() retorno false";
                                    }

                                     $CM_OK = false;
                                }
                            }
                        }
                    }

                    if($CM_OK===false)
                    {
                        if($this->AddTmpCuenta($NumeroDeCuenta, $error=true)===false)
                        {
                            if(empty($this->error))
                            {
                                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                                $this->mensajeDeError = "El metodo AddTmpCuenta() retorno false";
                            }

                            return false;
                        }

                        $CUENTAS_OK = false;
                    }
                    else
                    {
                        if($this->AddTmpCuenta($NumeroDeCuenta, $error=false)===false)
                        {
                            if(empty($this->error))
                            {
                                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                                $this->mensajeDeError = "El metodo AddTmpCuenta() retorno false";
                            }

                            return false;
                        }
                    }
                }
                else
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = 'El metodo Contabilizar_Cuenta() no retorno un valor valido (true/false).';
                    return false;
                }
            }
        }

        // VALIDAR SI LAS CUENTAS QUEDARON CONTABILIZADAS PARA TERMINAR LA CONTABILIZACION DEL DOCUMENTO
        if($CUENTAS_OK)
        {
            if($this->DatosFactura['aprovechamientos_cuotas']>0)
            {
                if($this->Contabilizar_AprovechamientoCM()===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo Contabilizar_AprovechamientoCM() retorno false";
                    }

                    return false;
                }
            }

            //CONTABILIZAR LA FACTURA DE ACUERDO AL TIPO (CREDITO, CONTADO HOSPITALARIA, CONTADO CAJA RAPIDA)
            switch($this->DatosFactura['sw_clase_factura'])
            {
                case '0': //FACTURAS CONTADO
                {
                    switch($this->DatosFactura['tipo_factura_contado'])
                    {
                        case 'HOSPITALARIA':
                        {
                            if($this->Contabilizar_FV_Contado_Hospitalaria()===false)
                            {
                                if(empty($this->error))
                                {
                                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                                    $this->mensajeDeError = "El metodo Contabilizar_FV() retorno false";
                                }

                                return false;
                            }
                        }
                        break;

                        case 'CAJA RAPIDA':
                        {
                            if($this->Contabilizar_FV_Contado_Cajas_Rapidas()===false)
                            {
                                if(empty($this->error))
                                {
                                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                                    $this->mensajeDeError = "El metodo Contabilizar_FV() retorno false";
                                }

                                return false;
                            }
                        }
                        break;

                        default:
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "NO SE PUDO DETERMINAR SI LA FACTURA DE CONTADO ERA HOSPITALARIA O DE CAJA RAPIDA";
                        return false;
                    }
                }
                break;

                case '1': //FACTURAS CREDITO
                {
                    if($this->Contabilizar_FV_Credito()===false)
                    {
                        if(empty($this->error))
                        {
                            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                            $this->mensajeDeError = "El metodo Contabilizar_FV() retorno false";
                        }

                        return false;
                    }
                }
                break;

                default:
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "CLASE DE FACTURA NO RECONOCIDO [public.fac_facturas.sw_clase_factura = ".$this->DatosFactura['sw_clase_factura']."]";
                return false;
            }


            if($this->Contabilizar_FV()===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo Contabilizar_FV() retorno false";
                }

                return false;
            }


            if(($this->GenerarDocumentoContable()===false))
            {
                if($this->error == "CONTABILIZACION NO CUADRADA")
                {
                    $MENSAJE_DESCUADRE = $this->mensajeDeError;
                }
                else
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GenerarDocumentoContable() retorno false";
                    }

                    return false;
                }
            }

            //UNA VEZ TERMINO LA CONTABILIZACION BORRO LAS TABLAS TEMPORALES Y RETORNO EL RESULTADO.
            if($this->DelTemporalesFacturacion()===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo DelTemporalesFacturacion() retorno false";
                }

                return false;
            }

            //SI EL DOCUMENTO SE CONTABILIZO DESCUADRADO
            if(!empty($MENSAJE_DESCUADRE))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $MENSAJE_DESCUADRE;
                return false;
            }

            //SI EL DOCUMENTO SE CONTABILIZO OK.
            return $this->RetornarDocumentoContable();
        }

        //SI LA CONTABILIZACION DE LA FACTURA NO ESTA BIEN RETORNO UN RESUMEN DE ERRORES
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = $this->RetornarResumenErrores();

        return false;
    }



    /**
    * Metodo que valida los datos de contabilizacion generados y llena el vector de contabilizacion.
    *
    * @return boolean
    * @access private
    */
    function Contabilizar_FV()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //GENERAR AJUSTES POR UTILIDAD O PERDIDA DE PAQUETES.
        $sql = "
                SELECT numerodecuenta, paquete_id, ( (SUM(creditos_f)-SUM(debitos_f)) - (SUM(creditos_p)-SUM(debitos_p)) ) as diferencia

                FROM
                (
                    (
                        SELECT
                            numerodecuenta,
                            paquete_id,
                            SUM(credito) as creditos_f,
                            SUM(debito) as debitos_f,
                            0 as creditos_p,
                            0 as debitos_p

                        FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos_d

                        WHERE
                            empresa_id = '".$this->DatosFactura['empresa_id']."'
                            AND prefijo = '".$this->DatosFactura['prefijo']."'
                            AND numero = ".$this->DatosFactura['numero']."
                            AND paquete_id IS NOT NULL
                            AND paquete_sw = '1'
                        GROUP BY numerodecuenta, paquete_id
                    )
                    UNION
                    (
                        SELECT
                            numerodecuenta,
                            paquete_id,
                            0 as creditos_f,
                            0 as debitos_f,
                            SUM(credito) as creditos_p,
                            SUM(debito) as debitos_p

                        FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos_d

                        WHERE
                            empresa_id = '".$this->DatosFactura['empresa_id']."'
                            AND prefijo = '".$this->DatosFactura['prefijo']."'
                            AND numero = ".$this->DatosFactura['numero']."
                            AND paquete_id IS NOT NULL
                            AND paquete_sw = '0'
                        GROUP BY numerodecuenta, paquete_id
                    )
                ) AS A GROUP BY numerodecuenta, paquete_id;";


        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        while($fila_dif = $result->FetchRow())
        {
            if(abs($fila_dif[diferencia])>0)
            {
                $sql = "SELECT centro_de_costo_id,credito FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos_d
                        WHERE
                            empresa_id = '".$this->DatosFactura['empresa_id']."'
                            AND prefijo = '".$this->DatosFactura['prefijo']."'
                            AND numero = ".$this->DatosFactura['numero']."
                            AND paquete_id = ".$fila_dif['paquete_id']."
                            AND paquete_sw = '1'
                        ORDER BY credito DESC
                        LIMIT 1 OFFSET 0
                    ";

                $result2 = $dbconn->Execute($sql);

                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }

                if(!$result2->EOF)
                {
                    list($CC)=$result2->FetchRow();
                }

                $result2->Close();

                $this->ContabilizarDiferenciaPaquetes($fila_dif,$CC);
            }
        }

        $result->Close();

        $sql = "DELETE FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos_d
                WHERE
                    empresa_id = '".$this->DatosFactura['empresa_id']."'
                    AND prefijo = '".$this->DatosFactura['prefijo']."'
                    AND numero = ".$this->DatosFactura['numero']."
                    AND paquete_id IS NOT NULL
                    AND paquete_sw = '1'
               ";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }


        $sql = "SELECT SUM(credito) as creditos, SUM(debito) as debitos
                FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos_d
                WHERE empresa_id = '".$this->DatosFactura['empresa_id']."'
                      AND prefijo = '".$this->DatosFactura['prefijo']."'
                      AND numero = ".$this->DatosFactura['numero']." ;
               ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE GENERARON ASIENTOS CONTABLES DEL DOCUMENTO.";
            return false;
        }

        $V = $result->FetchRow();
        $result->Close();

        if(!($V['creditos']>0))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El valor total de los Creditos[".$V['creditos']."] y de los Debitos[".$V['debitos']."] no son valores correctos.";
            return false;
        }

        $sql = "(
                    SELECT
                        empresa_id,
                        cuenta,
                        documento_cruce_id,
                        tipo_id_tercero,
                        tercero_id,
                        detalle,
                        centro_de_costo_id,
                        base_rtf,
                        porcentaje_rtf,
                        documento_cxc,
                        documento_cxp,
                        0 AS debito,
                        SUM(credito) AS credito

                    FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos_d

                    WHERE empresa_id = '".$this->DatosFactura['empresa_id']."'
                        AND prefijo = '".$this->DatosFactura['prefijo']."'
                        AND numero = ".$this->DatosFactura['numero']."
                        AND debito = 0

                    GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12
                )
                UNION
                (
                    SELECT
                        empresa_id,
                        cuenta,
                        documento_cruce_id,
                        tipo_id_tercero,
                        tercero_id,
                        detalle,
                        centro_de_costo_id,
                        base_rtf,
                        porcentaje_rtf,
                        documento_cxc,
                        documento_cxp,
                        SUM(debito) AS debito,
                        0 AS credito


                    FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos_d

                    WHERE empresa_id = '".$this->DatosFactura['empresa_id']."'
                        AND prefijo = '".$this->DatosFactura['prefijo']."'
                        AND numero = ".$this->DatosFactura['numero']."
                        AND credito = 0

                    GROUP BY 1,2,3,4,5,6,7,8,9,10,11,13
                )

                ;
               ";

//echo "<pre>".print_r($sql,true)."</pre>";exit;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE GENERARON ASIENTOS CONTABLES DEL DOCUMENTO.";
            return false;
        }

        $this->DelMOV();

        while($fila=$result->FetchRow())
        {
            $this->AddMOV($fila);
        }
        $result->Close();

        return true;
    }


    /**
    * Metodo para la contabilizacion de utilidad/perdida de la venta de un paquete.
    *
    * @param array
    * @return numeric
    * @access private
    */
    function ContabilizarDiferenciaPaquetes($fila_dif,$CC)
    {

        //CONTABILIZACION DEL DEBITO
        $INFO_CTA = $this->GetParametizacionDoc('UTILIDAD_PERDIDA_PAQUETE','FV01');

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }
            return false;
        }

        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];

        if($fila_dif['diferencia']>0)
        {
            $Datos['naturaleza'] = 'C';
            $Datos['valor']      = $fila_dif['diferencia'];
            $Datos['detalle']    = "UTILIDAD POR PAQUETE [".$fila_dif['paquete_id']."] CUENTA No.".$fila_dif['numerodecuenta'];
        }
        else
        {
            $Datos['naturaleza'] = 'D';
            $Datos['valor']      = abs($fila_dif['diferencia']);
            $Datos['detalle']    = "PERDIDA POR PAQUETE [".$fila_dif['paquete_id']."] CUENTA No.".$fila_dif['numerodecuenta'];

        }
        if(!empty($CC))
        {
            $Datos['centro_de_costo_id'] = $CC;
        }
        else
        {
            $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        }

        $Datos['documento_cruce_id'] = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";


        $VectorMOV = $this->GenerarVectorMovimiento($Datos);

        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }
            return false;
        }

        //UTILIZO EL NUMERO DE CUENTA CON TRANSACCION (-1) PARA EL REGISTRO DE LA DIFERENCIA DE PAQUETES.
        if(!$this->AddTmpTransaccion_d($fila_dif['numerodecuenta'], -1, $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }

        return true;
    }



    /**
    * Metodo para contabilizar contabilizar la contrapartida de los cargos de una factura tipo Credito.
    *
    * @return boolean
    * @access private
    */
    function Contabilizar_FV_Credito()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //CONTABILIZACION DE LA RTF SI APLICA
        $INFO_RTF = $this->GetParametizacionDoc('RTF_JURIDICAS_D');
        $RTF = 0;

        if($INFO_RTF === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }

            return false;
        }

        //SOLO PARA PERSONAS JURIDICAS
        $sql = "SELECT COUNT(*)
                FROM public.tipo_id_terceros
                WHERE tipo_id_tercero='".$this->DatosFactura['tipo_id_tercero']."'
                        AND sw_personas_naturales = '0';
                ";

        $result = $dbconn->Execute($sql);

        list($NaturalezaCliente) = $result->FetchRow();
        $result->Close();

        if($NaturalezaCliente > 0)
        {
            $RTF = $this->Contabilizar_RTF($INFO_RTF);
            if($RTF===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo Contabilizar_RTF() retorno false";
                }

                return false;
            }
        }

        //CONTABILIZACION DEL TOTAL DEL DOCUMENTO.
        $TOTAL_DOC = $this->DatosFactura['total_factura'] - $RTF;

        //VALIDAR EL CAMPO plan_id
        if(empty($this->DatosFactura['plan_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El campo [fac_facturas.plan_id] de una factura hospitalaria no pude ser null.";
            return false;
        }

        //OBTENER EL TIPO DE CLIENTE DESDE LA CONTRATACION
        $sql="SELECT tipo_cliente FROM public.planes WHERE plan_id = ".$this->DatosFactura['plan_id'].";";

        $resultado = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El plan [".$this->DatosFactura['plan_id']."] no existe.";
            return false;
        }

        list($tipo_cliente)=$resultado->FetchRow();
        $resultado->Close();

        if(empty($tipo_cliente))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El plan [".$this->DatosFactura['plan_id']."] no tiene parametrizado el campo [tipo_cliente].";
            return false;
        }

        $INFO_CTA = $this->GetParametizacionDoc("TIPO_CLIENTE_".$tipo_cliente);

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }
            return false;
        }

        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];
        $Datos['naturaleza']         = $INFO_CTA['cuenta_naturaleza'];
        $Datos['valor']              = $TOTAL_DOC;
        $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        $Datos['documento_cruce_id'] = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "TOTAL FACTURA";

        $VectorMOV = $this->GenerarVectorMovimiento($Datos);

        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }

            return false;
        }

        //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION (0) PARA EL REGISTRO DEL TOTAL DEL DOCUMENTO
        if(!$this->AddTmpTransaccion_d(0, 0, $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }

        //CONTABILIZACION DE APROVECHAMIENTO O PERDIDAS SI LA FACTURA ES DE CAPITACION
        if($this->DatosFactura['tipo_factura'] == '3')
        {
            if($this->Contabilizar_Capitacion()===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo Contabilizar_Capitacion() retorno false";
                }

                return false;
            }
        }

        return true;
    }


    /**
    * Metodo para contabilizar la perdida 0 el aprovechamiento de una factura de capitacion
    *
    * @return boolean
    * @access private
    */
    function Contabilizar_Capitacion()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT SUM(credito) as creditos, SUM(debito) as debitos
                FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos_d
                WHERE empresa_id = '".$this->DatosFactura['empresa_id']."'
                      AND prefijo = '".$this->DatosFactura['prefijo']."'
                      AND numero = ".$this->DatosFactura['numero']." ;
               ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            return true;
        }

        $V = $result->FetchRow();
        $result->Close();

        if($V['creditos'] == $V['debitos'])
        {
            return true;
        }
        elseif($V['creditos']>$V['debitos'])
        {
            $INFO_CTA = $this->GetParametizacionDoc('CAPITACION_PERDIDA');
        }
        else
        {
            $INFO_CTA = $this->GetParametizacionDoc('CAPITACION_APROVECHAMIENTO');
        }

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }

            return false;
        }

        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];
        $Datos['naturaleza']         = $INFO_CTA['cuenta_naturaleza'];
        $Datos['valor']              = abs($V['creditos'] - $V['debitos']);
        $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        $Datos['documento_cruce_id'] = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "APROVECHAMIENTO/PERDIDA CAPITACION";

        $VectorMOV = $this->GenerarVectorMovimiento($Datos);

        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }

            return false;
        }

        if(!is_array($VectorMOV) || empty($VectorMOV))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El metodo GenerarVectorMovimiento() no retorno un vector de movimiento contable.";
            return false;
        }

        //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION (4) PARA EL REGISTRO DEL APROVECHAMIENTO Y/O PERDIDA DE LA CAPITACION
        if(!$this->AddTmpTransaccion_d(0, 4, $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }

        return true;
    }


    /**
    * Metodo para contabilizar la RTF de una factura credito.
    *
    * @param array $INFO_RTF (vector retornado por el metodo GetParametizacion())
    * @return numeric
    * @access private
    */
    function Contabilizar_RTF($INFO_RTF)
    {
        if(!is_numeric($INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE ENCONTRO EL PORCENTAJE_RTF EN LA TABLA [cg_conf.doc_parametros] PARA EL PARAMETRO [RTF_JURIDICAS_D] ESTE DEBE VENIR CON FORMATO [PORCENTAJE_RTF:VALOR] EN EL CAMPO [argumentos]";
            return false;
        }

        $valor = round(($this->DatosFactura['total_factura'] * $INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF'] / 100),0);

        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_RTF['cuenta'];
        $Datos['naturaleza']         = 'D';
        $Datos['valor']              = $valor;
        $Datos['centro_de_costo_id'] = $INFO_RTF['ARGUMENTOS']['CENTRO_DE_COSTO'];
        $Datos['documento_cruce_id'] = -1;
        $Datos['base_rtf']           = $this->DatosFactura['total_factura'];
        $Datos['porcentaje_rtf']     = $INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF'];
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "RTF";

        $VectorMOV = $this->GenerarVectorMovimiento($Datos);

        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }

            return false;
        }

        //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION (3) PARA EL REGISTRO DE LA RTF
        if(!$this->AddTmpTransaccion_d(0, 3, $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }

        return $valor;
    }

    /**
    * Metodo para contabilizar contabilizar la contrapartida de los cargos de una factura tipo Contado Hospitalaria.
    *
    * @return boolean
    * @access private
    */
    function Contabilizar_FV_Contado_Hospitalaria()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql="
                (
                    SELECT
                    d.empresa_id,
                    d.prefijo,
                    d.recibo_caja as numero,
                    'RC' AS tipo_pago,
                    d.total_abono AS total

                    FROM
                    fac_facturas AS a,
                    fac_facturas_cuentas AS b,
                    rc_detalle_hosp AS c,
                    recibos_caja AS d

                    WHERE a.empresa_id = '".$this->DatosFactura['empresa_id']."'
                    AND a.factura_fiscal = ".$this->DatosFactura['numero']."
                    AND a.prefijo = '".$this->DatosFactura['prefijo']."'
                    AND b.empresa_id = a.empresa_id
                    AND b.factura_fiscal = a.factura_fiscal
                    AND b.prefijo = a.prefijo
                    AND c.numerodecuenta = b.numerodecuenta
                    AND d.empresa_id =  c.empresa_id
                    AND d.centro_utilidad = c.centro_utilidad
                    AND d.recibo_caja = c.recibo_caja
                    AND d.prefijo = c.prefijo
                    AND d.estado = '0'
                )
                UNION
                (
                    SELECT
                    c.empresa_id,
                    c.prefijo,
                    c.recibo_caja as numero,
                    'DV' AS tipo_pago,
                    c.total_devolucion AS total

                    FROM
                    fac_facturas AS a,
                    fac_facturas_cuentas AS b,
                    rc_devoluciones AS c

                    WHERE a.empresa_id = '".$this->DatosFactura['empresa_id']."'
                    AND a.factura_fiscal = ".$this->DatosFactura['numero']."
                    AND a.prefijo = '".$this->DatosFactura['prefijo']."'
                    AND b.empresa_id = a.empresa_id
                    AND b.factura_fiscal = a.factura_fiscal
                    AND b.prefijo = a.prefijo
                    AND c.numerodecuenta = b.numerodecuenta
                    AND c.estado = '0'
                )
                UNION
                (
                    SELECT
                    c.empresa_id,
                    c.prefijo,
                    c.numero,
                    'PG' AS tipo_pago,
                    c.valor AS total

                    FROM
                    fac_facturas AS a,
                    fac_facturas_cuentas AS b,
                    pagares AS c

                    WHERE a.empresa_id = '".$this->DatosFactura['empresa_id']."'
                    AND a.factura_fiscal = ".$this->DatosFactura['numero']."
                    AND a.prefijo = '".$this->DatosFactura['prefijo']."'
                    AND b.empresa_id = a.empresa_id
                    AND b.factura_fiscal = a.factura_fiscal
                    AND b.prefijo = a.prefijo
                    AND c.numerodecuenta = b.numerodecuenta
                    AND c.sw_estado = '1'
                )
        ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE ENCONTRARON RECIBOS DE CAJA NI PAGARES PARA EL RECAUDO DE LA FACTURA CONTADO HOSPITALARIA.";
            return false;
        }

        $PG = 0;
        $RC = 0;
        $DV = 0;
        $DOCS = array();

        while($fila = $result->FetchRow())
        {
            switch($fila['tipo_pago'])
            {
                case 'PG': $PG += $fila['total']; break;
                case 'RC': $RC += $fila['total']; break;
                case 'DV': $DV += $fila['total'];
            }
            $DOCS[$fila['tipo_pago']][] = $fila;
        }
        $result->Close();

//         if($valor!= ($PG+$RC-$DV))
//         {
//             ECHO "<PRE>$sql</PRE>";EXIT;
//             $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
//             $this->mensajeDeError = "LOS DOCUMENTOS CRUCE (RECIBOS,PAGARES,DEVOLUCIONES) DE LA FACTURA CONTADO HOSPITALARIA NO CUADRAN CON EL TOTAL DE LA MISMA (TOTAL[".$this->DatosFactura['total_factura']."] != PG[$PG] + RC[$RC] - DV[$DV]).";
//             return false;
//         }

        $I = 100;//PARA LA LLAVE TRANSACCION LAS MAYORES DE 100 SERAN DOCUMENTOS CRUCE.

        //CONTABILIZACION DEL CRUCE CON PAGARES
        if($PG>0)
        {
            $INFO_CTA = $this->GetParametizacionDoc($parametro='PAGARE_C','PG01');

            if($INFO_CTA === false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                }
                return false;
            }


            foreach($DOCS['PG'] as $K=>$D)
            {
                $DOC_CRUCE = $this->GetDatosDocumentoContabilizado($D['empresa_id'],$D['prefijo'],$D['numero']);

                if($DOC_CRUCE === false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GetDatosDocumentoContabilizado() retorno false";
                    }

                    return false;
                }
                else
                {
                    if(empty($DOC_CRUCE['documento_contable_id']))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "DOCUMENTO CRUCE SIN CONTABILIZAR : [".$D['prefijo']."-".$D['numero']."] DE LA EMPRESA [".$D['empresa_id']."].";
                        return false;
                    }
                }

                //DATOS DEL MOVIMIENTO
                $Datos['cuenta']             = $INFO_CTA['cuenta'];
                $Datos['naturaleza']         = 'D';
                $Datos['valor']              = $D['total'];
                $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
                $Datos['documento_cruce_id'] = $DOC_CRUCE['documento_contable_id'];
                $Datos['base_rtf']           = 0;
                $Datos['porcentaje_rtf']     = 0;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "CRUCE CON PAGARE [".$D['prefijo']." ".$D['numero']."]";

                $VectorMOV = $this->GenerarVectorMovimiento($Datos);

                if($VectorMOV===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                    }

                    return false;
                }

                if(!is_array($VectorMOV) || empty($VectorMOV))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GenerarVectorMovimiento() no retorno un vector de movimiento contable.";
                    return false;
                }

                //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION ($I MAYOR DE 100) PARA EL REGISTRO DE LOS DOCUMENTOS CRUCE
                if(!$this->AddTmpTransaccion_d(0, $I++, $VectorMOV))
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
                    }

                    return false;
                }

            }//END FOREACH
        }//END PAGARES

        //CONTABILIZACION DEL CRUCE CON RECIBOS DE CAJA
        if($RC>0)
        {
            $INFO_CTA = $this->GetParametizacionDoc($parametro='EFECTIVO_C','RC01');

            if($INFO_CTA === false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                }
                return false;
            }

            foreach($DOCS['RC'] as $K=>$D)
            {
                $DOC_CRUCE = $this->GetDatosDocumentoContabilizado($D['empresa_id'],$D['prefijo'],$D['numero']);

                if($DOC_CRUCE === false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GetDatosDocumentoContabilizado() retorno false";
                    }

                    return false;
                }
                else
                {
                    if(empty($DOC_CRUCE['documento_contable_id']))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "DOCUMENTO CRUCE SIN CONTABILIZAR : [".$D['prefijo']."-".$D['numero']."] DE LA EMPRESA [".$D['empresa_id']."].";
                        return false;
                    }
                }

                //DATOS DEL MOVIMIENTO
                $Datos['cuenta']             = $INFO_CTA['cuenta'];
                $Datos['naturaleza']         = 'D';
                $Datos['valor']              = $D['total'];
                $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
                $Datos['documento_cruce_id'] = $DOC_CRUCE['documento_contable_id'];
                $Datos['base_rtf']           = 0;
                $Datos['porcentaje_rtf']     = 0;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "CRUCE CON RECIBO DE CAJA [".$D['prefijo']." ".$D['numero']."]";
//echo "<pre>".print_r($Datos,true)."</pre>";
                $VectorMOV = $this->GenerarVectorMovimiento($Datos);

                if($VectorMOV===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                    }

                    return false;
                }

                if(!is_array($VectorMOV) || empty($VectorMOV))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GenerarVectorMovimiento() no retorno un vector de movimiento contable.";
                    return false;
                }

                //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION ($I MAYOR DE 100) PARA EL REGISTRO DE LOS DOCUMENTOS CRUCE
                if(!$this->AddTmpTransaccion_d(0, $I++, $VectorMOV))
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
                    }

                    return false;
                }

            }//END FOREACH
        }//END RECIBOS

        //CONTABILIZACION DEL CRUCE CON DEVOLUCIONES
        if($DV>0)
        {
            $INFO_CTA = $this->GetParametizacionDoc($parametro='EFECTIVO_C','RC01');

            if($INFO_CTA === false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                }
                return false;
            }


            foreach($DOCS['DV'] as $K=>$D)
            {
                $DOC_CRUCE = $this->GetDatosDocumentoContabilizado($D['empresa_id'],$D['prefijo'],$D['numero']);

                if($DOC_CRUCE === false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GetDatosDocumentoContabilizado() retorno false";
                    }

                    return false;
                }
                else
                {
                    if(empty($DOC_CRUCE['documento_contable_id']))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "DOCUMENTO CRUCE SIN CONTABILIZAR : [".$D['prefijo']."-".$D['numero']."] DE LA EMPRESA [".$D['empresa_id']."].";
                        return false;
                    }
                }

                //DATOS DEL MOVIMIENTO
                $Datos['cuenta']             = $INFO_CTA['cuenta'];
                $Datos['naturaleza']         = 'C';
                $Datos['valor']              = $D['total'];
                $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
                $Datos['documento_cruce_id'] = $DOC_CRUCE['documento_contable_id'];
                $Datos['base_rtf']           = 0;
                $Datos['porcentaje_rtf']     = 0;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "CRUCE CON DEVOLUCIONES DE CAJA [".$D['prefijo']." ".$D['numero']."]";

                $VectorMOV = $this->GenerarVectorMovimiento($Datos);

                if($VectorMOV===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                    }

                    return false;
                }

                if(!is_array($VectorMOV) || empty($VectorMOV))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GenerarVectorMovimiento() no retorno un vector de movimiento contable.";
                    return false;
                }

                //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION ($I MAYOR DE 100) PARA EL REGISTRO DE LOS DOCUMENTOS CRUCE
                if(!$this->AddTmpTransaccion_d(0, $I++, $VectorMOV))
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
                    }

                    return false;
                }

            }//END FOREACH
        }//END DEVOLUCIONES

        return true;
    }





    /**
    * Metodo para contabilizar los aprovechamientos por mayor valor de Cuotas moderadoras.
    *
    * @return boolean
    * @access private
    */
    function Contabilizar_AprovechamientoCM()
    {
        //SOLO APLICA EN LAS FACTURAS QUE PAGA EL PACIENTE
        if($this->DatosFactura['tipo_factura']!='0')
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "LA FACTURA EN LA QUE SE ESTA CARGANDO EL APROVECHAMIENTO NO ES DEL PACIENTE, EL CAMPO [fac_facturas.tipo_factura] DEBE SER '0'";
            return false;
        }

        $INFO_CTA = $this->GetParametizacionDoc('APROVECHAMIENTO_CUOTAS_MODERADORAS_C');

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }

            return false;
        }

        $CUENTAS = str_replace(" ", ",", trim($this->DatosFactura['aprovechamientos_cuotas_cuentas']));

        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];
        $Datos['naturaleza']         = "C";
        $Datos['valor']              = $this->DatosFactura['aprovechamientos_cuotas'];
        $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        $Datos['documento_cruce_id'] = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "APROV. C.P. CUENTAS " . $CUENTAS;

        $VectorMOV = $this->GenerarVectorMovimiento($Datos);

        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }

            return false;
        }

        //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION (2) PARA EL REGISTRO DE APROVECHAMIENTO POR CM
        if(!$this->AddTmpTransaccion_d(0, 2, $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }

        return true;
    }


    /**
    * Metodo para contabilizar contabilizar la Cuota Paciente (copagos) de una factura.
    *
    * @return boolean
    * @access private
    */
    function Contabilizar_CuotaPaciente($NumeroDeCuenta,$valor_cuota_paciente)
    {
        $INFO_CTA = $this->GetParametizacionDoc('COPAGO');

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }

            return false;
        }

        //DE MANERA ESTATICA ASIGNO LA NATURALEZA [C]:FACTURA PACIENTE [D]:FACTURA CLIENTE (ASEGURADORA)
        if($this->DatosFactura['tipo_factura']==='0')
        {
            $NATURALEZA = 'C';
        }
        else
        {
            $NATURALEZA = 'D';
        }

        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];
        $Datos['naturaleza']         = $NATURALEZA;
        $Datos['valor']              = $valor_cuota_paciente;
        $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        $Datos['documento_cruce_id'] = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "CUOTA PACIENTE";

        $VectorMOV = $this->GenerarVectorMovimiento($Datos);

        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }

            return false;
        }


        //UTILIZO LA TRANSACCION (-1) PARA EL REGISTRO DE LA CUOTA MODERADORA DE CADA CUENTA
        if(!$this->AddTmpTransaccion_d($NumeroDeCuenta, -1, $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }


        return true;
    }


    /**
    * Metodo para contabilizar contabilizar la Cuota moderadora de una factura.
    *
    * @return boolean
    * @access private
    */
    function Contabilizar_CuotaModeradora($NumeroDeCuenta,$valor_cuota_moderadora)
    {
        $INFO_CTA = $this->GetParametizacionDoc('CUOTA_MODERADORA');

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }

            return false;
        }

        //DE MANERA ESTATICA ASIGNO LA NATURALEZA [C]:FACTURA PACIENTE [D]:FACTURA CLIENTE (ASEGURADORA)
        if($this->DatosFactura['tipo_factura']==='0')
        {
            $NATURALEZA = 'C';
        }
        else
        {
            $NATURALEZA = 'D';
        }

        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];
        $Datos['naturaleza']         = $NATURALEZA;
        $Datos['valor']              = $valor_cuota_moderadora;
        $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        $Datos['documento_cruce_id'] = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "CUOTA MODERADORA";

        $VectorMOV = $this->GenerarVectorMovimiento($Datos);

        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }

            return false;
        }

        //UTILIZO LA TRANSACCION (0) PARA EL REGISTRO DE LA CUOTA MODERADORA DE CADA CUENTA
        if(!$this->AddTmpTransaccion_d($NumeroDeCuenta, 0, $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }

        return true;
    }


    /**
    * Metodo para contabilizar una cuenta de una factura
    *
    * @param integer $cuenta
    * @param boolean $reprocesar
    * @return boolean
    * @access private
    */
    function Contabilizar_Cuenta($NumeroDeCuenta)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT
                    b.sw_estado as sw_estado_contabilizacion,
                    a.transaccion,
                    a.numerodecuenta,
                    a.tarifario_id,
                    a.cargo,
                    a.cargo_cups,
                    a.valor_cargo,
                    a.valor_nocubierto,
                    a.valor_cubierto,
                    a.valor_descuento_paciente,
                    d.valor_descuento_paciente as valor_descuento_paciente_cta,
                    a.departamento,
                    a.facturado,
                    a.consecutivo,
                    a.paquete_codigo_id,
                    a.sw_paquete_facturado,
                    d.plan_id,
                    d.valor_total_empresa,
                    d.empresa_id,
                    c.grupo_tarifario_id,
                    c.subgrupo_tarifario_id,
                    e.sw_tipo_plan,
                    f.tipo_tercero_id,
                    f.tercero_id,
                    f.valor as valor_honorario,
                    f.porcentaje_honorario,
                    g.servicio,
                    g.unidad_funcional,
                    g.centro_utilidad

                FROM
                    cuentas_detalle a
                    LEFT JOIN cuentas_detalle_honorarios f ON (f.transaccion = a.transaccion)
                    LEFT JOIN cg_conf.tmp_contabilizacion_facturas_cuentas_cargos b
                    ON  (
                            b.empresa_id = '".$this->DatosDocumento['empresa_id']."'
                            AND b.prefijo = '".$this->DatosDocumento['prefijo']."'
                            AND b.numero = ".$this->DatosDocumento['numero']."
                            AND b.numerodecuenta = a.numerodecuenta
                            AND b.transaccion = a.transaccion
                        ),
                   tarifarios_detalle c,
                   cuentas d,
                   planes e,
                   departamentos g

                WHERE
                    a.numerodecuenta = $NumeroDeCuenta
                    AND c.tarifario_id = a.tarifario_id
                    AND c.cargo = a.cargo
                    AND d.numerodecuenta = a.numerodecuenta
                    AND e.plan_id = d.plan_id
                    AND g.departamento = a.departamento

                ";
//echo $NumeroDeCuenta;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "LA CUENTA [$NumeroDeCuenta] ASOCIADA A LA FACTURA (empresa_id=$empresa_id, prefijo=$prefijo, numero=$numero) NO TIENE REGISTROS";
            return false;
        }

        while($Fila=$result->FetchRow())
        {
            $Transacciones[$Fila['transaccion']]=$Fila;
        }
        $result->Close();

        $TRANSACCION_OK = true;
        $COUNT_ERRORES = 0;

        //CONTABILIZAR CADA UNA DE LAS TRANSACCIONES DE LA CUENTA.
        foreach ($Transacciones as $Transaccion=>$DatosTransaccion)
        {
            if(!$DatosTransaccion['sw_estado_contabilizacion'])
            {
                switch($this->DatosFactura['tipo_factura'])
                {
                    case '0': //FACTURA DEL PACIENTE

//                         if(($DatosTransaccion['tarifario_id']=='SYS' && $DatosTransaccion['valor_nocubierto']==0 && $DatosTransaccion['valor_cubierto']==0))
//                         {
//                             $DatosTransaccion['valor_cargo'] = abs($DatosTransaccion['valor_cargo']);
//                         }

                        //Si no se genera factura del cliente para que contabilize todo en la del paciente.
                        if($DatosTransaccion['valor_total_empresa'] == 0 )
                        {
                           $valor = $DatosTransaccion['valor_cargo'];
                        }
                        elseif($DatosTransaccion['tarifario_id']=='SYS' && $DatosTransaccion['valor_nocubierto']==0 && $DatosTransaccion['valor_cubierto']==0)
                        {
                            $valor = $DatosTransaccion['valor_cargo'];
                        }
                        else
                        {
                           $valor = $DatosTransaccion['valor_nocubierto'];
                        }
                        break;

                    case '1': //FACTURA DE LA ENTIDAD
                        $valor = $DatosTransaccion['valor_cubierto'];
                        break;

                    case '2': //FACTURA DE PACIENTE PARTICULAR
                        $valor = $DatosTransaccion['valor_cargo'];
                        break;

                    case '3': //FACTURA DE CAPITACION AGRUPADA
                        $valor = $DatosTransaccion['valor_cubierto'];
                        break;

                    case '4': //FACTURA DE LA ENTIDAD AGRUPADA
                        $valor = $DatosTransaccion['valor_cubierto'];
                        break;

                    default:

                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El tipo de factura ".$this->DatosFactura['tipo_factura']." no esta definido en la interfase de contabilizacion.";
                        return false;
                }

                $DatosTransaccion['valor'] = $valor;
//echo "<pre>".print_r($DatosTransaccion,true)."</pre>";
                //CONDICIONES PARA CONTABILIZAR EL CARGO
                if(($DatosTransaccion['facturado']=='1') && ($valor>0 || ($valor<0 && $DatosTransaccion['tarifario_id']=='SYS')))
                {
                    if($this->Contabilizar_Cargo($DatosTransaccion) != true)
                    {
                        if(empty($this->error))
                        {
                            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                            $this->mensajeDeError = "El metodo Contabilizar_Cuenta() retorno false";
                        }


                        if($this->AddTmpTransaccion($NumeroDeCuenta, $Transaccion, $error=true)===false)
                        {
                            if(empty($this->error))
                            {
                                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                                $this->mensajeDeError = "El metodo AddTmpTransaccion() retorno false";
                            }

                            return false;
                        }
                        $COUNT_ERRORES++;
                        $TRANSACCION_OK = false;
                    }
                    else
                    {
                        if($this->AddTmpTransaccion($NumeroDeCuenta, $Transaccion, $error=false)===false)
                        {
                            if(empty($this->error))
                            {
                                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                                $this->mensajeDeError = "El metodo AddTmpTransaccion() retorno false";
                            }

                            return false;
                        }
                    }
                }
                elseif($DatosTransaccion['facturado']=='2' && $DatosTransaccion['valor_honorario']>0)
                {
                    //AQUI DEBO LIQUIDAR EL HONORARIO DE PAQUETES TIPO ISS2001
                }
                else
                {
                    if($this->AddTmpTransaccion($NumeroDeCuenta, $Transaccion, $error=false)===false)
                    {
                        if(empty($this->error))
                        {
                            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                            $this->mensajeDeError = "El metodo AddTmpTransaccion() retorno false";
                        }

                        return false;
                    }
                }
            }
        }

        if($TRANSACCION_OK)
        {
            return true;
        }

        //SI NO CONTABILIZARON CORRECTAMENTE TODAS LAS TRANSACIONES DE LA CUENTA
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = "($COUNT_ERRORES)Transacciones sin contabilizar de (".count($Transacciones).").";
        return false;

    }//fin de Contabilizar_Cargo()


    /**
    * Metodo para contabilizar un cargo de una cuenta de una factura
    *
    * @param integer $cuenta
    * @param boolean $reprocesar
    * @return boolean
    * @access private
    */
    function Contabilizar_Cargo($DatosTransaccion)
    {
        //SI EL CARGO ES UN PRODUCTO DE INVENTARIO
        if($DatosTransaccion['consecutivo'])
        {
            if($this->Contabilizar_Cargo_inventario($DatosTransaccion)===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo Contabilizar_Cargo_inventario() retorno false";
                }

                return false;
            }
            return true;
        }
        else
        {
            if($this->Contabilizar_Cargo_tarifarios($DatosTransaccion)===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo Contabilizar_Cargo_tarifarios() retorno false";
                }

                return false;
            }
            return true;
        }
        return false;
    }


    /**
    * Metodo para contabilizar un cargo de un tarifario
    *
    * @param array $DatosTransaccion
    * @return boolean
    * @access private
    */
    function Contabilizar_Cargo_tarifarios($DatosTransaccion)
    {
        static $InfoContableCargos;

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //OBTENER EL CENTRO DE COSTO DEL DEPARTAMENTO DE LA TRANSACCION
        $CC = $this->GetCentroDeCostoDepartamento($this->DatosDocumento['empresa_id'], $DatosTransaccion['departamento']);
        if($CC===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetCentroDeCostoDepartamento() retorno false";
            }

            return false;
        }

        if(empty($InfoContableCargos[$this->DatosDocumento['empresa_id']][$CC['centro_de_costo_id']][$DatosTransaccion['grupo_tarifario_id']][$DatosTransaccion['subgrupo_tarifario_id']][$DatosTransaccion['tarifario_id']][$DatosTransaccion['cargo']]))
        {
            //LA BUSQUEDA DE LA PARAMETRIZACION SE HACE EN EL SIGUIENTE ORDEN:
            //1.POR CARGO Y CENTO DE COSTOS TABLA : cg_conf.doc_fv01_cargos_por_cc
            //2.POR CARGO TABLA : cg_conf.doc_fv01_cargos
            //3.POR GRUPO/SUBGRUPO TARIFARIO Y CENTRO DE COSTOS TABLA : cg_conf.doc_fv01_grupos_cargos_por_cc
            //4.POR GRUPO/SUBGRUPO TARIFARIO TABLA : cg_conf.doc_fv01_grupos_cargos

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

            //BUSQUEDA 1 : POR CARGO Y CENTO DE COSTOS TABLA cg_conf.doc_fv01_cargos_por_cc.
            $sql = "SELECT * FROM cg_conf.doc_fv01_cargos_por_cc
                    WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                          AND tarifario_id = '".$DatosTransaccion['tarifario_id']."'
                          AND cargo = '".$DatosTransaccion['cargo']."'
                          AND centro_de_costo_id = '".$CC['centro_de_costo_id']."';
                    ";

            $result = $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
            if($result->EOF)
            {
                //BUSQUEDA 2 : POR CARGO TABLA : cg_conf.doc_fv01_cargos
                $sql = "SELECT * FROM cg_conf.doc_fv01_cargos
                        WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                            AND tarifario_id = '".$DatosTransaccion['tarifario_id']."'
                            AND cargo = '".$DatosTransaccion['cargo']."';
                        ";

                $result = $dbconn->Execute($sql);

                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }
                if($result->EOF)
                {
                    //BUSQUEDA 3 : POR GRUPO/SUBGRUPO TARIFARIO Y CENTRO DE COSTOS TABLA : cg_conf.doc_fv01_grupos_cargos_por_cc
                    $sql = "SELECT * FROM cg_conf.doc_fv01_grupos_cargos_por_cc
                            WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                                AND grupo_tarifario_id = '".$DatosTransaccion['grupo_tarifario_id']."'
                                AND subgrupo_tarifario_id = '".$DatosTransaccion['subgrupo_tarifario_id']."'
                                AND centro_de_costo_id = '".$CC['centro_de_costo_id']."';
                            ";

                    $result = $dbconn->Execute($sql);

                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = $dbconn->ErrorMsg();
                        return false;
                    }
                    if($result->EOF)
                    {
                        //BUSQUEDA 4 : POR GRUPO/SUBGRUPO TARIFARIO TABLA : cg_conf.doc_fv01_grupos_cargos
                        $sql = "SELECT * FROM cg_conf.doc_fv01_grupos_cargos
                                WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                                    AND grupo_tarifario_id = '".$DatosTransaccion['grupo_tarifario_id']."'
                                    AND subgrupo_tarifario_id = '".$DatosTransaccion['subgrupo_tarifario_id']."';
                                ";

                        $result = $dbconn->Execute($sql);

                        if($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                            $this->mensajeDeError = $dbconn->ErrorMsg();
                            return false;
                        }
                        if($result->EOF)
                        {
                            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                            $this->mensajeDeError = "EL cargo [".$DatosTransaccion['cargo']."] del tarifario [".$DatosTransaccion['tarifario_id']."] no esta parametrizado contablemente en alguna de las siguientes tablas : [doc_fv01_cargos_por_cc, doc_fv01_cargos, doc_fv01_grupos_cargos_por_cc, doc_fv01_grupos_cargos]";
                            return false;
                        }
                    }
                }
            }
            //PARA EL RESULT QUE TENGA DATOS
            $InfoContableCargos[$this->DatosDocumento['empresa_id']][$CC['centro_de_costo_id']][$DatosTransaccion['grupo_tarifario_id']][$DatosTransaccion['subgrupo_tarifario_id']][$DatosTransaccion['tarifario_id']][$DatosTransaccion['cargo']] = $result->FetchRow();
            $result->Close();
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        }

        $INF_CARGO = &$InfoContableCargos[$this->DatosDocumento['empresa_id']][$CC['centro_de_costo_id']][$DatosTransaccion['grupo_tarifario_id']][$DatosTransaccion['subgrupo_tarifario_id']][$DatosTransaccion['tarifario_id']][$DatosTransaccion['cargo']];

        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INF_CARGO['cuenta'];
        $Datos['naturaleza']         = 'C';//$INF_CARGO['cuenta_naturaleza'];
        $Datos['valor']              = $DatosTransaccion['valor'];
        $Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
        $Datos['documento_cruce_id'] = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "";

        //PARA LA CONTABILIZACION DE PAQUETES
        $Datos['paquete_codigo_id']    = $DatosTransaccion['paquete_codigo_id'];
        $Datos['sw_paquete_facturado'] = $DatosTransaccion['sw_paquete_facturado'];

        $VectorMOV = $this->GenerarVectorMovimiento($Datos);

        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }

            return false;
        }

        //CUENTA Y TRANSACCION PARA CARGOS
        if(!$this->AddTmpTransaccion_d($DatosTransaccion['numerodecuenta'], $DatosTransaccion['transaccion'], $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }

        //CONTABILIZAR HONORARIOS MEDICOS DEL CARGO
        if($DatosTransaccion['porcentaje_honorario']>0)
        {
            if($this->Contabilizar_HM($DatosTransaccion)===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo Contabilizar_HM() retorno false";
                }

                return false;
            }
        }

        return true;
    }


    /**
    * Metodo para contabilizar un cargo de inventario
    *
    * @param array $DatosTransaccion
    * @return boolean
    * @access private
    */
    function Contabilizar_Cargo_inventario($DatosTransaccion)
    {
        static $InfoContableProductos;

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT a.codigo_producto, b.grupo_id, b.clase_id, b.subclase_id
                FROM bodegas_documentos_d a, inventarios_productos b
                WHERE consecutivo = $DatosTransaccion[consecutivo]
                AND b.codigo_producto = a.codigo_producto ;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE ENCONTRO EL PRODUCTO EN EL DOCUMENTO DE BODEGA.";
            return false;
        }

        $DatosProducto = $result->FetchRow();
        $result->Close();

        if(empty($InfoContableProductos[$this->DatosDocumento['empresa_id']][$DatosProducto['grupo_id']][$DatosProducto['clase_id']][$DatosProducto['subclase_id']]))
        {
            $sql = "SELECT * FROM cg_conf.doc_fv01_inv_productos
                    WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                            AND codigo_producto = '".$DatosProducto['codigo_producto']."';
                    ";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            if(!$result->EOF)
            {
                $InfoContableProductos[$this->DatosDocumento['empresa_id']][$DatosProducto['grupo_id']][$DatosProducto['clase_id']][$DatosProducto['subclase_id']] = $result->FetchRow();
                $result->Close();
            }
            else
            {
                $sql = "SELECT * FROM cg_conf.doc_fv01_inv_grupos_productos
                        WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                            AND grupo_id = '".$DatosProducto['grupo_id']."'
                            AND clase_id = '".$DatosProducto['clase_id']."'
                            AND subclase_id = '".$DatosProducto['subclase_id']."';
                    ";

                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($sql);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
                }
                if($result->EOF)
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "LA CLASIFICACION DE INVENTARIOS [empresa_id = ".$this->DatosDocumento['empresa_id']."][grupo_id = ".$DatosProducto['grupo_id']."][clase_id = ".$DatosProducto['clase_id']."][subclase_id = ".$DatosProducto['subclase_id']."] NO SE ENCUENTRA PARAMETRIZADA CONTABLEMENTE EN LA TABLA [cg_conf.doc_fv01_inv].";
                    return false;
                }

                $InfoContableProductos[$this->DatosDocumento['empresa_id']][$DatosProducto['grupo_id']][$DatosProducto['clase_id']][$DatosProducto['subclase_id']] = $result->FetchRow();
                $result->Close();
            }
        }

        $INF_PRODUCTO = &$InfoContableProductos[$this->DatosDocumento['empresa_id']][$DatosProducto['grupo_id']][$DatosProducto['clase_id']][$DatosProducto['subclase_id']];

        //INFORMACION PARA GENERAR EL VECTOR DE MOVIMIENTO
        $CC = $this->GetCentroDeCostoDepartamento($this->DatosDocumento['empresa_id'], $DatosTransaccion['departamento']);
        if($CC===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetCentroDeCostoDepartamento() retorno false";
            }

            return false;
        }

        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INF_PRODUCTO['cuenta'];
        $Datos['naturaleza']         = 'C';//$INF_PRODUCTO['cuenta_naturaleza'];
        $Datos['valor']              = $DatosTransaccion['valor'];
        $Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
        $Datos['documento_cruce_id'] = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "";

        //PARA LA CONTABILIZACION DE PAQUETES
        $Datos['paquete_codigo_id']    = $DatosTransaccion['paquete_codigo_id'];
        $Datos['sw_paquete_facturado'] = $DatosTransaccion['sw_paquete_facturado'];

        $VectorMOV = $this->GenerarVectorMovimiento($Datos);

        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }

            return false;
        }

        //CUENTA Y TRANSACCION PARA CARGOS
        if(!$this->AddTmpTransaccion_d($DatosTransaccion['numerodecuenta'], $DatosTransaccion['transaccion'], $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }

        return true;
    }


    /**
    * Metodo para insertar en la tabla temporal el estado de la contabilizacion de una cuenta.
    *
    * @param integer $NumeroDeCuenta
    * @param boolean $error TRUE = Contabilizacion OK,  FALSE = error.
    * @return boolean
    * @access private
    */
    function AddTmpCuenta($NumeroDeCuenta, $error=false)
    {
        list($dbconn) = GetDBconn();

        if($error)
        {
            $error_titulo = $this->Err();
            $error_detalle = $this->ErrMsg();
            $sw_estado = '0';

            //LIMPIAR LAS VARIABLES DE ERROR
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = '';
        }
        else
        {
            $error_titulo = '';
            $error_detalle = '';
            $sw_estado = '1';
        }

        $sql = "DELETE FROM cg_conf.tmp_contabilizacion_facturas_cuentas
                WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                     AND prefijo = '".$this->DatosDocumento['prefijo']."'
                     AND numero = ".$this->DatosDocumento['numero']."
                     AND numerodecuenta = $NumeroDeCuenta;

                INSERT INTO cg_conf.tmp_contabilizacion_facturas_cuentas
                (
                    empresa_id,
                    prefijo,
                    numero,
                    numerodecuenta,
                    error_titulo,
                    error_detalle,
                    sw_estado
                )
                VALUES
                (
                    '".$this->DatosDocumento['empresa_id']."',
                    '".$this->DatosDocumento['prefijo']."',
                    ".$this->DatosDocumento['numero'].",
                    $NumeroDeCuenta,
                    '$error_titulo',
                    '$error_detalle',
                    '$sw_estado'
                );
        ";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError =$sql. $dbconn->ErrorMsg();
            return false;
        }

        return true;

    }


    /**
    * Metodo para insertar en la tabla temporal un error en la contabilizacion de una transaccion.
    *
    * @param integer $NumeroDeCuenta
    * @param integer $transaccion
    * @param boolean $error TRUE = Contabilizacion OK,  FALSE = error.
    * @return boolean
    * @access private
    */
    function AddTmpTransaccion($NumeroDeCuenta, $transaccion, $error=false)
    {
        list($dbconn) = GetDBconn();

        if($error)
        {
            $error_titulo = PrepararCadenaParaSQL($this->Err());
            $error_detalle = PrepararCadenaParaSQL($this->ErrMsg());
            $sw_estado = '0';

            //LIMPIAR LAS VARIABLES DE ERROR
            $this->error = '';
            $this->mensajeDeError = '';
        }
        else
        {
            $error_titulo = '';
            $error_detalle = '';
            $sw_estado = '1';
        }

        $sql = "DELETE FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos
                WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                     AND prefijo = '".$this->DatosDocumento['prefijo']."'
                     AND numero = ".$this->DatosDocumento['numero']."
                     AND numerodecuenta = $NumeroDeCuenta
                     AND transaccion = $transaccion;

                INSERT INTO cg_conf.tmp_contabilizacion_facturas_cuentas_cargos
                (
                    empresa_id,
                    prefijo,
                    numero,
                    numerodecuenta,
                    transaccion,
                    error_titulo,
                    error_detalle,
                    sw_estado
                )
                VALUES
                (
                    '".$this->DatosDocumento['empresa_id']."',
                    '".$this->DatosDocumento['prefijo']."',
                    ".$this->DatosDocumento['numero'].",
                    $NumeroDeCuenta,
                    $transaccion,
                    '$error_titulo',
                    '$error_detalle',
                    '$sw_estado'
                );
        ";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        return true;
    }


    /**
    * Metodo para insertar en la tabla temporal un error en la contabilizacion de una transaccion.
    *
    * @param integer $NumeroDeCuenta
    * @param integer $transaccion
    * @param boolean $error TRUE = Contabilizacion OK,  FALSE = error.
    * @return boolean
    * @access private
    */
    function AddTmpTransaccion_d($NumeroDeCuenta, $transaccion, $Datos)
    {
        list($dbconn) = GetDBconn();

        if(empty($Datos['documento_cruce_id']))
        {
            $documento_cruce_id = "NULL";
        }
        else
        {
            $documento_cruce_id = $Datos['documento_cruce_id'];
        }

        list($dbconn) = GetDBconn();

        if(empty($Datos['documento_cxc']))
        {
            $documento_cxc = "NULL";
        }
        else
        {
            $documento_cxc = $Datos['documento_cxc'];
        }


        list($dbconn) = GetDBconn();

        if(empty($Datos['documento_cxp']))
        {
            $documento_cxp = "NULL";
        }
        else
        {
            $documento_cxp = $Datos['documento_cxp'];
        }


        if(empty($Datos['tipo_id_tercero']))
        {
            $tipo_id_tercero = "NULL";
        }
        else
        {
            $tipo_id_tercero = "'".$Datos['tipo_id_tercero']."'";
        }

        if(empty($Datos['tercero_id']))
        {
            $tercero_id = "NULL";
        }
        else
        {
            $tercero_id = "'".$Datos['tercero_id']."'";
        }

        if(empty($Datos['centro_de_costo_id']))
        {
            $centro_de_costo_id = "NULL";
        }
        else
        {
            $centro_de_costo_id = "'".$Datos['centro_de_costo_id']."'";
        }

        if(empty($Datos['paquete_codigo_id']))
        {
            $paquete_id = "NULL";
            $paquete_sw = '0';
        }
        else
        {
            $paquete_id = $Datos['paquete_codigo_id'];
            $paquete_sw = $Datos['sw_paquete_facturado'];
        }



        $sql = "DELETE FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos_d
                WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                     AND prefijo = '".$this->DatosDocumento['prefijo']."'
                     AND numero = ".$this->DatosDocumento['numero']."
                     AND numerodecuenta = $NumeroDeCuenta
                     AND transaccion = $transaccion
                     AND cuenta = '".$Datos['cuenta']."';

                INSERT INTO cg_conf.tmp_contabilizacion_facturas_cuentas_cargos_d
                (
                    empresa_id,
                    prefijo,
                    numero,
                    numerodecuenta,
                    transaccion,
                    documento_cruce_id,
                    cuenta,
                    tipo_id_tercero,
                    tercero_id,
                    debito,
                    credito,
                    detalle,
                    centro_de_costo_id,
                    base_rtf,
                    porcentaje_rtf,
                    paquete_id,
                    paquete_sw,
                    documento_cxc,
                    documento_cxp
                )
                VALUES
                (
                    '".$this->DatosDocumento['empresa_id']."',
                    '".$this->DatosDocumento['prefijo']."',
                    ".$this->DatosDocumento['numero'].",
                    $NumeroDeCuenta,
                    $transaccion,
                    $documento_cruce_id,
                    '".$Datos['cuenta']."',
                    $tipo_id_tercero,
                    $tercero_id,
                    ".$Datos['debito'].",
                    ".$Datos['credito'].",
                    '".$Datos['detalle']."',
                    $centro_de_costo_id,
                    ".$Datos['base_rtf'].",
                    ".$Datos['porcentaje_rtf'].",
                    $paquete_id,
                    $paquete_sw,
                    $documento_cxc,
                    $documento_cxp
                );
        ";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "<pre>$sql</pre>" . $dbconn->ErrorMsg();
            return false;
        }

        return true;
    }


    /**
    * Metodo para borrar los datos de la contabilizacion de una factura en las tablas temporales.
    *
    * @return boolean
    * @access private
    */
    function DelTemporalesFacturacion()
    {
        list($dbconn) = GetDBconn();

        $sql= "DELETE FROM cg_conf.tmp_contabilizacion_facturas_cuentas
               WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                     AND prefijo = '".$this->DatosDocumento['prefijo']."'
                     AND numero = ".$this->DatosDocumento['numero'].";

               DELETE FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos
               WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                     AND prefijo = '".$this->DatosDocumento['prefijo']."'
                     AND numero = ".$this->DatosDocumento['numero'].";

               DELETE FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos_d
               WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                     AND prefijo = '".$this->DatosDocumento['prefijo']."'
                     AND numero = ".$this->DatosDocumento['numero'].";
               ";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        return true;
    }


    /**
    * Metodo que retorna un listado de errores unificado de la contabilizacion de una factura.
    *
    * @return boolean
    * @access private
    */
    function RetornarResumenErrores()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //OBTENER LOS TEMPORALES DE CUENTAS
        $sql= "SELECT * FROM cg_conf.tmp_contabilizacion_facturas_cuentas
               WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                     AND prefijo = '".$this->DatosDocumento['prefijo']."'
                     AND numero = ".$this->DatosDocumento['numero'].";
               ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $DATOS_CUENTAS = array();
        while($fila = $result->FetchRow())
        {
            $DATOS_CUENTAS[$fila['numerodecuenta']] = $fila;
        }

        $result->Close();

        //OBTENER LOS TEMPORALES DE CARGOS
        $sql= "SELECT * FROM cg_conf.tmp_contabilizacion_facturas_cuentas_cargos
               WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                     AND prefijo = '".$this->DatosDocumento['prefijo']."'
                     AND numero = ".$this->DatosDocumento['numero'].";
               ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $DATOS_CARGOS = array();
        while($fila = $result->FetchRow())
        {
            $DATOS_CARGOS[$fila['numerodecuenta']][$fila['transaccion']] = $fila;
        }

        $result->Close();

        $salida = "<PRE>\n";

        foreach($DATOS_CUENTAS as $NumCuenta=>$DatosCuenta)
        {
            if($DatosCuenta['sw_estado']==='0')
            {
                $salida .= "CUENTA No.$NumCuenta \n";
                $salida .= "    MENSAJE : ".$DatosCuenta['error_titulo']." \n";
                $salida .= "    DETALLE : ".$DatosCuenta['error_detalle']." \n";
                $salida .= "\n";

                foreach($DATOS_CARGOS[$NumCuenta] as $NumTransaccion=>$DatosTransaccion)
                {
                    if($DatosTransaccion['sw_estado']==='0')
                    {
                        $salida .= "    TRANSACCION No.$NumTransaccion \n";
                        $salida .= "    MENSAJE : ".$DatosTransaccion['error_titulo']." \n";
                        $salida .= "    DETALLE : ".$DatosTransaccion['error_detalle']." \n";
                        $salida .= "\n";
                    }
                }
            }
        }

        $salida .= "</PRE>\n";

        return $salida;
    }


    /**
    * Metodo para contabilizar contabilizar la contrapartida de los cargos de una factura tipo Contado Cajas Rapidas.
    *
    * @return boolean
    * @access private
    */
    function Contabilizar_FV_Contado_Cajas_Rapidas()
    {
        if($this->DatosFactura['total_efectivo']>0)
        {
            if($this->Contabilizar_efectivo()===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo Contabilizar_efectivo() retorno false";
                }
                return false;
            }
        }

        if($this->DatosFactura['total_cheques']>0)
        {
            if($this->Contabilizar_cheques()===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo Contabilizar_cheques() retorno false";
                }
                return false;
            }
        }

        if($this->DatosFactura['total_tarjetas']>0)
        {
            if($this->Contabilizar_tarjetas()===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo Contabilizar_tarjetas() retorno false";
                }
                return false;
            }
        }

        if($this->DatosFactura['total_bonos']>0)
        {
            if($this->Contabilizar_bonos()===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo Contabilizar_bonos() retorno false";
                }
                return false;
            }
        }

        return true;
    }


    /**
    * Metodo para contabilizar el valor en efectivo de un recibo de caja hospitalario.
    *
    * @return
    * @access private
    */
    function Contabilizar_efectivo()
    {
        //CONTABILIZACION DEL DEBITO
        $INFO_CTA = $this->GetParametizacionDoc('EFECTIVO_D','RC01');

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }
            return false;
        }


        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];
        $Datos['naturaleza']         = 'D';
        $Datos['valor']              = $this->DatosFactura['total_efectivo'];
        $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        $Datos['documento_cruce_id'] = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "EFECTIVO";

        $VectorMOV = $this->GenerarVectorMovimiento($Datos);

        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }
            return false;
        }

         //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION (10) PARA EL REGISTRO DEL RECAUDO CON EFECTIVO
        if(!$this->AddTmpTransaccion_d(0, 10, $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }

        return true;
    }


    /**
    * Metodo para contabilizar el valor en cheques de un recibo de caja hospitalario.
    *
    * @return
    * @access private
    */
    function Contabilizar_cheques()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //OBTENER EL DETALLE DE LOS CHEQUES

         $sql=" SELECT * FROM chequesf_mov
                WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                    AND factura_fiscal = ".$this->DatosDocumento['numero']."
                    AND prefijo = '".$this->DatosDocumento['prefijo']."'
                    AND estado = '0';";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO HAY REGISTRO DE CHEQUES EN LA TABLA [public.chequesf_mov] PARA ESTA FACTURA.";
            return false;
        }

        $cheques = array();
        $total_cheques = 0;
        $total_cheques_al_dia = 0;
        $total_cheques_postfechados = 0;

        while($fila = $result->FetchRow())
        {
            $cheques = $fila;
            $total_cheques += $fila['total'];

            if($fila['sw_postfechado'])
            {
                $total_cheques_postfechados += $fila['total'];
            }
            else
            {
                $total_cheques_al_dia += $fila['total'];
            }
        }

        if($total_cheques != $this->DatosDocumento['total_cheques'])
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "EL VALOR TOTAL EN CHEQUES DE LA FACTURA [".$this->DatosDocumento['total_cheques']."] NO ES IGUAL AL VALOR DE LOS MOVIMIENTOS EN CHEQUES [".$total_cheques."] EN LA TABLA public.chequesf_mov.";
            return false;
        }

        //CONTABILIZACION DE LOS CHEQUES AL DIA
        if($total_cheques_al_dia > 0)
        {
            //CONTABILIZACION DEL DEBITO
            $INFO_CTA = $this->GetParametizacionDoc('CHEQUES_AL_DIA_D','RC01');

            if($INFO_CTA === false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                }
                return false;
            }

            //DATOS DEL MOVIMIENTO
            $Datos['cuenta']             = $INFO_CTA['cuenta'];
            $Datos['naturaleza']         = 'D';
            $Datos['valor']              = $total_cheques_al_dia;
            $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
            $Datos['documento_cruce_id'] = -1;
            $Datos['base_rtf']           = 0;
            $Datos['porcentaje_rtf']     = 0;
            $Datos['tipo_id_tercero']    = "";
            $Datos['tercero_id']         = "";
            $Datos['detalle']            = "CHEQUES";

            $VectorMOV = $this->GenerarVectorMovimiento($Datos);

            if($VectorMOV===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                }
                return false;
            }

            //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION (11) PARA EL REGISTRO DEL RECAUDO CON CHEQUES AL DIA
            if(!$this->AddTmpTransaccion_d(0, 11, $VectorMOV))
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
                }

                return false;
            }
        }

        //CONTABILIZACION DE LOS CHEQUES POSTFECHADOS
        if($total_cheques_postfechados > 0)
        {
            //CONTABILIZACION DEL DEBITO
            $INFO_CTA = $this->GetParametizacionDoc('CHEQUES_POSTFECHADOS_D','RC01');

            if($INFO_CTA === false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                }
                return false;
            }

            //DATOS DEL MOVIMIENTO
            $Datos['cuenta']             = $INFO_CTA['cuenta'];
            $Datos['naturaleza']         = 'D';
            $Datos['valor']              = $total_cheques_postfechados;
            $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
            $Datos['documento_cruce_id'] = -1;
            $Datos['base_rtf']           = 0;
            $Datos['porcentaje_rtf']     = 0;
            $Datos['tipo_id_tercero']    = "";
            $Datos['tercero_id']         = "";
            $Datos['detalle']            = "CHEQUES POSTFECHADOS";

            $VectorMOV = $this->GenerarVectorMovimiento($Datos);

            if($VectorMOV===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                }
                return false;
            }

            //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION (12) PARA EL REGISTRO DEL RECAUDO CON CHEQUES AL DIA
            if(!$this->AddTmpTransaccion_d(0, 12, $VectorMOV))
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
                }

                return false;
            }
        }

        return true;
    }


    /**
    * Metodo para contabilizar el valor en tarjetas de un recibo de caja hospitalario.
    *
    * @return
    * @access private
    */
    function Contabilizar_tarjetas()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //OBTENER EL DETALLE DE LAS TARJETAS

        $sql="
            (
                SELECT tarjeta, total, 'DEBITO' as tipo FROM tarjetasf_mov_debito
                WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                    AND factura_fiscal = ".$this->DatosDocumento['numero']."
                    AND prefijo = '".$this->DatosDocumento['prefijo']."'
                    AND estado = '0'
            )
            UNION
            (
                SELECT tarjeta, total, 'CREDITO' as tipo FROM tarjetasf_mov_credito
                WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                    AND factura_fiscal = ".$this->DatosDocumento['numero']."
                    AND prefijo = '".$this->DatosDocumento['prefijo']."'
                    AND estado = '0'
            );";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO HAY REGISTRO DE MOVIMIENTOS CON TARJETAS DEBITO O CREDITO EN LAS TABLAS [public.tarjetas_mov_debito, public.tarjetas_mov_credito] PARA ESTE RECIBO.";
            return false;
        }

        $total_tarjetas = 0;
        $total_por_tarjeta = array();

        while($fila = $result->FetchRow())
        {
            $total_tarjetas += $fila['total'];
            $total_por_tarjeta[$fila['tipo']][$fila['tarjeta']] += $fila['total'];
        }

        if($total_tarjetas != $this->DatosFactura['total_tarjetas'])
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "EL VALOR TOTAL EN TARJETAS DE LA FACTURA CONTADO [".$this->DatosDocumento['total_tarjetas']."] NO ES IGUAL AL VALOR DE LOS MOVIMIENTOS EN TARJETAS [".$total_tarjetas."] EN LA TABLAS public.tarjetasf_mov_debito + public.tarjetasf_mov_credito.";
            return false;
        }

        $I=20; // SE UTILIZA PARA GENERAR UN NUMERO UNICO DE TRANSACCION PARA CADA CRUCE DE RECAUDO CON TARJETAS

        //CONTABILIZACION DE LAS TARJETAS DEBITO
        if(!empty($total_por_tarjeta['DEBITO']))
        {
            $INFO_COMISION = $this->GetParametizacionDoc('TARJETAS_DEBITO_COMISION_D','RC01');
            $INFO_RTF      = $this->GetParametizacionDoc('TARJETAS_DEBITO_RTF_D','RC01');

            foreach($total_por_tarjeta['DEBITO'] as $TARJETA=>$VALOR)
            {
                $PORCENTAJE_COMISION = 0;
                $COMISION=0;
                $RTF=0;

                //EN ESTE PARAMETRO ESTA LA COMISION DE LA TARJETA
                $INFO_CTA = $this->GetParametizacionDoc('TARJETA_'.$TARJETA.'_C','RC01');

                if($INFO_CTA === false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                    }
                    return false;
                }

                if(!empty($INFO_CTA['ARGUMENTOS']['COMISION']))
                {
                    $PORCENTAJE_COMISION = $INFO_CTA['ARGUMENTOS']['COMISION'];
                    if(!is_array($INFO_COMISION))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "NO SE ENCONTRO EL PARAMETRO [TARJETAS_DEBITO_COMISION_D] PARA EL TIPO DE DOCUMENTO [RC01] DE LA EMPRESA [".$this->DatosDocumento['empresa_id']."] EN LA TABLA [cg_conf.doc_parametros].";
                        return false;
                    }
                }

                //CONTABILIZACION DEL DEBITO (COMISION + RTF + DIFERENCIA)

                //CONTABILIZAR LA COMISION SI LA HAY
                if($PORCENTAJE_COMISION>0)
                {
                    $COMISION = round(($VALOR * $PORCENTAJE_COMISION / 100),0);

                    //DATOS DEL MOVIMIENTO
                    $Datos['cuenta']             = $INFO_COMISION['cuenta'];
                    $Datos['naturaleza']         = 'D';
                    $Datos['valor']              = $COMISION;
                    $Datos['centro_de_costo_id'] = $INFO_COMISION['ARGUMENTOS']['CENTRO_DE_COSTO'];
                    $Datos['documento_cruce_id'] = -1;
                    $Datos['base_rtf']           = 0;
                    $Datos['porcentaje_rtf']     = 0;
                    $Datos['tipo_id_tercero']    = "";
                    $Datos['tercero_id']         = "";
                    $Datos['detalle']            = "COMISION TARJETA DEBITO";

                    $VectorMOV = $this->GenerarVectorMovimiento($Datos);

                    if($VectorMOV===false)
                    {
                        if(empty($this->error))
                        {
                            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                            $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                        }
                        return false;
                    }

                    //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION ($I++ >= 20) PARA CADA CRUCE DEL RECAUDO CON TARJETAS
                    if(!$this->AddTmpTransaccion_d(0, $I++, $VectorMOV))
                    {
                        if(empty($this->error))
                        {
                            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                            $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
                        }

                        return false;
                    }
                }


                //CONTABILIZAR LA RTF SI LA HAY
                if(!empty($INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF']))
                {
                    $PORCENTAJE_RTF = $INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF'];

                    if($PORCENTAJE_RTF > 0)
                    {
                        $RTF = round(($VALOR * $PORCENTAJE_RTF / 100),0);

                        //DATOS DEL MOVIMIENTO
                        $Datos['cuenta']             = $INFO_RTF['cuenta'];
                        $Datos['naturaleza']         = 'D';
                        $Datos['valor']              = $RTF;
                        $Datos['centro_de_costo_id'] = $INFO_RTF['ARGUMENTOS']['CENTRO_DE_COSTO'];
                        $Datos['documento_cruce_id'] = -1;
                        $Datos['base_rtf']           = $VALOR;
                        $Datos['porcentaje_rtf']     = $PORCENTAJE_RTF;
                        $Datos['tipo_id_tercero']    = "";
                        $Datos['tercero_id']         = "";
                        $Datos['detalle']            = "RTF TARJETA DEBITO";

                        $VectorMOV = $this->GenerarVectorMovimiento($Datos);

                        if($VectorMOV===false)
                        {
                            if(empty($this->error))
                            {
                                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                            }
                            return false;
                        }

                        //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION ($I++ >= 20) PARA CADA CRUCE DEL RECAUDO CON TARJETAS
                        if(!$this->AddTmpTransaccion_d(0, $I++, $VectorMOV))
                        {
                            if(empty($this->error))
                            {
                                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
                            }

                            return false;
                        }
                    }
                }

                //CONTABILIZAR EL SALDO A DEBITAR = (VALOR - COMISION - RTF)
                $SUBTOTAL = ($VALOR - $COMISION - $RTF);

                if($SUBTOTAL == 0)
                {
                    return true;
                }
                elseif($SUBTOTAL < 0)
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "EL VALOR DE LA COMISION MAS LA RTF ES MAYOR QUE EL VALOR CANCELADO CON TARJETA DEBITO.";
                    return false;
                }

                $INFO_CTA = $this->GetParametizacionDoc('TARJETAS_DEBITO_D','RC01');

                if($INFO_CTA === false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                    }
                    return false;
                }

                //DATOS DEL MOVIMIENTO
                $Datos['cuenta']             = $INFO_CTA['cuenta'];
                $Datos['naturaleza']         = 'D';
                $Datos['valor']              = $SUBTOTAL;
                $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
                $Datos['documento_cruce_id'] = -1;
                $Datos['base_rtf']           = 0;
                $Datos['porcentaje_rtf']     = 0;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "TARJETA DEBITO";

                $VectorMOV = $this->GenerarVectorMovimiento($Datos);

                if($VectorMOV===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                    }
                    return false;
                }

                //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION ($I++ >= 20) PARA CADA CRUCE DEL RECAUDO CON TARJETAS
                if(!$this->AddTmpTransaccion_d(0, $I++, $VectorMOV))
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
                    }

                    return false;
                }
            }
        }


        //CONTABILIZACION DE LAS TARJETAS CREDITO
        if(!empty($total_por_tarjeta['CREDITO']))
        {
            $INFO_COMISION = $this->GetParametizacionDoc('TARJETAS_CREDITO_COMISION_D','RC01');
            $INFO_RTF      = $this->GetParametizacionDoc('TARJETAS_CREDITO_RTF_D','RC01');

            foreach($total_por_tarjeta['CREDITO'] as $TARJETA=>$VALOR)
            {
                $PORCENTAJE_COMISION = 0;
                $COMISION=0;
                $RTF=0;

                //EN ESTE PARAMETRO ESTA LA COMISION DE LA TARJETA
                $INFO_CTA = $this->GetParametizacionDoc('TARJETA_'.$TARJETA.'_C','RC01');

                if($INFO_CTA === false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                    }
                    return false;
                }

                if(!empty($INFO_CTA['ARGUMENTOS']['COMISION']))
                {
                    $PORCENTAJE_COMISION = $INFO_CTA['ARGUMENTOS']['COMISION'];
                    if(!is_array($INFO_COMISION))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "NO SE ENCONTRO EL PARAMETRO [TARJETAS_CREDITO_COMISION_D] PARA EL TIPO DE DOCUMENTO [RC01] DE LA EMPRESA [".$this->DatosDocumento['empresa_id']."] EN LA TABLA [cg_conf.doc_parametros].";
                        return false;
                    }
                }


                //CONTABILIZACION DEL DEBITO (COMISION + RTF + DIFERENCIA)

                //CONTABILIZAR LA COMISION SI LA HAY
                if($PORCENTAJE_COMISION>0)
                {
                    $COMISION = round(($VALOR * $PORCENTAJE_COMISION / 100),0);

                    //DATOS DEL MOVIMIENTO
                    $Datos['cuenta']             = $INFO_COMISION['cuenta'];
                    $Datos['naturaleza']         = 'D';
                    $Datos['valor']              = $COMISION;
                    $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
                    $Datos['documento_cruce_id'] = -1;
                    $Datos['base_rtf']           = 0;
                    $Datos['porcentaje_rtf']     = 0;
                    $Datos['tipo_id_tercero']    = "";
                    $Datos['tercero_id']         = "";
                    $Datos['detalle']            = "COMISION TARJETA CREDITO";

                    $VectorMOV = $this->GenerarVectorMovimiento($Datos);

                    if($VectorMOV===false)
                    {
                        if(empty($this->error))
                        {
                            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                            $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                        }
                        return false;
                    }

                    //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION ($I++ >= 20) PARA CADA CRUCE DEL RECAUDO CON TARJETAS
                    if(!$this->AddTmpTransaccion_d(0, $I++, $VectorMOV))
                    {
                        if(empty($this->error))
                        {
                            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                            $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
                        }

                        return false;
                    }
                }


                //CONTABILIZAR LA RTF SI LA HAY
                if(!empty($INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF']))
                {
                    $PORCENTAJE_RTF = $INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF'];

                    if($PORCENTAJE_RTF > 0)
                    {
                        $RTF = round(($VALOR * $PORCENTAJE_RTF / 100),0);

                        //DATOS DEL MOVIMIENTO
                        $Datos['cuenta']             = $INFO_RTF['cuenta'];
                        $Datos['naturaleza']         = 'D';
                        $Datos['valor']              = $RTF;
                        $Datos['centro_de_costo_id'] = $INFO_RTF['ARGUMENTOS']['CENTRO_DE_COSTO'];
                        $Datos['documento_cruce_id'] = -1;
                        $Datos['base_rtf']           = $VALOR;
                        $Datos['porcentaje_rtf']     = $PORCENTAJE_RTF;
                        $Datos['tipo_id_tercero']    = "";
                        $Datos['tercero_id']         = "";
                        $Datos['detalle']            = "RTF TARJETA CREDITO";

                        $VectorMOV = $this->GenerarVectorMovimiento($Datos);

                        if($VectorMOV===false)
                        {
                            if(empty($this->error))
                            {
                                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                            }
                            return false;
                        }

                        //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION ($I++ >= 20) PARA CADA CRUCE DEL RECAUDO CON TARJETAS
                        if(!$this->AddTmpTransaccion_d(0, $I++, $VectorMOV))
                        {
                            if(empty($this->error))
                            {
                                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
                            }

                            return false;
                        }
                    }
                }

                //CONTABILIZAR EL SALDO A DEBITAR = (VALOR - COMISION - RTF)
                $SUBTOTAL = ($VALOR - $COMISION - $RTF);

                if($SUBTOTAL == 0)
                {
                    return true;
                }
                elseif($SUBTOTAL < 0)
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "EL VALOR DE LA COMISION MAS LA RTF ES MAYOR QUE EL VALOR CANCELADO CON TARJETA DE CREDITO.";
                    return false;
                }

                $INFO_CTA = $this->GetParametizacionDoc('TARJETAS_CREDITO_D','RC01');

                if($INFO_CTA === false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                    }
                    return false;
                }

                //DATOS DEL MOVIMIENTO
                $Datos['cuenta']             = $INFO_CTA['cuenta'];
                $Datos['naturaleza']         = 'D';
                $Datos['valor']              = $SUBTOTAL;
                $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
                $Datos['documento_cruce_id'] = -1;
                $Datos['base_rtf']           = 0;
                $Datos['porcentaje_rtf']     = 0;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "TARJETA CREDITO";

                $VectorMOV = $this->GenerarVectorMovimiento($Datos);

                if($VectorMOV===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                    }
                    return false;
                }

                //UTILIZO EL NUMERO DE CUENTA (0) Y LA TRANSACCION ($I++ >= 20) PARA CADA CRUCE DEL RECAUDO CON TARJETAS
                if(!$this->AddTmpTransaccion_d(0, $I++, $VectorMOV))
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
                    }

                    return false;
                }
            }
        }

        return true;
    }

   /**
    * Metodo para contabilizar el honorario de una transaccion.
    *
    * @param array $DatosTransaccion
    * @return boolean
    * @access private
    */
    function Contabilizar_HM($DatosTransaccion)
    {
        $ValorHonorario = RedondearValores($DatosTransaccion['valor'] * $DatosTransaccion['porcentaje_honorario'] / 100);
        if(!($ValorHonorario>0))
        {
            return true; // NO HAGA NADA.
        }

        //OBTENER EL CENTRO DE COSTO DEL DEPARTAMENTO DE LA TRANSACCION
        $CC = $this->GetCentroDeCostoDepartamento($DatosTransaccion['empresa_id'], $DatosTransaccion['departamento']);
        if($CC===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetCentroDeCostoDepartamento() retorno false";
            }

            return false;
        }

        //CONTABILIZACION DEL DEBITO
        $INFO_CTA = $this->GetParametizacionDoc('COSTO_D','HM01');

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }
            return false;
        }


        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];
        $Datos['naturaleza']         = 'D';
        $Datos['valor']              = $ValorHonorario;
        $Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
        $Datos['documento_cruce_id'] = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = $DatosTransaccion['tipo_tercero_id'];
        $Datos['tercero_id']         = $DatosTransaccion['tercero_id'];
        $Datos['detalle']            = "COSTO HONORARIO MEDICO DE LA CUENTA  No." . $DatosTransaccion['numerodecuenta'] . " TRANSACCION " . $DatosTransaccion['transaccion'];

        $VectorMOV = $this->GenerarVectorMovimiento($Datos);


        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }
            return false;
        }

        //UTILIZO EL NUMERO DE CUENTA (-1) Y LA TRANSACCION PARA EL COSTO DEL HM
        if(!$this->AddTmpTransaccion_d(-1, $DatosTransaccion['transaccion'], $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }



        //CONTABILIZACION DEL CREDITO RTF Y CxP
        $VALOR_CxP = $ValorHonorario;

        $INFO_RTF = $this->GetParametizacionDoc('RTF_C','HM01');

        if(!empty($INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF']))
        {
            $PORCENTAJE_RTF = $INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF'];

            if($PORCENTAJE_RTF > 0)
            {
                $RTF = round(($VALOR_CxP * $PORCENTAJE_RTF / 100),0);
                $VALOR_CxP -= $RTF;

                //DATOS DEL MOVIMIENTO
                $Datos['cuenta']             = $INFO_RTF['cuenta'];
                $Datos['naturaleza']         = 'C';
                $Datos['valor']              = $RTF;
                $Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
                $Datos['documento_cruce_id'] = -1;
                $Datos['base_rtf']           = $ValorHonorario;
                $Datos['porcentaje_rtf']     = $PORCENTAJE_RTF;
                $Datos['tipo_id_tercero']    = $DatosTransaccion['tipo_tercero_id'];
                $Datos['tercero_id']         = $DatosTransaccion['tercero_id'];;
                $Datos['detalle']            = "RTF HONORARIO MEDICO DE LA CUENTA No." . $DatosTransaccion['numerodecuenta'] . " TRANSACCION " . $DatosTransaccion['transaccion'];

                $VectorMOV = $this->GenerarVectorMovimiento($Datos);

                if($VectorMOV===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                    }
                    return false;
                }

                //UTILIZO EL NUMERO DE CUENTA (-2) Y LA TRANSACCION PARA LA RTF DEL HM
                if(!$this->AddTmpTransaccion_d(-2, $DatosTransaccion['transaccion'], $VectorMOV))
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
                    }

                    return false;
                }

            }
        }

        $INFO_CTA = $this->GetParametizacionDoc('CUENTA_POR_PAGAR_C','HM01');

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }
            return false;
        }

        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];
        $Datos['naturaleza']         = 'C';
        $Datos['valor']              = $VALOR_CxP;
        $Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
        $Datos['documento_cruce_id'] = -1;
        $Datos['documento_cxp']      = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = $DatosTransaccion['tipo_tercero_id'];
        $Datos['tercero_id']         = $DatosTransaccion['tercero_id'];
        $Datos['detalle']            = "CxP HONORARIO MEDICO DE LA CUENTA No. " . $DatosTransaccion['numerodecuenta'] . " TRANSACCION " . $DatosTransaccion['transaccion'];

        $VectorMOV = $this->GenerarVectorMovimiento($Datos);

        if($VectorMOV===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
            }
            return false;
        }

        //UTILIZO EL NUMERO DE CUENTA (-3) Y LA TRANSACCION PARA EL VALOR A PAGAR DEL HM
        if(!$this->AddTmpTransaccion_d(-3, $DatosTransaccion['transaccion'], $VectorMOV))
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo AddTmpTransaccion_d() retorno false";
            }

            return false;
        }

        return true;

    }


}//fin de la clase

?>