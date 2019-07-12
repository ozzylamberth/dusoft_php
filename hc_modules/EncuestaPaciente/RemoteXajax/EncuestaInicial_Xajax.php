<?php

     /**
     * Encuesta Inicial Pacientes Xajax.
     *
     * @author Tizziano Perea
     * @version 1.0
     * @package SIIS
     * $Id: EncuestaInicial_Xajax.php,v 1.1 2007/11/30 20:44:54 tizziano Exp $
     */
	
     function InsertEncuesta($Vector)
	{
		$objResponse = new xajaxResponse();
          $html = InsertarDatos_Encuesta($Vector);
          if($html)
		{
               $objResponse->assign("EncuIni","style.display","none"); 
               $objResponse->assign("EncuIniCon","style.display","block");
               $objResponse->assign("EncuIniCon","innerHTML",$html);
		}
		return $objResponse;
	}

     function InsertarDatos_Encuesta($Vector)
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
          //Consulta seq.
          $query="SELECT NEXTVAL('public.hc_psicologia_encuesta_inicial_encuesta_id_seq');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar Secuencia";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
          $conducta_id = $result->fields[0];
          
          //Validaciones
          if($Vector['asistencia'] == '1')
          { $asistencia = '1'; }else{ $asistencia = '0'; }

          if($Vector['avisos'] == '1')
          { $avisos = '1'; }else{ $avisos = '0'; }

          if($Vector['suspension'] == '1')
          { $suspension = '1'; }else{ $suspension = '0'; }

          if($Vector['compromiso'] == '1')
          { $compromiso = '1'; }else{ $compromiso = '0'; }
          
          //Insert
          $query = "INSERT INTO hc_psicologia_encuesta_inicial
          		VALUES (".$conducta_id.", ".SessionGetVar("Ingreso").", ".SessionGetVar("Evolucion").",
                    	   '".$Vector['motivo']."', '".$Vector['objetivo']."', '".$asistencia."',
                            '".$avisos."', '".$suspension."', '".$compromiso."', '".$Vector['concepto']."',
                    	   '".$Vector['otros']."', ".SessionGetVar("Usuario").", 'now()');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Insertar en hc_psicologia_concepto_personal";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}

          $dbconn->CommitTrans();
          $dbconn->Close();
          
          //Vista de datos
          $html.= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
          $html.= "	<tr>";
          $html.= "	<td class=\"modulo_table_title\">MOTIVO DE CONSULTA:</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$Vector['motivo']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"modulo_table_title\">OBJETIVOS INICIALES:</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$Vector['objetivo']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          
          if($Vector['asistencia'] == '1')
          { $asistencia = 'SI'; }else{ $asistencia = 'NO'; }

          if($Vector['avisos'] == '1')
          { $avisos = 'SI'; }else{ $avisos = 'NO'; }

          if($Vector['suspension'] == '1')
          { $suspension = 'SI'; }else{ $suspension = 'NO'; }

          if($Vector['compromiso'] == '1')
          { $compromiso = 'SI'; }else{ $compromiso = 'NO'; }

          $html.= "	<td class=\"modulo_table_title\">COMPROMISOS</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"modulo_list_oscuro\" align=\"left\">";
          $html.= "	<label class=\"label\">Asistir puntualmente a las sesiones:</label>&nbsp;&nbsp;&nbsp;<label class=\"label_error\">".$asistencia."</label><br>";
          $html.= "	<label class=\"label\">Avisar previamente la cancelación de la cita si no puede asistir:</label>&nbsp;&nbsp;&nbsp;<label class=\"label_error\">".$avisos."</label><br>";
          $html.= "	<label class=\"label\">Despues de 3 citas consecutivas de no asistencia puedo quedar suspendido del proceso:</label>&nbsp;&nbsp;&nbsp;<label class=\"label_error\">".$suspension."</label><br>";
          $html.= "	<label class=\"label\">Me compromento a cumplir con las tareas y ejercicios propuestos durante el proceso:</label>&nbsp;&nbsp;&nbsp;<label class=\"label_error\">".$compromiso."</label><br>";
          $html.= "	</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"modulo_table_title\">¿ COMO SE SINTIO DURANTE LA ENTREVISTA ?</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$Vector['concepto']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"modulo_table_title\">OTROS:</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$Vector['otros']."</td>";
          $html.= "	</tr>";
          $html.= "	</table><BR>";
          
          return $html;
     }
     
?>