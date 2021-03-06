<?php
	/**************************************************************************************
	* $Id: procesos.php,v 1.15 2007/01/05 22:27:57 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Hugo F. Manrique
	**************************************************************************************/
	$VISTA = "HTML";
	$_ROOT = "../../";
	include  "../../classes/rs_server/rs_server.class.php";
	include	 "../../includes/enviroment.inc.php";
	class procesos_admin extends rs_server
	{
		var $vias_id = "";
		function ActivarMenu($datoshc)
		{
			$usuario = str_replace("\'","",$datoshc[0]);
			if( $usuario == $datoshc[0]) $usuario = str_replace("'","",$datoshc[0]);
			
			$evolucion = str_replace("\'","",$datoshc[1]);
			if($evolucion == $datoshc[1])
				$evolucion = str_replace("\'","",$datoshc[1]);
				
			if($_SESSION['HC_EVOLUCION'][$usuario][$evolucion]['datosAdicionales']['ocultar_menu'] == 2)
				$_SESSION['HC_EVOLUCION'][$usuario][$evolucion]['datosAdicionales']['ocultar_menu'] = 1;
			else
				$_SESSION['HC_EVOLUCION'][$usuario][$evolucion]['datosAdicionales']['ocultar_menu'] = 2;

			return "Done";
		}

		function CrearTabla($arreglo)
		{
			$medica = SessionGetVar("MedicamentosFormulados");

			$codigo = $arreglo[0];
			$opcion = $arreglo[1];
			$path = $arreglo[2];

			$codigos = SessionGetVar("CodigosSeleccionados");

			if($opcion == '1')
				unset($codigos[$codigo]);
			else
				$codigos[$codigo] = $codigo;

			$sw = "0";

			if(sizeof($medica[$arreglo[0]]) > 1 && $arreglo[1] == '0')
			{
				$html = "<center><b class=\"label_error\">EL MEDICAMENTO ".$medica[$arreglo[0]]['producto']." YA HA SIDO FORMULADO<b></center>";
				$sw = "1";
			}
			else
			{
				$estilo .= "border-top:	3px solid #FFFFFF;";
				$estilo .= "border-right: 3px solid	#000000;";
				$estilo .= "border-bottom: 3px solid #000000;";
				$estilo .= "border-left: 3px solid #FFFFFF;";

				SessionSetVar("CodigosSeleccionados",$codigos);
				$datos = SessionGetVar("MedicamentosSeleccionados");
				$html .= "<form name=\"formulacion\" method=\"\">\n";
				$html .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
				$html .= "		<tr class=\"modulo_table_list_title\">\n";
				$html .= "  		<td align=\"center\" colspan=\"7\" height=\"16\">FORMULACI?N DE MEDICAMENTOS</td>\n";
				$html .= "		</tr>\n";
				$html .= "		<tr class=\"modulo_table_list_title\" >\n";
				$html .= "			<td colspan=\"2\"  align=\"center\" >PRODUCTO</td>\n";
				$html .= "			<td align=\"center\" >PRINCIPIO ACTIVO</td>\n";
				$html .= "			<td width=\"12%\" style=\"text-indent:0pt;\" align=\"center\" >CONCENTRACI?N</td>\n";
				$html .= "			<td align=\"center\" >FORMA</td>\n";
				$html .= "			<td width=\"1%\"  style=\"text-indent:0pt;\" align=\"center\" >OPC</td>\n";
				$html .= "		</tr>\n";

				foreach($codigos as $key )
				{
					$html .= "		<tr class=\"hc_table_submodulo_list_title\" >\n";
					$html .= "			<td class=\"normal_10AN\" align=\"center\" width=\"6%\">".$datos[$key]['item']."</td>\n";
					$html .= "			<td class=\"normal_10AN\" align=\"left\"  >".$datos[$key]['producto']."</td>\n";
					$html .= "			<td class=\"normal_10AN\" align=\"left\"  >".$datos[$key]['principio_activo']."</td>\n";
					$html .= "			<td class=\"normal_10AN\" align=\"left\" >".$datos[$key]['cff']."</td>\n";
					$html .= "			<td class=\"normal_10AN\" align=\"left\"  >".$datos[$key]['forma']."</td>\n";
					$html .= "			<td class=\"normal_10AN\" align=\"center\" title=\"BORRAR MEDICAMENTO\" >\n";
					$html .= "				<a href=\"javascript:creartabla('".$datos[$key]['codigo_medicamento']."','1','$path')\">\n";
					$html .= "					<img src=\"".$path."/images/delete.gif\" border=\"0\" width=\"15\" height=\"15\">\n";
					$html .= "				<a>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "		<tr class=\"hc_table_submodulo_list_title\" >\n";
					$html .= "			<td align=\"left\" colspan=\"6\">\n";
					$html .= "				<table class=\"label\" >\n";
					$html .= "					<tr >\n";
					$html .= "						<td width=\"30%\">VIA DE ADMINISTRACI?N: </td>\n";
					$html .= "						<td colspan=\"3\"><b class=\"label_mark\">".$this->ObtenerVias($key)."</b></td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr>\n";
					$html .= "						<td valign=\"top\">DOSIS</td>\n";
					$html .= "						<td valign=\"top\">\n";
					$html .= "							<input type=\"text\" class='input-text' size=\"10\" id=\"dosiscant$key\" name=\"dosiscant$key\" onkeypress=\"return acceptNum(event);\">\n";
					$html .= "						</td>\n";
					$html .= "						<td valign=\"top\">\n";
					$html .= "								".$this->ObtenerCombo($key,$datos[$key]['unidad_dosificacion'])."\n";
					$html .= "						</td>\n";
					$html .= "						<td valign=\"top\">\n";
					$html .= "							<a href=\"javascript:Adicionarfrecuencia('".$key."');\" title=\"Frecuencia Medicamento\">\n";
					$html .= "								<img src=\"".$path."/images/modificar.png\" border=\"0\">\n";
					$html .= "							</a>\n";
					$html .= "						</td>\n";
					$html .= "						<td width=\"30%\" valign=\"top\">\n";
					$html .= "							<textarea rows=\"2\" class=\"textarea\" style=\"width:100%\" id=\"frecuenciadosis0".$key."\" name=\"frecuenciadosis0".$key."\"></textarea>";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr >\n";
					$html .= "						<td >CANTIDAD</td>\n";
					$html .= "						<td >\n";
					$html .= "							<input type=\"text\" class='input-text' size=\"10\" id=\"cantidad".$key."\" name=\"cantidad".$key."\" onkeypress=\"return acceptNum(event);\">\n";
					$html .= "						</td>\n";
					$html .= "						<td colspan=\"3\" align=\"left\" class=\"normal_10N_menu\" >".$datos[$key]['umm']."</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					if($datos[$key]['item'] == 'NO POS')
					{
						$html .= "		<tr class=\"hc_table_submodulo_list_title\" >\n";
						$html .= "			<td align=\"left\" colspan=\"6\" class=\"label_error\">\n";
						$html .= "				<input name=\"sw_nopos\" id=\"sw_nopos\" value=\"S\" type=\"checkbox\">\n";
						$html .= "				FORMULACION NO POS A PETICION DEL PACIENTE\n";
						$html .= "			</td>\n";
						$html .= "		</tr>\n";
					}
					
					if(SessionGetVar("SolicitudAutorizacion") == '3')
					{
						$html .= "		<tr class=\"hc_table_submodulo_list_title\" >\n";
						$html .= "			<td align=\"left\" colspan=\"6\">\n";
						$html .= "				<table class=\"label\">\n";
						$html .= "					<tr >\n";
						$html .= "						<td width=\"33%\" >AUTORIZADO POR: </td>\n";
						$html .= "						<td >\n";
						$html .= "							<input type=\"text\" class='input-text' size=\"30\" id=\"profesional\" name=\"profesional\" readonly>\n";
						$html .= "							<input type=\"hidden\" id=\"profesionalid\" name=\"profesionalid\">\n";
						$html .= "						</td>\n";
						$html .= "						<td >\n";
						$html .= "							<a href=\"javascript:CrearProfesionales()\" class=\"label\" title=\"SELECCIONAR PROFESIONAL\">\n";
						$html .= "								<img src=\"".$path."/images/usuarios.png\" border=\"0\" >PROFESIONALES\n";
						$html .= "							</a>\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
						$html .= "				</table>\n";
						$html .= "			</td>\n";
						$html .= "		</tr>\n";
					}
					$html .= "		<tr class=\"modulo_table_list_title\" >\n";
					$html .= "			<td align=\"center\" colspan=\"7\">OBSERVACIONES E INDICACIONES DE SUMISTRO</td>\n";
					$html .= "		</tr>\n";
					$html .= "		<tr class=\"hc_table_submodulo_list_title\" >\n";
					$html .= "			<td class=\"modulo_list_oscuro\" style=\"$estilo\">\n";
					$html .= "				<a href=\"javascript:EvaluarFormulacion('$key','$path','".SessionGetVar("SolicitudAutorizacion")."');\">\n";
					$html .= "					<img src=\"".$path."/images/pcopiar.png\" border=\"0\" >GUARDAR\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td align=\"center\" colspan=\"6\">\n";
					$html .= "				<textarea rows=\"2\" class=\"textarea\" style=\"width:100%\" id=\"medicamento".$key."\" name=\"medicamento".$key."\"></textarea>";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
				}
				$html .= "		</table>\n";
				$html .= "</form>\n";
			}
			if(sizeof($codigos) == 0) $html = "";
			if($opcion == '1') $html = "";
      return  $sw."*".$html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function IngresarMedicamento($arreglo)
		{
			$usuario = UserGetUID();
			$codigos = SessionGetVar("CodigosSeleccionados");
			
			$cat = str_replace("\'","",$arreglo[8]);
			if($cat == $arreglo[8])	$cat = str_replace("'","",$arreglo[8]);
			
			$arreglo[8] = $cat;
			
			if($arreglo[8]) $usuario = $arreglo[8];
			
			print_r($arreglo);
			
			$html = "";
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
			$sql .= "				usuario_registro, ";
			$sql .= "				sw_no_pos_peticion_paciente ";
			
			$sql .= "				) ";
			$sql .= "VALUES( ";
			$sql .= "				 ".SessionGetVar("IngresoHc").", ";
			$sql .= "				 ".SessionGetVar("EvolucionHc").", ";
			$sql .= "				'".$arreglo[0]."', ";
			$sql .= "				 ".$usuario.",";
			$sql .= "				 NOW(),";
			$sql .= "				'".$arreglo[5]."',";
			$sql .= "				'".$arreglo[4]."',";
			$sql .= "				'".$arreglo[1]."',";
			$sql .= "				 ".$arreglo[3].",";
			$sql .= "				'".$arreglo[6]."',";
			$sql .= "				 ".$arreglo[2].", ";
			$sql .= "				 ".UserGetUID().", ";
			$sql .= "				 '".$arreglo[9]."' ";
			$sql .= "				) ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$rst->Close();
			

			if($opcion == '1') unset($codigos[$arreglo[0]]);

			if(sizeof($codigos) > 0)
			{
				$datos[0] = $arreglo[0];
				$datos[1] = "1";
				$datos[2] = $arreglo[7];
				$html = $this->CrearTabla($datos);
			}
			$this->ConsultaMedicamento($arreglo[0]);
			SessionSetVar("RutaImagenes",$arreglo[7]);

			return "0*".$html;
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
			$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS' ";
			$sql .= "				ELSE 'NO POS' END AS item, ";
			$sql .= "				SU.nombre AS med_formula, ";
			$sql .= "				SD.nombre AS med_modifica, ";
			$sql .= "				SU.usuario_id, ";
			$sql .= "				FM.sw_confirmacion_formulacion, ";
			$sql .= "				FH.usuario_registro, ";
			$sql .= "				FM.sw_requiere_autorizacion_no_pos, ";
			$sql .= "				FM.justificacion_no_pos_id ";
			$sql .= "FROM 	inv_med_cod_principios_activos AS IA, ";
			$sql .= "				hc_formulacion_medicamentos FM,";
			$sql .= "				hc_formulacion_medicamentos_eventos FH,";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				inv_med_cod_forma_farmacologica AS IF, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_vias_administracion HA, ";
			$sql .= "				system_usuarios SU, ";
			$sql .= "				system_usuarios SD ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND		FM.ingreso = ".SessionGetVar("IngresoHc")." ";
			$sql .= "AND		FM.codigo_producto = '".$codigo."' ";
			$sql .= "AND		FH.codigo_producto = '".$codigo."' ";
			$sql .= "AND		FH.num_reg = FM.num_reg ";
			$sql .= "AND		HA.via_administracion_id = FM.via_administracion_id ";
			$sql .= "AND 		IF.cod_forma_farmacologica = ME.cod_forma_farmacologica ";
			$sql .= "AND		SU.usuario_id = FH.usuario_id ";
			$sql .= "AND		SD.usuario_id = FH.usuario_registro ";
			$sql .= "ORDER BY FM.sw_estado ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();

			while (!$rst->EOF)
			{
				$datos[$codigo] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			$datos[$codigo]['activar'] = "1";

			$documentos = SessionGetVar("MedicamentosFormulados");
			foreach($documentos as $key=>$medica)
				$datos[$key] = $medica;

			SessionSetVar("MedicamentosFormulados",$datos);
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearMedicamentos($arreglo)
		{
			$html = "";
			$path = SessionGetVar("RutaImagenes");

			$clasesjs .= "new Array('formulacion_table_list_suspendido','formulacion_table_list',";
			$clasesjs .= "'formulacion_table_list_oscuro','formulacion_table_list_claro','label','label2')";

			$documentos = SessionGetVar("MedicamentosFormulados");
			echo "<pre>".print_r($documentos,true)."</pre>";
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

			$est0 = "style=\"text-indent:2pt;text-align:left;font-size:11px;\" ";
			$est1 = "style=\"text-indent:2pt;text-align:left;font-size:9px;\" ";
			$estilos = "style=\"border-bottom-width:0px;border-left-width:1px;border-right-width:0px;border-top-width:0px;border-style: solid;\"";
			
			$html .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "  		<td align=\"center\">PLAN DE MEDICAMENTOS</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr>\n";
			$html .= "			<td><br>\n";

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
					$html .= "									<td >VIA DE ADMINISTRACI?N: </td>\n";
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
					$html .= "							<table align=\"center\" id=\"Formulacion3x2".$key."\" class=\"".$clases[$datos['sw_estado']][7]."\" width=\"98%\">\n";
					$html .= "								<tr>\n";
					$html .= "									<td align=\"left\">FORMUL?: <font style=\"font-weight:normal;\">".$datos['med_formula']."</font></td>\n";
					$html .= "								</tr>\n";
					if($datos['med_modifica'] != $datos['med_formula'])
					{
						$html .= "								<tr>\n";
						$html .= "									<td align=\"left\">MODIFICO: <font style=\"font-weight:normal;\">".$datos['med_modifica']."</font></td>\n";
						$html .= "								</tr>\n";
					}
					
					$usuariohc = UserGetUID();
					if($datos['sw_confirmacion_formulacion'] == '0' && $datos['usuario_id'] == $usuariohc)
					{
						$arr = "'".$datos['codigo_producto']."','".$datos['num_reg_formulacion']."'";
						$html .= "								<tr>\n";
						$html .= "									<td id=\"confirmacion".$datos['codigo_producto']."\" align=\"center\"><a href=\"javascript:IniciarConfirmacion('".$datos['producto']."',$arr);MostrarCapas('Confirmacion')\" class=\"normal_10AN\">CONFIRMAR</a></td>\n";
						$html .= "								</tr>\n";
					}
					
					if($datos['sw_requiere_autorizacion_no_pos'] == 'S' && !$datos['justificacion_no_pos_id'])
					{
						$html .= "								<tr>\n";
						$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><a href=\"javascript:Justificar('".$datos['codigo_producto']."','".$datos['justificacion_no_pos_id']."')\" class=\"normal_10AN\">JUSTIFICAR</a></td>\n";
						$html .= "								</tr>\n";					
					}
					
					if($datos['sw_requiere_autorizacion_no_pos'] == 'S' && $datos['justificacion_no_pos_id'])
					{
						$html .= "								<tr>\n";
						$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><a href=\"javascript:Justificar('".$datos['codigo_producto']."','".$datos['justificacion_no_pos_id']."')\" class=\"normal_10AN\">VER JUSTIFICACI?N</a></td>\n";
						$html .= "								</tr>\n";					
					}
					
					if($datos['sw_requiere_autorizacion_no_pos'] == 'P' )
					{
						$html .= "								<tr>\n";
						$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><b class=\"normal_10AN\">MEDICAMENTO NO POS A PETICION DEL PACIENTE</B></td>\n";
						$html .= "								</tr>\n";					
					}

					if($datos['sw_requiere_autorizacion_no_pos'] == 'N' )
					{
						$html .= "								<tr>\n";
						$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><b class=\"normal_10AN\">MEDICAMENTO POS</b></td>\n";
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
					$html .= "				<a href=\"javascript:VerHistorial(new Array('".$key."'))\"  title=\"HISTORIAL\">\n";
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
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est0 width=\"80%\">".$nivel2['producto']." <font $est1>(".$nivel2['principio_activo'].")</font></td>\n";
							$html .= "						<td valign=\"bottom\" align=\"right\" $est1 width=\"10%\">".$nivel2['dosis']."</td>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est1 width=\"10%\">".$nivel2['unidad_dosificacion']."</td>\n";
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
					$html .= "									<td >".$nivel2['cantidad']."</td><td colspan=\"2\"><b>Unidad(es)</b></td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr >\n";
					$html .= "									<td >VOLUMEN DE INFUSI?N</td>\n";
					$html .= "									<td align=\"right\">".$nivel2['volumen_infusion']."</td><td colspan=\"2\">".$nivel2['unidad_volumen']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "						<td width=\"40%\" valign=\"top\" $estilos>\n";
					$html .= "							<table align=\"center\" id=\"Solucion42".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][7]."\" >\n";
					$html .= "								<tr>\n";
					$html .= "									<td align=\"center\">FORMUL?:</td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr>\n";
					$html .= "									<td style=\" font-weight:normal\">".$nivel1[$key1]['med_formula']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";

					if($nivel1[$key1]['observacion'] != "")
					{
						$html .= "		<tr class=\"".$clases[$nivel1[0]['sw_estado']][1]."\" >\n";
						$html .= "			<td colspan=\"5\" width=\"100%\" >\n";
						$html .= "				<table width=\"100%\" id=\"Solucion5".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][7]."\">\n";
						$html .= "					<tr>\n";
						$html .= "						<td valign=\"top\" width=\"30%\">\n";
						$html .= "							OBSERVACIONES E INDICACIONES DE SUMINISTRO:</td>\n";
						$html .= "						</td>\n";
						$html .= "						<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
						$html .= "							".$nivel1[$key1]['observacion']."\n";
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
		*
		*********************************************************************************/
		function ActualizarMedicamento($arreglo)
		{
			$datos = SessionGetVar("MedicamentosFormulados");

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
			$sql .= "				motivo_suspension, ";
			$sql .= "				usuario_registro ";
			$sql .= "				) ";
			$sql .= "VALUES( ";
			$sql .= "				 ".SessionGetVar("IngresoHc").", ";
			$sql .= "				 ".SessionGetVar("EvolucionHc").", ";
			$sql .= "				'".$arreglo[1]."', ";
			$sql .= "				 ".$datos[$arreglo[1]]['usuario_id'].",";
			$sql .= "				 NOW(),";
			$sql .= "				'".$datos[$arreglo[1]]['observacion']."',";
			$sql .= "				'".$datos[$arreglo[1]]['via_administracion_id']."',";
			$sql .= "				'".$datos[$arreglo[1]]['unidad_dosificacion']."',";
			$sql .= "				 ".$datos[$arreglo[1]]['dosis'].",";
			$sql .= "				 ".$datos[$arreglo[1]]['cantidad'].", ";
			$sql .= "				'".$datos[$arreglo[1]]['frecuencia']."', ";
			$sql .= "				'".$arreglo[2]."', ";
			$sql .= "				'".$arreglo[0]."', ";
			$sql .= "				 ".UserGetUID()." ";
			$sql .= "				) ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$this->ConsultaMedicamento($arreglo[1]);
			return true;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function Actualizarsolucion($param)
		{
			$sol = $this->ConsultaSoluciones($param[1],$param[2]);
			$solucion = $sol;
			$datos = array();
			unset($solucion[0]);
			
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
			$sql .= "			 ".$param[1].",";
    	$sql .= "			 ".SessionGetVar("IngresoHc").",";
    	$sql .= "			 ".SessionGetVar("EvolucionHc").",";
    	$sql .= "			 ".UserGetUID().",";
    	$sql .= "			NOW(),";
    	$sql .= "			'".$param[2]."',";
    	$sql .= "			'".$datos['observacion']."',";
    	$sql .= "			 ".$datos['volumen_infusion'].",";
    	$sql .= "			'".$datos['unidad_volumen']."',";
    	$sql .= "			 ".$datos['cantidad']." ";
			$sql .= ")";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			return true;
		}
		/*******************************************************************************
		*
		********************************************************************************/
		function ConsultaSoluciones($numero,$estado)
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
			$sql .= "AND 		FH.usuario_id = SU.usuario_id ";
			$sql .= "ORDER BY FM.sw_estado,FD.sw_solucion DESC ";

 			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			$soluciones = array();
			$medica = SessionGetVar("SolucionesFormuladas");
			while (!$rst->EOF)
			{
				$soluciones[$rst->fields[6]] = $rst->GetRowAssoc($ToUpper = false);
				$datos[$rst->fields[6]] = $rst->GetRowAssoc($ToUpper = false);
				($estado)? $datos[0]['sw_estado'] = $estado : $datos[0]['sw_estado'] = $datos[$rst->fields[6]]['sw_estado'];
				$datos[0]['activar'] = "1";
				$rst->MoveNext();
			}

			$medica[$numero] =$datos;

			SessionSetVar("SolucionesFormuladas",$medica);

			return $soluciones;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function FinalizarMedicamento($arreglo)
		{
			$this->ActualizarMedicamento($arreglo);
			$datos = SessionGetVar("MedicamentosFormulados");

			$html .= "	<table align=\"center\" border=\"0\" width=\"100%\" >\n";
			$html .= "		<tr>\n";
			$html .= "  		<td align=\"center\">\n";
			$html .= "				<b class=\"label_mark\">EL MEDICAMENTO <font class=\"label_error\">".$datos[$arreglo[0]]['producto']."</font>, HA SIDO FINALIZADO</b>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table><br>\n";

			unset($datos[$arreglo[1]]);
			SessionSetVar("MedicamentosFormulados",$datos);

			$cadena = str_replace("\'","",$arreglo[3])."*".$html;
			if($cadena == $arreglo[3]."*".$html)
				$cadena = str_replace("'","",$arreglo[3])."*".$html;
			return $cadena;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function FinalizarSolucion($arreglo)
		{
			$soluciones = SessionGetVar("SolucionesFormuladas");
			print_r($soluciones);
			$rst = $this->IngresarSolucion($arreglo,$soluciones[$arreglo[1]]);
			
			$html .= "	<table align=\"center\" border=\"0\" width=\"100%\" >\n";
			$html .= "		<tr>\n";
			$html .= "  		<td align=\"center\">\n";
			if($rst)
			{
				$html .= "				<b class=\"label_mark\">LA SOLUCION COMPUESTA POR: <font class=\"label_error\">";
				$medicamentoa = "";
				foreach($soluciones[$arreglo[0]] as $key=>$medica)
				{
					if($key != '0')
						$medicamentoa .= $medica['producto']." + ";
				}
				$html .= "	".substr($medicamentoa,0,strlen($medicamentoa)-3);
				$html .= "				</font> ,HA SIDO FINALIZADA</b>\n";
				unset($soluciones[$arreglo[1]]);
				SessionSetVar("SolucionesFormuladas",$soluciones);
			}
			else
			{
				$html .= "				<b class=\"label_error\">".$this->frmError['MensajeError']."</b>";
			}
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table><br>\n";

			$cadena = $arreglo[3]."*".$html;
			return $cadena;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function IngresarSolucion($param,$solucion)
		{
			print_r($solucion);
			unset($solucion[0]);
			$datos = array();
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
			$sql .= "			 ".$param[1].",";
    	$sql .= "			 ".SessionGetVar("IngresoHc").",";
    	$sql .= "			 ".SessionGetVar("EvolucionHc").",";
    	$sql .= "			 ".UserGetUID().",";
    	$sql .= "			NOW(),";
    	$sql .= "			'".$param[2]."',";
    	$sql .= "			'".$datos['observacion']."',";
    	$sql .= "			 ".$datos['volumen_infusion'].",";
    	$sql .= "			'".$datos['unidad_volumen']."',";
    	$sql .= "			 ".$datos['cantidad']." ";
			$sql .= ")";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			return true;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerCombo($key,$unidad)
		{
			$datos = array();
			if(!$unidad)
			{
				$sql .= "SELECT DISTINCT unidad_dosificacion ";
				$sql .= "FROM  	hc_unidades_dosificacion_vias_administracion ";
				$sql .= "WHERE	via_administracion_id IN (".$this->vias_id.") ";

				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

				while (!$rst->EOF)
				{
					$datos[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
			}
			else
			{
				$datos[0]['unidad_dosificacion'] = $unidad;
			}

			$dosis = "";
			if(sizeof($datos) == 1)
			{
				$dosis .= "		<input type=\"hidden\" id=\"dosis$key\" name=\"dosis$key\" value=\"".$datos[0]['unidad_dosificacion']."\">\n";
				$dosis .= "		<b class=\"label_mark\">".$datos[0]['unidad_dosificacion']."</b>\n";
			}
			else
			{
				$dosis .= "			<select class=\"select\" id=\"dosis$key\" name=\"dosis$key\">\n";
				$dosis .= "				<option value=\"0\">-----SELECCIONAR-----</option>\n";
				for($i = 0; $i< sizeof($datos); $i++ )
				{
					$dosis .= "				<option value=\"".$datos[$i]['unidad_dosificacion']."\">".$datos[$i]['unidad_dosificacion']."</option>\n";
				}
				$dosis .= "			</select>&nbsp;\n";
			}
			return $dosis;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerVias($codigo)
		{
			$sql .= "SELECT * ";
			$sql .= "FROM 	inv_medicamentos_vias_administracion ";
			$sql .= "WHERE 	codigo_medicamento = '".$codigo."' ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			if(!$rst->EOF)
			{
				$sql  = "SELECT HA.via_administracion_id,  ";
				$sql .= "				HA.nombre ";
				$sql .= "FROM 	inv_medicamentos_vias_administracion IA, ";
				$sql .= "				hc_vias_administracion HA ";
				$sql .= "WHERE	HA.via_administracion_id = IA.via_administracion_id ";
				$sql .= "AND 		IA.codigo_medicamento = '".$codigo."' ";
			}
			else
			{
				$sql  = "SELECT HA.via_administracion_id,  ";
				$sql .= "				HA.nombre ";
				$sql .= "FROM 	hc_vias_administracion HA ";
			}

			$sql .= "ORDER BY 2 ";

			if(!$rstm = $this->ConexionBaseDatos($sql)) return false;

			$i=0;
			$this->vias_id = "";
			$datos = array();
			while (!$rstm->EOF)
			{
				$datos[$i] = $rstm->GetRowAssoc($ToUpper = false);
				$this->vias_id .= "'".$rstm->fields{0}."' ";
				$rstm->MoveNext();
				$i++;
			}

			$this->vias_id = str_replace(" ",",",trim($this->vias_id));
			$vias = "";

			if(sizeof($datos) == 1)
			{
				$vias .= "		<input type=\"hidden\" id=\"viasadmin$codigo\" name=\"viasadmin$codigo\" value=\"".$datos[0]['via_administracion_id']."\">\n";
				$vias .= "		<b>".$datos[0]['nombre']."</b>\n";
			}
			else
			{
				$vias .= "		<select class=\"select\" id=\"viasadmin$codigo\" name=\"viasadmin$codigo\">\n";
				$vias .= "			<option value=\"0\">-----SELECCIONAR-----</option>\n";
				for($i = 0; $i< sizeof($datos); $i++ )
				{
					$vias .= "			<option value=\"".$datos[$i]['via_administracion_id']."\">".$datos[$i]['nombre']."</option>\n";
				}
				$vias .= "		</select>\n";
			}
			return $vias;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearInterfaceSolucion($param)
		{
			include	 "../../classes/BuscadorMedicamentos/BuscadorMedicamentos.class.php";
			$soluciones = SessionGetVar("SolucionesFormuladas");

			foreach($soluciones[$param[0]] as $key => $datos)
			{
				if($key != '0') break;
			}

			$salida .= "		<form name=\"editarM\" action=\"\" method=\"post\">\n";
			$salida .= "			<table width=\"100%\" align=\"center\">\n";
			$salida .= "				<tr>\n";
			$salida .= "					<td width=\"100%\">\n";
			$salida .= "						<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$salida .= "							<tr class=\"modulo_list_claro\">\n";
			$salida .= "								<td width=\"40%\" style=\"text-indent:6pt;text-align:left\" class=\"formulacion_table_list\">\n";
			$salida .= "									<b style=\"font-size:10px\">VOLUMEN DE INFUSI?N</b>\n";
			$salida .= "								</td>\n";
			$salida .= "								<td align=\"left\" >\n";
			$salida .= "									<input type='text' class='input-text' size='15' name='volumeninput' onkeypress=\"return acceptNum(event);\" value=\"".$datos['volumen_infusion']."\">\n";
			$salida .= "									<select name=\"volumenselect\" class=\"select\">\n";
			$salida .= "										<option value=\"0\">-SELECCIONAR-</option>";

			$buscador = new BuscadorMedicamentos();
			$mezclas = $buscador->UnidadesSolucion();
			for($i=0; $i<sizeof($mezclas); $i++)
			{
				$sel = "";
				if($datos['unidad_volumen'] == $mezclas[$i]['unidad_volumen']) $sel = "selected";
				$salida .= "											<option value=\"".$mezclas[$i]['unidad_volumen']."\" $sel>".$mezclas[$i]['unidad_volumen']."</option>";
			}
			$salida .= "									</select>\n";
			$salida .= "								</td>";
			$salida .= "							</tr>\n";
			$salida .= "							<tr class=\"formulacion_table_list\" >\n";
			$salida .= "								<td align=\"center\" colspan=\"2\">OBSERVACIONES E INDICACIONES DE SUMINISTRO</td>\n";
			$salida .= "							</tr>\n";
			$salida .= "							<tr class=\"formulacion_table_list\" >\n";
			$salida .= "								<td align=\"center\" colspan=\"2\">\n";
			$salida .= "										<textarea rows=\"2\" class=\"textarea\" style=\"width:100%\" id=\"observacion\" name=\"observacion\">".$datos['observacion']."</textarea>\n";
			$salida .= "									</td>\n";
			$salida .= "							</tr>\n";
			$salida .= "							<tr class=\"formulacion_table_list\" >\n";
			$salida .= "								<td align=\"center\" class=\"modulo_list_claro\" colspan=\"2\">\n";
			$salida .= "									<input class=\"input-submit\" name=\"buscar\" type=\"button\" onclick=\"EvaluarDatosEdicion(document.editarM,'".$param[0]."');\" value=\"Aceptar\">\n";
			$salida .= "									&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$salida .= "									<input class=\"input-submit\" name=\"buscar\" type=\"button\" onclick=\"MostrarSpan('Soluciones');\" value=\"Cancelar\">\n";
			$salida .= "								</td>\n";
			$salida .= "							</tr>\n";
			$salida .= "						</table>\n";
			$salida .= "					</td>\n";
			$salida .= "				</tr>\n";
			$salida .= "			</table>\n";
			$salida .= "		</form>\n";

			return $salida;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ActualizarEdicionSolucion($param)
		{
			$solucion = SessionGetVar("SolucionesFormuladas");
			$this->ActualizarSolucionA($param,$solucion[$param[0]]);
			$this->ConsultaSoluciones($param[0],null);
			$soluciones = SessionGetVar("SolucionesFormuladas");

			$j = 0;
			$est0 = "style=\"text-indent:2pt;font-size:11px;\" ";
			$est1 = "style=\"text-indent:2pt;font-size:9px;\" ";
			$estilos = "style=\"border-bottom-width:0px;border-left-width:1px;border-right-width:0px;border-top-width:0px;border-style: solid;\"";

			$html = "";
			$path = SessionGetVar("RutaImagenes");

			$clasesjs .= "new Array('formulacion_table_list_suspendido','formulacion_table_list',";
			$clasesjs .= "'formulacion_table_list_oscuro','formulacion_table_list_claro','label','label2')";

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

			$nivel1 = $soluciones[$param[0]];
			$key = $param[0];
			print_r($nivel1[0]);
			if($nivel1[0]['activar'] == "1")
			{
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
					$html .= "				<a href=\"javascript:CrearEdicion('".$param[4]."','".$key."',".$nivel1[0]['sw_estado'].")\"  title=\"EDITAR\">\n";
					$html .= "					<img name =\"Editar\" height=\"18\" src=\"".$path."/images/edita.png\" border=\"0\" >\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
				}
				$html .= "			<td width=\"4%\" align=\"center\">\n";
				$html .= "				<a href=\"javascript:VerHistorial(new Array('".$key."'))\"  title=\"HISTORIAL\">\n";
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
				$html .= "									<td >".$nivel1[$key1]['cantidad']."</td><td colspan=\"2\"><b>Unidad(es)</b></td>\n";
				$html .= "								</tr>\n";
				$html .= "								<tr >\n";
				$html .= "									<td >VOLUMEN DE INFUSI?N</td>\n";
				$html .= "									<td align=\"right\">".$nivel1[$key1]['volumen_infusion']."</td><td colspan=\"2\">".$nivel1[$key1]['unidad_volumen']."</td>\n";
				$html .= "								</tr>\n";
				$html .= "							</table>\n";
				$html .= "						</td>\n";
				$html .= "						<td width=\"40%\" valign=\"top\" $estilos>\n";
				$html .= "							<table align=\"center\" id=\"Solucion42".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][7]."\" >\n";
				$html .= "								<tr>\n";
				$html .= "									<td align=\"center\">FORMUL?:</td>\n";
				$html .= "								</tr>\n";
				$html .= "								<tr>\n";
				$html .= "									<td style=\" font-weight:normal\">".$nivel1[$key1]['med_formula']."</td>\n";
				$html .= "								</tr>\n";
				$html .= "							</table>\n";
				$html .= "						</td>\n";
				$html .= "					</tr>\n";
				$html .= "				</table>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";

				if($nivel1[$key1]['observacion'] != "")
				{
					$html .= "		<tr class=\"".$clases[$nivel1[0]['sw_estado']][1]."\" >\n";
					$html .= "			<td colspan=\"5\" width=\"100%\" >\n";
					$html .= "				<table width=\"100%\" id=\"Solucion5".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][7]."\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td valign=\"top\" width=\"30%\">\n";
					$html .= "							OBSERVACIONES E INDICACIONES DE SUMINISTRO:</td>\n";
					$html .= "						</td>\n";
					$html .= "						<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
					$html .= "							".$nivel1[$key1]['observacion']."\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
				}
				$html .= "	</table><br>";
				$j++;
			}
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearProfesionales()
		{
			$datos = $this->ObtenerProfesionales(SessionGetVar("IngresoHc"));
			$profesionales = $datos[0];

			$html .= "<form name=\"uno\">\n";
			$html .= "	<table align=\"center\"  class=\"modulo_table_list\" width=\"100%\">\n";

			if(sizeof($profesionales) > 0)
			{
				$html .= "		<tr class=\"formulacion_table_list\">\n";
				$html .= "			<td align=\"center\">PROFESIONALES HAN FORMULADO</td>\n";
				$html .= "		</tr>\n";
				$html .= "		<tr class=\"modulo_list_claro\">\n";
				$html .= "			<td >\n";
				$html .= "				<select class=\"select\" name=\"profesionalaut1\" onchange=\"document.getElementById('profesionalid').value = this.value; document.getElementById('profesional').value = uno.profesionalaut1.options[uno.profesionalaut1.selectedIndex].text; if(this.value != '0') MostrarSpan('Soluciones');\">\n";
				$html .= "					<option value=\"0\">-----SELECCIONAR-----</option>\n";
				foreach($profesionales as $Key => $profesional)
				{
					$html .= "						<option value=\"".$profesional['usuario_id']."\">".$profesional['nombre']."</option>\n";
				}
				$html .= "				</select>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
			}
			$html .= "		<tr class=\"formulacion_table_list\">\n";
			$html .= "			<td align=\"center\">MAS PROFESIONALES</td>\n";
			$html .= "		</tr>\n";

			$profesionales = $datos[1];

			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "			<td >\n";
			$html .= "				<select class=\"select\" name=\"profesionalaut\" onchange=\"document.getElementById('profesionalid').value = this.value; document.getElementById('profesional').value = uno.profesionalaut.options[uno.profesionalaut.selectedIndex].text; if(this.value != '0') MostrarSpan('Soluciones');\">\n";
			$html .= "					<option value=\"0\">-----SELECCIONAR-----</option>\n";
			foreach($profesionales as $Key => $profesional)
			{
				$html .= "						<option value=\"".$profesional['usuario_id']."\">".$profesional['nombre']."</option>\n";
			}
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ActualizarSolucionA($param,$solucion)
		{
			$datos = array();
			unset($solucion[0]);
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
    	$sql .= "			'".$datos['sw_estado']."',";
    	$sql .= "			'".$param[3]."',";
    	$sql .= "			 ".$param[1].",";
    	$sql .= "			'".$param[2]."',";
    	$sql .= "			 ".$datos['cantidad']." ";
			echo $sql .= ")";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			return true;
		}
		/********************************************************************************
		*
		********************************************************************************/
		function ObtenerProfesionales($ingreso)
		{
			$datos = array();
			$sql  = "SELECT DISTINCT SU.usuario_id, ";
			$sql .= "				SU.nombre ";
			$sql .= "FROM 	hc_formulacion_mezclas_eventos FH, ";
			$sql .= "				profesionales SU ";
			$sql .= "WHERE	FH.ingreso = ".$ingreso." ";
			$sql .= "AND 		FH.usuario_id = SU.usuario_id ";
			$sql .= "AND		SU.tipo_profesional IN ('1','2') ";
			$sql .= "ORDER BY SU.nombre ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			while (!$rst->EOF)
			{
				$datos[0][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			$sql  = "SELECT SU.usuario_id, ";
			$sql .= "				SU.nombre ";
			$sql .= "FROM 	profesionales SU ";
			$sql .= "WHERE	SU.tipo_profesional IN ('1','2') ";
			$sql .= "AND		estado = '1' ";
			$sql .= "ORDER BY SU.nombre ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			while (!$rst->EOF)
			{
				$datos[1][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $datos;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ConfirmarFormulacion($param)
		{
			$sql .= "INSERT INTO hc_formulacion_medicamentos_confirmaciones( ";
			$sql .= "			ingreso,";
			$sql .= "			codigo_producto,";
			$sql .= "			num_reg_formulacion,";
			$sql .= "			fecha_registro,";
			$sql .= "			usuario_id,";
			$sql .= "			observacion ";
			$sql .= "			) ";
			$sql .= "VALUES	(";
			$sql .= "				 ".SessionGetVar("IngresoHc").",";
			$sql .= "				'".$param[0]."',";
			$sql .= "				 ".$param[1].",";
			$sql .= "				NOW(),";
			$sql .= "				 ".UserGetUID().",";
			$sql .= "				'".$param[2]."' ";
			$sql .= "			)";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = SessionGetVar("MedicamentosFormulados");
			$datos[$param[0]]['sw_confirmacion_formulacion'] = '1';
			SessionSetVar("MedicamentosFormulados",$datos);
			
			return "'confirmacion".$param[0]."'";
		}
		/***********************************************************************
		* Esta funci?n verifica si este submodulo fue utilizado para la atencion 
		* de un paciente.
		*
		* @access private
		* @return text Datos HTML de la pantalla.
		*************************************************************************/
		function GetEstado($param)
		{
			$sql = "SELECT 	COUNT(A.sw_confirmacion_formulacion) AS cant
              FROM		hc_formulacion_medicamentos AS A,
	                    hc_formulacion_medicamentos_eventos AS B
							WHERE A.ingreso = ".SessionGetVar("IngresoHc")."
							AND A.num_reg = B.num_reg
							AND A.sw_confirmacion_formulacion = '0'
							AND B.usuario_id = ".UserGetUID().";";
							
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$estado = array();
			while(!$rst->EOF)
			{
				$estado = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $estado['cant'];
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
	$oRS = new procesos_admin( array( 'ActivarMenu', 'CrearTabla'));
	$oRS->action();
?>