<?php

/**
* $Id: Contabilizar_RT01.class.php,v 1.4 2007/08/15 14:23:14 alexgiraldo Exp $
*/

/**
* Clase para la contabilizacion de documentos de tipo RT01 (Recibos de Caja de Tesoreria)
*
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
* @version $Revision: 1.4 $
* @package SIIS
*/
class Contabilizar_RT01 extends ContabilizarDocumento
{

    /**
    * Datos del Recibo de caja
    *
    * @var array
    * @access private
    */
    var $DatosRecibo;


    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function Contabilizar_RT01()
    {
        $this->ContabilizarDocumento();
        return true;
    }


    /**
    * Metodo para contabilizar un recibo de caja
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
            $this->mensajeDeError = "FALTAN ARGUMENTOS (empresa_id=$empresa_id, prefijo=$prefijo, recibo_caja=$numero)";
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //SOLO PARA RECIBOS DE CAJA DE TESORERIA
        $sql = "
                SELECT
                    a.*,
                    a.recibo_caja as numero,
                    a.fecha_ingcaja as fecha_documento,
                    'RT01' as tipo_doc_general_id,
                    b.cuenta
                FROM
                    recibos_caja as a,
                    rc_tipos_documentos as b
                WHERE
                    a.empresa_id = '$empresa_id'
                    AND a.prefijo = '$prefijo'
                    AND a.recibo_caja = $numero
                    AND a.sw_recibo_tesoreria = '1'
                    AND b.rc_tipo_documento = a.rc_tipo_documento
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
            $this->mensajeDeError = "NO SE ENCONTRARON REGISTROS PARA EL RECIBO DE TESORERIA (empresa_id=$empresa_id, prefijo=$prefijo, recibo_caja=$numero)";
            return false;
        }

        unset($this->DatosRecibo);
        $this->DatosRecibo =$result->FetchRow();
        $result->Close();

        //ESTABLECER LOS DATOS DEL DOCUMENTO A CONTABILIZAR
        if($this->SetDocumento($this->DatosRecibo)===false)
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


        // SI EL ESTADO ES DISTINTO DE CERO LO CONTABILIZO COMO UN RECIBO DE CAJA ANULADO
        if($this->DatosRecibo['estado'] != '2')
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

        //VALIDAR LA INTEGRIDAD DEL RECIBO DE CAJA
        $TOTAL_RECIBO  =  $this->DatosRecibo['total_efectivo'];
        $TOTAL_RECIBO +=  $this->DatosRecibo['total_cheques'];
        $TOTAL_RECIBO +=  $this->DatosRecibo['total_tarjetas'];
        $TOTAL_RECIBO +=  $this->DatosRecibo['total_consignacion'];
        $TOTAL_RECIBO +=  $this->DatosRecibo['otros'];

        if($this->DatosRecibo['total_abono'] != $TOTAL_RECIBO)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO CUADRAN LOS TOTALES DEL RECIBO DE TESORERIA (empresa_id=$empresa_id, prefijo=$prefijo, recibo_caja=$numero)";
            return false;
        }

        if($this->DatosRecibo['total_abono']>0)
        {
            if($this->Contabilizar_RT()===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo Contabilizar_efectivo() retorno false";
                }
                return false;
            }
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
    * Metodo para contabilizar el valor abonado de un recibo de tesoreria.
    *
    * @return
    * @access private
    */
    function Contabilizar_RT()
    {
        //PARAMETRIZACION DEL RECAUDO (NOTA: POR EL MOMENTO LOS PAGOS SE LLEVAN TODOS A CAJA NO SE SEPARAN POR EFECTIVO, CHEQUE,TARJETA ETC.)
        if(empty($this->DatosRecibo['cuenta']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro 'cuenta' para el rc_tipo_documento = [".$this->DatosRecibo['rc_tipo_documento']."] en la tabla 'rc_tipos_documentos' no puede estar nulo.";
        }

        $INFO_CC_DEFAULT = $this->GetParametizacionDoc("CENTRO_DE_COSTO_DEFAULT",'RT01');

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }
            return false;
        }

        $CC_DEFAULT = $INFO_CC_DEFAULT['ARGUMENTOS']['CENTRO_DE_COSTO'];


        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        //OBTENER LAS FACTURAS CRUZADAS SI LAS HAY.
        $sql = "
                SELECT SUM(valor_abonado) AS valor_abonado,
                    tipo_cliente,
                    plan_id

