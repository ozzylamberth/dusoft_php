<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: ListadosHTML.class.php,v 1.3 2009/09/30 12:52:36 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ModificarDatosAfiliadosHTML
  * Clase encargada de crear las formas para mostrar la lista de los afiliados y 
  * los formularios para el cambio de informacion y los esatados y subestados de los mismos
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ListadosHTML
  {
    /**
    * Constructor de la clase
    */
    function ListadosHTML(){}
    /**
    * Funcion donde se crea la forma que muestra el buscador y la lista de los afiliados
    *
		* @param array $action Vector de links de la aplicaion
		* @param array $request Vector de datos del request
		* @param array $tipos_documento Vector con los tipos de documentos
		* @param array $estamentos Vector con los estametos
    * @param array $afiliados Vector con los datos de los afiliados encontrado, segun los criterios de busqueda
    * @param int   $pagina Numero de la pagina que se esta visualizando
    * @param int   $conteo Numero total de registros encontrado (Se usa para el paginador)
    * @param string $msgError Mensaje de error, si lo hay 
    *
    * @return String $html
    */
    function FormaListadoEstudiantes($action,$request,$tipos_documento,$estamentos,$afiliados = array(),$pagina,$conteo,$msgError)
    {
      $tipo_beneficiarios = array();
      $tipo_beneficiarios[1] = "ESTUDIANTES UNIVERSIDAD" ;
      $tipo_beneficiarios[2] = "NO ESTUDIANTES" ;
      $tipo_beneficiarios[3] = "ESTUDIANTES NOCTURNOS" ;
      $tipo_beneficiarios[4] = "ESTUDIANTE POSTGRADO" ;
      $tipo_beneficiarios[5] = "PAGO FINANCIERTO" ;
      $tipo_beneficiarios[6] = "ESTUDIANTE TRABAJA" ;
      
      $periodos = array();
      $periodos[1] = "PERIODO ACTIVO" ;
      $periodos[2] = "PERIODO VENCIDO" ;
      $periodos[3] = "SIN PERIODO REGISTRADo" ;
      
      $ctl = AutoCarga::factory("ClaseUtil"); 
      $html .= $ctl->LimpiarCampos();
      $html .= $ctl->RollOverFilas();
      $html .= ThemeAbrirTabla('RETIRAR BENEFICIARIOS ( MAYORIA DE EDAD )');
      $html .= "<script>\n";
			$html .= "	function Continuar(frm)\n";
			$html .= "	{\n";
			$html .= "		frm.submit();\n";
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
      $html .= "	function Habilitar(valor)\n";
			$html .= "	{\n";
			$html .= "		if(valor == '6')\n";
			$html .= "		  document.getElementById('edad_maxima').style.display = 'block'\n";
			$html .= "		else\n";
			$html .= "		  document.getElementById('edad_maxima').style.display = 'none'\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<form name=\"formabuscar\" action=\"".$action['buscar']."\" method=\"post\">\n";
			$html .= "	<center>\n";
			$html .= "		<fieldset style=\"width:80%\" class=\"fieldset\"><legend class=\"label\">CRITERIOS DE BUSQUEDA</legend>\n";
			$html .= "    	<table border=\"0\" width=\"100%\" align=\"center\">\n";
			$html .= "        <tr>\n";
			$html .= "        	<td class=\"normal_10AN\" width=\"18%\">TIPO DOCUMENTO: </td>\n";
			$html .= "          <td width=\"32%\" colspan=\"2\">\n";
			$html .= "          	<select name=\"buscador[afiliado_tipo_id]\" class=\"select\">\n";
			$html .= "            	<option value=\"-1\">-------SELECCIONE-------</option>";
			$slt = "";
			foreach($tipos_documento as $key => $ids)
			{
				($request['afiliado_tipo_id'] == $ids['tipo_id_paciente'])? $slt= "selected":$slt = "";
				$html .= "            	<option value=\"".$ids['tipo_id_paciente']."\" $slt>".$ids['descripcion']."</option>";
			}
			$html .= "            </select>\n";
			$html .= "          </td>\n";
			$html .= "          <td width=\"18%\" class=\"normal_10AN\">DOCUMENTO: </td>\n";
			$html .= "          <td>\n";
			$html .= "          	<input type=\"text\" class=\"input-text\" name=\"buscador[afiliado_id]\" maxlength=\"32\" value=\"".$request['afiliado_id']."\">\n";
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
      
      /*$html .= "        <tr>\n";
			$html .= "        	<td class=\"normal_10AN\">CODIGO ESTUDIANTE:</td>\n";
			$html .= "          <td>\n";
			$html .= "          	<input type=\"text\" class=\"input-text\" name=\"buscador[codigo]\" style=\"width:94%\" maxlength=\"64\" value=\"".$request['codigo']."\">\n";
			$html .= "          </td>\n";
			$html .= "          <td colspan=\"2\">&nbsp;</td>\n";
			/*$html .= "          <td class=\"normal_10AN\">TIPO</td>\n";
      $html .= "          <td>\n";
      $html .= "          	<select name=\"buscador[tipo_beneficiario]\" class=\"select\">\n";
			$html .= "            	<option value=\"-1\">-------SELECCIONE-------</option>";
			$slt = "";
			foreach($tipo_beneficiarios as $key => $ids)
			{
				($request['tipo_beneficiario'] == $key)? $slt= "selected":$slt = "";
				$html .= "            	<option value=\"".$key."\" $slt>".$ids."</option>";
			}
			$html .= "            </select>\n";
			$html .= "          </td>\n";
			$html .= "        </tr>\n";*/
      $html .= "        <tr>\n";
 			$html .= "        	<td class=\"normal_10AN\" width=\"18%\">ESTAMENTO: </td>\n";
			$html .= "          <td colspan=\"2\">\n";
			$html .= "          	<select name=\"buscador[estamento_id]\" class=\"select\">\n";
			$html .= "            	<option value=\"-1\">-------SELECCIONE-------</option>";
			
      $slt = "";
			($request['estamento_id'] == "B")? $slt= "selected":$slt = "";
      
      $html .= "            	<option value=\"B\" $slt>BENEFICIARIO</option>";
			foreach($estamentos as $key => $ids)
			{
				($request['estamento_id'] == $ids['estamento_id'])? $slt= "selected":$slt = "";
				$html .= "            	<option value=\"".$ids['estamento_id']."\" $slt>".$ids['descripcion_estamento']."</option>";
			}
			$html .= "            </select>\n";
			$html .= "          </td>\n";
      
      $html .= "        	<td class=\"normal_10AN\" width=\"18%\">TIPO PERIODOS: </td>\n";
			$html .= "          <td width=\"32%\" colspan=\"2\">\n";
			$html .= "          	<select name=\"buscador[periodo]\" class=\"select\">\n";
			$html .= "            	<option value=\"-1\">-------SELECCIONE-------</option>";
			$slt = "";
			foreach($periodos as $key => $ids)
			{
				($request['periodo'] == $key)? $slt= "selected":$slt = "";
				$html .= "            	<option value=\"".$key."\" $slt>".$ids."</option>";
			}
			$html .= "            </select>\n";
			$html .= "          </td>\n";
			$html .= "        </tr>\n";
      $sl1[$request['edad_signo']] = "selected";
      $html .= "        <tr>\n";
 			$html .= "        	<td class=\"normal_10AN\" >EDAD: </td>\n";
			$html .= "          <td class=\"normal_10AN\" width=\"35%\">\n";
			$html .= "          	<select name=\"buscador[edad_signo]\" class=\"select\" onChange=\"Habilitar(this.value)\">\n";
      $html .= "            	<option value=\"1\" ".$sl1[1]."> = </option>\n";
      $html .= "            	<option value=\"2\" ".$sl1[2]."> > </option>\n";
      $html .= "            	<option value=\"3\" ".$sl1[3]."> >=</option>\n";
      $html .= "            	<option value=\"4\" ".$sl1[4]."> < </option>\n";      
      $html .= "            	<option value=\"5\" ".$sl1[5]."> <=</option>\n";
      $html .= "            	<option value=\"6\" ".$sl1[6].">entre</option>\n";
			$html .= "            </select>\n";
 			$html .= "          	<input type=\"text\" class=\"input-text\" name=\"buscador[edad]\" style=\"width:30%\" onkeypress=\"return acceptNum(event)\" value=\"".$request['edad']."\">\n";
 			$html .= "          </td>\n";
 			$html .= "          <td  class=\"normal_10AN\">\n";
      $html .= "            <div id=\"edad_maxima\" style=\"display:".(($sl1[6] != "")? "block":"none")."\">\n";
      $html .= "          	   Y <input type=\"text\" class=\"input-text\" name=\"buscador[edad_maxima]\" style=\"width:50%\" onkeypress=\"return acceptNum(event)\" value=\"".$request['edad_maxima']."\">\n";
			$html .= "            </div>\n";
			$html .= "          </td>\n";
			$html .= "          <td colspan=\"2\">&nbsp;</td>\n";
			$html .= "        </tr>\n";
			$html .= "        <tr>\n";
			$html .= "         	<td colspan = '5' align=\"center\" >\n";
			$html .= "          	<table width=\"70%\">\n";
			$html .= "             	<tr align=\"center\">\n";
			$html .= "               	<td >\n";
			$html .= "                 	<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "                </td>\n";
			$html .= "                <td>\n";
			$html .= "                 	<input class=\"input-submit\" type=\"button\" onclick=\"LimpiarCampos(document.formabuscar);Habilitar(1)\" name=\"Limpiar\" value=\"Limpiar Campos\">\n";
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
        $rpt = new GetReports();
        $request['usuario_id'] = UserGetUID();
        $mst = $rpt->GetJavaReport('app','UV_AfiliadosEstudiantes','Estudiantes',$request,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $fnc = $rpt->GetJavaFunction();
        
        $html .= $mst;
        $html .= "<center>\n";
        $html .= "  <a title=\"REPORTE CONSULTA\" href=\"javascript:".$fnc."\" class=\"label_error\" >\n";
        $html .= "    <img src=\"".GetThemePath()."/images/imprimir.png\" border='0' >REPORTE CONSULTA\n";
        $html .= "  </a>\n";
        $html .= "</center><br>\n";

   			$html .= "<form name=\"formalista\" action=\"".$action['retirar']."\" method=\"post\">\n";
        $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "	  <tr class=\"formulacion_table_list\">\n";
				$html .= "		  <td width=\"5%\" >Nº AFI.</td>\n";
				$html .= "		  <td width=\"%\" colspan=\"2\">AFILIADO</td>\n";
				$html .= "			<td width=\"6%\" >EDAD</td>\n";
				//$html .= "			<td width=\"25%\" colspan=\"6\">ESTUDIANTE</td>\n";
				$html .= "			<td width=\"10%\">ESTAMENTO</td>\n";
				$html .= "			<td width=\"18%\">ESTADO - SUBESTADO</td>\n";
				$html .= "			<td width=\"1%\" ></td>\n";				
        $html .= "			<td width=\"1%\" >\n";
        $html .= "        <input type=\"checkbox\" name=\"todos\" onclick=\"SeleccionarCheckBox(document.formalista,this.checked)\">\n";
        $html .= "      </td>\n";
				$html .= "		</tr>\n";
				/*$html .= "	  <tr class=\"formulacion_table_list\">\n";
				$html .= "			<td width=\"3%\" >UNI.</td>\n";
				$html .= "			<td width=\"3%\" >POS.</td>\n";
				$html .= "			<td width=\"3%\" >FNC.</td>\n";
				$html .= "			<td width=\"3%\" >NCT.</td>\n";
				$html .= "			<td width=\"3%\" >TRA.</td>\n";
				$html .= "			<td width=\"10%\">CODIGO</td>\n";
				$html .= "		</tr>\n";*/
        
        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        $estuv = "";
				foreach($afiliados as $key => $afiliado)
				{
					($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro'; 
					($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
          
          ($afiliado['codigo_estudiante'])? $estuv = "SI": $estuv = "NO"; 
          
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "		  <td >".$afiliado['eps_afiliacion_id']."</td>\n";
					$html .= "		  <td width=\"12%\">".$afiliado['afiliado_tipo_id']." ".$afiliado['afiliado_id']."</td>\n";
					$html .= "		  <td >".$afiliado['primer_apellido']." ".$afiliado['segundo_apellido']." ".$afiliado['primer_nombre']." ".$afiliado['segundo_nombre']."</td>\n";
					$html .= "		  <td >".$afiliado['edad_afiliado']." Años</td>\n";
					/*$html .= "		  <td align=\"center\" class=\"label\">".$estuv."</td>\n";

          /*$html .= "		  <td align=\"center\" class=\"label\">";
          if($estuv == "SI")
          {
            if($afiliado['sw_estudiante_postgrado'] == '1')
              $html .= "SI";
            else
              $html .= "NO";
          }

					$html .= "		  </td>\n";
					$html .= "		  <td align=\"center\" class=\"label\">";
          if($estuv == "SI")
          {
            if($afiliado['sw_matricula_financiera'] == '1')
              $html .= "SI";
            else
              $html .= "NO";
          }
          $html .= "      </td>\n";          
          $html .= "		  <td align=\"center\" class=\"label\">";
          if($estuv == "SI")
          {
            if($afiliado['sw_estudiante_nocturno'] == '1')
              $html .= "SI";
            else
              $html .= "NO";
          }
          $html .= "      </td>\n";	          
          $html .= "		  <td align=\"center\" class=\"label\">";
          if($estuv == "SI")
          {
            if($afiliado['sw_estudiante_trabaja'] == '1')
              $html .= "SI";
            else
              $html .= "NO";
          }
          $html .= "      </td>\n";					

					$html .= "		  <td class=\"label\">".$afiliado['codigo_estudiante']."</td>\n";*/
					$html .= "		  <td class=\"label\">".$afiliado['descripcion_estamento']."</td>\n";
					$html .= "		  <td class=\"label\">".strtoupper($afiliado['descripcion_estado']." - ".$afiliado['descripcion_subestado'])."</td>\n";
  				$html .= "		  <td align=\"center\" class=\"label\">\n";
          if($afiliado['periodo'])
          {
            $f = explode("-",$afiliado['cobertura_fecha_fin']);
            $t1 = mktime(0,0,0,$f[1],$f[2],$f[0]);
            $t2 = mktime(0,0,0,date("m"),date("d"),date("Y"));

            $dd = abs(($t1 - $t2)/(60 * 60 * 24));
            
            if($afiliado['periodo'] == '1')
              $html .= "      <img src=\"".GetThemepath()."/images/pactivo.png\" title=\"PERIODO ACTIVO QUEDAN ".$dd." DIAS\">\n";
            elseif($afiliado['periodo'] == '2')
              $html .= "      <img src=\"".GetThemepath()."/images/pinactivo.png\" title=\"PERIODO INACTIVO VENCIDO HACE ".$dd." DIAS\">\n";
          }
          $html .= "      </td>\n";
          $html .= "			<td align=\"center\">\n";
          $html .= "        <input type=\"checkbox\" name=\"afi[".$afiliado['eps_afiliacion_id']."][".$afiliado['afiliado_tipo_id']."][".$afiliado['afiliado_id']."][chkbox]\" value=\"".$afiliado['eps_afiliacion_id']."\">\n";
          $html .= "        <input type=\"hidden\" name=\"afi[".$afiliado['eps_afiliacion_id']."][".$afiliado['afiliado_tipo_id']."][".$afiliado['afiliado_id']."][estado]\" value=\"".$afiliado['estado_afiliado_id']."\">\n";
          $html .= "        <input type=\"hidden\" name=\"afi[".$afiliado['eps_afiliacion_id']."][".$afiliado['afiliado_tipo_id']."][".$afiliado['afiliado_id']."][subestado]\" value=\"".$afiliado['subestado_afiliado_id']."\">\n";
          $html .= "        <input type=\"hidden\" name=\"afi[".$afiliado['eps_afiliacion_id']."][".$afiliado['afiliado_tipo_id']."][".$afiliado['afiliado_id']."][estamento]\" value=\"".$afiliado['estamento_siis']."\">\n";
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
  			$html .= "  </table>\n";
				$html .= "</form>\n";
				$html .= "<center>\n";
				$html .= "  <div id=\"error\" class=\"label_error\"></div>\n";
				$html .= "</center>\n";
        
        $chtml = AutoCarga::factory('ClaseHTML');
        $html .= "		".$chtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
    		$html .= "		<br>\n";
      }
      else
      {
       	$html .= "<center>\n";
        $html .= "	<label class=\"label_error\">LA BUSQUEDAD NO ARROJO RESULTADOS</label>\n";
        $html .= "</center>\n";
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
      //$html .= "	    xGetElementById('capaFondo1').style.display = \"none\";\n";
 			$html .= "			e = xGetElementById('ContenedorP');\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function OcultarSpanGrande()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
      //$html .= "	    xGetElementById('capaFondo1').style.display = \"none\";\n";
 			$html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			//$html .= "	    xGetElementById('capaFondo1').style.display = \"block\";\n";
 			$html .= "			e = xGetElementById('ContenedorP');\n";
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
  }
?>