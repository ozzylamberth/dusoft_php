<?php
	/**************************************************************************************  
	* $Id: doc_Bodegas_I012_HTML.class.php,v 1.1 2009/07/17 19:08:17 johanna Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.1 $ 
	* 
	* @autor Mauricio Medina 
	***************************************************************************************/

include "app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I012/doc_Bodegas_I012.class.php";
class doc_bodegas_I012_HTML
{
 function doc_bodegas_I012_HTML(){}

     
/*********************************************************************************
 Cabecera
*********************************************************************************/ 
function FormaDocumento($DATOS)
{    //var_dump($DATOS);
     $objeto=new Classmodules();
     $file ='app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I012/RemoteXajax/definirBodegas_I012.php';
     $objeto->SetXajax(array("Subtimit","CrearDocumentoFinalx","BorrarTmpAfirmativo1","MostrarProductox","Borrar","BorrarAjuste","IngresoProductosTemporal",
							"BuscarProducto1","ObtenerPaginadoPro","GuardarTmpDoc","Cuadrar_ids_terceros",
							"CrearUSA","BuscadorProveedores","BuscadorProveedor_d","Listar_FacturasProveedor","Departamento2","Municipios",
							"Guardar_DYM","GuardarPersona","Actualizartmp","Devolver","GuardarDevolucion",
							"BuscarProductoFactura","RegistrarFactura","BuscarProductoFacturaDespacho","RegistrarFacturaProveedor"),$file,"ISO-8859-1");

//
     switch($DATOS['accion'])
     {
        case 'NUEVO_TMP':
          return $this->FormaDocNuevo($DATOS);
        break;

        case 'EDITAR':
          return $this->FormaDocEditar($DATOS);
        break;

        
        DEFAULT:
     }
     
}

function FormaDocNuevo($DATOS)
{
     
      $salida .= ThemeAbrirTabla("TIPO DE DOCUMENTO".$DATOS['tipo_doc_bodega_id']);
      $consulta = new doc_bodegas_I012();
      $datos = $consulta->TraerDatos($DATOS['bodegas_doc_id']);
      $salida .=$this->Cabecera($datos);

      $salida .=$this->CrearDocumentosHtml($DATOS['bodegas_doc_id'],$DATOS['CTL'],$DATOS['tipo_doc_bodega_id'],$DATOS['nom_bodega'],$DATOS['utility'],$DATOS['bodega']);
      //$salida .=$this->ColocarProductos($DATOS['bodegas_doc_id'],$datos);
     $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('nom_bodegax'=>$DATOS['nom_bodega'],'utility'=>$DATOS['utility'],'bodegax'=>$DATOS['bodega']));
     $salida .= " <form name=\"volver1\" action=\"".$BODEGA."\" method=\"post\">\n";//".$this->action[0]."
     $salida .= "  <table align=\"center\" width=\"50%\">\n";
     $salida .= "    <tr>\n";
     $salida .= "       <td align=\"center\" colspan='7'>\n";
     $salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
     $salida .= "       </td>\n";
     $salida .= "    </tr>\n";
     $salida .= "  </table>\n";
     $salida .= " </form>\n";
      $salida .= ThemeCerrarTabla();
      return $salida;
}

