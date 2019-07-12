<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultaAfiliadoHTML.class.php,v 1.7 2007/11/08 22:53:48 jgomez Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */

  /**
  * Clase Vista: ConsultaAfiliadoHTML 
  * Clase contiene metodos para la consulta de afiliados del sistema
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.7 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Jaime Gomez
  */

	class DetalleAccidenteTrabajoHTML
	{
		/**
		* Constructor de la clase
		*/
		function DetalleAccidenteTrabajoHTML(){}
		/**
	    * @param array $action Vector de links de la aplicaion
		* @param array $partes_del_cuerpo_afectado 
		* @param array $tipos_lesion
        * @param array $Agentes_Accidentes
        * @param array $Formas_Accidente
        * @param array $tipos_Accidente.
        * @return String $html con la forma para diligenciar un accidente de trabajo.
		*/
		function DetalleAccidenteTrabajoInfo($action,$detalle_accidentes,$nombre)
		{
            
            $path=SessionGetVar("rutaImagenes");
            $html  = ThemeAbrirTabla('INFORMACION COMPLETA DEL ACCIDENTES DE TRABAJO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   -  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   PACIENTE:'.$nombre);
            var_dump($action);
/*

             array(24) {
    ["accidente_id"]=>
    string(1) "1"
    ["tipo_id_trabajador"]=>
    string(2) "CC"
    ["trabajador_id"]=>
    string(8) "25326988"


    ["zona_residencial"]=>
    string(1) "1"


    ["descripcion_accindente"]=>
    string(120) "DESCRIBA DETALLADAMENTE EN EL RECUADRO LA INFORMACION DEL ACCIDENTE QUE LO ORIGINO O CAUSO Y DEMAS ASPECTOS RELACIONADOS"
    ["sw_personas_presenciaron_accidente"]=>
    string(1) "0"
    ["fecha_registro"]=>
    string(10) "2008-05-15"
    ["usuario_registro"]=>
    string(1) "2"

*/
            
        if(!empty($detalle_accidentes))
        {
            $html .= "               <div align='center' id=\"tipos_de_riesgox\">";
            $html .= "                 <form name=\"accidente_trabajo\" id=\"accidente_trabajo\" action=\"#\" method=\"post\">\n";
            $html .= "                   <table class=\"modulo_table_list\" width=\"100%\" align=\"center\"  >\n";
            $html .= "                     <tr>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" width='20%' align=\"center\">\n";
            $html .= "                        FECHA DEL ACCIDENTE";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_list_claro\" width=\"17%\" align=\"center\" > \n";
            $html .= "                       ".$detalle_accidentes[0]['fecha_accidente']."";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" width='15%' align=\"center\">\n";
            $html .= "                        HORA ACCIDENTE";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_list_claro\" width=\"23%\" align=\"center\" > \n";
            $html .= "                       ".$detalle_accidentes[0]['hora_accidente']."";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" width='15%' align=\"center\">\n";
            $html .= "                        ACCIDENTE ID";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_list_claro\" width=\"15%\" align=\"center\" > \n";
            $html .= "                       ".$detalle_accidentes[0]['accidente_id']."";
            $html .= "                     </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                   <tr>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        TIPO ACCIDENTE";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_list_claro\" align=\"center\" > \n";
            $html .= "                       ".$detalle_accidentes[0]['tipo_accidente']."";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        JORNADA ACCIDENTE";
            $html .= "                     </td>\n";
            if($detalle_accidentes[0]['jornada_accidente']=="1")
            {
                $html .= "                     <td class=\"modulo_list_claro\" align=\"center\" > \n";
                $html .= "                      JORNADA NORMAL ";
                $html .= "                     </td>\n";                
            }
            elseif($detalle_accidentes[0]['jornada_accidente']=="0")
            {
                $html .= "                     <td class=\"modulo_list_claro\" align=\"center\" > \n";
                $html .= "                      JORNADA EXTRA";
                $html .= "                     </td>\n";                
            }
            
            $html .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        ESTABA REALIZANDO EL TRABAJO HABITUAL ?";
            $html .= "                     </td>\n";
            if($detalle_accidentes[0]['realizando_trabajo_habitual']=="1")
            {
                $html .= "                     <td class=\"modulo_list_claro\" align=\"center\" > \n";
                $html .= "                      SI";
                $html .= "                     </td>\n";                
            }
            elseif($detalle_accidentes[0]['realizando_trabajo_habitual']=="0")
            {
                $html .= "                     <td class=\"modulo_list_claro\" align=\"center\" > \n";
                $html .= "                      NO, ".$detalle_accidentes[0]['trabajo_no_habitual']."";
                $html .= "                     </td>\n";                
            }
            $html .= "                   </tr>\n";
            $html .= "                   <tr>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        SE ENCONTRABA DENTRO DE LA EMPRESA ?";
            $html .= "                     </td>\n";
            if($detalle_accidentes[0]['sw_accidente_dentro_empresa']=="1")
            {
                $html .= "                     <td class=\"modulo_list_claro\" align=\"center\" > \n";
                $html .= "                      SI";
                $html .= "                     </td>\n";                
            }
            elseif($detalle_accidentes[0]['sw_accidente_dentro_empresa']=="0")
            {
                $html .= "                     <td class=\"modulo_list_claro\" align=\"center\" > \n";
                $html .= "                      NO";
                $html .= "                     </td>\n";                
            }
            $html .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        LUGAR ACCIDENTE";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_list_claro\" align=\"center\" > \n";
            $html .= "                       ".$detalle_accidentes[0]['municipio']." (".$detalle_accidentes[0]['departamento']."-".$detalle_accidentes[0]['pais'].")";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                        SITIO ACCIDENTE";
            $html .= "                     </td>\n";
            $html .= "                     <td class=\"modulo_list_claro\" align=\"center\" > \n";
            $html .= "                       ".$detalle_accidentes[0]['sitio_accidente']."";
            $html .= "                     </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                   <tr>\n";
            $html .= "                     <td colspan='3' align=\"center\">\n";
            $html .= "                     <table width='100%' class=\"modulo_table_list\">\n";
            $html .= "                       <tr>\n";
            $html .= "                       <td colspan='2' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                       PARTES DEL CUERPO APARENTEMENTE AFECTADAS";
            $html .= "                       </td>\n";
            $html .= "                     </tr>\n";
            
            for($i=0;$i<count($detalle_accidentes['PARTES_CUERPO']);$i++)
            {
                $html .= "                     <tr>\n";
                $html .= "                       <td width='10%' class=\"modulo_list_claro\" align=\"left\">\n";
                $html .= "                      (".$detalle_accidentes['PARTES_CUERPO'][$i]['parte_cuerpo_id'].")";
                $html .= "                       </td>\n";
                $html .= "                       <td width='90%' class=\"modulo_list_claro\" align=\"left\">\n";
                $html .= "                      ".$detalle_accidentes['PARTES_CUERPO'][$i]['parte_del_cuerpo']."";
                $html .= "                       </td>\n";
                $html .= "                     </tr>\n";
            }
            $html .= "                         </table>\n";
            $html .= "                       </td>\n";
            $html .= "                     <td colspan='3' align=\"center\">\n";
            $html .= "                     <table width='100%' class=\"modulo_table_list\">\n";
            $html .= "                       <tr>\n";
            $html .= "                       <td colspan='2' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                       TIPOS DE LESIONES QUE TIENE EL TRABAJADOR";
            $html .= "                       </td>\n";
            $html .= "                     </tr>\n";
            
            for($i=0;$i<count($detalle_accidentes['TIPOS_LESION']);$i++)
            {
                $html .= "                     <tr>\n";
                $html .= "                       <td width='10%' class=\"modulo_list_claro\" align=\"left\">\n";
                $html .= "                      (".$detalle_accidentes['TIPOS_LESION'][$i]['tipo_lesion_id'].")";
                $html .= "                       </td>\n";
                $html .= "                       <td width='90%' class=\"modulo_list_claro\" align=\"left\">\n";
                $html .= "                      ".$detalle_accidentes['TIPOS_LESION'][$i]['desc_tipo_lesion']."";
                $html .= "                       </td>\n";
                $html .= "                     </tr>\n";
            }
            $html .= "                         </table>\n";
            $html .= "                       </td>\n";
            $html .= "                     </tr>\n";
                        $html .= "                   <tr>\n";
            $html .= "                     <td colspan='3' align=\"center\">\n";
            $html .= "                     <table width='100%' class=\"modulo_table_list\">\n";
            $html .= "                       <tr>\n";
            $html .= "                       <td colspan='2' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                       AGENTES DEL ACCIDENTE";
            $html .= "                       </td>\n";
            $html .= "                     </tr>\n";
            
            for($i=0;$i<count($detalle_accidentes['AGENTES_ACC']);$i++)
            {
                $html .= "                     <tr>\n";
                $html .= "                       <td width='10%' class=\"modulo_list_claro\" align=\"left\">\n";
                $html .= "                      (".$detalle_accidentes['AGENTES_ACC'][$i]['agente_accidente_id'].")";
                $html .= "                       </td>\n";
                $html .= "                       <td width='90%' class=\"modulo_list_claro\" align=\"left\">\n";
                $html .= "                      ".$detalle_accidentes['AGENTES_ACC'][$i]['desc_agente_accidente']."";
                $html .= "                       </td>\n";
                $html .= "                     </tr>\n";
            }
            $html .= "                         </table>\n";
            $html .= "                       </td>\n";
            $html .= "                     <td colspan='3' align=\"center\">\n";
            $html .= "                     <table width='100%' class=\"modulo_table_list\">\n";
            $html .= "                       <tr>\n";
            $html .= "                       <td colspan='2'  class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                       MECANISMOS O FORMAS DE ACCIDENTE";
            $html .= "                       </td>\n";
            $html .= "                     </tr>\n";
            for($i=0;$i<count($detalle_accidentes['FORMAS_ACC']);$i++)
            {
                $html .= "                     <tr>\n";
                $html .= "                       <td width='10%' class=\"modulo_list_claro\" align=\"left\">\n";
                $html .= "                      (".$detalle_accidentes['FORMAS_ACC'][$i]['forma_accidente_id'].")";
                $html .= "                       </td>\n";
                $html .= "                       <td width='90%' class=\"modulo_list_claro\" align=\"left\">\n";
                $html .= "                      ".$detalle_accidentes['FORMAS_ACC'][$i]['desc_forma_accidente']."";
                $html .= "                       </td>\n";
                $html .= "                     </tr>\n";
            }
            $html .= "                         </table>\n";
            $html .= "                       </td>\n";
            $html .= "                     </tr>";
            $html .= "                   <tr>\n";
            $html .= "                       <td colspan='3' align=\"center\">\n";
            $html .= "                         <table width='100%' class=\"modulo_table_list\">\n";
            $html .= "                           <tr>\n";
            $html .= "                               <td colspan='3' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                                   ESPACIOS Y AGENTES DE RIESGOS";
            $html .= "                               </td>\n";
            $html .= "                          </tr>\n";
            $html .= "                          <tr class=\"modulo_table_list_title\" >\n";
            $html .= "                              <td width='33%' align=\"center\">\n";
            $html .= "                                  ESPACIO";
            $html .= "                              </td>\n";
            $html .= "                       <td width='33%' align=\"center\">\n";
            $html .= "                          TIPO DE RIESGO";
            $html .= "                       </td>\n";
            $html .= "                       <td width='34%' align=\"center\">\n";
            $html .= "                          AGENTE DE RIESGO";
            $html .= "                       </td>\n";
            $html .= "                     </tr>\n";

            for($i=0;$i<count($detalle_accidentes['RIESGOS']);$i++)
            {
                $html .= "                     <tr class=\"modulo_list_claro\" >\n";
                $html .= "                       <td width='33%' align=\"left\">\n";
                $html .= "                          ".$detalle_accidentes['RIESGOS'][$i]['desc_tipo_espacio']."";
                $html .= "                       </td>\n";
                $html .= "                       <td width='33%' align=\"left\">\n";
                $html .= "                          ".$detalle_accidentes['RIESGOS'][$i]['desc_tipo_riesgo']."";
                $html .= "                       </td>\n";
                $html .= "                       <td width='34%' align=\"left\">\n";
                $html .= "                          ".$detalle_accidentes['RIESGOS'][$i]['desc_agente_riesgo']."";
                $html .= "                       </td>\n";
                $html .= "                     </tr>\n";
            }
            $html .= "                         </table>\n";
            $html .= "                       </td>\n";
            $html .= "                     <td valign='TOP' colspan='3' align=\"center\">\n";
            $html .= "                     <table width='100%' class=\"modulo_table_list\">\n";
            $html .= "                       <tr>\n";
            $html .= "                       <td colspan='3' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                       PERSONAS QUE PRESENCIARON EL ACCIDENTE ACCIDENTE";
            $html .= "                       </td>\n";
            $html .= "                     </tr>\n";
            $html .= "                     <tr class=\"modulo_table_list_title\">\n";
            $html .= "                       <td width='33%' align=\"center\">\n";
            $html .= "                          IDENTIFICACION";
            $html .= "                       </td>\n";
            $html .= "                       <td width='34%' align=\"center\">\n";
            $html .= "                          NOMBRE";
            $html .= "                       </td>\n";
            $html .= "                     </tr>\n";

            for($i=0;$i<count($detalle_accidentes['PERSONAS']);$i++)
            {
                $html .= "                     <tr>\n";
                $html .= "                       <td width='35%' class=\"modulo_list_claro\" align=\"left\">\n";
                $html .= "                          ".$detalle_accidentes['PERSONAS'][$i]['tipo_id_tercero']."-".$detalle_accidentes['PERSONAS'][$i]['tercero_id']."";
                $html .= "                       </td>\n";
                $html .= "                       <td width='75%' class=\"modulo_list_claro\" align=\"left\">\n";
                $html .= "                          ".$detalle_accidentes['PERSONAS'][$i]['nombre']."";
                $html .= "                       </td>\n";
                $html .= "                     </tr>\n";
            }
            $html .= "                         </table>\n";
            $html .= "                       </td>\n";
            $html .= "                     </tr>";
            $html .= "                     </table>";
           } 
        $html .= "   </form>";
        $html .= "  <br>\n";
        $html .= "   <table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "     <tr>\n";
        $html .= "       <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
        $html .= "        <td align=\"center\"><br>\n";
        $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
        $html .= "        </td>";
        $html .= "      </form>";
        $html .= "  </table>";


        $html .= ThemeCerrarTabla();
        return $html;
    }
}
?>