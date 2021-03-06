<?php
/**
* @package IPSOFT-SIIS
* @version $Id: ConsultarAfiliados.class.php,v 1.29 2007/11/08 21:32:38 hugo Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @author jaime gomez
*/
/**
* Clase: ConsultarAfiliados
* Clase para consulta de afiliados al sistema EPS
*
* @package IPSOFT-SIIS
* @version $Revision: 1.29 $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @author jaime gomez 
*/

IncludeClass("Afiliaciones1", "", "app","UV_MedicinaFamiliar");

class ConsultarAfiliados extends Afiliaciones1
{
    /**
    * Constructor de la clase
    */
    function ConsultarAfiliados(){}

    function ObternerListadeCotizantesPorMedico($tipo_id_medico,$medico_id)
    {
         $sql="   SELECT
                a.eps_afiliacion_id,
                a.tipo_id_cotizante,
                a.cotizante_id,
                (b.primer_apellido || ' ' || b.segundo_apellido  || ' ' || b.primer_nombre  || ' ' || b.segundo_nombre ) as nombre_afiliado
                FROM
                gruposfamiliarespormedico as a,
                eps_afiliados_datos as b
                WHERE
                a.tipo_id_medico='".$tipo_id_medico."'
                AND a.medico_id='".$medico_id."'
                AND b.afiliado_tipo_id = a.tipo_id_cotizante
                AND b.afiliado_id = a.cotizante_id";

            if(!$result = $this->ConexionBaseDatos($sql,true))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                return false;
            }

            $retorno = array();

            while($fila = $result->FetchRow())
            {
                $retorno[]=$fila;
            }
            $result->Close();


