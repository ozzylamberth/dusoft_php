<?php

/**
* $Id: Contabilizar_HMND.class.php,v 1.4 2007/03/06 00:07:12 jgomez Exp $
*/

/**
* Clase para la contabilizacion y consulta de la misma de documentos de tipo HMND(NOTAS DEBITO DE HONORARIOS MEDICOS)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.4 $
* @package SIIS
*/
class Contabilizar_HMND extends ContabilizarDocumento
{

    /**
    * Datos de la devolucion
    *
    * @var array
    * @access private
    */
    var $DatosVoucher;

    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function Contabilizar_HMND()
    {
        $this->ContabilizarDocumento();
        return true;
    }


    /**
    * Metodo para contabilizar notas debito para voucher de honorarios medicos
    *
    * @param string $empresa_id
    * @param string $prefijo
    * @param integer $numero
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

        $sql = "SELECT
                    a.*,
                    a.fecha_registro as fecha_documento,
                    b.numerodecuenta,
                    b.tipo_id_tercero,
                    b.tercero_id,
                    b.departamento,
                    'HMND' as tipo_doc_general_id

                FROM    voucher_honorarios_nd as a,
                        voucher_honorarios as b

                WHERE a.empresa_id = '$empresa_id'
                AND a.prefijo = '$prefijo'
                AND a.numero = $numero
                AND b.empresa_id = a.empresa_id
                AND b.prefijo = a.prefijo_voucher
                AND b.numero = a.numero_voucher;";


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
            $this->mensajeDeError = "NO SE ENCONTRARON REGISTROS PARA LA NOTA DEBITO DE VOUCHER HONORARIOS MEDICOS (empresa_id=$empresa_id, prefijo=$prefijo, numero=$numero)";
            return false;
        }

        unset($this->DatosVoucher);
        $this->DatosVoucher=$result->FetchRow();
        $result->Close();

        //VALIDAR QUE EL DOCUMENTO TENGA VALOR
        if(!($this->DatosVoucher['valor']>0))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El valor del voucher no es valido [".$this->DatosVoucher['valor_honorario']."]";
            return false;
        }

        //ESTABLECER LOS DATOS DEL DOCUMENTO A CONTABILIZAR
        if($this->SetDocumento(&$this->DatosVoucher)===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo SetDocumento() retorno false";
            }
            return false;
        }

        //CONSULTAR SI EL DOCUMENTO YA ESTA CONTABILIZADO

        $retorno =& $this->ValidarActualizacionDelDocumentoContable(&$actualizar);
        if($retorno !== null) return $retorno;

        switch($this->DatosVoucher['estado'])
        {
            case '1': //ACTIVO
            break;

            case '0': //ANULADO
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

            default: //ESTADOS INVALIDOS DEL PAGARE

                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El estado de la nota debito del voucher no es valido [".$this->DatosVoucher['estado']."].";
                return false;
        }

        if($this->Contabilizar_HM()===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo Contabilizar_HM() retorno false";
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

    }

   /**
    * Metodo para contabilizar el documento.
    *
    * @return
    * @access private
    */
    function Contabilizar_HM()
    {
        //OBTENER EL CENTRO DE COSTO DEL DEPARTAMENTO DE LA TRANSACCION
        $CC = $this->GetCentroDeCostoDepartamento($this->DatosVoucher['empresa_id'], $this->DatosVoucher['departamento']);
        if($CC===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetCentroDeCostoDepartamento() retorno false";
            }

            return false;
        }
        
        //OBTENER EL DOCUMENTO CRUCE DEL HONORARIO
        $DOC_CRUCE = $this->GetDatosDocumentoContabilizado($this->DatosVoucher['empresa_id'],$this->DatosVoucher['prefijo_voucher'],$this->DatosVoucher['numero_voucher']);

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
                $this->mensajeDeError = "DOCUMENTO CRUCE SIN CONTABILIZAR : [".$this->DatosVoucher['prefijo_voucher']."-".$this->DatosVoucher['numero_voucher']."] DE LA EMPRESA [".$this->DatosVoucher['empresa_id']."].";
                return false;
            }
        }          
        
        

        //CONTABILIZACION DEL CREDITO
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
        $Datos['naturaleza']         = 'C';
        $Datos['valor']              = $this->DatosVoucher['valor'];
        $Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
        $Datos['documento_cruce_id'] = $DOC_CRUCE['documento_contable_id'];
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "No.CUENTA " . $this->DatosVoucher['numerodecuenta'] . " TRANSACCION " . $this->DatosVoucher['transaccion'];

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


        //CONTABILIZACION DEL DEBITO RTF Y CxP
        $VALOR_CxP = $this->DatosVoucher['valor'];

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
                $Datos['naturaleza']         = 'D';
                $Datos['valor']              = $RTF;
                $Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
                $Datos['documento_cruce_id'] = $DOC_CRUCE['documento_contable_id'];
                $Datos['base_rtf']           = $this->DatosVoucher['valor'];
                $Datos['porcentaje_rtf']     = $PORCENTAJE_RTF;
                $Datos['tipo_id_tercero']    = "";
                $Datos['tercero_id']         = "";
                $Datos['detalle']            = "RTF - No.CUENTA " . $this->DatosVoucher['numerodecuenta'] . " TRANSACCION " . $this->DatosVoucher['transaccion'];

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
        $Datos['naturaleza']         = 'D';
        $Datos['valor']              = $VALOR_CxP;
        $Datos['centro_de_costo_id'] = $CC['centro_de_costo_id'];
        $Datos['documento_cruce_id'] = $DOC_CRUCE['documento_contable_id'];
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "No.CUENTA " . $this->DatosVoucher['numerodecuenta'] . " TRANSACCION " . $this->DatosVoucher['transaccion'];

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

?>