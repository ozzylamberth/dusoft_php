<?php
	/**************************************************************************************
	* $Id: definirBodegas_E012.php,v 1.1 2009/07/17 19:08:17 johanna Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Jaime gomez
	**************************************************************************************/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	//include "../../../app_modules/InvTomaFisica/classes/TomaFisicaSQL.class.php";
	include "../../../app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/E012/doc_Bodegas_E012.class.php";
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
function CrearDocumentoFinalx($bodegas_doc_id,$doc_tmp_id,$tipo_doc_bodega_id,$empresa_id,$centro_utilidad,$bodega)
{   
	$path = SessionGetVar("rutaImagenes");
    $consulta=new MovBodegasSQL();  
    $objResponse = new xajaxResponse();
	
	//Obtenemos los productos del Documento Temporal
	$cons=new doc_bodegas_E012();
	$ProductosTemporal=$cons->SacarProductosTMP($doc_tmp_id,UserGetUID());
	$DocTemporal_Auxiliar=$cons->GetDocTemporal($bodegas_doc_id,$doc_tmp_id,UserGetUID());
	$DocTemporal=$cons->DatosParaEditar($doc_tmp_id,UserGetUID());
	//print_r($DocTemporal_Auxiliar);
	//print_r($DocTemporal);
	//print_r($ProductosTemporal);
  
	//$sql = AutoCarga::factory("MovDocE012","classes","app","Inv_MovimientosBodegas");
	
    $productos=$consulta->CrearDocumentoOriginal($bodegas_doc_id,$doc_tmp_id,UserGetUID());
    if(!empty($productos))
    {
           
            foreach($ProductosTemporal as $key=>$Valor)
            {
            //$DocTemporal_Auxiliar['codigo_proveedor_id']
             $cons->CantidadesDevueltasFactura($DocTemporal_Auxiliar['numero_factura'],$Valor['codigo_producto'],$Valor['item_id_compras'],$Valor['cantidad']);
            }
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
                                                      $direccion="app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/E012/imprimir/imprimir_docE012.php";
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
      return $objResponse;
}

function MostrarProductox($bodegas_doc_id,$doc_tmp_id,$usuario_id)
{
  $objResponse = new xajaxResponse();
  $path = SessionGetVar("rutaImagenes");
  $consulta=new doc_bodegas_E012();
  $vector=$consulta->SacarProductosTMP($doc_tmp_id,$usuario_id);
  $Factura_Cabecera=$consulta->GetDocTemporal($bodegas_doc_id,$doc_tmp_id,UserGetUID());
  $sql=new MovBodegasSQL();
    //var_dump($vector);
 if(!empty($vector))
 {
   $salida .= "                  <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
   $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
   $salida .= "                      <td align=\"center\" width=\"5%\">\n";
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
   /*$salida .= "                      <td align=\"center\" width=\"12%\">\n";
   $salida .= "                        <a title='UNIDAD DEL PRODUCTO'>UNIDAD<a>";
   $salida .= "                      </td>\n";*/
   $salida .= "                      <td align=\"center\" width=\"5%\">\n";
   $salida .= "                        CANTIDAD";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"5%\">\n";
   $salida .= "                        <a title='PORCENTAJE DEL GRAVAMEN'> % GRAVAMEN<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"7%\">\n";
   $salida .= "                        <a title='VALOR UNITARIO'>VLR/U<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"7%\">\n";
   $salida .= "                        <a title='COSTO TOTAL'>COSTO<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"7%\">\n";
   $salida .= "                        <a title='TOTAL'>TOTAL<a>";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"center\" width=\"2%\">\n";
   $salida .= "                        <a title='ELIMINAR REGISTRO'>X<a>";
   $salida .= "                      </td>\n";
   $salida .= "                    </tr>\n";
   //$objResponse->script("alert(".print_r($vector).");");
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
		$salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
		$salida .= "                         ".$productos['cantidad'];
		$salida .= "                      </td>\n";
		$salida .= "                      <td align=\"right\">\n";
		$salida .= "                        ".FormatoValor($productos['porcentaje_gravamen'],2)."%";
		$salida .= "                      </td>\n";
		$salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
		$salida .= "                        $".FormatoValor(($productos['total_costo']/$productos['cantidad']),4);
		$salida .= "                      </td>\n";
		$salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
		$salida .= "                        $".FormatoValor($productos['total_costo'],4);
		$salida .= "                      </td>\n";
		$salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
		$salida .= "                        $".FormatoValor((($productos['total_costo'])+(($productos['total_costo']*($productos['porcentaje_gravamen']/100)))),4);
		$salida .= "                      </td>\n";
		$subtotal += $productos['total_costo'];
		$IvaTotal += ($productos['total_costo']*($productos['porcentaje_gravamen']/100));
		$salida .= "                      <td align=\"center\">\n";
		$jaxx = "javascript:MostrarCapa('ContenedorB3');BorrarAjustes('".$tr."','".$productos['item_id']."','ContenidoB3');IniciarB3('ELIMINAR REGISTRO');";
		$salida .= "                        <a title='ELIMINAR REGISTRO' href=\"".$jaxx."\">\n";
		$salida .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
		$salida .= "                         </a>\n";
		$salida .= "                      </td>\n";
		$salida .= "                    </tr>\n";
		$script .= "for (i=0;i<document.ProductosFactura.elements.length;i++)";
		$script .= " {";
		$script .= "  if(document.ProductosFactura.elements[i].type == \"checkbox\" && document.ProductosFactura.elements[i].value==\"".$productos['item_id_compras']."\")";
		$script .= "  {";
		$script .= "    document.ProductosFactura.elements[i].disabled=1;";
		$script .= "    document.ProductosFactura.elements[i].checked=1;";
		$script .= "  }";
		$script .= " }";
		$objResponse->script($script);
   }
		$salida .= "                    </table>\n";
		$salida .= "<br>";
		$parametros_retencion = $sql->Parametros_Retencion($Factura_Cabecera['empresa_id'],$Factura_Cabecera['anio_factura']);
	/*print_r($parametros_retencion);*/
	
	if($parametros_retencion['sw_rtf']=='2' || $parametros_retencion['sw_rtf']=='3')
	if($Factura_Cabecera['subtotal'] >= $parametros_retencion['base_rtf'])
	$retencion_fuente = $subtotal*($Factura_Cabecera['porc_rtf']/100);

	if($parametros_retencion['sw_ica']=='2' || $parametros_retencion['sw_ica']=='3')
	if($Factura_Cabecera['subtotal']  >= $parametros_retencion['base_ica'])
	$retencion_ica = $subtotal*($Factura_Cabecera['porc_ica']/1000);

	if($parametros_retencion['sw_reteiva']=='2' ||$parametros_retencion['sw_reteiva']=='3')
	if($Factura_Cabecera['subtotal']  >= $parametros_retencion['base_reteiva'])
	$retencion_iva = $IvaTotal*($Factura_Cabecera['porc_rtiva']/100);
	$salida1 .= "			<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
	$salida1 .= "				<tr align=\"center\" class=\"label\">";
	$salida1 .= "					<td>";
	$salida1 .= "						<u>SUBTOTAL</u>";
	$salida1 .= "					</td>";
	$salida1 .= "					<td>";
	$salida1 .= "						<u>IVA</u>";
	$salida1 .= "					</td>";
	$salida1 .= "					<td>";
	$salida1 .= "						<u>RET-FTE</u>";
	$salida1 .= "					</td>";
	$salida1 .= "					<td>";
	$salida1 .= "						<u>RETE-ICA</u>";
	$salida1 .= "					</td>";
	$salida1 .= "					<td>";
	$salida1 .= "						<u>RETE-IVA</u>";
	$salida1 .= "					</td>";

	$salida1 .= "					<td>";
	$salida1 .= "						<u>VALOR TOTAL</u>";
	$salida1 .= "					</td>";
	$salida1 .= "				</tr>";
	$salida1 .= "				<tr align=\"center\" >";
	$salida1 .= "					<td>";
	$salida1 .= "						$".FormatoValor($subtotal,4);
	$salida1 .= "					</td>";
	$salida1 .= "					<td>";
	$salida1 .= "						$".FormatoValor($IvaTotal,4);
	$salida1 .= "					</td>";
	$salida1 .= "					<td>";
	$salida1 .= "						$".FormatoValor($retencion_fuente,4);
	$salida1 .= "					</td>";
	$salida1 .= "					<td>";
	$salida1 .= "						$".FormatoValor($retencion_ica,4);
	$salida1 .= "					</td>";
	$salida1 .= "					<td>";
	$salida1 .= "						$".FormatoValor($retencion_iva,4);
	$salida1 .= "					</td>";

	$salida1 .= "					<td>";
	$total = (((($subtotal+$IvaTotal)-$retencion_fuente)-$retencion_ica)-$retencion_iva);
	$salida1 .= "						$".FormatoValor($total,4);
	$salida1 .= "					</td>";
	$salida1 .= "				</tr>";
	$salida1 .= "			</table>";
	$salida .= $salida1;
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
   $objResponse->call("off");
 }
  $objResponse->assign("tablaoide","innerHTML",$salida);
  
  return $objResponse;

}
/***************************************************************
* ELIMINAR AJUSTES DE PRODUCTOS
***************************************************************/
function BorrarAjuste($tr,$item)
{
      $consulta=new doc_bodegas_E012();
      $objResponse = new xajaxResponse();
      
      $ProductoTemporal=$consulta->ConsultaItemTemporal($item);
      
      $buscar=$consulta->EliminarItem($tr,$item);
     // print_r($ProductoTemporal);
      
      list($bodegas_doc_id,$doc_tmp_id) = explode("@",$tr);
      
      if($buscar==1)
      {
        $objResponse->alert("EL ITEM $item FUE ELIMINADO EXITOSAMENTE");
        $objResponse->script("xajax_MostrarProductox('".$bodegas_doc_id."','".$doc_tmp_id."','".UserGetUID()."');");    
            $script  = "for (i=0;i<document.ProductosFactura.elements.length;i++)";
            $script .= " {";
            $script .= "  if(document.ProductosFactura.elements[i].type == \"checkbox\" && document.ProductosFactura.elements[i].value==\"".$ProductoTemporal[0]['item_id_compras']."\")";
            $script .= "  {";
            $script .= "    document.ProductosFactura.elements[i].disabled=0;";
            $script .= "    document.ProductosFactura.elements[i].checked=0;";
            $script .= "  }";
            $script .= " }";
        $objResponse->script($script);
      
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
function IngresoProductosTemporal($bodegas_doc_id,$doc_tmp_id,$Formulario,$usuario_id)
{
  
  $objResponse = new xajaxResponse();
  $path = SessionGetVar("rutaImagenes");
  $consulta=new doc_bodegas_E012();
  
  for($i=0;$i<$Formulario['registros'];$i++)
    {
    if($Formulario[$i]!="")
        {
        if($Formulario['cantidad'.$Formulario[$i]]<=0 || $Formulario['cantidad'.$Formulario[$i]]=="")
          {
          $objResponse->assign("mensaje".$Formulario[$i],"innerHTML","La Cantidad No Es Correcta!!");
          }
          else
              if($Formulario['cantidad'.$Formulario[$i]]>$Formulario['total'.$Formulario[$i]])
                {
                $objResponse->assign("mensaje".$Formulario[$i],"innerHTML","La Cantidad No Puede Ser Mayor a la Existente!!");
                }
                else
                    {
                    $fecha = $Formulario['fecha_vencimiento'.$Formulario[$i]];
                    list( $ano, $mes, $dia ) = split( '[/.-]', $fecha ); 
                    $FechaVencimiento= $dia."-".$mes."-".$ano;
                    $ProductoEnTemporal=$consulta->ConsultaItemTemporal_($doc_tmp_id,$Formulario[$i]);
                    if(empty($ProductoEnTemporal))
                    {
                    $Retorno=$consulta->GuardarTemporal($bodegas_doc_id,$doc_tmp_id,$Formulario['codigo_producto'.$Formulario[$i]], $Formulario['cantidad'.$Formulario[$i]], $Formulario['porc_iva'.$Formulario[$i]], ($Formulario['cantidad'.$Formulario[$i]]*$Formulario['valor'.$Formulario[$i]]), $usuario_id=null,$FechaVencimiento,$Formulario['lote'.$Formulario[$i]]);
                    //print_r($Retorno);
                    $consulta->ItemEnMovimiento($doc_tmp_id,$Retorno,$Formulario[$i]);
                    $objResponse->script("document.getElementById('".$i."').disabled=\"true\"");
                    }
                    else
                        $objResponse->assign("mensaje".$Formulario[$i],"innerHTML","Ya se Hizo Insercion, no es posible Ingresarlo mas de Una Vez!!");
                    }
        }
    }
  
 
  //$sql = AutoCarga::factory("MovDocE012","classes","app","Inv_MovimientosBodegas");
 // $sql->RegistroLoteDevuelto($doc_tmp_id,$codigo_producto,$lotec,$lote_devuelto); 
  
  
  $objResponse->assign("codigo","value","");
  $objResponse->call("super");
  $objResponse->script("xajax_MostrarProductox('".$bodegas_doc_id."','".$doc_tmp_id."','".UserGetUID()."');");
  
  return $objResponse;

}
/************************************************
* funcion pra buscar productos
*************************************************/

function BuscarProducto1($empresa_id,$centro_utilidad,$bodega,$tip_bus,$criterio,$tipo_prestamo_id,$offset)
{                       
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta=new doc_bodegas_E012();
   //echo $tip_bus; 
    if($tip_bus==2)
    {
      $aumento="AND b.codigo_producto='".$criterio."'";
    }
    elseif($tip_bus==1)
    {
      $aumento="AND b.descripcion ILIKE '%".strtoupper($criterio)."%'";
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
      $busqueda=$consulta->BuscarProducto($empresa_id,$centro_utilidad,$bodega,$aumento,$offset);
      
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
           $salida .= "                      <td align=\"center\" width=\"5%\">\n";
           $salida .= "                        <a title='SELECCIONAR PRODUCTO'>SL<a>";
           $salida .= "                      </td>\n";
           $salida .= "                    </tr>\n";         
            for($i=0;$i<count($busqueda);$i++)
            {   
              $NombreProducto=$consulta->DescripcionProducto($busqueda[$i]['codigo_producto']);
              $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
              $salida .= "                      <td align=\"left\">\n";
              $salida .= "                        ".$busqueda[$i]['codigo_producto'];
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
              $salida .= "                          ".$NombreProducto[0]['descripcion_producto'];
              //$salida .= "                        ".$busqueda[$i]['descripcion']." ".$busqueda[$i]['contenido_unidad_venta'];
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
              $salida .= "                     </td>\n";
              $salida .= "                    </tr>\n";
           }   
              $salida .= "                </table>\n";
              
           $Cont=$consulta->ContarProStip($empresa_id,$centro_utilidad,$bodega,$aumento);
           $malo=$Cont[0]['count'];
                         $action = "Bus_Pro('".$empresa_id."','".$centro_utilidad."','".$bodega."','".$tip_bus."','".$criterio."','".$tipo_prestamo_id."'";
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

/******************************************************************************
* para guardar un documento temporal
********************************************************************************/
//GuardarTmpDoc(bodegas_doc_id, observacion, tipo_id_tercero, tercero_id, prestamo);
function GuardarTmpDoc($bodegas_doc_id, $observacion, $codigo_proveedor_id, $factura)
{
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta1=new doc_bodegas_E012();
    $valor=$consulta1->CrearDoc($bodegas_doc_id, $observacion, $codigo_proveedor_id, $factura);

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

/********************************************************************
* para buscar a los teceros LISTA DE terceroS
*********************************************************************************/
function BuscadorProveedores($div,$Forma)
{
      $objResponse = new xajaxResponse(); 
      $path = SessionGetVar("rutaImagenes");
      $sql = AutoCarga::factory("MovDocE012","classes","app","Inv_MovimientosBodegas");
      $Listar_Terceros = $sql -> Listar_TiposIdTerceros();
      
      $select  = "<select class=\"select\" id=\"tipo_id_tercero\" name=\"tipo_id_tercero\">";
      $select .= "<option value=\"\">---</option>";
        foreach($Listar_Terceros as $k=>$valor)
        $select .= "<option value=\"".$valor['tipo_id_tercero']."\">".$valor['descripcion']."</option>";
      $select .= "</select>";
      
      
      $salida .= "                  <form name=\"buscartercero\">\n";     
      $salida .= "                   <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td  align=\"center\" colspan='3'>\n";
      $salida .= "                         BUSCADOR DE TERCEROS";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"44%\"  align=\"center\">\n";
      $salida .= "                        <b>NOMBRE TERCERO :</b>";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"31%\" align=\"right\" >\n";
      $salida .= "                         <input style=\"width:100%;height:100%\" type=\"text\" class=\"input-text\" name=\"nombre_tercero\" id=\"nombre_tercero\" maxlength=\"40\" size\"40\" value=\"".$criterio."\" onkeypress=\"return acceptm(event)\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                       </tr>\n";
      $salida .= "                       <tr class=\"modulo_list_claro\" id=\"tres\">\n";
      $salida .= "                           <td align='center'>";
      $salida .= "                             <b>TIPO ID :</b>".$select;  
      $salida .="                          </td>\n";
      $salida .="                          <td ALIGN='right'>\n";
      $salida .="                             <b>ID :</b>\n";
      $salida .="                            <input style=\"width:100%;height:100%\" type=\"text\" class=\"input-text\" name=\"tercero_id\" id=\"tercero_id\" maxlength=\"40\" size\"40\" ></td>";
      $salida .="                         </td>\n";
      $salida .= "                       </tr>\n";
      $salida .= "                <tr class=\"modulo_list_claro\">";
      $salida .= "                       <td colspan=\"2\" width=\"10%\" align=\"center\">\n";                                                                 //$pagina,            $criterio1,                                   $criterio2,                           $criterio                          ,$div,      $Forma
      $salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"boton_bus\"  id=\"boton_bus\" value=\"BUSCAR\" onclick=\"xajax_BuscadorProveedor_d(".SessionGetVar("EMPRESA").",document.getElementById('tipo_id_tercero').value,document.getElementById('tercero_id').value,document.getElementById('nombre_tercero').value,'1');\")\">\n";
      $salida .= "                       </td>\n";
      $salida .= "                </tr>";
      $salida .= "                 </table>\n";         
      $salida .= "                 <table width='85%' border='0' align=\"center\">\n";         
      $salida .= "                 </table>\n";         
      $salida .= "                </form>\n";
      $salida .= "                <br>";      
      //Será desplegada la lista de Terceros Proveedores
      $salida .= "            <div id=\"ListaDeProveedores\">";      
      $salida .= "            </div>";      
      
      $salida = $objResponse->setTildes($salida);
      $objResponse->script("xajax_BuscadorProveedor_d(".SessionGetVar("EMPRESA").",'','','','1');");
      $objResponse->assign("".$div."","innerHTML",$salida);
      return $objResponse;
    }
    
    
/********************************************************************
* para buscar a los teceros LISTA DE terceroS
*********************************************************************************/
function BuscadorProveedor_d($empresa_id,$tipo_id_tercero,$tercero_id,$nombre_tercero,$offset)
{
      $objResponse = new xajaxResponse(); 
      $path = SessionGetVar("rutaImagenes");
      $sql = AutoCarga::factory("MovDocE012","classes","app","Inv_MovimientosBodegas");
      $Listar_Terceros = $sql ->Listar_TercerosProveedores($tipo_id_tercero,$tercero_id,$nombre_tercero,$empresa_id,$offset);
      $action['paginador'] = "Paginador('".$empresa_id."','".$tipo_id_tercero."','".$tercero_id."','".$nombre_tercero."'";
      $pghtml = AutoCarga::factory("ClaseHTML");
    
      if(!empty($Listar_Terceros))
      {
      $salida .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);     
      $salida .= "                   <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td  align=\"center\" >\n";
      $salida .= "                       ID";
      $salida .= "                      </td>\n";
      $salida .= "                      <td  align=\"center\" >\n";
      $salida .= "                       NOMBRE";
      $salida .= "                      </td>\n";
      $salida .= "                      <td  align=\"center\" >\n";
      $salida .= "                       OP";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
        foreach($Listar_Terceros as $key=>$valor)
        {
        $salida .= "<tr class=\"modulo_list_claro\" >";
        $salida .= "<td>".$valor['tipo_id_tercero']."-".$valor['tercero_id']."</td>";
        $salida .= "<td>".$valor['nombre_tercero']."</td>";
        $salida .= "      <td align=\"center\">\n";
        //formulario,tipo_id_tercero,tercero_id,nombre_tercero
        $salida .= "        <a href=\"#\" onclick=\"Seleccionado('unocreate','".$valor['tipo_id_tercero']."','".$valor['tercero_id']."','".$valor['nombre_tercero']."','".$valor['codigo_proveedor_id']."','".$empresa_id."')\">\n";
        $salida .= "          <img title=\"SELECCIONAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
        $salida .= "        </a>\n";
        $salida .= "      </td>\n";
        }
      }
   
      $salida = $objResponse->setTildes($salida);
      $objResponse->assign("ListaDeProveedores","innerHTML",$salida);
      return $objResponse;
    }
    
/********************************************************************
* para buscar a los teceros LISTA DE terceroS
*********************************************************************************/
function Listar_FacturasProveedor($CodigoProveedorId,$Empresa_Id)
{
      $objResponse = new xajaxResponse(); 
      $path = SessionGetVar("rutaImagenes");
      $sql = AutoCarga::factory("MovDocE012","classes","app","Inv_MovimientosBodegas");
      $facturas=$sql->Listar_FacturasProveedor($CodigoProveedorId,SessionGetVar("EMPRESA"));
		  $select  = "<select class=\"select\" name=\"factura\" id=\"factura\">";
      $select .= "<option value=\"\">---</option>";
        foreach($facturas as $key => $valor)
        {
        $select .=" <option value=\"".$valor['numero_factura']."\">".$valor['numero_factura']."</option>";
        }
		  $select .= "</select>";
      $salida = $objResponse->setTildes($select);
      $objResponse->assign("facturas_proveedor","innerHTML",$salida);
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

/*******************************************************************************
funcion para Registrar un Factura
*******************************************************************************/   
 function RegistrarFactura($empresa_id,$centro_utilidad,$bodega,$tmp_doc_id,$NumeroFactura,$Prefijo,$tercero_id,$tipo_id_tercero)
 {  
    
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("MovDocE012","classes","app","Inv_MovimientosBodegas");
    
    $Facturas=$sql->BuscarFactura($NumeroFactura,$Prefijo,$empresa_id);
    $num=count($Facturas);
    if($num<1)
        {
        $html ="No hay Facturas!!!"; 
        }
        else
        {
		$token=$sql->RegistrarFacturaDespacho($tmp_doc_id,UserGetUID(),$Prefijo,$NumeroFactura);
         $java = "MostrarCapa('ContenedorBus');Bus_ProductosFacturaDespacho('".$empresa_id."','".$centro_utilidad."','".$bodega."','".$Prefijo."','".$NumeroFactura."','".$tipo_id_tercero."','".$tercero_id."','','','1');Iniciar4('BUSCAR PRODUCTOS DE FACTURA DESPACHO CLIENTE');Clear3000();\"";
		 $html .="Hay Factura!!!<br>";
		 $html .="<a onclick=\"".$java."\">VER PRODUCTOS</a>";
		 $objResponse->script("document.getElementById('num_factura').value='".$NumeroFactura."';");
		 $objResponse->script("document.getElementById('num_factura').disabled=true;");
		 
		 $objResponse->script("
					var combo = document.getElementById('prefijo_');
					var cantidad = combo.length;
						for (i = 0; i < cantidad; i++) {
						if (combo[i].value == '".$Prefijo."') {
						combo[i].selected = true;
						}   
						}
		 ");
		 
        }
    //$token=$sql->RegistrarFactura($empresa_id,$tmp_doc_id,$NumeroFactura);
    
      
      
   $objResponse->assign("mensaje","innerHTML",$html);  
    return $objResponse;

 }

 
 /*******************************************************************************
funcion para Registrar un Factura
*******************************************************************************/   
 function RegistrarFacturaProveedor($empresa_id,$centro_utilidad,$bodega,$CodigoProveedorId,$NumeroFactura,$doc_tmp_id,$usuario_id)
 {  
    
    $objResponse = new xajaxResponse(); 
    $sql = AutoCarga::factory("MovDocE012","classes","app","Inv_MovimientosBodegas");
    
    $Facturas=$sql->BuscarFacturaProveedor($empresa_id,$centro_utilidad,$bodega,$CodigoProveedorId,$NumeroFactura);
	
	$java = "MostrarCapa('ContenedorBus');Bus_ProductosFactura('".$empresa_id."','".$centro_utilidad."','".$bodega."','".$CodigoProveedorId."','".$NumeroFactura."','','','".$doc_tmp_id."','1');Iniciar4('BUSCAR PRODUCTOS DE FACTURA PROVEEDOR');Clear3000();\"";
    
	$num=count($Facturas);
    if($num<1)
        {
        $html ="No hay Facturas!!!"; 
        }
        else
        {
         //$java = "MostrarCapa('ContenedorBus');Bus_ProductosFacturaDespacho('".$empresa_id."','".$centro_utilidad."','".$bodega."','".$Prefijo."','".$NumeroFactura."','".$tipo_id_tercero."','".$tercero_id."','','','1');Iniciar4('BUSCAR PRODUCTOS DE FACTURA DESPACHO CLIENTE');Clear3000();\"";
		 $html .="Hay Factura!!!<br>";
		 $html .="<a onclick=\"".$java."\">VER PRODUCTOS</a>";
     $token=$sql->RegistrarFacturaProveedor($doc_tmp_id,$usuario_id,$NumeroFactura);
     $objResponse->script("document.getElementById('num_factura').disabled=true;");
        }
    //$token=$sql->RegistrarFactura($empresa_id,$tmp_doc_id,$NumeroFactura);
    
      
      
   $objResponse->assign("mensaje_registro","innerHTML",$html);  
    return $objResponse;

 }

?>