            return $retorno;
    }

    /**
    * funcion que sirve para listar los medicos y obtener el numero de grupos familiares que tiene asignado cada medico
    * @return array 
    **/
    function ListarMedicosCon_N_gf()
    {

        $sql=" SELECT
                
                a.tipo_id_tercero,
                a.tercero_id,
                a.usuario_profesional,
                a.nombre_tercero as nombre,
                count(x.*) as grupos_de_familia
                FROM
                (
                SELECT
                DISTINCT(d.nombre_tercero),
                b.tercero_id ,
                b.tipo_id_tercero,
                b.usuario_id as usuario_profesional,
                c.estado
                from agenda_turnos as a
                    left join profesionales_estado as c
                        on (a.profesional_id=c.tercero_id
                            and a.tipo_id_profesional=c.tipo_id_tercero
                            and c.empresa_id='01'),
                    profesionales as b,
                    terceros as d
                    WHERE
                    
                    a.empresa_id='01'
                    and a.tipo_id_profesional=b.tipo_id_tercero
                    and a.profesional_id=b.tercero_id
                    and a.tipo_id_profesional=d.tipo_id_tercero
                    and a.profesional_id=d.tercero_id
                    
                    
                ) as a
                 left join gruposfamiliarespormedico as x
                    on (
                          a.tipo_id_tercero=x.tipo_id_medico
                          AND  a.tercero_id=x.medico_id
                       )
                    where a.estado is null or a.estado=1 group by 1,2,3,4";

                    if(!$result = $this->ConexionBaseDatos($sql,true))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
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
    * Metodo para obtener los MEDICOS DISPONIBLES
    * @return array
    * @access public
    */
    function BuscarMedicosAntesdeRegistrar($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id)
    {
      $sql="SELECT *
            FROM
            GruposFamiliaresPorMedico
            WHERE
            eps_afiliacion_id='".$eps_afiliacion_id."'
            AND tipo_id_cotizante='".$afiliado_tipo_id."'
            AND cotizante_id='".$afiliado_id."'";

        if(!$result = $this->ConexionBaseDatos($sql,true))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

            $retorno = array();

            while($fila = $result->FetchRow())
            {
                $retorno[]=$fila;
            }
            $result->Close();

        if(!empty($retorno))
        {

            $query1 ="DELETE FROM
                      GruposFamiliaresPorMedico
                    WHERE
                    eps_afiliacion_id='".$eps_afiliacion_id."'
                    AND tipo_id_cotizante='".$afiliado_tipo_id."'
                    AND cotizante_id='".$afiliado_id."'";


                            
                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {
                    $cad="falla en SQL ELIMINACION".$query1;
                    return $cad;
                 }
                else
                {
                   return true;
                } 
            

        }
        return true;







        
        return  $retorno;

    }
     /**
    * Metodo para obtener los usuarios afiliados al sistema de EPS
    *
    * @param array $filtros
    * @param boolean $count (opcional) True/False indica si retorna el numero de registros o los registros
    * @param integer $limit (opcional) limite de registros que retorna la consulta
    * @param integer $offset (opcional) desde que registro inicia la consulta
    * @return array
    * @access public
    */

    function AsignarMedicoBD($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id,$tipo_id_tercero,$tercero_id,$usuario_profesional,$usuario)
    {
                   $query1 ="INSERT INTO
                  GruposFamiliaresPorMedico
                  (
                    tipo_id_medico, 
                    medico_id,
                    usuario_id,
                    eps_afiliacion_id,
                    tipo_id_cotizante,
                    cotizante_id,
                    fecha_registro,
                    usuario_registro
                  )
                  values('".$tipo_id_tercero."',
                         '".$tercero_id."',
                         ".$usuario_profesional.",
                         '".$eps_afiliacion_id."',
                         '".$afiliado_tipo_id."',
                         '".$afiliado_id."',
                         NOW(),
                         '".$usuario."')";
                         
                 if(!$rst = $this->ConexionBaseDatos($query1)) 
                 {  $cad="falla en SQL insercion".$query1;
                    //return $cad;
                    return $cad;
                 }

       $cad=true;          
       return $cad;
    
    }


    
    /**
    * Metodo para obtener los MEDICOS DISPONIBLES
    * @return array
    * @access public
    */
    function ObtenerMedicos()
    {
      $sql="SELECT
                a.nombre_tercero as nombre,
                a.tercero_id,
                a.tipo_id_tercero,
                a.usuario_profesional
                FROM
                (
                SELECT
                DISTINCT(d.nombre_tercero),
                b.tercero_id ,
                b.tipo_id_tercero,
                b.usuario_id as usuario_profesional,
                c.estado
                from agenda_turnos as a
                    left join profesionales_estado as c
                        on (a.profesional_id=c.tercero_id
                            and a.tipo_id_profesional=c.tipo_id_tercero
                            and c.empresa_id='01'),
                    profesionales as b,
                    terceros as d
                    WHERE
                    
                    a.empresa_id='01'
                    and a.tipo_id_profesional=b.tipo_id_tercero
                    and a.profesional_id=b.tercero_id
                    and a.tipo_id_profesional=d.tipo_id_tercero
                    and a.profesional_id=d.tercero_id
                    
                    
                ) as a
                    where a.estado is null or a.estado=1 order by a.nombre_tercero;";


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

        return  $retorno;

    }
    /**
    * Metodo para obtener los usuarios afiliados al sistema de EPS
    *
    * @param array $filtros
    * @param boolean $count (opcional) True/False indica si retorna el numero de registros o los registros
    * @param integer $limit (opcional) limite de registros que retorna la consulta
    * @param integer $offset (opcional) desde que registro inicia la consulta
    * @return array
    * @access public
    */
    function GetAfiliados($filtros=array(), $count=null, $limit=null, $offset=null)
    {
        $filtro = "";
        $filtros['primer_apellido'] = trim($filtros['primer_apellido']);
        $filtros['segundo_apellido'] = trim($filtros['segundo_apellido']);


        if($filtros['afiliado_tipo_id'] && $filtros['afiliado_id'])
        {
            $filtro .= " AND a.afiliado_tipo_id = '".$filtros['afiliado_tipo_id']."' ";
            $filtro .= " AND a.afiliado_id = '".$filtros['afiliado_id']."' ";
        }
        elseif($filtros['primer_apellido'] || $filtros['segundo_apellido'] || $filtros['nombre_afiliado'])
        {
            if($filtros['primer_apellido'])
            {
                $filtro .= " AND b.primer_apellido ILIKE '".$filtros['primer_apellido']."' ";
            }

            if($filtros['segundo_apellido'])
            {
                $filtro .= " AND b.segundo_apellido ILIKE '".$filtros['segundo_apellido']."' ";
            }

            if($filtros['nombre_afiliado'])
            {
                $N = str_replace ("%", " ", $filtros['nombre_afiliado']);
                $N = explode(" ",preg_replace("/\s{2,}/"," ",trim($N)));

                if(count($N)>1)
                {
                    $filtro .= " AND (b.primer_nombre ILIKE '%$N[0]%' AND b.segundo_nombre LIKE '%$N[1]%') ";
                }
                else
                {
                    if(!empty($N[0]))
                    {
                        $filtro .= " AND (b.primer_nombre ILIKE '%$N[0]%' OR b.segundo_nombre LIKE '%$N[0]%') ";
                    }
                }
            }
        }
        else
        {
            if($filtros['estado_afiliado_id'])
            {
                $filtro .= " AND a.estado_afiliado_id = '".$filtros['estado_afiliado_id']."' ";
            }

            if($filtros['subestado_afiliado_id'])
            {
                $filtro .= " AND a.subestado_afiliado_id = '".$filtros['subestado_afiliado_id']."' ";
            }

            if($filtros['estamento_id'])
            {
                $filtro .= " AND f.estamento_id = '".$filtros['estamento_id']."' ";
            }

            if($filtros['codigo_dependencia_id'])
            {
                $filtro .= " AND f.codigo_dependencia_id = '".$filtros['codigo_dependencia_id']."' ";
            }

            if($filtros['tipo_aportante_id'])
            {
                $filtro .= " AND f.tipo_aportante_id = '".$filtros['tipo_aportante_id']."' ";
            }
            //if($filtros['eps_tipo_afiliado_id'])
           // {
             //   $filtro .= " AND a.eps_tipo_afiliado_id = 'C'";
           // }

            if($filtros['tipo_sexo_id'])
            {
                $filtro .= " AND b.tipo_sexo_id = '".$filtros['tipo_sexo_id']."' ";
            }

            if(is_numeric($filtros['edad_min']))
            {
                $filtro .= " AND b.fecha_nacimiento <= (now() - interval '".$filtros['edad_min']." years')::date";
            }

            if(is_numeric($filtros['edad_max']))
            {
                $filtro .= " AND b.fecha_nacimiento > (now() - interval '" . ($filtros['edad_max'] + 1) . " years')::date";
            }

            if(($filtros['fecha1']))
            {
                $filtro .= " AND a.fecha_afiliacion >= '".$filtros['fecha1']."' ";
            }

            if(($filtros['fecha2']))
            {
                $filtro .= " AND a.fecha_afiliacion <= '".$filtros['fecha2']."' ";
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

            $filtro .= " AND a.eps_tipo_afiliado_id = 'C'";
        if(empty($count))
        {
            $select = "
                        a.eps_afiliacion_id,
                        a.afiliado_tipo_id,
                        a.afiliado_id,
                        (b.primer_apellido || ' ' || b.segundo_apellido  || ' ' || b.primer_nombre  || ' ' || b.segundo_nombre ) as nombre_afiliado,
                        a.eps_tipo_afiliado_id,
                        c.descripcion_eps_tipo_afiliado,
                        a.fecha_afiliacion,
                        a.estado_afiliado_id,
                        a.subestado_afiliado_id,
                        e.descripcion_estado,
                        d.descripcion_subestado,
                        f.estamento_id,
                        g.descripcion_estamento,
                        f.codigo_dependencia_id,
                        h.descripcion_dependencia,
                        f.tipo_aportante_id,
                        i.descripcion_tipo_aportante,
                        x.tipo_id_medico,
                        x.medico_id,
                        p.nombre as nombre_profesional
            ";
        }
        else
        {
            $select = " COUNT(*) as cantidad";
        }

        $sql  = "
                    SELECT $select

                    FROM
                        eps_afiliados as a
                            LEFT JOIN eps_afiliados_cotizantes AS f
                            ON
                            (
                                f.eps_afiliacion_id = a.eps_afiliacion_id
                                AND f.afiliado_tipo_id = a.afiliado_tipo_id
                                AND f.afiliado_id = a.afiliado_id
                            )
                            LEFT JOIN  eps_estamentos AS g
                            ON
                            (
                                g.estamento_id = f.estamento_id
                            )
                            LEFT JOIN uv_dependencias AS h
                            ON
                            (
                                h.codigo_dependencia_id = f.codigo_dependencia_id
                            )
                            LEFT JOIN eps_tipos_aportantes AS i
                            ON
                            (
                                i.tipo_aportante_id = f.tipo_aportante_id
                            )
                            LEFT JOIN gruposfamiliarespormedico AS x
                            ON
                            (
                                f.eps_afiliacion_id = x.eps_afiliacion_id
                                AND f.afiliado_tipo_id = x.tipo_id_cotizante
                                AND f.afiliado_id = x.cotizante_id
                            )
                            LEFT JOIN profesionales AS p
                            ON
                            (
                                 x.tipo_id_medico=p.tipo_id_tercero
                                 AND x.medico_id = p.tercero_id
                            ),
                        eps_afiliados_datos as b,
                        eps_tipos_afiliados as c,
                        eps_afiliados_subestados as d,
                        eps_afiliados_estados as e
                        

                    WHERE
                        b.afiliado_tipo_id = a.afiliado_tipo_id
                        AND b.afiliado_id = a.afiliado_id
                        AND c.eps_tipo_afiliado_id = a.eps_tipo_afiliado_id
                        AND d.estado_afiliado_id = a.estado_afiliado_id
                        AND d.subestado_afiliado_id = a.subestado_afiliado_id
                        AND e.estado_afiliado_id = d.estado_afiliado_id
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
    * Metodo para obtener la informacion de un afiliado del sistema de EPS
    *
    * @param array $filtros
    * @param integer  $eps_afiliacion_id ID DE LA AFILIACION
    * @param string  $afiliado_tipo_id TIPO DE IDENTIFICACION DEL AFILIADO
    * @param string  $afiliado_id NUMERO DE IDENTIFICACION DEL AFILIADO
    * @return array
    * @access public
    */
    function GetDatosAfiliado($eps_afiliacion_id, $afiliado_tipo_id, $afiliado_id)
    {
        $sql = "
                    SELECT
                        datos.*,
                        g.descripcion_ciuo_88_grupo_primario,
                        c.razon_social_eps as razon_social_eps_anterior

                    FROM
                    (
                        SELECT

                        a.eps_afiliacion_id,
                        a.afiliado_tipo_id,
                        a.afiliado_id,
                        a.eps_tipo_afiliado_id,
                        l.descripcion_eps_tipo_afiliado,
                        a.fecha_afiliacion,
                        a.eps_anterior,
                        a.fecha_afiliacion_eps_anterior,
                        a.semanas_cotizadas_eps_anterior,
                        a.semanas_cotizadas,
                        a.estado_afiliado_id,
                        d.descripcion_estado,
                        a.subestado_afiliado_id,
                        e.descripcion_subestado,
                        a.observaciones,
                        b.primer_apellido,
                        b.segundo_apellido,
                        b.primer_nombre,
                        b.segundo_nombre,
                        (b.primer_apellido || ' ' || b.segundo_apellido  || ' ' || b.primer_nombre  || ' ' || b.segundo_nombre ) as nombre_afiliado,
                        b.fecha_nacimiento,
                        b.fecha_afiliacion_sgss,
                        b.tipo_sexo_id,
                        f.descripcion_eps_tipo_sexo_id,
                        b.ciuo_88_grupo_primario,
                        b.tipo_pais_id,
                        b.tipo_dpto_id,
                        b.tipo_mpio_id,
                        h.pais,
                        i.departamento,
                        j.municipio,
                        b.zona_residencia,
                        k.descripcion as descripcion_zona_residencia,
                        b.direccion_residencia,
                        b.telefono_residencia,
                        b.telefono_movil


                        FROM

                        eps_afiliados as a,
                        eps_afiliados_datos as b,
                        eps_afiliados_estados as d,
                        eps_afiliados_subestados as e,
                        eps_tipos_sexo as f,
                        tipo_pais as h,
                        tipo_dptos as i,
                        tipo_mpios as j,
                        zonas_residencia as k,
                        eps_tipos_afiliados as l


                        WHERE

                        a.eps_afiliacion_id = $eps_afiliacion_id
                        AND a.afiliado_tipo_id = '$afiliado_tipo_id'
                        AND a.afiliado_id = '$afiliado_id'
                        AND b.afiliado_tipo_id = a.afiliado_tipo_id
                        AND b.afiliado_id = a.afiliado_id
                        AND d.estado_afiliado_id = a.estado_afiliado_id
                        AND e.estado_afiliado_id = a.estado_afiliado_id
                        AND e.subestado_afiliado_id = a.subestado_afiliado_id
                        AND f.tipo_sexo_id = b.tipo_sexo_id
                        AND h.tipo_pais_id = b.tipo_pais_id
                        AND i.tipo_pais_id = b.tipo_pais_id
                        AND i.tipo_dpto_id = b.tipo_dpto_id
                        AND j.tipo_pais_id = b.tipo_pais_id
                        AND j.tipo_dpto_id = b.tipo_dpto_id
                        AND j.tipo_mpio_id = b.tipo_mpio_id
                        AND k.zona_residencia = b.zona_residencia
                        AND l.eps_tipo_afiliado_id = a.eps_tipo_afiliado_id
                    ) as datos
                    LEFT JOIN ciuo_88_grupos_primarios as g ON (g.ciuo_88_grupo_primario = datos.ciuo_88_grupo_primario)
                    LEFT JOIN entidades_promotoras_de_salud as c ON (c.codigo_sgss_eps = datos.eps_anterior)

        ";


        if(!$result = $this->ConexionBaseDatos($sql,true))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        switch($retorno['eps_tipo_afiliado_id'])
        {
            case 'C':

            $sql = "
                    SELECT
                        datos.*,
                        p.razon_social_afp

                    FROM
                    (
                        SELECT
                        a.ciiu_r3_division,
                        a.ciiu_r3_grupo,
                        b.descripcion_ciiu_r3_grupo,
                        a.telefono_dependencia,
                        a.estrato_socioeconomico_id,
                        c.descripcion_estrato_socioeconomico,
                        a.tipo_estado_civil_id,
                        d.descripcion as descripcion_estado_civil,
                        a.tipo_aportante_id,
                        e.descripcion_tipo_aportante,
                        a.estamento_id,
                        f.descripcion_estamento,
                        a.codigo_afp,
                        a.ingreso_mensual,
                        a.fecha_ingreso_laboral,
                        a.codigo_dependencia_id,
                        g.descripcion_dependencia

                        FROM

                        eps_afiliados_cotizantes as a,
                        ciiu_r3_grupos as b,
                        estratos_socioeconomicos as c,
                        tipo_estado_civil as d,
                        eps_tipos_aportantes as e,
                        eps_estamentos as f,
                        uv_dependencias as g

                        WHERE
                        a.eps_afiliacion_id = $eps_afiliacion_id
                        AND a.afiliado_tipo_id = '$afiliado_tipo_id'
                        AND a.afiliado_id = '$afiliado_id'
                        AND b.ciiu_r3_division = a.ciiu_r3_division
                        AND b.ciiu_r3_grupo = a.ciiu_r3_grupo
                        AND c.estrato_socioeconomico_id = a.estrato_socioeconomico_id
                        AND d.tipo_estado_civil_id = a.tipo_estado_civil_id
                        AND e.tipo_aportante_id = a.tipo_aportante_id
                        AND f.estamento_id = a.estamento_id
                        AND g.codigo_dependencia_id = a.codigo_dependencia_id
                    ) as datos
                    LEFT JOIN administradoras_de_fondos_de_pensiones as p
                        ON (p.codigo_afp = datos.codigo_afp)
                ";

                if(!$result = $this->ConexionBaseDatos($sql,true))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    return false;
                }

                $retorno['DATOS_COTIZANTE'] = $result->FetchRow();
                $result->Close();

                if($retorno['DATOS_COTIZANTE']['estamento_id']==='V')
                {
                    $sql = "
                            SELECT
                            a.convenio_tipo_id_tercero,
                            a.convenio_tercero_id,
                            a.fecha_inicio_convenio,
                            a.fecha_vencimiento_convenio,
                            b.nombre_tercero

                            FROM
                            eps_afiliados_cotizantes_convenios as a,
                            terceros as b

                            WHERE
                            a.eps_afiliacion_id = $eps_afiliacion_id
                            AND a.afiliado_tipo_id = '$afiliado_tipo_id'
                            AND a.afiliado_id = '$afiliado_id'
                            AND b.tipo_id_tercero = a.convenio_tipo_id_tercero
                            AND b.tercero_id = a.convenio_tercero_id

                    ";

                    if(!$result = $this->ConexionBaseDatos($sql,true))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        return false;
                    }

                    $retorno['DATOS_CONVENIO'] = $result->FetchRow();
                    $result->Close();

                }


            break;

            case 'B':

                $sql = "
                        SELECT
                        a.eps_afiliacion_id,
                        a.cotizante_tipo_id,
                        a.cotizante_id,
                        (b.primer_apellido || ' ' || b.segundo_apellido  || ' ' || b.primer_nombre  || ' ' || b.segundo_nombre ) as nombre_cotizante,
                        a.parentesco_id,
                        c.descripcion_parentesco

                        FROM
                        eps_afiliados_beneficiarios as a,
                        eps_afiliados_datos as b,
                        eps_parentescos_beneficiarios as c

                        WHERE
                        a.eps_afiliacion_id = $eps_afiliacion_id
                        AND a.afiliado_tipo_id = '$afiliado_tipo_id'
                        AND a.afiliado_id = '$afiliado_id'
                        AND b.afiliado_tipo_id = a.cotizante_tipo_id
                        AND b.afiliado_id = a.cotizante_id
                        AND c.parentesco_id = a.parentesco_id
                ";

                if(!$result = $this->ConexionBaseDatos($sql,true))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    return false;
                }

                $retorno['DATOS_BENEFICIARIO'] = $result->FetchRow();
                $result->Close();

                if($retorno['DATOS_BENEFICIARIO']['estamento_id']==='V')
                {
                    $sql = "
                            SELECT
                            a.convenio_tipo_id_tercero,
                            a.convenio_tercero_id,
                            a.fecha_inicio_convenio,
                            a.fecha_vencimiento_convenio,
                            b.nombre_tercero

                            FROM
                            eps_afiliados_cotizantes_convenios as a,
                            terceros as b

                            WHERE
                            a.eps_afiliacion_id = $eps_afiliacion_id
                            AND a.afiliado_tipo_id = '".$retorno['DATOS_BENEFICIARIO']['cotizante_tipo_id']."'
                            AND a.afiliado_id = '".$retorno['DATOS_BENEFICIARIO']['cotizante_id']."'
                            AND b.tipo_id_tercero = a.convenio_tipo_id_tercero
                            AND b.tercero_id = a.convenio_tercero_id

                    ";

                    if(!$result = $this->ConexionBaseDatos($sql,true))
                    {
                        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                        return false;
                    }

                    $retorno['DATOS_CONVENIO'] = $result->FetchRow();
                    $result->Close();

                }

            break;
        }

        return $retorno;
    }

}
?>