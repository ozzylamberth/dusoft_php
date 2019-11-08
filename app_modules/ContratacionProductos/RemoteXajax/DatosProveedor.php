<?php
 /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.11 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja Torres 
  */
  
   /**
  * Funcion que permite seleccionar el numero de contrato relacionado a un Proveedor  para modificar el contrato
  * @param string $noId cadena numero de identificacion del proveedor
  * @param string $tipoId cadena con el tipo de identificacion del proveedor
  * @return Object $objResponse objeto de respuesta al formulario  
  */
       
		function SelecNroContrato($noId, $tipoId,$empresa)
		{
  
			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("ContratacionProductosSQL", "", "app", "ContratacionProductos");
			$sncontrato= $sel->ConsultarNoContrato($noId, $tipoId,$empresa);
			$html .= "<form name=\"forma2seleccion\" id=\"forma2seleccion\" method=\"post\" >\n";
			$html  = "  <table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";     
			$html .= "      <td width=\"10%\" align=\"center\">NRO.CONTRATO\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"30%\" align=\"center\">DESCRIPCIÒN\n";
			$html .= "      </td>\n";
			$html .= "      <td  width=\"5%\" align=\"center\"> ESTADO \n";
			$html .= "      </td>\n";
			$html .= "      <td   width=\"5%\" align=\"center\"> MODIF.\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";    
			$est = "modulo_list_claro";
			foreach($sncontrato as $indice => $valor)
			{
				($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
				$html .= "    <tr class=\"modulo_list_oscuro\">\n";
				$html .= "      <td  width=\"10%\" align=\"center\"  class=\"modulo_list_claro\"> <b>".$valor['no_contrato']."  </b></td>\n";
				$html .= "      <td  width=\"10%\" align=\"center\"  class=\"modulo_list_claro\"> <b>".$valor['descripcion']."  </b></td>\n";
				$html .= "     <input type=\"hidden\" name=\"contratacion_prod_id\" id=\"contratacion_prod_id\" value=\"".$valor['contratacion_prod_id']."\">\n";

				$vestado="1";
				$html .= "      <td align=\"center\">\n";
				$html .= "  <a href=\"".ModuloGetURL("app", "ContratacionProductos", "controller", "ModificarEstadoContrato", array("sncontrato"=>$valor['no_contrato'],"estado"=>$vestado,"noid"=>$valor['tercero_id'],"tipoid"=>$valor['tipo_id_tercero'],"empresa_id"=>$valor['empresa_id'] ,"contratacion_prod_id"=>$valor['contratacion_prod_id']))."\">\n";
				$html .= "      <img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\" title=\"Activo\">\n";
				$html .= "        </a>\n";
				$html .= "      </td>\n";

				$html .= "      <td align=\"center\" >\n";
				$html .= "  <a href=\"".ModuloGetURL("app", "ContratacionProductos", "controller", "ModificarInfoContrato", array("contratacion_prod_id"=>$valor['contratacion_prod_id']))."\">\n";
				$html .= "          <img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\" title=\"Modificar\" >\n";
				$html .= "         </a>\n";
				$html .= " </td>\n";
				$html .= "    </tr>\n";
			}
			$html .= "  </table>\n";
			$html .= "  </form>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		}
	  /**
  * Funcion que permite seleccionar el numero de contrato relacionado a un Proveedor para hacer copia de el , el estado del  contrato  debe estar inactivo
  * @param string $noId cadena numero de identificacion del proveedor
  * @param string $tipoId cadena con el tipo de identificacion del proveedor
  * @return Object $objResponse objeto de respuesta al formulario  
  */
		function SelecUniNroContrato($noId, $tipoId,$empresa)
		{
  
			$objResponse = new xajaxResponse();

			$sel = AutoCarga::factory("ContratacionProductosSQL", "", "app", "ContratacionProductos");

			$sncontrato= $sel->ConsultarNoContratoE($noId, $tipoId,$empresa);
			$html .= "<form name=\"forma2seleccion\" id=\"forma2seleccion\" method=\"post\" >\n";
			$html  = "  <table class=\"modulo_table_list\" align=\"center\" width=\"50%\">\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";     
			$html .= "      <td width=\"15%\" align=\"center\">NRO.CONTRATO\n";
			$html .= "      </td>\n";
    		$html .= "      <td   width=\"5%\" align=\"center\"> ESTADO \n";
			$html .= "      </td>\n";
			$html .= "      <td   width=\"5%\" align=\"center\"> SELEC. \n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";    
			$est = "modulo_list_claro";
			foreach($sncontrato as $indice => $valor)
	        {
				($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
				$html .= "    <tr class=\"modulo_list_oscuro\">\n";
				$html .= "      <td  width=\"10%\" align=\"center\"  class=\"modulo_list_claro\"> <b>".$valor['no_contrato']."  </b></td>\n";
				$html .= "     <input type=\"hidden\" name=\"contratacion_prod_id\" id=\"contratacion_prod_id\" value=\"".$valor['contratacion_prod_id']."\">\n";

				if($valor['estado']=="0")
				{
		            $vestado="0";
		            $html .= "      <td align=\"center\">\n";
		            $html .= "      <img src=\"".GetThemePath()."/images/pinactivo.png\" border=\"0\" title=\"Inactivo\">\n";
		            $html .= "        </a>\n";
		            $html .= "      </td>\n";
		            $html .= "      <td align=\"center\" >\n";
					$html .= "  <a href=\"".ModuloGetURL("app", "ContratacionProductos", "controller", "CopiaContrato", array("contratacion_prod_id"=>$valor['contratacion_prod_id']))."\">\n";
					$html .= "          <img src=\"".GetThemePath()."/images/si.png\" border=\"0\" title=\"Copiar\" >\n";
					$html .= "         </a>\n";
					$html .= " </td>\n";
					$html .= "    </tr>\n";
		             
		        }
           
			}
        $html .= "  </table>\n";
        $html .= "  <br>\n";
        $html .= "  </form>\n";
        $html .= "  <br>\n";
        $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
        $objResponse->call("MostrarSpan");
        return $objResponse;
        }
     /**
  * Funcion que permite mostrar los laboratorios
  * @param array  $form vector con toda la forma
  * @return Object $objResponse objeto de respuesta al formulario  
  */
		function MostrarLaboratorios($form)
		{
			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("ContratacionProductosSQL", "", "app", "ContratacionProductos");
			$lab = $sel->ConsultarClaseId($form['grupo']) ;
			$html  = "document.formita.laboratorio.options.length = 0;\n";
			$html .= "document.formita.laboratorio.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
			$i = 1;
			foreach($lab as $key => $dtl)
			{
			$html .= "document.formita.laboratorio.options[".($i++)."] = new Option('".$dtl['descripcion']."','".$dtl['laboratorio_id']."',false, false);\n";
			}
			$objResponse->script($html);
			return $objResponse;
		}
   /**
  * Funcion que permite mostrar las moleculas
  * @param array  $form vector con toda la forma
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
		function SeleccionarMolecula($form)
		{
			$afi = AutoCarga::factory("ContratacionProductosSQL", "", "app","ContratacionProductos");
			$subgrupo = $afi->ConsultarSubClaseId($form['grupo'],$form['laboratorio']);
			$html  = "document.formita.molecula.options.length = 0 ;\n";
			$html .= "document.formita.molecula.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
			$i = 1;
			foreach($subgrupo as $key => $dtl)
			{
				$html .= "document.formita.molecula.options[".($i++)."] = new Option('".$dtl['descripcion']."','".$dtl['molecula_id']."',false, false);\n";
			}
			$objResponse = new xajaxResponse();
			$objResponse->script($html);
			return $objResponse;
		}
		
		 /**
  * Funcion que permite activar los los cuadros de texto para ingresasr el valor de un producto
  * @return Object $objResponse objeto de respuesta al formulario  
  */
		function Activar($value)
		{
			$objResponse = new xajaxResponse();

			$objResponse->script('
			if(document.formita.checkpesos'.$value.'.checked==true)
			 document.formita.txtpesos'.$value.'.disabled=false;
			 else
				document.formita.txtpesos'.$value.'.disabled=true;

			');
			return $objResponse;
        }
			
		 /**
  * Funcion que permite  desactivar  los los cuadros de texto para ingresasr el valor de un producto
* @return Object $objResponse objeto de respuesta al formulario  
  */
    
			function Activar2($value)
			{
				$objResponse = new xajaxResponse();

				$objResponse->script('
				if(document.formita.checkporcentaje'.$value.'.checked==true)
				document.formita.txtporcen'.$value.'.disabled=false;
				else
				document.formita.txtporcen'.$value.'.disabled=true;

				');
				return $objResponse;
			}
  
  /**
  * Funcion que permite  seleccionar un contrato activo para ir a la funcion de seleccionar los productos
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
    
    function SelNoContrato($noId, $tipoId,$empresa)
		{
  
			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("ContratacionProductosSQL", "", "app", "ContratacionProductos");
           
			$sncontrato= $sel->ConsultarNoContrato($noId, $tipoId,$empresa);
			$proveid= $sel->ConsultarProveedorID($noId, $tipoId,$empresa);
			$proid=$proveid['0']['codigo_proveedor_id'];
			$html .= "<form name=\"forma2seleccion\" id=\"forma2seleccion\" method=\"post\" >\n";
			$html  = "  <table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";     
			$html .= "      <td width=\"10%\" align=\"center\">NRO.CONTRATO\n";
			$html .= "      </td>\n";
			$html .= "      <td width=\"25%\" align=\"center\">DESCRIPCIÒN\n";
			$html .= "      </td>\n";
			$html .= "      <td  width=\"5%\" align=\"center\">PRODUCTOS.\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";    
			$est = "modulo_list_claro";
			foreach($sncontrato as $indice => $valor)
			{
				($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
				$html .= "    <tr class=\"modulo_list_oscuro\">\n";
				$html .= "      <td  width=\"10%\" align=\"center\"  class=\"modulo_list_claro\"> <b>".$valor['no_contrato']."  </b></td>\n";
				$html .= "      <td  width=\"10%\" align=\"center\"  class=\"modulo_list_claro\"> <b>".$valor['descripcion']."  </b></td>\n";
				$html .= "     <input type=\"hidden\" name=\"contratacion_prod_id\" id=\"contratacion_prod_id\" value=\"".$valor['contratacion_prod_id']."\">\n";
				$html .= "      <td align=\"center\" >\n";
				$html .= "  <a href=\"".ModuloGetURL("app", "ContratacionProductos", "controller", "MenuProducto", array("tipoid"=>$valor['tipo_id_tercero'],"noid"=>$valor['tercero_id'],"scontrato"=>$valor['no_contrato'],"empresa"=>$empresa,"proid"=>$proid))."\">\n";
				$html .= "      <img src=\"".GetThemePath()."/images/producto.png\" border=\"0\">\n";
				$html .= "        </a>\n";
				$html .= " </td>\n";
				$html .= "    </tr>\n";
			}
			$html .= "  </table>\n";
			$html .= "  </form>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		}
      /**
  * Funcion que permite  hacer una validacion sobre los productos seleccionados para asociarlos a un contrato
  * @return Object $objResponse objeto de respuesta al formulario  
  */  function ValidarDatosProducto($j,$empresa,$noidcontrato)
		{	   					       

				$objResponse = new xajaxResponse();
       
        $objResponse->script('
        var codigo_producto=document.getElementById(\'codigo_producto'.$j.'\').value;
        var valor=document.getElementById(\'costo'.$j.'\').value;
        var valor_pactado=document.getElementById(\'txtpesos'.$j.'\').value;
        var valor_porcentaje=document.getElementById(\'txtporcen'.$j.'\').value;
        var politica_vencimiento=document.getElementById(\'politica_vencimiento'.$j.'\').value;
        
        if(document.formita.checkseleccionar'.$j.'.checked==true)
        {
            if(document.getElementById(\'checkporcentaje'.$j.'\').checked==true  && document.getElementById(\'checkpesos'.$j.'\').checked==true)
            {
              document.getElementById("error").innerHTML = " SELECCIONE UNO DE LAS DOS OPCIONES ";
            }
            else
            {
              if(document.getElementById(\'checkpesos'.$j.'\').checked==true)
              {
                  if(document.getElementById(\'txtpesos'.$j.'\').value=="")
                  {
                    document.getElementById("error").innerHTML = " INGRESE EL VALOR DEL PRODUCTO";
                  }
                  else
                  {
                      bandera=0;		
                     xajax_InsertarProductoAlContrato(\''.$empresa.'\', \''.$noidcontrato.'\',codigo_producto,valor,valor_pactado,valor_porcentaje,totalporc,politica_vencimiento,bandera);
                  }
              }else
              {
               if(document.getElementById(\'checkporcentaje'.$j.'\').checked==true)
               {
                  if(document.getElementById(\'txtporcen'.$j.'\').value=="")
                  {
                    document.getElementById("error").innerHTML = " INGRESE EL PORCENTAJE  DEL PRODUCTO";
                  }
                  else
                  {
                      var transformardecimal=((valor * 100) / 100);
                      var subtotal= (transformardecimal* valor_porcentaje )/100;
                      
                     	var totalporc= transformardecimal + (subtotal);
                     bandera=1;			               
                     xajax_InsertarProductoAlContrato(\''.$empresa.'\', \''.$noidcontrato.'\',codigo_producto,valor,valor_pactado,valor_porcentaje,totalporc,politica_vencimiento,bandera);
                 }
               }
              }
            }
        }else
        {
              xajax_Eliminar_tmp_contrato("'.$empresa.'", "'.$noidcontrato.'",codigo_producto);

        
        }
        
         
				 ');
				  
			return $objResponse;
        }
   /**
  * Funcion que permite  hacer todas las validacion para ingresar los productos
  * @return Object $objResponse objeto de respuesta al formulario  
  */ 
        function ValidarTodosLosDatosProducto($empresa,$noidcontrato)
        {
        
        $objResponse = new xajaxResponse();
    
        $sel = AutoCarga::factory("ContratacionProductosSQL", "", "app", "ContratacionProductos");
        $rst =$sel->Productos_seleccionados($empresa,$noidcontrato);
        $datos=$sel->Insertar_Producto_detalle_Contrato($rst);
        if($datos==true)
        {
            
            $url=ModuloGetURL("app", "ContratacionProductos", "controller", "Subirimagen");
            $Elimina=$sel->Eliminar_producto_contratos($empresa,$noidcontrato);
            if($Elimina==true)
            {
              $objResponse->script('
              window.location="'.$url.'";
							');
            
            }
        }else
        {
        
         $objResponse->alert("ERROR AL GUARDAR LOS PRODUCTOS SELECCIONADOS");
        
        }
        return $objResponse;
        }
      
 
      /**
  * Funcion que permite  insertar los productos al contrato
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
		function InsertarProductoAlContrato($empresa,$noidcontrato,$producto,$costo,$vpesos,$vporc,$valortotal,$politica_vencimiento,$bandera)
		{
    
			$objResponse = new xajaxResponse();
    
      $sel = AutoCarga::factory("ContratacionProductosSQL", "", "app", "ContratacionProductos");
      $rst =$sel->IngresarProductoAlContrato($empresa,$noidcontrato,$producto,$costo,$vpesos,$vporc,$valortotal,$bandera);
      $rst =$sel->Productos_seleccionados($empresa,$noidcontrato);
      if(!empty($rst))
      {
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend    align=\"left\"><b>PRODUCTOS SELECCIONADOS: </b></legend>\n";
        $html .= "  <table width=\"100%\" class=\"modulo_table_list_title\" align=\"center\">";
        $html .= "	  <tr align=\" class=\"modulo_table_list_title\" >\n";
        /*$html .= "      <td >MOLECULA</td>\n";*/
        $html .= "      <td >CODIGO</td>\n";
        $html .= "      <td >PRODUCTO</td>\n";
        $html .= "      <td >PRECIO/PORCENTAJE</td>\n";
        $html .= "      <td >TOTAL</td>\n";
        $html .= "      <td >OP</td>\n";
        $html .= "  </tr>\n";
        $est = "modulo_list_claro"; $back = "#DDDDDD";
      
        foreach($rst as $key => $dtl)
        {
       
		$html .= "    <tr onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" class=\"".$est."\" >\n";
		/*$html .= "      <td align=\"center\">".$dtl['molecula']."</td>\n";*/
		$html .= "      <td align=\"center\">".$dtl['codigo_producto']."</td>\n";
		$html .= "      <td align=\"left\">".$dtl['producto']."</td>\n";

		if($dtl['valor_pactado']!=0)
		{
		$html .= "      <td align=\"center\">$".round($dtl['valor_pactado'])."</td>\n";
		}else
		{
		$html .= "      <td align=\"center\">".$dtl['valor_porcentaje']."%</td>\n";
		}
          
		$html .= "      <td align=\"center\">$".round($dtl['valor_total_pactado'])."</td>\n";
		$html .= "      <td width=\"1%\">\n";
		$html .= "       <a href=\"#\" onclick=\"xajax_Eliminar_Producto_Seleccionado('".$dtl['codigo_producto']."','".$empresa."','".$noidcontrato."');\">";
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
    function ProductosSeleccionados($empresa,$contrato)
    {
    	$objResponse = new xajaxResponse();
      
    	$mdl = AutoCarga::factory("ContratacionProductosSQL", "classes", "app", "ContratacionProductos");
      $rst =$mdl->Productos_seleccionados($empresa,$contrato);
      if(!empty($rst))
      {
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend    align=\"left\"><b>PRODUCTOS SELECCIONADOS</b></legend>\n";
        $html .= "  <table width=\"100%\" class=\"modulo_table_list_title\" align=\"center\">";
        $html .= "	  <tr align=\" class=\"modulo_table_list_title\" >\n";
        /*$html .= "      <td width=\"15%\">MOLECULA</td>\n";*/
        $html .= "      <td >CODIGO</td>\n";
        $html .= "      <td >PRODUCTO</td>\n";
        $html .= "      <td >PRECIO/PORCENTAJE</td>\n";
        $html .= "      <td >TOTAL</td>\n";
        $html .= "      <td >OP</td>\n";
        $html .= "  </tr>\n";
        $est = "modulo_list_claro"; $back = "#DDDDDD";
      
        foreach($rst as $key => $dtl)
        {
       
		$html .= "    <tr onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" class=\"".$est."\" >\n";
		/*$html .= "      <td align=\"center\">".$dtl['molecula']."</td>\n";*/
		$html .= "      <td align=\"center\">".$dtl['codigo_producto']."</td>\n";
		$html .= "      <td align=\"center\">".$dtl['producto']." </td>\n";

		if($dtl['valor_pactado']!=0)
		{
		$html .= "      <td align=\"center\">$".round($dtl['valor_pactado'])."</td>\n";
		}else
		{
		$html .= "      <td align=\"center\">".$dtl['valor_porcentaje']."%</td>\n";
		}

		$html .= "      <td align=\"center\">$".round($dtl['valor_total_pactado'])."</td>\n";
		$html .= "      <td width=\"1%\">\n";
		$html .= "       <a href=\"#\" onclick=\"xajax_Eliminar_Producto_Seleccionado('".$dtl['codigo_producto']."','".$empresa."','".$contrato."');\">";
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
		* Forma que permite Mostrar la forma para Eliminar el producto seleccionado
		* @return object $objResponse objeto de respuesta al formulario.
	*/
    
    function Eliminar_Producto_Seleccionado($codigo,$empresa,$contrato)
    {
      $objResponse = new xajaxResponse();
          	$mdl = AutoCarga::factory("ContratacionProductosSQL", "classes", "app", "ContratacionProductos");

      $rst =$mdl->Eliminar_producto_seleccionados($codigo,$empresa,$contrato);
      $consulta =$mdl->Productos_seleccionados($empresa,$contrato);
      if(!empty($consulta))
      {
       
         $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend    align=\"left\"><b>PRODUCTOS SELECCIONADOS</b></legend>\n";
        $html .= "  <table width=\"100%\" class=\"modulo_table_list_title\" align=\"center\">";
        $html .= "	  <tr align=\" class=\"modulo_table_list_title\" >\n";
        $html .= "      <td width=\"15%\">MOLECULA</td>\n";
        $html .= "      <td width=\"10%\">CODIGO</td>\n";
        $html .= "      <td width=\"40%\">PRODUCTO</td>\n";
        $html .= "      <td width=\"20%\">PRECIO/PORCENTAJE</td>\n";
        $html .= "      <td width=\"10%\">TOTAL</td>\n";
        $html .= "      <td width=\"5%\">OP</td>\n";
        $html .= "  </tr>\n";
        $est = "modulo_list_claro"; $back = "#DDDDDD";
      
        foreach($consulta as $key => $dtl)
        {
       
  				$html .= "    <tr onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" class=\"".$est."\" >\n";
  				$html .= "      <td align=\"center\">".$dtl['molecula']."</td>\n";
          $html .= "      <td align=\"center\">".$dtl['codigo_producto']."</td>\n";
  				$html .= "      <td align=\"center\">".$dtl['producto']." ".$dtl['contenido_unidad_venta']." ".$dtl['unidad']." -".$dtl['laboratorio']."</td>\n";
  				
            if($dtl['valor_pactado']!=0)
            {
                $html .= "      <td align=\"center\">$".round($dtl['valor_pactado'])."</td>\n";
            }else
            {
                  $html .= "      <td align=\"center\">".$dtl['valor_porcentaje']."%</td>\n";
            }
          
          $html .= "      <td align=\"center\">$".round($dtl['valor_total_pactado'])."</td>\n";
         	$html .= "      <td width=\"1%\">\n";
  				$html .= "       <a href=\"#\" onclick=\"xajax_Eliminar_Producto_Seleccionado('".$dtl['codigo_producto']."','".$empresa."','".$contrato."');\">";
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
		* Forma que permite Eliminar Los datos de la tabla Doc_Devolucion_tmp. 
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		function Eliminar_tmp_contrato($empresa,$contrato,$codigo_producto)
		{
      $objResponse = new xajaxResponse();
      $mdl = AutoCarga::factory("ContratacionProductosSQL", "classes", "app", "ContratacionProductos");

      $rst =$mdl->Eliminar_producto_seleccionados($codigo_producto,$empresa,$contrato);
      $consulta =$mdl->Productos_seleccionados($empresa,$contrato);
      if(!empty($consulta))
      {
      
         $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend    align=\"left\"><b>PRODUCTOS SELECCIONADOS</b></legend>\n";
        $html .= "  <table width=\"100%\" class=\"modulo_table_list_title\" align=\"center\">";
        $html .= "	  <tr align=\" class=\"modulo_table_list_title\" >\n";
        $html .= "      <td width=\"15%\">MOLECULA</td>\n";
        $html .= "      <td width=\"10%\">CODIGO</td>\n";
        $html .= "      <td width=\"40%\">PRODUCTO</td>\n";
        $html .= "      <td width=\"20%\">PRECIO/PORCENTAJE</td>\n";
        $html .= "      <td width=\"10%\">TOTAL</td>\n";
        $html .= "      <td width=\"5%\">OP</td>\n";
        $html .= "  </tr>\n";
        $est = "modulo_list_claro"; $back = "#DDDDDD";
      
        foreach($consulta as $key => $dtl)
        {
       
  				$html .= "    <tr onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" class=\"".$est."\" >\n";
  				$html .= "      <td align=\"center\">".$dtl['molecula']."</td>\n";
          $html .= "      <td align=\"center\">".$dtl['codigo_producto']."</td>\n";
  				$html .= "      <td align=\"center\">".$dtl['producto']." ".$dtl['contenido_unidad_venta']." ".$dtl['unidad']." -".$dtl['laboratorio']."</td>\n";
  				
            if($dtl['valor_pactado']!=0)
            {
                $html .= "      <td align=\"center\">$".round($dtl['valor_pactado'])."</td>\n";
            }else
            {
                  $html .= "      <td align=\"center\">".$dtl['valor_porcentaje']."%</td>\n";
            }
          
          $html .= "      <td align=\"center\">$".round($dtl['valor_total_pactado'])."</td>\n";
         	$html .= "      <td width=\"1%\">\n";
  				$html .= "       <a href=\"#\" onclick=\"xajax_Eliminar_Producto_Seleccionado('".$dtl['codigo_producto']."','".$empresa."','".$contrato."');\">";
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
   
		  /**
  * Funcion que permite  validar los datos de los productos  asociados  a un contrato inanctivo para hacer copia de ellos
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
		function ValidarTodosLosDatosProductos($i,$contrato,$empresa,$contratacion_prod_id)
		{
			$objResponse = new xajaxResponse();
      
		for($j=0;$j<$i;$j++)
			{
                $objResponse->script(' 
                   
				var codigo_producto=document.getElementById(\'codigo_producto'.$j.'\').value;
				var precio=document.getElementById(\'precio'.$j.'\').value;
				
				var valor_pactado=document.getElementById(\'valor_pactado'.$j.'\').value;
				var valor_porcentaje=document.getElementById(\'valor_porcentaje'.$j.'\').value;
				var valor_total_pactado=document.getElementById(\'valor_total_pactado'.$j.'\').value;
				
				
				xajax_InsertarProductoAlContratoCopia(\''.$empresa.'\', \''.$contratacion_prod_id.'\', \''.$contrato.'\',codigo_producto,precio,valor_pactado,valor_porcentaje,valor_total_pactado);
             ');
         
		  
		  
   			}
			
		$objResponse->alert("SE GUARDO CORRECTAMENTE");
		$url=ModuloGetURL("app", "ContratacionProductos", "controller", "Menu");
		$objResponse->script('
		window.location="'.$url.'";
		');
		return $objResponse;
		}
      /**
  * Funcion que permite  insertar a  un contrato inactivo productos 
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
		function InsertarProductoAlContratoCopia($empresa,$contratacion_prod_id,$contrato,$producto,$costo,$vpesos,$vporc,$valortotal)
		{
			
			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("ContratacionProductosSQL", "", "app", "ContratacionProductos");
			$rst =$sel->IngresarProductoAlContratoCopia($empresa,$contratacion_prod_id,$contrato,$producto,$costo,$vpesos,$vporc,$valortotal);
			return $objResponse;
			
		}
      /**
  * Funcion que permite  seleccionar un contrato activo para ir a la funcion de los dias de envio
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
		function SeNrContrato($noId, $tipoId,$empresa)
		{

			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("ContratacionProductosSQL", "", "app", "ContratacionProductos");
			$sncontrato= $sel->ConsultarNoContratoEn($noId, $tipoId,$empresa);
			$html .= "<form name=\"forma2seleccion\" id=\"forma2seleccion\" method=\"post\" >\n";
			$html  = "  <table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";     
			$html .= "      <td width=\"20%\" align=\"center\">NRO.CONTRATO\n";
			$html .= "      </td>\n";
			$html .= "      <td colspan=\"35\" align=\"center\">DESCRIPCIÒN\n";
			$html .= "      </td>\n";
			$html .= "      <td   width=\"5%\" align=\"center\"> OP.\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";    
			$est = "modulo_list_claro";
			foreach($sncontrato as $indice => $valor)
			{
				($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
				$html .= "    <tr class=\"modulo_list_oscuro\">\n";
				$html .= "      <td  width=\"20%\" align=\"center\"  class=\"modulo_list_claro\"> <b>".$valor['no_contrato']."  </b></td>\n";
				$html .= "      <td  colspan=\"35\" align=\"center\"  class=\"modulo_list_claro\"> <b>".$valor['descripcion']."  </b></td>\n";
				$html .= "     <input type=\"hidden\" name=\"contratacion_prod_id\" id=\"contratacion_prod_id\" value=\"".$valor['contratacion_prod_id']."\">\n";
				$html .= "      <td align=\"center\" >\n";
				$html .= "  <a href=\"".ModuloGetURL("app", "ContratacionProductos", "controller", "MenuEnvio", array("tipoid"=>$valor['tipo_id_tercero'],"noid"=>$valor['tercero_id'],"scontrato"=>$valor['no_contrato']))."\">\n";
				$html .= "      <img src=\"".GetThemePath()."/images/vehiculo.png\" border=\"0\">\n";
				$html .= "        </a>\n";
				$html .= " </td>\n";
				$html .= "    </tr>\n";
			}
			$html .= "  </table>\n";
			$html .= "  </form>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		}
		  /**
  * Funcion que permite  seleccionar un contrato activo para consultar la informacion completa del mismo contrato
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
      	function SeContratoConsulta($noId, $tipoId,$empresa)
		{
			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("ContratacionProductosSQL", "", "app", "ContratacionProductos");
			$sncontrato= $sel->ConsultarNoContrato($noId, $tipoId,$empresa);
			$html .= "<form name=\"forma2seleccion\" id=\"forma2seleccion\" method=\"post\" >\n";
			$html  = "  <table class=\"modulo_table_list\" align=\"center\" width=\"50%\">\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";     
			$html .= "      <td width=\"10%\" align=\"center\">NRO.CONTRATO\n";
			$html .= "      </td>\n";
			$html .= "      <td   width=\"10%\" align=\"center\"> OP.\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";    
			$est = "modulo_list_claro";
			foreach($sncontrato as $indice => $valor)
			{
				($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
				$html .= "    <tr class=\"modulo_list_oscuro\">\n";
				$html .= "      <td  width=\"10%\" align=\"center\"  class=\"modulo_list_claro\"> <b>".$valor['no_contrato']."  </b></td>\n";
				$html .= "     <input type=\"hidden\" name=\"contratacion_prod_id\" id=\"contratacion_prod_id\" value=\"".$valor['contratacion_prod_id']."\">\n";
				$html .= "      <td align=\"center\" >\n";
				$html .= "  <a href=\"".ModuloGetURL("app", "ContratacionProductos", "controller", "ConsultaContrato", array("contratacion_prod_id"=>$valor['contratacion_prod_id']))."\">\n";
				$html .= "      <img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\">\n";
				$html .= "        </a>\n";
				$html .= " </td>\n";
				$html .= "    </tr>\n";
			}
			$html .= "  </table>\n";
			$html .= "  </form>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		}
		    /**
  * Funcion que permite  seleccionar un contrato activo para ir a la funcion de parametrizar laas politicas de vencimiento
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
       
		function SContratoConProduc($noId, $tipoId)
		{
  
			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("ContratacionProductosSQL", "", "app", "ContratacionProductos");
			$sncontrato= $sel->ConsultarContratosTienenProductos($noId, $tipoId);
			$html .= "<form name=\"forma2seleccion\" id=\"forma2seleccion\" method=\"post\" >\n";
			$html  = "  <table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";     
			$html .= "      <td width=\"10%\" align=\"center\">NRO.CONTRATO\n";
			$html .= "      </td>\n";
			$html .= "      <td   colspan=\"30\" align=\"center\"> DESCRIPCIÒN \n";
			$html .= "      </td>\n";
			$html .= "      <td   colspan=\"1\" align=\"center\"> ESTADO \n";
			$html .= "      </td>\n";
			$html .= "      <td   width=\"5%\" align=\"center\"> POLIT.\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";    
			$est = "modulo_list_claro";
			foreach($sncontrato as $indice => $valor)
			{
				($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
				$html .= "    <tr class=\"modulo_list_oscuro\">\n";
				$html .= "      <td  width=\"10%\" align=\"center\"  class=\"modulo_list_claro\"> <b>".$valor['no_contrato']."  </b></td>\n";
				$html .= "      <td   colspan=\"30\" align=\"center\"  class=\"modulo_list_claro\"> <b>".$valor['descripcion']."  </b></td>\n";
				$html .= "     <input type=\"hidden\" name=\"contratacion_prod_id\" id=\"contratacion_prod_id\" value=\"".$valor['contratacion_prod_id']."\">\n";

				$vestado="1";
				$html .= "      <td align=\"center\">\n";
				$html .= "      <img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\" title=\"Activo\">\n";
				$html .= "      </td>\n";
				$html .= "      <td align=\"center\" >\n";
				$html .= "  <a href=\"".ModuloGetURL("app", "ContratacionProductos", "controller", "MenuPolitica", array("scontrato"=>$valor['no_contrato']))."\">\n";
				$html .= "          <img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\" title=\"Modificar\" >\n";
				$html .= "         </a>\n";
				$html .= " </td>\n";
				$html .= "    </tr>\n";
			}
			$html .= "  </table>\n";
			$html .= "  </form>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		}
	  /**
  * Funcion que permite  modificar los dias de envio
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
    
	    function ModificarEnvio($nocontrato,$tipo,$inf)
		{

			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("ContratacionProductosSQL", "", "app", "ContratacionProductos");
			$rst =$sel->ModificarEnvios($inf,$nocontrato,$tipo);
			$objResponse->script(' 
			if("'.$rst.'"==false)
			{
			alert("HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION   -  ¡¡ POR FAVOR INGRESA UN  NUMERO !!   ");
			} ');
			return $objResponse;
		}
 
		  /**
  * Funcion que permite  seleccionar un contrato activo para ir a la funcion de subir cartas de los proveedores
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
		function SeleccionarContratoCarta($noId, $tipoId,$empresa)
		{
			$objResponse = new xajaxResponse();
     	$sel = AutoCarga::factory("ContratacionProductosSQL", "", "app", "ContratacionProductos");
			$sncontrato= $sel->ConsultarNoContrato($noId,$tipoId,$empresa);
   
			$html .= "<form name=\"forma2seleccion\" id=\"forma2seleccion\" method=\"post\" >\n";
			$html  = "  <table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
			$html .= "    <tr class=\"formulacion_table_list\">\n";     
			$html .= "      <td width=\"10%\" align=\"center\">NRO.CONTRATO\n";
			$html .= "      </td>\n";
			$html .= "      <td   colspan=\"2\" align=\"center\"> DESCRIPCIÒN. \n";
			$html .= "      </td>\n";
			$html .= "      <td   align=\"center\"> OP. \n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";    
			$est = "modulo_list_claro";
			foreach($sncontrato as $indice => $valor)
			{
				($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
				$html .= "    <tr class=\"modulo_list_oscuro\">\n";
				$html .= "      <td  width=\"10%\" align=\"center\"  class=\"modulo_list_claro\"> <b>".$valor['no_contrato']."  </b></td>\n";
				$html .= "     <input type=\"hidden\" name=\"contratacion_prod_id\" id=\"contratacion_prod_id\" value=\"".$valor['contratacion_prod_id']."\">\n";
				$html .= "      <td colspan=\"2\"  align=\"center\"  class=\"modulo_list_claro\"> <b>".$valor['descripcion']."  </b></td>\n";
				$html .= "      <td align=\"center\" >\n";
				$html .= "  <a href=\"".ModuloGetURL("app", "ContratacionProductos", "controller", "Subirimagen", array("scontrato"=>$valor['no_contrato'],"tipoid"=>$tipoId,"noid"=>$noId))."\">\n";
				$html .= "          <img src=\"".GetThemePath()."/images/show.gif\" border=\"0\" title=\"subir carta\" >\n";
				$html .= "         </a>\n";
				$html .= " </td>\n";
				$html .= "    </tr>\n";
			}

			$html .= "  </table>\n";
			$html .= "  </form>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
		}
  /**
  * Funcion que permite  eliminar un producto asociado al contrato  activo
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
  
		function eliminar($codigo_producto,$contratacion_prod_id,$empresa)
		{

			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("ContratacionProductosSQL", "", "app", "ContratacionProductos");
			$rst =$sel->eliminar($codigo_producto,$contratacion_prod_id,$empresa);
      if($rst==true)
      {
        $url=ModuloGetURL("app", "ContratacionProductos", "controller", "listaproductoasociContrato",array("contratosus"=>$contratacion_prod_id));
			  $objResponse->script('
				window.location="'.$url.'";
				');
      }else
      {
         $objResponse->alert('ERROR AL ELIMINAR EL PRODUCTO');
       
      
      }
      
			return $objResponse;
		}

/**
  * Funcion que permite  seleccionar las bodegas asociadas a la empresa
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
		function Bodegas($empresa)
		{
		    $objResponse = new xajaxResponse();
		    $mdl = AutoCarga::factory("ContratacionProductosSQL", "", "app", "ContratacionProductos");
		    $bodega = $mdl->consultarBodegasAsociacion($empresa);
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend class=\"normal_10AN\" align=\"center\">SELECCIONAR LA BODEGA </legend>\n";
			$html .= "<form name=\"FormaEmpresa1\"  id=\"FormaEmpresa1\" method=\"post\" >\n";
			$html  .= "  <table  border=\"0\"  class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
			$html .= "    <tr   class=\"modulo_table_list_title\"  width=\"30%\" >\n";
    		$html .= "			<td align=\"center\"><b>BODEGA:</B></td>\n";
			$html .= "			<td  align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "					<select name=\"bodegas\" class=\"select\">\n";
			$html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($bodega as $id => $bod)
				{
					if($bod['bodega']==$request['bodega'])
					$sel = "selected";
					else   $sel = "";
					$html .= "  <option value=\"".$bod['bodega']."\" ".$sel.">".$bod['descripcion']."</option>\n";
				}
		    $html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
			$html .= "         <a href=\"#\" onclick=\"xajax_ListaBodeEmpresa(document.FormaEmpresa1.bodegas.value,'".$bod['centro_utilidad']."','".$empresa."')\"><img src=\"".GetThemePath()."/images/flecha_der.gif\" border=\"0\" title=\"\"></a>\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";
			$html .= "  </table><br>\n";
			
			$html .= "  </form>\n";
			$html .= "</fieldset><br>\n";
			$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
			$objResponse->call("MostrarSpan");
			return $objResponse;
	
		}
		  /**
  * Funcion que permite   ir a la funcion asociaciones 
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
		function ListaBodeEmpresa($bodega,$centro,$empresa)
		{
		 $objResponse = new xajaxResponse();
		  $url=ModuloGetURL("app", "ContratacionProductos", "controller", "Empresas_Planes_Activos",array("Empresa"=>$empresa,"bodega"=>$bodega,"centro"=>$centro));
		  $objResponse->script('
						 window.location="'.$url.'";
								');
          return $objResponse;
		
		}
	  /**
  * Funcion que permite   asociar un plan la bodega de la farmacia
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
		
		function AsociarPlan($planid,$empresa,$centro,$bodega,$empresa_plan)
		{
				$objResponse = new xajaxResponse();
				$url=ModuloGetURL("app", "ContratacionProductos", "controller", "Asociaciones",array("Empresa"=>$empresa,"bodega"=>$bodega,"centro"=>$centro,"empresa_plane"=>$empresa_plan));
		        $objResponse->script('
		 		 window.location="'.$url.'";
					'); 
				$mdl = AutoCarga::factory("ContratacionProductosSQL", "classes", "app", "ContratacionProductos");
				$insertar=$mdl->InsertarPlanes($empresa,$centro,$bodega,$planid); 
				
			return $objResponse;
	}
	  /**
  * Funcion que permite  consultar los planes asociados
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
		function  ConsultarPlanesAsociados($empresa,$centro,$bodega,$empresa_plan)
		{
				
				$objResponse = new xajaxResponse();
				$mdl = AutoCarga::factory("ContratacionProductosSQL", "classes", "app", "ContratacionProductos");
				$cons=$mdl->ConsultarInformacionAsociacion($empresa,$centro,$bodega);
				if(!empty($cons))
				{
				
		        $html  = "<fieldset class=\"fieldset\">\n";
			    $html .= "  <legend class=\"normal_10AN\" align=\"center\">PLANES ASOCIADOS A LA FARMACIA</legend>\n";
				$html .= "<form name=\"planes\" id=\"planes\" method=\"post\" >\n";
				$html .= "  <table width=\"95%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
				$html .= "	  <tr  align=\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td  width=\"55%\">PLAN-DESCRIPCION</td>\n";
				$html .= "  </tr>\n";
				foreach($cons as $llave => $proveedor)
				{
					$html .= "  <tr class=\"modulo_list_claro\">\n";
					$html .= "      <td align=\"left\">".$proveedor['plan']."</td>\n";
					$html .= "      <td width=\"1%\">\n";
    				$html .= "       <a href=\"#\" onclick=\"xajax_Planes_Elimina_('".$proveedor['plan_id']."','".$empresa."','".$centro."','".$bodega."','".$empresa_plan."');\">";
    				$html .= "      	  <img src=\"".GetThemePath()."/images/elimina.png\"  border=\"0\">\n";
    				$html .= "        </a>\n";
    				$html .= "      </td>\n";
					
					$html .= "  </tr>\n";
				}			
			
			$html .= "	</table>\n";
			$html .= "  </form>\n";
			$html .= "</fieldset><br>\n";
			}else
			{
			
			    $html  = "<fieldset class=\"fieldset\">\n";
			    $html .= "  <legend class=\"normal_10AN\" align=\"center\">PLANES ASOCIADOS A LA FARMACIA</legend>\n";
				$html .= "<form name=\"planes\" id=\"planes\" method=\"post\" >\n";
				$html .= "  <table width=\"95%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
				$html .= "	  <tr  align=\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td  width=\"55%\"> NO HAY PLANES ASOCIADOS</td>\n";
				$html .= "  </tr>\n";
				$html .= "	</table>\n";
				$html .= "  </form>\n";
				$html .= "</fieldset><br>\n";
			}
			$objResponse->assign("plan_id_c","innerHTML",$objResponse->setTildes($html));
			
			
			return $objResponse;
		}
		
	  /**
  * Funcion que permite parametrizar la informacion de los  tipos de productos para las polticas de vencimiento
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
	  Function  parametrizarInformacion($tipo_producto_id,$descripcion)
    {
        $objResponse = new xajaxResponse();
		$tipoid = SessionGetVar("tipoid");
		$noid = SessionGetVar("noid");
		$codprov = SessionGetVar("codprov");
		$contratoid = SessionGetVar("contratoid");
		$emp = SessionGetVar("DatosEmpresaCP");
		$empresa=$emp['empresa'];
		$contratacion = AutoCarga::factory('ContratacionProductosSQL', '', 'app', 'ContratacionProductos');
		$datos2=$contratacion->ConsultarInformacionContratoPoliticas($empresa,$tipoid,$noid,$contratoid,$tipo_producto_id);
		$datos3=$contratacion->ConsultarPoliticas($noid);
    
          $html .= "<form name=\"FormaPolit".$tipo_producto_id."\" id=\"FormaPolit".$tipo_producto_id."\" method=\"post\" >\n";
          $html.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"4\" align=\"center\" class=\"modulo_table_title\">   PARAMETRIZACION TIPO DE PRODUCTOS($descripcion)</td>";
          $html.= "	</tr>";
          $html.= "	<tr>";
          $html.= "	<td colspan=\"2\" class=\"modulo_table_title\">*INFORMACION:</td>";
          if($tipo_producto_id=='1')
          {
          foreach ($datos3 as $k=>$de)
          {
          
          $valor.= $de['descripcion'].",";
          }
          }
         
      
          $html.= "	<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\"><textarea name=\"informacion\" id=\"informacion\" class=\"input-text\" cols=\"65%\" rows=\"2\">".$datos2['0']['politica_descripcion']." ".$valor."</textarea></td>";
          $html.= "	</tr>";
          $html.= "	</tr>";
          $html.= "	<td colspan=\"4\" align=\"center\" class=\"modulo_list_oscuro\"><input type=\"button\" name=\"save_rolporf\" id=\"save_rolporf\" class=\"input-submit\" value=\"INSERTAR\" onclick=xajax_InsertarPoliticasVencimiento('".$tipo_producto_id."',document.FormaPolit$tipo_producto_id.informacion.value);></td>";
          $html.= "	</tr>";
          $html.= "	</table><BR>";
          $html .= "</form>";
          $objResponse->assign("Polticas".$tipo_producto_id,"innerHTML",$objResponse->setTildes($html));
          return $objResponse;
       
		}
		  /**
  * Funcion que permite  insertar las politicas de vencimiento
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
    
	    function InsertarPoliticasVencimiento($tipo_producto_id,$informacion)
	    {
	        $objResponse = new xajaxResponse();
			$tipoid = SessionGetVar("tipoid");
			$noid = SessionGetVar("noid");
			$codprov = SessionGetVar("codprov");
			$contratoid = SessionGetVar("contratoid");
			$emp = SessionGetVar("DatosEmpresaCP");
			$empresa=$emp['empresa'];
			$contratacion = AutoCarga::factory('ContratacionProductosSQL', '', 'app', 'ContratacionProductos');
			$datos2=$contratacion->ConsultarInformacionContratoPoliticas($empresa,$tipoid,$noid,$contratoid,$tipo_producto_id);
	        if(!empty($datos2))
			{
				$datos2=$contratacion->ActualizarPoliticasVencimiento($empresa,$tipoid,$noid,$contratoid,$informacion,$tipo_producto_id);
			}
			else 
			$datos3=$contratacion->IngresarPoliticaVencimiento($contratoid,$tipoid,$noid,$informacion,$tipo_producto_id,$empresa);

			$html.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
	        $html.= "	<tr>";
	        $html.= "	<td colspan=\"4\" class=\"modulo_table_title\">PARAMETRIZACION TIPO DE PRODUCTOS</td>";
	        $html.= "	</tr>";
	        $html.= "	<tr>";
	        $html.= "	<td class=\"hc_table_submodulo_list_title\" width=\"25%\">INFORMACION:</td>";
	        $html.= "	<td class=\"modulo_list_oscuro\" colspan=\"2\" >".$informacion."</td>";
	        $html.= "	</tr>";
	        $html.= "	</table><BR>";
	        $objResponse->assign("Polticas".$tipo_producto_id,"innerHTML",$objResponse->setTildes($html));
	        return $objResponse;
	    }
		
		  /**
  * Funcion que permite  parametrizar los dias de envio
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
		Function  ParametrizarDiasEnvio($tipo_producto_id,$descripcion)
	    {
	        $objResponse = new xajaxResponse();
			$tipoid = SessionGetVar("tipoid");
			$noid = SessionGetVar("noid");
			$codprov = SessionGetVar("codprov");
			$contratoid = SessionGetVar("contratoid");
			$emp = SessionGetVar("DatosEmpresaCP");
			$empresa=$emp['empresa'];
			$contratacion = AutoCarga::factory('ContratacionProductosSQL', '', 'app', 'ContratacionProductos');
			$datos2=$contratacion->consultarInformacionevio($empresa,$contratoid,$tipo_producto_id);
	        
			  $html .= "<form name=\"FormaDia".$tipo_producto_id."\" id=\"FormaDia".$tipo_producto_id."\" method=\"post\" >\n";
	          $html.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
	          $html.= "	<tr>";
	          $html.= "	<td colspan=\"1\" class=\"modulo_table_title\">* DIAS:</td>";
	          $html.= "	<td class=\"modulo_list_oscuro\" width=\"25%\"><input type=\"text\" name=\"diasd\" id=\"diasd\" class=\"input-text\" size=\"5\" maxlength=\"10\" value=\"".$datos2['0']['dias_envio']."\" ></td>";
	          $html.= "	</tr>";
	          $html.= "	</tr>";
	          $html.= "	<td colspan=\"4\" align=\"center\" class=\"modulo_list_oscuro\"><input type=\"button\" name=\"save_rolporf\" id=\"save_rolporf\" class=\"input-submit\" value=\"INSERTAR\" onclick=xajax_validarInformacionDiasEnvio('".$tipo_producto_id."',document.FormaDia$tipo_producto_id.diasd.value);></td>";
	          $html.= "	</tr>";
	          $html.= "	</table>";
	          $html .= "</form>";
	          $objResponse->assign("dia".$tipo_producto_id,"innerHTML",$objResponse->setTildes($html));
	          return $objResponse;
	    }
	  /**
  * Funcion que permite validar la informacion de los dias de envio
  * @return Object $objResponse objeto de respuesta al formulario  
  */  
			
		function validarInformacionDiasEnvio($tipo_producto_id,$dias)
		{
			$objResponse = new xajaxResponse();
			$ctl = AutoCarga::factory('ClaseUtil');
			$html  = $ctl->IsNumeric();
			$html .= $ctl->AcceptNum(false);
			$objResponse->script('
				function IsNumeric(valor)
				{
				var log = valor.length; 
				var sw=\'S\';
				var puntos = 0;
				for (x=0; x<log; x++)
				{ 
				v1 = valor.substr(x,1);
				v2 = parseInt(v1);
				if(v1 == \'.\')
				{
				puntos ++;
				}
				else if (isNaN(v2))
				{
				sw= \'N\';
				break;
				}
					}
				if(log == 0) sw = \'N\';
				if(puntos > 1) sw = \'N\';
				if(sw==\'S\') return true;
				return false;
				} 
			
			if(IsNumeric(document.getElementById(\'diasd\').value) && document.getElementById(\'diasd\').value!=\'\')
			{
			xajax_InsertarDiasEnvio(\''.$tipo_producto_id.'\', \''.$dias.'\');

			}
			else 
			alert("POR FAVOR INGRESE UN NUMERO DE DIA DE ENVIO");
			');
		     return $objResponse;
		
		}
		  /**
  * Funcion que permite  insetar dias de envio
  * @return Object $objResponse objeto de respuesta al formulario  
  */      function InsertarDiasEnvio($tipo_producto_id,$dias)
	    {
	        $objResponse = new xajaxResponse();
			$contratacion = AutoCarga::factory('ContratacionProductosSQL', '', 'app', 'ContratacionProductos');
			$tipoid = SessionGetVar("tipoid");
			$noid = SessionGetVar("noid");
			$codprov = SessionGetVar("codprov");
			$contratoid = SessionGetVar("contratoid");
			$emp = SessionGetVar("DatosEmpresaCP");
			$empresa=$emp['empresa'];
			$datos2=$contratacion->consultarInformacionevio($empresa,$contratoid,$tipo_producto_id);
	        if(!empty($datos2))
			{
				$datos2=$contratacion->ActualizarDiasenvio($empresa,$contratoid,$dias,$tipo_producto_id);
			}
			else 
			$datos3=$contratacion->IngresarDiasEnvioContrato($empresa,$contratoid,$tipo_producto_id,$dias);
	        
	        $html.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
	        $html.= "	<tr>";
	        $html.= "	<td colspan=\"1\" class=\"modulo_table_title\">* DIAS:</td>";
	        $html.= "	<td class=\"modulo_list_oscuro\" width=\"25%\">".$dias."</td>";
           	$html.= "	</tr>";
			$html.= "	</table>";
	        $objResponse->assign("dia".$tipo_producto_id,"innerHTML",$objResponse->setTildes($html));
	        return $objResponse;
	    }
		
		/*
		* Forma que permite  Eliminar los productos seleccionados que ya no se van a enviar
		* @return object $objResponse objeto de respuesta al formulario.
	*/
    
    function Planes_Elimina_($plan,$farmacia,$centro,$bodega,$empresa_plan)
    {
      $objResponse = new xajaxResponse();
      $contratacion = AutoCarga::factory('ContratacionProductosSQL', '', 'app', 'ContratacionProductos');

      $rst =$contratacion->Eliminar_producto_planes($plan,$farmacia,$centro,$bodega);
      if($rst==true)
	  {
		      $url=ModuloGetURL("app", "ContratacionProductos", "controller", "Asociaciones",array("Empresa"=>$farmacia,"bodega"=>$bodega,"centro"=>$centro,"empresa_plane"=>$empresa_plan));
		        $objResponse->script('
		 		 window.location="'.$url.'";
					'); 
      }
      return $objResponse;
    
    }
		
?>