<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: CrearNotasHTML
  * Clase Contiene La Interfaz para la creacion de Notas Deb
  *
  * @package IPSOFT-SIIS
  * @version $Revision:
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class CrearNotasHTML
	{
		/**
		* Constructor de la clase
		*/
		function CrearNotasHTML(){}
		 
     
		function main($action,$request,$SelectTipoId,$documentos)
		{
    /*
    Seccion de Funciones Javascript
    */
    $html .="<script>";
    $html .="function Paginador(TipoIdTercero,TerceroId,Descripcion,Empresa_Id,offset)";
    $html .="{";
    $html .="xajax_Listar_TercerosProveedores(TipoIdTercero,TerceroId,Descripcion,Empresa_Id,offset);";
    $html .="}";
    $html .="</script>";
	
	$html .="<script>";
	$html .="function Validar(Formulario)";
	$html .="{";
	$html .="			if (Formulario.justificacion ==\"\")";
	$html .="			{";
	$html .="			alert('Es Necesario Justificar La Anulacion de La Nota');";
	$html .="			return false;";
	$html .="			}";
	$html .="";
	$html .="			xajax_AplicarAnulacionNota(Formulario);";
	$html .="}";
	$html .="</script>";
	
    
    /*
    Fin de Funciones JavaScript
    */
	
    $accion=$action['volver'];
		$html .= ThemeAbrirTabla('NOTAS DEBITO - CREDITO: FACTURAS DESPACHO');
		$html .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$html .= "  <tr><td>";
		$html .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"6\">";
		$html .= "      BUSCAR PROVEEDORES";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      Tipo Id";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$SelectTipoId;
		$html .= "      </td>";
		
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      Tercero Id";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <input type=\"text\" class=\"input-text\" id=\"tercero_id\">";
		$html .= "      </td>";
		
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      Nombre Tercero";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <input type=\"text\" class=\"input-text\" id=\"nombre_tercero\">";
		$html .= "      </td>";
		$html .= "      </tr>";
	
    $html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"6\">";
		$html .= "      <input type=\"button\" value=\"Buscar Tercero\" onclick=\"xajax_Listar_TercerosProveedores(document.getElementById('tipo_id_tercero').value,document.getElementById('tercero_id').value,document.getElementById('nombre_tercero').value,'".$_REQUEST['datos']['empresa_id']."','1');\" class=\"modulo_table_list\">";
		$html .= "      </td>";
		$html .= "      </tr>";
    
		$html .= "      </table>";
    
     $html .= "      <div id=\"ListadoTercerosProveedores\"></div>";
		$html .= "  </td></tr>";
		$html .= ' 	<form name="forma" action="'.$action['volver'].'" method="post">';
		$html .= "  <tr>";
		$html .= "  <td align=\"center\"><br>";
		$html .= '  <input class="input-submit" type="submit" name="volver" value="Volver">';
		$html .= "  </td>";
		$html .= "  </form>";
		$html .= "  </tr>";
		$html .= "  </table>";
		$html .= ThemeCerrarTabla();
		/*
    Llamados a Funciones Xajax
    */
    $html .="<script>";
    $html .="xajax_Listar_TercerosProveedores('','','','".$_REQUEST['datos']['empresa_id']."','1');";
    $html .="</script>";
    $html .=$this->CrearVentana(700,"NOTAS DEBITO - CREDITO: FACTURAS DE PROVEEDORES");
	SessionSetVar("credito",$documentos[0]['documento_id_credito']);
	SessionSetVar("debito",$documentos[0]['documento_id_debito']);
	
	//print_r($_REQUEST);
		return $html;
	
		}
    
    
    function NotasFacturasProveedor($action,$request,$Tercero,$Documento,$Factura,$doc_nota_tmp_id)
		{
    //$request=$_REQUEST;
   //print_r($request);
    
	/*
    Seccion de Funciones Javascript
    */
    $html .="<script>";
    $html .="function Paginador(TipoIdTercero,TerceroId,Descripcion,Empresa_Id,offset)";
    $html .="{";
    $html .="xajax_Listar_TercerosProveedores(TipoIdTercero,TerceroId,Descripcion,Empresa_Id,offset);";
    $html .="}";
    $html .="</script>";
    
	$html .="<script>";
	$html .="function acceptNum(evt)";
	$html .="{ ";
	$html .="var nav4 = window.Event ? true : false;";
	$html .="var key = nav4 ? evt.which : evt.keyCode;";
	$html .="return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);";
	$html .="}";
    $html .="</script>";
	
	$html .="<script>";
    $html .="function ProductoSeleccionado(CodigoProducto,Descripcion,Lote)";
    $html .="{";
    $html .="document.getElementById('NombreProducto').innerHTML=Descripcion;";
	$html .="document.getElementById('codigo_producto').value=CodigoProducto;";
	$html .="document.getElementById('lote').value=Lote;";
	$html .="OcultarSpan();";
    $html .="}";
    $html .="</script>";
	
	$html .="<script>";
    $html .="function QuitarProductoSeleccionado()";
    $html .="{";
    $html .="document.getElementById('NombreProducto').innerHTML='';";
	$html .="document.getElementById('codigo_producto').value='';";
	$html .="document.getElementById('lote').value='';";
	$html .="OcultarSpan();";
    $html .="}";
    $html .="</script>";
	
	$html .="<script>";
    $html .="function CrearDocumento(EmpresaId,Prefijo,Numeracion,Opc)";
    $html .="{";
	$html .="xajax_CrearDocumento(EmpresaId,Prefijo,Numeracion,Opc);";
    //$html .="alert(\"vaya\");";
    $html .="}";
    $html .="</script>";
    /*
    Fin de Funciones JavaScript
    */
	
    $accion=$action['volver'];
		$html .= ThemeAbrirTabla($Documento[0]['descripcion']);
    
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$html .= "  <tr><td>";
		$html .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"12\">";
		$html .= "      APLICAR NOTA A FACTURA #".$request['numero_factura'];
		$html .= "      </td>";
		$html .= "      </tr>";
    
    if($Tercero[0]['tipo_id_tercero']=='NIT')
        {
        $dv="-".$Tercero[0]['dv'];
        }
        else
          {
          $dv="";
          }
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      TIPO ID";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$Tercero[0]['tipo_id_tercero'];
		$html .= "      </td>";
		
		$html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      TERCERO ID";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$Tercero[0]['tercero_id']."".$dv;
		$html .= "      </td>";
		
		$html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      NOMBRE";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$Tercero[0]['nombre_tercero'];
		$html .= "      </td>";
    
    $html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      TELEFONO";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$Tercero[0]['telefono'];
		$html .= "      </td>";
    
    $html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      DIRECCION";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$Tercero[0]['direccion'];
		$html .= "      </td>";
    
    $html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      PAIS";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$Tercero[0]['pais'];
		$html .= "      </td>";
		$html .= "      </tr>";
	
  /*
  * Segundo Renglon
  */
    $html .= "      <tr>";
    $html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      DOCUMENTO";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"3\">";
		$html .= "      ".$Documento[0]['descripcion'];
		$html .= "      </td>";
		
		$html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      DOC. TEMP ID";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$doc_nota_tmp_id;
		$html .= "      </td>";
		
	    
    $html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      VALOR NOTA:";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <div id=\"valor_notica\"></div>";
		$html .= "      </td>";
    
    
		$html .= "      </tr>";
    
  /*
  * Tercer Renglon
  */
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      FACTURA #";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\" >";
		$html .= "      ".$Factura[0]['prefijo']."-".$Factura[0]['numero'];
		$html .= "      </td>";
		
		$html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      FECHA FACTURA";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$Factura[0]['fecha_registro'];
		$html .= "      </td>";
		
		//print_r($request);
    
		$html .= "      </tr>";
	
    $html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"12\">";
		$html .= "		<input type=\"hidden\" id=\"numero\" value=\"".$request['numero']."\">";
    $html .= "		<input type=\"hidden\" id=\"prefijo\" value=\"".$request['prefijo']."\">";
    $html .= "		<input type=\"hidden\" id=\"tipo_nota\" value=\"".$request['tipo_nota']."\">";
		
		
		$html .= "		<input type=\"hidden\" id=\"valor_nota\" value=\"\" >";
		$html .= "      </td>";                                                                                                                   // $TipoIdTercero,$TerceroId,$ValorNota,$NumeroFactura,$DocumentoId,$Prefijo,$Numeracion,$EmpresaId
    $html .= "      </tr>";
    
    
		$html .= "      </table>";
    
     
		$html .= "  </td></tr>";
		$html .= ' 	<form name="forma" action="'.$action['volver'].'" method="post">';
		$html .= "  <tr>";
		$html .= "  <td align=\"center\">";
		$html .= '  <input class="input-submit" type="submit" name="volver" value="VOLVER"><input class="input-submit" type="button" id="crear_documento" value="CREAR DOCUMENTO" onclick="xajax_CrearDocumento(\''.$request['datos']['empresa_id'].'\',\''.$doc_nota_tmp_id.'\',document.getElementById(\'valor_nota\').value);">';
		$html .= "  </td>";
		$html .= "  </form>";
		$html .= "  </tr>";
		$html .= "  </table>";
				
		$html .= "<div id=\"DocumentoCreado\">";
		$html .= "	<center>";
		$html .= "	<a href=\"#\" class=\"label_error\" onclick=\"xajax_FormDetalleNota('".$request['datos']['empresa_id']."','".$doc_nota_tmp_id."');\">Adicionar Concepto Nota</a>";
		$html .= "  </center>";
		$html .= "<div id=\"NotaCreada\"></div>";
		$html .= "<div id=\"DetallesNota\"></div>";
		$html .= "</div>";
		$html .= ThemeCerrarTabla();
		$html .=$this->CrearVentana(700,"PRODUCTOS");
		
		
		$html .="<script>";
	    $html .="xajax_NotaDetalles('".$_REQUEST['datos']['empresa_id']."','".$doc_nota_tmp_id."');";
	    $html .="</script>";
		return $html;
		
	
		}
 
   
     // CREAR LA CAPITA
	 function CrearVentana($tmn,$Titulo)
  {
    $html .= "<script>\n";
    $html .= "  var contenedor = 'Contenedor';\n";
    $html .= "  var titulo = 'titulo';\n";
    $html .= "  var hiZ = 4;\n";
    $html .= "  function OcultarSpan()\n";
    $html .= "  { \n";
    $html .= "    try\n";
    $html .= "    {\n";
    $html .= "      e = xGetElementById('Contenedor');\n";
    $html .= "      e.style.display = \"none\";\n";
    $html .= "    }\n";
    $html .= "    catch(error){}\n";
    $html .= "  }\n";
    //Mostrar Span
  $html .= "  function MostrarSpan()\n";
    $html .= "  { \n";
    $html .= "    try\n";
    $html .= "    {\n";
    $html .= "      e = xGetElementById('Contenedor');\n";
    $html .= "      e.style.display = \"\";\n";
    $html .= "      Iniciar();\n";
    $html .= "    }\n";
    $html .= "    catch(error){alert(error)}\n";
    $html .= "  }\n";

    $html .= "  function MostrarTitle(Seccion)\n";
    $html .= "  {\n";
    $html .= "    xShow(Seccion);\n";
    $html .= "  }\n";
    $html .= "  function OcultarTitle(Seccion)\n";
    $html .= "  {\n";
    $html .= "    xHide(Seccion);\n";
    $html .= "  }\n";

    $html .= "  function Iniciar()\n";
    $html .= "  {\n";
    $html .= "    contenedor = 'Contenedor';\n";
    $html .= "    titulo = 'titulo';\n";
    $html .= "    ele = xGetElementById('Contenido');\n";
    $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
    $html .= "    ele = xGetElementById(contenedor);\n";
    $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
    $html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
    $html .= "    ele = xGetElementById(titulo);\n";
    $html .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
    $html .= "    xMoveTo(ele, 0, 0);\n";
    $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $html .= "    ele = xGetElementById('cerrar');\n";
    $html .= "    xResizeTo(ele,20, 20);\n";
    $html .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
    $html .= "  }\n";

    $html .= "  function myOnDragStart(ele, mx, my)\n";
    $html .= "  {\n";
    $html .= "    window.status = '';\n";
    $html .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
    $html .= "    else xZIndex(ele, hiZ++);\n";
    $html .= "    ele.myTotalMX = 0;\n";
    $html .= "    ele.myTotalMY = 0;\n";
    $html .= "  }\n";
    $html .= "  function myOnDrag(ele, mdx, mdy)\n";
    $html .= "  {\n";
    $html .= "    if (ele.id == titulo) {\n";
    $html .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
    $html .= "    }\n";
    $html .= "    else {\n";
    $html .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
    $html .= "    }  \n";
    $html .= "    ele.myTotalMX += mdx;\n";
    $html .= "    ele.myTotalMY += mdy;\n";
    $html .= "  }\n";
    $html .= "  function myOnDragEnd(ele, mx, my)\n";
    $html .= "  {\n";
    $html .= "  }\n";
    
    
    $html.= "function Cerrar(Elemento)\n";
         $html.= "{\n";
         $html.= "    capita = xGetElementById(Elemento);\n";
         $html.= "    capita.style.display = \"none\";\n";
         $html.= "}\n";
    
    
    
    $html .= "</script>\n";
    $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
    $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
    $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
    $html .= "  <div id='Contenido' class='d2Content'>\n";
    //En ese espacio se visualiza la informacion extraida de la base de datos.
    $html .= "  </div>\n";
    $html .= "</div>\n";


  
    return $html;
  } 
   
 
	}
?>