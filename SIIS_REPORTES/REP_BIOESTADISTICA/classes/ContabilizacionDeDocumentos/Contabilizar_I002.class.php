<?php

/**
* $Id: Contabilizar_I002.class.php,v 1.3 2007/06/26 21:27:17 alexgiraldo Exp $
*/

/**
* Clase para la contabilizacion y consulta de la misma de documentos de tipo I002(INGRESOS INVENTARIO POR COMPRA)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.3 $
* @package SIIS
*/
class Contabilizar_I002 extends ContabilizarDocumento
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
    function Contabilizar_I002()
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
                    d.tipo_id_tercero,
                    d.tercero_id,
                    a.fecha_registro as fecha_documento,
                    'I002' as tipo_doc_general_id

                FROM
                    inv_bodegas_movimiento as a,
                    inv_bodegas_movimiento_ordenes_compra as b,
                    compras_ordenes_pedidos as c,
                    terceros_proveedores as d


                WHERE
                    a.empresa_id = '$empresa_id'
                    AND a.prefijo = '$prefijo'
                    AND a.numero = $numero
                    AND b.empresa_id = a.empresa_id
                    AND b.prefijo = a.prefijo
                    AND b.numero = a.numero
                    AND c.orden_pedido_id = b.orden_pedido_id
                    AND d.codigo_proveedor_id = c.codigo_proveedor_id;
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

        if($this->Contabilizar_DocInv()===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo Contabilizar_DocInv() retorno false";
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
    function Contabilizar_DocInv()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT
                    a.*,
                    b.grupo_id,
                    b.clase_id,
                    b.subclase_id
                FROM
                    inv_bodegas_movimiento_d as a,
                    inventarios_productos as b

                WHERE a.empresa_id = '".$this->DatosDocumento['empresa_id']."'
                      AND a.prefijo = '".$this->DatosDocumento['prefijo']."'
                      AND a.numero = ".$this->DatosDocumento['numero']."
                      AND b.codigo_producto = a.codigo_producto;
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
            //CONTABILIZACION DEL INGRESO A INVENTARIO (DEBITO)
            $CUENTA = $this->GetCuentaContableInvProducto($fila['grupo_id'],$fila['clase_id'],$fila['subclase_id'],$fila['codigo_producto']);

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
            $Datos['naturaleza']         = 'D';
            $Datos['valor']              = $fila['total_costo'];
            $Datos['centro_de_costo_id'] = "";
            $Datos['documento_cruce_id'] = -1;
            $Datos['base_rtf']           = 0;
            $Datos['porcentaje_rtf']     = 0;
            $Datos['tipo_id_tercero']    = "";
            $Datos['tercero_id']         = "";
            $Datos['detalle']            = "INGRESO POR COMPRA";

            if($fila['porcentaje_gravamen']>0)
            {
                $Datos['centro_de_operacion_id'] = "90";
            }
            else
            {
                $Datos['centro_de_operacion_id'] = "80";
            }


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

            //CONTABILIZACION DEL INGRESO A INVENTARIO (CREDITO)

            $INFO_CTA = $this->GetParametizacionDoc('CRUCE_INGRESO_C','I001');

            if($INFO_CTA === false)
            {
                if(empty($this->error))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "El metodo GetParametizacion() retorno false";
                }
                return false;
            }

            unset($Datos);

            $Datos['cuenta']             = $INFO_CTA['cuenta'];
            $Datos['naturaleza']         = 'C';
            $Datos['valor']              = $fila['total_costo'];
            $Datos['centro_de_costo_id'] = "";
            $Datos['documento_cruce_id'] = -1;
            $Datos['base_rtf']           = 0;
            $Datos['porcentaje_rtf']     = 0;
            $Datos['tipo_id_tercero']    = "";
            $Datos['tercero_id']         = "";
            $Datos['detalle']            = "INGRESO POR COMPRA CxP I002";

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


    /**
    * Metodo para obtener la cuenta contable de inventario de un producto.
    *
    * @param string $grupo_id
    * @param string $clase_id
    * @param string $subclase_id
    * @param string $codigo_producto
    * @return array
    * @access private
    */
    function GetCuentaContableInvProducto($grupo_id,$clase_id,$subclase_id,$codigo_producto)
    {
        static $InfoContableProductos;
        static $InfoContableGrupos;

        if(empty($grupo_id)  || empty($clase_id) || empty($subclase_id) || empty($codigo_producto))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTAN ARGUMENTOS.[grupo_id = $grupo_id, clase_id = $clase_id, subclase_id = $subclase_id, codigo_producto = $codigo_producto]";
            return false;
        }

        if(empty($InfoContableProductos[$this->DatosDocumento['empresa_id']][$codigo_producto]) && empty($InfoContableGrupos[$this->DatosDocumento['empresa_id']][$grupo_id][$clase_id][$subclase_id]))
        {
            list($dbconn) = GetDBconn();

            $sql = "SELECT cuenta FROM cg_conf.doc_inv_parametros_excepciones
                    WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                    AND codigo_producto = '$codigo_producto';
                    ";

            $result = $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            if(!$result->EOF)
            {
                list($InfoContableProductos[$this->DatosDocumento['empresa_id']][$codigo_producto]) = $result->FetchRow();
                $result->Close();
            }
            else
            {
                $sql = "SELECT cuenta FROM cg_conf.doc_inv_parametros
                        WHERE empresa_id = '".$this->DatosDocumento['empresa_id']."'
                            AND grupo_id = '$grupo_id'
                            AND clase_id = '$clase_id'
                            AND subclase_id = '$subclase_id';
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
                    $this->mensajeDeError = "LA CLASIFICACION DE INVENTARIOS [empresa_id = ".$this->DatosDocumento['empresa_id']."][grupo_id = $grupo_id][clase_id = $clase_id][subclase_id = $subclase_id] NO SE ENCUENTRA PARAMETRIZADA CONTABLEMENTE EN LA TABLA [cg_conf.doc_inv_parametros].";
                    return false;
                }

                list($InfoContableGrupos[$this->DatosDocumento['empresa_id']][$grupo_id][$clase_id][$subclase_id]) = $result->FetchRow();
                $result->Close();
            }
        }

        if(!empty($InfoContableProductos[$this->DatosDocumento['empresa_id']][$codigo_producto]))
        {
            return $InfoContableProductos[$this->DatosDocumento['empresa_id']][$codigo_producto];
        }
        elseif(!empty($InfoContableGrupos[$this->DatosDocumento['empresa_id']][$grupo_id][$clase_id][$subclase_id]))
        {
            return $InfoContableGrupos[$this->DatosDocumento['empresa_id']][$grupo_id][$clase_id][$subclase_id];
        }
        else
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "LA CLASIFICACION DE INVENTARIOS [empresa_id = ".$this->DatosDocumento['empresa_id']."][grupo_id = $grupo_id][clase_id = $clase_id][subclase_id = $subclase_id] NO SE ENCUENTRA PARAMETRIZADA CONTABLEMENTE EN LA TABLA [cg_conf.doc_inv_parametros].";
            return false;
        }
    }


}//fin de la clase

?>