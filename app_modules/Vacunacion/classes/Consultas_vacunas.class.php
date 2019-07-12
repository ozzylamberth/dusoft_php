<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Consultas_vacunas.class.php,v 1.4 2009/12/07 21:15:52 alexander Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma Vargas
  */
  /**
  * Clase: Consultas_vacunas
  * Clase encargada del manejo de base de datos para las consultas que se necesitan 
  * para mostrar los datos de la afiliacion y los afiliados. Contine los metodos mas 
  * comunes, llamados por cualquier metodo del controlador
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.4 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma Vargas
  */
  class Consultas_vacunas extends ConexionBD
  {
    /*
    *Este es el constructor por defecto
    */
    function Consultas_vacunas(){}
   
    /**
    *Esta funcion verifica que la empresa tenga permisos para acceder al modulo de vacunacion
    *@param array $usuario
    *
    * @return array
    */
    function buscarPermisos($usuario)
    {
      $sql = "    SELECT  e.empresa_id, e.razon_social as empresa, us.empresa_id 
                  FROM    empresas e, userpermisos_vacunacion us 
                  WHERE   e.empresa_id=us.empresa_id 
                          and us.sw_activo='1' and us.usuario_id=".$usuario."";
	
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
    *Esta funcion asigna vacunas a la base de datos, a la tabla vacunas_parametro
    *y si tiene dosis y refuerzos guarda esta informacion en la tabla dosisxvacuna
    *tiene 3 parametros:
    *@param array $request: esta toda la informacion que manda el usuario de la vacuna
    *@param array $empresas: me trae la empresa
    *@param array $unidades: me trae el id de las unidades de tiempo
    *
    * @return boolean
    */
    function insertarVacuna($request,$empresas,$unidades)
    { 
      //print_r($request) ;  
      //$this->debug=true;  
      if(!$request['edadMax'])
      {
        $request['edadMax'] = "NULL";
        $request['unidadEdadMax'] = "NULL";
      }
      $this->ConexionTransaccion();
      
      if (!$request["genero"])  $request["genero"]=0;
      
      $sql = "  INSERT INTO   vacunas_parametro (cargo,edad_minima,edad_maxima,usuario_id,
                                                fecha_registro,sw_estado,empresa_id,
                                                unidad_tiempo_id,edad_minima_unidad,edad_maxima_unidad,
                                                dosis, refuerzos, genero, via_aplicacion, enfermedad, cantidad_dosis)                                         
                VALUES ( '".$request['cargo_cups']."',
                                                ".$request['edadMin'].",
                                                ".$request['edadMax'].",
                                                ".UserGetUID().",
                                                now(),
                                                '1',
                                                '".$empresas["empresa_id"]."',
                                                '".$unidades["unidad_tiempo_id"]."',
                                                ".$request['unidadEdadMin'].",
                                                ".$request['unidadEdadMax'].",
                                                ".$request['dosis'].",
                                                ".$request['refuerzos'].",
                                                '".$request["genero"]."',
                                                '".$request["via_aplicacion"]."',
                                                '".$request["enfermedad"]."',
                                                '".$request["cantidad_dosis"]."' )";
                                              
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      $j=1;
      if( $request['dosis'] >= 1)
      {
        for($i  =0; $i< $request['dosis'];$i++)
        {
          $sql  = "INSERT INTO dosisxvacuna ";
          $sql .= "      ( ";
          $sql .= "          dosis_vacuna_id, ";
          $sql .= "          sw_dosis, ";
          $sql .= "          edad_aplicacion,  ";
          $sql .= "          unidad_edad_aplicacion, ";
          $sql .= "          fecha_registro, ";
          $sql .= "          numero_dosis, ";
          $sql .= "          cargo,  ";
          $sql .= "          empresa_id, ";
          $sql .= "          usuario_id, ";
          $sql .= "          observacion_dosis, ";
          $sql .= "          observacion_refuerzo ";
          $sql .= "      ) ";
          $sql .= "VALUES ";
          $sql .= "     ( DEFAULT, ";
          $sql .= "      '1', ";
          $sql .= "      ".$request['edad_aplicacion_d'][$i].", ";
          $sql .= "      ".$request['unidad_edad_aplicacion_d'][$i].", ";
          $sql .= "      now(), ";
          $sql .= "      '$j', ";
          $sql .= "      '".$request['cargo_cups']."', ";
          $sql .= "      '".$empresas['empresa_id']."', ";
          $sql .= "      ".UserGetUID()." , ";
          $sql .= "      '".$request["observacion_dosis"][$i]."', ";
          $sql .= "       'null' ) "; 
          $j++;  
          if(!$rst = $this->ConexionTransaccion($sql)) return false;      
        }
      }
      
      $k=1;
      if( $request['refuerzos'] >= 1)
      {
        for($i  =0; $i< $request['refuerzos'];$i++)
        {
          $sql  = "INSERT INTO dosisxvacuna ";
          $sql .= "      ( ";
          $sql .= "          dosis_vacuna_id, ";
          $sql .= "          sw_dosis, ";
          $sql .= "          edad_aplicacion,  ";
          $sql .= "          unidad_edad_aplicacion, ";
          $sql .= "          fecha_registro, ";
          $sql .= "          numero_dosis, ";
          $sql .= "          cargo,  ";
          $sql .= "          empresa_id, ";
          $sql .= "          usuario_id, ";
          $sql .= "          observacion_dosis, ";
          $sql .= "          observacion_refuerzo ";
          $sql .= "      ) ";
          $sql .= "VALUES ";
          $sql .= "     ( DEFAULT, ";
          $sql .= "      '2', ";
          $sql .= "      ".$request['edad_aplicacion_r'][$i].", ";
          $sql .= "      ".$request['unidad_edad_aplicacion_r'][$i].", ";
          $sql .= "      now(), ";
          $sql .= "      $k, ";
          $sql .= "      '".$request['cargo_cups']."', ";
          $sql .= "      ".$empresas['empresa_id'].", ";
          $sql .= "      ".UserGetUID()." ,  ";
          $sql .= "      'null' ,  ";
          $sql .= "      '".$request["observacion_refuerzo"][$i]."' ) "; 
          $k++;
          if(!$rst = $this->ConexionTransaccion($sql)) return false;
        }                        
      }   
      $this->Commit();
      return true;
    }
    
    /**
    *Esta funcion me trae las vacunas que existe en la tabla vacunas_parmetro 
    *@param array $parametros
    *
    * @return array
    */
    function buscarVacunasParametros($parametros)
    {
      //$this->debug=true;
      $sql = "SELECT  v.cargo,
                      v.edad_minima,
                      v.edad_maxima,
                      v.sw_estado ,                       
                      c.descripcion,
                      u.descripcion as nombre,
                      x.descripcion as nombre_edad_min
              FROM    cups c,
                      vacunas_parametro v LEFT JOIN unidades_tiempo x                               
              ON      (v.edad_maxima_unidad=x.unidad_tiempo_id),
                      unidades_tiempo u                        
              WHERE   v.edad_minima_unidad=u.unidad_tiempo_id
                      and c.sw_vacuna='1'
                      and v.cargo=c.cargo ";  
     
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$parametros["offset"]))
        return false;
    
      $sql .= "ORDER BY c.descripcion ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
     
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
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
    *Esta funcion busca las vacunas existetes en la tabla cups de acuerdo al cargo
    *@param array $parametros
    *
    * @return array
    */
    function buscarVacunasCups($parametros)
    {
      //$this->debug=true;
      $sql = "SELECT    c.cargo, 
                        c.descripcion, 
                        v.cargo AS cargo_parametro 
              FROM      cups c 
              LEFT JOIN vacunas_parametro v 
              ON        (v.cargo = c.cargo)
              WHERE     c.sw_vacuna='1' ";
               
      if($parametros["cargo"])
        $sql .= "AND    c.cargo = '".$parametros["cargo"]."' ";
    
      if($parametros["descripcion"])
        $sql .= "AND    c.descripcion ILIKE '%".$parametros["descripcion"]."%' ";
    
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$parametros["offset"]))
        return false;
    
      $sql .= "ORDER BY c.descripcion ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
    
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    
    /*
    *Esta funcion me trae las unidades de tiempo que estan en la tabla unidades_tiempo
    *
    * @return array
    */    
    function unidades_tiempo()
    {
      $sql = "    SELECT   * from unidades_tiempo";
     
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
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
    *Esta funcion me trae el genero que esta en la tabla tipo_sexo
    *
    * @return array
    */
     function genero()
    {
      $sql = "    SELECT   * from tipo_sexo
                  WHERE     sw_mostrar='1'";
     
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
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
     *Esta funcion es para mostar las opciones de la via de administracion de las vacunas que estan
     *en la tabla hc_vias_administracion
     *
     * @return array
     */
     function viaAdministracion()
    {
      $sql = " SELECT  h.sw_vacuna, 
                       h.nombre,
                       via_administracion_id
              FROM     hc_vias_administracion h
              WHERE    sw_vacuna=1
              ORDER BY nombre";
     
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
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
    *Esta funcion me permite desactivar o activar las vacunas 
    *@param array ~parametros
    *
    * @return boolean
    */
    function desactivarVacuna($parametros)
    {
      //$this->debug=true;
      $sql = "  UPDATE   vacunas_parametro
                SET      sw_estado = '".$parametros["sw_estado"]."'
                WHERE    cargo = '".$parametros["cargo_cups"]."' ";
               
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
          
      return true;
    }
    
    /*
    *Esta funcion me trae toda la informacion de la vacuna cuando voy a modificar
    *@param array $parametros
    *
    * @return array
    */
    function traerDatos($parametros)
    {
      //$this->debug=true;
      //print_r($parametros);
      $sql = " SELECT   v.cargo AS cargo_cups,
                        v.edad_minima,  
                        v.edad_maxima,
                        v.edad_minima_unidad AS unidadedadmin,
                        v.edad_maxima_unidad,
                        v.dosis,
                        v.refuerzos,
                        v.genero,
                        v.via_aplicacion,
                        v.enfermedad,
                        v.cantidad_dosis,
                        c.descripcion AS descripcion_cups
              FROM      cups c ,
                        vacunas_parametro v 
              WHERE     v.cargo = '".$parametros["cargo_cups"]."' 
              AND       v.cargo = c.cargo";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    
    /*
    *Esta funcion me trae los datos de las dosis que estan en la tabla dosisxvacuna pero que tenga
    *como sw_dosis=1 que se refiere a que es una dosis.
    *@param character varying $cargo
    *
    * @return array
    */    
    function traerDatosDosis($cargo)
    {
        //$this->debug=true;
        //print_r($parametros);
        $sql = "  SELECT  d.numero_dosis,     
                          d.cargo,
                          d.edad_aplicacion,
                          d.unidad_edad_aplicacion,
                          d.observacion_dosis,
                          v.cargo AS cargo_cups
                  FROM    vacunas_parametro v,
                          dosisxvacuna d
                  WHERE   v.cargo = '".trim($cargo)."' 
                  AND     v.cargo = d.cargo 
                  AND     d.sw_dosis='1'";
        
        if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    
    /*
    *Esta funcion me trae los datos de los refuerzos que estan en la tabla dosisxvacuna pero que tenga
    *como sw_dosis=2 que se refiere a que es un refuerzo.
    *@param character varying $cargo
    *
    * @return array
    */ 
    function traerDatosRefuerzos($cargo)
    {
        //$this->debug=true;
        //print_r($parametros);
        $sql = "  SELECT  d.numero_dosis,     
                          d.cargo,
                          d.edad_aplicacion,
                          d.unidad_edad_aplicacion,
                          d.sw_dosis,
                          d.observacion_refuerzo,
                          v.cargo AS cargo_cups
                  FROM    vacunas_parametro v,
                          dosisxvacuna d
                  WHERE   v.cargo = '".$cargo."'  
                  AND     d.sw_dosis='2'
                  AND     v.cargo = d.cargo";
        
        if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    
    /*
    *Esta funcion me permite modificar los datos de la vacuna, las dosis y los refuerzos
    *tiene 3 parametros
    *@param array $request: es donde estan los nuevoz datos a guerdar
    *@param array $empresas: me trae la empresa
    *@param array $unidades: me trae las unidades
    *
    * @return boolean
    */
    function modificarVacuna($request,$empresas,$unidades)
    { 
      //print_r($request) ;  
      $this->debug=true;  
      if(!$request['edadMax'])
      {
        $request['edadMax'] = "NULL";
        $request['unidadEdadMax'] = "NULL";
      }
      $this->ConexionTransaccion();
      
      if (!$request["genero"])  $request["genero"]=0;
      
      $sql = "  UPDATE                      vacunas_parametro                                     
                SET                         edad_minima = ".$request['edadMin'].",
                                            edad_maxima = ".$request['edadMax'].",
                                            fecha_registro = now(),
                                            unidad_tiempo_id = '".$unidades["unidad_tiempo_id"]."',
                                            edad_minima_unidad = ".$request['unidadEdadMin'].",
                                            edad_maxima_unidad = ".$request['unidadEdadMax'].",
                                            dosis = ".$request['dosis'].",
                                            refuerzos = ".$request['refuerzos'].",
                                            genero = '".$request["genero"]."',
                                            via_aplicacion = '".$request["via_aplicacion"]."',
                                            enfermedad = '".$request["enfermedad"]."',
                                            cantidad_dosis = '".$request["cantidad_dosis"]."'
                WHERE                       cargo = '".$request["cargo_cups"]."'";
                                             
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      $j=1;
      
      $sql = " DELETE FROM dosisxvacuna
               WHERE   cargo = '".$request["cargo_cups"]."'  ";
                   
      if(!$rst = $this->ConexionTransaccion($sql)) return false;  

      if( $request['dosis'] >= 1)
      {
        for($i  =0; $i< $request['dosis'];$i++)
        { 
          $sql  = "INSERT INTO dosisxvacuna ";
          $sql .= "      ( ";
          $sql .= "          dosis_vacuna_id, ";
          $sql .= "          sw_dosis, ";
          $sql .= "          edad_aplicacion,  ";
          $sql .= "          unidad_edad_aplicacion, ";
          $sql .= "          fecha_registro, ";
          $sql .= "          numero_dosis, ";
          $sql .= "          cargo,  ";
          $sql .= "          empresa_id, ";
          $sql .= "          usuario_id, ";
          $sql .= "          observacion_dosis, ";
          $sql .= "          observacion_refuerzo ";
          $sql .= "      ) ";
          $sql .= "VALUES ";
          $sql .= "     ( DEFAULT, ";
          $sql .= "      '1', ";
          $sql .= "      ".$request['edad_aplicacion_d'][$i].", ";
          $sql .= "      ".$request['unidad_edad_aplicacion_d'][$i].", ";
          $sql .= "      now(), ";
          $sql .= "      '$j', ";
          $sql .= "      '".trim($request['cargo_cups'])."', ";
          $sql .= "      '".$empresas['empresa_id']."', ";
          $sql .= "      ".UserGetUID()." , "; 
          $sql .= "      '".$request["observacion_dosis"][$i]."' , "; 
          $sql .= "      null ) "; 
          $j++;  
          if(!$rst = $this->ConexionTransaccion($sql)) return false;      
        }
      }
      
      $k=1;
      
      if (!$request["edad_aplicacion_r"][$i])  $request["edad_aplicacion_r"][$i] = NULL;
      if (!$request["unidad_edad_aplicacion_r"][$i])  $request["unidad_edad_aplicacion_r"][$i] = "NULL";
      
      if( $request['refuerzos'] >= 1)
      {        
        for($i  =0; $i< $request['refuerzos'];$i++)
        {
            
          $sql  = "INSERT INTO dosisxvacuna ";
          $sql .= "      ( ";
          $sql .= "          dosis_vacuna_id, ";
          $sql .= "          sw_dosis, ";
          $sql .= "          edad_aplicacion,  ";
          $sql .= "          unidad_edad_aplicacion, ";
          $sql .= "          fecha_registro, ";
          $sql .= "          numero_dosis, ";
          $sql .= "          cargo,  ";
          $sql .= "          empresa_id, ";
          $sql .= "          usuario_id, ";
          $sql .= "          observacion_dosis, ";
          $sql .= "          observacion_refuerzo ";
          $sql .= "      ) ";
          $sql .= "VALUES ";
          $sql .= "     ( DEFAULT, ";
          $sql .= "      '2', ";
          $sql .= "      ".$request['edad_aplicacion_r'][$i].", ";
          $sql .= "      ".$request['unidad_edad_aplicacion_r'][$i].", ";
          $sql .= "      now(), ";
          $sql .= "      $k, ";
          $sql .= "      '".trim($request['cargo_cups'])."', ";
          $sql .= "      ".$empresas['empresa_id'].", ";
          $sql .= "      ".UserGetUID()." , "; 
          $sql .= "      null , "; 
          $sql .= "      '".$request["observacion_refuerzo"][$i]."' ) "; 
          $k++;
          if(!$rst = $this->ConexionTransaccion($sql)) return false;
        }                        
      }   
      $this->Commit();
      return true;
    }     
  }
?>