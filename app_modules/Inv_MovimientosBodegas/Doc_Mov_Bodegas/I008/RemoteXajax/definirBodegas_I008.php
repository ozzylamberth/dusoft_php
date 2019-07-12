<?php
	/**************************************************************************************
	* $Id: definirBodegas_I008.php,v 1.1 2009/07/17 19:08:17 johanna Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Mauricio Medina
	**************************************************************************************/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	//include "../../../app_modules/InvTomaFisica/classes/TomaFisicaSQL.class.php";
	include "../../../app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I008/doc_Bodegas_I008.class.php";
  include "../../../classes/ClaseHTML/ClaseHTML.class.php";

//
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

/****************************************************************
* mostrar datos a l editar una tabla
******************************************************************/
function CrearDocumentoFinalx($bodegas_doc_id,$doc_tmp_id,$tipo_doc_bodega_id)
{   $path = SessionGetVar("rutaImagenes");
    $consulta=new MovBodegasSQL();
    //$consulta1 =new definirBodegas_I008();
    //$Tmp_inv_movimientos=    
    $objResponse = new xajaxResponse();
    $productos=$consulta->CrearDocumentoOriginal($bodegas_doc_id,$doc_tmp_id,UserGetUID());
    //var_dump($buscar);
    if(!empty($productos))
    {
            $salida .= "                  <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td align=\"center\" width=\"12%\">\n";
            $salida .= "                        <a title='DOCUMENTO ID'>DOC ID<a> ";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"12%\">\n";
            $salida .= "                        <a title='PREFIJO-NUMERO'>PREFIJO<a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"40%\">\n";
            $salida .= "                        <a title='OBSERVACIONES DEL DOCUMENTO'>OBSERVACIONES<a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"27%\">\n";
            $salida .= "                        <a title='FECHA'>FECHA<a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"7%\">\n";
            $salida .= "                        <a title='ACCIONES SOBRE EL DOCUMENTO'>ACCIONES<a>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
                   
                    $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                    $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                    $salida .= "                        ".$productos['documento_id'];
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                    $salida .= "                         ".$productos['prefijo']."-".$productos['numero'];
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                    $salida .= "                        ".$productos['observacion'];
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
                    $salida .= "                         ".substr($productos['fecha_registro'],0,10);
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"center\">\n";
                                                      $direccion="app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I008/imprimir/imprimir_docI008.php";
                                                       $imagen = $path."/images/imprimir.png";
                                                       $alt="IMPRIMIR DOCUMENTO";
                                                       $x=RetornarImpresionDoc($direccion,$alt,$imagen,SessionGetVar("EMPRESA"),$productos['prefijo'],$productos['numero']);
                    $salida .= "                     ".$x."";                    $salida .= "                      </td>\n";
                    $salida .= "                    </tr>\n";
            
            
            $salida .= "                    </table>\n";




        $objResponse->assign("ventana1","innerHTML",$salida);
        $objResponse->call("superoff");
        $objResponse->assign("error_doc","innerHTML","SE HA CREADO EL DOCUMENTO EXITOSAMENTE");
        $borrardevo=$consulta->BorrarDevolucion_doc($tipo_doc_bodega_id,$doc_tmp_id);
        $borrarpara=$consulta->Borrarpara_docg($tipo_doc_bodega_id,$doc_tmp_id);
        $objResponse->assign("tablaoide","innerHTML","");
    }
    
    return $objResponse;
}

function BorrarTmpAfirmativo1($tmp,$bodega_doc_id)
{
      $consulta=new MovBodegasSQL();
      $objResponse = new xajaxResponse();
      $buscar=$consulta->EliminarDocTemporal($bodega_doc_id,$tmp,UserGetUID());
//       var_dump($buscar);
       if($buscar==1)
      {
        $objResponse->alert("EL DOCUMENTO TEMPORAL $tmp FUE ELIMINADO EXITOSAMENTE");
        $objResponse->call("AfirmaciondeEliminar");
      }
      else
      { $objResponse->alert("NO SE PUEDE BORRAR");
       } 
      
      return $objResponse;
}

function Devolver($tipo_doc_bodega_id,$doc_tmp_id,$empresa_id)
{
      $consulta=new MovBodegasSQL();
      //$productos=$consulta->CrearDocumentoOriginal($bodegas_doc_id,$doc_tmp_id,UserGetUID());
      $objResponse = new xajaxResponse();
      $productos=$consulta->Consultausuaritmp($doc_tmp_id,$bodegas_doc_id);
    
      $salida .= " <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "  <tr>\n";
      //$salida .= "<pre>".print_r($doc_tmp_id,true)."</pre>";
      $salida .= "     <td align=\"center\" width=\"30%\" class=\"modulo_table_list_title\">\n";
      $salida .= "      <a title='creador'>USUARIO<a> ";
      $salida .= "     </td>\n";
      $salida .= "     <td align=\"left\" class=\"normal_10AN\" class=\"modulo_list_claro\">\n";
      $salida .= "      ".UserGetUID()."";
      $salida .= "     </td>\n";
      $salida .= "   </tr>\n";
      $salida .= "   <tr>\n";
      $salida .= "     <td align=\"center\" width=\"30%\" class=\"modulo_table_list_title\">\n";
      $salida .= "       <a title='observacion'>OBSERVACION<a> ";
      $salida .= "     </td>\n";
      $salida .= "     <td align=\"left\" class=\"normal_10AN\" class=\"modulo_list_claro\">\n";
      $salida .= "       <input type=\"text\" class=\"input-text\"  name=\"observacion\" id=\"observacion\" maxlength=\"80\" style=\"width:100%;height:100%\" value=\"\">\n";
      $salida .= "     </td>\n";
      $salida .= "    </tr>\n";
      $salida .= "    <td id='MENGANO' align=\"center\"  colspan='7'>\n";
      $salida .= "      <input type=\"button\" id='enar' class=\"input-submit\" value=\"GUARDAR\"onclick=\"GuardarDevolucion('".$tipo_doc_bodega_id."','".$empresa_id."',document.getElementById('observacion').value,'".$doc_tmp_id."');\">\n";
      $salida .= "    </td>\n";
      $salida .= " </table>";
      $objResponse->assign("ventana1","innerHTML",$salida);
      return $objResponse;
}

function GuardarDevolucion($tipo_doc_bodega_id,$empresa_id,$observacion,$doc_tmp_id)
{
    $consulta=new MovBodegasSQL();
    $objResponse = new xajaxResponse();
    //print_r($tipo_doc_bodega_id);
    $guardar=$consulta->GuardarDevolucion($tipo_doc_bodega_id,$empresa_id,$observacion,$doc_tmp_id);
    
    return $objResponse;
}

