<?php
    /**************************************************************************************
    * $Id: app_Inv_MovimientosBodegas_userclasses_HTML.php,v 1.1 2009/07/17 19:08:23 johanna Exp $
    *
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @package IPSOFT-SIIS-FI
    *
    * $Revision: 1.1 $
    *
    * @autor Jaime G�ez
    ***************************************************************************************/

    IncludeClass("ClaseHTML");
    class app_Inv_MovimientosBodegas_userclasses_HTML extends app_Inv_MovimientosBodegas_user
    {
        function app_Inv_MovimientosBodegas_userclasses_HTML(){ }

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
       $url[1]='Inv_MovimientosBodegas';//m�ulo
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
      $consulta= new MovBodegasSQL();
      $this->IncludeJS('RemoteXajax/definirBodegas.js', $contenedor='app', $modulo='Inv_MovimientosBodegas');
      $file = 'app_modules/Inv_MovimientosBodegas/RemoteXajax/definirBodegas.php';
      $this->SetXajax(array(),$file);
      $this->salida .= ThemeAbrirTabla("INVENTARIO MOVIMIENTOS BODEGAS");
      $REVISARDOCS=ModuloGetURL('app','Inv_MovimientosBodegas','user','ValidacionTomaFisicaLogueo');
      $this->salida .= "            <form name=\"menu_docu\" action=\"javascript:LlamarDocu(1);\" method=\"post\">\n";
      $a=UserGetUID();

      $vector=$consulta->ColocarBodegas($a,SessionGetVar("EMPRESA"));
       //VAR_DUMP($vector);
      if(!empty($vector))
      {
        $this->salida .= "                 <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                   <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                      <td align=\"center\">\n";
        $this->salida .= "                         BODEGAS DISPONIBLES";
        $this->salida .= "                      </td>\n";
       for($i=0;$i<count($vector);$i++)
       {                                                                                 //empresa_id centro_utilidad bodega  usuario_id  nom_bodega
          $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('nom_bodegax'=>$vector[$i]['nom_bodega'],'utility'=>$vector[$i]['centro_utilidad'],'bodegax'=>$vector[$i]['bodega']));

          $this->salida .= "                   </tr>\n";
          $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
          $this->salida .= "                          <a  title=\"".$vector[$i]['nom_bodega']."\" class=\"label_error\" href=\"".$BODEGA."\">".$vector[$i]['nom_bodega']."</a>\n";
          $this->salida .= "                       </td>";
          $this->salida .= "                    </tr>";
       }

      }
      $this->salida .= "                 </table>";
      $this->salida .= "             </form>";
	
      if($_SESSION['MOVIMIENTO_BODEGA']['RETORNO']['existencia'] == true)
      {
      	$contenedor = $_SESSION['MOVIMIENTO_BODEGA']['RETORNO']['contenedor'];
          $modulo     = $_SESSION['MOVIMIENTO_BODEGA']['RETORNO']['modulo'];
          $tipo       = $_SESSION['MOVIMIENTO_BODEGA']['RETORNO']['tipo'];
          $metodo     = $_SESSION['MOVIMIENTO_BODEGA']['RETORNO']['metodo'];
          $Exit = ModuloGetURL($contenedor,$modulo,$tipo,$metodo);
      }else
      {
     	$Exit = ModuloGetURL('app','Inv_MovimientosBodegas','user','SelectEmpresa');
      }
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

