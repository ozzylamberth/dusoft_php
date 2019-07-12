<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Solicitud.php,v 1.7 2008/11/14 21:27:49 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.7 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  
  /**
  * Funcion que permite hacer la busqueda de cargos, listarlos y mostrarlos
  * por paginas
  * 
  * @param array $form Vector de datos con los filtros de busqueda
  * @param int $off Numero de la pagina que se esta visualizando en el momento
  *
  * @return object
  */
	function BuscarCargos($form,$off = 0)
	{
		$objResponse = new xajaxResponse();
		$slm = AutoCarga::factory('SolicitudesAutorizacion','','app','UV_SolicitudesAutorizaciones');
				
		$cargos = $slm->ObtenerCargos($form,$off);
		$html = "";
		if(!empty($cargos))
		{
			$est = "modulo_list_claro";
			$action = "Buscar(xajax.getFormValues('buscar')";
			$pghtml = AutoCarga::factory('ClaseHTML');
			
      $html .= "<br>\n";			
			$html .= $pghtml->ObtenerPaginadoXajax($slm->conteo,$slm->pagina,$action);
			
			$html .= "<table align=\"center\" class=\"modulo_table_list\" width=\"98%\">";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "  	<td width=\"15%\">TIPO CARGO</td>\n";
			$html .= "  	<td width=\"10%\">CARGO</td>\n";
			$html .= "  	<td width=\"%\" colspan=\"2\">DESCRIPCION</td>\n";
			$html .= "  	<td width=\"4%\" >CANT</td>\n";
			$html .= "  	<td width=\"2%\" ></td>\n";
			$html .= "	</tr>\n";
			foreach($cargos as $key => $rst)
			{
				($est == "modulo_list_claro")? $est = "modulo_list_oscuro": $est = "modulo_list_claro";
				
        $contrato = $slm->ObtenerValidacionContrato($rst['cargo'],$form['plan_id']);
        
				$html .= "	<tr class=\"".$est."\">\n";
				$html .= "  	<td align=\"center\" class=\"normal_10AN\">".$rst['tipo']."</td>\n";
				$html .= "  	<td align=\"center\" class=\"normal_10AN\">".$rst['cargo']."</td>\n";
        $html .= "		<td class=\"label\">".$rst['descripcion']."</td>\n";
        if($contrato > 0)
          $html .= "		<td width=\"10%\" align=\"center\" class=\"label\">CUBIERTO</td>\n";
				else
          $html .= "		<td width=\"10%\" align=\"center\" class=\"label_error\">NO CUBIERTO</td>\n";
		
        $html .= "		<td>\n";
        if($rst['sw_cantidad'] == 1)
          $html .= "    <input type=\"text\" class=\"input-text\" style=\"width:100%\" onkeypress=\"return acceptNum(event)\" id=\"cargo".$key."\">\n";
        else
          $html .= "    <input type=\"hidden\" value=\"1\" id=\"cargo".$key."\">1\n";
        $html .= "		</td>\n";
        $html .= "		<td>\n";
				$html .= "			<a href=\"javascript:Adicionar('".$rst['cargo']."','','',document.getElementById('cargo".$key."').value)\" title=\"ADICIONAR CARGOS\">\n";
				$html .= "				<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
				$html .= "			</a>\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
			}
			$html .= "</table>\n";
			$html .= $pghtml->ObtenerPaginadoXajax($slm->conteo,$slm->pagina,$action,true);
			$html .= "<br>\n";
		}			
		else
		{
			$html = "<label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
		}
    
		$html = utf8_encode( $html );
		$objResponse->assign("error_adicion","innerHTML","");
		$objResponse->assign("buscador","innerHTML",$html);
		$objResponse->assign("buscador","style.display","block");
		
		return $objResponse;
	}
  /**
  * Funcion que permite hacer la busqueda de medicamentos, listarlos y mostrarlos
  * por paginas 
  * 
  * @param array $form Vector de datos con los filtros de busqueda
  * @param int $off Numero de la pagina que se esta visualizando en el momento
  *
  * @return object
  */
  function BuscarMedicamentos($form,$off = 0)
  {
  	$objResponse = new xajaxResponse();
		$slm = AutoCarga::factory('SolicitudesAutorizacion','','app','UV_SolicitudesAutorizaciones');
    $empresa = SessionGetVar("EmpresasSolicitudes");
    
    $medicamentos = $slm->ObtenerMedicamentos($form,$empresa['empresa'],$off);
    
    $html = "";
		if(!empty($medicamentos))
		{
			$est = "modulo_list_claro";
			$action = "BuscarMedicamentos(1";
			$pghtml = AutoCarga::factory('ClaseHTML');
			
      $html .= "<br>\n";			
			$html .= $pghtml->ObtenerPaginadoXajax($slm->conteo,$slm->pagina,$action);
			
			$html .= "<table align=\"center\" class=\"modulo_table_list\" width=\"98%\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "  	<td width=\"8%\" ></td>\n";
      $html .= "  	<td width=\"10%\">CODIGO</td>\n";
      $html .= "  	<td width=\"20%\">PRINCIPIO ACTIVO</td>\n";
      $html .= "  	<td width=\"%\" colspan=\"2\" >DESCRIPCION</td>\n";
      $html .= "  	<td width=\"10%\">UNIDAD DE MEDIDA</td>\n";
      $html .= "  	<td width=\"4%\" >CANT</td>\n";
			$html .= "  	<td width=\"2%\" ></td>\n";
			$html .= "	</tr>\n";
			foreach($medicamentos as $key => $rst)
			{
				($est == "modulo_list_claro")? $est = "modulo_list_oscuro": $est = "modulo_list_claro";
				
				$html .= "	<tr class=\"".$est."\">\n";
				$html .= "  	<td align=\"center\" class=\"normal_10AN\">".$rst['item']."</td>\n";
				$html .= "  	<td align=\"center\" class=\"normal_10AN\">".$rst['codigo_producto']."</td>\n";
				$html .= "		<td class=\"label\">".$rst['principio_activo']."</td>\n";
				$html .= "  	<td class=\"label\">".$rst['descripcion_producto']."</td>\n";
        $html .= "		<td width=\"10%\" align=\"center\" class=\"label\">CUBIERTO</td>\n";
        $html .= "		<td >".$rst['ummi']."</td>\n";
        $html .= "    <td>\n";
        $html .= "      <input type=\"text\" class=\"input-text\" style=\"width:100%\" onkeypress=\"return acceptNum(event)\" id=\"medicamento".$key."\">\n";
        $html .= "		</td>\n";
        $html .= "		<td>\n";
				$html .= "			<a href=\"javascript:Adicionar('','".$rst['codigo_producto']."','',document.getElementById('medicamento".$key."').value)\" title=\"ADICIONAR MEDICAMENTOS\">\n";
				$html .= "				<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
				$html .= "			</a>\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
			}
			$html .= "</table>\n";
			$html .= $pghtml->ObtenerPaginadoXajax($slm->conteo,$slm->pagina,$action,true);
			$html .= "<br>\n";
		}			
		else
		{
			$html = "<label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
		}
    
    $html = utf8_encode( $html );
    $objResponse->assign("error_adicion","innerHTML","");
		$objResponse->assign("medicamentos","innerHTML",$html);
		$objResponse->assign("medicamentos","style.display","block");
		
		return $objResponse;
  }
	/**
  * Funcion que permite adicionar a una variable se sesion, el cargo, el
  * medicamento o el concepto seleccionado, para luego mostrarlo en 
  * una tabla al usuario
  *
  * @param string $cargo Codigo del cargo
  * @param string $medicamento Codigo del medicamento
  * @param array $concepto Vector con los datos del concepto adicional
  *
  * @return object
  */
	function Adicionar($cargo,$medicamento,$concepto,$cantidad)
	{
		$slm = AutoCarga::factory('SolicitudesAutorizacion','','app','UV_SolicitudesAutorizaciones');
    $objResponse = new xajaxResponse();
    
    $cargos = SessionGetVar("CargosAdicionados");
    $medicamentos = SessionGetVar("MedicamentosAdicionados");
    $conceptos = SessionGetVar("ConceptosAdicionados");
    
    if($cargo)  
    {
      $rst = $slm->ObtenerCargos(array("cargo"=>$cargo));
      $cargos[$cargo] = $rst[0];
      $cargos[$cargo]['cantidad'] = $cantidad;
      //$objResponse->alert(print_r($cargos,true));
      SessionSetVar("CargosAdicionados",$cargos);
    }
    else if($medicamento)
    {
      $empresa = SessionGetVar("EmpresasSolicitudes");
      $rst = $slm->ObtenerMedicamentos(array("codigo"=>$medicamento),$empresa['empresa']);
      $medicamentos[$medicamento] = $rst[0];
      $medicamentos[$medicamento]['cantidad'] = $cantidad;
      SessionSetVar("MedicamentosAdicionados",$medicamentos);
    }
    else if(is_array($concepto))
    {
      $i = sizeof($conceptos);
      (is_numeric($i))? $i++: $i=0;
      
      $rst = $slm->ObtenerTiposConceptos($concepto['concepto_id']);
      $conceptos[$i] = $rst[0];
      $conceptos[$i]['concepto_adicional'] = $concepto['descripcion_concepto'];
      SessionSetVar("ConceptosAdicionados",$conceptos);
      $objResponse->call("LimpiarConceptos");
    }
			
		$html = TablaAdicionales($cargos,$medicamentos,$conceptos);
    $html = utf8_encode( $html );
		$objResponse->assign("boton_aceptar","style.display","block");
		$objResponse->assign("error_adicion","innerHTML","");
		$objResponse->assign("adicionados","innerHTML",$html);
		
		return $objResponse;
	}
	/**
  * Funcion que permite eliminar de una variable se sesion, el cargo, el
  * medicamento o el concepto seleccionado
  *
  * @param string $cargo Codigo del cargo
  * @param string $medicamento Codigo del medicamento
  * @param array $concepto Vector con los datos del concepto adicional
  *
  * @return object
  */
	function Eliminar($cargo,$medicamento,$concepto)
	{
		$html = "";
		$cargos = SessionGetVar("CargosAdicionados");
		$medicamentos = SessionGetVar("MedicamentosAdicionados");
    $conceptos = SessionGetVar("ConceptosAdicionados");
    
    if($cargo)
    {
      unset($cargos[$cargo]);
      SessionSetVar("CargosAdicionados",$cargos);
    }
    else if($medicamentos)
    {
      unset($medicamentos[$medicamento]);
      SessionSetVar("MedicamentosAdicionados",$medicamentos);
    }
    else if($concepto)
    {
      unset($conceptos[$concepto]);
      SessionSetVar("ConceptosAdicionados",$conceptos);
    }
    
		$objResponse = new xajaxResponse();
		
		if(!empty($cargos) || !empty($medicamentos) || !empty($conceptos))
		{
			$html = TablaAdicionales($cargos,$medicamentos,$conceptos);
			$html = utf8_encode($html);
		}
		else
		{
			$objResponse->assign("boton_aceptar","style.display","none");
		}
		
		$objResponse->assign("adicionados","innerHTML",$html);
		$objResponse->assign("buscador","style.display","block");
		$objResponse->assign("equivalencia","style.display","none");
		return $objResponse;
	}
	/**
  * Funcion donde se crea la tabla que contiene los cargos, los medicamentos
  * y los conceptos seleccionados
  *
  * @param array $cargos Arreglo con los datos de los cargos
  * @param array $medicamentos Arreglo con los datos de los medicamentos
  * @param array $conceptos Arreglo con los datos de los conceptos adicionales
  *
  * @return string
  */
	function TablaAdicionales($cargos,$medicamentos,$conceptos)
	{
    $html .= "<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
		if(!empty($cargos))
    {
      $html .= "	<tr class=\"formulacion_table_list\">\n";
  		$html .= "		<td colspan=\"5\">CARGOS SELECCIONADOS</td>\n";
  		$html .= "	</tr>\n";
  		$html .= "	<tr class=\"modulo_table_list_title\">\n";
  		$html .= "		<td width=\"10%\">CUPS</td>\n";
  		$html .= "		<td colspan=\"2\" width=\"%\">DESCRIPCION</td>\n";
  		$html .= "		<td width=\"5%\">CANT</td>\n";
  		$html .= "		<td width=\"5%\"></td>\n";
  		$html .= "	</tr>\n";
  	
  		foreach($cargos as  $key => $crg)
  		{
  			$html .= "	<tr class=\"modulo_list_claro\">\n";
  			$html .= "		<td>".$crg['cargo']."</td>\n";
  			$html .= "		<td colspan=\"2\">".$crg['descripcion']."</td>\n";
  			$html .= "		<td ><b>".$crg['cantidad']."</b></td>\n";
  			$html .= "		<td align=\"center\">\n";
  			$html .= "			<a href=\"javascript:Eliminar('".$crg['cargo']."','','')\" title=\"ELIMINAR CARGO\">\n";
  			$html .= "				<img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
  			$html .= "			</a>\n";
  			$html .= "		</td>\n";				
  			$html .= "	</tr>\n";
  		}
		}
    
    if(!empty($medicamentos))
    {
      $html .= "	<tr class=\"formulacion_table_list\">\n";
  		$html .= "		<td colspan=\"5\">MEDICAMENTOS SELECCIONADOS</td>\n";
  		$html .= "	</tr>\n";
  		$html .= "	<tr class=\"modulo_table_list_title\">\n";
  		$html .= "		<td width=\"10%\">CODIGO</td>\n";
  		$html .= "		<td width=\"%\" colspan=\"2\">DESCRIPCION</td>\n";
      $html .= "		<td width=\"5%\">CANT</td>\n";
  		$html .= "		<td width=\"5%\"></td>\n";
  		$html .= "	</tr>\n";
  	
  		foreach($medicamentos as  $key => $crg)
  		{
  			$html .= "	<tr class=\"modulo_list_claro\">\n";
  			$html .= "		<td>".$crg['codigo_producto']."</td>\n";
  			$html .= "		<td width=\"15%\">".$crg['principio_activo']."</td>\n";
  			$html .= "		<td >".$crg['descripcion_producto']."</td>\n";
        $html .= "		<td ><b>".$crg['cantidad']."</b></td>\n";
  			$html .= "		<td align=\"center\">\n";
  			$html .= "			<a href=\"javascript:Eliminar('','".$crg['codigo_producto']."','')\" title=\"ELIMINAR MEDICAMENTO\">\n";
  			$html .= "				<img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
  			$html .= "			</a>\n";
  			$html .= "		</td>\n";				
  			$html .= "	</tr>\n";
  		}
		}
    
    if(!empty($conceptos))
    {
      $html .= "	<tr class=\"formulacion_table_list\">\n";
  		$html .= "		<td colspan=\"4\">CONCEPTOS ADICIONADOS</td>\n";
  		$html .= "	</tr>\n";
  		$html .= "	<tr class=\"modulo_table_list_title\">\n";
  		$html .= "		<td width=\"15%\">TIPO CONCEPTO</td>\n";
  		$html .= "		<td width=\"%\" colspan=\"2\">DESCRIPCION</td>\n";
  		$html .= "		<td width=\"5%\"></td>\n";
  		$html .= "	</tr>\n";
  	
  		foreach($conceptos as  $key => $crg)
  		{
  			$html .= "	<tr class=\"modulo_list_claro\">\n";
  			$html .= "		<td>".$crg['descripcion_concepto']."</td>\n";
  			$html .= "		<td colspan=\"2\">".$crg['concepto_adicional']."</td>\n";
  			$html .= "		<td align=\"center\">\n";
  			$html .= "			<a href=\"javascript:Eliminar('','','".$crg['tipo_concepto_id']."')\" title=\"ELIMINAR MEDICAMENTO\">\n";
  			$html .= "				<img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
  			$html .= "			</a>\n";
  			$html .= "		</td>\n";				
  			$html .= "	</tr>\n";
  		}
		}
    $html .= "</table>\n";
		return $html;
	}
  /**
  * Funcion donde se valida que se hayan escogido cargos, medicamentos o
  * adicionado conceptos
  *
  * @return object
  */
  function ValidarDatos()
  {
    $cargos = SessionGetVar("CargosAdicionados");
    $conceptos = SessionGetVar("ConceptosAdicionados");
		$medicamentos = SessionGetVar("MedicamentosAdicionados");
    
    $objResponse = new xajaxResponse();
    
    if(empty($cargos) && empty($conceptos) && empty($medicamentos))
    {
      $mensaje = "PARA CONTINUAR CON EL PROCESO DE LA SOLICITUD, SE DEBE HACER LA SELECCION DE CARGOS, MEDICAMENTOS Y/O ADICION DE CONCEPTOS";
      $objResponse->assign("error_adicion","innerHTML",$mensaje);
    }
    else
    {
      $objResponse->call("ContinuarProcesoSolicitud");
    }
    return $objResponse;
  }
  /**
  *
  */
  function SeleccionarItems($solicitud,$cargo,$plan)
  {
    $objResponse = new xajaxResponse();
    $html = FormaSeleccionItems($solicitud,$cargo,$plan);
    
    $objResponse->assign("ventana","innerHTML",$html);
    $objResponse->assign("error_p","innerHTML","");
    $objResponse->script("MostrarSpan(350,210);");
    
    return $objResponse;
  }  
  /**
  *
  */
  function IngresarItemsSeleccionados($form,$solicitud,$cargo,$plan)
  {
    $objResponse = new xajaxResponse();
    
    $flag = false;
    foreach($form['check'] as $k => $d)
    {
      if($d == 'on')
      {
        $flag = true;
        break;
      }
    }
    
    if(!$flag)
      $objResponse->assign("error_p","innerHTML","POR SELECCIONAR ALGUN ITEM PARA EL CARGO DE CIRUGIA");
    else
    {
      $html = FormaSeleccionarCargos($form['check'],$solicitud,$cargo,$plan);
      $objResponse->assign("ventana","innerHTML",$html);
      $objResponse->script("MostrarSpan(550,400);");
    }
    
    return $objResponse;
  }  
  /**
  *
  */
  function RegistrarCargos($form,$solicitud,$cargo)
  {
    $objResponse = new xajaxResponse();
    
    $flag = false;
    foreach($form['cargos'] as $k => $d)
    {
      if($d['cargo'])
      {
        $flag = true;
        break;
      }
    }
    
    if(!$flag)
      $objResponse->assign("error_p","innerHTML","POR SELECCIONAR LOS CARGOS DE LA SOLICITUD DE CIRUGIA");
    else
    {
      $slm = AutoCarga::factory('SolicitudesAutorizacion','','app','UV_SolicitudesAutorizaciones');
      $rst = $slm->AdicionarCargosSolicitud($form['cargos'],$solicitud,$cargo);
      if(!$rst)
        $objResponse->assign("error_p","innerHTML",$slm->mensajeDeError);
      else
        $objResponse->call("Recargar");
    }    
    return $objResponse;
  }
  /**
  * 
  * @return string
  */
  function FormaSeleccionItems($solicitud,$cargo,$plan)
  {
    $html  = "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"label\">SELECCION ITEMS CIRUGIA</legend>\n";
    $html .= "	<table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";
    $html .= "	  <tr class=\"modulo_list_claro\">\n";
    $html .= "	    <td class=\"label\"width=\"90%\">CIRUJANO</td>\n";
    $html .= "			<td >\n";
    $html .= "		    <input type=\"checkbox\" name=\"check[c]\">\n";
    $html .= "			</td>\n";
    $html .= "	  </tr>\n";
    $html .= "	  <tr class=\"modulo_list_claro\">\n";    
    $html .= "	    <td class=\"label\">ANESTESIOLOGO</td>\n";
    $html .= "			<td >\n";
    $html .= "		    <input type=\"checkbox\" name=\"check[a]\">\n";
    $html .= "			</td>\n";  
    $html .= "	  </tr>\n";
    $html .= "	  <tr class=\"modulo_list_claro\">\n";     
    $html .= "	    <td class=\"label\">AYUDANTE</td>\n";
    $html .= "			<td >\n";
    $html .= "		    <input type=\"checkbox\" name=\"check[y]\">\n";
    $html .= "			</td>\n";   
    $html .= "	  </tr>\n";
    $html .= "	  <tr class=\"modulo_list_claro\">\n";     
    $html .= "	    <td class=\"label\">DERECHOS DE SALA</td>\n";
    $html .= "			<td >\n";
    $html .= "		    <input type=\"checkbox\" name=\"check[s]\">\n";
    $html .= "			</td>\n";   
    $html .= "	  </tr>\n";
    $html .= "	  <tr class=\"modulo_list_claro\">\n";     
    $html .= "	    <td class=\"label\">DERECHO DE MATERIALES</td>\n";
    $html .= "			<td >\n";
    $html .= "		    <input type=\"checkbox\" name=\"check[m]\">\n";
    $html .= "			</td>\n";
    $html .= "	  </tr>\n";
    $html .= "	</table>\n";
    $html .= "</fieldset>\n";
    $html .= "<table align=\"center\" >\n";
    $html .= "	<tr>\n";
    $html .= "	  <td align=\"center\" >\n";
    $html .= "		  <input  type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\" onclick=\"xajax_IngresarItemsSeleccionados(xajax.getFormValues('oculta'),'".$solicitud."','".$cargo."','".$plan."')\">\n";
    $html .= "		</td>\n";
    $html .= "		<td align=\"center\" >\n";
    $html .= "			<input  type=\"button\" class=\"input-submit\" name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan()\" >\n";
    $html .= "		</td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    
    return $html;
  }
  /**
  *
  */
  function FormaSeleccionarCargos($checks,$solicitud,$cargo,$plan)
  {
 		$html = "";
    $slm = AutoCarga::factory('SolicitudesAutorizacion','','app','UV_SolicitudesAutorizaciones');
    $datos = array();
    $flag = false;
    if($checks['c']) $datos['c'] = $slm->ObtenerCargosDC($plan,$solicitud);
    
    if($checks['a']) $datos['a'] = $slm->ObtenerCargosDA($plan,$solicitud);
    
    if($checks['y']) $datos['y'] = $slm->ObtenerCargosDY($plan,$solicitud);
    
    if($checks['s']) $datos['s'] = $slm->ObtenerCargosSala($plan,$solicitud);
    
    if($checks['m']) $datos['m'] = $slm->ObtenerCargosMateriales($plan,$solicitud);
    
    $i = 0;
    if(!empty($datos['c']))
    {
      $flag = true;
      $html .= "<table width=\"100%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <fieldset class=\"fieldset\">\n";
      $html .= "        <legend class=\"normal_10AN\">CIRUJANO</legend>\n";
      $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "          <tr class=\"formulacion_table_list\">\n";
      $html .= "		        <td width=\"8%\">CARGO</td>\n";
      $html .= "		        <td >DESCRIPCION</td>\n";
      $html .= "		        <td width=\"2%\">OP</td>\n";
      $html .= "		      <tr>\n";
      foreach($datos['c'] as $key => $detalle)
      {
        $html .= "          <tr class=\"modulo_list_claro\">\n";
        $html .= "		        <td >".$detalle['dc_cargo']."</td>\n";
        $html .= "		        <td align=\"justify\">".$detalle['descripcion']."</td>\n";
        $html .= "		        <td >\n";
        $html .= "              <input type=\"checkbox\" name=\"cargos[".$i."][cargo]\" value=\"".$detalle['cargo']."\">\n";
        $html .= "              <input type=\"hidden\" name=\"cargos[".$i."][nivel]\" value=\"".$detalle['nivel_autorizador_id']."\">\n";
        $html .= "              <input type=\"hidden\" name=\"cargos[".($i++)."][cantidad]\" value=\"1\">\n";
        $html .= "            </td>\n";
        $html .= "		      <tr>\n";
      }
      $html .= "        </table>\n";
      $html .= "      </fieldset>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
    }    
    
    if(!empty($datos['a']))
    {
      $flag = true;
      $html .= "<table width=\"100%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <fieldset class=\"fieldset\">\n";
      $html .= "        <legend class=\"normal_10AN\">ANESTESIOLOGO</legend>\n";
      $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "          <tr class=\"formulacion_table_list\">\n";
      $html .= "		        <td width=\"8%\">CARGO</td>\n";
      $html .= "		        <td >DESCRIPCION</td>\n";
      $html .= "		        <td width=\"2%\">OP</td>\n";
      $html .= "		      <tr>\n";
      foreach($datos['a'] as $key => $detalle)
      {
        $html .= "          <tr class=\"modulo_list_claro\">\n";
        $html .= "		        <td >".$detalle['da_cargo']."</td>\n";
        $html .= "		        <td align=\"justify\">".$detalle['descripcion']."</td>\n";
        $html .= "		        <td >\n";
        $html .= "              <input type=\"checkbox\" name=\"cargos[".$i."][cargo]\" value=\"".$detalle['cargo']."\">\n";
        $html .= "              <input type=\"hidden\" name=\"cargos[".$i."][nivel]\" value=\"".$detalle['nivel_autorizador_id']."\">\n";
        $html .= "              <input type=\"hidden\" name=\"cargos[".($i++)."][cantidad]\" value=\"1\">\n";
        $html .= "            </td>\n";
        $html .= "		      <tr>\n";
      }
      $html .= "        </table>\n";
      $html .= "      </fieldset>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
    }    
    
    if(!empty($datos['y']))
    {
      $flag = true;
      $html .= "<table width=\"100%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <fieldset class=\"fieldset\">\n";
      $html .= "        <legend class=\"normal_10AN\">AYUDANTE</legend>\n";
      $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "          <tr class=\"formulacion_table_list\">\n";
      $html .= "		        <td width=\"8%\">CARGO</td>\n";
      $html .= "		        <td >DESCRIPCION</td>\n";
      $html .= "		        <td width=\"2%\">OP</td>\n";
      $html .= "		      <tr>\n";
      foreach($datos['y'] as $key => $detalle)
      {
        $html .= "          <tr class=\"modulo_list_claro\">\n";
        $html .= "		        <td >".$detalle['dy_cargo']."</td>\n";
        $html .= "		        <td align=\"justify\">".$detalle['descripcion']."</td>\n";
        $html .= "		        <td >\n";
        $html .= "              <input type=\"checkbox\" name=\"cargos[".$i."][cargo]\" value=\"".$detalle['cargo']."\">\n";
        $html .= "              <input type=\"hidden\" name=\"cargos[".$i."][nivel]\" value=\"".$detalle['nivel_autorizador_id']."\">\n";
        $html .= "              <input type=\"hidden\" name=\"cargos[".($i++)."][cantidad]\" value=\"1\">\n";
        $html .= "            </td>\n";
        $html .= "		      <tr>\n";
      }
      $html .= "        </table>\n";
      $html .= "      </fieldset>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
    }    
    
    if(!empty($datos['s']))
    {
      $flag = true;
      $html .= "<table width=\"100%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <fieldset class=\"fieldset\">\n";
      $html .= "        <legend class=\"normal_10AN\">DERECHOS DE SALA</legend>\n";
      $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "          <tr class=\"formulacion_table_list\">\n";
      $html .= "		        <td width=\"8%\">CARGO</td>\n";
      $html .= "		        <td >DESCRIPCION</td>\n";
      $html .= "		        <td width=\"2%\">OP</td>\n";
      $html .= "		      <tr>\n";
      foreach($datos['s'] as $key => $detalle)
      {
        $html .= "          <tr class=\"modulo_list_claro\">\n";
        $html .= "		        <td >".$detalle['cargo']."</td>\n";
        $html .= "		        <td align=\"justify\">".$detalle['descripcion']."</td>\n";
        $html .= "		        <td >\n";
        $html .= "              <input type=\"checkbox\" name=\"cargos[".$i."][cargo]\" value=\"".$detalle['cargo']."\">\n";
        $html .= "              <input type=\"hidden\" name=\"cargos[".$i."][nivel]\" value=\"".$detalle['nivel_autorizador_id']."\">\n";
        $html .= "              <input type=\"hidden\" name=\"cargos[".($i++)."][cantidad]\" value=\"1\">\n";
        $html .= "            </td>\n";
        $html .= "		      <tr>\n";
      }
      $html .= "        </table>\n";
      $html .= "      </fieldset>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
    }    
    
    if(!empty($datos['m']))
    {
      $flag = true;
      $html .= "<table width=\"100%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <fieldset class=\"fieldset\">\n";
      $html .= "        <legend class=\"normal_10AN\">DERECHOS DE MATERIALES</legend>\n";
      $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "          <tr class=\"formulacion_table_list\">\n";
      $html .= "		        <td width=\"8%\">CARGO</td>\n";
      $html .= "		        <td >DESCRIPCION</td>\n";
      $html .= "		        <td width=\"2%\">OP</td>\n";
      $html .= "		      <tr>\n";
      foreach($datos['m'] as $key => $detalle)
      {
        $html .= "          <tr class=\"modulo_list_claro\">\n";
        $html .= "		        <td >".$detalle['cargo']."</td>\n";
        $html .= "		        <td align=\"justify\">".$detalle['descripcion']."</td>\n";
        $html .= "		        <td >\n";
        $html .= "              <input type=\"checkbox\" name=\"cargos[".$i."][cargo]\" value=\"".$detalle['cargo']."\">\n";
        $html .= "              <input type=\"hidden\" name=\"cargos[".$i."][nivel]\" value=\"".$detalle['nivel_autorizador_id']."\">\n";
        $html .= "              <input type=\"hidden\" name=\"cargos[".($i++)."][cantidad]\" value=\"1\">\n";
        $html .= "            </td>\n";
        $html .= "		      <tr>\n";
      }
      $html .= "        </table>\n";
      $html .= "      </fieldset>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
    }
    
    if($flag)
    {
      $html .= "<table align=\"center\" >\n";
      $html .= "	<tr>\n";
      $html .= "	  <td align=\"center\" >\n";
      $html .= "		  <input  type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\" onclick=\"xajax_RegistrarCargos(xajax.getFormValues('oculta'),'".$solicitud."','".$cargo."')\">\n";
      $html .= "		</td>\n";
      $html .= "		<td align=\"center\" >\n";
      $html .= "			<input  type=\"button\" class=\"input-submit\" name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan()\" >\n";
      $html .= "		</td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
    }
    else
    {
      $html .= "<table align=\"center\" >\n";
      $html .= "	<tr>\n";
      $html .= "	  <td align=\"center\" class=\"label\">\n";
      $html .= "		  PARA LOS ITEMS DE CIRUGIA SELECCIONADOS NO EXISTE NINGUN CARGO PARAMETRIZADO\n";
      $html .= "		</td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<table align=\"center\" >\n";
      $html .= "	<tr>\n";
      $html .= "		<td align=\"center\" >\n";
      $html .= "			<input  type=\"button\" class=\"input-submit\" name=\"cancelar\" value=\"Aceptar\" onclick=\"OcultarSpan()\" >\n";
      $html .= "		</td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
    }
    return $html;
  }
?>