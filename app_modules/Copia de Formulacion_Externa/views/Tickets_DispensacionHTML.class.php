<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: Tickets_DispensacionHTML.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: Tickets_DispensacionHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class Tickets_DispensacionHTML	
	{
    /**
    * Constructor de la clase
    */
    function Tickets_DispensacionHTML(){}
    /**
    *
    * @return string
    */
    function Forma($action,$request,$lista,$Tipo_Id_paciente,$conteo, $pagina,$bodegas_doc_id)
    {
          $ctl = AutoCarga::factory("ClaseUtil");
       
          $html  = $ctl->LimpiarCampos();
          $html .= $ctl->RollOverFilas();
          $html .= $ctl->AcceptDate('/');
        $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
          $select = "<select style=\"width:40%\" name=\"buscador[tipo_id_paciente]\" id=\"tipo_id_paciente\" class=\"select\">";
          $select .= " <option value=\"\">--- TODOS ---</option>";
          foreach($Tipo_Id_paciente as $key=>$valor)
          {
                if($_REQUEST['buscador']['tipo_id_paciente']==$valor['tipo_id_paciente'])
                {
                    $selected =" selected ";
                }
              else
              {
                      $selected =" ";
              }
              $select .= "<option $selected value=\"".$valor['tipo_id_paciente']."\">".$valor['tipo_id_paciente']."-".$valor['descripcion']."</option>";
          }
          $select .= "</select>";
          $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:center\"";
        
          $html .= ThemeAbrirTabla('TICKETS DE DISPENSACION');
          $html .= "<form name=\"productos\" action=\"".$action['buscar']."\" method=\"post\">\n";
          $html .= "  <table width=\"65%\" align=\"center\">\n";
          $html .= "    <tr>\n";
          $html .= "      <td>\n";
          $html .= "	      <fieldset class=\"fieldset\">\n";
          $html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
          $html .= "		      <table width=\"100%\" class=\"label\" $style>\n";
          $html .= "            <tr>";
          $html .= "              <td class=\"formulacion_table_list\"\">No- FORMULA</td>\n";
          $html .= "                <td align=\"left\" ><input type=\"text\" name=\"buscador[formula_papel]\" id=\"formula_papel\" class=\"input-text\" value=\"".$request['formula_papel']."\" style=\"width:60%\"></td>\n";
          $html .= "            </tr>";
          $html .= "            <tr>";
          $html .= "              <td class=\"formulacion_table_list\">IDENTIFICACION DEL PACIENTE</td>\n";
          $html .= "                <td align=\"left\">".$select."<input type=\"text\" name=\"buscador[paciente_id]\" id=\"paciente_id\" class=\"input-text\" value=\"".$request['paciente_id']."\" style=\"width:60%\"></td>\n";
          $html .= "            </tr>";
          $html .= "            <tr>";
          $html .= "              <td class=\"formulacion_table_list\">NOMBRE DEL PACIENTE</td>\n";
          $html .= "                <td align=\"left\" ><input type=\"text\" name=\"buscador[nombre_paciente]\" id=\"nombre_paciente\" class=\"input-text\" value=\"".$request['nombre_paciente']."\" style=\"width:60%\"></td>\n";
          $html .= "            </tr>";
          $html .= "			      <tr>\n";
          
          if(!empty($bodegas_doc_id))
          {
              $html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
              $html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
              $html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.productos)\">\n";
              $html .= "				      </td>\n";
           }else
           {
                  $html .= "				      <td class=\"label_error\" align=\"center\" colspan=\"3\">\n";
                  $html .= "				      NO EXISTE UN DOCUMENTO PARAMETRIZADO PARA REALIZAR LA DISPENSACION </td>\n";


           }
         $html .= "			      </tr>\n";
          $html .= "		      </table>\n";
          $html .= "	      </fieldset>\n";
          $html .= "	    </td>\n";
          $html .= "	  </tr>\n";
          $html .= "	</table>\n";
          $html .= "</form>\n";
          if(!empty($lista))
          {
        
              $html .= "	<table align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">\n";
              $html .= "		<tr class=\"formulacion_table_list\" >\n";
              $html .= "			<td width=\"5%\">#FORMULA</td>\n";
              $html .= "			<td width=\"5%\">FECHA FORMULA</td>\n";
              $html .= "			<td width=\"15%\">PACIENTE</td>\n";
              $html .= "			<td width=\"4%\">TICKET DISPENSADOS</td>\n";
              $html .= "			<td width=\"4%\">  TICKET PENDIENTES</td>\n";
               $html .= "			<td width=\"2%\"> PENDIENTES</td>\n";
              
              $html .= "		</tr>\n";
              $reporte = new GetReports();
              foreach($lista as $k1 => $dtl)
              {
                        $mostrar = $reporte->GetJavaReport('app','Formulacion_Externa','MedicamentoDispensadosFormulacionExterna',
                        array("formula_id"=>$dtl['formula_id'],"paciente_id"=>$dtl['paciente_id'],"opc"=>"1"),
                        array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                        $funcion = $reporte->GetJavaFunction();

                        $mostrar_ = $reporte->GetJavaReport('app','Formulacion_Externa','MedicamentoPendienteFormulacionExterna',
                        array("formula_id"=>$dtl['formula_id']),
                        array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                        $funcion2 = $reporte->GetJavaFunction();

                    	$pendientes=$obje->Medicamentos_Pendientes_Esm($dtl['formula_id']);
             
                    $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
                    $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";

                    $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
                    $html .= "			<td ><u><b>".$dtl['formula_papel']."</b></u></td>\n";
                    $html .= "			<td >".$dtl['fecha_formula']."</td>\n";
                    $html .= "			<td>(".$dtl['tipo_id_paciente']."-".$dtl['paciente_id'].")-".$dtl['nombre_paciente']."</td>\n";
                    $html .= "				<td align=\"center\" >\n";
                    $html .= "				".$mostrar."\n";
                    $html .= "					<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"TICKET DE DISPENSADOS\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' >\n";
                    $html .= "					</a></center>\n";
                    $html .= "			</td>\n";	
                    if(!empty($pendientes))
                    {
                          $html .= "				<td align=\"center\" >\n";
                          $html .= "				".$mostrar_."\n";
                          $html .= "					<a href=\"javascript:$funcion2\" class=\"label_error\"  title=\"PENDIENTES\"><img src=\"".GetThemePath()."/images/cargosin.png\" border='0' >\n";
                          $html .= "					</a></center>\n";
                          $html .= "			</td>\n";	

                          $html .= "      <td  align=\"center\" class=\"label_error\">\n";
                          $html .= "        <a href=\"".$action['pendiente'].URLRequest($dtl)."\">\n";
                          $html .= "          <img border=\"0\"  title=\"Pendientes\" src=\"".GetThemePath()."/images/pparamedin.png\">\n";
                          $html .= "        </a>\n";
                          $html .= "      </td>\n";
                    }else
                    {
                          $html .= "				<td align=\"center\" >\n";
                          $html .= "					<img src=\"".GetThemePath()."/images/cargos.png\" border='0' >\n";
                          $html .= "					</center>\n";
                          $html .= "			</td>\n";
                          
                          $html .= "      <td  align=\"center\" >\n";
                          $html .= "          <img border=\"0\"  title=\"Pendientes\" src=\"".GetThemePath()."/images/pparamed.png\">\n";
                          $html .= "      </td>\n";

                    
                    
                    }
                    }
                    $html .= "		</tr>\n";
        
                    $html .= "		</table>\n";
                    $html .= "		<br>\n";
                    $pgn = AutoCarga::factory("ClaseHTML");
                    $html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
      else if(!empty($request))
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
        $html .= "</center>\n";
      }
      $html .= "	<table width=\"90%\" align=\"center\">\n";
			$html .= "		<tr><td align=\"center\">\n";
			$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "			</form>\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
      $html .= ThemeCerrarTabla();

      return $html;
    }
         
         
        function CrearVentana($tmn,$Titulo)
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
      $html .= "    catch(error){alert(\"vaya\"+error)}\n";
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
      
      
      $html.= "function Cerrar(Elemento)\n";
           $html.= "{\n";
           $html.= "    capita = xGetElementById(Elemento);\n";
           $html.= "    capita.style.display = \"none\";\n";
           $html.= "}\n";
      
      $html .= "</script>\n";
      $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
      $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
      $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
      $html .= "  <div id='Contenido' class='d2Content'>\n";
      //En ese espacio se visualiza la informacion extraida de la base de datos.
      $html .= "  </div>\n";
      $html .= "</div>\n";

 
      return $html;
    }    
       
  }
?>