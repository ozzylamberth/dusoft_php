<?php
	/**************************************************************************************
	* $Id: hc_FichaFamiliar_HTML.php,v 1.2 2008/10/10 13:44:25 gerardo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.2 $ 	
	* @author Gerardo Amador Vidal
	*
	***************************************************************************************/
IncludeClass("ClaseHTML");

class FichaFamiliar_HTML extends FichaFamiliar{
	
	//Constructor de la clase
	function FichaFamiliar_HTML(){
		$this->FichaFamiliar();
		$this->cantMostrar=2;
		return true;
	}
	
	var $cantMostrar = 0;

	function FormaInfoPaciente(){
	
	}

	function FormaInfoMiembFamilia($pfj){
	
		$this->SetXajax(array("DatosFamiliar", "DatosFamiliarMortal", "DatosFamiliarEmbzd", 
						"InsDatFamili", "InsDatFamEmbzd", "InsDatFamMort", "EvaluarDatos"), 
						"hc_modules/FichaFamiliar/RemoteXajax/FuncionesFamiliar.php");    
		
		$this->SetJavaScripts("Ocupaciones");
		
// 		$this->IncludeJS("CrossBrowser");
// 		$this->IncludeJS("CrossBrowserEvent");
// 		$this->IncludeJS("CrossBrowserDrag");
		
		//$request = $_REQUEST;
		
		$obCons = AutoCarga::factory('FichaFamiliarMetodos','','hc1','FichaFamiliar');
		
		$datPaciente = $this->datosPaciente;
		$action['IngFichaFamiliar'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>"IngFichaFamiliar"));
		$html .= ThemeAbrirTablaSubModulo();
		//$html .= "<pre>".print_r($datPaciente,true)."</pre>";
		$html .= "<form id=\"formFichaFam\" name=\"formFichaFam\" action=\"".$action['IngFichaFamiliar']."\" method=\"post\"> \n";
		$html .= "<table align=\"center\" width=\"100%\" class=\"modulo_table_list\" > \n";
		
		$vNoFichaFam = $obCons->ObtenNumFichFamil();
		$humPr = $vNoFichaFam[0]['setval'] + 1;
		
		$html .= "	<tr> \n";
		//$html .= "		<td align=\"center\" colspan=\"12\" > FICHA FAMILIAR \n";
		$html .= "		<td align=\"center\" width=\"70%\" class=\"modulo_table_title\" > FICHA FAMILIAR \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" width=\"30%\" > NUMERO DE FICHA FAMILIAR :    ".$humPr." \n";
		$html .= "		</td> \n";
		$html .= "	<input type=\"hidden\" name=\"noFichaFam\" value=\"".$humPr."\">\n";
		$html .= "	</tr> \n";
		
		$html .= "</table> \n";
		
		$html .= "	<input type=\"hidden\" name=\"paciente_id\" value=\"".$datPaciente['paciente_id']."\">\n";
		
		$html .= "<table align=\"center\" width=\"100%\" class=\"modulo_table_list\" > \n";
		$html .= "	<tr class=\"modulo_table_list\" > \n";
		$html .= "		<td align=\"center\" class=\"hc_table_submodulo_list_title\" > INSTRUCCION DEL SISTEMA \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" class=\"hc_table_submodulo_list_title\" > UNIDAD OPERATIVA \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" class=\"hc_table_submodulo_list_title\" > CODIGO UO \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" class=\"hc_table_submodulo_list_title\" > AREA NO \n";
		$html .= "		</td> \n";
		$html .= "	</tr> \n";
		$html .= "	<tr> \n";
		$html .= "		<td align=\"center\" > \n";
		$html .= "    		<input type=\"text\" class=\"input-text\" name=\"instSistem\" size=\"30\" maxlength=\"20\" value=\"\" > \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" > \n";
		$html .= "    		<input type=\"text\" class=\"input-text\" name=\"unidOper\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" > \n";
		$html .= "    		<input type=\"text\" class=\"input-text\" name=\"codigoUO\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" > \n";
		$html .= "    		<input type=\"text\" class=\"input-text\" name=\"areaNO\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
		$html .= "		</td> \n";
		$html .= "	</tr> \n";
		$html .= "</table> \n";
		