/*****************************************************************
*Documentos de la bodega
****************************************************************/

  function DocumentosBodega($arregloXjx)
  {
    $this->CrearElementos();
    IncludeClass("ClaseHTML");
    $path = SessionGetVar("rutaImagenes");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserDrag");
    $this->IncludeJS("CrossBrowserEvent");
    $consulta= new MovBodegasSQL();
    $this->IncludeJS('RemoteXajax/definirBodegas.js', $contenedor='app', $modulo='Inv_MovimientosBodegas');
    $file = 'app_modules/Inv_MovimientosBodegas/RemoteXajax/definirBodegas.php';
    $arregloXjx[] = "BorrarTmpAfirmativo";
    $arregloXjx[] = "BorrarTMPX";
    $arregloXjx[] = "SeleccionarDocumentos";
    
    $this->SetXajax($arregloXjx,$file,"ISO-8859-1");

    $this->salida .= ThemeAbrirTabla("DOCUMENTOS DE LA BODEGA :".$_REQUEST['bodegax']." - ".$_REQUEST['nom_bodegax']."");
    $vector=$consulta->PonerDocumentosBodega(UserGetUID(),SessionGetVar("EMPRESA"),$_REQUEST['utility'],$_REQUEST['bodegax']);
    //echo print_r($vector,true);
    $accion1=ModuloGetURL('app','InvTomaFisica','user','CapturaTomaFisica');
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
    $salida.= $javaC1;
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
    </script>";
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
    $this->salida .="    <div id='refresh'>";
     if(!EMPTY($vector))
     {
         //tipo_movimiento  bodegas_doc_id  tipo_clase_documento  prefijo descripcion
         $this->salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
         $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $this->salida .= "                       <td width=\"40%\" align=\"center\">\n";
         $this->salida .= "                          TIPO CLASE DE DOCUMENTO";
         $this->salida .= "                       </td>\n";
         $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
         $this->salida .= "                         TIPO";
         $this->salida .= "                       </td>\n";
         $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
         $this->salida .= "                          PREFIJO";
         $this->salida .= "                       </td>\n";
         $this->salida .= "                       <td width=\"40%\" align=\"center\">\n";
         $this->salida .= "                          DESCRIPCION";
         $this->salida .= "                       </td>\n";
         $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
         $this->salida .= "                          ACCIONES";
         $this->salida .= "                       </td>\n";
         $this->salida .= "                    </tr>\n";

         foreach($vector as $valor=>$valor1)
         {
           $this->salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
           $this->salida .= "                      <td rowspan='".sizeof($valor1)."' align=\"left\" class=\"label_error\">\n";
           $this->salida .= "                        ".strtoupper ($valor)."";
           $this->salida .= "                      </td>\n";
           $i=0;
           foreach($valor1 as $valor=>$valor2)
            {  if($i>0)
                {
                   $this->salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                }
                  $this->salida .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                  $this->salida .= "                       ".$valor2['tipo_doc_bodega_id'];
                  $this->salida .= "                       </td>\n";
                  $this->salida .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                  $this->salida .= "                       ".$valor2['prefijo'];
                  $this->salida .= "                       </td>\n";
                  //$this->salida .= "<pre>".print_r($valor2,true)."</pre>";
                  $this->salida .= "                      <td align=\"left\">\n";
                  $this->salida .= "                       ".$valor2['descripcion'];
                  $this->salida .= "                      </td>\n";
                  $this->salida .= "                       <td  align=\"center\">\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
                  
                  $DATOS['bodega']=$_REQUEST['bodegax'];
                  $DATOS['nom_bodega']=$_REQUEST['nom_bodegax'];
                  $DATOS['utility']=$_REQUEST['utility'];
                  //$DATOS['departamento']=$_REQUEST['departamento'];
                  $DATOS['accion']='NUEVO_TMP';
                  $DATOS['bodegas_doc_id']=$valor2['bodegas_doc_id'];
                  $DATOS['tipo_doc_bodega_id']=$valor2['tipo_doc_bodega_id'];
                  $CONTEO=ModuloGetURL('app','Inv_MovimientosBodegas','user','DirectorDocumentos',array('DATOS'=>$DATOS));
                  $this->salida .= "                         <a title='BODEGA DOCUMENTO".$valor2['bodegas_doc_id']."-".$valor2['tipo_doc_bodega_id']."' href=\"".$CONTEO."\">";
                  $this->salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                  $this->salida .= "                         </a>\n";
                  $this->salida .= "                       </td>\n";
                  $this->salida .= "                    </tr>\n";
                  $i=$i+1;
             }
         }
         $this->salida .= "                 </table>";
     }
         $this->salida .= "                 <br>";
    $actionN['paginador'] = ModuloGetURL("app","Inv_MovimientosBodegas","user","DocumentosBodega",
                         array("numero_doc"=>$_REQUEST['numero_doc'],"nom_bodegax"=>$_REQUEST['nom_bodegax'],"utility"=>$_REQUEST['utility'],"bodegax"=>$_REQUEST['bodegax'],
                               "clas_doc"=>$_REQUEST['clas_doc'],"tipos_doc"=>$_REQUEST['tipos_doc']));
    $actionN['buscar'] = ModuloGetURL("app","Inv_MovimientosBodegas","user","DocumentosBodega",array("nom_bodegax"=>$_REQUEST['nom_bodegax'],"utility"=>$_REQUEST['utility'],"bodegax"=>$_REQUEST['bodegax']));
    $clases_doc = $consulta->ObtenerClasesDocumentos(SessionGetVar("EMPRESA"), $_REQUEST['utility'], $_REQUEST['bodegax'], UserGetUID());
    
    $_REQUEST['empresa'] = SessionGetVar("EMPRESA");
    $_REQUEST['centro_utilidad'] = $_REQUEST['utility'];
    $_REQUEST['bodega'] = $_REQUEST['bodegax'];
    
    $this->salida .= "<form name=\"buscador\" id=\"buscador\" action=\"".$actionN['buscar']."\" method=\"post\">\n";
    $this->salida .= "  <input type=\"hidden\" name=\"empresa\" value=\"".SessionGetVar("EMPRESA")."\">\n";
    $this->salida .= "  <input type=\"hidden\" name=\"centro_utilidad\" value=\"".$_REQUEST['utility']."\">\n";
    $this->salida .= "  <input type=\"hidden\" name=\"bodega\" value=\"".$_REQUEST['bodegax']."\">\n";
    $this->salida .= "  <input type=\"hidden\" name=\"doc_seleccionado\" value=\"".$_REQUEST['tipos_doc']."\">\n";
    $this->salida .= "  <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
    $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
    $this->salida .= "      <td colspan=\"2\">BUSCADOR DE DOCUMENTOS TEMPORALES</td>\n";
    $this->salida .= "    </tr>\n";
    $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
    $this->salida .= "      <td width=\"30%\" align=\"left\">NUMERO DOCUMENTO</td>\n";
    $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
    $this->salida .= "        <input type=\"text\" class=\"input-text\" name=\"numero_doc\" value=\"".$_REQUEST['numero_doc']."\" style=\"width:50%\">\n";
    $this->salida .= "      </td>\n";
    $this->salida .= "    </tr>\n";
    $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
    $this->salida .= "      <td align=\"left\" >CLASES DE DOCUMENTO</td>\n";
    $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"> \n";
    $this->salida .= "        <select name=\"clas_doc\" class=\"select\" onChange=\"xajax_SeleccionarDocumentos(xajax.getFormValues('buscador'))\">";
    $this->salida .= "          <option value=\"-1\" selected>--</option> \n";
    
    foreach($clases_doc as $valor)
      $this->salida .= "          <option value=\"".$valor['tipo_movimiento']."\" ".(($_REQUEST['clas_doc'] == $valor['tipo_movimiento'])? "selected":"" ).">".$valor['tipo_movimiento']."</option> \n";
    
    $this->salida .= "        </select>\n";
    $this->salida .= "      </td>\n";
    $this->salida .= "    </tr>\n";
    $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
    $this->salida .= "      <td align=\"left\">\n";
    $this->salida .= "        TIPOS DE DOCUMENTO";
    $this->salida .= "      </td>\n";
    $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\" > \n";
    $this->salida .= "        <select name=\"tipos_doc\" id=\"tipos_doc\" class=\"select\" >";
    $this->salida .= "          <option value=\"-1\" >---SELECCIONAR</option> \n";
    $this->salida .= "        </select>\n";
    $this->salida .= "     </td>\n";
    $this->salida .= "    </tr>\n";
    $this->salida .= "  </table>\n";
    $this->salida .= "  <table width=\"50%\" align=\"center\">\n";
    $this->salida .= "    <tr>\n";
    $this->salida .= "      <td align=\"center\">\n";
    $this->salida .= "        <input type=\"submit\" name=\"buscar\" value=\"Buscar\" class=\"input-submit\">\n";
    $this->salida .= "      </td>\n";
    $this->salida .= "    </tr>\n";
    $this->salida .= "  </table>\n";
    $this->salida .= "</form>\n";
    
    //$vectorTMP = $consulta->ObtenerDocsTmpUsuario(SessionGetVar("EMPRESA"),$_REQUEST['utility'],$_REQUEST['bodegax'],UserGetUID());
    $vectorTMP = $consulta->ObtenerDocumetosTemporales($_REQUEST);
       
    if(!EMPTY($vectorTMP))
    {
      $this->salida .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "  <tr class=\"modulo_table_list_title\">\n";
      $this->salida .= "    <td width=\"5%\" align=\"center\">\n";
      $this->salida .= "      <a title='CLASE DE DOCUMENTO'>C</a>";
      $this->salida .= "    </td>\n";
      $this->salida .= "    <td width=\"10%\" align=\"center\">\n";
      $this->salida .= "      TIPO DOC";
      $this->salida .= "    </td>\n";      
      $this->salida .= "    <td width=\"10%\" align=\"center\">\n";
      $this->salida .= "      No DOC";
      $this->salida .= "    </td>\n";
      $this->salida .= "    <td width=\"40%\" align=\"center\">\n";
      $this->salida .= "      DESCRIPCION";
      $this->salida .= "    </td>\n";      
      $this->salida .= "    <td width=\"20%\" align=\"center\">\n";
      $this->salida .= "      USUARIO";
      $this->salida .= "    </td>\n";
      $this->salida .= "    <td width=\"10%\" align=\"center\">\n";
      $this->salida .= "      FECHA";
      $this->salida .= "    </td>\n";
      $this->salida .= "    <td colspan='2' width=\"5%\" align=\"center\">\n";
      $this->salida .= "      ACCIONES";
      $this->salida .= "    </td>\n";
      $this->salida .= " </tr>\n";
  
      $usuario = UserGetUID();
      foreach($vectorTMP as $valor=>$valorClase)
      {
                 $tr=$valorClase['doc_tmp_id'];
                 $this->salida .= "   <tr id='".$tr."' class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                 $this->salida .= "     <td class=\"normal_10AN\" align=\"left\">\n";
                 $this->salida .= "       ".$valorClase['tipo_movimiento'];
                 $this->salida .= "     </td>\n";                 

                 $this->salida .= "     <td align=\"left\">\n";
                 $this->salida .= "       <a title='".$valorClase['tipo_clase_documento']."'>";
                 $this->salida .= "         ".$valorClase['tipo_doc_bodega_id'];
                 $this->salida .= "       </a>";
                 $this->salida .= "     </td>\n";
                 $this->salida .= "     <td class=\"normal_10AN\" align=\"left\">\n";
                 $this->salida .= "       ".$valorClase['doc_tmp_id'];
                 $this->salida .= "     </td>\n";                 
                 $this->salida .= "     <td class=\"normal_10AN\" align=\"left\">\n";
                 $this->salida .= "       ".$valorClase['descripcion'];
                 $this->salida .= "     </td>\n";                 
                 $this->salida .= "     <td class=\"normal_10AN\" align=\"left\">\n";
                 $this->salida .= "       ".$valorClase['nombre'];
                 $this->salida .= "     </td>\n";
                 $this->salida .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                 $this->salida .= "                       ".substr($valorClase['fecha_registro'],0,10);
                 $this->salida .= "                       </td>\n";
                 $this->salida .= "                       <td  align=\"center\">\n";
                 $DATOS['bodegax']=$_REQUEST['bodegax'];
                 $DATOS['nom_bodegax']=$_REQUEST['nom_bodegax'];
                 $DATOS['utility']=$_REQUEST['utility'];
                 //$DATOS['departamento']=$_REQUEST['departamento'];
                 $DATOS['accion']='EDITAR';
                 $DATOS['doc_tmp_id']=$valorClase['doc_tmp_id'];
                 $DATOS['bodegas_doc_id']=$valorClase['bodegas_doc_id'];
                 $DATOS['tipo_doc_bodega_id']=$valorClase['tipo_doc_bodega_id'];
                 //VAR_DUMP($DATOS);
                 $CONTEO=ModuloGetURL('app','Inv_MovimientosBodegas','user','DirectorDocumentos',array('DATOS'=>$DATOS));
                if($usuario == $valorClase['usuario_id'])
                {
                  $this->salida .= "                         <a title='EDITAR DOCUMENTO ".$valorClase['bodegas_doc_id']."-".$valorClase['tipo_doc_bodega_id']."' href=\"".$CONTEO."\">";
                  $this->salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                  $this->salida .= "                         </a>\n";
                }
                 $this->salida .= "                       </td>\n";
                 $this->salida .= "                       <td  align=\"center\">\n";
                if($usuario == $valorClase['usuario_id'])
                {
                 $jaxx = "javascript:MostrarCapa('ContenedorB3');BorrarTMPX('".$tr."','".$valorClase['doc_tmp_id']."','".$valorClase['bodegas_doc_id']."','".$valorClase['tipo_doc_bodega_id']."','ContenidoB3');IniciarB3('ELIMINAR REGISTRO');";
                 $this->salida .= "                        <a title='ELIMINAR REGISTRO' href=\"".$jaxx."\">\n";
                 $this->salida .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                 $this->salida .= "                         </a>\n";
                }
                 $this->salida .= "                       </td>\n";
                 $this->salida .= "                    </tr>\n";

          
      }
      $this->salida .= "    </table>";
      $chtml = AutoCarga::factory('ClaseHTML');
      $this->salida .= "		".$chtml->ObtenerPaginado($consulta->conteo,$consulta->paginaActual,$actionN['paginador']);
    }
    
    $this->salida .= "            </div>\n";
    $this->salida .= "               <br>";
    $this->salida .= "  <table align=\"center\" width=\"80%\">\n";
    $this->salida .= "    <tr>\n";
    $this->salida .= "       <td align=\"LEFT\" colspan='7'>\n";
    $DOCSBODEGA = ModuloGetURL('app','Inv_MovimientosBodegas','user','BuscadorDeDocumentos',array('utility'=>$_REQUEST['utility'],'bodegax'=>$_REQUEST['bodegax'],'nom_bodegax'=>$_REQUEST['nom_bodegax']));
    $this->salida .= " <a title='BUSCAR DOCUMENTOS DE BODEGA' href=\"".$DOCSBODEGA."\">";
    $this->salida .= "   <sub><img src=\"".$path."/images/pconsultar.png\" border=\"0\" width=\"17\" height=\"17\"> Buscar Documentos de Bodega</sub>\n";
    $this->salida .= " </a>\n";
    $this->salida .= "       </td>\n";
    $this->salida .= "    </tr>\n";
    $this->salida .= "  </table>\n";
    $this->salida .= "<br>";
    $MENUMOV = ModuloGetURL('app','Inv_MovimientosBodegas','user','MenuBodegas');
    $this->salida .= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
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
    if($_REQUEST['clas_doc'] != '-1')
    {
      $this->salida .= "<script>\n";
      $this->salida .= " xajax_SeleccionarDocumentos(xajax.getFormValues('buscador'));\n";
      $this->salida .= "</script>\n";
    }
    $this->salida .= ThemeCerrarTabla();
    // $this->salida.="<pre>".print_r($DATOS,true)."</pre>";
     //$this->salida.="<pre>".print_r($_REQUEST,true)."</pre>";
    return true;
 }
    /**
    * Funcion de control, para la visualizacion de los documentos
    *
    * @return boolean
    */
    function DirectorDocumentos($DATOS=ARRAY())
    {
      if(empty($DATOS))
        $DATOS=$_REQUEST['DATOS'];
      
      if($DATOS['VOLVER'])
        $this->DocumentosBodega();
        
      $path = SessionGetVar("rutaImagenes");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");

      $klase = $DATOS['tipo_doc_bodega_id'];

      if(!is_dir("app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/$klase"))
        $klase = 'DocBodegaGeneral';

      $this->IncludeJS('Doc_Mov_Bodegas/'.$klase.'/RemoteXajax/definirBodegas_'.$klase.'.js', $contenedor='app', $modulo='Inv_MovimientosBodegas');
      $DATOS['CTL'] = ModuloGetURL('app','Inv_MovimientosBodegas','user','DirectorDocumentos');

      $objeto1 = AutoCarga::factory("doc_Bodegas_".$klase."_HTML","Doc_Mov_Bodegas/".$klase."","app","Inv_MovimientosBodegas");
           
      $this->salida .= $objeto1->FormaDocumento($DATOS);
      
      return true;
    }

