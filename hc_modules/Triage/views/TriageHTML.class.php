<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: TriageHTML.class.php,v 1.1 2009/06/09 19:11:18 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase vista: TriageHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class TriageHTML 
  {
    /**
    * Constructot de la clase
    */
    function TriageHTML(){}
    /**
    * Funcion donde se muestra la informacion del triage ingresado por admisiones
    *
    * @param array $action Arreglo para el manejo de los links del submodulo
    * @param array $datos Datos iniciales del triage
    * @param array $niveles Arreglo de datos con la informacion de los niveles del triage
    * @param array $signos Arreglo de datos con la informacion de los signos vitales
    * @param array $signo Arreglo de datos con la informacion de los signos vitales obligatorios
    * @param array $ocular Arreglo de datos con la informacion de la repuesta ocular
    * @param array $verbal Arreglo de datos con la informacion de la repuesta verval
    * @param array $motora Arreglo de datos con la informacion de la repuesta motora
    * @param integer $edad_paciente Variable con la edda del paciente
    * @param integer $max_edad_pediatrica Variable con la maxima edad pediatrica
    * @param array $mensaje Arreglo de datos de mensajes
    *
    * @return string
    */
    function FormaMostrarDatosTriage($action,$datos,$niveles,$signos,$signo,$ocular,$verbal,$motora,$edad_paciente,$max_edad_pediatrica,$mensaje)
    {
      $html  = ThemeAbrirTabla('INFORMACION TRIAGE');
      $html .= "<div class=\"".((!empty($mensaje['error']))? "lable_error":"normal_10AN")."\">\n";
      $html .= "  <center>".((!empty($mensaje['error']))? $mensaje['error']: $mensaje['informacion'] )."</center>\n";
      $html .= "</div>\n";
      $html .= "<form name=\"reclasificacion\" action=\"".$action['aceptar']."\" method=\"post\">\n";
      $html .= "<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td>MOTIVO CONSULTA</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td class=\"label\" align=\"justify\">".$datos['motivo_consulta']."</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td>OBSERVACIONES</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td class=\"label\" align=\"justify\">".$datos['observacion_medico']."</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <table width=\"100%\" class=\"modulo_table_list\" align=\"center\">\n";
      $html .= "        <tr class=\"formulacion_table_list\">\n";
      $html .= "          <td>Fc</td>\n";
      $html .= "          <td>Fr</td>\n";
      $html .= "          <td>Tº</td>\n";
      $html .= "          <td>Peso</td>\n";
      $html .= "          <td>T.A.</td>\n";
      $html .= "          <td>Sat 02</td>\n";
      $html .= "        </tr>\n";
      $html .= "        <tr class=\"modulo_list_claro\">\n";
      $html .= "          <td align=\"center\" class=\"label\">".$signos['signos_vitales_fc']."</td>\n";
      $html .= "          <td align=\"center\" class=\"label\">".$signos['signos_vitales_fr']."</td>\n";
      $html .= "          <td align=\"center\" class=\"label\">".$signos['signos_vitales_temperatura']."</td>\n";
      $html .= "          <td align=\"center\" class=\"label\">".$signos['signos_vitales_peso']."</td>\n";
      $html .= "          <td align=\"center\" class=\"label\">".$signos['signos_vitales_taalta']."/".$signos['signos_vitales_tabaja']."</td>\n";
      $html .= "          <td align=\"center\" class=\"label\">".$signos['sato2']."</td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";

      if($signo['eva']['sw_mostrar']==1)				
      {
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
        $html .= "      <table align=\"center\" width=\"100%\" >\n";
        $html .= "        <tr>\n";
        $html .= "          <td class=\"formulacion_table_list\">ESCALA VISUAL ANALOGA - EVA</td>\n";
        $html .= "          <td class=\"modulo_list_claro\">\n";
        if ($edad_paciente < $max_edad_pediatrica)
        {
          switch($signos['evaluacion_dolor'])
          {
            case 0:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/no_dolor.png\" border=0>\n";
            break;
            case 1:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/leve.png\" border=0>\n";
            break;
            case 2:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/moderado.png\" border=0>\n";
            break;
            case 3:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/severopain.png\" border=0>\n";
            break;
            case 4:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/muyseveropain.png\" border=0>\n";
            break;
          }
        }
        else
        {
          $html .= "        ".$signos['evaluacion_dolor']."\n";
        }
        $html .= "          </td>\n";
        $html .= "        </tr>\n";
        $html .= "      </table>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
			if($signo['glasgow']['sw_mostrar']==1 )
			{
        $glas = $signos['apertura_ocular_id']+$signos['respuesta_motora_id']+$signos['respuesta_verbal_id'];
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
				$html .= "      <table align=\"center\" width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "        <tr>\n";
        $html .= "          <td width=\"67%\" class=\"formulacion_table_list\" colspan=\"2\">ESCALA DE GLASGOW</td>\n";
        $html .= "          <td class=\"modulo_list_claro\" ><b class=\"label\">".$glas."</b></td>\n";
        $html .= "        </tr>\n";
        if($signos['apertura_ocular_id'] || $signos['respuesta_motora_id'] || $signos['respuesta_verbal_id'])
        {
  				$html .= "        <tr class=\"formulacion_table_list\">\n";
          $html .= "          <td>APERTURA OCULAR</td>\n";
          $html .= "          <td>RESPUESTA VERBAL</td>\n";
          $html .= "          <td>RESPUESTA MOTORA</td>\n";
          $html .= "        </tr>\n";
  				$html .= "        <tr class=\"modulo_list_claro\">\n";
  				$html .= "          <td class=\"label\" align=\"center\">\n";
  				$html .= "            ".$ocular[$signos['apertura_ocular_id']]['descripcion']."";
  				$html .= "          </td>\n";				
          $html .= "          <td class=\"label\" align=\"center\">\n";
  				$html .= "            ".$ocular[$signos['respuesta_verbal_id']]['descripcion']."";
  				$html .= "          </td>\n";				
          $html .= "          <td class=\"label\" align=\"center\">\n";
  				$html .= "            ".$ocular[$signos['respuesta_motora_id']]['descripcion']."";
  				$html .= "          </td>\n";
  				$html .= "        </tr>\n";
        }
				$html .= "      </table>\n";
				$html .= "    </td>\n";
				$html .= "  </tr>\n";
      }      
      if($niveles)
      {
        $chk= "";
        $html .= "  <tr>\n";
        $html .= "    <td>\n";      
        $html .= "      <table width=\"100%\"class=\"modulo_table_list\">\n";      
        $html .= "        <tr class=\"formulacion_table_list\">\n";
        $html .= "          <td colspan=\"".sizeof($niveles)."\">CLASIFICACION ASISTENCIAL TRIAGE</td>\n";
        $html .= "        </tr>\n";
        $html .= "        <tr class=\"normal_11N\">\n";
        foreach($niveles as $key=> $triage)
        {
          ($datos['nivel_triage_id'] == $key)? $chk = "checked": $chk= "";

          $html .= "          <td bgcolor=\"".$triage['color_oscuro']."\" title=\"".$triage['descripcion']."\">\n";
          $html .= "       	    <input type=\"radio\" name=\"nivel_triage\" value=\"$key\" $chk> <b style=\"color:".$triage['color_letra']."\">NIVEL $key</b>\n";
          $html .= "       	  </td>\n";
        }
        $html .= "        </tr>\n";
        $html .= "      </table>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "</table>\n";
      $html .= "<table border=\"0\" width=\"40%\" align=\"center\">\n";
      $html .= "  <tr align=\"center\">\n";
      $html .= "	  <td>\n";
      $html .= "      <input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"Guardar\">\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "</form>\n";
      $html .= ThemeCerrarTabla();
			return $html;
    }
    /**
    * Funcion donde se muestra la informacion del triage 
    *
    * @param array $datos Datos iniciales del triage
    * @param array $niveles Arreglo de datos con la informacion de los niveles del triage
    * @param array $signos Arreglo de datos con la informacion de los signos vitales
    * @param array $signo Arreglo de datos con la informacion de los signos vitales obligatorios
    * @param array $ocular Arreglo de datos con la informacion de la repuesta ocular
    * @param array $verbal Arreglo de datos con la informacion de la repuesta verval
    * @param array $motora Arreglo de datos con la informacion de la repuesta motora
    * @param integer $edad_paciente Variable con la edda del paciente
    * @param integer $max_edad_pediatrica Variable con la maxima edad pediatrica
    *
    * @return string
    */
    function FormaConsultaDatosTriage($datos,$niveles,$signos,$signo,$ocular,$verbal,$motora,$edad_paciente,$max_edad_pediatrica)
    {
      $html  = "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td>DATOS TRIAGE</td>\n";
      $html .= "  </tr>\n";      
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td>MOTIVO CONSULTA</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td class=\"label\" align=\"justify\">".$datos['motivo_consulta']."</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td>OBSERVACIONES</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td class=\"label\" align=\"justify\">".$datos['observacion_medico']."</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <table width=\"100%\" class=\"modulo_table_list\" align=\"center\">\n";
      $html .= "        <tr class=\"formulacion_table_list\">\n";
      $html .= "          <td>Fc</td>\n";
      $html .= "          <td>Fr</td>\n";
      $html .= "          <td>Tº</td>\n";
      $html .= "          <td>Peso</td>\n";
      $html .= "          <td>T.A.</td>\n";
      $html .= "          <td>Sat 02</td>\n";
      $html .= "        </tr>\n";
      $html .= "        <tr class=\"modulo_list_claro\">\n";
      $html .= "          <td align=\"center\" class=\"label\">".$signos['signos_vitales_fc']."</td>\n";
      $html .= "          <td align=\"center\" class=\"label\">".$signos['signos_vitales_fr']."</td>\n";
      $html .= "          <td align=\"center\" class=\"label\">".$signos['signos_vitales_temperatura']."</td>\n";
      $html .= "          <td align=\"center\" class=\"label\">".$signos['signos_vitales_peso']."</td>\n";
      $html .= "          <td align=\"center\" class=\"label\">".$signos['signos_vitales_taalta']."/".$signos['signos_vitales_tabaja']."</td>\n";
      $html .= "          <td align=\"center\" class=\"label\">".$signos['sato2']."</td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";

      if($signo['eva']['sw_mostrar']==1)				
      {
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
        $html .= "      <table align=\"center\" width=\"100%\" >\n";
        $html .= "        <tr>\n";
        $html .= "          <td class=\"formulacion_table_list\">ESCALA VISUAL ANALOGA - EVA</td>\n";
        $html .= "          <td class=\"modulo_list_claro\">\n";
        if ($edad_paciente < $max_edad_pediatrica)
        {
          switch($signos['evaluacion_dolor'])
          {
            case 0:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/no_dolor.png\" border=0>\n";
            break;
            case 1:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/leve.png\" border=0>\n";
            break;
            case 2:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/moderado.png\" border=0>\n";
            break;
            case 3:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/severopain.png\" border=0>\n";
            break;
            case 4:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/muyseveropain.png\" border=0>\n";
            break;
          }
        }
        else
        {
          $html .= "        ".$signos['evaluacion_dolor']."\n";
        }
        $html .= "          </td>\n";
        $html .= "        </tr>\n";
        $html .= "      </table>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
			if($signo['glasgow']['sw_mostrar']==1 )
			{
        $glas = $signos['apertura_ocular_id']+$signos['respuesta_motora_id']+$signos['respuesta_verbal_id'];
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
				$html .= "      <table align=\"center\" width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "        <tr>\n";
        $html .= "          <td width=\"67%\" class=\"formulacion_table_list\" colspan=\"2\">ESCALA DE GLASGOW</td>\n";
        $html .= "          <td class=\"modulo_list_claro\" ><b class=\"label\">".$glas."</b></td>\n";
        $html .= "        </tr>\n";
        if($signos['apertura_ocular_id'] || $signos['respuesta_motora_id'] || $signos['respuesta_verbal_id'])
        {
  				$html .= "        <tr class=\"formulacion_table_list\">\n";
          $html .= "          <td>APERTURA OCULAR</td>\n";
          $html .= "          <td>RESPUESTA VERBAL</td>\n";
          $html .= "          <td>RESPUESTA MOTORA</td>\n";
          $html .= "        </tr>\n";
  				$html .= "        <tr class=\"modulo_list_claro\">\n";
  				$html .= "          <td class=\"label\" align=\"center\">\n";
  				$html .= "            ".$ocular[$signos['apertura_ocular_id']]['descripcion']."";
  				$html .= "          </td>\n";				
          $html .= "          <td class=\"label\" align=\"center\">\n";
  				$html .= "            ".$ocular[$signos['respuesta_verbal_id']]['descripcion']."";
  				$html .= "          </td>\n";				
          $html .= "          <td class=\"label\" align=\"center\">\n";
  				$html .= "            ".$ocular[$signos['respuesta_motora_id']]['descripcion']."";
  				$html .= "          </td>\n";
  				$html .= "        </tr>\n";
        }
				$html .= "      </table>\n";
				$html .= "    </td>\n";
				$html .= "  </tr>\n";
      }      
      if($niveles)
      {
        $html .= "  <tr>\n";
        $html .= "    <td>\n";      
        $html .= "      <table width=\"100%\"class=\"modulo_table_list\">\n";      
        $html .= "        <tr class=\"formulacion_table_list\">\n";
        $html .= "          <td width=\"50%\">CLASIFICACION ASISTENCIAL TRIAGE</td>\n";
        $html .= "          <td bgcolor=\"".$niveles[$datos['nivel_triage_id']]['color_oscuro']."\" title=\"".$niveles[$datos['nivel_triage_id']]['descripcion']."\">\n";
        $html .= "       	    <b style=\"color:".$niveles[$datos['nivel_triage_id']]['color_letra']."\">NIVEL ".$datos['nivel_triage_id']."</b>\n";
        $html .= "       	  </td>\n";
        $html .= "        </tr>\n";
        $html .= "      </table>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "</table>\n";
			return $html;
    }
    /**
    * Funcion donde se muestra la informacion del triage 
    *
    * @param array $action Arreglo para el manejo de los links del submodulo
    * @param array $datos Datos iniciales del triage
    * @param array $niveles Arreglo de datos con la informacion de los niveles del triage
    * @param array $signos Arreglo de datos con la informacion de los signos vitales
    * @param array $signo Arreglo de datos con la informacion de los signos vitales obligatorios
    * @param array $ocular Arreglo de datos con la informacion de la repuesta ocular
    * @param array $verbal Arreglo de datos con la informacion de la repuesta verval
    * @param array $motora Arreglo de datos con la informacion de la repuesta motora
    * @param integer $edad_paciente Variable con la edda del paciente
    * @param integer $max_edad_pediatrica Variable con la maxima edad pediatrica
    *
    * @return string
    */
    function FormaReporteDatosTriage($action,$datos,$niveles,$signos,$signo,$ocular,$verbal,$motora,$edad_paciente,$max_edad_pediatrica)
    {
      $html .= "<table width=\"100%\" align=\"center\" border=\"1\" bordercolor=\"#000000\" cellpadding=\"0\" cellspacing=\"0\" rules=\"all\">\n";
      $html .= "  <tr class=\"label\">\n";
      $html .= "    <td align=\"center\">DATOS DEL TRIAGE</td>\n";
      $html .= "  </tr>\n";      
      $html .= "  <tr class=\"label\">\n";
      $html .= "    <td align=\"center\">MOTIVO CONSULTA</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td class=\"label\" align=\"justify\">".$datos['motivo_consulta']."</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"label\">\n";
      $html .= "    <td align=\"center\">OBSERVACIONES</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td class=\"label\" align=\"justify\">".$datos['observacion_medico']."</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <table width=\"100%\" border=\"1\" bordercolor=\"#000000\" cellpadding=\"0\" cellspacing=\"0\" rules=\"all\" align=\"center\">\n";
      $html .= "        <tr class=\"label\" align=\"center\">\n";
      $html .= "          <td>Fc</td>\n";
      $html .= "          <td>Fr</td>\n";
      $html .= "          <td>Tº</td>\n";
      $html .= "          <td>Peso</td>\n";
      $html .= "          <td>T.A.</td>\n";
      $html .= "          <td>Sat 02</td>\n";
      $html .= "        </tr>\n";
      $html .= "        <tr class=\"label\" align=\"center\">\n";
      $html .= "          <td >".$signos['signos_vitales_fc']."</td>\n";
      $html .= "          <td >".$signos['signos_vitales_fr']."</td>\n";
      $html .= "          <td >".$signos['signos_vitales_temperatura']."</td>\n";
      $html .= "          <td >".$signos['signos_vitales_peso']."</td>\n";
      $html .= "          <td >".$signos['signos_vitales_taalta']."/".$signos['signos_vitales_tabaja']."</td>\n";
      $html .= "          <td >".$signos['sato2']."</td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";

      if($signo['eva']['sw_mostrar']==1)				
      {
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
        $html .= "      <table align=\"center\" width=\"100%\" >\n";
        $html .= "        <tr>\n";
        $html .= "          <td class=\"label\" align=\"center\">ESCALA VISUAL ANALOGA - EVA</td>\n";
        $html .= "          <td class=\"normal_10\" align=\"center\">\n";
        if ($edad_paciente < $max_edad_pediatrica)
        {
          switch($signos['evaluacion_dolor'])
          {
            case 0:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/no_dolor.png\" border=0>\n";
            break;
            case 1:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/leve.png\" border=0>\n";
            break;
            case 2:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/moderado.png\" border=0>\n";
            break;
            case 3:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/severopain.png\" border=0>\n";
            break;
            case 4:
              $html .= "          <img src=\"".GetThemePath()."/images/signovital/muyseveropain.png\" border=0>\n";
            break;
          }
        }
        else
        {
          $html .= "        ".$signos['evaluacion_dolor']."\n";
        }
        $html .= "          </td>\n";
        $html .= "        </tr>\n";
        $html .= "      </table>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
			if($signo['glasgow']['sw_mostrar']==1 )
			{
        $glas = $signos['apertura_ocular_id']+$signos['respuesta_motora_id']+$signos['respuesta_verbal_id'];
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
				$html .= "      <table align=\"center\" width=\"100%\" border=\"1\" bordercolor=\"#000000\" cellpadding=\"0\" cellspacing=\"0\" rules=\"all\">\n";
				$html .= "        <tr>\n";
        $html .= "          <td width=\"67%\" class=\"label\" colspan=\"2\" align=\"center\">ESCALA DE GLASGOW</td>\n";
        $html .= "          <td ><b class=\"label\">".$glas."</b></td>\n";
        $html .= "        </tr>\n";
        if($signos['apertura_ocular_id'] || $signos['respuesta_motora_id'] || $signos['respuesta_verbal_id'])
        {
  				$html .= "        <tr class=\"label\" align=\"center\">\n";
          $html .= "          <td>APERTURA OCULAR</td>\n";
          $html .= "          <td>RESPUESTA VERBAL</td>\n";
          $html .= "          <td>RESPUESTA MOTORA</td>\n";
          $html .= "        </tr>\n";
  				$html .= "        <tr class=\"modulo_list_claro\">\n";
  				$html .= "          <td class=\"label\" align=\"center\">\n";
  				$html .= "            ".$ocular[$signos['apertura_ocular_id']]['descripcion']."";
  				$html .= "          </td>\n";				
          $html .= "          <td class=\"label\" align=\"center\">\n";
  				$html .= "            ".$ocular[$signos['respuesta_verbal_id']]['descripcion']."";
  				$html .= "          </td>\n";				
          $html .= "          <td class=\"label\" align=\"center\">\n";
  				$html .= "            ".$ocular[$signos['respuesta_motora_id']]['descripcion']."";
  				$html .= "          </td>\n";
  				$html .= "        </tr>\n";
        }
				$html .= "      </table>\n";
				$html .= "    </td>\n";
				$html .= "  </tr>\n";
      }      
      if($niveles)
      {
        $html .= "  <tr>\n";
        $html .= "    <td>\n";      
        $html .= "      <table width=\"100%\"border=\"1\" bordercolor=\"#000000\" cellpadding=\"0\" cellspacing=\"0\" rules=\"all\">\n";      
        $html .= "        <tr class=\"label\">\n";
        $html .= "          <td width=\"50%\">CLASIFICACION ASISTENCIAL TRIAGE</td>\n";
        $html .= "          <td bgcolor=\"".$niveles[$datos['nivel_triage_id']]['color_oscuro']."\" title=\"".$niveles[$datos['nivel_triage_id']]['descripcion']."\">\n";
        $html .= "       	    <b style=\"color:".$niveles[$datos['nivel_triage_id']]['color_letra']."\">NIVEL ".$datos['nivel_triage_id']."</b>\n";
        $html .= "       	  </td>\n";
        $html .= "        </tr>\n";
        $html .= "      </table>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "</table>\n";
			return $html;
    }
    /**
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		*
		* @param array $action vector que continen los link de la aplicacion
    * @param string $mensaje Cadena con el texto del mensaje a mostrar 
    *         en pantalla
    *
		* @return string
		*/
		function FormaMensajeModulo($mensaje)
		{
			$html  = ThemeAbrirTabla('MENSAJE');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$mensaje."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
  }
?>