<?php

/**
* $Id: Contabilizar_FA01.class.php,v 1.1 2007/07/11 04:30:37 alexgiraldo Exp $
*/

/**
* Clase para la contabilizacion y consulta de la misma de documentos de tipo FA01(NOTAS PARA ANULACION DE FACTURAS)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.1 $
* @package SIIS
*/
class Contabilizar_FA01 extends ContabilizarDocumento
{

    /**
    * Datos de la nota de anulacion
    *
    * @var array
    * @access private
    */
    var $DatosNotaAnulacion;


    /**
    * Datos de la Factura Anulada
    *
    * @var array
    * @access private
    */
    var $DatosFactura;

    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function Contabilizar_FA01()
    {
        $this->ContabilizarDocumento();
        return true;
    }


    /**
    * Metodo para contabilizar notas para anulacion de facturas.
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


        //BUSCAR LA FACTURA
        $sql = "
                SELECT
                    nc.*,
                    nc.nota_credito_id as numero,
                    nc.fecha_registro as fecha_documento,
                    'FA01' as tipo_doc_general_id

                FROM
                    notas_credito_anulacion_facturas as nc
                WHERE
                    nc.empresa_id = '$empresa_id'
                    AND nc.prefijo = '$prefijo'
                    AND nc.nota_credito_id = $numero;
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
            $this->mensajeDeError = "NO SE ENCONTRARON REGISTROS PARA LA NOTA DE ANULACION DE FACTURA DE VENTA";
            return false;
        }

        unset($this->DatosNotaAnulacion);
        $this->DatosNotaAnulacion=$result->FetchRow();
        $result->Close();

        //ESTABLECER LOS DATOS DEL DOCUMENTO A CONTABILIZAR
        if($this->SetDocumento($this->DatosNotaAnulacion)===false)
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

        switch($this->DatosNotaAnulacion['estado'])
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

            default: //ESTADOS INVALIDOS DE LA NOTA DE ANULACION

                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El estado de la nota no es valido [".$this->DatosNotaAnulacion['estado']."].";
                return false;
        }

        $this->DatosFactura = $this->GetDatosDocumentoContabilizado($this->DatosNotaAnulacion['empresa_id'],$this->DatosNotaAnulacion['prefijo_factura'],$this->DatosNotaAnulacion['factura_fiscal'],true);

        if($this->DatosFactura===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetDatosDocumentoContabilizado() retorno false";
            }
            return false;
        }

        if(empty($this->DatosFactura))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "SE REQUIERE QUE LA FACTURA [".$this->DatosNotaAnulacion['prefijo_factura']."-".$this->DatosNotaAnulacion['factura_fiscal']."] ESTE CONTABILIZADA PREVIAMENTE.";
            return false;
        }

        $this->DatosDocumento['tipo_id_tercero'] = $this->DatosFactura['tipo_id_tercero'];
        $this->DatosDocumento['tercero_id'] = $this->DatosFactura['tercero_id'];

        //SI LA FACTURA ESTA CONATBILIZADA COMO ANULADA LA NOTA TAMBIEN SE TIENE QUE ANULAR PUES NO HAY MOVIMIENTO PARA REVERTIR.
        if($this->DatosFactura['sw_estado']=='0')
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


        if($this->Contabilizar_Nota()===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo Contabilizar_Nota() retorno false";
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
    * Metodo para contabilizar la nota de anulacion de una factura.
    *
    * @return
    * @access private
    */
    function Contabilizar_Nota()
    {
        foreach($this->DatosFactura['DETALLE'] as $k=>$fila)
        {

            //DATOS DEL MOVIMIENTO
            $Datos['cuenta']             = $fila['cuenta'];

            if($fila['debito']>0)
            {
                $Datos['naturaleza'] = 'C';
                $Datos['valor'] = $fila['debito'];
            }
            else
            {
                $Datos['naturaleza'] = 'D';
                $Datos['valor'] = $fila['credito'];
            }


            $Datos['centro_de_costo_id'] = $fila['centro_de_costo_id'];
            $Datos['documento_cruce_id'] = $fila['documento_cruce_id'];
            $Datos['base_rtf']           = $fila['base_rtf'];
            $Datos['porcentaje_rtf']     = $fila['porcentaje_rtf'];
            $Datos['tipo_id_tercero']    = $fila['tipo_id_tercero'];
            $Datos['tercero_id']         = $fila['tercero_id'];
            $Datos['documento_cxc']      = $fila['documento_cxc'];
            $Datos['documento_cxp']      = $fila['documento_cxp'];
            $Datos['detalle']            = "ANULACION FACTURA [".$this->DatosNotaAnulacion['prefijo']."-".$this->DatosNotaAnulacion['numero']."]";
            $Datos['centro_de_operacion_id'] = $fila['centro_de_operacion_id'];

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

}//fin de la clase

?>