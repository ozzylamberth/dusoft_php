<?php

/**
* Submodulo de Areas de Conducta (Examen Fisico)
*
* @author Tizziano Perea O.
* @version 1.0
* @package SIIS
* $Id: hc_Conducta_ExamenFisico_Examen.class.php,v 1.1 2007/11/30 20:41:12 tizziano Exp $
*/

class Examen
{
     
     /**
     * Constructor
     *
     * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
     * @access public
     */
	function Examen()
     {
          return true;
     }

     
     /**
     * Get_ConductaFisica
     * Metodo para obtener los datos de la conducta fisica del paciente.
     *
     * @return array.
     * @access public
     */
     function Get_ConductaFisica()
     {
		list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          
          //Consulta de datos
          $query="SELECT * FROM hc_psicologia_conducta_fisica
          	   WHERE ingreso = ".SessionGetVar("Ingreso")."
                  AND evolucion_id = ".SessionGetVar("Evolucion").";";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_psicologia_conducta_fisica";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
          
          while(!$result->EOF)
          {
               $VectorFD = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
         
          $dbconn->CommitTrans();
		return $VectorFD;
     }

           
     /**
     * Get_ConductaMental
     * Metodo para obtener los datos de la conducta fisica del paciente.
     *
     * @return array.
     * @access public
     */
     function Get_ConductaMental()
     {
		list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          
          //Consulta de datos
          $query="SELECT * FROM hc_psicologia_conducta_mental
          	   WHERE ingreso = ".SessionGetVar("Ingreso")."
                  AND evolucion_id = ".SessionGetVar("Evolucion").";";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_psicologia_conducta_mental";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
			return false;
		}
          
          while(!$result->EOF)
          {
               $VectorMD = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
         
          $dbconn->CommitTrans();
		return $VectorMD;
     }
     /**
     * Get_IngresoEvaluacion
     * Metodo para obtener el ingreso de la evaluacion preanestesica.
     *
     * @return array.
     * @access public
     */
     function Get_IngresoEvaluacion()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query = "SELECT count(ingreso)
          		FROM hc_preanestesia_ingresos
                    WHERE ingreso = ".SessionGetVar("Ingreso").";";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_ingresos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
          
          $dbconn->CommitTrans();
          
          if($result->fields[count] > 0)
          { return 1; }else{ return 0; }
     }
     
     /**
     * Put_IngresoEvaluacion
     * Metodo para insertar los datos iniciales de la evaluacion preanestesica, Ingreso, 
     * Usuario y Fecha.
     *
     * @return array.
     * @access public
     */
     function Put_IngresoEvaluacion()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query = "INSERT INTO hc_preanestesia_ingresos 
          		VALUES (".SessionGetVar("Ingreso").", ".SessionGetVar("Usuario").", 'now()');";

		$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Insertar en hc_preanestesia_ingresos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
          
