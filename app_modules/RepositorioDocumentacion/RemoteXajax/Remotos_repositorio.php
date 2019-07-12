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
function Campos_tipoArch($cod_tipo, $empresa, $bodega) {
    $objResponse = new xajaxResponse();

    //$vista = AutoCarga::factory("Repositorio_MenuHTML", "views", "app", "RepositorioDocumentacion");
    $sql = AutoCarga::factory("Permisos", "classes", "app", "RepositorioDocumentacion");
    $esm = $sql->ListarEmpresas($empresa);
    //$objResponse->alert($empresa);
    $depen = $sql->ListarDptos($empresa);
    $TipoId = $sql->GetTipoId();

    //para facturas/informes
    if ($cod_tipo == 5 || $cod_tipo == 7) {
        $TipoFac = $sql->GetTipoFac($cod_tipo);
		$TipoInformeArchivos = $sql->GetTipoInforme($cod_tipo);
		
		$TipoProducto = $sql->GetTipoProducto();
    }

    switch ($cod_tipo) {
        case 1: //Orden requisicion//
            $html .= " <br><br>";
            $html .= "	<table border=\"-1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td colspan=\"2\">DATOS DE ORDEN REQUISICION</td>\n";
            $html .= "		</tr>\n";

            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">SOLICITUD A EMPRESA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetCentroU(this.value,'" . $bodega . "');\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos) {
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
            $html .= "				<select name=\"centro_utilidad_arch\" id=\"centro_utilidad_arch\" class=\"select\" onchange=\"xajax_GetBodega(this.value,document.getElementById('empresa_arch').value,'" . $bodega . "')\">\n";
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
            foreach ($depen as $key => $val) {
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
            $html .= "				<select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetCentroU(this.value,'" . $bodega . "');\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos) {
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
            $html .= "				<select name=\"centro_utilidad_arch\" id=\"centro_utilidad_arch\" class=\"select\" onchange=\"xajax_GetBodega(this.value,document.getElementById('empresa_arch').value,'" . $bodega . "');\">\n";
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
            $html .= "				<select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetBodegaAll(this.value,'" . $bodega . "');\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos) {
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
            foreach ($TipoId as $key => $value) {
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
            $html .= "              <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"" . $empresa . "\">";
            $html .= "              <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"" . $bodega . "\">";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
			
            /* Tipo Id paciente */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO ID: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"tipo_id\" id=\"tipo_id\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($TipoId as $key => $value) {
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
			
			/* Nombre paciente */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NOMBRE PACIENTE: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"20\" class=\"input-text\" name=\"nombrePaciente\" id=\"nombrePaciente\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
			
			/* Tiempo duracion */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >TIEMPO DURACION: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"3\" class=\"input-text\" name=\"tiempoDuracion\" id=\"tiempoDuracion\" value=\"\" style=\"width:10%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
			 /* Tiempo duracion */
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\"></td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
             $html .= "				<select name=\"tipo_tiem_durCTC\" id=\"tipo_tiem_durCTC\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "					<option value=\"1\">DIA</option>\n";
            $html .= "					<option value=\"2\">MES</option>\n";
			$html .= "					<option value=\"3\">A&Ntilde;O</option>\n";
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
			
			
			/* Medico que formula */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >MEDICO QUE FORMULA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"20\" class=\"input-text\" name=\"medicoFormula\" id=\"medicoFormula\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
			
			/* Medico que autoriza */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >MEDICO QUE AUTORIZA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"20\" class=\"input-text\" name=\"medicoAutoriza\" id=\"medicoAutoriza\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
			
            /*Valor CTC*/
		    $html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:8pt\" >VALOR CTC: </td>\n";
			$html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "				<input type=\"text\" maxlength=\"12\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"val_ctc\" id=\"val_ctc\" value=\"\" style=\"width:45%\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";	
			$html .= "  <td align=\"center\">";	
			$html .= "         <a title=\"Adicionar Producto\" class=\"label_error\" href=\"javascript:MostrarCapa('containerBus');Iniciar4('INCLUIR PRODUCTO');\">BUSCAR PRODUCTOS</a> ";	
			$html .= "       </td>";
			$html .= "      <tr>";		
			$html .= "       <td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"SIGUIENTE\" value=\"SIGUIENTE\"></td>";	
			$html .= "      </tr>";			
			$html .= "	</table>\n";	
			//AÃ‘ADIENDO TABLA DE PRODUCTOS
			$html .= "  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n"; 
			$html .= "     <tr><td colspan=\"3\" align=\"center\"><div class=\"label_error\" id=\"error_productos\"></div></td></tr>\n";
			$html .= "     <tr>\n"; 
			$html .= "      <td width=\"15%\" align=\"left\" class=\"modulo_table_list_title\">\n"; 
			$html .= "       CODIGO ";
			$html .= "      </td>\n"; 
			$html .= "      <td width=\"70%\" align=\"left\" class=\"modulo_table_list_title\">\n"; 
			$html .= "       DESCRIPCION ";			
			$html .= "      </td>\n";
			$html .= "      <td align=\"left\" class=\"modulo_table_list_title\">\n"; 
			$html .= "       <a title=\"Adicionar a la tutela\">ACCION</a> ";			
			$html .= "      </td>\n"; 			
			$html .= "     </tr>\n"; 
			$html .= "     <tr>\n"; 
			$html .= "      <td width=\"15%\"  align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "        <input type=\"text\" id=\"codigo_pro\" name=\"codigo_pro\" style=\"border:solid 1px;font-weight:bolder;background:#FFF;\" disabled=\"disabled\" value=\"\"> ";	
			$html .= "      </td>\n"; 
			$html .= "      <td width=\"70%\" align=\"left\" class=\"modulo_list_claro\">\n"; 
			$html .= "        <input type=\"text\" id=\"descrip_pro\" name=\"descrip_pro\" style=\"border:solid 1px;font-weight:bolder;background:#FFF;width:100%;\" disabled=\"disabled\" value=\"\"> ";						
			$html .= "      </td>\n";
			$html .= "      <td align=\"center\" style=\"cursor:pointer;\" class=\"modulo_table_list_claro\" >\n"; 
			$html .= "         <sub><a title=\"Adicionar\" class=\"label_error\" href=\"javascript:guardarProductoCTC(formCargarArchivo);\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\" width=\"14\" height=\"14\"></a></sub>\n"; 			
			$html .= "      </td>\n"; 			
			$html .= "     </tr>\n"; 		
			$html .= "  </table><br><br>\n"; 
		    /*Div para mostrar los productos seleccionados guardados en la tabla temporal*/
			$html .= "       <div id=\"selec_prod_temp\" style=\"display:none;\" class=\"modulo_table_list\">"; 
			$html .= "       </div>    ";
			$objResponse->assign("capa_tipos","innerHTML",$html);
			//$objResponse->script("MostrarSelProd();");

            break;

        case 5: //Facturas
            $html .= "<br><br>";
            $html .= "  <table border=\"-1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "      <tr class=\"modulo_table_list_title\">\n";
            $html .= "          <td colspan=\"2\">DATOS DE LA FACTURA.</td>\n";
            $html .= "      </tr>\n";
            $html .= "      <tr class=\"modulo_table_list_title\">\n";
            $html .= "          <td width=\"35%\" style=\"text-align:left;text-indent:8pt\">EMPRESA: </td>\n";
            $html .= "		<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetBodegaAll(this.value,'" . $bodega . "');\">\n";
            $html .= "                  <option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos) {
                $html .= "		<option value=\"" . $datos['empresa_id'] . "\">" . $datos['razon_social'] . "</option>\n";
            }
            $html .= "              </select>\n";
            $html .= "		</td>\n";
            $html .= "      </tr>\n";
            /* Bodega */
            $html .= "      <tr class=\"modulo_table_list_title\">\n";
            $html .= "          <td width=\"35%\" style=\"text-align:left;text-indent:8pt\">FARMACIA: </td>\n";
            $html .= "		<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <div id=\"select_bod\">";
            $html .= "                  <select name=\"bodega_arch\" id=\"bodega_arch\" class=\"select\">\n";
            $html .= "                      <option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "			</select>\n";
            $html .= "              </div>";
            $html .= "		</td>\n";
            $html .= "      </tr>\n";
            /* Numero factura */
            $html .= "      <tr class=\"modulo_table_list_title\">\n";
            $html .= "          <td style=\"text-align:left;text-indent:8pt\" >NRO FACTURA: </td>\n";
            $html .= "		<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <input type=\"text\" maxlength=\"15\" onkeypress=\"\" class=\"input-text\" name=\"num_factura\" id=\"num_factura\" value=\"\" style=\"width:45%\">\n";
            $html .= "		</td>\n";
            $html .= "      </tr>\n";
            /* Tipo factura */
            $html .= "      <tr class=\"modulo_table_list_title\">\n";
            $html .= "          <td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO DE FACTURA: </td>\n";
            $html .= "		<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <select name=\"tipo_fac\" id=\"tipo_fac\" class=\"select\">\n";
            $html .= "                  <option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($TipoFac as $key => $value) {
                $html .= "		<option value=\"" . $value['tipo_archivo_id'] . "\">" . $value['tipo_nombre'] . "</option>\n";
            }
            $html .= "              </select>\n";
            $html .= "		</td>\n";
            $html .= "      </tr>\n";
            /* Fecha factura */
            $html .= "      <tr class=\"modulo_table_list_title\">\n";
            $html .= "          <td style=\"text-align:left;text-indent:8pt\" >FECHA FACTURA: </td>\n";
            $html .= "		<td align=\"left\" class=\"modulo_list_claro\">\n";
            //$html .= "              <input type=\"text\" maxlength=\"10\" name=\"fecha_fac\" id=\"fecha_fac\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
			$html .= " <input type=\"date\" class=\"input-text\" name=\"fecha_fac\" id=\"fecha_fac\" style=\"width:70%\" maxlength=\"50\" value=\"\" style=\"width:45%\" readonly>".ReturnOpenCalendarioHTML("formCargarArchivo", "fecha_fac", '-');
            $html .= "		      [DD/MM/AAAA]\n";
            $html .= "		</td>\n";
            $html .= "      </tr>\n";
            // Buscar Producto y Siguiente
            $html .= "      <tr>";
            $html .= "          <td align=\"center\">";
            $html .= "              <a title=\"Adicionar Producto\" class=\"label_error\" href=\"javascript:MostrarCapa('containerBus');Iniciar4('INCLUIR PRODUCTO');\">BUSCAR PRODUCTOS</a> ";
            $html .= "          </td>";
            $html .= "          <td align=\"center\" colspan=\"1\"> ";
            $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"SIGUIENTE\" value=\"SIGUIENTE\"> ";
            $html .= "          </td>";
            $html .= "      </tr><br>";
            //$html .= "      <tr>";
            //$html .= "       <td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"SIGUIENTE\" value=\"SIGUIENTE\"></td>";
            //$html .= "      </tr>";
            $html .= "	</table>\n";

            /* Tabla para vista previa de productos a incluir */
            $html .= "  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "      <tr><td colspan=\"3\" align=\"center\"><div class=\"label_error\" id=\"error_productos\"></div></td></tr>\n";
            $html .= "      <tr>\n";
            $html .= "          <td width=\"15%\" align=\"left\" class=\"modulo_table_list_title\">\n";
            $html .= "              CODIGO ";
            $html .= "          </td>\n";
            $html .= "          <td width=\"70%\" align=\"left\" class=\"modulo_table_list_title\">\n";
            $html .= "              DESCRIPCION ";
            $html .= "          </td>\n";
            $html .= "          <td align=\"left\" class=\"modulo_table_list_title\">\n";
            $html .= "              <a title=\"Adicionar a la tutela\">ACCION</a> ";
            $html .= "          </td>\n";
            $html .= "      </tr>\n";
            $html .= "     <tr>\n";
            $html .= "          <td width=\"15%\"  align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <input type=\"text\" id=\"codigo_pro\" name=\"codigo_pro\" style=\"border:solid 1px;font-weight:bolder;background:#FFF;\" disabled=\"disabled\" value=\"\"> ";
            $html .= "          </td>\n";
            $html .= "          <td width=\"70%\" align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <input type=\"text\" id=\"descrip_pro\" name=\"descrip_pro\" style=\"border:solid 1px;font-weight:bolder;background:#FFF;width:100%;\" disabled=\"disabled\" value=\"\"> ";
            $html .= "          </td>\n";
            $html .= "          <td align=\"center\" style=\"cursor:pointer;\" class=\"modulo_table_list_claro\" >\n";
            $html .= "              <sub><a title=\"Adicionar\" class=\"label_error\" href=\"javascript:agregar_productos_factura(formCargarArchivo);\"><img src=\"" . GetThemePath() . "/images/abajo.png\" border=\"0\" width=\"14\" height=\"14\"></a></sub>\n";
            $html .= "          </td>\n";
            $html .= "     </tr>\n";
            $html .= "  </table><br><br>\n";
            /* Div para mostrar los productos seleccionados guardados en la tabla temporal */
            $html .= "       <div id=\"selec_prod_temp\" style=\"display:none;\" class=\"modulo_table_list\">";
            $html .= "       </div>    ";

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
            $html .= "				<select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetBodegaAll(this.value,'" . $bodega . "');\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos) {
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
            $html .= "				<select name=\"tipo_infor\" id=\"tipo_infor\" class=\"select\"  onchange=\"selectivo(this.value)\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($TipoInformeArchivos as $key => $value) {
                $html .= "					<option value=\"" . $value['tipo_archivo_id'] . "\">" . $value['tipo_nombre'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
			
			
			//Selectivo 15/12/2015
			//$html .= "		<tr id=\"ocultarSelectivo\" class=\"modulo_table_list_title\" style=\"display:none;\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\" name=\"selectivoEstadoLabel\" id=\"selectivoEstadoLabel\" >ESTADO: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            //$html .= "				<input type=\"radio\" class=\"input-text\" name=\"selectivoEstado\" id=\"selectivoEstado\" value=\"0\" style=\"width:45%\">\n";
			$html .= "				<select name=\"selectivoEstado\" id=\"selectivoEstado\" class=\"select\">\n";
			$html .= "					<option value=\"0\">---SELECCIONAR---</option>\n";
			$html .= "					<option value=\"1\">Diario</option>\n";
			$html .= "					<option value=\"2\">Semanal</option>\n";
			$html .= "				</select>\n";
			
            $html .= "			</td>\n";
            
          
			
			//TipoProducto
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO PRODUCTO: </td>\n";
			$html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"tipo_pro\" id=\"tipo_pro\" class=\"select\">\n";
			$html .= "					<option value=\"0\">---SELECCIONAR---</option>\n";
			foreach($TipoProducto as $key => $value)
			{
			 $html .= "					<option value=\"".$value['tipo_producto_id']."\">".$value['descripcion']."</option>\n";
			}
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			
			
            /* FECHA RADICACION */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\" >FECHA DE FORMULA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= " <input type=\"date\" class=\"input-text\" name=\"fecha_infor\" id=\"fecha_infor\" style=\"width:70%\" maxlength=\"50\" value=\"\" style=\"width:45%\" readonly>".ReturnOpenCalendarioHTML("formCargarArchivo", "fecha_infor", '-');
            //$html .= "				<input type=\"text\" maxlength=\"30\" class=\"input-text\" name=\"fecha_infor\" id=\"fecha_infor\" value=\"\" onkeypress=\"return acceptDate(event)\" style=\"width:45%\">\n";
            $html .= "              <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"" . $empresa . "\">";
            $html .= "              <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"" . $bodega . "\">";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
			
			//NOVEDAD SELECTIVA
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\" name=\"tipoSelectivoLabel\" id=\"tipoSelectivoLabel\"  > NOVEDAD: </td>\n";
			$html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"tipoSelectivo\" id=\"tipoSelectivo\" class=\"select\">\n";
            $html .= "					<option value=\" \">---SELECCIONAR---</option>\n";
            $html .= "					<option value=\"1\"> SI </option>\n";
			$html .= "					<option value=\"0\"> NO </option>\n";
            $html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			
            /* Fecha informe */

			//NUMERO FORMULAS
			$html .= "		<tr class=\"modulo_table_list_title\" >\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\"  id=\"nom_infor_label\" style=\"display:block;\" >NUMERO DE FORMULA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\"  >\n";
            $html .= "	         <input type=\"text\" maxlength=\"10\" name=\"nom_infor\" id=\"nom_infor\" style=\"display:block;\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"\"  >\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
			
			 /* Tipo Id paciente */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\" name=\"tipoIdPacienteLabel\" id=\"tipoIdPacienteLabel\" >TIPO ID: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"tipoIdPaciente\" id=\"tipoIdPaciente\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($TipoId as $key => $value) {
                $html .= "					<option value=\"" . $value['tipo_id_paciente'] . "\">" . $value['tipo_id_paciente'] . "-" . $value['descripcion'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Numero identificacion */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" id=\"nro_idenficacion_label\" style=\"display:block;\">NRO IDENTIFICACION: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"nro_idenficacion\" id=\"nro_idenficacion\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
			
			
			
			//NUEVO CAMPO VALOR FACTURADO
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" id=\"valorFacturadoLabel\" style=\"display:block;\" >VALOR FACTURADO: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\" >\n";
            $html .= "	         <input type=\"text\" onkeypress=\"return acceptNum(event)\"  name=\"valorFacturado\" id=\"valorFacturado\" style=\"display:block;\" class=\"input-text\"  value=\"\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
			
			//NUEVO CAMPO NOMBRE QUIEN LO ENTREGA
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" id=\"nomQuienEntregaLabel\" style=\"display:block;\">QUIEN ENTREGA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "	         <input type=\"text\"  maxlength=\"20\" name=\"nomQuienEntrega\" id=\"nomQuienEntrega\" style=\"display:block;\" class=\"input-text\"  value=\"\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
			
			//NUEVO CAMPO NOMBRE QUIEN RECIBE
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" id=\"nomQuienRecibeLabel\" style=\"display:block;\" >QUIEN RECIBE: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "	         <input type=\"text\"  maxlength=\"20\" name=\"nomQuienRecibe\" id=\"nomQuienRecibe\" style=\"display:block;\" class=\"input-text\" value=\"\">\n";
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
            $html .= "				<select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetBodegaAll(this.value,'" . $bodega . "');\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos) {
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
            foreach ($TipoId as $key => $value) {
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
            $html .= "				<select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetBodegaAll(this.value,'" . $bodega . "');\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos) {
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

        case 10: //Tutelas//
            $html .= " <br><br>";
            $html .= "	<table border=\"-1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td colspan=\"2\">DATOS DOC. TUTELA</td>\n";
            $html .= "		</tr>\n";

            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">EMPRESA: </td>\n";
            $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"empresa_arch\" id=\"empresa_arch\" class=\"select\" onchange=\"xajax_GetCentroU(this.value,'" . $bodega . "');\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($esm as $key => $datos) {
                $html .= "					<option value=\"" . $datos['empresa_id'] . "\">" . $datos['razon_social'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Centro Utilidad */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">CENTRO UTILIDAD: </td>\n";
            $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <div id=\"select_cu\">";
            $html .= "				<select name=\"centro_utilidad_arch\" id=\"centro_utilidad_arch\" class=\"select\" onchange=\"xajax_GetBodega(this.value,document.getElementById('empresa_arch').value,'" . $bodega . "')\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "				</select>\n";
            $html .= "			     </div>";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Bodega */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">BODEGA/FARMACIA: </td>\n";
            $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "          <div id=\"select_bod\">";
            $html .= "				<select name=\"bodega_arch\" id=\"bodega_arch\" class=\"select\" onchange=\"\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "				</select>\n";
            $html .= "          </div>";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Accionante */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >ACCIONANTE: </td>\n";
            $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"35\" onkeypress=\"\" class=\"input-text\" name=\"accionante\" id=\"accionante\" value=\"\" style=\"width:55%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Nombre paciente */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NOMBRE PACIENTE: </td>\n";
            $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"35\" onkeypress=\"\" class=\"input-text\" name=\"nombre_paciente\" id=\"nombre_paciente\" value=\"\" style=\"width:55%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Tipo Id paciente */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO ID: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"tipo_id\" id=\"tipo_id\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($TipoId as $key => $value) {
                $html .= "					<option value=\"" . $value['tipo_id_paciente'] . "\">" . $value['tipo_id_paciente'] . "-" . $value['descripcion'] . "</option>\n";
            }
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Numero identificacion */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO IDENTIFICACION: </td>\n";
            $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"12\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"num_id\" id=\"num_id\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Tipo tutela */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO TUTELA: </td>\n";
            $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"tipo_tut\" id=\"tipo_tut\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "					<option value=\"integral\">INTEGRAL</option>\n";
            $html .= "					<option value=\"normal\">NORMAL</option>\n";
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Numero radicado */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO RADICADO: *</td>\n";
            $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"12\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"nradicado\" id=\"nradicado\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Sentencia */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >SENTENCIA: </td>\n";
            $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"40\" onkeypress=\"\" class=\"input-text\" name=\"sentencia\" id=\"sentencia\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
			/* Tiempo duracion TEXT*/
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" > TIEMPO DURACION: </td>\n";
            $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"3\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"tduracion\" id=\"tduracion\" value=\"\" style=\"width:10%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Tiempo duracion */
			 $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\"></td>\n";
            $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"tipo_tiempo_duracion\" id=\"tipo_tiempo_duracion\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            $html .= "					<option value=\"1\">DIA</option>\n";
            $html .= "					<option value=\"2\">MES</option>\n";
			$html .= "					<option value=\"3\">A&Ntilde;O</option>\n";
            $html .= "				</select>\n";
            $html .= "			</td>\n";
            //$html .= "		</tr>\n";
			
            /* Autorizado por */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >AUTORIZADO POR: </td>\n";
            $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"35\" onkeypress=\"\" class=\"input-text\" name=\"autoriza\" id=\"autoriza\" value=\"\" style=\"width:65%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            /* Enviar */
            $html .= "      <tr>";
            $html .= "       <td align=\"center\">";
            $html .= "         <a title=\"Adicionar Producto\" class=\"label_error\" href=\"javascript:MostrarCapa('containerBus');Iniciar4('INCLUIR PRODUCTO');\">BUSCAR PRODUCTOS</a> ";
            $html .= "       </td>";
            $html .= "       <td align=\"center\" colspan=\"1\"> ";
            $html .= "       <input class=\"input-submit\" type=\"submit\" name=\"SIGUIENTE\" value=\"SIGUIENTE\"> ";
            $html .= "       </td>";
            $html .= "      </tr><br>";
            $html .= "	</table><br>\n";
            /* Tabla para vista previa de productos a incluir */
            $html .= "  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "     <tr><td colspan=\"3\" align=\"center\"><div class=\"label_error\" id=\"error_productos\"></div></td></tr>\n";
            $html .= "     <tr>\n";
            $html .= "      <td width=\"15%\" align=\"left\" class=\"modulo_table_list_title\">\n";
            $html .= "       CODIGO ";
            $html .= "      </td>\n";
            $html .= "      <td width=\"70%\" align=\"left\" class=\"modulo_table_list_title\">\n";
            $html .= "       DESCRIPCION ";
            $html .= "      </td>\n";
            $html .= "      <td align=\"left\" class=\"modulo_table_list_title\">\n";
            $html .= "       <a title=\"Adicionar a la tutela\">ACCION</a> ";
            $html .= "      </td>\n";
            $html .= "     </tr>\n";
            $html .= "     <tr>\n";
            $html .= "      <td width=\"15%\"  align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "        <input type=\"text\" id=\"codigo_pro\" name=\"codigo_pro\" style=\"border:solid 1px;font-weight:bolder;background:#FFF;\" disabled=\"disabled\" value=\"\"> ";
            $html .= "      </td>\n";
            $html .= "      <td width=\"70%\" align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "        <input type=\"text\" id=\"descrip_pro\" name=\"descrip_pro\" style=\"border:solid 1px;font-weight:bolder;background:#FFF;width:100%;\" disabled=\"disabled\" value=\"\"> ";
            $html .= "      </td>\n";
            $html .= "      <td align=\"center\" style=\"cursor:pointer;\" class=\"modulo_table_list_claro\" >\n";
            $html .= "         <sub><a title=\"Adicionar\" class=\"label_error\" href=\"javascript:ValidaProdTemp(formCargarArchivo);\"><img src=\"" . GetThemePath() . "/images/abajo.png\" border=\"0\" width=\"14\" height=\"14\"></a></sub>\n";
            $html .= "      </td>\n";
            $html .= "     </tr>\n";
            $html .= "  </table><br><br>\n";
            /* Div para mostrar los productos seleccionados guardados en la tabla temporal */
            $html .= "       <div id=\"selec_prod_temp\" style=\"display:none;\" class=\"modulo_table_list\">";
            $html .= "       </div>    ";
            $objResponse->assign("capa_tipos", "innerHTML", $html);
               break;
      /////////////////
           case 11: //Alto Costo
               $titulo="ALTO COSTO";
               $html .=formulario($cod_tipo,$titulo,$empresa,$bodega,$TipoId);
               $objResponse->assign("capa_tipos", "innerHTML", $html);
               break;
           case 12: //CODIGO_2000
               $titulo="CODIGO 2000";
               $html .=formulario($cod_tipo,$titulo,$empresa,$bodega,$TipoId);
               $objResponse->assign("capa_tipos", "innerHTML", $html);
               break;
           case 13: //RECOBRO_MAGISTERIO
              $titulo="RECOBRO MAGISTERIO";
              $html .=formulario($cod_tipo,$titulo,$empresa,$bodega,$TipoId);
              $objResponse->assign("capa_tipos", "innerHTML", $html);
              break;
           case 14: //RECOBRO_MAGISTERIO
              $titulo="RECOBRO PASIVO";
              $html .=formulario($cod_tipo,$titulo,$empresa,$bodega,$TipoId);
      ///////////      
            $objResponse->assign("capa_tipos", "innerHTML", $html);

            break;
    }


    return $objResponse;
}


function formulario($cod_tipo,$titulo,$empresa,$bodega,$TipoId){
    $html .= " <br><br>";
            $html .= "	<table border=\"-1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td colspan=\"2\">$titulo</td>\n";
            $html .= "		</tr>\n";
            /* Numero formula */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NRO FORMULA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"15\" onkeypress=\"\" class=\"input-text\" name=\"num_formula\" id=\"num_formula\" value=\"\" style=\"width:45%\">\n";
            $html .= "                          <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"" . $empresa . "\">";
            $html .= "                          <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"" . $bodega . "\">";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
			
            /* Tipo Id paciente */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO ID: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<select name=\"tipo_id\" id=\"tipo_id\" class=\"select\">\n";
            $html .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach ($TipoId as $key => $value) {
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
			
			/* Nombre paciente */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >NOMBRE PACIENTE: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"20\" class=\"input-text\" name=\"nombrePaciente\" id=\"nombrePaciente\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
	    			/* Medico que formula */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >MEDICO QUE FORMULA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<input type=\"text\" maxlength=\"20\" class=\"input-text\" name=\"medicoFormula\" id=\"medicoFormula\" value=\"\" style=\"width:45%\">\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";	
		
            /* Fecha inicial corte */
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\" >FECHA DE ENTREGA: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
	    $html .= " <input type=\"date\" class=\"input-text\" name=\"fecha_infor\" id=\"fecha_infor\" style=\"width:70%\" maxlength=\"50\" value=\"\" style=\"width:45%\" readonly>".ReturnOpenCalendarioHTML("formCargarArchivo", "fecha_infor", '-');
            $html .= "              <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"" . $empresa . "\">";
            $html .= "              <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"" . $bodega . "\">";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            
            
	
            $html .= "		<tr class=\"modulo_table_list_title\">\n";
            $html .= "			<td style=\"text-align:left;text-indent:8pt\" >OBSERVACIÓN: </td>\n";
            $html .= "			<td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "				<textarea  maxlength=\"100\" class=\"input-text\" name=\"observacion\" id=\"observacion\" value=\"\" style=\"width:45%\"></textarea>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            
			
            /*Valor CTC*/
		
			$html .= "  <td align=\"center\">";	
			$html .= "         <a title=\"Adicionar Producto\" class=\"label_error\" href=\"javascript:MostrarCapa('containerBus');Iniciar4('INCLUIR PRODUCTO');\">BUSCAR PRODUCTOS</a> ";	
			$html .= "       </td>";
			$html .= "      <tr>";		
			$html .= "       <td align=\"center\" colspan=\"2\"><input type='button' class=\"input-submit\" onclick=\"javascript:confirmar();\" name=\"SIGUIENTE\" value=\"SIGUIENTE\"></td>";	
			$html .= "      </tr>";			
			$html .= "	</table>\n";	
			//AñADIENDO TABLA DE PRODUCTOS
			$html .= "  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n"; 
			$html .= "     <tr><td colspan=\"3\" align=\"center\"><div class=\"label_error\" id=\"error_productos\"></div></td></tr>\n";
			$html .= "     <tr>\n"; 
			$html .= "      <td width=\"15%\" align=\"left\" class=\"modulo_table_list_title\">\n"; 
			$html .= "       CODIGO ";
			$html .= "      </td>\n"; 
			$html .= "      <td width=\"70%\" align=\"left\" class=\"modulo_table_list_title\">\n"; 
			$html .= "       DESCRIPCION ";			
			$html .= "      </td>\n";
			$html .= "      <td align=\"left\" class=\"modulo_table_list_title\">\n"; 
			$html .= "       <a title=\"Adicionar a la tutela\">ACCION</a> ";			
			$html .= "      </td>\n"; 			
			$html .= "     </tr>\n"; 
			$html .= "     <tr>\n"; 
			$html .= "      <td width=\"15%\"  align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "        <input type=\"text\" id=\"codigo_pro\" name=\"codigo_pro\" style=\"border:solid 1px;font-weight:bolder;background:#FFF;\" disabled=\"disabled\" value=\"\"> ";	
			$html .= "      </td>\n"; 
			$html .= "      <td width=\"70%\" align=\"left\" class=\"modulo_list_claro\">\n"; 
			$html .= "        <input type=\"text\" id=\"descrip_pro\" name=\"descrip_pro\" style=\"border:solid 1px;font-weight:bolder;background:#FFF;width:100%;\" disabled=\"disabled\" value=\"\"> ";						
			$html .= "      </td>\n";
			$html .= "      <td align=\"center\" style=\"cursor:pointer;\" class=\"modulo_table_list_claro\" >\n"; 
			$html .= "         <sub><a title=\"Adicionar\" class=\"label_error\" href=\"javascript:guardarProductoCTC(formCargarArchivo);\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\" width=\"14\" height=\"14\"></a></sub>\n"; 			
			$html .= "      </td>\n"; 			
			$html .= "     </tr>\n"; 		
			$html .= "  </table><br><br>\n"; 
		    /*Div para mostrar los productos seleccionados guardados en la tabla temporal*/
			$html .= "       <div id=\"selec_prod_temp\" style=\"display:none;\" class=\"modulo_table_list\">"; 
			$html .= "       </div>    ";
                        return $html; 
}

/* * ****************************************************************************************

 * Remotos: funciones xajax soporte para la funcion  Campos_tipoArch() [xajax]

 * **************************************************************************************** */

/* Listar Centro Utilidad [parameter: empresa] */
/* -------------------------------------------------------------- */

function GetCentroU($empId, $bodega) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("Permisos", "classes", "app", "RepositorioDocumentacion");
    $cutil = $sql->Listar_CU($empId);

    $html = " <select name=\"centro_utilidad_arch\" id=\"centro_utilidad_arch\" class=\"select\" onchange=\"xajax_GetBodega(this.value,document.getElementById('empresa_arch').value,'" . $bodega . "');\">\n";
    $html .= "  <option value=\"-1\">---SELECCIONAR---</option>\n";
    foreach ($cutil as $key => $valor) {
        $html .= " <option value=\"" . $valor['centro_utilidad'] . "\">" . $valor['descripcion'] . "</option>\n";
    }
    $html .= " </select>\n";

    $objResponse->assign("select_cu", "innerHTML", $html);

    return $objResponse;
}

/* Listar bodegas [parameters: centro utilidad, empresa] */
/* ------------------------------------------------------------------------------- */

function GetBodega($centro_u, $empresa, $bodega) {
    $objResponse = new xajaxResponse();
	/*$objResponse->script("alert('bodega {$bodega}');");
	$objResponse->script("alert('centro_u {$centro_u}');");
	$objResponse->script("alert('empresa {$empresa}');");*/
    $sql = AutoCarga::factory("Permisos", "classes", "app", "RepositorioDocumentacion");
    $bodega = $sql->ListarBodegas($centro_u, $empresa, $bodega);
	
    $html = " <select name=\"bodega_arch\" id=\"bodega_arch\" class=\"select\">\n";
    $html .= "  <option value=\"-1\">---SELECCIONAR---</option>\n";
    foreach ($bodega as $key => $value) {
        $html .= " <option value=\"" . $value['bodega'] . "\">" . $value['bodega'] . "-" . $value['descripcion'] . "</option>\n";
    }
    $html .= " </select>\n";

    $objResponse->assign("select_bod", "innerHTML", $html);

    return $objResponse;
}

/* Listar bodegas [parameters: empresa,bodega] */
/* ------------------------------------------------------------------------------- */

function GetBodegaAll($empresa, $bodega) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("Permisos", "classes", "app", "RepositorioDocumentacion");
    $bodega = $sql->ListarBodegasAll($empresa, $bodega);

    $html = " <select name=\"bodega_arch\" id=\"bodega_arch\" class=\"select\">\n";
    $html .= "  <option value=\"-1\">---SELECCIONAR---</option>\n";
    foreach ($bodega as $key => $value) {
        $html .= " <option value=\"" . $value['bodega'] . "\">" . $value['bodega'] . "-" . $value['descripcion'] . "</option>\n";
    }
    $html .= " </select>\n";

    $objResponse->assign("select_bod", "innerHTML", $html);

    return $objResponse;
}

/* Listar todas las bodegas [parameters: empresa] */
/* ------------------------------------------------------------------------------- */

function AllBodegas($empresa) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("Permisos", "classes", "app", "RepositorioDocumentacion");
    $bodega = $sql->ListarTodasBodegas($empresa);

    $html = " <select name=\"buscador[filtrobodega]\" id=\"buscador[filtrobodega]\" class=\"select\">\n";
    $html .= "  <option value=\"-1\">---SELECCIONAR---</option>\n";
    foreach ($bodega as $key => $value) {
        $html .= " <option value=\"" . $value['bodega'] . "\">" . $value['bodega'] . "-" . $value['descripcion'] . "</option>\n";
    }
    $html .= " </select>\n";

    $objResponse->assign("select_bod", "innerHTML", $html);

    return $objResponse;
}

/* * **********************************************
 * funcion para buscar productos
 * *********************************************** */

function BuscarProducto1($empresa_id, $bodega, $tip_bus, $criterio, $offset) {
    $objResponse = new xajaxResponse();
    /* $objResponse->script("alert('empresa_id {$empresa_id}');");
      $objResponse->script("alert('bodega {$bodega}');");
      $objResponse->script("alert('tipo bus {$tip_bus}');");
      $objResponse->script("alert('criterio {$criterio}');");
      return $objResponse;
      exit(); */
    //echo $tip_bus; 
    $consulta = AutoCarga::factory("DMLs_repositorio", "", "app", "RepositorioDocumentacion");

    if ($tip_bus == 2) {
        $aumento = "AND b.codigo_producto='" . $criterio . "'";
        $aumento2 = "";
    } elseif ($tip_bus == 1) {
        $aumento = "AND fc_descripcion_producto(b.codigo_producto) LIKE '%" . strtoupper($criterio) . "%'";
        $aumento2 = "";
    } elseif ($tip_bus == 3) {
        $msq = new MovBodegasSQL();
        $msq->RegistrarBusqueda(UserGetUID(), $empresa_id);

        $aumento = "AND b.codigo_barras ILIKE(UPPER('%" . $criterio . "%'))";
        $aumento2 = "";
    } elseif ($tip_bus == 4) {
        $aumento2 = "AND f.descripcion ='" . $criterio . "'";
    } elseif ($tip_bus == 5) {
        $aumento2 = "AND z.molecula_id ILIKE(UPPER('%" . $criterio . "%'))";
    } elseif ($tip_bus == 6) {
        $aumento2 = "AND h.descripcion ILIKE(UPPER('%" . $criterio . "%'))";
    } elseif ($tip_bus == 7) {
        $aumento2 = "AND g.laboratorio_id =" . $criterio . " ";
    } else {
        $aumento = "";
    }

    if ($criterio != "0" && $criterio != "") {
        $busqueda = $consulta->BuscarProducto($empresa_id, $bodega, $aumento, $aumento2, $offset);
        if (!empty($busqueda)) {
            $salida .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
            $salida .= "                 </div>\n";
            $salida .= "                 <form name=\"adicionar\">\n";
            $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td align=\"center\"width=\"15%\">\n";
            $salida .= "                        CODIGO PRODUCTO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"35%\">\n";
            $salida .= "                        <a title='DESCRIPCION PRODUCTO'>DESCRIPCION</a> ";
            $salida .= "                      </td>\n";
            // $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            // $salida .= "                        UNIDAD";
            // $salida .= "                      </td>\n";
            // $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            // $salida .= "                        EXISTENCIA";
            // $salida .= "                      </td>\n";
            // $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            // $salida .= "                        COSTO";
            // $salida .= "                      </td>\n";
            // $salida .= "                      <td align=\"center\" width=\"20%\">\n";
            // $salida .= "                        FECHA VEN";
            // $salida .= "                      </td>\n"; 
            // $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            // $salida .= "                        LOTE";
            // $salida .= "                      </td>\n"; 
            // $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            // $salida .= "                        CONCEN";
            // $salida .= "                      </td>\n";
            // $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            // $salida .= "                        <a title=\"LABORATORIO\">LAB</a>";
            // $salida .= "                      </td>\n";                    
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        <a title='SELECCIONAR PRODUCTO'>SL</a>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            for ($i = 0; $i < count($busqueda); $i++) {
                $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                        " . $busqueda[$i]['codigo_producto'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                $salida .= "                        " . $busqueda[$i]['descripcion'];
                $salida .= "                      </td>\n";
                // $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                // $salida .= "                         ".$busqueda[$i]['descripcion_unidad'];
                // $salida .= "                      </td>\n";
                // $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
                // $salida .= "                         ".$busqueda[$i]['existencia'];
                // $salida .= "                      </td>\n"; 
                // $salida .= "                      <td align=\"right\">\n";
                // $salida .= "                         <a title='COSTO PROMEDIO'>\n";
                // $salida .= "                         ".$busqueda[$i]['costo'];
                // $salida .= "                         </a>\n";  
                // $salida .= "                      </td>\n";
                // $fechaven=explode("-",$busqueda[$i]['fecha_vencimiento']);
                // $fechavencimiento=$fechaven[2]."-".$fechaven[1]."-".$fechaven[0];
                // $salida .= "                      <td align=\"right\">\n";
                // $salida .= "                         <a title='FECHA VENCIMIENTO'>\n";
                // $salida .= "                         ".$fechavencimiento;
                // $salida .= "                         </a>\n";  
                // $salida .= "                      </td>\n";
                // $salida .= "                      <td align=\"right\">\n";
                // $salida .= "                         <a title='LOTE'>\n";
                // $salida .= "                         ".$busqueda[$i]['lote'];
                // $salida .= "                         </a>\n";  
                // $salida .= "                      </td>\n";
                // $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                // $salida .= "                         ".$busqueda[$i]['contenido_unidad_venta'];
                // $salida .= "                      </td>\n";
                // $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                // $salida .= "                         ".$busqueda[$i]['laboratorio'];
                // $salida .= "                      </td>\n";                    
                // if($busqueda[$i]['existencia']>0)
                // {
                //$salida .= "                      <td align=\"center\" onclick=\"AsignarPro('".$busqueda[$i]['codigo_producto']."','".$busqueda[$i]['descripcion']."','".$fechavencimiento."','".$busqueda[$i]['lote']."');\">\n";
                $salida .= "                    <td align=\"center\" style=\"cursor:pointer;\" onclick=\"AsignarPro('" . $busqueda[$i]['codigo_producto'] . "','" . $busqueda[$i]['descripcion'] . "');\">\n";
                $salida .= "                         <a title='SELECCIONAR PRODUCTO'>\n";
                $salida .= "                          <sub><img src=\"" . GetThemePath() . "/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                $salida .= "                         </a>\n";
                // }
                // else
                // {
                // $salida .= "                      <td align=\"center\" onclick=\"\">\n";
                // }
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
            }
            $salida .= "                </table>\n";

            //$Cont=$consulta->ContarProStip($empresa_id,$centro_utilidad,$bodega,$aumento,$aumento2);
            //$malo=$Cont[0]['count'];

            $action = "Bus_Pro('" . $empresa_id . "','" . $bodega . "','" . $tip_bus . "','" . $criterio . "' ";
            $ctl = AutoCarga::factory("ClaseHTML");
            $salida .= $ctl->ObtenerPaginadoXajax($consulta->conteo, $consulta->paginaActual, $action, "0", 10);
        } else {
            $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr>\n";
            $salida .= "                      <td align=\"center\">\n";
            $salida .="                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    </table>\n";
        }
    } else {
        $salida .= "  <table width=\"95%\" align=\"center\">\n";
        $salida .= "    <tr>\n";
        $salida .= "      <td align=\"center\" class=\"normal_10AN\">\n";
        $salida .= "        INGRESE UN CRITERIO DE BUSQUEDA";
        $salida .= "      </td>\n";
        $salida .= "    </tr>\n";
        $salida .= "  </table>\n";
    }

    $objResponse->assign("tabla_bus", "innerHTML", $salida);
    return $objResponse;
}

/*Guardar productos de tutela en tabla temporal*/
   function guardarProdsTemp($tipoArch,$codigo,$descripcion,$radicado)
   {
    $objResponse = new xajaxResponse();
    $consulta = AutoCarga::factory("DMLs_repositorio", "", "app","RepositorioDocumentacion");
	$textoAdicionProductos;
	$funcionEliminarProducto;
	// $objResponse->alert($tipoArch);
    // $objResponse->alert($codigo);
    // $objResponse->alert($descripcion);
    // $objResponse->alert($radicado);
	
    $guardatmp = $consulta->SaveProdsTemp($tipoArch,$codigo,$descripcion,$radicado);	
   
	if($tipoArch == 10) //tutela CTC
	 {
	   $textoAdicionProductos = "PRODUCTOS A RELACIONAR EN LA TUTELA";
	 }
	 
	 if($tipoArch == 4) //tutela CTC
	 {
	   $textoAdicionProductos = "PRODUCTOS A RELACIONAR EN EL COMITE TECNICO CIENTIFICO";
	 }
         
	 if($tipoArch == 11) //ALTO COSTO
	 {
	   $textoAdicionProductos = "PRODUCTOS A RELACIONAR EN ALTO COSTO";
	 }
	 
	if($guardatmp)
	{
	 //obtener registros de la tabla temp
	 $GetProdsTemp = $consulta->GetProdTmp($tipoArch,$radicado);
	 
	 if(!empty($GetProdsTemp))
	 {
		 $salida .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
		 $salida .= "  <tr>\n";
		 $salida .= "    <td width=\"100%\" colspan=\"3\" align=\"center\" class=\"modulo_table_list_title\">\n";
		 $salida .= $textoAdicionProductos."\n";
		 $salida .= "    </td>\n";
		 $salida .= "  </tr>\n";
		 $salida .= "  <tr>\n";
		 $salida .= "    <td width=\"15%\" align=\"left\" class=\"modulo_table_list_title\">\n"; 
		 $salida .= "       CODIGO ";
		 $salida .= "    </td>\n"; 
		 $salida .= "    <td width=\"70%\" align=\"left\" class=\"modulo_table_list_title\">\n"; 
		 $salida .= "       DESCRIPCION ";			
		 $salida .= "    </td>\n";
		 $salida .= "    <td align=\"left\" class=\"modulo_table_list_title\">\n"; 
		 $salida .= "      <a title=\"Acciones para el medicamento\">ACCION</a> ";			
		 $salida .= "    </td>\n"; 
		 $salida .= "   </tr>\n";
         foreach($GetProdsTemp as $key=>$val)
		 {
		  $salida .="    <tr onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
		  $salida .= "    <td width=\"15%\" align=\"left\" class=\"modulo_table_list_claro\">\n";
		  $salida .= "     ".$val['codigo_producto']."\n";
		  $salida .= "      <input type=\"hidden\" name=\"codigo_final".$key."\" id=\"codigo_final".$key."\" value=\"".$val['codigo_producto']."\">";
		  $salida .= "    </td>\n";
		  $salida .= "    <td width=\"70%\" align=\"left\" class=\"modulo_table_list_claro\">\n";
		  $salida .= "     ".$val['descripcion']."\n";
		  $salida .= "      <input type=\"hidden\" name=\"desc_final".$key."\" id=\"desc_final".$key."\" value=\"".$val['descripcion']."\">";		  
		  $salida .= "    </td>\n";
		  $salida .= "    <td align=\"left\" class=\"modulo_table_list_title\">\n"; 
		  
		  $salida .= "<sub><a title=\"Borrar de la lista\" class=\"label_error\" href=\"javascript:EliminaProdTmp(formCargarArchivo,'".$val['codigo_producto']."');\"><img src=\"".GetThemePath()."/images/fallo.png\" border=\"0\" width=\"14\" height=\"14\"></a></sub>\n";	
		
		  $salida .= "    </td>\n";		 
		  $salida .= "   </tr>\n";
		 }
		 $salida .= "</table>\n";
	 }
	 else
		{
		 $salida .= "<label ALIGN='center' class='label_error'>EL DOC. NO TIENE PRODUCTOS RELACIONADOS</label>";
		}
	 
	}
    else
	    {
	     $salida .= "<label ALIGN='center' class='label_error'>ERROR EN INSERCION</label>";
		}
		
	$objResponse->assign("selec_prod_temp","innerHTML",$salida);
	$objResponse->script("MostrarSelProd();");
	
	return $objResponse; 
   }

  

   
   
   
   
   /*Eliminar productos de tutela de tabla temporal*/
   function EliminaProductoTmp($tipoArch,$radicado,$codigo)
   {
   
    
    $objResponse = new xajaxResponse();
    $consulta = AutoCarga::factory("DMLs_repositorio", "", "app","RepositorioDocumentacion");
	
	
    $deleted = $consulta->BorraProdsTmp($tipoArch,$radicado,$codigo);	
	
	 if($deleted) //if succeded
	 {
	  $GetProdsTemp = $consulta->GetProdTmp($tipoArch,$radicado);
	 
	  if(!empty($GetProdsTemp))
	  {
		 $salida .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
		 $salida .= "  <tr>\n";
		 $salida .= "    <td width=\"100%\" colspan=\"3\" align=\"center\" class=\"modulo_table_list_title\">\n";
		 $salida .= "    PRODUCTOS A RELACIONAR EN LA TUTELA\n ";
		 $salida .= "    </td>\n";
		 $salida .= "  </tr>\n";
		 $salida .= "  <tr>\n";
		 $salida .= "    <td width=\"15%\" align=\"left\" class=\"modulo_table_list_title\">\n"; 
		 $salida .= "       CODIGO ";
		 $salida .= "    </td>\n"; 
		 $salida .= "    <td width=\"70%\" align=\"left\" class=\"modulo_table_list_title\">\n"; 
		 $salida .= "       DESCRIPCION ";			
		 $salida .= "    </td>\n";
		 $salida .= "    <td align=\"left\" class=\"modulo_table_list_title\">\n"; 
		 $salida .= "      <a title=\"Acciones para el medicamento\">ACCION</a> ";			
		 $salida .= "    </td>\n"; 
		 $salida .= "   </tr>\n";
         foreach($GetProdsTemp as $key=>$val)
		 {
		  $salida .="    <tr onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
		  $salida .= "    <td width=\"15%\" align=\"left\" class=\"modulo_table_list_claro\">\n";
		  $salida .= "     ".$val['codigo_producto']."\n";
		  $salida .= "      <input type=\"hidden\" name=\"codigo_final".$key."\" id=\"codigo_final".$key."\" value=\"".$val['codigo_producto']."\">";
		  $salida .= "    </td>\n";
		  $salida .= "    <td width=\"70%\" align=\"left\" class=\"modulo_table_list_claro\">\n";
		  $salida .= "     ".$val['descripcion']."\n";
		  $salida .= "      <input type=\"hidden\" name=\"desc_final".$key."\" id=\"desc_final".$key."\" value=\"".$val['descripcion']."\">";		  
		  $salida .= "    </td>\n";
		  $salida .= "    <td align=\"left\" class=\"modulo_table_list_title\">\n"; 
		  $salida .= "      <sub><a title=\"Borrar de la lista\" class=\"label_error\" href=\"javascript:EliminaProdTmp(formCargarArchivo,'".$val['codigo_producto']."');\"><img src=\"".GetThemePath()."/images/fallo.png\" border=\"0\" width=\"14\" height=\"14\"></a></sub>\n";	
		  $salida .= "    </td>\n";		 
		  $salida .= "   </tr>\n";
		 }
		 $salida .= "</table>\n";
	 }
	 else
		{
		 $salida .= "<label ALIGN='center' class='label_error'>EL DOC. NO TIENE PRODUCTOS RELACIONADOS</label>";
		}
	 
	}
    else
	    {
	     $salida .= "<label ALIGN='center' class='label_error'>ERROR EN LA ELIMINACION DEL REGISTRO</label>";
		}
		
	$objResponse->assign("selec_prod_temp","innerHTML",$salida);
	$objResponse->script("MostrarSelProd();");
	
	return $objResponse;
	
  

    }
   
   
 

/*Guardar productos de CTC en tabla temporal
 * @fecha 14/12/2015
 */
   function guardarProdsTempCTC($tipoArch,$codigo,$descripcion,$radicado)
   {
    $objResponse = new xajaxResponse();
    $consulta = AutoCarga::factory("DMLs_repositorio", "", "app","RepositorioDocumentacion");
	$textoAdicionProductos;
	$funcionEliminarProducto;
	//$objResponse->alert($tipoArch + " " + $codigo + "  " + $descripcion + " " + $radicado);
    $guardatmp = $consulta->SaveProdsTemp($tipoArch,$codigo,$descripcion,$radicado);	

	
	 
	if($guardatmp)
	{
	 //obtener registros de la tabla temp
	 $GetProdsTemp = $consulta->GetProdTmp($tipoArch,$radicado);
	 
	 if(!empty($GetProdsTemp))
	 {
		 $salida .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
		 $salida .= "  <tr>\n";
		 $salida .= "    <td width=\"100%\" colspan=\"3\" align=\"center\" class=\"modulo_table_list_title\">\n";
		 $salida .= " PRODUCTOS A RELACIONAR EN EL COMITE TECNICO CIENTIFICO \n";
		 $salida .= "    </td>\n";
		 $salida .= "  </tr>\n";
		 $salida .= "  <tr>\n";
		 $salida .= "    <td width=\"15%\" align=\"left\" class=\"modulo_table_list_title\">\n"; 
		 $salida .= "       CODIGO ";
		 $salida .= "    </td>\n"; 
		 $salida .= "    <td width=\"70%\" align=\"left\" class=\"modulo_table_list_title\">\n"; 
		 $salida .= "       DESCRIPCION ";			
		 $salida .= "    </td>\n";
		 $salida .= "    <td align=\"left\" class=\"modulo_table_list_title\">\n"; 
		 $salida .= "      <a title=\"Acciones para el medicamento\">ACCION</a> ";			
		 $salida .= "    </td>\n"; 
		 $salida .= "   </tr>\n";
         foreach($GetProdsTemp as $key=>$val)
		 {
		  $salida .="    <tr onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
		  $salida .= "    <td width=\"15%\" align=\"left\" class=\"modulo_table_list_claro\">\n";
		  $salida .= "     ".$val['codigo_producto']."\n";
		  $salida .= "      <input type=\"hidden\" name=\"codigo_final".$key."\" id=\"codigo_final".$key."\" value=\"".$val['codigo_producto']."\">";
		  $salida .= "    </td>\n";
		  $salida .= "    <td width=\"70%\" align=\"left\" class=\"modulo_table_list_claro\">\n";
		  $salida .= "     ".$val['descripcion']."\n";
		  $salida .= "      <input type=\"hidden\" name=\"desc_final".$key."\" id=\"desc_final".$key."\" value=\"".$val['descripcion']."\">";		  
		  $salida .= "    </td>\n";
		  $salida .= "    <td align=\"left\" class=\"modulo_table_list_title\">\n"; 
		  
		  $salida .= "<sub><a title=\"Borrar de la lista\" class=\"label_error\" href=\"javascript:EliminaProdTmpCMT(formCargarArchivo,'".$val['codigo_producto']."');\"><img src=\"".GetThemePath()."/images/fallo.png\" border=\"0\" width=\"14\" height=\"14\"></a></sub>\n";	
		
		  $salida .= "    </td>\n";		 
		  $salida .= "   </tr>\n";
		 }
		 $salida .= "</table>\n";
	 }
	 else
		{
		 $salida .= "<label ALIGN='center' class='label_error'>EL DOC. NO TIENE PRODUCTOS RELACIONADOS</label>";
		}
	 
	}
    else
	    {
	     $salida .= "<label ALIGN='center' class='label_error'>ERROR EN INSERCION</label>";
		}
		
	$objResponse->assign("selec_prod_temp","innerHTML",$salida);
	$objResponse->script("MostrarSelProd();");
	
	return $objResponse; 
   }
  

/*+Descripcion: Eliminar productos de CTC de tabla temporal
 *@fecha: 14/12/2015
 */
   function EliminaProductoTmpCTC($tipoArch,$radicado,$codigo)
   {
   
    
    $objResponse = new xajaxResponse();
    $consulta = AutoCarga::factory("DMLs_repositorio", "", "app","RepositorioDocumentacion");
	
    $deleted = $consulta->BorraProdsTmp($tipoArch,$radicado,$codigo);	
	
	 if($deleted) //if succeded
	 {
	  $GetProdsTemp = $consulta->GetProdTmp($tipoArch,$radicado);
	 
	  if(!empty($GetProdsTemp))
	  {
		 $salida .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
		 $salida .= "  <tr>\n";
		 $salida .= "    <td width=\"100%\" colspan=\"3\" align=\"center\" class=\"modulo_table_list_title\">\n";
		 $salida .= "    PRODUCTOS A RELACIONAR EN LA TUTELA\n " . $tipoArch . " -- " . $radicado . " -- " . $codigo;
		 $salida .= "    </td>\n";
		 $salida .= "  </tr>\n";
		 $salida .= "  <tr>\n";
		 $salida .= "    <td width=\"15%\" align=\"left\" class=\"modulo_table_list_title\">\n"; 
		 $salida .= "       CODIGO ";
		 $salida .= "    </td>\n"; 
		 $salida .= "    <td width=\"70%\" align=\"left\" class=\"modulo_table_list_title\">\n"; 
		 $salida .= "       DESCRIPCION ";			
		 $salida .= "    </td>\n";
		 $salida .= "    <td align=\"left\" class=\"modulo_table_list_title\">\n"; 
		 $salida .= "      <a title=\"Acciones para el medicamento\">ACCION</a> ";			
		 $salida .= "    </td>\n"; 
		 $salida .= "   </tr>\n";
         foreach($GetProdsTemp as $key=>$val)
		 {
		  $salida .="    <tr onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
		  $salida .= "    <td width=\"15%\" align=\"left\" class=\"modulo_table_list_claro\">\n";
		  $salida .= "     ".$val['codigo_producto']."\n";
		  $salida .= "      <input type=\"hidden\" name=\"codigo_final".$key."\" id=\"codigo_final".$key."\" value=\"".$val['codigo_producto']."\">";
		  $salida .= "    </td>\n";
		  $salida .= "    <td width=\"70%\" align=\"left\" class=\"modulo_table_list_claro\">\n";
		  $salida .= "     ".$val['descripcion']."\n";
		  $salida .= "      <input type=\"hidden\" name=\"desc_final".$key."\" id=\"desc_final".$key."\" value=\"".$val['descripcion']."\">";		  
		  $salida .= "    </td>\n";
		  $salida .= "    <td align=\"left\" class=\"modulo_table_list_title\">\n"; 
		  $salida .= "      <sub><a title=\"Borrar de la lista\" class=\"label_error\" href=\"javascript:EliminaProdTmpCMT(formCargarArchivo,'".$val['codigo_producto']."');\"><img src=\"".GetThemePath()."/images/fallo.png\" border=\"0\" width=\"14\" height=\"14\"></a></sub>\n";	
		  $salida .= "    </td>\n";		 
		  $salida .= "   </tr>\n";
		 }
		 $salida .= "</table>\n";
	 }
	 else
		{
		 $salida .= "<label ALIGN='center' class='label_error'>EL DOC. NO TIENE PRODUCTOS RELACIONADOS</label>";
		}
	 
	}
    else
	    {
	     $salida .= "<label ALIGN='center' class='label_error'>ERROR EN LA ELIMINACION DEL REGISTRO</label>";
		}
		
	$objResponse->assign("selec_prod_temp","innerHTML",$salida);
	$objResponse->script("MostrarSelProd();");
	
	return $objResponse;
	
  

    }
   
   
    
  
?>