function Actualizartmp($bodega_doc_id,$tmp,$estado,$tipo_documento)
{
      $consulta=new MovBodegasSQL();
      $objResponse = new xajaxResponse();
      $buscar=$consulta->ActuEstado($estado,UserGetUID(),$tmp,$tipo_documento);
      $sw_verificono=$consulta->ConsultaSw_verificar($tipo_documento,$tmp);
       $option_select .= " <option value=\"-1\">-- Seleccionar --</option>\n";
      // print_r($sw_verificono);
       foreach($sw_verificono as $indice=>$valor)
        {
          $option_select .= "  <option value=\"".$valor['abreviatura']."\" ".$selected.">".$valor['descripcion']."</option>";
        }
      $objResponse->assign("estados","innerHTML",$option_select);
      $objResponse->script("xajax_MostrarProductox('".$bodega_doc_id."','".$tmp."','".UserGetUID()."');");
      return $objResponse;
}
 
 
function MostrarProductox($bodegas_doc_id,$doc_tmp_id,$usuario_id)
{
  $objResponse = new xajaxResponse();
  $path = SessionGetVar("rutaImagenes");
  $consulta=new doc_bodegas_I008();
  $vector=$consulta->SacarProductosTMP($doc_tmp_id,$usuario_id);
  /*print_r($vector);*/
   /*
   Para Activar el Boton Crear Documento, en caso de Que existan productos en el temporal y Todos los estados esten activos
   */
   $consulta1 = new MovBodegasSQL();
    
    $usuariotmp=$consulta1->Consultausuaritmp($doc_tmp_id,$bodegas_doc_id);
    $documentos=$consulta1->ConsultaPardocg($doc_tmp_id);
    $estadosEmpresa=$consulta1->ConsultaEmpresa(SessionGetVar("EMPRESA"));
    $contar=count($documentos); 
    
    $ContadorVerificaciones=0;
    
       if($estadosEmpresa['sw_estados']==1)
       {
         foreach($documentos as $llave => $Estados)
               {
              if($Estados['sw_verifico']=='1')
               $ContadorVerificaciones++;
               }
                
         if($usuariotmp['usuario_id']==UserGetUID()&& $ContadorVerificaciones==$contar && !empty($vector))
         {
         $objResponse->script("document.getElementById('enar').disabled=false;");
        // $objResponse->Alert("No Bloqueo".$ContadorVerificaciones);
         }
         else
            {
            $objResponse->script("document.getElementById('enar').disabled=true;");
          //  $objResponse->Alert("Bloqueo".$ContadorVerificaciones);
            }
       }
    /*
    Fin Activacion del Boton Crear Documento
    */
	/*print_r($vector);*/
 if(!empty($vector))
 {
   $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
   $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
   $salida .= "                      <td align=\"center\" >\n";
   $salida .= "                        <a title='CODIGO DEL PRODUCTO'>CODIGO<a> ";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" >\n";
   $salida .= "                        <a title='DESCRIPCION DEL PRODUCTO'>DESCRIPCION<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" >\n";
   $salida .= "                        <a title='FECHA VENCIMIENTO'>FECHA VENCIMIENTO<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" >\n";
   $salida .= "                        <a title='LOTE'>LOTE<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" >\n";
   $salida .= "                        <a title='UNIDAD DEL PRODUCTO'>UNIDAD<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" >n";
   $salida .= "                        CANTIDAD";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" >\n";
   $salida .= "                        <a title='PORCENTAJE DEL GRAVAMEN'> % GRAVAMEN<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" >\n";
   $salida .= "                        <a title='COSTO TOTAL'>COSTO<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" >\n";
   $salida .= "                        <a title='ELIMINAR REGISTRO'>X<a>";
   $salida .= "                      </td>\n";
   $salida .= "                    </tr>\n";
   foreach($vector as $valor=>$productos)
   {

          $tr=$bodegas_doc_id."@".$productos['doc_tmp_id'];
          $salida .= "                    <tr id='".$tr."' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
          $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
          $salida .= "                        ".$productos['codigo_producto'];
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
          $salida .= "                         ".$productos['descripcion'];
          $salida .= "                      </td>\n";
          
          $fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.SessionGetVar("EMPRESA"));
          //$fech_vencmodulo=ModuloGetVar('app','AdministracionFarmacia','dias_vencimiento_product_bodega_farmacia_02');
          
          
          /*
          * Para Sacar los numeros de días entre fechas
         */      
          $fecha =$productos['fecha_vencimiento'];  //esta es la que viene de la DB
          list($ano,$mes,$dia) = split( '[/.-]', $fecha );
          $fecha = $mes."/".$dia."/".$ano;
          
          $fecha_actual=date("m/d/Y");
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
         
            $salida .= "                      <td ".$color." align=\"left\" class=\"label_mark\">\n";
            $salida .= "                         ".$productos['fecha_vencimiento'];
            $salida .= "                      </td>\n";  
         
          $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
          $salida .= "                         ".$productos['lote'];
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
          $salida .= "                        ".$productos['descripcion_unidad'];
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
          $salida .= "                         ".$productos['cantidad'];
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"right\">\n";
          $salida .= "                        ".$productos['porcentaje_gravamen'];
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
          /*$salida .= "                        ".FormatoValor($productos['total_costo']);*/
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"center\">\n";
		  /*
          $jaxx = "javascript:MostrarCapa('ContenedorB3');BorrarAjustes('".$bodegas_doc_id."','".$tr."','".$productos['item_id']."','ContenidoB3');IniciarB3('ELIMINAR REGISTRO');";
          $salida .= "                        <a title='ELIMINAR REGISTRO' href=\"".$jaxx."\">\n";
          $salida .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
          $salida .= "                         </a>\n";*/
          $salida .= "                      </td>\n";
          $salida .= "                    </tr>\n";
   }
   
   $salida .= "                    </table>\n";
   $objResponse->call("super");
 }
 else
 {
   $salida .= "                  <table width=\"80%\" align=\"center\">\n";
   $salida .= "                  <tr>\n";
   $salida .= "                  <td align=\"center\">\n";
   $salida .= "                      <label class='label_error'> ESTE DOCUMENTO NO TIENE PRODUCTOS ASIGNADOS</label>";
   $salida .= "                  </td>\n";
   $salida .= "                  </tr>\n";
   $salida .= "                  </table>\n";
   $objResponse->call("superoff");
 }
  $objResponse->assign("tablaoide","innerHTML",$salida);
  
  return $objResponse;

}
/***************************************************************
* ELIMINAR AJUSTES DE PRODUCTOS
***************************************************************/
function BorrarAjuste($bodegas_doc_id,$tr,$item)
{
      $consulta=new doc_bodegas_I008();
      $objResponse = new xajaxResponse();
      list($bodegas_doc_id,$doc_tmp_id) = explode("@",$tr);
      $buscar=$consulta->EliminarItem($doc_tmp_id,$item);
      
      
      if($buscar==1)
      {
        $objResponse->alert("EL ITEM $item FUE ELIMINADO EXITOSAMENTE");
        //$objResponse->remove($tr);
        $objResponse->script("xajax_MostrarProductox('".$bodegas_doc_id."','".$doc_tmp_id."','".UserGetUID()."');");    
        $objResponse->script("xajax_ListadoProductos(document.getElementById('empresa_id').value,document.getElementById('prefijo').value,document.getElementById('numero').value,document.getElementById('codigo_barras').value,document.getElementById('descripcion').value,document.getElementById('bodegas_doc_id').value,document.getElementById('doc_tmp_id').value,'1');");
      }
      else
      { $objResponse->alert("NO SE PUEDE BORRAR");
       }      
      return $objResponse;
}

function Borrar($bodegas_doc_id,$doc_tmp_id,$item,$CONTENIDOR)
{
      $objResponse = new xajaxResponse();
      $da .= "      <table width='100%' border='0'>\n";
      $da .= "       <tr>\n";
      $da .= "        <td colspan='2' class=\"label_error\">\n";
      $da .= "          ESTA SEGURO DE ELIMINAR ESTE PRODUCTO ?";
      $da .= "        </td>\n";
      $da .= "       </tr>\n";
      $da .= "       <tr>\n";
      $da .= "        <td align='center' colspan='2'>\n";
      $da .= "          &nbsp;";
      $da .= "        </td>\n";
      $da .= "       </tr>\n";
      $da .= "       <tr>\n";
      $da .= "        <td align='center'>\n";
      $C=substr($CONTENIDOR,(strlen($CONTENIDOR)-2),2);
      $da .= "          <input type=\"button\" class=\"input-submit\" value=\"ELIMINAR\" name=\"ELIMINAR_MOV\" onclick=\"xajax_BorrarAjuste('".$bodegas_doc_id."','".$doc_tmp_id."','".$item."');Cerrar('Contenedor".$C."');\">\n";
      $da .= "        </td>\n";
      $da .= "        <td align='center'>\n";
      $da .= "          <input type=\"button\" class=\"input-submit\" value=\"CANCELAR\" name=\"CANCELAR\" onclick=\"Cerrar('Contenedor".$C."');\">\n";
      $da .= "        </td>\n";
      $da .= "       </tr>\n";
      $da .= "      </table>\n";
      $objResponse->assign($CONTENIDOR,"innerHTML",$da);
      return $objResponse;
}  
function GuardarPT($bodegas_doc_id,$doc_tmp_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$usuario_id,$fecha_venc,$lotec)
{
  
  $objResponse = new xajaxResponse();
  //$objResponse->alert("codigo"); 
  $path = SessionGetVar("rutaImagenes");
  $consulta=new doc_bodegas_I008();
  $Retorno=$consulta->GuardarTemporal($bodegas_doc_id,$doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id=null,$fecha_venc,$lotec);
  $objResponse->script("xajax_MostrarProductox('".$bodegas_doc_id."','".$doc_tmp_id."','".UserGetUID()."');");
  return $objResponse;

}


function RecargarProductosTmp($doc_tmp_id,$usuario_id)
{
  
  $objResponse = new xajaxResponse();
  $consulta=new doc_bodegas_I008();
  $vector=$consulta->SacarProductosTMP($doc_tmp_id,$usuario_id);
  
  //var_dump($vector);
   $path = SessionGetVar("rutaImagenes");
   $salida .= "                  <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
   $salida .= "                    <tr class=\"modulo_table_list_title\">\n";

   $salida .= "                      <td align=\"center\" width=\"12%\">\n";
   $salida .= "                        <a title='CODIGO DEL PRODUCTO'>CODIGO<a> ";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"40%\">\n";
   $salida .= "                        <a title='DESCRIPCION DEL PRODUCTO'>DESCRIPCION<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"15%\">\n";
   $salida .= "                        <a title='FECHA VENCIMIENTO'>FECHA VENCIMIENTO<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"15%\">\n";
   $salida .= "                        <a title='LOTE'>LOTE<a>";
   $salida .= "                      </td>\n";
   //$salida .= "<pre>".print_r($vector,true)."</pre>";
  /* $salida .= "                      <td align=\"center\" width=\"12%\">\n";
   $salida .= "                        <a title='UNIDAD DEL PRODUCTO'>dsdUNIDAD<a>";
   $salida .= "                      </td>\n";*/
   $salida .= "                      <td align=\"center\" width=\"12%\">\n";
   $salida .= "                        CANTIDAD";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"12%\">\n";
   $salida .= "                        <a title='PORCENTAJE DEL GRAVAMEN'> % GRAVAMEN<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"20%\">\n";
   $salida .= "                        <a title='COSTO TOTAL'>COSTO<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"2%\">\n";
   $salida .= "                        <a title='ELIMINAR REGISTRO'>X<a>";
   $salida .= "                      </td>\n";
   $salida .= "                    </tr>\n";
   
   foreach($vector as $valor=>$productos)
   {
//       foreach($productos as $valor1=>$productos1)
//       {
          $tr=$bodegas_doc_id."@".$productos['doc_tmp_id'];
          $salida .= "                    <tr id='".$tr."' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
          $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
          $salida .= "                        ".$productos['codigo_producto'];
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
          $salida .= "                         ".$productos['descripcion'];
          $salida .= "                      </td>\n";
          $fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.SessionGetVar("EMPRESA"));
          //$fech_vencmodulo=ModuloGetVar('app','AdministracionFarmacia','dias_vencimiento_product_bodega_farmacia_02');
          
          
          /*
          * Para Sacar los numeros de días entre fechas
         */      
          $fecha =$productos['fecha_vencimiento'];  //esta es la que viene de la DB
          list($ano,$mes,$dia) = split( '[/.-]', $fecha );
          $fecha = $mes."/".$dia."/".$ano;
          
          $fecha_actual=date("m/d/Y");
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
         
            $salida .= "                      <td ".$color." align=\"left\" class=\"label_mark\">\n";
            $salida .= "                         ".$productos['fecha_vencimiento'];
            $salida .= "                      </td>\n";
          
          $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
          $salida .= "                         ".$productos['lote'];
          $salida .= "                      </td>\n";
          /*$salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
          $salida .= "                        ".$productos['descripcion_unidad'];
          $salida .= "                      </td>\n";*/
          $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
          $salida .= "                         ".$productos['cantidad'];
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"right\">\n";
          $salida .= "                        ".$productos['porcentaje_gravamen'];
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
          $salida .= "                        ".FormatoValor($productos['total_costo']);
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"center\">\n";
          $jaxx = "javascript:MostrarCapa('ContenedorB3');BorrarAjustes('".$doc_tmp_id."','".$productos['item_id']."','ContenedorB3');IniciarB3('ELIMINAR REGISTRO');";
          $salida .= "                        <a title='ELIMINAR REGISTRO' href=\"".$jaxx."\">\n";
          $salida .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
          $salida .= "                         </a>\n";
          $salida .= "                      </td>\n";
          $salida .= "                    </tr>\n";
      //}
   }

   $salida .= "                    </table>\n";
  $objResponse->assign("tablaoide","innerHTML",$salida);
  return $objResponse;

}


/************************************************
* funcion pra buscar productos
*************************************************/