          $dbconn->CommitTrans();
		return true;     
     }
     
     /**
     * Get_EvaluadoresCardiovasculares
     * Metodo para obtener los evaluadores cardiovasculares para la evaluacion preanestesica.
     *
     * @return array.
     * @access public
     */
     function Get_EvaluadoresCardiovasculares()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query = "SELECT *
          		FROM hc_preanestesia_tipos_evaluacion_cardiovascular;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_tipos_evaluacion_cardiovascular";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               while(!$result->EOF)
               {
                    $vector[] = $result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
		}
          
          $dbconn->CommitTrans();
          return $vector;
     }

     /**
     * Get_EvaluadoresRespiratorios
     * Metodo para obtener los evaluadores respiratorios para la evaluacion preanestesica.
     *
     * @return array.
     * @access public
     */
     function Get_EvaluadoresRespiratorios()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query = "SELECT *
          		FROM hc_preanestesia_tipos_evaluacion_respiratoria;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_tipos_evaluacion_respiratoria";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               while(!$result->EOF)
               {
                    $vector[] = $result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
		}
          
          $dbconn->CommitTrans();
          return $vector;
     }
     
     /**
     * Get_EvaluadoresMetabolicos
     * Metodo para obtener los evaluadores metabolicos para la evaluacion preanestesica.
     *
     * @return array.
     * @access public
     */
     function Get_EvaluadoresMetabolicos()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query = "SELECT *
          		FROM hc_preanestesia_tipos_evaluacion_metabolica;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_tipos_evaluacion_metabolica";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               while(!$result->EOF)
               {
                    $vector[] = $result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
		}
          
          $dbconn->CommitTrans();
          return $vector;
     }

     /**
     * Get_EvaluadoresGastrointestinal
     * Metodo para obtener los evaluadores gastrointestinales para la evaluacion preanestesica.
     *
     * @return array.
     * @access public
     */
     function Get_EvaluadoresGastrointestinal()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query = "SELECT *
          		FROM hc_preanestesia_tipos_evaluacion_gastrointestinal;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_tipos_evaluacion_gastrointestinal";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               while(!$result->EOF)
               {
                    $vector[] = $result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
		}
          
          $dbconn->CommitTrans();
          return $vector;
     }
     
     /**
     * Get_EvaluadoresGastrointestinal
     * Metodo para obtener los evaluadores renales para la evaluacion preanestesica.
     *
     * @return array.
     * @access public
     */
     function Get_EvaluadoresRenal()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query = "SELECT *
          		FROM hc_preanestesia_tipos_evaluacion_renal;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_tipos_evaluacion_renal";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               while(!$result->EOF)
               {
                    $vector[] = $result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
		}
          
          $dbconn->CommitTrans();
          return $vector;
     }

     /**
     * Get_EvaluadoresNeurologicos
     * Metodo para obtener los evaluadores neurologicos para la evaluacion preanestesica.
     *
     * @return array.
     * @access public
     */
     function Get_EvaluadoresNeurologicos()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query = "SELECT *
          		FROM hc_preanestesia_tipos_evaluacion_neurologico;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_tipos_evaluacion_neurologico";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               while(!$result->EOF)
               {
                    $vector[] = $result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
		}
          
          $dbconn->CommitTrans();
          return $vector;
     }

     /**
     * Get_EvaluadoresEsqueletico
     * Metodo para obtener los evaluadores musculo-esqueleticos para la evaluacion preanestesica.
     *
     * @return array.
     * @access public
     */
     function Get_EvaluadoresEsqueleticos()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query = "SELECT *
          		FROM hc_preanestesia_tipos_evaluacion_esqueletico;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_tipos_evaluacion_esqueletico";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               while(!$result->EOF)
               {
                    $vector[] = $result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
		}
          
          $dbconn->CommitTrans();
          return $vector;
     }

     /**
     * Get_EvaluadoresHematologicos
     * Metodo para obtener los evaluadores hematologicos para la evaluacion preanestesica.
     *
     * @return array.
     * @access public
     */
     function Get_EvaluadoresHematologicos()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query = "SELECT *
          		FROM hc_preanestesia_tipos_evaluacion_hematologico;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_tipos_evaluacion_hematologico";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               while(!$result->EOF)
               {
                    $vector[] = $result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
		}
          
          $dbconn->CommitTrans();
          return $vector;
     }

     /**
     * Get_EvaluadoresHepatico
     * Metodo para obtener los evaluadores hepaticos para la evaluacion preanestesica.
     *
     * @return array.
     * @access public
     */
     function Get_EvaluadoresHepatico()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query = "SELECT *
          		FROM hc_preanestesia_tipos_evaluacion_hepatico;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_tipos_evaluacion_hepatico";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               while(!$result->EOF)
               {
                    $vector[] = $result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
		}
          
          $dbconn->CommitTrans();
          return $vector;
     }

     /**
     * Get_EvaluadoresGinecos
     * Metodo para obtener los evaluadores ginecos para la evaluacion preanestesica.
     *
     * @return array.
     * @access public
     */
     function Get_EvaluadoresGinecos()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query = "SELECT *
          		FROM hc_preanestesia_tipos_evaluacion_ginecos;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_tipos_evaluacion_ginecos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               while(!$result->EOF)
               {
                    $vector[] = $result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
		}
          
          $dbconn->CommitTrans();
          return $vector;
     }

     /**
     * Get_EvaluadoresIntubacion
     * Metodo para obtener los evaluadores de prediccion de intubacion para la evaluacion preanestesica.
     *
     * @return array.
     * @access public
     */
     function Get_EvaluadoresIntubacion()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query = "SELECT *
          		FROM hc_preanestesia_tipos_evaluacion_intubacion;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_tipos_evaluacion_intubacion";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               while(!$result->EOF)
               {
                    $vector[] = $result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
		}
          
          $dbconn->CommitTrans();
          return $vector;
     }
               
     /**
     * Get_EvaluadoresOtrosFactores
     * Metodo para obtener los evaluadores de otros factores para la evaluacion preanestesica.
     *
     * @return array.
     * @access public
     */
     function Get_EvaluadoresOtrosFactores()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query = "SELECT *
          		FROM hc_preanestesia_tipos_evaluacion_otros;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_tipos_evaluacion_otros";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               while(!$result->EOF)
               {
                    $vector[] = $result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
		}
          
          $dbconn->CommitTrans();
          return $vector;
     }
     
     /**
     * GetRegistroDiagnosticosI
     * Metodo para obtener los diagnosticos registrados en la evaluacion.
     *
     * @return array.
     * @access public
     */
     function GetRegistroDiagnosticosI()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
          
          $query = "SELECT A.diagnostico_id, B.diagnostico_nombre
          	   FROM hc_preanestesia_diagnostico_preoperatorio AS A,
          		   diagnosticos AS B
                  WHERE A.ingreso = ".SessionGetVar("Ingreso")."
                  AND A.diagnostico_id = B.diagnostico_id;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_diagnostico_preoperatorio";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               while(!$result->EOF)
               {
                    $vector[] = $result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
		}
          
          $dbconn->CommitTrans();
          return $vector;
     }

     /**
     * GetRegistroCargosCirugia
     * Metodo para obtener los cargos de la cirugia propuesta en la evaluacion.
     *
     * @return array.
     * @access public
     */
     function GetRegistroCargosCirugia()
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
          
          $query = "SELECT A.cargo_cups, B.descripcion
          	     FROM hc_preanestesia_ciruga_propuesta AS A,
          		     cups AS B
                    WHERE A.ingreso = ".SessionGetVar("Ingreso")."
                    AND A.cargo_cups = B.cargo;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_preanestesia_ciruga_propuesta";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
               while(!$result->EOF)
               {
                    $vector[] = $result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
               }
		}
          
          $dbconn->CommitTrans();
          return $vector;
     }
     
     
     /**
     * InsertarDatos_EvaluacionPreanestesica
     * Metodo para registrar los datos de la evaluacion preanestesica.
     *
     * @return array.
     * @access public
     */
     function InsertarDatos_EvaluacionPreanestesica($Caracteristicas, $Signos, $CardioDetalle, $CardioDescripcion, $RespDetalle, $RespDescripcion, $MetabDetalle, $MetabDescripcion, $GastroDetalle, $GastroDescripcion, $RenalDetalle, $RenalDescripcion,
               							  $NeuroDetalle, $NeuroDescripcion, $MusculoDetalle, $MusculoDescripcion, $HemaDetalle, $HemaDescripcion, $HepaDetalle, $HepaDescripcion, $GinecoDetalle, $GinecoDescripcion, $IntuDetalle, $IntuDescripcion, $OtrosDetalle, $OtrosDescripcion)
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
          //Descripciones caracterisricas
          $Sql = "SELECT count(ingreso) FROM hc_preanestesia_descripciones_caracteristicas WHERE ingreso = ".SessionGetVar("Ingreso").";";
          $result = $dbconn->Execute($Sql);
          
          if($result->fields[count] == 0)
          {
               $Query = "INSERT INTO hc_preanestesia_descripciones_caracteristicas
                         VALUES (".SessionGetVar("Ingreso").", '".$Caracteristicas['Especialidad']."',
                              '".$Caracteristicas['AnevPrevia']."', '".$Caracteristicas['Alergias']."',
                              '".$Caracteristicas['Drogas']."', '".$Caracteristicas['ASA']."',
                              '".$Caracteristicas['IT']."', '".$Caracteristicas['PlanAnes']."',
                              '".$Caracteristicas['Reserva']."', '".$Caracteristicas['Premed']."',
                              '".$Caracteristicas['HelpDx']."');";
               
               $dbconn->Execute($Query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Insertar en hc_preanestesia_descripciones_caracteristicas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          //Signos Vitales
          $Sql = "SELECT count(ingreso) FROM hc_preanestesia_signos_vitales WHERE ingreso = ".SessionGetVar("Ingreso").";";
          $result = $dbconn->Execute($Sql);
          
          if($result->fields[count] == 0)
          {
               $Query = "";
               $Estatura = $Signos['Estatura'] / 100;
               $IMC = $Signos['Peso'] / ($Estatura * $Estatura);
               $IMC = round($IMC, 2);
               $Query = "INSERT INTO hc_preanestesia_signos_vitales
                         VALUES (".SessionGetVar("Ingreso").", '".$Signos['Peso']."',
                              '".$Signos['Estatura']."', '".$Signos['TA']."',
                              '".$Signos['TB']."', '".$Signos['FC']."',
                              '".$Signos['FR']."', '".$Signos['Temp']."',
                              '".$IMC."');";
               
               $dbconn->Execute($Query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Insertar en hc_preanestesia_signos_vitales";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
		}
                    
          //E. Cardiovascular
          if($CardioDetalle AND $CardioDescripcion)
          {
               $Sql = "DELETE FROM hc_preanestesia_descripciones_cardiovascular_d WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);

               $Sql = "DELETE FROM hc_preanestesia_descripciones_cardiovascular WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);
               
               $Query = "INSERT INTO hc_preanestesia_descripciones_cardiovascular
                         VALUES (".SessionGetVar("Ingreso").", '".$CardioDescripcion."');";
               $dbconn->Execute($Query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Insertar en hc_preanestesia_descripciones_cardiovascular";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
			else
               {    
                    $Query = "";                     
                    for($i=0;$i<sizeof($CardioDetalle); $i++)
                    {
                         $Query = "INSERT INTO hc_preanestesia_descripciones_cardiovascular_d
                                   VALUES (".SessionGetVar("Ingreso").", '".$CardioDetalle[$i]."');";
                         $dbconn->Execute($Query);
                         if($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Insertar en hc_preanestesia_descripciones_cardiovascular_d";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                         $Query = "";
                    }
               }
          }

          //E. Respiratorio
          if($RespDetalle AND $RespDescripcion)
          {
               $Sql = "DELETE FROM hc_preanestesia_descripciones_respiratorio_d WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);

               $Sql = "DELETE FROM hc_preanestesia_descripciones_respiratorio WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);
               
               $Query = "INSERT INTO hc_preanestesia_descripciones_respiratorio
                         VALUES (".SessionGetVar("Ingreso").", '".$RespDescripcion."');";
               $dbconn->Execute($Query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Insertar en hc_preanestesia_descripciones_respiratorio";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
			else
               {    
                    $Query = "";                     
                    for($i=0;$i<sizeof($RespDetalle); $i++)
                    {
                         $Query = "INSERT INTO hc_preanestesia_descripciones_respiratorio_d
                                   VALUES (".SessionGetVar("Ingreso").", '".$RespDetalle[$i]."');";
                         $dbconn->Execute($Query);
                         if($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Insertar en hc_preanestesia_descripciones_respiratorio_d";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                         $Query = "";
                    }
               }
          }
		
          //E. Metabolico
          if($MetabDetalle AND $MetabDescripcion)
          {
               $Sql = "DELETE FROM hc_preanestesia_descripciones_metabolico_d WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);

               $Sql = "DELETE FROM hc_preanestesia_descripciones_metabolico WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);
               
               $Query = "INSERT INTO hc_preanestesia_descripciones_metabolico
                         VALUES (".SessionGetVar("Ingreso").", '".$MetabDescripcion."');";
               $dbconn->Execute($Query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Insertar en hc_preanestesia_descripciones_metabolico";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
			else
               {    
                    $Query = "";                     
                    for($i=0;$i<sizeof($MetabDetalle); $i++)
                    {
                         $Query = "INSERT INTO hc_preanestesia_descripciones_metabolico_d
                                   VALUES (".SessionGetVar("Ingreso").", '".$MetabDetalle[$i]."');";
                         $dbconn->Execute($Query);
                         if($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Insertar en hc_preanestesia_descripciones_metabolico_d";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                         $Query = "";
                    }
               }
          }
          
          //E. Gastrointestinal
          if($GastroDetalle AND $GastroDescripcion)
          {
               $Sql = "DELETE FROM hc_preanestesia_descripciones_gastro_d WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);

               $Sql = "DELETE FROM hc_preanestesia_descripciones_gastro WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);
               
               $Query = "INSERT INTO hc_preanestesia_descripciones_gastro
                         VALUES (".SessionGetVar("Ingreso").", '".$GastroDescripcion."');";
               $dbconn->Execute($Query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Insertar en hc_preanestesia_descripciones_gastro";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
			else
               {    
                    $Query = "";                     
                    for($i=0;$i<sizeof($GastroDetalle); $i++)
                    {
                         $Query = "INSERT INTO hc_preanestesia_descripciones_gastro_d
                                   VALUES (".SessionGetVar("Ingreso").", '".$GastroDetalle[$i]."');";
                         $dbconn->Execute($Query);
                         if($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Insertar en hc_preanestesia_descripciones_gastro_d";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                         $Query = "";
                    }
               }
          }
          
          //E. Renal
          if($RenalDetalle AND $RenalDescripcion)
          {
               $Sql = "DELETE FROM hc_preanestesia_descripciones_renal_d WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);

               $Sql = "DELETE FROM hc_preanestesia_descripciones_renal WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);
               
               $Query = "INSERT INTO hc_preanestesia_descripciones_renal
                         VALUES (".SessionGetVar("Ingreso").", '".$RenalDescripcion."');";
               $dbconn->Execute($Query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Insertar en hc_preanestesia_descripciones_renal";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
			else
               {    
                    $Query = "";                     
                    for($i=0;$i<sizeof($RenalDetalle); $i++)
                    {
                         $Query = "INSERT INTO hc_preanestesia_descripciones_renal_d
                                   VALUES (".SessionGetVar("Ingreso").", '".$RenalDetalle[$i]."');";
                         $dbconn->Execute($Query);
                         if($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Insertar en hc_preanestesia_descripciones_renal_d";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                         $Query = "";
                    }
               }
          }
          
          //E. Neurologico
          if($NeuroDetalle AND $NeuroDescripcion)
          {
               $Sql = "DELETE FROM hc_preanestesia_descripciones_neuro_d WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);

               $Sql = "DELETE FROM hc_preanestesia_descripciones_neuro WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);
               
               $Query = "INSERT INTO hc_preanestesia_descripciones_neuro
                         VALUES (".SessionGetVar("Ingreso").", '".$NeuroDescripcion."');";
               $dbconn->Execute($Query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Insertar en hc_preanestesia_descripciones_neuro";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
			else
               {    
                    $Query = "";                     
                    for($i=0;$i<sizeof($NeuroDetalle); $i++)
                    {
                         $Query = "INSERT INTO hc_preanestesia_descripciones_neuro_d
                                   VALUES (".SessionGetVar("Ingreso").", '".$NeuroDetalle[$i]."');";
                         $dbconn->Execute($Query);
                         if($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Insertar en hc_preanestesia_descripciones_neuro_d";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                         $Query = "";
                    }
               }
          }
          
          //E. Musculo Esqueletico
          if($MusculoDetalle AND $MusculoDescripcion)
          {
               $Sql = "DELETE FROM hc_preanestesia_descripciones_esqueletico_d WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);

               $Sql = "DELETE FROM hc_preanestesia_descripciones_esqueletico WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);
               
               $Query = "INSERT INTO hc_preanestesia_descripciones_esqueletico
                         VALUES (".SessionGetVar("Ingreso").", '".$MusculoDescripcion."');";
               $dbconn->Execute($Query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Insertar en hc_preanestesia_descripciones_esqueletico";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
			else
               {    
                    $Query = "";                     
                    for($i=0;$i<sizeof($MusculoDetalle); $i++)
                    {
                         $Query = "INSERT INTO hc_preanestesia_descripciones_esqueletico_d
                                   VALUES (".SessionGetVar("Ingreso").", '".$MusculoDetalle[$i]."');";
                         $dbconn->Execute($Query);
                         if($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Insertar en hc_preanestesia_descripciones_esqueletico_d";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                         $Query = "";
                    }
               }
          }
          
          //E. Hematologico
          if($HemaDetalle AND $HemaDescripcion)
          {
               $Sql = "DELETE FROM hc_preanestesia_descripciones_hematologico_d WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);

               $Sql = "DELETE FROM hc_preanestesia_descripciones_hematologico WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);
               
               $Query = "INSERT INTO hc_preanestesia_descripciones_hematologico
                         VALUES (".SessionGetVar("Ingreso").", '".$HemaDescripcion."');";
               $dbconn->Execute($Query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Insertar en hc_preanestesia_descripciones_hematologico";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
			else
               {    
                    $Query = "";                     
                    for($i=0;$i<sizeof($HemaDetalle); $i++)
                    {
                         $Query = "INSERT INTO hc_preanestesia_descripciones_hematologico_d
                                   VALUES (".SessionGetVar("Ingreso").", '".$HemaDetalle[$i]."');";
                         $dbconn->Execute($Query);
                         if($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Insertar en hc_preanestesia_descripciones_hematologico_d";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                         $Query = "";
                    }
               }
          }
          
          //E. Hepatico
          if($HepaDetalle AND $HepaDescripcion)
          {
               $Sql = "DELETE FROM hc_preanestesia_descripciones_hepatico_d WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);

               $Sql = "DELETE FROM hc_preanestesia_descripciones_hepatico WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);
               
               $Query = "INSERT INTO hc_preanestesia_descripciones_hepatico
                         VALUES (".SessionGetVar("Ingreso").", '".$HepaDescripcion."');";
               $dbconn->Execute($Query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Insertar en hc_preanestesia_descripciones_hepatico";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
			else
               {    
                    $Query = "";                     
                    for($i=0;$i<sizeof($HepaDetalle); $i++)
                    {
                         $Query = "INSERT INTO hc_preanestesia_descripciones_hepatico_d
                                   VALUES (".SessionGetVar("Ingreso").", '".$HepaDetalle[$i]."');";
                         $dbconn->Execute($Query);
                         if($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Insertar en hc_preanestesia_descripciones_hepatico_d";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                         $Query = "";
                    }
               }
          }
          
          //E. Gineco-Obstetrico
          if($GinecoDetalle AND $GinecoDescripcion)
          {
               $Sql = "DELETE FROM hc_preanestesia_descripciones_gineco_d WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);

               $Sql = "DELETE FROM hc_preanestesia_descripciones_gineco WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);
               
               $Query = "INSERT INTO hc_preanestesia_descripciones_gineco
                         VALUES (".SessionGetVar("Ingreso").", '".$GinecoDescripcion."');";
               $dbconn->Execute($Query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Insertar en hc_preanestesia_descripciones_gineco";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
			else
               {    
                    $Query = "";                     
                    for($i=0;$i<sizeof($GinecoDetalle); $i++)
                    {
                         $Query = "INSERT INTO hc_preanestesia_descripciones_gineco_d
                                   VALUES (".SessionGetVar("Ingreso").", '".$GinecoDetalle[$i]."');";
                         $dbconn->Execute($Query);
                         if($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Insertar en hc_preanestesia_descripciones_gineco_d";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                         $Query = "";
                    }
               }
          }
          
          //E. Prediccion Intubacion
          if($IntuDetalle AND $IntuDescripcion)
          {
               $Sql = "DELETE FROM hc_preanestesia_descripciones_intubacion_d WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);

               $Sql = "DELETE FROM hc_preanestesia_descripciones_intubacion WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);
               
               $Query = "INSERT INTO hc_preanestesia_descripciones_intubacion
                         VALUES (".SessionGetVar("Ingreso").", '".$IntuDescripcion."');";
               $dbconn->Execute($Query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Insertar en hc_preanestesia_descripciones_intubacion";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
			else
               {    
                    $Query = "";                     
                    for($i=0;$i<sizeof($IntuDetalle); $i++)
                    {
                         $Query = "INSERT INTO hc_preanestesia_descripciones_intubacion_d
                                   VALUES (".SessionGetVar("Ingreso").", '".$IntuDetalle[$i]."');";
                         $dbconn->Execute($Query);
                         if($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Insertar en hc_preanestesia_descripciones_intubacion_d";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                         $Query = "";
                    }
               }
          }
          
          //E. Otros Factores
          if($OtrosDetalle AND $OtrosDescripcion)
          {
               $Sql = "DELETE FROM hc_preanestesia_descripciones_otros_d WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);

               $Sql = "DELETE FROM hc_preanestesia_descripciones_otros WHERE ingreso = ".SessionGetVar("Ingreso").";";
               $dbconn->Execute($Sql);
               
               $Query = "INSERT INTO hc_preanestesia_descripciones_otros
                         VALUES (".SessionGetVar("Ingreso").", '".$OtrosDescripcion."');";
               
               $dbconn->Execute($Query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Insertar en hc_preanestesia_descripciones_otros";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
			else
               {    
                    $Query = "";                     
                    for($i=0;$i<sizeof($OtrosDetalle); $i++)
                    {
                         $Query = "INSERT INTO hc_preanestesia_descripciones_otros_d
                                   VALUES (".SessionGetVar("Ingreso").", '".$OtrosDetalle[$i]."');";
                         $dbconn->Execute($Query);
                         if($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Insertar en hc_preanestesia_descripciones_otros_d";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                         $Query = "";
                    }
               }
          }
          
          $dbconn->CommitTrans();
          return true;
     }

     /**
     * GetDatos_DescripcionCaracteristicas
     * Metodo para obtener los datos insertados en las descripciones caracteristicas de la evaluacion.
     *
     * @return array.
     * @access public
     */
     function GetDatos_DescripcionCaracteristicas()
     {
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_caracteristicas 
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_caracteristicas";
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
     * GetDatos_SignosVitales
     * Metodo para obtener los datos insertados en la evaluacion de signos vitales.
     *
     * @return array.
     * @access public
     */
     function GetDatos_SignosVitales()
     {
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          $Sql = "SELECT * 
                  FROM hc_preanestesia_signos_vitales
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_caracteristicas";
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
     * GetDatos_EvaluacionCardio
     * Metodo para obtener los datos insertados en la evaluacion cardiovascular.
     *
     * @return array.
     * @access public
     */
     function GetDatos_EvaluacionCardio()
     {
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_cardiovascular
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_cardiovascular";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          while(!$result->EOF)
          {
               $Vector['Maestro'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_cardiovascular_d
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_cardiovascular_d";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          
          while(!$result->EOF)
          {
               $Vector['Detalle'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $dbconn->CommitTrans();
          return $Vector;
     }

     /**
     * GetDatos_EvaluacionRespiratorio
     * Metodo para obtener los datos insertados en la evaluacion respiratoria.
     *
     * @return array.
     * @access public
     */
     function GetDatos_EvaluacionRespiratorio()
     {
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_respiratorio
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_respiratorio";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          while(!$result->EOF)
          {
               $Vector['Maestro'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_respiratorio_d
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_respiratorio_d";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          
          while(!$result->EOF)
          {
               $Vector['Detalle'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $dbconn->CommitTrans();
          return $Vector;
     }
     
     /**
     * GetDatos_EvaluacionMetabolica
     * Metodo para obtener los datos insertados en la evaluacion metabolica.
     *
     * @return array.
     * @access public
     */
     function GetDatos_EvaluacionMetabolica()
     {
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_metabolico
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_metabolico";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          while(!$result->EOF)
          {
               $Vector['Maestro'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_metabolico_d
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_metabolico_d";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          
          while(!$result->EOF)
          {
               $Vector['Detalle'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $dbconn->CommitTrans();
          return $Vector;
     }
     
     /**
     * GetDatos_EvaluacionGastroIntestinal
     * Metodo para obtener los datos insertados en la evaluacion gastrointestinal.
     *
     * @return array.
     * @access public
     */
     function GetDatos_EvaluacionGastroIntestinal()
     {
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_gastro
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_gastro";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          while(!$result->EOF)
          {
               $Vector['Maestro'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_gastro_d
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_gastro_d";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          
          while(!$result->EOF)
          {
               $Vector['Detalle'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $dbconn->CommitTrans();
          return $Vector;
     }
     
     /**
     * GetDatos_EvaluacionRenal
     * Metodo para obtener los datos insertados en la evaluacion renal.
     *
     * @return array.
     * @access public
     */
     function GetDatos_EvaluacionRenal()
     {
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_renal
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_renal";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          while(!$result->EOF)
          {
               $Vector['Maestro'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_renal_d
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_renal_d";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          
          while(!$result->EOF)
          {
               $Vector['Detalle'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $dbconn->CommitTrans();
          return $Vector;
     }
     
     /**
     * GetDatos_EvaluacionNeurologica
     * Metodo para obtener los datos insertados en la evaluacion Neurologica.
     *
     * @return array.
     * @access public
     */
     function GetDatos_EvaluacionNeurologica()
     {
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_neuro
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_neuro";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          while(!$result->EOF)
          {
               $Vector['Maestro'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_neuro_d
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_neuro_d";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          
          while(!$result->EOF)
          {
               $Vector['Detalle'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $dbconn->CommitTrans();
          return $Vector;
     }
     
     /**
     * GetDatos_EvaluacionEsqueletico
     * Metodo para obtener los datos insertados en la evaluacion musculo-esqueletica.
     *
     * @return array.
     * @access public
     */
     function GetDatos_EvaluacionEsqueletico()
     {
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_esqueletico
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_esqueletico";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          while(!$result->EOF)
          {
               $Vector['Maestro'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_esqueletico_d
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_esqueletico_d";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          
          while(!$result->EOF)
          {
               $Vector['Detalle'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $dbconn->CommitTrans();
          return $Vector;
     }

     /**
     * GetDatos_EvaluacionHematologico
     * Metodo para obtener los datos insertados en la evaluacion hematologico.
     *
     * @return array.
     * @access public
     */
     function GetDatos_EvaluacionHematologico()
     {
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_hematologico
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_hematologico";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          while(!$result->EOF)
          {
               $Vector['Maestro'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_hematologico_d
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_hematologico_d";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          
          while(!$result->EOF)
          {
               $Vector['Detalle'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $dbconn->CommitTrans();
          return $Vector;
     }
     
     /**
     * GetDatos_EvaluacionHepatico
     * Metodo para obtener los datos insertados en la evaluacion hepatico.
     *
     * @return array.
     * @access public
     */
     function GetDatos_EvaluacionHepatico()
     {
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_hepatico
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_hepatico";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          while(!$result->EOF)
          {
               $Vector['Maestro'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_hepatico_d
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_hepatico_d";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          
          while(!$result->EOF)
          {
               $Vector['Detalle'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $dbconn->CommitTrans();
          return $Vector;
     }
     
     /**
     * GetDatos_EvaluacionGineco
     * Metodo para obtener los datos insertados en la evaluacion gineco.
     *
     * @return array.
     * @access public
     */
     function GetDatos_EvaluacionGineco()
     {
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_gineco
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_gineco";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          while(!$result->EOF)
          {
               $Vector['Maestro'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_gineco_d
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_gineco_d";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          
          while(!$result->EOF)
          {
               $Vector['Detalle'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $dbconn->CommitTrans();
          return $Vector;
     }
          
     /**
     * GetDatos_EvaluacionIntubacion
     * Metodo para obtener los datos insertados en la evaluacion intubacion.
     *
     * @return array.
     * @access public
     */
     function GetDatos_EvaluacionIntubacion()
     {
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_intubacion
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_intubacion";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          while(!$result->EOF)
          {
               $Vector['Maestro'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_intubacion_d
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_intubacion_d";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          
          while(!$result->EOF)
          {
               $Vector['Detalle'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $dbconn->CommitTrans();
          return $Vector;
     }
     
     /**
     * GetDatos_EvaluacionOtrosFactores
     * Metodo para obtener los datos insertados en la evaluacion otros factores.
     *
     * @return array.
     * @access public
     */
     function GetDatos_EvaluacionOtrosFactores()
     {
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_otros
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_otros";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          while(!$result->EOF)
          {
               $Vector['Maestro'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $Sql = "SELECT * 
                  FROM hc_preanestesia_descripciones_otros_d
                  WHERE ingreso = ".SessionGetVar("Ingreso").";";
          
          $result = $dbconn->Execute($Sql);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Consultar en hc_preanestesia_descripciones_otros_d";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          
          while(!$result->EOF)
          {
               $Vector['Detalle'] = $result->GetRowAssoc($toUpper=false);
               $result->MoveNext();
          }
          
          $dbconn->CommitTrans();
          return $Vector;
     }
          
}
?>
