<?php

/**
* $Id: Contabilizar_PG01.class.php,v 1.4 2007/08/15 14:23:14 alexgiraldo Exp $
*/

/**
* Clase para la contabilizacion y consulta de la misma de documentos de tipo PG(Pagares)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.4 $
* @package SIIS
*/
class Contabilizar_PG01 extends ContabilizarDocumento
{
    /**
    * Datos del pagare
    *
    * @var array
    * @access private
    */
    var $DatosPagare;

    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function Contabilizar_PG01()
    {
        $this->ContabilizarDocumento();
        return true;
    }


    /**
    * Metodo para contabilizar un pagare
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

        //SE ESTA CONTABILIZANDO CON EL PACIENTE Y NO CON EL RESPONSABLE POR EL CRUZE DE LAS FACTURAS.
        $sql = "
                SELECT
                    a.*,
                    c.tipo_id_paciente as tipo_id_tercero,
                    c.paciente_id as tercero_id,
                    a.fecha_registro as fecha_documento,
                    'PG01' as tipo_doc_general_id,
                    CASE WHEN d.tipo_id_tercero IS NULL THEN '1' ELSE '0'  END as sw_crear_tercero

                FROM
                    pagares as a,
                    cuentas as b,
                    ingresos as c
                    LEFT JOIN terceros as d
                    ON (c.tipo_id_paciente = d.tipo_id_tercero AND c.paciente_id = d.tercero_id)

                WHERE
                    a.empresa_id = '$empresa_id'
                    AND a.prefijo = '$prefijo'
                    AND a.numero = $numero
                    AND b.numerodecuenta = a.numerodecuenta
                    AND c.ingreso = b.ingreso
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
            $this->mensajeDeError = "NO SE ENCONTRARON REGISTROS PARA EL PAGARE (empresa_id=$empresa_id, prefijo=$prefijo, numero=$numero)";
            return false;
        }

        unset($this->DatosPagare);
        $this->DatosPagare=$result->FetchRow();
        $result->Close();

        //VALIDAR QUE EL DOCUMENTO TENGA VALOR
        if(!($this->DatosPagare['valor']>0))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El valor del pagare no es valido [".$this->DatosPagare['valor']."]";
            return false;
        }

        //ESTABLECER LOS DATOS DEL DOCUMENTO A CONTABILIZAR
        if($this->SetDocumento(&$this->DatosPagare)===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo SetDocumento() retorno false";
            }
            return false;
        }

        //CREAR EL TERCERO SI NO EXISTE (RECORDAR QUE ESTAMOS CAMBIANDO EL RESPONSABLE AL PACIENTE Y PUEDE QUE NO EXISTA)
        if($this->DatosPagare['sw_crear_tercero'])
        {
            if($this->CrearTercero()===false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo CrearTercero() retorno false";
                }
                return false;
            }
        }

        //CONSULTAR SI EL DOCUMENTO YA ESTA CONTABILIZADO
        $retorno =& $this->ValidarActualizacionDelDocumentoContable(&$actualizar);
        if($retorno !== null) return $retorno;

        switch($this->DatosPagare['sw_estado'])
        {
            case '1': //ACTIVO
            case '3': //ACTIVO - CANCELADO
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

            default: //ESTADOS INVALIDOS DEL PAGARE

                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El estado del pagare no es valido [".$this->DatosPagare['sw_estado']."].";
                return false;
        }

        if($this->Contabilizar_PG()===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo Contabilizar_PG() retorno false";
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
    function Contabilizar_PG()
    {
        //CONTABILIZACION DEL CREDITO
        $INFO_CTA = $this->GetParametizacionDoc($parametro='PAGARE_C');

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
        $Datos['valor']              = $this->DatosPagare['valor'];
        $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        $Datos['documento_cruce_id'] = -1;
        $Datos['documento_cxp']      = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "No.CUENTA " . $this->DatosPagare['numerodecuenta'];

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
        $INFO_CTA = $this->GetParametizacionDoc($parametro='PAGARE_D');

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
        $Datos['valor']              = $this->DatosPagare['valor'];
        $Datos['centro_de_costo_id'] = $INFO_CTA['ARGUMENTOS']['CENTRO_DE_COSTO'];
        $Datos['documento_cruce_id'] = -1;
        $Datos['documento_cxp']      = -1;
        $Datos['base_rtf']           = 0;
        $Datos['porcentaje_rtf']     = 0;
        $Datos['tipo_id_tercero']    = "";
        $Datos['tercero_id']         = "";
        $Datos['detalle']            = "No.CUENTA " . $this->DatosPagare['numerodecuenta'];

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
    * METODO PARA CREAR UN TERCERO SI ESTE NO EXISTE
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function CrearTercero()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "
                    INSERT INTO terceros(
                                tipo_id_tercero,
                                tercero_id,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                direccion,
                                telefono,
                                usuario_id,
                                nombre_tercero)

                    SELECT  tipo_id_paciente,
                            paciente_id,
                            CASE WHEN tipo_pais_id IS NULL THEN 'CO' ELSE tipo_pais_id END as tipo_pais_id,
                            CASE WHEN tipo_dpto_id IS NULL THEN '76' ELSE tipo_dpto_id END as tipo_dpto_id,
                            CASE WHEN tipo_mpio_id IS NULL THEN '001' ELSE tipo_mpio_id END as tipo_mpio_id,
                            residencia_direccion as direccion,
                            residencia_telefono as telefono,
                            usuario_id,
                            primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombre_tercero

                    FROM pacientes

                    WHERE tipo_id_paciente = '".$this->DatosDocumento['tipo_id_tercero']."'
                          AND paciente_id = '".$this->DatosDocumento['tercero_id']."';
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

        return true;
    }

}//fin de la clase

?>