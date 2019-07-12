<?php

/**
* $Id: Contabilizar_CV01.class.php,v 1.1 2007/07/17 19:25:50 alexgiraldo Exp $
*/

/**
* Clase para la contabilizacion de documentos de tipo CV01 (COSTOS DE VENTA)
*
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
* @version $Revision: 1.1 $
* @package SIIS
*/
class Contabilizar_CV01 extends ContabilizarDocumento
{

    /**
    * Datos del Recibo de caja
    *
    * @var array
    * @access private
    */
    var $DatosDocumentoCV;


    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function Contabilizar_CV01()
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
                    'CV01' as tipo_doc_general_id,
                    b.tipo_id_tercero,
                    b.id as tercero_id

                FROM
                    inv_bodegas_movimiento_costo_por_lapso as a,
                    empresas as b

                WHERE
                    a.empresa_id = '$empresa_id'
                    AND a.prefijo = '$prefijo'
                    AND a.numero = $numero
                    AND b.empresa_id = a.empresa_id
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

        unset($this->DatosDocumentoCV);
        $this->DatosDocumentoCV =$result->FetchRow();
        $result->Close();

        //ESTABLECER LOS DATOS DEL DOCUMENTO A CONTABILIZAR
        if($this->SetDocumento($this->DatosDocumentoCV)===false)
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
        if($this->DatosDocumentoCV['sw_estado'] != '1')
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


        if($this->Contabilizar_CV()===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo Contabilizar_CV() retorno false";
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
    function Contabilizar_CV()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "
                SELECT
                    a.*,
                    CASE WHEN b.cuenta IS NOT NULL THEN b.cuenta ELSE c.cuenta END as cuenta

                FROM inv_bodegas_movimiento_costo_por_lapso_d as a
                LEFT JOIN cg_conf.doc_inv_costo_parametros_excepciones as b
                ON (
                    b.empresa_id = a.empresa_id
                    AND b.codigo_producto = a.codigo_producto
                    AND b.departamento = a.departamento
                )
                LEFT JOIN cg_conf.doc_inv_costo_parametros as c
                ON (
                    c.empresa_id = a.empresa_id
                    AND c.grupo_id = a.grupo_id
                    AND c.clase_id = a.clase_id
                    AND c.subclase_id = a.subclase_id
                    AND c.departamento = a.departamento
                )

                WHERE a.empresa_id = '".$this->DatosDocumentoCV['empresa_id']."'
                AND a.prefijo = '".$this->DatosDocumentoCV['prefijo']."'
                AND a.numero = ".$this->DatosDocumentoCV['numero'].";
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

        while($FILA = $result->FetchRow())
        {
            if($FILA['costo_total']>0)
            {
                if(empty($FILA['cuenta']))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    $this->mensajeDeError = "EL PARMETRO PARA EL COSTO DEL PRODUCTO [".$FILA['codigo_producto']."] EN EL DEPARTAMENTO [".$FILA['departamento']."] NO ESTA PARAMETRIZADO.";
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
