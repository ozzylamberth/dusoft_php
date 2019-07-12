<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: Formulacion_Externa_Facturacion_HTML.class.php
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: Parametrizar_Medico_ESM_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class Formulacion_Externa_Facturacion_HTML
	{
		/**
		* Constructor de la clase
		*/
		function Formulacion_Externa_Facturacion_HTML(){}
		
    
    function Vista_Formulario($action,$datos,$planes,$buscador,$CortesCentro,$FormulacionDiaria,$NumeroFormulas)
	{
	/*echo(" <pre> Informacion ".print_r($FormulacionDiaria, true)." </pre> ");*/
	
		$html .= " <script>";
		$html .= "  function Validar(Formulario)";
		$html .= "  {";
		$html .= "		var dato=0;";
		$html .= "		var max_tope= Date.parse(convertirAFecha('".$CortesCentro['fecha_finmax_tope']."'));";
		$html .= "		var fecha_seleccionada= Date.parse(convertirAFecha(Formulario.fecha_final.value));";
		
		$html .= "		dato=CompararFechas(Formulario.fecha_inicio.value,Formulario.fecha_final.value); ";
		$html .= "			if(dato==1)";
		$html .= "				{
										alert(\"La Fecha Seleccionada, No Puede Ser Menor a la Inicial\");";
		$html .= "						return false;
									}
									else
										if(fecha_seleccionada >max_tope)
											{
											alert(\"LA FECHA FINAL, NO PUEDE SER MAYOR AL LAPSO: \"+".$CortesCentro['lapso'].");";
		$html .= "							return false;
											}";
		$html .= "		document.cortes.submit();";
		$html .= " }";
		$html .= " </script>";

		$ctl = AutoCarga::factory("ClaseUtil");
		$html .= $ctl->CompararFechas_Javascript();
		$html .= $ctl->LimpiarCampos();
		$html .= $ctl->RollOverFilas();
		$html .= $ctl->AcceptDate('/');
		
		$selected = "";
		$select_p = "	<select name=\"buscador[plan_id]\" id=\"plan_id\" class=\"select\" style=\"width:100%\">";
		$select_p .= "	<option value=\"\">";
		$select_p .= "		-- -- --";
		$select_p .= "	</option>";
		foreach($planes as $k => $ll)
		{
		if($buscador['plan_id']==$ll['plan_id'])
		$selected = " selected ";
		$select_p .= "	<option $selected value=\"".$ll['plan_id']."\">";
		$select_p .= "		".$ll['plan_descripcion'];
		$select_p .= "	</option>";
		$selected = " ";
		}
		$select_p .="	</select>";
		
		$html .= ThemeAbrirTabla('GENERAR CORTES DE LA FARMACIA :'.$datos['empresa'].' - '.$datos['centro']);
		$html .= "<form name=\"cortes\" id=\"cortes\" action=\"".$action['buscar']."\" method=\"post\">\n";
		$html .= "  <table width=\"60%\" align=\"center\">\n";
		$html .= "    <tr>\n";
		$html .= "      <td>\n";
		$html .= "	      <fieldset class=\"fieldset\">\n";
		$html .= "          <legend class=\"normal_10AN\">FILTRO PARA LA GENERACION DE CORTES</legend>\n";
		$html .= "		      <table width=\"100%\">\n";
		$html .= "            <tr>\n";
		$html .= "              <td class=\"normal_10AN\">FECHA INICIAL</td>\n";
		$html .= "              <td>\n";
		$html .= "                <input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$CortesCentro['fecha_inicial_corte']."\" readonly>\n";
		$html .= "              </td>\n";
		$html .= "		          <td align=\"left\" class=\"label\" ></td>\n";
		$html .= "            </tr>\n";

		$html .= "            <tr>\n";
		$html .= "              <td class=\"normal_10AN\">FECHA FINAL</td>\n";
		$html .= "              <td>\n";
		$html .= "                <input type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$_REQUEST['buscador']['fecha_final']."\">\n";
		$html .= "              </td>\n";
		$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_final','/',1)."</td>\n";
		$html .= "            </tr>\n";
		$html .= "			      <tr>\n";

		/*$html .= "			      <tr class=\"normal_10AN\">\n";
		$html .= "						<td>";
		$html .= "							PLANES";
		$html .= "						</td>";
		$html .= "						<td colspan=\"2\">";
		$html .= "							".$select_p;
		$html .= "						</td>";
		$html .= "			      </tr>\n";*/
		$html .= "			      <tr>\n";
		$html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
		
		$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\" onclick=\"Validar(document.cortes)\">\n";
		$html .= "					      <input type=\"reset\" class=\"input-submit\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.cortes);\">\n";
		$html .= "				      </td>\n";
		$html .= "			      </tr>\n";
		$html .= "		      </table>\n";
		$html .= "	      </fieldset>\n";
		$html .= "	    </td>\n";
		$html .= "	  </tr>\n";
		$html .= "	</table>\n";
		$html .= "</form>\n";
  		
		$html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
		$html .= "    <tr>\n";
		$html .= "      <td align=\"center\">\n";
		$html .= "			<form name=\"forma\" action=\"".$action['crear_corte']."\" method=\"post\">\n";
		if($FormulacionDiaria['registros'][0]>0)
		$html .= "        		<input class=\"input-submit\" type=\"submit\" value=\"GENERAR CORTE\">\n";
		$html .= "			</form>\n";
		$html .= "      </td>\n";
		$html .= "      <td align=\"center\">\n";
		$html .= "			<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
		$html .= "        		<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
		$html .= "			</form>\n";
		$html .= "      </td>\n";
		$html .= "    </tr>\n";
		$html .= "  </table>\n";
		
		
		/*NUMERO DE PAQUETES*/
		$paquetes=($FormulacionDiaria['registros'][0]/$NumeroFormulas);
		$html .= "	<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
		$html .= "		<tr>";
		$html .= "			<td colspan=\"10\" class=\"modulo_table_list_title\">";
		$html .= "				PAQUETES - CORTES";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr>";
		$k=0;
		for($j=0;$j<ceil($paquetes);$j++)
		{
			$style="";
			if($k==5)
			{
			$html .= "		<tr>";
			$k=0;
			}
		if($buscador['offset']==($j*$NumeroFormulas))
		$style= " style=\"background-color:#D5D4D2;text-align:center\" ";
		$html .= "			<td width=\"10%\" $style align=\"center\">";
		$html .= "				TMP#".($j+1);
		$html .= "			</td>";
		$html .= "			<td width=\"10%\" $style align=\"center\">";
		$html .= "				<a href=\"".$action['paquete']."&buscador[offset]=".($j*$NumeroFormulas)."\">";
		$html .= "					<img title=\"VER PAQUETE #".($j+1)."\" src=\"".GetThemePath()."/images/tabla.png\" width=\"17\" height=\"17\" border=\"0\">";
		$html .= "				</a>";
		$html .= "			</td>";
			
		$k++;
		}
		$html .= "		</tr>";
		$html .= "	</table>";
		
		/*Informacion de los Cortes Seleccionados*/
		$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
		$html .= "		<tr class=\"modulo_table_list_title\">";
		$html .= "			<td>";
		$html .= "				ID.PACIENTE";
		$html .= "			</td>";
		$html .= "			<td>";
		$html .= "				PACIENTE";
		$html .= "			</td>";
		$html .= "			<td>";
		$html .= "				FORMULA";
		$html .= "			</td>";
		$html .= "			<td>";
		$html .= "				FECHA";
		$html .= "			</td>";
		$html .= "			<td>";
		$html .= "				ID. MEDICO";
		$html .= "			</td>";
		$html .= "			<td>";
		$html .= "				COD.DIAG";
		$html .= "			</td>";
		$html .= "			<td>";
		$html .= "				SUCURSAL";
		$html .= "			</td>";
		$html .= "			<td>";
		$html .= "				PLAN";
		$html .= "			</td>";
		$html .= "		</tr>";
		foreach($FormulacionDiaria as $key=>$valor)
		{
		($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
		($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
		$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
		$html .= "			<td>";
		$html .= "				".$valor['paciente_id'];
		$html .= "			</td>";
		$html .= "			<td>";
		$html .= "				".$valor['paciente'];
		$html .= "			</td>";
		$html .= "			<td>";
		$html .= "				".$valor['formula_papel'];
		$html .= "			</td>";
		$html .= "			<td>";
		$html .= "				".$valor['fecha_formula'];
		$html .= "			</td>";
		$html .= "			<td>";
		$html .= "				".$valor['tercero_id'];
		$html .= "			</td>";
		$html .= "			<td>";
		$html .= "				".$valor['diagnostico_id'];
		$html .= "			</td>";
		$html .= "			<td>";
		$html .= "				".$valor['codigo_farmacia'];
		$html .= "			</td>";
		$html .= "			<td class=\"normal_10AN\">";
		$html .= "				".$valor['plan_descripcion'];
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
		$html .= "			<td>";
		$html .= "			</td>";
		$html .= "			<td colspan=\"7\">";
		/*
		* CICLO QUE PERMITE GENERAR EL LISTADO DE ITEMS
		* DISPENSADOS
		*/
		if(count($FormulacionDiaria['detalle'][$valor['bodegas_doc_id']][$valor['numeracion']])>0)
			{
		$html .= "				<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">";
		$html .= "					<tr class=\"modulo_table_list_title\">";
		$html .= "						<td>";
		$html .= "							COD.";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							PRODUCTO";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							CANT.";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							VLR/U.";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							TOTAL";
		$html .= "						</td>";
		$html .= "					</tr>";
		$total =0;
		for($i=0;$i<count($FormulacionDiaria['detalle'][$valor['bodegas_doc_id']][$valor['numeracion']]);$i++)
				{
		$html .= "					<tr>";
		$html .= "						<td width=\"10%\">";
		$html .= "							".$FormulacionDiaria['detalle'][$valor['bodegas_doc_id']][$valor['numeracion']][$i]['codigo_alterno'];
		$html .= "						</td>";
		$html .= "						<td width=\"55%\">";
		$html .= "							".$FormulacionDiaria['detalle'][$valor['bodegas_doc_id']][$valor['numeracion']][$i]['producto'];
		$html .= "						</td>";
		$html .= "						<td width=\"10%\">";
		$html .= "							".FormatoValor($FormulacionDiaria['detalle'][$valor['bodegas_doc_id']][$valor['numeracion']][$i]['cantidad']);
		$html .= "						</td>";
		$html .= "						<td width=\"10%\">";
		$html .= "							".FormatoValor($FormulacionDiaria['detalle'][$valor['bodegas_doc_id']][$valor['numeracion']][$i]['valor_unitario'],2);
		$html .= "						</td>";
		$html .= "						<td width=\"15%\">";
		$html .= "							".FormatoValor($FormulacionDiaria['detalle'][$valor['bodegas_doc_id']][$valor['numeracion']][$i]['total_venta'],2);
		$html .= "						</td>";
		$html .= "					</tr>";
		$total_dispensacion += $FormulacionDiaria['detalle'][$valor['bodegas_doc_id']][$valor['numeracion']][$i]['total_venta'];
				}
		$html .= "				</table>";
			}
		
		/*
		* CICLO QUE PERMITE GENERAR EL LISTADO DE ITEMS
		* DISPENSADOS PENDIENTES
		*/
		$valor_pendientes=0;	
		if(count($FormulacionDiaria['detalle_pendientes'][$valor['formula_id']])>0)
			{
		$html .= "				<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">";
		$html .= "					<tr>";
		$html .= "						<td colspan=\"5\" align=\"center\" class=\"normal_10AN\">";
		$html .= "							<b class=\"label_error\">PENDIENTES DISPENSADOS</b>";
		$html .= "						</td>";
		$html .= "					</tr>";
		for($i=0;$i<count($FormulacionDiaria['detalle_pendientes'][$valor['formula_id']]);$i++)
				{
		$html .= "					<tr>";
		$html .= "						<td width=\"10%\">";
		$html .= "							".$FormulacionDiaria['detalle_pendientes'][$valor['formula_id']][$i]['codigo_alterno'];
		$html .= "						</td>";
		$html .= "						<td width=\"55%\">";
		$html .= "							".$FormulacionDiaria['detalle_pendientes'][$valor['formula_id']][$i]['producto'];
		$html .= "						</td>";
		$html .= "						<td width=\"10%\">";
		$html .= "							".FormatoValor($FormulacionDiaria['detalle_pendientes'][$valor['formula_id']][$i]['cantidad']);
		$html .= "						</td>";
		$html .= "						<td width=\"10%\">";
		$html .= "							".FormatoValor($FormulacionDiaria['detalle_pendientes'][$valor['formula_id']][$i]['valor_unitario'],2);
		$html .= "						</td>";
		$html .= "						<td width=\"15%\">";
		$html .= "							".FormatoValor($FormulacionDiaria['detalle_pendientes'][$valor['formula_id']][$i]['total_venta'],2);
		$html .= "						</td>";
		$html .= "					</tr>";
		$valor_pendientes += $FormulacionDiaria['detalle_pendientes'][$valor['formula_id']][$i]['total_venta'];
				}
		$html .= "				</table>";
			}
		$html .= "			</td>";
		$html .= "		</tr>";
		if(count($FormulacionDiaria['detalle'][$valor['bodegas_doc_id']][$valor['numeracion']])>0)
			{
		$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
		$html .= "			<td>";
		$html .= "			</td>";
		$html .= "			<td class=\"label_error\" colspan=\"4\" align=\"right\">";
		$html .= "					TOTAL FORMULA :";
		$html .= "			</td>";
		$html .= "			<td class=\"label_error\" colspan=\"3\">";
		/*$html .= "				".FormatoValor(($valor['total_venta']+$valor_pendientes),2);*/
		$html .= "				".FormatoValor(($total_dispensacion+$valor_pendientes),2);
		$html .= "			</td>";
		$html .= "		</tr>";
		$total_dispensacion=0;
			}
		}
		$html .= "	</table>";
		
        $html .= ThemeCerrarTabla();
		return $html;
    }
    
    
	function Vista_DescargaCortes($action,$datos,$planes,$buscador,$FormulasCortes,$direccion)
  {
    $ctl = AutoCarga::factory("ClaseUtil");
    $html .= $ctl->CompararFechas_Javascript();
    $html .= $ctl->LimpiarCampos();
    $html .= $ctl->RollOverFilas();
    $html .= $ctl->AcceptDate('/');
    $html .= $ctl->AcceptNum(false);

    $selected = "";
    $select_p = "	<select name=\"buscador[plan_id]\" id=\"plan_id\" class=\"select\" style=\"width:100%\">";
    $select_p .= "	<option value=\"\">";
    $select_p .= "		-- TODOS --";
    $select_p .= "	</option>";
    foreach($planes as $k => $ll)
    {
      if($buscador['plan_id']==$ll['plan_id'])
      $selected = " selected ";
      $select_p .= "	<option $selected value=\"".$ll['plan_id']."\">";
      $select_p .= "		".$ll['plan_descripcion'];
      $select_p .= "	</option>";
      $selected = " ";
    }
    $select_p .="	</select>";
    
    /*$id = "tmp/cortes/corte".$datos['empresa_id']."".$datos['centro_utilidad']."".$buscador['lapso']."".$_REQUEST['numero']."";*/
    
    
    $html .= ThemeAbrirTabla('DESCARGA DE CORTES - FARMACIA :'.$datos['empresa'].' - '.$datos['centro']);
    $html .= "<form name=\"cortes\" id=\"cortes\" action=\"".$action['buscar']."\" method=\"post\">\n";
    $html .= "  <table width=\"60%\" align=\"center\">\n";
    $html .= "    <tr>\n";
    $html .= "      <td>\n";
    $html .= "	      <fieldset class=\"fieldset\">\n";
    $html .= "          <legend class=\"normal_10AN\">FILTRO PARA LA GENERACION DE CORTES</legend>\n";
    $html .= "		      <table width=\"100%\">\n";
    $html .= "            		<tr class=\"normal_10AN\">\n";
    $html .= "						<td>";
    $html .= "							LAPSO CORTE AAAAMM (Ej: 201106)";
    $html .= "						</td>";
    $html .= "						<td>";
    $html .= "							<input onkeypress=\"return acceptNum(event);\" type=\"text\" value=\"".$buscador['lapso']."\" maxlength=\"6\" name=\"buscador[lapso]\" id=\"lapso\" class=\"input-text\" style=\"width:100%\">";
    $html .= "						</td>";
    $html .= "            		</tr>\n";
    $html .= "			      	<tr>\n";
    $html .= "			      	<tr class=\"normal_10AN\">\n";
    $html .= "						<td>";
    $html .= "							PLANES";
    $html .= "						</td>";
    $html .= "						<td colspan=\"2\">";
    $html .= "							".$select_p;
    $html .= "						</td>";
    $html .= "			      </tr>\n";
    $html .= "			      <tr>\n";
	$html .= "				      <td colspan=\"2\">\n";
	$html .= "							<table  class=\"modulo_table_list\" width=\"100%\" >";
	$html .= "								<tr>";
	$html .= "									<td colspan=\"4\" class=\"modulo_table_list_title\">";
	$html .= "										SEPARADORES";
	$html .= "									</td>";
	$html .= "								</tr>";
	$html .= "								<tr class=\"normal_10AN\">";
	$html .= "									<td align=\"right\">";
	$html .= "										Punto y Coma (;)";
	$html .= "									</td>";
	$html .= "									<td>";
	$html .= "										<input type=\"radio\" name=\"buscador[separador]\" value=\";\" class=\"input-radio\" checked>";
	$html .= "									</td>";
	$html .= "									<td align=\"right\">";
	$html .= "										Arroba (@)";
	$html .= "									</td>";
	$html .= "									<td>";
	$html .= "										<input type=\"radio\" name=\"buscador[separador]\" value=\"@\" class=\"input-radio\" ".(($buscador['separador']==='@')? 'checked':'').">";
	$html .= "									</td>";
	$html .= "								</tr>";
	$html .= "							</table>";
    $html .= "				      </td>\n";
    $html .= "			      </tr>\n";
    $html .= "			      <tr>\n";
    $html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
    $html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
    $html .= "					      <input type=\"reset\" class=\"input-submit\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.cortes);\">\n";
    $html .= "				      </td>\n";
    
    $html .= "			      </tr>\n";
    $html .= "		      </table>\n";
    $html .= "	      </fieldset>\n";
    $html .= "	    </td>\n";
    $html .= "	  </tr>\n";
    $html .= "	</table>\n";
    $html .= "</form>\n";
    
    $html .= "	<table width=\"90%\" class=\"modulo_table_list\" rules=\"all\" align=\"center\">";
    $html .= "		<tr class=\"modulo_table_list_title\">";
    $html .= "			<td colspan=\"8\">";
    $html .= "				CORTES DEL LAPSO -".$buscador['lapso'];
    $html .= "			</td>";
    $html .= "		</tr>";
    $html .= "		<tr class=\"formulacion_table_list\">";
    $html .= "			<td>";
    $html .= "				SUCURSAL";
    $html .= "			</td>";
    $html .= "			<td>";
    $html .= "				#CORTE";
    $html .= "			</td>";
    $html .= "			<td>";
    $html .= "				LAPSO";
    $html .= "			</td>";
    $html .= "			<td>";
    $html .= "				PERIODO";
    $html .= "			</td>";
    $html .= "			<td>";
    $html .= "				# REGISTROS";
    $html .= "			</td>";
    $html .= "			<td>";
    $html .= "				AUDITADO";
    $html .= "			</td>";
    $html .= "			<td>";
    $html .= "				OP";
    $html .= "			</td>";
    $html .= "			<td>";
    $html .= "				DESC.";
    $html .= "			</td>";
    $html .= "		</tr>";
    foreach($FormulasCortes as $key => $valor)
    {
    ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
    ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
    $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
    $html .= "			<td>";
    $html .= "				".$valor['empresa_id']."-".$valor['centro_utilidad'];
    $html .= "			</td>";
    $html .= "			<td align=\"center\">";
    $html .= "				".$valor['numero'];
    $html .= "			</td>";
    $html .= "			<td>";
    $html .= "				".$valor['lapso'];
    $html .= "			</td>";
    $html .= "			<td>";
    $html .= "				".$valor['fecha_inicial']." => ".$valor['fecha_final'];
    $html .= "			</td>";
    $html .= "			<td align=\"center\">";
    $html .= "				".$valor['cantidad'];
    $html .= "			</td>";
    $html .= "			<td>";
    $html .= "				";
    $html .= "			</td>";
    $html .= "			<td align=\"center\">";
    
    $url = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","DescargaDeCortes",array("buscador"=>$buscador,"numero"=>$valor['numero']));
    
    $html .= "				<a href=\"".$url."\">";
    $html .= "					<img title=\"GENERAR ARCHIVO - CORTE #".$valor['numero']."\" src=\"".GetThemePath()."/images/guarda.png\" border=\"0\">";
    $html .= "				</a>";
    $html .= "			</td>";
    $html .= "			<td>";
	$ubicacion = "tmp/cortes";
    $id = "corte".$datos['empresa_id']."".$datos['centro_utilidad']."".$buscador['lapso']."".$_REQUEST['numero']."";
    $destino = "tmp";
	$urlDwn = "classes/zipArchive/zipArchiveDownload.php?id=".$id."&ubicacion=".$ubicacion."&destino=".$destino."&nombre_arch=".$id;  
		if($_REQUEST['numero']===$valor['numero'])
		{
		$html .= "            <a href=\"".$urlDwn."\" class=\"label_error\" >\n";
		$html .= "              <img src=\"".GetThemePath()."/images/abajo.png\" border='0'>DESCARGAR\n";
		$html .= "            </a>\n";
		}
    $html .= "			</td>";
    $html .= "		</tr>";
    }
    $html .= "	</table>";
    /*echo(" <pre> DATOS ".print_r($FormulasCortes, true)." </pre> ");*/
    
    $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "			<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
    $html .= "        		<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
    $html .= "			</form>\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
    $html .= ThemeCerrarTabla();
    return $html;
  }
    
	function AuditoriaCortes($action,$datos,$conteo,$pagina)
	{
	$ctl = AutoCarga::factory("ClaseUtil");
    $html .= $ctl->CompararFechas_Javascript();
    $html .= $ctl->LimpiarCampos();
    $html .= $ctl->RollOverFilas();
    $html .= $ctl->AcceptDate('/');
    $html .= $ctl->AcceptNum(false);
	
	$html .= "<script>";
		
	$html .= "		function AsignarDatos(empresa_id,centro_utilidad,lapso,numero)";
	$html .= "		{";
	$html .= "			document.getElementById('empresa_id').value=empresa_id;";
	$html .= "			document.getElementById('centro_utilidad').value=centro_utilidad;";
	$html .= "			document.getElementById('lapso').value=lapso;";
	$html .= "			document.getElementById('numero').value=numero;";
	$html .= "			return true;";
	$html .= "		}";
	$html .= "		function Validar(Formulario)";
	$html .= "		{ html= \"\";";
	$html .= "		if(document.getElementById('observacion').value.replace(/^\s+/g,'').replace(/\s+$/g,'') ==\"\")";
	$html .= "			{";
	$html .= "				alert('DEBE DILIGENCIAR LA OBSERVACION PARA AUDITAR EL CORTE');";
	$html .= "				return false;";
	$html .= "			}";
	$html .="		Formulario.submit();";
	
	$html .= "		}";
	
	$html .= "</script>";
	
	
	$html .= ThemeAbrirTabla("AUDITORIA DE CORTES - FORMULACION EXTERNA");
	$html .= "	<center>";
	$html .= "		<fieldset style=\"width:40%;\">";
	$html .= "				<legend class=\"normal_10AN\">BUSCADOR</legend>";
	$html .= "			<form name=\"buscador\" id=\"buscador\" action=\"".$action['buscar']."\" method=\"POST\">";
	$html .= "				<table class=\"modulo_table_list\" width=\"100%\">";
	$html .= "					<tr class=\"normal_10AN\">";
	$html .= "						<td>";
	$html .= "							LAPSO";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<input onkeypress=\"return acceptNum(event);\"  maxlength=\"6\" type=\"text\" class=\"input-text\" style=\"width:100%\" name=\"buscador[lapso]\">";
	$html .= "						</td>";
	$html .= "					</tr>";
	$html .= "					<tr class=\"normal_10AN\">";
	$html .= "						<td>";
	$html .= "							FARMACIA";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<input type=\"text\" class=\"input-text\" style=\"width:100%\" name=\"buscador[farmacia]\">";
	$html .= "						</td>";
	$html .= "					</tr>";
	$html .= "					<tr class=\"normal_10AN\">";
	$html .= "						<td>";
	$html .= "							# CORTE";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<input onkeypress=\"return acceptNum(event);\" type=\"text\" class=\"input-text\" style=\"width:100%\" name=\"buscador[numero]\">";
	$html .= "						</td>";
	$html .= "					</tr>";
	$html .= "					<tr>";
	$html .= "						<td colspan=\"2\" align=\"center\">";
	$html .= "							<input type=\"submit\" value=\"BUSCAR\" class=\"input-submit\">";
	$html .= "						</td>";
	$html .= "					</tr>";
	$html .= "				</table>";
	$html .= "			</form>";
	$html .= "		</fieldset>";
	$html .= "	</center>";
	/*echo(" <pre> REQUEST ".print_r($datos, true)." </pre> ");*/
	$pgn = AutoCarga::factory("ClaseHTML");
	$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
	$html .= "	<table width=\"100%\" class=\"modulo_table_list\">";
	$html .= "			<tr class=\"modulo_table_list_title\">";
	$html .= "				<td width=\"30%\">";
	$html .= "					FARMACIA";
	$html .= "				</td>";
	$html .= "				<td width=\"5%\">";
	$html .= "					#CORTE";
	$html .= "				</td>";
	$html .= "				<td width=\"5%\">";
	$html .= "					LAPSO";
	$html .= "				</td>";
	$html .= "				<td width=\"10%\">";
	$html .= "					FECHA REGISTRO";
	$html .= "				</td>";
	$html .= "				<td width=\"20%\">";
	$html .= "					PERIODO";
	$html .= "				</td>";
	$html .= "				<td width=\"5%\">";
	$html .= "					AUDITADO";
	$html .= "				</td>";
	$html .= "				<td width=\"5%\">";
	$html .= "					OP";
	$html .= "				</td>";
	$html .= "			</tr>";
	foreach($datos as $key => $valor)
		{
	($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
    ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
    $html .= "			<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
	$html .= "				<td >";
	$html .= "					".$valor['farmacia'];
	$html .= "				</td>";
	$html .= "				<td align=\"center\">";
	$html .= "					".$valor['numero'];
	$html .= "				</td>";
	$html .= "				<td >";
	$html .= "					".$valor['lapso'];
	$html .= "				</td>";
	$html .= "				<td>";
	$html .= "					".$valor['fecha_registro'];
	$html .= "				</td>";
	$html .= "				<td>";
	$html .= "					".$valor['fecha_inicial']." => ".$valor['fecha_final'];
	$html .= "				</td>";
	$html .= "				<td align=\"center\">";
	$arreglo = explode('@',$valor['auditoria']);
	$html .= "					<img style=\"cursor: help;\" title=\"".$arreglo[1]."\" src=\"".GetThemePath()."/images/".$arreglo[0]."\" border=\"0\">";
	$html .= "				</td>";
	$html .= "				<td align=\"center\">";
	if($arreglo[2]==='1')
	{
	$html .= "					<a href=\"#nogo\" onclick=\"MostrarSpan();AsignarDatos('".trim($valor['empresa_id'])."','".trim($valor['centro_utilidad'])."','".trim($valor['lapso'])."','".trim($valor['numero'])."');\">";
	$html .= "						<img title=\"AUDITAR\" src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\">";
	$html .= "					</a>";
	}
	$html .= "				</td>";
	$html .= "			</tr>";
		}
	$html .= "		</table>";
	$html .= "	</form>";
	
	$html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "			<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
    $html .= "        		<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
    $html .= "			</form>\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
	
	
	$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
	$html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">AUDITORIA CORTES</div>\n";
	$html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
	$html .= "  <div id='Contenido' class='d2Content'>\n";
	//En ese espacio se visualiza la informacion extraida de la base de datos.
	$html .= "		<form name=\"formulario_\" method=\"POST\" action=\"".$action['paginador']."\" onSubmit=\"Validar(document.formulario_); return false;\">";
	$html .= "			<table width=\"100%\" class=\"modulo_table_list\">";
	$html .= "				<tr class=\"formulacion_table_list\">";
	$html .= "					<td colspan=\"2\" >";
	$html .= "						AUDITORIA";
	$html .= "					</td>";
	$html .= "				</tr>";
	$html .= "				<tr class=\"label_mark\">";
	$html .= "					<td colspan=\"2\" align=\"center\">";
	$html .= "						<i>Con el Presente Formulario, se dà por Auditado el Corte, El cual permite que sea ingresado a una Factura.</i>";
	$html .= "					</td>";
	$html .= "				</tr >";
	$html .= "				<tr class=\"normal_10AN\">";
	$html .= "					<td>";
	$html .= "						#CORTE";
	$html .= "					</td>";
	$html .= "					<td>";
	$html .= "						<input type=\"text\" name=\"formulario[numero]\" id=\"numero\" class=\"input-text\" readonly style=\"width:100%\">";
	$html .= "					</td>";
	$html .= "				</tr>";
	$html .= "				<tr class=\"normal_10AN\">";
	$html .= "					<td>";
	$html .= "						LAPSO";
	$html .= "					</td>";
	$html .= "					<td>";
	$html .= "						<input type=\"text\" name=\"formulario[lapso]\" id=\"lapso\" class=\"input-text\"  readonly style=\"width:100%\">";
	$html .= "					</td>";
	$html .= "				</tr>";
	$html .= "				<tr>";
	$html .= "				<tr class=\"normal_10AN\">";
	$html .= "					<td>";
	$html .= "						OBSERVACION";
	$html .= "					</td>";
	$html .= "					<td>";
	$html .= "						<textarea name=\"formulario[observacion]\" id=\"observacion\" class=\"textarea\" style=\"width:100%\"></textarea>";
	$html .= "					</td>";
	$html .= "				</tr>";
	$html .= "				<tr>";
	$html .= "					<td colspan=\"2\" align=\"center\">";
	$html .= "						<input type=\"hidden\" name=\"formulario[empresa_id]\" id=\"empresa_id\">";
	$html .= "						<input type=\"hidden\" name=\"formulario[centro_utilidad]\" id=\"centro_utilidad\">";
	$html .= "						<input type=\"submit\" value=\"GUARDAR\" class=\"input-submit\">";
	$html .= "					</td>";
	$html .= "				</tr>";
	$html .= "			</table>";
	$html .= "  </div>\n";
	$html .= "</div>\n";
	
	
	$html .= ThemeCerrarTabla();
	$html .= $this->CrearVentana1(300,'');
	return $html;
	}
	
	/*
	* Vista Para la Pre-Generacion de Facturas de Formulacion
	* Consiste en la consulta de aquellas farmacias que pertenecen
	* A una ciudad q puedan ser incluidas en una factura
	*/
	function Pre_GenerarFactura($action,$planes,$ciudades,$buscador,$PreFactura)
	{
	$ctl = AutoCarga::factory("ClaseUtil");
	$sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","Formulacion_Externa_Facturacion");
    $html .= $ctl->CompararFechas_Javascript();
    $html .= $ctl->LimpiarCampos();
    $html .= $ctl->RollOverFilas();
    $html .= $ctl->AcceptDate('/');
    $html .= $ctl->AcceptNum(false);
	$html .= ThemeAbrirTabla("FACTURACION - FORMULACION");
	
	$selected = "";
    $select_p = "	<select name=\"buscador[plan_id]\" id=\"plan_id\" class=\"select\" style=\"width:100%\">";
    $select_p .= "	<option value=\"\">";
    $select_p .= "		-- SELECCIONE PLAN --";
    $select_p .= "	</option>";
    foreach($planes as $k => $ll)
    {
      if($buscador['plan_id']==$ll['plan_id'])
      $selected = " selected ";
      $select_p .= "	<option $selected value=\"".$ll['plan_id']."\">";
      $select_p .= "		".$ll['plan_descripcion'];
      $select_p .= "	</option>";
      $selected = " ";
    }
    $select_p .="	</select>";
	
	$select_c = "<option value=\"\">-- SELECCIONAR CIUDAD --</option>";
	foreach($ciudades as $key => $valor)
	{
	$var = trim($valor['tipo_pais_id'])."@".trim($valor['tipo_dpto_id'])."@".trim($valor['tipo_mpio_id']);
	$select_c .= "<option value=\"".$var."\"  ".(($var===$buscador['localizacion'])? 'selected':'').">";
	$select_c .= "	".$valor['localizacion'];
	$select_c .= "</option>";
	}
	
	$html .= "	<center>";
	$html .= "		<fieldset style=\"width:40%;\">";
	$html .= "				<legend class=\"normal_10AN\">BUSCADOR</legend>";
	$html .= "			<form name=\"buscador\" id=\"buscador\" action=\"".$action['buscar']."\" method=\"POST\">";
	$html .= "				<table class=\"modulo_table_list\" width=\"100%\">";
	$html .= "					<tr class=\"normal_10AN\">";
	$html .= "						<td>";
	$html .= "							CIUDAD";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<select name=\"buscador[localizacion]\" id=\"localizacion\" class=\"select\" style=\"width:100%\">";
	$html .= "								".$select_c;
	$html .= "							</select>";
	$html .= "						</td>";
	$html .= "					</tr>";
	$html .= "					<tr class=\"normal_10AN\">";
	$html .= "						<td>";
	$html .= "							LAPSO";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<input onkeypress=\"return acceptNum(event);\"  maxlength=\"6\" type=\"text\" class=\"input-text\" style=\"width:100%\" name=\"buscador[lapso]\" value=\"".$buscador['lapso']."\">";
	$html .= "						</td>";
	$html .= "					</tr>";
    $html .= "			      	<tr class=\"normal_10AN\">\n";
    $html .= "						<td>";
    $html .= "							PLANES";
    $html .= "						</td>";
    $html .= "						<td colspan=\"2\">";
    $html .= "							".$select_p;
    $html .= "						</td>";
    $html .= "			      </tr>\n";
	$html .= "					<tr>";
	$html .= "						<td colspan=\"2\" align=\"center\">";
	$html .= "							<input type=\"submit\" value=\"BUSCAR\" class=\"input-submit\">";
	$html .= "						</td>";
	$html .= "					</tr>";
	$html .= "				</table>";
	$html .= "			</form>";
	$html .= "		</fieldset>";
	$html .= "	</center>";
	
	$html .= "	<table class=\"modulo_table_list\" width=\"100%\">";
	$html .= "		<tr class=\"modulo_table_list_title\">";
	$html .= "			<td width=\"10%\">";
	$html .= "				LAPSO";
	$html .= "			</td>";
	$html .= "			<td width=\"20%\">";
	$html .= "				FARMACIA";
	$html .= "			</td>";
	$html .= "			<td width=\"20%\">";
	$html .= "				ESTADO LAPSO";
	$html .= "			</td>";
	$html .= "			<td width=\"5%\">";
	$html .= "				CORTES AUDITADOS";
	$html .= "			</td>";
	$html .= "			<td width=\"5%\">";
	$html .= "				NO AUDITADOS";
	$html .= "			</td>";
	$html .= "			<td width=\"5%\">";
	$html .= "				POR FACTURAR";
	$html .= "			</td>";
	$html .= "		</tr>";
	$bandera =0;
	
	foreach($PreFactura as $key => $valor)
	{
	($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
    ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
    $html .= "			<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
	$html .= "			<td>";
	$html .= "				".$valor['lapso'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['descripcion'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['estado'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".FormatoValor($valor['auditado']);
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".FormatoValor($valor['no_auditado']);
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".FormatoValor($valor['faltan_facturar']);
	$html .= "			</td>";
	$total += $valor['faltan_facturar'];
	$html .= "		</tr>";
	if($valor['estado_corte_lapso']==='1')
		$bandera=1;
		if($valor['auditado']<= 0 && $valor['no_auditado']>0)
			$bandera=1;
	}
	if($total <= 0)
	$bandera=1;
	
	if(!empty($PreFactura) && $bandera==0 && !empty($buscador['plan_id']))
	{
	$archivo=$sql->GenerarFacturaTemporal($buscador);
	$html .= "		<tr class=\"modulo_list_oscuro\">";
	$html .= "			<td colspan=\"6\" align=\"center\">";
	$html .= "				<table width=\"100%\" class=\"modulo_table_list\">";
	$html .= "					<tr>";
	$html .= "						<td width=\"50%\" align=\"center\">";
	$html .= "							<form name=\"facturar\" id=\"facturar\" method=\"POST\" action=\"".$action['facturar']."\">";
	$html .= "								<input type=\"submit\" value=\"FACTURAR\" class=\"input-submit\">";
	$html .= "							</form>";
	$html .= "						</td>";
	$html .= "						<td width=\"50%\" align=\"center\">";
	$ubicacion = "tmp/facturas_tmp";
	$destino = "tmp";
    $id = $buscador['lapso']."".$buscador['plan_id']."".UserGetUID();
	$urlDwn = "classes/zipArchive/zipArchiveDownload.php?id=".$id."&ubicacion=".$ubicacion."&destino=".$destino."&nombre_arch=".$id;  
	$html .= "            				<a href=\"".$urlDwn."\" class=\"label_error\" >\n";
	$html .= "								DESCARGAR <img title=\"DESCARGAR FACTURA TEMPORAL\" src=\"".GetThemePath()."/images/guarda.png\" border=\"0\">";
	$html .= "							</a>";
	$html .= "						</td>";
	$html .= "					</tr>";
	$html .= "				</table>";
	$html .= "			</td>";
	$html .= "		</tr>";
	}
	$html .= "	</table>";
	
	/*
	* Teniendo en Cuenta la informacion que arroja la consulta anterior 
	* Verifico si cumplen las condiciones para generar una factura temporal
	*/
	
	
	
	$html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "			<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
    $html .= "        		<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
    $html .= "			</form>\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
	$html .= ThemeCerrarTabla();
	return $html;
	}
	
	
	/*
	* Ventana Para Mostrar Un Mensaje al Usuario en cuanto a la creacion de la factura de Formulacion
	*/
	function ConfirmacionFactura($action,$token,$datos_factura)
	{
	$ctl = AutoCarga::factory("ClaseUtil");
	$html .= $ctl->LimpiarCampos();
    $html .= ThemeAbrirTabla('FACTURAS DE FORMULACION');
	$html .= ThemeCerrarTabla();
	$html .= "<script>";
    if(!$token)
	{
      $html .= " alert(\"ERROR AL CREAR LA FACTURA\");";
	  $html .= " history.go(-1) ";
	 }
      else 
        {
        $html .= " alert(\"Fue Creado con Exito, LA FACTURA : ".$datos_factura['prefijo']." - ".$datos_factura['numeracion']." \");";
        $html .= "window.location=\"".$action['facturas']."\";";
        }
    $html .= "</script>";
	return $html;
	}
	
	/*
	* Funcion Que Permite Generar El Codigo Html para la 
	* Visualizacion de Facturas de Formulacion
	*/
	function Facturas($action,$buscador,$facturas,$prefijos,$planes,$conteo,$pagina)
	{
	$ctl = AutoCarga::factory("ClaseUtil");
    $html .= $ctl->CompararFechas_Javascript();
    $html .= $ctl->LimpiarCampos();
    $html .= $ctl->RollOverFilas();
    $html .= $ctl->AcceptDate('/');
    $html .= $ctl->AcceptNum(false);
	$html .= $ctl->LimpiarCampos();
	$html .= ThemeAbrirTabla('FACTURAS DE FORMULACION');
	$html .= "	<center>";
	$html .= "		<fieldset class=\"normal_10AN\" style=\"width:60%\">";
	$html .= "			<legend>BUSCADOR FACTURAS</legend>";
	$html .= "				<form name=\"buscador\" id=\"buscador\" method=\"POST\" action=\"".$action['buscador']."\">";
	$html .= "					<table width=\"100%\" class=\"modulo_table_list\">";
	$html .= "						<tr class=\"normal_10AN\">";
	$html .= "							<td>PREFIJO</td>";
	$html .= "							<td>";
	$html .= "								<select class=\"select\" name=\"buscador[prefijo]\" id=\"prefijo\" style=\"width:100%\">";
	$html .= "									<option value=\"\">-- SELECCIONE PREFIJO --</option>";
	foreach($prefijos as $key =>$valor)
		{
	$html .= "									<option ".(($valor['prefijo']==$buscador['prefijo'])? "selected":"")." value=\"".trim($valor['prefijo'])."\">".trim($valor['prefijo'])."</option>";
		}
	$html .= "								</select>";
	$html .= "							</td>";
	$html .= "						</tr>";
	$html .= "						<tr class=\"normal_10AN\">";
	$html .= "							<td>NUMERO</td>";
	$html .= "							<td><input type=\"text\" class=\"input-text\" name=\"buscador[factura_fiscal]\" id=\"factura_fiscal\" style=\"width:100%\" value=\"".$buscador['factura_fiscal']."\"></td>";
	$html .= "						</tr>";
	$html .= "						<tr class=\"normal_10AN\">";
	$html .= "							<td>PLAN</td>";
	$html .= "							<td>";
	$html .= "								<select class=\"select\" name=\"buscador[plan_id]\" id=\"plan_id\" style=\"width:100%\">";
	$html .= "									<option value=\"\">-- SELECCIONE PLAN --</option>";
	foreach($planes as $key =>$valor)
		{
	$html .= "									<option ".(($valor['plan_id']==$buscador['plan_id'])? "selected":"")." value=\"".trim($valor['plan_id'])."\">".$valor['plan_descripcion']."</option>";
		}
	$html .= "								</select>";
	$html .= "							</td>";
	$html .= "						</tr>";
	$html .= "						<tr class=\"normal_10AN\">";
	$html .= "							<td>NOMBRE TERCERO</td>";
	$html .= "							<td><input type=\"text\" class=\"input-text\" name=\"buscador[nombre_tercero]\" id=\"nombre_tercero\" style=\"width:100%\" value=\"".$buscador['nombre_tercero']."\"></td>";
	$html .= "						</tr>";
	$html .= "						<tr class=\"normal_10AN\">";
	$html .= "							<td colspan=\"2\" align=\"center\">";
	$html .= "								<input type=\"submit\" value=\"BUSCAR\" class=\"input-submit\">";
	$html .= "							</td>";
	$html .= "						</tr>";
	$html .= "					</table>";
	$html .= "				</form>";
	$html .= "		</fieldset>";
	$html .= "	</center>";
	$pgn = AutoCarga::factory("ClaseHTML");
	
	if(!empty($facturas))
		{
	$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
	$html .= "	<table width=\"100%\" class=\"modulo_table_list\" rules=\"all\">";
	$html .= "		<tr class=\"modulo_table_list_title\">";
	$html .= "			<td>";
	$html .= "				#FACTURA";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				TERCERO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				PLAN";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				TOTAL";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				FECHA";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				USUARIO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				OP";
	$html .= "			</td>";
	$html .= "		</tr>";
	$rpt  = new GetReports();
	foreach($facturas as $key => $valor)
			{
			($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
			($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
    $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
	$html .= "			<td>";
	$html .= "				".$valor['prefijo']." - ".$valor['factura_fiscal'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['tipo_id_tercero']." - ".$valor['tercero_id']." ".$valor['nombre_tercero'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['plan_descripcion'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				$".FormatoValor($valor['total_factura'],2);
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['fecha_registro'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['nombre'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= 										$rpt->GetJavaReport('app','Formulacion_Externa_Facturacion','facturas_formulas',$valor,
													array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
													$fnc  = $rpt->GetJavaFunction();
	$html .= "										<center>\n";
	$html .= "										<a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
	$html .= "	  									<image title=\"IMPRIMIR FACTURA\" src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
	$html .= "  									</a>\n";
	$html .= "										</center>\n";
	$html .= "			</td>";
	$html .= "		</tr>";
			}
	$html .= "	</table>";
		}
	
	$html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "			<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
    $html .= "        		<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
    $html .= "			</form>\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
	$html .= ThemeCerrarTabla();
	return $html;
	}
	
    function Vista_Facturas($accion,$DATOS)
    {
	$html .= " <script>";
	$html .= "  function Validar(Formulario)";
	$html .= "  {";
	$html .= "    if(Formulario.fecha_inicio.value>Formulario.fecha_final.value)";
	$html .= "    {";
	$html .= "     alert(\"Error: La Fecha Inicio No Puede Ser Mayor a La Final!!\");";
	$html .= "    return false;";
	$html .= "    }";

	$html .= " document.factura.submit();";
	$html .= " }";
	$html .= " </script>";

	$ctl = AutoCarga::factory("ClaseUtil");

	$html .= $ctl->LimpiarCampos();
	$html .= $ctl->RollOverFilas();
	$html .= $ctl->AcceptDate('/');
	$html .= ThemeAbrirTabla('GENERAR FACTURA');
	$html .= "<form name=\"factura\" action=\"".$action['buscar']."\" method=\"post\">\n";
	$html .= "  <table width=\"60%\" align=\"center\">\n";
	$html .= "    <tr>\n";
	$html .= "      <td>\n";
	$html .= "	      <fieldset class=\"fieldset\">\n";
	$html .= "          <legend class=\"normal_10AN\">FILTRO PARA LA GENERACION DE FACTURAS</legend>\n";
	$html .= "		      <table width=\"100%\">\n";
	$html .= "            <tr>\n";
	$html .= "              <td class=\"normal_10AN\">FECHA INICIAL</td>\n";
	$html .= "              <td>\n";
	$html .= "                <input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$_REQUEST['buscador']['fecha_inicio']."\">\n";
	$html .= "              </td>\n";
	$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_inicio','/',1)."</td>\n";
	$html .= "            </tr>\n";

	$html .= "            <tr>\n";
	$html .= "              <td class=\"normal_10AN\">FECHA FINAL</td>\n";
	$html .= "              <td>\n";
	$html .= "                <input type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$_REQUEST['buscador']['fecha_final']."\">\n";
	$html .= "              </td>\n";
	$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_final','/',1)."</td>\n";
	$html .= "            </tr>\n";

	$html .= "            <tr>\n";
	$html .= "              <td class=\"normal_10AN\">FACTURA (Prefijo-Numero)</td>\n";
	$html .= "              <td colspan=\"2\">\n";
	$html .= "                <input type=\"text\" name=\"buscador[prefijo]\" id=\"factura\" class=\"input-text\" value=\"".$_REQUEST['buscador']['prefijo']."\">-<input size=\"3\" type=\"text\" name=\"buscador[numero]\" id=\"numero\" class=\"input-text\" value=\"".$_REQUEST['buscador']['numero']."\">\n";
	$html .= "              </td>\n";
	$html .= "            </tr>\n";
	//print_r($_REQUEST);     
	$html .= "			      <tr>\n";
	$html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
	$html .= "					      <input type=\"hidden\" name=\"datos[empresa_id]\" value=\"".$_REQUEST['datos']['empresa_id']."\">\n";
	$html .= "					      <input type=\"hidden\" name=\"datos[ssiid]\" value=\"".$_REQUEST['datos']['ssiid']."\">\n";
	$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\" onclick=\"Validar(document.factura)\">\n";
	$html .= "					      <input type=\"reset\" class=\"input-submit\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.factura);\">\n";
	$html .= "				      </td>\n";
	$html .= "			      </tr>\n";
	$html .= "		      </table>\n";
	$html .= "	      </fieldset>\n";
	$html .= "	    </td>\n";
	$html .= "	  </tr>\n";
	$html .= "	</table>\n";
	$html .= "</form>\n";
	$html .= "  <br>";
	if(!empty($DATOS))
	{
	$html .= "                  <center>";
	$html .= "	                <fieldset class=\"fieldset\" style=\"width:60%\">\n";
	$html .= "                  <legend  class=\"label_error\">FACTURAS </legend>\n";
	$html .= "		                <table width=\"100%\" class=\"modulo_table_list\">\n";
	$html .= "                      <tr class=\"formulacion_table_list\" >";
	$html .= "                        <td width=\"10%\">";
	$html .= "                        #FACTURA";
	$html .= "                        </td>";
	$html .= "                        <td width=\"25%\">";
	$html .= "                        TERCERO";
	$html .= "                        </td>";
	$html .= "                        <td width=\"15%\">";
	$html .= "                        TOTAL";
	$html .= "                        </td>";
	$html .= "                        <td width=\"15%\">";
	$html .= "                        SALDO";
	$html .= "                        </td>";
	$html .= "                        <td width=\"3%\">";
	$html .= "                        OP";
	$html .= "                        </td>";
	$html .= "                      </tr>";
	$est = "modulo_list_claro";
	$bck = "#DDDDDD";
	$suma=0;
	foreach($DATOS as $k1 => $dtl)
	{
	($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
	($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
	$html .= "		              <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
	$html .= "                    <td>";
	$html .= "                    ".$dtl['prefijo']."-".$dtl['factura_fiscal'];
	$html .= "                    </td>";
	$html .= "                     <td>";
	$html .= "                    ".$dtl['tipo_id_tercero']."-".$dtl['tercero_id']." ".$dtl['nombre_tercero'];
	$html .= "                    </td>";
	$html .= "                    <td>";
	$html .= "                    $".FormatoValor($dtl['total_factura'],3);
	$html .= "                    </td>";
	$html .= "                    <td>";
	$html .= "                    $".FormatoValor($dtl['saldo'],3);
	$html .= "                    </td>";
	$html .= "                    <td align=\"center\">";
	$html .= "      				<a href=\"".$accion['crear_glosa']."&prefijo=".$dtl['prefijo']."&factura_fiscal=".$dtl['factura_fiscal']."\" >";
	$html .= "						 <img title=\"CREAR GLOSA\" src=\"".GetThemePath()."/images/pplan.png\" border=\"0\">";
	$html .= "						</a>";
	$html .= "                    </td>";
	$html .= "		              </tr>\n";
	}
	$html .= "               </table>\n";
	$html .= "	                </fieldset>\n";    
	}

	$html .= "<form name=\"forma\" action=\"".$accion['volver']."\" method=\"post\">\n";
	$html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
	$html .= "    <tr>\n";
	$html .= "      <td align=\"center\">\n";
	$html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
	$html .= "      </td>\n";
	$html .= "    </tr>\n";
	$html .= "  </table>\n";
	$html .= "</form>\n";
	$html .= ThemeCerrarTabla();
	return $html;
    }
    
	
	/*
	* VISTA MENU PARA LA GENERACION DE LOS RIPS.
	*/
	function MenuRips($action,$planes,$buscador,$facturas,$token)
	{
	$ctl = AutoCarga::factory("ClaseUtil");
	$html .= $ctl->LimpiarCampos();
	$html .= $ctl->RollOverFilas();
	$html .= $ctl->AcceptDate('/');
	
	$html .= ThemeAbrirTabla('GENERACION DE ENVÌOS - RIPS');
	$html .= "	<center>";
	$html .= "	<fieldset class=\"normal_10AN\" style=\"width:50%\">";
	$html .= "		<legend>BUSCAR FACTURAS POR PLAN</legend>";
	$html .= "			<form name=\"buscador\" id=\"buscador\" method=\"POST\" action=\"".$action['buscador']."\" >";
	$html .= "				<table width=\"100%\" class=\"modulo_table_list\">";
	$html .= "					<tr class=\"normal_10AN\">";
	$html .= "						<td>";
	$html .= "							PLAN";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<select name=\"buscador[plan_id]\" id=\"plan_id\" class=\"select\" style=\"width:100%\">";
	$html .= "								<option value=\"\">-- SELECCIONE PLAN --</option>";
	foreach($planes as $key => $valor)
	{
	$html .= "								<option value=\"".$valor['plan_id']."\" ".(($buscador['plan_id']==$valor['plan_id'])? "selected":"").">";
	$html .= "									".$valor['plan_descripcion'];
	$html .= "								</option>";
	}
	$html .= "							</select>";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "						</td>";
	$html .= "					</tr>";
	$html .= "					<tr class=\"normal_10AN\">";
	$html .= "						<td >";
	$html .= "							FECHA INICIAL :";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "                			<input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$buscador['fecha_inicio']."\" style=\"width:100%\">\n";
	$html .= "              		</td>\n";
	$html .= "		          		<td align=\"left\" class=\"label\" >";
	$html .= "							".ReturnOpenCalendario('buscador','fecha_inicio','/',1);
	$html .= "						</td>\n";
	$html .= "					</tr>";
	$html .= "					<tr class=\"normal_10AN\">";
	$html .= "						<td>";
	$html .= "							FECHA FINAL :";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "                			<input type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$buscador['fecha_final']."\" style=\"width:100%\">\n";
	$html .= "              		</td>\n";
	$html .= "		          		<td align=\"left\" class=\"label\" >";
	$html .= "							".ReturnOpenCalendario('buscador','fecha_final','/',1);
	$html .= "						</td>\n";
	$html .= "					</tr>";
	$html .= "					<tr>";
	$html .= "						<td colspan=\"3\" align=\"center\">";
	$html .= "							<input type=\"submit\" value=\"BUSCAR FACTURAS - PLAN\" class=\"input-submit\">";
	$html .= "						</td>";
	$html .= "					</tr>";
	$html .= "					<tr>";
	$html .= "						<td  align=\"right\" class=\"label_error\">";
	$html .= "							IR A GENERACION DE RIPS";
	$html .= "						</td>";
	$html .= "						<td colspan=\"2\" align=\"left\" class=\"label_error\">";
	$html .= "      					<a href=\"".$action['generacion_rips']."\" >";
    $html .= "						 		<img title=\"DESCARGA DE RIPS A PARTIR DE ENVÌOS\" src=\"".GetThemePath()."/images/abajo.png\" border=\"0\">";
    $html .= "							</a>";
	$html .= "						</td>";
	$html .= "					</tr>";
	$html .= "				</table>";
	$html .= "			</form>";
	$html .= "	</fieldset>";
	$html .= "	</center>";
	$key="";
	$valor="";
	if(!empty($facturas))
		{
	$html .= "	<form name=\"formulario\" method=\"POST\" action=\"".$action['guardar_envio']."\">";
	$html .= "	<table width=\"100%\" class=\"modulo_table_list\">";
	$html .= "		<tr class=\"modulo_table_list_title\">";
	$html .= "			<td width=\"10%\">";
	$html .= "				FACTURA";
	$html .= "			</td>";
	$html .= "			<td width=\"25%\">";
	$html .= "				PLAN";
	$html .= "			</td>";
	$html .= "			<td width=\"35%\">";
	$html .= "				TERCERO PLAN";
	$html .= "			</td>";
	$html .= "			<td width=\"10%\">";
	$html .= "				FECHA";
	$html .= "			</td>";
	$html .= "			<td width=\"15%\">";
	$html .= "				VALOR";
	$html .= "			</td>";
	$html .= "			<td width=\"5%\">";
	$html .= "				OP";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr>";
	$html .= "			<td colspan=\"6\" align=\"center\" class=\"modulo_list_oscuro\">";
	$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"GUARDAR ENVÌO\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$i=0;
	foreach($facturas as $key =>$valor)
			{
	($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
    ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
    $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
	$html .= "			<td>";
	$html .= "				".$valor['prefijo']." - ".$valor['factura_fiscal'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['plan_descripcion'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['nombre_tercero'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['fecha_registro'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				$".FormatoValor($valor['total_factura'],2);
	$html .= "			</td>";
	
	$html .= "			<td align=\"center\">";
	$html .= "				<input type=\"hidden\" name=\"facturas[registros]\" id=\"registros\" value=\"".$i."\">";
	$html .= "				<input type=\"checkbox\" name=\"facturas[".$i."]\" id=\"".$i."\" value=\"".$valor['prefijo']."@".$valor['factura_fiscal']."\" class=\"input-checkbox\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$i++;
		}
	$html .= "		<tr>";
	$html .= "			<td colspan=\"6\" align=\"center\" class=\"modulo_list_oscuro\">";
	$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"GUARDAR ENVÌO\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "	</table>";
	$html .= "	</form>";
		}
	$html .= "<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
	$html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
	$html .= "    <tr>\n";
	$html .= "      <td align=\"center\">\n";
	$html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
	$html .= "      </td>\n";
	$html .= "    </tr>\n";
	$html .= "  </table>\n";
	$html .= "</form>\n";
	$html .= ThemeCerrarTabla();
	$html .= "<script>";
	if(!empty($token))
	$html .= "		alert('SE HA GENERADO EL ENVÌO #".$token['numeracion'].", PUEDE CONTINUAR PARA GENERAR EL INFORME RIPS');";
	$html .= "</script>";
	return $html;
	}
     
    /*
	* VISTA PARA DESCARGA DE RIPS, A PARTIR DE ENVÌOS GENERADOS EN UNA PANTALLA ANTERIOR
	*/
	function DescargaRips($action,$planes,$buscador,$datos_envio,$envios,$comprimir)
	{
	$ctl = AutoCarga::factory("ClaseUtil");
	$html .= $ctl->LimpiarCampos();
    $html .= $ctl->RollOverFilas();
    $html .= $ctl->AcceptDate('/');
    $html .= $ctl->AcceptNum(false);
	$html .= $ctl->LimpiarCampos();
	$html .= ThemeAbrirTabla('DESCARGA - RIPS');
	$html .= "	<center>";
	$html .= "	<fieldset class=\"normal_10AN\" style=\"width:50%\">";
	$html .= "		<legend>BUSCAR ENVIOS RIPS</legend>";
	$html .= "			<form name=\"buscador\" id=\"buscador\" method=\"POST\" action=\"".$action['buscador']."\" >";
	$html .= "				<table width=\"100%\" class=\"modulo_table_list\">";
	$html .= "					<tr class=\"normal_10AN\">";
	$html .= "						<td>";
	$html .= "							PLAN";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<select name=\"buscador[plan_id]\" id=\"plan_id\" class=\"select\" style=\"width:100%\">";
	$html .= "								<option value=\"\">-- SELECCIONE PLAN --</option>";
	foreach($planes as $key => $valor)
	{
	$html .= "								<option value=\"".$valor['plan_id']."\" ".(($buscador['plan_id']==$valor['plan_id'])? "selected":"").">";
	$html .= "									".$valor['plan_descripcion'];
	$html .= "								</option>";
	}
	$html .= "							</select>";
	$html .= "						</td>";
	$html .= "					</tr>";
	$html .= "					<tr class=\"normal_10AN\">";
	$html .= "						<td>";
	$html .= "							NUMERO DE ENVÌO";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<input type=\"text\" name=\"buscador[numeracion]\" id=\"numeracion\" style=\"width:100%\" class=\"input-text\" onkeypress=\"return acceptNum(event);\" value=\"".$buscador['numeracion']."\">";
	$html .= "						</td>";
	$html .= "					</tr>";
	$html .= "					<tr>";
	$html .= "						<td colspan=\"2\" align=\"center\">";
	$html .= "							<input type=\"submit\" class=\"input-submit\" value=\"BUSCAR ENVIO\">";
	$html .= "						</td>";
	$html .= "					</tr>";
	$html .= "				</table>";
	$html .= "			</form>";
	$html .= "	</fieldset>";
	$html .= "	</center>";
	
	$html .= "	<table class=\"modulo_table_list\" width=\"100%\">";
	$html .= "		<tr class=\"modulo_table_list_title\">";
	$html .= "			<td width=\"5%\">";
	$html .= "				#ENVIO";
	$html .= "			</td>";
	$html .= "			<td width=\"10%\">";
	$html .= "				FECHA";
	$html .= "			</td>";
	$html .= "			<td width=\"5%\">";
	$html .= "				TOTAL FACTURAS";
	$html .= "			</td>";
	$html .= "			<td width=\"50%\">";
	$html .= "				PLAN";
	$html .= "			</td>";
	$html .= "			<td width=\"10%\" colspan=\"2\">";
	$html .= "				OP";
	$html .= "			</td>";
	$html .= "		</tr>";
	foreach($envios as $key => $valor)
		{
	($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
	($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
	$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
	$html .= "			<td align=\"center\">";
	$html .= "				".$valor['numeracion'];
	$html .= "			</td>";
	$html .= "			<td >";
	$html .= "				".$valor['fecha_registro'];
	$html .= "			</td>";
	$html .= "			<td align=\"center\">";
	$html .= "				".FormatoValor($valor['total']);
	$html .= "			</td>";
	$html .= "			<td >";
	$html .= "				".$valor['plan_descripcion'];
	$html .= "			</td>";
	$url = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Rips",array("buscador"=>$buscador,"datos_envio"=>$valor));
	$html .= "			<td align=\"center\">";
	$html .= "				<a href=\"".$url."\">";
	$html .= "					<img title=\"GENERAR RIPS\" src=\"".GetThemePath()."/images/uf.png\" border=\"0\">";
	$html .= "				</a>";
	$html .= "			</td>";
	$html .= "			<td align=\"center\">";
	$ubicacion = "tmp/RIPS";
    $id = $comprimir;
    $destino = "tmp";
	$urlDwn = "classes/zipArchive/zipArchiveDownload.php?id=".$id."&ubicacion=".$ubicacion."&destino=".$destino."&nombre_arch=".$id;  
	
	$html .= "				<a href=\"".$urlDwn."\">";
	if($valor['numeracion']===$datos_envio['numeracion'] && !empty($comprimir))
	$html .= "					<img title=\"DESCARGAR RIPS\" src=\"".GetThemePath()."/images/pguardar.png\" border=\"0\">";
	$html .= "				</a>";
	$html .= "			</td>";
	$html .= "		</tr>";
		}
	$html .= "	</table>";
	$html .= "	<br>";
	$html .= "<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
	$html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
	$html .= "    <tr>\n";
	$html .= "      <td align=\"center\">\n";
	$html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
	$html .= "      </td>\n";
	$html .= "    </tr>\n";
	$html .= "  </table>\n";
	$html .= "</form>\n";
	$html .= ThemeCerrarTabla();
	return $html;
	}
	
    function Forma_NuevaGlosa($accion,$MotivoGlosa,$DATOS,$glosas_concepto_general)
    {
    $ctl = AutoCarga::factory("ClaseUtil");
 	$html .= $ctl->LimpiarCampos();
 	$html .= $ctl->RollOverFilas();
 	$html .= $ctl->AcceptDate('/');
      
	  $Select_MotivoGlosa  = "	<select class=\"select\" name=\"motivo_glosa_id\"name=\"motivo_glosa_id\" style=\"width:50%\">";
	  $Select_MotivoGlosa .= "	<option value=\"\">-- SELECCIONAR --</option>";
	  foreach($MotivoGlosa as $key => $valor)
	  $Select_MotivoGlosa .= "	<option value=\"".$valor['motivo_glosa_id']."\">".$valor['motivo_glosa_id']." - ".$valor['motivo_glosa_descripcion']."</option>";
	  $Select_MotivoGlosa .= "	</select>";
	  
	  $Select_GlosaConceptoGeneral  = "	<select class=\"select\" name=\"codigo_concepto_general\"name=\"codigo_concepto_general\" style=\"width:50%\" onchange=\"xajax_Listado_ConceptoEspecifico(this.value);\">";
	  $Select_GlosaConceptoGeneral .= "	<option value=\"\">-- SELECCIONAR --</option>";
	  foreach($glosas_concepto_general as $key => $valor)
	  $Select_GlosaConceptoGeneral .= "	<option value=\"".$valor['codigo_concepto_general']."\">".$valor['codigo_concepto_general']." - ".$valor['descripcion_concepto_general']."</option>";
	  $Select_GlosaConceptoGeneral .= "	</select>";
            
      $html .= "<script>\n";
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
	  $html .= " <script>";
    $html .= "  function Validar(Formulario)";
    $html .= "  {";
    $html .= "    if(Formulario.fecha_glosa.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: La Fecha Glosa No Puede Estar Vacia!!\");";
    $html .= "    return false;";
    $html .= "    }";
    $html .= "    if(Formulario.motivo_glosa_id.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: No hay Motivo Glosa Seleccionada!!\");";
    $html .= "    return false;";
    $html .= "    }";
    $html .= "    if(Formulario.codigo_concepto_general.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: No hay Concepto General Glosa Seleccionada!!\");";
    $html .= "    return false;";
    $html .= "    }";
    $html .= "    if(Formulario.codigo_concepto_especifico.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: No hay Concepto Especifico Glosa Seleccionada!!\");";
    $html .= "    return false;";
    $html .= "    }";
    
    $html .= " document.esm_glosa.submit();";
    $html .= " }";
    $html .= " </script>";
    $html .= $ctl->AcceptNum(false);
    $html .= ThemeAbrirTabla('CREAR NUEVA GLOSA',"70%");
    $html .= "  <center>";
    $html .= "  <table border=\"1\" width=\"100%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FACTURA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['prefijo']."-".$DATOS['factura_fiscal'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FECHA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['fecha_registro'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      USUARIO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['nombre'];
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      TERCERO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['tipo_id_tercero']." ".$DATOS['tercero_id']."-".$DATOS['nombre_tercero'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      PLAN";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['plan_descripcion'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      SALDO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          $".FormatoValor($DATOS['saldo'],2);
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "  </table>\n";
    $html .= "</center>";
	$html .= "<br>";
	$html .= "  <center>";
	$html .= "	<form name=\"esm_glosa\" action=\"".$accion['guardar']."\" id=\"esm_glosa\" method=\"POST\">";
    $html .= "  <table border=\"1\" width=\"60%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"50%\">";
    $html .= "      FECHA GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          <input type=\"text\" name=\"fecha_glosa\" id=\"fecha_glosa\" readonly=\"true\" class=\"input-text\"> ".ReturnOpenCalendario('esm_glosa','fecha_glosa','-',1)."";
    $html .= "      </td>";
    $html .= "    </tr>";
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"50%\">";
    $html .= "      MOTIVO GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$Select_MotivoGlosa;
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"50%\">";
    $html .= "      CONCEPTO GENERAL";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$Select_GlosaConceptoGeneral;
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"50%\">";
    $html .= "      CONCEPTO ESPECIFICO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          <select name=\"codigo_concepto_especifico\" id=\"codigo_concepto_especifico\" style=\"width:50%\" class=\"select\">";
    $html .= "          <option value=\"\">-- SELECCIONAR --</option>";
    $html .= "          </select>";
    $html .= "      </td>";
    $html .= "    </tr>";
	
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"50%\">";
    $html .= "      # DOCUMENTO CLIENTE";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          <input type=\"text\" name=\"documento_interno_cliente_id\" id=\"documento_interno_cliente_id\" class=\"input-text\">";
    $html .= "      </td>";
    $html .= "    </tr>";
	
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"50%\">";
    $html .= "      GLOSA TODO EL DOCUMENTO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          <input type=\"checkbox\" name=\"sw_glosa_total_factura\" id=\"sw_glosa_total_factura\" value=\"1\" class=\"input-checbox\">";
    $html .= "      </td>";
    $html .= "    </tr>";
	
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"50%\">";
    $html .= "      GLOSA POR MAYOR VALOR";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          <input type=\"checkbox\" name=\"sw_mayor_valor\" id=\"sw_mayor_valor\" value=\"1\" class=\"input-checbox\">";
    $html .= "      </td>";
    $html .= "    </tr>";
    
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" colspan=\"2\">";
    $html .= "      OBSERVACION";
    $html .= "      </td>";
    $html .= "    </tr>";
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" colspan=\"2\">";
    $html .= "      <textarea class=\"textarea\" style=\"width:100%\" name=\"observacion\" id=\"observacion\"></textarea>";
    $html .= "      </td>";
    $html .= "    </tr>";
    
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" colspan=\"2\">";
    $html .= "			<input type=\"hidden\" name=\"prefijo\" id=\"prefijo\" value=\"".$_REQUEST['prefijo']."\">";
    $html .= "			<input type=\"hidden\" name=\"factura_fiscal\" id=\"factura_fiscal\" value=\"".$_REQUEST['factura_fiscal']."\">";
    $html .= "			<input type=\"hidden\" name=\"datos[empresa_id]\" id=\"datos[empresa_id]\" value=\"".$_REQUEST['datos']['empresa_id']."\">";
    $html .= "			<input type=\"hidden\" name=\"datos[ssiid]\" id=\"datos[ssiid]\" value=\"".$_REQUEST['datos']['ssiid']."\">";
	$html .= "          <input type=\"button\" value=\"REGISTRAR GLOSA\" class=\"input-submit\" onclick=\"Validar(document.esm_glosa);\">";
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "  </table>\n";
    $html .= " </form>";
	$html .= "</center>";
    
    $html .= "<form name=\"forma\" action=\"".$accion['volver']."\" method=\"post\">\n";
    $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
    $html .= "</form>\n";
    $html .= "  <script>";
    $html .= "  xajax_Listado_Productos_TMP('".$_REQUEST['orden_requisicion_tmp_id']."');";
    $html .= "  </script>";
    $html .= ThemeCerrarTabla();
    return $html;
    }
    
	
	function Forma_Glosa($accion,$DATOS,$DATOS_DETALLE)
    {
    $ctl = AutoCarga::factory("ClaseUtil");
 	$html .= $ctl->LimpiarCampos();
 	$html .= $ctl->RollOverFilas();
 	$html .= $ctl->AcceptDate('/');
          
      $html .= "<script>\n";
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
  $html .= " <script>";
  $html .= "  function Validar(Formulario)";
  $html .= "  {";
  $html .= "    if(Formulario.fecha_glosa.value==\"\")";
  $html .= "    {";
  $html .= "     alert(\"Error: La Fecha Glosa No Puede Estar Vacia!!\");";
  $html .= "    return false;";
  $html .= "    }";
  $html .= "    if(Formulario.motivo_glosa_id.value==\"\")";
  $html .= "    {";
  $html .= "     alert(\"Error: No hay Motivo Glosa Seleccionada!!\");";
  $html .= "    return false;";
  $html .= "    }";
  $html .= "    if(Formulario.codigo_concepto_general.value==\"\")";
  $html .= "    {";
  $html .= "     alert(\"Error: No hay Concepto General Glosa Seleccionada!!\");";
  $html .= "    return false;";
  $html .= "    }";
  $html .= "    if(Formulario.codigo_concepto_especifico.value==\"\")";
  $html .= "    {";
  $html .= "     alert(\"Error: No hay Concepto Especifico Glosa Seleccionada!!\");";
  $html .= "    return false;";
  $html .= "    }";
  $html .= " document.esm_glosa.submit();";
  $html .= " }";
  $html .= " </script>";
  
  $html .= "	<script>";
  $html .= "	function Asignar(campo,valor)";
  $html .= "	{"; 
  $html .= "  var total;";
  $html .= "  total = (document.getElementById('valor_aceptado').value-document.getElementById('valor_no_aceptado').value);";
  $html .= "  if(total<0)";
  $html .= "	valor=0;"; 
  $html .= "	document.getElementById(campo).value=valor;"; 
  $html .= "	}";  
  
  $html .= "	function CalculoNoAceptado(campo,valor_glosa,valor_aceptado)";
  $html .= "	{"; 
  $html .= "   var valor = (valor_glosa-valor_aceptado);";
  $html .= "   if(valor<0)";
  $html .= "      valor=0;";
  $html .= "	document.getElementById(campo).value=valor;"; 
  $html .= "	"; 
  $html .= "	}";   
  $html .= "	function Validar_AceptarGlosa(Formulario)";
  $html .= "	{"; 
  $html .= "   if(isNaN(Formulario.valor_aceptado))";
  $html .= "    {";
  $html .= "      alert('El Numero No Es Correcto!!');";
  $html .= "      return false;";
  $html .= "    }";
  $html .= "   if(isNaN(Formulario.valor_no_aceptado))";
  $html .= "    {";
  $html .= "      alert('El Numero No Es Correcto!!');";
  $html .= "      return false;";
  $html .= "    }";
  $html .= "   if(Formulario.valor_no_aceptado <=0 && Formulario.valor_aceptado <=0)";
  $html .= "    {";
  $html .= "      alert('Uno De Los Valores Debe Ser Mayor a Cero!!');";
  $html .= "      return false;";
  $html .= "    }";
  $html .= "   var entrar = confirm('Confirma Aceptar la Glosa?');";
  $html .= "    if (entrar) ";
  $html .= "    {";
  
  $html .= "      if(Formulario.sw_glosa_total_factura=='0')";
  $html .= "        xajax_AceptarGlosaDetalle(xajax.getFormValues('forma_glosa'));";
  $html .= "        else";
  $html .= "            xajax_AceptarGlosaTotal(xajax.getFormValues('forma_glosa'));";
  $html .= "    }";
  $html .= "        else";
  $html .= "              {";
  $html .= "              return(false);";
  $html .= "              }";
  $html .= "	"; 
  $html .= "	}";   
  
  $html .= "	function Validar_AnularGlosa(Formulario)";
  $html .= "	{"; 
  $html .= "   var entrar = confirm('Confirma Anular la Glosa?');";
  $html .= "    if (entrar) ";
  $html .= "    {";
  $html .= "        xajax_AnularGlosaDetalle(xajax.getFormValues('forma_glosa'));";
  $html .= "    }";
  $html .= "        else";
  $html .= "              {";
  $html .= "              return(false);";
  $html .= "              }";
  $html .= "	"; 
  $html .= "	}";   
  $html .= "	function ValidarCantidad(campo,valor,cant_sol,capa)\n";
	$html .= "	{\n";
	$html .= "		document.getElementById(campo).style.background='';\n";
	$html .= "		document.getElementById('error').innerHTML='';\n";
	$html .= "		if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='')\n";
	$html .= "		{\n";
	$html .= "			document.getElementById(campo).value='';\n";
	$html .= "			document.getElementById(campo).style.background='#ff9595';\n";
	$html .= "			document.getElementById('error').innerHTML='<center>VALOR NO VALIDO</center>';\n";
  $html .= "		}\n";
	$html .= "	}\n";
  
  $html .= "	function AnularGlosa(esm_glosa_id)";
  $html .= "	{"; 
  $html .= "   var entrar = confirm('Confirma Anular la Glosa?');";
  $html .= "    if (entrar) ";
  $html .= "    {";
  $html .= "        xajax_AnularGlosa(esm_glosa_id);";
  $html .= "    }";
  $html .= "        else";
  $html .= "              {";
  $html .= "              return(false);";
  $html .= "              }";
  $html .= "	"; 
  $html .= "	}";   
  
  $html .= " </script>";
    $html .= $ctl->AcceptNum(true);
    $html .= ThemeAbrirTabla('GLOSA');
	if($DATOS['sw_glosa_total_factura']=='1')
		$mensaje=" GLOSA A TODA LA FACTURA";
		else
		$mensaje=" GLOSA AL DETALLE DE LA FACTURA";
		
	if($DATOS['sw_mayor_valor']=='1')
		$mensaje_=" GLOSA POR MAYOR VALOR";
		else
		$mensaje_=" GLOSA POR MENOR VALOR";
	
    $html .= "  <center>";
	 $html .= "  <table border=\"1\" width=\"100%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          #".$DATOS['esm_glosa_id'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      TIPO GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$mensaje;
    $html .= "      			<a href=\"".$accion['cambiar_tipo_glosa']."\" >";
    $html .= "						 <img title=\"CAMBIAR TIPO GLOSA\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">";
    $html .= "						</a>";
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      MOTIVO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['motivo_glosa_descripcion'];
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      CONCEPTO GENERAL";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['descripcion_concepto_general'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      CONCEPTO ESPECIFICO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['descripcion_concepto_especifico'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      VALOR GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          $".FormatoValor($DATOS['valor_glosa'],2);
    $html .= "      </td>";
    $html .= "    </tr>";    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      VALOR ACEPTADO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "           $".FormatoValor($DATOS['valor_aceptado'],2);
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      VALOR NO ACEPTADO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          $".FormatoValor($DATOS['valor_no_aceptado'],2);
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$mensaje_;
    $html .= "      </td>";
    $html .= "    </tr>";
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" colspan=\"6\">";
    $html .= "      OBSERVACION";
    $html .= "      </td>";
	$html .= "    </tr>";
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td align=\"center\" colspan=\"6\">";
    $html .= "          ".$DATOS['observacion'];
    $html .= "      </td>";
    $html .= "    </tr>";
	$html .= "  </table>\n";
    $html .= "</center>";
	$html .= "<br>";
	$html .= "  <center>";
    $html .= "  <table border=\"1\" width=\"20%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td width=\"50%\" class=\"modulo_table_list_title\">";
	$html .= "		GLOSAR";
	$html .= "      </td>";
	$html .= "      <td align=\"center\" width=\"20%\">";
	/*
  * Para evaluar si la glosa es para toda la factura o por detalle
  */
  if($DATOS['sw_glosa_total_factura']=='0')
  {
  $Link  = "       				<a href=\"".$accion['glosar']."\" >";
	$Link .= "						 <img title=\"CREAR GLOSA\" src=\"".GetThemePath()."/images/pplan.png\" border=\"0\">";
  }
    else
        {
        $Link  = "       				<a onclick=\"xajax_VerGlosa_Total('".$DATOS['esm_glosa_id']."');\">";
        $Link .= "						 <img title=\"CREAR GLOSA\" src=\"".GetThemePath()."/images/pplan.png\" border=\"0\">";
        }
  $html .= "                ".$Link;
	$html .= "						</a>";
    $html .= "      </td>";
	$html .= "	  </tr>";
	$html .= "	</table>";
	$html .= "<br>";
    $html .= "  <center>";
    $html .= "  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FACTURA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          #".$DATOS['prefijo']."-".$DATOS['factura_fiscal'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FECHA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['fecha_registro'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      USUARIO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['nombre'];
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      TERCERO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['tipo_id_tercero']." ".$DATOS['tercero_id']."-".$DATOS['nombre_tercero'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      PLAN";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['plan_descripcion'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      SALDO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          $".FormatoValor($DATOS['saldo'],2);
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "  </table>\n";
    $html .= "</center>";
	$html .= "<br>";
	
	if(!empty($DATOS_DETALLE) && $DATOS['sw_glosa_total_factura']=='0')
	{
	$html .= "  <center>";
    $html .= "  <table border=\"1\" width=\"100%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_table_list_title\">";
    $html .= "      <td >";
    $html .= "      PRODUCTO";
    $html .= "      </td>";
	$html .= "      <td >";
    $html .= "      VALOR GLOSA";
    $html .= "      </td>";
	$html .= "      <td >";
    $html .= "      VALOR ACEPTADO";
    $html .= "      </td>";
	$html .= "      <td >";
    $html .= "      VALOR NO ACEPTADO";
    $html .= "      </td>";
	$html .= "      <td >";
    $html .= "      MOTIVO";
    $html .= "      </td>";
	$html .= "      <td >";
    $html .= "      CONCEPTO GENERAL";
    $html .= "      </td>";
	$html .= "      <td >";
    $html .= "      CONCEPTO ESPECIFICO";
    $html .= "      </td>";
	$html .= "      <td >";
    $html .= "      OP";
    $html .= "      </td>";
	$html .= "    </tr>";

			$est = "modulo_list_claro";
            $bck = "#DDDDDD";
	foreach($DATOS_DETALLE as $key => $valor)
		{
		($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
		($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
	$html .= "	    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
	$html .= "      	<td>";
	$html .= "				".$valor['producto'];
	$html .= "			</td>";
	$html .= "      	<td>";
	$html .= "				$".FormatoValor($valor['valor_glosa'],2);
	$html .= "			</td>";
	$html .= "      	<td>";
	$html .= "				$".FormatoValor($valor['valor_aceptado'],2);
	$html .= "			</td>";
	$html .= "      	<td>";
	$html .= "				$".FormatoValor($valor['valor_no_aceptado'],2);
	$html .= "			</td>";
	$html .= "      	<td>";
	$html .= "				".$valor['motivo_glosa_descripcion'];
	$html .= "			</td>";
	$html .= "      	<td>";
	$html .= "				".$valor['descripcion_concepto_general'];
	$html .= "			</td>";
	$html .= "      	<td>";
	$html .= "				".$valor['descripcion_concepto_especifico'];
	$html .= "			</td>";
	$html .= "			<td>";
	if($valor['sw_estado']=='1')
    {
    $link = "      		<a onclick=\"xajax_VerGlosa('".$valor['esm_glosa_detalle_id']."','".$valor['esm_glosa_id']."');\" >";
    $link .= "        <img title=\"GLOSA\" src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\">";
    }
    else
      {
      $link = "      		<a onclick=\"xajax_VerGlosa('".$valor['esm_glosa_detalle_id']."','".$valor['esm_glosa_id']."');\" >";
      $link .= "        <img title=\"GLOSA\" src=\"".GetThemePath()."/images/folder_lleno.png\" border=\"0\">";
      }
  $html .=  $link;
	$html .= "				</a>";
	$html .= "			</td>";
	$html .= "		</tr>";
		}
	$html .= "	</table>";
	}
   
    $html .= "<form name=\"forma\" action=\"".$accion['volver']."\" method=\"post\">\n";
    $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"button\" value=\"APLICAR GLOSA\" onclick=\"xajax_AplicarGlosaGeneral('".$DATOS['esm_glosa_id']."');\">\n";
    $html .= "      </td>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"button\" value=\"ANULAR GLOSA\" onclick=\"AnularGlosa('".$DATOS['esm_glosa_id']."');\">\n";
    $html .= "      </td>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
    $html .= "</form>\n";
    $html .= ThemeCerrarTabla();
	$html .= $this->CrearVentana(740,"GLOSA");
    return $html;
    }
    
    
   	function Forma_Glosar($accion,$DATOS,$DetalleFactura,$MotivoGlosa,$glosas_concepto_general)
    {
    $ctl = AutoCarga::factory("ClaseUtil");
 	$html .= $ctl->LimpiarCampos();
 	$html .= $ctl->RollOverFilas();
 	$html .= $ctl->AcceptDate('/');
	$Select_MotivoGlosa  = "	<select class=\"select\" name=\"motivo_glosa_id\"name=\"motivo_glosa_id\" style=\"width:50%\">";
	$Select_MotivoGlosa .= "	<option value=\"\">-- SELECCIONAR --</option>";
	foreach($MotivoGlosa as $key => $valor)
	$Select_MotivoGlosa .= "	<option value=\"".$valor['motivo_glosa_id']."\">".$valor['motivo_glosa_id']." - ".$valor['motivo_glosa_descripcion']."</option>";
	$Select_MotivoGlosa .= "	</select>";
	
	
	
	$html .= "	<script>";
	$html .= "	function Asignar(campo,valor)";
	$html .= "	{"; 
	$html .= "	document.getElementById(campo).value=valor;"; 
	$html .= "	"; 
	$html .= "	}";
	
	$html .= "	function ValidarCantidad(campo,valor,cant_sol,capa)\n";
	$html .= "	{\n";
	$html .= "		document.getElementById(campo).style.background='';\n";
	$html .= "		document.getElementById('error').innerHTML='';\n";
	$html .= "		if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='')\n";
	$html .= "		{\n";
	$html .= "			document.getElementById(campo).value='';\n";
	$html .= "			document.getElementById(campo).style.background='#ff9595';\n";
	$html .= "			document.getElementById('error').innerHTML='<center>VALOR NO VALIDO</center>';\n";
  $html .= "		}\n";
	$html .= "	}\n";
	
      $html .= "</script>\n";
      $html .= "<script>\n";
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
	  $html .= " <script>";
    $html .= "  function Validar(Formulario)";
    $html .= "  {";
    $html .= "    if(Formulario.fecha_glosa.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: La Fecha Glosa No Puede Estar Vacia!!\");";
    $html .= "    return false;";
    $html .= "    }";
    $html .= "    if(Formulario.motivo_glosa_id.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: No hay Motivo Glosa Seleccionada!!\");";
    $html .= "    return false;";
    $html .= "    }";
    $html .= "    if(Formulario.codigo_concepto_general.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: No hay Concepto General Glosa Seleccionada!!\");";
    $html .= "    return false;";
    $html .= "    }";
    $html .= "    if(Formulario.codigo_concepto_especifico.value==\"\")";
    $html .= "    {";
    $html .= "     alert(\"Error: No hay Concepto Especifico Glosa Seleccionada!!\");";
    $html .= "    return false;";
    $html .= "    }";
    
    $html .= " document.esm_glosa.submit();";
    $html .= " }";
    $html .= " </script>";
    $html .= $ctl->AcceptNum(true);
    $html .= ThemeAbrirTabla('GLOSA');
	if($DATOS['sw_glosa_total_factura']=='1')
		$mensaje=" GLOSA A TODA LA FACTURA";
		else
		$mensaje=" GLOSA AL DETALLE DE LA FACTURA";
		
	if($DATOS['sw_mayor_valor']=='1')
		$mensaje_=" GLOSA POR MAYOR VALOR";
		else
		$mensaje_=" GLOSA POR MENOR VALOR";
	
    $html .= "  <center>";
	 $html .= "  <table border=\"1\" width=\"100%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          #".$DATOS['prefijo']."-".$DATOS['factura_fiscal'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      TIPO GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$mensaje;
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      MOTIVO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['motivo_glosa_descripcion'];
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      CONCEPTO GENERAL";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['descripcion_concepto_general'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      CONCEPTO ESPECIFICO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['descripcion_concepto_especifico'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      VALOR GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          $".FormatoValor($DATOS['valor_glosa'],2);
    $html .= "      </td>";
    $html .= "    </tr>";    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      VALOR ACEPTADO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "           $".FormatoValor($DATOS['valor_aceptado'],2);
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      VALOR NO ACEPTADO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          $".FormatoValor($DATOS['valor_no_aceptado'],2);
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      GLOSA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$mensaje_;
    $html .= "      </td>";
    $html .= "    </tr>";
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" colspan=\"6\">";
    $html .= "      OBSERVACION";
    $html .= "      </td>";
	$html .= "    </tr>";
	$html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td align=\"center\" colspan=\"6\">";
    $html .= "          ".$DATOS['observacion'];
    $html .= "      </td>";
    $html .= "    </tr>";
	$html .= "  </table>\n";
    $html .= "</center>";
	$html .= "<br>";
	$html .= "  <center>";
    $html .= "  <table border=\"1\" width=\"100%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FACTURA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          #".$DATOS['prefijo']."-".$DATOS['factura_fiscal'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FECHA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['fecha_registro'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      USUARIO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['nombre'];
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      TERCERO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['tipo_id_tercero']." ".$DATOS['tercero_id']."-".$DATOS['nombre_tercero'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      PLAN";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DATOS['plan_descripcion'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      SALDO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          $".FormatoValor($DATOS['saldo'],2);
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "  </table>\n";
    $html .= "</center>";
	$html .= "<br>";

	
	$Select_GlosaConceptoGeneral .= "	<option value=\"\">-- SELECCIONAR --</option>";
	foreach($glosas_concepto_general as $key => $valor)
	$Select_GlosaConceptoGeneral .= "	<option value=\"".$valor['codigo_concepto_general']."\">".$valor['codigo_concepto_general']." - ".$valor['descripcion_concepto_general']."</option>";
	$Select_GlosaConceptoGeneral .= "	</select>";
	
  if(!empty($DetalleFactura))
	{
    $html .= "<center>";
	$html .= "	<div id=\"error\" class=\"label_error\"></div>";
	$html .= "	<form id=\"GlosarItems\" name=\"GlosarItems\" method=\"POST\">";
	$i=0;
	$html .= "  <table border=\"1\" width=\"50%\" align=\"center\" rules=\"all\">\n";
	$html .= "    <tr class=\"modulo_list_claro\">";
	$html .= "      <td class=\"modulo_table_list_title\" WIDTH=\"50%\">";
	$html .= "		MOTIVO GLOSA";
	$html .= "      </td>";
	$html .= "      <td class=\"modulo_list_claro\" >";
	$html .= "		".$Select_MotivoGlosa;
	$html .= "      </td>";
	$html .= "		<td>";
	$html .= "		<input type=\"button\" value=\"GUARDAR GLOSA\" class=\"input-submit\" onclick=\"xajax_GuardarGlosa(xajax.getFormValues('GlosarItems'));\"";
	$html .= "		</td>";
	$html .= "	</tr>";
	$html .= "	</table>";
	$html .= "	<br>";
	foreach($DetalleFactura as $k1 => $dtl)
	{
	($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
	($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
	$html .= "  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" width=\"15%\">";
    $html .= "      PRODUCTO";
    $html .= "      </td>";
	$html .= "      <td WIDTH=\"20%\">";
    $html .= "      ".$dtl['producto'];
    $html .= "      </td>";
	$html .= "      <td class=\"modulo_table_list_title\" width=\"10%\">";
    $html .= "      PRECIO";
    $html .= "      </td>";
	$html .= "      <td width=\"15%\">";
    $html .= "      $".FormatoValor($dtl['precio'],2);
    $html .= "      </td>";
	$html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      VALOR TOTAL";
    $html .= "      </td>";
	$html .= "      <td width=\"10%\">";
    $html .= "      $".FormatoValor($dtl['valor_total'],2);
    $html .= "      </td>";
	$html .= "		<td class=\"modulo_table_list_title\">";
	$html .= "		CANTIDAD";
	$html .= "		</td>";
	$html .= "		<td>";
	$html .= "		<b>".$dtl['cantidad']."</b>";
	$html .= "		</td>";
	$html .= "			<td rowspan=\"2\" align=\"center\">";
	$html .= "                    <input type=\"checkbox\" class=\"input-checkbox\" value=\"".$dtl['codigo_producto']."\" name=\"".$i."\" id=\"".$i."\">";
	$html .= "                    </td>";
	$html .= "	</tr>";
    
	$html .= "	 <tr class=\"modulo_list_claro\" >\n";
	$html .= "		<td class=\"modulo_table_list_title\">";
    $html .= "      VALOR GLOSA";
    $html .= "      </td>";
	$html .= "		<td>";
	$html .= "			<a onclick=\"Asignar('valor_glosa".$i."','".$dtl['valor_total']."');\" >";
	$html .= "			<img title=\"Valor Glosa\" src=\"".GetThemePath()."/images/hcright.png\" border=\"0\">";
	$html .= "			</a>";
	$html .= "          <input type=\"text\" name=\"valor_glosa".$i."\" id=\"valor_glosa".$i."\" onkeypress=\"return acceptNum(event);\" class=\"input-text\" value=\"0\" onkeyup=\"ValidarCantidad('valor_glosa".$i."',document.getElementById('valor_glosa".$i."').value,'".$dtl['valor_total']."','hell".$i."')\">";
	$html .= "     </td>";
	$html .= "                    	<td class=\"modulo_table_list_title\">";
	$html .= "                    	CONCEPTO GENERAL";
	$html .= "                    	</td>";
	$html .= "                    	<td>";
	$html .= "						<select class=\"select\" name=\"codigo_concepto_general".$i."\"name=\"codigo_concepto_general".$i."\" style=\"width:50%\" onchange=\"xajax_Listado_ConceptoEspecifico(this.value,'".$i."');\">";
	$html .= "						".$Select_GlosaConceptoGeneral;
	$html .= "						</select>";
	$html .= "                    	</td>";
	$html .= "                    	<td class=\"modulo_table_list_title\">";
	$html .= "                    	CONCEPTO ESPECIFICO";
	$html .= "                    	</td>";
	$html .= "                    	<td>";
	$html .= "          			<select name=\"codigo_concepto_especifico".$i."\" id=\"codigo_concepto_especifico".$i."\" style=\"width:50%\" class=\"select\">";
	$html .= "          			<option value=\"\">-- SELECCIONAR --</option>";
    $html .= "          			</select>";
	$html .= "                    	</td>";
	$html .= "                    	<td class=\"modulo_table_list_title\">";
	$html .= "                    	OBSERVACION";
	$html .= "                    	</td>";
	$html .= "                    	<td>";
	$html .= "                    	<textarea name=\"observacion".$i."\" id=\"observacion".$i."\" class=\"textarea\" style=\"width:100%\"></textarea>";
	$html .= "                    	</td>";
	
	$html .= "					</tr>";
	$html .= "  </table>\n";
	$html .= "<br>";
	$i++;
	}
	$html .= "  <table border=\"1\" width=\"50%\" align=\"center\" rules=\"all\">\n";
	$html .= "    <tr class=\"modulo_list_claro\">";
	$html .= "		<td align=\"center\">";
	$html .= "		<input type=\"hidden\" value=\"".$_REQUEST['esm_glosa_id']."\" name=\"esm_glosa_id\" id=\"esm_glosa_id\">";
	$html .= "		<input type=\"hidden\" value=\"".$DATOS['prefijo']."\" name=\"prefijo\" id=\"prefijo\">";
	$html .= "		<input type=\"hidden\" value=\"".$DATOS['factura_fiscal']."\" name=\"factura_fiscal\" id=\"factura_fiscal\">";
	$html .= "		<input type=\"hidden\" value=\"".$i."\" name=\"registros\" id=\"registros\">";
	$html .= "		<input type=\"button\" value=\"GUARDAR GLOSA\" class=\"input-submit\" onclick=\"xajax_GuardarGlosa(xajax.getFormValues('GlosarItems'));\"";
	$html .= "		</td>";
	$html .= "	</tr>";
	$html .= "	</table>";
	$html .= "	</form>";
    $html .= "</center>";
	
	}
      
    $html .= "<form name=\"forma\" action=\"".$accion['volver']."\" method=\"post\">\n";
    $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
    $html .= "</form>\n";
    $html .= ThemeCerrarTabla();
    return $html;
    }
  
  
  
  // CREAR LA CAPITA
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
	
  // CREAR LA CAPITA
	function CrearVentana1($tmn,$Titulo)
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
      

    
      return $html;
    }    
    
    
  
  }
?>