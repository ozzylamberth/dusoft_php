<?php

     /**
     * Conducta Examen Fisico Xajax
     *
     * @author Tizziano Perea
     * @version 1.0
     * @package SIIS
     * $Id: Conducta_ExamenFisico_Xajax.php,v 1.1 2007/11/30 20:41:12 tizziano Exp $
     */
	
     function InsertFisico($VectorF)
	{
		$objResponse = new xajaxResponse();
          $html = InsertarDatos_ExamenF($VectorF);
          if($html)
		{
               $objResponse->assign("ExF","style.display","none"); 
               $objResponse->assign("ExfCon","style.display","block");
               $objResponse->assign("ExfCon","innerHTML",$html);
		}
		return $objResponse;
	}

     function InsertarDatos_ExamenF($VectorF)
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
          //Consulta seq.
          $query="SELECT NEXTVAL('public.hc_psicologia_conducta_fisica_conducta_id_seq');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar Secuencia";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
          $conducta_id = $result->fields[0];
          
          //Insert
          $query = "INSERT INTO hc_psicologia_conducta_fisica 
          		VALUES (".$conducta_id.", ".SessionGetVar("Ingreso").", ".SessionGetVar("Evolucion").",
                    	   '".$VectorF['Enfermedades']."', '".$VectorF['Medicamentos']."', '".$VectorF['Antecedentes']."',
                            '".$VectorF['Alimentacion']."', '".$VectorF['Sueno']."', '".$VectorF['Cigarrillo']."',
                            '".$VectorF['Alcohol']."', '".$VectorF['Drogas']."', '".$VectorF['Deporte']."');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Insertar en hc_psicologia_conducta_fisica";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}

          $dbconn->CommitTrans();
          $dbconn->Close();
          
          //Vista de datos
          $html.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"6\" class=\"modulo_table_title\">AREA DE CONDUCTA FISICA</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">ENFERMEDADES CRONICAS O SINTOMA GENERAL:</td>";
          $html.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$VectorF['Enfermedades']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">MEDICAMENTOS QUE CONSUME ACTUALMENTE:</td>";
          $html.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$VectorF['Medicamentos']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">ANTECEDENTES DE ENFERMEDADES FAMILIARES:</td>";
          $html.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$VectorF['Antecedentes']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">ALIMENTACION:</td>";
          $html.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$VectorF['Alimentacion']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"15%\">SUENO:</td>";
          $html.= "	<td class=\"modulo_list_oscuro\" align=\"left\" width=\"20%\">".$VectorF['Sueno']."</td>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"15%\">CIGARRILLO:</td>";
          $html.= "	<td class=\"modulo_list_oscuro\" align=\"left\" width=\"20%\">".$VectorF['Cigarrillo']."</td>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"15%\">ALCOHOL:</td>";
          $html.= "	<td class=\"modulo_list_oscuro\" align=\"left\" width=\"20%\">".$VectorF['Alcohol']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\">ALUCIONOGENOS:</td>";
          $html.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\">".$VectorF['Drogas']."</td>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\">DEPORTE:</td>";
          $html.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\">".$VectorF['Deporte']."</td>";
          $html.= "	</tr>";
          $html.= "	</table><BR>";
          
          return $html;
     }
     
     function InsertMental($VectorF)
	{
		$objResponse = new xajaxResponse();
          $html = InsertarDatos_ExamenM($VectorF);
          if($html)
          {
               $objResponse->assign("ExM","style.display","none");
               $objResponse->assign("ExmCon","style.display","block");
               $objResponse->assign("ExmCon","innerHTML",$html);
		}
		return $objResponse;
	}
     
     function InsertarDatos_ExamenM($VectorF)
     {
          list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
          //Consulta seq.
          $query="SELECT NEXTVAL('public.hc_psicologia_conducta_mental_conducta_id_seq');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar Secuencia";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
          $conducta_id = $result->fields[0];
          
          //Insert
          $query = "INSERT INTO hc_psicologia_conducta_mental 
          		VALUES (".$conducta_id.", ".SessionGetVar("Ingreso").", ".SessionGetVar("Evolucion").",
                    	   '".$VectorF['desiciones']."', '".$VectorF['Actividades']."', '".$VectorF['actitud']."',
                            '".$VectorF['Percepcion']."', '".$VectorF['Raciocinio']."', '".$VectorF['Atencion']."');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Insertar en hc_psicologia_conducta_mental";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}

          $dbconn->CommitTrans();
          
          //Vista de datos
		$html.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"6\" class=\"modulo_table_title\">AREA DE CONDUCTA MENTAL</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"20%\">RACIOCINIO:</td>";
          $html.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" width=\"30%\" align=\"left\">".$VectorF['Raciocinio']."</td>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"20%\">CONCENTRACION:</td>";
          $html.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" width=\"30%\" align=\"left\">".$VectorF['Atencion']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">CAPACIDAD PARA TOMAR DECISIONES:</td>";
          $html.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$VectorF['desiciones']."</td>";          
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">ACTIVIDADES EXTRACURRICULARES:</td>";
          $html.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$VectorF['Actividades']."</td>";          
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">SENTIMIENTO O ACTITUD GENERAL HACIA LA VIDA:</td>";
          $html.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$VectorF['actitud']."</td>";          
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">PERCEPCION DE SI MISMO:</td>";
          $html.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$VectorF['Percepcion']."</td>";          
          $html.= "	</tr>";
          $html.= "	</table>";
          
          return $html;
     }
?>