<?php

IncludeClass("ClaseHTML");

class Agregar_Actual_HTML extends app_ActualizacionPreciosProductos_controller {

    function Agregar_Actual_HTML() {
        return true;
    }

    //Vista renderizada cuando se ingresa al modulo
    function VistaInicio($productos = array(), $criterio = '', $termino = '', $conteo, $pagina, $paginador, $offset){

        $pgn = AutoCarga::factory("ClaseHTML");

        $action1 = ModuloGetURL('system', 'Menu', 'user', 'main');

        $salida .= ThemeAbrirTabla('ACTUALIZACION PRECIOS PRODUCTOS');
        $salida .= "<form name=\"buscador_productos\" id=\"pedido\" method=\"post\">\n";
        $salida .= "    <table width=\"35%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "      <tr><td class=\"modulo_table_list_title\" align=\"center\" colspan=\"3\">BUSCAR PRODUCTOS</td></tr>\n";
        $salida .= "      <tr class=\"modulo_list_claro\">";
        $salida .= "        <td  align=\"center\">
                              <select id='criterio' name=\"criterio\">   
                                <option value=\"codigo\">C&oacute;digo</option>
                                <option value=\"nombre\">Nombre</option>
                                <option value=\"molecula\">Molecula</option>
                              </select>
                            </td>";
        $salida .= "        <td><input type=\"text\" value=\"\" name=\"termino\" id=\"tercero_id\" class=\"input-text\"></td>";
        $salida .= "        <td align=\"center\" colspan=\"\"><input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\"></td>";
        $salida .= "      </tr>\n";
        $salida .= "    </table>\n";
        $salida .= "<form>";
        $salida .= "<br>";

        //Tabla resultado de busqueda de productos
        $salida .= "<form name=\"buscador_productos\" id=\"pedido\" method=\"post\">\n";
        $salida .= "    <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "      <tr>
                            <td class=\"modulo_table_list_title\" align=\"center\">C&oacute;digo</td>
                            <td class=\"modulo_table_list_title\" align=\"center\">Nombre</td>
                            <td class=\"modulo_table_list_title\" align=\"center\">Mol&eacute;cula</td>
                            <td class=\"modulo_table_list_title\" align=\"center\">Precio Regulado</td>
                            <td class=\"modulo_table_list_title\" align=\"center\">Acci&oacute;n</td>
                          </tr>\n";
        //Renderizado de los productos encontrados (filas de la tabla de resultado de busqueda)
        $conteo = 1;
        foreach ($productos as $producto) {
          $parametros = array(
            'criterio' => $criterio,
            'termino' => $termino,
            'codigo_producto' => $producto['codigo_producto'],
            'nombre_producto' => $producto['nombre_producto'],
            'molecula_producto' => $producto['molecula_producto'],
            'precio_regulado_producto' => $producto['precio_regulado_producto'],
            'offsetBusqueda' => $offset
          );
          $urlBotonEditar = ModuloGetURL('app', 'ActualizacionPreciosProductos', 'controller', 'ActualizarPrecioRegulado', $parametros);
          $clase = $conteo%2 == 0? "class=\"modulo_list_oscuro\"" : "class=\"modulo_list_claro\"";
          $salida.= sprintf("<tr ".$clase."><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td align=\"center\"><a href='%s'>Editar</a></td></tr>", $producto['codigo_producto'], $producto['nombre_producto'], $producto['molecula_producto'], $producto['precio_regulado_producto'], $urlBotonEditar);

          $conteo++;
        }

        $salida .= "    </table>\n";
        $salida .= "</form>\n";
        $salida .= "    ".$pgn->ObtenerPaginado($conteo,$pagina,$paginador);

        $salida .= "    <br>\n";
        $salida .= "    <table align=\"center\" width=\"35%\">\n";
        $salida .= "      <tr><td align=\"center\">\n";
        $salida .= "        <form name=\"formavolver\" method=\"POST\" action=\"$action1\">";
        $salida .= "          <input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\">";
        $salida .= "        </form>";
        $salida .= "      </td></tr>\n";
        $salida .= "    </table>\n";

        $salida .= ThemeCerrarTabla();

        return $salida;
    }

