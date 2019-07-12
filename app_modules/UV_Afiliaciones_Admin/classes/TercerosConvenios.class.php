<?php

/**
  * @package IPSOFT-SIIS
  * @version * $Id: TercerosConvenios.class.php,v 1.12 2008/06/13 19:38:32 jgomez Exp $
  * @copyright (C) 2007 IPSOFT  S.A. (www.ipsoft-sa.com)
  * @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
  */

/**
  * Clase: TercerosConvenios
  * Clase para consulta de afiliados al sistema EPS.
  * @package IPSOFT-SIIS
  * @version $Revision: 1.12 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
  */


IncludeClass("Afiliaciones_Admin", "", "app","UV_Afiliaciones_Admin");

class TercerosConvenios extends Afiliaciones_Admin
{
    /**
    * Constructor de la clase
    */
    function TercerosConvenios(){}



    /**
    * Metodo para obtener las entidades con las que se tiene convenio
    *
    * @param array $filtros vector con criterios de busqueda atributo:valor
    * @param boolean $count (opcional) True/False indica si retorna el numero de registros o los registros
    * @param integer $limit (opcional) limite de registros que retorna la consulta
    * @param integer $offset (opcional) desde que registro inicia la consulta
    * @return array
    * @access public
    */
    function GetTercerosConvenios($filtros=array(), $count=null, $limit=null, $offset=null)
    {
        $filtro = "";

        if($filtros['tipo_id_tercero'] && $filtros['tercero_id'])
        {
            $filtro .= " AND a.tipo_id_tercero = '".$filtros['tipo_id_tercero']."' ";
            $filtro .= " AND a.tercero_id = '".$filtros['tercero_id']."' ";
        }
        else
        {
            if($filtros['nombre_tercero'])
            {
                $filtro .= " AND b.nombre_tercero ILIKE '%".$filtros['nombre_tercero']."%' ";
            }

            if($filtros['sw_estado']==='0')
            {
                $filtro .= " AND  a.sw_estado = '0' ";
            }
            elseif($filtros['sw_estado']==='1')
            {
                $filtro .= " AND  a.sw_estado = '1' ";
            }
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
                        b.tipo_id_tercero,
                        b.tercero_id,
                        b.nombre_tercero,
                        a.sw_estado
            ";
        }
        else
        {
            $select = " COUNT(*) as cantidad";
        }

        $sql  = "
                    SELECT $select
                    FROM
                        terceros_uv_convenios as a,
                        terceros as b

                    WHERE
                        b.tipo_id_tercero = a.tipo_id_tercero
                        AND b.tercero_id = a.tercero_id
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
    * ACTIVAR/DESACTIVAR el estado de la entidad con convenio
    *
    * @param string $tipo_id_tercero
    * @param string $tercero_id
    * @return string caracter de estado 1:ACTIVO, 2:INACTIVO
    * @access public
    */
    function CambiarEstadoTerceroConvenio($tipo_id_tercero,$tercero_id)
    {
        $sql  = "   SELECT sw_estado
                    FROM terceros_uv_convenios
                    WHERE
                        tipo_id_tercero = '$tipo_id_tercero'
                        AND tercero_id = '$tercero_id';
        ";

        if(!$result = $this->ConexionBaseDatos($sql,true))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        $fila = $result->FetchRow();

        $cambio = '0';

        if($fila['sw_estado']==='0')
        {
            $cambio = '1';
        }

       $sql = "UPDATE terceros_uv_convenios
                SET sw_estado = '$cambio'
                WHERE
                    tipo_id_tercero = '$tipo_id_tercero'
                    AND tercero_id = '$tercero_id';
        ";

        if(!$result = $this->ConexionBaseDatos($sql))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "ERROR DB : " . $dbconn->ErrorMsg();
            return false;
        }

        return $cambio;
    }

}
?>