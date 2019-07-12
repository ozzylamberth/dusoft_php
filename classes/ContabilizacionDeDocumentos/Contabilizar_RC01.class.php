<?php

/**
* $Id: Contabilizar_RC01.class.php,v 1.8 2007/08/15 14:23:14 alexgiraldo Exp $
*/

/**
* Clase para la contabilizacion de documentos de tipo RC (Recibos de Caja Hospitalario)
*
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
* @version $Revision: 1.8 $
* @package SIIS
*/
class Contabilizar_RC01 extends ContabilizarDocumento
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
    function Contabilizar_RC01()
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

        //SOLO PARA RECIBOS DE CAJA TIPO HOSPITALARIO
        $sql = "
                SELECT
                    a.*,
                    a.recibo_caja as numero,
                    a.fecha_ingcaja as fecha_documento,
                    'RC01' as tipo_doc_general_id,
                    b.rc_hosp_id,
                    b.numerodecuenta

                FROM
                    recibos_caja AS a,
                    rc_detalle_hosp AS b
                WHERE
                    a.empresa_id = '$empresa_id'
                    AND a.prefijo = '$prefijo'
                    AND a.recibo_caja = $numero
                    AND a.sw_recibo_tesoreria = '0'
                    AND b.empresa_id = a.empresa_id
                    AND b.centro_utilidad = a.centro_utilidad
                    AND b.recibo_caja = a.recibo_caja
                    AND b.prefijo = a.prefijo
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
            $this->mensajeDeError = "NO SE ENCONTRARON REGISTROS PARA EL RECIBO DE CAJA HOSPITALARIO (empresa_id=$empresa_id, prefijo=$prefijo, recibo_caja=$numero)";
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
        if($this->DatosRecibo['estado'] != '0')
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
        $TOTAL_RECIBO +=  $this->DatosRecibo['total_bonos'];

        if($this->DatosRecibo['total_abono'] != $TOTAL_RECIBO)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO CUADRAN LOS TOTALES DEL RECIBO DE CJA (empresa_id=$empresa_id, prefijo=$prefijo, recibo_caja=$numero)";
            return false;
        }

        if($this->DatosRecibo['total_efectivo']>0)
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

        if($this->DatosRecibo['total_cheques']>0)
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

        if($this->DatosRecibo['total_tarjetas']>0)
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

        if($this->DatosRecibo['total_bonos']>0)
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
    * Metodo para contabilizar el valor en efectivo de un recibo de caja hospitalario.
    *
    * @return
    * @access private
    */
    function Contabilizar_efectivo()
    {
        //CONTABILIZACION DEL CREDITO
        $INFO_CTA = $this->GetParametizacionDoc('EFECTIVO_C');

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }
            return false;
        }

        $CENTRO_DE_COSTO = "";

        if(!empty($INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO']))
        {
            $CENTRO_DE_COSTO = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        }

        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];
        $Datos['naturaleza']         = 'C';
        $Datos['valor']              = $this->DatosRecibo['total_efectivo'];
        $Datos['centro_de_costo_id'] = $CENTRO_DE_COSTO;
        $Datos['documento_cruce_id'] = -1;
        $Datos['documento_cxp']      = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "[CUENTA ".$this->DatosRecibo['numerodecuenta']."] EFECTIVO";

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

        //CONTABILIZACION DEL DEBITO
        $INFO_CTA = $this->GetParametizacionDoc('EFECTIVO_D');

        if($INFO_CTA === false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
            }
            return false;
        }

        $CENTRO_DE_COSTO = "";

        if(!empty($INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO']))
        {
            $CENTRO_DE_COSTO = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        }

        //DATOS DEL MOVIMIENTO
        $Datos['cuenta']             = $INFO_CTA['cuenta'];
        $Datos['naturaleza']         = 'D';
        $Datos['valor']              = $this->DatosRecibo['total_efectivo'];
        $Datos['centro_de_costo_id'] = $CENTRO_DE_COSTO;
        $Datos['documento_cruce_id'] = -1;
        $Datos['documento_cxp']      = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "[CUENTA ".$this->DatosRecibo['numerodecuenta']."] EFECTIVO";

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

         $sql=" SELECT * FROM cheques_mov
                WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                    AND centro_utilidad = '".$this->DatosRecibo['centro_utilidad']."'
                    AND recibo_caja = ".$this->DatosDocumento['numero']."
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
            $this->mensajeDeError = "NO HAY REGISTRO DE CHEQUES EN LA TABLA [public.cheques_mov] PARA ESTE RECIBO .";
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

        if($total_cheques != $this->DatosRecibo['total_cheques'])
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "EL VALOR TOTAL EN CHEQUES DEL RECIBO DE CAJA [".$this->DatosRecibo['total_cheques']."] NO ES IGUAL AL VALOR DE LOS MOVIMIENTOS EN CHEQUES [".$total_cheques."] EN LA TABLA public.cheques_mov.";
            return false;
        }

        //CONTABILIZACION DE LOS CHEQUES AL DIA
        if($total_cheques_al_dia > 0)
        {
            //CONTABILIZACION DEL CREDITO
            $INFO_CTA = $this->GetParametizacionDoc('CHEQUES_AL_DIA_C');

            if($INFO_CTA === false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                }
                return false;
            }

            $CENTRO_DE_COSTO = "";

            if(!empty($INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO']))
            {
                $CENTRO_DE_COSTO = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
            }

            //DATOS DEL MOVIMIENTO
            $Datos['cuenta']             = $INFO_CTA['cuenta'];
            $Datos['naturaleza']         = 'C';
            $Datos['valor']              = $total_cheques_al_dia;
            $Datos['centro_de_costo_id'] = $CENTRO_DE_COSTO;
            $Datos['documento_cruce_id'] = -1;
            $Datos['documento_cxp']      = -1;
            $Datos['base_rtf']           = 0;
            $Datos['porcentaje_rtf']     = 0;
            $Datos['tipo_id_tercero']    = "";
            $Datos['tercero_id']         = "";
            $Datos['detalle']            = "[CUENTA ".$this->DatosRecibo['numerodecuenta']."] CHEQUES";

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

            //CONTABILIZACION DEL DEBITO
            $INFO_CTA = $this->GetParametizacionDoc('CHEQUES_AL_DIA_D');

            if($INFO_CTA === false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                }
                return false;
            }

            $CENTRO_DE_COSTO = "";

            if(!empty($INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO']))
            {
                $CENTRO_DE_COSTO = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
            }

            //DATOS DEL MOVIMIENTO
            $Datos['cuenta']             = $INFO_CTA['cuenta'];
            $Datos['naturaleza']         = 'D';
            $Datos['valor']              = $total_cheques_al_dia;
            $Datos['centro_de_costo_id'] = $CENTRO_DE_COSTO;
            $Datos['documento_cruce_id'] = -1;
            $Datos['documento_cxp']      = -1;
            $Datos['base_rtf']           = 0;
            $Datos['porcentaje_rtf']     = 0;
            $Datos['tipo_id_tercero']    = "";
            $Datos['tercero_id']         = "";
            $Datos['detalle']            = "[CUENTA ".$this->DatosRecibo['numerodecuenta']."] CHEQUES";

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

        //CONTABILIZACION DE LOS CHEQUES POSTFECHADOS
        if($total_cheques_postfechados > 0)
        {
            //CONTABILIZACION DEL CREDITO
            $INFO_CTA = $this->GetParametizacionDoc('CHEQUES_POSTFECHADOS_C');

            if($INFO_CTA === false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                }
                return false;
            }

            $CENTRO_DE_COSTO = "";

            if(!empty($INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO']))
            {
                $CENTRO_DE_COSTO = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
            }

            //DATOS DEL MOVIMIENTO
            $Datos['cuenta']             = $INFO_CTA['cuenta'];
            $Datos['naturaleza']         = 'C';
            $Datos['valor']              = $total_cheques_postfechados;
            $Datos['centro_de_costo_id'] = $CENTRO_DE_COSTO;
            $Datos['documento_cruce_id'] = -1;
            $Datos['documento_cxp']      = -1;
            $Datos['base_rtf']           = 0;
            $Datos['porcentaje_rtf']     = 0;
            $Datos['tipo_id_tercero']    = "";
            $Datos['tercero_id']         = "";
            $Datos['detalle']            = "[CUENTA ".$this->DatosRecibo['numerodecuenta']."] CHEQUES POSTFECHADOS";

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


            //CONTABILIZACION DEL DEBITO
            $INFO_CTA = $this->GetParametizacionDoc('CHEQUES_POSTFECHADOS_D');

            if($INFO_CTA === false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                }
                return false;
            }

            $CENTRO_DE_COSTO = "";

            if(!empty($INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO']))
            {
                $CENTRO_DE_COSTO = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
            }

            //DATOS DEL MOVIMIENTO
            $Datos['cuenta']             = $INFO_CTA['cuenta'];
            $Datos['naturaleza']         = 'D';
            $Datos['valor']              = $total_cheques_postfechados;
            $Datos['centro_de_costo_id'] = $CENTRO_DE_COSTO;
            $Datos['documento_cruce_id'] = -1;
            $Datos['documento_cxp']      = -1;
            $Datos['base_rtf']           = 0;
            $Datos['porcentaje_rtf']     = 0;
            $Datos['tipo_id_tercero']    = "";
            $Datos['tercero_id']         = "";
            $Datos['detalle']            = "[CUENTA ".$this->DatosRecibo['numerodecuenta']."] CHEQUES POSTFECHADOS";

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
                SELECT tarjeta, total, 'DEBITO' as tipo FROM tarjetas_mov_debito
                WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                    AND centro_utilidad = '".$this->DatosRecibo['centro_utilidad']."'
                    AND recibo_caja = ".$this->DatosDocumento['numero']."
                    AND prefijo = '".$this->DatosDocumento['prefijo']."'
                    AND estado = '0'
            )
            UNION ALL
            (
                SELECT tarjeta, total, 'CREDITO' as tipo FROM tarjetas_mov_credito
                WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                    AND centro_utilidad = '".$this->DatosRecibo['centro_utilidad']."'
                    AND recibo_caja = ".$this->DatosDocumento['numero']."
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

        if($total_tarjetas != $this->DatosRecibo['total_tarjetas'])
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "EL VALOR TOTAL EN TARJETAS DEL RECIBO DE CAJA [".$this->DatosRecibo['total_tarjetas']."] NO ES IGUAL AL VALOR DE LOS MOVIMIENTOS EN TARJETAS [".$total_tarjetas."] EN LA TABLAS public.tarjetas_mov_debito + public.tarjetas_mov_credito.";
            return false;
        }

        //CONTABILIZACION DE LAS TARJETAS DEBITO
        if(!empty($total_por_tarjeta['DEBITO']))
        {
            $INFO_COMISION = $this->GetParametizacionDoc('TARJETAS_DEBITO_COMISION_D');
            $INFO_RTF      = $this->GetParametizacionDoc('TARJETAS_DEBITO_RTF_D');

            foreach($total_por_tarjeta['DEBITO'] as $TARJETA=>$VALOR)
            {
                $COMISION=0;
                $RTF=0;

                //CONTABILIZACION DEL CREDITO
                $INFO_CTA = $this->GetParametizacionDoc('TARJETA_'.$TARJETA.'_C');

                if($INFO_CTA === false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                    }
                    return false;
                }

                $CENTRO_DE_COSTO = "";

                if(!empty($INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO']))
                {
                    $CENTRO_DE_COSTO = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
                }

                //DATOS DEL MOVIMIENTO
                $Datos['cuenta']             = $INFO_CTA['cuenta'];
                $Datos['naturaleza']         = 'C';
                $Datos['valor']              = $VALOR;
                $Datos['centro_de_costo_id'] = $CENTRO_DE_COSTO;
                $Datos['documento_cruce_id'] = -1;
                $Datos['documento_cxp']      = -1;
                $Datos['base_rtf']           = 0;
                $Datos['porcentaje_rtf']     = 0;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "[CUENTA ".$this->DatosRecibo['numerodecuenta']."] TARJETA DEBITO";

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


                //CONTABILIZACION DEL DEBITO (COMISION + RTF + DIFERENCIA)

                //CONTABILIZAR LA COMISION SI LA HAY
                $PORCENTAJE_COMISION = 0;

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

                if($PORCENTAJE_COMISION>0)
                {
                    $CENTRO_DE_COSTO = "";

                    if(!empty($INFO_COMISION['ARGUMENTOS']['CENTRO_DE_COSTO']))
                    {
                        $CENTRO_DE_COSTO = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
                    }

                    $COMISION = round(($VALOR * $PORCENTAJE_COMISION / 100),0);

                    //DATOS DEL MOVIMIENTO
                    $Datos['cuenta']             = $INFO_COMISION['cuenta'];
                    $Datos['naturaleza']         = 'D';
                    $Datos['valor']              = $COMISION;
                    $Datos['centro_de_costo_id'] = $CENTRO_DE_COSTO;
                    $Datos['documento_cruce_id'] = -1;
                    $Datos['documento_cxp']      = -1;
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
                    $this->AddMOV($VectorMOV);
                }


                //CONTABILIZAR LA RTF SI LA HAY
                if(!empty($INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF']))
                {
                    $PORCENTAJE_RTF = $INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF'];

                    if($PORCENTAJE_RTF > 0)
                    {
                        $RTF = round(($VALOR * $PORCENTAJE_RTF / 100),0);

                        $CENTRO_DE_COSTO = "";

                        if(!empty($INFO_RTF['ARGUMENTOS']['CENTRO_DE_COSTO']))
                        {
                            $CENTRO_DE_COSTO = $INFO_RTF['ARGUMENTOS']['CENTRO_DE_COSTO'];
                        }

                        //DATOS DEL MOVIMIENTO
                        $Datos['cuenta']             = $INFO_RTF['cuenta'];
                        $Datos['naturaleza']         = 'D';
                        $Datos['valor']              = $RTF;
                        $Datos['centro_de_costo_id'] = $CENTRO_DE_COSTO;
                        $Datos['documento_cruce_id'] = -1;
                        $Datos['documento_cxp']      = -1;
                        $Datos['base_rtf']           = 0;
                        $Datos['porcentaje_rtf']     = 0;
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
                        $this->AddMOV($VectorMOV);
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

                $INFO_CTA = $this->GetParametizacionDoc('TARJETAS_DEBITO_D');

                if($INFO_CTA === false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                    }
                    return false;
                }

                $CENTRO_DE_COSTO = "";

                if(!empty($INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO']))
                {
                    $CENTRO_DE_COSTO = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
                }

                //DATOS DEL MOVIMIENTO
                $Datos['cuenta']             = $INFO_CTA['cuenta'];
                $Datos['naturaleza']         = 'D';
                $Datos['valor']              = $SUBTOTAL;
                $Datos['centro_de_costo_id'] = $CENTRO_DE_COSTO;
                $Datos['documento_cruce_id'] = -1;
                $Datos['documento_cxp']      = -1;
                $Datos['base_rtf']           = 0;
                $Datos['porcentaje_rtf']     = 0;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "[CUENTA ".$this->DatosRecibo['numerodecuenta']."] TARJETA DEBITO";

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


        //CONTABILIZACION DE LAS TARJETAS CREDITO
        if(!empty($total_por_tarjeta['CREDITO']))
        {
            $INFO_COMISION = $this->GetParametizacionDoc('TARJETAS_CREDITO_COMISION_D');
            $INFO_RTF      = $this->GetParametizacionDoc('TARJETAS_CREDITO_RTF_D');

            foreach($total_por_tarjeta['CREDITO'] as $TARJETA=>$VALOR)
            {
                $COMISION=0;
                $RTF=0;

                //CONTABILIZACION DEL CREDITO
                $INFO_CTA = $this->GetParametizacionDoc('TARJETA_'.$TARJETA.'_C');

                if($INFO_CTA === false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                    }
                    return false;
                }

                $CENTRO_DE_COSTO = "";

                if(!empty($INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO']))
                {
                    $CENTRO_DE_COSTO = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
                }

                //DATOS DEL MOVIMIENTO
                $Datos['cuenta']             = $INFO_CTA['cuenta'];
                $Datos['naturaleza']         = 'C';
                $Datos['valor']              = $VALOR;
                $Datos['centro_de_costo_id'] = $CENTRO_DE_COSTO;
                $Datos['documento_cruce_id'] = -1;
                $Datos['documento_cxp']      = -1;
                $Datos['base_rtf']           = 0;
                $Datos['porcentaje_rtf']     = 0;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "[CUENTA ".$this->DatosRecibo['numerodecuenta']."] TARJETA CREDITO";

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


                //CONTABILIZACION DEL DEBITO (COMISION + RTF + DIFERENCIA)

                //CONTABILIZAR LA COMISION SI LA HAY
                $PORCENTAJE_COMISION = 0;

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

                if($PORCENTAJE_COMISION>0)
                {
                    $CENTRO_DE_COSTO = "";

                    if(!empty($INFO_COMISION['ARGUMENTOS']['CENTRO_DE_COSTO']))
                    {
                        $CENTRO_DE_COSTO = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
                    }

                    $COMISION = round(($VALOR * $PORCENTAJE_COMISION / 100),0);

                    //DATOS DEL MOVIMIENTO
                    $Datos['cuenta']             = $INFO_COMISION['cuenta'];
                    $Datos['naturaleza']         = 'D';
                    $Datos['valor']              = $COMISION;
                    $Datos['centro_de_costo_id'] = $CENTRO_DE_COSTO;
                    $Datos['documento_cruce_id'] = -1;
                    $Datos['documento_cxp']      = -1;
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
                    $this->AddMOV($VectorMOV);
                }


                //CONTABILIZAR LA RTF SI LA HAY
                if(!empty($INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF']))
                {
                    $PORCENTAJE_RTF = $INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF'];

                    if($PORCENTAJE_RTF > 0)
                    {
                        $RTF = round(($VALOR * $PORCENTAJE_RTF / 100),0);

                        $CENTRO_DE_COSTO = "";

                        if(!empty($INFO_RTF['ARGUMENTOS']['CENTRO_DE_COSTO']))
                        {
                            $CENTRO_DE_COSTO = $INFO_RTF['ARGUMENTOS']['CENTRO_DE_COSTO'];
                        }

                        //DATOS DEL MOVIMIENTO
                        $Datos['cuenta']             = $INFO_RTF['cuenta'];
                        $Datos['naturaleza']         = 'D';
                        $Datos['valor']              = $RTF;
                        $Datos['centro_de_costo_id'] = $CENTRO_DE_COSTO;
                        $Datos['documento_cruce_id'] = -1;
                        $Datos['documento_cxp']      = -1;
                        $Datos['base_rtf']           = 0;
                        $Datos['porcentaje_rtf']     = 0;
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
                        $this->AddMOV($VectorMOV);
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

                $INFO_CTA = $this->GetParametizacionDoc('TARJETAS_CREDITO_D');

                if($INFO_CTA === false)
                {
                    if(empty($this->error))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                    }
                    return false;
                }

                $CENTRO_DE_COSTO = "";

                if(!empty($INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO']))
                {
                    $CENTRO_DE_COSTO = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
                }

                //DATOS DEL MOVIMIENTO
                $Datos['cuenta']             = $INFO_CTA['cuenta'];
                $Datos['naturaleza']         = 'D';
                $Datos['valor']              = $SUBTOTAL;
                $Datos['centro_de_costo_id'] = $CENTRO_DE_COSTO;
                $Datos['documento_cruce_id'] = -1;
                $Datos['documento_cxp']      = -1;
                $Datos['base_rtf']           = 0;
                $Datos['porcentaje_rtf']     = 0;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "[CUENTA ".$this->DatosRecibo['numerodecuenta']."] TARJETA CREDITO";

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

        return true;
    }


    /**
    * Metodo para contabilizar el valor en bonos de un recibo de caja hospitalario.
    *
    * @return
    * @access private
    */
    function Contabilizar_bonos()
    {
        return false;
    }

}//fin de la clase
