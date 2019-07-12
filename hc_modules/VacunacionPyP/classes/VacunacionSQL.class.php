<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: VacunacionSQL.class.php,v 1.1 2009/12/03 14:58:39 alexander Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma
  */
  /**
  * Clase: TriageSQL
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma
  */
  
  class VacunacionSQL extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function VacunacionSQL(){}
    
    /**
		* Esta funcion me trae todas las posibles vacunas que estan para aplicar al paciente
    * recive un $request que son los datos del paciente
    */
    function traerPosiblesVacunas($request)
    { 
      //$this->debug=true;
      $sql = " SELECT   v.edad_minima,
                        v.edad_maxima,
                        v.edad_minima_unidad,
                        v.edad_maxima_unidad,
                        v.dosis,
                        v.refuerzos,
                        h.nombre,
                        v.enfermedad,
                        v.cargo,
                        d.edad_aplicacion,
                        d.unidad_edad_aplicacion,
                        d.cargo,
                        c.descripcion,
                        u.descripcion as nom_unidad_min,
                        x.descripcion as nom_unidad_max
               FROM     vacunas_parametro v,
                        dosisxvacuna d,
                        cups c,
                        unidades_tiempo u,
                        unidades_tiempo x,
                        hc_vias_administracion h
               WHERE    v.cargo = d.cargo 
               AND      c.cargo = v.cargo 
               AND      h.via_administracion_id = v.via_aplicacion
               AND      v.edad_minima_unidad = u.unidad_tiempo_id
               AND      v.sw_estado = '1'  
               AND      v.edad_maxima > ".$request['edad_paciente']['edad_rips']." 
               AND      v.edad_maxima_unidad = x.unidad_tiempo_id
               AND      d.unidad_edad_aplicacion = x.unidad_tiempo_id ";
               
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
		* Funcion que me trae el historial de vacuancion del paciente
    * recive un $request que son los datos del paciente
    */
    function historialVacunacion($request)
    { 
      //$this->debug=true;
      $sql = " SELECT   h.dosis,
                        h.lugar_aplicacion,
                        h.observaciones,
                        h.fecha_aplicacion,
                        h.evolucion_id,
                        h.usuario_id,
                        u.nombre,
                        u.usuario_id,
                        v.enfermedad,
                        v.cargo,
                        d.cargo,
                        d.dosis_vacuna_id,
                        d.numero_dosis,
                        c.descripcion,
                        e.evolucion_id,
                        p.tipo_id_paciente,
                        p.paciente_id
               FROM     vacunas_parametro v,
                        dosisxvacuna d,
                        cups c,
                        hc_vacunacion_registro h,
                        system_usuarios u,
                        hc_evoluciones e,
                        ingresos p
               WHERE    e.evolucion_id = h.evolucion_id
               AND      h.usuario_id = u.usuario_id
               AND      v.cargo = d.cargo 
               AND      c.cargo = v.cargo 
               AND      v.sw_estado = '1'  
               AND      h.dosis = d.dosis_vacuna_id 
               AND      p.ingreso = e.ingreso 
               AND      p.tipo_id_paciente = '".$request['tipo_id_paciente']."'
               AND      p.paciente_id = '".$request['paciente_id']."' ";
               
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
    * Funcion que trae todas las dosis de la vacuna seleccionada
    * recive el cargo de la vacuna
    */
    function traerDatosDosis($cargo)
    {
        //$this->debug=true;
        //print_r($cargo);
        $sql = "  SELECT  d.numero_dosis,     
                          d.cargo,
                          d.edad_aplicacion,
                          d.unidad_edad_aplicacion,
                          d.observacion_dosis,
                          d.dosis_vacuna_id,
                          v.cargo AS cargo_cups,
                          u.descripcion,
                          e.dosis
                  FROM    vacunas_parametro v,
                          dosisxvacuna d
                          LEFT JOIN hc_vacunacion_registro e
                          ON(d.dosis_vacuna_id = e.dosis),
                          unidades_tiempo u
                  WHERE   v.cargo = '".trim($cargo)."' 
                  AND     v.cargo = d.cargo 
                  AND     d.unidad_edad_aplicacion = u.unidad_tiempo_id
                  AND     sw_dosis = '1' ";
        
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
    
    /**
    * Funcion que trae todos los refuerzos de la vacuna seleccionada
    * recive el cargo de la vacuna
    */
    function traerDatosRefuerzos($cargo)
    {
        //$this->debug=true;
        //print_r($cargo);
        $sql = "  SELECT  d.numero_dosis,     
                          d.cargo,
                          d.edad_aplicacion,
                          d.unidad_edad_aplicacion,
                          d.observacion_refuerzo,
                          d.dosis_vacuna_id,
                          v.cargo AS cargo_cups,
                          u.descripcion,
                          e.dosis
                  FROM    vacunas_parametro v,
                          dosisxvacuna d
                          LEFT JOIN hc_vacunacion_registro e
                          ON(d.dosis_vacuna_id = e.dosis),
                          unidades_tiempo u
                  WHERE   v.cargo = '".trim($cargo)."' 
                  AND     v.cargo = d.cargo 
                  AND     d.unidad_edad_aplicacion = u.unidad_tiempo_id
                  AND     sw_dosis = '2' ";
        
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
    
    /**
    *Funcion que trae las unidades de tiempo de las dosis y refuerzos
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
    * Funcion que inserta en la tabla hc_vacunacion_registro cuando se le aplica una dosis al paciente
    * recive $datos, que son los datos de la aplicacion de la vacuna
    */
    function insertar_registro_dosis($datos)
    {
        //$this->debug=true;
        //print_r($datos);
        $this->ConexionTransaccion();
        $sql = "   INSERT INTO  hc_vacunacion_registro  (evolucion_id, usuario_id, fecha_registro, 
                                                        observaciones, lugar_aplicacion,
                                                        cargo, dosis, fecha_aplicacion) 
                                VALUES (                ".$datos['evolucion_id']." , 
                                                        ".UserGetUID()." ,
                                                        now(),
                                                        '".$datos["observaciones"]."' , 
                                                        '".$datos["lugar_aplicacion"]."' , 
                                                        '".$datos["cargo"]."' ,
                                                        ".$datos['dosis_vacuna_id']." ,
                                                        '".$datos["fecha_aplicacion"]."'
                                       )"; 
       if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
        $this->Commit(); 
        return true;
    }
    
  }
?>