<?php
/**
* $Id: Contabilizar_ND01.class.php,v 1.2 2008/08/13 14:33:05 hugo Exp $
*/

/**
* Clase para la contabilizacion de documentos de tipo NC01 (NOTAS CREDITO)
*
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
* @version $Revision: 1.2 $
* @package SIIS
*/
class Contabilizar_ND01 extends ContabilizarDocumento
{

    /**
    * Datos del Recibo de caja
    *
    * @var array
    * @access private
    */
    var $DatosDocumentoNC;


    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function Contabilizar_ND01()
    {
        $this->ContabilizarDocumento();
        return true;
    }


    /**
    * Metodo para contabilizar el documento
    *
    * @param string $empresa_id
    * @param string $centro_utilidad
    * @param integer $recibo_caja
    * @param string $prefijo
    * @param boolean $actualizar   false:IGNORE   true:ACTUALIZAR
    *
    * @return string
    * @access public
    */
    function ContabilizarDoc($empresa_id, $prefijo, $numero, $actualizar=false)
    {
        if(empty($empresa_id) || empty($prefijo) || empty($numero))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTAN ARGUMENTOS (empresa_id=$empresa_id, prefijo=$prefijo, numero=$numero)";
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();


        $sql = "
                SELECT
                    a.*,
                    a.nota_debito_id as numero,
                    a.fecha_registro as fecha_documento,
                    'NC01' as tipo_doc_general_id,
                    a.estado as sw_estado

                FROM
                    notas_debito as a

                WHERE
                    a.empresa_id = '$empresa_id'
                    AND a.prefijo = '$prefijo'
                    AND a.nota_debito_id = $numero
                ";
//echo '<br><br>';
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
            $this->mensajeDeError = "NO SE ENCONTRARON REGISTROS PARA EL DOCUMENTO (empresa_id=$empresa_id, prefijo=$prefijo, numero=$numero)";
            return false;
        }

        unset($this->DatosDocumentoND);
        $this->DatosDocumentoND =$result->FetchRow();
        $result->Close();

        //ESTABLECER LOS DATOS DEL DOCUMENTO A CONTABILIZAR
        if($this->SetDocumento($this->DatosDocumentoND)===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo SetDocumento() retorno false";
            }
            return false;
        }

        //CONSULTAR SI EL DOCUMENTO YA ESTA CONTABILIZADO

        $retorno =& $this->ValidarActualizacionDelDocumentoContable($actualizar);
        if($retorno !== null) return $retorno;


        // SI EL ESTADO ES DISTINTO DE CERO LO CONTABILIZO COMO UN DOCUMENTO ANULADO
        if($this->DatosDocumentoND['sw_estado'] != '1')
        {
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
        }


        if($this->Contabilizar_ND()===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo Contabilizar_ND() retorno false";
            }
            return false;
        }


        if($this->GenerarDocumentoContable()===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GenerarDocumentoContable() retorno false";
            }
            return false;
        }

        //UNA VEZ TERMINO LA CONTABILIZACION RETORNO EL RESULTADO.
        return $this->RetornarDocumentoContable();

    }//fin de ContabilizarDoc()


    /**
    * Metodo para contabilizar el costo de venta.
    *
    * @return
    * @access private
    */
    function Contabilizar_ND()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //OBTENER EL TIPO DE FACTURA (CREDITO, CONTADO, CAPITACION)
        $sql="  SELECT tipo_factura
                FROM   public.fac_facturas as a

                WHERE a.empresa_id = '".$this->DatosDocumentoND['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumentoND['prefijo_factura']."'
                AND a.factura_fiscal = ".$this->DatosDocumentoND['factura_fiscal'].";";

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
            $this->mensajeDeError = "El tipo_factura no existe.";
            return false;
        }

        list($tipo_factura)=$resultado->FetchRow();
        $resultado->Close();
//echo '<br><br>';
        //CONTABILIZAR LA PARTIDA ADECUADA (CREDITO)
        //0 => PACIENTE 
        //1 => CLIENTE
        //2 => PARTICULAR
        //3 => AGRUPADA CAPITACION
        //4 => AGRUPADA NO CAPITACION
        //5 => CONCEPTOS 
        //6 => PRODUCTOS INVENTARIO
       switch($tipo_factura)
        {
            case '4':
                if($this->Contabilizar_ND_FacturaCapitacion()===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo Contabilizar_ND_FacturaCapitacion() retorno false";
                    }
                    return false;
                }

            break;

            case '1':
                if($this->Contabilizar_ND_FacturaCredito()===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo Contabilizar_ND_FacturaCredito() retorno false";
                    }
                    return false;
                }
            break;

            default:

                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El tipo_factura de la nota no es valido";
                return false;

        }

        //CONTABILIZACION DE LOS CONCEPTOS (DEBITO)
