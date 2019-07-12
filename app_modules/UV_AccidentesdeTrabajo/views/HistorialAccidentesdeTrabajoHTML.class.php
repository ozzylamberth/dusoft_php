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

	class HistorialAccidentesdeTrabajoHTML
	{
		/**
		* Constructor de la clase
		*/
		function HistorialAccidentesdeTrabajoHTML(){}
		/**
	    * @param array $action Vector de links de la aplicaion
		* @param array $partes_del_cuerpo_afectado 
		* @param array $tipos_lesion
        * @param array $Agentes_Accidentes
        * @param array $Formas_Accidente
        * @param array $tipos_Accidente.
        * @return String $html con la forma para diligenciar un accidente de trabajo.
		*/
		function HistorialdeAccidenteTrabajo($action,$lista_accidentes,$nombre)
		{
            
            $path=SessionGetVar("rutaImagenes");
            $html  = ThemeAbrirTabla('HISTORIAL DE ACCIDENTES DE TRABAJO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   -  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   PACIENTE:'.$nombre);

//             ["accidente_id"]=>
//             string(1) "1"
//             ["tipo_id_trabajador"]=>
//             string(2) "CC"
//             ["trabajador_id"]=>
//             string(8) "25326988"
//             ["tipo_accidente_id"]=>
//             string(1) "3"
//             ["fecha_accidente"]=>
//             string(10) "2008-05-27"
//             ["hora_accidente"]=>
//             string(8) "01:03:06"
//             ["jornada_accidente"]=>
//             string(1) "0"
//             ["realizando_trabajo_habitual"]=>
//             string(1) "1"
//             ["trabajo_no_habitual"]=>
//             string(26) "ssssssssssssssssssssssssss"
//             ["tipo_pais_id"]=>
//             string(2) "CO"
//             ["tipo_dpto_id"]=>
//             string(2) "17"
//             ["tipo_mpio_id"]=>
//             string(3) "174"
//             ["zona_residencial"]=>
//             string(1) "1"
//             ["sw_accidente_dentro_empresa"]=>
//             string(1) "1"
//             ["sitio_accidente_id"]=>
//             string(1) "4"
//             ["descripcion_accindente"]=>
//             string(120) "DESCRIBA DETALLADAMENTE EN EL RECUADRO LA INFORMACION DEL ACCIDENTE QUE LO ORIGINO O CAUSO Y DEMAS ASPECTOS RELACIONADOS"
//             ["sw_personas_presenciaron_accidente"]=>
//             string(1) "0"
//             ["fecha_registro"]=>
//             string(10) "2008-05-15"
//             ["usuario_registro"]=>
//             string(1) "2"


            
        if(!empty($lista_accidentes))
        {
            $html .= "               <div align='center' id=\"tipos_de_riesgox\">";
            $html .= "                 <form name=\"accidente_trabajo\" id=\"accidente_trabajo\" action=\"#\" method=\"post\">\n";
            $html .= "                   <table class=\"modulo_table_list\" width=\"80%\" align=\"center\"  >\n";
            $html .= "                     <tr class=\"modulo_table_list_title\">\n";
            $html .= "                     <td width='10%' align=\"center\">\n";
            $html .= "                        FECHA DEL ACCIDENTE";
            $html .= "                     </td>\n";
            $html .= "                     <td  width='15%' align=\"center\">\n";
            $html .= "                        ACCIDENTE ID";
            $html .= "                     </td>\n";
            $html .= "                     <td  width='15%' align=\"center\">\n";
            $html .= "                        TIPO DE ACCIDENTE";
            $html .= "                     </td>\n";
            $html .= "                     <td  width='55%' align=\"center\">\n";
            $html .= "                        DESCRIPCION DEL ACCIDENTE";
            $html .= "                     </td>\n";
            $html .= "                     <td  width='5%' align=\"center\">\n";
            $html .= "                        <a title='VER INFORMACION COMPLETA'>";
            $html .= "                        VER";
            $html .= "                        </a>";
            $html .= "                     </td>\n";
            $html .= "                   </tr>\n";

            for($i=0;$i<count($lista_accidentes);$i++)
            {
                $html .= "                   <tr class=\"modulo_list_claro\">\n";
                $html .= "                       <td  width=\"17%\" align=\"center\" > \n";
                $html .= "                       ".$lista_accidentes[$i]['fecha_accidente']."";
                $html .= "                       </td>\n";
                $html .= "                     <td  width='10%' align=\"center\">\n";
                $html .= "                       ".$lista_accidentes[$i]['accidente_id']."";
                $html .= "                     </td>\n";
                $html .= "                     <td  width='10%' align=\"LEFT\">\n";
                $html .= "                       ".$lista_accidentes[$i]['des_tipo_acc']."";
                $html .= "                     </td>\n";
                $html .= "                     <td  width='10%' align=\"LEFT\">\n";
                $html .= "                       ".$lista_accidentes[$i]['descripcion_accindente']."";
                $html .= "                     </td>\n";
                $html .= "                     <td width='26%' class=\"modulo_list_claro\" align=\"left\">\n";
                //$historial = ModuloGetURL('app','UV_AccidentesdeTrabajo','controller','HistorialAccidenteTrabajo',array('afiliado_tipo_id'=>$afiliados[$i]['afiliado_tipo_id'],'afiliado_id'=>$afiliados[$i]['afiliado_id'],'nombre_afiliado'=>$afiliados[$i]['nombre_afiliado']));
                $action['historial']=$action['historial'].URLRequest(array('accidente_id'=>$lista_accidentes[$i]['accidente_id'],'tipo_id_trabajador'=>$lista_accidentes[$i]['tipo_id_trabajador'],'trabajador_id'=>$lista_accidentes[$i]['trabajador_id'],'nombre_afiliado'=>$nombre));
                $html .="                         <a title='VER INFORMACION COMPLETA DEL ACCIDENTE' href=\"".$action['historial']."\">";
                $html .="                          <sub><img src=\"".$path."/images/hc.png\" border=\"0\" width=\"21\" height=\"21\"></sub>\n";//usuarios.png
                $html .="                         </a>\n";
                $html .= "                      </td>\n";
                $html .= "                   </tr>\n";
        

            }


            $html .= "  </table>";
            $html .= "  </form>";
		}
        else
        {
           $html .= "   <center><div class=\"label_error\" id=\"error\">ESTE PACIENTE NO TIENE ACCIDENTES DE TRABAJO REGISTRADOS</div></center>\n"; 
        }
        $html .= "  <br>\n";
        $html .= "   <table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "     <tr>\n";
        $html .= "       <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
        $html .= "        <td align=\"center\"><br>\n";
        $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
        $html .= "        </td>";
        $html .= "      </form>";
        $html .= "    </tr>";
        $html .= "  </table>";


        $html .= ThemeCerrarTabla();
        return $html;
    }
}
?>