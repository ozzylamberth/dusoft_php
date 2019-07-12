<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: ModificarDatosAfiliadosHTML.class.php,v 1.5 2009/09/30 12:52:13 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ModificarDatosAfiliadosHTML
  * Clase encargada de crear las formas para mostrar la lista de los afiliados y 
  * los formularios para el cambio de informacion y los esatados y subestados de los mismos
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.5 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ModificarDatosAfiliadosHTML
  {
    /**
    * Constructor de la clase
    */
    function ModificarDatosAfiliadosHTML(){}
    /**
    * Funcion donde se crea la forma que muestra el buscador y la lista de los afiliados
    *
		* @param array $action Vector de links de la aplicaion
		* @param array $request Vector de datos del request
		* @param array $tipos_documento Vector con los tipos de documentos
		* @param array $estamentos Vector con los estamentos
    * @param array $tipos_afiliados Arreglo con los datos de los tipos de afiliados
    * @param array $estado Arreglo con los datos de los estados para hacer el retiro
    * @param array $afiliados Vector con los datos de los afiliados encontrado, segun los criterios de busqueda
    * @param int   $pagina Numero de la pagina que se esta visualizando
    * @param int   $conteo Numero total de registros encontrado (Se usa para el paginador)
    * @param string $msgError Mensaje de error, si lo hay 
    *
    * @return String $html
    */
    function FormaBuscadorAfiliados($action,$request,$tipos_documento,$estamentos,$tipos_afiliados,$estado,$afiliados = array(),$pagina,$conteo,$msgError)
    {
      $html .= ThemeAbrirTabla('BUSCAR AFILIADO');
      $html .= "<script>\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "  {\n";
			$html .= "    var nav4 = window.Event ? true : false;\n";
			$html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "    return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
			$html .= "  }\n";
			$html .= "	function LimpiarCampos(frm)\n";
			$html .= "	{\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'text': frm[i].value = ''; break;\n";
			$html .= "				case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		Habilitar(1);\n";
			$html .= "	}\n";
			$html .= "	function mOvr(src,clrOver)\n";
			$html .= "	{\n";
			$html .= "		src.style.background = clrOver;\n";
			$html .= "	}\n";			
			$html .= "	function mOut(src,clrIn)\n";
			$html .= "	{\n";
			$html .= "		src.style.background = clrIn;\n";
			$html .= "	}\n";
      $html .= "	function Habilitar(valor)\n";
			$html .= "	{\n";
			$html .= "		if(valor == '6')\n";
			$html .= "		  document.getElementById('edad_maxima').style.display = 'block'\n";
			$html .= "		else\n";
			$html .= "		  document.getElementById('edad_maxima').style.display = 'none'\n";
			$html .= "	}\n";
      $html .= "	function SeleccionarCheckBox(frm,valor)\n";
			$html .= "	{\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'checkbox': \n";
      $html .= "          frm[i].checked = valor; \n";
      $html .= "        break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "	}\n";
			$html .= "	function Continuar(frm)\n";
			$html .= "	{\n";
			$html .= "		frm.submit();\n";
			$html .= "	}\n";						
      $html .= "	function EvaluarDatos(frm)\n";
			$html .= "	{\n";
			$html .= "	  flag = false\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'checkbox': \n";
      $html .= "          if(frm[i].checked == true) \n";
      $html .= "          {\n";
      $html .= "            flag = true;\n";
      $html .= "            break;\n";
      $html .= "          }\n";
      $html .= "        break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(flag == false)\n";
			$html .= "		{\n";
			$html .= "		  document.getElementById('error').innerHTML = 'NO SE HA SELECCIONADO NINGUN AFILIADO PARA SER RETIRADO';\n";
			$html .= "		  return;\n";
			$html .= "		}\n";
			$html .= "		document.getElementById('error').innerHTML = '';\n";
			$html .= "		MostrarSpan();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<form name=\"formabuscar\" action=\"".$action['buscar']."\" method=\"post\">\n";
			$html .= "	<center>\n";
			$html .= "		<fieldset style=\"width:80%\" class=\"fieldset\"><legend class=\"label\">CRITERIOS DE BUSQUEDA</legend>\n";
			$html .= "    	<table border=\"0\" width=\"100%\" align=\"center\">\n";
			$html .= "        <tr>\n";
			$html .= "        	<td class=\"normal_10AN\" width=\"18%\">TIPO DOCUMENTO: </td>\n";
			$html .= "          <td width=\"32%\" colspan=\"2\">\n";
			$html .= "          	<select name=\"buscador[TipoDocumento]\" class=\"select\">\n";
			$html .= "            	<option value=\"-1\">-------SELECCIONE-------</option>";
			$slt = "";
			foreach($tipos_documento as $key => $ids)
			{
				($request['TipoDocumento'] == $ids['tipo_id_paciente'])? $slt= "selected":$slt = "";
				$html .= "            	<option value=\"".$ids['tipo_id_paciente']."\" $slt>".$ids['descripcion']."</option>";
			}
			$html .= "            </select>\n";
			$html .= "          </td>\n";
			$html .= "          <td width=\"18%\" class=\"normal_10AN\">DOCUMENTO: </td>\n";
			$html .= "          <td>\n";
			$html .= "          	<input type=\"text\" class=\"input-text\" name=\"buscador[Documento]\" maxlength=\"32\" value=\"".$request['Documento']."\">\n";
			$html .= "          </td>\n";
			$html .= "				</tr>\n";
			$html .= "        <tr>\n";
			$html .= "        	<td class=\"normal_10AN\">NOMBRES:</td>\n";
			$html .= "          <td colspan=\"2\">\n";
			$html .= "          	<input type=\"text\" class=\"input-text\" name=\"buscador[Nombres]\" style=\"width:94%\" maxlength=\"64\" value=\"".$request['Nombres']."\">\n";
			$html .= "          </td>\n";
			$html .= "          <td class=\"normal_10AN\">APELLIDOS:</td>\n";
			$html .= "          <td>\n";
			$html .= "          	<input type=\"text\" class=\"input-text\" name=\"buscador[Apellidos]\" style=\"width:94%\" maxlength=\"64\" value=\"".$request['Apellidos']."\">\n";
			$html .= "          </td>\n";
			$html .= "        </tr>\n";
 			$html .= "        <tr>\n";
 			$html .= "        	<td class=\"normal_10AN\" width=\"18%\">ESTAMENTO: </td>\n";
			$html .= "          <td colspan=\"4\" >\n";
			$html .= "          	<select name=\"buscador[Estamento]\" class=\"select\">\n";
			$html .= "            	<option value=\"-1\">-------SELECCIONE-------</option>";
			$slt = "";
			foreach($estamentos as $key => $ids)
			{
				($request['Estamento'] == $ids['estamento_id'])? $slt= "selected":$slt = "";
				$html .= "            	<option value=\"".$ids['estamento_id']."\" $slt>".$ids['descripcion_estamento']."</option>";
			}
			$html .= "            </select>\n";
			$html .= "          </td>\n";
      
			$html .= "        </tr>\n"; 			
      $html .= "        <tr>\n";
 			$html .= "        	<td class=\"normal_10AN\" width=\"18%\">TIPO DE AFILIADO: </td>\n";
			$html .= "          <td colspan=\"4\" >\n";
			$html .= "          	<select name=\"buscador[tipo_afiliado]\" class=\"select\">\n";
			$html .= "            	<option value=\"-1\">-------SELECCIONE-------</option>\n";
			$slt = "";
			foreach($tipos_afiliados as $key => $ids)
			{
				($request['tipo_afiliado'] == $ids['eps_tipo_afiliado_id'])? $slt= "selected":$slt = "";
				$html .= "            	<option value=\"".$ids['eps_tipo_afiliado_id']."\" $slt>".$ids['descripcion_eps_tipo_afiliado']."</option>\n";
			}
			$html .= "            </select>\n";
			$html .= "          </td>\n"; 
			$html .= "        </tr>\n";
      
      $sl1[$request['edad_signo']] = "selected";
      $html .= "        <tr>\n";
 			$html .= "        	<td class=\"normal_10AN\" >EDAD: </td>\n";
			$html .= "          <td class=\"normal_10AN\" width=\"18%\">\n";
			$html .= "          	<select name=\"buscador[edad_signo]\" class=\"select\" onChange=\"Habilitar(this.value)\">\n";
      $html .= "            	<option value=\"1\" ".$sl1[1]."> = </option>\n";
      $html .= "            	<option value=\"2\" ".$sl1[2]."> > </option>\n";
      $html .= "            	<option value=\"3\" ".$sl1[3]."> >=</option>\n";
      $html .= "            	<option value=\"4\" ".$sl1[4]."> < </option>\n";      
      $html .= "            	<option value=\"5\" ".$sl1[5]."> <=</option>\n";
      $html .= "            	<option value=\"6\" ".$sl1[6].">entre</option>\n";
			$html .= "            </select>\n";
 			$html .= "          	<input type=\"text\" class=\"input-text\" name=\"buscador[edad]\" style=\"width:50%\" onkeypress=\"return acceptNum(event)\" value=\"".$request['edad']."\">\n";
 			$html .= "          </td>\n";
 			$html .= "          <td  class=\"normal_10AN\">\n";
      $html .= "            <div id=\"edad_maxima\" style=\"display:".(($sl1[6] != "")? "block":"none")."\">\n";
      $html .= "          	   Y <input type=\"text\" class=\"input-text\" name=\"buscador[edad_maxima]\" style=\"width:50%\" onkeypress=\"return acceptNum(event)\" value=\"".$request['edad_maxima']."\">\n";
			$html .= "            </div>\n";
			$html .= "          </td>\n";
 			$html .= "        	<td class=\"normal_10AN\" >VENCIMIENTO</td>\n";
			$html .= "          <td >\n";
      $sl1[$request['vencimiento']] = "selected";
			$html .= "          	<select name=\"buscador[vencimiento]\" class=\"select\">\n";
			$html .= "            	<option value=\"1\" ".$sl1[1].">TODOS</option>\n";
			$html .= "            	<option value=\"2\" ".$sl1[2].">VENCIDOS</option>\n";
			$html .= "            	<option value=\"3\" ".$sl1[3].">ACTIVOS</option>\n";
			$html .= "            </select>\n";
			$html .= "          </td>\n"; 
			$html .= "        </tr>\n";
			$html .= "        <tr>\n";
			$html .= "         	<td colspan = '5' align=\"center\" >\n";
			$html .= "          	<table width=\"70%\">\n";
			$html .= "             	<tr align=\"center\">\n";
			$html .= "               	<td >\n";
			$html .= "                 	<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "                </td>\n";
			$html .= "                <td>\n";
			$html .= "                 	<input class=\"input-submit\" type=\"button\" onclick=\"LimpiarCampos(document.formabuscar)\" name=\"Limpiar\" value=\"Limpiar Campos\">\n";
			$html .= "                </td>\n";
			$html .= "            	</tr>\n";
			$html .= "           	</table>\n";
			$html .= "          </td>\n";
			$html .= "        </tr>\n";
			$html .= "    	</table>\n";
			$html .= "      <div class=\"label_error\">".$msgError."</div>\n";
			$html .= "		</fieldset>\n";
			$html .= "	</center>\n";
			$html .= "</form><br>\n";

      if(!empty($afiliados))
      {
   			$html .= "<form name=\"formalista\" action=\"".$action['retirar']."\" method=\"post\">\n";
        $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "	  <tr class=\"formulacion_table_list\">\n";
				$html .= "		  <td width=\"3%\" >Nº AF.</td>\n";
				$html .= "			<td width=\"8%\">FECHA AFILIACION</td>\n";
				$html .= "			<td width=\"8%\">FECHA NACIMIENTO</td>\n";
				$html .= "			<td width=\"4%\">EDAD</td>\n";
				$html .= "		  <td width=\"35%\" colspan=\"2\">AFILIADO</td>\n";
				$html .= "			<td width=\"9%\">TIPO</td>\n";
				$html .= "			<td width=\"18%\">ESTADO - SUBESTADO</td>\n";
				$html .= "			<td width=\"13%\">ESTAMENTO</td>\n";
				$html .= "			<td width=\"20%\">PARENTESCO</td>\n";
				$html .= "			<td width=\"%\" colspan=\"2\">OP</td>\n";
        $html .= "			<td width=\"1%\" >\n";
        $html .= "        <input type=\"checkbox\" name=\"todos\" onclick=\"SeleccionarCheckBox(document.formalista,this.checked)\">\n";
        $html .= "      </td>\n";
				$html .= "		</tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
				foreach($afiliados as $key => $afiliado)
				{
					($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro'; 
					($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
          
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "		  <td >".$afiliado['eps_afiliacion_id']."</td>\n";
					$html .= "		  <td align=\"center\">".$afiliado['fecha_afiliacion']."</td>\n";
					$html .= "		  <td align=\"center\">".$afiliado['fecha_nacimiento']."</td>\n";
					$html .= "		  <td align=\"center\">".$afiliado['edad']." Años</td>\n";
					$html .= "		  <td width=\"11%\">".$afiliado['afiliado_tipo_id']." ".$afiliado['afiliado_id']."</td>\n";
					$html .= "		  <td >".$afiliado['apellidos_afiliado']." ".$afiliado['nombres_afiliado']."</td>\n";
					$html .= "		  <td >".$afiliado['descripcion_eps_tipo_afiliado']."</td>\n";
					$html .= "		  <td class=\"label\">".strtoupper($afiliado['descripcion_estado']." - ".$afiliado['descripcion_subestado'])."</td>\n";
					$html .= "		  <td >".strtoupper($afiliado['descripcion_estamento'])."</td>\n";
					$html .= "		  <td >".strtoupper($afiliado['descripcion_parentesco'])."</td>\n";
					$html .= "		  <td align=\"center\">\n";
          if($afiliado['estado_afiliado_id'] != 'RE' && $afiliado['estado_afiliado_id'] != 'AF')
          {
            $html .= "	      <a class=\"label_error\" title=\"MODIFICAR DATOS AFILIADO\" href=\"".$action['modificar'].URLRequest(array("afiliado_tipo_id"=>$afiliado['afiliado_tipo_id'],"afiliado_id"=>$afiliado['afiliado_id'],"eps_afiliacion_id"=>$afiliado['eps_afiliacion_id'],"eps_tipo_afiliado_id"=>$afiliado['eps_tipo_afiliado_id']))."\" title=\"CERRAR NOTA DE AJUSTE\">\n";
            $html .= "		      <img src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\">\n";
            $html .= "	      </a>\n";
					}
          $html .= "		  </td>\n";
          $html .= "		  <td align=\"center\">\n";
          $html .= "	      <a class=\"label_error\" title=\"MODIFICAR ESTADO AFILIADO\" href=\"".$action['estados'].URLRequest(array("afiliado_tipo_id"=>$afiliado['afiliado_tipo_id'],"afiliado_id"=>$afiliado['afiliado_id'],"eps_afiliacion_id"=>$afiliado['eps_afiliacion_id'],"eps_tipo_afiliado_id"=>$afiliado['eps_tipo_afiliado_id']))."\" title=\"CERRAR NOTA DE AJUSTE\">\n";
					$html .= "		      <img src=\"".GetThemePath()."/images/pguardar.png\" border=\"0\">\n";
					$html .= "	      </a>\n";
          $html .= "		  </td>\n";
          $html .= "			<td align=\"center\">\n";
          if($estado['estado_afiliado_id'] != $afiliado['estado_afiliado_id'] && $estado['subestado_afiliado_id'] != $afiliado['subestado_afiliado_id'] )
          {
            $html .= "        <input type=\"checkbox\" name=\"afi[".$afiliado['eps_afiliacion_id']."][".$afiliado['afiliado_tipo_id']."][".$afiliado['afiliado_id']."][chkbox]\" value=\"".$afiliado['eps_afiliacion_id']."\">\n";
            $html .= "        <input type=\"hidden\" name=\"afi[".$afiliado['eps_afiliacion_id']."][".$afiliado['afiliado_tipo_id']."][".$afiliado['afiliado_id']."][estado]\" value=\"".$afiliado['estado_afiliado_id']."\">\n";
            $html .= "        <input type=\"hidden\" name=\"afi[".$afiliado['eps_afiliacion_id']."][".$afiliado['afiliado_tipo_id']."][".$afiliado['afiliado_id']."][subestado]\" value=\"".$afiliado['subestado_afiliado_id']."\">\n";
            $html .= "        <input type=\"hidden\" name=\"afi[".$afiliado['eps_afiliacion_id']."][".$afiliado['afiliado_tipo_id']."][".$afiliado['afiliado_id']."][estamento]\" value=\"".$afiliado['eps_tipo_afiliado_id']."\">\n";
          }
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
				}
				$html .= "  </table>\n";
        $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" >\n";
  			$html .= "	  <tr>\n";
  			$html .= "		  <td align=\"right\"><br>\n";
  			$html .= "				<input class=\"input-submit\" type=\"button\" name=\"retirar\" value=\"Retirar\" onclick=\"EvaluarDatos(document.formalista)\">\n";
  			$html .= "		  </td>\n";
  			$html .= "	  </tr>\n";
  			$html .= "	  <tr>\n";
  			$html .= "	    <td align=\"center\">\n";
  			$html .= "	      <div id=\"error\" class=\"label_error\"></div>\n";
  			$html .= "	    </td>\n";
  			$html .= "	  </tr>\n";
  			$html .= "  </table>\n";
  			$html .= "</form>\n";
        $chtml = AutoCarga::factory('ClaseHTML');
        $html .= "		".$chtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
    		$html .= "		<br>\n";
      }
			$html .= "<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	  <tr>\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$html .= "		  </td>\n";
			$html .= "	  </tr>\n";
			$html .= "  </table>\n";
			$html .= "</form>\n";
      $html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 5;\n";
			$html .= "	function OcultarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('ContenedorP');\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function OcultarSpanGrande()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('ContenedorP');\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		  Iniciar();\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";				
			$html .= "	function Iniciar()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'ContenedorP';\n";
			$html .= "		titulo = 'tituloP';\n";
      $html .= "		ele = xGetElementById('ContenidoP');\n";
			$html .= "	  xResizeTo(ele,400, 'auto');\n";	
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,400, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,380, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrarP');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,380, 0);\n";
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
			$html .= "<div id='ContenedorP' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "	<div id='tituloP' class='draggable' style=\"	text-transform: uppercase;text-align:center;\">CONFIRMACIÓN</div>\n";
			$html .= "	<div id='cerrarP' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "	<div id='ContenidoP' class='d2Content'>\n";
      $html .= "    <table border=\"0\" width=\"100%\" align=\"center\" >\n";
  		$html .= "	    <tr>\n";
  		$html .= "		    <td align=\"center\" colspan=\"2\" class=\"normal_10AN\">\n";
  		$html .= "				  ESTA SEGURO QUE DESEA RETIRAR A LOS AFILIADOS SELECCIONADOS?\n";
  		$html .= "		    </td>\n";
  		$html .= "	    </tr>\n";
  		$html .= "	    <tr>\n";
  		$html .= "		    <td align=\"center\"><br>\n";
  		$html .= "				  <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"Continuar(document.formalista)\">\n";
  		$html .= "		    </td>\n";  		
      $html .= "		    <td align=\"center\"><br>\n";
  		$html .= "				  <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan()\">\n";
  		$html .= "		    </td>\n";
  		$html .= "	    </tr>\n";
  		$html .= "    </table><br>\n";
      $html .= "	</div>\n";
			$html .= "</div>\n";
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
		* Crea una forma donde se carga la informacion del cotizante, para
    * hacer la modificacion de la misma
		*
		* @param array $action Vector de links de la aplicaion
    * @param array $afiliado Vector con los datos del afiliado
		* @param array $tipos_documento Vector con los tipos de documentos
		* @param array $tipo_afiliacion Vector con los datos de tipos de afiliacion
		* @param array $estadocivil Vector con los datos de los estados civiles parametrizados
    * @param array $estratos Vector con los datos de los estratos parametrizados
    * @param array $tipo_afiliado Vector con los tipos de afiliado parametrizados
    * @param array $tipo_aportante Vector con los tipos de aportante parametrizados
    * @param array $estamentos Vector con los diferentes tipos de estamentos
    * @param array $pensiones Vector con los diferentes tipos de fondos de pensiones
    * @param array $eps Vector con los diferentes tipos de eps
    * @param array $ocupacion Vector con los datos de la ocupacion (grupo principal)
    * @param array $actividad Vector con los datos de la actividad economica (divisiones)
    * @param array $dependencia Vector con los datos de las dependencias
    * @param array $convenio Vector con los datos de las entidades convenio
    * @param array $parentesco vector con los datos de los tipos de parentesco
    * @param array $planes vector con los datos de los planes
    * @param array $puntos vector con los datos de los puntos de atencion
		*
    * @return String
		*/
		function FormaModificarInformacionCotizante($action,$afiliado,$tipos_documento,$tipo_afiliacion,$estadocivil,$estratos,$tipo_afiliado,$tipo_aportante,$estamentos,$pensiones,$eps,$ocupacion,$actividad,$dependencia,$convenio,$parentesco,$planes,$puntos)
		{
			$style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
      $url = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$afiliado['tipo_pais_id']."&dept=".$afiliado['tipo_dpto_id']."&mpio=".$afiliado['tipo_mpio_id']."&forma=registrar_afiliacion ";      
      
      $valida = ""; $i = 0;
      
      $html  = "<script>\n"; 
      $html .= "  function llamarLocalizacion()\n"; 
      $html .= "  {\n"; 
      $html .= "    window.open('".$url."','localidad','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); \n";
      $html .= "  }\n"; 
      $html .= "  function IniciarVentanaOcupacion(content,subcontent,tit,obj_cerrar,ancho,alto)\n"; 
      $html .= "  {\n"; 
      $html .= "    Iniciar(content,subcontent,tit,obj_cerrar,ancho*1,alto*1);\n"; 
      $html .= "		MostrarSpan(content);\n";
      $html .= "  }\n";
      $html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "	}\n";
			$html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "	}\n";
      $html .= "  function EvaluarDatosOcupacion(objeto)\n"; 
      $html .= "  {\n"; 
      $html .= "    if(objeto.grandes_grupos.value != \"-1\")\n";
      $html .= "    {\n"; 
      $html .= "      if(objeto.sub_grupos_principales.value == \"-1\")\n";
      $html .= "      {\n"; 
      $html .= "        document.getElementById(\"error_ocupacion\").innerHTML = \"SE DEBE SELECCIONAR EL SUBGRUPO PRINCIPAL\";\n"; 
      $html .= "        return true;\n"; 
      $html .= "      }\n"; 
  		$html .= "      else if(objeto.sub_grupo.value == \"-1\")\n";
      $html .= "        {\n"; 
      $html .= "          document.getElementById(\"error_ocupacion\").innerHTML = \"SE DEBE SELECCIONAR EL SUBGRUPO\";\n"; 
      $html .= "          return true;\n"; 
      $html .= "        }\n"; 
  		$html .= "        else if(objeto.grupos_primarios.value == \"-1\")\n";
      $html .= "          {\n"; 
      $html .= "            document.getElementById(\"error_ocupacion\").innerHTML = \"SE DEBE SELECCIONAR EL GRUPO PRIMARIO\";\n"; 
      $html .= "            return true;\n"; 
      $html .= "          }\n"; 
      $html .= "      document.getElementById(\"ocupacion_texto\").innerHTML = objeto.grupos_primarios.options[objeto.grupos_primarios.selectedIndex].title;\n"; 
      $html .= "    }\n"; 
      $html .= "    document.getElementById(\"error_ocupacion\").innerHTML = \"\";\n";
      $html .= "    if(objeto.grandes_grupos.value == \"-1\")\n";
      $html .= "      document.getElementById(\"ocupacion_texto\").innerHTML = '';\n"; 
      $html .= "    OcultarSpan('Ocupacion');\n"; 
      $html .= "  }\n";      
      $html .= "  function ResetDatosOcupacion(objeto)\n"; 
      $html .= "  {\n"; 
      $html .= "    if(document.getElementById(\"ocupacion_texto\").innerHTML == '')\n";
      $html .= "    {\n";
      $html .= "      objeto.grandes_grupos.selectedIndex = 0;\n";
      $html .= "      objeto.sub_grupo.selectedIndex = 0;\n";
      $html .= "      objeto.grupos_primarios.selectedIndex = 0;\n";
      $html .= "    }\n";
      $html .= "    OcultarSpan('Ocupacion');\n"; 
      $html .= "  }\n";
      $html .= "  function EvaluarDatosActividad(objeto)\n"; 
      $html .= "  {\n"; 
      $html .= "    if(objeto.division_actividad.value != \"-1\")\n";
      $html .= "    {\n"; 
      $html .= "      if(objeto.grupo_actividad.value == \"-1\")\n";
      $html .= "      {\n"; 
      $html .= "        document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR EL GRUPO\";\n"; 
      $html .= "        return true;\n"; 
      $html .= "      }\n";    
      $html .= "      else if(objeto.clase_actividad.value == \"-1\")\n";
      $html .= "        {\n"; 
      $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR LA CLASE\";\n"; 
      $html .= "          return true;\n"; 
      $html .= "        }\n"; 
      $html .= "      document.getElementById(\"actividad_texto\").innerHTML = objeto.grupo_actividad.options[objeto.grupo_actividad.selectedIndex].title;\n"; 
      $html .= "    }\n"; 
      $html .= "    document.getElementById(\"error_actividad\").innerHTML = \"\";\n"; 
      $html .= "    if(objeto.division_actividad.value == \"-1\")\n";
      $html .= "      document.getElementById(\"actividad_texto\").innerHTML = '';\n"; 
      $html .= "    OcultarSpan('Actividad');\n"; 
      $html .= "  }\n";
      $html .= "  function ResetDatosActividad(objeto)\n"; 
      $html .= "  {\n"; 
      $html .= "    if(document.getElementById(\"actividad_texto\").innerHTML == '')\n";
      $html .= "    {\n";
      $html .= "      objeto.division_actividad.selectedIndex = 0;\n";
      $html .= "      objeto.grupo_actividad.selectedIndex = 0;\n";
      $html .= "      objeto.clase_actividad.selectedIndex = 0;\n";
      $html .= "    }\n";
      $html .= "    OcultarSpan('Ocupacion');\n"; 
      $html .= "  }\n";
      $html .= "</script>\n"; 
      
			$html .= ThemeAbrirTabla('MODIFDICAR AFILIACION COTIZANTE');
			$html .= "<form name=\"registrar_afiliacion\" id=\"registrar_afiliacion\" action=\"javascript:evaluarDatosObligatorios(document.registrar_afiliacion)\" method=\"post\">\n";
			$html .= "<input type=\"hidden\" name=\"sirh_per_codigo\" value=\"".$afiliado['sirh_per_codigo']."\">\n";
			$html .= "<input type=\"hidden\" name=\"ter_codigo\" value=\"".$afiliado['ter_codigo']."\">\n";
			
      $html .= "<table border=\"0\" width=\"98%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend class=\"normal_10AN\">INFORMACION DEL COTIZANTE</legend>\n";
			$html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
      $html .= "					<tr>\n";
			$html .= "					  <td align=\"center\">\n";
      $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PLAN DE ATENCION</td>\n";
			$html .= "									<td colspan=\"2\">\n";
			$html .= "										<select name=\"plan_atencion\" class=\"select\" onchange=\"xajax_MostrarInformacionPlan(xajax.getFormValues('registrar_afiliacion'))\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			
			foreach($planes as $key => $dtl)
      {
				($afiliado['plan_atencion'] == $key)? $s1 = "selected": $s1 = ""; 
        $html .= "											<option value=\"".$key."\" $s1>".$dtl['plan_descripcion']."</option>\n";
			}
			$html .= "										</select>\n";
			$html .= "									</td>\n";
      $html .= "								</tr>\n";
      $html .= "								<tr>\n";
      $html .= "								  <td width=\"25%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO AFILIADO PLAN</td>\n";
      $html .= "								  <td width=\"25%\">\n";
      $html .= "                    <div id=\"tipo_afiliado_div\">\n";
      $html .= "			                <input type=\"hidden\" name=\"tipo_afiliado_plan\" value=\"".$afiliado['tipo_afiliado_atencion']."\">\n";
      $html .= "                    </div>\n";
      $html .= "                  </td>\n";
      $html .= "								  <td width=\"25%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >RANGO</td>\n";
      $html .= "								  <td width=\"25%\">\n";
      $html .= "                    <div id=\"rango_afiliado_div\">\n";
      $html .= "			                <input type=\"hidden\" name=\"rango_afiliado_plan\" value=\"".$afiliado['rango_afiliado_atencion']."\">\n";
      $html .= "                    </div>\n";
      $html .= "                  </td>\n";
      $html .= "								</tr>\n";
      $html .= "							</table>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.plan_atencion.value,1,'PLAN DE ATENCIÓN','select');\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.tipo_afiliado_plan.value,1,'TIPO DE AFILIADO PLAN','text');\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.rango_afiliado_plan.value,1,'RANGO','text');\n";

      $html .= "					  </td>\n";
      $html .= "					</tr>\n";
      $html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE RECEPCION</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_recepcion\" style=\"width:92%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_recepcion']."\">\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_recepcion.value,1,'FECHA DE RECEPCION','date',1);\n";

      $html .= "									</td>\n";
			$html .= "									<td align=\"left\" >".ReturnOpenCalendario('registrar_afiliacion','fecha_recepcion','/')."</td>\n";
			$html .= "								  <td colspan=\"3\">&nbsp;</td>\n";
			$html .= "								</tr>\n";
      
      $html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA VENCIMIENTO AFILIACION</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_vencimiento\" style=\"width:92%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_vencimiento']."\">\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_vencimiento.value,0,'FECHA VENCIMIENTO AFILIACION','date',0);\n";

      $html .= "									</td>\n";
			$html .= "									<td align=\"left\" >".ReturnOpenCalendario('registrar_afiliacion','fecha_vencimiento','/')."</td>\n";
			$html .= "								  <td colspan=\"3\">&nbsp;</td>\n";
			$html .= "								</tr>\n";
      
			$html .= "								<tr>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" colspan=\"4\" class=\"formulacion_table_list\" >FECHA DE AFILIACION AL SISTEMA GENERAL DE SEGURIDAD SOCIAL</td>\n";
			$html .= "									<td width=\"10%\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_sgss\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_afiliacion_sgss']."\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_sgss.value,0,'FECHA DE AFILIACION AL SISTEMA GENERAL DE SEGURIDAD SOCIAL','date',1);\n";

      $html .= "									</td>\n";
			$html .= "									<td >".ReturnOpenCalendario('registrar_afiliacion','fecha_sgss','/')."</td>\n";
      $html .= "								</tr>\n";
			$html .= "								<tr>\n";
      $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO DE VINCULACION O ESTAMENTO</td>\n";
			$html .= "									<td width=\"%\" colspan=\"2\">\n";
			$vec = " vector_estamentos = new Array();";
      if($estamentos[$afiliado['estamento_id']]['estamento_siis'] == "V")
      {
        $html .= "                    ".$estamentos[$afiliado['estamento_id']]['descripcion_estamento']."\n";
        $html .= "                    <input type=\"hidden\" name=\"estamento\" value=\"".$afiliado['estamento_id']."\">\n";
        $vec .= "	vector_estamentos['".$afiliado['estamento_id']."'] = 'V '; ";
      }
      else
      {
        $html .= "									  <select name=\"estamento\" class=\"select\" onchange=\"MostrarCapaEstamento(this.value)\">\n";
  			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
        $sl = "";
        
        foreach($estamentos as $key => $detalle)
        {
          if($detalle['estamento_siis'] != "V")
          {
            $vec .= "	vector_estamentos['".$key."'] = '".$detalle['estamento_siis']."'; ";
            ($key == $afiliado['estamento_id'])? $sl = "selected":$sl = "";
            $html .= "											<option value=\"".$key."\" $sl>".$detalle['descripcion_estamento']."</option>\n";
          } 
        }
  			$html .= "										</select>\n";
   			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.estamento.value,1,'TIPO DE VINCULACION O ESTAMENTO','select');\n";
      }
			$html .= "									</td>\n";	
      
      $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">TIPO AFILIACION</td>\n";
			$html .= "									<td colspan=\"2\">\n";
			$html .= "										<select name=\"tipo_afiliacion\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($tipo_afiliacion as $key1 => $afiliacion)
      {
				($key1 == trim($afiliado['eps_tipo_afiliacion_id']))? $sl = "selected":$sl = "";
			  $html .= "											<option value=\"".$key1."\" $sl>".$afiliacion['descripcion_eps_tipo_afiliacion']."</option>\n";
			}
			$html .= "										</select>\n";

 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.tipo_afiliacion.value,1,'TIPO AFILIACION','select');\n";

			$html .= "									</td>\n";	
      $html .= "								</tr>\n";
			$html .= "							</table>\n";
			$html .= "						<td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table  width=\"98%\" class=\"label\" $style>\n";
      $html .= "								<tr>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">TIPO DE IDENTIFICACION</td>\n";
			$html .= "									<td>\n";
			$html .= "				            <select name=\"tipo_id_cotizante\" class=\"select\">\n";
			$html .= "					            <option value=\"-1\">---Seleccionar---</option>\n";
			
			$s = "";
      foreach($tipos_documento as $key => $datos)
      {
				($key == $afiliado['afiliado_tipo_id'])? $sl = "selected": $sl = "";
        $html .= "					            <option value=\"".$datos['tipo_id_paciente']."\" $sl>".$datos['descripcion']."</option>\n";
      }
			$html .= "					          </select>\n";
      
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.tipo_id_cotizante.value,1,'TIPO DE IDENTIFICACION','select');\n";
      
			$html .= "									</td>\n";
      $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">N IDENTIFICACION</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"documento\" value=\"".$afiliado['afiliado_id']."\" class=\"input-text\" size=\"32\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.documento.value,1,'N IDENTIFICACION','text');\n";

			$html .= "									</td>\n";
			$html .= "								</tr>\n";				
			$html .= "								<tr class=\"formulacion_table_list\">\n";
			$html .= "									<td width=\"25%\">PRIMER APELLIDO</td>\n";
			$html .= "									<td width=\"25%\">SEGUNDO APELLIDO</td>\n";
			$html .= "									<td width=\"25%\">PRIMER NOMBRE</td>\n";
			$html .= "									<td width=\"25%\">SEGUNDO NOMBRE</td>\n";
			$html .= "								</tr>\n";
			$html .= "								<tr align=\"center\">\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"primerapellido\" value=\"".$afiliado['primer_apellido']."\" class=\"input-text\" size=\"20\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.primerapellido.value,1,'PRIMER APELLIDO','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"segundoapellido\" value=\"".$afiliado['segundo_apellido']."\" class=\"input-text\" size=\"30\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.segundoapellido.value,0,'SEGUNDO APELLIDO','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"primernombre\" value=\"".$afiliado['primer_nombre']."\" class=\"input-text\" size=\"20\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.primernombre.value,1,'PRIMER NOMBRE','text');\n";
			
      $html .= "									</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"segundonombre\" value=\"".$afiliado['segundo_nombre']."\" class=\"input-text\" size=\"30\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.segundonombre.value,0,'SEGUNDO NOMBRE','text');\n";

			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FECHA NACIMIENTO</td>\n";
			$html .= "									<td >\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_nacimiento\" size=\"11\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_nacimiento']."\">\n";
			$html .= "										".ReturnOpenCalendario('registrar_afiliacion','fecha_nacimiento','/')."</td>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_nacimiento.value,1,'FECHA NACIMIENTO','date',1);\n";

      $html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEXO</td>\n";
			$html .= "									<td align=\"left\">\n";
      
      $s1 = $s2 = "";
      
      if(trim($afiliado['tipo_sexo_id']) == 'M') $s1 = "checked";
      if(trim($afiliado['tipo_sexo_id']) == 'F') $s2 = "checked";
			$html .= "										<input type=\"radio\" name=\"tipo_sexo\" value=\"M\" $s1>Masculino\n";
			$html .= "										<input type=\"radio\" name=\"tipo_sexo\" value=\"F\" $s2>Femenino\n";
      $html .= "									</td>\n";
			$html .= "								</tr>\n";	
			
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">ESTADO CIVIL</td>\n";
			$html .= "									<td >\n";
			$html .= "										<select name=\"estado_civil\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			
			foreach($estadocivil as $key => $estadocv)
      {
				($afiliado['tipo_estado_civil_id'] == $key)? $s1 = "selected": $s1 = ""; 
        $html .= "											<option value=\"".$key."\" $s1>".$estadocv['descripcion']."</option>\n";
			}
			$html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.estado_civil.value,1,'ESTADO CIVIL','select');\n";

			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">ESTRATO SOCIOECONOMICO</td>\n";
			$html .= "									<td align=\"left\">\n";
			$html .= "										<select name=\"estrato_socioeconomico\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			
			foreach($estratos as $key => $detalle)
      {
				($key == $afiliado['estrato_socioeconomico_id'])? $sl = "selected":$sl= "";
        $html .= "											<option value=\"".$key."\" $sl>".$detalle['descripcion_estrato_socioeconomico']."</option>\n";
			}
			$html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.estrato_socioeconomico.value,0,'ESTRATO SOCIOECONOMICO','select');\n";

      $html .= "									</td>\n";
			$html .= "								</tr>\n";	
			
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">DIRECCION</td>\n";
			$html .= "									<td >\n";
 			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"100\" name=\"direccion_residencia\" value=\"".$afiliado['direccion_residencia']."\" class=\"input-text\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.direccion_residencia.value,1,'DIRECCION','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">ZONA DE RESIDENCIA</td>\n";
			$html .= "									<td align=\"left\">\n";
			
      $s1 = $s2 = "";
      if($afiliado['zona_residencia'] == 'U') $s1 = "checked";
      if($afiliado['zona_residencia'] == 'R') $s2 = "checked";
      $html .= "										<input type=\"radio\" name=\"zona_residencia\" $s1 value=\"U\">Urbano\n";
			$html .= "										<input type=\"radio\" name=\"zona_residencia\" $s2 value=\"R\">Rural\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";	
      $html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">DEPARTAMENTO - MUNICIPIO</td>\n";
			$html .= "									<td colspan =\"3\">\n";
			$html .= "				            <a title=\"ADICIONAR O CAMBIAR DEPARTAMENTO\" href=\"javascript:llamarLocalizacion()\"\">\n";
			$html .= "				              <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"-1\" width=\"16\" height=\"16\">\n";
			$html .= "				            </a>\n";
			$html .= "									  <label id=\"ubicacion\">".$afiliado['departamento_municipio']."</label>\n";
			$html .= "			              <input type=\"hidden\" name=\"pais\" value=\"".$afiliado['tipo_pais_id']."\">\n";
			$html .= "			              <input type=\"hidden\" name=\"dpto\" value=\"".$afiliado['tipo_dpto_id']."\">\n";
			$html .= "			              <input type=\"hidden\" name=\"mpio\" value=\"".$afiliado['tipo_mpio_id']."\">\n";			
 			$valida .= "	obligatorios[".($i++)."] = new Array(document.getElementById('ubicacion').innerHTML,1,'DEPARTAMENTO - MUNICIPIO','text');\n";

      $html .= "									</td>\n";
			$html .= "								</tr>\n";	

			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TELEFONO RESIDENCIA</td>\n";
			$html .= "									<td >\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"telefono_residencia\" value=\"".$afiliado['telefono_residencia']."\" class=\"input-text\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.telefono_residencia.value,0,'TELEFONO RESIDENCIA','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TELEFONO MOVIL</td>\n";
			$html .= "									<td align=\"left\">\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"telefono_movil\" value=\"".$afiliado['telefono_movil']."\" class=\"input-text\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.telefono_movil.value,0,'TELEFONO MOVIL','text');\n";

      $html .= "									</td>\n";
			$html .= "								</tr>\n";	
			$html .= "							</table>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE AFILIACION AL SERVICIO DE SALUD</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_afiliacion_empresa\" style=\"width:90%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_afiliacion']."\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_afiliacion_empresa.value,1,'FECHA DE AFILIACION AL SERVICIO DE SALUD','date',1);\n";

      $html .= "									</td>\n";
			$html .= "									<td width=\"15%\" align=\"left\" >".ReturnOpenCalendario('registrar_afiliacion','fecha_afiliacion_empresa','/')."</td>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">TIPO DE AFILIADO</td>\n";
			$html .= "									<td >COTIZANTE\n";
			$html .= "									  <input type=\"hidden\" name=\"tipo_afiliado\" value=\"C\">\n";
			$html .= "									</td>\n";			
			$html .= "								</tr>\n";
			$html .= "								<tr >\n";
			$html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >DEPENDENCIA DONDE LABORA</td>\n";
			$html .= "									<td colspan=\"4\" >\n";
			$html .= "										<select name=\"dependencia_laboral\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			$sl = "";
			foreach($dependencia as $key => $detalle)
      {
				($key == $afiliado['codigo_dependencia_id'])? $sl = "selected":$sl = "";
        $html .= "											<option value=\"".$key."\" $sl>".$detalle['descripcion_dependencia']."</option>\n";
			}
      
			$html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.dependencia_laboral.value,1,'DEPENDENCIA DONDE LABORA','select');\n";

      $html .= "									</td>\n";	
			$html .= "								</tr>\n";
      $html .= "								<tr >\n";
			$html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TELEFONO</td>\n";
			$html .= "									<td colspan=\"2\">\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"telefono_dependencia\" value=\"".$afiliado['telefono_dependencia']."\" class=\"input-text\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.telefono_dependencia.value,0,'TELEFONO','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO DE USUARIO</td>\n";
			$html .= "									<td >\n";
			$html .= "									  <select name=\"tipo_aportante\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($tipo_aportante as $key => $detalle)
      {
        ($key == $afiliado['tipo_aportante_id'])? $sl = "selected": $sl = "";
        $html .= "											<option value=\"".$key."\" $sl>".$detalle['descripcion_tipo_aportante']."</option>\n";
      }
      $html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.tipo_aportante.value,1,'TIPO DE USUARIO','select');\n";

			$html .= "									</td>\n";       
			$html .= "								</tr>\n";      
			$html .= "								<tr >\n";
			$html .= "									<td width=\"20%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >OCUPACION</td>\n";
			$html .= "									<td colspan=\"5\">\n";
 			$html .= "				            <a title=\"SELECCIONAR OCUPACION\" href=\"javascript:IniciarVentanaOcupacion('Ocupacion','Contenido_Ocupacion','ocupacion_titulo','ocupacion_cerrar',400,180)\"\">\n";
			$html .= "				              <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"-1\" width=\"16\" height=\"16\">\n";
			$html .= "				            </a>\n";
			$html .= "				            <label id=\"ocupacion_texto\"></label>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(document.getElementById('ocupacion_texto').innerHTML,0,'OCUPACION','text');\n";

			$html .= "									</td>\n";	
			$html .= "								</tr>\n";
      $html .= "								<tr >\n";
			$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PUNTO DE ATENCION</td>\n";
			$html .= "									<td colspan=\"3\">\n";
			$html .= "										<select name=\"puntos_atencion\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			
			foreach($puntos as $key => $dtl)
      {
				($afiliado['eps_punto_atencion_id'] == $dtl['eps_punto_atencion_id'])? $s1 = "selected": $s1 = ""; 
        $html .= "											<option value=\"".$dtl['eps_punto_atencion_id']."\" $s1>".$dtl['eps_punto_atencion_nombre']."</option>\n";
			}
			$html .= "										</select>\n";
			$html .= "									</td>\n";
      $html .= "								</tr>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.puntos_atencion.value,1,'PUNTO DE ATENCION','select');\n";

			$html .= "							</table>\n";
			$html .= "						<td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table  width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >NOMBRE DE LA EPS ANTERIOR</td>\n";
			$html .= "									<td width=\"65%\" colspan=\"3\">\n";
      $html .= "									  <select name=\"eps_anterior\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($eps as $key => $detalle)
      {
        ($key == trim($afiliado['eps_anterior']))? $sl = "selected": $sl = "";
        $html .= "											<option value=\"".$key."\" $sl>".$detalle['razon_social_eps']."</option>\n";
      }
      $html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.eps_anterior.value,0,'NOMBRE DE LA EPS ANTERIOR','select');\n";

			$html .= "									</td>\n";	
			$html .= "								</tr>\n";			
			$html .= "								<tr >\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FECHA DE AFILIACION</td>\n";
			$html .= "									<td align=\"right\" width=\"25%\" >\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_afiliacion\" style=\"width:40%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_afiliacion_eps_anterior']."\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_afiliacion.value,0,'FECHA DE AFILIACION','date',1);\n";

      $html .= "										".ReturnOpenCalendario('registrar_afiliacion','fecha_afiliacion','/')."</td>\n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEMANAS DE COTIZACION</td>\n";
			$html .= "									<td align=\"left\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"semanas_cotizadas\" size=\"12\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" value=\"".$afiliado['semanas_cotizadas_eps_anterior']."\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.semanas_cotizadas.value,0,'SEMANAS DE COTIZACION','numeric');\n";

      $html .= "									</td>\n";
			$html .= "								</tr>\n";	
			$html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ACTIVIDAD ECONOMICA DEL COTIZANTE</td>\n";
			$html .= "									<td colspan=\"3\">\n";
 			$html .= "				            <a title=\"SELECCIONAR ACTIVIDAD ECONOMICA\" href=\"javascript:IniciarVentanaOcupacion('Actividad','Contenido_Actividad','actividad_titulo','actividad_cerrar',400,80)\"\">\n";
			$html .= "				              <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"-1\" width=\"16\" height=\"16\">\n";
			$html .= "				            </a>\n";
			$html .= "				            <label id=\"actividad_texto\"></label>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(document.getElementById('actividad_texto').innerHTML,0,'ACTIVIDAD ECONOMICA DEL COTIZANTE','text');\n";

      $html .= "									</td>\n";	
			$html .= "								</tr>\n";				
			$html .= "							</table>\n";
			$html .= "						<td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			
      $cvn = $otr = $psn = $sub = "none";
      if($afiliado['estamento_id'])
      {
        switch($estamentos[$afiliado['estamento_id']]['estamento_siis'])
        {
          case 'J':
            $psn = "block";
          break;
          case 'S':
            $sub = "block";
            $psn = "block";
          break;
          case 'V':
            $cvn = "block";
          break;
          default:
            $otr = "block";
          break;
        }
      }
      
			$html .= "							<div id=\"pensionado\" style=\"display:$psn\">\n";
			$html .= "								<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "									<tr >\n";
			$html .= "										<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ENTIDAD QUE TIENE A CARGO SU PENSION</td>\n";
			$html .= "										<td width=\"%\" >\n";
      $html .= "									    <select name=\"administradora_pensiones\" class=\"select\">\n";
			$html .= "											  <option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($pensiones as $key => $detalle)
      {
        ($key == $afiliado['codigo_afp'])? $sl = "selected":$sl = "";
        $html .= "											  <option value=\"".$key."\" $sl>".$detalle['razon_social_afp']."</option>\n";
      }
      $html .= "										  </select>\n";
 			$valida .= "	sub_obliga[0][0] = new Array(objeto.administradora_pensiones.value,1,'ENTIDAD QUE TIENE A CARGO SU PENSION','select');\n";
      
      $html .= "										</td>\n";		
			$html .= "									</tr>\n";
			$html .= "									<tr >\n";
			$html .= "										<td width=\"30%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >INGRESO MENSUAL O VALOR MESADA PENSIONAL</td>\n";
			$html .= "										<td width=\"%\">\n";
			$html .= "											<input type=\"text\" class=\"input-text\" name=\"ingreso_mensual\" style=\"width:20%\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" value=\"".$afiliado['ingreso_mensual']."\">\n";
 			$valida .= "	sub_obliga[0][1] = new Array(objeto.ingreso_mensual.value,1,'INGRESO MENSUAL O VALOR MESADA PENSIONAL','numeric');\n";

      $html .= "										</td>\n";		
			$html .= "									</tr>\n";
			$html .= "								</table>\n";
			$html .= "							</div>\n";
      $html .= "							<div id=\"parentesco_pensionado\" style=\"display:$sub\">\n";
 			$html .= "								<table width=\"98%\" class=\"label\" $style>\n";
      $html .= "								  <tr>\n";
			$html .= "									  <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PARENTESCO CON COTIZANTE FALLECIDO</td>\n";
			$html .= "									  <td width=\"60%\" >\n";
			$html .= "									    <select name=\"parentesco\" class=\"select\">\n";
			$html .= "											  <option value=\"-1\">-SELECCIONAR-</option>\n";
      foreach($parentesco as $key => $detalle)
      {
        ($key == $afiliado['parentesco_id'])? $s = "selected":$s = "";
        $html .= "											  <option value=\"".$key."\" $s>".$detalle['descripcion_parentesco']."</option>\n";
      } 
			$html .= "										  </select>\n";
 			$valida .= "	sub_obliga[0][2] = new Array(objeto.parentesco.value,1,'PARENTESCO','select');\n";

			$html .= "									  </td>\n";	     
			$html .= "								  </tr>\n";
			$html .= "								</table>\n";
      $html .= "							</div>\n";

			$html .= "							<div id=\"otro\" style=\"display:$otr\">\n";
			$html .= "								<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "									<tr >\n";
			$html .= "									  <td colspan=\"4\" style=\"text-align:left;text-indent:8pt\"  class=\"formulacion_table_list\" >FECHA DE INGRESO A LABORAR EN LA INSTITUCION O FECHA DE VINCULACION AL APORTANTE</td>\n";
			$html .= "									  <td width=\"10%\">\n";
			$html .= "										  <input type=\"text\" class=\"input-text\" name=\"fecha_ingreso_empleo\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_ingreso_laboral']."\">\n";
 			$valida .= "	sub_obliga[1][0] = new Array(objeto.fecha_ingreso_empleo.value,1,'FECHA DE INGRESO A LABORAR EN LA INSTITUCION O FECHA DE VINCULACION AL APORTANTE','date',1);\n";

      $html .= "									  </td>\n";
			$html .= "									  <td >".ReturnOpenCalendario('registrar_afiliacion','fecha_ingreso_empleo','/')."</td>\n";
			$html .= "									</tr>\n";
 			$html .= "									<tr >\n";
			$html .= "										<td width=\"30%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SALARIO BASE</td>\n";
			$html .= "										<td colspan=\"3\" width=\"%\" >\n";
			$html .= "											<input type=\"text\" class=\"input-text\" name=\"salario_base\" style=\"width:30%\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" value=\"".$afiliado['ingreso_mensual']."\">\n";
 			$valida .= "	sub_obliga[1][1] = new Array(objeto.salario_base.value,1,'SALARIO BASE','numeric');\n";

      $html .= "										</td>\n";		
			$html .= "										<td ></td>\n";		
			$html .= "									</tr>\n";
      $html .= "									<tr class=\"formulacion_table_list\">\n";
			$html .= "										<td colspan=\"6\">OBSERVACIONES</td>\n";
 			$valida .= "	sub_obliga[1][2] = new Array(objeto.observaciones.value,0,'OBSERVACIONES','text');\n";

			$html .= "									</tr>\n";
      $html .= "									<tr>\n";
      $html .= "										<td colspan=\"6\" width=\"%\" >\n";
			$html .= "											<textarea name=\"observaciones\" style=\"width:100%\" rows=\"2\" class=\"textarea\">".$afiliado['observaciones']."</textarea>\n";
			$html .= "										</td>\n";		
			$html .= "									</tr>\n";
			$html .= "								</table>\n";
			$html .= "							</div>\n";
      
      $html .= "							<div id=\"convenio\" style=\"display:$cvn\">\n";
			$html .= "								<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "									<tr >\n";
			$html .= "										<td width=\"25%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >EMPRESA CONVENIO</td>\n";
			$html .= "										<td colspan=\"3\" width=\"%\" >\n";
      $html .= "									    <select name=\"empresa_convenio\" class=\"select\">\n";
			$html .= "											  <option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($convenio as $key => $detalle)
      {
        ($detalle['tipo_id_tercero'] == $afiliado['convenio_tipo_id_tercero'] && $detalle['tercero_id'] == $afiliado['convenio_tercero_id'])? $sl = "selected": $sl = "";
        $html .= "											  <option value=\"".$detalle['tipo_id_tercero']." ".$detalle['tercero_id']."\" $sl>".$detalle['nombre_tercero']."</option>\n";
      }
      $html .= "										  </select>\n";
 			$valida .= "	sub_obliga[2][0] = new Array(objeto.empresa_convenio.value,1,'EMPRESA CONVENIO','select');\n";
 			$valida .= "	sub_obliga[2][1] = new Array(objeto.empresa_convenio.value,1,'EMPRESA CONVENIO','select');\n";
 			$valida .= "	sub_obliga[2][2] = new Array(objeto.empresa_convenio.value,1,'EMPRESA CONVENIO','select');\n";
      
      $html .= "										</td>\n";		
			$html .= "									</tr>\n";
      $html .= "									<tr >\n";
			$html .= "									  <td height=\"18\" width=\"25%\" style=\"text-align:left;text-indent:8pt\"  class=\"formulacion_table_list\" >FECHA INICIO CONVENIO</td>\n";
			$html .= "									  <td colspan=\"2\">".$afiliado['fecha_inicio_convenio']."</td>\n";
			$html .= "									  <td width=\"25%\" style=\"text-align:left;text-indent:8pt\"  class=\"formulacion_table_list\" >FECHA FIN CONVENIO</td>\n";
			$html .= "									  <td colspan=\"2\">".$afiliado['fecha_vencimiento_convenio']."</td>\n";
      $html .= "									</tr>\n";

			$html .= "								</table>\n";
			$html .= "							</div>\n";
      
			$html .= "						<td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
      
      $html .= "<div id='Ocupacion' class='d2Container' style=\"display:none\">\n";
			$html .= "	<div id='ocupacion_titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">SELECCIONAR OCUPACION</div>\n";
			$html .= "	<div id='ocupacion_cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Ocupacion')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido_Ocupacion' class='d2Content' style=\"background:#FEFEFE\"><br>\n";
			$html .= "		<center>\n";
			$html .= "			<label id=\"error_ocupacion\" class=\"label_error\"></label>\n";
			$html .= "	  </center>\n";			
      $html .= "		<table width=\"100%\" class=\"label\" $style>\n";
			$html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >GRUPO</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"grandes_grupos\" class=\"select\" onchange=\"xajax_SeleccionarSubGrupoPrincipal(xajax.getFormValues('registrar_afiliacion'));\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($ocupacion as $key => $detalle)
        $html .= "						<option value=\"".$key."\" title=\"".$detalle['descripcion_ciuo_88_gran_grupo']."\">".substr($detalle['descripcion_ciuo_88_gran_grupo'],0,40)."</option>\n";

      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
      $html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SUBGRUPO PRINCIPAL</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"sub_grupos_principales\" class=\"select\" onChange=\"xajax_SeleccionarSubGrupos(xajax.getFormValues('registrar_afiliacion'))\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
      $html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SUBGRUPO</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"sub_grupo\" class=\"select\" onChange=\"xajax_SeleccionarGruposPrimarios(xajax.getFormValues('registrar_afiliacion'))\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
      $html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >GRUPO PRIMARIO</td>\n";
			$html .= "				<td width=\"%\" >\n";
      $html .= "					<select name=\"grupos_primarios\" class=\"select\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
			$html .= "		</table>\n";
      $html .= "    <table border=\"-1\" width=\"50%\" align=\"center\" >\n";
			$html .= "	    <tr>\n";
      $html .= "		    <td align=\"center\">\n";
      $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatosOcupacion(document.registrar_afiliacion)\">\n";
			$html .= "		    </td>";
      $html .= "		    <td align=\"center\">\n";
      $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cancelar\" onclick=\"ResetDatosOcupacion(document.registrar_afiliacion)\">\n";
			$html .= "		    </td>";
			$html .= "	    </tr>";
			$html .= "    </table>";
      $html .= "	</div>\n";
			$html .= "</div>\n";
      
      $html .= "<div id='Actividad' class='d2Container' style=\"display:none\">\n";
			$html .= "	<div id='actividad_titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">SELECCIONAR ACTIVIDAD ECONOMICA</div>\n";
			$html .= "	<div id='actividad_cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Actividad')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='Actividad_Ocupacion' class='d2Content' style=\"background:#FEFEFE\"><br>\n";
			$html .= "		<center>\n";
			$html .= "			<label id=\"error_actividad\" class=\"label_error\"></label>\n";
			$html .= "	  </center>\n";		
			$html .= "		<table width=\"100%\" class=\"label\" $style>\n";
			$html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >DIVISION</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"division_actividad\" class=\"select\" onchange=\"xajax_SeleccionarActividad(xajax.getFormValues('registrar_afiliacion'));\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($actividad as $key1 => $detalle)
      {
        ($key1 == $afiliado['ciiu_r3_division'])? $sl= "selected":$sl ="";
        $html .= "						<option value=\"".$key1."\" $sl title=\"".$detalle['descripcion_ciiu_r3_division']."\">".substr($detalle['descripcion_ciiu_r3_division'],0,40)."</option>\n";
      }

      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
      $html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >GRUPO</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"grupo_actividad\" class=\"select\" onchange=\"xajax_SeleccionarClase(xajax.getFormValues('registrar_afiliacion'));\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";      
      $html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >CLASE</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"clase_actividad\" class=\"select\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
			$html .= "		</table>\n";
      $html .= "	</div>\n";
      $html .= "    <table border=\"-1\" width=\"50%\" align=\"center\" >\n";
			$html .= "	    <tr>\n";
      $html .= "		    <td align=\"center\">\n";
      $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatosActividad(document.registrar_afiliacion)\">\n";
			$html .= "		    </td>";
      $html .= "		    <td align=\"center\">\n";
      $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cancelar\" onclick=\"ResetDatosActividad(document.registrar_afiliacion)\">\n";
			$html .= "		    </td>";
			$html .= "	    </tr>";
			$html .= "    </table>";
			$html .= "</div><br>\n";
			$html .= "<center><div id=\"error\" class=\"label_error\"></div></center>\n";
      
			$html .= "  <table border=\"-1\" width=\"50%\" align=\"center\" >\n";
			$html .= "	  <tr>\n";
      $html .= "		  <td align=\"center\"><br>\n";
      $html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$html .= "		  </td>";
      $html .= "		</form>\n";
			$html .= "		<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
			$html .= "		  </td>";
			$html .= "		</form>\n";
			$html .= "	</tr>";
			$html .= "</table>";
      
      $html .= "<script>\n";
      $html .= $vec;
      $html .= "  function MostrarCapaEstamento(estamento_id)\n"; 
      $html .= "  {\n"; 
      $html .= "  	valor = vector_estamentos[estamento_id]\n"; 
      $html .= "    cp1 = 'pensionado'; cp2 = 'otro'; cp3 = 'convenio'; cp4='parentesco_pensionado'\n"; 
      $html .= "    if(valor == '-1')\n"; 
      $html .= "    {\n"; 
      $html .= "      OcultarSpan(cp2);OcultarSpan(cp1);OcultarSpan(cp3);\n"; 
      $html .= "    }\n"; 
      $html .= "    else if(valor == 'J' || valor == 'S')\n"; 
      $html .= "      {\n"; 
      $html .= "		    OcultarSpan(cp3);\n";
      $html .= "		    OcultarSpan(cp2);\n";
      $html .= "		    MostrarSpan(cp1);\n";
      $html .= "		    OcultarSpan(cp4);\n";
      $html .= "		    if(valor == 'S')\n";
      $html .= "        {  MostrarSpan(cp4);}\n";
      $html .= "      }\n";
      $html .= "      else if(valor == 'V')\n"; 
      $html .= "        {\n"; 
      $html .= "		      OcultarSpan(cp1);\n";
      $html .= "		      OcultarSpan(cp2);\n";
      $html .= "		      MostrarSpan(cp3);\n";
      $html .= "		      OcultarSpan(cp4);\n";
      $html .= "        }\n";
      $html .= "      else\n"; 
      $html .= "        {\n"; 
      $html .= "		      OcultarSpan(cp3);\n";
      $html .= "		      OcultarSpan(cp1);\n";
      $html .= "		      MostrarSpan(cp2);\n";
      $html .= "		      OcultarSpan(cp4);\n";
      $html .= "        }\n";
      $html .= "  }\n";
			$html .= "	var contenedor = '';\n";
			$html .= "	var subcontenedor = '';\n";
			$html .= "	var titulo = '';\n";
			$html .= "	var cerrar = '';\n";
			$html .= "	var hiZ = 2;\n";
			
			$html .= "	function Iniciar(content,subcontent,tit,obj_cerrar,ancho,alto)\n";
			$html .= "	{\n";
			$html .= "		subcontenedor = subcontent;\n";
			$html .= "		contenedor = content;\n";
			$html .= "		titulo = tit;\n";
			$html .= "		cerrar = obj_cerrar;\n";
			$html .= "		ele = xGetElementById(subcontent);\n";
			$html .= "	  xResizeTo(ele,ancho,alto);\n";
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,ancho, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+10);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,(ancho-20), 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById(cerrar);\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele, (ancho - 20), 0);\n";
			$html .= "	}\n";

			$html .= "	function OcultarSpan(Seccion)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById(Seccion);\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xShow(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function OcultarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xHide(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan(Seccion)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById(Seccion);\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
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
      
      $html .= "	function evaluarDatosObligatorios(objeto)\n";
			$html .= "	{\n";
			$html .= "		div_msj = document.getElementById('error');\n";
			$html .= "		sub_obliga = new Array();\n";
			$html .= "		sub_obliga[0] = new Array();\n";
			$html .= "		sub_obliga[1] = new Array();\n";
			$html .= "		sub_obliga[2] = new Array();\n";
			$html .= "		obligatorios = new Array();\n";
			$html .= $valida."\n";
			$html .= "		for(i=0; i< $i; i++)\n";
			$html .= "		{\n";
			$html .= "			if(obligatorios[i][1] == '1')\n";
			$html .= "			{\n";
      $html .= "				switch(obligatorios[i][3])\n";
			$html .= "				{\n";
			$html .= "				  case 'select':\n";
			$html .= "				    if(obligatorios[i][0] == '-1')\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = 'SE DEBE SELECCIONAR '+obligatorios[i][2]+'';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'date':\n";
			$html .= "				    if(!IsDate(obligatorios[i][0]))\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = obligatorios[i][2]+', ES OBLIGATORIA O EL FORMATO NO CORRESPONDE';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'numeric':\n";
			$html .= "				    if(!IsNumeric(obligatorios[i][0]))\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = obligatorios[i][2]+', ES OBLIGATORIO O EL FORMATO NO CORRESPONDE';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'text':\n";
			$html .= "				    if(obligatorios[i][0] == '' || obligatorios[i][0] == undefined)\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = obligatorios[i][2]+', ES OBLIGATORIO';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";      
			$html .= "	  var fecha_validacion = new Date('".date("Y/m/d")."');\n";
      $html .= "		for(i=0; i< $i; i++)\n";
			$html .= "		{\n";
      $html .= "			if(obligatorios[i][3] == 'date' && obligatorios[i][0] != '')\n";
			$html .= "			{\n";
			$html .= "				if(!IsDate(obligatorios[i][0]))\n";
			$html .= "				{\n";
      $html .= "				  div_msj.innerHTML = obligatorios[i][2]+',FORMATO NO CORRESPONDE';\n";
			$html .= "				  return;\n";
			$html .= "				}\n";
      $html .= "				if(obligatorios[i][4] == 1)\n";
			$html .= "				{\n";
      $html .= "	        f = obligatorios[i][0].split('/')\n";
			$html .= "	        f1 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
      $html .= "          if(f1 > fecha_validacion)\n";
      $html .= "				  {\n";
      $html .= "				    div_msj.innerHTML = obligatorios[i][2]+',NO PUEDE SER SUPERIOR A: ".date("d/m/Y")."';\n";
			$html .= "				    return;\n";
			$html .= "				  }\n";
			$html .= "				}\n";
			$html .= "			}\n";
      $html .= "			else if(obligatorios[i][3] =='numeric'&& obligatorios[i][0] != '')\n";
      $html .= "			{\n";
			$html .= "			  if(!IsNumeric(obligatorios[i][0]))\n";
			$html .= "				{\n";
      $html .= "				  div_msj.innerHTML = obligatorios[i][2]+', FORMATO NO CORRESPONDE';\n";
			$html .= "					return;\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";   
      $html .= "		var indice = 1;\n";
      $html .= "		var arreglo = new Array(); \n";
      $html .= "		if(vector_estamentos[objeto.estamento.value] == 'J' || vector_estamentos[objeto.estamento.value] == 'S')\n";
      $html .= "      indice = '0';\n";
      $html .= "		else if(vector_estamentos[objeto.estamento.value] == 'V ') \n";
      $html .= "      indice = 2;\n";
      $html .= "		  else if(objeto.estamento.value == '-1') \n";
      $html .= "        indice = 3;\n";
      $html .= "		arreglo = sub_obliga[indice]; \n";
      $html .= "    if(vector_estamentos[objeto.estamento.value] != 'S') arreglo[2][1] = 0;\n ";
      $html .= "		for(i=0; i< arreglo.length ; i++)\n";
			$html .= "		{\n";
			$html .= "			if(arreglo[i][1] == '1')\n";
			$html .= "			{\n";
      $html .= "				switch(arreglo[i][3])\n";
			$html .= "				{\n";
			$html .= "				  case 'select':\n";
			$html .= "				    if(arreglo[i][0] == '-1')\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = 'SE DEBE SELECCIONAR '+arreglo[i][2]+'';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'date':\n";
			$html .= "				    if(!IsDate(arreglo[i][0]))\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = arreglo[i][2]+', ES OBLIGATORIA O EL FORMATO NO CORRESPONDE';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'numeric':\n";
			$html .= "				    if(!IsNumeric(arreglo[i][0]))\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = arreglo[i][2]+', ES OBLIGATORIO O EL FORMATO NO CORRESPONDE';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'text':\n";
			$html .= "				    if(arreglo[i][0] == '' || arreglo[i][0] == undefined)\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = arreglo[i][2]+', ES OBLIGATORIO';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";
      
      $html .= "		if(!objeto.tipo_sexo[0].checked && !objeto.tipo_sexo[1].checked)\n";
			$html .= "		{\n";
      $html .= "		   div_msj.innerHTML = 'SE DEBE SELECCIONAR EL TIPO DE SEXO';\n";
			$html .= "		   return;\n";
			$html .= "		}\n";
      $html .= "		if(!objeto.zona_residencia[0].checked && !objeto.zona_residencia[1].checked)\n";
			$html .= "		{\n";
      $html .= "		   div_msj.innerHTML = 'SE DEBE SELECCIONAR LA ZONA DE RESIDENCIA';\n";
			$html .= "		   return;\n";
			$html .= "		}\n";
      $html .= "    if(objeto.tipo_id_cotizante.value != '".$afiliado['afiliado_tipo_id']."' || ";
      $html .= "        objeto.documento.value != '".$afiliado['afiliado_id']."')\n ";
			$html .= "	  {\n";
			$html .= "	    xajax_ValidarAfiliado(objeto.tipo_id_cotizante.value,objeto.documento.value);\n";
			$html .= "	  }\n";
			$html .= "	  Continuar();\n";
			$html .= "	}\n";
			$html .= "	function Continuar()\n";
			$html .= "	{\n";
 			$html .= "		document.getElementById('error').innerHTML = '';\n";
			$html .= "		document.registrar_afiliacion.action = '".$action['crear']."';\n";
			$html .= "		document.registrar_afiliacion.submit();\n";
			$html .= "	}\n";
      
      $html .= "	function finMes(nMes)\n";
			$html .= "	{\n";
			$html .= "		var nRes = 0;\n";
			$html .= "		switch (nMes)\n";
			$html .= "		{\n";
			$html .= "			case '01': nRes = 31; break;\n";
			$html .= "			case '02': nRes = 29; break;\n";
			$html .= "			case '03': nRes = 31; break;\n";
			$html .= "			case '04': nRes = 30; break;\n";
			$html .= "			case '05': nRes = 31; break;\n";
			$html .= "			case '06': nRes = 30; break;\n";
			$html .= "			case '07': nRes = 31; break;\n";
			$html .= "			case '08': nRes = 31; break;\n";
			$html .= "			case '09': nRes = 30; break;\n";
			$html .= "			case '10': nRes = 31; break;\n";
			$html .= "			case '11': nRes = 30; break;\n";
			$html .= "			case '12': nRes = 31; break;\n";
			$html .= "		}\n";
			$html .= "		return nRes;\n";
			$html .= "	}\n";
			$html .= "	function IsDate(fecha)\n";
			$html .= "	{\n";
			$html .= "		if(fecha == '' || fecha == undefined)	return false;\n";
			$html .= "		var bol = true;\n";
			$html .= "		var arr = fecha.split('/');\n";
			$html .= "		if(arr.length > 3)\n";
			$html .= "			return false;\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "			bol = bol && (IsNumeric(arr[0]));\n";
			$html .= "			bol = bol && (IsNumeric(arr[1]));\n";
			$html .= "			bol = bol && (IsNumeric(arr[2]));\n";
			$html .= "			bol = bol && ((arr[1] >= 1) && (arr[1] <= 12));\n";
			$html .= "			bol = bol && (arr[0] <= finMes(arr[1]));\n";
			$html .= "			return bol;\n";
			$html .= "		}\n";
			$html .= "	}\n";
      $html .= "	function IsNumeric(valor)\n";
			$html .= "	{\n";
			$html .= "		try \n";
			$html .= "		{ \n";
			$html .= "		  var log = valor.length; \n";
			$html .= "		  var sw='S';\n";
			$html .= "		  var puntos = 0;\n";
			$html .= "		  for (x=0; x<log; x++)\n";
			$html .= "		  { \n";
			$html .= "			  v1 = valor.substr(x,1);\n";
			$html .= "			  v2 = parseInt(v1);\n";
			$html .= "		  	//Compruebo si es un valor num?ico\n";
			$html .= "		  	if(v1 == '.')\n";
			$html .= "	  		{\n";
			$html .= "		  		puntos ++;\n";
			$html .= "		  	}\n";
			$html .= "		  	else if (isNaN(v2)) \n";
			$html .= "		  	{ \n";
			$html .= "		  		sw= 'N';\n";
			$html .= "	  			break;\n";
			$html .= "	  		}\n";
			$html .= "	  	}\n";
			$html .= "		  if(log == 0) sw = 'N';\n";
			$html .= "		  if(puntos > 1) sw = 'N';\n";
			$html .= "		  if(sw=='S')\n"; 
			$html .= "			  return true;\n";
			$html .= "		  return false;\n";
			$html .= "	  } \n";
			$html .= "	  catch(error){return false;} \n";
			$html .= "	} \n";
			$html .= "</script>\n";
      $html .= "<script>\n";
      if($afiliado['ciiu_r3_division'] !== null || $afiliado['ciuo_88_grupo_primario'] !== null)
      {
        if(!$afiliado['ciiu_r3_clase'])
          $html .= "  xajax_SeleccionarDatosDefecto(xajax.getFormValues('registrar_afiliacion'),'".$afiliado['ciiu_r3_grupo']."','".$afiliado['ciuo_88_grupo_primario']."');\n";
        else
          $html .= "  xajax_SeleccionarDatosDefectoClase(xajax.getFormValues('registrar_afiliacion'),'".$afiliado['ciiu_r3_grupo']."','".$afiliado['ciuo_88_grupo_primario']."','".$afiliado['ciiu_r3_clase']."');\n";
      }
      
      $html .= "  xajax_MostrarInformacionPlan(xajax.getFormValues('registrar_afiliacion'));\n";
      $html .= "</script>\n";
			$html .= ThemeCerrarTabla();	
			return $html;
		}
    /**
		* Crea una forma donde se carga la informacion del beneficiario,
    * para hacer la modificacion de la misma
		*
		* @param array $action Vector de links de la aplicaion
		* @param array $eps Vector con los datos de las eps parametrizadas
    * @param array $ocupacion Vector con los datos de la ocupacion (grupo principal)
    * @param array $afiliado Vector con los datos del afiliado
    * @param array $tipos_documento Vector con los tipos de documentos 
    * @param array $parentesco vector con los datos de los tipos de parentesco
    * @param array $planes vector con los datos de los planes
    * @param array $puntos vector con los datos de los puntos de atencion
    *
		* @return String
		*/
		function FormaModificarInformacionBeneficiario($action,$eps,$ocupacion,$afiliado,$tipos_documento,$parentesco,$planes,$puntos)
		{
			$style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
      $url = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$afiliado['tipo_pais_id']."&dept=".$afiliado['tipo_dpto_id']."&mpio=".$afiliado['tipo_mpio_id']."&forma=registrar_afiliacion ";      
      
      $valida = ""; $i = 0;
      $ctl = AutoCarga::factory("ClaseUtil");
      $html  = $ctl->AcceptNum();
      $html .= $ctl->AcceptDate("/");
      $html .= "<script>\n"; 
      $html .= "	function cerrarVentana()\n";
			$html .= "	{\n";
			$html .= "		window.opener.document.informacion_beneficiario.submit();\n";
			$html .= "		window.close();\n";
			$html .= "	}\n";
      $html .= "  function llamarLocalizacion()\n"; 
      $html .= "  {\n"; 
      $html .= "    window.open('".$url."','localidad','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); \n";
      $html .= "  }\n"; 
      $html .= "  function IniciarVentanaOcupacion(content,subcontent,tit,obj_cerrar,ancho,alto)\n"; 
      $html .= "  {\n"; 
      $html .= "    Iniciar(content,subcontent,tit,obj_cerrar,ancho*1,alto*1);\n"; 
      $html .= "		MostrarSpan(content);\n";
      $html .= "  }\n";
      $html .= "  function MostrarCapaEstamento(valor)\n"; 
      $html .= "  {\n"; 
      $html .= "    cp1 = 'pensionado'; cp2 = 'otro';\n"; 
      $html .= "    if(valor == '-1')\n"; 
      $html .= "    {\n"; 
      $html .= "      OcultarSpan(cp2);OcultarSpan(cp1);\n"; 
      $html .= "    }\n"; 
      $html .= "    else if(valor == 'J')\n"; 
      $html .= "      {\n"; 
      $html .= "		    OcultarSpan(cp2);\n";
      $html .= "		    MostrarSpan(cp1);\n";
      $html .= "      }\n";
      $html .= "      else\n"; 
      $html .= "        {\n"; 
      $html .= "		      OcultarSpan(cp1);\n";
      $html .= "		      MostrarSpan(cp2);\n";
      $html .= "        }\n";
      $html .= "  }\n";
      $html .= "  function EvaluarDatosOcupacion(objeto)\n"; 
      $html .= "  {\n"; 
      $html .= "    if(objeto.grandes_grupos.value != \"-1\")\n";
      $html .= "    {\n"; 
      $html .= "      if(objeto.sub_grupos_principales.value == \"-1\")\n";
      $html .= "      {\n"; 
      $html .= "        document.getElementById(\"error_ocupacion\").innerHTML = \"SE DEBE SELECCIONAR EL SUBGRUPO PRINCIPAL\";\n"; 
      $html .= "        return true;\n"; 
      $html .= "      }\n"; 
  		$html .= "      else if(objeto.sub_grupo.value == \"-1\")\n";
      $html .= "        {\n"; 
      $html .= "          document.getElementById(\"error_ocupacion\").innerHTML = \"SE DEBE SELECCIONAR EL SUBGRUPO\";\n"; 
      $html .= "          return true;\n"; 
      $html .= "        }\n"; 
  		$html .= "        else if(objeto.grupos_primarios.value == \"-1\")\n";
      $html .= "          {\n"; 
      $html .= "            document.getElementById(\"error_ocupacion\").innerHTML = \"SE DEBE SELECCIONAR EL GRUPO PRIMARIO\";\n"; 
      $html .= "            return true;\n"; 
      $html .= "          }\n"; 
      $html .= "      document.getElementById(\"ocupacion_texto\").innerHTML = objeto.grupos_primarios.options[objeto.grupos_primarios.selectedIndex].title;\n"; 
      $html .= "    }\n"; 
      $html .= "    document.getElementById(\"error_ocupacion\").innerHTML = \"\";\n";
      $html .= "    if(objeto.grandes_grupos.value == \"-1\")\n";
      $html .= "      document.getElementById(\"ocupacion_texto\").innerHTML = '';\n"; 
      $html .= "    OcultarSpan('Ocupacion');\n"; 
      $html .= "  }\n";   
      $html .= "  function ResetDatosOcupacion(objeto)\n"; 
      $html .= "  {\n"; 
      $html .= "    if(document.getElementById(\"ocupacion_texto\").innerHTML == '')\n";
      $html .= "    {\n";
      $html .= "      objeto.grandes_grupos.selectedIndex = 0;\n";
      $html .= "      objeto.sub_grupo.selectedIndex = 0;\n";
      $html .= "      objeto.grupos_primarios.selectedIndex = 0;\n";
      $html .= "    }\n";
      $html .= "    OcultarSpan('Ocupacion');\n"; 
      $html .= "  }\n";
      $html .= "</script>\n"; 
      
			$html .= ThemeAbrirTabla('MODIFICAR DATOS BENEFICIARIO');
			$html .= "<form name=\"registrar_afiliacion\" id=\"registrar_afiliacion\" action=\"javascript:evaluarDatosObligatorios(document.registrar_afiliacion)\" method=\"post\">\n";
			$html .= "<table border=\"-1\" width=\"100%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "			  <legend class=\"normal_10AN\">INFORMACION BENEFICIARIO</legend>\n";
			$html .= "				<table width=\"100%\" cellspacing=\"5\">\n";
      $html .= "					<tr>\n";
			$html .= "					  <td align=\"center\">\n";
      $html .= "							<table width=\"100%\" class=\"label\" $style >\n";
			$html .= "								<tr >\n";
			$html .= "									<td width=\"25%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PLAN DE ATENCION</td>\n";
			$html .= "									<td colspan=\"4\">\n";
			$html .= "										<select name=\"plan_atencion\" class=\"select\" onchange=\"xajax_MostrarInformacionPlan(xajax.getFormValues('registrar_afiliacion'))\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			
			foreach($planes as $key => $dtl)
      {
				($afiliado['plan_atencion'] == $key)? $s1 = "selected": $s1 = ""; 
        $html .= "											<option value=\"".$key."\" $s1>".$dtl['plan_descripcion']."</option>\n";
			}
			$html .= "										</select>\n";
			$html .= "									</td>\n";
      $html .= "								</tr>\n";
      $html .= "								<tr>\n";
      $html .= "								  <td width=\"25%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO AFILIADO PLAN</td>\n";
      $html .= "								  <td width=\"25%\" colspan=\"2\" >\n";
      $html .= "                    <div id=\"tipo_afiliado_div\">\n";
      $html .= "			                <input type=\"hidden\" name=\"tipo_afiliado_plan\" value=\"".$afiliado['tipo_afiliado_atencion']."\">\n";
      $html .= "                    </div>\n";
      $html .= "                  </td>\n";
      $html .= "								  <td width=\"25%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >RANGO</td>\n";
      $html .= "								  <td width=\"25%\">\n";
      $html .= "                    <div id=\"rango_afiliado_div\">\n";
      $html .= "			                <input type=\"hidden\" name=\"rango_afiliado_plan\" value=\"".$afiliado['rango_afiliado_atencion']."\">\n";
      $html .= "                    </div>\n";
      $html .= "                  </td>\n";
      $html .= "								</tr>\n";
      $html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA VENCIMIENTO AFILIACION</td>\n";
			$html .= "									<td width=\"10%\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_vencimiento\" style=\"width:92%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_vencimiento']."\">\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_vencimiento.value,0,'FECHA VENCIMIENTO AFILIACION','date',2);\n";

      $html .= "									</td>\n";
			$html .= "									<td align=\"left\" colspan=\"3\" >".ReturnOpenCalendario('registrar_afiliacion','fecha_vencimiento','/')."</td>\n";
			
			$html .= "								</tr>\n";
      $html .= "							</table>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.plan_atencion.value,1,'PLAN DE ATENCIÓN','select');\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.tipo_afiliado_plan.value,1,'TIPO DE AFILIADO PLAN','text');\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.rango_afiliado_plan.value,1,'RANGO','text');\n";

      $html .= "					  </td>\n";
      $html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table  width=\"100%\" class=\"label\" $style>\n";
			$html .= "								<tr>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">TIPO DE IDENTIFICACION</td>\n";
			$html .= "									<td>\n";
			$html .= "				            <select name=\"tipo_id_beneficiario\" class=\"select\">\n";
			$html .= "					            <option value=\"-1\">---Seleccionar---</option>\n";
			
			$s = "";
      foreach($tipos_documento as $key => $datos)
      {
				($key == $afiliado['afiliado_tipo_id'])? $s = "selected": $s = "";
        $html .= "					            <option value=\"".$datos['tipo_id_paciente']."\" $s>".$datos['descripcion']."</option>\n";
      }
			$html .= "					          </select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.tipo_id_beneficiario.value,1,'TIPO DE IDENTIFICACION','select');\n";
      
			$html .= "									</td>\n";
      $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">N IDENTIFICACION</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"documento\" value=\"".$afiliado['afiliado_id']."\" class=\"input-text\" size=\"32\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.documento.value,1,'N IDENTIFICACION','text');\n";

			$html .= "									</td>\n";
			$html .= "								</tr>\n";			
			$html .= "								<tr class=\"formulacion_table_list\">\n";
			$html .= "									<td width=\"25%\">PRIMER APELLIDO</td>\n";
			$html .= "									<td width=\"25%\">SEGUNDO APELLIDO</td>\n";
			$html .= "									<td width=\"25%\">PRIMER NOMBRE</td>\n";
			$html .= "									<td width=\"25%\">SEGUNDO NOMBRE</td>\n";
			$html .= "								</tr>\n";
			$html .= "								<tr align=\"center\">\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"primerapellido\" value=\"".$afiliado['primer_apellido']."\" class=\"input-text\" size=\"20\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.primerapellido.value,1,'PRIMER APELLIDO','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"segundoapellido\" value=\"".$afiliado['segundo_apellido']."\" class=\"input-text\" size=\"30\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.segundoapellido.value,0,'SEGUNDO APELLIDO','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"primernombre\" value=\"".$afiliado['primer_nombre']."\" class=\"input-text\" size=\"20\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.primernombre.value,1,'PRIMER NOMBRE','text');\n";
			
      $html .= "									</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"segundonombre\" value=\"".$afiliado['segundo_nombre']."\" class=\"input-text\" size=\"30\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.segundonombre.value,0,'SEGUNDO NOMBRE','text');\n";

			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FECHA NACIMIENTO</td>\n";
			$html .= "									<td >\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_nacimiento\" size=\"11\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_nacimiento']."\">\n";
			$html .= "										".ReturnOpenCalendario('registrar_afiliacion','fecha_nacimiento','/')."</td>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_nacimiento.value,1,'FECHA NACIMIENTO','date',1);\n";

      $html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEXO</td>\n";
			$html .= "									<td align=\"left\">\n";
      
      $s1 = $s2 = "";
      
      if(trim($afiliado['tipo_sexo_id']) == 'M') $s1 = "checked";
      if(trim($afiliado['tipo_sexo_id']) == 'F') $s2 = "checked";
			$html .= "										<input type=\"radio\" name=\"tipo_sexo\" value=\"M\" $s1>Masculino\n";
			$html .= "										<input type=\"radio\" name=\"tipo_sexo\" value=\"F\" $s2>Femenino\n";
      $html .= "									</td>\n";
			$html .= "								</tr>\n";
			
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">DIRECCION</td>\n";
			$html .= "									<td >\n";
 			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"100\" name=\"direccion_residencia\" value=\"".$afiliado['direccion_residencia']."\" class=\"input-text\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.direccion_residencia.value,1,'DIRECCION','text');\n";

      $html .= "									</td>\n";
      $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TELEFONO RESIDENCIA</td>\n";
			$html .= "									<td >\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"telefono_residencia\" value=\"".$afiliado['telefono_residencia']."\" class=\"input-text\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.telefono_residencia.value,0,'TELEFONO RESIDENCIA','text');\n";

      $html .= "									</td>\n";
			$html .= "								</tr>\n";	
			      
      $html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">DEPARTAMENTO - MUNICIPIO</td>\n";
			$html .= "									<td>\n";
			$html .= "				            <a title=\"ADICIONAR O CAMBIAR DEPARTAMENTO\" href=\"javascript:llamarLocalizacion()\"\">\n";
			$html .= "				              <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"-1\" width=\"16\" height=\"16\">\n";
			$html .= "				            </a>\n";
			$html .= "									  <label id=\"ubicacion\">".$afiliado['departamento_municipio']."</label>\n";
			$html .= "			              <input type=\"hidden\" name=\"pais\" value=\"".$afiliado['tipo_pais_id']."\">\n";
			$html .= "			              <input type=\"hidden\" name=\"dpto\" value=\"".$afiliado['tipo_dpto_id']."\">\n";
			$html .= "			              <input type=\"hidden\" name=\"mpio\" value=\"".$afiliado['tipo_mpio_id']."\">\n";			
 			$valida .= "	obligatorios[".($i++)."] = new Array(document.getElementById('ubicacion').innerHTML,1,'DEPARTAMENTO - MUNICIPIO','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">ZONA DE RESIDENCIA</td>\n";
			$html .= "									<td align=\"left\">\n";
			
      $s1 = $s2 = "";
      if($afiliado['zona_residencia'] == 'U') $s1 = "checked";
      if($afiliado['zona_residencia'] == 'R') $s2 = "checked";
      $html .= "										<input type=\"radio\" name=\"zona_residencia\" $s1 value=\"U\">Urbano\n";
			$html .= "										<input type=\"radio\" name=\"zona_residencia\" $s2 value=\"R\">Rural\n";
			$html .= "									</td>\n";
      
			$html .= "								</tr>\n";	
			$html .= "							</table>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table width=\"100%\" class=\"label\" $style>\n";
 			$html .= "								<tr>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" colspan=\"2\" class=\"formulacion_table_list\" >FECHA DE AFILIACION AL SISTEMA GENERAL DE SEGURIDAD SOCIAL</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_sgss\" style=\"width:90%\" maxlength=\"10\"  onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_afiliacion_sgss']."\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_sgss.value,0,'FECHA DE AFILIACION AL SISTEMA GENERAL DE SEGURIDAD SOCIAL','date',1);\n";

      $html .= "									</td>\n";
			$html .= "									<td align=\"left\" colspan=\"3\" >".ReturnOpenCalendario('registrar_afiliacion','fecha_sgss','/')."</td>\n";
			$html .= "								</tr>\n";

			$html .= "								<tr >\n";
			$html .= "									<td width=\"60%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE AFILIACION AL SERVICIO DE SALUD</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_afiliacion_empresa\" style=\"width:90%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_afiliacion']."\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_afiliacion_empresa.value,1,'FECHA DE AFILIACION AL SERVICIO DE SALUD','date',1);\n";

      $html .= "									</td>\n";
			$html .= "									<td width=\"15%\" align=\"left\" colspan=\"3\">".ReturnOpenCalendario('registrar_afiliacion','fecha_afiliacion_empresa','/')."</td>\n";		
			$html .= "								</tr>\n";
			$html .= "								<tr >\n";
			$html .= "									<td width=\"20%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >OCUPACION</td>\n";
			$html .= "									<td colspan=\"5\">\n";
 			$html .= "				            <a title=\"SELECCIONAR OCUPACION\" href=\"javascript:IniciarVentanaOcupacion('Ocupacion','Contenido_Ocupacion','ocupacion_titulo','ocupacion_cerrar',400,180)\"\">\n";
			$html .= "				              <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"-1\" width=\"16\" height=\"16\">\n";
			$html .= "				            </a>\n";
			$html .= "				            <label id=\"ocupacion_texto\">".utf8_decode($afiliado['ocupacion_hd'])."</label>\n";

			$html .= "									</td>\n";	
			$html .= "								</tr>\n";
      $html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PARENTESCO</td>\n";
			$html .= "									<td width=\"%\" colspan=\"5\">\n";
			$html .= "									  <select name=\"parentesco\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
      foreach($parentesco as $key => $detalle)
      {
        ($key == $afiliado['parentesco_id'])? $s = "selected":$s = "";
        $html .= "											<option value=\"".$key."\" $s>".$detalle['descripcion_parentesco']."</option>\n";
      } 
			$html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.parentesco.value,1,'PARENTESCO','select');\n";

			$html .= "									</td>\n";	     
			$html .= "								</tr>\n";
      $html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PUNTO DE ATENCION</td>\n";
			$html .= "									<td colspan=\"5\">\n";
			$html .= "										<select name=\"puntos_atencion\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			
			foreach($puntos as $key => $dtl)
      {
				($afiliado['eps_punto_atencion_id'] == $dtl['eps_punto_atencion_id'])? $s1 = "selected": $s1 = ""; 
        $html .= "											<option value=\"".$dtl['eps_punto_atencion_id']."\" $s1>".$dtl['eps_punto_atencion_nombre']."</option>\n";
			}
			$html .= "										</select>\n";
			$html .= "									</td>\n";
      $html .= "								</tr>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.puntos_atencion.value,1,'PUNTO DE ATENCION','select');\n";

			$html .= "							</table>\n";
			$html .= "						<td>\n";
			$html .= "					</tr>\n";

			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table  width=\"100%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >NOMBRE DE LA EPS ANTERIOR</td>\n";
			$html .= "									<td width=\"65%\" colspan=\"3\">\n";
      $html .= "									  <select name=\"eps_anterior\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($eps as $key => $detalle)
      {
        ($key == $afiliado['eps_anterior'])? $s = "selected":$s = "";
        $html .= "											<option value=\"".$key."\" $s>".$detalle['razon_social_eps']."</option>\n";
      }
      $html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.eps_anterior.value,0,'NOMBRE DE LA EPS ANTERIOR','select');\n";

			$html .= "									</td>\n";	
			$html .= "								</tr>\n";			
			$html .= "								<tr >\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FECHA DE AFILIACION</td>\n";
			$html .= "									<td align=\"left\" width=\"25%\" >\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_afiliacion\" style=\"width:50%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_afiliacion_eps_anterior']."\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_afiliacion.value,0,'FECHA DE AFILIACION','date',1);\n";

      $html .= "										".ReturnOpenCalendario('registrar_afiliacion','fecha_afiliacion','/')."</td>\n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEMANAS DE COTIZACION</td>\n";
			$html .= "									<td align=\"left\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"semanas_cotizadas\" size=\"12\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" value=\"".$afiliado['semanas_cotizadas_eps_anterior']."\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.semanas_cotizadas.value,0,'SEMANAS DE COTIZACION','numeric');\n";

      $html .= "									</td>\n";
			$html .= "								</tr>\n";				
			$html .= "							</table>\n";
			$html .= "						<td>\n";
			$html .= "					</tr>\n";
      $html .= "			    <tr>\n";
			$html .= "			      <td>\n";
			$html .= "							<table  width=\"100%\" class=\"label\" $style>\n";
      $html .= "				        <tr class=\"formulacion_table_list\">\n";
			$html .= "								  <td colspan=\"6\">OBSERVACIONES</td>\n";
 			$valida .= "	sub_obliga[1][2] = new Array(objeto.observaciones.value,0,'OBSERVACIONES','text');\n";

			$html .= "								</tr>\n";
      $html .= "								<tr>\n";
      $html .= "								  <td colspan=\"6\" width=\"%\" >\n";
			$html .= "									  <textarea name=\"observaciones\" style=\"width:100%\" rows=\"2\" class=\"textarea\">".$afiliado['observaciones']."</textarea>\n";
			$html .= "									</td>\n";		
			$html .= "								</tr>\n";
			$html .= "							</table>\n";
			$html .= "			      </td>\n";
			$html .= "			    </tr>\n";
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
      
      $html .= "<div id='Ocupacion' class='d2Container' style=\"display:none\">\n";
			$html .= "	<div id='ocupacion_titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">SELECCIONAR OCUPACION</div>\n";
			$html .= "	<div id='ocupacion_cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Ocupacion')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido_Ocupacion' class='d2Content' style=\"background:#FEFEFE\"><br>\n";
			$html .= "		<center>\n";
			$html .= "			<label id=\"error_ocupacion\" class=\"label_error\"></label>\n";
			$html .= "	  </center>\n";			
      $html .= "		<table width=\"100%\" class=\"label\" $style>\n";
			$html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >GRUPO</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"grandes_grupos\" class=\"select\" onchange=\"xajax_SeleccionarSubGrupoPrincipal(xajax.getFormValues('registrar_afiliacion'));\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($ocupacion as $key => $detalle)
        $html .= "						<option value=\"".$key."\" title=\"".$detalle['descripcion_ciuo_88_gran_grupo']."\">".substr($detalle['descripcion_ciuo_88_gran_grupo'],0,40)."</option>\n";

      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
      $html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SUBGRUPO PRINCIPAL</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"sub_grupos_principales\" class=\"select\" onChange=\"xajax_SeleccionarSubGrupos(xajax.getFormValues('registrar_afiliacion'))\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
      $html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SUBGRUPO</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"sub_grupo\" class=\"select\" onChange=\"xajax_SeleccionarGruposPrimarios(xajax.getFormValues('registrar_afiliacion'))\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
      $html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >GRUPO PRIMARIO</td>\n";
			$html .= "				<td width=\"%\" >\n";
      $html .= "					<select name=\"grupos_primarios\" class=\"select\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
			$html .= "		</table>\n";
      $html .= "    <table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	    <tr>\n";
      $html .= "		    <td align=\"center\">\n";
      $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatosOcupacion(document.registrar_afiliacion)\">\n";
			$html .= "		    </td>";
      $html .= "		    <td align=\"center\">\n";
      $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cancelar\" onclick=\ResetDatosOcupacion(document.registrar_afiliacion)\">\n";
			$html .= "		    </td>";
			$html .= "	    </tr>";
			$html .= "    </table>";
      $html .= "	</div>\n";
			$html .= "</div>\n";
			$html .= "<center><div id=\"error\" class=\"label_error\"></div></center>\n";
      
			$html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	  <tr>\n";
      $html .= "		  <td align=\"center\"><br>\n";
      $html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$html .= "		  </td>";
      $html .= "		</form>\n";
			$html .= "		<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
			$html .= "		  </td>\n";
			$html .= "		</form>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";

      $html .= "<script>\n";
			$html .= "	var contenedor = '';\n";
			$html .= "	var subcontenedor = '';\n";
			$html .= "	var titulo = '';\n";
			$html .= "	var cerrar = '';\n";
			$html .= "	var hiZ = 2;\n";
			
			$html .= "	function Iniciar(content,subcontent,tit,obj_cerrar,ancho,alto)\n";
			$html .= "	{\n";
			$html .= "		subcontenedor = subcontent;\n";
			$html .= "		contenedor = content;\n";
			$html .= "		titulo = tit;\n";
			$html .= "		cerrar = obj_cerrar;\n";
			$html .= "		ele = xGetElementById(subcontent);\n";
			$html .= "	  xResizeTo(ele,ancho,alto);\n";
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,ancho, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+10);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,(ancho-20), 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById(cerrar);\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele, (ancho - 20), 0);\n";
			$html .= "	}\n";

			$html .= "	function OcultarSpan(Seccion)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById(Seccion);\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xShow(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function OcultarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xHide(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan(Seccion)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById(Seccion);\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
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
      
      $html .= "	function evaluarDatosObligatorios(objeto)\n";
			$html .= "	{\n";
			$html .= "		div_msj = document.getElementById('error');\n";
			$html .= "		sub_obliga = new Array();\n";
			$html .= "		sub_obliga[0] = new Array();\n";
			$html .= "		sub_obliga[1] = new Array();\n";
			$html .= "		obligatorios = new Array();\n";
			$html .= $valida."\n";
			$html .= "		for(i=0; i< $i; i++)\n";
			$html .= "		{\n";
			$html .= "			if(obligatorios[i][1] == '1')\n";
			$html .= "			{\n";
      $html .= "				switch(obligatorios[i][3])\n";
			$html .= "				{\n";
			$html .= "				  case 'select':\n";
			$html .= "				    if(obligatorios[i][0] == '-1')\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = 'SE DEBE SELECCIONAR '+obligatorios[i][2]+'';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'date':\n";
			$html .= "				    if(!IsDate(obligatorios[i][0]))\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = obligatorios[i][2]+', ES OBLIGATORIA O EL FORMATO NO CORRESPONDE';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'numeric':\n";
			$html .= "				    if(!IsNumeric(obligatorios[i][0]))\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = obligatorios[i][2]+', ES OBLIGATORIO O EL FORMATO NO CORRESPONDE';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'text':\n";
			$html .= "				    if(obligatorios[i][0] == '' || obligatorios[i][0] == undefined)\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = obligatorios[i][2]+', ES OBLIGATORIO';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";
      
      $fII = explode("/",$afiliado['fecha_vencimiento_ctz']);
			$html .= "	  var fecha_validacion = new Date('".date("Y/m/d")."');\n";
			$html .= "	  var fecha_validacionII = new Date('".$afiliado['fecha_vencimiento_ctz']."');\n";
      $html .= "		for(i=0; i< $i; i++)\n";
			$html .= "		{\n";
      $html .= "			if(obligatorios[i][3] == 'date' && obligatorios[i][0] != '')\n";
			$html .= "			{\n";
			$html .= "				if(!IsDate(obligatorios[i][0]))\n";
			$html .= "				{\n";
      $html .= "				  div_msj.innerHTML = obligatorios[i][2]+',FORMATO NO CORRESPONDE';\n";
			$html .= "				  return;\n";
			$html .= "				}\n";
      $html .= "				if(obligatorios[i][4] == 1)\n";
			$html .= "				{\n";
      $html .= "	        f = obligatorios[i][0].split('/')\n";
			$html .= "	        f1 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
      $html .= "          if(f1 > fecha_validacion)\n";
      $html .= "				  {\n";
      $html .= "				    div_msj.innerHTML = obligatorios[i][2]+', NO PUEDE SER SUPERIOR A: ".date("d/m/Y")."';\n";
			$html .= "				    return;\n";
			$html .= "				  }\n";
			$html .= "				}\n";      
      $html .= "				if(obligatorios[i][4] == 2)\n";
			$html .= "				{\n";
      $html .= "	        f = obligatorios[i][0].split('/')\n";
			$html .= "	        f1 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
      $html .= "          if(f1 > fecha_validacionII)\n";
      $html .= "				  {\n";
      $html .= "				    div_msj.innerHTML = obligatorios[i][2]+', NO PUEDE SER SUPERIOR A: ".$fII[2]."/".$fII[1]."/".$fII[0]."';\n";
			$html .= "				    return;\n";
			$html .= "				  }\n";
			$html .= "				}\n";
			$html .= "			}\n";
      $html .= "			else if(obligatorios[i][3] =='numeric'&& obligatorios[i][0] != '')\n";
      $html .= "			{\n";
			$html .= "			  if(!IsNumeric(obligatorios[i][0]))\n";
			$html .= "				{\n";
      $html .= "				  div_msj.innerHTML = obligatorios[i][2]+', FORMATO NO CORRESPONDE';\n";
			$html .= "					return;\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";  
      
      $html .= "		if(!objeto.tipo_sexo[0].checked && !objeto.tipo_sexo[1].checked)\n";
			$html .= "		{\n";
      $html .= "		   div_msj.innerHTML = 'SE DEBE SELECCIONAR EL TIPO DE SEXO';\n";
			$html .= "		   return;\n";
			$html .= "		}\n";
      $html .= "		if(!objeto.zona_residencia[0].checked && !objeto.zona_residencia[1].checked)\n";
			$html .= "		{\n";
      $html .= "		   div_msj.innerHTML = 'SE DEBE SELECCIONAR LA ZONA DE RESIDENCIA';\n";
			$html .= "		   return;\n";
			$html .= "		}\n";
      $html .= "    if(objeto.tipo_id_beneficiario.value != '".$afiliado['afiliado_tipo_id']."' || ";
      $html .= "        objeto.documento.value != '".$afiliado['afiliado_id']."')\n ";
			$html .= "	  {\n";
			$html .= "	    xajax_ValidarAfiliado(objeto.tipo_id_beneficiario.value,objeto.documento.value);\n";
			$html .= "	  }\n";
			$html .= "	  Continuar();\n";
			$html .= "	}\n";
			$html .= "	function Continuar()\n";
			$html .= "	{\n";
 			$html .= "		document.getElementById('error').innerHTML = '';\n";
			$html .= "		document.registrar_afiliacion.action = '".$action['crear']."';\n";
			$html .= "		document.registrar_afiliacion.submit();\n";
			$html .= "	}\n";
      
      $html .= "	function finMes(nMes)\n";
			$html .= "	{\n";
			$html .= "		var nRes = 0;\n";
			$html .= "		switch (nMes)\n";
			$html .= "		{\n";
			$html .= "			case '01': nRes = 31; break;\n";
			$html .= "			case '02': nRes = 29; break;\n";
			$html .= "			case '03': nRes = 31; break;\n";
			$html .= "			case '04': nRes = 30; break;\n";
			$html .= "			case '05': nRes = 31; break;\n";
			$html .= "			case '06': nRes = 30; break;\n";
			$html .= "			case '07': nRes = 31; break;\n";
			$html .= "			case '08': nRes = 31; break;\n";
			$html .= "			case '09': nRes = 30; break;\n";
			$html .= "			case '10': nRes = 31; break;\n";
			$html .= "			case '11': nRes = 30; break;\n";
			$html .= "			case '12': nRes = 31; break;\n";
			$html .= "		}\n";
			$html .= "		return nRes;\n";
			$html .= "	}\n";
			$html .= "	function IsDate(fecha)\n";
			$html .= "	{\n";
			$html .= "		if(fecha == '' || fecha == undefined)	return false;\n";
			$html .= "		var bol = true;\n";
			$html .= "		var arr = fecha.split('/');\n";
			$html .= "		if(arr.length > 3)\n";
			$html .= "			return false;\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "			bol = bol && (IsNumeric(arr[0]));\n";
			$html .= "			bol = bol && (IsNumeric(arr[1]));\n";
			$html .= "			bol = bol && (IsNumeric(arr[2]));\n";
			$html .= "			bol = bol && ((arr[1] >= 1) && (arr[1] <= 12));\n";
			$html .= "			bol = bol && (arr[0] <= finMes(arr[1]));\n";
			$html .= "			return bol;\n";
			$html .= "		}\n";
			$html .= "	}\n";
      $html .= "	function IsNumeric(valor)\n";
			$html .= "	{\n";
			$html .= "		var log = valor.length; \n";
			$html .= "		var sw='S';\n";
			$html .= "		var puntos = 0;\n";
			$html .= "		for (x=0; x<log; x++)\n";
			$html .= "		{ \n";
			$html .= "			v1 = valor.substr(x,1);\n";
			$html .= "			v2 = parseInt(v1);\n";
			$html .= "			//Compruebo si es un valor num?ico\n";
			$html .= "			if(v1 == '.')\n";
			$html .= "			{\n";
			$html .= "				puntos ++;\n";
			$html .= "			}\n";
			$html .= "			else if (isNaN(v2)) \n";
			$html .= "			{ \n";
			$html .= "				sw= 'N';\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(log == 0) sw = 'N';\n";
			$html .= "		if(puntos > 1) sw = 'N';\n";
			$html .= "		if(sw=='S')\n"; 
			$html .= "			return true;\n";
			$html .= "		return false;\n";
			$html .= "	} \n";
			$html .= "</script>\n";
      $html .= "<script>\n";
      if($afiliado['ciuo_88_grupo_primario'] !== null)
      {
        $html .= "  xajax_SeleccionarDatosDefecto(xajax.getFormValues('registrar_afiliacion'),'','".$afiliado['ciuo_88_grupo_primario']."');\n";
      }
      $html .= "  xajax_MostrarInformacionPlan(xajax.getFormValues('registrar_afiliacion'));\n";
      $html .= "</script>\n";
			$html .= ThemeCerrarTabla();	
			return $html;
		}
    /**
		* Funcion donde se crea la forma para hacer el cambio de estado del afiliado
    *
		* @param array $action Vector con los links de la aplicion
		* @param array $datos Vector con los datos del afiliado
		* @param array $estados Vector que contiene los estados posibles a loa que puede pasar un afiliado
    *
		* @return String
		*/
    function FormaModificarEstadoAfiliado($action,$datos,$estados)
    {
      $html  = ThemeAbrirTabla('CAMBIAR ESTADO AFILIADO');
 			$html .= "<center><div id=\"error\" class=\"label_error\">&nbsp;</div></center>\n";
			$html .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td colspan=\"2\" class=\"formulacion_table_list\">AFILIADO</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"label\" align=\"center\">\n";
      $html .= "			<td class=\"modulo_list_claro\" width=\"35%\">\n";
			$html .= "			  ".$datos['afiliado_tipo_id']." ".$datos['afiliado_id']."\n";          
			$html .= "			</td>\n";          
      $html .= "			<td class=\"modulo_list_claro\">\n";
			$html .= "			  ".trim($datos['apellidos']." ".$datos['nombres'])."\n";          
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td align=\"left\" >\n";
			$html .= "			  ESTADO ACTUAL\n";          
			$html .= "			</td>\n";          
      $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "			  ".$datos['descripcion_estado']."\n";          
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td align=\"left\" >\n";
			$html .= "			  SUBESTADO ACTUAL\n";          
			$html .= "			</td>\n";          
      $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "			  ".$datos['descripcion_subestado']."\n";          
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table><br>\n";
			$html .= "<form name=\"cambiarestado\" id=\"cambiarestado\" action=\"javascript:evaluarDatos(document.cambiarestado)\" method=\"post\">\n";
			$html .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	  <tr>\n";
			$html .= "		  <td width=\"35%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">ESTADO AFILIADO</td>\n";
			$html .= "			<td class=\"modulo_list_claro\">\n";
			$html .= "			  <select name=\"estado_afiliado_id\" class=\"select\" onchange=\"xajax_CambiarSubEstados(xajax.getFormValues('cambiarestado'),'')\">\n";
			$html .= "				  <option value=\"-1\">---Seleccionar---</option>\n";
      $sl = $scp = "";
      foreach($estados as $key => $detalle)
      {
        ($key == $datos['estado_afiliado_id'])? $sl = "selected":$sl = "";
        $html .= "				  <option value=\"".$key."\" $sl>".$detalle['descripcion_estado']."</option>\n";
        if($detalle['mensaje_confirmar_afiliacion'])
        {
          $scp .= "    if(frm.estado_afiliado_id.value == '".$key."')\n";
          $scp .= "    {  alert('".$detalle['mensaje_confirmar_afiliacion']."'); }\n";
        }
      }
      $html .= "			  </select>\n";
      $html .= "			</td>\n";
      $html .= "	  </tr>\n";
      $html .= "	  <tr>\n";
			$html .= "		  <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">SUBESTADO AFILIADO</td>\n";
			$html .= "			<td class=\"modulo_list_claro\">\n";
			$html .= "			  <select name=\"subestado_afiliado_id\" class=\"select\">\n";
			$html .= "				  <option value=\"-1\">---Seleccionar---</option>\n";
      $html .= "			  </select>\n";
      $html .= "			</td>\n";
      $html .= "	  </tr>\n";
      $html .= "	</table>\n";
 			$html .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "	  <tr class=\"formulacion_table_list\">\n";
      $html .= "	    <td>OBSERVACIONES</td>\n";
      $html .= "	  </tr>\n";
      $html .= "	  <tr class=\"formulacion_table_list\">\n";
      $html .= "	    <td>\n";
      $html .= "	      <textarea name=\"observacion\" style=\"width:100%\" rows=\"3\" class=\"textarea\"></textarea>\n";
      $html .= "      </td>\n";
      $html .= "	  </tr>\n";
      $html .= "	</table>\n";
			$html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	  <tr>\n";
      $html .= "		  <td align=\"center\"><br>\n";
      $html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$html .= "		  </td>";
      $html .= "		</form>\n";
			$html .= "		<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
			$html .= "		  </td>\n";
			$html .= "		</form>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<script>\n";
			$html .= "  function evaluarDatos(frm)\n";
			$html .= "  {\n";
			$html .= "    divMsg = document.getElementById('error');\n";
			$html .= "    if(frm.estado_afiliado_id.value == '-1')\n";
			$html .= "    {\n";
			$html .= "      divMsg.innerHTML = 'SE DEBE SELECCIONAR EL NUEVO ESTADO'\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    else if(frm.subestado_afiliado_id.value == '-1')\n";
			$html .= "    {\n";
			$html .= "      divMsg.innerHTML = 'SE DEBE SELECCIONAR EL NUEVO SUBESTADO'\n";
			$html .= "      return;\n";
			$html .= "    }\n";
      $html .= "    if(frm.estado_afiliado_id.value == '".$datos['estado_afiliado_id']."' && frm.subestado_afiliado_id.value == '".$datos['subestado_afiliado_id']."')\n";
			$html .= "    {\n";
			$html .= "      divMsg.innerHTML = 'NO SE HA ESPECIFICADO UN CAMBIO DE ESTADOS';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
      $html .= $scp;
      $html .= "    divMsg.innerHTML = '&nbsp;';\n";
 			$html .= "		document.getElementById('error').innerHTML = '';\n";
			$html .= "		document.cambiarestado.action = '".$action['actualizar']."';\n";
			$html .= "		document.cambiarestado.submit();\n";
			$html .= "  }\n";

			$html .= "</script>\n";
      
      $html .= "<script>\n";
      $html .= "  xajax_CambiarSubEstados(xajax.getFormValues('cambiarestado'),'".$datos['subestado_afiliado_id']."');\n";
      $html .= "</script>\n";

      $html .= ThemeCerrarTabla();	
      return $html;
    }
  }
?>