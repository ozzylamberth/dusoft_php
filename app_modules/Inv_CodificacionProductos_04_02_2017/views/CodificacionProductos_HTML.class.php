<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: CodificacionProductos_HTML.class.php,v 1.10 2009/10/06 19:07:21 mauricio Exp $ 
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */

/**
 * Clase Vista: Formularios_HTML_MenuHTML
 * Clase Contiene Metodos para el despliegue de Formularios del Módulo
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.10 $
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */
class CodificacionProductos_HTML {

    /**
     * Constructor de la clase
     */
    function CodificacionProductos_HTML()
    {
        
    }

    /**
     * @param array 
     * $action Vector de links de la aplicaion
     * 
     */
    function main($request, $Grupos, $action)
    {
        $accion = $action['volver'];
        //print_r($accion);		  
        /*
         * Funcion para Asignar a los Cajones de Texto, el Laboratorio Seleccionado
         */
        $html .="<script>";
        $html .="function Asignar(codigo_laboratorio,nombre_laboratorio)";
        $html .="{";
        $html .="document.getElementById('clase').value=codigo_laboratorio;";
        $html .="document.getElementById('descripcion_clase').value=nombre_laboratorio;";
        $html .="}";
        $html .="</script>";

        $html .="<script>";
        $html .="function AsignarCampoMolecula(codigo_molecula,nombre_subclase)";
        $html .="{";
        $html .="document.getElementById('subclase').value=codigo_molecula;";
        $html .="document.getElementById('descripcion_subclase').value=nombre_subclase;";
        $html .="}";
        $html .="</script>";

        $html .='
  <script languaje="javascript">
   function ConfirmaBorrar(tabla,id,campo_id,descripcion)
  {
        var entrar = confirm("Confirmar Borrar "+descripcion+"?")

        if (entrar) 
              {
                  xajax_BorrarGrupo(tabla,id,campo_id,descripcion);
              }
                else
                {
                  return(false);
                }
  
  
  
  }
  
  function ConfirmaBorrarClase(grupo_id,laboratorio_id,descripcion,nombregrupo,sw_medicamento)
  {
        var entrar = confirm("Confirmar Borrar "+descripcion+" de "+nombregrupo+"?")

        if (entrar) 
              {
                  xajax_BorrarClases(grupo_id,laboratorio_id,descripcion,nombregrupo,sw_medicamento);
              }
                else
                {
                  return(false);
                }
  
  
  
  }
  
  
  function ConfirmaBorrarSubClase(grupo_id,clase_id,nombreclase,nombregrupo,subclase_id,nombremolecula,sw_medicamento)
  {
        var entrar = confirm("Confirma Borrar "+ nombremolecula+" De la Clase "+ nombreclase+" Perteneciente al Grupo "+nombregrupo+"?COD: "+subclase_id)

        if (entrar) 
              {
                  xajax_BorrarSubClases(grupo_id,clase_id,nombreclase,nombregrupo,subclase_id,sw_medicamento);
              }
                else
                {
                  return(false);
                }
  
  
  
  }
  
  
  
  function ValidarIngresoGrupo(formulario)
  {
  var band=0;
  
  if(document.FormularioGrupo.grupo_id.value=="")
  {
  alert("Campo De Codigo del Grupo Está Vacío");
  band=1;
  }
  
  if(document.FormularioGrupo.descripcion.value=="")
  {
  alert("Campo De Nombre Grupo Está Vacío");
  band=1;
  }
  
  if(document.FormularioGrupo.sw_medicamento[1].checked==false && document.FormularioGrupo.sw_medicamento[0].checked==false)
  {
  alert("No haz definido si es Medicamento o Insumo!!");
  band=1;
  }
  
  if(band==1)
          {
          alert("Por Favor, llenar los Campos Vacíos!!!");
          }
          
          else
          {
              //alert("Formulario bien diligenciado!!!");
              var entrar = confirm("Confirmar Envio de datos?")
              if (entrar) 
              {
                //alert("Haz dado en Aceptar");
               if(document.FormularioGrupo.token.value=="1") //Si es Ingreso de Laboratorio
                  {
                  xajax_InsertarGrupo(formulario);
                  //alert("ingreso");
                  }
                       else
                          {
                          xajax_GuardarModGrupo(formulario);
                         // alert("Modi");
                          }
               
                
              }
                else
                {
                  alert("Haz Cancelado");
                                   
                }
          }
  
  }
  
  //Para Busquedas de Clases
 function Busqueda()
  {
        
        var nombre= document.buscador.descripcion.value;
        var codigo= document.buscador.clase_id.value;
        var grupo= document.buscador.GrupoId.value;
        var nombregrupo= document.buscador.NombreGrupo.value;
        var sw_medicamento= document.buscador.sw_medicamento.value;
        
        if(document.buscador.buscar_en.value=="1")
        {
        //alert(sw_medicamento);
        
        xajax_BusquedaClasesAsignadas(nombregrupo,grupo,nombre,codigo,sw_medicamento);
        }
        else
        {
        //alert("opcion 2");
        xajax_BusquedaClasesNoAsignadas(nombregrupo,grupo,nombre,codigo,sw_medicamento);
        }
  }
  
  //Para Busquedas de SubClases
  function Busqueda_()
  {
        //Parametros de Busqueda
        var nombre= document.buscador.descripcion.value;
        var codigo= document.buscador.clase_id.value;
        
        //Datos Anexos
        var grupo= document.buscador.GrupoId.value; //Codigo de Grupo
        var clase= document.buscador.ClaseId.value; //Codigo de Clase
        var nombregrupo= document.buscador.NombreGrupo.value; //Nombre del Grupo
        var nombreclase= document.buscador.NombreClase.value; //Nombre de la Clase
        var sw_medicamento= document.buscador.Sw_Medicamento.value; //Si es Medicamento o Insumos
       
        
        if(document.buscador.buscar_en.value=="1")
        {
       // alert(nombregrupo);
        
        xajax_BusquedaSubClasesAsignadas(nombregrupo,nombreclase,grupo,clase,nombre,codigo,sw_medicamento);
        }
        else
        {
        //alert(sw_medicamento);
        xajax_BusquedaSubClasesNoAsignadas(nombregrupo,nombreclase,grupo,clase,nombre,codigo,sw_medicamento);
        }
  }
  
  
  function acceptNum(evt)
{ 
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
}
  
  
  </script>
  ';









        $html .= ThemeAbrirTabla('CLASIFICACION GENERAL DE LOS PRODUCTOS');

        //URL CREACION DE PRODUCTO
        $Url = ModuloGetURL("app", "Inv_CodificacionProductos", "controller", "Crear_Productos");
        //FIN URL    
        $html .= "<BR><BR><CENTER><a class=\"label_error\" href=\"#\" onclick=\"xajax_IngresoGrupo()\">[::CREAR UN NUEVO GRUPO::]</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class=\"label_error\" href=\"" . $Url . "&datos[empresa_id]=" . $_REQUEST['datos']['empresa_id'] . "&datos[sw_tipo_empresa]=" . $_REQUEST['datos']['sw_tipo_empresa'] . "\">[::CREAR UN NUEVO PRODUCTO::]</a><BR></CENTER>";

        $html .= "<script>\n";




        //5) Paso LLamado a funcion: Crea un intermediario para el llamado a la funcion xajax
        $html .= "  function Buscador(CodigoGrupo,NombreGrupo,Sw_Medicamento,offset)\n";
        $html .= "  {";
        $html .= "    xajax_ClasesSubclases(CodigoGrupo,NombreGrupo,Sw_Medicamento,offset);\n";
        $html .= "  }\n";

        $html .= "  function paginador(CodigoGrupo,CodigoClase,NombreClase,NombreGrupo,Sw_Medicamento,offset)\n";
        $html .= "  {";
        $html .= "    xajax_SubclasesNoAsignadas(CodigoGrupo,CodigoClase,NombreClase,NombreGrupo,Sw_Medicamento,offset);\n";
        $html .= "  }\n";


        $html .= "  function paginador_(CodigoGrupo,CodigoClase,NombreClase,NombreGrupo,Sw_Medicamento,offset)\n";
        $html .= "  {";
        $html .= "    xajax_SubclasesAsignadas(CodigoGrupo,CodigoClase,NombreClase,NombreGrupo,Sw_Medicamento,offset);\n";
        $html .= "  }\n";


        $html .= "  function Buscador___(CodigoGrupo,NombreGrupo,Sw_Medicamento,offset)\n";
        $html .= "  {";
        $html .= "    xajax_AsignarClases(CodigoGrupo,NombreGrupo,Sw_Medicamento,offset);\n";
        $html .= "  }\n";

        $html .= "  function paginador_busquedasNAsing(nombregrupo,nombreclase,grupo,clase,nombre,codigo,sw_medicamento,offset)\n";
        $html .= "  {";
        $html .= "    xajax_BusquedaSubClasesNoAsignadas(nombregrupo,nombreclase,grupo,clase,nombre,codigo,sw_medicamento,offset);\n";
        $html .= "  }\n";

        $html .= "  function paginador_busquedasAsing(nombregrupo,nombreclase,grupo,clase,nombre,codigo,sw_medicamento,offset)\n";
        $html .= "  {";
        $html .= "    xajax_BusquedaSubClasesAsignadas(nombregrupo,nombreclase,grupo,clase,nombre,codigo,sw_medicamento,offset);\n";
        $html .= "  }\n";

        $html .= "  function Buscador_(NombreGrupo,Grupo,Nombre,Codigo,Sw_Medicamento,offset)\n";
        $html .= "  {";
        $html .= "    xajax_BusquedaClasesNoAsignadas(NombreGrupo,Grupo,Nombre,Codigo,Sw_Medicamento,offset);\n";
        $html .= "  }\n";

        $html .= "  function Buscador__(NombreGrupo,Grupo,Nombre,Codigo,Sw_Medicamento,offset)\n";
        $html .= "  {";
        $html .= "    xajax_BusquedaClasesAsignadas(NombreGrupo,Grupo,Nombre,Codigo,Sw_Medicamento,offset);\n";
        $html .= "  }\n";





        $html .= "  function mOvr(src,clrOver)\n";
        $html .= "  {\n";
        $html .= "    src.style.background = clrOver;\n";
        $html .= "  }\n";
        $html .= "  function mOut(src,clrIn)\n";
        $html .= "  {\n";
        $html .= "    src.style.background = clrIn;\n";
        $html .= "  }\n";
        $html .= "  function acceptDate(evt)\n";
        $html .= "  {\n";
        $html .= "    var nav4 = window.Event ? true : false;\n";
        $html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
        $html .= "    return (key <= 13 ||(key >= 47 && key <= 57));\n";
        $html .= "  }\n";
        $html .= "</script>\n";

        $html .= "<div id=\"Listado\">\n"; //DIV PARA EL LISTADO DE GRUPOS CREADOS
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend class=\"normal_10AN\">CLASIFICACION DE PRODUCTOS</legend>\n";

        $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">CODIGO</td>\n";
        $html .= "      <td width=\"40%\">NOMBRE DEL GRUPO</td>\n";
        $html .= "      <td width=\"10%\">MEDICAMENTOS</td>\n";
        $html .= "      <td width=\"10%\">ASIGNAR CLASES</td>\n";
        //$html .= "      <td width=\"10%\">CLASES/SUBCLASES</td>\n";
        $html .= "      <td width=\"10%\">MOD</td>\n";
        $html .= "      <td width=\"10%\">SUPR</td>\n";

        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach ($Grupos as $key => $grp)
        {
            ($est == "modulo_list_claro") ? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC") ? $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"" . $est . "\" onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            $html .= "      <td >" . $grp['grupo_id'] . "</td><td>" . $grp['descripcion'] . " </td>\n";
            $html .= "      </td>";
            if ($grp['sw_medicamento'] == 1)
                $html .= "<td align=\"center\"><img title=\"GRUPO PARA MEDICAMENTOS\" src=\"" . GetThemePath() . "/images/si.png\" border=\"0\"></td>\n";
            else
                $html .= "<td align=\"center\"><img title=\"GRUPO DIFERENTE A MEDICAMENTOS\" src=\"" . GetThemePath() . "/images/no.png\" border=\"0\"></td>\n";

            $html .= "      <td align=\"center\">\n";
            $html .= "        <a href=\"#\" onclick=\"xajax_AsignarClases('" . $grp['grupo_id'] . "','" . $grp['descripcion'] . "','" . $grp['sw_medicamento'] . "','1')\">\n";
            $html .= "          <img title=\"Asignar Clases\" src=\"" . GetThemePath() . "/images/asignacion_citas.png\" border=\"0\">\n";
            // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";


            /*
              $html .= "      <td align=\"center\">\n";
              $html .= "        <a href=\"#\" onclick=\"xajax_ClasesSubclases('".$grp['grupo_id']."','".$grp['descripcion']."','".$grp['sw_medicamento']."','1')\">\n";
              $html .= "          <img title=\"Ver Clases/SubClases\" src=\"".GetThemePath()."/images/asignacion_citas.png\" border=\"0\">\n";
              // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
              $html .= "        </a>\n";
              $html .= "      </td>\n"; */


            $html .= "      <td align=\"center\">\n";
            $html .= "        <a href=\"#\" onclick=\"xajax_ModificarGrupo('" . $grp['grupo_id'] . "')\">\n";
            $html .= "          <img title=\"MODIFICAR\" src=\"" . GetThemePath() . "/images/modificar.png\" border=\"0\">\n";
            // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";

            $html .= "      <td align=\"center\">\n";
            $html .= "        <a href=\"#\" onclick=\"ConfirmaBorrar('inv_grupos_inventarios','" . $grp['grupo_id'] . "','grupo_id','" . $grp['descripcion'] . "')\">\n";
            $html .= "          <img title=\"BORRAR\" src=\"" . GetThemePath() . "/images/delete.gif\" border=\"0\">\n";
            // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";
        }



        $html .= "    </table>\n";
        $html .= "</fieldset><br>\n";
        $html .= "</div>"; //CIERRA DIV DE LISTADO DE GRUPOS
        $html .= "<form name=\"forma\" action=\"" . $accion . "\" method=\"post\">\n";
        $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "    <tr>\n";
        $html .= "      <td align=\"center\"><br>\n";
        $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";

        $html .= "                   <div id='error_ter' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";

        $html .= $this->CrearVentana(600, "CLASIFICACION DE PRODUCTOS");
        $html .= ThemeCerrarTabla();



        return($html);
    }

