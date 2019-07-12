<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: VacunaHTML.class.php,v 1.3 2009/11/05 19:55:36 alexander Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma Vargas
  */
  /**
  * Clase: VacunaHTML
  * Clase encargada del manejo de base de datos para las consultas que se necesitan 
  * para mostrar los datos de la afiliacion y los afiliados. Contine los metodos mas 
  * comunes, llamados por cualquier metodo del controlador
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma Vargas
  */
 class VacunaHTML
 {
  /**
  *constructor
  */
  function VacunaHTML(){}
  
  /**
  *Esta es la primera ventana de vacunacion, aqui me muestra las vacunas ingresadas en caso de que 
  *hallan en la tabla de vacunas_parametro o si no me da la opcion de adicionar vacunas. 
  *contiene 4 parametros los cuales estan:
  *$action: volver, desactivar, modificar, vacuna(agregar vacuna) 
  *$datos: que es el que me trae los datos de la vacuna
  *$conteo y un $pagina que se usan para generar el paginador.
  */
  function VentanaAsignar($action,$datos,$conteo,$pagina)
  {
    $cl = AutoCarga::factory("ClaseUtil");
    $html =ThemeAbrirTabla("VACUNACION");
    $html.=$cl->RollOverFilas();
    $html.="<center><a href=\"".$action['vacuna']."\" class=\"label_error\" >AGREGAR VACUNA</a></center>";
    $html.="<br>";
    $html.="<table align=\"center\" border=\"1\" width=\"70%\" class=\"modulo_table_list\">";
    $html.="    <tr class=\"formulacion_table_list\">";
    $html.="        <td>CARGO</td>";
    $html.="        <td style=\"width:50%\">DESCRIPCION</td>";
    $html.="        <td>EDAD MINIMA</td>";
    $html.="        <td>EDAD MAXIMA</td>";
    $html.="        <td>OPCIONES</td>";
    $html.="        <td>MODIFICAR</td>";
    $html.="    </tr>";
    
    foreach($datos as $key => $detalle)
    {
                ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
                ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
      $html.="  <tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
      $html.="      <td>".$detalle['cargo']."</td>";
      $html.="      <td>".$detalle['descripcion']."</td>";
      $html.="      <td>".$detalle['edad_minima']." ".$detalle['nombre']."</td>";
      $html.="      <td>".$detalle['edad_maxima']." ".$detalle['nombre_edad_min']."</td>";
      $html.="      <td>";
      //if para poner el logo en opciones si esta asignado o para asignar
      if($detalle["sw_estado"] == "1")
      {
        $html.="        <a  href=\"".$action['desactivar'].URLRequest(array("cargo_cups"=>$detalle['cargo'],"sw_estado"=>"0"))."\" calss=\"label_error\">\n";
        $html.="        <img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">ACTIVADO\n";
        $html.="        </a>\n";        
      }
      else
      {
        $html.="        <a  href=\"".$action['desactivar'].URLRequest(array("cargo_cups"=>$detalle['cargo'],"sw_estado"=>"1"))."\" calss=\"label_error\">\n";
        $html.="        <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">DESACTIVADO\n";
        $html.="        </a>\n"; 
      }
        $html.="  </td>\n";
        $html.="  <td>\n";
        $html.="        <a  href=\"".$action['modificar'].URLRequest(array("cargo_cups"=>$detalle['cargo']))."\" calss=\"label_error\">\n";
        $html.="        <img src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\">MODIFICAR\n";
        $html.="        </a>\n";
        $html.="  </td>\n";
        
        $html .= "</tr>";
    }
    $pgn = AutoCarga::factory("ClaseHTML");
    $html.= $pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
    $html.= "</table>";
    $html.= "<br>";
    $html.= "<center><a href=\"".$action['volver']."\" class=\"label_error\" >VOLVER</a></center>";
    $html.= ThemeCerrarTabla();
    return $html;
  }
  
  /**
  *Esta ventana me permite buscar vacunas por cargo o por descripcion, ademas me muestra las vacunas 
  *existentes en la tabla de cups y me permite asignar vacunas que es aqui donde se parametrizan.
  *tiene 5 parametros los cuales son:
  *$action: buscar, asignar, volver
  *$datos: para que me traiga los datos de la consulta 
  *$conteo, $pagina: son los que me generan el paginador
  *$request: es donde estan los datos de la vacuna
  */
  function VentanaBuscar($action,$datos,$conteo,$pagina,$request)
  {
    $cl = AutoCarga::factory("ClaseUtil");
    $html  = ThemeAbrirTabla("VACUNACION");
    $html.= $cl->RollOverFilas();
    $html.="<form name=\"buscador_cargos\" action=\"".$action["buscador"]."\" method=\"post\">\n";
    $html.="    <table align=\"center\" border=\"0\" width=\"70%\" class=\"modulo_table_list\">";
    $html.="        <tr class=\"formulacion_table_list\">";
    $html.="            <td colspan=\"4\">BUSCADOR DE CARGOS</td>";
    $html.="        </tr>";
    $html.="        <tr class=\"modulo_list_claro\">";
    $html.="            <td class=\"formulacion_table_list\" width=\"20%\">Cargo</td>";
    $html.="            <td width=\"20%\"><input type=\"text\" name=\"cargo\" class=\"input-text\" style=\"width:80%\" value=\"".$request["cargo"]."\"></td>\n";
    $html.="            <td class=\"formulacion_table_list\" width=\"20%\">Descripcion</td>";
    $html.="            <td ><input type=\"text\" name=\"descripcion\" class=\"input-text\" style=\"width:80%\" value=\"".$request["descripcion"]."\"></td>\n";
    $html.="        </tr>";
    $html.="   </table>";
    $html.="   <center><input type=\"submit\" name=\"buscador\" value=\"Buscar\" class=\"input-submit\"></center>";
    $html.="   <br>";
    $html.="</form>\n";
    
    if(empty($datos))
    {  
    $html.="<center>\n";
    $html.="  <label class=\"label_error\">LA CONSULTA NO ARROJO DATOS</label>\n";
    $html.="</center>\n";
    }
    else
    {
    $html.="<table align=\"center\" border=\"0\" width=\"70%\" class=\"modulo_table_list\">\n";
    $html.="     <tr class=\"formulacion_table_list\">"; 
    $html.="         <td width=\"15%\">CARGO</td>";
    $html.="         <td>DESCRIPCION</td>";
    $html.="         <td width=\"20%\">OPCION</td>";
    $html.="     </tr>";
    foreach($datos as $key => $detalle)
    {
                            ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
                            ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";				
    $html.="            <tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
	  $html.="                <td>".$detalle['cargo']."</td>";
    $html.="                <td>".$detalle['descripcion']."</td>";
    $html.="                <td>\n";
                                    //if para poner el logo en opciones si esta asignado o para asignar
                                    if(!$detalle["cargo_parametro"])
                                    {
    $html.="                        <a  href=\"".$action['buscar'].URLRequest(array("cargo_cups"=>$detalle['cargo'],"descripcion_cups"=>$detalle['descripcion']))."\" calss=\"label_error\">\n";
    $html.="                        <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">ASIGNAR\n";
    $html.="                        </a>\n";        
                                    }
                                    else
                                    {
    $html.="                      <img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">ASIGNADO\n";
                                    }
    $html.="              </td>\n";
    $html.="          </tr>";
    }
    $html.="</table>";
    $html.="<br>";
    $pgn = AutoCarga::factory("ClaseHTML");
    $html.= $pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
    }
    $html.= "<center><a href=\"".$action['volver']."\" class=\"label_error\" >VOLVER</a></center>";
    $html.= ThemeCerrarTabla();
    return $html;
  }
  
  /**
  *Esta funcion me mustra la ventana de parametrizacion de la vacuna, si voy a modificar una vacuna
  *me mustra la informacion previa con la que se guardo. tiene 7 parametros.
  *$action: aceptar, volver
  *$datos: es el que me trae la informacion cuando voy a modificar
  *$unidades_tiempo: me trae las unidades de tiempo que estan en la tabla unidades_tiempo 
  *$genero: me trae el genero 
  *$viaAplicacion: me trae la via de aplicacion de la vacuna 
  *$datosDosis: me trae los datos de las dosis
  *$datosRefuerzos: me trae los datos de los refuerzos
  */
  function VentanaVacuna($action,$datos,$unidades_tiempo,$genero,$viaAplicacion,$datosDosis,$datosRefuerzos)
  {
       $sl   = AutoCarga::factory("ClaseUtil");
       $html = ThemeAbrirTabla("VACUNACION");
       $html.= $sl->AcceptNum();
       $html.= $sl->IsNumeric();
       
       $html.="<script>";
       $html.="    function seleccionarValor(objeto)";
       $html.="    {";
       $html.="        if(objeto.unidadEdadMin.value=='1')";
       $html.="        {";
       $html.="            objeto.edadMin.value=0;";
       //$html.= "            objeto.edadMax.disabled=true;";
       $html.="        }";
       $html.="        else{objeto.edadMax.disabled=false;}";
       $html.="    }";
       $html.="</script>";
       
       $html.="    <form name=\"buscador_cargos\" id=\"buscador_cargos\" action=\"javascript:validarEdad(document.buscador_cargos)\" method=\"post\">\n";
       $html.="        <table class=\"modulo_table_list\"  align=\"center\" width=\"80%\" border=\"0\">";
       $html.="            <tr class=\"formulacion_table_list\">";
       $html.="                <td colspan=\"8\">PARAMETRIZACION DE VACUNAS</td>";
       $html.="            </tr>"; 
       $html.="            <tr class=\"modulo_list_claro\">";
       $html.="                <td class=\"formulacion_table_list\">Enfermedad</td>";
       $html.="                <td colspan=\"6\">\n";
       $html.="                   <input type=\"text\" colspan=\"6\" name=\"enfermedad\" class=\"input-text\" value=\"".$datos['enfermedad']."\">\n";
       $html.="                </td>";
       $html.="            </tr>"; 
       $html.="            <tr class=\"modulo_list_claro\">";
       $html.="                <td class=\"formulacion_table_list\">Cargo</td>";
       $html.="                <td colspan=\"8\">".$datos["cargo_cups"]."</td>";
       $html.="            </tr>";
       $html.="            <tr class=\"modulo_list_claro\">";
       $html.="                <td class=\"formulacion_table_list\">Descripcion</td>";
       $html.="                <td colspan=\"8\">".$datos["descripcion_cups"]."</td>";
       $html.="            </tr>";
       $html.="            <tr class=\"formulacion_table_list\">";
       $html.="                <td>Edad Minima:</td>\n";
       $html.="                <td class=\"modulo_list_claro\">";
       $html.="                    <input type=\"text\" name=\"edadMin\" class=\"input-text\" onkeypress=\"return acceptNum(event)\" value=\"".$datos['edad_minima']."\">";
       $html.="                </td>";
       $html.="                <td class=\"modulo_list_claro\">";
       $html.="                    <select name=\"unidadEdadMin\" class=\"select\" onchange=\"seleccionarValor(document.buscador_cargos)\">";
       $html.="                        <option value='-1'>--Seleccionar--</option>";
       $slt = "";
       $script="                          var unidades = new Array();\n";
       foreach($unidades_tiempo as $key => $detalle)
       {
            ($datos['unidadedadmin'] == $detalle['unidad_tiempo_id'])? $slt= "selected":$slt = "";
                $html.="            <option value='".$detalle["unidad_tiempo_id"]."' $slt>".$detalle["descripcion"]."</option>"; 
        $script .= "      unidades[".$detalle["unidad_tiempo_id"]."] = ".$detalle["indice_orden"].";\n";
       }
       $html.="                    </select>";
       $html.="                </td> ";
       $html.="                <td>Edad Maxima:</td>";
       $html.="                <td class=\"modulo_list_claro\">";
       $html.="                    <input type=\"text\" name=\"edadMax\" class=\"input-text\" onkeypress=\"return acceptNum(event)\" value=\"".$datos['edad_maxima']."\">";
       $html.="                </td>";
       $html.="                <td class=\"modulo_list_claro\">";
       $html.="                    <select name=\"unidadEdadMax\" class=\"select\" value=\"".$datos['unidad_edad_maxima']."\">";
       $html.="                        <option value='-1'>--Seleccionar--</option>";
       foreach($unidades_tiempo as $key => $detalle)
       {         
          if($detalle["descripcion"]!="recien nacido")
          {
            ($datos['edad_maxima_unidad'] == $detalle['unidad_tiempo_id'])? $slt= "selected":$slt = "";
            $html.="            <option value='".$detalle["unidad_tiempo_id"]."' $slt>".$detalle["descripcion"]."</option>"; 
          }
       }      
       $html.="                    </select>";
       $html.="                </td>";
       $html.="            </tr>";
       $html.="            <tr class=\"formulacion_table_list\">";
       $html.="                <td>Via de Aplicacion</td>\n";
       $html.="                <td class=\"modulo_list_claro\" colspan=\"6\" align=\"left\">";
       $html.="                    <select name=\"via_aplicacion\" class=\"select\" onchange=\"seleccionarValor(document.buscador_cargos)\" value=\"".$datos['via_aplicacion']."\">";
       $html.="                        <option value='-1'>--Seleccionar--</option>";
        foreach($viaAplicacion as $key => $detalle)
        {
            ($datos['via_aplicacion'] == $detalle['via_administracion_id'])? $slt= "selected":$slt = "";
                $html.="                   <option value='".$detalle["via_administracion_id"]."' $slt>".$detalle["nombre"]."</option>";
        }
       $html.="                    </select>";
       $html.="                </td>";
       $html.="            </tr>";
       $html.="            <tr class=\"formulacion_table_list\">";
       $html.="                <td>Numero de Dosis</td>\n";
       $html.="                <td class=\"modulo_list_claro\"  align=\"left\">";
       $html.="                    <select name=\"dosis\" class=\"select\" onchange=\"mostrarLinkVentanaDosis(this.value)\" align=\"center\" >";
       $html.="                        <option value='0'>--Seleccionar--</option>";
       for($i = 1; $i <= 5; $i++)
       {
        ($datos['dosis'] == $i)? $slt= "selected":$slt = "";
         $html.="                       <option value='".$i."' ".$slt.">".$i."</option>";
       }
       $html.="                    </select>";
       $html.="                </td>";
       $html.="                <td class=\"modulo_list_claro\" colspan=\"4\">";
       $html.="                  <div id=\"link_dosis\" align=\"left\" style=\"display:none\">\n";
       $html.="                    <a href=\"#link_dosis\"  onclick=\"javascript:xajax_registroDosis(xajax.getFormValues('buscador_cargos'))\" class=\"label_error\" >REGISTRAR DOSIS</a>";
       $html.="                  </div>\n";
       $html.="                </td>";
       $html.="            </tr>";
       $html.="            <tr class=\"formulacion_table_list\">";
       $html.="                <td>Numero de Refuerzos</td>";
       $html.="                <td class=\"modulo_list_claro\" align=\"left\">";
       $html.="                    <select name=\"refuerzos\" class=\"select\" value=\"".$datos['refuerzos']."\" onchange=\"mostrarLinkVentanaRefuerzos(this.value)\">";
       $html.="                        <option value='0'>--Seleccionar--</option>";
       for($i = 1; $i <= 10; $i++)
       {
         ($datos['refuerzos'] == $i)? $slt= "selected":$slt = "";
          $html.="                   <option value='".$i."' ".$slt.">".$i."</option>";
       }
       $html.="                    </select>";
       $html.="                </td>";
       $html.="                <td class=\"modulo_list_claro\" colspan=\"4\">";
       $html.="                    <div id=\"link_refuerzos\" align=\"left\" style=\"display:none\">\n";
       $html.="                        <a href=\"#link_refuerzos\" onclick=\"javascript:xajax_registroRefuerzos(xajax.getFormValues('buscador_cargos'))\" class=\"label_error\" >REGISTRAR REFUERZOS</a>";
       $html.="                    </div>\n";
       $html.="                </td>";
       $html.="            </tr>";
       $html.="            <tr class=\"formulacion_table_list\">";
       $html.="                <td>Genero</td>\n";
       foreach($genero as $key => $detalle)
       {
          ($datos['genero'] == $detalle['genero'])? $slt= "selected":$slt = "";
            $html.="            <td class=\"modulo_list_claro\"><input type=\"radio\" name=\"genero\" value='".$detalle["sexo_id"]."'>".$detalle["descripcion"]."</td>";
       }
       $html.="             <td colspan=\"3\">&nbsp;</td>";
       $html.="            </tr>";
       $html.="            <tr class=\"modulo_list_claro\">";
       $html.="                <td colspan=\"6\">\n";
       $html.="                    <div id=\"tabla_dosis\"></div>\n";
       $html.="                </td>\n";
       $html.="            </tr>";
       $html.="            <tr class=\"modulo_list_claro\">";
       $html.="                <td colspan=\"6\">\n";
       $html.="                    <div id=\"tabla_refuerzos\"></div>\n";
       $html.="                </td>\n";
       $html.="            </tr>";
       $html.="            <tr class=\"formulacion_table_list\"><td colspan=\"8\">";
       $html.="                <input type=\"submit\" name=\"Aceptar\" value=\"Aceptar\" class=\"modulo_table_list\">";  
       $html.="            </tr>";
       $html.="        </table> ";
       $html.="        <div id=\"tabla_refuerzos\"></div>\n";
       $html.="    </form>";
       $html.="    <center><div id =\"error\" class=\"label_error\"></div></center>";
       $html.="    <br>";
       $html.="    <center><a href=\"".$action['volver']."\" class=\"label_error\" >VOLVER</a></center>";
       
       $html.="<script>\n";
       $html.=$script;
       $html.="    function validarEdad(objeto)\n";
       $html.="    {\n";
       $html.="        if(objeto.enfermedad.value == \"\")";
       $html.="        {";
       $html.="            document.getElementById('error').innerHTML = 'DEBE INGRESAR LA ENFERMEDAD A PREVENIR DE LA VACUNA';\n";
       $html.="            return;";
       $html.="        }";
       $html.="        if(!IsNumeric(objeto.edadMin.value))";
       $html.="        {";
       $html.="            document.getElementById('error').innerHTML = 'FALTAN INGRESAR EL VALOR DE LA EDAD MINIMA';\n";
       $html.="            return;";
       $html.="        }";
       $html.="        if(objeto.edadMin.value*1 > objeto.edadMax.value*1 && objeto.unidadEdadMax.value != '-1')\n";
       $html.="        {\n";
       $html.="            document.getElementById('error').innerHTML = 'ERROR: LA EDAD MINIMA TIENE QUE SER MENOR QUE LA EDAD MAXIMA';\n";
       $html.="            return;\n";
       $html.="        }\n";
       $html.="        if(unidades[objeto.unidadEdadMin.value] > unidades[objeto.unidadEdadMax.value])\n";
       $html.="        {\n";
       $html.="            document.getElementById('error').innerHTML = 'LA UNIDAD DE TIEMPO DE LA EDAD MINIMA DEBE SER MENOR A LA UNIDAD DE TIEMPO DE LA EDAD MAXIMA';\n";
       $html.="            return;\n";
       $html.="        }\n";
       $html.="        if(objeto.unidadEdadMin.value == '-1' && objeto.unidadEdadMax.value == '-1')\n";
       $html.="        {\n";
       $html.="            document.getElementById('error').innerHTML = 'DEBE SELECCIONAR LA UNIDAD DE TIEMPO DE LA EDAD MINIMA O LA EDAD MAXIMA ';\n";
       $html.="            return;";
       $html.="        }\n";
       $html.="        if(objeto.via_aplicacion.value == '-1')\n";
       $html.="        {\n";
       $html.="            document.getElementById('error').innerHTML = 'DEBE SELECCIONAR LA VIA DE APLICACION DE LA VACUNA ';\n";
       $html.="            return;";
       $html.="        }\n"; 
       $html.="        if(objeto.dosis.value == '0')\n";
       $html.="        {\n";
       $html.="            document.getElementById('error').innerHTML = 'DEBE SELECCIONAR EL NUMERO DE DOSIS DE LA VACUNA ';\n";
       $html.="            return;";
       $html.="        }\n";
       $html.="        var x = objeto.dosis.value*1\n";
       $html.="            for(i=0; i<x; i++)\n";
       $html.="            {\n";
       $html.="                try\n";
       $html.="                {\n";
       $html.="                 if(!IsNumeric(document.getElementById('edad_aplicacion_'+i+'').value))";
       $html.="                 {";
       $html.="                    document.getElementById('error').innerHTML = 'FALTAN VALORES DE LA DOSIS POR INGRESAR';\n";
       $html.="                    return;";
       $html.="                 }";
       $html.="                }";
       $html.="                catch(error)\n";
       $html.="                {\n";
       $html.="                    document.getElementById('error').innerHTML = 'DEBE INGRESAR EL REGISTRO DE LAS  DOSIS';\n";   
       $html.="                    return;\n";
       $html.="                }\n";
       $html.="                if(document.getElementById('unidad_edad_aplicacion_'+i+'').value == '-1') \n";
       $html.="                {\n";
       $html.="                    document.getElementById('error').innerHTML = 'DEBE SELECCIONAR TODAS LAS UNIDADES DE TIEMPO DE LAS EDADES DE APLICACION DE LAS DOSIS';\n";
       $html.="                    return;";
       $html.="                }\n";
       $html.="                try\n";
       $html.="                {\n";
       $html.="                     if( document.getElementById('edad_aplicacion_'+i+'').value*1 >= document.getElementById('edad_aplicacion_'+(i+1)+'').value*1 )\n";
       $html.="                     {";
       $html.="                         document.getElementById('error').innerHTML = 'LA EDAD DE LA DOSIS ('+(i+1)+') TIENE QUE SER MENOR QUE LA EDAD DE LA DOSIS ('+(i+2)+')';\n";
       $html.="                         return;";
       $html.="                     }";
       $html.="                     if( unidades[document.getElementById('unidad_edad_aplicacion_'+i+'').value] > unidades[document.getElementById('unidad_edad_aplicacion_'+(i+1)).value] )\n";
       $html.="                     {";
       $html.="                         document.getElementById('error').innerHTML = 'LA UNIDAD DE LA DOSIS ('+(i+1)+') TIENE QUE SER MENOR A LA UNIDAD DE LA DOSIS ('+(i+2)+')';\n";
       $html.="                         return;";
       $html.="                     }\n";
       $html.="                }\n";
       $html.="                catch(error){}\n";
       $html.="                if(document.getElementById('edad_aplicacion_'+i+'').value*1 < objeto.edadMin.value*1 || document.getElementById('edad_aplicacion_'+i).value*1 > objeto.edadMax.value*1)";
       $html.="                {";
       $html.="                    document.getElementById('error').innerHTML = 'LAS EDADES DE LAS DOSIS TIENEN QUE ESTAR EN EL RANGO AUTORIZADO';\n";
       $html.="                    return;";
       $html.="                }";
       $html.="                if( unidades[document.getElementById('unidad_edad_aplicacion_'+i).value] < unidades[objeto.unidadEdadMin.value] || unidades[document.getElementById('unidad_edad_aplicacion_'+i).value] > unidades[objeto.unidadEdadMax.value] )\n";
       $html.="                {";
       $html.="                    document.getElementById('error').innerHTML = 'LA UNIDAD DE LA DOSIS ('+(i+1)+') TIENE QUE ESTAR EN EL RANGO DE LA UNIDADES MINIMA Y MAXIMA';\n";
       $html.="                    return;";
       $html.="                }\n";
       $html.="            }";
       
       $html.="            var y = objeto.refuerzos.value\n";
       $html.="            for(i=0; i<y; i++)";
       $html.="            {";
       $html.="                try\n";
       $html.="                {\n";
       $html.="                 if(!IsNumeric(document.getElementById('edad_aplicacion_r_'+i+'').value))";
       $html.="                 {";
       $html.="                    document.getElementById('error').innerHTML = 'FALTAN VALORES DE LOS REFUERZOS POR INGRESAR';\n";
       $html.="                    return;";
       $html.="                 }";
       $html.="                }";
       $html.="                catch(error)\n";
       $html.="                {\n";
       $html.="                    document.getElementById('error').innerHTML = 'DEBE INGRESAR EL REGISTRO DE LOS REFUERZOS';\n";   
       $html.="                    return;\n";
       $html.="                }\n";
       $html.="                if(document.getElementById('unidad_edad_aplicacion_r_'+i+'').value == '-1') \n";
       $html.="                {\n";
       $html.="                    document.getElementById('error').innerHTML = 'DEBE SELECCIONAR TODAS LAS UNIDADES DE TIEMPO DE LAS EDADES DE APLICACION DE LOS REFUERZOS';\n";
       $html.="                    return;";
       $html.="                }\n";
       $html.="                try\n";
       $html.="                {\n";
       $html.="                     if( document.getElementById('edad_aplicacion_r_'+i+'').value*1 >= document.getElementById('edad_aplicacion_r_'+(i+1)+'').value*1 )\n";
       $html.="                     {";
       $html.="                         document.getElementById('error').innerHTML = 'LA EDAD DEL REFUERZO ('+(i+1)+') TIENE QUE SER MENOR QUE LA EDAD DEL REFUERZO('+(i+2)+')';\n";
       $html.="                         return;";
       $html.="                     }";
       $html.="                     if( unidades[document.getElementById('unidad_edad_aplicacion_r_'+i+'').value] > unidades[document.getElementById('unidad_edad_aplicacion_r_'+(i+1)).value] )\n";
       $html.="                     {";
       $html.="                         document.getElementById('error').innerHTML = 'LA UNIDAD DEL REFUERZO ('+(i+1)+') TIENE QUE SER MENOR A LA UNIDAD DEL REFUERZO ('+(i+2)+')';\n";
       $html.="                         return;";
       $html.="                     }\n";  
       $html.="               }\n";
       $html.="               catch(error){}\n";       
       $html.="               if(document.getElementById('edad_aplicacion_r_'+i+'').value*1 < objeto.edadMax.value*1) \n";
       $html.="               {\n";
       $html.="                  document.getElementById('error').innerHTML = 'LAS EDADES DE LOS REFUERZOS TIENE QUE SER MAYOR QUE LA EDAD MAXIMA';\n";
       $html.="                  return;";
       $html.="               }\n";
       $html.="               if( unidades[document.getElementById('unidad_edad_aplicacion_r_'+i+'').value] < unidades[objeto.unidadEdadMax.value] )\n";
       $html.="               {";
       $html.="                  document.getElementById('error').innerHTML = 'LA UNIDAD DEL REFUERZO ('+(i+1)+') TIENE QUE SER MAYOR O IGUAL A LA UNIDAD DE LA EDAD MAXIMA';\n";
       $html.="                  return;";
       $html.="               }\n";
       $html.="           }\n";
       $html.="           objeto.action = \"".$action["insertar"]."\";\n";
       $html.="           objeto.submit();";
       $html.="    }\n";
       $html.="    function mostrarLinkVentanaDosis(valor)\n";
       $html.="    {\n";
       $html.="      if(valor == '-1')\n";
       $html.="        document.getElementById('link_dosis').style.display='none';\n";
       $html.="      else if(valor >= 1)\n";
       $html.="        document.getElementById('link_dosis').style.display='block';\n";
       $html.="    }\n";
       $html.="    function mostrarLinkVentanaRefuerzos(valor)\n";
       $html.="    {\n";
       $html.="      if(valor == '-1')\n";
       $html.="        document.getElementById('link_refuerzos').style.display='none';\n";
       $html.="      else if(valor >= 1)\n";
       $html.="        document.getElementById('link_refuerzos').style.display='block';\n";
       $html.="    }\n";
       if($datos['dosis'] >= 1)
        $html .= "  xajax_registroDosis(xajax.getFormValues('buscador_cargos'),'".$datos["cargo_cups"]."');\n";
       if($datos['refuerzos'] >= 1)
        $html .= "  xajax_registroRefuerzos(xajax.getFormValues('buscador_cargos'), '".$datos["cargo_cups"]."');\n";
        
       $html.="</script>";
       $html.= ThemeCerrarTabla();
       return $html;
      }
      
    /**
    * Crea una forma, para mostrar mensajes informativos con un solo boton
    * @param array $action vector que continen los link de la aplicacion
    * @param string $mensaje Cadena con el texto del mensaje a mostrar 
    *  en pantalla
    * @return string
    */
		function FormaMensajeModulo($action,$mensaje)
		{
        $html  = ThemeAbrirTabla('MENSAJE');
        $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "	<tr>\n";
		    $html .= "		<td>\n";
		    $html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
		    $html .= "		    <tr class=\"normal_10AN\">\n";
		    $html .= "		      <td align=\"center\">\n".$mensaje."</td>\n";
		    $html .= "		    </tr>\n";
		    $html .= "		  </table>\n";
		    $html .= "		</td>\n";
		    $html .= "	</tr>\n";
		    $html .= "	<tr>\n";
		    $html .= "		<td align=\"center\"><br>\n";
		    $html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
		    $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
		    $html .= "			</form>";
		    $html .= "		</td>";
		    $html .= "	</tr>";
		    $html .= "</table>";
		    $html .= ThemeCerrarTabla();			
		    return $html;
		}
                      
 }
 ?>