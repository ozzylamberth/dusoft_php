<?php

/**
* Submodulo de Areas de Conducta (Roles)
*
* @author Tizziano Perea O.
* @version 1.0
* @package SIIS
* $Id: hc_Conducta_Roles_Examen_HTML.class.php,v 1.1 2007/11/30 20:43:03 tizziano Exp $
*/

class Examen_HTML
{

     /**
     * Constructor
     *
     * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
     * @access public
     */
	function Examen_HTML()
     {
     	return true;
     }
     
     function frmHistoria()
     {
          $this->salida="";
          return $this->salida;
     }
     
     function frmConsulta()
     {
          return true;
     }
     
     /**
     * Funcion que señaliza una palabra para simbolizar que esta en estado de alerta
     * @return boolean
     */
     function SetStyle($campo)
     {
          if ($this->frmError[$campo] || $campo=="MensajeError")
          {
               if ($campo=="MensajeError")
               {
                    return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
               }
               return ("label_error");
          }
          return ("label");
     }

     /**
     * Metodo para generar la vista HTML.
     *
     * @param array $RolAcademico Datos del rol academico o profesional.
     * @param array $RolPareja Datos del rol de pareja.
     * @param array $RolSocial Datos del rol social.
     * @param array $RolFamiliar Datos del rol familiar.
     *
     * @return string
     * @access public
     */
     function frmForma($RolAcademico, $RolPareja, $RolSocial, $RolFamiliar)
     {
          $this->salida.= ThemeAbrirTablaSubModulo('INFORMACION AREAS DE CONDUCTA - ROLES');
          $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida.= 		$this->SetStyle("MensajeError");
          $this->salida.= "	</table><br>";
          
          if(!$RolAcademico)
          {
               $this->salida.= "	<form name=\"RolProfesional\" id=\"RolProfesional\" method=\"post\">";          
               $this->salida.= "	<div id=\"RolProf\">";          
               $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_table_list_title\">ROL PROFESIONAL O ACADEMICO</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\" width=\"25%\">NIVEL DE AGRADO:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\"><input type=\"text\" name=\"grado\" id=\"grado\" class=\"input-text\" size=\"35\" maxlength=\"256\" multiline></td>";
               $this->salida.= "	<td class=\"modulo_table_title\" width=\"25%\">EFICIENCIA:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\"><input type=\"text\" name=\"eficiencia\" id=\"eficiencia\" class=\"input-text\" size=\"35\" maxlength=\"256\" multiline></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\" width=\"25%\">SITUACION ECONOMICA:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\"><input type=\"text\" name=\"economia\" id=\"economia\" class=\"input-text\" size=\"35\" maxlength=\"256\" multiline></td>";
               $this->salida.= "	<td class=\"modulo_table_title\" width=\"25%\">AMBICIONES FUTURAS:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\"><input type=\"text\" name=\"ambicion\" id=\"ambicion\" class=\"input-text\" size=\"35\" maxlength=\"256\" multiline></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_table_title\">OTROS:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"otros_prof\" id=\"otros_prof\" class=\"input-text\" cols=\"65%\" rows=\"1\"></textarea></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"4\" align=\"center\" class=\"modulo_list_oscuro\"><input type=\"button\" name=\"save_rolporf\" id=\"save_rolporf\" class=\"input-submit\" value=\"INSERTAR\" onclick=InsertarRolProfesional();></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
               $this->salida.= "</div>";
               $this->salida.="</form>";
               
               $this->salida.= "<div id=\"RolProfDat\" style=\"display:none\"></div>";
     
               $this->salida.= "<br>";
          }
          else
          {
               $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_table_title\">ROL PROFESIONAL O ACADEMICO</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">NIVEL DE AGRADO:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$RolAcademico['agrado']."</td>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">EFICIENCIA:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$RolAcademico['eficiencia']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">SITUACION ECONOMICA:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$RolAcademico['economia']."</td>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">AMBICIONES FUTURAS:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$RolAcademico['ambiciones']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\">OTROS:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\">".$RolAcademico['otros_detalles']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
          }          
          
          if(!$RolPareja)
          {
               $this->salida.= "	<form name=\"RolPareja\" id=\"RolPareja\" method=\"post\">";          
               $this->salida.= "	<div id=\"RolPar\">";          
               $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_table_list_title\">ROL DE PAREJA</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\" width=\"25%\">COMUNICACION:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\"><input type=\"text\" name=\"comunicacion\" id=\"comunicacion\" class=\"input-text\" size=\"35\" maxlength=\"256\" multiline></td>";
               $this->salida.= "	<td class=\"modulo_table_title\" width=\"25%\">RELACIONES SEXUALES:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\"><input type=\"text\" name=\"sexo\" id=\"sexo\" class=\"input-text\" size=\"35\" maxlength=\"256\" multiline></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\" width=\"25%\">DIVERSIONES COMPARTIDAS:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\"><input type=\"text\" name=\"diversion\" id=\"diversion\" class=\"input-text\" size=\"35\" maxlength=\"256\" multiline></td>";
               $this->salida.= "	<td class=\"modulo_table_title\" width=\"25%\">ACUERDO CRIANZA DE HIJOS:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\"><input type=\"text\" name=\"hijos\" id=\"hijos\" class=\"input-text\" size=\"35\" maxlength=\"256\" multiline></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_table_title\">OTROS:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"otros_par\" id=\"otros_par\" class=\"input-text\" cols=\"65%\" rows=\"1\"></textarea></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"4\" align=\"center\" class=\"modulo_list_oscuro\"><input type=\"button\" name=\"save_rolpar\" id=\"save_rolpar\" class=\"input-submit\" value=\"INSERTAR\" onclick=InsertarRolPareja();></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
               $this->salida.= "</div>";
               $this->salida.="</form>";
               
               $this->salida.= "<div id=\"RolParDat\" style=\"display:none\"></div>";
     
               $this->salida.= "<br>";
          }
          else
          {
	          $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_table_title\">ROL DE PAREJA</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">COMUNICACION:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$RolPareja['comunicacion']."</td>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">RELACIONES SEXUALES:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$RolPareja['relaciones_sexuales']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">DIVERSIONES COMPARTIDAS:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$RolPareja['diversion']."</td>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">ACUERDO CRIANZA DE HIJOS:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$RolPareja['crianza_hijos']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\">OTROS:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\">".$RolPareja['otros_detalles']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
          }

          if(!$RolSocial)
          {
               $this->salida.= "	<form name=\"RolSocial\" id=\"RolSocial\" method=\"post\">";          
               $this->salida.= "	<div id=\"RolSoc\">";          
               $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_table_list_title\">ROL DE SOCIAL</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\" width=\"25%\">AMIGOS:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\"><input type=\"text\" name=\"amigos\" id=\"amigos\" class=\"input-text\" size=\"35\" maxlength=\"256\" multiline></td>";
               $this->salida.= "	<td class=\"modulo_table_title\" width=\"25%\">REUNIONES SOCIALES:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\"><input type=\"text\" name=\"reuniones\" id=\"reuniones\" class=\"input-text\" size=\"35\" maxlength=\"256\" multiline></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\" width=\"50%\" colspan=\"2\">FACILIDAD PARA RELACIONARSE:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"50%\" colspan=\"2\"><input type=\"text\" name=\"relacionarse\" id=\"relacionarse\" class=\"input-text\" size=\"69\" maxlength=\"256\" multiline></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_table_title\">OTROS:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"otros_soc\" id=\"otros_soc\" class=\"input-text\" cols=\"65%\" rows=\"1\"></textarea></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"4\" align=\"center\" class=\"modulo_list_oscuro\"><input type=\"button\" name=\"save_rolsoc\" id=\"save_rolsoc\" class=\"input-submit\" value=\"INSERTAR\" onclick=InsertarRolSocial();></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
               $this->salida.= "</div>";
               $this->salida.="</form>";
               
               $this->salida.= "<div id=\"RolSocDat\" style=\"display:none\"></div>";
     
               $this->salida.= "<br>";
          }
          else
          {
               $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_table_title\">ROL SOCIAL</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">AMIGOS:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$RolSocial['amigos']."</td>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">REUNIONES SOCIALES:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$RolSocial['reuniones']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"50%\" colspan=\"2\">FACILIDAD PARA RELACIONARSE:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" width=\"50%\" colspan=\"2\">".$RolSocial['facilidad_relacionarse']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\">OTROS:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\">".$RolSocial['otros_detalles']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
          }          
          
          if(!$RolFamiliar)
          {
               $this->salida.= "	<form name=\"RolFamiliar\" id=\"RolFamiliar\" method=\"post\">";          
               $this->salida.= "	<div id=\"RolFam\">";          
               $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_table_list_title\">ROL FAMILIAR</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_table_title\" width=\"40%\">RELACION PADRE:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\" width=\"60%\"><textarea name=\"padre\" id=\"padre\" class=\"input-text\" cols=\"100%\" rows=\"1\"></textarea></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_table_title\" width=\"40%\">RELACION MADRE:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\" width=\"60%\"><textarea name=\"madre\" id=\"madre\" class=\"input-text\" cols=\"100%\" rows=\"1\"></textarea></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_table_title\" width=\"40%\">RELACION HERMANOS:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\" width=\"60%\"><textarea name=\"hermanos\" id=\"hermanos\" class=\"input-text\" cols=\"100%\" rows=\"1\"></textarea></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_table_title\" width=\"40%\">ACTIVIDADES FAMILIARES:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\" width=\"60%\"><textarea name=\"actividades\" id=\"actividades\" class=\"input-text\" cols=\"100%\" rows=\"1\"></textarea></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_table_title\" width=\"40%\">OTROS:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\" width=\"60%\"><textarea name=\"otros_fam\" id=\"otros_fam\" class=\"input-text\" cols=\"100%\" rows=\"1\"></textarea></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"4\" align=\"center\" class=\"modulo_list_oscuro\"><input type=\"button\" name=\"save_rolfam\" id=\"save_rolfam\" class=\"input-submit\" value=\"INSERTAR\" onclick=InsertarRolFamiliar();></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
               $this->salida.= "</div>";
               $this->salida.="</form>";
               
               $this->salida.= "<div id=\"RolFamDat\" style=\"display:none\"></div>";
     
               $this->salida.= "<br>";
          }
          else
          {
               $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_table_title\">ROL FAMILIAR</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">RELACION PADRE:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\" width=\"60%\">".$RolFamiliar['relacion_padre']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">RELACION MADRE:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\" width=\"60%\">".$RolFamiliar['relacion_madre']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">RELACION HERMANOS:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\" width=\"60%\">".$RolFamiliar['relacion_hermanos']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">ACTIVIDADES FAMILIARES:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\" width=\"60%\">".$RolFamiliar['actividades_familiares']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">OTROS:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\" width=\"60%\">".$RolFamiliar['otros_detalles']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
          }
                    
          $javaC = "<script>\n";          
          $javaC.="		function InsertarRolProfesional()\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_InsertRolProfesional(xajax.getFormValues('RolProfesional'));\n";
          $javaC.="		}\n";
          
          $javaC.="		function InsertarRolPareja()\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_InsertRolPareja(xajax.getFormValues('RolPareja'));\n";
          $javaC.="		}\n";
		
          $javaC.="		function InsertarRolSocial()\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_InsertRolSocial(xajax.getFormValues('RolSocial'));\n";
          $javaC.="		}\n";
          
          $javaC.="		function InsertarRolFamiliar()\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_InsertRolFamiliar(xajax.getFormValues('RolFamiliar'));\n";
          $javaC.="		}\n";
          $javaC.= "</script>\n";
          $this->salida.= $javaC;
          
		$this->salida.= ThemeCerrarTablaSubModulo();
		return $this->salida;
     }
     
}
?>