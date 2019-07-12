<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultarAfiliados.class.php,v 1.5 2009/12/04 20:36:51 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
  */
  /**
  * Clase: ConsultarAfiliados
  * Clase para consulta de afiliados al sistema EPS
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.5 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
  */
  IncludeClass("Afiliaciones", "", "app","UV_Afiliaciones");
  class ConsultarAfiliados extends Afiliaciones
  {
    /**
    * Constructor de la clase
    */
    function ConsultarAfiliados(){}
    /**
    * Metodo que sirve para la consulta de datos para la elaboracion del carnet
    *
    *
    *
    **/
    function ConsultarDatosConvenio($tipo_afiliacion,$afiliacion_id,$tipo_id_afiliado,$afiliado_id)
    {
        $sql=" SELECT
                    
                    a.afiliado_tipo_id,
                    a.afiliado_id,
                    a.primer_apellido,
                    a.segundo_apellido,
                    a.primer_nombre,
                    a.segundo_nombre,
                    a.fecha_nacimiento,
                    w.eps_tipo_afiliado_id,
                    (
                        CASE w.eps_tipo_afiliado_id
                            WHEN 'C'
                                THEN
                                    (
                                        SELECT
                                            (b.convenio_tipo_id_tercero || '@' || b.convenio_tercero_id || '@' || c.nombre_tercero || '@' || to_char(b.fecha_vencimiento_convenio, 'YYYY-MM-DD')) AS DATOS
                                        FROM
                                            eps_afiliados_cotizantes_convenios as b,
                                            terceros as c
                                        WHERE
                                            b.afiliado_tipo_id ='".$tipo_id_afiliado."'
                                            AND b.afiliado_id ='".$afiliado_id."'
                                            AND b.eps_afiliacion_id = ".$afiliacion_id."
                                            AND b.convenio_tipo_id_tercero = c.tipo_id_tercero
                                            AND b.convenio_tercero_id = c.tercero_id
                                    )

                            WHEN 'B'
                                THEN
                                    (
                                        SELECT
                                            (b.convenio_tipo_id_tercero || '@' || b.convenio_tercero_id || '@' || c.nombre_tercero || '@' || to_char(b.fecha_vencimiento_convenio, 'YYYY-MM-DD')) AS DATOS
                                        FROM
                                            eps_afiliados_cotizantes_convenios as b,
                                            terceros as c,
                                            eps_afiliados_beneficiarios as t
                                        WHERE
                                            t.afiliado_tipo_id ='".$tipo_id_afiliado."'
                                            AND t.afiliado_id ='".$afiliado_id."'
                                            AND t.eps_afiliacion_id = ".$afiliacion_id."
                                            AND t.eps_afiliacion_id = b.eps_afiliacion_id
                                            AND t.cotizante_tipo_id = b.afiliado_tipo_id
                                            AND t.cotizante_id = b.afiliado_id
                                            AND b.convenio_tipo_id_tercero = c.tipo_id_tercero
                                            AND b.convenio_tercero_id = c.tercero_id
                                    )

                            ELSE 'EL USUARIO NO TIENE DATOS'
                            END) as convenio_datos
                FROM
                    eps_afiliados_datos AS a,
                    eps_afiliados AS w
                WHERE
                    w.afiliado_tipo_id ='".$tipo_id_afiliado."'
                    AND w.afiliado_id ='".$afiliado_id."'
                    AND w.eps_afiliacion_id = ".$afiliacion_id."
                    AND w.eps_tipo_afiliado_id = '".$tipo_afiliacion."'
                    AND a.afiliado_tipo_id = w.afiliado_tipo_id
                    AND a.afiliado_id = w.afiliado_id";

        if(!$result = $this->ConexionBaseDatos($sql,true))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        $retorno = array();

        while($fila = $result->FetchRow())
        {
            $retorno=$fila;
        }
        $result->Close();
        
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

            if($filtros['codigo_dependencia_id'])
            {
                $filtro .= " AND f.codigo_dependencia_id = '".$filtros['codigo_dependencia_id']."' ";
            }

            if($filtros['tipo_aportante_id'])
            {
                $filtro .= " AND f.tipo_aportante_id = '".$filtros['tipo_aportante_id']."' ";
            }
            if($filtros['eps_tipo_afiliado_id'])
            {
                $filtro .= " AND a.eps_tipo_afiliado_id = '".$filtros['eps_tipo_afiliado_id']."' ";
            }

            if($filtros['tipo_sexo_id'])
            {
                $filtro .= " AND b.tipo_sexo_id = '".$filtros['tipo_sexo_id']."' ";
            }

            if(is_numeric($filtros['edad_min']))
            {
                $filtro .= " AND b.fecha_nacimiento <= (now() - interval '".$filtros['edad_min']." years')::date";
            }

            if($filtros['edad'])
            {
              $filtro .= "AND      edad(b.fecha_nacimiento) "; 
              switch($filtros['edad_signo'])
              {
                case 1: $filtro .= " =  ".$filtros['edad']." "; break;
                case 2: $filtro .= " >  ".$filtros['edad']." "; break;
                case 3: $filtro .= " >= ".$filtros['edad']." "; break;
                case 4: $filtro .= " <  ".$filtros['edad']." "; break;
                case 5: $filtro .= " <= ".$filtros['edad']." "; break;
                case 6: $filtro .= " BETWEEN ".$filtros['edad']. " AND ".$filtros['edad_maxima']." "; break;
              }
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
        
        if($filtros['entidad_convenio'] != '-1' && $filtros['entidad_convenio'])
        {
          $ent = explode(" ",$filtros['entidad_convenio']);
          $filtro .= " AND k.convenio_tipo_id_tercero = '".$ent[0]."' ";
          $filtro .= " AND k.convenio_tercero_id <= '".$ent[1]."' ";
        }
        
        if($filtros['plan_atencion'] && $filtros['plan_atencion'] != '-1')
        {
          $filtro .= " AND a.plan_atencion = ".$filtros['plan_atencion']." ";
          if($filtros['tipo_afiliado_plan'])
            $filtro .= " AND  a.tipo_afiliado_atencion = '".$filtros['tipo_afiliado_plan']."' ";
            
          if($filtros['rango_afiliado_plan'])
            $filtro .= " AND  a.rango_afiliado_atencion  = '".$filtros['rango_afiliado_plan']."' ";
        }
        
        if($filtros['estado_afiliado_id'] != '0')
          $filtro .= "  AND  a.estado_afiliado_id = '".$filtros['estado_afiliado_id']."' ";
        
        if($filtros['subestado_afiliado_id'] != '0')        
          $filtro .= " AND  a.subestado_afiliado_id = '".$filtros['subestado_afiliado_id']."' ";
                
        if(is_numeric($limit) && is_numeric($offset))
        {
          $filtro_limit = " LIMIT $limit OFFSET $offset ";
        }
        else
        {
          $filtro_limit = "";
        }
        
        
        $select = " COUNT(*) as cantidad";
        
        if(empty($count))
        {
          $select  = " a.eps_afiliacion_id,";
          $select .= " a.afiliado_tipo_id,";
          $select .= " a.afiliado_id,";
          $select .= " (b.primer_apellido || ' ' || b.segundo_apellido  || ' ' || b.primer_nombre  || ' ' || b.segundo_nombre ) as nombre_afiliado,";
          $select .= " a.eps_tipo_afiliado_id,";
          $select .= " c.descripcion_eps_tipo_afiliado,";
          $select .= " a.fecha_afiliacion,";
          $select .= " a.estado_afiliado_id,";
          $select .= " a.subestado_afiliado_id,";
          $select .= " e.descripcion_estado,";
          $select .= " d.descripcion_subestado,";
          $select .= " f.estamento_siis AS estamento_id,";
          $select .= " f.descripcion_estamento,";
          $select .= " f.codigo_dependencia_id,";
          $select .= " f.descripcion_dependencia,";
          $select .= " f.tipo_aportante_id,";
          $select .= " f.descripcion_tipo_aportante,";
          $select .= " k.afiliado_tipo_id as cotizante_conv_tipo_id,";
          $select .= " k.afiliado_id as cotizante_conv_id, ";
          $select .= " PR.rango, ";
          $select .= " PL.plan_descripcion,";
          $select .= " TA.tipo_afiliado_nombre, ";
          $select .= " f.observaciones ";
        }

        $sql  = "SELECT $select ";
        $sql .= "FROM   eps_afiliados as a, ";        
        $sql .= "       ( SELECT    EB.eps_afiliacion_id, ";
        $sql .= "                   EB.afiliado_id, ";
        $sql .= "                   EB.afiliado_tipo_id, ";
        $sql .= "                   EE.descripcion_estamento, ";
        $sql .= "                   EE.estamento_siis, ";
        $sql .= "                   '' AS codigo_dependencia_id, ";
        $sql .= "                   '' AS descripcion_dependencia, ";
        $sql .= "                   '' AS tipo_aportante_id, ";
        $sql .= "                   '' AS descripcion_tipo_aportante, ";
        $sql .= "                   EB.observaciones ";
        $sql .= "           FROM    eps_afiliados_beneficiarios EB,"; 
        $sql .= "                   eps_afiliados_cotizantes EC, " ;
        $sql .= "                   eps_estamentos EE " ;
        $sql .= "           WHERE   EB.cotizante_tipo_id = EC.afiliado_tipo_id";
        $sql .= "           AND     EB.cotizante_id = EC.afiliado_id ";
        $sql .= "           AND     EB.eps_afiliacion_id = EC.eps_afiliacion_id ";
        $sql .= "           AND     EC.estamento_id = EE.estamento_id ";
        if($filtros['estamento_id'])
          $sql .= "           AND EC.estamento_id = '".$filtros['estamento_id']."' ";
        
         $sql .= "           UNION ALL ";
        $sql .= "           SELECT  EC.eps_afiliacion_id, ";
        $sql .= "                   EC.afiliado_id, ";
        $sql .= "                   EC.afiliado_tipo_id,";
        $sql .= "                   EE.descripcion_estamento, ";
        $sql .= "                   EE.estamento_siis, ";
        $sql .= "                   UD.codigo_dependencia_id, ";
        $sql .= "                   UD.descripcion_dependencia, ";
        $sql .= "                   TA.tipo_aportante_id, ";
        $sql .= "                   TA.descripcion_tipo_aportante, ";
        $sql .= "                   '' AS observaciones ";
        $sql .= "           FROM    eps_afiliados_cotizantes EC, " ;
        $sql .= "                   eps_estamentos EE, " ;
        $sql .= "                   uv_dependencias UD," ;
        $sql .= "                   eps_tipos_aportantes AS TA " ;
        $sql .= "           WHERE   EC.estamento_id = EE.estamento_id  ";
        $sql .= "           AND     UD.codigo_dependencia_id = EC.codigo_dependencia_id " ;
        if($filtros['estamento_id'])
           $sql .= "           AND EC.estamento_id = '".$filtros['estamento_id']."' ";
        $sql .= "           AND     TA.tipo_aportante_id = EC.tipo_aportante_id) AS f " ;
        $sql .= "       LEFT JOIN eps_afiliados_cotizantes_convenios AS k  " ;
        $sql .= "       ON(  f.eps_afiliacion_id = k.eps_afiliacion_id  AND " ;
        $sql .= "            f.afiliado_tipo_id = k.afiliado_tipo_id AND ";
        $sql .= "            f.afiliado_id = k.afiliado_id ), ";
        $sql .= "       eps_afiliados_datos as b, ";
        //$sql .= "       LEFT JOIN interfaz_uv.funcionarios_univalle AS IU  ";
        //$sql .= "       ON( b.afiliado_tipo_id = IU.funcionario_tipo_id AND ";
				//$sql .= "	          b.afiliado_id = IU.funcionario_id ), ";
        $sql .= "	      eps_tipos_afiliados as c, ";
        $sql .= "	      eps_afiliados_subestados as d, ";
        $sql .= "	      eps_afiliados_estados as e, ";
        $sql .= "       planes PL, ";
        $sql .= "       planes_rangos PR, ";
        $sql .= "       tipos_afiliado TA ";
        $sql .= "WHERE  b.afiliado_tipo_id = a.afiliado_tipo_id ";
        $sql .= "AND    b.afiliado_id = a.afiliado_id ";
        $sql .= "AND    c.eps_tipo_afiliado_id = a.eps_tipo_afiliado_id ";
        $sql .= "AND    d.estado_afiliado_id = a.estado_afiliado_id ";
        $sql .= "AND    d.subestado_afiliado_id = a.subestado_afiliado_id ";
        $sql .= "AND    e.estado_afiliado_id = d.estado_afiliado_id ";
        $sql .= "AND    f.eps_afiliacion_id = a.eps_afiliacion_id ";
        $sql .= "AND    f.afiliado_tipo_id = a.afiliado_tipo_id ";
        $sql .= "AND    f.afiliado_id = a.afiliado_id ";
        $sql .= "AND    a.plan_atencion = PR.plan_id ";
        $sql .= "AND    a.tipo_afiliado_atencion = PR.tipo_afiliado_id ";
        $sql .= "AND    a.rango_afiliado_atencion = PR.rango ";
        $sql .= "AND    TA.tipo_afiliado_id = PR.tipo_afiliado_id ";
        $sql .= "AND    PR.plan_id = PL.plan_id ";
        $sql .= $filtro." ";
        /*if($filtros['copago'] == '1')
          $sql .= "AND    IU.cobrar_copagos = 't' ";
          
        if($filtros['copago'] == '2')
          $sql .= "AND    IU.cobrar_copagos = 'f' ";*/
          
        $sql .= $filtro_limit;

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
      $sql  = "SELECT datos.*,";
      $sql .= "       g.descripcion_ciuo_88_grupo_primario,";
      $sql .= "       c.razon_social_eps as razon_social_eps_anterior ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  a.eps_afiliacion_id,";
      $sql .= "                 a.afiliado_tipo_id,";
      $sql .= "                 a.afiliado_id,";
      $sql .= "                 a.eps_tipo_afiliado_id,";
      $sql .= "                 l.descripcion_eps_tipo_afiliado,";
      $sql .= "                 a.fecha_afiliacion,";
      $sql .= "                 a.eps_anterior,";
      $sql .= "                 a.fecha_afiliacion_eps_anterior,";
      $sql .= "                 a.semanas_cotizadas_eps_anterior,";
      $sql .= "                 a.semanas_cotizadas,";
      $sql .= "                 a.estado_afiliado_id,";
      $sql .= "                 d.descripcion_estado,";
      $sql .= "                 a.subestado_afiliado_id,";
      $sql .= "                 e.descripcion_subestado,";
      $sql .= "                 a.observaciones,";
      $sql .= "                 b.primer_apellido,";
      $sql .= "                 b.segundo_apellido,";
      $sql .= "                 b.primer_nombre,";
      $sql .= "                 b.segundo_nombre,";
      $sql .= "                 (b.primer_apellido || ' ' || b.segundo_apellido  || ' ' || b.primer_nombre  || ' ' || b.segundo_nombre ) as nombre_afiliado,";
      $sql .= "                 b.fecha_nacimiento,";
      $sql .= "                 b.fecha_afiliacion_sgss,";
      $sql .= "                 b.tipo_sexo_id,";
      $sql .= "                 f.descripcion_eps_tipo_sexo_id,";
      $sql .= "                 b.ciuo_88_grupo_primario,";
      $sql .= "                 b.tipo_pais_id,";
      $sql .= "                 b.tipo_dpto_id,";
      $sql .= "                 b.tipo_mpio_id,";
      $sql .= "                 h.pais,";
      $sql .= "                 i.departamento,";
      $sql .= "                 j.municipio,";
      $sql .= "                 b.zona_residencia,";
      $sql .= "                 k.descripcion as descripcion_zona_residencia,";
      $sql .= "                 b.direccion_residencia,";
      $sql .= "                 b.telefono_residencia,";
      $sql .= "                 b.telefono_movil, ";
      $sql .= "                 PR.rango, ";
      $sql .= "                 PL.plan_descripcion,";
      $sql .= "                 TA.tipo_afiliado_nombre, ";
   	  $sql .= " 				        PA.eps_punto_atencion_nombre ";
      $sql .= "         FROM    eps_afiliados as a,";
      $sql .= "                 eps_afiliados_datos as b,";
      $sql .= "                 eps_afiliados_estados as d,";
      $sql .= "                 eps_afiliados_subestados as e,";
      $sql .= "                 eps_tipos_sexo as f,";
      $sql .= "                 tipo_pais as h,";
      $sql .= "                 tipo_dptos as i,";
      $sql .= "                 tipo_mpios as j,";
      $sql .= "                 zonas_residencia as k,";
      $sql .= "                 eps_tipos_afiliados as l, ";
      $sql .= "                 planes PL, ";
      $sql .= "                 planes_rangos PR, ";
      $sql .= "                 tipos_afiliado TA, ";
      $sql .= "       			    eps_puntos_atencion PA ";
      $sql .= "         WHERE   a.eps_afiliacion_id = $eps_afiliacion_id ";
      $sql .= "         AND     a.afiliado_tipo_id = '$afiliado_tipo_id' ";
      $sql .= "         AND     a.afiliado_id = '$afiliado_id' ";
      $sql .= "         AND     b.afiliado_tipo_id = a.afiliado_tipo_id ";
      $sql .= "         AND     b.afiliado_id = a.afiliado_id ";
      $sql .= "         AND     d.estado_afiliado_id = a.estado_afiliado_id ";
      $sql .= "         AND     e.estado_afiliado_id = a.estado_afiliado_id ";
      $sql .= "         AND     e.subestado_afiliado_id = a.subestado_afiliado_id ";
      $sql .= "         AND     f.tipo_sexo_id = b.tipo_sexo_id ";
      $sql .= "         AND     h.tipo_pais_id = b.tipo_pais_id ";
      $sql .= "         AND     i.tipo_pais_id = b.tipo_pais_id ";
      $sql .= "         AND     i.tipo_dpto_id = b.tipo_dpto_id ";
      $sql .= "         AND     j.tipo_pais_id = b.tipo_pais_id ";
      $sql .= "         AND     j.tipo_dpto_id = b.tipo_dpto_id ";
      $sql .= "         AND     j.tipo_mpio_id = b.tipo_mpio_id ";
      $sql .= "         AND     k.zona_residencia = b.zona_residencia ";
      $sql .= "         AND     l.eps_tipo_afiliado_id = a.eps_tipo_afiliado_id ";
      $sql .= "         AND     a.plan_atencion = PR.plan_id ";
      $sql .= "         AND     a.tipo_afiliado_atencion = PR.tipo_afiliado_id ";
      $sql .= "         AND     a.rango_afiliado_atencion = PR.rango ";
      $sql .= "         AND     TA.tipo_afiliado_id = PR.tipo_afiliado_id ";
      $sql .= "         AND     PR.plan_id = PL.plan_id ";
      $sql .= "			    AND    	PA.eps_punto_atencion_id = a.eps_punto_atencion_id ";
      $sql .= "       ) as datos ";
      $sql .= "       LEFT JOIN ciuo_88_grupos_primarios as g  ";
      $sql .= "       ON (g.ciuo_88_grupo_primario = datos.ciuo_88_grupo_primario) ";
      $sql .= "       LEFT JOIN entidades_promotoras_de_salud as c  ";
      $sql .= "       ON (c.codigo_sgss_eps = datos.eps_anterior) ";

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
                        f.estamento_siis AS estamento_id,
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
                        ON (p.codigo_afp = datos.codigo_afp)  ";

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
                        c.descripcion_parentesco,
                        f.estamento_siis AS estamento_id,
                        f.descripcion_estamento,
                        a.observaciones
                        FROM
                        eps_afiliados_beneficiarios as a,
                        eps_afiliados_datos as b,
                        eps_parentescos_beneficiarios as c,
                        eps_estamentos as f,
                        eps_afiliados_cotizantes as x
                        WHERE
                        a.eps_afiliacion_id = $eps_afiliacion_id
                        AND a.afiliado_tipo_id = '$afiliado_tipo_id'
                        AND a.afiliado_id = '$afiliado_id'
                        AND b.afiliado_tipo_id = a.cotizante_tipo_id
                        AND b.afiliado_id = a.cotizante_id
                        AND x.afiliado_tipo_id = a.cotizante_tipo_id
                        AND x.afiliado_id = a.cotizante_id
                        AND x.estamento_id = f.estamento_id
                        AND x.eps_afiliacion_id = a.eps_afiliacion_id
                        AND c.parentesco_id = a.parentesco_id  ";

                if(!$result = $this->ConexionBaseDatos($sql,true))
                {
                    $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                    return false;
                }

                $retorno['DATOS_BENEFICIARIO'] = $result->FetchRow();
                $result->Close();

                if($retorno['DATOS_BENEFICIARIO']['estamento_id']==='V')
                {
                    $sql = "SELECT  a.convenio_tipo_id_tercero,
                                    a.convenio_tercero_id,
                                    a.fecha_inicio_convenio,
                                    a.fecha_vencimiento_convenio,
                                    b.nombre_tercero
                            FROM    eps_afiliados_cotizantes_convenios as a,
                                    terceros as b
                            WHERE   a.eps_afiliacion_id = $eps_afiliacion_id
                            AND     a.afiliado_tipo_id = '".$afiliado_tipo_id."'
                            AND     a.afiliado_id = '".$afiliado_id."'
                            AND     b.tipo_id_tercero = a.convenio_tipo_id_tercero
                            AND     b.tercero_id = a.convenio_tercero_id ";

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
		/**
		* Funcion encargada de obtener las ciudades relacionadas a un departamento
    *
    * @param string $deptno Identificador del departamento
    *
    * @return array
		*/
		function ObtenerCiudades($deptno)
		{
			$sql  = "SELECT	tipo_mpio_id,";
			$sql .= "				municipio ";
			$sql .= "FROM		tipo_mpios ";
			$sql .= "WHERE	tipo_dpto_id = '".$deptno."' ";				
			$sql .= "ORDER BY municipio ";
			
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
    * Funcion donde se obtienen los datos de los cotizantes
    *
    * @param array $filtros
    *
    * @return array
    */
    function ObtenerDatosAfiliadosCotizante($filtros)
    { 
      $ctl = AutoCarga::factory("ClaseUtil");
      
      $sql  = "SELECT f.eps_afiliacion_id, ";
      $sql .= "       f.afiliado_tipo_id, ";
      $sql .= "       f.afiliado_id, ";
      $sql .= "       TO_CHAR(f.fecha_afiliacion,'DD/MM/YYYY') AS fecha_afiliacion, ";
      $sql .= "       TO_CHAR(f.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
      $sql .= "       f.estamento_siis, ";
      $sql .= "       f.descripcion_estamento, ";
      $sql .= "       f.descripcion_dependencia, ";
      $sql .= "       f.descripcion_tipo_aportante, ";
      $sql .= "       f.apellidos  || ' ' || f.nombres AS nombre_afiliado, ";
      $sql .= "       f.direccion_residencia   , ";
      $sql .= "       f.telefono_residencia    , ";
      $sql .= "       f.telefono_movil,  ";
      $sql .= "       f.lugar,  ";
      $sql .= "       c.descripcion_eps_tipo_afiliado, ";
      $sql .= "       k.nombre_tercero, ";
      $sql .= "       e.descripcion_estado, ";
      $sql .= "       d.descripcion_subestado ";
      $sql .= " FROM  ";
      $sql .= "       ( SELECT  EE.descripcion_estamento, ";
      $sql .= "                 EE.estamento_siis, ";
      $sql .= "                 UD.codigo_dependencia_id, ";
      $sql .= "                 UD.descripcion_dependencia, ";
      $sql .= "                 TA.tipo_aportante_id, ";
      $sql .= "                 TA.descripcion_tipo_aportante, ";
      $sql .= "                 EA.eps_afiliacion_id, ";
      $sql .= "                 EA.afiliado_tipo_id, ";
      $sql .= "                 EA.afiliado_id, ";
      $sql .= "                 EA.eps_tipo_afiliado_id, ";
      $sql .= "                 EA.fecha_afiliacion, ";
      $sql .= "                 EA.estado_afiliado_id, ";
      $sql .= "                 EA.subestado_afiliado_id, ";
      $sql .= "                 edad(ED.fecha_nacimiento) AS edad, ";
      $sql .= "                 ED.fecha_nacimiento, ";
      $sql .= "                 ED.primer_apellido || ' ' || ED.segundo_apellido  AS apellidos, ";
      $sql .= "                 ED.primer_nombre  || ' ' || ED.segundo_nombre AS nombres  ,";
      $sql .= "                 ED.direccion_residencia   , ";
      $sql .= "                 ED.telefono_residencia    , ";
      $sql .= "                 ED.telefono_movil,  ";
      $sql .= "                 TM.municipio ||'-'||TD.departamento AS lugar ";

      $sql .= "         FROM    eps_afiliados_cotizantes EC, " ;
      $sql .= "                 eps_estamentos EE, " ;
      $sql .= "                 uv_dependencias UD," ;
      $sql .= "                 eps_tipos_aportantes AS TA, " ;
      $sql .= "                 eps_afiliados_datos ED, " ;
      //$sql .= "                 LEFT JOIN interfaz_uv.funcionarios_univalle AS IU  ";
      //$sql .= "                 ON( ED.afiliado_tipo_id = IU.funcionario_tipo_id AND ";
			//$sql .= "	                    ED.afiliado_id = IU.funcionario_id ), ";
      $sql .= "                 eps_afiliados EA ," ;
      $sql .= "                 tipo_pais TP,";
      $sql .= "                 tipo_dptos TD,";
      $sql .= "                 tipo_mpios TM ";
      $sql .= "         WHERE   EC.estamento_id = EE.estamento_id  ";
      $sql .= "         AND     UD.codigo_dependencia_id = EC.codigo_dependencia_id " ;
      $sql .= "         AND     ED.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $sql .= "         AND     ED.afiliado_id = EA.afiliado_id  ";
      $sql .= "         AND     EC.eps_afiliacion_id = EA.eps_afiliacion_id ";
      $sql .= "         AND     EC.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $sql .= "         AND     EC.afiliado_id = EA.afiliado_id ";
      $sql .= "         AND     TA.tipo_aportante_id = EC.tipo_aportante_id";
      $sql .= "         AND     ED.tipo_pais_id = TP.tipo_pais_id ";
      $sql .= "         AND     ED.tipo_dpto_id = TD.tipo_dpto_id ";
      $sql .= "         AND     ED.tipo_mpio_id = TM.tipo_mpio_id ";
      $sql .= "         AND     TD.tipo_pais_id = TP.tipo_pais_id ";
      $sql .= "         AND     TM.tipo_pais_id = TD.tipo_pais_id ";
      $sql .= "         AND     TM.tipo_dpto_id = TD.tipo_dpto_id ";  
      
      if($filtros['estamento_id'])
        $sql .= "         AND   EC.estamento_id = '".$filtros['estamento_id']."' ";
      
      if($filtros['afiliado_tipo_id'] && $filtros['afiliado_id'])
      {
        $sql .= "         AND   EC.afiliado_tipo_id = '".$filtros['afiliado_tipo_id']."' ";
        $sql .= "         AND   EC.afiliado_id = '".$filtros['afiliado_id']."' ";
      }
     
      if($filtros['primer_apellido'] || $filtros['segundo_apellido'] || $filtros['nombre_afiliado'])
        $sql .= "         AND  ".$ctl->FiltrarNombres($filtros['nombre_afiliado'],$filtros['primer_apellido']." ".$filtros['segundo_apellido'],"ED");

      if($filtros['estado_afiliado_id'])
        $sql .= "         AND   EA.estado_afiliado_id = '".$filtros['estado_afiliado_id']."' ";

      if($filtros['subestado_afiliado_id'])
        $sql .= "         AND   EA.subestado_afiliado_id = '".$filtros['subestado_afiliado_id']."' ";

      if($filtros['codigo_dependencia_id'])
        $sql .= "         AND   UD.codigo_dependencia_id = '".$filtros['codigo_dependencia_id']."' ";
      
      if($filtros['tipo_aportante_id'])
        $sql .= "         AND   TA.tipo_aportante_id = '".$filtros['tipo_aportante_id']."' ";

      if($filtros['eps_tipo_afiliado_id'])
        $sql .= "         AND   EA.eps_tipo_afiliado_id = '".$filtros['eps_tipo_afiliado_id']."' ";
      
      if($filtros['tipo_sexo_id'])
        $sql .= "         AND   ED.tipo_sexo_id = '".$filtros['tipo_sexo_id']."' ";

      if(($filtros['fecha1']))
        $sql .= "         AND   EA.fecha_afiliacion >= '".$filtros['fecha1']."' ";
      
      if(($filtros['fecha2']))
        $sql .= "         AND   EA.fecha_afiliacion <= '".$filtros['fecha2']."' ";
      
      /*if($filtros['copago'] == '1')
          $sql .= "AND    IU.cobrar_copagos = 't' ";
      else if($filtros['copago'] == '2')
          $sql .= "AND    IU.cobrar_copagos = 'f' ";*/
      
      $sql .= "       ) AS f " ;      
      $sql .= "       LEFT JOIN ";
      $sql .= "       (SELECT CC.eps_afiliacion_id, ";
      $sql .= "               CC.afiliado_tipo_id, ";
      $sql .= "               CC.afiliado_id, ";
      $sql .= "               CC.convenio_tipo_id_tercero, ";
      $sql .= "               CC.convenio_tercero_id, ";
      $sql .= "               TE.nombre_tercero ";
      $sql .= "        FROM   eps_afiliados_cotizantes_convenios CC, ";
      $sql .= "               terceros_uv_convenios TC, ";
      $sql .= "               terceros TE ";
      $sql .= "       WHERE  CC.convenio_tipo_id_tercero = TC.tipo_id_tercero ";
      $sql .= "       AND    CC.convenio_tercero_id    = TC.tercero_id ";
      $sql .= "       AND    TC.tipo_id_tercero = TE.tipo_id_tercero ";
      $sql .= "       AND    TC.tercero_id = TE.tercero_id) AS k ";
      $sql .= "       ON( f.eps_afiliacion_id = k.eps_afiliacion_id AND ";
      $sql .= "           f.afiliado_tipo_id = k.afiliado_tipo_id AND "; 
      $sql .= "           f.afiliado_id = k.afiliado_id ";
      $sql .= "        ),";
      $sql .= "        eps_tipos_afiliados as c, ";
      $sql .= "        eps_afiliados_subestados as d, ";
      $sql .= "        eps_afiliados_estados as e ";
      $sql .= "WHERE   c.eps_tipo_afiliado_id = f.eps_tipo_afiliado_id  ";
      $sql .= "AND     d.estado_afiliado_id = f.estado_afiliado_id  ";
      $sql .= "AND     d.subestado_afiliado_id = f.subestado_afiliado_id  ";
      $sql .= "AND     e.estado_afiliado_id = d.estado_afiliado_id ";
      
      if($filtros['edad'])
      {
        $sql .= "AND    f.edad "; 
        switch($filtros['edad_signo'])
        {
          case 1: $sql .= " =  ".$filtros['edad']." "; break;
          case 2: $sql .= " >  ".$filtros['edad']." "; break;
          case 3: $sql .= " >= ".$filtros['edad']." "; break;
          case 4: $sql .= " <  ".$filtros['edad']." "; break;
          case 5: $sql .= " <= ".$filtros['edad']." "; break;
          case 6: $sql .= " BETWEEN ".$filtros['edad']. " AND ".$filtros['edad_maxima']." "; break;
        }
      }
      if($filtros['entidad_convenio'] != '-1' && $filtros['entidad_convenio'])
      {
        $ent = explode(" ",$filtros['entidad_convenio']);
        $sql .= " AND k.convenio_tipo_id_tercero = '".$ent[0]."' ";
        $sql .= " AND k.convenio_tercero_id <= '".$ent[1]."' ";
      }
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $cotizantes = array();
      
      while (!$rst->EOF)
      {
        $cotizantes[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      if(!empty($cotizantes))
      {
        $sql  = "SELECT f.cotizante_tipo_id, ";
        $sql .= "       f.cotizante_id, ";
        $sql .= "       a.eps_afiliacion_id, ";
        $sql .= "       a.afiliado_tipo_id, ";
        $sql .= "       a.afiliado_id, ";
        $sql .= "       (b.primer_apellido || ' ' || b.segundo_apellido  || ' ' || b.primer_nombre  || ' ' || b.segundo_nombre ) as nombre_afiliado, ";
        $sql .= "       c.descripcion_eps_tipo_afiliado, ";
        $sql .= "       a.fecha_afiliacion, ";
        $sql .= "       e.descripcion_estado, ";
        $sql .= "       d.descripcion_subestado, ";
        $sql .= "       f.descripcion_parentesco, ";
        $sql .= "       f.tipo_aportante_id, ";
        $sql .= "       TO_CHAR(b.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento   , ";
        $sql .= "       b.direccion_residencia   , ";
        $sql .= "       b.telefono_residencia    , ";
        $sql .= "       b.telefono_movil,  ";
        $sql .= "       f.lugar  ";
        $sql .= " FROM  eps_afiliados as a, ";
        $sql .= "       ( SELECT  EB.eps_afiliacion_id, ";
        $sql .= "                 EB.afiliado_id, ";
        $sql .= "                 EB.afiliado_tipo_id, ";
        $sql .= "                 EE.descripcion_estamento, ";
        $sql .= "                 EE.estamento_siis, ";
        $sql .= "                 EB.cotizante_tipo_id, ";
        $sql .= "                 EB.cotizante_id, ";
        $sql .= "                 EP.descripcion_parentesco, ";
        $sql .= "                 UD.codigo_dependencia_id, ";
        $sql .= "                 TM.municipio ||'-'||TD.departamento AS lugar, ";

        $sql .= "                 TA.tipo_aportante_id ";
        $sql .= "         FROM    eps_afiliados_beneficiarios EB,"; 
        $sql .= "                 eps_afiliados_cotizantes EC, " ;
        $sql .= "                 uv_dependencias UD," ;
        $sql .= "                 eps_tipos_aportantes AS TA, " ;
        $sql .= "                 eps_afiliados_datos ED, " ;
        $sql .= "                 eps_afiliados EA, " ;
        $sql .= "                 eps_parentescos_beneficiarios EP, " ;
        $sql .= "                 tipo_pais TP,";
        $sql .= "                 tipo_dptos TD,";
        $sql .= "                 tipo_mpios TM ,";

        $sql .= "                 eps_estamentos EE " ;
        $sql .= "         WHERE   EB.cotizante_tipo_id = EC.afiliado_tipo_id";
        $sql .= "         AND     EB.cotizante_id = EC.afiliado_id ";
        $sql .= "         AND     EB.eps_afiliacion_id = EC.eps_afiliacion_id ";
        $sql .= "         AND     EB.parentesco_id = EP.parentesco_id ";
        $sql .= "         AND     EC.estamento_id = EE.estamento_id ";
        $sql .= "         AND     UD.codigo_dependencia_id = EC.codigo_dependencia_id " ;
        $sql .= "         AND     TA.tipo_aportante_id = EC.tipo_aportante_id";
        $sql .= "         AND     ED.afiliado_tipo_id = EA.afiliado_tipo_id ";
        $sql .= "         AND     ED.afiliado_id = EA.afiliado_id  ";
        $sql .= "         AND     EC.eps_afiliacion_id = EA.eps_afiliacion_id ";
        $sql .= "         AND     EC.afiliado_tipo_id = EA.afiliado_tipo_id ";
        $sql .= "         AND     EC.afiliado_id = EA.afiliado_id ";
        $sql .= "         AND     ED.tipo_pais_id = TP.tipo_pais_id ";
        $sql .= "         AND     ED.tipo_dpto_id = TD.tipo_dpto_id ";
        $sql .= "         AND     ED.tipo_mpio_id = TM.tipo_mpio_id ";
        $sql .= "         AND     TD.tipo_pais_id = TP.tipo_pais_id ";
        $sql .= "         AND     TM.tipo_pais_id = TD.tipo_pais_id ";
        $sql .= "         AND     TM.tipo_dpto_id = TD.tipo_dpto_id ";  
        

        if($filtros['estamento_id'])
          $sql .= "         AND EC.estamento_id = '".$filtros['estamento_id']."' ";
              
        if($filtros['afiliado_tipo_id'] && $filtros['afiliado_id'])
        {
          $sql .= "         AND   EC.afiliado_tipo_id = '".$filtros['afiliado_tipo_id']."' ";
          $sql .= "         AND   EC.afiliado_id = '".$filtros['afiliado_id']."' ";
        }
        
        if($filtros['primer_apellido'] || $filtros['segundo_apellido'] || $filtros['nombre_afiliado'])
          $sql .= "         AND  ".$ctl->FiltrarNombres($filtros['nombre_afiliado'],$filtros['primer_apellido']." ".$filtros['segundo_apellido'],"ED");

        if($filtros['estado_afiliado_id'])
          $sql .= "         AND   EA.estado_afiliado_id = '".$filtros['estado_afiliado_id']."' ";

        if($filtros['subestado_afiliado_id'])
          $sql .= "         AND   EA.subestado_afiliado_id = '".$filtros['subestado_afiliado_id']."' ";

        if($filtros['codigo_dependencia_id'])
          $sql .= "         AND   UD.codigo_dependencia_id = '".$filtros['codigo_dependencia_id']."' ";
        
        if($filtros['tipo_aportante_id'])
          $sql .= "         AND   TA.tipo_aportante_id = '".$filtros['tipo_aportante_id']."' ";
        
        $sql .= "           AND     TA.tipo_aportante_id = EC.tipo_aportante_id";
        $sql .= "       ) AS f " ;
        $sql .= "       LEFT JOIN eps_afiliados_cotizantes_convenios AS k ";
        $sql .= "       ON( f.eps_afiliacion_id = k.eps_afiliacion_id AND ";
        $sql .= "           f.afiliado_tipo_id = k.afiliado_tipo_id AND "; 
        $sql .= "           f.afiliado_id = k.afiliado_id ";
        $sql .= "        ),";
        $sql .= "        eps_afiliados_datos as b, ";
        $sql .= "        eps_tipos_afiliados as c, ";
        $sql .= "        eps_afiliados_subestados as d, ";
        $sql .= "        eps_afiliados_estados as e ";
        $sql .= "WHERE   b.afiliado_tipo_id = a.afiliado_tipo_id ";
        $sql .= "AND     b.afiliado_id = a.afiliado_id  ";
        $sql .= "AND     c.eps_tipo_afiliado_id = a.eps_tipo_afiliado_id  ";
        $sql .= "AND     d.estado_afiliado_id = a.estado_afiliado_id  ";
        $sql .= "AND     d.subestado_afiliado_id = a.subestado_afiliado_id  ";
        $sql .= "AND     e.estado_afiliado_id = d.estado_afiliado_id ";
        $sql .= "AND     f.eps_afiliacion_id = a.eps_afiliacion_id ";
        $sql .= "AND     f.afiliado_tipo_id = a.afiliado_tipo_id ";
        $sql .= "AND     f.afiliado_id = a.afiliado_id ";
        
        if($filtros['entidad_convenio'] != '-1' && $filtros['entidad_convenio'])
        {
          $ent = explode(" ",$filtros['entidad_convenio']);
          $sql .= " AND k.convenio_tipo_id_tercero = '".$ent[0]."' ";
          $sql .= " AND k.convenio_tercero_id <= '".$ent[1]."' ";
        }
        
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $beneficiarios = array();
        
        while (!$rst->EOF)
        {
          $beneficiarios[$rst->fields[0]][$rst->fields[1]][] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
      }
      
      $datos['cotizantes'] = $cotizantes;
      $datos['beneficiarios'] = $beneficiarios;
      
      return $datos;
    }
  }
?>