<?php
/** 
    * $Id: signos_HTML.class.php,v 1.2 2007/10/12 14:40:56 jgomez Exp $
    * 
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @package IPSOFT-SIIS-FI
    * 
    * $Revision: 1.2 $ 
    * 
    * @autor J gomez
    */
class VistaPTA_HTML
{  
     function VistaPTA_HTML($objeto=null)
     {
          $this->obj=$objeto;
          return true;
     }

    /**
    * Funcion que contiene la vista del submodulo
    * @param  array $datos del paciente
    * @param  array $deptos lista de departamentos del pais
    * @param  array $riesgos lista de espacios
    * @return true
    **/ 
     
    function Forma($datos=null,$deptos,$riesgos)
	{ 
        $path = SessionGetVar("rutaImagenes");
        $this->salida .= ThemeAbrirTabla("HISTORIAL OCUPACIONAL");
        $this->salida .= "            <div id=\"mensaje\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">";
        $this->salida .= "            </div>\n";
        $this->salida .= "            <form name=\"enfermedades\" id=\"enfermedades\" action=\"#\" method=\"post\">\n";
        $this->salida .= "                 <table width=\"100%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td  width=\"52%\" colspan='2' class=\"modulo_table_list_title\" align=\"center\">\n";
        $this->salida .= "                       TIENE O HA TENIDO ENFERMEDADES CAUSADAS POR SU PROFESION ? &nbsp;&nbsp;&nbsp;&nbsp;";
        $this->salida .= "                       SI  <input type=\"radio\" name=\"ep\" id=\"ep\" onclick=\"ValidarE(this.value);\" value=\"1\">";
        $this->salida .= "                       NO  <input type=\"radio\" name=\"ep\" id=\"ep\" onclick=\"ValidarE(this.value);\" value=\"0\" checked>";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td width=\"48%\" class=\"modulo_list_claro\" align=\"center\">\n";
        $this->salida .= "                       <a title='INSERTAR OBSERVACIONES' class=\"Normal_10AN\">\n";
        $this->salida .= "                         <sub><textarea ROWS=2 style=\"width:100%\" class='textarea' name='obs_ep' id='obs_ep' disabled>".$Lista_ep."</textarea></sub>\n";
        $this->salida .= "                       </a>\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td  width=\"52%\" colspan='2' class=\"modulo_table_list_title\" align=\"center\">\n";
        $this->salida .= "                       TIENE O HA TENIDO ACCIDENTES DE TRABAJO ? &nbsp;&nbsp;";
        $this->salida .= "                       SI  <input type=\"radio\" name=\"accp\" id=\"accp\" onclick=\"ValidarAcc(this.value);\" value=\"1\">";
        $this->salida .= "                       NO  <input type=\"radio\" name=\"accp\" id=\"accp\" onclick=\"ValidarAcc(this.value);\" value=\"0\" checked>";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td width=\"48%\" class=\"modulo_list_claro\" align=\"center\">\n";
        $this->salida .= "                       <a title='INSERTAR OBSERVACIONES' class=\"Normal_10AN\">\n";
        $this->salida .= "                         <sub><textarea ROWS=2 style=\"width:100%\" class='textarea' name='obs_accp' id='obs_accp' disabled>".$Lista_ep."</textarea></sub>\n";
        $this->salida .= "                       </a>\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_list_claro\" colspan=\"4\" align=\"center\" class=\"normal_10AN\">\n";
        $this->salida .= "                       <input type=\"button\" class=\"input-submit\" name=\"be\" id=\"be\" value=\"REGISTRAR\" onclick=\"GuardarInfo(document.getElementById('obs_ep').value,document.getElementById('obs_accp').value,'".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."');\" disabled>\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                 </table>\n";
        $this->salida .= "               </form>\n";
        $this->salida .= "              <div id=\"lista_enfermedades\" style=\"text-transform: uppercase; text-align:center;\">";
        $this->salida .= "              </div>\n";
        $this->salida .= "              <div id=\"lista_enfermedades1\">";
        $this->salida .= "              </div>\n";
        $this->salida .= "              <br>\n";
        $this->salida .= "            <form name=\"trabajos\" id=\"trabajos\" action=\"#\" method=\"post\">\n";
        $this->salida .= "              <table width=\"100%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td colspan='4' class=\"modulo_table_list_title\" align=\"center\">\n";
        $this->salida .= "                       INFORMACION TRABAJOS ANTERIORES DEL PACIENTE";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_table_list_title\" align=\"left\">\n";
        $this->salida .= "                       EMPLEADOR\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td COLSPAN='3' class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "                       <input type=\"text\" class=\"input-text\" name=\"empleador\" id=\"empleador\"  size=\"60\" onkeypress=\"\" >\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_table_list_title\" align=\"left\">\n";
        $this->salida .= "                       LUGAR\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td COLSPAN='2' class=\"modulo_list_claro\" align=\"left\">\n";
        if(!empty($deptos))
        {
            $this->salida .= "                    DEPARTAMENTO   <select class=\"select\" name=\"departamentos\" id=\"departamentos\" onchange=\"llamarCiudades(this.value);\">";
            $this->salida .= "                         <option value=\"-1\">SELECCIONAR</option> \n";
            for($i=0;$i<count($deptos);$i++)
            {
                $this->salida .= "                           <option value=\"".$deptos[$i]['tipo_dpto_id']."\">".$deptos[$i]['departamento']."</option> \n";
            }
            $this->salida .= "                         </select>\n";
        }
        $this->salida .= "                      </td>\n";
        $this->salida .= "                     <td id='ciudadesx' class=\"modulo_list_claro\" align=\"left\">\n";
        if(!empty($deptos))
        {
            $this->salida .= "            CIUDAD    <select class=\"select\" name=\"ciudades\" id=\"ciudades\" onchange=\"\">";
            $this->salida .= "                        <option style='background-color:#ffffff;' value=\"-1\">SELECCIONAR</option> \n";
            $this->salida .= "                      </select>\n";
        }
        $this->salida .= "                      </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_table_list_title\" align=\"left\">\n";
        $this->salida .= "                       CARGO\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td COLSPAN='3' class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "                       <input type=\"text\" class=\"input-text\" name=\"cargo\" id=\"cargo\"  size=\"60\" onkeypress=\"\" >\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td width=\"20%\" class=\"modulo_table_list_title\" align=\"left\">\n";
        $this->salida .= "                       FECHA INGRESO\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                       <td  width=\"30%\" align=\"left\" class=\"modulo_list_claro\"> \n";
        $this->salida .= "                         <input type=\"text\" class=\"input-text\" name=\"fecha1\" id=\"fecha1\"  size=\"12\" onkeypress=\"return acceptNum(event)\">\n";
        $this->salida .="<sub>".ReturnOpenCalendario("trabajos","fecha1","-")."</sub>";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                     <td  width=\"20%\" class=\"modulo_table_list_title\" align=\"left\">\n";
        $this->salida .= "                       FECHA RETIRO\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                       <td   width=\"30%\" align=\"left\" class=\"modulo_list_claro\"> \n";
        $this->salida .= "                         <input type=\"text\" class=\"input-text\" name=\"fecha2\" id=\"fecha2\"  size=\"12\" onkeypress=\"return acceptNum(event)\">\n";
        $this->salida .="<sub>".ReturnOpenCalendario("trabajos","fecha2","-")."</sub>";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   </table>\n";
        if(!empty($riesgos))
        {
            $this->salida .= "                 <table width=\"100%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
            $this->salida .= "                     <td width=\"21%\" rowspan='".($riesgos['CUANTOS']+1)."' class=\"modulo_table_list_title\" align=\"left\">\n";
            $this->salida .= "                       RIESGOS ASOCIADOS\n";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width=\"35%\" class=\"modulo_table_list_title\" align=\"left\">\n";
            $this->salida .= "                       TIPO DE RIESGO\n";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width=\"39%\" class=\"modulo_table_list_title\" align=\"left\">\n";
            $this->salida .= "                       AGENTE DE RIESGO\n";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width=\"5%\" class=\"modulo_table_list_title\" align=\"left\">\n";
            $this->salida .= "                       SELECCIONAR\n";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                   </tr>\n";
            $ban=0;
            foreach($riesgos as $key=>$valor1)
            {
                if($key!='CUANTOS')
                {
                        if($ban==1)
                        {
                            $this->salida .= "                   <tr>\n";
                        }
                            $this->salida .= "                     <td  class=\"modulo_list_claro\" align=\"LEFT\" rowspan='".COUNT($valor1)."'>\n";
                            $this->salida .= "                      ".$key."";
                            $this->salida .= "                     </td>\n";
                            $ban1=2;
                            foreach($valor1 as $key1=>$valor)
                            {
                                if($ban1==3)
                                {
                                    $this->salida .= "                   <tr >\n";
                                }
                                $this->salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
                                $this->salida .= "                       ".$valor['agente_de_riesgo_nom']." ";
                                $this->salida .= "                     </td>\n";
                                $this->salida .= "                     <td  class=\"modulo_list_claro\" align=\"center\">\n";
                                $this->salida .= "                         <input type=\"checkbox\" name='NORKER[".$valor['tipo_riesgo_id']."@".$valor['agente_riesgo_id']."]' id='".$valor['tipo_riesgo_id']."@".$valor['agente_riesgo_id']."' onclick='' value=\"1\">";
                                $this->salida .= "                     </td>\n";
                                $this->salida .= "                   </tr>\n";
                                $ban1=3;
                            }   
                            $ban=1;
                }
            }
            $this->salida .= "                 </table>\n";
        }
        
        $this->salida .= "                 <table width=\"100%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td width=\"20%\" ROWSPAN='1' class=\"modulo_table_list_title\" align=\"left\">\n";
        $this->salida .= "                       TIEMPO DEDICACION\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td width=\"30%\" class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "                     DIAS A LA SEMAMA   <select class=\"select\" name=\"dias_sem\" id=\"dias_sem\" onchange=\"\">";
        $this->salida .= "                         <option value=\"\">--</option> \n";
        $this->salida .= "                         <option value=\"1\">1</option> \n";
        $this->salida .= "                         <option value=\"2\">2</option> \n";
        $this->salida .= "                         <option value=\"3\">3</option> \n";
        $this->salida .= "                         <option value=\"4\">4</option> \n";
        $this->salida .= "                         <option value=\"5\">5</option> \n";
        $this->salida .= "                         <option value=\"6\">6</option> \n";
        $this->salida .= "                         <option value=\"7\">7</option> \n";
        $this->salida .= "                         </select>\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td width=\"25%\" class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "                      HORAS POR DIA  <select class=\"select\" name=\"horas_dia\" id=\"horas_dia\" onchange=\"\">";
        $this->salida .= "                         <option value=\"\">--</option> \n";
        for($i=0;$i<25;$i++)
        {
            $this->salida .= "                           <option value=\"".$i."\">".$i."</option> \n";
        }
        $this->salida .= "                         </select>\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td width=\"25%\" class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "                      INTENSIDAD  ";
        $this->salida .= "                       <select class=\"select\" name=\"intensidad\" id=\"intensidad\" onchange=\"\">";
        $this->salida .= "                         <option value=\"A\">ALTA</option> \n";
        $this->salida .= "                         <option value=\"M\">MEDIA</option> \n";
        $this->salida .= "                         <option value=\"B\">BAJA</option> \n";
        $this->salida .= "                       </select>\n";
        $this->salida .= "                         </select>\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td colspan='2' class=\"modulo_table_list_title\" align=\"left\">\n";
        $this->salida .= "                       LA EMPRESA APORTO ELEMENTOS PROTECTORES PERSONALES ?\n";
        $this->salida .= "                       <select class=\"select\" name=\"emp_prot\" id=\"emp_prot\" onchange=\"\">";
        $this->salida .= "                         <option value=\"S\">SI</option> \n";
        $this->salida .= "                         <option value=\"N\">NO</option> \n";
        $this->salida .= "                       </select>\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td colspan='2' class=\"modulo_table_list_title\" align=\"left\">\n";
        $this->salida .= "                       EL PACIENTE UTILIZO ELEMENTOS PROTECTORES PERSONALES ?\n";
        $this->salida .= "                       <select class=\"select\" name=\"usu_prot\" id=\"usu_prot\" onchange=\"\">";
        $this->salida .= "                         <option value=\"S\">SI</option> \n";
        $this->salida .= "                         <option value=\"N\">NO</option> \n";
        $this->salida .= "                       </select>\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_list_claro\" colspan=\"4\" align=\"center\" class=\"normal_10AN\">\n";
        $this->salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"REGISTRAR\" onclick=\"xajax_GuardarRiesgos_Paciente((xajax.getFormValues('trabajos')),'".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."');\">\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                 </tr>\n";
        $this->salida .= "                 </table>\n";
        $this->salida .= "               </form>\n";
        $this->salida .= "                 <br>\n";
        $this->salida .= "           <div class='label_error' style=\"text-transform: uppercase; text-align:center;\" id=\"cuadro_trabajos\">";
        $this->salida .= "           </div>\n";
        $this->salida .= "           <div id=\"cuadro_trabajos1\">";
        $this->salida .= "           </div>\n";
        $this->salida .= "                 <br>\n";
        
        $this->salida .= "            <form name=\"eps_anterior\" id=\"eps_anterior\" action=\"#\" method=\"post\">\n";
        $this->salida .= "                 <table width=\"100%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td colspan='4' class=\"modulo_table_list_title\" align=\"center\">\n";
        $this->salida .= "                       INFORMACION AFILIACIONES ANTERIORES";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_table_list_title\" align=\"left\">\n";
        $this->salida .= "                       EPS \n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "                       <input type=\"text\" class=\"input-text\" name=\"eps\" id=\"eps\"  size=\"40\" onkeypress=\"\" >\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td class=\"modulo_table_list_title\" align=\"left\">\n";
        $this->salida .= "                       ARP\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "                       <input type=\"text\" class=\"input-text\" name=\"arp\" id=\"arp\"  size=\"40\" onkeypress=\"\" >\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_table_list_title\" align=\"left\">\n";
        $this->salida .= "                       PENSIONES\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td colspan='3' class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "                       <input type=\"text\" class=\"input-text\" name=\"pension\" id=\"pension\"  size=\"60\" onkeypress=\"\" >\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_table_list_title\" align=\"left\">\n";
        $this->salida .= "                       FECHA INGRESO\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                       <td  width=\"30%\" align=\"left\" class=\"modulo_list_claro\"> \n";
        $this->salida .= "                         <input type=\"text\" class=\"input-text\" name=\"fecha3\" id=\"fecha3\"  size=\"12\" onkeypress=\"return acceptNum(event)\">\n";
        $this->salida .="<sub>".ReturnOpenCalendario("eps_anterior","fecha3","-")."</sub>";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                     <td class=\"modulo_table_list_title\" align=\"left\">\n";
        $this->salida .= "                       FECHA RETIRO\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                       <td  width=\"30%\" align=\"left\" class=\"modulo_list_claro\"> \n";
        $this->salida .= "                         <input type=\"text\" class=\"input-text\" name=\"fecha4\" id=\"fecha4\"  size=\"12\" onkeypress=\"return acceptNum(event)\">\n";
        $this->salida .="<sub>".ReturnOpenCalendario("eps_anterior","fecha4","-")."</sub>";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_list_claro\" colspan=\"4\" align=\"center\" class=\"normal_10AN\">\n";
        $this->salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"REGISTRAR\" onclick=\"xajax_GuardarEPS_Anteror((xajax.getFormValues('eps_anterior')),'".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."');\">\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                 </tr>\n";
        $this->salida .= "                 </table>\n";
        $this->salida .= "         </form>\n";
        $this->salida .= "             <br>\n";
        $this->salida .= "           <div class='label_error' style=\"text-transform: uppercase; text-align:center;\" id=\"eps_anteriores\">";
        $this->salida .= "           </div>\n";
        $this->salida .= "           <div id=\"eps_anteriores1\">";
        $this->salida .= "           </div>\n";
        $javaC1.= "<script>\n";
        $javaC1 .= "   function MostrarEnfermedades(a,b)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "     xajax_MostrarEnfermedades(a,b);\n";
        $javaC1 .= "   }\n";
        $javaC1 .= "   MostrarEnfermedades('".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."')\n";
        $javaC1 .= "   function MostrarTrabajosAnteriores(a,b)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "      xajax_MostrarTrabajosAnteriores(a,b);\n";
        $javaC1 .= "   }\n";
        $javaC1 .= "   MostrarTrabajosAnteriores('".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."')\n";
        $javaC1 .= "   function MostrarEPS_Anteriores_x(a,b)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "      xajax_MostrarEPS_Anteriores_x(a,b);\n";
        $javaC1 .= "   }\n";
        $javaC1 .= "   MostrarEPS_Anteriores_x('".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."')\n";
        $javaC1.= "</script>\n";
        $this->salida.= $javaC1;
        $this->salida .= ThemeCerrarTabla();
        return $this->salida;
	}
    /**
    * 
    */
    function FormaConsulta($trabajos,$eps,$enfermedades)
    {
      $html = "";
      if(!empty($enfermedades))
      {
        $html .= "<table class=\"modulo_table_list\" width='100%' align='center'>\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td colspan='4'>HISTORIAL DE ENFERMEDADES Y ACCIDENTES LABORALES</td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td width='16%'>FECHA</td>\n";
        $html .= "    <td width='42%'>ENFERMEDAD</td>\n";
        $html .= "    <td width='42%'>ACCIDENTE LABORAL</td>\n";
        $html .= "  </tr>\n";
        foreach($enfermedades as $key => $dtl)
        {
          $html .= "  <tr class=\"modulo_list_claro\">\n";
          $html .= "    <td class=\"normal_10AN\" align=\"center\">".$dtl['fecha_registro']."</td>\n";
          $html .= "    <td class=\"normal_10AN\">";          
          if($dtl['enfermedad_profesional']=='1')
            $html .= "      ".$dtl['descripcion_enfermedad']."";
          else
            $html .= "      NINGUNA";
          
          $html .= "    </td>\n";
          $html .= "    <td class=\"normal_10AN\">";          

          if($dtl['accidente_laboral']=='1')
            $html .= "      ".$dtl['descripcion_accidente']."";
          else
            $html .= "      NINGUNA";
          
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "</table>\n";
        $html .= "</br>\n";
      }
      
      if(!empty($trabajos))
      {
        $html .= "<table class=\"modulo_table_list\" width='100%' align='center'>\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td colspan=\"6\" >HISTORIAL DE TRABAJOS ANTERIORES DEL PACIENTE</td>\n";
        $html .= "  </tr>\n";
         
        foreach($trabajos[0] as $key=> $resultado1)
        {   
          $html .= "  <tr class=\"modulo_table_list_title\">\n";
          $html .= "    <td width='10%' align=\"left\">EMPLEADOR</td>\n";
          $html .= "    <td width='30%' class=\"modulo_list_claro\">".$resultado1['empleador']."</td>\n";
          $html .= "    <td width='15%' align=\"left\">LUGAR</td>\n";
          $html .= "    <td colspan=\"3\" class=\"modulo_list_claro\" >\n";
          $html .= "      ".$resultado1['municipio']."-".$resultado1['departamento']."-".$resultado1['pais']."";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr class=\"modulo_table_list_title\">\n";
          $html .= "    <td align=\"left\">CARGO</td>\n";
          $html .= "    <td align=\"left\" class=\"modulo_list_claro\" >".$resultado1['cargo']."</td>\n";
          $html .= "    <td align=\"left\">FECHA INGRESO</td>\n";
          $html .= "    <td align=\"left\" class=\"modulo_list_claro\">".$resultado1['fecha_ini']."</td>\n";
          $html .= "    <td align=\"left\">FECHA RETIRO</td>\n";
          $html .= "    <td align=\"left\" class=\"modulo_list_claro\" >".$resultado1['fecha_fin']."</td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr class=\"modulo_table_list_title\">\n";
          $html .= "    <td align=\"left\">INTENSIDAD</td>\n";
          $html .= "    <td align=\"left\" class=\"modulo_list_claro\">";
          
          switch($resultado1['intensidad'])
          {
            case 'A': $html .= "ALTA"; break;
            case 'M': $html .= "MEDIA"; break;
            case 'B': $html .= "BAJA"; break;
          }
          $html .= "    </td>\n";
          $html .= "    <td align=\"left\">HORAS POR DIA</td>\n";
          $html .= "    <td align=\"left\" class=\"modulo_list_claro\">".$resultado1['horas_dia']."</td>\n";
          $html .= "    <td align=\"left\">DIAS A LA SEMANA</td>\n";
          $html .= "    <td align=\"left\" class=\"modulo_list_claro\" >".$resultado1['dias_por_semana']."</td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr>\n";
          $html .= "    <td colspan='2' class=\"modulo_list_claro\">\n";
          $html .= "      <b class='label_error'>NOTA:</b> <br>";
          if($resultado1['empresa_elemetos_protectores']=="S")
            $html .= "      <b>* LA EMPRESA SI APORTO ELEMENTOS PROTECTORES PERSONALES </b>";
          elseif($resultado1['empresa_elemetos_protectores']=="N")
            $html .= "      <b>* LA EMPRESA NO APORTO ELEMENTOS PROTECTORES PERSONALES </b>";

          $html .= "      <br>\n";
          
          if($resultado1['uso_elemetos_protectores']=="S")
            $html .= "      <b>* EL PACIENTE SI UTILIZO ELEMENTOS PROTECTORES PERSONALES </b>";
          elseif($resultado1['uso_elemetos_protectores']=="N")
            $html .= "      <b>* EL PACIENTE NO UTILIZO ELEMENTOS PROTECTORES PERSONALES </b>";
          

          $html .= "    </td>\n";
          $html .= "    <td colspan='4' class=\"modulo_list_claro\">\n";
          $html .= "      <table class=\"modulo_table_list\" width='100%' align='center'>\n";

          if(!empty($trabajos[1][$resultado1['trabajo_id']]))
          {
            $html .= "        <tr class=\"modulo_table_list_title\">\n";
            $html .= "          <td colspan='6'>RIESGOS ASOCIADOS A ESE TRABAJO</td>\n";
            $html .= "        </tr>\n";
            $html .= "        <tr class=\"modulo_table_list_title\">\n";
            $html .= "          <td>TIPO DE RIESGO</td>\n";
            $html .= "          <td>COLOR</td>\n";
            $html .= "          <td>AGENTE DE RIESGO</td>\n";
            $html .= "        </tr>\n";
             
            foreach($trabajos[1][$resultado1['trabajo_id']] as $key1 => $valor_riesgos)
            {
              $html .= "        <tr class=\"modulo_list_claro\">\n";
              $html .= "          <td rowspan='".count($valor_riesgos)."' align=\"center\">".$key1."</td>\n";

              $xan=0;
              foreach($valor_riesgos as $key2 => $agentes_riesgos )
              {
                if($xan != 0)
                  $html .= "        <tr class=\"modulo_list_claro\">\n";
                
                $html .= "          <td bgcolor='".$agentes_riesgos['color']."' align=\"center\">&nbsp;</td>\n";
                $html .= "          <td >".$agentes_riesgos['agente_de_riesgo_nom']."</td>\n";
                $html .= "        </tr>\n";
                $xan++;
              }    
            }
          }
          else
          {
            $html .= "        <tr class=\"modulo_list_claro\">\n";
            $html .= "          <td class='label_error' align=\"center\">\n";
            $html .= "            NO SE ASIGNARON AGENTES DE RIESGO A ESTE TRABAJO";
            $html .= "          </td>\n";
            $html .= "        </tr>\n";
          }
          $html .= "      </table>";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "</table>\n";
        $html .= "</br>\n";
      }

      if(!empty($eps))
      {
        $html .= "<table class=\"modulo_table_list\" width='100%' align='center'>\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td colspan='5'>HISTORIAL DE AFILIACIONES ANTERIORES</td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td width='27%'>NOMBRE ARP</td>\n";
        $html .= "    <td width='27%'>NOMBRE EPS</td>\n";
        $html .= "    <td width='26%'>PENSION</td>\n";
        $html .= "    <td width='10%'>FECHA DE INGRESO</td>\n";
        $html .= "    <td width='10%'>FECHA DE RETIRO</td>\n";
        $html .= "  </tr>\n";
        
        foreach($eps as $key => $dtl)
        {
          $html .= "  <tr class=\"modulo_list_claro\">\n";
          $html .= "    <td >".$dtl['nombre_arp_anterior']."</td>\n";
          $html .= "    <td >".$dtl['nombre_eps_anterior']."</td>\n";
          $html .= "    <td >".$dtl['nombre_pensiones_anterior']."</td>\n";
          $html .= "    <td >".$dtl['fecha_ingreso']."</td>\n";
          $html .= "    <td >".$dtl['fecha_retiro']."</td>\n";
          $html .= "  </tr>\n";
        }
        
        $html .= "</table>\n";
      }
      return $html;
    }
    /**
    * 
    */
    function FormaHistoria($trabajos,$eps,$enfermedades)
    {
      $html = "";
      if(!empty($enfermedades))
      {
        $html .= "<table border=\"1\" cellpadin=\"0\" cellspacing=\"0\" rules=\"all\" width='100%' align='center'>\n";
        $html .= "  <tr class=\"label\" align=\"center\">\n";
        $html .= "    <td colspan='4'>HISTORIAL DE ENFERMEDADES Y ACCIDENTES LABORALES</td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"label\" align=\"center\">\n";
        $html .= "    <td width='16%'>FECHA</td>\n";
        $html .= "    <td width='42%'>ENFERMEDAD</td>\n";
        $html .= "    <td width='42%'>ACCIDENTE LABORAL</td>\n";
        $html .= "  </tr>\n";
        foreach($enfermedades as $key => $dtl)
        {
          $html .= "  <tr class=\"normal_10\">\n";
          $html .= "    <td class=\"label\" align=\"center\">".$dtl['fecha_registro']."</td>\n";
          $html .= "    <td class=\"label\">";          
          if($dtl['enfermedad_profesional']=='1')
            $html .= "      ".$dtl['descripcion_enfermedad']."";
          else
            $html .= "      NINGUNA";
          
          $html .= "    </td>\n";
          $html .= "    <td class=\"label\">";          

          if($dtl['accidente_laboral']=='1')
            $html .= "      ".$dtl['descripcion_accidente']."";
          else
            $html .= "      NINGUNA";
          
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "</table>\n";
        $html .= "</br>\n";
      }
      
      if(!empty($trabajos))
      {
        $html .= "<table border=\"1\" cellpadin=\"0\" cellspacing=\"0\" rules=\"all\" width='100%' align='center'>\n";
        $html .= "  <tr class=\"label\" align=\"center\">\n";
        $html .= "    <td colspan=\"6\" >HISTORIAL DE TRABAJOS ANTERIORES DEL PACIENTE</td>\n";
        $html .= "  </tr>\n";
         
        foreach($trabajos[0] as $key=> $resultado1)
        {   
          $html .= "  <tr >\n";
          $html .= "    <td width='10%' class=\"label\">EMPLEADOR</td>\n";
          $html .= "    <td width='30%' class=\"normal_10\">".$resultado1['empleador']."</td>\n";
          $html .= "    <td width='15%' class=\"label\">LUGAR</td>\n";
          $html .= "    <td colspan=\"3\" class=\"normal_10\" >\n";
          $html .= "      ".$resultado1['municipio']."-".$resultado1['departamento']."-".$resultado1['pais']."";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr>\n";
          $html .= "    <td class=\"label\">CARGO</td>\n";
          $html .= "    <td class=\"normal_10\" >".$resultado1['cargo']."</td>\n";
          $html .= "    <td class=\"label\">FECHA INGRESO</td>\n";
          $html .= "    <td class=\"normal_10\">".$resultado1['fecha_ini']."</td>\n";
          $html .= "    <td class=\"label\">FECHA RETIRO</td>\n";
          $html .= "    <td class=\"normal_10\" >".$resultado1['fecha_fin']."</td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr>\n";
          $html .= "    <td class=\"label\">INTENSIDAD</td>\n";
          $html .= "    <td class=\"normal_10\">";
          
          switch($resultado1['intensidad'])
          {
            case 'A': $html .= "ALTA"; break;
            case 'M': $html .= "MEDIA"; break;
            case 'B': $html .= "BAJA"; break;
          }
          $html .= "    </td>\n";
          $html .= "    <td class=\"label\">HORAS POR DIA</td>\n";
          $html .= "    <td class=\"normal_10\">".$resultado1['horas_dia']."</td>\n";
          $html .= "    <td class=\"label\">DIAS A LA SEMANA</td>\n";
          $html .= "    <td class=\"normal_10\" >".$resultado1['dias_por_semana']."</td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr>\n";
          $html .= "    <td colspan='2' class=\"normal_10\">\n";
          $html .= "      <b class='label_error'>NOTA:</b> <br>";
          if($resultado1['empresa_elemetos_protectores']=="S")
            $html .= "      <b>* LA EMPRESA SI APORTO ELEMENTOS PROTECTORES PERSONALES </b>";
          elseif($resultado1['empresa_elemetos_protectores']=="N")
            $html .= "      <b>* LA EMPRESA NO APORTO ELEMENTOS PROTECTORES PERSONALES </b>";

          $html .= "      <br>\n";
          
          if($resultado1['uso_elemetos_protectores']=="S")
            $html .= "      <b>* EL PACIENTE SI UTILIZO ELEMENTOS PROTECTORES PERSONALES </b>";
          elseif($resultado1['uso_elemetos_protectores']=="N")
            $html .= "      <b>* EL PACIENTE NO UTILIZO ELEMENTOS PROTECTORES PERSONALES </b>";
          

          $html .= "    </td>\n";
          $html .= "    <td colspan='4'>\n";
          $html .= "      <table border=\"1\" cellpadin=\"0\" cellspacing=\"0\" rules=\"all\" width='100%' align='center'>\n";

          if(!empty($trabajos[1][$resultado1['trabajo_id']]))
          {
            $html .= "        <tr class=\"label\" align=\"center\">\n";
            $html .= "          <td colspan='6'>RIESGOS ASOCIADOS A ESE TRABAJO</td>\n";
            $html .= "        </tr>\n";
            $html .= "        <tr class=\"label\" align=\"center\">\n";
            $html .= "          <td>TIPO DE RIESGO</td>\n";
            $html .= "          <td>COLOR</td>\n";
            $html .= "          <td>AGENTE DE RIESGO</td>\n";
            $html .= "        </tr>\n";
             
            foreach($trabajos[1][$resultado1['trabajo_id']] as $key1 => $valor_riesgos)
            {
              $html .= "        <tr class=\"normal_10\">\n";
              $html .= "          <td rowspan='".count($valor_riesgos)."' align=\"center\">".$key1."</td>\n";

              $xan=0;
              foreach($valor_riesgos as $key2 => $agentes_riesgos )
              {
                if($xan != 0)
                  $html .= "        <tr class=\"normal_10\">\n";
                
                $html .= "          <td bgcolor='".$agentes_riesgos['color']."' align=\"center\">&nbsp;</td>\n";
                $html .= "          <td >".$agentes_riesgos['agente_de_riesgo_nom']."</td>\n";
                $html .= "        </tr>\n";
                $xan++;
              }    
            }
          }
          else
          {
            $html .= "        <tr class=\"normal_10\">\n";
            $html .= "          <td class='label_error' align=\"center\">\n";
            $html .= "            NO SE ASIGNARON AGENTES DE RIESGO A ESTE TRABAJO";
            $html .= "          </td>\n";
            $html .= "        </tr>\n";
          }
          $html .= "      </table>";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "</table>\n";
        $html .= "</br>\n";
      }

      if(!empty($eps))
      {
        $html .= "<table border=\"1\" cellpadin=\"0\" cellspacing=\"0\" rules=\"all\" width='100%' align='center'>\n";
        $html .= "  <tr class=\"label\" align=\"center\">\n";
        $html .= "    <td colspan='5'>HISTORIAL DE AFILIACIONES ANTERIORES</td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"label\" align=\"center\">\n";
        $html .= "    <td width='27%'>NOMBRE ARP</td>\n";
        $html .= "    <td width='27%'>NOMBRE EPS</td>\n";
        $html .= "    <td width='26%'>PENSION</td>\n";
        $html .= "    <td width='10%'>FECHA DE INGRESO</td>\n";
        $html .= "    <td width='10%'>FECHA DE RETIRO</td>\n";
        $html .= "  </tr>\n";
        
        foreach($eps as $key => $dtl)
        {
          $html .= "  <tr class=\"normal_10\">\n";
          $html .= "    <td >".$dtl['nombre_arp_anterior']."</td>\n";
          $html .= "    <td >".$dtl['nombre_eps_anterior']."</td>\n";
          $html .= "    <td >".$dtl['nombre_pensiones_anterior']."</td>\n";
          $html .= "    <td >".$dtl['fecha_ingreso']."</td>\n";
          $html .= "    <td >".$dtl['fecha_retiro']."</td>\n";
          $html .= "  </tr>\n";
        }
        
        $html .= "</table>\n";
      }
      return $html;
    }
  }
?>