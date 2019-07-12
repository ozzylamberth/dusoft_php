<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: UsuariosPerHTML.class.php,v 1.8 2007/11/09 14:52:45 jgomez Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */

  /**
  * Clase Vista: UsuariosPerHTML - Administracion del modulo de UV_Afiliaciones
  * Clase que la forma que lista todos los usuarios y perfiles que tiene los usuario del sistema
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.8 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Jaime Gomez
  */


	class UsuariosPerHTML
	{
		/**
		* Constructor de la clase
		*/
		function UsuariosPerHTML(){}

        /**
		* Crea un menu principal para el modulo
		*
		* @param array $action vector que continen los link de la aplicacion
        * @param array $perfiles vector que continen los perfiles de usuario
        * @return String
		*/
		function FormaListadoUsu($action,$perfiles)
		{
            $path = SessionGetVar("rutaImagenes");
            $javaC = "<script>\n";
            $javaC .= "var contenedor1=''\n";
            $javaC .= "   var titulo1=''\n";
            $javaC .= "   var hiZ = 2;\n";
            $javaC .= "   var DatosFactor = new Array();\n";
            $javaC .= "   var EnvioFactor = new Array();\n";
            $javaC .= "   function IniciarB3(tit)\n";
            $javaC .= "   {\n";
            $javaC .= "       contenedor1 = 'ContenedorB3';\n";
            $javaC .= "       titulo1 = 'tituloB3';\n";
            $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
            $javaC.= "        Capa = xGetElementById(contenedor1);\n";
            $javaC .= "       xResizeTo(Capa, 360, 160);\n";
            $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-0);\n";
            $javaC .= "       ele = xGetElementById(titulo1);\n";
            $javaC .= "       xResizeTo(ele, 340, 20);\n";
            $javaC .= "       xMoveTo(ele, 0, 0);\n";
            $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
            $javaC .= "       ele = xGetElementById('cerrarB3');\n";
            $javaC .= "       xResizeTo(ele, 20, 20);\n";
            $javaC .= "       xMoveTo(ele, 340, 0);\n";
            $javaC .= "   }\n";
            $javaC .= "   function IniciarConf(tit)\n";
            $javaC .= "   {\n";
            $javaC .= "       contenedor1 = 'ContenedorConf';\n";
            $javaC .= "       titulo1 = 'tituloConf';\n";
            $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
            $javaC.= "        Capa = xGetElementById(contenedor1);\n";
            $javaC .= "       xResizeTo(Capa, 200, 160);\n";
            $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-0);\n";
            $javaC .= "       ele = xGetElementById(titulo1);\n";
            $javaC .= "       xResizeTo(ele, 180, 20);\n";
            $javaC .= "       xMoveTo(ele, 0, 0);\n";
            $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
            $javaC .= "       ele = xGetElementById('cerrarConf');\n";
            $javaC .= "       xResizeTo(ele, 20, 20);\n";
            $javaC .= "       xMoveTo(ele, 180, 0);\n";
            $javaC .= "   }\n";
            $javaC.= "</script>\n";
            $salida.= $javaC;
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
            $salida.= $javaC1;
            $salida.="
            <script language=\"javaScript\">
            function mOvr(src,clrOver)
                        {
                        src.style.background = clrOver;
                        }
        
                        function mOut(src,clrIn)
                        {
                        src.style.background = clrIn;
                        }
            </script>";
            $html .=$salida;
            $html .= " <div id='ContenedorB3' class='d2Container' style=\"display:none;\">";
            $html .= "    <div id='tituloB3' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
            $html .= "    <div id='cerrarB3' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorB3');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
            $html .= "    <div id='errorB3' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
            $html .= "    <div id='ContenidoB3'  class='d2Content' style='z-index:10;'>\n";
            $html .= "    </div>\n";
            $html .= " </div>\n";
            $html .= " <div id='ContenedorConf' class='d2Container' style=\"display:none;\">";
            $html .= "    <div id='tituloConf' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
            $html .= "    <div id='cerrarConf' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorConf');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
            $html .= "    <div id='errorConf' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
            $html .= "    <div id='ContenidoConf'  class='d2Content' style='z-index:10;'>\n";
            $html .= "    </div>\n";
            $html .= " </div>\n";
            $html .="    <div id='refresh'>";
            $html .= ThemeAbrirTabla('AFILIACIONES ADMINISTRACION');
            $html .= "        <form name=\"busqueda_usu\" id=\"busqueda_usu\" action=\"#\" method=\"post\">";
            $html .= "			<table border=\"0\" width=\"60%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td colspan='3' align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">BUSCADOR DE USUARIOS</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr>\n";
			$html .= "					<td width='25%' class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                  TIPO <select name=\"tipo\" id=\"tipo\" class=\"select\" onchange=\"GetPerfiles(this.value);\">\n";
            $html .= "                  <option value=\"\">SELECCIONAR</option>\n";
            $html .= "                  <option value=\"usuario_id\">ID</option>\n";
            $html .= "                  <option value=\"usuario\">LOGIN</option>\n";
            $html .= "                  <option value=\"nombre\">NOMBRE</option>\n";
            $html .= "                  <option value=\"perfil\">PERFIL</option>\n";
            $html .= "                  </select>";
			$html .= "					</td>\n";
            $html .= "                  <td width='65%' id='descripcione' class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                    <div id='descrip_2' style=\"display:block;\">";
            $html .= "                     DESCRIPCION\n";
            $html .= "                     <input type=\"text\" class=\"input-text\" name=\"valor\" id=\"valor\" size=\"40\" value=\"\">\n";
            $html .= "                    </div>";
            $html .= "                    <div id='perfilix' style=\"display:none;\">";
            $html .= "                     <select name=\"perfil\" id=\"tipo_id\" class=\"select\">";
            $html .= "                       <option value=\"\">--SELECCIONAR--</option>\n";
            foreach($perfiles as $key => $valor)
            {
                $html .= "                     <option value=\"".$valor['perfil_id']."\">".$valor['descripcion_perfil']."</option>\n";
            }
            $html .= "                     </select>\n";
            $html .= "                   </div>";
            $html .= "                  </td>\n";
            $html .= "                  <td width='10%' class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                     <input type=\"button\" class=\"input-submit\" name=\"buscar_usu\" id=\"buscar_usu\" value=\"BUSCAR\" onclick=\"xajax_BuscarUsu(xajax.getFormValues('busqueda_usu'),1,0);\">\n";
            $html .= "                  </td>\n";
            $html .= "				</tr>\n";
            $html .= "			</table>\n";
            $html .= "        </form>";
            
            $html .= "            <table width=\"60%\" align=\"center\">\n";
            $html .= "               <tr>\n";
            $html .= "                 <td COLSPAN='6' class=\"normal_10AN\" align=\"center\">\n";
            $nuevousu = $action['registrar'];
            $html .= "                   <a title='ELIMINAR USUARIO' href=\"".$nuevousu."\">";
            $html .= "                     <sub><img src=\"".$path."/images/activo.gif\" border=\"0\" width=\"14\" height=\"14\"> ADICIONAR NUEVO USUARIO</sub>\n";
            $html .= "                   </a>\n";
            $html .= "                 </td>\n";
            $html .= "               </tr>\n";
            $html .= "            </table>\n";    
            $html .="    <div id='error_usuarios2' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
            
            $html .= "<table width='100%' align='center'>";
            $html .= "  <div id='resultado_usuarios'>";
            $html .= "  </div>";
            $html .= "</table>";
            

            $html .= "<table width='100%' align='center'>";

            $html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";

            $html.="
            <script language=\"javaScript\">
              xajax_BuscarUsu(xajax.getFormValues('busqueda_usu'),1,0);
            </script>";

            $html .= ThemeCerrarTabla();			
			return $html;
		}


        /**
        * FUNCION QUE SIRVE PARA INDICAR QUE EL USUARIO QUE ACCEDIO AL MODULO NO TIENE PERMISO
        * @param array $action trae los link para volver a la anterior funcion
        * @return string $html con la forma del buscador
        **/
        function FormaListadoSystemUsu($action)
        {

            $javaC = "<script>\n";
            $javaC .= "var contenedor1=''\n";
            $javaC .= "   var titulo1=''\n";
            $javaC .= "   var hiZ = 2;\n";
            $javaC .= "   var DatosFactor = new Array();\n";
            $javaC .= "   var EnvioFactor = new Array();\n";
            $javaC .= "   function IniciarB3(tit)\n";
            $javaC .= "   {\n";
            $javaC .= "       contenedor1 = 'ContenedorB3';\n";
            $javaC .= "       titulo1 = 'tituloB3';\n";
            $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
            $javaC.= "        Capa = xGetElementById(contenedor1);\n";
            $javaC .= "       xResizeTo(Capa, 360, 160);\n";
            $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-0);\n";
            $javaC .= "       ele = xGetElementById(titulo1);\n";
            $javaC .= "       xResizeTo(ele, 340, 20);\n";
            $javaC .= "       xMoveTo(ele, 0, 0);\n";
            $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
            $javaC .= "       ele = xGetElementById('cerrarB3');\n";
            $javaC .= "       xResizeTo(ele, 20, 20);\n";
            $javaC .= "       xMoveTo(ele, 340, 0);\n";
            $javaC .= "   }\n";
            $javaC.= "</script>\n";
            $salida.= $javaC;
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
            $salida.= $javaC1;
            $salida.="
            <script language=\"javaScript\">
            function mOvr(src,clrOver)
                        {
                        src.style.background = clrOver;
                        }
        
                        function mOut(src,clrIn)
                        {
                        src.style.background = clrIn;
                        }
            </script>";
            $html .=$salida;
            $html .= " <div id='ContenedorB3' class='d2Container' style=\"display:none;\">";
            $html .= "    <div id='tituloB3' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
            $html .= "    <div id='cerrarB3' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorB3');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
            $html .= "    <div id='errorB3' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
            $html .= "    <div id='ContenidoB3'  class='d2Content' style='z-index:10;'>\n";
            $html .= "    </div>\n";
            $html .= " </div>\n";

            $html .="    <div id='refresh'>";
            $html .= ThemeAbrirTabla('AFILIACIONES ADMINISTRACION');
            $html .= "        <form name=\"busqueda_usu_sys\" id=\"busqueda_usu_sys\" action=\"#\" method=\"post\">";
            $html .= "          <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "              <tr>\n";
            $html .= "                  <td colspan='3' align=\"center\" class=\"modulo_table_list_title\" ><b style=\"color:#ffffff\">BUSCADOR DE USUARIOS NO PRESENTES EN EL SISTEMA EPS</td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr>\n";
            $html .= "                  <td width='25%' class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                  TIPO <select name=\"tipo\" id=\"tipo\" class=\"select\" onchange=\"GetPerfiles1(this.value);\">\n";
            $html .= "                         <option value=\"\">SELECCIONAR</option>\n";
            $html .= "                         <option value=\"usuario_id\">ID</option>\n";
            $html .= "                         <option value=\"usuario\">LOGIN</option>\n";
            $html .= "                         <option value=\"nombre\">NOMBRE</option>\n";
            $html .= "                       </select>";
            $html .= "                  </td>\n";
            $html .= "                  <td width='65%' id='descripcione' class=\"modulo_list_claro\" align=\"left\">\n";
            $html .= "                    <div id='descrip_2' style=\"display:block;\">";
            $html .= "                     DESCRIPCION\n";
            $html .= "                     <input type=\"text\" class=\"input-text\" name=\"valor\" id=\"valor\" size=\"40\" value=\"\" disabled>\n";
            $html .= "                    </div>";
            $html .= "                    <div id='perfilix' style=\"display:none;\">";
            $html .= "                     <select name=\"perfil\" id=\"tipo_id\" class=\"select\">";
            $html .= "                       <option value=\"\">--SELECCIONAR--</option>\n";
            foreach($perfiles as $key => $valor)
            {
                $html .= "                     <option value=\"".$valor['perfil_id']."\">".$valor['descripcion_perfil']."</option>\n";
            }
            $html .= "                     </select>\n";
            $html .= "                   </div>";
            $html .= "                  </td>\n";
            $html .= "                  <td width='10%' class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                     <input type=\"button\" class=\"input-submit\" name=\"buscar_usu\" id=\"buscar_usu\" value=\"BUSCAR\" onclick=\"xajax_BuscarUsuSys(xajax.getFormValues('busqueda_usu_sys'),1,0);\">\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "          </table>\n";
            $html .= "        </form>";
            $html .= "<br>";
            $html .="    <div id='error_usuarios2' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
            
            $html .= "<table width='100%' align='center'>";
            $html .= "  <div id='resultado_usuarios_sys'>";
            $html .= "  </div>";
            $html .= "</table>";
            
            $html .= "<br>";

            $html .= "<table width='100%' align='center'>";

            $html .= "  <tr>\n";
            $html .= "      <td align=\"center\"><br>\n";
            $html .= "          <form name=\"form_volver\" action=\"".$action['volver']."\" method=\"post\">";
            $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
            $html .= "          </form>";
            $html .= "      </td>";
            $html .= "  </tr>";
            $html .= "</table>";


            $html.="
            <script language=\"javaScript\">
              xajax_BuscarUsuSys(xajax.getFormValues('busqueda_usu_sys'),1,0);
            </script>";
            $html .= ThemeCerrarTabla();            
            return $html;
        }


        
        /**
        * FUNCION QUE SIRVE PARA INDICAR QUE EL USUARIO QUE ACCEDIO AL MODULO NO TIENE PERMISO
        * @param array $action
        * @return string $html
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