/**********************************************************************************
* buscador de documentos hecho
***********************************************************************************/

  function BuscadorDeDocumentos()
  {
    IncludeClass("ClaseHTML");
    $path = SessionGetVar("rutaImagenes");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserDrag");
    $this->IncludeJS("CrossBrowserEvent");
    $this->salida .= ThemeAbrirTabla("DOCUMENTOS DE LA BODEGA :".$_REQUEST['bodegax']." - ".$_REQUEST['nom_bodegax']."");
    $empresa_id=SessionGetVar("EMPRESA");
    $centro_utilidad=$_REQUEST['utility'];
    $bodega=$_REQUEST['bodegax'];
    
    $consulta = new MovBodegasSQL();
    $this->IncludeJS('RemoteXajax/definirBodegas.js', $contenedor='app', $modulo='Inv_MovimientosBodegas');
    $file = 'app_modules/Inv_MovimientosBodegas/RemoteXajax/definirBodegas.php';
    $this->SetXajax(array("ImprimirPDF","ObtenerDatosDocumento","ObtenerDocumentosBodegaFinal","ObtenerListaTiposDocumentos"),$file,"ISO-8859-1");
    $action['documento'] = ModuloGetURL('app','Inv_MovimientosBodegas','user','FormaDocumentoAdjunto');

    $javaC1 .= "<script>\n";
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
    $this->salida .= "<script>\n";
    $this->salida .= "  function ImprimirPDF(empresa,prefijo,numero,documento,tipo)\n";
    $this->salida .= "  {\n";
    $this->salida .= "    xajax_ImprimirPDF(empresa,prefijo,numero,documento,tipo)\n";
    $this->salida .= "  }\n";   
    $this->salida .= "  function mOvr(src,clrOver)\n";
    $this->salida .= "  {\n";
    $this->salida .= "    src.style.background = clrOver;\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function mOut(src,clrIn)\n";
    $this->salida .= "  {\n";
    $this->salida .= "    src.style.background = clrIn;\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function Adjuntos(empresa_id,prefijo,numero,documento_id)\n";
    $this->salida .= "  {\n";
    $this->salida .= "    var url= \"".$action['documento']."&empresa_id=\"+empresa_id+\"&prefijo=\"+prefijo+\"&numero=\"+numero;\n";
    $this->salida .= "    window.open(url,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');\n";
    $this->salida .= "  }\n";
    $this->salida .= "</script>\n";
    $this->salida .= $javaC1;
    $xml = AutoCarga::factory("ReportesCsv");
    $this->salida .= $xml->GetJavacriptReporteFPDF('app','Inv_MovimientosBodegas','documentoE008',array(),array("interface"=>5));
    $fnc   = $xml->GetJavaFunction();    
    $this->salida .= $xml->GetJavacriptReporteFPDF('app','Inv_MovimientosBodegas','rotuloE008',array(),array("interface"=>5));
    $fnci   = $xml->GetJavaFunction();
    SessionSetVar("funcion_E008",$fnc);
    SessionSetVar("rotulo_E008",$fnci);
  /*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta.
**********************************************************************************/
    $this->salida.="<div id='ContenedorMov1' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='tituloMov1' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarMov1' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorMov1');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorMov1' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoMov1'>\n";
    //$this->salida .= "jaime ";
    $this->salida .= "    </div>\n";
    $this->salida.="</div>";
/**************************************************************************************/
/*******************************************************************************
*Ventana para crear tercero
**********************************************************************************/
    $this->salida.="<div id='ContenedorCre' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='tituloCre' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarCre' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCre');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorCre' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoCre'>\n";
    $this->salida .= "    </div>\n";
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/
/*******************************************************************************
*Ventana para crear tercero
**********************************************************************************/
    $this->salida.="<div id='ContenedorDet' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='tituloDet' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarDet' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorDet');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorDet' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoDet' class='d2Content'>\n";
    $this->salida .= "    </div>\n";
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/
    $path = SessionGetVar("rutaImagenes");
    $this->salida .= "       <div id='errorcreartmp' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "            <form name=\"docus\" action=\"".$accion1."\" method=\"post\">\n";
    $this->salida .= "               <div id=\"ventana1\">\n";
    $this->salida .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td colspan='2' align=\"left\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          BUSCADOR DE DOCUMENTOS";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td width='30%' align=\"left\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          CLASES DE DOCUMENTO";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td  width='70%'  align=\"left\" class=\"modulo_list_claro\" id=\"tipos_ids_terceroxa\"> \n";
    $this->salida .= "                         <select name=\"clas_doc\" id=\"clas_doc\" class=\"select\" onchange=\"TraerMov('".$empresa_id."','".$centro_utilidad."','".$bodega."',this.value);\">";
    $tipos_clases=$consulta->ObtenerClasesDocumentos($empresa_id, $centro_utilidad, $bodega, UserGetUID());
      //var_dump($tipos_clases);
    $this->salida .="                           <option value=\"0\" selected>--</option> \n";
        foreach($tipos_clases as $valor)
          {
            $this->salida .="                           <option value=\"".$valor['tipo_movimiento']."\">".$valor['tipo_movimiento']."</option> \n";
          }
    $this->salida .="                         </select>\n";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td align=\"left\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          TIPOS DE DOCUMENTO";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td align=\"left\" class=\"modulo_list_claro\" > \n";
    $this->salida .= "                         <select name=\"tipos_doc\" id=\"tipos_doc\" class=\"select\" onchange=\"MostrarDocusFinal('1','".SessionGetVar("EMPRESA")."','".$centro_utilidad."','".$bodega."','".UserGetUID()."',document.getElementById('clas_doc').value,document.getElementById('tipos_doc').value);\">";
    $this->salida .="                           <option value=\"0\" selected>SELECCIONAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
    $this->salida .="                         </select>\n";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                 </table>";
    $this->salida .= "              </form>";
    $this->salida .= "              <br>";
    $this->salida .= "              <div id='documentos_final'>";
    $this->salida .= "              </div>";
    $DOCSBODEGA = ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('utility'=>$_REQUEST['utility'],'bodegax'=>$_REQUEST['bodegax'],'nom_bodegax'=>$_REQUEST['nom_bodegax']));
    $this->salida .= " <form name=\"volver\" action=\"".$DOCSBODEGA."\" method=\"post\">\n";//".$this->action[0]."
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
  /**
  *
  */
  function FormaDocumentoAdjunto()
  {
    $consulta= new MovBodegasSQL();
    //print_r($_REQUEST);
    //print_r($_REQUEST['prefijo']);
    $archivo=$consulta->ArchivoAdjunto($_REQUEST['empresa_id'] ,$_REQUEST['numero']);
    //print_r($archivo);
    //s/".$_REQUEST['prefijo'].9.".$_REQUEST['numero']."'
    if($archivo)
    {
      $this->salida.="<TD ALIGN=\"LEFT\" ><IMG SRC='app_modules/SalidasProductos/cartas/".$archivo['archivo_nombre']."></td>";
    }
    else
    {
      $this->salida.="<TD ALIGN=\"LEFT\" >NO EXISTE IMAGEN ADJUNTA EN ESTE DOCUMENTO</td>";
    }
    return true;
  }
 }
?>