<?php
	/**************************************************************************************
	* $Id: medicamentos.php,v 1.9 2006/08/16 15:38:24 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Hugo F. Manrique
	**************************************************************************************/	

	$VISTA = "HTML";
	$_ROOT = "../../../";
	include  "../../../classes/rs_server/rs_server.class.php";
	include	 "../../../includes/enviroment.inc.php";
	class procesos_admin extends rs_server
	{
		/********************************************************************************
		*
		*********************************************************************************/
		function ActualizarFormulacion($arreglo)
		{
			$html = "";
			$result = true;
			$result = $this->IngresoMedicamento($arreglo);
			
			if($result)
			{
				$est0 = "style=\"text-indent:2pt;text-align:left;font-size:11px;\" ";
				$est1 = "style=\"text-indent:2pt;text-align:left;font-size:9px;\" ";
				$estilos = "style=\"border-bottom-width:0px;border-left-width:1px;border-right-width:0px;border-top-width:0px;border-style: solid;\""; 

				$clasesjs .= "new Array('formulacion_table_list_suspendido','formulacion_table_list',";
				$clasesjs .= "'formulacion_table_list_oscuro','formulacion_table_list_claro','label','label2')";
				
				$datos = $this->ConsultaMedicamento($arreglo[0]); 
				
				//$html .= "<div id=\"CapaFormula".$arreglo[7]."\">\n";
				$html .= "	<table id=\"Bordex".$arreglo[7]."\" align=\"center\" border=\"0\" width=\"100%\" class=\"formulacion_table_list_oscuro\">\n";
				$html .= "		<tr id=\"Formulacion0x".$arreglo[7]."\" class=\"formulacion_table_list\">\n";
				$html .= "  		<td width=\"84%\">\n";
				$html .= "				<table id=\"Formulacion1x".$arreglo[7]."\" class=\"formulacion_table_list\" >\n";
				$html .= "					<tr >\n";
				$html .= "						<td $est0 >".$datos['producto']."</td>\n";
				$html .= "						<td id=\"Formulacion2x".$arreglo[7]."\" $est1> (".$datos['principio_activo'].")</td>\n";
				$html .= "					</tr>\n";
				$html .= "				</table>\n";
				$html .= "			</td>\n";
				$html .= "			<td width=\"4%\" align=\"center\" >\n";
				$html .= "				<a href=\"javascript:EditarFormulacion('".$datos['codigo_producto']."','".$arreglo[7]."',".$arreglo[9].")\"  title=\"EDITAR\">\n";
				$html .= "					<img name =\"Editar\" height=\"18\" src=\"".$arreglo[8]."/images/edita.png\" border=\"0\" >\n";
				$html .= "				</a>\n";
				$html .= "			</td>\n";
				$html .= "			<td width=\"4%\" align=\"center\">\n";
				$html .= "				<a href=\"javascript:VisualizarHistorial('".$datos['codigo_producto']."')\"  title=\"HISTORIAL\">\n";
				$html .= "					<img name =\"Historial".$arreglo[7]."\" height=\"18\"  src=\"".$arreglo[8]."/images/HistoriaClinica1/historia_actual_osc.gif\" border=\"0\">\n";
				$html .= "				</a>\n";
				$html .= "			</td>\n";
				$html .= "			<td width=\"4%\" align=\"center\">\n";
				$html .= "				<a href=\"javascript:DatosActuales('".$arreglo[7]."',$clasesjs,'".$datos['codigo_producto']."',".$arreglo[9].");Iniciar('".$datos['producto']."');\" >\n";
				$html .= "					<img width=\"16\" height=\"18\" title=\"SUSPENDER MEDICAMENTO\" src=\"".$arreglo[8]."/images/pactivo.png\" border=\"0\" name=\"Suspender".$arreglo[7]."\" >\n";
				$html .= "				</a>\n";
				$html .= "			</td>\n";
				$html .= "			<td width=\"4%\" align=\"center\" >\n";
				$html .= "				<a href=\"javascript:Finalizar('".$datos['codigo_producto']."','".$arreglo[7]."',".$arreglo[9].",'".$datos['producto']."')\"  title=\"FINALIZAR MEDICAMENTO\">\n";
				$html .= "					<img name =\"Finalizar\" height=\"18\" src=\"".$arreglo[8]."/images/HistoriaClinica1/cerrar_claro.gif\" border=\"0\" >\n";
				$html .= "				</a>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "		<tr class=\"modulo_list_claro\">\n";
				$html .= "			<td colspan=\"5\">\n";
				$html .= "				<table width=\"100%\">\n";
				$html .= "					<tr>\n";
				$html .= "						<td width=\"60%\" valign=\"top\">\n";
				$html .= "							<table id=\"Formulacion3x1".$arreglo[7]."\" class=\"label\" >\n";
				$html .= "								<tr>\n";
				$html .= "									<td >VIA DE ADMINISTRACIÓN: </td>\n";
				$html .= "									<td colspan=\"2\">".$datos['nombre']."</td>\n";
				$html .= "								</tr>\n";
				$html .= "								<tr >\n";
				$html .= "									<td >DOSIS</td>\n";
				$html .= "									<td align=\"right\">".intval($datos['dosis'])."</td><td>".$datos['unidad_dosificacion']."</td>\n";
				$html .= "									<td align=\"left\">".$datos['frecuencia']."</td>\n";
				$html .= "								</tr>\n";				
				$html .= "								<tr >\n";
				$html .= "									<td >CANTIDAD</td>\n";
				$html .= "									<td align=\"right\">".intval($datos['cantidad'])."</td><td>".$datos['umm']."</td>\n";
				$html .= "								</tr>\n";
				$html .= "							</table>\n";
				$html .= "						</td>\n";
				$html .= "						<td width=\"40%\" valign=\"top\" $estilos>\n";
				$html .= "							<table align=\"center\" id=\"Formulacion3x2".$arreglo[7]."\" class=\"label\">\n";
				$html .= "								<tr>\n";
				$html .= "									<td align=\"center\">FORMULÓ:</td>\n";
				$html .= "								</tr>\n";
				$html .= "								<tr>\n";
				$html .= "									<td style=\" font-weight:normal\">".$datos['med_formula']."</td>\n";
				$html .= "								</tr>\n";
				
				$usuariohc = UserGetUID();
				if($datos['sw_confirmacion_formulacion'] == '0' && $datos['usuario_id'] == $usuariohc)
				{
					$html .= "								<tr>\n";
					$html .= "									<td align=\"center\"><a href=\"\" class=\"normal_10AN\">CONFIRMAR</a></td>\n";
					$html .= "								</tr>\n";
				}
				
				$html .= "							</table>\n";
				$html .= "						</td>\n";
				$html .= "					</tr>\n";	
				$html .= "				</table>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				if($datos['observacion'] != "")
				{
					$html .= "		<tr class=\"modulo_list_claro\">\n";
					$html .= "			<td colspan=\"5\">\n";
					$html .= "				<table width=\"100%\" id=\"Formulacion5x".$arreglo[7]."\" class=\"label\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td valign=\"top\" width=\"30%\">\n";
					$html .= "							OBSERVACIONES E INDICACIONES DE SUMINISTRO</td>\n";
					$html .= "						</td>\n";
					$html .= "						<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
					$html .= "							".$datos['observacion']."\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
				}
				$html .= "	</table><br>";
				//$html .= "</div>\n";
			}
			
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function IngresoMedicamento($arreglo)
		{
			$sql .= "INSERT INTO hc_formulacion_medicamentos_eventos( ";
			$sql .= "				ingreso,";
			$sql .= "				evolucion_id,";
			$sql .= "				codigo_producto,";
			$sql .= "				usuario_id,";
			$sql .= "				fecha_registro,";
			$sql .= "				observacion,"; 	
			$sql .= "				via_administracion_id,";
			$sql .= "				unidad_dosificacion,";
			$sql .= "				dosis,";
			$sql .= "				frecuencia,";
			$sql .= "				cantidad, ";
			$sql .= "				usuario_registro ";
			$sql .= "				) ";
			$sql .= "VALUES( ";
			$sql .= "				 ".SessionGetVar("IngresoHc").", ";
			$sql .= "				 ".SessionGetVar("EvolucionHc").", ";
			$sql .= "				'".$arreglo[0]."', ";
			$sql .= "				 ".$arreglo[10].",";
			$sql .= "				 NOW(),";
			$sql .= "				'".$arreglo[5]."',";
			$sql .= "				'".$arreglo[4]."',";
			$sql .= "				'".$arreglo[1]."',";
			$sql .= "				 ".$arreglo[3].",";
			$sql .= "				'".$arreglo[6]."',";
			$sql .= "				 ".$arreglo[2].", ";
			$sql .= "				 ".UserGetUID()." ";			
			$sql .= "				) ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ConsultaMedicamento($codigo)
    {
	    $sql  = "SELECT ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				HA.nombre, ";
			$sql .= "				FM.dosis, ";
			$sql .= "				FM.unidad_dosificacion, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.ingreso, ";
			$sql .= "				FM.observacion, ";
			$sql .= "				CASE WHEN FM.sw_estado = '8' THEN '0'";
			$sql .= "						ELSE FM.sw_estado END AS sw_estado, ";
			$sql .= "				FM.codigo_producto, ";
			$sql .= "				FM.via_administracion_id, ";
			$sql .= "				FM.frecuencia, ";
			$sql .= "				IF.descripcion AS forma, ";
			$sql .= "				ME.concentracion_forma_farmacologica AS cff, ";
			$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item, ";
			$sql .= "				SU.nombre AS med_formula, ";
			$sql .= "				SU.usuario_id, ";
			$sql .= "				FM.sw_confirmacion_formulacion, ";
			$sql .= "				FH.usuario_registro ";
			$sql .= "FROM 	inv_med_cod_principios_activos AS IA, ";
			$sql .= "				hc_formulacion_medicamentos FM,";
			$sql .= "				hc_formulacion_medicamentos_eventos FH,";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				inv_med_cod_forma_farmacologica AS IF, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_vias_administracion HA, ";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND		FM.ingreso = ".SessionGetVar("IngresoHc")." ";
			$sql .= "AND		FM.codigo_producto = '".$codigo."' ";
			$sql .= "AND		HA.via_administracion_id = FM.via_administracion_id ";
			$sql .= "AND 		IF.cod_forma_farmacologica = ME.cod_forma_farmacologica ";
			$sql .= "AND		FH.num_reg = FM.num_reg ";
			$sql .= "AND		SU.usuario_id = FH.usuario_id ";
			$sql .= "ORDER BY FM.sw_estado ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$medica = SessionGetVar("MedicamentosFormulados");
			$medica[$codigo] = $datos;
			$medica[$codigo]['activar'] = "1";
			
			SessionSetVar("MedicamentosFormulados",$medica);
			
			return $datos;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ActualizarMedicamento($arreglo)
		{
			$datos = $this->ConsultaMedicamento($arreglo[0]);
			$datos['sw_estado'] = $arreglo[1];
			
			$sql .= "INSERT INTO hc_formulacion_medicamentos_eventos( ";
			$sql .= "				ingreso,";
			$sql .= "				evolucion_id,";
			$sql .= "				codigo_producto,";
			$sql .= "				usuario_id,";
			$sql .= "				fecha_registro,";
			$sql .= "				observacion,"; 	
			$sql .= "				via_administracion_id,";
			$sql .= "				unidad_dosificacion,";
			$sql .= "				dosis,";
			$sql .= "				cantidad, ";
			$sql .= "				frecuencia, ";
			$sql .= "				sw_estado, ";
			$sql .= "				usuario_registro ";
			$sql .= "				) ";
			$sql .= "VALUES( ";
			$sql .= "				 ".SessionGetVar("IngresoHc").", ";
			$sql .= "				 ".SessionGetVar("EvolucionHc").", ";
			$sql .= "				'".$arreglo[0]."', ";
			$sql .= "				 ".$datos['usuario_id'].",";
			$sql .= "				 NOW(),";
			$sql .= "				'".$datos['observacion']."',";
			$sql .= "				'".$datos['via_administracion_id']."',";
			$sql .= "				'".$datos['unidad_dosificacion']."',";
			$sql .= "				 ".$datos['dosis'].",";
			$sql .= "				 ".$datos['cantidad'].", ";
			$sql .= "				'".$datos['frecuencia']."', ";
			$sql .= "				'".$arreglo[1]."',";
			$sql .= "				 ".UserGetUID()." ";
			$sql .= "				) ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = $this->ConsultaMedicamento($arreglo[0]);
			
			$html = $this->CrearMedicamentos();
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ActualizarSolucion($param)
		{		
			$datos = array();
			$solucion = $this->SolucionesFinalizadas($param[0],$param[1]);
			
			foreach($solucion as $key=> $datos);
			
			$sql  = "INSERT INTO hc_formulacion_mezclas_eventos (";
			$sql .= "			num_mezcla,";
    	$sql .= "			ingreso ,";
    	$sql .= "			evolucion_id ,";
    	$sql .= "			usuario_id ,";
    	$sql .= "			fecha_registro,";
    	$sql .= "			sw_estado ,";
    	$sql .= "			observacion,";
    	$sql .= "			volumen_infusion,";
    	$sql .= "			unidad_volumen,";
    	$sql .= "			cantidad ";
			$sql .= ")";
			$sql .= "VALUES(";
			$sql .= "			 ".$param[0].",";
    	$sql .= "			 ".SessionGetVar("IngresoHc").",";
    	$sql .= "			 ".SessionGetVar("EvolucionHc").",";
    	$sql .= "			 ".UserGetUID().",";
    	$sql .= "			NOW(),";
    	$sql .= "			'".$param[1]."',";
    	$sql .= "			'".$datos['observacion']."',";
    	$sql .= "			 ".$datos['volumen_infusion'].",";
    	$sql .= "			'".$datos['unidad_volumen']."',";
    	$sql .= "			 ".$datos['cantidad']." ";
			$sql .= ")";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$html = $this->CrearMedicamentos();
			return $html;
		}
		/*******************************************************************************
		*
		********************************************************************************/
		function SolucionesFinalizadas($numero,$estado)
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
			$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item, ";
			$sql .= "				SU.nombre AS med_formula, ";
			$sql .= "				FD.dosis, ";
			$sql .= "				FD.unidad_dosificacion, ";
			$sql .= "				SU.usuario_id ";
			$sql .= "FROM 	hc_formulacion_mezclas FM,";
			$sql .= "				hc_formulacion_mezclas_detalle FD,";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				inv_med_cod_principios_activos AS IA,";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_formulacion_mezclas_eventos FH, ";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FD.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		FD.num_mezcla = FM.num_mezcla ";
			$sql .= "AND 		FD.num_mezcla = ".$numero." ";
			$sql .= "AND		FM.ingreso = ".SessionGetVar("IngresoHc")." ";
			$sql .= "AND		FH.num_reg = FM.num_reg ";
			$sql .= "AND 		FH.usuario_id = SU.usuario_id ";
			$sql .= "ORDER BY FM.sw_estado,FD.sw_solucion DESC ";
			
 			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$sw_estado = "";
			$soluciones = array();
			$medica = SessionGetVar("SolucionesFormuladas");
			while (!$rst->EOF)
			{
				$soluciones[$rst->fields[6]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$medica[$numero] = $soluciones;
			$medica[$numero][0]['sw_estado'] = $estado;
			$medica[$numero][0]['activar'] = "1";
			
			SessionSetVar("SolucionesFormuladas",$medica);
			
			return $soluciones;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearMedicamentos($arreglo)
		{
			$html = "";
			$path = SessionGetVar("RutaImagenes");
			
			$est0 = "style=\"text-indent:2pt;text-align:left;font-size:10pt;\" ";
			$est1 = "style=\"text-indent:2pt;text-align:left;font-size:7pt;\" ";
			
			$clasesjs .= "new Array('formulacion_table_list_suspendido','formulacion_table_list',";
			$clasesjs .= "'formulacion_table_list_oscuro','formulacion_table_list_claro','label','label2')";
				
			$documentos = SessionGetVar("MedicamentosFormulados");
			
			$cl1 = array(	"formulacion_table_list","modulo_list_claro","formulacion_table_list_oscuro",
										"formulacion_table_list_suspendido","formulacion_table_list_claro",
										"hc_table_submodulo_list_title","modulo_table_list_title","label","label2");
			$cl2 = array(	"formulacion_table_list_suspendido","modulo_list_claro","formulacion_table_list_claro",
										"formulacion_table_list","formulacion_table_list_oscuro","hc_table_submodulo_list_title",
										"formulacion_table_list_suspendido","label2","label");
			$img1 = array ("historia_actual_osc.gif","pactivo.png");
			$img2 = array ("historia_actual_cla.gif","pinactivo.png");
			
			$clases = array("1"=>$cl1,"2"=>$cl2);
			$imagenes = array("1"=>$img1,"2"=>$img2);
			
			$html .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "  		<td align=\"center\">PLAN DE MEDICAMENTOS</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr>\n";
			$html .= "			<td><br>\n";
			$est0 = "style=\"text-indent:2pt;text-align:left;font-size:11px;\" ";
			$est1 = "style=\"text-indent:2pt;text-align:left;font-size:9px;\" ";
			$estilos = "style=\"border-bottom-width:0px;border-left-width:1px;border-right-width:0px;border-top-width:0px;border-style: solid;\""; 
			
			foreach($documentos as $key=>$datos)
			{
				if($datos['activar'] == "1")
				{
					$clasesjs  = "new Array('".$clases[$datos['sw_estado']][3]."','".$clases[$datos['sw_estado']][0]."'";
					$clasesjs .= ",'".$clases[$datos['sw_estado']][2]."','".$clases[$datos['sw_estado']][4]."',";
					$clasesjs .= "'".$clases[$datos['sw_estado']][7]."','".$clases[$datos['sw_estado']][8]."')";
					
					if($datos['sw_estado'] == '2')
					{
						$clasesjs  = "new Array('".$clases[$datos['sw_estado']][0]."','".$clases[$datos['sw_estado']][3]."',";
						$clasesjs .= "'".$clases[$datos['sw_estado']][4]."','".$clases[$datos['sw_estado']][2]."',";
						$clasesjs .= "'".$clases[$datos['sw_estado']][8]."','".$clases[$datos['sw_estado']][7]."')";
					}
					
					$html .= "<div id=\"CapaFormula".$key."\">\n";
					$html .= "	<table id=\"Bordex".$key."\" align=\"center\" border=\"0\" width=\"100%\" class=\"".$clases[$datos['sw_estado']][2]."\">\n";
					$html .= "		<tr id=\"Formulacion0x".$key."\" class=\"".$clases[$datos['sw_estado']][0]."\">\n";
					$html .= "  		<td width=\"84%\">\n";
					$html .= "				<table id=\"Formulacion1x".$key."\" class=\"".$clases[$datos['sw_estado']][0]."\" >\n";
					$html .= "					<tr >\n";
					$html .= "						<td $est0 >".$datos['producto']."</td>\n";
					$html .= "						<td valign=\"bottom\" id=\"Formulacion2x".$key."\" $est1> (".$datos['principio_activo'].")</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\" >\n";
					$html .= "				<a href=\"javascript:EditarFormulacion('".$datos['codigo_producto']."','".$key."',".$datos['sw_estado'].")\"  title=\"EDITAR\">\n";
					$html .= "					<img name =\"Editar\" height=\"18\" src=\"".$path."/images/edita.png\" border=\"0\" >\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:VisualizarHistorial('".$datos['codigo_producto']."')\"  title=\"HISTORIAL\">\n";
					$html .= "					<img name =\"Historial".$key."\" height=\"18\"  src=\"".$path."/images/HistoriaClinica1/".$imagenes[$datos['sw_estado']][0]."\" border=\"0\">\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:DatosActuales('".$key."',$clasesjs,'".$datos['codigo_producto']."',".$datos['sw_estado'].");Iniciar('".$datos['producto']."');\" >\n";					
					$html .= "					<img width=\"16\" height=\"18\" title=\"SUSPENDER MEDICAMENTO\" src=\"".$path."/images/".$imagenes[$datos['sw_estado']][1]."\" border=\"0\" name=\"Suspender".$key."\" >\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\" >\n";
					$html .= "				<a href=\"javascript:Finalizar('".$datos['codigo_producto']."','".$key."',".$datos['sw_estado'].",'".$datos['producto']."')\"  title=\"FINALIZAR MEDICAMENTO\">\n";
					$html .= "					<img name =\"Finalizar\" height=\"18\" src=\"".$path."/images/HistoriaClinica1/cerrar_claro.gif\" border=\"0\" >\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "		<tr class=\"".$clases[$datos['sw_estado']][1]."\">\n";
					$html .= "			<td colspan=\"5\">\n";
					$html .= "				<table width=\"100%\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td width=\"60%\" valign=\"top\">\n";
					$html .= "							<table id=\"Formulacion3x1".$key."\" class=\"".$clases[$datos['sw_estado']][7]."\" >\n";
					$html .= "								<tr>\n";
					$html .= "									<td >VIA DE ADMINISTRACIÓN: </td>\n";
					$html .= "									<td colspan=\"3\">".$datos['nombre']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr >\n";
					$html .= "									<td >DOSIS</td>\n";
					$html .= "									<td align=\"right\">".intval($datos['dosis'])."</td><td>".$datos['unidad_dosificacion']."</td>\n";
					$html .= "									<td align=\"left\">".$datos['frecuencia']."</td>\n";
					$html .= "								</tr>\n";				
					$html .= "								<tr >\n";
					$html .= "									<td >CANTIDAD</td>\n";
					$html .= "									<td align=\"right\">".intval($datos['cantidad'])."</td><td colspan=\"2\">".$datos['umm']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";
					
					$html .= "						</td>\n";
					$html .= "						<td width=\"40%\" valign=\"top\" $estilos>\n";
					$html .= "							<table align=\"center\" id=\"Formulacion3x2".$key."\" class=\"".$clases[$datos['sw_estado']][7]."\">\n";
					$html .= "								<tr>\n";
					$html .= "									<td align=\"center\">FORMULÓ:</td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr>\n";
					$html .= "									<td style=\" font-weight:normal\">".$datos['med_formula']."</td>\n";
					$html .= "								</tr>\n";
					$usuariohc = UserGetUID();
					if($datos['sw_confirmacion_formulacion'] == '0' && $datos['usuario_id'] == $usuariohc)
					{
						$html .= "								<tr>\n";
						$html .= "									<td align=\"center\"><a href=\"\" class=\"normal_10AN\">CONFIRMAR</a></td>\n";
						$html .= "								</tr>\n";
					}
					
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";					
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					
					if($datos['observacion'] != "")
					{
						$html .= "		<tr class=\"".$clases[$datos['sw_estado']][1]."\">\n";
						$html .= "			<td colspan=\"5\">\n";
						$html .= "				<table width=\"100%\" id=\"Formulacion5x".$key."\" class=\"".$clases[$datos['sw_estado']][7]."\">\n";
						$html .= "					<tr>\n";
						$html .= "						<td valign=\"top\" width=\"30%\">\n";
						$html .= "							OBSERVACIONES E INDICACIONES DE SUMINISTRO</td>\n";
						$html .= "						</td>\n";
						$html .= "						<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
						$html .= "							".$datos['observacion']."\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
						$html .= "				</table>\n";
						$html .= "			</td>\n";
						$html .= "		</tr>\n";
					}
					
					$html .= "	</table><br>";
					$html .= "</div>\n";
				}
			}
			
			$soluciones = SessionGetVar("SolucionesFormuladas");
			print_r($soluciones);
			$j = 0;
			$est0 = "style=\"text-indent:2pt;font-size:11px;\" ";
			$est1 = "style=\"text-indent:2pt;font-size:9px;\" ";
			foreach($soluciones as $key=> $nivel1)
			{
				if($nivel1[0]['activar'] == "1")
				{
					$html .= "<div id=\"CapaSolucion".$j."\">\n";
					$html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"".$clases[$nivel1[0]['sw_estado']][2]."\">\n";
					$html .= "		<tr id=\"Solucion1".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][0]."\">\n";
					$html .= "  		<td width=\"84%\">\n";
					$html .= "				<table id=\"Solucion2".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][0]."\" >\n";
					$html .= "					<tr >\n";
					$html .= "						<td valign=\"bottom\" $est0 >SOLUCION</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					
					if(SessionGetVar("tipoProfesionalhc") == '1')
					{
						$html .= "			<td width=\"4%\" align=\"center\" >\n";
						$html .= "				<a href=\"javascript:CrearEdicion('CapaSolucion".$j."','".$key."',".$nivel1[0]['sw_estado'].")\"  title=\"EDITAR\">\n";
						$html .= "					<img name =\"Editar\" height=\"18\" src=\"".$path."/images/edita.png\" border=\"0\" >\n";
						$html .= "				</a>\n";
						$html .= "			</td>\n";
					}
					
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:\"  title=\"HISTORIAL\">\n";
					$html .= "					<img name =\"HistorialS".$j."\" height=\"18\"  src=\"".$path."/images/HistoriaClinica1/".$imagenes[$nivel1[0]['sw_estado']][0]."\" border=\"0\">\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:DatosActuales(".$j.",$clasesjs,'".$key."',".$nivel1[0]['sw_estado'].");IniciarS('SOLUCION');\" >\n";
					$html .= "					<img name =\"SuspenderS".$j."\" width=\"16\" height=\"18\" title=\"SUSPENDER SOLUCION\" src=\"".$path."/images/".$imagenes[$nivel1[0]['sw_estado']][1]."\" border=\"0\">\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\" >\n";
					$html .= "				<a href=\"javascript:FinalizarS('".$key."','".$j."')\"  title=\"FINALIZAR SOLUCION\">\n";
					$html .= "					<img name =\"Finalizar\" height=\"18\" src=\"".$path."/images/HistoriaClinica1/cerrar_claro.gif\" border=\"0\" >\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "		<tr >\n";
					$html .= "			<td colspan=\"5\">\n";
					$html .= "				<table id=\"Solucion0".$j."\"  class=\"".$clases[$nivel1[0]['sw_estado']][0]."\" width=\"100%\">\n";
					foreach($nivel1 as $key0=> $nivel2)
					{
						if($nivel2['sw_solucion'] == '1') 
						{
							$html .= "					<tr>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est0 width=\"75%\">".$nivel2['producto']." <font $est1>(".$nivel2['principio_activo'].")</font></td>\n";
							$html .= "						<td valign=\"bottom\" align=\"right\" $est1 width=\"10%\">".$nivel2['dosis']."</td>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est1 width=\"15%\">".$nivel2['unidad_dosificacion']."</td>\n";
							$html .= "					</tr>\n";
						}
					}
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
						
					$html .= "		<tr>\n";
					$html .= "			<td colspan=\"5\" class=\"modulo_list_oscuro\">\n";
					$html .= "				<table id=\"Solucion3".$j."\"  class=\"".$clases[$nivel1[0]['sw_estado']][7]."\" width=\"100%\">\n";
					$key3 = 0;
					foreach($nivel1 as $key1=> $nivel2)
					{
						if($nivel2['sw_solucion'] == '0') 
						{
							$html .= "					<tr>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est0 width=\"75%\">".$nivel2['producto']." <font $est1>(".$nivel2['principio_activo'].")</font></td>\n";
							$html .= "						<td valign=\"bottom\" align=\"right\" $est1 width=\"10%\">".$nivel2['dosis']."</td>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est1 width=\"15%\">".$nivel2['unidad_dosificacion']."</td>\n";
							$html .= "					</tr>\n";
							$key3 = $key1;
						}
					}
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					
	 				$html .= "		<tr class=\"".$clases[$nivel1[0]['sw_estado']][1]."\">\n";
					$html .= "			<td colspan=\"5\">\n";
					$html .= "				<table width=\"100%\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td width=\"60%\" valign=\"top\">\n";
					$html .= "							<table id=\"Solucion41".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][7]."\" >\n";
					$html .= "								<tr>\n";
					$html .= "									<td >CANTIDAD TOTAL </td>\n";
					$html .= "									<td >".$nivel1[$key3]['cantidad']."</td><td colspan=\"2\"><b>Unidad(es)</b></td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr >\n";
					$html .= "									<td >VOLUMEN DE INFUSIÓN</td>\n";
					$html .= "									<td align=\"right\">".$nivel1[$key3]['volumen_infusion']."</td><td colspan=\"2\">".$nivel1[$key3]['unidad_volumen']."</td>\n";
					$html .= "								</tr>\n";				
					$html .= "							</table>\n";
					
					$html .= "						</td>\n";
					$html .= "						<td width=\"40%\" valign=\"top\" $estilos>\n";
					$html .= "							<table align=\"center\" id=\"Solucion42".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][7]."\" >\n";
					$html .= "								<tr>\n";
					$html .= "									<td align=\"center\">FORMULÓ:</td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr>\n";
					$html .= "									<td style=\" font-weight:normal\">".$nivel1[$key3]['med_formula']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";	
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n"; 
				
					if($nivel1[$key3]['observacion'] != "")
					{
						$html .= "		<tr class=\"".$clases[$nivel1[0]['sw_estado']][1]."\" >\n";
						$html .= "			<td colspan=\"5\" width=\"100%\" >\n";
						$html .= "				<table width=\"100%\" id=\"Solucion5".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][7]."\">\n";
						$html .= "					<tr>\n";
						$html .= "						<td valign=\"top\" width=\"30%\">\n";
						$html .= "							OBSERVACIONES E INDICACIONES DE SUMINISTRO:</td>\n";
						$html .= "						</td>\n";
						$html .= "						<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
						$html .= "							".$nivel1[$key3]['observacion']."\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
						$html .= "				</table>\n";
						$html .= "			</td>\n";
						$html .= "		</tr>\n";
					}
						
					$html .= "	</table><br>";
					$html .= "</div>\n";
					$j++;
				}
			}
			
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table><br>";
				
			return $html;
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
			$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
	$oRS = new procesos_admin( array( 'ActivarMenu'));
	$oRS->action();	
?>