                FROM
                (
                    (
                        SELECT SUM(valor_abonado) AS valor_abonado,
                            PL.tipo_cliente, FF.plan_id

                        FROM   rc_detalle_tesoreria_facturas RF,
                            fac_facturas FF LEFT JOIN planes PL
                            ON(FF.plan_id = PL.plan_id)
                        WHERE  RF.empresa_id = '".$this->DatosRecibo['empresa_id']."'
                        AND    RF.prefijo = '".$this->DatosRecibo['prefijo']."'
                        AND    RF.recibo_caja = ".$this->DatosRecibo['recibo_caja']."
                        AND    RF.factura_fiscal = FF.factura_fiscal
                        AND    RF.prefijo_factura = FF.prefijo
                        AND    RF.empresa_id = FF.empresa_id
                        GROUP BY 2,3
                    )
                    UNION
                    (
                        SELECT SUM(valor_abonado) AS valor_abonado,
                            PL.tipo_cliente, FF.plan_id

                        FROM   rc_detalle_tesoreria_facturas RF,
                            facturas_externas FF LEFT JOIN planes PL
                            ON(FF.plan_id = PL.plan_id)
                        WHERE  RF.empresa_id = '".$this->DatosRecibo['empresa_id']."'
                        AND    RF.prefijo = '".$this->DatosRecibo['prefijo']."'
                        AND    RF.recibo_caja = ".$this->DatosRecibo['recibo_caja']."
                        AND    RF.factura_fiscal = FF.factura_fiscal
                        AND    RF.prefijo_factura = FF.prefijo
                        AND    RF.empresa_id = FF.empresa_id
                        GROUP BY 2, 3
                    )
                ) AS A GROUP BY 2, 3 ;
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

        //CONTABILIZAR EL CREDITO DE LA CxC DE LAS FACTURAS CRUZADAS.
        while($FILA = $result->FetchRow())
        {
            if(empty($FILA['tipo_cliente']))
            {
                if(!empty($FILA['plan_id']))
                {
                    $this->mensajeDeError = "El plan [".$FILA['plan_id']."] no tiene parametrizado el campo [tipo_cliente].";
                }
                else
                {
                    $this->mensajeDeError = "El recibo de tesoreria esta cruzando con facturas externas que no tienen el campo plan_id. TABLA[facturas_externas].";
                }

                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                return false;
            }

            $INFO_CTA = $this->GetParametizacionDoc("TIPO_CLIENTE_".$FILA['tipo_cliente'],'FV01');

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
            $Datos['valor']              = $FILA['valor_abonado'];
            $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
            $Datos['documento_cruce_id'] = -1;
            $Datos['documento_cxp']      = -1;
            $Datos['base_rtf']           = 0;
            $Datos['porcentaje_rtf']     = 0;
            $Datos['tipo_id_tercero']    = "";
            $Datos['tercero_id']         = "";
            $Datos['detalle']            = "FACTURAS CRUZADAS TIPO CLIENTE ".$FILA['tipo_cliente'];

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

        $result->Close();


        //OBTENER LOS CONCEPTOS DEL RECIBO
        $sql = "
                SELECT a.*, b.cuenta, b.descripcion as descripcion_concepto
                FROM rc_detalle_tesoreria_conceptos as a,
                rc_conceptos_tesoreria as b
                WHERE
                    a.empresa_id = '".$this->DatosRecibo['empresa_id']."'
                    AND a.prefijo = '".$this->DatosRecibo['prefijo']."'
                    AND a.recibo_caja = ".$this->DatosRecibo['recibo_caja']."
                    AND b.concepto_id = a.concepto_id;
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

        //CONTABILIZAR LOS CONCEPTOS DEL RECIBO
        while($FILA = $result->FetchRow())
        {
            if(empty($FILA['cuenta']))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El parametro 'cuenta' para el  concepto_id= [".$FILA['concepto_id']."] en la tabla 'rc_conceptos_tesoreria' no puede estar nulo.";
            }

            //DATOS DEL MOVIMIENTO
            $Datos['cuenta']             = $FILA['cuenta'];
            $Datos['naturaleza']         = $FILA['naturaleza'];
            $Datos['valor']              = $FILA['valor'];

            if($FILA['departamento'])
            {
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
                $Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
            }
            else
            {
                $Datos['centro_de_costo_id'] = $CC_DEFAULT;
            }

            $Datos['documento_cruce_id'] = -1;
            $Datos['documento_cxp']      = -1;
            $Datos['base_rtf']           = 0;
            $Datos['porcentaje_rtf']     = 0;
            $Datos['tipo_id_tercero']    = "";
            $Datos['tercero_id']         = "";
            $Datos['detalle']            = $FILA['descripcion_concepto'];

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


        //CONTABILIZACION EL VALOR DEL RECIBO
        if(empty($this->DatosRecibo['cuenta']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El parametro 'cuenta' para el rc_tipo_documento = [".$this->DatosRecibo['rc_tipo_documento']."] en la tabla 'rc_tipos_documentos' no puede estar nulo.";
        }

        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $this->DatosRecibo['cuenta'];
        $Datos['naturaleza']         = 'D';
        $Datos['valor']              = $this->DatosRecibo['total_abono'];
        $Datos['centro_de_costo_id'] = $CC_DEFAULT;
        $Datos['documento_cruce_id'] = -1;
        $Datos['documento_cxp']      = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "TOTAL RECAUDO";

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

        return true;
    }

}//fin de la clase
