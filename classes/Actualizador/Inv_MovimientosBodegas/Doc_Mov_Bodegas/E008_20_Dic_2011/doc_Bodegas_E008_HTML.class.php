<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: doc_Bodegas_E008_HTML.class.php,v 1.1.1.1 2010/08/25 22:28:40 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: doc_bodegas_E008_HTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1.1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Jaime G�ez 
  */
  IncludeClass("doc_Bodegas_E008","Doc_Mov_Bodegas/E008","app","Inv_MovimientosBodegas");
  class doc_bodegas_E008_HTML
  {
    /**
    * @var $xajaxExterno
    * Variable que indica si los includes xajas se deben hacer desde la clase principal
    */
    var $xajaxExterno = true;    
    /**
    * @var $nombreArchivoXajax
    * Variable que contiene el nombre del archivo xajax a incluir por la clase
    */
    var $nombreArchivoXajax = "";
    /**
    * Constructor de la clase
    */
    function doc_bodegas_E008_HTML(){}
    /**
    * Funcion donde se crea el arreglo que contiene las funciones a registrar en xajax
    *
    * @return array
    */
    function ArregloXajax()
    {
      $arreglo = array(
                  "Subtimit",
                  "CrearDocumentoFinalx",
                  "BorrarTmpAfirmativo1",
                  "MostrarProductox",
                  "Borrar",
                  "BorrarAjuste",
                  "GuardarPT",
                  "BuscarProducto1",
                  "ObtenerPaginadoPro",
                  "GuardarTmpDoc",
                  "Cuadrar_ids_terceros",
                  "CrearUSA",
                  "Buscadorter",
                  "ObtenerPaginadoter",
                  "Departamento2",
                  "Municipios",
                  "Guardar_DYM",
                  "GuardarPersona",
                  "Actualizartmp",
                  "Devolver",
                  "GuardarDevolucion",
                  "MostrarFarmacia_Cliente",
                  "MostrarProductoPedFarm",
                  "BuscarProductoFarm",
                  "ListadoProductos",
                  "ListadoProductos_HTML",
                  "ObtenerPaginado",
                  "GetItems",
                  "FormaItems_HTML",
                  "AgregarItem",
                  "ActualizarFechayLote",
                  "AdicionarNuevoLote",
                  "GuardarRotuloCaja",
                  "InsertarProductoPedido",
                  "ActuPendiente",
                  "BuscarProductoPendiente",
                  "BuscarProducto",
                  "BuscarDocumentosPedidos",
                  "MarcarTercero",
                  "RemoverItem",
                  "IngresarObservacion",
				  "ObservacionesDespachoCliente"
                );
       return $arreglo;
    }
    /**
    * Funcion donde se crea la forma del documentp
    *
    * @param array $datos arreglo de datos con la informacion del documento
    *
    * @return string
    */
    function FormaDocumento($datos)
    {
      $clMod = new classModules();
      $clMod->IncludeJS("CrossBrowser");
      $clMod->IncludeJS("CrossBrowserDrag");
      $clMod->IncludeJS("CrossBrowserEvent");
      
      $file ='app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/E008/RemoteXajax/definirBodegas_E008.php';

      $clMod->SetXajax($this->ArregloXajax(),$file,"ISO-8859-1");

      $html = "";
      switch($datos['accion'])
      {
        case 'NUEVO_TMP':
          $html = $this->FormaDocNuevo($datos);
        break;
        case 'EDITAR':
          $html = $this->FormaDocEditar($datos);
        break;
      }
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma para el documento
    * 
    * @param array $DATOS vector que contiene los datos
    *
    * @return string $html retorna la cadena con el codigo salida de la pagina
    */ 
    function FormaDocNuevo($DATOS)
    {
      $consulta = new doc_bodegas_E008();
      $datosDoc = $consulta->TraerDatos($DATOS['bodegas_doc_id']);
      $action['volver'] = ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('nom_bodegax'=>$DATOS['nom_bodega'],'utility'=>$DATOS['utility'],'bodegax'=>$DATOS['bodega']));
      
      $html = "";
      if(!$datosDoc)
      {
        $html .= $this->FormaMensajeModulo($action,$consulta->mensajeDeError);
      }
      else
      {
        $html .= ThemeAbrirTabla("TIPO DE DOCUMENTO -".$DATOS['tipo_doc_bodega_id']);
        $html .=$this->Cabecera($datosDoc);
        $html .=$this->CrearDocumentosHtml($DATOS['bodegas_doc_id'],$DATOS['CTL'],$DATOS['tipo_doc_bodega_id'],$DATOS['nom_bodega'],$DATOS['utility'],$DATOS['bodega']);
        $html .= " <form name=\"volver1\" action=\"".$action['volver']."\" method=\"post\">\n";
        $html .= "  <table align=\"center\" width=\"50%\">\n";
        $html .= "    <tr>\n";
        $html .= "       <td align=\"center\" colspan='7'>\n";
        $html .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $html .= "       </td>\n";
        $html .= "    </tr>\n";
        $html .= "  </table>\n";
        $html .= " </form>\n";
        $html .= ThemeCerrarTabla();
      }
      return $html;
    }
    /**
    * Funcion donde se crea la forma para el documento para editar
    * 
    * @param array $DATOS vector que contiene los datos
    * @return string $html retorna la cadena con el codigo salida de la pagina
    */ 
    function FormaDocEditar($DATOS)
    {
      $salida .= ThemeAbrirTabla("TIPO DE DOCUMENTO -".$DATOS['tipo_doc_bodega_id']);
      
      $consulta = new doc_bodegas_E008();
      $datox = $consulta->DatosParaEditar($DATOS['doc_tmp_id'],UserGetUID());
      $salida .= $this->PintarTabla($datox);
      $datos = $consulta->TraerDatos($DATOS['bodegas_doc_id']);
      $consulta1 = new MovBodegasSQL();
      $si_esta = $consulta1->ConsultaPardocg($DATOS['doc_tmp_id']);

      $param_estados=$consulta1->ConsultaEstadosPermisos($DATOS['tipo_doc_bodega_id']);
      
      if(empty($si_esta))
      {
        foreach ($param_estados as $indice=>$valor)
        { 
          $guadarpar=$consulta1->GuardarParGrabar($DATOS['tipo_doc_bodega_id'],$valor['abreviatura'],$DATOS['doc_tmp_id']);
        }
      }
      
      $identificador = "FM";
      $farmacia_pedidos = $consulta1->FarmaciaPedidosTmp($DATOS['doc_tmp_id']);
      if(empty($farmacia_pedidos))
      {
        $clientes_pedidos = $consulta1->ClientesPedidosTmp($DATOS['doc_tmp_id']);
        $identificador = "CL";
      }
      SessionSetVar("empresa_id",$datox['empresa_id']);
      SessionSetVar("centro_utilidad",$DATOS['utility']);
      SessionSetVar("bodega",$DATOS['bodegax']);
      $salida .= $this->ColocarProductosPedFarmacia($DATOS['bodegas_doc_id'],$DATOS['doc_tmp_id'],$datos,$identificador);
      
      $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('nom_bodegax'=>$DATOS['nom_bodegax'],'utility'=>$DATOS['utility'],'bodegax'=>$DATOS['bodegax']));
      $salida .= " <form name=\"volver1\" action=\"".$BODEGA."\" method=\"post\">\n";
      $salida .= "  <table align=\"center\" width=\"50%\">\n";
      $salida .= "    <tr>\n";
       
      if(!empty($datox))
      {
        $usuariotmp=$consulta1->Consultausuaritmp($datox['doc_tmp_id'],$datox['bodegas_doc_id']);
        $documentos=$consulta1->ConsultaPardocg($datox['doc_tmp_id']);
        $estadosEmpresa=$consulta1->ConsultaEmpresa($datox['empresa_id']);
        $contar=count($documentos); 
         
        $salida .= " <td id='SUTANO' align=\"center\" colspan='7'>\n";
        $salida .= "   <input type=\"button\" id='ELI' class=\"input-submit\" value=\"ELIMINAR DOCUMENTO\"  onclick=\"EliminarDocu('".$DATOS['doc_tmp_id']."','".$DATOS['bodegas_doc_id']."');\">\n";
        $salida .= " </td>\n"; 
        $salida .= " <td align=\"center\" colspan='7'>\n";
        $salida .= "   <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $salida .= " </td>\n";
        //print_r($estadosEmpresa); 
        if($estadosEmpresa['sw_estados']==1)
        {
          $m=0;
          $k = false;
          foreach($documentos as $k1 => $dtl)
            if($dtl['sw_verifico']=='1')
              $k = true;
          
          if($usuariotmp['usuario_id']==UserGetUID() && $k)
          {
            $salida .= " <td id='MENGANO' align=\"center\"  colspan='7'>\n";
            $salida .= "   <input type=\"button\" id='enar' class=\"input-submit\" value=\"CREAR DOCUMENTO\"  onclick=\"xajax_CrearDocumentoFinalx('".$DATOS['bodegas_doc_id']."','".$DATOS['doc_tmp_id']."','".$DATOS['tipo_doc_bodega_id']."','".$identificador."');\">\n";
            $salida .= " </td>\n";
          }
          else
          {
            $salida .= " <td id='MENGANO' align=\"center\"  colspan='7'>\n";
            $salida .= " <input type=\"button\" disabled='true' id='enar' class=\"input-submit\" value=\"CREAR DOCUMENTO\"  onclick=\"xajax_CrearDocumentoFinalx('".$DATOS['bodegas_doc_id']."','".$DATOS['doc_tmp_id']."','".$DATOS['tipo_doc_bodega_id']."','".$identificador."');\">\n";
            $salida .= " </td>\n";
          }
         
         $salida .= " <td id='MENGANO' align=\"center\"  colspan='7'>\n";
         $salida .= "  <input type=\"hidden\" id='devolver' class=\"input-submit\" value=\"DEVOLVER\" onclick=\"Devolver('".$DATOS['tipo_doc_bodega_id'] ."','".$DATOS['doc_tmp_id']."','".$_SESSION['EMPRESAS']['empresa_id']."');\">\n";
         $salida .= " </td>\n";
            
        }
        else
        {
          $salida .= " <td id='MENGANO' align=\"center\"  colspan='7'>\n";
          $salida .= "   <input type=\"button\" id='enar' class=\"input-submit\" value=\"CREAR DOCUMENTO\" onclick=\"xajax_CrearDocumentoFinalx('".$DATOS['bodegas_doc_id']."','".$DATOS['doc_tmp_id']."','".$DATOS['tipo_doc_bodega_id']."','".$identificador."');\">\n";
          $salida .= " </td>\n";
        }
      }
      else
      {
        $salida .= " <td align=\"center\" colspan='7'>\n";
        $salida .= "   <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $salida .= " </td>\n";
      }

      $salida .= " </tr>\n";
      $salida .= " </table>\n";
      $salida .= " </form>\n";
      $salida .= ThemeCerrarTabla();
      return $salida;
    }
  /**
      * Funcion donde se crea la forma para el documento para editar
      * 
      * @param array $DATOS vector que contiene los datos
      * @return string $html retorna la cadena con el codigo salida de la pagina
      */ 
  function PintarTabla($busqueda)
  {
    $path = SessionGetVar("rutaImagenes");
    $salida .= " <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
    $salida .= "  <tr class=\"modulo_table_list_title\">\n";
    $salida .= "   <td align=\"center\" >\n";
    $salida .= "     <a title='DOCUMENTO TEMPORAL ID'>TMP-ID</a>";
    $salida .= "   </td>\n";
    $salida .= "   <td align=\"left\" class=\"modulo_list_claro\">\n";
    $salida .= "     ".$busqueda['doc_tmp_id'];
    $salida .= "   </td>\n";
    $salida .= "   <td align=\"center\">\n";
    $salida .= "     <a title='ID DOCUMENTO DE LA BODEGA'>BODEGA DOC ID<a> ";
    $salida .= "   </td>\n";
    $salida .= "   <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
    $salida .= "     ".$busqueda['bodegas_doc_id'];
    $salida .= "   </td>\n";
    $salida .= "   <td align=\"center\">\n";
    $salida .= "     <a title='FECHA REGISTRO DOCUMENTO'>FECHA BOD REG<a> ";
    $salida .= "   </td>\n";
    $salida .= "   <td align=\"left\" class=\"modulo_list_claro\">\n";
    $salida .= "     ".substr($busqueda['fecha_registro'],0,10);
    $salida .= "   </td>\n";
    $salida .= "   <td align=\"center\">\n";
    $salida .= "    <a title='PREFUJO DEL DOCUMENTO'>PREFIJO<a>";
    $salida .= "   </td>\n";
    $salida .= "   <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
    $salida .= "      ".$busqueda['prefijo'];
    $salida .= "   </td>\n";
    $salida .= " </tr>\n";
  
    $salida .= " <tr class=\"modulo_table_list_title\">\n";
    $salida .= "   <td align=\"center\" >\n";
   $salida .= "     <a title='DOCUMENTO DESCRIPCION'>DESCRIPCION<a>";
   $salida .= "  </td>\n";
   $salida .= "  <td COLSPAN='7' align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
   $salida .= "     ".$busqueda['descripcion'];
   $salida .= "  </td>\n";
   $salida .= " </tr>\n";
   $salida .= " <tr class=\"modulo_table_list_title\">\n";
   $salida .= "   <td align=\"center\">\n";
   $salida .= "     OBSERVACION";
   $salida .= "   </td>\n";
   $salida .= "   <td COLSPAN='7' align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
   $salida .= "     ".$busqueda['observacion'];
   $salida .= "   </td>\n";
   $salida .= "  </tr>\n";
   $salida .= " </table>\n";
   return $salida;   
 }

 function Cabecera($datos)
 { 
   $consulta = new MovBodegasSQL();
   $path = SessionGetVar("rutaImagenes");
  
   $salida .= " <form name=\"recaudo\" action=\"javascript:LlamarRevi('".SessionGetVar("EMPRESA")."',document.revi.tip_lapi.value);\" method=\"post\">\n";
   $salida .= "  <div id=\"ventana1\">\n";
   $salida .= "   <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";
   $salida .= "     <tr class=\"modulo_list_claro\">\n";
   $salida .= "       <td width=\"25%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
   $salida .= "        EMPRESA";
   $salida .= "       </td>";
   $nombreempresa=$consulta->ColocarEmpresa($datos['empresa_id']);
   $salida .= "       <td colspan='3' align=\"left\" class=\"normal_10AN\">\n";
   $salida .= "         ".$nombreempresa[0]['razon_social'];
   $salida .= "       </td>";
   $salida .= "      </tr>";
   $salida .= "      <tr class=\"modulo_list_claro\">\n";
   $salida .= "       <td width=\"25%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
   $salida .= "         BODEGA";
   $salida .= "       </td>";
   $salida .= "       <td colspan='3' align=\"left\" class=\"normal_10AN\">\n";
   $nombrebodega=$consulta->bodegasname($datos['bodega']);
   $salida .= "           10 -  ".$nombrebodega[0]['descripcion'];
   $salida .= "       </td>";
   $salida .= "      </tr>";
   $salida .= "      <tr class=\"modulo_list_claro\">\n";
   $salida .= "       <td width=\"25%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
   $salida .= "         TIPO CLASE DE DOCUMENTO";
   $salida .= "       </td>";
   //$nombre=$consulta->Nombres($tipo_id_tercero,$tercero_id);
   $salida .= "       <td colspan='3' align=\"left\" class=\"normal_10AN\">\n";
   $salida .= "          ".$datos['tipo_clase_documento'];
   $salida .= "       </td>";
   $salida .= "      </tr>";
   $salida .= "      <tr class=\"modulo_list_claro\">\n";
   $salida .= "        <td width=\"25%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
   $salida .= "          DESCRIPCION";
   $salida .= "        </td>";
   //$nombre=$consulta->Nombres($tipo_id_tercero,$tercero_id);
   $salida .= "        <td colspan='3' align=\"left\" class=\"normal_10AN\">\n";
   $salida .= "          ".$datos['descripcion'];
   $salida .= "        </td>";
   $salida .= "       </tr>";

   $salida .= "       <tr class=\"modulo_list_claro\">\n";
   $salida .= "        <td width=\"25%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
   $salida .= "          TIPO DE MOVIMIENTO";
   $salida .= "        </td>";
  
   $salida .= "       <td  align=\"left\" class=\"normal_10AN\">\n";
   if($datos['tipo_movimiento']=='I')
    $salida .= "       INGRESO";
    ELSE
   $salida .= "        EGRESO";
   $salida .= "       </td>";
   $salida .= "       <td colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
   $salida .= "        PREFIJO";
   $salida .= "       </td>";
   $salida .= "       <td align=\"left\" class=\"normal_10AN\">\n";
   $salida .= "         ".$datos['prefijo'];
   $salida .= "       </td>";
   $salida .= "       <tr class=\"modulo_list_claro\">";
   $salida .= "          <td width=\"25%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
   $salida .= "            CENTRO DE UTILIDAD";
   $salida .= "          </td>";
   $salida .= "          <td colspan='3' align=\"left\" class=\"normal_10AN\">\n";
   $salida .= "            ".$datos['centro_utilidad'];
   $salida .= "          </td>";
   $salida .= "       </tr>";
   $salida .= "    </table>";
   $salida .= " </form>";

   return $salida;
 }
    /**
    *
    */
    function CrearDocumentosHtml($bodegas_doc_id,$dir,$tipo_doc_bodega_id,$nom_bodega,$utilidad,$bodega)
    {
      $consulta = new MovBodegasSQL();
      $div = "ventana_terceros";
      $ctl = AutoCarga::factory("ClaseUtil");

      $html  = $ctl->LimpiarCampos();
      $html .= "<script>\n";
      $html .= " var contenedor1=''\n";
      $html .= " var titulo1=''\n";
      $html .= " var hiZ = 2;\n";
      $html .= " var DatosFactor = new Array();\n";
      $html .= " var EnvioFactor = new Array();\n";
      $html .= " function Rata()\n";
      $html .= " {\n";
      $html .= "  alert('JUKILO');";
      $html .= " }\n";
	  
		$html .= "   function Iniciar4(tit)\n";
		$html .= "   {\n";
		$html .= "       contenedor1 = 'ContenedorBus';\n";
		$html .= "       titulo1 = 'tituloBus';\n";
		$html .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
		$html .= "        Capa = xGetElementById(contenedor1);\n"; 
		$html .= "       xResizeTo(Capa, 600, 400);\n";
		$html .= "       xMoveTo(Capa, xClientWidth()/11, xScrollTop()+100);\n";
		$html .= "       ele = xGetElementById(titulo1);\n";
		$html .= "       xResizeTo(ele, 580, 20);\n";
		$html .= "       xMoveTo(ele, 0, 0);\n";
		$html .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
		$html .= "       ele = xGetElementById('cerrarBus');\n";
		$html .= "       xResizeTo(ele, 20, 20);\n";
		$html .= "       xMoveTo(ele, 580, 0);\n";
		$html .= "   }\n";
	  
      $html .= " function Iniciar2(tit)\n";
      $html .= " {\n";
      $html .= "   contenedor1 = 'ContenedorMov1';\n";
      $html .= "   titulo1 = 'tituloMov1';\n";
      $html .= "   document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
      $html .= "    Capa = xGetElementById(contenedor1);\n";
      $html .= "   xResizeTo(Capa, 600, 430);\n";
      $html .= "   xMoveTo(Capa, xClientWidth()/5, xScrollTop()+30);\n";
      $html .= "   ele = xGetElementById(titulo1);\n";
      $html .= "   xResizeTo(ele, 580, 20);\n";
      $html .= "   xMoveTo(ele, 0, 0);\n";
      $html .= "   xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $html .= "   ele = xGetElementById('cerrarMov1');\n";
      $html .= "   xResizeTo(ele, 20, 20);\n";
      $html .= "   xMoveTo(ele, 580, 0);\n";
      $html .= "  }\n";
      $html .= "  function IniciarUsu(tit)\n";
      $html .= "  {\n";
      $html .= "   contenedor1 = 'ContenedorCre';\n";
      $html .= "   titulo1 = 'tituloCre';\n";
      $html .= "   document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
      $html .= "    Capa = xGetElementById(contenedor1);\n"; 
      $html .= "   xResizeTo(Capa, 500, 380);\n";
      $html .= "   xMoveTo(Capa, xClientWidth()/5, xScrollTop()+65);\n";
      $html .= "   ele = xGetElementById(titulo1);\n";
      $html .= "   xResizeTo(ele, 480, 20);\n";
      $html .= "   xMoveTo(ele, 0, 0);\n";
      $html .= "   xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $html .= "   ele = xGetElementById('cerrarCre');\n";
      $html .= "   xResizeTo(ele, 20, 20);\n";
      $html .= "   xMoveTo(ele, 480, 0);\n";
      $html .= "  }\n";
      $html .= "  function myOnDragStart(ele, mx, my)\n";
      $html .= "  {\n";
      $html .= "    window.status = '';\n";
      $html .= "    if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
      $html .= "    else xZIndex(ele, hiZ++);\n";
      $html .= "    ele.myTotalMX = 0;\n";
      $html .= "    ele.myTotalMY = 0;\n";
      $html .= "  }\n";
      $html .= "  function myOnDrag(ele, mdx, mdy)\n";
      $html .= "  {\n";
      $html .= "   if (ele.id == titulo1) {\n";
      $html .= "    xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
      $html .= "  }\n";
      $html .= "  else {\n";
      $html .= "   xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
      $html .= "  }  \n";
      $html .= "  ele.myTotalMX += mdx;\n";
      $html .= "  ele.myTotalMY += mdy;\n";
      $html .= "  }\n";
      $html .= "  function myOnDragEnd(ele, mx, my)\n";
      $html .= "  {\n";
      $html .= "  }\n";
      $html .= "   function MostrarCapa(Elemento)\n";
      $html .= "   {\n";
      $html .= "    capita = xGetElementById(Elemento);\n";
      $html .= "    capita.style.display = \"\";\n";
      $html .= "   }\n";
      $html .= "   function Cerrar(Elemento)\n";
      $html .= "   {\n";
      $html .= "    capita = xGetElementById(Elemento);\n";          
      $html .= "    capita.style.display = \"none\";\n";          
      $html .= "   }\n";
      $html .= "  function mOvr(src,clrOver)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrOver;\n";
      $html .= "  }\n";
      $html .= "  function mOut(src,clrIn)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrIn;\n";
      $html .= "  }\n";
      $html .= "  function MostrarOpcion()\n";
      $html .= "  {\n";
      $html .= "    xajax_MostrarFarmacia_Cliente(xajax.getFormValues(unocreate));\n";
      $html .= "  }\n";
      $html .= "  function BuscarDocumentosPedidos()\n";
      $html .= "  {\n";
      $html .= "    xajax_BuscarDocumentosPedidos(xajax.getFormValues('unocreate'));\n";
      $html .= "  }\n";      
      $html .= "  function MostrarBotonGuardar(valor)\n";
      $html .= "  {\n";
      $html .= "    visual = (valor == '-1')? 'none':'block';\n";
      $html .= "    document.getElementById('boton_guardar').style.display = visual;\n";
      $html .= "  }\n";      
      $html .= "  function MarcarTercero(forma,tercero_tipo,tercero_id,tercero_nombre)\n";
      $html .= "  {\n";
      $html .= "    xajax_MarcarTercero(xajax.getFormValues('unocreate'),tercero_tipo,tercero_id,tercero_nombre);\n";
      $html .= "  }\n";
      $html .= "  function GrabarDocumentoE(bodegas_doc_id,frm)\n";
      $html .= "  {\n";
      $html .= "    if(frm.tipo_idfc.value == '2')\n";
      $html .= "    {\n";
      $html .= "      if(frm.tercerito_tip.value == '')\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('errorcreartmp').innerHTML=\"EL DOCUMENTO EXIGE UN TERCERO\";\n";
      $html .= "        return false;\n";
      $html .= "      }\n";      
      $html .= "      if(frm.pedido_farmacia.value == '')\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('errorcreartmp').innerHTML=\"NO SE HA ESPECIFICADO EL DOCUMENTO DE PEDIDO\";\n";
      $html .= "        return false;\n";
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "    else if(frm.tipo_idfc.value == '1')\n";
      $html .= "    {\n";
      $html .= "      if(frm.farmacia_id.value == '-1')\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('errorcreartmp').innerHTML=\"EL DOCUMENTO EXIGE ESPECIFICAR UNA FARMACIA\";\n";
      $html .= "        return false;\n";
      $html .= "      }\n";      
      $html .= "      if(frm.pedido_farmacia.value == '')\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('errorcreartmp').innerHTML=\"NO SE HA ESPECIFICADO EL DOCUMENTO DE PEDIDO\";\n";
      $html .= "        return false;\n";
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "    xajax_GuardarTmpDoc(bodegas_doc_id, xajax.getFormValues('unocreate'));";
      $html .= "  }\n";
      $html .= "</script>";
      /**
      *Ventana emergente 3 aqui es cuando se modifica una cuenta. 
      */
      $tipos_terceros = $consulta->Terceros_id();
      $html .= "<div id='ContenedorMov1' class='d2Container' style=\"display:none\">";
      $html .= " <div id='tituloMov1' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $html .= " <div id='cerrarMov1' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorMov1');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $html .= " <div id='errorMov1' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $html .= " <div id='ContenidoMov1'>\n";
      $html .= "  <form name=\"buscartercero\">\n";     
      $html .= "    <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "        <td colspan=\"2\">BUSCADOR DE TERCEROS</td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "        <td width=\"30%\" align=\"left\">NOMBRE TERCERO</td>\n";
      $html .= "        <td class=\"modulo_list_claro\" align=\"left\">\n";
      $html .= "          <input type=\"text\" class=\"input-text\" name=\"nom_buscar\" id=\"nom_buscar\" style=\"width:100%\" value=\"".(($criterio != "0")? $criterio:"")."\" onkeypress=\"return acceptm(event)\">\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "        <td align=\"left\" >TIPO IDENTIFICACION</td>\n";
      $html .= "        <td class=\"modulo_list_claro\" align=\"left\">\n";    
      $html .= "          <select name=\"buscar_x\" id=\"buscar_x\" class=\"select\">";
      $html .= "            <option value=\"-1\" >--SELECCIONAR--</option> \n";
      foreach($tipos_terceros as $key=> $dtl)
        $html .= "            <option value=\"".$dtl['tipo_id_tercero']."\" ".(($criterio1 == $dtl['tipo_id_tercero'])? "selected":"" ).">".$dtl['descripcion']."</option> \n";
      
      $html .= "          </select>\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "        <td align=\"left\">IDENTIFICACION</td>\n";
      $html .= "        <td class=\"modulo_list_claro\" align=\"left\">\n"; 
      $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscar\" id=\"buscar\" maxlength=\"40\" size\"40\" value=\"".(($criterio2 != "0")? $criterio:"")."\"onkeypress=\"return acceptm(event)\">";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr class=\"modulo_list_claro\">\n";
      $html .= "        <td colspan=\"2\" align=\"center\">\n";
      $html .= "          <input type=\"button\" class=\"input-submit\" name=\"boton_bus\"  id=\"boton_bus\" value=\"Buscar\" onclick=\"Bus_ter(document.getElementById('buscar_x').value,document.getElementById('buscar').value,document.getElementById('nom_buscar').value,'".$div."','".$Forma."','1')\">\n";
      $html .= "          <input type=\"button\" class=\"input-submit\" name=\"boton_bus\"  id=\"boton_limpiar\" value=\"Limpiar Campos\" onclick=\"LimpiarCampos(document.buscartercero)\">\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";         
      $html .= "    </table>\n";        
      $html .= "  </form>\n";  
      $html .= "    <div id=\"ventana_terceros\"></div>\n";
      $html .= "  </div>\n";
      $html .= "</div>";
      /**
      *Ventana para crear tercero
      */
      $html .= "<div id='ContenedorCre' class='d2Container' style=\"display:none\">";
      $html .= " <div id='tituloCre' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $html .= " <div id='cerrarCre' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCre');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $html .= " <div id='errorCre' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $html .= " <div id='ContenidoCre'>\n";
      $html .= " </div>\n";
      $html.="</div>";

      $path = SessionGetVar("rutaImagenes");
    
      $html .= "<br>\n";
      $html .= "<div id='errorcreartmp' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $html .= "<form name=\"unocreate\" id=\"unocreate\" action=\"".$accion1."\" method=\"post\">\n";
      $html .= "    <input type=\"hidden\" name=\"bodegas_doc_id\" value=\"".$bodegas_doc_id."\"size=\"5\">\n";
      $html .= "    <input type=\"hidden\" name=\"empresa_id\" value=\"".$_SESSION['EMPRESAS']['empresa_id']."\"size=\"5\">\n";
      $html .= "    <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "      <tr>\n";
      $html .= "        <td  width='6%' align=\"left\" class=\"formulacion_table_list\" >\n";
      $html .= "          SELECCION TIPO DESPACHO";
      $html .= "        </td>\n";
      $html .= "        <td  width='5%'  align=\"left\" class=\"modulo_list_claro\" id=\"farmacia_cliente\" > \n";
      $html .= "          <select name=\"tipo_idfc\" id=\"tipo_idfc\" class=\"select\" onchange=\"MostrarOpcion()\">";
      $html .= "            <option value=\"-1\">--SELECCIONAR--</option>\n";
      $html .= "            <option value=\"1\">DESPACHO DE FARMACIA</option> \n";
      $html .= "            <option value=\"2\">DESPACHO A CLIENTE</option> \n";
      $html .= "          </select>\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "        <td colspan='2' align=\"left\" class=\"modulo_list_claro\"> \n";
      $html .= "          <fieldset class=\"fieldset\">";
      $html .= "            <legend class=\"normal_10AN\" >OBSERVACIONES</legend>";
      $html .= "            <textarea class=\"textarea\" name=\"obser\" id=\"obser\" rows=\"2\" style=\"width:100%\" ></textarea>\n";//OnFocus=\"this.blur()\"
      $html .= "          </fieldset>";
      $html .= "        </td>\n";
      $html .= "       </tr>\n";
      $html .= "      </table>";
      $html .= "      <div align=\"center\" id=\"tipo_farmaclie\"></div>\n";
      $html .= "      <div align=\"center\" id=\"boton_guardar\" style=\"display:none\">\n";
      $html .= "        <table width=\"50%\" align=\"center\">\n";
      $html .= "          <tr>\n";
      $html .= "            <td  COLSPAN='8' align=\"center\" class=\"modulo_list_claro\">\n";                         
      $html .= "               <input type=\"button\" id=\"nuevo\" value=\"GRABAR DOCUMENTO\" class=\"input-bottom\" onClick=\"javascript:GrabarDocumentoE('".$bodegas_doc_id."',document.unocreate);\">";//
      $html .= "            </td>\n"; 
      $html .= "          </tr>\n";
      $html .= "         </table>\n";
      $html .= "      </div>\n";
      $html .= "    </form>";
   
      $html .= " <form name=\"volver\" action=\"".$dir."\" method=\"post\">\n";
      $html .= "  <input  id='doc_tmp_id_h' type=\"hidden\" value=\"\">\n";
      $html .= "  <input name='accion_h' id='accion_h' type=\"hidden\" value=\"\">\n";
      $html .= "  <input id='tipo_clase' type=\"hidden\" value=\"".$tipo_doc_bodega_id."\">\n";
      $html .= "  <input id='bodegas_doc_id' type=\"hidden\" value=\"".$bodegas_doc_id."\">\n";
      $html .= "  <input id='nom_bodegax' type=\"hidden\" value=\"".$nom_bodega."\">\n";
      $html .= "  <input id='utility' type=\"hidden\" value=\"".$utilidad."\">\n";
      $html .= "  <input id='bodegax' type=\"hidden\" value=\"".$bodega."\">\n";
      $html .= " </form>\n";
    return $html;
   }

