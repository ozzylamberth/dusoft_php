<?php
	/**************************************************************************************
	* $Id: definirBodegas_T003.php,v 1.2 2010/07/07 15:40:25 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Mauricio Medina
	**************************************************************************************/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	//include "../../../app_modules/InvTomaFisica/classes/TomaFisicaSQL.class.php";
	include "../../../app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/T003/doc_Bodegas_T003.class.php";
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
  //    if(!empty($centrosx) && count($centrosx)>1)
    //{
          $salida .= "                       <select id=\"bod_des\" name=\"bod_des\" class=\"select\" onchange=\"\">";//alert(this.value);
          $salida .= "                           <option value=\"\" SELECTED>SELECCIONAR</option>\n"; 
          //var_dump($utility);
          for($i=0;$i<count($centrosx);$i++)
          {
            $salida .= "                      <option value=\"".$centrosx[$i]['bodega']."\">".$centrosx[$i]['descripcion']."</option>\n";
          }
          $salida .= "                       </select>\n";
          $objResponse->assign("centros","innerHTML",$salida);
    //}
    //else
    //{
      $objResponse->assign("bod_des","value",$centrosx[0]['centro_utilidad']);
      //$salida .= "<LABEL>".$centrosx[0]['descripcion']."</LABEL>";
        $objResponse->assign("centros","innerHTML",$salida);
    //}
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
function CrearDocumentoFinalx($bodegas_doc_id,$doc_tmp_id)
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
                                                       $direccion="app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/T003/imprimir/imprimir_docT003.php";
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
 
