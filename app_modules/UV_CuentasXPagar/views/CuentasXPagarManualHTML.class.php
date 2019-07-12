<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: CuentasXPagarManualHTML.class.php,v 1.3 2008/10/23 22:09:23 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: CuentasXPagarManualHTML
  * Clase en la que se crean las formas para el modulo de cuentas por pagar
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class CuentasXPagarManualHTML
  {
    /**
    * Constructosr de la clase
    */
    function CuentasXPagarManualHTML(){}
    /**
		* Funcion donde se crea una forma con una ventana con capas para mostrar informacion
    * en pantalle
    *
    * @param string $funcion Funcion a la que se llama cuando se hace submit sobre la forma
    * @param int $tmn Tamaño que tendra la ventana
    *
    * @return string
		*/
		function CrearVentana($funcion, $tmn = 350)
		{
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 4;\n";
			$html .= "	function OcultarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('ContenedorP');\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function OcultarSpanGrande()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('ContenedorP');\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		  Iniciar();\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";			
      
			$html .= "	function MostrarSpanGrande()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
      $html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		  IniciarGrande();\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";		
      
      $html .= "	function MostrarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xShow(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function OcultarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xHide(Seccion);\n";
			$html .= "	}\n";

			$html .= "	function Iniciar()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'ContenedorP';\n";
			$html .= "		titulo = 'tituloP';\n";
      $html .= "		xGetElementById('error_p').innerHTNL = '';\n";
      $html .= "		ele = xGetElementById('ContenidoP');\n";
			$html .= "	  xResizeTo(ele,".$tmn.", 'auto');\n";	
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,".($tmn - 20).", 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrarP');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,".($tmn - 20).", 0);\n";
			$html .= "	}\n";

      $html .= "	function IniciarGrande()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Contenedor';\n";
			$html .= "		titulo = 'titulo';\n";
			$html .= "		ele = xGetElementById('Contenido');\n";
			$html .= "	  xResizeTo(ele,800, 380);\n";			
      $html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,800, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "		ele.innerHTML = 'LISTADO DE CARGOS';\n";
			$html .= "	  xResizeTo(ele,780, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrar');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,780, 0);\n";
			$html .= "	}\n";
      
			$html .= "	function myOnDragStart(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	  window.status = '';\n";
			$html .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "	  else xZIndex(ele, hiZ++);\n";
			$html .= "	  ele.myTotalMX = 0;\n";
			$html .= "	  ele.myTotalMY = 0;\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag(ele, mdx, mdy)\n";
			$html .= "	{\n";
			$html .= "	  if (ele.id == titulo) {\n";
			$html .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "	  }\n";
			$html .= "	  else {\n";
			$html .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "	  }  \n";
			$html .= "	  ele.myTotalMX += mdx;\n";
			$html .= "	  ele.myTotalMY += mdy;\n";
			$html .= "	}\n";
			$html .= "	function myOnDragEnd(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center;\">CONFIRMACIÓN</div>\n";
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpanGrande()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content'>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			$html .= "<div id='ContenedorP' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "	<div id='tituloP' class='draggable' style=\"	text-transform: uppercase;text-align:center;\"></div>\n";
			$html .= "	<div id='cerrarP' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "	<div id='ContenidoP' class='d2Content'>\n";
			$html .= "	  <form name=\"oculta\" id=\"oculta\" method=\"post\">\n";
 			$html .= "		  <div id=\"glosas\"></div>\n";
			$html .= "	    <div id='error_p' style=\"text-align:center\" class=\"label_error\"></div>\n";
			$html .= "	    <div id='ventana'></div>\n";
			$html .= "	  </form>\n";
			$html .= "	</div>\n";			
      $html .= "</div>\n";

			return $html;
		}
    /**
    * Funcion donde se crea una forma con los datos del proveedor
    *
    * @param array $proveedor Arreglo de datos cob la informacion del proveedor
    * @param int $p Valor del porcentaje para el tamaño de la tabla
    *
    * @return string
    */
    function FormaDatosProveedor($proveedor,$p)
    {
      $st = "style=\"text-indent:4pt;text-align:left\"";
      $html  = "<table width=\"".$p."%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"2\" class=\"formulacion_table_list\">PROVEEDOR</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td width=\"25%\"><b>".$proveedor['tipo_id_tercero']." ".$proveedor['tercero_id']." <b></td>\n";
      $html .= "    <td $st width=\"%\" ><b>".$proveedor['nombre_tercero']."</b></td>\n";
      $html .= "  </tr>\n";
      if($proveedor['direccion'])
      {
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td $st >DIRECCION</td>\n";
        $html .= "    <td $st class=\"modulo_list_claro\"> ".$proveedor['direccion']."</td>\n";
        $html .= "  </tr>\n";
      }
      if($proveedor['telefono'])
      {
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td $st>TELEFONO</td>\n";
        $html .= "    <td $st class=\"modulo_list_claro\"> ".$proveedor['telefono']."</td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "</table>\n";
      
      return $html;
    }
    /**
		* Funcion donde se crea la forma para mostrar el detalle de la factura
    *
    * @param array $action Arreglo de datos con los links de la forma
    * @param array $proveedor Arreglo de datos con la informacion del proveedor
    * @param array $paciente Arreglo de datos con la informacion del paciente
    * @param array $factura Arreglo de datos con la informacion de la factura
    * @param array $cargo Arreglo de datos con la informacion de los cargos de detalle de la factura
    * @param array $medica Arreglo de datos con la informacion de los medicamentos de detalle de la factura
    * @param array $otros Arreglo de datos con la informacion de los demas servicios cobrados en el detalle de la factura
    * @param array $ordenes Arreglo con la informacion de las ordenes asociados al proveedor
    * @param array $detalle Arreglo con la informacion del detalle de las ordenes
    * @param array $glosa Arreglo con la informacion delas glosas
    *
		* @return string
		*/
		function FormaDetalleCxP($action,$proveedor,$paciente,$factura,$cargo,$medica,$otros,$ordenes,$detalle,$glosa,$historico)
		{	
      $ctl = AutoCarga::factory("ClaseUtil"); 
      $html .= $ctl->AcceptNum();
      $html .= $ctl->LimpiarCampos();
      $html .= $ctl->IsNumeric();
      $html .= $ctl->RollOverFilas();
      $html .= $ctl->TrimScript();
			$html .= "<script>\n";
			$html .= "	function Objetar(cxp_detalle_factura_id,referencia,descripcion,valor,tipo_detalle)\n";
			$html .= "	{\n";
			$html .= "		xajax_Objetar(cxp_detalle_factura_id,referencia,descripcion,valor,tipo_detalle);\n";
			$html .= "	}\n";			
      $html .= "	function AceptarObjeccion()\n";
			$html .= "	{\n";
			$html .= "	  if(trim(document.oculta.observacion.value) == '')\n";
			$html .= "		  {document.getElementById('erroro').innerHTML = 'PARA HACER LA OBJECCION DEL DETALLE DE LA CUENTA ES NECESARIO INGRESAR UNA OBSERVACION';}\n";
			$html .= "		else if(!IsNumeric(document.oculta.valor_total.value))\n";
			$html .= "		  {document.getElementById('erroro').innerHTML = 'EL VALOR OBJETADO POSEE UN FORMATO DE NUMERO INCORRECTO O ES NULO';}\n";
			$html .= "		else if((document.oculta.valor_total.value*1) > (document.oculta.valor1.value*1))\n";
			$html .= "		  {document.getElementById('erroro').innerHTML = 'EL VALOR OBJETADO NO DEBE SER MAYOR AL VALOR DEl DETALLE';}\n";
			$html .= "		else\n";
			$html .= "		{\n";
 			$html .= "		  document.getElementById('erroro').innerHTML = ''\n";
			$html .= "		  xajax_RegistrarObjeccion(xajax.getFormValues('oculta'));\n";
			$html .= "	  }\n";
			$html .= "	}\n";
      $html .= "	function PasarValor(objeto)\n";
      $html .= "	{\n";
      $html .= "	  objeto.valor_total.value = objeto.valor1.value; \n";
      $html .= "	}\n";
      $html .= "	function ObjetarCuenta(pre,num)\n";
			$html .= "	{\n";
			$html .= "	  if(trim(document.objetar.observacion.value) == '')\n";
			$html .= "		  document.getElementById('errort').innerHTML = 'PARA HACER LA OBJECCION DE LA CUENTA ES NECESARIO INGRESAR UNA OBSERVACION'\n";
      $html .= "		else if(!IsNumeric(document.objetar.valor_total.value))\n";
			$html .= "		  {document.getElementById('errort').innerHTML = 'EL VALOR OBJETADO POSEE UN FORMATO DE NUMERO INCORRECTO O ES NULO';}\n";
			$html .= "		else if((document.objetar.valor_total.value*1) > (document.objetar.valor1.value*1))\n";
			$html .= "		  {document.getElementById('errort').innerHTML = 'EL VALOR OBJETADO NO DEBE SER MAYOR AL VALOR DE LA FACTURA';}\n";
			$html .= "		else\n";
			$html .= "		{\n";
 			$html .= "		  document.getElementById('errort').innerHTML = ''\n";
			$html .= "		  xajax_RegistrarObjeccionT(xajax.getFormValues('objetar'),pre,num);\n";
			$html .= "	  }\n";      
			$html .= "	}\n";      
      $html .= "	function AgregarDetalle(tipodetalle)\n";
			$html .= "	{\n";
      $html .= "	  if(tipodetalle == 'c')\n";
			$html .= "		  xajax_AgregarCargo();\n";
      $html .= "	  if(tipodetalle == 'm')\n";
			$html .= "		  xajax_AgregarMedicamento();\n";
      $html .= "	  if(tipodetalle == 'o')\n";
			$html .= "		  xajax_FormaAdicionarOtro();\n";
			$html .= "	}\n";        
      $html .= "	function Buscar(identificacion,tipo,off)\n";
			$html .= "	{\n";
			$html .= "	  if(tipo == 'c')\n";
			$html .= "	    xajax_AgregarCargo(xajax.getFormValues(identificacion),off);\n";
			$html .= "	  if(tipo == 'm')\n";
			$html .= "	    xajax_AgregarMedicamento(xajax.getFormValues(identificacion),off);\n";
			$html .= "	}\n";      
      $html .= "	function Agregar(referencia,tipo)\n";
			$html .= "	{\n";
			$html .= "	  if(tipo == 'c')\n";
			$html .= "	    xajax_FormaAdicionarCargo(referencia);\n";
			$html .= "	  if(tipo == 'm')\n";
			$html .= "	    xajax_FormaAdicionarMedic(referencia);\n";
			$html .= "	}\n";
      
      $html .= "	function AdicionarDetalleCxP(tipodetalle)\n";
			$html .= "	{\n";
			$html .= "	  if(!IsNumeric(document.oculta.cantidad.value))\n";
			$html .= "		  document.getElementById('error_p').innerHTML = 'LA CANTIDAD ES OBLIGATORIA O POSEE UN FORMATO DE NUMERO INCORRECTO'\n";
			$html .= "	  else if(!IsNumeric(document.oculta.valor_unitario.value))\n";
			$html .= "		  document.getElementById('error_p').innerHTML = 'EL VALOR UNITARIO ES OBLIGATORIO O POSEE UN FORMATO DE NUMERO INCORRECTO'\n";
			$html .= "		else\n";
			$html .= "		  xajax_AdicionarDetalleCxP(xajax.getFormValues('oculta'),'".$factura['prefijo']."','".$factura['numero']."',tipodetalle);\n";
			$html .= "	}\n";
      $html .= "	function AdicionarDetalleCxPOtros(tipodetalle)\n";
			$html .= "	{\n";
			$html .= "	  if(!IsNumeric(document.oculta.cantidad.value))\n";
			$html .= "		  document.getElementById('error_p').innerHTML = 'LA CANTIDAD ES OBLIGATORIA O POSEE UN FORMATO DE NUMERO INCORRECTO'\n";
			$html .= "	  else if(!IsNumeric(document.oculta.valor_unitario.value))\n";
			$html .= "		  document.getElementById('error_p').innerHTML = 'EL VALOR UNITARIO ES OBLIGATORIO O POSEE UN FORMATO DE NUMERO INCORRECTO'\n";
			$html .= "	  else if(trim(document.oculta.descripcion.value) == '')\n";
			$html .= "		  document.getElementById('error_p').innerHTML = 'SE DEBE HACER EL INGRESO DE LA DESCRIPCION DEL CONCEPTO QUE SE DESA INGRESAR'\n";
			$html .= "		else\n";
			$html .= "		  xajax_AdicionarDetalleCxP(xajax.getFormValues('oculta'),'".$factura['prefijo']."','".$factura['numero']."','o');\n";
			$html .= "	}\n";
      
      $html .= "	function Eliminar(cxp_detalle_id,tipo_detalle)\n";
			$html .= "	{\n";
			$html .= "		xajax_Eliminar(cxp_detalle_id,tipo_detalle,'".$factura['prefijo']."','".$factura['numero']."');\n";
			$html .= "	}\n"; 
      
      $html .= "	function AsociarCXP(orden)\n";
			$html .= "	{\n";
			$html .= "		xajax_AsociarCXP(orden,'".$factura['prefijo']."','".$factura['numero']."');\n";
			$html .= "	}\n";
      $html .= "	function DesvincularCXP(orden)\n";
			$html .= "	{\n";
			$html .= "		xajax_DesvincularCXP(orden,'".$factura['prefijo']."','".$factura['numero']."');\n";
			$html .= "	}\n";      
      $html .= "	function AsociarDetalleCargo(orden,orden_cargo,cups,valor)\n";
			$html .= "	{\n";
			$html .= "		xajax_AsociarDetalleCargo(orden,orden_cargo,cups,valor,'".$factura['prefijo']."','".$factura['numero']."');\n";
			$html .= "	}\n";
      $html .= "	function VincularDetalle(cxp_detalle_id,orden,orden_cargo,cargo,valor1,valor2)\n";
      $html .= "	{\n";
      $html .= "		xajax_VincularDetalle(cxp_detalle_id,orden,orden_cargo,cargo,valor1,valor2);\n";
      $html .= "	}\n";      
      $html .= "	function DesvincularDetalleCargo(orden,orden_cargo,cxp_detalle_id,cargo,valor,detalle)\n";
      $html .= "	{\n";
      $html .= "		xajax_DesvincularDetalle(orden,orden_cargo,cxp_detalle_id,cargo,valor,detalle);\n";
      $html .= "	}\n";     
      $html .= "	function AsociarDetalleMedicamento(orden,orden_medicamento,codigo,valor)\n";
			$html .= "	{\n";
			$html .= "		xajax_AsociarDetalleMedicamento(orden,orden_medicamento,codigo,valor,'".$factura['prefijo']."','".$factura['numero']."');\n";
			$html .= "	}\n";
      $html .= "	function VincularDetalleM(cxp_detalle_id,orden,orden_medicamento,codigo,valor1,valor2,medicamento)\n";
      $html .= "	{\n";
      $html .= "		xajax_VincularDetalleM(cxp_detalle_id,orden,orden_medicamento,codigo,valor1,valor2,medicamento);\n";
      $html .= "	}\n";      
      $html .= "	function DesvincularDetalleMedicamento(orden,orden_medicamento,cxp_detalle_id,codigo,valor,detalle,medicamento)\n";
      $html .= "	{\n";
      $html .= "		xajax_DesvincularDetalleM(orden,orden_medicamento,cxp_detalle_id,codigo,valor,detalle,medicamento);\n";
      $html .= "	}\n";
      $html .= "	function FinalizarRevision()\n";
      $html .= "	{\n";
      $html .= "		xajax_FinalizarRevision('".$factura['prefijo_factura']." ".$factura['numero_factura']."');\n";
      $html .= "	}\n";      
      $html .= "	function TerminarRevision()\n";
      $html .= "	{\n";
      $html .= "	  location.href = \"".$action['revision'].URLRequest(array("prefijo"=>$factura['prefijo'],"numero"=>$factura['numero']))."\"\n";
      $html .= "	}\n";
      $html .= "	function ModificarObjecion(cxp_detalle_factura_id,referencia,descripcion,valor,tipo_detalle)\n";
			$html .= "	{\n";
			$html .= "		xajax_ModificarObjecion(cxp_detalle_factura_id,referencia,descripcion,valor,tipo_detalle);\n";
			$html .= "	}\n";	
			$html .= "</script>\n";
			$html .= ThemeAbrirTabla("DETALLE DEL DOCUMENTO");
      $html .= "<table width=\"90%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td width=\"45%\"  valign=\"top\">\n";
      $html .= $this->FormaDatosProveedor($proveedor,100);
      
      $html .= "    </td>\n";
      $html .= "    <td width=\"%\" valign=\"top\">\n";
      $st = "style=\"text-indent:4pt;text-align:left\"";
			$html .= "      <table align=\"center\" cellpading=\"0\"  width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "        <tr>\n";
      $html .= "          <td colspan=\"7\" class=\"formulacion_table_list\">FACTURA</td>\n";
      $html .= "        </tr>\n";
      $html .= "	      <tr class=\"formulacion_table_list\">\n";
			$html .= "		      <td $st>FACTURA</td>\n";
			$html .= "		      <td class=\"modulo_list_claro\" width=\"10%\">\n";
			$html .= "			      ".$factura['prefijo_factura']." ".$factura['numero_factura']."\n";
			$html .= "		      </td>\n";
			$html .= "		      <td $st >F FACTURA</td>\n";
			$html .= "		      <td class=\"modulo_list_claro\"  width=\"10%\">\n";
			$html .= "			      ".$factura['fecha_documento']."\n";
			$html .= "		      </td>\n";
			$html .= "		      <td $st >REGISTRO</td>\n";
			$html .= "		      <td class=\"modulo_list_claro\"  width=\"10%\">\n";
			$html .= "			      ".$factura['fecha_registro']."\n";
			$html .= "		      </td>\n";
			$html .= "		    </tr>\n";
			$html .= "		    <tr class=\"formulacion_table_list\">\n";
			$html .= "			    <td $st width=\"17%\">VALOR</td>\n";
			$html .= "			    <td width=\"17%\" class=\"modulo_list_claro\" align=\"right\">\n";
			$html .= "				    ".formatoValor($factura['valor_total'])."\n";
			$html .= "			    </td>\n";
			$html .= "			    <td $st width=\"18%\">IVA</td>\n";
			$html .= "			    <td width=\"15%\" class=\"modulo_list_claro\" align=\"right\" >\n";
			$html .= "				    ".formatoValor($factura['valor_iva'])."\n";
			$html .= "			    </td>\n";
			$html .= "			    <td $st width=\"18%\">GRAVAMEN</td>\n";
			$html .= "			    <td width=\"15%\" class=\"modulo_list_claro\" align=\"right\" width=\"15%\">\n";
			$html .= "				    ".formatoValor($factura['valor_gravamen'])."\n";
			$html .= "			    </td>\n";
			$html .= "		    </tr>\n";
      $html .= "		    <tr class=\"formulacion_table_list\">\n";
			$html .= "			    <td $st >T CUENTA</td>\n";
			$html .= "			    <td colspan=\"6\" class=\"modulo_list_claro\" >\n";
			$html .= "				    ".$factura['tipo_cxp_descripcion']."\n";
			$html .= "			    </td>\n";
			$html .= "		    </tr>\n";
			$html .= "	    </table>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table><br>\n";
      $html .= $this->FormaHistoricoEstados($historico);
      $html .= "<br>\n";
      
      if(!empty($paciente))
      {
        $html .= "  <table align=\"center\" cellpading=\"0\"  width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
        $html .= "	  <tr class=\"formulacion_table_list\">\n";
        $html .= "		  <td colspan=\"2\">PACIENTES RELACIONADOS EN LA CUENTA DE COBRO</td>\n";
        $html .= "		</tr>\n";
        $html .= "		<tr class=\"formulacion_table_list\">\n";
        $html .= "			<td width=\"40%\">IDENTIFICACION</td>\n";
        $html .= "			<td >NOMBRE</td>\n";
        $html .= "		</tr>\n";
        
        foreach($paciente as $key => $dtl)
        {
          $html .= "		<tr>\n";
          
          $html .= "			<td class=\"modulo_list_claro\">\n";
          $html .= "				".$dtl['identificacion']." \n";
          $html .= "			</td>\n";
          $html .= "			<td class=\"modulo_list_claro\" >\n";
          if($dtl['tipo_id_paciente'])
            $html .= "				".$dtl['primer_nombre']." ".$dtl['segundo_nombre']." ".$dtl['primer_apellido']." ".$dtl['segundo_apellido']."\n";
          else
            $html .= "				<b class=\"label_error\">EL PACIENTE NO FUE ENCONTRADO EN EL SISTEMA</b>\n";
          $html .= "			</td>\n";
          $html .= "		</tr>\n";
        }
        $html .= "	</table><br>\n";
      }
            $glosai = $glosa[UserGetUID()];
      
      unset($glosa[UserGetUID()]);
      if(!empty($glosa))
      {
        $html .= "<table width=\"71%\" align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
        $html .= "      <fieldset class=\"fieldset\">\n";
        $html .= "	      <legend class=\"normsl_10AN\">OBJECCIONES PRESENTES SOBRE LA CUENTA</legend>\n";
        $html .= "	      <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
        $html .= "		      <tr class=\"modulo_table_list_title\" height=\"17\">\n";
        $html .= "			      <td width=\"10%\" >VALOR</b></td>\n";
        $html .= "			      <td width=\"%\">OBESERVACION</b></td>\n";
        $html .= "			      <td width=\"25%\">REGISTRADO POR</td>\n";
        $html .= "			      <td width=\"10%\">FECHA</td>\n";
        $html .= "		      </tr>\n";
        
        foreach($glosa as $key => $detalle)
        {          
          $html .= "		      <tr>\n";
          $html .= "		        <td align=\"right\">$".formatoValor($detalle['valor'])."</td>\n";
          $html .= "		        <td align=\"justify\">".$detalle['observacion']."</td>\n";
          $html .= "		        <td >".$detalle['nombre']."</td>\n";
          $html .= "		        <td >".$detalle['fecha_registro']."</td>\n";
          $html .= "		      </tr>\n";
        }
        $html .= "	      </table>\n";
        $html .= "      </fieldset>\n";
        $html .= "		</td>\n";
        $html .= "	</tr>\n";
        $html .= "</table>\n";
      }
      $html .= "<form name=\"objetar\" id=\"objetar\" action=\"javascript:ObjetarCuenta('".$factura['prefijo']."', '".$factura['numero']."')\" method=\"post\">\n";
      $html .= "  <table width=\"71%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "        <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">OBJETAR CUENTA POR COBRAR</LEGEND>\n";
      $html .= "			    <table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
      $html .= "			      <tr class=\"formulacion_table_list\">\n";
      $html .= "			        <td colspan=\"4\">OBSERVACION</td>\n";
      $html .= "			      </tr>\n";
      $html .= "			      <tr class=\"formulacion_table_list\">\n";
      $html .= "			        <td colspan=\"4\">\n";
      $html .= "                <textarea class=\"textarea\" id=\"general\" name=\"observacion\" style=\"width:100%\" rows=\"3\">".$glosai['observacion']."</textarea>\n";
      $html .= "              </td>\n";
      $html .= "			      </tr>\n";
      $html .= "            <tr class=\"formulacion_table_list\">\n"; 
      $html .= "              <td align=\"left\" width=\"25%\">* VALOR</td>\n"; 
      $html .= "              <td align=\"right\" width=\"25%\" class=\"modulo_list_claro\">$".formatoValor($factura['valor_total'])." </td>\n"; 
      $html .= "              <td width=\"2%\" class=\"modulo_list_claro\">\n";
      $html .= "                <img style=\"cursor:pointer\" title=\"PASAR VALOR\" src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" onclick=\"PasarValor(document.objetar)\">\n";
      $html .= "              </td>\n"; 
      $html .= "              <td align=\"left\"  class=\"modulo_list_claro\">\n";
      $html .= "                <input type=\"hidden\" name=\"cxp_glosa_observacion_id\" value=\"".$glosai['cxp_glosa_observacion_id']."\">\n";
      $html .= "                <input style=\"width:60%\"  type=\"text\" name=\"valor_total\" class=\"input-text\" onkeypress=\"return acceptNum(event)\" value=\"".$glosai['valor']."\">\n";
      $html .= "              </td>\n"; 
      $html .= "            </tr>\n"; 
      $html .= "			    </table >\n";
      $html .= "			    <div id=\"errort\" class=\"label_error\" style=\"text-align:center\"><br></div>\n";
      $html .= "	        <table width=\"100%\" align=\"center\">\n";
      $html .= "	          <tr>\n";
      $html .= "			        <td align='center'>\n";
      $html .= "			          <input type=\"hidden\" name=\"valor1\" value=\"".$factura['valor_total']."\">\n";
      $html .= "			          <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
      $html .= "		          </td>\n";
      $html .= "            </tr>\n";
      $html .= "          </table>\n";
      $html .= "        </fieldset>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "<table width=\"100%\" align=\"center\">\n";
			$html .= "  <tr>\n";
			$html .= "	  <td>\n";
			$html .= "		  <div class=\"tab-pane\" id=\"APD\">\n";
			$html .= "			  <script>	tabPane = new WebFXTabPane( document.getElementById( \"APD\" ),false); </script>\n";
      $html .= "				<div class=\"tab-page\" id=\"pendientes\">\n";
      $html .= "				  <h2 class=\"tab\">DETALLE CXP</h2>\n";
      $html .= "				  <script>	tabPane.addTabPage( document.getElementById(\"pendientes\")); </script>\n";
      $html .= "          <table align=\"center\" width=\"75%\" >\n";
      $html .= "            <tr>\n";
      $html .= "              <td width=\"23%\">\n";
      $html .= "                <a href=\"javascript:AgregarDetalle('c')\" class=\"label_error\">\n";
      $html .= "                  ADICCION DE CARGOS\n";
      $html .= "                </a>\n";
      $html .= "              </td>\n";
      $html .= "              <td width=\"30%\">\n";
      $html .= "                <a href=\"javascript:AgregarDetalle('m')\" class=\"label_error\">\n";
      $html .= "                  ADICCION DE MEDICAMENTOS\n";
      $html .= "                </a>\n";
      $html .= "              </td>\n";
      $html .= "              <td width=\"%\">\n";
      $html .= "                <a href=\"javascript:AgregarDetalle('o')\" class=\"label_error\">\n";
      $html .= "                  ADICCION DE OTROS SERVICIOS Y/O CONCEPTOS\n";
      $html .= "                </a>\n";
      $html .= "              </td>\n";
      $html .= "            </tr>\n";
      $html .= "          </table>\n";
      
      (!empty($cargo))? $dsp = "block": $dsp = "none"; 
      $html .= "          <div id=\"cargos_detalle\" style=\"display:".$dsp."\">\n";
      $html .= $this->FormaDetalleCxPCargos($cargo);
			$html .= "          </div>\n";
      
      (!empty($medica))? $dsp = "block": $dsp = "none"; 
      $html .= "          <div id=\"medicamentos_detalle\" style=\"display:".$dsp."\">\n";
      $html .= $this->FormaDetalleCxPMedicamentos($medica);
			$html .= "          </div>\n";

      (!empty($otros))? $dsp = "block": $dsp = "none"; 
      $html .= "          <div id=\"otros_detalle\" style=\"display:".$dsp."\">\n";
      $html .= $this->FormaDetalleCxPOtros($otros);
			$html .= "          </div>\n";
      $html .= "        </div>\n";
      
      $html .= "				<div class=\"tab-page\" id=\"ordenes\">\n";
      $html .= "				  <h2 class=\"tab\">ORDENES DE SERVICIO PROVEEDOR</h2>\n";
      $html .= "				  <script>	tabPane.addTabPage( document.getElementById(\"ordenes\")); </script>\n";
      $html .= $this->FormaMostarOrden($ordenes,$detalle);
      $html .= "        </div>\n";
      $html .= "      </div>\n";
			$html .= "	  </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
			$html .= "<table width=\"90%\" align=\"center\"><tr><td align=\"center\">\n";
			$html .= "  <tr>\n";
      $html .= "    <td align=\"center\" >\n";
      $html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "			</form>\n";
			$html .= "		</td>\n";
      $html .= "    <td align=\"center\" >\n";
			$html .= "		  <form name=\"volver\" action=\"javascript:FinalizarRevision()\" method=\"post\">\n";
			$html .= "			  <input type=\"submit\" class=\"input-submit\" value=\"Finalizar Revision\">\n";
			$html .= "			</form>\n";
			$html .= "		</td>\n";
      $html .= "  </tr>\n";
			$html .= "</table>\n";
      $html .= $this->CrearVentana("AceptarObjeccion()",500);
			$html .= ThemeCerrarTabla();
			return $html;
		}
    /**
    * Funcion donde se crea la tabla que contiene  la informacion de los cargos del detalle 
    * de la factura
    *
    * @param array $cargo Arreglo de datos con la informacion de los cargos de detalle de la factura
    *
    * @return string
    */
    function FormaDetalleCxPCargos($cargo)
    {
      $html = "";
      if(sizeof($cargo) > 0)
			{			
				$bck = "#CCCCCC";
        $est = "modulo_list_oscuro";
        
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "	<legend class=\"normsl_10AN\">DETALLE CARGOS CUENTA</legend>\n";
				
				$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$html .= "		<tr class=\"modulo_table_list_title\" height=\"17\">\n";
				$html .= "			<td width=\"9%\" >CODIGO</b></td>\n";
				$html .= "			<td width=\"%\">DESCRIPCIÓN</b></td>\n";
				$html .= "			<td width=\"5%\">CANT</td>\n";
				$html .= "			<td width=\"8%\">UNITARIO</td>\n";
				$html .= "			<td width=\"8%\">GARVAMEN</td>\n";
				$html .= "			<td width=\"8%\">TOTAL</td>\n";
				$html .= "			<td width=\"6%\">AUTO</td>\n";
				$html .= "			<td width=\"2%\" colspan=\"2\"></td>\n";
				$html .= "		</tr>\n";
				
				foreach($cargo as $key => $detalle)
				{
					($bck == "#DDDDDD")? $bck = "#CCCCCC":$bck = "#DDDDDD";
					($est == "modulo_list_oscuro")? $est = "modulo_list_claro":$est = "modulo_list_oscuro";
					
          $bd = $bc = $ms = $vl = "";
          if ($detalle['valor_orden'] != $detalle['valor_unitario'] && $detalle['valor_orden'] > 0)
          {
            $bd = "class=\"label_error\" ";
            $ms = "<img src=\"".GetThemePath()."/images/infor.png\" onclick=\"alert(document.getElementById('hdd_".$detalle['cxp_detalle_factura_id']."').value)\">\n";
            $vl .= "\\nEL VALOR DEL CARGO IDENTIFICADO CON ".$detalle['referencia'].", NO COINCIDE CON EL VALOR ($".formatoValor($detalle['valor_orden']).") DE LA ORDEN DE SERVICO.";
          }
          
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$html .= "			<td $bc >".$detalle['referencia']."</td>\n";
					$html .= "			<td align=\"justify\">".$detalle['descripcion']."</td>\n";
					$html .= "			<td align=\"right\">".$detalle['cantidad']."</td>\n";
					$html .= "			<td $bd align=\"right\">".formatoValor($detalle['valor_unitario'])."</td>\n";
					$html .= "			<td align=\"right\">".formatoValor($detalle['valor_gravamen'])."</td>\n";
					$html .= "			<td align=\"right\">".formatoValor($detalle['valor_total'])."</td>\n";
          $html .= "			<td align=\"right\">".$detalle['autorizacion']."</td>\n";
          $html .= "      <td width=\"1%\">\n";
          $html .= "        <div id=\"objeccion".$detalle['cxp_detalle_factura_id']."\" >\n";
					if($detalle['sw_objetado'] == '1')
          {
            $html .= "				<a href=\"javascript:ModificarObjecion('".$detalle['cxp_detalle_factura_id']."','".$detalle['referencia']."','".$detalle['descripcion']."','".$detalle['valor_total']."','C')\" title=\"MODIFICAR OBJETAR CARGO\">\n";
            $html .= "					<img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\">\n";
            $html .= "				</a>\n";					
          }
          else
          {
					  $html .= "				<a href=\"javascript:Objetar('".$detalle['cxp_detalle_factura_id']."','".$detalle['referencia']."','".$detalle['descripcion']."','".$detalle['valor_total']."','C')\" title=\"OBJETAR CARGO\">\n";
            $html .= "					<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\">\n";
            $html .= "				</a>\n";
          }
          $html .= "        </div>\n";
          $html .= "      </td>\n";
          $html .= "		  <td width=\"1%\">\n";
          $html .= "        <input type=\"hidden\" id =\"hdd_".$detalle['cxp_detalle_factura_id']."\" value=\"".$vl."\">\n";
          $html .= "        <div id=\"dtl_".$detalle['cxp_detalle_factura_id']."\" >\n";
          if ($ms != "")
          {
            $html .= $ms;
          }
          else if(!$detalle['cxp_glosa_id'] && !$detalle['valor_orden'])
          {
            $html .= "			  <a href=\"javascript:Eliminar('".$detalle['cxp_detalle_factura_id']."','c')\" title=\"ADICIONAR CARGOS\"";
            $html .= " onclick=\"return confirm('ESTA SEGURO QUE DESEA ELIMINAR EL DETALLE DE LA CUENTA \\nPERTENECIENTE AL CARGO ".$detalle['referencia']." ?');\">";
            $html .= "				  <img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
            $html .= "			  </a>\n";
  				}
          $html .= "        </div>\n";
          $html .= "		  </td>\n";
					$html .= "		</tr>\n";
				}
				$html .= "	</table>\n";
        $html .= "</fieldset><br>\n";		
			}
      return $html;
    }
    /**
    * Funcion donde se crea la tabla que contiene la informacion de los medicamentos
    * del detalle de la factura
    *
    * @param array $medica Arreglo de datos con la informacion de los medicamentos de detalle de la factura
    *
    * @return string
    */
    function FormaDetalleCxPMedicamentos($medica)
    {
      $html = "";
			if(sizeof($medica) > 0)
			{
 				$bck = "#CCCCCC";
        $est = "modulo_list_oscuro";

        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "	<legend class=\"normsl_10AN\">DETALLE MEDICAMENTOS CUENTA</legend>\n";
				$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$html .= "		<tr class=\"modulo_table_list_title\" height=\"17\">\n";
				$html .= "			<td width=\"9%\" >CODIGO</b></td>\n";
				$html .= "			<td width=\"%\">DESCRIPCIÓN</b></td>\n";
				$html .= "			<td width=\"5%\">CANT</td>\n";
				$html .= "			<td width=\"8%\">UNITARIO</td>\n";
				$html .= "			<td width=\"8%\">GARVAMEN</td>\n";
				$html .= "			<td width=\"8%\">TOTAL</td>\n";
 				$html .= "			<td width=\"6%\">AUTO</td>\n";
				$html .= "			<td width=\"2%\" colspan=\"2\"></td>\n";
				$html .= "		</tr>\n";
				
				foreach($medica as $key => $detalle)
				{
					($bck == "#DDDDDD")? $bck = "#CCCCCC":$bck = "#DDDDDD";
					($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro";

          $bd = $bc = $ms = $vl = "";
          if ($detalle['valor'] != $detalle['valor_unitario'] && $detalle['valor'] > 0)
          {
            $bd = "class=\"label_error\" ";
            $ms = "<img src=\"".GetThemePath()."/images/infor.png\" onclick=\"alert(document.getElementById('hdd_".$detalle['cxp_detalle_factura_id']."').value)\">\n";
            $vl = "EL VALOR DEL MEDICAMENTO IDENTIFICADO CON ".$detalle['referencia'].", NO COINCIDE CON EL VALOR ($".formatoValor($detalle['valor']).") DE LA ORDEN DE SERVICO.";
          }
          
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$html .= "			<td >".$detalle['referencia']."</td>\n";
					$html .= "			<td >".$detalle['descripcion']."</td>\n";
					$html .= "			<td align=\"right\">".$detalle['cantidad']."</td>\n";
					$html .= "			<td align=\"right\" $bd >".formatoValor($detalle['valor_unitario'])."</td>\n";
					$html .= "			<td align=\"right\">".formatoValor($detalle['valor_gravamen'])."</td>\n";
					$html .= "			<td align=\"right\">".formatoValor($detalle['valor_total'])."</td>\n";
					$html .= "			<td align=\"right\">".$detalle['autorizacion']."</td>\n";
					$html .= "      <td width=\"1%\">\n";
          $html .= "        <div id=\"objeccion".$detalle['cxp_detalle_factura_id']."\" >\n";
          if($detalle['sw_objetado'] == '1')
          {
            $html .= "				<a href=\"javascript:ModificarObjecion('".$detalle['cxp_detalle_factura_id']."','".$detalle['referencia']."','".$detalle['descripcion']."','".$detalle['valor_total']."','M')\" title=\"MODIFICAR OBJECCION MEDICAMENTO\">\n";
            $html .= "					<img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\">\n";
            $html .= "				</a>\n";					
          }
          else
          {
            $html .= "				<a href=\"javascript:Objetar('".$detalle['cxp_detalle_factura_id']."','".$detalle['referencia']."','".$detalle['descripcion']."','".$detalle['valor_total']."','M')\" title=\"OBJETAR MEDICAMENTO\">\n";
            $html .= "					<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\">\n";
            $html .= "				</a>\n";
          }
          $html .= "        </div>\n";
          $html .= "      </td>\n";
          $html .= "		  <td width=\"1%\">\n";
          $html .= "        <input type=\"hidden\" id =\"hdd_".$detalle['cxp_detalle_factura_id']."\" value=\"".$vl."\">\n";
          $html .= "        <div id=\"dtl_".$detalle['cxp_detalle_factura_id']."\" >\n";
          if ($ms != "")
          {
            $html .= $ms;
          }
          else if(!$detalle['cxp_glosa_id'] && !$detalle['valor'])
          {
            $html .= "			  <a href=\"javascript:Eliminar('".$detalle['cxp_detalle_factura_id']."','m')\" title=\"ADICIONAR CARGOS\"";
            $html .= " onclick=\"return confirm('ESTA SEGURO QUE DESEA ELIMINAR EL DETALLE DE LA CUENTA \\nPERTENECIENTE AL MEDICAMENTO ".$detalle['referencia']." ?');\">";
            $html .= "				  <img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
            $html .= "			  </a>\n";
  				}
          $html .= "        </div>\n";
          $html .= "		  </td>\n";
          $html .= "		</tr>\n";
				}
				$html .= "	</table>\n";
        $html .= "</fieldset><br>\n";	
			}
      return $html;
    }
    /**
    * Funcion donde se crea la tabla que contiene la informacion de los otros servicios 
    * cobrados en el detalle de la factura
    *
    * @param array $otros Arreglo de datos con la informacion de los demas servicios cobrados en el detalle de la factura
    *
    * @return string
    */
    function FormaDetalleCxPOtros($otros)
    {
      $html = "";
      if(sizeof($otros) > 0)
			{
 				$bck = "#CCCCCC";
        $est = "modulo_list_oscuro";

        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "	<legend class=\"normsl_10AN\">DETALLE OTROS SERVICIOS CUENTA</legend>\n";
				$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$html .= "		<tr class=\"modulo_table_list_title\" height=\"17\">\n";
				$html .= "			<td width=\"9%\" >CODIGO</b></td>\n";
				$html .= "			<td width=\"%\">DESCRIPCIÓN</b></td>\n";
				$html .= "			<td width=\"5%\">CANT</td>\n";
				$html .= "			<td width=\"8%\">UNITARIO</td>\n";
				$html .= "			<td width=\"8%\">GARVAMEN</td>\n";
				$html .= "			<td width=\"8%\">TOTAL</td>\n";
 				$html .= "			<td width=\"6%\">AUTO</td>\n";
				$html .= "			<td width=\"2%\" colspan=\"2\"></td>\n";
				$html .= "		</tr>\n";
				
				foreach($otros as $key => $detalle)
				{
					($bck == "#DDDDDD")? $bck = "#CCCCCC":$bck = "#DDDDDD";
					($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro";
									
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$html .= "			<td >".$detalle['referencia']."</td>\n";
					$html .= "			<td >".$detalle['descripcion']."</td>\n";
					$html .= "			<td align=\"right\">".$detalle['cantidad']."</td>\n";
					$html .= "			<td align=\"right\">".formatoValor($detalle['valor_unitario'])."</td>\n";
					$html .= "			<td align=\"right\">".formatoValor($detalle['valor_gravamen'])."</td>\n";
					$html .= "			<td align=\"right\">".formatoValor($detalle['valor_total'])."</td>\n";
					$html .= "			<td align=\"right\">".$detalle['autorizacion']."</td>\n";
          $html .= "      <td width=\"1%\">\n";
          $html .= "        <div id=\"objeccion".$detalle['cxp_detalle_factura_id']."\" >\n";
          if($detalle['sw_objetado'] == '1')
          {
            $html .= "				<a href=\"javascript:ModificarObjecion('".$detalle['cxp_detalle_factura_id']."','".$detalle['referencia']."','".$detalle['descripcion']."','".$detalle['valor_total']."','O')\" title=\"MODIFICAR OBJECION OTROS SERVICIOS\">\n";
            $html .= "					<img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\">\n";
            $html .= "				</a>\n";					
          }
          else
          {
					  $html .= "				<a href=\"javascript:Objetar('".$detalle['cxp_detalle_factura_id']."','".$detalle['referencia']."','".$detalle['descripcion']."','".$detalle['valor_total']."','O')\" title=\"OBJETAR OTROS SERVICIOS\">\n";
            $html .= "					<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\">\n";
            $html .= "				</a>\n";
          }
          $html .= "        </div>\n";
          $html .= "		  </td>\n";
          $html .= "		  <td width=\"1%\">\n";
          $html .= "        <div id=\"dtl_".$detalle['cxp_detalle_factura_id']."\" >\n";
          if(!$detalle['cxp_glosa_id'])
          {
            $html .= "			  <a href=\"javascript:Eliminar('".$detalle['cxp_detalle_factura_id']."','o')\" title=\"ADICIONAR CARGOS\"";
            $html .= " onclick=\"return confirm('ESTA SEGURO QUE DESEA ELIMINAR EL DETALLE DE LA CUENTA \\nPERTENECIENTE AL CONCEPTO ".$detalle['referencia']." ?');\">";
            $html .= "				  <img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
            $html .= "			  </a>\n";
  				}
          $html .= "        </div>\n";
          $html .= "		  </td>\n";
          $html .= "		</tr>\n";
				}
				$html .= "	</table>\n";
        $html .= "</fieldset><br>\n";	
			}
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar las ordenes de servicios asociadas
    * al proveedor
    *
    * @param array $orden Arreglo con los datos de las ordenes
    * @param array $detalle Arreglo con los datos del detalle de las ordenes
    *
    * @return string
    */
    function FormaMostarOrden($orden,$detalle)
    {
      $dat = array();
      $est = 'modulo_list_oscuro'; $back = "#DDDDDD";
 			
      $html = "";
      foreach($orden as $key1 => $ordenes)
      {          
        $sty = " style=\"text-align:left;text-indent:6pt\" ";
        $html .= "  <table width=\"100%\" class=\"modulo_table_list\" align=\"center\">\n";
        $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
        $html .= "  	  <td $sty width=\"16%\">Nº ORDEN</td>\n";
        $html .= "  	  <td $sty width=\"16%\" class=\"modulo_list_claro\">".$ordenes['eps_orden_servicio']."</td>\n";
        $html .= "  	  <td $sty width=\"16%\">Nº AUTORIZACION</td>\n";
        $html .= "  	  <td $sty width=\"16%\" class=\"modulo_list_claro\">".$ordenes['autorizacion_id']."</td>\n";
        $html .= "  	  <td $sty width=\"10%\">FECHA</td>\n";
        $html .= "  	  <td $sty width=\"22%\" class=\"modulo_list_claro\" >".$ordenes['fecha_registro']."</td>\n";
        $html .= "			<td class=\"modulo_list_oscuro\" align=\"center\" valign=\"middle\" rowspan=\"2\">\n";
        $html .= "			  <div id=\"divorden_".$ordenes['eps_orden_servicio']."\">\n";
        if($ordenes['marca'] == '1')
        {  
          $html .= "			    <a href=\"javascript:DesvincularCXP('".$ordenes['eps_orden_servicio']."')\" class=\"label_error\"  title=\"DESVINCULAR ORDEN DE LA CUENTA\">\n";
          $html .= "			      <img src=\"".GetThemePath()."/images/checksi.png\" border='0'>\n";
          $html .= "			    </a>\n";          
        }
        else
        {
          $html .= "			    <a href=\"javascript:AsociarCXP('".$ordenes['eps_orden_servicio']."')\" class=\"label_error\"  title=\"ASOCIAR ORDEN CON LA CUENTA\">\n";
          $html .= "			      <img src=\"".GetThemePath()."/images/checkno.png\" border='0'>\n";
          $html .= "			    </a>\n";
        }
        
        $html .= "			  </div>\n";
        $html .= "			</td>\n";
        $html .= "		</tr>\n";
        $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
        $html .= "  	  <td $sty >PACIENTE</td>\n";
        $html .= "	    <td $sty class=\"modulo_list_claro\" >".$ordenes['tipo_id_paciente']." ".$ordenes['paciente_id']."</td>\n";
        $html .= "			<td $sty class=\"modulo_list_claro\" colspan=\"2\">".trim($ordenes['primer_nombre']." ".$ordenes['segundo_nombre']." ".$ordenes['primer_apellido']." ".$ordenes['segundo_apellido'])."</td>\n";
        $html .= "  	  <td $sty >ESTAMENTO</td>\n";
        $html .= "	    <td $sty class=\"modulo_list_claro\" >".$ordenes['descripcion_estamento']."</td>\n";
        $html .= "		</tr>\n";
        
        if($ordenes['observacion'])
        {
          $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
          $html .= "	    <td colspan=\"7\">OSERVACION</td>\n";
          $html .= "		</tr>\n";
          $html .= "	  <tr align=\"justify\" class=\"modulo_table_list\">\n";
          $html .= "	    <td colspan=\"7\">".$ordenes['observacion']."</td>\n";
          $html .= "		</tr>\n";
        }
        
        if(!empty($detalle[$key1]['cargos']))
        {
          $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
          $html .= "	    <td colspan=\"7\">CARGOS AUTORIZADOS</td>\n";
          $html .= "		</tr>\n";
          $html .= "	  <tr align=\"center\">\n";
          $html .= "	    <td colspan=\"7\">\n";
          $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $html .= "          <tr class=\"modulo_table_list_title\">\n";
          $html .= "	          <td width=\"10%\">TARIFARIO</td>\n";
          $html .= "		        <td width=\"10%\">CARGO</td>\n";
          $html .= "		        <td >DESCRIPCION</td>\n";
          $html .= "		        <td width=\"10%\">CANTIDAD</td>\n";
          $html .= "		        <td width=\"10%\">VALOR U</td>\n";
          $html .= "		        <td width=\"10%\">TOTAL</td>\n";
          $html .= "		        <td width=\"1%\"></td>\n";
          $html .= "	        </tr>\n";
          foreach($detalle[$key1]['cargos'] as $kc => $dtl_cargos)
          {
            foreach($dtl_cargos as $kc1=> $dtl)
            {
              $html1 .= "  <tr class=\"modulo_list_claro\">\n";
              $html1 .= "    <td>".$dtl['tarifario_id']."</td>\n";
              $html1 .= "    <td>".$dtl['cargo']."</td>\n";
              $html1 .= "    <td align=\"justify\">".$dtl['descripcion_equivalencia']."</td>\n";
              $html1 .= "		 <td>".$dtl['cantidad']."</td>\n";
              $html1 .= "		 <td align=\"right\">$".formatoValor($dtl['valor'])."</td>\n";
              $html1 .= "		 <td align=\"right\">$".formatoValor($dtl['valor']*$dtl['cantidad'])."</td>\n";
              $html1 .= "		 <td>\n";
              $html1 .= "		  <div id=\"divcrg_".$dtl['eps_orden_servicio_cargo']."\">\n";
              if($dtl['marca'] == '1')
              {  
                $html1 .= " 				  <a href=\"javascript:DesvincularDetalleCargo('".$ordenes['eps_orden_servicio']."','".$dtl['eps_orden_servicio_cargo']."','".$dtl['cxp_detalle_factura_id']."','".$kc."','".$dtl['valor']."',document.getElementById('hdd_".$dtl['cxp_detalle_factura_id']."').value)\" class=\"label_error\"  title=\"DESVINCULAR ORDEN DE LA CUENTA\">\n";
                $html1 .= "            <img src=\"".GetThemePath()."/images/checksi.png\" border='0'>\n";
                $html1 .= " 				  </a>\n";          
              }
              else
              {
                if($dtl['marca'] == '0')
                {
                  $html1 .= " 				  <a href=\"javascript:AsociarDetalleCargo('".$ordenes['eps_orden_servicio']."','".$dtl['eps_orden_servicio_cargo']."','".$kc."','".$dtl['valor']."')\" class=\"label_error\"  title=\"ASOCIAR ORDEN CON LA CUENTA\">\n";
                  $html1 .= "            <img src=\"".GetThemePath()."/images/checkno.png\" border='0'>\n";
                  $html1 .= " 				  </a>\n";
                }
                else
                {
                  $html1 .= "<img src=\"".GetThemePath()."/images/infor.png\" onclick=\"alert('";
                  $html1 .= "EL CARGO CUPS: ".$kc." YA ESTA ASOCIADO A UNA CUENTA";
                  $html1 .= "')\">\n";
                }
              }
              $html1 .= "		  </div>\n";
              $html1 .= "		 </td>\n";
              $html1 .= "  </tr>\n";
            }
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "    <td width=\"%\" colspan=\"7\">CUPS: ".$kc." ".$dtl['descripcion_base']."</td>\n";
            $html .= "  </tr>\n";
            $html .= $html1;
            $html1 = "";
          }
          $html .= "        </table>\n";
          $html .= "		  </td>\n";
          $html .= "		</tr>\n";
        }
        
        if(!empty($detalle[$key1]['medicamentos']))
        {
          $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
          $html .= "	    <td colspan=\"7\">MEDICAMENTOS AUTORIZADOS</td>\n";
          $html .= "		</tr>\n";
          $html .= "	  <tr align=\"center\">\n";
          $html .= "	    <td colspan=\"7\">\n";
          $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $html .= "          <tr class=\"modulo_table_list_title\">\n";
          $html .= "	          <td width=\"20%\">CODIGO</td>\n";
          $html .= "		        <td >DESCRIPCION</td>\n";
          $html .= "		        <td width=\"10%\">CANTIDAD</td>\n";
          $html .= "		        <td width=\"10%\">VALOR U</td>\n";
          $html .= "		        <td width=\"10%\">TOTAL</td>\n";
          $html .= "		        <td width=\"1%\"></td>\n";
          $html .= "	        </tr>\n";            
          foreach($detalle[$key1]['medicamentos'] as $kc => $dtl)
          {
            $html .= "	        <tr class=\"modulo_list_claro\">\n";
            $html .= "            <td>".$dtl['codigo_producto']."</td>\n";
            $html .= "            <td align=\"justify\">".$dtl['descripcion_producto']."</td>\n";
            $html .= "		        <td>".$dtl['cantidad']."</td>\n";
            $html .= "		        <td align=\"right\">$".formatoValor($dtl['valor'])."</td>\n";
            $html .= "		        <td align=\"right\">$".formatoValor($dtl['valor'] * $dtl['cantidad'])."</td>\n";
            $html .= "		        <td>\n";
            $html .= "		          <div id=\"divmed_".$dtl['eps_orden_servicio_medicamento']."\">\n";
            if($dtl['marca'] == '1')
            {  
              $html .= " 				        <a href=\"javascript:DesvincularDetalleMedicamento('".$ordenes['eps_orden_servicio']."','".$dtl['eps_orden_servicio_medicamento']."','".$dtl['cxp_detalle_factura_id']."','".$dtl['codigo_producto']."','".$dtl['valor']."',document.getElementById('hdd_".$dtl['cxp_detalle_factura_id']."').value,'".$dtl['codigo_producto']."')\" class=\"label_error\"  title=\"DESVINCULAR ORDEN DE LA CUENTA\">\n";
              $html .= "                  <img src=\"".GetThemePath()."/images/checksi.png\" border='0'>\n";
              $html .= " 				        </a>\n";          
            }
            else
            {
              if($dtl['marca'] == '0')
              {
                $html .= " 				      <a href=\"javascript:AsociarDetalleMedicamento('".$ordenes['eps_orden_servicio']."','".$dtl['eps_orden_servicio_medicamento']."','".$dtl['codigo_producto']."','".$dtl['valor']."')\" class=\"label_error\"  title=\"ASOCIAR MEDICAMENTO CON LA CUENTA\">\n";
                $html .= "                <img src=\"".GetThemePath()."/images/checkno.png\" border='0'>\n";
                $html .= " 				      </a>\n";
              }
              else
              {
                $html .= "          <img src=\"".GetThemePath()."/images/infor.png\" onclick=\"alert('";
                $html .= "EL MEDICAMENTO: ".$kc." YA ESTA ASOCIADO A UNA CUENTA";
                $html .= "')\">\n";
              }
            }
            $html .= "		          </div>\n";
            $html .= "		        </td>\n";
            $html .= "	        </tr>\n";
          }
          $html .= "        </table>\n";
          $html .= "		  </td>\n";
          $html .= "		</tr>\n";
        }
        
        if(!empty($detalle[$key1]['conceptos']))
        {
          $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
          $html .= "	    <td colspan=\"7\">CONCEPTOS AUTORIZADOS</td>\n";
          $html .= "		</tr>\n";
          $html .= "	  <tr align=\"center\">\n";
          $html .= "	    <td colspan=\"7\">\n";
          $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $html .= "          <tr class=\"modulo_table_list_title\">\n";
          $html .= "		        <td >DESCRIPCION</td>\n";
          $html .= "		        <td width=\"10%\">VALOR</td>\n";
          $html .= "	        </tr>\n";            
          foreach($detalle[$key1]['conceptos'] as $kc => $dtl)
          {
            $html .= "	        <tr class=\"modulo_list_claro\">\n";
            $html .= "            <td align=\"justify\">".$dtl['descripcion_concepto_adicional']."</td>\n";
            $html .= "		        <td align=\"right\">$".formatoValor($dtl['valor'])."</td>\n";
            $html .= "	        </tr>\n";
          }
          $html .= "        </table>\n";
          $html .= "		  </td>\n";
          $html .= "		</tr>\n";          
        }
        $html .= "	</table><br>\n";
      }
      
      if($html == "")
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">\n";
        $html .= "    PARA ESTE PROVEEDOR NO EXISTEN ORDENES DE SERVICIO RELACIONADAS EN LA FECHA DEL DOCUMENTO\n";
        $html .= "  </label>\n";
        $html .= "</center>\n";
      }
      return $html;
    }
    /**
    * Funcion donde se muestra la lista de cargos
    * 
    * @param array $action Arreglo de datos con los links de la forma
    * @param array $grupos Arreglo de datos con la informacion de los grupos de cargos
    * @param array $cargos Arreglo de datos con la informacion de los cargos buscados
    * @param array $request Arreglo de datos con la informacion del request
    * @param string $conteo Cadena que indica la cantida de registros que salen de la busqueda
    * @param string $pagina Pagina que se muestra actualmente en pantalla
    *
    * @return String
    */
    function FormaListarCargos($action,$grupos,$cargos,$request,$conteo,$pagina)
    {
      $stl = "style=\"text-align:left;text-indent:2pt\"";
      $html  = "<center>\n";
			$html .= "	<form name=\"buscarc\" action=\"javascript:Buscar('buscarc','c')\" id=\"buscarc\" method=\"post\">\n";
			$html .= "		<table align=\"center\" border=\"0\" width=\"85%\" class=\"modulo_table_list\">\n";
			$html .= "			<tr class=\"formulacion_table_list\">";
			$html .= "				<td $stl width=\"20%\">CARGO:</td>\n";
			$html .= "				<td $stl class=\"modulo_list_claro\" width=\"30%\">\n";
			$html .= "					<input type=\"text\" class=\"input-text\" name=\"cargo\" style=\"width:90%\" value=\"".$request['cargo']."\">\n";
			$html .= "				</td>\n";			
			$html .= "				<td $stl width=\"20%\">GRUPOS</td>\n";
			$html .= "				<td $stl class=\"modulo_list_claro\" width=\"20%\">\n";
			$html .= "					<select name=\"grupo_tipo_cargo\" class =\"select\">\n";
			$html .= "						<option value=\"-1\">TODOS</option>\n";
      $ck = "";
      foreach($grupos as $key => $dtll)
      {
        ($request['grupo_tipo_cargo'] == $dtll['grupo_tipo_cargo'])? $ck = "selected": $ck = "";
        $html .= "						<option value=\"".$dtll['grupo_tipo_cargo']."\" $ck>".$dtll['descripcion']."</option>\n";
      }
			$html .= "					</select>\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr class=\"formulacion_table_list\">";
			$html .= "				<td $stl>DESCRIPCION:</td>";
			$html .= "				<td $stl class=\"modulo_list_claro\" colspan=\"2\">\n";
			$html .= "					<input type=\"text\" class=\"input-text\" name=\"descripcion\" style=\"width:90%\" value=\"".$request['descripcion']."\">\n";
			$html .= "				</td>\n";
			$html .= "				<td align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "					<input class=\"input-submit\" name=\"aceptar\" type=\"submit\" value=\"Buscar\">&nbsp;&nbsp;&nbsp;\n";
			$html .= "					<input class=\"input-submit\" name=\"limpiar\" type=\"button\" value=\"Limpiar Campos\" onclick=\"LimpiarCampos(document.buscarc)\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "		</table>\n";
			$html .= "	</form>\n";
			$html .= "</center>\n";

      if(!empty($cargos))
      {
        $pghtml = AutoCarga::factory("ClaseHTML");
        $html .= $pghtml->ObtenerPaginadoXajax($conteo,$pagina,$action['paginador']);        
        $est = "modulo_list_claro"; $back = "#DDDDDD";
        
  			$html .= "<table align=\"center\" class=\"modulo_table_list\" width=\"98%\">";
  			$html .= "	<tr class=\"modulo_table_list_title\">\n";
  			$html .= "  	<td width=\"15%\">TIPO APOYO</td>\n";
  			$html .= "  	<td width=\"10%\">CARGO</td>\n";
  			$html .= "  	<td width=\"%\">DESCRIPCION</td>\n";
  			$html .= "  	<td width=\"2%\" ></td>\n";
  			$html .= "	</tr>\n";
        foreach($cargos as $key => $dtl)
        {
          ($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro"; 
          ($back == "#DDDDDD")? $back = "#CCCCCC":$back = "#DDDDDD";
          
          $html .= "	<tr class=\"$est\" onmouseout=mOut(this,\"".$back."\"); onmouseover=mOvr(this,'#FFFFFF'); >\n";
  				$html .= "  	<td class=\"normal_10AN\">".$dtl['tipo']."</td>\n";
  				$html .= "  	<td align=\"center\" class=\"normal_10AN\">".$dtl['cargo']."</td>\n";
  				$html .= "		<td class=\"label\">".$dtl['descripcion']."</td>\n";
          $html .= "		<td>\n";
  				$html .= "			<a href=\"javascript:Agregar('".$dtl['cargo']."','c')\" title=\"ADICIONAR CARGOS\">\n";
  				$html .= "				<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
  				$html .= "			</a>\n";
  				$html .= "		</td>\n";
  				$html .= "	</tr>\n";
        }
        $html .= "</table><br>\n";
        $html .= $pghtml->ObtenerPaginadoXajax($conteo,$pagina,$action['paginador'],true);
      }
      else if($request['aceptar'])
      {
        $html .= "  <center>\n";
        $html .= "    <label class=\"label_error\">NO SE ENCONTRARON CARGOS PARA SER ADICIONADOS</label>\n";
        $html .= "  </center>\n";
      }
      return $html;
    }
    /**
    * Funcion donde se muestra la lista de cargos
    * 
    * @param array $action Arreglo de datos con los links de la forma
    * @param array $medica Arreglo de datos con la informacion de los medicamentos buscados
    * @param array $request Arreglo de datos con la informacion del request
    * @param string $conteo Cadena que indica la cantida de registros que salen de la busqueda
    * @param string $pagina Pagina que se muestra actualmente en pantalla
    *
    * @return String
    */
    function FormaListarMedicamentos($action,$medica,$request,$conteo,$pagina)
    {
      $stl = "style=\"text-align:left;text-indent:2pt\"";
      $html  = "<center>\n";
			$html .= "	<form name=\"buscarII\" id=\"buscarII\" action=\"javascript:Buscar('buscarII','m')\" method=\"post\">\n";
      $html .= "	  <table align=\"center\" border=\"0\" width=\"85%\" class=\"modulo_table_list\">\n";
      $html .= "		  <tr $stl class=\"formulacion_table_list\">\n";
      $html .= "			  <td width=\"10%\">CODIGO:</td>\n";
      $html .= "			  <td width=\"25%\" class=\"modulo_list_claro\">\n";
      $html .= "				  <input type=\"text\" class=\"input-text\" style=\"width:90%\" name =\"codigo\" value=\"".$request['codigo']."\">\n";
      $html .= "			  </td>\n";
      $html .= "			  <td width=\"20%\">PRINCIPIO ACTIVO:</td>";
      $html .= "			  <td width=\"%\" class=\"modulo_list_claro\" >\n";
      $html .= "				  <input type=\"text\" class=\"input-text\" style=\"width:90%\" name=\"principio_activo\" value=\"".$request['principio_activo']."\">\n";
      $html .= "			  </td>\n" ;
      $html .= "			</tr>\n" ;
			$html .= "			<tr $stl class=\"formulacion_table_list\">";
			$html .= "				<td >DESCRIPCION:</td>";
			$html .= "				<td  class=\"modulo_list_claro\" colspan=\"2\">\n";
			$html .= "					<input type=\"text\" class=\"input-text\" name=\"descripcion\" style=\"width:90%\" value=\"".$request['descripcion']."\">\n";
			$html .= "				</td>\n";
			$html .= "				<td align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "					<input class=\"input-submit\" name=\"buscar\" type=\"submit\" value=\"Buscar\" >&nbsp;&nbsp;&nbsp;\n";
			$html .= "					<input class=\"input-submit\" name=\"limpiar\" type=\"button\" value=\"Limpiar Campos\" onclick=\"LimpiarCampos(document.buscarII)\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
      $html .= "	  </table>\n";
      $html .= "	</form>\n";
			$html .= "</center>\n";

      if(!empty($medica))
      {
  			$pghtml = AutoCarga::factory('ClaseHTML');
  			
        $html .= "<br>\n";			
  			$html .= $pghtml->ObtenerPaginadoXajax($slm->conteo,$slm->pagina,$action);
  			
  			$html .= "<table align=\"center\" class=\"modulo_table_list\" width=\"98%\">\n";
  			$html .= "	<tr class=\"modulo_table_list_title\">\n";
  			$html .= "  	<td width=\"8%\" ></td>\n";
        $html .= "  	<td width=\"10%\">CODIGO</td>\n";
        $html .= "  	<td width=\"20%\">PRINCIPIO ACTIVO</td>\n";
        $html .= "  	<td width=\"%\" >DESCRIPCION</td>\n";
  			$html .= "  	<td width=\"2%\" ></td>\n";
  			$html .= "	</tr>\n";
  			foreach($medica as $key => $rst)
  			{
  				($est == "modulo_list_claro")? $est = "modulo_list_oscuro": $est = "modulo_list_claro";
  				
  				$html .= "	<tr class=\"".$est."\">\n";
  				$html .= "  	<td align=\"center\" class=\"normal_10AN\">".$rst['item']."</td>\n";
  				$html .= "  	<td align=\"center\" class=\"normal_10AN\">".$rst['codigo_producto']."</td>\n";
  				$html .= "		<td class=\"label\">".$rst['principio_activo']."</td>\n";
  				$html .= "  	<td class=\"label\">".$rst['descripcion_producto']."</td>\n";
          $html .= "		<td>\n";
  				$html .= "			<a href=\"javascript:Agregar('".$rst['codigo_producto']."','m')\" title=\"ADICIONAR CARGOS\">\n";
  				$html .= "				<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
  				$html .= "			</a>\n";
  				$html .= "		</td>\n";
  				$html .= "	</tr>\n";
  			}
  			$html .= "</table>\n";
  			$html .= $pghtml->ObtenerPaginadoXajax($slm->conteo,$slm->pagina,$action,true);
  			$html .= "<br>\n";
  		}			
  		else if($request['buscar'])
  		{
        $html .= "  <center>\n";
  			$html .= "    <label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
        $html .= "  </center>\n";
  		}
      return $html;
    }
    /**
    *
    */
    function FormaHistoricoEstados($historico)
    {
      $html = "";
      if(!empty($historico))
      {
        $html .= "<table width=\"70%\" align=\"center\">\n";
        $html .= "  <tr align=\"center\">\n";
        $html .= "	  <td colspan=\"7\">\n";
        $html .= "	    <fieldset class=\"fieldset\">\n";
        $html .= "	      <legend class=\"normal_10AN\">HISTORICO DE ESTADOS</legend>\n";
        $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "          <tr class=\"formulacion_table_list\">\n";
        $html .= "	          <td width=\"30%\">ESTADO ACTUAL</td>\n";
        $html .= "		        <td width=\"30%\">ESTADO ANTERIOR</td>\n";
        $html .= "		        <td width=\"15%\">REGISTRO</td>\n";
        $html .= "		        <td width=\"%\">RESPONSABLE</td>\n";
        $html .= "	        </tr>\n";
        
        $est = 'modulo_list_oscuro';
        foreach($historico as $key => $dtl)
        {
          ($est == "modulo_list_oscuro")? $est = "modulo_list_claro": $est = "modulo_list_oscuro";
          $html .= "          <tr class=\"".$est."\">\n";
          $html .= "	          <td >".$dtl['estado_actual']."</td>\n";
          $html .= "		        <td >".$dtl['estado_anterior']."</td>\n";
          $html .= "		        <td >".$dtl['fecha_registro']."</td>\n";
          $html .= "		        <td >".$dtl['nombre']."</td>\n";
          $html .= "	        </tr>\n";
        }
        $html .= "	      </table>\n";
        $html .= "	    </filedset>\n";
        $html .= "	  </td>\n";
        $html .= "	</tr>\n";
        $html .= "</table>\n";
      }
      return $html;
    }
  }
?>