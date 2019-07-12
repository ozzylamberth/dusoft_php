<?php
/**
* $Id: ContabilizacionDeDocumentos.class.php,v 1.10 2007/06/19 15:48:36 alexgiraldo Exp $
*/

/**
* Clase para la contabilizacion de documentos de SIIS
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.10 $
* @package SIIS
*/
class ContabilizacionDeDocumentos
{
    var $OBJ;
    var $OBJ_TIPO_DOC;
    var $DatosOBJs;

    /**
    * Codigo de error
    *
    * @var string
    * @access private
    */
    var $error;

    /**
    * Mensaje de error
    *
    * @var string
    * @access private
    */
    var $mensajeDeError;

    /**
    * retorno de un lote de documentos contabilizados
    *
    * @var array
    * @access private
    */
    var $RetornoLoteContabilizacion;
    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public
    */
    function ContabilizacionDeDocumentos()
    {
        return true;
    }


    /**
    * Metodo para recuperar el titulo del error
    *
    * @return string
    * @access public
    */
    function Err()
    {
        return $this->error;
    }


    /**
    * Metodo para recuperar el detalle del error
    *
    * @return string
    * @access public
    */
    function ErrMsg()
    {
        return $this->mensajeDeError;
    }


    /**
    * Metodo para obtener un arreglo con los retornos de un lote de contabilizacion.
    *
    * @return string
    * @access public
    */
    function GetRetornoLoteContabilizacion()
    {
        return $this->RetornoLoteContabilizacion;
    }


    /**
    * Metodo para retornar el documento contabilizado.
    *
    * @param string $empresa_id
    * @param string $prefijo PREFIJO DEL DOCUMENTO A CONTABILIZAR
    * @param integer $numero
    * @param boolean $actualizar INDICA SI DEBE RECONTABILIZAR LOS DOCUMENTOS QUE YA ESTAN CONTABILIZADOS
    * @return
    * @return
    * @access private
    */
    function ContabilizarDocumento($empresa_id, $prefijo, $numero,  $actualizar=false)
    {
        if($this->SetOBJ($empresa_id, $prefijo)===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo SetOBJ() retorno false";
            }
            return false;
        }

        $salida = &$this->OBJ->ContabilizarDoc($empresa_id, $prefijo, $numero,  $actualizar);

        if($salida===false)
        {
            if(empty($this->OBJ->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo ContabilizarDocumento() del objeto de contabilizacion retorno false";
            }
            else
            {
                $this->error = $this->OBJ->ErrMsg();
                $this->mensajeDeError = $this->OBJ->Err();
            }
            return false;
        }
        elseif(!is_array($salida))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "El metodo ContabilizarDocumento() retorno [$salida]";
            return false;
        }

