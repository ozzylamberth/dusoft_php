<?php

/**
* $Id: Contabilizar_CP01.class.php,v 1.1 2007/07/09 15:24:19 alexgiraldo Exp $
*/

/**
* Clase para la contabilizacion y consulta de la misma de documentos de tipo CP01(CUENTAS POR PAGAS POR HONORARIOS MEDICOS)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.1 $
* @package SIIS
*/
class Contabilizar_CP01 extends ContabilizarDocumento
{

    /**
    * Datos del documento
    *
    * @var array
    * @access private
    */
    var $DatosDocInv;

    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function Contabilizar_CP01()
    {
        $this->ContabilizarDocumento();
        return true;
    }


    /**
    * Metodo para contabilizar el documento
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
                    a.estado as sw_estado,
                    a.fecha_registro as fecha_documento,
                    'CP01' as tipo_doc_general_id

                FROM
                    voucher_honorarios_cuentas_x_pagar as a
                WHERE a.empresa_id = '$empresa_id'
                      AND a.prefijo = '$prefijo'
                      AND a.numero = $numero
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
            $this->mensajeDeError = "NO SE ENCONTRARON REGISTROS PARA EL DOCUMENTO (empresa_id=$empresa_id, prefijo=$prefijo, numero=$numero)";
            return false;
        }

        unset($this->DatosDocInv);
        $this->DatosDocInv=$result->FetchRow();
        $result->Close();


        //ESTABLECER LOS DATOS DEL DOCUMENTO A CONTABILIZAR
        if($this->SetDocumento($this->DatosDocInv)===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo SetDocumento() retorno false";
            }
            return false;
        }

        //CONSULTAR SI EL DOCUMENTO YA ESTA CONTABILIZADO

        $retorno = $this->ValidarActualizacionDelDocumentoContable($actualizar);
        if($retorno !== null) return $retorno;

        switch($this->DatosDocInv['sw_estado'])
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

            default: //ESTADOS INVALIDOS DEL DOCUMENTO

                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El estado del documento no es valido [".$this->DatosDocInv['sw_estado']."].";
                return false;
        }

        if($this->Contabilizar_DocCxPHM()===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo Contabilizar_DocCxPHM() retorno false";
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
    * @return bolean
    * @access private
    */
    function Contabilizar_DocCxPHM()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT b.*

                FROM
                    voucher_honorarios_facturas_profesionales as a,
                    voucher_honorarios as b

                WHERE a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
                      AND a.prefijo_cxp = '".$this->DatosDocumento['prefijo']."'
                      AND a.numero_cxp = ".$this->DatosDocumento['numero']."
                      AND b.empresa_id = a.empresa_id
                      AND b.prefijo = a.prefijo
                      AND b.numero = a.numero;
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
            $this->mensajeDeError = "EL DOCUMENTO NO TIENE DETALLE.";
            return false;
        }

        while($fila = $result->FetchRow())
        {
            //OBTENER EL CENTRO DE COSTO DEL DEPARTAMENTO DE LA TRANSACCION
            $CC = $this->GetCentroDeCostoDepartamento($fila['empresa_id'], $fila['departamento']);
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
            $VALOR_CxP = $fila['valor_honorario'];

            $INFO_RTF = $this->GetParametizacionDoc('RTF_C','HM01');

            if(!empty($INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF']))
            {
                $PORCENTAJE_RTF = $INFO_RTF['ARGUMENTOS']['PORCENTAJE_RTF'];

                if($PORCENTAJE_RTF > 0)
                {
                    $RTF = round(($VALOR_CxP * $PORCENTAJE_RTF / 100),0);
                    $VALOR_CxP -= $RTF;
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
            $Datos['documento_cruce_id'] = -1;
            $Datos['documento_cxp']      = -1;
            $Datos['base_rtf']           = 0;
            $Datos['porcentaje_rtf']     = 0;
            $Datos['tipo_id_tercero']    = "";
            $Datos['tercero_id']         = "";
            $Datos['detalle']            = "No.CUENTA " . $fila['numerodecuenta'] . " TRANSACCION " . $fila['transaccion'];

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


            $INFO_CTA = $this->GetParametizacionDoc('CUENTA_POR_PAGAR_C','CP01');

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
            $Datos['base_rtf']           = 0;
            $Datos['porcentaje_rtf']     = 0;
            $Datos['tipo_id_tercero']    = "";
            $Datos['tercero_id']         = "";
            $Datos['detalle']            = "No.CUENTA " . $fila['numerodecuenta'] . " TRANSACCION " . $fila['transaccion'];

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

        }//END DEL WILE

        return true;
    }


}//fin de la clase

?>