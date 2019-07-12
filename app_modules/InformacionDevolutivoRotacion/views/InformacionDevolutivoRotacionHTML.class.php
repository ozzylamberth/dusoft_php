<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id:InformacionDevolutivoRotacionHTML.class.php,v 1.0 
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres
	*/

	IncludeClass("ClaseHTML");
    IncludeClass("ClaseUtil");

	class InformacionDevolutivoRotacionHTML
	{
	/**
		* Constructor de la clase
	*/

	function  InformacionDevolutivoRotacionHTML()
	{}
	/*
		* Funcion donde se crear el Menu principal
          * @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
       
	*/
		function FormaMenu($action,$num)
		{
			$html  = ThemeAbrirTabla('MENU INFORMATIVO DEVOLUTIVO POR ROTACION DE FARMACIA');
			$ctl = AutoCarga::factory("ClaseUtil");
			$html .= $ctl->RollOverFilas();
			$html .= "<center>\n";
			$html .= "<fieldset class=\"fieldset\" style=\"width:45%\">\n";
			$html .= "<table width=\"65%\" class=\"modulo_table_list\" border=\"1\" align=\"center\" >\n";
			$html .= "  <tr class=\"formulacion_table_list\" >\n";
			$html .= "     <td align=\"center\">MENU\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"normal_10AN\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
			$html .= "      <td   align=\"center\">\n";
			$html .= "        <a href=\"".$action['devolutivos']."\">DEVOLUTIVOS PENDIENTES POR ENVIAR(".$num.")</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "	</table>\n";
			$html .= "</fieldset><br>\n";
			$html .= "</center>\n";
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
	/*
		* Funcion donde se forma el detalle de lo solicitado
		* @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
       
	*/
		Function DetalleSolicitudDevolucion($action,$datos,$num,$farmacia_id)
		{
	
		   $ctl = AutoCarga::factory("ClaseUtil");
			$html .= "<script>\n";
			$html .= "  function EnviarDatos()\n";
			$html .= "  {\n";
			$html .= "    xajax_ActualizarDatos(xajax.getFormValues('Forma1'),'".$num."','".$farmacia."')\n";
			$html .= "  }\n";
			$html .= "</script>\n";		   
			$html .= $ctl->RollOverFilas();
		    $html .= ThemeAbrirTabla('DETALLE DE LA SOLICITUD A DEVOLVER');
			$html .= "<form name=\"Forma1\" id=\"Forma1\" method=\"post\" >\n";
			$html .= "<center>\n";
			$html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
			$html .= "  <table width=\"100%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
			$html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
			$html .= "      <td  width=\"10%\">CODIGO PRODUCTO</td>\n";
			$html .= "      <td width=\"30%\">PRODUCTO.</td>\n";
            $html .= "      <td  width=\"5%\" align=\"center\">CANTIDAD</td>\n";
			$html .= "      <td  width=\"5%\" align=\"center\">DEVUELTO</td>\n";
			$html .= "    </tr>\n";
			$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
			$j=0;
			foreach($datos as $key => $dtl)
			{
				$html .= "  <tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
           		$html .= "      <td   align=\"left\">".$dtl['codigo_producto']."</td>\n";
				$html .= "      <td   align=\"left\">".$dtl['producto']." </td>\n";
				$html .= "      <td    align=\"center\">".$dtl['cantidad']."\n";
				$html .= "      <td    align=\"center\">".round($dtl['cantidad_dev'])."\n";
			/*	$html .= "  <input type=\"hidden\" name=\"medicamento[".$j."]\" value=\"".$dtl['codigo_producto']."\">\n";

				$html .= "      <td >\n";
				$html .= "        <input type=\"checkbox\" name=\"chec[".$j."]\" value=\"1\" ".(($j== '1')? "checked":"").">\n";
				$html .= "      </td>\n";		*/
				$html .= "    </tr>\n";
				$j++;
			}
			$html .= "  </table>\n";
			$html .= "  <br>\n";
			/*$html .= "  <table width=\"90%\"  border=\"0\"  align=\"center\">";
			$html .= "	  <tr  align=\"center\">\n";
			$html .= "      <td >\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" name=\"guardar\" value=\"Guardar\" onclick=\"EnviarDatos();\">\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "	</table>\n";*/
			$html .= "  <table width=\"90%\"  border=\"0\"  align=\"center\">";
			$html .= "	  <tr   align=\"center\">\n";
			$html .= "      <td colspan=\"4\" align=\"center\">\n";
			$html .= "  <div id=\"error\"></div>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>";
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
	
			
	}
?>