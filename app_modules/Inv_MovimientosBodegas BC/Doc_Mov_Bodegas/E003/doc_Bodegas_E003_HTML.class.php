<?php
	/**************************************************************************************  
	* $Id: doc_Bodegas_E003_HTML.class.php,v 1.2 2011/06/14 19:58:06 mauricio Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.2 $ 
	* 
	* @autor Jaime G�ez 
	***************************************************************************************/

include "app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/E003/doc_Bodegas_E003.class.php";
//include "app_modules/Inv_MovimientosBodegas/classes/MovBodegasSQL.class.php";
//include_once "app_modules/Inv_MovimientosBodegas/pruebaDocBodega.php";
//IncludeClass('RecaudoElectronico',null,'app','RecibosCaja');
class doc_bodegas_E003_HTML
{
 function doc_bodegas_E003_HTML(){}

     
/*********************************************************************************
 Cabecera
*********************************************************************************/ 
function FormaDocumento($DATOS)
{    //var_dump($DATOS);
     $objeto=new Classmodules();
     $file ='app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/E003/RemoteXajax/definirBodegas_E003.php';
     $objeto->SetXajax(array("Subtimit","CrearDocumentoFinalx","BorrarTmpAfirmativo1","MostrarProductox","Borrar","BorrarAjuste","GuardarPT",
							"BuscarProducto1","ObtenerPaginadoPro","GuardarTmpDoc","Cuadrar_ids_terceros",
							"CrearUSA","Buscadorter","ObtenerPaginadoter","Departamento2","Municipios",
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
      $consulta = new doc_bodegas_E003();
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
     
	  $salida .= ThemeAbrirTabla("TIPO DE DOCUMENTO -".$DATOS['tipo_doc_bodega_id']);
      $consulta = new doc_bodegas_E003();
      $datox=$consulta->DatosParaEditar($DATOS['doc_tmp_id'],UserGetUID());
      $salida .= $this->PintarTabla($datox);
	  
      $datos = $consulta->TraerDatos($DATOS['bodegas_doc_id']);
	  $datos_adicionales = $consulta ->TraerGetDocTemporal($DATOS['bodegas_doc_id'],$DATOS['doc_tmp_id']);
	  /*print_r($datos_adicionales);*/
      $consulta1 = new MovBodegasSQL();
      $si_esta=$consulta1->ConsultaPardocg($DATOS['doc_tmp_id'],$DATOS['tipo_doc_bodega_id']);
      $param_estados=$consulta1->ConsultaEstadosPermisos($DATOS['tipo_doc_bodega_id']);
      if(empty($si_esta))
      {
        foreach ($param_estados as $indice=>$valor)
        { 
          $guadarpar=$consulta1->GuardarParGrabar($DATOS['tipo_doc_bodega_id'],$valor['abreviatura'],$DATOS['doc_tmp_id']);
        }
      }
		
		/*
		Consulta del tipo de prestamo del documento
		*/
	  $tipo_prestamo=$consulta->TraerTipoPrestamo($DATOS['doc_tmp_id'],UserGetUID());
    //print_r($tipo_prestamo);
    $tipo_prestamo_id = $tipo_prestamo[0]['tipo_prestamo_id'];
	  $tercero_id = $tipo_prestamo[0]['tercero_id'];
	  $tipo_id_tercero = $tipo_prestamo[0]['tipo_id_tercero'];
	  
	  	  
	  $Result=$consulta->CodigoProveedor($tercero_id,$tipo_id_tercero);
	  $CodigoProveedorId=$Result[0]['codigo_proveedor_id'];
	  //print_r($Result);
	     
	  
		/*
		* Es el que carga la capa del Buscador.
		*/
      $salida .=$this->ColocarProductos($DATOS['bodegas_doc_id'],$datos,$DATOS['doc_tmp_id'],$tipo_prestamo_id,$CodigoProveedorId,$tercero_id,$tipo_id_tercero,$datox['empresa_id'],$datos_adicionales);
      $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('nom_bodegax'=>$DATOS['nom_bodegax'],'utility'=>$DATOS['utility'],'bodegax'=>$DATOS['bodegax']));
      $salida .= " <form name=\"volver1\" action=\"".$BODEGA."\" method=\"post\">\n";//".$this->action[0]."
      $salida .= "  <table align=\"center\" width=\"50%\">\n";
      $salida .= "    <tr>\n";
      
      //print_r($datox);
      if(!empty($datox))
      {
       $usuariotmp=$consulta1->Consultausuaritmp($datox['doc_tmp_id'],$datox['bodegas_doc_id']);
       $documentos=$consulta1->ConsultaPardocg($datox['doc_tmp_id']);
       $estadosEmpresa=$consulta1->ConsultaEmpresa($datox['empresa_id']);
       $contar=count($documentos); 
       
       $salida .= "       <td id='SUTANO' align=\"center\" colspan='7'>\n";
       $salida .= "          <input type=\"button\" id='ELI' class=\"input-submit\" value=\"ELIMINAR DOCUMENTO\" onclick=\"EliminarDocu('".$DATOS['doc_tmp_id']."','".$DATOS['bodegas_doc_id']."');\">\n";
       $salida .= "       </td>\n"; 
       //$salida .= "<pre>".print_r($contar,true)."</pre>";
       $salida .= "       <td align=\"center\" colspan='7'>\n";
       $salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
       $salida .= "       </td>\n";
      
      $ContadorVerificaciones=0;
       /*if($estadosEmpresa['sw_estados']==1)
       {
         foreach($documentos as $llave => $Estados)
               {
              if($Estados['sw_verifico']=='1')
               $ContadorVerificaciones++;
               //echo "[".$Estados['sw_verifico']."]";
               }*/
        
        //print_r($documentos);
         
        /* if($usuariotmp['usuario_id']==UserGetUID()&&$ContadorVerificaciones==$contar)
         {
          $salida .= "       <td id='MENGANO' align=\"center\"  colspan='7'>\n";
          $salida .= "          <input type=\"button\" id='enar' class=\"input-submit\" value=\"CREAR DOCUMENTO\" DISABLED onclick=\"CrearDocuFinal('".$DATOS['bodegas_doc_id']."','".$DATOS['doc_tmp_id']."','".$DATOS['tipo_doc_bodega_id']."','".$tipo_prestamo_id."','".$CodigoProveedorId."','".$tercero_id."','".$tipo_id_tercero."','".$datox['empresa_id']."','".$datox['centro_utilidad']."','".$datox['bodega']."',document.getElementById('num_factura').value);\">\n";
          $salida .= "       </td>\n";
         }
         else
         {
          $salida .= "       <td id='MENGANO' align=\"center\"  colspan='7'>\n";
          $salida .= "          <input type=\"button\" id='enar' class=\"input-submit\" value=\"CREAR DOCUMENTO\" DISABLED onclick=\"CrearDocuFinal('".$DATOS['bodegas_doc_id']."','".$DATOS['doc_tmp_id']."','".$DATOS['tipo_doc_bodega_id']."');\">\n";
          $salida .= "       </td>\n";
         }*/
		
		
		/*Si el Documento està autorizado Para el Ajuste, aparaece el Botòn, de resto nop!*/
		
		if($datos_adicionales['autorizado']=="1")
		{
		$salida .= "       <td id='MENGANO' align=\"center\"  colspan='7'>\n";
		$salida .= "          <input type=\"button\" id='enar' class=\"input-submit\" value=\"CREAR DOCUMENTO\" onclick=\"CrearDocuFinal('".$DATOS['bodegas_doc_id']."','".$DATOS['doc_tmp_id']."','".$DATOS['tipo_doc_bodega_id']."');\">\n";
		$salida .= "       </td>\n";
		}
		 
         $salida .= "       <td id='MENGANO' align=\"center\"  colspan='7'>\n";
         $salida .= "          <input type=\"hidden\" id='devolver' class=\"input-submit\" value=\"DEVOLVER\" onclick=\"Devolver('".$DATOS['tipo_doc_bodega_id'] ."','".$DATOS['doc_tmp_id']."','".$_['EMPRESAS']['empresa_id']."');\">\n";
         $salida .= "       </td>\n";
          
      /* }*/
      /* else
       {
         $salida .= "       <td id='MENGANO' align=\"center\"  colspan='7'>\n";
         $salida .= "          <input type=\"button\" id='enar' class=\"input-submit\" value=\"CREAR DOCUMENTO\" onclick=\"CrearDocuFinal('".$DATOS['bodegas_doc_id']."','".$DATOS['doc_tmp_id']."','".$DATOS['tipo_doc_bodega_id']."');\">\n";
         $salida .= "       </td>\n";
       }*/
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
    $salida.="<div id='ContenedorMov1' class='d2Container' style=\"display:none\">";//style=\"100%;width:95%;height:300px;overflow:scroll;display:none;\"></
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
  IncludeClass("CalendarioHtml");
  $salida .= "          <br>\n";

  $salida .= "       <div id='errorcreartmp' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
  $salida .= "            <form name=\"unocreate\" action=\"".$accion1."\" method=\"post\">\n";
  $salida .= "               <div id=\"ventana1\">\n";
  $salida .= "                 <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";
  $salida .= "                    <tr  class=\"modulo_list_claro\">\n";
  $salida .= "							<td>";
  $salida .= "								COORDINADOR O AUXILIAR ESTABLECIMIENTO";
  $salida .= "							</td>";
  $salida .= "							<td>";
  $salida .= "								<input type=\"text\" name=\"coordinador_auxiliar\" id=\"coordinador_auxiliar\" class=\"input-text\" style=\"width:100%\">";
  $salida .= "							</td>";
  $salida .= "                    </tr>\n";
  $salida .= "                    <tr  class=\"modulo_list_claro\">\n";
  $salida .= "							<td>";
  $salida .= "								AUDITOR GESTION CONTROL INTERNO";
  $salida .= "							</td>";
  $salida .= "							<td>";
  $salida .= "								<input type=\"text\" name=\"control_interno\" id=\"control_interno\" class=\"input-text\" style=\"width:100%\">";
  $salida .= "							</td>";
  $salida .= "                    </tr>\n";
  $salida .= "                    <tr  class=\"modulo_list_claro\">\n";
  $salida .= "							<td>";
  $salida .= "								FECHA SELECTIVO";
  $salida .= "							</td>";
  $salida .= "							<td>";
  $salida .= "								<input type=\"text\" name=\"fecha_selectivo\" id=\"fecha_selectivo\" class=\"input-text\" style=\"width:50%\">".ReturnOpenCalendario('unocreate','fecha_selectivo','-')."\n";
  $salida .= "							</td>";
  $salida .= "                    </tr>\n";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td rowspan='1' colspan='6' align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                         <fieldset>";
  $salida .= "                           <legend>OBSERVACIONES</legend>";
  $salida .= "                             <TEXTAREA id='obser' ROWS='2' COLS=55 ></TEXTAREA>\n";//OnFocus=\"this.blur()\"
  $salida .= "                         </fieldset>";
  $salida .= "                       </td>\n";
  $salida .= "                    </tr>\n";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  COLSPAN='8' align=\"center\" class=\"modulo_list_claro\">\n";                          //GrabarDocumento(bodegas_doc_id, observacion,centro,bodega)
  $salida .="<input type=\"button\" id=\"nuevo\" value=\"GRABAR DOCUMENTO\" class=\"input-bottom\" onClick=\"javascript:GrabarDocumento('".$bodegas_doc_id."',document.getElementById('obser').value,document.getElementById('coordinador_auxiliar').value,document.getElementById('control_interno').value,document.getElementById('fecha_selectivo').value);\">";
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
    /*$javaC .= "   function Iniciar4(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorBus';\n";
    $javaC .= "       titulo1 = 'tituloBus';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 800, 700);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/11, xScrollTop()+100);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 800, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarBus');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 800, 0);\n";
    $javaC .= "   }\n";*/

    $javaC .= "   function IniciarUsu(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorCre';\n";
    $javaC .= "       titulo1 = 'tituloCre';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 600, 380);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+65);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 580, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarCre');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 580, 0);\n";
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
*Ventana emergente 3 aqui es cuando se BVSCA UN PRODUCTO
**********************************************************************************/
    $salida.="<div id='ContenedorBus' class='d2Container' style=\"display:none\">";
    $salida .= "    <div id='tituloBus' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $salida .= "    <div id='cerrarBus' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorBus');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $salida .= "    <div id='errorBus' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $salida .= "    <div id='ContenidoBus' >\n";
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
 }

function ColocarProductos($bodegas_doc_id,$datos,$tmp_doc_id,$tipo_prestamo,$CodigoProveedorId,$tercero_id,$tipo_id_tercero,$empresa_id,$datos_adicionales)
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
    $salida .= "    <div id='ContenidoBus' >\n";
    /****************************************************************************/
    /*
    * Principal Buscador
    */
	$salida .= "                          <input type=\"hidden\" id=\"empresa_idz\" value=\"".$datos['empresa_id']."\">\n";
	$salida .= "                          <input type=\"hidden\" id=\"centro_utilidadz\" value=\"".$datos['centro_utilidad']."\">\n";
	$salida .= "                          <input type=\"hidden\" id=\"bodegaz\" value=\"".$datos['bodega']."\">\n";
	$salida .= "                          <input type=\"hidden\" id=\"bodegas_doc_id\" value=\"".$bodegas_doc_id."\">\n";
	$salida .= "                          <input type=\"hidden\" id=\"tmp_doc_id\" value=\"".$tmp_doc_id."\">\n";
   	/*
	* Este es el buscador que se encuentra en la Capita que usa Xajax
	*/
	
					
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
			$salida .= "                       <select id=\"tip_bus\" name=\"tip_bus\" class=\"select\" >"; /*onchange=\"Aplicar(this.value)\"*/
			$salida .= "                           <option value=\"1\" SELECTED>DESCRIPCION</option> \n";
			$salida .= "                           <option value=\"2\"># CODIGO</option> \n";
			$salida .= "                       </select>\n";
			$salida .= "                         <input type=\"hidden\" id=\"tipo_prestamo_id\" name=\"tipo_prestamo_id\" value=\"".$tipo_prestamo."\">\n";//

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
  $salida .= "    <div id='error_doc' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
  $salida .= "               <div id=\"ventana1\">\n";
  $salida .= "                 <form name=\"jukilo4\"  method=\"post\">\n";
  $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
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
  
  $java = "javascript:MostrarCapa('ContenedorBus');Bus_Pro('".trim($datos['empresa_id'])."','".trim($datos['centro_utilidad'])."','".trim($datos['bodega'])."','0','0','".trim($bodegas_doc_id)."','".trim($tmp_doc_id)."','1');Iniciar4('BUSCAR PRODUCTO');Clear3000();\"";
  $salida .= "                         <input type=\"hidden\" value=\"0\" class=\"input-text\" id=\"num_factura\" name=\"num_factura\">\n";
  $salida .= "                         <a title='BUSCADOR PRODUCTO' class=\"label_error\" href=\"".$java."\">\n";
  $salida .= "                          BUSCAR PRODUCTO AJUSTE\n";
  $salida .= "                         </a>\n";
  
  
  
  $salida .= "                       </td>\n";
  $salida .= "                      </tr>\n";
  
  $salida .= "      <tr>\n";
  $salida .= "       <td  width='10%' align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "         V/CTO";
  $salida .= "       </td>\n";
  $fecha = date("Y-m-d");
  $salida .= " <td  width='10%'  align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "<div id='fecha_vencimiento'></div>";
  $salida .= "      <input type=\"hidden\" class=\"input-text\"  name=\"fecha_venc\" id=\"fecha_venc\" maxlength=\"20\" style=\"width:100%;height:100%\" value=\"\">\n";
  $salida .= "      <input type=\"hidden\"  name=\"token\" id=\"token\"  value=\"\">\n";
  $salida .= "                        ";
  //$salida .= "<pre>".print_r($devolucion,true)."</pre>";
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
    $documentos2=$consulta->ConsultaEstadosPermisosp($datos['tipo_doc_bodega_id'],$tmp_doc_id);
    $si_esta=$consulta->ConsultaPardocg($tmp_doc_id,$datos['tipo_doc_bodega_id']);
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
    //print_r($sw_verificono);
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
     $salida .= " </td>";
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
  $salida .= "                       <td  align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "                          <input type=\"text\" id=\"cantidad\" size='10' class=\"input-text\" value=\"\" onkeypress=\"return acceptNum(event);\" onclick=\"Clear3000();\">\n";
  $salida .= "                          <input type=\"hidden\" id=\"costeno_val\"  value=\"0\">\n";
  $salida .= "                       </td>\n";
  $salida .= "						<tr>";
  $salida .= "							<td class=\"modulo_table_list_title\">";
  $salida .= "								JUSTIFICACION";
  $salida .= "							</td>";
  $salida .= "							<td colspan=\"7\">";
  $salida .= "								<textarea id=\"justificacion\" name=\"justificacion\" style=\"width:100%\" class=\"textarea\"></textarea>";
  $salida .= "							</td>";
  $salida .= "						</tr>";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  COLSPAN='8' align=\"center\" class=\"modulo_list_claro\">\n";                          //                         $doc_tmp_id,                                            $codigo_producto,                     $cantidad,                                       $porcentaje_gravamen[document.getElementById('gravamen').value],$total_costo[document.getElementById('op22').value],                   $usuario_id=null
  $salida .= "                          <input type=\"button\" id=\"nuevo\" value=\"SELECCIONAR PRODUCTO\" class=\"input-bottom\" onClick=\"javascript:GuardarProductoTemporal('".$bodegas_doc_id."','".$tmp_doc_id."',document.getElementById('codigo').value,document.getElementById('cantidad').value,'0',document.getElementById('costeno_val').value,'".UserGetUID()."',document.getElementById('fecha_venc').value,document.getElementById('lotec').value,document.getElementById('justificacion').value);\">";
  $salida .= "                       </td>\n"; 
  $salida .= "                    </tr>\n";
  $salida .= "                 </table>";
  $salida .= "                </form>\n";
  $salida .= "               </div>";
  $salida .= "               <br>";
  $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
  $salida .= "						<tr class=\"modulo_table_list_title\">";
  $salida .= "							<td colspan=\"4\">";
  $salida .= "								SELECTIVO DE MEDICAMENTOS Y DISPOSITIVOS MEDICOS EN DROGUERIAS, SERVICIOS FARMACEUTICOS Y BODEGAS";
  $salida .= "							</td>";
  $salida .= "						</tr>";
  $salida .= "						<tr class=\"modulo_table_list_title\">";
  $salida .= "							<td>";
  $salida .= "								FECHA SELECTIVO";
  $salida .= "							</td>";
  $salida .= "							<td class=\"modulo_list_claro\">";
  $salida .= "								".$datos_adicionales['fecha_selectivo'];
  $salida .= "							</td>";
  $salida .= "							<td>";
  $salida .= "								COORDINADOR O AUXILIAR EXTABLECIMIENTO";
  $salida .= "							</td>";
  $salida .= "							<td class=\"modulo_list_claro\">";
  $salida .= "								".$datos_adicionales['coordinador_auxiliar'];
  $salida .= "							</td>";
  $salida .= "						</tr>";
  $salida .= "						<tr class=\"modulo_table_list_title\">";
  $salida .= "							<td>";
  $salida .= "								NOMBRE ESTABLECIMIENTO";
  $salida .= "							</td>";
  $salida .= "							<td class=\"modulo_list_claro\">";
  $salida .= "								".$datos_adicionales['establecimiento'];
  $salida .= "							</td>";
  $salida .= "							<td>";
  $salida .= "								AUDITOR GESTION CONTROL INTERNO";
  $salida .= "							</td>";
  $salida .= "							<td class=\"modulo_list_claro\">";
  $salida .= "								".$datos_adicionales['control_interno'];
  $salida .= "							</td>";
  $salida .= "						</tr>";
  $salida .= "                 </table>\n";
  
  
  
  $salida .= "				<br>";
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