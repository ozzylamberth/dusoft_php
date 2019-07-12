<?php

   /*********************************************************
  * @package DUANA & CIA
  * @version 1.0 $Id: DocumentoExternoHTML.class
  * @copyright DUANA & CIA DIC-2012
  * @author L.G.T.L
  **********************************************************/

  /***********************************************************
  * Clase Vista: DocumentoExternoHTML
  * Clase Contiene menus de modulo 
  ************************************************************/

	class DocumentoExternoHTML
	{
		/********************************************************
		* Constructor de la clase
		********************************************************/
		function DocumentoExternoHTML(){}


		
		/********************************************************
		* Menu de Bodegas
		********************************************************/
		function MenuBodegas($action, $datos, $empresa_id)
		{
			$bodegas = $datos['bodegas'];
			$html  = ThemeAbrirTabla('CREACION DE DOCUMENTOS EXTERNOS','50%');
			
			$html .= "<table width=\"50%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";

			$html .= "</table>\n";
			$html .= "<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\" colspan=\"3\">BODEGAS\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			for ($i=0; $i < count($bodegas); $i++) 
			{
			  $BODEGA = ModuloGetURL("app", "DocumentoExterno", "controller", "VerFormularioDocumentoExterno")."&datos[empresa_id]=".$empresa_id."&datos[centro_utilidad]=".$bodegas[$i]['centro_utilidad']."&datos[bodega]=".$bodegas[$i]['bodega'];
	          $html .= "</tr>\n";
	          $html .= "<tr class=\"modulo_list_claro\">\n";
	          $html .= "<td align=\"center\" class=\"normal_10AN\">\n";
	          $html .= "<a title=\"".$bodegas[$i]['nom_bodega']."\" class=\"label_error\" href=\"".$BODEGA."\">".$bodegas[$i]['nom_bodega']."</a>\n";
	          $html .= "</td>";
	          $html .= "</tr>";
	       	}
			$html .= "</table>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}


		/***************************************************************************
		* Función que visualiza formulario para crear el documento externo
		***************************************************************************/
		function FormularioDocumentoExterno($action, /*$datos, $pedido_id, $despacho, $destinatario_despacho, */$farmacias)
		{
			$html  = "<script>\n"; 
			$html .= "	function ValidarCampos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";

			$html .= "		if(forma.farmacia.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR LA FARMACIA\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";

			$html .= "		if(forma.documento.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR EL DOCUMENTO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";

			$html .= "		if(forma.prefijo.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL PREFIJO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";

			$html .= "		if(forma.cantidad_cajas.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR LA CANTIDAD DE CAJAS QUE CONTIENE EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(isNaN(forma.cantidad_cajas.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR UN VALOR NUMERICO EN LA CANTIDAD DE CAJAS QUE CONTIENE EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";

			$html .= "		if(forma.cantidad_neveras.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR LA CANTIDAD DE NEVERAS QUE CONTIENE EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(isNaN(forma.cantidad_neveras.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR UN VALOR NUMERICO EN LA CANTIDAD DE NEVERAS QUE CONTIENE EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";

			$html .= "		if(forma.temperatura_neveras.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR TEMPERATURA DE LAS NEVERAS QUE CONTIENE EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(isNaN(forma.temperatura_neveras.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR UN VALOR NUMERICO EN LA TEMPERATURA DE LAS NEVERAS QUE CONTIENE EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "    document.documento_externo.action =\"".$action['GuardarDocumentoExterno']."\"; \n";
			$html .= "    document.documento_externo.submit();\n";
			$html .= "	}\n";
		    $html .= "</script>\n";

			$html .= ThemeAbrirTabla('CREACION DE DOCUMENTOS EXTERNOS','50%');

			$html .= "<form name=\"documento_externo\" id=\"documento_externo\" action=\"javascript:ValidarCampos(document.documento_externo)\" method=\"post\">\n";
			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
			$html .= "	<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "     		<td align=\"center\" colspan=\"4\">DOCUMENTO EXTERNO ".$pedido_id."\n";
			$html .= "  		</td>\n";
			$html .= "  	</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				Farmacia\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\">\n";
	        $html .= "				Documento\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\">\n";
	        $html .= "				Prefijo\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        //$html .= "				<input type=\"text\" name=\"farmacia\" id=\"farmacia\" value=\"\">\n";
	        $html .= "				<select name=\"farmacia\">\n";
	        $html .= "					<option value=\"\"></option>\n";
	        for($i = 0; $i < count($farmacias); $i++)
	        {
	        	$html .= "<option value=\"".$farmacias[$i]['empresa_id']." - ".$farmacias[$i]['centro_utilidad']." - ".$farmacias[$i]['bodega']."\">".$farmacias[$i]['razon_social']."</option>\n";
	    	}
	        $html .= "				</select>\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\">\n";
	        $html .= "				<input type=\"text\" name=\"documento\" id=\"documento\" value=\"\">\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\">\n";
	        $html .= "				<select name=\"prefijo\">\n";
	        $html .= "					<option value=\"\"></option>\n";
	        $html .= "					<option value=\"DC\">DC</option>\n";
	        $html .= "					<option value=\"TE\">TE</option>\n";
	        $html .= "					<option value=\"EDD\">EDD</option>\n";
	        $html .= "				</select>\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\">\n";
	        $html .= "				Cantidad de Cajas\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\">\n";
	        $html .= "				Cantidad de Neveras\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				Temperatura (en grados centigrados)\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\">\n";
	        $html .= "				<input type=\"text\" name=\"cantidad_cajas\" id=\"cantidad_cajas\" value=\"\">\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\">\n";
	        $html .= "				<input type=\"text\" name=\"cantidad_neveras\" id=\"cantidad_neveras\" value=\"\">\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				<input type=\"text\" name=\"temperatura_neveras\" id=\"temperatura_neveras\" value=\"\">\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";

	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"4\">\n";
	        $html .= "				Observacion\n";
			$html .= "			</td>\n";
	        $html .= "		</tr>\n";

	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"4\">\n";
	        $html .= "				<textarea cols=\"110\" rows=\"4\" name=\"observacion\" id=\"observacion\">";
			$html .= "</textarea>\n";
			$html .= "			</td>\n";
	        $html .= "		</tr>\n";

	        $html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td align=\"center\" colspan=\"4\">\n";
	        $html .= "			<input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"Guardar\">\n";
	        $html .= "		</td>\n";
	        $html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "<br>\n";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}

		
		/***********************************************************************
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		* @param array $action vector que contine los link de la aplicacion
		* @param string $mensaje Cadena con el texto del mensaje a mostrar en pantalla
		* @return string
		************************************************************************/
		function FormaMensajeModulo($action,$mensaje)
		{
			$html  = ThemeAbrirTabla('DOCUMENTOS EXTERNOS');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$mensaje."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}	
	}
?>