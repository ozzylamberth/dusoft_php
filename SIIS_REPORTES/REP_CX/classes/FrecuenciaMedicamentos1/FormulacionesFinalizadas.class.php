<?php
	/**************************************************************************************
	* $Id: FormulacionesFinalizadas.class.php,v 1.5 2006/08/16 15:38:17 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Hugo F. Manrique
	**************************************************************************************/	

	class FormulacionesFinalizadas
	{
		var $fm;
		var $capa = "";
		var $tema = "";
		var $scripts = "";
		var $inicio = "0";
		var $indice = "0";
		var $datos =array();
		var	$soluciones = array();		
		
		function FormulacionesFinalizadas()
		{
			$this->datos['ingreso'] = $_REQUEST['ingreso'];
			$this->MedicamentosFinalizados();
			$this->SolucionesFinalizads();
		}		
		/*****************************************************************************************************
		*
		******************************************************************************************************/
		function Iniciar()
		{
			$estilo .= "border-top:	3px solid #FFFFFF;";
			$estilo .= "border-right: 3px solid	#000000;";
			$estilo .= "border-bottom: 3px solid #000000;";
			$estilo .= "border-left: 3px solid #FFFFFF;";
			
			$borde = "style=\"border-bottom-width:0px;border-left-width:0px;border-right-width:0px;border-top-width:1px;border-style: solid;\""; 
			
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/jsrsClient.js\"></script>\n";
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/VisibilidadMenuHc.js\"></script>\n";
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/Formulacion.js\"></script>\n";
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/cross-browser/x/x_core.js\"></script>\n";
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/cross-browser/x/x_drag.js\"></script>\n";
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/cross-browser/x/x_event.js\"></script>\n";

			$this->scripts .= "<script>\n";
			$this->scripts .= "		var alternar = new Array();\n";
			$this->scripts .= "		var ImgArriba = new Image(8,10);\n";
			$this->scripts .= "		var ImgAbajo = new Image(8,10);\n";
			$this->scripts .= "		ImgArriba.src = \"".GetThemePath()."/images/arriba.png\";\n";
      $this->scripts .= "		ImgAbajo.src = \"".GetThemePath()."/images/abajo.png\";\n";
			$this->scripts .= "		function CambiarImagen(indice)\n";
			$this->scripts .= "		{\n";
			$this->scripts .= "			if(alternar[indice] == undefined) alternar[indice] = 1;\n";
			$this->scripts .= "			if(alternar[indice] == 1)\n";
			$this->scripts .= "			{\n";
			$this->scripts .= "				window.document['Img'+indice].src = ImgArriba.src; \n";
			$this->scripts .= "				alternar[indice] = 0;\n";
			$this->scripts .= "			}\n";
			$this->scripts .= "			else\n";
			$this->scripts .= "			{\n";
			$this->scripts .= "				window.document['Img'+indice].src = ImgAbajo.src; \n";
			$this->scripts .= "				alternar[indice] = 1;\n";
			$this->scripts .= "			}\n";
			$this->scripts .= "		}\n";
			$this->scripts .= "		function CambiarImagenAbajo(indice)\n";
			$this->scripts .= "		{\n";
			$this->scripts .= "			window.document['Img'+indice].src = ImgAbajo.src; \n";
			$this->scripts .= "		}\n";
			$this->scripts .= "</script>\n";
			
			$flagPrimerCapa = true;
			$capas = "var secc1 = new Array(";
			
			$this->salida .= ReturnHeader('MEDICAMENTOS Y SOLUCIONES FINALIZADAS',$this->scripts);
      $this->salida .= ReturnBody()."<br>\n";
			$this->salida .= "<table width=\"99%\" border=\"0\" align=\"center\" class=\"formulacion_table_list\" >\n";
			$this->salida .= "	<tr class=\"formulacion_table_list\" >\n";
			$this->salida .= "		<td height=\"18\" align=\"center\"><b style=\"color:#ffffff\">MEDICAMENTOS Y SOLUCIONES FINALIZADAS</b></td>";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr >\n";
			$this->salida .= "		<td bgcolor=\"#f8f8f8\"><br>\n";
			
			$est0 = "style=\"text-indent:2pt;text-align:left;font-size:11px;\" ";
			$est1 = "style=\"text-indent:2pt;text-align:left;font-size:9px;\" ";
			
			$i=0;
			
			foreach($this->medica as $key=>$datos)
			{
				$this->salida .= "			<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
				$this->salida .= "				<tr class=\"modulo_table_list_title\" >\n";
				$this->salida .= "					<td width=\"%\">\n";
				$this->salida .= "						<table class=\"modulo_table_list_title\">\n";
				$this->salida .= "							<tr>\n";
				$this->salida .= "								<td valign=\"bottom\">\n";
				$this->salida .= "      						<a $est0 class=\"hcPaciente\" href=\"javascript:OcultarCapas('Fin$key','$key');CambiarImagen('$i')\">\n";
				$this->salida .= "										<img name=\"Img$i\" height=\"8\" width=\"10\" src=\"".GetThemePath()."/images/abajo.png\" border=\"0\" >\n";
				$this->salida .= "      							".$datos['producto']."\n";
				$this->salida .= "      						</a>\n";
				$this->salida .= "								</td>\n";
				$this->salida .= "								<td $est1>(".$datos['principio_activo'].")</td>\n";
				$this->salida .= "							</tr>\n";
				$this->salida .= "						</table>\n";				
				$this->salida .= "					</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "			</table>\n";
				$this->salida .= "			<div id=\"Fin$key\" style=\"display:none\">\n";
				$this->salida .= "				<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
				$this->salida .= "					<tr>\n";
				$this->salida .= "						<td>\n";				
				$this->salida .= "							<table width=\"100%\" class=\"modulo_list_claro\">\n";
				$this->salida .= "								<tr>\n";
				$this->salida .= "									<td>\n";
				$this->salida .= "										<table  widtah=\"100%\">\n";
				$this->salida .= "											<tr class=\"label\">\n";
				$this->salida .= "												<td >VIA DE ADMINISTRACIÓN: </td>\n";
				$this->salida .= "												<td colspan=\"3\"><b class=\"label_mark\">".$datos['nombre']."</b></td>\n";
				$this->salida .= "											</tr>\n";
				$this->salida .= "											<tr class=\"label\">\n";
				$this->salida .= "												<td>DOSIS</td>\n";
				$this->salida .= "												<td align=\"right\">".$datos['dosis']."</td><td>".$datos['unidad_dosificacion']."</td>\n";
				$this->salida .= "												<td >".$datos['frecuencia']."</td>\n";
				$this->salida .= "											</tr>\n";				
				$this->salida .= "											<tr class=\"label\">\n";
				$this->salida .= "												<td >CANTIDAD</td>\n";
				$this->salida .= "												<td align=\"right\">".$datos['cantidad']."</td><td colspan=\"2\">".$datos['umm']."</td>\n";
				$this->salida .= "											</tr>\n";
				$this->salida .= "										</table>\n";
				$this->salida .= "									</td>\n";
				$this->salida .= "								</tr>\n";
				
				if($datos['observacion'] != "")
				{
					$this->salida .= "		<tr class=\"modulo_list_claro\" >\n";
					$this->salida .= "			<td colspan=\"5\" width=\"100%\" $borde>\n";
					$this->salida .= "				<table width=\"100%\" class=\"label\">\n";
					$this->salida .= "					<tr>\n";
					$this->salida .= "						<td valign=\"top\" width=\"30%\">\n";
					$this->salida .= "							OBSERVACIONES E INDICACIONES DE SUMINISTRO:</td>\n";
					$this->salida .= "						</td>\n";
					$this->salida .= "						<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
					$this->salida .= "							".$datos['observacion']."\n";
					$this->salida .= "						</td>\n";
					$this->salida .= "					</tr>\n";
					$this->salida .= "				</table>\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
				}
				
				if(SessionGetVar("tipoProfesionalhc") == '1')
				{
					$this->salida .= "							<tr class=\"modulo_list_claro\" >\n";
					$this->salida .= "								<td $borde>\n";
					$this->salida .= "									<table align=\"center\" style=\"$estilo\">\n";
					$this->salida .= "										<tr>\n";
					$this->salida .= "											<td>\n";
					$this->salida .= "      									<a $est1 class=\"label_mark\" href=\"javascript:Reformular('".$datos['codigo_producto']."','1','".$datos['producto']."')\">\n";
					$this->salida .= "													<img src=\"".GetThemePath()."/images/resumen.gif\" border=\"0\">REFORMULAR\n";
					$this->salida .= "      									</a>\n";
					$this->salida .= "											</td>\n";
					$this->salida .= "										</tr>\n";
					$this->salida .= "									</table>\n";
					$this->salida .= "								</td>\n";
					$this->salida .= "							</tr>\n";
				}
				
				$this->salida .= "							</table>\n";
				$this->salida .= "						</td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "			</table>\n";
				$this->salida .= "						</div>\n";
				$flagPrimerCapa ?  $capas .= "\"Fin$key\"" : $capas .= ",\"Fin$key\"";
				$flagPrimerCapa = false;
				$i++;
			}
			
			$j=0;
			$est0 = "style=\"text-indent:2pt;font-size:11px;\" ";
			$est1 = "style=\"text-indent:2pt;font-size:9px;\" ";
			foreach($this->soluciones as $key=> $nivel1)
			{
				$nombre = "SOLUCION: ";
				foreach($nivel1 as $key1=> $nivel2)
					$nombre .= $nivel2['producto']." + ";
				
				$envio = substr($nombre,0,strlen($nombre)-3);
				
				if(strlen($nombre) <= 70) 
					$nombre = substr($nombre,0,strlen($nombre)-3);
				else
					$nombre = substr($nombre,0,70).".....";
				
				$this->salida .= "			<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
				$this->salida .= "				<tr class=\"modulo_table_title\" >\n";
				$this->salida .= "					<td width=\"86%\">\n";
				$this->salida .= "						<table>\n";
				$this->salida .= "							<tr>\n";
				$this->salida .= "								<td valign=\"bottom\">\n";
				$this->salida .= "      						<a $est0 class=\"hcPaciente\" href=\"javascript:OcultarCapas('FinS$key','S$key');CambiarImagen('$i')\">\n";
				$this->salida .= "										<img name=\"Img$i\" height=\"8\" width=\"10\" src=\"".GetThemePath()."/images/abajo.png\" border=\"0\" >\n";
				$this->salida .= "      							".$nombre."\n";
				$this->salida .= "      						</a>\n";
				$this->salida .= "								</td>\n";
				$this->salida .= "							</tr>\n";
				$this->salida .= "						</table>\n";				
				$this->salida .= "					</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "			</table>";
				$this->salida .= "			<div id=\"FinS$key\" style=\"display:none\">\n";
				$this->salida .= "				<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
 				$this->salida .= "					<tr>\n";
				$this->salida .= "						<td>\n";	
				$this->salida .= "							<table width=\"100%\" class=\"modulo_list_claro\">\n";
				$this->salida .= "								<tr align=\"left\">\n";
				$this->salida .= "									<td>\n";
				$this->salida .= "										<table align=\"left\" width=\"100%\" >\n";
				foreach($nivel1 as $key0=> $nivel2)
				{
					$this->salida .= "											<tr>\n";
					$this->salida .= "												<td class=\"label\" valign=\"bottom\" align=\"left\"  width=\"60%\">".$nivel2['producto']." <font $est1>(".$nivel2['principio_activo'].")</font></td>\n";
					$this->salida .= "												<td class=\"label\" valign=\"bottom\" style=\"font-size:9px\" align=\"right\" width=\"8%\" >".$nivel2['dosis']."</td>\n";
					$this->salida .= "												<td class=\"label\" valign=\"bottom\" style=\"font-size:9px\" align=\"left\"  width=\"12%\">".$nivel2['unidad_dosificacion']."</td>\n";
					$this->salida .= "												<td class=\"label\" valign=\"bottom\" style=\"font-size:9px\" align=\"right\" width=\"8%\" >".$nivel2['cmedicamento']."</td>\n";
					$this->salida .= "												<td class=\"label\" valign=\"bottom\" style=\"font-size:9px\" align=\"left\"  width=\"12%\">".$nivel2['umm']."</td>\n";
					$this->salida .= "											</tr>\n";
				}
				
				$this->salida .= "										</table>\n";
				$this->salida .= "									</td>\n";	
				$this->salida .= "								</tr>\n";	
				$this->salida .= "								<tr class=\"modulo_list_claro\" align=\"left\" >\n";
				$this->salida .= "									<td $borde>\n";
				$this->salida .= "										<table align=\"left\">\n";
				$this->salida .= "											<tr class=\"label\">\n";
				$this->salida .= "												<td >CANTIDAD TOTAL </td>\n";
				$this->salida .= "												<td >".$nivel1[$key1]['cantidad']."</td><td colspan=\"2\"><b>Unidad(es)</b></td>\n";
				$this->salida .= "											</tr>\n";
				$this->salida .= "											<tr class=\"label\">\n";
				$this->salida .= "												<td >VOLUMEN DE INFUSIÓN</td>\n";
				$this->salida .= "												<td align=\"right\">".$nivel1[$key1]['volumen_infusion']."</td><td colspan=\"2\">".$nivel1[$key1]['unidad_volumen']."</td>\n";
				$this->salida .= "											</tr>\n";				
				$this->salida .= "										</table>\n";
				$this->salida .= "									</td>\n";
				$this->salida .= "								</tr>\n"; 
			
				if($nivel1[$key1]['observacion'] != "")
				{
					$this->salida .= "							<tr class=\"modulo_list_claro\" >\n";
					$this->salida .= "								<td colspan=\"4\" width=\"100%\" $borde>\n";
					$this->salida .= "									<table width=\"100%\" class=\"label\">\n";
					$this->salida .= "										<tr>\n";
					$this->salida .= "											<td valign=\"top\" width=\"30%\">\n";
					$this->salida .= "												OBSERVACIONES E INDICACIONES DE SUMINISTRO:</td>\n";
					$this->salida .= "											</td>\n";
					$this->salida .= "											<td valign=\"top\" width=\"70%\" align=\"justify\" >\n";
					$this->salida .= "												".$nivel1[$key1]['observacion']."\n";
					$this->salida .= "											</td>\n";
					$this->salida .= "										</tr>\n";
					$this->salida .= "									</table>\n";
					$this->salida .= "								</td>\n";
					$this->salida .= "							</tr>\n";
				}
				
				if(SessionGetVar("tipoProfesionalhc") == '1')
				{
					$this->salida .= "							<tr class=\"modulo_list_claro\" >\n";
					$this->salida .= "								<td $borde>\n";
					$this->salida .= "									<table align=\"center\" style=\"$estilo\">\n";
					$this->salida .= "										<tr>\n";
					$this->salida .= "											<td>\n";
					$this->salida .= "      									<a $est1 class=\"label_mark\" href=\"javascript:ReformularS('".$key."','1','$envio')\">\n";
					$this->salida .= "													<img src=\"".GetThemePath()."/images/resumen.gif\" border=\"0\">REFORMULAR\n";
					$this->salida .= "      									</a>\n";
					$this->salida .= "											</td>\n";
					$this->salida .= "										</tr>\n";
					$this->salida .= "									</table>\n";
					$this->salida .= "								</td>\n";
					$this->salida .= "							</tr>\n";
				}
				
				$this->salida .= "						</table>\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "				</tr>\n";	
				$this->salida .= "			</table>";
				$this->salida .= "		</div>\n";
				$flagPrimerCapa ?  $capas .= "\"FinS$key\"" : $capas .= ",\"FinS$key\"";
				$flagPrimerCapa = false;
				$j++;
				$i++;
			}
			
			if(sizeof($this->medica) == 0 && sizeof($this->soluciones) == 0)
			{
				$this->salida .= "			<center><b class=\"label_mark\">NO HAY MEDICAMENTOS Y/O SOLUCIONES FINALIZADAS</b></center>\n";
			}
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table><br>\n";
			$this->salida .= "<script language=\"javascript\">\n";
			$this->salida .= "	".$capas.");\n";
			$this->salida .= "	function OcultarCapas(Seccion,indice)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		for(i=0; i<secc1.length; i++)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(secc1[i] != Seccion)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(secc1[i]);\n";
			$this->salida .= "				if(e.style.display != 'none') CambiarImagenAbajo(i);\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				if(Seccion != \"\") MostrarSpan(Seccion);\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function MostrarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		e = xGetElementById(Seccion);\n";
			$this->salida .= "		if(e.style.display == \"none\")\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e.style.display = \"\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		else \n";
			$this->salida .= "		{\n";
			$this->salida .= "			e.style.display = \"none\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "		function Iniciar()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			DatosObligatorias('titulo','d2Container','');\n";
			$this->salida .= "			ele = xGetElementById('d2Container');\n";
			$this->salida .= "	  	xResizeTo(ele,400, 'auto');\n";
			$this->salida .= "	  	xMoveTo(ele, xClientWidth()/4, xScrollTop()+24);\n";
			$this->salida .= "			ele = xGetElementById('titulo');\n";
			$this->salida .= "	  	xResizeTo(ele,380, 20);\n";
			$this->salida .= "			xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  	xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "			ele = xGetElementById('cerrar');\n";
			$this->salida .= "	  	xResizeTo(ele,20, 20);\n";
			$this->salida .= "			xMoveTo(ele, 380, 0);\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n\n";

			$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">REFORMULAR</div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:MostrarSpan('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='d2Contents'>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			
			
		}
		/********************************************************************
		*
		*********************************************************************/  
    function MedicamentosFinalizados()
    {
	    $sql  = "SELECT ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				HA.nombre, ";
			$sql .= "				FM.dosis, ";
			$sql .= "				FM.unidad_dosificacion, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.observacion, ";
			$sql .= "				CASE WHEN FM.sw_estado = '8' THEN '0'";
			$sql .= "						ELSE FM.sw_estado END AS sw_estado, ";
			$sql .= "				FM.codigo_producto, ";
			$sql .= "				FM.frecuencia, ";
			$sql .= "				FM.via_administracion_id ";
			$sql .= "FROM 	inv_med_cod_principios_activos AS IA, ";
			$sql .= "				hc_formulacion_medicamentos FM,";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_vias_administracion HA ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND		FM.ingreso = ".$this->datos['ingreso']." ";
			$sql .= "AND		FM.sw_estado IN ('0','8') ";
			$sql .= "AND		HA.via_administracion_id = FM.via_administracion_id ";
			$sql .= "ORDER BY producto ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			while (!$rst->EOF)
			{
				$this->medica[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
		}
		/*******************************************************************************
		*
		********************************************************************************/
		function SolucionesFinalizads()
    {
	    $sql  = "SELECT FM.num_mezcla, ";			
			$sql .= "				FM.volumen_infusion, ";
			$sql .= "				FM.unidad_volumen, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.observacion, ";
			$sql .= "				CASE WHEN FM.sw_estado = '8' THEN '0'";
			$sql .= "						ELSE FM.sw_estado END AS sw_estado, ";
			$sql .= "				FD.codigo_producto,";
			$sql .= "				FD.sw_solucion, ";
			$sql .= "				FD.cantidad as cmedicamento, ";
	    $sql .= "				ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				FD.dosis, ";
			$sql .= "				FD.unidad_dosificacion ";
			$sql .= "FROM 	hc_formulacion_mezclas FM,";
			$sql .= "				hc_formulacion_mezclas_detalle FD,";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				inv_med_cod_principios_activos AS IA,";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id) ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FD.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		FD.num_mezcla = FM.num_mezcla ";
			$sql .= "AND		FM.sw_estado iN ('0','8') ";
			$sql .= "AND		FM.ingreso = ".$this->datos['ingreso']." ";
			$sql .= "ORDER BY FM.sw_estado,FD.sw_solucion DESC ";
			
 			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			while (!$rst->EOF)
			{
				$this->soluciones[$rst->fields[0]][$rst->fields[6]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
		}
		/********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*********************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug = true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
	$VISTA='HTML';
	$_ROOT='../../';
	include $_ROOT.'includes/enviroment.inc.php';
		
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	IncludeFile($fileName);
	
	$edit = new FormulacionesFinalizadas();
	$edit->Iniciar();
	echo $edit->salida; 
?>