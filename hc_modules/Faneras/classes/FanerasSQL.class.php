<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: FanerasSQL.class.php,v 1.1 2009/11/06 14:42:11 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: TriageSQL
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class FanerasSQL extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function FanerasSQL(){}
    /**
		* Funcion donde se obtiene la informacion de las coordenadas para hacer la seleccion
    * de las areas dentro de la imagen
    *
    * @return mixed
    */
    function ObtenerCoordenadas()
    {
      $sql  = "SELECT coordenada_id,";
      $sql .= " 	    datos_coordenadas,";
      $sql .= " 	    franja,";
      $sql .= " 	    sw_mapa ";
      $sql .= "FROM   coordenadas_piel_faneras ";
      $sql .= "ORDER BY coordenada_id ";

      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }    
    /**
		* Funcion donde se obtiene la informacion de los tipos de sensibilidad
    * parametrizados, con sus respectivos colores
    *
    * @return mixed
    */
    function ObtenerSensibilidad()
    {
      $sql  = "SELECT PS.piel_fanera_color_id,";
      $sql .= " 	    PS.sensibiliad_descripcion,";
      $sql .= " 	    CL.color_nombre1 ";
      $sql .= "FROM   piel_faneras_sensibilidad PS, ";
      $sql .= "       colores CL ";
      $sql .= "WHERE  CL.color_id = PS.color_id ";

      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
    /**
    * Funcion donde se obtiene la informacion de las clasificaciones realizadas
    *
    * @param integer $evolucion Identificador de la evolucion
    *
    * @return mixed
    */
    function ObtenerClasificacion($evolucion)
    {
      $sql  = "SELECT PF.coordenada_id, ";
      $sql .= "       PF.piel_fanera_color_id , ";
      $sql .= "       PF.persistencia, ";
      $sql .= "       PF.referencia, ";
      $sql .= "       PF.aumento_descripcion, ";
      $sql .= "       PF.disminucion_descripcion, ";
      $sql .= "       PF.observacion, ";
      $sql .= "       PS.sensibiliad_descripcion, ";
      $sql .= "       CF.franja, ";
      $sql .= "       CF.sw_mapa ";
      $sql .= "FROM   hc_clasificacion_piel_faneras PF, ";
      $sql .= "       piel_faneras_sensibilidad PS, ";
      $sql .= "       coordenadas_piel_faneras CF ";
      $sql .= "WHERE  PF.evolucion_id = ".$evolucion." ";
      $sql .= "AND    PF.piel_fanera_color_id = PS.piel_fanera_color_id ";
      $sql .= "AND    PF.coordenada_id = CF.coordenada_id ";
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }    
    /**
    * Funcion donde se obtienen los puntajes eva parametrizados
    *
    * @return mixed
    */
    function ObtenerPuntajesEva()
    {
      $sql  = "SELECT puntaje_eva, ";
      $sql .= "       equivalencia_eva ";
      $sql .= "FROM   puntajes_eva ";
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }     
    /**
    * Funcion donde se obtiene el puntuje ingresado de evolucion
    *
    * @param integer $evolucion Identificador de la evolucion
    *
    * @return mixed
    */
    function ObtenerPuntajesEvaEvolucion($evolucion)
    {
      $sql  = "SELECT puntaje_eva ";
      $sql .= "FROM   hc_clasificacion_piel_faneras_eva ";
      $sql .= "WHERE  evolucion_id = ".$evolucion." ";
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }      
    /**
    * Funcion donde se ingresa la informacion de la clasificación de los
    * sectores nerviosos
    *
    * @param array $datos Arreglo con la informacion a ingresar del sector
    *
    * @return boolean
    */
    function IngresarClasificacion($datos)
    {
      $sql  = "INSERT INTO hc_clasificacion_piel_faneras ";
      $sql .= "   ( ";
      $sql .= "     evolucion_id, ";
      $sql .= "     coordenada_id, ";
      $sql .= "     piel_fanera_color_id , ";
      $sql .= "     persistencia, ";
      $sql .= "     referencia, ";
      $sql .= "     aumento_descripcion, ";
      $sql .= "     disminucion_descripcion, ";
      $sql .= "     observacion ";
      $sql .= "   ) ";
      $sql .= "VALUES ";
      $sql .= "   ( ";
      $sql .= "     ".$datos['evolucion_id'].", ";
      $sql .= "     ".$datos['area_id'].", ";
      $sql .= "     ".$datos['sensibilidad'].", ";
      $sql .= "    '".$datos['persistencia']."', ";
      $sql .= "    '".$datos['referencia']."', ";
      $sql .= "    '".$datos['aumento']."', ";
      $sql .= "    '".$datos['disminucion']."', ";
      $sql .= "    '".$datos['observacion']."' ";
      $sql .= "   ) ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $this->RegistrarSubmodulo($datos);
      
      return true;
    }    
    /**
    * Funcion donde se actualiza la información de la clasificación de un
    * sector nervioso
    *
    * @param array $datos Arreglo de datos con la informacion del sector seleccionado
    *
    * @return array
    */
    function ActualizarClasificacion($datos)
    {
      $sql  = "UPDATE hc_clasificacion_piel_faneras ";
      $sql .= "SET    piel_fanera_color_id = ".$datos['sensibilidad']." , ";
      $sql .= "       persistencia = '".$datos['persistencia']."', ";
      $sql .= "       referencia = '".$datos['referencia']."', ";
      $sql .= "       aumento_descripcion = '".$datos['aumento']."', ";
      $sql .= "       disminucion_descripcion = '".$datos['disminucion']."', ";
      $sql .= "       observacion = '".$datos['observacion']."'";
      $sql .= "WHERE  evolucion_id = ".$datos['evolucion_id']." ";
      $sql .= "AND    coordenada_id = ".$datos['area_id']." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $this->RegistrarSubmodulo($datos);
      
      return true;
    }
    /**
    * Funcion donde se elimina la información de la clasificación de un
    * sector nervioso
    *
    * @param array $datos Arreglo de datos con la informacion del sector seleccionado
    *
    * @return array
    */
    function EliminarClasificacion($datos)
    {
      $sql  = "DELETE FROM hc_clasificacion_piel_faneras ";
      $sql .= "WHERE  evolucion_id = ".$datos['evolucion_id']." ";
      $sql .= "AND    coordenada_id = ".$datos['area_id']." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      return true;
    }
    /**
    * Funcion donde se ingresa la informacion del puntaje de eva
    *
    * @param array $datos Arreglo con la informacion a ingresar del sector
    *
    * @return boolean
    */
    function IngresarPuntajeEva($datos)
    {
      $sql  = "INSERT INTO hc_clasificacion_piel_faneras_eva ";
      $sql .= "   ( ";
      $sql .= "     evolucion_id, ";
      $sql .= "     puntaje_eva ";
      $sql .= "   ) ";
      $sql .= "VALUES ";
      $sql .= "   ( ";
      $sql .= "     ".$datos['evolucion_id'].", ";
      $sql .= "     ".$datos['puntaje_eva']." ";
      $sql .= "   ) ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $this->RegistrarSubmodulo($datos);
      
      return true;
    }
    /**
    * Funcion donde se actualiza la información de la clasificación de un
    * sector nervioso
    *
    * @param array $datos Arreglo de datos con la informacion del sector seleccionado
    *
    * @return array
    */
    function ActualizarPuntajeEva($datos)
    {
      $sql  = "UPDATE hc_clasificacion_piel_faneras_eva ";
      $sql .= "SET    puntaje_eva = ".$datos['puntaje_eva']." ";
      $sql .= "WHERE  evolucion_id = ".$datos['evolucion_id']." ";
 
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $this->RegistrarSubmodulo($datos);
      
      return true;
    }
    /**
    * Funcion donde se registra al evolucion por submodulo, para hacer la impresion del
    * mismo
    *
    * @param array $datos Arreglo de datos con la informacion de la evolucion y el ingreso
    * @param array $DatosVersion Arreglo de datos con la informacion de la version
    *
    * @return true;
    */
		function RegistrarSubmodulo($datos,$DatosVersion=array('version'=>'1','subversion'=>'0'))
    {
      $sql  = "DELETE FROM hc_evoluciones_submodulos ";
      $sql .= "WHERE  evolucion_id = ".$datos['evolucion_id']." ";
      $sql .= "AND    submodulo = 'PlanTerapeuticoHospitalizacion'; ";
      $sql .= "INSERT INTO hc_evoluciones_submodulos";
      $sql .= "    (";
      $sql .= "      ingreso,";
      $sql .= "      evolucion_id,";
      $sql .= "      submodulo,";
      $sql .= "      version,";
      $sql .= "      subversion";
      $sql .= "    )";
      $sql .= "VALUES";
      $sql .= "    (";
      $sql .= "      ".$datos['ingreso'].",";
      $sql .= "      ".$datos['evolucion_id'].",";
      $sql .= "      'Faneras',";
      $sql .= "      '".$DatosVersion['version']."',";
      $sql .= "      '".$DatosVersion['subversion']."' ";
      $sql .= "   )";

      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      return true;
    }
  }
?>