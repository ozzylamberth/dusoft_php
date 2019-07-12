<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Listados.class.php,v 1.4 2009/09/30 12:52:36 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: Novedades
  * Clase encargada de hacer las consultas y las actualizaciones para 
  * las novedades de los afiliados
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.4 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class Listados extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function Novedades(){}
    /**
    * Funcion donde se obtiene la lista de novedades no procesadas 
    * para el archivo de novedades
    * 
    * @param int $pg_siguiente Numero de la pagina que se esta 
    *         visualizando actualmente
    * @param int $fecha Filtro de la fecha de registro para filtrar la busqueda
    *
    * @return array
    */
    function ObtenerListaBeneficiarios($filtro ,$pg_siguiente,$op = 0)
    {
      $sql  = "SELECT * ";
      $sql .= "FROM    ( ";
      $sql .= "          SELECT  edad(ED.fecha_nacimiento) AS edad_afiliado,";
      $sql .= "                  ED.primer_nombre,";
      $sql .= "                  ED.segundo_nombre,";
      $sql .= "                  ED.primer_apellido,";
      $sql .= "                  ED.segundo_apellido,";
      $sql .= "                  EA.eps_afiliacion_id,";
      $sql .= "                  ED.afiliado_tipo_id,";
      $sql .= "                  ED.afiliado_id, ";
      $sql .= "                  EA.estado_afiliado_id,";
      $sql .= "                  EA.subestado_afiliado_id,";      
      $sql .= "                  AU.descripcion_subestado, ";
      $sql .= "                  AT.descripcion_estado, ";
      $sql .= "                  EB.estamento_id, ";
      $sql .= "                  EB.estamento_siis, ";
      $sql .= "                  EB.descripcion_estamento, ";
      $sql .= "                  PC.periodo, ";
      $sql .= "                  PC.cobertura_fecha_fin, ";
      $sql .= "                  PC.inicio, ";
      $sql .= "                  PC.fin, ";
      $sql .= "                  ED.telefono_residencia, ";
      $sql .= "                  ED.direccion_residencia, ";
      $sql .= "                  ED.telefono_movil ";
      $sql .= "          FROM    ( ";
      $sql .= "                   SELECT  EB.eps_afiliacion_id, ";
      $sql .= "                           EB.afiliado_id, ";
      $sql .= "                           EB.afiliado_tipo_id, ";
      $sql .= "                           'B' AS estamento_id, ";
      $sql .= "                           'B' AS estamento_siis, ";
      $sql .= "                           'BENEFICIARIO' AS descripcion_estamento ";
      $sql .= "                   FROM    eps_afiliados_beneficiarios EB, "; 
      $sql .= "                           eps_parentescos_beneficiarios EP " ;
      $sql .= "                   WHERE   EB.parentesco_id = EP.parentesco_id ";
      $sql .= "                   AND     EB.parentesco_id = '2' ";
      $sql .= "                   UNION ALL ";
      $sql .= "                   SELECT  EC.eps_afiliacion_id, ";
      $sql .= "                           EC.afiliado_id, ";
      $sql .= "                           EC.afiliado_tipo_id, ";
      $sql .= "                           EE.estamento_id, ";
      $sql .= "                           EE.estamento_siis, ";
      $sql .= "                           EE.descripcion_estamento ";
      $sql .= "                   FROM    eps_afiliados_cotizantes EC, " ;
      $sql .= "                           eps_estamentos EE " ;
      $sql .= "                   WHERE   EE.estamento_id = EC.estamento_id  ";
      $sql .= "                   AND     EE.estamento_siis = 'S'  ";      
      $sql .= "                  ) AS EB   " ;
      $sql .= "                 LEFT JOIN  ";                
      $sql .= "                 ( ";
      $sql .= "                   SELECT CE.afiliado_tipo_id,";
      $sql .= "                          CE.afiliado_id,";
      $sql .= "                          CE.cobertura_fecha_fin,";
      $sql .= "                          TO_CHAR(CE.cobertura_fecha_inicio,'DD/MM/YYYY') AS inicio,";
      $sql .= "                          TO_CHAR(CE.cobertura_fecha_fin,'DD/MM/YYYY') AS fin,";
      $sql .= "                          CASE WHEN CE.cobertura_fecha_fin >= NOW()::date THEN '1'	";
      $sql .= "                          ELSE '2' END AS periodo ";
      $sql .= "                   FROM  eps_afiliados_cobertura_estudiantes AS CE, ";
      $sql .= "                   ( ";
      $sql .= "                     SELECT MAX(eps_afiliados_atencion_estudiante_id) AS eps_afiliados_atencion_estudiante_id, ";
      $sql .= "                                       afiliado_tipo_id, 	 ";
      $sql .= "                                       afiliado_id ";
      $sql .= "                                 FROM  eps_afiliados_cobertura_estudiantes ";
      $sql .= "                                 GROUP BY afiliado_tipo_id, afiliado_id ";
      $sql .= "                   ) AS CD ";
      $sql .= "                   WHERE CD.eps_afiliados_atencion_estudiante_id = CE.eps_afiliados_atencion_estudiante_id ";
      $sql .= "                   AND   CD.afiliado_tipo_id = CE.afiliado_tipo_id ";
      $sql .= "                   AND   CD.afiliado_id = CE.afiliado_id ";
      if($filtro['periodo'] == '1')
        $sql .= "                   AND   CE.cobertura_fecha_fin >= NOW()::date ";
      if($filtro['periodo'] == '2')
        $sql .= "                   AND   CE.cobertura_fecha_fin < NOW()::date ";
      $sql .= "                  ) AS PC ";
      $sql .= "                  ON( PC.afiliado_tipo_id = EB.afiliado_tipo_id ";
      $sql .= "                      AND   PC.afiliado_id = EB.afiliado_id), ";
      $sql .= "                  eps_afiliados_estados AT,";
      $sql .= "                  eps_afiliados_subestados AU, ";
      $sql .= "                  eps_afiliados_datos ED,";
      $sql .= "                  eps_afiliados EA ";
      $sql .= "          WHERE   EB.eps_afiliacion_id = EA.eps_afiliacion_id ";
      $sql .= "          AND     EB.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $sql .= "          AND     EB.afiliado_id = EA.afiliado_id ";
      $sql .= "          AND     ED.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $sql .= "          AND     ED.afiliado_id = EA.afiliado_id ";
      $sql .= "          AND     EA.estado_afiliado_id != 'RE' ";
      $sql .= "          AND     EA.estado_afiliado_id = AU.estado_afiliado_id ";
      $sql .= "          AND     EA.subestado_afiliado_id = AU.subestado_afiliado_id ";
      $sql .= "          AND     AU.estado_afiliado_id = AT.estado_afiliado_id ";
      if($filtro['afiliado_id'] && $filtro['afiliado_tipo_id'] != '-1')
      {
        $sql .= "          AND     ED.afiliado_tipo_id = '".$filtro['afiliado_tipo_id']."' ";
        $sql .= "          AND     ED.afiliado_id = '".$filtro['afiliado_id']."' ";
      }
      if($filtro['Apellidos'] != "" || $filtro['Nombres'] != "")
      {
        $ctl = AutoCarga::factory("ClaseUtil");
        $sql .= "AND ".$ctl->FiltrarNombres($filtro['Nombres'],$filtro['Apellidos'],"ED");
      }      
      if($filtro['estamento_id'] != "-1" && $filtro['estamento_id'] != "")
        $sql .= "AND estamento_id = '".$filtro['estamento_id']."' ";
            
      if($filtro['codigo'] != "")
      {
        $sql .= "AND codigo_estudiante = '".$filtro['codigo']."' ";
      }
      
      if($filtro['periodo'] == '1' || $filtro['periodo'] == '2')
        $sql .= "AND  periodo IS NOT NULL ";
      
      if($filtro['periodo'] == '3')
        $sql .= "AND  periodo IS NULL ";
      
      /*
      switch($filtro['tipo_beneficiario'])
      {
        case '1':
          $sql .= "AND  codigo_estudiante IS NOT NULL ";
        break;
        case '2':
          $sql .= "AND  codigo_estudiante IS NULL ";
        break;
        case '3':
          $sql .= "AND  sw_estudiante_nocturno = '1' ";
        break;        
        case '4':
          $sql .= "AND  sw_estudiante_postgrado = '1' ";
        break;
        case '5':
          $sql .= "AND  sw_matricula_financiera = '0' ";
          $sql .= "AND  sw_estudiante_postgrado = '1' ";
        break;        
        case '6':
          $sql .= "AND  sw_estudiante_trabaja = '1' ";
        break;
      }*/
      $sql .= "        ) AS A ";
      
      if($filtro['edad'])
      {
        $sql .= "WHERE      edad_afiliado "; 
        switch($filtro['edad_signo'])
        {
          case 1: $sql .= " =  ".$filtro['edad']." "; break;
          case 2: $sql .= " >  ".$filtro['edad']." "; break;
          case 3: $sql .= " >= ".$filtro['edad']." "; break;
          case 4: $sql .= " <  ".$filtro['edad']." "; break;
          case 5: $sql .= " <= ".$filtro['edad']." "; break;
          case 6: $sql .= " BETWEEN ".$filtro['edad']. " AND ".$filtro['edad_maxima']." "; break;
        }
      }
      /*$sql .= " WHERE  edad_afiliado >= 18 ";
      $sql .= " AND    edad_afiliado <= 25 ";*/
      
      if($op == 0)
      {
        if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") AS A",$pg_siguiente))
          return false;
      
        $sql .= "ORDER BY edad_afiliado,primer_apellido,segundo_apellido ";
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      }
      else
        $sql .= "ORDER BY edad_afiliado,primer_apellido,segundo_apellido ";
        
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
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
    * Funcion donde se actualiza el estado y el subestado de los afiliados
    * correspondientes    
    *
    * @param array $datos Vector con la informacion del tipo e identificacion
    *               del afiliado
    * @param array $beneficiarios Vector con los datos del numero de identificacion y 
    *              tipo de identificacion de los beneficiarios, para cuando el estado que
    *              se modificara pertenece a un cotizante
    * @param array $estados Vector con los datos de los flujos de estados para los beneficiarios
    * @param array $compare Valor con el que se comparara el vector
    *
    * @return array
    */
    function ActualizarEstadosAfiliado($datos,$estados,$compare = 'S')
    {
      $this->ConexionTransaccion();
      foreach($datos as $key1 => $afiliacion)
      {
        foreach($afiliacion as $key2 => $tiposdocumentos)
        {
          foreach($tiposdocumentos as $key3 => $documentos)
          {
            if($documentos['chkbox'])
            {
              $sql  = "UPDATE eps_afiliados  ";
              $sql .= "SET    estado_afiliado_id = '".$estados['estado_afiliado_id']."', ";
              $sql .= "       subestado_afiliado_id = '".$estados['subestado_afiliado_id']."' ";
              $sql .= "WHERE  afiliado_tipo_id = '".$key2."' ";
              $sql .= "AND    afiliado_id = '".$key3."' ";
              $sql .= "AND    eps_afiliacion_id = ".$key1."; ";
      
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
              $sql .= "       ".$key1.",";
              $sql .= "       '".$key2."', ";
              $sql .= "       '".$key3."', ";
              $sql .= "       '".$estados['estado_afiliado_id']."', ";
              $sql .= "       '".$estados['subestado_afiliado_id']."', ";
              $sql .= "       '".$documentos['estado']."', ";
              $sql .= "       '".$documentos['subestado']."', ";
              $sql .= "       '', ";
              $sql .= "        ".UserGetUID()." ";
              $sql .= ");";
              
              if(!$rst = $this->ConexionTransaccion($sql)) return false;
              
              if($documentos['estamento'] == $compare)
              {
                $d['eps_afiliacion_id'] = $key1;
                $d['afiliado_tipo_id'] = $key2;
                $d['afiliado_id'] = $key3;
                
                $beneficiarios = $this->ObtenerBeneficiariosCotizante($d);
                
                if(!empty($beneficiarios))
                {
                  $estb = $this->ObtenerEstadosFlujos($estados);
                  
                  foreach($beneficiarios as $key => $afiliado)
                  {
                    if(empty($estb))
                    {
                      $estados['estado_afiliado_id_beneficiario'] = $estados['estado_afiliado_id'];
                      $estados['subestado_afiliado_id_beneficiario'] = $estados['subestado_afiliado_id']; 
                    }
                    $sql  = "UPDATE eps_afiliados  ";
                    $sql .= "SET    estado_afiliado_id = '".$estados['estado_afiliado_id_beneficiario']."', ";
                    $sql .= "       subestado_afiliado_id = '".$estados['subestado_afiliado_id_beneficiario']."' ";
                    $sql .= "WHERE  afiliado_tipo_id = '".$afiliado['afiliado_tipo_id']."' ";
                    $sql .= "AND    afiliado_id = '".$afiliado['afiliado_id']."' ";
                    $sql .= "AND    eps_afiliacion_id = ".$d['eps_afiliacion_id']."; ";
                    
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
                    $sql .= "        ".$d['eps_afiliacion_id'].",";
                    $sql .= "       '".$afiliado['afiliado_tipo_id']."', ";
                    $sql .= "       '".$afiliado['afiliado_id']."', ";
                    $sql .= "       '".$estados['estado_afiliado_id_beneficiario']."', ";
                    $sql .= "       '".$estados['subestado_afiliado_id_beneficiario']."', ";
                    $sql .= "       '".$afiliado['estado_afiliado_id']."', ";
                    $sql .= "       '".$afiliado['subestado_afiliado_id']."', ";
                    $sql .= "        ".UserGetUID()." ";
                    $sql .= ");";
                    
                    unset($afiliacion[$d['eps_afiliacion_id']][$afiliado['afiliado_tipo_id']][$afiliado['afiliado_id']]);
                    
                    if(!$rst = $this->ConexionTransaccion($sql)) return false;
                  }
                }
              }
            }
          }
        }
      }
      $this->dbconn->CommitTrans();
      return true;
    }
    /**
    * Consulta de las dependencias de UV
    * registrados
    *
    * @return array
    */
    function ObtenerEstamentos()
    {
        $sql  = "SELECT estamento_id, ";
        $sql .= "       descripcion_estamento,";
        $sql .= "       estamento_siis ";
        $sql .= "FROM   eps_estamentos ";
        $sql .= "WHERE  estamento_siis = 'S' ";
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
    * Funcion donde se otiene el nombre del usuario del siistema
    *
    * @param integer $usuario_id identificador del usuario
    *
    * @return mixed
    */
    function ObtenerUsuario($usuario_id)
    {
        $sql  = "SELECT nombre ";
        $sql .= "FROM   system_usuarios ";
        $sql .= "WHERE  usuario_id = ".$usuario_id." ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        if(!$rst->EOF)
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
  }
?>