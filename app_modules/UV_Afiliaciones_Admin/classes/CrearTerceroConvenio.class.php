<?php

/**
  * @package IPSOFT-SIIS
  * @version $Id: CrearTerceroConvenio.class.php,v 1.7 2008/06/13 19:38:25 jgomez Exp $
  * @copyright (C) 2007 IPSOFT  S.A. (www.ipsoft-sa.com)
  * @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
  */

/**
  * Clase: CrearTerceroConvenio - Administracion del modulo de UV_Afiliaciones
  * Clase para la creacion de entidades convenios.
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.7 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
  */

IncludeClass("Afiliaciones_Admin", "", "app","UV_Afiliaciones_Admin");


class CrearTerceroConvenio extends Afiliaciones_Admin
{
    /**
    * Constructor de la clase
    */
    function CrearTerceroConvenio(){}

    /**
    * Metodo para buscar un tercero juridico en una interfaz HACIENDO LEFT JOIN CON TERCEROS
    *
    * @param string $razonSocial
    * @param boolean $count (opcional) True/False indica si retorna el numero de registros o los registros
    * @param integer $limit (opcional) limite de registros que retorna la consulta
    * @param integer $offset (opcional) desde que registro inicia la consulta
    * @return array
    * @access public
    */
    function BuscarTerceroJuridicoPorRazonSocial_LJ_TER($razonSocial, $count=null, $limit=null, $offset=null)
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
                        a.tipo_id_tercero,
                        a.tercero_id,
                        a.nombre_tercero,
                        a.ter_codigo,
                        a.tipo_pais_id,
                        a.tipo_dpto_id,
                        a.tipo_mpio_id,
                        a.direccion,
                        a.telefono,
                        a.fax,
                        a.email,
                        a.celular,
                        a.sw_gran_contribuyente,
                        a.sw_responsable_iva,
                        a.sw_regimen_comun,
                        b.sw_estado
            ";
        }
        else
        {
            $select = " COUNT(*) as cantidad ";
        }

        $sql  = "
                    SELECT $select
                    FROM interfaz_uv.terceros_juridicos_univalle as a
                         LEFT JOIN
                         terceros_uv_convenios as b
                     ON (nombre_tercero ILIKE '%$razonSocial%'
                        AND a.tipo_id_tercero=b.tipo_id_tercero
                        AND a.tercero_id=b.tercero_id)
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
    * Buscar terceros para la creacion de terceros de convenios
    *
    * La busqueda se puede realizar en una interfaz externa o en los mismos terceros de SIIS
    *
    * @param boolean $interfaz indica si la busqueda es en una interfaz(true) o en los terceros de SIIs(false)
    * @param string $nombre cadena buscar en el atributo nombre del tercero
    * @param boolean $count (opcional) True/False indica si retorna el numero de registros o los registros
    * @param integer $limit (opcional) limite de registros que retorna la consulta
    * @param integer $offset (opcional) desde que registro inicia la consulta
    * @return array registros de terceros que coinciden con la busqueda.
    * @access public
    */
    function BuscarTerceroPorNombre($interfaz=true, $nombre, $count=null, $limit=null, $offset=null)
    {
        if($interfaz)
        {
            $obj = AutoCarga::factory("Interfaces", "", "app","UV_Afiliaciones");
            if(!is_object($obj))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "No se pudo crear el objeto Interfaces";
                return false;
            }

            $resultado = $this->BuscarTerceroJuridicoPorRazonSocial_LJ_TER($nombre, $count, $limit, $offset);

            if($resultado===false)
            {
                $this->error = $obj->Err();
                $this->mensajeDeError = $obj->ErrMsg();
                return false;
            }

            return $resultado;
        }
        else
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
                            a.tipo_id_tercero,
                            a.tercero_id,
                            a.nombre_tercero,
                            a.tipo_pais_id,
                            a.tipo_dpto_id,
                            a.tipo_mpio_id,
                            a.direccion,
                            a.telefono,
                            a.fax,email,
                            a.celular,
                            b.sw_estado
                ";
            }
            else
            {
                $select = " COUNT(*) as cantidad ";
            }

              $sql  = "
                        SELECT $select
                        FROM public.terceros as a LEFT JOIN
                        terceros_uv_convenios as b
                        ON (nombre_tercero ILIKE '%$nombre%'
                        AND a.tipo_id_tercero=b.tipo_id_tercero
                        AND a.tercero_id=b.tercero_id)
                        
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


      /**
    * Funcion que se utiliza para GUARDAR LA INFORMACION de trabajos anteriores y los riesgos a los que estuvo expuesto por ese trabajo
    * @param array $vector
    * @param string $ban
    * @return string $salida
    **/
    function Guardar_TercerosConvenios($vector)
    {
       
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $usuario=UserGetUID();


//  ["tipo_id_tercero"]=>
//   string(2) "CC"
//   ["tercero_id"]=>
//   string(7) "6603000"
//   ["nombre"]=>
//   string(15) "JAIME GOMEZ PYP"
//   ["pais"]=>
//   string(2) "CO"
//   ["dpto"]=>
//   string(2) "76"
//   ["mpio"]=>
//   string(3) "001"
//   ["direccion"]=>
//   string(18) "CALLE 18N # 5N-32 "
//   ["fax"]=>
//   string(10) "6698987987"
//   ["mail"]=>
//   string(6) "jaimmi"
//   ["celular"]=>
//   string(9) "654654644"
//                

    if($vector['dv']=='1')
    {
              $sql="INSERT INTO
               terceros
                (
                    tipo_id_tercero,
                    tercero_id,
                    tipo_pais_id,
                    tipo_dpto_id,
                    tipo_mpio_id,
                    direccion,
                    telefono,
                    fax,
                    email,
                    celular,
                    sw_persona_juridica,
                    usuario_id,
                    fecha_registro,
                    nombre_tercero
                )
                  values('".$vector["tipo_id_tercero"]."',
                         '".$vector["tercero_id"]."',
                         '".$vector["pais"]."',
                         '".$vector["dpto"]."',
                         '".$vector["mpio"]."',
                         '".$vector["direccion"]."',
                         '".$vector["telefono"]."',
                         '".$vector["fax"]."',
                         '".$vector["email"]."',
                         '".$vector["celular"]."',
                         '1',
                         ".$usuario.",
                         NOW(),
                         '".$vector["nombre"]."'
                         )";
           $result = $dbconn->Execute($sql);

             if($dbconn->ErrorNo() != 0)
             {
                 $dbconn->RollbackTrans();
                 $this->error = "ERROR INSERCION TERCEROS [" . get_class($this) . "][" . __LINE__ . "]";
                 $this->mensajeDeError = $dbconn->ErrorMsg();
                 return false;
             }


    }
    
    
                 $sql="INSERT INTO
                    terceros_uv_convenios
                    (
                        tipo_id_tercero,
                        tercero_id,
                        sw_estado
                    )
                  values('".$vector["tipo_id_tercero"]."',
                         '".$vector["tercero_id"]."',
                         '1'
                        )";
                        
           $result = $dbconn->Execute($sql);

             if($dbconn->ErrorNo() != 0)
             {
                 $dbconn->RollbackTrans();
                 $this->error = "ERROR INSERCION TERCEROS CONVENIOS [" . get_class($this) . "][" . __LINE__ . "]";
                 $this->mensajeDeError = $dbconn->ErrorMsg();
                 return false;
             }

        $dbconn->CommitTrans();
 
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        return true;

    }


}
?>