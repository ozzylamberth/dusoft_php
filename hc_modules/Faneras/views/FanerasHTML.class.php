<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: FanerasHTML.class.php,v 1.1 2009/11/06 14:42:11 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase vista: FanerasSQLHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class FanerasHTML 
  {
    /**
    * Constructot de la clase
    */
    function FanerasHTML(){}
    /**
    *
    */
    function FormaMostrarPielFaneras($action,$coordenadas,$sensibilidad,$sectores,$puntajes,$puntajeEvolucion,$evolucion,$ingreso)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      
      $html  = $ctl->LimpiarCampos();
      $html .= ThemeAbrirTabla('EVALUACIÓN FISIOTERAPÉUTICA');
      $html .= "<table align=\"left\" class=\"modulo_table_list\" width=\"100%\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td colspan=\"2\">PIEL Y FANERAS</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr >\n";
      $html .= "    <td width=\"530\" rowspan=\"2\">\n";
      $html .= "      <table align=\"center\" class=\"modulo_table_list\" width=\"540\">\n";
      $html .= "        <tr>\n";
      $html .= "          <td height=\"620\" width=\"530\">\n";
      $html .= "            <div id=\"cuerpo_franjas\" style=\"position:absolute;width:540px;height:706px;top:0px;left:0px;z-index:3\">\n";
      $html .= "              <img src=\"images/piel_faneras.png\" width=\"540\" height=\"706\" border=\"0\" usemap=\"#Map\" />\n";
      $html .= "              <map name=\"Map\" >\n";
      $scpt  = "   var puntos = new Array();\n"; 
      foreach($coordenadas as $key => $dtl)
      {
        $mapa = str_replace("(","",$dtl['datos_coordenadas']);
        $mapa = str_replace(")","",$mapa);
        $html .= "              <area id=\"".$key."\" shape=\"poly\" style=\"cursor:pointer\" target=\"\" coords=\"".$mapa."\" onclick=\"MostrarIngresoDatos('".$key."');\">\n";
        $scpt .= "   puntos[".$key."] = new Array(".$mapa.");\n"; 
      }
      $html .= "              </map>\n";
      $html .= "            </div>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "    <td valign=\"top\" >\n";
      $html .= "      <table align=\"left\" width=\"300\" class=\"modulo_table_list\">\n";
      $html .= "        <tr class=\"formulacion_table_list\">\n";
      $html .= "          <td colspan=\"3\">SENSIBILIDAD</td>\n";
      $html .= "        </tr>\n";
      $scpt .= "   var colores = new Array();\n"; 
      foreach($sensibilidad as $key => $dt)
      {
        $html .= "        <tr>\n";
        $html .= "          <td width=\"50%\" class=\"normal_10AN\">".$dt['sensibiliad_descripcion']."</td>\n";
        $html .= "          <td width=\"50%\" style=\"background:".$dt['color_nombre1'].";height:26px\">&nbsp;</td>\n";
        $html .= "        </tr>\n";
        $scpt .= "   colores[".$dt['piel_fanera_color_id']."] = '".$dt['color_nombre1']."';\n"; 
      }
      
      $scpt .= "   colores[0] = '#F4F4F4';\n"; 
      $html .= "      </table>\n";
      $html .= "    </td>\n";

      $html .= "  </tr>\n";      
      $html .= "  <tr>\n";      
      $html .= "    <td valign=\"top\">\n";
      $html .= "      <form name=\"puntaje_dolor\" id=\"puntaje_dolor\" action=\"javascript:EvaluarPuntaje(document.puntaje_dolor)\" method=\"post\">\n";
      $html .= "        <table align=\"left\" class=\"modulo_table_list\" width=\"300\" >\n";
      $html .= "          <tr class=\"formulacion_table_list\">\n";
      $html .= "            <td colspan=\"3\">DOLOR</td>\n";
      $html .= "          </tr>\n";      
      $html .= "          <tr>\n";
      $html .= "            <td class=\"formulacion_table_list\" >PUNTAJE</td>\n";
      $html .= "            <td class=\"modulo_list_claro\">\n";
      $html .= "              <select name=\"puntaje_eva\" class=\"select\" onchange=\"SeleccionarEscala(this.value)\">\n";
      $html .= "                <option value=\"-1\">---</option>\n";
      $slt = "";
      foreach($puntajes as $key => $dtl)
      {
        ($dtl['puntaje_eva'] == $puntajeEvolucion['puntaje_eva'])? $slt = "selected":$slt = "";
        $html .= "                <option value=\"".$dtl['puntaje_eva']."\" ".$slt.">".$dtl['puntaje_eva']."</option>\n";
      }
      $html .= "              </select>\n";
      $html .= "            </td>\n";
      $html .= "            <td class=\"modulo_list_claro\">\n";
      $html .= "              <input type=\"hidden\" name=\"actualizar_eva\" id=\"actualizar_eva\" value=\"".(($puntajeEvolucion['puntaje_eva'])? "1":"0")."\">\n";
      $html .= "              <input type=\"hidden\" name=\"ingreso\" value=\"".$ingreso."\">\n";
      $html .= "              <input type=\"hidden\" name=\"evolucion_id\" value=\"".$evolucion."\">\n";
      $html .= "              <input type=\"submit\" class=\"input-submit\" name=\"Guardar\" value=\"Guardar\">\n";
      $html .= "            </td>\n";
      $html .= "          </tr>\n";
      $html .= "          <tr>\n";
      $html .= "            <td colspan=\"3\" height=\"391\" align=\"center\" valign=\"top\">\n";
      $html .= "              <div id=\"error_eva\" class=\"label_error\"></div>\n";
      $html .= "            </td>\n";
      $html .= "          </tr>\n";
      $html .= "        </table>\n";
      $html .= "      </form>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "   <tr>\n";
      $html .= "     <td colspan=\"2\">\n";
      $html .= "      <div id=\"datos_ingresados\">\n";
      if(!empty($sectores))
      {
        $html .= "  <table align=\"left\" width=\"100%\" class=\"modulo_table_list\">\n";
        $html .= "    <tr class=\"formulacion_table_list\" align=\"center\">\n";
        $html .= "      <td width=\"1%\"></td>\n";
        $html .= "      <td >SENSIBILIDAD</td>\n";
        $html .= "      <td width=\"10%\">INTERMITENTE</td>\n";
        $html .= "      <td width=\"10%\">PERSISTENTE</td>\n";
        $html .= "      <td >QUE LO AUMENTA</td>\n";
        $html .= "      <td >QUE LO DISMINUYE</td>\n";
        $html .= "      <td width=\"10%\">IRRADIADO</td>\n";
        $html .= "      <td width=\"10%\">REFERIDO</td>\n";
        $html .= "      <td>OBSERVACION</td>\n";
        $html .= "    </tr>\n";
        foreach($sectores as $key => $dtl)
        {
          $html .= "    <tr class=\"modulo_list_claro\">\n";
          $html .= "      <td class=\"label\">".$dtl['franja']."</td>\n";
          $html .= "      <td>".$dtl['sensibiliad_descripcion']."</td>\n";
          $html .= "      <td align=\"center\">".(($dtl['persistencia'] == '2')? "X":"&nbsp;")."</td>\n";
          $html .= "      <td align=\"center\">".(($dtl['persistencia'] == '1')? "X":"&nbsp;")."</td>\n";
          $html .= "      <td>".$dtl['aumento_descripcion']."&nbsp;</td>\n";
          $html .= "      <td>".$dtl['disminucion_descripcion']."&nbsp;</td>\n";
          $html .= "      <td align=\"center\">".(($dtl['referencia'] == '1')? "X":"&nbsp;")."</td>\n";
          $html .= "      <td align=\"center\">".(($dtl['referencia'] == '2')? "X":"&nbsp;")."</td>\n";
          $html .= "      <td>".$dtl['observacion']."&nbsp;</td>\n";
          $html .= "    </tr>\n";
        }
        $html .= "  </table>\n";
      }
      $html .= "      </div>\n";
      $html .= "     </td>\n";
      $html .= "   </tr>\n";
      $html .= "</table>\n";
      $html .= "<script>\n";
      $html .= $scpt;
      $html .= "  var ingresado = new Array();\n";
      $html .= "  var circulos = new Array();\n";
      $html .= "  var gr = new jsGraphics(document.getElementById('CapaHc'));\n";
      $html .= "  var valores = new Array();\n";
      foreach($puntajes as $key => $dtl)
        $html .= "    valores[".$dtl['puntaje_eva']."] = '".$dtl['equivalencia_eva']."';\n";

      $html .= "  function MostrarIngresoDatos(area)\n";
      $html .= "  {\n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      objeto = document.clasificacion_faneras;\n";
      $html .= "      document.getElementById('eliminar_sector').style.display = 'none';\n";
      $html .= "      LimpiarCampos(objeto);\n";
      $html .= "      if(ingresado[area] != undefined)\n";
      $html .= "      {\n";
      $html .= "        objeto.sensibilidad.value = ingresado[area][0];";
      $html .= "        objeto.persistencia[ingresado[area][1]].checked = true;";
      $html .= "        objeto.aumento.value = ingresado[area][2];";
      $html .= "        objeto.disminucion.value = ingresado[area][3];";
      $html .= "        objeto.referencia[ingresado[area][4]].checked = true;";
      $html .= "        objeto.observacion.value = ingresado[area][5];";
      $html .= "        objeto.actualizar.value = '1';";
      $html .= "        document.getElementById('eliminar_sector').style.display = 'block';\n";
      $html .= "      }\n";
      $html .= "      else\n";
      $html .= "        objeto.actualizar.value = '0';";
      $html .= "    }\n";
      $html .= "    catch(error){}\n";
      $html .= "    document.clasificacion_faneras.area_id.value = area;\n";
      $html .= "    MostrarSpan('Contenedor');\n";
      $html .= "  }\n";
      $html .= "  function GuardarSector(objeto)\n";
      $html .= "  {\n";
      $html .= "    xajax_IngresarClasificacion(xajax.getFormValues('clasificacion_faneras'));\n";
      $html .= "  }\n";       
      $html .= "  function EvaluarPuntaje(objeto)\n";
      $html .= "  {\n";
      $html .= "    if(objeto.puntaje_eva.value != '-1')\n";
      $html .= "      xajax_IngresarPuntajeEva(xajax.getFormValues('puntaje_dolor'));\n";
      $html .= "    else\n";      
      $html .= "    {\n";      
      $html .= "      e = document.getElementById('error_eva');\n";      
      $html .= "      e.innerHTML = 'DEBE SELECCIONAR UN PUNTAJE A ASIGNAR';\n";      
      $html .= "    }\n";      
      $html .= "  }\n";      
      $html .= "  function EliminarSector(objeto)\n";
      $html .= "  {\n";
      $html .= "    if(confirm('Esta seguro que desea eliminar la información de este sector?'))\n";
      $html .= "    {\n";
      $html .= "      var arr = objeto.area_id.value;\n";
      $html .= "      ingresado[arr] = new Array();\n";
      $html .= "      xajax_EliminarClasificacion(xajax.getFormValues('clasificacion_faneras'));\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function CrearSector(objeto)\n";
      $html .= "  {\n";
      $html .= "    var arr = objeto.area_id.value;\n";
      $html .= "    ingresado[arr] = new Array();\n";
      $html .= "    ingresado[arr][0] = objeto.sensibilidad.value;\n";
      $html .= "    ingresado[arr][1] = (objeto.persistencia[0].checked)? 0: 1;\n";
      $html .= "    ingresado[arr][2] = objeto.aumento.value;\n";
      $html .= "    ingresado[arr][3] = objeto.disminucion.value;\n";
      $html .= "    ingresado[arr][4] = (objeto.referencia[0].checked)? 0: 1;\n";
      $html .= "    ingresado[arr][5] = objeto.observacion.value;\n";
      $html .= "    Dibujar(arr,ingresado[arr][0]);\n";
      $html .= "    document.getElementById('error').innerHTML = '';\n";
      $html .= "    OcultarSpan('Contenedor');\n";
      $html .= "  }\n";
      $html .= "  function Dibujar(area,sns)\n";
      $html .= "  {\n";
      $html .= "    var col = new jsColor(colores[sns]);\n";
      $html .= "    var pen = new jsPen(col,2);\n";
      $html .= "    coords = new Array();\n";
      $html .= "    var j = 0;\n";
      $html .= "    for(i=0; i<puntos[area].length;i++)\n";
      $html .= "    {\n";
      $html .= "      coords[j] = new jsPoint(puntos[area][i++],puntos[area][i]);\n";
      $html .= "      j++;\n";
      $html .= "    }\n";
      $html .= "    gr.fillPolygon(col,coords);\n";
      $html .= "  }\n";     
      
      $html .= "  function DibujarEscala()\n";
      $html .= "  {\n";
      $html .= "    var col = new jsColor('#330066');\n";
      $html .= "    var pen = new jsPen(col,2);\n";
      $html .= "    gr.drawLine(pen, new jsPoint(580,400), new jsPoint(840,400));\n";
      $html .= "    x = 580; y1= 385; y2 = 415;i=0;\n";     
      $html .= "    do\n";     
      $html .= "    {\n";    
      $html .= "      circulos[i] = new Array();\n";    
      $html .= "      circulos[i][0] = x;\n";    
      $html .= "      circulos[i++][1] = y1+15;\n";    
      $html .= "      gr.drawLine(pen, new jsPoint(x,y1), new jsPoint(x,y2));\n";   
      $html .= "      x += 52;\n";
      $html .= "    }\n";      
      $html .= "    while(x <= 850);\n";
      $html .= "    var fnt = new jsFont('sans serif','bold','10pt');\n";
      $html .= "    gr.drawText('0', new jsPoint(578,365),fnt);\n";
      $html .= "    gr.drawText('10',new jsPoint(830,365),fnt);\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(590,435), 15)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(583,432), 2)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(597,432), 2)\n";
      $html .= "    gr.drawArc(pen,new jsPoint(590,425) , 30, 40, 60, 60)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(830,435), 15)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(823,432), 2)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(837,432), 2)\n";
      $html .= "    gr.drawArc(pen, new jsPoint(830,460), 30, 40, 240, 60)\n";
      $html .= "  }\n";

      $html .= "  function SeleccionarEscala(valor)\n";
      $html .= "  {\n";
      $html .= "    var col2 = new jsColor('#EDEDED');\n";
      $html .= "    var col1 = new jsColor('#FF0000');\n";
      $html .= "    var pen1 = new jsPen(col1,2);\n";
      $html .= "    var pen2 = new jsPen(col2,2);\n";
      $html .= "    elegido = valores[valor];\n";
      $html .= "    var j = 0;\n";
      $html .= "    for(i=0; i<=5; i++)\n";
      $html .= "    {\n";
      $html .= "      if(i == elegido)\n";
      $html .= "        gr.drawCircle(pen1,new jsPoint(circulos[i][0],circulos[i][1]), 8);\n";
      $html .= "      else\n";
      $html .= "        gr.drawCircle(pen2,new jsPoint(circulos[i][0],circulos[i][1]), 8);\n";
      $html .= "    }\n";
      $html .= "  }\n"; 
      
      foreach($sectores as $key => $dtl)
      {
        $html .= "  Dibujar('".$dtl['coordenada_id']."','".$dtl['piel_fanera_color_id']."');";
        $html .= "  ingresado[".$key."] = new Array();\n";
        $html .= "  ingresado[".$key."][0] = ".$dtl['piel_fanera_color_id'].";\n";
        $html .= "  ingresado[".$key."][1] = ".(($dtl['persistencia'])? "0": "1")."\n";
        $html .= "  ingresado[".$key."][2] = '".$dtl['aumento_descripcion']."';\n";
        $html .= "  ingresado[".$key."][3] = '".$dtl['disminucion_descripcion']."';\n";
        $html .= "  ingresado[".$key."][4] = ".(($dtl['persistencia'])? "0": "1")."\n";
        $html .= "  ingresado[".$key."][5] = '".$dtl['observacion']."';\n";
      }
      $html .= "  DibujarEscala();\n";
      if($puntajeEvolucion['puntaje_eva'])
        $html .= "  SeleccionarEscala('".$puntajeEvolucion['puntaje_eva']."');";
        
      $html .= "</script>\n";
      $html .= ThemeCerrarTabla();
      
      $stl = "style=\"text-align:left;padding:2px\"";

      $htm  = "<form name=\"clasificacion_faneras\" id=\"clasificacion_faneras\" action=\"javascript:GuardarSector(document.clasificacion_faneras)\" method=\"post\">\n";
      $htm .= " <table align=\"center\" width=\"94%\" class=\"modulo_table_list\">\n";
      $htm .= "   <tr class=\"formulacion_table_list\">\n";
      $htm .= "     <td colspan=\"2\">CLASIFICACIÓN PIEL Y FANERAS</td>\n";
      $htm .= "   </tr>\n";
      $htm .= "   <tr class=\"formulacion_table_list\">\n";
      $htm .= "     <td ".$stl.">SENSIBILIDAD</td>\n";
      $htm .= "     <td ".$stl." class=\"modulo_list_claro\">\n";
      $htm .= "       <select name=\"sensibilidad\" class=\"select\">\n";
      $htm .= "         <option value=\"-1\">--SELECCIONAR--</option>\n";
      foreach($sensibilidad as $key => $dt)
      {
        $htm .= "         <option value=\"".$dt['piel_fanera_color_id']."\">".$dt['sensibiliad_descripcion']."</option>\n";
      }
      $htm .= "       </select>\n";
      $htm .= "     </td>\n";
      $htm .= "   </tr>\n";
      $htm .= "   <tr class=\"modulo_list_claro\">\n";
      $htm .= "     <td ".$stl." class=\"normal_10AN\" >\n";
      $htm .= "       <input type=\"radio\" name=\"persistencia\" value=\"1\">&nbsp;INTERMITENTE\n";
      $htm .= "     </td>\n";
      $htm .= "     <td ".$stl." class=\"normal_10AN\">\n";
      $htm .= "       <input type=\"radio\" name=\"persistencia\" value=\"2\">&nbsp;PERSISTENTE\n";
      $htm .= "     </td>\n";
      $htm .= "   </tr>\n";
      $htm .= "   <tr class=\"formulacion_table_list\">\n";
      $htm .= "     <td ".$stl." colspan=\"2\">QUE LO AUMENTA</td>\n";
      $htm .= "   </tr>\n";      
      $htm .= "   <tr class=\"modulo_list_claro\">\n";
      $htm .= "     <td colspan=\"2\">\n";
      $htm .= "       <textarea name=\"aumento\" style=\"width:100%\" rows=\"2\" class=\"textarea\"></textarea>\n";
      $htm .= "     </td>\n";
      $htm .= "   </tr>\n";      
      $htm .= "   <tr class=\"formulacion_table_list\">\n";
      $htm .= "     <td ".$stl." colspan=\"2\">QUE LO DISMINUYE</td>\n";
      $htm .= "   </tr>\n";      
      $htm .= "   <tr class=\"modulo_list_claro\">\n";
      $htm .= "     <td colspan=\"2\">\n";
      $htm .= "       <textarea name=\"disminucion\" style=\"width:100%\" rows=\"2\" class=\"textarea\"></textarea>\n";
      $htm .= "     </td>\n";
      $htm .= "   </tr>\n";
      $htm .= "   <tr class=\"modulo_list_claro\">\n";
      $htm .= "     <td ".$stl." class=\"normal_10AN\" >\n";
      $htm .= "       <input type=\"radio\" name=\"referencia\" value=\"1\">&nbsp;IRRADIADO\n";
      $htm .= "     </td>\n";
      $htm .= "     <td ".$stl." class=\"normal_10AN\">\n";
      $htm .= "       <input type=\"radio\" name=\"referencia\" value=\"2\">&nbsp;REFERIDO\n";
      $htm .= "     </td>\n";
      $htm .= "   </tr>\n";
      $htm .= "   <tr class=\"formulacion_table_list\">\n";
      $htm .= "     <td ".$stl." colspan=\"2\">OBSERVACIÓN</td>\n";
      $htm .= "   </tr>\n"; 
      $htm .= "   <tr class=\"modulo_list_claro\">\n";
      $htm .= "     <td colspan=\"2\">\n";
      $htm .= "       <textarea name=\"observacion\" style=\"width:100%\" rows=\"2\" class=\"textarea\"></textarea>\n";
      $htm .= "     </td>\n";
      $htm .= "   </tr>\n";      
      $htm .= "   <tr class=\"modulo_list_claro\">\n";
      $htm .= "     <td colspan=\"2\" align=\"center\">\n";
      $htm .= "      <input type=\"hidden\" name=\"area_id\">\n";
      $htm .= "      <input type=\"hidden\" name=\"actualizar\" value=\"0\">\n";
      $htm .= "      <input type=\"hidden\" name=\"ingreso\" value=\"".$ingreso."\">\n";
      $htm .= "      <input type=\"hidden\" name=\"evolucion_id\" value=\"".$evolucion."\">\n";
      $htm .= "      <div id=\"error\" class=\"label_error\"></div>\n";
			$htm .= "      <table width=\"100%\" align=\"center\" >\n";
			$htm .= "	      <tr>\n";
      $htm .= "		      <td align=\"center\">\n";
      $htm .= "			      <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$htm .= "		      </td>";
			$htm .= "		      <td align=\"center\">\n";
			$htm .= "				    <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan('Contenedor')\">\n";
			$htm .= "		      </td>";			
      $htm .= "		      <td align=\"center\">\n";
      $htm .= "		        <div id=\"eliminar_sector\" style=\"display:none\">\n";
			$htm .= "				      <input class=\"input-submit\" type=\"button\" name=\"eliminar\" value=\"Eliminar Sector\" onclick=\"EliminarSector(document.clasificacion_faneras)\">\n";
			$htm .= "		        </div>\n";
			$htm .= "		      </td>";
			$htm .= "	      </tr>";
			$htm .= "      </table>";
      $htm .= "     </td>\n";
      $htm .= "   </tr>\n";

      $htm .= " </table><br>\n";
      $htm .= "</form><br>\n";
      
      $html .= $this->CrearVentana(370,350,$htm);
      return $html;
    }
    /**
		* Funcion donde se crea una forma con una ventana con capas para mostrar informacion
    * en pantalle
    *
    * @param int $tmn Tamaño que tendra la ventana
    *
    * @return string
		*/
		function CrearVentana($tmn = 370, $tmny = "'auto'",$contenido)
		{
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 4;\n";
			$html .= "	function OcultarSpan(capa)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById(capa);\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan(capa)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById(capa);\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		  Iniciar();\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";			
      
      $html .= "	function MostrarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xShow(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function OcultarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xHide(Seccion);\n";
			$html .= "	}\n";

			$html .= "	function Iniciar()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Contenedor';\n";
			$html .= "		titulo = 'titulo';\n";
      $html .= "		ele = xGetElementById('Contenido');\n";
			$html .= "	  xResizeTo(ele,".$tmn.", ".$tmny.");\n";	
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,".$tmn.", ".$tmny.");\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,".($tmn - 20).", 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrar');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,".($tmn - 20).", 0);\n";
			$html .= "	}\n";
      
			$html .= "	function myOnDragStart(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	  window.status = '';\n";
			$html .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "	  else xZIndex(ele, hiZ++);\n";
			$html .= "	  ele.myTotalMX = 0;\n";
			$html .= "	  ele.myTotalMY = 0;\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag(ele, mdx, mdy)\n";
			$html .= "	{\n";
			$html .= "	  if (ele.id == titulo) {\n";
			$html .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "	  }\n";
			$html .= "	  else {\n";
			$html .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "	  }  \n";
			$html .= "	  ele.myTotalMX += mdx;\n";
			$html .= "	  ele.myTotalMY += mdy;\n";
			$html .= "	}\n";
			$html .= "	function myOnDragEnd(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center;\"></div>\n";
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content'>\n";
			$html .= "	".$contenido;
			$html .= "	</div>\n";
			$html .= "</div>\n";
			return $html;
		}
    /**
    *
    */
    function FormaHistoria($coordenadas,$sensibilidad,$sectores,$puntajes,$puntajeEvolucion)
    {     
      $stl = " style=\"position:relative;border:0px;z-index:1;\" ";
      
      $html .= "<center>\n";
      $html .= "<div id=\"capa_faneras\" ".$stl.">\n";
      $html .= "  <table align=\"left\" border=\"1\" rules=\"cols\" width=\"100%\">\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td align=\"center\" colspan=\"2\">PIEL Y FANERAS</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr >\n";
      $html .= "      <td width=\"530\" rowspan=\"2\">\n";
      $html .= "        <table align=\"center\" border=\"1\" rules=\"all\" width=\"540\">\n";
      $html .= "          <tr>\n";
      $html .= "            <td height=\"700\" width=\"530\">\n";
      $html .= "              <div id=\"cuerpo\" style=\"position:absolute;width:540px;height:706px;top:0px;left:0px;z-index:3\">\n";
      $html .= "              <img src=\"images/piel_faneras.png\" width=\"540\" height=\"706\" border=\"0\" />\n";

      $scpt  = "   var puntos = new Array();\n"; 
      foreach($coordenadas as $key => $dtl)
      {
        $mapa = str_replace("(","",$dtl['datos_coordenadas']);
        $mapa = str_replace(")","",$mapa);
        $scpt .= "   puntos[".$key."] = new Array(".$mapa.");\n"; 
      }

      $html .= "            </div>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "    <td valign=\"top\" >\n";
      $html .= "      <table align=\"left\" width=\"300\" border=\"1\" rules=\"all\">\n";
      $html .= "        <tr class=\"label\">\n";
      $html .= "          <td colspan=\"3\">SENSIBILIDAD</td>\n";
      $html .= "        </tr>\n";
      $scpt .= "   var colores = new Array();\n"; 
      foreach($sensibilidad as $key => $dt)
      {
        $html .= "        <tr>\n";
        $html .= "          <td width=\"50%\" class=\"normal_10AN\">".$dt['sensibiliad_descripcion']."</td>\n";
        $html .= "          <td width=\"50%\" style=\"background:".$dt['color_nombre1'].";height:26px\">&nbsp;</td>\n";
        $html .= "        </tr>\n";
        $scpt .= "   colores[".$dt['piel_fanera_color_id']."] = '".$dt['color_nombre1']."';\n"; 
      }
      
      $html .= "        </table>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";      
      $html .= "    <tr>\n";      
      $html .= "      <td valign=\"top\">\n";
      $html .= "        <table align=\"left\" border=\"1\" rules=\"all\" width=\"300\" >\n";
      $html .= "          <tr class=\"label\">\n";
      $html .= "            <td colspan=\"2\">DOLOR</td>\n";
      $html .= "          </tr>\n";      
      $html .= "          <tr class =\"normal_10AN\">\n";
      $html .= "            <td class=\"label\" width=\"50%\">PUNTAJE</td>\n";
      $html .= "            <td>".$puntajeEvolucion['puntaje_eva']."</td>\n";
      $html .= "          </tr>\n";
      $html .= "          <tr>\n";
      $html .= "            <td colspan=\"2\" height=\"496\" align=\"center\" valign=\"top\">\n";
      $html .= "            </td>\n";
      $html .= "          </tr>\n";
      $html .= "        </table>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table><br>\n";
      if(!empty($sectores))
      {
        $html .= "  <table align=\"left\" width=\"100%\" border=\"1\" rules=\"all\">\n";
        $html .= "    <tr class=\"label\" align=\"center\">\n";
        $html .= "      <td width=\"1%\"></td>\n";
        $html .= "      <td >SENSIBILIDAD</td>\n";
        $html .= "      <td width=\"10%\">INTERMITENTE</td>\n";
        $html .= "      <td width=\"10%\">PERSISTENTE</td>\n";
        $html .= "      <td >QUE LO AUMENTA</td>\n";
        $html .= "      <td >QUE LO DISMINUYE</td>\n";
        $html .= "      <td width=\"10%\">IRRADIADO</td>\n";
        $html .= "      <td width=\"10%\">REFERIDO</td>\n";
        $html .= "      <td>OBSERVACION</td>\n";
        $html .= "    </tr>\n";
        foreach($sectores as $key => $dtl)
        {
          $html .= "    <tr class=\"label\">\n";
          $html .= "      <td>".$dtl['franja']."</td>\n";
          $html .= "      <td>".$dtl['sensibiliad_descripcion']."</td>\n";
          $html .= "      <td align=\"center\">".(($dtl['persistencia'] == '2')? "X":"&nbsp;")."</td>\n";
          $html .= "      <td align=\"center\">".(($dtl['persistencia'] == '1')? "X":"&nbsp;")."</td>\n";
          $html .= "      <td>".$dtl['aumento_descripcion']."&nbsp;</td>\n";
          $html .= "      <td>".$dtl['disminucion_descripcion']."&nbsp;</td>\n";
          $html .= "      <td align=\"center\">".(($dtl['referencia'] == '1')? "X":"&nbsp;")."</td>\n";
          $html .= "      <td align=\"center\">".(($dtl['referencia'] == '2')? "X":"&nbsp;")."</td>\n";
          $html .= "      <td>".$dtl['observacion']."&nbsp;</td>\n";
          $html .= "    </tr>\n";
        }
        $html .= "  </table>\n";
      }
      $html .= "</div>\n";
      $html .= "</center>\n";
      $html .= "<script>\n";
      $html .= $scpt;
      $html .= "  var ingresado = new Array();\n";
      $html .= "  var circulos = new Array();\n";
      $html .= "  var gr = new jsGraphics(document.getElementById('capa_faneras'));\n";
      $html .= "  var valores = new Array();\n";
      foreach($puntajes as $key => $dtl)
        $html .= "    valores[".$dtl['puntaje_eva']."] = '".$dtl['equivalencia_eva']."';\n";

      $html .= "  function MostrarIngresoDatos(area)\n";
      $html .= "  {\n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      objeto = document.clasificacion_faneras;\n";
      $html .= "      document.getElementById('eliminar_sector').style.display = 'none';\n";
      $html .= "      LimpiarCampos(objeto);\n";
      $html .= "      if(ingresado[area] != undefined)\n";
      $html .= "      {\n";
      $html .= "        objeto.sensibilidad.value = ingresado[area][0];";
      $html .= "        objeto.persistencia[ingresado[area][1]].checked = true;";
      $html .= "        objeto.aumento.value = ingresado[area][2];";
      $html .= "        objeto.disminucion.value = ingresado[area][3];";
      $html .= "        objeto.referencia[ingresado[area][4]].checked = true;";
      $html .= "        objeto.observacion.value = ingresado[area][5];";
      $html .= "        objeto.actualizar.value = '1';";
      $html .= "        document.getElementById('eliminar_sector').style.display = 'block';\n";
      $html .= "      }\n";
      $html .= "      else\n";
      $html .= "        objeto.actualizar.value = '0';";
      $html .= "    }\n";
      $html .= "    catch(error){}\n";
      $html .= "    document.clasificacion_faneras.area_id.value = area;\n";
      $html .= "    MostrarSpan('Contenedor');\n";
      $html .= "  }\n";      
      $html .= "  function CrearSector(objeto)\n";
      $html .= "  {\n";
      $html .= "    var arr = objeto.area_id.value;\n";
      $html .= "    ingresado[arr] = new Array();\n";
      $html .= "    ingresado[arr][0] = objeto.sensibilidad.value;\n";
      $html .= "    ingresado[arr][1] = (objeto.persistencia[0].checked)? 0: 1;\n";
      $html .= "    ingresado[arr][2] = objeto.aumento.value;\n";
      $html .= "    ingresado[arr][3] = objeto.disminucion.value;\n";
      $html .= "    ingresado[arr][4] = (objeto.referencia[0].checked)? 0: 1;\n";
      $html .= "    ingresado[arr][5] = objeto.observacion.value;\n";
      $html .= "    Dibujar(arr,ingresado[arr][0]);\n";
      $html .= "    document.getElementById('error').innerHTML = '';\n";
      $html .= "    OcultarSpan('Contenedor');\n";
      $html .= "  }\n";
      $html .= "  function Dibujar(area,sns)\n";
      $html .= "  {\n";
      $html .= "    var col = new jsColor(colores[sns]);\n";
      $html .= "    var pen = new jsPen(col,2);\n";
      $html .= "    coords = new Array();\n";
      $html .= "    var j = 0;\n";
      $html .= "    for(i=0; i<puntos[area].length;i++)\n";
      $html .= "    {\n";
      $html .= "      coords[j] = new jsPoint(puntos[area][i++],puntos[area][i]);\n";
      $html .= "      j++;\n";
      $html .= "    }\n";
      $html .= "    gr.fillPolygon(col,coords);\n";
      $html .= "  }\n";     
      
      $html .= "  function DibujarEscala()\n";
      $html .= "  {\n";
      $html .= "    var col = new jsColor('#330066');\n";
      $html .= "    var pen = new jsPen(col,2);\n";
      $html .= "    gr.drawLine(pen, new jsPoint(560,300), new jsPoint(820,300));\n";
      $html .= "    x = 560; y1= 285; y2 = 315;i=0;\n";     
      $html .= "    do\n";     
      $html .= "    {\n";    
      $html .= "      circulos[i] = new Array();\n";    
      $html .= "      circulos[i][0] = x;\n";    
      $html .= "      circulos[i++][1] = y1+15;\n";    
      $html .= "      gr.drawLine(pen, new jsPoint(x,y1), new jsPoint(x,y2));\n";   
      $html .= "      x += 52;\n";
      $html .= "    }\n";      
      $html .= "    while(x <= 850);\n";
      $html .= "    var fnt = new jsFont('sans serif','bold','10pt');\n";
      $html .= "    gr.drawText('0', new jsPoint(558,265),fnt);\n";
      $html .= "    gr.drawText('10',new jsPoint(810,265),fnt);\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(570,335), 15)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(563,332), 2)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(577,332), 2)\n";
      $html .= "    gr.drawArc(pen,new jsPoint(570,325) , 30, 40, 60, 60)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(810,335), 15)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(803,332), 2)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(817,332), 2)\n";
      $html .= "    gr.drawArc(pen, new jsPoint(810,360), 30, 40, 240, 60)\n";
      $html .= "  }\n";

      $html .= "  function SeleccionarEscala(valor)\n";
      $html .= "  {\n";
      $html .= "    var col1 = new jsColor('#FF0000');\n";
      $html .= "    var pen1 = new jsPen(col1,2);\n";
      $html .= "    elegido = valores[valor];\n";
      $html .= "    var j = 0;\n";
      $html .= "    for(i=0; i<=5; i++)\n";
      $html .= "    {\n";
      $html .= "      if(i == elegido)\n";
      $html .= "      {\n";
      $html .= "        gr.drawCircle(pen1,new jsPoint(circulos[i][0],circulos[i][1]), 8);\n";
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "  }\n"; 
      
      foreach($sectores as $key => $dtl)
      {
        $html .= "  Dibujar('".$dtl['coordenada_id']."','".$dtl['piel_fanera_color_id']."');";
        $html .= "  ingresado[".$key."] = new Array();\n";
        $html .= "  ingresado[".$key."][0] = ".$dtl['piel_fanera_color_id'].";\n";
        $html .= "  ingresado[".$key."][1] = ".(($dtl['persistencia'])? "0": "1")."\n";
        $html .= "  ingresado[".$key."][2] = '".$dtl['aumento_descripcion']."';\n";
        $html .= "  ingresado[".$key."][3] = '".$dtl['disminucion_descripcion']."';\n";
        $html .= "  ingresado[".$key."][4] = ".(($dtl['persistencia'])? "0": "1")."\n";
        $html .= "  ingresado[".$key."][5] = '".$dtl['observacion']."';\n";
      }
      $html .= "  DibujarEscala();\n";
      if($puntajeEvolucion['puntaje_eva'])
        $html .= "  SeleccionarEscala('".$puntajeEvolucion['puntaje_eva']."');";
        
      $html .= "</script>\n";
      
      return $html;
    }
    /**
    *
    */
    function FormaHistoriaModulo($coordenadas,$sensibilidad,$sectores,$puntajes,$puntajeEvolucion)
    {     
      $stl = " style=\"position:relative;border:0px;z-index:1;\" ";
      
      $html .= "<center>\n";
      $html .= "<div id=\"capa_faneras\" ".$stl.">\n";
      $html .= "  <table align=\"left\" class=\"modulo_table_list\" width=\"100%\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td align=\"center\" colspan=\"2\">PIEL Y FANERAS</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr >\n";
      $html .= "      <td width=\"530\" rowspan=\"2\">\n";
      $html .= "        <table align=\"center\" class=\"modulo_table_list\" width=\"540\">\n";
      $html .= "          <tr>\n";
      $html .= "            <td height=\"700\" width=\"530\">\n";
      $html .= "              <div id=\"cuerpo\" style=\"position:absolute;width:540px;height:706px;top:0px;left:0px;z-index:3\">\n";
      $html .= "              <img src=\"images/piel_faneras.png\" width=\"540\" height=\"706\" border=\"0\" />\n";

      $scpt  = "   var puntos = new Array();\n"; 
      foreach($coordenadas as $key => $dtl)
      {
        $mapa = str_replace("(","",$dtl['datos_coordenadas']);
        $mapa = str_replace(")","",$mapa);
        $scpt .= "   puntos[".$key."] = new Array(".$mapa.");\n"; 
      }

      $html .= "            </div>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "    <td valign=\"top\" >\n";
      $html .= "      <table align=\"left\" width=\"300\" border=\"1\" rules=\"all\">\n";
      $html .= "        <tr class=\"formulacion_table_list\">\n";
      $html .= "          <td colspan=\"3\">SENSIBILIDAD</td>\n";
      $html .= "        </tr>\n";
      $scpt .= "   var colores = new Array();\n"; 
      foreach($sensibilidad as $key => $dt)
      {
        $html .= "        <tr class=\"modulo_list_claro\">\n";
        $html .= "          <td width=\"50%\" class=\"normal_10AN\">".$dt['sensibiliad_descripcion']."</td>\n";
        $html .= "          <td width=\"50%\" style=\"background:".$dt['color_nombre1'].";height:26px\">&nbsp;</td>\n";
        $html .= "        </tr>\n";
        $scpt .= "   colores[".$dt['piel_fanera_color_id']."] = '".$dt['color_nombre1']."';\n"; 
      }
      
      $html .= "        </table>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";      
      $html .= "    <tr>\n";      
      $html .= "      <td valign=\"top\">\n";
      $html .= "        <table align=\"left\" class=\"modulo_table_list\" width=\"300\" >\n";
      $html .= "          <tr class=\"formulacion_table_list\">\n";
      $html .= "            <td colspan=\"2\">DOLOR</td>\n";
      $html .= "          </tr>\n";      
      $html .= "          <tr class =\"modulo_list_claro\">\n";
      $html .= "            <td width=\"50%\" class=\"formulacion_table_list\">PUNTAJE</td>\n";
      $html .= "            <td>".$puntajeEvolucion['puntaje_eva']."</td>\n";
      $html .= "          </tr>\n";
      $html .= "          <tr class=\"modulo_list_claro\">\n";
      $html .= "            <td colspan=\"2\" height=\"496\" align=\"center\" valign=\"top\">\n";
      $html .= "            </td>\n";
      $html .= "          </tr>\n";
      $html .= "        </table>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"2\">\n";

      if(!empty($sectores))
      {
        $html .= "  <table align=\"left\" width=\"100%\" class=\"modulo_table_list\">\n";
        $html .= "    <tr class=\"formulacion_table_list\" align=\"center\">\n";
        $html .= "      <td width=\"1%\"></td>\n";
        $html .= "      <td >SENSIBILIDAD</td>\n";
        $html .= "      <td width=\"10%\">INTERMITENTE</td>\n";
        $html .= "      <td width=\"10%\">PERSISTENTE</td>\n";
        $html .= "      <td >QUE LO AUMENTA</td>\n";
        $html .= "      <td >QUE LO DISMINUYE</td>\n";
        $html .= "      <td width=\"10%\">IRRADIADO</td>\n";
        $html .= "      <td width=\"10%\">REFERIDO</td>\n";
        $html .= "      <td>OBSERVACION</td>\n";
        $html .= "    </tr>\n";
        foreach($sectores as $key => $dtl)
        {
          $html .= "    <tr class=\"modulo_list_claro\">\n";
          $html .= "      <td>".$dtl['franja']."</td>\n";
          $html .= "      <td>".$dtl['sensibiliad_descripcion']."</td>\n";
          $html .= "      <td align=\"center\">".(($dtl['persistencia'] == '2')? "X":"&nbsp;")."</td>\n";
          $html .= "      <td align=\"center\">".(($dtl['persistencia'] == '1')? "X":"&nbsp;")."</td>\n";
          $html .= "      <td>".$dtl['aumento_descripcion']."&nbsp;</td>\n";
          $html .= "      <td>".$dtl['disminucion_descripcion']."&nbsp;</td>\n";
          $html .= "      <td align=\"center\">".(($dtl['referencia'] == '1')? "X":"&nbsp;")."</td>\n";
          $html .= "      <td align=\"center\">".(($dtl['referencia'] == '2')? "X":"&nbsp;")."</td>\n";
          $html .= "      <td>".$dtl['observacion']."&nbsp;</td>\n";
          $html .= "    </tr>\n";
        }
        $html .= "  </table>\n";
      }
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table><br>\n";
      $html .= "</div>\n";
      $html .= "</center>\n";
      $html .= "<script>\n";
      $html .= $scpt;
      $html .= "  var ingresado = new Array();\n";
      $html .= "  var circulos = new Array();\n";
      $html .= "  var gr = new jsGraphics(document.getElementById('capa_faneras'));\n";
      $html .= "  var valores = new Array();\n";
      foreach($puntajes as $key => $dtl)
        $html .= "    valores[".$dtl['puntaje_eva']."] = '".$dtl['equivalencia_eva']."';\n";

      $html .= "  function MostrarIngresoDatos(area)\n";
      $html .= "  {\n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      objeto = document.clasificacion_faneras;\n";
      $html .= "      document.getElementById('eliminar_sector').style.display = 'none';\n";
      $html .= "      LimpiarCampos(objeto);\n";
      $html .= "      if(ingresado[area] != undefined)\n";
      $html .= "      {\n";
      $html .= "        objeto.sensibilidad.value = ingresado[area][0];";
      $html .= "        objeto.persistencia[ingresado[area][1]].checked = true;";
      $html .= "        objeto.aumento.value = ingresado[area][2];";
      $html .= "        objeto.disminucion.value = ingresado[area][3];";
      $html .= "        objeto.referencia[ingresado[area][4]].checked = true;";
      $html .= "        objeto.observacion.value = ingresado[area][5];";
      $html .= "        objeto.actualizar.value = '1';";
      $html .= "        document.getElementById('eliminar_sector').style.display = 'block';\n";
      $html .= "      }\n";
      $html .= "      else\n";
      $html .= "        objeto.actualizar.value = '0';";
      $html .= "    }\n";
      $html .= "    catch(error){}\n";
      $html .= "    document.clasificacion_faneras.area_id.value = area;\n";
      $html .= "    MostrarSpan('Contenedor');\n";
      $html .= "  }\n";      
      $html .= "  function CrearSector(objeto)\n";
      $html .= "  {\n";
      $html .= "    var arr = objeto.area_id.value;\n";
      $html .= "    ingresado[arr] = new Array();\n";
      $html .= "    ingresado[arr][0] = objeto.sensibilidad.value;\n";
      $html .= "    ingresado[arr][1] = (objeto.persistencia[0].checked)? 0: 1;\n";
      $html .= "    ingresado[arr][2] = objeto.aumento.value;\n";
      $html .= "    ingresado[arr][3] = objeto.disminucion.value;\n";
      $html .= "    ingresado[arr][4] = (objeto.referencia[0].checked)? 0: 1;\n";
      $html .= "    ingresado[arr][5] = objeto.observacion.value;\n";
      $html .= "    Dibujar(arr,ingresado[arr][0]);\n";
      $html .= "    document.getElementById('error').innerHTML = '';\n";
      $html .= "    OcultarSpan('Contenedor');\n";
      $html .= "  }\n";
      $html .= "  function Dibujar(area,sns)\n";
      $html .= "  {\n";
      $html .= "    var col = new jsColor(colores[sns]);\n";
      $html .= "    var pen = new jsPen(col,2);\n";
      $html .= "    coords = new Array();\n";
      $html .= "    var j = 0;\n";
      $html .= "    for(i=0; i<puntos[area].length;i++)\n";
      $html .= "    {\n";
      $html .= "      coords[j] = new jsPoint(puntos[area][i++],puntos[area][i]);\n";
      $html .= "      j++;\n";
      $html .= "    }\n";
      $html .= "    gr.fillPolygon(col,coords);\n";
      $html .= "  }\n";     
      
      $html .= "  function DibujarEscala()\n";
      $html .= "  {\n";
      $html .= "    var col = new jsColor('#330066');\n";
      $html .= "    var pen = new jsPen(col,2);\n";
      $html .= "    gr.drawLine(pen, new jsPoint(560,300), new jsPoint(820,300));\n";
      $html .= "    x = 560; y1= 285; y2 = 315;i=0;\n";     
      $html .= "    do\n";     
      $html .= "    {\n";    
      $html .= "      circulos[i] = new Array();\n";    
      $html .= "      circulos[i][0] = x;\n";    
      $html .= "      circulos[i++][1] = y1+15;\n";    
      $html .= "      gr.drawLine(pen, new jsPoint(x,y1), new jsPoint(x,y2));\n";   
      $html .= "      x += 52;\n";
      $html .= "    }\n";      
      $html .= "    while(x <= 850);\n";
      $html .= "    var fnt = new jsFont('sans serif','bold','10pt');\n";
      $html .= "    gr.drawText('0', new jsPoint(558,265),fnt);\n";
      $html .= "    gr.drawText('10',new jsPoint(810,265),fnt);\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(570,335), 15)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(563,332), 2)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(577,332), 2)\n";
      $html .= "    gr.drawArc(pen,new jsPoint(570,325) , 30, 40, 60, 60)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(810,335), 15)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(803,332), 2)\n";
      $html .= "    gr.drawCircle(pen,new jsPoint(817,332), 2)\n";
      $html .= "    gr.drawArc(pen, new jsPoint(810,360), 30, 40, 240, 60)\n";
      $html .= "  }\n";

      $html .= "  function SeleccionarEscala(valor)\n";
      $html .= "  {\n";
      $html .= "    var col1 = new jsColor('#FF0000');\n";
      $html .= "    var pen1 = new jsPen(col1,2);\n";
      $html .= "    elegido = valores[valor];\n";
      $html .= "    var j = 0;\n";
      $html .= "    for(i=0; i<=5; i++)\n";
      $html .= "    {\n";
      $html .= "      if(i == elegido)\n";
      $html .= "      {\n";
      $html .= "        gr.drawCircle(pen1,new jsPoint(circulos[i][0],circulos[i][1]), 8);\n";
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "  }\n"; 
      
      foreach($sectores as $key => $dtl)
      {
        $html .= "  Dibujar('".$dtl['coordenada_id']."','".$dtl['piel_fanera_color_id']."');";
        $html .= "  ingresado[".$key."] = new Array();\n";
        $html .= "  ingresado[".$key."][0] = ".$dtl['piel_fanera_color_id'].";\n";
        $html .= "  ingresado[".$key."][1] = ".(($dtl['persistencia'])? "0": "1")."\n";
        $html .= "  ingresado[".$key."][2] = '".$dtl['aumento_descripcion']."';\n";
        $html .= "  ingresado[".$key."][3] = '".$dtl['disminucion_descripcion']."';\n";
        $html .= "  ingresado[".$key."][4] = ".(($dtl['persistencia'])? "0": "1")."\n";
        $html .= "  ingresado[".$key."][5] = '".$dtl['observacion']."';\n";
      }
      $html .= "  DibujarEscala();\n";
      if($puntajeEvolucion['puntaje_eva'])
        $html .= "  SeleccionarEscala('".$puntajeEvolucion['puntaje_eva']."');";
        
      $html .= "</script>\n";
      
      return $html;
    }
  }
?>