<?php
	/**************************************************************************************
	* $Id: definirBodegas_E001.php,v 1.1 2009/07/17 19:08:17 johanna Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Jaime gomez
	**************************************************************************************/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	//include "../../../app_modules/InvTomaFisica/classes/TomaFisicaSQL.class.php";
	include "../../../app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/E001/doc_Bodegas_E001.class.php";
  include "../../../classes/ClaseHTML/ClaseHTML.class.php";

/**************************************************************************
*
*****************************************************************************/
function PintarBodegas($bodega,$centro)
{
    $path = SessionGetVar("rutaImagenes");
    $consulta=new MovBodegasSQL();  
    $objResponse = new xajaxResponse();
    $centrosx=$consulta->bodegasname1($bodega,$centro);
    //var_dump($centrosx);
      if(!empty($centrosx) && count($centrosx)>1)
    {
          $salida .= "                       <select id=\"bod_des\" name=\"bod_des\" class=\"select\" onchange=\"\">";//alert(this.value);
          $salida .= "                           <option value=\"0\" SELECTED>SELECCIONAR</option>\n"; 
          //var_dump($utility);
          for($i=0;$i<count($centrosx);$i++)
          {
            $salida .= "                      <option value=\"".$centrosx[$i]['bodega']."\">".$centrosx[$i]['descripcion']."</option>\n";
          }
          $salida .= "                       </select>\n";
          $objResponse->assign("centros","innerHTML",$salida);
    }
    else
    {
      $objResponse->assign("bod_des","value",$centrosx[0]['centro_utilidad']);
      $salida .= "<LABEL>".$centrosx[0]['descripcion']."</LABEL>";
        $objResponse->assign("centros","innerHTML",$salida);
    }
 return $objResponse;
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
/****************************************************************
* mostrar datos a l editar una tabla
******************************************************************/
function CrearDocumentoFinalx($bodegas_doc_id,$doc_tmp_id,$tipo_doc_bodega_id)
{   $path = SessionGetVar("rutaImagenes");
    $consulta=new MovBodegasSQL();  
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
//             foreach($buscar as $productos)
//             {var_dumP($productos);
          
                   
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
                                                       $direccion="app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I001/imprimir/imprimir_docI001.php";
                                                       $imagen = $path."/images/imprimir.png";
                                                       $alt="IMPRIMIR DOCUMENTO";
                                                       $x=RetornarImpresionDoc($direccion,$alt,$imagen,SessionGetVar("EMPRESA"),$productos['prefijo'],$productos['numero']);
                    $salida .= "                     ".$x."";
                    $salida .= "                      </td>\n";
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
      //$bodegas_doc_id,$doc_tmp_id,$usuario_id
      $sw_verificono=$consulta->ConsultaSw_verificar($tipo_documento,$tmp);
       $option_select .= " <option value=\"-1\">-- Seleccionar --</option>\n";
      // print_r($sw_verificono);
       foreach($sw_verificono as $indice=>$valor)
        {
          $option_select .= "  <option value=\"".$valor['abreviatura']."\" ".$selected.">".$valor['descripcion']."</option>";
        }
      $objResponse->assign("estados","innerHTML",$option_select);
      $objResponse->script("xajax_RecargarProductosTmp('".$bodega_doc_id."','".$tmp."','".UserGetUID()."');");
      //RecargarProductosTmp($doc_tmp_id,$usuario_id)
      return $objResponse;
}
 
function MostrarProductox($bodegas_doc_id,$doc_tmp_id,$usuario_id)
{
  $objResponse = new xajaxResponse();
  $path = SessionGetVar("rutaImagenes");
  $consulta=new doc_Bodegas_E001();
  $vector=$consulta->SacarProductosTMP($doc_tmp_id,$usuario_id);
    //var_dump($vector);
 if(!empty($vector))
 {
   $salida .= "                  <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
   $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
   $salida .= "                      <td align=\"center\" width=\"12%\">\n";
   $salida .= "                        <a title='CODIGO DEL PRODUCTO'>CODIGO<a> ";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"30%\">\n";
   $salida .= "                        <a title='DESCRIPCION DEL PRODUCTO'>DESCRIPCION<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"30%\">\n";
   $salida .= "                        <a title='FECHA VENCIMIENTO'>FECHA VENCIMIENTO<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"15%\">\n";
   $salida .= "                        <a title='LOTE'>LOTE<a>";
   $salida .= "                      </td>\n";
   /*$salida .= "                      <td align=\"center\" width=\"12%\">\n";
   $salida .= "                        <a title='UNIDAD DEL PRODUCTO'>UNIDAD<a>";
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

          $tr=$bodegas_doc_id."@".$productos['doc_tmp_id'];
          $salida .= "                    <tr id='".$tr."' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
          $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
          $salida .= "                        ".$productos['codigo_producto'];
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
          $salida .= "                         ".$productos['descripcion'];
          $salida .= "                      </td>\n";
          $fech_vencmodulo=ModuloGetVar('app','AdministracionFarmacia','dias_vencimiento_product_bodega_farmacia_02');
          
          $fecha_actual=date("m/d/Y");
          /*
                            * Para Sacar los numeros de días entre fechas
                           */      
          $fecha =$productos['fecha_vencimiento'];  //esta es la que viene de la DB
          list( $dia, $mes, $ano ) = split( '[/.-]', $fecha );
          $fecha = $mes."/".$dia."/".$ano;
          //Mes/Dia/Año  "02/02/2010
          $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
          if($int_nodias>$fech_vencmodulo)
          {
            $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
            $salida .= "                         ".$productos['fecha_vencimiento'];
            $salida .= "                      </td>\n";
          }
          else
          {
            $salida .= "                      <td style=\"background:#A9D0F5\" align=\"left\" class=\"label_mark\">\n";
            $salida .= "                         ".$productos['fecha_vencimiento'];
            $salida .= "                      </td>\n";  
          }
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
          $jaxx = "javascript:MostrarCapa('ContenedorB3');BorrarAjustes('".$tr."','".$productos['item_id']."','ContenidoB3');IniciarB3('ELIMINAR REGISTRO');";
          $salida .= "                        <a title='ELIMINAR REGISTRO' href=\"".$jaxx."\">\n";
          $salida .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
          $salida .= "                         </a>\n";
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
 }
  $objResponse->assign("tablaoide","innerHTML",$salida);
  
  return $objResponse;

}
/***************************************************************
* ELIMINAR AJUSTES DE PRODUCTOS
***************************************************************/
function BorrarAjuste($bodegas_doc_id,$doc_tmp_id,$item)
{
      $consulta=new doc_bodegas_E001();
      $objResponse = new xajaxResponse();
      $buscar=$consulta->EliminarItem($doc_tmp_id,$item);
	  
      if($buscar==1)
      {
        $objResponse->alert("EL ITEM $item FUE ELIMINADO EXITOSAMENTE");
		    $objResponse->script("xajax_RecargarProductosTmp('".$bodegas_doc_id."','".$doc_tmp_id."','".UserGetUID()."')");
        $objResponse->remove($tr);
      }
      else
      { 
	  $objResponse->alert("NO SE PUEDE BORRAR");
       } 
      
      return $objResponse;
}

function Borrar($bodegas_doc_id,$tr,$item,$CONTENIDOR)
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
      $da .= "          <input type=\"button\" class=\"input-submit\" value=\"ELIMINAR\" name=\"ELIMINAR_MOV\" onclick=\"xajax_BorrarAjuste('".$bodegas_doc_id."','".$tr."','".$item."');Cerrar('Contenedor".$C."');\">\n";
      $da .= "        </td>\n";
      $da .= "        <td align='center'>\n";
      $da .= "          <input type=\"button\" class=\"input-submit\" value=\"CANCELAR\" name=\"CANCELAR\" onclick=\"Cerrar('Contenedor".$C."');\">\n";
      $da .= "        </td>\n";
      $da .= "       </tr>\n";
      $da .= "      </table>\n";
      $objResponse->assign($CONTENIDOR,"innerHTML",$da);
      return $objResponse;
}  
function GuardarPT($bodegas_doc_id,$doc_tmp_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$usuario_id,$fecha_venc,$lotec,$valor_unitario)
{
  
  $objResponse = new xajaxResponse();
  //$objResponse->alert("codigo"); 
  $path = SessionGetVar("rutaImagenes");
  $consulta=new doc_Bodegas_E001();
  $Retorno=$consulta->GuardarTemporal($bodegas_doc_id,$doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id=null,$fecha_venc,$lotec,$valor_unitario);
  $objResponse->script("xajax_RecargarProductosTmp('".$bodegas_doc_id."','".$doc_tmp_id."','".UserGetUID()."')");
  return $objResponse;

}


