<?php
/** 
    * $Id: hc_InformacionInicialPaciente_DatosInformacion.class.php,v 1.3 2008/11/18 16:31:48 hugo Exp $
    * 
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @package IPSOFT-SIIS
    * 
    * $Revision: 1.3 $ 
    * 
    * @autor J gomez
    */

class DatosInformacion
{

/**
* Esta funcion Inicializa las variable de la clase
* @access public
* @return boolean Para identificar que se realizo.
*/
    function DatosInformacion($objeto=null)
	{
     	$this->obj=$objeto;
          return true;
	}

/**
* Esta funci� retorna los datos de concernientes a la version del submodulo
* @access private
*/

	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'10/25/2006',
		'autor'=>'JAIME ANDRES GOMEZ',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}

/**
* Esta funcion verifica si este submodulo fue utilizado para la atencion de un paciente.
* @access private
* @return text Datos HTML de la pantalla.
*/

	function ConsultaSignos()
	{  
          $obj=$this->obj;
          list($dbconn) = GetDBconn();
               
          $query1 = "select * 
          from hc_tipos_sistemas 
          where   sw_defecto='1'
          order by tipo_sistema_id";
          
          $result = $dbconn->Execute($query1);
          if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
          else
          {
               while(!$result->EOF)
               {
                    $vector[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
		return $vector;
	}


    /**
    *
    *
    *
    **/
    function ObtenerAlertas($evolucion_id)
    {
        list($dbconn) = GetDBconn();
            
        $query1 ="  SELECT a.hc_tipo_alerta_id,
                           a.descripcion,
                           b.observacion
                    FROM   hc_tipos_alertas as a,
                           hc_alertas as b
                    WHERE evolucion_id=".$evolucion_id."
                    AND a.hc_tipo_alerta_id=b.hc_tipo_alerta_id
                    ORDER BY 1";
          
          $result = $dbconn->Execute($query1);
          if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
          else
          {
               while(!$result->EOF)
               {
                    $vector[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
        return $vector;


    }


     function HistoriaClinicaCompleta($paciente_id,$tipo_id_paciente)
     {
          list($dbconn) = GetDBconn();
          $sql="SELECT A.ingreso, A.comentario, TO_CHAR(A.fecha_ingreso,'YYYY-MM-DD') AS fecha_ingreso,
                    B.evolucion_id, TO_CHAR(B.fecha,'YYYY-MM-DD') AS fecha_evolucion,
                    C.descripcion AS motivo_consulta, C.enfermedadactual AS enfermedad_actual,
                    D.diagnostico_nombre,
                    D.diagnostico_id,
                    F.nombre AS nombre_medico, F.descripcion AS descipcion_medico,
                    G.descripcion AS dpto,
                    H.via_ingreso_nombre,
                    I.triage_id,
                    C.evolucion_id AS evo_motivo,
                    E.evolucion_id AS evo_diag

               FROM ingresos AS A
               JOIN vias_ingreso AS H ON(A.via_ingreso_id=H.via_ingreso_id)
               JOIN hc_evoluciones AS B ON (A.ingreso=B.ingreso and B.estado!=1)
               JOIN system_usuarios AS F ON(B.usuario_id=F.usuario_id)
               JOIN departamentos AS G ON(B.departamento=G.departamento)
               LEFT JOIN hc_motivo_consulta AS C ON(B.ingreso = C.ingreso AND B.evolucion_id = C.evolucion_id)
               LEFT JOIN hc_diagnosticos_ingreso AS E ON (B.evolucion_id=E.evolucion_id)
               LEFT JOIN diagnosticos AS D ON(E.tipo_diagnostico_id=D.diagnostico_id)
               LEFT JOIN triages AS I ON(I.ingreso = A.ingreso)

               WHERE A.paciente_id='".$paciente_id."'
               AND A.tipo_id_paciente='".$tipo_id_paciente."'
               ORDER BY B.fecha DESC, B.evolucion_id ASC LIMIT 5 OFFSET 0;";

          $result = $dbconn->Execute($sql);
          if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
          else
          {
               while(!$result->EOF)
               {
                    $historia[$result->fields[0]][$result->fields[3]][] = $result->GetRowAssoc($ToUpper = false);//[$result->fields[3]]
                    $result->MoveNext();
               }
          }
            
          //$salida_submodulo = $this->HistoriaClinicaPaciente(&$historia, $this->datosEvolucion['evolucion_id'], $this->hc_modulo);
          return $historia;
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



        if($filtros['afiliado_tipo_id'] && $filtros['afiliado_id'])
        {
            $filtro .= " AND a.afiliado_tipo_id = '".$filtros['afiliado_tipo_id']."' ";
            $filtro .= " AND a.afiliado_id = '".$filtros['afiliado_id']."' ";
        }

//        $filtro .= " AND a.eps_tipo_afiliado_id = 'C'";

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
                        $filtro;
        ";

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



    function ObtenerBeneficiariosCotizante($datos)
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
      $sql .= "       AF.eps_afiliacion_id  , ";
      $sql .= "       PB.descripcion_parentesco , ";
      $sql .= "       TP.pais ||'-'||TD.departamento||'-'||TM.municipio AS departamento_municipio ";
      $sql .= "FROM   eps_afiliados_datos AD,";
      $sql .= "       eps_afiliados AF,";
      $sql .= "       eps_afiliados_beneficiarios AB,";
      $sql .= "       eps_parentescos_beneficiarios PB,";
      $sql .= "       tipo_pais TP,";
      $sql .= "       tipo_dptos TD,";
      $sql .= "       tipo_mpios TM ";
      $sql .= "WHERE  AB.cotizante_tipo_id = '".$datos['afiliado_tipo_id']."' ";
      $sql .= "AND    AB.cotizante_id = '".$datos['afiliado_id']."' ";
      $sql .= "AND    AD.tipo_pais_id = TP.tipo_pais_id ";
      $sql .= "AND    AD.tipo_dpto_id = TD.tipo_dpto_id ";
      $sql .= "AND    AD.tipo_mpio_id = TM.tipo_mpio_id ";
      $sql .= "AND    AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
      $sql .= "AND    AD.afiliado_id = AF.afiliado_id ";
      $sql .= "AND    AB.eps_afiliacion_id = AF.eps_afiliacion_id ";
      $sql .= "AND    AB.afiliado_tipo_id = AF.afiliado_tipo_id ";
      $sql .= "AND    AB.afiliado_id = AF.afiliado_id ";
      $sql .= "AND    AB.parentesco_id = PB.parentesco_id ";
      $sql .= "ORDER BY AF.eps_afiliacion_id ASC ";
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
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
    /**
    *
    */
    function ObtenerDiagnosticosCronicos($paciente)
    {
      $sql  = "SELECT  DA.diagnostico_id, ";
      $sql .= "        DA.diagnostico_nombre, ";
      $sql .= "        TO_CHAR(HI.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
      $sql .= "        HI.descripcion ";
      $sql .= "FROM    hc_diagnosticos_ingreso HI, ";
      $sql .= "        diagnosticos DA, ";
      $sql .= "        ( ";
      $sql .= "          SELECT MIN(HI.evolucion_id) AS evolucion_id, ";
      $sql .= "                 HI.tipo_diagnostico_id ";
      $sql .= "          FROM   hc_diagnosticos_ingreso HI, ";
      $sql .= "                 hc_evoluciones HE, ";
      $sql .= "                 ingresos IG ";
      $sql .= "          WHERE  HI.evolucion_id = HE.evolucion_id ";
      $sql .= "          AND    HE.ingreso = IG.ingreso ";
      $sql .= "          AND    HI.sw_cronico = '1' ";
      $sql .= "          AND    IG.tipo_id_paciente = '".$paciente['tipo_id_paciente']."' ";
      $sql .= "          AND    IG.paciente_id = '".$paciente['paciente_id']."' ";
      $sql .= "          GROUP BY HI.tipo_diagnostico_id ";
      $sql .= "        ) AS HE ";
      $sql .= "WHERE   DA.diagnostico_id = HI.tipo_diagnostico_id ";
      $sql .= "AND     HI.evolucion_id = HE.evolucion_id ";
      $sql .= "AND     HI.tipo_diagnostico_id = HE.tipo_diagnostico_id ";
      $sql .= "ORDER BY HI.fecha_registro ";
      
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
    *
    */
    function ObtenerRiesgos($paciente)
    {
      $sql  = "SELECT CI.descripcion AS ciclo_descripcion, ";
      $sql .= "       TO_CHAR(CP.fecha_registro, 'DD/MM/YYYY') AS fecha_registro,";
      $sql .= "       CF.descripcion AS riesgo_decripcion ";
      $sql .= "FROM   ciclo_vital_factores_riesgo CF, ";
      $sql .= "       ciclo_vital_factores_riesgo_paciente CP, ";
      $sql .= "       ciclo_vital_individual CI ";
      $sql .= "WHERE  CF.factor_riesgo_id = CP.factor_riesgo_id ";
      $sql .= "AND    CI.ciclo_vital_individual_id = CP.ciclo_vital_individual_id ";
      $sql .= "AND    CP.tipo_id_paciente = '".$paciente['tipo_id_paciente']."' ";
      $sql .= "AND    CP.paciente_id = '".$paciente['paciente_id']."' ";
      $sql .= "ORDER BY CP.fecha_registro ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos; 
    }
    /**
    *
    */
    function ObtenerDatosCicloFamiliar($paciente)
    {
      $sql  = "SELECT grupo,";
      $sql .= "       descripcion, ";
      $sql .= "       MIN(fecha_registro) AS fecha_registro ";
      $sql .= "FROM   (";
      $sql .= "         SELECT  'CICLO VITAL FAMILIAR' AS grupo, ";
      $sql .= "                 CV.descripcion, ";
      $sql .= "                 TO_CHAR(CD.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
      $sql .= "         FROM    ciclo_vital_familiar CV, ";
      $sql .= "                 ciclo_vital_familiar_detalle CD ";
      $sql .= "         WHERE   CD.ciclo_vital_familiar_id = CV.ciclo_vital_familiar_id ";
      $sql .= "         AND     CD.tipo_id_paciente = '".$paciente['tipo_id_paciente']."' ";
      $sql .= "         AND     CD.paciente_id = '".$paciente['paciente_id']."' ";
      $sql .= "         UNION ALL ";
      $sql .= "         SELECT  'FACTORES DE RIESGO' AS grupo, ";
      $sql .= "                 CR.descripcion, ";
      $sql .= "                 TO_CHAR(CP.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
      $sql .= "         FROM    ciclo_vital_factores_riesgo CR, ";
      $sql .= "                 ciclo_vital_factores_riesgo_paciente CP ";
      $sql .= "         WHERE   CR.factor_riesgo_id = CP.factor_riesgo_id ";
      $sql .= "         AND     CP.tipo_id_paciente = '".$paciente['tipo_id_paciente']."' ";
      $sql .= "         AND     CP.paciente_id = '".$paciente['paciente_id']."' ";
      $sql .= "       )AS A ";
      $sql .= "GROUP BY 1,2 ";
      $sql .= "ORDER BY 1 ";
      
      $rst = $this->ConexionBaseDatos($sql);
      
      $datos = array();         
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      return $datos;
    }
    /**
    *
    */
    function ObtenerDatosCicloIndividual($paciente)
    {
      $sql  = "SELECT DISTINCT 'CICLO VITAL INDIVIDUAL' AS grupo, ";
      $sql .= "       CI.descripcion ";
      $sql .= "FROM   ingresos IG, ";
      $sql .= "       pacientes PA, ";
      $sql .= "       ciclo_vital_individual CI ";
      $sql .= "WHERE  IG.paciente_id = PA.paciente_id  ";
      $sql .= "AND    IG.tipo_id_paciente = PA.tipo_id_paciente  ";
      $sql .= "AND    edad(PA.fecha_nacimiento) >= edad_min  ";
      $sql .= "AND    edad(PA.fecha_nacimiento) <= edad_max  ";
      $sql .= "AND    IG.tipo_id_paciente = '".$paciente['tipo_id_paciente']."' ";
      $sql .= "AND    IG.paciente_id = '".$paciente['paciente_id']."' ";
      
      $rst = $this->ConexionBaseDatos($sql);
      
      $datos = array();         
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      return $datos;
    }
    /**
    *
    */
    function ObtenerMedicamentosUsuario($paciente)
    {
      $sql  = "SELECT a.*,";
      $sql .= "       b.descripcion ";
      $sql .= "FROM  ( SELECT  w.*, ";
      $sql .= "                y.nombre ";
      $sql .= "        FROM   ( ";
      $sql .= "                  SELECT HA.* ";
      $sql .= "                  FROM  hc_formulacion_antecedentes HA,";
      $sql .= "                        hc_evoluciones HE ";
      $sql .= "                  WHERE HA.evolucion_id = HE.evolucion_id "; 
      $sql .= "                  AND   HA.tipo_id_paciente = '".$paciente['tipo_id_paciente']."' ";
      $sql .= "                  AND   HA.paciente_id = '".$paciente['paciente_id']."' ";
      $sql .= "                ) as w ";
      $sql .= "                LEFT JOIN system_usuarios as y ";
      $sql .= "                ON(w.medico_id=y.usuario_id) ";
      $sql .= "      ) as a ";
      $sql .= "      LEFT JOIN inventarios_productos as b ";
      $sql .= "      ON(a.codigo_medicamento = b.codigo_producto) ";
      $sql .= "ORDER BY a.fecha_registro DESC ";
      
      $result = $this->ConexionBaseDatos($sql);
      $medicamentos_usu=Array();
      while(!$result->EOF)
      {
        $medicamentos_usu[] = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
      }
     
      $result->Close();
      return $medicamentos_usu;
    }
    /**
    *
    */
    function ObtenerProgramasPyP($paciente)
    {
      $sql  = "SELECT PP.descripcion, ";
      $sql .= "       TO_CHAR(PI.fecha_inscripcion, 'DD/MM/YYYY') AS fecha_inscripcion ";
      $sql .= "FROM   pyp_inscripciones_pacientes PI, ";
      $sql .= "       pyp_programas PP ";
      $sql .= "WHERE  PI.programa_id = PP.programa_id ";
      $sql .= "AND    PI.tipo_id_paciente = '".$paciente['tipo_id_paciente']."' ";
      $sql .= "AND    PI.paciente_id = '".$paciente['paciente_id']."' ";
      $sql .= "ORDER BY PI.fecha_inscripcion ";
      
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
    *
    */
    function ObtenerIncapacidades($paciente)
    {
      $sql  = "SELECT COUNT(*) AS cantidad ";
      $sql .= "FROM   hc_incapacidades ";
      $sql .= "WHERE  tipo_id_paciente = '".$paciente['tipo_id_paciente']."' ";
      $sql .= "AND    paciente_id = '".$paciente['paciente_id']."' ";
            
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $retorno = array();
      $datos = array();
      while(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      $retorno['cantidad_incapacidades'] = $datos['cantidad'];
      
      $fechaF = date("Y-m-d");
      $fechaI = (date("Y")-1)."-".date("m-d");
      
      $sql  = "SELECT SUM(dias_de_incapacidad) AS dias_incapacidad ";
      $sql .= "FROM   hc_incapacidades ";
      $sql .= "WHERE  tipo_id_paciente = '".$paciente['tipo_id_paciente']."' ";
      $sql .= "AND    paciente_id = '".$paciente['paciente_id']."' ";
      $sql .= "AND    fecha_inicio >= '".$fechaI."'::date ";
      $sql .= "AND    fecha_inicio <= '".$fechaF."'::date ";
            
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      $retorno['dias_incapacidad'] = $datos['dias_incapacidad'];
      
      return $retorno; 
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
      //$dbconn->debug=true;

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