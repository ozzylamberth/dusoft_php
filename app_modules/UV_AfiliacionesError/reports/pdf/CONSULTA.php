<?php
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
                        k.afiliado_tipo_id as cotizante_conv_tipo_id,
                        k.afiliado_id as cotizante_conv_id
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
                             LEFT JOIN eps_afiliados_beneficiarios AS j
                                ON
                                (
                                    j.eps_afiliacion_id = a.eps_afiliacion_id
                                    AND j.afiliado_tipo_id = a.afiliado_tipo_id
                                    AND j.afiliado_id = a.afiliado_id
                                )
                             LEFT JOIN eps_afiliados_cotizantes_convenios AS k
                                ON
                                (
                                    j.eps_afiliacion_id = k.eps_afiliacion_id
                                    AND j.cotizante_tipo_id = k.afiliado_tipo_id
                                    AND j.cotizante_id = k.afiliado_id
                                    
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

?>    