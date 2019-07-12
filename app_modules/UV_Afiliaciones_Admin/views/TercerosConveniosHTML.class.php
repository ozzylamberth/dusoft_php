<?php

  /**
  * @package IPSOFT-SIIS
  * @version $Id: TercerosConveniosHTML.class.php,v 1.7 2008/06/13 19:39:07 jgomez Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */

  /**
  * Clase Vista: TercerosConveniosHTML - Administracion del modulo de UV_Afiliaciones
  * Clase que contiene un buscador avanzado mas la opcion de crear entidades convenio
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.7 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Jaime Gomez
  */

    class  TercerosConveniosHTML
	{
		/**
		* Constructor de la clase
		*/
		function TercerosConveniosHTML(){}
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action vector que continen los link de la aplicacion
        * @param array $combo_tipos_id vector que continen los tipos de documentos
        * @return String
    */           
		function FormaTercerosConvenios($action,$combo_tipos_id)
		{      
            $html .= ThemeAbrirTabla("ADMINISTRADOR DE TERCEROS CONVENIOS");
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
            $javaC .= "       xResizeTo(Capa, 500, 380);\n";
            $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
            $javaC .= "       ele = xGetElementById(titulo1);\n";
            $javaC .= "       xResizeTo(ele, 480, 20);\n";
            $javaC .= "       xMoveTo(ele, 0, 0);\n";
            $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
            $javaC .= "       ele = xGetElementById('cerrarCent');\n";
            $javaC .= "       xResizeTo(ele, 20, 20);\n";
            $javaC .= "       xMoveTo(ele, 480, 0);\n";
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
            $html .= "    </div>\n";
            $html.="</div>";
            /**
            *final de la ventana3
            **/

    
            
            $html .= "            <form name=\"terceros_buscador\" id=\"terceros_buscador\" action=\"javascript:Buscar_Proveedores();\" method=\"post\">\n";
            $html .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                    <tr class=\"modulo_table_list_title\">\n";
            $html .= "                       <td colspan=\"5\" align=\"center\">\n";
            $html .= "                          BUSCADOR DE TERCEROS";
            $html .= "                       </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                    <tr class=\"modulo_list_claro\">\n";
            $html .= "                       <td align=\"center\">\n";
            $html .= "                          TIPO DE BUSQUEDA";
            $html .= "                       </td>\n";
            $html .= "                       <td align=\"center\">\n";
            $html .= "                         <select name=\"tip_bus\" id=\"tipos_bus\" class=\"select\" onchange=\"TipodeBusqueda(this.value);\">";
            $html .= "                           <option value=\"1\" selected>IDENTIFICACION</option> \n";
            $html .= "                           <option value=\"2\">NOMBRE</option> \n";
            $html .= "                         </select>\n";
            $html .= "                       </td>\n";
            $html .= "                       <td id=\"aux\" align=\"center\">\n";
            $html .= "                          TIPO DE DOCUMENTO";
            //var_dump($combo_tipos_id);
            if(!empty($combo_tipos_id))
            {
                $html.= "                            <select id=\"tipo_id_tercero\" name=\"tipo_id_tercero\" class=\"select\" onchange=\"Tachar(this.value);\">";
                foreach($combo_tipos_id as $key => $datos)
                {
                    $html .= "                          <option value=\"".$datos['tipo_id_tercero']."\" title='".$datos['descripcion']."'>".$datos['tipo_id_tercero']."</option>\n";
                }
                $html.= "                             </select>\n";
            }
            $html .= "                             &nbsp; TERCERO ID";
            $html .= "                             <input type=\"text\" class=\"input-text\" id=\"tercero_id\" name=\"tercero_id\" maxlength=\"20\" size=\"20\" value=\"\" onkeypress=\"return acceptNum(event)\" onkeydown=\"recogerTecla(event);\" onclick=\"limpiar()\">";
//             $html .= "                             &nbsp; - &nbsp;";
//             $html .= "                             <input type=\"text\" class=\"input-text\" id=\"dv\" name=\"dv\" maxlength=\"1\" size=\"1\" value=\"\" onkeypress=\"return acceptNum(event)\" onkeydown=\"recogerTecla(event);\" onclick=\"limpiar()\">";
            $html .= "                       </td>\n";
            $html .= "                       <td align=\"center\">\n";
            $html .= "                         ESTADO <select name=\"sw_estado\" id=\"sw_estado\" class=\"select\" onchange=\"\">";
            $html .= "                           <option value=\"\" selected>------</option> \n";
            $html .= "                           <option value=\"1\" >ACTIVO</option> \n";
            $html .= "                           <option value=\"0\">INACTIVO</option> \n";
            $html .= "                         </select>\n";
            $html .= "                       </td>\n";
            $html .= "                       <td id=\"busqueda\" align=\"center\">\n";
            $html .= "                         <input type=\"button\" class=\"input-submit\" value=\"BUSCAR\" onclick=\"ObtenerTercerosConvenio(1,0);\">\n";
            $html .= "                       </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                  </table>";
            $html .= "                  </form>";
            $html .= "                  <br>\n";
            $html .= "                   <table align=\"center\" BORDER='0' width=\"60%\">\n";
            $html .= "                    <tr>\n";
            $html .= "                      <td align=\"center\">\n";
            //$nuevocen = "javascript:MostrarCapa('ContenedorCent');CrearTercero();IniciarCent('CREAR NUEVO TERCERO');";
            $html .= "                          <a  title=\"CREAR NUEVO TERCERO\" class=\"label_error\" href=\"".$action['crear']."\">CREAR NUEVO TERCERO</a>\n";
            $html .= "                      </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                   </table>\n";
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

                        ObtenerTercerosConvenio(1,0);
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