<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: SolicitudAutorizacionHTML.class.php,v 1.8 2008/11/14 21:27:49 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: SolicitudAutorizacionHTML
  * Clase encargada de crear las formas para el manejo de las solicitudes
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.8 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class SolicitudAutorizacionHTML
  {
    /**
    * Constructor de la clase
    */
    function SolicitudAutorizacionHTML(){}
    /**
    * Funcion donde se crea la forma para hacer la solicitud de los datos del
    * paciente
    *
    * @param array $action Vector con los links de la forma
    * @param array $request Vector con los datos de $_REQUEST
    * @param array $paciente Arreglo con los datos del paciente, si los hay
    * @param array $obliga Arreglo con la informacion de los campos obligatorios
    * @param array $adicionales Arreglo con los datos de informacion que es solicitda 
    *              como por ejempo las zonas de residencia
    * @param array $label Vector con la informacion de aquello que no esta presente
    *              en el arreglo de pacientes, como por ejemplo el nombre del pais, 
    *              el departamento,etc.
    * @return string
    */
    function FormaDatosPaciente($action,$request,$paciente,$obliga,$adicionales,$label)
    {
			$cl = AutoCarga::factory('ClaseUtil');
      
			$valida = "";
			$i = 0;
      $html  = ThemeAbrirTabla('ORDEN DE SERVICIOS MEDICOS');
			$html .= "	<script language='javascript'>";
			$html .= "		function acceptNum(evt)\n";
			$html .= "		{\n";
			$html .= "			var nav4 = window.Event ? true : false;\n";
			$html .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "		}\n";
			$html .= "		function acceptDate(evt)\n";
			$html .= "		{\n";
			$html .= "			var nav4 = window.Event ? true : false;\n";
			$html .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "		}\n";
			$html .= "	</script>";
			$html .= "<center>\n";						
			$html .= "	<fieldset style=\"width:65%\" class=\"fieldset\"><legend>DATOS DEL PACIENTE</legend>\n";
			$html .= "  	<form name=\"forma\" action=\"javascript:EvaluarDatos(document.forma)\" method=\"post\">";
			$html .= "			<input type=\"hidden\" name=\"forma\" value=\"paciente\" class=\"input-text\" >\n";
			$html .= "			<input type=\"hidden\" name=\"zona\" value=\"".$paciente['zona_residencia']."\">\n";
			$html .= "			<input type=\"hidden\" name=\"pais\" value=\"".$paciente['tipo_pais_id']."\">\n";
			$html .= "			<input type=\"hidden\" name=\"dpto\" value=\"".$paciente['tipo_dpto_id']."\">\n";
			$html .= "			<input type=\"hidden\" name=\"mpio\" value=\"".$paciente['tipo_mpio_id']."\">\n";
			$html .= "			<input type=\"hidden\" name=\"rango\" value=\"".$paciente['rango']."\">\n";
			$html .= "			<input type=\"hidden\" name=\"tipo_afiliado_id\" value=\"".$paciente['tipo_afiliado_id']."\">\n";
			$html .= "			<input type=\"hidden\" name=\"semanas_cotizadas\" value=\"".$paciente['semanas_cotizadas']."\">\n";
			$html .= "			<input type=\"hidden\" name=\"estamento_id\" value=\"".$paciente['estamento_id']."\">\n";
			$html .= "			<input type=\"hidden\" name=\"plan_id\" value=\"".$request['plan_id']."\">\n";
			$html .= "			<table width=\"95%\" align=\"center\">\n";

			if($paciente['fecha_registro'])
			{
        $f = explode("-",$paciente['fecha_nacimiento']);
        $html .= "				<tr class=\"label\" height=\"20\">\n";
				$html .= "					<td>FECHA REGISTRO: </td>\n";
				$html .= "					<td class=\"normal_10AN\">".$paciente['fecha_nacimiento']."</td>\n";
				$html .= "				</tr>\n";
			}
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\" width=\"30%\">RESPONSABLE: </td>\n";
			$html .= "			<td>".$label['plan']['plan_descripcion']."</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\">TIPO DOCUMENTO:\n";
			$html .= "			<td>".$label['tipo']['descripcion']."</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\">DOCUMENTO: </td>\n";
			$html .= "			<td >".$request['paciente_id']."</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"label\" height=\"20\">\n";
			
			if($plan['sw_tipo_plan'] == '1' || $obliga['lugar_expedicion_documento']['sw_mostrar'] == 1 )
			{ 
				if($plan['sw_tipo_plan'] == '1') 
				{
					$obliga['lugar_expedicion_documento']['marca'] = "*";
					$obliga['lugar_expedicion_documento']['sw_obligatorio'] = "1";
				}
				
				$html .= "			<td class=\"label\"><label id=\"expedicion\">".$obliga['lugar_expedicion_documento']['marca']." LUGAR EXPEDICION:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "    		<input type=\"text\" name=\"lugar_expedicion_documento\" value=\"".$paciente['lugar_expedicion_documento']."\" class=\"input-text\" size=\"30\" maxlength=\"60\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.lugar_expedicion_documento.value,'".$obliga['lugar_expedicion_documento']['sw_obligatorio'] ."','LUGAR EXPEDICION','expedicion','texto');\n";
			}

			if($obliga['historia_prefijo']['sw_mostrar'] == 1 && $obliga['historia_numero']['sw_mostrar']==1 
				&& $obliga['historia_numero']['sw_obligatorio'] == 1 )
			{
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td ><label id=\"prefijo_historia\" class=\"label\">".$obliga['historia_prefijo']['marca']."PREFIJO:</label></td>\n";
				$html .= "      <td>\n";
				if(!empty($paciente['historia_prefijo']) && is_array($paciente) )
				{
					$html .= "				".$paciente['historia_prefijo']."\n";
					$html .= "				<input type=\"hidden\" name=\"prefijo\" value=\"".$paciente['historia_prefijo']."\">\n";
				}
				else
					$html .= "				<input type=\"text\" maxlength=\"4\" name=\"prefijo\" value=\"".$paciente['historia_prefijo']."\" class=\"input-text\">\n";
				$html .= "			</td>\n";
				$html .= "    </tr>\n";
				
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.prefijo.value,'".$obliga['historia_prefijo']['sw_obligatorio'] ."','PREFIJO','prefijo_historia','texto');\n";
			}

			if($obliga['historia_numero']['sw_mostrar'] == 1)
			{
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"historia_numero\" class=\"label\">".$obliga['historia_numero']['marca']." No. HISTORIA:</label></td>\n";
				$html .= "			<td>\n";
				if(is_array($paciente) AND !empty($paciente['historia_numero']))
				{
					$html .= "			".$paciente['historia_numero']."\n";
					$html .= "			<input type=\"hidden\" name=\"historia\" value=\"".$paciente['historia_numero']."\">\n";
				}
				else
					$html .= "				<input type=\"text\" maxlength=\"50\" name=\"historia\" value=\"".$paciente['historia_numero']."\" class=\"input-text\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.historia.value,'".$obliga['historia_numero']['sw_obligatorio'] ."','No. HISTORIA','historia_numero','texto');\n";
			}

			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.primernombre.value,'1','PRIMER NOMBRE','primer_nombre','texto');\n";

			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td><label id=\"primer_nombre\" class=\"label\">* PRIMER NOMBRE:</label></td>\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"text\" maxlength=\"20\" name=\"primernombre\" value=\"".$paciente['primer_nombre']."\" class=\"input-text\" size=\"30\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			if($obliga['segundo_nombre']['sw_mostrar'] == 1)
			{
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"segundo_nombre\" class=\"label\">".$obliga['segundo_nombre']['marca']." SEGUNDO NOMBRE:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				<input type=\"text\" maxlength=\"20\" name=\"segundonombre\" value=\"".$paciente['segundo_nombre']."\" class=\"input-text\" size=\"30\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.segundonombre.value,'".$obliga['segundo_nombre']['sw_obligatorio'] ."','SEGUNDO NOMBRE','segundonombre','texto');\n";
			}
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td><label id=\"primer_apellido\" class=\"label\">* PRIMER APELLIDO:</label></td>\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"text\" maxlength=\"20\" name=\"primerapellido\" value=\"".$paciente['primer_apellido']."\" class=\"input-text\" size=\"30\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.primerapellido.value,'1','PRIMER APELLIDO','primer_apellido','texto');\n";

			if($obliga['segundo_apellido']['sw_mostrar'] == 1)
			{
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"segundo_apellido\" class=\"label\">".$obliga['segundo_apellido']['marca']." SEGUNDO APELLIDO:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				<input type=\"text\" maxlength=\"20\" name=\"segundoapellido\" value=\"".$paciente['segundo_apellido']."\" class=\"input-text\" size=\"30\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.segundoapellido.value,'".$obliga['segundo_apellido']['sw_obligatorio'] ."','SEGUNDO APELLIDO','segundo_apellido','texto');\n";				
			}
			
      $f = explode("-",$paciente['fecha_nacimiento']);
      if(sizeof($f) == 3)
        $fecha = $f[2]."/".$f[1]."/".$f[0];
      else
        $fecha = $paciente['fecha_nacimiento'];
			
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td><label class=\"label\" id=\"fecha_nacimiento\">* FECHA NACIMIENTO:</label></td>\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"text\" name=\"fechanacimiento\" value=\"".$fecha."\" class=\"input-text\" maxlength=\"12\" onkeyPress=\"return acceptDate(event)\" size=\"14\">\n";
			$html .= "				".ReturnOpenCalendario('forma','fechanacimiento','/')."\n";
			$html .= "			</td>\n";
			$html .= "    </tr>\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fechanacimiento.value,'*','FECHA NACIMIENTO','fecha_nacimiento','date');\n";				

			if($obliga['residencia_direccion']['sw_mostrar'] == 1)
			{
				if($paciente['direccion_residencia']) $paciente['residencia_direccion'] = $paciente['direccion_residencia'];
        
        $html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"residencia_direccion\" class=\"label\">".$obliga['residencia_direccion']['marca']." DIRECCION:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				<input type=\"text\" maxlength=\"60\" name=\"Direccion\" value=\"".$paciente['residencia_direccion']."\" class=\"input-text\" size=\"30\">\n";
				$html .= "			</td>\n";
				$html .= "    </tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.Direccion.value,'".$obliga['residencia_direccion']['sw_obligatorio'] ."','DIRECCION','residencia_direccion','texto');\n";				
			}
			
			if($obliga['lugar_residencia']['sw_mostrar'] == 1 && $obliga['lugar_residencia']['sw_obligatorio'] == 1)
			{
				$url = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$paciente['tipo_pais_id']."&dept=".$paciente['tipo_dpto_id']."&mpio=".$paciente['tipo_mpio_id']."&forma=forma ";
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"lugar_residencia\" class=\"label\">".$obliga['lugar_residencia']['marca']." LUGAR RESIDENCIA:</label></td>\n";
				$html .= "			<td >\n";
				$html .= "				<label id=\"ubicacion\">".$label['nombre_pais']." - ".$label['nombre_departamento']." - ".$label['nombre_municipio']."</label>\n";
				$html .= "				<input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"Cambiar\" target=\"localidad\" onclick=\"window.open('".$url."','localidad','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\"\">\n";
				$html .= "			</td>\n";
				$html .= "    </tr>\n";

				$valida .= "	obligatorios[".($i++)."] = new Array(document.getElementById('ubicacion').innerHTML,'".$obliga['lugar_residencia']['sw_obligatorio'] ."','LUGAR RESIDENCIA','lugar_residencia','texto');\n";				

				if($obliga['tipo_comuna_id']['sw_mostrar'] == 1)
				{					
					$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
					$html .= "			<td><label id=\"tipo_comuna\" class=\"label\">".$obliga['tipo_comuna_id']['marca']." ".strtoupper(ModuloGetVar('app','Pacientes','NombreComuna')).":</label></td>\n";
					$html .= "      <td>\n";
					$html .= "				<input type=\"text\" name=\"ncomuna\" value=\"".$label['nombre_comuna']."\" class=\"input-text\" readonly  style=\"width:60%;background:#FFFFFF\">\n";
					$html .= "      	<input type=\"hidden\" name=\"comuna\" value=\"".$paciente['tipo_comuna_id']."\" class=\"input-text\">\n";
					$html .= "			</td>\n";
					$html .= "    </tr>\n";
					$valida .= "	obligatorios[".($i++)."] = new Array(objeto.comuna.value,'".$obliga['tipo_comuna_id']['sw_obligatorio'] ."','".strtoupper(ModuloGetVar('app','Pacientes','NombreComuna'))."','tipo_comuna','texto');\n";				
				}

				if($obliga['tipo_barrio_id']['sw_mostrar'] == 1 && $obliga['tipo_comuna_id']['sw_obligatorio'] == 1)
				{					
					$html .= "    <tr class=\"normal_10AN\" height=\"20\">\n";
					$html .= "			<td><label id=\"nombre_barrio\" class=\"label\">".$obliga['tipo_barrio_id']['marca']." BARRIO:</label></td>\n";
					$html .= "			<td>\n";
					$html .= "				<input type=\"text\" name=\"nbarrio\" value=\"".$label['nombre_barrio']."\" class=\"input-text\" readonly style=\"width:60%;background:#FFFFFF\">\n";
					$html .= "				<input type=\"hidden\" name=\"barrio\" value=\"".$paciente['tipo_barrio_id']."\" class=\"input-text\">\n";
					$html .= "			</td>\n";
					$html .= "    </tr>\n";
					$valida .= "	obligatorios[".($i++)."] = new Array(objeto.barrio.value,'".$obliga['tipo_barrio_id']['sw_obligatorio'] ."','BARRIO','nombre_barrio','texto');\n";				
				}
			}
			
			if($obliga['tipo_estrato_id']['sw_mostrar'] == 1)
			{
				$html .= "    <tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"tipo_estrato\" class=\"label\"> ".$obliga['tipo_estrato_id']['marca']." ESTRATO:</label></td>\n";
				$html .= "      <td>\n";
				$html .= "				<input type=\"text\" name=\"estrato\" value=\"".trim($paciente['tipo_estrato_id'])."\" class=\"input-text\" maxlength=\"1\" size=\"7\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.estrato.value,'".$obliga['tipo_estrato_id']['sw_obligatorio'] ."','ESTRATO','tipo_estrato','texto');\n";				
			}
			
			if($obliga['residencia_telefono']['sw_mostrar'] == 1)
			{
        if($paciente['telefono_residencia']) $paciente['residencia_telefono'] = $paciente['telefono_residencia'];
        
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"residencia_telefono\" class=\"label\">".$obliga['residencia_telefono']['marca']." TELEFONO:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				<input type=\"text\" maxlength=\"30\" name=\"Telefono\" value=\"".$paciente['residencia_telefono']."\" class=\"input-text\" size=\"30\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.Telefono.value,'".$obliga['residencia_telefono']['sw_obligatorio'] ."','TELEFONO','residencia_telefono','texto');\n";				
			}
			
			if($obliga['nombre_madre']['sw_mostrar'] == 1)
			{
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"nombre_madre\" class=\"label\">".$obliga['nombre_madre']['marca']." NOMBRE MADRE:</label></td>";
				$html .= "      <td>\n";
				$html .= "				<input type=\"text\" maxlength=\"60\" name=\"Mama\" value=\"".$paciente['nombre_madre']."\" class=\"input-text\" size=\"30\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.Mama.value,'".$obliga['nombre_madre']['sw_obligatorio'] ."','NOMBRE MADRE','nombre_madre','texto');\n";				
			}
			
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\">ZONA RESIDENCIA: </td>";
			$html .= "			<td>\n";
      
      $chk = "";
			foreach($adicionales['zonas_residencia'] as $key => $valor)
			{
        ($valor['zona_residencia'] == $paciente['zona_residencia'])? $chk = "checked":$chk = "";
				$html .= "				<input type=\"radio\" name=\"Zona\" value=\"".$valor['zona_residencia']."\" $chk>".$valor['descripcion']."\n";
      }
			$html .= "			</td>\n";
			$html .= "		</tr>\n";

			if($obliga['ocupacion_id']['sw_mostrar'] == 1)
			{
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";//Spente le stelle 
				$html .= "			<td><label id=\"tipo_ocupacion\" class=\"label\">".$obliga['ocupacion_id']['marca']." OCUPACION:</label></td>\n";
				$html .= "      <td>\n";
				$html .= "      	<input type=\"hidden\" name=\"ocupacion_id\" value=\"".$paciente['ocupacion_id']."\">\n";
				$html .= "				<textarea class=\"textarea\"	rows=\"2\" name=\"descripcion_ocupacion\" readonly style=\"width:70%;background:#FFFFFF\"\">".$label['nombre_ocupacion']."</textarea>\n";
				$html .= "				<input type=\"button\" name=\"ocupacion\" value=\"Ocupación\" class=\"input-submit\" onClick=\"javascript:Ocupaciones('forma','')\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.ocupacion.value,'".$obliga['ocupacion_id']['sw_obligatorio'] ."','OCUPACION','tipo_ocupacion','texto');\n";				
			}

			$html .= "    <tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td><label id=\"tipo_genero\" class=\"label\">* SEXO:</label></td>\n";
			$html .= "			<td>\n";
			$html .= "				<select name=\"Sexo\"  class=\"select\">\n";
			$html .= "					<option value=\"-1\">--Seleccione--</option>\n";
			
      foreach($adicionales['tipos_sexo'] as $key => $tp)
			{
				($paciente['sexo_id'] == $tp['sexo_id'] || $paciente['tipo_sexo_id'] == $tp['sexo_id'])? $chk = "selected": $chk = "";
				$html .= "					<option value=\"".$tp['sexo_id']."\" $chk>".$tp['descripcion']."</option>\n";
			}
			
			$html .= "					</select>\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.Sexo.value,'1','SEXO','tipo_genero','texto');\n";				

			if($obliga['talla']['sw_mostrar'] == 1)
			{			
				$html .= "    <tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"talla_metrica\" class=\"label\">".$obliga['talla']['marca']." TALLA:</label></td\n>";
				$html .= "			<td class=\"normal_10AN\">\n";
				$html	.= "				<input type=\"text\" id=\"talla\" name=\"metrica[talla]\" value=\"".trim($paciente['talla'])."\" class=\"input-text\" maxlength=\"5\" size=\"7\" onKeyPress='return acceptNum(event)'> Altura: ".$adicionales['unidad_talla']."\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.talla.value,'".$obliga['talla']['sw_obligatorio'] ."','TALLA','talla_metrica','numerico');\n";				
			}				
			
			if($obliga['peso']['sw_mostrar'] == 1)
			{
				$html .= "    <tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"peso_metrica\" class=\"label\">".$obliga['peso']['marca']." PESO:</label></td>\n";
				$html .= "			<td class=\"normal_10AN\">\n";
				$html .= "				<input type=\"text\" id=\"peso\" name=\"metrica[peso]\" value=\"".trim($paciente['peso'])."\" class=\"input-text\" maxlength=\"5\" size=\"7\" onKeyPress='return acceptNum(event)'> ".$adicionales['unidad_peso']."\n";
				$html .= "			</td>\n";
				$html .= "    </tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.peso.value,'".$obliga['peso']['sw_obligatorio'] ."','PESO','peso_metrica','numerico');\n";				
			}

			if($obliga['tipo_estado_civil_id']['sw_mostrar'] == 1)
			{
				$html .= "    <tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "    	<td><label id=\"estado_civil\" class=\"label\">".$obliga['tipo_estado_civil_id']['marca']." ESTADO CIVIL:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				<select name=\"estadocivil\"  class=\"select\">\n";
				$html .= "					<option value=\"-1\">--Seleccione--</option>\n";
				
				foreach($adicionales['estado_civil'] as $key => $tp)
				{
					($tp['tipo_estado_civil_id'] == $paciente['tipo_estado_civil_id'])? $chk= "selected": $chk = "";
					$html .= "					<option value=\"".$tp['tipo_estado_civil_id']."\" $chk>".$tp['descripcion']."</option>\n";
				}
				$html .= "				</select>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.estadocivil.value,'".$obliga['tipo_estado_civil_id']['sw_obligatorio'] ."','ESTADO CIVIL','estado_civil','texto');\n";				
			}
			if($obliga['observaciones']['sw_mostrar'] == 1)
			{
				$html .= "    	<tr class=\"label\" height=\"20\">\n";
				$html .= "      	<td colspan=\"2\" align=\"center\">\n";
				$html .= "      		<fieldset style=\"width:99%\"><legend><label id=\"observaciones\" class=\"label\">".$obliga['observaciones']['marca']." DATOS ADICIONALES:</label></legend>\n";
				$html .= "						<textarea name=\"Observaciones\" style=\"width:100%\" rows=\"2\" class=\"textarea\">".$paciente['observaciones']."</textarea>\n";
				$html .= "      		</fieldset>\n";
				$html .= "      	</td>\n";
				$html .= "    	</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.Observaciones.value,'".$obliga['observaciones']['sw_obligatorio'] ."','DATOS ADICIONALES','observaciones','texto');\n";				
			}
			$html .= "		</table>\n";
			$html .= "		<div id=\"error\" class=\"label_error\" width=\"80%\"><br></div>\n";
			$html .= "		<table width=\"80%\" align=\"center\">\n";
			$html .= "    	<tr class=\"normal_10AN\" height=\"20\" align=\"center\">\n";
			$html .= "    		<td>\n";
			$html .= "					<input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\">\n";
			$html .= "				</td>\n";
			$html .= "			</form>\n";
			$html .= "  		<form name=\"formacancelar\" action=\"".$action['cancelar']."\" method=\"post\">";
			$html .= "    		<td>\n";
			$html .= "					<input class=\"input-submit\" type=\"submit\" name=\"Cancel\" value=\"Cancelar\">\n";
			$html .= "				</td>\n";
			$html .= "  		</form>\n";
			$html .= "  		</tr>\n";
			$html .= "		</table>\n";
			$html .= "	</fieldset>\n";
			$html .= "</center>\n";

			$html .= $cl->IsNumeric();
			$html .= "<script>\n";
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
			$html .= "		if(arr.length > 3)\n";
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
			$html .= "	function EvaluarDatos(objeto)\n";
			$html .= "	{\n";
			$html .= "		obligatorios = new Array();\n";
			$html .= "		".$valida."\n";
			$html .= "		document.getElementById('fecha_nacimiento').className = 'label';\n";
			$html .= "		for(i=0; i< obligatorios.length; i++)\n";
			$html .= "		{\n";
			$html .= "			if(obligatorios[i][1] == '1')\n";
			$html .= "			{\n";
			$html .= "				if(obligatorios[i][0] == '' || obligatorios[i][0] == '-1')\n";
			$html .= "				{\n";
			$html .= "					document.getElementById(obligatorios[i][3]).className=\"label_error\";\n";
			$html .= "					document.getElementById('error').innerHTML = 'EL CAMPO '+obligatorios[i][2]+' ES OBLIGATORIO';\n";
			$html .= "					return;\n";
			$html .= "				}\n";
			$html .= "				else\n";
			$html .= "				{\n";
			$html .= "					document.getElementById(obligatorios[i][3]).className=\"label\";\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "			if(obligatorios[i][0] != '' )\n";
			$html .= "			{\n";
			$html .= "				if(obligatorios[i][4]== 'numerico' && !IsNumeric(obligatorios[i][0]))\n";
			$html .= "				{\n";
			$html .= "					document.getElementById(obligatorios[i][3]).className=\"label_error\";\n";
			$html .= "					document.getElementById('error').innerHTML = 'EL CAMPO '+obligatorios[i][2]+' TIENE UN FORMATO DE NUMERO INCORRECTO';\n";
			$html .= "					return;\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			if(objeto.edadcalculada.value == '' && objeto.fechanacimiento.value == '')\n";
			$html .= "			{\n";
			$html .= "				document.getElementById('error').innerHTML = 'NO SE HA INGRESADO LA FECHA DE NACIMIENTO O UNA EDAD CALCULADA';\n";
			$html .= "				document.getElementById('fecha_nacimiento').className = 'label_error';\n";
			$html .= "				return;\n";
			$html .= "			}\n";
			$html .= "			if(!IsDate(objeto.fechanacimiento.value) && !IsNumeric(objeto.edadcalculada.value) )\n";
			$html .= "			{\n";
			$html .= "				document.getElementById('fecha_nacimiento').className=\"label_error\";\n";
			$html .= "				document.getElementById('error').innerHTML = 'FORMATO DE FECHA INCORRECTO O NUEMERO INVALIDO EN LA EDAD CALCULADA';\n";
			$html .= "				return;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		catch(error)\n";
			$html .= "		{\n";
			$html .= "			if(!IsDate(objeto.fechanacimiento.value))\n";
			$html .= "			{\n";
			$html .= "				document.getElementById('fecha_nacimiento').className=\"label_error\";\n";
			$html .= "				document.getElementById('error').innerHTML = 'FORMATO DE FECHA INCORRECTO';\n";
			$html .= "				return;\n";
			$html .= "			}\n";
			$html .= "			if(objeto.fechanacimiento.value == '')\n";
			$html .= "			{\n";
			$html .= "				document.getElementById('error').innerHTML = 'NO SE HA INGRESADO LA FECHA DE NACIMIENTO';\n";
			$html .= "				document.getElementById('fecha_nacimiento').className = 'label_error';\n";
			$html .= "				return;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		document.getElementById('error').innerHTML = '<br>';\n";
			$html .= "		objeto.action = '".$action['aceptar']."';\n";
			$html .= "		objeto.submit();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion donde se crea la forma la creacion de la solicitud
    * junto con los cargos y medicamentos que haran parte de la misma
    *
    * @param array $action Vector con los links de la forma
		* @param array $datos Vector con los datos de $_REQUEST
		* @param array $grupos Vector con los datos de los grupos tarifarios
		* @param array $solicitudes Vector con los datos de las solicitudes pendientes
		* @param array $conceptos Vector con los datos de los tipos de conceptos
    * @param array $label Vector con los datos adicionales
    *
		* @return string
		*/
		function FormaDatosSolicitud($action,$datos,$grupos,$conceptos,$solicitudes,$label)
		{				
			$stl = "style=\"text-align:left; text-indent:4pt\"";
			$html  = "<script>\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 48 && key <= 57));\n";
			$html .= "	}\n";
			$html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "	}\n";
      $html .= "	function LimpiarCampos(frm)\n";
			$html .= "	{\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'text': frm[i].value = ''; break;\n";
			$html .= "				case 'textarea': frm[i].value = ''; break;\n";
			$html .= "				case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "	}\n";
			$html.= "		function mOvr(src,clrOver)\n";
			$html.= "		{\n";
			$html.= "			src.style.background = clrOver;\n";
			$html.= "		}\n";
			$html.= "		function mOut(src,clrIn)\n";
			$html.= "		{\n";
			$html.= "			src.style.background = clrIn;\n";
			$html.= "		}\n";
      
			$html .= "	function Buscar(frm,off)\n";
			$html .= "	{\n";
			$html .= "		xajax_BuscarCargos(xajax.getFormValues('buscar'),off);\n";
			$html .= "	}\n";
      $html .= "	function BuscarMedicamentos(uno,off)\n";
			$html .= "	{\n";
			$html .= "		xajax_BuscarMedicamentos(xajax.getFormValues('buscarII'),off);\n";
			$html .= "	}\n";
			$html .= "	function Adicionar(cargo,medicamento,concepto,cantidad)\n";
			$html .= "	{\n";
			$html .= "	  if(cantidad*1 == 0)\n";
			$html .= "	  {\n";
			$html .= "	    if (cargo)\n";
      $html .= "	    {\n";
			$html .= "	      alert('PARA HACER LA SELCCION DEL CARGO SE DEBE INDICAR LA CANTIDAD DEL MISMO\\nPOR FAVOR INDICAR LA CANTIDAD PARA EL CARGO: '+cargo);\n";
			$html .= "	      return;\n";
			$html .= "	    }\n";
      $html .= "	    else if (medicamento)\n";
      $html .= "	    {\n";
			$html .= "	      alert('PARA HACER LA SELCCION DEL MEDICAMENTO SE DEBE INDICAR LA CANTIDAD DEL MISMO\\nPOR FAVOR INDICAR LA CANTIDAD PARA EL MEDICAMENTO: '+medicamento);\n";
			$html .= "	      return;\n";
			$html .= "	    }\n";
			$html .= "	  }\n";
			$html .= "		xajax_Adicionar(cargo,medicamento,concepto,cantidad);\n";
			$html .= "	}\n";
			$html .= "	function Eliminar(cargo,medicamento,concepto)\n";
			$html .= "	{\n";
			$html .= "		xajax_Eliminar(cargo,medicamento,concepto);\n";
			$html .= "	}\n";
			$html .= "	function LimpiarConceptos()\n";
			$html .= "	{\n";
			$html .= "	  LimpiarCampos(document.add_conceptos)\n";
			$html .= "	}\n";
      $html .= "	function Ocultar()\n";
			$html .= "	{\n";
			$html .= "		document.getElementById('buscador').style.display=\"block\";\n";
			$html .= "		document.getElementById('equivalencia').style.display=\"none\";\n";
			$html .= "	}\n";
		  $html .= "  function AdicionarConcepto(frm,identificador)\n";
      $html .= "  {\n";
      $html .= "    err = document.getElementById('error_concepto');\n";
      $html .= "    if(frm.concepto_id.value == '-1')\n";
      $html .= "    {\n";
      $html .= "      err.innerHTML='SE DEBE SELECCIONAR EL CONCEPTO'\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.descripcion_concepto.value == '')\n";
      $html .= "    {\n";
      $html .= "      err.innerHTML='SE DEBE INGRESAR LA DESCRIPCION DEL CONCEPTO'\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    err.innerHTML='';\n";
      $html .= "    xajax_Adicionar('','',xajax.getFormValues(identificador));\n";
      $html .= "  }\n";
      $html .= "  function ValidarDatos()\n";
      $html .= "  {\n";
      $html .= "    xajax_ValidarDatos();\n";
      $html .= "  }\n";
      $html .= "  function ContinuarProcesoSolicitud()\n";
      $html .= "  {\n";
      $html .= "    document.forma.action =\"".$action['aceptar']."\";\n";
      $html .= "    document.forma.submit();\n";
      $html .= "  }\n";
			$html .= "</script>\n";
      
      $sl = "style=\"text-indent:8pt;text-align:left\"";
      
			$html .= ThemeAbrirTabla("CREAR SOLICITUD - ADICIONAR CARGOS Y/O MEDICAMENTOS");
			$html .= "<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "	<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td $sl>PACIENTE</td>\n";
			$html .= "      <td $sl class=\"modulo_list_claro\" >\n";
      $html .= "        ".trim($label['paciente']['tipo_id_paciente']." ".$label['paciente']['paciente_id'])."\n";
      $html .= "      </td>\n";
			$html .= "      <td $sl class=\"modulo_list_claro\" colspan=\"4\">\n";
      $html .= "        ".trim($label['paciente']['primer_nombre']." ".$label['paciente']['segundo_nombre']." ".$label['paciente']['primer_apellido']." ".$label['paciente']['segundo_apellido'])."\n";
      $html .= "      </td>\n";
			$html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td $sl>PLAN</td>\n";
      $html .= "      <td $sl class=\"modulo_list_claro\" colspan=\"5\">\n";
      $html .= "        ".$label['plan_descripcion']." \n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td $sl width=\"17%\">TIPO AFILIADO</td>\n";
      $html .= "      <td $sl width=\"12%\" class=\"modulo_list_claro\">\n";
      $html .= "        ".$label['tipo_afiliado']." \n";
      $html .= "      </td>\n";
      $html .= "      <td $sl width=\"16%\">RANGO</td>\n";
      $html .= "      <td $sl width=\"17%\" class=\"modulo_list_claro\">\n";
      $html .= "        ".$label['rango']." \n";
      $html .= "      </td>\n";
      $html .= "      <td $sl width=\"17%\">ESTAMENTO</td>\n";
      $html .= "      <td $sl width=\"%\" class=\"modulo_list_claro\">\n";
      $html .= "        ".$label['estamento']." \n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if($label['mensaje_plan'])
      {
        $html .= "    <tr class=\"modulo_table_list_title\">\n";
  			$html .= "      <td colspan=\"6\">MENSAJE PLAN</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"modulo_list_claro\">\n";
  			$html .= "      <td colspan=\"6\">".$label['mensaje_plan']."</td>\n";
        $html .= "    </tr>\n";
      }
			$html .= "  </table><br>\n";
			$html .= "	<div id=\"adicionados\" style=\"display:block\"></div>\n";
			$html .= "	<center>\n";
			$html .= "		<div id=\"error\" style=\"width:50%\" class=\"label_error\"></div>\n";
			$html .= "	</center>\n";
			$html .= "	<table align=\"center\" width=\"50%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<div id=\"boton_aceptar\" style=\"display:none\">\n";
			$html .= "					<input type=\"button\" name=\"Aceptar\" value=\"Continuar\" onclick=\"ValidarDatos()\" class=\"input-submit\">\n";
			$html .= "				</div>\n";
			$html .= "			</td>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"submit\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			$html .= "<div id=\"error_adicion\" style=\"width:50%\" class=\"label_error\"></div>\n";
      $html .= "<table width=\"100%\" align=\"center\">\n";
			$html .= "  <tr>\n";
			$html .= "	  <td>\n";
			$html .= "		  <div class=\"tab-pane\" id=\"APD\">\n";
			$html .= "			  <script>	tabPane = new WebFXTabPane( document.getElementById( \"APD\" ),false ); </script>\n";
			if(!empty($solicitudes))
      {
      	$html .= "				<div class=\"tab-page\" id=\"pendientes\">\n";
  			$html .= "				  <h2 class=\"tab\">SOLICITUDES PENDIENTES</h2>\n";
  			$html .= "				  <script>	tabPane.addTabPage( document.getElementById(\"pendientes\")); </script>\n";
        foreach($solicitudes as $key => $numerosolicitud)
        {
          $html1 = "";
          $row = 0;
          foreach($numerosolicitud as $key0 => $tipossolicitudes)
          {
            switch($key0)
            {
              case 'C':
            		$html1 .= "	            <tr class=\"modulo_table_list_title\">\n";
            		$html1 .= "		            <td width=\"15%\">CUPS</td>\n";
            		$html1 .= "		            <td width=\"%\">DESCRIPCION CARGO</td>\n";
            		$html1 .= "		            <td width=\"10%\">NIVEL AUTO.</td>\n";
            		$html1 .= "	            </tr>\n";

                foreach($tipossolicitudes as $key1 => $dtl)
                {
            			$html1 .= "	            <tr class=\"modulo_list_claro\">\n";
            			$html1 .= "		            <td>".$dtl['cargo']."</td>\n";
            			$html1 .= "		            <td align=\"justify\">".$dtl['descripcion']."</td>\n";
            			$html1 .= "		            <td>".$dtl['nivel_autorizador']."</td>\n";
            			$html1 .= "	            </tr>\n";
                }
              break;
              case 'M':
            		$html1 .= "	            <tr class=\"modulo_table_list_title\">\n";
            		$html1 .= "		            <td width=\"15%\">CODIGO</td>\n";
            		$html1 .= "		            <td width=\"%\">DESCRIPCION MEDICAMENTO</td>\n";
            		$html1 .= "		            <td width=\"10%\">NIVEL AUTO.</td>\n";
            		$html1 .= "	            </tr>\n";

                foreach($tipossolicitudes as $key1 => $dtl)
                {
            			$html1 .= "	            <tr class=\"modulo_list_claro\">\n";
            			$html1 .= "		            <td>".$dtl['codigo_producto']."</td>\n";
            			$html1 .= "		            <td align=\"justify\">".$dtl['descripcion_producto']."</td>\n";
            			$html1 .= "		            <td>".$dtl['nivel_autorizador']."</td>\n";
            			$html1 .= "	            </tr>\n";
                }
            		
              break;
              case 'P':
            		$html1 .= "	            <tr class=\"modulo_table_list_title\">\n";
            		$html1 .= "		            <td width=\"15%\">T. CONCEPTO</td>\n";
            		$html1 .= "		            <td width=\"%\">DESCRIPCION CONCEPTO</td>\n";
            		$html1 .= "		            <td width=\"10%\">NIVEL AUTO.</td>\n";
            		$html1 .= "	            </tr>\n";

                foreach($tipossolicitudes as $key1 => $dtl)
                {
            			$html1 .= "	            <tr class=\"modulo_list_claro\">\n";
            			$html1 .= "		            <td>".$dtl['descripcion_concepto']."</td>\n";
            			$html1 .= "		            <td align=\"justify\">".$dtl['descripcion_concepto_adicional']."</td>\n";
            			$html1 .= "		            <td>".$dtl['nivel_autorizador']."</td>\n";
            			$html1 .= "	            </tr>\n";
                }
              break;
            }
          }
          $html .= "			        <table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
          $html .= "				        <tr class=\"formulacion_table_list\">\n";
          $html .= "					        <td width=\"2%\">SOLIC.</td>\n";
          $html .= "					        <td >".$dtl['plan_descripcion']."</td>\n";			
          $html .= "					      </tr>\n";
          $html .= "				        <tr width=\"30%\">\n";
          $html .= "					        <td class=\"formulacion_table_list\">".$key."</td>\n";
          $html .= "					        <td >\n";
          $html .= "                    <table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
          $html .= "					          ".$html1."\n";
          $html .= "                    </table>\n";          
          $html .= "					        </td>\n";			
          $html .= "					      </tr>\n";	
          $html .= "					    </table><br>\n";			

        }
  			$html .= "				</div>\n";
      }
      $html .= "				<div class=\"tab-page\" id=\"buscador_cargos\">\n";
			$html .= "				  <h2 class=\"tab\">ADICION DE CARGOS CUPS</h2>\n";
			$html .= "				  <script>	tabPane.addTabPage( document.getElementById(\"buscador_cargos\")); </script>\n";
			$html .= "          <center>\n";
			$html .= "		        <form name=\"buscar\" id=\"buscar\" method=\"post\">\n";
			$html .= "			        <table align=\"center\" border=\"0\" width=\"85%\" class=\"modulo_table_list\">\n";
			$html .= "				        <tr $stl class=\"modulo_table_list_title\">";
			$html .= "					        <td width=\"20%\">CARGO:</td>\n";
			$html .= "					        <td class=\"modulo_list_claro\" width=\"30%\">\n";
			$html .= "						        <input type=\"text\" class=\"input-text\" name=\"cargo\" style=\"width:90%\">\n";
			$html .= "						        <input type=\"hidden\" name=\"plan_id\" value=\"".$datos['plan_id']."\">\n";
			$html .= "					        </td>\n";			
			$html .= "					        <td width=\"20%\">GRUPOS</td>\n";
			$html .= "					        <td class=\"modulo_list_claro\" width=\"20%\">\n";
			$html .= "						        <select name=\"grupo_tipo_cargo\" class =\"select\">\n";
			$html .= "							        <option value=\"-1\">TODOS</option>\n";
      foreach($grupos as $key => $dtll)
        $html .= "							        <option value=\"".$dtll['grupo_tipo_cargo']."\">".$dtll['descripcion']."</option>\n";
      
			$html .= "						        </select>\n";
			$html .= "					        </td>\n";
			$html .= "				        </tr>\n";
			$html .= "				        <tr $stl class=\"modulo_table_list_title\">";
			$html .= "					        <td >DESCRIPCION:</td>";
			$html .= "					        <td  class=\"modulo_list_claro\" colspan=\"2\">\n";
			$html .= "						        <input type=\"text\" class=\"input-text\" name=\"descripcion\" style=\"width:90%\">\n";
			$html .= "					        </td>\n";
			$html .= "					        <td align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "						        <input class=\"input-submit\" name=\"buscar\" type=\"button\" value=\"Buscar\" onclick=\"Buscar(document.buscar,0)\">&nbsp;&nbsp;&nbsp;\n";
			$html .= "						        <input class=\"input-submit\" name=\"limpiar\" type=\"button\" value=\"Limpiar Campos\" onclick=\"LimpiarCampos(document.buscar)\">\n";
			$html .= "					        </td>\n";
			$html .= "				        </tr>\n";
			$html .= "			        </table>\n";
			$html .= "		        </form>\n";
			$html .= "		        <div id=\"buscador\" style=\"display:block\"></div>\n";
			$html .= "          </center>\n";
			$html .= "				</div>\n";
			$html .= "				<div class=\"tab-page\" id=\"buscador_medicamentos\">\n";
			$html .= "					<h2 class=\"tab\">ADICION DE MEDICAMENTOS</h2>\n";
			$html .= "					<script>	tabPane.addTabPage( document.getElementById(\"buscador_medicamentos\")); </script>\n";
			$html .= "          <center>\n";
      $html .= "		        <form name=\"buscarII\" id=\"buscarII\" method=\"post\">\n";
      $html .= "	            <table align=\"center\" border=\"0\" width=\"85%\" class=\"modulo_table_list\">\n";
      $html .= "		            <tr $stl class=\"modulo_table_list_title\">\n";
      $html .= "			            <td width=\"10%\">CODIGO:</td>\n";
      $html .= "			            <td width=\"20%\" class=\"modulo_list_claro\">\n";
      $html .= "				            <input type=\"text\" class=\"input-text\" style=\"width:90%\" name =\"codigo\" >\n";
      $html .= "			            </td>\n";
      $html .= "			            <td width=\"20%\">PRINCIPIO ACTIVO:</td>";
      $html .= "			            <td width=\"20%\" class=\"modulo_list_claro\" >\n";
      $html .= "				            <input type=\"text\" class=\"input-text\" style=\"width:90%\" name=\"principio_activo\" >\n";
      $html .= "			            </td>\n" ;
      $html .= "			          </tr>\n" ;
			$html .= "				        <tr $stl class=\"modulo_table_list_title\">";
			$html .= "					        <td >DESCRIPCION:</td>";
			$html .= "					        <td  class=\"modulo_list_claro\" colspan=\"2\">\n";
			$html .= "						        <input type=\"text\" class=\"input-text\" name=\"descripcion\" style=\"width:90%\">\n";
			$html .= "					        </td>\n";
			$html .= "					        <td align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "						        <input class=\"input-submit\" name=\"buscar\" type=\"button\" value=\"Buscar\" onclick=\"BuscarMedicamentos(1,'0')\">&nbsp;&nbsp;&nbsp;\n";
			$html .= "						        <input class=\"input-submit\" name=\"limpiar\" type=\"button\" value=\"Limpiar Campos\" onclick=\"LimpiarCampos(document.buscarII)\">\n";
			$html .= "					        </td>\n";
			$html .= "				        </tr>\n";
      $html .= "	            </table>\n";
      $html .= "	          </form>\n";
 			$html .= "		        <div id=\"medicamentos\" style=\"display:block\"></div>\n";
      $html .= "          </center>\n";      
      $html .= "				</div>\n";
      $html .= "				<div class=\"tab-page\" id=\"conceptos\">\n";
			$html .= "				  <h2 class=\"tab\">ADICION DE CONCEPTOS</h2>\n";
			$html .= "				  <script>	tabPane.addTabPage( document.getElementById(\"conceptos\")); </script>\n";
			$html .= "          <center>\n";
			$html .= "            <div id=\"error_concepto\" style=\"width:50%\" class=\"label_error\"></div>\n";
			$html .= "          </center>\n";
      $html .= "          <form name=\"add_conceptos\" id=\"add_conceptos\" action=\"\">\n";
			$html .= "	          <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		          <tr $stl class=\"modulo_table_list_title\">\n";
			$html .= "			          <td>CONCEPTO: </td>\n";
			$html .= "			          <td class=\"modulo_list_claro\">\n";
			$html .= "				          <select name=\"concepto_id\" class=\"select\">\n";
			$html .= "					          <option value=\"-1\">-----SELECCIONAR-----</option>\n";
			foreach($conceptos as $key => $dtl)
				$html .= "					          <option value=\"".$dtl['tipo_concepto_id']."\" >".$dtl['descripcion_concepto']."</option>\n";  
			
			$html .= "				          </select>\n";

			$html .= "			          </td>\n";
			$html .= "		          </tr>\n";
			$html .= "		          <tr class=\"modulo_table_list_title\">\n";
			$html .= "			          <td colspan=\"2\">DESCRIPCION CONCEPTO</td>\n";
			$html .= "		          </tr>\n";
			$html .= "		          <tr class=\"modulo_table_list_title\">\n";
			$html .= "			          <td colspan=\"2\">\n";
			$html .= "				          <textarea style=\"width:100%\" rows=\"2\" class=\"textarea\" name=\"descripcion_concepto\"></textarea>\n";
			$html .= "			          </td>\n";
			$html .= "		          </tr>\n";
			$html .= "		          <tr class=\"modulo_list_claro\">\n";
			$html .= "			          <td colspan=\"2\" align=\"center\">\n";
			$html .= "						      <input class=\"input-submit\" name=\"adicionar\" type=\"button\" value=\"Adicionar\" onClick=\"AdicionarConcepto(document.add_conceptos,'add_conceptos')\">\n";
			$html .= "					      </td>\n";
			$html .= "				      </tr>\n";
			$html .= "            </table>\n";
			$html .= "          </form>\n";
      $html .= "				</div>\n";
			$html .= "			</div>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
      
			return $html;
		}
     /**
    * Funcion donde se crea la forma para la autorizacion de cargos, medicamentos y /o conceptos
    *
    * @param array $action Vector con los links de la forma
		* @param array $datos Vector con los datos de $_REQUEST
		* @param array $solicitudes Vector con los datos de las solicitudes pendientes
		* @param array $conceptos Vector con los datos de los tipos de conceptos
    * @param array $label vector con los datos adicionales
    * @param string $nivel_usuario Cadena con el nivel del autorizador
    *
		* @return string
		*/
		function FormaDatosSolicitudAutorizar($action,$datos,$solicitudes,$label,$nivel_usuario,$cirugia)
		{				
			$stl = "style=\"text-align:left; text-indent:4pt\"";
			$cutl = AutoCarga::factory('ClaseUtil');
      $html .= $cutl->AcceptNum(true);
      $html .= $cutl->RollOverFilas();
      $html .= $cutl->IsNumeric();
      
      $html .= "<script>\n";
      $html .= "	function ProveedorCargos(grupo)\n";
			$html .= "	{\n";
			$html .= "	  j = 0;\n";
			$html .= "	  i = 0;\n";
			$html .= "	  valores = '';\n";
			$html .= "	  try\n";
			$html .= "	  {\n";
			$html .= "	    while(true)\n";
			$html .= "	    {\n";
			$html .= "	      elemento = document.getElementById('cargos_'+grupo+'_'+i);\n";
			$html .= "	      provee = document.getElementById('proveedor_'+grupo+'_'+i);\n";
			$html .= "	      cantidad = document.getElementById('cantidad_'+grupo+'_'+i);\n";
			$html .= "	      solicitud = document.getElementById('solicitud_id_'+grupo+'_'+i);\n";
			$html .= "	      if(elemento.checked == true)\n";
			$html .= "	      {\n";
			$html .= "	        valores += \"&cargos[cargo][\"+elemento.value+\"]=\"+elemento.value;\n";
			$html .= "	        valores += \"&cargos[solicitud][\"+elemento.value+\"]=\"+solicitud.value;\n";
			$html .= "	        valores += \"&cargos[cantidad][\"+elemento.value+\"]=\"+cantidad.value;\n";
 			$html .= "	        if(provee.value != '') \n";
			$html .= "	          valores += \"&cargos[proveedor][\"+provee.value+\"][\"+elemento.value+\"]=\"+provee.value;\n";
			$html .= "	      }\n";
			$html .= "	      i++;\n";
			$html .= "	    }\n";
			$html .= "	  }\n";
			$html .= "	  catch(error){ }\n";
			$html .= "	  if(valores == '')\n";
			$html .= "	  {\n";
			$html .= "	    alert('NO SE HA SELECCIONADO NINGUN CARGO, PARA ELEGIR EL PROVEEDOR')\n";
			$html .= "	    return;\n";
			$html .= "	  }\n";
			$html .= "	  valores += '&grupo_tipo_cargo='+grupo;\n";
      $html .= "	  url = \"".$action['cargos']."\"+valores;\n";
			$html .= "	  AbrirVentana(url);\n";
			$html .= "	}\n";
      $html .= "	function ProveedorMedicamentos()\n";
			$html .= "	{\n";
			$html .= "	  j = 0;\n";
			$html .= "	  i = 0;\n";
			$html .= "	  valores = '';\n";
			$html .= "	  try\n";
			$html .= "	  {\n";
			$html .= "	    while(true)\n";
			$html .= "	    {\n";
			$html .= "	      elemento = document.getElementById('medicamento_'+i);\n";
			$html .= "	      provee = document.getElementById('proveedorm_'+i);\n";
			$html .= "	      if(elemento.checked == true)\n";
			$html .= "	      {\n";
			$html .= "	        valores += \"&productos[producto][\"+elemento.value+\"]=\"+elemento.value;\n";
 			$html .= "	        if(provee.value != '') \n";
			$html .= "	          valores += \"&productos[proveedor][\"+provee.value+\"][\"+elemento.value+\"]=\"+provee.value;\n";
			$html .= "	      }\n";
			$html .= "	      i++;\n";
			$html .= "	    }\n";
			$html .= "	  }\n";
			$html .= "	  catch(error){ }\n";
			$html .= "	  if(valores == '')\n";
			$html .= "	  {\n";
			$html .= "	    alert('NO SE HA SELECCIONADO NINGUN MEDICAMENTO, PARA ELEGIR EL PROVEEDOR')\n";
			$html .= "	    return;\n";
			$html .= "	  }\n";
			$html .= "	  url = \"".$action['medicamentos']."\"+valores;\n";      
			$html .= "	  AbrirVentana(url);\n";
			$html .= "	}\n";
      
      $html .= "	function ProveedorConceptos()\n";
			$html .= "	{\n";
			$html .= "	  j = 0;\n";
			$html .= "	  i = 0;\n";
			$html .= "	  valores = '';\n";
			$html .= "	  try\n";
			$html .= "	  {\n";
			$html .= "	    while(true)\n";
			$html .= "	    {\n";
			$html .= "	      elemento = document.getElementById('concepto_'+i);\n";
			$html .= "	      provee = document.getElementById('proveedorp_'+i);\n";
			$html .= "	      if(elemento.checked == true)\n";
			$html .= "	      {\n";
			$html .= "	        valores += \"&conceptos[concepto][\"+elemento.value+\"]=\"+elemento.value;\n";
 			$html .= "	        if(provee.value != '') \n";
			$html .= "	          valores += \"&conceptos[proveedor][\"+provee.value+\"][\"+elemento.value+\"]=\"+provee.value;\n";
			$html .= "	      }\n";
			$html .= "	      i++;\n";
			$html .= "	    }\n";
			$html .= "	  }\n";
			$html .= "	  catch(error){ }\n";
			$html .= "	  if(valores == '')\n";
			$html .= "	  {\n";
			$html .= "	    alert('NO SE HA SELECCIONADO NINGUN MEDICAMENTO, PARA ELEGIR EL PROVEEDOR')\n";
			$html .= "	    return;\n";
			$html .= "	  }\n";
			$html .= "	  url = \"".$action['conceptos']."\"+valores;\n";      
			$html .= "	  AbrirVentana(url);\n";
			$html .= "	}\n";
      
			$html .= "	function AbrirVentana(url)\n";
			$html .= "	{\n";
			$html .= "		window.open(url,'proveedores','toolbar=no,width=700,height=400,resizable=no,scrollbars=yes').focus();\n";
			$html .= "	}\n";
			$html .= "	function EvaluarDatos(frm)\n";
			$html .= "	{\n";
      $html .= "    var continuar = false;\n";
      $html .= "    var provee = \"\";\n";
      $html .= "    error = document.getElementById('errorII');\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "		  if(frm[i].type == 'checkbox')\n";
			$html .= "		  {\n";
      $html .= "        if(frm[i].checked)\n";
      $html .= "        { \n";			
      $html .= "			    switch(frm[i].name.split('[')[0])\n";
			$html .= "			    {\n";
			$html .= "		        case 'cargos':\n";
			$html .= "		          sol = frm[i].name.split('[')[1].split(']')[0];\n";
      $html .= "              if(document.getElementsByName('cargos['+sol+']['+frm[i].value+'][proveedor]')[0].value == '')\n";
      $html .= "              {\n";
      $html .= "                error.innerHTML = 'PARA EL CARGO '+frm[i].value+', NO SE HA SELECCIONADO EL PROVEEDOR';\n";
      $html .= "                return;\n";
      $html .= "              }\n";
      $html .= "              else\n";
      $html .= "                provee += '&proveedores['+document.getElementsByName('cargos['+sol+']['+frm[i].value+'][proveedor]')[0].value+']=1';\n";
      $html .= "              continuar = true;\n";
      $html .= "            break;\n";
			$html .= "		        case 'medicamento':\n";
      $html .= "              if(!IsNumeric(document.getElementsByName('medicamento['+frm[i].value+'][cantidad]')[0].value))\n";
      $html .= "              {\n";
      $html .= "                error.innerHTML = 'LA CANTIDAD INGRESADA PARA EL MEDICAMENTO CON CODIGO '+frm[i].value+', ES INCORRECTA O POSEE UN FORMATO INVALIDO';\n";
      $html .= "                return;\n";
      $html .= "              }\n";
      $html .= "              if(document.getElementsByName('medicamento['+frm[i].value+'][proveedor]')[0].value == '')\n";
      $html .= "              {\n";
      $html .= "                error.innerHTML = 'PARA EL MEDICAMENTO CON CODIGO '+frm[i].value+', NO SE HA SELECCIONADO EL PROVEEDOR';\n";
      $html .= "                return;\n";
      $html .= "              }\n";
      $html .= "              else\n";
      $html .= "                provee += '&proveedores['+document.getElementsByName('medicamento['+frm[i].value+'][proveedor]')[0].value+']=1';\n";
      $html .= "              continuar = true;\n";
      $html .= "            break;\n";
      $html .= "		        case 'conceptos':\n";
      $html .= "              if(!IsNumeric(document.getElementsByName('conceptos['+frm[i].value+'][valor]')[0].value))\n";
      $html .= "              {\n";
      $html .= "                error.innerHTML = 'EL VALOR INGRESADO PARA EL CONCEPTO Nº '+frm[i].value+', ES INCORRECT0 O POSEE UN FORMATO INVALIDO';\n";
      $html .= "                return;\n";
      $html .= "              }\n";
      $html .= "              if(document.getElementsByName('conceptos['+frm[i].value+'][proveedor]')[0].value == '')\n";
      $html .= "              {\n";
      $html .= "                error.innerHTML = 'PARA EL CONCEPTO Nº '+frm[i].value+', NO SE HA SELECCIONADO EL PROVEEDOR';\n";
      $html .= "                return;\n";
      $html .= "              }\n";
      $html .= "              else\n";
      $html .= "                provee += '&proveedores['+document.getElementsByName('conceptos['+frm[i].value+'][proveedor]')[0].value+']=1';\n";
      $html .= "              continuar = true;\n";
      $html .= "            break;\n";
      $html .= "          }\n";  		
      $html .= "			  }\n";
			$html .= "		  }\n";
			$html .= "	  }\n";
      $html .= "		if(continuar == true)\n";
			$html .= "		{\n";
 			$html .= "	    document.forma.action = \"".$action['aceptar']."\"+provee;\n";      
			$html .= "		  document.forma.submit();\n";
			$html .= "		}\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "		  error.innerHTML = 'NO SE HA SELECCIONADO NINGUN CARGO, CONCEPTO O MEDICAMENTO PARA SER AUTORIZADO';\n";
			$html .= "		}\n";
			$html .= "  }\n";
      $html .= "	function Recargar()\n";
			$html .= "	{\n";
			$html .= "	  document.location=\"".$action['recargar']."\";\n";
			$html .= "	}\n";
			$html .= "</script>\n";
      $sl = "style=\"text-indent:8pt;text-align:left\"";
      $html .= $this->CrearVentana();
			$html .= ThemeAbrirTabla("CREAR ORDEN DE SERVICIO");
			$html .= "<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "	<table width=\"72%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td $sl>PACIENTE</td>\n";
			$html .= "      <td $sl class=\"modulo_list_claro\" >\n";
      $html .= "        ".trim($label['paciente']['tipo_id_paciente']." ".$label['paciente']['paciente_id'])."\n";
      $html .= "      </td>\n";
			$html .= "      <td $sl class=\"modulo_list_claro\" colspan=\"4\">\n";
      $html .= "        ".trim($label['paciente']['primer_nombre']." ".$label['paciente']['segundo_nombre']." ".$label['paciente']['primer_apellido']." ".$label['paciente']['segundo_apellido'])."\n";
      $html .= "      </td>\n";
			$html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td $sl>PLAN</td>\n";
      $html .= "      <td $sl class=\"modulo_list_claro\" colspan=\"5\">\n";
      $html .= "        ".$label['plan_descripcion']." \n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td $sl width=\"17%\">TIPO AFILIADO</td>\n";
      $html .= "      <td $sl width=\"16%\" class=\"modulo_list_claro\">\n";
      $html .= "        ".$label['tipo_afiliado']." \n";
      $html .= "      </td>\n";
      $html .= "      <td $sl width=\"16%\">RANGO</td>\n";
      $html .= "      <td $sl width=\"17%\" class=\"modulo_list_claro\">\n";
      $html .= "        ".$label['rango']." \n";
      $html .= "      </td>\n";
      $html .= "      <td $sl width=\"17%\">ESTAMENTO</td>\n";
      $html .= "      <td $sl width=\"17%\" class=\"modulo_list_claro\">\n";
      $html .= "        ".$label['estamento']." \n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if($label['mensaje_plan'])
      {
        $html .= "    <tr class=\"modulo_table_list_title\">\n";
  			$html .= "      <td colspan=\"6\">MENSAJE PLAN</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"modulo_list_claro\">\n";
  			$html .= "      <td colspan=\"6\">".$label['mensaje_plan']."</td>\n";
        $html .= "    </tr>\n";
      }
			$html .= "	<table width=\"72%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";
			$html .= "      <td>OSERVACION A LA ORDEN</td>\n";
			$html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
			$html .= "      <td>\n";
      $html .= "        <textarea style=\"width:100%\" rows=\"2\" name=\"observacion_orden\"></textarea>\n";
      $html .= "      </td>\n";
			$html .= "    </tr>\n";
			$html .= "  </table><br>\n";
			$html .= "	<div id=\"adicionados\" style=\"display:block\"></div>\n";
			$html .= "	<center>\n";
			$html .= "		<div id=\"error\" style=\"width:50%\" class=\"label_error\"></div>\n";
			$html .= "	</center>\n";
			$html .= "<div id=\"error\" style=\"width:50%\" class=\"label_error\"></div>\n";
      
      if(!empty($cirugia))
      {
        $html .= "<table width=\"60%\" align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
        $html .= "      <fieldset class=\"fieldset\">\n";
        $html .= "        <legend class=\"normal_10AN\">SOLICITUDES DE CIRUGIA</legend>\n";
        $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "          <tr class=\"formulacion_table_list\">\n";
        $html .= "	          <td width=\"15%\">GRUPO</td>\n";
        $html .= "		        <td width=\"8%\">CODIGO</td>\n";
        $html .= "		        <td >DESCRIPCION</td>\n";
        $html .= "		        <td width=\"2%\">OP</td>\n";
        $html .= "		      <tr>\n";
        foreach($cirugia as $key => $detalle)
        {
          $html .= "          <tr class=\"modulo_list_claro\">\n";
          $html .= "	          <td >".$detalle['grupo_cargo_descripcion']."</td>\n";
          $html .= "		        <td >".$detalle['cargo']."</td>\n";
          $html .= "		        <td align=\"justify\">".$detalle['descripcion']."</td>\n";
          $html .= "		        <td >\n";
          $html .= "              <a href=\"#\" onclick=\"xajax_SeleccionarItems('".$detalle['numero_solicitud_orden']."','".$detalle['cargo']."','".$datos['plan_id']."')\">\n";
          $html .= "                <img src=\"".GetThemePath()."/images/cargos.png\" border=\"0\">\n";
          $html .= "              </a>\n";
          $html .= "            </td>\n";
          $html .= "		      <tr>\n";
        }
        $html .= "        </table>\n";
        $html .= "      </fieldset>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
      }
      
      if(!empty($solicitudes['CARGOS']))
      {
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend class=\"normal_10AN\">CARGOS</legend>\n";
        $nvl = 0;
        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "	  <td width=\"15%\">GRUPO</td>\n";
        $html .= "		<td width=\"8%\">CODIGO</td>\n";
        $html .= "		<td >DESCRIPCION</td>\n";
        $html .= "		<td width=\"5%\">SOL</td>\n";
        $html .= "		<td width=\"8%\">FECHA</td>\n";
        $html .= "		<td width=\"3%\">NV</td>\n";
        $html .= "		<td width=\"3%\">CANT</td>\n";
        $html .= "		<td width=\"6%\" colspan=\"2\">OP</td>\n";
        $html .= "	</tr>\n";
                
        foreach($solicitudes['CARGOS'] as $key => $dtll)
        {
          $x = 0;
          $html1 = "";
          $variable = 0;
          foreach($dtll as $key1 => $detalle)
          {
            foreach($detalle as $keyR => $dtll)
            {            
              $y = 0;
              foreach($dtll as $key2 => $dtl)
              {
                $est = "modulo_list_oscuro";
                $nvl = $dtl['nivel_autorizador_id'];
                if($x == 0)
                {
                  if($keyR)
                  {
                    $html1 .= " <tr class=\"formulacion_table_list\">\n";
                    $html1 .= "	  <td class=\"formulacion_table_list\" rowspan=\"_x_\">".$key."</td>\n";
                    $html1 .= "   <td colspan=\"8\">".$keyR."</td>\n";
                    $html1 .= " </tr>\n";
                    $html1 .= " <tr class=\"$est\">\n";
                    $x++;
                  }
                  else
                  {
                    $html1 .= " <tr class=\"$est\">\n";
                    $html1 .= "	  <td class=\"formulacion_table_list\" rowspan=\"_x_\">".$key."</td>\n";
                  }
                }
                $html1 .= "		<td>".$dtl['cargo']."</td>\n";
                $html1 .= "		<td align=\"justify\">".$dtl['descripcion']."</td>\n";
                $html1 .= "		<td>".$dtl['numero_solicitud_orden']."</td>\n";
                $html1 .= "		<td align=\"center\">".$dtl['fecha_registro']."</td>\n";
                $html1 .= "	  <td>".$nvl."</td>\n";
                $html1 .= "	  <td>".$dtl['cantidad']."</td>\n";
                $html1 .= "	  <td align=\"center\" >\n";
                if($dtl['nivel_autorizador'] == 0)
                {
                  $html1 .= "     <input type=\"checkbox\" id=\"cargos_".$dtl['grupo_tipo_cargo']."_".$y."\" name=\"cargos[".$dtl['eps_solicitud_orden_cargo_id']."][".$dtl['cargo']."][cargo]\" value=\"".$dtl['cargo']."\">\n";
                  $html1 .= "     <input type=\"hidden\" id=\"proveedor_".$dtl['grupo_tipo_cargo']."_".$y."\" name=\"cargos[".$dtl['eps_solicitud_orden_cargo_id']."][".$dtl['cargo']."][proveedor]\" value=\"\">\n";
                  $html1 .= "     <input type=\"hidden\" id=\"cantidad_".$dtl['grupo_tipo_cargo']."_".$y."\" name=\"cargos[".$dtl['eps_solicitud_orden_cargo_id']."][".$dtl['cargo']."][cantidad]\" value=\"".$dtl['cantidad']."\">\n";
                  $html1 .= "     <input type=\"hidden\" id=\"solicitud_id_".$dtl['grupo_tipo_cargo']."_".$y."\" name=\"cargos[".$dtl['eps_solicitud_orden_cargo_id']."][".$dtl['cargo']."][solicitud_id]\" value=\"".$dtl['eps_solicitud_orden_cargo_id']."\">\n";
                }
                $html1 .= "   </td>\n";

                if($y == 0)
                {
                  $html1 .= "	  <td align=\"center\" rowspan=\"_y_\">\n";
                  if($dtl['nivel_autorizador'] == 0)
                  {
                    $html1 .= "     <a href=\"javascript:ProveedorCargos('".$dtl['grupo_tipo_cargo']."')\">\n";
                    $html1 .= "       <img src=\"".GetThemePath()."/images/proveedor.png\" border=\"0\">\n";
                    $html1 .= "     </a>\n";
                  }
                  $html1 .= "   </td>\n";
                }
                $html1 .= " </tr>\n";
                $x++;
                $y++;
              }
              $html1 = str_replace("_y_",$y,$html1);
            }
          }
          $html .= str_replace("_x_",$x,$html1);
        }
        $html .= " </table>\n";        
        $html .= "</fieldset><br>\n";
      }
      
      if(!empty($solicitudes['MEDICAMENTOS']))
      {
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend class=\"normal_10AN\">MEDICAMENTOS</legend>\n";
        $nvl = 0;
        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "	  <td width=\"15%\">GRUPO</td>\n";
        $html .= "		<td width=\"8%\">CODIGO</td>\n";
        $html .= "		<td >DESCRIPCION</td>\n";
        $html .= "		<td width=\"5%\">SOL</td>\n";
        $html .= "		<td width=\"8%\">FECHA</td>\n";
        $html .= "		<td width=\"3%\">NV</td>\n";
        $html .= "		<td width=\"3%\">CANT</td>\n";
        $html .= "		<td width=\"8%\" colspan=\"2\">OP</td>\n";
        $html .= "	</tr>\n";

        foreach($solicitudes['MEDICAMENTOS'] as $key => $dtll)
        {
          $x = 0;
          $html1 = "";
          foreach($dtll as $key1 => $detalle)
          {
            $y = 0;
            foreach($detalle as $key2 => $dtl)
            {
              $est = "modulo_list_oscuro";
              $nvl = $dtl['nivel_autorizador'];
              if($dtl['nivel_autorizador'] == 0)
              {
                $nvl = $nivel_usuario;
                $est = "modulo_list_claro";
              }          
              $html1 .= " <tr class=\"$est\">\n";
              if($x == 0)
                $html1 .= "	  <td class=\"formulacion_table_list\" rowspan=\"_x_\">PRODUCTOS</td>\n";
              
              $html1 .= "		<td>".$dtl['codigo_producto']."</td>\n";
              $html1 .= "		<td align=\"justify\">".$dtl['descripcion_producto']."</td>\n";
              $html1 .= "		<td>".$dtl['numero_solicitud_orden']."</td>\n";
              $html1 .= "		<td align=\"center\">".$dtl['fecha_registro']."</td>\n";
              $html1 .= "	  <td>".$nvl."</td>\n";
              $html1 .= "	  <td>\n";
              $html1 .= "     ".$dtl['cantidad']."";
              $html1 .= "     <input type=\"hidden\" value=\"".$dtl['cantidad']."\" name=\"medicamento[".$dtl['codigo_producto']."][cantidad]\">\n";
              $html1 .= "   </td>\n";
              $html1 .= "	  <td align=\"center\" >\n";
              if($dtl['nivel_autorizador'] == 0)
              {
                $html1 .= "     <input type=\"checkbox\" id=\"medicamento_".$y."\" name=\"medicamento[".$dtl['codigo_producto']."][producto]\" value=\"".$dtl['codigo_producto']."\">\n";
                $html1 .= "     <input type=\"hidden\" id=\"proveedorm_".$y."\" name=\"medicamento[".$dtl['codigo_producto']."][proveedor]\" value=\"\">\n";
              }
              $html1 .= "   </td>\n";

              if($y == 0)
              {
                $html1 .= "	  <td align=\"center\" rowspan=\"_y_\">\n";
                if($dtl['nivel_autorizador'] == 0)
                {
                  $html1 .= "     <a href=\"javascript:ProveedorMedicamentos()\">\n";
                  $html1 .= "       <img src=\"".GetThemePath()."/images/proveedor.png\" border=\"0\">\n";
                  $html1 .= "     </a>\n";
                }
                $html1 .= "   </td>\n";
              }
              $html1 .= " </tr>\n";
              $x++;
              $y++;
            }
            $html1 = str_replace("_y_",$y,$html1);
          }
          $html .= str_replace("_x_",$x,$html1);
        }
        $html .= " </table>\n";        
        $html .= "</fieldset><br>\n";
      }
      
      if(!empty($solicitudes['CONCEPTOS']))
      {
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend class=\"normal_10AN\">CONCEPTOS</legend>\n";
        $nvl = 0;
        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "	  <td width=\"15%\">TIPO CONCEPTO</td>\n";
        $html .= "	  <td width=\"8%\">CONCEPTO</td>\n";
        $html .= "		<td >DESCRIPCION</td>\n";
        $html .= "		<td width=\"8%\">VALOR</td>\n";
        $html .= "		<td width=\"5%\">SOL</td>\n";
        $html .= "		<td width=\"8%\">FECHA</td>\n";
        $html .= "		<td width=\"3%\">NV</td>\n";
        $html .= "		<td width=\"8%\" colspan=\"2\">OP</td>\n";
        $html .= "	</tr>\n";
        $y = 0;
        foreach($solicitudes['CONCEPTOS'] as $key => $dtll)
        {
          $x = 0;
          $html1 = "";
          foreach($dtll as $key1 => $detalle)
          {
            foreach($detalle as $key2 => $dtl)
            {
              $est = "modulo_list_oscuro";
              $nvl = $dtl['nivel_autorizador'];
              if($dtl['nivel_autorizador'] == 0)
              {
                $nvl = $nivel_usuario;
                $est = "modulo_list_claro";
              }          
              $html1 .= " <tr class=\"$est\">\n";
              if($x == 0)
                $html1 .= "	  <td class=\"formulacion_table_list\" rowspan=\"_x_\">".$key."</td>\n";
              
              $html1 .= "		<td >".$dtl['eps_solicitud_orden_concepto']."</td>\n";
              $html1 .= "		<td align=\"justify\">".$dtl['descripcion_concepto_adicional']."</td>\n";
              $html1 .= "		<td>\n";
              if($dtl['nivel_autorizador'] == 0)
                $html1 .= "     $<input type=\"text\" class=\"input-text\" style=\"width:85%\" value=\"\" onkeypress=\"return acceptNum(event)\" name=\"conceptos[".$dtl['eps_solicitud_orden_concepto']."][valor]\">\n";
              
              $html1 .= "   </td>\n";
              $html1 .= "		<td>".$dtl['numero_solicitud_orden']."</td>\n";
              $html1 .= "		<td align=\"center\">".$dtl['fecha_registro']."</td>\n";
              $html1 .= "	  <td>".$nvl."</td>\n";
              $html1 .= "	  <td align=\"center\" >\n";
              if($dtl['nivel_autorizador'] == 0)
              {
                $html1 .= "     <input type=\"checkbox\" id=\"concepto_".$y."\" name=\"conceptos[".$dtl['eps_solicitud_orden_concepto']."][concepto]\" value=\"".$dtl['eps_solicitud_orden_concepto']."\">\n";
                $html1 .= "     <input type=\"hidden\" id=\"proveedorp_".$y."\" name=\"conceptos[".$dtl['eps_solicitud_orden_concepto']."][proveedor]\" value=\"\">\n";
              }
              $html1 .= "   </td>\n";

              if($y == 0)
              {
                $html1 .= "	  <td align=\"center\" rowspan=\"_y_\">\n";
                if($dtl['nivel_autorizador'] == 0)
                {
                  $html1 .= "     <a href=\"javascript:ProveedorConceptos()\">\n";
                  $html1 .= "       <img src=\"".GetThemePath()."/images/proveedor.png\" border=\"0\">\n";
                  $html1 .= "     </a>\n";
                }
                $html1 .= "   </td>\n";
              }
              $html1 .= " </tr>\n";
              $x++;
              $y++;
            }
          }
          $html .= str_replace("_x_",$x,$html1);
        }
        $html  = str_replace("_y_",$y,$html);
        $html .= " </table>\n";        
        $html .= "</fieldset>\n";
      }
      $html .= "  <center>\n";
      $html .= "    <div id=\"errorII\" style=\"width:50%\" class=\"label_error\"></div>\n";
      $html .= "  </center>\n";
      $html .= "	<table align=\"center\" width=\"50%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"button\" name=\"Aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatos(document.forma)\" class=\"input-submit\">\n";
			$html .= "			</td>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"submit\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
  		$html .= "</form>\n";	
			$html .= ThemeCerrarTabla();
      
			return $html;
		}
    /**
		* Funcion donde se crea una forma con una ventana con capas para mostrar informacion
    * en pantalle
    *
    * @param string $funcion Funcion a la que se llama cuando se hace submit sobre la forma
    * @param int $tmn Tamaño que tendra la ventana
    *
    * @return string
		*/
		function CrearVentana($tmn = 350)
		{
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 4;\n";
			$html .= "	function OcultarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('ContenedorP');\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan(longx,longy)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('ContenedorP');\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		  Iniciar(longx,longy);\n";
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

			$html .= "	function Iniciar(longx,longy)\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'ContenedorP';\n";
			$html .= "		titulo = 'tituloP';\n";
      $html .= "		xGetElementById('error_p').innerHTNL = '';\n";
      $html .= "		ele = xGetElementById('ContenidoP');\n";
			$html .= "	  xResizeTo(ele,longx, longy-20);\n";	
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,longx, longy);\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,longx-20, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrarP');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,longx-20, 0);\n";
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
			$html .= "	<div id='tituloP' class='draggable' style=\"	text-transform: uppercase;text-align:center;\"></div>\n";
			$html .= "	<div id='cerrarP' class='draggable' ><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "	<div id='ContenidoP' class='d2Content' style=\"background:#EFEFEF\">\n";
			$html .= "	  <form name=\"oculta\" id=\"oculta\" method=\"post\">\n";
			$html .= "	    <div id='error_p' style=\"text-align:center\" class=\"label_error\"></div>\n";
			$html .= "	    <div id='ventana'></div>\n";
			$html .= "	  </form>\n";
			$html .= "	</div>\n";			
      $html .= "</div>\n";

			return $html;
		}
  }
?>