<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: CuentasXPagar.php,v 1.2 2008/10/10 22:24:17 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  
  /**
  * Funcion que hace la busqueda de un afiliado en la base de datos,
  * para indicar se puede o no registrar una factura
  *
  * @param array $datos Vector con los datos de la identificacion del afiliado
  *
  * @return object
  */
  function BuscarAfiliado($datos)
  {
    $objResponse = new xajaxResponse();
    $afi = AutoCarga::factory("CuentasXPagar", "", "app","UV_CuentasXPagar");
    
    $registros = $afi->ObtenerAfiliado($datos['afiliado_tipo_id'],$datos['afiliado_id']);

    if(empty($registros))
    {
      $html = "EL PACIENTE IDENFICADO CON ".$datos['afiliado_tipo_id']." ".$datos['afiliado_id'].", NO SE ENCUENTRA REGISTRADO EN EL SISTEMA";
      $objResponse->assign("error","innerHTML",$html);
    }
    else
    {
      $objResponse->assign("error","innerHTML","");
      $objResponse->call("ContinuarRegistroFactura");
    }
    return $objResponse;
  }
  /**
  * Funcion donde se evalua la factura para solicitar datos determinados para
  * la consulta de la misma
  *
  * @param string $factura cadena que contiene el numero de factura
  * @param string $proveedor Codigo del proveedor
  *
  * @return object
  */
  function SolicitarValidacion($factura,$proveedor)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory("CuentasXPagar", "", "app","UV_CuentasXPagar");
    $validacion = $cxp->ObtenerValidacionRips($proveedor);
    
    $c = $t = "";
    if($validacion == "C") 
      $c = "checked"; 
    else if($validacion == "T") 
      $t = "checked"; 
    
    $html  = "<table width=\"90%\" align=\"center\" class=\"label\">\n"; 
    $html .= "  <tr>\n"; 
    $html .= "    <td colspan=\"2\" align=\"center\" class=\"normal_10AN\">\n";
    $html .= "      PARA LA FACTURA ".$factura." , SE SOLICITA INDICAR LA FORMA EN LA QUE SE VALIDARA EL DETALLE DE LOS RIPS\n"; 
    $html .= "    </td>\n"; 
    $html .= "  </tr>\n"; 
    $html .= "  <tr>\n"; 
    $html .= "    <td>\n"; 
    $html .= "      <input type=\"radio\" name=\"validacion\" value=\"C\" $c>POR CARGO CUPS\n"; 
    $html .= "    </td>\n";     
    $html .= "    <td>\n"; 
    $html .= "      <input type=\"radio\" name=\"validacion\" value=\"T\" $t>POR CARGO TARIFARIO\n"; 
    $html .= "    </td>\n"; 
    $html .= "  </tr>\n"; 
    $html .= "  <tr>\n";
		$html .= "		<td colspan=\"2\" align=\"center\">\n";
		$html .= "			<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Aceptar\">\n";
		$html .= "			<input type=\"hidden\" name=\"actualizar\" value=\"".$validacion."\">\n";
		$html .= "			<input type=\"hidden\" name=\"proveedor\" value=\"".$proveedor."\">\n";
		$html .= "		</td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n"; 
    $objResponse->assign("ventana","innerHTML",$html);
    $objResponse->call("MostrarSpan");
    
    return $objResponse;
  }
  /**
  * Funcion en la que se ingresa la parametrizacion de la validacion de rips
  *
  * @param array $form Arreglo con loa datos de la forma
  *
  * @return object
  */
  function IngresarValidacion($form)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory("CuentasXPagar", "", "app","UV_CuentasXPagar");
    
    $rst = "";
    if(!$form['actualizar'])
      $rst = $cxp->IngresarValidacionRips($form['proveedor'],$form['validacion']);
    else
      $rst = $cxp->ActualizarValidacionRips($form['proveedor'],$form['validacion']);
    
    if(!$rst)
    {
      $objResponse->alert($cxp->ErrMsg());
    }
    else
    {
      $objResponse->call("Continuar");
    }
    return $objResponse;
  }  
  /**
  * Funcion en la que se asocia una orden de servicio con una cuenta
  *
  * @param string $orden Identificador de la orden de servicio que asociara
  * @param string $prefijo Prefijo del documento
  * @param string $numero Numero del documento 
  *
  * @return object
  */
  function AsociarCXP($orden,$prefijo,$numero)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory("Ordenes", "", "app","UV_CuentasXPagar");

    $empresa = SessionGetVar("EmpresasCuentas");
    $rst = $cxp->RegistrarAsociacionOrden($orden,$empresa['empresa'],$prefijo,$numero);
    
    if(!$rst)
    {
      $objResponse->alert($cxp->ErrMsg());
    }
    else
    {
      $html  = " 				  <a href=\"javascript:DesvincularCXP('".$orden."')\" class=\"label_error\"  title=\"DESVINCULAR ORDEN DE LA CUENTA\">\n";
      $html .= "            <img src=\"".GetThemePath()."/images/checksi.png\" border='0'>\n";
      $html .= " 				  </a>\n";
      $objResponse->assign("divorden_".$orden,"innerHTML",$html);
    }
    return $objResponse;
  }  
  /**
  * Funcion en la que se desvincula una orden de servicio con una cuenta
  * 
  * @param string $orden Identificador de la orden de servicio
  * @param string $prefijo Prefijo del documento
  * @param string $numero Numero del documento 
  *
  * @return object
  */
  function DesvincularCXP($orden,$prefijo,$numero)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory("Ordenes", "", "app","UV_CuentasXPagar");
    $empresa = SessionGetVar("EmpresasCuentas");
    $rst = $cxp->RegistrarDesvinculacionOrden($orden,$empresa['empresa'],$prefijo,$numero);
    
    if(!$rst)
    {
      $objResponse->alert($cxp->ErrMsg());
    }
    else
    {
      $html  = " 				  <a href=\"javascript:AsociarCXP('".$orden."')\" class=\"label_error\"  title=\"ASOCIAR ORDEN CON LA CUENTA\">\n";
      $html .= "            <img src=\"".GetThemePath()."/images/checkno.png\" border='0'>\n";
      $html .= " 				  </a>\n";
      $objResponse->assign("divorden_".$orden,"innerHTML",$html);
    }
    return $objResponse;
  }
  /**
  * Funcion que permite solicitar la informacion de la objeccion del detalle
  *
  * @param string $cxp_detalle_factura_id Identificar del detalle de la factura
  * @param string $referencia Identificador del tipo de detalle
  * @param string $descripcion Descripcion del detalle
  * @param string $valor Valor del detalle de la factura
  * @param string $tipodetalle identificar del tipo de detalle
  *
  * @return object
  */
  function Objetar($cxp_detalle_factura_id,$referencia,$descripcion,$valor,$tipo_detalle)
  {
    $msj = "OBJETAR EL COBRO DEL CARGO ".$referencia;
    if($tipo_detalle == "M")
      $msj = "OBJETAR EL COBRO DEL MEDICAMENTO ".$referencia;
    else if($tipo_detalle == "O")
      $msj = "OBJETAR EL COBRO DE OTRO SERVICIO , REFERENCIA ".$referencia;
      
    $objResponse = new xajaxResponse();

    $html .= "<input type=\"hidden\" name=\"descripcion\" value=\"".$descripcion."\" >\n";
    $html .= "<input type=\"hidden\" name=\"referencia\" value=\"".$referencia."\" >\n";
    $html .= "<input type=\"hidden\" name=\"tipo_detalle\" value=\"".$tipo_detalle."\" >\n";
    $html .= "<table width=\"98%\" align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td>\n";
    $html .= "      <fieldset class=\"fieldset\">\n";
    $html .= "        <legend class=\"normal_10AN\">".$msj."</LEGEND>\n";
    $html .= "			    <table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
    if($descripcion)
    {
      $html .= "			    <tr class=\"formulacion_table_list\">\n";
      $html .= "			      <td colspan=\"4\">DESCRIPCION</td>\n";
      $html .= "			    </tr>\n";
      $html .= "			    <tr class=\"modulo_list_claro\">\n";
      $html .= "			      <td align=\"justify\" colspan=\"4\">".$descripcion."</td>\n";
      $html .= "			    </tr>\n";
    }
    $html .= "          <tr class=\"formulacion_table_list\">\n"; 
    $html .= "            <td align=\"left\" width=\"25%\">* VALOR</td>\n"; 
    $html .= "            <td align=\"right\" width=\"25%\" class=\"modulo_list_claro\">$".formatoValor($valor)." </td>\n"; 
    $html .= "            <td width=\"2%\" class=\"modulo_list_claro\">\n";
    $html .= "              <img style=\"cursor:pointer\" title=\"PASAR VALOR\" src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" onclick=\"PasarValor(document.oculta)\">\n";
    $html .= "            </td>\n"; 
    $html .= "            <td align=\"left\"  class=\"modulo_list_claro\">\n";
    $html .= "              <input style=\"width:60%\"  type=\"text\" name=\"valor_total\" class=\"input-text\" onkeypress=\"return acceptNum(event)\">\n";
    $html .= "            </td>\n"; 
    $html .= "          </tr>\n"; 
    $html .= "			    <tr class=\"formulacion_table_list\" >\n";
    $html .= "			      <td colspan=\"4\">OBSERVACION</td>\n";
    $html .= "			    </tr>\n";
    $html .= "			    <tr class=\"formulacion_table_list\">\n";
    $html .= "			      <td colspan=\"4\">\n";
    $html .= "              <textarea class=\"textarea\" name=\"observacion\" style=\"width:100%\" rows=\"3\">".$informacion['observacion']."</textarea>\n";
    $html .= "            </td>\n";
    $html .= "			    </tr>\n";
    $html .= "			  </table >\n";
    $html .= "			  <div id=\"error\" class=\"label_error\" style=\"text-align:center\"><br></div>\n";
    $html .= "	      <table width=\"100%\" align=\"center\">\n";
    $html .= "	        <tr>\n";
    $html .= "			      <td align='center'>\n";
    $html .= "			        <input type=\"hidden\" name=\"cxp_detalle_factura_id\" value=\"".$cxp_detalle_factura_id."\" >\n";
    $html .= "			        <input type=\"hidden\" name=\"valor1\" value=\"".$valor."\" >\n";
    $html .= "			        <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
    $html .= "		        </td>\n";
    $html .= "		        <td align=\"center\">\n";
    $html .= "				      <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan('Contenedor')\">\n";
    $html .= "		        </td>\n";
    $html .= "          </tr>\n";
    $html .= "        </table>\n";
    $html .= "      </fieldset>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    
    $objResponse->assign("ventana","innerHTML",$html);
    $objResponse->assign("glosas","innerHTML","");
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }  
  /**
  * Funcion que
  *
  * @param string $cxp_detalle_factura_id Identificar del detalle de la factura
  * @param string $referencia Identificador del tipo de detalle
  * @param string $descripcion Descripcion del detalle
  * @param string $valor Valor del detalle de la factura
  * @param string $tipodetalle identificar del tipo de detalle
  *
  * @return object
  */
  function ModificarObjecion($cxp_detalle_factura_id,$referencia,$descripcion,$valor,$tipo_detalle)
  {
    $msj = "MODIFICAR OBJECION DEL COBRO DEL CARGO ".$referencia;
    if($tipo_detalle == "M")
      $msj = "MODIFICAR OBJECION DEL COBRO DEL MEDICAMENTO ".$referencia;
    else if($tipo_detalle == "O")
      $msj = "MODIFICAR OBJECION DEL COBRO DE OTRO SERVICIO , REFERENCIA ".$referencia;
      
    $objResponse = new xajaxResponse();
    
    $html1 = "";
    $cxp = Autocarga::factory("CuentasXPagar","","app","Uv_CuentasXPagar");
    $datos = $cxp->ObtenerInformacionGlosaDetalle($cxp_detalle_factura_id);
    $informacion = $datos[UserGetUID()];
    
    unset($datos[UserGetUID()]);
    if(!empty($datos))
    {
      $html1 .= "<fieldset class=\"fieldset\">\n";
      $html1 .= "	<legend class=\"normsl_10AN\">OBJECCIONES PRESENTES SOBRE EL DETALLE</legend>\n";
      $html1 .= "	<table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";		
      $html1 .= "		<tr class=\"modulo_table_list_title\" height=\"17\">\n";
      $html1 .= "			<td width=\"10%\" >VALOR</b></td>\n";
      $html1 .= "			<td width=\"%\">OBESERVACION</b></td>\n";
      $html1 .= "			<td width=\"25%\">REGISTRADO POR</td>\n";
      $html1 .= "			<td width=\"10%\">FECHA</td>\n";
      $html1 .= "		</tr>\n";
      
      foreach($datos as $key => $detalle)
      {          
        $html1 .= "		<tr>\n";
        $html1 .= "			<td align=\"right\">$".formatoValor($detalle['valor'])."</td>\n";
        $html1 .= "			<td align=\"justify\">".$detalle['observacion']."</td>\n";
        $html1 .= "			<td >".$detalle['nombre']."</td>\n";
        $html1 .= "			<td >".$detalle['fecha_registro']."</td>\n";
        $html1 .= "		</tr>\n";
      }
      $html1 .= "	</table>\n";
      $html1 .= "</fieldset><br>\n";
      
      $html1 = utf8_encode($html1);
    }
    
    $html .= "<input type=\"hidden\" name=\"descripcion\" value=\"".$descripcion."\" >\n";
    $html .= "<input type=\"hidden\" name=\"referencia\" value=\"".$referencia."\" >\n";
    $html .= "<input type=\"hidden\" name=\"tipo_detalle\" value=\"".$tipo_detalle."\" >\n";
    $html .= "<table width=\"100%\" align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td>\n";
    $html .= "      <fieldset class=\"fieldset\">\n";
    $html .= "        <legend class=\"normal_10AN\">".$msj."</LEGEND>\n";
    $html .= "			    <table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
    if($descripcion)
    {
      $html .= "			    <tr class=\"formulacion_table_list\">\n";
      $html .= "			      <td colspan=\"4\">DESCRIPCION</td>\n";
      $html .= "			    </tr>\n";
      $html .= "			    <tr class=\"modulo_list_claro\">\n";
      $html .= "			      <td align=\"justify\" colspan=\"4\">".$descripcion."</td>\n";
      $html .= "			    </tr>\n";
    }
    $html .= "          <tr class=\"formulacion_table_list\">\n"; 
    $html .= "            <td align=\"left\" width=\"25%\">* VALOR</td>\n"; 
    $html .= "            <td align=\"right\" width=\"25%\" class=\"modulo_list_claro\">$".formatoValor($valor)." </td>\n"; 
    $html .= "            <td width=\"2%\" class=\"modulo_list_claro\">\n";
    $html .= "              <img style=\"cursor:pointer\" title=\"PASAR VALOR\" src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" onclick=\"PasarValor(document.oculta)\">\n";
    $html .= "            </td>\n"; 
    $html .= "            <td align=\"left\"  class=\"modulo_list_claro\">\n";
    $html .= "              <input style=\"width:60%\"  type=\"text\" name=\"valor_total\" class=\"input-text\" onkeypress=\"return acceptNum(event)\" value=\"".$informacion['valor']."\">\n";
    $html .= "            </td>\n"; 
    $html .= "          </tr>\n"; 
    $html .= "			    <tr class=\"formulacion_table_list\" >\n";
    $html .= "			      <td colspan=\"4\">OBSERVACION</td>\n";
    $html .= "			    </tr>\n";
    $html .= "			    <tr class=\"formulacion_table_list\">\n";
    $html .= "			      <td colspan=\"4\">\n";
    $html .= "              <textarea class=\"textarea\" name=\"observacion\" style=\"width:100%\" rows=\"3\">".$informacion['observacion']."</textarea>\n";
    $html .= "            </td>\n";
    $html .= "			    </tr>\n";
    $html .= "			  </table >\n";
    $html .= "			  <div id=\"error\" class=\"label_error\" style=\"text-align:center\"><br></div>\n";
    $html .= "	      <table width=\"100%\" align=\"center\">\n";
    $html .= "	        <tr>\n";
    $html .= "			      <td align='center'>\n";
    $html .= "			        <input type=\"hidden\" name=\"cxp_detalle_factura_id\" value=\"".$cxp_detalle_factura_id."\" >\n";
    $html .= "			        <input type=\"hidden\" name=\"valor1\" value=\"".$valor."\" >\n";
    $html .= "			        <input type=\"hidden\" name=\"cxp_glosa_id\" value=\"".$informacion['cxp_glosa_id']."\" >\n";
    $html .= "			        <input type=\"hidden\" name=\"cxp_glosa_detalle_observacion_id\" value=\"".$informacion['cxp_glosa_detalle_observacion_id']."\" >\n";
    $html .= "			        <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
    $html .= "		        </td>\n";
    $html .= "		        <td align=\"center\">\n";
    $html .= "				      <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan('Contenedor')\">\n";
    $html .= "		        </td>\n";
    $html .= "          </tr>\n";
    $html .= "        </table>\n";
    $html .= "      </fieldset>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    $html = utf8_encode($html);
    $objResponse->assign("ventana","innerHTML",$html);
    $objResponse->assign("glosas","innerHTML",$html1);
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  /**
  * Funcion en la que se registra la objeccion sobre el detalle de la cuenta
  *
  * @param array $form Arreglo con loa datos de la forma
  *
  * @return object
  */
  function RegistrarObjeccion($form)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory("CuentasXPagar", "", "app","UV_CuentasXPagar");
    $form['observacion'] = utf8_decode($form['observacion']);
    
    $rst = "";
    $tipoauditor = SessionGetVar("TipoRevision");
    $mensaje = "LA OBJECION SOBRE EL DETALLE DE FACTURA SE HA REGISTRADO";
    $detalle = $cxp->ObtenerNumerosGlosa($form['cxp_detalle_factura_id']);
    
    if(is_numeric($form['cxp_glosa_id']))
    {
      $detalle['cxp_glosa_id'] = $form['cxp_glosa_id'];
      $detalle['cxp_glosa_detalle_observacion_id'] = $form['cxp_glosa_detalle_observacion_id'];
      $mensaje = "LA MODIFICACION DE LA OBJECION SOBRE EL DETALLE DE FACTURA SE HA REGISTRADO";
    }
    
    $rst = $cxp->RegistrarObjeccion($detalle,$form,$tipoauditor);
    
    $class = "normal_10AN";
    
    if(!$rst)
    {
      $class = "label_error";
      $mensaje = $cxp->ErrMsg();
    }
    
    $html .= "<table width=\"98%\" align=\"center\">\n";
    $html .= "  <tr class=\"".$class."\">\n";
    $html .= "	  <td align=\"justify\">".$mensaje."</td>\n";
    $html .= "	</tr>\n";
    $html .= "</table >\n";
    $html .= "<table width=\"100%\" align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "	  <td align=\"center\">\n";
    $html .= "		  <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Aceptar\" onclick=\"OcultarSpan('Contenedor')\">\n";
    $html .= "	  </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    
    $objResponse->assign("ventana","innerHTML",$html);
    $objResponse->assign("glosas","innerHTML","");
    if($rst)
    {
      if(!is_numeric($form['cxp_glosa_id']))
      {
        $msj = "";
        switch($form['tipo_detalle'])
        {
          case 'C': $msj = "MODIFICAR OBJECION DEL CARGO"; break;
          case 'M': $msj = "MODIFICAR OBJECION DEL MEDICAMENTO"; break;
          case 'O': $msj = "MODIFICAR OBJECION DE OTRO SERVICIO"; break;
        }
         
        $html  = "				<a href=\"javascript:ModificarObjecion('".$form['cxp_detalle_factura_id']."','".$form['referencia']."','".$form['descripcion']."','".$form['valor1']."','".$form['tipo_detalle']."')\" title=\"".$msj."\">\n";
        $html .= "					<img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\">\n";
        $html .= "				</a>\n";	
        $objResponse->assign("objeccion".$form['cxp_detalle_factura_id'],"innerHTML",$html);
      }
    }
    return $objResponse;
  }    
  /**
  * Funcion en la que se registra la objeccion sobre el detalle de la cuenta
  *
  * @param array $form Arreglo con loa datos de la forma
  * @param string $prefijo Prefijo del documento
  * @param string $numero Numero del documento 
  *
  * @return object
  */
  function RegistrarObjeccionT($form,$prefijo,$numero)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory("CuentasXPagar", "", "app","UV_CuentasXPagar");
    
    $empresa = SessionGetVar("EmpresasCuentas");
    $tipoauditor = SessionGetVar("TipoRevision");
    $detalle = $cxp->ObtenerNumeroGlosa($prefijo,$numero,$empresa['empresa']);
    
    $form['observacion'] = utf8_decode($form['observacion']);
    $rst = $cxp->RegistrarObjeccionCuenta($detalle,$form,$tipoauditor);
    
    $class = "normal_10AN";
    $mensaje = "LA OBJECCION SOBRE LA CUENTA SE HA REGISTRADO";
    if(!$rst)
    {
      $class = "label_error";
      $mensaje = $cxp->ErrMsg();
    }
    
    $html .= "<table width=\"98%\" align=\"center\">\n";
    $html .= "  <tr class=\"".$class."\">\n";
    $html .= "	  <td align=\"justify\">".$mensaje."</td>\n";
    $html .= "	</tr>\n";
    $html .= "</table >\n";
    $html .= "<table width=\"100%\" align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "	  <td align=\"center\">\n";
    $html .= "		  <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Aceptar\" onclick=\"OcultarSpan('Contenedor')\">\n";
    $html .= "	  </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    
    $factura['prefijo'] = $prefijo;
    $factura['numero'] = $numero;
    $factura['empresa_id'] = $empresa['empresa'];
    
    $glosas = $cxp->ObtenerInformacionGlosa($factura);
    $objResponse->assign("ventana","innerHTML",$html);
    $objResponse->assign("glosas","innerHTML","");
    $objResponse->assign("errort","innerHTML","<br>");
    $objResponse->assign("cxp_glosa_observacion_id","value",$glosas[UserGetUID()]['cxp_glosa_observacion_id']);
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }  
  /**
  * Funcion que permite asociar un cargo de la orden de servicio con un detalle de 
  * la factura
  *
  * @param string $orden Identificador de la orden de servicio que asociara
  * @param string $orden_cargo Identificador del cargo de la orden de servicio
  * @param string $cups Identificador del cargo cups de la orden de servicio
  * @param string $valor valor del cargo en la orden de servicio
  * @param string $prefijo Prefijo del documento
  * @param string $numero Numero del documento 
  *
  * @return object
  */
  function AsociarDetalleCargo($orden,$orden_cargo,$cups,$valor,$prefijo,$numero)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory("Ordenes", "", "app","UV_CuentasXPagar");
    
    $empresa = SessionGetVar("EmpresasCuentas");
    $cargos = $cxp->ObtenerCargosFactura($empresa['empresa'],$prefijo,$numero,$cups);
    $ventana = "MostrarSpan";
    
    $html = "";
    if(empty($cargos))
    {
      $html .= "<center>\n ";
      $html .= " <label class=\"label_error\">NO EXISTEN CARGOS EN EL DETALLE DE LA CUENTA CON EL MISMO CARGO CUPS DE LA ORDEN</label>\n";
      $html .= "</center>\n ";
      $html .= "<table width=\"100%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "	  <td align=\"center\">\n";
      $html .= "		  <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Aceptar\" onclick=\"OcultarSpan('Contenedor')\">\n";
      $html .= "	  </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
    }
    else
    {
      $html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
      $html .= "		<tr class=\"modulo_table_list_title\" height=\"17\">\n";
      $html .= "			<td width=\"8%\">CODIGO</b></td>\n";
      $html .= "			<td width=\"%\">DESCRIPCIÓN</b></td>\n";
      $html .= "			<td width=\"36%\">OBSERVACIÓN</b></td>\n";
      $html .= "			<td width=\"4%\">CANT</td>\n";
      $html .= "			<td width=\"7%\">UNITARIO</td>\n";
      $html .= "			<td width=\"7%\">TOTAL</td>\n";
      $html .= "			<td width=\"1%\"></td>\n";
      $html .= "		</tr>\n";
      
      foreach($cargos as $key => $detalle)
      {
        ($bck == "#DDDDDD")? $bck = "#CCCCCC":$bck = "#DDDDDD";
        ($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro";
                
        $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
        $html .= "			<td >".$detalle['referencia']."</td>\n";
        $html .= "			<td >".$detalle['descripcion']."</td>\n";
        if($valor == $detalle['valor_unitario'])
          $html .= "			<td class=\"normal_10AN\" >EL VALOR REFERENCIADO COINCIDE CON EL VALOR DE LA ORDEN DE SERVICIO</td>\n";
        else
          $html .= "			<td class=\"label_error\">EL VALOR REFERENCIADO NO COINCIDE CON EL VALOR DE LA ORDEN DE SERVICIO. $".formatoValor($valor)."</td>\n";
        
        $html .= "			<td align=\"right\">".$detalle['cantidad']."</td>\n";
        $html .= "			<td align=\"right\">".formatoValor($detalle['valor_unitario'])."</td>\n";
        $html .= "			<td align=\"right\">".formatoValor($detalle['valor_total'])."</td>\n";
        $html .= "      <td >\n";
        $html .= "				<a href=\"javascript:VincularDetalle('".$detalle['cxp_detalle_factura_id']."','".$orden."','".$orden_cargo."','".$detalle['referencia']."','".$valor."','".$detalle['valor_unitario']."')\" title=\"VINCULAR\">\n";
        $html .= "					<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\">\n";
        $html .= "				</a>\n";
        $html .= "      </td>\n";
        $html .= "		</tr>\n";
      }
      $html .= "	</table>\n";
      $html .= "  <table width=\"100%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "	  <td align=\"center\">\n";
      $html .= "		  <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cerrar\" onclick=\"OcultarSpan('Contenedor')\">\n";
      $html .= "	  </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $ventana = "MostrarSpanGrande";
    }
    
    $html = utf8_encode($html);
    $objResponse->assign("ventana","innerHTML",$html);
    $objResponse->call($ventana);
      
    return $objResponse;
  }
  /**
  * Funcion para hacer el vinculo del cargo de la orden de servicios con el
  * detalle de la cuenta por pagar
  *
  * @param string $cxp_detalle_id Identificar del detalle de la factura
  * @param string $orden Identificador de la orden de servicio que asociara
  * @param string $orden_cargo Identificador del cargo de la orden de servicio
  * @param string $cargo Identificador del cargo a ser asociado
  * @param string $valor1 valor del cargo en la orden de servicio
  * @param string $valor2 valor del cargo en el detalle de la factura
  * 
  * @return object
  */
  function VincularDetalle($cxp_detalle_id,$orden,$orden_cargo,$cargo,$valor1,$valor2)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory("Ordenes", "", "app","UV_CuentasXPagar");
    $rst = $cxp->RegistrarVinculoDetalleCargo($cxp_detalle_id,$orden,$orden_cargo,$valor1);
    
    if(!$rst)
    {
      $html .= "<table width=\"98%\" align=\"center\">\n";
      $html .= "  <tr class=\"label_error\">\n";
      $html .= "	  <td align=\"justify\">".$cxp->ErrMsg()."</td>\n";
      $html .= "	</tr>\n";
      $html .= "</table >\n";
      $html .= "<table width=\"100%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "	  <td align=\"center\">\n";
      $html .= "		  <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Aceptar\" onclick=\"OcultarSpan('Contenedor')\">\n";
      $html .= "	  </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $objResponse->call("OcultarSpan");
      $objResponse->assign("ventana","innerHTML",$html);
      $objResponse->call("MostrarSpan");
    }
    else
    {
      if($valor1 != $valor2)
      {
        $vl = "EL VALOR DEL CARGO IDENTIFICADO CON ".$cargo.", NO COINCIDE CON EL VALOR ($".formatoValor($valor1).") DE LA ORDEN DE SERVICO.";
        $ms = "<img src=\"".GetThemePath()."/images/infor.png\" onclick=\"alert(document.getElementById('hdd_".$cxp_detalle_id."').value)\">\n";
    
        $objResponse->script("document.getElementById('hdd_".$cxp_detalle_id."').value =  document.getElementById('hdd_".$cxp_detalle_id."').value + '\\n".$vl."'");
        $objResponse->assign("dtl_".$cxp_detalle_id."","innerHTML",$ms);
      }
      
      $html  = " 				  <a href=\"javascript:DesvincularDetalleCargo('".$orden."','".$orden_cargo."','".$cxp_detalle_id."','".$cargo."','".$valor1."',document.getElementById('hdd_".$cxp_detalle_id."').value)\" class=\"label_error\"  title=\"DESVINCULAR ORDEN DE LA CUENTA\">\n";
      $html .= "            <img src=\"".GetThemePath()."/images/checksi.png\" border='0'>\n";
      $html .= " 				  </a>\n";  
      $objResponse->assign("divcrg_".$orden_cargo,"innerHTML",$html);
      $objResponse->call("OcultarSpan");
    }
    return $objResponse;
  }  
  /**
  * Funcion en la que se desvincula un detalle de una orden de servicio del 
  * detalle de la cuenta por pagar
  * 
  * @param string $orden Identificador de la orden de servicio
  * @param string $orden_cargo Identificador del detalle de la orden de servicio
  * @param string $cxp_detalle_id Identificador del detalle de la factura
  * @param string $cargo Cargo asociado
  * @param string $valor Valor del detalle de la orden de servicio
  * @param string $detalle Cadena que contiene las alertas
  *
  * @return object
  */
  function DesvincularDetalle($orden,$orden_cargo,$cxp_detalle_id,$cargo,$valor,$detalle)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory("Ordenes", "", "app","UV_CuentasXPagar");
    
    $rst = $cxp->RegistrarDesvinculacionDetalleCargo($cxp_detalle_id,$orden,$orden_cargo);
    
    if(!$rst)
    {
      $html .= "<table width=\"98%\" align=\"center\">\n";
      $html .= "  <tr class=\"label_error\">\n";
      $html .= "	  <td align=\"justify\">".$cxp->ErrMsg()."</td>\n";
      $html .= "	</tr>\n";
      $html .= "</table >\n";
      $html .= "<table width=\"100%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "	  <td align=\"center\">\n";
      $html .= "		  <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Aceptar\" onclick=\"OcultarSpan('Contenedor')\">\n";
      $html .= "	  </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $objResponse->assign("ventana","innerHTML",$html);
      $objResponse->call("MostrarSpan");
    }
    else
    {
      $vl = "EL VALOR DEL CARGO IDENTIFICADO CON ".$cargo.", NO COINCIDE CON EL VALOR ($".formatoValor($valor).") DE LA ORDEN DE SERVICO.";
      $xg = str_replace($vl,"",$detalle);
      
      if(trim($detalle) == "")
        $objResponse->assign("dtl_".$cxp_detalle_id."","innerHTML","");
      else
      {
        $ms = "<img src=\"".GetThemePath()."/images/infor.png\" onclick=\"alert(document.getElementById('hdd_".$cxp_detalle_id."').value)\">\n";
    
        $objResponse->script("document.getElementById('hdd_".$cxp_detalle_id."').value = ".$xg."'");
        $objResponse->assign("dtl_".$cxp_detalle_id."","innerHTML",$ms);
      }
      
      $html  = " 				  <a href=\"javascript:AsociarDetalleCargo('".$orden."','".$orden_cargo."','".$cargo."','".$valor."')\" class=\"label_error\"  title=\"ASOCIAR ORDEN CON LA CUENTA\">\n";
      $html .= "            <img src=\"".GetThemePath()."/images/checkno.png\" border='0'>\n";
      $html .= " 				  </a>\n";
      $objResponse->assign("divcrg_".$orden_cargo,"innerHTML",$html);
    }
    return $objResponse;
  }  
  /**
  * Funcion que permite asociar un cargo de la orden de servicio con un detalle de 
  * la factura
  *
  * @param string $orden Identificador de la orden de servicio que asociara
  * @param string $orden_medicamento Identificador del medicamento de la orden de servicio
  * @param string $codigo Identificador del medicamento de la orden de servicio
  * @param string $valor valor del cargo en la orden de servicio
  * @param string $prefijo Prefijo del documento
  * @param string $numero Numero del documento 
  *
  * @return object
  */
  function AsociarDetalleMedicamento($orden,$orden_medicamento,$codigo,$valor,$prefijo,$numero)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory("Ordenes", "", "app","UV_CuentasXPagar");
    
    $empresa = SessionGetVar("EmpresasCuentas");
    $medica = $cxp->ObtenerMedicamentosFactura($empresa['empresa'],$prefijo,$numero,$codigo);
    $ventana = "MostrarSpan";
    
    $html = "";
    if(empty($medica))
    {
      $html .= "<center>\n ";
      $html .= " <label class=\"label_error\">NO EXISTEN MEDICAMENTOS EN EL DETALLE DE LA CUENTA CON EL MISMO CODIGO DE MEDICAMENTO DE LA ORDEN</label>\n";
      $html .= "</center>\n ";
      $html .= "<table width=\"100%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "	  <td align=\"center\">\n";
      $html .= "		  <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Aceptar\" onclick=\"OcultarSpan('Contenedor')\">\n";
      $html .= "	  </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
    }
    else
    {
      $html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
      $html .= "		<tr class=\"modulo_table_list_title\" height=\"17\">\n";
      $html .= "			<td width=\"8%\">CODIGO</b></td>\n";
      $html .= "			<td width=\"%\">DESCRIPCIÓN</b></td>\n";
      $html .= "			<td width=\"36%\">OBSERVACIÓN</b></td>\n";
      $html .= "			<td width=\"4%\">CANT</td>\n";
      $html .= "			<td width=\"7%\">UNITARIO</td>\n";
      $html .= "			<td width=\"7%\">TOTAL</td>\n";
      $html .= "			<td width=\"1%\"></td>\n";
      $html .= "		</tr>\n";
      
      foreach($medica as $key => $detalle)
      {
        ($bck == "#DDDDDD")? $bck = "#CCCCCC":$bck = "#DDDDDD";
        ($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro";
                
        $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
        $html .= "			<td >".$detalle['referencia']."</td>\n";
        $html .= "			<td >".$detalle['descripcion']."</td>\n";
        if($valor == $detalle['valor_unitario'])
          $html .= "			<td class=\"normal_10AN\" >EL VALOR REFERENCIADO COINCIDE CON EL VALOR DE LA ORDEN DE SERVICIO</td>\n";
        else
          $html .= "			<td class=\"label_error\">EL VALOR REFERENCIADO NO COINCIDE CON EL VALOR DE LA ORDEN DE SERVICIO. $".formatoValor($valor)."</td>\n";
        
        $html .= "			<td align=\"right\">".$detalle['cantidad']."</td>\n";
        $html .= "			<td align=\"right\">".formatoValor($detalle['valor_unitario'])."</td>\n";
        $html .= "			<td align=\"right\">".formatoValor($detalle['valor_total'])."</td>\n";
        $html .= "      <td >\n";
        $html .= "				<a href=\"javascript:VincularDetalleM('".$detalle['cxp_detalle_factura_id']."','".$orden."','".$orden_medicamento."','".$detalle['referencia']."','".$valor."','".$detalle['valor_unitario']."','".$codigo."')\" title=\"VINCULAR\">\n";
        $html .= "					<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\">\n";
        $html .= "				</a>\n";
        $html .= "      </td>\n";
        $html .= "		</tr>\n";
      }
      $html .= "	</table>\n";
      $html .= "  <table width=\"100%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "	  <td align=\"center\">\n";
      $html .= "		  <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cerrar\" onclick=\"OcultarSpan('Contenedor')\">\n";
      $html .= "	  </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $ventana = "MostrarSpanGrande";
    }
    
    $html = utf8_encode($html);
    $objResponse->assign("ventana","innerHTML",$html);
    $objResponse->call($ventana);
      
    return $objResponse;
  }
  /**
  * Funcion para hacer el vinculo del medicamento de la orden de servicios con el
  * detalle de la cuenta por pagar
  *
  * @param string $cxp_detalle_id Identificar del detalle de la factura
  * @param string $orden Identificador de la orden de servicio que asociara
  * @param string $orden_medicamento Identificador del medicamento de la orden de servicio
  * @param string $codigo Identificador del medicamento a ser asociado
  * @param string $valor1 valor del cargo en la orden de servicio
  * @param string $valor2 valor del cargo en el detalle de la factura
  * @param string $medicamento Codigo del medicamento
  * 
  * @return object
  */
  function VincularDetalleM($cxp_detalle_id,$orden,$orden_medicamento,$codigo,$valor1,$valor2,$medicamento)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory("Ordenes", "", "app","UV_CuentasXPagar");
    $rst = $cxp->RegistrarVinculoDetalleMedicamento($cxp_detalle_id,$orden,$orden_medicamento,$valor1);
    
    if(!$rst)
    {
      $html .= "<table width=\"98%\" align=\"center\">\n";
      $html .= "  <tr class=\"label_error\">\n";
      $html .= "	  <td align=\"justify\">".$cxp->ErrMsg()."</td>\n";
      $html .= "	</tr>\n";
      $html .= "</table >\n";
      $html .= "<table width=\"100%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "	  <td align=\"center\">\n";
      $html .= "		  <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Aceptar\" onclick=\"OcultarSpan('Contenedor')\">\n";
      $html .= "	  </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $objResponse->call("OcultarSpan");
      $objResponse->assign("ventana","innerHTML",$html);
      $objResponse->call("MostrarSpan");
    }
    else
    {
      if($valor1 != $valor2)
      {
        $vl = "EL VALOR DEL MEDICAMENTO IDENTIFICADO CON ".$codigo.", NO COINCIDE CON EL VALOR ($".formatoValor($valor1).") DE LA ORDEN DE SERVICO.";
        $ms = "<img src=\"".GetThemePath()."/images/infor.png\" onclick=\"alert(document.getElementById('hdd_".$cxp_detalle_id."').value)\">\n";
    
        $objResponse->script("document.getElementById('hdd_".$cxp_detalle_id."').value =  document.getElementById('hdd_".$cxp_detalle_id."').value + '\\n".$vl."'");
        $objResponse->assign("dtl_".$cxp_detalle_id."","innerHTML",$ms);
      }
      
      $html  = " 				  <a href=\"javascript:DesvincularDetalleMedicamento('".$orden."','".$orden_medicamento."','".$cxp_detalle_id."','".$codigo."','".$valor1."',document.getElementById('hdd_".$cxp_detalle_id."','".$medicamento."').value)\" class=\"label_error\"  title=\"DESVINCULAR ORDEN DE LA CUENTA\">\n";
      $html .= "            <img src=\"".GetThemePath()."/images/checksi.png\" border='0'>\n";
      $html .= " 				  </a>\n";  
      $objResponse->assign("divmed_".$orden_medicamento,"innerHTML",$html);
      $objResponse->call("OcultarSpan");
    }
    return $objResponse;
  }  
  /**
  * Funcion en la que se desvincula un detalle de una orden de servicio del 
  * detalle de la cuenta por pagar
  * 
  * @param string $orden Identificador de la orden de servicio
  * @param string $orden_medicamento Identificador del detalle de la orden de servicio
  * @param string $cxp_detalle_id Identificador del detalle de la factura
  * @param string $cargo Cargo asociado
  * @param string $valor Valor del detalle de la orden de servicio
  * @param string $detalle Cadena que contiene las alertas
  * @param string $medicamento Codigo del medicamento
  *
  * @return object
  */
  function DesvincularDetalleM($orden,$orden_medicamento,$cxp_detalle_id,$codigo,$valor,$detalle,$medicamento)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory("Ordenes", "", "app","UV_CuentasXPagar");
    
    $rst = $cxp->RegistrarDesvinculacionDetalleMedicamento($cxp_detalle_id,$orden,$orden_medicamento);
    
    if(!$rst)
    {
      $html .= "<table width=\"98%\" align=\"center\">\n";
      $html .= "  <tr class=\"label_error\">\n";
      $html .= "	  <td align=\"justify\">".$cxp->ErrMsg()."</td>\n";
      $html .= "	</tr>\n";
      $html .= "</table >\n";
      $html .= "<table width=\"100%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "	  <td align=\"center\">\n";
      $html .= "		  <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Aceptar\" onclick=\"OcultarSpan('Contenedor')\">\n";
      $html .= "	  </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $objResponse->assign("ventana","innerHTML",$html);
      $objResponse->call("MostrarSpan");
    }
    else
    {
      $objResponse->assign("dtl_".$cxp_detalle_id."","innerHTML","");
      $objResponse->script("document.getElementById('hdd_".$cxp_detalle_id."').value = ''");
     
      $html  = " 				  <a href=\"javascript:AsociarDetalleMedicamento('".$orden."','".$orden_medicamento."','".$medicamento."','".$valor."')\" class=\"label_error\"  title=\"ASOCIAR ORDEN CON LA CUENTA\">\n";
      $html .= "            <img src=\"".GetThemePath()."/images/checkno.png\" border='0'>\n";
      $html .= " 				  </a>\n";
      $objResponse->assign("divmed_".$orden_medicamento,"innerHTML",$html);
    }
    return $objResponse;
  }
  /**
  * 
  * 
  * @return object
  */
  function FinalizarRevision($factura)
  {
    $objResponse = new xajaxResponse();
    $tipoauditor = SessionGetVar("TipoRevision");
    
    $html .= "<table width=\"98%\" align=\"center\">\n";
    $html .= "  <tr class=\"normal_10AN\">\n";
    $html .= "	  <td align=\"justify\">ESTA SEGURO QUE DESA FINALIZAR LA REVSION DE LA CUENTA DE COBRO ".$factura."</td>\n";
    $html .= "	</tr>\n";
    $html .= "</table >\n";
    $html .= "<table width=\"60%\" align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "	  <td align=\"center\">\n";
    $html .= "		  <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Aceptar\" onclick=\"TerminarRevision()\">\n";
    $html .= "	  </td>\n";
    $html .= "	  <td align=\"center\">\n";
    $html .= "		  <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan('Contenedor')\">\n";
    $html .= "	  </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    
    $objResponse->assign("ventana","innerHTML",$html);
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  /**
  *
  */
  function ObtenerServicio($form)
  {
    $objResponse = new xajaxResponse();
    $cs = AutoCarga::factory("CuentasXPagar","","app","UV_CuentasXPagar");
       
    $servicios = $cs->ObtenerServiciosCuenta($form);
    
    if(is_array($servicios))
    {
      $html  = "document.radicacion_factura.tipo_servicio.options.length = 0 ;\n";
      $html .= "document.radicacion_factura.tipo_servicio.options[0] = new Option('---Seleccionar---','-1',false, false);\n";
      $html .= "document.radicacion_factura.tipo_especialidad.options.length = 0 ;\n";
      $html .= "document.radicacion_factura.tipo_especialidad.options[0] = new Option('---Seleccionar---','-1',false, false);\n";
      $i = 1;
      foreach($servicios as $key => $dtl)
        $html .= "document.radicacion_factura.tipo_servicio.options[".($i++)."] = new Option('".utf8_encode($dtl['descripcion_tipo_servicio'])."','".$dtl['cxp_tipo_servicio_id']."',false, false);\n";
      
      if($i == 1)
        $html .= "document.radicacion_factura.tipo_servicio.disabled = true ;\n";
      else
        $html .= "document.radicacion_factura.tipo_servicio.disabled = false ;\n";  
      
      $html .= "document.radicacion_factura.tipo_especialidad.disabled = true ;\n";
      $objResponse->script($html);
    }
    else
    {
      $objResponse->alert($cs->mensajeDeError);
    }
    
    return $objResponse;
  }  
  /**
  *
  */
  function ObtenerEspecialidad($form)
  {
    $objResponse = new xajaxResponse();
    $cs = AutoCarga::factory("CuentasXPagar","","app","UV_CuentasXPagar");
       
    $servicios = $cs->ObtenerEspecialidadesServicios($form);
    
    if(is_array($servicios))
    {
      $html  = "document.radicacion_factura.tipo_especialidad.options.length = 0 ;\n";
      $html .= "document.radicacion_factura.tipo_especialidad.options[0] = new Option('---Seleccionar---','-1',false, false);\n";
      $i = 1;
      foreach($servicios as $key => $dtl)
        $html .= "document.radicacion_factura.tipo_especialidad.options[".($i++)."] = new Option('".utf8_encode($dtl['descripcion_especialidad'])."','".$dtl['cxp_especialidad_id']."',false, false);\n";
      
      if($i == 1)
        $html .= "document.radicacion_factura.tipo_especialidad.disabled = true ;\n";
      else
        $html .= "document.radicacion_factura.tipo_especialidad.disabled = false ;\n";  
      
      $objResponse->script($html);
    }
    else
    {
      $objResponse->alert($cs->mensajeDeError);
    }
    
    return $objResponse;
  }
?>