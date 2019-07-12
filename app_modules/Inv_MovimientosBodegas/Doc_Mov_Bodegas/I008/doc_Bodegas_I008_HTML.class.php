<?php
	/**************************************************************************************  
	* $Id: doc_Bodegas_I008_HTML.class.php,v 1.1 2009/07/17 19:08:17 johanna Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.1 $ 
	* 
	* @autor Mauricio Medina
	***************************************************************************************/

include "app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I008/doc_Bodegas_I008.class.php";
//include "app_modules/Inv_MovimientosBodegas/classes/MovBodegasSQL.class.php";
//include_once "app_modules/Inv_MovimientosBodegas/pruebaDocBodega.php";
//IncludeClass('RecaudoElectronico',null,'app','RecibosCaja');
class doc_bodegas_I008_HTML
{
 function doc_bodegas_I008_HTML(){}

     
/*********************************************************************************
 Cabecera
*********************************************************************************/ 
function FormaDocumento($DATOS)
{    //var_dump($DATOS);
     $objeto=new Classmodules();
     $file ='app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I008/RemoteXajax/definirBodegas_I008.php';
     $objeto->SetXajax(array("Subtimit","CrearDocumentoFinalx","BorrarTmpAfirmativo1",
							 "MostrarProductox","Borrar","BorrarAjuste","AgregarItem",
							 "BuscarProducto1","ObtenerPaginadoPro","GuardarTmpDoc",
							 "Cuadrar_ids_terceros","CrearUSA","Buscadorter","ObtenerPaginadoter",
							 "Departamento2","Municipios","Guardar_DYM","GuardarPersona",
							 "Actualizartmp","Devolver","GuardarDevolucion","RecargarProductosTmp","DevolucionprestamoTerce","MostrarTerceros","ListadoProductos"),$file,"ISO-8859-1");


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

      $salida .= ThemeAbrirTabla("TIPO DE DOCUMENTO - ".$DATOS['tipo_doc_bodega_id']);
      $consulta = new doc_bodegas_I008();
      $datos = $consulta->TraerDatos($DATOS['bodegas_doc_id']);
      $salida .=$this->Cabecera($datos);
    	
     $salida .=$this->CrearDocumentosHtml($DATOS['bodegas_doc_id'],$DATOS['CTL'],$DATOS['tipo_doc_bodega_id'],$DATOS['nom_bodega'],$DATOS['utility'],$DATOS['bodega'],$datos['empresa_id']);
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
      $salida .= ThemeAbrirTabla("TIPO DE DOCUMENTO - ".$DATOS['tipo_doc_bodega_id']);
      $consulta = new doc_bodegas_I008();
      $datox=$consulta->DatosParaEditar($DATOS['doc_tmp_id'],UserGetUID());
      //var_dump($datox);
      $salida .= $this->PintarTabla($datox);
      $datos = $consulta->TraerDatos($DATOS['bodegas_doc_id']);
      $consulta1 = new MovBodegasSQL();
      $si_esta=$consulta1->ConsultaPardocg($DATOS['doc_tmp_id']);
      $param_estados=$consulta1->ConsultaEstadosPermisos($DATOS['tipo_doc_bodega_id']);
      if(empty($si_esta))
      {
        foreach ($param_estados as $indice=>$valor)
        { 
          $guadarpar=$consulta1->GuardarParGrabar($DATOS['tipo_doc_bodega_id'],$valor['abreviatura'],$DATOS['doc_tmp_id']);
        }
      }
      $salida .=$this->ColocarProductos($DATOS['bodegas_doc_id'],$datos,$DATOS['doc_tmp_id']);
      $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('nom_bodegax'=>$DATOS['nom_bodegax'],'utility'=>$DATOS['utility'],'bodegax'=>$DATOS['bodegax']));
      $salida .= " <form name=\"volver1\" action=\"".$BODEGA."\" method=\"post\">\n";//".$this->action[0]."
      $salida .= "  <table align=\"center\" width=\"50%\">\n";
      $salida .= "    <tr>\n";
      //print_r($DATOS['doc_tmp_id']);
      SessionSetVar("empresa_id",$datox['empresa_id']);
      SessionSetVar("centro_utilidad",$DATOS['utility']);
      SessionSetVar("bodega",$DATOS['bodegax']);
      if(!empty($datox))
      {
       $usuariotmp=$consulta1->Consultausuaritmp($datox['doc_tmp_id'],$datox['bodegas_doc_id']);
       $documentos=$consulta1->ConsultaPardocg($datox['doc_tmp_id']);
       $estadosEmpresa=$consulta1->ConsultaEmpresa($datox['empresa_id']);
       $contar=count($documentos); 
       
       $salida .= "       <td id='SUTANO' align=\"center\" colspan='7'>\n";
       $salida .= "          <input type=\"button\" id='ELI' class=\"input-submit\" value=\"ELIMINAR DOCUMENTO\" onclick=\"EliminarDocu('".$DATOS['doc_tmp_id']."','".$DATOS['bodegas_doc_id']."');\">\n";
       $salida .= "       </td>\n"; 
       $ContadorVerificaciones=0;
       if($estadosEmpresa['sw_estados']==1)
       {
         foreach($documentos as $llave => $Estados)
               {
              if($Estados['sw_verifico']=='1')
               $ContadorVerificaciones++;
               //echo "[".$Estados['sw_verifico']."]";
               }
        
        //print_r($documentos);
         
         if($usuariotmp['usuario_id']==UserGetUID()&&$ContadorVerificaciones==$contar)

         {
          $salida .= "       <td id='MENGANO' align=\"center\"  colspan='7'>\n";
          $salida .= "          <input type=\"button\" id='enar' class=\"input-submit\" value=\"CREAR DOCUMENTO\" onclick=\"CrearDocuFinal('".$DATOS['bodegas_doc_id']."','".$DATOS['doc_tmp_id']."','".$DATOS['tipo_doc_bodega_id']."');\">\n";
          $salida .= "       </td>\n";
         }
         else
         {
          $salida .= "       <td id='MENGANO' align=\"center\"  colspan='7'>\n";
          $salida .= "          <input type=\"button\" id='enar' class=\"input-submit\" value=\"CREAR DOCUMENTO\" onclick=\"CrearDocuFinal('".$DATOS['bodegas_doc_id']."','".$DATOS['doc_tmp_id']."','".$DATOS['tipo_doc_bodega_id']."');\">\n";
          $salida .= "       </td>\n";
         }
         
         $salida .= "       <td id='MENGANO' align=\"center\"  colspan='7'>\n";
         $salida .= "          <input type=\"hidden\" id='devolver' class=\"input-submit\" value=\"DEVOLVER\" onclick=\"Devolver('".$DATOS['tipo_doc_bodega_id'] ."','".$DATOS['doc_tmp_id']."','".$_['EMPRESAS']['empresa_id']."');\">\n";
         $salida .= "       </td>\n";
         
       
          
       }
       else
       {
         $salida .= "       <td id='MENGANO' align=\"center\"  colspan='7'>\n";
         $salida .= "          <input type=\"button\" id='enar' class=\"input-submit\" value=\"CREAR DOCUMENTO\" onclick=\"CrearDocuFinal('".$DATOS['bodegas_doc_id']."','".$DATOS['doc_tmp_id']."','".$DATOS['tipo_doc_bodega_id']."');\">\n";
         $salida .= "       </td>\n";
       }
      }
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
   $consulta = new doc_bodegas_I008();
   //$datox=$consulta->DatosParaEditar($busqueda['doc_tmp_id'],UserGetUID());
   $datos = $consulta->GetInfoDocTemporal($busqueda['bodegas_doc_id'],$busqueda['doc_tmp_id']);
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
   $salida .= "	<br>";
   $salida .= " <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
   $salida .= "   <tr class=\"modulo_table_list_title\">\n";
   $salida .= "     <td align=\"center\" colspan=\"4\">\n";
   $salida .= "       <a title='DESCRIPCION DEL DOCUMENTO DE DESPACHO'>DOCUMENTO DE DESPACHO</a>";
   $salida .= "     </td>\n";
   $salida .= "   </tr>\n";
   
