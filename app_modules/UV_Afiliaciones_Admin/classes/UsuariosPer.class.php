<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: UsuariosPer.class.php,v 1.17 2007/11/09 14:54:13 alexgiraldo Exp $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
  */

/**
  * Clase : UsuariosPer - Administracion del modulo de UV_Afiliaciones
  * Clase para la administracion de afiliados al sistema y usuario del sistema EPS
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.17 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
  */

IncludeClass("Afiliaciones_Admin", "", "app","UV_Afiliaciones_Admin");


class UsuariosPer extends Afiliaciones_Admin
{
    /**
    * Constructor de la clase
    */
    function UsuariosPer(){}

    /**
    * Metodo para obtener los usuarios del sitema de EPS
    *
    * @param array $filtros vector con criterios de busqueda atributo:valor
    * @param boolean $count (opcional) True/False indica si retorna el numero de registros o los registros
    * @param integer $limit (opcional) limite de registros que retorna la consulta
    * @param integer $offset (opcional) desde que registro inicia la consulta
    * @return array
    * @access public
    */
    function GetUsuariosAfiliaciones($filtros=array(), $count=null, $limit=null, $offset=null)
    {

        $filtro = "";

        if($filtros['tipo']==='usuario_id')
        {
           $filtro = " AND a.usuario_id = " . $filtros['valor'] . " ";
        }
        elseif($filtros['tipo']==='usuario')
        {
           $filtro = " AND c.usuario = '" . $filtros['valor'] . "' ";
        }
        elseif($filtros['tipo']==='nombre')
        {
           $filtro = " AND c.nombre ILIKE '%" . $filtros['valor'] . "%' ";
        }
        elseif($filtros['tipo']==='perfil_id')
        {
           $filtro = " AND b.perfil_id = '" . $filtros['perfil'] . "' ";
        }

        if(is_numeric($limit) && is_numeric($offset))
        {
            $filtro_limit = " LIMIT $limit OFFSET $offset ";
        }
        else
        {
            $filtro_limit = "";
        }

        if(empty($count))
        {
            $select = "
                        a.usuario_id,
                        c.usuario,
                        c.nombre,
                        b.perfil_id,
                        b.descripcion_perfil,
                        a.sw_admin
            ";

            $filtro .= " ORDER BY c.nombre ";
        }
        else
        {
            $select = " COUNT(*) as cantidad";
        }
        $sql  = "
                    SELECT $select
                    FROM
                        userpermisos_eps_afiliaciones as a,
                        userpermisos_eps_afiliaciones_perfiles as b,
                        system_usuarios as c

                    WHERE
                        b.perfil_id = a.perfil_id
                        AND c.usuario_id = a.usuario_id
                        AND c.activo = '1'
                        $filtro
                    $filtro_limit;
        ";

        if(!$result = $this->ConexionBaseDatos($sql,true))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
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
    * Metodo para obtener los usuarios del sistema que no estan en el sistema de EPS
    *
    * @param array $filtros
    * @param boolean $count (opcional) True/False indica si retorna el numero de registros o los registros
    * @param integer $limit (opcional) limite de registros que retorna la consulta
    * @param integer $offset (opcional) desde que registro inicia la consulta
    * @return array
    * @access public
    */
    function GetSystemUsers($filtros=array(), $count=null, $limit=null, $offset=null)
    {
        if($filtros['tipo']==='usuario_id')
        {
           $filtro = " AND a.usuario_id = " . $filtros['valor'] . " ";
        }
        elseif($filtros['tipo']==='usuario')
        {
           $filtro = " AND a.usuario = '" . $filtros['valor'] . "' ";
        }
        elseif($filtros['tipo']==='nombre')
        {
           $filtro = " AND a.nombre ILIKE '%" . $filtros['valor'] . "%' ";
        }

        if(is_numeric($limit) && is_numeric($offset))
        {
            $filtro_limit = " LIMIT $limit OFFSET $offset ";
        }
        else
        {
            $filtro_limit = "";
        }

        if(empty($count))
        {
            $select = "
                        a.usuario_id,
                        a.usuario,
                        a.nombre
            ";

            $filtro .= " ORDER BY a.nombre ";
        }
        else
        {
            $select = " COUNT(*) as cantidad";
        }

        $sql  = "
                    SELECT $select
                    FROM
                    system_usuarios as a
                    LEFT JOIN userpermisos_eps_afiliaciones as b
                    ON (b.usuario_id = a.usuario_id)

                    WHERE
                        a.activo = '1'
                        AND b.usuario_id IS NULL
                        $filtro
                    $filtro_limit;

        ";

        if(!$result = $this->ConexionBaseDatos($sql,true))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
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
    * Adicionar un usuario al sistema de Afiliaciones EPS
    *
    * @param integer $usuario_id
    * @param string $perfil_id
    * @param string $sw_admin 0:inactivo 1:activo
    * @return boolean
    * @access public
    */
    function AddUsuarioAfiliaciones($usuario_id,$perfil_id,$sw_admin='0')
    {
        $sql  = "   INSERT INTO userpermisos_eps_afiliaciones(usuario_id,perfil_id,sw_admin)
                    VALUES ($usuario_id,'$perfil_id','$sw_admin');
        ";

        if(!$result = $this->ConexionBaseDatos($sql))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        return true;
    }


    /**
    * Eliminar un usuario al sistema de Afiliaciones EPS
    *
    * @param integer $usuario_id
    * @return boolean
    * @access public
    */
    function DelUsuarioAfiliaciones($usuario_id)
    {
        $sql  = "DELETE FROM userpermisos_eps_afiliaciones WHERE usuario_id = $usuario_id ";

        if(!$result = $this->ConexionBaseDatos($sql))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        return true;
    }

    /**
    * Modificar el perfil de un usuario del sistema de Afiliaciones EPS
    *
    * @param integer $usuario_id
    * @param string $perfil_id
    * @return boolean
    * @access public
    */
    function ModificarPerfilUsuarioAfiliaciones($usuario_id,$perfil_id)
    {
        $sql  = "UPDATE userpermisos_eps_afiliaciones SET perfil_id = '$perfil_id' WHERE usuario_id = $usuario_id ";

        if(!$result = $this->ConexionBaseDatos($sql))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        return true;
    }

     /**
    * ACTIVAR/DESACTIVAR el permiso de ADMIN de un usuario del sistema de Afiliaciones EPS
    *
    * @param integer $usuario_id
    * @return string caracter de estado 1:ACTIVO, 2:INACTIVO
    * @access public
    */
    function CambiarPermisoAdminUsuarioAfiliaciones($usuario_id)
    {
        $sql  = "SELECT * FROM userpermisos_eps_afiliaciones WHERE usuario_id = $usuario_id";

        if(!$result = $this->ConexionBaseDatos($sql,true))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        $fila = $result->FetchRow();

        $cambio = '0';

        if($fila['sw_admin']==='0')
        {
            $cambio = '1';
        }

        $sql  = "UPDATE userpermisos_eps_afiliaciones SET sw_admin = '$cambio' WHERE usuario_id = $usuario_id ";

        if(!$result = $this->ConexionBaseDatos($sql))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        return $cambio;
    }


}
?>