function FormaDocEditar($DATOS)
{
     
	    $salida .= ThemeAbrirTabla("TIPO DE DOCUMENTO -".$DATOS['tipo_doc_bodega_id']);
      $consulta = new doc_bodegas_I012();
      $datox=$consulta->DatosParaEditar($DATOS['doc_tmp_id'],UserGetUID());
      $DocTemporal_Auxiliar=$consulta->GetDocTemporal($DATOS['bodegas_doc_id'],$DATOS['doc_tmp_id'],UserGetUID());
      $salida .= $this->PintarTabla($datox);
	  //print_r($datox);
      $datos = $consulta->TraerDatos($DATOS['bodegas_doc_id']);
	  $InformacionTercero=$consulta->Tercero(trim($DocTemporal_Auxiliar['tipo_id_tercero']),trim($DocTemporal_Auxiliar['tercero_id']));
      $ProductosFactura=$consulta->ProductosFactura(trim($DocTemporal_Auxiliar['tipo_id_tercero']),trim($DocTemporal_Auxiliar['tercero_id']),$DocTemporal_Auxiliar['prefijo'],$DocTemporal_Auxiliar['numero'],$DocTemporal_Auxiliar['empresa_id']);
	    
      //print_r($ProductosFactura);
		  
      /*
		   * Es el que carga la capa del Buscador.
		  */
      $salida .=$this->ColocarProductos($DATOS['bodegas_doc_id'],$datos,$DATOS['doc_tmp_id'],$datox['empresa_id'],$DocTemporal_Auxiliar,$InformacionTercero,$ProductosFactura,$DATOS);
      $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('nom_bodegax'=>$DATOS['nom_bodegax'],'utility'=>$DATOS['utility'],'bodegax'=>$DATOS['bodegax']));
      $salida .= " <form name=\"volver1\" action=\"".$BODEGA."\" method=\"post\">\n";//".$this->action[0]."
      $salida .= "  <table align=\"center\" width=\"50%\">\n";
      $salida .= "    <tr>\n";
      
      
       $salida .= "       <td id='SUTANO' align=\"center\" colspan='7'>\n";
       $salida .= "          <input type=\"button\" id='ELI' class=\"input-submit\" value=\"ELIMINAR DOCUMENTO\" onclick=\"EliminarDocu('".$DATOS['doc_tmp_id']."','".$DATOS['bodegas_doc_id']."');\">\n";
       $salida .= "       </td>\n"; 
       //$salida .= "<pre>".print_r($contar,true)."</pre>";
       $salida .= "       <td align=\"center\" colspan='7'>\n";
       $salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
       $salida .= "       </td>\n";
           
      
          $salida .= "       <td id='MENGANO' align=\"center\"  colspan='7'>\n";
          $salida .= "          <input type=\"button\" id='enar' class=\"input-submit\" value=\"CREAR DOCUMENTO\" DISABLED onclick=\"CrearDocuFinal('".$DATOS['bodegas_doc_id']."','".$DATOS['doc_tmp_id']."','".$DATOS['tipo_doc_bodega_id']."','".$datox['empresa_id']."','".$datox['centro_utilidad']."','".$datox['bodega']."');\">\n";
          $salida .= "       </td>\n";
         
         $salida .= "       <td id='MENGANO' align=\"center\"  colspan='7'>\n";
         $salida .= "          <input type=\"hidden\" id='devolver' class=\"input-submit\" value=\"DEVOLVER\" onclick=\"Devolver('".$DATOS['tipo_doc_bodega_id'] ."','".$DATOS['doc_tmp_id']."','".$_['EMPRESAS']['empresa_id']."');\">\n";
         $salida .= "       </td>\n";
          
       
      
       $salida .= "       <td align=\"center\" colspan='7'>\n";
       $salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
       $salida .= "       </td>\n";
    
      $salida .= "    </tr>\n";
      $salida .= "  </table>\n";
      $salida .= " </form>\n";
      $salida .= ThemeCerrarTabla();
      return $salida;
}






 function PintarTabla($busqueda)
 {
   //var_dump($busqueda);

   $path = SessionGetVar("rutaImagenes");
   $salida .= "                  <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
   $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
   $salida .= "                      <td align=\"center\" >\n";
   $salida .= "                        <a title='DOCUMENTO TEMPORAL ID'>TMP-ID</a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
   $salida .= "                        ".$busqueda['doc_tmp_id'];
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\">\n";
   $salida .= "                        <a title='ID DOCUMENTO DE LA BODEGA'>BODEGA DOC ID<a> ";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
   $salida .= "                        ".$busqueda['bodegas_doc_id'];
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\">\n";
   $salida .= "                        <a title='FECHA REGISTRO DOCUMENTO'>FECHA BOD REG<a> ";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
   $salida .= "                        ".substr($busqueda['fecha_registro'],0,10);
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\">\n";
   $salida .= "                        <a title='PREFUJO DEL DOCUMENTO'>PREFIJO<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
   $salida .= "                         ".$busqueda['prefijo'];
   $salida .= "                      </td>\n";
   $salida .= "                    </tr>\n";


  
   $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
   $salida .= "                      <td align=\"center\" >\n";
   $salida .= "                        <a title='DOCUMENTO DESCRIPCION'>DESCRIPCION<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td COLSPAN='7' align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
   $salida .= "                         ".$busqueda['descripcion'];
   $salida .= "                      </td>\n";
   $salida .= "                      </tr>\n";
   $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
   $salida .= "                      <td align=\"center\">\n";
   $salida .= "                        OBSERVACION";
   $salida .= "                      </td>\n";
   $salida .= "                      <td COLSPAN='7' align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
   $salida .= "                         ".$busqueda['observacion'];
   $salida .= "                      </td>\n";
   $salida .= "                      </tr>\n";
   $salida .= "                    </table>\n";
   return $salida;
   
 }



