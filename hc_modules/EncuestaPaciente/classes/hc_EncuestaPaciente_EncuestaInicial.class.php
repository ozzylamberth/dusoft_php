<?php

/**
* Submodulo de Encuesta Paciente
*
* @author Tizziano Perea O.
* @version 1.0
* @package SIIS
* $Id: hc_EncuestaPaciente_EncuestaInicial.class.php,v 1.1 2007/11/30 20:44:54 tizziano Exp $
*/

class EncuestaInicial
{
     
     /**
     * Constructor
     *
     * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
     * @access public
     */
	function EncuestaInicial()
     {
          return true;
     }

     
     /**
     * Get_DatosEncuesta
     * Metodo para obtener los datos de la encuesta inicial diligenciada por el paciente.
     *
     * @return array.
     * @access public
     */
     function Get_DatosEncuesta()
     {
		list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          
          //Consulta de datos
          $query="SELECT * FROM hc_psicologia_encuesta_inicial
          	   WHERE ingreso = ".SessionGetVar("Ingreso")."
                  AND evolucion_id = ".SessionGetVar("Evolucion").";";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_psicologia_encuesta_inicial";
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
