 <?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: RotacionFarmaciaHTML.class.php,v 1.0 
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja Torres
 */
IncludeClass("ClaseHTML");
IncludeClass("ClaseUtil");

class RotacionFarmaciaHTML {

    /**
     * Constructor de la clase
     */
    function RotacionFarmaciaHTML() {
        
    }

    /*
     * Funcion donde se crea la forma para el menu la Rotacion Productos
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina

     */

    function FormaMenu($action) {
        $html = ThemeAbrirTabla('MENU ROTACION DE PRODUCTOS POR FARMACIA');
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->RollOverFilas();
        $html .= "<center>\n";
        $html .= "<table width=\"35%\" class=\"modulo_table_list\" border=\"1\" align=\"center\" >\n";
        $html .= "  <tr class=\"formulacion_table_list\" >\n";
        $html .= "     <td align=\"center\">MENU\n";
        $html .= "     </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"normal_10AN\" onmouseout=mOut(this,'{$back}'); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
        $html .= "      <td  align=\"center\">\n";
        $html .= "        <a href='{$action['rotacion_farmacias']}'>ROTACION FARMACIAS</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"normal_10AN\" onmouseout=mOut(this,\"" . $back . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
        $html .= "      <td  align=\"center\">\n";
        $html .= "        <a href='{$action['rotacion_general_farmacias']}'>ROTACION GENERAL FARMACIAS</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "	</table>\n";
        $html .= "</center>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href='{$action['volver']}'>VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /* Funcion donde se crea la forma para Buscar Las Farmacias para realizar las solicitudes
      @param array $action vector que contiene los link de la aplicacion
      @return string $html retorna la cadena con el codigo html de la pagina

     */

    function FormaBuscarFarmaciasSolicitudes($action, $Tipo, $request, $datos, $conteo, $pagina, $dat) {
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->RollOverFilas();
        $html .="  <script>\n";
        $html .= "	  function LimpiarCampos(frm)\n";
        $html .= "	  {\n";
        $html .= "		  for(i=0; i<frm.length; i++)\n";
        $html .= "		  {\n";
        $html .= "			  switch(frm[i].type)\n";
        $html .= "			  {\n";
        $html .= "				  case 'text': frm[i].value = ''; break;\n";
        $html .= "				  case 'select-one': frm[i].selectedIndex = 0; break;\n";
        $html .= "			  }\n";
        $html .= "		  }\n";
        $html .= "	  }\n";
        $html .="  </script>\n";
        $html .= ThemeAbrirTabla('BUSCAR  - FARMACIA');
        $html .= "		<form name=\"formabuscarE\" action=\"" . $action['buscador'] . "\" method=\"post\">";
        $html .= "			<table   width=\"55%\"  class=\"modulo_table_list\" align=\"center\" border=\"0\"  >";
        /* $html .= "   <tr> \n";
          $html .= "			<td align=\"center\"  class=\"formulacion_table_list\"><b> TIPO DOCUMENTO :</B></td>\n";
          $html .= "			<td  class=\"modulo_list_claro\" colspan=\"4\">\n";
          $html .= "					<select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
          $html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
          $csk = "";
          foreach($Tipo as $indice => $valor)
          {
          if($valor['tipo_id_tercero']==$request['tipo_id_tercero'])
          $sel = "selected";
          else   $sel = "";
          $html .= "  <option value=\"".$valor['tipo_id_tercero']."\" ".$sel.">".$valor['descripcion']."</option>\n";
          }
          $html .= "                </select>\n";
          $html .= "						  </td>\n";
          $html .= "	 </tr>\n";
          $html .= "		<tr>\n";
          $html .= "			<td  align=\"center\"  class=\"formulacion_table_list\"><b>DOCUMENTO:</B></td>\n";
          $html .= "	    <td class=\"modulo_list_claro\" colspan=\"4\">\n";
          $html .= "     <input type=\"text\" class=\"input-text\" name=\"buscador[id]\" size=\"35\"  maxlength=\"32\" value=".$request['id']."></td>\n";
          $html .= "		</tr>\n"; */
        //print_r($_REQUEST);
        $html .= "		<tr>\n";
        $html .= "			<td  align=\"center\"  class=\"formulacion_table_list\"><b>CODIGO:</B></td>\n";
        $html .= "	    <td class=\"modulo_list_claro\" colspan=\"4\">\n";
        $html .= "     <input type=\"hidden\" name=\"buscador[tipo_id_tercero]\" value=\"-1\">";
        $html .= "     <input type=\"hidden\" name=\"buscador[id]\" value=\"\">\n";
        $html .= "     <input type=\"text\" class=\"input-text\" name=\"buscador[codigo]\" size=\"35\"  maxlength=\"32\" value=" . $request['codigo'] . "></td>\n";
        $html .= "		</tr>\n";
        $html .= "		<tr>\n";
        $html .= "			<td align=\"center\"  class=\"formulacion_table_list\"><b> RAZON SOCIAL:</B></td>\n";
        $html .= "			<td  colspan=\"4\" class=\"modulo_list_claro\" ><input type=\"text\" class=\"input-text\"  size=\"35\" name=\"buscador[razon_social]\" maxlength=\"32\" value=" . $request['razon_social'] . "></td>\n";
        $html .= "		</tr>\n";
        $html .= "</table><br>\n";
        $html .= "			<table   width=\"25%\"  class=\"normal_10AN\" align=\"center\" border=\"0\"  >";
        $html .= "		<tr>\n";
        $html .= "	   	<td align='center'>\n";
        $html .= "			<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
        $html .= "			</td>\n";
        $html .= "			<td align='center' colspan=\"1\">\n";
        $html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.formabuscarE)\" value=\"Limpiar Campos\">\n";
        $html .= "	  	</td>\n";

        $html .= "			</tr>\n";
        $html .= "</table><br>\n";
        $html .= "				    </form>\n";
        $html .= "		<form name=\"Datos_emp\" method=\"post\">";
        if (!empty($datos)) {
            $pghtml = AutoCarga::factory('ClaseHTML');
            $html .= "<fieldset class=\"fieldset\"  >\n";
            $html .= "  <table width=\"100%\"  border=\"0\" class=\"modulo_table_list\"   align=\"center\">";
            $html .= "	  <tr align=\"left\" class=\"formulacion_table_list\">\n";
            $html .= "      <td align=\"left\"  class=\"formulacion_table_list\"  width=\"15%\">IDENTIFICACION.</td>\n";
            $html .= "      <td align=\"left\" class=\"formulacion_table_list\"  width=\"25%\">RAZON SOCIAL.</td>\n";
            $html .= "      <td align=\"left\" class=\"formulacion_table_list\"  width=\"15%\">REPRESENTANTE.</td>\n";
            $html .= "      <td width=\"2%\" class=\"formulacion_table_list\" >OP.</td>\n";
            $html .= "  </tr>\n";
            $est = "modulo_list_oscuro";
            $back = "#DDDDDD";
            foreach ($datos as $key => $dtl) {
                $html .= "  <tr  class=\"normal_10AN\" " . $est . " onmouseout=mOut(this,\"" . $back . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
                $html .= "      <td  class=\"modulo_list_oscuro\" align=\"left\">" . $dtl['tipo_id_tercero'] . " " . $dtl['id'] . "</td>\n";
                $html .= "      <td class=\"modulo_list_oscuro\"  align=\"center\">" . $dtl['razon_social'] . "</td>\n";
                $html .= "      <td  class=\"modulo_list_oscuro\"  align=\"left\">" . $dtl['representante_legal'] . "</td>\n";
                $html .= "      <td class=\"modulo_list_oscuro\" align=\"center\">\n";
                $html .= "      <a href=\"" . $action['rotacionFarma'] . URLRequest(array("Farmacia_id" => $dtl['empresa_id'])) . "\">\n";
                $html .= "      <img src=\"" . GetThemePath() . "/images/siguiente.png\" border=\"0\" title=\"rotacion\"> </a>\n";
                $html .= "      </td>\n";
                $html .= "  </tr>\n";
            }
            $html .= "	</table><br>\n";
            $html .= "</fieldset><br>\n";
            $html .= $pghtml->ObtenerPaginado($conteo, $pagina, $action['paginador']);
            $html .= "	<br>\n";
        } else {
            if ($request)
                $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
        }
        $html .= "				    </form>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /* Funcion donde se crea la forma para Buscar la informacion adicional de la farmacia
      @param array $action vector que contiene los link de la aplicacion
      @return string $html retorna la cadena con el codigo html de la pagina

     */

    function FormaInformacionFarmacia($action, $datos, $dat) {

        //var_dump($_REQUEST);
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->RollOverFilas();
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html .= ThemeAbrirTabla('DATOS DE LA FARMACIA Y TIPO DE ROTACION');
        $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";

        $html .="  <script>\n";
        $html .= "	  function ValidarDatos(frms)\n";
        $html .= "	  {\n";
        $html .= "	//	if(frms.bodega.selectedIndex==0){";
        $html .= "      //document.getElementById('error').innerHTML = 'DEBE  SELECCIONAR UNA BODEGA DE LA FARMACIA';\n";
        $html .= "      //return;\n";
        $html .= "	  //}\n";
        $html .= "	  if(frms.destino.selectedIndex==0){\n";
        $html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR LA EMPRESA DESTINO PARA LAS SOLICITUDES';\n";
        $html .= "      return;\n";
        $html .= "	  }\n";
        $html .= "	  if(frms.bodega_destino.selectedIndex==0){\n";
        $html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR LA DESTINO DESTINO PARA LAS SOLICITUDES';\n";
        $html .= "      return;\n";
        $html .= "	  }\n";
        $html .= "    if(!IsDate(frms.fecha_inicio.value))\n";
        $html .= "    {\n";
        $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DE INICIO ';\n";
        $html .= "      return;\n";
        $html .= "    } \n";
        $html .= "    if(!IsDate(frms.fecha_final.value))\n";
        $html .= "    {\n";
        $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA FINAL';\n";
        $html .= "      return;\n";
        $html .= "    } \n";
        $html .= "	    f = frms.fecha_inicio.value.split('-')\n";
        $html .= "	    f1 = new Date(f[2]+'/'+ f[1]+'/'+ f[0]); \n";
        $html .= "	    f = frms.fecha_final.value.split('-')\n";
        $html .= "	    f2 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
        $html .= "	    if(f1 >= f2 )\n";
        $html .= "	    {\n";
        $html .= "        document.getElementById('error').innerHTML = 'LA FECHA INICIAL NO PUEDE SER MAYOR O IGUAL A LA FECHA FINAL ';\n";
        $html .= "        return;\n";
        $html .= "      } \n";
        $html .= "    frms.submit();\n";
        $html .= "	  }\n";
        $html .="  </script>\n";
        $html .= "		<form name=\"formabodega\" id=\"formabodega\"  action=\"" . $action['continuar'] . "\" method=\"post\">";
        $html .= "			<table   width=\"55%\"  class=\"modulo_table_list\" align=\"center\" border=\"0\"  >";
        /* $html .= "   <tr> \n";
          $html .= "			<td align=\"center\"  class=\"formulacion_table_list\"><b>BODEGAS :</B></td>\n";
          $html .= "			<td  class=\"modulo_list_claro\" colspan=\"4\">\n";
          $html .= "					<select name=\"bodega\" class=\"select\">\n";
          $html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
          $csk = "";
          foreach ($datos as $indice => $valor)
          {
          if ($valor['bodega'] == $request['bodega'])
          $sel = "selected";
          else
          $sel = "";
          $html .= "  <option value=\"" . $valor['bodega'] . "/" . $valor['centro_utilidad'] . "\" " . $sel . ">" . $valor['descripcion'] . " (" . $valor['centro'] . ")</option>\n";
          }
          $html .= "                </select>\n";
          $html .= "						  </td>\n";
          $html .= "	 </tr>\n"; */

        $html .= "<tr>\n";
        $html .= "  <td align='center'  class='formulacion_table_list'><b>BODEGAS :</b></td>\n";
        $html .= "  <td align='left'  class='modulo_list_claro'>
                        <input type='text' class='input-text' name='farmacia_id'   id='farmacia_id' maxlength='2' onblur=\"xajax_buscar_famacia('{$_REQUEST['Farmacia_id']}', document.getElementById('farmacia_id').value);\">                            
                        <span id='nombre_farmacia'></span>                         
                    </td>
                    \n";
        $html .= "</tr>\n";

        $html .= "   <tr> \n";
        $html .= "			<td align=\"center\" class=\"formulacion_table_list\"><b>EMPRESA ORIGEN:</B></td>\n";
        $html .= "			<td  class=\"modulo_list_claro\" colspan=\"4\">\n";
        $html .= "					<select name=\"destino\" class=\"select\" onchange=\"xajax_MostrarBodegas(xajax.getFormValues('formabodega'))\">\n";
        $html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
        $csk = "";
        foreach ($dat as $indice => $val) {
            if ($val['empresa_id'] == $request['empresa_id'])
                $sel = "selected";
            else
                $sel = "";
            $html .= "  <option value=\"" . $val['empresa_id'] . "\" " . $sel . ">" . $val['razon_social'] . "</option>\n";
        }
        $html .= "                </select>\n";
        $html .= "						  </td>\n";
        $html .= "	 </tr>\n";

        $html .= "  <tr >\n";
        $html .= "		           	<td  align=\"center\" class=\"formulacion_table_list\" >BODEGA ORIGEN:</td>\n";
        $html .= "		            	<td  class=\"modulo_list_claro\" align=\"left\">\n";
        $html .= "					           <select name=\"bodega_destino\" class=\"select\">\n";
        $html .= "                     	<option value ='-1'>--  SELECCIONE --</option>\n";
        $csk = "";
        $html .= "                </select>\n";
        $html .= "						     </td>\n";
        $html .= "		</tr>\n";


        $html .= "</table><br>\n";

        $html .= "<table  width=\"55%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "		<td  width=\"30%\" >FECHA INICIAL:</td>\n";
        $html .= "		<td width=\"15%\" align=\"left\" class=\"modulo_list_claro\" >\n";
        $html .= "		  <input type=\"text\" class=\"input-text\" name=\"fecha_inicio\"   id=\"fecha_inicio\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\"  >\n";
        $html .= "		</td>\n";
        $html .= "    <td  width=\"15%\" class=\"modulo_list_claro\" >\n";
        $html .= "				" . ReturnOpenCalendario('formabodega', 'fecha_inicio', '-') . "\n";
        $html .= "		</td>\n";
        $html .= "  </tr >\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "		<td  width=\"30%\" >FECHA FINAL:</td>\n";
        $html .= "		<td width=\"15%\" align=\"left\" class=\"modulo_list_claro\" >\n";
        $html .= "		  <input type=\"text\" class=\"input-text\" name=\"fecha_final\"   id=\"fecha_final\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\"  >\n";
        $html .= "		</td>\n";
        $html .= "    <td  width=\"15%\" class=\"modulo_list_claro\" >\n";
        $html .= "				" . ReturnOpenCalendario('formabodega', 'fecha_final', '-') . "\n";
        $html .= "		</td>\n";
        $html .= "  </tr >\n";
        $html .= "</table>\n";
        $html .= " <br>";
        /* $html .= "<center>\n";
          $html .= "<fieldset class=\"fieldset\" style=\"width:45%\">\n";
          $html .= "  <legend class=\"normal_10AN\" align=\"center\">TIPOS DE ROTACION</legend>\n";
          $html .= "<table  width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";

          $html .= "  <tr class=\"formulacion_table_list\" >\n";
          $html .= "		<td  width=\"35%\" >ROTACION GENERAL</td>\n";
          $html .= "      <td  class=\"modulo_list_claro\"  width=\"5%\" align=\"center\" > <input type=\"radio\" name=\"check\" id=\"check\" value=\"1\">  ";
          $html .= "		</td>\n";
          $html .= "  </tr >\n";
          $html .= "  <tr class=\"formulacion_table_list\" >\n";
          $html .= "		<td  width=\"35%\" >ROTACION MOLECULA</td>\n";
          $html .= "       <td class=\"modulo_list_claro\"   width=\"5%\" align=\"center\" > <input type=\"radio\" name=\"check\" id=\"check\" value=\"2\">  ";
          $html .= "		 </td>\n";
          $html .= "  </tr >\n";
          $html .= "  <tr class=\"formulacion_table_list\" >\n";
          $html .= "		<td  width=\"35%\" >ROTACION INSUMOS</td>\n";
          $html .= "      <td  class=\"modulo_list_claro\"  width=\"5%\" align=\"center\" > <input type=\"radio\" name=\"check\" id=\"check\" value=\"3\">  ";
          $html .= "		</td>\n";
          $html .= "  </tr >\n";
          $html .= "		</tr>\n";
          $html .= "</table>\n";
          $html .= " <br>";
          $html .= "</fieldset><br>\n";
          $html .= "</center>\n"; */
        $html .= "			<table   width=\"25%\"  class=\"normal_10AN\" align=\"center\" border=\"0\"  >";
        $html .= "		<tr>\n";
        $html .= "	   	<td align='center'>\n";
        $html .= "			<input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"Continuar\" onclick=\"ValidarDatos(document.formabodega);\">\n";
        $html .= "			</td>\n";
        $html .= "				    </form>\n";
        $html .= "			</tr>\n";
        $html .= "</table><br>\n";

        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*
     * Funcion donde se crea una Forma con una Ventana con capas para mostrar informacion
     * en pantalla
     * @param int $tmn Tama�o que tendra la ventana
     * @return string
     */

    function CrearVentana($tmn, $Titulo) {
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
        $html .= "  </div>\n";
        $html .= "</div>\n";
        $html .= "</script>\n";
        $html .= "<div id='Contenedor2' class='d2Container' style=\"display:none;z-index:4\">\n";
        $html .= "  <div id='titulo2' class='draggable' style=\" text-transform: uppercase;text-align:center;\">" . $Titulo . "</div>\n";
        $html .= "  <div id='cerrar2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
        $html .= "  <div id='Contenido2' class='d2Content'>\n";
        $html .= "  </div>\n";
        $html .= "</div>\n";
        return $html;
    }

    /* 	 Funcion que  Contiene la Forma  del Mensaje cuando se realiza la solicitud de gerencia
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaMensajeSolcitud_($action, /* $datos_empresa, */ $datos, $datos_s, $Empresa_D, $datos_dV, $Bodega_D) {

        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html .= $ctl->IsNumeric();
        $html .= $ctl->AcceptNum(false);
        $html .= ThemeAbrirTabla('MENSAJE-DESCRIPCION DE LO SOLICITADO POR ROTACION');


        if (!empty($datos_s)) {


            $html .= "<form name=\"FormaModificarPedidos\" id=\"FormaModificarPedidos\" method=\"post\"   action=\"" . $action['Crear_s'] . "\" >\n";
            $html .= "<center> ";
            $html .= "<fieldset class=\"fieldset\" style=\"width:80%\" >\n";
            $html .= "  <legend class=\"normal_10AN\" align=\"center\">SOLICITUD A BODEGA</legend>\n";

            $html .= "  <table class=\"modulo_table_list\"  align=\"center\" width=\"100%\">\n";
            $html .= "    <tr class=\"formulacion_table_list\">\n";
            $html .= "      <td width=\"10%\" align=\"center\"> OBSERVACI�N\n";
            $html .= "      </td>\n";
            $html .= "    </tr>\n";
            $html .= "    <tr class=\"modulo_table_list_title\">\n";
            $html .= "      <td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "        <textarea onkeypress=\"return max(event)\"  name=\"observar\" rows=\"2\" style=\"width:100%\"></textarea>\n";
            $html .= "      </td>\n";
            $html .= "    </tr>\n";
            $html .= "  </table>\n";

            $html .= "  <table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
            $html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
            $html .= "      <td  width=\"10%\">CODIGO </td>\n";
            $html .= "      <td    width=\"55%\">MEDICAMENTO</td>\n";
            $html .= "      <td    width=\"10%\">CANTIDAD</td>\n";
            $html .= "      <td    width=\"10%\">DISPONIBLE</td>\n";
            $html .= "      <td    width=\"15%\">OBSERVACION</td>\n";
            $html .= "      <td    width=\"5%\">OP</td>\n";
            $html .= "  </tr>\n";
            $contador = 0;
            foreach ($datos_s as $key => $datos_sd) {
                $est = ($est == 'modulo_list_oscuro') ? 'modulo_list_claro' : 'modulo_list_oscuro';
                $bck = ($bck == "#CCCCCC") ? "#DDDDDD" : "#CCCCCC";
                $html .= "  <tr  class=\"" . $est . "\" onmouseout=mOut(this,\"" . $back . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
                $html .= "      <td   align=\"left\">" . $datos_sd['codigo_producto'] . "</td>\n";
                $html .= "      <td  class=\"label\"  align=\"left\">" . $datos_sd['producto'] . "</td>\n";
                $mdl = AutoCarga::factory("RotacionFarmaciaSQL", "classes", "app", "RotacionFarmacia");


                $pendientes = $mdl->BuscarPedidosFarmacia($Empresa_D, $datos_sd['codigo_producto']);

                if ($pendientes['sum'] == ' ') {
                    $disponible = 0;
                } else {

                    $disponible = $pendientes['sum'];
                }
                $exitenciaD = $mdl->Existencia_x_Producto_Bodega($Empresa_D, $datos_sd['codigo_producto'], $Bodega_D);
                if ($exitenciaD['existencia'] == '') {
                    $exitencia = 0;
                } else {

                    $exitencia = $exitenciaD['existencia'];
                }
                $totalD = $exitencia - $disponible;
                if ($totalD <= 0) {
                    $totalD = 0;
                }

                $html .= "      <td lign=\"left\">";
                $html .="       <input  class=\"input-text\"  style=\"width:100%\" type=\"text\" name=\"cantidad" . $contador . "\"  id=\"cantidad" . $contador . "\"    value=\"" . round($datos_sd['cantidad']) . "\"  size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" >";
                $html .="        </td>\n";
                $html .= "		  <input type=\"hidden\" name=\"observacionp" . $contador . "\" id=\"observacionp" . $contador . "\" value=\"" . $datos_sd['observacion'] . "\">";
                $html .= "		  <input type=\"hidden\" name=\"tipo_p" . $contador . "\" id=\"tipo_p" . $contador . "\" value=\"" . $datos_sd['tipo_producto'] . "\">";

                $html .= "      <td align=\"left\"> <input  class=\"input-text\"  style=\"width:100%\" type=\"text\" name=\"txtdisponi" . $contador . "\"  id=\"txtdisponi" . $contador . "\"    value=\"" . round($totalD) . "\"  size=\"5\" readonly=true ></td>";
                $html .= "      <td align=\"left\"> " . $datos_sd['observacion'] . " </td>";

                $html .= "      <td align=\"left\">";

                $html .="       <input type=\"checkbox\" name=\"" . $contador . "\" id=\"" . $contador . "\"   value=\"" . $datos_sd['codigo_producto'] . "\" > </td>  ";
                $html .="       </td>\n";
                $html .= " </tr> ";
                $contador++;
            }

            $html .= "</table>";

            $html .= "	<table   width=\"30%\" align=\"right\" border=\"0\"   >";
            $html .= "  <tr>\n";
            $html .= "	      <td  colspan=\"10\"  align='right'>\n";
            $html .= "		  <input type=\"hidden\" name=\"valor2\" id=\"valor2\" value=\"1\">";
            $html .= "		  <input type=\"hidden\" name=\"cantidad_registrosP\" id=\"cantidad_registrosP\" value=\"" . $contador . "\">";
            $html .= "        <input class=\"input-submit\" type=\"submit\" class=\"input-text\" name=\"btnCrear\"  value=\"CREAR DOCUMENTO DE SOLICITUD\" >\n";
            $html .= " 		 </td>\n";
            $html .= "	</tr>\n";
            $html .= "</table><br>\n";
            $html .= "</fieldset><br>\n";
            $html .= "</center>\n";
        }
        $html .= "</form>\n";


        if (!empty($datos_dV)) {
            $html .= "<form name=\"FormaModificar_D\" id=\"FormaModificar_D\" method=\"post\"   action=\"" . $action['guardar_D'] . "\" >\n";
            $html .= "<center> ";
            $html .= "<fieldset class=\"fieldset\" style=\"width:80%\" >\n";
            $html .= "  <legend class=\"normal_10AN\" align=\"center\">SOLICITUD DE DEVOLUCION</legend>\n";
            $html .= "  <table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
            $html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
            $html .= "      <td  width=\"10%\">CODIGO </td>\n";
            $html .= "      <td    width=\"55%\">MEDICAMENTO</td>\n";
            $html .= "      <td    width=\"10%\">CANTIDAD</td>\n";
            $html .= "      <td    width=\"5%\">OP</td>\n";
            $html .= "  </tr>\n";
            $g = 0;
            foreach ($datos_dV as $key => $datos_V) {
                $est = ($est == 'modulo_list_oscuro') ? 'modulo_list_claro' : 'modulo_list_oscuro';
                $bck = ($bck == "#CCCCCC") ? "#DDDDDD" : "#CCCCCC";
                $html .= "  <tr  class=\"" . $est . "\"  >\n";
                $html .= "      <td   align=\"left\">" . $datos_V['codigo_producto'] . "</td>\n";
                $html .= "      <td  class=\"label\"  align=\"left\">" . $datos_V['producto'] . "</td>\n";
                $html .= "      <td lign=\"left\">";
                $html .="       <input  class=\"input-text\"  style=\"width:100%\" type=\"text\" name=\"txtcantidaddev" . $g . "\"  id=\"txtcantidaddev" . $g . "\"    value=\"" . round($datos_V['cantidad']) . "\"  size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" >";
                $html .="        </td>\n";
                $html .= "      <td lign=\"left\">";
                $html .="       <input type=\"checkbox\" name=\"" . $g . "\" id=\"" . $g . "\"   value=\"" . $datos_V['codigo_producto'] . "\" > </td>  ";
                $html .="       </td>\n";
                $html .= " </tr> ";
                $g++;
            }

            $html .= "</table>";

            $html .= "	<table   width=\"30%\" align=\"right\" border=\"0\"   >";
            $html .= "  <tr>\n";
            $html .= "	      <td  colspan=\"10\"  align='right'>\n";
            $html .= "		  <input type=\"hidden\" name=\"valor3\" id=\"valor3\" value=\"1\">";
            $html .= "		  <input type=\"hidden\" name=\"cantidad_registrosde\" id=\"cantidad_registrosde\" value=\"" . $g . "\">";
            $html .= "        <input class=\"input-submit\" type=\"submit\" class=\"input-text\" name=\"btnCrear\"  value=\"MODIFICAR CANTIDAD A DEVOLVER\" >\n";
            $html .= " 		 </td>\n";
            $html .= "	</tr>\n";
            $html .= "</table><br>\n";
            $html .= "</fieldset><br>\n";
            $html .= "</center>\n";
        }
        $html .= "</form>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /**/
    /* 	 Funcion que  Contiene la Forma  del Mensaje cuando se realiza la solicitud de gerencia
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaMensajeSolcitud_P($action, /* $datos_empresa,$datos,$datos_s,$Empresa_D, */ $solicitud) {

        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html .= $ctl->IsNumeric();
        $html .= $ctl->AcceptNum(false);
        $html .= ThemeAbrirTabla('MENSAJE-PEDIDO A BODEGA');
        $html .= "<center> ";
        $html .= "<fieldset class=\"fieldset\" style=\"width:80%\" >\n";
        $html .= " <table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "       <tr class=\"formulacion_table_list\">\n";
        $html .= "             <td align=\"center\">\n";
        $html .= "               SE GENERO LA SOLCITUD PARA LA FARMACIA PEDIDO No " . $solicitud . "</td> ";
        $html .= "      </tr>\n";
        $html .= "</table><br>\n";
        $html .= "</fieldset><br>\n";
        $html .= "</center>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*   Funcion que  Contiene la Forma  de Generar rotacion por producto
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */

    function FormaGenerarRotacionProducto($action, $medicamentos_d, $diferencia_meses_fecha) {
        
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");

        $html = $ctl->IsNumeric();
        $html .= $ctl->AcceptNum(false);

        $html .= ThemeAbrirTabla('ROTACION - PRODUCTOS');
        $html .= $ctl->RollOverFilas();
        $html .= "<center>\n";
        $html .= "<fieldset class=\"fieldset\" >\n";
        $html .= "<form name=\"FormaRotacionLaboratorio\" id=\"FormaRotacionLaboratorio\" method=\"post\"  action=\"" . $action['guardar'] . "\">\n";

        $html .= "  <table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
        $html .= "	<tr  align=\"center\" class=\"formulacion_table_list\" >\n";
        $html .= "      <td  width=\"1%\">CODIGO</td>\n";
        $html .= "      <td  width=\"1%\">MOLECULA</td>\n";
        $html .= "      <td  width=\"20%\">MEDICAMENTO</td>\n";
        $html .= "      <td  width=\"5%\">LABORATORIO</td>\n";
        $html .= "      <td  width=\"5%\">TIPO PRODUCTO</td>\n";
        for ($i = 1; $i <= $diferencia_meses_fecha; $i++) {
            $html .= "  <td width=\"3%\" >SALIDAS {$i}</td>\n";
        }
        $html .= "      <td  width=\"5%\">STOCK FARMACIA</td>\n";
        $html .= "      <td  width=\"5%\">STOCK BODEGA</td>\n";
        $html .= "      <td  width=\"15%\">BODEGA</td>\n";
        $html .= "      <td  width=\"1%\">NIVEL</td>\n";
        $html .= "</tr>\n";
        $separator="@";
        $csv=$this->cabecera();       
        
        if (!empty($medicamentos_d)) {
            $j = 0;
            foreach ($medicamentos_d as $producto => $detalle) {
                $existencia = $detalle['stock_farmacia'];

                $est = ($est == 'modulo_list_oscuro') ? 'modulo_list_claro' : 'modulo_list_oscuro';
                $bck = ($bck == "#CCCCCC") ? "#DDDDDD" : "#CCCCCC";
                $html .= "  <tr  class=\"" . $est . "\" onmouseout=mOut(this,\"" . $back . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
                $html .= "      <td align=\"left\">{$detalle['codigo_producto']}</td>\n";
                $html .= "      <td align=\"left\">{$detalle['molecula']}</td>\n";
                $html .= "      <td align=\"left\">{$detalle['descripcion_producto']}</td>\n";
                $html .= "      <td align=\"left\">{$detalle['laboratorio']}</td>\n";
                $html .= "      <td align=\"left\">{$detalle['tipo_producto']}</td>\n";

                $total_egresos = 0;
                $totales=0;
                $meses=count($detalle['cantidad_total_despachada']);
                for ($i = 1; $i <= $meses; $i++) {
                    $total_egresos = $total_egresos + $detalle['cantidad_total_despachada'][$i];
                    $html .= "      <td  align='center'>" . $detalle['cantidad_total_despachada'][$i] . "</td>\n";
                    $totales+=$detalle['cantidad_total_despachada'][$i];
                }
                $html .= "      <td align='center'>{$existencia}</td>\n";
                $html .= "      <td align='center'>{$detalle['stock_bodega']}</td>\n";
                $promedio_mes=floor($totales/$meses);
                $promedio_dia=(($totales/$meses)/30); 
                $pedido_60=(($promedio_dia*60)-$existencia);
                $pedido_60=round(($pedido_60<0)?0:$pedido_60);
                $mes_mes=round($existencia/($totales/$meses));
        if($detalle['codigo_producto'][0]=='1'){        
                $csv .=$detalle['codigo_producto'].$separator;
                $csv .=$detalle['descripcion_producto'].$separator;
                $csv .=$detalle['molecula'].$separator;
                $csv .=$promedio_mes.$separator;
                $csv .=$existencia.$separator;
                $csv .=$pedido_60.$separator;
                $csv .=$separator;
                $csv .=$detalle['stock_bodega'];
                $csv .= "\n";
        }else{
                $csv2 .=$detalle['codigo_producto'].$separator;
                $csv2 .=$detalle['descripcion_producto'].$separator;
                $csv2 .=$detalle['molecula'].$separator;
                $csv2 .=$promedio_mes.$separator;
                $csv2 .=$existencia.$separator;
                $csv2 .=$pedido_60.$separator;
                $csv2 .=$separator;
                $csv2 .=$detalle['stock_bodega'];
                $csv2 .= "\n";
        }
                $porcentaje_pedidos = (($total_egresos / $diferencia_meses_fecha) / $existencia) * 100;

                $porcentaje_rotacion = ModuloGetVar("", "", "rotacion_porcentaje");

                $sugerido_pedido = 0;
                
                if ($existencia == 0 || $porcentaje_pedidos > $porcentaje_rotacion) {
                    $sugerido_pedido = $existencia - abs($total_egresos / $diferencia_meses_fecha);                    
                }
                               
                

                $html .= "      <td align=\"left\">" . $detalle['farmacia'] . "  </td>\n";
                //$html .="       <td align=\"left\"><input  class=\"input-text\"  style=\"width:100%\" type=\"text\" name=\"txtpedido" . $j . "\" value=\"" . intval($sugerido_pedido) . "\"  size=\"5\"></td>";
                //$html .="       <td align=\"left\">";
                //$html .="       <input type=\"checkbox\" name=\"" . $j . "\" value=\"" . $detalle['codigo_producto'] . "\" >";
                /* $html .="       ".$detalle['codigo_producto']; */
                //$html .="       <input type=\"hidden\" name=\"tipo_producto".$j."\" value=\"".$detalle['tipo_producto']."\" >";
                //$html .="       </td>  ";
                $html .= "  <td>" . $detalle['nivel'] . "</td>\n";
                $html .= "  </tr>\n";

                $j++;
            }
            $csv .=$csv2;
            /* foreach($medicamentos_d as $key => $descripcion)
              {
              foreach($descripcion as $key2 => $dtlm)
              {
              //print_r($dtlm);
              foreach($dtlm as $key3 => $dtl)
              {
              // print_r($dtl);
              $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro';
              $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
              $html .= "  <tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
              $html .= "      <td align=\"left\">".$key." </td>\n";
              $html .= "      <td align=\"left\">".$key2."  </td>\n";
              $html .= "      <td align=\"left\">".$key3."  </td>\n";

              $sumaE=0;
              $sumaI=0;

              for($j=0;$j<$meses;$j++)
              {
              $sumaE=$sumaE +($dtl[$FechaPeriodo[$j]]['egreso']);
              $sumaI=$sumaI +($dtl[$FechaPeriodo[$j]]['ingreso']);

              if($dtl[$FechaPeriodo[$j]]['fecha_registro']!="")
              {
              $html .= "      <td align=\"left\">".FormatoValor($dtl[$FechaPeriodo[$j]]['ingreso'])."</td>\n";
              $html .= "      <td align=\"left\">".FormatoValor($dtl[$FechaPeriodo[$j]]['egreso'])."</td>\n";
              } else {
              $html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
              $html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
              }
              }

              $gastoE=($sumaE/$meses);
              $porce=$gastoE/100;

              $Existencia=$mdl->Existencia_x_Producto($empresa,$key,$bodega_id);

              $html .= "      <th  style=\"background:#D8BFD8\" align=\"left\"  >".FormatoValor($Existencia[0]['existencia'])."</th>\n";
              $exitenciaD=$mdl->Existencia_x_Producto_Bodega($Empresa_D,$key,$Bodega_D);
              //Formula
              $sugerido_pedido="";
              $sugerido_compras="";
              $porcentaje_compras=0;
              $porcentaje_pedido=0;

              $porcentaje_pedido=($gastoE/$Existencia[0]['existencia'])*100;
              $porcentaje_compras=($gastoE/$exitenciaD['existencia'])*100;

              if($porcentaje_pedido>$porcentaje_rotacion || $Existencia[0]['existencia']==0)
              $sugerido_pedido = Abs($Existencia[0]['existencia']-$gastoE);

              if($porcentaje_compras>$$porcentaje_rotacion || $exitenciaD['existencia']==0)
              $sugerido_compras = Abs($exitenciaD['existencia']-$gastoE);
              //Fin Formula

              $html .= "      <th  style=\"background:#D8BFD8\" align=\"left\"  >".FormatoValor($exitenciaD['existencia'])."</th>\n";
              //$html .= "      <td align=\"left\"  >".FormatoValor($porce,1)."%(".FormatoValor($porcentaje_pedido,2)."%)(".FormatoValor($porcentaje_compras,2)."%)</td>\n";
              $html .= "      <td align=\"left\"  >".FormatoValor($porce,1)."</td>\n";
              $html .="        <th  aling=\"left\">";
              $html .="  <input  class=\"input-text\"  style=\"width:100%\" type=\"text\" name=\"txtpedido".$i."\"  id=\"txtpedido".$i."\"    value=\"".intval($sugerido_pedido)."\"  size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" >";

              $html .="        <th  aling=\"left\">";
              $html .="  <input  class=\"input-text\"  style=\"width:100%\" type=\"text\" name=\"txtdevolucion".$i."\"  id=\"txtdevolucion".$i."\"    value=\"\"  size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" >";

              $html .="        <th  aling=\"left\">";
              $html .="  <input  class=\"input-text\"  style=\"width:100%\" type=\"text\" name=\"txtcantidad".$i."\"  id=\"txtcantidad".$i."\"    value=\"".intval($sugerido_compras)."\"  size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" >";

              $html .="        <th  aling=\"left\">";
              $html .="<textarea   style=\"width:100%\"  class=\"input-text\" name=\"observa".$i."\" cols=\"5\" row=\"3\" ></textarea> ";

              $html .="        <td  aling=\"left\">";
              $html .= "		  <input type=\"hidden\" name=\"tipo_prod".$i."\" id=\"tipo_prod".$i."\" value=\"".$Existencia[0]['tipo_producto_id']."\">";
              $html .=" <input type=\"checkbox\" name=\"".$i."\" id=\"".$i."\"   value=\"".$key."\" > </td>  ";
              $html .= "<center>\n";
              $i++;
              }
              }
              }

              $html .= "  </tr>\n";
              $html .= "</table>";
              $html .= "<br>";
              $html .= "	<table   width=\"30%\" align=\"right\" border=\"0\">";
              $html .= "  <tr>\n";
              $html .= "	      <td  colspan=\"10\"  align='right'>\n";
              $html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$i."\">";
              $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"REALIZAR SOLICITUD\" >\n";
              $html .= "      </td>\n";
              $html .= "  </tr>\n"; */
            $html .= "	</table>\n";

            $html .= "<br>";
            $html .= "	<table   width=\"30%\" align=\"right\" border=\"0\">";
            $html .= "  <tr>\n";
            $html .= "	      <td  colspan=\"10\"  align='center'>\n";
            $html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"" . $j . "\">";
            //$html .= "        <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"REALIZAR SOLICITUD\" >\n";
            $html .= "      </td>\n";
            $html .= "  </tr>\n";
            $html .= "	</table>\n";
            $html .= "	<br>\n";
            
                $time = time();
                $fecha= date("d_m_Y", $time);
                $nombre_rotacion="Rotacion_".$detalle['farmacia']."_".$fecha.".csv";
                $nombre_archivo = realpath('./'). '/cache/'.$nombre_rotacion;
                if(file_exists($nombre_archivo))
                {
                    $mensaje = "El Archivo $nombre_archivo se ha modificado";
                }

                else
                {
                    $mensaje = "El Archivo $nombre_archivo se ha creado";
                }

                if($archivo = fopen($nombre_archivo, "w"))
                {
                    if(fwrite($archivo, $csv))
                    {
                        if(file_exists($nombre_archivo)) 
                            {
                                $archivo = $_ROOT . "cache/".$nombre_rotacion;
                                $html .="<div align=\"center\"><a href='$archivo' target='_blank'>DESCARGAR ROTACI&Oacute;N</a></div>";
                            }
                    }
                    else
                    {
                        echo "Ha habido un problema al crear el archivo";
                    }

                    fclose($nombre_archivo);
                }
        } else {
            $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA GENERAR LA ROTACION</center><br>\n";
        }

        $html .= "</form>\n";
        $html .= "</fieldset><br>\n";
        $html .= "</center>\n";
        $html .= " <br>";
        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\"  class=\"label_error\">\n";
        $html .= "        VOLVER\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }
    
     function cabecera($bodega){    
        $separador="@";
        $csv  ="ROTACION DUANA ".$bodega;
        $csv .= $separador."".$separador."".$separador."".$separador."".$separador."".$separador."".$separador."\n";
        $csv .="CODIGO";
        $csv .= $separador;
        $csv .="MEDICAMENTO";
        $csv .= $separador;
        $csv .="MOLECULA"; 
        $csv .= $separador;
        $csv .="PROMEDIO MES";
        $csv .= $separador;
        $csv .="STOCK FARMACIA";
        $csv .= $separador;
        $csv .="PEDIDO 60 DIAS";
        $csv .= $separador;
        $csv .="";
        $csv .= $separador;
        $csv .="STOCK BODEGA";
        $csv .= "\n";
        return $csv;
    }

    function rotacion_producto($lista_empresas, $lista_zonas, $action) {

        //var_dump($_REQUEST);
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->RollOverFilas();
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html .= ThemeAbrirTabla('ROTACION FARMACIAS POR PRODUCTO');

        $html .= "
                <script>
                    function validar_datos(){
                    
                        var empresa_id = document.getElementById('empresa_id').value;
                        var zona_id = document.getElementById('zona_id').value;
                        var codigo_producto = document.getElementById('codigo_producto').value;
                        var proyeccion = document.getElementById('proyeccion').value;
                        var fecha_inicial = document.getElementById('fecha_inicio').value; 
                        var fecha_final = document.getElementById('fecha_final').value; 
                        
                        
                        
                        var meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                                     'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

                        if(empresa_id == '-1'){
                            alert('SELECCIONE LA EMPRESA');
                            return false;
                        }
                        
                        if(zona_id == '-1'){
                            alert('SELECCIONE LA ZONA');
                            return false;
                        }
                        
                        if(codigo_producto == ''){
                            alert('INGRESE EL CODIGO DEL PRODUCTO');
                            return false;
                        }
                        
                        if(fecha_inicial == ''){
                            alert('SELECCIONE UNA FECHA INICIAL');
                            return false;
                        }
                                              
                        if(fecha_final == ''){
                            alert('SELECCIONE UNA FECHA FINAL');
                            return false;
                        }                               

                        fecha_inicial = fecha_inicial+'/01';
                        fecha_final = fecha_final+'/01';
                        
                        _fecha_inicial = new Date(fecha_inicial);                        
                        _fecha_final = new Date(fecha_final);
                        
                        if(_fecha_final.getTime() < _fecha_inicial.getTime()){
                            alert('LA FECHA FINAL DEBE SER MAYOR A LA INICIAL');
                            return false;
                        }

                        var ultimo_dia_mes = new Date(_fecha_final.getFullYear(), _fecha_final.getMonth() + 1, 0);
                        _fecha_final.setDate(ultimo_dia_mes.getDate());
                        
                        var cantidad_meses = calcular_diferencia_meses(_fecha_inicial, _fecha_final);
                        
                        if(proyeccion > cantidad_meses){
                            alert('LA PROYECCION DEBE SER MENOR A LA CANTIDAD DE MESES SELECCIONADOS');
                            return false;
                        } 

                        proyeccion = (proyeccion=='0')?1:proyeccion;

                        var periodos_tiempo = {};                        
                        for (var i=0; i<= cantidad_meses; i++){
                            fecha = new Date(fecha_inicial);
                            var mes_inicial = fecha.getMonth();
                            fecha.setMonth(mes_inicial+i); 
                            
                            var primer_dia_mes = '01';
                            var ultimo_dia_mes = new Date(fecha.getFullYear(), fecha.getMonth() + 1, 0).getDate();
                            
                            var mes = (fecha.getMonth()+1 < 10)?'0'+(fecha.getMonth()+1):fecha.getMonth()+1;
                            f_inicial = fecha.getFullYear() +'-'+ mes +'-'+primer_dia_mes;
                            f_final = fecha.getFullYear() +'-'+ mes +'-'+ultimo_dia_mes;;
                            periodos_tiempo[i]= [f_inicial,f_final, meses[fecha.getMonth()]+'-'+fecha.getFullYear()];
                        }                                               
                                                
                       xajax_rotacion_producto(periodos_tiempo, zona_id, proyeccion, codigo_producto);
                        
                    }
                    

                    function calcular_diferencia_meses(d1, d2) {
                    
                        var months;
                        months = (d2.getFullYear() - d1.getFullYear()) * 12;                        
                        months -= d1.getMonth() + 1;
                        months += d2.getMonth() + 1;                        
                        return months <= 0 ? 0 : months;
                    }
                </script>                    
        ";

        $html .= "<form name='rotacion_producto' id='rotacion_producto'  action='' method='post'>";
        $html .= "  <table   width=\"450px\"  class=\"modulo_table_list\" align=\"center\" border=\"0\"  >";
        $html .= "      <tr>\n";
        $html .= "          <td align='left'  class='formulacion_table_list' width=\"35%\" ><b>EMPRESA :</b></td>\n";
        $html .= "          <td align='left'  class='modulo_list_claro'>
                                <select name='empresa_id' id='empresa_id' class=\"select\">
                                    <option value='-1'>--  SELECCIONE --</option>";
        foreach ($lista_empresas as $key => $value) {
            $html .= "              <option value='{$value['empresa_id']}'>{$value['razon_social']}</option>";
        }
        $html .= "              </select>
                            </td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr> \n";
        $html .= "          <td align='left' class=\"formulacion_table_list\" width=\"35%\" ><b>ZONA :</B></td>\n";
        $html .= "          <td  class=\"modulo_list_claro\" colspan=\"4\">\n";
        $html .= "              <select name='zona_id' id='zona_id' class='select'>\n";
        $html .= "                  <option value = '-1'>--  SELECCIONE --</option>\n";
        foreach ($lista_zonas as $key => $value) {
            $html .= "              <option value='{$value['zona_bodega_id']}'>{$value['zona_bodega']}</option>";
        }
        $html .= "              </select>\n";
        $html .= "          </td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr >\n";
        $html .= "          <td  align='left' class=\"formulacion_table_list\" width=\"35%\" >CODIGO PRODUCTO:</td>\n";
        $html .= "          <td  class=\"modulo_list_claro\" align=\"left\">\n";
        $html .= "              <input type='text' class='input-text' name='codigo_producto'   id='codigo_producto' size='19' value=''>\n";
        $html .= "          </td>\n";
        $html .= "	</tr>\n";
        $html .= "      <tr >\n";
        $html .= "          <td  align='left' class=\"formulacion_table_list\" width=\"35%\" >PROYECCION:</td>\n";
        $html .= "          <td  class=\"modulo_list_claro\" align=\"left\">\n";
        $html .= "              <input type='text' class='input-text' name='proyeccion'   id='proyeccion' size='19' maxlength='10' value=''>\n";
        $html .= "          </td>\n";
        $html .= "	</tr>\n";
        $html .= "      <tr>\n";
        $html .= "          <td align='left'  class='formulacion_table_list' width=\"35%\" ><b>FECHA INICIAL :</b></td>\n";
        $html .= "          <td align='left'  class='modulo_list_claro'> 
                                <input type=\"text\" class=\"input-text\" name=\"fecha_inicio\"   id=\"fecha_inicio\" size=\"19\" maxlength=\"7\"  value=\"\"  >                                
                                <span>[yyyy/mm] ej: 2013/01</span>
                            </td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr>\n";
        $html .= "          <td align='left'  class='formulacion_table_list' width=\"35%\" ><b>FECHA FINAL :</b></td>\n";
        $html .= "          <td align='left'  class='modulo_list_claro'> 
                                <input type=\"text\" class=\"input-text\" name=\"fecha_final\"   id=\"fecha_final\" size=\"19\" maxlength=\"7\" value=\"\"  >                                
                                <span>[yyyy/mm] ej: 2013/11</span>
                            </td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "  <table   width=\"25%\"  class=\"normal_10AN\" align=\"center\" border=\"0\"  >";
        $html .= "      <tr>\n";
        $html .= "          <td align='center'>\n";
        $html .= "              <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"Continuar\" onclick=\"validar_datos();\">\n";
        $html .= "          </td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
        $html .= "<div align=\"center\" class=\"label_error\" id='error'></div> ";
        $html .= "<div id='resultado_rotacion'></div>";
        $html .= "<br>";
        $html .= "<table align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";

        $html .= $this->CrearVentana(800, "SELECCIONE PRODUCTO");
        $html .= ThemeCerrarTabla();
        return $html;
    }
    
    function rotacion_general_farmacias($lista_empresas, $lista_zonas, $action) {

        //var_dump($_REQUEST);
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->RollOverFilas();
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html .= ThemeAbrirTabla('ROTACION GENERAL FARMACIAS');

        $html .= "
                <script>
                    function validar_datos(){
                    
                        var empresa_id = document.getElementById('empresa_id').value;
                        
                        var fecha_inicial = document.getElementById('fecha_inicio').value; 
                        var fecha_final = document.getElementById('fecha_final').value; 
                        
                        
                        
                        var meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                                     'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

                        if(empresa_id == '-1'){
                            alert('SELECCIONE LA EMPRESA');
                            return false;
                        }
                        
                        if(fecha_inicial == ''){
                            alert('SELECCIONE UNA FECHA INICIAL');
                            return false;
                        }
                                              
                        if(fecha_final == ''){
                            alert('SELECCIONE UNA FECHA FINAL');
                            return false;
                        }                               

                        /*fecha_inicial = fecha_inicial+'/01';
                        fecha_final = fecha_final+'/01';*/
                        
                        _fecha_inicial = new Date(fecha_inicial);                        
                        _fecha_final = new Date(fecha_final);
                        
                        if(_fecha_final.getTime() < _fecha_inicial.getTime()){
                            alert('LA FECHA FINAL DEBE SER MAYOR A LA INICIAL');
                            return false;
                        }
                        
                       xajax_rotacion_general_farmacias(empresa_id, fecha_inicial, fecha_final);
                        
                    }
                    

                    function calcular_diferencia_meses(d1, d2) {
                    
                        var months;
                        months = (d2.getFullYear() - d1.getFullYear()) * 12;                        
                        months -= d1.getMonth() + 1;
                        months += d2.getMonth() + 1;                        
                        return months <= 0 ? 0 : months;
                    }
                </script>                    
        ";

        $html .= "<form name='rotacion_producto' id='rotacion_producto'  action='' method='post'>";
        $html .= "  <table   width=\"450px\"  class=\"modulo_table_list\" align=\"center\" border=\"0\"  >";
        $html .= "      <tr>\n";
        $html .= "          <td align='left'  class='formulacion_table_list' width=\"35%\" ><b>EMPRESA :</b></td>\n";
        $html .= "          <td align='left'  class='modulo_list_claro'>
                                <select name='empresa_id' id='empresa_id' class=\"select\">
                                    <option value='-1'>--  SELECCIONE --</option>";
        foreach ($lista_empresas as $key => $value) {
            $html .= "              <option value='{$value['empresa_id']}'>{$value['razon_social']}</option>";
        }
        $html .= "              </select>
                            </td>\n";
        $html .= "      </tr>\n"; 
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "		<td  width=\"30%\" >FECHA INICIAL:</td>\n";
        $html .= "		<td width=\"15%\" align=\"left\" class=\"modulo_list_claro\" >\n";
        $html .= "		  <input type=\"text\" class=\"input-text\" name=\"fecha_inicio\"   id=\"fecha_inicio\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\"  >\n";
        $html .= "		</td>\n";
        $html .= "    <td  width=\"15%\" class=\"modulo_list_claro\" >\n";
        $html .= "				" . ReturnOpenCalendario('rotacion_producto', 'fecha_inicio', '/') . "\n";
        $html .= "		</td>\n";
        $html .= "  </tr >\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "		<td  width=\"30%\" >FECHA FINAL:</td>\n";
        $html .= "		<td width=\"15%\" align=\"left\" class=\"modulo_list_claro\" >\n";
        $html .= "		  <input type=\"text\" class=\"input-text\" name=\"fecha_final\"   id=\"fecha_final\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\"  >\n";
        $html .= "		</td>\n";
        $html .= "    <td  width=\"15%\" class=\"modulo_list_claro\" >\n";
        $html .= "				" . ReturnOpenCalendario('rotacion_producto', 'fecha_final', '/') . "\n";
        $html .= "		</td>\n";
        $html .= "  </tr >\n";        
        $html .= "  </table>\n";
        $html .= "  <table   width=\"25%\"  class=\"normal_10AN\" align=\"center\" border=\"0\"  >";
        $html .= "      <tr>\n";
        $html .= "          <td align='center'>\n";
        $html .= "              <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"Continuar\" onclick=\"validar_datos();\">\n";
        $html .= "          </td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
        $html .= "<div align=\"center\" class=\"label_error\" id='error'></div> ";
        $html .= "<div id='resultado_rotacion'></div>";
        $html .= "<br>";
        $html .= "<table align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";

        $html .= $this->CrearVentana(800, "SELECCIONE PRODUCTO");
        $html .= ThemeCerrarTabla();
        return $html;
    }

}

?>