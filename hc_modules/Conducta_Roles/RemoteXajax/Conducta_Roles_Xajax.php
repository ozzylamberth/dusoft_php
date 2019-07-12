<?php

     /**
     * Conducta Examen Roles Xajax
     *
     * @author Tizziano Perea
     * @version 1.0
     * @package SIIS
     * $Id: Conducta_Roles_Xajax.php,v 1.1 2007/11/30 20:43:03 tizziano Exp $
     */
	
     function InsertRolProfesional($VectorR)
	{
		$objResponse = new xajaxResponse();
          $html = InsertarDatos_RolProfesional($VectorR);
          if($html)
		{
               $objResponse->assign("RolProf","style.display","none"); 
               $objResponse->assign("RolProfDat","style.display","block");
               $objResponse->assign("RolProfDat","innerHTML",$html);
		}
		return $objResponse;
	}

     function InsertarDatos_RolProfesional($VectorR)
     {
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
          //Consulta seq.
          $query="SELECT NEXTVAL('public.hc_psicologia_roles_profesionales_conducta_id_seq');";
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
          $query = "INSERT INTO hc_psicologia_roles_profesionales 
          		VALUES (".$conducta_id.", ".SessionGetVar("Ingreso").", ".SessionGetVar("Evolucion").",
                    	   '".$VectorR['grado']."', '".$VectorR['eficiencia']."', '".$VectorR['economia']."',
                            '".$VectorR['ambicion']."', '".$VectorR['otros_prof']."');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Insertar en hc_psicologia_roles_profesionales";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}

          $dbconn->CommitTrans();
          $dbconn->Close();
          
          //Vista de datos
          $html.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"4\" class=\"modulo_table_title\">ROL PROFESIONAL O ACADEMICO</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">NIVEL DE AGRADO:</td>";
          $html.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$VectorR['grado']."</td>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">EFICIENCIA:</td>";
          $html.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$VectorR['eficiencia']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">SITUACION ECONOMICA:</td>";
          $html.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$VectorR['economia']."</td>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">AMBICIONES FUTURAS:</td>";
          $html.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$VectorR['ambicion']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\">OTROS:</td>";
          $html.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\">".$VectorR['otros_prof']."</td>";
          $html.= "	</tr>";
          $html.= "	</table><BR>";
          
          return $html;
     }
     
     function InsertRolPareja($VectorP)
	{
		$objResponse = new xajaxResponse();
          $html = InsertarDatos_RolPareja($VectorP);
          if($html)
          {
               $objResponse->assign("RolPar","style.display","none");
               $objResponse->assign("RolParDat","style.display","block");
               $objResponse->assign("RolParDat","innerHTML",$html);
		}
		return $objResponse;
	}
     
     function InsertarDatos_RolPareja($VectorP)
     {
          list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
          //Consulta seq.
          $query="SELECT NEXTVAL('public.hc_psicologia_roles_pareja_conducta_id_seq');";
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
          $query = "INSERT INTO hc_psicologia_roles_pareja 
          		VALUES (".$conducta_id.", ".SessionGetVar("Ingreso").", ".SessionGetVar("Evolucion").",
                    	   '".$VectorP['comunicacion']."', '".$VectorP['sexo']."', '".$VectorP['diversion']."',
                            '".$VectorP['hijos']."', '".$VectorP['otros_par']."');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Insertar en hc_psicologia_roles_pareja";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}

          $dbconn->CommitTrans();
          $dbconn->Close();
          
          //Vista de datos
          $html.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"4\" class=\"modulo_table_title\">ROL DE PAREJA</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">COMUNICACION:</td>";
          $html.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$VectorP['comunicacion']."</td>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">RELACIONES SEXUALES:</td>";
          $html.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$VectorP['sexo']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">DIVERSIONES COMPARTIDAS:</td>";
          $html.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$VectorP['diversion']."</td>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">ACUERDO CRIANZA DE HIJOS:</td>";
          $html.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$VectorP['hijos']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\">OTROS:</td>";
          $html.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\">".$VectorP['otros_par']."</td>";
          $html.= "	</tr>";
          $html.= "	</table><BR>";
          
          return $html;
     }
     
     function InsertRolSocial($VectorS)
	{
		$objResponse = new xajaxResponse();
          $html = InsertarDatos_RolSocial($VectorS);
          if($html)
          {
               $objResponse->assign("RolSoc","style.display","none");
               $objResponse->assign("RolSocDat","style.display","block");
               $objResponse->assign("RolSocDat","innerHTML",$html);
		}
		return $objResponse;
	}
     
     function InsertarDatos_RolSocial($VectorS)
     {
          list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
          //Consulta seq.
          $query="SELECT NEXTVAL('public.hc_psicologia_roles_sociales_conducta_id_seq');";
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
          $query = "INSERT INTO hc_psicologia_roles_sociales
          		VALUES (".$conducta_id.", ".SessionGetVar("Ingreso").", ".SessionGetVar("Evolucion").",
                    	   '".$VectorS['amigos']."', '".$VectorS['reuniones']."', '".$VectorS['relacionarse']."',
                            '".$VectorS['otros_soc']."');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Insertar en hc_psicologia_roles_sociales";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}

          $dbconn->CommitTrans();
          $dbconn->Close();
          
          //Vista de datos
          $html.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"4\" class=\"modulo_table_title\">ROL SOCIAL</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">AMIGOS:</td>";
          $html.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$VectorS['amigos']."</td>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">REUNIONES SOCIALES:</td>";
          $html.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$VectorS['reuniones']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"50%\" colspan=\"2\">FACILIDAD PARA RELACIONARSE:</td>";
          $html.= "	<td class=\"modulo_list_oscuro\" width=\"50%\" colspan=\"2\">".$VectorS['relacionarse']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\">OTROS:</td>";
          $html.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\">".$VectorS['otros_soc']."</td>";
          $html.= "	</tr>";
          $html.= "	</table><BR>";
          
          return $html;
     }
     
	function InsertRolFamiliar($VectorF)
	{
		$objResponse = new xajaxResponse();
          $html = InsertarDatos_RolFamiliar($VectorF);
          if($html)
          {
               $objResponse->assign("RolFam","style.display","none");
               $objResponse->assign("RolFamDat","style.display","block");
               $objResponse->assign("RolFamDat","innerHTML",$html);
		}
		return $objResponse;
	}
     
     function InsertarDatos_RolFamiliar($VectorF)
     {
          list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
          //Consulta seq.
          $query="SELECT NEXTVAL('public.hc_psicologia_roles_familiares_conducta_id_seq');";
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
          $query = "INSERT INTO hc_psicologia_roles_familiares
          		VALUES (".$conducta_id.", ".SessionGetVar("Ingreso").", ".SessionGetVar("Evolucion").",
                    	   '".$VectorF['padre']."', '".$VectorF['madre']."', '".$VectorF['hermanos']."',
                            '".$VectorF['actividades']."', '".$VectorF['otros_fam']."');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Insertar en hc_psicologia_roles_familiares";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}

          $dbconn->CommitTrans();
          $dbconn->Close();
          
          //Vista de datos
          $html.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"4\" class=\"modulo_table_title\">ROL FAMILIAR</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">RELACION PADRE:</td>";
          $html.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\" width=\"60%\">".$VectorF['padre']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">RELACION MADRE:</td>";
          $html.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\" width=\"60%\">".$VectorF['madre']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">RELACION HERMANOS:</td>";
          $html.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\" width=\"60%\">".$VectorF['hermanos']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">ACTIVIDADES FAMILIARES:</td>";
          $html.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\" width=\"60%\">".$VectorF['actividades']."</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">OTROS:</td>";
          $html.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\" width=\"60%\">".$VectorF['otros_fam']."</td>";
          $html.= "	</tr>";
          $html.= "	</table><BR>";
          
          return $html;
     }
         
?>