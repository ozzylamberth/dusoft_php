<?php
	class IngresaFamiliarHTML{
		
		//Constructor de la Clase
		function IngresaFamiliarHTML(){
		}
		
		/**
		*	Forma para Ingresar los Datos del Familiar
		*/
		function FormaDatosFamiliar($idPaciente){
		
			
			$obCons = AutoCarga::factory('FichaFamiliarMetodos','','hc1','FichaFamiliar');
			
			$html .= "<form id=\"formIngFami\" name=\"formIngFami\" action=\"#\" method=\"post\">";
		
			$html .= "<table align=\"center\" border=\"0\" width=\"100%\" > \n";
					
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > PRIMER APELLIDO \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"priApellFam\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > SEGUNDO APELLIDO \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"secApellFam\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > PRIMER NOMBRE \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"priNomFam\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > SEGUNDO NOMBRE \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"secNomFam\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > PARENTESCO \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			//$html .= "    		<input type=\"text\" class=\"input-text\" name=\"parentFam\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "      	<select name=\"parentFam\" class=\"select\">\n";
			$html .= "        		<option value=\"-1\">-- Seleccionar --</option>\n";
 			$arrParent = $obCons->ObtenListParentesco();
			foreach($arrParent as $key => $vecParent){
				$html .= "        		<option value=\"".$vecParent['tipo_parentesco_id']."\" > ".$vecParent['descripcion']." </option> \n";
			}
			$html .= "      	</select> \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > FECHA DE NACIMIENTO \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			//$html .= "			<input type=\"text\" name=\"FechaNacim\" value=\"".$fecha."\" class=\"input-text\" maxlength=\"12\" onkeyPress=\"return acceptDate(event)\" size=\"14\" > \n";
			
			$html .= "<a title=\"Ver Calendario\" href=\"javascript:Mostrar_Campo(document.formIngFami.fechaNacim, 'fechaNacim')\" class=\"label_error\">\n";
      		$html .= "  <img src=\"". GetThemePath() . "/images/calendario/calendario.png\" border=\"0\"  >\n";
      		$html .= "</a>\n";
      		$html .= "<label class=\"label\">[dd/mm/aaaa]</label>\n";
      		$html .= "<div id=\"calendario_pxCampo\" class=\"calendario_px\"></div>\n";
			
			$html .= "			<input type=\"text\" name=\"fechaNacim\" value=\"\" class=\"input-text\" maxlength=\"12\" onkeyPress=\"return acceptDate(event)\" size=\"14\" > \n";
			$html .= "		</td>\n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > OCUPACION \n";
			$html .= "		</td> \n";

			$html .= "    	<td >\n";
			
			$html .= "      	<input type=\"hidden\" name=\"ocupacion_id\" value=\"\">\n";
			$html .= "				<textarea class=\"textarea\"	rows=\"2\" name=\"descripcion_ocupacion\" readonly style=\"width:70%;background:#FFFFFF\"\"> </textarea>\n";
			$html .= "				<input type=\"button\" name=\"btnOcupa\" value=\"Ocupacion\" class=\"input-submit\" onClick=\"javascript:Ocupaciones('formIngFami','')\"> \n";
			
			$html .= "    	</td> \n";
			
			$html .= "	</tr> \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > SEXO \n";
			$html .= "		</td> \n";
			$html .= "    	<td>\n";
			$html .= "			<input type=\"radio\" name=\"sexoFam\" value= \"1\" checked> Hombre \n";
			$html .= "			<input type=\"radio\" name=\"sexoFam\" value=\"2\" > Mujer \n";
			$html .= "    	</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > ESCOLARIDAD \n";
			$html .= "		</td> \n";
			$html .= "    	<td>\n";   
	
			$html .= "      	<select name=\"escolarFam\" class=\"select\">\n";
			$html .= "        		<option value=\"-1\">-- Seleccionar --</option>\n";
			
			$arrEscolar = $obCons->ObtenListInstruccion();
			
			foreach($arrEscolar as $key => $vecEscolar){
				$html .= "        		<option value=\"".$vecEscolar['instruccion_id']."\" > ".$vecEscolar['descripcion']." </option> \n";
			}
			
			$html .= "      	</select> \n";
			
			$html .= "    	</td> \n"; 
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > ESQUEMA COMPLETO DE VACUNAS \n";
			$html .= "		</td> \n";
			$html .= "    	<td>\n";
			$html .= "			<input type=\"checkbox\" name=\"esqVacFam\" value= \"1\"> Si \n";
			$html .= "    	</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > SALUD BUCAL \n";
			$html .= "		</td> \n";
			$html .= "    	<td>\n";
			$html .= "			<input type=\"checkbox\" name=\"saludBucalFam\" value= \"1\"> Si \n";
			$html .= "    	</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > RIESGO, ENFERMEDAD O DISCAPACIDAD \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "        <textarea cols=35 rows=2 name=\"riesEnfDiscFam\"></textarea>\n";
			//$html .= "    		<input type=\"text\" class=\"input-text\" name=\"riesEnfDiscFam\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > NUMERO DE HISTORIA CLINICA \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"histClinFam\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > NUMERO DE IDENTIFICACION \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"noIdentiFam\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > TIPO DE IDENTIFICACION \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			//$html .= "    		<input type=\"text\" class=\"input-text\" name=\"tipoIdentiFam\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "      	<select name=\"tipoIdentiFam\" class=\"select\">\n";
			$html .= "        		<option value=\"-1\">-- Seleccionar --</option>\n";
 			$arrTipIdPacien = $obCons->ObtenListTipoIdPaciente();
			foreach($arrTipIdPacien as $key => $vecTipIdPacien){
				$html .= "        		<option value=\"".$vecTipIdPacien['tipo_id_paciente']."\" > ".$vecTipIdPacien['descripcion']." </option> \n";
			}
			$html .= "      	</select> \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "<input type=\"hidden\" name=\"embarazFam\" value=\"1\"> \n ";
			$html .= "<input type=\"hidden\" name=\"difuntoFam\" value=\"1\" > \n ";
			
			$html .= "	<tr> \n";
			$html .= "		<td align=\"center\" colspan=\"2\" rowspan=\"1\" > \n";
			$html .= "			<input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Ingresar\" onclick=\"validarDatosFam('".$idPaciente."')\" > \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "</table> \n";
			$html .= "</form>";

			$html .=	"<center>\n
							<div id=\"errorFam\" class=\"label_error\"></div>\n
						</center> <br>\n"; 
			
			return $html;
		}
		
		/**
		*	Forma para Ingresar los datos relacionados a una Familiar Embarazada
		*/
		function FormaDatFamEmbzd($idPaciente, $idFamiliar, $nomCompl){
			$html .= "<form id=\"formIngFamEmbzd\" name=\"formIngFamEmbzd\" action=\"#\" method=\"post\"> \n";
		
			$html .= "<table align=\"center\" border=\"0\" width=\"100%\" > \n";
					
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > FAMILIAR ID \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"idFamiliar\" size=\"30\" maxlength=\"20\" value=\"".$idFamiliar."\" readOnly >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > NOMBRE APELLIDOS \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"nombApellFamEmbzd\" size=\"30\" maxlength=\"20\" value=\"".$nomCompl."\" readOnly >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > FECHA ULTIMA MENSTRUACION \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			
			$html .= "<a title=\"Ver Calendario\" href=\"javascript:Mostrar_Campo(document.formIngFamEmbzd.fechaUltMenstr, 'fechaUltMenstr')\" class=\"label_error\">\n";
      		$html .= "  <img src=\"". GetThemePath() . "/images/calendario/calendario.png\" border=\"0\"  >\n";
      		$html .= "</a>\n";
      		$html .= "<label class=\"label\">[dd/mm/aaaa]</label>\n";
      		$html .= "<div id=\"calendario_pxCampo\" class=\"calendario_px\"></div>\n";
			$html .= "	<input type=\"text\" name=\"fechaUltMenstr\" value=\"\" class=\"input-text\" maxlength=\"12\" onkeyPress=\"return acceptDate(event)\" size=\"14\" > \n";
			
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > FECHA PROBABLE PARTO \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			
			$html .= "<a title=\"Ver Calendario\" href=\"javascript:Mostrar_Campo(document.formIngFamEmbzd.fechaProbParto, 'fechaProbParto')\" class=\"label_error\">\n";
      		$html .= "  <img src=\"". GetThemePath() . "/images/calendario/calendario.png\" border=\"0\"  >\n";
      		$html .= "</a>\n";
      		$html .= "<label class=\"label\">[dd/mm/aaaa]</label>\n";
      		$html .= "<div id=\"calendario_pxCampo\" class=\"calendario_px\"></div>\n";
			$html .= "	<input type=\"text\" name=\"fechaProbParto\" value=\"\" class=\"input-text\" maxlength=\"12\" onkeyPress=\"return acceptDate(event)\" size=\"14\" > \n";
			
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > SEMANAS GESTACION \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"semGestac\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > PRIMERA DOSIS \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			
			$html .= "<a title=\"Ver Calendario\" href=\"javascript:Mostrar_Campo(document.formIngFamEmbzd.priDosis, 'priDosis')\" class=\"label_error\">\n";
      		$html .= "  <img src=\"". GetThemePath() . "/images/calendario/calendario.png\" border=\"0\"  >\n";
      		$html .= "</a>\n";
      		$html .= "<label class=\"label\">[dd/mm/aaaa]</label>\n";
      		$html .= "<div id=\"calendario_pxCampo\" class=\"calendario_px\"></div>\n";
			$html .= "	<input type=\"text\" name=\"priDosis\" value=\"\" class=\"input-text\" maxlength=\"12\" onkeyPress=\"return acceptDate(event)\" size=\"14\" > \n";
			//$html .= "    		<input type=\"text\" class=\"input-text\" name=\"priDosis\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > SEGUNDA DOSIS \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			
			$html .= "<a title=\"Ver Calendario\" href=\"javascript:Mostrar_Campo(document.formIngFamEmbzd.segDosis, 'segDosis')\" class=\"label_error\">\n";
      		$html .= "  <img src=\"". GetThemePath() . "/images/calendario/calendario.png\" border=\"0\"  >\n";
      		$html .= "</a>\n";
      		$html .= "<label class=\"label\">[dd/mm/aaaa]</label>\n";
      		$html .= "<div id=\"calendario_pxCampo\" class=\"calendario_px\"></div>\n";
			$html .= "	<input type=\"text\" name=\"segDosis\" value=\"\" class=\"input-text\" maxlength=\"12\" onkeyPress=\"return acceptDate(event)\" size=\"14\" > \n";
			//$html .= "    		<input type=\"text\" class=\"input-text\" name=\"segDosis\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > REFUERZO DOSIS \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			
			$html .= "<a title=\"Ver Calendario\" href=\"javascript:Mostrar_Campo(document.formIngFamEmbzd.rfzDosis, 'rfzDosis')\" class=\"label_error\">\n";
      		$html .= "  <img src=\"". GetThemePath() . "/images/calendario/calendario.png\" border=\"0\"  >\n";
      		$html .= "</a>\n";
      		$html .= "<label class=\"label\">[dd/mm/aaaa]</label>\n";
      		$html .= "<div id=\"calendario_pxCampo\" class=\"calendario_px\"></div>\n";
			$html .= "	<input type=\"text\" name=\"rfzDosis\" value=\"\" class=\"input-text\" maxlength=\"12\" onkeyPress=\"return acceptDate(event)\" size=\"14\" > \n";
			//$html .= "    		<input type=\"text\" class=\"input-text\" name=\"rfzDosis\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";

			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > GESTAS \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"agoGestas\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > PARTOS \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"agoPartos\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > ABORTOS \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"agoAbortos\" size=\"30\" maxlength=\"20\" value=\"\" > \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > CESAREAS \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"agoCesareas\" size=\"30\" maxlength=\"20\" value=\"\" > \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";

			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > ANTECEDENTES PATOLOGICOS OBSTETRICOS \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "        <textarea cols=35 rows=2 name=\"antPatObst\"></textarea>\n";
			//$html .= "    		<input type=\"text\" class=\"input-text\" name=\"antPatObst\" size=\"30\" maxlength=\"60\" value=\"\" > \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";

			$html .= "	<tr> \n";
			$html .= "		<td align=\"center\" colspan=\"2\" rowspan=\"1\" > \n";
			$html .= "			<input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Ingresar\" onclick=\"validarDatosFamEmbzd('".$idPaciente."');\" > \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "</table> \n";
			$html .= "</form>";
			
			$html .=	"<center>\n
							<div id=\"errorFamEmbzd\" class=\"label_error\"></div>\n
						</center> <br>\n";
			
			return $html;
		}
		
		/**
		*	Forma para Ingresar los datos de un Familiar Difunto
		*/
		function FormaDatFamMortal($idPaciente){
		
			$obCons = AutoCarga::factory('FichaFamiliarMetodos','','hc1','FichaFamiliar');	
		
			$html .= "<form id=\"formIngFamMort\" name=\"formIngFamMort\" action=\"#\" method=\"post\">";
		
			$html .= "<table align=\"center\" border=\"0\" width=\"100%\" > \n";
					
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > PRIMER APELLIDO \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"priApellFamMort\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > SEGUNDO APELLIDO \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"secApellFamMort\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > PRIMER NOMBRE \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"priNomFamMort\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > SEGUNDO NOMBRE \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"secNomFamMort\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			

			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > PARENTESCO \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			//$html .= "    		<input type=\"text\" class=\"input-text\" name=\"parentFamMort\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "      	<select name=\"parentFamMort\" class=\"select\">\n";
			$html .= "        		<option value=\"-1\">-- Seleccionar --</option>\n";
			
			$arrParent = $obCons->ObtenListParentesco();
			
			foreach($arrParent as $key => $vecParent){
				$html .= "        		<option value=\"".$vecParent['tipo_parentesco_id']."\" > ".$vecParent['descripcion']." </option> \n";
			}
			
			$html .= "      	</select> \n";
			
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > EDAD AL FALLECER \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"edadFalleFamMort\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > CAUSA \n";
			$html .= "		</td> \n";
			$html .= "		<td colspan=\"1\" rowspan=\"1\" > \n";
			$html .= "        <textarea cols=35 rows=2 name=\"causaFamMort\"></textarea>\n";
// 			$html .= "    		<input type=\"text\" class=\"input-text\" name=\"causaFamMort\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "<input type=\"hidden\" name=\"difuntoFam\" value=\"2\" > \n ";
			
// 			$html .= "	<tr> \n";
// 			$html .= "		<td align=\"center\" colspan=\"2\" rowspan=\"1\" > \n";
// 			$html .= "			<input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Ingresar\" onclick=\"xajax_InsDatFamMort(xajax.getFormValues('formIngFamMort'), xajax.getFormValues('formDatFami'), '".$idPaciente."')\" > \n";
// 			$html .= "		</td> \n";
// 			$html .= "	</tr> \n";
			
			$html .= "	<tr> \n";
			$html .= "		<td align=\"center\" colspan=\"2\" rowspan=\"1\" > \n";
			$html .= "			<input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Ingresar\" onclick=\"validarDatosFamMort('".$idPaciente."');\" > \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "</table> \n";
			$html .= "</form>";
			
			$html .=	"<center>\n
							<div id=\"errorFamMort\" class=\"label_error\"></div>\n
						</center> <br>\n"; 
			return $html;
		}
		
		/**
		*	Forma donde se listan los familiares con sus datos
		*/
		function frmMiemFamiliar($obCons, $idPaciente){
			
			//$html .= "<form id=\"formDatFami\" name=\"formDatFami\" action=\"#\" method=\"post\" > \n";
			//$html .= "		<div id=\"SeccionDatFam_id\" > \n";
			
			$datCamp = $obCons->ConsultarFamiliar("1", $idPaciente);
			//$datos2 = $obCons->ConsultarFamiliarEmbzd();
			
			$html .= "<table align=\"center\" border=\"0\" class=\"modulo_table_list\" > \n";
			
			
			$html .= "	<tr class=\"modulo_table_title\"> \n";
			$html .= "		<td align=\"center\" colspan=\"18\" > MIEMBROS DE LA FAMILIA POR GRUPOS DE EDAD \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr> \n";
			$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > GRUPO \n";
			$html .= "		</td> \n";

			$html .= "		<td align=\"left\" colspan=\"4\" rowspan=\"1\" width=\"40%\" class=\"hc_table_submodulo_list_title\" > APELLIDOS Y NOMBRES \n";
			$html .= "		</td> \n";
			
			$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > PARENTESCO \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > FECHA DE NACIMIENTO \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > EDAD \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > OCUPACION \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > SEXO \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > ESCOLARIDAD \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" width=\"7%\"> E.C.V \n";
			//ESQUEMA COMPLETO DE VACUNAS
			$html .= "<sub> [1] </sub>\n ";
			$html .= " </td> \n";
			$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" width=\"6%\" > S.B \n";
			$html .= "<sub> [2] </sub>\n ";
			$html .= "		</td> \n";
			
			
			//SALUD BUCAL
			$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > RIESGO, ENFERMEDAD O DISCAPACIDAD \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" width=\"10%\" > NO H.C \n";
			$html .= "			<sub> [3] </sub>\n ";
			$html .= "		</td> \n";
			
			//NO HISTORIA CLINICA
			
			//$html .= "		<td align=\"center\" colspan=\"2\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > NO IDENTIFICACION \n";
			//$html .= "		</td> \n";
			//$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > NUMERO DE IDENTIFICACION \n";
			//$html .= "		</td> \n";
			$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" >MUJER EMBARAZADA \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "        <input type=\"hidden\" name=\"contTipo1\" value=\"0\" class=\"input-text\">\n";
			$html .= "        <input type=\"hidden\" name=\"contTipo2\" value=\"0\" class=\"input-text\">\n";
			$html .= "        <input type=\"hidden\" name=\"contTipo3\" value=\"0\" class=\"input-text\">\n";
			$html .= "        <input type=\"hidden\" name=\"contTipo4\" value=\"0\" class=\"input-text\">\n";
			
			foreach($datCamp as $key => $posvec){
				$html .= "	<tr class='modulo_list_claro' onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" > \n";
				$html .= "<input type=\"hidden\" name=\"idFamEmbazd\" value=\"".$posvec['familiar_id']."\" > \n ";
				$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > ".$posvec['rango']." \n";
				$html .= "		</td> \n";
				
				$nomCompl = $posvec['primer_nombre']." ".$posvec['segundo_nombre']." ".$posvec['primer_apellido']." ".$posvec['segundo_apellido'];

				$html .= "		<td align=\"left\" colspan=\"4\" > ".$posvec['tipo_identi_fam'].". ".$posvec['no_identi_fam']." ".$nomCompl." \n";
				$html .= "		</td> \n";
				
				$arrParent = $obCons->ObtenerParentesco("".$posvec['parentesco']);
				$html .= "		<td align=\"center\" colspan=\"1\" > ".$arrParent[0]['descripcion']." \n";
				$html .= "		</td> \n";
				
					//$fechaNac = $posvec['fecha_nacim'];
				$f = explode("-",$posvec['fecha_nacim']);
				if(sizeof($f) == 3) $fechaNac = $f[2]."/".$f[1]."/".$f[0];
					
				$html .= "		<td align=\"center\" colspan=\"1\" > ".$fechaNac." \n";
				$html .= "		</td> \n";
				
				$html .= "		<td align=\"center\" colspan=\"1\" > ".$posvec['edad']." \n";
				$html .= "		</td> \n";
				
				$arrOcupa = $obCons->ObtenerOcupacion("".$posvec['ocupacion']);
				$html .= "		<td align=\"center\" colspan=\"1\" > ".$arrOcupa['ocupacion_descripcion']." \n";
				$html .= "		</td> \n";
				
				if($posvec['sexo'] == "1"){
					$strSexo = "HOMBRE";
					//$htmlChkEmbazd .= "";	
					$dsbEmbazd = "disabled";
				}
				else{
					$strSexo = "MUJER";
					
					$dsbEmbazd = "";
				}
				$html .= "		<td align=\"center\" colspan=\"1\" > ".$strSexo." \n";
				$html .= "		</td> \n";
				
				$arrEscolar = $obCons->ObtenerInstruccion($posvec['escolaridad']);
				$html .= "		<td align=\"center\" colspan=\"1\" > ".$arrEscolar[0]['descripcion']." \n";
				$html .= "		</td> \n";
				
				if($posvec['esquema_vacunas'] == "1")
					$strEsqVac = "SI";
				else
					$strEsqVac = "NO";
				$html .= "		<td align=\"center\" colspan=\"1\" > ".$strEsqVac." \n";
				$html .= "		</td> \n";

				if($posvec['salud_bucal'] == "1")
					$strSalBuc = "SI";
				else
					$strSalBuc = "NO";
				$html .= "		<td align=\"center\" colspan=\"1\" > ".$strSalBuc." \n";
				$html .= "		</td> \n";
				
				$html .= "		<td align=\"center\" colspan=\"1\" > ".$posvec['rie_enf_disca']." \n";
				$html .= "		</td> \n";
				$html .= "		<td align=\"center\" colspan=\"1\" > ".$posvec['hist_clinica']." \n";
				$html .= "		</td> \n";
				
				//$arrTipoIdPaciente = $obCons->ObtenerTipoIdPaciente($posvec['tipo_identi_fam']);
				
// 				$html .= "		<td align=\"center\" colspan=\"2\" > ".$posvec['tipo_identi_fam'].". ".$posvec['no_identi_fam']."\n";
// 				$html .= "		</td> \n";

				$nomCompl = $posvec['primer_nombre']." ".$posvec['segundo_nombre']." ".$posvec['primer_apellido']." ".$posvec['segundo_apellido'];
	
// 				if($posvec['embarazada'] == 2)
// 					$chkEmbazd = "checked";
// 				else
					$chkEmbazd = "";
				
				$html .= "		<td align=\"center\" colspan=\"1\" > \n";
				$html .= "			<input type=\"checkbox\" name=\"chkEmbazd\" ".$dsbEmbazd." ".$chkEmbazd." onclick=\"verificarFamEmbzd(this.checked, ".$posvec['familiar_id'].", '".$nomCompl."')\" > \n";
				//$html .= $htmlChkEmbazd;
				$html .= "		</td> \n";
				
				$html .= "	</tr> \n";
			}
	
	// 		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > 1 - 4 AÑOS \n";
	// 		$html .= "		</td> \n";
	// 
	// 		$html .= "		<td align=\"center\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > 5 - 9 AÑOS \n";
	// 		$html .= "		</td> \n";
	// 
	// 		$html .= "		<td align=\"center\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > 10 - 19 AÑOS \n";
	// 		$html .= "		</td> \n";
	// 
	// 		$html .= "		<td align=\"center\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > 20 - 64 AÑOS \n";
	// 		$html .= "		</td> \n";
	// 
	// 		$html .= "		<td align=\"center\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > 65 AÑOS O MAS\n";
	// 		$html .= "		</td> \n";
			
			//$html .= "		<div> \n";
			
			$html .= "</table> \n";
			//$html .= "</form> \n";
			
			$html .= " <br>";
			
			$html .= "<table align=\"center\" > \n";
			$html .= "	<tr> \n";
			$html .= "		<td align=\"center\" > \n"; 
			$html .= "			<input type=\"button\" class=\"input-submit\" name=\"BtnIngresar_Fam\" value=\"Ingresar Familiar\" onclick=\"xajax_DatosFamiliar('".$idPaciente."')\" > \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "</table> \n";
			
			//class='modulo_list_claro'
			//class=\"hc_table_submodulo_list_title\"
			$html .= " <br>";
			
			$html .= "<table align=\"center\" width=\"100%\" > \n";
			$html .= "	<tr> \n";
			$html .= "		<td width=\"75%\" >  \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"left\" class=\"hc_table_submodulo_list_title\" > [1] Esquema Completo de Vacunas \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr> \n";
			$html .= "		<td > \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"left\" class=\"hc_table_submodulo_list_title\" > [2] Salud Bucal \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			$html .= "	<tr> \n";
			$html .= "		<td > \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"left\" class=\"hc_table_submodulo_list_title\" > [3] No Historia Clinica \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "</table> \n";
			
			$html .= "<script> ";
			$html .= "	
						function mOvr(src,clrOver){
							src.style.background = clrOver;
						}
			
						function mOut(src,clrIn){
							src.style.background = clrIn;
						}  \n";
						
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
			$html .= "		var bol = true;\n";
			$html .= "		var arr = fecha.split('/');\n";
			$html .= "		if(arr.length != 3)\n";
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
						
			$html .=	"function validarEntero(valor){
							valor = parseInt(valor);
							
							if(isNaN(valor)){
							return \"\";
							}
							else{
							return valor;
							} 
						} 
			
			
						function validarDatosFam(idPacie){
							
							if(document.formIngFami.priApellFam.value == \"\" ){
 								document.getElementById('errorFam').innerHTML = 'Debe ingresar el Primer Apellido del Familiar!'; \n
								document.formIngFami.priApellFam.focus(); \n
								return false; 
							}
						
// 							if(document.formIngFami.secApellFam.value == \"\" ){
//  								document.getElementById('errorFam').innerHTML = 'Debe ingresar el Segundo Apellido del Familiar!'; \n
// 								document.formIngFami.secApellFam.focus(); \n
// 								return false; 
// 							}
							
							if(document.formIngFami.priNomFam.value == \"\" ){
 								document.getElementById('errorFam').innerHTML = 'Debe ingresar el Primer Nombre del Familiar!'; \n
								document.formIngFami.priNomFam.focus(); \n
								return false; 
							}
						
// 							if(document.formIngFami.secNomFam.value == \"\" ){
//  								document.getElementById('errorFam').innerHTML = 'Debe ingresar el Segundo Nombre del Familiar!'; \n
// 								document.formIngFami.secNomFam.focus(); \n
// 								return false; 
// 							}
							
							if(document.formIngFami.parentFam.value == \"-1\" ){
 								document.getElementById('errorFam').innerHTML = 'Debe ingresar el Tipo de Parentesco con el Familiar!'; \n
								document.formIngFami.parentFam.focus(); \n
								return false; 
							}
							
							if(document.formIngFami.fechaNacim.value == \"\" ){
 								document.getElementById('errorFam').innerHTML = 'Debe ingresar la Fecha de Nacimiento del Familiar!'; \n
								document.formIngFami.fechaNacim.focus(); \n
								return false; 
							}
							
							if(!IsDate(document.formIngFami.fechaNacim.value)){
								document.getElementById('errorFam').innerHTML = 'El Formato de la Fecha de Nacimiento del Familiar NO ES CORRECTO!'; \n
								document.formIngFami.fechaNacim.focus(); \n
								return false; 
							}
							
							
							
							if(document.formIngFami.ocupacion_id.value == \"\" ){
 								document.getElementById('errorFam').innerHTML = 'Debe ingresar una Ocupacion para el Familiar!'; \n
								//document.formIngFami.btnOcupa.focus(); \n
								return false; 
							}
							
							if(document.formIngFami.escolarFam.value == \"-1\" ){
 								document.getElementById('errorFam').innerHTML = 'Debe ingresar el Nivel de Escolaridad del Familiar!'; \n
								document.formIngFami.escolarFam.focus(); \n
								return false; 
							}
							
							if(document.formIngFami.histClinFam.value == \"\" ){
 								document.getElementById('errorFam').innerHTML = 'Debe ingresar el numero de Historia Clinica del Familiar!'; \n
								document.formIngFami.histClinFam.focus(); \n
								return false; 
							}
							
							if(document.formIngFami.noIdentiFam.value == \"\" ){
 								document.getElementById('errorFam').innerHTML = 'Debe ingresar el Numero de Identificacion del Familiar!'; \n
								document.formIngFami.noIdentiFam.focus(); \n
								return false; 
							}
							
							if(document.formIngFami.tipoIdentiFam.value == \"-1\" ){
 								document.getElementById('errorFam').innerHTML = 'Debe ingresar un Tipo de Identificacion para el Familiar!'; \n
								document.formIngFami.tipoIdentiFam.focus(); \n
								return false; 
							}
							

							
							document.getElementById('errorFam').innerHTML = null;
							
							xajax_InsDatFamili(xajax.getFormValues('formIngFami'), xajax.getFormValues('formDatFami'), idPacie);
						}
						
						
						function verificarFamEmbzd(valChkEmbazd, valIdFamEmbazd, valNomComp){
										
							//if(document.formDatFami.chkEmbazd.checked == true){
							//	alert('FamEmbzd: ' + chkEmbazd + '\\n');
							//}
							
							if(valChkEmbazd == true){
								//alert('FamEmbzd: ' + valChkEmbazd + '\\n' +
								//	'IdFamEmbazd: ' + valIdFamEmbazd + '\\n' +  
								//	'NomComp: ' + valNomComp);
									  
								xajax_DatosFamiliarEmbzd(valChkEmbazd, valIdFamEmbazd, valNomComp);
							}
							else{
								//xajax_InsDatFamEmbzd(xajax.getFormValues('formIngFamEmbzd'), xajax.getFormValues('formDatFami'), vIdPacie);
								xajax_InsDatFamEmbzd(valIdFamEmbazd);
								
								//alert('idFamiliar: ' + valIdFamEmbazd + '\\n');
							}
							
						}
						
						function cargar(){
						
							alert('Hollaaa!!!' );
							document.formIngFamEmbzd.idFamiliar.readOnly = 7777777;
						}
						
						function validarDatosFamEmbzd(vIdPacie){
							
							if(document.formIngFamEmbzd.fechaUltMenstr.value == \"\" ){
 								document.getElementById('errorFamEmbzd').innerHTML = 'Debe ingresar la Ultima Fecha de Menstruacion de la Familiar!'; \n
								document.formIngFamEmbzd.fechaUltMenstr.focus(); \n
								return false; 
							}
							
							if(!IsDate(document.formIngFamEmbzd.fechaUltMenstr.value)){
								document.getElementById('errorFamEmbzd').innerHTML = 'El Formato de la Ultima Fecha de Menstruacion de la Familiar NO ES CORRECTO!'; \n
								document.formIngFamEmbzd.fechaUltMenstr.focus(); \n
								return false; 
							}
							
							if(document.formIngFamEmbzd.fechaProbParto.value == \"\" ){
 								document.getElementById('errorFamEmbzd').innerHTML = 'Debe ingresar la Fecha Probable del Parto de la Familiar!'; \n
								document.formIngFamEmbzd.fechaProbParto.focus(); \n
								return false; 
							}	
							
							if(!IsDate(document.formIngFamEmbzd.fechaProbParto.value)){
								document.getElementById('errorFamEmbzd').innerHTML = 'El Formato de la Fecha Probable del Parto de la Familiar NO ES CORRECTO!'; \n
								document.formIngFamEmbzd.fechaProbParto.focus(); \n
								return false; 
							}
							
							feUltMen = document.formIngFamEmbzd.fechaUltMenstr.value; \n 
							feProPar = document.formIngFamEmbzd.fechaProbParto.value; \n 
							fecha_u_m = feUltMen.split('/'); \n 
							fecha_p_p = feProPar.split('/'); \n 
							ffUltMen = new Date(fecha_u_m[2] + '/' + fecha_u_m[1] + '/' + fecha_u_m[0]); \n 
							ffProPar = new Date(fecha_p_p[2] + '/' + fecha_p_p[1] + '/' + fecha_p_p[0]); \n 
							
							if(ffUltMen > ffProPar){
								document.getElementById('errorFamEmbzd').innerHTML = 'La Fecha Probable de Parto debe ser menor o igual a la Fecha de la Ultima Menstruacion!'; \n
								document.formIngFamEmbzd.fechaProbParto.focus(); \n
								return false; \n							
							}
							
							if(document.formIngFamEmbzd.semGestac.value == \"\" ){
								document.getElementById('errorFamEmbzd').innerHTML = 'Debe ingresar las Semanas de Gestacion de la Familiar!'; \n
								document.formIngFamEmbzd.semGestac.focus(); \n
								return false; \n
							}
							
							if(!IsNumeric(document.formIngFamEmbzd.semGestac.value)){
								document.getElementById('errorFamEmbzd').innerHTML = 'Semanas de Gestacion debe ser un numero!'; \n
								document.formIngFamEmbzd.semGestac.focus(); \n
								return false; \n
							}
							
							if(document.formIngFamEmbzd.priDosis.value == \"\" ){
 								document.getElementById('errorFamEmbzd').innerHTML = 'Debe ingresar la Primera Dosis de la Familiar!'; \n
								document.formIngFamEmbzd.priDosis.focus(); \n
								return false; 
							}
							
							if(!IsDate(document.formIngFamEmbzd.priDosis.value)){
								document.getElementById('errorFamEmbzd').innerHTML = 'El Formato de la Fecha de Primera Dosis del Familiar NO ES CORRECTO!'; \n
								document.formIngFamEmbzd.priDosis.focus(); \n
								return false; 
							}
							
							if(document.formIngFamEmbzd.segDosis.value == \"\" ){
 								document.getElementById('errorFamEmbzd').innerHTML = 'Debe ingresar la Segunda Dosis de la Familiar!'; \n
								document.formIngFamEmbzd.segDosis.focus(); \n
								return false; 
							}
							
							if(!IsDate(document.formIngFamEmbzd.segDosis.value)){
								document.getElementById('errorFamEmbzd').innerHTML = 'El Formato de la Fecha de Segunda Dosis del Familiar NO ES CORRECTO!'; \n
								document.formIngFamEmbzd.segDosis.focus(); \n
								return false; 
							}
							
							if(document.formIngFamEmbzd.rfzDosis.value == \"\" ){
 								document.getElementById('errorFamEmbzd').innerHTML = 'Debe ingresar la Dosis de Refuerzo de la Familiar!'; \n
								document.formIngFamEmbzd.rfzDosis.focus(); \n
								return false; 
							}
							
							if(!IsDate(document.formIngFamEmbzd.rfzDosis.value)){
								document.getElementById('errorFamEmbzd').innerHTML = 'El Formato de la Fecha de Dosis de Refuerzo del Familiar NO ES CORRECTO!'; \n
								document.formIngFamEmbzd.rfzDosis.focus(); \n
								return false; 
							}
							
							fePriDosis = document.formIngFamEmbzd.priDosis.value; \n 
							feSegDosis = document.formIngFamEmbzd.segDosis.value; \n 
							fecha_p_d = fePriDosis.split('/'); \n 
							fecha_s_d = feSegDosis.split('/'); \n 
							ffPriDosis = new Date(fecha_p_d[2] + '/' + fecha_p_d[1] + '/' + fecha_p_d[0]); \n 
							ffSegDosis = new Date(fecha_s_d[2] + '/' + fecha_s_d[1] + '/' + fecha_s_d[0]); \n 
							
							if(ffPriDosis > ffSegDosis){
								document.getElementById('errorFamEmbzd').innerHTML = 'La Fecha de la Segunda Dosis debe ser superior o igual a la Fecha de la Primera Dosis!'; \n
								document.formIngFamEmbzd.segDosis.focus(); \n
								return false; \n							
							}
							
							
							feSegDosis = document.formIngFamEmbzd.segDosis.value; \n 
							feRfzDosis = document.formIngFamEmbzd.rfzDosis.value; \n 
							fecha_s_d = feSegDosis.split('/'); \n 
							fecha_r_d = feRfzDosis.split('/'); \n 
							ffSegDosis = new Date(fecha_s_d[2] + '/' + fecha_s_d[1] + '/' + fecha_s_d[0]); \n 
							ffRfzDosis = new Date(fecha_r_d[2] + '/' + fecha_r_d[1] + '/' + fecha_r_d[0]); \n
							
							if(ffSegDosis > ffRfzDosis){
								document.getElementById('errorFamEmbzd').innerHTML = 'La Fecha de la Dosis de Refuerzo debe ser superior o igual a la Fecha de la Segunda Dosis!'; \n
								document.formIngFamEmbzd.rfzDosis.focus(); \n
								return false; \n							
							}
							
							
							if(document.formIngFamEmbzd.agoGestas.value == \"\" ){
 								document.getElementById('errorFamEmbzd').innerHTML = 'Debe ingresar las Gestas de la Familiar!'; \n
								document.formIngFamEmbzd.agoGestas.focus(); \n
								return false; 
							}
							
							if(!IsNumeric(document.formIngFamEmbzd.agoGestas.value)){
								document.getElementById('errorFamEmbzd').innerHTML = 'La cantidad de Gestas deben ser un numero!'; \n
								document.formIngFamEmbzd.agoGestas.focus(); \n
								return false; \n
							}
							
							if(document.formIngFamEmbzd.agoPartos.value == \"\" ){
 								document.getElementById('errorFamEmbzd').innerHTML = 'Debe ingresar los Partos de la Familiar!'; \n
								document.formIngFamEmbzd.agoPartos.focus(); \n
								return false; 
							}
							
							if(!IsNumeric(document.formIngFamEmbzd.agoPartos.value)){
								document.getElementById('errorFamEmbzd').innerHTML = 'La cantidad de Partos deben ser un numero!'; \n
								document.formIngFamEmbzd.agoPartos.focus(); \n
								return false; \n
							}
							
							if(document.formIngFamEmbzd.agoAbortos.value == \"\" ){
 								document.getElementById('errorFamEmbzd').innerHTML = 'Debe ingresar los Abortos de la Familiar!'; \n
								document.formIngFamEmbzd.agoAbortos.focus(); \n
								return false; 
							}
							
							if(!IsNumeric(document.formIngFamEmbzd.agoAbortos.value)){
								document.getElementById('errorFamEmbzd').innerHTML = 'La cantidad de Abortos deben ser un numero!'; \n
								document.formIngFamEmbzd.agoAbortos.focus(); \n
								return false; \n
							}
							
							if(document.formIngFamEmbzd.agoCesareas.value == \"\" ){
 								document.getElementById('errorFamEmbzd').innerHTML = 'Debe ingresar las Cesareas de la Familiar!'; \n
								document.formIngFamEmbzd.agoCesareas.focus(); \n
								return false; 
							}
							
							if(!IsNumeric(document.formIngFamEmbzd.agoCesareas.value)){
								document.getElementById('errorFamEmbzd').innerHTML = 'La cantidad de Cesareas deben ser un numero!'; \n
								document.formIngFamEmbzd.agoCesareas.focus(); \n
								return false; \n
							}
							
							document.getElementById('errorFamEmbzd').innerHTML = null;
							
							xajax_InsDatFamEmbzd(xajax.getFormValues('formIngFamEmbzd'), xajax.getFormValues('formDatFami'), vIdPacie);
						
						}
						
						
						function validarDatosFamMort(idPacie){
							
							if(document.formIngFamMort.priApellFamMort.value == \"\" ){
 								document.getElementById('errorFamMort').innerHTML = 'Debe ingresar el Primer Apellido del Familiar Difunto!'; \n
								document.formIngFamMort.priApellFamMort.focus(); \n
								return false; 
							}
						
// 							if(document.formIngFamMort.secApellFamMort.value == \"\" ){
//  								document.getElementById('errorFamMort').innerHTML = 'Debe ingresar el Segundo Apellido del Familiar Difunto!'; \n
// 								document.formIngFamMort.secApellFamMort.focus(); \n
// 								return false; 
// 							}
 							
							if(document.formIngFamMort.priNomFamMort.value == \"\" ){
 								document.getElementById('errorFamMort').innerHTML = 'Debe ingresar el Primer Nombre del Familiar Difunto!'; \n
								document.formIngFamMort.priNomFamMort.focus(); \n
								return false; 
							}
						
// 							if(document.formIngFamMort.secNomFamMort.value == \"\" ){
//  								document.getElementById('errorFamMort').innerHTML = 'Debe ingresar el Segundo Nombre del Familiar Difunto!'; \n
// 								document.formIngFamMort.secNomFamMort.focus(); \n
// 								return false; 
// 							}
						
							if(document.formIngFamMort.parentFamMort.value == \"-1\" ){
 								document.getElementById('errorFamMort').innerHTML = 'Debe ingresar el Tipo de Parentesco con el Familiar Difunto!'; \n
								document.formIngFamMort.parentFamMort.focus(); \n
								return false; 
							}
							
							if(document.formIngFamMort.edadFalleFamMort.value == \"\" ){
 								document.getElementById('errorFamMort').innerHTML = 'Debe ingresar la Edad de fallecimiento del Familiar!'; \n
								document.formIngFamMort.edadFalleFamMort.focus(); \n
								return false; 
							}
							
							if(!IsNumeric(document.formIngFamMort.edadFalleFamMort.value)){
								document.getElementById('errorFamMort').innerHTML = 'La Edad de fallecimiento debe ser un numero!'; \n
								document.formIngFamMort.edadFalleFamMort.focus(); \n
								return false; \n
							}
							
							document.getElementById('errorFamMort').innerHTML = null;
							
							xajax_InsDatFamMort(xajax.getFormValues('formIngFamMort'), xajax.getFormValues('formDatFami'), idPacie);
						}
						 
					";
			$html .= "</script> ";
			
			return $html; 
		}

	/**
	*
	*/
	function frmEmbarazFamiliar($obCons){
	
		//$datCamp = $obCons->ConsultarFamiliar();
		$datCamp = $obCons->ConsultarFamiliarEmbzd();
	
		$html .= "<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\" > \n";
		
		$html .= "	<tr class=\"modulo_table_title\"> \n";
		$html .= "		<td align=\"center\" colspan=\"19\" > EMBARAZOS \n";
		$html .= "		</td> \n";
		$html .= "	</tr> \n";
		$html .= "	<tr> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"2\" class=\"hc_table_submodulo_list_title\" > GRUPO \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"2\" class=\"hc_table_submodulo_list_title\" > APELLIDOS Y NOMBRES \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"2\" class=\"hc_table_submodulo_list_title\" > FECHA DE ULTIMA MENSTRUACION \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"2\" class=\"hc_table_submodulo_list_title\" > FECHA PROBABLE DEL PARTO \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"2\" class=\"hc_table_submodulo_list_title\" > SEMANAS DE GESTACION \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"3\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > DOSIS DE VACUNACION dT \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"4\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > ANTECEDENTES GINECO-OBSTETRICOS \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"2\" class=\"hc_table_submodulo_list_title\" > ANTECEDENTES PATOLOGICOS OBSTETRICOS \n";
		$html .= "		</td> \n";
		$html .= "	</tr> \n";
		$html .= "	<tr> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > PRIMERA \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > SEGUNDA \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > REFUERZO \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > GESTAS \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > PARTOS \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > ABORTOS \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > CESAREAS \n";
		$html .= "		</td> \n";
		$html .= "	</tr> \n";
		
		foreach($datCamp as $key => $posvec){
			$html .= "	<tr class='modulo_list_claro' onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" > \n";
			$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > GRUPO \n";
			$html .= "		</td> \n";
			
			$nomCompl = $posvec['primer_nombre']." ".$posvec['segundo_nombre']." ".$posvec['primer_apellido']." ".$posvec['segundo_apellido'];
			
			$html .= "		<td align=\"center\" > ".$nomCompl." \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\"> ".$posvec['fecha_ult_menstruacion']." \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\"> ".$posvec['fecha_prob_parto']." \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\"> ".$posvec['semanas_gesta']." \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\"> ".$posvec['pri_dosis']." \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\"> ".$posvec['seg_dosis']." \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\"> ".$posvec['refuerzo_dosis']." \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\"> ".$posvec['gestas']." \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\"> ".$posvec['partos']." \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\"> ".$posvec['abortos']." \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\"> ".$posvec['cesareas']." \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\"> ".$posvec['ante_pato_obstre']." \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
		}

		$html .= "</table> \n";
		
		return $html;
	}
		
		/**
		*	Forma donde se listan los Familiares que ha fallecido
		*/
		function frmMortalFamiliar($obCons, $idPaciente){
		
			$datCamp = $obCons->ConsultarFamiliar("2", $idPaciente);
		
			$html .= "<table border=\"0\" width=\"90%\" class=\"modulo_table_list\" > \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td align=\"center\" colspan=\"7\" > MORTALIDAD FAMILIAR \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			//Titulos
			$html .= "	<tr> \n";
			$html .= "		<td align=\"center\" colspan=\"4\"  class=\"hc_table_submodulo_list_title\" > APELLIDOS Y NOMBRES \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\" colspan=\"1\"  class=\"hc_table_submodulo_list_title\" > PARENTESCO \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\" colspan=\"1\"  class=\"hc_table_submodulo_list_title\" > EDAD AL FALLECER \n";
			$html .= "		</td> \n";
			$html .= "		<td align=\"center\" colspan=\"1\"  class=\"hc_table_submodulo_list_title\" > CAUSA \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			
			foreach($datCamp as $key => $posvec){
				//$html .= "	<tr> \n";
				$html .= "	<tr class='modulo_list_claro' onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" > \n";
				
				$nomCompl = $posvec['primer_nombre']." ".$posvec['segundo_nombre']." ".$posvec['primer_apellido']." ".$posvec['segundo_apellido'];

				$html .= "		<td align=\"center\" colspan=\"4\" > ".$nomCompl." \n";
				$html .= "		</td> \n";
				
				$arrParent = $obCons->ObtenerParentesco("".$posvec['parentesco']);
				$html .= "		<td align=\"center\" colspan=\"1\" > ".$arrParent[0]['descripcion']." \n";
				$html .= "		</td> \n";
				
				$html .= "		<td align=\"center\" colspan=\"1\" > ".$posvec['edad_fallece']." \n";
				$html .= "		</td> \n";
				$html .= "		<td align=\"center\" colspan=\"1\" > ".$posvec['causa']." \n";
				$html .= "		</td> \n";
				$html .= "	</tr> \n";
			}
			
			$html .= "</table>";
			
			$html .= " <br>";
			
			$html .= "<table align=\"center\" > \n";
			$html .= "		<td align=\"center\" > \n"; 
			$html .= "			<input type=\"button\" class=\"input-submit\" name=\"BtnIngresar_Fam_Mortal\" value=\"Ingresar Familiar Difunto\" onclick=\"xajax_DatosFamiliarMortal('".$idPaciente."')\" > \n";
			$html .= "		</td> \n";
			$html .= "</table> \n";
			
			return $html;
		}

	
	}
?>