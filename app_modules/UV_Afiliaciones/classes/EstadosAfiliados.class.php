<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: EstadosAfiliados.class.php,v 1.3 2009/09/30 12:52:13 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sEA.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : EstadosAfiliados
  * Clase donde se crean las consultas necesarias para conocer los estados de los
  * afiliados y la respectiva actualizacion de los mismos
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sEA.com)
  * @author Hugo F  Manrique
  */
  IncludeClass("Afiliaciones", "", "app","UV_Afiliaciones");
  class EstadosAfiliados extends Afiliaciones
  {
    /**
    * Contructor de la clase
    */
    function EstadosAfiliados(){}
    /**
    * Funcion donde se obtiene el flujo de estados asocioados a un estado 
    * en particular
    *
    * @param string $estado_origen identificador del estado origen
    *
    * @return array 
    */
    function ObtenerEstados($estado_origen)
    {
      $sql  = "SELECT  AE.estado_afiliado_id,";
      $sql .= "        AE.descripcion_estado, ";
      $sql .= "        AE.mensaje_confirmar_afiliacion ";
      $sql .= "FROM    eps_afiliados_estados AE,";
      $sql .= "        eps_afiliados_estados_flujos EF ";
      $sql .= "WHERE   EF.estado_afiliado_destino_id = AE.estado_afiliado_id ";
      $sql .= "AND     EF.estado_afiliado_id = '".$estado_origen."' ";
      $sql .= "ORDER BY AE.descripcion_estado ";
      
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
    * Funcion donde se obtienen los subestados asociados a un estado
    * especifico
    *
    * @param string $estado identificador del estado
    *
    * @return array 
    */
    function ObtenerSubEstados($estado)
    {
      $sql  = "SELECT  subestado_afiliado_id,";
      $sql .= "        descripcion_subestado ";
      $sql .= "FROM    eps_afiliados_subestados ";
      $sql .= "WHERE   estado_afiliado_id = '".$estado."' ";
      $sql .= "ORDER BY descripcion_subestado ";
      
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
    * Funcion donde se obtienen los estados y subestados a los que pasaran
    * los beneficiarios    
    *
    * @param array $datos Vector con los datos de estados y subestados nuevos
    *
    * @return array 
    */
    function ObtenerEstadosFlujos($datos)
    {
      $sql  = "SELECT  estado_afiliado_id_beneficiario,";
      $sql .= "        subestado_afiliado_id_beneficiario ";
      $sql .= "FROM    eps_afiliados_estados_flujos_cotizante ";
      $sql .= "WHERE   estado_afiliado_id = '".$datos['estado_afiliado_id']."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      if (!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se obtienen el estado y el subestado actual de un afiliado
    *
    * @param array $datos Vector con la informacion del tipo e identificacion
    *               del afiliado
    *
    * @return array
    */
    function ObtenerEstadosAfiliado($datos)
    {
        $sql  = "SELECT AF.eps_afiliacion_id,";
        $sql .= "       AD.afiliado_tipo_id, ";
        $sql .= "       AD.afiliado_id, ";
        $sql .= "       AD.primer_apellido||' '||AD.segundo_apellido AS apellidos, ";
        $sql .= "       AD.primer_nombre ||' '||AD.segundo_nombre AS nombres, ";
        $sql .= "       AF.estado_afiliado_id, ";
        $sql .= "       AF.subestado_afiliado_id, ";
        $sql .= "       AU.descripcion_subestado, ";
        $sql .= "       AE.descripcion_estado ";
        $sql .= "FROM   eps_afiliados_datos AD,";
        $sql .= "       eps_afiliados AF,";
        $sql .= "       eps_afiliados_estados AE,";
        $sql .= "       eps_afiliados_subestados AU ";
        $sql .= "WHERE  AD.afiliado_tipo_id = '".$datos['afiliado_tipo_id']."' ";
        $sql .= "AND    AD.afiliado_id = '".$datos['afiliado_id']."' ";
        $sql .= "AND    AF.eps_afiliacion_id = ".$datos['eps_afiliacion_id']." ";
        $sql .= "AND    AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
        $sql .= "AND    AD.afiliado_id = AF.afiliado_id ";
        $sql .= "AND    AU.estado_afiliado_id = AF.estado_afiliado_id ";
        $sql .= "AND    AU.estado_afiliado_id = AE.estado_afiliado_id ";
        $sql .= "AND    AU.subestado_afiliado_id = AF.subestado_afiliado_id ";

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
    * Funcion donde se obtiene el numero de identificacion y el tipo de identificacion
    * de los beneficiarios asociados a un cotizante
    *
    * @param array $datos Vector con la informacion del tipo e identificacion
    *               del afiliado
    *
    * @return array
    */
    function ObtenerBeneficiariosCotizante($datos)
    {
      $sql  = "SELECT EB.afiliado_tipo_id, ";
      $sql .= "       EB.afiliado_id, ";
      $sql .= "       EA.estado_afiliado_id, ";
      $sql .= "       EA.subestado_afiliado_id ";
      $sql .= "FROM   eps_afiliados_beneficiarios EB, ";
      $sql .= "       eps_afiliados EA ";
      $sql .= "WHERE  EB.cotizante_tipo_id = '".$datos['afiliado_tipo_id']."' ";
      $sql .= "AND    EB.cotizante_id = '".$datos['afiliado_id']."' ";
      $sql .= "AND    EB.eps_afiliacion_id = ".$datos['eps_afiliacion_id']." ";
      $sql .= "AND    EB.eps_afiliacion_id = EA.eps_afiliacion_id ";
      $sql .= "AND    EB.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $sql .= "AND    EB.afiliado_id = EA.afiliado_id ";
      $sql .= "AND    EA.estado_afiliado_id NOT IN('AF') ";

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
    * Funcion donde se actualiza el estado y el subestado de los afiliados
    * correspondientes    
    *
    * @param array $datos Vector con la informacion del tipo e identificacion
    *               del afiliado
    * @param array $beneficiarios Vector con los datos del numero de identificacion y 
    *              tipo de identificacion de los beneficiarios, para cuando el estado que
    *              se modificara pertenece a un cotizante
    * @param array $estados Vector con los datos de los flujos de estados para los beneficiarios
    * @return array
    */
    function ActualizarrEstadosAfiliado($datos,$beneficiarios,$estados)
    {
      $sql  = "UPDATE eps_afiliados  ";
      $sql .= "SET    estado_afiliado_id = '".$datos['estado_afiliado_id']."', ";
      $sql .= "       subestado_afiliado_id = '".$datos['subestado_afiliado_id']."', ";
      $sql .= "       accion_ultima_actualizacion = '".$datos['observacion']."' ";
      $sql .= "WHERE  afiliado_tipo_id = '".$datos['afiliado_tipo_id']."' ";
      $sql .= "AND    afiliado_id = '".$datos['afiliado_id']."' ";
      $sql .= "AND    eps_afiliacion_id = ".$datos['eps_afiliacion_id']."; ";
      
      $this->ConexionTransaccion();
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      $sql  = "INSERT INTO eps_historico_estados( ";
      $sql .= "       eps_afiliacion_id ,";
      $sql .= "       afiliado_tipo_id ,";
      $sql .= "       afiliado_id ,";
      $sql .= "       nuevo_valor_estado ,";
      $sql .= "       nuevo_valor_subestado,";
      $sql .= "       viejo_valor_estado ,";
      $sql .= "       viejo_valor_subestado ,";
      $sql .= "       observacion,";
      $sql .= "       usuario_registro)";
      $sql .= "VALUES ( ";
      $sql .= "       ".$datos['eps_afiliacion_id'].",";
      $sql .= "       '".$datos['afiliado_tipo_id']."', ";
      $sql .= "       '".$datos['afiliado_id']."', ";
      $sql .= "       '".$datos['estado_afiliado_id']."', ";
      $sql .= "       '".$datos['subestado_afiliado_id']."', ";
      $sql .= "       '".$datos['anterior']['estado_afiliado_id']."', ";
      $sql .= "       '".$datos['anterior']['subestado_afiliado_id']."', ";
      $sql .= "       '".$datos['observacion']."', ";
      $sql .= "        ".UserGetUID()." ";
      $sql .= ");";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      if(!empty($beneficiarios) && $datos['estado_afiliado_id'] == "RE")
      {
        foreach($beneficiarios as $key => $afiliado)
        {
          if(empty($estados))
          {
            $estados['estado_afiliado_id_beneficiario'] = $datos['estado_afiliado_id'];
            $estados['subestado_afiliado_id_beneficiario'] = $datos['subestado_afiliado_id']; 
          }
          $sql  = "UPDATE eps_afiliados  ";
          $sql .= "SET    estado_afiliado_id = '".$estados['estado_afiliado_id_beneficiario']."', ";
          $sql .= "       subestado_afiliado_id = '".$estados['subestado_afiliado_id_beneficiario']."' ";
          $sql .= "WHERE  afiliado_tipo_id = '".$afiliado['afiliado_tipo_id']."' ";
          $sql .= "AND    afiliado_id = '".$afiliado['afiliado_id']."' ";
          $sql .= "AND    eps_afiliacion_id = ".$datos['eps_afiliacion_id']."; ";
          
          if(!$rst = $this->ConexionTransaccion($sql)) return false;
          
          $sql  = "INSERT INTO eps_historico_estados( ";
          $sql .= "       eps_afiliacion_id ,";
          $sql .= "       afiliado_tipo_id ,";
          $sql .= "       afiliado_id ,";
          $sql .= "       nuevo_valor_estado ,";
          $sql .= "       nuevo_valor_subestado,";
          $sql .= "       viejo_valor_estado ,";
          $sql .= "       viejo_valor_subestado ,";
          $sql .= "       usuario_registro)";
          $sql .= "VALUES ( ";
          $sql .= "        ".$datos['eps_afiliacion_id'].",";
          $sql .= "       '".$afiliado['afiliado_tipo_id']."', ";
          $sql .= "       '".$afiliado['afiliado_id']."', ";
          $sql .= "       '".$estados['estado_afiliado_id_beneficiario']."', ";
          $sql .= "       '".$estados['subestado_afiliado_id_beneficiario']."', ";
          $sql .= "       '".$afiliado['estado_afiliado_id']."', ";
          $sql .= "       '".$afiliado['subestado_afiliado_id']."', ";
          $sql .= "        ".UserGetUID()." ";
          $sql .= ");";
          
          if(!$rst = $this->ConexionTransaccion($sql)) return false;
        }
      }
      $this->dbconn->CommitTrans();
      return true;
    }
    /**
    * Funcion donde se la lista de cambios en los estados y subestados de una persona
    *
    * @param array $datos Vector con los filtros que aplica el usuario a la busquedad
    * @param int $pg_siguiente Numero de la pagina que se esta 
    *         visualizando actualmente
    * @return array
    */
    function ObtenerHistorialEstados($datos,$pg_siguiente)
    {
      $sql  = "SELECT AD.afiliado_tipo_id, ";
      $sql .= "       AD.afiliado_id, ";
      $sql .= "       AD.primer_apellido||' '||AD.segundo_apellido AS apellidos, ";
      $sql .= "       AD.primer_nombre ||' '||AD.segundo_nombre AS nombres, ";
      $sql .= "       AU.descripcion_subestado, ";
      $sql .= "       AE.descripcion_estado, ";
      $sql .= "       AB.descripcion_subestado AS descripcion_subestado_viejo, ";
      $sql .= "       AT.descripcion_estado AS descripcion_estado_viejo, ";
      $sql .= "       HE.observacion, ";
      $sql .= "       TO_CHAR(HE.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
      $sql .= "       SU.nombre ";
      $whr  = "FROM   eps_afiliados_datos AD,";
      $whr .= "       eps_afiliados AF,";
      $whr .= "       eps_afiliados_estados AE,";
      $whr .= "       eps_afiliados_estados AT,";
      $whr .= "       eps_afiliados_subestados AU, ";
      $whr .= "       eps_afiliados_subestados AB, ";
      $whr .= "       eps_historico_estados HE, ";
      $whr .= "       system_usuarios SU ";
      $whr .= "WHERE  AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
      $whr .= "AND    AD.afiliado_id = AF.afiliado_id ";
      $whr .= "AND    AF.afiliado_tipo_id = HE.afiliado_tipo_id ";
      $whr .= "AND    AF.afiliado_id = HE.afiliado_id ";
      $whr .= "AND    AF.eps_afiliacion_id = HE.eps_afiliacion_id ";
      $whr .= "AND    AE.estado_afiliado_id = HE.nuevo_valor_estado ";
      $whr .= "AND    AU.subestado_afiliado_id = HE.nuevo_valor_subestado ";
      $whr .= "AND    AT.estado_afiliado_id = HE.viejo_valor_estado ";
      $whr .= "AND    AB.subestado_afiliado_id = HE.viejo_valor_subestado ";
      $whr .= "AND    HE.usuario_registro = SU.usuario_id ";
      
      if($datos['fecha_registro'])
        $whr .= "AND    HE.fecha_registro::date = '".$this->DividirFecha($datos['fecha_registro'])."' ";
      if($datos['buscador']['afiliado_tipo_id'] != "-1" && $datos['buscador']['afiliado_tipo_id'])
      {
        $whr .= "AND    AD.afiliado_tipo_id = '".$datos['buscador']['afiliado_tipo_id']."' ";
        $whr .= "AND    AD.afiliado_id = '".$datos['buscador']['afiliado_id']."' ";
      }

      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr",$pg_siguiente))
        return false;
      
      $whr .= "ORDER BY HE.eps_historico_estados_id ";
      $whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";

      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
  }
?>