<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ParametrizacionInicialHTML.class.php,v 1.1 2009/09/14 08:19:24
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  /**
  * Clase Vista: ParametrizacionInicialHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.5 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  class ParametrizacionInicialHTML
  {
    /**
    * Constructor de la clase
    */
    function ParametrizacionInicialHTML(){}
    /**
    * Funcion donde se crea la forma para el menu de Parametrizacion tiempo de cita
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaMenu($action)
    {
      $html  = ThemeAbrirTabla('PARAMETRIZAR TIEMPO DE CITA');
      $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td align=\"center\">MENU\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['parametrizar_tiempo_cita_planes']."\" class=\"label_error\">PARAMETRIZAR TIEMPO CITA - PLANES</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "   <tr class=\"modulo_list_oscuro\">\n";
      $html .= "     <td align=\"center\">\n ";
      $html .= "       <a href=\"".$action['parametrizar_especialidades']."\" class=\"label_error\">PARAMETRIZAR ESPECIALIDADES</a>\n";
      $html .= "     </td>\n";
      $html .= "  </tr>\n";
      $html .= "   <tr class=\"modulo_list_claro\">\n";
      $html .= "     <td align=\"center\">\n ";
      $html .= "       <a href=\"".$action['parametrizar_usuariosplanes']."\" class=\"label_error\">PARAMETRIZAR USUARIOS - PLANES</a>\n";
      $html .= "     </td>\n";
      $html .= "  </tr>\n";
      $html .= "   <tr class=\"modulo_list_oscuro\">\n";
      $html .= "     <td align=\"center\">\n ";
      $html .= "       <a href=\"".$action['parametrizar_servicios']."\" class=\"label_error\">PARAMETRIZAR SERVICIOS</a>\n";
      $html .= "     </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    /**
    * Funcion donde se crea la forma que permite realizar la busqueda de la informacion de
    * los planes
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $datos  vector con la informacion de los planes
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaBuscarPlanes($datos,$action)
    {
      $html  = ThemeAbrirTabla('BUSQUEDA DE PLANES');
      $clas = AutoCarga ::factory("ClaseUtil");
      $html .= $clas->RollOverFilas();
      $html .= $clas->AcceptNum();
      $html .= $clas->IsNumeric();
      $html .= "<form name=\"formBuscarPlanes\" id=\"formBuscarPlanes\" method=\"post\" action=\"".$action['guardarTiempoC']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"2\" align=\"center\">BUSCADOR\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "    <td class=\"formulacion_table_list\"align=\"left\" width=\"25%\">PLANES\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"modulo_list_claro\"><select width=\"50%\" class=\"select\" name=\"planes\" id=\"planes\" onchange=\"MostrarDias()\">\n";
      $html .= "       <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach ($datos as $indice=>$valor)
      {
          $html .= " <option value=\"".$valor['plan_id']."\">".$valor['plan_descripcion']."</option>";
      }
      $html .= "     </select>\n";
      $html .= "    </td>\n";
      $html .= "    </tr>\n";
      $html .= "    </table>\n";
      $html .= "  <div align=\"center\" class=\"label_error\"id='tiempocita'></div>";
      
      $html .= "</form>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<script>";
      $html .= "  function MostrarDias()\n";
      $html .= "  {\n";
      $html .= "    frm=document.formBuscarPlanes;\n";
      $html .= "      if(frm.planes.value =='-1')\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('tiempocita').innerHTML='DEBE SELECCIONAR UN PLAN';\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      else\n";
      $html .= "      {\n";
      $html .= "        xajax_MostrarDCitas(xajax.getFormValues(formBuscarPlanes));\n";
      $html .= "      }\n";
      $html .= "  }\n";
      $html .= "  function Asignar(frm)\n";
      $html .= "  {\n";
      $html .= "    cant=frm.elements.length;\n";
      $html .= "    for(i=0;i<cant;i++)\n";
      $html .= "    {\n";
      $html .= "      elem=frm.elements[i];\n";
      $html .= "      if(elem.type=='text')\n";
      $html .= "        elem.value=document.formBuscarPlanes.NDias.value;\n";
      $html .= "    }\n";
      $html .= "    return;\n";
      $html .= "  }\n";
      $html .= "  function ValidarDias()\n";
      $html .= "  {\n";
      $html .= "    vdias=document.getElementsByName('DiaCita[]');\n";
      $html .= "    j=0;\n";
      $html .= "    for(i=0;i< vdias.length ;i++)\n";
      $html .= "    {\n";
      $html .= "      elem = vdias[i].value;\n";
      $html .= "      if(IsNumeric(elem))\n";
      $html .= "      {\n";
      $html .= "        j++;\n";
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "    if(j==0)\n";
      $html .= "    {\n";
      $html .= "        alert('DEBE INGRESAR AL MENOS UN DIA CITAS');\n";
      $html .= "    }\n";
      $html .= "    else\n";
      $html .= "    {\n";
      $html .= "      document.formBuscarPlanes.submit();\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "</script>";
       
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    /**
    * Funcion donde se crea la forma que permite realizar la busqueda de la informacion de
    * los cargos
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $datos  vector con la informacion de los cargos
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaBuscarCargos($datos,$action,$empresa_id)
    {
      $html  = ThemeAbrirTabla('BUSQUEDA DE CARGOS');
      
      $clas = AutoCarga ::factory("ClaseUtil");
      $html .= $clas->RollOverFilas();
      $html .= $clas->IsNumeric();
      $html .= $clas->AcceptNum();
      $html .= "<form name=\"formaBuscarCargos\" id=\"formBuscarCargos\" method=\"post\" action=\"".$action['guardarCargo']."\">\n";
      $html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
      $html .= "  <tr class=\"modulo_table_title\">\n";
      $html .= "    <td colspan=\"2\" align=\"center\">BUSCADOR\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "  <td class=\"formulacion_table_list\"align=\"left\" width=\"25%\">TIPOS DE CONSULTA\n";
      $html .= "  </td>\n";
      $html .= "  <td class=\"modulo_list_claro\"><select width=\"50%\" class=\"select\" name=\"cargos\" id=\"cargos\" onchange=\"MostrarCargos()\">\n";
      $html .= "    <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach ($datos as $indice=>$valor)
      {
        $html .= "  <option value=\"".$valor['tipo_consulta_id']."\">".$valor['descripcion']."</option>";
      }
      $html .= "    </select>\n";
      $html .= "   </td>\n";
      $html .= "   </tr>\n";
      $html .= "      <input type=\"hidden\" class=\"input-text\" name=\"empresa\" value=\"".$empresa_id."\" >\n";
      $html .= "  </table>\n";
      $html .= "  <div align=\"center\" class=\"label_error\"id='tipocargo'></div>";
      
      $html .= "</form>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<script>";
      $html .= "  function MostrarCargos()\n";
      $html .= "  {\n";
      $html .= "    frm=document.formaBuscarCargos;\n";
      $html .= "      if(frm.cargos.value =='-1')\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('tipocargo').innerHTML='DEBE SELECCIONAR UN TIPO DE CONSULTA';\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      else\n";
      $html .= "      {\n";
      $html .= "        xajax_MostrarCargo(xajax.getFormValues(formaBuscarCargos));\n";
      $html .= "      }\n";
      $html .= "  }\n";
      $html .= "  function ValidarPrioridades()\n";
      $html .= "  {\n";
      $html .= "    vprioridad=document.getElementsByName('Prioridad[]');\n";
      $html .= "    vcargo=document.getElementsByName('cargostiemp[]');\n";
      $html .= "    frm=document.formaBuscarCargos;\n";
      $html .= "    for(i=0;i< vprioridad.length ;i++)\n";
      $html .= "    {\n";
     
      $html .= "        elem = vprioridad[i].value;\n";
      $html .= "        if(elem == '1')\n";
      $html .= "        {\n";
      $html .= "          vcargo[i].value=0;\n";
      $html .= "        }\n";
      $html .= "        else if(elem != '-1')\n";
      $html .= "        {\n";
      
      $html .= "          if(!IsNumeric(vcargo[i].value))\n";
      $html .= "          {\n";
      $html .= "              alert('EL TIEMPO ES OBLIGATORIO');\n";
      $html .= "              return;\n";
      $html .= "          }\n";
      $html .= "        }\n";
      $html .= "     }\n";
      $html .= "   frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>";
      
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    /**
    * Funcion donde se crea la forma que listar los usuarios con permisos para asignar los planes
    *  
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $datos  vector con la informacion de los planes
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaBuscarUsuariosplanes($datos,$action)
    {
      $html  = ThemeAbrirTabla('BUSQUEDA DE USUARIOS POR PLANES');
      $clas = AutoCarga ::factory("ClaseUtil");
      $html .= $clas->RollOverFilas();
      $html .= $clas->AcceptNum();
      $html .= $clas->IsNumeric();
      $html .= "<form name=\"formBuscarUsuariosplanes\" id=\"formBuscarUsuariosplanes\" method=\"post\" action=\"".$action['guardarTiempoC']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"60%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td class=\"formulacion_table_list\"align=\"left\" width=\"25%\" colspan=\"3\">PLANES\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td  align=\"center\" width=\"10%\">USUARIO\n";
      $html .= "      </td>\n";
      $html .= "      <td  align=\"center\"width=\"40%\">NOMBRE\n";
      $html .= "      </td>\n";
      $html .= "      <td align=\center\"width=\"10%\">ASIGNAR PLANES\n";
      $html .= "      </td>";
      $html .= "    </tr>";
      foreach ($datos as $indice=>$valor)
      {
        ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
        ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
  						
  			$html .= "	<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
        $html .= "      <td>".$valor['id']."</td>";
        $html .= "      <td>".$valor['usuario']."</td>";
        $html .= "      <td>";
        //$html .= "        <input type=\"checkbox\" name=\"Asignarplanes \" value=\"Asignar \"> Asignar\n";
        $html .= "          <a href=\"".$action['asignarplanes'].URLRequest(array("usuario_id"=>$valor['id'],"usuario"=>$valor['usuario']))."\" class=\"label_error\">\n";
        $html .= "            <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\">ASIGNAR";
        $html .= "          </a>";
        $html .= "      </td>";
        $html .= "  </tr>";
      }
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "    <table align=\"center\">\n";
      $html .= "      <tr>\n";
      $html .= "        <td>\n";
      $html .= "          <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "    </table>\n";
          
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    /**
    * Funcion donde se crea la forma que asigna los planes a un usuario
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $datos  vector con la informacion de los planes
    * @param array $consultar_usuarios  vector donde se encuentra los usuarios con permiso
    * @return string $html retorna la cadena con el codigo html de la pagina 
    */
    function formaAsignarplanes($datos,$action,$consultar_usuarios)
    {
      $html  = ThemeAbrirTabla('ASIGNAR PLANES');
      $clas = AutoCarga ::factory("ClaseUtil");
      $html .= $clas->RollOverFilas();
      $html .= $clas->AcceptNum();
      $html .= $clas->IsNumeric();
      
      $key = key($consultar_usuarios);
      //print_r($consultar_usuarios[$key]);
	  $html .= "<form name=\"formAsignarplanes\" id=\"formAsignarplanes\" method=\"post\" action=\"".$action['guardarAsignacionPlanes']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"60%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td class=\"formulacion_table_list\"align=\"left\" width=\"25%\" colspan=\"3\">ASIGNACION DE LOS PLANES\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "     <td>\n";
      
      $html .= "      <input type=\"radio\" name=\"TodosPlanes\" value=\"1\" ".(($key == "1")? "checked": "" ).">";
      $html .= "      <align=\"center\" width=\"10%\" class=\"label_error\">TODOS LOS PLANES\n";
      $html .= "     </td>\n";
      $html .= "    </tr>";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "     <td>\n";
      $html .= "      <input type=\"radio\" name=\"TodosPlanes\" value=\"0\" ".(($key == "0")? "checked": "" ).">";
      $html .= "      <align=\"center\" width=\"10%\" class=\"label_error\">PLAN ESPECIFICO \n";
      $html .= "     </td>\n";
      $html .= "    </tr>";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"60%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td class=\"formulacion_table_list\"align=\"left\" width=\"25%\" colspan=\"3\">PLANES\n";
      //$html .= "       <input type=\"checkbox\" name=\"todosplanes \" value=\"todosplanes \">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      foreach ($datos as $indice=>$valor)
      {
        ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
        ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
  		
  			$html .= "	<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
        $html .= "      <td>".$valor['plan_descripcion']."</td>";
        $html .= "      <td>";
        $html .= "       <input type=\"checkbox\" name=\"todosplanest[]\" value=\"".$valor['plan_id']."\" ".(($consultar_usuarios[$key][$valor['plan_id']]['plan_id'] == $valor['plan_id'])? "checked": "" )."> \n";
        $html .= "       <input type=\"hidden\" class=\"input-text\" name=\"insert[]\" value=\"".$valor['plan_id']."\" >\n";
        $html .= "      <input type=\"hidden\" name=\"plan_id\" value=\"".$valor['descripcion']."\"size=\"5\">\n";
        $html .= "      </td>";
        $html .= "  </tr>";
      }
      $html .= "   </table><br>\n";
      
      $html .= " <table align=\"center\" width=\"50%\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "         <input class=\"input-submit\" type=\"submit\" value=\"Guardar\" onclick=\"ValidarSeleccionP()\">\n";
      $html .= "      </td>\n";
      $html .= "  </form>\n";
      $html .= "  <form name=\"volver\" method=\"post\" action=\"".$action['volver']."\">\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "         <input class=\"input-submit\" type=\"submit\" value=\"Volver\" name=\"volver\">\n";
      //$html .= "        <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
      $html .= "      </td>\n";
      $html .= "      </tr>\n";
      $html .= "    </table>\n";
      //$html .= "  </table>\n";
      $html .= "<script>";
      $html .= "  function ValidarSeleccionP()\n";
      $html .= "  {\n";
      $html .= "    bandera = false;\n";
      $html .= "    frm=document.formAsignarplanes;\n";
      $html .= "    todplanes=document.getElementsByName('todosplanest[]');\n";
      $html .= "    if (!frm.TodosPlanes[0].checked  && !frm.TodosPlanes[1].checked)\n";
      $html .= "    {\n";
      $html .= "      alert ('DEBE ELEGIR UNA OPCION');\n";
		  $html .= "      return false;\n";
      $html .= "    }\n";
      $html .= "    if(frm.TodosPlanes[1].checked)\n";
      $html .= "    {\n";
      $html .= "      for(i=0;i<todplanes.length;i++)\n";
      $html .= "      {\n";
      $html .= "        elem = todplanes[i];\n";
      $html .= "        if(elem.checked)\n";
      $html .= "        {\n";
      $html .= "          bandera=true;\n";
      $html .= "        }\n";
      $html .= "      }\n";
      $html .= "      if(bandera==false)\n";
      $html .= "      {\n";
      $html .= "        alert ('DEBE ELEGIR UN PLAN');\n";
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "    else\n";
      $html .= "    {\n";
      $html .= "     frm.submit();\n ";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "</script>";  
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    /**
    * Funcion donde se crea la forma de mensaje
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaMensajeInTc($action,$mensaje)
    {
      $html  = ThemeAbrirTabla('MENSAJE',500);
      $html .= "<table class=\"modulo_table_list\"align=\"center\">\n ";
      $html .= "<tr>\n";
      $html .= "<td> ".$mensaje." </td>\n";
      $html .= "</tr>\n";
      $html .= "</table>\n";
      $html .= "<table align=\"center\">";
      $html .= "<tr>\n";
      $html .= "<td>\n";
      $html .= "  <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
      $html .= "</td>";
      $html .= "</tr>";
      $html .= "</table>";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma que permite la parametrizacion de los servicios
    *
    * @param array $action Arreglo de datos con los links
    * @param array $servcios Arreglo con los datos de los servicios
    *
    * @return string
    */
    function FormaParametrizarServicios($action,$servicios)
    {      
      $html  = "<script>\n";
      $html .= "  function ActivarFechaIncapacidad(estado,servicio)\n";
      $html .= "  {\n";
      $html .= "    xajax_ActivarFechaIncapacidad(estado,servicio);\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeAbrirTabla('PARAMETRIZAR SERVICIOS');
      $html .= "<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td width=\"10%\">SERVICIO</td>\n";
      $html .= "    <td width=\"%\"  >DESCRIPCIÓN</td>\n";
      $html .= "    <td width=\"20%\" colspan=\"2\">MODIFICAR FECHA INCAPACIDAD</td>\n";
      $html .= "  </tr>\n";
      $cls = "modulo_list_claro";
      foreach($servicios as $key => $dtl)
      {
        ($cls == "modulo_list_oscuro")? $cls = "modulo_list_claro" : $cls = "modulo_list_oscuro";
        $html .= "  <tr class=\"".$cls."\">\n";
        $html .= "    <td>".$dtl['servicio']."</td>\n";
        $html .= "    <td>".$dtl['descripcion']."</td>\n";
        $html .= "    <td width=\"5%\" class=\"normal_10AN\" align=\"center\">\n";
        $html .= "      <div id=\"servicio_letra_".$dtl['servicio']."\">".(($dtl['sw_fecha_incapacidad'] == '1')? "SI":"NO")."</div>\n";        
        $html .= "    </td>\n";
        $html .= "    <td>\n";
        $html .= "      <div id=\"servicio_link_".$dtl['servicio']."\">\n";
        $html .= "        <a href=\"javascript:ActivarFechaIncapacidad('".(($dtl['sw_fecha_incapacidad'] == '1')? "0":"1")."','".$dtl['servicio']."')\" class=\"label_error\">\n";
        $html .= "          <img src=\"".GetThemePath()."/images/".(($dtl['sw_fecha_incapacidad'] == '1')? "checksi":"checkno").".png\" border=\"0\">".(($dtl['sw_fecha_incapacidad'] == '1')? "INACTIVAR":"ACTIVAR")."\n";
        $html .= "        </a>\n";
        $html .= "      </div>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "</table><br>\n";
      $html .= "<center>\n";
      $html .= "  <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
      $html .= "</center>\n";

      $html .= ThemeCerrarTabla();
      
      return $html;
    }
  }  
?>