<?php
	/**************************************************************************************  
	* $Id: doc_Bodegas_T001_HTML.class.php,v 1.2 2010/07/07 15:40:25 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.2 $ 
	* 
	* @autor Jaime G�ez 
	***************************************************************************************/

include "app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/T001/doc_Bodegas_T001.class.php";
//include "app_modules/Inv_MovimientosBodegas/classes/MovBodegasSQL.class.php";
//include_once "app_modules/Inv_MovimientosBodegas/pruebaDocBodega.php";
//IncludeClass('RecaudoElectronico',null,'app','RecibosCaja');
class doc_Bodegas_T001_HTML
{
 function doc_Bodegas_T001_HTML(){}

     
/*********************************************************************************
 Cabecera
*********************************************************************************/ 
    function FormaDocumento($DATOS)
    {    //var_dump($DATOS);
      $objeto=new Classmodules();
      $file = 'app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/T001/RemoteXajax/definirBodegas_T001.php';
      $objeto->SetXajax(array("Subtimit","PintarBodegas","CrearDocumentoFinalx","BorrarTmpAfirmativo1","MostrarProductox","Borrar","BorrarAjuste","GuardarPT","BuscarProducto1","ObtenerPaginadoPro","GuardarTmpDoc","Cuadrar_ids_terceros","CrearUSA","Buscadorter","ObtenerPaginadoter","AsignarProducto"),$file,"ISO-8859-1");

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

      $salida .= ThemeAbrirTabla("TIPO DE DOCUMENTO");
      $consulta = new doc_Bodegas_T001();
      $datos = $consulta->TraerDatos($DATOS['bodegas_doc_id']);
      $salida .=$this->Cabecera($datos);
      $salida .=$this->CrearDocumentosHtml($DATOS['bodegas_doc_id'],$DATOS['CTL'],$DATOS['tipo_doc_bodega_id'],$DATOS['nom_bodega'],$DATOS['utility'],$DATOS['bodega']);
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
      $salida .= ThemeAbrirTabla("TIPO DE DOCUMENTO");
      $consulta = new doc_Bodegas_T001();
      $datox=$consulta->DatosParaEditar($DATOS['doc_tmp_id'],UserGetUID());
      $salida .= $this->PintarTabla($datox);
      $datos = $consulta->TraerDatos($DATOS['bodegas_doc_id']);
      $salida .=$this->ColocarProductos($DATOS['bodegas_doc_id'],$datos,$DATOS['doc_tmp_id']);
      $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('nom_bodegax'=>$DATOS['nom_bodegax'],'utility'=>$DATOS['utility'],'bodegax'=>$DATOS['bodegax']));
      $salida .= " <form name=\"volver1\" action=\"".$BODEGA."\" method=\"post\">\n";//".$this->action[0]."
      $salida .= "  <table align=\"center\" width=\"50%\">\n";
      $salida .= "    <tr>\n";
      if(!empty($datox))
      {
       $salida .= "       <td id='SUTANO' align=\"center\" colspan='7'>\n";
       $salida .= "          <input type=\"button\" id='ELI' class=\"input-submit\" value=\"ELIMINAR DOCUMENTO\" DISABLED onclick=\"EliminarDocu('".$DATOS['doc_tmp_id']."','".$DATOS['bodegas_doc_id']."');\">\n";
       $salida .= "       </td>\n"; 
       $salida .= "       <td align=\"center\" colspan='7'>\n";
       $salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
       $salida .= "       </td>\n";
       $salida .= "       <td id='MENGANO' align=\"center\"  colspan='7'>\n";
       $salida .= "          <input type=\"button\" id='enar' class=\"input-submit\" value=\"CREAR DOCUMENTO\" DISABLED onclick=\"CrearDocuFinal('".$DATOS['bodegas_doc_id']."','".$DATOS['doc_tmp_id']."');\">\n";
       $salida .= "       </td>\n";
      }
      else
      {

       $salida .= "       <td align=\"center\" colspan='7'>\n";
       $salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
       $salida .= "       </td>\n";


      }

