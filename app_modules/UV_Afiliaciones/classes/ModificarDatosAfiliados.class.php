<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ModificarDatosAfiliados.class.php,v 1.6 2009/10/05 18:27:11 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sEA.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : ModificarDatosAfiliados
  * Clase donde se consultan y se actualizan los datos de los afiliados 
  * que se han registrado
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.6 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sEA.com)
  * @author Hugo F  Manrique
  */
  IncludeClass("Afiliaciones", "", "app","UV_Afiliaciones");
  class ModificarDatosAfiliados extends Afiliaciones
  {
    /**
    * Contructor de la clase
    */
    function ModificarDatosAfiliados(){}
    /**
    * Funcion para obtener la lista de afiliados existenters en el sistema
    *
    * @param array $datos vector con los parametros de busqueda
    * @param int $pg_siguiente indica el numero de la pagina que se esta visualizando
    *
    * @return array 
    */
    function ObtenerListaAfiliados($datos,$pg_siguiente)
    {       
      $sql  = "SELECT EA.eps_afiliacion_id, ";
      $sql .= "       EA.afiliado_tipo_id, ";
      $sql .= "       EA.afiliado_id, ";
      $sql .= "       EA.eps_tipo_afiliado_id, ";
      $sql .= "       EA.estado_afiliado_id, ";
      $sql .= "       EA.subestado_afiliado_id, ";
      $sql .= "       TO_CHAR(EA.fecha_afiliacion,'DD/MM/YYYY') AS fecha_afiliacion, ";
      $sql .= "       ED.primer_apellido || ' ' || ED.segundo_apellido AS apellidos_afiliado,  ";
      $sql .= "       ED.primer_nombre  || ' ' || ED.segundo_nombre AS nombres_afiliado, ";
      $sql .= "       TO_CHAR(ED.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
      $sql .= "       edad(ED.fecha_nacimiento) AS edad, ";
      $sql .= "       EP.descripcion_eps_tipo_afiliado, ";
      $sql .= "       EE.descripcion_estamento, ";
      $sql .= "       EE.estamento_siis, ";
      $sql .= "       EC.descripcion_tipo_aportante, ";
      $sql .= "       AU.descripcion_subestado, ";
      $sql .= "       AE.descripcion_estado, ";
      $sql .= "       EC.descripcion_parentesco ";
      $whr  = "FROM   eps_afiliados EA, ";
      $whr .= "       eps_afiliaciones EF, ";
      $whr .= "       ( SELECT    EB.eps_afiliacion_id, ";
      $whr .= "                   EB.afiliado_id, ";
      $whr .= "                   EB.afiliado_tipo_id, ";
      $whr .= "                   EC.estamento_id, ";
      $whr .= "                   EP.descripcion_parentesco, ";
      $whr .= "                   '' AS descripcion_tipo_aportante ";
      $whr .= "           FROM    eps_afiliados_beneficiarios EB,"; 
      $whr .= "                   eps_afiliados_cotizantes EC, " ;
      $whr .= "                   eps_parentescos_beneficiarios EP " ;
      $whr .= "           WHERE   EB.cotizante_tipo_id = EC.afiliado_tipo_id";
      $whr .= "           AND     EB.cotizante_id = EC.afiliado_id ";
      $whr .= "           AND     EB.eps_afiliacion_id = EC.eps_afiliacion_id ";
      $whr .= "           AND     EB.parentesco_id = EP.parentesco_id ";
      if($datos['Estamento'] != '-1')
        $whr .= "           AND EC.estamento_id = '".$datos['Estamento']."' ";
      
      $whr .= "           UNION ALL ";
      $whr .= "           SELECT  EC.eps_afiliacion_id, ";
      $whr .= "                   EC.afiliado_id, ";
      $whr .= "                   EC.afiliado_tipo_id,";
      $whr .= "                   EC.estamento_id, ";
      $whr .= "                   EP.descripcion_parentesco, ";
      $whr .= "                   TA.descripcion_tipo_aportante ";
      $whr .= "           FROM    eps_afiliados_cotizantes EC LEFT JOIN " ;
      $whr .= "                   eps_parentescos_beneficiarios EP " ;
      $whr .= "                   ON (EC.parentesco_id = EP.parentesco_id), " ;
      $whr .= "                   eps_tipos_aportantes AS TA " ;
      $whr .= "           WHERE   TA.tipo_aportante_id = EC.tipo_aportante_id  ";
      if($datos['Estamento'] != '-1')
        $whr .= "           AND EC.estamento_id = '".$datos['Estamento']."' ";
      $whr .= "         ) AS EC, " ;
      $whr .= "         eps_estamentos EE, ";
      $whr .= "         eps_afiliados_datos ED, ";
      $whr .= "         eps_tipos_afiliados EP, ";
      $whr .= "         eps_afiliados_estados AE,";
      $whr .= "         eps_afiliados_subestados AU ";
      $whr .= "WHERE    EF.eps_afiliacion_id = EA.eps_afiliacion_id ";
      $whr .= "AND      ED.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $whr .= "AND      ED.afiliado_id = EA.afiliado_id ";
      $whr .= "AND      EP.eps_tipo_afiliado_id = EA.eps_tipo_afiliado_id ";
      $whr .= "AND      AU.estado_afiliado_id = EA.estado_afiliado_id ";
      $whr .= "AND      AU.subestado_afiliado_id = EA.subestado_afiliado_id ";
      $whr .= "AND      AU.estado_afiliado_id = AE.estado_afiliado_id ";
      $whr .= "AND      EC.eps_afiliacion_id = EA.eps_afiliacion_id  ";
      $whr .= "AND      EC.afiliado_tipo_id = EA.afiliado_tipo_id  ";
      $whr .= "AND      EC.afiliado_id = EA.afiliado_id  ";
      $whr .= "AND      EE.estamento_id = EC.estamento_id  ";

      if($datos['Documento'])
      {
        $whr .= "AND      ED.afiliado_id = '".$datos['Documento']."' ";
        
        if($datos['TipoDocumento'] != "-1")
          $whr .= "AND      ED.afiliado_tipo_id = '".$datos['TipoDocumento']."' ";
      }
      
      if($datos['tipo_afiliado'] != '-1')
        $whr .= "AND      EA.eps_tipo_afiliado_id = '".$datos['tipo_afiliado']."' ";
      
      if($datos['Nombres'] || $datos['Apellidos'])
      {
        $util = AutoCarga::factory('ClaseUtil');
        $whr .= "AND      ".$util->FiltrarNombres($datos['Nombres'],$datos['Apellidos'],"ED");
      }
      if($datos['edad'])
      {
        $whr .= "AND      edad(ED.fecha_nacimiento) "; 
        switch($datos['edad_signo'])
        {
          case 1: $whr .= " =  ".$datos['edad']." "; break;
          case 2: $whr .= " >  ".$datos['edad']." "; break;
          case 3: $whr .= " >= ".$datos['edad']." "; break;
          case 4: $whr .= " <  ".$datos['edad']." "; break;
          case 5: $whr .= " <= ".$datos['edad']." "; break;
          case 6: $whr .= " BETWEEN ".$datos['edad']. " AND ".$datos['edad_maxima']." "; break;
        }
      }
      
      switch($datos['vencimiento'])
      {
        case 2: 
          $whr .= "AND    EA.fecha_vencimiento < NOW()::date "; 
        break;
        case 3: 
          $whr .= "AND    EA.fecha_vencimiento >= NOW()::date "; 
        break;
      }
      
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr",$pg_siguiente))
       return false;
      
      $whr .= "ORDER BY EA.eps_afiliacion_id,EA.eps_tipo_afiliado_id DESC,apellidos_afiliado ";
      $whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr,null)) return false;

      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion para obtener la informacion de la afiliacion de un afiliado cotizante
    *
    * @param array $datos vector con los datos de docmento, tipo de documento y numero de afiliacion
    *
    * @return array 
    */
    function ObtenerDatosAfiliado($datos)
    { 	 	
      $sql  = "SELECT   EA.eps_afiliacion_id, ";
      $sql .= "         EA.afiliado_tipo_id, ";
      $sql .= "         EA.afiliado_id, ";
      $sql .= "         EA.eps_anterior, ";
      $sql .= "         EA.semanas_cotizadas_eps_anterior, ";
      $sql .= "         EA.observaciones, ";
      $sql .= "         ED.primer_apellido,";
      $sql .= "         ED.segundo_apellido,  ";
      $sql .= "         ED.primer_nombre,";
      $sql .= "         ED.segundo_nombre, ";
      $sql .= "         ED.tipo_sexo_id, ";
      $sql .= "         ED.tipo_pais_id, ";
      $sql .= "         ED.tipo_dpto_id, ";
      $sql .= "         ED.tipo_mpio_id, ";
      $sql .= "         ED.direccion_residencia, ";
      $sql .= "         ED.zona_residencia, ";
      $sql .= "         ED.telefono_residencia, ";
      $sql .= "         ED.telefono_movil, ";
      $sql .= "         ED.ciuo_88_grupo_primario, ";
      $sql .= "         EA.eps_tipo_afiliado_id,";
      $sql .= "         EA.fecha_afiliacion,";
      $sql .= "         EA.fecha_afiliacion_eps_anterior,";
      $sql .= "         EA.semanas_cotizadas_eps_anterior,";
      $sql .= "         EA.eps_anterior,";
      $sql .= "         EA.plan_atencion, ";
      $sql .= "         EA.tipo_afiliado_atencion, ";
      $sql .= "         EA.rango_afiliado_atencion, ";
      $sql .= "         EA.eps_punto_atencion_id, ";
      $sql .= "         AC.estamento_id,";
      $sql .= "         AC.codigo_dependencia_id,";
      $sql .= "         AC.tipo_aportante_id, ";
      $sql .= "         AC.tipo_estado_civil_id, ";
      $sql .= "         AC.estrato_socioeconomico_id, ";
      $sql .= "         AC.codigo_dependencia_id, ";
      $sql .= "         AC.telefono_dependencia, ";
      $sql .= "         AC.ciiu_r3_division,";
      $sql .= " 	      AC.ciiu_r3_grupo, ";
      $sql .= " 	      AC.ciiu_r3_clase, ";
      $sql .= " 	      AC.codigo_afp, ";
      $sql .= " 	      AC.ingreso_mensual, ";
      $sql .= " 	      AC.parentesco_id, ";
      $sql .= " 	      CO.convenio_tipo_id_tercero, ";
      $sql .= " 	      CO.convenio_tercero_id, ";
      $sql .= " 	      AF.eps_tipo_afiliacion_id, 	";
      $sql .= "         TO_CHAR(AF.fecha_recepcion,'DD/MM/YYYY') AS fecha_recepcion, ";
      $sql .= "         TO_CHAR(EA.fecha_vencimiento,'DD/MM/YYYY') AS fecha_vencimiento, ";
      $sql .= "         TO_CHAR(ED.fecha_afiliacion_sgss,'DD/MM/YYYY') AS fecha_afiliacion_sgss, ";
      $sql .= "         TO_CHAR(ED.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
      $sql .= "         TO_CHAR(EA.fecha_afiliacion,'DD/MM/YYYY') AS fecha_afiliacion, ";
      $sql .= "         TO_CHAR(EA.fecha_afiliacion_eps_anterior,'DD/MM/YYYY') AS fecha_afiliacion_eps_anterior, ";
      $sql .= "         TO_CHAR(AC.fecha_ingreso_laboral,'DD/MM/YYYY') AS fecha_ingreso_laboral, ";
      $sql .= "         TO_CHAR(CO.fecha_inicio_convenio,'DD/MM/YYYY') AS fecha_inicio_convenio, ";
      $sql .= "         TO_CHAR(CO.fecha_vencimiento_convenio,'DD/MM/YYYY') AS fecha_vencimiento_convenio, ";
      $sql .= "         TP.pais ||'-'||TD.departamento||'-'||TM.municipio AS departamento_municipio ";
      $sql .= "FROM     eps_afiliados EA, ";
      $sql .= "         eps_afiliados_cotizantes AC ";
      $sql .= "         LEFT JOIN eps_afiliados_cotizantes_convenios CO ";
      $sql .= "         ON  ( AC.eps_afiliacion_id = CO.eps_afiliacion_id AND ";
      $sql .= "               AC.afiliado_tipo_id = CO.afiliado_tipo_id AND ";
      $sql .= "               AC.afiliado_id = CO.afiliado_id ), ";
      $sql .= "         eps_afiliados_datos ED, ";
      $sql .= "         eps_afiliaciones AF, ";
      $sql .= "         tipo_pais TP,";
      $sql .= "         tipo_dptos TD,";
      $sql .= "         tipo_mpios TM ";
      $sql .= "WHERE    ED.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $sql .= "AND      ED.afiliado_id = EA.afiliado_id ";
      $sql .= "AND      EA.eps_afiliacion_id = AF.eps_afiliacion_id ";
      $sql .= "AND      EA.afiliado_tipo_id = '".$datos['afiliado_tipo_id']."' ";
      $sql .= "AND      EA.afiliado_id = '".$datos['afiliado_id']."' ";
      $sql .= "AND      AC.eps_afiliacion_id = EA.eps_afiliacion_id  ";
      $sql .= "AND      AC.afiliado_tipo_id = EA.afiliado_tipo_id  ";
      $sql .= "AND      AC.afiliado_id = EA.afiliado_id  ";
      $sql .= "AND      ED.tipo_pais_id = TP.tipo_pais_id ";
      $sql .= "AND      ED.tipo_dpto_id = TD.tipo_dpto_id ";
      $sql .= "AND      ED.tipo_mpio_id = TM.tipo_mpio_id ";
      $sql .= "AND      TD.tipo_pais_id = TP.tipo_pais_id ";
      $sql .= "AND      TM.tipo_pais_id = TD.tipo_pais_id ";
      $sql .= "AND      TM.tipo_dpto_id = TD.tipo_dpto_id ";
      if($datos['eps_afiliacion_id'])
        $sql .= "AND    EA.eps_afiliacion_id = ".$datos['eps_afiliacion_id']." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while (!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
    }
    /**
    * Funcion para obtener la informacion de la afiliacion de un afiliado beneficiario
    *
    * @param array $datos vector con los datos de docmento, tipo de documento y numero de afiliacion
    *
    * @return array 
    */
    function ObtenerDatosAfiliadoBeneficiario($datos)
    { 	 	
      $sql  = "SELECT   EA.eps_afiliacion_id, ";
      $sql .= "         EA.afiliado_tipo_id, ";
      $sql .= "         EA.afiliado_id, ";
      $sql .= "         EA.eps_anterior, ";
      $sql .= "         EA.semanas_cotizadas_eps_anterior, ";
      $sql .= "         EA.observaciones, ";
      $sql .= "         ED.primer_apellido,";
      $sql .= "         ED.segundo_apellido,  ";
      $sql .= "         ED.primer_nombre,";
      $sql .= "         ED.segundo_nombre, ";
      $sql .= "         ED.tipo_sexo_id, ";
      $sql .= "         ED.tipo_pais_id, ";
      $sql .= "         ED.tipo_dpto_id, ";
      $sql .= "         ED.tipo_mpio_id, ";
      $sql .= "         ED.direccion_residencia, ";
      $sql .= "         ED.zona_residencia, ";
      $sql .= "         ED.telefono_residencia, ";
      $sql .= "         ED.telefono_movil, ";
      $sql .= "         ED.ciuo_88_grupo_primario, ";
      $sql .= "         EA.eps_tipo_afiliado_id,";
      $sql .= "         EA.fecha_afiliacion,";
      $sql .= "         EA.fecha_afiliacion_eps_anterior,";
      $sql .= "         EA.semanas_cotizadas_eps_anterior,";
      $sql .= "         EA.observaciones,";
      $sql .= "         EA.eps_anterior,";
      $sql .= "         EA.plan_atencion, ";
      $sql .= "         EA.tipo_afiliado_atencion, ";
      $sql .= "         EA.rango_afiliado_atencion, ";
      $sql .= "         EA.eps_punto_atencion_id, ";
      $sql .= "         EB.parentesco_id, ";
      $sql .= "         EB.cotizante_tipo_id, ";
      $sql .= "         EB.cotizante_id, ";
      $sql .= "         EB.observaciones, ";
      $sql .= " 	      AF.eps_tipo_afiliacion_id, 	";
      $sql .= "         TO_CHAR(AF.fecha_recepcion,'DD/MM/YYYY') AS fecha_recepcion, ";
      $sql .= "         TO_CHAR(ED.fecha_afiliacion_sgss,'DD/MM/YYYY') AS fecha_afiliacion_sgss, ";
      $sql .= "         TO_CHAR(ED.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
      $sql .= "         TO_CHAR(EA.fecha_afiliacion,'DD/MM/YYYY') AS fecha_afiliacion, ";
      $sql .= "         TO_CHAR(EA.fecha_afiliacion_eps_anterior,'DD/MM/YYYY') AS fecha_afiliacion_eps_anterior, ";
      $sql .= "         TO_CHAR(EA.fecha_vencimiento,'DD/MM/YYYY') AS fecha_vencimiento, ";
      $sql .= "         TP.pais ||'-'||TD.departamento||'-'||TM.municipio AS departamento_municipio ";
      $sql .= "FROM     eps_afiliados EA, ";
      $sql .= "         eps_afiliados_beneficiarios EB, ";
      $sql .= "         eps_afiliados_datos ED, ";
      $sql .= "         eps_afiliaciones AF, ";
      $sql .= "         tipo_pais TP,";
      $sql .= "         tipo_dptos TD,";
      $sql .= "         tipo_mpios TM ";
      $sql .= "WHERE    ED.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $sql .= "AND      ED.afiliado_id = EA.afiliado_id ";
      $sql .= "AND      EA.eps_afiliacion_id = AF.eps_afiliacion_id ";
      $sql .= "AND      EA.afiliado_tipo_id = '".$datos['afiliado_tipo_id']."' ";
      $sql .= "AND      EA.afiliado_id = '".$datos['afiliado_id']."' ";
      $sql .= "AND      EB.eps_afiliacion_id = EA.eps_afiliacion_id  ";
      $sql .= "AND      EB.afiliado_tipo_id = EA.afiliado_tipo_id  ";
      $sql .= "AND      EB.afiliado_id = EA.afiliado_id  ";
      $sql .= "AND      ED.tipo_pais_id = TP.tipo_pais_id ";
      $sql .= "AND      ED.tipo_dpto_id = TD.tipo_dpto_id ";
      $sql .= "AND      ED.tipo_mpio_id = TM.tipo_mpio_id ";
      $sql .= "AND      TD.tipo_pais_id = TP.tipo_pais_id ";
      $sql .= "AND      TM.tipo_pais_id = TD.tipo_pais_id ";
      $sql .= "AND      TM.tipo_dpto_id = TD.tipo_dpto_id ";
      if($datos['eps_afiliacion_id'])
         $sql .= "AND    EA.eps_afiliacion_id = ".$datos['eps_afiliacion_id']." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while (!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
    }
    /**
    * Funcion para obtener la informacion de la afiliacion de un afiliado beneficiario
    *
    * @param array $datos vector con los datos de docmento, tipo de documento y numero de afiliacion
    *
    * @return array 
    */
    function ObtenerDatosAfiliadoBeneficiarioRetirado($datos)
    { 	 	
      $sql  = "SELECT   EA.afiliado_tipo_id AS tipo_id_beneficiario, ";
      $sql .= "         EA.afiliado_id AS documento, ";
      $sql .= "         EA.eps_anterior, ";
      $sql .= "         EA.semanas_cotizadas_eps_anterior, ";
      $sql .= "         EA.observaciones, ";
      $sql .= "         ED.primer_apellido AS primerapellido,";
      $sql .= "         ED.segundo_apellido AS segundoapellido,  ";
      $sql .= "         ED.primer_nombre AS primernombre,";
      $sql .= "         ED.segundo_nombre AS segundonombre, ";
      $sql .= "         ED.tipo_sexo_id AS tipo_sexo, ";
      $sql .= "         ED.tipo_pais_id AS pais, ";
      $sql .= "         ED.tipo_dpto_id AS dpto, ";
      $sql .= "         ED.tipo_mpio_id AS mpio, ";
      $sql .= "         ED.direccion_residencia, ";
      $sql .= "         ED.zona_residencia, ";
      $sql .= "         ED.telefono_residencia, ";
      $sql .= "         ED.telefono_movil, ";
      $sql .= "         EA.eps_tipo_afiliado_id,";
      $sql .= "         EA.fecha_afiliacion,";
      $sql .= "         EA.fecha_afiliacion_eps_anterior,";
      $sql .= "         EA.semanas_cotizadas_eps_anterior,";
      $sql .= "         EA.observaciones,";
      $sql .= "         EA.eps_anterior,";
      $sql .= "         EB.parentesco_id, ";
      $sql .= " 	      AF.eps_tipo_afiliacion_id, 	";
      $sql .= " 	      CU.ciuo_88_grupo_primario, 	";
      $sql .= " 	      CU.descripcion_ciuo_88_grupo_primario AS ocupacion_hd, 	";
      $sql .= "         TO_CHAR(AF.fecha_recepcion,'DD/MM/YYYY') AS fecha_recepcion, ";
      $sql .= "         TO_CHAR(ED.fecha_afiliacion_sgss,'DD/MM/YYYY') AS fecha_sgss, ";
      $sql .= "         TO_CHAR(ED.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
      $sql .= "         TO_CHAR(EA.fecha_afiliacion,'DD/MM/YYYY') AS fecha_afiliacion_empresa, ";
      $sql .= "         TO_CHAR(EA.fecha_afiliacion_eps_anterior,'DD/MM/YYYY') AS fecha_afiliacion, ";
      $sql .= "         TP.pais ||'-'||TD.departamento||'-'||TM.municipio AS ubicacion_hd ";
      $sql .= "FROM     eps_afiliados EA ,";
      $sql .= "         eps_afiliados_beneficiarios EB, ";
      $sql .= "         eps_afiliados_datos ED ";
      $sql .= "         LEFT JOIN ciuo_88_grupos_primarios CU ";
      $sql .= "         ON (ED.ciuo_88_grupo_primario = CU.ciuo_88_grupo_primario), ";
      $sql .= "         eps_afiliaciones AF, ";
      $sql .= "         tipo_pais TP,";
      $sql .= "         tipo_dptos TD,";
      $sql .= "         tipo_mpios TM ";
      $sql .= "WHERE    ED.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $sql .= "AND      ED.afiliado_id = EA.afiliado_id ";
      $sql .= "AND      EA.eps_afiliacion_id = AF.eps_afiliacion_id ";
      $sql .= "AND      EA.afiliado_tipo_id = '".$datos['afiliado_tipo_id']."' ";
      $sql .= "AND      EA.afiliado_id = '".$datos['afiliado_id']."' ";
      $sql .= "AND      EB.eps_afiliacion_id = EA.eps_afiliacion_id  ";
      $sql .= "AND      EB.afiliado_tipo_id = EA.afiliado_tipo_id  ";
      $sql .= "AND      EB.afiliado_id = EA.afiliado_id  ";
      $sql .= "AND      ED.tipo_pais_id = TP.tipo_pais_id ";
      $sql .= "AND      ED.tipo_dpto_id = TD.tipo_dpto_id ";
      $sql .= "AND      ED.tipo_mpio_id = TM.tipo_mpio_id ";
      $sql .= "AND      TD.tipo_pais_id = TP.tipo_pais_id ";
      $sql .= "AND      TM.tipo_pais_id = TD.tipo_pais_id ";
      $sql .= "AND      TM.tipo_dpto_id = TD.tipo_dpto_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while (!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
    }
    /**
    * Funcion donde se actualizan los datos de un afiliado cotizante
    *
    * @param array $datos Arreglo, que contiene los datos de la afiliacion y el cotizante
    *
    * @return boolean
    */
    function ActualizarDatosAfiliacionCotizante($datos)
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
      $sql .= "WHERE  afiliado_tipo_id = '".$datos['afiliado_tipo_id']."'  ";
      $sql .= "AND    afiliado_id = '".$datos['afiliado_id']."'; ";

      $this->ConexionTransaccion();
      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      $sql  = "UPDATE eps_afiliaciones ";
      $sql .= "SET    eps_tipo_afiliacion_id = '".$datos['tipo_afiliacion']."'   , ";
      $sql .= "       fecha_recepcion  = '".$f5[2]."-".$f5[1]."-".$f5[0]."' , ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID()." , ";
      $sql .= "       fecha_ultima_actualizacion =  NOW() ";
      $sql .= "WHERE  eps_afiliacion_id = '".$datos['eps_afiliacion_id']."'; ";

      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      if(!$datos['semanas_cotizadas']) $datos['semanas_cotizadas'] = 0;

      $sql  = "UPDATE eps_afiliados ";
      $sql .= "SET    eps_tipo_afiliado_id = '".$datos['tipo_afiliado']."' , ";
      $sql .= "       fecha_afiliacion = '".$f3[2]."-".$f3[1]."-".$f3[0]."' , ";
      $sql .= "       eps_anterior = ".$datos['eps_anterior']." , ";
      $sql .= "       fecha_afiliacion_eps_anterior = ".$fecha_anterior." , ";
      $sql .= "       semanas_cotizadas_eps_anterior = ".$datos['semanas_cotizadas']." , ";
      $sql .= "       observaciones = '".$datos['observaciones']."'    , ";
      $sql .= "       plan_atencion = ".$datos['plan_atencion'].", ";
      $sql .= "       tipo_afiliado_atencion = '".$datos['tipo_afiliado_plan']."', ";
      $sql .= "       rango_afiliado_atencion = '".$datos['rango_afiliado_plan']."', ";
      $sql .= "       eps_punto_atencion_id = '".$datos['puntos_atencion']."', ";
      $sql .= "       fecha_vencimiento = ".$fecha_vencimiento.", ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID()." , ";
      $sql .= "       fecha_ultima_actualizacion =  NOW() ";
      $sql .= "WHERE  eps_afiliacion_id = '".$datos['eps_afiliacion_id']."' ";
      $sql .= "AND    afiliado_tipo_id = '".$datos['afiliado_tipo_id']."'  ";
      $sql .= "AND    afiliado_id = '".$datos['afiliado_id']."'; ";

      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      $afp = $datos['administradora_pensiones'];
      ($afp == "-1")? $afp = "NULL":$afp = "'".$afp."'";

      $ing_mensual = $datos['salario_base'];
      if($datos['ingreso_mensual']) $ing_mensual = $datos['ingreso_mensual'];

      if(!$ing_mensual) $ing_mensual = 0;

      $fecha_ingreso_laboral = "NULL";
      if($datos['fecha_ingreso_empleo']) $fecha_ingreso_laboral = "'".$f6[2]."-".$f6[1]."-".$f6[0]."'";
      
      if($datos['parentesco'] != '-1') 
        $datos['parentesco'] = "'".$datos['parentesco']."'";
      else
        $datos['parentesco'] = "NULL";
      
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

      
      $sql  = "UPDATE   eps_afiliados_cotizantes ";
      $sql .= "SET      ciiu_r3_division = ".$datos['division_actividad'].",";
      $sql .= "         ciiu_r3_grupo = ".$datos['grupo_actividad'].",";
      $sql .= "         ciiu_r3_clase = ".$datos['clase_actividad'].",";
      $sql .= "         telefono_dependencia = '".$datos['telefono_dependencia']."' ,";
      $sql .= "         estrato_socioeconomico_id = ".$datos['estrato_socioeconomico'].",";
      $sql .= "         tipo_estado_civil_id = '".$datos['estado_civil']."',";
      $sql .= "         tipo_aportante_id = '".$datos['tipo_aportante']."',";
      $sql .= "         estamento_id = '".$datos['estamento']."',";
      $sql .= "         parentesco_id = ".$datos['parentesco'].",";
      $sql .= "         codigo_afp = ".$afp.",";
      $sql .= "         ingreso_mensual = ".$ing_mensual.",";
      $sql .= "         fecha_ingreso_laboral = ".$fecha_ingreso_laboral." ,";
      $sql .= "         codigo_dependencia_id = '".$datos['dependencia_laboral']."',";
      $sql .= "         usuario_ultima_actualizacion = ".UserGetUID().",";
      $sql .= "         fecha_ultima_actualizacion =  NOW() ";
      $sql .= "WHERE    eps_afiliacion_id = '".$datos['eps_afiliacion_id']."' ";
      $sql .= "AND      afiliado_tipo_id = '".$datos['afiliado_tipo_id']."'  ";
      $sql .= "AND      afiliado_id = '".$datos['afiliado_id']."'; ";

      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      $estament = $this->ObtenerEstamentos($datos['estamento']);
      
      if($estament[$datos['estamento']]['estamento_siis'] == "V")
      {
        $tercero = array();
        list($tercero_tipo_id,$tercero_id) = explode(" ",$datos['empresa_convenio']);

        $fi = explode("/",$datos['fecha_inicio_convenio']);
        $ff = explode("/",$datos['fecha_fin_convenio']);

        $sql  = "UPDATE   eps_afiliados_cotizantes_convenios ";
        $sql .= "SET      convenio_tipo_id_tercero = '".$tercero_tipo_id."',";
        $sql .= "         convenio_tercero_id = '".$tercero_id."',";
        $sql .= "         usuario_ultima_actualizacion = ".UserGetUID().",";
        $sql .= "         fecha_ultima_actualizacion =  NOW() ";
        $sql .= "WHERE    eps_afiliacion_id = '".$datos['eps_afiliacion_id']."' ";
        $sql .= "AND      afiliado_tipo_id = '".$datos['afiliado_tipo_id']."'  ";
        $sql .= "AND      afiliado_id = '".$datos['afiliado_id']."'; ";

        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      
      if($datos['tipo_id_cotizante'] != $datos['afiliado_tipo_id'] || $datos['documento'] != $datos['afiliado_id'])
      {
        $sql  = "UPDATE eps_afiliados_datos ";
        $sql .= "SET    afiliado_tipo_id = '".$datos['tipo_id_cotizante']."' , ";
        $sql .= "       afiliado_id = '".$datos['documento']."' ";
        $sql .= "WHERE  afiliado_tipo_id = '".$datos['afiliado_tipo_id']."'  ";
        $sql .= "AND    afiliado_id = '".$datos['afiliado_id']."'; ";
      
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      $this->dbconn->CommitTrans();

      return true;
    }
    /**
    * Funcion donde se actualizan los datos de un afiliado beneficiario
    *
    * @param array $datos Arreglo, que contiene los datos de la afiliacion y el cotizante
    *
    * @return boolean
    */
    function ActualizarDatosAfiliacionBeneficiario($datos)
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

      $grupo_primario = "NULL";
      if($datos['grupos_primarios'] != '-1') $grupo_primario = "'".$datos['grupos_primarios']."'";

      $fecha_vencimiento = "NULL";
      if($datos['fecha_vencimiento'])
      {
        $f2 = explode("/",$datos['fecha_vencimiento']);
        $fecha_vencimiento = "'".$f2[2]."-".$f2[1]."-".$f2[0]."'";
      }
      
      $sql  = "UPDATE eps_afiliados_datos ";
      $sql .= "SET    primer_apellido =   '".strtoupper(str_replace("'","''",$datos['primerapellido']))."', ";
      $sql .= "       segundo_apellido = '".strtoupper(str_replace("'","''",$datos['segundoapellido']))."', ";
      $sql .= "       primer_nombre =   '".strtoupper(str_replace("'","''",$datos['primernombre']))."', ";
      $sql .= "       segundo_nombre = '".strtoupper(str_replace("'","''",$datos['segundonombre']))."', ";
      $sql .= "       fecha_nacimiento = '".$f1[2]."-".$f1[1]."-".$f1[0]."', ";
      $sql .= "       fecha_afiliacion_sgss =  ".$fecha_sgss.", ";
      $sql .= "       tipo_sexo_id = '".$datos['tipo_sexo']."', ";
      $sql .= "       ciuo_88_grupo_primario = ".$grupo_primario.", ";
      $sql .= "       tipo_pais_id = '".$datos['pais']."', ";
      $sql .= "       tipo_dpto_id = '".$datos['dpto']."', ";
      $sql .= "       tipo_mpio_id = '".$datos['mpio']."', ";
      $sql .= "       zona_residencia = '".$datos['zona_residencia']."', ";
      $sql .= "       direccion_residencia = '".$datos['direccion_residencia']."', ";
      $sql .= "       telefono_residencia = '".$datos['telefono_residencia']."', ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().",   ";
      $sql .= "       fecha_ultima_actualizacion = NOW()     ";
      $sql .= "WHERE  afiliado_tipo_id = '".$datos['afiliado_tipo_id']."'  ";
      $sql .= "AND    afiliado_id = '".$datos['afiliado_id']."'; ";

      $this->ConexionTransaccion();
      if(!$rst = $this->ConexionTransaccion($sql)) return false;

      if(!$datos['semanas_cotizadas']) $datos['semanas_cotizadas'] = 0;

      $sql  = "UPDATE eps_afiliados ";
      $sql .= "SET    fecha_afiliacion = '".$f3[2]."-".$f3[1]."-".$f3[0]."' , ";
      $sql .= "       eps_anterior = ".$datos['eps_anterior']." , ";
      $sql .= "       fecha_afiliacion_eps_anterior = ".$fecha_anterior." , ";
      $sql .= "       semanas_cotizadas_eps_anterior = ".$datos['semanas_cotizadas']." , ";
      $sql .= "       plan_atencion = ".$datos['plan_atencion'].", ";
      $sql .= "       tipo_afiliado_atencion = '".$datos['tipo_afiliado_plan']."', ";
      $sql .= "       rango_afiliado_atencion = '".$datos['rango_afiliado_plan']."', ";
      $sql .= "       eps_punto_atencion_id = '".$datos['puntos_atencion']."', ";
      $sql .= "       fecha_vencimiento = ".$fecha_vencimiento.", ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID()." , ";
      $sql .= "       fecha_ultima_actualizacion =  NOW() ";
      $sql .= "WHERE  eps_afiliacion_id = '".$datos['eps_afiliacion_id']."' ";
      $sql .= "AND    afiliado_tipo_id = '".$datos['afiliado_tipo_id']."'  ";
      $sql .= "AND    afiliado_id = '".$datos['afiliado_id']."'; ";

      if(!$rst = $this->ConexionTransaccion($sql)) return false;
        
      $sql  = "UPDATE eps_afiliados_beneficiarios  ";
      $sql .= "SET    parentesco_id  = '".$datos['parentesco']."'   ,";
      $sql .= "       observaciones = '".$datos['observaciones']."', ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().", ";
      $sql .= "       fecha_ultima_actualizacion = NOW() ";
      $sql .= "WHERE  eps_afiliacion_id = '".$datos['eps_afiliacion_id']."' ";
      $sql .= "AND    afiliado_tipo_id = '".$datos['afiliado_tipo_id']."'  ";
      $sql .= "AND    afiliado_id = '".$datos['afiliado_id']."'; ";

      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      if($datos['tipo_id_beneficiario'] != $datos['afiliado_tipo_id'] || $datos['documento'] != $datos['afiliado_id'])
      {
        $sql  = "UPDATE eps_afiliados_datos ";
        $sql .= "SET    afiliado_tipo_id = '".$datos['tipo_id_beneficiario']."' , ";
        $sql .= "       afiliado_id = '".$datos['documento']."' ";
        $sql .= "WHERE  afiliado_tipo_id = '".$datos['afiliado_tipo_id']."'  ";
        $sql .= "AND    afiliado_id = '".$datos['afiliado_id']."'; ";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      
      $this->dbconn->CommitTrans();

      return true;
    }
    /**
    * Funcion para obtener la lista de afiliados existenters en el sistema
    *
    * @param array $datos vector con los parametros de busqueda
    * @param int $pg_siguiente indica el numero de la pagina que se esta visualizando
    *
    * @return array 
    */
    function ObtenerMaestroAfiliados($datos)
    {
      $sql  = "SELECT   '".$datos['codigo_sgsss']."' AS codigo_entidad, ";
      $sql .= "         EC.cotizante_tipo_id, ";
      $sql .= "         EC.cotizante_id, ";      
      $sql .= "         EA.afiliado_tipo_id, ";
      $sql .= "         EA.afiliado_id, ";
      $sql .= "         ED.primer_apellido ,";
      $sql .= "         ED.segundo_apellido ,  ";
      $sql .= "         ED.primer_nombre ,";
      $sql .= "         ED.segundo_nombre , ";
      $sql .= "         TO_CHAR(ED.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
      $sql .= "         ED.tipo_sexo_id, ";
      $sql .= "         EA.eps_tipo_afiliado_id, ";
      $sql .= "         EC.parentesco_id, ";
      /*$sql .= "         ED.tipo_dpto_id, ";
      $sql .= "         ED.tipo_mpio_id, ";*/
      $sql .= "         SUBSTRING(EA.eps_punto_atencion_id FROM 1 FOR 2) AS tipo_dpto_id, ";
      $sql .= "         SUBSTRING(EA.eps_punto_atencion_id FROM 3 FOR 5) AS tipo_dpto_id, ";
      $sql .= "         ED.zona_residencia, ";
      $sql .= "         TO_CHAR(ED.fecha_afiliacion_sgss,'DD/MM/YYYY') AS fecha_afiliacion_sgss, ";
      $sql .= "         EC.pensiones_tipo_id, ";
      $sql .= "         EC.pensiones_id, ";
      $sql .= "         EC.fecha_ingreso_laboral, ";
      $sql .= "         EC.actividad_economica ";
      $sql .= "FROM     eps_afiliados EA, ";
      $sql .= "         (";
      $sql .= "           SELECT EB.cotizante_tipo_id, ";
      $sql .= "              EB.cotizante_id, ";      
      $sql .= "              EB.afiliado_id, ";
      $sql .= "              EB.afiliado_tipo_id, ";
      $sql .= "              EB.eps_afiliacion_id, ";
      $sql .= "              EP.parentesco_res_812 AS parentesco_id, ";
      $sql .= "              '' AS pensiones_tipo_id, ";
      $sql .= "              '' AS pensiones_id, ";
      $sql .= "              '' AS fecha_ingreso_laboral, ";
      $sql .= "              '' AS actividad_economica ";
      $sql .= "           FROM   eps_afiliados_beneficiarios EB,"; 
      $sql .= "              eps_parentescos_beneficiarios EP, " ;
      $sql .= "              eps_afiliados_cotizantes EC, " ;
      $sql .= "              eps_estamentos EE " ;
      $sql .= "           WHERE  EB.cotizante_tipo_id = EC.afiliado_tipo_id";
      $sql .= "           AND    EB.cotizante_id = EC.afiliado_id ";
      $sql .= "           AND    EB.eps_afiliacion_id = EC.eps_afiliacion_id ";
      $sql .= "           AND    EC.estamento_id = EE.estamento_id ";
      $sql .= "           AND    EP.parentesco_id = EB.parentesco_id ";
      $sql .= "           AND    COALESCE(EE.estamento_siis,'1') != 'V' ";
      $sql .= "           UNION ALL ";
      $sql .= "           SELECT '' AS cotizante_tipo_id, ";
      $sql .= "              '' AS cotizante_id, ";
      $sql .= "              EC.afiliado_id, ";
      $sql .= "              EC.afiliado_tipo_id,";
      $sql .= "              EC.eps_afiliacion_id,";
      $sql .= "              '' AS parentesco_id, ";
      $sql .= "              CASE WHEN EC.codigo_afp IS NOT NULL THEN AF.tipo_id_tercero  ";
      $sql .= "                   ELSE '".$datos['tipo_id_tercero']."' END AS pensiones_tipo_id, ";
      $sql .= "              CASE WHEN EC.codigo_afp IS NOT NULL THEN AF.tercero_id ";
      $sql .= "                   ELSE '".$datos['id']."' END AS pensiones_id, ";
      $sql .= "              TO_CHAR(EC.fecha_ingreso_laboral,'DD/MM/YYYY') AS fecha_ingreso_laboral, ";
      $sql .= "              CASE WHEN EC.codigo_afp IS NULL THEN EC.ciiu_r3_clase ";
      $sql .= "                   ELSE '' END AS actividad_economica ";
      $sql .= "           FROM   eps_afiliados_cotizantes EC " ;
      $sql .= "              LEFT JOIN administradoras_de_fondos_de_pensiones AF  ";
      $sql .= "              ON (  EC.codigo_afp = AF.codigo_afp  ), ";
      $sql .= "              eps_estamentos EE " ;
      $sql .= "           WHERE  EC.estamento_id = EE.estamento_id ";
      $sql .= "           AND    COALESCE(EE.estamento_siis,'1') != 'V' ";
      $sql .= "         ) AS EC, ";
      $sql .= "         eps_afiliados_datos ED ";
      $sql .= "WHERE    ED.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $sql .= "AND      ED.afiliado_id = EA.afiliado_id ";            
      $sql .= "AND      EC.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $sql .= "AND      EC.afiliado_id = EA.afiliado_id ";      
      $sql .= "AND      EC.eps_afiliacion_id = EA.eps_afiliacion_id  ";
      $sql .= "AND      EA.estado_afiliado_id = 'AC' ";      
      $sql .= "AND      ED.fecha_afiliacion_sgss::date <= '".$this->DividirFecha($datos['fecha_final'])."' ";
      $sql .= "AND      ED.fecha_afiliacion_sgss::date >= '".$this->DividirFecha($datos['fecha_inicio'])."' ";
      if($datos['plan'])
      {
        $query = "";
        foreach($datos['plan'] as $k)
          ($query == "" )? $query .= $k: $query .= ",".$k;
          
        $sql .= "AND    EA.plan_atencion IN (".$query.") ";
      }
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      return $rst;
    }
    /**
    * Funcion donde se obtiene la informacion necesaria para armar el archivo de
    * maestro de aportantes
    *
    * @param array $datos Vector de datos, con los filtros.
    *
    * @return object
    */
    function ObtenerAportantes($datos)
    {
      $sql .= "SELECT DISTINCT *  ";
      $sql .= "FROM   (";
      $sql .= "         SELECT CASE WHEN EC.codigo_afp IS NOT NULL THEN AF.tipo_id_tercero  ";
      $sql .= "                   ELSE '".$datos['tipo_id_tercero']."' END AS pensiones_tipo_id, ";
      $sql .= "                CASE WHEN EC.codigo_afp IS NOT NULL THEN AF.tercero_id ";
      $sql .= "                   ELSE '".$datos['id']."' END AS pensiones_id, ";
      $sql .= "                CASE WHEN EC.codigo_afp IS NOT NULL THEN AF.digito_verificacion ";
      $sql .= "                   ELSE '".$datos['digito_verificacion']."' END AS digito_verificacion, ";
      $sql .= "                CASE WHEN EC.codigo_afp IS NOT NULL THEN AF.razon_social_afp ";
      $sql .= "                   ELSE '".$datos['razon_social']."' END AS razon_social, ";      
      $sql .= "                CASE WHEN EC.codigo_afp IS NOT NULL THEN AF.esp_tipo_aportante_id ";
      $sql .= "                   ELSE '".$datos['esp_tipo_aportante_id']."' END AS esp_tipo_aportante_id, ";      
      $sql .= "                CASE WHEN EC.codigo_afp IS NOT NULL THEN AF.esp_sector_aportante_id ";
      $sql .= "                   ELSE '".$datos['esp_sector_aportante_id']."' END AS esp_sector_aportante_id, ";      
      $sql .= "                CASE WHEN EC.codigo_afp IS NOT NULL THEN AF.ciiu_r3_clase ";
      $sql .= "                   ELSE '".$datos['ciiu_r3_clase']."' END AS ciiu_r3_clase ";      
      $sql .= "           FROM    eps_afiliados_cotizantes EC " ;
      $sql .= "                   LEFT JOIN administradoras_de_fondos_de_pensiones AF  ";
      $sql .= "                   ON (  EC.codigo_afp = AF.codigo_afp  ), ";
      $sql .= "                   eps_estamentos EE, " ;
      $sql .= "                   eps_afiliados ED ";
      $sql .= "           WHERE   EC.estamento_id = EE.estamento_id ";
      $sql .= "           AND     COALESCE(EE.estamento_siis,'1') != 'V' ";
      $sql .= "           AND     ED.afiliado_tipo_id = EC.afiliado_tipo_id ";
      $sql .= "           AND     ED.afiliado_id = EC.afiliado_id ";            
      $sql .= "           AND     ED.estado_afiliado_id = 'AC' ";            
      $sql .= "           AND     ED.afiliado_tipo_id = EC.afiliado_tipo_id ";
      $sql .= "           AND     ED.fecha_afiliacion::date <= '".$this->DividirFecha($datos['fecha_final'])."' ";
      $sql .= "           AND     ED.fecha_afiliacion::date >= '".$this->DividirFecha($datos['fecha_inicio'])."' ";
      if($datos['plan'])
      {
        $query = "";
        foreach($datos['plan'] as $k)
          ($query == "" )? $query .= $k: $query .= ",".$k;
          
        $sql .= "           AND    ED.plan_atencion IN (".$query.") ";
      }
      echo $sql .= "         ) AS EC ";

      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      return $rst;
    }
    /**
    * Funcion donde se obtiene la informacion de los cotizantes, para
    * hacer la creacion de un archivo plano
    *
    * @param array $datos vector con los parametros de busqueda
    *
    * @return array 
    */
    function ObtenerCotizantes($datos)
    {
      $sql  = "SELECT   ED.afiliado_tipo_id, ";
      $sql .= "         ED.afiliado_id, ";      
      $sql .= "         ED.primer_nombre ,";
      $sql .= "         ED.segundo_nombre , ";
      $sql .= "         ED.primer_apellido ,";
      $sql .= "         ED.segundo_apellido ,  ";
      $sql .= "         TO_CHAR(EA.fecha_afiliacion,'DD/MM/YYYY') AS fecha_afiliacion, ";
      $sql .= "         EE.descripcion_estamento , ";
      $sql .= "         TO_CHAR(ED.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento ";
      $sql .= "FROM     eps_afiliados EA, ";
      $sql .= "         eps_afiliados_datos ED, ";
      $sql .= "         eps_afiliados_cotizantes EC, ";
      $sql .= "         eps_estamentos EE " ;
      $sql .= "WHERE    ED.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $sql .= "AND      ED.afiliado_id = EA.afiliado_id ";            
      $sql .= "AND      EC.eps_afiliacion_id = EA.eps_afiliacion_id "; 
      $sql .= "AND      EC.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $sql .= "AND      EC.afiliado_id = EA.afiliado_id "; 
      $sql .= "AND      EC.estamento_id = EE.estamento_id ";

      if($datos['fecha_final'])
        $sql .= "           AND     EA.fecha_afiliacion::date <= '".$this->DividirFecha($datos['fecha_final'])."' ";
      
      if($datos['fecha_inicio'])
        $sql .= "           AND     EA.fecha_afiliacion::date >= '".$this->DividirFecha($datos['fecha_inicio'])."' ";

      $sql .= "ORDER BY EA.fecha_afiliacion DESC ";
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      return $rst;
    }
    /**
    * Funcion donde se obtiene la informacion de los beneficiarios, para
    * hacer la creacion de un archivo plano
    *
    * @param array $datos vector con los parametros de busqueda
    *
    * @return array 
    */
    function ObtenerBeneficiarios($datos)
    {
      $sql  = "SELECT   EB.cotizante_tipo_id, ";
      $sql .= "         EB.cotizante_id, ";      
      $sql .= "         EB.afiliado_tipo_id, "; 
      $sql .= "         EB.afiliado_id, ";
      $sql .= "         ED.primer_nombre ,";
      $sql .= "         ED.segundo_nombre , ";
      $sql .= "         ED.primer_apellido ,";
      $sql .= "         ED.segundo_apellido ,  ";
      $sql .= "         TO_CHAR(EA.fecha_afiliacion,'DD/MM/YYYY') AS fecha_afiliacion, ";
      $sql .= "         EE.descripcion_estamento , ";
      $sql .= "         EP.descripcion_parentesco , ";
      $sql .= "         TO_CHAR(ED.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento ";
      $sql .= "FROM     eps_afiliados EA, ";
      $sql .= "         eps_afiliados_datos ED, ";
      $sql .= "         eps_afiliados_beneficiarios EB,"; 
      $sql .= "         eps_parentescos_beneficiarios EP, " ;
      $sql .= "         eps_afiliados_cotizantes EC, " ;
      $sql .= "         eps_estamentos EE " ;
      $sql .= "WHERE    EA.afiliado_tipo_id = ED.afiliado_tipo_id ";
      $sql .= "AND      EA.afiliado_id = ED.afiliado_id ";            
      $sql .= "AND      EB.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $sql .= "AND      EB.afiliado_id = EA.afiliado_id ";      
      $sql .= "AND      EB.eps_afiliacion_id = EA.eps_afiliacion_id ";          
      $sql .= "AND      EB.cotizante_tipo_id = EC.afiliado_tipo_id ";
      $sql .= "AND      EB.cotizante_id = EC.afiliado_id ";
      $sql .= "AND      EB.eps_afiliacion_id = EC.eps_afiliacion_id ";
      $sql .= "AND      EP.parentesco_id = EB.parentesco_id ";
      $sql .= "AND      EC.estamento_id = EE.estamento_id ";
       
      if($datos['fecha_final'])
        $sql .= "           AND     EA.fecha_afiliacion::date <= '".$this->DividirFecha($datos['fecha_final'])."' ";
      
      if($datos['fecha_inicio'])
        $sql .= "           AND     EA.fecha_afiliacion::date >= '".$this->DividirFecha($datos['fecha_inicio'])."' ";

      $sql .= "ORDER BY EA.fecha_afiliacion DESC ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      return $rst;
    }
    /**
    * Funcion donde se obtiene la informacion de los cotizantes, para
    * hacer la creacion de un archivo plano
    *
    * @param array $datos vector con los parametros de busqueda
    *
    * @return array 
    */
    function ObtenerPacientes($datos)
    {
      $sql .= "SELECT * ";
      $sql .= "FROM   ( SELECT  ED.afiliado_tipo_id, ";
      $sql .= "                 ED.afiliado_id, ";      
      $sql .= "                 TO_CHAR(EA.fecha_afiliacion,'DD/MM/YYYY') AS fecha_afiliacion, ";
      $sql .= "                 CASE WHEN ED.primer_apellido = PA.primer_apellido THEN 0";
      $sql .= "                   ELSE 1 END AS apellido1_dif,";
      $sql .= "                 CASE WHEN ED.segundo_apellido = PA.segundo_apellido THEN 0";
      $sql .= "                   ELSE 1 END AS apellido2_dif,";
      $sql .= "                 CASE WHEN ED.primer_nombre = PA.primer_nombre THEN 0";
      $sql .= "                   ELSE 1 END AS nombre1_dif,";      
      $sql .= "                 CASE WHEN ED.segundo_nombre = PA.segundo_nombre THEN 0";
      $sql .= "                   ELSE 1 END AS nombre2_dif,";      
      $sql .= "                 CASE WHEN ED.direccion_residencia = PA.residencia_direccion THEN 0";
      $sql .= "                   ELSE 1 END AS direccion_dif,";
      $sql .= "                 CASE WHEN ED.telefono_residencia = PA.residencia_telefono THEN 0";
      $sql .= "                   ELSE 1 END AS telefono_dif,";
      $sql .= "                 CASE WHEN ED.zona_residencia = PA.zona_residencia THEN 0 ";
      $sql .= "                   ELSE 1 END AS zona_dif,";
      $sql .= "                 CASE WHEN ED.tipo_sexo_id = PA.sexo_id THEN 0";
      $sql .= "                   ELSE 1 END AS sexo_dif,";
      $sql .= "                 CASE WHEN ED.tipo_dpto_id = PA.tipo_dpto_id THEN 0";
      $sql .= "                   ELSE 1 END AS dpto_dif,";
      $sql .= "                 CASE WHEN ED.tipo_mpio_id = PA.tipo_mpio_id THEN 0";
      $sql .= "                   ELSE 1 END AS mpio_dif,";      
      $sql .= "                 CASE WHEN ED.fecha_nacimiento = PA.fecha_nacimiento THEN 0";
      $sql .= "                   ELSE 1 END AS nacimieneto_dif,";
      $sql .= "                 ED.primer_nombre AS primer_nombre_afiliacion,";
      $sql .= "                 ED.segundo_nombre AS segundo_apellido_afiliacion, ";
      $sql .= "                 ED.primer_apellido AS primer_apellido_afiliacion,  ";
      $sql .= "                 ED.segundo_apellido AS segundo_apellido_afiliacion ,  ";
      $sql .= "                 PA.primer_nombre AS primer_nombre_atencion,";
      $sql .= "                 PA.segundo_nombre AS segundo_apellido_atencion, ";
      $sql .= "                 PA.primer_apellido AS primer_apellido_atencion,  ";
      $sql .= "                 PA.segundo_apellido AS segundo_apellido_atencion ,  ";
      $sql .= "                 TO_CHAR(ED.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento_afiliacion, ";
      $sql .= "                 TO_CHAR(PA.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento_atencion, ";
      $sql .= "                 ED.direccion_residencia AS direccion_residencia_afiliacion,";
      $sql .= "                 PA.residencia_direccion AS direccion_residencia_atencion, ";
      $sql .= "                 ED.telefono_residencia AS telefono_residencia_afiliacion,";
      $sql .= "                 PA.residencia_telefono AS telefono_residencia_atencion, ";
      $sql .= "                 ED.zona_residencia AS zona_residencia_afiliacion,";
      $sql .= "                 PA.zona_residencia AS zona_residencia_atencion, ";
      $sql .= "                 ED.tipo_sexo_id AS sexo_afiliacion,";
      $sql .= "                 PA.sexo_id AS sexo_atencion, ";
      $sql .= "                 ED.tipo_mpio_id AS mpio_afiliacion,";
      $sql .= "                 PA.tipo_mpio_id AS mpio_atencion, ";
      $sql .= "                 ED.tipo_dpto_id AS dpto_afiliacion,";
      $sql .= "                 PA.tipo_dpto_id AS dpto_atencion ";
      $sql .= "         FROM    eps_afiliados EA, ";
      $sql .= "                 eps_afiliados_datos ED, ";
      $sql .= "                 pacientes PA ";
      $sql .= "         WHERE   ED.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $sql .= "         AND     ED.afiliado_id = EA.afiliado_id ";
      $sql .= "         AND     EA.afiliado_tipo_id = PA.tipo_id_paciente ";
      $sql .= "         AND     EA.afiliado_id = PA.paciente_id ";
      $sql .= "         AND     EA.estado_afiliado_id != 'RE' ";
      if($datos['fecha_final'])
        $sql .= "           AND     EA.fecha_afiliacion::date <= '".$this->DividirFecha($datos['fecha_final'])."' ";
      
      if($datos['fecha_inicio'])
        $sql .= "           AND     EA.fecha_afiliacion::date >= '".$this->DividirFecha($datos['fecha_inicio'])."' ";

      $sql .= "       )AS A ";
      $sql .= "WHERE  (apellido1_dif+apellido2_dif+nombre1_dif+nombre2_dif+";
      $sql .= "direccion_dif+telefono_dif+zona_dif+sexo_dif+dpto_dif+mpio_dif+nacimieneto_dif) > 0 ";
  
      $sql .= "ORDER BY fecha_afiliacion DESC ";
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      return $rst;
    }
  }
?>