    // CREAR LA CAPITA
    function CrearVentana($tmn, $Titulo)
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
        //Mostrar Span
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
        $html .= "    xResizeTo(ele," . $tmn . ", 'auto');\n";
        $html .= "    ele = xGetElementById(contenedor);\n";
        $html .= "    xResizeTo(ele," . $tmn . ", 'auto');\n";
        $html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
        $html .= "    ele = xGetElementById(titulo);\n";
        $html .= "    xResizeTo(ele," . ($tmn - 20) . ", 20);\n";
        $html .= "    xMoveTo(ele, 0, 0);\n";
        $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $html .= "    ele = xGetElementById('cerrar');\n";
        $html .= "    xResizeTo(ele,20, 20);\n";
        $html .= "    xMoveTo(ele," . ($tmn - 20) . ", 0);\n";
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


        $html.= "function Cerrar(Elemento)\n";
        $html.= "{\n";
        $html.= "    capita = xGetElementById(Elemento);\n";
        $html.= "    capita.style.display = \"none\";\n";
        $html.= "}\n";

        $html .= "</script>\n";
        $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
        $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">" . $Titulo . "</div>\n";
        $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
        $html .= "  <div id='Contenido' class='d2Content'>\n";
        //En ese espacio se visualiza la informacion extraida de la base de datos.
        $html .= "  </div>\n";
        $html .= "</div>\n";

