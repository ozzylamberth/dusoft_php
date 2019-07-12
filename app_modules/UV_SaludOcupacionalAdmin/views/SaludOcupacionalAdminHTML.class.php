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

	class SaludOcupacionalAdminHTML
	{
		/**
		* Constructor de la clase
		*/
		function SaludOcupacionalAdminHTML(){}
		/**
	    * @param array $action Vector de links de la aplicaion
		* @param array $tipos_identificacion Vector de tipos de identificacion
		* @param array $tipos_afiliados Vector de tipos de afiliados
        * @param array $estados_afiliados Vector de estados de afiliados
        * @param array $dependencias Vector de dependencias de la U.V.
        * @param array $estamentos Vector de estamentos
        * @param array $tipos_aportantes Vector de tipos aportantes
        *
        * @return String
		*/
		function FormaRiesgos($action,$tipos_de_riesgo,$agentes_de_riesgos)
		{
           $path=SessionGetVar("rutaImagenes"); 
           $html  = ThemeAbrirTabla('GESTION DE TIPOS Y AGENTES DE RIESGOS');
           $javaC = "<script>\n";
           $javaC .= "   var contenedor1=''\n";
           $javaC .= "   var titulo1=''\n";
           $javaC .= "   var hiZ = 2;\n";
           $javaC .= "   var DatosFactor = new Array();\n";
           $javaC .= "   var EnvioFactor = new Array();\n";
           $javaC .= "   function Iniciar2(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'ContenedorGrup';\n";
           $javaC .= "       titulo1 = 'tituloGrup';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC.= "        Capa = xGetElementById(contenedor1);\n";
           $javaC .= "       xResizeTo(Capa, 500, 'auto');\n";
           $javaC.= "        Capx = xGetElementById('ContenidoGrup');\n";
           $javaC .= "       xResizeTo(Capx, 500, 150);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+30);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 480, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarGrup');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 480, 0);\n";
           $javaC .= "   }\n";
           $javaC .= "   function Iniciar3(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'ContenedorMed';\n";
           $javaC .= "       titulo1 = 'tituloMed';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC.= "        Capa = xGetElementById(contenedor1);\n";
           $javaC .= "       xResizeTo(Capa, 350, 'auto');\n";
           $javaC.= "        Capx = xGetElementById('ContenidoMed');\n";
           $javaC .= "       xResizeTo(Capx, 350, 300);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+10);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 330, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarMed');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 330, 0);\n";
           $javaC .= "   }\n";
           $javaC .="</script>\n";
           $html.= $javaC;
           $javaC1.= "<script>\n";
           $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
           $javaC1 .= "   {\n";
           $javaC1 .= "     window.status = '';\n";
           $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
           $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
           $javaC1 .= "     ele.myTotalMX = 0;\n";
           $javaC1 .= "     ele.myTotalMY = 0;\n";
           $javaC1 .= "   }\n";
           $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";
           $javaC1 .= "   {\n";
           $javaC1 .= "     if (ele.id == titulo1) {\n";
           $javaC1 .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
           $javaC1 .= "     }\n";
           $javaC1 .= "     else {\n";
           $javaC1 .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
           $javaC1 .= "     }  \n";
           $javaC1 .= "     ele.myTotalMX += mdx;\n";
           $javaC1 .= "     ele.myTotalMY += mdy;\n";
           $javaC1 .= "   }\n";
           $javaC1 .= "   function myOnDragEnd(ele, mx, my)\n";
           $javaC1 .= "   {\n";
           $javaC1 .= "   }\n";
           $javaC1.= "function MostrarCapa(Elemento)\n";
           $javaC1.= "{\n";
           $javaC1.= "    capita = xGetElementById(Elemento);\n";
           $javaC1.= "    capita.style.display = \"\";\n";
           $javaC1.= "}\n";
           $javaC1.= "function Cerrar(Elemento)\n";
           $javaC1.= "{\n";
           $javaC1.= "    capita = xGetElementById(Elemento);\n";          
           $javaC1.= "    capita.style.display = \"none\";\n";          
           $javaC1.= "}\n";

           $javaC1.= "</script>\n";
           $html.= $javaC1;        
            /*******************************************************************************
            *Ventana para crear tercero
            **********************************************************************************/
            $html .="<div id='ContenedorGrup' class='d2Container' style=\"display:none\">";
            $html .= "    <div id='tituloGrup' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
            $html .= "    <div id='cerrarGrup' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorGrup');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
            $html .= "    <div id='errorGrup' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
            $html .= "    <div id='ContenidoGrup' class='d2Content'>\n";
            $html .= "    </div>\n";
            $html .="</div>";
            /*******************************************************************************
            *fin Ventana para crear tercero
            **********************************************************************************/
            /*******************************************************************************
            *Ventana para crear tercero
            **********************************************************************************/
            $html .="<div id='ContenedorMed' class='d2Container' style=\"display:none\">";
            $html .= "    <div id='tituloMed' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
            $html .= "    <div id='cerrarMed' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorMed');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
            $html .= "    <div id='errorMed' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
            $html .= "    <div id='ContenidoMed' class='d2Content'>\n";
            $html .= "    </div>\n";
            $html .="</div>";
            /*******************************************************************************
            *fin Ventana para crear tercero
            **********************************************************************************/
            $html .= "   <center><div class=\"label_error\" id=\"error\"></div></center>\n";


///////////////////////////////////
            $html .= "                 <table ALIGN='CENTER' WIDTH='50%' BORDER='0'>\n";
            $html .= "                   <tr>\n";
            $html .= "                      <td align=\"CENTER\">\n";
            $javita = "javascript:MostrarCapa('ContenedorGrup');ListarTiposRiesgos();Iniciar2('CREAR TIPO DE RIESGO');\"";
            $html .="                         <a title='CREAR TIPO DE RIESGO' class='label_error' href=\"".$javita."\">";
            $html .="                          <label >CREAR NUEVO TIPO DE RIESGO</label>\n";//usuarios.png
            $html .="                         </a>\n";
            $html .= "                    </td>\n";
            $html .= "                      <td align=\"CENTER\">\n";
            $javita = "javascript:MostrarCapa('ContenedorGrup');CrearAgenteRiesgos();Iniciar2('CREAR AGENTE DE RIESGO');\"";
            $html .="                         <a title='CREAR AGENTE DE RIESGO' class='label_error' href=\"".$javita."\">";
            $html .="                          <label >CREAR CREAR AGENTE DE RIESGO </label>\n";//usuarios.png
            $html .="                         </a>\n";
            $html .= "                    </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                 </table>\n";



            $html .= "               <div align='center' id=\"tipos_de_riesgox\">";
//         if(!empty($tipos_de_riesgo))
//         {           
// 
//        
//             
//             for($i=0;$i<count($tipos_de_riesgo);$i++)
//             {   
// 
//                     $td="BotonBenef".$i;
//                     $html .= "               <div align='center' id=\"Benef".$i."\" style=\"width:100%; height:25px; z-index:1; border: 1px none #000000; overflow:hidden; scrollbars=no;\"onClick=''>";
//                     $html .= "                 <table class=\"modulo_table_list\" width=\"50%\" align=\"center\"  >\n";
//                     $html .= "                   <tr >\n";
//                     $html .= "                     <td class=\"modulo_table_list_title\" width='87%' align=\"center\">\n";
//                     $html .= "                       <a title='TIPO DE RIESGO'>";
//                     $html .= "                        ".$tipos_de_riesgo[$i]['descripcion']."";
//                     $html .= "                       </a>";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td width='5%' align=\"center\">\n";
//                     $html .= "                       <a title='EDITAR INFORMACION TIPO DE RIESGO' href=\"javascript:MostrarCapa('ContenedorGrup');EditarInfo('".$tipos_de_riesgo[$i]['tipo_riesgo_id']."','".$tipos_de_riesgo[$i]['descripcion']."','".$tipos_de_riesgo[$i]['color']."','".$tipos_de_riesgo[$i]['usuario_registro']."');Iniciar2('CREAR TIPO DE RIESGO');\">";
//                     $html .= "                          <sub><img src=\"".$path."/images/edita.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
//                     $html .= "                       </a>";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td BGCOLOR='".$tipos_de_riesgo[$i]['color']."' width='5%' align=\"center\" id='".$td1."'>\n";
//                     $html .= "                       <a title='COLOR TIPO DE RIESGO'\">";
//                     $html .= "                          \n";
//                     $html .= "                       </a>";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td class=\"modulo_table_list_title\" width='3%' align=\"center\" id='".$td."'>\n";
//                     $html .= "                       <a title='DESPLEGAR INFORMACION' href=\"javascript:biger('Benef".$i."','0','".$path."','".$td."');\">";
//                     $html .= "                          <sub><img src=\"".$path."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
//                     $html .= "                       </a>";
//                     $html .= "                     </td>\n";
//                     $html .= "                   </tr>\n";
//                     $html .= "                   </table>\n";
//                     $html .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
//                     $html .= "                   <tr class=\"modulo_list_claro\">\n";
//                     $html .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
//                     $html .= "                        AFILIACION";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td width=\"10%\" align=\"LEFT\">\n";
//                     $html .= "                       ".$valor1['eps_afiliacion_id']."";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
//                     $html .= "                        IDENTIFICACION";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td width=\"30%\"align=\"LEFT\">\n";
//                     $html .= "                       <a  title='TIPO AFILIADO'>";
//                     $html .= "                         ".$valor1['afiliado_tipo_id']."-".$valor1['afiliado_id']."";
//                     $html .= "                       </a>";
//                     $html .= "                     </td>\n";
//                     $html .= "                   </tr>\n";
//                     $html .= "                   <tr class=\"modulo_list_claro\">\n";
//                     $html .= "                     <td  class=\"modulo_table_list_title\"  align=\"center\">\n";
//                     $html .= "                        SEXO";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td  align=\"LEFT\">\n";
//                     if($valor1['tipo_sexo_id']=='M')
//                     {
//                         $html .= "              MASCULINO       ";
//                     }
//                     else
//                     {
//                         $html .= "              FEMENINO";
//                     }
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
//                     $html .= "                        FECHA AFILIACION";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td  align=\"center\">\n";
//                     $html .= "                       ".$valor1['fecha_afiliacion_sgss']."";
//                     $html .= "                     </td>\n";
//                     $html .= "                   </tr>\n";
//                     $html .= "                   <tr class=\"modulo_list_claro\">\n";
//                     $html .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
//                     $html .= "                        PARENTESCO";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td  align=\"left\">\n";
//                     $html .= "                       ".$valor1['descripcion_parentesco']."";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
//                     $html .= "                        FECHA NACIMIENTO";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td align=\"center\">\n";
//                     $html .= "                       <a title='TIPO AFILIADO'>";
//                     $html .= "                         ".$valor1['fecha_nacimiento']."";
//                     $html .= "                       </a>";
//                     $html .= "                     </td>\n";
//                     $html .= "                   </tr>\n";
//                     $html .= "                   <tr class=\"modulo_list_claro\">\n";
//                     $html .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
//                     $html .= "                        ZONA RESIDENCIAL";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td  align=\"LEFT\">\n";
//                     IF($valor1['zona_residencia']=='U')
//                     {
//                         $html .= "                       URBANA";
//                     }
//                     else
//                     {
//                         $html .= "                       RURAL";
//                     }
//                     
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
//                     $html .= "                        DIRECCION RESIDENCIA";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td align=\"LEFT\">\n";
//                     $html .= "                       <a title='DIRECCION RESIDENCIA'>";
//                     $html .= "                         ".$valor1['direccion_residencia']."-( ".$valor1['departamento_municipio'].")";
//                     $html .= "                       </a>";
//                     $html .= "                     </td>\n";
//                     $html .= "                   </tr>\n";
//                     $html .= "                   <tr class=\"modulo_list_claro\">\n";
//                     $html .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
//                     $html .= "                        TELEFONO RESIDENCIA";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td  align=\"LEFT\">\n";
//                     $html .= "                       ".$valor1['telefono_residencia']."";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
//                     $html .= "                        TELEFONO MOVIL";
//                     $html .= "                     </td>\n";
//                     $html .= "                     <td align=\"LEFT\">\n";
//                     $html .= "                       <a title='TIPO AFILIADO'>";
//                     $html .= "                         ".$valor1['telefono_movil']."";
//                     $html .= "                       </a>";
//                     $html .= "                     </td>\n";
//                     $html .= "                   </tr>\n";
//                     $html .= "                 </table>\n";
//                     $html .= "                   </div>\n";
//                
//                
//             }
// 
//           
//         }

    $html .= "               </div>";







////////////////////////////////








            
            $html .= "  <br>\n";
            $html .= "	<table border=\"0\" width=\"50%\" align=\"center\" >\n";
            $html .= "	  <tr>\n";
			$html .= "	    <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "        <td align=\"center\"><br>\n";
			$html .= "          <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "        </td>";
			$html .= "      </form>";
			$html .= "    </tr>";
			$html .= "  </table>";
            $html .="<script language=\"javaScript\">
                        function mOvr(src,clrOver) 
                        {
                            src.style.background = clrOver;
                        }
        
                        function mOut(src,clrIn) 
                        {
                            src.style.background = clrIn;
                        }

                        PintarTiposAgentes();
                    </script>";
            $html .= ThemeCerrarTabla();
            return $html;
		}



        
        
        /**
        * funcion que lista los medicos y la cantidad de grupos familiares asignados
        * @param array $action Vector de links de la aplicaion
        * @return String
        */
        function FormaListarMedicos($action,$vector_medicos)
        {
           $path = SessionGetVar("rutaImagenes");
           $html  = ThemeAbrirTabla('MEDICOS FAMILIARES');
           $javaC = "<script>\n";
           $javaC .= "   var contenedor1=''\n";
           $javaC .= "   var titulo1=''\n";
           $javaC .= "   var hiZ = 2;\n";
           $javaC .= "   var DatosFactor = new Array();\n";
           $javaC .= "   var EnvioFactor = new Array();\n";
           $javaC .= "   function Iniciar2(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'ContenedorGrup';\n";
           $javaC .= "       titulo1 = 'tituloGrup';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC.= "        Capa = xGetElementById(contenedor1);\n";
           $javaC .= "       xResizeTo(Capa, 550, 'auto');\n";
           $javaC.= "        Capx = xGetElementById('ContenidoGrup');\n";
           $javaC .= "       xResizeTo(Capx, 550, 320);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+30);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 530, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarGrup');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 530, 0);\n";
           $javaC .= "   }\n";
           $javaC .= "   function Iniciar3(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'ContenedorMed';\n";
           $javaC .= "       titulo1 = 'tituloMed';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC.= "        Capa = xGetElementById(contenedor1);\n";
           $javaC .= "       xResizeTo(Capa, 350, 'auto');\n";
           $javaC.= "        Capx = xGetElementById('ContenidoMed');\n";
           $javaC .= "       xResizeTo(Capx, 350, 300);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+10);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 330, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarMed');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 330, 0);\n";
           $javaC .= "   }\n";
           $javaC .="</script>\n";
           $html.= $javaC;
           $javaC1.= "<script>\n";
           $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
           $javaC1 .= "   {\n";
           $javaC1 .= "     window.status = '';\n";
           $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
           $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
           $javaC1 .= "     ele.myTotalMX = 0;\n";
           $javaC1 .= "     ele.myTotalMY = 0;\n";
           $javaC1 .= "   }\n";
           $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";
           $javaC1 .= "   {\n";
           $javaC1 .= "     if (ele.id == titulo1) {\n";
           $javaC1 .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
           $javaC1 .= "     }\n";
           $javaC1 .= "     else {\n";
           $javaC1 .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
           $javaC1 .= "     }  \n";
           $javaC1 .= "     ele.myTotalMX += mdx;\n";
           $javaC1 .= "     ele.myTotalMY += mdy;\n";
           $javaC1 .= "   }\n";
           $javaC1 .= "   function myOnDragEnd(ele, mx, my)\n";
           $javaC1 .= "   {\n";
           $javaC1 .= "   }\n";
           $javaC1.= "function MostrarCapa(Elemento)\n";
           $javaC1.= "{\n";
           $javaC1.= "    capita = xGetElementById(Elemento);\n";
           $javaC1.= "    capita.style.display = \"\";\n";
           $javaC1.= "}\n";
           $javaC1.= "function Cerrar(Elemento)\n";
           $javaC1.= "{\n";
           $javaC1.= "    capita = xGetElementById(Elemento);\n";          
           $javaC1.= "    capita.style.display = \"none\";\n";          
           $javaC1.= "}\n";

           $javaC1.= "</script>\n";
           $html.= $javaC1;        
            /*******************************************************************************
            *Ventana para crear tercero
            **********************************************************************************/
            $html .="<div id='ContenedorGrup' class='d2Container' style=\"display:none\">";
            $html .= "    <div id='tituloGrup' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
            $html .= "    <div id='cerrarGrup' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorGrup');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
            $html .= "    <div id='errorGrup' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
            $html .= "    <div id='ContenidoGrup' class='d2Content'>\n";
            $html .= "    </div>\n";
            $html .="</div>";
            /*******************************************************************************
            *fin Ventana para crear tercero
            **********************************************************************************/
            /*******************************************************************************
            *Ventana para crear tercero
            **********************************************************************************/
            $html .="<div id='ContenedorMed' class='d2Container' style=\"display:none\">";
            $html .= "    <div id='tituloMed' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
            $html .= "    <div id='cerrarMed' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorMed');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
            $html .= "    <div id='errorMed' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
            $html .= "    <div id='ContenidoMed' class='d2Content'>\n";
            $html .= "    </div>\n";
            $html .="</div>";
            /*******************************************************************************
            *fin Ventana para crear tercero
            **********************************************************************************/
            $html .= "   <center><div class=\"label_error\" id=\"error\"></div></center>\n";
            $html .= " <div id='tabla_afiliados'>\n";
            $html .= "  </div>\n";
            if(!empty($vector_medicos))
            {
                $html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
                $html .= "                    <tr class=\"modulo_table_list_title\">\n";
                $html .= "                       <td width=\"20%\" align=\"center\">\n";
                $html .= "                       <a title='IDENTIFICACION DEL MEDICO'>";
                $html .= "                        IDENTIFICACION";
                $html .= "                       </a>";
                $html .= "                       </td>\n";
                $html .= "                       <td width=\"15%\" align=\"center\">\n";
                $html .= "                        USUARIO";
                $html .= "                       </td>\n";
                $html .= "                       <td width=\"50%\" align=\"center\">\n";
                $html .= "                       <a title='IDENTIFICACION DEL AFILIADO'>";
                $html .= "                         NOMBRE";
                $html .= "                       </a>";
                $html .= "                       </td>\n";
                $html .= "                       <td width=\"15%\" align=\"center\">\n";
                $html .= "                       <a title='CANTIDAD DE GRUPOS FAMILIARES'>";
                $html .= "                          GRUPOS FAMILIARES";
                $html .= "                       </a>";
                $html .= "                       </td>\n";
                $html .= "                    </tr>\n";
                for($i=0;$i<count($vector_medicos);$i++)
                {   
                    $td="medico".$i;
                    $html .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                    $html .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                    $html .= "                       ".$vector_medicos[$i]['tipo_id_tercero']." - ".$vector_medicos[$i]['tercero_id'];
                    $html .= "                       </td>\n";
                    $html .= "                      <td align=\"left\">\n";
                    $html .= "                       ".$vector_medicos[$i]['usuario_profesional'];
                    $html .= "                      </td>\n";
                    $html .= "                      <td align=\"left\">\n";
                    $html .= "                       ".$vector_medicos[$i]['nombre'];
                    $html .= "                      </td>\n";
                    $html .= "                      <td align=\"left\">\n";
                    $beneficiario = "javascript:MostrarCapa('ContenedorGrup');ListarGrupos('".$vector_medicos[$i]['tipo_id_tercero']."','".$vector_medicos[$i]['tercero_id']."','".$vector_medicos[$i]['nombre']."');Iniciar2('GRUPOS FAMILIARES DEL MEDICO ".$vector_medicos[$i]['nombre']."');\"";
                    $html .="                         <a title='CONSULTAR BENEFICIARIO (GRUPO FAMILIAR)' href=\"".$beneficiario."\">";
                    $html .="                          <sub><img src=\"".$path."/images/mvto_errado.png\" border=\"0\" width=\"21\" height=\"21\"></sub>\n";//usuarios.png
                    $html .="                         </a>\n";
                    $html .= "                       ".$vector_medicos[$i]['grupos_de_familia'];
                    
                    $html .= "                      </td>\n";
                    $html .= "                    </tr>";
                }   
                $html .= "  </table>";
            }
            $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
            $html .= "    <tr>\n";
            $html .= "      <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
            $html .= "        <td align=\"center\"><br>\n";
            $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
            $html .= "        </td>";
            $html .= "      </form>";
            $html .= "    </tr>";
            $html .= "  </table>";
            $html .="<script language=\"javaScript\">
                        function mOvr(src,clrOver) 
                        {
                        src.style.background = clrOver;
                        }
        
                        function mOut(src,clrIn) 
                        {
                        src.style.background = clrIn;
                        }
                    </script>";
            $html .= ThemeCerrarTabla();
            return $html;
        }
	}
?>