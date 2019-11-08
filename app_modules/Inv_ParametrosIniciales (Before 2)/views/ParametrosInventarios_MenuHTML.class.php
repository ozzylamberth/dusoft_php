<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: ParametrosInventarios_MenuHTML
  * Clase Contiene Metodos para el Ingreso de Parametros Iniciales de Inventario
  *
  * @package IPSOFT-SIIS
  * @version $Revision:
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class ParametrosInventarios_MenuHTML
	{
		/**
		* Constructor de la clase
		*/
		function ParametrosInventarios_MenuHTML(){}
		 
     
		function Menu($action)
		{
		$accion=$action['volver'];
		$html  = ThemeAbrirTabla('PARAMETROS INICIALES - INVENTARIOS');
		$html .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$html .= "  <tr><td>";
		$html .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\">";
		$html .= "      MENÚ";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','ParametrizarEmpresas') ."\">PARAMETRIZAR EMPRESAS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
		
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','CrearPerfilesTerapeuticos') ."\">CREAR PERFILES TERAPEUTICOS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','CrearEstadosDocumentos') ."\">CREAR ESTADOS DE DOCUMENTOS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
		
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','AsignarEstadosUsuariosBodega') ."\">ASIGNAR ESTADOS A USUARIOS DE BODEGA</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','CrearCondicionesCompra') ."\">CREAR CONDICIONES - COMPRA</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','CrearNovedadesDevolucionProductos') ."\">CREAR NOVEDADES- DEVOLUCION PRODUCTOS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    
    /*$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','CrearTiposSalidasProductos') ."\">CREAR TIPOS- SALIDAS PRODUCTOS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";*/
		

    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','CrearBancos') ."\">REGISTRAR BANCOS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','CrearMensajeSistema') ."\">CREAR MENSAJES DEL SISTEMA</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    /*
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','CrearTiposDispensacion') ."\">CREAR TIPOS DE DISPENSACION</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    */
    /*
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','AsignarTopesDispensacionFarmacias') ."\">ASIGNACION DE TOPES DE DISPENSACION POR FARMACIA</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    */
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','CrearTiposBloqueos') ."\">CREAR TIPOS DE BLOQUEOS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','BloquearClientes') ."\">BLOQUEAR CLIENTES</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','DefinirCostosDeVentaProductos') ."\">DEFINIR % COSTO DE VENTA DE PRODUCTOS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','AsignarCondicionesDeDevolucionProductosXLaboratorio') ."\">POLITICAS DE VENCIMIENTO POR LABORATORIO (PROVEEDOR)</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    /*$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','Menu_DefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica') ."\">DEFINIR TIEMPOS DE ENTREGA DE MEDICAMENTOS DE FORMULA MEDICA POR FARMACIA</a>";
		$html .= "      </td>";
		$html .= "      </tr>";*/
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','DefinirProductosRequierenAutorizacionDespachosPedidos') ."\">DEFINIR PRODUCTOS QUE REQUIERAN AUTORIZACION PARA DESPACHOS Y PEDIDOS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','Menu_CrearRutasDeViajes') ."\">CREAR RUTAS DE VIAJES, PARAMETRIZANDO POR ZONAS O REGIONALES</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','EstadosDocumentos') ."\">PARAMETRIZACION ESTADOS TIPOS DE DOCUMENTOS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    /*$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','ParametrizarPrioridadBodegasDespachos') ."\">PARAMETRIZAR PRIORIDAD DE BODEGAS PARA EL DESPACHO DE PRODUCTOS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";*/
    
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','MenuAsignarDocumentosABodegas') ."\">ASIGNAR DOCUMENTOS A BODEGA</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','MenuAsignarDocumentosBodegasAUsuarios') ."\">ASIGNAR DOCUMENTOS DE BODEGA A USUARIOS (PERMISOS)</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','MenuParametrizarDocumentosPorDepartamentos') ."\">PARAMETRIZAR DOCUMENTOS POR DEPARTAMENTO</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','ProductosConteo') ."\">PARAMETRIZACION PRODUCTOS CONTEO DIARIO</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','Farmacovigilancia') ."\">FARMACOVIGILANCIA</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','BodegasVirtuales') ."\">BODEGAS VIRTUALES</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','TorresProductos') ."\">PARAMETRIZACION DE LAS TORRES PRODUCTOS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','AutorJefes') ."\">PARAMETRIZACION DE JEFES BODEGA-CONTROL INTERNO</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    $html .= "        <tr class=\"modulo_list_claro\">\n";
		$html .= "          <td class=\"label\" align=\"center\">\n";
		$html .= "            <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','DiasEnvios') ."\">PARAMETRIZACION DE DIAS DE ENVIO DE PRODUCTOS</a>";
		$html .= "          </td>\n";
		$html .= "        </tr>\n";
    $html .= "        <tr class=\"modulo_list_claro\">\n";
		$html .= "          <td class=\"label\" align=\"center\">\n";
		$html .= "            <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','UnidadesNegocio') ."\">PARAMETRIZACION DE UNIDADES DE NEGOCIO</a>";
		$html .= "          </td>\n";
		$html .= "        </tr>\n";
		
		$html .= "  </td></tr>";
		$html .= "        <tr class=\"modulo_list_claro\">\n";
		$html .= "          <td class=\"label\" align=\"center\">\n";
		$html .= "            <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','TiposCliente') ."\">PARAMETRIZACION DE TIPOS DE CLIENTE</a>";
		$html .= "          </td>\n";
		$html .= "        </tr>\n";
		$html .= "        <tr class=\"modulo_list_claro\">\n";
		$html .= "          <td class=\"label\" align=\"center\">\n";
		$html .= "            <a href=\"".ModuloGetURL('app','Inv_ParametrosIniciales','controller','Transportadoras') ."\">PARAMETRIZACION DE TRANSPORTADORAS</a>";
		$html .= "          </td>\n";
		$html .= "        </tr>\n";
    	$html .= "      </table>\n";
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
		
		return $html;
	
		}
   
  /**
        * Funcion donde se crea la forma para mostrar todos tipos de documentos para asignar los estados
        * 
        * @param array $documentos vector que contiene la informacion de la consulta de los documentos
         * @param array $estados vector que contiene la informacion de la consulta de los estados
        * @param array $action vector que contiene los link de la aplicacion
        * @param string $empresa_id cadena que contiene la empresa
        * @return string $html retorna la cadena con el codigo html de la pagina
        */
   function formaParametrizacionEstadosDocu($documentos,$estados,$action,$empresa_id)
   {
    $clas = AutoCarga ::factory("ClaseUtil");
    $html .= $clas->RollOverFilas();
    $html .= $clas->AcceptNum();
    $html .= $clas->IsNumeric();
    $mdl = AutoCarga::factory("ParametrizacionEstadosTiposDocumentos","","app","Inv_ParametrosIniciales");
    $html  = ThemeAbrirTabla('PARAMETROS - ESTADOS TIPOS DE DOCUMENTOS');
    $html .= "<form name=\"formParametrizacionEstadosDocu\" id=\"formParametrizacionEstadosDocu\" method=\"post\" action=\"".$action['guardar']."\">\n";
    $html .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
    $html .= "   <tr>";
    $html .= "    <td class=\"formulacion_table_list\"align=\"left\" width=\"25%\">DOCUMENTOS";
    $html .= "   </tr>";
    $selected="";
    $html .= "   <tr class=\"modulo_list_claro\">"; 
    $html .= "    <td align=\"center\" class=\"modulo_list_claro\"><select width=\"100%\" class=\"select\" name=\"estados\" id=\"estados\" onchange=\"MostrarEstadosScri()\">\n";
    $html .= "     <option value=\"-1\">-- Seleccionar --</option>\n";
    $j=1;
    $selected ="";
    foreach ($documentos as $indice=>$valor)
    { 
       $html .= "   <option value=\"".$valor['tipo_doc_general_id']."\">".$valor['tipo_doc_general_id']."</option>";
      $j++;
    }
    $html .= "    </select>\n";
    $html .= "   </td>\n";
    $html .= "   </tr>";
    $html .= "  <tr>\n";
    $html .= "    <td align=\"center\">\n";
    $html .= "      <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "  </table>\n";
    
    $html .= "  <div align=\"center\" class=\"label_error\"id='estadosdoc'></div>";
    
    $html .= "</form>";
    $m=count($estados);
    $html .= "<script>";
    $html .= "  function MostrarEstadosScri()\n";
    $html .= "  {\n";
    $html .= "    frm=document.formParametrizacionEstadosDocu;\n";
    $html .= "      if(frm.estados.value =='-1')\n";
    $html .= "      {\n";
    $html .= "        document.getElementById('estadosdoc').innerHTML='DEBE SELECCIONAR UN TIPO DE DOCUMENTO';\n";
    $html .= "        return;\n";
    $html .= "      }\n";
    $html .= "      else\n";
    $html .= "      {\n";
    $html .= "        xajax_EstadosMod(xajax.getFormValues(formParametrizacionEstadosDocu));\n";
    $html .= "      }\n";
    $html .= "  }\n";
    $html .= " function ValidarDatosProducto()\n";
    $html .= " {\n";
    $html .= "  var checks=document.getElementsByTagName('input');\n";
    $html .= "  var totalChecks=checks.length;\n";
    $html .= "  var totalNoMarcados=0;\n";
    $html .= "  var totalMarcados=0;\n";
    $html .= "  var tipo=document.formParametrizacionEstadosDocu.value;\n";                  
    $html .= "  var cadenaMarc=[];\n" ;
    $html .= "  var cadenitaNoMar=[];\n" ;
    $html .= "  for(var pos=0;pos<totalChecks;pos++)\n" ;
    $html .= "  {\n";
    $html .= "    if(checks[pos].type=='checkbox' && checks[pos].name=='chk_estados')\n";
    $html .= "    {\n";
    $html .= "      if(checks[pos].checked==false)\n";
    $html .= "      {\n";
    $html .= "        totalNoMarcados++;\n";
    $html .= "      }\n";
    $html .= "      else\n";
    $html .= "      {\n";
    $html .= "        if(checks[pos].checked==true)\n";
    $html .= "        {\n";
    $html .= "          totalMarcados++;\n";
    $html .= "        }\n";
    $html .= "      }\n";
    $html .= "    }\n";
    $html .= "  }\n";
    
    for($i=1;$i<=$m;$i++)
    {
      $html .= "   if(document.getElementById('chk_estados".$i."').checked==true)";
      $html .= "   {\n";
      $html .= "      cadenaMarc.push(document.formParametrizacionEstadosDocu.id_estado".$i.".value); ";
      //$html .="alert(document.formParametrizacionEstadosDocu.id_estado".$i.".value);";
      $html .= "   }\n";
      $html .= "   else";
      $html .= "   {\n";
      $html .= "    if(document.getElementById('chk_estados".$i."').checked==false)";
      $html .= "    {\n";
      $html .= "      cadenitaNoMar.push(document.formParametrizacionEstadosDocu.id_estado".$i.".value); ";
      $html .= "    }";
      $html .= "   }";
      //$html .="alert(document.formParametrizacionEstadosDocu.estados.value);";
    }
    //$html .="alert(cadenaMarc);";
    $html .= " xajax_MostrarEstados(totalNoMarcados,totalMarcados,cadenaMarc,cadenitaNoMar,document.formParametrizacionEstadosDocu.estados.value);\n";
    $html .= " }";
    $html .= "</script>";
    $html .= ThemeCerrarTabla();
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
   
   /**
        * Funcion donde se crea la forma para mostrar la parametrizacion del conteo diario de los productos
        * 
        * @param array $action vector que contiene los link de la aplicacion
        * @param array $parametrizacion contiene los datos de la parametrizacion
        * @return string $html retorna la cadena con el codigo html de la pagina
        */
   function formaMenuProdutosConteo($action,$parametrizacion)
   {
    $html  = ThemeAbrirTabla('PARAMETRIZAR PRODUCTOS CONTEO DIARIO');
    $clas = AutoCarga ::factory("ClaseUtil");
    $html .= $clas->RollOverFilas();
    $html .= $clas->AcceptNum();
    $html .= $clas->IsNumeric();
    $html .= "<form name=\"formMenuProductosConteo\" id=\"formMenuProductosConteo\" method=\"post\" action=\"\">\n";
    $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td align=\"center\">MENU\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "  <tr class=\"modulo_list_claro\">\n";
    $html .= "    <td align=\"center\">\n";
    $html .= "      <a href=\"#\" onclick=\"xajax_ProductosConteo()\" class=\"label_error\">PRODUCTOS CONTEO DIARIO</a>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    $html .= "<br>\n";
    //print_r($parametrizacion);
    if($parametrizacion)
    {
      $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
      $html .= "  <tr class=\"modulo_table_list_title\">\n";
      $html .= "    <td align=\"center\">ID\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\">CANTIDAD\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\">MAYOR ROTACION\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\">MAYOR COSTO\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\">DIAS\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\">USUARIO\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\">ACTIVACION\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      foreach ($parametrizacion as $indice=>$valor)
      {
        ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
        ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
              
        $html .= "	<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
        $html .= "      <td id=\"id_paramtomafisica\">".$valor['id_paramtomafisica']."</td>";
        $html .= "      <td>".$valor['cantidad']."</td>";
        if($valor['sw_mayor_rotacion']==1)
         $html .= "      <td>".$valor['sw_mayor_rotacion']."</td>";
        else
         $html .= "      <td> </td>";
        if($valor['sw_mayor_costo']==1)
         $html .= "      <td>".$valor['sw_mayor_costo']."</td>";
        else
         $html .= "      <td> </td>";
        if($valor['sw_lunes']==1)
         $dias=" LUNES";
        else
         $dias="";
        if($valor['sw_martes']==1)
         $dias1=" MARTES";
        else
         $dias1="";
        if($valor['sw_miercoles']==1)
         $dias2=" MIERCOLES";
        else
         $dias2="";
        if($valor['sw_jueves']==1)
         $dias3=" JUEVES";
        else
         $dias3="";
        if($valor['sw_viernes']==1)
         $dias4=" VIERNES";
        else
         $dias4="";
        if($valor['sw_sabado']==1)
         $dias5=" SABADO";
        else
         $dias5="";
        if($valor['sw_domingo']==1)
         $dias6=" DOMINGO";
        else
         $dias6="";
        if($valor['sw_aleatorio']==1)
         $dias7=" ALEATORIO";
        else
         $dias7=""; 
        $html .= "      <td>".$dias.$dias1.$dias2.$dias3.$dias4.$dias5.$dias6.$dias7."</td>";
        $html .= "      <td>".$valor['usuario_id']."</td>";
      
        if($valor['sw_activado']==1)
        {
        $html .= "<td align=\"center\">
                   <a href=\"#\" onclick=\"xajax_GuardarActivado('".$valor['id_paramtomafisica']."','0')\">\n";
        $html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
        }
        else
        {
          $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_GuardarActivado('".$valor['id_paramtomafisica']."','1')\">\n";
          $html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
        }
        $html .= "  </tr>";
      }
     
      $html .= "</table>\n";
    }
    $html .= "<table align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td align=\"center\">\n";
    $html .= "      <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    $html .= "</form>\n";
    $html .= "<script>";
    $html .= " function GuardarActivado(frm)\n";
    $html .= " {\n";
    $html .="alert(frm.activarlo.checked);";
    $html .="alert(document.getElementById('id_paramtomafisica[]'));";
    
    $html .= " }\n";
    $html .= " function GuardarProductosConteo(frm)\n";
    $html .= " {\n";
    $html .= "  cantidad=frm.cantida_p.value;\n";
    $html .= "  lunes=frm.lunes;\n";
    $html .= "  martes=frm.martes;\n";
    $html .= "  miercoles=frm.miercoles;\n";
    $html .= "  jueves=frm.jueves;\n";
    $html .= "  viernes=frm.viernes;\n";
    $html .= "  sabado=frm.sabado;\n";
    $html .= "  domingo=frm.domingo;\n";
    $html .= "  aleatorio=frm.aleatorio;\n";
    $html .= "  mayor_rotacion_g=0;\n";
    $html .= "  if(frm.mayor_rotacion.checked)\n";
    $html .= "  {\n";
    $html .= "    mayor_rotacion_g=1;\n";
    $html .= "  }\n";
    $html .= "  else\n";
    $html .= "  {\n";
    $html .= "    mayor_rotacion_g=0;\n";
    $html .= "  }\n";
    $html .= "    mayor_costo_g=0;\n";
    $html .= "  if(frm.mayor_costo.checked)\n";
    $html .= "  {\n";
    $html .= "    mayor_costo_g=1;\n";
    $html .= "  }\n";
    $html .= "  else\n";
    $html .= "  {\n";
    $html .= "    mayor_costo_g=0;\n";
    $html .= "  }\n";
    $html .= " alert(mayor_costo_g);";
    
    $html .= "  if(lunes.checked)\n";
    $html .= "  {\n";
    $html .= "    lunes_g=1;\n";
    $html .= "  }\n";
    $html .= "  else\n";
    $html .= "  {\n";
    $html .= "    lunes_g=0;\n";
    $html .= "  }\n";
    
    $html .= "  if(martes.checked)\n";
    $html .= "  {\n";
    $html .= "    martes_g=1;\n";
    $html .= "  }\n";
    $html .= "  else\n";
    $html .= "  {\n";
    $html .= "    martes_g=0;\n";
    $html .= "  }\n";
    
    $html .= "  if(miercoles.checked)\n";
    $html .= "  {\n";
    $html .= "    miercoles_g=1;\n";
    $html .= "  }\n";
    $html .= "  else\n";
    $html .= "  {\n";
    $html .= "    miercoles_g=0;\n";
    $html .= "  }\n";
    
    $html .= "  if(jueves.checked)\n";
    $html .= "  {\n";
    $html .= "    jueves_g=1;\n";
    $html .= "  }\n";
    $html .= "  else\n";
    $html .= "  {\n";
    $html .= "    jueves_g=0;\n";
    $html .= "  }\n";
    
    $html .= "  if(viernes.checked)\n";
    $html .= "  {\n";
    $html .= "    viernes_g=1;\n";
    $html .= "  }\n";
    $html .= "  else\n";
    $html .= "  {\n";
    $html .= "    viernes_g=0;\n";
    $html .= "  }\n";
    
    $html .= "  if(sabado.checked)\n";
    $html .= "  {\n";
    $html .= "    sabado_g=1;\n";
    $html .= "  }\n";
    $html .= "  else\n";
    $html .= "  {\n";
    $html .= "    sabado_g=0;\n";
    $html .= "  }\n";
    
    $html .= "  if(domingo.checked)\n";
    $html .= "  {\n";
    $html .= "    domingo_g=1;\n";
    $html .= "  }\n";
    $html .= "  else\n";
    $html .= "  {\n";
    $html .= "    domingo_g=0;\n";
    $html .= "  }\n";
    
    $html .= "  if(aleatorio.checked)\n";
    $html .= "  {\n";
    $html .= "    aleatorio_g=1;\n";
    $html .= "  }\n";
    $html .= "  else\n";
    $html .= "  {\n";
    $html .= "    aleatorio_g=0;\n";
    $html .= "  }\n";
    
    
    
    $html .= "  if(IsNumeric(cantidad))\n";
    $html .= "  {\n";
    $html .= "    if(lunes.checked==true || martes.checked==true || miercoles.checked==true || jueves.checked==true || viernes.checked==true || sabado.checked==true || domingo.checked==true || aleatorio.checked==true)\n";
    $html .= "    {\n";
   
    $html .= "      xajax_GuardarProductosConteo(cantidad,mayor_rotacion_g,mayor_costo_g,lunes_g,martes_g,miercoles_g,jueves_g,viernes_g,sabado_g,domingo_g,aleatorio_g);\n";
    $html .= "      alert('insertar');\n";
    $html .= "    }\n";
    $html .= "    else\n";
    $html .= "    {\n";
    $html .= "      alert('DEBE SELECCIONAR - DIAS CONTEO ALEATORIO');\n";
    $html .= "    }\n";
    $html .= "  }\n";
    $html .= "  else\n";
    $html .= "  {\n";
    $html .= "    alert('DEBE INGRESAR LA CANTIDAD');\n";
    $html .= "  }\n";
    
    $html .= " }";
    $html .= "</script>";
    $html .= $this->CrearVentana(400,"MENSAJE");
    $html .= ThemeCerrarTabla();
      
      return $html;
   }
	}
?>