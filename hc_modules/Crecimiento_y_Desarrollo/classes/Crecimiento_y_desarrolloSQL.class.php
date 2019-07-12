<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Crecimiento_y_desarrolloSQL.class.php,v 1.2 2010/02/05 21:41:03 alexander Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma
  */
  /**
  * Clase: TriageSQL
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma
  */
  
  class Crecimiento_y_DesarrolloSQL extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function Crecimiento_y_DesarrolloSQL(){}
    
    /**
    *Esta funcion asigna los datos del paciente, de los familiares, de la vivienda y de las patologias
    *a la base de datos
    *@param array $datos: los datos del paciente
    *@param array $request: la informacion del formulario
    *
    * @return boolean
    */
    function registrarPaciente($datos,$request)
    {
      //print_r($datos);
      //print_r($request);
      $this->ConexionTransaccion();
      
      $sql = "  SELECT nextval('hc_inscripcion_pyp_crecimiento_y_desarrollo_inscripcion_id_seq'::regclass) AS id ";
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $inscripcion = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
      
      if(!$request['sw_ubicacion'])
        $request['sw_ubicacion'] = '0';
        
      $sql = "  INSERT INTO   hc_inscripcion_pyp_crecimiento_y_desarrollo (inscripcion_id, tipo_id_paciente,
                                                                          paciente_id, evolucion_id,
                                                                          establecimiento_donde_nacio,
                                                                          embarazo_deseado,
                                                                          edad_gestional_semanas,
                                                                          atencion_prenatal, talla,
                                                                          peso, per_cef, apgar,
                                                                          usuario_id, fecha_registro,
                                                                          cantidad_hermanos, sw_ubicacion)                                                        
                              VALUES (                                  ".$inscripcion['id']." ,
                                                                        '".$datos['tipo_id_paciente']."' , 
                                                                        '".$datos['paciente_id']."' , 
                                                                        ".$request['evolucion']." , 
                                                                        '".$request['establecimiento_donde_nacio']."' , 
                                                                        '".$request['embarazo_deseado']."' , 
                                                                        '".$request['edad_gestional_semanas']."' , 
                                                                        '".$request['atencion_prenatal']."' , 
                                                                        '".$request['talla']."' , 
                                                                        '".$request['peso']."' , 
                                                                        '".$request['per_cef']."' , 
                                                                        '".$request['apgar']."' , 
                                                                        ".UserGetUID()." ,
                                                                        now(),
                                                                        ".$request['cantidad_hermanos']." ,
                                                                        '".$request['sw_ubicacion']."'
                                      )";
                                      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
       
      if($request['educacion_madre'] == "-1")
        $request['educacion_madre'] = "NULL";
      if($request['ocupacion_madre'] == "-1")
        $request['ocupacion_madre'] = "NULL";
      if($request['educacion_padre'] == "-1")
        $request['educacion_padre'] = "NULL";
      if($request['ocupacion_padre'] == "-1")
        $request['ocupacion_padre'] = "NULL";
       
      $sql = "  INSERT INTO   hc_inscripcion_cd_familiares    (inscripcion_id, nombre_madre, edad_madre,
                                                              educacion_madre, ocupacion_madre, nombre_padre, 
                                                              edad_padre, educacion_padre,ocupacion_padre)                                                        
                              VALUES  (                       ".$inscripcion['id']." ,
                                                              '".$request['nombre_madre']."' , 
                                                              ".$request['edad_madre']." , 
                                                              ".$request['educacion_madre']." , 
                                                              ".$request['ocupacion_madre']." , 
                                                              '".$request['nombre_padre']."' , 
                                                              ".$request['edad_padre']." , 
                                                              ".$request['educacion_padre']." , 
                                                              ".$request['ocupacion_padre']." 
                                      ) ";
                                      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      $sql = "  INSERT INTO   hc_inscripcion_cd_aspectos_vivienda   (inscripcion_id, descripcion,
                                                                    energia, acueducto, alcantarillado,
                                                                    sanitario, animales, descripcion_animales )                                                        
                              VALUES  (                             ".$inscripcion['id']." ,
                                                                    '".$request['descripcion']."' , 
                                                                    '".$request['energia']."' , 
                                                                    '".$request['acueducto']."' , 
                                                                    '".$request['alcantarillado']."' , 
                                                                    '".$request['sanitario']."' , 
                                                                    '".$request['animales']."', 
                                                                    '".$request['descripcion_animales']."' 
                                      ) ";
                                      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;    
      
      foreach($request['estado'] as $key => $dtl)
      {      
        $sql = "  INSERT INTO   hc_inscripcion_cd_patologias    (inscripcion_id, evolucion,
                                                                patologia_id, estado, observacion)                                                        
                              VALUES (                          ".$inscripcion['id']." ,
                                                                ".$request['evolucion']." , 
                                                                ".$key." , 
                                                                '".$dtl."' , 
                                                                '".$request['observacion'][$key]."' 
                                     )";
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }                                
       if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
        $this->Commit(); 
        return true;
    }   
    
    /**
    *Esta funcion trae la edad del paciente de acuerdo a los intervalos que exiten
    *@param int $meses: edad en meses del paciente
    *
    * @return array
    */
    function traerEdadPaciente($meses)
    {
      //$this->debug=true;
      $this->ConexionTransaccion();
      $sql = "  SELECT  c.control_edad_id,
                        c.edad_uno,
                        c.edad_dos,
                        c.sw_estado
                FROM    control_edades_cd c
                WHERE   ".$meses." >= c.edad_uno
                AND     ".$meses." <= c.edad_dos
                AND     c.sw_estado = '1'   ";
                
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
    
    
    /**
    *Esta funcion registra la informacion del control del paciente
    *@param array $request: la informacion del formulario
    *
    * @return boolean
    */
    function registrarControl($request)
    {
      //$this->debug=true;
      //print_r($request);
      $this->ConexionTransaccion();
           
      foreach($request['estado_f'] as $key => $dtl)
      { 
        $sql = " INSERT INTO  hc_control_cd_exploracion_fisica  (exploracion_fisica_id, control_edad_id,
                                                                evolucion, division_cuerpo_id, estado_f,
                                                                observacion, usuario_id, fecha_registro)                                                        
                              VALUES (                          DEFAULT,
                                                                ".$request['control_edad_id']." ,
                                                                ".$request['evolucion']." , 
                                                                ".$key." , 
                                                                '".$dtl."' , 
                                                                '".$request['observacion'][$key]."' ,  
                                                                ".UserGetUID()." ,
                                                                now()
                                      )";
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }      
      
      foreach($request['estado_c'] as $key => $dtl)
      { 
        $sql = "  INSERT INTO   hc_control_cd_cavidad_oral  (cavidad_oral_id, control_edad_id, 
                                                            evolucion, tipo_cavidad_oral_id, estado_c, 
                                                            observacion, usuario_id, fecha_registro)                                                        
                              VALUES (                      DEFAULT,
                                                            ".$request['control_edad_id']." ,
                                                            ".$request['evolucion']." ,
                                                            ".$key." , 
                                                            '".$dtl."' , 
                                                            '".$request['observacion'][$key]."' ,
                                                            ".UserGetUID()." ,
                                                            now()
                                      )"; 
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }     

      foreach($request['estado_a'] as $key => $dtl)
      { 
        $sql = "  INSERT INTO   hc_control_cd_alimentacion      (alimentacion_id, control_edad_id, 
                                                                evolucion, tipo_alimentacion_id, 
                                                                estado_a, usuario_id, fecha_registro)                                                        
                              VALUES (                          DEFAULT,
                                                                ".$request['control_edad_id']." ,
                                                                ".$request['evolucion']." ,
                                                                ".$key." , 
                                                                '".$dtl."' , 
                                                                ".UserGetUID()." ,
                                                                now()
                                      )"; 
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }          
 
        $this->Commit(); 
        return true;
    }   
     
    /*
    *Funcion que trae los niveles de educacion 
    *
    * @return array
    */   
    function traerDatosNivelesEducacion()
    {
      //$this->debug=true;
      $sql = " SELECT   *  FROM tipos_educacion
               WHERE     sw_estado='1'";
               
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
    
    /*
    *Funcion que trae los tipos de ocupaciones
    *
    * @return array
    */   
    function traerDatosOcupaciones()
    {
      //$this->debug=true;
      $sql = " SELECT   *  FROM tipos_ocupaciones
               WHERE     sw_estado='1'";
               
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
    
    /*
    *Funcion que trae las patologias 
    *
    * @return array
    */   
    function traerPatologias()
    { 
      //$this->debug=true;
      $sql = " SELECT  p.patologia_id,
                       p.descripcion,
                       p.sw_estado
               FROM    patologias p
               WHERE   p.sw_estado = '1' ";
      
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
    *Funcion que trae las partes del cuerpo, que le llaman exploracion fisica 
    *
    * @return array
    */   
    function traerExploracionFisica()
    { 
      //$this->debug=true;
      $sql = " SELECT  e.division_cuerpo_id,
                       e.division_cuerpo_descripcion,
                       e.sw_exploracion_fisica  
               FROM    division_cuerpo e
               WHERE   e.sw_exploracion_fisica = '1' ";
      
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
    *Funcion que trae las cavidades orales
    *
    * @return array
    */   
    function traerTiposCavidadOral()
    { 
      //$this->debug=true;
      $sql = " SELECT  c.tipo_cavidad_oral_id,
                       c.descripcion,
                       c.sw_estado
               FROM    tipos_cavidad_oral c
               WHERE   c.sw_estado = '1' ";
      
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
    *Funcion que trae los aspectos alimenticios
    *
    * @return array
    */   
    function traerDatosAlimenticios()
    { 
      //$this->debug=true;
      $sql = " SELECT  a.tipo_alimentacion_id,
                       a.descripcion,
                       a.tipo_campo,
                       a.sw_estado
               FROM    tipos_alimentacion a
               WHERE   a.sw_estado = '1' ";
      
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
    *Funcion que trae las escalas abreviadas del desarrollo de a cuerdo a la edad del paciente
    *@param int $meses: edad en meses del paciente
    *
    * @return array
    */
    function traerPuntajesEscalasAD($meses)
    {
      //$this->debug=true;
      $sql = " SELECT  s.seccion_escala_id,
                       s.sw_estado,
                       s.descripcion,
                       i.seccion_escala_id,
                       i.item,
                       i.descripcion as descripcion_item,
                       i.edad_minima,
                       i.edad_maxima
               FROM    secciones_escala_ad s,
                       secciones_escala_ad_item i
               WHERE   s.seccion_escala_id = i.seccion_escala_id
               AND     ".$meses." > i.edad_minima       
               AND     ".$meses." <= i.edad_maxima 
               AND     s.sw_estado = '1'  ";
               
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][]= $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }  
    
    /**
    *Esta funcion calcula el puntaje de las escalas abreviadas del desarrollo
    *
    * @return array
    */
    function CalcularPuntaje()
    {
      //$this->debug=true;
      $sql = " SELECT  s.seccion_escala_id,
                       s.descripcion,
                       s.sw_estado,
                       i.seccion_escala_id,
                       i.item,
                       i.descripcion as descripcion_item
               FROM    secciones_escala_ad s,
                       secciones_escala_ad_item i
               WHERE   i.item = i.seccion_escala_id  ";
               
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
    *Esta funcion es para verificar si el paciente ya esta registrado o no
    *
    * @return array
    */
    function verificarInscripcion()
    {
      //$this->debug=true;
      $sql = " SELECT  i.paciente_id,
                       i.tipo_id_paciente,
                       p.paciente_id
               FROM    hc_inscripcion_pyp_crecimiento_y_desarrollo i,
                       pacientes p
               WHERE   i.paciente_id = p.paciente_id  ";
               
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
    *Esta funcion inserta los puntajes de las escalas abreviadas del desarrollo
    *@param array $form: la informacion del formulario
    *
    * @return boolean
    */
    function insertarPuntajes($form)
    {
      //$this->debug=true;
      //print_r($request);
      $this->ConexionTransaccion();
      
      $arreglo = array();
      foreach($form['escalas'] as $key => $dtl)
      {
        $mayor = 0;
        foreach($dtl as $k1 => $dt)
        {
          if($dt > $mayor)
            $mayor  = $dt;
          $total = $mayor + $mayor;
        }
        $arreglo[$key] = $mayor;  
      }
      foreach($form['escalas'] as $key => $dtl)
      {
        $mayor = 0;
        foreach($dtl as $k1 => $dt)
        {
            $sql = " INSERT INTO  hc_control_secciones_escala_ad    
                        (
                          seccion_puntajes_id, 
                          seccion_escala_id,
                          item, 
                          evolucion, 
                          puntaje_total, 
                          usuario_id, 
                          fecha_registro
                        )                                                        
                        VALUES 
                        (
                          DEFAULT,
                          ".$key." , 
                          ".$k1." , 
                          ".$form['evolucion']." ,
                          ".$total." , 
                          ".UserGetUID()." ,
                          now()
                        )";
                                      
        }
        
       if(!$rst = $this->ConexionTransaccion($sql)) return false;
      } 
  
      $this->Commit(); 
      return true;
    }
    
    /**
    *Esta funcion trae todos los datos de la inscripcion
    *@param array $request: todos los datos del registro del paciente
    *
    * @return array
    */
    function traerDatos($request)
    {
      //$this->debug=true;
      $sql = " SELECT   i.inscripcion_id,  
                        i.establecimiento_donde_nacio,
                        i.embarazo_deseado,  
                        i.edad_gestional_semanas,
                        i.atencion_prenatal,
                        i.talla,
                        i.peso,
                        i.per_cef,
                        i.apgar,
                        i.cantidad_hermanos,
                        i.sw_ubicacion,
                        f.inscripcion_id,
                        f.nombre_madre,
                        f.edad_madre,
                        f.educacion_madre,
                        f.ocupacion_madre,
                        f.nombre_padre,
                        f.edad_padre,
                        f.educacion_padre,
                        f.ocupacion_padre,
                        v.inscripcion_id,
                        v.descripcion,
                        v.energia,
                        v.acueducto,
                        v.alcantarillado,
                        v.sanitario,
                        v.animales,
                        v.descripcion_animales,
                        p.inscripcion_id,
                        p.estado,
                        p.observacion,
                        g.tipo_id_paciente,
                        g.paciente_id
              FROM      hc_inscripcion_pyp_crecimiento_y_desarrollo i ,
                        hc_inscripcion_cd_aspectos_vivienda v ,
                        hc_inscripcion_cd_familiares f ,
                        hc_inscripcion_cd_patologias p ,
                        ingresos g
              WHERE     i.inscripcion_id = f.inscripcion_id
              AND       f.inscripcion_id = v.inscripcion_id
              AND       v.inscripcion_id = p.inscripcion_id 
              AND       g.tipo_id_paciente = '".$request['tipo_id_paciente']."'
              AND       g.paciente_id = '".$request['paciente_id']."' ";
      
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
    
    /**
    *Esta funcion es para verificar si el paciente ya esta registrado o no
    *
    * @return array
    */
    function verificarDatosControl()
    {
      //$this->debug=true;
      $sql = " SELECT  f.control_edad_id,
                       o.control_edad_id,
                       a.control_edad_id
               FROM    hc_control_cd_exploracion_fisica f,
                       hc_control_cd_cavidad_oral o,
                       hc_control_cd_alimentacion a
               WHERE   f.control_edad_id = o.control_edad_id
               AND     o.control_edad_id = a.control_edad_id ";
               
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
    *Funcion que trae las escalas abreviadas del desarrollo de a cuerdo a la edad del paciente
    *@param int $meses: edad en meses del paciente
    *
    * @return array
    */
    function traerPuntajes($meses)
    {
      //$this->debug=true;
      $sql = " SELECT  s.seccion_escala_id,
                       s.descripcion,
                       i.descripcion as descripcion_item,
                       i.edad_minima,
                       i.edad_maxima,
                       c.seccion_escala_id,
                       c.item,
                       c.puntaje_total
               FROM    secciones_escala_ad s,
                       secciones_escala_ad_item i,
                       hc_control_secciones_escala_ad c
               WHERE   s.seccion_escala_id = i.seccion_escala_id
               AND     s.seccion_escala_id = c.seccion_escala_id
               AND     ".$meses." > i.edad_minima       
               AND     ".$meses." <= i.edad_maxima  ";
               
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][]= $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }  

  }
?>