function BuscarProducto1($empresa_id,$centro_utilidad,$bodega,$tip_bus,$criterio,$offset)
{                       
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta=new doc_bodegas_I008();
   //echo $tip_bus; 
    if($tip_bus==2)
    {
      $aumento="AND b.codigo_producto='".$criterio."' ";
      $aumento2="";
    }
    elseif($tip_bus==1)
    {
      $aumento="AND b.descripcion ILIKE '%".strtoupper($criterio)."%' ";
      $aumento2="";
    }
    elseif($tip_bus==3)
    {
      $msq = new MovBodegasSQL();
      $msq->RegistrarBusqueda(UserGetUID(),$empresa_id);
      
      $aumento="AND b.codigo_barras ILIKE '%".$criterio."%' ";
      $aumento2="";
    }
    elseif($tip_bus==4)
    {
      $aumento2="AND f.descripcion ILIKE '%".$criterio."%' ";
    }
    elseif($tip_bus==5)
    {
      $aumento2="AND z.molecula_id ILIKE '%".$criterio."%' ";
    }
    elseif($tip_bus==6)
    {
      $aumento2="AND h.descripcion ILIKE '%".$criterio."%' ";
    }
    elseif($tip_bus==7)
    {
      $aumento2="AND g.laboratorio_id =".$criterio." ";
    }
    else
    {
      $aumento="";
    }

    if($criterio != "0" && $criterio != "")
    {
      $colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
      $colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');
      $fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.SessionGetVar("EMPRESA"));
      $fecha_actual=date("m/d/Y");
      $busqueda=$consulta->BuscarProducto($empresa_id,$centro_utilidad,$bodega,$aumento,$aumento2,$offset);
      if(!empty($busqueda))
      {             
               $salida .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
               //$salida .=                     $busqueda;
                    //codigo_producto descripcion unidad_id descripcion_unidad
               $salida .= "                 </div>\n";
               $salida .= "                 <form name=\"adicionar\">\n";
               $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
               $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
               $salida .= "                      <td align=\"center\"width=\"15%\">\n";
               $salida .= "                        CODIGO PRODUCTO";
               $salida .= "                      </td>\n";
               //$salida .= "<pre>".print_r($criterio,true)."</pre>";
               $salida .= "                      <td align=\"center\" width=\"40%\">\n";
               $salida .= "                        <a title='DESCRIPCION PRODUCTO'>DESCRIPCION<a> ";
               $salida .= "                      </td>\n";
               /*$salida .= "                      <td align=\"center\" width=\"10%\">\n";
               $salida .= "                        UNIDAD";
               $salida .= "                      </td>\n";*/
               $salida .= "                      <td align=\"center\" width=\"10%\">\n";
               $salida .= "                        EXISTENCIA";
               $salida .= "                      </td>\n";
               $salida .= "                      <td align=\"center\" width=\"10%\">\n";
               $salida .= "                        COSTO";
               $salida .= "                      </td>\n";  
               $salida .= "                      <td align=\"center\" width=\"10%\">\n";
               $salida .= "                        FECHA VEN";
               $salida .= "                      </td>\n"; 
               $salida .= "                      <td align=\"center\" width=\"10%\">\n";
               $salida .= "                        LOTE";
               $salida .= "                      </td>\n"; 
               /*$salida .= "                      <td align=\"center\" width=\"10%\">\n";
               $salida .= "                        CONCEN";
               $salida .= "                      </td>\n";
               $salida .= "                      <td align=\"center\" width=\"10%\">\n";
               $salida .= "                        LABO";
               $salida .= "                      </td>\n";               */
               $salida .= "                      <td align=\"center\" width=\"3%\">\n";
               $salida .= "                        <a title='SELECCIONAR PRODUCTO'>SL<a>";
               $salida .= "                      </td>\n";
               $salida .= "                    </tr>\n";         
                for($i=0;$i<count($busqueda);$i++)
                {   
                  $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                        ".$busqueda[$i]['codigo_producto'];
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                  $salida .= "                        ".$busqueda[$i]['descripcion'];
                  $salida .= "                      </td>\n";
                  /*$salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                  $salida .= "                         ".$busqueda[$i]['descripcion_unidad'];
                  $salida .= "                      </td>\n";*/
                  $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
                  $salida .= "                         ".$busqueda[$i]['existencia'];
                  $salida .= "                      </td>\n"; 
                  $salida .= "                      <td align=\"right\">\n";
                  $salida .= "                         <a title='COSTO PROMEDIO'>\n";
                  $salida .= "                         ".$busqueda[$i]['costo'];
                  $salida .= "                         </a>\n";  
                  $salida .= "                      </td>\n"; 
                  
                  $fechaven=explode("-",$busqueda[$i]['fecha_vencimiento']);
                        $fechavencimiento=$fechaven[2]."-".$fechaven[1]."-".$fechaven[0];
                        
                        $fecha =$busqueda[$i]['fecha_vencimiento'];  //esta es la que viene de la DB
                        list($ano,$mes,$dia) = split( '[/.-]', $fecha );
                        $fecha = $mes."/".$dia."/".$ano;
                    
                        $fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
                        $fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
                        
                    //Mes/Dia/Año  "02/02/2010
                        $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
                          $color ="";
                          
                            if($int_nodias<$fech_vencmodulo)
                            {
                              $color = "style=\"background:".$colores['PV']."\"";
                            }
                            
                            
                            if($fecha_dos<=$fecha_uno_act)
                                  {
                                  $color = "style=\"background:".$colores['VN']."\"";
                                  }
                  
                  $salida .= "                      <td ".$color." align=\"right\">\n";
                  $salida .= "                         <a title='FECHA VENCIMIENTO'>\n";
                  $salida .= "                         ".$fechavencimiento;
                  $salida .= "                         </a>\n";  
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"right\">\n";
                  $salida .= "                         <a title='LOTE'>\n";
                  $salida .= "                         ".$busqueda[$i]['lote'];
                  $salida .= "                         </a>\n";  
                  $salida .= "                      </td>\n";
                  /*$salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                  $salida .= "                         ".$busqueda[$i]['contenido_unidad_venta'];
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                  $salida .= "                         ".$busqueda[$i]['laboratorio'];
                  $salida .= "                      </td>\n";*/
                  if($busqueda[$i]['existencia']>0)
                  {
                     $salida .= "                      <td align=\"center\" onclick=\"AsignarPro('".$busqueda[$i]['codigo_producto']."','".$busqueda[$i]['descripcion']."','".$busqueda[$i]['descripcion_unidad']."','".$busqueda[$i]['costo']."','".$busqueda[$i]['existencia']."','".$fechavencimiento."','".$busqueda[$i]['lote']."');\">\n";
                     $salida .= "                         <a title='SELECCIONAR PRODUCTO'>\n";
                     $salida .= "                          <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                     $salida .= "                         </a>\n"; 
                  }
                  else
                  {
                   $salida .= "                      <td align=\"center\" onclick=\"\">\n";
                  }
                  $salida .= "                      </td>\n";
                  $salida .= "                    </tr>\n";
               }   
                  $salida .= "                </table>\n";
                  
              $Cont=$consulta->ContarProStip($empresa_id,$centro_utilidad,$bodega,$aumento,$aumento2);
              $malo=$Cont[0]['count'];
              
              $action = "Bus_Pro('".$empresa_id."','".$centro_utilidad."','".$bodega."','".$tip_bus."','".$criterio."' ";
              $ctl = AutoCarga::factory("ClaseHTML");
              $salida .= $ctl->ObtenerPaginadoXajax($consulta->conteo,$consulta->paginaActual,$action,"0",10);
      }                        
      else
      {
               $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";         
               $salida .= "                    <tr>\n";
               $salida .= "                      <td align=\"center\">\n";
               $salida .="                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
               $salida .= "                      </td>\n";
               $salida .= "                    </tr>\n";
               $salida .= "                    </table>\n";
      } 
    }
    else
    {
      $salida .= "  <table width=\"95%\" align=\"center\">\n";         
      $salida .= "    <tr>\n";
      $salida .= "      <td align=\"center\" class=\"normal_10AN\">\n";
      $salida .= "        INGRESE UN CRITERIO DE BUSQUEDA";
      $salida .= "      </td>\n";
      $salida .= "    </tr>\n";
      $salida .= "  </table>\n";
    }

    $objResponse->assign("tabelos","innerHTML",$salida);
    return $objResponse;
  }

	function ListadoProductos($empresa_id,$prefijo,$numero,$codigo_barras,$descripcion,$bodegas_doc_id,$doc_tmp_id,$offset)
	{
	$objResponse=new xajaxResponse();
	$objClass=new doc_bodegas_I008();
	$consulta1=new MovBodegasSQL();
	$datos = $objClass->ProductosDocumento($empresa_id,$prefijo,$numero,$codigo_barras,$descripcion,$bodegas_doc_id,$doc_tmp_id,'');
	$conteo = $objClass->conteo;
	$salida = ListadoProductos_HTML($empresa_id,$prefijo,$numero,$codigo_barras,$descripcion,$bodegas_doc_id,$doc_tmp_id,$datos);
	$objResponse->assign("ProductosDocumento","innerHTML",$objResponse->setTildes($salida));

	return $objResponse;
	}

	function ListadoProductos_HTML($empresa_id,$prefijo,$numero,$codigo_barras,$descripcion,$bodegas_doc_id,$doc_tmp_id,$datos)
	{
	$objClass=new doc_bodegas_I008();
	$consulta1=new MovBodegasSQL();

	$fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.SessionGetVar("empresa_id"));
	$salida .= "<br>";
	$salida .= "<div id=\"cabecera_facturaventa\"></div>";
	/*print_r($datos);*/ 	
	if(!empty($datos))
	{
	
		$salida .= "<center><label class=\"label_error\">Resultado(s) Encontrado(s) (".sizeof($datos).")</label></center>";
		$salida .= "                 <form id=\"forma\" name=\"forma\" action=\"\" method=\"post\">\n";
	    $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

	    	$salida .= "					<tr class=\"modulo_table_list_title\">";
		$salida .= "						<td width=\"35%\" >PRODUCTO</td>";
		$salida .= "						<td width=\"10%\">CODIGO</td>";
		$salida .= "						<td width=\"10%\">LOTE</td>";
		$salida .= "						<td width=\"10%\">FECHA VENC.</td>";
		$salida .= "						<td width=\"5%\">CAJA.</td>";
		$salida .= "						<td width=\"10%\">CANT.</td>";
		$salida .= "						<td width=\"10%\">CANT.PEND</td>";
		$salida .= "						<td width=\"10%\">OP</td>";
		$salida .= "					</tr>";
		$i=0;
		foreach($datos as $k => $valor)
	      {
		  $fecha_vencimiento=explode("-",$valor['fecha_vencimiento']);
		  $fechavencimiento=$fecha_vencimiento[2]."-".$fecha_vencimiento[1]."-".$fecha_vencimiento[0];
		  
	      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
	      $salida .= "                      <td >".$valor['descripcion'].". </td>\n";
	      $salida .= "						<td>";
	      $salida .= "							<input readonly=\"true\"  type=\"text\" name=\"codigo_producto".$i."\" id=\"codigo_producto".$i."\" class=\"input-text\" style=\"width:100%\" value=\"".$valor['codigo_producto']."\">";
	      $salida .= "						</td>";
	      $salida .= "						<td>";
	      $salida .= "							<input readonly=\"true\"  type=\"text\" name=\"lote".$i."\" id=\"lote".$i."\" class=\"input-text\" style=\"width:100%\" value=\"".$valor['lote']."\">";
	      $salida .= "						</td>";
	      $salida .= "						<td>";
	      $salida .= "							<input readonly=\"true\"  type=\"text\" name=\"fecha_vencimiento".$i."\" id=\"fecha_vencimiento".$i."\" class=\"input-text\" style=\"width:100%\" value=\"".$fechavencimiento."\">";
	      $salida .= "						</td>";
              $salida .= "						<td align='center'>";
	      $salida .= " ".$valor['numero_caja']." ";

	      $salida .= "						</td>";
	      $salida .= "						<td>";
		  $salida .= "							<input type=\"text\" name=\"cantidad".$i."\" id=\"cantidad".$i."\" class=\"input-text\" style=\"width:100%\" value=\"".$valor['cantidad']."\" readonly=\"true\" onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('cantidad$i',xGetElementById('cantidad$i').value,'".$valor['cantidad']."','hell$i');\">";
	      $salida .= "						</td>";
		  $salida .= "						<td>";
		  $salida .= "							<input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" id=\"cantidad_pendiente\" value=\"".FormatoValor($valor['cantidad'],0)."\" class=\"input-text\" style=\"width:100%\">";
		  $salida .= "						</td>";
		  $salida .= "						<td align=\"center\">";
		  $salida .= "                          <input type=\"hidden\" name=\"valor".$i."\" id=\"valor".$i."\" value=\"".$valor['costo']."\">";
		  $salida .= "                          <input type=\"hidden\" name=\"caja".$i."\" id=\"caja".$i."\" value=\"".$valor['numero_caja']."\">";

	      /*$salida .= "                          <input type=\"hidden\" name=\"porc_iva".$i."\" id=\"porc_iva".$i."\" value=\"".$valor['porc_iva']."\">";*/
	      $salida .= "                          <input type=\"hidden\" name=\"porc_iva".$i."\" id=\"porc_iva".$i."\" value=\"0\">";
	      $salida .= "							<input type=\"checkbox\" name=\"".$i."\" id=\"".$i."\" class=\"input-checkbox\" value=\"1\">";
		  $salida .= "						</td>";
		  $salida .= "                    </tr>\n";
	      $i++;
	      
	          foreach($Existencias as $key=>$v)
	          {

					$fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.SessionGetVar("empresa_id"));
    
					$fecha =$v['fecha_vencimiento'];  //esta es la que viene de la DB
					list($ano,$mes,$dia) = split( '[/.-]', $fecha );
					$fecha = $mes."/".$dia."/".$ano;

					$fecha_actual=date("m/d/Y");
					$fecha_compara_actual=date("Y-m-d");
					//Mes/Dia/Año  "02/02/2010
					$int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
					$colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
					$colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');

					$fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
					$fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
					$color =" style=\"width:100%\" ";
					$vencido=0;
					if($int_nodias<$fech_vencmodulo)
					{
					$color = "style=\"width:100%;background:".$colores['PV'].";\"";
					$vencido=0;
					}

					if($fecha_dos<=$fecha_uno_act)
					{
					$color = "style=\"width:100%;background:".$colores['VN'].";\"";
					$vencido=1;
					}
	          }
		}
		  $salida .= "                                        <tr class=\"modulo_table_list_title\">\n";
	      $salida .= "                                        <td width=\"20%\" colspan=\"8\" align=\"center\">";
	      $salida .= "                        						<input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".trim($empresa_id)."\">";
	      $salida .= "                        						<input type=\"hidden\" name=\"prefijo\" id=\"prefijo\" value=\"".trim($prefijo)."\">";
	      $salida .= "                        						<input type=\"hidden\" name=\"numero\" id=\"numero\" value=\"".trim($numero)."\">";
		  $salida .= "												<input type=\"hidden\" name=\"doc_tmp_id\" id=\"doc_tmp_id\" value=\"".trim($doc_tmp_id)."\">";
	      $salida .= "												<input type=\"hidden\" name=\"bodegas_doc_id\" id=\"bodegas_doc_id\" value=\"".trim($bodegas_doc_id)."\">";
		  $salida .= "                          <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
		  $salida .= "                                              <input class=\"input-submit\" type=\"button\" value=\"GUARDAR TEMPORAL\" onclick=\"xajax_AgregarItem(xajax.getFormValues('forma'));\">";
	      $salida .= "                                        </td>";
	      $salida .= "                                        </tr>\n";
	      
	      $salida .= "                </table>\n";
	      $salida .= "              </form>";
	      $salida .= "                <br>\n";
	}
	else
	{
	$salida .= "<center>\n";
	$salida .= "  <label class=\"label_error\">\n";
	$salida .= "    EL PRODUCTO BUSCADO NO HACE PARTE DEL PEDIDO, FAVOR REVISAR PARAMETROS DE LA BUSQUEDA\n";
	$salida .= "  </label>\n";
	$salida .= "</center>\n";
	}
	$salida .= "</table><br>";

	
	$salida .= "";

	return $salida;
	}

	
	  function AgregarItem($Formulario)
	{	
    $objResponse=new xajaxResponse();
    $objClass=new doc_bodegas_I008;

    $fecha_actual=date("m/d/Y");
    $dias_vencimiento = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.trim(SessionGetVar('empresa_id')));
    
    for($i=0;$i<$Formulario['registros'];$i++)
    {
       
      if($Formulario['cantidad'.$i]!="" && $Formulario['cantidad'.$i]>0)
      {
        $fecha = $Formulario['fecha_vencimiento'.$i];
     
        list( $dia, $mes, $ano ) = split( '[/.-]', $fecha ); 
        $fecha = $mes."/".$dia."/".$ano;
        $fecha_ = $ano."/".$mes."/".$dia;

        $fecha_comparar = $ano."-".$mes."-".$dia;
        
        //Para despues verificar si un producto está vencido o nó
        $fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
        $fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
        
        $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
      
        $datosItem = $objClass->ConsultarItemsExistencias($Formulario['codigo_producto'.$i],trim(SessionGetVar('empresa_id')),trim(SessionGetVar('centro_utilidad')),trim(SessionGetVar('bodega')));
        //Valido que el Producto se encuentre en la Existencia de la empresa
        if(count($datosItem)==0)
        {
          $objResponse->alert("El producto [ ".$Formulario['codigo_producto'.$i]." ] no se encuentra en bodegas_existencias");
        }
        else
        { //Valido que los Campos no estén vacíos
          if($Formulario['cantidad'.$i]=="" || $Formulario['lote'.$i]=="" || $Formulario['fecha_vencimiento'.$i]=="")
             $objResponse->alert("Faltan Campos por Diligenciar, El Registro No Será Guardado!!");
          else
          {
              if($fecha_dos<=$fecha_uno_act)
              {
                $objResponse->alert("El Producto [ ".$Formulario['codigo_producto'.$i]." ], Se Encuentra Vencido!!!");
              }
              else
              {
                $mensaje ="";
                $bandera="0";
                if($int_nodias < $dias_vencimiento)
                {
                  $mensaje .= "El Producto Está proximo a Vencer ";
                  $bandera="1";
                }
               
                 $total_costo = $Formulario['cantidad'.$i]*($Formulario['valor'.$i]);
                  
                  $alerta ="";
                  $ProductosDocumento = $objClass->ProductosDocumento($Formulario['empresa_id'],$Formulario['prefijo'],$Formulario['numero'],'','',$Formulario['bodegas_doc_id'],$Formulario['doc_tmp_id'],$Formulario['codigo_producto'.$i],$Formulario['lote'.$i],$fecha_comparar,$Formulario['caja'.$i]);

				   	
				  if(($ProductosDocumento[0]['cantidad']-$Formulario['cantidad'.$i])>=0)
                         {
						// $objResponse->alert("El Producto GUARDAR TEMPORAL!!!");
						$ItemId=$objClass->GuardarTemporal($Formulario['bodegas_doc_id'],$Formulario['doc_tmp_id'],$Formulario['codigo_producto'.$i],$Formulario['cantidad'.$i],$Formulario['porc_iva'.$i],$total_costo,UserGetUID(),$Formulario['fecha_vencimiento'.$i],$Formulario['lote'.$i],$Formulario['caja'.$i]);
						 /*print_r("Hoooola");*/
						 }
                  if($ItemId)
					$h++;
				}
			}
		}
      }
    }
		
		$objResponse->assign("crearDoc","style.display","block");
		$objResponse->assign("listadoP","innerHTML",$objResponse->setTildes($salida));
		$objResponse->script("xajax_MostrarProductox('".$Formulario['bodegas_doc_id']."','".$Formulario['doc_tmp_id']."','".UserGetUID()."');");
		$objResponse->script("xajax_ListadoProductos(document.getElementById('empresa_id').value,document.getElementById('prefijo').value,document.getElementById('numero').value,document.getElementById('codigo_barras').value,document.getElementById('descripcion').value,document.getElementById('bodegas_doc_id').value,document.getElementById('doc_tmp_id').value,'1');");
		
		return $objResponse;
}
/********************************************************************************
*para mostrar la tabla de clientes
*********************************************************************************/
    
    function ObtenerPaginadoPro($path,$slc,$op,$empresa_id,$centro_utilidad,$bodega,$tip_bus,$criterio,$pagina)
    {
      
       //echo "io";
      $TotalRegistros = $slc[0]['count'];
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $uid = UserGetUID();
         $LimitRow = 10;//;intval(GetLimitBrowser());
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
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";   //$empresa_id,$centro_utilidad,$bodega,$tip_bus,$criterio,$offset      
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_Pro('".$empresa_id."','".$centro_utilidad."','".$bodega."','".$tip_bus."','".$criterio."','1')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";                                                             
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_Pro('".$empresa_id."','".$centro_utilidad."','".$bodega."','".$tip_bus."','".$criterio."','".($pagina-1)."')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:Bus_Pro('".$empresa_id."','".$centro_utilidad."','".$bodega."','".$tip_bus."','".$criterio."','".$i."')\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";   
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_Pro('".$empresa_id."','".$centro_utilidad."','".$bodega."','".$tip_bus."','".$criterio."','".($pagina+1)."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:Bus_Pro('".$empresa_id."','".$centro_utilidad."','".$bodega."','".$tip_bus."','".$criterio."','".$NumeroPaginas."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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

/******************************************************************************
* para guardar un documento temporal
********************************************************************************/
//GuardarTmpDoc(bodegas_doc_id, observacion, tipo_id_tercero, tercero_id, prestamo);
//(bodegas_doc_id, observacion,tipo_id_farmaClie, pedido_farmacia,tipo_id_tercero, tercero_id)
//GuardarTmpDoc(bodegas_doc_id, observacion, tipo_id_tercero, tercero_id, prestamo)
function GuardarTmpDoc($bodegas_doc_id, $observacion,$doc_despacho)
{
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta=new MovBodegasSQL();
    $consulta1=new doc_bodegas_I008();
    $valor=$consulta1->CrearDoc($bodegas_doc_id, $observacion,$doc_despacho);

    //$salida=PintarTabla($valor);
    
    $objResponse->assign("doc_tmp_id_h","value",$valor['doc_tmp_id']);
    sleep(1);  
    $objResponse->assign("accion_h","value",'EDITAR');
    $objResponse->call("mar1");

    return $objResponse;
}

/******************************************************************************
* PARA HACER EL FAMOSISIMO SUBMIT
********************************************************************************/

function Subtimit()
{
    $objResponse = new xajaxResponse();
    $objResponse->call("mar");
    return $objResponse;
}
/*******************************************************************************
funcion para buscar tecero por id
*******************************************************************************/   
 function BusUnTer($tipo_id,$id)
 {  
    
    $objResponse = new xajaxResponse();
    
    $path = SessionGetVar("rutaImagenes");
    $consulta=new MovBodegasSQL();
    $Tercero=$consulta->Nombres($tipo_id,$id);     
    if(!empty($Tercero))
    {
      $tercero_tipo_id=$Tercero[0]['tipo_id_tercero'];
      $tercero_id=$Tercero[0]['tercero_id'];
      $tercero_ids=$Tercero[0]['tipo_id_tercero']."-".$Tercero[0]['tercero_id'];
      $tercero_nombre=$Tercero[0]['nombre_tercero'];
      $objResponse->assign("tercerito_tip","value",$tercero_tipo_id);  
      $objResponse->assign("tercerito","value",$tercero_id);  
      $objResponse->assign("id_tercerox","value",$tercero_id);  
      $objResponse->assign("td_terceros_nue_mov","innerHTML",$tercero_nombre);  
      $objResponse->assign("ter_id_nuedoc","value",$tercero_ids);  
      $objResponse->assign("ter_nom_nue_doc","value",$tercero_nombre);  
      $objResponse->assign("nombre_tercero","innerHTML",$tercero_nombre);   
      $objResponse->assign("nom_terc","value",$tercero_id);
      $objResponse->assign("tipo_id_tercero_sel","value",$tercero_tipo_id);  
      $objResponse->assign("id_tercero_sel","value",$tercero_id);
      $objResponse->assign("nombre_tercero_sel","value",$tercero_nombre);   
    }
    else
    {
      $clear="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">NO EXISTE CON ESA IDENTIFICACION</label>";
      $objResponse->assign("td_terceros_nue_mov","innerHTML",$clear);  
      $objResponse->assign("nombre_tercero","innerHTML",$clear);   
    }  
    return $objResponse;

 } 


/*******************************************************************************
*Cuadra el select de tipo id terceros
********************************************************************************/
function Cuadrar_ids_terceros($id)
 {  
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta=new MovBodegasSQL();
    $TiposTercerosId=$consulta->Terceros_id();     
    $salida .= "                         <select name=\"tipox_id\" id=\"tipox_id\" class=\"select\" onchange=\"\">";
    for($i=0;$i<count($TiposTercerosId);$i++)
     {
        if($TiposTercerosId[$i]['tipo_id_tercero']==$id)
        {
          $salida .="                           <option value=\"".$TiposTercerosId[$i]['tipo_id_tercero']."\" selected>".$TiposTercerosId[$i]['tipo_id_tercero']."</option> \n";         
        }
        else
        {
         $salida .="                           <option value=\"".$TiposTercerosId[$i]['tipo_id_tercero']."\">".$TiposTercerosId[$i]['tipo_id_tercero']."</option> \n";         
        }
     }
    $salida .="                         </select>\n";
    $objResponse->assign("tercero_identic","innerHTML",$salida);  
    $objResponse->assign("tipos_ids_terceroxa","innerHTML",$salida);  
    
    return $objResponse;
   
 } 
 
  /********************************************************************************
    trae numero de movimiento segun lapso
    *********************************************************************************/
    
    function Departamento2($id_pais)
    { 
      $consulta=new MovBodegasSQL();
      $objResponse = new xajaxResponse();
      $Departamentos=$consulta->DePX($id_pais);
      $path = SessionGetVar("rutaImagenes");
      //$objResponse->alert("sss $id_pais");
      
      if($id_pais != "0")  
       {   
         //  var_dump($Departamentos);
          if(!empty($Departamentos))  
          {
              $salida = "                       <select id=\"dptox\" name=\"dptox\" class=\"select\" onchange=\"Municipios1(document.getElementById('paisex').value,document.getElementById('dptox').value);\">";
              $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
              for($i=0;$i<count($Departamentos);$i++)
                {
                  $salida .= "                           <option value=\"".$Departamentos[$i]['tipo_dpto_id']."\">".$Departamentos[$i]['departamento']."</option> \n";
                }
              $salida .= "                           <option value=\""."otro"."\">OTRO</option> \n";
              $salida .= "                       </select>\n";
            $salida = $objResponse->setTildes($salida);
            $objResponse->assign("depart","innerHTML",$salida);
            $salida1 = "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" disabled>";
            $salida1 .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
            $salida1 .= "                       </select>\n";
            $objResponse->assign("muni","innerHTML",$salida1);
            $objResponse->assign("h_departamento","value","0");
            $objResponse->assign("h_municipio","value","0");   
          }
            else
            {
              //$objResponse->alert("saaa $id_pais");
              $inc="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">INSERTAR</label>";
              $salida = " <input type=\"text\" class=\"input-text\" id=\"dptox\" name=\"dptox\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida.=$inc;
              $salida1 = " <input type=\"text\" class=\"input-text\" id=\"mpios\" name=\"mpios\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida1.=$inc;
              $objResponse->assign("depart","innerHTML",$salida);  
              $objResponse->assign("muni","innerHTML",$salida1);   
              //$salida .= "                       <input type=\"hidden\" id=\"h_departamento\" name=\"h_departamento\" value=\"0\">\n";
              //$salida .= "                       <input type=\"hidden\" id=\"h_municipio\" name=\"h_municipio\" value=\"0\">\n";
              $objResponse->assign("h_departamento","value","1");
              $objResponse->assign("h_municipio","value","1");
            }   
           
       }
       else
       {
          $salida = "                       <select id=\"dptox\" name=\"dptox\" class=\"select\" disabled>";
          $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
          $salida .= "                       </select>\n";
          $objResponse->assign("depart","innerHTML",$salida);  
          $salida1 = "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" disabled>";
          $salida1 .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
          $salida1 .= "                       </select>\n";
          $objResponse->assign("muni","innerHTML",$salida1);
          $objResponse->assign("h_departamento","value","0");
          $objResponse->assign("h_municipio","value","0");   
      
       }
        
       return $objResponse;
    }
/******************************************************************************
*MUNICIPIOS
********************************************************************************/
    function Municipios($id_pais,$id_dpto)
    { 
      $consulta=new MovBodegasSQL();
      $objResponse = new xajaxResponse();
      $Municipios=$consulta->DeMX($id_pais,$id_dpto);
      $path = SessionGetVar("rutaImagenes");
      //$objResponse->alert("sss $id_dpto");
      
      if($id_dpto != "0" && $id_dpto != "otro")  
       {   
          
         //  var_dump($Departamentos);Municipio3(municipio)
          if(!empty($Municipios))  
          {
              $salida = "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" onchange=\"Municipio3(this.value);\">";//
              $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
              for($i=0;$i<count($Municipios);$i++)
                {                                                               
                  $salida .= "                           <option value=\"".$Municipios[$i]['tipo_mpio_id']."\">".$Municipios[$i]['municipio']."</option> \n";
                }
              
              $salida .= "                           <option value=\""."otro"."\">OTRO</option> \n";
              $salida .= "                       </select>\n";
            $salida = $objResponse->setTildes($salida);
            $objResponse->assign("muni","innerHTML",$salida);  
           }
            else
            {
              $inc="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">INSERTAR</label>";
              $salida1 = " <input type=\"text\" class=\"input-text\" id=\"mpios\" name=\"mpios\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida1.=$inc;
              $objResponse->assign("muni","innerHTML",$salida1);
              $objResponse->assign("h_municipio","value","1");
            }   
            
       }
       elseif($id_dpto == "otro")
            {
              //$objResponse->alert("serasss $id_dpto");
              $inc="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">INSERTAR</label>";
              $salida = " <input type=\"text\" class=\"input-text\" id=\"dptox\" name=\"dptox\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida.=$inc;
              $salida1 = " <input type=\"text\" class=\"input-text\" id=\"mpios\" name=\"mpios\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida1.=$inc;
              $objResponse->assign("depart","innerHTML",$salida);   
              $objResponse->assign("muni","innerHTML",$salida1);
              $objResponse->assign("h_departamento","value","1");
              $objResponse->assign("h_municipio","value","1");
            }   
            else
            {
                $salida1 = "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" disabled>";
                $salida1 .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
                $salida1 .= "                       </select>\n";
                $objResponse->assign("muni","innerHTML",$salida1);   
            
            }
        
       return $objResponse;
    }


/*********************************************************************************
 *FUNCION PARA GUARDAR PERSONAS
 **********************************************************************************/
 function GuardarPersona($tipo_identificacion,
                         $id_tercero,
                         $nombre,
                         $pais,
                         $departamento,
                         $municipio,
                         $direccion,
                         $telefono,
                         $faz,
                         $email,
                         $celular,
                         $perjur)
 {
      $path = SessionGetVar("rutaImagenes");
      $objResponse = new xajaxResponse();
      $consulta=new MovBodegasSQL();
      //$objResponse->alert("Hoddla $direccion");
      $REGISTRAR=$consulta->GuardarPersonas($tipo_identificacion,
                                            $id_tercero,
                                            strtoupper($nombre),
                                            $pais,
                                            $departamento,
                                            $municipio,
                                            $direccion,
                                            $telefono,
                                            $faz,
                                            $email,
                                            $celular,
                                            $perjur);
   
       if($REGISTRAR=="EXITO")
        {
             
              $objResponse->call("CerrarTrocha");
             $Tercero=$consulta->Nombres($tipo_identificacion,$id_tercero);     
            if(!empty($Tercero))
            {
              $tercero_tipo_id=$Tercero[0]['tipo_id_tercero'];
              $tercero_id=$Tercero[0]['tercero_id'];
              $tercero_ids=$Tercero[0]['tipo_id_tercero']."-".$Tercero[0]['tercero_id'];
              $tercero_nombre=$Tercero[0]['nombre_tercero'];
              //$objResponse->alert("Hola1 $tercero_id");
              $objResponse->assign("nom_terc","value",$tercero_id);
              //$objResponse->alert("Hola2 $tercero_id");
              $objResponse->assign("tercerito_tip","value",$tercero_tipo_id);  
              $objResponse->assign("tercerito","value",$tercero_id);  
              $objResponse->assign("id_tercerox","value",$tercero_id);  
              $objResponse->assign("td_terceros_nue_mov","innerHTML",$tercero_nombre);  
              $objResponse->assign("ter_id_nuedoc","value",$tercero_ids);  
              $objResponse->assign("ter_nom_nue_doc","value",$tercero_nombre);  
              $objResponse->assign("nombre_tercero","innerHTML",$tercero_nombre);   
              
              $objResponse->assign("tipo_id_tercero_sel","value",$tercero_tipo_id);  
              $objResponse->assign("id_tercero_sel","value",$tercero_id);
              $objResponse->assign("nombre_tercero_sel","value",$tercero_nombre);   
            }
            
            $TiposTercerosId=$consulta->Terceros_id();     
            $salida ="<select name=\"tipox_id\" id=\"tipox_id\" class=\"select\" onchange=\"\">";
            for($i=0;$i<count($TiposTercerosId);$i++)
            {
                if($TiposTercerosId[$i]['tipo_id_tercero']==$tipo_identificacion)
                {
                  $salida .="                           <option value=\"".$TiposTercerosId[$i]['tipo_id_tercero']."\" selected>".$TiposTercerosId[$i]['tipo_id_tercero']."</option> \n";         
                }
                else
                {
                $salida .="                           <option value=\"".$TiposTercerosId[$i]['tipo_id_tercero']."\">".$TiposTercerosId[$i]['tipo_id_tercero']."</option> \n";         
                }
            }
            $salida .="                         </select>\n";
            $objResponse->assign("tercero_identic","innerHTML",$salida);  
            $objResponse->assign("tipos_ids_terceroxa","innerHTML",$salida);  

        }
        else
        {
          $objResponse->assign("error_terco","innerHTML",$REGISTRAR);     
        }
              //$objResponse->alert("Hola $REGISTRAR");  
 
     //$objResponse->assign("error_terco","innerHTML",$REGISTRAR);   
     return $objResponse;
 } 
function Guardar_DYM($vienen,$id_pais,$departamentox,$Municipio)
{
     $consulta=new MovBodegasSQL();
     $objResponse = new xajaxResponse();
     //$objResponse->alert("VIENEN $vienen");
     if($vienen==2)
     {     
          $revisar=$consulta->Consultadpto($departamentox);
        
          if(empty($revisar))
          {
            $departamentox=strtoupper($departamentox);
            $Municipio=strtoupper($Municipio);
            $GuardarD=$consulta->GXD($id_pais,UTF8_DECODE($departamentox));
            
            $GuardarM=$consulta->GXM($id_pais,$GuardarD,UTF8_DECODE($Municipio));
            
            $LISTO="YA ESTAN".$GuardarD."Y".$GuardarM;
            
            //$objResponse->alert("r $LISTO");
          
            $objResponse->assign("dptox","value",$GuardarD);
            
            $objResponse->assign("mpios","value",$GuardarM);
            
            $objResponse->assign("ban_dep","value","1");
            
            $objResponse->assign("ban_mun","value","1"); 
          }
          elseif(Is_array($revisar))
          {
            $GuardarD=$revisar[0]['tipo_dpto_id']; 
            //var_dump($revisar);
            $LISTO="YA ESTA REPETIDO DEPATAMENTO".$GuardarD;
            
            //$objResponse->alert("r $LISTO");
          
            $revisar=$consulta->Consultampio($id_pais,$GuardarD,$Municipio);
            
            if(empty($revisar))
            { 
               $Municipio=strtoupper($Municipio);
               $GuardarM=$consulta->GXM($id_pais,$GuardarD,UTF8_DECODE($Municipio));
               
            }
            elseif(Is_array($revisar))
            {
                $GuardarM=$revisar[0]['tipo_mpio_id']; 
                
                $toca="municipio ya existe".$GuardarM;
                
                //$objResponse->alert("r $toca");
            
            }
          
            
            $objResponse->assign("dptox","value",$GuardarD);
            
            $objResponse->assign("mpios","value",$GuardarM);
            
            $objResponse->assign("ban_dep","value","1");
            
            $objResponse->assign("ban_mun","value","1"); 
          
          }
     
     
     }
     elseif($vienen==1)
     {
          $revisar=$consulta->Consultampio($id_pais,$departamentox,$Municipio);
          //var_dump($revisar);
          if(empty($revisar))
          {
            $Municipio=strtoupper($Municipio);
            $GuardarM=$consulta->GXM($id_pais,$departamentox,UTF8_DECODE($Municipio));    
          
            $LISTO="MUNICIPIO GRABDO".$GuardarM; 
            
            //$objResponse->alert("r $LISTO");
                        
          }
          elseif(Is_array($revisar))
          {
            $GuardarM=$revisar[0]['tipo_mpio_id']; 
            
            $toca="municipio ya existe".$GuardarM;
            
            //$objResponse->alert("r $toca");
            
          }
        
            $objResponse->assign("mpios","value",$GuardarM);
            
            $objResponse->assign("ban_dep","value","1");
            
            $objResponse->assign("ban_mun","value","1"); 
            
            
     
     }
     
          
     $objResponse->call("Guardaralfa");
     
     return $objResponse;
     
}
    
 /**************************************************************************************
 *FUNCION PARA CREAR UN USUARIO
 **************************************************************************************/   
 function CrearUSA()
 {
 
      $path = SessionGetVar("rutaImagenes");
      $objResponse = new xajaxResponse();
      $consulta=new MovBodegasSQL();
      $salida  = "                <div id=\"ventana_terceros\">\n";
      $salida .= "                 <form name=\"formcreausu\">\n";     
      $salida .= "                  <div id='error_terco' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $salida .= "                   <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td  align=\"center\" colspan='2'>\n";
      $salida .= "                         CREAR TERCERO";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        TIPO ID TERCERO";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\" align=\"left\" >\n";
      $tipos_id_ter3=$consulta->Terceros_id();
                if(!empty($tipos_id_ter3))
                {
                  $salida .= "                       <select id=\"tipos_idx3\" name=\"tipos_idx3\" class=\"select\" onchange=\"\">";
                
                
                  for($i=0;$i<count($tipos_id_ter3);$i++)
                  {
                    $salida .="                           <option value=\"".$tipos_id_ter3[$i]['tipo_id_tercero']."\">".$tipos_id_ter3[$i]['tipo_id_tercero']."</option> \n";
                  }
                  $salida .= "                       </select>\n";
                }
      $salida .= "                        &nbsp; TERCERO ID";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"terco_id\" name=\"terco_id\" maxlength=\"20\" size=\"20\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        NOMBRE";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"nom_man\" name=\"nom_man\" size=\"50\" value=\"\" onkeypress=\"\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        PAIS";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $Pais=$consulta->Paises();
                
                if(!empty($Pais))
                {
                  $salida .= "                       <select id=\"paisex\" name=\"paisex\" class=\"select\" onchange=\"Departamentos2(this.value);\">";
                  $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
                
                  for($i=0;$i<count($Pais);$i++)
                  {
                    $salida .="                           <option value=\"".$Pais[$i]['tipo_pais_id']."\">".$Pais[$i]['pais']."</option> \n";
                  }
                  $salida .= "                       </select>\n";
                }
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        DEPARTAMENTO";
      $salida .= "                       </td>\n";
      $salida .= "                       <input type=\"hidden\" id=\"ban_dep\" name=\"ban_dep\" value=\"0\">\n";
      $salida .= "                       <input type=\"hidden\" id=\"h_departamento\" name=\"h_departamento\" value=\"0\">\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\" id=\"depart\">\n";
      $salida .= "                       <select id=\"dptox\" name=\"dptox\" class=\"select\" disabled>";
      $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
      $salida .= "                       </select>\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        MUNICIPIO";
      $salida .= "                       </td>\n";
      $salida .= "                       <input type=\"hidden\" id=\"ban_mun\" name=\"ban_mun\" value=\"0\">\n";
      $salida .= "                       <input type=\"hidden\" id=\"h_municipio\" name=\"h_municipio\" value=\"0\">\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\" id=\"muni\">\n";
      $salida .= "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" disabled>";
      $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
      $salida .= "                       </select>\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        DIRECCION";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"direc\" id=\"direc\" maxlength=\"50\" size=\"50\" value=\"\" onkeypress=\"\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        TELEFONO";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"phone\" name=\"phone\" maxlength=\"30\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        FAX";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"fax\" name=\"fax\" maxlength=\"30\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        E-MAIL";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"e_mail\" name=\"e_mail\" maxlength=\"50\" size=\"50\" value=\"\" onkeypress=\"\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        CELULAR";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"cel\" name=\"cel\" maxlength=\"30\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td colspan='2'  align=\"center\">\n";
      $salida .= "                          PERSONA NATURAL";
      $salida .= "                          <input type=\"radio\" class=\"input-text\" id=\"persona1\" name=\"persona1\" value=\"0\" checked>\n";
      $salida .= "                          PERSONA JURIDICA";
      $salida .= "                          <input type=\"radio\" class=\"input-text\" id=\"persona2\" name=\"persona1\" value=\"1\" >\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td colspan='2'  align=\"center\">\n";
      $salida .= "                         <input type=\"button\" class=\"input-submit\" onclick=\"ValidadorUltraTercero();\" value=\"Registrar\">\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                 </table>\n";         
      $salida .= "                </form>\n";     
      $salida .= "         </div>\n";
      $salida = $objResponse->setTildes($salida);
      $objResponse->assign("ContenidoCre","innerHTML",$salida);
      return $objResponse;
    
    
 
 
 }
 
 /********************************************************************************
 *para mostrar la tabla de terceros
 *********************************************************************************/
    function ObtenerPaginadoter($pagina,$path,$slc,$op,$criterio1,$criterio2,$criterio,$div,$forma)
    {
      
       //echo "io";
      $TotalRegistros = $slc[0]['count'];
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
                                                                                               //     na,criterio1,criterio2,criterio,div,forma
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_ter('1','".$criterio1."','".$criterio2."','".$criterio."','".$div."','".$forma."')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_ter('".($pagina-1)."','".$criterio1."','".$criterio2."','".$criterio."','".$div."','".$forma."')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:Bus_ter('".$i."','".$criterio1."','".$criterio2."','".$criterio."','".$div."','".$forma."')\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_ter('".($pagina+1)."','".$criterio1."','".$criterio2."','".$criterio."','".$div."','".$forma."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:Bus_ter('".$NumeroPaginas."','".$criterio1."','".$criterio2."','".$criterio."','".$div."','".$forma."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
	*
	*/
    function DevolucionprestamoTerce($tipo_id_tercero,$tercero_id,$tipo_doc_bodega_id)
    {
        $objResponse = new xajaxResponse();
		$consulta=new MovBodegasSQL();
        
        $devolucion_prestamo=$consulta->DocumentosDePrestamo($tipo_id_tercero,$tercero_id,SessionGetVar("EMPRESA"),'I');
			$salida .= "                          <option value=\"0\" SELECTED>SELECCIONAR</option>\n";
            foreach($devolucion_prestamo as $k=>$dp)
            {
              $salida .= "                      <option value=\"".$dp['empresa_id']."-".$dp['prefijo']."-".$dp['numero']."\">".$dp['prefijo']."-".$dp['numero']."</option>\n";
            }
     $objResponse->assign("doc_devolucion","innerHTML",$salida);
     return $objResponse;
   }
   /**
   *
   */
  function MostrarTerceros($forma)
  {
     $consulta=new MovBodegasSQL();
     $objResponse = new xajaxResponse();
     //print_r($forma);
     if($forma['tipo_idfc']==1)
    {
        $salida .= " <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                       <td  width='13%' align=\"left\" class=\"modulo_table_list_title\">\n";
        $salida .= "                        TERCERO ID";
        $salida .= "                       </td>\n";
        $salida .= "                          <input type=\"hidden\" id=\"tercerito_tip\" name=\"tercerito_tip\" value=\"0\">\n";
        $salida .= "                          <input type=\"hidden\" id=\"tercerito\" name=\"tercerito\" value=\"0\">\n";
        $salida .= "                          <input type=\"hidden\" id=\"htmp_id\" name=\"htmp_id\" value=\"0\">\n";
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
       $salida .= "                            NOMBRE";
       $salida .= "                       </td>\n";
       $salida .= "                       <td  width='40%'  align=\"left\" class=\"modulo_list_claro\" id=\"td_terceros_nue_mov\"> \n";
       $salida .= "                       </td>\n";
      /* $salida .= "                        <td width='20%' align=\"center\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
       $salida .= "                         <div id='prestamo'></div>\n";
       $salida .= "                        </td>";*/
      //$salida .= "<pre>".print_r($tipo_doc_bodega_id,true)."</pre>";
       $salida .= "                        <td width='20%' align=\"center\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
       $java = "javascript:MostrarCapa('ContenedorMov1');Bus_ter('0','0','0','ContenidoMov1','unocreate','".$forma['tipo_doc']."','1');Iniciar2('BUSCAR TERCERO');\"";//
       $salida .= "                          <a  title=\"SELECIONAR TERCERO\" class=\"label_error\" href=\"".$java."\"> BUSCAR TERCERO</a>\n";
      //$salida .= "                          <input type=\"button\" class=\"input-submit\" name=\"tercero\" value=\"SELECCIONAR TERCERO\" onclick=\"limpiar2(); MostrarCapa('ContenedorMov1');xajax_Buscadorter('1','0','0','ContenidoMov1','unocreate');Iniciar2('CREAR DOCUMENTO NUEVO');\">\n";
       $salida .= "                        </td>";
       $salida .= "                    </tr>\n";
       $salida .= "  </table>";
    }
    if($forma['tipo_idfc']==2)
    {
        $salida .= " <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                       <td  width='13%' align=\"left\" class=\"modulo_table_list_title\">\n";
        $salida .= "                        FARMACIA ID";
        $salida .= "                       </td>\n";
        $salida .= "                          <input type=\"hidden\" id=\"tercerito_tip\" name=\"tercerito_tip\" value=\"0\">\n";
        $salida .= "                          <input type=\"hidden\" id=\"tercerito\" name=\"tercerito\" value=\"0\">\n";
        $salida .= "                          <input type=\"hidden\" id=\"htmp_id\" name=\"htmp_id\" value=\"0\">\n";
        $salida .= "                       <td  width='5%'  align=\"left\" class=\"modulo_list_claro\" id=\"tipos_ids_terceroxa\"> \n";
       /* $salida .= "                         <select name=\"tipox_id\" id=\"tipox_id\" class=\"select\" onchange=\"\">";
        $TiposTercerosId=$consulta->Terceros_id();
        for($i=0;$i<count($TiposTercerosId);$i++)
        {
            $salida .="                           <option value=\"".$TiposTercerosId[$i]['tipo_id_tercero']."\">".$TiposTercerosId[$i]['tipo_id_tercero']."</option> \n";
        }
       $salida .="                         </select>\n";
       $salida .= "                       </td>\n";*/
       $salida .= "                       <td  width='14%'  align=\"left\" class=\"modulo_list_claro\"> \n";
       $salida .= "                          <input type=\"text\" id=\"id_tercerox\" name=\"id_tercerox\" class=\"input-text\" onkeydown=\"recogerTeclab(event);\" onclick=\"\" value=\"\">\n";
       $salida .= "                       </td>\n";
       $salida .= "                       <td  width='8%' align=\"left\" class=\"modulo_table_list_title\">\n";
       $salida .= "                            NOMBRE";
       $salida .= "                       </td>\n";
       $salida .= "                       <td  width='40%'  align=\"left\" class=\"modulo_list_claro\" id=\"td_terceros_nue_mov\"> \n";
       $salida .= "                       </td>\n";
       $salida .= "                        <td width='20%' align=\"center\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
      // $salida .= "                         <div id='prestamo'></div>\n";
       $salida .= "                        </td>";
      //$salida .= "<pre>".print_r($tipo_doc_bodega_id,true)."</pre>";
       $salida .= "                        <td width='20%' align=\"center\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
       $java = "javascript:MostrarCapa('ContenedorMov1');Bus_Far('0','0','0','ContenidoMov1','unocreate','".$forma['tipo_doc']."','1');Iniciar2('BUSCAR FARMACIA');\"";//
       $salida .= "                          <a  title=\"SELECIONAR FARMACIA\" class=\"label_error\" href=\"".$java."\"> BUSCAR FARMACIA</a>\n";
      //$salida .= "                          <input type=\"button\" class=\"input-submit\" name=\"tercero\" value=\"SELECCIONAR TERCERO\" onclick=\"limpiar2(); MostrarCapa('ContenedorMov1');xajax_Buscadorter('1','0','0','ContenidoMov1','unocreate');Iniciar2('CREAR DOCUMENTO NUEVO');\">\n";
       $salida .= "                        </td>";
       $salida .= "                    </tr>\n";
       $salida .= "  </table>";
    }
     $objResponse->assign("tipo_teceros","innerHTML",$salida);
     return $objResponse;
  }
 
  function BuscadorFarm($pagina,$criterio1,$criterio2,$criterio,$div,$Forma,$tipo_doc_bodega_id)
 { //echo "si";
      $objResponse = new xajaxResponse(); 

//       $objResponse->alert("PAGIMA $pagina");
//       $objResponse->alert("CRITERIO 1 $criterio1");
//       $objResponse->alert("CRITERIO 2 $criterio2");
//       $objResponse->alert("CRITERIO 0 $criterio");
//       $objResponse->alert($div);
//       $objResponse->alert($Forma);
      $path = SessionGetVar("rutaImagenes");
      
      $consulta=new MovBodegasSQL();
      $consulta1=new doc_Bodegas_I008();
      if($criterio2=="")
      $criterio2="0";
      if($criterio=="")
      $criterio="0";
      $vector=$consulta1->Terceros($pagina,$criterio1,$criterio2,$criterio,$op=1);
      //print_r($vector);
      $salida .= "                  <div id=\"ventana_terceros\">\n";
      $salida .= "                  <form name=\"buscartercero\">\n";     
      $salida .= "                   <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td  align=\"center\" colspan='3'>\n";
      $salida .= "                         BUSCADOR DE TERCEROS";
      $salida .= "                      </td>\n";
     // $salida .= "<pre>".print_r($vector,true)."</pre>";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"44%\"  align=\"center\">\n";
      $salida .= "                        NOMBRE TERCERO";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"31%\" align=\"right\" >\n";
      if($criterio=="0")
      $salida .= "                         <input type=\"text\" class=\"input_text\" name=\"nom_buscar\" id=\"nom_buscar\" maxlength=\"40\" size\"40\" value=\"\" onkeypress=\"return acceptm(event)\">";
      else
      $salida .= "                         <input type=\"text\" class=\"input_text\" name=\"nom_buscar\" id=\"nom_buscar\" maxlength=\"40\" size\"40\" value=\"".$criterio."\" onkeypress=\"return acceptm(event)\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                       <td rowspan='2' width=\"10%\" align=\"center\">\n";                                                                 //$pagina,            $criterio1,                                   $criterio2,                           $criterio                          ,$div,      $Forma
      $salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"boton_bus\"  id=\"boton_bus\" value=\"BUSCAR\" onclick=\"Bus_ter('1',document.getElementById('buscar_x').value,document.getElementById('buscar').value,document.getElementById('nom_buscar').value,'".$div."','".$Forma."')\">\n";
      $salida .= "                       </td>\n";
      $salida .= "                       </tr>\n";
      $salida .= "                       <tr class=\"modulo_list_claro\" id=\"tres\">\n";
      $salida .= "                           <td align='center'>";
      $salida .= "                             TIPO ID";  
//       $salida .= "                           </td>";
//       $salida .= "                           <td>";
      $salida .= "                              <select name=\"buscar_x\" id=\"buscar_x\" class=\"select\">";
        if($criterio1=="0")
         $salida .="                               <option value=\"0\" selected>SELECCIONAR</option> \n";
        else
         $salida .="                               <option value=\"0\">SELECCIONAR</option> \n";
        if($criterio1=="CE")
         $salida .="                               <option value=\"CE\" selected>CEDULA DE EXTRANJERIA</option> \n";
        else
         $salida .="                               <option value=\"CE\">CEDULA DE EXTRANJERIA</option> \n";
        if($criterio1=="CC")
        $salida .="                                <option value=\"CC\" selected>CEDULA DE CIUDADANIA</option> \n";
        else
        $salida .="                                <option value=\"CC\">CEDULA DE CIUDADANIA</option> \n";
        if($criterio1=="TI")
        $salida .="                                <option value=\"TI\" selected>TARJETA DE IDENTIDAD</option> \n";
        else
        $salida .="                                <option value=\"TI\">TARJETA DE IDENTIDAD</option> \n";
        if($criterio1=="PA")
        $salida .="                                <option value=\"PA\" selected>PASAPORTE</option> \n";
        else
        $salida .="                                <option value=\"PA\">PASAPORTE</option> \n";
        if($criterio1=="RC")
        $salida .="                                <option value=\"RC\" selected>REGISTRO CIVIL</option> \n";
        else
        $salida .="                                <option value=\"RC\">REGISTRO CIVIL</option> \n";
        if($criterio1=="MS")
        $salida .="                                <option value=\"MS\" selected>MENOR SIN IDENTIFICACION</option> \n";
        else
        $salida .="                                <option value=\"MS\">MENOR SIN IDENTIFICACION</option> \n";
        if($criterio1=="NIT")
        $salida .="                                <option value=\"NIT\" selected>N. IDENTIFICACION TRIBUTARIO</option> \n";
        else
        $salida .="                                <option value=\"NIT\">N. IDENTIFICACION TRIBUTARIO</option> \n";
        if($criterio1=="AS")
        $salida .="                                <option value=\"AS\" selected>ADULTO SIN IDENTIFICACION </option> \n";
        else
        $salida .="                                <option value=\"AS\">ADULTO SIN IDENTIFICACION </option> \n";
        if($criterio1=="NU")
        $salida .="                                <option value=\"NU\" selected>NUMERO UNICO DE IDENTIF.</option> \n";
        else
        $salida .="                                <option value=\"NU\">NUMERO UNICO DE IDENTIF.</option> \n";
        $salida .="                             </select>\n";
        $salida .="                          </td>\n";

//         $salida .="<tr class=\"modulo_list_claro\">\n";
        $salida .="                          <td ALIGN='right'>\n";
        $salida .="                             ID\n";
//         $salida .="                         </td>\n";
//         $salida .="                         <td>\n";
        if($criterio2=="0")
         $salida .="                            <input type=\"text\" class=\"input_text\" name=\"buscar\" id=\"buscar\" maxlength=\"40\" size\"40\" value=\"\"onkeypress=\"return acceptm(event)\">";
        else
         $salida .="                            <input type=\"text\" class=\"input_text\" name=\"buscar\" id=\"buscar\" maxlength=\"40\" size\"40\" value=\"".$criterio2."\"onkeypress=\"return acceptm(event)\"></td>";
        $salida .="                         </td>\n";
        $salida .= "                       </tr>\n";
      
      $salida .= "                 </table>\n";         
      $salida .= "                 <table width='85%' border='0' align=\"center\">\n";         
      $salida .= "                 <tr>\n";         
      $salida .= "                 <td>\n";         
      if($Forma=="unocreate")
      {
        $nuevousu = "javascript:Cerrar('ContenedorMov1');CrearNuevoUsuario();MostrarCapa('ContenedorCre');IniciarUsu('CREAR NUEVO TERCERO'); ";//
      }
      elseif($Forma=="exige_ter")
      {
        $nuevousu = "javascript:Cerrar('ContenedorTer');CrearNuevoUsuario();MostrarCapa('ContenedorCre');IniciarUsu('CREAR NUEVO TERCERO'); ";//
      }
      
     /* $salida .= "                    <a title='CREAR TERCERO' class=\"label_error\" href=\"".$nuevousu."\">\n";
      $salida .= "                    <sub><img src=\"".$path."/images/inactivo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub> CREAR TERCERO\n";       
      $salida .= "                 </a>\n"; */
      $salida .= "                 </td>\n";         
      $salida .= "                 </tr>\n";         
      $salida .= "                 </table>\n";         
      $salida .= "                </form>\n";     
     
      if(count($vector)==0)
      {
        $salida .= "               <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
        $salida .= "                No se encontraron resultados con ese tipo de descripcion";       
        $salida .= "                </div>\n";       
      }
   if(count($vector)>0) 
   {
      $op="1";
      $action = "Bus_Far('".$criterio1."','".$criterio2."','".$criterio."','".$div."','".$Forma."'";
      $ctl = AutoCarga::factory("ClaseHTML");
      $salida .= $ctl->ObtenerPaginadoXajax($consulta->conteo,$consulta->paginaActual,$action,"0",10);
     // $slc=$consulta->ContarTercerosStip($criterio1,$criterio2,$criterio);
      //$salida .= "".ObtenerPaginadoter($pagina,$path,$slc,$op,$criterio1,$criterio2,$criterio,$div,$Forma);
      //$objResponse->alert("Hola $vector");
      $salida .= "                 <form name=\"clientes\">\n";         
      $salida .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
//       $salida .= "                       <td align=\"center\" width=\"15%\">\n";
//       $salida .= "                         TIP TERCERO ID";
//       $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\" width=\"23%\">\n";
      $salida .= "                         FARMACIA ID";
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\"width=\"47%\">\n";
      $salida .= "                         NOMBRE FARMACIA";
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\" width=\"15%\">\n";
      $salida .= "                         ACCIONES";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";         
        for($i=0;$i<count($vector);$i++)
        {   
            $salida .= "                    <tr class=\"modulo_list_claro\"  onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
//             $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
//             $salida .= "                         ".$vector[$i]['tipo_id_tercero']."";
//             $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                       ".$vector[$i]['tipo_id_tercero']."-".strtoupper($vector[$i]['id']);
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
            $salida .= "                        ".$vector[$i]['razon_social']."";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\">\n";
            $java = "javascript:Seleccionado('".$Forma."','".$vector[$i]['tipo_id_tercero']."','".strtoupper($vector[$i]['id'])."','".$vector[$i]['razon_social']."','".$tipo_doc_bodega_id."');";
            $salida .= "                         <a title='SELECCIONAR TERCERO' class=\"label_error\" href=\"".$java."\">\n";
            $salida .= "                          <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
            $salida .= "                         </a>\n";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
          }   
      $salida .= "                </table>\n";
      $salida .= "              </form>\n";
//       $op="1";
//       $slc=$consulta->ContarTercerosStip($tip_bus,$criterio);
//       $salida .= "".ObtenerPaginadoter($pagina,$path,$slc,$op,$tip_bus,$criterio,$div,$Forma);
   } 
      $salida .= "         <br>\n";
      $salida .= "         </div>\n";
      $salida = $objResponse->setTildes($salida);
   //ContenidoTer
      //$objResponse->assign("tabla_terceros","innerHTML",$salida);
      $objResponse->assign("".$div."","innerHTML",$salida);
      
      return $objResponse;
    }
 
  
  /**************************************************************************************
  * Separa la Fecha del formato timestamp  @access private @return string @param date fecha
  **************************************************************************************/
  function FechaStamp($fecha)
  {
    if($fecha)
    {
      $fech = strtok ($fecha,"-");
      for($l=0;$l<3;$l++)
      {
        $date[$l]=$fech;
        $fech = strtok ("-");
      }

      return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
    }
  }


/********************************************************************************
 *para mostrar la tabla de vinculacion de cuentas con paginador incluido
*********************************************************************************/
                             
    function ObtenerPaginadoPN($pagina,$path,$slc,$op,$prefijo,$numero)
    {
      $TotalRegistros = $slc[0]['count'];
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $uid = UserGetUID();
        $LimitRow = 10;//intval(GetLimitBrowser());
        //return $LimitRow;
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
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Paginas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";                //     $lapso,$tip_doc,$prefijo,$numero                       
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('1','".$prefijo."','".$numero."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('".($pagina-1)."','".$prefijo."','".$numero."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:LlamarDocus('".$i."','".$prefijo."','".$numero."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('".($pagina+1)."','".$prefijo."','".$numero."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:LlamarDocus('".$NumeroPaginas."','".$prefijo."','".$numero."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     Pagina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
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

/****************************************************************
*lapsos en el creardoc
*****************************************************************/
function ColocarDias($lapso,$div)
{
  $objResponse = new xajaxResponse();
  //$objResponse->alert("Hay $lapso");
  $consulta=new TomaFisicaSQL();
  $anho=substr($lapso,0,4);
  $mes=substr($lapso,4,2);
  
  
  //$objResponse->alert("Hyy $anho");
  $dias=date("d",mktime(0,0,0,$mes+1,0,$anho));
  //$objResponse->alert("Hyy $dias");
  $salida ="                    <select name=\"mesito\" class=\"select\" onchange=\"limpiar()\">";
  $salida .="                      <option value=\"0\" selected>---</option> \n";
  for($i=1;$i<=$dias;$i++)
   {
     $salida .="                   <option value=\"".$i."\">".$i."</option> \n";
   }
  $salida .="                   </select>\n";
  $objResponse->assign($div,"innerHTML",$salida);
  return $objResponse;
}
?>