      $salida .= "    </tr>\n";
      $salida .= "  </table>\n";
      $salida .= " </form>\n";
      $salida .= ThemeCerrarTabla();
      return $salida;
    }
    /**
    *
    */
    function PintarTabla($busqueda)
    {
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
//   $salida .= "                       <td width=\"25%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
//   $salida .= "                          DOCUMENTO TEMPORAL #";
//   $salida .= "                       </td>";
//   $salida .= "                       <td  align=\"left\" class=\"normal_10AN\">\n";
//   $salida .= "                         1";
//   $salida .= "                       </td>";
  $salida .= "                    </tr>";
  $salida .= "                 </table>";
  $salida .= "              </form>";

  return $salida;
 }


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
    $javaC .= "       xResizeTo(Capa, 600, 430);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+30);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 580, 20);\n";
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
  $salida .= "                       <td rowspan='1' colspan='2' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                         <fieldset>";
  $salida .= "                           <legend>OBSERVACIONES</legend>";
  $salida .= "                             <TEXTAREA id='obser' ROWS='2' COLS=55 ></TEXTAREA>\n";//OnFocus=\"this.blur()\"
  $salida .= "                         </fieldset>";
  $salida .= "                       </td>\n";
  $salida .= "                    </tr>\n";
  $salida .= "                    <tr>\n";
  $salida .= "                      <td rowspan='1' colspan='1' align=\"center\" class=\"modulo_list_claro\"> \n";
  $salida .= "                   <b>CENTRO UTILIDAD :</b>";
  $utility=$consulta->ColocarCentro1($utilidad);
   // if(!empty($utility) && count($utility)>1)
    //{
          $salida .= "                       <select id=\"cen_des\" name=\"cen_des\" class=\"select\" onchange=\"PrepararBodegas('".trim($bodega)."',this.value);\">";
          $salida .= "                           <option value=\"\" SELECTED>SELECCIONAR</option>\n"; 
          //var_dump($utility);
          for($i=0;$i<count($utility);$i++)
          {
            $salida .= "                      <option value=\"".$utility[$i]['centro_utilidad']."\">".$utility[$i]['descripcion']."</option>\n";
          }
          $salida .= "                       </select>\n";
    //}
    //else
    //{
      $salida .= "<input  id='cen_des' type=\"hidden\" value=\"".$utility[0]['centro_utilidad']."\">\n";
      //$salida .= "<LABEL>".$utility[0]['descripcion']."</LABEL>";
      $salida .= "<script>PrepararBodegas('".$bodega."',document.getElementById('cen_des').value);</script>";
    //}
  $salida .= "                       </td>\n";
  $salida .= "                      <td rowspan='1' id='centros' colspan='1' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                      <b>BODEGAS :</b>";
  $salida .= "                       <select id=\"bod_des\" name=\"bod_des\" class=\"select\" onchange=\"\">";
  $salida .= "                           <option value=\"0\" SELECTED>SELECCIONAR</option>\n";
  $salida .= "                       </select>\n";
  $salida .= "                       </td>\n"; 
  $salida .= "                    </tr>\n";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  COLSPAN='2' align=\"center\" class=\"modulo_list_claro\">\n";                          //GrabarDocumento(bodegas_doc_id, observacion,centro,bodega)
  $salida .="<input type=\"button\" id=\"nuevo\" value=\"GRABAR DOCUMENTO\" class=\"input-bottom\" onClick=\"javascript:GrabarDocumento('".$bodegas_doc_id."',document.getElementById('obser').value,document.getElementById('cen_des').value,document.getElementById('bod_des').value);\">";//
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
    /**
    *
    */
    function ColocarProductos($bodegas_doc_id,$datos,$tmp_doc_id)
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
    $javaC .= "		    ele = xGetElementById('ContenidoBus');\n";
    $javaC .= "	      xResizeTo(ele,750, 380);\n";	
    $javaC .= "       Capa = xGetElementById(contenedor1);\n";
    $javaC .= "       xResizeTo(Capa, 750, 400);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/11, xScrollTop()+50);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 730, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarBus');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 730, 0);\n";
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
    $salida .= "    <div id='ContenidoBus' class=\"d2Content\">\n";
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
            $salida .="              <div id=\"tabelos\" style=\"overflow:scroll;width:99%;height:80%;\">";
            $salida .="              </div>\n";
            $salida .= "   </div>\n";
            $salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/


      $path = SessionGetVar("rutaImagenes");
      $salida .= "          <br>\n";
      $salida .= "<form name=\"forma_producto\" id=\"forma_producto\" action=\"\" method=\"post\">\n";
      $salida .= "  <div id='error_doc' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $salida .= "  <div id=\"ventana1\">\n";
      $salida .= "    <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "      <tr class=\"modulo_table_list_title\">\n";
      $salida .= "        <td  width='10%' align=\"left\">CODIGO</td>\n";
      $salida .= "        <input name='codigo_producto' id='codigo' type=\"hidden\" value=\"\">\n";
      $salida .= "        <input name='existencia' id='existo_val' type=\"hidden\" value=\"\">\n";
      $salida .= "        <td COLSPAN='1' width='13%' COLSPAN='1' id='codigo_pro' align=\"left\" class=\"modulo_list_claro\"> \n";
      $salida .= "                       ";
      $salida .= "        </td>\n";
      $salida .= "        <td width='10%' align=\"left\">EXISTENCIA</td>\n";
      $salida .= "        <td ID='existo' COLSPAN='1' width='10%' COLSPAN='1' id='codigo_pro' align=\"left\" class=\"modulo_list_claro\"> \n";
      $salida .= "                       ";
      $salida .= "        </td>\n";
      $salida .= "        <td COLSPAN='1' width='10%' align=\"left\">COSTO</td>\n";
      $salida .= "                       <td width='14%' id='costeno' align=\"center\" class=\"modulo_list_claro\">\n";
      $salida .= "                         \n";
      $salida .= "                       </td>\n";
      $salida .= "                       <td  width='7%' align=\"left\" class=\"modulo_list_claro\"> \n";
      $salida .= "                       ";
      $salida .= "                       </td>\n";
      $salida .= "                        <td width='15%' align=\"center\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
      $java = "javascript:MostrarCapa('ContenedorBus');Bus_Pro('".$datos['empresa_id']."','".$datos['centro_utilidad']."','".$datos['bodega']."','0','0','1','".$tmp_doc_id."');Iniciar4('BUSCAR PRODUCTO');Clear3000();\"";
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
      $salida .= "                       <td align=\"left\" class=\"modulo_table_list_title\">\n";
      $salida .= "                       UNIDAD";
      $salida .= "                       </td>\n";
      $salida .= "                       <td  id='unidad_pro' align=\"center\" class=\"modulo_list_claro\"> \n";
      $salida .= "                        ";
      $salida .= "                       </td>\n";
      $salida .= "                       <td COLSPAN='1' align=\"left\" class=\"modulo_table_list_title\">\n";
      $salida .= "                        CANTIDAD";
      $salida .= "                       </td>\n";
      $salida .= "                       <td  align=\"left\" class=\"modulo_list_claro\"> \n";
      $salida .= "                          <input type=\"text\" name=\"cantidad\" id=\"cantidad\" size='10' class=\"input-text\" value=\"\" onkeypress=\"return acceptNum(event);\" onclick=\"Clear3000();\">\n";
      $salida .= "                          <input type=\"hidden\" name=\"costeno_val\" id=\"costeno_val\"  value=\"0\">\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "      <tr class=\"modulo_table_list_title\">\n";
      $salida .= "        <td align=\"left\">LOTE</td>\n";   
      $salida .= "        <td colspan=\"3\" align=\"left\" class=\"modulo_list_claro\">\n";
      //$salida .= "          <input type=\"hidden\" id=\"lote\" name=\"lote\"  value=\"\">\n";
      $salida .= "          <label id=\"label_lote\"></label>\n";      
      $salida .= "        </td>\n";      
      $salida .= "        <td align=\"left\">FECHA VENCIMIENTO</td>\n";   
      $salida .= "        <td align=\"left\" class=\"modulo_list_claro\">\n";
      //$salida .= "          <input type=\"hidden\" id=\"fecha_vencimiento\" name=\"fecha_vencimiento\"  value=\"\">\n";
      $salida .= "          <label id=\"label_fecha\"></label>\n"; 
      $salida .= "        </td>\n";
      $salida .= "        <td colspan=\"2\" align=\"left\" class=\"modulo_list_claro\">\n";      
      $salida .= "          <div id=\"fecha_div\" style=\"display:none\">\n";
      $salida .= ReturnOpenCalendario("forma_producto","fecha_vencimiento","-")."\n";
      $salida .= "          </div>\n";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr>\n";
      $salida .= "        <td  COLSPAN='8' align=\"center\" class=\"modulo_list_claro\">\n";
      $salida .= "          <input type=\"hidden\" name=\"bodegas_doc_id\" value=\"".$bodegas_doc_id."\">\n";
      $salida .= "          <input type=\"hidden\" name=\"tmp_doc_id\" value=\"".$tmp_doc_id."\">\n";
      $salida .= "          <input type=\"hidden\" name=\"usuario_id\" value=\"".UserGetUID()."\">\n";
      $salida .= "          <input type=\"hidden\" name=\"porcentaje_gravamen\" value=\"0\">\n";
      //$salida .= "                          <input type=\"button\" id=\"nuevo\" value=\"SELECCIONAR PRODUCTO\" class=\"input-bottom\" onClick=\"javascript:GuardarProductoTemporal('".$bodegas_doc_id."','".$tmp_doc_id."',document.getElementById('codigo').value,document.getElementById('cantidad').value,'I',document.getElementById('costeno_val').value,'".UserGetUID()."');\">";//
      $salida .= "          <input type=\"button\" id=\"nuevo\" value=\"SELECCIONAR PRODUCTO\" class=\"input-submit\" onClick=\"xajax_GuardarPT(xajax.getFormValues('forma_producto'));\">";//
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $salida .= "    </table>";
      $salida .= "  </div>";
      $salida .= "  <br>";
      $salida .= "  <div id='tablaoide'></div>\n";
      $salida .= "</form>\n";
      return $salida;
    }
  }
?>