  // print_r($datos);
   $salida .= "   <tr class=\"modulo_list_claro\">\n";
   $salida .= "      <td align=\"center\" class=\"modulo_table_list_title\">\n";
   $salida .= "         <a title='NOMBRE DE EMPRESA QUE DESPACHA'>NOMBRE<a>";
   $salida .= "      </td>\n";
   $salida .= "      <td align=\"left\" class=\"label_mark\">\n";
   $salida .= "          ".$datos['razon_social'];
   $salida .= "      </td>\n";
   $salida .= "      <td align=\"center\" class=\"modulo_table_list_title\">\n";
   $salida .= "         <a title='DOCUMENTO'>DOCUMENTO<a>";
   $salida .= "      </td>\n";
   $salida .= "      <td align=\"left\" class=\"label_mark\" >\n";
   $salida .= "          ".$datos['prefijo']."-".$datos['numero'];
   $salida .= "          <input type=\"hidden\" id=\"prefijo\" name=\"prefijo\" value=\"".$datos['prefijo']."\">";
   $salida .= "          <input type=\"hidden\" id=\"numero\" name=\"numero\" value=\"".$datos['numero']."\">";
   $salida .= "          <input type=\"hidden\" id=\"empresa_id\" name=\"empresa_id\" value=\"".$datos['empresa_documento']."\">";
   $salida .= "          <input type=\"hidden\" id=\"bodegas_doc_id\" name=\"bodegas_doc_id\" value=\"".$datos['bodegas_doc_id']."\">";
   $salida .= "          <input type=\"hidden\" id=\"doc_tmp_id\" name=\"doc_tmp_id\" value=\"".$datos['doc_tmp_id']."\">";
   $direccion="app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/E008/imprimir/imprimir_docE008.php";
   $imagen = $path."/images/imprimir.png";
   $alt="IMPRIMIR DOCUMENTO";
   $imagen1 = "<sub><img src=\"".$imagen."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
   $salida .="<a title='".$alt."' href=\"javascript:Imprimir('".trim($direccion)."','".trim($datos['empresa_documento'])."','".trim($datos['prefijo'])."','".trim($datos['numero'])."');\">".$imagen1."</a>";
   $salida .= "      </td>\n";
   $salida .= "   </tr>\n";
   $salida .= "   <tr class=\"modulo_list_claro\">\n";
   $salida .= "      <td align=\"center\" class=\"modulo_table_list_title\">\n";
   $salida .= "         <a title='OBSERVACION PEDIDO'>OBSERVACION<a>";
   $salida .= "      </td>\n";
   $salida .= "      <td align=\"left\" class=\"label_mark\" colspan=\"3\">\n";
   $salida .= "			".$datos['observacion_despacho'];
   $salida .= "      </td>\n";
   $salida .= " </table>\n";
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



////////////////////////////////////////////////////////////////////////////////
function CrearDocumentosHtml($bodegas_doc_id,$dir,$tipo_doc_bodega_id,$nom_bodega,$utilidad,$bodega,$empresa_id)
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
  $documentos = $consulta->DocumentosDespacho_AFarmacia($empresa_id,$utilidad,$bodega);
  
