<?php

/**
* Submodulo de Areas de Conducta (Examen Fisico)
*
* @author Tizziano Perea O.
* @version 1.0
* @package SIIS
* $Id: hc_Conducta_ExamenFisico_Examen_HTML.class.php,v 1.1 2007/11/30 20:41:12 tizziano Exp $
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
     * @param array $ExFisico Datos de conducta fisica insertados.
     * @param array $ExMental Datos de conducta mental insertados.
     *
     * @return string
     * @access public
     */
     function frmForma($ExFisico, $ExMental)
     {
          $this->salida.= ThemeAbrirTablaSubModulo('INFORMACION AREAS DE CONDUCTA - EXAMEN FISICO Y MENTAL');
          $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida.= 		$this->SetStyle("MensajeError");
          $this->salida.= "	</table><br>";
          
          if(!$ExFisico)
          {
               $this->salida.= "	<form name=\"ExFisico\" id=\"ExFisico\" method=\"post\">";          
               $this->salida.= "	<div id=\"ExF\">";          
               $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"6\" class=\"modulo_table_list_title\">AREA DE CONDUCTA FISICA</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_table_title\">ENFERMEDADES CRÓNICAS O SÍNTOMA GENERAL:</td>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"Enfermedades\" id=\"Enfermedades\" class=\"input-text\" cols=\"70%\" rows=\"1\"></textarea></td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_table_title\">MEDICAMENTOS QUE CONSUME ACTUALMENTE:</td>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"Medicamentos\" id=\"Medicamentos\" class=\"input-text\" cols=\"70%\" rows=\"1\"></textarea></td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_table_title\">ANTECEDENTES DE ENFERMEDADES FAMILIARES:</td>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"Antecedentes\" id=\"Antecedentes\" class=\"input-text\" cols=\"70%\" rows=\"1\"></textarea></td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_table_title\">ALIMENTACIÓN:</td>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"Alimentacion\" id=\"Alimentacion\" class=\"input-text\" cols=\"70%\" rows=\"1\"></textarea></td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\">SUEÑO:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"Sueno\" id=\"Sueno\" class=\"input-text\" size=\"25\" maxlength=\"20\"></td>";
               $this->salida.= "	<td class=\"modulo_table_title\">CIGARRILLO:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"Cigarrillo\" id=\"Cigarrillo\" class=\"input-text\" size=\"27\" maxlength=\"20\"></td>";
               $this->salida.= "	<td class=\"modulo_table_title\">ALCOHOL:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"Alcohol\" id=\"Alcohol\" class=\"input-text\" size=\"27\" maxlength=\"20\"></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\">ALUCIONÓGENOS:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\"><input type=\"text\" name=\"Drogas\" id=\"Drogas\" class=\"input-text\" size=\"42\" maxlength=\"20\"></td>";
               $this->salida.= "	<td class=\"modulo_table_title\">DEPORTE:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\"><input type=\"text\" name=\"Deporte\" id=\"Deporte\" class=\"input-text\" size=\"42\" maxlength=\"20\"></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"6\" align=\"center\" class=\"modulo_list_oscuro\"><input type=\"button\" name=\"save_fisico\" id=\"save_fisico\" class=\"input-submit\" value=\"INSERTAR\" onclick=InsertarExamenF();></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
               $this->salida.= "</div>";
               $this->salida.="</form>";
               
               $this->salida.= "<div id=\"ExfCon\" style=\"display:none\"></div>";
     
               $this->salida.= "<br>";
          }
          else
          {
               $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"6\" class=\"modulo_table_title\">AREA DE CONDUCTA FISICA</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">ENFERMEDADES CRONICAS O SINTOMA GENERAL:</td>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$ExFisico['enfermedades']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">MEDICAMENTOS QUE CONSUME ACTUALMENTE:</td>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$ExFisico['medicamentos']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">ANTECEDENTES DE ENFERMEDADES FAMILIARES:</td>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$ExFisico['enfermedades_familiares']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">ALIMENTACION:</td>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$ExFisico['alimentacion']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"15%\">SUENO:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\" width=\"20%\">".$ExFisico['sueño']."</td>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"15%\">CIGARRILLO:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\" width=\"20%\">".$ExFisico['cigarrillo']."</td>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"15%\">ALCOHOL:</td>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\" width=\"20%\">".$ExFisico['alcohol']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\">ALUCIONOGENOS:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\">".$ExFisico['alucionogenos']."</td>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\">DEPORTE:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\">".$ExFisico['deporte']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
          }          
          
          if(!$ExMental)
          {
               $this->salida.="<form name=\"ExMental\" id=\"ExMental\" method=\"post\">";
               $this->salida.= "<div id=\"ExM\">";
               $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"6\" class=\"modulo_table_list_title\">AREA DE CONDUCTA MENTAL</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\" width=\"25%\">RACIOCINIO:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" width=\"25%\"><input type=\"text\" name=\"Raciocinio\" id=\"Raciocinio\" class=\"input-text\" size=\"40\" maxlength=\"20\"></td>";
               $this->salida.= "	<td class=\"modulo_table_title\" width=\"25%\">CONCENTRACIÓN:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" width=\"25%\"><input type=\"text\" name=\"Atencion\" id=\"Atencion\" class=\"input-text\" size=\"40\" maxlength=\"20\"></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_table_title\" width=\"50%\">CAPACIDAD PARA TOMAR DECISIONES:</td>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"desiciones\" id=\"desiciones\" class=\"input-text\" cols=\"70%\" rows=\"1\"></textarea></td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_table_title\" width=\"50%\">ACTIVIDADES EXTRACURRICULARES:</td>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"Actividades\" id=\"Actividades\" class=\"input-text\" cols=\"70%\" rows=\"1\"></textarea></td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_table_title\" width=\"50%\">SENTIMIENTO O ACTITUD GENERAL HACIA LA VIDA:</td>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"actitud\" id=\"actitud\" class=\"input-text\" cols=\"70%\" rows=\"1\"></textarea></td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_table_title\" width=\"50%\">PERCEPCIÓN DE SÍ MISMO:</td>";
               $this->salida.= "	<td colspan=\"3\" class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"Percepcion\" id=\"Percepcion\" class=\"input-text\" cols=\"70%\" rows=\"1\"></textarea></td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"6\" align=\"center\" class=\"modulo_list_oscuro\"><input type=\"button\" name=\"save_mental\" id=\"save_mental\" class=\"input-submit\" value=\"INSERTAR\" onclick=InsertarExamenM();></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table>";
               $this->salida.= "</div>";
               $this->salida.= "</form>";
     
               $this->salida.= "<div id=\"ExmCon\" style=\"display:none\"></div>";
          }
          else
          {
               $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"6\" class=\"modulo_table_title\">AREA DE CONDUCTA MENTAL</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"20%\">RACIOCINIO:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" width=\"30%\" align=\"left\">".$ExMental['raciocinio']."</td>";
               $this->salida.= "	<td class=\"hc_table_submodulo_list_title\" width=\"20%\">CONCENTRACION:</td>";
               $this->salida.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" width=\"30%\" align=\"left\">".$ExMental['concentracion']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">CAPACIDAD PARA TOMAR DECISIONES:</td>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$ExMental['decisiones']."</td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">ACTIVIDADES EXTRACURRICULARES:</td>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$ExMental['actividades_extracurriculares']."</td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">SENTIMIENTO O ACTITUD GENERAL HACIA LA VIDA:</td>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$ExMental['actitud_vida']."</td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" width=\"40%\">PERCEPCION DE SI MISMO:</td>";
               $this->salida.= "	<td colspan=\"4\" class=\"modulo_list_oscuro\" align=\"left\">".$ExMental['percepcion_si_mismo']."</td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	</table>";
          }
          
          $javaC = "<script>\n";          
          $javaC.="		function InsertarExamenF()\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_InsertFisico(xajax.getFormValues('ExFisico'));\n";
          $javaC.="		}\n";
          
          $javaC.="		function InsertarExamenM()\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_InsertMental(xajax.getFormValues('ExMental'));\n";
          $javaC.="		}\n";
         
          $javaC.= "</script>\n";
          $this->salida.= $javaC;
          
		$this->salida.= ThemeCerrarTablaSubModulo();
		return $this->salida;
     }
     
}
?>