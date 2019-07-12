<?php

    /**
    * @package IPSOFT-SIIS
    * @version $Id: CrearTerceroConvenioHTML.class.php,v 1.7 2008/06/13 19:38:49 jgomez Exp $ 
    * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
    * @author JAIME GOMEZ
    */
    
    /**
    * Clase : CrearTerceroConvenioHTML - Administracion del modulo de UV_Afiliaciones
    * * Clase contiene un buscador de entidades convenio mas la opcion de crear una nueva entidad convenio
    *
    * @package IPSOFT-SIIS
    * @version $Revision: 1.7 $
    * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
    * @author JAIME GOMEZ
    */
        
	class  CrearTerceroConvenioHTML
	{
		/**
		* Constructor de la clase
		*/
		function CrearTerceroConvenioHTML(){}
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action vector que continen los link de la aplicacion
        * @param array $combo_tipos_id vector que continen los tipos id de los terceros
        * @return String
    */           
		function FormaCrearTerceroConvenio($action,$combo_tipos_id)
		{      
            $path = SessionGetVar("rutaImagenes");
            $html .= ThemeAbrirTabla("CREAR ENTIDAD CONVENIO");
            $javaC = "<script>\n";
            $javaC .= "   var contenedor1=''\n";
            $javaC .= "   var titulo1=''\n";
            $javaC .= "   var hiZ = 2;\n";
            $javaC .= "   var DatosFactor = new Array();\n";
            $javaC .= "   var EnvioFactor = new Array();\n";
            $javaC .= "   function Iniciar2(tit)\n";
            $javaC .= "   {\n";
            $javaC .= "       contenedor1 = 'ContenedorVer';\n";
            $javaC .= "       titulo1 = 'tituloVer';\n";
            $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
            $javaC.= "        Capa = xGetElementById(contenedor1);\n";
            $javaC .= "       xResizeTo(Capa, 950, 'auto');\n";
            $javaC.= "        Capx = xGetElementById('ContenidoVer');\n";
            $javaC .= "       xResizeTo(Capx, 950, 400);\n";
            $javaC .= "       xMoveTo(Capa, xClientWidth()/40, xScrollTop()+30);\n";
            $javaC .= "       ele = xGetElementById(titulo1);\n";
            $javaC .= "       xResizeTo(ele, 930, 20);\n";
            $javaC .= "       xMoveTo(ele, 0, 0);\n";
            $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
            $javaC .= "       ele = xGetElementById('cerrarVer');\n";
            $javaC .= "       xResizeTo(ele, 20, 20);\n";
            $javaC .= "       xMoveTo(ele, 930, 0);\n";
            $javaC .= "   }\n";
            $javaC .= "   function IniciarCent(tit)\n";
            $javaC .= "   {\n";
            $javaC .= "       contenedor1 = 'ContenedorCent';\n";
            $javaC .= "       titulo1 = 'tituloCent';\n";
            $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
            $javaC.= "        Capa = xGetElementById(contenedor1);\n";
            $javaC .= "       xResizeTo(Capa, 600, 480);\n";
            $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+5);\n";
            $javaC .= "       ele = xGetElementById(titulo1);\n";
            $javaC .= "       xResizeTo(ele, 580, 20);\n";
            $javaC .= "       xMoveTo(ele, 0, 0);\n";
            $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
            $javaC .= "       ele = xGetElementById('cerrarCent');\n";
            $javaC .= "       xResizeTo(ele, 20, 20);\n";
            $javaC .= "       xMoveTo(ele, 580, 0);\n";
            $javaC .= "   }\n";
            $javaC.= "</script>\n";
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
            $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";//
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
    
            $javaC1.= "function Traer(Elemento)\n";
            $javaC1.= "{\n";
            $javaC1.= "    document.getElementById('hcuenta').innerHTML=Elemento;\n";
            $javaC1.= "    document.hijo_cuenta.padre.value=Elemento;\n";
            $javaC1.= "}\n";
            $javaC1.= "</script>\n";
            $html.= $javaC1;
            


            /**
            *Ventana emergente 3 aqui es cuando se modifica una cuenta.
            ***/
            $html.="<div id='ContenedorCent' class='d2Container' style=\"display:none;\">";
            $html .= "    <div id='tituloCent' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
            $html .= "    <div id='cerrarCent' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCent');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
            $html .= "    <div id='errorCent' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
            $html .= "    <div id='ContenidoCent' class='d2Content'>\n";
            $html .= "    <form name=\"buscar_terco\" id=\"buscar_terco\" action=\"#\" method=\"post\">\n";
            $html .= "      <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "        <tr class=\"modulo_table_list_title\">\n";
            $html .= "          <td colspan=\"3\" align=\"center\">\n";
            $html .= "            BUSCAR POR RAZON SOCIAL";
            $html .= "          </td>\n";
            $html .= "        </tr>\n";
            $html .= "        <tr class=\"modulo_list_claro\">\n";
            $html .= "          <td width='60%' rowspan='2' align=\"left\">\n";
            $html .= "            <input type=\"text\" class=\"input-text\" id=\"tercero_name\" name=\"tercero_name\" maxlength=\"60\" size=\"60\" value=\"\" onkeypress=\"return acceptm(event)\" onkeydown=\"recogerTecla(event);\" onclick=\"limpiar()\">";
            $html .= "          </td>\n";
            $html .= "          <td width='25%' align=\"left\">\n";
            $html .= "            <input type=\"radio\" id=\"interfaz\" name=\"interfaz\" value=\"0\" onkeypress=\"return acceptNum(event)\" onkeydown=\"\" onclick=\"\" checked='true'> SIIS";
            $html .= "          </td>\n";
            $html .= "          <td width='15%' rowspan='2' align=\"center\">\n";
            $html .= "            <input type=\"button\" class=\"input-submit\" value=\"Buscar\" onclick=\"BuscarTerceroPorNombre(document.getElementById('tercero_name').value,1,0);\">\n";
            $html .= "          </td>\n";
            $html .= "        </tr>\n";
            $html .= "        <tr class=\"modulo_list_claro\">\n";
            $html .= "          <td align=\"left\" title='ES NECESARIO QUE PRIMERO SE CONSULTE EN EL MAESTRO DE TERCEROS DE SIIS'>\n";
            $html .= "            <input type=\"radio\" id=\"interfax\" name=\"interfaz\" value=\"1\" onkeypress=\"return acceptNum(event)\" onkeydown=\"\" onclick=\"\" disabled > INTERFAZ";
            $html .= "          </td>\n";
            $html .= "        </tr>\n";
            $html .= "      </table>\n";
            $html .= "    </form>\n";
            $html .= "    </div>\n";
            $html .= "    <div id='ResultadoBusqueda'>\n";
            $html .= "    </div>\n";
            $html.="</div>";
            /**
            *final de la ventana3
            **/
            $html .= "            <form name=\"terceros_buscador\" id=\"terceros_buscador\" action=\"".$action['crear_forma']."\" method=\"post\">\n";
            $html .= "                 <table width=\"45%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                    <tr class=\"modulo_table_list_title\">\n";
            $html .= "                       <td colspan=\"5\" align=\"center\">\n";
            $html .= "                          IDENTIFICACION DE TERCERO";
            $html .= "                       </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                    <tr class=\"modulo_list_claro\">\n";
            $html .= "                       <td align=\"center\">\n";
            $html .= "                          TIPO DE IDENTIFICACION";
            $html .= "                       </td>\n";
            $html .= "                       <td align=\"left\">\n";
            if(!empty($combo_tipos_id))
            {
                $html.= "                            <select id=\"tipo_id_tercero\" name=\"tipo_id_tercero\" class=\"select\" onchange=\"Tachar(this.value);\">";
                foreach($combo_tipos_id as $key => $datos)
                {
                    $html .= "                          <option value=\"".$datos['tipo_id_tercero']."\" title='".$datos['descripcion']."'>".$datos['descripcion']."</option>\n";
                }
                $html.= "                             </select>\n";
            }
            $html .= "                       </td>\n";
            $html .= "                       <td rowspan='2' id=\"busqueda\" name=\"busqueda\" align=\"center\">\n";
            
            $html .= "                         <input type=\"button\" class=\"input-submit\" name='btn_crear_ter_conv' id='btn_crear_ter_conv' value=\"Crear\" onclick=\"ValidarCreacion('".$path."')\" >\n";
            $html .= "                       </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                    <tr class=\"modulo_list_claro\">\n";
            $html .= "                       <td id=\"aux\" align=\"center\">\n";
            $html .= "                          NUMERO IDENTIFICACION";
            $html .= "                       </td>\n";
            $html .= "                       <td align=\"left\">\n";
            $html .= "                        <input type=\"text\" class=\"input-text\" id=\"tercero_id\" name=\"tercero_id\" maxlength=\"20\" size=\"20\" value=\"\" onkeypress=\"return acceptNum(event)\" onkeydown=\"recogerTecla(event);\" onclick=\"limpiar()\">";
            $html .= "                       </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                  </table>";
            $html .= "                  <div id='el_error' align='center'>\n";

            $html .= "                  </div>\n";
            $html .= "                        <input type=\"hidden\" class=\"input-text\" id=\"dv\" name=\"dv\" value=\"\" >";
            $html .= "                        <input type=\"hidden\" class=\"input-text\" id=\"nombre_tercero\" name=\"nombre_tercero\" value=\"\" >";
            $html .= "                        <input type=\"hidden\" class=\"input-text\" id=\"tipo_pais_id\" name=\"tipo_pais_id\" value=\"\" >";
            $html .= "                        <input type=\"hidden\" class=\"input-text\" id=\"tipo_dpto_id\" name=\"tipo_dpto_id\" value=\"\" >";
            $html .= "                        <input type=\"hidden\" class=\"input-text\" id=\"tipo_mpio_id\" name=\"tipo_mpio_id\" value=\"\" >";
            $html .= "                        <input type=\"hidden\" class=\"input-text\" id=\"direccion\" name=\"direccion\" value=\"\" >";
            $html .= "                        <input type=\"hidden\" class=\"input-text\" id=\"telefono\" name=\"telefono\" value=\"\" >";
            $html .= "                        <input type=\"hidden\" class=\"input-text\" id=\"fax\" name=\"fax\" value=\"\" >";
            $html .= "                        <input type=\"hidden\" class=\"input-text\" id=\"email\" name=\"email\" value=\"\" >";
            $html .= "                        <input type=\"hidden\" class=\"input-text\" id=\"celular\" name=\"celular\" value=\"\" >";
            $html .= "                  </form>";
            $html .= "                   <table align=\"center\" BORDER='0' width=\"60%\">\n";
            $html .= "                    <tr>\n";
            $html .= "                      <td align=\"center\">\n";
            $nuevocen = "javascript:MostrarCapa('ContenedorCent');IniciarCent('BUSCAR TERCERO');BuscarTerceroPorNombre(document.getElementById('tercero_name').value,1,0);";
            $html .= "                          <a  title=\"BUSCAR TERCERO\" class=\"label_error\" href=\"".$nuevocen."\">\n";
            $html .= "                          <sub><img src=\"".$path."/images/buscar_tercero.png\" border=\"0\" width=\"17\" height=\"17\"> BUSCAR TERCERO</sub>\n";
            $html .= "                         </a>\n";
            $html .= "                      </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                   </table>\n";

////////////////////////
// ["tipo_id_tercero"]=>
//     string(3) "NIT"
//     ["tercero_id"]=>
//     string(9) "2859729-5"
//     ["dv"]=>
//     NULL
//     ["nombre_tercero"]=>
//     string(17) "MONTEALEGRE JAIME"
//     ["tipo_pais_id"]=>
//     string(2) "CO"
//     ["tipo_dpto_id"]=>
//     string(2) "76"
//     ["tipo_mpio_id"]=>
//     string(3) "001"
//     ["direccion"]=>
//     string(15) "CL 114A # 33-48"
//     ["telefono"]=>
//     string(7) "2159599"
//     ["fax"]=>
//     string(0) ""
//     ["email"]=>
//     string(0) ""
//     ["celular"]=>
//     string(0) ""



///////////////////






// $html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
//             $html .= "                    <tr class=\"modulo_table_list_title\">\n";
//             $html .= "                       <td colspan=\"3\" align=\"center\">\n";
//             $html .= "                          BUSCAR POR RAZON SOCIAL";
//             $html .= "                       </td>\n";
//             $html .= "                    </tr>\n";
//             $html .= "                    <tr class=\"modulo_list_claro\">\n";
//             $html .= "                       <td colspan='2' align=\"left\">\n";
//             $html .= "                        <input type=\"text\" class=\"input-text\" id=\"tercero_id\" name=\"tercero_id\" maxlength=\"100\" size=\"80\" value=\"\" onkeypress=\"return acceptNum(event)\" onkeydown=\"recogerTecla(event);\" onclick=\"limpiar()\">";
//             $html .= "                      </td>\n";
//             $html .= "                       <td rowspan='2' align=\"center\">\n";
//             $html .= "                         <input type=\"button\" class=\"input-submit\" value=\"Buscar\" onclick=\"ObtenerTercerosConvenio(1,0);\">\n";
//             $html .= "                      </td>\n";
//             $html .= "                    </tr>\n";
//             $html .= "                    <tr class=\"modulo_list_claro\">\n";
//             $html .= "                       <td width='40%' align=\"center\">\n";
//             $html .= "                        <input type=\"radio\" id=\"siis\" name=\"siis\" value=\"\" onkeypress=\"return acceptNum(event)\" onkeydown=\"\" onclick=\"\"> SIIS";
//             $html .= "                      </td>\n";
//             $html .= "                       <td width='40%' align=\"center\">\n";
//             $html .= "                        <input type=\"radio\" id=\"interfaz\" name=\"interfaz\" value=\"\" onkeypress=\"return acceptNum(event)\" onkeydown=\"\" onclick=\"\"> INTERFAZ";
//             $html .= "                      </td>\n";
//             $html .= "                    </tr>\n";
//             $html .= "                   </table>\n";
            $html .= "                   <br>\n";
            $html .= "                   <div id='error_ter' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
            $html .= "                   <div id=\"lista_ter\">";
            $html .= "                   </div>\n";
            $html .= "           </form>";
            $html.="<script language=\"javaScript\">
                    var ban=0;
                    function mOvr(src,clrOver) 
                        {
                        src.style.background = clrOver;
                        }
        
                    function mOut(src,clrIn) 
                        {
                            src.style.background = clrIn;
                        }
            
                    </script>";
            
            
            
            $html .= "    <div id=\"volvercen_cos\">";
            $html .= "     <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
            $html .= "      <table align=\"center\" width=\"50%\">\n";
            $html .= "       <tr>\n";
            $html .= "        <td align=\"center\" colspan='7'>\n";
            $html .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
            $html .= "        </td>\n";
            $html .= "       </tr>\n";
            $html .= "      </table>\n";
            $html .= "     </form>";
            $html .= "    </div>";
            $html .= ThemeCerrarTabla();
            return $html;
        }


        /**
        * FUNCION QUE SIRVE PARA INDICAR QUE EL USUARIO QUE ACCEDIO AL MODULO NO TIENE PERMISO
        * @param array $action
        * @return string $html
        *
        **/
        function FormaPermisoNegado($action)
        {
            $html  = ThemeAbrirTabla('AFILIACIONES ADMINISTRACION');
            $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
            $html .= "  <tr>\n";
            $html .= "      <td>\n";
            $html .= "          <table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "              <tr>\n";
            $html .= "                  <td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">ACCESO DENEGADO</td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                      <b><label class='label_error'>ESTE USUARIO NO TIENE PERMISO PARA ACCEDER A ESTE MODULO</label></b>\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "          </table>\n";
            $html .= "      </td>\n";
            $html .= "  </tr>\n";
            $html .= "  <tr>\n";
            $html .= "      <td align=\"center\"><br>\n";
            $html .= "          <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
            $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
            $html .= "          </form>";
            $html .= "      </td>";
            $html .= "  </tr>";
            $html .= "</table>";
            $html .= ThemeCerrarTabla();            
            return $html;
        }
	}
?>