function RecargarProductosTmp($bodegas_doc_id,$doc_tmp_id,$usuario_id)
{
  
  $objResponse = new xajaxResponse();
  $consulta=new doc_bodegas_E001();
  $vector=$consulta->SacarProductosTMP($doc_tmp_id,$usuario_id);
  
  //var_dump($vector);
   $path = SessionGetVar("rutaImagenes");
   $salida .= "                  <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
   $salida .= "                    <tr class=\"modulo_table_list_title\">\n";

   $salida .= "                      <td align=\"center\" width=\"10%\">\n";
   $salida .= "                        <a title='CODIGO DEL PRODUCTO'>CODIGO<a> ";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"30%\">\n";
   $salida .= "                        <a title='DESCRIPCION DEL PRODUCTO'>DESCRIPCION<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"10%\">\n";
   $salida .= "                        <a title='FECHA VENCIMIENTO'>FECHA VENCIMIENTO<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"10%\">\n";
   $salida .= "                        <a title='LOTE'>LOTE<a>";
   $salida .= "                      </td>\n";
   //$salida .= "<pre>".print_r($vector,true)."</pre>";
   /*$salida .= "                      <td align=\"center\" width=\"12%\">\n";
   $salida .= "                        <a title='UNIDAD DEL PRODUCTO'>dsdUNIDAD<a>";
   $salida .= "                      </td>\n";*/
   $salida .= "                      <td align=\"center\" width=\"8%\">\n";
   $salida .= "                        CANTIDAD";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"5%\">\n";
   $salida .= "                        <a title='PORCENTAJE DEL GRAVAMEN'> % GRAVAMEN<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"5%\">\n";
   $salida .= "                        <a title='COSTO TOTAL'>COSTO<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"2%\">\n";
   $salida .= "                        <a title='ELIMINAR REGISTRO'>X<a>";
   $salida .= "                      </td>\n";
   $salida .= "                    </tr>\n";
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
         //$objResponse->Alert("No Bloqueo".$ContadorVerificaciones);
         }
         else
            {
            $objResponse->script("document.getElementById('enar').disabled=true;");
           // $objResponse->Alert("Bloqueo".$ContadorVerificaciones);
            }
       }
    /*
    Fin Activacion del Boton Crear Documento
    */
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
          $jaxx = "javascript:MostrarCapa('ContenedorB3');BorrarAjustes('".$bodegas_doc_id."','".$doc_tmp_id."','".$productos['item_id']."','ContenedorB3');IniciarB3('ELIMINAR REGISTRO');";
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
    $consulta=new doc_Bodegas_E001();
   //echo $tip_bus; 
    if($tip_bus==2)
    {
      $aumento="AND b.codigo_producto='".$criterio."'";
      $aumento2="";
    }
    elseif($tip_bus==1)
    {
      $aumento="AND b.descripcion ILIKE '%".strtoupper($criterio)."%'";
      $aumento2="";
    }
    elseif($tip_bus==3)
    {
      $msq = new MovBodegasSQL();
      $msq->RegistrarBusqueda(UserGetUID(),$empresa_id);
      
      $aumento="AND b.codigo_barras ILIKE(UPPER('%".$criterio."%'))";
      $aumento2="";
    }
    elseif($tip_bus==4)
    {
      $aumento2="AND f.descripcion ILIKE '%".$criterio."%'";
    }
    elseif($tip_bus==5)
    {
      $aumento2="AND z.molecula_id ILIKE(UPPER('%".$criterio."%'))";
    }
    elseif($tip_bus==6)
    {
      $aumento2="AND h.descripcion ILIKE(UPPER('%".$criterio."%'))";
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
                 //$salida .= "<pre>".print_r($busqueda,true)."</pre>";
                 $salida .= "                      <td align=\"center\" width=\"35%\">\n";
                 $salida .= "                        <a title='DESCRIPCION PRODUCTO'>DESCRIPCION<a> ";
                 $salida .= "                      </td>\n";
                 /*$salida .= "                      <td align=\"center\" width=\"10%\">\n";
                 $salida .= "                        UNIDAD";
                 $salida .= "                      </td>\n";*/
                 $salida .= "                      <td align=\"center\" width=\"7%\">\n";
                 $salida .= "                        EXIST";
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
                 $salida .= "                      </td>\n";*/
                 /*$salida .= "                      <td align=\"center\" width=\"10%\">\n";
                 $salida .= "                        LABO";
                 $salida .= "                      </td>\n";*/
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
                    $salida .= "                      </td>\n";*/
                    /*$salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                    $salida .= "                         ".$busqueda[$i]['laboratorio'];
                    $salida .= "                      </td>\n";*/
                    if($busqueda[$i]['existencia']>0)
                    {
                       $salida .= "                      <td align=\"center\" onclick=\"AsignarPro('".$busqueda[$i]['codigo_producto']."','".$busqueda[$i]['descripcion']."','".$busqueda[$i]['descripcion_unidad']."','".$busqueda[$i]['costo']."','".$busqueda[$i]['existencia']."','".$fechavencimiento."','".$busqueda[$i]['lote']."');\">\n";
                       $salida .= "                         <a title='SELECCIONAR PRODUCTO'>\n";
                       $salida .= "                          <sub><img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
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
   //GuardarTmpDoc(bodegas_doc_id,observacion,centro,bodega)
function GuardarTmpDoc($bodegas_doc_id, $observacion,$perdida)
{
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta=new MovBodegasSQL();
    $consulta1=new doc_Bodegas_E001();
    $valor=$consulta1->CrearDoc($bodegas_doc_id, $observacion,$perdida);

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
                  $salida .= "                       <select name=\"tipos_idx3\" class=\"select\" onchange=\"\">";
                
                
                  for($i=0;$i<count($tipos_id_ter3);$i++)
                  {
                    $salida .="                           <option value=\"".$tipos_id_ter3[$i]['tipo_id_tercero']."\">".$tipos_id_ter3[$i]['tipo_id_tercero']."</option> \n";
                  }
                  $salida .= "                       </select>\n";
                }
      $salida .= "                        &nbsp; TERCERO ID";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"terco_id\"maxlength=\"20\" size=\"20\" value=\"\" onkeypress=\"return acceptNum(event)\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        NOMBRE";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"nom_man\" size=\"50\" value=\"\" onkeypress=\"\">"; 
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
                  $salida .= "                       <select name=\"paisex\" class=\"select\" onchange=\"Departamentos2(document.formcreausu.paisex.value);\">";
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
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"direc\"maxlength=\"50\" size=\"50\" value=\"\" onkeypress=\"\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        TELEFONO";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"phone\"maxlength=\"30\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        FAX";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"fax\"maxlength=\"30\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        E-MAIL";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"e_mail\"maxlength=\"50\" size=\"50\" value=\"\" onkeypress=\"\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        CELULAR";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"cel\"maxlength=\"30\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td colspan='2'  align=\"center\">\n";
      $salida .= "                          PERSONA NATURAL";
      $salida .= "                          <input type=\"radio\" class=\"input-text\" name=\"persona\" value=\"0\" checked>\n";
      $salida .= "                          PERSONA JURIDICA";
      $salida .= "                          <input type=\"radio\" class=\"input-text\" name=\"persona\" value=\"1\" >\n";
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

/********************************************************************
* para buscar a los teceros LISTA DE terceroS
*********************************************************************************/
function Buscadorter($pagina,$criterio1,$criterio2,$criterio,$div,$Forma)
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
      if($criterio2=="")
      $criterio2="0";
      if($criterio=="")
      $criterio="0";
      $vector=$consulta->Terceros($pagina,$criterio1,$criterio2,$criterio);
      $salida .= "                  <div id=\"ventana_terceros\">\n";
      $salida .= "                  <form name=\"buscartercero\">\n";     
      $salida .= "                   <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td  align=\"center\" colspan='3'>\n";
      $salida .= "                         BUSCADOR DE TERCEROS";
      $salida .= "                      </td>\n";
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
      
      $salida .= "                    <a title='CREAR TERCERO' class=\"label_error\" href=\"".$nuevousu."\">\n";
      $salida .= "                    <sub><img src=\"".$path."/images/inactivo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub> CREAR TERCERO\n";       
      $salida .= "                 </a>\n"; 
      $salida .= "                 </td>\n";         
      $salida .= "                 </tr>\n";         
      $salida .= "                 </table>\n";         
      $salida .= "                </form>\n";     
     
      if(count($vector)==0)
      {
        $salida .= "               <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
        $salida .= "                No se encontraron resultados con ese tipo de descripci�";       
        $salida .= "                </div>\n";       
      }
   if(count($vector)>0) 
   {
      $op="1";
      $slc=$consulta->ContarTercerosStip($criterio1,$criterio2,$criterio);
      $salida .= "".ObtenerPaginadoter($pagina,$path,$slc,$op,$criterio1,$criterio2,$criterio,$div,$Forma);
      //$objResponse->alert("Hola $vector");
      $salida .= "                 <form name=\"clientes\">\n";         
      $salida .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
//       $salida .= "                       <td align=\"center\" width=\"15%\">\n";
//       $salida .= "                         TIP TERCERO ID";
//       $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\" width=\"23%\">\n";
      $salida .= "                         TERCERO ID";
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\"width=\"47%\">\n";
      $salida .= "                         NOMBRE TERCERO";
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
            $salida .= "                       ".$vector[$i]['tipo_id_tercero']."-".strtoupper($vector[$i]['tercero_id']);
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
            $salida .= "                        ".$vector[$i]['nombre_tercero']."";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\">\n";
            $java = "javascript:Seleccionado('".$Forma."','".$vector[$i]['tipo_id_tercero']."','".strtoupper($vector[$i]['tercero_id'])."','".$vector[$i]['nombre_tercero']."');";
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