		$html .= " <br>";
		
		$html .= "<table align=\"center\" width=\"100%\" class=\"modulo_table_list\" > \n";
		$html .= "	<tr class=\"modulo_table_list\" > \n";
		$html .= "		<td align=\"center\" colspan=\"2\" class=\"hc_table_submodulo_list_title\" > DIRECCION HABITUAL DE LA FAMILIA (CALLES O REFERENCIA) \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"5\" > ".$datPaciente['residencia_direccion']." \n";
		$html .= "		</td> \n";
		$html .= "	</tr> \n";
		$html .= "</table> \n";
		
		$html .= "<table align=\"center\" width=\"100%\" class=\"modulo_table_list\" > \n";
		$html .= "	<tr > \n";
		$html .= "		<td align=\"center\" colspan=\"3\" width=\"60%\" class=\"hc_table_submodulo_list_title\"  > CODIGO LOCALIZACION \n";
		$html .= "		</td> \n";
		
// 		$html .= "		<td align=\"center\" rowspan=\"2\" class=\"hc_table_submodulo_list_title\" > SECTOR \n";
// 		$html .= "		</td> \n";
// 		$html .= "		<td align=\"center\" rowspan=\"2\" class=\"hc_table_submodulo_list_title\" > MANZANA \n";
// 		$html .= "		</td> \n";
		$html .= "	</tr> \n";
		
		$html .= "	<tr> \n";
		$html .= "		<td align=\"center\" class=\"hc_table_submodulo_list_title\" > PROVINCIA \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" class=\"hc_table_submodulo_list_title\" > CANTON \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" class=\"hc_table_submodulo_list_title\" > PARROQUIA \n";
		$html .= "		</td> \n";
		$html .= "	</tr> \n";
		
		$html .= "	<tr> \n";
		$html .= "		<td align=\"center\" > ".$datPaciente['departamento']." \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" > ".$datPaciente['municipio']." \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" > PARROQUIA \n";
		$html .= "		</td> \n";
// 		$html .= "		<td align=\"center\" > \n";
// 		$html .= "    	<input type=\"text\" class=\"input-text\" name=\"sector\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
// 		$html .= "		</td> \n";
// 		$html .= "		<td align=\"center\" > \n";
// 		$html .= "    	<input type=\"text\" class=\"input-text\" name=\"manzana\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
// 		$html .= "		</td> \n";
		$html .= "	</tr> \n";
		$html .= "</table> \n";
		
		$html .= " <br>";
		
// 		$html .= "	<tr> \n";
// 		$html .= "		<td align=\"center\" > \n";
// 		$html .= "    		<input type=\"text\" class=\"input-text\" name=\"codigoLocal\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
// 		$html .= "		</td> \n";
// 		$html .= "	<tr> \n";
		
		$html .= "<table align=\"center\" width=\"100%\" class=\"modulo_table_list\" > \n";
		$html .= "	<tr> \n";

		$html .= "		<td align=\"center\" colspan=\"2\" class=\"hc_table_submodulo_list_title\" > # DE FAMILIA \n";
		$html .= "		</td> \n";
// 		$html .= "		<td align=\"center\" width=\"20%\" class=\"hc_table_submodulo_list_title\" > BARRIO \n";
// 		$html .= "		</td> \n";
// 		$html .= "		<td align=\"center\" width=\"20%\" class=\"hc_table_submodulo_list_title\" > NUMERO DE CASA \n";
// 		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"2\" class=\"hc_table_submodulo_list_title\" > COMUNIDAD \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" class=\"hc_table_submodulo_list_title\" > GRUPO CULTURAL \n";
		$html .= "		</td> \n";
		
		$html .= "	</tr> \n";		
		
		$html .= "	<tr> \n";
		