        $html .= "</script>\n";
        $html .= "<div id='Contenedor2' class='d2Container' style=\"display:none;z-index:4\">\n";
        $html .= "  <div id='titulo2' class='draggable' style=\" text-transform: uppercase;text-align:center;\">" . $Titulo . "</div>\n";
        $html .= "  <div id='cerrar2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
        $html .= "  <div id='Contenido2' class='d2Content'>\n";
        //En ese espacio se visualiza la informacion extraida de la base de datos.
        $html .= "  </div>\n";
        $html .= "</div>\n";

        return $html;
    }

    /*
     * Funcion donde se crea la forma para  consultar las auditorias de los productos
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */
    
    function FormaConsultarAuditoriasProductos($action, $request, $datosProducto, $datosAuditoriaProducto, $datosMedicamento, $datosAuditoriaMedicamento, $datosAuditoriaEstadoProducto)
    {
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html .= $ctl->LimpiarCampos();

        $html .= ThemeAbrirTabla('CONSULTAR AUDITORIA PRODUCTOS');
        $html .= "<form name=\"FormaConsultar2\" id=\"FormaConsultar2\" action=\"" . $action['buscador'] . "\"  method=\"post\" >\n";
        $html .= "<table  width=\"45%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
        $html .= "  <tr colspan=\"5\" class=\"formulacion_table_list\">\n";
        $html .= "      <td >CÓDIGO PRODUCTO:</td>\n";
        $html .= "      <td colspan=\"4\" align=\"left\" class=\"modulo_list_claro\"><input class=\"input-text\" type=\"text\" name=\"buscador[codigo_producto]\" value=\"" . $request['codigo_producto'] . "\" size=\"30%\" maxlength=\"30\">\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<table   width=\"30%\" align=\"center\" border=\"0\"   >";
        $html .= "  <tr>\n";
        $html .= "      <td  colspan=\"10\"  align='center'>\n";
        $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"  >\n";
        $html .= "      </td>\n";
        $html .= "      <td  colspan=\"10\" align='center' >\n";
        $html .= "          <input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.FormaConsultar2)\" value=\"Limpiar Campos\">\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table><br>\n";

        if (!empty($datosProducto))
        {
            $html .= "<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
            $html .= "  <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"10%\" colspan=\"31\">DATOS PRODUCTO</td>\n";
            $html .= "  </tr>\n";
            $html .= "  <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"5%\">GRUPO</td>\n";
            $html .= "      <td width=\"5%\">CLASE</td>\n";
            $html .= "      <td width=\"5%\">SUBCLASE</td>\n";
            $html .= "      <td width=\"5%\">CONSECUTIVO</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO PRODUCTO</td>\n";
            $html .= "      <td width=\"5%\">DESCRIPCIÓN</td>\n";
            $html .= "      <td width=\"5%\">DESCRIPCIÓN ABREVIADA</td>\n";
            $html .= "      <td width=\"5%\">DENOMINACIÓN COMUN INTERNACIONAL (DCI)</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO CUM</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO ALTERNO</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO BARRAS</td>\n";
            $html .= "      <td width=\"5%\">FABRICANTE</td>\n";
            $html .= "      <td width=\"5%\">PAÍS DE FABRICACIÓN</td>\n";
            $html .= "      <td width=\"5%\">PRODUCTO POS</td>\n";
            $html .= "      <td width=\"5%\">ACUERDO 029 POS</td>\n";
            $html .= "      <td width=\"5%\">RIPS NO POS</td>\n";
            $html .= "      <td width=\"5%\">UNIDAD MEDIDA X CANTIDAD</td>\n";
            $html .= "      <td width=\"5%\">PRESENTACIÓN COMERCIAL</td>\n";
            $html .= "      <td width=\"5%\">PERFIL TERAPEUTICO</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO MENSAJE</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO MIN. DEFENSA</td>\n";
            $html .= "      <td width=\"5%\">REGISTRO INVIMA</td>\n";
            $html .= "      <td width=\"5%\">FECHA VENCIMIENTO R. INVIMA</td>\n";
            $html .= "      <td width=\"5%\">TITULAR REG. INVIMA</td>\n";
            $html .= "      <td width=\"5%\">% IVA</td>\n";
            $html .= "      <td width=\"5%\">GENÉRICO</td>\n";
            $html .= "      <td width=\"5%\">VENTA DIRECTA</td>\n";
            $html .= "      <td width=\"5%\">TIPO DE PRODUCTO</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO ADMINISTRATIVO PRESENTACIÓN</td>\n";
            $html .= "      <td width=\"5%\">TRATAMIENTOS ESPECIALES</td>\n";
            $html .= "      <td width=\"5%\">REGULADO</td>\n";
            $html .= "  </tr>\n";

            $est = "modulo_list_claro";

            foreach ($datosProducto as $dtl)
            {
                $html .= "  <tr align=\"center\" class=\"" . $est . "\" >\n";
                $html .= "      <td>" . $dtl['grupo'] . "</td>\n";
                $html .= "      <td>" . $dtl['clase'] . "   " . $dtl['tercero_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['subclase'] . "</td>\n";
                $html .= "      <td>" . $dtl['producto_id'] . "</td>\n";
                $html .= "      <td>" . $request['codigo_producto'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion_abreviada'] . "</td>\n";
                $html .= "      <td>" . $dtl['dci_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['codigo_cum'] . "</td>\n";
                $html .= "      <td>" . $dtl['codigo_alterno'] . "</td>\n";
                $html .= "      <td>" . $dtl['codigo_barras'] . "</td>\n";
                $html .= "      <td>" . $dtl['fabricante'] . "</td>\n";
                //$html .= "      <td>" . $dtl['tipo_pais_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['pais'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_pos'] . "</td>\n";
                $html .= "      <td>" . $dtl['cod_acuerdo228_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['rips_no_pos'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion_unidad'] . "-" . $dtl['unidad_id'] . " POR " . $dtl['cantidad'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion_presentacion_comercial'] . " POR " . $dtl['cantidad_p'] . "</td>\n";
                $html .= "      <td>" . $dtl['anatofarmacologico'] . "-" . $dtl['cod_anatofarmacologico'] . "</td>\n";
                //anatofarmacologico
                $html .= "      <td>" . $dtl['mensaje_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['codigo_mindefensa'] . "</td>\n";
                $html .= "      <td>" . $dtl['codigo_invima'] . "</td>\n";
                $html .= "      <td>" . $dtl['vencimiento_codigo_invima'] . "</td>\n";
                $html .= "      <td>" . $dtl['titular'] . "</td>\n";
                $html .= "      <td>" . $dtl['porc_iva'] . "%</td>\n";
                $html .= "      <td>" . $dtl['sw_generico'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_venta_directa'] . "</td>\n";
                //$html .= "      <td>" . $dtl['tipo_producto_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion_tipo_producto'] . "</td>\n";
                $html .= "      <td>" . $dtl['cod_adm_presenta'] . "</td>\n";
                //$html .= "      <td>" . $dtl['tratamiento_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion_tratamiento_producto'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_regulado'] . "</td>\n";
                $html .= "  </tr>\n";
            }
            $html .= "</table><br>\n";
        } else {
            if ($request)
                $html .= "<br><center class=\"label_error\">NO EXISTE UN PRODUCTO CON EL CÓDIGO DIGITADO</center><br>\n";
        }

        if (!empty($datosAuditoriaProducto))
        {
            $html .= "<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
            $html .= "  <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"10%\" colspan=\"34\">AUDITORIA PRODUCTO</td>\n";
            $html .= "  </tr>\n";
            $html .= "      <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"5%\">GRUPO</td>\n";
            $html .= "      <td width=\"5%\">CLASE</td>\n";
            $html .= "      <td width=\"5%\">SUBCLASE</td>\n";
            $html .= "      <td width=\"5%\">CONSECUTIVO</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO PRODUCTO</td>\n";
            $html .= "      <td width=\"5%\">DESCRIPCIÓN</td>\n";
            $html .= "      <td width=\"5%\">DESCRIPCIÓN ABREVIADA</td>\n";
            $html .= "      <td width=\"5%\">DENOMINACIÓN COMUN INTERNACIONAL (DCI)</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO CUM</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO ALTERNO</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO BARRAS</td>\n";
            $html .= "      <td width=\"5%\">FABRICANTE</td>\n";
            $html .= "      <td width=\"5%\">PAÍS DE FABRICACIÓN</td>\n";
            $html .= "      <td width=\"5%\">PRODUCTO POS</td>\n";
            $html .= "      <td width=\"5%\">ACUERDO 029 POS</td>\n";
            $html .= "      <td width=\"5%\">RIPS NO POS</td>\n";
            $html .= "      <td width=\"5%\">UNIDAD MEDIDA X CANTIDAD</td>\n";
            $html .= "      <td width=\"5%\">PRESENTACIÓN COMERCIAL</td>\n";
            $html .= "      <td width=\"5%\">PERFIL TERAPEUTICO</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO MENSAJE</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO MIN. DEFENSA</td>\n";
            $html .= "      <td width=\"5%\">REGISTRO INVIMA</td>\n";
            $html .= "      <td width=\"5%\">FECHA VENCIMIENTO R. INVIMA</td>\n";
            $html .= "      <td width=\"5%\">TITULAR REG. INVIMA</td>\n";
            $html .= "      <td width=\"5%\">% IVA</td>\n";
            $html .= "      <td width=\"5%\">GENÉRICO</td>\n";
            $html .= "      <td width=\"5%\">VENTA DIRECTA</td>\n";
            $html .= "      <td width=\"5%\">TIPO DE PRODUCTO</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO ADMINISTRATIVO PRESENTACIÓN</td>\n";
            $html .= "      <td width=\"5%\">TRATAMIENTOS ESPECIALES</td>\n";
            $html .= "      <td width=\"5%\">REGULADO</td>\n";
            $html .= "      <td width=\"5%\">EDITOR</td>\n";
            $html .= "      <td width=\"5%\">FECHA MODIFICACIÓN</td>\n";
            $html .= "      <td width=\"5%\">VERSIÓN</td>\n";
            $html .= "  </tr>\n";

            $est = "modulo_list_claro";

            foreach ($datosAuditoriaProducto as $dtl)
            {
                $html .= "  <tr align=\"center\" class=\"" . $est . "\" >\n";
                $html .= "      <td>" . $dtl['grupo'] . "</td>\n";
                $html .= "      <td>" . $dtl['clase'] . "   " . $dtl['tercero_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['subclase'] . "</td>\n";
                $html .= "      <td>" . $dtl['producto_id'] . "</td>\n";
                $html .= "      <td>" . $request['codigo_producto'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion_abreviada'] . "</td>\n";
                $html .= "      <td>" . $dtl['dci_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['codigo_cum'] . "</td>\n";
                $html .= "      <td>" . $dtl['codigo_alterno'] . "</td>\n";
                $html .= "      <td>" . $dtl['codigo_barras'] . "</td>\n";
                $html .= "      <td>" . $dtl['fabricante'] . "</td>\n";
                $html .= "      <td>" . $dtl['pais'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_pos'] . "</td>\n";
                $html .= "      <td>" . $dtl['cod_acuerdo228_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['rips_no_pos'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion_unidad'] . "-" . $dtl['unidad_id'] . " POR " . $dtl['cantidad'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion_presentacion_comercial'] . " POR " . $dtl['cantidad_p'] . "</td>\n";
                $html .= "      <td>" . $dtl['anatofarmacologico'] . "-" . $dtl['cod_anatofarmacologico'] . "</td>\n";                
                $html .= "      <td>" . $dtl['mensaje_id'] . "</td>\n";
                $html .= "      <td>" . $dtl['codigo_mindefensa'] . "</td>\n";
                $html .= "      <td>" . $dtl['codigo_invima'] . "</td>\n";
                $html .= "      <td>" . $dtl['vencimiento_codigo_invima'] . "</td>\n";
                $html .= "      <td>" . $dtl['titular'] . "</td>\n";
                $html .= "      <td>" . $dtl['porc_iva'] . "%</td>\n";
                $html .= "      <td>" . $dtl['sw_generico'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_venta_directa'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion_tipo_producto'] . "</td>\n";
                $html .= "      <td>" . $dtl['cod_adm_presenta'] . "</td>\n";
                $html .= "      <td>" . $dtl['descripcion_tratamiento_producto'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_regulado'] . "</td>\n";
                $html .= "      <td>" . $dtl['usuario_modificador'] . "</td>\n";
                $html .= "      <td>" . $dtl['fecha_modificacion'] . "</td>\n";
                $html .= "      <td>" . $dtl['version'] . "</td>\n";                
                $html .= "  </tr>\n";
            }
            $html .= "</table><br>\n";
        } else {
            if ($request)
                $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS DE AUDITORIA DEL PRODUCTO</center><br>\n";
        }
        
        if (!empty($datosMedicamento))
        {
            $html .= "<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
            $html .= "  <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"10%\" colspan=\"12\">DATOS PRODUCTO->MEDICAMENTO</td>\n";
            $html .= "  </tr>\n";
            $html .= "      <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"5%\">PRINCIPIO ACTIVO</td>\n";
            $html .= "      <td width=\"5%\">CONCENTRACIÓN (mg)</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO CONCENTRACIÓN</td>\n";
            $html .= "      <td width=\"5%\">MANEJO DE LUZ</td>\n";
            $html .= "      <td width=\"5%\">LÍQUIDOS ELECTROLITOS</td>\n";
            $html .= "      <td width=\"5%\">USO CONTROLADO</td>\n";
            $html .= "      <td width=\"5%\">*S1* ANTIBIÓTICO</td>\n";
            $html .= "      <td width=\"5%\">REFRIGERADO</td>\n";
            $html .= "      <td width=\"5%\">ALIMENTO PARENTAL</td>\n";
            $html .= "      <td width=\"5%\">ALIMENTO ENTERAL</td>\n";
            $html .= "      <td width=\"5%\">DÍAS PREVIOS AL VENCIMIENTO</td>\n";
            $html .= "      <td width=\"5%\">FARMACOVIGILANCIA</td>\n";
            $html .= "  </tr>\n";

            $est = "modulo_list_claro";

            foreach ($datosMedicamento as $dtl)
            {
                $html .= "  <tr align=\"center\" class=\"" . $est . "\" >\n";
                $html .= "      <td>" . $dtl['cod_principio_activo'] . " -  " . $dtl['descripcion_subclase'] . "</td>\n";
                $html .= "      <td>" . $dtl['concentracion_forma_farmacologica'] . "</td>\n";
                $html .= "      <td>" . $dtl['cod_concentracion'] . "</td>\n";                
                $html .= "      <td>" . $dtl['sw_fotosensible'] . "</td>\n";           
                $html .= "      <td>" . $dtl['sw_liquidos_electrolitos'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_uso_controlado'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_antibiotico'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_refrigerado'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_alimento_parenteral'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_alimento_enteral'] . "</td>\n";
                $html .= "      <td>" . $dtl['dias_previos_vencimiento'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_farmacovigilancia'] . " - " .  $dtl['descripcion_alerta']  . "</td>\n";
                $html .= "  </tr>\n";                
            }
            $html .= "</table><br>\n";
        } else {
            if ($request)
                $html .= "<br><center class=\"label_error\">NO EXISTE UN MEDICAMENTO CON EL CÓDIGO DIGITADO</center><br>\n";
        }
        
        if (!empty($datosAuditoriaMedicamento))
        {
            $html .= "<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
            $html .= "  <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"10%\" colspan=\"15\">AUDITORIA PRODUCTO->MEDICAMENTO</td>\n";
            $html .= "  </tr>\n";
            $html .= "      <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"5%\">PRINCIPIO ACTIVO</td>\n";
            $html .= "      <td width=\"5%\">CONCENTRACIÓN (mg)</td>\n";
            $html .= "      <td width=\"5%\">CÓDIGO CONCENTRACIÓN</td>\n";
            $html .= "      <td width=\"5%\">MANEJO DE LUZ</td>\n";
            $html .= "      <td width=\"5%\">LÍQUIDOS ELECTROLITOS</td>\n";
            $html .= "      <td width=\"5%\">USO CONTROLADO</td>\n";
            $html .= "      <td width=\"5%\">*S1* ANTIBIÓTICO</td>\n";
            $html .= "      <td width=\"5%\">REFRIGERADO</td>\n";
            $html .= "      <td width=\"5%\">ALIMENTO PARENTAL</td>\n";
            $html .= "      <td width=\"5%\">ALIMENTO ENTERAL</td>\n";
            $html .= "      <td width=\"5%\">DÍAS PREVIOS AL VENCIMIENTO</td>\n";
            $html .= "      <td width=\"5%\">FARMACOVIGILANCIA</td>\n";
            $html .= "      <td width=\"5%\">EDITOR</td>\n";
            $html .= "      <td width=\"5%\">FECHA MODIFICACIÓN</td>\n";
            $html .= "      <td width=\"5%\">VERSIÓN</td>\n";
            $html .= "  </tr>\n";

            $est = "modulo_list_claro";

            foreach ($datosAuditoriaMedicamento as $dtl)
            {
                $html .= "  <tr align=\"center\" class=\"" . $est . "\" >\n";
                $html .= "      <td>" . $dtl['cod_principio_activo'] . " -  " . $dtl['descripcion_subclase'] . "</td>\n";
                $html .= "      <td>" . $dtl['concentracion_forma_farmacologica'] . "</td>\n";
                $html .= "      <td>" . $dtl['cod_concentracion'] . "</td>\n";                
                $html .= "      <td>" . $dtl['sw_fotosensible'] . "</td>\n";           
                $html .= "      <td>" . $dtl['sw_liquidos_electrolitos'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_uso_controlado'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_antibiotico'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_refrigerado'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_alimento_parenteral'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_alimento_enteral'] . "</td>\n";
                $html .= "      <td>" . $dtl['dias_previos_vencimiento'] . "</td>\n";
                $html .= "      <td>" . $dtl['sw_farmacovigilancia'] . " - " .  $dtl['descripcion_alerta']  . "</td>\n";
                $html .= "      <td>" . $dtl['nombre'] . "</td>\n";
                $html .= "      <td>" . $dtl['fecha_modificacion'] . "</td>\n";
                $html .= "      <td>" . $dtl['version'] . "</td>\n";                
                $html .= "  </tr>\n";
            }
            $html .= "</table><br>\n";
        } else {
            if ($request)
                $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS DE AUDITORIA DEL PRODUCTO->MEDICAMENTO</center><br>\n";
        }
        
        if (!empty($datosAuditoriaEstadoProducto))
        {
            $html .= "<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
            $html .= "  <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"10%\" colspan=\"3\">AUDITORIA ESTADO PRODUCTO</td>\n";
            $html .= "  </tr>\n";
            $html .= "      <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"5%\">NUEVO ESTADO</td>\n";
            $html .= "      <td width=\"5%\">EDITOR</td>\n";
            $html .= "      <td width=\"5%\">FECHA MODIFICACIÓN</td>\n";
            $html .= "  </tr>\n";

            $est = "modulo_list_claro";

            foreach ($datosAuditoriaEstadoProducto as $dtl)
            {
                $html .= "  <tr align=\"center\" class=\"" . $est . "\" >\n";
                $html .= "      <td>" . $dtl['nuevo_estado'] . "</td>\n";
                $html .= "      <td>" . $dtl['nombre'] . "</td>\n";
                $html .= "      <td>" . $dtl['fecha_modificacion'] . "</td>\n";                
                $html .= "  </tr>\n";
            }
            $html .= "</table><br>\n";
        } else {
            if ($request)
                $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS DE AUDITORIA DE ESTADO DEL PRODUCTO</center><br>\n";
        }        
        
        $html .= " <br>";
        
        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\"  class=\"label_error\">\n";
        $html .= "        Volver\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= $this->CrearVentana(820, "ORDENES DE COMPRA");
        $html .= ThemeCerrarTabla();

        return $html;
    }

}

?>