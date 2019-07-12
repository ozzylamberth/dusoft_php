<?php
  /**
  * $Id: app_Inv_Movimientos_Admin_userclasses_HTML.php,v 1.2 2011/05/19 22:19:10 hugo Exp $
  *
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS-FI
  *
  * $Revision: 1.2 $
  *
  * @autor Jaime G�ez
  **/

  $VISTA = "HTML";
//$_ROOT = "../../../";

  IncludeClass("ClaseHTML");

  if (!IncludeClass('BodegasDocumentos'))
    die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
  
  if (!IncludeClass('BodegasProductos'))
    die(MsgOut("Error al incluir archivo","BodegasProductos"));

  class app_Inv_Movimientos_Admin_userclasses_HTML extends app_Inv_Movimientos_Admin_user
  {
    /**
    * Funcion constructora
    * @return true;
    **/
    function app_Inv_Movimientos_Admin_userclasses_HTML(){ }
    /**
    * Funcion de que da inicio al modulo
    * @return true;
    **/
    function main()
    {
      $this->SelectEmpresa();
      return true;
    }
    /**
    * Funcion que muestra el menu de los empresas y centros de utilidad
    * @return true;
    **/
    function SelectEmpresa()
    {
      $this->MostrarEmpresas();
      $titulo[0]='EMPRESA';
      $url[0]='app';//contenedor
      $url[1]='Inv_Movimientos_Admin';//m�ulo
      $url[2]='user';//clase
      $url[3]='SelectCentroCosto';//m�odo
      $url[4]='Empresas';//indice del request
      $this->salida .= gui_theme_menu_acceso('SELECCIONE EMPRESA',$titulo,$this->TodasEmpresas,$url,ModuloGetURL('system','Menu'));
      return true;
    }
    /**
    * funcion para seleccionar centros de costo
    * @return true;
    **/   
    function SelectCentroCosto()
    {
      $this->CrearElementos();
      $this->MostrarCentros();
      
      $titulo[0] = 'CENTRO DE UTILIDAD';
      $titulo[1] = 'BODEGA';
      $url[0]= 'app';
      $url[1]= 'Inv_Movimientos_Admin';
      $url[2]= 'user';
      $url[3]= 'Menu_Movimientos_Admin';
      $url[4]= 'Chentro';
      $this->salida .= gui_theme_menu_acceso('SELECCIONAR BODEGA',$titulo,$this->TodosCentros,$url,ModuloGetURL('app','Inv_Movimientos_Admin','user','SelectEmpresa'));
      return true;
    }
    /**
    * funcion para seleccionar Menu del programa
    * @return true;
    */
    function Menu_Movimientos_Admin()
    {
      $request = $_REQUEST;
      
      if(!empty($request['Chentro']))
        SessionSetVar("Chentro",$request['Chentro']);
      
      $bodegas = SessionGetVar("Chentro");
      $empresas = SessionGetVar("EMPRESAS");
      
      $this->CrearElementos();
      IncludeClass("ClaseHTML");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $path = SessionGetVar("rutaImagenes");
      $this->salida .= "<div id=\"campa_fondo_bodegas\" style=\"position:absolute; padding:0px; left:0px; top:0px; right:0px; bottom:0px; width:100%; display:none; background-color:#eeeeee;z-index:4;\"></div>\n";
      $this->salida .= ThemeAbrirTabla("CONSULTA BODEGAS");
      
      $CONSULTARPRO=ModuloGetURL('app','Inv_Movimientos_Admin','user','BuscadorProductos');
      $CONSULTARDOC=ModuloGetURL('app','Inv_Movimientos_Admin','user','MenuBodegas');
      $this->salida .= "            <form name=\"menu_docu\" action=\"javascript:LlamarDocu(1);\" method=\"post\">\n";
      $this->salida .= "                 <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "                   <tr class=\"modulo_table_list_title\">\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                         MENU DE OPCIONES";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                   </tr>\n";
      if($bodegas['sw_cierre'] == '1')
      {
        if($bodegas['lapso_cerrar'] && $bodegas['lapso_cerrar'] < date('Ym'))
        {
          $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
          $this->salida .= "                          <a class=\"label_error\" href=\"".ModuloGetURL('app','Inv_Movimientos_Admin','user','FormaCierre')."\" onclick=\"document.getElementById('campa_fondo_bodegas').style.display='block';\">HACER CIERRE DE BODEGA PERIODO ".$bodegas['lapso_cerrar']."</a>\n";
          $this->salida .= "                       </td>";
          $this->salida .= "                    </tr>";        
        }
      }
      $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a class=\"label_error\" href=\"".$CONSULTARPRO."\">CONSULTAR PRODUCTOS DE LA BODEGA ".$bodegas['bodega_descripcion']."</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a class=\"label_error\" href=\"".$CONSULTARDOC."\">CONSULTAR DOCUMENTOS DE LA BODEGA ".$bodegas['bodega_descripcion']."</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";
      $this->salida .= "                 </table>";
      $this->salida .= "             </form>";
      
      $Exit = ModuloGetURL('app','Inv_Movimientos_Admin','user','SelectCentroCosto');
      $this->salida .= " <form name=\"volver\" action=\"".$Exit."\" method=\"post\">\n";//".$this->action[0]."
      $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
      $this->salida .= "    <tr>\n";
      $this->salida .= "       <td align=\"center\" colspan='7'>\n";
      $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
      $this->salida .= "       </td>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "  </table>\n";
      $this->salida .= " </form>\n";
      $this->salida .= ThemeCerrarTabla();
      return true;
    }       
 
    // CREAR LA CAPITA
	 function CrearVentana($tmn,$Titulo)
   {
    $this->salida .= "<script>\n";
    $this->salida .= "  var contenedor = 'Contenedor';\n";
    $this->salida .= "  var titulo = 'titulo';\n";
    $this->salida .= "  var hiZ = 4;\n";
    $this->salida .= "  function OcultarSpan()\n";
    $this->salida .= "  { \n";
    $this->salida .= "    try\n";
    $this->salida .= "    {\n";
    $this->salida .= "      e = xGetElementById('Contenedor');\n";
    $this->salida .= "      e.style.display = \"none\";\n";
    $this->salida .= "    }\n";
    $this->salida .= "    catch(error){}\n";
    $this->salida .= "  }\n";
    //Mostrar Span
    $this->salida .= "  function MostrarSpan()\n";
    $this->salida .= "  { \n";
    $this->salida .= "    try\n";
    $this->salida .= "    {\n";
    $this->salida .= "      e = xGetElementById('Contenedor');\n";
    $this->salida .= "      e.style.display = \"\";\n";
    $this->salida .= "      Iniciar();\n";
    $this->salida .= "    }\n";
    $this->salida .= "    catch(error){alert(error)}\n";
    $this->salida .= "  }\n";

    $this->salida .= "  function MostrarTitle(Seccion)\n";
    $this->salida .= "  {\n";
    $this->salida .= "    xShow(Seccion);\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function OcultarTitle(Seccion)\n";
    $this->salida .= "  {\n";
    $this->salida .= "    xHide(Seccion);\n";
    $this->salida .= "  }\n";

    $this->salida .= "  function Iniciar()\n";
    $this->salida .= "  {\n";
    $this->salida .= "    contenedor = 'Contenedor';\n";
    $this->salida .= "    titulo = 'titulo';\n";
    $this->salida .= "    ele = xGetElementById('Contenido');\n";
    $this->salida .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
    $this->salida .= "    ele = xGetElementById(contenedor);\n";
    $this->salida .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
    $this->salida .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
    $this->salida .= "    ele = xGetElementById(titulo);\n";
    $this->salida .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
    $this->salida .= "    xMoveTo(ele, 0, 0);\n";
    $this->salida .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $this->salida .= "    ele = xGetElementById('cerrar');\n";
    $this->salida .= "    xResizeTo(ele,20, 20);\n";
    $this->salida .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
    $this->salida .= "  }\n";

    $this->salida .= "  function myOnDragStart(ele, mx, my)\n";
    $this->salida .= "  {\n";
    $this->salida .= "    window.status = '';\n";
    $this->salida .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
    $this->salida .= "    else xZIndex(ele, hiZ++);\n";
    $this->salida .= "    ele.myTotalMX = 0;\n";
    $this->salida .= "    ele.myTotalMY = 0;\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function myOnDrag(ele, mdx, mdy)\n";
    $this->salida .= "  {\n";
    $this->salida .= "    if (ele.id == titulo) {\n";
    $this->salida .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
    $this->salida .= "    }\n";
    $this->salida .= "    else {\n";
    $this->salida .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
    $this->salida .= "    }  \n";
    $this->salida .= "    ele.myTotalMX += mdx;\n";
    $this->salida .= "    ele.myTotalMY += mdy;\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function myOnDragEnd(ele, mx, my)\n";
    $this->salida .= "  {\n";
    $this->salida .= "  }\n";
    
    
    $this->salida .= "function Cerrar(Elemento)\n";
         $this->salida .= "{\n";
         $this->salida .= "    capita = xGetElementById(Elemento);\n";
         $this->salida .= "    capita.style.display = \"none\";\n";
         $this->salida .= "}\n";
    
    
    
    $this->salida .= "</script>\n";
    $this->salida .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
    $this->salida .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
    $this->salida .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
    $this->salida .= "  <div id='Contenido' class='d2Content'>\n";
    //En ese espacio se visualiza la informacion extraida de la base de datos.
    $this->salida .= "  </div>\n";
    $this->salida .= "</div>\n";
    return true;
   } 
 
 
    /**
    * Funcion que sirve para buscar el menu del programa
    * @return true;
    **/
    function BuscadorProductos()
    { 
        $cc=SessionGetVar("Chentro");
		$Empresas=SessionGetVar("EMPRESAS");
		
        if(empty($_REQUEST['VolverCuy']))
          $bodega = $cc['bodega'];
        else
          $bodega = $_REQUEST['bodega'];

        $centro_id = $cc['centro_utilidad'];
        $file ='app_modules/Inv_Movimientos_Admin/RemoteXajax/definirAdmin.php';
        $this->SetXajax(array("PonerGrupoVolver",
		"InfoProducto","Guardar_Edit","Editar_Precio",
		"GetLixtadox","GetSubbClasex","GetClasex",
		"GetSubbClasex1","GetClasex1",
		"BuscadorTBodega","BuscadorTBodegam",
		"ListadoDocGeneral","BodegasBusqueda"),$file,"ISO-8859-1");
        IncludeClass("ClaseHTML");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $path = SessionGetVar("rutaImagenes");
        $consulta= new MovBodegasAdminSQL();
        $this->IncludeJS('RemoteXajax/definirAdmin.js', $contenedor='app', $modulo='Inv_Movimientos_Admin');
        /*print_r($Empresas);*/
		$javaC = "<script>\n";
        $javaC .= "   var contenedor1=''\n";
        $javaC .= "   var titulo1=''\n";
        $javaC .= "   var hiZ = 2;\n";
        $javaC .= "   var DatosFactor = new Array();\n";
        $javaC .= "   var EnvioFactor = new Array();\n";
        $javaC .= "   function Iniciar2(tit)\n";
        $javaC .= "   {\n";
        $javaC .= "       contenedor1 = 'ContenedorVer';\n";
        $javaC .= "       titulo1 = 'tituloVer';\n";
        $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $javaC.= "        Capa = xGetElementById(contenedor1);\n";
        $javaC .= "       xResizeTo(Capa, 950, 'auto');\n";
        $javaC.= "        Capx = xGetElementById('ContenidoVer');\n";
        $javaC .= "       xResizeTo(Capx, 950, 400);\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/40, xScrollTop()+30);\n";
        $javaC .= "       ele = xGetElementById(titulo1);\n";
        $javaC .= "       xResizeTo(ele, 930, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";
        $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $javaC .= "       ele = xGetElementById('cerrarVer');\n";
        $javaC .= "       xResizeTo(ele, 20, 20);\n";
        $javaC .= "       xMoveTo(ele, 930, 0);\n";
        $javaC .= "   }\n";
        $javaC .= "   function IniciarCent(tit)\n";
        $javaC .= "   {\n";
        $javaC .= "       contenedor1 = 'ContenedorCent';\n";
        $javaC .= "       titulo1 = 'tituloCent';\n";
        $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $javaC.= "        Capa = xGetElementById(contenedor1);\n";
        $javaC .= "       xResizeTo(Capa, 900, 'auto');\n";
        $javaC.= "        Capx = xGetElementById('ContenidoCent');\n";
        $javaC .= "       xResizeTo(Capx, 900, 400);\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
        $javaC .= "       ele = xGetElementById(titulo1);\n";
        $javaC .= "       xResizeTo(ele, 880, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";
        $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $javaC .= "       ele = xGetElementById('cerrarCent');\n";
        $javaC .= "       xResizeTo(ele, 20, 20);\n";
        $javaC .= "       xMoveTo(ele, 880, 0);\n";
        $javaC .= "   }\n";
        $javaC.= "</script>\n";
        $this->salida.= $javaC;
        $javaC1.= "<script>\n";
        $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "     window.status = '';\n";
        $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
        $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
        $javaC1 .= "     ele.myTotalMX = 0;\n";
        $javaC1 .= "     ele.myTotalMY = 0;\n";
        $javaC1 .= "   }\n";
        $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";//
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
        
        $javaC1.= "function Traer(Elemento)\n";
        $javaC1.= "{\n";
        $javaC1.= "    document.getElementById('hcuenta').innerHTML=Elemento;\n";
        $javaC1.= "    document.hijo_cuenta.padre.value=Elemento;\n";
        $javaC1.= "}\n";
        $javaC1.= "</script>\n";
        $this->salida.= $javaC1;


        /**
        * Ventana emergente
        **/

        $this->salida .="<div id='ContenedorCent' class='d2Container' style=\"display:none;\">";
        $this->salida .= "    <div id='tituloCent' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
        $this->salida .= "    <div id='cerrarCent' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCent');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $this->salida .= "    <div id='errorCent' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "    <div id='ContenidoCent' class='d2Content'>\n";
        $this->salida .= "    </div>\n";
        $this->salida.="</div>";
        $this->salida .= ThemeAbrirTabla("BUSCADOR DE PRODUCTOS");
        $consulta= new BodegasProductos();
        $consulta1= new MovBodegasAdminSQL();
        
		$CentrosUtilidad=$consulta1->CentrosUtilidad_Empresa($Empresas['empresa_id']);
		$Moleculas=$consulta1->Listado_Moleculas();
        $ctl = AutoCarga::factory("ClaseUtil");
      
      $this->salida .=  $ctl->LimpiarCampos();
      $this->salida .=  $ctl->RollOverFilas();
      $this->salida .=  $ctl->AcceptDate('/');
      $this->salida .=  $ctl->AcceptNum();
      
		$selectM = "	<option value=\"\">-- NINGUNO --</option>	";
		foreach($Moleculas as $l=>$val)
		{
		$selectM .= "	<option value=\"".$val['molecula_id']."\">".$val['molecula_id']." -- ".$val['descripcion']."</option>";
		}
      
      $this->salida .= "                 <center>";
      $this->salida .=  "                <fieldset class=\"fieldset\" style=\"width:60%\">\n";
      $this->salida .=  "                  <legend class=\"normal_10AN\">\n";
      $this->salida .=  "                    <img src=\"".GetThemePath()."/images/informacion.png\">NOTA\n";
      $this->salida .=  "                  </legend>\n";
      $this->salida .=  "                  <center>\n";
      $this->salida .=  "                    <label class=\"normal_10AN\">[[::PARAMETROS DE BUSQUEDA::]]</label>\n";
      $this->salida .=  "                  </center>\n";
         
		$select = "<option value=\"\">-- CENTRO POR DEFECTO --</option>";
		foreach($CentrosUtilidad as $llave=>$v)
		{
		$select .= "<option value=\"".$v['centro_utilidad']."\">".$v['descripcion']."-".$v['centro_utilidad']."</option>";
		}
		 
		 
        $this->salida .= "    <div id='error_con_busqueda' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "    	<form name=\"buscador\" action=\"javascript:GetLixtado('".SessionGetVar("EMPRESA")."','".$centro_id."','".$bodega."',document.getElementById('codigo_pro').value,document.getElementById('nom_pro').value,document.getElementById('grupos_pro').value,document.getElementById('clasexx').value,document.getElementById('subclasexy').value,document.getElementById('fecha_inicio').value,document.getElementById('fecha_final').value,document.getElementById('tipo_movimiento').value,document.getElementById('tipo_doc_general_id').value,document.getElementById('centro_utilidad_bus').value,document.getElementById('bodega_bus').value,document.getElementById('molecula_bus').value,'1');\" method=\"post\"> \n";
        //$this->salida .= "    	<form name=\"buscador\" id=\"buscador\" action=\"javascript:GetLixtado(xajax.getFormValues('buscador'));\" method=\"post\"> \n";
        $this->salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                      <td colspan='2'>\n";
        $this->salida .= "                        BUSCADOR DE PRODUCTOS";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                    <tr>\n";
		$this->salida .= "							<td class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "								CENTROS UTILIDAD";
		$this->salida .= "							</td>";
		$this->salida .= "							<td class=\"modulo_list_claro\">";
		$this->salida .= "								<select name=\"centro_utilidad_bus\" id=\"centro_utilidad_bus\" class=\"select\" style=\"width:60%\" onchange=\"xajax_BodegasBusqueda('".trim($Empresas['empresa_id'])."',this.value);\">";
		$this->salida .= 								$select;
		$this->saldia .= "								</select>";
		$this->salida .= "							</td>";
		$this->salida .= "                    </tr>\n";
		$this->salida .= "                    <tr>\n";
		$this->salida .= "							<td class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "								BODEGA";
		$this->salida .= "							</td>";
		$this->salida .= "							<td class=\"modulo_list_claro\">";
		$this->salida .= "								<select name=\"bodega_bus\" id=\"bodega_bus\" class=\"select\" style=\"width:60%\">";
		$this->salida .= "									<option value=\"\">-- BODEGA POR DEFECTO --</option>";
		$this->saldia .= "								</select>";
		$this->salida .= "							</td>";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                    <tr>\n";
        $this->salida .= "                       <td class=\"modulo_table_list_title\" width=\"30%\" align=\"center\">\n";
        $this->salida .= "                          GRUPO";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td id='supergrupos' class=\"modulo_list_claro\" width=\"70%\" align=\"left\">\n";
        $GruposProductos=$consulta1->GetGrupos();
        $this->salida .= "                         <select name=\"grupos_pro\" id=\"grupos_pro\" class=\"select\" onchange=\"GetClasex(this.value);\" style=\"width:60%\">";
        $this->salida .="                           <option value=\"0\" selected>SELECCIONAR</option> \n";
        for($i=0;$i<count($GruposProductos);$i++)
        {
            $this->salida .="                           <option value=\"".$GruposProductos[$i]['grupo_id']."\">".strtoupper($GruposProductos[$i]['descripcion'])."</option> \n";
        }
        $this->salida .="                         </select>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                    <tr>\n";
        $this->salida .= "                       <td  class=\"modulo_table_list_title\" width=\"30%\" align=\"center\">\n";
        $this->salida .= "                          CLASE";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td id='clasexy' class=\"modulo_list_claro\" width=\"70%\" align=\"left\">\n";
        $this->salida .= "                         <select name=\"clasexx\" id=\"clasexx\" class=\"select\" onchange=\"GetSubClasex(,this.value);\">";
        $this->salida .= "                           <option value=\"0\" selected>SELECCIONAR</option> \n";
        $this->salida .= "                         </select>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                    <tr>\n";
        $this->salida .= "                       <td class=\"modulo_table_list_title\" width=\"30%\" align=\"center\">\n";
        $this->salida .= "                          SUBCLASE";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td id='subclase' class=\"modulo_list_claro\" width=\"70%\" align=\"left\">\n";
        $this->salida .= "                         <select name=\"subclasexy\" id=\"subclasexy\" class=\"select\" onchange=\"\">";
        $this->salida .= "                           <option value=\"0\" selected>SELECCIONAR</option> \n";
        $this->salida .= "                         </select>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
		$this->salida .= "                    <tr>\n";
		$this->salida .= "							<td class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "								MOLECULAS";
		$this->salida .= "							</td>";
		$this->salida .= "							<td class=\"modulo_list_claro\">";
		$this->salida .= "								<select name=\"molecula_bus\" id=\"molecula_bus\" class=\"select\" style=\"width:84%\" >";
		$this->salida .= 								$selectM;
		$this->saldia .= "								</select>";
		$this->salida .= "							</td>";
		$this->salida .= "                    </tr>\n";
        $this->salida .= "                    <tr>\n";
        $this->salida .= "                       <td class=\"modulo_table_list_title\" width=\"30%\" align=\"center\">\n";
        $this->salida .= "                          CODIGO";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td class=\"modulo_list_claro\" width=\"70%\" align=\"left\">\n";
        $this->salida .= "                         <input type=\"text\" class=\"input-text\" name=\"codigo_pro\" id=\"codigo_pro\" size=\"50\" value=\"\">\n"; /*onkeypress=\"return acceptNum(event)\"*/
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                    <tr>\n";
        $this->salida .= "                       <td class=\"modulo_table_list_title\" width=\"30%\" align=\"center\">\n";
        $this->salida .= "                          NOMBRE";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td class=\"modulo_list_claro\" width=\"70%\" align=\"left\">\n";
        $this->salida .= "                         <input type=\"text\" class=\"input-text\" name=\"nom_pro\" id=\"nom_pro\" size=\"60\" value=\"\">\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        
        if(empty($request['fecha_inicio']))
            $request['fecha_inicio'] = "01/04/2015";
        
        
        $this->salida .=  "            <tr>\n";
        $this->salida .=  "              <td class=\"modulo_table_list_title\">FECHA INICIAL</td>\n";
        $this->salida .=  "              <td class=\"modulo_list_claro\">\n";
        $this->salida .=  "                <input readonly=\"true\" type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicio']."\">\n";
        $this->salida .=  "		          ".ReturnOpenCalendario('buscador','fecha_inicio','/',1)."\n";
        $this->salida .=  "              </td>\n";
        $this->salida .=  "            </tr>\n";
        $this->salida .=  "            <tr>\n";
        $this->salida .=  "              <td class=\"modulo_table_list_title\">FECHA FINAL</td>\n";
        $this->salida .=  "              <td class=\"modulo_list_claro\">\n";
        $this->salida .=  "                <input readonly=\"true\" type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_final']."\">\n";
        $this->salida .=  "		          ".ReturnOpenCalendario('buscador','fecha_final','/',1)."\n";
        $this->salida .=  "              </td>\n";
        $this->salida .=  "            </tr>\n";
        
        $select  = "<select name=\"tipo_movimiento\" id=\"tipo_movimiento\" class=\"select\" onchange=\"xajax_ListadoDocGeneral(this.value);\">";
        $select .= "<option value=\"\">TODOS</option>";
        $select .= "<option value=\"I\">INGRESO</option>";
        $select .= "<option value=\"E\">EGRESO</option>";
        $select .= "<option value=\"T\">TRASLADO</option>";
        $select .= "</select>";
        
        $this->salida .=  "            <tr>\n";
        $this->salida .=  "              <td class=\"modulo_table_list_title\">TIPO-MOVIMIENTO ".$select."</td>\n";
        $this->salida .=  "              <td class=\"modulo_list_claro\">\n";
        $this->salida .=  "               <div id=\"doc_general\">";
        $this->salida .=  "               <input type=\"hidden\" name=\"tipo_doc_general_id\" id=\"tipo_doc_general_id\" value=\"\">";
        $this->salida .=  "               </div>";
        $this->salida .=  "              </td>\n";
        $this->salida .=  "            </tr>\n";

        $this->salida .=  "			      <tr>\n";
        /*$this->salida .= "                    <tr>\n";
        $this->salida .= "                     <td align=\"center\" class=\"modulo_list_claro\" colspan='2'>\n";
        //$this->salida .= "                     <input type=\"button\" class=\"input-submit\" value=\"BUSCAR PRODUCTO-BODEGA\" onclick=\"javascript:BuscadorTBodega('".SessionGetVar("EMPRESA")."','".$centro_id."','".$bodega."',document.getElementById('codigo_pro').value,document.getElementById('nom_pro').value,'1');\" >\n";
        $this->salida .= "                     <input type=\"button\" class=\"input-submit\" value=\"BUSCAR PRODUCTO-BODEGA\" onclick=\"javascript:BuscadorTBodega('".SessionGetVar("EMPRESA")."','".$centro_id."','".$bodega."',document.getElementById('codigo_pro').value,document.getElementById('nom_pro').value);\" >\n";
        $this->salida .= "                    </td>\n";
        $this->salida .= "                   </tr>\n";*/
        $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                      <td align=\"center\" width=\"50%\" >\n";//                                                                     empresa_id,             centro_id,bodega,codigo_pro,nom_pro,grupos_pro,clasexx,subclasexy,pagina
        //$this->salida .= "                         <input type=\"button\" class=\"input-submit\" value=\"BUSCAR PRODUCTOS\" onclick=\"GetLixtado('".SessionGetVar("EMPRESA")."','".$centro_id."','".$bodega."',document.getElementById('codigo_pro').value,document.getElementById('nom_pro').value,document.getElementById('grupos_pro').value,document.getElementById('clasexx').value,document.getElementById('subclasexy').value,'1');\">\n";
        $this->salida .= "                         <input type=\"submit\" class=\"input-submit\" value=\"BUSCAR PRODUCTOS(Para Kardex)\" >\n";
        $this->salida .= "                       </td>\n";
        
        $this->salida .= "                      <td align=\"center\" width=\"50%\">\n";//                                                                     empresa_id,             centro_id,bodega,codigo_pro,nom_pro,grupos_pro,clasexx,subclasexy,pagina
        //$this->salida .= "                         <input type=\"button\" class=\"input-submit\" value=\"BUSCAR PRODUCTOS\" onclick=\"GetLixtado('".SessionGetVar("EMPRESA")."','".$centro_id."','".$bodega."',document.getElementById('codigo_pro').value,document.getElementById('nom_pro').value,document.getElementById('grupos_pro').value,document.getElementById('clasexx').value,document.getElementById('subclasexy').value,'1');\">\n";
        $this->salida .= "                         <input type=\"reset\" class=\"input-submit\" value=\"LIMPIAR FORMULARIO\" >\n";
        $this->salida .= "                       </td>\n";
        
        $this->salida .= "                     </tr>\n";
        $this->salida .= "                  </table>";
        $this->salida .= "           </form>";
        $this->salida .=  "                </fieldset><br>\n";
        $this->salida .= "                 </center>";
        
        $this->salida .= "   <br>\n";
        $this->salida .= "   <div id='ListadoGeneral_error' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "   <div id='ListadoGeneral'></div>\n";
        $this->salida.="<script language=\"javaScript\">
                    var ban=0;
                    function mOvr(src,clrOver)
                    {
                        src.style.background = clrOver;
                    }

                    function mOut(src,clrIn)
                    {
                        src.style.background = clrIn;
                    }";

        if(!empty($_REQUEST['VolverCuy']))
        {

            if(!empty($_REQUEST['grupos_pro']))
            {
                $javaCX= " PonerLosGrupos('".$_REQUEST['grupos_pro']."');";
                $javaCX.= " GetClasex1('".$_REQUEST['grupos_pro']."','".$_REQUEST['clasexx']."');";
                $javaCX.= " GetSubClasex1('".$_REQUEST['grupos_pro']."','".$_REQUEST['clasexx']."','".$_REQUEST['subclasexy']."');";
            
            }
            //ECHO "AQUI".$_REQUEST['nom_bus']."LOLO";
            if(!empty($_REQUEST['codigo_pro_bus']))
            {
                $javaCX.= "    document.getElementById('codigo_pro').value='".$_REQUEST['codigo_pro_bus']."';";
            }
            if(!empty($_REQUEST['nom_bus']))
            {
                $javaCX.= "    document.getElementById('nom_pro').value='".$_REQUEST['nom_bus']."';";
            }
            $bodega=$_REQUEST['bodega'];
            $javaCX.= " GetLixtado('".SessionGetVar("EMPRESA")."','".$centro_id."','".$bodega."','".$_REQUEST['codigo_pro_bus']."','".$_REQUEST['nom_bus']."','".$_REQUEST['grupos_pro']."','".$_REQUEST['clasexx']."','".$_REQUEST['subclasexy']."','1');";


        }

        $this->salida .=$javaCX;
        $this->salida .= "</script>";


        if(empty($_REQUEST['VolverCuy']))
        {
            $Volver=ModuloGetURL('app','Inv_Movimientos_Admin','user','Menu_Movimientos_Admin');
        }
        else
        {
            $nombre=$consulta1->bodegasname($bodega);
            $Volver=ModuloGetURL('app','Inv_Movimientos_Admin','user','Menu_Movimientos_Admin',array('Bodeguix'=>array('bodega'=>$bodega,'descripcion'=>$nombre[0]['descripcion'])));

        }

        $this->salida .= "    <div id=\"volvercen_cos\">";
        $this->salida .= "     <form name=\"volver\" action=\"".$Volver."\" method=\"post\">\n";
        $this->salida .= "      <table align=\"center\" width=\"50%\">\n";
        $this->salida .= "       <tr>\n";
        $this->salida .= "        <td align=\"center\" colspan='7'>\n";
        $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "        </td>\n";
        $this->salida .= "       </tr>\n";
        $this->salida .= "      </table>\n";
        $this->salida .= "     </form>";
        $this->salida .= "    </div>";
        
        
        //$this->salida .= "<div id=\"prueba\"></div>";
        $this->salida .= ThemeCerrarTabla();
        $this->CrearVentana(900,"MENSAJE");
        return true;
    }
 
    /**
    * Funcion que especifica los datos del producto
    * @return true;
    */
    function DatosProducto()
    { 
      
        $file ='app_modules/Inv_Movimientos_Admin/RemoteXajax/definirAdmin.php';
        $this->SetXajax(array("PonerNuevosDias","InfoProducto","Guardar_Edit","Editar_Precio","GetLixtadox","GetSubbClasex","GetClasex","GetSubbClasex"),$file);
        IncludeClass("ClaseHTML");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $path = SessionGetVar("rutaImagenes");
        $consulta= new MovBodegasAdminSQL();
        $this->IncludeJS('RemoteXajax/definirAdmin.js', $contenedor='app', $modulo='Inv_Movimientos_Admin');
        $javaC = "<script>\n";
        $javaC .= "   var contenedor1=''\n";
        $javaC .= "   var titulo1=''\n";
        $javaC .= "   var hiZ = 2;\n";
        $javaC .= "   var DatosFactor = new Array();\n";
        $javaC .= "   var EnvioFactor = new Array();\n";
        $javaC .= "   function IniciarVer(tit)\n";
        $javaC .= "   {\n";
        $javaC .= "       contenedor1 = 'ContenedorVer';\n";
        $javaC .= "       titulo1 = 'tituloVer';\n";
        $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $javaC.= "        Capa = xGetElementById(contenedor1);\n";
        $javaC .= "       xResizeTo(Capa, 650, 'auto');\n";
        $javaC.= "        Capx = xGetElementById('ContenidoVer');\n";
        $javaC .= "       xResizeTo(Capx, 650, 300);\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/40, xScrollTop()+30);\n";
        $javaC .= "       ele = xGetElementById(titulo1);\n";
        $javaC .= "       xResizeTo(ele, 630, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";
        $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $javaC .= "       ele = xGetElementById('cerrarVer');\n";
        $javaC .= "       xResizeTo(ele, 20, 20);\n";
        $javaC .= "       xMoveTo(ele, 630, 0);\n";
        $javaC .= "   }\n";
        $javaC .= "   function IniciarCent(tit)\n";
        $javaC .= "   {\n";
        $javaC .= "       contenedor1 = 'ContenedorCent';\n";
        $javaC .= "       titulo1 = 'tituloCent';\n";
        $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $javaC.= "        Capa = xGetElementById(contenedor1);\n";
        $javaC .= "       xResizeTo(Capa, 280, 'auto');\n";
        $javaC.= "        Capx = xGetElementById('ContenidoCent');\n";
        $javaC .= "       xResizeTo(Capx, 280, 300);\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
        $javaC .= "       ele = xGetElementById(titulo1);\n";
        $javaC .= "       xResizeTo(ele, 260, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";
        $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $javaC .= "       ele = xGetElementById('cerrarCent');\n";
        $javaC .= "       xResizeTo(ele, 20, 20);\n";
        $javaC .= "       xMoveTo(ele, 260, 0);\n";
        $javaC .= "   }\n";
        $javaC.= "</script>\n";
        $this->salida.= $javaC;
        $javaC1.= "<script>\n";
        $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "     window.status = '';\n";
        $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
        $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
        $javaC1 .= "     ele.myTotalMX = 0;\n";
        $javaC1 .= "     ele.myTotalMY = 0;\n";
        $javaC1 .= "   }\n";
        $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";//
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
        
        $javaC1.= "function Traer(Elemento)\n";
        $javaC1.= "{\n";
        $javaC1.= "    document.getElementById('hcuenta').innerHTML=Elemento;\n";
        $javaC1.= "    document.hijo_cuenta.padre.value=Elemento;\n";
        $javaC1.= "}\n";
        $javaC1.= "</script>\n";
        $this->salida.= $javaC1;
        
        $empresa_id=$_REQUEST['empresa_id'];
        $centro_id=$_REQUEST['centro_id'];
        $bodega=$_REQUEST['bodega'];
        $codigo_producto=$_REQUEST['codigo_producto'];
        $limite=$_REQUEST['limite'];
        $pagina=$_REQUEST['pagina'];
        $nom_bus=$_REQUEST['nombre'];
        $grupo=$_REQUEST['grupo'];
        $clasex=$_REQUEST['clasex'];
        $subclase=$_REQUEST['subclasex'];
        $lapso=$_REQUEST['periodo'];
        $dia_inicial=$_REQUEST['dia1'];
        $dia_final=$_REQUEST['dia2'];
        $fecha_inicio_lapso=$_REQUEST['fecha_inicio_lapso'];
        $fecha_final_lapso=$_REQUEST['fecha_final_lapso'];
        
        
        if(!empty($_REQUEST['tipo_cri']))
        {
            $tipo_movimiento=$_REQUEST['tipo_cri'];
        }
        else
        {
            $tipo_movimiento=null;
        }
    
         /*if(empty($_REQUEST['dia1']))
         {
             $dia_inicial="-";
         }

         if(empty($_REQUEST['dia1']))
         {
             $dia_inicial="-";
         }*/        
//         if(!empty($_REQUEST['fecha1']))
//         {
//             $fecha_inicial=$_REQUEST['fecha1'];
//             if($_REQUEST['f1_mod']!=$fecha_inicial)
//             {
//                 $partes=explode("-", $fecha_inicial);
//                 $fecha_inicial=$partes[2]."-".$partes[1]."-".$partes[0];
//             }
//         }
//         else
//         {
//             $fecha_inicial=date("Y-m-01");
//         }
// 
//         if(!empty($_REQUEST['fecha2']))
//         {
//             $fecha_final=$_REQUEST['fecha2'];
//             if($_REQUEST['f2_mod']!=$fecha_final)
//             {
//                 $partes=explode("-", $fecha_final);
//                 $fecha_final=$partes[2]."-".$partes[1]."-".$partes[0];
//             }
//         }
//         else
//         {
//             $fecha_final=date("Y-m-d");
//         }
      
        $offset=$limite*($pagina-1);
        $consulta1=new BodegasProductos();
        
                               //GetInfoProductoPorLapso($empresa_id, $centro_utilidad, $bodega, $codigo_producto, $limit=null, $offset=null, $count=null, $lapso=null, $dia_inicial=null, $dia_final=null, $tipo=null, $tipo_movimiento=null, $tipo_documento=null)
        $resultado=$consulta1->GetInfoProductoPorLapso($empresa_id, $centro_id,$bodega, $codigo_producto, $limite,     $offset,      $count=null, $lapso,      $dia_inicial,      $dia_final,      $tipo=null,$tipo_movimiento,$fecha_inicio_lapso,$fecha_final_lapso);
        $slc=$consulta1->GetInfoProductoPorLapso($empresa_id, $centro_id,$bodega, $codigo_producto, $limite1=null,$offset1=0, $count=true,  $lapso,      $dia_inicial,      $dia_final,      $tipo=null,$tipo_movimiento,$fecha_inicio_lapso,$fecha_final_lapso);
     
        echo $consulta1->error;
        echo $consulta1->mensajeDeError;
        $nombre_empresa=$consulta->ColocarEmpresa($empresa_id);
        $this->salida .= ThemeAbrirTabla("KARDEX - ".$empresa_id."-".$nombre_empresa[0]['razon_social'].""); 
     
      /**
      *Ventana emergente 3 aqui es cuando se modifica una cuenta.   
      **/
        $this->salida.="<div id='ContenedorCent' class='d2Container' style=\"display:none;\">";
        $this->salida .= "    <div id='tituloCent' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
        $this->salida .= "    <div id='cerrarCent' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCent');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $this->salida .= "    <div id='errorCent' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "    <div id='ContenidoCent' class='d2Content'>\n";
        if(!EMPTY($resultado['HISTORICO']))
        {
            $this->salida .= "       <table width=\"90%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";   
            $this->salida .= "         <tr>\n";
            $this->salida .= "           <td  class=\"modulo_table_list_title\"  align=\"center\" width='20%'>\n";
            $this->salida .= "             LAPSO";
            $this->salida .= "           </td>\n"; 
            $this->salida .= "           <td  class=\"modulo_table_list_title\"  align=\"center\" width='20%'>\n";
            $this->salida .= "             UNIDADES";
            $this->salida .= "           </td>\n"; 
            $this->salida .= "         </tr>\n";
            
            for($i=0;$i<count($resultado['HISTORICO']);$i++)
            {
                $this->salida .= "         <tr>\n";
                $this->salida .= "           <td align=\"center\" class=\"modulo_list_claro\">\n";
                $this->salida .= "             ".$resultado['HISTORICO'][$i]['lapso'];
                $this->salida .= "           </td>\n";
                $this->salida .= "           <td align=\"center\" class=\"modulo_list_claro\">\n";
                $this->salida .= "            ".$resultado['HISTORICO'][$i]['unidades'];
                $this->salida .= "           </td>\n";
                $this->salida .= "         </tr>\n";
            }
            $this->salida .= "         </table>\n";
        }
        else
        {
            $this->salida .= "       <table width=\"90%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "         <tr>\n";
            $this->salida .= "          <td>\n";
            $this->salida .= "          <label class='label_error'>NO SE ENCONTRO HISTORICO</label>\n";
            $this->salida .= "          </td>\n";
            $this->salida .= "         </tr>\n";
            $this->salida .= "       </table>\n";   
        } 
        $this->salida .= "    </div>\n";
        $this->salida.="</div>";
        $this->salida.="<div id='ContenedorVer' class='d2Container' style=\"display:none;\">";
        $this->salida .= "    <div id='tituloVer' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
        $this->salida .= "    <div id='cerrarVer' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorVer');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $this->salida .= "    <div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "    <div id='ContenidoVer' class='d2Content'>\n";
        if(!empty($resultado['EXISTENCIAS']))
        {
            $this->salida .= "                   <table width=\"90%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       <td colspan='6' align=\"center\">\n";
            $this->salida .= "                        EXISTENCIAS POR BODEGAS";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
            $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       <td align=\"center\" width='10%'>\n";
            $this->salida .= "                          CENTRO DE UTILIDAD";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align=\"center\" width='10%'>\n";
            $this->salida .= "                          BODEGA";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align=\"center\" width='41%'>\n";
            $this->salida .= "                          DESCRIPCION";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align=\"center\" width='13%'>\n";
            $this->salida .= "                          EXISTENCIA";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align=\"center\" width='13%'>\n";
            $this->salida .= "                          EXISTENCIA MINIMA";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align=\"center\" width='13%'>\n";
            $this->salida .= "                          EXISTENCIA MAXIMA";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
            for($i=0;$i<count($resultado['EXISTENCIAS']);$i++)
            {
                $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
                $this->salida .= "                       <td align=\"center\">\n";
                $this->salida .= "                        ".$resultado['EXISTENCIAS'][$i]['centro_utilidad'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td align=\"center\">\n";
                $this->salida .= "                        ".$resultado['EXISTENCIAS'][$i]['bodega'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td align=\"left\">\n";
                $this->salida .= "                        ".$resultado['EXISTENCIAS'][$i]['descripcion'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td align=\"center\">\n";
                $this->salida .= "                        ".$resultado['EXISTENCIAS'][$i]['existencia'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td align=\"center\">\n";
                $this->salida .= "                        ".$resultado['EXISTENCIAS'][$i]['existencia_minima'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td align=\"center\">\n";
                $this->salida .= "                        ".$resultado['EXISTENCIAS'][$i]['existencia_maxima'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                    </tr>\n";
            }
            $this->salida .= "                   </table>\n";
        }
        else
        {
            $this->salida .= "       <table width=\"90%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "         <tr>\n";
            $this->salida .= "          <td>\n";
            $this->salida .= "          <label class='label_error'>NO SE ENCONTRO HISTORICO</label>\n";
            $this->salida .= "          </td>\n";
            $this->salida .= "         </tr>\n";
            $this->salida .= "       </table>\n";
        }
        $this->salida .= "    </div>\n";
        $this->salida.=" </div>";
        $this->salida .= "    <div id='error_con_busqueda' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $CONSULTARPRO=ModuloGetURL('app','Inv_Movimientos_Admin','user','DatosProducto',array('empresa_id'=>$empresa_id,'centro_id'=>$centro_id,'bodega'=>$bodega,'codigo_producto'=>$codigo_producto,'fecha_inicio_lapso'=>$fecha_inicio_lapso,'fecha_final_lapso'=>$fecha_final_lapso,'limite'=>10,'pagina'=>'1'));
        $this->salida .= "                 <form name=\"fechas\" action=\"".$CONSULTARPRO."\" method=\"post\">\n";
        $this->salida .= "                 <table width=\"90%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                      <td COLSPAN='6' class=\"modulo_table_list_title\"  align=\"center\" >\n";
        $this->salida .= "                         BUSCADOR DE REGISTROS";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                   <td>\n";
        $this->salida .= "                 <table width=\"100%\" border='0' align=\"center\" cellspacing='0' class=\"modulo_table_list\">\n";
        $this->salida .= "                    <tr>\n";
        $this->salida .= "                       <td class=\"modulo_table_list_title\" width=\"10%\" align=\"center\">\n";
        $this->salida .= "                        CODIGO";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td class=\"modulo_table_list_title\" width=\"40%\" align=\"center\">\n";
        $this->salida .= "                        NOMBRE";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td class=\"modulo_table_list_title\" width=\"15%\" align=\"center\">\n";
        $this->salida .= "                        UNIDAD";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td class=\"modulo_table_list_title\" width=\"25%\" align=\"center\">\n";
        $this->salida .= "                       <a title='CONTENIDO UNIDAD VENTA'>";
        $this->salida .= "                        CONTENIDO UNIDAD VENTA";
        $this->salida .= "                       </a>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td class=\"modulo_table_list_title\" width=\"10%\" align=\"center\">\n";
        $this->salida .= "                        ESTADO";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                     </tr>\n";
        $this->salida .= "                    <tr>\n";
        $this->salida .= "                       <td align=\"center\" class=\"modulo_list_claro\">\n";
        $this->salida .= "                        ".$resultado['codigo_producto'];
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"center\" class=\"modulo_list_claro\">\n";
        $this->salida .= "                        ".$resultado['descripcion'];
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"center\" class=\"modulo_list_claro\">\n";
        $this->salida .= "                        ".$resultado['descripcion_unidad'];
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"center\" class=\"modulo_list_claro\">\n";
        $this->salida .= "                        ".$resultado['contenido_unidad_venta'];
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"center\" class=\"modulo_list_claro\">\n";
        if($resultado['estado']=='1')
        {
            $this->salida .= "                        ACTIVO";
        }
        elseif($resultado['estado']=='0')
        {
            $this->salida .= "                        DESACTIVO";
        }
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                   </table>\n";
        $this->salida .= "                   </td>\n";
        $this->salida .= "                   </tr>\n"; 
        $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                   <td>\n";
        $this->salida .= "                    <table width=\"100%\" border='0' align=\"center\" cellspacing='0' class=\"modulo_table_list\">\n";
        $this->salida .= "                      <tr>\n";
        $this->salida .= "                        <td  class=\"modulo_table_list_title\"  align=\"center\" width='15%'>\n";
        $this->salida .= "                         PERIODO";
        $this->salida .= "                        </td>\n";
        $this->salida .= "                        <td class=\"modulo_list_claro\" width='15%' align=\"left\">\n";
        $lapsos=$consulta->BuscarLapsos();
        
        if(!empty($lapsos))
        {
            $this->salida .="                         <select name=\"periodo\" id=\"periodo\" class=\"select\" onchange=\"PonerNuevosDias(this.value);\">";
            $this->salida .="                           <option value=\"\" SELECTED>LAPSOS</option> \n";
            for($i=0;$i<count($lapsos);$i++)
            {
                if($_REQUEST['periodo']==$lapsos[$i]['lapso'])
            {
                $this->salida .="                           <option value=\"".$lapsos[$i]['lapso']."\" selected>".$lapsos[$i]['lapso']."</option> \n";
            }
            else
            {
                $this->salida .="                           <option value=\"".$lapsos[$i]['lapso']."\">".$lapsos[$i]['lapso']."</option> \n";
            }    
          
            }
            $this->salida .="                         </select>\n";
        }
        

        $this->salida .= "                   </td>\n";
        $this->salida .= "                      <td  class=\"modulo_table_list_title\"  align=\"center\" width='15%'>\n";
        $this->salida .= "                         DIA INICIAL";
        $this->salida .= "                      </td>\n";
        $ano500 = substr($_REQUEST['periodo'], 0, 4);
        $mes500 = substr($_REQUEST['periodo'], 4, 2);
        //$fecha_inicial = date('d',mktime(0, 0, 0, $mes, 1, $ano));
        $fecha_final = date('d',mktime(0, 0, 0, $mes500+1, 0, $ano500));

        $this->salida .= "                   <td class=\"modulo_list_claro\" width='15%' align=\"left\">\n";
      
        $this->salida .= "                         <select name=\"dia1\" id=\"dia1\" class=\"select\">";
        $this->salida .="                           <option value=\"-\" SELECTED>----</option> \n";
        if(!empty($_REQUEST['dia1']))
        {
            for($i=1;$i<$fecha_final;$i++)
            {
                if($_REQUEST['dia1']==$i)
                {
                    $this->salida .="                           <option value=\"".$i."\" selected>".$i."</option> \n";
                }
                else
                {
                    $this->salida .="                           <option value=\"".$i."\">".$i."</option> \n";
                }
            }
        }    
        $this->salida .= "                         </select>";
        $this->salida .= "                   </td>\n";
        $this->salida .= "                      <td class=\"modulo_table_list_title\" align=\"center\" width='15%'>\n";
        $this->salida .= "                        DIA FINAL";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      <td width='25%' class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "                         <select name=\"dia2\" id=\"dia2\" class=\"select\" >";
        $this->salida .="                           <option value=\"-\" SELECTED>----</option> \n";
        if(!empty($_REQUEST['dia1']))
        {
            for($i=1;$i<=$fecha_final;$i++)
            {
                if($_REQUEST['dia2']==$i)
                {
                    $this->salida .="                           <option value=\"".$i."\" selected>".$i."</option> \n";
                }
                else
                {
                    $this->salida .="                           <option value=\"".$i."\">".$i."</option> \n";
                }    
            }
        }    
        $this->salida .="                         </select>\n";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      </tr>\n";
        $this->salida .= "                      <tr>\n";
        $this->salida .= "                          <td class=\"modulo_table_list_title\">";
        $this->salida .= "                          FECHA INICIAL";
        $this->salida .= "                          </td>";
        $this->salida .= "                          <td class=\"modulo_list_claro\">";
        $this->salida .= "                          <B>".$fecha_inicio_lapso."</B>";
        $this->salida .= "                          </td>";
        $this->salida .= "                          <td class=\"modulo_table_list_title\">";
        $this->salida .= "                          FECHA FINAL";
        $this->salida .= "                          </td>";
        $this->salida .= "                          <td class=\"modulo_list_claro\">";
        $this->salida .= "                          <B>".$fecha_final_lapso."</B>";
        $this->salida .= "                          </td>";
        $this->salida .= "                      </tr>\n";
        $this->salida .= "                    </table>\n";
        $this->salida .= "                   </td>\n";
        $this->salida .= "                   </tr>\n";
//         $this->salida .= "                  <td align='left' class=\"modulo_table_list_title\" >\n";
//         $this->salida .= "                     TIPO MOVIMIENTO";
//         $this->salida .= "                   </td>\n";
//         $this->salida .= "                   <td align='left' class='modulo_list_claro'>\n";
//         $this->salida .= "                         <select name=\"tipo_cri\" id=\"tipo_cri\" class=\"select\">";
//         $this->salida .="                           <option value=\"\" SELECTED>TODOS</option> \n";
//         $this->salida .="                           <option value=\"I\">INGRESO</option> \n";
//         $this->salida .="                           <option value=\"E\">EGRESO</option> \n";
//         $this->salida .="                           <option value=\"T\">TRASLADO</option> \n";
//         $this->salida .="                           <option value=\"C\">CARGO</option> \n";
//         $this->salida .="                           <option value=\"D\">DESCARGO</option> \n";
//         $this->salida .="                         </select>\n";
//         $this->salida .= "                   </td>\n";
//         $this->salida .= "                  <td align='left' class=\"modulo_table_list_title\">\n";
//         $this->salida .= "                    TIPO DE DOCUMENTO";
//         $this->salida .= "                   </td>\n";
//         $this->salida .= "                   <td colspan='3' align='left' class='modulo_list_claro'>\n";
//         $this->salida .= "                         <select name=\"limite\" id=\"limite\" class=\"select\" disabled>";
//         $this->salida .="                           <option value=\"10\">SELECCIONAR</option> \n";
//         $this->salida .="                           <option value=\"15\">15</option> \n";
//         $this->salida .= "                           <option value=\"50\">50</option> \n";
//         $this->salida .= "                           <option value=\"100\">100</option> \n";
//         $this->salida .= "                           <option value=\"200\">200</option> \n";
//         $this->salida .= "                           <option value=\"\">TODOS</option> \n";
//         $this->salida .= "                         </select>\n";
//         $this->salida .= "                   </td>\n";
//         $this->salida .= "                   </tr>\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                     <td colspan='6' align='center' class='modulo_list_claro'>\n";
        $this->salida .= "                      <input type=\"submit\" name=\"boton_buscar\" class=\"input-submit\" value=\"BUSCAR REGISTROS\">\n";
        $this->salida .= "                     </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "                  </table>\n";
        $this->salida .= "                 </form>\n";
        $this->salida .= "                 <br>\n";

        if($_REQUEST['periodo']==date("Ym") && !empty($_REQUEST['boton_buscar']))
        {
        //VAR_DUMP($resultado);
            $salida .= "                   <table width=\"90%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td  colspan='8' align=\"center\">\n";
            $salida .= "                        EXISTENCIAS";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td align=\"center\" width='12%'>\n";
            $salida .= "                          INICIAL";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='13%'>\n";
            $salida .= "                          INGRESOS";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='12%'>\n";
            $salida .= "                          EGRESOS";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='13%'>\n";
            $salida .= "                          ACTUAL";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='12%'>\n";
            $salida .= "                           DESCUADRE";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='13%'>\n";
            $salida .= "                           GLOBAL";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='12%'>\n";
            $salida .= "                           MINIMA";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='13%'>\n";
            $salida .= "                           MAXIMA";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    <tr class=\"modulo_list_claro\">\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['existencia_inicial'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['ingresos'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['egresos'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['existencia'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['descuadre'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['existencia_global'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['existencia_minima'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['existencia_maxima'];
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                   </table>\n";
            
            
//             $salida .= "                    <tr>\n";
//             $salida .= "                       <td colspan='1' align=\"center\" class=\"modulo_list_claro\">\n";
//             $historico = "javascript:MostrarCapa('ContenedorCent');IniciarCent('HISTORICO DEL PRODUCTO');";//
//             $salida .= "                         <a  title=\"VER HISTORICO DEL PRODUCTO\" class=\"label_error\" href=\"".$historico."\">\n";
//             $salida .= "                          <sub>VER HISTORICO DE EXISTENCIAS</sub>\n";
//             $salida .= "                         </a>\n";
//             $salida .= "                       </td>\n";
//             $salida .= "                       <td colspan='1' align=\"center\" class=\"modulo_list_claro\">\n";
//             $historico = "javascript:MostrarCapa('ContenedorVer');IniciarVer('EXISTENCIAS BODEGAS');";//
//             $salida .= "                         <a  title=\"VER EXISTENCIAS BODEGAS\" class=\"label_error\" href=\"".$historico."\">\n";
//             $salida .= "                          <sub>VER EXISTENCIAS DE TODAS LAS BODEGAS</sub>\n";
//             $salida .= "                         </a>\n";
//             $salida .= "                       </td>\n";
//             $salida .= "                    </tr>\n";
//             $salida .= "                   </table>\n";
            $salida .= "                   <br>\n";
            $this->salida .=$salida;


        }
        else
        {
           // VAR_DUMP($resultado);
            if(!empty($_REQUEST['boton_buscar']))
            {
                $salida .= "                   <table width=\"90%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";
                $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
                $salida .= "                       <td  colspan='8' align=\"center\">\n";
                $salida .= "                        EXISTENCIAS";
                $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
                $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
                $salida .= "                       <td align=\"center\" width='12%'>\n";
                $salida .= "                          INICIAL";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\" width='13%'>\n";
                $salida .= "                          INGRSOS";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\" width='12%'>\n";
                $salida .= "                          EGRESOS";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\" width='13%'>\n";
                $salida .= "                          FINAL";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\" width='12%'>\n";
                $salida .= "                           DESCUADRE";
                $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
                $salida .= "                    <tr class=\"modulo_list_claro\">\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['existencia_inicial'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['ingresos'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['egresos'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['existencia_final'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['descuadre'];
                $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
                $salida .= "                   </table>\n";
                $salida .= "                   <br>\n";
                $this->salida .=$salida;
            }

        }
                
        if(!empty($resultado['KARDEX']) && !empty($_REQUEST['boton_buscar']))
        {
            $this->salida .= "                   <table width=\"90%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       <td colspan='12' align=\"center\">\n";
            $nombre=$consulta->bodegasname($bodega);
            $this->salida .= "                        KARDEX BODEGA: ".$bodega."-".$nombre[0]['descripcion'].",    DESDE ".$fecha_inicial."  HASTA ".$fecha_final;
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
            $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       <td align=\"center\" width='2%'>\n";
            $this->salida .= "                         &nbsp; ";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align=\"center\" width='4%'>\n";
            $this->salida .= "                          T_M";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align=\"center\" width='8%'>\n";
            $this->salida .= "                          FECHA";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align=\"center\" width='8%'>\n";
            $this->salida .= "                          NUMERO";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align=\"center\" width='35%'>\n";
            $this->salida .= "                          OBSERVACIONES DOCUMENTO";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align=\"center\" width='7%'>\n";
            $this->salida .= "                          ENTRADAS";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align=\"center\" width='8%'>\n";
            $this->salida .= "                          SALIDAS";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align=\"center\" width='7%'>\n";
            $this->salida .= "                          COSTO UNITARIO";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align=\"center\" width='8%'>\n";
            $this->salida .= "                          COSTO TOTAL";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align=\"center\" width='8%'>\n";
            $this->salida .= "                          USUARIO";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
            $SALDO_ACT=0;
            for($i=(count($resultado['KARDEX'])-1);$i>=0;$i--)
            {
                if($resultado['KARDEX'][$i]['tipo']=='EGRESO')
                { 
                    $espejo[$i]['tipo']='S';
                    $espejo[$i]['cantidad']=$resultado['KARDEX'][$i]['cantidad'];
                    $SALDO_ACT=$SALDO_ACT+$espejo[$i]['cantidad'];
                }
                elseif($resultado['KARDEX'][$i]['tipo']=='INGRESO')
                {
                    $espejo[$i]['tipo']='R';
                    $espejo[$i]['cantidad']=$resultado['KARDEX'][$i]['cantidad'];
                    $SALDO_ACT=$SALDO_ACT-$espejo[$i]['cantidad'];
                }  
                       
            
            }
            
            //var_dump($espejo);
            $BODEXXXX=$consulta->bodegasname($bodega);
            //var_dump($BODEXXXX);
            $SALDO_ACT=$resultado['existencia'];
            $suma_egresos=0;
            $suma_ingresos=0;
            $suma_egresos_por_costo=0;
            $suma_ingresos_por_costo=0;
            for($i=0;$i<count($resultado['KARDEX']);$i++)
            {
                $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
                $this->salida .= "                       <td align=\"center\">\n";
                $this->salida .= "                        ".$i+1+$offset;
                $this->salida .= "                       </td>\n";
//               $this->salida .= "                       <td align=\"center\">\n";
//               $this->salida .= "                        ".$resultado['KARDEX'][$i]['tipo'];
//               $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td align=\"center\">\n";
                $this->salida .= "                        ".$resultado['KARDEX'][$i]['tipo_movimiento'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td align=\"center\">\n";
                $this->salida .= "                        ".$resultado['KARDEX'][$i]['fecha'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td align=\"left\">\n";
                if(!empty($resultado['KARDEX'][$i]['bodegas_doc_id']))
                {
                    $direccion="app_modules/Inv_Movimientos_Admin/Imprimir/imprimir_doc2.php";
                    $imagen = $resultado['KARDEX'][$i]['prefijo']."-".$resultado['KARDEX'][$i]['numero'];      //"themes/$VISTA/" . GetTheme() ."/images//imprimir.png";
                    $actualizar="false";
                    $alt="IMPRIMIR DOCUMENTO";
                    $x=$this->RetornarImpresionDoc1($direccion,$alt,$imagen,$resultado['KARDEX'][$i]['bodegas_doc_id'],$resultado['KARDEX'][$i]['numero'],$resultado['codigo_producto']);
                                    
                }
                elseif(empty($resultado['KARDEX'][$i]['bodegas_doc_id']))
                {
                    $direccion="app_modules/Inv_Movimientos_Admin/Imprimir/imprimir_docI001.php";
                    $imagen = $resultado['KARDEX'][$i]['prefijo']."-".$resultado['KARDEX'][$i]['numero'];      //"themes/$VISTA/" . GetTheme() ."/images//imprimir.png";
                    $actualizar="false";
                    $alt="IMPRIMIR DOCUMENTO";
                    $x=$this->RetornarImpresionDoc53($direccion,$alt,$imagen,SessionGetVar("EMPRESA"),$resultado['KARDEX'][$i]['prefijo'],$resultado['KARDEX'][$i]['numero']);
                }                                                        
                $this->salida .= "                       ".$x."";
                //$this->salida .= "                        ".$resultado['KARDEX'][$i]['prefijo']."-".$resultado['KARDEX'][$i]['numero'];
                $this->salida .= "                       </td>\n";
		$datosPacientes = "";
		if($resultado['KARDEX'][$i]['numerodecuenta'])
		{
		$datosPacientes = $this->MostrarDatosPaciente($resultado['KARDEX'][$i]['numerodecuenta']);
		}
                $this->salida .= "                       <td align=\"left\">\n";
                $this->salida .= "                        ".$resultado['KARDEX'][$i]['observacion'].'<br><b>'.$datosPacientes.'</b>';
                $this->salida .= "                       </td>\n";

              //$this->salida .= "                       <td align=\"center\">\n";
                $partes=explode(".", $resultado['KARDEX'][$i]['cantidad']);
                if($partes[1]>0)
                {
                    $resultado['KARDEX'][$i]['cantidad']=$partes[0].".".$partes[1];
                }
                else
                {
                    $resultado['KARDEX'][$i]['cantidad']=$partes[0];
                }
              
                if($resultado['KARDEX'][$i]['tipo']=='INGRESO')
                { 
                    $this->salida .= "                       <td align=\"right\">\n";
                    $this->salida .= "                        ".$resultado['KARDEX'][$i]['cantidad'];
                    $this->salida .= "                       </td>\n";
                    $this->salida .= "                       <td align=\"right\">\n";
                    $this->salida .= "                        &nbsp;";
                    $this->salida .= "                       </td>\n";
                    $suma_ingresos +=$resultado['KARDEX'][$i]['cantidad'];
                }
                elseif($resultado['KARDEX'][$i]['tipo']=='EGRESO')
                {   
                    $this->salida .= "                       <td align=\"right\">\n";
                    $this->salida .= "                        &nbsp;";
                    $this->salida .= "                       </td>\n";
                    $this->salida .= "                       <td align=\"right\">\n";
                    $this->salida .= "                        ".$resultado['KARDEX'][$i]['cantidad'];
                    $this->salida .= "                       </td>\n";
                    $suma_egresos +=$resultado['KARDEX'][$i]['cantidad'];   
                }

//$this->salida .= "                        ".$resultado['KARDEX'][$i]['cantidad'];
//$this->salida .= "                       </td>\n";
//               $this->salida .= "                       <td align=\"right\">\n";
//               if($resultado['KARDEX'][$i]['tipo']=='EGRESO')
//                { 
//                  $SALDO_ACT1=$SALDO_ACT+$espejo[$i]['cantidad'];
//                }
//                elseif($resultado['KARDEX'][$i]['tipo']=='INGRESO')
//                {
//                  $SALDO_ACT1=$SALDO_ACT-$espejo[$i]['cantidad'];
//                }  
//$this->salida .= "                        ".$SALDO_ACT1;
//$this->salida .= "                       </td>\n";
//$this->salida .= "                       <td align=\"right\">\n";
//$this->salida .= "                        ".$SALDO_ACT;
//$SALDO_ACT=$SALDO_ACT1;
//$this->salida .= "                       </td>\n";
                $this->salida .= "                       <td align=\"right\">\n";
                $this->salida .= "                        ".FormatoValor($resultado['KARDEX'][$i]['costo']);
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td align=\"right\">\n";
                $this->salida .= "                        ".FormatoValor($resultado['KARDEX'][$i]['costo']*$resultado['KARDEX'][$i]['cantidad']);
                $this->salida .= "                       </td>\n"; 
                if($resultado['KARDEX'][$i]['tipo']=='EGRESO')
                { 
                    $suma_egresos_por_costo +=($resultado['KARDEX'][$i]['costo']*$resultado['KARDEX'][$i]['cantidad']);
//                   $SALDO_ACT1=$SALDO_ACT+$espejo[$i]['cantidad'];
//                   $this->salida .= "                       <td align=\"right\">\n";
//                   $this->salida .= "                        ".$SALDO_ACT;
//                   $this->salida .= "                       </td>\n";
//                   
                }
                elseif($resultado['KARDEX'][$i]['tipo']=='INGRESO')
                {
                    $suma_ingresos_por_costo +=($resultado['KARDEX'][$i]['costo']*$resultado['KARDEX'][$i]['cantidad']);
//                  $SALDO_ACT1=$SALDO_ACT-$espejo[$i]['cantidad'];
//                  $this->salida .= "                       <td align=\"right\">\n";
//                  $this->salida .= "                        ".$SALDO_ACT;
//                  $this->salida .= "                       </td>\n";
                }
//               
//               $SALDO_ACT=$SALDO_ACT1;
                $this->salida .= "                       <td align=\"center\">\n";
                $this->salida .= "                        ".$resultado['KARDEX'][$i]['usuario'];
                $this->salida .= "                       </td>\n";
//               $this->salida .= "                       <td align=\"center\">\n";
//               $this->salida .= "                        ".$resultado['KARDEX'][$i]['numerodecuenta'];
//               $this->salida .= "                       </td>\n";
                $this->salida .= "                    </tr>\n";
            }       
            
            $this->salida .= "                   </table>\n";
            //echo "limite".$limite;
            //echo "offset".$offset;
            //echo "hay".$slc;
            //echo "fecha1".$fecha_inicial;
           // echo "fecha2".$fecha_final;
            $this->salida .= "                   <table width=\"90%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "                    <tr>\n";
            $this->salida .= "                    <td colspan='1' align='right' class=\"modulo_list_claro\">\n";
            $this->salida .= "                    <label class='label_error'>TOTAL PARCIAL UNIDADES INGRESOS</label>";
            $this->salida .= "                    </td>\n";
            $this->salida .= "                    <td align='right' class=\"modulo_list_claro\">\n";
            $this->salida .= "                     <label>".$suma_ingresos."</label>";
            $this->salida .= "                    </td>\n";
            $this->salida .= "                    <td colspan='1' align='right' class=\"modulo_list_claro\">\n";
            $this->salida .= "                    <label class='label_error'>TOTAL PARCIAL UNIDADES EGRESOS</label>";
            $this->salida .= "                    </td>\n";
            $this->salida .= "                    <td align='right' class=\"modulo_list_claro\">\n";
            $this->salida .= "                     <label>".$suma_egresos."</label>";
            $this->salida .= "                    </td>\n";
            $this->salida .= "                    </tr>\n";
            $this->salida .= "                   </table>\n";
            $this->salida .= "                   <br>\n";
           // var_dump($resultado['TOTALES']);
//             if(!empty($resultado['TOTALES']))
//             {
//                
//                 $cadena .= "                   <table width=\"90%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";
//                 $cadena .= "                    <tr>\n";
//                 $cadena .= "                    <td colspan='1' align='right' class=\"modulo_list_claro\">\n";
//                 $cadena .= "                    <label class='label_error'>TOTAL UNIDADES INGRESOS</label>";
//                 $cadena .= "                    </td>\n";
//                 $cadena .= "                    <td align='right' class=\"modulo_list_claro\">\n";
//                 $cadena .= "                     <label>".$resultado['TOTALES']['INGRESO']['cantidad']."</label>";
//                 $cadena .= "                    </td>\n";
//                 $cadena .= "                    <td colspan='1' align='right' class=\"modulo_list_claro\">\n";
//                 $cadena .= "                    <label class='label_error'>TOTAL PARCIAL UNIDADES EGRESOS</label>";
//                 $cadena .= "                    </td>\n";
//                 $cadena .= "                    <td align='right' class=\"modulo_list_claro\">\n";
//                 $cadena .= "                     <label>".$resultado['TOTALES']['EGRESO']['cantidad']."</label>";
//                 $cadena .= "                    </td>\n";
//                 $cadena .= "                    </tr>\n";
//                 $cadena .= "                    <tr>\n";
//                 $cadena .= "                     <td colspan='1' align='right' class=\"modulo_list_claro\">\n";
//                 $cadena .= "                      <label class='label_error'>TOTAL PARCIAL COSTO INGRESOS</label>";
//                 $cadena .= "                      </td>\n";
//                 $cadena .= "                    <td align='right' class=\"modulo_list_claro\">\n";
//                 $cadena .= "                     <label>".FormatoValor($resultado['TOTALES']['INGRESO']['costo_total'])."</label>";
//                 $cadena .= "                    </td>\n";
//                 $cadena .= "                     <td colspan='1' align='right' class=\"modulo_list_claro\">\n";
//                 $cadena .= "                      <label class='label_error'>TOTAL PARCIAL COSTO EGRESOS</label>";
//                 $cadena .= "                     </td>\n";
//                 $cadena .= "                    <td align='right' class=\"modulo_list_claro\">\n";
//                 $cadena .= "                     <label>".FormatoValor($resultado['TOTALES']['EGRESO']['costo_total'])."</label>";
//                 $cadena .= "                    </td>\n";
//                 $cadena .= "                    </tr>\n";
//                 $cadena .= "                   </table>\n";
//                   
//             }
            //$this->salida .= "".$this->ObtenerPaginadoRegistros($path,$slc,'1',$empresa_id,$centro_id,$bodega,$codigo_producto,$limite,$offset,$count,$fecha_inicial,$fecha_final,$tipo=null,$tipo_movimiento,$pagina,$cadena);
            //$this->salida .= "                     <br>\n";
//             if(!EMPTY($_REQUEST['cadena']))
//             {
//                 $this->salida .= "".$this->ObtenerPaginadoRegistros($path,$slc,'1',$empresa_id,$centro_id,$bodega,$codigo_producto,$limite,$offset,$count,$fecha_inicial,$fecha_final,$tipo=null,$tipo_movimiento,$pagina);
//                 $this->salida .= "                     <br>\n";
//                 $this->salida .=$_REQUEST['cadena']; 
//             }
//             else
//             {
                   // $resultado=$consulta1->                GetInfoProductoPorLapso($empresa_id,$centro_id,$bodega,$codigo_producto,$limite,$offset,$count=null, $lapso,      $dia_inicial,      $dia_final,      $tipo=null,$tipo_movimiento);
                $this->salida .= "".$this->ObtenerPaginadoRegistros($path,$slc,'1',$empresa_id,$centro_id,$bodega,$codigo_producto,$limite,$offset,$count,     $lapso,       $dia_inicial,      $dia_final,      $tipo=null,$tipo_movimiento,$pagina);
                $this->salida .= "                     <br>\n";
                $this->salida .=$cadena;
//             }
            
            $this->salida .= "                   <table WIDTH='90%' align='center'>\n";
            $this->salida .= "                     <tr>\n";
            $this->salida .= "                       <td align='left'>\n";
                $direccion="app_modules/Inv_Movimientos_Admin/Imprimir/imprimir_producto1.php";
                $codigo = $codigo_producto;
                /*$fecha_inicio_lapso=$_REQUEST['fecha_inicio_lapso'];
                  $fecha_final_lapso=$_REQUEST['fecha_final_lapso'];*/
             $x=$this->RetornarImpresionDoc2($direccion,$alt,$empresa_id,$centro_id,$bodega,$codigo_producto,$lapso,$fecha_inicio_lapso,$fecha_final_lapso);
                $alt="VER INFORMACION DEL PRODUCTO";
            $this->salida .= "                         ".$x."";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td align='RIGHT'>\n";
                $direccion="app_modules/Inv_Movimientos_Admin/Imprimir/imprimir_producto2.php";
                $codigo = $codigo_producto;
                $alt="VER INFORMACION DEL PRODUCTO";
                $x=$this->RetornarImpresionDoc3($direccion,$alt,$empresa_id,$codigo_producto,$lapso,$dia_inicial,$dia_final);
            $this->salida .= "                         ".$x."";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                     </tr>\n";
            $this->salida .= "                   </table>\n"; 
        }
        elseif(empty($_REQUEST['boton_buscar']))
        {
            $this->salida .= "                   <table WIDTH='90%' align='center'>\n";
            $this->salida .= "                   <tr>\n";
            $this->salida .= "                   <td align='center'>\n";
            $this->salida .= "                     <label class='label_error'>DEBE SELECCIONAR LOS PARAMETROS DE BUSQUEDA</label>\n";
            $this->salida .= "                   </td>\n";
            $this->salida .= "                   </tr>\n";
            $this->salida .= "                   </table>\n";
        }
        elseif(empty($resultado['KARDEX']) && !empty($_REQUEST['boton_buscar']))
        {
            $this->salida .= "                   <table WIDTH='90%' align='center'>\n";
            $this->salida .= "                   <tr>\n";
            $this->salida .= "                   <td align='center'>\n";
            $this->salida .= "                     <label class='label_error'>NO SE ENCONTRARON REGISTROS DEL KARDEX</label>\n";
            $this->salida .= "                   </td>\n";
            $this->salida .= "                   </tr>\n";
            $this->salida .= "                   </table>\n"; 
        }   
        $this->salida .= "                   <br>\n";
        $this->salida.="<script language=\"javaScript\">
              var ban=0;
              function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

              function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }
    
             </script>";
    
    
        $Volver=ModuloGetURL('app','Inv_Movimientos_Admin','user','BuscadorProductos',array('centro_id'=>$centro_id,'bodega'=>$bodega,'codigo_pro'=>$codigo_producto,'nom_bus'=>$nom_bus,'grupos_pro'=>$grupo,'clasexx'=>$clasex,'subclasexy'=>$subclase));
        $this->salida .= "    <div id=\"volvercen_cos\">";
        $this->salida .= "     <form name=\"volver\" action=\"".$Volver."\" method=\"post\">\n";
        $this->salida .= "      <table align=\"center\" width=\"50%\">\n";
        $this->salida .= "       <tr>\n";
        $this->salida .= "        <td align=\"center\" colspan='7'>\n";
        $this->salida .= "          <input type=\"submit\" class=\"input-submit\" name=\"VolverCuy\" value=\"Volver\">\n";
        $this->salida .= "        </td>\n";
        $this->salida .= "       </tr>\n";
        $this->salida .= "      </table>\n";
        $this->salida .= "     </form>";
        $this->salida .= "    </div>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }
 
    /**
    * funcion q sirve para colocar una ventana emergente
    * @param string  $direccion (ruta del archivo a procesar)
    * @param string  $alt (comentario auxiliar del link)
    * @param string  $empresa_id (id dela empresa)
    * @param string  $centro_id (id centro de utilidad)
    * @param string  $bodega (id de la bodega)
    * @param string  $codigo (codigo del producto)
    * @param string  $fecha_inicial 
    * @param string  $fecha_final 
    * @return string $salida1 caddena con la forma a pintar  
    **/  
    function RetornarImpresionDoc2($direccion,$alt,$empresa_id,$centro_id,$bodega,$codigo,$lapso,$fecha_inicial,$fecha_final)
    {    
        global $VISTA;
                                                     //Imprimir1(direccion,    empresa_id,   centro_id,   bodega,    codigo,fecha_inicial,fecha_final,tipo_movimiento,tipo_doc_general_id)
        $salida1 ="<a title='".$alt."' href=javascript:Imprimir1('$direccion','$empresa_id','$centro_id','$bodega','$codigo','$fecha_inicial','$fecha_final','','')>Obtener reporte de esta consulta</a>";
        return $salida1;
    }

    /**
    * funcion q sirve para colocar una ventana emergente
    * @param string  $direccion (ruta del archivo a procesar)
    * @param string  $alt (comentario auxiliar del link)
    * @param string  $empresa_id (id dela empresa)
    * @param string  $centro_id (id centro de utilidad)
    * @param string  $bodega (id de la bodega)
    * @param string  $codigo (codigo del producto)
    * @param string  $fecha_inicial 
    * @param string  $fecha_final 
    * @return string $salida1 caddena con la forma a pintar  
    **/
    function RetornarImpresionDoc3($direccion,$alt,$empresa_id,$codigo,$lapso,$fecha_inicial,$fecha_final)
    {    
        global $VISTA;
        $salida1 ="<a title='".$alt."' href=javascript:Imprimir2('$direccion','$empresa_id','$codigo','$lapso','$fecha_inicial','$fecha_final')>Obtener Movimiento General del Producto</a>";
        return $salida1;
    }
    /**
    * Metodo para obtener el paginador de proveedores
    *
    * @param string   $path (ruta de las imagenes del siis)
    * @param string   $slc (numero total de registro de la consulta realizada)
    * @param string   $op (posciuon del paginador arriba o abajo)
    * @param string   $path (ruta de las imagenes del siis)
    * @param integer  $tipo_de_busqueda (CRITERIO DE BUSQUEDA)
    * @param integer  $tipo_de_busqueda_aux (CRITERIO DE BUSQUEDA)
    * @param integer  $valor_de_busqueda 
    * @param integer  $limite (cantidad de refistros a mostrar por pagina)
    * @param integer  $pagina (pagina de la consulta)
    
    * @access public     
    */    //   ObtenerPaginadoRegistros($path,$slc,'1',$empresa_id,$centro_id,$bodega,$codigo_producto,$limite,$offset,$count,$lapso,$dia_inicial,$dia_final,$tipo=null,$tipo_movimiento,$pagina);
    function ObtenerPaginadoRegistros($path,$slc,$op,$empresa_id,$centro_id,$bodega,$codigo_producto,$limit, $offset,$count,$lapso,$fecha1,$fecha2,$tipo=null,$tipo_movimiento,$pagina)
    {
      
        $TotalRegistros = $slc;
        $TablaPaginado = "";
        
        if($limite == null)
        {
            $uid = UserGetUID();
            $LimitRow = 10;//intval(GetLimitBrowser()
        }
        else
        {
            $LimitRow = $limite;
        }
        if ($TotalRegistros > 0)
        {
            $columnas = 1;
            $NumeroPaginas = intval($TotalRegistros/$LimitRow);
            
            if($TotalRegistros%$LimitRow > 0)
            {
                $NumeroPaginas++;
            }
                
            $Inicio = $pagina;
            if($NumeroPaginas - $pagina < 9 )
            {
                $Inicio = $NumeroPaginas - 9;
            }
            elseif($pagina > 1)
            {
                $Inicio = $pagina - 1;
            }
            
            if($Inicio <= 0)
            {
                $Inicio = 1;
            }
            
            $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" "; 
    
            $TablaPaginado .= "<tr>\n";
            if($NumeroPaginas > 1)
            {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P�inas:</td>\n";
                if($pagina > 1)
                {
                    $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                    $CONSULTARPRO=ModuloGetURL('app','Inv_Movimientos_Admin','user','DatosProducto',array('empresa_id'=>$empresa_id,'centro_id'=>$centro_id,'bodega'=>$bodega,'codigo_producto'=>$codigo_producto,'limite'=>10,'pagina'=>'1','periodo'=>$lapso,'dia1'=>$fecha1,'dia2'=>$fecha2,'boton_buscar'=>true));
                    $TablaPaginado .= "     <a class=\"label_error\" href=\"".$CONSULTARPRO."\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                    $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                    $CONSULTARPRO=ModuloGetURL('app','Inv_Movimientos_Admin','user','DatosProducto',array('empresa_id'=>$empresa_id,'centro_id'=>$centro_id,'bodega'=>$bodega,'codigo_producto'=>$codigo_producto,'limite'=>10,'pagina'=>$pagina-1,'periodo'=>$lapso,'dia1'=>$fecha1,'dia2'=>$fecha2,'boton_buscar'=>true));
                    $TablaPaginado .= "     <a class=\"label_error\" href=\"".$CONSULTARPRO."\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                    $TablaPaginado .= "   </td>\n";
                    $columnas +=2;
                }
                $Fin = $NumeroPaginas + 1;
                if($NumeroPaginas > 10)
                {
                    $Fin = 10 + $Inicio;
                }
                
                for($i=$Inicio; $i< $Fin ; $i++)
                {
                    if ($i == $pagina )
                    {
                        $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>".$i."</b></td>\n";
                    }
                    else
                    {
                        $CONSULTARPRO=ModuloGetURL('app','Inv_Movimientos_Admin','user','DatosProducto',array('empresa_id'=>$empresa_id,'centro_id'=>$centro_id,'bodega'=>$bodega,'codigo_producto'=>$codigo_producto,'limite'=>10,'pagina'=>$i,'periodo'=>$lapso,'dia1'=>$fecha1,'dia2'=>$fecha2,'boton_buscar'=>true));
                        $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"".$CONSULTARPRO."\">".$i."</a></td>\n";
                    }
                    $columnas++;
                }
            }
            if($pagina <  $NumeroPaginas )
            {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";      //tercos('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','".$NumeroPaginas."')
                $CONSULTARPRO=ModuloGetURL('app','Inv_Movimientos_Admin','user','DatosProducto',array('empresa_id'=>$empresa_id,'centro_id'=>$centro_id,'bodega'=>$bodega,'codigo_producto'=>$codigo_producto,'limite'=>10,'pagina'=>($pagina+1),'periodo'=>$lapso,'dia1'=>$fecha1,'dia2'=>$fecha2,'boton_buscar'=>true));                                                                           //       array('empresabus_id'=>$_REQUEST['empresabus_id'],             'fecha1'=>$_REQUEST['fecha1'],'fecha2'=>$_REQUEST['fecha2'],'bodega'=>$_REQUEST['bodega'],'nom_bodega'=>utf8_decode($_REQUEST['nom_bodega'])
                $TablaPaginado .= "     <a class=\"label_error\" href=\"".$CONSULTARPRO."\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $CONSULTARPRO=ModuloGetURL('app','Inv_Movimientos_Admin','user','DatosProducto',array('empresa_id'=>$empresa_id,'centro_id'=>$centro_id,'bodega'=>$bodega,'codigo_producto'=>$codigo_producto,'limite'=>10,'pagina'=>$NumeroPaginas,'periodo'=>$lapso,'dia1'=>$fecha1,'dia2'=>$fecha2,'boton_buscar'=>true));
                $TablaPaginado .= "     <a class=\"label_error\"  href=\"".$CONSULTARPRO."\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
            $aviso .= "     Pgina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
            $aviso .= "   </tr>\n";
            
            if($op == 2)
            {
                $TablaPaginado .= $aviso;
            }
            else
            {
                $TablaPaginado = $aviso.$TablaPaginado;
            }
      }
      
      $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
      $Tabla .= $TablaPaginado;
      $Tabla .= "</table>";

      return $Tabla;
    }

    /**
    * Metodo para obtener el paginador de proveedores
    * @access public  
    * @return string $this->salida    
    */  
    function MenuBodegas()
    {

        $this->CrearElementos();
        IncludeClass("ClaseHTML");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $path = SessionGetVar("rutaImagenes");
        $bodegas = SessionGetVar("Chentro");
        $a = $bodegas;
        
        $consulta= new MovBodegasAdminSQL();
        $consulta1= new BodegasDocumentos();
        $this->IncludeJS('RemoteXajax/definirAdmin.js', $contenedor='app', $modulo='Inv_Movimientos_Admin');
        $file = 'app_modules/Inv_Movimientos_Admin/RemoteXajax/definirAdmin.php';
        $this->SetXajax(array("GetUpBodega","BuscarDocumentx","Poner_descr","Poner_pref","ObtenerDatosDocumento1"),$file);
        $this->salida .= ThemeAbrirTabla("BUSCADOR DE DOCUMENTOS POR BODEGA");
        $REVISARDOCS=ModuloGetURL('app','Inv_Movimientos_Admin','user','MenuBodegas',array('empresabus_id'=>$_REQUEST['empresabus_id'],'fecha1'=>$_REQUEST['fecha1'],'fecha2'=>$_REQUEST['fecha2']));
        $this->salida .= "<form name=\"menu_docu\" action=\"".$REVISARDOCS."\" method=\"post\">\n";
        $this->salida .= "  <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "      <td COLSPAN='2'>BUSCAR DOCUMENTOS</td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "      <td width='30%' >CENTRO DE UTILIDAD</td>\n";
        $this->salida .= "      <td width='70%' class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "        ".$bodegas['centro_descripcion'];
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "      <td >BODEGA</td>\n";
        $this->salida .= "      <td id='Select_bodega' class=\"modulo_list_claro\" align=\"left\">\n";
        if(empty($_REQUEST['VolverCris']))
          $this->salida .= "                      ".$bodegas["bodega_descripcion"];
        elseif(!empty($_REQUEST['VolverCris']))
          $this->salida .= "                      ".$_REQUEST['nom_bodega'];
      
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "      <td>FECHA INICIAL</td>\n";
        if(empty($_REQUEST['VolverCris']))
        { 
            $c=date('m')-1;
            //$fecha_inicial=date("Y-0".$c."-01");
			$fecha_inicial=date("Y-".$c."-01");
        }
        elseif(!empty($_REQUEST['VolverCris']))
        {
            $fecha_inicial=$_REQUEST['fecha1'];
        }  
        $this->salida .= "      <td class=\"modulo_list_claro\" width='15%' align=\"left\">\n";
        $this->salida .= "        <input type=\"text\" class=\"input-text\" name=\"fecha1\" id=\"fecha1\" size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"".$fecha_inicial."\">\n";
        $this->salida .= "        <sub>".ReturnOpenCalendario("menu_docu","fecha1","-")."</sub>";
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "      <td>FECHA FINAL</td>\n";
        $this->salida .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
        if(!empty($_REQUEST['VolverCris']))
        {
            $fecha2=$_REQUEST['fecha2'];
            $this->salida .= "                         <input type=\"text\" class=\"input-text\" name=\"fecha2\" id=\"fecha2\"  size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"".$fecha2."\">\n";
        }
        else
        {
            $this->salida .= "                         <input type=\"text\" class=\"input-text\" name=\"fecha2\" id=\"fecha2\"  size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"\">\n";
        }  
      
        $this->salida .= "                           <sub>".ReturnOpenCalendario("menu_docu","fecha2","-")."</sub>";
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "    <tr class=\"modulo_list_claro\" >\n";
        $this->salida .= "      <td colspan='2' align=\"center\">\n";
        if(empty($_REQUEST['VolverCris']))
        {
            $this->salida .= "                        <input type=\"button\" class=\"input-submit\" value=\"BUSCAR\" onclick=\"BuscarDocuments('".SessionGetVar("EMPRESA")."','".$a['centro_utilidad']."','".$bodegas['bodega']."',document.getElementById('fecha1').value,document.getElementById('fecha2').value,'".$bodegas['bodega_descripcion']."')\">\n";
        }
        elseif(!empty($_REQUEST['VolverCris']))
        {
            $this->salida .= "                        <input type=\"button\" class=\"input-submit\" value=\"BUSCAR\" onclick=\"BuscarDocuments('".SessionGetVar("EMPRESA")."','".$a['centro_utilidad']."','".$_REQUEST['bodega']."',document.getElementById('fecha1').value,document.getElementById('fecha2').value,'".$_REQUEST['nom_bodega']."')\">\n";
        }
      
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= "</form>\n";
        $this->salida .= "<br>\n";
      
        $this->salida .= "  <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "    <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "      <td COLSPAN='7'>BUSCAR DOCUMENTO</td>\n";
        $this->salida .= "    </tr>\n";
      
      
        if(empty($_REQUEST['VolverCris']))
        {
          $Prefijos = $consulta->Get_Prefijos(SessionGetVar("EMPRESA"),$a['centro_utilidad'],$bodegas['bodega']);
        }
        elseif(!empty($_REQUEST['VolverCris']))
        {
          $Prefijos=$consulta->Get_Prefijos(SessionGetVar("EMPRESA"),$a['centro_utilidad'],$_REQUEST['bodega']);
        }
      
        $empresaBus_id = SessionGetVar("EMPRESA");
        
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                      <td width='10%' class=\"modulo_table_list_title\"  align=\"center\">\n";
        $this->salida .= "                         PREFIJO";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      <td id='prefijo_solo' width='10%' class=\"modulo_list_claro\" align=\"left\">\n";
        if(empty($_REQUEST['VolverCris']))
        {
            $this->salida .= "                         <select name=\"prefijo_s\" id=\"prefijo_s\" class=\"select\" onchange=\"Poner_des(this.value,'".$a['centro_utilidad']."','".$bodegas['bodega']."');\">";
        }
        elseif(!empty($_REQUEST['VolverCris']))
        {
            $this->salida .= "                         <select name=\"prefijo_s\" id=\"prefijo_s\" class=\"select\" onchange=\"Poner_des(this.value,'".$a['centro_utilidad']."','".$_REQUEST['bodega']."');\">";
        }
        $this->salida .= "                           <option value=\"0\">--</option> \n";
        for($i=0;$i<count($Prefijos);$i++)
        {
            $this->salida .="                           <option value=\"".$Prefijos[$i]['prefijo']."\">".$Prefijos[$i]['prefijo']."</option> \n";
        }
        $this->salida .="                         </select>\n";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      <td width='10%' class=\"modulo_table_list_title\"  align=\"center\">\n";
        $this->salida .= "                         DESCRIPCION";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      <td id='prefijo_nombre' width='50%' class=\"modulo_list_claro\" align=\"left\">\n";
        if(empty($_REQUEST['VolverCris']))
        {
            $this->salida .= "                         <select name=\"prefijo_d\" id=\"prefijo_d\" class=\"select\" onchange=\"Poner_pre(this.value,'".$a['centro_utilidad']."','".$bodegas['bodega']."')\">";
        }
        elseif(!empty($_REQUEST['VolverCris']))
        {
            $this->salida .= "                         <select name=\"prefijo_d\" id=\"prefijo_d\" class=\"select\" onchange=\"Poner_pre(this.value,'".$a['centro_utilidad']."','".$_REQUEST['bodega']."')\">";
        }                                       //tipo_doc_general_id	prefijo	descripcion_documento
        $this->salida .="                           <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
        for($i=0;$i<count($Prefijos);$i++)
        {
            $this->salida .="                           <option value=\"".$Prefijos[$i]['prefijo']."\">".$Prefijos[$i]['descripcion_documento']."</option> \n";
        }
        $this->salida .= "                         </select>\n";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      <td width='5%' class=\"modulo_table_list_title\"  align=\"center\">\n";
        $this->salida .= "                         NUMERO";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      <td width='10%' class=\"modulo_list_claro\"  align=\"center\">\n";
        $this->salida .= "                         <input type=\"text\" class=\"input-text\" name=\"numerito\" id=\"numerito\" size=\"10\" onkeypress=\"return acceptNum(event)\" value=\"\">\n";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      <td class=\"modulo_list_claro\" colspan='1' align=\"center\">\n";
        $this->salida .= "                        <input type=\"button\" class=\"input-submit\" value=\"BUSCAR\" onclick=\"MostrarEseDocumento('".SessionGetVar("EMPRESA")."',document.getElementById('prefijo_s').value,document.getElementById('numerito').value)\">\n";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                   </tr>\n";
        $this->salida .= "        </table>\n";
        $this->salida .= "        <br>\n";
       
        $this->salida .= "<form name=\"menu_docu1\" action=\"javascript:LlamarDocu(1);\" method=\"post\">\n";
        $this->salida .= "<div id='docs_bodega'>";
        $this->salida .= "</div>";
        $this->salida .= "        <br>\n";
        $this->salida .= "        <br>\n";
        $this->salida .= "             </form>";
      
        if(empty($_REQUEST['VolverCris']))
        {
            $Volver=ModuloGetURL('app','Inv_Movimientos_Admin','user','Menu_Movimientos_Admin');
        }
        else
        {
          $Volver=ModuloGetURL('app','Inv_Movimientos_Admin','user','Menu_Movimientos_Admin',array('Bodeguix'=>array('descripcion'=>$_REQUEST['nom_bodega'],'bodega'=>$_REQUEST['bodega'])));
        }
        $this->salida .= " <form name=\"volver\" action=\"".$Volver."\" method=\"post\">\n";//".$this->action[0]."
        $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "       <td align=\"center\" colspan='7'>\n";
        $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "       </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= " </form>\n";
        $this->salida .= ThemeCerrarTabla();
        if(empty($_REQUEST['VolverCris']))
        {
            $this->salida.="<script language=\"javaScript\">
                                BuscarDocuments('".SessionGetVar("EMPRESA")."','".$a['centro_utilidad']."','".$bodegas['bodega']."',document.getElementById('fecha1').value,document.getElementById('fecha2').value,'".$bodegas['bodega_descripcion']."');
                            </script>";
        
        }
        elseif(!empty($_REQUEST['VolverCris']))
        {
            $this->salida.="<script language=\"javaScript\">
                                BuscarDocuments('".SessionGetVar("EMPRESA")."','".$a['centro_utilidad']."','".$_REQUEST['bodega']."',document.getElementById('fecha1').value,document.getElementById('fecha2').value,'".$_REQUEST['nom_bodega']."');
                            </script>";
        
        }
      
        $this->salida.="<script language=\"javaScript\">
      
            function mOvr(src,clrOver)
            {
                src.style.background = clrOver;
            }
            function mOut(src,clrIn)
            {
                src.style.background = clrIn;
            }
            </script>";
        return true;
    }


    /**
    * Metodo para listar los documentos segun la consulta
    * @access public  
    * @return string $this->salida    tabla con los documentos
    */  
    function ListarDocumentos()
    {
        global $VISTA;
        //echo "yooooo".$_REQUEST['nom_bodega'];
        $this->CrearElementos();
        IncludeClass("ClaseHTML");
        $path = SessionGetVar("rutaImagenes");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $consulta= new MovBodegasAdminSQL();
        $consulta1= new BodegasDocumentos();
        $this->IncludeJS('RemoteXajax/definirAdmin.js', $contenedor='app', $modulo='Inv_Movimientos_Admin');
        $file = 'app_modules/Inv_Movimientos_Admin/RemoteXajax/definirAdmin.php';
        $this->SetXajax(array("ObtenerDatosDocumento"),$file);
        $limit=20;
        $this->salida .= ThemeAbrirTabla("DOCUMENTOS ID :".$_REQUEST['documento_id']."-".$_REQUEST['nombre_doc']."");
        $BODEGA=ModuloGetURL('app','Inv_Movimientos_Admin','user','ListarDocumentos' ,array('empresa_idx'=>$empresaBus_id,'documento_id'=>$vector[$i]['documento_id'],'fecha1'=>$fecha_inicial,'fecha2'=>$fecha_final, 'nombre_doc'=>$vector[$i]['descripcion']));
        if(empty($_REQUEST['offset']) || $_REQUEST['offset']==1)
        {
            $offset=0;
            $pagina=1;
        }
        else
        {   //$offset=$_REQUEST['offset'];
            $offset=($_REQUEST['offset']-1)*$limit;
            $pagina=$_REQUEST['offset'];
        }
          //$BODEGA=ModuloGetURL('app','Inv_MovimientosBodegasReportes','user','ListarDocumentos' ,array('empresa_idx'=>$empresaBus_id,'documento_id'=>$vector[$i]['documento_id'],'fecha1'=>$fecha_inicial,'fecha2'=>$fecha_final, 'nombre_doc'=>$vector[$i]['descripcion']));
                
        $vector=$consulta1->GetTipoDocumento($_REQUEST['empresa_idx'], $_REQUEST['documento_id'], $_REQUEST['fecha1'], $_REQUEST['fecha2'], $count=null, $limit, $offset);
        $num_reg=$consulta1->GetTipoDocumento($_REQUEST['empresa_idx'], $_REQUEST['documento_id'], $_REQUEST['fecha1'], $_REQUEST['fecha2'], $count=true, $limit=null, $offset=null);
          //var_dump($num_reg);
          
        $javaC = "<script>\n";
        $javaC .= "var contenedor1=''\n";
        $javaC .= "   var titulo1=''\n";
        $javaC .= "   var hiZ = 2;\n";
        $javaC .= "   var DatosFactor = new Array();\n";
        $javaC .= "   var EnvioFactor = new Array();\n";
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
        $javaC1 .=$javaC;
        $javaC1 .= "   function IniciarDoc(tit)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "       contenedor1 = 'ContenedorDet';\n";
        $javaC1 .= "       titulo1 = 'tituloDet';\n";
        $javaC1 .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $javaC1 .= "       Capa = xGetElementById(contenedor1);\n";
        $javaC1 .= "       xResizeTo(Capa, 800, 'auto');\n";
        $javaC1 .= "       xMoveTo(Capa, xClientWidth()/10, xScrollTop()+20);\n";
        $javaC1 .= "       Capa = xGetElementById('ContenidoDet');\n";
        $javaC1 .= "       xResizeTo(Capa, 800, 350);\n";
        $javaC1 .= "       xMoveTo(Capa, xClientWidth()/10, xScrollTop()+20);\n";
        $javaC1 .= "       ele = xGetElementById(titulo1);\n";
        $javaC1 .= "       xResizeTo(ele, 780, 20);\n";
        $javaC1 .= "       xMoveTo(ele, 0, 0);\n";
        $javaC1 .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $javaC1 .= "       ele = xGetElementById('cerrarDet');\n";
        $javaC1 .= "       xResizeTo(ele, 20, 20);\n";
        $javaC1 .= "       xMoveTo(ele, 780, 0);\n";
        $javaC1 .= "   }\n";
        $javaC=$javaC1;
        $javaC.= "</script>\n";
        $salida.= $javaC;
        $javaC1= "<script>\n";
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
        $salida.= $javaC1;
        
        $this->salida .=$salida;
        $this->salida .= " <div id='ContenedorB3' class='d2Container' style=\"display:none;\">";
        $this->salida .= "    <div id='tituloB3' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
        $this->salida .= "    <div id='cerrarB3' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorB3');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $this->salida .= "    <div id='errorB3' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "    <div id='ContenidoB3'  class='d2Content' style='z-index:10;'>\n";
        $this->salida .= "    </div>\n";
        $this->salida .= " </div>\n";
        $this->salida .="    <div id='error_en_mov' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "   <br>";
          
        /**
        *div para mostrar el documento
        **/
        $this->salida.="<div id='ContenedorDet' class='d2Container' style=\"display:none\">";
        $this->salida .= "    <div id='tituloDet' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
        $this->salida .= "    <div id='cerrarDet' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorDet');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $this->salida .= "    <div id='errorDet' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "    <div id='ContenidoDet' class='d2Content'>\n";
        $this->salida .= "    </div>\n";
        $this->salida.="</div>"; 
          /*/////////////////////////////////////////////////////////////////////////////////*/ 
        $this->salida .="    <div id='refresh'>";
        if(!EMPTY($vector))
        {
              //tipo_movimiento  bodegas_doc_id  tipo_clase_documento  prefijo descripcion
            $vaclor_toctal=0;
            $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
            $this->salida .= "                          PREFIJO";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
            $this->salida .= "                          FECHA";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
            $this->salida .= "                       <a title='CENTRO DE UTILIDAD ID'>";
            $this->salida .= "                          CU";
            $this->salida .= "                       </a>";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"20%\" align=\"center\">\n";
            $this->salida .= "                          BODEGA";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"30%\" align=\"center\">\n";
            $this->salida .= "                         OBSERVACION";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
            $this->salida .= "                         VALOR";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
            $this->salida .= "                          USUARIO";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td colspan='2' width=\"10%\" align=\"center\">\n";
            $this->salida .= "                          ACCIONES";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
            
            for($i=0;$i<count($vector);$i++)
            {       
                $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
                $this->salida .= "                       <td class=\"normal_10AN\" align=\"center\">\n";
                $this->salida .= "                        ".$vector[$i]['prefijo']."-".$vector[$i]['numero'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td class=\"normal_10AN\" align=\"center\">\n";
                $this->salida .= "                          ".$vector[$i]['fecha_documento'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td class=\"normal_10AN\" align=\"center\">\n";
                $this->salida .= "                          ".$vector[$i]['centro_utilidad'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td class=\"normal_10AN\" align=\"center\">\n";
                $this->salida .= "                       <a title='".$vector[$i]['bodega']."'>";
                $this->salida .= "                          ".$vector[$i]['nom_bodega'];
                $this->salida .= "                       </a>";
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                $this->salida .= "                         ".$vector[$i]['observacion'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td  align=\"right\">\n";
                $this->salida .= "                         ".FormatoValor($vector[$i]['total_costo']);
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td class=\"normal_10AN\" align=\"center\">\n";
                $this->salida .= "                       <a title='".$vector[$i]['nombre']."'>";
                $this->salida .= "                          ".$vector[$i]['usuario'];
                $this->salida .= "                       </a>";
                $this->salida .= "                       </td>\n";
                $this->salida .= "                      <td class=\"normal_10AN\" align=\"center\">\n";
                $nuevousu = "javascript:MostarDatosDocumento('".$_REQUEST['empresa_idx']."','".$vector[$i]['prefijo']."','".$vector[$i]['numero']."');MostrarCapa('ContenedorDet');IniciarDoc('DATOS DEL DOCUMENTO');";//
                $this->salida .= "                          <a  title=\"LISTAR DOCUMENTOS\" class=\"label_error\" href=\"".$nuevousu."\">\n";
                $this->salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $this->salida .= "                         </a>\n";
                $this->salida .= "                      </td>\n";
                $this->salida .= "                      <td class=\"normal_10AN\" align=\"center\">\n";
                                                        $direccion="app_modules/Inv_MovimientosBodegasReportes/Imprimir/imprimir_docI001.php";
                                                        $imagen = "themes/$VISTA/" . GetTheme() ."/images//imprimir.png";
                                                        $actualizar="false";
                                                        $alt="IMPRIMIR DOCUMENTO";
                                                        $x=$this->RetornarImpresionDoc($direccion,$alt,$imagen,$_REQUEST['empresa_idx'],$vector[$i]['prefijo'],$vector[$i]['numero']);
                $this->salida .= "                       ".$x."";
                $this->salida .= "                      </td>\n";
                $this->salida .= "                    </tr>\n";
                $vaclor_toctal =$vaclor_toctal+$vector[$i]['total_costo'];
            }
            $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
            $this->salida .= "                       <td colspan='5' class=\"label_error\" align=\"right\">\n";
            $this->salida .= "                        TOTAL";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td  align=\"right\">\n";
            $this->salida .= "                         ".FormatoValor($vaclor_toctal);
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td  align=\"right\">\n";
            $this->salida .= "                         ";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td  align=\"right\">\n";
            $this->salida .= "                         ";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td  align=\"right\">\n";
            $this->salida .= "                         ";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
            $this->salida .= "                 </table>";
            $this->salida .="".$this->ObtenerPaginadoDocus($path,$num_reg,1,$_REQUEST['empresa_idx'],$_REQUEST['documento_id'],$_REQUEST['fecha1'],$_REQUEST['fecha2'],$_REQUEST['nombre_doc'],20,$pagina,$_REQUEST['bodega'],utf8_decode($_REQUEST['nom_bodega']));    
        
        }       
        $this->salida .= "                 <br>";
        $this->salida .= "            </div>\n";
        $this->salida .= "               <br>";
        $REVISARDOCS=ModuloGetURL('app','Inv_Movimientos_Admin','user','MenuBodegas',array('empresabus_id'=>$_REQUEST['empresa_idx'],'fecha1'=>$_REQUEST['fecha1'],'fecha2'=>$_REQUEST['fecha2'],'bodega'=>$_REQUEST['bodega'],'nom_bodega'=>utf8_decode($_REQUEST['nom_bodega'])));
        $this->salida .= " <form name=\"volver\" action=\"".$REVISARDOCS."\" method=\"post\">\n";
        $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "       <td align=\"center\" colspan='7'>\n";
        $this->salida .= "          <input type=\"submit\" name=\"VolverCris\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "       </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= " </form>\n";
        $this->salida.="<script language=\"javaScript\">
            function mOvr(src,clrOver)
            {
                src.style.background = clrOver;
            }
            function mOut(src,clrIn)
            {
                src.style.background = clrIn;
            }
        </script>";
        $this->salida .= ThemeCerrarTabla();
        return true;
      }

      /**
      *funcion pop up para imprimir
      * @return $salida.
      **/
      function RetornarImpresionDoc($direccion,$alt,$imagen,$empresa_id,$prefijo,$numero)
      {    
          global $VISTA;
          $imagen1 = "<sub><img src=\"".$imagen."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
          $salida1 ="<a title='".$alt."' href=javascript:Imprimir('$direccion','$empresa_id','$prefijo','$numero')>".$imagen1."</a>";
          return $salida1;
      }

      /**
      *funcion pop up para imprimir kardex
      * @return $salida.
      **/
      function RetornarImpresionDoc53($direccion,$alt,$imagen,$empresa_id,$prefijo,$numero)
      {    
          global $VISTA;
          //$imagen1 = "<sub><img src=\"".$imagen."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
          $salida1 ="<a title='".$alt."' href=javascript:Imprimir('$direccion','$empresa_id','$prefijo','$numero')>".$imagen."</a>";
          return $salida1;
      }
      /**
      *funcion pop up para imprimir documento de bodega
      * @return $salida.
      **/
      function RetornarImpresionDoc1($direccion,$alt,$imagen,$bodega_doc_id,$numero,$codigo_producto)
      {    
          global $VISTA;
          $salida1 ="<a title='".$alt."' href=javascript:ImprimirModeloAnterior('$direccion','$bodega_doc_id','$numero','$codigo_producto')>".$imagen."</a>";
          return $salida1;
      }
    /**
    * Metodo para obtener el paginador de proveedores
    *
    * @param string   $path (ruta de las imagenes del siis)
    * @param string   $slc (numero total de registro de la consulta realizada)
    * @param string   $op (posciuon del paginador arriba o abajo)
    * @param string   $path (ruta de las imagenes del siis)
    * @param integer  $tipo_de_busqueda (CRITERIO DE BUSQUEDA)
    * @param integer  $tipo_de_busqueda_aux (CRITERIO DE BUSQUEDA)
    * @param integer  $valor_de_busqueda
    * @param integer  $limite (cantidad de refistros a mostrar por pagina)
    * @param integer  $pagina (pagina de la consulta)
    
    * @access public
    */   
    function ObtenerPaginadoDocus($path,$slc,$op,$empresa_id,$documento_id,$fecha1,$fecha2,$nombre,$limite,$pagina,$bodega,$nom_bo)
    {
      
        $TotalRegistros = $slc;
        $TablaPaginado = "";

        if($limite == null)
        {
            $uid = UserGetUID();
            $LimitRow = intval(GetLimitBrowser());
        }
        else
        {
            $LimitRow = $limite;
        }
        if ($TotalRegistros > 0)
        {
            $columnas = 1;
            $NumeroPaginas = intval($TotalRegistros/$LimitRow);

            if($TotalRegistros%$LimitRow > 0)
            {
                $NumeroPaginas++;
            }

            $Inicio = $pagina;
            if($NumeroPaginas - $pagina < 9 )
            {
                $Inicio = $NumeroPaginas - 9;
            }
            elseif($pagina > 1)
            {
                $Inicio = $pagina - 1;
            }

            if($Inicio <= 0)
            {
                $Inicio = 1;
            }

            $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";
            $TablaPaginado .= "<tr>\n";
            if($NumeroPaginas > 1)
            {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P�inas:</td>\n";
                if($pagina > 1)
                {
                    $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                    $BODEGA=ModuloGetURL('app','Inv_Movimientos_Admin','user','ListarDocumentos' ,array('empresa_idx'=>$empresa_id,'documento_id'=>$documento_id,'fecha1'=>$fecha1,'fecha2'=>$fecha2,'nombre_doc'=>$nombre,'offset'=>1,'bodega'=>$bodega,'nom_bodega'=>utf8_decode($nom_bo)));
                    $TablaPaginado .= "     <a class=\"label_error\" href=\"".$BODEGA."\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                    $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                    $BODEGA=ModuloGetURL('app','Inv_Movimientos_Admin','user','ListarDocumentos' ,array('empresa_idx'=>$empresa_id,'documento_id'=>$documento_id,'fecha1'=>$fecha1,'fecha2'=>$fecha2,'nombre_doc'=>$nombre,'offset'=>($pagina-1),'bodega'=>$bodega,'nom_bodega'=>$nom_bo));
                    $TablaPaginado .= "     <a class=\"label_error\" href=\"".$BODEGA."\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                    $TablaPaginado .= "   </td>\n";
                    $columnas +=2;
                }
                $Fin = $NumeroPaginas + 1;
                if($NumeroPaginas > 10)
                {
                    $Fin = 10 + $Inicio;
                }
                for($i=$Inicio; $i< $Fin ; $i++)
                {
                    if ($i == $pagina )
                    {
                        $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>".$i."</b></td>\n";
                    }
                    else
                    {
                        $BODEGA=ModuloGetURL('app','Inv_Movimientos_Admin','user','ListarDocumentos' ,array('empresa_idx'=>$empresa_id,'documento_id'=>$documento_id,'fecha1'=>$fecha1,'fecha2'=>$fecha2,'nombre_doc'=>$nombre,'offset'=>$i,'bodega'=>$bodega,'nom_bodega'=>$nom_bo));
                        $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"".$BODEGA."\">".$i."</a></td>\n";
                    }
                    $columnas++;
                }
            }
            if($pagina <  $NumeroPaginas )
            {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";      //tercos('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','".$NumeroPaginas."')
                $BODEGA=ModuloGetURL('app','Inv_Movimientos_Admin','user','ListarDocumentos' ,array('empresa_idx'=>$empresa_id,'documento_id'=>$documento_id,'fecha1'=>$fecha1,'fecha2'=>$fecha2,'nombre_doc'=>$nombre,'offset'=>($pagina+1),'bodega'=>$bodega,'nom_bodega'=>$nom_bo));
                $TablaPaginado .= "     <a class=\"label_error\" href=\"".$BODEGA."\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $BODEGA=ModuloGetURL('app','Inv_Movimientos_Admin','user','ListarDocumentos' ,array('empresa_idx'=>$empresa_id,'documento_id'=>$documento_id,'fecha1'=>$fecha1,'fecha2'=>$fecha2,'nombre_doc'=>$nombre,'offset'=>$NumeroPaginas,'bodega'=>$bodega,'nom_bodega'=>$nom_bo));
                $TablaPaginado .= "     <a class=\"label_error\"  href=\"".$BODEGA."\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
            $aviso .= "     P�ina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
            $aviso .= "   </tr>\n";

            if($op == 2)
            {
                $TablaPaginado .= $aviso;
            }
            else
            {
                $TablaPaginado = $aviso.$TablaPaginado;
            }
        }

        $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
        $Tabla .= $TablaPaginado;
        $Tabla .= "</table>";
        return $Tabla;
    }
    /**
    * Funcion donde se crea la forma q hace el cierre de mes
    *
    * @return boolean
    */
    function FormaCierre()
    {
      $request = $_REQUEST;
      $op = ((!$request['opcion'])? 0:$request['opcion']) + 1; 
      $action['volver'] = ModuloGetURL('app','Inv_Movimientos_Admin','user','FormaCierre',array("opcion"=>$op));
      $action['cancelar'] = ModuloGetURL('app','Inv_Movimientos_Admin','user','Menu_Movimientos_Admin');
      $empresas = SessionGetVar("EMPRESAS");
      $empresa = SessionGetVar("EMPRESA");
            
      IncludeClass("CierreBodegas","","app","Inv_Movimientos_Admin");
      $crb = new CierreBodegas();
      switch($op)
      {
        case 1:
          $documento = ModuloGetVar('app','Inv_Movimientos_Admin','documento_costos_'.$empresas['empresa_id']);
          $rst = $crb->CostosXLapsos($empresa,$documento,UserGetUID());
          if(empty($rst[$empresa]))
          {
            $action['volver'] = $action['cancelar'];
            $this->salida .= $this->FormaMensajeModulo($action,"NO HAY BODEGAS PARA REALIZAR EL CIERRE");
          }
          else
            $this->salida .= $this->FormaMensajeBodega($action,$rst[$empresa]);
        break;
        case 2:
          $rst = $crb->CierreBodegasLapsos($empresa,UserGetUID());

          $this->salida .= $this->FormaMensajeBodega($action,$rst[$empresa]);
        break;
        case 3:
          $rst = $crb->CierreExistencias($empresa,UserGetUID());

          $this->salida .= $this->FormaMensajeBodega($action,$rst[$empresa]);
        break;           
        case 4:
          $rst = $crb->CierreExistenciasMovimientos($empresa,UserGetUID());

          $this->salida .= $this->FormaMensajeBodega($action,$rst[$empresa]);
        break;        
        case 5:
          $action['volver'] = ModuloGetURL('app','Inv_Movimientos_Admin','user','SelectCentroCosto');
          $rst = $crb->IncrementarLapso($empresa,UserGetUID());
          $mensaje = "EL CIERRE DE MES FUE HECHO SATISFACTORIAMENTE";
          if($rst === false) 
            $mensaje = $crb->ErrMsg();
            
          $this->salida .= $this->FormaMensajeModulo($action,$mensaje,$rst);
          return true;
        break;
      }
      return true;
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
		function FormaMensajeModulo($action,$mensaje,$imprimir)
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
      if($imprimir)
      {
        $empresa = SessionGetVar("EMPRESA");
        $html .= "	<tr>\n";
        $html .= "    <td align=\"center\">\n";
        $rpt = new GetReports();
        $html .= $rpt->GetJavaReport("app","Inv_Movimientos_Admin","DocumentosMovimientos",array('empresa_id'=>$empresa,"usuario"=>UserGetUID()),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $fnc = $rpt->GetJavaFunction();
        $html .= "        <a class=\"label_error\" href=\"javascript:".$fnc."\">\n";
        $html .= "          <img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\"> DOCUMENTOS\n";
        $html .= "        </a>\n";
        $html .= "      </td>\n";
        $html .= "	</tr>\n";
			}
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
    /**
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		*
		* @param array $action vector que continen los link de la aplicacion
    * @param array $datos Datos de las bodegas con su respectivo mensaje
    *
		* @return string
		*/
		function FormaMensajeBodega($action,$datos)
		{
			$html  = ThemeAbrirTabla('MENSAJE');
			$html .= "<table border=\"0\" width=\"70%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td colspan=\"2\">\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"formulacion_table_list\">\n";
			$html .= "		      <td width=\"30%\">BODEGA</td>\n";
			$html .= "		      <td width=\"70%\">MENSAJE</td>\n";
			$html .= "		    </tr>\n";
      
      foreach($datos as $key => $centro)
      {
        foreach($centro as $k1 => $bodega)
        {
          $est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
          $err = ($bodega['retorno'])? "normal_10AN":"label_error";
          
          $html .= "		    <tr class=\"".$est."\">\n";
          $html .= "		      <td >".$bodega['bodega_descripcion']."</td>\n";
          $html .= "		      <td class=\"".$err."\">".$bodega['mensaje']."</td>\n";
          $html .= "		    </tr>\n";
        }
      }
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
      $html .= "		<td align=\"center\"><br>\n";
      $html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
      $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
      $html .= "			</form>";
      $html .= "		</td>";
      $html .= "		<td align=\"center\"><br>\n";
      $html .= "			<form name=\"form\" action=\"".$action['cancelar']."\" method=\"post\">";
      $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Cancelar\">";
      $html .= "			</form>";
      $html .= "		</td>";			
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
}
?>