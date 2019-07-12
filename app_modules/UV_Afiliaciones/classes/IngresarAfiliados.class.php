<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: IngresarAfiliados.class.php,v 1.4 2009/11/30 13:07:02 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : IngresarAfiliados
  * Clase encargada de hacer el registro de las afiliaciones y actualizaciones de
  * datos de los afiliados (cotizantes y beneficiarios)
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.4 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  IncludeClass("Afiliaciones", "", "app","UV_Afiliaciones");
  class IngresarAfiliados extends Afiliaciones
  {
    /**
    * Constructor de la clase
    */
    function IngresarAfiliados(){}
    /**
    * Funcion donde se actualizabn los datos de los afiliados,
    * cuando estos ya existen en la base de datos y se crean los documentos de la afiliacion
    *
    * @param array $datos Arreglo, que contiene los datos de la afiliacion y el cotizante
    *
    * @return boolean
    */
    function ActualizarDatosAfiliacion($datos)
    {
      $f1 = $f2 = $f3 = $f4 = $f5 = $f6 = array();

      $f1 = explode("/",$datos['fecha_nacimiento']);

      $fecha_sgss = "NULL";
      if($datos['fecha_sgss'])
      {
        $f2 = explode("/",$datos['fecha_sgss']);
        $fecha_sgss = "'".$f2[2]."-".$f2[1]."-".$f2[0]."'";
      }

      ($datos['eps_anterior'] != "-1" && $datos['eps_anterior'])? $datos['eps_anterior'] = "'".$datos['eps_anterior']."'": $datos['eps_anterior'] = "NULL";

      $fecha_anterior = "NULL";
      if($datos['fecha_afiliacion'])
      {
        $f4 = explode("/",$datos['fecha_afiliacion']);
        $fecha_anterior = "'".$f4[2]."-".$f4[1]."-".$f4[0]."'";
      }

      $fecha_vencimiento = "NULL";
      if($datos['fecha_vencimiento'])
      {
        $f2 = explode("/",$datos['fecha_vencimiento']);
        $fecha_vencimiento = "'".$f2[2]."-".$f2[1]."-".$f2[0]."'";
      }
      
      $f3 = explode("/",$datos['fecha_afiliacion_empresa']);
      $f5 = explode("/",$datos['fecha_recepcion']);
      if($datos['fecha_ingreso_empleo'])
        $f6 = explode("/",$datos['fecha_ingreso_empleo']);

      $grupo_primario = "NULL";
      if($datos['grupos_primarios'] != '-1') $grupo_primario = "'".$datos['grupos_primarios']."'";

      $sql = "LOCK TABLE eps_afiliaciones IN ROW EXCLUSIVE MODE ";

      $this->ConexionTransaccion();
      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      $sql  = "SELECT NEXTVAL('eps_afiliaciones_eps_afiliacion_id_seq') AS id; ";
      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      $eps_afiliacion_id = 0;
      while(!$rst->EOF)
      {
        $eps_afiliacion_id = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }

      $sql  = "UPDATE eps_afiliados_datos ";
      $sql .= "SET    primer_apellido = '".strtoupper(str_replace("'","''",$datos['primerapellido']))."', ";
      $sql .= "       segundo_apellido = '".strtoupper(str_replace("'","''",$datos['segundoapellido']))."', ";
      $sql .= "       primer_nombre =   '".strtoupper(str_replace("'","''",$datos['primernombre']))."', ";
      $sql .= "       segundo_nombre = '".strtoupper(str_replace("'","''",$datos['segundonombre']))."', ";
      $sql .= "       fecha_nacimiento = '".$f1[2]."-".$f1[1]."-".$f1[0]."', ";
      $sql .= "       fecha_afiliacion_sgss = ".$fecha_sgss.", ";
      $sql .= "       tipo_sexo_id = '".$datos['tipo_sexo']."', ";
      $sql .= "       ciuo_88_grupo_primario = ".$grupo_primario.", ";
      $sql .= "       tipo_pais_id = '".$datos['pais']."', ";
      $sql .= "       tipo_dpto_id = '".$datos['dpto']."', ";
      $sql .= "       tipo_mpio_id = '".$datos['mpio']."', ";
      $sql .= "       zona_residencia = '".$datos['zona_residencia']."', ";
      $sql .= "       direccion_residencia = '".$datos['direccion_residencia']."', ";
      $sql .= "       telefono_residencia = '".$datos['telefono_residencia']."', ";
      $sql .= "       telefono_movil = '".$datos['telefono_movil']."', ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().",   ";
      $sql .= "       fecha_ultima_actualizacion = NOW()     ";
      $sql .= "WHERE  afiliado_tipo_id = '".$datos['tipo_id_paciente']."'  ";
      $sql .= "AND    afiliado_id = '".$datos['documento']."'; ";

      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      $sql  = "INSERT INTO eps_afiliaciones (";
      $sql .= "       eps_afiliacion_id     , ";
      $sql .= "       eps_tipo_afiliacion_id    , ";
      $sql .= "       fecha_recepcion   , ";
      $sql .= "       usuario_registro  , ";
      $sql .= "       fecha_registro    , ";
      $sql .= "       usuario_ultima_actualizacion  , ";
      $sql .= "       fecha_ultima_actualizacion ) ";
      $sql .= "VALUES ( ";
      $sql .= "        ".$eps_afiliacion_id['id'].",";
      $sql .= "        '".$datos['tipo_afiliacion']."',";
      $sql .= "        '".$f5[2]."-".$f5[1]."-".$f5[0]."',";
      $sql .= "         ".UserGetUID().",";
      $sql .= "         NOW(),";
      $sql .= "         ".UserGetUID().",";
      $sql .= "         NOW() ";
      $sql .= "       ); ";

      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      if(!$datos['semanas_cotizadas']) $datos['semanas_cotizadas'] = 0;

      $sql  = "INSERT INTO eps_afiliados ";
      $sql .= "   (   eps_afiliacion_id, ";
      $sql .= "       afiliado_tipo_id  , ";
      $sql .= "       afiliado_id   , ";
      $sql .= "       eps_tipo_afiliado_id  , ";
      $sql .= "       fecha_afiliacion  , ";
      $sql .= "       eps_anterior  , ";
      $sql .= "       fecha_afiliacion_eps_anterior     , ";
      $sql .= "       semanas_cotizadas_eps_anterior    , ";
      $sql .= "       plan_atencion, ";
      $sql .= "       tipo_afiliado_atencion, ";
      $sql .= "       rango_afiliado_atencion, ";
      $sql .= "       eps_punto_atencion_id, ";
      $sql .= "       fecha_vencimiento, ";
      $sql .= "       observaciones     , ";
      $sql .= "       usuario_registro  , ";
      $sql .= "       fecha_registro    , ";
      $sql .= "       usuario_ultima_actualizacion  , ";
      $sql .= "       fecha_ultima_actualizacion) ";
      $sql .= "VALUES ( ";
      $sql .= "        ".$eps_afiliacion_id['id'].",";
      $sql .= "        '".$datos['tipo_id_paciente']."',";
      $sql .= "        '".$datos['documento']."',";
      $sql .= "        '".$datos['tipo_afiliado']."',";
      $sql .= "        '".$f3[2]."-".$f3[1]."-".$f3[0]."',";
      $sql .= "         ".$datos['eps_anterior'].",";
      $sql .= "         ".$fecha_anterior.",";
      $sql .= "         ".$datos['semanas_cotizadas'].",";
      $sql .= "         ".$datos['plan_atencion'].",";
      $sql .= "        '".$datos['tipo_afiliado_plan']."',";
      $sql .= "        '".$datos['rango_afiliado_plan']."',";
      $sql .= "        '".$datos['puntos_atencion']."',";
      $sql .= "         ".$fecha_vencimiento.",";
      $sql .= "        '".$datos['observaciones']."',";
      $sql .= "        ".UserGetUID().",";
      $sql .= "        NOW(),";
      $sql .= "        ".UserGetUID().",";
      $sql .= "        NOW() ";
      $sql .= "       ); ";

      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      $afp = $datos['administradora_pensiones'];
      ($afp == "-1")? $afp = "NULL":$afp = "'".$afp."'";

      $ing_mensual = $datos['salario_base'];
      if($datos['ingreso_mensual']) $ing_mensual = $datos['ingreso_mensual'];

      if(!$ing_mensual) $ing_mensual = 0;

      $fecha = "NULL";
      if($datos['fecha_ingreso_empleo']) $fecha = "'".$f6[2]."-".$f6[1]."-".$f6[0]."'";

      if(!$datos['sirh_per_codigo']) $datos['sirh_per_codigo'] = "NULL";
      if(!$datos['ter_codigo']) 
        $datos['ter_codigo'] = "NULL";
      else
        $datos['ter_codigo'] = "'".$datos['ter_codigo']."'";
      
      if($datos['parentesco'] == '-1') 
        $datos['parentesco'] = "NULL";
      else
        $datos['parentesco'] = "'".$datos['parentesco']."'";
      
      if($datos['division_actividad'] == '-1')
        $datos['division_actividad'] = "NULL";
      else
        $datos['division_actividad'] = "'".$datos['division_actividad']."'";
      
      if($datos['grupo_actividad'] == '-1')
        $datos['grupo_actividad'] = "NULL";
      else
        $datos['grupo_actividad'] = "'".$datos['grupo_actividad']."'";
      
      if($datos['clase_actividad'] == '-1')
        $datos['clase_actividad'] = "NULL";
      else
        $datos['clase_actividad'] = "'".$datos['clase_actividad']."'";
      
      if($datos['estrato_socioeconomico'] == '-1')
        $datos['estrato_socioeconomico'] = "NULL";
      else
        $datos['estrato_socioeconomico'] = "'".$datos['estrato_socioeconomico']."'";
      
      $sql  = "INSERT INTO eps_afiliados_cotizantes (";
      $sql .= "        eps_afiliacion_id,";
      $sql .= "        afiliado_tipo_id,";
      $sql .= "        afiliado_id,";
      $sql .= "        ciiu_r3_division,";
      $sql .= "        ciiu_r3_grupo,";
      $sql .= "        ciiu_r3_clase,";
      $sql .= "        telefono_dependencia,";
      $sql .= "        estrato_socioeconomico_id,";
      $sql .= "        tipo_estado_civil_id,";
      $sql .= "        tipo_aportante_id,";
      $sql .= "        estamento_id,";
      $sql .= "        codigo_afp,";
      $sql .= "        ingreso_mensual,";
      $sql .= "        fecha_ingreso_laboral,";
      $sql .= "        codigo_dependencia_id,";
      $sql .= "        usuario_registro,";
      $sql .= "        fecha_registro,";
      $sql .= "        usuario_ultima_actualizacion,";
      $sql .= "        fecha_ultima_actualizacion, ";
      $sql .= "        sirh_per_codigo,  ";
      $sql .= "        ter_codigo, ";
      $sql .= "        parentesco_id ) ";
      $sql .= "VALUES (";
      $sql .= "        ".$eps_afiliacion_id['id'].",";
      $sql .= "        '".$datos['tipo_id_paciente']."',";
      $sql .= "        '".$datos['documento']."',";
      $sql .= "         ".$datos['division_actividad'].",";
      $sql .= "         ".$datos['grupo_actividad'].",";
      $sql .= "         ".$datos['clase_actividad'].",";
      $sql .= "        '".$datos['telefono_dependencia']."',";
      $sql .= "         ".$datos['estrato_socioeconomico'].",";
      $sql .= "        '".$datos['estado_civil']."',";
      $sql .= "        '".$datos['tipo_aportante']."',";
      $sql .= "        '".$datos['estamento']."',";
      $sql .= "         ".$afp." ,";
      $sql .= "         ".$ing_mensual.",";
      $sql .= "         ".$fecha.",";
      $sql .= "        '".$datos['dependencia_laboral']."',";
      $sql .= "         ".UserGetUID().",";
      $sql .= "         NOW(),";
      $sql .= "         ".UserGetUID().",";
      $sql .= "         NOW(), ";
      $sql .= "         ".$datos['sirh_per_codigo'].", ";
      $sql .= "         ".$datos['ter_codigo'].", ";
      $sql .= "         ".$datos['parentesco']." ";
      $sql .= "       );";

      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      $estament = $this->ObtenerEstamentos($datos['estamento']);
      
      if($estament[$datos['estamento']]['estamento_siis'] == "V")
      {
        $tercero = array();
        list($tercero_tipo_id,$tercero_id) = explode(" ",$datos['empresa_convenio']);

        $fi = explode("/",$datos['fecha_inicio_convenio']);
        $ff = explode("/",$datos['fecha_fin_convenio']);

        $sql = "INSERT INTO eps_afiliados_cotizantes_convenios (";
        $sql .= "       eps_afiliacion_id,";
        $sql .= "       afiliado_tipo_id ,";
        $sql .= "       afiliado_id,";
        $sql .= "       convenio_tipo_id_tercero,";
        $sql .= "       convenio_tercero_id,";
        $sql .= "       fecha_inicio_convenio,";
        $sql .= "       fecha_vencimiento_convenio,";
        $sql .= "       usuario_registro,";
        $sql .= "       fecha_registro,";
        $sql .= "       usuario_ultima_actualizacion,";
        $sql .= "       fecha_ultima_actualizacion ) ";
        $sql .= "VALUES (";
        $sql .= "        ".$eps_afiliacion_id['id'].",";
        $sql .= "        '".$datos['tipo_id_paciente']."',";
        $sql .= "        '".$datos['documento']."',";
        $sql .= "        '".$tercero_tipo_id."',";
        $sql .= "        '".$tercero_id."',";
        $sql .= "        '".$fi[2]."-".$fi[1]."-".$fi[0]."',";
        $sql .= "        '".$ff[2]."-".$ff[1]."-".$ff[0]."',";
        $sql .= "         ".UserGetUID().",";
        $sql .= "         NOW(),";
        $sql .= "         ".UserGetUID().",";
        $sql .= "         NOW() ";
        $sql .= "); ";

        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      $this->dbconn->CommitTrans();

      return true;
    }
    /**
    * Funcion donde se crea un nuevo afiliado cotizante, con los documentos
    * de afiliacion
    *
    * @param array $datos Arreglo, que contiene los datos de la afiliacion y el cotizante
    * @param array $estamentos Arreglo, que contiene los datos de los estamentos
    *
    * @return boolean
    */
    function IngresarDatosAfiliacion($datos,$estamentos)
    {
      $f1 = $f2 = $f3 = $f4 = $f5 = $f6 = array();

      $f1 = explode("/",$datos['fecha_nacimiento']);
      $f3 = explode("/",$datos['fecha_afiliacion_empresa']);
      $f5 = explode("/",$datos['fecha_recepcion']);
      if($datos['fecha_ingreso_empleo'])
        $f6 = explode("/",$datos['fecha_ingreso_empleo']);

      ($datos['eps_anterior'] != "-1" && $datos['eps_anterior'])? $datos['eps_anterior'] = "'".$datos['eps_anterior']."'": $datos['eps_anterior'] = "NULL";

      $fecha_anterior = "NULL";
      if($datos['fecha_afiliacion'])
      {
        $f4 = explode("/",$datos['fecha_afiliacion']);
        $fecha_anterior = "'".$f4[2]."-".$f4[1]."-".$f4[0]."'";
      }

      $fecha_sgss = "NULL";
      if($datos['fecha_sgss'])
      {
        $f2 = explode("/",$datos['fecha_sgss']);
        $fecha_sgss = "'".$f2[2]."-".$f2[1]."-".$f2[0]."'";
      }

      $fecha_vencimiento = "NULL";
      if($datos['fecha_vencimiento'])
      {
        $f2 = explode("/",$datos['fecha_vencimiento']);
        $fecha_vencimiento = "'".$f2[2]."-".$f2[1]."-".$f2[0]."'";
      }
      
      $grupo_primario = "NULL";
      if($datos['grupos_primarios'] != '-1') $grupo_primario = "'".$datos['grupos_primarios']."'";

      $sql = "LOCK TABLE eps_afiliaciones IN ROW EXCLUSIVE MODE ";

      $this->ConexionTransaccion();
      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      $sql  = "SELECT NEXTVAL('eps_afiliaciones_eps_afiliacion_id_seq') AS id; ";
      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      $eps_afiliacion_id = 0;
      while(!$rst->EOF)
      {
        $eps_afiliacion_id = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }

      $sql  = "INSERT INTO eps_afiliados_datos (";
      $sql .= "       primer_apellido , ";
      $sql .= "       segundo_apellido, ";
      $sql .= "       primer_nombre, ";
      $sql .= "       segundo_nombre, ";
      $sql .= "       fecha_nacimiento, ";
      $sql .= "       fecha_afiliacion_sgss, ";
      $sql .= "       tipo_sexo_id, ";
      $sql .= "       ciuo_88_grupo_primario, ";
      $sql .= "       tipo_pais_id, ";
      $sql .= "       tipo_dpto_id, ";
      $sql .= "       tipo_mpio_id, ";
      $sql .= "       zona_residencia, ";
      $sql .= "       direccion_residencia, ";
      $sql .= "       telefono_residencia , ";
      $sql .= "       telefono_movil, ";
      $sql .= "       usuario_registro,  ";
      $sql .= "       fecha_registro,    ";
      $sql .= "       usuario_ultima_actualizacion,  ";
      $sql .= "       fecha_ultima_actualizacion,    ";
      $sql .= "       afiliado_tipo_id,  ";
      $sql .= "       afiliado_id ) ";
      $sql .= "VALUES (";
      $sql .= "       '".strtoupper(str_replace("'","''",$datos['primerapellido']))."', ";
      $sql .= "       '".strtoupper(str_replace("'","''",$datos['segundoapellido']))."', ";
      $sql .= "       '".strtoupper(str_replace("'","''",$datos['primernombre']))."', ";
      $sql .= "       '".strtoupper(str_replace("'","''",$datos['segundonombre']))."', ";
      $sql .= "       '".$f1[2]."-".$f1[1]."-".$f1[0]."', ";
      $sql .= "        ".$fecha_sgss.", ";
      $sql .= "       '".$datos['tipo_sexo']."', ";
      $sql .= "        ".$grupo_primario.", ";
      $sql .= "       '".$datos['pais']."', ";
      $sql .= "       '".$datos['dpto']."', ";
      $sql .= "       '".$datos['mpio']."', ";
      $sql .= "       '".$datos['zona_residencia']."', ";
      $sql .= "       '".$datos['direccion_residencia']."', ";
      $sql .= "       '".$datos['telefono_residencia']."', ";
      $sql .= "       '".$datos['telefono_movil']."', ";
      $sql .= "        ".UserGetUID().",     ";
      $sql .= "        NOW(),    ";
      $sql .= "        ".UserGetUID().",     ";
      $sql .= "        NOW(),    ";
      $sql .= "       '".$datos['tipo_id_paciente']."',  ";
      $sql .= "       '".$datos['documento']."'";
      $sql .= "        ); ";

      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      $sql  = "INSERT INTO eps_afiliaciones (";
      $sql .= "       eps_afiliacion_id     , ";
      $sql .= "       eps_tipo_afiliacion_id    , ";
      $sql .= "       fecha_recepcion   , ";
      $sql .= "       usuario_registro  , ";
      $sql .= "       fecha_registro    , ";
      $sql .= "       usuario_ultima_actualizacion  , ";
      $sql .= "       fecha_ultima_actualizacion ) ";
      $sql .= "VALUES ( ";
      $sql .= "        ".$eps_afiliacion_id['id'].",";
      $sql .= "        '".$datos['tipo_afiliacion']."',";
      $sql .= "        '".$f5[2]."-".$f5[1]."-".$f5[0]."',";
      $sql .= "         ".UserGetUID().",";
      $sql .= "         NOW(),";
      $sql .= "         ".UserGetUID().",";
      $sql .= "         NOW() ";
      $sql .= "       ); ";

      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      if(!$datos['semanas_cotizadas']) $datos['semanas_cotizadas'] = 0;

      $sql  = "INSERT INTO eps_afiliados ";
      $sql .= "   (   eps_afiliacion_id, ";
      $sql .= "       afiliado_tipo_id  , ";
      $sql .= "       afiliado_id   , ";
      $sql .= "       eps_tipo_afiliado_id  , ";
      $sql .= "       fecha_afiliacion  , ";
      $sql .= "       eps_anterior  , ";
      $sql .= "       fecha_afiliacion_eps_anterior     , ";
      $sql .= "       semanas_cotizadas_eps_anterior    , ";
      
      $sql .= "       plan_atencion, ";
      $sql .= "       tipo_afiliado_atencion, ";
      $sql .= "       rango_afiliado_atencion, ";
      $sql .= "       eps_punto_atencion_id, ";
      $sql .= "       fecha_vencimiento , ";
      $sql .= "       observaciones     , ";
      $sql .= "       usuario_registro  , ";
      $sql .= "       fecha_registro    , ";
      $sql .= "       usuario_ultima_actualizacion  , ";
      $sql .= "       fecha_ultima_actualizacion) ";
      $sql .= "VALUES ( ";
      $sql .= "        ".$eps_afiliacion_id['id'].",";
      $sql .= "        '".$datos['tipo_id_paciente']."',";
      $sql .= "        '".$datos['documento']."',";
      $sql .= "        '".$datos['tipo_afiliado']."',";
      $sql .= "        '".$f3[2]."-".$f3[1]."-".$f3[0]."',";
      $sql .= "         ".$datos['eps_anterior'].",";
      $sql .= "         ".$fecha_anterior.",";
      $sql .= "         ".$datos['semanas_cotizadas'].",";
      $sql .= "         ".$datos['plan_atencion'].",";
      $sql .= "        '".$datos['tipo_afiliado_plan']."',";
      $sql .= "        '".$datos['rango_afiliado_plan']."',";
      $sql .= "        '".$datos['puntos_atencion']."',";
      $sql .= "         ".$fecha_vencimiento.",";
      $sql .= "        '".$datos['observaciones']."',";
      $sql .= "        ".UserGetUID().",";
      $sql .= "        NOW(),";
      $sql .= "        ".UserGetUID().",";
      $sql .= "        NOW() ";
      $sql .= "       ); ";

      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      $afp = $datos['administradora_pensiones'];
      ($afp == "-1")? $afp = "NULL":$afp = "'".$afp."'";

      $ing_mensual = $datos['salario_base'];
      if($datos['ingreso_mensual']) $ing_mensual = $datos['ingreso_mensual'];

      if(!$ing_mensual) $ing_mensual = 0;

      $fecha = "NULL";
      if($datos['fecha_ingreso_empleo']) $fecha = "'".$f6[2]."-".$f6[1]."-".$f6[0]."'";

      if(!$datos['sirh_per_codigo']) $datos['sirh_per_codigo'] = "NULL";
      
      if(!$datos['ter_codigo']) 
        $datos['ter_codigo'] = "NULL";
      else
        $datos['ter_codigo'] = "'".$datos['ter_codigo']."'";
      
      if($datos['parentesco'] == '-1') 
        $datos['parentesco'] = "NULL";
      else
        $datos['parentesco'] = "'".$datos['parentesco']."'";
      
      if($datos['division_actividad'] == '-1')
        $datos['division_actividad'] = "NULL";
      else
        $datos['division_actividad'] = "'".$datos['division_actividad']."'";
      
      if($datos['grupo_actividad'] == '-1')
        $datos['grupo_actividad'] = "NULL";
      else
        $datos['grupo_actividad'] = "'".$datos['grupo_actividad']."'";
      
      if($datos['clase_actividad'] == '-1')
        $datos['clase_actividad'] = "NULL";
      else
        $datos['clase_actividad'] = "'".$datos['clase_actividad']."'";
      
      if($datos['estrato_socioeconomico'] == '-1')
        $datos['estrato_socioeconomico'] = "NULL";
      else
        $datos['estrato_socioeconomico'] = "'".$datos['estrato_socioeconomico']."'";
      
      $sql  = "INSERT INTO eps_afiliados_cotizantes (";
      $sql .= "        eps_afiliacion_id,";
      $sql .= "        afiliado_tipo_id,";
      $sql .= "        afiliado_id,";
      $sql .= "        ciiu_r3_division,";
      $sql .= "        ciiu_r3_grupo,";
      $sql .= "        ciiu_r3_clase,";
      $sql .= "        telefono_dependencia,";
      $sql .= "        estrato_socioeconomico_id,";
      $sql .= "        tipo_estado_civil_id,";
      $sql .= "        tipo_aportante_id,";
      $sql .= "        estamento_id,";
      $sql .= "        codigo_afp,";
      $sql .= "        ingreso_mensual,";
      $sql .= "        fecha_ingreso_laboral,";
      $sql .= "        codigo_dependencia_id,";
      $sql .= "        usuario_registro,";
      $sql .= "        fecha_registro,";
      $sql .= "        usuario_ultima_actualizacion,";
      $sql .= "        fecha_ultima_actualizacion, ";
      $sql .= "        sirh_per_codigo, ";
      $sql .= "        ter_codigo , ";
      $sql .= "        parentesco_id ) ";
      $sql .= "VALUES (";
      $sql .= "        ".$eps_afiliacion_id['id'].",";
      $sql .= "        '".$datos['tipo_id_paciente']."',";
      $sql .= "        '".$datos['documento']."',";
      $sql .= "         ".$datos['division_actividad'].",";
      $sql .= "         ".$datos['grupo_actividad'].",";
      $sql .= "         ".$datos['clase_actividad'].",";
      $sql .= "        '".$datos['telefono_dependencia']."',";
      $sql .= "         ".$datos['estrato_socioeconomico'].",";
      $sql .= "        '".$datos['estado_civil']."',";
      $sql .= "        '".$datos['tipo_aportante']."',";
      $sql .= "        '".$datos['estamento']."',";
      $sql .= "         ".$afp." ,";
      $sql .= "         ".$ing_mensual.",";
      $sql .= "         ".$fecha.",";
      $sql .= "        '".$datos['dependencia_laboral']."',";
      $sql .= "         ".UserGetUID().",";
      $sql .= "         NOW(),";
      $sql .= "         ".UserGetUID().",";
      $sql .= "         NOW(), ";
      $sql .= "         ".$datos['sirh_per_codigo'].", ";
      $sql .= "         ".$datos['ter_codigo'].", ";
      $sql .= "         ".$datos['parentesco']." ";
      $sql .= "       );";

      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      if($estamentos[$datos['estamento']]['estamento_siis'] == "V")
      {
        $tercero = array();
        list($tercero_tipo_id,$tercero_id) = explode(" ",$datos['empresa_convenio']);

        $fi = explode("/",$datos['fecha_inicio_convenio']);
        $ff = explode("/",$datos['fecha_fin_convenio']);

        $sql = "INSERT INTO eps_afiliados_cotizantes_convenios (";
        $sql .= "       eps_afiliacion_id,";
        $sql .= "       afiliado_tipo_id ,";
        $sql .= "       afiliado_id,";
        $sql .= "       convenio_tipo_id_tercero,";
        $sql .= "       convenio_tercero_id,";
        $sql .= "       fecha_inicio_convenio,";
        $sql .= "       fecha_vencimiento_convenio,";
        $sql .= "       usuario_registro,";
        $sql .= "       fecha_registro,";
        $sql .= "       usuario_ultima_actualizacion,";
        $sql .= "       fecha_ultima_actualizacion ) ";
        $sql .= "VALUES (";
        $sql .= "        ".$eps_afiliacion_id['id'].",";
        $sql .= "        '".$datos['tipo_id_paciente']."',";
        $sql .= "        '".$datos['documento']."',";
        $sql .= "        '".$tercero_tipo_id."',";
        $sql .= "        '".$tercero_id."',";
        $sql .= "        '".$fi[2]."-".$fi[1]."-".$fi[0]."',";
        $sql .= "        '".$ff[2]."-".$ff[1]."-".$ff[0]."',";
        $sql .= "         ".UserGetUID().",";
        $sql .= "         NOW(),";
        $sql .= "         ".UserGetUID().",";
        $sql .= "         NOW() ";
        $sql .= "); ";

        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }

      $this->dbconn->CommitTrans();

      return true;
    }
    /**
    * Funcion donde se actualizan o se ingresan los datos de la afiliacion
    * de un beneficiario, asociado a una afiliacion
    *
    * @param array $cotizante Arreglo, que contiene los datos del cotizante
    *        necesarios para registrar los datos de los beneficiarios
    * @param array $beneficiarios Arreglo, que contiene los datos de los
    *        beneficiarios a actualizar o a ingresar
    *
    * @return boolean
    */
    function IngresarDatosAfiliacionBeneficiarios($cotizante,$beneficiarios)
    {
      $this->ConexionTransaccion();

      foreach($beneficiarios as $key => $tipo_id)
      {
        foreach($tipo_id as $keyI => $datos)
        {
          $f1 = $f2 = $f3 = $f4 = array();
          $sql = "";

          $f1 = explode("/",$datos['fecha_nacimiento']);

          ($datos['eps_anterior'] != "-1" && $datos['eps_anterior'])? $datos['eps_anterior'] = "'".$datos['eps_anterior']."'": $datos['eps_anterior'] = "NULL";

          $fecha_anterior = "NULL";
          if($datos['fecha_afiliacion'])
          {
            $f4 = explode("/",$datos['fecha_afiliacion']);
            $fecha_anterior = "'".$f4[2]."-".$f4[1]."-".$f4[0]."'";
          }

          $f3 = explode("/",$datos['fecha_afiliacion_empresa']);
          $fecha_sgss = "NULL";
          if($datos['fecha_sgss'])
          {
            $f2 = explode("/",$datos['fecha_sgss']);
            $fecha_sgss = "'".$f2[2]."-".$f2[1]."-".$f2[0]."'";
          }
          
          $fecha_vencimiento = "NULL";
          if($datos['fecha_vencimiento'])
          {
            $f2 = explode("/",$datos['fecha_vencimiento']);
            $fecha_vencimiento = "'".$f2[2]."-".$f2[1]."-".$f2[0]."'";
          }
          
          $grupo_primario = "NULL";
          if($datos['grupo_primario_hd'] != '-1' && $datos['grupo_primario_hd']) $grupo_primario = "'".$datos['grupo_primario_hd']."'";

          if($datos['accion'] == 0)
          {
            $sql .= "INSERT INTO eps_afiliados_datos (";
            $sql .= "         primer_apellido , ";
            $sql .= "         segundo_apellido, ";
            $sql .= "         primer_nombre, ";
            $sql .= "         segundo_nombre, ";
            $sql .= "         fecha_nacimiento, ";
            $sql .= "         fecha_afiliacion_sgss, ";
            $sql .= "         tipo_sexo_id, ";
            $sql .= "         ciuo_88_grupo_primario, ";
            $sql .= "         tipo_pais_id, ";
            $sql .= "         tipo_dpto_id, ";
            $sql .= "         tipo_mpio_id, ";
            $sql .= "         zona_residencia, ";
            $sql .= "         direccion_residencia, ";
            $sql .= "         telefono_residencia , ";
            $sql .= "         telefono_movil, ";
            $sql .= "         usuario_registro,  ";
            $sql .= "         fecha_registro,    ";
            $sql .= "         usuario_ultima_actualizacion,  ";
            $sql .= "         fecha_ultima_actualizacion,    ";
            $sql .= "         afiliado_tipo_id,  ";
            $sql .= "         afiliado_id ) ";
            $sql .= "VALUES (";
            $sql .= "         '".strtoupper(str_replace("'","''",$datos['primerapellido']))."', ";
            $sql .= "         '".strtoupper(str_replace("'","''",$datos['segundoapellido']))."', ";
            $sql .= "         '".strtoupper(str_replace("'","''",$datos['primernombre']))."', ";
            $sql .= "         '".strtoupper(str_replace("'","''",$datos['segundonombre']))."', ";
            $sql .= "         '".$f1[2]."-".$f1[1]."-".$f1[0]."', ";
            $sql .= "          ".$fecha_sgss.", ";
            $sql .= "         '".$datos['tipo_sexo']."', ";
            $sql .= "          ".$grupo_primario.", ";
            $sql .= "         '".$datos['pais']."', ";
            $sql .= "         '".$datos['dpto']."', ";
            $sql .= "         '".$datos['mpio']."', ";
            $sql .= "         '".$datos['zona_residencia']."', ";
            $sql .= "         '".$datos['direccion_residencia']."', ";
            $sql .= "         '".$datos['telefono_residencia']."', ";
            $sql .= "         '".$datos['telefono_movil']."', ";
            $sql .= "          ".UserGetUID().",     ";
            $sql .= "          NOW(),    ";
            $sql .= "          ".UserGetUID().",     ";
            $sql .= "          NOW(),    ";
            $sql .= "         '".$datos['tipo_id_beneficiario']."',  ";
            $sql .= "         '".$datos['documento']."'";
            $sql .= "        ); ";
          }
          else if($datos['accion'] == "1")
          {
            $sql .= "UPDATE   eps_afiliados_datos ";
            $sql .= "SET      primer_apellido =   '".strtoupper($datos['primerapellido'])."', ";
            $sql .= "         segundo_apellido = '".strtoupper($datos['segundoapellido'])."', ";
            $sql .= "         primer_nombre =   '".strtoupper($datos['primernombre'])."', ";
            $sql .= "         segundo_nombre = '".strtoupper($datos['segundonombre'])."', ";
            $sql .= "         fecha_nacimiento = '".$f1[2]."-".$f1[1]."-".$f1[0]."', ";
            $sql .= "         fecha_afiliacion_sgss =  ".$fecha_sgss.", ";
            $sql .= "         tipo_sexo_id = '".$datos['tipo_sexo']."', ";
            $sql .= "         ciuo_88_grupo_primario = ".$grupo_primario.", ";
            $sql .= "         tipo_pais_id = '".$datos['pais']."', ";
            $sql .= "         tipo_dpto_id = '".$datos['dpto']."', ";
            $sql .= "         tipo_mpio_id = '".$datos['mpio']."', ";
            $sql .= "         zona_residencia = '".$datos['zona_residencia']."', ";
            $sql .= "         direccion_residencia = '".$datos['direccion_residencia']."', ";
            $sql .= "         telefono_residencia = '".$datos['telefono_residencia']."', ";
            $sql .= "         usuario_ultima_actualizacion = ".UserGetUID().",   ";
            $sql .= "         fecha_ultima_actualizacion = NOW()     ";
            $sql .= "WHERE    afiliado_tipo_id = '".$datos['tipo_id_beneficiario']."'  ";
            $sql .= "AND      afiliado_id = '".$datos['documento']."'; ";
          }
          if(!$rst = $this->ConexionTransaccion($sql)) return false;

          if(!$datos['semanas_cotizadas']) $datos['semanas_cotizadas'] = 0;

          $sql  = "INSERT INTO eps_afiliados ";
          $sql .= "   (   eps_afiliacion_id, ";
          $sql .= "       afiliado_tipo_id  , ";
          $sql .= "       afiliado_id   , ";
          $sql .= "       eps_tipo_afiliado_id  , ";
          $sql .= "       fecha_afiliacion  , ";
          $sql .= "       eps_anterior  , ";
          $sql .= "       fecha_afiliacion_eps_anterior     , ";
          $sql .= "       semanas_cotizadas_eps_anterior    , ";
          $sql .= "       plan_atencion, ";
          $sql .= "       tipo_afiliado_atencion, ";
          $sql .= "       rango_afiliado_atencion, ";
          $sql .= "       fecha_vencimiento, ";
          $sql .= "       eps_punto_atencion_id, ";
          $sql .= "       usuario_registro  , ";
          $sql .= "       fecha_registro    , ";
          $sql .= "       usuario_ultima_actualizacion  , ";
          $sql .= "       fecha_ultima_actualizacion) ";
          $sql .= "VALUES ( ";
          $sql .= "        ".$cotizante['eps_afiliacion_id'].",";
          $sql .= "        '".$datos['tipo_id_beneficiario']."',";
          $sql .= "        '".$datos['documento']."',";
          $sql .= "        'B',";
          $sql .= "        '".$f3[2]."-".$f3[1]."-".$f3[0]."',";
          $sql .= "         ".$datos['eps_anterior'].",";
          $sql .= "         ".$fecha_anterior.",";
          $sql .= "         ".$datos['semanas_cotizadas'].",";
          $sql .= "         ".$datos['plan_atencion'].",";
          $sql .= "        '".$datos['tipo_afiliado_plan']."',";
          $sql .= "        '".$datos['rango_afiliado_plan']."',";
          $sql .= "         ".$fecha_vencimiento.", ";
          $sql .= "        '".$datos['puntos_atencion']."',";
          $sql .= "         ".UserGetUID().",";
          $sql .= "         NOW(),";
          $sql .= "         ".UserGetUID().",";
          $sql .= "         NOW() ";
          $sql .= "       ); ";

          if(!$rst = $this->ConexionTransaccion($sql)) return false;

          $sql  = "INSERT INTO eps_afiliados_beneficiarios( ";
          $sql .= "       eps_afiliacion_id ,";
          $sql .= "       afiliado_tipo_id  ,";
          $sql .= "       afiliado_id   ,";
          $sql .= "       cotizante_tipo_id     ,";
          $sql .= "       cotizante_id  ,";
          $sql .= "       parentesco_id     ,";
          $sql .= "       observaciones     ,";
          $sql .= "       usuario_registro  ,";
          $sql .= "       fecha_registro ,";
          $sql .= "       usuario_ultima_actualizacion ,";
          $sql .= "       fecha_ultima_actualizacion ";
          $sql .= " ) ";
          $sql .= "VALUES (";
          $sql .= "        ".$cotizante['eps_afiliacion_id']." ,";
          $sql .= "       '".$datos['tipo_id_beneficiario']."', ";
          $sql .= "       '".$datos['documento']."', ";
          $sql .= "       '".$cotizante['cotizante_tipo_id']."', ";
          $sql .= "       '".$cotizante['cotizante_id']."', ";
          $sql .= "       '".$datos['parentesco']."', ";
          $sql .= "       '".$datos['observaciones']."', ";
          $sql .= "        ".UserGetUID().",     ";
          $sql .= "        NOW(),    ";
          $sql .= "        ".UserGetUID().",     ";
          $sql .= "        NOW()     ";
          $sql .= " );";

          if(!$rst = $this->ConexionTransaccion($sql)) return false;
        }
      }
      $this->dbconn->CommitTrans();

      return true;
    }
    /**
    * Funcion donde se realiza la actualizacion del periodo de cobertura
    *
    * @param array $datos Vector con los datos a modificar en el convenio
    *
    * @return boolean
    */
    function ActualizarFechaConvenio($datos)
    {
      $f1 = explode("/",$datos['fecha_vencimiento_convenio']);

      $sql  = "UPDATE eps_afiliados_cotizantes_convenios ";
      $sql .= "SET    fecha_vencimiento_convenio = '".$f1[2]."-".$f1[1]."-".$f1[0]."', ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().",   ";
      $sql .= "       fecha_ultima_actualizacion = NOW()     ";
      $sql .= "WHERE  afiliado_tipo_id = '".$datos['afiliado_tipo_id']."' ";
      $sql .= "AND    afiliado_id = '".$datos['afiliado_id']."' ";
      $sql .= "AND    eps_afiliacion_id = ".$datos['eps_afiliacion_id']." ";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      return true;
    }
  }
?>