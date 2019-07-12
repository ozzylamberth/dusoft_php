<?php
	/**************************************************************************************
	* $Id: definirBodegas_I013.php,v 1.1 2009/07/17 19:08:17 johanna Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Mauricio Medina
	**************************************************************************************/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	//include "../../../app_modules/InvTomaFisica/classes/TomaFisicaSQL.class.php";
	include "../../../app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I013/doc_Bodegas_I013.class.php";
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
	$cons=new doc_bodegas_I013();
	$ProductosTemporal=$cons->SacarProductosTMP($doc_tmp_id,UserGetUID());
	$DocTemporal_Auxiliar=$cons->GetDocTemporal($bodegas_doc_id,$doc_tmp_id,UserGetUID());
	$DocTemporal=$cons->DatosParaEditar($doc_tmp_id,UserGetUID());
    $productos=$consulta->CrearDocumentoOriginal($bodegas_doc_id,$doc_tmp_id,UserGetUID());
   
    if(!empty($productos))
    {
                foreach($ProductosTemporal as $key=>$detalle)
                          {
						  list($bodegas_doc_id,$numeracion,$consecutivo) = explode("@",$detalle['lote_devuelto']);
                          $cons->Modificar_CantidadesDocumento($bodegas_doc_id,$numeracion,$consecutivo,$detalle['cantidad']);
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
                                                      $direccion="app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I001/imprimir/imprimir_docI001.php";
                                                       $imagen = $path."/images/imprimir.png";
                                                       $alt="IMPRIMIR DOCUMENTO";
                                                       $x=RetornarImpresionDoc($direccion,$alt,$imagen,SessionGetVar("EMPRESA"),$productos['prefijo'],$productos['numero']);
                    $salida .= "                     ".$x."";                    $salida .= "                      </td>\n";
                    
                    $salida .= "                    </tr>\n";
            
            
            $salida .= "                    </table>\n";



		
        $objResponse->assign("ventana1","innerHTML",$salida);
        $objResponse->call("superoff");
        $objResponse->assign("error_doc","innerHTML","SE HA CREADO EL DOCUMENTO EXITOSAMENTE");
        $script = " document.getElementById('codigo_barras').disabled=true;";
        $script .= " document.getElementById('descripcion').disabled=true;";
		$borrardevo=$consulta->BorrarDevolucion_doc($tipo_doc_bodega_id,$doc_tmp_id);
        $borrarpara=$consulta->Borrarpara_docg($tipo_doc_bodega_id,$doc_tmp_id);
        $objResponse->script($script);
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
  $consulta=new doc_bodegas_I013();
  $vector=$consulta->SacarProductosTMP($doc_tmp_id,$usuario_id);
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
   $salida .= "                      <td align=\"center\" width=\"5%\">\n";
   $salida .= "                        CANTIDAD";
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
      $consulta=new doc_bodegas_I013();
      $objResponse = new xajaxResponse();
      
      $ProductoTemporal=$consulta->ConsultaItemTemporal($item);
      
      $buscar=$consulta->EliminarItem($tr,$item);
     // print_r($ProductoTemporal);
      
      list($bodegas_doc_id,$doc_tmp_id) = explode("@",$tr);
      
      if($buscar==1)
      {
        $objResponse->alert("EL ITEM $item FUE ELIMINADO EXITOSAMENTE");
        $objResponse->script("xajax_MostrarProductox('".$bodegas_doc_id."','".$doc_tmp_id."','".UserGetUID()."');");    
        $objResponse->script("xajax_ListadoProductos_Formula(document.getElementById('bodegas_doc_id').value,document.getElementById('doc_tmp_id').value,document.getElementById('formula_id').value,document.getElementById('formula_papel').value,document.getElementById('empresa_idz').value,document.getElementById('codigo_barras').value,document.getElementById('descripcion').value);");
        $objResponse->script($script);
      
      }
      else
      { $objResponse->alert("NO SE PUEDE BORRAR");
       } 
      
      return $objResponse;
}

function Borrar($tr,$item,$CONTENIDOR)
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
  $consulta=new doc_bodegas_I013();
  $dias_vencimiento = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.trim(SessionGetVar('EMPRESA')));
   $fecha_actual=date("m/d/Y");
  
  for($i=0;$i<$Formulario['registros'];$i++)
    {
    $mensaje = "";
    if($Formulario[$i]!="")
        {
        if($Formulario['cantidad'.$i]=="" )
          {
          $objResponse->assign("mensaje".$Formulario[$i],"innerHTML","La Cantidad No Es Correcta!!");
          }
          
                          
                    $fecha = $Formulario['fecha_vencimiento'.$i];
                    list( $ano, $mes, $dia ) = split( '[/.-]', $fecha ); 
                    $FechaVencimiento= $dia."-".$mes."-".$ano;
                    /*
                    Para Evaluar si el Producto es proximo a Vencer o Vencido
                    */
                   
                    $fecha__ = $mes."/".$dia."/".$ano;
                    $fecha_ = $ano."/".$mes."/".$dia;
                    //Para despues verificar si un producto está vencido o nó
                    $fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
                    $fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
                    $int_nodias = floor(abs(strtotime($fecha__) - strtotime($fecha_actual))/86400);
                   // print_r($int_nodias);
                    /*
                    Fin Evaluacion
                    */
                    
                        if($fecha_dos<=$fecha_uno_act)
                          {
                          $mensaje .=" - ¡PRODUCTO VENCIDO¡\n";
						  $objResponse->assign("error".$i,"innerHTML",$mensaje);
						  break;
                          }
                          else
                             if($int_nodias < $dias_vencimiento)
                                {
                                $mensaje .= "  - El Producto Está proximo a Vencer.\n ";
								 $objResponse->assign("error".$i,"innerHTML",$mensaje);
                                }
					
                                                    
                    $ProductoEnTemporal=$consulta->ConsultaItemTemporal_($doc_tmp_id,$Formulario[$i]);
                    
                    if(empty($ProductoEnTemporal))
                    {
                    
                    $Retorno=$consulta->GuardarTemporal($bodegas_doc_id,$doc_tmp_id,$Formulario['codigo_producto'.$i], $Formulario['cantidad'.$i], 0, ($Formulario['cantidad'.$i]*$Formulario['valor'.$i]), $usuario_id=null,$FechaVencimiento,$Formulario['lote'.$i]);
                    $consulta->ItemEnMovimiento($doc_tmp_id,$Retorno,$Formulario[$i]);
                    $objResponse->script("document.getElementById('".$i."').disabled=\"true\"");
                    $ProductoTemporal=$consulta->ConsultaItemTemporal($Retorno);
                    
                    //print_r($ProductoTemporal);
                    }
                    else
                        $objResponse->assign("mensaje".$Formulario[$i],"innerHTML","Ya se Hizo Insercion, no es posible Ingresarlo mas de Una Vez!!");
                    }
        
    }
  
  $objResponse->assign("codigo","value","");
  $objResponse->call("super");
  $objResponse->script("xajax_MostrarProductox('".$bodegas_doc_id."','".$doc_tmp_id."','".UserGetUID()."');");
  
  return $objResponse;

}