function Cabecera($datos)
{
  
  $consulta = new MovBodegasSQL();
  //$consulta=new RecaudoElectronico();
  $path = SessionGetVar("rutaImagenes");
  
  $salida .= "            <form name=\"recaudo\" action=\"javascript:LlamarRevi('".SessionGetVar("EMPRESA")."',document.revi.tip_lapi.value);\" method=\"post\">\n";
  $salida .= "               <div id=\"ventana1\">\n";
  $salida .= "                 <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";
  $salida .= "                    <tr class=\"modulo_list_claro\">\n";
  $salida .= "                       <td width=\"25%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                          EMPRESA";
  $salida .= "                       </td>";
  $nombreempresa=$consulta->ColocarEmpresa($datos['empresa_id']);
  $salida .= "                       <td colspan='3' align=\"left\" class=\"normal_10AN\">\n";
  $salida .= "                      ".$nombreempresa[0]['razon_social'];
  $salida .= "                       </td>";
  $salida .= "                    </tr>";
  $salida .= "                    <tr class=\"modulo_list_claro\">\n";
  $salida .= "                       <td width=\"25%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                          BODEGA";
  $salida .= "                       </td>";
  $salida .= "                       <td colspan='3' align=\"left\" class=\"normal_10AN\">\n";
  $nombrebodega=$consulta->bodegasname($datos['bodega']);
  $salida .= "                          10 -  ".$nombrebodega[0]['descripcion'];
  $salida .= "                       </td>";
  $salida .= "                    </tr>";
  $salida .= "                    <tr class=\"modulo_list_claro\">\n";
  $salida .= "                       <td width=\"25%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                          TIPO CLASE DE DOCUMENTO";
  $salida .= "                       </td>";
  //$nombre=$consulta->Nombres($tipo_id_tercero,$tercero_id);
  $salida .= "                       <td colspan='3' align=\"left\" class=\"normal_10AN\">\n";
  $salida .= "                        ".$datos['tipo_clase_documento'];
  $salida .= "                       </td>";
  $salida .= "                    </tr>";
  $salida .= "                    <tr class=\"modulo_list_claro\">\n";
  $salida .= "                       <td width=\"25%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                          DESCRIPCION";
  $salida .= "                       </td>";
  //$nombre=$consulta->Nombres($tipo_id_tercero,$tercero_id);
  $salida .= "                       <td colspan='3' align=\"left\" class=\"normal_10AN\">\n";
  $salida .= "                        ".$datos['descripcion'];
  $salida .= "                       </td>";
  $salida .= "                    </tr>";

  $salida .= "                    <tr class=\"modulo_list_claro\">\n";
  $salida .= "                       <td width=\"25%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                          TIPO DE MOVIMIENTO";
  $salida .= "                       </td>";
  
  $salida .= "                       <td  align=\"left\" class=\"normal_10AN\">\n";
if($datos['tipo_movimiento']=='I')
  $salida .= "                         INGRESO";
ELSE
  $salida .= "                         EGRESO";
  $salida .= "                       </td>";
  $salida .= "                       <td colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                          PREFIJO";
  $salida .= "                       </td>";
  $salida .= "                       <td align=\"left\" class=\"normal_10AN\">\n";
  $salida .= "                         ".$datos['prefijo'];
  $salida .= "                       </td>";
  $salida .= "                    <tr class=\"modulo_list_claro\">";
  $salida .= "                       <td width=\"25%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                          CENTRO DE UTILIDAD";
  $salida .= "                       </td>";
  $salida .= "                       <td colspan='3' align=\"left\" class=\"normal_10AN\">\n";
  $salida .= "                         ".$datos['centro_utilidad'];
  $salida .= "                       </td>";

  $salida .= "                    </tr>";
  $salida .= "                 </table>";
  $salida .= "              </form>";

  return $salida;
 }



