<?php
/**
* @package IPSOFT-SIIS
* @version $Id: Interfaces.class.php,v 1.1.1.1 2009/09/11 20:36:58 hugo Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
*/
/**
* Clase: Interfaces
* Clase para consulta de afiliados al sistema EPS
*
* @package IPSOFT-SIIS
* @version $Revision: 1.1.1.1 $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
*/
IncludeClass("Afiliaciones", "", "app","UV_Afiliaciones");
class Interfaces extends Afiliaciones
{
    /**
    * Constructor de la clase
    */
    function Interfaces(){}

    /**
    * Metodo para obtener la informacion de un funcionario interfazado.
    *
    * @param string  $funcionario_tipo_id Tipo de identificacion del afiliado
    * @param string  $funcionario_id Numero de identificacion del afiliado
    * @return array
    * @access public
    */
    function GetDatosFuncionario($funcionario_tipo_id, $funcionario_id)
    {
        $sql = "
                    SELECT  funcionario_tipo_id,
                            funcionario_id,
                            primer_apellido,
                            segundo_apellido,
                            primer_nombre,
                            segundo_nombre,
                            TO_CHAR(fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento,
                            tipo_sexo_id,
                            ciuo_88_grupo_primario,
                            tipo_pais_id,
                            tipo_dpto_id,
                            tipo_mpio_id,
                            zona_residencia,
                            direccion_residencia,
                            telefono_residencia,
                            telefono_movil,
                            ciiu_r3_division,
                            ciiu_r3_grupo,
                            telefono_dependencia,
                            estrato_socioeconomico_id,
                            tipo_estado_civil_id,
                            estamento_id,
                            codigo_afp,
                            ingreso_mensual,
                            TO_CHAR(fecha_ingreso_laboral,'DD/MM/YYYY') AS fecha_ingreso_laboral,
                            codigo_dependencia_id,
                            sirh_per_codigo,
                            ter_codigo

                    FROM interfaz_uv.funcionarios_univalle
                    WHERE
                        funcionario_tipo_id = '$funcionario_tipo_id'
                        AND funcionario_id = '$funcionario_id';
        ";


        if(!$result = $this->ConexionBaseDatos($sql,true))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        return $retorno;
    }

    /**
    * Metodo para obtener la informacion de un tercero juridico interfazado.
    *
    * @param string $tipo_id_tercero  Tipo de identificacion del tercero
    * @param string $tercero_id Numero de identificacion del tercero
    * @return array
    * @access public
    */
    function GetTercerosJuridicos($tipo_id_tercero, $tercero_id)
    {
        $sql = "
                    SELECT
                            tipo_id_tercero,
                            tercero_id,
                            dv,
                            nombre_tercero,
                            ter_codigo,
                            tipo_pais_id,
                            tipo_dpto_id,
                            tipo_mpio_id,
                            direccion,
                            telefono,
                            fax,email,
                            celular,
                            sw_gran_contribuyente,
                            sw_responsable_iva,
                            sw_regimen_comun

                    FROM interfaz_uv.terceros_juridicos_univalle
                    WHERE
                        tipo_id_tercero = '$tipo_id_tercero'
                        AND tercero_id = '$tercero_id';
        ";


        if(!$result = $this->ConexionBaseDatos($sql,true))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        return $retorno;
    }


    /**
    * Metodo para buscar un tercero juridico en una interfaz
    *
    * @param string $razonSocial
    * @param boolean $count (opcional) True/False indica si retorna el numero de registros o los registros
    * @param integer $limit (opcional) limite de registros que retorna la consulta
    * @param integer $offset (opcional) desde que registro inicia la consulta
    * @return array
    * @access public
    */
    function BuscarTerceroJuridicoPorRazonSocial($razonSocial, $count=null, $limit=null, $offset=null)
    {

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
                        tipo_id_tercero,
                        tercero_id,
                        dv,
                        nombre_tercero,
                        ter_codigo,
                        tipo_pais_id,
                        tipo_dpto_id,
                        tipo_mpio_id,
                        direccion,
                        telefono,
                        fax,email,
                        celular,
                        sw_gran_contribuyente,
                        sw_responsable_iva,
                        sw_regimen_comun
            ";
        }
        else
        {
            $select = " COUNT(*) as cantidad ";
        }

        $sql  = "
                    SELECT $select
                    FROM interfaz_uv.terceros_juridicos_univalle
                    WHERE nombre_tercero ILIKE '%$razonSocial%'
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

}
?>