<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: ControlDespachos_HTML.class.php,
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: ControlDespachos_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class ControlDespachos_HTML
	{
		/**
		* Constructor de la clase
		*/
		function ControlDespachos_HTML(){}
		/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
	function DespacharMercancia($request,$action,$resultado,$conteo, $pagina)
    {
	$ctl = AutoCarga::factory("ClaseUtil"); 
	$html .= $ctl->RollOverFilas();
	$html .= ThemeAbrirTabla('DESPACHAR MERCANCIA A LA FARMACIA');
	$html .= "<form name=\"Buscador\" id=\"Buscador\" method=\"POST\" action=\"".$action['buscar']."\">";
	$html .= "	<table width=\"100%\" class=\"modulo_table_list\">";
	$html .= "		<tr class=\"modulo_table_list_title\">";
	$html .= "			<td colspan=\"10\">";
	$html .= "				BUSCADOR DE DESPACHOS A FARMACIAS";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				PREFIJO DESPACHO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input type=\"text\" name=\"buscador[prefijo]\" id=\"buscador[prefijo]\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['prefijo']."\">";
	$html .= "			</td>";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				NUMERO DESPACHO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input type=\"text\" name=\"buscador[numero]\" id=\"buscador[numero]\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['numero']."\">";
	$html .= "			</td>";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				NUMERO PEDIDO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input type=\"text\" name=\"buscador[solicitud_prod_a_bod_ppal_id]\" id=\"buscador[solicitud_prod_a_bod_ppal_id]\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['solicitud_prod_a_bod_ppal_id']."\">";
	$html .= "			</td>";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				FARMACIA";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input type=\"text\" name=\"buscador[nombre_farmacia]\" id=\"buscador[nombre_farmacia]\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['nombre_farmacia']."\">";
	$html .= "			</td>";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				USUARIO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input type=\"text\" name=\"buscador[usuario]\" id=\"buscador[usuario]\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['usuario']."\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td colspan=\"10\" align=\"center\">";
	$html .= "				<input type=\"submit\" value=\"BUSCAR\" class=\"input-submit\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "	</table>";
	$html .= "</form>";
	$html .= "<br>";
	$pgn = AutoCarga::factory("ClaseHTML");
	$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
	$html .= "	<table class=\"modulo_table_list\" width=\"100%\">";
	$html .= "		<tr class=\"modulo_table_list_title\">";
	$html .= "			<td>";
	$html .= "				DOC";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				#PED";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				FARMACIA";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				U.PEDIDO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				FECHA PEDIDO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				U.CRUCE";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				FECHA CRUCE";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				FECHA DESPACHO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				OP";
	$html .= "			</td>";
	$html .= "		</tr>";
	foreach($resultado as $key => $valor)
	{
	$html .= "		<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
	$html .= "			<td>";
	$html .= "				".$valor['prefijo']."-".$valor['numero'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['solicitud_prod_a_bod_ppal_id'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['farmacia'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['usuario_pedido'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['fecha_pedido'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['usuario_cruce'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['fecha_cruce'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['fecha_despacho'];
	$html .= "			</td>";
	$estado=explode("@",$valor['estado_despacho']);
	$html .= "			<td>";
	$pagina_siguiente = ModuloGetURL('app','Inv_ControlDespachos','controller','DespacharMercancia_Forma',array("buscador"=>array("empresa_id"=>$valor['empresa_id'],"prefijo"=>$valor['prefijo'],"numero"=>$valor['numero'])));
	$html .= "				<a href=\"".$pagina_siguiente."\">";
	$html .= "				<center><img title=\"".$estado[0]."\" src=\"".GetThemePath()."/images/".$estado[1]."\" border='0' ></center>\n";
	$html .= "				</a>";
	$html .= "			</td>";
	$html .= "		</tr>";
	}
	$html .= "	</table>";
	$html .= "<br>";
	$html .= '	<form name="forma" action="'.$action['volver'].'" method="post">';
	$html .= "		<table width=\"100%\" class=\"modulo_table_list\">";
	$html .= "  		<tr>";
	$html .= "  			<td align=\"center\"><br>";
	$html .= '  				<input class="input-submit" type="submit" name="volver" value="Volver">';
	$html .= "  			</td>";
	$html .= "		</table>";
	$html .= "	</form>";
	$html .= ThemeCerrarTabla();
    return $html;
    }
	
	function DespacharMercancia_Forma($request,$action,$resultado,$transportadoras)
    {
	$ctl = AutoCarga::factory("ClaseUtil"); 
	$html .= $ctl->RollOverFilas();
	$html .= $ctl->AcceptNum(false);
	
	$option .= "<option value=\"\">-- SELECCIONAR --</option>";
	foreach($transportadoras as $key => $valor)
	{
		if($valor['transportadora_id'] == $resultado[0]['transportadora_id'])
		{
		$selected = " selected ";
		}
		else
			$selected = " ";
	$option .= "<option ".$selected." value=\"".$valor['transportadora_id']."\">".$valor['descripcion']."-".$valor['carro']."</option>";
	}
	$estado=explode("@",$resultado[0]['estado_despacho']);
	$html .= "<script>";
	$html .= "function Validar_Campos(Formulario)";
	$html .= "{";
	$html .= "var mensaje=\"\";";
	
	$html .= "		if(Formulario.transportadora_id.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\")";
	$html .="			mensaje += 'SE REQUIERE SELECCIONAR UNA TRANSPORTADORA<br>';	";
	
	$html .= "		if(Formulario.numero_guia.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\" || Formulario.numero_guia.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"0\")";
	$html .="			mensaje += 'FALTA DILIGENCIAR EL NUMERO GUIA<br>';	";
	
	$html .= "		if(Formulario.conductor.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\" || Formulario.conductor.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"0\")";
	$html .="			mensaje += 'FALTA DILIGENCIAR EL CONDUCTOR<br>';	";
	
	$html .= "		if(Formulario.neveras.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\")";
	$html .="			mensaje += 'FALTA DILIGENCIAR EL NUMERO DE NEVERAS<br>';	";
	
	$html .= "		if(Formulario.temperatura.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\")";
	$html .="			mensaje += 'FALTA DILIGENCIAR LA TEMPERATURA DEL LOS PRODUCTOS<br>';	";
	
	$html .= "		if(Formulario.numero_cajas.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\" || Formulario.numero_cajas.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"0\")";
	$html .="			mensaje += 'FALTA DILIGENCIAR EL NUMERO DE CAJAS<br>';	";
	
	
	$html .="	document.getElementById('error').innerHTML = mensaje; ";
	$html .="	if(mensaje.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\")";
	$html .="	Formulario.submit();";
	$html .="	else";
	$html .="	return false;	";
	$html .= "}";
	$html .= "</script>";
	$html .= ThemeAbrirTabla('DESPACHAR MERCANCIA A LA FARMACIA');
	$html .= "<center>";
	$html .= "	<div id=\"error\" class=\"label_error\"></div>";
	$html .= "</center>";
	$html .= "<form name=\"FormaDespacho\" id=\"FormaDespacho\" method=\"POST\" onSubmit=\"Validar_Campos(document.FormaDespacho); return false;\">";
	$html .= "	<table class=\"modulo_table_list\" width=\"70%\" align=\"center\">";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\" colspan=\"2\">";
	$html .= "				".$resultado[0]['farmacia'];
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				DOCUMENTO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$resultado[0]['prefijo']."-".$resultado[0]['numero'];
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				PEDIDO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$resultado[0]['solicitud_prod_a_bod_ppal_id'];
	$html .= "			</td>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				FECHA PEDIDO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$resultado[0]['fecha_pedido'];
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				FECHA CRUCE";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$resultado[0]['fecha_cruce'];
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				USUARIO PEDIDO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$resultado[0]['usuario_pedido'];
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				USUARIO CRUCE";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$resultado[0]['usuario_cruce'];
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				TRANSPORTADORA";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<select ".$estado[2]." name=\"transportadora_id\" id=\"transportadora_id\" style=\"width:50%\" class=\"select\">";
	$html .= "				".$option;
	$html .= "				</select>";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				NUMERO GUIA";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input ".$estado[2]." type=\"text\" name=\"numero_guia\" id=\"numero_guia\" class=\"input-text\" style=\"width:50%\" value=\"".$resultado[0]['numero_guia']."\" onKeypress=\"return acceptNum(event)\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				CONDUCTOR";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input ".$estado[2]." type=\"text\" name=\"conductor\" id=\"conductor\" class=\"input-text\" style=\"width:50%\" value=\"".$resultado[0]['conductor']."\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				NEVERA(s)";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input ".$estado[2]." type=\"text\" name=\"neveras\" id=\"neveras\" class=\"input-text\" style=\"width:50%\" value=\"".$resultado[0]['neveras']."\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				TEMPERATURA";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input ".$estado[2]." type=\"text\" name=\"temperatura\" id=\"temperatura\" class=\"input-text\" style=\"width:50%\" value=\"".$resultado[0]['temperatura']."\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				CAJA(s)";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input ".$estado[2]." type=\"text\" name=\"numero_cajas\" id=\"numero_cajas\" class=\"input-text\" style=\"width:50%\" value=\"".$resultado[0]['numero_cajas']."\" onKeypress=\"return acceptNum(event)\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td colspan=\"2\" align=\"center\">";
	$html .= "				<input type=\"hidden\" name=\"guardar\" id=\"guardar\" value=\"1\">";
	$html .= "				<input type=\"submit\" value=\"".$estado[0]."\" class=\"input-submit\" ".$estado[2].">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "	</table>";
	$html .= "</form>";
	
	$html .= "<br>";
	$html .= '	<form name="volver" action="'.$action['volver'].'" method="post">';
	$html .= "		<table width=\"100%\" class=\"modulo_table_list\">";
	$html .= "  		<tr>";
	$html .= "  			<td align=\"center\"><br>";
	$html .= '  				<input class="input-submit" type="submit" name="volver" value="Volver">';
	$html .= "  			</td>";
	$html .= "		</table>";
	$html .= "	</form>";
	$html .= ThemeCerrarTabla();
    return $html;
    }
    
	
  	function RecibirMercancia($request,$action,$resultado,$conteo, $pagina)
    {
	$ctl = AutoCarga::factory("ClaseUtil"); 
	$html .= $ctl->RollOverFilas();
	$html .= ThemeAbrirTabla('RECIBIR MERCANCIA EN LA FARMACIA');
	$html .= "<form name=\"Buscador\" id=\"Buscador\" method=\"POST\" action=\"".$action['buscar']."\">";
	$html .= "	<table width=\"100%\" class=\"modulo_table_list\">";
	$html .= "		<tr class=\"modulo_table_list_title\">";
	$html .= "			<td colspan=\"10\">";
	$html .= "				BUSCADOR DE DESPACHOS A FARMACIAS";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				PREFIJO DESPACHO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input type=\"text\" name=\"buscador[prefijo]\" id=\"buscador[prefijo]\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['prefijo']."\">";
	$html .= "			</td>";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				NUMERO DESPACHO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input type=\"text\" name=\"buscador[numero]\" id=\"buscador[numero]\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['numero']."\">";
	$html .= "			</td>";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				NUMERO PEDIDO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input type=\"text\" name=\"buscador[solicitud_prod_a_bod_ppal_id]\" id=\"buscador[solicitud_prod_a_bod_ppal_id]\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['solicitud_prod_a_bod_ppal_id']."\">";
	$html .= "			</td>";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				FARMACIA";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input type=\"text\" name=\"buscador[nombre_farmacia]\" id=\"buscador[nombre_farmacia]\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['nombre_farmacia']."\">";
	$html .= "			</td>";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				USUARIO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input type=\"text\" name=\"buscador[usuario]\" id=\"buscador[usuario]\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['usuario']."\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td colspan=\"10\" align=\"center\">";
	$html .= "				<input type=\"submit\" value=\"BUSCAR\" class=\"input-submit\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "	</table>";
	$html .= "</form>";
	$html .= "<br>";
	$pgn = AutoCarga::factory("ClaseHTML");
	$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
	$html .= "	<table class=\"modulo_table_list\" width=\"100%\">";
	$html .= "		<tr class=\"modulo_table_list_title\">";
	$html .= "			<td>";
	$html .= "				DOC";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				#PED";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				FARMACIA";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				U.PEDIDO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				FECHA PEDIDO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				U.CRUCE";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				FECHA CRUCE";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				FECHA DESPACHO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				OP";
	$html .= "			</td>";
	$html .= "		</tr>";
	foreach($resultado as $key => $valor)
	{
	$html .= "		<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
	$html .= "			<td>";
	$html .= "				".$valor['prefijo']."-".$valor['numero'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['solicitud_prod_a_bod_ppal_id'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['farmacia'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['usuario_pedido'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['fecha_pedido'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['usuario_cruce'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['fecha_cruce'];
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$valor['fecha_despacho'];
	$html .= "			</td>";
	$estado=explode("@",$valor['estado_recepcion']);
	$html .= "			<td>";
	$pagina_siguiente = ModuloGetURL('app','Inv_ControlDespachos','controller','RecibirMercancia_Forma',array("buscador"=>array("empresa_id"=>$valor['empresa_id'],"prefijo"=>$valor['prefijo'],"numero"=>$valor['numero'])));
	$html .= "				<a href=\"".$pagina_siguiente."\">";
	$html .= "				<center><img title=\"".$estado[0]."\" src=\"".GetThemePath()."/images/".$estado[1]."\" border='0' ></center>\n";
	$html .= "				</a>";
	$html .= "			</td>";
	$html .= "		</tr>";
	}
	$html .= "	</table>";
	$html .= "<br>";
	$html .= '	<form name="forma" action="'.$action['volver'].'" method="post">';
	$html .= "		<table width=\"100%\" class=\"modulo_table_list\">";
	$html .= "  		<tr>";
	$html .= "  			<td align=\"center\"><br>";
	$html .= '  				<input class="input-submit" type="submit" name="volver" value="Volver">';
	$html .= "  			</td>";
	$html .= "		</table>";
	$html .= "	</form>";
	$html .= ThemeCerrarTabla();
    return $html;
    }
  
 	function RecibirMercancia_Forma($request,$action,$resultado,$transportadoras)
    {
	$ctl = AutoCarga::factory("ClaseUtil"); 
	$html .= $ctl->RollOverFilas();
	$html .= $ctl->AcceptNum(false);
	
	$option .= "<option value=\"\">-- SELECCIONAR --</option>";
	foreach($transportadoras as $key => $valor)
	{
		if($valor['transportadora_id'] == $resultado[0]['transportadora_id'])
		{
		$selected = " selected ";
		}
		else
			$selected = " ";
	$option .= "<option ".$selected." value=\"".$valor['transportadora_id']."\">".$valor['descripcion']."-".$valor['carro']."</option>";
	}
	$estado=explode("@",$resultado[0]['estado_recepcion']);
	$html .= "<script>";
	$html .= "function Validar_Campos(Formulario)";
	$html .= "{";
	$html .= "var mensaje=\"\";";
	
	$html .= "		if(Formulario.transportadora_id.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\")";
	$html .="			mensaje += 'SE REQUIERE SELECCIONAR UNA TRANSPORTADORA<br>';	";
	
	$html .= "		if(Formulario.numero_guia.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\" || Formulario.numero_guia.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"0\")";
	$html .="			mensaje += 'FALTA DILIGENCIAR EL NUMERO GUIA<br>';	";
	
	$html .= "		if(Formulario.conductor.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\" || Formulario.conductor.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"0\")";
	$html .="			mensaje += 'FALTA DILIGENCIAR EL CONDUCTOR<br>';	";
	
	$html .= "		if(Formulario.neveras.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\")";
	$html .="			mensaje += 'FALTA DILIGENCIAR EL NUMERO DE NEVERAS<br>';	";
	
	$html .= "		if(Formulario.temperatura.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\")";
	$html .="			mensaje += 'FALTA DILIGENCIAR LA TEMPERATURA DEL LOS PRODUCTOS<br>';	";
	
	$html .= "		if(Formulario.numero_cajas.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\" || Formulario.numero_cajas.value.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"0\")";
	$html .="			mensaje += 'FALTA DILIGENCIAR EL NUMERO DE CAJAS<br>';	";
	
	
	$html .="	document.getElementById('error').innerHTML = mensaje; ";
	$html .="	if(mensaje.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\")";
	$html .="	Formulario.submit();";
	$html .="	else";
	$html .="	return false;	";
	$html .= "}";
	$html .= "</script>";
	$html .= ThemeAbrirTabla('DESPACHAR MERCANCIA A LA FARMACIA');
	$html .= "<center>";
	$html .= "	<div id=\"error\" class=\"label_error\"></div>";
	$html .= "</center>";
	$html .= "<form name=\"FormaDespacho\" id=\"FormaDespacho\" method=\"POST\" onSubmit=\"Validar_Campos(document.FormaDespacho); return false;\">";
	$html .= "	<table class=\"modulo_table_list\" width=\"70%\" align=\"center\">";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\" colspan=\"2\">";
	$html .= "				".$resultado[0]['farmacia'];
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				DOCUMENTO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$resultado[0]['prefijo']."-".$resultado[0]['numero'];
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				PEDIDO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$resultado[0]['solicitud_prod_a_bod_ppal_id'];
	$html .= "			</td>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				FECHA PEDIDO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$resultado[0]['fecha_pedido'];
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				FECHA CRUCE";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$resultado[0]['fecha_cruce'];
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				USUARIO PEDIDO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$resultado[0]['usuario_pedido'];
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				USUARIO CRUCE";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$resultado[0]['usuario_cruce'];
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				USUARIO DESPACHA";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$resultado[0]['usuario_despacha'];
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				FECHA DESPACHO";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				".$resultado[0]['fecha_despacho'];
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				TRANSPORTADORA";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<select disabled name=\"transportadora_id\" id=\"transportadora_id\" style=\"width:50%\" class=\"select\">";
	$html .= "				".$option;
	$html .= "				</select>";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				NUMERO GUIA";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input disabled type=\"text\" name=\"numero_guia\" id=\"numero_guia\" class=\"input-text\" style=\"width:50%\" value=\"".$resultado[0]['numero_guia']."\" onKeypress=\"return acceptNum(event)\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				CONDUCTOR";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input disabled type=\"text\" name=\"conductor\" id=\"conductor\" class=\"input-text\" style=\"width:50%\" value=\"".$resultado[0]['conductor']."\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				NEVERA(s)";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input disabled type=\"text\" name=\"neveras\" id=\"neveras\" class=\"input-text\" style=\"width:50%\" value=\"".$resultado[0]['neveras']."\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				TEMPERATURA";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input disabled type=\"text\" name=\"temperatura\" id=\"temperatura\" class=\"input-text\" style=\"width:50%\" value=\"".$resultado[0]['temperatura']."\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td class=\"modulo_table_list_title\">";
	$html .= "				CAJA(s)";
	$html .= "			</td>";
	$html .= "			<td>";
	$html .= "				<input ".$estado[2]." type=\"text\" name=\"numero_cajas\" id=\"numero_cajas\" class=\"input-text\" style=\"width:50%\" value=\"".$resultado[0]['numero_cajas_recibidas']."\" onKeypress=\"return acceptNum(event)\">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "		<tr class=\"modulo_list_claro\">";
	$html .= "			<td colspan=\"2\" align=\"center\">";
	$html .= "				<input type=\"hidden\" name=\"guardar\" id=\"guardar\" value=\"1\">";
	$html .= "				<input type=\"submit\" value=\"".$estado[0]."\" class=\"input-submit\" ".$estado[2].">";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "	</table>";
	$html .= "</form>";
	
	$html .= "<br>";
	$html .= '	<form name="volver" action="'.$action['volver'].'" method="post">';
	$html .= "		<table width=\"100%\" class=\"modulo_table_list\">";
	$html .= "  		<tr>";
	$html .= "  			<td align=\"center\"><br>";
	$html .= '  				<input class="input-submit" type="submit" name="volver" value="Volver">';
	$html .= "  			</td>";
	$html .= "		</table>";
	$html .= "	</form>";
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



      
      $html .= "</script>\n";
      $html .= "<div id='Contenedor2' class='d2Container' style=\"display:none;z-index:4\">\n";
      $html .= "  <div id='titulo2' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
      $html .= "  <div id='cerrar2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
      $html .= "  <div id='Contenido2' class='d2Content'>\n";
      //En ese espacio se visualiza la informacion extraida de la base de datos.
      $html .= "  </div>\n";
      $html .= "</div>\n";
		
      
    
    
    
      return $html;
    }    
    
    
  
  }
?>