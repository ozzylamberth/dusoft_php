<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: AdministracionFarmaciaHTML.class.php,v 1.0 
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres
	*/

	IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");

	class AdministracionFarmaciaHTML
	{
  	/**
    * Constructor de la clase
  	*/
  	function  AdministracionFarmaciaHTML(){}
  /* Funcion que Contiene la Forma para buscar una factura de venta 
    *
    * @param array $action vector que contiene los link de la aplicacion
    *
    * @return String 
    */	
      function FormaBuscarProductosVenta($action,$conteo,$pagina,$request,$datos,$prefijo_venta,$documento)
      {  
			$ctl = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->LimpiarCampos();
			$html .= $ctl->AcceptNum();
			$html .= "<script>\n";
			$html .= "  function ValidarDatos(frm)\n";
			$html .= "  {\n";
			$html .= "    if(document.getElementById('prefijo').value == '-1')\n";
			$html .= "    {\n";
			$html .= "       document.getElementById('error').innerHTML = 'FAVOR SELECCIONE EL PREFIJO';\n";
			$html .= "       return;\n";
			$html .= "    }\n";
			$html .= "    frm.submit();\n";
			$html .= "  }\n";
			$html .= "</script>\n";
			$html .= ThemeAbrirTabla("BUSCAR FACTURAS");

			$html .= "<form name=\"Forma12\" id=\"Forma12\" method=\"post\"  action=\"".$action['buscador']."\">\n";
			$html .= "  <input type=\"hidden\" name=\"empresa_id\" value=\"".$empresa['empresa_id']."\">\n";
			$html .= "  <input type=\"hidden\" name=\"centro_utilidad\" value=\"".$empresa['centro_utilidad']."\">\n";
			$html .= "  <input type=\"hidden\" name=\"bodega\" value=\"".$empresa['bodega']."\">\n";
			$html .= "  <input type=\"hidden\" name=\"bodegas_doc_id\" value=\"".$empresa['bodegas_doc_id']."\">\n";
			$html .= "  <input type=\"hidden\" name=\"usuario_id\" value=\"".$empresa['usuario_id']."\">\n";
			$html .= "  <input type=\"hidden\" name=\"documento\" id=\"documento\" value=\"".$documento['documento']."\">\n";
			$html .= "  <table width=\"45%\"  class=\"modulo_table_list\" align=\"center\" border=\"0\" >\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";
			$html .= "		  <td colspan=\"7\">FACTURAS -VENTA DE PRODUCTOS</td>\n";
			$html .= "	  </tr>\n";			
			$html .= "    <tr class=\"formulacion_table_list\">\n";
			$html .= "		  <td width=\"30%\" align=\"left\">PREFIJO:</td>\n";
			$html .= "	    <td width=\"15%\" class=\"modulo_list_claro\" align=\"left\">\n";
			$html .= "					            <select name=\"buscador[pref]\"  id=\"prefijo\" class=\"select\" >\n";
			$html .= "                        	<option value = '-1'>---SEL---</option>\n";
			$csk = "";
      		foreach($prefijo_venta as $indice => $valor)
      		{
      			$html .= "  <option value=\"".$valor[prefijo]."\" >".$valor[prefijo]."</option>\n";
      		}
      			$html .= "                </select>\n";

          $html .= "      </td>\n";
    		
    			$html .= "	    <td width=\"30%\" align=\"center\">FACTURA No:</td>\n";
    			$html .= "		  <td width=\"15%\"class=\"modulo_list_claro\" align=\"left\">\n";
          $html .= "        <input type=\"text\"  style=\"width:100%\" class=\"input-text\" name=\"buscador[numero]\" value=".$request['numero'].">\n";
          $html .= "      </td>\n";
    			$html .= "  	</tr>\n";
    			$html .= "    <tr class=\"formulacion_table_list\">\n";
    			$html .= "	    <td align=\"left\">TIPO DE DOCUMENTO:</td>\n";
    	
          $html .= "	    <td colspan=\"7\"class=\"modulo_list_claro\" align=\"left\">\n";
    			$html .= "					            <select name=\"buscador[tipo]\" class=\"select\" >\n";
      		$html .= "                        	<option value = '-1'>----SELECCIONE----</option>\n";
      		$csk = "";
      		foreach($documento as $indice => $valor)
      		{
      			$html .= "  <option value=\"".$valor['tipo_id_tercero']."\" >".$valor['descripcion']."</option>\n";
      		}
      			$html .= "                </select>\n";

          $html .= "      </td>\n";
    		 	$html .= "	  </tr>\n";
    			
          $html .= "    <tr class=\"formulacion_table_list\">\n";
    			$html .= "	    <td align=\"left\">IDENTIFICACION:</td>\n";
    			$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">\n";
          $html .= "        <input type=\"text\" style=\"width:100%\" class=\"input-text\" name=\"buscador[identificacion]\" value=".$request['descripcion'].">\n";
          $html .= "      </td>\n";
    			$html .= "	  </tr>\n";
          $html .= "    <tr class=\"formulacion_table_list\">\n";
    			$html .= "	    <td align=\"left\">NOMBRE COMPLETO:</td>\n";
    			$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">\n";
          $html .= "        <input type=\"text\" style=\"width:100%\" class=\"input-text\" name=\"buscador[nombre]\" value=".$request['nombre'].">\n";
          $html .= "      </td>\n";
    			$html .= "	  </tr>\n";
    			$html .= "  </table>\n";
    			$html .= "	<table height=\"40\" width=\"50%\" align=\"center\" >\n";
    			$html .= "    <tr>\n";
    			$html .= "	    <td width=\"50%\" align=\"center\">\n";
    			$html .= "			  <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"Buscar\"  onclick=\"ValidarDatos(document.Forma12)\" >\n";
    			$html .= "		  </td>\n";
    			$html .= "			<td width=\"50%\" align=\"center\">\n";
    			$html .= "			  <input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.Forma12)\" value=\"Limpiar Campos\">\n";
    			$html .= "	  	</td>\n";
    			$html .= "	  </tr>\n";
    			$html .= "  </table>\n";
         	$html .="<div align=\"center\" class=\"label_error\" id='error'></div><br> ";
         	$html .= "</form>\n";
     		 	if(!empty($datos))
    			{
    				$pghtml = AutoCarga::factory('ClaseHTML');
    				$html .= "  <table width=\"85%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
    				$html .= "	  <tr  class=\"formulacion_table_list\" >\n";
    		
    				$html .= "      <td width=\"10%\">FACTURA No</td>\n";
    				$html .= "      <td  width=\"55%\" >TERCERO</td>\n";
    				$html .= "      <td width=\"15%\">FECHA REGISTRO</td>\n";
            $html .= "      <td width=\"25%\">USUARIO</td>\n";
    				$html .= "      <td width=\"3%\">OP.</td>\n";
    				$html .= "  </tr>\n";
    				$est = "modulo_list_claro"; $back = "#DDDDDD";
    				foreach($datos as $key => $dtl)
    				{
    					$html .= "  <tr class=\"modulo_list_claro\">\n";
             
              $html .= "      <td align=\"left\"><b>".$dtl['prefijo']." - ".$dtl['factura_fiscal']."</b></td>\n";
              $html .= "      <td align=\"center\"><b>".$dtl['tipo_id_tercero']." ".$dtl['tercero_id']." -".$dtl['tercero']."</b></td>\n";
    					$html .= "      <td align=\"left\"><b>".$dtl['fecha_registro']."</td>\n";
    					$html .= "      <td align=\"left\"><b>".$dtl['nombre']."</b></td>\n";
              $html .= "      <td align=\"center\">\n";
      				$html .= "      <a href=\"".$action['tercero'].URLRequest(array("prefijo"=>$dtl['prefijo'],"factura_fiscal"=>$dtl['factura_fiscal'],"tipo_id_tercero"=>$dtl['tipo_id_tercero'],"tercero_id"=>$dtl['tercero_id'],"nombre_tercero"=>$dtl['tercero'],"total_factura"=>$dtl['total_factura'],"saldo"=>$dtl['saldo']))."\">\n";
      				$html .= "        <img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\">\n";
      				$html .= "    </a>\n";
    					$html .= "  </tr>\n";
    				}
    				$html .= "	</table><br>\n";
    				$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
    				$html .= "	<br>\n";
    			}
    			else
    			{
    			if($request)
    				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
    			}
         
    			$html .= "<table align=\"center\" width=\"50%\">\n";
    			$html .= "  <tr>\n";
    			$html .= "    <td align=\"center\">\n";
    			$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
    			$html .= "        VOLVER\n";
    			$html .= "      </a>\n";
    			$html .= "    </td>\n";
    			$html .= "  </tr>\n";
    			$html .= "</table>\n";
     			$html .= ThemeCerrarTabla();
    			return $html;
      } 
  /* Funcion que Contiene la Forma del detalle de la factura de venta
    *
    * @param array $action vector que contiene los link de la aplicacion
    *
    * @return String 
    */	  
      function FormaDetalle_Factura_Venta($action,$empresa,$Informacion_Terc,$prefijo_venta,$factura_fis,$informacion_general,$informacion_detalle,$total_fa,$informacion_detalle_rc,$bodegas_doc_id,$bodegas_numeracion)
	  	{  
		$ctl = AutoCarga::factory("ClaseUtil"); 
		$html .= $ctl->LimpiarCampos();
		$num= count($informacion_detalle);
		$num_rc=count($informacion_detalle_rc);
		$html .= $ctl->AcceptNum();
		$html .= "<script>\n";
		$html .= "  function ValidarDatos(frm)\n";
		$html .= "  {\n";
		$html .= "    if(document.getElementById('prefijo').value == '-1')\n";
		$html .= "    {\n";
		$html .= "       document.getElementById('error').innerHTML = 'FAVOR SELECCIONE EL PREFIJO';\n";
		$html .= "       return;\n";
		$html .= "    }\n";
		$html .= "    frm.submit();\n";
		$html .= "  }\n";
		$html .= "  function ValidarInformacion(frm)";
		$html .= "  {";
		$html .= " var band=0; ";
		$html .= " var check=0; ";
		$html .= " var check_rc=0; ";
		$html .= " var band_rc=0; ";
          for($k=0;$k<$num;$k++)
          {
            $html .= " if(frm.checkseleccionar".$k.".checked==true){\n";
            /*$html .= "		alert(frm.cantidad_fac".$k.".value);";
            $html .= "		alert(frm.devolver".$k.".value);";*/
			$html .= "    check=check+1; ";
            $html .= "      if(frm.devolver".$k.".value==''){ ";
            $html .= "         document.getElementById('error').innerHTML = 'FAVOR INGRESAR LA CANTIDAD A DEVOLVER';\n";
            $html .= "       return;\n";
            $html .= "      }    ";
            $html .= "      if(parseInt(frm.devolver".$k.".value) > parseInt(frm.cantidad_fac".$k.".value)){ ";
            $html .= "         document.getElementById('error').innerHTML = 'LA CANTIDAD A DEVOLVER DEBE SER MENOR O IGUAL A LA CANTIDAD INICIAL';\n";
            $html .= "       return;\n";
            $html .= "     }else {";
            $html .= "      band=band+1; ";
            $html .= " } ";
            $html .= " } ";
         }   
             
       if(!empty($informacion_detalle_rc))
          {
              
              for($p=0;$p<$num_rc;$p++)
              { 
              
               
               if($bandera==1)
               {  
                  $html .= " if(frm.checkseleccionar_rc".$p.".checked==true){\n";
                  $html .= "    check_rc=check_rc+1; ";
                  $html .= "      if(frm.devolver_rc".$p.".value==''){ ";
                  $html .= "         document.getElementById('error').innerHTML = 'FAVOR INGRESAR LA CANTIDAD A DEVOLVER';\n";
                  $html .= "       return;\n";
                  $html .= "      }    ";
                  $html .= "      if(frm.devolver_rc".$p.".value > frm.cantidad_rc".$p.".value){ ";
                  $html .= "         document.getElementById('error').innerHTML = 'LA CANTIDAD A DEVOLVER DEBE SER MENOR O IGUAL A LA CANTIDAD INICIAL';\n";
                  $html .= "       return;\n";
                  $html .= "     }else {";
                  $html .= "      band_rc=band_rc+1; ";
                  $html .= " } ";
                  $html .= " } ";
                  //$html .= " } ";
                }  
              }
            
          }
        /*   if(!empty($informacion_detalle_rc))
          {*/
			$html .= "   if(band==check){ "; 
			$html .= "      frm.submit();\n";
			$html .= "  }";
			$html .= "  }";
			$html .= "</script>\n";
			$html .= ThemeAbrirTabla("DETALLE DE LA FACTURA DE VENTA");
         
			$html .= "<form name=\"Forma13\" id=\"Forma13\" method=\"post\"  action=\"".$action['dventa']."\">\n";
			$html .= "  <table width=\"60%\"  class=\"modulo_table_list\" align=\"center\" border=\"0\" >\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";
			$html .= "	    <td align=\"left\">EMPRESA:</td>\n";
			$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">".$empresa['descripcion1']."-".$empresa['descripcion2']."\n";
			$html .= "      </td>\n";
			$html .= "	  </tr>\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";
			$html .= "	    <td align=\"left\">TERCERO:</td>\n";
			$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">".$Informacion_Terc."\n";
			$html .= "      </td>\n";
			$html .= "	  </tr>\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";
			$html .= "	    <td align=\"left\">FACTURA:</td>\n";
			$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">".$prefijo_venta." -".$factura_fis."\n";
			$html .= "      </td>\n";
			$html .= "	  </tr>\n";
			$html .= "  </table>\n";
			$html .=" <br>";
			$html .= "  <table width=\"60%\"  class=\"modulo_table_list\" align=\"center\" border=\"0\" >\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";
			$html .= "	    <td width=\"22%\" align=\"left\">TOTAL FACTURA:</td>\n";
			$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">$".round($total_fa)."\n";
			$html .= "      </td>\n";
			$html .= "        <input type=\"hidden\" name=\"total_costo\" value=\"".$total_fa."\">\n";

			$html .= "	  </tr>\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";
			$html .= "	    <td width=\"22%\" align=\"left\">OBSERVACION:</td>\n";
			$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">".$informacion_general[0]['observacion']."\n";
			$html .= "      </td>\n";
			$html .= "  </table><BR>\n";
			$html .="<div align=\"center\" class=\"label_error\" id='error'></div>";
          
          if(!empty($informacion_detalle_rc))
          {
					$j=0;
					$pghtml = AutoCarga::factory('ClaseHTML');
					$html .= "<fieldset class=\"fieldset\">\n";
					$html .= "  <legend class=\"normal_10AN\" align=\"LEFT\">PRODUCTOS CON DEVOLUCIONES</legend>\n";
					$html .= "  <table width=\"98%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";

					$html .= "	  <tr  class=\"formulacion_table_list\" >\n";
					$html .= "      <td width=\"15%\">MOLECULA</td>\n";
					$html .= "      <td width=\"15%\">CODIGO</td>\n";
					$html .= "      <td width=\"55%\">PRODUCTO</td>\n";
					$html .= "      <td  width=\"10%\">CANTIDAD</td>\n";
					$html .= "      <td width=\"20%\">FECHA VEN</td>\n";
					$html .= "      <td width=\"25%\">LOTE</td>\n";
					$html .= "      <td width=\"10%\">CANT</td>\n";
					$html .= "      <td width=\"3%\">OP.</td>\n";
					$html .= "  </tr>\n";
					$html .= "        <input type=\"hidden\" name=\"contador_rc\" value=\"".$num_rc."\">\n";
            foreach($informacion_detalle_rc as $key => $dtl)
    				{
                  $mdl = AutoCarga::factory("DevolucionVentaSQL", "classes", "app", "DevolucionVenta");
                  $cantidad=$mdl->Consultar_Cantidad_Producto($dtl['codigo_producto'],$dtl['fecha_vencimiento'],$dtl['lote'],$bodegas_doc_id,$bodegas_numeracion);
                  /*print_r($cantidad);*/
                  $cantidad_final=$dtl['cantidad'] - $cantidad[0]['cantidad'];
                  if($cantidad_final!=0)  
                  {         

					$bandera=1;              
					$html .= "  <tr class=\"modulo_list_claro\">\n";
					$html .= "      <td align=\"center\"><b>".$dtl['molecula']."</b></td>\n";
					$html .= "      <td align=\"left\"><b>".$dtl['codigo_producto']."</b></td>\n";
					$html .= "      <td align=\"center\"><b>".$dtl['descripcion']." ".$dtl['contenido_unidad_venta']." ".$dtl['unidad']." - ".$dtl['laboratorio']."</b></td>\n";
					$html .= "      <td align=\"left\"><b>".round($cantidad_final)."</b></td>\n";
					$html .= "      <td align=\"left\"><b>".$dtl['fecha_vencimiento']."</b></td>\n";
					$html .= "      <td align=\"left\"><b>".$dtl['lote']."</b></td>\n";
					$html .= "      <td align=\"left\">";
					$html .= "        <input type=\"text\" style=\"width:100%\" size=\"15\" class=\"input-text\" name=\"devolver_rc".$j."\"\" id=\"devolver_rc".$j."\" onKeypress=\"return acceptNum(event)\">\n";
					$html .= " </td>\n";
					$html .=" <td  align=\"left\"> <input type=\"checkbox\" name=\"checkseleccionar_rc".$j."\" id=\"checkseleccionar_rc".$j."\" value=\"1\">";       
					$html .= "        <input type=\"hidden\" name=\"cantidad_rc".$j."\" value=\"".round($dtl['cantidad'])."\">\n";
					$valor_unitario_rc=($dtl['total_costo']);

					$html .= "        <input type=\"hidden\" name=\"precio_venta_rc".$j."\" value=\"".$valor_unitario_rc."\">\n";

					$html .= "        <input type=\"hidden\" name=\"codigo_producto_rc".$j."\" value=\"".$dtl['codigo_producto']."\">\n";
					$html .= "        <input type=\"hidden\" name=\"fecha_vencimiento_rc".$j."\" value=\"".$dtl['fecha_vencimiento']."\">\n";
					$html .= "        <input type=\"hidden\" name=\"lote_rc".$j."\" value=\"".$dtl['lote']."\">\n";
					$html .= "      </td>\n";
					$html .= "  </tr>\n";
                  }
            $j++;
    				}
            $html .= "  </table><br>\n";
            $html .= "</fieldset><br>\n";
          }
          $i=0;
     		 	if(!empty($informacion_detalle))
    			{
			$pghtml = AutoCarga::factory('ClaseHTML');
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend class=\"normal_10AN\" align=\"LEFT\">PRODUCTOS SIN DEVOLUCIONES</legend>\n";
			$html .= "  <table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
			$html .= "	  <tr  class=\"formulacion_table_list\" >\n";
			$html .= "      <td width=\"15%\">MOLECULA</td>\n";
			$html .= "      <td width=\"15%\">CODIGO</td>\n";
			$html .= "      <td width=\"55%\">PRODUCTO</td>\n";
			$html .= "      <td  width=\"10%\">CANTIDAD</td>\n";
			$html .= "      <td width=\"20%\">FECHA VEN</td>\n";
			$html .= "      <td width=\"25%\">LOTE</td>\n";
			$html .= "      <td width=\"10%\">CANT</td>\n";
			$html .= "      <td width=\"3%\">OP.</td>\n";
			$html .= "  </tr>\n";
			$est = "modulo_list_claro"; $back = "#DDDDDD";
			$html .= "        <input type=\"hidden\" name=\"contador\" value=\"".$num."\">\n";

    				foreach($informacion_detalle as $key => $dtl)
    				{

			$html .= "  <tr class=\"modulo_list_claro\">\n";
			$html .= "      <td align=\"center\"><b>".$dtl['molecula']."</b></td>\n";
			$html .= "      <td align=\"left\"><b>".$dtl['codigo_producto']."</b></td>\n";
			$html .= "      <td align=\"center\"><b>".$dtl['descripcion']." ".$dtl['contenido_unidad_venta']." ".$dtl['unidad']." - ".$dtl['laboratorio']."</b></td>\n";
			$html .= "      <td align=\"left\"><b>".round($dtl['cantidad'])."</b></td>\n";
			$html .= "      <td align=\"left\"><b>".$dtl['fecha_vencimiento']."</b></td>\n";
			$html .= "      <td align=\"left\"><b>".$dtl['lote']."</b></td>\n";
			$html .= "      <td align=\"left\">";
			$html .= "        <input type=\"text\" style=\"width:100%\" size=\"15\" class=\"input-text\" name=\"devolver".$i."\" id=\"devolver".$i."\" onKeypress=\"return acceptNum(event)\">\n";
			$html .= " </td>\n";

              $valor_unitario=($dtl['total_costo']);
                      
              
              $html .=" <td  align=\"left\"> <input type=\"checkbox\" name=\"checkseleccionar".$i."\" id=\"checkseleccionar".$i."\" value=\"1\">";       
              $html .= "        <input type=\"hidden\" name=\"cantidad_fac".$i."\" value=\"".round($dtl['cantidad'])."\">\n";
              $html .= "        <input type=\"hidden\" name=\"precio_venta".$i."\" value=\"".$valor_unitario."\">\n";
              $html .= "        <input type=\"hidden\" name=\"codigo_producto".$i."\" value=\"".$dtl['codigo_producto']."\">\n";
              $html .= "        <input type=\"hidden\" name=\"fecha_vencimiento".$i."\" value=\"".$dtl['fecha_vencimiento']."\">\n";
              $html .= "        <input type=\"hidden\" name=\"lote".$i."\" value=\"".$dtl['lote']."\">\n";

    				$html .= "      </td>\n";
    			  $html .= "  </tr>\n";
            $i++;
    				}
             $html .= "	</table>\n";
            $html .= "</fieldset><br>\n";
          
    			}
            
    			else
    			{
    			if($request)
    				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
    			}
        
          $html .= "	<table  width=\"10%\" align=\"right\" >\n";
          $html .= "    <tr>\n";
          $html .= "	    <td width=\"30%\" align=\"center\">\n";
          $html .= "			  <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"DEVOLUCION\"  onclick=\"ValidarInformacion(document.Forma13)\" >\n";
          $html .= "		  </td>\n";
          $html .= "	  </tr>\n";
          $html .= "  </table><br>\n";
    			$html .= "<table align=\"center\" width=\"100%\">\n";
    			$html .= "  <tr>\n";
    			$html .= "    <td align=\"center\">\n";
    			$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
    			$html .= "        VOLVER\n";
    			$html .= "      </a>\n";
    			$html .= "    </td>\n";
    			$html .= "  </tr>\n";
    			$html .= "</table>\n";
          $html .= "</form>\n";
     			$html .= ThemeCerrarTabla();
    			return $html;
		} 
  /* Funcion que Contiene la Forma  para crear la anulacion de la factura
    *
    * @param array $action vector que contiene los link de la aplicacion
    *
    * @return String 
    */	  
      function FormaCrearDevolucionTotal($action,$empresa,$Informacion_Terc,$prefijo_venta,$factura_fis,$informacion_general,$informacion_detalle,$total_fa,$motivos)
      {  
		$ctl = AutoCarga::factory("ClaseUtil"); 
		$html .= $ctl->LimpiarCampos();
		$html .= $ctl->AcceptNum();
		$html .= "<script>\n";
		$html .= "  function ValidarDatos(frm)\n";
		$html .= "  {\n";
		$html .= "    if(document.getElementById('motivo_anula').value == '-1')\n";
		$html .= "    {\n";
		$html .= "       document.getElementById('error').innerHTML = 'FAVOR SELECCIONE EL MOTIVO DE ANULACION';\n";
		$html .= "       return;\n";
		$html .= "    }\n";
		$html .= "    frm.submit();\n";
		$html .= "  }\n";
		$html .= "</script>\n";
		$html .= ThemeAbrirTabla("CREAR DOCUMENTO");

		$html .= "<form name=\"Forma14\" id=\"Forma14\" method=\"post\"  action=\"".$action['anulacion']."\">\n";
		$html .= "  <table width=\"60%\"  class=\"modulo_table_list\" align=\"center\" border=\"0\" >\n";
		$html .= "    <tr class=\"formulacion_table_list\">\n";
		$html .= "	    <td align=\"left\">EMPRESA:</td>\n";
		$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">".$empresa['descripcion1']."-".$empresa['descripcion2']."\n";
		$html .= "      </td>\n";
		$html .= "	  </tr>\n";
		$html .= "    <tr class=\"formulacion_table_list\">\n";
		$html .= "	    <td align=\"left\">TERCERO:</td>\n";
		$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">".$Informacion_Terc."\n";
		$html .= "      </td>\n";
		$html .= "	  </tr>\n";
		$html .= "    <tr class=\"formulacion_table_list\">\n";
		$html .= "	    <td align=\"left\">FACTURA:</td>\n";
		$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">".$prefijo_venta." -".$factura_fis."\n";
		$html .= "      </td>\n";
		$html .= "	  </tr>\n";
		$html .= "    <tr class=\"formulacion_table_list\">\n";
		$html .= "	    <td width=\"22%\" align=\"left\">TOTAL FACTURA:</td>\n";
		$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">$".round($total_fa)."\n";
		$html .= "      </td>\n";
		$html .= "        <input type=\"hidden\" name=\"total_costo\" value=\"".$total_fa."\">\n";
		$html .= "	  </tr>\n";
		$html .= "  </table>\n";
		$html .=" <br>";
		$html .="	<table align=\"center\" cellpading=\"0\"  width=\"60%\" border=\"0\" class=\"modulo_table_list\">\n";
		$html .="		<tr class=\"formulacion_table_list\">\n";
		$html .="		<td width=\"25%\">MOTIVO ANULACIÓN</td>\n";
		$html .="			<td  align=\"left\"  class=\"modulo_list_claro\" >\n";
		$html .="			<select name=\"motivo_anula\" id=\"motivo_anula\" class=\"select\">\n";
		$html .="				<option value='-1' >----SELECCIONAR----</option>\n";
		$csk = "";
    		foreach($motivos as $indice => $valor)
    		{
    			$html .= "  <option value=\"".$valor['motivo_id']."\" >".$valor['motivo_descripcion']."</option>\n";
    		}
			$html .="			</select>\n";
			$html .="			</td>\n";
			$html .="		</tr>\n";
			$html .="		<tr class=\"formulacion_table_list\">\n";
			$html .="			<td colspan=\"2\">OBSERVACIÓN</td>\n";
			$html .="		</tr>\n";
			$html .="			<tr class=\"modulo_table_list_title\">\n";
			$html .="				<td colspan=\"2\" align=\"right\">\n";
			$html .="					<textarea name=\"observacion\" class=\"textarea\" style=\"width:100%\" rows=\"2\">".$this->request[3]."</textarea>\n";
			$html .="				</td>\n";
			$html .="			</tr>\n";
			$html .="		</table>\n";

			$html .= "	<table height=\"40\" width=\"50%\" align=\"center\" >\n";
			$html .= "    <tr>\n";
			$html .= "	    <td width=\"50%\" align=\"center\">\n";
			$html .= "			  <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"ANULAR\"  onclick=\"ValidarDatos(document.Forma14)\" >\n";
			$html .= "		  </td>\n";
			$html .= "			<td width=\"50%\" align=\"center\">\n";
			$html .= "			  <input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.Forma14)\" value=\"Limpiar Campos\">\n";
			$html .= "	  	</td>\n";
			$html .= "	  </tr>\n";
			$html .= "  </table>\n";



			$html .="<div align=\"center\" class=\"label_error\" id='error'></div>";
			$html .= "<table align=\"center\" width=\"100%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
			$html .= "        VOLVER\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
			$html .= ThemeCerrarTabla();
  			return $html;
      } 
  /* Funcion que Contiene la Forma  del mensaje por anulacion de factura
    *
    * @param array $action vector que contiene los link de la aplicacion
    *
    * @return String 
    */	 
      function FormaMensaje($action,$prefijo_venta,$factura_fis,$numeracion,$bodegas_doc_id)
      {
        
		$html .= ThemeAbrirTabla("MENSAJE");
		$html .= "<form name=\"Forma14\" id=\"Forma14\" method=\"post\"  action=\"".$action['anulacion']."\">\n";
		$html .= "  <table width=\"60%\"  class=\"modulo_table_list\" align=\"center\" border=\"0\" >\n";
		$html .= "    <tr class=\"formulacion_table_list\">\n";
		$html .= "	    <td align=\"CENTER\">SE ANULO LA FACTURA Nº:&nbsp;&nbsp;&nbsp;  ".$prefijo_venta."  ".$factura_fis."</td>\n";
		$html .= "	  </tr>\n";
		$html .= "  </table>\n";
		$html .=" <br>";
		$reporte = new GetReports(); 
		$mostrar = $reporte->GetJavaReport('app','DevolucionVenta','DevolucionVenta',
		array("prefijo_factura"=>$prefijo_venta,"factura_fiscal"=>$factura_fis,"numeracion"=>$numeracion,"bodegas_doc_id"=>$bodegas_doc_id),
		array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
		$funcion = $reporte->GetJavaFunction();
		$html .= "		<table  width=\"60%\"  class=\"modulo_table_list\" align=\"center\" border=\"0\" >\n";
		$html .= "				<td class=\"modulo_list_oscuro\" width=\"95%\">";
		$html .= "					IMPRIMIR DOCUMENTO";
		$html .= "				</td>";
		$html .= "				<td align=\"center\"  class=\"modulo_list_claro\" width=\"5%\">";
		$html .= "				".$mostrar."\n";
		$html .= "					<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE DEL PEDIDO\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' >\n";
		$html .= "					</a></center>\n";
		$html .= "			</td>\n";	
		$html .= "		</table>";
		$html .= "<table align=\"center\" width=\"100%\">\n";
		$html .= "  <tr>\n";
		$html .= "    <td align=\"center\">\n";
		$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
		$html .= "        VOLVER\n";
		$html .= "      </a>\n";
		$html .= "    </td>\n";
		$html .= "  </tr>\n";
		$html .= "</table>\n";
		$html .= "</form>\n";
		$html .= ThemeCerrarTabla();
		return $html;
    
      }
  /* Funcion que Contiene la Forma  de crear la devolucion parcial de la factura
    *
    * @param array $action vector que contiene los link de la aplicacion
    *
    * @return String 
    */
      function FormaCrearDevolucion_Parcial($action,$empresa,$Informacion_Terc,$prefijo_venta,$factura_fis,$informacion_general,$informacion_detalle,$total_fa,$motivos,$costo_factura)
      {  
		$ctl = AutoCarga::factory("ClaseUtil"); 
		$html .= $ctl->LimpiarCampos();
		$html .= $ctl->AcceptNum();
		$html .= "<script>\n";
		$html .= "  function ValidarDatos(frm)\n";
		$html .= "  {\n";
		$html .= "    if(document.getElementById('concepto').value == '-1')\n";
		$html .= "    {\n";
		$html .= "       document.getElementById('error').innerHTML = 'FAVOR SELECCIONE EL CONCEPTO DE DEVOLUCION';\n";
		$html .= "       return;\n";
		$html .= "    }\n";
		$html .= "    frm.submit();\n";
		$html .= "  }\n";
		$html .= "</script>\n";
		$html .= ThemeAbrirTabla("CREAR DOCUMENTO");

		$html .= "<form name=\"Forma15\" id=\"Forma15\" method=\"post\"  action=\"".$action['parcial']."\">\n";
		$html .= "  <table width=\"60%\"  class=\"modulo_table_list\" align=\"center\" border=\"0\" >\n";
		$html .= "    <tr class=\"formulacion_table_list\">\n";
		$html .= "	    <td align=\"left\">EMPRESA:</td>\n";
		$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">".$empresa['descripcion1']."-".$empresa['descripcion2']."\n";
		$html .= "      </td>\n";
		$html .= "	  </tr>\n";
		$html .= "    <tr class=\"formulacion_table_list\">\n";
		$html .= "	    <td align=\"left\">TERCERO:</td>\n";
		$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">".$Informacion_Terc."\n";
		$html .= "      </td>\n";
		$html .= "	  </tr>\n";
		$html .= "    <tr class=\"formulacion_table_list\">\n";
		$html .= "	    <td align=\"left\">FACTURA:</td>\n";
		$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">".$prefijo_venta." -".$factura_fis."\n";
		$html .= "      </td>\n";
		$html .= "	  </tr>\n";
		$html .= "    <tr class=\"formulacion_table_list\">\n";
		$html .= "	    <td width=\"22%\" align=\"left\">TOTAL FACTURA:</td>\n";
		$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">$".round($total_fa)."\n";
		$html .= "      </td>\n";
		$html .= "        <input type=\"hidden\" name=\"total_costo\" value=\"".$total_fa."\">\n";
		$html .= "	  </tr>\n";
		$html .= "  </table>\n";
		$html .=" <br>";
		$devolver_tercero=$total_fa - $costo_factura;
		$html .= "        <input type=\"hidden\" name=\"valor_productos\" value=\"".$costo_factura."\">\n";
		$html .= "        <input type=\"hidden\" name=\"total_devolver\" value=\"".$devolver_tercero."\">\n";

		$html .= "  <table width=\"45%\"  class=\"modulo_table_list\" align=\"center\" border=\"0\" >\n";
		$html .= "    <tr class=\"formulacion_table_list\">\n";
		$html .= "	    <td align=\"left\">VALOR PRODUCTOS A DEVOLVER:</td>\n";
		$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">$".round($costo_factura)."\n";
		$html .= "      </td>\n";
		$html .= "	  </tr>\n";
		$html .= "    <tr class=\"formulacion_table_list\">\n";
		$html .= "	    <td align=\"left\">TOTAL A DEVOLVER:</td>\n";
		$html .= "		  <td colspan=\"7\" class=\"modulo_list_claro\" align=\"left\">$".round($devolver_tercero)."\n";
		$html .= "      </td>\n";
		$html .= "	  </tr>\n";
		$html .= "  </table>\n";
		$html .=" <br>";

		$html .="	<table align=\"center\" cellpading=\"0\"  width=\"60%\" border=\"0\" class=\"modulo_table_list\">\n";
		$html .="		<tr class=\"formulacion_table_list\">\n";
		$html .="		<td width=\"25%\">CONCEPTO</td>\n";
		$html .="			<td  align=\"left\"  class=\"modulo_list_claro\" >\n";
		$html .="			<select name=\"concepto\" id=\"concepto\" class=\"select\">\n";
		$html .="				<option value='-1' >----SELECCIONAR----</option>\n";
		$csk = "";
		foreach($motivos as $indice => $valor)
		{
		$html .= "  <option value=\"".$valor['nota_contado_concepto_id']."\" >".$valor['descripcion']."</option>\n";
		}
		$html .="			</select>\n";
		$html .="			</td>\n";
		$html .="		</tr>\n";
		$html .="		<tr class=\"formulacion_table_list\">\n";
		$html .="			<td colspan=\"2\">OBSERVACIÓN</td>\n";
		$html .="		</tr>\n";
		$html .="			<tr class=\"modulo_table_list_title\">\n";
		$html .="				<td colspan=\"2\" align=\"right\">\n";
		$html .="					<textarea name=\"observacion\" class=\"textarea\" style=\"width:100%\" rows=\"2\">".$this->request[3]."</textarea>\n";
		$html .="				</td>\n";
		$html .="			</tr>\n";
		$html .="		</table>\n";

		$html .= "	<table height=\"40\" width=\"50%\" align=\"center\" >\n";
		$html .= "    <tr>\n";
		$html .= "	    <td width=\"50%\" align=\"center\">\n";
		$html .= "			  <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"REALIZAR DEVOLUCION\"  onclick=\"ValidarDatos(document.Forma15)\" >\n";
		$html .= "		  </td>\n";
		$html .= "			<td width=\"50%\" align=\"center\">\n";
		$html .= "			  <input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.Forma15)\" value=\"LIMPIAR CAMPOS\">\n";
		$html .= "	  	</td>\n";
		$html .= "	  </tr>\n";
		$html .= "  </table>\n";



		$html .="<div align=\"center\" class=\"label_error\" id='error'></div>";
		$html .= "<table align=\"center\" width=\"100%\">\n";
		$html .= "  <tr>\n";
		$html .= "    <td align=\"center\">\n";
		$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
		$html .= "        VOLVER\n";
		$html .= "      </a>\n";
		$html .= "    </td>\n";
		$html .= "  </tr>\n";
		$html .= "</table>\n";
		$html .= "</form>\n";
		$html .= ThemeCerrarTabla();
		return $html;
		} 
  /* Funcion que Contiene la Forma  del mensaje por devolucion parcial de la factura
    *
    * @param array $action vector que contiene los link de la aplicacion
    *
    * @return String 
    */ 
      function FormaMensajeParcial($action,$prefijo_venta,$factura_fis,$numeracion,$bodegas_doc_id)
      {
		$html .= ThemeAbrirTabla("MENSAJE");
		$html .= "<form name=\"Forma14\" id=\"Forma14\" method=\"post\"  action=\"".$action['anulacion']."\">\n";
		$html .= "  <table width=\"60%\"  class=\"modulo_table_list\" align=\"center\" border=\"0\" >\n";
		$html .= "    <tr class=\"formulacion_table_list\">\n";
		$html .= "	    <td align=\"CENTER\">SE REALIZO LA DEVOLUCION DE  LA FACTURA Nº:&nbsp;&nbsp;&nbsp;  ".$prefijo_venta."  ".$factura_fis." &nbsp;&nbsp; CORRECTAMENTE</td>\n";
		$html .= "	  </tr>\n";
		$html .= "  </table>\n";
		$html .=" <br>";
		$reporte = new GetReports(); 
		$mostrar = $reporte->GetJavaReport('app','DevolucionVenta','DevolucionVenta',
		array("prefijo_factura"=>$prefijo_venta,"factura_fiscal"=>$factura_fis,"numeracion"=>$numeracion,"bodegas_doc_id"=>$bodegas_doc_id),
		array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
		$funcion = $reporte->GetJavaFunction();
		$html .= "		<table  width=\"60%\"  class=\"modulo_table_list\" align=\"center\" border=\"0\" >\n";
		$html .= "				<td class=\"modulo_list_oscuro\" width=\"95%\">";
		$html .= "					IMPRIMIR DOCUMENTO";
		$html .= "				</td>";
		$html .= "				<td align=\"center\"  class=\"modulo_list_claro\" width=\"5%\">";
		$html .= "				".$mostrar."\n";
		$html .= "					<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE DEL PEDIDO\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' >\n";
		$html .= "					</a></center>\n";
		$html .= "			</td>\n";	
		$html .= "		</table>";
		$html .=" <br>";
		$html .= "<table align=\"center\" width=\"100%\">\n";
		$html .= "  <tr>\n";
		$html .= "    <td align=\"center\">\n";
		$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
		$html .= "        VOLVER\n";
		$html .= "      </a>\n";
		$html .= "    </td>\n";
		$html .= "  </tr>\n";
		$html .= "</table>\n";
		$html .= "</form>\n";
		$html .= ThemeCerrarTabla();
		return $html;
      
      }
   
  }
?>