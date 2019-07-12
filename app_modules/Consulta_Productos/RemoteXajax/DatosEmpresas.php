<?php
	/**
	* Archivo Xajax
	* Tiene como responsabilidad hacer el manejo de las funciones
	* que son invocadas por medio de xajax
	*
	* @package IPSOFT-SIIS
	* @version $Revision: 1.25 $
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	
	
	/*  GENERAR DOCUMENTO DE INGRESO A PARTIR DE UN DOCUMENTO DE DESPACHO */
	/*
		* Funcion que permite consultar los prefijos que existen en  la Bodega Principal de la Empresa Principal
		* @param array $form vector con la informacion de los campos ingresados en el formulario
		* @return object $objResponse objeto de respuesta al formulario
	*/
		function MostrarPrefijos($form)
		{
			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
			$lab = $sel->ObtenerDocumentoFarmacia($form['empresas']) ;
      
			$html  = "document.formita.prefijo.options.length = 0;\n";
			$html .= "document.formita.prefijo.options[0] = new Option('--SEL--','-1',false, false);\n";
			$i = 1;
			foreach($lab as $key => $dtl)
			{
				$html .= "document.formita.prefijo.options[".($i++)."] = new Option('".$dtl['prefijo']."','".$dtl['prefijo']."',false, false);\n";
			}
			$objResponse->script($html);
			return $objResponse;
		}
	/*
		* Forma para Transferir las variables para poder Generar El documento de Ingreso.
		* @return object $objResponse objeto de respuesta al formulario.
	*/
	
	/*Mostrar Empresas
	*/
	 function BusquedaEmpresa()
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $sql=new AdminFarmaciaSQL();  
        $datos=$sql->ListarEmpresas();
        
        if(empty($datos))
		$html = "<option value=\"\">-- SELECCIONAR --</option>";
		foreach($datos as $key=>$valor)
		{
		$html .= "<option value=\"".$valor['empresa_id']."\">".$valor['razon_social']."</option>";
		}
        
        $objResponse->assign("empresa_","innerHTML",$html);
        return $objResponse;
  }
		function TransVariables($observar,$empresa,$bodega,$cen,$abrev_estado,$prefijo,$numeracion)
		{
		
		
			$objResponse = new xajaxResponse();
      
		
			$url=ModuloGetURL("app", "AdminFarmacia", "controller", "GenerarDocumento", array("bodega"=>$bodega,"centroU"=>$cen,"EmpresaE"=>$empresa,"observacion"=>$observar,"abrev_estado"=>$abrev_estado,"prefijo"=>$prefijo,"numeracion"=>$numeracion));
			$objResponse->script('
						 window.location="'.$url.'";
							');
		    return $objResponse;
		}
	/*
		* Funcion que permite De Acuerdo a la Verificacion de los productos realizar Diferentes Operaciones:
		* @param variable $noMarcados variable que contiene cuantos productos no han sido seleccionados.
		* @param variable $Marcados variable que contiene cuantos productos  han sido seleccionados.
		* @param array $cadenaMarc vector con el codigo de cada uno de los  Productos seleccionados. 
		* @param array $descMarc vector con la descripcion de cada uno de los productos seleccionados.
		* @param array $cantidadM vector con la cantidad de cada producto  seleccionado.
		* @param array $porcentaje_gravamenM vector con el porcentaje de cada uno de los productos seleccionados.
		* @param array $total_costoM vector con el total de costo de cada uno de los productos seleccionados.
		* @param array $existencia_bodegaM vector con la cantidad de existencias  en bodega de cada producto seleccionado.
		* @param array $existencia_inventarioM vector con la cantidad de existencias  en inventario de cada producto seleccionado.
		* @param array $costo_inventarioM vector con el costo de  cada producto seleccionado.
		* @param array $cadenitaNoMar vector con el codigo de los  Productos no seleccionados. 
		* @param array $descNoM  vector con la descripcion de los productos no seleccionados.
		* @param array $fechav  vector con la fehca de vencimiento de cada uno de los productos seleccionados.
		* @param array $lote  vector con el lote de cada uno  de los productos seleccionados.
		* @param variable  $prefijo contiene el prefijo del documento de Egreso
		* @param variable  $numero contiene el numero  del documento de Egreso
		* @param variable  $empresa contiene el codigo de la empresa principal
		* @param variable  $farmacia contiene el codigo de la farmacia seleccionada
		* @param variable  $bodega contiene el codigo de la bodega de la farmacia seleccionada.
		* @param variable  $cen contiene el centro de utilidad de  la farmacia seleccionada.
		* @return object $objResponse objeto de respuesta al formulario
	*/
		function OrganizarInfor($noMarcados,$Marcados,$cadenaMarc,$descMarc,$cantidadM,$porcentaje_gravamenM,$total_costoM,$existencia_bodegaM,$existencia_inventarioM,$costo_inventarioM,$cadenitaNoMar,$descNoM,$fechav,$lote,$prefijo,$numero,$empresa,$farmacia,$bodega,$cen,$fechaNM,$loteNM)
		{
			$objResponse = new xajaxResponse();
			
			if($Marcados==0 && $noMarcados!=0)
			{
				for($j=0;$j<=$noMarcados;$j++)	
				{					
					$objResponse->script('
					var codigo_producto=document.getElementById(\'codigo_producto'.$j.'\').value;
					var descripcion=document.getElementById(\'descripcion'.$j.'\').value;
					xajax_MarcadoNull();
					');
			    }
			}else 
				if($Marcados!=0 && $noMarcados==0)
				{
					$num=count($cadenaMarc);
					for($k=0;$k<$num;$k++)
					{	
						$objResponse->script('
						var codigo_producto=document.getElementById(\'codigo_producto'.$k.'\').value;
						var descripcion=document.getElementById(\'descripcion'.$k.'\').value;
						xajax_GuardarTemporalProductCompletos(\''.$cadenaMarc[$k].'\',\''.$empresa.'\',\''.$prefijo.'\',\''.$numero.'\',\''.$farmacia.'\',\''.$cantidadM[$k].'\',\''.$porcentaje_gravamenM[$k].'\',\''.$total_costoM[$k].'\',\''.$existencia_bodegaM[$k].'\',\''.$existencia_inventarioM[$k].'\',\''.$costo_inventarioM[$k].'\',\''.$fechav[$k].'\',\''.$lote[$k].'\',\''.$bodega.'\');
						');
					}
					$objResponse->script('
						xajax_MostrarFormaProductosCompleta(\''.$empresa.'\',\''.$prefijo.'\',\''.$numero.'\',\''.$farmacia.'\');
						');
			   }else
		        	if($Marcados!=0 && $noMarcados!=0)
				{		
					$num=count($cadenaMarc);
					for($k=0;$k<$num;$k++)
					{	
						$objResponse->script('
						var codigo=document.getElementById(\'codigo_producto'.$k.'\').value;
						var des=document.getElementById(\'descripcion'.$k.'\').value;
						xajax_GuardarTemporalProductCompletos(\''.$cadenaMarc[$k].'\',\''.$empresa.'\',\''.$prefijo.'\',\''.$numero.'\',\''.$farmacia.'\',\''.$cantidadM[$k].'\',\''.$porcentaje_gravamenM[$k].'\',\''.$total_costoM[$k].'\',\''.$existencia_bodegaM[$k].'\',\''.$existencia_inventarioM[$k].'\',\''.$costo_inventarioM[$k].'\',\''.$fechav[$k].'\',\''.$lote[$k].'\',\''.$bodega.'\');
						');
					}
					$num=count($cadenitaNoMar);
					for($k=0;$k<$num;$k++)
					{	
						$objResponse->script('
						var codigo_producto=document.getElementById(\'codigo_producto'.$k.'\').value;
						var descripcion=document.getElementById(\'descripcion'.$k.'\').value;
						xajax_GuardarTemporal(\''.$cadenitaNoMar[$k].'\',\''.$empresa.'\',\''.$prefijo.'\',\''.$numero.'\',\''.$farmacia.'\',\''.$fechaNM[$k].'\',\''.$loteNM[$k].'\');
						');
				   }
				}
		   return $objResponse;
		}
	/*
		* Forma que muestra un Mensaje de aviso al Usuario cuando no se a seleccionado Ningun Producto
		* @return object $objResponse objeto de respuesta al formulario
	*/	
		function MarcadoNull()
		{  
			$objResponse = new xajaxResponse();
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend  class=\"label_error\"  align=\"center\"> </legend>\n";
			$html .= "<form name=\"Forma8\" id=\"Forma8\" method=\"post\" >\n";
			$html  .= "  <table border=\"0\" align=\"center\" width=\"65%\">\n";
			$html .= "		<tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">DEBE SELECCIONAR AL MENOS UN PRODUCTO PARA GENERAR EL DOCUMENTO\n";
		    $html .= " </td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "  </form>\n";
			$html .= "</fieldset><br>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		}
	/*
		* Funcion que permite Guardar los producto seleccionados en la tabla  producto_verificados_tmp (tabla temporal).
		* @return object $objResponse objeto de respuesta al formulario */
		function GuardarTemporalProductCompletos($cadenaMarc,$empresa,$prefijo,$numero,$farmacia,$cantidadM,$porcentaje_gravamenM,$total_costoM,$existencia_bodegaM,$existencia_inventarioM,$costo_inventarioM,$fechav,$lote,$bodega)
		{
		    $objResponse = new xajaxResponse();
   
			$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
			$del=$sel->IngresarTemporalmenteProduc($cadenaMarc,$empresa,$prefijo,$numero,$farmacia,$cantidadM,$porcentaje_gravamenM,$total_costoM,$existencia_bodegaM,$existencia_inventarioM,$costo_inventarioM,$fechav,$lote,$bodega);
			return $objResponse;
		}
		/*
		* Funcion que permite Mostrar un Mensaje antes de Generar el documento de Ingreso.
		* @return object $objResponse objeto de respuesta al formulario.
		*/	
		function MostrarFormaProductosCompleta($empresa,$prefijo,$numero,$farmacia)
		{                	
			$objResponse = new xajaxResponse();
 
			$html  = "<form name=\"Forma10\" id=\"Forma10\" method=\"post\" >\n";
			$html  .= "  <table class=\"modulo_table_list\" border=\"0\" align=\"center\" width=\"80%\">\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td  class=\"formulacion_table_list\"  width=\"10%\" align=\"center\">TODOS LOS PRODUCTOS FUERON VERIFICADOS Y SERAN INCLUIDOS AL GENERAR EL DOCUMENTO DE INGRESO DE PRODUCTOS\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";
			$html .= "  </table>\n";
			$html .= "  <br>\n";
			$html  .= "  <table class=\"modulo_table_list\" border=\"0\" align=\"center\" width=\"80%\">\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td  class=\"formulacion_table_list\" width=\"10%\" align=\"center\">OBSERVACIÒN \n";
			$html .= "      </td>\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "    </tr>\n";
			$html .= "      <td colspan=\"5\"  align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "        <textarea  name=\"observar\" rows=\"2\" style=\"width:100%\"></textarea>\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";
			$html .= "  </table>\n";
			$html .= "  <br>\n";
			$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
			$html .= "		<tr>\n";
			$html .= "      <td align=\"center\">\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btn\"   value=\"GENERAR DOCUMENTO\" onclick=\"ValidarDtos(document.Forma10);\" > ";                                                         
			$html .= " </td>\n";			
			$html .= "      <td align=\"center\">\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CANCELAR\" onclick=\"xajax_EliminarProductosTodosV('".$empresa."','".$prefijo."','".$numero."','".$farmacia."');\">  \n";
			$html .= " </td>\n";
			$html .= "		<tr>\n";
			$html .= "	</table>\n";
			$html .= "  </form>\n";
			$html .= "</fieldset><br>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		}	
	/*
		* Funcion que permite Eliminar Los producto Verificados de la Tabla Temporal (tabla  producto_verificados_tmp).
	*/
		function EliminarProductosTodosV($empresa,$prefijo,$numero,$farmacia)
		{
			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
			$del=$sel->EliminarProductosVerificados($prefijo,$numero,$empresa,$farmacia);
			$objResponse->call("OcultarSpan");
			return $objResponse;
		}

	/*
		*Funcion que Guarda Temporamente informacion del producto que ha quedado pendiente al generar un documento.
		* @param variable  $codigo_producto contiene el codigo_producto no seleccionado.
		* @param variable  $empresa contiene el codigo de la empresa principal.
		* @param variable  $prefijo contiene el prefijo del documento de Egreso.
		* @param variable  $numero contiene el numero  del documento de Egreso.
		* @param variable  $farmacia contiene el codigo de la farmacia seleccionada.
		* @return object $objResponse objeto de respuesta al formulario.
	*/	
		function GuardarTemporal($codigo_producto,$empresa,$prefijo,$numero,$farmacia,$fechav,$lote)
		{
			$objResponse = new xajaxResponse();
      
			$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
			$rst =$sel->IngresarTemporalmenteProducP($codigo_producto,$empresa,$prefijo,$numero,$farmacia,$fechav,$lote);
			$dat =$sel->ConsultarTemporal($empresa,$prefijo,$numero,$farmacia);
	
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend class=\"normal_10AN\" align=\"center\">PRODUCTOS QUE NO VAN HACER INCLUIDOS AL GENERAR EL DOCUMENTO DE INGRESO DE PRODUCTOS</legend>\n";
			$html .= "<form name=\"Forma8\" id=\"Forma8\" method=\"post\" >\n";
			$html  .= "  <table class=\"modulo_table_list\" border=\"0\" align=\"center\" width=\"80%\">\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td class=\"formulacion_table_list\"  width=\"10%\" align=\"center\">COD.PROD\n";
			$html .= "      </td>\n";
			$html .= "      <td class=\"formulacion_table_list\" width=\"20%\" align=\"center\">DESCRP.PRODUCTO\n";
			$html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"20%\" align=\"center\">FECHA V\n";
			$html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"20%\" align=\"center\">LOTE\n";
			$html .= "      </td>\n";
			$html .= "      <td  class=\"formulacion_table_list\" width=\"20%\" align=\"center\">ESTADO\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";
			foreach($dat as $key => $dtl)
			{
				$html .= "    <tr class=\"modulo_list_claro\">\n";
				$html .= "      <td width=\"20%\" align=\"center\">".$dtl['codigo_producto']."\n";
				$html .= "      </td>\n";
				$html .= "      <td width=\"20%\" align=\"center\">".$dtl['descripcion']." ".$dtl['unidad']." ".$dtl['contenido_unidad_venta']."\n";
				$html .= "      </td>\n";
        $html .= "      <td width=\"10%\" align=\"center\">".$dtl['fecha_vencimiento']."\n";
				$html .= "      </td>\n";
        $html .= "      <td width=\"10%\" align=\"center\"> ".$dtl['lote']."\n";
				$html .= "      </td>\n";
				$html .= "      <td width=\"20%\" align=\"center\" class=\"label_error\" >Pendiente por Verificar\n";
				$html .= "      </td>\n";
			}
			$html .= "  </table>\n";
			$html .= "  <br>\n";
			$html  .= "  <table class=\"modulo_table_list\" border=\"0\" align=\"center\" width=\"80%\">\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td  class=\"formulacion_table_list\" width=\"10%\" align=\"center\">* OBSERVACION\n";
			$html .= "      </td>\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "    </tr>\n";
			$html .= "      <td colspan=\"5\"  align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "        <textarea onkeypress=\"return max(event)\"  name=\"observar\" rows=\"2\" style=\"width:100%\"></textarea>\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";
			$html .= "  </table>\n";
			$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
			$html .= "		<tr>\n";
			$html .= "      <td align=\"center\" class=\"normal_10AN\" >\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"GENERAR DOCMENTO\" onclick=\"ValidarDtos(document.Forma8);\">\n";
			$html .= " </td>\n";
			$html .= "      <td align=\"center\">\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CANCELAR\" onclick=\"xajax_EliminarProductos('".$empresa."','".$prefijo."','".$numero."','".$farmacia."');\">   \n";
			$html .= "   </a>\n";
			$html .= " </td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "  </form>\n";
			$html .= "</fieldset><br>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		}
	/*
		* Funcion que permite Eliminar Los productos Ingresados Temporalmente
		* @return object $objResponse objeto de respuesta al formulario
	*/
		function EliminarProductos($empresa,$prefijo,$numero,$farmacia)
		{
			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
			$rst =$sel->EliminarProductosTempo($empresa,$prefijo,$numero,$farmacia);
			$objResponse->call("OcultarSpan");
			return $objResponse;
		}
	/*
		* Forma que permite transferir las variables para generar el documento de ingreso ademas de Eliminar los productos que han dejado de ser pendientes. 
		* @return object $objResponse objeto de respuesta al formulario.
		
	*/
		function TransVariablesP($observar,$empresa,$bodega,$cen,$prefijo,$numero,$farmacia,$abrev_estado)
		{
			$objResponse = new xajaxResponse();
	
			$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
			$rst =$sel->EliminarProductosPend($prefijo,$numero,$empresa,$farmacia);
			$url=ModuloGetURL("app", "AdminFarmacia", "controller", "GenerarDocumento", array("bodega"=>$bodega,"centroU"=>$cen,"EmpresaE"=>$empresa,"observacion"=>$observar,"abrev_estado"=>$abrev_estado,"prefijo"=>$prefijo,"numeracion"=>$numero));
			$objResponse->script('
						 window.location="'.$url.'";
							');
 
			return $objResponse;
		}
		/*
		* Funcion que permite De Acuerdo a la Verificacion de los productos realizar Diferentes Operaciones siempre y cuando existan productos pedientes:
		* @param variable $noMarcados variable que contiene cuantos productos no han sido seleccionados.
		* @param variable $Marcados variable que contiene cuantos productos  han sido seleccionados.
		* @param array $cadenaMarc vector con el codigo de cada uno de los  Productos seleccionados. 
		* @param array $descMarc vector con la descripcion de cada uno de los productos seleccionados.
		* @param array $cantidadM vector con la cantidad de cada producto  seleccionado.
		* @param array $porcentaje_gravamenM vector con el porcentaje de cada uno de los productos seleccionados.
		* @param array $total_costoM vector con el total de costo de cada uno de los productos seleccionados.
		* @param array $existencia_bodegaM vector con la cantidad de existencias  en bodega de cada producto seleccionado.
		* @param array $existencia_inventarioM vector con la cantidad de existencias  en inventario de cada producto seleccionado.
		* @param array $costo_inventarioM vector con el costo de  cada producto seleccionado.
		* @param array $cadenitaNoMar vector con el codigo de los  Productos no seleccionados. 
		* @param array $descNoM  vector con la descripcion de los productos no seleccionados.
		* @param array $fechav  vector con la fehca de vencimiento de cada uno de los productos seleccionados.
		* @param array $lote  vector con el lote de cada uno  de los productos seleccionados.
		* @param variable  $prefijo contiene el prefijo del documento de Egreso
		* @param variable  $numero contiene el numero  del documento de Egreso
		* @param variable  $empresa contiene el codigo de la empresa principal
		* @param variable  $farmacia contiene el codigo de la farmacia seleccionada
		* @param variable  $bodega contiene el codigo de la bodega de la farmacia seleccionada.
		* @param variable  $cen contiene el centro de utilidad de  la farmacia seleccionada.
		* @return object $objResponse objeto de respuesta al formulario
	*/
		function OrganizarInforPend($noMarcados,$Marcados,$cadenaMarc,$descMarc,$cantidadM,$porcentaje_gravamenM,$total_costoM,$existencia_bodegaM,$existencia_inventarioM,$costo_inventarioM,$cadenitaNoMar,$descNoM,$fechav,$lote,$prefijo,$numero,$empresa,$farmacia,$bodega,$cen,$numeracion,$fechaNM,$loteNM)
		{
			$objResponse = new xajaxResponse();
			if($Marcados==0 && $noMarcados!=0)
			{
				for($j=0;$j<=$noMarcados;$j++)
				{	
					$objResponse->script('
					var codigo_producto=document.getElementById(\'codigo_producto'.$j.'\').value;
					var descripcion=document.getElementById(\'descripcion'.$j.'\').value;
					xajax_MarcadoNullPendientes();
					');
				}
			}else 
				if($Marcados!=0 && $noMarcados==0)
			    {
					$num=count($cadenaMarc);
					for($k=0;$k<$num;$k++)
					{	
						$objResponse->script('
						var codigo_producto=document.getElementById(\'codigo_producto'.$k.'\').value;
						var descripcion=document.getElementById(\'descripcion'.$k.'\').value;
						xajax_GuardarTemporalProductCompletos(\''.$cadenaMarc[$k].'\',\''.$empresa.'\',\''.$prefijo.'\',\''.$numero.'\',\''.$farmacia.'\',\''.$cantidadM[$k].'\',\''.$porcentaje_gravamenM[$k].'\',\''.$total_costoM[$k].'\',\''.$existencia_bodegaM[$k].'\',\''.$existencia_inventarioM[$k].'\',\''.$costo_inventarioM[$k].'\',\''.$fechav[$k].'\',\''.$lote[$k].'\',\''.$bodega.'\');
						');
					}
                        $objResponse->script('
						xajax_MostrarFormaProductosCompletaP(\''.$empresa.'\',\''.$prefijo.'\',\''.$numero.'\',\''.$farmacia.'\');
						');				
					
				}else
				if($Marcados!=0 && $noMarcados!=0)
				{	
					$objResponse->script('
						xajax_ElimanarConsulta(\''.$empresa.'\',\''.$prefijo.'\',\''.$numero.'\',\''.$farmacia.'\');
					');
					$num=count($cadenaMarc);
					for($k=0;$k<$num;$k++)
					{
						$objResponse->script('
						var codigo=document.getElementById(\'codigo_producto'.$k.'\').value;
						var des=document.getElementById(\'descripcion'.$k.'\').value;
					    xajax_GuardarTemporalProductCompletos(\''.$cadenaMarc[$k].'\',\''.$empresa.'\',\''.$prefijo.'\',\''.$numero.'\',\''.$farmacia.'\',\''.$cantidadM[$k].'\',\''.$porcentaje_gravamenM[$k].'\',\''.$total_costoM[$k].'\',\''.$existencia_bodegaM[$k].'\',\''.$existencia_inventarioM[$k].'\',\''.$costo_inventarioM[$k].'\',\''.$fechav[$k].'\',\''.$lote[$k].'\',\''.$bodega.'\');
						');
					}
					$num=count($cadenitaNoMar);
					for($k=0;$k<$num;$k++)
					{	
						$objResponse->script('
						var codigo_producto=document.getElementById(\'codigo_producto'.$k.'\').value;
						var descripcion=document.getElementById(\'descripcion'.$k.'\').value;
						xajax_GuardarTemporalPen(\''.$cadenitaNoMar[$k].'\',\''.$empresa.'\',\''.$prefijo.'\',\''.$numero.'\',\''.$farmacia.'\',\''.$fechaNM[$k].'\',\''.$loteNM[$k].'\');
						');
					}
				}
			return $objResponse;
		}    
	/*
	    *   para los productos Pendientes
			* Forma que muestra un Mensaje de aviso al Usuario cuando no se a seleccionado Ningun Producto
			* @return object $objResponse objeto de respuesta al formulario
	*/
		function MarcadoNullPendientes()
		{  
			$objResponse = new xajaxResponse();
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend  class=\"label_error\"  align=\"center\" </legend>\n";
			$html .= "<form name=\"Forma8\" id=\"Forma8\" method=\"post\" >\n";
			$html  .= "  <table border=\"0\" align=\"center\" width=\"80%\">\n";
			$html .= "		<tr>\n";
			$html .= "      <td  class=\"label_error\" align=\"center\">\n";
			$html .= " NO SE PUEDE GENERAR EL DOCUMENTO DE INGRESO DE LOS PRODUCTOS DEBE SELECCIONAR AL MENOS UN PRODUCTO</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "  </form>\n";
			$html .= "</fieldset><br>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		}
	
	/*
		* Para los productos Pendientes
		* Mostrar un Mensaje antes de Generar el documento de Ingreso.
		* @return object $objResponse objeto de respuesta al formulario.
	*/
	function MostrarFormaProductosCompletaP($empresa,$prefijo,$numero,$farmacia)
{            
			$objResponse = new xajaxResponse();
			
			$html  = "<form name=\"Forma12\" id=\"Forma12\" method=\"post\" >\n";
			$html  .= "  <table class=\"modulo_table_list\" border=\"0\" align=\"center\" width=\"80%\">\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td  class=\"formulacion_table_list\"  width=\"10%\" align=\"center\">TODOS LOS PRODUCTOS FUERON VERIFICADOS Y SERAN INCLUIDOS AL GENERAR EL DOCUMENTO DE INGRESO DE PRODUCTOS\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";
			$html .= "  </table>\n";
			$html .= "  <br>\n";
			$html  .= "  <table class=\"modulo_table_list\" border=\"0\" align=\"center\" width=\"80%\">\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td  class=\"formulacion_table_list\"  width=\"10%\" align=\"center\">OBSERVACIÒN \n";
			$html .= "      </td>\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "    </tr>\n";
			$html .= "      <td colspan=\"5\"  align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "        <textarea  name=\"observar\" rows=\"2\" style=\"width:100%\"></textarea>\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";
			$html .= "  </table>\n";
			$html .= "  <br>\n";
			$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
			$html .= "		<tr>\n";
			$html .= "      <td align=\"center\">\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btn\"   value=\"GENERAR DOCMENTO\" onclick=\"ValidarDtosP(document.Forma12);\" > ";                                                         
			$html .= " </td>\n";			
			$html .= "      <td align=\"center\">\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CANCELAR\" onclick=\"xajax_EliminarProductosTodosV('".$empresa."','".$prefijo."','".$numero."','".$farmacia."');\">  \n";
			$html .= " </td>\n";
			$html .= "		<tr>\n";
			$html .= "	</table>\n";
			$html .= "  </form>\n";
			$html .= "</fieldset><br>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		}
	/*
		*   para los productos Pendientes:
				*Funcion que permite hacer llamado a las sql para eliminar y consultar los productos pendientes.
				* @return object $objResponse objeto de respuesta al formulario.
		*/

		function ElimanarConsulta($empresa,$prefijo,$numero,$farmacia)
		{
			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
			$del=$sel->EliminarDatosDeLosProductos($prefijo,$numero,$empresa,$farmacia);
			return $objResponse;
		}
	/*
		*  para los productos Pendientes:
			* *Funcion que Guarda Temporamente informacion del producto que ha quedado pendiente al generar un documento
			* @param variable  $codigo_producto contiene el codigo_producto no seleccionado
			* @param variable  $empresa contiene el codigo de la empresa principal
			* @param variable  $prefijo contiene el prefijo del documento de Egreso
			* @param variable  $numero contiene el numero  del documento de Egreso
			* @param variable  $farmacia contiene el codigo de la farmacia seleccionada
			* @return object $objResponse objeto de respuesta al formulario
	*/
		function GuardarTemporalPen($codigo_producto,$empresa,$prefijo,$numero,$farmacia,$fechav,$lote)
		{
			$objResponse = new xajaxResponse();
     
			$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
			$rst =$sel->IngresarTemporalmenteProducP($codigo_producto,$empresa,$prefijo,$numero,$farmacia,$fechav,$lote);
			$dat =$sel->ConsultarTemporal($empresa,$prefijo,$numero,$farmacia);
			
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend class=\"normal_10AN\" align=\"center\">PRODUCTOS QUE NO VAN HACER INCLUIDOS AL GENERAR EL DOCUMENTO DE INGRESO DE PRODUCTOS </legend>\n";
			$html .= "<form name=\"Forma8\" id=\"Forma8\" method=\"post\" >\n";
			$html  .= "  <table class=\"modulo_table_list\" border=\"0\"  align=\"center\" width=\"80%\">\n";
			$html .= "    <tr  class=\"modulo_table_list_title\" >\n";
			$html .= "      <td class=\"formulacion_table_list\" width=\"10%\" align=\"center\">COD.PROD\n";
			$html .= "      </td>\n";
			$html .= "      <td class=\"formulacion_table_list\" width=\"20%\" align=\"center\">DESCRP.PRODUCTO\n";
			$html .= "      </td>\n";
		  $html .= "      <td class=\"formulacion_table_list\" width=\"20%\" align=\"center\">FECHA V\n";
			$html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"20%\" align=\"center\">LOTE\n";
			$html .= "      </td>\n";
			$html .= "      <td  class=\"formulacion_table_list\" width=\"20%\" align=\"center\">ESTADO\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";
			foreach($dat as $key => $dtl)
			{
				$html .= "    <tr class=\"modulo_list_claro\">\n";
				$html .= "      <td width=\"20%\" align=\"center\">".$dtl['codigo_producto']."\n";
				$html .= "      </td>\n";
				$html .= "      <td width=\"20%\" align=\"center\">".$dtl['descripcion']." ".$dtl['unidad']." ".$dtl['contenido_unidad_venta']."\n";
				$html .= "      </td>\n";
        $html .= "      <td width=\"10%\" align=\"center\">".$dtl['fecha_vencimiento']."\n";
				$html .= "      </td>\n";
        $html .= "      <td width=\"10%\" align=\"center\"> ".$dtl['lote']."\n";
				$html .= "      </td>\n";
        $html .= "      <td width=\"20%\" align=\"center\" class=\"label_error\" >Pendiente por Verificar\n";
				$html .= "      </td>\n";
			}
			$html .= "  </table>\n";
			$html .= "  <br>\n";
			
			$html  .= "  <table class=\"modulo_table_list_title\" border=\"1\" align=\"center\" width=\"80%\">\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td width=\"10%\" align=\"center\">* OBSERVACIÒN\n";
			$html .= "      </td>\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "    </tr>\n";
			$html .= "      <td colspan=\"5\"  align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "        <textarea onkeypress=\"return max(event)\"  name=\"observar\" rows=\"2\" style=\"width:100%\"></textarea>\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";
			$html .= "  </table>\n";
			$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
			$html .= "		<tr>\n";
			$html .= "      <td align=\"center\" class=\"normal_10AN\" >\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"GENERAR DOCMENTO\" onclick=\"ValidarDtosPen(document.Forma8);\">\n";
			$html .= " </td>\n";
			$html .= "      <td align=\"center\">\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CANCELAR\" onclick=\"xajax_EliminarProductos('".$empresa."','".$prefijo."','".$numero."','".$farmacia."');\">   \n";
			$html .= " </td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "  </form>\n";
			$html .= "</fieldset><br>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		}
	  
  /* EMPIEZA SOLO EL DOCUMENTO DE DEVOLUCION POR FECHA DE VENCIMIENTO 
  */
		function EmpresaDestino()
		{
			$objResponse = new xajaxResponse();
			$mdl = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
			$Empresas = $mdl->ListarEmpresas();
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
			$html .= "  <legend class=\"normal_10AN\" align=\"center\"></legend>\n";
			$html .= "<form name=\"EmpresaDestino\" id=\"EmpresaDestino\" method=\"post\" >\n";
			$html  .= "  <table  border=\"1\" align=\"center\" class=\"modulo_table_list\" width=\"85%\">\n";
			$html .= "    <tr  width=\"45%\" class=\"formulacion_table_list\">\n";
    	$html .= "			<td align=\"center\"><b>EMPRESA:</B></td>\n";
			$html .= "			<td  align=\"left\" class=\"modulo_list_claro\" colspan=\"5\">\n";
			$html .= "					<select name=\"empresas\" class=\"select\">\n";
			$html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
				foreach($Empresas as $id => $empresa)
				{
					if($empresa['empresa_id']==$request['empresa_id'])
					$sel = "selected";
					else   $sel = "";
					$html .= "  <option value=\"".$empresa['empresa_id']."\" ".$sel.">".$empresa['razon_social']."</option>\n";
				}
		    $html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html .= "    </tr>\n";
			$html .= "  </table><br>\n";
			$html .= "  <table width=\"85%\"  border=\"0\"  align=\"center\">";
			$html .= "		<tr>\n";
			$html .= "      <td align=\"center\">\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CONTINUAR\" onclick=\"validarEmpresaDestino(document.EmpresaDestino);\">   \n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "	</table>\n";
			$html .= "  </form>\n";
			$html .= "</fieldset><br>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		}
		
		function TransEmpresaDestino($empresa_destino)
		{
		    $objResponse = new xajaxResponse();
			
			$url=ModuloGetURL("app", "AdminFarmacia", "controller", "TipoDocumentos",array("empresa_destino"=>$empresa_destino));
			$objResponse->script('
						 window.location="'.$url.'";
								');
			return $objResponse;
		}
		function TransDocid($documento_id,$empresa_destino)
		{
		
			$objResponse = new xajaxResponse();
			
			$url=ModuloGetURL("app", "AdminFarmacia", "controller", "DocumentoTmp",array("documento_id"=>$documento_id,"empresa_destino"=>$empresa_destino));
			$objResponse->script('
						 window.location="'.$url.'";
								');
			return $objResponse;
		}
	/*
		* Forma que permite direccionar la informacion de acuerdo a lo que se a seleccionado 
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		function TransferirDatosTmp($empresa_destino,$observacion,$bodegas_doc_id,$doc_tmp_id,$abreviatura,$tipo_doc_general_id,$farmacia)
		{
	        $objResponse = new xajaxResponse();
        
         // $objResponse->alert($empresa_destino);
          
			$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
   		$rst =$sel->GrabarDocinv_bodegas_movimiento_tmp($empresa_destino,$bodegas_doc_id,$observacion,$doc_tmp_id,$abreviatura);
      $Estados =$sel->EstadosParamestadosdocum($tipo_doc_general_id,$farmacia);
	    $InsertEstados=$sel->Insertar_Estados_para_documentosg($Estados,$doc_tmp_id);
		  //	$ConEstados=$mdl->Consultarpara_documentosg($tipo_doc_general_id,$doc_tmp_id);
      
      $elim=$sel->Eliminar_AbrevParaDocumeng($tipo_doc_general_id,$doc_tmp_id,$abreviatura);
			
			$url=ModuloGetURL("app", "AdminFarmacia", "controller", "DevolucionFecha_vencimiento",array("doc_tmp_id"=>$doc_tmp_id));
			$objResponse->script('
						 window.location="'.$url.'";
								');
			return $objResponse;
		   
	    }
				
	/*
		* Forma que permite direccionar la informacion de acuerdo a lo que se a seleccionado 
		* @return object $objResponse objeto de respuesta al formulario.
	*/
				
		function OrganizarInfo($farmacia,$centro,$bodega,$tipo_doc_general_id)
		{
			$objResponse = new xajaxResponse();
			
			$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
			$rst =$sel->BuscarTemporalDevoluc($farmacia,$centro,$bodega);
		    $num=count($rst);
		    $url=ModuloGetURL("app", "AdminFarmacia", "controller", "GenerarDocumentotmp", array("tipo_doc_general_id"=>$tipo_doc_general_id,"estado"=>$estado));

		  $objResponse->script('
						  
				if('.$num.'.==0){
				xajax_MostrarMensaje();
				}else{
				
				if('.$num.'.!=0){
					 window.location="'.$url.'";
					
			
			  }
			  }
			  ');
			
			return $objResponse;
		
		}
		function DocumentosEstadosVerificar($empresa,$centro,$bodega,$documento,$tipo_doc_general_id)
		{
		
			$objResponse = new xajaxResponse();
			$mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
			$dat=$mdl->ConsultarInfomacionDocumento($empresa,$centro,$bodega,$documento);
			$inf=$mdl->consultarinformaciondocumento($documento);
			$da=$mdl->consutartmp($dat[0]['bodegas_doc_id']);
			//$vectorTMP=$sel->ObtenerTmpUsuario($empresa,$centro,$bodega);
         
		        $html .= "                 <table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
                $html .= "                    <tr class=\"modulo_table_list_title\">\n";
                $html .= "                       <td width=\"5%\" align=\"center\">\n";
                $html .= "                       <a title='CLASE DE DOCUMENTO'>";
                $html .= "                        C";
                $html .= "                       </a>";
                $html .= "                       </td>\n";
                $html .= "                       <td width=\"10%\" align=\"center\">\n";
                $html .= "                          TIPO DOC";
                $html .= "                       </td>\n";
                $html .= "                       <td width=\"50%\" align=\"center\">\n";
                $html .= "                          DESCRIPCION";
                $html .= "                       </td>\n";
				$html .= "                       <td width=\"25%\" align=\"center\">\n";
                $html .= "                          OBSERVACION";
                $html .= "                       </td>\n";
                $html .= "                       <td width=\"10%\" align=\"center\">\n";
                $html .= "                          FECHA";
                $html .= "                       </td>\n";
                $html .= "                       <td width=\"5%\" align=\"center\">\n";
                $html .= "                          ACCIONES";
                $html .= "                       </td>\n";
				$html .= "                       <td width=\"5%\" align=\"center\">\n";
                $html .= "                          ELIMINAR";
                $html .= "                       </td>\n";
                $html .= "                   </tr>\n";

				foreach($da as $valor=>$fila)
                {
                    foreach($inf as $valor=>$valorClase)
					{
                            $html .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
							$html .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
							$html .= "                       E ";
							$html .= "                      </td>\n";
							$html .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
							$html .= "                       ".$valorClase['tipo_doc_general_id'];
							$html .= "                      </td>\n";
							$html .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
							$html .= "                       ".$valorClase['descripcion'];
							$html .= "                      </td>\n";
					}

							$html .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
							$html .= "                       ".$fila['observacion'];
							$html .= "                       </td>\n";
							$html .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
							$html .= "                       ".substr($fila['fecha_registro'],0,10);
							$html .= "                       </td>\n";
							$html .= "                       <td  align=\"center\">\n";
							$html .= "                        <a href=\"#\" onclick=\"xajax_ConsultarDoc_tmp('".$fila['doc_tmp_id']."','".$tipo_doc_general_id."');\">";
							$html .= "                          <img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\" width=\"15\" height=\"17\"></sub>\n";
							$html .= "                         </a>\n";
							$html .= "                       </td>\n";
							$html .= "                       <td  align=\"center\">\n";
							$html .= "                        <a href=\"#\" onclick=\"xajax_Eliminardoc_tmp_id('".$fila['doc_tmp_id']."','".$tipo_doc_general_id."');\">";
							$html .= "                          <img src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\" width=\"15\" height=\"17\"></sub>\n";
							$html .= "                         </a>\n";
							$html .= "                       </td>\n";
							$html .= "                    </tr>\n";

				}
            		
		    $html .= "	</table>\n";
			$objResponse->assign("DocumentosTmp","innerHTML",$objResponse->setTildes($html));
			return $objResponse;
		}
		
		function Eliminardoc_tmp_id($docitm,$tipo_doc_general_id)
		{
			$objResponse = new xajaxResponse();
			$empresa_destino = SessionGetVar("empresa_de");
			$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
			$rst =$sel->EliminartmpCompleto($docitm);
			$rst =$sel->Eliminar_AbreveParaDocumeng($tipo_doc_general_id,$docitm);
		    $url=ModuloGetURL("app", "AdminFarmacia", "controller", "TipoDocumentos",array("empresa_destino"=>$empresa_destino));

			$objResponse->script('
				 window.location="'.$url.'";
				  
			  ');
			return $objResponse;
		}
		
        function  ConsultarDoc_tmp($iditm,$tipo_doc_general_id)
		{
			$objResponse = new xajaxResponse();
			$url=ModuloGetURL("app", "AdminFarmacia", "controller", "ConsultarDtosTmp", array("iditm"=>$iditm,"tipo_doc_general_id"=>$tipo_doc_general_id));
			$objResponse->script('
						  	window.location="'.$url.'";
			');
			return $objResponse;
		}
		  
		function EliminadocTmp_d($timd,$codigo_producto,$tipo_doc_general_id)
		{
		 	$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
			$rst =$sel->EloiminarDoctmp_d($timd,$codigo_producto);
			$url=ModuloGetURL("app", "AdminFarmacia", "controller", "ConsultarDtosTmp",array("iditm"=>$timd,"tipo_doc_general_id"=>$tipo_doc_general_id));
			$objResponse->script('
			window.location="'.$url.'";
			');
				
				return $objResponse;
		}
		  
		  function TrasnpoVerifEstados($doc_tmp_id,$abreviatura,$tipo_doc_general_id)
		  {
		        $objResponse = new xajaxResponse();
				$empresa_destino = SessionGetVar("empresa_de");
				$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
				$rst =$sel->ActuEstadotmp($abreviatura,$doc_tmp_id,$tipo_doc_general_id);
				$url=ModuloGetURL("app", "AdminFarmacia", "controller", "TipoDocumentos",array("empresa_destino"=>$empresa_destino));
				$objResponse->script('
				window.location="'.$url.'";
				');
				return $objResponse;
		  }
		  
		  function TrasnpoDocumGenerarE($doc_tmp_id)
		  {
			    $objResponse = new xajaxResponse();
				$url=ModuloGetURL("app", "AdminFarmacia", "controller", "GenerarDocumentoRealE",array("doc_tmp_id"=>$doc_tmp_id));
				$objResponse->script('
				window.location="'.$url.'";
				');
				
				return $objResponse;
		  
		  
		  }
	
		function ValidarDatosProducto($value,$codigo_producto,$fecha_vencimiento,$lote,$far,$Centrid,$bod,$costo,$existencia_actual)
		{	   					       

				$objResponse = new xajaxResponse();
        $cadena=$codigo_producto." ".$fecha_vencimiento." ".$lote;
        
				$objResponse->script('
				
         
			  if(document.getElementById(\'Enviar'.$cadena.'\').checked==true &&  document.getElementById(\'cantidad'.$cadena.'\').value=="" ){
				 document.getElementById("error").innerHTML = "DEBE INGRESAR LA CANTIDAD";
				}
        else
        {
			  	if(document.getElementById(\'cantidad'.$cadena.'\').value > '.$existencia_actual.' )
          {
            document.getElementById("error").innerHTML = "DEBE INGRESAR UNA CANTIDAD MENOR  A LA EXISTENCIA";
				  }
          else
          {
         
            if(document.getElementById(\'Enviar'.$cadena.'\').checked==true && document.getElementById(\'cantidad'.$cadena.'\').value!="")
            {
              
                var total_costo= document.getElementById(\'cantidad'.$cadena.'\').value * '.$costo.';							
           
                xajax_InsertarProductoDevol_tmp("'.$far.'", "'.$Centrid.'", "'.$bod.'","'.$codigo_producto.'",document.getElementById(\'cantidad'.$cadena.'\').value,"'.$fecha_vencimiento.'","'.$lote.'",total_costo);
            } else 
				    {
				       
                  xajax_EliminarProd_Devol_tmp("'.$far.'", "'.$Centrid.'", "'.$bod.'","'.$codigo_producto.'","'.$fecha_vencimiento.'","'.$lote.'");
			
				    }
          }
        }
        
        
        
        
        
        
				 ');
				  
			return $objResponse;
        }
	/*
		* Forma que permite Insertar en la tabla Doc_Devolucion_tmp. 
		* @return object $objResponse objeto de respuesta al formulario.
		
	*/
		function InsertarProductoDevol_tmp($far,$Centrid,$bod,$codigo_producto,$cantidad,$fecha_vencimiento,$lote,$total_costo)
		{	
			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
			$rst =$sel->IngresarProducDevolucion_temporales($far,$Centrid,$bod,$codigo_producto,$cantidad,$fecha_vencimiento,$lote,$total_costo);
		  $consulta =$sel->Productos_Seleccionados_x_devolver($far,$Centrid,$bod);
      $html .= "<fieldset class=\"fieldset\">\n";
      $html .= "  <legend    align=\"left\"><b>PRODUCTOS SELECCIONADOS</b></legend>\n";
      $html .= "  <table width=\"98%\" class=\"modulo_table_list_title\" align=\"center\">";
      $html .= "	  <tr align=\" class=\"modulo_table_list_title\" >\n";
      $html .= "      <td width=\"15%\">MOLECULA</td>\n";
      $html .= "      <td width=\"15%\">CODIGO</td>\n";
      $html .= "      <td width=\"45%\">PRODUCTO</td>\n";
      $html .= "      <td width=\"5%\"> CANT</td>\n";
      $html .= "      <td width=\"10%\">FECHA VEN.</td>\n";
      $html .= "      <td width=\"10%\">LOTE</td>\n";
      $html .= "      <td width=\"10%\">OP</td>\n";
      $html .= "  </tr>\n";
      $est = "modulo_list_claro"; $back = "#DDDDDD";
      
   		foreach($consulta as $key => $dtl)
			{
        $ent = ($valor > $dtl['cantidad'])? $dtl['cantidad']: $valor;
				$html .= "    <tr ".(($dtl['sw_generico']=='1')? "style=\"background:#CFE7FA\" ":"")." onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" class=\"".$est."\" >\n";
				$html .= "      <td align=\"center\">".$dtl['molecula']."</td>\n";
        $html .= "      <td align=\"center\">".$dtl['codigo_producto']."</td>\n";
				$html .= "      <td align=\"center\">".$dtl['producto']." ".$dtl['contenido_unidad_venta']." ".$dtl['unidad']." -".$dtl['laboratorio']."</td>\n";
				$html .= "      <td align=\"center\">".$dtl['cantidad']."</td>\n";
        $html .= "      <td align=\"center\">".$dtl['fecha_vencimiento']."</td>\n";
				$html .= "      <td align=\"center\">".$dtl['lote']."</td>\n";
				$html .= "      <td width=\"1%\">\n";
				$html .= "       <a href=\"#\" onclick=\"xajax_Eliminar_Producto_Seleccionado('".$dtl['codigo_producto']."','".$far."','".$Centrid."','".$bod."','".$dtl['fecha_vencimiento']."','".$dtl['lote']."');\">";
				$html .= "      	  <img src=\"".GetThemePath()."/images/elimina.png\"  border=\"0\">\n";
				$html .= "        </a>\n";
				$html .= "      </td>\n";
				$html .= "    </tr>\n";
		
			}
		
      $html .= "</table><br>\n";	
      $html .= "</fieldset><br>\n";			
      $objResponse->assign("productos","innerHTML",$html);
      return $objResponse;
    }
	/*
		* Forma que permite Eliminar Los datos de la tabla Doc_Devolucion_tmp. 
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		function EliminarProd_Devol_tmp($far,$Centrid,$bod,$codigo_producto,$fecha,$lote)
		{
			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
			$rst =$sel->Eliminar_ProducDevolucion_temporales($far,$Centrid,$bod,$codigo_producto,$fecha,$lote);
       $consulta =$sel->Productos_Seleccionados_x_devolver($far,$Centrid,$bod);
      if(!empty($consulta))
      {
        
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend    align=\"left\"><b>PRODUCTOS SELECCIONADOS</b></legend>\n";
        $html .= "  <table width=\"98%\" class=\"modulo_table_list_title\" align=\"center\">";
        $html .= "	  <tr align=\" class=\"modulo_table_list_title\" >\n";
        $html .= "      <td width=\"15%\">MOLECULA</td>\n";
        $html .= "      <td width=\"15%\">CODIGO</td>\n";
        $html .= "      <td width=\"45%\">PRODUCTO</td>\n";
        $html .= "      <td width=\"5%\"> CANT</td>\n";
        $html .= "      <td width=\"10%\">FECHA VEN.</td>\n";
        $html .= "      <td width=\"10%\">LOTE</td>\n";
        $html .= "      <td width=\"10%\">OP</td>\n";
        $html .= "  </tr>\n";
        $est = "modulo_list_claro"; $back = "#DDDDDD";
        
       		foreach($consulta as $key => $dtl)
    			{
            $ent = ($valor > $dtl['cantidad'])? $dtl['cantidad']: $valor;
    				$html .= "    <tr ".(($dtl['sw_generico']=='1')? "style=\"background:#CFE7FA\" ":"")." onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" class=\"".$est."\" >\n";
    				$html .= "      <td align=\"center\">".$dtl['molecula']."</td>\n";
            $html .= "      <td align=\"center\">".$dtl['codigo_producto']."</td>\n";
    				$html .= "      <td align=\"center\">".$dtl['producto']." ".$dtl['contenido_unidad_venta']." ".$dtl['unidad']." -".$dtl['laboratorio']."</td>\n";
    				$html .= "      <td align=\"center\">".$dtl['cantidad']."</td>\n";
            $html .= "      <td align=\"center\">".$dtl['fecha_vencimiento']."</td>\n";
    				$html .= "      <td align=\"center\">".$dtl['lote']."</td>\n";
    				$html .= "      <td width=\"1%\">\n";
    				$html .= "       <a href=\"#\" onclick=\"xajax_Eliminar_Producto_Seleccionado('".$dtl['codigo_producto']."','".$far."','".$Centrid."','".$bod."','".$dtl['fecha_vencimiento']."','".$dtl['lote']."');\">";
    				$html .= "      	  <img src=\"".GetThemePath()."/images/elimina.png\"  border=\"0\">\n";
    				$html .= "        </a>\n";
    				$html .= "      </td>\n";
    				$html .= "    </tr>\n";
    		
    			}
		  
        $html .= "</table><br>\n";	
        $html .= "</fieldset><br>\n";			
     }
      $objResponse->assign("productos","innerHTML",$html);
      return $objResponse;
		}
	  
	/*
		* Forma que permite Mostrar la forma completa de los productos seleccionados
		* @return object $objResponse objeto de respuesta al formulario.
	*/
    function ProductosSeleccionados($farmacia,$centro,$bodega)
    {
    	$objResponse = new xajaxResponse();
      
      $sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
      $rst =$sel->Productos_Seleccionados_x_devolver($farmacia,$centro,$bodega);
      if(!empty($rst))
      {
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend    align=\"left\"><b>PRODUCTOS SELECCIONADOS</b></legend>\n";
        $html .= "  <table width=\"98%\" class=\"modulo_table_list_title\" align=\"center\">";
        $html .= "	  <tr align=\" class=\"modulo_table_list_title\" >\n";
        $html .= "      <td width=\"15%\">MOLECULA</td>\n";
        $html .= "      <td width=\"15%\">CODIGO</td>\n";
        $html .= "      <td width=\"45%\">PRODUCTO</td>\n";
        $html .= "      <td width=\"5%\"> CANT</td>\n";
        $html .= "      <td width=\"10%\">FECHA VEN.</td>\n";
        $html .= "      <td width=\"10%\">LOTE</td>\n";
        $html .= "      <td width=\"10%\">OP</td>\n";
        $html .= "  </tr>\n";
        $est = "modulo_list_claro"; $back = "#DDDDDD";
      
        foreach($rst as $key => $dtl)
        {
          $ent = ($valor > $dtl['cantidad'])? $dtl['cantidad']: $valor;
  				$html .= "    <tr ".(($dtl['sw_generico']=='1')? "style=\"background:#CFE7FA\" ":"")." onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" class=\"".$est."\" >\n";
  				$html .= "      <td align=\"center\">".$dtl['molecula']."</td>\n";
          $html .= "      <td align=\"center\">".$dtl['codigo_producto']."</td>\n";
  				$html .= "      <td align=\"center\">".$dtl['producto']." ".$dtl['contenido_unidad_venta']." ".$dtl['unidad']." -".$dtl['laboratorio']."</td>\n";
  				$html .= "      <td align=\"center\">".$dtl['cantidad']."</td>\n";
          $html .= "      <td align=\"center\">".$dtl['fecha_vencimiento']."</td>\n";
  				$html .= "      <td align=\"center\">".$dtl['lote']."</td>\n";
  				$html .= "      <td width=\"1%\">\n";
  				$html .= "       <a href=\"#\" onclick=\"xajax_Eliminar_Producto_Seleccionado('".$dtl['codigo_producto']."','".$farmacia."','".$centro."','".$bodega."','".$dtl['fecha_vencimiento']."','".$dtl['lote']."');\">";
  				$html .= "      	  <img src=\"".GetThemePath()."/images/elimina.png\"  border=\"0\">\n";
  				$html .= "        </a>\n";
  				$html .= "      </td>\n";
  				$html .= "    </tr>\n";
		
        }
		    $html .= "</table><br>\n";	
        $html .= "</fieldset><br>\n";		
      }	
        $objResponse->assign("productos","innerHTML",$html);
  
      return $objResponse;
  }
  	/*
		* Forma que permite  Eliminar los productos seleccionados que ya no se van a enviar
		* @return object $objResponse objeto de respuesta al formulario.
	*/
    
    function Eliminar_Producto_Seleccionado($codigo,$farmacia,$centro,$bodega,$fecha_vencimiento,$lote)
    {
      $objResponse = new xajaxResponse();
      $sel = AutoCarga::factory("AdminFarmaciaSQL", "", "app", "AdminFarmacia");
      $rst =$sel->Eliminar_producto_seleccionados($codigo,$farmacia,$centro,$bodega,$fecha_vencimiento,$lote);
      $consulta =$sel->Productos_Seleccionados_x_devolver($farmacia,$centro,$bodega);
      if(!empty($consulta))
      {
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend    align=\"left\"><b>PRODUCTOS SELECCIONADOS</b></legend>\n";
        $html .= "  <table width=\"98%\" class=\"modulo_table_list_title\" align=\"center\">";
        $html .= "	  <tr align=\" class=\"modulo_table_list_title\" >\n";
        $html .= "      <td width=\"15%\">MOLECULA</td>\n";
        $html .= "      <td width=\"15%\">CODIGO</td>\n";
        $html .= "      <td width=\"45%\">PRODUCTO</td>\n";
        $html .= "      <td width=\"5%\"> CANT</td>\n";
        $html .= "      <td width=\"10%\">FECHA VEN.</td>\n";
        $html .= "      <td width=\"10%\">LOTE</td>\n";
        $html .= "      <td width=\"10%\">OP</td>\n";
        $html .= "  </tr>\n";
        $est = "modulo_list_claro"; $back = "#DDDDDD";

        foreach($consulta as $key => $dtl)
        {
        $ent = ($valor > $dtl['cantidad'])? $dtl['cantidad']: $valor;
        $html .= "    <tr ".(($dtl['sw_generico']=='1')? "style=\"background:#CFE7FA\" ":"")." onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" class=\"".$est."\" >\n";
        $html .= "      <td align=\"center\">".$dtl['molecula']."</td>\n";
        $html .= "      <td align=\"center\">".$dtl['codigo_producto']."</td>\n";
        $html .= "      <td align=\"center\">".$dtl['producto']." ".$dtl['contenido_unidad_venta']." ".$dtl['unidad']." -".$dtl['laboratorio']."</td>\n";
        $html .= "      <td align=\"center\">".$dtl['cantidad']."</td>\n";
        $html .= "      <td align=\"center\">".$dtl['fecha_vencimiento']."</td>\n";
        $html .= "      <td align=\"center\">".$dtl['lote']."</td>\n";
        $html .= "      <td width=\"1%\">\n";
        $html .= "       <a href=\"#\" onclick=\"xajax_Eliminar_Producto_Seleccionado('".$dtl['codigo_producto']."','".$farmacia."','".$centro."','".$bodega."','".$dtl['fecha_vencimiento']."','".$dtl['lote']."');\">";
        $html .= "      	  <img src=\"".GetThemePath()."/images/elimina.png\"  border=\"0\">\n";
        $html .= "        </a>\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";

        }

      $html .= "</table><br>\n";	
      $html .= "</fieldset><br>\n";		
    }
      $objResponse->assign("productos","innerHTML",$html);
      return $objResponse;
    
    }
    
  
  
  
  
	/*
		* Forma que permite Mostrar la forma completa de la creacion del documento temporal 
		* @return object $objResponse objeto de respuesta al formulario.
	*/
	
		function MostrarFormaCompleta($abreviatura)
		{
	
			$objResponse = new xajaxResponse();
			$html = "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend class=\"normal_10AN\" align=\"center\">LOS PRODUCTOS SEÑALADOS SERAN INCLUIDOS  AL GENERARSE EL DOCUMENTO DE DEVOLUCION  </legend>\n";
			$html .= "<form name=\"Forma13\" id=\"Forma13\" method=\"post\" >\n";
			$html  .= "  <table class=\"modulo_table_list_title\" border=\"1\" align=\"center\" width=\"80%\">\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td width=\"10%\" align=\"center\">  * Estados del Documento\n";
			$html .= "      </td>\n";
			$html .= "		<td  class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "				<select name=\"tipo_doc_general_id\" class=\"select\">\n";
			$html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($abreviatura as $indice => $valor)
			{
				if($valor[0]['abreviatura']==$request['abreviatura'])
				$sel = "selected";
				else   $sel = "";
				$html .= "  <option value=\"".$valor['abreviatura']."\" ".$sel.">".$valor['descripcion']."</option>\n";
			}
			$html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html .= "    </tr>\n";
			$html .= "  </table><br>\n";
			$html  .= "  <table class=\"modulo_table_list_title\" border=\"1\" align=\"center\" width=\"80%\">\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "      <td width=\"10%\" align=\"center\">* OBSERVACIÒN\n";
			$html .= "      </td>\n";
			$html .= "    <tr class=\"modulo_table_list_title\">\n";
			$html .= "    </tr>\n";
			$html .= "      <td colspan=\"5\"  align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "        <textarea onkeypress=\"return max(event)\"  name=\"observar\" rows=\"2\" style=\"width:100%\"></textarea>\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";
			$html .= "  </table>\n";
			$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
			$html .= "		<tr>\n";
			$html .= "      <td align=\"center\" class=\"normal_10AN\" >\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"GENERAR DOCMENTO\" onclick=\"ValidarDtosPen(document.Forma13);\">\n";
			$html .= " </td>\n";
			$html .= "      <td align=\"center\">\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CANCELAR\" onclick=\"OcultarSpan();\">   \n";
			$html .= " </td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "  </form>\n";
			$html .= "</fieldset><br>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		
		}
	/*
		* Forma que permite mostrar un mensaje cuando no se ha seleccionado ningun item
		* @return object $objResponse objeto de respuesta al formulario.
	
	*/
		function MostrarMensaje()
		{
			$objResponse = new xajaxResponse();
			$html  = "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend class=\"normal_10AN\" align=\"center\">SE DEBE SELECCIONAR AL MENOS UN PRODUCTO PARA GENERAR EL DOCUMENTO</legend>\n";
			$html .= " <form name=\"Forma13\" id=\"Forma13\" method=\"post\" >\n";
			$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
			$html .= "      <td align=\"center\">\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CANCELAR\" onclick=\"OcultarSpan();\">   \n";
			$html .= " </td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "  </form>\n";
			$html .= "</fieldset><br>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		}
	
?>