		$html .= "		<td align=\"center\" colspan=\"2\" > \n";
		$html .= "    		<input type=\"text\" class=\"input-text\" name=\"numFamilia\" size=\"10\" maxlength=\"10\" value=\"\" >\n";
		$html .= "		</td> \n";
// 		$html .= "		<td align=\"center\" width=\"20%\" > \n";
// 		$html .= "    	<input type=\"text\" class=\"input-text\" name=\"barrio\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
// 		$html .= "		</td> \n";
// 		$html .= "		<td align=\"center\" width=\"20%\" > \n";
// 		$html .= "    	<input type=\"text\" class=\"input-text\" name=\"noCasa\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
// 		$html .= "		</td> \n";
		
		$html .= "		<td align=\"center\" colspan=\"2\" > \n";
		$html .= "      	<select name=\"comunidad\" class=\"select\">\n";
		$html .= "        		<option value=\"-1\">-- Seleccionar --</option>\n";
		$arrComunidad = $obCons->ObtenListComunidad();
		foreach($arrComunidad as $key => $vecComunidad){
			$html .= "        		<option value=\"".$vecComunidad['comunidad_id']."\" > ".$vecComunidad['descripcion']." </option> \n";
		}
		$html .= "      	</select> \n";
		$html .= "		</td> \n";
		
		$html .= "		<td align=\"center\" colspan=\"1\" > \n";
		$html .= "      	<select name=\"grupCult\" class=\"select\">\n";
		$html .= "        		<option value=\"-1\">-- Seleccionar --</option>\n";
		$arrGrupCult = $obCons->ObtenListGrupCultu();
		foreach($arrGrupCult as $key => $vecGrupCult){
			$html .= "        		<option value=\"".$vecGrupCult['grup_cult_id']."\" > ".$vecGrupCult['descripcion']." </option> \n";
		}
		$html .= "      	</select> \n";
		
		$html .= "		</td> \n";
		
		$html .= "	</tr> \n";
		
		$html .= "	<tr> \n";
		$html .= "		<td align=\"center\" class=\"hc_table_submodulo_list_title\" colspan=\"2\" > NOMBRE Y APELLIDO DEL JEFE DE FAMILIA \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" class=\"hc_table_submodulo_list_title\" colspan=\"1\" > NUMERO DE TELEFONO \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" class=\"hc_table_submodulo_list_title\" colspan=\"1\" > FECHA DE LLENADO \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" class=\"hc_table_submodulo_list_title\" colspan=\"1\" > NUMERO DE CARPETA \n";
		$html .= "		</td> \n";
		$html .= "	</tr> \n";
		$hoy = date("d/m/Y");
		$html .= "	<tr> \n";

		$html .= "		<td align=\"center\" colspan=\"2\" > \n";
		$html .= "    	<input type=\"text\" class=\"input-text\" name=\"nombApellJefeFam\" size=\"30\" maxlength=\"40\" value=\"\" >\n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\"  > ".$datPaciente['residencia_telefono']." \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\"  > ".$hoy." \n";
		$html .= "	<input type=\"hidden\" name=\"fechaLlena\" value=\"".$hoy."\" > \n";
		//$html .= "    	<input type=\"text\" class=\"input-text\" name=\"fechaLlena\" size=\"30\" maxlength=\"20\" value=\"".$hoy."\" >\n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\"  > \n";
		$html .= "    	<input type=\"text\" class=\"input-text\" name=\"noCarpeta\" size=\"10\" maxlength=\"10\" value=\"\" >\n";
		$html .= "		</td> \n";
		$html .= "	</tr> \n";
		$html .= "</table> \n";

/**heyyyyyy*/