    function FormularioActualizacionPrecioProducto($precio_regulado_producto = '', $logs = array(), $conteo = 0, $pagina = 0, $paginador){
      $request = $_REQUEST;
      $precio_regulado_producto = !empty($precio_regulado_producto) || $precio_regulado_producto == "0"? $precio_regulado_producto : $request['precio_regulado_producto'];

      $salida = "
      <script type='text/javascript'>
        function soloNumeros(e){
          var teclaPulsada=window.event ? window.event.keyCode:e.which;
          var valor=document.getElementById('nuevo_precio_regulado_producto').value;
          if(teclaPulsada==45 && valor.indexOf('-')==-1) {
              document.getElementById('nuevo_precio_regulado_producto').value='-'+valor;
          }
          console.log(teclaPulsada);
          if(teclaPulsada==13 || teclaPulsada==8 || teclaPulsada==0 ||  (teclaPulsada==46 && valor.indexOf('.')==-1)) {
              return true;
          }
          return /\d/.test(String.fromCharCode(teclaPulsada));
        }
      </script>";

      $pgn = AutoCarga::factory("ClaseHTML");
      
      $urlVolver = ModuloGetURL('app', 'ActualizacionPreciosProductos', 'controller', 'main', array('criterio' => $request['criterio'], 'termino' => $request['termino'], 'offsetBusqueda'=> $request['offsetBusqueda']));
      $salida .= ThemeAbrirTabla('ACTUALIZACION PRECIO REGULADO');
      //Formulario de edicion
      $salida .= "<form name=\"actualizar_precio_regulado\" id=\"pedido\" method=\"post\">\n";
      $salida .= "    <table width=\"35%\" cellspacing=\"2\" cellpadding=\"3\" border=\"0\" align=\"center\">\n";
      $salida .= "      <tr>";
      $salida .= "        <td class=\"modulo_table_list_title\" align=\"center\" alignleftn=\"center\">
                           CODIGO
                          </td>";
      $salida .= "        <td class=\"modulo_list_claro\">".$request['codigo_producto']."</td>";
      $salida .= "      </tr><tr>";
      $salida .= "        <td class=\"modulo_table_list_title\" align=\"center\" alignleftn=\"center\">
                            NOMBRE
                          </td>";
      $salida .= "        <td class=\"modulo_list_claro\">".$request['nombre_producto']."</td>";
      $salida .= "      </tr><tr>"; 
      $salida .= "        <td class=\"modulo_table_list_title\" align=\"center\" alignleftn=\"center\">
                            MOLECULA
                          </td>";
      $salida .= "        <td class=\"modulo_list_claro\">".$request['molecula_producto']."</td>";
      $salida .= "      </tr><tr>";
      $salida .= "        <td class=\"modulo_table_list_title\" align=\"center\" alignleftn=\"center\">
                            PRECIO REGULADO  
                          </td>";
      $salida .= "        <td class=\"modulo_list_claro\">".$precio_regulado_producto."</td>";
      $salida .= "      </tr><tr>";
      $salida .= "        <td class=\"modulo_table_list_title\" align=\"center\" alignleftn=\"center\">
                            NUEVO PRECIO REGULADO
                          </td>";
      $salida .= "        <td class=\"modulo_list_claro\"><input style=\"width:100%;\" type=\"text\" id=\"nuevo_precio_regulado_producto\" name=\"nuevo_precio_regulado_producto\" maxlength=\"15\" onkeypress=\"return soloNumeros(event);\"></td>";
      $salida .= "      </tr><tr>";
      $salida .= "        <td class=\"modulo_list_claro\" colspan=\"2\" align=\"center\"> <input type=\"submit\" class=\"input-submit\" name=\"Guardar\" value=\"Guardar\"> </td>";
      $salida .= "      </tr>\n";
      $salida .= "    </table>\n";
      $salida .= "    <input type=\"hidden\" name=\"precio_regulado_producto\" value=\"".$precio_regulado_producto."\">";
      $salida .= "    <input type=\"hidden\" name=\"codigo_producto\" value=\"".$request['codigo_producto']."\">";
      $salida .= "</form>";
      $salida .= "<br>";

      //tabla historial de actualizaciones 
      $salida .= "    <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "      <tr><td class=\"modulo_table_list_title\" colspan=\"4\" align=\"center\">HISTORIAL DE ACTUALIZACIONES</td></tr>";
      $salida .= "      <tr>
                          <td class=\"modulo_table_list_title\" align=\"center\">Usuario</td>
                          <td class=\"modulo_table_list_title\" align=\"center\">Valor Anterior</td>
                          <td class=\"modulo_table_list_title\" align=\"center\">Valor Actual</td>
                          <td class=\"modulo_table_list_title\" align=\"center\">Fecha</td>
                        </tr>\n";
      //Renderizado de filas del historial de actualizaciones
      foreach ($logs as $log) {
        $salida.= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td></tr>", $log['nombre_usuario'], $log['anterior'], $log['actual'], $this->FechaStamp($log['fecha'])." ".$this->HoraStamp($log['fecha']));
      }
      $salida .= "  </table>\n<br>";
      $salida .= "    ".$pgn->ObtenerPaginado($conteo,$pagina,$paginador);

      //boton volver
      $salida .= "    <table align=\"center\" width=\"20%\">\n";
      $salida .= "      <tr>";
      $salida .= "        <form name=\"formavolver\" method=\"POST\" action=\"$urlVolver\">";
      $salida .= "          <td align=\"center\"><input  type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\"></td>";
      $salida .= "        </form>";
      $salida .= "      </tr>\n";
      $salida .= "    </table>\n<br>";

      $salida .= ThemeCerrarTabla();

      return $salida;
    }

      /**
     * Se encarga de separar la fecha del formato timestamp
     * @access private
     * @return string
     * @param date fecha
     */
    function FechaStamp($fecha) {
        if ($fecha) {
            $fech = strtok($fecha, "-");
            for ($l = 0; $l < 3; $l++) {
                $date[$l] = $fech;
                $fech = strtok("-");
            }
            return ceil($date[2]) . "/" . str_pad(ceil($date[1]), 2, 0, STR_PAD_LEFT) . "/" . str_pad(ceil($date[0]), 2, 0, STR_PAD_LEFT);
        }
    }

    function HoraStamp($hora) {
        $hor = strtok($hora, " ");
        for ($l = 0; $l < 4; $l++) {
            $time[$l] = $hor;
            $hor = strtok(":");
        }
        $x = explode('.', $time[3]);
        return $time[1] . ":" . $time[2] . ":" . $x[0];
    }

}

?>