/************************************************************************************
* COLOCAR LOS PRODUCTOS DEL DOCUMENTO
*********************************************************************************/ 

/*function ColocarProductos2500($bodegas_doc_id,$datos,$tmp_doc_id)
 {

    
    $consulta = new MovBodegasSQL();
    $javaC = "<script>\n";
    $javaC .= "var contenedor1=''\n";
    $javaC .= "   var titulo1=''\n";
    $javaC .= "   var hiZ = 2;\n";
    $javaC .= "   var DatosFactor = new Array();\n";
    $javaC .= "   var EnvioFactor = new Array();\n";
    $javaC .= "   function Rata()\n";
    $javaC .= "   {\n";
    $javaC .= "   alert('JUKILO');";
    $javaC .= "   }\n";
    $javaC .= "   function Iniciar4(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorBus';\n";
    $javaC .= "       titulo1 = 'tituloBus';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 600, 400);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/11, xScrollTop()+100);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 580, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarBus');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 580, 0);\n";
    $javaC .= "   }\n";
    $javaC .= "   function IniciarUsu(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorCre';\n";
    $javaC .= "       titulo1 = 'tituloCre';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 500, 380);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+65);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 480, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarCre');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 480, 0);\n";
    $javaC .= "   }\n";
    $javaC .= "   function IniciarB3(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorB3';\n";
    $javaC .= "       titulo1 = 'tituloB3';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n";
    $javaC .= "       xResizeTo(Capa, 200, 160);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-0);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 180, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarB3');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 180, 0);\n";
    $javaC .= "   }\n";
    $javaC.= "</script>\n";
    $salida.= $javaC;
    $javaC1.= "<script>\n";
    $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
    $javaC1 .= "   {\n";
    $javaC1 .= "     window.status = '';\n";
    $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
    $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
    $javaC1 .= "     ele.myTotalMX = 0;\n";
    $javaC1 .= "     ele.myTotalMY = 0;\n";
    $javaC1 .= "   }\n";
    $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";
    $javaC1 .= "   {\n";
    $javaC1 .= "     if (ele.id == titulo1) {\n";
    $javaC1 .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
    $javaC1 .= "     }\n";
    $javaC1 .= "     else {\n";
    $javaC1 .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
    $javaC1 .= "     }  \n";
    $javaC1 .= "     ele.myTotalMX += mdx;\n";
    $javaC1 .= "     ele.myTotalMY += mdy;\n";
    $javaC1 .= "   }\n";
    $javaC1 .= "   function myOnDragEnd(ele, mx, my)\n";
    $javaC1 .= "   {\n";
    $javaC1 .= "   }\n";
    $javaC1.= "function MostrarCapa(Elemento)\n";
    $javaC1.= "{\n";
    $javaC1.= "    capita = xGetElementById(Elemento);\n";
    $javaC1.= "    capita.style.display = \"\";\n";
    $javaC1.= "}\n";
    $javaC1.= "function Cerrar(Elemento)\n";
    $javaC1.= "{\n";
    $javaC1.= "    capita = xGetElementById(Elemento);\n";          
    $javaC1.= "    capita.style.display = \"none\";\n";          
    $javaC1.= "}\n";                    
    $javaC1.= "</script>\n";
    $salida.="
    <script language=\"javaScript\">
      function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

                function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }

               MostrarProductoxjs('".$bodegas_doc_id."','".$tmp_doc_id."','".UserGetUID()."');
    </script>";
    $salida.= $javaC1;
    $salida .= " <div id='ContenedorB3' class='d2Container' style=\"display:none;\">";
    $salida .= "    <div id='tituloB3' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $salida .= "    <div id='cerrarB3' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorB3');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $salida .= "    <div id='errorB3' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $salida .= "    <div id='ContenidoB3'  class='d2Content' style='z-index:10;'>\n";
    $salida .= "    </div>\n";
    $salida .= " </div>\n";
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se BVUSCA UN PRODUCTO
**********************************************************************************/
   /* $salida.="<div id='ContenedorBus' class='d2Container' style=\"display:none\">";
    $salida .= "    <div id='tituloBus' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $salida .= "    <div id='cerrarBus' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorBus');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $salida .= "    <div id='errorBus' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $salida .= "    <div id='ContenidoBus'>\n";
    /****************************************************************************/

   
           /* $salida .= "                          <input type=\"hidden\" id=\"empresa_idz\" value=\"".$datos['empresa_id']."\">\n";
            $salida .= "                          <input type=\"hidden\" id=\"centro_utilidadz\" value=\"".$datos['centro_utilidad']."\">\n";
            $salida .= "                          <input type=\"hidden\" id=\"bodegaz\" value=\"".$datos['bodega']."\">\n";
            $salida .= "                 <form name=\"jukilo\" action=\"".$accion1."\" method=\"post\">\n";
            $salida .= "                 <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";         
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td COLSPAN='2' align=\"center\">\n";
            $salida .= "                          BUSCADOR DE PRODUCTOS";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td width=\"35%\" align=\"center\">\n";
            $salida .= "                          TIPO DE BUSQUEDA";
            $salida .= "                       <select id=\"tip_bus\" name=\"tip_bus\" class=\"select\" onchange=\"Aplicar(this.value)\">";
            $salida .= "                           <option value=\"1\" SELECTED>DESCRIPCION</option> \n";
            $salida .= "                           <option value=\"2\"># CODIGO</option> \n";
            $salida .= "                       </select>\n";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"55%\" align=\"left\" id=\"ventanatabla\">\n";
            $salida .= "                          DESCRIPCION";                                                                                                             
            $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"30\" onkeypress=\"return acceptm(event);\" onkeydown=\"recogerTeclaBus(event)\" value=\"\">\n";//
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                </table>\n";
            $salida .= "                </form>\n";
            $salida .= "                 <br>\n";
            $salida .="              <div id=\"tabelos\">";
            $salida .="              </div>\n";
            $salida .= "   </div>\n";     
            $salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/   


  /*$path = SessionGetVar("rutaImagenes");
  $salida .= "          <br>\n";
  $salida .= "    <div id='error_doc' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
  $salida .= "               <div id=\"ventana1\">\n";
  $salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  width='10%' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                        CODIGO";
  $salida .= "                       </td>\n";
  $salida .= "                        <input name='codigo' id='codigo' type=\"hidden\" value=\"\">\n";
  $salida .= "                       <td  width='15%' COLSPAN='1' id='codigo_pro' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                       ";
  $salida .= "                       </td>\n";
  $salida .= "                       <td  width='10%' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                       UNIDAD";
  $salida .= "                       </td>\n";
  $salida .= "                       <td  width='10%' id='unidad_pro' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                        ";
  $salida .= "                       </td>\n";
  $salida .= "                       <td  width='12%' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                       ";
  $salida .= "                       </td>\n";
  $salida .= "                       <td  width='13%' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                       ";
  $salida .= "                       </td>\n";
  $salida .= "                        <td width='30%' align=\"center\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
  $java = "javascript:MostrarCapa('ContenedorBus');Bus_Pro('".$datos['empresa_id']."','".$datos['centro_utilidad']."','".$datos['bodega']."','0','0','1');Iniciar4('BUSCAR PRODUCTO');Clear3000();\"";
  $salida .= "                         <a title='BUSCADOR PRODUCTO' class=\"label_error\" href=\"".$java."\">\n";
  $salida .= "                          BUSCAR PRODUCTO\n";
  $salida .= "                         </a>\n";
  $salida .= "                       </td>\n";
  $salida .= "                      </tr>\n";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td COLSPAN='1' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                        DESCRIPCION";
  $salida .= "                       </td>\n"; 
  $salida .= "                       <td  COLSPAN='3' id='desc_pro' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                         ";
  $salida .= "                       </td>\n";
  $salida .= "                       <td COLSPAN='1' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                        CANTIDAD";
  $salida .= "                       </td>\n";
  $salida .= "                       <td  align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                          <input type=\"text\" id=\"cantidad\" size='10' class=\"input-text\" value=\"\" onkeypress=\"return acceptNum(event);\" onclick=\"limpiar200z();Clear3000();\">\n";
  $salida .= "                       </td>\n";
  $salida .= "                       <td  ROWSPAN='3' id='unidad_pro' align=\"left\" class=\"modulo_list_claro\"> \n";
                                     
                                  $salida .= "                 <form name=\"ventana_hill2\">\n";
                                  $salida .= "                 <table align=\"center\" class=\"modulo_table_list\">\n";
                                  $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
                                  $salida .= "                       <td   align=\"left\" class=\"modulo_table_list_title\">\n";
                                  $salida .= "                        COSTO";
                                  $salida .= "                       </td>\n";
                                  $salida .= "                       <td  COLSPAN='2' width='33%' COLSPAN='1' id='codigo_pro' align=\"center\" class=\"modulo_table_list_title\"> \n";
                                  $salida .= "                       SIN GRAVAMEN";
                                  $salida .= "                       </td>\n";
                                  $salida .= "                       <td  COLSPAN='2' width='33%' align=\"center\" class=\"modulo_table_list_title\">\n";
                                  $salida .= "                       CON GRAVAMEN";
                                  $salida .= "                       </td>\n";
                                  $salida .= "                    </tr>\n";
                                  $salida .= "                    <tr>\n";
                                  $salida .= "                       <td  align=\"left\" class=\"modulo_table_list_title\">\n";
                                  $salida .= "                          UNITARIO";
                                  $salida .= "                       </td>\n";
                                  $salida .= "                       <td  align=\"left\" id=\"td11\" class=\"modulo_table_list_title\">\n";
                                  $salida .= "                          <input type=\"radio\" id=\"costow\" name=\"costow\" class=\"input-text\" value=\"11\" onclick=\"pintar(document.getElementById('costow').value);\" checked>\n";
                                  $salida .= "                       </td>\n";
                                  $salida .= "                       <td  COLSPAN='1' align=\"left\" class=\"modulo_list_claro\"> \n";
                                  $salida .= "                          <input type=\"text\" id=\"op11\"style=\"text-align:right\"  size='12' onkeyup=\"Calcular(document.getElementById('costow').value,document.getElementById('cantidad').value,document.getElementById('gravamen').value,document.getElementById('op11').value);\" class=\"input-text\" onkeypress=\"return acceptNum(event);\" onclick=\"Clear3000();\" value=\"\">\n";
                                  $salida .= "                       </td>\n";
                                  $salida .= "                       <td  align=\"left\" class=\"modulo_table_list_title\">\n";
                                  $salida .= "                          <input type=\"radio\" id=\"costow\" name=\"costow\" class=\"input-text\" value=\"12\" onclick=\"pintar(document.getElementById('costow').value);\">\n";
                                  $salida .= "                       </td>\n";
                                  $salida .= "                       <td  COLSPAN='1' id=\"td12\" align=\"left\" class=\"modulo_list_claro\"> \n";
                                  $salida .= "                          <input type=\"text\" style=\"text-align:right\" id=\"op12\" size='12' class=\"input-text\" value=\"\" disabled onkeyup=\"Calcular(document.getElementById('costow').value,document.getElementById('cantidad').value,document.getElementById('gravamen').value,document.getElementById('op12').value);\"  onkeypress=\"return acceptNum(event);\" onclick=\"Clear3000();\">\n";
                                  $salida .= "                       </td>\n";
                                  $salida .= "                    </tr>\n";
                                  $salida .= "                    <tr>\n";
                                  $salida .= "                       <td  align=\"left\" class=\"modulo_table_list_title\">\n";
                                  $salida .= "                          TOTAL";
                                  $salida .= "                       </td>\n";
                                  $salida .= "                       <td  align=\"left\" class=\"modulo_table_list_title\">\n";
                                  $salida .= "                          <input type=\"radio\" id=\"costow\" name=\"costow\" class=\"input-text\" value=\"21\" onclick=\"pintar(document.getElementById('costow').value);\" >\n";
                                  $salida .= "                       </td>\n";
                                  $salida .= "                       <td  COLSPAN='1' id=\"td21\" align=\"left\" class=\"modulo_list_claro\"> \n";
                                  $salida .= "                          <input type=\"text\" style=\"text-align:right\" id=\"op21\" size='12' class=\"input-text\" value=\"\" onkeyup=\"Calcular(document.getElementById('costow').value,document.getElementById('cantidad').value,document.getElementById('gravamen').value,document.getElementById('op21').value);\" onkeypress=\"return acceptNum(event);\" onclick=\"Clear3000();\" disabled>\n";
                                  $salida .= "                       </td>\n";
                                  $salida .= "                       <td  align=\"left\" class=\"modulo_table_list_title\">\n";
                                  $salida .= "                          <input type=\"radio\" id=\"costow\" name=\"costow\" class=\"input-text\" value=\"22\" onclick=\"pintar(document.getElementById('costow').value);\">\n";
                                  $salida .= "                       </td>\n";
                                  $salida .= "                       <td  COLSPAN='1' id=\"td22\" align=\"left\" class=\"modulo_list_claro\"> \n";
                                  $salida .= "                          <input type=\"text\" style=\"text-align:right\" id=\"op22\" size='12' class=\"input-text\" value=\"\" disabled onkeyup=\"Calcular(document.getElementById('costow').value,document.getElementById('cantidad').value,document.getElementById('gravamen').value,document.getElementById('op22').value);\" onkeypress=\"return acceptNum(event);\" onclick=\"Clear3000();\">\n";
                                  $salida .= "                       </td>\n";
                                  $salida .= "                    </tr>\n";
                                  $salida .= "                  </table>\n";
  $salida .= "                 </form>\n";
  $salida .= "                    <tr>\n"; 
  $salida .= "                       <td COLSPAN='4' align=\"left\" class=\"modulo_list_claro\">\n";
  $salida .= "                        ";
  $salida .= "                       </td>\n"; 
//   $salida .= "                       <td  align=\"left\" class=\"modulo_list_claro\"> \n";
//   $salida .= "                        ";
//   $salida .= "                       </td>\n";
  $salida .= "                       <td COLSPAN='1' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                       GRAVAMEN";
  $salida .= "                       </td>\n"; 
  $salida .= "                       <td  align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                          <input type=\"text\" id=\"gravamen\" size='10' class=\"input-text\" value=\"\" onclick=\"limpiar200z();Clear3000();\" onkeypress=\"return acceptNum(event);\">&nbsp;% \n";
  $salida .= "                       </td>\n";
  $salida .= "                    </tr>\n"; 
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  COLSPAN='6' align=\"RIGHT\" class=\"modulo_list_claro\">\n";                          //                         $doc_tmp_id,                                            $codigo_producto,                     $cantidad,                                       $porcentaje_gravamen,                           $total_costo,                   $usuario_id=null
  $salida .= "                          <input type=\"button\" id=\"nuevo\" value=\"SELECCIONAR PRODUCTO\" class=\"input-bottom\" onClick=\"javascript:GuardarProductoTemporal('".$bodegas_doc_id."','".$tmp_doc_id."',document.getElementById('codigo').value,document.getElementById('cantidad').value,document.getElementById('gravamen').value,document.getElementById('op22').value,'".UserGetUID()."');\">";//
  $salida .= "                       </td>\n"; 
  $salida .= "                    </tr>\n";
  $salida .= "                 </table>";
  $salida .= "               </div>";
  $salida .= "               <br>";
  $salida .= "               <div id='tablaoide'>\n";
  $salida .= "               </div>";
  return $salida;
 }*/
 // CREAR LA CAPITA
	 function CrearVentana($tmn,$Titulo)
   {
    $salida .= "<script>\n";
    $salida .= "  var contenedor = 'Contenedor';\n";
    $salida .= "  var titulo = 'titulo';\n";
    $salida .= "  var hiZ = 4;\n";
    $salida .= "  function OcultarSpan()\n";
    $salida .= "  { \n";
    $salida .= "    try\n";
    $salida .= "    {\n";
    $salida .= "      e = xGetElementById('Contenedor');\n";
    $salida .= "      e.style.display = \"none\";\n";
    $salida .= "    }\n";
    $salida .= "    catch(error){}\n";
    $salida .= "  }\n";
    //Mostrar Span
    $salida .= "  function MostrarSpan()\n";
    $salida .= "  { \n";
    $salida .= "    try\n";
    $salida .= "    {\n";
    $salida .= "      e = xGetElementById('Contenedor');\n";
    $salida .= "      e.style.display = \"\";\n";
    $salida .= "      Iniciar();\n";
    $salida .= "    }\n";
    $salida .= "    catch(error){alert(error)}\n";
    $salida .= "  }\n";

    $salida .= "  function MostrarTitle(Seccion)\n";
    $salida .= "  {\n";
    $salida .= "    xShow(Seccion);\n";
    $salida .= "  }\n";
    $salida .= "  function OcultarTitle(Seccion)\n";
    $salida .= "  {\n";
    $salida .= "    xHide(Seccion);\n";
    $salida .= "  }\n";

    $salida .= "  function Iniciar()\n";
    $salida .= "  {\n";
    $salida .= "    contenedor = 'Contenedor';\n";
    $salida .= "    titulo = 'titulo';\n";
    $salida .= "    ele = xGetElementById('Contenido');\n";
    $salida .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
    $salida .= "    ele = xGetElementById(contenedor);\n";
    $salida .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
    $salida .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
    $salida .= "    ele = xGetElementById(titulo);\n";
    $salida .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
    $salida .= "    xMoveTo(ele, 0, 0);\n";
    $salida .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $salida .= "    ele = xGetElementById('cerrar');\n";
    $salida .= "    xResizeTo(ele,20, 20);\n";
    $salida .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
    $salida .= "  }\n";

    $salida .= "  function myOnDragStart(ele, mx, my)\n";
    $salida .= "  {\n";
    $salida .= "    window.status = '';\n";
    $salida .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
    $salida .= "    else xZIndex(ele, hiZ++);\n";
    $salida .= "    ele.myTotalMX = 0;\n";
    $salida .= "    ele.myTotalMY = 0;\n";
    $salida .= "  }\n";
    $salida .= "  function myOnDrag(ele, mdx, mdy)\n";
    $salida .= "  {\n";
    $salida .= "    if (ele.id == titulo) {\n";
    $salida .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
    $salida .= "    }\n";
    $salida .= "    else {\n";
    $salida .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
    $salida .= "    }  \n";
    $salida .= "    ele.myTotalMX += mdx;\n";
    $salida .= "    ele.myTotalMY += mdy;\n";
    $salida .= "  }\n";
    $salida .= "  function myOnDragEnd(ele, mx, my)\n";
    $salida .= "  {\n";
    $salida .= "  }\n";
    
    
    $salida.= "function Cerrar(Elemento)\n";
         $salida.= "{\n";
         $salida.= "    capita = xGetElementById(Elemento);\n";
         $salida.= "    capita.style.display = \"none\";\n";
         $salida.= "}\n";
    
    
    
    $salida .= "</script>\n";
    $salida .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
    $salida .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
    $salida .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
    $salida .= "  <div id='Contenido' class='d2Content'>\n";
    //En ese espacio se visualiza la informacion extraida de la base de datos.
    $salida .= "  </div>\n";
    $salida .= "</div>\n";
    return $salida;
   }
    /**
    * Funcion donde se crea la interface para la creacion de documentos
    *
    * @param integer $bodegas_doc_id
    * @param integer $tmp_doc_id
    * @param array $datos
    * @param string $identificdor Identifica si el pedido corresponde a un cliente o a una farmacia
    *
    * @return string
    */
    function ColocarProductosPedFarmacia($bodegas_doc_id,$tmp_doc_id,$datos,$identificador)
    {
      $consulta = new MovBodegasSQL();
      $objClass=new doc_bodegas_E008();
      $usuariotmp=$consulta->Consultausuaritmp($tmp_doc_id,$bodegas_doc_id);
	  
      $devolucion=$consulta->ConsultaDevolucion_doc($datos['tipo_doc_bodega_id'],$tmp_doc_id);
      $cl = AutoCarga::factory('ClaseUtil');
      $pedido = array();
      if($identificador == "FM")
        $pedido = $consulta->FarmaciaPedidosTmp($tmp_doc_id);
      else if($identificador == "CL") 
        $pedido = $consulta->ClientesPedidosTmp($tmp_doc_id);
        
      $estadosEmpresa=$consulta->ConsultaEmpresa($datos['empresa_id']);
      $documentos = $consulta->ConsultaPardocg($tmp_doc_id);
      
      $action['buscar_ruta'] = ModuloGetURL('app','Inv_MovimientosBodegas','user','FormaRutasViajes',array('empresa_id'=>$_SESSION['EMPRESAS']['empresa_id']));
	  $salida .=  $cl->RollOverFilas();
      
	  
	  $salida .= "<script>";
      $salida .= "  function Desaparecer()";
      $salida .= "  {";
      $salida .= "    document.getElementById('productos_ordenCompra').innerHTML = \"\";";
      $salida .= "  }";      
      $salida .= "  function AsignarRuta()\n";
      $salida .= "  {\n";
      $salida .= "    indice = document.getElementById('ruta_viaje').selectedIndex;\n";
      $salida .= "    texto = document.getElementById('ruta_viaje').options[indice].text;\n ";
      $salida .= "    if(indice == 0)\n";
      $salida .= "      document.getElementById('ruta_asignada').innerHTML = '';\n";
      $salida .= "    else\n";
      $salida .= "      document.getElementById('ruta_asignada').innerHTML = texto;\n";
      $salida .= "  }\n";
      $salida .= "  function Observacion(doc_tmp_id,codigo_producto,observacion)\n";
      $salida .= "  {\n";
      $salida .= "    xajax_IngresarObservacion(doc_tmp_id,codigo_producto,observacion);\n";
      $salida .= "  }\n"; 
      $salida .= "</script>";
      $salida .= "<br>";
      
      $salida .= "<div id=\"ventana1\"></div>";
      $salida .= "<div id=\"ventanados\">";
      $salida .= "  <center><div id=\"mensajes\"></div></center>\n";
      $salida .= "  <br>";
      
  		$salida .= "  <table width=\"65%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">\n";
      $estadosEmpresa = $consulta->ConsultaEmpresa($_SESSION['EMPRESAS']['empresa_id']);
      
      if($estadosEmpresa['sw_estados']==1)
      {
        $estadostmp = $consulta->ConsultaEstadosTmp($_SESSION['SYSTEM_USUARIO_ID'],$tmp_doc_id);    
        $sw_verificono = $consulta->ConsultaSw_verificar($datos['tipo_doc_bodega_id'],$tmp_doc_id);
        $documentos2 = $consulta->ConsultaEstadosPermisosp($datos['tipo_doc_bodega_id'],$tmp_doc_id);
        $si_esta = $consulta->ConsultaPardocg($tmp_doc_id);
        $tipo_documento = $datos['tipo_doc_bodega_id'];
        
        $salida .= "	<tr class=\"modulo_table_list_title\">";
        $salida .= "    	<td colspan=\"2\" width=\"30%\" align=\"left\" >NUMERO DE PEDIDO:</td>\n";
		$salida .= "    	<td width='%' align=\"left\" class=\"modulo_list_claro\">";
		$salida .= "		".$pedido['solicitud_prod_a_bod_ppal_id']."".$pedido['pedido_cliente_id'];
		$salida .= "		</td>";
		$salida .= "	</tr>";
        $salida .= "	<tr class=\"modulo_table_list_title\">";
		$salida .= "    	<td colspan=\"2\" width=\"30%\" align=\"left\" >ESTADO</td>\n";
        $salida .= "    	<td width='%' align=\"left\" class=\"modulo_list_claro\">";
        $salida .= "      		<select class=\"select\" name=\"estados\" id=\"estados\" onchange=\"ActuEstado($bodegas_doc_id,$tmp_doc_id,document.getElementById('estados').value,'$tipo_documento')\">";
        $salida .= "        	<option value=\"-1\">-- Seleccionar --</option>\n";
        $selected ="";
        $contar=count($si_esta);
        
        $k=0;
        $m=0;
        for($i=0;$i<$contar;$i++)
        {
          if($si_esta[$i]['sw_verifico']==1)
            $k++;
          else
            $m++;
        }
    
        if($k!=$contar)
        {
          if($sw_verificono)
          {
            foreach($sw_verificono as $indice=>$valor)
            {
              $salida .= "  <option value=\"".$valor['abreviatura']."\" ".$selected.">".$valor['descripcion']."</option>";
            }
          }
          else
          {
            foreach ($documentos2 as $indice=>$valor)
            { 
              $salida .= "  <option value=\"".$valor['abreviatura']."\" ".$selected.">".$valor['descripcion']."</option>";
            }
          }
        }
        $salida .= "      </select>\n";
        $salida .= "    </td>";
        $salida .= "  </tr>";
        $salida .= "  <tr class=\"modulo_table_list_title\">";
        $salida .= "    <td colspan=\"2\" align=\"left\">RUTA DE VIAJE</td>\n";
        
        $numero = ($pedido['solicitud_prod_a_bod_ppal_id'])? $pedido['solicitud_prod_a_bod_ppal_id']:$pedido['pedido_cliente_id'];
        $ruta = $consulta->ConsultaRuta($_SESSION['EMPRESAS']['empresa_id']);
        $tm_docu = $consulta->ConsultaTmp($_SESSION['SYSTEM_USUARIO_ID'],$tmp_doc_id);
        if($identificador == "FM")
          $si_rotulocaja = $objClass->ConsulCajaRotulo($tmp_doc_id,$pedido,$identificador);
            else if($identificador == "CL") 
          $si_rotulocaja = $objClass->ConsulCajaRotulo($tmp_doc_id,$pedido,$identificador);
         
        $contar1 = count($tm_docu);
        $salida .= "    <td align=\"left\" class=\"modulo_list_claro\">";
        $salida .= "      <select name=\"ruta_viaje\" id=\"ruta_viaje\" class=\"select\" onchange=\"AsignarRuta()\">\n";
        $salida .= "        <option value=\"-1\">--Seleccionar--</option>\n";
        foreach($ruta as $key => $dtl)
          $salida .= "          <option value=\"".$dtl['rutaviaje_destinoempresa_id']."\">".$dtl['ruta']." - ".$dtl['descripcion']."</option>\n";
        
        $salida .= "    </td>\n";
        $salida .= "  </tr>\n";
      
        $cont=count($documentos); 
        
        $display = "none";
        if($estadosEmpresa['sw_estados']==1)
        {
          foreach($documentos as $k1 => $dtl)
            if($dtl['sw_verifico']=="1")
              $display = "block";
        }
        
        $salida .= "  <tr>";
        $salida .= "    <td colspan=\"3\">\n";
        $salida .= "      <div id=\"rotulo_empresa\" style=\"display:".$display."\">\n";
        $salida .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "  <tr class=\"modulo_table_list_title\">";
        //$salida .= "<pre>".print_r($identificador,true)."</pre>";
        $salida .= "    <td colspan=\"3\">ROTULO DE CAJA</td>\n";
        $salida .= "  </tr>";
        $salida .= "  <tr class=\"modulo_table_list_title\">";
        $salida .= "    <td colspan=\"2\" width=\"30%\" align=\"left\">CLIENTE</td>\n";
        $salida .= "    <td align=\"left\" class=\"modulo_list_claro\">";
        $salida .= "      <input type=\"text\" class=\"input-text\" style=\"width:100%\" name=\"cliente\" id=\"cliente\"value=\"".$si_rotulocaja['nombre_tercero']."\">\n";
        $salida .= "    </td>\n";
        $salida .= "  </tr>";
        $salida .= "  <tr class=\"modulo_table_list_title\">\n";
        $salida .= "    <td colspan=\"2\" align=\"left\">DIRECCION</td>\n";
        $salida .= "    <td align=\"left\" class=\"modulo_list_claro\">";
        $salida .= "      <input type=\"text\" class=\"input-text\" name=\"direccion\" style=\"width:100%\" id=\"direccion\"value=\"".$si_rotulocaja['direccion']."\">\n";
        $salida .= "    </td>\n";
        $salida .= "  </tr>\n";
        $salida .= "  <tr class=\"modulo_table_list_title\" >";
        $salida .= "    <td colspan=\"2\" align=\"left\">RUTA</td>\n";
        $salida .= "    <td align=\"left\" class=\"modulo_list_claro\">";
        $salida .= "      <label class=\"normal_10AN\" id=\"ruta_asignada\">".$si_rotulocaja['ruta']."</label>\n";
        $salida .= "    </td>\n";
        $salida .= "  </tr>\n";
        $salida .= " <td width='20%' align=\"center\" class=\"modulo_list_claro\" align=\"center\" colspan='5'>\n";
        $salida .= "   <input type=\"button\" id='Guardar' class=\"input-submit\" value=\"Guardar\" onclick=\"xajax_GuardarRotuloCaja('".$tmp_doc_id."',document.getElementById('cliente').value,document.getElementById('direccion').value,'".$cant."',document.getElementById('ruta_asignada').innerHTML,'".$conte."','".$numero."','".$identificador."');\">\n";
        $salida .= " </td>\n"; 
        $salida .= "      </table>\n"; 
        $salida .= "    </div>\n"; 
        $salida .= "  </td>\n"; 
        $salida .= "</tr>\n"; 
      }
      $salida .= "</table>";
      $dias[0] = "DOMINGO";
      $dias[1] = "LUNES";
      $dias[2] = "MARTES";
      $dias[3] = "MIERCOLES";
      $dias[4] = "JUEVES";
      $dias[5] = "VIERNES";
      $dias[6] = "SABADO";
      $colores['PN'] = ModuloGetVar('app','ReportesInventariosGral','color_producto_pendiente');
      $colores['NE'] = ModuloGetVar('app','ReportesInventariosGral','color_producto_bloqueo_envio');
	  
	  if($identificador=='FM')
	  {
	  $salida .= "	<br>\n";
      $salida .= "	<table align=\"center\" border=\"0\" width=\"70%\" class=\"modulo_table_list\">\n";
      $salida .= "		<tr class=\"label\" >\n";
      $salida .= "		  <td colspan=\"4\" class=\"formulacion_table_list\">CONVENCIONES</td>\n";
      $salida .= "		</tr>\n";
      $salida .= "		<tr class=\"label\" >\n";
      $salida .= "			<td width=\"10%\" style=\"background:".$colores['PN']."\" >&nbsp;</td>\n";
      $salida .= "			<td width=\"40%\" >PENDIENTE</td>\n";      
      $salida .= "			<td width=\"10%\" style=\"background:".$colores['NE']."\" >&nbsp;</td>\n";
      $salida .= "			<td width=\"40%\" >NO SE PUEDE ENVIAR LOS DIAS ".$dias[date("w")]."</td>\n";
      $salida .= "		</tr>\n";
      $salida .= "  </table><br>\n";
	  }
      $salida .= "	<div id=\"justificaciones\"  style=\"border:1px inset;margin:0px;padding:6px;overflow:auto;width:100%;height:150px;text-align:left;display:none\">";
      $salida .= "	</div>";
	  
	  $parBusqueda=$consulta->ParmaBusquedaDoc($datos['empresa_id'],UserGetUID());
  		$salida .= "	<div id=\"listadoP\">\n";
  		$salida .= "		<script>\n";
  		$salida .= "			xajax_GetItems('".$tmp_doc_id."','".$bodegas_doc_id."','".$identificador."');\n";
  		$salida .= "		</script>\n";
  		$salida .= "	</div>";
  		$salida .= "</div>";
	
		$salida .= "<script>";
		$salida .= "	function Iniciar4(tit)\n";
		$salida .= "	{\n";
		$salida .= "	 	contenedor = 'd2Container';\n";
		$salida .= "		titulo = 'titulo';\n";
		$salida .= "		document.getElementById('error').innerHTML = '';\n";
		$salida .= "		document.getElementById(titulo).innerHTML = tit;\n";
		$salida .= "		ele = xGetElementById(contenedor);\n";
		$salida .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+50);\n";
		$salida .= "	  xResizeTo(ele,800,'auto');\n";
		$salida .= "		ele = xGetElementById('d2Contents');\n";
		$salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop()+50);\n";
		$salida .= "	  xResizeTo(ele,800, 'auto');\n";
		$salida .= "		ele = xGetElementById(titulo);\n";
		$salida .= "	  xResizeTo(ele,800, 20);\n";
		$salida .= "		xMoveTo(ele, 0, 0);\n";
		$salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
		$salida .= "		ele = xGetElementById('cerrar');\n";
		$salida .= "	  xResizeTo(ele,20, 20);\n";
		$salida .= "		xMoveTo(ele, 780, 0);\n";
		$salida .= "    ele = xGetElementById('d2Contents2');\n";
		$salida .= "	}\n";
		$salida .= "</script>";
	
	/*$salida .= "<script>";
	$salida .= "   function Iniciar4(tit)\n";
	$salida .= "   {\n";
	$salida .= "       contenedor1 = 'd2Container';\n";
	$salida .= "       titulo1 = 'titulo';\n";
	$salida .= "       document.getElementById(titulo1).innersalida = '<center>'+tit+'</center>';\n";
	$salida .= "        Capa = xGetElementById(contenedor1);\n"; 
	$salida .= "       xResizeTo(Capa, 600, 400);\n";
	$salida .= "       xMoveTo(Capa, xClientWidth()/11, xScrollTop()+100);\n";
	$salida .= "       ele = xGetElementById(titulo1);\n";
	$salida .= "       xResizeTo(ele, 580, 20);\n";
	$salida .= "       xMoveTo(ele, 0, 0);\n";
	$salida .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
	$salida .= "       ele = xGetElementById('cerrar');\n";
	$salida .= "       xResizeTo(ele, 20, 20);\n";
	$salida .= "       xMoveTo(ele, 580, 0);\n";
	$salida .= "   }\n";
	$salida .= "</script>";*/
		
      $salida .= "<div id=\"d2Container\" class=\"d2Container\" style=\"display:none\" >";
  		$salida .= "    <div id=\"titulo\" class=\"draggable\" style=\"text-transform: uppercase;\"></div>\n";
  		$salida .= "    <div id=\"cerrar\" class=\"draggable\"> <a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container'),Desaparecer('');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
  		$salida .= "    <div id=\"error\" class=\"label_error\" style=\"text-transform: uppercase; text-align:center;\"></div>\n";
  		$salida .= "    <div id=\"d2Contents\"  >\n";     
      $salida .= "			<input type=\"hidden\" id=\"tmp_doc_id1\" value=\"".$tmp_doc_id."\">\n";
  		$salida .= "			<input type=\"hidden\" id=\"empresa_idz\" value=\"".$datos['empresa_id']."\">\n";
  		$salida .= "			<input type=\"hidden\" id=\"centro_utilidadz\" value=\"".$datos['centro_utilidad']."\">\n";
  		$salida .= "			<input type=\"hidden\" id=\"bodegaz\" value=\"".$datos['bodega']."\">\n";
  		$salida .= "			<input type=\"hidden\" id=\"pagina\" value=\"1\">\n";
  		$salida .= "			<input type=\"hidden\" id=\"identify\" value=\"".$identificador."\">\n";
  		$salida .= "			<input type=\"hidden\" id=\"bodega_doc_id_\" value=\"".$bodegas_doc_id."\">\n";
  		$salida .= "			<br>\n";
  		$salida .= "      <div id=\"listado_pro\">";
  		$salida .= "      </div>\n";
  		$salida .= "   </div>\n";     
  		$salida .= "</div>";
      
      $fnc = "  BuscarProductos('1',document.buscador.tip_bus.value,document.buscador.criterio.value,'".$tmp_doc_id."','".$bodegas_doc_id."','".$datos['empresa_id']."','".$datos['centro_utilidad']."','".$identificador."');";
      $salida .= "<br>";
      $salida .= "<form name=\"buscador\" id=\"buscador\" action=\"javascript:".$fnc."\" method=\"post\">\n";
  		$salida .= "  <table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";         
  		$salida .= "	  <tr class=\"formulacion_table_list\">\n";
  		$salida .= "		  <td colspan=\"4\">BUSCADOR DE PRODUCTOS</td>\n";
  		$salida .= "		</tr>\n";
  		$salida .= "		<tr class=\"modulo_list_claro\">\n";
  		$salida .= "		  <td width=\"25%\" class=\"formulacion_table_list\">TIPO DE BUSQUEDA</td>\n";
  		$salida .= "			<td >\n";
      $salida .= "				<select id=\"tip_bus\" name=\"tip_bus\" class=\"select\" onchange=\"document.criterio.buscador.value = ''\">";
      $salida .= "          <option value=\"1\">CODIGO BARRAS</option> \n";
  		$salida .= "				  <option value=\"2\">DESCRIPCION</option> \n";
  		$salida .= "        </select>\n";
  		$salida .= "			</td>\n";
  		$salida .= "			<td width=\"50%\" align=\"left\">\n";
  		$salida .= "			  <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"50\" onkeypress=\"return acceptm(event);\" onkeydown=\"recogerTeclaBus1(event)\" value=\"\">\n";//
  		$salida .= "			</td>\n";  		
      $salida .= "			<td width=\"%\" align=\"left\">\n";
  		$salida .= "			  <input type=\"submit\" class=\"input-submit\" name=\"buscar\" size=\"50\" value=\"Buscar\">\n";//
  		$salida .= "			</td>\n";
  		$salida .= "		</tr>\n";
  		$salida .= "	</table>\n";
  		$salida .= "	<div class=\"label_error\" id=\"error_buscador\" style=\"text-align:center\"></div>\n";
  		$salida .= "</form>\n";
      
	
	
	  
      $salida .= "<div id=\"d2Container2\" class=\"d2Container\" style=\"display:none\">";
  		$salida .= "    <div id=\"titulo2\" class=\"draggable\" style=\"text-transform: uppercase;\"></div>\n";
  		$salida .= "    <div id=\"cerrar2\" class=\"draggable\"> <a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container2');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
  		$salida .= "    <div id=\"error2\" class=\"label_error\" style=\"text-transform: uppercase; text-align:center;\"></div>\n";
  		$salida .= "    <div id=\"d2Contents2\">\n";
  		$salida .= "    </div>\n";
  		$salida .= "</div>\n";
  		
      $salida .= "<script>\n";
  		$salida .= "	 	var contenedor = '';\n";
  		$salida .= "		var titulo = '';\n";
  		$salida .= "	 	var hiZ=2;\n";
  		
  		$salida .= "	function ValidarCantidad(campo,valor,cant_sol,capa)\n";
  		$salida .= "	{\n";
  		$salida .= "		document.getElementById(campo).style.background='';\n";
  		$salida .= "		document.getElementById('error').innerHTML='';\n";
  		$salida .= "		if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='')\n";
  		$salida .= "		{\n";
  		$salida .= "			document.getElementById(campo).value='';\n";
  		$salida .= "			document.getElementById(campo).style.background='#ff9595';\n";
  		$salida .= "			document.getElementById('error').innerHTML='<center>CANTIDAD NO VALIDA</center>';\n";
  		$salida .= "			document.getElementById(capa).style.display=\"none\"\n";
  		$salida .= "		}\n";
  		$salida .= "		else{\n";
  		$salida .= "			document.getElementById(capa).style.display=\"\"\n";
  		$salida .= "		}\n";
  		$salida .= "	}\n";
  		$tmn = "600";
      $tmny = "700";
  		
		$salida .= "	function Iniciar(tit)\n";
  		$salida .= "	{\n";
  		$salida .= "	 	contenedor = 'd2Container';\n";
  		$salida .= "		titulo = 'titulo';\n";
  		$salida .= "		document.getElementById('error').innerHTML = '';\n";
  		//$salida .= "		document.getElementById('criterio').value = '';\n";
  		$salida .= "		document.getElementById(titulo).innerHTML = tit;\n";
  		$salida .= "		ele = xGetElementById(contenedor);\n";
  		$salida .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+50);\n";
    	$salida .= "	  xResizeTo(ele,400,'auto');\n";
  		$salida .= "		ele = xGetElementById('d2Contents');\n";
      $salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop());\n";
  		$salida .= "	  xResizeTo(ele,400, 'auto');\n";
  		$salida .= "		ele = xGetElementById(titulo);\n";
  		$salida .= "	  xResizeTo(ele,700, 20);\n";
  		$salida .= "		xMoveTo(ele, 0, 0);\n";
  		$salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
  		$salida .= "		ele = xGetElementById('cerrar');\n";
  		$salida .= "	  xResizeTo(ele,20, 20);\n";
      $salida .= "		xMoveTo(ele, 680, 0);\n";
  		$salida .= "    ele = xGetElementById('d2Contents2');\n";
      $salida .= "	}\n";
	  
      $salida .= "	function Iniciar2(tit)\n";
  		$salida .= "	{\n";
  		$salida .= "	 	contenedor = 'd2Container2';\n";
  		$salida .= "		titulo = 'titulo2';\n";
  		$salida .= "		document.getElementById('error2').innerHTML = '';\n";
  		$salida .= "		document.getElementById(titulo).innerHTML = tit;\n";
  		$salida .= "		ele = xGetElementById(contenedor);\n";
  		$salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+250);\n";
  		$salida .= "	  xResizeTo(ele,400,'auto');\n";
      $salida .= "		ele = xGetElementById('d2Contents2');\n";
      $salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop());\n";
      $salida .= "	  xResizeTo(ele,400, 'auto');\n";
      $salida .= "		ele = xGetElementById(titulo);\n";
  		$salida .= "	  xResizeTo(ele,380, 20);\n";
  		$salida .= "		xMoveTo(ele, 0, 0);\n";
  		$salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
  		$salida .= "		ele = xGetElementById('cerrar2');\n";
      $salida .= "	  xResizeTo(ele,20, 20);\n";
  		$salida .= "		xMoveTo(ele, 380, 0);\n";
  		
      $salida .= "	}\n";

  		$salida .= "	function myOnDragStart(ele, mx, my)\n";
  		$salida .= "	{\n";
  		$salida .= "	  window.status = '';\n";
  		$salida .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
  		$salida .= "	  else xZIndex(ele, hiZ++);\n";
  		$salida .= "	  ele.myTotalMX = 0;\n";
  		$salida .= "	  ele.myTotalMY = 0;\n";
  		$salida .= "	}\n";
  		
  		$salida .= "	function myOnDrag(ele, mdx, mdy)\n";
  		$salida .= "	{\n";
  		$salida .= "	  if (ele.id == titulo) {\n";
  		$salida .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
  		$salida .= "	  }\n";
  		$salida .= "	  else {\n";
  		$salida .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
  		$salida .= "	  }  \n";
  		$salida .= "	  ele.myTotalMX += mdx;\n";
  		$salida .= "	  ele.myTotalMY += mdy;\n";
  		$salida .= "	}\n";
  		$salida .= "	function myOnDragEnd(ele, mx, my)\n";
  		$salida .= "	{}\n";
  		
      $salida .= "	function MostrarSpan(Seccion)\n";
  		$salida .= "	{\n";
  		$salida .= "		e = xGetElementById(Seccion);\n";
  		$salida .= "		e.style.display = \"\";\n";
  		$salida .= "	}\n";
  		$salida .= "	function Cerrar(Seccion)\n";
  		$salida .= "	{ \n";
  		$salida .= "		e = xGetElementById(Seccion);\n";
  		$salida .= "		e.style.display = \"none\";\n";
  		$salida .= "	}\n";  		
      $salida .= "	function RemoverItem(doc_tmp_id,bodegas_doc_id,item,identificador)\n";
  		$salida .= "	{ \n";
  		$salida .= "		xajax_RemoverItem(doc_tmp_id,bodegas_doc_id,item,identificador);\n";
  		$salida .= "	}\n";
  		$salida .= "</script>\n";
      
      //Convenciones
  $colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
  $colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');
  
  $salida .= "                <table class=\"modulo_table_list\" width=\"35%\" align=\"center\">";
  $salida .= "                 <td style=\"background:".$colores['PV']."\" width=\"50%\" align=\"center\">";
  $salida .= "                  PROD. PROXIMO A VENCER";
  $salida .= "                  </td>";
  $salida .= "                 <td style=\"background:".$colores['VN']."\" width=\"50%\" align=\"center\">";
  $salida .= "                  PROD. VENCIDO";
  $salida .= "                  </td>";
  $salida .= "                 </table>";
  $salida .= "               <br>";
      
      
      $salida .= "<div id=\"productos_ordenCompra\"></div>\n";
      $salida .= "<div id='tablaoide'></div>";
  		//$salida .= "<script>\n";
      //$salida .= "  BuscarProductos('1','0','0','".$tmp_doc_id."','".$bodegas_doc_id."','".$datos['empresa_id']."','".$datos['centro_utilidad']."','".$identificador."');";
  		//$salida .= "</script>\n";
      $xml = AutoCarga::factory("ReportesCsv");
      $salida .= $xml->GetJavacriptReporteFPDF('app','Inv_MovimientosBodegas','documentoE008',array(),array("interface"=>5));
      $fnc   = $xml->GetJavaFunction();
      $salida .= $xml->GetJavacriptReporteFPDF('app','Inv_MovimientosBodegas','rotuloE008',array(),array("interface"=>5));
      $fnci   = $xml->GetJavaFunction();
      SessionSetVar("funcion_E008",$fnc);
      SessionSetVar("rotulo_E008",$fnci);
      return $salida;
    }
 
