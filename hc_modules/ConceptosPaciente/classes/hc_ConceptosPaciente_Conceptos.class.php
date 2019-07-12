<?php

/**
* Submodulo de Conceptos Paciente
*
* @author Tizziano Perea O.
* @version 1.0
* @package SIIS
* $Id: hc_ConceptosPaciente_Conceptos.class.php,v 1.1 2007/11/30 20:37:20 tizziano Exp $
*/

class Conceptos
{
     
     /**
     * Constructor
     *
     * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
     * @access public
     */
	function Conceptos()
     {
          return true;
     }

     
     /**
     * Get_ConceptosPersonal
     * Metodo para obtener los datos de los conceptos personales de cada paciente.
     *
     * @return array.
     * @access public
     */
     function Get_ConceptosPersonal()
     {
		list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          
          //Consulta de datos
          $query="SELECT * FROM hc_psicologia_concepto_personal
          	   WHERE ingreso = ".SessionGetVar("Ingreso")."
                  AND evolucion_id = ".SessionGetVar("Evolucion").";";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_psicologia_concepto_personal";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
          
          while(!$result->EOF)
          {
               $Vector = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
         
          $dbconn->CommitTrans();
		return $Vector;
     }

           
     /**
     * Get_ConceptosOtros
     * Metodo para obtener los datos de los conceptos hacia otras personas de cada paciente.
     *
     * @return array.
     * @access public
     */
     function Get_ConceptosOtros()
     {
		list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          
          //Consulta de datos
          $query="SELECT * FROM hc_psicologia_concepto_demas
          	   WHERE ingreso = ".SessionGetVar("Ingreso")."
                  AND evolucion_id = ".SessionGetVar("Evolucion").";";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_psicologia_concepto_demas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
			return false;
		}
          
          while(!$result->EOF)
          {
               $Vector = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
         
          $dbconn->CommitTrans();
		return $Vector;
     }
          
}
?>