		$html .= " <br>";
		
		
		$html .= "<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\" > \n";
		$html .= "	<tr class=\"modulo_table_title\"> \n";
		$html .= "		<td align=\"center\" colspan=\"12\" > INFORMACION GEOREFERENCIADA \n";
		$html .= "		</td> \n";
		$html .= "	</tr> \n";
		//Titulos
		$html .= "	<tr> \n";
		$html .= "		<td align=\"center\" colspan=\"4\" class=\"hc_table_submodulo_list_title\" > LATITUD \n";
		$html .= "    	<input type=\"text\" class=\"input-text\" name=\"latitud\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"4\" class=\"hc_table_submodulo_list_title\" > LONGITUD \n";
		$html .= "    	<input type=\"text\" class=\"input-text\" name=\"longitud\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"4\" class=\"hc_table_submodulo_list_title\" > ALTITUD \n";
		$html .= "    	<input type=\"text\" class=\"input-text\" name=\"altitud\" size=\"30\" maxlength=\"20\" value=\"\" >\n";
		$html .= "		</td> \n";
		$html .= "	</tr> \n";
		$html .= "</table>";
			
		//$html .= "</form>";
		
		$html .= "<script>
					function validarDatosFichaFam(){
//					alert('noFichaFam: ' + document.formFichaFam.noFichaFam.value + '\\n' + 
// 						'noCarpeta: ' + document.formFichaFam.noCarpeta.value + '\\n' +
// 						
// 						'latitud: ' + document.formFichaFam.latitud.value + '\\n' +
// 						'longitud: ' + document.formFichaFam.longitud.value + '\\n' + 
// 						'altitud: ' + document.formFichaFam.altitud.value + '\\n' + 
// 						
// 						
// 						'priApellResp: ' + document.formFichaFam.priApellResp.value + '\\n' + 
// 						'segApellResp: ' + document.formFichaFam.segApellResp.value + '\\n' + 
// 						'priNombResp: ' + document.formFichaFam.priNombResp.value + '\\n' + 
// 						'segNombResp: ' + document.formFichaFam.segNombResp.value + '\\n' 
// 						);
						
						//alert('idPaciente: ' + '".$datPaciente['paciente_id']."');
						
						if((document.formFichaFam.latitud.value != \"\") && !IsNumeric(document.formFichaFam.latitud.value)){
							document.getElementById('errorFichaFamili').innerHTML = 'La Georeferencia Latitud debe ser un numero!'; \n
							document.formFichaFam.latitud.focus(); \n
							return false; \n
						}
						
						if((document.formFichaFam.longitud.value != \"\") && !IsNumeric(document.formFichaFam.longitud.value)){
							document.getElementById('errorFichaFamili').innerHTML = 'La Georeferencia Longitud debe ser un numero!'; \n
							document.formFichaFam.longitud.focus(); \n
							return false; \n
						}
						
						if((document.formFichaFam.altitud.value != \"\") && !IsNumeric(document.formFichaFam.altitud.value)){
							document.getElementById('errorFichaFamili').innerHTML = 'La Georeferencia Altitud debe ser un numero!'; \n
							document.formFichaFam.altitud.focus(); \n
							return false; \n
						}
						
						if((document.formFichaFam.numFamilia.value != \"\") && !IsNumeric(document.formFichaFam.numFamilia.value)){
							document.getElementById('errorFichaFamili').innerHTML = 'La Cantidad de familiares debe ser un numero!'; \n
							document.formFichaFam.numFamilia.focus(); \n
							return false; \n
						}
						
						if(document.formFichaFam.comunidad.value == \"-1\" ){
							document.getElementById('errorFichaFamili').innerHTML = 'Debe seleccionar un Tipo de Comunidad'; \n
							document.formFichaFam.comunidad.focus(); \n
							return false; 
						}
						
						if(document.formFichaFam.grupCult.value == \"-1\" ){
							document.getElementById('errorFichaFamili').innerHTML = 'Debe seleccionar un Tipo de Grupo Cultural'; \n
							document.formFichaFam.grupCult.focus(); \n
							return false; 
						}
						
						if(document.formFichaFam.noCarpeta.value == \"\" ){
							document.getElementById('errorFichaFamili').innerHTML = 'Debe ingresar el Numero de la Carpeta'; \n
							document.formFichaFam.noCarpeta.focus(); \n
							return false; 
						}
						
						if(!IsNumeric(document.formFichaFam.noCarpeta.value)){
							document.getElementById('errorFichaFamili').innerHTML = 'El Numero de la Carpeta debe ser un numero!'; \n
							document.formFichaFam.noCarpeta.focus(); \n
							return false; \n
						}
						
						document.formFichaFam.submit();

					}
				  </script>";
				  
		$html .= " <br>";
		
// 		$campo = "fechaNacim";
// 		$forma = "formIngFami";
// 	  
// 		$html .= "<script language=\"javascript\">\n";
// 		$html .= "  function Mostrar_".$campo."()\n";
// 		$html .= "  {\n";
// 		$html .= "    var dia = '';\n";
// 		$html .= "    var mes = '';\n";
// 		$html .= "    var anyo = '';\n";
// 		$html .= "    var valor = '';\n";
// 		$html .= "    try{\n";
// 		$html .= "      valor = document.".$forma.".fechaNacim.value;\n";
// 		$html .= "    }catch(error){}\n";
// 		$html .= "    if(valor.length == 10)\n";
// 		$html .= "    {\n";
// 		$html .= "      dia = valor.split('/')[0];\n";
// 		$html .= "      mes = parseInt(valor.split('/')[1]) -1;\n";
// 		$html .= "      anyo = valor.split('/')[2];\n";
// 		$html .= "    }\n";
// 		$html .= "    CrearCalendario('fechaNacim','/',dia,mes,anyo);\n";
// 		$html .= "  }\n";
// 		$html .= "  function Ocultar_".$campo."(fecha)\n";
// 		$html .= "  {\n";
// 		$html .= "    if(fecha != '')\n";
// 		$html .= "      document.".$forma.".fechaNacim.value = fecha;\n";
// 		$html .= "    document.getElementById('calendario_px".$campo."').style.visibility = 'hidden';\n";
// 		$html .= "  }\n";
// 		$html .= "</script>\n";
		
 		$html .= "<script language=\"javascript\">\n";
		
		$html .= " var vFormCamp;";
		
		$html .= "  function Mostrar_Campo(vFormaCampo, cadCampo)\n";
		$html .= "  {\n";
		$html .= "    var dia = ''; \n";
		$html .= "    var mes = ''; \n";
		$html .= "    var anyo = ''; \n";
		$html .= "    var valor = ''; \n";
		$html .= "    try{\n";
		$html .= " 		vFormCamp = vFormaCampo;";
		$html .= "      valor = vFormaCampo.value;\n";	
 		$html .= "    }catch(error){}\n";
		$html .= "    if(valor.length == 10)\n";
		$html .= "    {\n";
		$html .= "      dia = valor.split('/')[0];\n";
		$html .= "      mes = parseInt(valor.split('/')[1]) -1;\n";
		$html .= "      anyo = valor.split('/')[2];\n";
		$html .= "    }\n";
		$html .= "    CrearCalendario('Campo','/',dia,mes,anyo);\n";
		$html .= "  }\n";
	    
		$html .= "function Ocupaciones(forma,prefijo)\n";
		$html .= "{\n";
		$html .= "var url='reports/HTML/ocupaciones.php?forma=' + forma +'&prefijo=' + prefijo;\n";
		$html .= "window.open(url,'','width=600,height=500,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no');\n";
		$html .= "}\n";

		$html .= "  function Ocultar_Campo(fecha)\n";
		$html .= "  {\n";
		$html .= "    if(fecha != '')\n";
// 		$html .= "      document.".$forma.".fechaNacim.value = fecha;\n";
		$html .= "      vFormCamp.value = fecha;\n";
		
 		$html .= "    document.getElementById('calendario_pxCampo').style.visibility = 'hidden';\n";
		$html .= "  }\n";
		
		$html .= "</script>\n";
		
		
		
// 		$html .= "<table align=\"center\" > \n";
// 		$html .= "		<td align=\"center\" > \n"; 
// 		$html .= "			<input type=\"button\" class=\"input-submit\" name=\"BtnIngresar_Fam\" value=\"Ingresar Familiar\" onclick=\"xajax_DatosFamiliar('".$datPaciente['paciente_id']."')\" > \n";
// 
// 		$html .= "		</td> \n";
// 		$html .= "</table> \n";
		
		$html .= " <br>";
		
		$mdl = AutoCarga::factory('IngresaFamiliarHTML','','hc1','FichaFamiliar');
		
		$html .= "<table width=\"100%\" align=\"center\">\n";
		$html .= "  <tr>\n";
		$html .= "    <td>\n";
		$html .= "      <div class=\"tab-pane\" id=\"pestaña_uno\">\n";
		$html .= "        <script> tabPane = new WebFXTabPane(document.getElementById(\"pestaña_uno\"), false); </script>\n";
		
		$html .= "        <div class=\"tab-page\" id=\"SeccionDatFam_id\">\n";
		$html .= "          <h2 class=\"tab\">MIEMBRO FAMILIAR</h2>\n";
		$html .= "          <script> tabPane.addTabPage(document.getaElementById(\"SeccionDatFam_id\"))</script>\n";
		$html .= $mdl->frmMiemFamiliar($obCons, $datPaciente['paciente_id']);
		$html .= "        </div>\n";
		
		$html .= "        <div class=\"tab-page\" id=\"SeccionDatFamEmbzd_id\">\n";
		$html .= "          <h2 class=\"tab\">FAMILIAR EN EMBARAZO</h2>\n";
		$html .= "          <script> tabPane.addTabPage(document.getaElementById(\"SeccionDatFamEmbzd_id\"))</script>\n";
		$html .= $mdl->frmEmbarazFamiliar($obCons);
		$html .= "        </div>\n";
		
		$html .= "        <div class=\"tab-page\" id=\"SeccionDatFamMort_id\">\n";
		$html .= "          <h2 class=\"tab\">MORTALIDAD FAMILIAR</h2>\n";
		$html .= "          <script> tabPane.addTabPage(document.getaElementById(\"SeccionDatFamMort_id\"))</script>\n";
		$html .= $mdl->frmMortalFamiliar($obCons, $datPaciente['paciente_id']);
		$html .= "        </div>\n";
		
		$html .= "      </div>\n";
		$html .= "    </td>\n";
		$html .= "  </tr>\n";
		$html .= "</table>\n";
		
		
		$html .= " <br>";
		
		
		$arrUsRsp  = $obCons->ObtenUsuario();
		
		$html .= "<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\" > \n";
		$html .= "	<tr class=\"modulo_table_title\"> \n";
		$html .= "		<td align=\"center\" colspan=\"3\" > RESPONSABLE DEL LLENADO \n";
		$html .= "		</td> \n";
		$html .= "	</tr> \n";
		
		//Titulos
		$html .= "	<tr> \n";
// 		$html .= "		<td align=\"center\" colspan=\"2\" class=\"hc_table_submodulo_list_title\" > PRIMER APELLIDO \n";
// 		$html .= "		<td align=\"center\" colspan=\"2\" class=\"hc_table_submodulo_list_title\" > SEGUNDO APELLIDO \n";
// 		$html .= "		<td align=\"center\" colspan=\"2\" class=\"hc_table_submodulo_list_title\" > PRIMER NOMBRE \n";
// 		$html .= "		<td align=\"center\" colspan=\"2\" class=\"hc_table_submodulo_list_title\" > SEGUNDO NOMBRE \n";
// 		$html .= "		</td> \n";

		$html .= "		<td align=\"center\" colspan=\"1\" class=\"hc_table_submodulo_list_title\" > NOMBRE COMPLETO \n";
		$html .= "		</td> \n";

		$html .= "		<td align=\"center\" colspan=\"1\" class=\"hc_table_submodulo_list_title\" > CODIGO \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" class=\"hc_table_submodulo_list_title\" > FIRMA \n";
		$html .= "		</td> \n";
		$html .= "	</tr> \n";

		$html .= "	<tr> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" width=\"50%\" > ".$arrUsRsp[0]['nombre']." \n";
// 		$html .= "    		<input type=\"text\" class=\"input-text\" name=\"nomComplResp\"  value=\"".$arrUsRsp[0]['nombre']."\" > \n";
 		$html .= "		</td> \n";
// 		$html .= "		<td align=\"center\" colspan=\"2\"  > \n";
// 		$html .= "    		<input type=\"text\" class=\"input-text\" name=\"segApellResp\"  value=\"\" > \n";
// 		$html .= "		</td> \n";
// 		$html .= "		<td align=\"center\" colspan=\"2\"  > \n";
// 		$html .= "    		<input type=\"text\" class=\"input-text\" name=\"priNombResp\"  value=\"\" > \n";
// 		$html .= "		</td> \n";
// 		$html .= "		<td align=\"center\" colspan=\"2\"  > \n";
// 		$html .= "    		<input type=\"text\" class=\"input-text\" name=\"segNombResp\"  value=\"\" > \n";
// 		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" width=\"25%\" > ".$arrUsRsp[0]['usuario_id']." \n";
// 		$html .= "    		<input type=\"text\" class=\"input-text\" name=\"codigoResp\" value=\"".$arrUsRsp[0]['usuario_id']."\" > \n";
		$html .= "		</td> \n";
		$html .= "		<td align=\"center\" colspan=\"1\" width=\"25%\" > \n";
		$html .= "    		<input type=\"text\" class=\"input-text\" name=\"firmResp\" value=\"\" > \n";
		$html .= "		</td> \n";
		$html .= "	</tr> \n";	
		$html .= "</table>";
		
		$html .= " <br>";
		
		$html .= "<table align=\"center\" > \n";
		$html .= "		<td align=\"center\" > \n"; 
		$html .= "			<input type=\"button\" class=\"input-submit\" name=\"BtnCrearFichaFam\" value=\"Crear Ficha Familiar\" onclick=\"validarDatosFichaFam()\" > \n";
		$html .= "		</td> \n";
		$html .= "</table> \n";
		$html .= "</form>";
		
		$html .=	"<center>\n
						<div id=\"errorFichaFamili\" class=\"label_error\"></div>\n
					</center> <br>\n";
		
		$html .= ThemeCerrarTablaSubModulo();
		
		$mdl = AutoCarga::factory('MensajesHTML','','hc1','FichaFamiliar');
      	$html .= $mdl->CrearVentana();
		
		return $this->salida = $html;
	}
	
	
	function GetForma(){
		$pfj = $this->frmPrefijo;
		$evento = $_REQUEST['accion'.$pfj];
		
		//if($action){
			//echo entre;
		//}
		
 		if($evento == 'IngFichaFamiliar'){
 			$request = $_REQUEST;
 			$obConn = AutoCarga::factory('FichaFamiliarMetodos','','hc1','FichaFamiliar');
			$valInsFiFam = $obConn->InsertarFichaFamil($request);
			
			$mdl = AutoCarga::factory('MensajesHTML','','hc1','FichaFamiliar');
			
			if($valInsFiFam)
				$mensaje = "LA FICHA FAMILIAR HA SIDO INGRESADA EXITOSAMENTE !";
			else
				$mensaje = "ERROR EN EL INGRESO DE LA FICHA FAMILIAR !";
			
			$action['volver'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array());
			
			return $this->salida = $mdl->fmrMsjIngrFichaFamiliar($action, $mensaje);
			//return $mdl->fmrMsjIngrFichaFamiliar($action, $mensaje);
 		}
		
		//$action['volver'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array());
		return $this->salida = $this->FormaInfoMiembFamilia($pfj);
		//return $verHtml;
	}
	/**
	*
	*/
	function GetConsulta()
	{
		return "";
	}
}  
?>