<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: AutorizacionCreditoSQL.class.php,v 1.1 2008/09/22 11:46:29 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */
  /**
  * Clase : AutorizacionCreditoSQL
  * 
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */

  class AutorizacionCreditoSQL extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function AutorizacionCreditoSQL(){}
    /**
    * Funcion donde se verifica el permiso del usuario para el ingreso
    * al modulo
    *
    * @return array $datos vector que contiene la informacion de la consulta del codigo de
    * la empresa y la razon social 
    */
    function ObtenerPermisos()
    {
      //$this->debug=true;
      $sql  = "SELECT   EM.empresa_id AS empresa, ";
      $sql .= "         EM.razon_social AS razon_social ";
      $sql .= "FROM     userpermisos_autorizacion_cd CP, empresas EM ";
      $sql .= "WHERE    CP.usuario_id = ".UserGetUID()." ";
      $sql .= "         AND CP.empresa_id = EM.empresa_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan las cuentas de los usuarios 
    *
    * @param string $pg_siguiente
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarCuentas($pg_siguiente)
    {
      //$this->debug=true;
      $sql  = "SELECT   c.numerodecuenta, p.paciente_id, p.primer_nombre, "; 
      $sql .= "         p.segundo_nombre, p.primer_apellido, p.segundo_apellido, "; 
      $sql .= "         hc.historia_numero, c.estado, c.total_cuenta, i.ingreso ";
      $whr  = "FROM     cuentas c, pacientes p, historias_clinicas hc, ";
      $whr .= "         ingresos i, cuentas_estados ce ";
      $whr .= "WHERE    p.paciente_id = hc.paciente_id ";
      $whr .= "         AND p.tipo_id_paciente = hc.tipo_id_paciente ";
      $whr .= "         AND p.paciente_id = i.paciente_id ";
      $whr .= "         AND p.tipo_id_paciente = i.tipo_id_paciente ";
      $whr .= "         AND i.ingreso = c.ingreso AND ce.estado = c.estado ";
      $whr .= "         AND (ce.estado = 1 OR ce.estado = 2) ";
      $whr .= "ORDER BY c.numerodecuenta, p.paciente_id, p.primer_nombre, ";
      $whr .= "         p.segundo_nombre, p.primer_apellido, p.segundo_apellido, ";
      $whr .= "         hc.historia_numero, c.estado, c.total_cuenta, i.ingreso ";
      
      $whr1  = "FROM     cuentas c, pacientes p, historias_clinicas hc, ";
      $whr1 .= "         ingresos i, cuentas_estados ce ";
      $whr1 .= "WHERE    p.paciente_id = hc.paciente_id ";
      $whr1 .= "         AND p.tipo_id_paciente = hc.tipo_id_paciente ";
      $whr1 .= "         AND p.paciente_id = i.paciente_id ";
      $whr1 .= "         AND p.tipo_id_paciente = i.tipo_id_paciente ";
      $whr1 .= "         AND i.ingreso = c.ingreso AND ce.estado = c.estado ";
      $whr1 .= "         AND (ce.estado = 1 OR ce.estado = 2) ";
      
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr1",$pg_siguiente,null,100))
        return false;
      
      $whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan las cuentas de los usuarios
    *
    * @param array $buscar arreglo que contiene los filtros de busqueda
    * @param string $pg_siguiente
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarCuentasFiltro($buscar, $pg_siguiente)
    {
      //$this->debug=true;
      $sql  = "SELECT   c.numerodecuenta, p.paciente_id, p.primer_nombre, "; 
      $sql .= "         p.segundo_nombre, p.primer_apellido, p.segundo_apellido, "; 
      $sql .= "         hc.historia_numero, c.estado, p.residencia_direccion, ";
      $sql .= "         p.residencia_telefono, c.total_cuenta, p.tipo_id_paciente, ";
      $sql .= "         i.ingreso ";
      $whr  = "FROM     cuentas c, pacientes p, historias_clinicas hc, ";
      $whr .= "         ingresos i, cuentas_estados ce ";
      $whr .= "WHERE    p.paciente_id = hc.paciente_id ";
      $whr .= "         AND p.tipo_id_paciente = hc.tipo_id_paciente ";
      $whr .= "         AND p.paciente_id = i.paciente_id ";
      $whr .= "         AND p.tipo_id_paciente = i.tipo_id_paciente ";
      $whr .= "         AND i.ingreso = c.ingreso AND ce.estado = c.estado ";
      $whr .= "         AND (ce.estado = 1 OR ce.estado = 2) "; 
      $whr .= "         AND c.sw_autoriza_credito='1' ";        
      if($buscar['cuenta'])
        $whr .= "       AND c.numerodecuenta = ".$buscar['cuenta']." ";
      if($buscar['nombre'])
        $whr .= "       AND (p.primer_nombre ILIKE '%".$buscar['nombre']."%' OR p.segundo_nombre ILIKE '%".$buscar['nombre']."%') ";
      if($buscar['identificacion'])
        $whr .= "       AND p.paciente_id = ".$buscar['identificacion']." ";
      if($buscar['apellido'])
        $whr .= "       AND (p.primer_apellido ILIKE '%".$buscar['apellido']."%' OR p.segundo_apellido ILIKE '%".$buscar['apellido']."%') ";
      if($buscar['historia'])
        $whr .= "       AND hc.historia_numero = ".$buscar['historia']." ";
      $whr .= "ORDER BY c.numerodecuenta, p.paciente_id, p.primer_nombre, ";
      $whr .= "         p.segundo_nombre, p.primer_apellido, p.segundo_apellido, ";
      $whr .= "         hc.historia_numero, c.estado, p.residencia_direccion, ";
      $whr .= "         p.residencia_telefono, c.total_cuenta, p.tipo_id_paciente, ";
      $whr .= "         i.ingreso ";
      
      $whr1  = "FROM     cuentas c, pacientes p, historias_clinicas hc, ";
      $whr1 .= "         ingresos i, cuentas_estados ce ";
      $whr1 .= "WHERE    p.paciente_id = hc.paciente_id ";
      $whr1 .= "         AND p.tipo_id_paciente = hc.tipo_id_paciente ";
      $whr1 .= "         AND p.paciente_id = i.paciente_id ";
      $whr1 .= "         AND p.tipo_id_paciente = i.tipo_id_paciente ";
      $whr1 .= "         AND i.ingreso = c.ingreso AND ce.estado = c.estado ";
      $whr1 .= "         AND (ce.estado = 1 OR ce.estado = 2) ";
      $whr1 .= "         AND c.sw_autoriza_credito='1' ";
      if($buscar['cuenta'])
        $whr1 .= "       AND c.numerodecuenta = ".$buscar['cuenta']." ";
      if($buscar['nombre'])
        $whr1 .= "       AND (p.primer_nombre ILIKE '%".$buscar['nombre']."%' OR p.segundo_nombre ILIKE '%".$buscar['nombre']."%') ";
      if($buscar['identificacion'])
        $whr1 .= "       AND p.paciente_id = ".$buscar['identificacion']." ";
      if($buscar['apellido'])
        $whr1 .= "       AND (p.primer_apellido ILIKE '%".$buscar['apellido']."%' OR p.segundo_apellido ILIKE '%".$buscar['apellido']."%') ";
      if($buscar['historia'])
        $whr1 .= "       AND hc.historia_numero = ".$buscar['historia']." ";
        
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr1",$pg_siguiente,null,100))
        return false;
      
      $whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan la clasificacion financiera y el tipo de grado de un 
    * paciente
    *
    * @param array $request arreglo que contiene los filtros de busqueda
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarClasiGrado($request)
    {
      //$this->debug=true;
      
      $sql  = "SELECT   pi.paciente_id, pi.tipo_id_paciente, ";
      $sql .= "         cf.descripcion as clasi_financiera, tg.descripcion as grado ";
      $whr  = "FROM     paciente_issfa pi, equivalencias_clasificacion_finaciera ecf, ";
      $whr .= "         tipo_grados tg, clasificaciones_financieros cf ";
      $whr .= "WHERE    pi.paciente_id = ".$request['paciente_id']." AND ";
      $whr .= "         pi.tipo_id_paciente = '".$request['tipo_id_paciente']."' AND ";
      $whr .= "         pi.grado_id = ecf.grado_id AND ";
      $whr .= "         pi.clasifi_finaci_id = ecf.clasifi_finaci_id AND ";
      $whr .= "         ecf.grado_id = tg.grado_id AND ";
      $whr .= "         ecf.clasifi_finaci_id = cf.clasifi_finaci_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion de los destinos existentes
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarDestinos()
    {
      $sql  = "SELECT * FROM destinos ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion de los plazos de pago existentes
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarPlazos()
    {
      $sql  = "SELECT * FROM plazos ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion del familiar responsable (garante)
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarResponsable($request)
    {
      //$this->debug=true;
      
      $sql  = "SELECT     rf.pri_nombre, rf.seg_nombre, rf.pri_apellido, rf.seg_apellido, ";
      $sql .= "           rf.no_identi_id, rf.direccionfam, rf.telefonofam, ";
      $sql .= "           responsable_familiar_id ";
      $whr  = "FROM       responsable_familiar rf, ingresos i, pacientes p ";
      $whr .= "WHERE      p.paciente_id=".$request['paciente_id']." AND ";
      $whr .= "           p.tipo_id_paciente='".$request['tipo_id_paciente']."' AND ";
      $whr .= "           p.paciente_id=i.paciente_id AND ";
      $whr .= "           p.tipo_id_paciente=i.tipo_id_paciente AND ";
      $whr .= "           i.ingreso=rf.ingreso_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    } 
    /**
    * Funcion donde se consulta la informacion de la via de ingreso
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarGrupo()
    {
      //$this->debug=true;
      $sql  = "SELECT via_ingreso_id, via_ingreso_nombre FROM vias_ingreso ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }  
    /**
    * Funcion donde se consulta la informacion de los repartos existentes
    * @return array $datos vector que contiene la informacion de la consulta
    */ 
    function ConsultarRepartos()
    {
      $sql  = "SELECT * FROM tipo_repartos ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion de los tipos de identificacion existentes
    * @return array $datos vector que contiene la informacion de la consulta
    */ 
    function ConsultarTiposId()
    {
      $sql  = "SELECT * FROM tipos_id_pacientes ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se realiza el ingreso de la autorizacion de credito a un paciente, 
    * teniendo en cuenta que tambien se ingresan los datos relacionados a las tablas 
    * responsable_familiar (garante), titular, cuotas y cuentas
    * @return array $indice['sq'] retorna el campo de identificacion de la tabla 
    * responsable_familiar
    */
    function IngresarAutorizacionCredito($request)
    {
      //$this->debug = true;
      if ($request['infoGarante']=="false")
      {
        $indice = array();
        
        $this->ConexionTransaccion();
        
        $sql = "SELECT NEXTVAL('responsable_familiar_responsable_familiar_id_seq'::regclass) AS sq ";
        
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;
        
        if(!$rst->EOF)
        {
          $indice = $rst->GetRowAssoc($ToUpper=false);
          $rst->MoveNext();
        }
        
        $rst->Close();
        
        $sqlerror = "SELECT setval('responsable_familiar_responsable_familiar_id_seq', ".($indice['sq']-1).") ";
        
        $sql  = "INSERT INTO responsable_familiar( ";
        $sql .= "       responsable_familiar_id, ";
        $sql .= "       paciente_id, ";
        $sql .= "       tipo_id_paciente, ";
        $sql .= "       no_identi_id, ";
        $sql .= "       pri_nombre, ";
        $sql .= "       seg_nombre, ";
        $sql .= "       pri_apellido, ";
        $sql .= "       seg_apellido, ";
        $sql .= "       repartofam_id, ";
        $sql .= "       telefonofam, ";
        $sql .= "       direccionfam, ";
        $sql .= "       pais_id, ";
        $sql .= "       provincia_id, ";
        $sql .= "       canton_id, ";
        $sql .= "       parroquia_id, ";
        $sql .= "       tipo_identi_id, ";
        $sql .= "       ingreso_id) ";
        $sql .= "VALUES( ";
        $sql .= "       ".$indice['sq'].", ";
        $sql .= "       '".$request['paciente_id']."', ";
        $sql .= "       '".$request['tipo_id_paciente']."', ";
        $sql .= "       '".$request['noIdGarante']."', ";
        $sql .= "       '".$request['priNomGarante']."', ";
        $sql .= "       '".$request['segNomGarante']."', ";
        $sql .= "       '".$request['priApeGarante']."', ";
        $sql .= "       '".$request['segApeGarante']."', ";
        $sql .= "       ".$request['reparto'].", ";
        if($request['telGarante']!="")
          $sql .= "       ".$request['telGarante'].", ";
        else
          $sql .= "       NULL, ";
        $sql .= "       '".$request['dirGarante']."', ";
        $sql .= "       '".$request['pais']."', ";
        $sql .= "       '".$request['dpto']."', ";
        $sql .= "       '".$request['mpio']."', ";
        $sql .= "       '".$request['comuna']."', ";
        $sql .= "       '".$request['tipoIdGarante']."', ";
        $sql .= "       ".$request['ingreso'].") ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
        {
          if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
          return false;
        }
        //$this->Commit();
        //print_r("Ingreso IF Responsable");
      }
      //------------
      $this->ConexionTransaccion();
      
      $indice2 = array();
      
      $sql2 = "SELECT NEXTVAL('titular_titular_id_seq'::regclass) AS sq2 ";
      
      if(!$rst2 = $this->ConexionBaseDatos($sql2)) return false;
          
      if(!$rst2->EOF) 
      {
        $indice2 = $rst2->GetRowAssoc($ToUpper=false);
        $rst2->MoveNext();
      }
      
      $rst2->Close();
          
      $sqlerror2 = "SELECT setval('titular_titular_id_seq', ".($indice2['sq2']-1).") ";
      
      $sql2  = "INSERT INTO titular( ";
      $sql2 .= "       titular_id, ";
      $sql2 .= "       primer_nombre, ";
      $sql2 .= "       segundo_nombre, ";
      $sql2 .= "       primer_apellido, ";
      $sql2 .= "       segundo_apellido, ";
      $sql2 .= "       tipo_identificacion, ";
      $sql2 .= "       telefono, ";
      $sql2 .= "       parentesco, ";
      $sql2 .= "       identificacion, ";
      $sql2 .= "       direccion, ";
      $sql2 .= "       no_afiliacion, ";
      $sql2 .= "       paciente_id, ";
      $sql2 .= "       tipo_id_paciente) ";
      $sql2 .= "VALUES( ";
      $sql2 .= "       ".$indice2['sq2'].", ";
      $sql2 .= "       '".$request['priNomTitular']."', ";
      $sql2 .= "       '".$request['segNomTitular']."', ";
      $sql2 .= "       '".$request['priApeTitular']."', ";
      $sql2 .= "       '".$request['segApeTitular']."', ";
      $sql2 .= "       '".$request['tipoIdTitular']."', ";
      $sql2 .= "       '".$request['telTitular']."', ";
      $sql2 .= "       '".$request['parTitular']."', ";
      $sql2 .= "       '".$request['noIdTitular']."', ";
      $sql2 .= "       '".$request['dirTitular']."', ";
      $sql2 .= "       '".$request['noAfiTitular']."', ";
      $sql2 .= "       '".$request['paciente_id']."', ";
      $sql2 .= "       '".$request['tipo_id_paciente']."') ";
      
      if(!$rst2 = $this->ConexionTransaccion($sql2))
      {
        if(!$rst2 = $this->ConexionTransaccion($sqlerror2)) return false;
        return false;
      }
      
      //---------------------------
      
      $this->ConexionTransaccion();
      
      $indice3 = array();
      
      $sql3 = "SELECT NEXTVAL('autorizaciones_creditos_autorizacion_cr_id_seq'::regclass) AS sq3 ";
      
      if(!$rst3 = $this->ConexionBaseDatos($sql3)) return false;
          
      if(!$rst3->EOF) 
      {
        $indice3 = $rst3->GetRowAssoc($ToUpper=false);
        $rst3->MoveNext();
      }
      
      $rst3->Close();
          
      $sqlerror3 = "SELECT setval('autorizaciones_creditos_autorizacion_cr_id_seq', ".($indice3['sq3']-1).") ";
      
      if($request['garantiaP']=="")
        $request['garantiaP']='0';
      
      if($request['garantiaLC']=="")
        $request['garantiaLC']='0';    
      
      $sql3  = "INSERT INTO autorizaciones_creditos( ";
      $sql3 .= "       autorizacion_cr_id, ";
      $sql3 .= "       no_cuotas, ";
      $sql3 .= "       fecha_registro, ";
      $sql3 .= "       sw_pagare, ";
      $sql3 .= "       sw_letra_cambio, ";
      $sql3 .= "       porcentaje_interes, ";
      $sql3 .= "       deposito, ";
      $sql3 .= "       fecha_inicio, ";
      $sql3 .= "       plazo_id, ";
      $sql3 .= "       numerodecuenta, ";
      $sql3 .= "       usuario_id, ";
      $sql3 .= "       destino_id, ";
      $sql3 .= "       responsable_familiar_id, ";
      $sql3 .= "       titular_id, ";
      $sql3 .= "       total_pago, ";
      $sql3 .= "       via_ingreso_id) ";
      $sql3 .= "VALUES(";
      $sql3 .= "       ".$indice3['sq3'].", ";
      $sql3 .= "       ".$request['noCuotas'].", ";
      $sql3 .= "       NOW(), ";
      $sql3 .= "       '".$request['garantiaP']."', ";
      $sql3 .= "       '".$request['garantiaLC']."', ";
      $sql3 .= "       ".$request['interes'].", ";
      $sql3 .= "       ".$request['deposito'].", ";
      $sql3 .= "       '".$request['fechaInicio']."', ";
      $sql3 .= "       ".$request['plazo'].", ";
      $sql3 .= "       ".$request['numerodecuenta'].", ";
      $sql3 .= "       ".UserGetUID().", ";
      $sql3 .= "       ".$request['destino'].", ";
      if($request['infoGarante']=="true")
        $sql3 .= "     ".$request['responsable_familiar_id'].", ";
      else
        $sql3 .= "     ".$indice['sq'].", ";
      $sql3 .= "       ".$indice2['sq2'].", ";
      $sql3 .= "       ".$request['pago_total'].", ";
      $sql3 .= "       ".$request['grupo'].") ";
      $fk = $indice3['sq3'];
      if(!$rst3 = $this->ConexionTransaccion($sql3))
      {
        if(!$rst3 = $this->ConexionTransaccion($sqlerror3)) return false;
        return false;
      }
      
      //----------------------------
      
      $this->ConexionTransaccion();
      
      for($i=0;$i<$request['noCuotas'];$i++)
      {
        $indice4 = array();
        
        $sql4 = "SELECT NEXTVAL('cuotas_cuota_id_seq'::regclass) AS sq4 ";
        
        if(!$rst4 = $this->ConexionBaseDatos($sql4)) return false;
            
        if(!$rst4->EOF) 
        {
          $indice4 = $rst4->GetRowAssoc($ToUpper=false);
          $rst4->MoveNext();
        }
        
        $rst4->Close();
            
        $sqlerror4 = "SELECT setval('cuotas_cuota_id_seq', ".($indice4['sq4']-1).") ";
        
        $sql4  = "INSERT INTO cuotas( ";
        $sql4 .= "       cuota_id, ";
        $sql4 .= "       fecha_cuota, ";
        $sql4 .= "       cuota, ";
        $sql4 .= "       intereses, ";
        $sql4 .= "       autorizacion_cr_id) ";
        $sql4 .= "VALUES( ";
        $sql4 .= "       ".$indice4['sq4'].", ";
        $sql4 .= "       '".$request['fecha'.$i]."', ";
        $sql4 .= "       ".$request['cuota'.$i].", ";
        $sql4 .= "       ".$request['cargo'.$i].", ";
        $sql4 .= "       ".$fk."); ";
        
        if(!$rst4 = $this->ConexionTransaccion($sql4))
        {
          if(!$rst4 = $this->ConexionTransaccion($sqlerror4)) return false;
          return false;
        }
      }
      
      //------------------------
      
      $this->ConexionTransaccion();
      
      $sql5  = "UPDATE    cuentas ";
      $sql5 .= "SET       sw_autoriza_credito='2' ";
      $sql5 .= "WHERE     numerodecuenta=".$request['numerodecuenta']." ";
      
      if(!$rst = $this->ConexionTransaccion($sql5))
        return false;
      
      $this->Commit();
      return $indice['sq'];
    }
    /**
    * Funcion donde se consultan las cuentas que tienen el credito autorizado
    *
    * @param array $buscar arreglo que contiene los filtros de busqueda
    * @param string $pg_siguiente
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarCuentasAutorizadas($buscar, $pg_siguiente)
    {
      //$this->debug=true;
      $sql  = "SELECT   c.numerodecuenta, p.paciente_id, p.primer_nombre, ";
      $sql .= "         p.segundo_nombre, p.primer_apellido, p.segundo_apellido, ";
      $sql .= "         hc.historia_numero, c.estado, p.residencia_direccion, ";
      $sql .= "         p.residencia_telefono, c.total_cuenta, p.tipo_id_paciente, ";
      $sql .= "         i.ingreso, fc.factura_fiscal ";
      $whr  = "FROM     cuentas c LEFT JOIN( ";
      $whr .= "         SELECT  fc.* ";
      $whr .= "         FROM    fac_facturas ff, fac_facturas_cuentas fc ";
      $whr .= "         WHERE   ff.prefijo = fc.prefijo AND ff.empresa_id = fc.empresa_id ";
      $whr .= "                 AND ff.factura_fiscal = fc.factura_fiscal ";
      $whr .= "                 AND ff.sw_clase_factura = '0' AND ff.estado IN ('0','1') ";
      $whr .= ") AS fc ON fc.numerodecuenta = c.numerodecuenta, pacientes p, ";
      $whr .= "         historias_clinicas hc, ingresos i, cuentas_estados ce ";
      $whr .= "WHERE    p.paciente_id = hc.paciente_id ";
      $whr .= "         AND p.tipo_id_paciente = hc.tipo_id_paciente ";
      $whr .= "         AND p.paciente_id = i.paciente_id ";
      $whr .= "         AND p.tipo_id_paciente = i.tipo_id_paciente ";
      $whr .= "         AND i.ingreso = c.ingreso AND ce.estado = c.estado ";
      $whr .= "         AND (ce.estado = 1 OR ce.estado = 2) "; 
      $whr .= "         AND c.sw_autoriza_credito='2' ";
      if($buscar['cuenta'])
        $whr .= "       AND c.numerodecuenta = ".$buscar['cuenta']." ";
      if($buscar['nombre'])
        $whr .= "       AND (p.primer_nombre ILIKE '%".$buscar['nombre']."%' OR p.segundo_nombre ILIKE '%".$buscar['nombre']."%') ";
      if($buscar['identificacion'])
        $whr .= "       AND p.paciente_id = ".$buscar['identificacion']." ";
      if($buscar['apellido'])
        $whr .= "       AND (p.primer_apellido ILIKE '%".$buscar['apellido']."%' OR p.segundo_apellido ILIKE '%".$buscar['apellido']."%') ";
      if($buscar['historia'])
        $whr .= "       AND hc.historia_numero = ".$buscar['historia']." ";
      if($buscar['factura'])
        $whr .= "       AND fc.factura_fiscal = ".$buscar['factura']." ";
      $whr .= "ORDER BY c.numerodecuenta, p.paciente_id, p.primer_nombre, ";
      $whr .= "         p.segundo_nombre, p.primer_apellido, p.segundo_apellido, ";
      $whr .= "         hc.historia_numero, c.estado, p.residencia_direccion, ";
      $whr .= "         p.residencia_telefono, c.total_cuenta, p.tipo_id_paciente, ";
      $whr .= "         i.ingreso, fc.factura_fiscal ";
      
      $whr1  = "FROM     cuentas c LEFT JOIN( ";
      $whr1 .= "         SELECT  fc.* ";
      $whr1 .= "         FROM    fac_facturas ff, fac_facturas_cuentas fc ";
      $whr1 .= "         WHERE   ff.prefijo = fc.prefijo AND ff.empresa_id=fc.empresa_id ";
      $whr1 .= "                 AND ff.factura_fiscal = fc.factura_fiscal ";
      $whr1 .= "                 AND ff.sw_clase_factura = '0' AND ff.estado IN ('0','1') ";
      $whr1 .= ") AS fc ON fc.numerodecuenta = c.numerodecuenta, pacientes p, ";
      $whr1 .= "         historias_clinicas hc, ingresos i, cuentas_estados ce ";
      $whr1 .= "WHERE    p.paciente_id = hc.paciente_id ";
      $whr1 .= "         AND p.tipo_id_paciente = hc.tipo_id_paciente ";
      $whr1 .= "         AND p.paciente_id = i.paciente_id ";
      $whr1 .= "         AND p.tipo_id_paciente = i.tipo_id_paciente ";
      $whr1 .= "         AND i.ingreso = c.ingreso AND ce.estado = c.estado ";
      $whr1 .= "         AND (ce.estado = 1 OR ce.estado = 2) "; 
      $whr1 .= "         AND c.sw_autoriza_credito='2' ";
      if($buscar['cuenta'])
        $whr1 .= "       AND c.numerodecuenta = ".$buscar['cuenta']." ";
      if($buscar['nombre'])
        $whr1 .= "       AND (p.primer_nombre ILIKE '%".$buscar['nombre']."%' OR p.segundo_nombre ILIKE '%".$buscar['nombre']."%') ";
      if($buscar['identificacion'])
        $whr1 .= "       AND p.paciente_id = ".$buscar['identificacion']." ";
      if($buscar['apellido'])
        $whr1 .= "       AND (p.primer_apellido ILIKE '%".$buscar['apellido']."%' OR p.segundo_apellido ILIKE '%".$buscar['apellido']."%') ";
      if($buscar['historia'])
        $whr1 .= "       AND hc.historia_numero = ".$buscar['historia']." ";
      if($buscar['factura'])
        $whr1 .= "       AND fc.factura_fiscal = ".$buscar['factura']." ";
        
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr1",$pg_siguiente,null,100))
        return false;
      
      $whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta informacion de la cuenta autorizada
    *
    * @param array $request arreglo que contiene los filtros de busqueda
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarInfoCuenAutorizada($request)
    {
      //$this->debug=true;
      
      $sql  = "SELECT   ac.deposito,ac.porcentaje_interes,ac.no_cuotas, ac.fecha_registro,";
      $sql .= "         ac.fecha_inicio, ac.total_pago, pl.descripcion as desc_plazo, ";
      $sql .= "         d.descripcion as desc_destino,vi.via_ingreso_nombre,ac.sw_pagare, ";
      $sql .= "         ac.sw_letra_cambio, rf.pri_nombre, rf.seg_nombre, ";
      $sql .= "         rf.pri_apellido, rf.seg_apellido, rf.direccionfam, ";
      $sql .= "         rf.no_identi_id, rf.telefonofam, tc.comuna, ";
      $sql .= "         tm.municipio, td.departamento, tp.pais, ";
      $sql .= "         ti.primer_nombre, ti.segundo_nombre, ti.primer_apellido, ";
      $sql .= "         ti.segundo_apellido, ti.direccion, ti.telefono, ";
      $sql .= "         ti.identificacion, ti.parentesco, ti.no_afiliacion ";
      $whr  = "FROM     autorizaciones_creditos ac, plazos pl, destinos d, ";
      $whr .= "         vias_ingreso vi, responsable_familiar rf, titular ti, ";
      $whr .= "         tipo_comunas tc, tipo_mpios tm, tipo_dptos td, tipo_pais tp ";
      $whr .= "WHERE    ac.numerodecuenta=".$request['numerodecuenta']." AND ";
      $whr .= "         ac.plazo_id=pl.plazo_id AND ac.destino_id=d.destino_id AND ";
      $whr .= "         ac.via_ingreso_id=vi.via_ingreso_id AND ";
      $whr .= "         ac.responsable_familiar_id=rf.responsable_familiar_id AND ";
      $whr .= "         rf.pais_id=tc.tipo_pais_id AND ";
      $whr .= "         rf.provincia_id=tc.tipo_dpto_id AND ";
      $whr .= "         rf.canton_id=tc.tipo_mpio_id AND ";
      $whr .= "         rf.parroquia_id=tc.tipo_comuna_id AND ";
      $whr .= "         tc.tipo_pais_id=tm.tipo_pais_id AND ";
      $whr .= "         tc.tipo_dpto_id=tm.tipo_dpto_id AND ";
      $whr .= "         tc.tipo_mpio_id=tm.tipo_mpio_id AND ";
      $whr .= "         tm.tipo_pais_id=td.tipo_pais_id AND ";
      $whr .= "         tm.tipo_dpto_id=td.tipo_dpto_id AND ";
      $whr .= "         td.tipo_pais_id=tp.tipo_pais_id AND ac.titular_id=ti.titular_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta el detalle de la cuenta autorizada
    *
    * @param array $request arreglo que contiene los filtros de busqueda
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarDetCuenAutorizada($request)
    {
      //$this->debug=true;
      
      $sql  = "SELECT   cu.fecha_cuota, cu.cuota, cu.intereses ";
      $whr  = "FROM     autorizaciones_creditos ac, cuotas cu ";
      $whr .= "WHERE    ac.numerodecuenta=".$request['numerodecuenta']." AND ";
      $whr .= "         ac.autorizacion_cr_id=cu.autorizacion_cr_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion de las cuentas con credito autorizado, para 
    * generar los reportes
    *
    * @param array $datos arreglo que contiene los filtros de busqueda
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ReporteAutorizacionFecha($datos)
    {
      //$this->debug=true;
      
      $sql  = "SELECT   p.tipo_id_paciente, p.paciente_id, p.primer_nombre, "; 
      $sql .= "         p.segundo_nombre, p.primer_apellido, p.segundo_apellido, ";
      $sql .= "         c.total_cuenta, su.nombre, ac.fecha_registro, fc.factura_fiscal, ";
      $sql .= "         ac.autorizacion_cr_id ";
      $whr  = "FROM     cuentas c LEFT JOIN( ";
      $whr .= "         SELECT   fc.* ";
      $whr .= "         FROM     fac_facturas ff, fac_facturas_cuentas fc ";
      $whr .= "         WHERE    ff.prefijo = fc.prefijo AND ";
      $whr .= "                  ff.empresa_id = fc.empresa_id AND ";
      $whr .= "                  ff.factura_fiscal = fc.factura_fiscal AND ";
      $whr .= "                  ff.sw_clase_factura = '0' AND ff.estado IN ('0', '1') ";
      $whr .= "         ) AS fc ON fc.numerodecuenta = c.numerodecuenta, pacientes p, ";
      $whr .= "         system_usuarios su, autorizaciones_creditos ac, ingresos i, ";
      $whr .= "         historias_clinicas hc ";
      $whr .= "WHERE    p.paciente_id = hc.paciente_id AND ";
      $whr .= "         p.tipo_id_paciente = hc.tipo_id_paciente AND ";
      $whr .= "         p.paciente_id = i.paciente_id AND ";
      $whr .= "         p.tipo_id_paciente = i.tipo_id_paciente AND ";
      $whr .= "         i.ingreso = c.ingreso AND ";
      $whr .= "         c.numerodecuenta = ac.numerodecuenta AND ";
      $whr .= "         ac.usuario_id = su.usuario_id ";
      if($datos['oculto']=='fecha')
      {
        $whr .= "         AND (ac.fecha_registro>='".$datos['fechaInicio']."' AND ";
        $whr .= "         ac.fecha_registro<='".$datos['fechaFinal']."') ";
      }
      
      if($datos['oculto']=='paciente')
      {
        if($datos['noHistoria']!='')
          $whr .= "       AND hc.historia_numero = ".$datos['noHistoria']." ";
        if($datos['noIdentificacion']!='')
          $whr .= "       AND p.paciente_id = ".$datos['noIdentificacion']." ";
        if($datos['nombres']!='')  
          $whr .= "       AND (p.primer_nombre ILIKE '%".$datos['nombres']."%' OR p.segundo_nombre ILIKE '%".$datos['nombres']."%') ";
        if($datos['apellidos']!='')
          $whr .= "       AND (p.primer_apellido ILIKE '%".$datos['apellidos']."%' OR p.segundo_apellido ILIKE '%".$datos['apellidos']."%') ";       
      }
      
      if($datos['oculto']=='activa')
      {
        $whr .= "         AND sw_activo='1' ";
      }
      
      if($datos['oculto']=='garantia')
      {
        if($datos['garP']=='1' && $datos['garLC']=='0')
          $whr .= "       AND ac.sw_pagare='1' AND ac.sw_letra_cambio='0' ";
      
        if($datos['garP']=='0' && $datos['garLC']=='1')
          $whr .= "       AND ac.sw_pagare='0' AND ac.sw_letra_cambio='1' ";
          
        if($datos['garP']=='1' && $datos['garLC']=='1')
          $whr .= "       AND ac.sw_pagare='1' AND ac.sw_letra_cambio='1' ";
      }
      
      if($datos['oculto']=='codAutori')
      {
        $whr .= "         AND ac.autorizacion_cr_id=".$datos['codAutorizacion']." ";
      }
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
  }
?>