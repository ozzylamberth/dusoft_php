<?php
/**
* @package IPSOFT-SIIS
*
* @author    Mauricio Bejarano L. 
* @version   $Revision: 1.7 $
* @package   LiquidacionPrecios
* 
*
*/


class app_LiquidacionPrecios_userclasses_HTML extends app_LiquidacionPrecios_user
{
	//Constructor de la clase app_LiquidacionPrecios_userclasses_HTML
	function app_LiquidacionPrecios_userclasses_HTML()
	{
							$this->salida='';
							$this->app_LiquidacionPrecios_user();
							return true;
	}

	//aoltu
	function SetStyle($campo)
	{
			if ($this->frmError[$campo] || $campo=="MensajeError")
			{
					if ($campo=="MensajeError")
					{
							$arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
							return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
			}
			return ("label");
	}



function Consultar_Cumplimiento()
{
    $this->salida= ThemeAbrirTablaSubModulo('CONSULTA LIQUIDACION DE CARGOS');
		$accion=ModuloGetURL('app','LiquidacionPrecios','user','BuscaDatos');
		
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
		
		$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td colspan = 10 align=\"center\" width=\"100%\">DATOS BASICOS</td>";
		$this->salida.="</tr>";
				
			$planes=$_REQUEST['plan'];
			$grupo_tarifario=$_REQUEST['grupo_tarifario'];
			$subgrupo_tarifario=$_REQUEST['subgrupo_tarifario'];
			$grupo_tipo_cargo=$_REQUEST['grupo_tipo_cargo'];
			$tipo_cargo=$_REQUEST['tipo_cargo'];
			$mostrar=$_REQUEST['mostrar'];
			
			//,array('plan'=>$plan,'grupo_tarifario'=>$grupo_tarifario,'subgrupo_tarifario'=>$subgrupo_tarifario,'grupo_tipo_cargo'=>$grupo_tipo_cargo,'tipo_cargo'=>$tipo_cargo)
			$this->salida .= "  <script>";
			$this->salida.=  "  function filtrotarifario(valor)"."\n";
			$this->salida.='    {'."\n";
			$accion2=ModuloGetUrl('app','LiquidacionPrecios','user');
			$this->salida.='    window.location.href="'.$accion2.'&grupo_tarifario="+valor+"&plan="+document.forma.plan.value+"&grupo_tarifario="+document.forma.grupo_tarifario.value+"&subgrupo_tarifario="+document.forma.subgrupo_tarifario.value+"&grupo_tipo_cargo="+document.forma.grupo_tipo_cargo.value+"&tipo_cargo="+document.forma.tipo_cargo.value';
			//$this->salida.=' document.forma.submit();'."\n";
			$this->salida.=' ;'."\n";
			$this->salida.=' }'."\n";
			$this->salida.= "</script>";
			
			$this->salida .= "  <script>";
			$this->salida.=  "  function filtrogrupotipocargo(valor)"."\n";
			$this->salida.='    {'."\n";
			$accion3=ModuloGetUrl('app','LiquidacionPrecios','user');
			//$this->salida.='    window.location.href="'.$accion3.'&grupo_tipo_cargo="+valor';
			$this->salida.='    window.location.href="'.$accion2.'&grupo_tarifario="+valor+"&plan="+document.forma.plan.value+"&grupo_tarifario="+document.forma.grupo_tarifario.value+"&subgrupo_tarifario="+document.forma.subgrupo_tarifario.value+"&grupo_tipo_cargo="+document.forma.grupo_tipo_cargo.value+"&tipo_cargo="+document.forma.tipo_cargo.value';
			$this->salida.=' ;'."\n";
			$this->salida.=' }'."\n";
			$this->salida.= "</script>";
			
			$this->salida.="<tr>";
			//Plan
			$this->salida.="  <td align=\"center\" class=\"modulo_table_title\">PLAN</td>";
			$this->salida .= "<td><select name=\"plan\" class=\"select\">";
			$plan=$this->Plan();
			$this->salida .=" <option value= \"-1\" >Seleccione</option>";
			for($j=0;$j< sizeof($plan);$j++){
				if($plan[$j][plan_id]==$_REQUEST['plan']){
					$this->salida .=" <option value= '".$plan[$j][plan_id]."' selected>".$plan[$j][plan_descripcion]."</option>";
				}else{
					$this->salida .=" <option value= '".$plan[$j][plan_id]."'>".$plan[$j][plan_descripcion]."</option>";
				}
			}
			$this->salida .= "   </select>";
			$this->salida .= "  </td>";
			$this->salida.="</tr>";	
			
			//grupo tarifario
			$this->salida.="<tr>";
			$this->salida.="  <td align=\"center\" class=\"modulo_table_title\">GRUPO TARIFARIO</td>";
				$this->salida .= "  <td >";
				$this->salida .= "  	<select name=\"grupo_tarifario\" onchange=\"filtrotarifario(this.value)\" class=\"select\">";
				$this->salida .= "  		<option value=\"\" selected>SIN DETERMINAR</option>";
				$Gtarifario=$this->Gtarifario();
				for($i=0;$i<sizeof($Gtarifario);$i++){
					if($Gtarifario[$i][grupo_tarifario_id]==$_REQUEST['grupo_tarifario']){
						$this->salida .="		<option value=\"".$Gtarifario[$i][grupo_tarifario_id]."\" selected>".$Gtarifario[$i][grupo_tarifario_descripcion]."</option>";
					}else{
						$this->salida .="		<option value=\"".$Gtarifario[$i][grupo_tarifario_id]."\">".$Gtarifario[$i][grupo_tarifario_descripcion]."</option>";
					}
				}
				$this->salida .= "   </select>";
				$this->salida .= "  </td>";
			$this->salida.="<tr>";
			
			
			//subgrupo tarifario
			$this->salida.="<tr>";
			$this->salida.="  <td align=\"center\" class=\"modulo_table_title\">SUBGRUPO TARIFARIO</td>";
			$this->salida.= "<td align=\"left\" ><select name=\"subgrupo_tarifario\" class=\"select\">";
			$Sgtarifario=$this->SubGtarifario($_REQUEST['grupo_tarifario']);
			$this->salida .=" 	<option value=\"\" selected>--</option>";
			for($i=0;$i<sizeof($Sgtarifario);$i++){
					if($Sgtarifario[$i][subgrupo_tarifario_id]==$_REQUEST['subgrupo_tarifario']){
						$this->salida .="		<option value=\"".$Sgtarifario[$i][subgrupo_tarifario_id]."\" selected>".$Sgtarifario[$i][subgrupo_tarifario_descripcion]."</option>";
					}else{
						$this->salida .="		<option value=\"".$Sgtarifario[$i][subgrupo_tarifario_id]."\">".$Sgtarifario[$i][subgrupo_tarifario_descripcion]."</option>";
					}
				}
			$this->salida.="		</select></td>";
			$this->salida.="</tr>";
			
			
					//grupo tipo cargo
			$this->salida.="<tr>";
			$this->salida.="  <td align=\"center\" class=\"modulo_table_title\">TIPO CARGO</td>";
			$this->salida.= "<td align=\"left\" >";
			$this->salida.= "		<select name=\"grupo_tipo_cargo\" onchange=\"filtrogrupotipocargo(this.value)\" class=\"select\">";
			$gtipocargo=$this->GrupoTipoCargo();
			$this->salida .=" 	<option value=\"\" selected>--</option>";
			for($i=0;$i<sizeof($gtipocargo);$i++){
					if($gtipocargo[$i][grupo_tipo_cargo]==$_REQUEST['grupo_tipo_cargo']){
						$this->salida .="		<option value=\"".$gtipocargo[$i][grupo_tipo_cargo]."\" selected>".$gtipocargo[$i][descripcion]."</option>";
					}else{
						$this->salida .="		<option value=\"".$gtipocargo[$i][grupo_tipo_cargo]."\">".$gtipocargo[$i][descripcion]."</option>";
					}
				}
			$this->salida.="		</select></td>";
			$this->salida.="</tr>";
			
					//tipo cargo
			$this->salida.="<tr>";
			$this->salida.="  <td align=\"center\" class=\"modulo_table_title\">SUBGRUPO TIPO CARGO</td>";
				$this->salida .= "  <td >";
				$this->salida .= "  	<select name=\"tipo_cargo\"  class=\"select\">";
				$this->salida .= "  		<option value=\"\" selected>SIN DETERMINAR</option>";
				$tipocargo=$this->TipoCargo($_REQUEST['grupo_tipo_cargo']);
				for($i=0;$i<sizeof($tipocargo);$i++){
					if($tipocargo[$i][tipo_cargo]==$_REQUEST['tipo_cargo']){
						$this->salida .="		<option value=\"".$tipocargo[$i][tipo_cargo]."\" selected>".$tipocargo[$i][descripcion]."</option>";
					}else{
						$this->salida .="		<option value=\"".$tipocargo[$i][tipo_cargo]."\">".$tipocargo[$i][descripcion]."</option>";
					}
				}
				$this->salida .= "   </select>";
				$this->salida .= "  </td>";
			$this->salida.="</tr>";
			$this->salida.="<tr><td class=\"modulo_table_title\">Mostrar SQL</td><td><input type='radio' value='1' name='mostrar'  ></td></tr>  ";
			$this->salida.="<tr><td class=\"modulo_table_title\">Mostrar Resultado</td><td><input type='radio' value='2' name='mostrar' checked></td></tr>";
		//Boton Validar
			$this->salida.="<tr >";
			$this->salida .= "<td align=\"right\"  width=\"13%\" colspan=\"5\"><input class=\"input-submit\" name=\"forma\" type=\"submit\" value=\"CONSULTAR DATOS\"></td>";
			$this->salida.="</tr>";
		$this->salida.="</table>";
    $this->salida.= "</form>";

		
		if($mostrar == 1){
			if(!empty($_SESSION['LIQ_PRECIOS']['QUERY'])){
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="	<tr>";
				$this->salida.="		<td align=\"center\" >";
				$this->salida.="			<textarea style = \"width:100%\" class=\"textarea\" name = \"resultado\" cols = \"150\" rows = \"25\">";
				$this->salida.="			".$_SESSION['LIQ_PRECIOS']['QUERY']."</textarea></td>";
				$this->salida.="	</tr>";
				$this->salida.="</table>";
			}
		}else
		{
			if(!empty($_SESSION['LIQ_PRECIOS']['RESULT'])){
				if (!IncludeFile("classes/adodb/tohtml.inc.php"))
					{
							$this->error = "No se pudo inicializar la Clase de adodb";
							$this->mensajeDeError = "No se pudo Incluir el archivo : classes/adodb/tohtml.inc.php";
							return false;
					}
					$resultado=rs2html($_SESSION['LIQ_PRECIOS']['RESULT'],'border=2 cellpadding=3','','',false);//,array('Customer Name','Customer ID')
					$this->salida.= $resultado;
			}
		}
		
		
		
		$this->salida .= ThemeCerrarTabla();
		return true;
	}//fin function Consultar_Cumplimiento

	/**
	 * Forma para liquidar cargos
	 */
	function FrmLiquidarInsumosMedicamentos()
	{
		$accion = ModuloGetUrl('app','LiquidacionPrecios','user','CallFrmLiquidarInsumosMedicamentos');
		$this->salida .= "<form name=\"frmLiquidarMedicamentos\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<table align=\"center\">";
		$this->salida .= "	<tr>";
		$this->salida .= "		<td class=\"label\">Plan:</td>";
		$this->salida .= "		<td>";
		$this->salida .= "			<select class=\"select\" name=\"plan\">";
		$plan=$this->Plan();
		$this->salida .=" <option value= \"-1\" >Seleccione un plan</option>";
		for($j=1;$j< sizeof($plan);$j++)
		{
			if($plan[$j][plan_id]==$_REQUEST['plan']){
				$this->salida .=" <option value= '".$plan[$j][plan_id]."' selected>".$plan[$j][plan_descripcion]."</option>";
			}
			else
			{
				$this->salida .=" <option value= '".$plan[$j][plan_id]."'>".$plan[$j][plan_descripcion]."</option>";
			}
		}
		$this->salida .= "			</select>";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr>";
		$this->salida .= "		<td class=\"label\">Producto:</td>";
		$this->salida .= "		<td><input type=\"text\" class=\"input-text\" name=\"producto\" value=\"{$_REQUEST['producto']}\" size=\"15\"></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<table align=\"center\">";
		$this->salida .= "	<tr>";
		$this->salida .= "		<td>";
		$this->salida .= "			<input type=\"submit\" class=\"input-submit\" name=\"liquidar\" value=\"LIQUIDAR\">";
		$this->salida .= "		</td>";
		$this->salida .= "</form>";
		$this->salida .= "		<td>";
		$accion = ModuloGetUrl('system','Menu');
		$this->salida .= "			<form name=\"fmrVolver\" action=\"$accion\" method=\"post\">";
		$this->salida .= "				<input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "			</form>";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table>";	
		return true;
	}//Fin FrmLiquidarInsumosMedicamentos
	
	/**
	 * Muestra la forma de liquidar insumos y medicamentos
	 */
	function CallFrmLiquidarInsumosMedicamentos()
	{
		$this->salida .= ThemeAbrirTabla("LIQUIDAR INSUMOS Y MEDICAMENTOS");
		$this->FrmLiquidarInsumosMedicamentos();
		if(!empty($_REQUEST['plan']))
		{
			if($_REQUEST['plan']=="-1")
			{
				$this->frmError["MensajeError"] = "Por favor seleccione un plan";
				$this->salida .= "<table align=\"center\">";
				$this->salida .= $this->setStyle("MensajeError");
				$this->salida .= "</table>";
			}
			else
			{
				if(!empty($_REQUEST['producto']))
				{
					$resultado = $this->LiquidarInsumosMedicamentos($_REQUEST['producto'], null, $cantidad=1, array('plan_id'=>$_REQUEST['plan']));
					if($resultado === false)
					{
						$this->frmError["MensajeError"] = "Error al liquidar ".$this->mensajeDeError;
						$this->salida .= "<table align=\"center\">";
						$this->salida .= $this->setStyle("MensajeError");
						$this->salida .= "</table>";
					}
					elseif(is_array($resultado))
					{
						$this->salida .= "<br>";
						$this->FrmResultadoLiquidarInsumosMedicamentos($resultado);
					}
					else
					{
						$this->frmError["MensajeError"] = "La clase LiquidacionCargosInventario no retorno datos";
						$this->salida .= "<table align=\"center\">";
						$this->salida .= $this->setStyle("MensajeError");
						$this->salida .= "</table>";
					}
				}
				else
				{
					$this->frmError["MensajeError"] = "Por favor ingrese un producto";
					$this->salida .= "<table align=\"center\">";
					$this->salida .= $this->setStyle("MensajeError");
					$this->salida .= "</table>";
				}
			}
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}//Fin CallFrmLiquidarInsumosMedicamentos
	
	
	/**
	 * Muestra el arreglo de datos que retorna la liquidacion de insumos y medicamentos
	 *
	 * @param array datos	
	 */
	function FrmResultadoLiquidarInsumosMedicamentos($datos)
	{
		$descripcionIndices["precio_plan"] = "Precio Plan";
		$descripcionIndices["cantidad"] = "Cantidad";
		$descripcionIndices["valor_cargo"] = "Valor Cargo";
		$descripcionIndices["valor_cubierto"] = "Valor Cubierto";
		$descripcionIndices["valor_nocubierto"] = "Valor No Cubierto";
		$descripcionIndices["porcentaje_gravamen"] = "% Gravamen";
		$descripcionIndices["descripcion"] = "Descripción";
		$descripcionIndices["codigo_producto"] = "Código Producto";
		$descripcionIndices["inventarios_origen_parametrizacion"] = "Inventarios Origen Parametrizacion";
		$descripcionIndices["inventarios_costo"] = "Inventarios Costo";
		$descripcionIndices["inventarios_sw_base_liquidacion_imd"] = "Inventarios Base Liquidacion Imd";
		$descripcionIndices["inventarios_lista_de_precios"] = "Inventarios Lista Precios";
		$descripcionIndices["facturado"] = "Facturado";
		$descripcionIndices["valor_descuento_empresa"] = "Valor Descuento Empresa";
		$descripcionIndices["valor_descuento_paciente"] = "Valor Descuento Paciente";
		$descripcionIndices["porcentaje_descuento_empresa"] = "% Descuento Empresa";
		$descripcionIndices["porcentaje_descuento_paciente"] = "% Descuento Paciente";
		$descripcionIndices["sw_cuota_paciente"] = "Cuota Paciente";
		$descripcionIndices["sw_cuota_moderadora"] = "Cuota Moderadora";
		$descripcionIndices["sw_paragrafados_imd"] = "Paragrafados Imd";
		$descripcionIndices["tipo_para_imd"] = "Tipo para. Imd";
		$descripcionIndices["sw_descuento"] = "Descuento";
		
		//formatoValor es un vector donde a cada dato del vector datos
		// se le determina el método de esta clase para dar formato al valor 
		$formatoValor["precio_plan"] = "FormatoValor";
		$formatoValor["cantidad"] = "FormatoDefault";
		$formatoValor["valor_cargo"] = "FormatoValor";
		$formatoValor["valor_cubierto"] = "FormatoValor";
		$formatoValor["valor_nocubierto"] = "FormatoValor";
		$formatoValor["porcentaje_gravamen"] = "FormatoDefault";
		$formatoValor["descripcion"] = "FormatoDefault";
		$formatoValor["codigo_producto"] = "FormatoDefault";
		$formatoValor["inventarios_origen_parametrizacion"] = "FormatoDefault";
		$formatoValor["inventarios_costo"] = "FormatoDefault";
		$formatoValor["inventarios_sw_base_liquidacion_imd"] = "FormatoDefault";
		$formatoValor["inventarios_lista_de_precios"] = "FormatoDefault";
		$formatoValor["facturado"] = "FormatoDefault";
		$formatoValor["valor_descuento_empresa"] = "FormatoValor";
		$formatoValor["valor_descuento_paciente"] = "FormatoValor";
		$formatoValor["porcentaje_descuento_empresa"] = "FormatoDefault";
		$formatoValor["porcentaje_descuento_paciente"] = "FormatoDefault";
		$formatoValor["sw_cuota_paciente"] = "getValorSw";
		$formatoValor["sw_cuota_moderadora"] = "getValorSw";
		$formatoValor["sw_paragrafados_imd"] = "getValorSw";
		$formatoValor["tipo_para_imd"] = "getValorSw";
		$formatoValor["sw_descuento"] = "getValorSw";
		$this->salida .= "<table border=\"1\" align=\"center\" width=\"50%\" class=\"modulo_table_list_title\" >\n";
		$this->salida .= "	<tr class=\"modulo_table_title\" ><td colspan=\"2\" align=\"center\">RESULTADO LIQUIDACIÓN</td></tr>";
		$this->salida .= "	<tr class=\"modulo_table_title\">";
		$this->salida .= "		<td>Descripción</td>";
		$this->salida .= "		<td>Valor</td>";
		$this->salida .= "	</tr>";
		foreach($datos as $key=>$row)
		{
			$this->salida .= "	<tr align=\"left\">";
			if(isset($descripcionIndices[$key]))
				$this->salida .= "		<td>{$descripcionIndices[$key]}</td>";
			else
				$this->salida .= "		<td>$key</td>";
			if(isset($formatoValor[$key]))
				$this->salida .= "		<td class=\"modulo_list_claro\">".$this->$formatoValor[$key]($row)."</td>";
			else
				$this->salida .= "		<td class=\"modulo_list_claro\">{$row}</td>";
			$this->salida .= "	</tr>";
		}
		$this->salida .= "</table>";
		return true;
	}//Fin FrmResultadoLiquidarInsumosMedicamentos
	
	function getValorSw($sw)
	{
		$switches = array(0=>"No",1=>"Si");
		return $switches[$sw];
	}
	
	function FormatoValor($val)
	{
		return FormatoValor($val);
	}
	
	function FormatoDefault($val)
	{
		return $val;
	}
}//fin clase

?>
