<?php
    /**
    * @package IPSOFT-SIIS
    * @version $Id: InsetarDatosTerceroConvenioHTML.class.php,v 1.7 2008/06/13 19:38:53 jgomez Exp $ 
    * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
    * @author JAIME GOMEZ
    */
    
    /**
    * Clase Vista: InsetarDatosTerceroConvenioHTML - Administracion del modulo de UV_Afiliaciones
    * Clase que contiene el formulario para ingresar los datos de un nuevo tercero convenio
    * @package IPSOFT-SIIS
    * @version $Revision: 1.7 $
    * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
    * @author Jaime Gomez
    */    

	class  InsetarDatosTerceroConvenioHTML
	{
		/**
		* Constructor de la clase
		*/
		function InsetarDatosTerceroConvenioHTML(){}
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action vector que continen los link de la aplicacion
        * @param array $combo_tipos_id vector que continen los link de la aplicacion
        * @param array $paises 
        * @param array $datos_pais 
        * @return String
    */           
		function FormaInsertarDatos($action,$combo_tipos_id,$paises,$datos_pais)
		{
            
            $path = SessionGetVar("rutaImagenes");
            $url = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$datos_pais['DefaultPais']."&dept=".$datos_pais['DefaultDpto']."&mpio=".$datos_pais['DefaultMpio']."&forma=fin_crear_tercero ";
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
            $javaC1 .= "  function llamarLocalizacion()\n";
            $javaC1 .= "  {\n";
            $javaC1 .= "    window.open('".$url."','localidad','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); \n";
            $javaC1 .= "  }\n";

            $javaC1.= "</script>\n";
            $html.= $javaC1;
     
            $html .= "            <form name=\"fin_crear_tercero\" id=\"fin_crear_tercero\" action=\"#\" method=\"post\">\n";
            $html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                    <tr class=\"modulo_table_list_title\">\n";
            $html .= "                       <td colspan=\"2\" align=\"center\">\n";
            $html .= "                          IDENTIFICACION DE TERCERO";
            $html .= "                       </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                    <tr class=\"modulo_list_claro\">\n";
            $html .= "                       <td width='30%' class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                          TIPO DE IDENTIFICACION";
            $html .= "                       </td>\n";
            $html .= "                       <td align=\"left\">\n";

            
            if(!empty($combo_tipos_id))
            {
                $html.= "                            <select id=\"tipo_id_tercero\" name=\"tipo_id_tercero\" class=\"select\" onchange=\"\" OnFocus=\"this.blur()\">";
                foreach($combo_tipos_id as $key => $datos)
                {
//                     if($_REQUEST["tipo_id_tercero"]==$datos['tipo_id_tercero'])
//                     {
//                         $html .= "                          <option value=\"".$datos['tipo_id_tercero']."\" title='".$datos['descripcion']."'>".$datos['descripcion']."</option>\n";
//                     }
                    
                    $html .= "                          <option value=\"".$datos['tipo_id_tercero']."\" title='".$datos['descripcion']."'>".$datos['descripcion']."</option>\n";
                }
                $html.= "                             </select>\n";
            }
            $html .= "                       </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                    <tr class=\"modulo_list_claro\">\n";
            $html .= "                       <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                          NUMERO IDENTIFICACION";
            $html .= "                       </td>\n";
            $html .= "                       <td align=\"left\">\n";
            $html .= "                        <input type=\"text\" class=\"input-text\" id=\"tercero_id\" name=\"tercero_id\" maxlength=\"20\" size=\"20\" value=\"".$_REQUEST['tercero_id']."\" onkeypress=\"return acceptNum(event)\" onkeydown=\"recogerTecla(event);\" OnFocus=\"this.blur()\">";
            $html .= "                       </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                    <tr class=\"modulo_list_claro\">\n";
            $html .= "                       <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                          NOMBRE";
            $html .= "                       </td>\n";
            $html .= "                       <td align=\"left\">\n";
            $html .= "                        <input type=\"text\" class=\"input-text\" id=\"nombre\" name=\"nombre\" maxlength=\"60\" size=\"60\" value=\"".$_REQUEST['nombre_tercero']."\" onkeypress=\"\" onclick=\"\" OnFocus=\"this.blur()\" >";
            $html .= "                       </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                             <tr class=\"modulo_list_claro\">\n";
            $html .= "                               <td class=\"modulo_table_list_title\">UBICACION</td>\n";
            $html .= "                               <td>\n";
            //$html .= "                                 <a title=\"ADICIONAR O CAMBIAR DEPARTAMENTO\" href=\"javascript:llamarLocalizacion()\"\">\n";
            //$html .= "                                   <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"-1\" width=\"16\" height=\"16\">\n";
            //$html .= "                                 </a>\n";
            $html .= "                                 <label id=\"ubicacion\">".$paises['departamento_municipio']."</label>\n";
            $html .= "                                 <input type=\"hidden\" name=\"pais\" value=\"".$_REQUEST['tipo_pais_id']."\">\n";
            $html .= "                                 <input type=\"hidden\" name=\"dpto\" value=\"".$_REQUEST['tipo_dpto_id']."\">\n";
            $html .= "                                 <input type=\"hidden\" name=\"mpio\" value=\"".$_REQUEST['tipo_mpio_id']."\">\n";
            $html .= "                               </td>\n";
            $html .= "                              </tr>\n";   

            $html .= "                    <tr class=\"modulo_list_claro\">\n";
            $html .= "                       <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                          DIRECCION";
            $html .= "                       </td>\n";
            $html .= "                       <td align=\"left\">\n";
            $html .= "                        <input type=\"text\" class=\"input-text\" id=\"direccion\" name=\"direccion\" maxlength=\"60\" size=\"60\" value=\"".$_REQUEST['direccion']."\" onkeypress=\"\" onkeydown=\"\" onclick=\"\" OnFocus=\"this.blur()\">";
            $html .= "                       </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                    <tr class=\"modulo_list_claro\">\n";
            $html .= "                       <td class=\"modulo_table_list_title\"  align=\"center\">\n";
            $html .= "                          TELEFONO";
            $html .= "                       </td>\n";
            $html .= "                       <td align=\"left\">\n";
            $html .= "                        <input type=\"text\" class=\"input-text\" id=\"telefono\" name=\"telefono\" maxlength=\"40\" size=\"40\" value=\"".$_REQUEST['telefono']."\" onkeypress=\"return acceptNum(event)\" OnFocus=\"this.blur()\">";
            $html .= "                       </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                    <tr class=\"modulo_list_claro\">\n";
            $html .= "                       <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                          FAX";
            $html .= "                       </td>\n";
            $html .= "                       <td align=\"left\">\n";
            $html .= "                        <input type=\"text\" class=\"input-text\" id=\"fax\" name=\"fax\" maxlength=\"40\" size=\"40\" value=\"".$_REQUEST['fax']."\" onkeypress=\"return acceptNum(event)\" OnFocus=\"this.blur()\">";
            $html .= "                       </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                    <tr class=\"modulo_list_claro\">\n";
            $html .= "                       <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                          E-MAIL";
            $html .= "                       </td>\n";
            $html .= "                       <td align=\"left\">\n";
            $html .= "                        <input type=\"text\" class=\"input-text\" id=\"email\" name=\"email\" maxlength=\"40\" size=\"40\" value=\"".$_REQUEST['email']."\" OnFocus=\"this.blur()\">";
            $html .= "                       </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                    <tr class=\"modulo_list_claro\">\n";
            $html .= "                       <td class=\"modulo_table_list_title\" align=\"center\">\n";
            $html .= "                          CELULAR";
            $html .= "                       </td>\n";
            $html .= "                       <td align=\"left\">\n";
            $html .= "                        <input type=\"text\" class=\"input-text\" id=\"celular\" name=\"celular\" maxlength=\"40\" size=\"40\" value=\"".$_REQUEST['celular']."\" onkeypress=\"return acceptNum(event)\" OnFocus=\"this.blur()\">";
            $html .= "                       </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                    <tr class=\"modulo_list_claro\">\n";
            $html .= "                       <td colspan='2' align=\"center\">\n";
            $html .= "                         <input type=\"hidden\" id=\"dv\" name=\"dv\" value=\"".$_REQUEST['dv']."\">";
            $html .= "                         <input type=\"button\" class=\"input-submit\" id='btn_crear_bd' name='btn_crear_bd' value=\"Crear Entidad Convenio\" onclick=\"xajax_CrearConvUsu(xajax.getFormValues('fin_crear_tercero'));\">\n";
            $html .= "                      </td>\n";
            $html .= "                    </tr>\n";
            $html .= "                  </table>";
            $html .= "                  </form>";
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

                        seleccionarTipo1_id('".$_REQUEST['tipo_id_tercero']."');
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