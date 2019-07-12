<?php
	/**************************************************************************************
	* $Id: PacientesHTML.class.php,v 1.1 2009/11/10 19:33:17 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F  Manrique
	***************************************************************************************/
	class PacientesHTML
	{
		function PacientesHTML(){}
		/**********************************************************************************
		* 
		************************************************************************************/
		function FormaPedirDatosPaciente($datos,$afiliado,$action,&$obj,$afiliacion)
		{	
			$html = "";
			IncludeClass('ClaseUtil');
			
			if($datos['tipo_id_paciente'] == 'AS' || $datos['tipo_id_paciente'] == 'MS')
				$html = $this->FormaDatosNN($datos,$afiliado,$action);
			else
				$html = $this->FormaDatosPaciente($datos, $afiliado, $action,$afiliacion);
			
			return $html;
		}
		/**********************************************************************************
		*@acess private 
		***********************************************************************************/
		function FormaDatosAfiliado($cotizante,$plan,$paciente,$afiliacion)
	  {
   
			$sel = "";
			$tipos = $paciente->ObtenerTiposAfiliados($plan,($afiliacion == '1')? $cotizante['tipo_afiliado']:"");
    
			$rangos = $paciente->ObtenerRangosNiveles($plan,($afiliacion == '1')? $cotizante['rango']:"");
			$semanas = $cotizante['semanas_cotizadas'];
			if(!$semanas) $semanas = 0;
			
			$html .= "<table width=\"100%\" align=\"center\">\n";
			$html .= "	<tr >\n";
			$html .= "		<td class=\"label\" width=\"20%\">TIPO AFILIADO: </td>\n";
			$html .= "		<td class=\"normal_10AN\" width=\"20%\">\n";
			if(sizeof($tipos) > 1)
			{
				$html .= "			<select name=\"tipoafiliado\" class=\"select\">\n";
				$html .= "				<option value=\"-1\">-- Seleccionar --</option>\n";
				foreach($tipos as $key => $valor)
				{
					($cotizante['tipo_afiliado'] == $valor['tipo_afiliado_id'])? $sel = "selected":$sel = "";
					$html .= "				<option value=\"".$valor['tipo_afiliado_id']."\" $sel>".$valor['tipo_afiliado_nombre']."</option>\n";
				}
				$html .= "			</select>\n";
			}
			else
			{
				$html .= "			".$tipos[0]['tipo_afiliado_nombre']."\n";
				$html .= "			<input type=\"hidden\" name=\"tipoafiliado\" value=\"".$tipos[0]['tipo_afiliado_id']."\">\n";
			}
      
			$html .= "		</td>\n";
			$html .= "		<td class=\"label\">RANGO:</td>\n";
			$html .= "		<td class=\"normal_10AN\">\n";
			if(sizeof($rangos) > 1)
			{
				$html .= "			<select name=\"rango\" class=\"select\">\n";
				$html .= "				<option value=\"\">-- Selecc --</option>\n";
				foreach($rangos as $key => $valor)
				{
					($cotizante['rango'] == $valor['rango'])? $sel = "selected":$sel = "";
					$html .= "				<option value=\"".$valor['rango']."\" $sel>".$valor['rango']."</option>\n";
				}
				$html .= "			</select>\n";
			}
			else
			{
				$html .= "			".$rangos[0]['rango']."\n";
				$html .= "			<input type=\"hidden\" name=\"rango\" value=\"".$rangos[0]['rango']."\">\n";
			}

			$html .= "		</td>\n";
			$html .= "		<td class=\"label\">SEMANAS COTIZADAS</td>\n";
			$html .= "		<td class=\"normal_10AN\" width=\"7%\" class=\"normal_10AN\">\n";
			if($cotizante['semanas_cotizadas'] > 0)
				$html .= "			<input type=\"hidden\" name=\"Semanas\" value=\"".$semanas."\">".$semanas."\n";
			else
				$html .= "			<input class=\"input-text\" type=\"text\" name=\"Semanas\" style=\"width:100%\" maxlength=\"8\" onkeypress=\"return acceptNum(event)\" value=\"".$semanas."\">\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			
			return $html;
	  }
	  
	/**********************************************************************************
	*@acess private 
	***********************************************************************************/
	function FormaDatosTrasladosAmbulancias(&$pct)
	{
		$html = "<br><table width=\"100%\" align=\"center\">\n";
		$html .= "	<tr class=\"normal_10AN\" height=\"20\">\n";
		$html .= "		<td>TRASLADO EN AMBULANCIA PROPIA:</td>\n";
		$html .= "		<td>\n";
		$html .= "			SI&nbsp;&nbsp;<input type=\"radio\" name=\"traslado\" value=\"1\" checked>";
		$html .= "			NO&nbsp;&nbsp;<input type=\"radio\" name=\"traslado\" value=\"0\">";
		$html .= "		</td>\n";
		$html .= "	</tr>\n";
		$html .= "	<tr class=\"normal_10AN\" height=\"20\">\n";
		$html .= "		<td>TIPO DE AMBULANCIA:</td>\n";
		$html .= "		<td>\n";
		$html .= "			BASICA&nbsp;&nbsp;<input type=\"radio\" name=\"tipoambulancia\" value=\"1\" checked>";
		$html .= "			MEDICALIZADA&nbsp;&nbsp;<input type=\"radio\" name=\"tipoambulancia\" value=\"0\">";
		$html .= "		</td>\n";
		$html .= "	</tr>\n";
		$html .= "	<tr class=\"normal_10AN\" height=\"20\">\n";
		$html .= "		<td>ZONA DONDE RECOGE LA VICTIMA:</td>\n";
		$html .= "		<td>\n";
		$html .= "			URBANA&nbsp;&nbsp;<input type=\"radio\" name=\"lugar\" value=\"1\" checked>";
		$html .= "			RURAL&nbsp;&nbsp;<input type=\"radio\" name=\"lugar\" value=\"0\">";
		$html .= "		</td>\n";
		$html .= "	</tr>\n";
		$html .= "	<tr class=\"normal_10AN\" height=\"20\">\n";
		$html .= "		<td>NATURALEZA DEL EVENTO:</td>\n";
		$html .= "		<td>\n";
		$html .= "		<select name=\"tiponaturaleza\" class=\"select\" onChange=\"SetValor(this.value)\">\n";
		$html .=" 		<option value=\"\" selected>-------NINGUNO-------</option>\n";
		$eventos = $pct->ObtenerTiposEventos();
		for($i=0; $i<sizeof($eventos); $i++)
		{
			$html .=" 	<option value=\"".$eventos[$i][soat_naturaleza_evento_id]."\">".$eventos[$i]['descripcion']."</option>\n";
		}			
		$html .= "              </select>\n";
		$html .= "		</td>\n";
		$html .= "	</tr>\n";
		$html .= " <tr class=\"normal_10AN\">\n";
		$html .= "  <td width=\"100%\" colspan=\"2\">\n";
		//TIPO ACCIDENTE DE TRANSITO
		$html .= "    <div name='condicionaccidentado' id='condicionaccidentado' style=\"display:none\">";
		$html .= "	<table width=\"100%\" align=\"center\">\n";
		$html .= "      <tr class=\"normal_10AN\">";
		$html .= "      <td  width=\"40%\">COND. DEL ACCIDENTADO:";
		$html .= "      </td>";
		$html .= "      <td  width=\"60%\">";
		$condic = $pct->BuscarCondicion();
		for($i=0;$i<sizeof($condic);$i++)
		{
			$html.= "      ".strtoupper($condic[$i]['descripcion'])."";
			if($_POST['condicion']==$condic[$i]['condicion_accidentado'])
			{
				$html .= "      <input type='radio' name='condicion' value=\"".$condic[$i]['condicion_accidentado']."\" checked>";
			}
			else
			{
				$html .= "      <input type='radio' name='condicion' value=\"".$condic[$i]['condicion_accidentado']."\">";
			}
		}
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "     </table>\n";
		$html .= "    </div>";
		//FIN TIPO ACCIDENTE DE TANSITO
		$html .= "  </td>\n";
		$html .= " </tr>\n";
		$html .= "</table><br>\n";
		return $html;
	}

		/**********************************************************************************
		* 
		************************************************************************************/
		function FormaDatosNN($datos,$afiliado,$action)
		{
			$pct = new Pacientes();
			$paciente = $pct->ObtenerDatosPaciente($datos['tipo_id_paciente'],$datos['paciente_id']);
			$obliga = $pct->ObtenerCamposObligatorios();
			$plan  = $pct->ObtenerDatosPlanDescripcion($datos['plan_id']);
			$tipo  = $pct->ObtenerDescripcionId($datos['tipo_id_paciente']);
			
			$i = 0;

			$cl = new ClaseUtil();
			$zona = GetVarConfigAplication('DefaultZona');					
			$pais = GetVarConfigAplication('DefaultPais');
			$dpto = GetVarConfigAplication('DefaultDpto');
			$mpio = GetVarConfigAplication('DefaultMpio');
						
			$dpaciente = $paciente;
			if(empty($paciente) && !empty($afiliado))
				$dpaciente = $afiliado;
			
			if(!$dpaciente['primer_nombre'] && !$dpaciente['primer_apellido'])
			{
				$dpaciente['primer_apellido'] = 'NN';
				$dpaciente['primer_nombre'] = 'NN';
			}
			
			if(!empty($afiliado))
			{
				$html .= "  <center class=\"label_error\">\n";
				$html .= "		".RetornarWinOpenDatosBD($paciente['tipo_id_paciente'],$paciente['paciente_id'],$plan)."\n";
				$html .= "	</center><br>";
			}
			
			$valida = "";
			
			$html .="	<script language='javascript'>";
			$html .="		function acceptNum(evt)\n";
			$html .="		{\n";
			$html .="			var nav4 = window.Event ? true : false;\n";
			$html .="			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .="			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .="		}\n";
			$html .= "		function acceptDate(evt)\n";
			$html .= "		{\n";
			$html .= "			var nav4 = window.Event ? true : false;\n";
			$html .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "		}\n";
			$html .="	</script>";
			
			if(!empty($afiliado))
			{
				if($asfiliado['activo'] == 0 )
				{
					$html .= "<p class=\"label_error\" align=\"center\">\n";
					$html .= "	EL PACIENTE ESTA ".strtoupper($afiliado['estado'])."\n";
					$html .= "</p>\n";
				}
			}
			$html .= "<center>\n";				
			$html .= "	<fieldset style=\"width:65%\" class=\"fieldset\"><legend>DATOS DEL PACIENTE</legend>\n";
			$html .= "  	<form name=\"forma\" action=\"javascript:EvaluarDatos(document.forma)\" method=\"post\">";
			$html .= "			<input type=\"hidden\" name=\"forma\" value=\"FormaNN\" class=\"input-text\" >\n";
			$html .= "			<input type=\"hidden\" name=\"actualizar\" value=\"".sizeof($paciente)."\" class=\"input-text\" >\n";
			$html .= "			<input type=\"hidden\" name=\"zona\" value=\"".$zona."\">\n";
			$html .= "			<input type=\"hidden\" name=\"pais\" value=\"".$pais."\">\n";
			$html .= "			<input type=\"hidden\" name=\"dpto\" value=\"".$dpto."\">\n";
			$html .= "			<input type=\"hidden\" name=\"mpio\" value=\"".$mpio."\">\n";
			$html .= "			<input type=\"hidden\" name=\"plan_id\" value=\"".$datos['plan_id']."\">\n";
			$html .= "			<input type=\"hidden\" name=\"paciente_id\" value=\"".$datos['paciente_id']."\">\n";
			$html .= "			<table width=\"95%\" align=\"center\">\n";

			if(!empty($paciente))
			{
				$html .= "				<tr class=\"label\" height=\"20\">\n";
				$html .= "					<td>FECHA REGISTRO: </td>\n";
				$html .= "					<td class=\"normal_10AN\">".$paciente['fecha_registro']."</td>\n";
				$html .= "				</tr>\n";
			}
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\" width=\"30%\">RESPONSABLE: </td>\n";
			$html .= "			<td>".$plan['plan_descripcion']."</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\">TIPO DOCUMENTO:\n";
			$html .= "			<td>".$tipo['descripcion']."</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\">DOCUMENTO: </td>\n";
			$html .= "			<td >".$datos['paciente_id']."</td>\n";
			$html .= "		</tr>\n";

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

			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.primernombre.value,'0','PRIMER NOMBRE','primer_nombre','texto');\n";

			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td><label id=\"primer_nombre\" class=\"label\">PRIMER NOMBRE:</label></td>\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"text\" maxlength=\"20\" name=\"primernombre\" value=\"".$dpaciente['primer_nombre']."\" class=\"input-text\" size=\"30\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td><label id=\"primer_apellido\" class=\"label\">PRIMER APELLIDO:</label></td>\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"text\" maxlength=\"20\" name=\"primerapellido\" value=\"".$dpaciente['primer_apellido']."\" class=\"input-text\" size=\"30\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.primerapellido.value,'0','PRIMER APELLIDO','primer_apellido','texto');\n";
			
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td><label id=\"calculada\" class=\"label\">* EDAD CALCULADA:</label></td>\n"; 
			$html .= "      <td >\n";
			$html .= "				<input type=\"text\" name=\"edadcalculada\" class=\"input-text\" size=\"6\">\n";
			$html .= "      	<select name=\"edad\"  class=\"select\">\n";
			$html .= "					<option value=\"3\">Años</option>\n";  
			$html .= "					<option value=\"1\">Días</option>\n";  
			$html .= "					<option value=\"2\">Meses</option>\n"; 
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.edadcalculada.value,'1','EDAD CALCULADA','calculada','numerico');\n";
			
			if($obliga['lugar_residencia']['sw_mostrar'] == 1 && $obliga['lugar_residencia']['sw_obligatorio'] == 1)
			{
				if($paciente['tipo_pais_id']) $pais = $paciente['tipo_pais_id'];
				if($paciente['tipo_dpto_id']) $dpto = $paciente['tipo_dpto_id'];
				if($paciente['tipo_mpio_id']) $mpio = $paciente['tipo_mpio_id'];
				
				$NomPais = $pct->ObtenerNombrePais($pais);
				$NomDpto = $pct->ObtenerNombreDepartamento($pais,$dpto);
				$NomMpio = $pct->ObtenerNombreCiudad($pais,$dpto,$mpio);
				
				$url = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$pais."&dept=".$dpto."&mpio=".$mpio."&forma=forma ";
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"lugar_residencia\" class=\"label\">".$obliga['lugar_residencia']['marca']." LUGAR RESIDENCIA:</label></td>\n";
				$html .= "			<td >\n";
				$html .= "				<label id=\"ubicacion\">".$NomPais." - ".$NomDpto." - ".$NomMpio."</label>\n";
				$html .= "				<input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"Cambiar\" target=\"localidad\" onclick=\"window.open('".$url."','localidad','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\"\">\n";
				$html .= "			</td>\n";
				$html .= "    </tr>\n";

				$valida .= "	obligatorios[".($i++)."] = new Array(document.getElementById('ubicacion').innerHTML,'".$obliga['lugar_residencia']['sw_obligatorio'] ."','LUGAR RESIDENCIA','lugar_residencia','texto');\n";				

				if($obliga['tipo_comuna_id']['sw_mostrar'] == 1)
				{
					$NomComuna = $pct->ObtenerNombreComuna($pais,$dpto,$mpio,$comuna);
					
					$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
					$html .= "			<td><label id=\"tipo_comuna\" class=\"label\">".$obliga['tipo_comuna_id']['marca']." ".strtoupper(ModuloGetVar('app','Pacientes','NombreComuna')).":</label></td>\n";
					$html .= "      <td>\n";
					$html .= "				<input type=\"text\" name=\"ncomuna\" value=\"".$NomComuna."\" class=\"input-text\" readonly  style=\"width:60%;background:#FFFFFF\">\n";
					$html .= "      	<input type=\"hidden\" name=\"comuna\" value=\"".$comuna."\" class=\"input-text\">\n";
					$html .= "			</td>\n";
					$html .= "    </tr>\n";
					$valida .= "	obligatorios[".($i++)."] = new Array(objeto.comuna.value,'".$obliga['tipo_comuna_id']['sw_obligatorio'] ."','".strtoupper(ModuloGetVar('app','Pacientes','NombreComuna'))."','tipo_comuna','texto');\n";				
				}

				if($obliga['tipo_barrio_id']['sw_mostrar'] == 1 && $obliga['tipo_comuna_id']['sw_obligatorio'] == 1)
				{
					$NomBarrio = $pct->ObtenerNombreBarrio($pais,$dpto,$mpio,$comuna,$barrio);
					
					$html .= "    <tr class=\"normal_10AN\" height=\"20\">\n";
					$html .= "			<td><label id=\"nombre_barrio\" class=\"label\">".$obliga['tipo_barrio_id']['marca']." BARRIO:</label></td>\n";
					$html .= "			<td>\n";
					$html .= "				<input type=\"text\" name=\"nbarrio\" value=\"".$NomBarrio."\" class=\"input-text\" readonly style=\"width:60%;background:#FFFFFF\">\n";
					$html .= "				<input type=\"hidden\" name=\"barrio\" value=\"".$barrio."\" class=\"input-text\">\n";
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
				$html .= "				<input type=\"text\" name=\"estrato\" value=\"".trim($dpaciente['tipo_estrato_id'])."\" class=\"input-text\" maxlength=\"1\" size=\"7\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.estrato.value,'".$obliga['tipo_estrato_id']['sw_obligatorio'] ."','ESTRATO','tipo_estrato','texto');\n";				
			}
			
			$tiposS = $pct->ObtenerTiposSexo();

			$html .= "    <tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td><label id=\"tipo_genero\" class=\"label\">* SEXO:</label></td>\n";
			$html .= "			<td>\n";
			$html .= "				<select name=\"Sexo\"  class=\"select\">\n";
			$html .= "					<option value=\"-1\">--Seleccione--</option>\n";
			foreach($tiposS as $key => $tp)
			{
				($dpaciente['sexo_id'] == $tp['sexo_id'])? $chk = "selected": $chk = "";
				$html .= "					<option value=\"".$tp['sexo_id']."\" $chk>".$tp['descripcion']."</option>\n";
			}
			
			$html .= "					</select>\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.Sexo.value,'1','SEXO','tipo_genero','texto');\n";				
			
			//AMBULANCIAS - TIPO PLAN SOAT
/*			if($afiliado[sw_tipo_plan]=='1')
			{
				$html .= "    	<tr class=\"label\" height=\"20\">\n";
				$html .= "      	<td colspan=\"2\" align=\"center\">\n";
				$html .= "      		<fieldset class=\"fieldset\" style=\"width:100%\"><legend><label id=\"traslado\" class=\"label\">* TRASLADO AMBULANCIA:</label></legend>\n";
				$html .= 		$this->FormaDatosTrasladosAmbulancias(&$pct);
				$html .= "      		</fieldset>\n";
			}*/
			//FIN OPCION AMBULANCIAS
			
			$html .= "    	<tr class=\"label\" height=\"20\">\n";
			$html .= "      	<td colspan=\"2\" align=\"center\">\n";
			$html .= "      		<fieldset class=\"fieldset\" style=\"width:100%\"><legend><label id=\"afiliado\" class=\"label\">* DATOS DE AFILIACION:</label></legend>\n";
			$rango = $afiliado;
			
			$html .= $this->FormaDatosAfiliado($rango,$datos['plan_id'],$pct);
			$html .= "      		</fieldset>\n";
			$html .= "      	</td>\n";
			$html .= "    	</tr>\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.tipoafiliado.value,'1','DATOS DE AFILIACION','afiliado','texto');\n";				
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.rango.value,'1','DATOS DE AFILIACION','afiliado','texto');\n";				
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.Semanas.value,'1','DATOS DE AFILIACION','afiliado','numerico');\n";				

			$html .= "    	<tr class=\"label\" height=\"20\">\n";
			$html .= "      	<td colspan=\"2\" align=\"center\">\n";
			$html .= "      		<fieldset style=\"width:90%\"><legend><label id=\"observaciones\" class=\"label\">DATOS ADICIONALES:</label></legend>\n";
			$html .= "						<textarea name=\"Observaciones\" style=\"width:100%\" rows=\"2\" class=\"textarea\">".$paciente['observaciones']."</textarea>\n";
			$html .= "      		</fieldset>\n";
			$html .= "      	</td>\n";
			$html .= "    	</tr>\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.Observaciones.value,'".$obliga['observaciones']['sw_obligatorio'] ."','DATOS ADICIONALES','observaciones','texto');\n";				

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
			$html .= "		objeto.action = '".$action['aceptar']."';\n";
			$html .= "		objeto.submit();\n";
			$html .= "	}\n";
			//tipo-accidente transito
			$html .= "	function SetValor(valor)\n";
			$html .= "	{\n";
			$html .= "	 e = document.getElementById('condicionaccidentado');\n";
			$html .= "	 if(valor == '01')\n";
			$html .= "	 {\n";
			$html .= "	  e.style.display = \"block\";\n";
			$html .= "	 }\n";
			$html .= "	 else\n";
			$html .= "	 {\n";
			$html .= "	  e.style.display = \"none\";\n";
			$html .= "	 }\n";
			$html .= "	}\n";
			//fin tipo-accidente transito
			$html .= "</script>\n";
			return $html;
		}
		/**********************************************************************************
		* 
		************************************************************************************/
		function FormaDatosPaciente($datos, $afiliados, $action, $afiliacion)
		{
		
			$pct = new Pacientes();
			$paciente = $pct->ObtenerDatosPaciente($datos['tipo_id_paciente'],$datos['paciente_id']);
			$obliga = $pct->ObtenerCamposObligatorios();
			$plan  = $pct->ObtenerDatosPlanDescripcion($datos['plan_id']);
			$tipo  = $pct->ObtenerDescripcionId($datos['tipo_id_paciente']);
			$zonas = $pct->ObtenerZonasResidencia();
			$ESMP = $pct->Validar_Paciente_ESM($datos['tipo_id_paciente'],$datos['paciente_id']);
			$ESMS = $pct->_ESM();
	    $FUERZAS = $pct->Pacientes_Fuerza($datos['tipo_id_paciente'],$datos['paciente_id']);
			$TIPOS_FUERZA =$pct->Tipos_Fuerzas();
			
			$i = 0;

			$cl = new ClaseUtil();
			$zona = GetVarConfigAplication('DefaultZona');					
			$pais = GetVarConfigAplication('DefaultPais');
			$dpto = GetVarConfigAplication('DefaultDpto');
			$mpio = GetVarConfigAplication('DefaultMpio');
			$afiliado = $afiliados['afiliados'];
			
			$fecha = $paciente['fecha_nacimiento'];
			if($afiliado['fecha_nacimiento'])
			{
				$f = explode("-",$afiliado['fecha_nacimiento']);
				if(sizeof($f) == 3)
          $fecha = $f[2]."/".$f[1]."/".$f[0];
        else
          $fecha = $afiliado['fecha_nacimiento'];
      }	
			
			$dpaciente = $paciente;
			if(empty($paciente) && !empty($afiliado))
				$dpaciente = $afiliado;
			
			if(!empty($afiliado))
			{
				$html .= "  <center class=\"label_error\">\n";
				$html .= "		".RetornarWinOpenDatosBD($paciente['tipo_id_paciente'],$paciente['paciente_id'],$plan)."\n";
				$html .= "	</center><br>";
			}
			
			$valida = "";
			
			$html .="	<script language='javascript'>";
			$html .="		function acceptNum(evt)\n";
			$html .="		{\n";
			$html .="			var nav4 = window.Event ? true : false;\n";
			$html .="			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .="			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .="		}\n";
			$html .= "		function acceptDate(evt)\n";
			$html .= "		{\n";
			$html .= "			var nav4 = window.Event ? true : false;\n";
			$html .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "		}\n";
			$html .="	</script>";
			
			if(!empty($afiliado))
			{
				if($afiliado['activo'] === 0 )
				{
					$html .= "<p class=\"label_error\" align=\"center\">\n";
					$html .= "	EL PACIENTE ESTA ".strtoupper($afiliado['estado'])."\n";
					$html .= "</p>\n";
				}
			}
			$html .= "<center>\n";
			if($paciente['fecha_nacimiento_es_calculada'] == '1')
				$html .= "<center class=\"normal_10AN\">LA EDAD DEL PACIENTE ES CALCULADA</center>";
						
			$html .= "	<fieldset style=\"width:65%\" class=\"fieldset\"><legend>DATOS DEL PACIENTE</legend>\n";
			$html .= "  	<form name=\"forma\" action=\"javascript:EvaluarDatos(document.forma)\" method=\"post\">";
			$html .= "			<input type=\"hidden\" name=\"forma\" value=\"paciente\" class=\"input-text\" >\n";
			$html .= "			<input type=\"hidden\" name=\"actualizar\" value=\"".sizeof($paciente)."\" class=\"input-text\" >\n";
			$html .= "			<input type=\"hidden\" name=\"zona\" value=\"".$zona."\">\n";
			$html .= "			<input type=\"hidden\" name=\"pais\" value=\"".$pais."\">\n";
			$html .= "			<input type=\"hidden\" name=\"dpto\" value=\"".$dpto."\">\n";
			$html .= "			<input type=\"hidden\" name=\"mpio\" value=\"".$mpio."\">\n";
			$html .= "			<input type=\"hidden\" name=\"plan_id\" value=\"".$datos['plan_id']."\">\n";
			$html .= "			<table width=\"95%\" align=\"center\">\n";

			if(!empty($paciente))
			{
				$html .= "				<tr class=\"label\" height=\"20\">\n";
				$html .= "					<td>FECHA REGISTRO: </td>\n";
				$html .= "					<td class=\"normal_10AN\">".$paciente['fecha_registro']."</td>\n";
				$html .= "				</tr>\n";
			}
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\" width=\"30%\">RESPONSABLE: </td>\n";
			$html .= "			<td>".$plan['plan_descripcion']."</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\">TIPO DOCUMENTO:\n";
			$html .= "			<td>".$tipo['descripcion']."</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\">DOCUMENTO: </td>\n";
			$html .= "			<td >".$datos['paciente_id']."</td>\n";
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
			$html .= "				<input type=\"text\" maxlength=\"20\" name=\"primernombre\" value=\"".$dpaciente['primer_nombre']."\" class=\"input-text\" size=\"30\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			if($obliga['segundo_nombre']['sw_mostrar'] == 1)
			{
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"segundo_nombre\" class=\"label\">".$obliga['segundo_nombre']['marca']." SEGUNDO NOMBRE:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				<input type=\"text\" maxlength=\"20\" name=\"segundonombre\" value=\"".$dpaciente['segundo_nombre']."\" class=\"input-text\" size=\"30\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.segundonombre.value,'".$obliga['segundo_nombre']['sw_obligatorio'] ."','SEGUNDO NOMBRE','segundonombre','texto');\n";
			}
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td><label id=\"primer_apellido\" class=\"label\">* PRIMER APELLIDO:</label></td>\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"text\" maxlength=\"20\" name=\"primerapellido\" value=\"".$dpaciente['primer_apellido']."\" class=\"input-text\" size=\"30\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.primerapellido.value,'1','PRIMER APELLIDO','primer_apellido','texto');\n";

			if($obliga['segundo_apellido']['sw_mostrar'] == 1)
			{
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"segundo_apellido\" class=\"label\">".$obliga['segundo_apellido']['marca']." SEGUNDO APELLIDO:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				<input type=\"text\" maxlength=\"20\" name=\"segundoapellido\" value=\"".$dpaciente['segundo_apellido']."\" class=\"input-text\" size=\"30\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.segundoapellido.value,'".$obliga['segundo_apellido']['sw_obligatorio'] ."','SEGUNDO APELLIDO','segundo_apellido','texto');\n";				
			}

			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td><label class=\"label\" id=\"fecha_nacimiento\">* FECHA NACIMIENTO:</label></td>\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"text\" name=\"fechanacimiento\" value=\"".$fecha."\" class=\"input-text\" maxlength=\"12\" onkeyPress=\"return acceptDate(event)\" size=\"14\">\n";
			$html .= "				".ReturnOpenCalendario('forma','fechanacimiento','/')."\n";
			$html .= "			</td>\n";
			$html .= "    </tr>\n";
			if(!$fecha)
			{
				if($obliga['fecha_nacimiento_es_calculada']['sw_mostrar'] == 1)
				{
					$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
					$html .= "			<td class=\"label\">".$obliga['fecha_nacimiento_es_calculada']['marca']." EDAD CALCULADA:</label></td>\n"; 
					$html .= "      <td >\n";
					$html .= "				<input type=\"text\" name=\"edadcalculada\" class=\"input-text\" size=\"6\">\n";
					$html .= "      	<select name=\"edad\"  class=\"select\">\n";
					$html .= "					<option value=\"3\">Años</option>\n";  
					$html .= "					<option value=\"1\">Días</option>\n";  
					$html .= "					<option value=\"2\">Meses</option>\n"; 
					$html .= "				</select>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
				}
			}
			
			if($obliga['residencia_direccion']['sw_mostrar'] == 1)
			{
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"direccion_residencia\" class=\"label\">".$obliga['residencia_direccion']['marca']." DIRECCION:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				<input type=\"text\" maxlength=\"60\" name=\"Direccion\" value=\"".$dpaciente['residencia_direccion']."\" class=\"input-text\" size=\"30\">\n";
				$html .= "			</td>\n";
				$html .= "    </tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.Direccion.value,'".$obliga['residencia_direccion']['sw_obligatorio'] ."','DIRECCION','direccion_residencia','texto');\n";				
			}
			
			if($obliga['lugar_residencia']['sw_mostrar'] == 1 && $obliga['lugar_residencia']['sw_obligatorio'] == 1)
			{
				if($paciente['tipo_pais_id']) $pais = $paciente['tipo_pais_id'];
				if($paciente['tipo_dpto_id']) $dpto = $paciente['tipo_dpto_id'];
				if($paciente['tipo_mpio_id']) $mpio = $paciente['tipo_mpio_id'];
				
				$NomPais = $pct->ObtenerNombrePais($pais);
				$NomDpto = $pct->ObtenerNombreDepartamento($pais,$dpto);
				$NomMpio = $pct->ObtenerNombreCiudad($pais,$dpto,$mpio);
				
				$url = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$pais."&dept=".$dpto."&mpio=".$mpio."&forma=forma ";
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"lugar_residencia\" class=\"label\">".$obliga['lugar_residencia']['marca']." LUGAR RESIDENCIA:</label></td>\n";
				$html .= "			<td >\n";
				$html .= "				<label id=\"ubicacion\">".$NomPais." - ".$NomDpto." - ".$NomMpio."</label>\n";
				$html .= "				<input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"Cambiar\" target=\"localidad\" onclick=\"window.open('".$url."','localidad','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\"\">\n";
				$html .= "			</td>\n";
				$html .= "    </tr>\n";

				$valida .= "	obligatorios[".($i++)."] = new Array(document.getElementById('ubicacion').innerHTML,'".$obliga['lugar_residencia']['sw_obligatorio'] ."','LUGAR RESIDENCIA','lugar_residencia','texto');\n";				

				if($obliga['tipo_comuna_id']['sw_mostrar'] == 1)
				{
					$NomComuna = $pct->ObtenerNombreComuna($pais,$dpto,$mpio,$comuna);
					
					$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
					$html .= "			<td><label id=\"tipo_comuna\" class=\"label\">".$obliga['tipo_comuna_id']['marca']." ".strtoupper(ModuloGetVar('app','Pacientes','NombreComuna')).":</label></td>\n";
					$html .= "      <td>\n";
					$html .= "				<input type=\"text\" name=\"ncomuna\" value=\"".$NomComuna."\" class=\"input-text\" readonly  style=\"width:60%;background:#FFFFFF\">\n";
					$html .= "      	<input type=\"hidden\" name=\"comuna\" value=\"".$comuna."\" class=\"input-text\">\n";
					$html .= "			</td>\n";
					$html .= "    </tr>\n";
					$valida .= "	obligatorios[".($i++)."] = new Array(objeto.comuna.value,'".$obliga['tipo_comuna_id']['sw_obligatorio'] ."','".strtoupper(ModuloGetVar('app','Pacientes','NombreComuna'))."','tipo_comuna','texto');\n";				
				}

				if($obliga['tipo_barrio_id']['sw_mostrar'] == 1 && $obliga['tipo_comuna_id']['sw_obligatorio'] == 1)
				{
					$NomBarrio = $pct->ObtenerNombreBarrio($pais,$dpto,$mpio,$comuna,$barrio);
					
					$html .= "    <tr class=\"normal_10AN\" height=\"20\">\n";
					$html .= "			<td><label id=\"nombre_barrio\" class=\"label\">".$obliga['tipo_barrio_id']['marca']." BARRIO:</label></td>\n";
					$html .= "			<td>\n";
					$html .= "				<input type=\"text\" name=\"nbarrio\" value=\"".$NomBarrio."\" class=\"input-text\" readonly style=\"width:60%;background:#FFFFFF\">\n";
					$html .= "				<input type=\"hidden\" name=\"barrio\" value=\"".$barrio."\" class=\"input-text\">\n";
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
				$html .= "				<input type=\"text\" name=\"estrato\" value=\"".trim($dpaciente['tipo_estrato_id'])."\" class=\"input-text\" maxlength=\"1\" size=\"7\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.estrato.value,'".$obliga['tipo_estrato_id']['sw_obligatorio'] ."','ESTRATO','tipo_estrato','texto');\n";				
			}
			
			if($obliga['residencia_telefono']['sw_mostrar'] == 1)
			{
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"telefono_residencia\" class=\"label\">".$obliga['residencia_telefono']['marca']." TELEFONO:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				<input type=\"text\" maxlength=\"30\" name=\"Telefono\" value=\"".$dpaciente['residencia_telefono']."\" class=\"input-text\" size=\"30\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.Telefono.value,'".$obliga['residencia_telefono']['sw_obligatorio'] ."','TELEFONO','telefono_residencia','texto');\n";				
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

			foreach($zonas as $key => $valor)
			{
        $chk = ""; if($valor['zona_residencia'] == $zona) $chk = "checked";
				$html .= "				<input type=\"radio\" name=\"Zona\" value=\"".$valor['zona_residencia']."\" $chk>".$valor['descripcion']."\n";
      }
			$html .= "			</td>\n";
			$html .= "		</tr>\n";

			if($obliga['ocupacion_id']['sw_mostrar'] == 1)
			{
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";//Spente le stelle 
				$html .= "			<td><label id=\"tipo_ocupacion\" class=\"label\">".$obliga['ocupacion_id']['marca']." OCUPACION:</label></td>\n";
				$html .= "      <td>\n";
				if($dpaciente['ocupacion_id'])
					$dpaciente['nombre_ocupa'] = $pct->ObtenerNombreOcupacion($dpaciente['ocupacion_id']);
				
				$html .= "      	<input type=\"hidden\" name=\"ocupacion_id\" value=\"".$dpaciente['ocupacion_id']."\">\n";
				$html .= "				<textarea class=\"textarea\"	rows=\"2\" name=\"descripcion_ocupacion\" readonly style=\"width:70%;background:#FFFFFF\"\">".$dpaciente['nombre_ocupa']."</textarea>\n";
				$html .= "				<input type=\"button\" name=\"ocupacion\" value=\"Ocupación\" class=\"input-submit\" onClick=\"javascript:Ocupaciones('forma','')\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.ocupacion.value,'".$obliga['ocupacion_id']['sw_obligatorio'] ."','OCUPACION','tipo_ocupacion','texto');\n";				
			}
			$tiposS = $pct->ObtenerTiposSexo();

			$html .= "    <tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td><label id=\"tipo_genero\" class=\"label\">* SEXO:</label></td>\n";
			$html .= "			<td>\n";
			$html .= "				<select name=\"Sexo\"  class=\"select\">\n";
			$html .= "					<option value=\"-1\">--Seleccione--</option>\n";
			foreach($tiposS as $key => $tp)
			{
				($dpaciente['sexo_id'] == $tp['sexo_id'])? $chk = "selected": $chk = "";
				$html .= "					<option value=\"".$tp['sexo_id']."\" $chk>".$tp['descripcion']."</option>\n";
			}
			
			$html .= "					</select>\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.Sexo.value,'1','SEXO','tipo_genero','texto');\n";				

			if($obliga['talla']['sw_mostrar'] == 1)
			{
				$unidad = $pct->ObtenerUnidad('talla');				
				$html .= "    <tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"talla_metrica\" class=\"label\">".$obliga['talla']['marca']." TALLA:</label></td\n>";
				$html .= "			<td class=\"normal_10AN\">\n";
				$html	.= "				<input type=\"text\" id=\"talla\" name=\"metrica[talla]\" value=\"".trim($paciente['talla'])."\" class=\"input-text\" maxlength=\"5\" size=\"7\" onKeyPress='return acceptNum(event)'> Altura: ".$unidad."\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.talla.value,'".$obliga['talla']['sw_obligatorio'] ."','TALLA','talla_metrica','numerico');\n";				
			}				
			
			if($obliga['peso']['sw_mostrar'] == 1)
			{
				$unidad = $pct->ObtenerUnidad('peso');
				$html .= "    <tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"peso_metrica\" class=\"label\">".$obliga['peso']['marca']." PESO:</label></td>\n";
				$html .= "			<td class=\"normal_10AN\">\n";
				$html .= "				<input type=\"text\" id=\"peso\" name=\"metrica[peso]\" value=\"".trim($paciente['peso'])."\" class=\"input-text\" maxlength=\"5\" size=\"7\" onKeyPress='return acceptNum(event)'> ".$unidad."\n";
				$html .= "			</td>\n";
				$html .= "    </tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.peso.value,'".$obliga['peso']['sw_obligatorio'] ."','PESO','peso_metrica','numerico');\n";				
			}

			if($obliga['tipo_estado_civil_id']['sw_mostrar'] == 1)
			{
				$estadocivil = $pct->ObtenerEstadoCivil();
				$html .= "    <tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "    	<td><label id=\"estado_civil\" class=\"label\">".$obliga['tipo_estado_civil_id']['marca']." ESTADO CIVIL:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				<select name=\"estadocivil\"  class=\"select\">\n";
				$html .= "					<option value=\"-1\">--Seleccione--</option>\n";
				
				foreach($estadocivil as $key => $tp)
				{
					($tp['tipo_estado_civil_id'] == $paciente['tipo_estado_civil_id'])? $chk= "selected": $chk = "";
					$html .= "					<option value=\"".$tp['tipo_estado_civil_id']."\" $chk>".$tp['descripcion']."</option>\n";
				}
				$html .= "				</select>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.estadocivil.value,'".$obliga['tipo_estado_civil_id']['sw_obligatorio'] ."','ESTADO CIVIL','estado_civil','texto');\n";				
			}
			//TIPOS DE USUARIOS
			if($obliga['tipos_condicion_usuarios_planes_id']['sw_mostrar'] == 1)
			{
				$tipoUsuario = $pct->ObtenerTiposUsuariosPlan($datos['plan_id']);
				$html .= "    <tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "    	<td><label id=\"condicion\" class=\"label\">".$obliga['tipos_condicion_usuarios_planes_id']['marca']." CONDICION DEL PACIENTE:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				<select name=\"condicionUsuario\"  class=\"select\" id='condicionUsuario'>\n";
				$html .= "					<option value=\"-1\">--Seleccione--</option>\n";
				
				foreach($tipoUsuario as $key => $tp)
				{
					$html .= "					<option value=\"".$tp['tipos_condicion_usuarios_planes_id']."\" $chk>".$tp['descripcion']."</option>\n";
				}
				$html .= "				</select>\n";
				//$valida .= "	obligatorios[".($i++)."] = new Array(objeto.condicionUsuario.value,'".$obliga['tipos_condicion_usuarios_planes_id']['sw_obligatorio'] ."','CONDICION PACIENTE','condicion','texto');\n";				
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
			//FIN TIPOS DE USUARIOS
			}
			
	if($_REQUEST['NO_ESM']!='NO')
	{
      if(empty($ESMP))
			{
					
				$html .= "    	<tr class=\"label\" height=\"20\">\n";
				$html .= "    	<td><label id=\"esm_paciente\" class=\"label\">ESM DEL PACIENTE:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				<select name=\"esm_pac\"  class=\"select\" id='esm_pac'>\n";
				$html .= "					<option value=\"-1\">--Seleccione--</option>\n";
				
				foreach($ESMS as $key => $tp)
				{
					$html .= "					<option value=\"".$tp['tipo_id_tercero']."@".$tp['tercero_id']."\" $chk>".$tp['nombre_tercero']."</option>\n";
				}
				$html .= "				</select>\n";
				//$valida .= "	obligatorios[".($i++)."] = new Array(objeto.esm_pac.value,'".$obliga['tipos_condicion_usuarios_planes_id']['sw_obligatorio'] ."','CONDICION PACIENTE','condicion','texto');\n";				
				
				$html .= "			</td>\n";
				$html .= "    	</tr>\n";
				
				
			
			}
			if(empty($FUERZAS))
			{
					
				$html .= "    	<tr class=\"label\" height=\"20\">\n";
				$html .= "    	<td><label id=\"fuerzas\" class=\"label\">TIPO DE FUERZAS:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				<select name=\"tipo_fuerza_i\"  class=\"select\" id='tipo_fuerza_i'>\n";
				$html .= "					<option value=\"-1\">--Seleccione--</option>\n";
				
				foreach($TIPOS_FUERZA as $key => $tp)
				{
					$html .= "					<option value=\"".$tp['tipo_fuerza_id']."\" $chk>".$tp['descripcion']."</option>\n";
				}
				$html .= "				</select>\n";
				//$valida .= "	obligatorios[".($i++)."] = new Array(objeto.condicionUsuario.value,'".$obliga['tipos_condicion_usuarios_planes_id']['sw_obligatorio'] ."','CONDICION PACIENTE','condicion','texto');\n";				
				
				$html .= "			</td>\n";
				$html .= "    	</tr>\n";
				
				
			
			}
	}

    /*  if($afiliacion || empty($afiliacion))
      {
      
  			$html .= "    	<tr class=\"label\" height=\"20\">\n";
  			$html .= "      	<td colspan=\"2\" align=\"center\">\n";
  			$html .= "      		<fieldset class=\"fieldset\" style=\"width:100%\"><legend><label id=\"afiliado\" class=\"label\">* DATOS DE AFILIACION:</label></legend>\n";
  			$rango = $afiliados['afiliados'];
  			if(!empty($afiliados['externa']))
  				$rango = $afiliados['externa'];
  			
  			$html .= $this->FormaDatosAfiliado($rango,$datos['plan_id'],$pct,$afiliados['afiliados']['afiliacion_activa']);
  			$html .= "      		</fieldset>\n";
  			$html .= "      	</td>\n";
  			$html .= "    	</tr>\n";
  			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.tipoafiliado.value,'1','DATOS DE AFILIACION','afiliado','texto');\n";				
  			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.rango.value,'1','DATOS DE AFILIACION','afiliado','texto');\n";				
  			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.Semanas.value,'1','DATOS DE AFILIACION','afiliado','numerico');\n";				
			}
				
		/*	if($obliga['observaciones']['sw_mostrar'] == 1)
			{
				$html .= "    	<tr class=\"label\" height=\"20\">\n";
				$html .= "      	<td colspan=\"2\" align=\"center\">\n";
				$html .= "      		<fieldset style=\"width:99%\"><legend><label id=\"observaciones\" class=\"label\">".$obliga['observaciones']['marca']." DATOS ADICIONALES:</label></legend>\n";
				$html .= "						<textarea name=\"Observaciones\" style=\"width:100%\" rows=\"2\" class=\"textarea\">".$paciente['observaciones']."</textarea>\n";
				$html .= "      		</fieldset>\n";
				$html .= "      	</td>\n";
				$html .= "    	</tr>\n";
				$valida .= "	obligatorios[".($i++)."] = new Array(objeto.Observaciones.value,'".$obliga['observaciones']['sw_obligatorio'] ."','DATOS ADICIONALES','observaciones','texto');\n";				
			}*/
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
      
			if($_REQUEST['NO_ESM']!='NO')
			{
          if(empty($ESMP))
          {
					
                    
              $html .= "			if(objeto.esm_pac.value == '-1')\n";
              $html .= "			{\n";
              $html .= "				document.getElementById('error').innerHTML = 'NO SE HA SELECCIONADO EL TIPO DE FUERZA';\n";
              $html .= "				document.getElementById('esm_paciente').className = 'label_error';\n";
              $html .= "				return;\n";
              $html .= "			}\n";
        }
			
          if(empty($FUERZAS))
          {
          
          $html .= "			if(objeto.tipo_fuerza_i.value == '-1')\n";
          $html .= "			{\n";
          $html .= "				document.getElementById('error').innerHTML = 'NO SE HA SELECCIONADO EL TIPO DE FUERZA';\n";
          $html .= "				document.getElementById('fuerzas').className = 'label_error';\n";
          $html .= "				return;\n";
          $html .= "			}\n";
          
          }
			}
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
			
		
			
 			//
/*			$html .= "		if(document.getElementById('condicionUsuario').value == '-1')\n";
			$html .= "		{\n";
			$html .= "				document.getElementById('error').innerHTML = 'NO SE HA INGRESADO LA CONDICIï¿½N DEL USUARIO';\n";
			$html .= "				document.getElementById('condicion').className = 'label_error';\n";
			$html .= "				return;\n";
			$html .= "		}\n";*/
			//
		
			$html .= "		document.getElementById('error').innerHTML = '<br>';\n";
			$html .= "		objeto.action = '".$action['aceptar']."';\n";
			$html .= "		objeto.submit();\n";
			$html .= "	}\n";
			//tipo-accidente transito
			$html .= "function SetValor(valor)\n";
			$html .= "{\n";
			$html .= " e = document.getElementById('condicionaccidentado');\n";
			$html .= " if(valor == '01')\n";
			$html .= " {\n";
			$html .= "  e.style.display = \"block\";\n";
			$html .= " }\n";
			$html .= " else\n";
			$html .= " {\n";
			$html .= "  e.style.display = \"none\";\n";
			$html .= " }\n";
			$html .= "}\n";
			//fin tipo-accidente transito

			$html .= "</script>\n";
			
			return $html;
		}
		/**********************************************************************************
		* 
		************************************************************************************/
		function FormaNombresHomonimos($pacientes,$datos,$action)
		{
			$html = "";

			$html .= ThemeAbrirTabla('PACIENTES - HOMONIMOS');
			$html .= "<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td colspan=\"2\">DATOS DEL PACIENTE ACTUAL</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td width=\"25%\">IDENTIFICACION</td>\n";
			$html .= "		<td>NOMBRE</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"normal_10AN\">\n";
			$html .= "		<td align=\"center\" >".$datos['tipo_id_paciente']." ".$datos['paciente_id']."</td>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			".strtoupper($datos['primernombre']." ".$datos['segundonombre']." ".$datos['primerapellido']." ".$datos['segundoapellido'])."\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table><br>\n";

			$html .= "<form name=\"continuar\" action=\"".$action['continuar']."\" method=\"post\">\n";
			$html .= "	<center>\n";
			$html .= "		<input type=\"submit\" name=\"continuar\" class=\"input-submit\" value=\"Continuar - Ingreso Paciente\">\n";
			$html .= "	</center>\n";
			$html .= "</form>\n";
			
			$html .= "<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td width=\"25%\">IDENTIFICACION</td>\n";
			$html .= "		<td width=\"70%\">PACIENTE</td>\n";
			$html .= "		<td></td>\n";
			$html .= "		<td></td>\n";
			$html .= "	</tr>\n";
			
			$estilo = "";
			foreach($pacientes as $key => $homonimo)
			{
				$pct = new Pacientes();
				$cantidad = $pct->IngresoActivo($homonimo['tipo_id_paciente'],$homonimo['paciente_id']);
				
				
				
				($estilo == "modulo_list_claro")? $estilo = "modulo_list_oscuro": $estilo = "modulo_list_claro";
				
				$urlI = $action['ver'].UrlRequest(array("tipo_id_paciente"=>$homonimo['tipo_id_paciente'],"paciente_id"=>$homonimo['paciente_id']));
				$urlIngreso = $action['verI'].UrlRequest(array("tipo_id_paciente"=>$homonimo['tipo_id_paciente'],"paciente_id"=>$homonimo['paciente_id']));
				$datos['tipo_id_paciente'] = $homonimo['tipo_id_paciente'];
				$datos['paciente_id'] = $homonimo['paciente_id'];
				
				$urlV = $action['volver'].UrlRequest($datos);
				
				$html .= "	<tr class=\"$estilo\">\n";
				$html .= "		<td class=\"label\"> ".$homonimo['tipo_id_paciente']." ".$homonimo['paciente_id']."</td>\n";
				$html .= "		<td class=\"normal_10AN\"> ".$homonimo['primer_nombre']." ".$homonimo['segundo_nombre']." ".$homonimo['primer_apellido']." ".$homonimo['segundo_apellido']."</td>\n";
				$html .= "		<td>\n";
				$html .= "			<a href=\"".$urlI."\"  target=\"ver\" onclick=\"window.open('".$urlI."','ver','toolbar=no,width=600,height=400,resizable=no,scrollbars=yes').focus(); return false;\" title=\"VER INFORMACIÓN DEL PACIENTE\">\n";
				$html .= "				<img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\">\n";
				$html .= "			</a>\n";
				$html .= "		</td>\n";
				
				if($cantidad > 0)
				{
					$html .= "		<td>\n";
					$html .= "			<a href=\"".$urlIngreso."\"  target=\"verI\" onclick=\"window.open('".$urlIngreso."','verI','toolbar=no,width=600,height=400,resizable=no,scrollbars=yes').focus(); return false;\" title=\"VER INFORMACIÓN DEL INGRESO ACTIVO\">\n";
					$html .= "				<img src=\"".GetThemePath()."/images/pparacarin.png\" border=\"0\">\n";
					$html .= "			</a>\n";
					$html .= "		</td>\n";
				}
				else
				{
					$html .= "		<td>\n";
					$html .= "			<a href=\"".$urlV."\">\n";
					$html .= "				<img src=\"".GetThemePath()."/images/atencion_citas.png\" border=\"0\">\n";
					$html .= "			</a>\n";
					$html .= "		</td>\n";
				}
				
				$html .= "	</tr>\n";
			}
			
			$html .= "	</table>\n";
			$html .= ThemeCerrarTabla();
			
			return $html;
		}
		/**********************************************************************************
		* 
		************************************************************************************/
		function FormaInformacionPaciente($paciente,$action)
		{
			$pct = new Pacientes();
			$html .= "<center>\n";
			$html .= "	<fieldset style=\"width:100%\" class=\"fieldset\"><legend>DATOS DEL PACIENTE</legend>\n";
			$html .= "		<table width=\"95%\" align=\"center\">\n";
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\">IDENTIFICACION: </td>\n";
			$html .= "			<td >".$paciente['tipo_id_paciente']." ".$paciente['paciente_id']."</td>\n";
			$html .= "		</tr>\n";
			
			if($paciente['lugar_expedicion_documento'] )
			{ 
				$html .= "		<tr class=\"label\" height=\"20\">\n";
				$html .= "			<td class=\"label\"><label id=\"expedicion\">".$obliga['lugar_expedicion_documento']['marca']." LUGAR EXPEDICION:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "    		".$paciente['lugar_expedicion_documento']."\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
			}

			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td><label id=\"primer_nombre\" class=\"label\">PACIENTE:</label></td>\n";
			$html .= "			<td>\n";
			$html .= "				".$paciente['primer_nombre']." ".$paciente['segundo_nombre']." ".$paciente['primer_apellido']." ".$paciente['segundo_apellido']."\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";

			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td><label class=\"label\" id=\"fecha_nacimiento\">FECHA NACIMIENTO:</label></td>\n";
			$html .= "			<td>\n";
			$html .= "				".$paciente['fecha_nacimiento']."\n";
			$html .= "			</td>\n";
			$html .= "    </tr>\n";
			
			if($paciente['residencia_direccion'])
			{
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"direccion_residencia\" class=\"label\">DIRECCION:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				".$paciente['residencia_direccion']."\n";
				$html .= "			</td>\n";
				$html .= "    </tr>\n";
			}
			
			if($paciente['residencia_telefono'])
			{
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"telefono_residencia\" class=\"label\">".$obliga['residencia_telefono']['marca']." TELEFONO:</label></td>\n";
				$html .= "			<td>\n";
				$html .= "				".$paciente['residencia_telefono']."\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
			}

			if($paciente['ocupacion_id'])
			{
				$paciente['nombre_ocupa'] = $pct->ObtenerNombreOcupacion($paciente['ocupacion_id']);
				$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
				$html .= "			<td><label id=\"tipo_ocupacion\" class=\"label\">OCUPACION:</label></td>\n";
				$html .= "      <td>\n";
				$html .= "				".$paciente['nombre_ocupa']."\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
			}
			
			if($paciente['observaciones'])
			{
				$html .= "    <tr class=\"label\" height=\"20\">\n";
				$html .= "			<td colspan=\"2\">OBSERVACIONES:</td>\n";
				$html .= "    </tr>\n";
				$html .= "    <tr class=\"label\" height=\"20\">\n";
				$html .= "			<td colspan=\"2\">\n";
				$html .= "				".$paciente['observaciones']."\n";
				$html .= "      </td>\n";
				$html .= "    </tr>\n";
			}
			$html .= "		</table>\n";
			$html .= "		<form name=\"formacancelar\" action=\"".$action['cerrar']."\" method=\"post\">";
			$html .= "			<table width=\"80%\" align=\"center\">\n";
			$html .= "    		<tr class=\"normal_10AN\" height=\"20\" align=\"center\">\n";
			$html .= "    			<td>\n";
			$html .= "						<input class=\"input-submit\" type=\"submit\" name=\"Cancel\" value=\"Cerrar\">\n";
			$html .= "					</td>\n";
			$html .= "  			</tr>\n";
			$html .= "			</table>\n";
			$html .= "  	</form>\n";
			$html .= "	</fieldset>\n";
			$html .= "</center>\n";
			
			return $html;
		}
		
		function FormaInformacionIngreso($ingreso,$action)
		{
			$pct = new Pacientes();
			$html .= "<center>\n";
			$html .= "	<fieldset style=\"width:50%\" class=\"fieldset\"><legend>DATOS DEL INGRESO</legend>\n";
			$html .= "		<table width=\"95%\" align=\"center\">\n";
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\">INGRESO: </td>\n";
			$html .= "			<td >".$ingreso['ingreso']."</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\">FECHA INGRESO: </td>\n";
			$html .= "			<td >".$ingreso['fecha_ingreso']."</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\">NUMERO DE CUENTA: </td>\n";
			$html .= "			<td >".$ingreso['numerodecuenta']."</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\">TOTAL CUENTA: </td>\n";
			$html .= "			<td >".$ingreso['total_cuenta']."</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"normal_10AN\" height=\"20\">\n";
			$html .= "			<td class=\"label\">PLAN: </td>\n";
			$html .= "			<td >".$ingreso['plan_descripcion']."</td>\n";
			$html .= "		</tr>\n";
			
			
			$html .= "		</table>\n";
			$html .= "		<form name=\"formacancelar\" action=\"".$action['cerrar']."\" method=\"post\">";
			$html .= "			<table width=\"80%\" align=\"center\">\n";
			$html .= "    		<tr class=\"normal_10AN\" height=\"20\" align=\"center\">\n";
			$html .= "    			<td>\n";
			$html .= "						<input class=\"input-submit\" type=\"submit\" name=\"Cancel\" value=\"Cerrar\">\n";
			$html .= "					</td>\n";
			$html .= "  			</tr>\n";
			$html .= "			</table>\n";
			$html .= "  	</form>\n";
			$html .= "	</fieldset>\n";
			$html .= "</center>\n";
			
			return $html;
		}
	}
?>