<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: ParametrizacionFarmaciaHTML.class.php,v 1.0 
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres
	*/

	IncludeClass("ClaseHTML");
	IncludeClass("ClaseUtil");

	class ParametrizacionFarmaciaHTML
	{
	/**
		* Constructor de la clase
	*/

	function  ParametrizacionFarmaciaHTML()
	{}
	/*
		  * Funcion donde se crea la forma para el menu Principal
          * @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
        
	*/
		function FormaMenu($action)
		{
			$html  = ThemeAbrirTabla('PARAMETRUZACION FARMACIA');
			$ctl = AutoCarga::factory("ClaseUtil");
			$html .= $ctl->RollOverFilas();
			$html .= "<center>\n";
			$html .= "<fieldset class=\"fieldset\" style=\"width:45%\">\n";
			$html .= "<table width=\"65%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" >\n";
			$html .= "  <tr class=\"formulacion_table_list\" >\n";
			$html .= "     <td align=\"center\">MENU\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"normal_10AN\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
			$html .= "      <td   align=\"center\">\n";
			$html .= "        <a href=\"".$action['parametrizfarma']."\">PARAMETRIZACION DE LA FARMACIA</a>\n";
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
	/* PARAMETRIZACION DE LAS FARMACIA */
	/*
		* Funcion que  Contiene la Forma de Parametrizar Empresas 	que son farmacias
		 * @param array $action vector que contiene los link de la aplicacion
                      * @return string $html retorna la cadena con el codigo html de la pagina
	*/
		function FormaFarmacia($action,$datos,$farmacia)                             
		{
		
			$html .= ThemeAbrirTabla('FARMACIA');
			$ctl = AutoCarga::factory("ClaseUtil");
			$html .= $ctl->RollOverFilas();
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend  width=\"34%\" class=\"normal_10AN\" align=\"left\">INFORMACION DE LA  FARMACIA </legend>\n";
			
			
			$html .= "<table width=\"68%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"formulacion_table_list\">\n";
			$html .= "     <td  colspan=\"2\" align=\"CENTER\">DATOS DE LA FARMACIA\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"formulacion_table_list\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
			$html .= "      <td   width=\"30%\"  class=\"modulo_list_oscuro\" align=\"LEFT\"><b>IDENTIFICACION</b>\n";
			$html .= "      </td>\n";
	        $html .= "      <td   class=\"modulo_list_claro\" align=\"center\"><b>".$datos[0]['tipo_id_tercero']." ".$datos[0]['id']."</b>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"formulacion_table_list\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
			$html .= "      <td  width=\"30%\"  class=\"modulo_list_oscuro\" align=\"LEFT\"><b> RAZON SOCIAL</b>\n";
			$html .= "      </td>\n";
	        $html .= "      <td   class=\"modulo_list_claro\" align=\"center\"><b>".$datos[0]['razon_social']."</b>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"formulacion_table_list\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
			$html .= "      <td  width=\"30%\"  class=\"modulo_list_oscuro\" align=\"LEFT\"><b> REPRESENTANTE LEGAL</b>\n";
			$html .= "      </td>\n";
	        $html .= "      <td   class=\"modulo_list_claro\" align=\"center\"><b>".$datos[0]['representante_legal']."</b>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"formulacion_table_list\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
			$html .= "      <td  width=\"30%\"  class=\"modulo_list_oscuro\" align=\"LEFT\"><b>TELEFONO</B>\n";
			$html .= "      </td>\n";
	        $html .= "      <td   class=\"modulo_list_claro\" align=\"center\"><b>".$datos[0]['telefonos']."</b>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"formulacion_table_list\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
			$html .= "      <td   width=\"30%\" class=\"modulo_list_oscuro\" align=\"LEFT\"><b>DIRECCION</b>\n";
			$html .= "      </td>\n";
	        $html .= "      <td   class=\"modulo_list_claro\" align=\"center\"><b>".$datos[0]['direccion']."</b>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .="<br>";
			$html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"formulacion_table_list\">\n";
			$html .= "     <td align=\"center\">TIPO DE ATENCION ACTUAL";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
      	if($datos[0]['tipo_atencion']==0)
			{
				$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
				$html .= "      <td   class=\"label\" align=\"center\">\n";
				$html .= "       NO TIENE ASIGNADO NINGUN TIPO DE ATENCION</a>\n";
			  $html .= "      </td>\n";
		    $html .= "  </tr>\n";	
			}
           
			if($datos[0]['tipo_atencion']==1)
			{
				$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
				$html .= "      <td   class=\"label\" align=\"center\">\n";
				$html .= "       VENTA DIRECTAMENTE AL PUBLICO</a>\n";
			  $html .= "      </td>\n";
		    $html .= "  </tr>\n";	
			}
			if($datos[0]['tipo_atencion']==2)
			{
				$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
				$html .= "      <td   class=\"label\" align=\"center\">\n";
				$html .= "       VENTA CON FORMULA MEDICA</a>\n";
			  $html .= "      </td>\n";
		    $html .= "  </tr>\n";	
			}
			if($datos[0]['tipo_atencion']==3)
			{
				$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
				$html .= "      <td   class=\"label\" align=\"center\">\n";
				$html .= "       VENTA DIRECTAMENTE AL PUBLICO Y VENTA CON FORMULA</a>\n";
			  $html .= "      </td>\n";
		    $html .= "  </tr>\n";	
			}
			$html .= "</table>\n";
			$html .="<br>";
			
			$html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"formulacion_table_list\">\n";
			$html .= "     <td align=\"center\">TIPO DE ATENCION\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
      	if($datos[0]['tipo_atencion']==0)
			{
        
      	$html .= "  <tr  class=\"modulo_list_oscuro\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
				$html .= "      <td align=\"center\">\n";
				$html .= "         <a href=\"#\" onclick=\"xajax_UpdateTipoAtencion(1,'".$farmacia."','".$datos[0]['tipo_atencion']."')\"  class=\"label_error\">VENTA DIRECTAMENTE AL PUBLICO<a>\n";
			  $html .= "      </td>\n";
		  	$html .= "  </tr>\n";
				$html .= "  <tr  class=\"modulo_list_oscuro\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
				$html .= "      <td   class=\"label\" align=\"center\">\n";
				$html .= "         <a href=\"#\" onclick=\"xajax_UpdateTipoAtencion(2,'".$farmacia."','".$datos[0]['tipo_atencion']."')\"  class=\"label_error\">VENTA CON FORMULA MEDICA</a>\n";
			  $html .= "      </td>\n";
				$html .= "  </tr>\n";
				$html .= "  <tr  class=\"modulo_list_oscuro\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
				$html .= "      <td  class=\"label\"  align=\"center\">\n";
				$html .= "         <a href=\"#\" onclick=\"xajax_UpdateTipoAtencion(3,'".$farmacia."','".$datos[0]['tipo_atencion']."')\"  class=\"label_error\">VENTA DIRECTAMENTE AL PUBLICO Y VENTA CON FORMULA</a>\n";
			  $html .= "      </td>\n";
				$html .= "  </tr>\n";
			}
           
      
      
			if($datos[0]['tipo_atencion']==1)
			{
				$html .= "  <tr  class=\"modulo_list_oscuro\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
				$html .= "      <td   class=\"label\" align=\"center\">\n";
				$html .= "         <a href=\"#\" onclick=\"xajax_UpdateTipoAtencion(2,'".$farmacia."','".$datos[0]['tipo_atencion']."')\"  class=\"label_error\">VENTA CON FORMULA MEDICA</a>\n";
			  $html .= "      </td>\n";
				$html .= "  </tr>\n";
				$html .= "  <tr  class=\"modulo_list_oscuro\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
				$html .= "      <td  class=\"label\"  align=\"center\">\n";
				$html .= "         <a href=\"#\" onclick=\"xajax_UpdateTipoAtencion(3,'".$farmacia."','".$datos[0]['tipo_atencion']."')\"  class=\"label_error\">VENTA DIRECTAMENTE AL PUBLICO Y VENTA CON FORMULA</a>\n";
			  $html .= "      </td>\n";
				$html .= "  </tr>\n";
			}
			if($datos[0]['tipo_atencion']==2)
			{ 
			 
        $html .= "  <tr  class=\"modulo_list_oscuro\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
        $html .= "      <td align=\"center\">\n";
        $html .= "         <a href=\"#\" onclick=\"xajax_UpdateTipoAtencion(1,'".$farmacia."','".$datos[0]['tipo_atencion']."')\"  class=\"label_error\">VENTA DIRECTAMENTE AL PUBLICO<a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"modulo_list_oscuro\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
        $html .= "      <td   class=\"label\" align=\"center\">\n";
        $html .= "         <a href=\"#\" onclick=\"xajax_UpdateTipoAtencion(3,'".$farmacia.",'".$datos[0]['tipo_atencion']."')\"  class=\"label_error\">VENTA DIRECTAMENTE AL PUBLICO Y VENTA CON FORMULA</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
	        }
			if($datos[0]['tipo_atencion']==3)
			{

        $html .= "  <tr  class=\"modulo_list_oscuro\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
        $html .= "      <td   class=\"label\" align=\"center\">\n";
        $html .= "         <a href=\"#\" onclick=\"xajax_UpdateTipoAtencion(2,'".$farmacia."','".$datos[0]['tipo_atencion']."')\"  class=\"label_error\">VENTA CON FORMULA MEDICA</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"modulo_list_oscuro\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
        $html .= "      <td   class=\"label\" align=\"center\">\n";
        $html .= "         <a href=\"#\" onclick=\"xajax_UpdateTipoAtencion(1,'".$farmacia."','".$datos[0]['tipo_atencion']."')\"  class=\"label_error\">VENTA DIRECTAMENTE AL PUBLICO</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
				
			}
			$html .= "</table><br>\n";
			$html .= "</fieldset><br>\n";
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