  foreach($documentos as $key =>$valor)
  {
  $select .= "<option value=\"".trim($valor['documento'])."\">".$valor['despacho'].": ".$valor['observacion']."</option>";
  }
  
  
  $salida .= "          <br>\n";
  $salida .= "  <div id='errorcreartmp' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
  $salida .= "    <form name=\"unocreate\" action=\"".$accion1."\" method=\"post\">\n";
  $salida .= "    <div id=\"ventana1\">\n";
  $salida .= "      <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";
  $salida .= "        <td COLSPAN='1' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "        DOCUMENTOS :\n";
  $salida .= "       </td>\n";
  $salida .= "        <td COLSPAN='1' align=\"left\" class=\"modulo_list_claro\">\n";
  $salida .= "                         <select id=\"doc_despacho\" name=\"doc_despacho\" class=\"select\" onchange=\"\" style=\"width:100%\">\n";
  $salida .= "                          	<option value=\"0\" SELECTED>SELECCIONAR</option>\n";
  $salida .= "								".$select;
  $salida .= "                       </select>\n";
  $salida .= "       </td>\n";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td rowspan='1' colspan='8' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                         <fieldset>";
  $salida .= "                           <legend>OBSERVACIONES</legend>";
  $salida .= "                             <TEXTAREA id='obser' ROWS='2' COLS=55 ></TEXTAREA>\n";//OnFocus=\"this.blur()\"
  $salida .= "                         </fieldset>";
  $salida .= "                       </td>\n";
  $salida .= "                    </tr>\n";
  $salida .= "      <div align=\"center\" class=\"label_error\"id='tipo_teceros'></div>";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  COLSPAN='8' align=\"center\" class=\"modulo_list_claro\">\n"; 
  $salida .="<input type=\"button\" id=\"nuevo\" value=\"GRABAR DOCUMENTO\" class=\"input-bottom\" onClick=\"javascript:GrabarDocumento('".$bodegas_doc_id."',document.getElementById('obser').value,document.getElementById('doc_despacho').value);\">";
  $salida .= "                       </td>\n"; 
  $salida .= "                    </tr>\n";
  $salida .= "                 </table>";
  $salida .= "              </form>";
  $salida .= " <form name=\"volver\" action=\"".$dir."\" method=\"post\">\n";
  $salida .= "  <input  id='doc_tmp_id_h' type=\"hidden\" value=\"\">\n";
  $salida .= "  <input name='accion_h' id='accion_h' type=\"hidden\" value=\"\">\n";
  $salida .= "  <input id='tipo_clase' type=\"hidden\" value=\"".trim($tipo_doc_bodega_id)."\">\n";
  $salida .= "  <input id='bodegas_doc_id' type=\"hidden\" value=\"".trim($bodegas_doc_id)."\">\n";
  $salida .= "  <input id='nom_bodegax' type=\"hidden\" value=\"".trim($nom_bodega)."\">\n";
  $salida .= "  <input id='utility' type=\"hidden\" value=\"".trim($utilidad)."\">\n";
  $salida .= "  <input id='bodegax' type=\"hidden\" value=\"".trim($bodega)."\">\n";
  $salida .= " </form>\n";
  return $salida;
 }

