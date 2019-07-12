<?php

class app_Mensajeria_controller extends classModulo {

    function app_Mensajeria_controller() {
        return true;
    }

    function main() {
        $this->SetXajax(array("procesarformulario", "glectura", "envios", "procesarformularioMof", "filtro_mensaje"), "app_modules/Mensajeria/RemoteXajax/Funciones.php");
        $objSql = AutoCarga::factory("ConsultasSql", "classes", "app", "Mensajeria");
        $datosDP = $objSql->ConsultarPermisos();
        if ($datosDP[0]['sw'] == 1) {
            $salida .= $this->Menu();
        } else {
            $objHtml2 = AutoCarga::factory("Agregar_Actual_HTML", "views", "app", "Mensajeria");
            $salida .= $objHtml2->LeerActualizaciones();
        }
        $this->salida = $salida;
        return true;
    }

    function leer() {
        $this->SetXajax(array("procesarformulario", "glectura", "procesarformularioMof"), "app_modules/Mensajeria/RemoteXajax/Funciones.php");
        $objHtml2 = AutoCarga::factory("Agregar_Actual_HTML", "views", "app", "Mensajeria");
        $salida .= $objHtml2->LeerActualizaciones();
        $this->salida = $salida;
        return true;
    }

    function crear() {

        $this->SetXajax(array("procesarformulario", "glectura", "envios", "eliminar", "procesarformularioMof"), "app_modules/Mensajeria/RemoteXajax/Funciones.php");
        $objSql = AutoCarga::factory("ConsultasSql", "classes", "app", "Mensajeria");
        $objHtml = AutoCarga::factory("Agregar_Actual_HTML", "views", "app", "Mensajeria");
        $idy = UserGetUID();

        if ($_REQUEST['modif'] == 'guardar') {
            $cadenaCar = explode(",", $_REQUEST['cargos']);
            $cadenaChe = explode(",", $_REQUEST['chequeados']);
            $datosDP = $objSql->IngrActualizacion($idy, $_REQUEST['asunto'], $_REQUEST['descripcion'], $_REQUEST['caducidad']);
            $id = $objSql->ConsultarIDActulizacion();
            for ($i = 0; $i < sizeof($cadenaCar); $i++) {
                if ($cadenaChe[$i] != '') {
                    $carg = $objSql->Ingrcontrolar_x_perfil($id[0]['max'], $cadenaCar[$i], $cadenaChe[$i]);
                }
            }
            $_REQUEST['asunto'] = '';
            $_REQUEST['descripcion'] = '';
            $_REQUEST['caducidad'] = '';
            $_REQUEST['actualizacion_id'] = '';
            $_REQUEST['cargos'] = '';
            $_REQUEST['chequeados'] = '';
        }
        if ($_REQUEST['asunto'] != '' && $_REQUEST['modif'] == 'Modificar') {
            $cadenaCar = explode(",", $_REQUEST['cargos']);
            $cadenaChe = explode(",", $_REQUEST['chequeados']);
            $datosDP = $objSql->UpdateActualizacion($idy, $_REQUEST['asunto'], $_REQUEST['descripcion'], $_REQUEST['caducidad'], $_REQUEST['actualizacion_id']);
            $delete = $objSql->Deletecontrolar_x_perfil($_REQUEST['actualizacion_id']);

            if ($delete == 1) {
                for ($i = 0; $i < sizeof($cadenaCar); $i++) {
                    if ($cadenaChe[$i] != '') {
                        $carg = $objSql->Ingrcontrolar_x_perfil($_REQUEST['actualizacion_id'], $cadenaCar[$i], $cadenaChe[$i]);
                    }
                }
            }
            $_REQUEST['asunto'] = '';
            $_REQUEST['descripcion'] = '';
            $_REQUEST['caducidad'] = '';
            $_REQUEST['actualizacion_id'] = '';
        }

        $salida .= $objHtml->agregar();
        $this->salida = $salida;
        return true;
    }

    function Menu() {
        $html.="<script>
                 function formulario()
                  { 
                   crear();
                  }
                </script>";
        $vermenu = '1';

        $action1 = ModuloGetURL('system', 'Menu', 'user', 'main');
        $action2 = ModuloGetURL('app', 'Mensajeria', 'controller', 'leer', array('vermenu' => $vermenu));
        $action3 = ModuloGetURL('app', 'Mensajeria', 'controller', 'crear');
        $html .=" <input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\">";
        $html .=" </form>";
        $this->salida .= ThemeAbrirTabla('ACTUALIZACIONES DEL SISTEMA');
        $this->salida .= "		<table width=\"35%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "			<tr><td class=\"modulo_table_list_title\" align=\"center\">MEN&Uacute;</td></tr>\n";
        $this->salida .= "			<tr><td class=\"modulo_list_claro\" align=\"center\"><a href=$action3 class=\"link\"><b>AGREGAR ACTUALIZACIONES</b></a></td></tr>\n";
        $this->salida .= "			<tr><td class=\"modulo_list_claro\" align=\"center\"><a href=$action2 class=\"link\"><b>VER ACTUALIZACIONES</b></a></td></tr>\n";
        $this->salida .= "		</table>\n";
        $this->salida .= "		<br>\n";
        $this->salida .= "		<table align=\"center\" width=\"35%\">\n";
        $this->salida .= "			<tr><td align=\"center\">\n";
        $this->salida .= " <form name=\"formavolver\" method=\"POST\" action=\"$action1\">";
        $this->salida .= "      <input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\">";
        $this->salida .= " </form>";
        $this->salida .= "			</td></tr>\n";
        $this->salida .= "		</table>\n";
        $this->salida .= ThemeCerrarTabla();
        return $this->salida;
    }

}

?>
