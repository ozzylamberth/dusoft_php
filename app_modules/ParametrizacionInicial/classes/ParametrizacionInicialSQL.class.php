<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ParametrizacionInicialSQL.class.php,v 1.1 2009/09/14 08:19:24 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  /**
  * Clase : ParametrizacionInicialSQL
  * 
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.4 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  
  class ParametrizacionInicialSQL extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function ParametrizacionInicialSQL(){}
    
    /**
    * Funcion donde se verifica el permiso del usuario para el ingreso al modulo
    *
    * @return array $datos vector que contiene la informacion de la consulta del codigo de
    * la empresa y la razon social
    */
    function ObtenerPermisos()
    {
      //$this->debug = true;
      $sql  = "SELECT   EM.empresa_id AS empresa, ";
      $sql .= "         EM.razon_social AS razon_social ";
      $sql .= "FROM     userpermisos_parametrizacion CP, empresas EM ";
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
    * Funcion donde se consultan los planes
    *
    * @return array $datos vector que contiene la informacion de la consulta de los planes 
    * donde se tiene la identificacion del plan y descripcion
    */
    function ConsultarPlanes()
    {
      //$this->debug=true;
      $sql  = "SELECT    a.plan_id,a.plan_descripcion ";
      $sql .= "FROM      planes a ";
      $sql .= "WHERE     a.estado='1' ";
	  $sql .= "AND       a.empresa_id = '".SessionGetVar("empresa_id")."' ";
      $sql .= "ORDER BY  a.plan_descripcion";
       
      
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
   * Funcion donde se consultan los afiliados a un plan
   * 
   * @param array $plan variable donde se encuentra el plan
   * @return array $datos vector que contiene la informacion de la consulta de los tipos afiliado
   */
   function ConsultarAfiliados($plan)
   {
      //$this->debug=true;
      $sql  = "SELECT  b.tipo_afiliado_nombre,a.rango,a.tipo_afiliado_id,c.tiempo_cita ";
      $sql .= "FROM    planes_rangos as a LEFT JOIN tiempocitaxplanes as c ON(a.tipo_afiliado_id=c.tipo_afiliado_id AND a.plan_id=c.plan_id AND a.rango=c.rango), "; 
      $sql .= "        tipos_afiliado as b ";
      $sql .= "WHERE   a.tipo_afiliado_id=b.tipo_afiliado_id ";
      $sql .= "AND     a.plan_id = ".$plan." ";
      
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
   * Funcion donde se consultan los tipos de consulta
   * 
   * @return array $datos vector que contiene la informacion de la consulta de los tipos de consulta
   */
   function ConsultarTipoConsulta()
   {
      //$this->debug=true;
      $sql  = "SELECT  descripcion, ";
      $sql .= "        tipo_consulta_id ";
      $sql .= "FROM    tipos_consulta ";
      
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
   * Funcion donde se consultan los tipos de cargos
   *
   * @param array $tipo_consulta variable donde se encuentra el tipo de consulta
   * @return array $datos vector que contiene la informacion de la consulta de los tipos de cargo
   */
   function ConsultarTipoCargos($tipo_consulta)
   {
      //$this->debug=true;
      $sql  = "SELECT  b.cargo_cita, ";
      $sql .= "        b.descripcion, ";
      $sql .= "        c.tipo_consulta_id, ";
      $sql .= "        d.prioridad, ";
      $sql .= "        d.tiempo_cargo ";
      $sql .= "FROM    tipos_consultas_cargos a LEFT JOIN tiempoxcargo as d ON (a.tipo_consulta_id=d.tipo_consulta_id AND a.cargo_cita=d.cargo_cups), ";
      $sql .= "        cargos_citas b, ";
      $sql .= "        tipos_consulta c ";
      $sql .= "WHERE   c.tipo_consulta_id = ".$tipo_consulta." ";
      $sql .= "AND     a.cargo_cita = b.cargo_cita ";
      $sql .= "AND     a.tipo_consulta_id = c.tipo_consulta_id ";
      
      
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
   * Funcion donde se consultan los permisos de un usuario
   *
   * @return array $datos vector que contiene la informacion de la consulta de los permisos de usuario
   */
   function ConsultarpermisosUsuarios()
   {
     
     $sql  = "SELECT  DISTINCT b.usuario,a.usuario_id as id ";
     $sql .= "FROM    userpermisos_tipos_consulta as a, ";
     $sql .= "        system_usuarios b ";
     $sql .= "WHERE   a.usuario_id=b.usuario_id ";
     
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
   * Funcion donde se consultan los planes que puede ver el usuario
   *
   * @param array $empresa variable donde se encuentra la empresa
   * @param array $usuario variable donde se encuentra el usuario
   * @return array $datos vector que contiene la informacion de la consulta de los planes
   */
   function ConsultarUsuariosAsigacion($empresa,$usuario)
   {
     //$this->debug = true;
     $sql  = "SELECT DISTINCT b.sw_vertodosplanes,";
     $sql .= "       a.plan_id ";
     $sql .= "FROM   userpermisos_tipos_consulta b ";
     $sql .= "       LEFT JOIN todoslosplanes a ";
     $sql .= "       ON( a.usuario_id=b.usuario_id AND "; 
     $sql .= "           a.empresa_id= '".$empresa."') ";
     $sql .= "WHERE  b.usuario_id = ".$usuario." ";
     
     if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
   }
   
   /**
    * Funcion donde se almacena la informacion del tiempo de las citas 
    * 
    * @param array $request vector con la informacion del request
    * @return integer $request['planes'] valor del id de los planes
    */
   function IngresarTiempoDeCitas($request)
   {
      //$this->debug=true;
      $this->ConexionTransaccion();
      echo $cant = count($request['DiaCita']);
      
      for ($i=0; $i<$cant;$i++)
      {
        if($request['DiaCita'][$i])
        {
          if(!$request['insert'][$i])
          {
            $sql  = "INSERT INTO tiempocitaxplanes( ";
            $sql .= "        tipo_afiliado_id, ";
            $sql .= "        plan_id, ";
            $sql .= "        rango, ";
            $sql .= "        tiempo_cita, ";
            $sql .= "        usuario_registro, ";
            $sql .= "        fecha_registro ";
            $sql .= ")VALUES (";
            $sql .= "         '".$request['tipo_af'][$i]."', ";
            $sql .= "         ".$request['planes'].", ";
            $sql .= "         '".$request['rango'][$i]."', ";
            $sql .= "         '".$request['DiaCita'][$i]."', ";
            $sql .= "         ".UserGetUID().", ";
            $sql .= "         NOW() ) ";
          }
          else
          {
            $sql  = "UPDATE tiempocitaxplanes ";
            $sql .= "SET    tiempo_cita = '".$request['DiaCita'][$i]."' , "; 
            $sql .= "       usuario_actualizacion = ".UserGetUID().", ";
            $sql .= "       fecha_actualizacion = NOW() ";
            $sql .= "WHERE  tipo_afiliado_id= '".$request['tipo_af'][$i]."' ";
            $sql .= "AND    plan_id = ".$request['planes']." ";
            $sql .= "AND    rango = '".$request['rango'][$i]."' ";
          }
          if(!$rst = $this->ConexionTransaccion($sql))
          {
          echo $this->mensajeDeError;
            return false;
            
          }
        }
        //$rst->Close();
      }
      
      $this->Commit();
      
      return $request['planes'];
   }
   
   /**
   * Funcion donde se almacena la informacion de la prioridad del cargo 
   *
   * @param array $request vector con la informacion del request
   * @return booleano
   */
   function IngresarPrioridad($request,$empresa)
   {
      //$this->debug=true;
      $this->ConexionTransaccion();
      
      $cant = count($request['Prioridad']);
		
      for ($i=0; $i<$cant;$i++)
      {
        if($request['cargostiemp'][$i] >= 0)
        {
          if(!$request['insert'][$i])
          {
            
			$sql = "DELETE FROM tiempoxcargo WHERE tipo_consulta_id = ".$request['tipo_consulta'][$i]." ";
			$sql.= " AND cargo_cups = '".$request['cargocups'][$i]."' ";
			$sql.= " AND empresa_id = '".$empresa."'; ";
			
			
			$sql .= "INSERT INTO tiempoxcargo( ";
            $sql .= "            tipo_consulta_id, ";
            $sql .= "            cargo_cups, ";
            $sql .= "            prioridad, ";
            $sql .= "            tiempo_cargo, ";
            $sql .= "            usuario, ";
            $sql .= "            fecha_registro, ";
            $sql .= "            empresa_id ";
            $sql .= ")VALUES    (";
            $sql .= "           ".$request['tipo_consulta'][$i].", ";
            $sql .= "           '".$request['cargocups'][$i]."', ";
            $sql .= "           ".$request['Prioridad'][$i].", ";
            $sql .= "           ".$request['cargostiemp'][$i].", ";
            $sql .= "           ".UserGetUID().", ";
            $sql .= "           NOW(), ";
            $sql .= "           '".$empresa."' )";
          }
          else
          {
            $sql  = "UPDATE tiempoxcargo ";
            $sql .= "SET    prioridad = ".$request['Prioridad'][$i]." , ";
            $sql .= "       tiempo_cargo = ".$request['cargostiemp'][$i]." ";
            $sql .= "WHERE  tipo_consulta_id = '".$request['tipo_consulta'][$i]."' ";
            $sql .= "AND    cargo_cups = ".$request['cargocups'][$i]." ";
            $sql .= "AND    empresa_id = '".$empresa."' ";
          }
          if(!$rst = $this->ConexionTransaccion($sql))
          {
             echo $this->mensajeDeError;
             return false;
          }
        }
       //$rst->Close();
     }
     
     $this->Commit();
     return true;
   }
   
   /**
   * Funcion donde se almacena la informacion de la prioridad del cargo 
   *
   * @param array $request vector con la informacion del request
   * @return booleano
   */
    function IngresarAsignacionPlanes($todosPlanes,$empresa,$usuario)
    {
      //$this->debug=true;
      $this->ConexionTransaccion();
      
      $sql .= "DELETE FROM todoslosplanes ";
      $sql .= "WHERE  usuario_id = ".$usuario." ";
      $sql .= "AND    empresa_id = '".$empresa."' ";
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        echo $this->mensajeDeError;
        return false;
      }
      $cant = sizeof($todosPlanes);
      
      for ($i=0; $i<$cant;$i++)
      {
	  
        if($todosPlanes[$i])
        {
            $sql  = "INSERT INTO todoslosplanes( ";
            $sql .= "            usuario_id, ";
            $sql .= "            plan_id, ";
            $sql .= "            empresa_id ";
            $sql .= ")VALUES    ( ";
            $sql .= "           ".$usuario.", ";
            $sql .= "           ".$todosPlanes[$i].", ";
            $sql .= "           '".$empresa."') ";
            
            if(!$rst = $this->ConexionTransaccion($sql))
            {
              echo $this->mensajeDeError;
              return false;
            }
        }
        $rst->Close();
      }
      
      $this->Commit();
      return true;
    
   }
   
   /**
   * Funcion donde se actualiza la informacion de la asignacion de los planes 
   *
   * @param array $usuario_id variable donde se encuentra el usuario 
   * @param array $sw_todos variable donde se encuentra el estado de la vista de los planes
   * @return booleano
   */
    function ActualizacionAsignacionPlanes($usuario_id,$sw_todos)
    {
      //$this->debug=true;
      $this->ConexionTransaccion();
      
      $sql  = "UPDATE  userpermisos_tipos_consulta ";
      $sql .= "SET     sw_vertodosplanes='".$sw_todos."' ";
      $sql .= "WHERE   usuario_id=".$usuario_id." ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {
             echo $this->mensajeDeError;
             return false;
      }
      $this->Commit();
      return true;
    }
  }
?>