function ListadoProductos_Formula($bodegas_doc_id,$doc_tmp_id,$formula_id,$formula_papel,$empresa_id,$codigo_barras,$descripcion)
{                       
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $sql=new MovBodegasSQL();
    $consulta=new doc_bodegas_I013();
    
    $ProductosDocumento = $consulta ->Productos_Formula($formula_papel,$empresa_id,$codigo_barras,$descripcion);
   
    
      $salida .= "                 <form name=\"Productos_Formula\" id=\"Productos_Formula\"  method=\"post\">\n";
      $salida .= "                 <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "                  <tr class=\"modulo_table_list_title\">";
      $salida .= "                      <td>CODIGO</td><td>NOMBRE</td><td>CANTIDAD</td><td>LOTE</td><td>FECHA VENCIMIENTO</td><td>CANT. INGRESAR</td><td>OP</td>";
      $salida .= "                  </tr>";
      
      $i=0;
      foreach($ProductosDocumento as $key=>$producto)
      {
      $fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.SessionGetVar("EMPRESA"));
      
			/*
			* Para Sacar los numeros de días entre fechas
			*/      
          $fecha =$producto['fecha_vencimiento'];  //esta es la que viene de la DB
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
      
      $codigo_unico= $producto['bodegas_doc_id']."@".$producto['numeracion']."@".$producto['consecutivo'];
      $ItemTemporal=$consulta->ConsultaItemTemporal_($doc_tmp_id,$codigo_unico);
	  if(!empty($ItemTemporal))
	  {
	  $disabled=" checked=\"true\" disabled=\"true\" ";
	  }
	  else
		{
		$disabled ="";
		}
	  $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
	  //$salida .= "                 <tr class=\"modulo_list_claro\">";
      $salida .= "                    <td><input ReadOnly=\"true\" class=\"input-text\" type=\"text\" name=\"codigo_producto".$i."\" id=\"codigo_producto".$i."\" value=\"".$producto['codigo_producto']."\"></td>";
      $salida .= "                    <td>".$producto['descripcion']."</td>";
      $salida .= "                    <td>".FormatoValor($producto['cantidad'])."</td>";
      $salida .= "                    <td><input ReadOnly=\"true\" class=\"input-text\" type=\"text\" name=\"lote".$i."\" id=\"lote".$i."\" value=\"".$producto['lote']."\"></td>";
      //$salida .= "                    <td>".$producto['lote']."</td>";
      $salida .= "                    <td><input ReadOnly=\"true\" class=\"input-text\" type=\"text\" name=\"fecha_vencimiento".$i."\" ".$color." id=\"fecha_vencimiento".$i."\" value=\"".$producto['fecha_vencimiento']."\"></td>";
      $salida .= "                    <td><input title=\"Cantidad a Ingresar de ".$producto['descripcion']."\" onkeypress=\"return acceptNum(event);\" style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"cantidad".$i."\" id=\"cantidad".$i."\" onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('cantidad".$i."',xGetElementById('cantidad".$i."').value,'".$producto['cantidad']."','hell$i','error".$i."');\"></td>";
      $salida .= "                    <td>";
      //$Inventario=$sql->ConsultaInventarioProducto(SessionGetVar("EMPRESA"),$producto['codigo_producto']);
      // print_r($Inventario);
      $salida .= "                    <input type=\"hidden\" name=\"cantidad_enviada".$i."\" id=\"cantidad_enviada".$i."\" value=\"".FormatoValor($producto['cantidad'])."\">";
      $salida .= "                    <input type=\"hidden\" name=\"valor".$i."\" id=\"valor".$i."\" value=\"".($producto['total_costo']/$producto['cantidad'])."\">";
      $salida .= "                    <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
      $salida .= "                    <input $disabled type=\"checkbox\" id=\"".$i."\" name=\"".$i."\" value=\"".$codigo_unico."\" class=\"checkbox\">";
      $salida .= "                    </td>";
      $salida .= "                 </tr>";
      $salida .= "                <tr>";
      $salida .= "                <td colspan=\"10\" align=\"center\"><div class=\"label_error\" id=\"error".$i."\"></div><div class=\"label_error\" id=\"hell".$i."\"></div></td>";
      $salida .= "                </tr>";
            
      $i++;
      }
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  COLSPAN='10' align=\"center\" class=\"modulo_list_claro\">\n";                          //                         $doc_tmp_id,                                            $codigo_producto,                     $cantidad,                                       $porcentaje_gravamen[document.getElementById('gravamen').value],$total_costo[document.getElementById('op22').value],                   $usuario_id=null
  $salida .= "                          <input type=\"hidden\" name=\"doc_tmp_id\" id=\"doc_tmp_id\" value=\"".$doc_tmp_id."\">";
  $salida .= "                          <input type=\"hidden\" name=\"bodegas_doc_id\" id=\"bodegas_doc_id\" value=\"".$bodegas_doc_id."\">";
  $salida .= "                          <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
  $salida .= "                          <input type=\"button\" id=\"nuevo\" value=\"SELECCIONAR PRODUCTO\" class=\"input-bottom\" onClick=\"xajax_IngresoProductosTemporal('".$bodegas_doc_id."','".$doc_tmp_id."',xajax.getFormValues('Productos_Formula'),'".UserGetUID()."');\">";
  $salida .= "                       </td>\n"; 
  $salida .= "                    </tr>\n";
  $salida .= "                 </table>";
  $salida .= "                </form>\n";
    $objResponse->assign("ListadoProductos","innerHTML",$salida);
  //  $objResponse->script("xajax_MostrarProductox('".$bodegas_doc_id."','".$doc_tmp_id."','".UserGetUID()."');");
    
    return $objResponse;

}

