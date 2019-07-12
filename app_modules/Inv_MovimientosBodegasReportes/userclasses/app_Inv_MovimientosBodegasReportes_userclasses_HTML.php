<?php
    /**************************************************************************************
    * $Id: app_Inv_MovimientosBodegasReportes_userclasses_HTML.php,v 1.1 2007/07/17 22:24:14 jgomez Exp $
    *
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @package IPSOFT-SIIS-FI
    *
    * $Revision: 1.1 $
    *
    * @autor Jaime G�ez
    ***************************************************************************************/
     $VISTA = "HTML";
	   //$_ROOT = "../../../";

  IncludeClass("ClaseHTML");
   if (!IncludeClass('BodegasDocumentos'))
    {
      die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
    } 
    class app_Inv_MovimientosBodegasReportes_userclasses_HTML extends app_Inv_MovimientosBodegasReportes_user
    {
        function app_Inv_MovimientosBodegasReportes_userclasses_HTML(){ }

    /***********************************************************************************
    * Muestra el menu de los empresas y centros de utilidad
    *
    * @access public
    ***********************************************************************************/
     function SelectEmpresa()
     {

       $this->MostrarEmpresas();
       $this->CrearElementos();
       $titulo[0]='EMPRESA';
       $url[0]='app';//contenedor
       $url[1]='Inv_MovimientosBodegasReportes';//m�ulo
       $url[2]='user';//clase
       $url[3]='MenuBodegas';//m�odo
       $url[4]='Empresas';//indice del request
       $this->salida .= gui_theme_menu_acceso('SELECCIONE EMPRESA',$titulo,$this->TodasEmpresas,$url,ModuloGetURL('system','Menu'));
       return true;
     }
/**********************************************************************************
* Funci� principal del m�ulo
*
* @return boolean
***********************************************************************************/
    function main()
    {
      $this->SelectEmpresa();
      return true;
    }

    function MenuBodegas()
    {

      $this->CrearElementos();
      IncludeClass("ClaseHTML");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $path = SessionGetVar("rutaImagenes");
      $consulta= new MovBodegasReportSQL();
      $consulta1= new BodegasDocumentos();
      $this->IncludeJS('RemoteXajax/definirReportes.js', $contenedor='app', $modulo='Inv_MovimientosBodegasReportes');
      $file = 'app_modules/Inv_MovimientosBodegasReportes/RemoteXajax/definirReportes.php';
      $this->SetXajax(array(),$file);
      $this->salida .= ThemeAbrirTabla("DOCUMENTOS MOVIMIENTOS BODEGAS");
      $REVISARDOCS=ModuloGetURL('app','Inv_MovimientosBodegasReportes','user','MenuBodegas',array('empresabus_id'=>$_REQUEST['empresabus_id'],'fecha1'=>$_REQUEST['fecha1'],'fecha2'=>$_REQUEST['fecha2']));
      $this->salida .= "<form name=\"menu_docu\" action=\"".$REVISARDOCS."\" method=\"post\">\n";
      $this->salida .= "  <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "                   <tr class=\"modulo_table_list_title\">\n";
      $this->salida .= "                      <td COLSPAN='2' align=\"center\">\n";
      $this->salida .= "                         BUSCAR DOCUMENTOS";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                   </tr>\n";
      $this->salida .= "                   <tr>\n";
      $this->salida .= "                      <td class=\"modulo_table_list_title\" width='20%' align=\"center\">\n";
      $this->salida .= "                         FECHA INICIAL";
      $this->salida .= "                      </td>\n";
      if(empty($_REQUEST['fecha1']))
      {
        $fecha_inicial=date("01-m-Y");
      }
      else
      {
        $fecha_inicial=$_REQUEST['fecha1'];
      }
       
      $this->salida .= "                      <td class=\"modulo_list_claro\" width='15%' align=\"center\">\n";
      $this->salida .= "                         <input type=\"text\" class=\"input-text\" name=\"fecha1\" id=\"fecha1\" size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"".$fecha_inicial."\">\n";
      $this->salida .="                           <sub>".ReturnOpenCalendario("menu_docu","fecha1","-")."</sub>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                   </tr>\n";
      $this->salida .= "                   <tr>\n";
      $this->salida .= "                      <td class=\"modulo_table_list_title\" width='20%' align=\"center\">\n";
      $this->salida .= "                         FECHA FINAL";
      $this->salida .= "                      </td>\n";
      if(!empty($_REQUEST['fecha2']))
      {
        $fecha_final=$_REQUEST['fecha2'];
      }
      
      $this->salida .= "                      <td class=\"modulo_list_claro\" width='15%' align=\"center\">\n";
      $this->salida .= "                         <input type=\"text\" class=\"input-text\" name=\"fecha2\" id=\"fecha2\"  size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"".$fecha_final."\">\n";
      $this->salida .= "                           <sub>".ReturnOpenCalendario("menu_docu","fecha2","-")."</sub>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                   </tr>\n";
      $this->salida .= "                   <tr>\n";
      $this->salida .= "                      <td class=\"modulo_list_claro\" colspan='2' align=\"center\">\n";
      $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" value=\"BUSCAR\">\n";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                   </tr>\n";
      $this->salida .= "  </table>\n";
      $this->salida .= "</form>\n";
      $this->salida .= "<br>\n";
      
      $this->salida .= "<form name=\"menu_docu1\" action=\"javascript:LlamarDocu(1);\" method=\"post\">\n";
      if(!empty($_REQUEST['empresabus_id']))
      {
        $empresaBus_id=$_REQUEST['empresabus_id'];
      }
      else
      {
        $empresaBus_id=SessionGetVar("EMPRESA");
      }
       
      if(!empty($_REQUEST['fecha1']))
      {
        $fecha_inicial=$_REQUEST['fecha1'];
        $partes=explode("-", $fecha_inicial);
        $fecha_inicial=$partes[2]."-".$partes[1]."-".$partes[0]; 
      }
      else
      {
        $fecha_inicial=date("Y-m-01");
        $partes=explode("-", $fecha_inicial);
        $fecha_inicial=$partes[2]."-".$partes[1]."-".$partes[0]; 
      }
      
      
      if(!empty($_REQUEST['fecha2']))
      {
        $fecha_final=$_REQUEST['fecha2'];
        $partes=explode("-", $fecha_final);
        $fecha_final=$partes[2]."-".$partes[1]."-".$partes[0]; 
      }
      else
      {
        $fecha_final=null;
      }
      
      
      
      $vector=$consulta1->GetDocumentosEmpresa($empresaBus_id, $fecha_inicial, $fecha_final);
      
      //VAR_DUMP($vector);
      if(!empty($vector))
      {
        $this->salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                   <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                      <td width='5%' align=\"center\">\n";
        $this->salida .= "                       <a title='CENTRO DE UTILIDAD'>";
        $this->salida .= "                         CU";
        $this->salida .= "                      </a>\n";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      <td width='25%' align=\"center\">\n";
        $this->salida .= "                         BODEGA";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      <td width='3%' align=\"center\">\n";
        $this->salida .= "                         TIPO";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      <td width='7%' align=\"center\">\n";
        $this->salida .= "                         PREFIJO";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      <td width='45%' align=\"center\">\n";
        $this->salida .= "                         DESCRIPCION";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      <td width='10%' align=\"center\">\n";
        $this->salida .= "                         CANTIDAD";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      <td width='5%' align=\"center\" colspan=\"2\">\n";
        $this->salida .= "                         ACCIONES";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                   </tr>\n";

        $rpt  = new GetReports();

        for($i=0;$i<count($vector);$i++)
       {                                                                                 
          $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegasReportes','user','ListarDocumentos' ,array('empresa_idx'=>$empresaBus_id,'documento_id'=>$vector[$i]['documento_id'],'fecha1'=>$fecha_inicial,'fecha2'=>$fecha_final, 'nombre_doc'=>$vector[$i]['descripcion']));
          $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                      <td class=\"normal_10AN\" align=\"center\">\n";
          $this->salida .= "                        ".$vector[$i]['centro_utilidad'];
          $this->salida .= "                      </td>\n";
          $this->salida .= "                      <td class=\"normal_10AN\" align=\"left\">\n";
          $this->salida .= "                       <a title='".$vector[$i]['bodega']."' class=\"label_error\">";
          $this->salida .= "                        ".$vector[$i]['nom_bodega'];
          $this->salida .= "                      </a>\n";
          $this->salida .= "                      </td>\n";
          $this->salida .= "                      <td  class=\"normal_10AN\" align=\"center\">\n";
          $this->salida .= "                       <a title='".$vector[$i]['tipo_clase_documento']."' class=\"label_error\">";
          $this->salida .= "                         ".$vector[$i]['tipo_movimiento'];
          $this->salida .= "                       </a>\n";
          $this->salida .= "                      </td>\n";
          $this->salida .= "                      <td class=\"normal_10AN\" align=\"center\">\n";
          $this->salida .= "                        ".$vector[$i]['prefijo'];
          $this->salida .= "                      </td>\n";
          $this->salida .= "                      <td class=\"normal_10AN\" align=\"left\">\n";
          $this->salida .= "                       <a title='".$vector[$i]['documento_id']."'>";
          $this->salida .= "                        ".$vector[$i]['descripcion'];
          $this->salida .= "                       </a>\n";
          $this->salida .= "                      </td>\n";
          $this->salida .= "                      <td class=\"normal_10AN\" align=\"right\">\n";
          $this->salida .= "                        ".$vector[$i]['numero_documentos'];
          $this->salida .= "                      </td>\n";

        $dtl['empresa_id'] = SessionGetVar("EMPRESA");
        $dtl['usuario_id'] = UserGetUID();
        $dtl['documento_id'] = $vector[$i]['documento_id'];
        $dtl['fecha_inicio'] = $fecha_inicial;
        $dtl['fecha_fin'] =  $fecha_final;
        $dtl['nombre_doc'] =  $vector[$i]['descripcion'];

        $this->salida .= $rpt->GetJavaReport('app','Inv_MovimientosBodegasReportes','documentos',$dtl,
                              array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $fnc  = $rpt->GetJavaFunction();
        $this->salida .= "                      <td class=\"normal_10AN\" align=\"center\">\n";
        $this->salida .= "                          <a  title=\"LISTAR DOCUMENTOS\" class=\"label_error\" href=\"".$BODEGA."\">\n";
        $this->salida .= "                          <sub><img src=\"".$path."/images/mvto_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
        $this->salida .= "                         </a>\n";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                      <td class=\"normal_10AN\" align=\"center\">\n";
        $this->salida .= "	                      <a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
        $this->salida .= "	                        <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
        $this->salida .= "                        </a>\n";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                    </tr>";
      }

      }
      $this->salida .= "                 </table>";
      $this->salida .= "             </form>";
	
      
     	$Exit = ModuloGetURL('app','Inv_MovimientosBodegasReportes','user','SelectEmpresa');
      
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











///////////////////////////////////////////////////////////////////////////////////////
/**********************************************************************************************
*
*listar documentos segun el documento id
*
*
********************************************************************************************/
//////////////////////////////////////////////////////////////////////////////////////




function ListarDocumentos()
 {
    global $VISTA;
    $this->CrearElementos();
    IncludeClass("ClaseHTML");
    $path = SessionGetVar("rutaImagenes");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserDrag");
    $this->IncludeJS("CrossBrowserEvent");
    $consulta= new MovBodegasReportSQL();
    $consulta1= new BodegasDocumentos();
    $this->IncludeJS('RemoteXajax/definirReportes.js', $contenedor='app', $modulo='Inv_MovimientosBodegasReportes');
    $file = 'app_modules/Inv_MovimientosBodegasReportes/RemoteXajax/definirReportes.php';
    $this->SetXajax(array("ObtenerDatosDocumento"),$file);
    $limit=20;
    $this->salida .= ThemeAbrirTabla("DOCUMENTOS ID :".$_REQUEST['documento_id']."-".$_REQUEST['nombre_doc']."");
    $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegasReportes','user','ListarDocumentos' ,array('empresa_idx'=>$empresaBus_id,'documento_id'=>$vector[$i]['documento_id'],'fecha1'=>$fecha_inicial,'fecha2'=>$fecha_final, 'nombre_doc'=>$vector[$i]['descripcion']));
    if(empty($_REQUEST['offset']) || $_REQUEST['offset']==1)
    {
      $offset=0;
      $pagina=1;
      
    }
    else
    {
        //$offset=$_REQUEST['offset']; 
        $offset=($_REQUEST['offset']-1)*$limit;
        $pagina=$_REQUEST['offset'];
    }
    //$BODEGA=ModuloGetURL('app','Inv_MovimientosBodegasReportes','user','ListarDocumentos' ,array('empresa_idx'=>$empresaBus_id,'documento_id'=>$vector[$i]['documento_id'],'fecha1'=>$fecha_inicial,'fecha2'=>$fecha_final, 'nombre_doc'=>$vector[$i]['descripcion']));
           
    $vector=$consulta1->GetTipoDocumento($_REQUEST['empresa_idx'], $_REQUEST['documento_id'], $_REQUEST['fecha1'], $_REQUEST['fecha2'], $count=null, $limit, $offset);
    $num_reg=$consulta1->GetTipoDocumento($_REQUEST['empresa_idx'], $_REQUEST['documento_id'], $_REQUEST['fecha1'], $_REQUEST['fecha2'], $count=true, $limit=null, $offset=null);
    
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
    
    /********************************************************************************
    *PARA MOSTRAR EL DOCUMENTO
    **********************************************************************************/
    $this->salida.="<div id='ContenedorDet' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='tituloDet' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarDet' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorDet');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorDet' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoDet' class='d2Content'>\n";
    $this->salida .= "    </div>\n";
    $this->salida.="</div>"; 
    
    $dtl['empresa_id'] = $_REQUEST['empresa_idx'];
    $dtl['usuario_id'] = UserGetUID();
    $dtl['documento_id'] = $_REQUEST['documento_id'];
    $dtl['fecha_inicio'] = $_REQUEST['fecha1'];
    $dtl['fecha_fin'] =  $_REQUEST['fecha2'];
    $dtl['nombre_doc'] =  $_REQUEST['nombre_doc'];
    
    $rpt  = new GetReports();
    $this->salida .= $rpt->GetJavaReport('app','Inv_MovimientosBodegasReportes','documentos',$dtl,
                          array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
    $fnc  = $rpt->GetJavaFunction();
    $this->salida .= "<center>\n";
    $this->salida .= "	<a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
    $this->salida .= "	  <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">IMPRIMIR REPORTE\n";
    $this->salida .= "  </a>\n";
    $this->salida .= "</center>\n";
    $this->salida .= "<br>\n";
    
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
         $this->salida .="".$this->ObtenerPaginadoDocus($path,$num_reg,1,$_REQUEST['empresa_idx'],$_REQUEST['documento_id'],$_REQUEST['fecha1'],$_REQUEST['fecha2'],$_REQUEST['nombre_doc'],20,$pagina);    
     
     }
         $this->salida .= "                 <br>";
         
   
    $this->salida .= "            </div>\n";
    $this->salida .= "               <br>";
    
    $REVISARDOCS=ModuloGetURL('app','Inv_MovimientosBodegasReportes','user','MenuBodegas',array('empresabus_id'=>$_REQUEST['empresabus_id'],'fecha1'=>$_REQUEST['fecha1'],'fecha2'=>$_REQUEST['fecha2']));
    
    $this->salida .= " <form name=\"volver\" action=\"".$REVISARDOCS."\" method=\"post\">\n";
    $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
    $this->salida .= "    <tr>\n";
    $this->salida .= "       <td align=\"center\" colspan='7'>\n";
    $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
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

/********************************
*pop up para imprimir
***********************************/
function RetornarImpresionDoc($direccion,$alt,$imagen,$empresa_id,$prefijo,$numero)
 {    
    global $VISTA;
    $imagen1 = "<sub><img src=\"".$imagen."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
    $salida1 ="<a title='".$alt."' href=javascript:Imprimir('$direccion','$empresa_id','$prefijo','$numero')>".$imagen1."</a>";
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
   function ObtenerPaginadoDocus($path,$slc,$op,$empresa_id,$documento_id,$fecha1,$fecha2,$nombre,$limite,$pagina)
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
            
            $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegasReportes','user','ListarDocumentos' ,array('empresa_idx'=>$empresa_id,'documento_id'=>$documento_id,'fecha1'=>$fecha1,'fecha2'=>$fecha2,'nombre_doc'=>$nombre,'offset'=>1));
            $TablaPaginado .= "     <a class=\"label_error\" href=\"".$BODEGA."\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";                                                             
            $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegasReportes','user','ListarDocumentos' ,array('empresa_idx'=>$empresa_id,'documento_id'=>$documento_id,'fecha1'=>$fecha1,'fecha2'=>$fecha2,'nombre_doc'=>$nombre,'offset'=>($pagina-1)));
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
              $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegasReportes','user','ListarDocumentos' ,array('empresa_idx'=>$empresa_id,'documento_id'=>$documento_id,'fecha1'=>$fecha1,'fecha2'=>$fecha2,'nombre_doc'=>$nombre,'offset'=>$i));
              
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"".$BODEGA."\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";      //tercos('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','".$NumeroPaginas."')
          $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegasReportes','user','ListarDocumentos' ,array('empresa_idx'=>$empresa_id,'documento_id'=>$documento_id,'fecha1'=>$fecha1,'fecha2'=>$fecha2,'nombre_doc'=>$nombre,'offset'=>($pagina+1)));
          $TablaPaginado .= "     <a class=\"label_error\" href=\"".$BODEGA."\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegasReportes','user','ListarDocumentos' ,array('empresa_idx'=>$empresa_id,'documento_id'=>$documento_id,'fecha1'=>$fecha1,'fecha2'=>$fecha2,'nombre_doc'=>$nombre,'offset'=>$NumeroPaginas));
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

}
?>