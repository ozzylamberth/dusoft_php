<?php

/**
 * $Id: app_Central_de_Autorizaciones_adminclasses_HTML.php,v 1.1.1.1 2009/09/11 20:36:19 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Usuarios del Sistema
 */

/**
 * Contiene los metodos visuales para realizar la administracion de usuarios
 */
class app_Central_de_Autorizaciones_adminclasses_HTML extends app_Central_de_Autorizaciones_admin {

    /**
     * Constructor de la clase app_Usuarios_user_HTML
     * El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
     * a la clase app_Usuarios_user quien se encarga de el tratamiento
     * de la base de datos.
     */
    function app_Central_de_Autorizaciones_admin_HTML() {
        $this->salida = '';
        $this->app_Central_de_Autorizaciones_admin();
        return true;
    }

    /**
     * Funcion donde se visualiza el menu de usuario.
     * @return boolean
     */
    function Menu() {
        $this->salida.= ThemeAbrirTabla('MENU ADMINISTRADOR CENTRAL DE IMPRESION');

        $this->salida.="<br><table border=\"0\"  class=\"modulo_table_list\"  align=\"center\"   width=\"80%\" >";
        $this->salida.="<tr>";
        $this->salida .= "<td align=\"center\" class=\"modulo_table_title\" >ADMINISTRACION CAJA</td><td  align=\"center\" class=\"modulo_table_title\" >DEPARTAMENTO</td>";
        $this->salida.="</tr>";
        $this->salida.="<tr>";
        $this->salida .= "<td class=\"modulo_list_oscuro\"  align=\"center\">CAJA GENERAL</td><td class=\"modulo_list_oscuro\"  align=\"center\">COD&nbsp;: " . $_SESSION['USER_ADMIN_MOD']['DPTO'] . "&nbsp;&nbsp;&nbsp;&nbsp;" . $_SESSION['USER_ADMIN_MOD']['NOMBRE'] . "";
        $this->salida.="</td>";
        $this->salida.="</table>";

        $this->salida.="<br><table border=\"0\"  class=\"modulo_table_list\"  align=\"center\"   width=\"80%\" >";
        $this->salida.="<tr>";
        $this->salida .= "<td colspan=\"2\"   align=\"center\" class=\"modulo_table_title\" >EVENTOS DE USUARIOS</td>";
        $this->salida.="</tr>";
        $ac = ModuloGetURL('app', 'Central_de_Autorizaciones', 'admin', 'RetornarPermisos');
        $ax = ModuloGetURL('system', 'Usuarios', 'user', 'LlamaFormaModificarPasswd');

        $this->salida.="<tr>";
        $this->salida .= "<td   colspan=\"2\"  class=\"modulo_list_claro\"  align=\"center\"><a href=\"$ac\">ADICIONAR USUARIO</a>";
        $this->salida.="</td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.="<table border='0' align='center'>";
        $action3 = ModuloGetURL('app', 'Central_de_Autorizaciones', 'admin', 'Retornar');
        $this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
        $this->salida.="	<tr>";
        $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Menu\"></td></tr>";
        $this->salida.="</table>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

}

//fin clase user
?>

