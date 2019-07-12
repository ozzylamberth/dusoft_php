<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: TriageSQL.class.php,v 1.1 2009/06/09 19:11:18 hugo Exp $
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
  class TriageSQL extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function TriageSQL(){}
    /**
  	* Funcion para obtener la informacion del triage
    *
    * @param string $empresa Identificador de la empresa
    * @param array $datosP Arreglo de datos con la informacion del paciente
    * @param integer $triage_id Referencia del triage, opcional
    *
    * @return mixed
  	*/
  	function ObtenerDatosTriage($empresa,$datosP,$triage_id)
  	{
      $sql  = "SELECT TR.triage_id, ";
      $sql .= "       TR.nivel_triage_id,  ";
      $sql .= "       TR.motivo_consulta,  ";
      $sql .= "       TR.observacion_medico, ";
      $sql .= "       TR.punto_admision_id,  ";
      $sql .= "       TR.tipo_id_paciente, "; 
      $sql .= "       TR.paciente_id,  ";
      $sql .= "       DE.descripcion, ";
      $sql .= "       TR.impresion_diagnostica, "; 
      $sql .= "       TR.hora_llegada,  ";
      $sql .= "       TE.nombre_tercero AS nombre, ";
      $sql .= "       PU.tipo_tercero_id, "; 
      $sql .= "       PU.tercero_id,  ";
      $sql .= "       PR.tarjeta_profesional, ";
      $sql .= "       TP.descripcion AS tipo_profesional, "; 
      $sql .= "       EP.descripcion AS especialidad ";
      $sql .= "FROM   triages TR,";
      $sql .= "       departamentos DE, ";
      $sql .= "       profesionales_usuarios PU ";
      $sql .= "				LEFT JOIN profesionales_especialidades AS PE ";
      $sql .= "				ON( PU.tipo_tercero_id = PE.tipo_id_tercero AND "; 
      $sql .= "				    PU.tercero_id = PE.tercero_id) ";
      $sql .= "				LEFT JOIN especialidades EP ";
      $sql .= "				ON( EP.especialidad = PE.especialidad), ";
      $sql .= "				terceros TE, ";
      $sql .= "				profesionales PR, ";
      $sql .= "				tipos_profesionales TP ";
      $sql .= "WHERE  TR.tipo_id_paciente = '".$datosP['tipo_id_paciente']."' ";
      $sql .= "AND    TR.paciente_id = '".$datosP['paciente_id']."' ";
      if($empresa)
        $sql .= "AND    TR.empresa_id = '".$empresa."' ";
      $sql .= "AND    TR.departamento = DE.departamento ";
      $sql .= "AND    TR.usuario_clasificacion = PU.usuario_id ";
      $sql .= "AND    TE.tipo_id_tercero = PU.tipo_tercero_id ";
      $sql .= "AND    TE.tercero_id = PU.tercero_id ";
      $sql .= "AND    PR.tipo_id_tercero = PU.tipo_tercero_id ";
      $sql .= "AND    PR.tercero_id = PU.tercero_id ";
      $sql .= "AND    TP.tipo_profesional = PR.tipo_profesional ";
      if($triage_id)
        $sql .= "AND    TR.triage_id = ".$triage_id." ";
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      if(!$rst->EOF)
      {
        $datos =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
    /*
    * Funcion donde se obtiene la informacion de los signos vitales del triage
    *
    * @param integer $triage_id Referencia del triage
    *
    * @return mixed
    */
		function ObtenerSignosVitales($triage)
		{
			$sql  = "SELECT signos_vitales_fc,";
      $sql .= "       signos_vitales_fr,";
      $sql .= "       signos_vitales_temperatura,";
      $sql .= "				signos_vitales_peso,";
      $sql .= "       signos_vitales_taalta,";
      $sql .= "       signos_vitales_tabaja,";
      $sql .= "       fecha,";
      $sql .= "       triage_id,";
      $sql .= "				usuario_id,";
      $sql .= "       evaluacion_dolor,";
      $sql .= "       respuesta_motora_id,";
      $sql .= "       respuesta_verbal_id,";
      $sql .= "				apertura_ocular_id,";
      $sql .= "       tipo_glasgow,";
      $sql .= "       sato2 ";
      $sql .= "FROM   signos_vitales_triages ";
      $sql .= "WHERE  triage_id= ".$triage." ";
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      if(!$rst->EOF)
      {
        $datos =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
    /**
    * Funcion donde se obtienen los signos vitales obligatorios a ser ingresados
    *
    * @return mixed
    */
    function ObtenerSignosObligatorios()
    {
			$sql  = "SELECT signo,";
      $sql .= "       sw_mostrar,";
      $sql .= "       sw_obligatorio,";
      $sql .= "       sw_cero ";
      $sql .= "FROM   triage_signos_vitales_obligatorios ";
      
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
  	* Funcion donde se obtienen los datos de las respuestas oculares que
    * se pueden seleccionar
    *
    * @return mixed
  	*/
  	function ObtenerRespuestaOcular()
  	{
			$sql  = "SELECT apertura_ocular_id, ";
      $sql .= "       descripcion ";
      $sql .= "FROM   hc_tipos_apertura_ocular ";
      $sql .= "ORDER BY apertura_ocular_id ASC";
      
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
  	* Funcion donde se obtienen los datos de las respuestas vervales que
    * se pueden seleccionar
    *
    * @param integer $edad_paciente Variable con la edda del paciente
    * @param integer $max_edad_pediatrica Variable con la maxima edad pediatrica
    *
    * @return mixed
  	*/
  	function ObtenerRespuestaVerbal($edad_paciente,$max_edad_lactante)
  	{
  		$sql  = "SELECT respuesta_verbal_id, ";
      if ($edad_paciente < $max_edad_lactante)
        $sql .= "       descripcion_lactante as descripcion ";
      else
        $sql .= "       descripcion ";
      $sql .= "FROM   hc_tipos_respuesta_verbal ";
      $sql .= "ORDER BY respuesta_verbal_id ASC";

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
  	* Funcion donde se obtienen los datos de las respuestas motoras que
    * se pueden seleccionar
    *
    * @param integer $edad_paciente Variable con la edda del paciente
    * @param integer $max_edad_pediatrica Variable con la maxima edad pediatrica
    *
    * @return mixed
  	*/
  	function ObtenerRespuestaMotora($edad_paciente,$max_edad_lactante)
  	{
			$sql  = "SELECT respuesta_motora_id,";
      if ($edad_paciente < $max_edad_lactante)
        $sql .= "       descripcion_lactante as descripcion ";
      else
        $sql .= "       descripcion ";

      $sql .= "FROM   hc_tipos_respuesta_motora ";
      $sql .= "ORDER BY respuesta_motora_id ASC ";

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
		* Funcion donde se obtiene la informacion de los diferentes niveles de 
    * triage que se pueden seleccionar
    *
    * @return mixed
    */
    function ObtenerNivelesTriage()
    {
      $sql  = "SELECT * ";
      $sql .= "FROM   niveles_triages ";
      $sql .= "WHERE  nivel_triage_id !='0' ";
      $sql .= "ORDER BY indice_de_orden ";

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
    * Funcion donde se actualiza la clasificacion del triage
    *
    * @return array $request Arreglo de datos con la informacion ingresado por el usuario
    * @return integer $usuario Identificador del usuario
    *
    * @return boolean
    */
    function ActualizarClasificacionTriage($request,$usuario)
    {
      $sql  = "UPDATE triages ";
      $sql .= "SET    nivel_triage_id = '".$request['nivel_triage']."',";
      $sql .= "				usuario_reclasifica = ".$usuario." ";
      $sql .= "WHERE  triage_id = ".$request['triage_id']." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      return true;
    }    
  }
?>