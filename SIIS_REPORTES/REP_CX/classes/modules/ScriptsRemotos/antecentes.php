<?php
	/**************************************************************************************
	* $Id: antecentes.php,v 1.4 2006/12/07 21:26:44 luis Exp $ 
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
		function IngresarAntecente($param)
		{
 			$this->IngresarDatos($param,"'0'");
			$nivel2 = $this->ConsultarAntecedente($param[0],$param[1]);
			$html = $this->CrearHtml($nivel2,$param);
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function OcultarAntecente($param)
		{
			$this->ActualizarDatos($param);
			$nivel2 = $this->ConsultarAntecedente($param[0],$param[1]);
			$html = $this->CrearHtml($nivel2,$param);
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ActualizarDatos($param)
		{
			$sql .= "UPDATE hc_antecedentes_personales ";
			$sql .= "SET		ocultar = '".$param[6]."' ";
			$sql .= "WHERE	hc_tipo_antecedente_personal_id = ".$param[0]." ";
			$sql .= "AND		hc_tipo_antecedente_detalle_personal_id = ".$param[1]." ";
			$sql .= "AND		hc_antecedente_personal_id = ".$param[5]." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			$rst->Close();
			
			return true;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function IngresarDatos($param,$oculto)
		{
			$sql .= "INSERT INTO hc_antecedentes_personales(";
			$sql .= "				detalle,";
			$sql .= "				evolucion_id,";
			$sql .= "				hc_tipo_antecedente_personal_id,";
			$sql .= "				destacar,";
			$sql .= "				hc_tipo_antecedente_detalle_personal_id,";
			$sql .= "				sw_riesgo,";
			$sql .= "				ocultar,";
			$sql .= "				fecha_registro";
			$sql .= "				) ";
			$sql .= "VALUES( '".$param[6]."', ";
			$sql .= "				  ".SessionGetVar("EvolucionHc").", ";
			$sql .= "				  ".$param[0].", ";
			$sql .= "				 '".$param[7]."', ";
			$sql .= "				  ".$param[1].", ";
			$sql .= "				 '".$param[5]."',";
			$sql .= "				  ".$oculto.",";
			$sql .= "					NOW()";
			$sql .= "				);";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			$rst->Close();
			
			return true;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function IngresarAntecenteFami($param)
		{
 			$this->CrearAntecente($param,"'0'");
			$nivel2 = $this->ConsultarAntecedentesFami($param[0],$param[1]);
			$html = $this->CrearHtml($nivel2,$param,"OcultarAntecedenteJs");
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearAntecente($param,$oculto)
		{
			$sql .= "INSERT INTO hc_antecedentes_familiares(";
			$sql .= "				detalle,";
			$sql .= "				evolucion_id,";
			$sql .= "				hc_tipo_antecedente_familiar_id,";
			$sql .= "				destacar,";
			$sql .= "				hc_tipo_antecedente_detalle_familiar_id,";
			$sql .= "				sw_riesgo,";
			$sql .= "				ocultar,";
			$sql .= "				fecha_registro";
			$sql .= "				) ";
			$sql .= "VALUES( '".$param[6]."', ";
			$sql .= "				  ".SessionGetVar("EvolucionHc").", ";
			$sql .= "				  ".$param[0].", ";
			$sql .= "				 '".$param[7]."', ";
			$sql .= "				  ".$param[1].", ";
			$sql .= "				 '".$param[5]."',";
			$sql .= "				  ".$oculto.",";
			$sql .= "					NOW()";
			$sql .= "				);";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			$rst->Close();
			
			return true;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function OcultarAntecenteFami($param)
		{
			$this->ActualizarAntecedenteFami($param);
			$nivel2 = $this->ConsultarAntecedentesFami($param[0],$param[1]);
			$html = $this->CrearHtml($nivel2,$param,"OcultarAntecedenteFami");
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ActualizarAntecedenteFami($param)
		{
			$sql .= "UPDATE hc_antecedentes_familiares ";
			$sql .= "SET		ocultar = '".$param[6]."' ";
			$sql .= "WHERE	hc_tipo_antecedente_familiar_id = ".$param[0]." ";
			$sql .= "AND		hc_tipo_antecedente_detalle_familiar_id = ".$param[1]." ";
			$sql .= "AND		hc_antecedente_familiar_id = ".$param[5]." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			$rst->Close();
			
			return true;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearHtml($nivel2,$param,$metjs = "OcultarAntecedente")
		{
			$path = SessionGetVar("RutaImg");
			$i=0;
			
			$htmlO = "";
			$htmlV = "";
			foreach($nivel2 as $nivel3)
			{
				$op = "&nbsp;"; $styl = "style=\"text-transform:capitalize;\"";
				
				$arregloJs = "new Array('".$nivel3['hctap']."','".$nivel3['hctad']."','".$param[2]."','".$param[3]."','".$param[4]."','".$nivel3['hcid']."'";
					
				$check  = "<a href=\"javascript:CrearArregloCapas(new Array('Antecedente".$param[4]."','Ocultos".$param[4]."'));$metjs(".$arregloJs.",'1'))\" title=\"Ocultar Antecedente\">";
				$check .= "	<img src=\"".$path."/images/checkno.png\" height=\"14\" border=\"0\"></a>";
						
				$check1  = "<a href=\"javascript:CrearArregloCapas(new Array('Antecedente".$param[4]."','Ocultos".$param[4]."'));$metjs(".$arregloJs.",'0'))\" title=\"Mostrar Antecedente\">";
				$check1 .= "	<img src=\"".$path."/images/checkS.gif\" height=\"14\" width=\"14\" border=\"0\"></a>";
				
				if($nivel3['sw_riesgo'] == '0') $op = "NO";
				else if($nivel3['sw_riesgo'] == '1') $op = "SI";
				
				if(!$nivel3['detalle'])
				{
					$op = "&nbsp;";$check = "&nbsp;";
				}
				
				if($nivel3['destacar'] == '1') $styl = " style=\"font-weight : bold; text-transform:capitalize;\" ";
				
				$styl1 = " style=\"color:#000066;font-weight : bold; \" ";
				if($nivel3['riesgo'] == $nivel3['sw_riesgo']) $styl1 = " style=\"color:#C40000;font-weight : bold; \" ";
				
				if($nivel3['ocultar'] == '0')
				{
					$htmlV .= "									<tr class=\"".$param[2]."\">\n";
					$htmlV .= "										<td align=\"center\" $styl1 width=\"15%\"  >$op</td>\n";
					$htmlV .= "										<td align=\"justify\" $styl width=\"55%\" >".$nivel3['detalle']."</td>\n";
					$htmlV .= "										<td align=\"center\" $styl width=\"20%\">".$nivel3['fecha']."</td>\n";
					$htmlV .= "										<td align=\"center\" $styl width=\"10%\">$check</td>\n";
					$htmlV .= "									</tr>\n";
				}
				else if($nivel3['ocultar'] == '1')
				{
					$htmlO .= "						<tr class=\"".$param[3]."\">\n";
					$htmlO .= "							<td align=\"center\" $styl1 width=\"15%\"  >$op</td>\n";
					$htmlO .= "							<td align=\"justify\" $styl width=\"55%\" >".$nivel3['detalle']."</td>\n";
					$htmlO .= "							<td align=\"center\" $styl width=\"20%\">".$nivel3['fecha']."</td>\n";
					$htmlO .= "							<td align=\"center\" $styl width=\"10%\">$check1</td>\n";
					$htmlO .= "						</tr>\n";
				}
				
				$i++;
			}
			
			if($htmlV != "")
			{
				$htmlV  = "								<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n".$htmlV;			
				$htmlV .= "								</table>\n";
			}
			if($htmlO != "")
			{
				$htmlO  = "								<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n".$htmlO;
				$htmlO .= "								</table>\n";
			}
			
			$html .= "								<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n";
			$html .= "									<tr class=\"formulacion_table_list\" >\n";
			$html .= "										<td align=\"center\" width=\"15%\" >OP.</td>\n";
			$html .= "										<td align=\"center\" width=\"55%\" >DETALLE</td>\n";
			$html .= "										<td align=\"center\" width=\"20%\" >F. REGIS</td>\n";				
			$html .= "										<td align=\"center\" width=\"10%\" >OCUL</td>\n";
			$html .= "									</tr>\n";
			$html .= "								</table>\n";
			
			return $htmlV."ç".$htmlO."ç".$html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ConsultarAntecedente($hctap,$hctad)
		{			
			$sql .= "SELECT	HD.nombre_tipo, ";
			$sql .= "				HD.riesgo,  ";
			$sql .= "				HA.detalle,  ";
			$sql .= "				HA.destacar, ";
			$sql .= "				HE.evolucion_id,  ";
			$sql .= "				HD.hc_tipo_antecedente_personal_id AS hctap, ";
			$sql .= "				HD.hc_tipo_antecedente_detalle_personal_id AS hctad, ";
			$sql .= "			 	HZ.sexo, ";
			$sql .= "			 	HZ.edad_min, ";
			$sql .= "			 	HZ.edad_max, ";
			$sql .= "			 	HA.sw_riesgo, ";
			$sql .= "			 	TO_CHAR(HA.fecha_registro,'YYYY-MM-DD') AS fecha, ";
			$sql .= "				COALESCE(HA.ocultar,'0') AS ocultar, ";
			$sql .= "				HA.hc_antecedente_personal_id AS hcid ";
			$sql .= "FROM 	hc_evoluciones HE JOIN ingresos IG ";
			$sql .= "				ON(	HE.evolucion_id <= ".SessionGetVar("EvolucionHc")." AND "; 
			$sql .= "						HE.ingreso = IG.ingreso AND ";
			$sql .= " 					IG.paciente_id = '".SessionGetVar("IdPaciente")."' AND ";
			$sql .= " 					IG.tipo_id_paciente = '".SessionGetVar("TipoPaciente")."') ";
			$sql .= "				JOIN hc_antecedentes_personales HA ";
			$sql .= "				ON(	HE.evolucion_id = HA.evolucion_id ) ";
			$sql .= "				RIGHT JOIN hc_tipos_antecedentes_detalle_personales HD ";
			$sql .= "				ON(	HA.hc_tipo_antecedente_detalle_personal_id = HD.hc_tipo_antecedente_detalle_personal_id AND ";
			$sql .= "						HA.hc_tipo_antecedente_personal_id = HD.hc_tipo_antecedente_personal_id )";
			$sql .= "				RIGHT JOIN hc_tipos_antecedentes_personales HZ ";
			$sql .= "				ON(	HD.hc_tipo_antecedente_personal_id = HZ.hc_tipo_antecedente_personal_id) ";
			$sql .= "WHERE	HD.hc_tipo_antecedente_personal_id = ".$hctap." ";
			$sql .= "AND		HD.hc_tipo_antecedente_detalle_personal_id = ".$hctad." ";
			$sql .= "ORDER BY HD.hc_tipo_antecedente_personal_id; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$antecedentes = array();
			while(!$rst->EOF)
			{
				$antecedentes[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			return $antecedentes;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ConsultarAntecedentesFami($hctap,$hctad)
		{
			$sql .= "SELECT	HD.nombre_tipo,";
			$sql .= "				HD.riesgo, ";
			$sql .= "				HF.detalle, "; 
			$sql .= "				HF.destacar, ";
			$sql .= "				HE.evolucion_id,"; 
			$sql .= "				HD.hc_tipo_antecedente_familiar_id AS hctap, ";
			$sql .= "				HD.hc_tipo_antecedente_detalle_familiar_id AS hctad, ";
			$sql .= "				HT.descripcion,";
			$sql .= "				HT.sexo, "; 
			$sql .= "				HT.edad_min,"; 
			$sql .= "				HT.edad_max,"; 
			$sql .= "				HF.sw_riesgo,"; 
			$sql .= "			 	TO_CHAR(HF.fecha_registro,'YYYY-MM-DD') AS fecha, ";
			$sql .= "				COALESCE(HF.ocultar,'0') AS ocultar, ";
			$sql .= "				HF.hc_antecedente_familiar_id 	 AS hcid ";
			$sql .= "FROM		hc_evoluciones HE "; 
			$sql .= "				JOIN ingresos IG "; 
			$sql .= "				ON(	HE.evolucion_id <= ".SessionGetVar("EvolucionHc")." AND "; 
			$sql .= "						HE.ingreso = IG.ingreso AND ";
			$sql .= " 					IG.paciente_id = '".SessionGetVar("IdPaciente")."' AND ";
			$sql .= " 					IG.tipo_id_paciente = '".SessionGetVar("TipoPaciente")."') ";
			$sql .= "				JOIN hc_antecedentes_familiares HF "; 
			$sql .= "				ON(HE.evolucion_id = HF.evolucion_id) ";
			$sql .= "				RIGHT JOIN hc_tipos_antecedentes_detalle_familiares HD "; 
			$sql .= "				ON(	HF.hc_tipo_antecedente_detalle_familiar_id = HD.hc_tipo_antecedente_detalle_familiar_id AND ";
			$sql .= "						HF.hc_tipo_antecedente_familiar_id = HD.hc_tipo_antecedente_familiar_id) ";
			$sql .= "				RIGHT JOIN hc_tipos_antecedentes_familiares HT ";
			$sql .= "				ON(	HD.hc_tipo_antecedente_familiar_id = HT.hc_tipo_antecedente_familiar_id) ";
			$sql .= "WHERE	HD.hc_tipo_antecedente_familiar_id = ".$hctap." ";
			$sql .= "AND		HD.hc_tipo_antecedente_detalle_familiar_id = ".$hctad." ";
			$sql .= "ORDER BY HD.hc_tipo_antecedente_familiar_id, HD.nombre_tipo;";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$antecedentes = array();
			while(!$rst->EOF)
			{
				$antecedentes[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			return $antecedentes;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function BusquedaPatronConsumo($tipo_patron)
    {
			$sql .= "SELECT * ";
			$sql .= "FROM 	hc_tipos_patron_consumo ";
      $sql .= "WHERE 	indice_patron = '$tipo_patron'";
      $sql .= "ORDER BY indice_orden;";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$patron = array();
			while(!$rst->EOF)
			{
				$patron[$rst->fields[0]] = $rst->fields[1];
				$rst->MoveNext();
			}
			return $patron;
    }
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearAntecedenteT($param)
		{
			$sustancias = $this->BusquedaTotalToxicos($param[0]);
			$patron = $this->BusquedaPatronConsumo($param[1]);
			$ultimo = $this->BusquedaUltimoConsumo();
			$problemas = $this->BusquedaProblemasxConsumo();
			
			$est = "style=\"text-align:left\" ";
			$html .= "<form name=\"atencions\" action=\"\" method=\"post\">\n";
			$html .= "	<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "			<td class=\"modulo_table_list_title\" $est>PATRON DE CONSUMO</td>\n";
			$html .= "			<td colspan=\"2\">\n";
			$html .= " 				<select name=\"patron\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">--SELECCIONE--</option>\n";
      
			foreach($patron as $k=>$v)
			{
				$ck = "";
				if($sustancias['hc_tipos_patron_consumos_id'] == $k) 	$ck = "selected";
				
				$html .= "					<option value=\"".$k."\" $ck>".$v."</option>\n";
			}
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "			<td class=\"modulo_table_list_title\" $est>ULTIMO CONSUMO</td>\n";
			$html .= "			<td colspan=\"2\">\n";
			$html .= " 				<select name=\"ultimosustancia\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">--SELECCIONE--</option>\n";
			foreach($ultimo as $k=>$v)
			{
				$ck = "";
				if($sustancias['hc_tipos_ultimo_consumo_id'] == $k) $ck = "selected";
				
				$html .= "					<option value=\"".$k."\" $ck>".$v."</option>\n";
			}
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "			<td class=\"modulo_table_list_title\" $est>PROBLEMAS POR CONSUMO</td>\n";
			$html .= "			<td colspan=\"2\">\n";
			$html .= "				<select name=\"problemasxconsumo\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">--SELECCIONE--</option>\n";
			foreach($problemas as $k=>$v)
			{
				$ck = "";
				if($sustancias['hc_tipos_problemasxconsumo_id'] == $k) $ck = "selected";
				
				$html .= "					<option value=\"$k\" $ck >$v</option>\n";
				
			}
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "			<td class=\"modulo_table_list_title\" $est>EDAD DE INICIO</td>\n";
			$html .= "			<td colspan=\"2\" >\n";
			$html .= "				<input type\"text\" name=\"Einicio\" maxlength=\"3\" size=\"4\" class=\"input-text\" value=\"".$sustancias['edad_inicio']."\" onkeypress=\"return acceptNum(event)\"><b>Años</b>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "			<td class=\"modulo_table_list_title\" $est>TIEMPO DE CONSUMO</td>\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"hidden\" name=\"consecutivo\" value=\"".$sustancias['id']."\">\n";
			$html .= "				<input type=\"hidden\" name=\"sustanciaid\" value=\"".$param[0]."\">\n";
			$html .= "				<input type=\"text\" name=\"consumotiempo\"  maxlength=\"3\" size=\"5\" class=\"input-text\" value=\"".$sustancias['tiempo_consumo']."\" onkeypress=\"return acceptNum(event)\">\n";
			$html .= "			</td>\n";
			$a = $m = $d = "";
			switch($sustancias['tiempo_consumo_tipo'])
			{
				case 'A': $a = "selected"; break;
				case 'M': $m = "selected"; break;
				case 'D': $d = "selected"; break;
			}
			$html .= "			<td>\n";
			$html .= "				<select name=\"tiempoconsumotipo\" class=\"select\">\n";
			$html .= "					<option value=\"A\" $a>Años</option>\n";
			$html .= "					<option value=\"M\" $m>Meses</option>\n";
			$html .= "					<option value=\"D\" $d>Dias</option>\n";
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			
			$metodo = 'IngresarDatosT';
			if($sustancias['tiempo_consumo_tipo']) $metodo = 'ActualizarDatosT';
			
			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "			<td colspan=\"3\" align=\"center\">\n";
			$html .= "				<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Guardar\" onclick=\"EvaluarConsumoT(document.atencions,'$metodo')\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function EliminarInstitucion($param)
		{
			print_r($param);
			echo $param;
			$sql  = "DELETE FROM hc_antecedentes_personales_instituciones ";
			$sql .= "WHERE	hc_antecedente_personal_institucion_id = ".$param[0].";";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$instituciones = $this->BusquedaInstituciones();
			
			$html = "";
			
			if(SessionGetVar("SwSiquiatria") == 1)
			{
				$html .= "<center>\n";
				$html .= "	<a href=\"javascript:IngresarInstitucion()\" class=\"label\">ADICIONAR PROGRAMAS DE REHABILITACION</a>\n";
				$html .= "</center>\n";
			}
			
			if(!empty($instituciones))
			{
				$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "		<tr class=\"formulacion_table_list\">\n";
				$html .= "			<td align=\"center\" colspan=\"4\">PROGRAMAS DE REHABILITACION</td>\n";
				$html .= "		</tr>\n";
				$html .= "		<tr class=\"modulo_table_list_title\">\n";
				$html .= "			<td width=\"1%\"></td>\n";
				$html .= "			<td align=\"center\">INSTITUCIÓN</td>\n";
				$html .= "			<td align=\"center\">ESTANCIA</td>\n";
				
				if(SessionGetVar("SwSiquiatria") == 1)
					$html .= "			<td align=\"center\" width=\"10%\">OPCIÓN</td>\n";
				
				$html .= "		</tr>\n";
			
				$j=0;
				foreach($instituciones as $k=>$nivel1)
				{
	        if($j % 2 == 0)	
						$est = 'hc_submodulo_list_oscuro'; 
					else 
						$est = 'hc_submodulo_list_claro';
						
					$j++; 
					$estancia = "Años";
					if($nivel1['tipo_estancia_institucion']=='M') $estancia = " Meses";
					else if($nivel1['tipo_estancia_institucion']=='D')	$estancia = " Dias";
					
					$html .= "		<tr class=\"$est\">\n";
					$html .= "			<td class=\"label\">$j</td>\n";
					$html .= "			<td class=\"label\">".$nivel1['nombre_institucion']."</td>\n";
					$html .= "			<td class=\"label\">".$nivel1['estancia_institucion']."$estancia</td>\n";
					
					if(SessionGetVar("SwSiquiatria") ==1)
					{
						$id = $nivel1['hc_antecedente_personal_institucion_id'];
					
						$html .= "			<td align=\"center\" >\n";
						$html .= "				<a href=\"javascript:EliminarInstitucion('$id','".strtoupper($nivel1['nombre_institucion'])."','$j')\">\n";
						$html .= "					<img src=\"".SessionGetVar("RutaImg")."/images/elimina.png\"  border='0'>\n";
						$html .= "				</a>\n";
						$html .= "			</td>\n";
					}
					$html .= "		</tr>\n";
				}
				$html .= "	</table>\n";
			}
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function IngresarDatosT($param)
		{
      $sql .= "INSERT INTO hc_antecedentes_personales_toxico_alergicos ";
			$sql .= "		(	hc_tipos_sustancias_adictivas_id,  ";
			$sql .= "			hc_tipos_patron_consumos_id,  ";
			$sql .= "			hc_tipos_ultimo_consumo_id,  ";
			$sql .= "			hc_tipos_problemasxconsumo_id,  ";
			$sql .= "			edad_inicio,  ";
			$sql .= "			tiempo_consumo,  ";
			$sql .= "			tiempo_consumo_tipo, "; 
			$sql .= "			evolucion_id)  ";
			$sql .= "	VALUES(  ";
			$sql .= "			".$param[7].",  ";
			$sql .= "			".$param[0].",  ";
			$sql .= "			".$param[1].",  ";
			$sql .= "			".$param[2].",  ";
			$sql .= "			".$param[3].",  ";
			$sql .= "			".$param[4].",  ";
			$sql .= "			'".$param[5]."',  ";
			$sql .= "			".SessionGetVar("EvolucionHc")." ";
			$sql .= "			) ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$nivel1 = $this->ObtenerInformacionToxicos($param[7]);
			
			$html .= "			<td >\n";
			$html .= "				<a href=\"javascript:CrearIngresoDatos(new Array('".$nivel1['hc_tipos_sustancias_adictivas_id']."','".$nivel1['tipo_patronconsumo']."'));\" class=\"label\">".$nivel1['descripcion']."</a>\n";
			$html .= "			</td>\n";
			$html .= "			<td class=\"label\">".$nivel1['patron']."</td>\n";
			$html .= "			<td class=\"label\">".$nivel1['ultimo_consumo']."</td>\n";
			$html .= "			<td class=\"label\">".$nivel1['problemas']."</td>\n";
			$html .= "			<td class=\"label\">".$nivel1['edad_inicio']."</td>\n";
			$html .= "			<td class=\"label\">".$nivel1['tiempo_consumo']." ".$nivel1['tiempo_consumo_tipo']."</td>\n";
			
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ActualizarDatosT($param)
		{
			$sql .= "UPDATE hc_antecedentes_personales_toxico_alergicos ";
			$sql .= "SET		hc_tipos_patron_consumos_id = ".$param[0].",  ";
			$sql .= "				hc_tipos_ultimo_consumo_id = ".$param[1].",  ";
			$sql .= "				hc_tipos_problemasxconsumo_id = ".$param[2].",  ";
			$sql .= "				edad_inicio = ".$param[3].",  ";
			$sql .= "				tiempo_consumo = ".$param[4].",  ";
			$sql .= "				tiempo_consumo_tipo = '".$param[5]."', "; 
			$sql .= "				evolucion_id = ".SessionGetVar("EvolucionHc")."  ";
			$sql .= "WHERE	hc_tipos_sustancias_adictivas_id = ".$param[7]." ";
			$sql .= "AND		hc_antecedentes_personales_toxico_alergicos_id = ".$param[6]."		";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$nivel1 = $this->ObtenerInformacionToxicos($param[7],$param[6]);
			
			$html .= "			<td >\n";
			$html .= "				<a href=\"javascript:CrearIngresoDatos(new Array('".$nivel1['hc_tipos_sustancias_adictivas_id']."','".$nivel1['tipo_patronconsumo']."'));\" class=\"label\">".$nivel1['descripcion']."</a>\n";
			$html .= "			</td>\n";
			$html .= "			<td class=\"label\">".$nivel1['patron']."</td>\n";
			$html .= "			<td class=\"label\">".$nivel1['ultimo_consumo']."</td>\n";
			$html .= "			<td class=\"label\">".$nivel1['problemas']."</td>\n";
			$html .= "			<td class=\"label\">".$nivel1['edad_inicio']."</td>\n";
			$html .= "			<td class=\"label\">".$nivel1['tiempo_consumo']." ".$nivel1['tiempo_consumo_tipo']."</td>\n";
			
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function BusquedaTotalToxicos($sustancia)
    {
			$sql .= "SELECT	HA.hc_tipos_sustancias_adictivas_id,  ";
			$sql .= "				HA.hc_tipos_patron_consumos_id,  ";
			$sql .= "				HA.hc_tipos_ultimo_consumo_id, "; 
			$sql .= "				HA.hc_tipos_problemasxconsumo_id, "; 
			$sql .= "				HA.edad_inicio,  ";
			$sql .= "				HA.tiempo_consumo,  ";
			$sql .= "				HA.tiempo_consumo_tipo,  ";
			$sql .= "				HA.hc_antecedentes_personales_toxico_alergicos_id AS id "; 				
			$sql .= "FROM 	hc_antecedentes_personales_toxico_alergicos HA,  ";
			$sql .= "				hc_evoluciones HE,  ";
			$sql .= "				ingresos IG  ";
			$sql .= "WHERE 	IG.ingreso = ".SessionGetVar("IngresoHc")."  ";
			$sql .= "AND 		IG.ingreso = HE.ingreso  ";
			$sql .= "AND 		HE.evolucion_id = HA.evolucion_id ";
			$sql .= "AND		HA.hc_tipos_sustancias_adictivas_id = ".$sustancia." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
    }
		/********************************************************************************
		*
		*********************************************************************************/
		function BusquedaUltimoConsumo()
    {
      $sql = "SELECT * FROM hc_tipos_ultimo_consumo;";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$ultimo = array();
			while(!$rst->EOF)
      {
        $ultimo[$rst->fields[0]] = $rst->fields[1];
        $rst->MoveNext();
      }
      $rst->Close();
			return $ultimo;
    }
		/********************************************************************************
		*
		*********************************************************************************/
		function BusquedaProblemasxConsumo()
    {
      $sql = "SELECT * FROM hc_tipos_problemasxconsumo;";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$problemas = array();
			while(!$rst->EOF)
      {
        $problemas[$rst->fields[0]] = $rst->fields[1];
        $rst->MoveNext();
      }
      
			$rst->Close();
			return $problemas;
    }
		/************************************************************************************
		*
		*************************************************************************************/
		function ObtenerInformacionToxicos($sustancia,$id)
    {
			$sql .= "SELECT	HS.descripcion, ";
			$sql .= "				HS.hc_tipos_sustancias_adictivas_id, ";
			$sql .= "				HS.tipo_patronconsumo, ";
			$sql .= "				HA.patron, ";
			$sql .= "				HA.ultimo_consumo,  ";
			$sql .= "				HA.problemas,  ";
			$sql .= "				HA.edad_inicio,  ";
			$sql .= "				HA.tiempo_consumo,  ";
			$sql .= "				HA.tiempo_consumo_tipo ";
			$sql .= "FROM		hc_tipos_sustancias_adictivas HS, ";
			$sql .= "				(	SELECT  HT.descripcion AS patron, ";
			$sql .= "									HU.descripcion AS ultimo_consumo,  ";
			$sql .= "									HX.descripcion AS problemas,  ";
			$sql .= "									HA.edad_inicio ||' Años' AS edad_inicio,  ";
			$sql .= "									HA.tiempo_consumo,  ";
			$sql .= "									CASE 	WHEN HA.tiempo_consumo_tipo = 'A' THEN 'AÑOS'";
			$sql .= "												WHEN HA.tiempo_consumo_tipo = 'M' THEN 'MESES'";
			$sql .= "												WHEN HA.tiempo_consumo_tipo = 'D' THEN 'DIAS' END AS tiempo_consumo_tipo, ";
			$sql .= "									HA.hc_tipos_sustancias_adictivas_id ";
			$sql .= "					FROM		hc_antecedentes_personales_toxico_alergicos HA, ";
			$sql .= "									hc_evoluciones HE, ";
			$sql .= "									hc_tipos_patron_consumo HT, ";
			$sql .= "									hc_tipos_ultimo_consumo HU, ";
			$sql .= "									hc_tipos_problemasxconsumo HX ";
			$sql .= "					WHERE		HT.hc_tipos_patron_consumos_id = HA.hc_tipos_patron_consumos_id ";
			$sql .= "					AND			HU.hc_tipos_ultimo_consumo_id = HA.hc_tipos_ultimo_consumo_id ";
			$sql .= "					AND			HX.hc_tipos_problemasxconsumo_id = HA.hc_tipos_problemasxconsumo_id ";
			if($id != "")
				$sql .= "					AND			HA.hc_antecedentes_personales_toxico_alergicos_id = ".$id."		";
			$sql .= "					AND 		HE.evolucion_id = HA.evolucion_id) AS HA ";
			$sql .= "WHERE	HS.hc_tipos_sustancias_adictivas_id = HA.hc_tipos_sustancias_adictivas_id ";
			$sql .= "AND		HS.hc_tipos_sustancias_adictivas_id = ".$sustancia."";
			if(!$rst = $this->ConexionBaseDatos($sql.$where))	return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    }
		/************************************************************************************
		*
		*************************************************************************************/
		function BusquedaInstituciones()
		{
			$sql .= "SELECT	HI.hc_antecedente_personal_institucion_id, ";
			$sql .= "				HI.nombre_institucion,  ";
			$sql .= "				Hi.estancia_institucion,  ";
			$sql .= "				HI.tipo_estancia_institucion, "; 
			$sql .= "				CASE WHEN HI.evolucion_id=".SessionGetVar("EvolucionHc")." THEN '1'  ";
			$sql .= "						 ELSE '0' END AS esta  ";
			$sql .= "FROM		hc_antecedentes_personales_instituciones HI,  ";
			$sql .= "				hc_evoluciones HE,  ";
			$sql .= "				ingresos IG ";
			$sql .= "WHERE 	IG.ingreso = ".SessionGetVar("IngresoHc")."  ";
			$sql .= "AND 		IG.ingreso = HE.ingreso  ";
			$sql .= "AND 		HE.evolucion_id = HI.evolucion_id; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql.$where))	return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function AdicionarInstitucion($param) 
		{
			$sql .= "INSERT into hc_antecedentes_personales_instituciones ( ";
			$sql .= "				nombre_institucion,  ";
			$sql .= "				estancia_institucion,  ";
			$sql .= "				tipo_estancia_institucion, "; 
			$sql .= "				evolucion_id )  ";
			$sql .= "VALUES (  ";
			$sql .= "				'".$param[0]."',  ";
			$sql .= "				 ".$param[1].",  ";
			$sql .= "				'".$param[2]."',  ";
			$sql .= "				 ".SessionGetVar("EvolucionHc")." ) ";
			
			if(!$rst = $this->ConexionBaseDatos($sql.$where))	return false;
			
			$instituciones = $this->BusquedaInstituciones();
			
			$html = "";
			
			if(SessionGetVar("SwSiquiatria") == 1)
			{
				$html .= "<center>\n";
				$html .= "	<a href=\"javascript:IngresarInstitucion()\" class=\"label\">ADICIONAR PROGRAMAS DE REHABILITACION</a>\n";
				$html .= "</center>\n";
			}
			
			if(!empty($instituciones))
			{
				$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "		<tr class=\"formulacion_table_list\">\n";
				$html .= "			<td align=\"center\" colspan=\"4\">PROGRAMAS DE REHABILITACION</td>\n";
				$html .= "		</tr>\n";
				$html .= "		<tr class=\"modulo_table_list_title\">\n";
				$html .= "			<td width=\"1%\"></td>\n";
				$html .= "			<td align=\"center\">INSTITUCIÓN</td>\n";
				$html .= "			<td align=\"center\">ESTANCIA</td>\n";
				
				if(SessionGetVar("SwSiquiatria") == 1)
					$html .= "			<td align=\"center\" width=\"10%\">OPCIÓN</td>\n";
				
				$html .= "		</tr>\n";
			
				$j=0;
				foreach($instituciones as $k=>$nivel1)
				{
	        if($j % 2 == 0)	
						$est = 'hc_submodulo_list_oscuro'; 
					else 
						$est = 'hc_submodulo_list_claro';
						
					$j++; 
					$estancia = "Años";
					if($nivel1['tipo_estancia_institucion']=='M') $estancia = " Meses";
					else if($nivel1['tipo_estancia_institucion']=='D')	$estancia = " Dias";
					
					$html .= "		<tr class=\"$est\">\n";
					$html .= "			<td class=\"label\">$j</td>\n";
					$html .= "			<td class=\"label\">".$nivel1['nombre_institucion']."</td>\n";
					$html .= "			<td class=\"label\">".$nivel1['estancia_institucion']."$estancia</td>\n";
					
					if(SessionGetVar("SwSiquiatria") ==1)
					{
						$id = $nivel1['hc_antecedente_personal_institucion_id'];
					
						$html .= "			<td align=\"center\" >\n";
						$html .= "				<a href=\"javascript:EliminarInstitucion('$id','".strtoupper($nivel1['nombre_institucion'])."','$j')\">\n";
						$html .= "					<img src=\"".SessionGetVar("RutaImg")."/images/elimina.png\"  border='0'>\n";
						$html .= "				</a>\n";
						$html .= "			</td>\n";
					}
					$html .= "		</tr>\n";
				}
				$html .= "	</table>\n";
			}
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
	$oRS = new procesos_admin( array( 'ActivarMenu', 'CrearTabla'));
	$oRS->action();	
?>