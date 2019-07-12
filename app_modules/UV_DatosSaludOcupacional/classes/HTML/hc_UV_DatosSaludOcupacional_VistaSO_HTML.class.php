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
class VistaSO_HTML
{  
     function VistaSO_HTML($objeto=null)
     {
          $this->obj=$objeto;
          return true;
     }

    /**
    * Funcion que contiene la vits del submodulo 
    * @param  array $datos del paciente
    * @param  array $ocupaciones lista de ocupaciones
    * @param  array $espacios lista de espacios 
    * @param  array $ocupaciones_paciente ocupaciones del paciente
    * @param  array $espacios_paciente del espacios del paciente
    *
    **/ 
     

    function Forma($datos=null,$ocupaciones,$espacios,$datos_funcionario)//$ocupaciones_paciente,$espacios_paciente
	{ 
        $path = SessionGetVar("rutaImagenes");
       // var_dump($datos);
//         $contador=count($Lista_ciclos_familiares_seleccionados);
//         $vector_ceros=array();
//         for($j=0;$j<count($Lista_ciclos_familiares_seleccionados);$j++)
//         {
//             $vector_ceros[$i]=0;
//         }

        
        $this->salida .= ThemeAbrirTabla("INFORMACION SALUD OCUPACIONAL");
        $this->salida .= "            <form name=\"menu_docu\" action=\"#\" method=\"post\">\n";
        $this->salida .= "            <div id=\"mensaje\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">";
        $this->salida .= "            </div>\n";
        if(!empty($datos_funcionario))
        {
        
            $this->salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "                   <tr>\n";
            $this->salida .= "                     <td width='24%' class=\"modulo_table_list_title\" align=\"center\">\n";
            $this->salida .= "                       FECHA DE INGRESO";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width='20%' class=\"modulo_list_claro\" align=\"left\">\n";
            $this->salida .= "                     ".$datos_funcionario[0]['fecha_ingreso_laboral']."";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width='23%' class=\"modulo_table_list_title\" align=\"center\">\n";
            $this->salida .= "                       CIUDAD (DONDE LABORA)";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td width='33%' class=\"modulo_list_claro\" align=\"left\">\n";
            $this->salida .= "                     ".$datos_funcionario[0]['municipio']." (".$datos_funcionario[0]['departamento']." - ".$datos_funcionario[0]['pais'].")";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                   </tr>\n";
            $this->salida .= "                   <tr>\n";
            $this->salida .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $this->salida .= "                       DEDICACION";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
            if($datos_funcionario[0]['sw_tiempo_completo']=='1')
            {
                $this->salida .= "                     TIEMPO COMPLETO";
            }
            elseif($datos_funcionario[0]['sw_tiempo_completo']=='0')
            {
                $this->salida .= "                     TIEMPO PARCIAL";
            }
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $this->salida .= "                       JORNADA";
            $this->salida .= "                     </td>\n";
            $this->salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
            if($datos_funcionario[0]['jornada']=='D')
            {
                $this->salida .= "                     DIURNA";
            }
            elseif($datos_funcionario[0]['jornada']=='N')
            {
                $this->salida .= "                     NOCTURNA";
            }
            $this->salida .= "                     </td>\n";
            $this->salida .= "                   </tr>\n";
            $this->salida .= "                 </table>\n";
            $this->salida .= "                 <br>\n";
        }
        $this->salida .= "               <table width=\"100%\" align=\"center\" border='0'>\n";
        $this->salida .= "               <tr>\n";
        $this->salida .= "                 <td width=\"50%\">\n";
        $this->salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td colspan='2' class=\"modulo_table_list_title\" align=\"center\">\n";
        $this->salida .= "                       ASIGNAR NUEVO ESPACIO";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "                       ESPACIO\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
        if(!empty($espacios))
        {


            $this->salida .= "                       <select class=\"select\" name=\"espacios_x\" id=\"espacios_x\" onchange=\"llamarAgentesSegunEspacio(this.value);\">";
            $this->salida .= "                         <option style='background-color:#ffffff;' value=\"-1\">SELECCIONAR</option> \n";
                //ocupacion_id     tipo_riesgo_id integer    agente_riesgo_id integer    usuario_registro integer    fecha_registro    sw_estado
            for($i=0;$i<count($espacios);$i++)
            {
                $this->salida .= "                           <option value=\"".$espacios[$i]['tipo_espacio_id']."\">".$espacios[$i]['descripcion']."</option> \n";
            }
            $this->salida .= "                         </select>\n";

        }
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_list_claro\" colspan=\"4\" align=\"center\" class=\"normal_10AN\">\n";
        $this->salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"ASIGNAR\" onclick=\"GuardarInfoE(document.getElementById('espacios_x').value,'".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."');\">\n";
        $this->salida .= "                     </td>";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                 </table>\n";
        $this->salida .= "                </td>\n";
//         $this->salida .= "              </tr>\n";
//         $this->salida .= "              <tr>\n";
        $this->salida .= "                <td width=\"50%\">\n";
        $this->salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td colspan='2' class=\"modulo_table_list_title\" align=\"center\">\n";
        $this->salida .= "                       ASIGNAR NUEVA OCUPACION";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "                       OCUPACION\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                     <td class=\"modulo_list_claro\" align=\"left\">\n";
        if(!empty($ocupaciones))
        {

            $this->salida .= "                       <select class=\"select\" name=\"ocupaciones\" id=\"ocupaciones\" onchange=\"llamarCargosSegunOcupacion(this.value);\">";
            $this->salida .= "                         <option style='background-color:#ffffff;' value=\"-1\">SELECCIONAR</option> \n";
                //ocupacion_id     tipo_riesgo_id integer    agente_riesgo_id integer    usuario_registro integer    fecha_registro    sw_estado
            for($i=0;$i<count($ocupaciones);$i++)
            {
                $this->salida .= "                           <option value=\"".$ocupaciones[$i]['ocupacion_id']."\">".$ocupaciones[$i]['descripcion']."</option> \n";
            }
           $this->salida .= "                         </select>\n";

        }
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td class=\"modulo_list_claro\" colspan=\"4\" align=\"center\" class=\"normal_10AN\">\n";
        $this->salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"ASIGNAR\" onclick=\"GuardarInfoO(document.getElementById('ocupaciones').value,'".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."');\">\n";
        $this->salida .= "                     </td>";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                 </table>\n";
        $this->salida .= "               </td>\n";
        $this->salida .= "             </tr>\n";
        $this->salida .= "           </table>\n";
        $this->salida .= "             <br>\n";
        $this->salida .= "           <div id=\"cuadro_datos\">";
        $this->salida .= "           </div>\n";

        $this->salida .= "             <br>\n";
        $this->salida .= "           <div id=\"cuadro_datos1\">";
        $this->salida .= "           </div>\n";


        $javaC1.= "<script>\n";
        $javaC1 .= "   function MostrarDatosEspacio(a,b)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "     xajax_MostrarDatosEspacio(a,b);\n";
        $javaC1 .= "   }\n";
        $javaC1 .= "   MostrarDatosEspacio('".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."')\n";

        $javaC1 .= "   function MostrarDatosOcupacion(a,b)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "     xajax_MostrarDatosOcupacion(a,b);\n";
        $javaC1 .= "   }\n";
        $javaC1 .= "   MostrarDatosOcupacion('".$datos->datosPaciente['tipo_id_paciente']."','".$datos->datosPaciente['paciente_id']."')\n";
        $javaC1.= "</script>\n";
        $this->salida.= $javaC1;


        $this->salida .= ThemeCerrarTabla();
        return $this->salida;
	}

}
?>