<?php
/**
* $Id: BodegasDocumentos.class.php,v 1.4 2011/06/14 20:07:27 mauricio Exp $
*/

/**
* Clase para la consulta y generacion de documentos de bodega
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.4 $
* @package SIIS
*/
class BodegasDocumentos
{

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
    * Tipo de documento de bodega
    *
    * @var string
    * @access private
    */
    var $bodegas_doc_id;


    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/


    /**
    * Constructor
    *
    * @param $bodegas_doc_id (opcional)
    * @return boolean
    * @access public
    */
    function BodegasDocumentos($bodegas_doc_id)
    {
        $this->bodegas_doc_id = $bodegas_doc_id;
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
    * Metodo para establecer el tipo de documento de bodega.
    *
    * @param integer $bodegas_doc_id (opcional)
    * @return boolean
    * @access public
    */
    function SetTipoDoc($bodegas_doc_id)
    {
        $this->bodegas_doc_id = $bodegas_doc_id;
        return true;
    }


    /**
    * Metodo para obtener informacion de un tipo de documento especifico.
    *
    * @param integer $bodegas_doc_id (opcional)
    * @return array
    * @access public
    */
    function GetInfoBodegaDocumento($bodegas_doc_id=null)
    {
        if(empty($bodegas_doc_id))
        {
            if(empty($this->bodegas_doc_id))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "PARAMETRO [bodegas_doc_id] ES REQUERIDO.";
                return false;
            }
            $bodegas_doc_id = $this->bodegas_doc_id;
        }
        else
        {
            $this->SetTipoDoc($bodegas_doc_id);
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT
                    c.inv_tipo_movimiento as tipo_movimiento,
                    b.tipo_doc_general_id as tipo_doc_bodega_id,
                    c.descripcion as tipo_clase_documento,
                    b.prefijo,
                    b.descripcion,
                    a.empresa_id,
                    a.centro_utilidad,
                    a.bodega

                FROM
                    inv_bodegas_documentos as a,
                    documentos as b,
                    tipos_doc_generales as c
                WHERE
                    a.bodegas_doc_id = $bodegas_doc_id
                    AND b.documento_id = a.documento_id
                    AND b.empresa_id = a.empresa_id
                    AND c.tipo_doc_general_id = b.tipo_doc_general_id
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
            $this->mensajeDeError = "NO EXISTE EL DOCUMENTO DE BODEGA [$bodegas_doc_id] EN [public.inv_bodegas_documentos].";
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        return  $retorno;
    }



    /**
    * Metodo para obtener un objeto del tipo de documento especifico.
    *
    * @param integer $bodegas_doc_id (opcional si ya esta configurado)
    * @return object
    * @access public
    */
    function GetOBJ($bodegas_doc_id=NULL)
    {
        
       
        if(empty($bodegas_doc_id))
        {
            if(empty($this->bodegas_doc_id))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "PARAMETRO [bodegas_doc_id] ES REQUERIDO.";
                return false;
            }
            $bodegas_doc_id = $this->bodegas_doc_id;
        }
        else
        {
            $this->SetTipoDoc($bodegas_doc_id);
        }

        if (!IncludeClass('BodegasDocumentosComun', 'BodegasDocumentos'))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE PUDO INCLUIR LA CLASE [BodegasDocumentosComun].";
            return false;
        }

        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        $sql = "SELECT b.tipo_doc_general_id as tipo_doc_bodega_id
                FROM
                    inv_bodegas_documentos as a,
                    documentos as b
                WHERE a.bodegas_doc_id = ".$this->bodegas_doc_id."
                AND b.documento_id = a.documento_id
                AND b.empresa_id = a.empresa_id;";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            echo $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            echo $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        elseif($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO EXISTE EL DOCUMENTO DE BODEGA [".$this->bodegas_doc_id."] EN [public.inv_bodegas_documentos].";
            return false;
        }

        list($tipo_doc_bodega_id)=$result->FetchRow();
        $result->Close();

        $CLASS = "tipo_doc_bodega_".$tipo_doc_bodega_id;

        if (!IncludeClass("TIPOSDOC/".$CLASS, 'BodegasDocumentos'))
        {
            $CLASS = "BodegasDocumentosComun";
        }

        if(!class_exists($CLASS))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO EXISTE LA CLASE [$CLASS].";
            return false;
        }

        $OBJ = new $CLASS($this->bodegas_doc_id);

        if(!is_object($OBJ))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO SE PUDO CREAR EL OBJETO [$CLASS]";
            return false;
        }
        //print_r($CLASS);
        return $OBJ;
    }



    /**
    * Metodo para obtener informacion de un documento temporal de un usuario en una bodega.
    *
    * @param integer $doc_tmp_id identificador del documento temporal
    * @param integer $usuario_id (opcional) usuario creador del documento temporal, por defecto el usuario en sesion.
    * @return array
    * @access public
    */
    function GetInfoBodegaDocumentoTMP($doc_tmp_id, $usuario_id=null)
    {
        if(empty($doc_tmp_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTA EL ARGUMENTO doc_tmp_id[$doc_tmp_id].";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
	
        $sql="SELECT
                t.*,
                c.inv_tipo_movimiento as tipo_movimiento,
                b.tipo_doc_general_id as tipo_doc_bodega_id,
                c.descripcion as tipo_clase_documento,
                b.prefijo,
                b.descripcion,
                a.empresa_id,
                a.centro_utilidad,
                a.bodega

            FROM
                inv_bodegas_movimiento_tmp as t,
                inv_bodegas_documentos as a,
                documentos as b,
                tipos_doc_generales as c
            WHERE
                doc_tmp_id = $doc_tmp_id
                AND usuario_id = $usuario_id
                AND a.bodegas_doc_id = t.bodegas_doc_id
                AND b.documento_id = a.documento_id
                AND b.empresa_id = a.empresa_id
                AND c.tipo_doc_general_id = b.tipo_doc_general_id
        ";
		/*print_r($sql);*/
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        return  $retorno;
    }



    /**
    * Metodo para obtener los documentos temporales de un usuario en una bodega.
    *
    * @param string $empresa_id Identificador de la bodega
    * @param string $centro_utilidad Identificador de la bodega
    * @param integer $bodega Identificador de la bodega
    * @param integer $usuario_id (opcional) usuario creador de los documentos temporales, por defecto el usuario en sesion.
    * @return array
    * @access public
    */
    function GetDocumentosTMP_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id=null)
    {
        if(empty($empresa_id) || empty($centro_utilidad) || empty($bodega))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTAN ARGUMENTOS";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql=" SELECT
                    t.*,
                    c.inv_tipo_movimiento as tipo_movimiento,
                    b.tipo_doc_general_id as tipo_doc_bodega_id,
                    c.descripcion as tipo_clase_documento,
                    b.prefijo,
                    b.descripcion,
                    a.empresa_id,
                    a.centro_utilidad,
                    a.bodega

                FROM
                    inv_bodegas_movimiento_tmp as t,
                    inv_bodegas_documentos as a,
                    documentos as b,
                    tipos_doc_generales as c
                WHERE
                    t.usuario_id = $usuario_id
                    AND a.bodegas_doc_id = t.bodegas_doc_id
                    AND a.empresa_id = '$empresa_id'
                    AND a.centro_utilidad = '$centro_utilidad'
                    AND a.bodega = '$bodega'
                    AND b.documento_id = a.documento_id
                    AND b.empresa_id = a.empresa_id
                    AND c.tipo_doc_general_id = b.tipo_doc_general_id
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

        $retorno = array();

        while($fila = $result->FetchRow())
        {
            $retorno[$fila['tipo_movimiento']][$fila['tipo_doc_bodega_id']][$fila['doc_tmp_id']]=$fila;
        }
        $result->Close();

        return  $retorno;
    }


    /**
    * Metodo para obtener las bodegas en las que tiene permiso el usuario de trabajar.
    *
    * @param string $empresa_id empresa en la que se van a buscar las bodegas del usuario.
    * @param integer $usuario_id (opcional)
    * @return array Informacion de las bodegas.
    * @access public
    */
    function GetBodegasUsuario($empresa_id, $usuario_id=null)
    {
        if(empty($empresa_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTA ARGUMENTO [empresa_id]";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $sql = "SELECT
                    a.*,
                    b.descripcion as nom_bodega
                FROM
                (
                    SELECT DISTINCT
                        empresa_id,
                        centro_utilidad,
                        bodega
                    FROM inv_bodegas_userpermisos
                    WHERE usuario_id = $usuario_id AND empresa_id = '$empresa_id'
                ) as a,
                bodegas as b
                WHERE b.empresa_id=a.empresa_id
                AND  b.centro_utilidad =a.centro_utilidad
                AND  b.bodega = a.bodega
                ORDER BY nom_bodega
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

        $retorno = array();

        while($fila = $result->FetchRow())
        {
            $retorno[]=$fila;
        }
        $result->Close();

        return  $retorno;
    }

    function  ModuloGetVar($app,$modulo,$variable){
         
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $sql = "SELECT
                valor
                FROM
                system_modulos_variables
                where
                modulo= '$modulo'
                and 
                modulo_tipo='$app'
                and 
                variable='$variable'
                
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

        $retorno = array();

        while($fila = $result->FetchRow())
        {
            $retorno[]=$fila;
        }
        $result->Close();

        return  $retorno[0]['valor'];
    }

    /**
    * Metodo para obtener las bodegas en las que tiene permiso el usuario de trabajar.
    *
    * @param string $empresa_id empresa en la que se van a buscar las bodegas del usuario.
    * @param integer $usuario_id (opcional)
    * @return array Informacion de las bodegas.
    * @access public
    */
    function GetTipoDocumentosUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id=null)
    {
        if(empty($empresa_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTA ARGUMENTO [empresa_id]";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }
            
        $bloquear_prefijo= ModuloGetVar('app','ParametrizacionDocumentosBode','bloquear_prefijo');
        
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $sql = "SELECT
                    c.inv_tipo_movimiento as tipo_movimiento,
                    a.bodegas_doc_id,
                    b.tipo_doc_general_id as tipo_doc_bodega_id,
                    c.descripcion as tipo_clase_documento,
                    b.prefijo,
                    b.descripcion
                FROM
                    (
                        SELECT
                            documento_id
                        FROM
                            inv_bodegas_userpermisos
                        WHERE
                            usuario_id = $usuario_id
                            AND empresa_id = '$empresa_id'
                            AND centro_utilidad = '$centro_utilidad '
                            AND bodega = '$bodega'
                    ) AS u,
                    inv_bodegas_documentos as a,
                    documentos as b,
                    tipos_doc_generales as c
                WHERE
                a.documento_id = u.documento_id
                AND a.empresa_id = '$empresa_id'
                AND a.centro_utilidad = '$centro_utilidad'
                AND a.bodega = '$bodega'
                AND b.documento_id = a.documento_id
                AND b.empresa_id = a.empresa_id
                and b.prefijo not in (".$bloquear_prefijo.")
                AND c.tipo_doc_general_id = b.tipo_doc_general_id
                ORDER BY tipo_movimiento, tipo_doc_bodega_id
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

        $retorno = array();

        while($datos=$result->FetchRow($ToUpper = false))
        {
          $retorno[$datos['tipo_clase_documento']][]=$datos;
        }

        $result->Close();
        return $retorno;

    }


    /**
    * Metodo para obtener los documentos de traslado para reposicion automatica de un usuario en una bodega.
    *
    * @param string $empresa_id Identificador de la bodega
    * @param string $centro_utilidad Identificador de la bodega
    * @param integer $bodega Identificador de la bodega
    * @param integer $usuario_id (opcional) usuario creador de los documentos, por defecto el usuario en sesion.
    * @return array
    * @access public
    */
    function GetDocumentosReposicion($empresa_id, $centro_utilidad, $bodega, $usuario_id=null)
    {
        if(empty($empresa_id) || empty($centro_utilidad) || empty($bodega))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTAN ARGUMENTOS";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT
                    c.inv_tipo_movimiento as tipo_movimiento,
                    a.bodegas_doc_id,
                    b.tipo_doc_general_id as tipo_doc_bodega_id,
                    c.descripcion as tipo_clase_documento,
                    b.prefijo,
                    b.descripcion
                FROM
                    (
                        SELECT
                            documento_id
                        FROM
                            inv_bodegas_userpermisos
                        WHERE
                            usuario_id = $usuario_id
                            AND empresa_id = '$empresa_id'
                            AND centro_utilidad = '$centro_utilidad '
                            AND bodega = '$bodega'
                    ) AS u,
                    inv_bodegas_documentos as a,
                    documentos as b,
                    tipos_doc_generales as c
                WHERE
                a.documento_id = u.documento_id
                AND a.empresa_id = '$empresa_id'
                AND a.centro_utilidad = '$centro_utilidad'
                AND a.bodega = '$bodega'
                AND b.documento_id = a.documento_id
                AND b.empresa_id = a.empresa_id
                AND c.tipo_doc_general_id = b.tipo_doc_general_id
                AND b.tipo_doc_general_id = 'T001'
                ORDER BY tipo_movimiento, tipo_doc_bodega_id
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

        $retorno = array();

        while($datos=$result->FetchRow($ToUpper = false))
        {
          $retorno[$datos['tipo_clase_documento']][]=$datos;
        }

        $result->Close();
        return $retorno;

    }

        /**
    * Metodo para obtener los documentos de una empresa filtrados por bodega.
    *
    * @param string $empresa_id I
    * @param string $centro de utilidad
    * @param string $bodega
    * @param date $fecha_inicial
    * @param date $fecha_final
    * @return array
    * @access public
    */
    function GetDocumentosByBodega($empresa_id, $centro, $bodega, $fecha_inicial=null, $fecha_final=null)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $filtro_inicial = '';
        $filtro_inicial = '';

        if(!empty($fecha_inicial))
        {
            $filtro_inicial = " AND m.fecha_registro::date >= '$fecha_inicial' ";
        }

        if(!empty($fecha_final))
        {
            $filtro_final = " AND m.fecha_registro::date <= '$fecha_final' ";
        }

        $sql="
                SELECT
                    m.empresa_id,
                    m.centro_utilidad,
                    m.bodega,
                    g.descripcion as nom_bodega,
                    c.inv_tipo_movimiento as tipo_movimiento,
                    b.tipo_doc_general_id as tipo_doc_bodega_id,
                    c.descripcion as tipo_clase_documento,
                    b.documento_id,
                    b.prefijo,
                    b.descripcion,
                    count(*) AS numero_documentos
                FROM
                    inv_bodegas_movimiento as m,
                    inv_bodegas_documentos as a,
                    documentos as b,
                    bodegas as g,
                    tipos_doc_generales as c,
                    inv_bodegas_userpermisos_admin as x
                WHERE
                    a.documento_id = m.documento_id
                    $filtro_inicial
                    $filtro_final
                    AND a.empresa_id = m.empresa_id
                    AND m.centro_utilidad ='$centro'
                    AND a.centro_utilidad = m.centro_utilidad
                    AND a.bodega = m.bodega
                    AND m.bodega ='$bodega'
                    AND b.documento_id = a.documento_id
                    AND b.empresa_id = a.empresa_id
                    AND c.tipo_doc_general_id = b.tipo_doc_general_id
                    AND a.bodega = g.bodega
                    AND a.empresa_id = g.empresa_id
                    AND a.centro_utilidad = g.centro_utilidad
                    AND x.usuario_id ='".UserGetUID()."'
                    AND a.bodega = x.bodega
                    AND a.empresa_id = x.empresa_id
                    AND a.centro_utilidad = x.centro_utilidad
                    GROUP BY 1,2,3,4,5,6,7,8,9,10
                    ORDER BY 1,2,3,5,6";

       
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
            $retorno[]=$fila;
         }
        $result->Close();
        return  $retorno;
    }

    /**
    * Metodo para obtener los documentos de una empresa
    *
    * @param string $empresa_id I
    * @param date $fecha_inicial
    * @param date $fecha_final
    * @return array
    * @access public
    */
    function GetDocumentosEmpresa($empresa_id, $fecha_inicial=null, $fecha_final=null)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $filtro_inicial = '';
        $filtro_inicial = '';

		 if(!empty($empresa_id))
        {
            $filtro = " AND m.empresa_id = '".trim($empresa_id)."' ";
        }
		
        if(!empty($fecha_inicial))
        {
            $filtro_inicial = " AND m.fecha_registro::date >= '".$fecha_inicial."' ";
        }

        if(!empty($fecha_final))
        {
            $filtro_final = " AND m.fecha_registro::date <= '".$fecha_final."' ";
        }

        $sql="
                SELECT
                    m.empresa_id,
                    m.centro_utilidad,
                    m.bodega,
                    g.descripcion as nom_bodega,
                    c.inv_tipo_movimiento as tipo_movimiento,
                    b.tipo_doc_general_id as tipo_doc_bodega_id,
                    c.descripcion as tipo_clase_documento,
                    b.documento_id,
                    b.prefijo,
                    b.descripcion,
                    count(*) AS numero_documentos
                FROM
                    inv_bodegas_movimiento as m,
                    inv_bodegas_documentos as a,
                    documentos as b,
                    bodegas as g,
                    tipos_doc_generales as c
                WHERE
                    a.documento_id = m.documento_id
					$filtro
                    $filtro_inicial
                    $filtro_final
					
                    AND a.empresa_id = m.empresa_id
                    AND a.centro_utilidad = m.centro_utilidad
                    AND a.bodega = m.bodega
                    AND b.documento_id = a.documento_id
                    AND b.empresa_id = a.empresa_id
                    AND c.tipo_doc_general_id = b.tipo_doc_general_id
                    AND a.bodega = g.bodega
                    AND a.empresa_id = g.empresa_id
                    AND a.centro_utilidad = g.centro_utilidad
                    GROUP BY 1,2,3,4,5,6,7,8,9,10
                    ORDER BY 1,2,3,5,6";

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
            $retorno[]=$fila;
         }
        $result->Close();
        return  $retorno;
    }



    /**
    * Metodo para obtener un tipo de documento de una empresa
    *
    * @param string $empresa_id
    * @param integer $documento_id
    * @param date $fecha_inicial
    * @param date $fecha_final
    * @param boolean $count (opcional) True/False indica si retorna el numero de registros o los registros
    * @param integer $limit (opcional) limite de registros que retorna la consulta
    * @param integer $offset (opcional) desde que registro inicia la consulta
    * @return array
    * @access public
    */
    function GetTipoDocumento($empresa_id, $documento_id, $fecha_inicial=null, $fecha_final=null, $count=null, $limit=null, $offset=null)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        if(empty($count))
        {
            $select = "*";
        }
        else
        {
            $select = " COUNT(*) as cantidad";
        }

        if(is_numeric($limit) && is_numeric($offset))
        {
            $filtro_limit = " LIMIT $limit OFFSET $offset ";
        }
        else
        {
            $filtro_limit = "";
        }

        $filtro_inicial = '';
        $filtro_final = '';

        if(!empty($fecha_inicial))
        {
            $filtro_inicial = " AND m.fecha_registro::date >= '$fecha_inicial' ";
        }

        if(!empty($fecha_final))
        {
            $filtro_final = " AND m.fecha_registro::date <= '$fecha_final' ";
        }

        $sql="  SELECT $select
                FROM
                (
                    SELECT
                        m.*,
                        u.nombre,
                        u.usuario,
                        m.fecha_registro::date as fecha_documento,
                        g.descripcion as nom_bodega

                    FROM
                        inv_bodegas_movimiento as m,
                        system_usuarios as u,
                        bodegas as g
                    WHERE
                        m.documento_id = $documento_id
                        $filtro_inicial
                        $filtro_final
                        AND m.bodega = g.bodega
                        AND m.empresa_id = g.empresa_id
                        AND m.centro_utilidad = g.centro_utilidad
                        AND m.usuario_id = u.usuario_id
                        ORDER BY m.prefijo, m.numero
                        $filtro_limit
                ) as x

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

        if(empty($count))
        {
            $retorno = array();

            while($fila = $result->FetchRow())
            {
                $retorno[]=$fila;
            }
            $result->Close();
        }
        else
        {
            $fila = $result->FetchRow();
            $retorno = $fila['cantidad'];
        }

        return  $retorno;

    }



    /**
    * Metodo para obtener los documentos de una empresa
    *
    * @param string $empresa_id I
    * @param date $fecha_inicial
    * @param date $fecha_final
    * @return array
    * @access public
    */
    function GetDocumentosBodega($empresa_id, $centro_utilidad, $bodega, $fecha_inicial=null, $fecha_final=null)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql="  SELECT $select
                FROM
                (
                    SELECT
                        m.*,
                        c.inv_tipo_movimiento as tipo_movimiento,
                        b.tipo_doc_general_id as tipo_doc_bodega_id,
                        c.descripcion as tipo_clase_documento,
                        b.descripcion
                    FROM
                        inv_bodegas_movimiento as m,
                        inv_bodegas_documentos as a,
                        documentos as b,
                        tipos_doc_generales as c
                    WHERE
                        m.empresa_id = '$empresa_id'
                        AND m.centro_utilidad = '$centro_utilidad'
                        AND m.bodega = '$bodega'
                        AND a.documento_id = m.documento_id
                        AND a.empresa_id = m.empresa_id
                        AND a.centro_utilidad = m.centro_utilidad
                        AND a.bodega = m.bodega
                        AND b.documento_id = a.documento_id
                        AND b.empresa_id = a.empresa_id
                        AND c.tipo_doc_general_id = b.tipo_doc_general_id
                        $filtro
                        $filtro_orderby
                        $filtro_limit
                ) as x;
        ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    }










    /**
    * Metodo para obtener los documentos  de un usuario en una bodega.
    *
    * @param string $empresa_id Identificador de la bodega
    * @param string $centro_utilidad Identificador de la bodega
    * @param integer $bodega Identificador de la bodega
    * @param integer $usuario_id (opcional) usuario creador de los documentos, por defecto el usuario en sesion.
    * @param boolean $count (opcional) True/False indica si retorna el numero de registros o los registros
    * @param integer $limit (opcional) limite de registros que retorna la consulta
    * @param integer $offset (opcional) desde que registro inicia la consulta
    * @param string $tipo_movimiento (opcional) filtro por tipo de movimiento (I,E,T,D,C)
    * @param string $tipo_doc_bodega_id (opcional) filtro por tipo de documento general
    * @param integer $documento_id (opcional) filtro por un documento (tabla documentos)
    * @return array
    * @access public
    */
    function GetDocumentos_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id=null, $count=null, $limit=null, $offset=null, $tipo_movimiento=null, $tipo_doc_bodega_id=null, $orderby = 'DESC')
    {
        if(empty($empresa_id) || empty($centro_utilidad) || empty($bodega))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTAN ARGUMENTOS";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        $filtro = '';

        if(!empty($tipo_movimiento))
        {
            $filtro .= " AND c.inv_tipo_movimiento = '$tipo_movimiento'";
        }

        if(!empty($tipo_doc_bodega_id))
        {
            $filtro .= " AND b.tipo_doc_general_id = '$tipo_doc_bodega_id'";
        }

        if(empty($count))
        {
            $select = "*";
        }
        else
        {
            $select = " COUNT(*) as cantidad";
        }

        if(is_numeric($limit) && is_numeric($offset))
        {
            $filtro_limit = " LIMIT $limit OFFSET $offset ";
        }
        else
        {
            $filtro_limit = "";
        }

        if($orderby)
        {
            $filtro_orderby = " ORDER BY m.fecha_registro $orderby";
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql="  SELECT $select
                FROM
                (
                    SELECT
                        m.*,
                        c.inv_tipo_movimiento as tipo_movimiento,
                        b.tipo_doc_general_id as tipo_doc_bodega_id,
                        c.descripcion as tipo_clase_documento,
                        b.descripcion
                    FROM
                        inv_bodegas_movimiento as m,
                        inv_bodegas_documentos as a,
                        documentos as b,
                        tipos_doc_generales as c
                    WHERE
                        --m.usuario_id = $usuario_id
                        --AND
            m.empresa_id = '$empresa_id'
                        AND m.centro_utilidad = '$centro_utilidad'
                        AND m.bodega = '$bodega'
                        AND a.documento_id = m.documento_id
                        AND a.empresa_id = m.empresa_id
                        AND a.centro_utilidad = m.centro_utilidad
                        AND a.bodega = m.bodega
                        AND b.documento_id = a.documento_id
                        AND b.empresa_id = a.empresa_id
                        AND c.tipo_doc_general_id = b.tipo_doc_general_id
                        $filtro
                        $filtro_orderby
                        $filtro_limit
                ) as x;
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

        if(empty($count))
        {
            $retorno = array();

            while($fila = $result->FetchRow())
            {
                $retorno[$fila['tipo_movimiento']][$fila['tipo_doc_bodega_id']][]=$fila;
            }
            $result->Close();
        }
        else
        {
            $fila = $result->FetchRow();
            $retorno = $fila['cantidad'];
        }

        return  $retorno;
    }


    /**
    * Metodo para obtener los tipos de movimiento (I/E/T/D/C) que ha creado usuario en una bodega.
    *
    * @param string $empresa_id Identificador de la bodega
    * @param string $centro_utilidad Identificador de la bodega
    * @param integer $bodega Identificador de la bodega
    * @param integer $usuario_id (opcional) usuario creador de los documentos, por defecto el usuario en sesion.
    * @return array
    * @access public
    */
    function GetTiposMovimiento_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id=null)
    {
        if(empty($empresa_id) || empty($centro_utilidad) || empty($bodega))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTAN ARGUMENTOS";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql="
                    SELECT DISTINCT
                        c.inv_tipo_movimiento as tipo_movimiento
                    FROM
                        inv_bodegas_movimiento as m,
                        inv_bodegas_documentos as a,
                        documentos as b,
                        tipos_doc_generales as c
                    WHERE
                        --m.usuario_id = $usuario_id
                        --AND
            m.empresa_id = '$empresa_id'
                        AND m.centro_utilidad = '$centro_utilidad'
                        AND m.bodega = '$bodega'
                        AND a.documento_id = m.documento_id
                        AND a.empresa_id = m.empresa_id
                        AND a.centro_utilidad = m.centro_utilidad
                        AND a.bodega = m.bodega
                        AND b.documento_id = a.documento_id
                        AND b.empresa_id = a.empresa_id
                        AND c.tipo_doc_general_id = b.tipo_doc_general_id;
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

        $retorno = array();

        while($fila = $result->FetchRow())
        {
            $retorno[$fila['tipo_movimiento']]=$fila;
        }
        $result->Close();

        return $retorno;
    }



    /**
    * Metodo para obtener los tipos de documentos por un tipo de movimiento que ha creado usuario en una bodega.
    *
    * @param string $empresa_id Identificador de la bodega
    * @param string $centro_utilidad Identificador de la bodega
    * @param string $bodega Identificador de la bodega
    * @param string $tipo_movimiento Tipo de movimiento
    * @param integer $usuario_id (opcional) usuario creador de los documentos, por defecto el usuario en sesion.
    * @return array
    * @access public
    */
    function GetTiposDocumentos_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $tipo_movimiento, $usuario_id=null)
    {
        if(empty($empresa_id) || empty($centro_utilidad) || empty($bodega) || empty($tipo_movimiento))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTAN ARGUMENTOS";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql="
                    SELECT DISTINCT
                        b.tipo_doc_general_id as tipo_doc_bodega_id,
                        c.descripcion as tipo_clase_documento
                    FROM
                        inv_bodegas_movimiento as m,
                        inv_bodegas_documentos as a,
                        documentos as b,
                        tipos_doc_generales as c
                    WHERE
                        --m.usuario_id = $usuario_id
                        --AND
            m.empresa_id = '$empresa_id'
                        AND m.centro_utilidad = '$centro_utilidad'
                        AND m.bodega = '$bodega'
                        AND a.documento_id = m.documento_id
                        AND a.empresa_id = m.empresa_id
                        AND a.centro_utilidad = m.centro_utilidad
                        AND a.bodega = m.bodega
                        AND b.documento_id = a.documento_id
                        AND b.empresa_id = a.empresa_id
                        AND c.tipo_doc_general_id = b.tipo_doc_general_id
                        AND c.inv_tipo_movimiento = '$tipo_movimiento';
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

        $retorno = array();

        while($fila = $result->FetchRow())
        {
            $retorno[$fila['tipo_doc_bodega_id']]=$fila['tipo_clase_documento'];
        }
        $result->Close();

        return $retorno;
    }


    /**
    * Metodo para obtener un documento de bodega.
    *
    * @param string $empresa_id identificador del documento
    * @param string $prefijo
    * @param integer $numero
    * @param boolean $detalle TRUE devuelve un vector con el detalle(items) del documento
    * @return array datos del documento consultado.
    * @access public
    */
    function GetDoc($empresa_id,$prefijo,$numero,$detalle=true)
    {
        if(empty($empresa_id) || empty($prefijo) || empty($numero))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETROS REQUERIDOS [empresa_id,prefijo,numero].";
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        /*$sql="  SELECT
                    m.*,
                    c.inv_tipo_movimiento as tipo_movimiento,
                    b.tipo_doc_general_id as tipo_doc_bodega_id,
                    c.descripcion as tipo_clase_documento,
                    b.descripcion
                FROM
                    inv_bodegas_movimiento as m,
                    inv_bodegas_documentos as a,
                    documentos as b,
                    tipos_doc_generales as c
                WHERE
                    m.empresa_id = '$empresa_id'
                    AND m.prefijo = '$prefijo'
                    AND m.numero = $numero
                    AND a.documento_id = m.documento_id
                    AND a.empresa_id = m.empresa_id
                    AND a.centro_utilidad = m.centro_utilidad
                    AND a.bodega = m.bodega
                    AND b.documento_id = a.documento_id
                    AND b.empresa_id = a.empresa_id
                    AND c.tipo_doc_general_id = b.tipo_doc_general_id;
        ";*/
				
		$sql="  select
y.documento_id,	y.empresa_id,	y.centro_utilidad,	y.bodega,	y.prefijo,	y.numero,
y.observacion,	y.sw_estado,	y.usuario_id,	y.fecha_registro,	y.total_costo, y.abreviatura,
y.empresa_destino,	y.sw_verificado,	y.porcentaje_rtf,	y.porcentaje_ica,	y.porcentaje_reteiva,	y.tipo_movimiento,	
y.tipo_doc_bodega_id,	y.tipo_clase_documento,	y.descripcion,	list(y.obs_pedido) as obs_pedido
from(
(SELECT
                    m.*,
                    c.inv_tipo_movimiento as tipo_movimiento,
                    b.tipo_doc_general_id as tipo_doc_bodega_id,
                    c.descripcion as tipo_clase_documento,
                    b.descripcion,
		    vop.observacion as obs_pedido
                FROM
                    inv_bodegas_movimiento as m
JOIN inv_bodegas_documentos as a
ON a.documento_id = m.documento_id
                    AND a.empresa_id = m.empresa_id
                    AND a.centro_utilidad = m.centro_utilidad
                    AND a.bodega = m.bodega
   JOIN         documentos as b
ON b.documento_id = a.documento_id
                    AND b.empresa_id = a.empresa_id
JOIN                    tipos_doc_generales as c                    
ON c.tipo_doc_general_id = b.tipo_doc_general_id


left join inv_bodegas_movimiento_despachos_clientes as dc
ON m.empresa_id = dc.empresa_id
                    AND m.prefijo = dc.prefijo
                    AND m.numero = dc.numero
Left JOIN ventas_ordenes_pedidos vop
ON dc.pedido_cliente_id = vop.pedido_cliente_id


 WHERE 
                    m.empresa_id = '$empresa_id'
                    AND m.prefijo = '$prefijo'
                    AND m.numero = $numero
)
UNION(
SELECT
                    m.*,
                    c.inv_tipo_movimiento as tipo_movimiento,
                    b.tipo_doc_general_id as tipo_doc_bodega_id,
                    c.descripcion as tipo_clase_documento,
                    b.descripcion,
		    sp.observacion as obs_pedido
                FROM
                    inv_bodegas_movimiento as m
JOIN inv_bodegas_documentos as a
ON a.documento_id = m.documento_id
                    AND a.empresa_id = m.empresa_id
                    AND a.centro_utilidad = m.centro_utilidad
                    AND a.bodega = m.bodega
   JOIN         documentos as b
ON b.documento_id = a.documento_id
                    AND b.empresa_id = a.empresa_id
JOIN                    tipos_doc_generales as c                    
ON c.tipo_doc_general_id = b.tipo_doc_general_id

Left join inv_bodegas_movimiento_despachos_farmacias as df
on m.empresa_id = df.empresa_id
                    AND m.prefijo = df.prefijo
                    AND m.numero = df.numero

left JOIN public.solicitud_productos_a_bodega_principal as sp
ON  df.solicitud_prod_a_bod_ppal_id = sp.solicitud_prod_a_bod_ppal_id
               


 WHERE 
                    m.empresa_id = '$empresa_id'
                    AND m.prefijo = '$prefijo'
                    AND m.numero = $numero

)                 ) as y
group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21;
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
            $this->mensajeDeError = "EL DOCUMENTO NO EXISTE.";
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        $retorno['DATOS_ADICIONALES'] = $this->GetDocDatosAdicionales($empresa_id,$prefijo,$numero,$retorno['tipo_doc_bodega_id']);

        if($detalle)
        {
            $retorno['DETALLE'] = $this->GetDocDetalle($empresa_id,$prefijo,$numero);
        }


        return  $retorno;
    }

    /**
    * Metodo para obtener un documento de bodega del modelo anterior.
    *
    * @param integer $bodegas_doc_id
    * @param boolean $detalle TRUE devuelve un vector con el detalle(items) del documento
    * @return array datos del documento consultado.
    * @access public
    */
    function GetDoc_ModeloAnterior($bodegas_doc_id,$numero,$detalle=true)
    {
        if(empty($bodegas_doc_id) || empty($numero))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETROS REQUERIDOS [bodegas_doc_id, numero].";
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql="  SELECT
                    a.*,
                    a.numeracion as numero,
                    b.prefijo,
                    b.empresa_id,
                    b.centro_utilidad,
                    b.bodega,
                    b.tipo_movimiento,
                    b.tipo_doc_bodega_id,
                    c.descripcion
                FROM
                    bodegas_documentos as a,
                    bodegas_doc_numeraciones as b,
                    tipos_doc_bodega as c

                WHERE
                    a.bodegas_doc_id = $bodegas_doc_id
                    AND a.numeracion = $numero
                    AND b.bodegas_doc_id = a.bodegas_doc_id
                    AND c.tipo_doc_bodega_id = b.tipo_doc_bodega_id;
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
            $this->mensajeDeError = "EL DOCUMENTO NO EXISTE.";
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        if($detalle)
        {
            $sql="  SELECT
                        a.*,
                        b.descripcion,
                        b.unidad_id,
                        c.descripcion as descripcion_unidad
                    FROM
                        bodegas_documentos_d as a,
                        inventarios_productos as b,
                        unidades as c

                    WHERE
                        a.bodegas_doc_id = $bodegas_doc_id
                        AND a.numeracion = $numero
                        AND b.codigo_producto = a.codigo_producto
                        AND c.unidad_id = b.unidad_id;
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

            while($fila = $result->FetchRow())
            {
//		if($fila['movimiento_id'])
//                {
                 $retorno['DETALLE'][$fila['movimiento_id']]=$fila;
//                }
//                else
//                {
//                 $retorno['DETALLE'][$fila['consecutivo']]=$fila;
//                }
            }

            $result->Close();
        }

        return  $retorno;
    }



    /**
    * Metodo para obtener los datos adicionales de un documento de bodega.
    *
    * @param string $empresa_id identificador del documento
    * @param string $prefijo
    * @param integer $numero
    * @param string $tipo_doc_bodega_id tipo de documento de bodega
    * @return array datos adicionales del documento consultado.
    * @access public
    */
    function GetDocDatosAdicionales($empresa_id,$prefijo,$numero,$tipo_doc_bodega_id)
    {
        if(empty($empresa_id) || empty($prefijo) || empty($numero) || empty($tipo_doc_bodega_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETROS REQUERIDOS [empresa_id,prefijo,numero,tipo_doc_bodega_id].";
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        switch($tipo_doc_bodega_id)
        {
            case 'I001':

                $sql = "SELECT
                            a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| b.nombre_tercero as \"PROVEEDOR\",
                            a.documento_compra as \"FACTURA DE COMPRA No.\",
                            a.fecha_doc_compra as \"FECHA DE COMPRA\"
                        FROM
                            inv_bodegas_movimiento_compras_directas as a,
                            terceros as b
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.tipo_id_tercero = a.tipo_id_tercero
                            AND b.tercero_id = a.tercero_id;
                ";
            break;

            case 'I002':

                $sql = "SELECT
                            a.orden_pedido_id as \"ORDEN DE PEDIDO No.\",
                            d.tipo_id_tercero || ' ' || d.tercero_id || ' : '|| d.nombre_tercero as \"PROVEEDOR.\"

                        FROM
                            inv_bodegas_movimiento_ordenes_compra as a,
                            compras_ordenes_pedidos as b,
                            terceros_proveedores as c,
                            terceros as d

                        WHERE
                            a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.orden_pedido_id = a.orden_pedido_id
                            AND c.codigo_proveedor_id = b.codigo_proveedor_id
                            AND d.tipo_id_tercero = c.tipo_id_tercero
                            AND d.tercero_id = c.tercero_id;
                ";
            break;

			            case 'I003':

                $sql = "SELECT
                            a.fecha_selectivo as \"<b>FECHA SELECTIVO</b>\",
                            a.coordinador_auxiliar as \"<b>COORDINADOR O AUXILIAR ESTABLECIMIENTO</b>\",
                            c.descripcion||'-'||d.descripcion as \"<b>NOMBRE ESTABLECIMIENTO</b>\",
                            a.control_interno as \"<b>AUDITOR GESTION CONTROL INTERNO</b>\"
                        FROM
                            inv_bodegas_movimiento_ajustes as a
                            JOIN inv_bodegas_movimiento as b ON (a.empresa_id = b.empresa_id)
							AND (a.prefijo = b.prefijo)
							AND (a.numero = b.numero)
							JOIN bodegas as c ON (b.empresa_id = c.empresa_id)
							AND (b.centro_utilidad = c.centro_utilidad)
							AND (b.bodega = c.bodega)
							JOIN centros_utilidad as d ON (c.empresa_id = d.empresa_id)
							AND (c.centro_utilidad = d.centro_utilidad)
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero;
                ";
            //print_r($sql);
            break;
            
			
            case 'I005':

                $sql = "SELECT '(' || b.tipo_aprovechamiento_id || ') ' || b.descripcion as \"TIPO DE APROVECHAMIENTO\"
                        FROM
                            inv_bodegas_movimiento_aprovechamientos as a,
                            inv_bodegas_tipos_aprovechamiento as b
                        WHERE
                            a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND a.tipo_aprovechamiento_id = b.tipo_aprovechamiento_id;
                ";
            break;

            case 'I006':

                $sql = "SELECT
                            a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| b.nombre_tercero as \"TERCERO QUE DEVUELVE EL PRESTAMO\",
                            a.prefijo_prestamo || '-' || a.numero_prestamo as \"DOCUMENTO DE PRESTAMO\"
                        FROM
                            inv_bodegas_movimiento_ing_dev_prestamos as a,
                            terceros as b
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.tipo_id_tercero = a.tipo_id_tercero
                            AND b.tercero_id = a.tercero_id ";
            
            break;
            
            case 'I007':

                $sql = "SELECT
                            a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| b.nombre_tercero as \"PROVEEDOR DEL PRESTAMO\",
                            '(' || c.tipo_prestamo_id || ') ' || c.descripcion as \"MOTIVO DEL PRESTAMO\"
                        FROM
                            inv_bodegas_movimiento_prestamos as a,
                            terceros as b,
                            inv_bodegas_tipos_prestamos as c
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.tipo_id_tercero = a.tipo_id_tercero
                            AND b.tercero_id = a.tercero_id
                            AND c.tipo_prestamo_id = a.tipo_prestamo_id;
                ";
            break;
			
            case 'I008':

                $sql = "SELECT
                            c.razon_social as \"EMPRESA QUE DESPACHA\",
                            b.prefijo||'-'||b.numero as \"DOCUMENTO DE DESPACHO\",
                            b.solicitud_prod_a_bod_ppal_id as \"PEDIDO\",
                            d.observacion as \"OBSERVACION DOCUMENTO\"
                        FROM
                            inv_bodegas_movimiento_ingresosdespachos_farmacias as a
                            JOIN inv_bodegas_movimiento_despachos_farmacias as b ON (a.empresa_despacho = b.empresa_id)
							AND (a.prefijo_despacho = b.prefijo)
							AND (a.numero_despacho = b.numero)
							JOIN inv_bodegas_movimiento as d ON (b.empresa_id = d.empresa_id)
							AND (b.prefijo = d.prefijo)
							AND (b.numero = d.numero)
							JOIN empresas as c ON (b.empresa_id = c.empresa_id)
                            WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero;  "; 
            break;
            
            case 'I011':

                $sql = "SELECT
                            emp.empresa_id || ' - ' || emp.razon_social as \"FARMACIA\",
                            ' ' || a.prefijo_doc_farmacia || '-' || a.numero_doc_farmacia as \"DOCUMENTO DE DEVOLUCION\"
                        FROM
                            inv_bodegas_movimiento_devolucion_farmacia as a,
                            empresas as emp
                            WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND a.farmacia_id = emp.empresa_id
                            
                ";
            break;
            
             case 'I012':

                $sql = "SELECT
                            b.tipo_id_tercero || ' ' || b.tercero_id || ' : '|| b.nombre_tercero as \"CLIENTE\",
                            a.prefijo_doc_cliente || '-' || a.numero_doc_cliente as \"NUMERO DE FACTURA\"
                        FROM
                            inv_bodegas_movimiento_devolucion_cliente as a,
                            terceros as b
                           
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND a.tipo_id_tercero = b.tipo_id_tercero
                            AND a.tercero_id = b.tercero_id
                            
                ";
            //print_r($sql);
            break;
			
			 case 'I013':

                $sql = "SELECT
                            pac.primer_nombre|| ' ' ||pac.segundo_nombre|| ' ' ||pac.primer_apellido|| ' ' ||pac.segundo_apellido || ' : ' as \"PACIENTE\",
                            '(' || a.formula_papel || ') ' as \"NUMERO DE FORMULA\"
                        FROM
                            inv_bodegas_movimiento_devoluciones_formula_medica as a,
                            esm_formula_externa as b,
                            pacientes as pac
                           
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND a.formula_id = b.formula_id
                            AND b.tipo_id_paciente = pac.tipo_id_paciente
                            AND b.paciente_id = pac.paciente_id
                            
                ";
            //print_r($sql);
            break;
			 case 'I015':

                $sql = "SELECT
                            e.razon_social||'-'||d.descripcion||'-'||c.descripcion as \"FARMACIA ORIGEN\",
                            '(' || a.prefijo_doc_farmacia || '-' || a.numero_doc_farmacia || ') ' as \"DOCUMENTO DE TRASLADO\"
                        FROM
                            inv_bodegas_movimiento_ingresos_traslados_farmacia as a
                            JOIN inv_bodegas_movimiento as b ON (a.farmacia_id = b.empresa_id)
							AND (a.prefijo_doc_farmacia = b.prefijo)
							AND (a.numero_doc_farmacia = b.numero)
							JOIN bodegas as c ON (b.empresa_id = c.empresa_id)
							AND (b.centro_utilidad = c.centro_utilidad)
							AND (b.bodega = c.bodega)
							JOIN centros_utilidad as d ON (c.empresa_id = d.empresa_id)
							AND (c.centro_utilidad = d.centro_utilidad)
							JOIN empresas as e ON (d.empresa_id = e.empresa_id)
                            WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            
                ";
            /*print_r($sql);*/
            break;

            case 'T001':

                $sql = "SELECT
                        ('[' || b.empresa_id || ']' || '[' || b.centro_utilidad || ']' || '[' || b.bodega || '] : ' || b.descripcion) as \"BODEGA DESTINO\",
                        (CASE WHEN a.usuario_id IS NULL THEN 'SIN CONFIRMAR' ELSE c.nombre || ' [' || to_char(a.fecha_confirmacion, 'YYYY-MM-DD HH24:MI:SS') || ']' END) as \"CONFIRMACION\"

                        FROM
                            inv_bodegas_movimiento_traslados as a
                            LEFT JOIN system_usuarios as c
                            ON (a.usuario_id = c.usuario_id),
                            bodegas as b


                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.empresa_id = a.empresa_id
                            AND b.centro_utilidad = a.centro_utilidad_destino
                            AND b.bodega = a.bodega_destino;
                ";
            break;

            case 'T003':

                $sql = "SELECT
                        ('[' || b.empresa_id || ']' || '[' || b.centro_utilidad || ']' || '[' || b.bodega || '] : ' || b.descripcion) as \"BODEGA DESTINO\",
                        c.nombre  as \"USUARIO QUE DEVUELVE\"

                        FROM
                            inv_bodegas_movimiento_traslados_esm_devoluciones as a
                            LEFT JOIN system_usuarios as c
                            ON (a.usuario_id = c.usuario_id),
                            bodegas as b


                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.empresa_id = a.empresa_id
                            AND b.centro_utilidad = a.centro_utilidad_destino
                            AND b.bodega = a.bodega_destino;
                ";
            break;
			
			case 'T004':

                $sql = "SELECT
                        ('[' || b.empresa_id || ']' || '[' || b.centro_utilidad || ']' || '[' || b.bodega || '] : ' || b.descripcion) as \"BODEGA DESTINO\",
                        a.prefijo_documento_devolucion || ' ' ||a.numero_documento_devolucion  as \"DOCUMENTO DE DEVOLUCION\"

                        FROM
                            inv_bodegas_movimiento_traslados_esm_despacho_devolucion as a,
                            bodegas as b


                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.empresa_id = a.empresa_id
                            AND b.centro_utilidad = a.centro_utilidad_destino
                            AND b.bodega = a.bodega_destino;
                ";
            break;

			case 'T002':

                $sql = "SELECT
                        ('[' || b.empresa_id || ']' || '[' || b.centro_utilidad || ']' || '[' || b.bodega || '] : ' || b.descripcion) as \"BODEGA DESTINO\",
                        (CASE WHEN a.usuario_id IS NULL THEN 'SIN CONFIRMAR' ELSE c.nombre || ' [' || to_char(a.fecha_confirmacion, 'YYYY-MM-DD HH24:MI:SS') || ']' END) as \"CONFIRMACION\",
						d.orden_requisicion_id as \"ORDEN REQUISICION\",
						f.tipo_id_tercero || ' ' || f.tercero_id || '-' || f.nombre_tercero as \"ESM\",
             g.descripcion as \"FUERZA\",
            CASE WHEN d.sw_bodegamindefensa = '1' 
            THEN 'PRODUCTOS DE MINDEFENSA'
            ELSE
            'PRODUCTOS DE OPERADOR LOGISTICO' END as \"BODEGA\",
            d.sw_bodegamindefensa,
            d.sw_entregado_off
           

                        FROM
                            inv_bodegas_movimiento_traslados as a
                            LEFT JOIN system_usuarios as c
                            ON (a.usuario_id = c.usuario_id),
                            bodegas as b,
                            inv_bodegas_movimiento_traslados_esm d,
                            esm_orden_requisicion e,
                            terceros f,
                            esm_tipos_fuerzas g
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.empresa_id = a.empresa_id
                            AND b.centro_utilidad = a.centro_utilidad_destino
                            AND b.bodega = a.bodega_destino
                            AND a.empresa_id = d.empresa_id
                            AND a.prefijo = d.prefijo
                            AND a.numero = d.numero 
                            AND d.orden_requisicion_id = e.orden_requisicion_id 
                            AND e.tercero_id = f.tercero_id 
                            AND e.tipo_id_tercero = f.tipo_id_tercero
                            AND e.tipo_fuerza_id = g.tipo_fuerza_id
							";
							//print_r($sql);
            break;

            case 'E001':

                $sql = "SELECT '(' || b.tipo_perdida_id || ') ' || b.descripcion as \"TIPO DE PERDIDA\"
                        FROM
                            inv_bodegas_movimiento_perdidas as a,
                            inv_bodegas_tipos_perdidas as b
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND a.tipo_perdida_id = b.tipo_perdida_id;
                ";

            break;

            case 'E002':

                $sql = "SELECT
                            a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| b.nombre_tercero as \"ENTIDAD BENEFICIARIA\",
                            '(' || c.tipo_prestamo_id || ') ' || c.descripcion as \"MOTIVO DEL PRESTAMO\"
                        FROM
                            inv_bodegas_movimiento_prestamos as a,
                            terceros as b,
                            inv_bodegas_tipos_prestamos as c
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.tipo_id_tercero = a.tipo_id_tercero
                            AND b.tercero_id = a.tercero_id
                            AND c.tipo_prestamo_id = a.tipo_prestamo_id;
                ";
				
            case 'E003':

                $sql = "SELECT
                            a.fecha_selectivo as \"<b>FECHA SELECTIVO</b>\",
                            a.coordinador_auxiliar as \"<b>COORDINADOR O AUXILIAR ESTABLECIMIENTO</b>\",
                            c.descripcion||'-'||d.descripcion as \"<b>NOMBRE ESTABLECIMIENTO</b>\",
                            a.control_interno as \"<b>AUDITOR GESTION CONTROL INTERNO</b>\"
                        FROM
                            inv_bodegas_movimiento_ajustes as a
                            JOIN inv_bodegas_movimiento as b ON (a.empresa_id = b.empresa_id)
							AND (a.prefijo = b.prefijo)
							AND (a.numero = b.numero)
							JOIN bodegas as c ON (b.empresa_id = c.empresa_id)
							AND (b.centro_utilidad = c.centro_utilidad)
							AND (b.bodega = c.bodega)
							JOIN centros_utilidad as d ON (c.empresa_id = d.empresa_id)
							AND (c.centro_utilidad = d.centro_utilidad)
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero;
                ";
            //print_r($sql);
            break;
            
            case 'E004':

                $sql = "SELECT
                            a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| b.nombre_tercero as \"TERCERO QUE RECIBE LA DEVOLUCION DEL PRESTAMO\",
                            a.prefijo_prestamo || '-' || a.numero_prestamo as \"DOCUMENTO DE PRESTAMO\"
                        FROM
                            inv_bodegas_movimiento_eg_dev_prestamos as a,
                            terceros as b
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.tipo_id_tercero = a.tipo_id_tercero
                            AND b.tercero_id = a.tercero_id ";
            //print_r($sql);
            break;

            case 'E006':

                $sql = "SELECT b.departamento || ' : ' || b.descripcion as \"DEPARTAMENTO\"
                        FROM inv_bodegas_movimiento_consumo as a,
                             departamentos as b
                        WHERE
                            a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.departamento = a.departamento;
                ";

            break;


            case 'E007':

                $sql = "SELECT
                            b.departamento || ' : ' || b.descripcion as \"DEPARTAMENTO\",
                            a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| d.nombre_tercero as \"TERCERO\",
                            '(' || d.tipo_id_tercero || ') ' || c.descripcion as \"CONCEPTO DEL EGRESO\"


                        FROM inv_bodegas_movimiento_conceptos_egresos as a,
                             departamentos as b,
                             inv_bodegas_conceptos_egresos as c,
                             terceros as d

                        WHERE
                            a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.departamento = a.departamento
                            AND c.concepto_egreso_id = a.concepto_egreso_id
                            AND d.tipo_id_tercero = a.tipo_id_tercero
                            AND d.tercero_id = a.tercero_id;
                ";

            break;
            
            
            case 'E008':

                              
                $sql = "
                select * From
                (
                  (   
                    SELECT  'CLIENTES'  as \"TIPO DE DESPACHO :\",
							a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| b.nombre_tercero as \"FARMACIA/CLIENTE :\",
                            a.pedido_cliente_id AS \"NUMERO PEDIDO: \",
                            b.direccion AS \"DIRECCION: \",
                            b.telefono AS \"TELEFONO: \"
                    FROM    inv_bodegas_movimiento_despachos_clientes as a,
                            terceros as b
                    WHERE   a.empresa_id = '$empresa_id'
                    AND a.prefijo = '$prefijo'
                    AND a.numero = $numero
                    AND b.tipo_id_tercero = a.tipo_id_tercero
                    AND b.tercero_id = a.tercero_id
                  ) ";
                
                $sql .= "
                
                UNION ALL
                  (    
                    SELECT  'FARMACIAS'  as \"TIPO DE DESPACHO :\",
							e.empresa_id || ' - '|| e.razon_social ||' ::: '||c.descripcion as \"FARMACIA/CLIENTE :\",
                            a.solicitud_prod_a_bod_ppal_id AS \"NUMERO PEDIDO: \",
                            e.direccion AS \"DIRECCION: \",
                            e.telefonos AS \"TELEFONO: \"
                    FROM    inv_bodegas_movimiento_despachos_farmacias as a
							JOIN solicitud_productos_a_bodega_principal as b ON (a.solicitud_prod_a_bod_ppal_id = b.solicitud_prod_a_bod_ppal_id)
							JOIN bodegas as c ON (b.farmacia_id = c.empresa_id)
							AND (b.centro_utilidad = c.centro_utilidad)
							AND (b.bodega = c.bodega)
							JOIN centros_utilidad as d ON (c.centro_utilidad = d.centro_utilidad)
							AND (c.empresa_id = d.empresa_id)
                            JOIN empresas as e ON (d.empresa_id = e.empresa_id)
                    WHERE   a.empresa_id = '$empresa_id'
                    AND a.prefijo = '$prefijo'
                    AND a.numero = $numero
                  )        
                )as x
                ";
            /*print_r($sql);*/
            break;
            case 'E012':
			$sql = "SELECT 
			b.tipo_id_tercero || ' ' || b.tercero_id || ' : '|| b.nombre_tercero as \"PROVEEDOR\",
			a.codigo_proveedor_id as \"CODIGO DEL PROVEEDOR\",
			a.numero_factura as \"NUMERO DE FACTURA\"
			FROM inv_bodegas_movimiento_devolucion_proveedor as a
			JOIN terceros_proveedores as c ON (a.codigo_proveedor_id = c.codigo_proveedor_id) 
			JOIN terceros as b ON (c.tipo_id_tercero = b.tipo_id_tercero)
			AND (c.tercero_id = b.tercero_id) 
			WHERE 
			a.empresa_id = '$empresa_id'
			AND a.prefijo = '$prefijo'
			AND a.numero = $numero; ";
            break;
            
            case 'E016':

                $sql = "SELECT
                            b.tipo_id_tercero || ' ' || b.tercero_id || ' : '|| b.nombre_tercero as \"ESM\",
                            '(' || a.orden_requisicion_id || ') ' as \"NUMERO DE REQUISICION\",
                            '' || c.descripcion || ' ' as \"TIPO DE FUERZA\",
                            '' || a.direccion || ' ' as \"DIRECCION\",
                            '' || a.empresa_transportadora || ' ' as \"EMPRESA TRANSPORTADORA\",
                            '' || a.numero_guia || ' ' as \"NUMERO GUIA\",
                            CASE WHEN sw_bodegamindefensa = '1' 
                            THEN 'PRODUCTOS DE MINDEFENSA'
                            ELSE
                            'PRODUCTOS DE OPERADOR LOGISTICO' END as \"BODEGA\",
                            a.sw_bodegamindefensa,
                            a.sw_entregado_off
                            

                        FROM
                            inv_bodegas_movimiento_despacho_campania as a,
                            terceros as b,
                            esm_tipos_fuerzas as c
                            
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND a.tercero_id = b.tercero_id
                            AND a.tipo_id_tercero = b.tipo_id_tercero
                            AND a.tipo_fuerza_id = c.tipo_fuerza_id
                            
                            
                ";
            //print_r($sql);
            break;
            
            case 'E017':

                $sql = "SELECT
                            d.razon_social||'-'||c.descripcion||'-'||b.descripcion as \"FARMACIA DESTINO\",
                            CASE WHEN a.sw_estado = '1' 
                            THEN 'PENDIENTE POR RECIBIR TOTALMENTE'
                            ELSE
                            'RECIBIDO TOTALMENTE' END as \"ESTADO DOCUMENTO\"
                        FROM
                            inv_bodegas_movimiento_traslados_farmacia as a
                            LEFT JOIN bodegas as b ON (a.farmacia_id = b.empresa_id)
							AND (a.centro_utilidad = b.centro_utilidad)
							AND (a.bodega = b.bodega)
							LEFT JOIN centros_utilidad as c ON (b.empresa_id = c.empresa_id)
							AND (b.centro_utilidad = c.centro_utilidad)
							LEFT JOIN empresas as d ON (c.empresa_id = d.empresa_id)
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero;
                ";
           /*print_r($sql);*/
            break;

            case 'E018':

                $sql = "SELECT
                             b.plan_descripcion as \"PLAN\",
							 c.descripcion_tipo_formula as \"TIPO DISPENSACION\",
							 a.requisicion AS \"COD. SOLICITUD\"
                            
                        FROM
                            inv_bodegas_movimiento_distribucion as a
                            JOIN planes as b ON(a.plan_id = b.plan_id)
							JOIN esm_tipos_formulas as c ON (a.tipo_formula_id = c.tipo_formula_id)
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero;
                ";
           /*print_r($sql);*/
            break;

            default:

                return null;
        }

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
            return null;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        return  $retorno;

    }


    /**
    * Metodo para obtener el detalle(items) de documento de bodega.
    *
    * @param string $empresa_id identificador del documento
    * @param string $prefijo
    * @param integer $numero
    * @return array datos del documento consultado.
    * @access public
    */
    function GetDocDetalle($empresa_id,$prefijo,$numero)
    {
        if(empty($empresa_id) || empty($prefijo) || empty($numero))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETROS REQUERIDOS [empresa_id,prefijo,numero].";
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT
                    a.*,
                    b.descripcion,
                    b.unidad_id,
					b.contenido_unidad_venta,
                    c.descripcion as descripcion_unidad,
                    fc_descripcion_producto(b.codigo_producto) as nombre,
					(a.valor_unitario*(a.porcentaje_gravamen/100)) as iva,
					(a.valor_unitario+(a.valor_unitario*(a.porcentaje_gravamen/100))) as valor_unitario_iva,
					((a.cantidad)*(a.valor_unitario+(a.valor_unitario*(a.porcentaje_gravamen/100)))) as valor_total_iva,
					(((a.total_costo)/((a.porcentaje_gravamen/100)+1))/a.cantidad) as valor_unit_1,
					((a.total_costo/a.cantidad)-(((a.total_costo)/((a.porcentaje_gravamen/100)+1))/a.cantidad)) as iva_1,
					((((a.total_costo)/((a.porcentaje_gravamen/100)+1))/a.cantidad)*a.cantidad) as valor_total_1,
					(((a.total_costo/a.cantidad)-(((a.total_costo)/((a.porcentaje_gravamen/100)+1))/a.cantidad))*a.cantidad) as iva_total_1,
					(SELECT ti.descripcion FROM inv_tipo_producto as ti WHERE b.tipo_producto_id =  ti.tipo_producto_id ) as tipo_producto,
				     CASE WHEN (SELECT tor.torre FROM param_torreproducto as tor WHERE tor.codigo_producto = b.codigo_producto AND tor.empresa_id = a.empresa_id LIMIT 1) is null THEN 'Sin definir' 
				     ELSE (SELECT tor.torre FROM param_torreproducto as tor WHERE tor.codigo_producto = b.codigo_producto AND tor.empresa_id = a.empresa_id LIMIT 1) END as torre
                FROM
                    inv_bodegas_movimiento_d as a,
                    inventarios_productos as b,
                    unidades as c
                WHERE
                    a.empresa_id = '$empresa_id'
                    AND a.prefijo = '$prefijo'
                    AND a.numero = $numero
                    AND b.codigo_producto = a.codigo_producto
                    AND c.unidad_id = b.unidad_id
                    ORDER BY torre  asc
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

        $retorno = array();

        while($fila = $result->FetchRow())
        {
            $retorno[$fila['movimiento_id']]=$fila;
        }

        $result->Close();

        return  $retorno;

    }

    /**
    * Metodo para obtener un item del detalle de un documento de bodega.
    *
    * @param integer $movimiento_id Identificador del movimiento(item)
    * @return array datos del item consultado.
    * @access public
    */
    function GetDocItemDetalle($movimiento_id)
    {
        if(empty($movimiento_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO REQUERIDO [movimiento_id].";
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT
                    a.*,
                    b.descripcion,
                    b.unidad_id,
                    c.descripcion as descripcion_unidad
                FROM
                    inv_bodegas_movimiento_d as a,
                    inventarios_productos as b,
                    unidades as c
                WHERE
                    movimiento_id = $movimiento_id
                    AND b.codigo_producto = a.codigo_producto
                    AND c.unidad_id = b.unidad_id;
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
            $this->mensajeDeError = "EL [movimiento_id=$movimiento_id] NO EXISTE.";
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        return  $retorno;

    }
  }
?>