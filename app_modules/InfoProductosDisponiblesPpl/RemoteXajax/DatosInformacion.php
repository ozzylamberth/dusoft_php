<?php
	/**
	* Archivo Xajax
	* Tiene como responsabilidad hacer el manejo de las funciones
	* que son invocadas por medio de xajax
	*
	* @package IPSOFT-SIIS
	* @version $Revision: 1.2 $
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	
	/*
		* Forma que permite Mostrar los centros de utilidades de la empresa
		* @return object $objResponse objeto de respuesta al formulario.
	*/
	
		function MostrarCentroUtilidad($form)
		{
			$afi = AutoCarga::factory("InfoProductosDisponiblesPplSQL", "", "app","InfoProductosDisponiblesPpl");
			$centro = $afi->ListarCentroUtilidad($form['empresa']);
			$html  = "document.formita.centro.options.length = 0 ;\n";
			$html .= "document.formita.centro.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
			$i = 1;
			foreach($centro as $key => $dtl)
			{
				$html .= "document.formita.centro.options[".($i++)."] = new Option('".$dtl['descripcion']."','".$dtl['centro_utilidad']."',false, false);\n";
			}
			$objResponse = new xajaxResponse();
			$objResponse->script($html);
			return $objResponse;
		}
			/*
		* Forma que permite Mostrar las bodegas
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		Function MostrarBodegas($form)
		{
		    $afi = AutoCarga::factory("InfoProductosDisponiblesPplSQL", "", "app","InfoProductosDisponiblesPpl");
			$centro = $afi->ListarBodegaEmp($form['empresa'],$form['centro']);
			$html  = "document.formita.bodega.options.length = 0 ;\n";
			$html .= "document.formita.bodega.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
			$i = 1;
			foreach($centro as $key => $dtl)
			{
				$html .= "document.formita.bodega.options[".($i++)."] = new Option('".$dtl['descripcion']."','".$dtl['bodega']."',false, false);\n";
			}
			$objResponse = new xajaxResponse();
			$objResponse->script($html);
			return $objResponse;
		}
			/*
		* Forma que permite Mostrar la informacion de la empresa seleccionada
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		function InformacionFinal($empresa,$centro,$bodega)
		{
		  $objResponse = new xajaxResponse();
		  $sel = AutoCarga::factory("InfoProductosDisponiblesPplSQL", "", "app", "InfoProductosDisponiblesPpl");
            
		  $datos2=$sel->ConsultarInformacionEmpresaSelec($empresa,$centro,$bodega);
          $html .= "<form name=\"forma3\" id=\"forma3\" method=\"post\" >\n";
          $html.= "	<table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
          $html.= "	<tr>";
          $html.= "	<td  class=\"modulo_table_list_title\">DATOS SELECCIONAOS</td>";
          $html.= "	<td  class=\"modulo_table_list_title\">OP</td>";
          $html.= "	</tr>";
          $html .= "  <tr class=\"modulo_list_claro\">\n";
          $html .= "      <td align=\"center\"><B>".$datos2[0]['razon_social']." [".$datos2[0]['centro']."]- ".$datos2[0]['bodega_des']."</B></td>\n";
          $html .= "      <td align=\"center\" >\n";
		  $html .= " <input type=\"hidden\" name=\"empresa\" value=\"".$datos2[0]['empresa_id']."\">";
		  $html .= " <input type=\"hidden\" name=\"centro\" value=\"".$datos2[0]['centro_utilidad']."\">";
		   $html .= " <input type=\"hidden\" name=\"bodega\" value=\"".$datos2[0]['bodega']."\">";
          $html .= "  <a href=\"".ModuloGetURL("app", "InfoProductosDisponiblesPpl", "controller", "BuscarProducto", array ("empresa"=>$datos2[0]['empresa_id'],"centro"=>$datos2[0]['centro_utilidad'],"bodega"=>$datos2[0]['bodega']))."\">\n";
          $html .= "          <img src=\"".GetThemePath()."/images/flecha.png\" border=\"0\" title=\"continuar\" >\n";
          $html .= "         </a>\n";
          $html .= " </td>\n";
          $html .= "  </tr>\n";

          $html .= "	</table><br>\n";
          $html .= "  </form>\n";
	        $objResponse->assign("continuar","innerHTML",$html);
			
			
			
		   return $objResponse;
		  }
			/*
		* Forma que permite Mostrar los productos disponibles
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		 function DetalleInformacion($producto,$empresa,$centro,$bodega,$pendientes)
		 {
		    $objResponse = new xajaxResponse();
        $sel = AutoCarga::factory("InfoProductosDisponiblesPplSQL", "", "app", "InfoProductosDisponiblesPpl");
        $datos2=$sel->ConsultarDisponibles($empresa,$centro,$bodega,$producto);
        $html .= "<form name=\"forma3".$producto."\" id=\"forma3".$producto."\" method=\"post\" >\n";
        $html.= "	<table border=\"0\" width=\"66%\" align=\"center\" class=\"modulo_table_list_title\">";
        $html.= "	<tr>";
        $html.= "	<td  class=\"modulo_table_list_title\">LOTE</td>";
        $html.= "	<td  class=\"modulo_table_list_title\">FECHA VENCIMIENTO</td>";
        $html.= "	<td  class=\"modulo_table_list_title\">EXISTENCIAS</td>";
        $html.= "	</tr>";
			foreach($datos2 as $key => $dt)
			{	    
							$html .= "  <tr >\n";
							$html .= "      <td width=\"40%\" align=\"center\" class=\"modulo_list_claro\"><b>".$dt['lote']."</b></td>\n";
							$html .= "      <td width=\"25%\" align=\"center\" class=\"modulo_list_claro\" ><b>".$dt['fecha_vencimiento']."</b></td>\n";
							$html .= "      <td width=\"5%\" align=\"center\" class=\"modulo_list_claro\"><b>".$dt['existencia_actual']."</b></td>\n";
				            $html .= "  </tr>\n";
							$suma=$suma + $dt['existencia_actual'];
						   
			}
        $disponibles=$suma-$pendientes;
        $html .= "	</table><br>\n";
        $html.= "	<table border=\"0\" width=\"30%\" align=\"center\" class=\"modulo_table_list\">";
        $html.= "	<tr>";
        $html.= "	<td  class=\"modulo_table_list_title\">DISPONIBLE</td>";
        $html.= "	<td  class=\"modulo_list_claro\"><b>".$disponibles."</b></td>";
        $html .= "      <td align=\"center\">\n";
        $html .= "         <a href=\"#"."\" onclick=\"xajax_Solicitar('".$producto."', '".$empresa."','".$centro."','".$bodega."','".$disponibles."')\" class=\"label_error\"><img src=\"".GetThemePath()."/images/flecha_der.gif\" border=\"0\" title=\"solicitar\"></a>\n";
        $html .= "      </td>\n";
        $html.= "	</tr>";
        $html .= "	</table><br>\n";

        $html .= "  </form>\n";
	        $objResponse->assign("disponible".$producto,"innerHTML",$html);
			
	        return $objResponse;
		}
		
		function Solicitar($producto,$empresa,$centro,$bodega,$disponibles)
		{
			$objResponse = new xajaxResponse();
			
			$url=ModuloGetURL("app", "InfoProductosDisponiblesPpl", "controller", "RealizarDocumentoSolicitud", array("producto"=>$producto,"empresa"=>$empresa,"centro"=>$centro,"bodega"=>$bodega,"disponibles"=>$disponibles));
			$objResponse->script('
						 window.location="'.$url.'";
							');
		    return $objResponse;
		
		}
				
?>