/******************************************************************************
* para guardar un documento temporal
********************************************************************************/
//GuardarTmpDoc(bodegas_doc_id, observacion, tipo_id_tercero, tercero_id, prestamo);
function GuardarTmpDoc($bodegas_doc_id, $observacion, $formula_id, $formula_papel)
{
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta1=new doc_bodegas_I013();
    
    $valor=$consulta1->CrearDoc($bodegas_doc_id, $observacion, $formula_id, $formula_papel);

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
function BuscarFormula($formula_papel,$empresa_id)
{
      $objResponse = new xajaxResponse(); 
      $path = SessionGetVar("rutaImagenes");
      $sql=new doc_bodegas_I013();
	  $formula=$sql->Formula_Buscada($formula_papel,$empresa_id);
       // print_r($formula);
     if(empty($formula))
	    {
		$mensaje=" La Formula: ".$formula_papel."- No Ha Sido Dispensada o No Existe!!";
		$objResponse->script("document.getElementById('formula_id').value=\"\";");
		$objResponse->script("document.getElementById('formula_papel').value=\"\";");
		}
		else
			{
			$mensaje=" Formula: ".$formula_papel." - ".$formula['tipo_id_paciente']." ".$formula['paciente_id']." ".$formula['paciente']." ";
			$objResponse->script("document.getElementById('formula_id').value=".$formula['formula_id'].";");
			$objResponse->script("document.getElementById('formula_papel').value='".$formula['formula_papel']."';");
			}
      $salida = $objResponse->setTildes($mensaje);
	  
      $objResponse->assign("descripcion_formula","innerHTML",$salida);
      return $objResponse;
    }
    
    
/********************************************************************
* para buscar a los teceros LISTA DE terceroS
*********************************************************************************/
function BuscadorFarmacia_d($empresa_id,$CentroUtilidad,$Bodega,$CodigoEmpresa,$RazonSocial,$offset)
{
      $objResponse = new xajaxResponse(); 
      $path = SessionGetVar("rutaImagenes");
      $sql = AutoCarga::factory("MovDocI013","classes","app","Inv_MovimientosBodegas");
      $Farmacias = $sql ->Listar_Farmacias(SessionGetVar("EMPRESA"),$CentroUtilidad,$Bodega,$CodigoEmpresa,$RazonSocial,$offset);
      $action['paginador'] = "Paginador('".$empresa_id."','".$CentroUtilidad."','".$Bodega."','".$CodigoEmpresa."','".$RazonSocial."'";
      $pghtml = AutoCarga::factory("ClaseHTML");
    
      if(!empty($Farmacias))
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
        foreach($Farmacias as $key=>$valor)
        {
        $salida .= "<tr class=\"modulo_list_claro\" >";
        $salida .= "<td>".$valor['empresa_id']."</td>";
        $salida .= "<td>".$valor['razon_social']."</td>";
        $salida .= "      <td align=\"center\">\n";
        //formulario,tipo_id_tercero,tercero_id,nombre_tercero
        $salida .= "        <a href=\"#\" onclick=\"Seleccionado('unocreate','".$valor['empresa_id']."','".$valor['razon_social']."','".SessionGetVar("EMPRESA")."','".$CentroUtilidad."','".$Bodega."')\">\n";
        $salida .= "          <img title=\"SELECCIONAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
        $salida .= "        </a>\n";
        $salida .= "      </td>\n";
        $salida .= "      <tr>";
        }
      $salida .= "<table>";
      }
      else
          {
          $salida .= "                   <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
          $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
          $salida .= "                      <td  align=\"center\" class=\"label_error\">\n";
          $salida .= "                       NO HAY RESULTADOS";          
          $salida .= "                      </td>\n";
          $salida .= "                    </tr>\n";
          $salida .= "<table>";
          }
   
      $salida = $objResponse->setTildes($salida);
      $objResponse->assign("ListaDeProveedores","innerHTML",$salida);
      return $objResponse;
    }
    
/********************************************************************
* para buscar a los teceros LISTA DE terceroS
*********************************************************************************/
function Listar_DocumentosFarmacia($farmacia_id,$empresa_id,$CentroUtilidad,$Bodega)
{
      $objResponse = new xajaxResponse(); 
      $path = SessionGetVar("rutaImagenes");
      $sql = AutoCarga::factory("MovDocI013","classes","app","Inv_MovimientosBodegas");
      $cons=new doc_bodegas_I013();
      $documentos=$sql->Listar_DocumentosFarmacia($farmacia_id,$empresa_id,$CentroUtilidad,$Bodega);
      $select .= "<option value=\"-1\">--- ---</option>";
        foreach($documentos as $key => $valor)
        {
        $Productos=$cons->ProductosDocumento($farmacia_id,$valor['prefijo'],$valor['numero'],'','');
        if(!empty($Productos))
        $select .=" <option value=\"".$valor['prefijo']."@".$valor['numero']."\">".$valor['prefijo']."-".$valor['numero']."</option>";
        }
		  $salida = $objResponse->setTildes($select);
      $objResponse->assign("DocumentosFarmacia","innerHTML",$salida);
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