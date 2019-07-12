<?php

/**
 * @package DUANA
 * @version 1.0 $Id: Remotos_repositorio.php
 * @copyright (C) 2012 DUANA & CIA 
 * @author R.O.M.A
 */

/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 */
//util
//$objResponse->script("alert('Nota Anulada, con Exito');");


/* * ****************************************************************************************
 * Remoto: Retornar los campos del formulario de acuerdo al tipo de 
 * documento a cargar en el repositorio-servidor
 * **************************************************************************************** */
function Campos_tipoArch($cod_tipo, $empresa)
{
    $objResponse = new xajaxResponse();

    //$vista = AutoCarga::factory("Repositorio_MenuHTML", "views", "app", "RepositorioDocumentacion");
    $sql = AutoCarga::factory("Permisos", "classes", "app", "RepositorioDocumentacion");
    $esm = $sql->ListarEmpresas();
    $depen = $sql->ListarDptos($empresa);
    $TipoId = $sql->GetTipoId();

    //para facturas/informes
    if ($cod_tipo == 5 || $cod_tipo == 7)
    {
        $TipoFac = $sql->GetTipoFac($cod_tipo);
    }

    switch ($cod_tipo)
    {
        case 1: //Orden requisicion//
            $html .= " <br><br>";
            $html .= "	<table border=\"-1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td colspan=\"2\">DATOS DE ORDEN REQUISICION</td>\n";
            $html .= "		</tr>\n";

            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">SOLICITUD A EMPRESA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetCentroU(this.value);\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos)
            {
                $html .= "					<option value=\"" . $datos['empresa_id'] . "\">" . $datos['razon_social'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Centro Utilidad */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">CENTRO UTILIDAD: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "              <div id=\"select_cu\">";
            $html .= "				<select name=\"centro_utilidad_arch\" id=\"centro_utilidad_arch\" class=\"select\" onchange=\"xajax_GetBodega(this.value,document.getElementById('empresa_arch').value)\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "				</select>\n";
            $html .= "			     </div>";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Bodega */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">BODEGA/FARMACIA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "          <div id=\"select_bod\">";
            $html .= "				<select name=\"bodega_arch\" id=\"bodega_arch\" class=\"select\" onchange=\"\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "				</select>\n";
            $html .= "          </div>";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Departamentos */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">DEPENDENCIA SOLICITANTE: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"dpto_arch\" id=\"dpto_arch\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($depen as $key => $val)
            {
                $html .= "					<option value=\"" . $val['departamento'] . "\">" . $val['departamento'] . "-" . $val['descripcion'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Numero requisicion */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO ORDEN: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"num_requisicion\" id=\"num_requisicion\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            $html .= "      <tr>";
            $html .= "       <td align=\"center\" colspan=\"2\"> ";
            $html .= "       <input class=\"input-submit\" type=\"submit\" name=\"SIGUIENTE\" value=\"SIGUIENTE\"> ";
            $html .= "       </td>";
            $html .= "      </tr>";
            $html .= "	</table>\n";

            $objResponse->assign("capa_tipos", "innerHTML", $html);

            break;

        case 2: //Ordenes suministro
            $html .= " <br><br>";
            $html .= "	<table border=\"-1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td colspan=\"2\">DATOS DE ORDEN SUMINISTRO</td>\n";
            $html .= "		</tr>\n";

            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">EMPRESA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetCentroU(this.value);\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos)
            {
                $html .= "					<option value=\"" . $datos['empresa_id'] . "\">" . $datos['razon_social'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Centro Utilidad */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">CENTRO UTILIDAD: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "              <div id=\"select_cu\">";
            $html .= "				<select name=\"centro_utilidad_arch\" id=\"centro_utilidad_arch\" class=\"select\" onchange=\"xajax_GetBodega(this.value,document.getElementById('empresa_arch').value)\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "				</select>\n";
            $html .= "			     </div>";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Bodega */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">BODEGA/FARMACIA DESTINO: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "          <div id=\"select_bod\">";
            $html .= "				<select name=\"bodega_arch\" id=\"bodega_arch\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "				</select>\n";
            $html .= "          </div>";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Numero suministro */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO SUMINISTRO: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"num_suministro\" id=\"num_suministro\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            $html .= "      <tr>";
            $html .= "       <td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"SIGUIENTE\" value=\"SIGUIENTE\"></td>";
            $html .= "      </tr>";
            $html .= "	</table>\n";

            $objResponse->assign("capa_tipos", "innerHTML", $html);

            break;

        case 3: //Formulas
            $html .= " <br><br>";
            $html .= "	<table border=\"-1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td colspan=\"2\">DATOS DE FORMULA</td>\n";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">EMPRESA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetBodegaAll(this.value);\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos)
            {
                $html .= "					<option value=\"" . $datos['empresa_id'] . "\">" . $datos['razon_social'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Bodega */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">FARMACIA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "          <div id=\"select_bod\">";
            $html .= "				<select name=\"bodega_arch\" id=\"bodega_arch\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "				</select>\n";
            $html .= "          </div>";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Numero formula */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO FORMULA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"15\" onkeypress=\"\" class=\"input-text\" name=\"num_formula\" id=\"num_formula\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Tipo Id paciente */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO ID: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"tipo_id\" id=\"tipo_id\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($TipoId as $key => $value)
            {
                $html .= "					<option value=\"" . $value['tipo_id_paciente'] . "\">" . $value['tipo_id_paciente'] . "-" . $value['descripcion'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Numero identificacion */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO IDENTIFICACION: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"num_id\" id=\"num_id\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            $html .= "      <tr>";
            $html .= "       <td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"SIGUIENTE\" value=\"SIGUIENTE\"></td>";
            $html .= "      </tr>";
            $html .= "	</table>\n";

            $objResponse->assign("capa_tipos", "innerHTML", $html);

            break;

        case 4: //CTC: Comite tecnico cientifico
            $html .= " <br><br>";
            $html .= "	<table border=\"-1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td colspan=\"2\">DATOS DOC. COMITE TECNICO CIENTIFICO</td>\n";
            $html .= "		</tr>\n";
            /* Numero formula */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO FORMULA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"15\" onkeypress=\"\" class=\"input-text\" name=\"num_formula\" id=\"num_formula\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Tipo Id paciente */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO ID: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"tipo_id\" id=\"tipo_id\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($TipoId as $key => $value)
            {
                $html .= "					<option value=\"" . $value['tipo_id_paciente'] . "\">" . $value['tipo_id_paciente'] . "-" . $value['descripcion'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Numero identificacion */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO IDENTIFICACION: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"num_id\" id=\"num_id\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Valor CTC */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >VALOR CTC: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"12\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"val_ctc\" id=\"val_ctc\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            $html .= "      <tr>";
            $html .= "       <td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"SIGUIENTE\" value=\"SIGUIENTE\"></td>";
            $html .= "      </tr>";
            $html .= "	</table>\n";

            $objResponse->assign("capa_tipos", "innerHTML", $html);

            break;

        case 5: //Facturas
            $html .= " <br><br>";
            $html .= "	<table border=\"-1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td colspan=\"2\">DATOS DE LA FACTURA</td>\n";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">EMPRESA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetBodegaAll(this.value);\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos)
            {
                $html .= "					<option value=\"" . $datos['empresa_id'] . "\">" . $datos['razon_social'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Bodega */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">FARMACIA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "          <div id=\"select_bod\">";
            $html .= "				<select name=\"bodega_arch\" id=\"bodega_arch\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "				</select>\n";
            $html .= "          </div>";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Numero factura */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO FACTURA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"15\" onkeypress=\"\" class=\"input-text\" name=\"num_factura\" id=\"num_factura\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Tipo factura */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO DE FACTURA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"tipo_fac\" id=\"tipo_fac\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($TipoFac as $key => $value)
            {
                $html .= "					<option value=\"" . $value['tipo_archivo_id'] . "\">" . $value['tipo_nombre'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Fecha factura */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >FECHA FACTURA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "	         <input type=\"text\" maxlength=\"10\" name=\"fecha_fac\" id=\"fecha_fac\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
            $html .= "		      [DD/MM/AAAA]\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            $html .= "      <tr>";
            $html .= "       <td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"SIGUIENTE\" value=\"SIGUIENTE\"></td>";
            $html .= "      </tr>";
            $html .= "	</table>\n";

            $objResponse->assign("capa_tipos", "innerHTML", $html);

            // $htm = "<input type=\"checkbox\" name=\"ctrl_fec\" value=\"ctrl_fec\" disabled checked>FECHAS<br>";
            // $objResponse->assign("fec","innerHTML",$htm);      
            break;

        case 6: //Glosas
            $html .= " <br><br>";
            $html .= "	<table border=\"-1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td colspan=\"2\">DATOS DE LA GLOSA</td>\n";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">EMPRESA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetBodegaAll(this.value);\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos)
            {
                $html .= "					<option value=\"" . $datos['empresa_id'] . "\">" . $datos['razon_social'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Bodega */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">FARMACIA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "          <div id=\"select_bod\">";
            $html .= "				<select name=\"bodega_arch\" id=\"bodega_arch\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "				</select>\n";
            $html .= "          </div>";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Numero glosa */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO GLOSA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"15\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"num_glosa\" id=\"num_glosa\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Numero factura glosar */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO FACTURA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"num_fac_glo\" id=\"num_fac_glo\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Valor glosa */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >VALOR GLOSA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"12\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"val_glosa\" id=\"val_glosa\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            $html .= "      <tr>";
            $html .= "       <td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"SIGUIENTE\" value=\"SIGUIENTE\"></td>";
            $html .= "      </tr>";
            $html .= "	</table>\n";

            $objResponse->assign("capa_tipos", "innerHTML", $html);

            break;

        case 7: //Informes
            $html .= " <br><br>";
            $html .= "	<table border=\"-1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td colspan=\"2\">DATOS DEL INFORME</td>\n";
            $html .= "		</tr>\n";
            /* Tipo Informe */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO INFORME: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"tipo_infor\" id=\"tipo_infor\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($TipoFac as $key => $value)
            {
                $html .= "					<option value=\"" . $value['tipo_archivo_id'] . "\">" . $value['tipo_nombre'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Nombre de informe */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NOMBRE ESPEC. INFORME: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"30\" onkeypress=\"\" class=\"input-text\" name=\"nom_infor\" id=\"nom_infor\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Fecha informe */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >FECHA INFORME: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "	         <input type=\"text\" maxlength=\"10\" name=\"fecha_infor\" id=\"fecha_infor\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
            $html .= "		      [DD/MM/AAAA]\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            $html .= "      <tr>";
            $html .= "       <td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"SIGUIENTE\" value=\"SIGUIENTE\"></td>";
            $html .= "      </tr>";
            $html .= "	</table>\n";

            $objResponse->assign("capa_tipos", "innerHTML", $html);


            break;

        case 8: //Pendientes dispensados
            $html .= " <br><br>";
            $html .= "	<table border=\"-1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td colspan=\"2\">DATOS PENDIENTE DISPENSADO</td>\n";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">EMPRESA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetBodegaAll(this.value);\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos)
            {
                $html .= "					<option value=\"" . $datos['empresa_id'] . "\">" . $datos['razon_social'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Bodega */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">FARMACIA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "          <div id=\"select_bod\">";
            $html .= "				<select name=\"bodega_arch\" id=\"bodega_arch\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "				</select>\n";
            $html .= "          </div>";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Numero formula */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO FORMULA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"15\" onkeypress=\"\" class=\"input-text\" name=\"num_formula\" id=\"num_formula\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Tipo Id paciente */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO ID: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"tipo_id\" id=\"tipo_id\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($TipoId as $key => $value)
            {
                $html .= "					<option value=\"" . $value['tipo_id_paciente'] . "\">" . $value['tipo_id_paciente'] . "-" . $value['descripcion'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Numero identificacion */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO IDENTIFICACION: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"num_id\" id=\"num_id\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            $html .= "      <tr>";
            $html .= "       <td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"SIGUIENTE\" value=\"SIGUIENTE\"></td>";
            $html .= "      </tr>";
            $html .= "	</table>\n";

            $objResponse->assign("capa_tipos", "innerHTML", $html);
            break;

        case 9: //cortes
            $html .= " <br><br>";
            $html .= "	<table border=\"-1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td colspan=\"2\">DATOS DEL CORTE</td>\n";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">EMPRESA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetBodegaAll(this.value);\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos)
            {
                $html .= "					<option value=\"" . $datos['empresa_id'] . "\">" . $datos['razon_social'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Bodega */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">FARMACIA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "          <div id=\"select_bod\">";
            $html .= "				<select name=\"bodega_arch\" id=\"bodega_arch\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "				</select>\n";
            $html .= "          </div>";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Numero corte */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO DEL CORTE: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"15\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"num_corte\" id=\"num_corte\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Cantidad formulas */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >CANTIDAD FORMULAS: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"15\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"cant_form\" id=\"cant_form\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Valor corte */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >VALOR CORTE: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"12\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"val_corte\" id=\"val_corte\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Fecha inicial corte */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >FECHA INICIAL CORTE: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "	         <input type=\"text\" maxlength=\"10\" name=\"fecha_ini_corte\" id=\"fecha_ini_corte\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
            $html .= "		      [DD/MM/AAAA]\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Fecha final corte */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >FECHA FINAL CORTE: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "	         <input type=\"text\" maxlength=\"10\" name=\"fecha_fin_corte\" id=\"fecha_fin_corte\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
            $html .= "		      [DD/MM/AAAA]\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Nombre entrega */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >ENTREGADO POR: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"30\" onkeypress=\"\" class=\"input-text\" name=\"entrega\" id=\"entrega\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Nombre audita */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >AUDITADO POR: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"30\" onkeypress=\"\" class=\"input-text\" name=\"audita\" id=\"audita\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            $html .= "      <tr>";
            $html .= "       <td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"SIGUIENTE\" value=\"SIGUIENTE\"></td>";
            $html .= "      </tr>";
            $html .= "	</table>\n";

            $objResponse->assign("capa_tipos", "innerHTML", $html);
            break;
    }


    return $objResponse;
}

/* * ****************************************************************************************

 * Remotos: funciones xajax soporte para la funcion  Campos_tipoArch() [xajax]

 * **************************************************************************************** */

/* Listar Centro Utilidad [parameter: empresa] */
/* -------------------------------------------------------------- */

function GetCentroU($empId)
{
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("Permisos", "classes", "app", "RepositorioDocumentacion");
    $cutil = $sql->Listar_CU($empId);

    $html = " <select name=\"centro_utilidad_arch\" id=\"centro_utilidad_arch\" class=\"select\" onchange=\"xajax_GetBodega(this.value,document.getElementById('empresa_arch').value)\">\n";
    $html .= "  <option value=\"-1\">---SELECCIONAR---</option>\n";
    foreach ($cutil as $key => $valor)
    {
        $html .= " <option value=\"" . $valor['centro_utilidad'] . "\">" . $valor['descripcion'] . "</option>\n";
    }
    $html .= " </select>\n";

    $objResponse->assign("select_cu", "innerHTML", $html);

    return $objResponse;
}

/* Listar bodegas [parameters: centro utilidad, empresa] */
/* ------------------------------------------------------------------------------- */

function GetBodega($centro_u, $empresa)
{
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("Permisos", "classes", "app", "RepositorioDocumentacion");
    $bodega = $sql->ListarBodegas($centro_u, $empresa);

    $html = " <select name=\"bodega_arch\" id=\"bodega_arch\" class=\"select\">\n";
    $html .= "  <option value=\"-1\">---SELECCIONAR---</option>\n";
    foreach ($bodega as $key => $value)
    {
        $html .= " <option value=\"" . $value['bodega'] . "\">" . $value['bodega'] . "-" . $value['descripcion'] . "</option>\n";
    }
    $html .= " </select>\n";

    $objResponse->assign("select_bod", "innerHTML", $html);

    return $objResponse;
}

/* Listar bodegas [parameters: empresa] */
/* ------------------------------------------------------------------------------- */

function GetBodegaAll($empresa)
{
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("Permisos", "classes", "app", "RepositorioDocumentacion");
    $bodega = $sql->ListarBodegasAll($empresa);

    $html = " <select name=\"bodega_arch\" id=\"bodega_arch\" class=\"select\">\n";
    $html .= "  <option value=\"-1\">---SELECCIONAR---</option>\n";
    foreach ($bodega as $key => $value)
    {
        $html .= " <option value=\"" . $value['bodega'] . "\">" . $value['bodega'] . "-" . $value['descripcion'] . "</option>\n";
    }
    $html .= " </select>\n";

    $objResponse->assign("select_bod", "innerHTML", $html);

    return $objResponse;
}

/* * ****************************************************************************************
 * Remotos: Select listar usuarios de farmacia
 * @param: $empresa_id, $bodega
 * **************************************************************************************** */

function UsuariosFarmacia($bodega)
{
    $objResponse = new xajaxResponse();

    $emp = SessionGetVar("empresa_permiso");

    $sql = AutoCarga::factory("Permisos", "classes", "app", "ESM_AdminPqrs");
    $users = $sql->BuscarUsuarioFarm($emp, $bodega);
    /* $objResponse->alert("users: ".$users); */

    $html = " <select name=\"resp_caso\" id=\"resp_caso\" class=\"select\">\n";
    $html .= "  <option value=\"0\">---SELECCIONAR---</option>\n";
    foreach ($users as $key => $value)
    {
        $html .= " <option value=\"" . $value['usuario_id'] . "\">" . strtoupper($value['nombre']) . "</option>\n";
    }
    $html .= " </select>\n";

    $objResponse->assign("resp_farm", "innerHTML", $html);

    return $objResponse;
}

/* * ****************************************************************************************
 * Remotos: Select listar usuarios de farmacia
 * @param: $empresa_id, $bodega
 * **************************************************************************************** */

function GetUserFarm($bodega, $empresa)
{
    $objResponse = new xajaxResponse();
    //$objResponse->alert('mensje');
    $sql = AutoCarga::factory("Permisos", "classes", "app", "ESM_AdminPqrs");
    $usuarios = $sql->BuscarUsuarioFarm($empresa, $bodega);

    $html = " <select name=\"resp_caso\" id=\"resp_caso\" class=\"select\">  ";
    $html .= "  <option value=\"0\">---SELECCIONAR---</option>\n";
    foreach ($usuarios as $key => $valor)
    {
        $html .= " <option value=\"" . $valor['usuario_id'] . "\">" . strtoupper($valor['nombre']) . "</option>\n";
    }
    $html .= " </select>\n";

    $objResponse->assign("resp_farm", "innerHTML", $html);

    return $objResponse;
}

function formularioServicioAlCliente()
{
    $objResponse = new xajaxResponse();

    $html = "<table width=\"100%\">";
    
     $sql = AutoCarga::factory("DMLs_pqrs", "classes", "app", "ESM_AdminPqrs");
    $idtipos = $sql->obtenerTiposIndentificacion();
    
    
     $html .= "<tr>      <td  colspan=\"4\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\" >DATOS DEL CLIENTE</td>\n</tr>";
    $html .= "  <td width=\"%\">\n";
    $html .= "      <table border=\"1\" width=\"100%\" cellspacing=\"2\">\n";
     $html .= " <tr>\n";
    $html .= "      <td width=\"50%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >NUMERO IDENTIFICACION</td>\n";
    $html .= "      <td width=\"50%\" align=\"right\">\n";
    $html .= "          <input type=\"text\" class=\"input-text\" name=\"cedula\" id=\"cedula\" style=\"width:100%\" maxlength=\"12\" value=\"\"><input type=\"hidden\" class=\"input-text\" name=\"tipoencontrado\" id=\"tipoencontrado\"> <input type=\"hidden\" class=\"input-text\" name=\"cedulaencontrada\" id=\"cedulaencontrada\">\n";
    $html .= "      </td>\n";
    $html .= "</tr>\n";
    $html .= " <tr>\n";
    $html .= "      <td width=\"50%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >NOMBRES&nbsp;</td>\n";
    $html .= "      <td width=\"50%\" align=\"right\">\n";
    $html .= "          <input type=\"text\" class=\"input-text\" name=\"nombres\" id=\"nombres\" style=\"width:100%\" maxlength=\"40\" value=\"\" disabled>\n";
    $html .= "      </td>\n";
    $html .= "</tr>\n";
    $html .= "<tr>\n";
    $html .= "      <td width=\"50%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >APELLIDOS</td>\n";
    $html .= "      <td width=\"50%\" align=\"right\">\n";
    $html .= "              <input type=\"text\" class=\"input-text\" name=\"apellidos\" id=\"apellidos\" style=\"width:100%\" maxlength=\"40\" value=\"\" disabled>\n";
    $html .= "       </td>\n";
    $html .= " </tr>\n";
    $html .= " <tr>\n";
    $html .= "  <td width=\"20%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SEXO</td>\n";
    $html .= "<td colspan=\"2\">\n";
    $html .= "       <input type=\"text\" class=\"input-text\" name=\"sexo\" id=\"sexo\" style=\"width:100%\" maxlength=\"40\" value=\"\" disabled>\n";
    $html .= "</td>\n";
    $html .= "</tr>\n";
    $html .= "</table>\n";
    $html .= "</td>\n";

    $html .= "<td width=\"\">\n";
    $html .= "<table border=\"1\" width=\"100%\" cellspacing=\"2\">\n";
     $html .= "<tr>\n";
    $html .= "      <td width=\"35%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO</td>\n";
    $html .= "      <td colspan=\"2\">\n";
    $html .= "      <select name=\"tipodocumento\" id=\"tipodocumento\" class=\"select\">  ";
    $html .= "          <option value=\"\">--SELECCIONAR--</option>";
    
    foreach($idtipos as $d){
        $html .= "<option value=\"{$d["tipo_id_paciente"]}\">{$d["tipo_id_paciente"]}</option>";
    }
    
    
    $html .= "      </select> ";
    $html .= "      </td>\n";
    $html .= "</tr>\n";
    $html .= "<tr>\n";
    $html .= "      <td width=\"40%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" ><a title=\"FECHA NACIMIENTO\">FEC. NAC.</a>&nbsp;</td>\n";
    $html .= "      <td width=\"25%\" align=\"right\">\n";
    $html .= "              <input type=\"text\" align=\"left\" class=\"input-text\" name=\"fecha_naci\" id=\"fecha_naci\" style=\"width:100%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\" disabled>\n";
    $html .= "      </td>\n";
    $html .= " </tr>\n";
    $html .= "<tr>\n";
    $html .= "      <td width=\"35%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\"  disabled>DIRECCION RESIDENCIA&nbsp;</td>\n";
    $html .= "      <td colspan=\"2\">\n";
    $html .= "              <input type=\"text\" class=\"input-text\" name=\"direccion\" id=\"direccion\" style=\"width:100%\" maxlength=\"60\" value=\"\" disabled>\n";
    $html .= "      </td>\n";
    $html .= "</tr>\n";
    $html .= "<tr>\n";
    $html .= "      <td width=\"35%\" style=\"text-align:left;text-indent:8pt; height:14px;\" class=\"\" ></td>\n";
    $html .= "      <td colspan=\"2\">\n";
    $html .= "      </td>\n";
    $html .= "</tr>\n";

    $html .= "</table>\n";
    $html .= "</td>\n";

    $html .= "<td width=\"\" >\n";
    $html .= "          <table border=\"1\" width=\"98%\" cellspacing=\"2\">\n";
    $html .= "                  <tr>\n";
    $html .= "                      <td width=\"8%\" align=\"left\" colspan=\"2\">\n";
    $html .= "                              <a href=\"#\" onclick=\"buscarClientePorId('cedula', 'tipodocumento', 'asignarDatosCliente'); return false;\" style=\"margin-left:10px; font-size:12;\">Buscar Cliente</a>";
    $html .= "                      </td>\n";
    $html .= "                  </tr>\n";
    $html .= "                  <tr>\n";
    $html .= "                      <td width=\"8%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\"  >TEL. RESIDENCIA</td>\n";
    $html .= "                      <td width=\"8%\" align=\"right\">\n";
    $html .= "                              <input type=\"text\" class=\"input-text\" name=\"telefono\" id=\"telefono\" onkeypress=\"return acceptNum(event)\" style=\"width:100%\" maxlength=\"30\" value=\"\" disabled>\n";
    $html .= "                      </td>\n";
    $html .= "                  </tr>\n";
    $html .= "                  <tr>\n";
    $html .= "                         <td width=\"8%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TEL. CELULAR</td>\n";
    $html .= "                          <td width=\"8%\" align=\"right\">\n";
    $html .= "                                      <input type=\"text\" class=\"input-text\" name=\"celular\" id=\"celular\" onkeypress=\"return acceptNum(event)\" style=\"width:100%\" maxlength=\"30\" value=\"\" disabled>\n";
    $html .= "                          </td>\n";
    $html .= "                  </tr>\n";
    $html .= "                  <tr>\n";
    $html .= "                      <td width=\"8%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >EMAIL</td>\n";
    $html .= "                      <td width=\"8%\" align=\"right\">\n";
    $html .= "                              <input type=\"text\" class=\"input-text\" name=\"email\" id=\"email\" style=\"width:100%\" maxlength=\"80\" value=\"\" disabled>\n";
    $html .= "                      </td>\n";
    $html .= "                   </tr>\n";
    $html .= "              </table>\n";
    $html .= " </td>\n";

    $html .= " </tr>\n";

    $html .= "  <tr>\n";
    $html .= "              <td colspan=\"4\" style=\"width:100%; background-color:#C0C0C0\"><br>\n";
    $html .= "              </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>";


    $objResponse->assign("contenedorFormularioCaso", "innerHTML", $html);

    return $objResponse;
}



function autoCompletado($nombre,$i,$busquedad)
{
    
$objResponse = new xajaxResponse();

$sqlPqrs = Autocarga::factory("DMLs_pqrs", "", "app", "ESM_AdminPqrs");
$datos = $sqlPqrs->obtenerProductos($busquedad, SessionGetVar("empresa_id"));
/*
 *  position: absolute;
                backgroundColor: white;
                zIndex: 33;
                zIndex: 10px;
 */
$html ="<style>
            .div_auto{
                position: absolute;
                border: 1px solid #F8F8F8;
                padding : 4px;
                cursor:pointer;
            }
            .div_texto{
                 font-family: sans-serif;
                 font-size: 70%;
                 font-style: normal; 
            }
        </style>";
$html .="<table class='div_auto' bgcolor='#FFFFFF' >";
foreach ($datos as $key => $value) {
   $codigo_producto= $value['codigo_producto'];
   $descripcion= $value['descripcion'];
    $html .="<tr><td  style=\"text-align:left;text-indent:8pt\"><p class='div_texto' id='demo' onclick=\"agrega_producto('$codigo_producto','$descripcion','$i')\">".$descripcion."</p></td></tr>";//descripcion
}
$html .="</table>";
$objResponse->assign("autocom".$i,"innerHTML",$html);

return $objResponse;
}

function formularioLogistica($areaid,$numero_form,$primera)
{
    
$objResponse = new xajaxResponse();
 //ReturnOpenCalendario('Buscador', 'fecha_fin', '/', 1)

    if($primera==0){
    $html = "<br>";
    $html .= "<table width=\"100%\">";
    $html .= "<tr>";   
    $html .= "<td style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\" width=\"100%\"># de Productos por Caso:&nbsp;&nbsp;<input class=\"input-text\" type='text' id='numero' name='numero' value='' size='6' />&nbsp;&nbsp;<input type='button' value='CREAR' onclick='adicionarFormularioPorAreaProducto(\"$areaid\");' class=\"input-submit\" name='crear_form_producto' /></td>";
    $html .= "</tr>";
    $html .= "</table>";  
    $html .= "<br>";  
    $html .= "<br>";  
    }else{
    $html .= "<table width=\"100%\">
             <tr>      <td  colspan=\"8\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\" >LOGISTICA</td>\n</tr>
            <tr>
            <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">FECHA RECEPCION</td>
            <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">TIPO DOCUMENTO</td>
                <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">NUMERO DOCUMENTO</td>
                <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">PRODUCTO</td>
                <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CANTIDAD DESPACHADA</td>
                <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CANTIDAD RECIBIDA</td>
                <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">NOVEDAD</td>
            </tr>";
     for($i=1;$i<=$numero_form;$i++){      
     $html .= "<tr>"; 
         if($i==1){ 
             //<td width=\"8%\" align='center'>
             // <input type=\"date\"  class=\"input-text\" name=\"fecha_recepcion$i\" id=\"fecha_recepcion$i\" style=\"width:70%\" ><br><b class=\"label\">(dd/mm/aaaa)</b></td>
     $html .= "
             <td><input type=\"date\" class=\"input-text\" name=\"fecharecepcion\" id=\"fecharecepcion\" style=\"width:70%\" maxlength=\"50\" value=\"\" readonly>".ReturnOpenCalendarioHTML("registrar_caso", "fecharecepcion", '-') ."</td>
                <td width=\"7%\">";                
     $html .= "     <select  class=\"input-text\" name=\"tipodocumento\" id=\"tipodocumento\" style=\"width:100%\" value=\"\">
                        <option value='DC'>DC</option>
                        <option value='CE'>CE</option>
                        <option value='ME'>ME</option>
                        <option value='TE'>TE</option>
                        <option value='FDC'>FDC</option>
                        <option value='S2'>S2</option>
                        <option value='EFC'>EFC</option>
                        <option value='DFC'>DFC</option>
                        <option value='EFM'>EFM</option>
                        <option value='DSM'>DSM</option>
                        <option value='EDD'>EDD</option>
                        <option value='ITM'>ITM</option>
                        <option value='ETB'>ETB</option>
                        <option value='EDFM'>EDFM</option>                        
                        <option value='FDB'>FDB</option>
                    </select>";                   
     $html .= "</td>
                <td width=\"10%\"><input type=\"text\" class=\"input-text\" name=\"numerodocumento\" id=\"numerodocumento\" autocomplete=\"off\" style=\"width:100%\" maxlength=\"80\" value=\"\"><input type=\"hidden\" id=\"numerocasos\" name=\"numerocasos\" value='$numero_form' /></td> ";
        }else{
     $html .= "<td width=\"8%\" align='center'></td>";                  
     $html .= "<td width=\"7%\" align='center'></td>";                  
     $html .= "<td width=\"10%\" align='center'></td>";                  
             }        
     $html .= "<td width=\"40%\"><input type=\"text\" class=\"input-text\" name=\"nombreproducto$i\" id=\"nombreproducto$i\" autocomplete=\"off\" style=\"width:100%\" maxlength=\"80\" value=\"\"  onkeypress=\"autoCompletado(event,'nombreproducto','$i')\" >   <input type=\"hidden\" id=\"productoid$i\" name=\"productoid$i\" />
                <div id='autocom$i'></div>    
                </td>                
                <td width=\"5%\"><input type=\"text\" class=\"input-text\" name=\"cantidaddespachada$i\" id=\"cantidaddespachada$i\" style=\"width:100%\" maxlength=\"80\" value=\"\"></td>
                <td width=\"5%\"><input type=\"text\" class=\"input-text\" name=\"cantidadrecibida$i\" id=\"cantidadrecibida$i\" style=\"width:100%\" maxlength=\"80\" value=\"\"></td>
                <td width=\"20%\">
                    <select  class=\"input-text\" name=\"novedad$i\" id=\"novedad$i\" style=\"width:100%\" value=\"\">
                        <option value='-1'>--- seleccionar ---</option>
                        <option value='1'>No cumple con los requisitos espec&iacute;ficos o legales</option>
                        <option value='2'>No cumple con las especificaciones t&eacute;cnicas</option>
                        <option value='3'>Remisionado/Facturado No enviado</option>
                        <option value='4'>Remisionado/Facturado enviado MENOR Cantidad </option>
                        <option value='5'>Remisionado/Facturado Enviado MAYOR Cantidad </option>
                        <option value='6'>Enviado y NO Remisionado</option>
                        <option value='7'>Producto averiado</option>
						<option value='8'>Producto Trocado</option>
                    </select>
                </td>
            </tr>
    ";
     }  //autocomplete=\"off\"
        
    //$html .= ReturnOpenCalendarioHTML("registrar_caso", "fecharecepcion", '-') . "\n";
    $html .= "</table>";
    $html .= "</br>";
    }
    $objResponse->assign("contenedorFormularioCaso", "innerHTML", $html);
    $objResponse->call("formularioAgregado", "logistica","".$numero_form."");
    return $objResponse;
}

function obtenerCategoriaPorArea($areaid)
{
    $objResponse = new xajaxResponse();
    if($areaid==2){
        $html = "";
        $sqlPqrs = Autocarga::factory("DMLs_pqrs", "", "app", "ESM_AdminPqrs");
        $datos = $sqlPqrs->obtenerCategoriaPorArea($areaid);
        $html .= "Cateoria: ";
        $html .= "			<select name=\"categoria\" id=\"categoria\" class=\"select\">\n";
        $html .= "<option value='0'>--SELECCIONAR--</option>";
        foreach ($datos as $d) 
        {
            $html .= "<option value= \"{$d["id"]}\">{$d["descripcion"]}</option>\n";
        }
        $html .= "                     </select>\n";
    }
    $objResponse->assign("categoria", "innerHTML", $html);
    return $objResponse;
}

function obtenerPrioridadPorArea($areaid)
{
    $objResponse = new xajaxResponse();

    $html = "";

    $sqlPqrs = Autocarga::factory("DMLs_pqrs", "", "app", "ESM_AdminPqrs");
    $datos = $sqlPqrs->obtenerPrioridadPorArea($areaid);

    $html .= "<option value='0'>--SELECCIONAR--</option>";
    foreach ($datos as $d)
    {
        $html .= "<option value= \"{$d["id"]}\">{$d["nombre"]}</option>\n";
    }


    $objResponse->assign("prioridad", "innerHTML", $html);

    return $objResponse;
}


function buscarCliente($id, $tipo, $callback = 'asignarDatosCliente')
{
    $objResponse = new xajaxResponse();

    $sqlPqrs = Autocarga::factory("DMLs_pqrs", "", "app", "ESM_AdminPqrs");

    $datos = $sqlPqrs->obtenerPaciente($id, $tipo);

    $objResponse->call($callback, $datos);

    return $objResponse;
}


function buscarClienteLogistica($id, $tipo, $callback = 'asignarDatosCliente')
{
    $objResponse = new xajaxResponse();

    $sqlPqrs = Autocarga::factory("DMLs_pqrs", "", "app", "ESM_AdminPqrs");

    $datos = $sqlPqrs->obtenerTerceroLogistica($id, $tipo);

    $objResponse->call($callback, $datos);

    return $objResponse;
}


function buscarProducto($busquedad){
      $objResponse = new xajaxResponse();
      $sqlPqrs = Autocarga::factory("DMLs_pqrs", "", "app", "ESM_AdminPqrs");
      $datos = $sqlPqrs->obtenerProductos($busquedad,  SessionGetVar("empresa_id"));
     
     $objResponse->call("buscador.resultadoBusquedad", $datos);

    return $objResponse;
}

?>