<?php
	/**************************************************************************************
	* $Id: hc_AntecedentesFamiliares_HTML.php,v 1.9 2006/12/19 23:10:29 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @author 
	* @version 1.0
	* @package SIIS
	* $Id: hc_AntecedentesFamiliares_HTML.php,v 1.9 2006/12/19 23:10:29 hugo Exp $
	*
	*****************************************************************************************/
	class AntecedentesFamiliares_HTML extends AntecedentesFamiliares
	{
		/**
		* Color de fondo especial para el manejo de antecedentes
		*
		* @var text
		* @access private
		*/
		var $backcolor;
		/**
		* Color especial de la letra para el manejo de antecedentes
		*
		* @var text
		* @access private
		*/
		var $backcolorf;

		  /**
    * Esta función retorna los datos de concernientes a la version del submodulo
    * @access private
    */

    function GetVersion()
    {
      $informacion=array(
      'version'=>'1',
      'subversion'=>'0',
      'revision'=>'0',
      'fecha'=>'01/27/2005',
      'autor'=>'JAIME ANDRES VALENCIA',
      'descripcion_cambio' => '',
      'requiere_sql' => false,
      'requerimientos_adicionales' => '',
      'version_kernel' => '1.0'
      );
      return $informacion;
    }
    /////////////////////////////////////////////////////////////////
    function AntecedentesFamiliares_HTML()
		{
		    $this->AntecedentesFamiliares();//constructor del padre
		    $this->backcolor="pink";
			$this->backcolorf="#990000";
	       	return true;
		}

		function frmConsulta()
		{
			$html = "";
			$Antecedentes = $this->BusquedaAntecedentesTotal2();
			
			if(!empty($Antecedentes))
			{
				$html .= "	<table width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "		<tr><td class=\"modulo_table_list_title\"><b>ANTECEDENTES FAMILIARES</b></td></tr>\n";
				$html .= "		<tr><td>\n";
				$html .= "			<table width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "				<tr class=\"formulacion_table_list\">\n";
				$html .= "					<td align=\"center\" width=\"44%\" colspan=\"2\">ANTECEDENTES</td>\n";
				$html .= "					<td align=\"center\" width=\"%\">\n";
				$html .= "						<table width=\"100%\" class=\"formulacion_table_list\">\n";
				$html .= "							<tr>\n";
				$html .= "								<td align=\"center\" width=\"14%\" >OP</td>\n";
				$html .= "								<td align=\"center\"  width=\"%\" >DETALLE</td>\n";
				$html .= "							</tr>\n";
				$html .= "						</table>\n";
				$html .= "					</td>\n";
				$html .= "				</tr>\n";
				
				$i = 0;
				
				foreach($Antecedentes as $key => $nivel1)
				{
					$j=0;
					$columna = "";					
					
					foreach($nivel1 as $key1 => $nivel2)
					{			
						$k = 0;
						$x = 0;
						$tablaX = "";
						$tablaY = "";
						$tablaO = "";
						foreach($nivel2 as $key2 => $nivel3)
						{
							$op = "&nbsp;"; $styl = "style=\"text-transform:capitalize;\"";
							
							if($nivel3['sw_riesgo'] == '0') $op = "NO";
							else if($nivel3['sw_riesgo'] == '1') $op = "SI";
													
							if(!$nivel3['detalle'])
							{
								$k = 0;
								$op = "&nbsp;";
								$check = "&nbsp;";
								$check1 = "&nbsp;";
							}
							if($nivel3['destacar'] == '1') $styl = " style=\"font-weight : bold;text-transform:capitalize;\" ";
							
							$styl1 = " style=\"color:#000066;font-weight : bold; \" ";
							if($nivel3['riesgo'] == $nivel3['sw_riesgo']) $styl1 = " style=\"color:#C40000;font-weight : bold; \" ";
													
							if($nivel3['detalle'] != "")
								$k = 1;
								
							$tablaX .= "						<tr class=\"modulo_list_claro\">\n";
							$tablaX .= "							<td align=\"center\" $styl1 width=\"14%\" >$op</td>\n";
							$tablaX .= "							<td align=\"justify\" $styl width=\"%\" >".$nivel3['detalle']."</td>\n";
							$tablaX .= "						</tr>\n";
						}
							
						$columna .= "					<tr class=\"modulo_list_claro\">\n";
						$columna .= "						<td class=\"label\">\n";
						$columna .= "							".$nivel2[$key2]['nombre_tipo']."\n";
						$columna .= "						</td>\n";
						$columna .= "						<td>\n";
						$columna .= "							<table width=\"100%\" border=\"1\">\n";
						$columna .= "								$tablaX\n";
						$columna .= "							</table>\n";
						$columna .= "						</td>\n";
						$columna .= "					</tr>\n";
						
						$j++;
					}
					$html .= "		<tr class=\"modulo_list_claro\" >\n";
					$html .= "			<td rowspan=\"".($j+1)."\"><label class=\"label\" width=\"15%\">".$key."</label></td>\n";					
					$html .= "		</tr>\n";
					$html .= "		".$columna;
					$i++;
				}
							
				$html .= "			</table>\n";
				$html .= "		</td></tr>\n";
				$html .= "	</table>\n";
			}
			$this->salida = $html;
			return true;
		}

		function SetStyle($campo)
		{
		  if ($this->frmError[$campo]||$campo=="MensajeError")
			{
			  if ($campo=="MensajeError")
				{
				  return ("<tr><td class=\"hc_tderror\" colspan=\"3\">".$this->frmError["MensajeError"]."</td></tr>");
				}
				return ("hc_tderror");
			}
			return ("hc_tdlabel");
		}
		/*************************************************************************************************
		*
		**************************************************************************************************/
    function frmForma()
    {
			SessionSetVar("EvolucionHc",$this->evolucion);
			SessionSetVar("IngresoHc",$this->ingreso);
			SessionSetVar("RutaImg",GetThemePath());
			SessionSetVar("IdPaciente",$this->paciente);
			SessionSetVar("TipoPaciente",$this->tipoidpaciente);
			
			$pfj = $this->frmPrefijo;
			$titulo1 = $this->titulo;
			//if(empty($this->titulo)) 
      $titulo1 = "ANTECEDENTES FAMILIARES";
			
			$Antecedentes = $this->BusquedaAntecedentesTotal();
			$accion = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
			
			$estilos = "style=\"border-bottom-width:0px;border-left-width:2px;border-right-width:0px;border-top-width:0px;border-style: solid;\""; 
			
			$this->salida = ThemeAbrirTablaSubModulo($titulo1);
			$this->salida .= "	<table width=\"100%\">\n";
			$this->salida .= "		<tr><td>\n";
			$this->salida .= "			<table width=\"30%\" align=\"right\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"label_mark\" >MOSTRAR: </td>\n";
			$this->salida .= "					<td align=\"center\" class=\"label_mark\" >\n";
			$this->salida .= "						<a href=\"javascript:ActualizarOpcion('T');MostrarVisibles();MostrarOcultos();\">TODOS</a>\n";			
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td align=\"center\" class=\"label_mark\" $estilos>\n";
			$this->salida .= "						<a href=\"javascript:ActualizarOpcion('V');MostrarVisibles();EsconderOcultos();\">VISIBLES</a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td align=\"center\" class=\"label_mark\" $estilos>\n";
			$this->salida .= "						<a href=\"javascript:ActualizarOpcion('O');MostrarOcultos();EsconderVisibles();\">OCULTOS</a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "		<tr><td>\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td align=\"center\" width=\"44%\" colspan=\"2\">ANTECEDENTES</td>\n";
			$this->salida .= "					<td align=\"center\" >DETALLE</td>\n";
			$this->salida .= "				</tr>\n";
			
			$i = 0;
			$b1 = true;
			$b2 = true;
			$ocultos = "var Aocultos = new Array(";
			$visibles = "var Avisibles = new Array(";
			
			foreach($Antecedentes as $key => $nivel1)
			{
				$j=0;
				$columna = "";					
				if($i % 2 == 0)
				{
					$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
				}
				else
				{
					$estilo='modulo_list_claro'; $background = "#DDDDDD";
				}
				
				foreach($nivel1 as $key1 => $nivel2)
				{
					if($j % 2 == 0)	
					{	
						$est = 'hc_submodulo_list_oscuro'; $estX = 'hc_submodulo_list_claro'; 
					}
					else 
					{
						$est = 'hc_submodulo_list_claro'; $estX = 'hc_submodulo_list_oscuro';
					}
					
					$k = 0;
					$x = 0;
					$tabla = "";
					$tablaO = "";
					foreach($nivel2 as $key2 => $nivel3)
					{
						$op = "&nbsp;"; $styl = "style=\"text-transform:capitalize;\"";
						
						if($nivel3['sw_riesgo'] == '0') $op = "NO";
						else if($nivel3['sw_riesgo'] == '1') $op = "SI";
						
						$arregloJs = "new Array('".$nivel3['hctap']."','".$nivel3['hctad']."','$est','$estX','".$i.$j."','".$nivel3['hcid']."'";
						
						$check  = "<a href=\"javascript:CrearArregloCapas(new Array('Antecedente".$i.$j."','Ocultos".$i.$j."'));OcultarAntecedenteFami(".$arregloJs.",'1'))\" title=\"Ocultar Antecedente\">";
						$check .= "	<img src=\"".GetThemePath()."/images/checkno.png\" height=\"14\" border=\"0\"></a>";
						
						$check1  = "<a href=\"javascript:CrearArregloCapas(new Array('Antecedente".$i.$j."','Ocultos".$i.$j."'));OcultarAntecedenteFami(".$arregloJs.",'0'))\" title=\"Mostrar Antecedente\">";
						$check1 .= "	<img src=\"".GetThemePath()."/images/checkS.gif\" height=\"14\" width=\"14\" border=\"0\"></a>";
						
						if(!$nivel3['detalle'])
						{
							$k = 0;
							$op = "&nbsp;";
							$check = "&nbsp;";
							$check1 = "&nbsp;";
						}
						if($nivel3['destacar'] == '1') $styl = " style=\"font-weight : bold;text-transform:capitalize;\" ";
						
						$styl1 = " style=\"color:#000066;font-weight : bold; \" ";
						if($nivel3['riesgo'] == $nivel3['sw_riesgo']) $styl1 = " style=\"color:#C40000;font-weight : bold; \" ";
												
						if($nivel3['ocultar'] == '0')
						{
							if($nivel3['detalle'] != "")
								$k = 1;
							
							$tabla .= "						<tr class=\"$est\">\n";
							$tabla .= "							<td align=\"center\" $styl1 width=\"15%\" >$op</td>\n";
							$tabla .= "							<td align=\"justify\" $styl width=\"55%\" >".$nivel3['detalle']."</td>\n";
							$tabla .= "							<td align=\"center\" $styl width=\"20%\">".$nivel3['fecha']."</td>\n";
							$tabla .= "							<td align=\"center\" $styl width=\"10%\">$check</td>\n";
							$tabla .= "						</tr>\n";
						}
						else if($nivel3['ocultar'] == '1')
						{
							$x = 1;
							$tablaO .= "						<tr class=\"$estX\">\n";
							$tablaO .= "							<td align=\"center\" $styl1 width=\"15%\"  >$op</td>\n";
							$tablaO .= "							<td align=\"justify\" $styl width=\"55%\" >".$nivel3['detalle']."</td>\n";
							$tablaO .= "							<td align=\"center\" $styl width=\"20%\">".$nivel3['fecha']."</td>\n";
							$tablaO .= "							<td align=\"center\" $styl width=\"10%\">$check1</td>\n";
							$tablaO .= "						</tr>\n";
						}
					}
					
					$arregloJs = "new Array('".$nivel2[$key2]['hctap']."','".$nivel2[$key2]['hctad']."','$est','$estX','".$i.$j."')";
					
					$columna .= "					<tr>\n";
					$columna .= "						<td class=\"$est\">\n";
					$columna .= "							<a href=\"javascript:MostrarSpan('d2Container');Iniciar('".$nivel2[$key2]['nombre_tipo']."',new Array('Antecedente".$i.$j."','Ocultos".$i.$j."'),$arregloJs)\" class=\"label\">".$nivel2[$key2]['nombre_tipo']."</a>\n";
					$columna .= "						</td>\n";
					$columna .= "						<td height=\"17\" class=\"$est\">\n";
					$clase = "";
					
					$columna1 = "";
					if($k == 1 || $x == 1)
					{
						$columna1 .= "								<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n";
						$columna1 .= "									<tr class=\"formulacion_table_list\" >\n";
						$columna1 .= "										<td align=\"center\" width=\"15%\" >OP.</td>\n";
						$columna1 .= "										<td align=\"center\" width=\"55%\" >DETALLE</td>\n";
						$columna1 .= "										<td align=\"center\" width=\"20%\" >F. REGIS</td>\n";				
						$columna1 .= "										<td align=\"center\" width=\"10%\" >OCUL</td>\n";
						$columna1 .= "									</tr>\n";
						$columna1 .= "								</table>\n";
						
						$clase = " class=\"modulo_table_list\" bgcolor=\"#FFFFFF\"";
					}
					
					$display = "style=\"display:block\"";
					if($k==0 && $x==1) $display = "style=\"display:none\"";
					
					$columna .= "							<div id=\"XAntecedente".$i.$j."\" $display >$columna1</div>\n";
					$columna .= "								<div id=\"Antecedente".$i.$j."\" style=\"display:block\">\n";
					if($tabla != "")
					{
						$columna .= "									<table width=\"100%\" $clase>\n";
						$columna .= "										$tabla\n";
						$columna .= "									</table>\n";
					}
					$columna .= "								</div>\n";
					$columna .= "								<div id=\"Ocultos".$i.$j."\" style=\"display:none\">\n";	
					if($tablaO != "")
					{
						$columna .= "								<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n";
						$columna .= "									$tablaO\n";
						$columna .= "								</table>\n";
					}
					$columna .= "							</div>\n";
					$columna .= "						</td>\n";
					$columna .= "					</tr>\n";
					
					$b1? $visibles .= "'Antecedente".$i.$j."'": $visibles .= ",'Antecedente".$i.$j."'";
					$b1? $ocultos .= "'Ocultos".$i.$j."'": $ocultos .= ",'Ocultos".$i.$j."'";
					$b1 = false;
					
					$j++;
				}
				$this->salida .= "		<tr >\n";
				$this->salida .= "			<td rowspan=\"".($j+1)."\" class=\"$estilo\" ><label class=\"label\" width=\"15%\">".$key."</label></td>\n";					
				$this->salida .= "		</tr>\n";
				$this->salida .= "		".$columna;
				$i++;
			}
						
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "<script>\n";
			$this->salida .= "	".$ocultos.");\n";
			$this->salida .= "	".$visibles.");\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	var mensaje = '';\n";
			$this->salida .= "	var opcion = 'V';\n";
			$this->salida .= "	var datosE = new Array();\n";
			$this->salida .= "	var capaActual = new Array();\n";
			$this->salida .= "	function Iniciar(tit,capita,envios)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		datosE = envios;\n";
			$this->salida .= "		capaActual = capita;\n";
			$this->salida .= "		document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
			$this->salida .= "		document.getElementById('error').innerHTML = '';\n";
			$this->salida .= "		document.oculta.resaltar.checked = false;\n";
			$this->salida .= "		document.oculta.observacion.value = '';\n";
			$this->salida .= "		document.oculta.decision[0].checked = false;\n";
			$this->salida .= "		document.oculta.decision[1].checked = false;\n";
			$this->salida .= "		ele = xGetElementById('d2Container');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop()+24);\n";
			$this->salida .= "		ele = xGetElementById('titulo');\n";
			$this->salida .= "	  xResizeTo(ele,280, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrar');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 280, 0);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDragStart(ele, mx, my)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  window.status = '';\n";
			$this->salida .= "	  if (ele.id == 'titulo') xZIndex('d2Container', hiZ++);\n";
			$this->salida .= "	  else xZIndex(ele, hiZ++);\n";
			$this->salida .= "	  ele.myTotalMX = 0;\n";
			$this->salida .= "	  ele.myTotalMY = 0;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDrag(ele, mdx, mdy)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  if (ele.id == 'titulo') {\n";
			$this->salida .= "	    xMoveTo('d2Container', xLeft('d2Container') + mdx, xTop('d2Container') + mdy);\n";
			$this->salida .= "	  }\n";
			$this->salida .= "	  else {\n";
			$this->salida .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$this->salida .= "	  }  \n";
			$this->salida .= "	  ele.myTotalMX += mdx;\n";
			$this->salida .= "	  ele.myTotalMY += mdy;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDragEnd(ele, mx, my)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function MostrarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		e = xGetElementById(Seccion);\n";
			$this->salida .= "		e.style.display = \"\";\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function Cerrar(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		e = xGetElementById(Seccion);\n";
			$this->salida .= "		e.style.display = \"none\";\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function CrearArregloCapas(capita)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		capaActual = capita;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function EvaluarDatos(objeto)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		mensaje = '';\n";
			$this->salida .= "		var vsino = ''; \n";
			$this->salida .= "		var vresaltar = '0';\n";
			$this->salida .= "		var vobservacion = objeto.observacion.value;\n";
			$this->salida .= "		if(objeto.decision[0].checked) \n";
			$this->salida .= "			vsino = objeto.decision[0].value;\n";
			$this->salida .= "			else if(objeto.decision[1].checked)\n";
			$this->salida .= "				vsino = objeto.decision[1].value;\n";
			$this->salida .= "		if(objeto.resaltar.checked) vresaltar = objeto.resaltar.value;\n";
			$this->salida .= "		if(vsino == '')\n";
			$this->salida .= "			mensaje = 'SE DEBE ESCOGER SI, EL PACIENTE PRESENTA O NO EL ANTECENTE';\n";
			$this->salida .= "		else if(vobservacion == '')\n";
			$this->salida .= "			mensaje = 'SE DEBE INGRESAR EL DETALLE DE LA PATOLOGIA';\n";
			$this->salida .= "		document.getElementById('error').innerHTML = '<center>'+mensaje+'</center>';\n";
			$this->salida .= "		if(mensaje == '')\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			datosE[5] = vsino;\n";
			$this->salida .= "			datosE[6] = vobservacion;\n";
			$this->salida .= "			datosE[7] = vresaltar;\n";
			$this->salida .= "			CrearAntecedentesFami(datosE);\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function ActualizarAntecedentes(html)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		Cerrar('d2Container');\n";
			$this->salida .= "		resultado  = jsrsArrayFromString( html, 'ç' );";
			$this->salida .= "		document.getElementById(capaActual[0]).innerHTML = resultado[0];\n";
			$this->salida .= "		document.getElementById(capaActual[1]).innerHTML = resultado[1];\n";
			$this->salida .= "		document.getElementById('X'+capaActual[0]).innerHTML = resultado[2];\n";
			$this->salida .= "		if(opcion == 'V' && resultado[0] == \"\") Cerrar('X'+capaActual[0]);\n";
			$this->salida .= "		if(opcion == 'O' && resultado[1] == \"\") Cerrar('X'+capaActual[0]);\n";
			$this->salida .= "	}\n";
			$this->salida .= "		function MostrarOcultos()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(i=0; i<Aocultos.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Aocultos[i]);\n";
			$this->salida .= "				e.style.display = \"block\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function EsconderOcultos()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(i=0; i<Aocultos.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Aocultos[i]);\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function MostrarVisibles()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(i=0; i<Avisibles.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Avisibles[i]);\n";
			$this->salida .= "				e.style.display = \"block\";\n";
			$this->salida .= "				try\n";
			$this->salida .= "				{\n";
			$this->salida .= "					html = document.getElementById(Avisibles[i]).innerHTML;\n";
			$this->salida .= "					if( html.substring(10,11) != \"\" && opcion == 'V')\n";
			$this->salida .= "					{\n";
			$this->salida .= "						f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "						f.style.display = \"block\";\n";
			$this->salida .= "					}\n";
			$this->salida .= "					else if(opcion == 'T')\n";
			$this->salida .= "					{\n";
			$this->salida .= "						f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "						f.style.display = \"block\";\n";
			$this->salida .= "					}\n";
			$this->salida .= "					else \n";
			$this->salida .= "					{\n";
			$this->salida .= "						f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "						f.style.display = \"none\";\n";
			$this->salida .= "					}\n";
			$this->salida .= "				}\n";
			$this->salida .= "				catch(error){}\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function EsconderVisibles()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(i=0; i<Avisibles.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Avisibles[i]);\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "				html = document.getElementById(Aocultos[i]).innerHTML;";
			$this->salida .= "				if( html.substring(9,10) == \"\")\n";
			$this->salida .= "				{\n";
			$this->salida .= "					f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "					f.style.display = \"none\";\n";
			$this->salida .= "				}\n";
			$this->salida .= "				else\n";
			$this->salida .= "				{\n";
			$this->salida .= "					f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "					f.style.display = \"block\";\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function ActualizarOpcion(op)\n";
			$this->salida .= "		{opcion = op;}\n";
			$this->salida .= "</script>";
			$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='d2Contents'>\n";
			$this->salida .= "		<form name=\"oculta\" action=\"\" method=\"post\">\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td  >PRESENCIA DEL ANTECEDENTE</td>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\">\n";
			$this->salida .= "            <input type=\"radio\" name=\"decision\" value=\"1\" >SI\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\">\n";
			$this->salida .= "            <input type=\"radio\" name=\"decision\" value=\"0\" >NO\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td colspan=\"3\">DETALLE</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td colspan=\"3\">\n";
			$this->salida .= "						<textarea class=\"textarea\" name=\"observacion\" rows=\"3\" style=\"width:100%\"></textarea>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td colspan=\"3\">\n";
			$this->salida .= "						<input type=\"checkbox\" name=\"resaltar\" class=\"input-text\" value=\"1\"><b>RESALTAR</b>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td colspan=\"3\" align=\"center\">\n";
			$this->salida .= "						<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatos(document.oculta)\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			$this->salida .= ThemeCerrarTablaSubModulo();
			return true;
		}
		/*******************************************************************************
		*
		********************************************************************************/
		function frmHistoria()
		{
			$html = "";
			$Antecedentes = $this->BusquedaAntecedentesTotal2();
			
			if(!empty($Antecedentes))
			{
				$html .= "	<table width=\"100%\" border=\"1\" rules=\"none\">\n";
				$html .= "		<tr><td class=\"normal_11_menu\" align=\"center\"><b>ANTECEDENTES FAMILIARES</b></td></tr>\n";
				$html .= "		<tr><td>\n";
				$html .= "			<table width=\"100%\" border=\"1\" align=\"center\" class=\"normal_10\">\n";
				$html .= "				<tr class=\"normal_11_menu\">\n";
				$html .= "					<td align=\"center\" width=\"44%\" colspan=\"2\">ANTECEDENTES</td>\n";
				$html .= "					<td align=\"center\" width=\"%\">\n";
				$html .= "						<table width=\"100%\" border=\"0\" class=\"normal_11_menu\">\n";
				$html .= "							<tr class=\"normal_11_menu\">\n";
				$html .= "								<td align=\"center\" width=\"14%\" >OP</td>\n";
				$html .= "								<td align=\"center\"  width=\"%\" >DETALLE</td>\n";
				$html .= "							</tr>\n";
				$html .= "						</table>\n";
				$html .= "					</td>\n";
				$html .= "				</tr>\n";
				
				$i = 0;
				
				foreach($Antecedentes as $key => $nivel1)
				{
					$j=0;
					$columna = "";					
					
					foreach($nivel1 as $key1 => $nivel2)
					{			
						$k = 0;
						$x = 0;
						$tablaX = "";
						$tablaY = "";
						$tablaO = "";
						foreach($nivel2 as $key2 => $nivel3)
						{
							$op = "&nbsp;"; $styl = "style=\"text-transform:capitalize;\"";
							
							if($nivel3['sw_riesgo'] == '0') $op = "NO";
							else if($nivel3['sw_riesgo'] == '1') $op = "SI";
													
							if(!$nivel3['detalle'])
							{
								$k = 0;
								$op = "&nbsp;";
								$check = "&nbsp;";
								$check1 = "&nbsp;";
							}
							if($nivel3['destacar'] == '1') $styl = " style=\"font-weight : bold;text-transform:capitalize;\" ";
							
							$styl1 = " style=\"color:#000066;font-weight : bold; \" ";
							if($nivel3['riesgo'] == $nivel3['sw_riesgo']) $styl1 = " style=\"color:#C40000;font-weight : bold; \" ";
													
							if($nivel3['detalle'] != "")
								$k = 1;
								
							$tablaX .= "						<tr class=\"label\">\n";
							$tablaX .= "							<td align=\"center\" $styl1 width=\"14%\" >$op</td>\n";
							$tablaX .= "							<td align=\"justify\" $styl width=\"%\" >".$nivel3['detalle']."</td>\n";
							$tablaX .= "						</tr>\n";
						}
							
						$columna .= "					<tr>\n";
						$columna .= "						<td class=\"label\">\n";
						$columna .= "							".$nivel2[$key2]['nombre_tipo']."\n";
						$columna .= "						</td>\n";
						$columna .= "						<td>\n";
						$columna .= "							<table width=\"100%\" border=\"1\">\n";
						$columna .= "								$tablaX\n";
						$columna .= "							</table>\n";
						$columna .= "						</td>\n";
						$columna .= "					</tr>\n";
						
						$j++;
					}
					$html .= "		<tr >\n";
					$html .= "			<td rowspan=\"".($j+1)."\"><label class=\"label\" width=\"15%\">".$key."</label></td>\n";					
					$html .= "		</tr>\n";
					$html .= "		".$columna;
					$i++;
				}
							
				$html .= "			</table>\n";
				$html .= "		</td></tr>\n";
				$html .= "	</table>\n";
			}		
			return $html;
		}
	}
?>