        return $salida;
    }


    /**
    * Metodo para contabilizar todos los documentos de un tipo especifico en un lapso.
    *
    * @param string $empresa_id
    * @param string $prefijo PREFIJO DEL DOCUMENTO A CONTABILIZAR
    * @param string $lapso LAPSO A CONTABILIZAR
    * @param boolean $actualizar INDICA SI DEBE RECONTABILIZAR LOS DOCUMENTOS QUE YA ESTAN CONTABILIZADOS
    * @return
    * @access public
    */
    function ContabilizarLapsoDocumento($empresa_id, $prefijo, $lapso, $actualizar=false)
    {
        if($this->SetOBJ($empresa_id, $prefijo)===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo SetOBJ() retorno false";
            }
            return false;
        }

        if($actualizar)
        {
            $tipo = 'TODOS';
        }
        else
        {
            $tipo = 'NO_CONT';
        }

        $tipo_doc_general_id = $this->DatosOBJs[$empresa_id][$prefijo]['tipo_doc_general_id'];

        $salida = &$this->GetDocumentosLapsoDoc($tipo_doc_general_id,$empresa_id, $prefijo, $lapso, $tipo, $detallado=false);

        if($salida===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetDocumentosLapsoDoc() retorno false";
            }
            return false;
        }
        elseif(!is_array($salida))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE ENCONTRARON DOCUMENTOS PARA CONTABILIZAR";
            return false;
        }

        unset($this->RetornoLoteContabilizacion);
        foreach($salida as $numero => $numDoc)
        {
           $retorno = $this->ContabilizarDocumento($empresa_id, $prefijo, $numero, $actualizar);

           if($retorno === false)
           {
                $this->RetornoLoteContabilizacion[$numero]['RESULTADO'] = FALSE;
                $this->RetornoLoteContabilizacion[$numero]['TITULO']    = $this->error;
                $this->RetornoLoteContabilizacion[$numero]['DETALLE']   = $this->mensajeDeError;
           }
           elseif(is_array($retorno))
           {
                $this->RetornoLoteContabilizacion[$numero]['RESULTADO'] = TRUE;
                $this->RetornoLoteContabilizacion[$numero]['TITULO']    = $retorno['RESULTADO'];
                $this->RetornoLoteContabilizacion[$numero]['DETALLE']   = $retorno['RESULTADO_D'];
           }
        }

        return true;
    }


    /**
    * Metodo que retorna un vector con todos los documentos en un lapso
    *
    * @param string $tipo_doc_general_id
    * @param string $empresa_id
    * @param string $prefijo
    * @param string $lapso
    * @param string $tipo  ['TODOS'/'SI_CONT'/'DESCUADRADAS'/'NO_CONT'] deafult 'TODOS'
    *
    * @return string
    * @access public
    */
    function GetDocumentosLapsoDoc($tipo_doc_general_id, $empresa_id, $prefijo, $lapso, $tipo = 'TODOS',$detallado = false)
    {
        if(empty($tipo_doc_general_id) || empty($empresa_id) || empty($prefijo) || empty($lapso))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTAN ARGUMENTOS (tipo_doc_general_id=$tipo_doc_general_id, empresa_id=$empresa_id, prefijo=$prefijo, lapso=$lapso)";
            return false;
        }

        $VISTA = "cg_mov_$empresa_id".".\"cg_mov_contable_$empresa_id"."_$tipo_doc_general_id\"";

        switch ($tipo)
        {
            case 'SI_CONT':

                    $sql = "SELECT * FROM $VISTA
                            WHERE prefijo = '$prefijo'
                            AND lapso = '$lapso'
                            AND sw_estado = '2'
                            AND documento_contable_id IS NOT NULL";
            break;

            case 'DESCUADRADAS':

                    $sql = "SELECT * FROM $VISTA
                            WHERE prefijo = '$prefijo'
                            AND lapso = '$lapso'
                            AND sw_estado = '1'
                            AND documento_contable_id IS NOT NULL";
            break;

            case 'NO_CONT':

                    $sql = "SELECT * FROM $VISTA
                            WHERE prefijo = '$prefijo'
                            AND lapso = '$lapso'
                            AND documento_contable_id IS NULL";
            break;

            default:

                    $sql = "SELECT * FROM $VISTA
                            WHERE prefijo = '$prefijo'
                            AND lapso = '$lapso'";
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $retorno = array();

        while($fila = $result->FetchRow())
        {
            if($detallado)
            {
                $retorno[$fila['numero']] = $fila;
            }
            else
            {
                $retorno[$fila['numero']] = $fila['numero'];
            }
        }
        $result->Close();

        return  $retorno;
    }


    /**
    * Metodo para crear el objecto de contabilizacion.
    *
    * @param string $empresa_id
    * @param string $prefijo
    * @return
    * @access private
    */
    function SetOBJ($empresa_id, $prefijo)
    {
        if(is_object($this->OBJ) && ($this->OBJ_TIPO_DOC['empresa_id'] == $empresa_id) && ($this->OBJ_TIPO_DOC['prefijo'] == $prefijo))
        {
            return true;
        }
        unset($this->OBJ_TIPO_DOC);
        unset($this->OBJ);

        if (!IncludeClass('ContabilizarDocumento', 'ContabilizacionDeDocumentos'))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE PUDO INCLUIR LA CLASE [ContabilizarDocumento].";
            return false;
        }

        $CLASS = $this->GetClaseDeContabilizacion($empresa_id, $prefijo);

        if($CLASS===false)
        {
            if(empty($this->error))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "El metodo GetClaseDeContabilizacion() retorno false";
            }
            return false;
        }

        $this->OBJ = new $CLASS;

        if(!is_object($this->OBJ))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE PUDO CREAR EL OBJETO DE CONTABILIZACION.";
            return false;
        }

        $this->OBJ_TIPO_DOC['empresa_id'] = $empresa_id;
        $this->OBJ_TIPO_DOC['prefijo'] = $prefijo;

        return true;
    }


    /**
    * Metodo para retornar la clase de contabilizacion de un documento
    *
    * @param string $empresa_id
    * @param string $prefijo
    * @return
    * @access private
    */
    function GetClaseDeContabilizacion($empresa_id, $prefijo)
    {
        if(empty($this->DatosOBJs))
        {
            GLOBAL $ADODB_FETCH_MODE;
            list($dbconn) = GetDBconn();

            $sql = "SELECT * FROM public.documentos as a, public.tipos_doc_generales as b
                    WHERE b.tipo_doc_general_id = a.tipo_doc_general_id";

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
                $this->mensajeDeError = "NO HAY DOCUMENTOS CREADOS";
                return false;
            }

            while($fila = $result->FetchRow())
            {
                $this->DatosOBJs[$fila['empresa_id']][$fila['prefijo']]=$fila;
            }
            $result->Close();
        }

        if($this->DatosOBJs[$empresa_id][$prefijo]['sw_doc_sistema']==='0')
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "EL TIPO DE DOCUMENTO [".$this->DatosOBJs[$empresa_id][$prefijo]['tipo_doc_general_id']."] ES DE CONTABILIZACION MANUAL.";
            return false;
        }

        $VersionMC  = '';

        if($this->DatosOBJs[$empresa_id][$prefijo]['modelo_contabilizacion'])
        {
            $VersionMC = "_".$this->DatosOBJs[$empresa_id][$prefijo]['modelo_contabilizacion'];

        }

        $CLASS = "Contabilizar_".$this->DatosOBJs[$empresa_id][$prefijo]['tipo_doc_general_id'].$VersionMC;

        if (!IncludeClass($CLASS, 'ContabilizacionDeDocumentos'))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE PUDO INCLUIR LA CLASE  [$CLASS].";
            return false;
        }

        if(!class_exists($CLASS))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO EXISTE LA CLASE [$CLASS].";
            return false;
        }

        return $CLASS;
    }


    /**
    * Metodo para obtener la informacion de los documentos creados en un lapso contable.
    *
    * @param string $empresa_id
    * @param string $lapso
    * @return array
    * @access public
    */
    function GetInformacionDocumentosLapso($empresa_id,$lapso)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql= "SELECT prefijo,descripcion FROM public.documentos WHERE empresa_id = '$empresa_id' AND sw_estado = '1';";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $DESCRIPCION_DOC=array();
        while($file = $result->FetchRow())
        {
            $DESCRIPCION_DOC[$file['prefijo']]=$file['descripcion'];
        }
        $result->Close();


        //SELECCIONAR LOS TIPOS DE DOCUMENTO DEL SISTEMA ACTIVOS.
        $sql="
                SELECT DISTINCT
                    a.tipo_doc_general_id,
                    a.descripcion
                FROM
                    public.tipos_doc_generales as a,
                    public.documentos as b
                WHERE
                    a.sw_doc_sistema = '1'
                    AND b.empresa_id = '$empresa_id'
                    AND b.tipo_doc_general_id = a.tipo_doc_general_id
                    AND b.sw_contabiliza = '1'
                    AND b.sw_estado = '1'
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

        $salidas = array();

        //POR CADA TIPO DE DOCUMENTO ACTIVO
        while($fila = $result->FetchRow())
        {
            $sql = "
                    SELECT
                    prefijo,
                    COUNT(*) as cantidad,
                    MIN(numero) as numero_inicial,
                    MAX(numero) as numero_final,
                    ABS(COUNT(*) - (MAX(numero) -  MIN(numero) + 1)) as huecos
                    FROM cg_mov_$empresa_id.\"cg_mov_contable_$empresa_id"."_".$fila['tipo_doc_general_id']."\"
                    WHERE prefijo IN (SELECT DISTINCT prefijo FROM public.documentos WHERE sw_contabiliza = '1' AND sw_estado = '1' AND tipo_doc_general_id = '".$fila['tipo_doc_general_id']."')
                    AND lapso = '$lapso'
                    GROUP BY prefijo
                    ORDER BY prefijo
            ";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $res_doc = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if($dbconn->ErrorNo() != 0)
            {
                $salidas[$fila['tipo_doc_general_id']] = "ERROR 1 : ".$dbconn->ErrorMsg();
            }
            elseif(!$res_doc->EOF)
            {
                while($fila2 = $res_doc->FetchRow())
                {
                    $salidas[$fila['tipo_doc_general_id']][$fila2['prefijo']] = $fila2;

                    $sql = "SELECT COUNT(*) as sin_contabilizar FROM cg_mov_$empresa_id.\"cg_mov_contable_$empresa_id"."_".$fila['tipo_doc_general_id']."\"
                            WHERE prefijo = '".$fila2['prefijo']."'
                            AND lapso = '$lapso'
                            AND documento_contable_id IS NULL; ";

                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $res_sin = $dbconn->Execute($sql);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                    if($dbconn->ErrorNo() != 0)
                    {
                        $salidas[$fila['tipo_doc_general_id']] = "ERROR 2 : ".$dbconn->ErrorMsg();
                    }
                    else
                    {
                        $fila3 = $res_sin->FetchRow();
                        $salidas[$fila['tipo_doc_general_id']][$fila2['prefijo']]['descuadrados']=0;
                        $salidas[$fila['tipo_doc_general_id']][$fila2['prefijo']]['sin_contabilizar']=$fila3['sin_contabilizar'];
                        $salidas[$fila['tipo_doc_general_id']][$fila2['prefijo']]['descripcion']= $DESCRIPCION_DOC[$fila2['prefijo']];
                        $res_sin->Close();

                        $sql = "SELECT COUNT(*) as descuadrados FROM cg_mov_$empresa_id.\"cg_mov_contable_$empresa_id"."_".$fila['tipo_doc_general_id']."\"
                                WHERE prefijo = '".$fila2['prefijo']."'
                                AND lapso = '$lapso'
                                AND sw_estado = '1'; ";

                        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                        $res_sin = $dbconn->Execute($sql);
                        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                        if($dbconn->ErrorNo() != 0)
                        {
                            $salidas[$fila['tipo_doc_general_id']] = "ERROR 3 : ".$dbconn->ErrorMsg();
                        }
                        else
                        {
                            $fila4 = $res_sin->FetchRow();
                            $salidas[$fila['tipo_doc_general_id']][$fila2['prefijo']]['descuadrados']=$fila4['descuadrados'];
                            $res_sin->Close();
                        }
                    }
                }
                $res_doc->Close();
            }
        }
        $result->Close();

        $sql = "SELECT
                    prefijo,
                    COUNT(*) as cantidad,
                    MIN(numero) as numero_inicial,
                    MAX(numero) as numero_final,
                    (COUNT(*) - (MAX(numero) -  MIN(numero) + 1)) as huecos
                FROM cg_mov_$empresa_id.\"cg_mov_contable_$empresa_id\"
                WHERE prefijo IN (
                    SELECT DISTINCT
                        b.prefijo
                    FROM
                        public.tipos_doc_generales as a,
                        public.documentos as b
                    WHERE
                        a.sw_doc_sistema = '0'
                        AND b.empresa_id = '$empresa_id'
                        AND b.tipo_doc_general_id = a.tipo_doc_general_id
                        AND b.sw_contabiliza = '1'
                        AND b.sw_estado = '1'
                )
                AND lapso = '$lapso'
                GROUP BY prefijo;";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        while($fila = $result->FetchRow())
        {
            $salidas['MANUALES'][$fila['prefijo']] = $fila;
            $salidas['MANUALES'][$fila['prefijo']]['sin_contabilizar'] = 0;
            $salidas['MANUALES'][$fila['prefijo']]['descripcion']= $DESCRIPCION_DOC[$fila['prefijo']];
        }

        return $salidas;
    }


}

?>