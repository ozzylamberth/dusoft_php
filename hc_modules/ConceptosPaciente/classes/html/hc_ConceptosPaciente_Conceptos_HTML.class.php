<?php

/**
* Submodulo de Conceptos Paciente
*
* @author Tizziano Perea O.
* @version 1.0
* @package SIIS
* $Id: hc_ConceptosPaciente_Conceptos_HTML.class.php,v 1.1 2007/11/30 20:37:20 tizziano Exp $
*/

class Conceptos_HTML
{

     /**
     * Constructor
     *
     * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
     * @access public
     */
	function Conceptos_HTML()
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
     * @param array $ConcepPer Datos de conceptos personales insertados.
     * @param array $ConcepOtr Datos de conceptos de otras personas insertados.
     *
     * @return string
     * @access public
     */
     function frmForma($ConcepPer, $ConcepOtr)
     {
          $this->salida.= ThemeAbrirTablaSubModulo('CONCEPTOS DE PACIENTES');
          $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida.= 		$this->SetStyle("MensajeError");
          $this->salida.= "	</table><br>";
          
          if(!$ConcepPer)
          {
               $this->salida.= "	<form name=\"Conceptos\" id=\"Conceptos\" method=\"post\">";          
               $this->salida.= "	<div id=\"Concep\">";          
               $this->salida.= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_list_title\">CONCEPTO DE SI MISMO - (Frases claves)</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"simismo\" id=\"simismo\" class=\"input-text\" cols=\"100%\" rows=\"2\"></textarea></td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td align=\"center\" class=\"modulo_list_oscuro\"><input type=\"button\" name=\"save_concepto\" id=\"save_concepto\" class=\"input-submit\" value=\"INSERTAR\" onclick=InsertarConceptosPer();></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
               $this->salida.= "</div>";
               $this->salida.="</form>";
               
               $this->salida.= "<div id=\"ConcepCon\" style=\"display:none\"></div>";
     
               $this->salida.= "<br>";
          }
          else
          {
               $this->salida.= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\">CONCEPTO DE SI MISMO - (Frases claves)</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$ConcepPer['descripcion']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
          }          
          
          if(!$ConcepOtr)
          {
               $this->salida.= "	<form name=\"ConceptosDemas\" id=\"ConceptosDemas\" method=\"post\">";          
               $this->salida.= "	<div id=\"Concep_Demas\">";          
               $this->salida.= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_list_title\">CONCEPTO DE LOS DEMAS - (Figuras cercanas)</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"con_demas\" id=\"con_demas\" class=\"input-text\" cols=\"100%\" rows=\"2\"></textarea></td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td align=\"center\" class=\"modulo_list_oscuro\"><input type=\"button\" name=\"save_demas\" id=\"save_demas\" class=\"input-submit\" value=\"INSERTAR\" onclick=InsertarConceptosOtros();></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
               $this->salida.= "</div>";
               $this->salida.="</form>";
               
               $this->salida.= "<div id=\"Concep_DemasCon\" style=\"display:none\"></div>";
     
               $this->salida.= "<br>";
          }
          else
          {
               $this->salida.= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\">CONCEPTO DE LOS DEMAS - (Figuras cercanas)</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$ConcepOtr['descripcion']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
          }          
          
          $javaC = "<script>\n";          
          $javaC.="		function InsertarConceptosPer()\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_InsertConceptosPer(xajax.getFormValues('Conceptos'));\n";
          $javaC.="		}\n";
          
          $javaC.="		function InsertarConceptosOtros()\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_InsertConceptosOtros(xajax.getFormValues('ConceptosDemas'));\n";
          $javaC.="		}\n";
         
          $javaC.= "</script>\n";
          $this->salida.= $javaC;
          
		$this->salida.= ThemeCerrarTablaSubModulo();
		return $this->salida;
     }
     
}
?>