/*
 echo       $sql = "
                SELECT
                    a.*,
                    b.cuenta

                FROM public.notas_debito_detalle_conceptos as a
                LEFT JOIN cg_conf.doc_nc01_conceptos as b
                ON (
                    b.empresa_id = a.empresa_id
                    AND b.concepto_id = a.concepto_id
                )

                WHERE a.empresa_id = '".$this->DatosDocumentoND['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumentoND['prefijo']."'
                AND a.nota_debito_id = ".$this->DatosDocumentoND['numero'].";
        ";

echo '<br><br>';

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        while($FILA = $result->FetchRow())
        {
            if($FILA['valor']>0)
            {
                if(empty($FILA['cuenta']))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "EL PARMETRO PARA VALOR DE LA NOTA DEBITO [".$FILA['prefijo'].$FILA['nota_debito_id']."] EN EL DEPARTAMENTO [".$FILA['departamento']."] NO ESTA PARAMETRIZADO.";
                    return false;
                }

                $CC = $this->GetCentroDeCostoDepartamento($FILA['empresa_id'], $FILA['departamento']);

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
                $Datos['cuenta']             = $FILA['cuenta'];
                $Datos['naturaleza']         = 'D';
                $Datos['valor']              = $FILA['costo_total'];
                $Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
                $Datos['documento_cruce_id'] = -1;
                $Datos['base_rtf']           = 0;
                $Datos['porcentaje_rtf']     = 0;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "";

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

                $this->AddMOV($VectorMOV);


                //CONTABILIZACION DEL INVENTARIO (CREDITO)
                $CUENTA = $this->GetCuentaContableInvProducto($FILA['grupo_id'],$FILA['clase_id'],$FILA['subclase_id'],$FILA['codigo_producto']);

                if($CUENTA===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GetCuentaContableInvProducto() retorno false";
                    }

                    return false;
                }

                $Datos['cuenta']             = $CUENTA;
                $Datos['naturaleza']         = 'C';
                $Datos['valor']              = $FILA['costo_total'];
                $Datos['centro_de_costo_id'] = "";
                $Datos['documento_cruce_id'] = -1;
                $Datos['base_rtf']           = 0;
                $Datos['porcentaje_rtf']     = 0;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "";

    //             if($fila['porcentaje_gravamen']>0)
    //             {
    //                 $Datos['centro_de_operacion_id'] = "90";
    //             }
    //             else
    //             {
    //                 $Datos['centro_de_operacion_id'] = "80";
    //             }

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

                $this->AddMOV($VectorMOV);
            }
        }
*/

        return true;
    }



    /**
    * Metodo para contabilizar contabilizar la contrapartida de los cargos de una factura tipo Credito.
    *
    * @return boolean
    * @access private
    */
    function Contabilizar_ND_FacturaCredito()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //OBTENER EL TIPO DE CLIENTE DESDE LA CONTRATACION
        $sql="  SELECT tipo_cliente
                FROM
                    public.fac_facturas as a,
                    public.planes as b

                WHERE a.empresa_id = '".$this->DatosDocumentoND['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumentoND['prefijo_factura']."'
                AND a.factura_fiscal = ".$this->DatosDocumentoND['factura_fiscal']."
                AND b.plan_id = a.plan_id;";
//echo '<br><br>';
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

        $DOC_CRUCE = $this->GetDatosDocumentoContabilizado($this->DatosDocumentoND['empresa_id'],$this->DatosDocumentoND['prefijo_factura'],$this->DatosDocumentoND['factura_fiscal']);
//print_r($DOC_CRUCE);
        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];
        $Datos['naturaleza']         = "D";
        $Datos['valor']              = $this->DatosDocumentoND['valor_nota'];
        $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        $Datos['documento_cruce_id'] = $DOC_CRUCE['documento_contable_id'];
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "TOTAL NOTA D";

//print_r($Datos);

        $VectorMOV = $this->GenerarVectorMovimiento($Datos);
//print_r($VectorMOV); 

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

       $this->AddMOV($VectorMOV);
     
//CONTABILIZACION DEL DEBITO DE LA NOTA
        $sql = "
                SELECT DISTINCT a.departamento,
                    a.*,
                    b.cuenta,
                    b.empresa_id,
                    CASE WHEN a.departamento IS NULL THEN b.departamento 
                    ELSE a.departamento END AS departamento
                FROM public.notas_debito_detalle_conceptos as a
                LEFT JOIN cg_conf.doc_ncnd_conceptos as b
                ON (
                    b.empresa_id = a.empresa_id
                    AND b.concepto_id = a.concepto_id
                    AND b.departamento = a.departamento
                )

                WHERE a.empresa_id = '".$this->DatosDocumentoND['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumentoND['prefijo']."'
                AND a.nota_debito_id = ".$this->DatosDocumentoND['numero'].";
        ";

//echo '<br><br>';

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        //while($FILA = $result->FetchRow())
        while($FILA = $result->FetchRow())
        {
            if($FILA['valor']>0)
            {
                if(empty($FILA['cuenta']))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "EL PARMETRO PARA VALOR DE LA NOTA DEBITO [".$FILA['prefijo'].$FILA['nota_debito_id']."] EN EL DEPARTAMENTO [".$FILA['departamento']."] NO ESTA PARAMETRIZADO.";
                    return false;
                }

               //OBTENER EL CENTRO DE COSTO DEL DEPARTAMENTO DE LA TRANSACCION
               $CC = $this->GetCentroDeCostoDepartamento($FILA['empresa_id'], $FILA['departamento']);

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
                $Datos['cuenta']             = $FILA['cuenta'];
                $Datos['naturaleza']         = 'C';
                $Datos['valor']              = $FILA['valor'];
                $Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
                $Datos['documento_cruce_id'] = -1;
                $Datos['base_rtf']           = 0;
                $Datos['porcentaje_rtf']     = 0;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "TOTAL NOTA C";

                $VectorMOV = $this->GenerarVectorMovimiento($Datos);
// echo '<br><br><br>';
// print_r($VectorMOV);

                if($VectorMOV===false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GenerarVectorMovimiento() retorno false";
                    }

                    return false;
                }

                $this->AddMOV($VectorMOV);
            }
        }

//FIN CONTABILIZACION DEL DEBITO DE LA NOTA 

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
                    paquete_sw
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
                    $paquete_sw
                );
        ";
//echo $sql; 
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
    * Metodo para contabilizar contabilizar la contrapartida de los cargos de una factura tipo Credito.
    *
    * @return boolean
    * @access private
    */
    function Contabilizar_ND_FacturaCapitacion()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = "El metodo No definido Contabilizar_ND_FacturaCapitacion() retorno false";
        return false;

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

}//fin de la clase