////////////////////////////////////////////////////////////////////////////////
function CrearDocumentosHtml($bodegas_doc_id,$dir,$tipo_doc_bodega_id,$nom_bodega,$utilidad,$bodega)
 {

  
    $consulta = new MovBodegasSQL();
    $javaC = "<script>\n";
    $javaC .= "   var contenedor1=''\n";
    $javaC .= "   var titulo1=''\n";
    $javaC .= "   var hiZ = 2;\n";
    $javaC .= "   var DatosFactor = new Array();\n";
    $javaC .= "   var EnvioFactor = new Array();\n";
    $javaC .= "   function Rata()\n";
    $javaC .= "   {\n";
    $javaC .= "   alert('JUKILO');";
    $javaC .= "   }\n";
    $javaC .= "   function Iniciar2(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorMov1';\n";
    $javaC .= "       titulo1 = 'tituloMov1';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n";
    $javaC .= "       xResizeTo(Capa, 700, 600);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+30);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 700, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarMov1');\n";
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
    </script>";
    $salida.= $javaC1;

  /*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
    $salida.="<div id='ContenedorMov1' class='d2Container' style=\"display:none\">";
    $salida .= "    <div id='tituloMov1' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $salida .= "    <div id='cerrarMov1' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorMov1');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $salida .= "    <div id='errorMov1' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $salida .= "    <div id='ContenidoMov1'>\n";
    //$this->salida .= "jaime ";
    $salida .= "    </div>\n";
    $salida.="</div>";
/**************************************************************************************/
/*******************************************************************************
*Ventana para crear tercero
**********************************************************************************/
    $salida.="<div id='ContenedorCre' class='d2Container' style=\"display:none\">";
    $salida .= "    <div id='tituloCre' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $salida .= "    <div id='cerrarCre' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCre');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $salida .= "    <div id='errorCre' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $salida .= "    <div id='ContenidoCre'>\n";
    $salida .= "    </div>\n";
    $salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/   


  $path = SessionGetVar("rutaImagenes");
  
  $salida .= "          <br>\n";

  $salida .= "       <div id='errorcreartmp' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
  $salida .= "            <form name=\"unocreate\" action=\"".$accion1."\" method=\"post\">\n";
  $salida .= "               <div id=\"ventana1\">\n";
  $salida .= "                 <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  width='13%' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                        TERCERO ID";
  $salida .= "                       </td>\n";
  $salida .= "                          <input type=\"hidden\" id=\"tercerito_tip\" name=\"tercerito_tip\" value=\"0\">\n";
  $salida .= "                          <input type=\"hidden\" id=\"tercerito\" name=\"tercerito\" value=\"0\">\n";
  $salida .= "                          <input type=\"hidden\" id=\"htmp_id\" name=\"htmp_id\" value=\"0\">\n";
  $salida .= "                          <input type=\"hidden\" id=\"codigo_proveedor_id\" name=\"codigo_proveedor_id\" value=\"\">\n";
  $salida .= "                       <td  width='5%'  align=\"left\" class=\"modulo_list_claro\" id=\"tipos_ids_terceroxa\"> \n";
  $salida .= "                         <select name=\"tipox_id\" id=\"tipox_id\" class=\"select\" onchange=\"\">";
  $TiposTercerosId=$consulta->Terceros_id();
      for($i=0;$i<count($TiposTercerosId);$i++)
        {
          $salida .="                           <option value=\"".$TiposTercerosId[$i]['tipo_id_tercero']."\">".$TiposTercerosId[$i]['tipo_id_tercero']."</option> \n";
        }
  $salida .="                         </select>\n";
  $salida .= "                       </td>\n";
  $salida .= "                       <td  width='14%'  align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                          <input type=\"text\" id=\"id_tercerox\" name=\"id_tercerox\" class=\"input-text\" onkeydown=\"recogerTeclab(event);\" onclick=\"\" value=\"\">\n";
  $salida .= "                       </td>\n";
  $salida .= "                       <td  width='8%' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                        NOMBRE";
  $salida .= "                       </td>\n";
  $salida .= "                       <td  width='40%'  align=\"left\" class=\"modulo_list_claro\" id=\"td_terceros_nue_mov\"> \n";
  $salida .= "                       </td>\n";
  $salida .= "                        <td width='20%' align=\"center\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
  $java =    "  javascript:MostrarCapa('ContenedorMov1');BuscarProveedor('ContenidoMov1','unocreate');Iniciar2('BUSCAR CLIENTE');\"";//
  $salida .= "                          <a  title=\"SELECIONAR CLIENTE\" class=\"label_error\" href=\"".$java."\"> BUSCAR CLIENTE</a>\n";
  $salida .= "                        </td>";
  $salida .= "                    </tr>\n";
  $salida .= "                    <tr class=\"modulo_list_claro\">";
  $salida .= "                    <td class=\"modulo_table_list_title\" >";
  $salida .= "                        FACTURAS";
  $salida .= "                    </td>";
  $salida .= "                    <td class=\"modulo_list_claro\" colspan='6'>";
  $salida .= "                        <div id=\"facturas_proveedor\"></div>";
  $salida .= "                    </td>";
  $salida .= "                    </tr>";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td rowspan='1' colspan='6' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                         <fieldset>";
  $salida .= "                           <legend>OBSERVACIONES</legend>";
  $salida .= "                             <TEXTAREA id='obser' ROWS='2' COLS=55 ></TEXTAREA>\n";//OnFocus=\"this.blur()\"
  $salida .= "                         </fieldset>";
  $salida .= "                       </td>\n";
  $salida .= "                    </tr>\n";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  COLSPAN='8' align=\"center\" class=\"modulo_list_claro\">\n";                          //GrabarDocumento(bodegas_doc_id, observacion,centro,bodega)               tercerito_tip,tercerito
  $salida .="<input type=\"button\" id=\"nuevo\" value=\"GRABAR DOCUMENTO\" class=\"input-bottom\" onClick=\"javascript:GrabarDocumento('".$bodegas_doc_id."',document.getElementById('obser').value,document.getElementById('tercerito_tip').value,document.getElementById('tercerito').value,document.getElementById('factura').value);\">";//
  $salida .= "                       </td>\n"; 
  $salida .= "                    </tr>\n";
  $salida .= "                 </table>";
  $salida .= "              </form>";
 
      $salida .= " <form name=\"volver\" action=\"".$dir."\" method=\"post\">\n";
      $salida .= "  <input  id='doc_tmp_id_h' type=\"hidden\" value=\"\">\n";
      $salida .= "  <input name='accion_h' id='accion_h' type=\"hidden\" value=\"\">\n";
      $salida .= "  <input id='tipo_clase' type=\"hidden\" value=\"".$tipo_doc_bodega_id."\">\n";
      $salida .= "  <input id='bodegas_doc_id' type=\"hidden\" value=\"".$bodegas_doc_id."\">\n";
      $salida .= "  <input id='nom_bodegax' type=\"hidden\" value=\"".$nom_bodega."\">\n";
      $salida .= "  <input id='utility' type=\"hidden\" value=\"".$utilidad."\">\n";
      $salida .= "  <input id='bodegax' type=\"hidden\" value=\"".$bodega."\">\n";
      $salida .= " </form>\n";
  return $salida;
 }

