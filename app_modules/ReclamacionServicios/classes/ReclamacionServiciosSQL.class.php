<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ReclamacionServiciosSQL.class.php,v 1.1 2008/01/09 11:23:08 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */
  /**
  * Clase : ReclamacionServiciosSQL
  * 
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */
  
  class ReclamacionServiciosSQL extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function ReclamacionServiciosSQL(){}
    
    /**
    * Funcion donde se verifica el permiso del usuario para el ingreso al modulo
    *
    * @return array $datos vector que contiene la informacion de la consulta del codigo de
    * la empresa y la razon social
    */
    function ObtenerPermisos()
    {
      $sql  = "SELECT   EM.empresa_id AS empresa, ";
      $sql .= "         EM.razon_social AS razon_social, ";
      $sql .= "         EM.digito_verificacion ";
      $sql .= "FROM     userpermisos_reclamacion CP, empresas EM ";
      $sql .= "WHERE    CP.usuario_id = ".UserGetUID()." ";
      $sql .= "         AND CP.empresa_id = EM.empresa_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        //echo "rst ".$rst->fields[0];
        $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    
    /**
    * Funcion donde se consultan los tipos de identificacion
    *
    * @return array $datos vector que contiene la informacion de la consulta de los tipos 
    * de identificacion
    */
    function ConsultarTipoId()
    {
      $sql  = "SELECT    indice_de_orden, tipo_id_paciente, descripcion ";
      $sql .= "FROM      tipos_id_pacientes ";
      $sql .= "ORDER BY  indice_de_orden, tipo_id_paciente, descripcion ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se consultan los tipos de identificacion indicando el id como 
    * parametro de busqueda
    *
    * @param string $tipoId cadena con el id a consultar 
    * @return array $datos vector con la informacion del tipo de identificacion
    */
    function ConsTipoIdFiltro($tipoId)
    {
      $sql  = "SELECT    indice_de_orden, descripcion ";
      $sql .= "FROM      tipos_id_pacientes ";
      $sql .= "WHERE     tipo_id_paciente='".$tipoId."' ";
      $sql .= "ORDER BY  indice_de_orden, descripcion ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion del paciente teniendo en cuenta diferentes 
    * parametros de busqueda
    *
    * @param array $request vector con los datos del request donde se encuentran los
    * parametos de busqueda
    * @param string $pg_siguiente
    * @return array $datos vector que contiene la informacion consultada del paciente
    */
    function ConsultarPacienteFiltro($request, $pg_siguiente)
    { 
      $sql  = "SELECT  p.paciente_id, p.tipo_id_paciente, p.primer_apellido, ";
      $sql .= "        p.segundo_apellido, p.primer_nombre, p.segundo_nombre ";
      $whr  = "FROM    pacientes p ";
      $whr .= "WHERE   TRUE ";
      if($request['tipoId'] != "-1")
        $whr .= "      AND p.tipo_id_paciente = '".$request['tipoId']."' ";
      if($request['noId'])
        $whr .= "      AND p.paciente_id = '".$request['noId']."' ";
      if($request['nombre'])
        $whr .= "      AND (p.primer_nombre ILIKE '%".$request['nombre']."%' OR p.segundo_nombre ILIKE '%".$request['nombre']."%') ";
      if($request['apellido'])
        $whr .= "      AND (p.primer_apellido ILIKE '%".$request['apellido']."%' OR p.segundo_apellido ILIKE '%".$request['apellido']."%')";
        
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr",$pg_siguiente,null,50))
      return false;
    
      $whr1  = "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr.$whr1))
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
    * Funcion donde se consulta la informacion del consecutivo para una fecha determinada
    *
    * @param string $fecha cadena con la fecha que se quiere consultar
    * @return array $datos vector con la informacion del consecutivo
    */
    function ConsultarConsecutivo($fecha, $noForm)
    {
      $sql  = "SELECT  * ";
      $sql .= "FROM    consecutivos ";
      $sql .= "WHERE   fecha='".$fecha."' AND no_formulario='".$noForm."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se almacena la informacion del consecutivo
    *
    * @param string $c cadena con el valor del consecutivo
    * @param string $fecha cadena con el valor de la fecha
    * @return string $c cadena con el valor del consecutivo 
    */
    function IngresarConsecutivo($c, $fecha, $noForm)
    {
      $this->ConexionTransaccion();
      
      $sql  = "INSERT INTO consecutivos( ";
      $sql .= "       consecutivo, ";
      $sql .= "       fecha, ";
      $sql .= "       no_formulario ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$c.", ";
      $sql .= "       '".$fecha."', ";
      $sql .= "       '".$noForm."') ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
        return false;
      }
      
      $rst->Close();
      
      $this->Commit();
      
      return $c;
    }
    /**
    * Funcion donde se actualiza la informacion del consecutivo
    *
    * @param string $c cadena con el valor del consecutivo
    * @param string $fecha cadena con el valor de la fecha
    * @return string $c cadena con el valor del consecutivo actualizado
    */
    function ActualizarConsecutivo($c, $fecha, $noForm)
    {
      $this->ConexionTransaccion();
      
      $sql  = "UPDATE   consecutivos ";
      $sql .= "SET      consecutivo = ".$c." ";
      $sql .= "WHERE    fecha='".$fecha."' AND no_formulario='".$noForm."' ";
      
      if(!$rst1 = $this->ConexionTransaccion($sql))
      {
        return false;
      }
      
      $this->Commit();
      
      return $c;
    }
    /**
    * Funcion donde se consulta la informacion de la empresa indicando el ingreso como
    * parametro de busqueda
    *
    * @param string $ingreso cadena con el valor del ingreso a consultar
    * @return array $datos vector que contiene la informacion de la empresa
    */
    function ConsultarEmpresa($plan_id)
    {
      $sql  = "SELECT  DISTINCT em.razon_social, em.tipo_id_tercero, em.id as id_emp, ";
      $sql .= "        em.codigo_sgsss, ";
      $sql .= "        em.direccion as direccion_emp, em.telefonos as telefonos_emp, ";
      $sql .= "        em.tipo_dpto_id as tipo_dpto_id_emp, ";
      $sql .= "        em.tipo_mpio_id as tipo_mpio_id_emp, ";
      $sql .= "        td.departamento as departamento_emp, pl.plan_id, ";
      $sql .= "        em.empresa_id, tm.municipio as municipio_emp, ";
      $sql .= "        em.indicativo as indicativo_emp, em.digito_verificacion ";
      $sql .= "FROM    empresas em, planes pl, tipo_dptos td, ";
      $sql .= "        tipo_mpios tm ";
      $sql .= "WHERE   pl.plan_id=".$plan_id." ";
      $sql .= "        AND pl.empresa_id = em.empresa_id ";
      $sql .= "        AND em.tipo_mpio_id = tm.tipo_mpio_id ";
      $sql .= "        AND em.tipo_dpto_id = tm.tipo_dpto_id ";
      $sql .= "        AND em.tipo_pais_id = tm.tipo_pais_id ";
      $sql .= "        AND tm.tipo_dpto_id = td.tipo_dpto_id ";
      $sql .= "        AND tm.tipo_pais_id = td.tipo_pais_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion de los ingresos
    *
    * @param string $noId cadena con el valor del numero de identificacion
    * @param string $tipoId cadena con el valor del tipo de identificacion
    * @return array $datos vector con la informacion de los ingresos 
    */
    function ConsIngresosFiltro($noId, $tipoId)//ConsultarIngresos
    {
      $sql  = "SELECT   i.ingreso, i.fecha_ingreso, pl.plan_id ";
      $sql .= "FROM     pacientes p, ingresos i, autorizaciones a, planes pl ";
      $sql .= "WHERE    p.paciente_id = '".$noId."' ";
      $sql .= "         AND p.tipo_id_paciente = '".$tipoId."' ";
      $sql .= "         AND p.paciente_id = i.paciente_id ";
      $sql .= "         AND p.tipo_id_paciente = i.tipo_id_paciente ";
      $sql .= "         AND i.ingreso = a.ingreso AND a.plan_id = pl.plan_id ";
      $sql .= "GROUP BY i.ingreso, i.fecha_ingreso, pl.plan_id ";
      $sql .= "ORDER BY  i.fecha_ingreso DESC ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se consulta la informacion del tercero(pagador) indicando el 
    * ingreso como parametro de busqueda
    *
    * @param string $ingreso cadena con el valor del ingreso
    * @return array $datos vector con la informacion del tercero
    */
    function ConsultarTerceros($plan_id)
    {    
      $sql  = "SELECT  DISTINCT t.tipo_id_tercero, t.tercero_id, t.nombre_tercero, "; 
      $sql .= "        tsg.codigo_sgsss as codigo_sgsss_p ";
      $sql .= "FROM    planes pl, terceros t left join  terceros_sgsss ";
      $sql .= "        tsg on (t.tipo_id_tercero=tsg.tipo_id_tercero AND ";
      $sql .= "        t.tercero_id=tsg.tercero_id) ";
      $sql .= "WHERE   pl.plan_id=".$plan_id." ";
      $sql .= "        AND pl.tipo_tercero_id=t.tipo_id_tercero ";
      $sql .= "        AND pl.tercero_id=t.tercero_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion del paciente indicando el numero y el tipo
    * de identificacion
    *
    * @param string $noId cadena con el valor del numero de identificacion
    * @param string $tipoId cadena con el valor del tipo de identificacion
    * @return array $datos vector con la informacion del pacientes
    */
    function ConsultarPaciente($noId, $tipoId)
    {
      $sql  = "SELECT  p.primer_apellido as primer_apellido_u, ";
      $sql .= "        p.segundo_apellido as segundo_apellido_u, ";
      $sql .= "        p.primer_nombre as primer_nombre_u, ";
      $sql .= "        p.segundo_nombre as segundo_nombre_u, ";
      $sql .= "        p.fecha_nacimiento as fecha_nacimiento_u, ";
      $sql .= "        p.residencia_direccion as residencia_direccion_u, ";
      $sql .= "        p.residencia_telefono as residencia_telefono_u, ";
      $sql .= "        p.tipo_dpto_id as tipo_dpto_id_u, ";
      $sql .= "        p.tipo_mpio_id as tipo_mpio_id_u, ";
      $sql .= "        td.departamento as departamento_u, ";
      $sql .= "        tm.municipio as municipio_u, ";
      $sql .= "        p.celular_telefono, ";
      $sql .= "        p.email ";
      $sql .= "FROM    pacientes p, tipo_mpios tm, tipo_dptos td ";
      $sql .= "WHERE   p.paciente_id='".$noId."' AND p.tipo_id_paciente='".$tipoId."' ";
      $sql .= "        AND p.tipo_mpio_id=tm.tipo_mpio_id ";
      $sql .= "        AND p.tipo_dpto_id=tm.tipo_dpto_id ";
      $sql .= "        AND p.tipo_pais_id=tm.tipo_pais_id ";
      $sql .= "        AND tm.tipo_dpto_id = td.tipo_dpto_id ";
      $sql .= "        AND tm.tipo_pais_id = td.tipo_pais_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion del usuario
    *
    * @return array $datos vector que contiene la informacion del usuario
    */
    function ConsultarUsuario($usuario)
    {
      if(!$usuario) $usuario = UserGetUID();
      
      $sql  = "SELECT usuario_id, ";
      $sql .= "       nombre as nombre_us,  ";
      $sql .= "       descripcion as descripcion_us, ";
      $sql .= "       telefono as telefono_us,  ";
      $sql .= "       tel_celular as tel_celular_us, ";
      $sql .= "       indicativo as indicativo_us,  ";
      $sql .= "       extension as extension_us ";
      $sql .= "FROM   system_usuarios ";
      $sql .= "WHERE  usuario_id = ".$usuario." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan los tipos de cobertura en salud
    *
    * @return array $datos vector que contiene la informacion de los tipos de cobertura en
    * salud 
    */
    function ConsultarCoberturasSalud()
    {
      $sql  = "SELECT cobertura_id, ";
      $sql .= "       descripcion, ";
      $sql .= "       regimen_res_3047 ";
      $sql .= "FROM   coberturas_salud ";
      $sql .= "WHERE  sw_activo='1' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se consulta el regimen(cobertura en salud) asociado a un ingreso
    *
    * @param integer $ingreso entero con el valor del ingreso
    * @return array $datos vector con la informacion de la consulta
    */    
    function ConsCoberturaSalud($ingreso)
    {
      $sql  = "SELECT r.regimen_id, ";
      $sql .= "       r.regimen_descripcion, ";
      $sql .= "       r.regimen_res_3047 ";
      $sql .= "FROM   autorizaciones a, ";
      $sql .= "       planes pl, ";
      $sql .= "       tipos_cliente tc,";
      $sql .= "       regimenes r ";
      $sql .= "WHERE  a.ingreso = ".$ingreso." ";
      $sql .= "AND    a.plan_id = pl.plan_id ";
      $sql .= "AND    pl.tipo_cliente = tc.tipo_cliente ";
      $sql .= "AND    tc.regimen_id = r.regimen_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    
    /**
    * Funcion que permite consultar la informacion de las coberturas en salud, 
    * indicando el id de la cobertura como parametro de busqueda
    *
    * @param integer $cobertura valor del id de la cobertura
    * @return array $datos vector con la informacion de las coberturas en salud
    */
    function ConsCobertSaludFiltro($cobertura)
    {
      $sql  = "SELECT  descripcion ";
      $sql .= "FROM    coberturas_salud ";
      $sql .= "WHERE   cobertura_id=".$cobertura." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan los tipos de inconsistencias
    *
    * @return array $datos vector con la informacion del tipo de inconsistencia
    */
    function ConsTiposInconsistencias()
    {
      $sql  = "SELECT  inconsistencia_id, descripcion ";
      $sql .= "FROM    tipos_inconsistencias ";
      $sql .= "WHERE   sw_activo='1' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se consulta la informacion de los tipos de inconsistencias, indicando 
    * el id de la inconsistencia como parametro de busqueda
    *
    * @param integer $inconsist valor del id de la inconsistencia
    * @return array $datos vector con la informacion del tipo de insonsistencia
    */
    function ConsTiposInconsistFiltro($inconsist)
    {
      $sql  = "SELECT  descripcion ";
      $sql .= "FROM    tipos_inconsistencias ";
      $sql .= "WHERE   inconsistencia_id=".$inconsist." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se almacena la informacion de las inconsistencias
    * @param array $request vector con la informacion del request
    * @return integer $request['consec'] valor del id de la inconsistencia
    */
    function IngresarInconsistencias($request)
    {
      $this->ConexionTransaccion();
    
      $sql  = "INSERT INTO inconsistencias_pagador( ";
      $sql .= "       num_informe, ";
      $sql .= "       fecha, ";
      $sql .= "       hora, ";
      $sql .= "       inconsistencia_id, ";
      $sql .= "       sw_primer_apellido, ";
      $sql .= "       sw_segundo_apellido, ";
      $sql .= "       sw_primer_nombre, ";
      $sql .= "       sw_segundo_nombre, ";
      $sql .= "       sw_tipo_id_paciente, ";
      $sql .= "       sw_paciente_id, ";
      $sql .= "       sw_fecha_nacimiento, ";
      $sql .= "       primer_apellido, ";
      $sql .= "       segundo_apellido, ";
      $sql .= "       primer_nombre, ";
      $sql .= "       segundo_nombre, ";
      $sql .= "       tipo_id_paciente, ";
      $sql .= "       paciente_id, ";
      $sql .= "       fecha_nacimiento, ";
      $sql .= "       tipo_id_paciente_u, ";
      $sql .= "       paciente_id_u, "; 
      $sql .= "       primer_apellido_u, ";
      $sql .= "       segundo_apellido_u, "; 
      $sql .= "       primer_nombre_u, ";
      $sql .= "       segundo_nombre_u, ";
      $sql .= "       fecha_nacimiento_u, ";
      $sql .= "       observaciones, ";
      $sql .= "       usuario_id, ";
      $sql .= "       ingreso, ";
      $sql .= "       plan_id, ";
      $sql .= "       empresa_id ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$request['consec'].", ";
      $sql .= "       '".$request['fecha']."', ";
      $sql .= "       '".$request['hora']."', ";
      $sql .= "       ".$request['inconsistencia'].", ";
      if($request['chpApellido'])
        $sql .= "     '1', ";
      else
        $sql .= "     '0', ";
      if($request['chsApellido'])
        $sql .= "     '1', ";
      else
        $sql .= "     '0', ";
      if($request['chpNombre'])
        $sql .= "     '1', ";
      else
        $sql .= "     '0', ";
      if($request['chsNombre'])
        $sql .= "     '1', ";
      else
        $sql .= "     '0', ";
      if($request['chtDoc'])
        $sql .= "     '1', ";
      else
        $sql .= "     '0', ";
      if($request['chnDoc'])
        $sql .= "     '1', ";
      else
        $sql .= "     '0', ";
      if($request['chfNac'])
        $sql .= "     '1', ";
      else
        $sql .= "     '0', ";
      $sql .= "       '".strtoupper($request['txtpApellido'])."', ";
      $sql .= "       '".strtoupper($request['txtsApellido'])."', ";
      $sql .= "       '".strtoupper($request['txtpNombre'])."', ";
      $sql .= "       '".strtoupper($request['txtsNombre'])."', ";
      if($request['seltDoc'])
        $sql .= "     '".$request['seltDoc']."', ";
      else
        $sql .= "     NULL, ";
      $sql .= "       '".$request['txtnDoc']."', ";
      if($request['txtfNac'])
        $sql .= "     '".$request['txtfNac']."', ";
      else
        $sql .= "     NULL, ";
      
      $sql .= (($request['chtDoc'])? "'".$request['tipoId_u']."', ":"NULL, ");
      $sql .= (($request['chnDoc'])? "'".$request['noId_u']."', ":"NULL, ");
      $sql .= (($request['chpApellido'])? "'".strtoupper($request['primer_apellido_u'])."', " : "NULL, ");
      $sql .= (($request['chsApellido'])? "'".strtoupper($request['segundo_apellido_u'])."', ": "NULL, ");
      $sql .= (($request['chpNombre'])? "'".strtoupper($request['primer_nombre_u'])."', ":"NULL, ");
      $sql .= (($request['chsNombre'])? "'".strtoupper($request['segundo_nombre_u'])."', ":"NULL, ");
      $sql .= (($request['chfNac'])? "'".$request['fecha_nacimiento_u']."', ":"NULL, ");
      $sql .= "       '".$request['observaciones']."', ";
      $sql .= "       ".$request['usuario_id'].", ";
      $sql .= "       ".$request['ingreso'].", ";
      $sql .= "       ".$request['plan_id'].", ";
      $sql .= "       '".$request['empresa_id']."' ";
      $sql .= "       ) ";
            
      if(!$rst = $this->ConexionTransaccion($sql))
      {        
        return false;
      }
      
      $this->Commit();
      
      return $request['consec'];
    }
    /**
    * Funcion donde se consultan los tipos de atencion
    *
    * @return array $datos vector con la informacion de los tipos de atencion
    */
    function ConsOrigenAtencion()
    {
      $sql  = "SELECT  * ";
      $sql .= "FROM    hc_tipos_atencion ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se consulta la informacion de la clasificacion Triage
    *
    * @return array $datos vector con la informacion de la clasificacion Triage
    */
    function ConsNivelesTriages()
    {
      $sql  = "SELECT  * ";
      $sql .= "FROM    niveles_triages ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se consulta la informacion de los ingresos a urgencias
    *
    * @param string $noId cadena con el valor del numero de identificacion
    * @param string $tipoId cadena con el valor del tipo de identificacion
    * @return array $datos vector con la informacion de los ingresos 
    */
    function ConsIngresosUrgFiltro($noId, $tipoId)//ConsultarIngresosUrg
    {
   // $this->debug=true;
      $sql  = "SELECT   i.ingreso, i.fecha_ingreso, pl.plan_id ";
      $sql .= "FROM     pacientes p, ingresos i, autorizaciones a, planes pl, ";
      $sql .= "         hc_ordenes_medicas hom, hc_evoluciones he, profesionales pr, ";
      $sql .= "         departamentos d ";
      $sql .= "WHERE    p.paciente_id = '".$noId."' ";
      $sql .= "         AND p.tipo_id_paciente = '".$tipoId."' ";
      $sql .= "         AND i.via_ingreso_id = '1' ";
      $sql .= "         AND p.paciente_id = i.paciente_id ";
      $sql .= "         AND p.tipo_id_paciente = i.tipo_id_paciente ";
      $sql .= "         AND i.ingreso = a.ingreso AND a.plan_id = pl.plan_id ";
      $sql .= "         AND i.ingreso = hom.ingreso ";
      $sql .= "         AND hom.evolucion_id = he.evolucion_id ";
      $sql .= "         AND he.usuario_id = pr.usuario_id ";
      $sql .= "         AND pr.tipo_profesional IN ('1','2') ";
      $sql .= "         AND he.departamento = d.departamento ";
      $sql .= "         AND d.servicio IN ('4') ";
      $sql .= "GROUP BY i.ingreso, i.fecha_ingreso, pl.plan_id ";
      $sql .= "ORDER BY  i.fecha_ingreso DESC ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se consulta la descripcion del motivo de ingreso a urgencias
    *
    * @param integer $ingreso entero con el valor del id del ingreso
    * @return array $datos vector con la informacion del motivo de ingreso a urgencias
    */
    function ConsIngresoUrg($ingreso)
    {
      $sql  = "SELECT i.ingreso, hmc.descripcion as desc_motivo, ";
      $sql .= "       i.fecha_ingreso ";
      $sql .= "FROM   ingresos i,";
      $sql .= "       hc_motivo_consulta hmc ";
      $sql .= "WHERE  i.ingreso = ".$ingreso." ";
      $sql .= "AND    i.ingreso = hmc.ingreso ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la descripcion del destino del paciente
    *
    * @param integer $ingreso entero con el valor del id del ingreso
    *
    * @return mixed
    */
    function ObtenerDestinoPaciente($ingreso)
    {
     // $this->debug=true;
      $sql  = "SELECT DP.destino_paciente_id, ";
      $sql .= "       DP.destino_paciente_descripcion  ";
      $sql .= "FROM   pacientes_urgencias PU, ";
      $sql .= "       historias_clinicas_tipos_cierres HT, ";
      $sql .= "       destino_pacientes_3047 DP  ";
      $sql .= "WHERE  PU.ingreso = ".$ingreso."  ";
      $sql .= "AND    PU.historia_clinica_tipo_cierre_id = HT.historia_clinica_tipo_cierre_id  ";
      $sql .= "AND    HT.destino_paciente_id = DP.destino_paciente_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion de la causa externa de ingreso
    *
    * @param integer $ingreso entero con el valor del id del ingreso
    * @return array $datos vector con la informacion de la clasificacion triage
    */
    function ConsultarCausaIng($ingreso)
    {
      $sql  = "SELECT i.causa_externa_id,";
      $sql .= "       i.via_ingreso_id, ";
      $sql .= "       i.comentario, ";
      $sql .= "       ce.causa_externa_id AS origen_atencion ";
      $sql .= "FROM   ingresos i, causas_externas ce ";
      $sql .= "WHERE  i.ingreso = ".$ingreso." ";
      $sql .= "AND    ce.causa_externa_id = i.causa_externa_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la clasificacion Triage
    * @param integer $ingreso entero con el valor del id del ingreso
    * @return array $datos vector con la informacion de la clasificacion triage
    */
    function ConsultarTriageIng($ingreso)
    {
      $sql  = "SELECT t.triage_id, ";
      $sql .= "       t.nivel_triage_id, ";
      $sql .= "       nt.descripcion, ";
      $sql .= "       nt.color as triage ";
      $sql .= "FROM   triages t,  ";
      $sql .= "       niveles_triages nt,  ";
      $sql .= "       ingresos i ";
      $sql .= "WHERE  i.ingreso = ".$ingreso." ";
      $sql .= "AND    i.ingreso = t.ingreso ";
      $sql .= "AND    t.nivel_triage_id = nt.nivel_triage_id ";     
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;    
    }
    /**
    * Funcion donde se consulta la informacion del lugar de remision de un paciente
    * 
    * @param integer $ingreso entero con el valor del id del ingreso
    * @return array $datos vector con la informacion del tipo de insonsistencia
    */
    function ConsPacienteRemitido($ingreso)
    {
     //$this->debug=true;
      $sql  = "SELECT    pr.paciente_remitido_id, pr.centro_remision, ";
      $sql .= "          cr.descripcion as nomb_rem, ";
      $sql .= "          tm.municipio as municipio_pr, ";
      $sql .= "          td.departamento as departamento_pr, ";
      $sql .= "          tm.tipo_dpto_id as tipo_dpto_id_pr, ";
      $sql .= "          tm.tipo_mpio_id as tipo_mpio_id_pr, ";
      $sql .= "          i.fecha_registro ";
      $sql .= "FROM      ingresos i left join pacientes_remitidos pr on ";
      $sql .= "          (i.ingreso = pr.ingreso) left join centros_remision cr on ";
      $sql .= "          (pr.centro_remision = cr.centro_remision) left join tipo_mpios ";
      $sql .= "          tm on (cr.tipo_pais_id = tm.tipo_pais_id AND ";
      $sql .= "          cr.tipo_dpto_id = tm.tipo_dpto_id AND ";
      $sql .= "          cr.tipo_mpio_id = tm.tipo_mpio_id) left join tipo_dptos td ";
      $sql .= "          on (tm.tipo_pais_id = td.tipo_pais_id AND ";
      $sql .= "          tm.tipo_dpto_id = td.tipo_dpto_id) ";
      $sql .= "WHERE     pr.ingreso = ".$ingreso." ";
          
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos; 
    }
    /**
    * Funcion donde se consulta la informacion de los diagnosticos
    * @param integer $ingreso entero con el valor del id del ingreso
    * @param integer $usuario_id entero con el valor del id del usuario
    * @return array $datos vector con la informacion de los diagnosticos
    */
    function ConsultarDiagnosticos($ingreso, $usuario_id)
    {
      $sql  = "SELECT i.ingreso, ";
      $sql .= "       he.evolucion_id, ";
      $sql .= "       hdi.sw_principal, ";
      $sql .= "       hdi.tipo_diagnostico_id, ";
      $sql .= "       i.tipo_id_paciente, ";
      $sql .= "       i.paciente_id, ";
      $sql .= "       hdi.descripcion, ";
      $sql .= "       d.diagnostico_nombre, ";
      $sql .= "       d.diagnostico_id, ";
      $sql .= "       he.usuario_id ";
      $sql .= "FROM   ingresos i, ";
      $sql .= "       hc_evoluciones he, ";
      $sql .= "       hc_diagnosticos_ingreso hdi, ";
      $sql .= "       diagnosticos d ";
      $sql .= "WHERE  i.ingreso = ".$ingreso." AND i.ingreso = he.ingreso ";
      if(!empty($usuario_id))
        $sql .= "        AND he.usuario_id = ".$usuario_id." ";
      $sql .= "          AND he.evolucion_id = hdi.evolucion_id ";
      $sql .= "          AND hdi.tipo_diagnostico_id = d.diagnostico_id ";
      $sql .= "ORDER BY  hdi.sw_principal DESC ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se almacena la informacion del reporte generado para la atencion 
    * inicial de un paciente a urgencias
    * @param array $request vector con la informacion del request
    * @return integer $request['consec'] valor del id de la inconsistencia
    */
    function IngRepAtencionUrgencias($request)
    {
      $this->ConexionTransaccion();
    
      $sql  = "INSERT INTO atencion_inicial_urgencias";
      $sql .= "   ( ";
      $sql .= "       num_atencion, ";
      $sql .= "       fecha, ";
      $sql .= "       hora, ";
      $sql .= "       usuario_id, ";
      $sql .= "       ingreso, ";
      $sql .= "       plan_id, ";
      $sql .= "       empresa_id, ";
      $sql .= "       tipo_id_paciente, ";
      $sql .= "       paciente_id ";
      $sql .= "   )";
      $sql .= "VALUES";
      $sql .= "( ";
      $sql .= "      ".$request['consec'].", ";
      $sql .= "     '".$request['fecha']."', ";
      $sql .= "     '".$request['hora']."', ";
      $sql .= "      ".$request['usuario_id'].", ";
      $sql .= "      ".$request['ingreso'].", ";
      $sql .= "      ".$request['plan_id'].", ";
      $sql .= "     '".$request['empresa_id']."', ";
      $sql .= "     '".$request['tipoId_u']."', ";
      $sql .= "     '".$request['noId_u']."' ";
      $sql .= ") ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
      
      $this->Commit();
      
      return $request['consec'];
    }
    /**
    * Funcion donde se consulta la informacion de los ingresos que tienen cuenta en estado
    * (0, 1 y 2)
    *
    * @param string $noId cadena con el numero de identificacion del paciente
    * @param string $tipoId cadena con el tipo de identificacion del paciente
    * @return array $datos vector con la informacion de los ingresos
    */
    function ConsIngresosAutoriza($noId, $tipoId)
    {
      //$this->debug=true;    
      $sql  = "SELECT i.ingreso, ";
      $sql .= "       i.fecha_ingreso,";
      $sql .= "       c.plan_id ";
      $sql .= "FROM   pacientes p, ";
      $sql .= "       ingresos i INNER JOIN cuentas c ";
      $sql .= "       ON (i.ingreso = c.ingreso) ";
      $sql .= "       LEFT JOIN hc_evoluciones he ";
      $sql .= "       ON (i.ingreso = he.ingreso) ";
      $sql .= "			  LEFT JOIN hc_os_solicitudes hos ";
      $sql .= "       ON (he.evolucion_id = hos.evolucion_id), ";
      $sql .= "       cuentas_estados ce ";
      $sql .= "WHERE     p.paciente_id = '".$noId."' ";
      $sql .= "          AND p.tipo_id_paciente = '".$tipoId."' ";
      $sql .= "          AND i.estado IN ('0','1') ";
      $sql .= "          AND p.paciente_id = i.paciente_id ";
      $sql .= "          AND p.tipo_id_paciente = i.tipo_id_paciente ";
      $sql .= "          AND c.estado IN ('0', '1', '2', '3') ";
      $sql .= "			 AND c.estado = ce.estado ";
      $sql .= "GROUP BY  i.ingreso, i.fecha_ingreso, c.plan_id ";
      $sql .= "ORDER BY  i.fecha_ingreso DESC ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se consulta la informacion de las ordenes de servicio
    *
    * @param integer $ingreso entero con el valor del id del ingreso
    * @return array $datos vector con el resultado de la consulta
    */
    function ConsultarOrdenServicio($ingreso)
    {
      //$this->debug=true;
      
      $sql  = "SELECT    hos.hc_os_solicitud_id, he.usuario_id, su.nombre, c.cargo, " ;
      $sql .= "			     c.descripcion as desc_cargo, d.descripcion as desc_departamento, hos.plan_id, ";
      $sql .= "          s.servicio, s.descripcion as desc_servicio ";
      $sql .= "FROM	     ingresos i, hc_evoluciones he, hc_os_solicitudes hos, ";
      $sql .= "			     cups c, system_usuarios su, departamentos d, servicios s ";
      $sql .= "WHERE	   i.ingreso = ".$ingreso." ";
      $sql .= "			     AND i.ingreso = he.ingreso ";
      $sql .= "			     AND he.evolucion_id = hos.evolucion_id ";
      $sql .= "			     AND hos.cargo = c.cargo ";
      $sql .= "			     AND hos.sw_estado ='1' ";
      $sql .= "			     AND he.usuario_id = su.usuario_id ";
      $sql .= "			     AND he.departamento = d.departamento ";
      $sql .= "			     AND d.servicio = s.servicio ";
      $sql .= "ORDER BY  hos.hc_os_solicitud_id, he.usuario_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    
    function ConsultarAutorizaEscrita($autorizacion)
    {
      $sql  = "SELECT	a.autorizacion, a.descripcion_autorizacion, a.observaciones ";
      $sql .= "FROM		autorizaciones a ";
      $sql .= "WHERE	a.autorizacion=".$autorizacion." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se consulta la informacion de las ordenes de servicio
    *
    * @param integer $ingreso entero con el valor del id del ingreso
    * @param integer $orden_id entero con el valor del id de la orden de servicio
    * @return array $datos vector con el resultado de la consulta
    */
    function ConsultarCargosOrden($ingreso, $usuario_id,$cargos)
    {      
      
    
      $sql  = "SELECT hos.hc_os_solicitud_id, ";
      $sql .= "       hos.cantidad, ";
      $sql .= "       he.usuario_id, ";
      $sql .= "       su.nombre, ";
      $sql .= "       c.cargo, " ;
      $sql .= "			  c.descripcion as desc_cargo, ";
      $sql .= "       d.descripcion as desc_departamento, ";
      $sql .= "       d.departamento, ";
      $sql .= "       hos.plan_id, ";
      $sql .= "       s.servicio,  ";
      $sql .= "       s.descripcion as desc_servicio ";
      $sql .= "FROM	  ingresos i, ";
      $sql .= "       hc_evoluciones he, ";
      $sql .= "       hc_os_solicitudes hos, ";
      $sql .= "			  cups c,  ";
      $sql .= "       system_usuarios su,  ";
      $sql .= "       departamentos d,  ";
      $sql .= "       servicios s ";
      $sql .= "WHERE	i.ingreso = ".$ingreso." ";
      $sql .= "AND    su.usuario_id = ".$usuario_id." ";
      $sql .= "AND    i.ingreso = he.ingreso ";
      $sql .= "AND    he.evolucion_id = hos.evolucion_id ";
      $sql .= "AND    hos.cargo = c.cargo ";
      $sql .= "AND    hos.sw_estado ='1' ";
      $sql .= "AND    he.usuario_id = su.usuario_id ";
      $sql .= "AND    he.departamento = d.departamento ";
      $sql .= "AND    d.servicio = s.servicio ";
      if(!empty($cargos))
      {
        $v = "";
        foreach($cargos as $key => $dtl)
          ($v == "")? $v = "'".$dtl."'": $v .= ",'".$dtl."'";
        
        $sql .= "AND   c.cargo IN (".$v.") ";
      }
      $sql .= "ORDER BY  hos.hc_os_solicitud_id, he.usuario_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion que permite consultar la informacion del servicio solicitado para el paciente
    * 
    * @param integer $ingreso entero con el valor del id del ingreso
    * @return array $datos vector con la informacion del servicio
    */
    function ConsServicioAtencion($ingreso)
    {
      $sql  = "SELECT    eeta.tipo_atencion_estacion_id, ";
      $sql .= "          eeta.descripcion as desc_servicio ";
      $sql .= "FROM      cuentas c, estaciones_enfermeria_ingresos_realizados eeir, ";
      $sql .= "          estaciones_enfermeria ee, ";
      $sql .= "          estaciones_enfermeria_tipos_atencion eeta ";
      $sql .= "WHERE     c.ingreso = ".$ingreso." ";
      $sql .= "          AND c.numerodecuenta = eeir.numerodecuenta ";
      $sql .= "          AND eeir.estacion_id = ee.estacion_id ";
      $sql .= "          AND ee.tipo_atencion_estacion_id = ";
      $sql .= "          eeta.tipo_atencion_estacion_id ";

      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion que permite consultar la informacion de la ubicacion del paciente 
    * (via_ingreso_nombre) y la cama en la que se encuentra
    *
    * @param integer $ingreso valor del id del ingreso
    * @return array $datos vector con la informacion de la ubicacion del paciente
    */
    function ConsultarViaIngresoCama($ingreso)
    {
      $sql  = "SELECT    mh.cama, mh.fecha_ingreso as fecha_ing_cama, ";
      $sql .= "          vi.via_ingreso_nombre ";
      $sql .= "FROM      ingresos i LEFT JOIN movimientos_habitacion mh ";
      $sql .= "          ON (i.ingreso = mh.ingreso) LEFT JOIN vias_ingreso vi ";
      $sql .= "          ON (i.via_ingreso_id = vi.via_ingreso_id) ";
      $sql .= "WHERE     i.ingreso = ".$ingreso." ";
      $sql .= "ORDER BY  mh.fecha_ingreso DESC ";
      $sql .= "LIMIT     1 ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion que permite consultar el consecutivo correspondiente a un año y un formulario
    * indicado
    *
    * @param string $anyo cadena con el valor del año
    * @param string $noForm cadena con el valor del numero del formulario
    * @return array $datos vector con la informacion del consecutivo
    */
    function ConsultarConsecutivoAnyo($anyo, $noForm)
    {
      $sql  = "SELECT  * ";
      $sql .= "FROM    consecutivos ";
      $sql .= "WHERE   TO_CHAR(fecha,'YYYY')='".$anyo."' ";
      $sql .= "        AND no_formulario='".$noForm."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se actualiza la informacion del consecutivo
    *
    * @param string $c cadena con el valor del consecutivo
    * @param string $anyo cadena con el valor del año
    * @param string $noForm cadena con el numero de formulario
    * @return string $c cadena con el valor del consecutivo actualizado
    */
    function ActualizarConsecutivoAnyo($c, $anyo, $noForm)
    {
    
      $this->ConexionTransaccion();
      
      $sql  = "UPDATE   consecutivos ";
      $sql .= "SET      consecutivo = ".$c." ";
      $sql .= "WHERE    TO_CHAR(fecha,'yyyy')='".$anyo."' AND no_formulario='".$noForm."' ";
      
      if(!$rst1 = $this->ConexionTransaccion($sql))
      {
        return false;
      }
      
      $this->Commit();
      
      return $c;
    }
    /**
    * Funcion donde se consulta la informacion de los profesionales relacionados a un
    * ingreso
    *
    * @param integer $ingreso entero con el id del ingreso
    * @return array $datos vector con la informacion de los profesionales
    */
    function ConsProfesionalIngreso($ingreso)
    {
      $sql  = "SELECT    he.usuario_id, p.nombre as nombre_us ";
      $sql .= "FROM      hc_evoluciones he, hc_os_solicitudes hos, profesionales p ";
      $sql .= "WHERE     he.ingreso = ".$ingreso." ";
      $sql .= "          AND he.evolucion_id = hos.evolucion_id ";
      $sql .= "          AND he.usuario_id = p.usuario_id ";
      $sql .= "GROUP BY  he.usuario_id, p.nombre ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se consulta la informacion de los cargos relacionados a un ingreso y a 
    * un profesional
    *
    * @param integer $ingreso entero con el id del ingreso
    * @param integer $usuario_id entero con el id del profesional
    * @return array $datos vector con la informacion de los cargos
    */
    function ConsCargosProfesional($ingreso, $usuario_id)
    {
      $sql  = "SELECT    he.evolucion_id, hos.cargo, hos.cantidad, c.descripcion as ";
      $sql .= "          desc_cargo ";
      $sql .= "FROM      hc_evoluciones he, hc_os_solicitudes hos, cups c ";
      $sql .= "WHERE     he.ingreso = ".$ingreso." ";
      $sql .= "          AND he.usuario_id = ".$usuario_id." ";
      $sql .= "          AND he.evolucion_id = hos.evolucion_id ";
      $sql .= "          AND hos.cargo = c.cargo ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se consulta la informacion de los profesionales
    * 
    * @param integer $ingreso entero con el id del ingreso
    * @param integer $usuario_id entero con el id del profesional
    * @return array $datos vector con la informacion de los profesionales
    */
    function ConsTipoProfesFiltro($ingreso, $usuario_id)
    {
      $sql  = "SELECT    p.nombre as nomb_prof, tp.descripcion as desc_prof, ";
      $sql .= "          su.indicativo as indicativo_prof, su.telefono as tel_prof, ";
      $sql .= "          su.extension as extencion_prof, su.tel_celular as tel_cel_prof ";
      $sql .= "FROM      hc_evoluciones he, profesionales p, tipos_profesionales tp, ";
      $sql .= "          system_usuarios su ";
      $sql .= "WHERE     he.ingreso = ".$ingreso." ";
      $sql .= "          AND he.usuario_id = ".$usuario_id." ";
      $sql .= "          AND he.usuario_id = p.usuario_id ";
      $sql .= "          AND p.tipo_profesional = tp.tipo_profesional ";
      $sql .= "          AND he.usuario_id = su.usuario_id ";
      $sql .= "GROUP BY  p.nombre, tp.descripcion, su.indicativo, su.telefono, ";
      $sql .= "          su.extension, su.tel_celular ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }    
    /**
    * Funcion donde se almacena la informacion del reporte generado manualmente para la solicitud 
    * de autorizacion de servicios de salud
    *
    * @param array $request vector con la informacion del request
    * @return integer $request['consec'] valor del id de la solicitud
    */
    function IngSolicitudAutorizaServ($request)
    {
      // $this->debug=true;
      $this->ConexionTransaccion();
     
      $sql  = "INSERT INTO solicitud_autorizacion_serv( ";
      $sql .= "       numero_solicitud, ";
      $sql .= "       fecha, ";
      $sql .= "       hora, ";
      $sql .= "       usuario_id, ";
      $sql .= "       estado, ";
      $sql .= "       solicitud_manual, ";
      $sql .= "       tipo_id_paciente, ";
      $sql .= "       paciente_id, ";
      $sql .= "       plan_id, ";
      $sql .= "       ingreso, ";
      $sql .= "       profesional_id, ";
      $sql .= "       prioridad, ";
      $sql .= "       tipo_servicio, ";
      $sql .= "       origen_atencion_id, ";
      $sql .= "       empresa_id ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$request['consec'].", ";
      $sql .= "       '".$request['fecha']."', ";
      $sql .= "       '".$request['hora']."', ";
      $sql .= "       ".UserGetUID().", ";
      $sql .= "       '1', ";
      if($request['solicitud']=="manual")
        $sql .= "     '1', ";
      else
        $sql .= "     '0', ";
      $sql .= "       '".$request['tipoId_u']."', ";
      $sql .= "       '".$request['noId_u']."', ";
      $sql .= "       ".$request['plan_id'].", ";
      if($request['ingreso'])
        $sql .= "     ".$request['ingreso'].", ";
      else
        $sql .= "     NULL, ";
        
      if($request['usuario_id'])
        $sql .= "     ".$request['usuario_id'].", ";
      else
        $sql .= "   ".UserGetUID().", ";
      $sql .= "       '".$request['prioridad_servicio']."',";
      $sql .= "       '".$request['tipo_servicio']."',";
      $sql .= "       '".$request['origen_atencion']."', ";
      $sql .= "       '".$request['empresa_id']."' ";
      $sql .= ")";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {        
        return false;
      }
      
      $this->ConexionTransaccion();
      if($request['solicitud']=="manual")
      {
        foreach($request['cargos'] as $indice => $valor)
        {
          $sql1  = "INSERT INTO solicitud_autorizacion_cargos( ";
          $sql1 .= "       cargo, ";
          $sql1 .= "       numero_solicitud, ";
          $sql1 .= "       fecha, ";
          $sql1 .= "       servicio, ";
          $sql1 .= "       ubicacion, ";
          $sql1 .= "       cantidad, ";
          $sql1 .= "       hc_os_solicitud_id ";
          $sql1 .= ")VALUES( ";
          $sql1 .= "       '".$valor['cargo']."', ";
          $sql1 .= "       ".$request['consec'].", ";
          $sql1 .= "       '".$request['fecha']."', ";
          $sql1 .= "       '".$request['servicio']."', ";
          $sql1 .= "       '".$request['departamento']."', ";
          $sql1 .= "       ".$valor['cantidad'].", ";
          $sql1 .= "       NULL ";
          $sql1 .= ")";
          
          if(!$rst1 = $this->ConexionTransaccion($sql1))
          {
            return false;
          }
        }
      }
      else
      {
        $cant_cg = count($request['cargos']);
        for($i=0; $i<$cant_cg; $i++)
        {          
          $sql1  = "INSERT INTO solicitud_autorizacion_cargos( ";
          $sql1 .= "       cargo, ";
          $sql1 .= "       numero_solicitud, ";
          $sql1 .= "       fecha, ";
          $sql1 .= "       servicio, ";
          $sql1 .= "       ubicacion, ";
          $sql1 .= "       cantidad, ";
          $sql1 .= "       hc_os_solicitud_id ";
          $sql1 .= ")VALUES( ";
          $sql1 .= "       '".$request['cargos'][$i]['cargo']."', ";
          $sql1 .= "       ".$request['consec'].", ";
          $sql1 .= "       '".$request['fecha']."', ";
          $sql1 .= "       '".$request['cargos'][$i]['servicio']."', ";
          $sql1 .= "       '".$request['cargos'][$i]['departamento']."', ";
          $sql1 .= "       '".$request['cargos'][$i]['cantidad']."', ";
          if($request['cargos'][$i]['hc_os_solicitud_id'])
            $sql1 .= "     ".$request['cargos'][$i]['hc_os_solicitud_id']." ";
          else
            $sql1 .= "     NULL ";
          $sql1 .= ")";
          
          if(!$rst1 = $this->ConexionTransaccion($sql1))
          {
            return false;
          }
        }
        
      }

      $this->Commit();      
      
      return $request['consec'];
    }
    /**
    * Funcion donde se consulta la informacion de los planes
    *
    * @return array $datos vector que contiene la informacion de los planes
    */
    function ConsultarPlanes()
    {
      $sql  = "SELECT	plan_id, plan_descripcion, tercero_id, tipo_tercero_id ";
      $sql .= "FROM 	planes ";
      $sql .= "WHERE 	fecha_final >= now() ";
      $sql .= "         AND	estado=1 ";
      $sql .= "         AND	fecha_inicio <= now() ";
      $sql .= "ORDER BY plan_descripcion ";
    
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se consulta la informacion de los cargos
    *
    * @return array $datos vector que contiene la informacion de los cargos
    */
    function ConsultarSolicitudCargos()
    {
      $sql  = "SELECT    sc.cargo, c.descripcion as desc_cargo, s.servicio, s.descripcion as desc_servicio, ";
      $sql .= "          d.departamento, d.descripcion as desc_depto ";
      $sql .= "FROM      solicitud_cargos sc, cups c, servicios s, departamentos d ";
      $sql .= "WHERE     sc.cargo = c.cargo ";
      $sql .= "          AND sc.servicio = s.servicio ";
      $sql .= "          AND sc.departamento = d.departamento ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se consulta la informacion del cargo indicando el codigo del cargo como
    * parametro de busqueda
    *
    * @param string $cargo cadena con el valor del cargo a consultar
    * @return array $datos vector que contiene la informacion del cargo
    */
    function ConsSolicitudCargosFiltro($cargo)
    {
      $sql  = "SELECT    sc.cargo, c.descripcion as desc_cargo, s.servicio, s.descripcion as desc_servicio, ";
      $sql .= "          d.departamento, d.descripcion as desc_depto ";
      $sql .= "FROM      solicitud_cargos sc, cups c, servicios s, departamentos d ";
      $sql .= "WHERE     sc.cargo = '".$cargo."' AND sc.cargo = c.cargo ";
      $sql .= "          AND sc.servicio = s.servicio ";
      $sql .= "          AND sc.departamento = d.departamento ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion de la empresa indicando el plan como
    * parametro de busqueda
    *
    * @param integer $plan_id valor del plan a consultar
    * @return array $datos vector que contiene la informacion de la empresa
    */
    function ConsultarEmpresaManual($plan_id)
    { 
      $sql  = "SELECT  em.razon_social, em.tipo_id_tercero, em.id as id_emp, ";
      $sql .= "        em.codigo_sgsss, ";
      $sql .= "        em.direccion as direccion_emp, em.telefonos as telefonos_emp, ";
      $sql .= "        em.tipo_dpto_id as tipo_dpto_id_emp, ";
      $sql .= "        em.tipo_mpio_id as tipo_mpio_id_emp, ";
      $sql .= "        td.departamento as departamento_emp, ";
      $sql .= "        em.empresa_id, tm.municipio as municipio_emp, ";
      $sql .= "        em.indicativo as indicativo_emp, em.digito_verificacion ";
      $sql .= "FROM    empresas em, planes pl, tipo_dptos td, ";
      $sql .= "        tipo_mpios tm ";
      $sql .= "WHERE   pl.plan_id = ".$plan_id." ";
      $sql .= "        AND pl.empresa_id = em.empresa_id ";
      $sql .= "        AND em.tipo_mpio_id = tm.tipo_mpio_id ";
      $sql .= "        AND em.tipo_dpto_id = tm.tipo_dpto_id ";
      $sql .= "        AND em.tipo_pais_id = tm.tipo_pais_id ";
      $sql .= "        AND tm.tipo_dpto_id = td.tipo_dpto_id ";
      $sql .= "        AND tm.tipo_pais_id = td.tipo_pais_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion del tercero(pagador) indicando el 
    * plan como parametro de busqueda
    *
    * @param integer $plan_id valor del plan
    * @return array $datos vector con la informacion del tercero
    */
    function ConsultarTercerosManual($plan_id)
    {
      $sql  = "SELECT  t.tipo_id_tercero, t.tercero_id, t.nombre_tercero, "; 
      $sql .= "        tsg.codigo_sgsss as codigo_sgsss_p ";
      $sql .= "FROM    planes pl, terceros t left join  terceros_sgsss ";
      $sql .= "        tsg on (t.tipo_id_tercero=tsg.tipo_id_tercero AND ";
      $sql .= "        t.tercero_id=tsg.tercero_id) ";
      $sql .= "WHERE   pl.plan_id = ".$plan_id." ";
      $sql .= "        AND pl.tipo_tercero_id=t.tipo_id_tercero ";
      $sql .= "        AND pl.tercero_id=t.tercero_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta el regimen(cobertura en salud) asociado a un plan
    *
    * @param integer $plan_id valor del plan
    * @return array $datos vector con la informacion de la consulta
    */    
    function ConsCoberturaSaludPlan($plan_id)
    {
      //$this->debug=true;
      $sql  = "SELECT r.regimen_id, ";
      $sql .= "       r.regimen_res_3047,";
      $sql .= "       r.regimen_descripcion ";
      $sql .= "FROM   planes pl, tipos_cliente tc, regimenes r ";
      $sql .= "WHERE  pl.plan_id = ".$plan_id." ";
      $sql .= "AND    pl.tipo_cliente = tc.tipo_cliente ";
      $sql .= "AND    tc.regimen_id = r.regimen_id ";
      $sql .= "GROUP BY  r.regimen_id, r.regimen_descripcion,r.regimen_res_3047  ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
    
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos= $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion de los cargos
    *
    * @param array $request vector con la informacion de los filtros de la consulta
    * @return array $datos vector con la informacion de la consulta
    */
    function ConsultarCUPS($request)
    {
      $sql  = "SELECT     c.cargo, c.descripcion as desc_cargo, sw_cantidad ";
      $sql .= "FROM       cups c ";
      $sql .= "WHERE      TRUE ";
      if($request['cups'])
        $sql .= "          AND c.cargo='".$request['cups']."' ";
      if($request['desc_cups'])
        $sql .= "         AND c.descripcion ILIKE '%".$request['desc_cups']."%' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se consulta la informacion de los cargos
    *
    * @param string $cargo cadena con el id del cargo
    * @return array $datos vector con la informacion de la consulta
    */
    function ConsultarCUPSFiltro($cargo)
    {
      $sql  = "SELECT     c.cargo, c.descripcion as desc_cargo ";
      $sql .= "FROM       cups c ";
      $sql .= "WHERE      c.cargo='".$cargo."'  ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion de los departamentos y los servicios relacionados
    *
    * @return array $datos vector con la informacion de la consulta
    */
    function ConsultarDepartamentos()
    {
      $sql  = "SELECT     d.departamento, d.descripcion as desc_depto, d.servicio, s.descripcion as desc_serv ";
      $sql .= "FROM       departamentos d, servicios s ";
      $sql .= "WHERE      d.servicio = s.servicio ";
      $sql .= "           AND s.sw_asistencial = '1' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
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
    * Funcion donde se obtienen los origenes de atencion de la
    * Resolucion 3047
    *
    * @return mixed
    */
    function ObtenerOrigenAtencion()
    {
      $sql  = "SELECT causa_externa_id AS origen_atencion_id, ";
      $sql .= "       descripcion AS origenes_atencion_descripcion ";
      $sql .= "FROM   causas_externas ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;      
    }
    /**
    * Funcion donde se obtiene la informacion del ingreso
    *
    * @param integer $ingreso entero con el valor del id del ingreso
    *
    * @return mixed
    */
    function ObtenerDatosIngreso($ingreso)
    {
      $sql  = "SELECT i.paciente_id, ";
      $sql .= "       i.tipo_id_paciente ";
      $sql .= "FROM   ingresos i ";
      $sql .= "WHERE  i.ingreso = ".$ingreso." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se obtiene la informacion de la base de datos de la atencion inicial
    * de urgencias.
    *
    * @param array $filtro Arreglo con los filtros para la busqueda del registro
    *
    * @return mixed
    */
    function ObtenerDatosInformeUrgencias($filtro)
    {
      $sql  = "SELECT num_atencion ,";
      $sql .= "     	fecha ,";
      $sql .= "	      hora ,";
      $sql .= "	      usuario_id ,";
      $sql .= "	      ingreso,";
      $sql .= " 	    plan_id,";
      $sql .= " 	    empresa_id,";
      $sql .= " 	    paciente_id,";
      $sql .= " 	    tipo_id_paciente ";
      $sql .= "FROM   atencion_inicial_urgencias ";
      $sql .= "WHERE  fecha = '".$filtro['fecha']."' ";
      $sql .= "AND    num_atencion = '".$filtro['formulario_no']."' ";
      $sql .= "AND 	  paciente_id = '".$filtro['paciente_id']."' ";
      $sql .= "AND    tipo_id_paciente = '".$filtro['tipo_id_paciente']."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }    
    /**
    * Funcion donde se obtiene la informacion de la base de datos del registro de 
    * inconsistencias
    *
    * @param array $filtro Arreglo con los filtros para la busqueda del registro
    *
    * @return mixed
    */
    function ObtenerDatosInformePresuntaInconsistencia($filtro)
    {
      $sql  = "SELECT ID.num_informe, ";
      $sql .= "       ID.fecha, ";
      $sql .= "       ID.hora, ";
      $sql .= "FROM   inconsistencias_pagador ID, ";
      $sql .= "       ingresos IG, ";
      $sql .= "       pacientes PA ";
      $sql .= "WHERE  ID.ingreso = IG.ingreso ";
      $sql .= "AND 	  ID.ingreso = ".$filtro['ingreso']." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se obtiene la informacion de la base de datos del registro de 
    * la autorizacion de servicios
    *
    * @param array $filtro Arreglo con los filtros para la busqueda del registro
    *
    * @return mixed
    */
    function ObtenerDatosSolicitudAutorizacionServicios($filtro)
    {
      //$this->debug = true;
      $sql  = "SELECT numero_solicitud, ";
      $sql .= "       fecha, ";
      $sql .= "       hora, ";
      $sql .= "       usuario_id, ";
      $sql .= "       estado, ";
      $sql .= "       solicitud_manual, ";
      $sql .= "       tipo_id_paciente, ";
      $sql .= "       paciente_id, ";
      $sql .= "       plan_id, ";
      $sql .= "       ingreso, ";
      $sql .= "       profesional_id, ";
      $sql .= "       prioridad, ";
      $sql .= "       tipo_servicio, ";
      $sql .= "       origen_atencion_id AS origen_atencion ";
      $sql .= "FROM   solicitud_autorizacion_serv ";
      $sql .= "WHERE  paciente_id = '".$filtro['paciente_id']."' ";
      $sql .= "AND    tipo_id_paciente = '".$filtro['tipo_id_paciente']."' ";

      if(!$rst = $this->ConexionBaseDatos($sql))
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
    
    function ConsultarUltimaEvolucion($ingreso)
    {
     //$this->debug=true;
      $sql = " SELECT MAX(evolucion_id) as evolucion
               FROM   hc_evoluciones
               WHERE  ingreso='".$ingreso."'; ";
     if(!$rst = $this->ConexionBaseDatos($sql))
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
    
   function ConsultarDestinoPaciente($evolucion,$ingreso)
   {
  //$this->debug=true;
    $sql = " SELECT   e.evolucion_id,
                      e.historia_clinica_tipo_cierre_id,
                      d.destino_paciente_id,
                     	d.destino_paciente_descripcion 
              FROM    hc_evoluciones  e left join historias_clinicas_tipos_cierres hc on(e.historia_clinica_tipo_cierre_id=hc.historia_clinica_tipo_cierre_id),
                      destino_pacientes_3047 d
              WHERE   e.ingreso = '".$ingreso."'
              and     e.evolucion_id= '".$evolucion."'
              and     hc.destino_paciente_id=d.destino_paciente_id              ";
        if(!$rst = $this->ConexionBaseDatos($sql))
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
   
   function GetJustificacion($hc_os_solicitud_id)
   {
  //$this->debug=true;
      $sql = " SELECT observacion
               FROM   hc_os_solicitudes_apoyod
               WHERE  hc_os_solicitud_id = '".$hc_os_solicitud_id."' ";
              
              
              
              
              if(!$rst = $this->ConexionBaseDatos($sql))
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
   function ConsultarSolicitud_id_Cargo($cargo,$paciente_id,$tipo_id_paciente)
   {
     // $this->debug=true;
      $sql = " SELECT   hc_os_solicitud_id 
               FROM     hc_os_solicitudes 
               WHERE    cargo= '".$cargo."'  
               AND      sw_estado = '1' 
               AND      paciente_id= '".$paciente_id."' 
               AND      tipo_id_paciente = '".$tipo_id_paciente."' ";
                 if(!$rst = $this->ConexionBaseDatos($sql))
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
   
   
   
  }
?>