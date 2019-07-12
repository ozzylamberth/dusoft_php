<?php
/**
* @package IPSOFT-SIIS
* @version $Id: SaludOcupacionalAdmin.class.php,v 1.23 2007/12/18 23:14:53 hugo Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @author JAIME GOMEZ
*/
/**
* Clase: SaludOcupacionalAdmin
* Clase encargada del manejo del maestro de tipos de riesgo, agemtes de riesgo, tipos de espacio y demas datos necesario para el amenjo del proceso de salud ocupacional
*
*
* @package IPSOFT-SIIS
* @version $Revision: 1.23 $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @author JAIME GOMEZ
*/
class SaludOcupacionalAdmin
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
    * Variable global para el manejo de la conexion
    *
    * @var object
    * @access public
    */
    var $dbconn;
    /**
    * Variable que indica el offset de la consulta
    *
    * @var int
    * @access public
    */
    var $offset;
    /**
    * Variable que indica el numero de la pagina a mostrar
    *
    * @var int
    * @access public
    */
    var $pagina;
    /**
    * Variable que indica la cantidad total de registros de la consulta
    *
    * @var int
    * @access public
    */
    var $conteo;
    /**
    * Variable que indica el total de registros a mostrar por pagina
    *
    * @var int
    * @access public
    */
    var $limit;
    /**
    *
    * Constructor de la clase
    */
    function SaludOcupacionalAdmin(){}
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
    * FUNCION UTIL PARA LA ACTUALIZACION DE CARGOS
    * @param 
    *
    *
    *
    **/

    function UpdateTheCargo($cargo_id,$ocupaciones,$nuevo_cargo,$usuario)
    {

        $query1="UPDATE UV_cargos_por_ocupaciones
                SET
                ocupacion_id = ".$ocupaciones.",
                descripcion='".strtoupper($nuevo_cargo)."',
                fecha_registro=NOW(),
                usuario_registro=".$usuario."
                WHERE
                cargo_ocupacion_id=".$cargo_id."";

        if(!$rst = $this->ConexionBaseDatos($query1))
        {  $cad="falla en SQL insercion".$query1;
        //return $cad;
        return $cad;
        }

       $cad=true;          
       return $cad;
    }
    
    /**
    * Funcion necesario para insertar cargos dependidnedo de una ocupacion
    * @param string $ocupacion
    * @param string $nuevo_cargo
    * @param integer $usuario
    * @return boolena true si todo ok, false si error  .
    **/

    function InsertarNuevoCargo($ocupaciones,$nuevo_cargo,$usuario)
    {  
                  $sql ="INSERT INTO
                  UV_cargos_por_ocupaciones
                  (

                    ocupacion_id,
                    descripcion,
                    usuario_registro,
                    fecha_registro,
                    sw_estado
                  )
                  values(".$ocupaciones.",
                         '".$nuevo_cargo."',
                         ".$usuario.",
                         NOW(),
                         '1')";


                 if(!$rst = $this->ConexionBaseDatos($sql))
                 {  $cad="falla en SQL insercion".$sql;
                    //return $cad;
                    return $cad;
                 }

        return true;

    }
    /**
    * funcion que sirve para obtener los cargos dependiendo de la ocupacion
    * @param string $ocupacion
    * @return array con la lista de cargos
    **/
    function Obtener_cargos_por_espacio($ocupacion)
    {
         $sql  = "SELECT *
                 FROM
                 UV_cargos_por_ocupaciones
                 WHERE ocupacion_id=".$ocupacion."
                 AND sw_estado='1'
                 ORDER BY ocupacion_id,cargo_ocupacion_id";

        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while(!$rst->EOF)
        {
            $datos[] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;



    }
    
    
     /**
    * Funcion para realizar la insercion de los agentes de riesgos
    *
    * @return array
    */             //  ocupacion,tipo_riesgo,agente_riesgo,
    function InsertarAgentePorEspacio($espacio,$tipo_riesgo,$agente_riesgo,$checar,$usuario)
    {  
            if($checar==='true')
            { $ban=1;
                $sql ="INSERT INTO
                  uv_agentes_de_riesgo_por_tipos_de_espacios
                  (
                    tipo_espacio_id,
                    tipo_riesgo_id,
                    agente_riesgo_id,
                    usuario_registro,
                    fecha_registro
                  )
                  values(".$espacio.",
                         ".$tipo_riesgo.",
                         ".$agente_riesgo.",
                         ".$usuario.",
                         NOW())";

            }
            elseif($checar==='false')
            {  $ban=0;
                $sql="DELETE
                      FROM
                      uv_agentes_de_riesgo_por_tipos_de_espacios
                      WHERE
                      tipo_espacio_id=".$espacio."
                      AND agente_riesgo_id=".$agente_riesgo."";
            }
                 if(!$rst = $this->ConexionBaseDatos($sql))
                 {  $cad="falla en SQL insercion".$sql;
                    //return $cad;
                    return $cad;
                 }

        $retorno=array();
        $retorno[0]=$ban;
        $retorno[1]=true;
        return $retorno;

    }


    /**
    * Funcion para realizar la consulta de los agentes de riesgos por espacio
    * @param  string $espacio
    * @return array $datos
    */
    function Obteneragentes_de_riesgo_por_espacio($espacio)
    {
        $sql  = "SELECT *
                 FROM
                 uv_agentes_de_riesgo_por_tipos_de_espacios
                 WHERE tipo_espacio_id=".$espacio."
                 ORDER BY tipo_espacio_id,agente_riesgo_id";

        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while(!$rst->EOF)
        {
            $datos[] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    


    
    /**
    * Funcion para realizar la consulta de los tipos de riesgos
    *
    * @return array
    */
    function ObtenerEspacios()
    {
        $sql  = "   SELECT *
                    FROM uv_tipos_de_espacios
                    WHERE sw_estado='1'
                    ";//ORDER BY 2

        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while(!$rst->EOF)
        {
            $datos[] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    



    /**
    * Funcion para realizar la insercion de los agentes de riesgos
    *
    * @return array
    */             //  ocupacion,tipo_riesgo,agente_riesgo,
    function InsertarAgentePorOcupacion($ocupacion,$tipo_riesgo,$agente_riesgo,$checar,$usuario)
    {  
            if($checar==='true')
            { $ban=1;
                $sql ="INSERT INTO
                  uv_agentes_de_riesgo_por_ocupacion
                  (
                    ocupacion_id,
                    tipo_riesgo_id,
                    agente_riesgo_id,
                    usuario_registro,
                    fecha_registro
                  )
                  values(".$ocupacion.",
                         ".$tipo_riesgo.",
                         ".$agente_riesgo.",
                         ".$usuario.",
                         NOW())";

            }
            elseif($checar==='false')
            {  $ban=0;
                $sql="DELETE
                      FROM
                      uv_agentes_de_riesgo_por_ocupacion
                      WHERE
                      ocupacion_id=".$ocupacion."
                      AND agente_riesgo_id=".$agente_riesgo."";
            }
                 if(!$rst = $this->ConexionBaseDatos($sql))
                 {  $cad="falla en SQL insercion".$sql;
                    //return $cad;
                    return $cad;
                 }

        $retorno=array();
        $retorno[0]=$ban;
        $retorno[1]=true;
        return $retorno;

    }

    
    
    /**
    * Funcion para realizar la consulta de los tipos de riesgos
    *
    * @return array
    */
    function ObtenerOcupaciones()
    {
        $sql  = "   SELECT *
                    FROM uv_ocupaciones_sd
                    WHERE sw_estado='1'
                    ";//ORDER BY 2

        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while(!$rst->EOF)
        {
            $datos[] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    /**
    * funcion que sirve para la actualizacion de tipos de riesgos
    * @param string $tipo_riesgo_id
    * @param string $color
    * @param string $usuario_registro
    * @return $cad boolean true (transaccion existosa)  false (error).
    ***/
    function UpdateAgenteDeRiesgos($agente_riesgo_id,$tipos_riesgo,$agente_de_riesgo_nom,$sw_estado,$usuario)
    {

        $query1="UPDATE uv_agentes_de_riesgos
                 SET
                 descripcion='".strtoupper($agente_de_riesgo_nom)."',
                 tipo_riesgo_id='".$tipos_riesgo."',
                 fecha_registro=NOW(),
                 usuario_registro=".$usuario.",
                 sw_estado='".$sw_estado."'

                 WHERE

                 agente_riesgo_id=".$agente_riesgo_id."";

        if(!$rst = $this->ConexionBaseDatos($query1))
        {  $cad="falla en SQL insercion".$query1;
        //return $cad;
        return $cad;
        }

       $cad=true;          
       return $cad;

    }


    /**
    * Funcion para realizar la consulta de los tipos de riesgos
    *
    * @return array
    */
    function ObtenerAgentesDeRiesgos()
    {
        $sql  = " SELECT *
                  FROM uv_agentes_de_riesgos
                  WHERE
                  sw_estado='1'
                  ORDER BY 1,2";//

        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while(!$rst->EOF)
        {
            $datos[$rst->fields[1]][] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    
    /**
    * Funcion para realizar la insercion de los agentes de riesgos
    *
    * @return array
    */
    function InsertarAgenteDeRiesgos($tipo_riesgo,$descripcion,$usuario)
    {

        //tipo_riesgo_id,
        $query1 ="INSERT INTO
                  uv_agentes_de_riesgos
                  (
                    tipo_riesgo_id,
                    descripcion,
                    usuario_registro,
                    fecha_registro,
                    sw_estado
                  )
                  values(".$tipo_riesgo.",
                         '".strtoupper($descripcion)."',
                         ".$usuario.",
                         NOW(),
                         '1')";
                         
                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {  $cad="falla en SQL insercion".$query1;
                    //return $cad;
                    return $cad;
                 }

       $cad=true;          
       return $cad;



    }

    /**
    * funcion que sirve para la actualizacion de tipos de riesgos
    * @param string $tipo_riesgo_id
    * @param string $color
    * @param string $usuario_registro
    * @return $cad boolean true (transaccion existosa)  false (error).
    ***/
    function UpdateTiposDeRiesgos($tipo_riesgo_id,$tipo_riesgo,$color,$usuario_registro)
    {

        $query1="UPDATE uv_tipos_de_riesgos
        SET
            descripcion='".strtoupper($tipo_riesgo)."',
            color='".$color."',
            fecha_registro=NOW(),
            usuario_registro=".$usuario_registro."
        WHERE
            tipo_riesgo_id=".$tipo_riesgo_id."";

        if(!$rst = $this->ConexionBaseDatos($query1))
        {  $cad="falla en SQL insercion".$query1;
        //return $cad;
        return $cad;
        }

       $cad=true;          
       return $cad;

    }



     /**
    * funcion que sirve para la actualizacion de tipos de riesgos
    * @param string $tipo_riesgo_id
    * @param string $color
    * @param string $usuario_registro
    * @return $cad boolean true (transaccion existosa)  false (error).
    ***/
    function UpdateTiposDeRiesgosSW($tipo_riesgo_id,$estado,$usuario_registro)
    {

        $query1="UPDATE uv_tipos_de_riesgos
        SET
            descripcion='".strtoupper($tipo_riesgo)."',
            color='".$color."',
            fecha_registro=NOW(),
            usuario_registro=".$usuario_registro."
        WHERE
            tipo_riesgo_id=".$tipo_riesgo_id."";

        if(!$rst = $this->ConexionBaseDatos($query1))
        {  $cad="falla en SQL insercion".$query1;
        //return $cad;
        return $cad;
        }

       $cad=true;          
       return $cad;

    }
    /**
    * Funcion para realizar la consulta de los tipos de riesgos
    *
    * @return array
    */
    function ObtenerTiposDeRiesgos()
    {
        $sql  = "SELECT *
                 FROM
                 UV_tipos_de_riesgos
                 ORDER BY tipo_riesgo_id";

        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while(!$rst->EOF)
        {
            $datos[] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    /**
    * Funcion para realizar la consulta de los tipos de riesgos
    *
    * @return array
    */
    function Obteneragentes_de_riesgo_por_ocupacion($ocupacion)
    {
        $sql  = "SELECT *
                 FROM
                 UV_agentes_de_riesgo_por_ocupacion
                 WHERE ocupacion_id=".$ocupacion."
                 ORDER BY ocupacion_id,agente_riesgo_id";

        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while(!$rst->EOF)
        {
            $datos[] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    
    /**
    * Funcion para realizar la consulta de los tipos de riesgos
    *
    * @return array
    */
    function InsertarTiposDeRiesgos($descripcion,$color,$usuario)
    {

        //tipo_riesgo_id,
        $query1 ="INSERT INTO
                  UV_tipos_de_riesgos
                  (
                    
                    descripcion,
                    color,
                    sw_estado,
                    fecha_registro,
                    usuario_registro
                  )
                  values('".strtoupper($descripcion)."',
                         '".$color."',
                         '1',
                         NOW(),
                         ".$usuario.")";
                         
                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {  $cad="falla en SQL insercion".$query1;
                    //return $cad;
                    return $cad;
                 }

       $cad=true;          
       return $cad;



    }


    /**
    * Obtiene los tipos de afiliados registrados en la base de datos
    *
    * @return array
    */
    function ObtenerTiposAfiliados()
    {
        $sql  = "SELECT eps_tipo_afiliado_id,";
        $sql .= "               descripcion_eps_tipo_afiliado ";
        $sql .= "FROM       eps_tipos_afiliados ";

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
    * Funcion domde se seleccionan los tipos de id de los terceros
    *
    * @return array datos de tipo_id_terceros
    */
    function ObtenerTiposIdentificacion()
    {
        $sql  = "SELECT tipo_id_paciente,";
        $sql .= "       descripcion ";
        $sql .= "FROM   tipos_id_pacientes ";

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
    * Consulta en la base de datos los diferentes tipos de estado civil
    * registrados
    *
    * @return array
    */
    function ObtenerTiposEstadoCivil()
    {
        $sql  = "SELECT tipo_estado_civil_id,";
        $sql .= "               descripcion ";
        $sql .= "FROM       tipo_estado_civil ";
        $sql .= "WHERE  tipo_estado_civil_id !=0 ";
        $sql .= "ORDER BY indice_de_orden ";

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
    * Consulta en la base de datos los diferentes tipos de estado civil
    * registrados
    *
    * @return array
    */
    function ObtenerTiposEstratosSocioeconomicos()
    {
        $sql  = "SELECT estrato_socioeconomico_id,";
        $sql .= "               descripcion_estrato_socioeconomico ";
        $sql .= "FROM       estratos_socioeconomicos ";
        $sql .= "ORDER BY descripcion_estrato_socioeconomico ";

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
    * Consulta en la base de datos los diferentes estados de un afiliado.
    * registrados
    *
    * @return array
    */
    function ObtenerTiposEstadosAfiliados()
    {
        $sql  = "SELECT estado_afiliado_id, descripcion_estado ";
        $sql .= "FROM eps_afiliados_estados ";
        $sql .= "ORDER BY descripcion_estado ";

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
    * Consulta en la base de datos los diferentes subestados de un afiliado.
    * registrados
    *
    * @param string $estado_afiliado_id (opcional, para traer los subestados de un solo estado)
    *
    * @return array
    */
    function ObtenerTiposSubestadosAfiliados($estado_afiliado_id = null)
    {
        if(empty($estado_afiliado_id))
        {
            $sql  = "SELECT estado_afiliado_id, subestado_afiliado_id, descripcion_subestado ";
            $sql .= "FROM eps_afiliados_subestados ";
            $sql .= "ORDER BY estado_afiliado_id,descripcion_subestado ";
        }
        else
        {
            $sql  = "SELECT subestado_afiliado_id, descripcion_subestado ";
            $sql .= "FROM eps_afiliados_subestados ";
            $sql .= "WHERE estado_afiliado_id = '$estado_afiliado_id' ";
            $sql .= "ORDER BY descripcion_subestado ";
        }

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
    * Consulta de las dependencias de UV
    * registrados
    *
    * @return array
    */
    function ObtenerDependenciasUV()
    {
        $sql  = "SELECT codigo_dependencia_id, descripcion_dependencia ";
        $sql .= "FROM uv_dependencias ";
        $sql .= "ORDER BY descripcion_dependencia ";

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
    * Consulta de las dependencias de UV
    * registrados
    *
    * @return array
    */
    function ObtenerEstamentos()
    {
        $sql  = "SELECT estamento_id, descripcion_estamento ";
        $sql .= "FROM eps_estamentos ";
        $sql .= "ORDER BY descripcion_estamento ";

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
    * Obtiene los tipos de afiliados registrados en la base de datos
    *
    * @return array
    */
    function ObtenerTiposAportantes()
    {
        $sql  = "SELECT tipo_aportante_id, descripcion_tipo_aportante ";
        $sql .= "FROM eps_tipos_aportantes ";
        $sql .= "ORDER BY descripcion_tipo_aportante ";

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
    * Consulta de las diferentes administradoras de fondos de pensiones registradas
    *
    * @return array
    */
    function ObtenerFondosPensiones()
    {
        $sql  = "SELECT codigo_afp,";
        $sql .= "       razon_social_afp ";
        $sql .= "FROM   administradoras_de_fondos_de_pensiones ";
        $sql .= "ORDER BY razon_social_afp ";

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
    * Consulta de las diferentes administradoras de fondos de pensiones registradas
    *
    * @return array
    */
    function ObtenerEPS()
    {
        $sql  = "SELECT codigo_sgss_eps,";
        $sql .= "       razon_social_eps ";
        $sql .= "FROM   entidades_promotoras_de_salud ";
        $sql .= "ORDER BY razon_social_eps ";

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
    * Consulta de las divisiones de las actividades economicas
    *
    * @return array
    */
    function ObtenerDivisionActividadEconomica()
    {
        $sql  = "SELECT ciiu_r3_division,";
        $sql .= "       descripcion_ciiu_r3_division ";
        $sql .= "FROM   ciiu_r3_divisiones ";
        $sql .= "ORDER BY descripcion_ciiu_r3_division ";

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
    * Consulta de las divisiones de las actividades economicas
    *
    * @param string $division Division a la que pertenece el grupo
    *
    * @return array
    */
    function ObtenerGruposActividadEconomica($division)
    {
        $sql  = "SELECT ciiu_r3_grupo,";
        $sql .= "       descripcion_ciiu_r3_grupo ";
        $sql .= "FROM   ciiu_r3_grupos ";
        $sql .= "WHERE  ciiu_r3_division = '".$division."' ";
        $sql .= "ORDER BY descripcion_ciiu_r3_grupo ";

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
    * Consulta de los grupos de ocupaciones existentes
    *
    * @return array
    */
    function ObtenerGruposOcupacion()
    {
        $sql  = "SELECT ciuo_88_gran_grupo,";
        $sql .= "       descripcion_ciuo_88_gran_grupo ";
        $sql .= "FROM   ciuo_88_grandes_grupos ";
        $sql .= "ORDER BY descripcion_ciuo_88_gran_grupo ";

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
    * Consulta las ocurrencias que hay de acuerdo al tipo de
    * identificacion y el documento de identificacion
    *
    * @param string $tipo_documento_id Tipo de identificacion
    * @param string $documento_id Numero de identificacion
    *
    * @return boolean
    */
    function VerificarExistenciaAfiliado($tipo_documento_id,$documento_id)
    {
      $sql  = "SELECT AD.afiliado_tipo_id   , ";
      $sql .= "       AD.afiliado_id ";
      $sql .= "FROM   eps_afiliados_datos AD ";
      $sql .= "WHERE  AD.afiliado_tipo_id = '".$tipo_documento_id."' ";
      $sql .= "AND    AD.afiliado_id = '".$documento_id."' ";
          
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      if(empty($datos))
        return true;
      
      return false;
    }
    /**
    * Consulta de los subgrupos principlaes de ocupaciones existentes
    *
    * @param string $grupo Identificador del grupo al que pertenece el subgrupo principal
    *
    * @return array
    */
    function ObtenerSubGruposPrincipalesOcupacion($grupo)
    {
        $sql  = "SELECT ciuo_88_subgrupo_principal,";
        $sql .= "       descripcion_ciuo_88_subgrupo_principal ";
        $sql .= "FROM   ciuo_88_subgrupos_principales ";
        $sql .= "WHERE  ciuo_88_gran_grupo = '".$grupo."' ";
        $sql .= "ORDER BY descripcion_ciuo_88_subgrupo_principal ";

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
    * Consulta de los terceros con los que se tiene convenios
    *
    * @return array
    */
    function ObtenerTercerosConvenios()
    {
        $sql  = "SELECT TE.tipo_id_tercero    ,";
        $sql .= "       TE.tercero_id, ";
        $sql .= "       TE.nombre_tercero ";
        $sql .= "FROM   terceros_uv_convenios TC, ";
        $sql .= "       terceros TE ";
        $sql .= "WHERE  TE.tipo_id_tercero = TC.tipo_id_tercero ";
        $sql .= "AND    TE.tercero_id = TC.tercero_id ";
        $sql .= "AND    sw_estado = '1' ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        while(!$rst->EOF)
        {
          $datos[] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }
    /**
    * Consulta de los subgrupos de ocupaciones existentes
    *
    * @param string $grupo Identificador del grupo al que pertenece el subgrupo
    * @param string $subgrupo_pr Identificador del subgrupo principal al que
    *               pertenece el subgrupo
    *
    * @return array
    */
    function ObtenerSubGruposOcupacion($grupo,$subgrupo_pr)
    {
        $sql  = "SELECT ciuo_88_subgrupo,";
        $sql .= "       descripcion_ciuo_88_subgrupo ";
        $sql .= "FROM   ciuo_88_subgrupos ";
        $sql .= "WHERE  ciuo_88_gran_grupo = '".$grupo."' ";
        $sql .= "AND    ciuo_88_subgrupo_principal = '".$subgrupo_pr."'";
        $sql .= "ORDER BY descripcion_ciuo_88_subgrupo ";

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
    * Consulta de los grupos primarios de ocupaciones existentes
    *
    * @param string $grupo Identificador del grupo al que pertenece el subgrupo
    * @param string $subgrupo_pr Identificador del subgrupo principal al que
    *               pertenece el subgrupo
    * @param string $subgrupo Identificador del subgrupo al que pertenece
    *               el grupo primario
    *
    * @return array
    */
    function ObtenerGruposPrimariosOcupacion($grupo,$subgrupo_pr,$subgrupo)
    {
        $sql  = "SELECT ciuo_88_grupo_primario,";
        $sql .= "       descripcion_ciuo_88_grupo_primario ";
        $sql .= "FROM   ciuo_88_grupos_primarios ";
        $sql .= "WHERE  ciuo_88_gran_grupo = '".$grupo."' ";
        $sql .= "AND    ciuo_88_subgrupo_principal = '".$subgrupo_pr."' ";
        $sql .= "AND    ciuo_88_subgrupo = '".$subgrupo."' ";
        $sql .= "ORDER BY descripcion_ciuo_88_grupo_primario ";

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
    * Consulta la informacion de un grupo primario
    *
    * @param string $grupo Identificador del grupo primario que se esta buscando
    *
    * @return array
    */
    function ObtenerDatosGrupoPrimario($grupo)
    {
        $sql  = "SELECT ciuo_88_gran_grupo,";
        $sql .= " 	    ciuo_88_subgrupo_principal 	,";
        $sql .= " 	    ciuo_88_subgrupo 	,";
        $sql .= " 	    ciuo_88_grupo_primario ";
        $sql .= "FROM   ciuo_88_grupos_primarios ";
        $sql .= "WHERE  ciuo_88_grupo_primario = '".$grupo."'";

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
    /**
    * Consulta la informacion de afiliados ingrerados anteriormente
    *
    * @param array $datos Vector con la informacion del tipo e identificacion
    *               del afiliado
    * @param string $opr Cadena que contiene la operacion para realizar el filtro 
    *               de la consulta
    *
    * @return array
    */
    function ObtenerDatosAfiliados($datos,$opr)
    {
        $sql  = "SELECT AD.afiliado_tipo_id   , ";
        $sql .= "       AD.afiliado_id, ";
        $sql .= "       AD.primer_apellido    , ";
        $sql .= "       AD.segundo_apellido   , ";
        $sql .= "       AD.primer_nombre  , ";
        $sql .= "       AD.segundo_nombre     , ";
        $sql .= "       TO_CHAR(AD.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
        $sql .= "       TO_CHAR(AD.fecha_afiliacion_sgss,'DD/MM/YYYY') AS fecha_afiliacion_sgss, ";
        $sql .= "       AD.tipo_sexo_id   , ";
        $sql .= "       AD.tipo_pais_id   , ";
        $sql .= "       AD.tipo_dpto_id   , ";
        $sql .= "       AD.tipo_mpio_id   , ";
        $sql .= "       AD.zona_residencia    , ";
        $sql .= "       AD.direccion_residencia   , ";
        $sql .= "       AD.telefono_residencia    , ";
        $sql .= "       AD.telefono_movil     , ";
        $sql .= "       MAX(AF.eps_afiliacion_id) AS eps_afiliacion_id  , ";
        $sql .= "       TP.pais ||'-'||TD.departamento||'-'||TM.municipio AS departamento_municipio ";
        $sql .= "FROM   eps_afiliados_datos AD,";
        $sql .= "       eps_afiliados AF,";
        $sql .= "       tipo_pais TP,";
        $sql .= "       tipo_dptos TD,";
        $sql .= "       tipo_mpios TM ";
        if($datos['afiliado_tipo_id'])
        {
          $sql .= "WHERE  AD.afiliado_tipo_id = '".$datos['afiliado_tipo_id']."' ";
          $sql .= "AND    AD.afiliado_id = '".$datos['afiliado_id']."' ";
          
          if($datos['eps_afiliacion_id'])
            $sql .= "AND    AF.eps_afiliacion_id = ".$datos['eps_afiliacion_id']." ";
        }
        else
        {
          $sql .= "WHERE  AD.afiliado_tipo_id = '".$datos['tipo_id_paciente']."' ";
          $sql .= "AND    AD.afiliado_id = '".$datos['documento']."' ";
        }
        $sql .= "AND    AD.tipo_pais_id = TP.tipo_pais_id ";
        $sql .= "AND    AD.tipo_dpto_id = TD.tipo_dpto_id ";
        $sql .= "AND    AD.tipo_mpio_id = TM.tipo_mpio_id ";
        $sql .= "AND    AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
        $sql .= "AND    AD.afiliado_id = AF.afiliado_id ";
        $sql .= "AND    AF.estado_afiliado_id $opr IN('RE','DE') ";
        $sql .= "AND    TD.tipo_pais_id = TP.tipo_pais_id ";
        $sql .= "AND    TM.tipo_pais_id = TD.tipo_pais_id ";
        $sql .= "AND    TM.tipo_dpto_id = TD.tipo_dpto_id ";
        $sql .= "GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,18 ";
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
    /**
    * Consulta la informacion del lugar de residencia por defecto
    *
    * @param array $datos Vector con la informacion delpais, municipio
    *               y ciudad por defecto
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
    /**
    * Consulta la informacion de los tipos de parentescos
    *
    * @param string $parentesco_id parametro opcional, para cuando se necesita
    *               una informacion especifica del parentesco
    *
    * @return array
    */
    function ObtenerTiposParentescos($parentesco_id = null)
    {
        $sql  = "SELECT parentesco_id,";
        $sql .= "       descripcion_parentesco,";
        $sql .= "       mensaje_confirmar_afiliacion ";
        $sql .= "FROM   eps_parentescos_beneficiarios ";

        if($parentesco_id)
            $sql .= "WHERE  parentesco_id = '".$parentesco_id."' ";

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
    * Copnsulta los permisos que posee el usuario que ingresa al modulo+
    *
    * @return array
    */
    function ObtenerPermisos()
    {
        $sql  = "SELECT usuario_id ,";
        $sql .= "       perfil_id ";
        $sql .= "FROM   userpermisos_eps_afiliaciones ";
        $sql .= "WHERE  usuario_id = ".UserGetUID()." ";

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
    * Funcion que permite obtener el nombre de un usuario
    * @param string $usuario_id
    * @return array $datos con el nombre del usuario 
    **/
    function GetNombreUsuario($usuario_id)
    {
        $sql="  SELECT nombre
                FROM system_usuarios
                WHERE usuario_id='".trim($usuario_id)."'";
                
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

        $datos=Array();
        while(!$resultado->EOF)
        {
        $datos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
        }

        $resultado->Close();
        return $datos;
    }
    /**
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		*
		* @param String $sql Cadena que contiene la consulta sql del conteo
    * @param int $pg_siguiente Indica el numero de la pagina que se desea ver
		* @param int $num_reg numero que define el limite de datos,cuando no se desa el del
		* 			 usuario,si no se pasa se tomara por defecto el del usuario
    * @param int $limite Indica el limite que se desea ver, si no esta se pondra el 
    *        definido para el usuario en la base de datos    
		* @return boolean
		*/
		function ProcesarSqlConteo($sql,$pg_siguiente = 0,$num_reg = 0,$limite = 0)
		{
			$this->offset = 0;
			$this->pagina = 1;
			if($limite === 0)
			{
				$this->limit = GetLimitBrowser();
				if(!$this->limit) $this->limit = 20;
			}
			else
			{
				$this->limit = $limite;
			}

			if($pg_siguiente)
			{
				$this->pagina = intval($pg_siguiente);
				if($this->pagina > 1)
					$this->offset = ($this->pagina - 1) * ($this->limit);
			}

			if(!$num_reg)
			{
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

				if(!$rst->EOF)
				{
					$this->conteo = $rst->fields[0];
					$rst->MoveNext();
				}
				$rst->Close();
			}
			else
			{
				$this->conteo = $num_reg;
			}
			return true;
		}
    /**
    * Funcion que permite crear una transaccion
    * @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
    * @param boolean $asoc Indica el modo en el cual se hara la ejecucion del query,
    *                por defecto es false
    * @return object $rst Objeto de la transaccion - Al momento de iniciar la transaccion no
    *                se devuelve nada
    */
    function ConexionTransaccion($sql,$asoc = false)
    {
      GLOBAL $ADODB_FETCH_MODE;

      if(!$sql)
      {
        list($this->dbconn) = GetDBconn();
        //$this->dbconn->debug=true;
        $this->dbconn->BeginTrans();
      }
      else
      {
        if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

        $rst = $this->dbconn->Execute($sql);

        if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($this->dbconn->ErrorNo() != 0)
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = $this->dbconn->ErrorMsg()."<br>".$sql;
          $this->dbconn->RollbackTrans();
          return false;
        }
        return $rst;
      }
    }
    /**
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la
    * consulta sql
    *
    * @param string $sql sentencia sql a ejecutar
    * @param boolean $asoc Indica el modo en el cual se hara la ejecucion del query,
    *                      por defecto es false
    * @param boolean $debug Permite activar el debug del 
    * @return object $rst
    */
    function ConexionBaseDatos($sql,$asoc = false,$debug = false)
    {
      GLOBAL $ADODB_FETCH_MODE;
      list($dbconn)=GetDBConn();
      $dbconn->debug=$debug;

      if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

      $rst = $dbconn->Execute($sql);

      if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

      $this->error = $sql;
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = $dbconn->ErrorMsg();
        return false;
      }
      return $rst;
    }
}
?>