/************************************************************************************
* COLOCAR LOS PRODUCTOS DEL DOCUMENTO
*********************************************************************************/ 

function ColocarProductos2500($bodegas_doc_id,$datos,$tmp_doc_id)
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
				xajax_MostrarProductox('".$bodegas_doc_id."','".$tmp_doc_id."','".UserGetUID()."');
               //MostrarProductoxjs('".$bodegas_doc_id."','".$tmp_doc_id."','".UserGetUID()."');
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
            $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"30\" onkeypress=\"return acceptm(event);\" onkeydown=\"recogerTeclaBus_1(event)\" value=\"\">\n";//
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

  $salida .= "               </div>";
  $salida .= "               <br>";
  $salida .= "               <div id='tablaoide'>\n";
  $salida .= "               </div>";
  return $salida;
 }

function ColocarProductos($bodegas_doc_id,$datos,$tmp_doc_id)
 {
    $consulta = new MovBodegasSQL();
    $usuariotmp=$consulta->Consultausuaritmp($tmp_doc_id,$bodegas_doc_id);
    $devolucion=$consulta->ConsultaDevolucion_doc($datos['tipo_doc_bodega_id'],$tmp_doc_id);
    
   
    
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
    $javaC .= "       xResizeTo(Capa, 700, 500);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/11, xScrollTop()+100);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 700, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarBus');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 600, 0);\n";
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
				xajax_MostrarProductox('".trim($bodegas_doc_id)."','".trim($tmp_doc_id)."','".trim(UserGetUID())."');
    </script>";
	
	$salida.= $javaC1;
	$salida .= "<script>";
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
	$salida .= "</script>";
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

   $parBusqueda=$consulta->ParmaBusquedaDoc($datos['empresa_id'],UserGetUID());
            $salida .= "                          <input type=\"hidden\" id=\"empresa_idz\" value=\"".trim($datos['empresa_id'])."\">\n";
            $salida .= "                          <input type=\"hidden\" id=\"centro_utilidadz\" value=\"".trim($datos['centro_utilidad'])."\">\n";
            $salida .= "                          <input type=\"hidden\" id=\"bodegaz\" value=\"".trim($datos['bodega'])."\">\n";
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
            $salida .= "                       <select id=\"tip_bus\" name=\"tip_bus\" class=\"select\" onchange=\"document.jukilo.criterio.value =''\">";
            $salida .= "                           <option value=\"1\" SELECTED>DESCRIPCION</option> \n";
            $salida .= "                           <option value=\"2\"># CODIGO</option> \n";
            if($parBusqueda['sw_codigobarras']==1)
              $salida .= "                         <option value=\"3\" SELECTED>CODIGO DE BARRAS</option> \n";
            if($parBusqueda['sw_nombremolecula']==1)
              $salida .= "                         <option value=\"4\" SELECTED>NOMBRE MOLECULA</option> \n";
            if($parBusqueda['sw_codigomolecula']==1)
              $salida .= "                         <option value=\"5\" SELECTED>CODIGO MOLECULA</option> \n";
            if($parBusqueda['sw_nombrelaboratorio']==1)
              $salida .= "                         <option value=\"6\" SELECTED>NOMBRE LABORATORIO</option> \n";
            if($parBusqueda['sw_codigolaboratorio']==1)
              $salida .= "                           <option value=\"7\" SELECTED>CODIGO LABORATORIO</option> \n";
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
	$salida .= " <br>";
  
    $fech_vencmodulo=ModuloGetVar('app','AdministracionFarmacia','dias_vencimiento_product_bodega_farmacia_02');
    $ctl = AutoCarga::factory("ClaseUtil");
    $fecha_total=$ctl->sumaDia($fecha,$fech_vencmodulo);
    $estadosEmpresa=$consulta->ConsultaEmpresa($_SESSION['EMPRESAS']['empresa_id']);
    $salida .= " <table align=\"center\" width=\"30%\" class=\"modulo_table_list\">";
     $salida .= "      <tr>\n";
	if($estadosEmpresa['sw_estados']==1)
    {
     $salida .= " <td  align=\"left\" class=\"modulo_table_list_title\">\n";
     $salida .= "   ESTADO";
     $salida .= " </td>\n";
     $salida .= " <td  align=\"left\" class=\"modulo_list_claro\">";
     $estadostmp=$consulta->ConsultaEstadosTmp($_SESSION['SYSTEM_USUARIO_ID'],$tmp_doc_id);    
     $sw_verificono=$consulta->ConsultaSw_verificar($datos['tipo_doc_bodega_id'],$tmp_doc_id);
     $documentos2=$consulta->ConsultaEstadosPermisosp($datos['tipo_doc_bodega_id'],$tmp_doc_id);
     $si_esta=$consulta->ConsultaPardocg($tmp_doc_id);
     $tipo_documento=$datos['tipo_doc_bodega_id'];
     $salida .= "   <select style=\"width:50%\" class=\"select\" name=\"estados\" id=\"estados\" onchange=\"ActuEstado($bodegas_doc_id,$tmp_doc_id,document.getElementById('estados').value,'$tipo_documento')\">";
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
   }
   else
   {
    $salida .= "           <td width='10%'  align=\"left\" class=\"modulo_list_claro\">\n";
    $salida .= "            </td>\n";
   }
   $salida .= "      </tr>\n";
  $salida .= " </table>";
  $salida .= " <br>";
  $salida .= "<center><label id=\"error\" class=\"label_error\"></label></center>";
   $salida .= " <table align=\"center\" width=\"50%\" class=\"modulo_table_list\">";
   $salida .= "      <tr>\n";
   $salida .= "         <td class=\"modulo_table_list_title\">\n";
   $salida .= "         CODIGO BARRAS";
   $salida .= "         </td>\n";
   $salida .= "         <td>\n";
   $salida .= "         <input type=\"text\"  readonly=\"true\"  class=\"input-text\" onkeypress=\"return recogerTeclab_1(event);\" id=\"codigo_barras\" style=\"width:100%\">";
   $salida .= "         </td>\n";
   $salida .= "         <td class=\"modulo_table_list_title\">\n";
   $salida .= "         DESCRIPCION";
   $salida .= "         </td>\n";
   $salida .= "         <td>\n";
   $salida .= "         <input type=\"text\"  readonly=\"true\"  class=\"input-text\" onkeypress=\"return recogerTeclab_1(event);\" id=\"descripcion\" style=\"width:100%\">";
   $salida .= "         </td>\n";
   $salida .= "      </tr>\n";
   $salida .= "     </table>";
	


  $path = SessionGetVar("rutaImagenes");
  $salida .= "          <br>\n";
  $salida .= "    <div id='error_doc' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
  $salida .= "               <div id=\"ventana1\">\n";
  $salida .= "						<div id=\"ProductosDocumento\"></div>";
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
  $salida .= "				<script>";
  $salida .= "					xajax_ListadoProductos(document.getElementById('empresa_id').value,document.getElementById('prefijo').value,document.getElementById('numero').value,document.getElementById('codigo_barras').value,document.getElementById('descripcion').value,document.getElementById('bodegas_doc_id').value,document.getElementById('doc_tmp_id').value,'1');";
  $salida .= "				</script>";
  return $salida;
 }
 
/* function RetornarImpresionDoc($direccion,$alt,$imagen,$empresa_id,$prefijo,$numero)
 {    
    global $VISTA;
    $imagen1 = "<sub><img src=\"".$imagen."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
    $salida1 ="<a title='".$alt."' href=javascript:Imprimir('$direccion','$empresa_id','$prefijo','$numero')>".$imagen1."</a>";
    return $salida1;
 }*/
}
?>