<?php

/**
 * $Id: app_Inventarios_userclasses_HTML.php,v 1.10 2008/06/26 19:22:07 cahenao Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Inventario del Sistema
 */

/**
 * Contiene los metodos visuales para realizar la administracion de los Inventario de la clinica
 */
class app_Inventarios_userclasses_HTML extends app_Inventarios_user {

    /**
     * Constructor de la clase app_Inventarios_user_HTML
     * El constructor de la clase app_Inventarios_user_HTML se encarga de llamar
     * a la clase app_Inventarios_user que se encarga del tratamiento
     * de la base de datos.
     */
    function app_Inventarios_user_HTML()
    {
        $this->salida = '';
        $this->app_Inventarios_user();
        return true;
    }

    //=====================================================================================
    /**
     * Function que muestra las diferentes opciones del menu
     * @return boolean
     * @param string codigo de la empresa en la que esta trabajando el usuario
     * @param string nombre de la empresa en la que esta trabajando el usuario
     */
    function MenuInventariosPrincipal()
    {
        $actionA = ModuloGetURL('app', 'Inventarios', 'user', 'ProductosNoExisEmpresas', array("Empresa" => $_REQUEST['Empresa'], "NombreEmp" => $_REQUEST['NombreEmp']));
        $actionB = ModuloGetURL('app', 'Inventarios', 'user', 'LlamaFormaTiposBusqueda', array("Empresa" => $_REQUEST['Empresa'], "NombreEmp" => $_REQUEST['NombreEmp']));
        $actionC = ModuloGetURL('app', 'Inventarios', 'user', 'LlamaSeleccionCentroUtilidad', array("Empresa" => $_REQUEST['Empresa'], "NombreEmp" => $_REQUEST['NombreEmp']));
        $actionD = ModuloGetURL('app', 'Inventarios', 'user', 'llamarFormaReportes', array("Empresa" => $_REQUEST['Empresa'], "NombreEmp" => $_REQUEST['NombreEmp'], "Nit" => $_REQUEST['Nit'], "Direccion" => $_REQUEST['Dir'], "Telefono" => $_REQUEST['Tel']));

        $this->salida .= ThemeAbrirTabla('MENU INVENTARIO GENERAL');
        $this->salida .= "			<br>";
        $actionMenu = ModuloGetURL('system', 'Menu');
        $this->salida .= "    <form name=\"forma\" action=\"$actionMenu\" method=\"post\">";
        $this->salida .= "			      <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "			<tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
        $this->salida .= "			<tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$actionA\" class=\"link\"><b>INSERTAR PRODUCTOS INVENTARIO EMPRESA</b></a></td></tr>";
        $this->salida .= "			<tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$actionB\" class=\"link\"><b>CONSULTA PRODUCTOS INVENTARIO EMPRESA</b></a></td></tr>";
        $this->salida .= "			<tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$actionC\" class=\"link\"><b>BODEGAS</b></a></td></tr>";
        $this->salida .= "			<tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$actionD\" class=\"link\"><b>CONSULTA VENCIMIENTOS PRODUCTOS</b></a></td></tr>";
        $this->salida .= "			     </table><BR>";
        $this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\">";
        $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"MENU\"></td></tr>";
        $this->salida .= "    </table>";
        $this->salida .= "		</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //=======================================================================================
    /**
     * Function que muestra la ventana en la que el usuario puede elegir la empresa en la que va a trabajar
     * @return boolean
     */
    function FrmLogueoBodega()
    {
        $this->salida .= themeAbrirTabla("SELECCION DE EMRESA");
        $this->salida .= "	 <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "   <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "			<td>EMPRESA</td>\n";
        $this->salida .= "		</tr>\n";
        $Empresas = $this->LogueoBodega();

        if (sizeof($Empresas) > 0)
        {
            $y = 0;
            for ($i = 0; $i < sizeof($Empresas); $i++)
            {
                if ($y % 2)
                    $estilo = 'modulo_list_claro';
                else
                    $estilo = 'modulo_list_oscuro';

                $this->salida .= "	 <tr>\n";
                $action = ModuloGetURL('app', 'Inventarios', 'user', 'MenuInventariosPrincipal', array("Empresa" => $Empresas[$i]['empresa_id'], "NombreEmp" => $Empresas[$i]['razon_social'], "Dir" => $Empresas[$i]['direccion'], "Tel" => $Empresas[$i]['telefonos'], "Nit" => $Empresas[$i]['id']));
                $this->salida .= "				       <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$action\" class=\"link\"><b>" . $Empresas[$i]['razon_social'] . "</b></a></td>";
                $this->salida .= "	 </tr>\n";
                $y++;
            }
            $this->salida .= "	</table>\n";
        }
        else
        {
            $mensaje = "EL USUARIO NO TIENE PERMISOS PARA ACCESAR A UN INVENTARIO.";
            $titulo = "INVENTARIO GENERAL";
            $boton = ""; //REGRESAR
            $accion = ModuloGetURL('app', 'Inventarios', 'user', 'MenuInventariosPrincipal');
            $this->FormaMensaje($mensaje, $titulo, $accion, $boton);
            return true;
        }
        $this->salida .= themeCerrarTabla();
        return true;
    }

    //=====================================================================================
    /**
     * La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
     * @return boolean
     * @param string mensaje a retornar para el usuario
     * @param string titulo de la ventana a mostrar
     * @param string lugar a donde debe retornar la ventana
     * @param boolean tipo boton de la ventana
     */
    function FormaMensaje($mensaje, $titulo, $accion, $boton)
    {
        $this->salida .= ThemeAbrirTabla($titulo);
        $this->salida .= "			      <table class=\"normal_10\" width=\"60%\" align=\"center\">";
        $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "				       <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
        if ($boton)
        {
            $this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
        }
        else
        {
            $this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
        }
        $this->salida .= "			     </form>";
        $this->salida .= "			     </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //=====================================================================================
    function FormaTiposBusqueda($Empresa, $NombreEmp, $action, $centinela, $palabra)
    {
        if (empty($palabra))
        {
            $palabra = 'GENERAL';
        }
        $this->salida = ThemeAbrirTabla('BUSQUEDA DE PRODUCTOS EN EL INVENTARIO  ' . $palabra);
        $this->salida .="<SCRIPT>";
        $this->salida .="function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
        $this->salida .=" f=frm;\n";
        $this->salida .=" var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
        $this->salida .=" var ban=1;";
        $this->salida .=" var url2 = url+'?bandera='+ban;";
        $this->salida .=" var rems = window.open(url2, nombre, str);\n";
        $this->salida .=" if (rems != null) {\n";
        $this->salida .="   if (rems.opener == null) {\n";
        $this->salida .="	    rems.opener = self;\n";
        $this->salida .="   }\n";
        $this->salida .=" }\n";
        $this->salida .="}\n";
        $this->salida .="</SCRIPT>";
        $this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
        if (!empty($NombreEmp) && !empty($Empresa))
        {
            $this->salida .= "	    <table cellspacing=\"2\"  cellpadding=\"3\" border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "        <tr class=\"modulo_table_list_title\" align=\"center\">";
            $this->salida .= "			  <td>EMPRESA</td>\n";
            $this->salida .= "        </tr>";
            $this->salida .= "        <tr class=\"modulo_list_claro\" align=\"center\">";
            $this->salida .= "			  <td><b>$NombreEmp</b></td>\n";
            $this->salida .= "      </tr>";
            $this->salida .= "			 </table><BR><BR>";
            $this->salida .= "         <input type=\"hidden\" name=\"Empresa\" value=\"$Empresa\" class=\"input-text\">";
            $this->salida .= "         <input type=\"hidden\" name=\"NombreEmp\" value=\"$NombreEmp\" class=\"input-text\">";
        }
        $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"50%\" align=\"center\" >";
        $this->salida .= "         <tr><td align=\"center\"><fieldset><legend class=\"field\">PARAMETROS DE BUSQUEDA</legend>";
        $this->salida .= "         <table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
        $this->salida .= "         <tr><td>&nbsp&nbsp&nbsp;</td></tr>";
        $this->salida .= "	  	   <tr>";
        $this->salida .= "	  	   <td width=\"10%\" class=\"label\">GRUPO:</td>";
        $this->salida .= "	  	   <td><input type=\"text\" name=\"NomGrupo\" value=\"$NomGrupo\" size=\"30\" class=\"input-text\" readonly></td>";
        $this->salida .= "         <input type=\"hidden\" name=\"grupo\" value=\"$grupo\" class=\"input-text\">";
        $this->salida .= "	  	   </tr>";
        $this->salida .= "		     <tr>";
        $this->salida .= "	  	   <td width=\"10%\" class=\"label\">CLASE: </td>";
        $this->salida .= "	  	   <td><input type=\"text\" name=\"NomClase\"  value=\"$NomClase\" size=\"30\" class=\"input-text\" readonly></td>";
        $this->salida .= "         <input type=\"hidden\" name=\"clasePr\" value=\"$clasePr\" class=\"input-text\" >";
        $this->salida .= "		     </tr>";
        $this->salida .= "		     <tr>";
        $this->salida .= "	  	   <td width=\"10%\" class=\"label\">SUBCLASE: </td>";
        $this->salida .= "	  	   <td><input type=\"text\" name=\"NomSubClase\"  value=\"$NomSubClase\" size=\"30\" class=\"input-text\" readonly>";
        $this->salida .= "         <input type=\"hidden\" name=\"subclase\" value=\"$subclase\" class=\"input-text\" >";
        $ruta = 'app_modules/Inventarios/ventanaClasificacion.php';
        $this->salida .= "         <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form)\"></td>";
        $this->salida .= "		     </tr>";
        $this->salida .= "         <tr><td>&nbsp&nbsp&nbsp;</td></tr>";
        $this->salida .= "		     </table></fieldset></td></tr>";
        $this->salida .= "		     <tr><td align=\"center\">&nbsp;</td></tr>";
        $this->salida .= "		     <tr><td align=\"center\">";
        $this->salida .= "		     <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\">";
        if ($centinela != 1)
        {
            $this->salida .= "		     <input class=\"input-submit\" type=\"submit\" name=\"CrearProducto\" value=\"CREAR PRODUCTO INVENTARIO\">";
        }
        $this->salida .= "	 		   <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"BUSCAR\"></td>";
        $this->salida .= "		     </tr>";
        $this->salida .= "			 </table>";
        $this->salida .="        </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //=====================================================================================
    function FormaMostrarPrInvnoEmp($Empresa, $NombreEmp, $grupo, $clasePr, $subclase, $NomGrupo, $NomClase, $NomSubClase, $codigoPro, $descripcionPro, $codigoProAlterno)
    {

        $this->salida .= ThemeAbrirTabla('PRODUCTOS QUE NO EXISTEN EN EL INVENTARIO DE LA EMPRESA');
        $this->salida .= "<SCRIPT>";
        $this->salida .="function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
        $this->salida .=" f=frm;\n";
        $this->salida .=" var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
        $this->salida .=" var ban=1;";
        $this->salida .=" var url2 = url+'?bandera='+ban;";
        $this->salida .=" var rems = window.open(url2, nombre, str);\n";
        $this->salida .=" if (rems != null) {\n";
        $this->salida .="   if (rems.opener == null) {\n";
        $this->salida .="	    rems.opener = self;\n";
        $this->salida .="   }\n";
        $this->salida .=" }\n";
        $this->salida .="}\n";
        $this->salida .= "function chequeoTotal(frm,x){";
        $this->salida .= "  if(x==true){";
        $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
        $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
        $this->salida .= "        frm.elements[i].checked=true";
        $this->salida .= "      }";
        $this->salida .= "    }";
        $this->salida .= "  }else{";
        $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
        $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
        $this->salida .= "        frm.elements[i].checked=false";
        $this->salida .= "      }";
        $this->salida .= "    }";
        $this->salida .= "  }";
        $this->salida .= "}";
        $this->salida .= "</SCRIPT>";
        $action = ModuloGetURL('app', 'Inventarios', 'user', 'RealizarInseryPrEmpresa', array("conteo" => $this->conteo, "Of" => $_REQUEST['Of'], "paso" => $_REQUEST['paso']));
        $this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->salida .= "	    <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "        <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "			  <td>EMPRESA</td>\n";
        $this->salida .= "        </tr>";
        $this->salida .= "        <tr class=\"modulo_list_claro\" align=\"center\">";
        $this->salida .= "			  <td><b>$NombreEmp</b></td>\n";
        $this->salida .= "      </tr>";
        $this->salida .= "			 </table><BR>";
        $this->salida .= "         <table class=\"modulo_table_list\" border=\"0\" width=\"85%\" align=\"center\" >";
        $this->salida .= "         <tr><td colspan=\"2\" class=\"modulo_table_list_title\">FILTROS DE BUSQUEDA</td></tr>";
        $this->salida .= "         <tr><td class=\"modulo_list_claro\" width=\"60%\">";
        $this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
        $this->salida .= "	  	   <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "	  	   <td width=\"10%\" class=\"label\">GRUPO:</td>";
        $this->salida .= "	  	   <td><input type=\"text\" name=\"NomGrupo\" value=\"$NomGrupo\" size=\"30\" class=\"input-text\" readonly></td>";
        $this->salida .= "         <input type=\"hidden\" name=\"grupo\" value=\"$grupo\" class=\"input-text\">";
        $this->salida .= "	  	   </tr>";
        $this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "	  	   <td width=\"10%\" class=\"label\">CLASE: </td>";
        $this->salida .= "	  	   <td><input type=\"text\" name=\"NomClase\"  value=\"$NomClase\" size=\"30\" class=\"input-text\" readonly></td>";
        $this->salida .= "         <input type=\"hidden\" name=\"clasePr\" value=\"$clasePr\" class=\"input-text\" >";
        $this->salida .= "		     </tr>";
        $this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "	  	   <td width=\"10%\" class=\"label\">SUBCLASE: </td>";
        $this->salida .= "	  	   <td><input type=\"text\" name=\"NomSubClase\"  value=\"$NomSubClase\" size=\"30\" class=\"input-text\" readonly>";
        $this->salida .= "         <input type=\"hidden\" name=\"subclase\" value=\"$subclase\" class=\"input-text\" >";
        $ruta = 'app_modules/Inventarios/ventanaClasificacion.php';
        $this->salida .= "         <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form)\"></td>";
        $this->salida .= "		     </tr>";
        $this->salida .= "		     </table><BR><BR>";
        $this->salida .= "		     </td>";
        $this->salida .= "		     <td valign=\"top\" class=\"modulo_list_claro\" width=\"40%\"> ";
        $this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
        $this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">CODIGO</td><td><input type=\"text\" class=\"input-text\" name=\"codigoPro\" value=\"$codigoPro\"></td></tr>";
        $this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">CODIGO ALTERNO</td><td><input type=\"text\" class=\"input-text\" name=\"codigoProAlterno\" value=\"$codigoProAlterno\"></td></tr>";
        $this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">DESCRIPCION</td><td><input type=\"text\" class=\"input-text\" name=\"descripcionPro\" value=\"$descripcionPro\"></td></tr>";
        $this->salida .= "		     </table><BR>";
        $this->salida .= "         </td></tr>";
        $this->salida .= "         <tr><td colspan=\"2\" align=\"center\" class=\"modulo_list_claro\"><input type=\"submit\" class=\"input-submit\" value=\"FILTRAR\" name=\"buscar\"></td></tr>";
        $this->salida .= "		     </table><BR>";
        $this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"85%\" align=\"center\">";
        $this->salida .= "       <tr><td>&nbsp&nbsp;</td></tr>";
        $this->salida .= "		    <input type=\"hidden\" name=\"Empresa\" value=\"$Empresa\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"NombreEmp\" value=\"$NombreEmp\" >";
        $this->salida .= "       </td></tr>";
        $this->salida .= "			 </table>";
        $TotalInventario = $this->TotalInventarioProductosInvnoEmp($Empresa, $grupo, $clasePr, $subclase, $codigoPro, $descripcionPro, $codigoProAlterno);
        if ($TotalInventario)
        {
            $this->salida .= "			 <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\" align=\"center\">";
            $this->salida .= "			  <td>CODIGO</td>";
            $this->salida .= "        <td>DESCRIPCION</td>";
            $this->salida .= "        <td>INSERTAR</td>";
            //$this->salida .= "       <td align=\"center\" width=\"5%\"><input type=\"checkbox\" name=\"checkTotal\" onclick=\"chequeoTotal(this.form,this.checked)\"></td>";
            $this->salida .= "       </tr>";
            $y = 0;
            for ($i = 0; $i < sizeof($TotalInventario); $i++)
            {
                if ($y % 2)
                {
                    $estilo = 'modulo_list_claro';
                }
                else
                {
                    $estilo = 'modulo_list_oscuro';
                }
                $Pr = $TotalInventario[$i]['codigo_producto'];
                $this->salida .= "			<tr class=\"$estilo\">";
                $this->salida .= "       <td>" . $TotalInventario[$i]['codigo_producto'] . "</td>";
                $this->salida .= "				<td width=\"80%\">" . $TotalInventario[$i]['nombre'] . "</td>";
                $action = ModuloGetURL('app', 'Inventarios', 'user', 'InsertarProductoEnInventarios', array("Producto" => $Pr, "Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "grupo" => $grupo, "clasePr" => $clasePr, "subclase" => $subclase, "NomGrupo" => $NomGrupo, "NomClase" => $NomClase, "NomSubClase" => $NomSubClase, "conteo" => $this->conteo, "Of" => $_REQUEST['Of'], "paso" => $_REQUEST['paso'], "codigoPro" => $codigoPro, "descripcionPro" => $descripcionPro, "descripcion" => $TotalInventario[$i]['descripcion']));
                $this->salida .= "				<td align=\"center\"><a href=\"$action\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/pguardar.png\"></a></a></td>";
                //$this->salida .= "        <td align=\"center\"><input type=\"checkbox\" name=\"Seleccion[$Pr]\"></td>";
                $this->salida .= "      </tr>";
                $y++;
            }
            $this->salida .="          </table>";
            /* $this->salida .= "         <table class=\"normal_10N\"  border=\"0\" width=\"85%\" align=\"center\">";
              $this->salida .= "         <tr><td  align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"InsProductosTotal\" value=\"INSERTAR TOTAL PRODUCTOS\">";
              //$this->salida .= "         <input class=\"input-submit\" type=\"submit\" name=\"InsProductos\" value=\"INSERTAR PRODUCTOS\">";
              $this->salida .= "         </td></tr>";
              $this->salida .= "			   </table>"; */
            $this->salida .=$this->RetornarBarra(1);
        }
        else
        {
            $this->salida .= "         <table class=\"normal_10N\"  border=\"0\" width=\"85%\" align=\"center\">";
            $this->salida .= "         <tr><td  align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS CON ESTOS PARAMETROS DE BUSQUEDA</td></tr>";
            $this->salida .= "			   </table><BR>";
        }
        $this->salida .= "         <table class=\"normal_10N\"  border=\"0\" width=\"85%\" align=\"center\">";
        $this->salida .= "         <tr><td  align=\"center\" width=\"5%\"><BR><input class=\"input-submit\" type=\"submit\" name=\"Regresar\" value=\"VOLVER\">";
        $this->salida .= "			   </table>";
        $this->salida .="          </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //=====================================================================================
    function FormaDatosProductoEnInventarios($Empresa, $NombreEmp, $Producto, $existMinima, $existMaxima, $precioVentaAnt, $precioVenta, $precioMinimo, $precioMaximo, $venta, $servicio, $grupo, $NomGrupo, $clasePr, $NomClase, $subclase, $NomSubClase, $codigoPro, $descripcionPro, $grupoContratacion, $autorizadorCompra, $descripcion)
    {

        $this->salida .= ThemeAbrirTabla('INSERTAR PRODUCTO AL INVENTARIO EMPRESA');
        $action = ModuloGetURL('app', 'Inventarios', 'user', 'InsertarDatosPrInventarios');
        $this->salida .= "     <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->salida .= "	    <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "        <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "			  <td>EMPRESA</td>\n";
        $this->salida .= "        </tr>";
        $this->salida .= "        <tr class=\"modulo_list_claro\" align=\"center\">";
        $this->salida .= "			  <td><b>$NombreEmp</b></td>\n";
        $this->salida .= "      </tr>";
        $this->salida .= "			 </table><BR><BR>";
        $this->salida .= "     <table class=\"normal_10\"  border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "         <tr><td align=\"center\"><fieldset><legend class=\"field\"> DATOS DEL PRODUCTO </legend>";
        $this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"95%\" align=\"center\">";
        $this->salida .= "         <tr><td></td></tr>";
        $this->salida .= "         <tr class=\"modulo_list_claro\">";
        $this->salida .= "         <td width=\"15%\" class=\"label\">CODIGO</td><td width=\"15%\">$Producto</td>";
        $this->salida .= "         <td width=\"15%\" class=\"label\">DESCRIPCION</td><td>$descripcion</td>";
        $this->salida .= "         </tr>";
        $this->salida .= "         </tr>";
        $this->salida .= "         <tr><td></td></tr>";
        $this->salida .= "		     </table></fieldset></td></tr>";
        $this->salida .= "     </table><BR>";
        $this->salida .= "      <table class=\"normal_10\"  border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= "      <tr><td colspan=\"3\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "      </td></tr>";
        $this->salida .= "       <tr class=\"modulo_table_title\"><td align=\"center\">DATOS DEL PRODUCTO</td></tr>";
        $this->salida .= "       <tr><td class=\"modulo_list_oscuro\">";
        $this->salida .= "      <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"Empresa\" value=\"$Empresa\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"NombreEmp\" value=\"$NombreEmp\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"Producto\" value=\"$Producto\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"grupo\" value=\"$grupo\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"clasePr\" value=\"$clasePr\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"subclase\" value=\"$subclase\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"NomGrupo\" value=\"$NomGrupo\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"NomClase\" value=\"$NomClase\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"NomSubClase\" value=\"$NomSubClase\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"codigoPro\" value=\"$codigoPro\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"descripcionPro\" value=\"$descripcionPro\" >";
        $this->salida .= "		  <tr class=\"modulo_list_claro\" height=\"15\">";
        $this->salida .= "	  	<td class=\"" . $this->SetStyle("existMinima") . "\">EXISTENCIAS MINIMAS:</td>";
        $this->salida .= "	  	<td><input size=\"9\" type=\"text\" name=\"existMinima\" value=\"$existMinima\" class=\"input-text\">";
        $this->salida .= "	  	<td class=\"" . $this->SetStyle("existMaxima") . "\">EXISTENCIAS MAXIMAS:</td>";
        $this->salida .= "	  	<td><input size=\"9\" type=\"text\" name=\"existMaxima\" value=\"$existMaxima\" class=\"input-text\">";
        $this->salida .= "		  </tr>";
        $this->salida .= "		  <tr class=\"modulo_list_claro\" height=\"15\">";
        $this->salida .= "	  	<td class=\"" . $this->SetStyle("precioVenta") . "\">PRECIO VENTA ANTERIOR:</td>";
        $this->salida .= "	  	<td><input size=\"15\" type=\"text\" name=\"precioVentaAnt\" value=\"$precioVentaAnt\" class=\"input-text\">";
        $this->salida .= "	  	<td class=\"" . $this->SetStyle("precioVenta") . "\">PRECIO VENTA:</td>";
        $this->salida .= "	  	<td><input size=\"15\" type=\"text\" name=\"precioVenta\" value=\"$precioVenta\" class=\"input-text\">";
        $this->salida .= "		  </tr>";
        $this->salida .= "		  <tr class=\"modulo_list_claro\" height=\"15\">";
        $this->salida .= "	  	<td class=\"" . $this->SetStyle("precioMinimo") . "\">PRECIO MINIMO:</td>";
        $this->salida .= "	  	<td><input size=\"15\" type=\"text\" name=\"precioMinimo\" value=\"$precioMinimo\" class=\"input-text\">";
        $this->salida .= "	  	<td class=\"" . $this->SetStyle("precioMaximo") . "\">PRECIO MAXIMO:</td>";
        $this->salida .= "	  	<td colspan=\"3\"><input size=\"15\" type=\"text\" name=\"precioMaximo\" value=\"$precioMaximo\" class=\"input-text\"></td>";
        $this->salida .= "		  </tr>";
        if ($venta == 1)
        {
            $varCheck = 'checked';
        }
        if ($servicio == 1)
        {
            $varCheckSer = 'checked';
        }
        $this->salida .= "		  <tr class=\"modulo_list_claro\" height=\"15\">";
        $this->salida .= "	  	<td class=\"" . $this->SetStyle("venta") . "\">VENTA  ";
        $this->salida .= "	  	<input type=\"checkbox\" name=\"venta\" $varCheck>";
        $this->salida .= "	  	<td colspan=\"3\" class=\"" . $this->SetStyle("servicio") . "\">PRODUCTO SIN MANEJO DE STOCK  ";
        $this->salida .= "	  	<input type=\"checkbox\" name=\"servicio\" $varCheckSer>";
        $this->salida .= "		  </td></tr>";
        $this->salida .= "		      <tr class=\"modulo_list_claro\" height=\"20\"><td class=\"" . $this->SetStyle("grupoContratacion") . "\">GRUPO DE CONTRATACION</td><td colspan=\"3\"><select name=\"grupoContratacion\"  class=\"select\" $deshabilitado>";
        $GruposContratacion = $this->TiposDeGruposContratacion();
        $this->MostrarSin($GruposContratacion, 'False', $grupoContratacion);
        $this->salida .= "          </select></td></tr>";
        $this->salida .= "		      <tr class=\"modulo_list_claro\" height=\"20\"><td class=\"" . $this->SetStyle("autorizadorCompra") . "\">NIVEL AUTORIZACION COMPRA</td><td colspan=\"3\"><select name=\"autorizadorCompra\"  class=\"select\" $deshabilitado>";
        $AutorizacionesCompra = $this->TiposAutorizacionesCompra();
        $this->MostrarSin($AutorizacionesCompra, 'False', $autorizadorCompra);
        $this->salida .= "          </select></td></tr>";
        $this->salida .= "		  </table><BR>";
        $this->salida .= "     </td></tr>";
        $this->salida .= "     <tr><td>&nbsp;</td></tr>";
        $this->salida .= "     <tr><td  align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\">&nbsp&nbsp&nbsp;";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"INSERTAR\"></td></tr>";
        $this->salida .= "		 </table><BR>";
        $this->salida .="      </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //=====================================================================================
    /**
     * Funcion que se encarga de listar los elementos pasados por parametros
     * @return array
     * @param array codigos y valores que vienen en el arreglo
     * @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los valores del arreglo
     * @param string elemento seleccionado en el objeto donde se imprimen los valores
     */
    function MostrarSin($arreglo, $Seleccionado = 'False', $Defecto = '')
    {

        switch ($Seleccionado)
        {
            case 'False': {
                    foreach ($arreglo as $value => $titulo)
                    {
                        if ($value == $Defecto)
                        {
                            $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                        }
                        else
                        {
                            $this->salida .=" <option value=\"$value\">$titulo</option>";
                        }
                    }
                    break;
                }
            case 'True': {
                    foreach ($arreglo as $value => $titulo)
                    {
                        if ($value == $Defecto)
                        {
                            $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                        }
                        else
                        {
                            $this->salida .=" <option value=\"$value\">$titulo</option>";
                        }
                    }
                    break;
                }
        }
    }

    //=====================================================================================
    function CalcularNumeroPasos($conteo)
    {
        $numpaso = ceil($conteo / $this->limit);
        return $numpaso;
    }

    //=====================================================================================
    function CalcularBarra($paso)
    {
        $barra = floor($paso / 10) * 10;
        if (($paso % 10) == 0)
        {
            $barra = $barra - 10;
        }
        return $barra;
    }

    //=====================================================================================
    function CalcularOffset($paso)
    {
        $offset = ($paso * $this->limit) - $this->limit;
        return $offset;
    }

    //=====================================================================================
    function RetornarBarra($var)
    {
        if ($this->limit >= $this->conteo)
        {
            return '';
        }
        $paso = $_REQUEST['paso'];
        if (empty($paso))
        {
            $paso = 1;
        }
        if ($var == 1)
        {
            $accion = ModuloGetURL('app', 'Inventarios', 'user', 'ProductosNoExisEmpresas', array('conteo' => $this->conteo, 'NombreEmp' => $_REQUEST['NombreEmp'], 'Empresa' => $_REQUEST['Empresa'], 'grupo' => $_REQUEST['grupo'], 'clasePr' => $_REQUEST['clasePr'], 'subclase' => $_REQUEST['subclase'], 'NomGrupo' => $_REQUEST['NomGrupo'], 'NomClase' => $_REQUEST['NomClase'], 'NomSubClase' => $_REQUEST['NomSubClase']));
        }
        elseif ($var == 2)
        {
            $accion = ModuloGetURL('app', 'Inventarios', 'user', 'BusquedaInventarios', array('conteo' => $this->conteo, 'NombreEmp' => $_REQUEST['NombreEmp'], 'Empresa' => $_REQUEST['Empresa'], 'grupo' => $_REQUEST['grupo'], 'clasePr' => $_REQUEST['clasePr'], 'subclase' => $_REQUEST['subclase'], 'NomGrupo' => $_REQUEST['NomGrupo'], 'NomClase' => $_REQUEST['NomClase'], 'NomSubClase' => $_REQUEST['NomSubClase']));
        }
        else
        {
            $accion = ModuloGetURL('app', 'Inventarios', 'user', 'BusquedaBDProductosInventarios', array('conteo' => $this->conteo, 'grupo' => $_REQUEST['grupo'], 'clasePr' => $_REQUEST['clasePr'], 'subclase' => $_REQUEST['subclase'], 'NomGrupo' => $_REQUEST['NomGrupo'], 'NomClase' => $_REQUEST['NomClase'], 'NomSubClase' => $_REQUEST['NomSubClase'], 'descripcionPro' => $_REQUEST['descripcionPro'], 'codigoPro' => $_REQUEST['codigoPro']));
        }

        $barra = $this->CalcularBarra($paso);
        $numpasos = $this->CalcularNumeroPasos($this->conteo);
        $colspan = 1;

        $this->salida .= "<br><table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if ($paso > 1)
        {
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset(1) . "&paso=1'>&lt;</a></td>";
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso - 1) . "&paso=" . ($paso - 1) . "'>&lt;&lt;</a></td>";
            $colspan+=2;
        }
        else
        {
            // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
            //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        }
        $barra++;
        if (($barra + 10) <= $numpasos)
        {
            for ($i = ($barra); $i < ($barra + 10); $i++)
            {
                if ($paso == $i)
                {
                    $this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
                }
                else
                {
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($i) . "&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso + 1) . "&paso=" . ($paso + 1) . "' >&gt;&gt;</a></td>";
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($numpasos) . "&paso=$numpasos'>&gt;</a></td>";
            $colspan+=2;
        }
        else
        {
            $diferencia = $numpasos - 9;
            if ($diferencia <= 0)
            {
                $diferencia = 1;
            }//CAmbiar en todas
            for ($i = ($diferencia); $i <= $numpasos; $i++)
            {
                if ($paso == $i)
                {
                    $this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
                }
                else
                {
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($i) . "&paso=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if ($paso != $numpasos)
            {
                $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso + 1) . "&paso=" . ($paso + 1) . "' >&gt;&gt;</a></td>";
                $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($numpasos) . "&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
            else
            {
                //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
                //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
            }
        }
        $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan='15' align='center'>P�ina $paso de $numpasos</td><tr></table>";
    }

    //=====================================================================================
    function SetStyle($campo)
    {
        if ($this->frmError[$campo] || $campo == "MensajeError")
        {
            if ($campo == "MensajeError")
            {
                return ("<tr><td class='label_error' colspan='3' align='center'>" . $this->frmError["MensajeError"] . "</td></tr>");
            }
            return ("label_error");
        }
        return ("label");
    }

    //=====================================================================================
    /**
     * Function que muestra el listado de los productos que pertenecen a un inventario
     * @return boolean
     * @param string codigo de la empresa en la que esta trabajando el usuario
     * @param string nombre de la empresa en la que esta trabajando el usuario
     */
    function FormaListadoInventario($Empresa, $NombreEmp, $grupo, $clasePr, $subclase, $NomGrupo, $NomClase, $NomSubClase, $Seleccion, $codigoPro, $descripcionPro, $codigoProAlterno)
    {

        $this->salida .= ThemeAbrirTabla('LISTADO INVENTARIO GENERAL');
        $this->salida .= "<SCRIPT>";
        $this->salida .="function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
        $this->salida .=" f=frm;\n";
        $this->salida .=" var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
        $this->salida .=" var ban=1;";
        $this->salida .=" var url2 = url+'?bandera='+ban;";
        $this->salida .=" var rems = window.open(url2, nombre, str);\n";
        $this->salida .=" if (rems != null) {\n";
        $this->salida .="   if (rems.opener == null) {\n";
        $this->salida .="	    rems.opener = self;\n";
        $this->salida .="   }\n";
        $this->salida .=" }\n";
        $this->salida .="}\n";
        $this->salida .= "function chequeoTotal(frm,x){";
        $this->salida .= "  if(x==true){";
        $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
        $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
        $this->salida .= "        frm.elements[i].checked=true";
        $this->salida .= "      }";
        $this->salida .= "    }";
        $this->salida .= "  }else{";
        $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
        $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
        $this->salida .= "        frm.elements[i].checked=false";
        $this->salida .= "      }";
        $this->salida .= "    }";
        $this->salida .= "  }";
        $this->salida .= "}";
        $this->salida .= "</SCRIPT>";
        $action = ModuloGetURL('app', 'Inventarios', 'user', 'LlamaAccionDelProducto', array("conteo" => $this->conteo, "Of" => $_REQUEST['Of'], "paso" => $_REQUEST['paso']));
        $this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->salida .= "         <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "            <tr><td class=\"modulo_table_list_title\" align=\"center\"><b>INVENTARIO GENERAL</b></td></tr>";
        $this->salida .= "            <tr><td class=\"modulo_list_oscuro\" align=\"center\"><b>$NombreEmp</b></td></tr>";
        $this->salida .= "			 </table><BR><BR>";
        $this->salida .= "         <table class=\"modulo_table_list\" border=\"0\" width=\"85%\" align=\"center\" >";
        $this->salida .= "         <tr><td colspan=\"2\" class=\"modulo_table_list_title\">FILTROS DE BUSQUEDA</td></tr>";
        $this->salida .= "         <tr><td class=\"modulo_list_claro\" width=\"60%\">";
        $this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
        $this->salida .= "	  	   <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "	  	   <td width=\"10%\" class=\"label\">GRUPO:</td>";
        $this->salida .= "	  	   <td><input type=\"text\" name=\"NomGrupo\" value=\"$NomGrupo\" size=\"30\" class=\"input-text\" readonly></td>";
        $this->salida .= "         <input type=\"hidden\" name=\"grupo\" value=\"$grupo\" class=\"input-text\">";
        $this->salida .= "	  	   </tr>";
        $this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "	  	   <td width=\"10%\" class=\"label\">CLASE: </td>";
        $this->salida .= "	  	   <td><input type=\"text\" name=\"NomClase\"  value=\"$NomClase\" size=\"30\" class=\"input-text\" readonly></td>";
        $this->salida .= "         <input type=\"hidden\" name=\"clasePr\" value=\"$clasePr\" class=\"input-text\" >";
        $this->salida .= "		     </tr>";
        $this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "	  	   <td width=\"10%\" class=\"label\">SUBCLASE: </td>";
        $this->salida .= "	  	   <td><input type=\"text\" name=\"NomSubClase\"  value=\"$NomSubClase\" size=\"30\" class=\"input-text\" readonly>";
        $this->salida .= "         <input type=\"hidden\" name=\"subclase\" value=\"$subclase\" class=\"input-text\" >";
        $ruta = 'app_modules/Inventarios/ventanaClasificacion.php';
        $this->salida .= "         <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form)\"></td>";
        $this->salida .= "		     </tr>";
        $this->salida .= "		     </table><BR><BR>";
        $this->salida .= "		     </td>";
        $this->salida .= "		     <td valign=\"top\" class=\"modulo_list_claro\" width=\"40%\"> ";
        $this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
        $this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">CODIGO</td><td><input type=\"text\" class=\"input-text\" name=\"codigoPro\" value=\"$codigoPro\"></td></tr>";
        $this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">CODIGO ALTERNO</td><td><input type=\"text\" class=\"input-text\" name=\"codigoProAlterno\" value=\"$codigoProAlterno\"></td></tr>";
        $this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">DESCRIPCION</td><td><input type=\"text\" class=\"input-text\" name=\"descripcionPro\" value=\"$descripcionPro\"></td></tr>";
        $this->salida .= "		     </table><BR>";
        $this->salida .= "         </td></tr>";
        $this->salida .= "         <tr><td colspan=\"2\" align=\"center\" class=\"modulo_list_claro\"><input type=\"submit\" class=\"input-submit\" value=\"FILTRAR\" name=\"buscar\"></td></tr>";
        $this->salida .= "		     </table><BR>";
        $this->salida .= "       <table class=\"normal_10\"  border=\"0\" width=\"95%\" align=\"center\">";
        $this->salida .= "       <tr><td>&nbsp&nbsp;</td></tr>";
        $this->salida .= "		    <input type=\"hidden\" name=\"Empresa\" value=\"$Empresa\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"NombreEmp\" value=\"$NombreEmp\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"Seleccion\" value=\"$Seleccion\" >";
        $this->salida .= "       </td></tr>";
        $this->salida .= "			 </table>";
        $TotalInventario = $this->TotalInventario($Empresa, $grupo, $clasePr, $subclase, $codigoPro, $descripcionPro, $codigoProAlterno);
        if ($TotalInventario)
        {
            $this->salida .= "			 <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\" align=\"center\">";
            $this->salida .= "			  <td>CODIGO PRODUCTO</td>";
            $this->salida .= "        <td>DESCRIPCION</td>";
            $this->salida .= "        <td width=\"5%\">EDITAR</td>";
            $this->salida .= "        <td width=\"5%\">CONSULTA</td>";
            $this->salida .= "        <td width=\"5%\">ESTADO</td>";
            $this->salida .= "       <td align=\"center\" width=\"5%\"><input type=\"checkbox\" name=\"checkTotal\" onclick=\"chequeoTotal(this.form,this.checked)\"></td>";
            $this->salida .= "       </tr>";
            $y = 0;
            for ($i = 0; $i < sizeof($TotalInventario); $i++)
            {
                if ($y % 2)
                {
                    $estilo = 'modulo_list_claro';
                }
                else
                {
                    $estilo = 'modulo_list_oscuro';
                }
                $Pr = $TotalInventario[$i]['codigo_producto'];
                $this->salida .= "			<tr class=\"$estilo\">";
                $this->salida .= "       <td>" . $TotalInventario[$i]['codigo_producto'] . "</td>";
                $this->salida .= "				<td width=\"90%\">" . $TotalInventario[$i]['nombre'] . "</td>";
                $actionEditar = ModuloGetURL('app', 'Inventarios', 'user', 'LlamaEditarProductoEmpresa', array('Empresa' => $Empresa, 'NombreEmp' => $NombreEmp, 'grupo' => $grupo, 'clasePr' => $clasePr, 'subclase' => $subclase, 'NomGrupo' => $NomGrupo, 'NomClase' => $NomClase, 'NomSubClase' => $NomSubClase, 'Seleccion' => $Seleccion, 'codigoProducto' => $TotalInventario[$i]['codigo_producto'], "codigoPro" => $codigoPro, "descripcionPro" => $descripcionPro));
                $this->salida .= "			  <td align=\"center\"><a href=\"$actionEditar\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/editar.png\"></a></td>";
                $action1 = ModuloGetURL('app', 'Inventarios', 'user', 'LlamaVerDetalleProducto', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "grupo" => $grupo, "claseIn" => $clasePr, "subclase" => $subclase, "NomGrupo" => $NomGrupo, "NomClase" => $NomClase, "NomSubClase" => $NomSubClase, "Seleccion" => $Seleccion, "codigoProducto" => $TotalInventario[$i]['codigo_producto'], "conteo" => $this->conteo, "Of" => $_REQUEST['Of'], "paso" => $_REQUEST['paso'], "codigoPro" => $codigoPro, "descripcionPro" => $descripcionPro));
                $this->salida .= "			   <td align=\"center\"><a href=\"$action1\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/pconsultar.png\"></a></td>";
                if ($TotalInventario[$i]['estado'] == 1)
                {
                    $this->salida .= "				<td align=\"center\"><img border=\"0\" src=\"" . GetThemePath() . "/images/checksi.png\"></td>";
                }
                else
                {
                    $this->salida .= "				<td align=\"center\"><img border=\"0\" src=\"" . GetThemePath() . "/images/checkno.png\"></td>";
                }
                $accion = ModuloGetURL('app', 'Inventarios', 'user', 'BusquedaInventarios', $_REQUEST);
                $this->salida .= "        <td align=\"center\"><input type=\"checkbox\" name=\"Seleccion[$Pr]\"></td>";
                $this->salida .= "      </tr>";
                $y++;
            }
            $this->salida .="          </table>";
            $this->salida .= "         <table class=\"normal_10N\"  border=\"0\" width=\"85%\" align=\"center\">";
            //$this->salida .= "         <tr><td  align=\"left\"><input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"CREA PRODUCTO INVENTARIO\">";
            //$this->salida .= "		     <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACTUALIZACION PRECIOS\"></td>";
            $this->salida .= "         <td  align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Eliminar\" value=\"ELIMINA O CAMBIA ESTADO\"></td></tr>";
            $this->salida .= "         </td></tr>";
            $this->salida .= "			   </table>";
            $this->salida .=$this->RetornarBarra(2);
            $this->salida .= "			   <BR>";
        }
        else
        {
            $this->salida .= "         <table class=\"normal_10N\"  border=\"0\" width=\"85%\" align=\"center\">";
            $this->salida .= "         <tr><td  align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS CON ESTOS PARAMETROS DE BUSQUEDA</td></tr>";
            $this->salida .= "			   </table><BR>";
        }
        $this->salida .= "         <table class=\"normal_10N\"  border=\"0\" width=\"85%\" align=\"center\">";
        $this->salida .= "         <tr><td  align=\"center\" width=\"5%\"><BR><input class=\"input-submit\" type=\"submit\" name=\"Regresar\" value=\"VOLVER\">";
        $this->salida .= "			   </table>";
        $this->salida .="          </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //=====================================================================================
    function VerDetalleProductoInv($Empresa, $NombreEmp, $grupo, $NomGrupo, $claseIn, $NomClase, $subclase, $NomSubClase, $Seleccion, $codigoProducto, $conteo, $Of, $paso, $codigoPro, $descripcionPro)
    {

        $this->salida .= ThemeAbrirTabla('DATOS PRODUCTO INVENTARIOS');
        $action = ModuloGetURL('app', 'Inventarios', 'user', 'LlamaFormaTiposBusqueda', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "grupo" => $grupo, "NomGrupo" => $NomGrupo, "clasePr" => $claseIn, "NomClase" => $NomClase, "subclase" => $subclase, "NomSubClase" => $NomSubClase, "conteo" => $this->conteo, "Of" => $Of, "paso" => $paso, "codigoPro" => $codigoPro, "descripcionPro" => $descripcionPro));
        $this->salida .= "     <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->salida .= "          <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "          <tr><td class=\"modulo_table_list_title\" align=\"center\"><b>EMPRESA</b></td></tr>";
        $this->salida .= "          <tr><td class=\"modulo_list_claro\" align=\"center\" ><b>$NombreEmp</b></td></tr>";
        $this->salida .= "			    </table><BR>";
        $DatosProd = $this->BuscarDatosProductoInv($Empresa, $codigoProducto);
        if ($DatosProd)
        {
            $this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\" >";
            $this->salida .= "         <tr><td><fieldset><legend class=\"field\">DATOS DEL PRODUCTO</legend>";
            $this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"95%\" align=\"center\" >";
            $this->salida .= "			   <tr><td>&nbsp;</td></tr>";
            $this->salida .= "			   <tr class=\"modulo_list_claro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">CODIGO</td><td>$codigoProducto</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">DESCRIPCION</td><td width=\"30%\">" . $DatosProd['descripcion'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">DESCRIPCION. ABVDA</td><td width=\"30%\">" . $DatosProd['descripcion_abreviada'] . "</td>";
            $this->salida .= "			   </tr>";
            if (empty($grupo) || empty($claseIn) || empty($subclase))
            {
                $DatosClasify = $this->BuscarDatosClasifyProd($Empresa, $codigoProducto);
                $grupo = $DatosClasify['grupo_id'];
                $claseIn = $DatosClasify['clase_id'];
                $subclase = $DatosClasify['subclase_id'];
                $NomGrupo = $DatosClasify['desgr'];
                $NomClase = $DatosClasify['desclas'];
                $NomSubClase = $DatosClasify['dessubclas'];
            }
            $this->salida .= "			   <tr class=\"modulo_list_oscuro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">GRUPO</td><td>$grupo $NomGrupo</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">CLASE</td><td>$claseIn $NomClase</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">SUBCLASE</td><td>$subclase $NomSubClase</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr class=\"modulo_list_claro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">FABRICANTE</td><td>" . $DatosProd['fabricante'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">UNIDAD</td><td>" . $DatosProd['unidad'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">IVA</td><td>" . $DatosProd['porc_iva'] . "</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr class=\"modulo_list_oscuro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">EXIST. MINIMA</td><td>" . $DatosProd['existencia_minima'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">EXIST. MAXIMA</td><td>" . $DatosProd['existencia_maxima'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">EXISTENCIAS</td><td>" . $DatosProd['existencia'] . "</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr class=\"modulo_list_claro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">COSTO ANTERIOR</td><td>" . $DatosProd['costo_anterior'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">COSTO</td><td>" . $DatosProd['costo'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">COSTO PENULTIMA COMPRA</td><td>" . $DatosProd['costo_penultima_compra'] . "</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr class=\"modulo_list_oscuro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">COSTO ULTIMA COMPRA</td><td>" . $DatosProd['costo_ultima_compra'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">PRECIO VENTA ANTERIOR</td><td>" . $DatosProd['precio_venta_anterior'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">PRECIO VENTA</td><td>" . $DatosProd['precio_venta'] . "</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr class=\"modulo_list_claro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">PRECIO MINIMO</td><td>" . $DatosProd['precio_minimo'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">PRECIO MAXIMO</td><td>" . $DatosProd['precio_maximo'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">&nbsp;</td><td>&nbsp;</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr class=\"modulo_list_oscuro\">";
            if ($DatosProd['sw_vende'] == 1)
            {
                $Img = 'endturn.png';
            }
            else
            {
                $Img = 'delete.gif';
            }
            if ($DatosProd['sw_servicio'] == 1)
            {
                $Img1 = 'endturn.png';
            }
            else
            {
                $Img1 = 'delete.gif';
            }
            $this->salida .= "			   <td width=\"15%\" class=\"label\">PRODUCTO VENTA</td><td align=\"center\"><img border=\"0\"    src=\"" . GetThemePath() . "/images/$Img\"></td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">PRODUCTO SIN<br>MANEJO DE STOCK</td><td align=\"center\"><img border=\"0\" src=\"" . GetThemePath() . "/images/$Img1\"></td>";
            $this->salida .= "			   <td>&nbsp;</td>";
            $this->salida .= "			   <td>&nbsp;</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr class=\"modulo_list_claro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">GRUPO CONTRATACION</td><td>" . $DatosProd['grupocontratacion'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">AUTORIZADOR COMPRA</td><td>" . $DatosProd['autorizador'] . "</td>";
            $this->salida .= "			   <td>&nbsp;</td>";
            $this->salida .= "			   <td>&nbsp;</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">&nbsp;</td><td>&nbsp;</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr><td>&nbsp;</td></tr>";
            $this->salida .= "			   </table>";
            $this->salida .= "		     </fieldset></td></tr>";
            $this->salida .= "        </table><BR>";
        }
        $this->salida .= "       <table class=\"normal_10N\"  border=\"0\" width=\"90%\" align=\"center\" >";
        $this->salida .= "        <tr><td align=\"center\" class=\"input-submit\"><input class=\"input-submit\" type=\"submit\" name=\"Regresa\" value=\"VOLVER\"></td></tr>";
        $this->salida .= "       </table>";
        $this->salida .= " </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //=====================================================================================
    /**
     * Function que muestra la forma donde el usuario selecciona el centro de utilidad
     * @return boolean
     * @param string codigo de la empresa en la que esta trabajando el usuario
     * @param string nombre de la empresa en la que esta trabajando el usuario
     * @param string variable que contiene la accion a seguir
     */
    function SeleccionCentroUtilidad($Empresa, $NombreEmp, $action)
    {

        $this->salida .= ThemeAbrirTabla('SELECCION CENTRO UTILIDAD DE LA BODEGA');
        $this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->salida .= "       <BR>";
        $this->salida .= "	      <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "        <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "			    <td>EMPRESA</td>\n";
        $this->salida .= "        </tr>";
        $this->salida .= "        <tr class=\"modulo_list_claro\" align=\"center\">";
        $this->salida .= "			    <td><b>$NombreEmp</b></td>\n";
        $this->salida .= "        </tr>";
        $this->salida .= "			 </table><BR><BR>";
        $this->salida .= "       <table class=\"normal_10\"  border=\"0\" width=\"50%\" align=\"center\" >";
        $this->salida .= "      <tr><td colspan=\"3\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "      </td></tr>";
        $this->salida .= "		    <input type=\"hidden\" name=\"Empresa\" value=\"$Empresa\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"NombreEmp\" value=\"$NombreEmp\" >";
        $this->salida .= "	        <tr><td align=\"center\" class=\"" . $this->SetStyle("centroutilidad") . "\">CREACION DE BODEGA PARA EL CENTRO DE UTILIDAD: </td></tr>";
        $this->salida .= "         <tr><td>&nbsp;";
        $this->salida .= "         </td></tr>";
        $this->salida .= "	        <tr><td align=\"center\"><select name=\"centroutilidad\" class=\"select\">";
        $CentrosU = $this->CentrosUtilidad($Empresa);
        $this->Mostrar($CentrosU, 'False', $centroutilidad);
        $this->salida .= "          </select></td>";
        $this->salida .= "        </tr>";
        $this->salida .= "        <tr><td align=\"center\"><BR>";
        $this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"VOLVER\">";
        $this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CREAR\">";
        $this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"VerBodegas\" value=\"CONSULTA\"></td></tr>";
        $this->salida .= "       </table><br>";
        $this->salida .= "       </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     * Funcion que se encarga de visualizar en un OBJETO select las opciones del arreglo enviadas por parametro
     * @return array
     * @param array Arreglo que se va a visualizar en el OBJETO select
     * @param boolean que indica si anteriormente habia una opcion seleccionada
     * @param string elemento seleccionado anteriormente
     */
    function Mostrar($Arreglo, $Seleccionado = 'False', $variable = '')
    {
        switch ($Seleccionado)
        {
            case 'False': {
                    $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                    foreach ($Arreglo as $value => $titulo)
                    {
                        if ($value == $variable)
                        {
                            $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                        }
                        else
                        {
                            $this->salida .=" <option value=\"$value\">$titulo</option>";
                        }
                    }
                    break;
                }
            case 'True': {
                    foreach ($Arreglo as $value => $titulo)
                    {
                        if ($value == $variable)
                        {
                            $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                        }
                        else
                        {
                            $this->salida .=" <option value=\"$value\">$titulo</option>";
                        }
                    }
                    break;
                }
        }
    }

    function FormaCrearBodegas($Empresa, $NombreEmp, $centroutilidad, $Bodega, $descripcion, $Departamento, $Ubicacion, $descripcion, $Responsable, $bandera, $tipoNumeracion, $restitucion, $ingresocompras, $TipoDisposicion)
    {

        $this->salida .= ThemeAbrirTabla('CREACION BODEGAS');
        $action = ModuloGetURL('app', 'Inventarios', 'user', 'InsertarFormaCrearBodegas');
        $this->salida .= "      <script>";
        $this->salida .= "      function chequearTipoDisposicion(frm,valor){";
        $this->salida .= "        if(valor==true){";
        $this->salida .= "          frm.TipoDisposicion.disabled=false;";
        $this->salida .= "        }else{";
        $this->salida .= "          frm.TipoDisposicion.disabled=true;";
        $this->salida .= "        }";
        $this->salida .= "      }";
        $this->salida .= "      </script>";
        $this->salida .= "      <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "       <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "			  <td>NOMBRE EMPRESA</td>\n";
        $this->salida .= "			  <td>NOMBRE CENTRO UTILIDAD</td>\n";
        $this->salida .= "		    </tr>\n";
        $this->salida .= "       <tr class=\"modulo_list_claro\" align=\"center\">";
        $this->salida .= "			  <td><b>$NombreEmp</b></td>\n";
        $NombreUtilidad = $this->NombreCentroUtilidad($Empresa, $centroutilidad);
        $this->salida .= "			  <td>" . $NombreUtilidad['descripcion'] . "</td>\n";
        $this->salida .= "		    </tr>\n";
        $this->salida .= "			  </table><BR>";
        $this->salida .= "      <table class=\"normal_10\"  border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"60%\" align=\"center\" >";
        $this->salida .= "      <tr><td colspan=\"3\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "      </td></tr>";
        $this->salida .= "       <tr class=\"modulo_table_title\"><td align=\"center\">DATOS DE LA BODEGA</td></tr>";
        $this->salida .= "       <tr><td class=\"modulo_list_oscuro\">";
        $this->salida .= "       <BR><table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"Empresa\" value=\"$Empresa\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"NombreEmp\" value=\"$NombreEmp\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"centroutilidad\" value=\"$centroutilidad\" >";
        $this->salida .= "	     <tr class=\"modulo_list_claro\">";
        $this->salida .= "	        <td class=\"" . $this->SetStyle("Bodega") . "\">BODEGA: </td>";
        $this->salida .= "				  <td><input type=\"text\" class=\"input-text\" maxlength=\"2\" size=\"2\" name=\"Bodega\" value=\"$Bodega\"></td>";
        $this->salida .= "	     </tr>";
        $this->salida .= "	     <tr class=\"modulo_list_claro\">";
        $this->salida .= "	        <td class=\"" . $this->SetStyle("descripcion") . "\">DESCRIPCION: </td>";
        $this->salida .= "				  <td><input type=\"text\" class=\"input-text\" maxlength=\"20\" size=\"20\" name=\"descripcion\" value=\"$descripcion\"></td>";
        $this->salida .= "	     </tr>";
        $this->salida .= "	     <tr class=\"modulo_list_claro\">";
        $this->salida .= "	        <td class=\"" . $this->SetStyle("Departamento") . "\">DEPARTAMENTOS: </td>";
        $this->salida .= "	        <td><select name=\"Departamento\" class=\"select\">";
        $Departamentos = $this->TiposDepartamentos($Empresa, $centroutilidad);
        $this->Mostrar($Departamentos, 'False', $Departamento);
        $this->salida .= "          </select></td>";
        $this->salida .= "	     </tr>";
        $this->salida .= "	     <tr class=\"modulo_list_claro\">";
        $this->salida .= "	        <td class=\"" . $this->SetStyle("Ubicacion") . "\">UBICACION: </td>";
        $this->salida .= "				  <td><input type=\"text\" class=\"input-text\" maxlenght=\"20\" size=\"20\" name=\"Ubicacion\" value=\"$Ubicacion\"></td>";
        $this->salida .= "	     </tr>";
        $this->salida .= "	     <tr class=\"modulo_list_claro\">";
        $this->salida .= "	        <td class=\"" . $this->SetStyle("Responsable") . "\">RESPONSABLE: </td>";
        $this->salida .= "				  <td><input type=\"text\" class=\"input-text\" maxlenght=\"20\" size=\"20\" name=\"Responsable\" value=\"$Responsable\"></td>";
        $this->salida .= "	     </tr>";
        /* $this->salida .= "	     <tr class=\"modulo_list_claro\">";
          $this->salida .= "	        <td class=\"".$this->SetStyle("tipoNumeracion")."\">TIPO DE NUMERACION: </td>";
          $this->salida .= "	        <td><select name=\"tipoNumeracion\" class=\"select\">";
          $TiposNumeracion=$this->TiposDeNumeracion();
          $this->Mostrar($TiposNumeracion,'False',$tipoNumeracion);
          $this->salida .= "          </select></td>";
          $this->salida .= "	     </tr>"; */
        if ($restitucion)
        {
            $var = 'checked';
        }
        $this->salida .= "	     <tr class=\"modulo_list_claro\">";
        $this->salida .= "	        <td class=\"" . $this->SetStyle("restitucion") . "\">REPOSICION AUTOMATICA: </td>";
        $this->salida .= "	        <td valign=\"top\">";
        $this->salida .= "	        <input onclick=\"chequearTipoDisposicion(this.form,this.checked)\" type=\"checkbox\" name=\"restitucion\" $var>&nbsp&nbsp;";
        if (!$restitucion)
        {
            $sel = 'disabled';
        }
        else
        {
            $sel = '';
        }
        $this->salida .= "	        <select name=\"TipoDisposicion\" class=\"select\" $sel>";
        if ($TipoDisposicion == 'MIN')
        {
            $min = 'selected';
        }
        elseif ($TipoDisposicion == 'MAX')
        {
            $max = 'selected';
        }
        $this->salida .="           <option value=\"MIN\" $min>MINIMAS</option>";
        $this->salida .="           <option value=\"MAX\" $max>MAXIMAS</option>";
        $this->salida .= "	        </select>";
        $this->salida .= "	        </td>";
        $this->salida .= "	     </tr>";
        if ($ingresocompras)
        {
            $var1 = 'checked';
        }
        $this->salida .= "	     <tr class=\"modulo_list_claro\">";
        $this->salida .= "	        <td class=\"" . $this->SetStyle("ingresocompras") . "\">INGRESO COMPRAS: </td>";
        $this->salida .= "	        <td><input type=\"checkbox\" name=\"ingresocompras\" $var1></td>";
        $this->salida .= "	     </tr>";
        $this->salida .= "			 </table><BR>";
        $this->salida .= "	     </td></tr>";
        $this->salida .= "	     <tr><td>&nbsp;</td></tr>";
        $this->salida .= "       <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"INSERTAR\">&nbsp;";
        $this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"VOLVER\"></td></tr>";
        $this->salida .= "			 </table><BR>";
        if ($bandera != 1)
        {
            $this->salida .= "       <table class=\"normal_10N\"  border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" >";
            $actionLink = ModuloGetURL('app', 'Inventarios', 'user', 'LLamaListadoBodegas', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "bandera" => 1));
            $this->salida .= "				<tr><td class=\"" . $this->SetStyle("") . "\" align=\"right\"><a href=\"$actionLink\"><b>VER BODEGAS</b></a></td></tr>";
            $this->salida .= "       </table>";
        }
        else
        {
            $TotalBodegas = $this->ConsultaTotalBodegas($Empresa, $centroutilidad);
            if ($TotalBodegas)
            {
                $this->salida .= "	  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "    <tr class=\"modulo_table_list_title\" align=\"center\">";
                $this->salida .= "			<td>CODIGO</td>\n";
                $this->salida .= "			<td>DESCRIPCION</td>\n";
                $this->salida .= "			<td>DEPARTAMENTO</td>\n";
                $this->salida .= "			<td>LOCALIZACION</td>\n";
                $this->salida .= "			<td>RESPONSABLE</td>\n";
                $this->salida .= "			<td>DETALLE</td>\n";
                $this->salida .= "			<td>ESTADO</td>\n";
                $this->salida .= "			<td>UBICACIONES</td>\n";
                $this->salida .= "		 </tr>\n";
                $y = 0;
                for ($i = 0; $i < sizeof($TotalBodegas); $i++)
                {
                    if ($y % 2)
                    {
                        $estilo = 'modulo_list_claro';
                    }
                    else
                    {
                        $estilo = 'modulo_list_oscuro';
                    }
                    $this->salida .= "	 <tr class=\"modulo_list_claro\">\n";
                    $this->salida .= "	 <td width=\"5%\">" . $TotalBodegas[$i]['bodega'] . "</td>";
                    $this->salida .= "	 <td>" . $TotalBodegas[$i]['descripcion'] . "</td>";
                    $this->salida .= "	 <td>" . $TotalBodegas[$i]['desdpto'] . "</td>";
                    $this->salida .= "	 <td>" . $TotalBodegas[$i]['ubicacion'] . "</td>";
                    $this->salida .= "	 <td>" . $TotalBodegas[$i]['responsable'] . "</td>";
                    $action1 = ModuloGetURL('app', 'Inventarios', 'user', 'LlamaFormaModificarBodega', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "bandera" => $bandera, "Bodega" => $TotalBodegas[$i]['bodega'], "NomBodega" => $TotalBodegas[$i]['descripcion'], "descripcion" => $TotalBodegas[$i]['descripcion'], "Departamento" => $TotalBodegas[$i]['departamento'], "ubicacion" => $TotalBodegas[$i]['ubicacion'], "responsable" => $TotalBodegas[$i]['responsable'], "restitucion" => $TotalBodegas[$i]['sw_restitucion'], "ingresocompras" => $TotalBodegas[$i]['autorizacion_recibir_compras']));
                    $action2 = ModuloGetURL('app', 'Inventarios', 'user', 'ModificarEstadoBodega', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "bandera" => $bandera, "Estado" => $TotalBodegas[$i]['estado'], "Bodega" => $TotalBodegas[$i]['bodega']));
                    $action3 = ModuloGetURL('app', 'Inventarios', 'user', 'CrearUbicacionesBodega', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $TotalBodegas[$i]['bodega'], "descripcion" => $TotalBodegas[$i]['descripcion'], "ubicacion" => $TotalBodegas[$i]['ubicacion'], "responsable" => $TotalBodegas[$i]['responsable'], "TipoNumeracion" => $TotalBodegas[$i]['tipo_numeracion'], "desDpto" => $TotalBodegas[$i]['desdpto']));
                    $this->salida .= "	 <td align=\"center\"><a href=\"$action1\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/modificar.png\"></a></td>";
                    if ($TotalBodegas[$i]['estado'] == 1)
                    {
                        $this->salida .= "	 <td align=\"center\"><a href=\"$action2\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/checksi.png\"></a></td>";
                        $this->salida .= "	 <td align=\"center\"><a href=\"$action3\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/ubicacion.png\"></a></td>";
                    }
                    else
                    {
                        $this->salida .= "	 <td align=\"center\"><a href=\"$action2\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/checkno.png\"></a></td>";
                        $this->salida .= "	 <td align=\"center\">&nbsp;</td>";
                    }
                    $this->salida .= "	 </tr>\n";
                    $y++;
                }
                $this->salida .= "       <table class=\"normal_10N\"  border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" >";
                $actionLink1 = ModuloGetURL('app', 'Inventarios', 'user', 'LLamaListadoBodegas', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "bandera" => 0));
                $this->salida .= "				<tr><td class=\"" . $this->SetStyle("") . "\" align=\"right\"><a href=\"$actionLink1\" class=\"link\"><b>CERRAR</b></a></td></tr>";
                $this->salida .= "       </table><BR>";
            }
            else
            {
                $this->salida .= "       <table class=\"normal_10N\"  border=\"0\" width=\"90%\" align=\"center\" >";
                $this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON BODEGAS</td></tr>";
                $this->salida .= "       </table><BR>";
            }
        }
        $this->salida .= " </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function FormaModificarBodega($Empresa, $NombreEmp, $centroutilidad, $bandera, $Bodega, $NomBodega, $Departamento, $descripcion, $Ubicacion, $Responsable, $centinela, $restitucion, $ingresocompras)
    {

        $this->salida .= ThemeAbrirTabla('MODIFICACION BODEGA' . ' ' . $NomBodega);
        $action = ModuloGetURL('app', 'Inventarios', 'user', 'InsertarModificacionBodegas');
        $this->salida .= "      <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "       <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "			  <td>NOMBRE EMPRESA</td>\n";
        $this->salida .= "			  <td>NOMBRE CENTRO UTILIDAD</td>\n";
        $this->salida .= "		    </tr>\n";
        $this->salida .= "       <tr class=\"modulo_list_claro\" align=\"center\">";
        $this->salida .= "			  <td><b>$NombreEmp</b></td>\n";
        $NombreUtilidad = $this->NombreCentroUtilidad($Empresa, $centroutilidad);
        $this->salida .= "			  <td><b>" . $NombreUtilidad['descripcion'] . "</b></td>\n";
        $this->salida .= "		    </tr>\n";
        $this->salida .= "			  </table>";
        $this->salida .= "        <br><table class=\"normal_10\" border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"60%\" align=\"center\" >";
        $this->salida .= "        <tr><td colspan=\"3\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "        </td></tr>";
        $this->salida .= "        <tr class=\"modulo_table_title\"><td align=\"center\">DATOS DE LA BODEGA</td></tr>";
        $this->salida .= "        <tr><td class=\"modulo_list_oscuro\">";
        $this->salida .= "        <table class=\"normal_10\"  border=\"0\" width=\"95%\" align=\"center\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"Empresa\" value=\"$Empresa\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"NombreEmp\" value=\"$NombreEmp\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"centroutilidad\" value=\"$centroutilidad\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"bandera\" value=\"$bandera\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"NomBodega\" value=\"$NomBodega\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"Bodega\" value=\"$Bodega\" >";
        $this->salida .= "		    <input type=\"hidden\" name=\"centinela\" value=\"$centinela\" >";
        $this->salida .= "	     <tr><td>&nbsp;</td></tr>";
        $this->salida .= "	     <tr class=\"modulo_list_claro\">";
        $this->salida .= "	        <td class=\"" . $this->SetStyle("descripcion") . "\">DESCRIPCION: </td>";
        $this->salida .= "				  <td><input type=\"text\" class=\"input-text\" size=\"30\" name=\"descripcion\" value=\"$descripcion\"></td>";
        $this->salida .= "	     </tr>";
        $this->salida .= "	     <tr class=\"modulo_list_claro\">";
        $this->salida .= "	        <td class=\"" . $this->SetStyle("Departamento") . "\">DEPARTAMENTOS: </td>";
        $this->salida .= "	        <td><select name=\"Departamento\" class=\"select\">";
        $Departamentos = $this->TiposDepartamentos($Empresa, $centroutilidad);
        $this->Mostrar($Departamentos, 'False', $Departamento);
        $this->salida .= "          </select></td>";
        $this->salida .= "	     </tr>";
        $this->salida .= "	     <tr class=\"modulo_list_claro\">";
        $this->salida .= "	        <td class=\"" . $this->SetStyle("Ubicacion") . "\">UBICACION: </td>";
        $this->salida .= "				  <td><input type=\"text\" class=\"input-text\" maxlenght=\"20\" size=\"20\" name=\"Ubicacion\" value=\"$Ubicacion\"></td>";
        $this->salida .= "	     </tr>";
        $this->salida .= "	     <tr class=\"modulo_list_claro\">";
        $this->salida .= "	        <td class=\"" . $this->SetStyle("Responsable") . "\">RESPONSABLE: </td>";
        $this->salida .= "				  <td><input type=\"text\" class=\"input-text\" maxlenght=\"20\" size=\"20\" name=\"Responsable\" value=\"$Responsable\"></td>";
        $this->salida .= "	     </tr>";
        if ($restitucion)
        {
            $var = 'checked';
        }
        $this->salida .= "	     <tr class=\"modulo_list_claro\">";
        $this->salida .= "	        <td class=\"" . $this->SetStyle("restitucion") . "\">REPOSICION AUTOMATICA: </td>";
        $this->salida .= "	        <td><input type=\"checkbox\" name=\"restitucion\" $var></td>";
        $this->salida .= "	     </tr>";
        if ($ingresocompras)
        {
            $var1 = 'checked';
        }
        $this->salida .= "	     <tr class=\"modulo_list_claro\">";
        $this->salida .= "	        <td class=\"" . $this->SetStyle("ingresocompras") . "\">INGRESO COMPRAS: </td>";
        $this->salida .= "	        <td><input type=\"checkbox\" name=\"ingresocompras\" $var1></td>";
        $this->salida .= "	     </tr>";
        $this->salida .= "	     </table><BR>";
        $this->salida .= "       <tr><td>&nbsp;</td></tr>";
        $this->salida .= "       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\">&nbsp;";
        $this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"MODIFICAR\"></td></tr>";
        $this->salida .= "			 </table>";
        $this->salida .= " </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function CrearUbicacionesEmpresas($Empresa, $NombreEmp, $centroutilidad, $Bodega, $descripcion, $ubicacion, $responsable, $TipoNumeracion, $NomDpto, $centinela)
    {

        $this->salida .= ThemeAbrirTabla('UBICACIONES DENTRO DE LA BODEGA');
        $action = ModuloGetURL('app', 'Inventarios', 'user', 'LLamaListadoBodegas', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "bandera" => 1, "centinela" => $centinela));
        $this->salida .= "     <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->salida .= "	    <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "        <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "			  <td>EMPRESA</td>\n";
        $this->salida .= "			  <td>CENTRO UTILIDAD</td>\n";
        $this->salida .= "			  <td>BODEGA</td>\n";
        $this->salida .= "        </tr>";
        $this->salida .= "        <tr class=\"modulo_list_claro\" align=\"center\">";
        $this->salida .= "			  <td><b>$NombreEmp</b></td>\n";
        $nombreCU = $this->NombreCentroUtilidad($Empresa, $centroutilidad);
        $this->salida .= "			  <td><b>" . $nombreCU['descripcion'] . "</b></td>\n";
        $this->salida .= "			  <td><b>$Bodega $descripcion</b></td>\n";
        $this->salida .= "     </tr>";
        $this->salida .= "			</table><BR>";
        $this->salida .= "     <table class=\"normal_10\"  border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "         <input type=\"hidden\" name=\"Empresa\" value=\"$Empresa\">";
        $this->salida .= "	  	   <input type=\"hidden\" name=\"NombreEmp\" value=\"$NombreEmp\">";
        $this->salida .= "	  	   <input type=\"hidden\" name=\"centroutilidad\" value=\"$centroutilidad\">";
        $this->salida .= "	  	   <input type=\"hidden\" name=\"Bodega\" value=\"$Bodega\">";
        $this->salida .= "	  	   <input type=\"hidden\" name=\"descripcion\" value=\"$descripcion\">";
        $this->salida .= "	  	   <input type=\"hidden\" name=\"ubicacion\" value=\"$ubicacion\">";
        $this->salida .= "	  	   <input type=\"hidden\" name=\"responsable\" value=\"$responsable\">";
        $this->salida .= "	  	   <input type=\"hidden\" name=\"TipoNumeracion\" value=\"$TipoNumeracion\">";
        $this->salida .= "         <tr><td align=\"center\"><fieldset><legend class=\"field\"> BODEGA </legend>";
        $this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"95%\" align=\"center\">";
        $this->salida .= "         <tr><td></td></tr>";
        $this->salida .= "         <tr class=\"modulo_list_claro\">";
        $this->salida .= "         <td width=\"20%\" class=\"label\">UBICACION</td><td>$ubicacion</td>";
        $this->salida .= "         <td width=\"20%\" class=\"label\">DEPARTAMENTO</td><td>$NomDpto</td>";
        $this->salida .= "         </tr>";
        $this->salida .= "         <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "         <td width=\"20%\" class=\"label\">RESPONSABLE</td><td>$responsable</td>";
        //$nombreNumeracion=$this->DescripcionNumeracion($TipoNumeracion);
        $this->salida .= "         <td width=\"20%\" class=\"label\">TIPO NUMERACION</td><td>$nombreNumeracion</td>";
        $this->salida .= "         </tr>";
        $this->salida .= "         <tr><td></td></tr>";
        $this->salida .= "		     </table></fieldset></td></tr>";
        $this->salida .= "     </table><BR>";
        $totalClasificacionUno = $this->ClasificacionUbicacionUno($Empresa, $centroutilidad, $Bodega);
        if ($totalClasificacionUno)
        {
            $this->salida .= "      <table class=\"normal_10\"  cellspacing=\"3\"  cellpadding=\"3\"border=\"1\" width=\"75%\" align=\"center\">";
            $this->salida .= "      <tr><td align=\"center\" colspan=\"2\" class=\"modulo_table_list_title\">CLASIFICACIONES</td></tr>";
            for ($i = 0; $i < sizeof($totalClasificacionUno); $i++)
            {
                if ($i % 2)
                {
                    $estilo = 'modulo_list_claro';
                }
                else
                {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida .= "      <tr>";
                $this->salida .= "        <td align=\"center\" class=\"$estilo\"><b>" . $totalClasificacionUno[$i]['n1'] . "<BR></b>";
                $accionEdit = ModuloGetURL('app', 'Inventarios', 'user', 'EditarClasificacionUbicacion', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $Bodega, "descripcion" => $descripcion, "ubicacion" => $ubicacion, "responsable" => $responsable, "TipoNumeracion" => $TipoNumeracion, "NomDpto" => $NomDpto, "NombreUbicaAct" => $totalClasificacionUno[$i]['n1'], "bandera" => 1, "tipoAlmacenaje" => $totalClasificacionUno[$i]['tipo_almacenaje_id'], "centinela" => $centinela));
                $this->salida .= "			  &nbsp;<a href=\"$accionEdit\"><img border=\"0\" src=\"" . GetThemePath() . "/images/modificar.png\"></a>";
                $accionAdic = ModuloGetURL('app', 'Inventarios', 'user', 'AdicionarClasificacionUbicacion', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $Bodega, "descripcion" => $descripcion, "ubicacion" => $ubicacion, "responsable" => $responsable, "TipoNumeracion" => $TipoNumeracion, "NomDpto" => $NomDpto, "bandera" => 1, "n1" => $totalClasificacionUno[$i]['n1'], "centinela" => $centinela));
                $this->salida .= "        &nbsp;<a href=\"$accionAdic\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/planblanco.png\"></a>";
                $comprobacionNivel1 = $this->ComprobarExisteNivelSiguiente($Empresa, $centroutilidad, $Bodega, 1, $totalClasificacionUno[$i]['n1']);
                if ($comprobacionNivel1 != 1)
                {
                    $accionElim = ModuloGetURL('app', 'Inventarios', 'user', 'EliminarUbicacionClasify', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $Bodega, "descripcion" => $descripcion, "ubicacion" => $ubicacion, "responsable" => $responsable, "TipoNumeracion" => $TipoNumeracion, "NomDpto" => $NomDpto, "bandera" => 1, "n1" => $totalClasificacionUno[$i]['n1'], "centinela" => $centinela));
                    $this->salida .= "        &nbsp;<a href=\"$accionElim\"><img border=\"0\" src=\"" . GetThemePath() . "/images/elimina.png\"></a>";
                }
                $this->salida .= "        <BR><br><font color=\"#3d61ff\">" . $totalClasificacionUno[$i]['destipoal'] . "</font>";
                $this->salida .= "        </td>";
                $this->salida .= "        <td class=\"$estilo\" width=\"70%\">";
                $totalClasificacionDos = $this->ClasificacionUbicacionDos($Empresa, $centroutilidad, $Bodega, $totalClasificacionUno[$i]['n1']);
                for ($j = 0; $j < sizeof($totalClasificacionDos); $j++)
                {
                    if ($totalClasificacionDos[$j]['n2'])
                    {
                        $this->salida .= "    <table class=\"normal_10\"  cellspacing=\"0\"  cellpadding=\"3\"border=\"1\" width=\"100%\" align=\"center\">";
                        if ($j % 2)
                        {
                            $estilo1 = 'modulo_list_claro';
                        }
                        else
                        {
                            $estilo1 = 'modulo_list_oscuro';
                        }
                        $this->salida .= "    <tr class=\"$estilo1\">";
                        $this->salida .= "      <td align=\"center\">" . $totalClasificacionDos[$j]['n2'] . "<BR>";
                        if ($totalClasificacionDos[$j]['n2'])
                        {
                            $accionEdit = ModuloGetURL('app', 'Inventarios', 'user', 'EditarClasificacionUbicacion', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $Bodega, "descripcion" => $descripcion, "ubicacion" => $ubicacion, "responsable" => $responsable, "TipoNumeracion" => $TipoNumeracion, "NomDpto" => $NomDpto, "NombreUbicaAct" => $totalClasificacionDos[$j]['n2'], "bandera" => 2, "n1" => $totalClasificacionUno[$i]['n1'], "centinela" => $centinela));
                            $this->salida .= "			 &nbsp;<a href=\"$accionEdit\"><img border=\"0\" src=\"" . GetThemePath() . "/images/modificar.png\"></a>";
                            $accionAdic = ModuloGetURL('app', 'Inventarios', 'user', 'AdicionarClasificacionUbicacion', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $Bodega, "descripcion" => $descripcion, "ubicacion" => $ubicacion, "responsable" => $responsable, "TipoNumeracion" => $TipoNumeracion, "NomDpto" => $NomDpto, "bandera" => 2, "n1" => $totalClasificacionUno[$i]['n1'], "n2" => $totalClasificacionDos[$j]['n2'], "centinela" => $centinela));
                            $this->salida .= "       &nbsp;<a href=\"$accionAdic\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/planblanco.png\"></a>";
                            $comprobacionNivel1 = $this->ComprobarExisteNivelSiguiente($Empresa, $centroutilidad, $Bodega, 2, $totalClasificacionUno[$i]['n1'], $totalClasificacionDos[$j]['n2']);
                            if ($comprobacionNivel1 != 1)
                            {
                                $accionElim = ModuloGetURL('app', 'Inventarios', 'user', 'EliminarUbicacionClasify', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $Bodega, "descripcion" => $descripcion, "ubicacion" => $ubicacion, "responsable" => $responsable, "TipoNumeracion" => $TipoNumeracion, "NomDpto" => $NomDpto, "bandera" => 2, "n1" => $totalClasificacionUno[$i]['n1'], "n2" => $totalClasificacionDos[$j]['n2'], "centinela" => $centinela));
                                $this->salida .= "        &nbsp;<a href=\"$accionElim\"><img border=\"0\" src=\"" . GetThemePath() . "/images/elimina.png\"></a>";
                            }
                        }
                        $this->salida .= "       </td>";
                        $this->salida .= "       <td width=\"80%\">";
                        $totalClasificacionTres = $this->ClasificacionUbicacionTres($Empresa, $centroutilidad, $Bodega, $totalClasificacionUno[$i]['n1'], $totalClasificacionDos[$j]['n2']);
                        for ($k = 0; $k < sizeof($totalClasificacionTres); $k++)
                        {
                            if ($totalClasificacionTres[$k]['n3'])
                            {
                                $this->salida .= "      <table class=\"normal_10\"  cellspacing=\"0\"  cellpadding=\"3\"border=\"1\" width=\"100%\" align=\"center\">";
                                $this->salida .= "      <tr>";
                                $this->salida .= "      <td>" . $totalClasificacionTres[$k]['n3'] . "&nbsp;&nbsp;&nbsp;";
                                if ($totalClasificacionTres[$k]['n3'])
                                {
                                    $accionEdit = ModuloGetURL('app', 'Inventarios', 'user', 'EditarClasificacionUbicacion', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $Bodega, "descripcion" => $descripcion, "ubicacion" => $ubicacion, "responsable" => $responsable, "TipoNumeracion" => $TipoNumeracion, "NomDpto" => $NomDpto, "NombreUbicaAct" => $totalClasificacionTres[$k]['n3'], "bandera" => 3, "n1" => $totalClasificacionUno[$i]['n1'], "n2" => $totalClasificacionDos[$j]['n2'], "centinela" => $centinela));
                                    $this->salida .= "			 <a href=\"$accionEdit\"><img border=\"0\" src=\"" . GetThemePath() . "/images/modificar.png\"></a>&nbsp;";
                                    $accionAdic = ModuloGetURL('app', 'Inventarios', 'user', 'AdicionarClasificacionUbicacion', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $Bodega, "descripcion" => $descripcion, "ubicacion" => $ubicacion, "responsable" => $responsable, "TipoNumeracion" => $TipoNumeracion, "NomDpto" => $NomDpto, "bandera" => 3, "n1" => $totalClasificacionUno[$i]['n1'], "n2" => $totalClasificacionDos[$j]['n2'], "n3" => $totalClasificacionTres[$k]['n3'], "centinela" => $centinela));
                                    $this->salida .= "       <a href=\"$accionAdic\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/planblanco.png\"></a>&nbsp;";
                                    $comprobacionNivel1 = $this->ComprobarExisteNivelSiguiente($Empresa, $centroutilidad, $Bodega, 3, $totalClasificacionUno[$i]['n1'], $totalClasificacionDos[$j]['n2'], $totalClasificacionTres[$k]['n3']);
                                    if ($comprobacionNivel1 != 1)
                                    {
                                        $accionElim = ModuloGetURL('app', 'Inventarios', 'user', 'EliminarUbicacionClasify', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $Bodega, "descripcion" => $descripcion, "ubicacion" => $ubicacion, "responsable" => $responsable, "TipoNumeracion" => $TipoNumeracion, "NomDpto" => $NomDpto, "bandera" => 3, "n1" => $totalClasificacionUno[$i]['n1'], "n2" => $totalClasificacionDos[$j]['n2'], "n3" => $totalClasificacionTres[$k]['n3'], "centinela" => $centinela));
                                        $this->salida .= "      <a href=\"$accionElim\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/elimina.png\"></a>&nbsp;";
                                    }
                                }
                                $this->salida .= "      </td>";
                                $this->salida .= "      <td width=\"50%\">";
                                $totalClasificacionCuatro = $this->ClasificacionUbicacionCuatro($Empresa, $centroutilidad, $Bodega, $totalClasificacionUno[$i]['n1'], $totalClasificacionDos[$j]['n2'], $totalClasificacionTres[$k]['n3']);
                                //if($totalClasificacionCuatro){
                                for ($l = 0; $l < sizeof($totalClasificacionCuatro); $l++)
                                {
                                    if ($totalClasificacionCuatro[$l]['n4'])
                                    {
                                        $this->salida .= "      <table class=\"normal_10\"  cellspacing=\"0\"  cellpadding=\"3\"border=\"1\" width=\"100%\" align=\"center\">";
                                        $this->salida .= "      <tr>";
                                        $this->salida .= "      <td>" . $totalClasificacionCuatro[$l]['n4'] . "&nbsp;&nbsp;&nbsp;";
                                        if ($totalClasificacionCuatro[$l]['n4'])
                                        {
                                            $accionEdit = ModuloGetURL('app', 'Inventarios', 'user', 'EditarClasificacionUbicacion', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $Bodega, "descripcion" => $descripcion, "ubicacion" => $ubicacion, "responsable" => $responsable, "TipoNumeracion" => $TipoNumeracion, "NomDpto" => $NomDpto, "NombreUbicaAct" => $totalClasificacionCuatro[$l]['n4'], "bandera" => 4, "n1" => $totalClasificacionUno[$i]['n1'], "n2" => $totalClasificacionDos[$j]['n2'], "n3" => $totalClasificacionTres[$k]['n3'], "centinela" => $centinela));
                                            $this->salida .= "			 <a href=\"$accionEdit\"><img border=\"0\" src=\"" . GetThemePath() . "/images/modificar.png\"></a>&nbsp;&nbsp;";
                                            $accionElim = ModuloGetURL('app', 'Inventarios', 'user', 'EliminarUbicacionClasify', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $Bodega, "descripcion" => $descripcion, "ubicacion" => $ubicacion, "responsable" => $responsable, "TipoNumeracion" => $TipoNumeracion, "NomDpto" => $NomDpto, "bandera" => 4, "n1" => $totalClasificacionUno[$i]['n1'], "n2" => $totalClasificacionDos[$j]['n2'], "n3" => $totalClasificacionTres[$k]['n3'], "n4" => $totalClasificacionCuatro[$l]['n4'], "centinela" => $centinela));
                                            $this->salida .= "       <a href=\"$accionElim\"><img border=\"0\" src=\"" . GetThemePath() . "/images/elimina.png\"></a>";
                                        }
                                        $this->salida .= "      </td>";
                                        $this->salida .= "      </tr>";
                                        $this->salida .= "      </table>";
                                    }
                                }
                                //}else{
                                //$this->salida .= "      <table class=\"normal_10\"  cellspacing=\"0\"  cellpadding=\"3\"border=\"1\" width=\"100%\" align=\"center\">";
                                //$this->salida .= "      <tr><td>&nbsp;</td></tr>";
                                //$this->salida .= "      </table>";
                                //}
                                $this->salida .= "      </td>";
                                $this->salida .= "      </tr>";
                                $this->salida .= "      </table>";
                            }
                        }
                        $this->salida .= "      </td>";
                        $this->salida .= "      </tr>";
                        $this->salida .= "      </table>";
                    }
                }
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
            }
            $this->salida .= "      </table>";
        }
        else
        {
            $this->salida .= "      <table class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= "      <tr><td align=\"center\" class=\"label_error\">NO EXISTE CLASIFICACION DE UBICACIONES EN ESTA BODEGA</td></tr>";
            $this->salida .= "      </table>";
        }
        $this->salida .= "      <table class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\"border=\"0\" width=\"75%\" align=\"center\">";
        $accionAdic = ModuloGetURL('app', 'Inventarios', 'user', 'AdicionarClasificacionUbicacion', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $Bodega, "descripcion" => $descripcion, "ubicacion" => $ubicacion, "responsable" => $responsable, "TipoNumeracion" => $TipoNumeracion, "NomDpto" => $NomDpto, "bandera" => 4, "centinela" => $centinela));
        $this->salida .= "       <tr><td class=\"label\"><a href=\"$accionAdic\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/planblanco.png\"></a>&nbsp&nbsp;NUEVO</td></tr>";
        $this->salida .= "      </table>";
        $this->salida .= "      <table class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "      <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"salir\" value=\"VOLVER\"></td></tr>";
        $this->salida .= "      </table>";
        $this->salida .="       </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function FormaAdicionarClasificacionUbicacion($Empresa, $NombreEmp, $centroutilidad, $Bodega, $descripcion, $ubicacion, $responsable, $TipoNumeracion, $NomDpto, $bandera, $n1, $n2, $n3, $centinela)
    {

        if ($bandera == 1)
        {
            $palabra = 'SEGUNDO NIVEL';
        }
        elseif ($bandera == 2)
        {
            $palabra = 'TERCER NIVEL';
        }
        elseif ($bandera == 3)
        {
            $palabra = 'CUARTO NIVEL';
        }
        elseif ($bandera == 4)
        {
            $palabra = 'PRIMER NIVEL';
        }
        $this->salida .= ThemeAbrirTabla('ADICION DE CLASIFICACION DE LA UBICACION DENTRO DE LA BODEGA');
        $action = ModuloGetURL('app', 'Inventarios', 'user', 'InsertarNuevaClasify', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $Bodega, "descripcion" => $descripcion, "ubicacion" => $ubicacion, "responsable" => $responsable, "TipoNumeracion" => $TipoNumeracion, "NomDpto" => $NomDpto, "bandera" => $bandera, "n1" => $n1, "n2" => $n2, "n3" => $n3, "centinela" => $_REQUEST['centinela']));
        $this->salida .= "     <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->salida .= "	    <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "			 <td>EMPRESA</td>\n";
        $this->salida .= "			 <td>CENTRO UTILIDAD</td>\n";
        $this->salida .= "			 <td>BODEGA</td>\n";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\" align=\"center\">";
        $this->salida .= "			 <td><b>$NombreEmp</b></td>\n";
        $nombreCU = $this->NombreCentroUtilidad($Empresa, $centroutilidad);
        $this->salida .= "			 <td><b>" . $nombreCU['descripcion'] . "</b></td>\n";
        $this->salida .= "			 <td><b>$Bodega $descripcion</b></td>\n";
        $this->salida .= "      </tr>";
        $this->salida .= "			 </table><BR>";
        $this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "         <tr><td colspan=\"3\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "         </td></tr>";
        $this->salida .= "         <tr class=\"modulo_table_title\"><td align=\"center\">PROPIEDADES DE LA NUEVA CLASIFICACION EN EL $palabra</td></tr>";
        $this->salida .= "         <tr><td class=\"modulo_list_oscuro\">";
        $this->salida .= "         <BR><table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= "         <tr><td colspan=\"3\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "         </td></tr>";
        $this->salida .= "        <tr class=\"modulo_list_claro\">";
        $this->salida .= "        <td class=\"" . $this->SetStyle("descripcionNuv") . "\"><br>DESCRIPCION: </td>";
        $this->salida .= "	  	   <td><input type=\"text\" maxlength=\"40\" size=\"30\" name=\"descripcionNuv\" value=\"$descripcionNuv\" class=\"input-text\"></td>";
        $this->salida .= "        </tr>";
        if ($bandera == 4)
        {
            $this->salida .= "	      <tr class=\"modulo_list_claro\">";
            $this->salida .= "        <td class=\"" . $this->SetStyle("tipoAlmacenaje") . "\">TIPO ALMACENAJE: </td>";
            $this->salida .= "        <td><select name=\"tipoAlmacenaje\" class=\"select\">";
            $TiposAlmacenajes = $this->TiposAlmacenajesBodega();
            $this->Mostrar($TiposAlmacenajes, 'False', $tipoAlmacenaje);
            $this->salida .= "        </select></td></tr>";
        }
        $this->salida .= "		    </table><BR>";
        $this->salida .= "        <tr><td>&nbsp;</td></tr>";
        $this->salida .= "        <tr align=\"center\"><td><input class=\"input-submit\" type=\"submit\" name=\"Modificar\" value=\"INSERTAR\">&nbsp;";
        $this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
        $this->salida .= "		    </table>";
        $this->salida .="     </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function FormaEditarClasificacionUbicacion($Empresa, $NombreEmp, $centroutilidad, $Bodega, $descripcion, $ubicacion, $responsable, $TipoNumeracion, $NomDpto, $bandera, $tipoAlmacenaje, $NombreUbicaAct, $n1, $n2, $n3, $centinela)
    {

        if ($bandera == 1)
        {
            $palabra = 'DE LA PRIMERA CLASIFICACION';
        }
        elseif ($bandera == 2)
        {
            $palabra = 'DE LA SEGUNDA CLASIFICACION';
        }
        elseif ($bandera == 3)
        {
            $palabra = 'DE LA TERCERA CLASIFICACION';
        }
        elseif ($bandera == 4)
        {
            $palabra = 'DE LA CUARTA CLASIFICACION';
        }
        $this->salida .= ThemeAbrirTabla('EDICION CLASIFICACION UBICACION DENTRO DE LA BODEGA');
        $this->salida .= "			 <br>";

        $action = ModuloGetURL('app', 'Inventarios', 'user', 'InsertarEditarClasify', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $Bodega, "descripcion" => $descripcion, "ubicacion" => $ubicacion, "responsable" => $responsable, "TipoNumeracion" => $TipoNumeracion, "NomDpto" => $NomDpto, "bandera" => $bandera, "tipoAlmacenaje" => $tipoAlmacenaje, "NombreUbicaAct" => $NombreUbicaAct, "n1" => $n1, "n2" => $n2, "n3" => $n3, "centinela" => $centinela));
        $this->salida .= "     <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->salida .= "	    <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "			 <td>EMPRESA</td>\n";
        $this->salida .= "			 <td>CENTRO UTILIDAD</td>\n";
        $this->salida .= "			 <td>BODEGA</td>\n";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\" align=\"center\">";
        $this->salida .= "			 <td><b>$NombreEmp</b></td>\n";
        $nombreCU = $this->NombreCentroUtilidad($Empresa, $centroutilidad);
        $this->salida .= "			 <td><b>" . $nombreCU['descripcion'] . "</b></td>\n";
        $this->salida .= "			 <td><b>$Bodega $descripcion</b></td>\n";
        $this->salida .= "      </tr>";
        $this->salida .= "			 </table><BR>";
        $this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "         <tr><td colspan=\"3\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "         </td></tr>";
        $this->salida .= "         <tr class=\"modulo_table_title\"><td align=\"center\">PROPIEDADES $palabra</td></tr>";
        $this->salida .= "         <tr><td class=\"modulo_list_oscuro\">";
        $this->salida .= "         <BR><table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\">";
        if ($bandera == 1)
        {
            $this->salida .= "       <tr class=\"modulo_list_claro\">";
            $this->salida .= "         <td class=\"" . $this->SetStyle("NombreUbica") . "\">DESCRIPCION: </td>";
            $this->salida .= "	  	    <td><input type=\"text\" maxlength=\"40\" size=\"30\" name=\"NombreUbica\" value=\"$NombreUbicaAct\" class=\"input-text\"></td>";
            $this->salida .= "       </tr>";
            $this->salida .= "	      <tr class=\"modulo_list_claro\">";
            $this->salida .= "        <td class=\"" . $this->SetStyle("tipoAlmacenaje") . "\">TIPO ALMACENAJE: </td>";
            $this->salida .= "        <td><select name=\"tipoAlmacenaje\" class=\"select\">";
            $TiposAlmacenajes = $this->TiposAlmacenajesBodega();
            $this->Mostrar($TiposAlmacenajes, 'False', $tipoAlmacenaje);
            $this->salida .= "        </select></td></tr>";
        }
        else
        {
            $this->salida .= "        <tr class=\"modulo_list_claro\">";
            $this->salida .= "        <td class=\"" . $this->SetStyle("NombreUbica") . "\">DESCRIPCION: </td>";
            $this->salida .= "	  	   <td><input type=\"text\" maxlength=\"40\" size=\"30\" name=\"NombreUbica\" value=\"$NombreUbicaAct\" class=\"input-text\"></td>";
            $this->salida .= "        </tr>";
        }
        $this->salida .= "       <input type=\"hidden\" name=\"bandera\" value=\"$bandera\">";
        $this->salida .= "		    </table><BR>";
        $this->salida .= "        <tr><td>&nbsp;</td></tr>";
        $this->salida .= "        <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Modificar\" value=\"MODIFICAR\">&nbsp";
        $this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
        $this->salida .= "		    </table>";
        $this->salida .="     </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function FormaConsultaBodegas($Empresa, $NombreEmp, $centroutilidad)
    {

        $this->salida .= ThemeAbrirTabla('CREACION BODEGAS');
        $action = ModuloGetURL('app', 'Inventarios', 'user', 'LlamaSeleccionCentroUtilidad', array('Empresa' => $Empresa, 'NombreEmp' => $NombreEmp, 'centroutilidad' => $centroutilidad));
        $this->salida .= "      <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->salida .= "       <BR>";
        $this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "       <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "			  <td>NOMBRE EMPRESA</td>\n";
        $this->salida .= "			  <td>NOMBRE CENTRO UTILIDAD</td>\n";
        $this->salida .= "		    </tr>\n";
        $this->salida .= "       <tr class=\"modulo_list_claro\" align=\"center\">";
        $this->salida .= "			  <td><b>$NombreEmp</b></td>\n";
        $NombreUtilidad = $this->NombreCentroUtilidad($Empresa, $centroutilidad);
        $this->salida .= "			  <td><b>" . $NombreUtilidad['descripcion'] . "</b></td>\n";
        $this->salida .= "		    </tr>\n";
        $this->salida .= "			  </table><BR><BR>";
        $TotalBodegas = $this->ConsultaTotalBodegas($Empresa, $centroutilidad);
        if ($TotalBodegas)
        {
            $this->salida .= "	  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "    <tr class=\"modulo_table_list_title\" align=\"center\">";
            $this->salida .= "			<td>CODIGO</td>\n";
            $this->salida .= "			<td>DESCRIPCION</td>\n";
            $this->salida .= "			<td>DEPARTAMENTO</td>\n";
            $this->salida .= "			<td>LOCALIZACION</td>\n";
            $this->salida .= "			<td>RESPONSABLE</td>\n";
            $this->salida .= "			<td>DETALLE</td>\n";
            $this->salida .= "			<td>ESTADO</td>\n";
            $this->salida .= "			<td>UBICACIONES</td>\n";
            $this->salida .= "		 </tr>\n";
            $y = 0;
            for ($i = 0; $i < sizeof($TotalBodegas); $i++)
            {
                if ($y % 2)
                {
                    $estilo = 'modulo_list_claro';
                }
                else
                {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida .= "	 <tr class=\"modulo_list_claro\">\n";
                $this->salida .= "	 <td width=\"5%\">" . $TotalBodegas[$i]['bodega'] . "</td>";
                $this->salida .= "	 <td>" . $TotalBodegas[$i]['descripcion'] . "</td>";
                $this->salida .= "	 <td>" . $TotalBodegas[$i]['desdpto'] . "</td>";
                $this->salida .= "	 <td>" . $TotalBodegas[$i]['ubicacion'] . "</td>";
                $this->salida .= "	 <td>" . $TotalBodegas[$i]['responsable'] . "</td>";
                $action1 = ModuloGetURL('app', 'Inventarios', 'user', 'LlamaFormaModificarBodega', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "bandera" => $bandera, "Bodega" => $TotalBodegas[$i]['bodega'], "NomBodega" => $TotalBodegas[$i]['descripcion'], "descripcion" => $TotalBodegas[$i]['descripcion'], "Departamento" => $TotalBodegas[$i]['departamento'], "ubicacion" => $TotalBodegas[$i]['ubicacion'], "responsable" => $TotalBodegas[$i]['responsable'], "centinela" => 1, "restitucion" => $TotalBodegas[$i]['sw_restitucion'], "ingresocompras" => $TotalBodegas[$i]['autorizacion_recibir_compras']));
                $action2 = ModuloGetURL('app', 'Inventarios', 'user', 'ModificarEstadoBodega', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "bandera" => $bandera, "Estado" => $TotalBodegas[$i]['estado'], "Bodega" => $TotalBodegas[$i]['bodega'], "centinela" => 1));
                $action3 = ModuloGetURL('app', 'Inventarios', 'user', 'CrearUbicacionesBodega', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "centroutilidad" => $centroutilidad, "Bodega" => $TotalBodegas[$i]['bodega'], "descripcion" => $TotalBodegas[$i]['descripcion'], "ubicacion" => $TotalBodegas[$i]['ubicacion'], "responsable" => $TotalBodegas[$i]['responsable'], "TipoNumeracion" => $TotalBodegas[$i]['tipo_numeracion'], "desDpto" => $TotalBodegas[$i]['desdpto'], "centinela" => 1));
                $this->salida .= "	 <td align=\"center\"><a href=\"$action1\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/modificar.png\"></a></td>";
                if ($TotalBodegas[$i]['estado'] == 1)
                {
                    $this->salida .= "	 <td align=\"center\"><a href=\"$action2\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/checksi.png\"></a></td>";
                    $this->salida .= "	 <td align=\"center\"><a href=\"$action3\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/ubicacion.png\"></a></td>";
                }
                else
                {
                    $this->salida .= "	 <td align=\"center\"><a href=\"$action2\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/checkno.png\"></a></td>";
                    $this->salida .= "	 <td align=\"center\">&nbsp;</td>";
                }
                $this->salida .= "	 </tr>\n";
                $y++;
            }
            $this->salida .= "       </table><BR>";
        }
        else
        {
            $this->salida .= "       <table class=\"normal_10N\"  border=\"0\" width=\"90%\" align=\"center\" >";
            $this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON BODEGAS</td></tr>";
            $this->salida .= "       </table><BR>";
        }
        $this->salida .= "       <table class=\"normal_10N\"  border=\"0\" width=\"90%\" align=\"center\" >";
        $this->salida .= "        <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Regresa\" value=\"VOLVER\"></td></tr>";
        $this->salida .= "       </table><BR>";
        $this->salida .= " </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function ClasificacionProductoGrupo()
    {

        $this->salida = ThemeAbrirTabla('CLASIFICACION GENERAL DE LOS ITEMS DEL INVENTARIO');
        $actionTotal = ModuloGetURL('app', 'Inventarios', 'user', 'LLamaAdicionCancelacionClas');
        $this->salida .= "            <form name=\"forma\" action=\"$actionTotal\" method=\"post\">";
        $this->salida .= "    <table class=\"normal_10\"  cellspacing=\"3\"  cellpadding=\"3\"border=\"1\" width=\"90%\" align=\"center\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "			 <td>GRUPOS</td>";
        $this->salida .= "       <td>CLASES</td>";
        $this->salida .= "       <td width=\"35%\">SUBCLASES</td>";
        $this->salida .= "       </tr>";
        $y = 0;
        $m = 1;
        $totalGrupos = $this->GruposClasificacionInv();
        for ($i = 0; $i < sizeof($totalGrupos); $i++)
        {
            if ($y % 2)
            {
                $estilo = 'modulo_list_claro';
            }
            else
            {
                $estilo = 'modulo_list_oscuro';
            }
            $GrupoId = $totalGrupos[$i]['grupo_id'];
            $NombreGrupo = $totalGrupos[$i]['descripcion'];
            $this->salida .= "      <tr>";
            $this->salida .= "      <td class=\"$estilo\" width=\"30%\">$GrupoId  $NombreGrupo<BR>";
            $accionEdit = ModuloGetURL('app', 'Inventarios', 'user', 'EditarClasificacion', array("grupo" => $GrupoId, "NombreGrupo" => $NombreGrupo, "claseIn" => $ClaseId, "NombreClase" => $NombreClase, "subclase" => $SubClaseId, "NombreSubClase" => $NombreSubClase, 'bandera' => 1, 'Empresa' => $Empresa, 'NombreEmp' => $NombreEmp));
            $this->salida .= "			 <a href=\"$accionEdit\"><img border=\"0\" src=\"" . GetThemePath() . "/images/modificar.png\"></a>";
            $accionAdicClass = ModuloGetURL('app', 'Inventarios', 'user', 'AdicionarClasify', array("grupo" => $GrupoId, "NombreGrupo" => $NombreGrupo, "claseIn" => $ClaseId, "NombreClase" => $NombreClase, "subclase" => $SubClaseId, "NombreSubClase" => $NombreSubClase, 'bandera' => 1, 'Empresa' => $Empresa, 'NombreEmp' => $NombreEmp));
            $this->salida .= "				      <a href=\"$accionAdicClass\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/planblanco.png\"></a>";
            $indicaElimGrup = $this->PosibleEliminacionGrupo($GrupoId);
            if ($indicaElimGrup < 1)
            {
                $accionElim = ModuloGetURL('app', 'Inventarios', 'user', 'EliminarClasificacionSubclass', array("grupo" => $GrupoId, "NombreGrupo" => $NombreGrupo, "claseIn" => $ClaseId, "NombreClase" => $NombreClase, "subclase" => $SubClaseId, "NombreSubClase" => $NombreSubClase, 'bandera' => 1, 'Empresa' => $Empresa, 'NombreEmp' => $NombreEmp));
                $this->salida .= "				      <a href=\"$accionElim\"><img border=\"0\" src=\"" . GetThemePath() . "/images/elimina.png\"></a>";
            }
            $this->salida .= "      </td>";
            $this->salida .= "      <td colspan=\"2\" valign=\"top\">";
            $totalClases = $this->ClasesClasificacionInv($GrupoId);
            for ($x = 0; $x < sizeof($totalClases); $x++)
            {
                if ($m % 2)
                {
                    $estilo1 = 'modulo_list_claro';
                }
                else
                {
                    $estilo1 = 'modulo_list_oscuro';
                }
                $ClaseId = $totalClases[$x]['clase_id'];
                $NombreClase = $totalClases[$x]['descripcion'];
                $this->salida .= "			     <table class=\"normal_10\"  cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"100%\" align=\"center\" class=\"$estilo1\">";
                $this->salida .= "              <tr>";
                $this->salida .= "              <td width=\"50%\" class=\"$estilo1\">$ClaseId  $NombreClase<BR>";
                if ($totalClases)
                {
                    $accionEdit = ModuloGetURL('app', 'Inventarios', 'user', 'EditarClasificacion', array("grupo" => $GrupoId, "NombreGrupo" => $NombreGrupo, "claseIn" => $ClaseId, "NombreClase" => $NombreClase, "subclase" => $SubClaseId, "NombreSubClase" => $NombreSubClase, 'bandera' => 2, 'Empresa' => $Empresa, 'NombreEmp' => $NombreEmp));
                    $this->salida .= "				      <a href=\"$accionEdit\"><img border=\"0\" src=\"" . GetThemePath() . "/images/modificar.png\"></a>";
                    $accionAdicSbClass = ModuloGetURL('app', 'Inventarios', 'user', 'AdicionarClasify', array("grupo" => $GrupoId, "NombreGrupo" => $NombreGrupo, "claseIn" => $ClaseId, "NombreClase" => $NombreClase, "subclase" => $SubClaseId, "NombreSubClase" => $NombreSubClase, 'bandera' => 2, 'Empresa' => $Empresa, 'NombreEmp' => $NombreEmp));
                    $this->salida .= "				      <a href=\"$accionAdicSbClass\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/planblanco.png\" alt=\"Nuevo\"></a>";
                    $indicaElimClass = $this->PosibleEliminacionClase($GrupoId, $ClaseId);
                    if ($indicaElimClass < 1)
                    {
                        $accionElim = ModuloGetURL('app', 'Inventarios', 'user', 'EliminarClasificacionSubclass', array("grupo" => $GrupoId, "NombreGrupo" => $NombreGrupo, "claseIn" => $ClaseId, "NombreClase" => $NombreClase, "subclase" => $SubClaseId, "NombreSubClase" => $NombreSubClase, 'bandera' => 2, 'Empresa' => $Empresa, 'NombreEmp' => $NombreEmp));
                        $this->salida .= "				      <a href=\"$accionElim\" class=\"link\"><img border=\"0\" src=\"" . GetThemePath() . "/images/elimina.png\"></a>";
                    }
                }
                $this->salida .= "              </td>";
                $this->salida .= "              <td valign=\"top\" width=\"50%\">";
                $totalSubClases = $this->SubClasesClasificacionInv($GrupoId, $ClaseId);
                for ($z = 0; $z < sizeof($totalSubClases); $z++)
                {
                    $SubClaseId = $totalSubClases[$z]['subclase_id'];
                    $NombreSubClase = $totalSubClases[$z]['descripcion'];
                    $this->salida .= "			     <table class=\"normal_10\"  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"$estilo1\">";
                    if ($totalSubClases)
                    {
                        $this->salida .= "              <tr><td class=\"$estilo1\">$SubClaseId  $NombreSubClase</td>";
                        $accionEdit = ModuloGetURL('app', 'Inventarios', 'user', 'EditarClasificacion', array("grupo" => $GrupoId, "NombreGrupo" => $NombreGrupo, "claseIn" => $ClaseId, "NombreClase" => $NombreClase, "subclase" => $SubClaseId, "NombreSubClase" => $NombreSubClase, 'bandera' => 3, 'Empresa' => $Empresa, 'NombreEmp' => $NombreEmp));
                        $this->salida .= "				      <td class=\"$estilo1\" width=\"5%\"><a href=\"$accionEdit\"><img border=\"0\" src=\"" . GetThemePath() . "/images/modificar.png\"></a></td>";
                        $PermisoElim = $this->VerificarEliminacionClasificacion($GrupoId, $ClaseId, $SubClaseId);
                        if ($PermisoElim > 0)
                        {
                            $accionVerItems = ModuloGetURL('app', 'Inventarios', 'user', 'BusquedaBDProductosInventarios', array("grupo" => $GrupoId, "NomGrupo" => $NombreGrupo, "clasePr" => $ClaseId, "NomClase" => $NombreClase, "subclase" => $SubClaseId, "NomSubClase" => $NombreSubClase, "origenFun" => '1'));
                            $this->salida .= "				      <td class=\"$estilo1\" width=\"5%\"><a href=\"$accionVerItems\"><img border=\"0\" src=\"" . GetThemePath() . "/images/informacion.png\"></a></td>";
                        }
                        else
                        {
                            $accionElim = ModuloGetURL('app', 'Inventarios', 'user', 'EliminarClasificacionSubclass', array("grupo" => $GrupoId, "NombreGrupo" => $NombreGrupo, "claseIn" => $ClaseId, "NombreClase" => $NombreClase, "subclase" => $SubClaseId, "NombreSubClase" => $NombreSubClase, 'bandera' => 3, 'Empresa' => $Empresa, 'NombreEmp' => $NombreEmp));
                            $this->salida .= "				      <td class=\"$estilo1\" width=\"5%\"><a href=\"$accionElim\"><img border=\"0\" src=\"" . GetThemePath() . "/images/elimina.png\"></a></td>";
                        }
                        $this->salida .= "             </tr>";
                    }
                    else
                    {
                        $this->salida .= "              <tr><td class=\"$estilo1\">&nbsp;</td></tr>";
                    }
                    $this->salida .= "			     </table>";
                }
                $this->salida .= "              </td>";
                $this->salida .= "              </tr>";
                $this->salida .= "			     </table>";
                $m++;
            }
            $this->salida .= "       </td>";
            $this->salida .= "       </tr>";
            $y++;
        }
        $this->salida .= "			    </table><BR>";
        $this->salida .= "         <table class=\"normal_10N\"  border=\"0\" width=\"90%\" align=\"center\">";
        $accionAdicGrupo = ModuloGetURL('app', 'Inventarios', 'user', 'InsertarGrupoclasify', array("grupo" => $GrupoId, "NombreGrupo" => $NombreGrupo, "claseIn" => $ClaseId, "NombreClase" => $NombreClase, "subclase" => $SubClaseId, "NombreSubClase" => $NombreSubClase, 'Empresa' => $Empresa, 'NombreEmp' => $NombreEmp));
        $this->salida .= "				       <td><a href=\"$accionAdicGrupo\"><img border=\"0\" src=\"" . GetThemePath() . "/images/planblanco.png\"></a></td>";
        $this->salida .= "               <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
        $this->salida .="           </table><BR>";
        $this->salida .="          </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function EditarProductoEmpresa($Empresa, $NombreEmp, $grupo, $clasePr, $subclase, $NomGrupo, $NomClase, $NomSubClase, $Seleccion, $codigoProducto, $codigoPro, $descripcionPro)
    {
        $this->salida .= ThemeAbrirTabla('DATOS PRODUCTO INVENTARIOS');
        $action = ModuloGetURL('app', 'Inventarios', 'user', 'ActualizarProsuctoEmpresa', array("Empresa" => $Empresa, "NombreEmp" => $NombreEmp, "grupo" => $grupo, "NomGrupo" => $NomGrupo, "clasePr" => $claseIn, "NomClase" => $NomClase, "subclase" => $subclase, "NomSubClase" => $NomSubClase, "conteo" => $this->conteo, "Of" => $Of, "paso" => $paso, "codigoPro" => $codigoPro, "descripcionPro" => $descripcionPro, 'Seleccion' => $Seleccion, 'codigoProducto' => $codigoProducto));
        $this->salida .= "     <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->salida .= "          <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "          <tr><td class=\"modulo_table_list_title\" align=\"center\"><b>EMPRESA</b></td></tr>";
        $this->salida .= "          <tr><td class=\"modulo_list_claro\" align=\"center\" ><b>$NombreEmp</b></td></tr>";
        $this->salida .= "			    </table><BR>";
        $this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "         <tr><td colspan=\"3\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "         </td></tr>";
        $this->salida .= "			    </table><BR>";
        $DatosProd = $this->BuscarDatosProductoInv($Empresa, $codigoProducto);
        $precioVenta = $DatosProd['precio_venta'];
        $grupoContratacion = $DatosProd['grupo_contratacion_id'];
        $autorizadorCompra = $DatosProd['nivel_autorizacion_id'];
        if ($DatosProd)
        {
            $this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\" >";
            $this->salida .= "         <tr><td><fieldset><legend class=\"field\">DATOS DEL PRODUCTO</legend>";
            $this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"95%\" align=\"center\" >";
            $this->salida .= "			   <tr><td>&nbsp;</td></tr>";
            $this->salida .= "			   <tr class=\"modulo_list_claro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">CODIGO</td><td>$codigoProducto</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">DESCRIPCION</td><td width=\"30%\">" . $DatosProd['descripcion'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">DESCRIPCION. ABVDA</td><td width=\"30%\">" . $DatosProd['descripcion_abreviada'] . "</td>";
            $this->salida .= "			   </tr>";
            $DatosClasify = $this->BuscarDatosClasifyProd($Empresa, $codigoProducto);
            $grupoCod = $DatosClasify['grupo_id'];
            $claseInCod = $DatosClasify['clase_id'];
            $subclaseCod = $DatosClasify['subclase_id'];
            $NomGrupoCod = $DatosClasify['desgr'];
            $NomClaseCod = $DatosClasify['desclas'];
            $NomSubClaseCod = $DatosClasify['dessubclas'];
            $this->salida .= "			   <tr class=\"modulo_list_oscuro\">";

            $this->salida .= "			   <td width=\"15%\" class=\"label\">GRUPO</td><td>$grupoCod $NomGrupoCod</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">CLASE</td><td>$claseInCod $NomClaseCod</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">SUBCLASE</td><td>$subclaseCod $NomSubClaseCod</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr class=\"modulo_list_claro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">FABRICANTE</td><td>" . $DatosProd['fabricante'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">UNIDAD</td><td>" . $DatosProd['unidad'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">IVA</td><td>" . $DatosProd['porc_iva'] . "</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr class=\"modulo_list_oscuro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">EXIST. MINIMA</td><td><input size=\"10\" type=\"text\" name=\"existencia_minima\" value=\"" . $DatosProd['existencia_minima'] . "\"></td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">EXIST. MAXIMA</td><td><input size=\"10\" type=\"text\" name=\"existencia_maxima\" value=\"" . $DatosProd['existencia_maxima'] . "\"></td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">EXISTENCIAS</td><td>" . $DatosProd['existencia'] . "</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr class=\"modulo_list_claro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">COSTO ANTERIOR</td><td>" . $DatosProd['costo_anterior'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">COSTO</td><td>" . $DatosProd['costo'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">COSTO PENULTIMA COMPRA</td><td>" . $DatosProd['costo_penultima_compra'] . "</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr class=\"modulo_list_oscuro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">COSTO ULTIMA COMPRA</td><td>" . $DatosProd['costo_ultima_compra'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">PRECIO VENTA ANTERIOR</td><td>" . $DatosProd['precio_venta_anterior'] . "</td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">PRECIO VENTA</td><td><input size=\"10\" type=\"text\" name=\"precioVenta\" value=\"$precioVenta\"></td>";
            $this->salida .= "			   <input type=\"hidden\" name=\"PrecioVentaActual\" value=\"" . $DatosProd['precio_venta'] . "\">";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr class=\"modulo_list_claro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">PRECIO MINIMO</td><td><input size=\"10\" type=\"text\" name=\"precioMinimo\" value=\"" . $DatosProd['precio_minimo'] . "\"></td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">PRECIO MAXIMO</td><td><input size=\"10\" type=\"text\" name=\"precioMaximo\" value=\"" . $DatosProd['precio_maximo'] . "\"></td>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">&nbsp;</td><td>&nbsp;</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr class=\"modulo_list_oscuro\">";
            if ($DatosProd['sw_vende'] == 1)
            {
                $check = 'checked';
            }
            if ($DatosProd['sw_servicio'] == 1)
            {
                $check1 = 'checked';
            }
            //if($DatosProd['sw_vende']==1){$Img='endturn.png';}else{$Img='delete.gif';}
            //if($DatosProd['sw_servicio']==1){$Img1='endturn.png';}else{$Img1='delete.gif';}
            $this->salida .= "			   <td width=\"15%\" class=\"label\">PRODUCTO VENTA</td><td align=\"center\"><input type=\"checkbox\" name=\"PtoVende\" $check></td>";
            //<img border=\"0\"    src=\"".GetThemePath()."/images/$Img\">
            $this->salida .= "			   <td width=\"15%\" class=\"label\">PRODUCTO SIN<BR>MANEJO DE STOCK</td><td align=\"center\"><input type=\"checkbox\" name=\"PtoService\" $check1></td>";
            //<img border=\"0\" src=\"".GetThemePath()."/images/$Img1\">
            $this->salida .= "			   <td>&nbsp;</td>";
            $this->salida .= "			   <td>&nbsp;</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr class=\"modulo_list_claro\">";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">GRUPO CONTRATACION</td>";
            $this->salida .= "		      <td colspan=\"5\"><select name=\"grupoContratacion\"  class=\"select\">";
            $GruposContratacion = $this->TiposDeGruposContratacion();
            $this->MostrarSin($GruposContratacion, 'False', $grupoContratacion);
            $this->salida .= "          </select></td></tr>";
            $this->salida .= "			   <tr class=\"modulo_list_oscuro\">";
            $this->salida .= "			    <td width=\"15%\" class=\"label\">AUTORIZADOR COMPRA</td>";
            $this->salida .= "          <td colspan=\"5\"><select name=\"autorizadorCompra\"  class=\"select\">";
            $AutorizacionesCompra = $this->TiposAutorizacionesCompra();
            $this->MostrarSin($AutorizacionesCompra, 'False', $autorizadorCompra);
            $this->salida .= "         </select></td></tr>";
            $this->salida .= "			   <td>&nbsp;</td>";
            $this->salida .= "			   <td>&nbsp;</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <td width=\"15%\" class=\"label\">&nbsp;</td><td>&nbsp;</td>";
            $this->salida .= "			   </tr>";
            $this->salida .= "			   <tr><td>&nbsp;</td></tr>";
            $this->salida .= "			   </table>";
            $this->salida .= "		     </fieldset></td></tr>";
            $this->salida .= "        </table><BR>";
        }
        $this->salida .= "       <table class=\"normal_10N\"  border=\"0\" width=\"90%\" align=\"center\" >";
        $this->salida .= "        <tr><td align=\"center\" class=\"input-submit\"><input class=\"input-submit\" type=\"submit\" name=\"Regresa\" value=\"VOLVER\">";
        $this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Actualizar\" value=\"ACTUALIZAR\"></td></tr>";
        $this->salida .= "       </table>";
        $this->salida .= " </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //====================================================================================
    function formaReporteBusqueda() // by Alvaro
    {
        $act = ModuloGetUrl("app", "Inventarios", "user", "formaReporteBusqueda", array('Empresa' => $_REQUEST['Empresa'], 'NombreEmp' => $_REQUEST['NombreEmp'], "Nit" => $_REQUEST['Nit'], "Direccion" => $_REQUEST['Direccion'], "Telefono" => $_REQUEST['Telefono']));
        $mainMenu = ModuloGetURL('app', 'Inventarios', 'user', 'MenuInventariosPrincipal', array("Empresa" => $_REQUEST['Empresa'], "NombreEmp" => $_REQUEST['NombreEmp'], "Nit" => $_REQUEST['Nit'], "Direccion" => $_REQUEST['Dir'], "Telefono" => $_REQUEST['Tel']));

        include_once("app_modules/Inventarios/RemoteXajax/AJAX.php");
        $this->SetXajax(Array("actualizarSelectProductos"));

        $linea = "---------------------";
        $this->salida.=ThemeAbrirTabla("CONSULTA VENCIMIENTOS PRODUCTOS");
        //*****************************Tabla Busqueda********************************************************
        //-------Fieldset---------------
        $this->salida.="<table border=\"0\" width=\"70%\" align=\"center\" class=\"normal_10\">";
        $this->salida.="<tr><td><fieldset><legend class=\"field\"> FILTRO PRODUCTOS </legend>";
        //---------------------------

        $this->salida.="<table border=\"1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida.="  <tr>";
        $this->salida.="    <td width=\"22%\" align=\"right\" class=\"modulo_table_list_title\"> BUSCAR PRODUCTO POR: </td>";

        //--------------------------Form-----------------------------------------------
        $this->salida.="<form name=\"form_prod\" action=\"$act\" method=\"post\" >";

        $a = "";
        $b = "checked";
        if ($_REQUEST['r_busqueda'] == 'c')
            $a = "checked";
        if ($_REQUEST['r_busqueda'] == 'ca')
            $ca = "checked";
        if ($_REQUEST['r_busqueda'] == 'd')
            $b = "checked";

        $this->salida.="    <td width=\"30%\" class=\"modulo_list_claro\">";
        $this->salida.="      <input type=\"radio\" name=\"r_busqueda\" value=\"c\" $a> CODIGO </input><br>";
        $this->salida.="      <input type=\"radio\" name=\"r_busqueda\" value=\"ca\" $ca> CODIGO ALTERNO </input><br>";
        $this->salida.="      <input type=\"radio\" name=\"r_busqueda\" value=\"d\" $b> DESCRIPCION </input><br>";
        $this->salida.="    </td>";

        $this->salida.="    <td align=\"right\" width=\"8%\" class=\"modulo_table_list_title\">  VALOR: </td>";

        $this->salida.="    <td width=\"40%\" class=\"modulo_list_claro\">";
        $this->salida.="      <input type=\"text\" name=\"t_valorp\" id=\"t_valorp\" class=\"input-text\" size=\"30%\" value=\"" . $_REQUEST['t_valorp'] . "\">\n";
        $this->salida.="      <input type=\"button\" name=\"b_buscarp\" value=\"Buscar\"  onClick=\"actualizarProductos()\" class=\"input-submit\">";
        $this->salida.="    </td>";
        $this->salida.="  </tr>";
        //--------------------------Productos-----------------------------------
        $this->salida.="  <tr>";
        $this->salida.="    <td align=\"right\"  class=\"modulo_table_list_title\"> PRODUCTO: </td>";
        $this->salida.="    <td colspan=\"3\" class=\"modulo_list_claro\">";
        $this->salida.="      <div id=\"div_p\">";
        $this->salida.="      <select name=\"s_productos\" id=\"s_productos\" width=\"60%\" onChange=\"asignarProducto()\" class=\"select\">";
        $this->salida.="        <option value=\"-1\"> " . $linea . $linea . $linea . "TODOS" . $linea . $linea . $linea . " </option>";

        $matrixp = $this->consultaProductos("t"); //Trae todos los productos
        $sel = "";

        foreach ($matrixp as $key => $val)
        {
            if ($_REQUEST['prod_hide'] == $val["codigo"])
                $sel = "selected";
            else
                $sel = "";

            $this->salida.="  	<option value=\"" . $val["codigo"] . "\" " . $sel . "> " . substr($val["descripcion"], 0, 50) . "</option>";
        }

        $this->salida.="      </select>";
        $this->salida.="      </div>";
        $this->salida.="    </td>";
        $this->salida.="  </tr>";
        //----------------------Bodegas---------------------------------------
        $this->salida.="  <tr>";
        $this->salida.="    <td align=\"right\" class=\"modulo_table_list_title\"> BODEGA: </td>";
        $this->salida.="    <td colspan=\"3\" class=\"modulo_list_claro\">";
        $this->salida.="      <select name=\"s_bodegas\" width=\"60%\" class=\"select\">";
        $this->salida.="        <option value=\"-1\"> " . $linea . "TODOS" . $linea . " </option>";

        $matrixb = $this->consultaBodegas(); //Trae todas las bodegas

        foreach ($matrixb as $key => $val)  //Llena el Select de Bodegas
        {
            if ($_REQUEST['s_bodegas'] == $val["bodega"])
                $sel = "selected";
            else
                $sel = "";

            $this->salida.="        <option value=\"" . $val["bodega"] . "\"  " . $sel . "> " . substr($val["descripcion"], 0, 50) . " </option>";
        }

        $this->salida.="      </select>";
        $this->salida.="    </td>";
        $this->salida.="  </tr>";
        //------------------PERIODO-------------------------------------------
        if ($_REQUEST['s_periodo'] == "igual")
        {
            $selI = "selected";
            $selA = "";
            $selD = "";
        }

        if ($_REQUEST['s_periodo'] == "antes")
        {
            $selI = "";
            $selA = "selected";
            $selD = "";
        }

        if ($_REQUEST['s_periodo'] == "despues")
        {
            $selI = "";
            $selA = "";
            $selD = "selected";
        }

        $this->salida.="  <tr>";
        $this->salida.="    <td align=\"right\" class=\"modulo_table_list_title\"> PERIODO VENCIMIENTO: </td>";
        $this->salida.="    <td colspan=\"3\" class=\"modulo_list_claro\">";
        $this->salida.="      <select name=\"s_periodo\" id=\"s_periodo\" class=\"select\">";
        $this->salida.="        <option value=\"antes\" " . $selA . "> Antes de </option>";
        $this->salida.="        <option value=\"igual\" " . $selI . "> Igual a </option>";
        $this->salida.="        <option value=\"despues\" " . $selD . "> Despues de </option>";
        $this->salida.="      </select>";

        $this->salida.="      <input type=\"text\" name=\"t_fecha\" size=\"10\" class=\"input-text\" value=\"" . $_REQUEST['t_fecha'] . "\">";
        $this->salida.="      <sub>" . ReturnOpenCalendario("form_prod", "t_fecha", "/") . "</sub>";
        $this->salida.="    </td>";
        $this->salida.="  </tr>";
        $this->salida.="</table>";

        //-------------Fin Fieldset-----------------------------------------------
        $this->salida.="</fieldset></td></tr>";
        $this->salida.="</table>";
        //--------------------------Valor escondido del producto---------------
        if ($_REQUEST["b_consultar"])// Si presionò el botòn consultar
            $this->salida.="		<input type=\"hidden\" name=\"prod_hide\" id=\"prod_hide\"  value=\"" . $_REQUEST['prod_hide'] . "\">";
        else
            $this->salida.="		<input type=\"hidden\" name=\"prod_hide\" id=\"prod_hide\"  value=\"-1\">";

        //-----------------------Boton Consultar--------------------------------------
        $this->salida.="<table align=\"center\" border=\"0\">";
        $this->salida.="<tr>";
        $this->salida.="<td>";
        $this->salida.="  <input type=\"submit\" name=\"b_consultar\" id=\"b_consultar\" value=\"CONSULTAR\"  class=\"input-submit\" >";
        $this->salida.="</td>";
        $this->salida.="</form>"; //Fin formulario "form_prod"
        //----------------------Boton Volver-----------------------------------------
        $this->salida.="<form name=\"form_volver\" action=\"$mainMenu\" method=\"post\">";
        $this->salida.="<td>";
        $this->salida.="		<input type=\"submit\" name=\"b_volver\" id=\"b_volver\" value=\"VOLVER\"  class=\"input-submit\">";
        $this->salida.="</td>";
        $this->salida.="</form>";

        $this->salida.="</tr>";
        $this->salida.="</table>";

        $this->salida.="<br>";

        //------------------------------------SCRIPTS-----------------------------------------------
        $this->salida .= "<script>\n";
        $this->salida .= "	function actualizarProductos()\n";
        $this->salida .= "	{ var tipo_busqueda;\n";
        $this->salida .= "    for(i=0; i<document.form_prod.r_busqueda.length; i++)  \n";
        $this->salida .= "    { if(document.form_prod.r_busqueda[i].checked) \n";
        $this->salida .= "      { tipo_busqueda=document.form_prod.r_busqueda[i].value;\n";
        $this->salida .= "        break;\n";
        $this->salida .= "      }\n";
        $this->salida .= "    } \n";
        $this->salida .= "	  var valor=document.form_prod.t_valorp.value;\n";
        $this->salida .= "	  xajax_actualizarSelectProductos(tipo_busqueda,valor);\n";
        $this->salida .= "	}\n";
        $this->salida .= "	function asignarProducto()\n";
        $this->salida .= "	{ \n"; //alert('V:'+s_productos.value);
        $this->salida .= "	  document.form_prod.prod_hide.value=document.getElementById('s_productos').value;\n";
        $this->salida .= "	}\n";
        $this->salida .= "</script>\n";

        if ($_REQUEST["b_consultar"])// Si presionò el botòn consultar
            $this->mostrarPaginador();

        $this->salida.=ThemeCerrarTabla();

        return true;
    }

// forma ReporteBusqueda
    //=================================================================================
    function mostrarPaginador()
    {
        $vec = $this->consultaProductosVencimientos();

        if (count($vec) <= 0)
            $this->salida.="<p align=\"center\" class=\"label\"> <font color=\"red\"> NO SE ENCONTRARON INCIDENCIAS </font> </p>";
        else
        {
            $this->salida.="<table border=\"0\" align=\"center\" width=\"90%\" class=\"modulo_table_list\">";
            //--------------------------
            $this->salida.="  <tr>";
            $this->salida.="    <td class=\"modulo_table_list_title\" align=\"center\">  <b>BODEGA</b>  </td>";
            $this->salida.="    <td class=\"modulo_table_list_title\" align=\"center\">  <b>CODIGO PRODUCTO</b> </td>";
            $this->salida.="    <td class=\"modulo_table_list_title\" align=\"center\">  <b>PRODUCTO</b> </td>";
            //$this->salida.="    <td class=\"modulo_table_list_title\" align=\"center\">  <b>EXISTENCIA</b> </td>";
            $this->salida.="    <td class=\"modulo_table_list_title\" align=\"center\" width=\"7%\">  <b>LOTE</b> </td>";
            $this->salida.="    <td class=\"modulo_table_list_title\" align=\"center\" width=\"10%\">  <b>CANTIDAD EN LOTE</b> </td>";
            $this->salida.="    <td class=\"modulo_table_list_title\" align=\"center\" width=\"9%\">  <b>FECHA VENCIMIENTO</b> </td>";
            $this->salida.="  </tr>";

            $color = "";
            //--------------------------------------------
            foreach ($vec as $key => $val)
            {
                if ($key % 2 == 0)
                    $color = "\"modulo_list_claro\"";
                else
                    $color = "modulo_list_oscuro";

                $this->salida.="<tr>";
                $this->salida.="  <td class=" . $color . ">" . $val["b_desc"] . "</td>";
                $this->salida.="  <td class=" . $color . ">" . $val["codigo_producto"] . "</td>";
                $this->salida.="  <td class=" . $color . ">" . $val["descripcion"] . "--[(" . $val["subclase"] . ")]--" . $val["unidad_id"] . "-(" . $val["contenido_unidad_venta"] . ") (" . $val["clase"] . ")</td>";
                //$this->salida.="  <td class=".$color.">".$val["existencia"]."</td>";
                $this->salida.="  <td class=" . $color . ">" . $val["lote"] . "</td>";
                $this->salida.="  <td class=" . $color . ">" . $val["cantidad"] . "</td>";
                $this->salida.="  <td class=" . $color . ">" . $val["fecha_vencimiento"] . "</td>";
                $this->salida.="</tr>";
            }

            $this->salida.="</table>";

            //----------------Paginador---------------------------------------------------------------------
            $paramet = array("Empresa" => $_REQUEST['Empresa'],
                "prod_hide" => $_REQUEST['prod_hide'],
                "s_bodegas" => $_REQUEST['s_bodegas'],
                "t_fecha" => $_REQUEST['t_fecha'],
                "s_periodo" => $_REQUEST['s_periodo'], "b_consultar" => $_REQUEST["b_consultar"]);

            $action = ModuloGetUrl("app", "Inventarios", "user", "formaReporteBusqueda", $paramet);
            IncludeClass('ClaseHTML');
            $paginador = new ClaseHTML();

            $this->salida.=$paginador->ObtenerPaginado($this->cont, $this->paginaActual, $action);

            //----------------Reporte---------------------------------------------------------------------
            $param; //Parametros para el reporte
            $param['empresa_id'] = $_REQUEST['Empresa'];
            $param['empresa_nombre'] = $_REQUEST['NombreEmp'];
            $param['empresa_nit'] = $_REQUEST['Nit'];
            $param['empresa_direccion'] = $_REQUEST['Direccion'];
            $param['empresa_telefono'] = $_REQUEST['Telefono'];
            $param['producto'] = $_REQUEST['prod_hide'];
            $param['bodega'] = $_REQUEST['s_bodegas'];
            $param['fecha'] = $_REQUEST['t_fecha'];
            $param['periodo'] = $_REQUEST['s_periodo'];

            $reporte = new GetReports();
            $mostrar = $reporte->GetJavaReport('app', 'Inventarios', 'inventarios_vencimientos', $param, array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $funcion = $reporte->GetJavaFunction();

            $this->salida.="<p align=\"center\">";
            $this->salida.="  <a href=\"javascript:" . $funcion . "\" class=\"label\">  IMPRIMIR REPORTE  </a>";
            $this->salida.="</p>";
            $this->salida.=$mostrar;
        }
        return true;
    }

}

//fin clase user
?>
