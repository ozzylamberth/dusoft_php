<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: Afiliaciones_Admin.class.php,v 1.15 2007/11/09 14:10:47 alexgiraldo Exp $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
  */

/**
  * Clase : Afiliaciones_Admin - Administracion del modulo de UV_Afiliaciones
  * Clase que proporciona datos generales mas conexion a la base de datos para
  * la administracion del modulo de UV_Afiliaciones
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.15 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
  */

class Afiliaciones_Admin
{
    /**
    * Codigo de error
    *
    * @var string
    * @access public
    */
    var $error;

    /**
    * Mensaje de error
    *
    * @var string
    * @access public
    */
    var $mensajeDeError;

    /**
    * Constructor de la clase
    */
    function Afiliaciones_Admin(){}

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
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la
    * consulta sql
    *
    * @param string $sql sentencia sql a ejecutar
    * @param boolean $asoc Indica el modo en el cual se hara la ejecucion del query,
    *                      por defecto es false
    * @return object $rst
    */
    function ConexionBaseDatos($sql,$asoc = false)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn)=GetDBConn();
        //$dbconn->debug=true;

        if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

        $rst = $dbconn->Execute($sql);

        if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0)
        {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        return $rst;
    }

    /**
    * Retorna la informacion del perfil de permisos del usuario para el modulo de afiliaciones
    *
    * @param integer $usuario_id id del usuario
    * @return array
    */
    function GetUserPermisos($usuario_id)
    {
        $sql  = "SELECT * ";
        $sql .= "FROM userpermisos_eps_afiliaciones ";
        $sql .= "WHERE usuario_id = $usuario_id ";

        if(!$rst = $this->ConexionBaseDatos($sql,true)) return false;

        if($rst->EOF)
        {
            return null;
        }

        $datos = $rst->FetchRow();
        return $datos;
    }


    /**
    * Consulta de las diferentes perfiles
    *
    * @return array
    */
    function GetPerfiles()
    {
        $sql  = "SELECT perfil_id, descripcion_perfil ";
        $sql .= "FROM userpermisos_eps_afiliaciones_perfiles ";
        $sql .= "ORDER BY descripcion_perfil ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        while(!$rst->EOF)
        {
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }


    /**
    * Metodo para validar si un usuario tiene permisos administrativos del modulo
    *
    * @return string
    * @access public
    */
    function ValidarPermisoAdmin()
    {
        $resultado=$this->GetUserPermisos(UserGetUID());
        if($resultado['sw_admin']==='1')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
    * Obtiene los tipos de identificacion de los afiliados
    *
    * @return array
    */
    function ObtenerAfiliadosTiposId()
    {
        $sql  = " SELECT afiliado_tipo_id, descripcion_tipo_id ";
        $sql .= " FROM eps_afiliados_tipos_id ";
        $sql .= " ORDER BY descripcion_tipo_id ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }


    /**
    * Obtiene los tipos de identificacion de los terceros
    *
    * @return array
    */
    function ObtenerTercerosTiposId()
    {
        $sql  = " SELECT tipo_id_tercero, descripcion ";
        $sql .= " FROM tipo_id_terceros ";
        $sql .= " ORDER BY indice_de_orden ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /**
    * Consulta la informacion del lugar de residencia por defecto
    *
    * @param array $datos Vector con la informacion delpais, municipio y ciudad por defecto
    *
    * @return array
    */
    function ObtenerDatosLugarResidencia($datos)
    {
        $sql  = "SELECT TM.tipo_pais_id   , ";
        $sql .= "       TM.tipo_dpto_id   , ";
        $sql .= "       TM.tipo_mpio_id   , ";
        $sql .= "       TP.pais ||'-'||TD.departamento||'-'||TM.municipio AS departamento_municipio ";
        $sql .= "FROM   tipo_pais TP,";
        $sql .= "       tipo_dptos TD,";
        $sql .= "       tipo_mpios TM ";
        $sql .= "WHERE  TD.tipo_pais_id = TP.tipo_pais_id ";
        $sql .= "AND    TM.tipo_pais_id = TD.tipo_pais_id ";
        $sql .= "AND    TM.tipo_dpto_id = TD.tipo_dpto_id ";
        $sql .= "AND    TM.tipo_pais_id = '".$datos['DefaultPais']."' ";
        $sql .= "AND    TM.tipo_dpto_id = '".$datos['DefaultDpto']."' ";
        $sql .= "AND    TM.tipo_mpio_id = '".$datos['DefaultMpio']."' ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        while(!$rst->EOF)
        {
                $datos = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }


}
?>