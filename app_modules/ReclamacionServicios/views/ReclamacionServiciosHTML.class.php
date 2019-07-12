<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ReclamacionServiciosHTML.class.php,v 1.1 2008/06/03 15:17:05 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez 
  */
  /**
  * Clase Vista: ReclamacionServiciosHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */
  
  IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");
  
  class ReclamacionServiciosHTML
  {
    /**
    * Constructor de la clase
    */
    function ReclamacionServiciosHTML(){}
    /**
    * Funcion donde se crea la forma para el menu de Reclamacion de Servicios
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaMenu($action)
    {
      $html  = ThemeAbrirTabla('RECLAMACION DE SERVICIOS ');
      $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td align=\"center\">MENU\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a class=\"label_error\" href=\"".$action['inconsistencias_ent_pago']."\">INFORME DE POSIBLES INCONSISTENCIAS EN LA BASE DE DATOS DE LA ENTIDAD RESPONSABLE DEL PAGO</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a class=\"label_error\" href=\"".$action['atencion_urgencias']."\">INFORME DE LA ATENCION INICIAL DE URGENCIAS</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a class=\"label_error\" href=\"".$action['autorizacion_servicios']."\">SOLICITUD DE AUTORIZACION DE SERVICIOS DE SALUD</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";      
      /*$html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a class=\"label_error\" href=\"".$action['buscar']."\">BUSCAR DOCUMENTOS</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";*/
      $html .= "</table>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a class=\"label_error\" href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma que permite realizar la busqueda de la informacion de
    * los pacientes
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $tipos_id  vector con la informacion de los tipos de identificacion
    * @param array $request vector que contiene la informacion del request
    * @param array $datos_pac vector que contiene la informacion del paciente
    * @param string $pagina cadena con el numero de la pagina que se esta visualizando
    * @param string $conteo cadena con la cantidad de los datos que se muestran
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaBuscarPaciente($action, $tipos_id, $request, $datos_pac, $pagina, $conteo, $causa_ingreso, $ingresos, $planes)
    {
      $html  = ThemeAbrirTabla('BUSQUEDA PACIENTE');
      $html .= "<form name=\"formBuscarPaciente\" id=\"formBuscarPaciente\" method=\"post\" action=\"".$action['buscar_paciente']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"60%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"4\" align=\"center\">BUSCADOR\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <input type=\"hidden\" name=\"ing_pac\" value=\"".$request['ing_pac']."\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tipo Id:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "          <select class=\"select\" name=\"tipoId\" id=\"tipoId\">\n";
      $html .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($tipos_id as $indice => $valor)
      {
        if($valor['tipo_id_paciente']==$request['tipoId'])
          $sel = "selected";
        else
          $sel = "";
        $html .= "          <option value=\"".$valor['tipo_id_paciente']."\" ".$sel.">".$valor['descripcion']."</option>\n";
      }
      $html .= "          </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Paciente Id:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"noId\" value=\"".$request['noId']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"4\" align=\"center\">\n";
      $html .= "        <div id=\"error_b\" class=\"label_error\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    <tr>\n";
      $html .= "    <input type=\"hidden\" name=\"sw_oculto\" id=\"sw_oculto\" value=\"consultar\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"4\" align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"buscar\" value=\"Buscar\" onclick=\"ValidarBuscar()\">\n";
      $html .= "      </td>\n";
      $html .= "    <tr>\n";
      $html .= "  </table>\n";
	    $html .= "<br>\n";
      
      $path = GetThemePath();
      if(!empty($datos_pac) && !empty($ingresos))
      {
        $html .= "  <br>\n";
        $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"60%\">\n";
        $html .= "    <tr class=\"modulo_table_title\">\n";
        $html .= "      <td colspan=\"4\" align=\"center\">INFORMACION DEL PACIENTE\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"hc_table_submodulo_list_title\">\n";
        $html .= "      <td align=\"center\" width=\"10%\">ID\n";
        $html .= "      </td>\n";
        $html .= "      <td align=\"center\" width=\"5%\">TIPO ID\n";
        $html .= "      </td>\n";
        $html .= "      <td align=\"center\" width=\"40%\">NOMBRE\n";
        $html .= "      </td>\n";
        $html .= "      <td align=\"center\" width=\"5%\">C\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $est = "modulo_list_claro";
        foreach($datos_pac as $indice => $valor)
        { 
          ($est=="modulo_list_claro")? $est="modulo_list_oscuro" : $est="modulo_list_claro";
          $html .= "    <tr class=\"".$est."\">\n";
          $html .= "      <td align=\"left\">".$valor['paciente_id']."\n";
          $html .= "      </td>\n";
          $html .= "      <td align=\"center\">".$valor['tipo_id_paciente']."\n";
          $html .= "      </td>\n";
          $html .= "      <td align=\"left\">".$valor['primer_nombre']." ".$valor['segundo_nombre']." ".$valor['primer_apellido']." ".$valor['segundo_apellido']."\n";
          $html .= "      </td>\n";
          
          $html .= "      <td align=\"center\">\n";
          $html .= "        <a href=\"#\" onclick=\"xajax_SeleccionarIngreso('".$valor['paciente_id']."', '".$valor['tipo_id_paciente']."', '".$action['det_ingresos']."', '".$causa_ingreso."')\" class=\"label_error\"><img src=\"".$path."/images/informacion.png\" border=\"0\" title=\"INGRESOS\"></a>\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
        }
        $html .= "  <tr>\n";
        $html .= "    <td colspan=\"5\" align=\"center\">\n";
        $chtml = AutoCarga::factory('ClaseHTML');
        $html .= "  ".$chtml->ObtenerPaginado($conteo, $pagina, $action['paginador'], 50);
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  </table>\n";
      }else if($request['sw_oculto']=="consultar"){
        if($request['ing_pac']=="SI")
        {
          $html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"40%\">";
          $html .= "  <tr>\n";
          $html .= "    <td class=\"formulacion_table_list\">PLAN:\n";
          $html .= "    </td>\n";
          $html .= "    <td class=\"modulo_list_claro\">\n";
          $html .= "	  <select class=\"select\" name=\"plan_id\">\n";
          $html .= "        <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($planes as $indice => $valor)
            $html .= "      <option value=\"".$valor['plan_id']."\">".$valor['plan_descripcion']."</option>\n";
          $html .= "	  </select>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr>\n";
          $html .= "    <td colspan=\"2\">\n";
          $html .= "  	  <center>\n";
          $html .= "        <div id=\"error\" class=\"label_error\"></div>\n";
          $html .= "  	  </center>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr>\n";
          /*$html .= "    <td colspan=\"2\">\n";
          $html .= "  	  <center><a href=\"".$action['ing_paciente'].URLRequest(array("noId"=>$request['noId'], "tipoId"=>$request['tipoId']))."\" class=\"label_error\">INGRESAR PACIENTE</a></center>\n";
          $html .= "    </td>\n";*/          
          $html .= "    <td colspan=\"2\">\n";
          $html .= "  	  <center>\n";
          $html .= "        <input class=\"input-submit\" type=\"button\" name=\"ingresar\" value=\"Ingresar Paciente\" onclick=\"ValidarDatos()\">\n";
          $html .= "  	  </center>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          $html .= "</table>";
        }  
        else
          $html .= "<p align=\"center\" class=\"label_error\">NO EXISTEN REGISTROS</p>\n";
      }
            
      $html .= "</form>\n";      
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <a class=\"label_error\" href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<br>\n";
      $html .= "<script>\n";
      $html .= "  function ValidarBuscar()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formBuscarPaciente;\n";
      $html .= "    if(frm.tipoId.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error_b').innerHTML = 'DEBE INGRESAR EL TIPO DE IDENTIFICACION';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.noId.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error_b').innerHTML = 'DEBE INGRESAR EL NUMERO DE IDENTIFICACION';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "  function ValidarDatos()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formBuscarPaciente;\n";
      $html .= "    if(frm.tipoId.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL TIPO DE IDENTIFICACION';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.noId.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NUMERO DE IDENTIFICACION';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.plan_id.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL PLAN';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    frm.action = '".$action['ing_paciente']."';";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= $this->CrearVentana();
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    /**
    * Funcion donde se crea una forma con una ventana con capas para mostrar informacion
    * en pantalla
    *
    * @param int $tmn Tamaño que tendra la ventana
    *
    * @return string
    */
    function CrearVentana($tmn = 370)
    {
      $html .= "<script>\n";
      $html .= "  var contenedor = 'Contenedor';\n";
      $html .= "  var titulo = 'titulo';\n";
      $html .= "  var hiZ = 4;\n";
      $html .= "  function OcultarSpan()\n";
      $html .= "  { \n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      e = xGetElementById('Contenedor');\n";
      $html .= "      e.style.display = \"none\";\n";
      $html .= "    }\n";
      $html .= "    catch(error){}\n";
      $html .= "  }\n";
      $html .= "  function MostrarSpan()\n";
      $html .= "  { \n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      e = xGetElementById('Contenedor');\n";
      $html .= "      e.style.display = \"\";\n";
      $html .= "      Iniciar();\n";
      $html .= "    }\n";
      $html .= "    catch(error){alert(error)}\n";
      $html .= "  }\n";     
      
      $html .= "  function MostrarTitle(Seccion)\n";
      $html .= "  {\n";
      $html .= "    xShow(Seccion);\n";
      $html .= "  }\n";
      $html .= "  function OcultarTitle(Seccion)\n";
      $html .= "  {\n";
      $html .= "    xHide(Seccion);\n";
      $html .= "  }\n";

      $html .= "  function Iniciar()\n";
      $html .= "  {\n";
      $html .= "    contenedor = 'Contenedor';\n";
      $html .= "    titulo = 'titulo';\n";
      $html .= "    ele = xGetElementById('Contenido');\n";
      $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";  
      $html .= "    ele = xGetElementById(contenedor);\n";
      $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
      $html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
      $html .= "    ele = xGetElementById(titulo);\n";
      $html .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
      $html .= "    xMoveTo(ele, 0, 0);\n";
      $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $html .= "    ele = xGetElementById('cerrar');\n";
      $html .= "    xResizeTo(ele,20, 20);\n";
      $html .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
      $html .= "  }\n";
      
      $html .= "  function myOnDragStart(ele, mx, my)\n";
      $html .= "  {\n";
      $html .= "    window.status = '';\n";
      $html .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
      $html .= "    else xZIndex(ele, hiZ++);\n";
      $html .= "    ele.myTotalMX = 0;\n";
      $html .= "    ele.myTotalMY = 0;\n";
      $html .= "  }\n";
      $html .= "  function myOnDrag(ele, mdx, mdy)\n";
      $html .= "  {\n";
      $html .= "    if (ele.id == titulo) {\n";
      $html .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
      $html .= "    }\n";
      $html .= "    else {\n";
      $html .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
      $html .= "    }  \n";
      $html .= "    ele.myTotalMX += mdx;\n";
      $html .= "    ele.myTotalMY += mdy;\n";
      $html .= "  }\n";
      $html .= "  function myOnDragEnd(ele, mx, my)\n";
      $html .= "  {\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
      $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">CONFIRMACIÓN</div>\n";
      $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
      $html .= "  <div id='Contenido' class='d2Content'>\n";
      $html .= "  </div>\n";
      $html .= "</div>\n";
      return $html;
    }
    /**
    * Funcion donde se crea la forma para registrar la informacion de las inconsistencias 
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param string $c cadena que contiene el consecutivo para el numero del informe
    * @param string $fecha cadena que contiene la informacion de la fecha actual
    * @param array $empresa vector que contiene la informacion de la empresa
    * @param array $tercero vector que contiene la informacion del tercero(entidad 
    * responsable del pago)
    * @param array $paciente vector con la informacion del paciente
    * @param array $request vector que contiene la informacion del request
    * @param array $tipos_id vector que contiene la informacion de los tipos de 
    * identificacion
    * @param array $usuario vector que contiene la informacion del usuario del sistema
    * @param array $coberturas vector que contiene la informacion de la cobertura en salud
    * que tiene el paciente
    * @param array $inconsistencias vector que contiene los tipos de inconsistencias
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaInconsisEntPago($action, $c, $fecha, $empresa, $tercero, $paciente, $request, $tipos_id, $usuario, $coberturas, $inconsistencias)
    {
      $html  = ThemeAbrirTabla('INFORME DE POSIBLES INCONSISTENCIAS EN LA BASE DE DATOS DE LA ENTIDAD RESPONSABLE DEL PAGO');
      $html .= "<form name=\"formInconsisEntPago\" id=\"formInconsisEntPago\" method=\"post\" action=\"".$action['ing_inconsistencias']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">INCONSISTENCIAS EN LA BASE DE DATOS DE LA ENTIDAD RESPONSABLE DEL PAGO\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";

      $hora = date("H:i");
      if($fecha)
      {
        $fe = explode("-",$fecha);
        $f = $fe[2].'/'.$fe[1].'/'.$fe[0];
      }
      $html .= "    <tr>\n";
      $html .= "      <input type=\"hidden\" name=\"noForm\" id=\"noForm\" value=\"".$request['noForm']."\">\n";
      $html .= "      <input type=\"hidden\" name=\"ingreso\" id=\"ingreso\" value=\"".$request['ingreso']."\">\n";
      $html .= "      <input type=\"hidden\" name=\"tipo_ing\" id=\"tipo_ing\" value=\"".$request['tipo_ing']."\">\n";
      $html .= "      <input type=\"hidden\" name=\"plan_id\" id=\"plan_id\" value=\"".$request['plan_id']."\">\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"13%\">Fecha:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"27.32%\" colspan=\"2\">".$f."\n";
      $html .= "        <input type=\"hidden\" name=\"fecha\" id=\"fecha\" value=\"".$fecha."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"13%\">Hora:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"27.32%\" colspan=\"2\">".$hora."\n";
      $html .= "        <input type=\"hidden\" name=\"hora\" id=\"hora\" value=\"".$hora."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";      
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\">INFORMACION DEL PRESTADOR\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Nombre:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">".$empresa['razon_social']."\n";
      $html .= "      </td>\n";           
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tipo Id:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$empresa['tipo_id_tercero']."\n";
      $html .= "      </td>\n"; 
      $html .= "      <td class=\"formulacion_table_list\">Id:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$empresa['id_emp']." -- DV  ".$empresa['digito_verificacion']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Codigo:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$empresa['codigo_sgsss']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Direccion Prestador:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['direccion_emp']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Telefono:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">Ind: ".$empresa['indicativo_emp']." -- Num: ".$empresa['telefonos_emp']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Departamento:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['departamento_emp']."\n";
      $html .= "      </td>\n"; 
      $html .= "      <td class=\"formulacion_table_list\">Cod. Dpto.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['tipo_dpto_id_emp']."\n";
      $html .= "      </td>\n";      
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Municipio:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['municipio_emp']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cod. Mpio.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['tipo_mpio_id_emp']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr  align=\"center\">\n";
      $html .= "      <td class=\"modulo_table_title\" colspan=\"6\">ENTIDAD A LA QUE SE LE INFORMA (PAGADOR)\n";      
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Nombre:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">".$tercero['nombre_tercero']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Codigo:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$tercero['codigo_sgsss_p']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tipo Inconsistencia:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">\n";
      $html .= "        <select class=\"select\" name=\"inconsistencia\" id=\"inconsistencia\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($inconsistencias as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['inconsistencia_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">DATOS DEL USUARIO (como aparece en la base de datos)\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Primer Apellido:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['primer_apellido_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Segundo Apellido:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['segundo_apellido_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Primer Nombre:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['primer_nombre_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Segundo Nombre:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['segundo_nombre_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tipo Documento:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$request['tipoId']."\n";
      $html .= "        <input type=\"hidden\" name=\"tipoId_u\" id=\"tipoId_u\" value=\"".$request['tipoId']."\">";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">No. Documento:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$request['noId']."\n";
      $html .= "        <input type=\"hidden\" name=\"noId_u\" id=\"noId_u\" value=\"".$request['noId']."\">";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if($paciente['fecha_nacimiento_u'])
      {
        $fn = explode('-', $paciente['fecha_nacimiento_u']);
        $fnac = $fn[2].'/'.$fn[1].'/'.$fn[0];
      }
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Fecha Nacimiento:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">".$fnac."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Direccion Residencia:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['residencia_direccion_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Telefono:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['residencia_telefono_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Departamento:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['departamento_u']."\n";
      $html .= "      </td>\n"; 
      $html .= "      <td class=\"formulacion_table_list\">Cod. Dpto.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['tipo_dpto_id_u']."\n";
      $html .= "      </td>\n";      
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Municipio:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['municipio_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cod. Mpio.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['tipo_mpio_id_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cobertura en salud:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">".$coberturas['regimen_descripcion']."\n";
      $html .= "        <input type=\"hidden\" name=\"cobertura\" id=\"cobertura\" value=\"".$coberturas['regimen_id']."\">";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">INFORMACION DE LA POSIBLE INCONSISTENCIA\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" colspan=\"2\">VARIABLE PRESUNTAMENTE INCORRECTA\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" colspan=\"4\">DATOS SEGUN DOCUMENTO DE IDENTIFICACION (fisico)\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td colspan=\"2\"><input type=\"checkbox\" name=\"chpApellido\" id=\"chpApellido\" value=\"PA\" onclick=\"ValidarVariable()\"></input> Primer Apellido\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"4\"><input class=\"input-text\" type=\"text\" name=\"txtpApellido\" id=\"txtpApellido\" size=\"40%\" maxlength=\"30\" disabled></input>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        <input type=\"checkbox\" name=\"chsApellido\" id=\"chsApellido\" value=\"SA\" onclick=\"ValidarVariable()\"></input> Segundo Apellido\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"4\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"txtsApellido\" id=\"txtsApellido\" size=\"40%\" maxlength=\"30\" disabled>\n";      
      $html .= "        </input>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        <input type=\"checkbox\" name=\"chpNombre\" id=\"chpNombre\" value=\"PN\" onclick=\"ValidarVariable()\"></input> Primer Nombre\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"4\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"txtpNombre\" id=\"txtpNombre\" size=\"40%\" maxlength=\"20\" disabled>\n";      
      $html .= "        </input>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        <input type=\"checkbox\" name=\"chsNombre\" id=\"chsNombre\" value=\"SN\" onclick=\"ValidarVariable()\"></input> Segundo Nombre\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"4\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"txtsNombre\" id=\"txtsNombre\" size=\"40%\" maxlength=\"20\" disabled>\n";      
      $html .= "        </input>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        <input type=\"checkbox\" name=\"chtDoc\" id=\"chtDoc\" value=\"TD\" onclick=\"ValidarVariable()\"></input> Tipo Documento de Identificacion\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"4\">\n";
      $html .= "        <select class=\"select\" name=\"seltDoc\" id=\"seltDoc\" disabled>\n";      
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($tipos_id as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['tipo_id_paciente']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        <input type=\"checkbox\" name=\"chnDoc\" id=\"chnDoc\" value=\"ND\" onclick=\"ValidarVariable()\"></input> Numero Documento de Identificacion\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"4\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"txtnDoc\" id=\"txtnDoc\" size=\"40%\" maxlength=\"32\" disabled>\n";
      $html .= "        </input>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $cut = new ClaseUtil();
      $html .= $cut->AcceptDate("/");
      $html .= $cut->IsDate();
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        <input type=\"checkbox\" name=\"chfNac\" id=\"chfNac\" value=\"FN\" onclick=\"ValidarFecha()\"></input> Fecha de Nacimiento\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"4\">\n";
      $html .= "        <div id=\"div_fecha\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Observaciones:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">\n";
      $html .= "        <textarea class=\"textarea\" name=\"observaciones\" id=\"observaciones\" style=\"width:100%;background:#FFFFFF\"></textarea>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">INFORMACION DE LA PERSONA QUE REPORTA\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Nombre:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$usuario['nombre_us']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Telefono:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">Ind. ".$usuario['indicativo_us']." -- Num. ".$usuario['telefono_us']." -- Ext. ".$usuario['extension_us']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cargo o actividad:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">".$usuario['descripcion_us']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Telefono Cel.:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$usuario['tel_celular_us']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"6\">\n";
      $html .= "        <div class=\"label_error\" id=\"error\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"6\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"ValidarDatos()\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr align=\"center\">\n";
      $html .= "    <td>\n";
      $html .= "      <a class=\"label_error\" href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<script>\n";
      $html .= "  function ValidarVariable()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formInconsisEntPago;\n";
      //$html .= "    alert(frm.chpApellido.value);\n";
      $html .= "    if(frm.chpApellido.checked)\n";
      $html .= "    {\n";
      $html .= "      frm.txtpApellido.disabled=false;\n";      
      $html .= "    }else{\n";
      $html .= "      frm.txtpApellido.disabled=true;\n";
      $html .= "      frm.txtpApellido.value='';\n";
      $html .= "    }\n";
      $html .= "    if(frm.chsApellido.checked)\n";
      $html .= "    {\n";
      $html .= "      frm.txtsApellido.disabled=false;\n";      
      $html .= "    }else{\n";
      $html .= "      frm.txtsApellido.disabled=true;\n";
      $html .= "      frm.txtsApellido.value='';\n";
      $html .= "    }\n";
      $html .= "    if(frm.chpNombre.checked)\n";
      $html .= "    {\n";
      $html .= "      frm.txtpNombre.disabled=false;\n";      
      $html .= "    }else{\n";
      $html .= "      frm.txtpNombre.disabled=true;\n";
      $html .= "      frm.txtpNombre.value='';\n";
      $html .= "    }\n";
      $html .= "    if(frm.chsNombre.checked)\n";
      $html .= "    {\n";
      $html .= "      frm.txtsNombre.disabled=false;\n";      
      $html .= "    }else{\n";
      $html .= "      frm.txtsNombre.disabled=true;\n";
      $html .= "      frm.txtsNombre.value='';\n";
      $html .= "    }\n";
      $html .= "    if(frm.chtDoc.checked)\n";
      $html .= "    {\n";
      $html .= "      frm.seltDoc.disabled=false;\n";      
      $html .= "    }else{\n";
      $html .= "      frm.seltDoc.disabled=true;\n";
      $html .= "      frm.seltDoc.value='-1';\n";
      $html .= "    }\n";
      $html .= "    if(frm.chnDoc.checked)\n";
      $html .= "    {\n";
      $html .= "      frm.txtnDoc.disabled=false;\n";      
      $html .= "    }else{\n";
      $html .= "      frm.txtnDoc.disabled=true;\n";
      $html .= "      frm.txtnDoc.value='';\n";
      $html .= "    }\n";
      $html .= "    if(frm.chfNac.checked)\n";
      $html .= "    {\n";
      $html .= "      frm.txtfNac.disabled=false;\n";      
      $html .= "    }else{\n";
      $html .= "      frm.txtfNac.disabled=true;\n";
      $html .= "      frm.txtfNac.value='';\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function ValidarFecha()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formInconsisEntPago;\n";
      $html .= "    if(frm.chfNac.checked)\n";
      $html .= "    {\n";
      $html .= "      xajax_MostrarFecha(xajax.getFormValues(formInconsisEntPago));\n";
      $html .= "    }else{\n";
      $html .= "      document.getElementById('div_fecha').innerHTML = '';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<script language=\"javascript\">\n";
      $html .= "  var vFormCamp;\n";
      $html .= "  function Mostrar_Campo(vFormaCampo, cadCampo)\n";
      $html .= "  {\n";
      $html .= "    var dia='';\n";
      $html .= "    var mes='';\n";
      $html .= "    var anyo='';\n";
      $html .= "    var valor='';\n";
      $html .= "    try{\n";
      $html .= "      vFormCamp=vFormaCampo;\n";
      $html .= "      valor=vFormaCampo.value;\n";
      $html .= "    }catch(error){}\n";
      $html .= "    if(valor.length==10)\n";
      $html .= "    {\n";
      $html .= "      dia=valor.split('/')[0];\n";
      $html .= "      mes=parseInt(valor.split('/')[1])-1;\n";
      $html .= "      anyo=valor.split('/')[2];\n";
      $html .= "    }\n";
      $html .= "    CrearCalendario('Campo','/',dia,mes,anyo);\n";
      $html .= "  }\n";
      $html .= "  function Ocultar_Campo(fecha)\n";
      $html .= "  {\n";
      $html .= "    if(fecha!='')\n";
      $html .= "    {\n";
      $html .= "      vFormCamp.value=fecha;\n";
      $html .= "    }\n";      
      $html .= "    document.getElementById('calendario_pxCampo').style.visibility = 'hidden';\n";
      $html .= "  }\n";
      $html .= "  function ValidarDatos()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formInconsisEntPago;\n";
      $html .= "    if(frm.inconsistencia.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el Tipo de Inconsistencia';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.cobertura.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar la Cobertura en Salud';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.chpApellido.checked && frm.txtpApellido.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el Primer Apellido';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.chsApellido.checked && frm.txtsApellido.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el Segundo Apellido';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.chpNombre.checked && frm.txtpNombre.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el Primer Nombre';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.chsNombre.checked && frm.txtsNombre.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el Segundo Nombre';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.chtDoc.checked && frm.seltDoc.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el Tipo Documento de Identificacion';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.chnDoc.checked && frm.txtnDoc.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el Numero Documento de Identificacion';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.chfNac.checked && frm.txtfNac.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la Fecha de Nacimiento';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.chfNac.checked && !IsDate(frm.txtfNac.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El formato de la Fecha de Nacimiento es incorrecto';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      //$html .= "    document.getElementById('error').innerHTML = 'OK';\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma que permite indicar al usuario que se almaceno la
    * informacion de las inconsistencias. Ademas permite generar el reporte PDF
    * 
    * @param array $action vector que contiene los link de la aplicacion
    * @param string $mensaje cadena con el mensaje que se muestra al usuario
    * @param string $RUTA cadena con la ruta en la cual se encuentra el archivo pdf
    * @param integer $opcion Indica cual de los reportes se esta imprimiendo
    *
    * @return string $html cadena con el codigo html de la pagina
    */
    function formaMensajeInconsis($action, $mensaje, $RUTA,$request,$opcion)
    {//print_r($opcion);
      $xml = Autocarga::factory("ReportesCsv");
      $datos['inicio'] = 4;
      
      $html = "";
      $fncn = "";
      $datos['fecha'] = $request['fecha'];
      $datos['formulario_no'] = $request['consec'];
      switch($opcion)
      {
        case '1':
          $datos['ingreso'] = $request['ingreso'];
          $html .= $xml->GetJavacriptReporteXml('app','ReclamacionServicios','InformePresuntaInconsistencia',$datos,array("interface"=>4));
          $fncn  = $xml->GetJavaFunction();

          $html .= $xml->GetJavacriptReporteFPDF('app','ReclamacionServicios','InformePresuntaInconsistencia',$datos,array("interface"=>5));
          $fnc1  = $xml->GetJavaFunction();
        break;
        case '2':
          $datos['ingreso'] = $request['ingreso'];
          $datos['paciente_id'] = $request['noId_u'];
          $datos['tipo_id_paciente'] = $request['tipoId_u'];
          $html .= $xml->GetJavacriptReporteXml('app','ReclamacionServicios','InformeUrgencias',$datos,array("interface"=>4));
          $fncn  = $xml->GetJavaFunction();
          
          $html .= $xml->GetJavacriptReporteFPDF('app','ReclamacionServicios','InformeUrgencias',$datos,array("interface"=>5));
          $fnc1  = $xml->GetJavaFunction();
        break;
        case '3':
          $datos['paciente_id'] = $request['noId_u'];
          $datos['tipo_id_paciente'] = $request['tipoId_u'];
          $html .= $xml->GetJavacriptReporteXml('app','ReclamacionServicios','SolicitudAutorizacionServicios',$datos,array("interface"=>4));
          $fncn  = $xml->GetJavaFunction();
          
         $html .= $xml->GetJavacriptReporteFPDF('app','ReclamacionServicios','SolicitudAutorizacionServicios',$datos,array("interface"=>5));
         $fnc1  = $xml->GetJavaFunction();
        break;
      }
      $html .= ThemeAbrirTabla('MENSAJE');      
      $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"2\">\n";
      $html .= "      <table width=\"100%\" class=\"modulo_table_list\">\n";
      $html .= "        <tr class=\"normal_10AN\">\n";
      $html .= "          <td align=\"center\">\n".$mensaje."</td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr align=\"center\">\n";
      $html .= "    <td><br>\n";
      $html .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir_doc\" value=\"Imprimir Doc.\" onclick=\"".$fnc1."\">\n";
      $html .= "    </td>\n";
      $html .= "    <td>\n";
      $html .= "      <br>\n";
      $html .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir_xml\" value=\"Documento Xml\" onclick=\"".$fncn."\">\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";

      $html .= "<br>";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a class=\"label_error\" href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar la informacion de la atencion inicial de
    * un paciente en urgencias
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $request vector que contiene la informacion del request
    * @param array $empresa vector que contiene la informacion de la empresa
    * @param array $tercero vector que contiene la informacion del tercero(entidad 
    * responsable del pago)
    * @param array $paciente vector con la informacion del paciente
    * @param array $tipos_id vector que contiene la informacion de los tipos de 
    * identificacion
    * @param array $usuario vector que contiene la informacion del usuario del sistema
    * @param array $coberturas vector que contiene la informacion de la cobertura en salud
    * que tiene el paciente
    * @param string $fecha cadena que contiene la informacion de la fecha actual
    * @param string $c cadena que contiene el consecutivo para el numero del informe
    * @param array $orig_aten vector que contiene la informacion del origen de la atencion a
    * urgencias
    * @param array $ing_urg vector que contiene la informacion del ingreso a urgencias
    * @param array $niv_triages vector que contiene la informacion de la clasificacion
    * Triage
    * @param array $pac_rem vector que contiene la informacion del lugar de remision de un 
    * paciente a urgencias
    * @param array $diagnosticos vector que contiene la informacion de los diagnosticos
    * @param array $origenes Arreglo de datos de los origenes de atencion
    * @param array $destino Arreglo de datos del destno del paciente
    *
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaAtencionUrgencias($action, $request, $empresa, $tercero, $paciente, $tipos_id, $usuario, $coberturas, $fecha, $orig_aten, $ing_urg, $niv_triages, $pac_rem, $diagnosticos,$origenes,$destino,$destino2)
    {
      
     
      $html  = ThemeAbrirTabla("INFORME DE LA ATENCION INICIAL DE URGENCIAS");
      $html .= "<form name=\"formInconsisEntPago\" id=\"formInconsisEntPago\" method=\"post\" action=\"".$action['ing_aten_urg']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">INFORME DE LA ATENCION INICIAL DE URGENCIAS\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $hora = date("H:i");
      if($fecha)
      {
        $fe = explode("-",$fecha);
        $f = $fe[2].'/'.$fe[1].'/'.$fe[0];
      }
      $html .= "    <tr>\n";
      $html .= "      <input type=\"hidden\" name=\"noForm\" id=\"noForm\" value=\"".$request['noForm']."\">\n";
      $html .= "      <input type=\"hidden\" name=\"ingreso\" id=\"ingreso\" value=\"".$request['ingreso']."\">\n";
      $html .= "      <input type=\"hidden\" name=\"tipo_ing\" id=\"tipo_ing\" value=\"".$request['tipo_ing']."\">\n";
      $html .= "      <input type=\"hidden\" name=\"plan_id\" id=\"plan_id\" value=\"".$request['plan_id']."\">\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"13%\">Fecha:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"27.32%\" colspan=\"2\">".$f."\n";
      $html .= "        <input type=\"hidden\" name=\"fecha\" id=\"fecha\" value=\"".$fecha."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"13%\">Hora:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"27.32%\" colspan=\"2\">".$hora."\n";
      $html .= "        <input type=\"hidden\" name=\"hora\" id=\"hora\" value=\"".$hora."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";      
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\">INFORMACION DEL PRESTADOR\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Nombre:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">".$empresa['razon_social']."\n";
      $html .= "      </td>\n";           
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tipo Id:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$empresa['tipo_id_tercero']."\n";
      $html .= "      </td>\n"; 
      $html .= "      <td class=\"formulacion_table_list\">Id:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$empresa['id_emp']." -- DV  ".$empresa['digito_verificacion']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Codigo:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$empresa['codigo_sgsss']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Direccion Prestador:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['direccion_emp']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Telefono:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">Ind: ".$empresa['indicativo_emp']." -- Num: ".$empresa['telefonos_emp']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Departamento:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['departamento_emp']."\n";
      $html .= "      </td>\n"; 
      $html .= "      <td class=\"formulacion_table_list\">Cod. Dpto.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['tipo_dpto_id_emp']."\n";
      $html .= "      </td>\n";      
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Municipio:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['municipio_emp']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cod. Mpio.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['tipo_mpio_id_emp']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr  align=\"center\">\n";
      $html .= "      <td class=\"modulo_table_title\" colspan=\"6\">ENTIDAD A LA QUE SE LE INFORMA (PAGADOR)\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Nombre:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">".$tercero['nombre_tercero']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Codigo:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$tercero['codigo_sgsss_p']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">DATOS DEL PACIENTE\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Primer Apellido:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['primer_apellido_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Segundo Apellido:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['segundo_apellido_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Primer Nombre:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['primer_nombre_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Segundo Nombre:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['segundo_nombre_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tipo Documento:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$request['tipoId']."\n";
      $html .= "        <input type=\"hidden\" name=\"tipoId_u\" id=\"tipoId_u\" value=\"".$request['tipoId']."\">";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">No. Documento:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$request['noId']."\n";
      $html .= "        <input type=\"hidden\" name=\"noId_u\" id=\"noId_u\" value=\"".$request['noId']."\">";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if($paciente['fecha_nacimiento_u'])
      {
        $fn = explode('-', $paciente['fecha_nacimiento_u']);
        $fnac = $fn[2].'/'.$fn[1].'/'.$fn[0];
      }
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Fecha Nacimiento:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">".$fnac."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Direccion Residencia:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['residencia_direccion_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Telefono:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['residencia_telefono_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Departamento:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['departamento_u']."\n";
      $html .= "      </td>\n"; 
      $html .= "      <td class=\"formulacion_table_list\">Cod. Dpto.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['tipo_dpto_id_u']."\n";
      $html .= "      </td>\n";      
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Municipio:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['municipio_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cod. Mpio.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['tipo_mpio_id_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cobertura en salud:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">".$coberturas['regimen_descripcion']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">INFORMACION DE LA ATENCION\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Origen de la atencion:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$origenes[$orig_aten['origen_atencion']]['origenes_atencion_descripcion']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Clasificacion Triage:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$niv_triages[0]['triage']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\" colspan=\"6\" align=\"center\">Ingreso a Urgencias:\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if($ing_urg['fecha_ingreso'])
      {
        $f_ing = explode(" ", $ing_urg['fecha_ingreso']);
        $fIng = explode("-", $f_ing[0]);
        $fIngreso = $fIng[2].'/'.$fIng[1].'/'.$fIng[0];
        
        $hIng = explode(":", $f_ing[1]);
        $hIngreso = $hIng[0].":".$hIng[1];
      }
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Fecha:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$fIngreso."\n";
      $html .= "        <input type=\"hidden\" name=\"fIngUrg\" id=\"fIngUrg\" value=\"".$f_ing[0]."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Hora:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$hIngreso."\n";
      $html .= "        <input type=\"hidden\" name=\"hIngUrg\" id=\"hIngUrg\" value=\"".$hIngreso."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Paciente Remitido:\n";
      $html .= "      </td>\n";
     
      if(count($pac_rem)>0)
      {
        $html .= "    <td class=\"modulo_list_claro\">SI\n";
        $html .= "      <input type=\"hidden\" name=\"pacRem\" id=\"pacRem\" value=\"SI\">\n";
        $html .= "    </td>\n";
      }
      else
      {
        $html .= "    <td class=\"modulo_list_claro\">NO\n";
        $html .= "      <input type=\"hidden\" name=\"pacRem\" id=\"pacRem\" value=\"NO\">\n";
        $html .= "    </td>\n";
      }
      $html .= "    </tr>\n";
      if(count($pac_rem)>0)
      {
        $html .= "    <tr>\n";
        $html .= "      <td class=\"formulacion_table_list\">Nombre Remitente:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">".$pac_rem['nomb_rem']."\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"formulacion_table_list\">Codigo:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">".$pac_rem['centro_remision']."\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"formulacion_table_list\">Departamento:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$pac_rem['departamento_pr']."\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"formulacion_table_list\">Cod. Dept.:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$pac_rem['tipo_dpto_id_pr']."\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"formulacion_table_list\">Municipio:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$pac_rem['municipio_pr']."\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"formulacion_table_list\">Cod. Mpio.:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$pac_rem['tipo_mpio_id_pr']."\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
      }
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Motivo de consulta:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">".$ing_urg['desc_motivo']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" align=\"center\">Impresion Diagnostica:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" align=\"center\">Codigo CIE10\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" align=\"left\" colspan=\"4\">Descripcion\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $cant_diag = count($diagnosticos);
      for($i=0; $i<$cant_diag; $i++)
      {
        $html .= "  <tr>\n";
        if($i==0)
        {
          $html .= "    <td class=\"formulacion_table_list\" align=\"center\">Diagnostico principal\n";
          $html .= "    </td>\n";
        }else{
          $html .= "    <td class=\"formulacion_table_list\" align=\"center\">Diagnos. relacionado ".$i."\n";
          $html .= "    </td>\n";
        }
        
        $html .= "    <td class=\"modulo_list_claro\" align=\"center\">".$diagnosticos[$i]['tipo_diagnostico_id']."\n";
        $html .= "        <input type=\"hidden\" name=\"cant_diag\" id=\"cant_diag\" value=\"".$cant_diag."\">\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"modulo_list_claro\" align=\"left\" colspan=\"4\">".$diagnosticos[$i]['diagnostico_nombre']."\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" align=\"center\">Destino del Paciente:\n";
      $html .= "      </td>\n";
      if(!empty($destino))
      {
      $html .= "      <td class=\"modulo_list_claro\" align=\"center\" colspan=\"5\">".$destino['destino_paciente_descripcion']."</td>\n";
      }
      else 
      {
      $html .= "      <td class=\"modulo_list_claro\" align=\"center\" colspan=\"5\">".$destino2[0]['destino_paciente_descripcion']."</td>\n";
      }
      
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">INFORMACION DE LA PERSONA QUE INFORMA\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Nombre:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$usuario['nombre_us']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Telefono:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">Ind. ".$usuario['indicativo_us']." -- Num. ".$usuario['telefono_us']." -- Ext. ".$usuario['extension_us']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cargo o actividad:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">".$usuario['descripcion_us']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Telefono Cel.:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$usuario['tel_celular_us']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"6\">\n";
      $html .= "        <div class=\"label_error\" id=\"error\">\n";
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"6\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr align=\"center\">\n";
      $html .= "    <td>\n";
      $html .= "      <a class=\"label_error\" href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";

      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar la informacion de las ordenes de servicio
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $orden vector que contiene la informacion de las ordenes de servicio
    * @param array $request vector que contiene la informacion del request
    * @param array $paciente vector que contiene la informacion del paciente
    * @param array $cargos vector que contiene la informacion de los cargos
    * @param array $origenes Vector con los datos de los origenes de atencion
    *
    * @return string $html cadena que contiene el codigo html de la pagina
    */
    function formaOrdenServicio($action, $orden, $request, $paciente, $cargos,$origenes)
    {
      $html  = ThemeAbrirTabla("SOLICITUD DE AUTORIZACION ");
      $html .= "<script>\n";
      $html .= "  function EvaluarDatos(frm,plan_id,usuario_id,nform)\n";
      $html .= "  {\n";
      $html .= "    url = \"".$action['det_ingresos']."&plan_id=\"+plan_id+\"&usuario_id=\"+usuario_id;\n";
      $html .= "    flag = false;\n";
      $html .= "    long = frm.elements.length;\n";
      $html .= "    for(i=0; i<long; i++)\n";
      $html .= "    {\n";
      $html .= "      elem = frm.elements[i];\n";
      $html .= "      if(elem.type=='checkbox' )\n";
      $html .= "      {\n";
      $html .= "        if(elem.checked) flag = true;\n";
      $html .= "      }\n";      
      $html .= "    }\n";
      $html .= "    if(flag == false)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error'+nform).innerHTML = 'DEBE SELECCIONAR UN CARGO';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "		frmi = document.adicionales;\n";
      $html .= "    if(!frmi.prioridad_servicio[0].checked && !frmi.prioridad_servicio[1].checked)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error'+nform).innerHTML = 'DEBE SELECCIONAR LA PRIORIDAD DEL SERVICIO';\n";
      $html .= "      frmi.departamento.focus()\n";
      $html .= "      return;\n";
      $html .= "    }\n";      
      $html .= "    if(!frmi.tipo_servicio[0].checked && !frmi.tipo_servicio[1].checked)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error'+nform).innerHTML = 'DEBE SELECCIONAR EL TIPO DE SERVICIOS SOLICITADOS';\n";
      $html .= "      frmi.departamento.focus()\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    v = '';\n";
      $html .= "    (frmi.prioridad_servicio[0].checked)? v = frmi.prioridad_servicio[0].value: v = frmi.prioridad_servicio[1].value;\n";
      $html .= "    url = url + \"&prioridad_servicio=\"+v;\n";
      $html .= "    (frmi.tipo_servicio[0].checked)? v = frmi.tipo_servicio[0].value: v = frmi.prioridad_servicio[1].value;\n";
      $html .= "    url = url + \"&tipo_servicio=\"+v;\n";      
      $html .= "    frm.action = url;\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      
      $st = " style=\"text-align:left;padding:4px\" ";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "	    <td width=\"10%\">PACIENTE:</td>\n";
      $html .= "	    <td width=\"50%\" colspan=\"3\" class=\"modulo_list_claro\" align=\"left\">".$paciente['primer_nombre_u']." ".$paciente['segundo_nombre_u']." ".$paciente['primer_apellido_u']." ".$paciente['segundo_apellido_u']."\n";
      $html .= "	    </td>\n";	  
      $html .= "	  </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "	    <td>TIPO ID:</td>\n";
      $html .= "	    <td width=\"20%\" class=\"modulo_list_claro\" align=\"left\">".$request['tipoId']."\n";
      $html .= "	    </td>\n";
      $html .= "	    <td width=\"10%\">No. ID:</td>\n";
      $html .= "	    <td width=\"20%\" class=\"modulo_list_claro\" align=\"left\">".$request['noId']."\n";
      $html .= "	    </td>\n";	  
      $html .= "	  </tr>\n";
      $html .= "  </table>\n";
      $html .= "<form name=\"adicionales\" method=\"post\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
      $html .= "    <tr class=\"formulacion_table_list\" >\n";
      $html .= "      <td ".$st." >PRIORIDAD DE LA ATENCION</td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" >\n";
      $html .= "        <input type=\"radio\" name=\"prioridad_servicio\" value=\"1\">Prioritaria\n";
      $html .= "      </td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" >\n";
      $html .= "        <input type=\"radio\" name=\"prioridad_servicio\" value=\"2\">No prioritaria\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";   
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td ".$st." >TIPO DE SERVICIOS SOLICITADOS</td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" >\n";
      $html .= "        <input type=\"radio\" name=\"tipo_servicio\" value=\"1\">Posterior a la atención inicial de urgencias\n";
      $html .= "      </td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"radio\" name=\"tipo_servicio\" value=\"2\">Servicios electivos\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";    
      $html .= "  </table>\n";     
      $html .= "</form>\n";   
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr align=\"center\">\n";
      $html .= "    <td>\n";
      $html .= "      <a class=\"label_error\" href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<input type=\"hidden\" name=\"tipo_sol\" value=\"".$request['tipo_sol']."\">";
      $html .= "<br>\n";
      
      if(!empty($orden))
      {
        $cant = count($orden);
        $sw = true;
        $est="modulo_list_claro";
        $l = 0;
        for($i=0; $i<$cant; $i++)
        {		
          ($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
          if($sw==true)
          {
            //$html .= "<form name=\"formOrdenServicio".$l."\" id=\"formOrdenServicio".$l."\" method=\"post\" action=\"".$action['det_ingresos'].URLRequest(array("ingreso"=>$request['ingreso'], "noId"=>$request['noId'], "tipoId"=>$request['tipoId'], "plan_id"=>$orden[$i]['plan_id'], "usuario_id"=>$orden[$i]['usuario_id'], "tipo_sol"=>$request['tipo_sol']))."\">\n";
            $html .= "<form name=\"formOrdenServicio".$l."\" id=\"formOrdenServicio".$l."\" method=\"post\" action=\"javascript:EvaluarDatos(document.formOrdenServicio".$l.",'".$orden[$i]['plan_id']."','".$orden[$i]['usuario_id']."','".$l."')\">\n";
            $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
            $html .= "    <tr>\n";
            $html .= "	    <td width=\"10%\" class=\"formulacion_table_list\">PROFESIONAL\n";
            $html .= "	    </td>\n";
            $html .= "	    <td width=\"30%\" class=\"modulo_list_claro\">".$orden[$i]['nombre']."\n";
            $html .= "	    </td>\n";
            $html .= "	    <td width=\"10%\" class=\"formulacion_table_list\">DEPARTAMENTO\n";
            $html .= "	    </td>\n";
            $html .= "	    <td width=\"25%\" class=\"modulo_list_claro\">".$orden[$i]['desc_departamento']."\n";
            $html .= "	    </td>\n";
            $html .= "	    <td width=\"5%\" class=\"modulo_list_claro\">\n";
            $html .= "	    </td>\n";
            $html .= "	  </tr>\n";		  
            $html .= "    <tr class=\"formulacion_table_list\">\n";
            $html .= "	    <td align=\"center\">No. CARGO\n";
            $html .= "	    </td>\n";
            $html .= "	    <td colspan=\"4\" align=\"center\">DESCRIPCION CARGO\n";
            $html .= "	    </td>\n";
            $html .= "	  </tr>\n";
          }  
          $html .= "    <tr class=\"".$est."\">\n";
          $html .= "	      <td>".$orden[$i]['cargo']."\n";
          $html .= "	      </td>\n";
          $html .= "	      <td colspan=\"3\">".$orden[$i]['desc_cargo']."\n";		  
          $html .= "	      </td>\n";		
          $html .= "    	  <td align=\"center\">\n";
          $html .= "          <input type=\"checkbox\" name=\"cargos[]\" value=\"".$orden[$i]['cargo']."\">";
          $html .= "	      </td>\n";
          $html .= "	  </tr>\n";
          
          if($i<($cant-1) && $orden[$i]['usuario_id']!=$orden[$i+1]['usuario_id'])
            $sw = true;
          else
            $sw = false;
          
          if($sw==true || $i==($cant-1))
          {
            $html .= "    <tr>\n";
            $html .= "	      <td colspan=\"4\">\n";
            $html .= "	      </td>\n";
            $html .= "	      <td align=\"center\">\n";
            //$html .= "          <input class=\"input-submit\" type=\"button\" name=\"aceptar".$l."\" value=\"Aceptar\" onclick=\"ValidarDatos(document.formOrdenServicio".$l.", ".$l.")\">\n";
            $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"aceptar".$l."\" value=\"Aceptar\">\n";
            $html .= "	      </td>\n";		
            $html .= "    </tr>\n";
            $html .= "    <tr>\n";
            $html .= "	      <td colspan=\"4\" align=\"center\">\n";
            $html .= "          <div id=\"error".$l."\" class=\"label_error\"></div>\n";
            $html .= "	      </td>\n";		
            $html .= "    </tr>\n";
            $html .= "  </table>\n";
            $html .= "</form>\n";
            $html .= "<br>";
            $l++;
          }
        }
      }else{
        $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
        $html .= "    <tr>\n";
        $html .= "      <td width=\"5%\" align=\"center\">NO SE ENCONTRARON SOLICITUDES DE SERVICIOS RELACIONADAS AL PACIENTE\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "  </table>\n";        
      }
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar la informacion de la solicitud de  
    * autorizacion de servicios de salud
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param string $c cadena que contiene el consecutivo para el numero del informe
    * @param string $fecha cadena que contiene la informacion de la fecha actual
    * @param array $empresa vector que contiene la informacion de la empresa
    * @param array $tercero vector que contiene la informacion del tercero(entidad 
    * responsable del pago)
    * @param array $paciente vector con la informacion del paciente
    * @param array $request vector que contiene la informacion del request
    * @param array $coberturas vector que contiene la informacion de la cobertura en salud
    * que tiene el paciente
    * @param array $orig_aten vector que contiene la informacion del origen de la atencion 
    * del paciente
    * @param array $via_ing_cama vector que contiene la informacion de la ubicacion del 
    * paciente al momento de solicitar la autorizacion
    * @param string $anyo cadena que contiene el año en que se realiza la solicitud
    * @param array $cargos_orden vector que contiene la informacion de los cargos relacionados a la orden de servicio
    * @param array $diagnosticos vector que contiene la informacion de los diagnosticos
    * @param array $prof vector que contiene la informacion de los profesionales
    * @param array $origenes Arreglo de datos con los origenes de atencion
    *
    * @return string $html cadena que retorna el codigo html de la pagina
    */
    function formaSolicitudAutorizacionServ($action, $fecha, $empresa, $tercero, $paciente, $request, $coberturas, $orig_aten, $via_ing_cama, $anyo, $cargos_orden, $diagnosticos, $prof,$origenes,$cadenaJusti)
    {
      $st = " style=\"text-align:left;padding:4px\" ";
      
      $tipos_ser[1]['detalle'] = "Posterior a la atención inicial de urgencias";
      $tipos_ser[2]['detalle'] = "Servicios electivos";
      
      $prioridad_ser[1]['detalle'] = "Prioritaria";
      $prioridad_ser[2]['detalle'] = "No prioritaria";

      $html  = ThemeAbrirTabla("SOLICITUD DE AUTORIZACION DE SERVICIOS DE SALUD ");
      $html .= "<form name=\"formSolicAutorizacionServ\" id=\"formSolicAutorizacionServ\" method=\"post\" action=\"".$action['ing_solic_autoriza']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">SOLICITUD DE AUTORIZACION DE SERVICIOS DE SALUD\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";

      $hora = date("H:i");
      if($fecha)
      {
        $fe = explode("-",$fecha);
        $f = $fe[2].'/'.$fe[1].'/'.$fe[0];
      }
      $cut = new ClaseUtil();
      $html .= $cut->IsNumeric(); 
      $html .= $cut->AcceptNum(false);
      $html .= "    <tr>\n";
      $html .= "      <input type=\"hidden\" name=\"noForm\" id=\"noForm\" value=\"".$request['noForm']."\">\n";
      $html .= "      <input type=\"hidden\" name=\"ingreso\" id=\"ingreso\" value=\"".$request['ingreso']."\">\n";
      $html .= "      <input type=\"hidden\" name=\"tipo_ing\" id=\"tipo_ing\" value=\"".$request['tipo_ing']."\">\n";
      $html .= "      <input type=\"hidden\" name=\"orden_servicio_id\" id=\"orden_servicio_id\" value=\"".$request['orden_servicio_id']."\">\n";
      $html .= "      <input type=\"hidden\" name=\"usuario_id\" id=\"usuario_id\" value=\"".$request['usuario_id']."\">\n";
      $html .= "      <input type=\"hidden\" name=\"tipo_sol\" id=\"tipo_sol\" value=\"".$request['tipo_sol']."\">";
      $html .= "      <input type=\"hidden\" name=\"plan_id\" id=\"plan_id\" value=\"".$request['plan_id']."\">";
      $html .= "      <td class=\"formulacion_table_list\" width=\"20%\">Fecha:</td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"27%\" colspan=\"2\">".$f."\n";
      $html .= "        <input type=\"hidden\" name=\"fecha\" id=\"fecha\" value=\"".$fecha."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"13%\">Hora:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"27%\" colspan=\"2\">".$hora."\n";
      $html .= "        <input type=\"hidden\" name=\"hora\" id=\"hora\" value=\"".$hora."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";      
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\">INFORMACION DEL PRESTADOR (solicitante)</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Nombre:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">".$empresa['razon_social']."\n";
      $html .= "      </td>\n";           
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tipo Id:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$empresa['tipo_id_tercero']."\n";
      $html .= "      </td>\n"; 
      $html .= "      <td class=\"formulacion_table_list\">Id:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$empresa['id_emp']." -- DV  ".$empresa['digito_verificacion']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Codigo:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$empresa['codigo_sgsss']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Direccion Prestador:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['direccion_emp']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Telefono:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">Ind: ".$empresa['indicativo_emp']." -- Num: ".$empresa['telefonos_emp']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Departamento:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['departamento_emp']."\n";
      $html .= "      </td>\n"; 
      $html .= "      <td class=\"formulacion_table_list\">Cod. Dpto.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['tipo_dpto_id_emp']."\n";
      $html .= "      </td>\n";      
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Municipio:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['municipio_emp']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cod. Mpio.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['tipo_mpio_id_emp']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr  align=\"center\">\n";
      $html .= "      <td class=\"modulo_table_title\" colspan=\"6\">ENTIDAD A LA QUE SE LE SOLICITA (PAGADOR)\n";      
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Nombre:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">".$tercero['nombre_tercero']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Codigo:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$tercero['codigo_sgsss_p']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">DATOS DEL PACIENTE\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Primer Apellido:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['primer_apellido_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Segundo Apellido:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['segundo_apellido_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Primer Nombre:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['primer_nombre_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Segundo Nombre:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['segundo_nombre_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tipo Documento:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$request['tipoId']."\n";
      $html .= "        <input type=\"hidden\" name=\"tipoId_u\" id=\"tipoId_u\" value=\"".$request['tipoId']."\">";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">No. Documento:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$request['noId']."\n";
      $html .= "        <input type=\"hidden\" name=\"noId_u\" id=\"noId_u\" value=\"".$request['noId']."\">";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if($paciente['fecha_nacimiento_u'])
      {
        $fn = explode('-', $paciente['fecha_nacimiento_u']);
        $fnac = $fn[2].'/'.$fn[1].'/'.$fn[0];
      }
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Fecha Nacimiento:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">".$fnac."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Direccion Residencia:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['residencia_direccion_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Telefono:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['residencia_telefono_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Departamento:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['departamento_u']."\n";
      $html .= "      </td>\n"; 
      $html .= "      <td class=\"formulacion_table_list\">Cod. Dpto.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['tipo_dpto_id_u']."\n";
      $html .= "      </td>\n";      
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Municipio:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['municipio_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cod. Mpio.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['tipo_mpio_id_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tel celular:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['celular_telefono']."\n";
      //$html .= "        <input class=\"input-text\" type=\"text\" name=\"telCel\" id=\"telCel\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Correo electronico:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['email']."\n";
      //$html .= "        <input class=\"input-text\" type=\"text\" name=\"email\" id=\"email\" size=\"36%\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cobertura en salud:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">".$coberturas['regimen_descripcion']."\n";
      $html .= "        <input type=\"hidden\" name=\"cobertura\" id=\"cobertura\" value=\"".$coberturas['regimen_id']."\">";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\">INFORMACION DE LA ATENCION Y SERVICIOS SOLICITADOS\n";      
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td ".$st." >Origen de la atencion:</td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" colspan=\"5\">\n";
      $html .= "        ".$origenes[$request['origen_atencion']]['origenes_atencion_descripcion']." ";
      $html .= "        <input type=\"hidden\" name=\"origen_atencion\" value=\"".$request['origen_atencion']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\" >\n";
      $html .= "      <td ".$st." colspan=\"2\">Ubicacion del Paciente al realizar la solicitud:</td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" colspan=\"4\">".$cargos_orden[0]['desc_departamento']."</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td ".$st." colspan=\"2\">Prioridad del servicio:</td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" colspan=\"4\">\n";
      $html .= "        ".$prioridad_ser[$request['prioridad_servicio']]['detalle']."\n";
      $html .= "        <input type=\"hidden\" name=\"prioridad_servicio\" value=\"".$request['prioridad_servicio']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";      
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td ".$st." colspan=\"2\">Tipo de servicios solicitados:</td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" colspan=\"4\">\n";
      $html .= "        ".$tipos_ser[$request['tipo_servicio']]['detalle']."\n";
      $html .= "        <input type=\"hidden\" name=\"tipo_servicio\" value=\"".$request['tipo_servicio']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Servicio:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$cargos_orden[0]['desc_servicio']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cama:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$via_ing_cama['cama']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td colspan=\"2\" align=\"left\">Manejo integral segun guia:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"4\" align=\"left\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"16.66%\" align=\"center\">Codigo CUPS\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"16.66%\" align=\"center\">Cantidad\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"66.68%\" align=\"left\" colspan=\"4\">Descripcion \n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $est = "modulo_list_claro";
      $cant_cg = count($request['cargos']);
      
      foreach($cargos_orden as $indice => $valor)
      {
        ($est=="modulo_list_oscuro")?$est="modulo_list_claro":$est="modulo_list_oscuro";
        for($i=0; $i<$cant_cg ; $i++)
        {
          if($request['cargos'][$i]==$valor['cargo'])
          {
            $html .= "    <tr class=\"".$est."\">\n";
            $html .= "      <td width=\"16.66%\" align=\"center\">".$valor['cargo']."\n";
            $html .= "        <input type=\"hidden\" name=\"cargos[]\" value=\"".$valor['cargo']."\">";
            $html .= "      </td>\n";
            $html .= "      <td width=\"16.66%\" align=\"center\">".$valor['cantidad']."\n";
            $html .= "      </td>\n";
            $html .= "      <td width=\"66.68%\" align=\"left\" colspan=\"4\">".$valor['desc_cargo']."\n";
            $html .= "      </td>\n";
            $html .= "    </tr>\n";
          }
        }
      }
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td class=\"formulacion_table_list\" align=\"left\">Justificacion Clinica:\n";
      $html .= "      </td>\n";
      $html .= "      <td align=\"left\" colspan=\"5\">\n";
      $html .= "     ".$cadenaJusti."";
      
      $html .= "      </td>\n";    
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" align=\"center\">Impresion Diagnostica:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" align=\"center\">Codigo CIE10\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" align=\"left\" colspan=\"4\">Descripcion\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if(!empty($diagnosticos))
      {
        $cant_diag = count($diagnosticos);
        for($i=0; $i<$cant_diag; $i++)
        {
          $html .= "  <tr>\n";
          if($i==0)
          {
            $html .= "    <td class=\"formulacion_table_list\" align=\"center\">Diagnostico principal\n";
            $html .= "    </td>\n";
          }else{
            $html .= "    <td class=\"formulacion_table_list\" align=\"center\">Diagnos. relacionado ".$i."\n";
            $html .= "    </td>\n";
          }
        
          $html .= "    <td class=\"modulo_list_claro\" align=\"center\">".$diagnosticos[$i]['tipo_diagnostico_id']."\n";
          $html .= "        <input type=\"hidden\" name=\"cant_diag\" id=\"cant_diag\" value=\"".$cant_diag."\">\n";
          $html .= "    </td>\n";
          $html .= "    <td class=\"modulo_list_claro\" align=\"left\" colspan=\"4\">".$diagnosticos[$i]['diagnostico_nombre']."\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
        }
      }else{
        $html .= "    <tr>\n";
        $html .= "      <td class=\"modulo_list_claro\" align=\"center\">&nbsp\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" align=\"center\">&nbsp\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" align=\"left\" colspan=\"4\">&nbsp\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
      }
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\">INFORMACION DE LA PERSONA QUE SOLICITA\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";    
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"16.66%\">Nombre:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\" width=\"33.33%\">".$prof['nomb_prof']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"16.66%\">Telefono:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\" width=\"33.33%\">Ind. ".$prof['indicativo_prof']." -- Num. ".$prof['tel_prof']." -- Ext. ".$prof['extencion_prof']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"16.66%\">Cargo o actividad:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\" width=\"49.99%\">".$prof['desc_prof']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"16.66%\">Telefono Cel.:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"16.66%\">".$prof['tel_cel_prof']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"6\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\" onclick=\"\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"6\">\n";
      $html .= "        <div class=\"label_error\" id=\"error\">\n";
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr align=\"center\">\n";
      $html .= "    <td>\n";
      $html .= "      <a class=\"label_error\" href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
      
    }
    /**
    * Funcion donde se crea la forma que permite seleccionar los cargos
    *
    * @param array $action vector que contiene los links de la aplicacion
    * @param array $request vector que contiene la informacion del request
    * @param array $cargos vector que contiene la informacion de los cargos
    * @param array $paciente vector que contiene la informacion del paciente
    * @param array $cups vector que contiene la informacion de los cargos de la tabla CUPS
    * @param array $deptos vector que contiene la informacion de los departamentos
    * @param array $origenes vector que contiene los origenes de atencion
    *
    * @return string $html cadena con el codigo html de la pagina
    */
    function formaSeleccionarCargos($action, $request, $cargos, $paciente, $cups, $deptos,$origenes)
    {
      $html  = ThemeAbrirTabla("CARGOS PACIENTE");
      if($request['sw_volver'])
        $html .= "<input type=\"hidden\" name=\"sw_volver\" value=\"1\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"60%\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "	  <td width=\"10%\">PACIENTE:\n";
      $html .= "	  </td>\n";
      $html .= "	  <td width=\"50%\" colspan=\"3\" class=\"modulo_list_claro\" align=\"left\">".$paciente['primer_nombre_u']." ".$paciente['segundo_nombre_u']." ".$paciente['primer_apellido_u']." ".$paciente['segundo_apellido_u']."\n";
      $html .= "	  </td>\n";	  
      $html .= "	</tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "	  <td width=\"10%\">TIPO ID:\n";
      $html .= "	  </td>\n";
      $html .= "	  <td width=\"20%\" class=\"modulo_list_claro\" align=\"left\">".$request['tipo_id_paciente']."\n";
      $html .= "	  </td>\n";
      $html .= "	  <td width=\"10%\">No. ID:\n";
      $html .= "	  </td>\n";
      $html .= "	  <td width=\"20%\" class=\"modulo_list_claro\" align=\"left\">".$request['paciente_id']."\n";
      $html .= "	  </td>\n";	  
      $html .= "	</tr>\n";
      $html .= "  </table>\n";
      $html .= "<br>\n";
      
      $html .= "<form name=\"formConsultarCargos\" id=\"formConsultarCargos\" method=\"post\" action=\"".$action['det_ingreso'].URLRequest(array("sw_volver"=>$request['sw_volver']))."\">\n";      
      
      $st = " style=\"text-align:left;padding:4px\" ";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
      $html .= "    <tr class=\"formulacion_table_list\" >\n";
      $html .= "      <td ".$st." >PRIORIDAD DE LA ATENCION</td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" >\n";
      $html .= "        <input type=\"radio\" name=\"prioridad_servicio\" value=\"1\">Prioritaria\n";
      $html .= "      </td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" >\n";
      $html .= "        <input type=\"radio\" name=\"prioridad_servicio\" value=\"2\">No prioritaria\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";   
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td ".$st." >TIPO DE SERVICIOS SOLICITADOS</td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" >\n";
      $html .= "        <input type=\"radio\" name=\"tipo_servicio\" value=\"1\">Posterior a la atención inicial de urgencias\n";
      $html .= "      </td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"radio\" name=\"tipo_servicio\" value=\"2\">Servicios electivos\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td ".$st." align=\"left\">ORIGEN DE LA ATENCIÓN:</td>\n";
      $html .= "      <td ".$st." colspan=\"2\" class=\"modulo_list_claro\" align=\"left\">\n";
      $html .= "        <select class=\"select\" name=\"origen_atencion\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($origenes as $k => $dtl)
        $html .= "        <option value=\"".$dtl['origen_atencion_id']."\">".$dtl['origenes_atencion_descripcion']."</option>\n";
      
      $html .= "        </select>\n";    
      $html .= "      </td>\n";
      $html .= "    </tr>\n";      
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td ".$st." align=\"left\">UBICACION:\n";    
      $html .= "      </td>\n";
      $html .= "      <td ".$st." colspan=\"2\" class=\"modulo_list_claro\" align=\"left\">\n";
      $html .= "        <select class=\"select\" name=\"departamento\" onchange=\"MostrarServicio()\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($deptos as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['departamento']."/".$valor['servicio']."/".$valor['desc_serv']."/".$valor['desc_depto']."\">".$valor['desc_depto']."</option>\n";
      }
      $html .= "        </select>\n";    
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td ".$st.">SERVICIO:</td>\n";
      $html .= "      <td colspan=\"2\" class=\"modulo_list_claro\" align=\"left\">\n";
      $html .= "        <div id=\"div_serv\"></div>\n";
      $html .= "        <input type=\"hidden\"  name=\"servicio\" id=\"servicio\" value=\"\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td colspan=\"3\" align=\"center\">CARGOS ADICIONADOS</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"3\">\n";
      $html .= "        <div id=\"div_ac\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <center>\n";
      $html .= "    <div id=\"div_con\" class=\"label_error\"></div>\n";
      $html .= "  </center>\n";
      $html .= "<br>\n";
      $html .= "<input type=\"hidden\" name=\"ingreso\" value=\"".$request['ingreso']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
      $html .= "    <tr class=\"modulo_table_list_title\">\n";
      $html .= "      <td colspan=\"5\">BUSCADOR CARGOS</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td width=\"5%\" class=\"modulo_table_list_title\">CARGO</td>\n";
      $html .= "      <td width=\"10%\" class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" style=\"width:100%\" name=\"cups\" maxlength=\"10\" value=\"".$request['cups']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"10%\" class=\"modulo_table_list_title\">DESCRIPCION</td>\n";
      $html .= "      <td width=\"30%\" class=\"modulo_list_claro\">\n";       
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"desc_cups\" value=\"".$request['desc_cups']."\" style=\"width:80%\">\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"5%\" class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"buscar\" value=\"Buscar\" onclick=\"ValidarCUPS()\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <center>\n";
      $html .= "    <div class=\"label_error\" id=\"error\"></div>\n";
      $html .= "  </center>\n";
      $html .= "  <br>\n"; 
      $html .= "  <div id=\"div_bc\"></div>\n";
      $html .= "</form>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr align=\"center\">\n";
      $html .= "    <td>\n";
      $html .= "      <a class=\"label_error\"  href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $html .= "<script>\n";
      $html .= "  function ValidarCUPS()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formConsultarCargos;\n";      
      $html .= "    if(frm.cups.value==\"\" && frm.desc_cups.value==\"\")\n";      
      $html .= "    {\n";
      $html .= "      document.getElementById('div_bc').innerHTML = '';\n";
      $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR UN CAMPO DE BUSQUEDA';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    document.getElementById('error').innerHTML = '';\n";
      $html .= "    xajax_BuscarCUPS(xajax.getFormValues('formConsultarCargos'));\n";
      $html .= "  }\n";
      $html .= "  function MostrarServicio()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formConsultarCargos;\n";
      $html .= "    if(frm.departamento.value==\"-1\")";
      $html .= "    {\n";
      $html .= "      document.getElementById('div_serv').innerHTML = '';\n";      
      $html .= "      document.getElementById('servicio').value = 1;\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    document.getElementById('div_con').innerHTML = '';\n";
      $html .= "    xajax_RelacionarServicio(xajax.getFormValues('formConsultarCargos'));\n";
      $html .= "  }\n";
      $html .= "  function ValidarCantidad(cargo)\n";
      $html .= "  {\n";      
      $html .= "    frm = document.formConsultarCargos;\n";      
      $html .= "    if(document.getElementById('cantidad_'+cargo).value*1 >= 1)\n";      
      $html .= "    {\n";
      $html .= "      xajax_AdicionarCargo(cargo, document.getElementById('cantidad_'+cargo).value);\n";      
      $html .= "    }else{\n";    
      $html .= "      alert('PARA ADICIONAR EL CARGO DEBE INGRESAR LA CANTIDAD');\n";
      $html .= "      return;\n";
      $html .= "    }\n";      
      $html .= "  }\n";
      $html .= "  function EliminaCargo(cargo)\n";
      $html .= "  {\n";
      $html .= "    xajax_EliminarCargo(cargo);\n";
      $html .= "  }\n";
      $html .= "  function Continuar()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formConsultarCargos;\n";
      $html .= "    if(!frm.prioridad_servicio[0].checked && !frm.prioridad_servicio[1].checked)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('div_con').innerHTML = 'DEBE SELECCIONAR LA PRIORIDAD DEL SERVICIO';\n";
      $html .= "      frm.departamento.focus()\n";
      $html .= "      return;\n";
      $html .= "    }\n";      
      $html .= "    if(!frm.tipo_servicio[0].checked && !frm.tipo_servicio[1].checked)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('div_con').innerHTML = 'DEBE SELECCIONAR EL TIPO DE SERVICIOS SOLICITADOS';\n";
      $html .= "      frm.departamento.focus()\n";
      $html .= "      return;\n";
      $html .= "    }\n";        
      $html .= "    if(frm.origen_atencion.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('div_con').innerHTML = 'DEBE SELECCIONAR UN ORIGEN DE LA ATENCIÓN';\n";
      $html .= "      frm.departamento.focus()\n";
      $html .= "      return;\n";
      $html .= "    }\n";       
      $html .= "    if(frm.departamento.value==\"-1\" || frm.servicio.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('div_con').innerHTML = 'DEBE SELECCIONAR LA UBICACION';\n";
      $html .= "      frm.departamento.focus()\n";
      $html .= "      return;\n";
      $html .= "    }\n";      
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar la informacion de la solicitud de  
    * autorizacion de servicios de salud
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $request vector que contiene la informacion del request
    * @param array $empresa vector que contiene la informacion de la empresa
    * @param array $paciente vector que contiene la informacion del paciente
    * @param array $tercero vector que contiene la informacion del tercero(entidad 
    * responsable del pago)
    * @param array $cargo vector con la informacion del cargo
    * @param array $coberturas vector con la informacion de la cobertura en salud
    * @param array $cargos vector con la informacion de los cargos que se encuentran en la variable de sesion
    * @param array $origenes vector con la informacion de los origenes de atencion
    *
    * @return string $html cadena que retorna el codigo html de la pagina
    */
    function formaSolicitudAutorizacionManual($action, $request, $empresa, $paciente, $tercero, $cargo, $coberturas, $cargos,$origenes)
    {
      $st = " style=\"text-align:left;padding:4px\" ";
  
     
      $tipos_ser[1]['detalle'] = "Posterior a la atención inicial de urgencias";
      $tipos_ser[2]['detalle'] = "Servicios electivos";
      
      $prioridad_ser[1]['detalle'] = "Prioritaria";
      $prioridad_ser[2]['detalle'] = "No prioritaria";
      
      $html .= ThemeAbrirTabla("SOLICITUD DE AUTORIZACION DE SERVICIOS DE SALUD");
      $html .= "<form name=\"formSolicAutorizacionServ\" id=\"formSolicAutorizacionServ\" method=\"post\" action=\"".$action['ing_solicitud_manual']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">SOLICITUD DE AUTORIZACION DE SERVICIOS DE SALUD\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $fecha = date("Y-m-d");
      $anyo = date("Y");
      $hora = date("H:i");
      if($fecha)
      {
        $fe = explode("-",$fecha);
        $f = $fe[2].'/'.$fe[1].'/'.$fe[0];
      }
      $cut = new ClaseUtil();
      $html .= $cut->IsNumeric(); 
      $html .= $cut->AcceptNum(false);
      $html .= "<input type=\"hidden\" name=\"plan_id\" value=\"".$request['plan_id']."\">\n";
      $html .= "<input type=\"hidden\" name=\"noForm\" value=\"".$request['noForm']."\">\n";
      $html .= "<input type=\"hidden\" name=\"ingreso\" value=\"".$request['ingreso']."\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"13%\">Fecha:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"27.32%\" colspan=\"2\">".$f."\n";
      $html .= "        <input type=\"hidden\" name=\"fecha\" id=\"fecha\" value=\"".$fecha."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"13%\">Hora:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"27.32%\" colspan=\"2\">".$hora."\n";
      $html .= "        <input type=\"hidden\" name=\"hora\" id=\"hora\" value=\"".$hora."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";      
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\">INFORMACION DEL PRESTADOR (solicitante)\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Nombre:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">".$empresa['razon_social']."\n";
      $html .= "      </td>\n";           
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tipo Id:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$empresa['tipo_id_tercero']."\n";
      $html .= "      </td>\n"; 
      $html .= "      <td class=\"formulacion_table_list\">Id:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$empresa['id_emp']." -- DV  ".$empresa['digito_verificacion']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Codigo:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$empresa['codigo_sgsss']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Direccion Prestador:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['direccion_emp']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Telefono:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">Ind: ".$empresa['indicativo_emp']." -- Num: ".$empresa['telefonos_emp']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Departamento:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['departamento_emp']."\n";
      $html .= "      </td>\n"; 
      $html .= "      <td class=\"formulacion_table_list\">Cod. Dpto.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['tipo_dpto_id_emp']."\n";
      $html .= "      </td>\n";      
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Municipio:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['municipio_emp']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cod. Mpio.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$empresa['tipo_mpio_id_emp']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr  align=\"center\">\n";
      $html .= "      <td class=\"modulo_table_title\" colspan=\"6\">ENTIDAD A LA QUE SE LE SOLICITA (PAGADOR)\n";      
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Nombre:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">".$tercero['nombre_tercero']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Codigo:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$tercero['codigo_sgsss_p']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">DATOS DEL PACIENTE\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Primer Apellido:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['primer_apellido_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Segundo Apellido:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['segundo_apellido_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Primer Nombre:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['primer_nombre_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Segundo Nombre:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['segundo_nombre_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tipo Documento:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$request['tipoId']."\n";
      $html .= "        <input type=\"hidden\" name=\"tipoId_u\" id=\"tipoId_u\" value=\"".$request['tipoId']."\">";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">No. Documento:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$request['noId']."\n";
      $html .= "        <input type=\"hidden\" name=\"noId_u\" id=\"noId_u\" value=\"".$request['noId']."\">";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if($paciente['fecha_nacimiento_u'])
      {
        $fn = explode('-', $paciente['fecha_nacimiento_u']);
        $fnac = $fn[2].'/'.$fn[1].'/'.$fn[0];
      }
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Fecha Nacimiento:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">".$fnac."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Direccion Residencia:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['residencia_direccion_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Telefono:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['residencia_telefono_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Departamento:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['departamento_u']."\n";
      $html .= "      </td>\n"; 
      $html .= "      <td class=\"formulacion_table_list\">Cod. Dpto.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['tipo_dpto_id_u']."\n";
      $html .= "      </td>\n";      
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Municipio:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['municipio_u']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cod. Mpio.:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['tipo_mpio_id_u']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tel celular:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['celular_telefono']."\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Correo electronico:\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$paciente['email']."\n";      
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td ".$st.">Cobertura en salud:</td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" colspan=\"5\">".$coberturas['regimen_descripcion']."\n";
      $html .= "        <input type=\"hidden\" name=\"cobertura\" id=\"cobertura\" value=\"".$coberturas['regimen_res_3047']."\">";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\">INFORMACION DE LA ATENCION Y SERVICIOS SOLICITADOS\n";      
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td ".$st." >Origen de la atencion:</td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" colspan=\"5\">\n";
      $html .= "        ".$origenes[$request['origen_atencion']]['origenes_atencion_descripcion']." ";
      $html .= "        <input type=\"hidden\" name=\"origen_atencion\" value=\"".$request['origen_atencion']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $dept_serv = explode("/", $request['departamento'] );
      $html .= "    <input type=\"hidden\" name=\"departamento\" value=\"".$dept_serv[0]."\">\n";
      $html .= "    <input type=\"hidden\" name=\"desc_depto\" value=\"".$dept_serv[3]."\">\n";
      $html .= "    <input type=\"hidden\" name=\"servicio\" value=\"".$dept_serv[1]."\">\n";
      $html .= "    <input type=\"hidden\" name=\"desc_serv\" value=\"".$dept_serv[2]."\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td ".$st." colspan=\"2\">Ubicacion del Paciente al realizar la solicitud:</td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" colspan=\"4\">".$dept_serv[3]."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";      
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td ".$st." colspan=\"2\">Prioridad del servicio:</td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" colspan=\"4\">\n";
      $html .= "        ".$prioridad_ser[$request['prioridad_servicio']]['detalle']."\n";
      $html .= "        <input type=\"hidden\" name=\"prioridad_servicio\" value=\"".$request['prioridad_servicio']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";      
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td ".$st." colspan=\"2\">Tipo de servicios solicitados:</td>\n";
      $html .= "      <td ".$st." class=\"modulo_list_claro\" colspan=\"4\">\n";
      $html .= "        ".$tipos_ser[$request['tipo_servicio']]['detalle']."\n";
      $html .= "        <input type=\"hidden\" name=\"tipo_servicio\" value=\"".$request['tipo_servicio']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Servicio:\n";
      $html .= "      </td>\n";
      //$html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$cargo['desc_servicio']."\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$dept_serv[2]."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cama:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"16.66%\" align=\"center\">Codigo CUPS\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"16.66%\" align=\"center\">Cantidad\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"66.68%\" align=\"left\" colspan=\"4\">Descripcion \n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $est = "modulo_list_claro";
      foreach($cargos as $indice => $valor)
      {
        $html .= "    <tr class=\"".$est."\">\n";
        $html .= "      <td width=\"16.66%\" align=\"center\">".$valor['cargo']."\n";
        $html .= "        <input type=\"hidden\" name=\"cargos\" value=\"".$valor['cargo']."\">";
        $html .= "      </td>\n";
        $html .= "      <td width=\"16.66%\" align=\"center\">".$valor['cantidad']."\n";
        $html .= "      </td>\n";
        $html .= "      <td width=\"66.68%\" align=\"left\" colspan=\"4\">".$valor['desc_cargo']."\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
      }
      $html .= "    <tr>\n";      
      $html .= "      <td align=\"center\" colspan=\"6\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr align=\"center\">\n";
      $html .= "    <td>\n";
      $html .= "      <a class=\"label_error\" href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma que permite indicar al usuario que se almaceno la
    * informacion de las inconsistencias. Ademas permite generar el reporte PDF
    * 
    * @param array $action vector que contiene los link de la aplicacion
    * @param string $mensaje cadena con el mensaje que se muestra al usuario
    * @param string $RUTA cadena con la ruta en la cual se encuentra el archivo pdf
    * @param integer $opcion Indica cual de los reportes se esta imprimiendo
    *
    * @return string $html cadena con el codigo html de la pagina
    */
    function FormaBuscarDocumentos($action, $tipos_id, $request,$datos)
    {
      $xml = Autocarga::factory("ReportesCsv");
      $datos['inicio'] = 4;
      
      $html = "";
      $fncn = "";
      $datos['fecha'] = $request['fecha'];
      $datos['formulario_no'] = $request['consec'];
      switch($opcion)
      {
        case '1':
          $datos['ingreso'] = $request['ingreso'];
          $html .= $xml->GetJavacriptReporteXml('app','ReclamacionServicios','InformePresuntaInconsistencia',$datos,array("interface"=>4));
          $fncn  = $xml->GetJavaFunction();

          $html .= $xml->GetJavacriptReporteFPDF('app','ReclamacionServicios','InformePresuntaInconsistencia',$datos,array("interface"=>5));
          $fnc1  = $xml->GetJavaFunction();
        break;
        case '2':
          $datos['ingreso'] = $request['ingreso'];
          $datos['paciente_id'] = $request['noId_u'];
          $datos['tipo_id_paciente'] = $request['tipoId_u'];
          $html .= $xml->GetJavacriptReporteXml('app','ReclamacionServicios','InformeUrgencias',$datos,array("interface"=>4));
          $fncn  = $xml->GetJavaFunction();
          
          $html .= $xml->GetJavacriptReporteFPDF('app','ReclamacionServicios','InformeUrgencias',$datos,array("interface"=>5));
          $fnc1  = $xml->GetJavaFunction();
        break;
        case '3':
          $datos['paciente_id'] = $request['noId_u'];
          $datos['tipo_id_paciente'] = $request['tipoId_u'];
          $html .= $xml->GetJavacriptReporteXml('app','ReclamacionServicios','SolicitudAutorizacionServicios',$datos,array("interface"=>4));
          $fncn  = $xml->GetJavaFunction();
          
          $html .= $xml->GetJavacriptReporteFPDF('app','ReclamacionServicios','SolicitudAutorizacionServicios',$datos,array("interface"=>5));
          $fnc1  = $xml->GetJavaFunction();
        break;
      }
      $st = "style=\"text-align:left;padding:4px\"";
      $html .= "<script>\n";
      $html .= "  function EvaluarDatos(objeto)\n";
      $html .= "  {\n";
      $html .= "    msg = document.getElementById('error')\n";
      $html .= "    if(objeto.tipo_id_paciente.value == '-1')\n";
      $html .= "    {\n";
      $html .= "      msg.innerHTML = 'SE DEBE SELECCIONAR EL TIPO DE DOCUMENTO';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(objeto.paciente_id.value == '')\n";
      $html .= "    {\n";
      $html .= "      msg.innerHTML = 'SE DEBE INGRESAR EL NUMERO DEL DOCUMENTO';\n";
      $html .= "      return;\n";
      $html .= "    }\n";      
      $html .= "    if(!objeto.formulario[0].checked && !objeto.formulario[1].checked && !objeto.formulario[2].checked)\n";
      $html .= "    {\n";
      $html .= "      msg.innerHTML = 'SE DEBE SELECCIONAR EL TIPO DE FORMULARIO';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    objeto.action = '".$action['buscador']."';\n";
      $html .= "    objeto.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeAbrirTabla('BUSCAR DOCUMENTOS');
			$html .= "		    <form name=\"buscador\" action=\"javascript:EvaluarDatos(document.buscador)\" method=\"post\">\n";
			$html .= "		      <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "			      <tr class=\"formulacion_table_list\">\n";
			$html .= "				      <td colspan=\"2\">BUSCADOR</td>\n";
			$html .= "			      </tr>\n";
      $html .= "			      <tr class=\"formulacion_table_list\">\n";
			$html .= "				      <td ".$st.">TIPO DOCUMENTO</td>\n";
			$html .= "				      <td ".$st." class= \"modulo_list_claro\">\n";
			$html .= "					      <select name=\"tipo_id_paciente\" class=\"select\">\n";
			$html .= "						      <option value='-1'>-----SELECCIONAR-----</option>\n";
			$sel="";
			foreach($tipos_id as $key => $dtl)
			{
				($request['tipo_id_paciente'] == $dtl['tipo_id_paciente'])? $sel = "selected":$sel="";
				$html .= "					       <option value='".$dtl['tipo_id_paciente']."' ".$sel." >".ucwords(strtolower($dtl['descripcion']))."</option>\n";
			}
			$html .= "						    </select>\n";
			$html .= "				      </td>\n";
			$html .= "				    </tr>\n";
      $chk[$request['formulario']] = "checked";
			$html .= "				    <tr class=\"formulacion_table_list\">\n";
			$html .= "				      <td ".$st.">DOCUMENTO</td>\n";
			$html .= "				      <td ".$st." class= \"modulo_list_claro\">\n";
			$html .= "					      <input type=\"text\" class=\"input-text\" name=\"paciente_id\" style=\"width:90%\" maxlength=\"32\"  value=\"".$request['paciente_id']."\">\n";
			$html .= "				      </td>\n";
			$html .= "			      </tr>\n";			
      $html .= "				    <tr class=\"formulacion_table_list\">\n";
			$html .= "				      <td ".$st.">Nº FORMULARIO</td>\n";
			$html .= "				      <td ".$st." class= \"modulo_list_claro\">\n";
			$html .= "					      <input type=\"text\" class=\"input-text\" name=\"no_formulario\" style=\"width:90%\" maxlength=\"32\"  value=\"".$request['no_formulario']."\">\n";
			$html .= "				      </td>\n";
			$html .= "			      </tr>\n";
			$html .= "			      <tr class=\"formulacion_table_list\">\n";
			$html .= "				      <td ".$st." colspan=\"2\">TIPO DE FORMULARIO</td>\n";
			$html .= "			      </tr>\n";
			$html .= "			      <tr class= \"modulo_list_claro\">\n";
      $html .= "				      <td colspan=\"2\" class=\"label\" ".$st.">\n";
			$html .= "					      <input type=\"radio\" name=\"formulario\" value=\"1\" ".$chk[1].">FORMULARIO DE INCONSISTENCIAS EN LOS DATOS DEL PACIENTE\n";
			$html .= "				      </td>\n";
			$html .= "			      </tr>\n";			
      $html .= "			      <tr class= \"modulo_list_claro\">\n";
      $html .= "				      <td colspan=\"2\" class=\"label\" ".$st.">\n";
			$html .= "					      <input type=\"radio\" name=\"formulario\" value=\"2\" ".$chk[2].">FORMULARIO DE ATENCION INICIAL DE URGENCIA\n";
			$html .= "				      </td>\n";
			$html .= "			      </tr>\n";			
      $html .= "			      <tr class= \"modulo_list_claro\">\n";
      $html .= "				      <td colspan=\"2\" class=\"label\" ".$st.">\n";
			$html .= "					      <input type=\"radio\" name=\"formulario\" value=\"1\" ".$chk[3].">FORMULARIO DE SOLICITUD DE AUTORIZACION DE SERVICIOS DE SALUD\n";
			$html .= "				      </td>\n";
			$html .= "			      </tr>\n";
			$html .= "			      <tr class= \"modulo_list_claro\">\n";
			$html .= "				      <td class=\"label\" align=\"center\" colspan=\"2\">\n";
			$html .= "				        <table align=\"center\" width=\"80%\">\n";
			$html .= "				          <tr>\n";
			$html .= "				            <td align=\"center\">\n";
			$html .= "					            <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
      $html .= "                    </td>\n";
      $html .= "                    <td align=\"center\">\n";
			$html .= "					            <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.buscador)\">\n";
			$html .= "				            </td>\n";
			$html .= "			            </tr>\n";
			$html .= "			          </table>\n";
			$html .= "	            </td>\n";
			$html .= "	          </tr>\n";
			$html .= "	        </table>\n";
			$html .= "	      </form>\n";
			$html .= "	      <center><div id=\"error\" class=\"label_error\"></div><center>\n";
      
      /*$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"2\">\n";
      $html .= "      <table width=\"100%\" class=\"modulo_table_list\">\n";
      $html .= "        <tr class=\"normal_10AN\">\n";
      $html .= "          <td align=\"center\">\n".$mensaje."</td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr align=\"center\">\n";
      $html .= "    <td><br>\n";
      $html .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir_doc\" value=\"Imprimir Doc.\" onclick=\"".$fnc1."\">\n";
      $html .= "    </td>\n";
      $html .= "    <td>\n";
      $html .= "      <br>\n";
      $html .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir_xml\" value=\"Documento Xml\" onclick=\"".$fncn."\">\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";*/

      $html .= "<br>";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a class=\"label_error\" href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $html .= ThemeCerrarTabla();
      return $html;
    }
  }  
?>