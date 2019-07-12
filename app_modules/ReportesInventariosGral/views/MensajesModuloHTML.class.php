<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: MensajesModuloHTML.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: MensajesModuloHTML
  * Clase encargada de crear las formas para mostrar el menu principal del modulo 
  * y los mensajes al usuario
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class MensajesModuloHTML	
	{
		/**
		* Constructor de la clase
		*/
		function MensajesModuloHTML(){}
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action Vector que continen los link de la aplicacion
    *
		* @return string
		*/
		function FormaMenuInicial($action)
		{
      $rpt  = new GetReports();
	  $empresa = SessionGetVar("PermisosReportesGral");
			$html  = ThemeAbrirTabla('REPORTES');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL</td>\n";
			$html .= "				</tr>\n";
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['proveedores']."\"><b>REPORTE POR PROVEEDOR</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";      
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['conformes']."\"><b>REPORTE PRODUCTOS NO CONFORMES</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";      
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['movimiento']."\"><b>REPORTE PRODUCTOS SIN MOVIMIENTO</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['vencimiento']."\"><b>REPORTE PRODUCTOS PROXIMOS A VENCER</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['codigobarras']."\"><b>REPORTE DE USUARIOS QUE <u>NO</u> USAN CODIGOS DE BARRAS</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['estadopacientes']."\"><b>REPORTE DE ESTADOS DE PACIENTES</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['pendientesdespacho']."\"><b>REPORTE DE PRODUCTOS PENDIENTES POR DESPACHAR A LAS FARMACIAS</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['pendientescompras']."\"><b>REPORTE DE PRODUCTOS PENDIENTES EN COMPRAS</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['logauditoria']."\"><b>REPORTE DE LOG AUDITORIA</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
       
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['actastecnicas']."\"><b>ACTAS TECNICAS</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
       
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['selectivo']."\"><b>CONTEO DIARIO - BODEGA [".$empresa['descripcion_bodega']."]</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
	  
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "            <a href=\"".$action['despachos_ingresos']."\"><b>REPORTE DE DESPACHOS - INGRESADOS A LA FARMACIA</b></a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      
      
			$html .= "			</table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
    /**
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		*
		* @param array $action vector que continen los link de la aplicacion
    * @param string $mensaje Cadena con el texto del mensaje a mostrar 
    *         en pantalla
    *
		* @return string
		*/
		function FormaMensajeModulo($action,$mensaje,$imprimir)
		{
			$html  = ThemeAbrirTabla('MENSAJE');
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
			$html .= "		  <table width=\"100%\" align=\"center\">\n";
			$html .= "		    <tr>\n";
			$html .= "		      <td align=\"center\">\n";
			$html .= "			      <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				      <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
			$html .= "			      </form>";
			$html .= "		      </td>\n";
      
      if(!empty($imprimir))
      {
        $rpt  = new GetReports();
  			$html .= $rpt->GetJavaReport('app','NotasFacturasContado',$imprimir['nombre_reporte'],$imprimir,
  																	array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
  			$fnc  = $rpt->GetJavaFunction();
        $html .= "		      <td align=\"center\">\n";
  			$html .= "			      <form name=\"impresion\" action=\"javascript:".$fnc."\" method=\"post\">";
  			$html .= "				      <input class=\"input-submit\" type=\"submit\" name=\"imprimir\" value=\"Imprimir\">";
  			$html .= "			      </form>";
  			$html .= "		      </td>";
      }
      
			$html .= "		    </tr>";
			$html .= "		  </table>";
			$html .= "		</td>";
			$html .= "  </tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
  }
?>