function ColocarProductos($bodegas_doc_id,$datos,$tmp_doc_id)
 {
    $consulta = new MovBodegasSQL();
    $objClass=new doc_bodegas_E008();
    $usuariotmp=$consulta->Consultausuaritmp($tmp_doc_id,$bodegas_doc_id);
    $devolucion=$consulta->ConsultaDevolucion_doc($datos['tipo_doc_bodega_id'],$tmp_doc_id);
    //$si_rotulocaja=$objClass->ConsulCajaRotulo($tmp_doc_id,$pedido['solicitud_prod_a_bod_ppal_id']);
   
    
    $javaC = "<script>\n";
    $javaC .= "var contenedor1=''\n";
    $javaC .= "   var titulo1=''\n";
    $javaC .= "   var hiZ = 2;\n";
    $javaC .= "   var DatosFactor = new Array();\n";
    $javaC .= "   var EnvioFactor = new Array();\n";
    $javaC .= "   function Rata()\n";
    $javaC .= "   {\n";
    $javaC .= "   alert('JUKILO');";
    $javaC .= "   }\n";
    $javaC .= "   function Iniciar4(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorBus';\n";
    $javaC .= "       titulo1 = 'tituloBus';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 600, 400);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/11, xScrollTop()+100);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 580, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarBus');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 580, 0);\n";
    $javaC .= "   }\n";
    $javaC .= "   function IniciarUsu(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorCre';\n";
    $javaC .= "       titulo1 = 'tituloCre';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 500, 380);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+65);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 480, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarCre');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 480, 0);\n";
    $javaC .= "   }\n";
    $javaC .= "   function IniciarB3(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorB3';\n";
    $javaC .= "       titulo1 = 'tituloB3';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n";
    $javaC .= "       xResizeTo(Capa, 200, 160);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-0);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 180, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarB3');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 180, 0);\n";
    $javaC .= "   }\n";
    $javaC.= "</script>\n";
    $salida.= $javaC;
    $javaC1.= "<script>\n";
    $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
    $javaC1 .= "   {\n";
    $javaC1 .= "     window.status = '';\n";
    $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
    $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
    $javaC1 .= "     ele.myTotalMX = 0;\n";
    $javaC1 .= "     ele.myTotalMY = 0;\n";
    $javaC1 .= "   }\n";
    $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";
    $javaC1 .= "   {\n";
    $javaC1 .= "     if (ele.id == titulo1) {\n";
    $javaC1 .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
    $javaC1 .= "     }\n";
    $javaC1 .= "     else {\n";
    $javaC1 .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
    $javaC1 .= "     }  \n";
    $javaC1 .= "     ele.myTotalMX += mdx;\n";
    $javaC1 .= "     ele.myTotalMY += mdy;\n";
    $javaC1 .= "   }\n";
    $javaC1 .= "   function myOnDragEnd(ele, mx, my)\n";
    $javaC1 .= "   {\n";
    $javaC1 .= "   }\n";
    $javaC1.= "function MostrarCapa(Elemento)\n";
    $javaC1.= "{\n";
    $javaC1.= "    capita = xGetElementById(Elemento);\n";
    $javaC1.= "    capita.style.display = \"\";\n";
    $javaC1.= "}\n";
    $javaC1.= "function Cerrar(Elemento)\n";
    $javaC1.= "{\n";
    $javaC1.= "    capita = xGetElementById(Elemento);\n";          
    $javaC1.= "    capita.style.display = \"none\";\n";          
    $javaC1.= "}\n";                    
    $javaC1.= "</script>\n";
    $salida.="
    <script language=\"javaScript\">
      function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

                function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }

               MostrarProductoxjs('".$bodegas_doc_id."','".$tmp_doc_id."','".UserGetUID()."');
    </script>";
    $salida.= $javaC1;
    $salida .= " <div id='ContenedorB3' class='d2Container' style=\"display:none;\">";
    $salida .= "    <div id='tituloB3' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $salida .= "    <div id='cerrarB3' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorB3');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $salida .= "    <div id='errorB3' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $salida .= "    <div id='ContenidoB3'  class='d2Content' style='z-index:10;'>\n";
    $salida .= "    </div>\n";
    $salida .= " </div>\n";
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se BVUSCA UN PRODUCTO
**********************************************************************************/
    $salida.="<div id='ContenedorBus' class='d2Container' style=\"display:none\">";
    $salida .= "    <div id='tituloBus' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $salida .= "    <div id='cerrarBus' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorBus');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $salida .= "    <div id='errorBus' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $salida .= "    <div id='ContenidoBus'>\n";
    /****************************************************************************/

   
            $salida .= "                          <input type=\"hidden\" id=\"empresa_idz\" value=\"".$datos['empresa_id']."\">\n";
            $salida .= "                          <input type=\"hidden\" id=\"centro_utilidadz\" value=\"".$datos['centro_utilidad']."\">\n";
            $salida .= "                          <input type=\"hidden\" id=\"bodegaz\" value=\"".$datos['bodega']."\">\n";
            $salida .= "                 <form name=\"jukilo\" action=\"".$accion1."\" method=\"post\">\n";
            $salida .= "                 <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";         
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td COLSPAN='2' align=\"center\">\n";
            $salida .= "                          BUSCADOR DE PRODUCTOS";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td width=\"35%\" align=\"center\">\n";
            $salida .= "                          TIPO DE BUSQUEDA";
            $salida .= "                       <select id=\"tip_bus\" name=\"tip_bus\" class=\"select\" onchange=\"Aplicar(this.value)\">";
            $salida .= "                           <option value=\"1\" SELECTED>DESCRIPCION</option> \n";
            $salida .= "                           <option value=\"2\"># CODIGO</option> \n";
            $salida .= "                       </select>\n";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"55%\" align=\"left\" id=\"ventanatabla\">\n";
            $salida .= "                          DESCRIPCION";                                                                                                             
            $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"30\" onkeypress=\"return acceptm(event);\" onkeydown=\"recogerTeclaBus(event)\" value=\"\">\n";//
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                </table>\n";
            $salida .= "                </form>\n";
            $salida .= "                 <br>\n";
            $salida .="              <div id=\"tabelos\">";
            $salida .="              </div>\n";
            $salida .= "   </div>\n";     
            $salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/   


  $path = SessionGetVar("rutaImagenes");
  $salida .= "          <br>\n";
  $salida .= "    <div id='error_doc' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
  $salida .= "               <div id=\"ventana1\">\n";
  $salida .= "                 <form name=\"jukilo4\"  method=\"post\">\n";
  $salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  width='10%' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                        CODIGO";
  $salida .= "                       </td>\n";
  $salida .= "                        <input name='codigo' id='codigo' type=\"hidden\" value=\"\">\n";
  $salida .= "                        <input name='existo_val' id='existo_val' type=\"hidden\" value=\"\">\n";
  $salida .= "                       <td COLSPAN='1' width='13%' COLSPAN='1' id='codigo_pro' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                       ";
  $salida .= "                       </td>\n";
  $salida .= "                       <td  width='10%' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                        EXISTENCIA";
  $salida .= "                       </td>\n";
  $salida .= "                       <td ID='existo' COLSPAN='1' width='10%' COLSPAN='1' id='codigo_pro' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                       ";
  $salida .= "                       </td>\n";
  $salida .= "                       <td COLSPAN='1' width='10%' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                        COSTO";
  $salida .= "                       </td>\n";
  $salida .= "                       <td width='14%' id='costeno' align=\"center\" class=\"modulo_list_claro\">\n";
  $salida .= "                         \n";
  $salida .= "                       </td>\n"; 
  $salida .= "                       <td  width='7%' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                       ";
  $salida .= "                       </td>\n";
  $salida .= "                        <td width='15%' align=\"center\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
  $java = "javascript:MostrarCapa('ContenedorBus');Bus_Pro('".$datos['empresa_id']."','".$datos['centro_utilidad']."','".$datos['bodega']."','0','0','1');Iniciar4('BUSCAR PRODUCTO');Clear3000();\"";
  $salida .= "                         <a title='BUSCADOR PRODUCTO' class=\"label_error\" href=\"".$java."\">\n";
  $salida .= "                          BUSCAR PRODUCTO\n";
  $salida .= "                         </a>\n";
  $salida .= "                       </td>\n";
  $salida .= "                      </tr>\n";
  
  $salida .= " <tr>\n";
  $salida .= "   <td  width='10%' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "      FECHA VENCIMIENTO";
  $salida .= "   </td>\n";
  $fecha = date("Y-m-d");
  $salida .= " <td  width='10%'  align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "<div id='fecha_vencimiento'></div>";
  $salida .= "      <input type=\"hidden\" class=\"input-text\"  name=\"fecha_venc\" id=\"fecha_venc\" maxlength=\"20\" style=\"width:100%;height:100%\" value=\"\">\n";
  $salida .= "      <input type=\"hidden\"  name=\"token\" id=\"token\"  value=\"\">\n";
  $salida .= "                        ";
  $salida .= " </td>\n";
  if($devolucion['id_doc_generl']==$tmp_doc_id)
  {
    $salida .= "  <td  width='10%' align=\"left\" class=\"modulo_table_list_title\">\n";
    $salida .= "       DEVOLUCION-OBSERVACION";
    $salida .= "  </td>\n";
    $salida .= "  <td  id='devol_doc' align=\"center\" class=\"modulo_list_claro\"> \n";
    $salida .= "      ".$devolucion['observacion']."";
    $salida .= "  </td>\n";
  }
 
 
  $salida .= "           <td  width='10%' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "            LOTE";
  $salida .= "           </td>\n";
  
  $salida .= " <td  width='10%' align=\"left\" class=\"modulo_list_claro\" colspan=\"1\"> \n";
  $salida .= "<div id='lote'></div>";
  $salida .= "     <input type=\"hidden\" class=\"input-text\"  name=\"lotec\" id=\"lotec\" maxlength=\"20\" style=\"width:100%;height:100%\" value=\"0\">\n";
  $salida .= "     <input type=\"hidden\"  name=\"token\" id=\"tokenL\"  value=\"\">\n";
  $salida .= "                        ";
  $salida .= "</td>\n"; 
  $estadosEmpresa=$consulta->ConsultaEmpresa($_SESSION['EMPRESAS']['empresa_id']);
  if($estadosEmpresa['sw_estados']==1)
  {
    $salida .= " <td  width='10%' align=\"left\" class=\"modulo_table_list_title\">\n";
    $salida .= "   ESTADO";
    //$salida .= "<pre>".print_r($estadosEmpresa,true)."</pre>";
    $salida .= " </td>\n";
    $salida .= " <td COLSPAN='3' align=\"left\" class=\"modulo_list_claro\">";
    $estadostmp=$consulta->ConsultaEstadosTmp($_SESSION['SYSTEM_USUARIO_ID'],$tmp_doc_id);    
    $sw_verificono=$consulta->ConsultaSw_verificar($datos['tipo_doc_bodega_id'],$tmp_doc_id);
    //$documentos=$consulta->ConsultaEstadosPermisos($datos['tipo_doc_bodega_id']);
    $documentos2=$consulta->ConsultaEstadosPermisosp($datos['tipo_doc_bodega_id'],$tmp_doc_id);
    $si_esta=$consulta->ConsultaPardocg($tmp_doc_id);
    //$salida .= "<pre>".print_r($si_esta,true)."</pre>";    
    $tipo_documento=$datos['tipo_doc_bodega_id'];
    $salida .= "   <select width=\"50%\" class=\"select\" name=\"estados\" id=\"estados\" onchange=\"ActuEstado($bodegas_doc_id,$tmp_doc_id,document.getElementById('estados').value,'$tipo_documento')\">";
    $salida .= "   <option value=\"-1\">-- Seleccionar --</option>\n";
    $selected ="";
    $contar=count($si_esta);
    
    $k=0;
    $m=0;
    for($i=0;$i<$contar;$i++)
    {
      if($si_esta[$i]['sw_verifico']==1)
      $k++;
      else
      $m++;
    }
    
    if($k!=$contar)
    {
      if($sw_verificono)
      {
        foreach($sw_verificono as $indice=>$valor)
        {
          $salida .= "  <option value=\"".$valor['abreviatura']."\" ".$selected.">".$valor['descripcion']."</option>";
        }
      }
      else
      {
        foreach ($documentos2 as $indice=>$valor)
        { 
         
         $salida .= "  <option value=\"".$valor['abreviatura']."\" ".$selected.">".$valor['descripcion']."</option>";
        
        
        //else{    
         //$salida .= "  <option value=\"".$valor['abreviatura']."\" ".$selected.">".$valor['descripcion']."</option>";    
        }
      }
     }
   $salida .= " </td>";
   
   $ruta=$consulta->ConsultaRuta($_SESSION['EMPRESAS']['empresa_id']);
   $pedido=$consulta->FarmaciaPedidosTmp($tmp_doc_id);
   //$salida .="<pre>".print_r($pedido,true)."</pre>";
   $salida .= " <tr>";
      $salida .= "  <td  width='10%' align=\"left\" class=\"modulo_table_list_title\" colspan=\"1\">\n";
      $salida .= "   RUTA";
      $salida .= "  </td>\n";
      $ruta=$consulta->ConsultaRuta($_SESSION['EMPRESAS']['empresa_id']);
      $tm_docu = $consulta->ConsultaTmp($_SESSION['SYSTEM_USUARIO_ID'],$tmp_doc_id);
      $si_rotulocaja=$objClass->ConsulCajaRotulo($tmp_doc_id,$pedido['solicitud_prod_a_bod_ppal_id']);
      $contar1=count($tm_docu);
      
      $salida .= "  <td COLSPAN='8' align=\"left\" class=\"modulo_list_claro\">";
      $salida .= "  ".$ruta['ruta']." -- ".$ruta['descripcion']." ";
      $salida .= "  </td>\n";
      $salida .= " </tr>";
   $salida .= " <tr>";
        $salida .= "  <td  width='10%' align=\"left\" class=\"modulo_table_list_title\" colspan=\"8\">\n";
        $salida .= "    ROTULO DE CAJA";
        $salida .= "  </td>\n";
        $salida .= " </tr>";
        $salida .= " <tr>";
        $salida .= "  <td  width='20%' align=\"left\" class=\"modulo_table_list_title\" colspan=\"1\">\n";
        $salida .= "   CLIENTE";
        $salida .= "  </td>\n";
        $salida .= "  <td COLSPAN='8' align=\"left\" class=\"modulo_list_claro\">";
        $salida .= "   <input type=\"text\" class=\"input-text\" name=\"cliente\"id=\"cliente\"value=\"".$si_rotulocaja['cliente']."\">\n";
        //$salida .= "   <input type=\"hidden\" id=\"fecha_venci\" value='".$buscar['fecha_vencimiento']."'>";
        $salida .= "  </td>\n";
        $salida .= " </tr>";
        $salida .= " <tr>";
        $salida .= "  <td  width='20%' align=\"left\" class=\"modulo_table_list_title\" colspan=\"1\">\n";
        $salida .= "   DIRECCION";
        $salida .= "  </td>\n";
        $salida .= "  <td COLSPAN='8' align=\"left\" class=\"modulo_list_claro\">";
        $salida .= "   <input type=\"text\" class=\"input-text\" name=\"direccion\"id=\"direccion\"value=\"".$si_rotulocaja['direccion']."\">\n";
        $salida .= "  </td>\n";
        $salida .= " </tr>";
        $salida .= " <tr>";
        $salida .= "  <td  width='20%' align=\"left\" class=\"modulo_table_list_title\" colspan=\"1\">\n";
        $salida .= "   RUTA";
        $salida .= "  </td>\n";
        $salida .= "  <td COLSPAN='7' align=\"left\" class=\"modulo_list_claro\">";
        $salida .= "  ".$ruta['ruta']." -- ".$ruta['descripcion']." ";
        $salida .= "  </td>\n";
        $salida .= " </tr>";
        for($i=0;$i<$contar1;$i++)
       {
          $salida .= " <tr>";
          $salida .= "  <td  width='20%' align=\"left\" class=\"modulo_table_list_title\" colspan=\"1\">\n";
          $salida .= "   CANTIDAD Y CONTENIDO ";
          $salida .= "  </td>\n";
          $salida .= "  <td COLSPAN='8' align=\"left\" class=\"modulo_list_claro\">";
          $n=$i+1;
          $salida .= "  <b>".$n."</b><b> - </b>".$tm_docu[$i]['cantidad']." <b> - </b> ".$tm_docu[$i]['descripcion']." ";
          $salida .= "  </td>\n";
          $salida .= " </tr>";
          $cant .= $tm_docu[$i]['cantidad'].",";
          $conte .= $tm_docu[$i]['descripcion'].",";
          
        }
        $salida .= " <td width='20%' align=\"center\" class=\"modulo_list_claro\" align=\"center\" colspan='8'>\n";
        $salida .= "   <input type=\"button\" id='Guardar' class=\"input-submit\" value=\"Guardar\" onclick=\"xajax_GuardarRotuloCaja('".$tmp_doc_id."',document.getElementById('cliente').value,document.getElementById('direccion').value,'".$cant."','".$ruta['descripcion']."','".$conte."','0');\">\n";
        $salida .= " </td>\n"; 
   
   
  }
  else
  {
    $salida .= "           <td width='10%'  align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
    $salida .= "            </td>\n";
  }

  $salida .= "      </tr>\n";
  
  
  
  
  
  
  $salida .= "                    <tr>\n";
  $salida .= "                       <td COLSPAN='1' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                        DESCRIPCION";
  $salida .= "                       </td>\n"; 
  $salida .= "                       <td  COLSPAN='3' id='desc_pro' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                         ";
  $salida .= "                       </td>\n";
  $salida .= "                       <td align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                       UNIDAD";
  $salida .= "                       </td>\n";
  $salida .= "                       <td  id='unidad_pro' align=\"center\" class=\"modulo_list_claro\"> \n";
  $salida .= "                        ";
  $salida .= "                       </td>\n";
  $salida .= "                       <td COLSPAN='1' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                        CANTIDAD";
  $salida .= "                       </td>\n";
  $salida .= "                       <td COLSPAN='1' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                          <input type=\"text\" id=\"cantidad\" size='10' class=\"input-text\" value=\"\" onkeypress=\"return acceptNum(event);\" onclick=\"Clear3000();\">\n";
  $salida .= "                          <input type=\"hidden\" id=\"costeno_val\"  value=\"0\">\n";
  $salida .= "                       </td>\n";

  $salida .= "                    </tr>\n";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  COLSPAN='8' align=\"center\" class=\"modulo_list_claro\">\n";                          //                         $doc_tmp_id,                                            $codigo_producto,                     $cantidad,                                       $porcentaje_gravamen[document.getElementById('gravamen').value],$total_costo[document.getElementById('op22').value],                   $usuario_id=null
                                                                                                                                                                                                                       //GuardarProductoTemporal(doc_bodega_id,doc_tmp_id,codigo_producto,cantidad,porcentaje_gravamen,total_costo,usuario_id,fecha_venc,lotec)
  $salida .= "                          <input type=\"button\" id=\"nuevo\" value=\"SELECCIONAR PRODUCTO\" class=\"input-bottom\" onClick=\"javascript:GuardarProductoTemporal('".$bodegas_doc_id."','".$tmp_doc_id."',document.getElementById('codigo').value,document.getElementById('cantidad').value,'0',document.getElementById('costeno_val').value,'".UserGetUID()."',document.getElementById('fecha_venc').value,document.getElementById('lotec').value);\">";//
  $salida .= "                       </td>\n"; 
  $salida .= "                    </tr>\n";
  $salida .= "                 </table>";
  $salida .= "                </form>\n";
  $salida .= "               </div>";
  $salida .= "               <br>";
  //Convenciones
  $colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
  $colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');
  
  $salida .= "                <table class=\"modulo_table_list\" width=\"35%\" align=\"center\">";
  $salida .= "                 <td style=\"background:".$colores['PV']."\" width=\"50%\" align=\"center\">";
  $salida .= "                  PROD. PROXIMO A VENCER";
  $salida .= "                  </td>";
  $salida .= "                 <td style=\"background:".$colores['VN']."\" width=\"50%\" align=\"center\">";
  $salida .= "                  PROD. VENCIDO";
  $salida .= "                  </td>";
  $salida .= "                 </table>";
  $salida .= "               <br>";
  $salida .= "               <div id='tablaoide'>\n";
  $salida .= "               </div>";
  return $salida;
 }
    /**
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		*
		* @param array $action vector que continen los link de la aplicacion
    * @param string $mensaje Cadena con el texto del mensaje a mostrar 
    *         en pantalla
    *
		* @return string
		*/
		function FormaMensajeModulo($action,$mensaje)
		{
			$html  = ThemeAbrirTabla('MENSAJE');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$mensaje."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
  }
?>