/************************************************************************************
* COLOCAR LOS PRODUCTOS DEL DOCUMENTO
*********************************************************************************/ 

function ColocarProductos($bodegas_doc_id,$datos,$tmp_doc_id,$empresa_id,$DocTemporal_Auxiliar,$InformacionTercero,$ProductosFactura,$DATOS)
 {
    $consulta = new MovBodegasSQL();
    $usuariotmp=$consulta->Consultausuaritmp($tmp_doc_id,$bodegas_doc_id);
    $devolucion=$consulta->ConsultaDevolucion_doc($datos['tipo_doc_bodega_id'],$tmp_doc_id);
    //print_r($ProductosFactura);
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
    $javaC .= "       xResizeTo(Capa, 720, 500);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/11, xScrollTop()+100);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 720, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarBus');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 620, 0);\n";
    $javaC .= "   }\n";
    $javaC .= "   function IniciarUsu(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorCre';\n";
    $javaC .= "       titulo1 = 'tituloCre';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 700, 680);\n";
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
    /*
    * Principal Buscador
    */
	$salida .= "                          <input type=\"hidden\" id=\"empresa_idz\" value=\"".$datos['empresa_id']."\">\n";
	$salida .= "                          <input type=\"hidden\" id=\"centro_utilidadz\" value=\"".$datos['centro_utilidad']."\">\n";
	$salida .= "                          <input type=\"hidden\" id=\"bodegaz\" value=\"".$datos['bodega']."\">\n";
 
      
      $sql = new doc_bodegas_I012();
     //$DocTemporal_Auxiliar,$InformacionTercero,$ProductosFactura
     //print_r($InformacionTercero);
     $PoliticasVencimiento=$sql->PoliticasVencimiento($InformacionTercero[0]['tercero_id']);
   // print_r($InformacionTercero);
      $politicas  = "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $politicas .= "                    <tr class=\"modulo_table_list_title\">\n";
      $politicas .= "                     <td align=\"center\" colspan=\"2\">";
      $politicas .= "                     CLIENTE :".$InformacionTercero[0]['tipo_id_tercero']."-".$InformacionTercero[0]['tercero_id']."::-".$InformacionTercero[0]['nombre_tercero']." FACTURA:".$DocTemporal_Auxiliar['prefijo']."-".$DocTemporal_Auxiliar['numero']."";
      $politicas .= "                     </td>";
      $politicas .= "                     </tr>";
      $i=1;
      foreach($PoliticasVencimiento as $k=>$pol)
      {
      $politicas .= "                   <tr class=\"modulo_table_list\">";
      $politicas .= "                     <td width=\"10%\" align=\"center\" class=\"label_error\">";
      $politicas .= "                     ".$i;
      $politicas .= "                     </td>";
      $politicas .= "                     <td width=\"90%\" >";
      $politicas .= "                      <b>".$pol['descripcion']."</b>";
      $politicas .= "                     </td>";
      $politicas .= "                   </tr>"; 
      $i++;
      }
      $politicas .= "                 </table>\n";         
     					
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
  $salida .= "                 <form name=\"ProductosFactura\" id=\"ProductosFactura\"  method=\"post\">\n";
  $salida .= "                 <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
  $salida .= "                  <tr class=\"modulo_table_list_title\">";
  $salida .= "                      <td>CODIGO</td><td>NOMBRE</td><td>CANTIDAD</td><td>%IVA</td><td>VLR/U</td><td>LOTE</td><td>FECHA VENCIMIENTO</td><td>DEV</td><td>OP</td>";
  $salida .= "                  </tr>";
      $i=0;
      $fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.SessionGetVar("EMPRESA"));
      $fecha_actual=date("m/d/Y");
      foreach($ProductosFactura as $key=>$producto)
      {
      
          //$fech_vencmodulo=ModuloGetVar('app','AdministracionFarmacia','dias_vencimiento_product_bodega_farmacia_02');
          
          
          /*
          * Para Sacar los numeros de días entre fechas
         */      
          $fecha =$producto['fecha_vencimiento'];  //esta es la que viene de la DB
          list($ano,$mes,$dia) = split( '[/.-]', $fecha );
          $fecha = $mes."/".$dia."/".$ano;
          
          
          $fecha_compara_actual=date("Y-m-d");
          //Mes/Dia/Año  "02/02/2010
          $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
          $colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
          $colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');
            
          $fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
          $fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
          $color ="";
           if($int_nodias<$fech_vencmodulo)
          {
            $color = "style=\"background:".$colores['PV']."\"";
          }
          
              if($fecha_dos<=$fecha_uno_act)
                {
                $color = "style=\"background:".$colores['VN']."\"";
                }
      
      $total = ($producto['cantidad']-$producto['cantidad_devuelta']);
      $salida .= "                 <tr class=\"modulo_list_claro\">";
      $salida .= "                    <td><input style=\"width:100%\" ReadOnly=\"true\" class=\"input-text\" type=\"text\" name=\"codigo_producto".$i."\" id=\"codigo_producto".$i."\" value=\"".$producto['codigo_producto']."\"></td>";
      $salida .= "                    <td>".$producto['descripcion_producto']."</td>";
      $salida .= "                    <td>".$total."</td>";
     
      
      $salida .= "                    <td><input style=\"width:100%\" ReadOnly=\"true\" class=\"input-text\" type=\"text\" name=\"porc_iva".$i."\" id=\"porc_iva".$i."\" value=\"".$producto['porc_iva']."\"></td>";
      $salida .= "                    <td>$".FormatoValor($producto['valor_unitario'],4)."</td>";
      $salida .= "                    <td><input style=\"width:100%\" ReadOnly=\"true\" class=\"input-text\" type=\"text\" name=\"lote".$i."\" id=\"lote".$i."\" value=\"".$producto['lote']."\"></td>";
      $salida .= "                    <td><input style=\"width:100%\" ReadOnly=\"true\" class=\"input-text\" type=\"text\" name=\"fecha_vencimiento".$i."\" ".$color." id=\"fecha_vencimiento".$i."\" value=\"".$producto['fecha_vencimiento']."\"></td>";
      $salida .= "                    <td><input style=\"width:100%\" title=\"Cantidad a Devolver de ".$producto['descripcion_producto']."\" onkeypress=\"return acceptNum(event);\"  size=\"4\" type=\"text\" class=\"input-text\" name=\"cantidad".$i."\" id=\"cantidad".$i."\"></td>";
      $salida .= "                    <td align=\"center\">";
      $salida .= "                    ".$Existencia[0]['existencia_actual']."<input type=\"hidden\" name=\"total".$i."\" id=\"total".$i."\" value=\"".$total."\">";
      $salida .= "                    <input type=\"hidden\" name=\"costo".$i."\" id=\"costo".$i."\" value=\"".($producto['costo'])."\">";
      $salida .= "                    <input type=\"hidden\" name=\"valor_unitario".$i."\" id=\"valor_unitario".$i."\" value=\"".($producto['valor_unitario'])."\">";
      $salida .= "                    <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
      $salida .= "                    <input type=\"checkbox\" id=\"".$i."\" name=\"".$i."\" value=\"".$producto['item_id']."\" class=\"checkbox\">";
      $salida .= "                    </td>";
      $salida .= "                 </tr>";
      $salida .= "                <tr>";
      $salida .= "                <td colspan=\"10\" align=\"center\"><div class=\"label_error\" id=\"mensaje".$i."\"></div></td>";
      $salida .= "                </tr>";
      $i++;
      }
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  COLSPAN='10' align=\"center\" class=\"modulo_list_claro\">\n";                          //                         $doc_tmp_id,                                            $codigo_producto,                     $cantidad,                                       $porcentaje_gravamen[document.getElementById('gravamen').value],$total_costo[document.getElementById('op22').value],                   $usuario_id=null
  $salida .= "                          ".$politicas;
  $salida .= "                       </td>\n"; 
  $salida .= "                    </tr>\n";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  COLSPAN='10' align=\"center\" class=\"modulo_list_claro\">\n";                          //                         $doc_tmp_id,                                            $codigo_producto,                     $cantidad,                                       $porcentaje_gravamen[document.getElementById('gravamen').value],$total_costo[document.getElementById('op22').value],                   $usuario_id=null
  $salida .= "                          <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
  $salida .= "                          <input type=\"button\" id=\"nuevo\" value=\"SELECCIONAR PRODUCTO\" class=\"input-bottom\" onClick=\"xajax_IngresoProductosTemporal('".$bodegas_doc_id."','".$tmp_doc_id."',xajax.getFormValues('ProductosFactura'),'".UserGetUID()."');\">";
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
}
?>