function MostrarProductox($bodegas_doc_id,$doc_tmp_id,$usuario_id)
{
  $objResponse = new xajaxResponse();
  $path = SessionGetVar("rutaImagenes");
  $consulta=new doc_Bodegas_T003();
  $vector=$consulta->SacarProductosTMP($doc_tmp_id,$usuario_id);
    //var_dump($vector);
  if(!empty($vector))
  {
    $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    $salida .= "                      <td align=\"center\" width=\"12%\">\n";
    $salida .= "                        <a title='CODIGO DEL PRODUCTO'>CODIGO<a> ";
    $salida .= "                      </td>\n";
    $salida .= "                      <td align=\"center\" width=\"30%\">\n";
    $salida .= "                        <a title='DESCRIPCION DEL PRODUCTO'>DESCRIPCION<a>";
    $salida .= "                      </td>\n";
    $salida .= "                      <td width=\"10%\">LOTE</td>\n";    
    $salida .= "                      <td width=\"10%\">FECHA VENCIMIENTO</td>\n";    
    $salida .= "                      <td align=\"center\" width=\"12%\">\n";
    $salida .= "                        CANTIDAD";
    $salida .= "                      </td>\n";
    $salida .= "                      <td align=\"center\" width=\"12%\">\n";
    $salida .= "                        <a title='PORCENTAJE DEL GRAVAMEN'> % GRAVAMEN<a>";
    $salida .= "                      </td>\n";
    $salida .= "                      <td align=\"center\" width=\"10%\">\n";
    $salida .= "                        <a title='COSTO TOTAL'>COSTO<a>";
    $salida .= "                      </td>\n";
    $salida .= "                      <td align=\"center\" width=\"%\">\n";
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
          
          $salida .= "                      <td class=\"label_mark\">".$productos['lote']."</td>\n";
          $salida .= "                      <td align=\"center\" class=\"label_mark\">".$productos['fecha_vencimiento']."</td>\n";
          
          
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
function BorrarAjuste($tr,$item)
{
      $consulta=new doc_Bodegas_T003();
      $objResponse = new xajaxResponse();
      $buscar=$consulta->EliminarItem($tr,$item);
      if($buscar==1)
      {
        $objResponse->alert("EL ITEM $item FUE ELIMINADO EXITOSAMENTE");
        $objResponse->remove($tr);
      }
      else
      { $objResponse->alert("NO SE PUEDE BORRAR");
       } 
      
      return $objResponse;
}

FUNCTION Borrar($tr,$item,$CONTENIDOR)
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
      $da .= "          <input type=\"button\" class=\"input-submit\" value=\"ELIMINAR\" name=\"ELIMINAR_MOV\" onclick=\"xajax_BorrarAjuste('".$tr."','".$item."');Cerrar('Contenedor".$C."');\">\n";
      $da .= "        </td>\n";
      $da .= "        <td align='center'>\n";
      $da .= "          <input type=\"button\" class=\"input-submit\" value=\"CANCELAR\" name=\"CANCELAR\" onclick=\"Cerrar('Contenedor".$C."');\">\n";
      $da .= "        </td>\n";
      $da .= "       </tr>\n";
      $da .= "      </table>\n";
      $objResponse->assign($CONTENIDOR,"innerHTML",$da);
      return $objResponse;
}  
  //function GuardarPT($bodegas_doc_id,$doc_tmp_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$usuario_id)
  function GuardarPT($form)
  {
    $objResponse = new xajaxResponse();
    $consulta=new doc_Bodegas_T003();
    
    if($form['codigo_producto'] == "")
    {
      $objResponse->assign('error_doc',"innerHTML","NO HA SELECCIONADO NINGUN PRODUCTO");
      return $objResponse;
    }

    if(!is_numeric($form['cantidad']))
    {
      $objResponse->assign('error_doc', "innerHTML", "FALTA INGRESAR LA CANTIDAD DE UNIDADES DEL PRODUCTO");
      return $objResponse;
    }
    
    if($form['cantidad']*1 > $form['existencia']*1)
    {
      $objResponse->assign('error_doc', "innerHTML", "LA CANTIDAD A TRASLADAR NO PUEDE SER MAYOR A ".$form['existencia']);
      return $objResponse;
    }
    
    if(!$form['fecha_vencimiento'])
    {
      $objResponse->assign('error_doc', "innerHTML", "FALTA INGRESAR  LA FECHA DE VENCIMIENTO DEL PRODUCTO");
      return $objResponse;
    }
    
    if($form['lote']=="")
    {
      $objResponse->assign('error_doc', "innerHTML", "FALTA INGRESAR EL LOTE DEL PRODUCTO");
      return $objResponse;
    }
    
    $ctl = AutoCarga::factory("ClaseUtil");
    if($ctl->CompararFechas($form['fecha_vencimiento'],date("d/m/Y")) < 0 )
    {
      $objResponse->assign('error_doc', "innerHTML", "LA FECHA DE VENCIMIENTO DEBE SER MAYOR A LA FECHA DE HOY");
      return $objResponse;
    }
    
    $Retorno = $consulta->GuardarTemporal($form['bodegas_doc_id'],$form['tmp_doc_id'], $form['codigo_producto'], $form['cantidad'], $form['porcentaje_gravamen'], $form['costeno_val']*$form['cantidad'], $form['usuario_id'],$form['fecha_vencimiento'],$form['lote']);
    if($Retorno === false)
    {
      $objResponse->assign('error_doc','innerHTML',$consulta->mensajeDeError);
    }
	
	
	$objResponse->script("xajax_MostrarProductox('".$form['bodegas_doc_id']."','".$form['tmp_doc_id']."','".UserGetUID()."');");
	
   /* $vector = $consulta->SacarProductosTMP($form['tmp_doc_id'],$form['usuario_id']);
    $objResponse->assign("codigo","value","");
    $objResponse->call("super");
  
    $path = SessionGetVar("rutaImagenes");
    $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $salida .= "                    <tr class=\"modulo_table_list_title\">\n";

    $salida .= "                      <td align=\"center\" width=\"12%\">\n";
    $salida .= "                        <a title='CODIGO DEL PRODUCTO'>CODIGO<a> ";
    $salida .= "                      </td>\n";
    $salida .= "                      <td align=\"center\" width=\"30%\">\n";
    $salida .= "                        <a title='DESCRIPCION DEL PRODUCTO'>DESCRIPCION<a>";
    $salida .= "                      </td>\n";
    $salida .= "                      <td width=\"10%\">LOTE</td>\n";    
    $salida .= "                      <td width=\"10%\">FECHA VENCIMIENTO</td>\n";    
    $salida .= "                      <td align=\"center\" width=\"30%\">\n";
    $salida .= "                        <a title='FORMA FARMACOLOGICA'>FORMA<a>";
    $salida .= "                      </td>\n";
    $salida .= "                      <td align=\"center\" width=\"30%\">\n";
    $salida .= "                        <a title='CONCENTRACION'>CONCENTRACION<a>";
    $salida .= "                      </td>\n";
    $salida .= "                      <td align=\"center\" width=\"12%\">\n";
    $salida .= "                        <a title='UNIDAD DEL PRODUCTO'>UNIDAD<a>";
    $salida .= "                      </td>\n";
    $salida .= "                      <td align=\"center\" width=\"12%\">\n";
    $salida .= "                        CANTIDAD";
    $salida .= "                      </td>\n";
    $salida .= "                      <td align=\"center\" width=\"12%\">\n";
    $salida .= "                        <a title='PORCENTAJE DEL GRAVAMEN'> % GRAVAMEN<a>";
    $salida .= "                      </td>\n";
    $salida .= "                      <td align=\"center\" width=\"10%\">\n";
    $salida .= "                        <a title='COSTO TOTAL'>COSTO<a>";
    $salida .= "                      </td>\n";
    $salida .= "                      <td align=\"center\" width=\"%\">\n";
    $salida .= "                        <a title='ELIMINAR REGISTRO'>X<a>";
    $salida .= "                      </td>\n";
    $salida .= "                    </tr>\n";
    foreach($vector as $valor=>$productos)
    {
      $tr = $form['bodegas_doc_id']."@".$productos['doc_tmp_id'];
      $salida .= "                    <tr id='".$tr."' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
      $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
      $salida .= "                        ".$productos['codigo_producto'];
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
      $salida .= "                         ".$productos['descripcion'];
      $salida .= "                      </td>\n";      
      $salida .= "     <td class=\"label_mark\">".$productos['lote']."</td>\n";
      $salida .= "     <td align=\"center\" class=\"label_mark\">".$productos['fecha_vencimiento']."</td>\n";
      $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
      $salida .= "                         ".$productos['forma_farmacologica'];
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
      $salida .= "                         ".$productos['concentracion'];
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
*/
    $salida .= "                    </table>\n";
    $objResponse->assign("tablaoide","innerHTML",$salida);
    $objResponse->script("Clear();");
    return $objResponse;
  }


  function BuscarProducto1($empresa_id,$centro_utilidad,$bodega,$tip_bus,$criterio,$offset,$tmp_doc_id)
  {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new doc_bodegas_T003();
  
    $doc_tmp = $consulta->ObtenerInformacionTraslado($tmp_doc_id);
    
    $aumento = "";
    if($tip_bus==2)
      $aumento="AND b.codigo_producto='".$criterio."'";
    elseif($tip_bus==1)
      $aumento="AND b.descripcion LIKE '%".strtoupper($criterio)."%'";

    $busqueda = $consulta->BuscarProducto($empresa_id,$centro_utilidad,$bodega,$aumento,$offset,$doc_tmp);
    if(!empty($busqueda))
    {
      $salida .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
      $salida .= "                 </div>\n";
      $salida .= "                 <form name=\"adicionar\">\n";
      $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td width=\"30%\" colspan=\"2\">PRODUCTO</td>\n";
      $salida .= "                      <td width=\"10%\">LOTE</td>\n";
      $salida .= "                      <td width=\"10%\">FECHA VENCIMIENTO</td>\n";
      $salida .= "                      <td width=\"15%\">\n";
      $salida .= "                        <a title='FORMA FARMACOLOGICA'>FORMA<a> ";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"10%\">\n";
      $salida .= "                        <a title='CONCENTRACION'>CONCEN<a> ";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"10%\">\n";
      $salida .= "                        UNIDAD";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"10%\">\n";
      $salida .= "                        EXISTENCIA";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"10%\">\n";
      $salida .= "                        COSTO";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"%\">\n";
      $salida .= "                        <a title='SELECCIONAR PRODUCTO'>SL<a>";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      foreach($busqueda as $key => $dt2)
      {
        $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
        $salida .= "                      <td >".$dt2['codigo_producto']."</td>\n";
        $salida .= "                      <td >".$dt2['descripcion']."</td>\n";
        $salida .= "                      <td >".$dt2['lote']."</td>\n";
        $salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
        $salida .= "                        ".$dt2['fecha_vencimiento'];
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
        $salida .= "                        ".$dt2['forma_farmacologica'];
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
        $salida .= "                        ".$dt2['concentracion'];
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
        $salida .= "                         ".$dt2['descripcion_unidad'];
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
        $salida .= "                         ".$dt2['existencia'];
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"right\">\n";
        $salida .= "                         <a title='COSTO PROMEDIO'>\n";
        $salida .= "                         ".$dt2['costo'];
        $salida .= "                         </a>\n";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" onclick=\"xajax_AsignarProducto('".$dt2['codigo_producto']."','".$dt2['descripcion']."','".$dt2['descripcion_unidad']."','".$dt2['costo']."','".$dt2['existencia']."','".$dt2['lote']."','".$dt2['fecha_vencimiento']."');\">\n";
        $salida .= "                         <a title='SELECCIONAR PRODUCTO'>\n";
        $salida .= "                          <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
        $salida .= "                         </a>\n";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
      }
      $salida .= "                </table>\n";
    }
    $objResponse->assign("tabelos","innerHTML",$salida);
    return $objResponse;
  }
  /**
  *
  */
  function AsignarProducto($codigo_producto,$descripcion,$descripcion_unidad,$costo,$existencia,$lote,$fecha_vencimiento)
  {
    $objResponse = new xajaxResponse();
    
    $objResponse->assign('codigo_pro',"innerHTML",$codigo_producto);
    $objResponse->assign('desc_pro',"innerHTML",$descripcion);
    $objResponse->assign('unidad_pro',"innerHTML",$descripcion_unidad);
    $objResponse->assign('costeno',"innerHTML",$costo);
    $objResponse->assign('existo',"innerHTML",$existencia);
    $objResponse->assign('codigo',"value",$codigo_producto);
    $objResponse->assign('costeno_val',"value",$costo);
    $objResponse->assign('existo_val',"value",$existencia);
    
    if(!$fecha_vencimiento)
    {
      $html = "<input type=\"text\" name=\"fecha_vencimiento\" value=\"\" class=\"input-text\" style=\"width:100%\">\n";
      $html1 = "<input type=\"text\" name=\"lote\" value=\"\" class=\"input-text\" style=\"width:50%\">\n";
      $objResponse->assign("fecha_div","style.display","block");
    }
    else
    {
      $html = $fecha_vencimiento."<input type=\"hidden\" id=\"fecha_vencimiento\" name=\"fecha_vencimiento\"  value=\"".$fecha_vencimiento."\">\n";
      $html1 = $lote."<input type=\"hidden\" id=\"lote\" name=\"lote\"  value=\"".$lote."\">\n";

      $objResponse->assign("fecha_div","style.display","none");
    }
    $objResponse->assign("label_fecha","innerHTML",$html);
    $objResponse->assign("label_lote","innerHTML",$html1);
    $objResponse->script("Cerrar('ContenedorBus');");
    
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
function GuardarTmpDoc($bodegas_doc_id, $observacion,$centro,$bodega)
{
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta=new MovBodegasSQL();
    $consulta1=new doc_Bodegas_T003();
    $valor=$consulta1->CrearDoc($bodegas_doc_id, $observacion,$centro,$bodega);

    //$salida=PintarTabla($valor);
    if(!empty($valor))
    {
    $objResponse->assign("doc_tmp_id_h","value",$valor['doc_tmp_id']);
    sleep(1); 
    $objResponse->assign("accion_h","value",'EDITAR');
    $objResponse->call("mar1");
    }
    else
      {
      $objResponse->alert($consulta1->mensajeDeError);
      }

    return $objResponse;
}

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