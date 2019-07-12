<?php

     /**
     * Conceptos Pacientes Xajax.
     *
     * @author Tizziano Perea
     * @version 1.0
     * @package SIIS
     * $Id: ConceptosPacientes_Xajax.php,v 1.1 2007/11/30 20:37:20 tizziano Exp $
     */
	
     function InsertConceptosPer($Vector)
	{
		$objResponse = new xajaxResponse();
          $html = InsertarDatos_ConceptosPer($Vector);
          if($html)
		{
               $objResponse->assign("Concep","style.display","none"); 
               $objResponse->assign("ConcepCon","style.display","block");
               $objResponse->assign("ConcepCon","innerHTML",$html);
		}
		return $objResponse;
	}

     function InsertarDatos_ConceptosPer($Vector)
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
          //Consulta seq.
          $query="SELECT NEXTVAL('public.hc_psicologia_concepto_personal_concepto_id_seq');";
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
          $query = "INSERT INTO hc_psicologia_concepto_personal
          		VALUES (".$conducta_id.", ".SessionGetVar("Ingreso").", ".SessionGetVar("Evolucion").",
                    	   '".$Vector['simismo']."', ".SessionGetVar("Usuario").", 'now()');";
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
          $html.= "	<td class=\"modulo_table_title\">CONCEPTO DE SI MISMO - (Frases claves)</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$Vector['simismo']."</td>";
          $html.= "	</tr>";
          $html.= "	</table><BR>";
          
          return $html;
     }
     
     function InsertConceptosOtros($Vector)
	{
		$objResponse = new xajaxResponse();
          $html = InsertarDatos_ConceptosOtros($Vector);
          if($html)
          {
               $objResponse->assign("Concep_Demas","style.display","none");
               $objResponse->assign("Concep_DemasCon","style.display","block");
               $objResponse->assign("Concep_DemasCon","innerHTML",$html);
		}
		return $objResponse;
	}
     
     function InsertarDatos_ConceptosOtros($Vector)
     {
          list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
          //Consulta seq.
          $query="SELECT NEXTVAL('public.hc_psicologia_concepto_demas_concepto_id_seq');";
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
          $query = "INSERT INTO hc_psicologia_concepto_demas
          		VALUES (".$conducta_id.", ".SessionGetVar("Ingreso").", ".SessionGetVar("Evolucion").",
                    	   '".$Vector['con_demas']."', ".SessionGetVar("Usuario").", 'now()');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Insertar en hc_psicologia_concepto_demas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}

          $dbconn->CommitTrans();
          
          //Vista de datos
          $html.= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
          $html.= "	<tr>";
          $html.= "	<td class=\"modulo_table_title\">CONCEPTO DE LOS DEMAS - (Figuras cercanas)</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$Vector['con_demas']."</td>";
          $html.= "	</tr>";
          $html.= "	</table><BR>";
          
          return $html;
     }
?>