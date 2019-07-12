<?php

/**
* Submodulo de Encuesta Paciente
*
* @author Tizziano Perea O.
* @version 1.0
* @package SIIS
* $Id: hc_EncuestaPaciente_EncuestaInicial_HTML.class.php,v 1.1 2007/11/30 20:44:54 tizziano Exp $
*/

class EncuestaInicial_HTML
{

     /**
     * Constructor
     *
     * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
     * @access public
     */
	function EncuestaInicial_HTML()
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
     function frmForma($EncuestaDatos)
     {
          $this->salida.= ThemeAbrirTablaSubModulo('ENCUESTA INICIAL PARA EL CONSULTANTE');
          $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida.= 		$this->SetStyle("MensajeError");
          $this->salida.= "	</table><br>";
          
          if(!$EncuestaDatos)
          {
               $this->salida.= "	<form name=\"Encuesta\" id=\"Encuesta\" method=\"post\">";          
               $this->salida.= "	<div id=\"EncuIni\">";          
               $this->salida.= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_list_title\">MOTIVO DE CONSULTA:</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"motivo\" id=\"motivo\" class=\"input-text\" cols=\"100%\" rows=\"2\"></textarea></td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_list_title\">OBJETIVOS INICIALES:</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"objetivo\" id=\"objetivo\" class=\"input-text\" cols=\"100%\" rows=\"2\"></textarea></td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_list_title\">COMPROMISOS</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\">";
               $this->salida.= "	<label class=\"label\">Asistir puntualmente a las sesiones:</label>&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"asistencia\" id=\"asistencia\" value=\"1\"><br>";
               $this->salida.= "	<label class=\"label\">Avisar previamente la cancelación de la cita si no puede asistir:</label>&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"avisos\" id=\"avisos\" value=\"1\"><br>";
               $this->salida.= "	<label class=\"label\">Despues de 3 citas consecutivas de no asistencia puedo quedar suspendido del proceso:</label>&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"suspension\" id=\"suspension\" value=\"1\"><br>";
               $this->salida.= "	<label class=\"label\">Me compromento a cumplir con las tareas y ejercicios propuestos durante el proceso:</label>&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"compromiso\" id=\"compromiso\" value=\"1\"><br>";
               $this->salida.= "	</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_list_title\">¿ COMO SE SINTIO DURANTE LA ENTREVISTA ?</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"concepto\" id=\"concepto\" class=\"input-text\" cols=\"100%\" rows=\"2\"></textarea></td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_list_title\">OTROS:</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"otros\" id=\"otros\" class=\"input-text\" cols=\"100%\" rows=\"2\"></textarea></td>";          
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td align=\"center\" class=\"modulo_list_oscuro\"><input type=\"button\" name=\"save_encuesta\" id=\"save_encuesta\" class=\"input-submit\" value=\"INSERTAR\" onclick=InsertarEncuesta();></td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
               $this->salida.= "</div>";
               
               $this->salida.= "<div id=\"EncuIniCon\" style=\"display:none\"></div>";
               
          }
          else
          {
               $this->salida.= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\">MOTIVO DE CONSULTA:</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$EncuestaDatos['motivo_consulta']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\">OBJETIVOS INICIALES:</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$EncuestaDatos['objetivos_iniciales']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               
               if($EncuestaDatos['asistencia_sesiones'] == '1')
               { $asistencia = 'SI'; }else{ $asistencia = 'NO'; }
     
               if($EncuestaDatos['cancelacion_cita'] == '1')
               { $avisos = 'SI'; }else{ $avisos = 'NO'; }
     
               if($EncuestaDatos['suspension_proceso'] == '1')
               { $suspension = 'SI'; }else{ $suspension = 'NO'; }
     
               if($EncuestaDatos['compromiso'] == '1')
               { $compromiso = 'SI'; }else{ $compromiso = 'NO'; }
     
               $this->salida.= "	<td class=\"modulo_table_title\">COMPROMISOS</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\">";
               $this->salida.= "	<label class=\"label\">Asistir puntualmente a las sesiones:</label>&nbsp;&nbsp;&nbsp;<label class=\"label_error\">".$asistencia."</label><br>";
               $this->salida.= "	<label class=\"label\">Avisar previamente la cancelación de la cita si no puede asistir:</label>&nbsp;&nbsp;&nbsp;<label class=\"label_error\">".$avisos."</label><br>";
               $this->salida.= "	<label class=\"label\">Despues de 3 citas consecutivas de no asistencia puedo quedar suspendido del proceso:</label>&nbsp;&nbsp;&nbsp;<label class=\"label_error\">".$suspension."</label><br>";
               $this->salida.= "	<label class=\"label\">Me compromento a cumplir con las tareas y ejercicios propuestos durante el proceso:</label>&nbsp;&nbsp;&nbsp;<label class=\"label_error\">".$compromiso."</label><br>";
               $this->salida.= "	</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\">¿ COMO SE SINTIO DURANTE LA ENTREVISTA ?</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$EncuestaDatos['conformidad_entrevista']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_table_title\">OTROS:</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	<tr>";
               $this->salida.= "	<td class=\"modulo_list_oscuro\" align=\"left\">".$EncuestaDatos['otros_detalles']."</td>";
               $this->salida.= "	</tr>";
               $this->salida.= "	</table><BR>";
          }
          
          $javaC = "<script>\n";          
          
          $javaC.="		function InsertarEncuesta()\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_InsertEncuesta(xajax.getFormValues('Encuesta'));\n";
          $javaC.="		}\n";
         
          $javaC.= "</script>\n";
          $this->salida.= $javaC;
          
		$this->salida.= ThemeCerrarTablaSubModulo();
		return $this->salida;
     }
     
}
?>