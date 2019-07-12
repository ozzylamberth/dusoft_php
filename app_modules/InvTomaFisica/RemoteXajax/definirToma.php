<?php

/* * ************************************************************************************
 * $Id: definirToma.php,v 1.17 2010/02/01 21:17:33 johanna Exp $ 
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 * 
 * @author Jaime gomez
 * ************************************************************************************ */

//$VISTA = "HTML";
//$_ROOT = "../../../";
//include "../../../app_modules/InvTomaFisica/classes/TomaFisicaSQL.class.php";
//include "../../../app_modules/InvTomaFisica/RemoteXajax/definirToma.js";
//include "../../../classes/ClaseHTML/ClaseHTML.class.php";
IncludeClass("ClaseHTML");
IncludeClass("TomaFisicaSQL", "classes", "app", "InvTomaFisica");
/* * *************************************************************
 * FUNCION PARA CREAR TOMAS FISICAS
 * ************************************************************* */

function CrearTomaxFisicas($vector) {
    //var_dump($vector);
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $objResponse = new xajaxResponse();

    foreach ($vector['OBSERVACION_TOMA'] as $key => $valor1) {
        if ($valor1 == "") {
            $salida .= "                  <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr>\n";
            $salida .= "                      <td align=\"center\">\n";
            $salida .= "                         <label ALIGN='center' class='label_error'>LA DESCRIPCION DE LA BODEGA " . $key . " SE ENCUENTRA VACIA</label>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    </table>\n";
            $objResponse->assign("error_en_toma", "innerHTML", $salida);
            return $objResponse;
        }
//           $objResponse->alert($valor1);   
    }
    //print_r($vector['con_existencia']);
    foreach ($vector['con_existencia'] as $key => $valor1) {
        list( $empresa_id, $centro_utilidad, $bodega ) = split('_', $key);
        $numero_conteos = $vector['conteos'];
        $descripcion = $vector['OBSERVACION_TOMA'][$bodega];
        if ($valor1 == 1) {
            $solo_con_existencia = true;
        } elseif ($valor1 == 0) {
            $solo_con_existencia = false;
        }

        $orderby = $vector['orderby'][0];
        $resultado = $consulta->CrearTomaFisica($empresa_id, $centro_utilidad, $bodega, $numero_conteos, $descripcion, $orderby, $solo_con_existencia);

        if ($resultado === false) {
            $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr>\n";
            $salida .= "                      <td align=\"center\">\n";
            $salida .= "                         <label ALIGN='center' class='label_error1'>ERROR AL CREAR TOMA FISICA" . $consulta->mensajeDeError . "</label>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    </table>\n";
            $objResponse->assign("error_en_toma", "innerHTML", $salida);
            return $objResponse;
        }
    }

    if ($resultado === true) {
        $salida .= "                  <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .= "                         <label ALIGN='center' class='label_error'>TOMAS FISICAS CREADAS CON EXITO</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
        $objResponse->assign("error_en_toma", "innerHTML", $salida);
        //$objResponse->Call("VolverMenuAdmin");
        return $objResponse;
    }
}

/* * *************************************************************
 * PROCESAR LOTE

 * ************************************************************* */

function ProcesarLote($empresa, $bodega_id,$bodega_nombre) {
    $consulta = new TomaFisicaSQL();
    $objResponse = new xajaxResponse();
    $diferenciLotes = $consulta->GetDiferenciasLotes($empresa, $bodega_id);
    
   //echo "<pre>";print_r($diferenciLotes);
    $html  = "<table width=\"100%\" class=\"modulo_table_list\" align=\"center\" >";
    $html .= "<tr align=\"center\" class=\"modulo_table_list_title\" >";
    $html .= "<td colspan='7'>";
    $html .= "<div>$bodega_nombre</div>";
    $html .= "</td>";
    $html .= "</tr>";
    $html .= "<tr align=\"center\" class=\"modulo_table_list_title\" >";
    $html .= "<td>PRODUCTO</td>";
    $html .= "<td>EXISTENCIA</td>";
    $html .= "<td>OPERACION</td>";
    $html .= "<td>LOTE</td>";
    $html .= "<td>EXISTENCIA LOTE</td>";
    $html .= "<td>TOTAL LOTES</td>";
    $html .= "<td>CANTIDAD</td>";
    $html .= "</tr>";
    if (sizeof($diferenciLotes) > 0) {
        $n_registros_cambio=0;
      foreach ($diferenciLotes as $keys => $values) {
          $diferencia_a=0;
          if(round($values['titular']['exis'])==$values['lote']['exis']){
              continue;
          }elseif($values['titular']['exis']>$values['lote']['exis']){
              $lotes = $consulta->GetLotedeMayorExistencia($empresa, $bodega_id, $keys, 3);
              $diferencia=$values['titular']['exis']-$values['lote']['exis'];
              //aqui se actualiza el lote sumando lo que hay mas el valor de la diferencia
              $cantidad = ($lotes['existencia_actual']=='0'?0:$lotes['existencia_actual']) + $diferencia;
              $n_registros_cambio+=$consulta->ActualizarLote($empresa,$bodega_id,$keys,$lotes['lote'],$cantidad,$lotes['fecha_vencimiento']);
           $html .=vista_lote($keys,$values['titular']['exis']==0?'0':$values['titular']['exis'],"SUMA",$lotes['lote'],$lotes['existencia_actual']==0?'0':$lotes['existencia_actual'],$values['lote']['exis']==0?'0':$values['lote']['exis'],$diferencia);
           
          }elseif($values['titular']['exis']<$values['lote']['exis']){
               // $lotes = $consulta->GetLotedeMayorExistencia($empresa, $bodega_id, $keys, 0);
                $diferencia=$values['lote']['exis']-$values['titular']['exis'];
                $diferencia_fija=$diferencia;
                $lotes = $consulta->GetLotedeMayorExistencia($empresa, $bodega_id, $keys, 1);
                //echo "<pre>"; print_r($lotes);
                 foreach ($lotes as $value) {
                     if($diferencia>$value['existencia_actual']){
                         
                         $diferencia=$diferencia-$value['existencia_actual'];
                         $diferencia_a+=$diferencia;
                         $cantidad = ($value['existencia_actual']==0?0:$value['existencia_actual']) - $value['existencia_actual'];
                         $n_registros_cambio+=$consulta->ActualizarLote($empresa,$bodega_id,$keys,$value['lote'],$cantidad,$value['fecha_vencimiento']);
                         $html .=vista_lote($keys,$values['titular']['exis']==0?'0':$values['titular']['exis'],"RESTA",$value['lote'],$value['existencia_actual']==0?'0':$value['existencia_actual'],$values['lote']['exis']==0?'0':$values['lote']['exis'],$value['existencia_actual']);
                         if($diferencia_fija<=$value['existencia_actual']){
                         $diferencia_a=0;
                         break;
                         }
                         //aqui se le coloca cero al lote ya que se resto el valor de la existencia 
                     }else{
                         $dif=$diferencia;
                         $diferencia=$value['existencia_actual']-$diferencia;
                         $diferencia_a+=$diferencia;
                          $cantidad = ($value['existencia_actual']==0?0:$value['existencia_actual'])-$dif;
                          $n_registros_cambio+=$consulta->ActualizarLote($empresa,$bodega_id,$keys,$value['lote'],$cantidad,$value['fecha_vencimiento']);
                         $html .=vista_lote($keys,$values['titular']['exis']==0?'0':$values['titular']['exis'],"RESTA",$value['lote'],$value['existencia_actual']==0?'0':$value['existencia_actual'],$values['lote']['exis']==0?'0':$values['lote']['exis'],$dif);
                       // echo $keys." diferencia_fija ".$diferencia_fija." dif ".$dif."<br>";
                         if($diferencia_fija<=$diferencia_a || $diferencia_fija<=$dif){
                         $diferencia_a=0;
                         break;
                         }
                         //aqui se actualiza el lote con el valor de la diferencia
                     }
                     
                 }
          }else{
              if($values['titular']['exis']>0){
                  $lotes = $consulta->GetLotedeMayorExistencia($empresa, $bodega_id, $keys, 0);
                  $diferencia=$values['titular']['exis'];
                  $cantidad =$lotes['lote']+$diferencia;
                  $n_registros_cambio+=$consulta->ActualizarLote($empresa,$bodega_id,$keys,$lotes['lote'],$cantidad,$lotes['fecha_vencimiento']);
                  $html .=vista_lote($keys,$values['titular']['exis']==0?'0':$values['titular']['exis'],"SUMA",$lotes['lote'],"0",$diferencia);
              //aqui se actualiza el lote sumando lo que hay mas el valor de la diferencia
              }else{
                  //$lotes = $consulta->GetLotedeMayorExistencia($empresa, $bodega_id, $keys, 0);
                   $diferencia=$values['lote']['exis'];
                   $diferencia_fija=$diferencia;
                   $lotes = $consulta->GetLotedeMayorExistencia($empresa, $bodega_id, $keys, 1);
                  // echo "<pre>"; print_r($lotes);
                 foreach ($lotes as $value) {
                     if($value['existencia_actual']<$diferencia){
                         $diferencia=$diferencia-$value['existencia_actual'];
                         $diferencia_a+=$diferencia;
                         $cantidad = $value['lote']-$diferencia;
                         $n_registros_cambio+=$consulta->ActualizarLote($empresa,$bodega_id,$keys,$value['lote'],$cantidad,$value['fecha_vencimiento']);
                         $html .=vista_lote($keys,"0","RESTA",$value['lote'],$values['lote']['exis']==0?'0':$values['lote']['exis'],$diferencia);
                         if($diferencia_fija==$diferencia){
                         $diferencia_a=0;
                         break;
                         }
                         //aqui se le coloca cero al lote ya que se resto el valor de la existencia 
                     }else{
                         $diferencia=$value['existencia_actual']-$diferencia;
                         $diferencia_a+=$diferencia;
                         $cantidad = $value['lote']+$diferencia;
                         $n_registros_cambio+=$consulta->ActualizarLote($empresa,$bodega_id,$keys,$value['lote'],$cantidad,$value['fecha_vencimiento']);
                         $html .=vista_lote($keys,$values['titular']['exis']==0?'0':$values['titular']['exis'],"SUMA",$value['lote'],$values['lote']['exis']==0?'0':$values['lote']['exis'],$diferencia);
                         if($diferencia_fija==$diferencia){
                             $diferencia_a=0;
                             break;
                         }
                         //aqui se actualiza el lote con el valor de la diferencia
                     }
                     
                 }
              }
          }          
      }
      $n_registros=sizeof($diferenciLotes);
      
//      $html  .= "<tr class=\"modulo_table_list_title\">";
//      if($n_registros_cambio==0){
//         $html .= "<td colspan='7' align=\"center\"><b>SE ACTULIZARON ".$n_registros." PRODUCTOS</b></td>";
//      }else{
//         $html .= "<td colspan='7' align=\"center\"><b>ERROR AL ACTUALIZAR - ACTUALIZADOS ".($n_registros-$n_registros_cambio)." DE ".$n_registros." PRODUCTOS</b></td>"; 
//      }
//      $html .= "</tr>";
      $html .="</table>";
      $objResponse->assign("tabla", "innerHTML", "<br><br><div style='font-size:12px;color:#7F1A25;'>" . $html . "</div>");
      $objResponse->assign("mensaje", "innerHTML", "");
      $n_registros_cambio=0;
        
    }else{
        $objResponse->assign("mensaje", "innerHTML", "<div style='font-size:12px;color:#7F1A25;'><b>NO SE ENCONTRARON DIFERENCIAS</b></div>");
        $objResponse->assign("tabla", "innerHTML", "");
    }
     $html=vista_lotes_empresa($consulta,$empresa);
     $objResponse->assign("tabla_empresas", "innerHTML", "<br><br><div style='font-size:12px;color:#7F1A25;'>" . $html . "</div>");
      return $objResponse;
}

function ProcesarInventario($empresa,$centro_utilidad ,$bodega_id,$bodega_nombre,$cabecera_id,$nombrecabe) {
   
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $usuario=UserGetUID();
    //productos que faltan en existencias_bodegas
    $productosFaltantes = $consulta->ListarProductosFaltantesEnExistencias($empresa,$centro_utilidad,$bodega_id,$cabecera_id);
    if(!sizeof($productosFaltantes)>0){
    $vector = $consulta->ListarDocumentosIngreso($empresa,$centro_utilidad, $bodega_id,$usuario);
    $vector_egreso = $consulta->ListarDocumentosEgreso($empresa,$centro_utilidad, $bodega_id,$usuario);
    ////////////////////////////////////////////////////////////
    $salidaH .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $salidaH .= "                    <tr  class=\"modulo_table_list_title\">\n";
    $salidaH .= "                      <td align=\"center\" width=\"15%\">\n";
    $salidaH .= "                       <a title='BODEGA DOCUMENTO ID'>";
    $salidaH .= "                        BOD DOC ID";
    $salidaH .= "                       </a>";
    $salidaH .= "                      </td>\n";
    $salidaH .= "                      <td align=\"center\"width=\"15%\">\n";
    $salidaH .= "                       <a title='PREFIJO'>";
    $salidaH .= "                        PREFIJO";
    $salidaH .= "                       </a>";
    $salidaH .= "                      </td>\n";
    $salidaH .= "                      <td colspan='2' align=\"center\" width=\"60%\">\n";
    $salidaH .= "                        <a title='DESCRIPCION DEL DOCUMENTO'>DESCRIPCION</a>";
    $salidaH .= "                      </td>\n";
    $salidaH .= "                     </tr>\n";
    ///////////////////////////////////////////////egreso/////////////////////////////////////////////
    for ($i = 0; $i < count($vector_egreso); $i++) {
//    //    if($vector_egreso[$i]['prefijo']=='ASF'){
        $tr = "nocuadre3" . $i;
        $salida .= "                    <tr id='" . $tr . "' class=\"modulo_list_claro\" >\n";
        $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
        $salida .= "                        <a title='BODEGA DOCUMENTO ID'>";
        $salida .= "                     " . $vector_egreso[$i]['bodegas_doc_id'] . "";
        $salida .= "                        </a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"left\">\n";
        $salida .= "                        <a title='PREFIJO DEL DOCUMENTO'>";
        $salida .= "                        " . $vector_egreso[$i]['prefijo'];
        $salida .= "                        </a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
        $salida .= "                        <a title=\"" . $vector_egreso[$i]['descripcion'] . "\">";
        $salida .= "                        " . substr($vector_egreso[$i]['descripcion'], 0, 40);
        $salida .= "                        </a>";
        $salida .= "                      </td>\n";       
        $salida .= "                      <td align=\"right\" class=\"modulo_list_claro\">\n";
        $j = "javascript:CrearDocumento_Egreso('".$empresa."','".$centro_utilidad."','".$bodega_id."','" . $cabecera_id . "','" . $vector_egreso[$i]['bodegas_doc_id'] . "');";
        $salida .= "<a title='SELECCIONAR " . $vector[$i]['descripcion'] . "' class=\"label_error\" href=\"" . $j . "\">\n";
        $salida .= "                          <sub> 2 <img src=\"" . $path . "/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $salida .= "                         </a>\n";
        $salida .= "                      </td>\n";
        $salida .= "                      </tr>\n";
        $bander=true;
   //     }
    }
/////////////////////////////////////////////////ingreso////////////////////////////////////////////////
    for ($i = 0; $i < count($vector); $i++) {
    //    if($vector[$i]['prefijo']=='IAE'){
        $bander=true;
        $tr = "nocuadre3" . $i;
        $salidaI .= "                    <tr id='" . $tr . "' class=\"modulo_list_claro\"  >\n";
        $salidaI .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
        $salidaI .= "                        <a title='BODEGA DOCUMENTO ID'>";
        $salidaI .= "                     " . $vector[$i]['bodegas_doc_id'] . "";
        $salidaI .= "                        </a>";
        $salidaI .= "                      </td>\n";
        $salidaI .= "                      <td align=\"left\">\n";
        $salidaI .= "                        <a title='PREFIJO DEL DOCUMENTO'>";
        $salidaI .= "                        " . $vector[$i]['prefijo'];
        $salidaI .= "                        </a>";
        $salidaI .= "                      </td>\n";
        $salidaI .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
        $salidaI .= "                        <a title=\"" . $vector[$i]['descripcion'] . "\">";
        $salidaI .= "                        " . substr($vector[$i]['descripcion'], 0, 40);
        $salidaI .= "                        </a>";
        $salidaI .= "                      </td>\n";
        
        $salidaI .= "                      <td align=\"right\" class=\"modulo_list_claro\">\n";//$empresa,$bodega,$cabecera, $bodegas_doc_id
        $j = "javascript:CrearDocumento_Ingreso('".$empresa."','".$centro_utilidad."','".$bodega_id."','" . $cabecera_id . "','" . $vector[$i]['bodegas_doc_id'] . "');";
        $salidaI .= "<a title='SELECCIONAR " . $vector[$i]['descripcion'] . "' class=\"label_error\" href=\"" . $j . "\">\n";
        $salidaI .= "                          <sub> 1 <img src=\"" . $path . "/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $salidaI .= "                         </a>\n";
        $salidaI .= "                      </td>\n";
        $salidaI .= "                      </tr>\n";
        }
        if($bander==true){
          $salidaF =$salidaH;  
        } 
        if(sizeof($vector_egreso)>0){
        $salidaF .=$salida;  
       }else{
         $salidaF2.="<br><div bgcolor='#800000'><b>NO TIENE PERMISOS PARA CREAR DOCUMENTO DE EGRESO</b></div>";  
       }
       if(sizeof($vector)>0){
       $salidaF .=$salidaI;         
       }else{
         $salidaF2.="<br><b><div bgcolor='#800000'>NO TIENE PERMISOS PARA CREAR DOCUMENTO DE INGRESO</div></b>";    
       }
        if($bander==true){
          $salidaF .= "</table>\n"; 
        }
    }else{
        $salidaF2.="<table width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
        $salidaF2.="<tr class=\"modulo_table_list_title\">";
        $salidaF2.="<td>Los siguientes productos no se encuentran en </br> las existencias de la bodega ".$bodega_nombre."</br> (Debe insertarlos antes de continuar)</td>";
        $salidaF2.="</tr>";
        $salidaF2.="<tr class=\"modulo_table_list_title\">";
        $salidaF2.="<td >Codigo Producto</td>";
        $salidaF2.="</tr>";
        foreach ($productosFaltantes as $key => $value) { 
        $salidaF2.="<tr  class=\"modulo_list_claro\">";
        $salidaF2.="<td >".$value['codigo_producto']."</td>";
        $salidaF2.="</tr>";
        }       
        $salidaF2.="</table>";
    }  
       
       $salidaF .=$salidaF2;
       $objResponse->assign("tabla_empresas", "innerHTML", "<br><br><div style='font-size:12px;color:#7F1A25;'>" . $salidaF . "</div>");
    return $objResponse;
}

function vista_lotes_empresa($consulta,$empresa){
    $datos=$consulta->total_productos_lotes($empresa);   
    if(sizeof($datos)>0){
    $html  = "<table width=\"100%\" class=\"modulo_table_list\" align=\"center\" >";
    $html .= "<tr align=\"center\" class=\"modulo_table_list_title\" >";
    $html .= "<td>BODEGA</td>";
    $html .= "<td>PRODUCTO</td>";
    $html .= "<td>TIPO</td>";
    $html .= "<td>EXISTENCIA</td>";
    $html .= "</tr>";
    foreach ($datos as $key => $value) {
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td>".$value['descripcion']."</td>";
    $html .= "<td align=\"center\">".$value['codigo_producto']."</td>";
    if($value['lote']=='titular'){
        $tipo="KARDEX";       
    }else{
        $tipo="LOTE";
    }
    $html .= "<td align=\"center\">".$tipo."</td>";
    $html .= "<td align=\"right\">".$value['exis']."</td>";
    $html .= "</tr>";
    }
    $html .= "</table>";
    }
    return $html; 
}
function vista_lote($producto,$existencia,$operacion,$lote,$lote_existencia,$total_lote,$diferencia){
    $html  = "<tr class=\"modulo_list_claro\">";
    $html .= "<td>".$producto."</td>";
    $html .= "<td align=\"right\">".$existencia."</td>";
    $html .= "<td>".$operacion."</td>";
    $html .= "<td align=\"right\">".$lote."</td>";
    $html .= "<td align=\"right\">".$lote_existencia."</td>";
    $html .= "<td align=\"right\">".$total_lote."</td>";
    $html .= "<td align=\"right\">$diferencia</td>";
    $html .= "</tr>";
    return $html; 
}

/* * *************************************************************
 * ELIMINAR AJUSTES DE PRODUCTOS

 * ************************************************************* */

function ActivarTomaFisica($tr, $toma_fisica_id) {
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $objResponse = new xajaxResponse();
    $fecha_activacion = $consulta->ActivarTomaFisica($toma_fisica_id);
    if ($fecha_activacion != false) {
        //$objResponse->alert($buscar);
        $salida .= "                         <a title='FECHA DE ACTIVACION: " . substr($fecha_activacion, 0, 19) . "'>";
        $salida .= "                          <sub><img src=\"" . $path . "/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $salida .= "                         </a>\n";
        $objResponse->assign($tr, "innerHTML", $salida);
        $sala = "FECHA DE ACTIVACION " . substr($fecha_activacion, 0, 19);
        $objResponse->alert($sala);
    } else {

        $salida .= "                  <table width=\"80%\" align=\"center\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .= "                         <label ALIGN='center' class='label_error'>" . $consulta->mensajeDeError . "</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
        $objResponse->assign("error_en_toma", "innerHTML", $salida);
    }
    return $objResponse;
}

/* * *****************************************************************
 * PARA AJUSTAR PRODUCTOS IGUALES A CERO
 * ***************************************************************** */

function Ajustar_Cero($toma_fisica, $empresa, $cu, $bodega, $usuario, $numero_conteos) {
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $objResponse = new xajaxResponse();
    $Ajuste = $consulta->AjustaraCeroBD($toma_fisica);
    //$objResponse->alert("AQUI");
    /* $vector=$consulta->ContarParaCierre($toma_fisica); */

    if ($Ajuste) {
        $objResponse->script("MostrarCierre('" . trim($toma_fisica) . "','" . trim($empresa) . "','" . trim($cu) . "','" . trim($bodega) . "','" . trim($usuario) . "','" . trim($numero_conteos) . "');");
    } else {
        $objResponse->alert($consulta->mensajeDeError);
    }
    return $objResponse;
}

/* * ************************************************
 *
 * *************************************************** */

function AdicionarUserConteoBD($toma, $usuario_id) {
    $path = SessionGetVar("rutaImagenes");
    $objResponse = new xajaxResponse();
    $consulta = new TomaFisicaSQL();

    $resultado = $consulta->AddUsuarioConteo($toma, $usuario_id);
    if ($resultado === true) {
        $html = "AL USUARIO " . $usuario_id . " LE FUE ASIGNADO EL PERMISO DE CONTEO SATISFACTORIAMENTE";
    } else {
        $html = "ERROR" . $consulta->mensajeDeError;
    }
    $objResponse->assign("error_usuarios2", "innerHTML", $html);
    $objResponse->call("Actualizar_Lista_Usuarios");
    return $objResponse;
}

function AdicionarUserValidacionBD($toma, $usuario_id) {
    $path = SessionGetVar("rutaImagenes");
    $objResponse = new xajaxResponse();
    $consulta = new TomaFisicaSQL();

    $resultado = $consulta->AddUsuarioValidacion($toma, $usuario_id);
    if ($resultado === true) {
        $html = "AL USUARIO " . $usuario_id . " LE FUE ASIGNADO EL PERMISO DE VALIDACION SATISFACTORIAMENTE";
    } else {
        $html = "ERROR" . $consulta->mensajeDeError;
    }
    $objResponse->assign("error_usuarios2", "innerHTML", $html);
    $objResponse->call("Actualizar_Lista_Usuarios");
    return $objResponse;
}

/**
 * funcion que sirve para buscar los usuarios 
 * @param array $filtros vector con os datos a consultar
 * @param string $pagina con el numero de la pagina  buscar
 * @param string $usu_cant contador de usuarios cuando se utiliza por primera vez sera cero
 * @return string $html con la forma dela consulta
 * */
function BuscarUsuSysConteo($filtros, $pagina, $usu_cant, $toma) {
    $path = SessionGetVar("rutaImagenes");
    $objResponse = new xajaxResponse();
    $consulta = new TomaFisicaSQL();
    // var_dump($filtros);
    if ($usu_cant == 0) {
        $usu_cant = $consulta->GetSystemUsersForConteo($filtros, $count = true, $limit = false, $offset = false, $toma);
    }

    $limit = 10;
    $offset = ($pagina - 1) * $limit;
    $usuarios = $consulta->GetSystemUsersForConteo($filtros, $count = null, $limit, $offset, $toma);
    //$afiliados = $afi->GetAfiliados($datos, $count=false, $limit, $offset);
    //$objResponse->assign("JJJJJ");
    //var_dump($usu_cant);
    //var_dump($usuarios);
    if (!empty($usuarios)) {
        $html .= "                 <table width=\"60%\" align=\"center\">\n";
        $html .= "                    <tr class=\"normal_10AN\">\n";
        $html .= "                       <td width=\"100%\" align=\"left\">\n";
        $html .= "                       SE ENCONTRARON (" . $usu_cant . ") REGISTRO(S)";
        $html .= "                      </td>\n";
        $html .= "                   </tr>\n";
        $html .= "                 </tABLE>\n";
        $html .= "          <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "              <tr class=\"modulo_table_list_title\">\n";
        $html .= "                <td width='16%'  align=\"center\">\n";
        $html .= "                  USUARIO ID";
        $html .= "                </td>\n";
        $html .= "                <td width='16%'  align=\"center\">\n";
        $html .= "                  LOGIN";
        $html .= "                </td>\n";
        $html .= "                <td width='48%'  align=\"center\">\n";
        $html .= "                  NOMBRE";
        $html .= "                </td>\n";
        $html .= "                <td width='20%'  align=\"center\">\n";
        $html .= "                  ACCIONES";
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        for ($i = 0; $i < count($usuarios); $i++) {
            $sitio = "switch_admin" . $i;
            $sitio_perfil = "perfil_sitio" . $i;
            $sitio_accion = "accion" . $i;
            $html .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
            $html .= "                       <td class=\"normal_10AN\" align=\"center\">\n";
            $html .= "                       " . $usuarios[$i]['usuario_id'];
            $html .= "                       </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                       " . $usuarios[$i]['usuario'];
            $html .= "                       </td>\n";
            $html .= "                      <td align=\"left\">\n";
            $html .= "                       <a title='" . $usuarios[$i]['nombre'] . "'>";
            $html .= "                       " . $usuarios[$i]['nombre'];
            $html .= "                       </a>\n";
            $html .= "                      </td>\n";
            $html .= "                      <td id='$sitio_accion' align=\"center\">\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
            $PERMISO = "javascript:AdicionarUserConteo('" . $toma . "','" . $usuarios[$i]['usuario_id'] . "');"; //
            $html .= "                         <a title='DAR PERMISO DE CONTEO PARA ESTE USUARIO' href=\"" . $PERMISO . "\">";
            $html .= "                          <sub><img src=\"" . $path . "/images/editar.gif\" border=\"0\" width=\"17\" height=\"17\"> SELECCIONAR</sub>\n";
            $html .= "                         </a>\n";
        }
        $html .= "               </tr>\n";
        $html .= "            </table>\n";
        $html .= "" . ObtenerPaginadorNOAFI($pagina, $path, $usu_cant, $op, $toma);
    } else {
        $html .= "                 <table width=\"100%\" align=\"center\">\n";
        $html .= "                    <tr class=\"label_error\">\n";
        $html .= "                       <td width=\"100%\" align=\"center\">\n";
        $html .= "                       NO SE ENCONTRARON RESULTADOS CON ESE CRITERIO DE BUSQUEDA";
        $html .= "                      </td>\n";
        $html .= "                   </tr>\n";
        $html .= "                 </table>\n";
    }
    $objResponse->assign("resultado_usuarios_sys", "innerHTML", $html);
    return $objResponse;
}

/**
 * funcion que sirve para buscar los usuarios que no pertenecen al sistema EPS
 * @param array $filtros vector con os datos a consultar
 * @param string $pagina con el numero de la pagina  buscar
 * @param string $usu_cant contador de usuarios cuando se utiliza por primera vez sera cero
 * @return string $html con la forma dela consulta
 * */
function BuscarUsuSysValidacion($filtros, $pagina, $usu_cant, $toma) {
    $path = SessionGetVar("rutaImagenes");
    $objResponse = new xajaxResponse();
    $consulta = new TomaFisicaSQL();
    // var_dump($filtros);
    if ($usu_cant == 0) {
        $usu_cant = $consulta->GetSystemUsersForValidacion($filtros, $count = true, $limit = false, $offset = false, $toma);
    }

    $limit = 10;
    $offset = ($pagina - 1) * $limit;
    $usuarios = $consulta->GetSystemUsersForValidacion($filtros, $count = null, $limit, $offset, $toma);
    //$afiliados = $afi->GetAfiliados($datos, $count=false, $limit, $offset);
    //$objResponse->assign("JJJJJ");
    //var_dump($usu_cant);
    //var_dump($usuarios);
    if (!empty($usuarios)) {
        $html .= "                 <table width=\"60%\" align=\"center\">\n";
        $html .= "                    <tr class=\"normal_10AN\">\n";
        $html .= "                       <td width=\"100%\" align=\"left\">\n";
        $html .= "                       SE ENCONTRARON (" . $usu_cant . ") REGISTRO(S)";
        $html .= "                      </td>\n";
        $html .= "                   </tr>\n";
        $html .= "                 </tABLE>\n";
        $html .= "          <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "              <tr class=\"modulo_table_list_title\">\n";
        $html .= "                <td width='16%'  align=\"center\">\n";
        $html .= "                  USUARIO ID";
        $html .= "                </td>\n";
        $html .= "                <td width='16%'  align=\"center\">\n";
        $html .= "                  LOGIN";
        $html .= "                </td>\n";
        $html .= "                <td width='48%'  align=\"center\">\n";
        $html .= "                  NOMBRE";
        $html .= "                </td>\n";
        $html .= "                <td width='20%'  align=\"center\">\n";
        $html .= "                  ACCIONES";
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        for ($i = 0; $i < count($usuarios); $i++) {
            $sitio = "switch_admin" . $i;
            $sitio_perfil = "perfil_sitio" . $i;
            $sitio_accion = "accion" . $i;
            $html .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
            $html .= "                       <td class=\"normal_10AN\" align=\"center\">\n";
            $html .= "                       " . $usuarios[$i]['usuario_id'];
            $html .= "                       </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                       " . $usuarios[$i]['usuario'];
            $html .= "                       </td>\n";
            $html .= "                      <td align=\"left\">\n";
            $html .= "                       <a title='" . $usuarios[$i]['nombre'] . "'>";
            $html .= "                       " . $usuarios[$i]['nombre'];
            $html .= "                       </a>\n";
            $html .= "                      </td>\n";
            $html .= "                      <td id='$sitio_accion' align=\"center\">\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
            $PERMISO = "javascript:AdicionarUserValidacion('" . $toma . "','" . $usuarios[$i]['usuario_id'] . "');"; //
            $html .= "                         <a title='DAR PERMISO DE CONTEO PARA ESTE USUARIO' href=\"" . $PERMISO . "\">";
            $html .= "                          <sub><img src=\"" . $path . "/images/editar.gif\" border=\"0\" width=\"17\" height=\"17\"> SELECCIONAR</sub>\n";
            $html .= "                         </a>\n";
        }
        $html .= "               </tr>\n";
        $html .= "            </table>\n";
        $html .= "" . ObtenerPaginadorValida($pagina, $path, $usu_cant, $op, $toma);
    } else {
        $html .= "                 <table width=\"100%\" align=\"center\">\n";
        $html .= "                    <tr class=\"label_error\">\n";
        $html .= "                       <td width=\"100%\" align=\"center\">\n";
        $html .= "                       NO SE ENCONTRARON RESULTADOS CON ESE CRITERIO DE BUSQUEDA";
        $html .= "                      </td>\n";
        $html .= "                   </tr>\n";
        $html .= "                 </table>\n";
    }
    $objResponse->assign("resultado_usuarios_sys", "innerHTML", $html);
    return $objResponse;
}

/**
 *
 * */

/**
 * Funcion que se encarga de mostrar una tabla paginadora para la lista de usuarios afiliados
 * @param string $pagina
 * @param string $path
 * @param string $slc
 * @param string $op
 * @return string $Tabla con la tabla que indica el listado del paginador
 * */
function ObtenerPaginadorValida($pagina, $path, $slc, $op, $toma) {


    //echo "io";
    $TotalRegistros = $slc;
    $TablaPaginado = "";

    if ($limite == null) {
        $LimitRow = 10;
    } else {
        $LimitRow = 10;
    }
    if ($TotalRegistros > 0) {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros / $LimitRow);

        if ($TotalRegistros % $LimitRow > 0) {
            $NumeroPaginas++;
        }

        $Inicio = $pagina;
        if ($NumeroPaginas - $pagina < 9) {
            $Inicio = $NumeroPaginas - 9;
        } elseif ($pagina > 1) {
            $Inicio = $pagina - 1;
        }

        if ($Inicio <= 0) {
            $Inicio = 1;
        }

        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

        $TablaPaginado .= "<tr>\n";
        if ($NumeroPaginas > 1) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P&#225;ginas:</td>\n";
            if ($pagina > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                //  xajax_ObtenerDocumentosBodegaFinal(empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento);
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarUsuSysValidation('1','" . $slc . "','" . $toma . "');\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarUsuSysValidation('" . ($pagina - 1) . "','" . $slc . "','" . $toma . "');\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $Fin = $NumeroPaginas + 1;
            if ($NumeroPaginas > 10) {
                $Fin = 10 + $Inicio;
            }

            for ($i = $Inicio; $i < $Fin; $i++) {
                if ($i == $pagina) {
                    $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                } else {
                    $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BuscarUsuSysValidation('" . $i . "','" . $slc . "','" . $toma . "');\">" . $i . "</a></td>\n";
                }
                $columnas++;
            }
        }
        if ($pagina < $NumeroPaginas) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarUsuSysValidation('" . ($pagina + 1) . "','" . $slc . "','" . $toma . "');\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BuscarUsuSysValidation('" . ($NumeroPaginas) . "','" . $slc . "','" . $toma . "');\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
        $aviso .= "     P&#225;gina&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
        $aviso .= "   </tr>\n";

        if ($op == 2) {
            $TablaPaginado .= $aviso;
        } else {
            $TablaPaginado = $aviso . $TablaPaginado;
        }
    }

    $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
    $Tabla .= $TablaPaginado;
    $Tabla .= "</table>";

    return $Tabla;
}

/* * *****************************************************************
 *
 * ****************************************************************** */

/**
 * Funcion que se encarga de mostrar una tabla paginadora para la lista de usuarios afiliados
 * @param string $pagina
 * @param string $path
 * @param string $slc
 * @param string $op
 * @return string $Tabla con la tabla que indica el listado del paginador
 * */
function ObtenerPaginadorNOAFI($pagina, $path, $slc, $op, $toma) {


    //echo "io";
    $TotalRegistros = $slc;
    $TablaPaginado = "";

    if ($limite == null) {
        $LimitRow = 10;
    } else {
        $LimitRow = 10;
    }
    if ($TotalRegistros > 0) {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros / $LimitRow);

        if ($TotalRegistros % $LimitRow > 0) {
            $NumeroPaginas++;
        }

        $Inicio = $pagina;
        if ($NumeroPaginas - $pagina < 9) {
            $Inicio = $NumeroPaginas - 9;
        } elseif ($pagina > 1) {
            $Inicio = $pagina - 1;
        }

        if ($Inicio <= 0) {
            $Inicio = 1;
        }

        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

        $TablaPaginado .= "<tr>\n";
        if ($NumeroPaginas > 1) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P&#225;ginas:</td>\n";
            if ($pagina > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                //  xajax_ObtenerDocumentosBodegaFinal(empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento);
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarUsuSysx('1','" . $slc . "','" . $toma . "');\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarUsuSysx('" . ($pagina - 1) . "','" . $slc . "','" . $toma . "');\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $Fin = $NumeroPaginas + 1;
            if ($NumeroPaginas > 10) {
                $Fin = 10 + $Inicio;
            }

            for ($i = $Inicio; $i < $Fin; $i++) {
                if ($i == $pagina) {
                    $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                } else {
                    $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BuscarUsuSysx('" . $i . "','" . $slc . "','" . $toma . "');\">" . $i . "</a></td>\n";
                }
                $columnas++;
            }
        }
        if ($pagina < $NumeroPaginas) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarUsuSysx('" . ($pagina + 1) . "','" . $slc . "','" . $toma . "');\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BuscarUsuSysx('" . ($NumeroPaginas) . "','" . $slc . "','" . $toma . "');\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
        $aviso .= "     P&#225;gina&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
        $aviso .= "   </tr>\n";

        if ($op == 2) {
            $TablaPaginado .= $aviso;
        } else {
            $TablaPaginado = $aviso . $TablaPaginado;
        }
    }

    $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
    $Tabla .= $TablaPaginado;
    $Tabla .= "</table>";

    return $Tabla;
}

/* * ***************************************************************
 * confirmar PARA AJUSTAR PRODUCTOS IGUALES A CERO
 * ****************************************************************** */

FUNCTION Ajustar_A_Cero($toma, $empresa, $cu, $bodega, $usuario, $numero_conteos) {
    $consulta = new TomaFisicaSQL();
    $objResponse = new xajaxResponse();
    $da .= "      <table width='100%' border='0'>\n";
    $da .= "       <tr>\n";
    $da .= "        <td colspan='2' class=\"label_error\">\n";
    $da .= "          ESTA SEGURO DE CUADRAR O AJUSTAR A CERO (0) LOS PRODUCTOS NO CONTADOS ?";
    $da .= "        </td>\n";
    $da .= "       </tr>\n";
    $da .= "       <tr>\n";
    $da .= "        <td align='center' colspan='2'>\n";
    $da .= "          &nbsp;";
    $da .= "        </td>\n";
    $da .= "       </tr>\n";
    $da .= "       <tr>\n";
    $da .= "        <td align='center'>\n";
    $da .= "          <input type=\"button\" class=\"input-submit\" value=\"ACEPTAR\" name=\"ELIMINAR_MOV\" onclick=\"xajax_Ajustar_Cero('" . trim($toma) . "','" . trim($empresa) . "','" . trim($cu) . "','" . trim($bodega) . "','" . trim($usuario) . "','" . trim($numero_conteos) . "');Cerrar('ContenedorB5');\">\n";
    $da .= "        </td>\n";
    $da .= "        <td align='center'>\n";
    $da .= "          <input type=\"button\" class=\"input-submit\" value=\"CANCELAR\" name=\"CANCELAR\" onclick=\"Cerrar('ContenedorB5');\">\n";
    $da .= "        </td>\n";
    $da .= "       </tr>\n";
    $da .= "      </table>\n";
    $objResponse->assign("ContenidoB5", "innerHTML", $da);
    return $objResponse;
}

/* * **************************************
 *
 * ************************************** */

function RetornarImpresionDoc($direccion, $alt, $imagen, $empresa_id, $prefijo, $numero) {
    global $VISTA;
    //$imagen1 = "<sub><img src=\"".$imagen."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
    $salida1 = "<a title='" . $alt . "' href=javascript:Imprimir('$direccion','$empresa_id','$prefijo','$numero')>" . $imagen . "</a>";
    return $salida1;
}

function RetornarImpresionDoc1($direccion, $alt, $imagen, $bodega_doc_id, $numero) {
    global $VISTA;
    $salida1 = "<a title='" . $alt . "' href=javascript:ImprimirModeloAnterior('$direccion','$bodega_doc_id','$numero')>" . $imagen . "</a>";
    return $salida1;
}

function CrearDocumentoIngreso_22($toma,$bodegas_doc_id)
{
    $consulta=new TomaFisicaSQL();
    $objResponse = new xajaxResponse();
    $objResponse->alert('toma '.$toma);
    $objResponse->alert('bodegas_doc_id '.$bodegas_doc_id);
    $resultado=$consulta->CrearDocumento($toma,$bodegas_doc_id);
    //var_dump($consulta->mensajeDeError);
    //var_dump($resultado);
    //$objResponse->alert($resultado);


    $direccion="app_modules/InvTomaFisica/reports/html/imprimir_docI001.php";
    //$objResponse->alert($resultado);
    $imagen = $resultado['prefijo']."-".$resultado['numero'];      //"themes/$VISTA/" . GetTheme() ."/images//imprimir.png";
    $alt="IMPRIMIR DOCUMENTO";
            $objResponse->alert($direccion);
            $objResponse->alert($alt);
            $objResponse->alert($imagen);
            $objResponse->alert($resultado['empresa_id']);
            $objResponse->alert($resultado['prefijo']);
            $objResponse->alert($resultado['numero']);
    $x=RetornarImpresionDoc($direccion,$alt,$imagen,$resultado['empresa_id'],$resultado['prefijo'],$resultado['numero']);

    $salida .= "                  <table width=\"50%\" align=\"center\">\n";
    $salida .= "                    <tr>\n";
    $salida .= "                      <td align=\"center\">\n";
    $salida .= "                       <label class='label_error'>  SE HA CREADO EL DOCUMENTO ".$x." POR UN COSTO TOTAL DE ".FormatoValor($resultado['total_costo'])."&nbsp;</label>";
    $salida .= "                      </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    </table>\n";
    $cuadrarproducto=$consulta->CuadrarTomaFisica($toma);    
    //$cambiar_estado=$consulta->InactivarTomaFisica($toma);    
    $objResponse->assign("tabla_empresas","innerHTML",$salida);
    $objResponse->script("CerrarLaVentana()");
    return $objResponse;
}

function Backup_existencias_bodegas_lote($cabecera,$empresa,$centro_utilidad,$bodega,$documento_egreso,$documento_ingreso){
  $consulta = new TomaFisicaSQL();
  $objResponse = new xajaxResponse();
  $resultado = $consulta-> Backup_existencias_bodegas_lote($cabecera,$empresa,$centro_utilidad,$bodega);
  $objResponse->alert('EGRESO: '.$documento_egreso);
  $objResponse->alert('INGRESO: '.$documento_ingreso);
  if($resultado){
    $egreso=CrearDocumento_Egreso($empresa,$centro_utilidad,$bodega,$cabecera,$documento_egreso);
    $objResponse->alert('EGRESO');
    $ingreso=CrearDocumento_Ingreso($empresa,$centro_utilidad,$bodega,$cabecera,$documento_ingreso);
    $objResponse->alert('INGRESO');
    $salida="SE GENERA EL RESPALDO DE LA TABLA 'existencias_bodegas_lote_fv' CORRECTAMENTE";
  }
  $objResponse->append("tabla_empresas", "innerHTML", $salida);
  return $objResponse;
}

function CrearDocumento_Ingreso($empresa,$centro_utilidad,$bodega,$cabecera, $bodegas_doc_id) {
    $consulta = new TomaFisicaSQL();
    $objResponse = new xajaxResponse();
    $resultado = $consulta->CrearDocumento_Ingreso($empresa,$centro_utilidad,$bodega,$cabecera,$bodegas_doc_id);
    $direccion = "app_modules/InvTomaFisica/reports/html/imprimir_docI001.php";
    $imagen = $resultado['prefijo'] . "-" . $resultado['numero'];      //"themes/$VISTA/" . GetTheme() ."/images//imprimir.png";
    $alt = "IMPRIMIR DOCUMENTO";
    $x = RetornarImpresionDoc($direccion, $alt, $imagen, $resultado['empresa_id'], $resultado['prefijo'], $resultado['numero']);

    $salida .= "                  <table width=\"50%\" align=\"center\">\n";
    $salida .= "                    <tr>\n";
    $salida .= "                      <td align=\"center\">\n";
    $salida .= "                       <label class='label_error'>  SE HA CREADO EL DOCUMENTO " . $x . " POR UN COSTO TOTAL DE " . FormatoValor($resultado['total_costo']) . "&nbsp;</label>";
    $salida .= "                      </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    </table>\n";
    $objResponse->script("document.getElementById('botonAjuste').disabled=true;");
    $objResponse->append("tabla_empresas", "innerHTML", $salida);
    return $objResponse;
}

function CrearDocumento_Egreso($empresa,$centro_utilidad,$bodega,$cabecera, $bodegas_doc_id) {
    $consulta = new TomaFisicaSQL();
    $objResponse = new xajaxResponse();
    $backup = $consulta-> Backup_existencias_bodegas_lote($cabecera,$empresa,$centro_utilidad,$bodega);
    $resultado = $consulta->CrearDocumento_Egreso($empresa,$centro_utilidad,$bodega,$cabecera,$bodegas_doc_id);
    
    if (empty($resultado)) {
        $objResponse->alert($consulta->mensajeDeError);
    } 

    $direccion = "app_modules/InvTomaFisica/reports/html/imprimir_docI001.php";

    $imagen = $resultado['prefijo'] . "-" . $resultado['numero'];      //"themes/$VISTA/" . GetTheme() ."/images//imprimir.png";
    $alt = "IMPRIMIR DOCUMENTO";
    $x = RetornarImpresionDoc($direccion, $alt, $imagen, $resultado['empresa_id'], $resultado['prefijo'], $resultado['numero']);
     $salida .= "<br>\n";
    IF ($resultado) {
        $salida .= "                  <table width=\"50%\" align=\"center\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .= "                       <label class='label_error'>  SE HA CREADO EL DOCUMENTO " . $x . " POR UN COSTO TOTAL DE " . FormatoValor($resultado['total_costo']) . "&nbsp;</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
    } ELSE {
        $salida .= "                  <table width=\"50%\" align=\"center\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .= "                       <label class='label_error'> ERROR NO SE CREO EL DOCUMENTO</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
    }
    $objResponse->append("tabla_empresas", "innerHTML", $salida);
    return $objResponse;
}

function CrearDocumentoEgreso_2($toma, $bodegas_doc_id) {
    $consulta = new TomaFisicaSQL();
    $objResponse = new xajaxResponse();
    $resultado = $consulta->CrearDocumentoEgreso($toma, $bodegas_doc_id);
    //var_dump($consulta->mensajeDeError);
    if (empty($resultado)) {
        $objResponse->alert($consulta->mensajeDeError);
    } else {
        $objResponse->alert($resultado);
    }

    $direccion = "app_modules/InvTomaFisica/reports/html/imprimir_docI001.php";

    $imagen = $resultado['prefijo'] . "-" . $resultado['numero'];      //"themes/$VISTA/" . GetTheme() ."/images//imprimir.png";
    $alt = "IMPRIMIR DOCUMENTO";
    $x = RetornarImpresionDoc($direccion, $alt, $imagen, $resultado['empresa_id'], $resultado['prefijo'], $resultado['numero']);
    IF ($resultado) {
        $salida .= "                  <table width=\"50%\" align=\"center\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .= "                       <label class='label_error'>  SE HA CREADO EL DOCUMENTO " . $x . " POR UN COSTO TOTAL DE " . FormatoValor($resultado['total_costo']) . "&nbsp;</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
        $cuadrarproducto = $consulta->CuadrarTomaFisica($toma);
        //$cambiar_estado=$consulta->InactivarTomaFisica($toma);
    } ELSE {
        $salida .= "                  <table width=\"50%\" align=\"center\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .= "                       <label class='label_error'> ERROR NO SE CREO EL DOCUMENTO</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
    }
    $objResponse->assign("div_de_egreso", "innerHTML", $salida);
    $objResponse->call("CerrarLaVentana1");
    return $objResponse;
}

/* * ***************************
 * SacarProductos para bodega
 * ************************** */

function ListarDocumentosIngresoAjax($toma, $empresa, $cu, $bodega, $usuario) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $vector = $consulta->ListarDocumentosIngreso($empresa, $cu, $bodega, $usuario);
    //var_dump($vector);
    ////////////////////////////////////////////////////////////
    $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $salida .= "                    <tr  class=\"modulo_table_list_title\">\n";
    $salida .= "                      <td align=\"center\" width=\"15%\">\n";
    $salida .= "                       <a title='BODEGA DOCUMENTO ID'>";
    $salida .= "                        BOD DOC ID";
    $salida .= "                       </a>";
    $salida .= "                      </td>\n";
    $salida .= "                      <td align=\"center\"width=\"15%\">\n";
    $salida .= "                       <a title='PREFIJO'>";
    $salida .= "                        PREFIJO";
    $salida .= "                       </a>";
    $salida .= "                      </td>\n";
    $salida .= "                      <td colspan='2' align=\"center\" width=\"60%\">\n";
    $salida .= "                        <a title='DESCRIPCION DEL DOCUMENTO'>DESCRIPCION</a>";
    $salida .= "                      </td>\n";
    $salida .= "                     </tr>\n";

    for ($i = 0; $i < count($vector); $i++) {
        $tr = "nocuadre3" . $i;
        $salida .= "                    <tr id='" . $tr . "' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
        $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
        $salida .= "                        <a title='BODEGA DOCUMENTO ID'>";
        $salida .= "                     " . $vector[$i]['bodegas_doc_id'] . "";
        $salida .= "                        </a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"left\">\n";
        $salida .= "                        <a title='PREFIJO DEL DOCUMENTO'>";
        $salida .= "                        " . $vector[$i]['prefijo'];
        $salida .= "                        </a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
        $salida .= "                        <a title=\"" . $vector[$i]['descripcion'] . "\">";
        $salida .= "                        " . substr($vector[$i]['descripcion'], 0, 40);
        $salida .= "                        </a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"right\" class=\"modulo_list_claro\">\n";
        $j = "javascript:CrearDocumento('" . $toma . "','" . $vector[$i]['bodegas_doc_id'] . "');";
        $salida .= "<a title='SELECCIONAR " . $vector[$i]['descripcion'] . "' class=\"label_error\" href=\"" . $j . "\">\n";
        $salida .= "                          <sub><img src=\"" . $path . "/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $salida .= "                         </a>\n";
        $salida .= "                      </td>\n";
        $salida .= "                      </tr>\n";
    }
    $salida .= "                     </table>\n";
    //////////////////////////////////////////////

    $objResponse->assign("ContenidoDocumentos", "innerHTML", $salida);
    return $objResponse;
}

/* * ***************************
 * SacarProductos para bodega
 * ************************** */

function ListarDocumentosEgresoAjax($toma, $empresa, $cu, $bodega, $usuario) {

    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $vector = $consulta->ListarDocumentosEgreso($empresa, $cu, $bodega, $usuario);
    $autorizacion = $consulta->Buscarparamprod($empresa, $toma);
    //priny($autorizacion);
    //var_dump($vector);
    ////////////////////////////////////////////////////////////
    $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $salida .= "                    <tr  class=\"modulo_table_list_title\">\n";
    $salida .= "                      <td align=\"center\" width=\"15%\">\n";
    $salida .= "                       <a title='BODEGA DOCUMENTO ID'>";
    $salida .= "                        BOD DOC ID";
    $salida .= "                       </a>";
    //$salida .= "<pre>".print_r($autorizacion)."</pre>";
    $salida .= "                      </td>\n";
    $salida .= "                      <td align=\"center\"width=\"15%\">\n";
    $salida .= "                       <a title='PREFIJO'>";
    $salida .= "                        PREFIJO";
    $salida .= "                       </a>";
    $salida .= "                      </td>\n";
    $salida .= "                      <td colspan='2' align=\"center\" width=\"60%\">\n";
    $salida .= "                        <a title='DESCRIPCION DEL DOCUMENTO'>DESCRIPCION</a>";
    $salida .= "                      </td>\n";
    $salida .= "                     </tr>\n";

    for ($i = 0; $i < count($vector); $i++) {
        $tr = "nocuadre3" . $i;
        $salida .= "                    <tr id='" . $tr . "' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
        $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
        $salida .= "                        <a title='BODEGA DOCUMENTO ID'>";
        $salida .= "                     " . $vector[$i]['bodegas_doc_id'] . "";
        $salida .= "                        </a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"left\">\n";
        $salida .= "                        <a title='PREFIJO DEL DOCUMENTO'>";
        $salida .= "                        " . $vector[$i]['prefijo'];
        $salida .= "                        </a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
        $salida .= "                        <a title=\"" . $vector[$i]['descripcion'] . "\">";
        $salida .= "                        " . substr($vector[$i]['descripcion'], 0, 40);
        $salida .= "                        </a>";
        $salida .= "                      </td>\n";
        /* if($autorizacion[0]['sw_jefebodega']==1 and $autorizacion[0]['sw_jefecontroli']==1)
          { */
        $salida .= "                      <td align=\"right\" class=\"modulo_list_claro\">\n";
        $j = "javascript:CrearDocumentoEgreso('" . $toma . "','" . $vector[$i]['bodegas_doc_id'] . "');";
        $salida .= "<a title='SELECCIONAR " . $vector[$i]['descripcion'] . "' class=\"label_error\" href=\"" . $j . "\">\n";
        $salida .= "                          <sub><img src=\"" . $path . "/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $salida .= "                         </a>\n";
        $salida .= "                      </td>\n";
        //}*/
        /* else
          {
          $salida .= "                      <td align=\"CENTER\" class=\"modulo_list_claro\">\n";
          $salida .= "                        <a title='AUTORIZACION'>NO TIENE AUTORIZACION DE JEFE BODEGA Y JEFE DE CONTROL INTERNO</a>";
          $salida .= "                      </td>\n";
          } */
        $salida .= "                      </tr>\n";
    }
    $salida .= "                     </table>\n";
    //////////////////////////////////////////////

    $objResponse->assign("ContenidoB5", "innerHTML", $salida);
    return $objResponse;
}

/* * ************************
 * *FUNCION SIN CONTEO
 * ************************ */

function ConsultaParaCierre($toma_fisica, $empresa, $cu, $bodega, $usuario, $numero_conteos) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $vector = $consulta->ContarParaCierre($toma_fisica);
    /* $vector1=$consulta->InconsistenciasC1($toma_fisica);
      $vector2=$consulta->InconsistenciasC2($toma_fisica);
      $vector3=$consulta->InconsistenciasC3($toma_fisica); */

    /* $ExistenciasVsTF=$consulta->InconSistemaVsTomaF($toma_fisica,$empresa,$cu,$bodega); */

    //print_r($ExistenciasVsTF);
    $salida .= "                  <table width=\"40%\" BORDER='0' align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
    /* if($ExistenciasVsTF)
      { */
    $salida .= "                    <tr>\n";
    //$salida .= "<pre>".print_r($vector,true)."</pre>";
    $salida .= "                      <td width=\"95%\" class=\"modulo_table_list_title\" >\n";
    $salida .= "							GENERAR REPORTE AJUSTES - INVENTARIO";
    $salida .= "						</td>";
    $salida .= "						<td width=\"5%\" align=\"center\">";
    $salida .= "                        <a href=\"javascript:WindowPrinter0007()\" class=\"label_error\"> <img src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE INCOSISTENCIAS \"></a>\n";
    $salida .= "                      </td>\n";
    $salida .= "                    </tr>\n";

    /* } */
    /* $salida .= "                    <tr>\n";
      $salida .= "                      <td class=\"modulo_table_list_title\" >\n";
      $salida .= "							GENERAR REPORTE AJUSTE AUTOMATICO";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\">";
      $salida .= "                        <a href=\"javascript:WindowPrinter0008()\" class=\"label_error\"><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE AJUSTE AUTOMATICO \"></a>\n";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n"; */
    $salida .= "                    <tr>\n";
    $salida .= "                      <td class=\"modulo_table_list_title\" >\n";
    $salida .= "							GENERAR REPORTE DE LOS CONTEOS";
    $salida .= "                      </td >\n";
    $salida .= "                      <td align=\"center\">";
    $salida .= "                        <a href=\"javascript:WindowPrinter0009()\" class=\"label_error\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE AJUSTE AUTOMATICO \"></a>\n";
    $salida .= "                      </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                  </table>\n";
    /* if($vector1)
      {
      for($i=0;$i<$contar;$i++)
      {
      if($vector1[$i]['existencia']<$vector1[$i]['conteo_1'])
      {
      $salida .= "                  <table width=\"100%\" BORDER='0' align=\"center\">\n";
      $salida .= "                    <tr>\n";
      //$salida .= "<pre>".print_r($vector,true)."</pre>";
      $salida .= "                      <td align=\"CENTER\" width=\"100%\">\n";
      $salida .= "                        <a href=\"javascript:WindowPrinter0004()\" class=\"label_error\">GENERAR REPORTE INCOSISTENCIAS C1  <sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE INCOSISTENCIAS C1 \"></sub>&nbsp;</a>\n";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                  </table>\n";
      $k++;
      }
      else
      {
      $m++;
      }
      }
      } */
    /* if($vector2)
      {
      $contar1=count($vector);
      $k1=0;
      $m1=0;
      for($i=0;$i<$contar1;$i++)
      {
      if($vector2[$i]['existencia']<$vector2[$i]['conteo_1'])
      {
      $salida .= "                  <table width=\"100%\" BORDER='0' align=\"center\">\n";
      $salida .= "                    <tr>\n";
      //$salida .= "<pre>".print_r($vector,true)."</pre>";
      $salida .= "                      <td align=\"CENTER\" width=\"100%\">\n";
      $salida .= "                        <a href=\"javascript:WindowPrinter0005()\" class=\"label_error\">GENERAR REPORTE INCOSISTENCIAS C2  <sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE INCOSISTENCIAS C2 \"></sub>&nbsp;</a>\n";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                  </table>\n";
      $k1++;
      }
      else
      {
      $m1++;
      }
      }
      } */

    /* if($vector3)
      {
      $contar2=count($vector);
      $k2=0;
      $m2=0;
      for($i=0;$i<$contar2;$i++)
      {
      if($vector2[$i]['existencia']<$vector3[$i]['conteo_1'])
      {
      $salida .= "                  <table width=\"100%\" BORDER='0' align=\"center\">\n";
      $salida .= "                    <tr>\n";
      //$salida .= "<pre>".print_r($vector,true)."</pre>";
      $salida .= "                      <td align=\"CENTER\" width=\"100%\">\n";
      $salida .= "                        <a href=\"javascript:WindowPrinter0006()\" class=\"label_error\">GENERAR REPORTE INCOSISTENCIAS C3  <sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE INCOSISTENCIAS C3 \"></sub>&nbsp;</a>\n";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                  </table>\n";
      $k2++;
      }
      else
      {
      $m2++;
      }
      }
      } */

    //var_dump($vector);
    if (!empty($vector)) {
        //-$variable=SistemaVsTomaFisica($toma_fisica);
        //$salida .=$vector;
        $salida .= "                  <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td colspan='2' class=\"formulacion_table_list\" align=\"left\">\n";
        $salida .= "                       <a title='RESUMEN TOMA FISICA'>";
        $salida .= "                        RESUMEN PRODUCTOS TOMA FISICA";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td class=\"modulo_table_list_title\" align=\"left\">\n";
        $salida .= "                       <a title='PRODUCTOS CONTEO 1 SIN CUADRAR'>";
        $salida .= "                        PRODUCTOS CONTEO 1 SIN CUADRAR";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"right\" class=\"modulo_list_claro\" >\n";
        $salida .= "                       <label>" . $vector[0]['cont_conteo1'] . "</label>";
        $salida .= "                      </td>\n";
        $salida .= "                     </tr>\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td class=\"modulo_table_list_title\" align=\"left\">\n";
        $salida .= "                       <a title='PRODUCTOS CONTEO 2 SIN CUADRAR'>";
        $salida .= "                        PRODUCTOS CONTEO 2 SIN CUADRAR";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"right\" class=\"modulo_list_claro\">\n";
        $salida .= "                        " . $vector[0]['cont_conteo2'];
        $salida .= "                      </td>\n";
        $salida .= "                     </tr>\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td  class=\"modulo_table_list_title\" align=\"left\">\n";
        $salida .= "                       <a title='PRODUCTOS CONTEO 3 SIN CUADRAR'>";
        $salida .= "                        PRODUCTOS CONTEO 3 SIN CUADRAR";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"right\" class=\"modulo_list_claro\">\n";
        $salida .= "                        " . $vector[0]['cont_conteo3'];
        $salida .= "                      </td>\n";
        $salida .= "                     </tr>\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td  class=\"modulo_table_list_title\" align=\"left\">\n";
        $salida .= "                       <a title='PRODUCTOS SIN CONTAR'>";
        $salida .= "                        PRODUCTOS SIN CONTAR";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"right\" class=\"modulo_list_claro\">\n";
        if ($vector[0]['cont_conteo3'] == 0 && $vector[0]['cont_conteo2'] == 0 && $vector[0]['cont_conteo1'] == 0 && $vector[0]['sin_contar'] > 0) {
            $j = "javascript:MostrarCapa('ContenedorB5'); CuadrarSinContar('" . trim($toma_fisica) . "','" . trim($empresa) . "','" . trim($cu) . "','" . trim($bodega) . "','" . trim($usuario) . "','" . trim($numero_conteos) . "');IniciarB5('CONFIRMACION');";
            $salida .= "<a title='CUADRAR PRODUCTOS SIN CONTAR' class=\"label_error\" href=\"" . $j . "\">\n";
            $salida .= "                          <sub><img src=\"" . $path . "/images/uf.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
            $salida .= "                         </a>\n";
        }
        $salida .= "                         " . $vector[0]['sin_contar'];
        $salida .= "                      </td>\n";
        $salida .= "                     </tr>\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td class=\"modulo_table_list_title\" align=\"left\">\n";
        $salida .= "                       <a title='PRODUCTOS CUADRADOS'>";
        $salida .= "                        PRODUCTOS CUADRADOS";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"right\" class=\"modulo_list_claro\">\n";
        $salida .= "                        " . $vector[0]['cuadrados'];
        $salida .= "                      </td>\n";
        $salida .= "                     </tr>\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td class=\"modulo_table_list_title\" align=\"left\">\n";
        $salida .= "                       <a title='TOTAL PRODUCTOS TOMA FISICA'>";
        $salida .= "                        TOTAL PRODUCTOS TOMA FISICA";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n";
        $suma = $vector[0]['cont_conteo1'] +
                $vector[0]['cont_conteo2'] +
                $vector[0]['cont_conteo3'] +
                $vector[0]['sin_contar'] +
                $vector[0]['cuadrados'];
        $salida .= "                      <td align=\"right\" class=\"modulo_list_claro\">\n";
        $salida .= "                      " . $suma . "";
        $salida .= "                      </td>\n";
        $salida .= "                     </tr>\n";
        $salida .= "                </table>\n";
        $salida .= "                <br>\n";

        //VAR_DUMP($vector);
        if ($vector[0]['cont_conteo3'] == 0 && $vector[0]['cont_conteo2'] == 0 && $vector[0]['cont_conteo1'] == 0) {
            $vector1 = $consulta->ProductosParaAjustarPorIngreso($toma_fisica);
            $vector2 = $consulta->ProductosParaAjustarPorEgreso($toma_fisica);
            //VAR_DUMP($vector1);
            //VAR_DUMP($vector2);
            //$objResponse->alert($vector2[0]['count']);
            if ($vector[0]['sin_contar'] == 0 && count($vector1) > 0) {
                //$objResponse->alert($vector1[0]['count']);
                //VAR_DUMP($vector1);
                $salida .= "    <div id='div_de_ingreso'>\n";
                $salida .= "                  <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
                $salida .= "                    <tr>\n";
                $salida .= "                      <td class=\"formulacion_table_list\" align=\"left\">\n";
                $salida .= "                        <a title='PRODUCTOS PARA AJUSTAR POR INGRESO'>";
                $salida .= "                          PRODUCTOS PARA AJUSTAR POR INGRESO";
                $salida .= "                        </a>";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\" class=\"modulo_list_claro\" >\n";
                $j = "javascript:MostrarCapa('ContenedorDocumentos');SacarProductosParaDocumento('" . trim($toma_fisica) . "','" . trim($empresa) . "','" . trim($cu) . "','" . trim($bodega) . "','" . trim($usuario) . "'); IniciarDocumentos('SELECCIONAR DOCUMENTO DE AJUSTE');"; //CuadrarSinContar('".$toma_fisica."');
                $salida .= "" . (count($vector1)) . "<a title='OBSERVAR PRODUCTOS' class=\"label_error\" href=\"" . $j . "\">\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/uf.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
                $salida .= "                     </td>\n";
                $salida .= "                   </tr>\n";
                $salida .= "                  </table>\n";
                $salida .= "                </div>\n";
            }
            if ($vector[0]['sin_contar'] == 0 && count($vector2) > 0) {
                //$objResponse->alert($vector2[0]['count']);
                $salida .= "    <div id='div_de_egreso'>\n";
                $salida .= "                  <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
                $salida .= "                    <tr>\n";
                $salida .= "                      <td class=\"formulacion_table_list\" align=\"left\">\n";
                $salida .= "                        <a title='PRODUCTOS PARA AJUSTAR POR EGRESO'>";
                $salida .= "                          PRODUCTOS PARA AJUSTAR POR EGRESO";
                $salida .= "                        </a>";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\" class=\"modulo_list_claro\" >\n";
                $j = "javascript:MostrarCapa('ContenedorB5');SacarProductosParaDocumentoEgreso('" . trim($toma_fisica) . "','" . trim($empresa) . "','" . trim($cu) . "','" . trim($bodega) . "','" . trim($usuario) . "'); IniciarB5('CONFIRMACION');"; //CuadrarSinContar('".$toma_fisica."');
                $salida .= "" . (count($vector2)) . "<a title='AJUSTAR PRODUCTOS POR EGRESO' class=\"label_error\" href=\"" . $j . "\">\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/uf.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
                $salida .= "                     </td>\n";
                $salida .= "                   </tr>\n";
                $salida .= "                  </table>\n";
                $salida .= "                </div>\n";
            }

            $salida .= "                  <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr>\n";
            $salida .= "                      <td colspan='2' class=\"formulacion_table_list\" align=\"left\">\n";
            $salida .= "                       <a title='RESUMEN TOMA FISICA'>";
            $salida .= "                        VALOR DE INVENTARIOS";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    <tr>\n";
            $salida .= "                      <td class=\"modulo_table_list_title\" align=\"left\">\n";
            $salida .= "                       <a title='VALOR TOTAL DEL INVENTARIO ACTUAL'>";
            $salida .= "                        VALOR TOTAL DEL INVENTARIO ANTERIOR";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"modulo_list_claro\" >\n";
            $salida .= "                       <label>" . FormatoValor($vector[0]['inv_anterior']) . "</label>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    <tr>\n";
            $salida .= "                      <td class=\"modulo_table_list_title\" align=\"left\">\n";
            $salida .= "                       <a title='TOTAL PRODUCTOS TOMA FISICA'>";
            $salida .= "                        VALOR TOTAL DEL INVENTARIO ACTUAL";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"modulo_list_claro\">\n";
            $salida .= "                       <label>" . FormatoValor($vector[0]['inv_actual']) . "</label>";
            $salida .= "                      </td>\n";
            $salida .= "                     </tr>\n";
            $salida .= "                    <tr>\n";
            $salida .= "                      <td class=\"modulo_table_list_title\" align=\"left\">\n";
            $salida .= "                       <a title='DIFERENCIA VALOR TOTAL DE INVENTARIO ANTERIOR - ACTUAL'>";
            $salida .= "                        DIFERENCIA INV ANTERIOR -INV ACTUAL";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"modulo_list_claro\">\n";
            $diferencia = ($vector[0]['inv_anterior'] - $vector[0]['inv_actual']);
            if ($diferencia < 0) {
                $salida .= "                       <label class='label_error'>" . FormatoValor($diferencia) . "</label>";
            } else {
                $salida .= "                       <label>" . FormatoValor($diferencia) . "</label>";
            }

            $salida .= "                      </td>\n";
            $salida .= "                     </tr>\n";
            $salida .= "                   </table>\n";
        }
        $objResponse->call($variable);
    } else {
        $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .= "                         <label ALIGN='center' class='label_error1'> NO SE ENCONTRARON RESULTADOS</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
    }
    $salida = $objResponse->setTildes($salida);
    $objResponse->assign("Cierrex", "innerHTML", $salida);
    return $objResponse;
}

/* * **************************************************************
 *
 * **************************************************************** */

FUNCTION Borrar($tr, $toma, $etiqueta, $CONTENIDOR) {
    $consulta = new TomaFisicaSQL();
    $objResponse = new xajaxResponse();
    $da .= "      <table width='100%' border='0'>\n";
    $da .= "       <tr>\n";
    $da .= "        <td colspan='2' class=\"label_error\">\n";
    $da .= "          ESTA SEGURO DE ELIMINAR ESTE PRODUCTO AJUSTADO ?";
    $da .= "        </td>\n";
    $da .= "       </tr>\n";
    $da .= "       <tr>\n";
    $da .= "        <td align='center' colspan='2'>\n";
    $da .= "          &nbsp;";
    $da .= "        </td>\n";
    $da .= "       </tr>\n";
    $da .= "       <tr>\n";
    $da .= "        <td align='center'>\n";

    $C = substr($CONTENIDOR, (strlen($CONTENIDOR) - 2), 2);
    $da .= "          <input type=\"button\" class=\"input-submit\" value=\"ELIMINAR\" name=\"ELIMINAR_MOV\" onclick=\"xajax_BorrarAjuste('" . $tr . "','" . $toma . "','" . $etiqueta . "');Cerrar('Contenedor" . $C . "');\">\n";
    $da .= "        </td>\n";
    $da .= "        <td align='center'>\n";
    $da .= "          <input type=\"button\" class=\"input-submit\" value=\"CANCELAR\" name=\"CANCELAR\" onclick=\"Cerrar('Contenedor" . $C . "');\">\n";
    $da .= "        </td>\n";
    $da .= "       </tr>\n";
    $da .= "      </table>\n";
    $objResponse->assign($CONTENIDOR, "innerHTML", $da);
    return $objResponse;
}

/* * *************************************************************
 * ELIMINAR AJUSTES DE PRODUCTOS
 * ************************************************************* */

function BorrarAjuste($tr, $toma_fisica_id, $etiqueta) {
    $consulta = new TomaFisicaSQL();
    $objResponse = new xajaxResponse();
    $buscar = $consulta->EliminarAjuste($toma_fisica_id, $etiqueta);
    $objResponse->alert($buscar);
    $objResponse->remove($tr);
    return $objResponse;
}

/* * *******************************************************************
 * funxcion para cuadrar no cuadrados conteo 2
 * ******************************************************************** */

function SetCuadrarPro2($tr, $toma_fisica_id, $etiqueta, $num_conteo, $sw_manual, $empresa_id, $centro_utilidad, $bodega, $codigo_producto, $existencia, $nueva_existencia, $costo, $lote, $fecha_vencimiento) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $vector = $consulta->AddCuadrar($toma_fisica_id, $etiqueta, $num_conteo, $sw_manual, $empresa_id, $centro_utilidad, $bodega, $codigo_producto, $existencia, $nueva_existencia, $costo, $lote, $fecha_vencimiento);

    if (!empty($vector)) {
        $objResponse->alert($vector);
        $objResponse->remove($tr);
        $objResponse->call("Cerrarno2");
        //$objResponse->assign("errorAj","innerHTML",$vector);
        //$objResponse->assign("conteo1x","innerHTML",$vector[0]['diferencia_1']);
    }

    return $objResponse;
}

/* * *********************
 * div del no cuadre2
 * *********************** */

function CuadrarPro2($tr, $toma, $etiqueta) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $vector = $consulta->GetNoCuadre($toma, $etiqueta);
    //$nn=$vector[0]['existencia']; 
    //$objResponse->alert($nn);
    if (!empty($vector)) {
//       toma_fisica_id  etiqueta  codigo_producto
//       descripcion unidad_id descripcion_unidad
//       existencia  conteo_1  validacion_conteo_1
//       diferencia_1  conteo_2  validacion_conteo_2
//       diferencia_2  conteo_3  validacion_conteo_3 diferencia_3
        $objResponse->assign("uno2", "checked", "true");
        $objResponse->assign("nueva_existencia2", "value", "");
        $objResponse->assign("dife2", "innerHTML", "");
        $objResponse->assign("toma_fisica2", "innerHTML", $vector[0]['toma_fisica_id']);
        $objResponse->assign("etiquetaxy2", "innerHTML", $vector[0]['etiqueta']);
        $objResponse->assign("desc2", "innerHTML", $vector[0]['descripcion']);
        $objResponse->assign("unidad2", "innerHTML", $vector[0]['descripcion_unidad']);
        $objResponse->assign("exist2", "innerHTML", $vector[0]['existencia']);
        $objResponse->assign("conteo1x2", "innerHTML", $vector[0]['conteo_1']);
        $objResponse->assign("conteo2x2", "innerHTML", $vector[0]['conteo_2']);
        $objResponse->assign("conteo3x2", "innerHTML", $vector[0]['conteo_3']);
        $objResponse->assign("conteo1x2dif1", "innerHTML", "<a title='DIFERENCIA CON EXISTENCIA'>&#60;" . $vector[0]['diferencia_1'] . "&#62;</a>");
        $objResponse->assign("conteo2x2dif2", "innerHTML", "<a title='DIFERENCIA CON EXISTENCIA'>&#60;" . $vector[0]['diferencia_2'] . "&#62;</a>");
        $objResponse->assign("conteo3x2dif3", "innerHTML", "<a title='DIFERENCIA CON EXISTENCIA'>&#60;" . $vector[0]['diferencia_3'] . "&#62;</a>");
        $objResponse->assign("tr_h2", "value", $tr);
        $objResponse->assign("uno2", "value", $vector[0]['existencia']);
        $objResponse->assign("dos2", "value", $vector[0]['conteo_1']);
        $objResponse->assign("tres2", "value", $vector[0]['conteo_2']);
        $objResponse->assign("cuatro2", "value", $vector[0]['conteo_3']);
        ///////datos//////////
        $objResponse->assign("toma_fisica_id_h2", "value", $vector[0]['toma_fisica_id']);
        $objResponse->assign("etiqueta_h2", "value", $vector[0]['etiqueta']);
        $objResponse->assign("num_conteo_h2", "value", '3');
        $objResponse->assign("sw_manual_h2", "value", '1');
        $objResponse->assign("empresa_id_h2", "value", $vector[0]['empresa_id']);
        $objResponse->assign("centro_utilidad_h2", "value", $vector[0]['centro_utilidad']);
        $objResponse->assign("bodega_h2", "value", $vector[0]['bodega']);
        $objResponse->assign("codigo_producto_h2", "value", $vector[0]['codigo_producto']);
        $objResponse->assign("existencia_h2", "value", $vector[0]['existencia']);
        $objResponse->assign("costo_h2", "value", $vector[0]['costo']);
        $objResponse->assign("lote_h2", "value", $vector[0]['lote']);
        $objResponse->assign("fecha_vencimiento_h2", "value", $vector[0]['fecha_vencimiento']);
    }

    return $objResponse;
}

/* * *******************************************************************
 * funxcion para cuadrar no cuadrados conteo 2
 * ******************************************************************** */

function SetCuadrarPro1($tr, $toma_fisica_id, $etiqueta, $num_conteo, $sw_manual, $empresa_id, $centro_utilidad, $bodega, $codigo_producto, $existencia, $nueva_existencia, $costo, $lote, $fecha_vencimiento) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $vector = $consulta->AddCuadrar($toma_fisica_id, $etiqueta, $num_conteo, $sw_manual, $empresa_id, $centro_utilidad, $bodega, $codigo_producto, $existencia, $nueva_existencia, $costo, $lote, $fecha_vencimiento);

    if (!empty($vector)) {
        $objResponse->alert($vector);
        $objResponse->remove($tr);
        $objResponse->call("Cerrarno1");
        //$objResponse->assign("errorAj","innerHTML",$vector);
        //$objResponse->assign("conteo1x","innerHTML",$vector[0]['diferencia_1']);
    }
    return $objResponse;
}

function ModificarConteo2($tr, $toma_fisica_id, $etiqueta, $num_conteo, $sw_manual, $empresa_id, $centro_utilidad, $bodega, $codigo_producto, $existencia, $nueva_existencia_conteo, $costo, $lote, $fecha_vencimiento) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $vector = $consulta->ActualizarConteo2($toma_fisica_id, $etiqueta, $nueva_existencia_conteo, $num_conteo);
    $this->salida .= $vector;
    if (!empty($vector)) {
        $objResponse->alert($vector);
        $objResponse->remove($tr);
        $objResponse->script("xajax_NoCuadraConteo2('" . $toma_fisica_id . "');");
        //$objResponse->assign("Modificacion","innerHTML",$this->salida);
        //$objResponse->call("CerrarModificacion");
        //$objResponse->assign("errorAj","innerHTML",$vector);
        //$objResponse->assign("conteo1x","innerHTML",$vector[0]['diferencia_1']);
    }
    return $objResponse;
}

function ModificarConteo3($tr, $toma_fisica_id, $etiqueta, $num_conteo, $sw_manual, $empresa_id, $centro_utilidad, $bodega, $codigo_producto, $existencia, $nueva_existencia_conteo, $costo, $lote, $fecha_vencimiento) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $vector = $consulta->ActualizarConteo2($toma_fisica_id, $etiqueta, $nueva_existencia_conteo, $num_conteo);
    if (!empty($vector)) {
        $objResponse->alert($vector);
        $objResponse->remove($tr);
        $objResponse->script("xajax_NoCuadraConteo3('" . $toma_fisica_id . "');");
    }
    return $objResponse;
}

/* * *********************
 * div del no cuadre2
 * *********************** */

function CuadrarPro1($tr, $toma, $etiqueta) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $vector = $consulta->GetNoCuadre($toma, $etiqueta);
    //$nn=$vector[0]['existencia']; 
    //$objResponse->alert($nn);
    if (!empty($vector)) {
//       toma_fisica_id  etiqueta  codigo_producto
//       descripcion unidad_id descripcion_unidad
//       existencia  conteo_1  validacion_conteo_1
//       diferencia_1  conteo_2  validacion_conteo_2
//       diferencia_2  conteo_3  validacion_conteo_3 diferencia_3
        $objResponse->assign("uno1", "checked", "true");
        $objResponse->assign("nueva_existencia1", "value", "");
        $objResponse->assign("dife1", "innerHTML", "");
        $objResponse->assign("toma_fisica1", "innerHTML", $vector[0]['toma_fisica_id']);
        $objResponse->assign("etiquetaxy1", "innerHTML", $vector[0]['etiqueta']);
        $objResponse->assign("etiquetaGral1", "innerHTML", $vector[0]['etiqueta_x_producto']);
        $objResponse->assign("desc1", "innerHTML", $vector[0]['descripcion']);
        $objResponse->assign("unidad1", "innerHTML", $vector[0]['descripcion_unidad']);
        $objResponse->assign("exist1", "innerHTML", $vector[0]['existencia']);
        $objResponse->assign("conteo1x1", "innerHTML", $vector[0]['conteo_1']);
        $objResponse->assign("conteo2x1", "innerHTML", $vector[0]['conteo_2']);
        $objResponse->assign("conteo1x1dif1", "innerHTML", "<a title='DIFERENCIA CON EXISTENCIA'>&#60;" . $vector[0]['diferencia_1'] . "&#62;</a>");
        $objResponse->assign("conteo2x1dif2", "innerHTML", "<a title='DIFERENCIA CON EXISTENCIA'>&#60;" . $vector[0]['diferencia_2'] . "&#62;</a>");
        $objResponse->assign("tr_h1", "value", $tr);
        $objResponse->assign("uno1", "value", $vector[0]['existencia']);
        $objResponse->assign("dos1", "value", $vector[0]['conteo_1']);
        $objResponse->assign("tres1", "value", $vector[0]['conteo_2']);
        ///////datos//////////
        $objResponse->assign("toma_fisica_id_h1", "value", $vector[0]['toma_fisica_id']);
        $objResponse->assign("etiqueta_h1", "value", $vector[0]['etiqueta']);
        $objResponse->assign("num_conteo_h1", "value", '2');
        $objResponse->assign("sw_manual_h1", "value", '1');
        $objResponse->assign("empresa_id_h1", "value", $vector[0]['empresa_id']);
        $objResponse->assign("centro_utilidad_h1", "value", $vector[0]['centro_utilidad']);
        $objResponse->assign("bodega_h1", "value", $vector[0]['bodega']);
        $objResponse->assign("codigo_producto_h1", "value", $vector[0]['codigo_producto']);
        $objResponse->assign("existencia_h1", "value", $vector[0]['existencia']);
        $objResponse->assign("costo_h1", "value", $vector[0]['costo']);
        $objResponse->assign("lote_h1", "value", $vector[0]['lote']);
        $objResponse->assign("fecha_vencimiento_h1", "value", $vector[0]['fecha_vencimiento']);
    }

    return $objResponse;
}

/* * *********************
 * div del no cuadre2
 * *********************** */

function ModificarC2($tr, $toma, $etiqueta) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $vector = $consulta->GetNoCuadre($toma, $etiqueta);
    //print_r($vector);
    //$nn=$vector[0]['existencia']; 
    //$objResponse->alert($nn);
    if (!empty($vector)) {
//       toma_fisica_id  etiqueta  codigo_producto
//       descripcion unidad_id descripcion_unidad
//       existencia  conteo_1  validacion_conteo_1
//       diferencia_1  conteo_2  validacion_conteo_2
//       diferencia_2  conteo_3  validacion_conteo_3 diferencia_3
        $objResponse->assign("uno1", "checked", "true");
        $objResponse->assign("nueva_existencia1", "value", "");
        $objResponse->assign("dife1", "innerHTML", "");
        $objResponse->assign("toma_fisica_m", "innerHTML", $vector[0]['toma_fisica_id']);
        $objResponse->assign("etiquetaxy1", "innerHTML", $vector[0]['etiqueta']);
        $objResponse->assign("desc_m", "innerHTML", $vector[0]['descripcion']);
        $objResponse->assign("unidad1", "innerHTML", $vector[0]['descripcion_unidad']);
        $objResponse->assign("exist1", "innerHTML", $vector[0]['existencia']);
        $objResponse->assign("conteo1x1", "innerHTML", $vector[0]['conteo_1']);
        $objResponse->assign("conteo_m2x1", "innerHTML", $vector[0]['conteo_2']);
        $objResponse->assign("conteo1x1dif1", "innerHTML", "<a title='DIFERENCIA CON EXISTENCIA'>&#60;" . $vector[0]['diferencia_1'] . "&#62;</a>");
        $objResponse->assign("conteo2x1dif2", "innerHTML", "<a title='DIFERENCIA CON EXISTENCIA'>&#60;" . $vector[0]['diferencia_2'] . "&#62;</a>");
        $objResponse->assign("tr_h1", "value", $tr);
        $objResponse->assign("uno1", "value", $vector[0]['existencia']);
        $objResponse->assign("dos1", "value", $vector[0]['conteo_1']);
        $objResponse->assign("tres1", "value", $vector[0]['conteo_2']);
        ///////datos//////////
        $objResponse->assign("toma_fisica_id_h1_m", "value", $vector[0]['toma_fisica_id']);
        $objResponse->assign("etiqueta_h1_m", "value", $vector[0]['etiqueta']);
        $objResponse->assign("num_conteo_h1_m", "value", '2');
        $objResponse->assign("sw_manual_h1_m", "value", '1');
        $objResponse->assign("empresa_id_h1_m", "value", $vector[0]['empresa_id']);
        $objResponse->assign("centro_utilidad_h1_m", "value", $vector[0]['centro_utilidad']);
        $objResponse->assign("bodega_h1_m", "value", $vector[0]['bodega']);
        $objResponse->assign("codigo_producto_h1_m", "value", $vector[0]['codigo_producto']);
        $objResponse->assign("existencia_h1_m", "value", $vector[0]['existencia']);
        $objResponse->assign("costo_h1_m", "value", $vector[0]['costo']);
        $objResponse->assign("lote_h1_m", "value", $vector[0]['lote']);
        $objResponse->assign("fecha_vencimiento_h1_m", "value", $vector[0]['fecha_vencimiento']);
    }
    return $objResponse;
}

function ModificarC3($tr, $toma, $etiqueta) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $vector = $consulta->GetNoCuadre($toma, $etiqueta);
    //print_r($vector);
    //$nn=$vector[0]['existencia']; 
    //$objResponse->alert($nn);
    if (!empty($vector)) {
//       toma_fisica_id  etiqueta  codigo_producto
//       descripcion unidad_id descripcion_unidad
//       existencia  conteo_1  validacion_conteo_1
//       diferencia_1  conteo_2  validacion_conteo_2
//       diferencia_2  conteo_3  validacion_conteo_3 diferencia_3
        $objResponse->assign("uno1", "checked", "true");
        $objResponse->assign("nueva_existencia1", "value", "");
        $objResponse->assign("dife1", "innerHTML", "");
        $objResponse->assign("toma_fisica_m3", "innerHTML", $vector[0]['toma_fisica_id']);
        $objResponse->assign("etiquetaxy1", "innerHTML", $vector[0]['etiqueta']);
        $objResponse->assign("desc_m3", "innerHTML", $vector[0]['descripcion']);
        $objResponse->assign("unidad1", "innerHTML", $vector[0]['descripcion_unidad']);
        $objResponse->assign("exist1", "innerHTML", $vector[0]['existencia']);
        $objResponse->assign("conteo1x1", "innerHTML", $vector[0]['conteo_1']);
        $objResponse->assign("conteo_mi2x1", "innerHTML", $vector[0]['conteo_3']);
        $objResponse->assign("conteo1x1dif1", "innerHTML", "<a title='DIFERENCIA CON EXISTENCIA'>&#60;" . $vector[0]['diferencia_1'] . "&#62;</a>");
        $objResponse->assign("conteo2x1dif2", "innerHTML", "<a title='DIFERENCIA CON EXISTENCIA'>&#60;" . $vector[0]['diferencia_2'] . "&#62;</a>");
        $objResponse->assign("tr_h1", "value", $tr);
        $objResponse->assign("uno1", "value", $vector[0]['existencia']);
        $objResponse->assign("dos1", "value", $vector[0]['conteo_1']);
        $objResponse->assign("tres1", "value", $vector[0]['conteo_2']);
        ///////datos//////////
        $objResponse->assign("toma_fisica_id_h1_m3", "value", $vector[0]['toma_fisica_id']);
        $objResponse->assign("etiqueta_h1_m3", "value", $vector[0]['etiqueta']);
        $objResponse->assign("num_conteo_h1_m3", "value", '3');
        $objResponse->assign("sw_manual_h1_m3", "value", '1');
        $objResponse->assign("empresa_id_h1_m3", "value", $vector[0]['empresa_id']);
        $objResponse->assign("centro_utilidad_h1_m3", "value", $vector[0]['centro_utilidad']);
        $objResponse->assign("bodega_h1_m3", "value", $vector[0]['bodega']);
        $objResponse->assign("codigo_producto_h1_m3", "value", $vector[0]['codigo_producto']);
        $objResponse->assign("existencia_h1_m3", "value", $vector[0]['existencia']);
        $objResponse->assign("costo_h1_m3", "value", $vector[0]['costo']);
        $objResponse->assign("lote_h1_m3", "value", $vector[0]['lote']);
        $objResponse->assign("fecha_vencimiento_h1_m3", "value", $vector[0]['fecha_vencimiento']);
    }
    return $objResponse;
}

/* * *******************************************************************
 * funxcion para cuadrar no cuadrados conteo 1
 * ******************************************************************** */

function SetCuadrarPro($tr, $toma_fisica_id, $etiqueta, $num_conteo, $sw_manual, $empresa_id, $centro_utilidad, $bodega, $codigo_producto, $existencia, $nueva_existencia, $costo, $lote, $fecha_vencimiento) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $vector = $consulta->AddCuadrar($toma_fisica_id, $etiqueta, $num_conteo, $sw_manual, $empresa_id, $centro_utilidad, $bodega, $codigo_producto, $existencia, $nueva_existencia, $costo, $lote, $fecha_vencimiento);

    if (!empty($vector)) {
        $objResponse->alert($vector);
        $objResponse->remove($tr);
        $objResponse->call("Cerrarno");
        //$objResponse->assign("errorAj","innerHTML",$vector);
        //$objResponse->assign("conteo1x","innerHTML",$vector[0]['diferencia_1']);
    }

    return $objResponse;
}

/* * *********************
 * div del no cuadre1
 * *********************** */

function CuadrarPro($tr, $toma, $etiqueta) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $vector = $consulta->GetNoCuadre($toma, $etiqueta);
    //var_dump($vector);
    //$nn=$vector[0]['existencia']; 
    //$objResponse->alert($nn);

    if (!empty($vector)) {
//       toma_fisica_id  etiqueta  codigo_producto
//       descripcion unidad_id descripcion_unidad
//       existencia  conteo_1  validacion_conteo_1
//       diferencia_1  conteo_2  validacion_conteo_2
//       diferencia_2  conteo_3  validacion_conteo_3 diferencia_3
        $objResponse->assign("uno", "checked", "true");
        $objResponse->assign("nueva_existencia", "value", "");
        $objResponse->assign("dife", "innerHTML", "");
        $objResponse->assign("toma_fisica", "innerHTML", $vector[0]['toma_fisica_id']);
        $objResponse->assign("etiquetaxy", "innerHTML", $vector[0]['etiqueta']);
        $objResponse->assign("etiquetaGral", "innerHTML", $vector[0]['etiqueta_x_producto']);
        $objResponse->assign("desc", "innerHTML", $vector[0]['descripcion']);
        $objResponse->assign("unidad", "innerHTML", $vector[0]['descripcion_unidad']);
        $objResponse->assign("exist", "innerHTML", FormatoValor($vector[0]['existencia']));
        $objResponse->assign("conteo1x", "innerHTML", FormatoValor($vector[0]['conteo_1']));
        $objResponse->assign("conteo1xdif1", "innerHTML", "<a title='DIFERENCIA CON EXISTENCIA'>&#60;" . FormatoValor($vector[0]['diferencia_1']) . "&#62;</a>");
        $objResponse->assign("tr_h", "value", $tr);
        $objResponse->assign("uno", "value", $vector[0]['existencia']);
        $objResponse->assign("dos", "value", $vector[0]['conteo_1']);

        ///////datos//////////
        $objResponse->assign("toma_fisica_id_h", "value", $vector[0]['toma_fisica_id']);
        $objResponse->assign("etiqueta_h", "value", $vector[0]['etiqueta']);
        $objResponse->assign("num_conteo_h", "value", '1');
        $objResponse->assign("sw_manual_h", "value", '1');
        $objResponse->assign("empresa_id_h", "value", $vector[0]['empresa_id']);
        $objResponse->assign("centro_utilidad_h", "value", $vector[0]['centro_utilidad']);
        $objResponse->assign("bodega_h", "value", $vector[0]['bodega']);
        $objResponse->assign("codigo_producto_h", "value", $vector[0]['codigo_producto']);
        $objResponse->assign("existencia_h", "value", $vector[0]['existencia']);
        $objResponse->assign("costo_h", "value", $vector[0]['costo']);
        $objResponse->assign("lote_h", "value", $vector[0]['lote']);
        $objResponse->assign("fecha_vencimiento_h", "value", $vector[0]['fecha_vencimiento']);
    }

    return $objResponse;
}

/* * **********************************
 * no cuadra conteo 3
 * ************************************ */

function NoCuadraConteo3($toma, $Formulario, $numero_conteos, $offset) {

    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $pghtml = AutoCarga::factory('ClaseHTML');
    $vector = $consulta->SacarNoCuadroC3($toma, $numero_conteos, $Formulario['buscador'], $offset);
    $ConteoSICuadre = ModuloGetVar('app', 'InvTomaFisica', 'CuadrarConteo_' . $vector[0]['empresa_id']);
    //var_dump($vector); 
    //print_r($vector);
    if (!empty($vector)) {
        /* $salida .= "<table width=\"100%\" BORDER='0' align=\"center\">\n";
          $salida .= " <tr>\n";
          $salida .= "  <td align=\"LEFT\" width=\"100%\">\n";
          $salida .= "    <a href=\"javascript:WindowPrinter0003()\" class=\"label_error\">GENERAR REPORTE DE LOS PRODUCTOS DEL CONTEO 3  <sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS NO CUADRADOS EN CONTEO 1 \"></sub>&nbsp;</a>\n";
          $salida .= "  </td>\n";
          $salida .= " </tr>\n";
          $salida .= "</table>\n";
          $salida .= "<br>"; */
        $action['paginador'] = "SacarNoCuadraConteo3('" . $toma . "',xajax.getFormValues('FormaConteo3_NC'),'" . $numero_conteos . "'";
        $salida .= $pghtml->ObtenerPaginadoXajax($consulta->conteo, $consulta->paginaActual, $action['paginador'], null, 100);
        $salida .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
        $salida .= " <tr  class=\"modulo_table_list_title\">\n";
        $salida .= "  <td rowspan='2' align=\"center\" width=\"2%\">\n";
        $salida .= "   <a title='ETIQUETA'>";
        $salida .= "     ET";
        $salida .= "   </a>";
        $salida .= "  </td>\n"; /*
          $salida .= "  <td rowspan='2' align=\"center\"width=\"8%\">\n";
          $salida .= "    <a title='CODIGO DEL PRODUCTO'>";
          $salida .= "      CODIGO";
          $salida .= "    </a>";
          $salida .= "   </td>\n"; */
        $salida .= "   <td rowspan='2' align=\"center\" width=\"22%\">\n";
        $salida .= "    <a title='DESCRIPCION PRODUCTO'>PRODUCTO</a>";
        $salida .= "   </td>\n";
        $salida .= "   <td rowspan='2' align=\"center\" width=\"9%\">\n";
        $salida .= "     <a title='FECHA DE VECIMIENTO'>FECHA VENCIMIENTO</a>";
        $salida .= "   </td>\n";
        $salida .= "   <td rowspan='2' align=\"center\" width=\"9%\">\n";
        $salida .= "     <a title='LOTE'>LOTE</a>";
        $salida .= "   </td>\n"; /*
          $salida .= "   <td rowspan='2' align=\"center\" width=\"7%\">\n";
          $salida .= "     <a title='UNIDAD DEL PRODUCTO'>UNIDAD</a>";
          $salida .= "   </td>\n"; */
        $salida .= "   <td rowspan='2' align=\"center\" width=\"10%\">\n";
        $salida .= "     COSTO";
        $salida .= "   </td>\n";
        $salida .= "   <td rowspan='2' align=\"center\" width=\"9%\">\n";
        $salida .= "    <a title='EXISTENCIA DEL PRODUCTO'>EXISTENCIA</a>";
        $salida .= "   </td>\n";
        $salida .= "   <td rowspan='2' align=\"center\" width=\"9%\">\n";
        $salida .= "    <a title='CONTEO 1'>CONTEO 1</a>";
        $salida .= "   </td>\n";
        $salida .= "   <td rowspan='2' align=\"center\" width=\"9%\">\n";
        $salida .= "     <a title='CONTEO 2'>CONTEO 2</a>";
        $salida .= "   </td>\n";
        $salida .= "   <td rowspan='2' align=\"center\" width=\"9%\">\n";
        $salida .= "     <a title='CONTEO 3'>CONTEO 3</a>";
        $salida .= "   </td>\n";
        $salida .= "   <td colspan='3' align=\"center\" width=\"18%\">\n";
        $salida .= "      <a title='DIFERENCIA'>DIFERENCIA</a>";
        $salida .= "   </td>\n";
        $salida .= "   <td rowspan='2'align=\"center\" width=\"2%\">\n";
        $salida .= "       <a title='VALIDACION CONTEO 3'>V</a>";
        $salida .= "    </td>\n";
        $salida .= "    <td rowspan='2'align=\"center\" width=\"2%\">\n";
        $salida .= "      <a title='MODIFICACION CONTEO 2'>M</a>";
        $salida .= "    </td>\n";
        if ($ConteoSICuadre == '4' OR $ConteoSICuadre == '1' OR $ConteoSICuadre == '6') {
            $salida .= "   <td rowspan='2'align=\"center\" width=\"2%\">\n";
            $salida .= "      <a title='AJUSTAR CONTEO 3'>A</a>";
            $salida .= "  </td>\n";
        } else {
            $salida .= "  &nbsp;";
        }
        $salida .= "  </tr>\n";
        $salida .= "  <tr  class=\"modulo_table_list_title\">\n";
        $salida .= "   <td  align=\"center\">\n";
        $salida .= "    <a title='DIFERENCIA EXISTENCIA - CONTEO 3'>(E-C3)</a>";
        $salida .= "   </td>\n";
        $salida .= "   <td  align=\"center\">\n";
        $salida .= "    <a title='DIFERENCIA CONTEO 1 - CONTEO 3'>(C1-C3)</a>";
        $salida .= "   </td>\n";
        $salida .= "   <td  align=\"center\">\n";
        $salida .= "    <a title='DIFERENCIA CONTEO 2 - CONTEO 3'>(C2-C3)</a>";
        $salida .= "   </td>\n";
        $salida .= " </tr>\n";

        for ($i = 0; $i < count($vector); $i++) {
            $tr = "nocuadre3" . $i;
            /*  if($vector[$i]['conteo_2']!=$vector[$i]['conteo_1'])
              { */
            $salida .= " <tr id='" . $tr . "' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
            $salida .= "   <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "      <a title='ETIQUETA'>";
            $salida .= "        " . $vector[$i]['etiqueta_x_producto'] . "";
            $salida .= "      </a>";
            $salida .= "   </td>\n"; /*
              $salida .= "   <td align=\"left\">\n";
              $salida .= "     <a title='CODIGO DEL PRODUCTO'>";
              $salida .= "       ".$vector[$i]['codigo_producto'];
              $salida .= "     </a>";
              $salida .= "   </td>\n"; */
            $salida .= "   <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "     <a title=\"" . $vector[$i]['descripcion'] . "\">";
            $salida .= "			" . $vector[$i]['codigo_producto'] . "-" . substr($vector[$i]['descripcion'], 0, 18);
            /* $salida .= "       ".substr($vector[$i]['descripcion'],0,18); */
            /* $salida .= "       ".$vector[$i]['descripcion']; */
            $salida .= "     </a>";
            $salida .= "   </td>\n";
            $salida .= "   <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "     <a title=\"" . $vector[$i]['fecha_vencimiento'] . "\">";
            $salida .= "       " . $vector[$i]['fecha_vencimiento'];
            $salida .= "     </a>";
            $salida .= "   </td>\n";
            $salida .= "   <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "    <a title=\"" . $vector[$i]['lote'] . "\">";
            $salida .= "      " . $vector[$i]['lote'];
            $salida .= "    </a>";
            $salida .= "   </td>\n"; /*
              $salida .= "   <td align=\"left\" class=\"normal_10AN\">\n";
              $salida .= "    <a title='UNIDAD'>";
              $salida .= "       ".$vector[$i]['descripcion_unidad'];
              $salida .= "    </a>";
              $salida .= "   </td>\n"; */
            $salida .= "   <td align=\"right\">\n";
            $salida .= "    <a title='COSTO'>";
            $salida .= "      " . $vector[$i]['costo'];
            $salida .= "    </a>";
            $salida .= "   </td>\n";
            $salida .= "   <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "     <a title='EXISTENCIA'>";
            $salida .= "       " . $vector[$i]['existencia'] . "";
            $salida .= "    </a>";
            $salida .= "   </td>\n";
            $salida .= "   <td align=\"right\">\n";
            $salida .= "    <a title='CONTEO 1'>";
            $salida .= "      " . $vector[$i]['conteo_1'] . "";
            $salida .= "    </a>";
            $salida .= "   </td>\n";
            $salida .= "   <td align=\"right\">\n";
            $salida .= "    <a title='CONTEO 2'>";
            $salida .= "      " . $vector[$i]['conteo_2'] . "";
            $salida .= "    </a>";
            $salida .= "   </td>\n";
            $salida .= "   <td align=\"right\">\n";
            $salida .= "    <a title='CONTEO 3'>";
            $salida .= "      " . $vector[$i]['conteo_3'] . "";
            $salida .= "    </a>";
            $salida .= "   </td>\n";
            $salida .= "   <td align=\"right\">\n";
            $salida .= "    <a title='DIFERENCIA ENTRE (EXISTENCIA) Y (CONTEO 3)'>";
            if ($vector[$i]['diferencia_3'] > 0 || $vector[$i]['diferencia_3'] < 0) {/* str_replace("-","",$vector[$i]['diferencia_3']) */
                $salida .= "                      <label class='label_error'>" . FormatoValor(str_replace("-", "", $vector[$i]['diferencia_3'])) . "</label>";
            } else {
                $salida .= "                      <label class='normal_10AN'>" . FormatoValor($vector[$i]['diferencia_3']) . "</label>";
            }
            $salida .= "     </a>";
            $salida .= "   </td>\n";
            $salida .= "   <td align=\"right\">\n";
            $salida .= "    <a title='DIFERENCIA CONTEO 1 - CONTEO 3'>";
            if ($vector[$i]['diferencia_1con3'] > 0 || $vector[$i]['diferencia_1con3'] < 0) {/* str_replace("-","",$vector[$i]['diferencia_3']) */
                $salida .= "                      <label class='label_error'>" . FormatoValor(str_replace("-", "", $vector[$i]['diferencia_1con3'])) . "</label>";
            } else {
                $salida .= "                      <label class='normal_10AN'>" . FormatoValor($vector[$i]['diferencia_1con3']) . "</label>";
            }
            $salida .= "   </a>";
            $salida .= "  </td>\n";
            $salida .= "  <td align=\"right\">\n";
            $salida .= "    <a title='DIFERENCIA CONTEO 2 - CONTEO 3'>";
            if ($vector[$i]['diferencia_2con3'] > 0 || $vector[$i]['diferencia_2con3'] < 0) {/* str_replace("-","",$vector[$i]['diferencia_3']) */
                $salida .= "                      <label class='label_error'>" . FormatoValor(str_replace("-", "", $vector[$i]['diferencia_2con3'])) . "</label>";
            } else {
                $salida .= "                      <label class='normal_10AN'>" . FormatoValor($vector[$i]['diferencia_2con3']) . "</label>";
            }
            $salida .= "   </a>";
            $salida .= " </td>\n";
            $salida .= " <td align=\"right\" class=\"normal_10AN\">\n";
            if ($vector[$i]['validacion_conteo_3'] == '1') {
                $salida .= "                         <a title='PRODUCTO VALIDADO'>\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
            } else {
                $salida .= "                         <a title='PRODUCTO SIN VALIDADO'>\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
            }
            $salida .= "   </td>\n";
            $salida .= "   <td align=\"right\" class=\"normal_10AN\">\n";
            $javadx1 = "   javascript:MostrarCapa('ContenedorModificarC3'); ModificarC3('" . $tr . "','" . $toma . "','" . $vector[$i]['etiqueta'] . "');IniciarModificarC3('MODIFICAR CONTEO');";
            $salida .= "      <a title='MODIFICAR CONTEO' class=\"label_error\" href=\"" . $javadx1 . "\">\n";
            $salida .= "      <sub><img src=\"" . $path . "/images/psemanas.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
            $salida .= "      </a>\n";
            $salida .= "   </td>\n";
            $salida .= "   <td align=\"right\" class=\"normal_10AN\">\n";
            if ($ConteoSICuadre == '4' OR $ConteoSICuadre == '1' OR $ConteoSICuadre == '6') {
                if ($vector[$i]['validacion_conteo_3'] == '1') {
                    $javadx = "javascript:MostrarCapa('ContenedorAj2'); Cuadrar2('" . $tr . "','" . $toma . "','" . $vector[$i]['etiqueta'] . "');IniciarAj2('AJUSTAR CONTEO');";
                    $salida .= "<a title='AJUSTAR CONTEO' class=\"label_error\" href=\"" . $javadx . "\">\n";
                    $salida .= "<sub><img src=\"" . $path . "/images/psemanas.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $salida .= "</a>\n";
                } else {
                    $salida .= "                        &nbsp;";
                }
            } else {
                $salida .= "                        &nbsp;";
            }
            $salida .= "                      </td>\n";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            /* } */
        }
        $salida .= "                </table>\n";

        //$Cont=$consulta->ContarSC();
        //$malo=$Cont[0]['count'];
        /* $salida .= "".ObtenerPaginadoPro($path,$Cont,'1',$toma_fisica,$tip_bus,$criterio,$offset); */
        /* $salida .= "<table width=\"100%\" BORDER='0' align=\"center\">\n";
          $salida .= " <tr>\n";
          $salida .= "  <td align=\"center\" width=\"100%\">\n";
          $salida .= "    <a href=\"javascript:ConfirmarCuadre()\" class=\"label_error\">CUADRE AUTOMATICO</a>\n";
          $salida .= "  </td>\n";
          $salida .= " </tr>\n";
          $salida .= "</table>\n"; */
        $salida .= "<br>";
    } else {
        $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .="                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
    }

    $salida = $objResponse->setTildes($salida);
    $objResponse->assign("InfoSinConteo3", "innerHTML", $salida);
    return $objResponse;
}

function CuadreAutomatico() {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $automatico = $consulta->AjustaraAutomaticamenteCONTEO3($_REQUEST['toma_id']);
    //print_r($vector);
    $objResponse->script("xajax_NoCuadraConteo3('" . $_REQUEST['toma_id'] . "');");
    return $objResponse;
}

/* * *******************************
 * no cuadrados conteo 2
 * ******************************* */

function NoCuadraConteo2($toma, $Formulario, $numero_conteos, $offset) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $pghtml = AutoCarga::factory('ClaseHTML');
    $vector = $consulta->SacarNoCuadroC2($toma, $numero_conteos, $Formulario['buscador'], $offset);
    $ConteoSICuadre = ModuloGetVar('app', 'InvTomaFisica', 'CuadrarConteo_' . $vector[0]['empresa_id']);
    //var_dump($vector); 
    if (!empty($vector)) {
        /* $salida .= "                  <table width=\"100%\" BORDER='0' align=\"center\">\n";
          $salida .= "                    <tr>\n";
          $salida .= "                      <td align=\"LEFT\" width=\"100%\">\n";
          $salida .= "                        <a href=\"javascript:WindowPrinter0002()\" class=\"label_error\">GENERAR REPORTE DE LOS PRODUCTOS DEL CONTEO 2  <sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS NO CUADRADOS EN CONTEO 2 \"></sub>&nbsp;</a>\n";
          $salida .= "                      </td>\n";
          $salida .= "                    </tr>\n";
          $salida .= "                  </table>\n";
          $salida .= "<br>"; */
        $action['paginador'] = "SacarNoCuadraConteo2('" . $toma . "',xajax.getFormValues('FormaConteo2_NC'),'" . $numero_conteos . "'";
        $salida .= $pghtml->ObtenerPaginadoXajax($consulta->conteo, $consulta->paginaActual, $action['paginador'], null, 100);
        $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
        $salida .= "                    <tr  class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"2%\">\n";
        $salida .= "                       <a title='ETIQUETA'>";
        $salida .= "                        ET";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n"; /*
          $salida .= "                      <td rowspan='2' align=\"center\"width=\"10%\">\n";
          $salida .= "                       <a title='CODIGO DEL PRODUCTO'>";
          $salida .= "                        CODIGO";
          $salida .= "                       </a>";
          $salida .= "                      </td>\n"; */
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"41%\">\n";
        $salida .= "                        <a title='DESCRIPCION PRODUCTO'>PRODUCTO</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"9%\">\n";
        $salida .= "                        <a title='FECHA DE VECIMIENTO'>FECHA VENCIMIENTO</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"9%\">\n";
        $salida .= "                        <a title='LOTE'>LOTE</a>";
        $salida .= "                      </td>\n"; /*
          $salida .= "                      <td rowspan='2' align=\"center\" width=\"9%\">\n";
          $salida .= "                        <a title='UNIDAD DEL PRODUCTO'>UNIDAD</a>";
          $salida .= "                      </td>\n"; */
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"8%\">\n";
        $salida .= "                        COSTO";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"8%\">\n";
        $salida .= "                        <a title='EXISTENCIA DEL PRODUCTO'>EXISTENCIA</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"8%\">\n";
        $salida .= "                        <a title='CONTEO 1'>CONTEO 1</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"8%\">\n";
        $salida .= "                        <a title='CONTEO 2'>CONTEO 2</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td colspan='3' align=\"center\" width=\"21%\">\n";
        $salida .= "                        <a title='DIFERENCIA'>DIFERENCIA</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2'align=\"center\" width=\"2%\">\n";
        $salida .= "                        <a title='VALIDACION CONTEO 2'>V</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2'align=\"center\" width=\"2%\">\n";
        $salida .= "                        <a title='MODIFICACION CONTEO 2'>M</a>";
        $salida .= "                      </td>\n";
        if ($ConteoSICuadre == '3' OR $ConteoSICuadre == '1' OR $ConteoSICuadre == '5' OR $ConteoSICuadre == '6') {
            $salida .= "                      <td rowspan='2'align=\"center\" width=\"2%\">\n";
            $salida .= "                        <a title='AJUSTAR CONTEO 2'>A</a>";
            $salida .= "                      </td>\n";
        } else {
            $salida .= "                        &nbsp;";
        }
        $salida .= "                     </tr>\n";
        $salida .= "                    <tr  class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td  align=\"center\">\n";
        $salida .= "                        <a title='DIFERENCIA EXISTENCIA - CONTEO 1'>(E-C1)</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td  align=\"center\">\n";
        $salida .= "                        <a title='DIFERENCIA EXISTENCIA - CONTEO 2'>(E-C2)</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td  align=\"center\">\n";
        $salida .= "                        <a title='DIFERENCIA CONTEO 1 - CONTEO 2'>(C1-C2)</a>";
        $salida .= "                      </td>\n";
        $salida .= "                     </tr>\n";

        for ($i = 0; $i < count($vector); $i++) {
            $tr = "nocuadre2" . $i;
            //$salida .= "<pre>".print_r($vector,true)."</pre>";
            //if($vector[$i]['conteo_2']==$vector[$i]['conteo_1'])
            // $ajustarC1yC2=$consulta->AjustaraAutomaticamenteC1YC2($toma,$vector[$i]['codigo_producto'],$vector[$i]['fecha_vencimiento'],$vector[$i]['lote'],$vector[$i]['conteo_2']);
            $salida .= "                    <tr id='" . $tr . "' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"ETIQUETA\">";
            $salida .= "                     " . $vector[$i]['etiqueta_x_producto'] . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n"; /*
              $salida .= "                      <td align=\"left\">\n";
              $salida .= "                       <a title=\"CODIGO DEL PRODUCTO\">";
              $salida .= "                        ".$vector[$i]['codigo_producto'];
              $salida .= "                       </a>";
              $salida .= "                      </td>\n"; */
            $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
            $salida .= "                        <a title=\"" . $vector[$i]['descripcion'] . "\">";
            $salida .= "                        " . $vector[$i]['codigo_producto'] . "-" . substr($vector[$i]['descripcion'], 0, 30);
            /* $salida .= "                        ".substr($vector[$i]['descripcion'],0,18); */
            $salida .= "                        </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                        <a title=\"" . $vector[$i]['fecha_vencimiento'] . "\">";
            $salida .= "                        " . $vector[$i]['fecha_vencimiento'];
            $salida .= "                        </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                        <a title=\"" . $vector[$i]['lote'] . "\">";
            $salida .= "                        " . $vector[$i]['lote'];
            $salida .= "                        </a>";
            $salida .= "                      </td>\n"; /*
              $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
              $salida .= "                       <a title=\"UNIDAD\">";
              $salida .= "                        ".$vector[$i]['descripcion_unidad'];
              $salida .= "                       </a>";
              $salida .= "                      </td>\n"; */
            $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
            $salida .= "                       <a title=\"COSTO\">";
            $salida .= "                         " . $vector[$i]['costo'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"EXISTENCIA\">";
            $salida .= "                      " . FormatoValor($vector[$i]['existencia']) . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"CONTEO 1\">";
            $salida .= "                      " . FormatoValor($vector[$i]['conteo_1']) . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"CONTEO 2\">";
            $salida .= "                      " . FormatoValor($vector[$i]['conteo_2']) . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"DIFERENCIA ENTRE (EXISTENCIA) Y (CONTEO 1)\">";
            if ($vector[$i]['diferencia_1'] > 0 || $vector[$i]['diferencia_1'] < 0) {/* str_replace("-","",$vector[$i]['diferencia_3']) */
                $salida .= "                      <label class='label_error'>" . FormatoValor(str_replace("-", "", $vector[$i]['diferencia_1'])) . "</label>";
            } else {
                $salida .= "                      <label class='normal_10AN'>" . FormatoValor($vector[$i]['diferencia_1']) . "</label>";
            }
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"DIFERENCIA ENTRE (EXISTENCIA) Y (CONTEO 2)\">";
            if ($vector[$i]['diferencia_2'] > 0 || $vector[$i]['diferencia_2'] < 0) {/* str_replace("-","",$vector[$i]['diferencia_3']) */
                $salida .= "                      <label class='label_error'>" . FormatoValor(str_replace("-", "", $vector[$i]['diferencia_2'])) . "</label>";
            } else {
                $salida .= "                      <label class='normal_10AN'>" . FormatoValor($vector[$i]['diferencia_2']) . "</label>";
            }
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"DIFERENCIA ENTRE (CONTEO 1) Y (CONTEO 2)\">";
            if ($vector[$i]['diferencia_1con2'] > 0 || $vector[$i]['diferencia_1con2'] < 0) {/* str_replace("-","",$vector[$i]['diferencia_3']) */
                $salida .= "                      <label class='label_error'>" . FormatoValor(str_replace("-", "", $vector[$i]['diferencia_1con2'])) . "</label>";
            } else {
                $salida .= "                      <label class='normal_10AN'>" . FormatoValor($vector[$i]['diferencia_1con2']) . "</label>";
            }

            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            if ($vector[$i]['validacion_conteo_2'] == '1') {
                $salida .= "                         <a title='PRODUCTO VALIDADO'>\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
            } else {
                $salida .= "                         <a title='PRODUCTO SIN VALIDADO'>\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
            }
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $javadx = "javascript:MostrarCapa('ContenedorModificarC1C2'); ModificarC2('" . $tr . "','" . $toma . "','" . $vector[$i]['etiqueta'] . "');IniciarModificarC1C2('MODIFICAR CONTEO');";
            $salida .= "<a title='MODIFICAR CONTEO' class=\"label_error\" href=\"" . $javadx . "\">\n";
            $salida .= "<sub><img src=\"" . $path . "/images/psemanas.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
            $salida .= "</a>\n";
            $salida .= "                      </td>\n";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            if ($ConteoSICuadre == '3' OR $ConteoSICuadre == '1' OR $ConteoSICuadre == '5' OR $ConteoSICuadre == '6') {
                if ($vector[$i]['validacion_conteo_2'] == '1') {
                    $javadx = "javascript:MostrarCapa('ContenedorAj1'); Cuadrar1('" . $tr . "','" . $toma . "','" . $vector[$i]['etiqueta'] . "');IniciarAj1('AJUSTAR CONTEO');";

                    $salida .= "<a title='AJUSTAR CONTEO' class=\"label_error\" href=\"" . $javadx . "\">\n";
                    $salida .= "<sub><img src=\"" . $path . "/images/psemanas.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $salida .= "</a>\n";
                } else {
                    $salida .= "                        &nbsp;";
                }
            } else {
                $salida .= "                        &nbsp;";
            }
            $salida .= "                      </td>\n";

            $salida .= "                    </tr>\n";
        }
        $salida .= "                </table>\n";

        //$Cont=$consulta->ContarSC();
        //$malo=$Cont[0]['count'];
        $salida .= "" . ObtenerPaginadoPro($path, $Cont, '1', $toma_fisica, $tip_bus, $criterio, $offset);
    } else {
        $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .="                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
    }
    $salida = $objResponse->setTildes($salida);
    $objResponse->assign("InfoSinConteo2", "innerHTML", $salida);
    return $objResponse;
}

function SistemaVsTomaFisica($toma) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $vector = $consulta->SacarNoCuadroC2($toma);
    //var_dump($vector); 
    if (!empty($vector)) {
        $salida .= "                  <table width=\"100%\" BORDER='0' align=\"center\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"LEFT\" width=\"100%\">\n";
        $salida .= "                        <a href=\"javascript:WindowPrinter0002()\" class=\"label_error\">GENERAR REPORTE DE LOS PRODUCTOS NO CUADRADOS EN CONTEO 2  <sub><img src=\"" . GetThemePath() . "/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS NO CUADRADOS EN CONTEO 2 \"></sub>&nbsp;</a>\n";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                  </table>\n";
        $salida .= "<br>";
    } else {
        $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .="                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
    }
    $salida = $objResponse->setTildes($salida);
    $objResponse->assign("SistemaVsTomaFisica", "innerHTML", $salida);
    return $objResponse;
}

/* * *********************************
 * no cuadrados conteo 1
 * ******************************** */

function NoCuadraConteo1($toma, $Formulario, $numero_conteos, $offset) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $pghtml = AutoCarga::factory('ClaseHTML');
    $vector = $consulta->SacarNoCuadroC1($toma, $numero_conteos, $Formulario['buscador'], $offset);
    //print_r($_REQUEST);
    $ConteoSICuadre = ModuloGetVar('app', 'InvTomaFisica', 'CuadrarConteo_' . $vector[0]['empresa_id']);
    //print_r($ConteoSICuadre."HELLO");
    if (!empty($vector)) {
        $action['paginador'] = "SacarNoCuadraConteo1('" . $toma . "',xajax.getFormValues('FormaConteo1_NC'),'" . $numero_conteos . "'";
        $salida .= $pghtml->ObtenerPaginadoXajax($consulta->conteo, $consulta->paginaActual, $action['paginador'], null, 100);
        $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
        $salida .= "                    <tr  class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td align=\"center\" width=\"2%\">\n";
        $salida .= "                       <a title='ETIQUETA'>";
        $salida .= "                        ET";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n"; /*
          $salida .= "                      <td align=\"center\"width=\"12%\">\n";
          $salida .= "                       <a title='CODIGO DEL PRODUCTO'>";
          $salida .= "                        CODIGO";
          $salida .= "                       </a>";
          $salida .= "                      </td>\n"; */
        $salida .= "                      <td  align=\"center\" width=\"51%\">\n";
        $salida .= "                        <a title='DESCRIPCION PRODUCTO'>PRODUCTO</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td  align=\"center\" width=\"9%\">\n";
        $salida .= "                        <a title='FECHA DE VECIMIENTO'>FECHA VENCIMIENTO</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td  align=\"center\" width=\"9%\">\n";
        $salida .= "                        <a title='LOTE'>LOTE</a>";
        $salida .= "                      </td>\n"; /*
          $salida .= "                      <td  align=\"center\" width=\"9%\">\n";
          $salida .= "                        <a title='UNIDAD DEL PRODUCTO'>UNIDAD</a>";
          $salida .= "                      </td>\n"; */
        $salida .= "                      <td  align=\"center\" width=\"8%\">\n";
        $salida .= "                        COSTO";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"8%\">\n";
        $salida .= "                        <a title='EXISTENCIA DEL PRODUCTO'>EXISTENCIA</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"8%\">\n";
        $salida .= "                        <a title='CONTEO 1'>CONTEO 1</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"8%\">\n";
        $salida .= "                        <a title='DIFERENCIA EXISTENCIA - CONTEO1'>E-C1</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"2%\">\n";
        $salida .= "                        <a title='VALIDACION CONTEO'>V</a>";
        $salida .= "                      </td>\n";
        if ($ConteoSICuadre == '2' OR $ConteoSICuadre == '1' OR $ConteoSICuadre == '5') {
            $salida .= "                      <td align=\"center\" width=\"2%\">\n";
            $salida .= "                        <a title='AJUSTAR CONTEO'>A</a>";
            $salida .= "                      </td>\n";
        } else {
            $salida .= "                      <td align=\"center\" width=\"2%\">\n";
            $salida .= "                      </td>\n";
        }
        $salida .= "                     </tr>\n";


        for ($i = 0; $i < count($vector); $i++) {
            $tr = "nocuadre1" . $i;
            $salida .= "                    <tr id='" . $tr . "' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"ETIQUETA\">";
            $salida .= "                         " . $vector[$i]['etiqueta_x_producto'] . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n"; /*
              $salida .= "                      <td align=\"left\">\n";
              $salida .= "                       <a title=\"CODIGO DEL PRODUCTO\">";
              $salida .= "                        ".$vector[$i]['codigo_producto'];
              $salida .= "                       </a>";
              $salida .= "                      </td>\n"; */
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                        <a title=\"" . $vector[$i]['descripcion'] . "\">";
            $salida .= "                        " . $vector[$i]['codigo_producto'] . "-" . substr($vector[$i]['descripcion'], 0, 50);
            $salida .= "                        </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                        <a title=\"" . $vector[$i]['fecha_vencimiento'] . "\">";
            $salida .= "                        " . $vector[$i]['fecha_vencimiento'];
            $salida .= "                        </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                        <a title=\"" . $vector[$i]['lote'] . "\">";
            $salida .= "                        " . $vector[$i]['lote'];
            $salida .= "                        </a>";
            $salida .= "                      </td>\n"; /*
              $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
              $salida .= "                       <a title=\"UNIDAD\">";
              $salida .= "                        ".$vector[$i]['descripcion_unidad'];
              $salida .= "                       </a>";
              $salida .= "                      </td>\n"; */
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"COSTO\">";
            $salida .= "                        " . $vector[$i]['costo'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"EXISTENCIA\">";
            $salida .= "                        " . $vector[$i]['existencia'] . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"CONTEO 1\">";
            $salida .= "                      " . FormatoValor($vector[$i]['conteo_1']) . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"DIFERENCIA ENTRE (EXISTENCIA) Y (CONTEO 1)\">";
            if ($vector[$i]['diferencia_1'] > 0 || $vector[$i]['diferencia_1'] < 0) {
                $salida .= "                      <label class='label_error'>" . FormatoValor(str_replace("-", "", $vector[$i]['diferencia_1'])) . "</label>";
            } else {
                $salida .= "                      <label class='normal_10AN'>" . FormatoValor($vector[$i]['diferencia_1']) . "</label>";
            }
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            if ($vector[$i]['validacion_conteo_1'] == '1') {
                $salida .= "                         <a title='PRODUCTO VALIDADO'>\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
            } else {
                $salida .= "                         <a title='PRODUCTO SIN VALIDADO'>\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
            }

            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            if ($ConteoSICuadre == '2' OR $ConteoSICuadre == '1' OR $ConteoSICuadre == '5') {
                if ($vector[$i]['validacion_conteo_1'] == '1') {
                    $javadx = "javascript:MostrarCapa('ContenedorAj'); Cuadrar('" . $tr . "','" . $toma . "','" . $vector[$i]['etiqueta'] . "');IniciarAj('AJUSTAR CONTEO');";
                    $salida .= "<a title='AJUSTAR CONTEO' class=\"label_error\" href=\"" . $javadx . "\">\n";
                    $salida .= "<sub><img src=\"" . $path . "/images/psemanas.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $salida .= "</a>\n";
                } else {
                    $salida .= "                        &nbsp;";
                }
            } else {
                $salida .= "                        &nbsp;";
            }
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
        }
        $salida .= "                </table>\n";

        //$Cont=$consulta->ContarSC();
        //$malo=$Cont[0]['count'];
        $salida .= "" . ObtenerPaginadoPro($path, $Cont, '1', $toma_fisica, $tip_bus, $criterio, $offset);
    } else {
        $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .="                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
    }
    $salida = $objResponse->setTildes($salida);
    $objResponse->assign("InfoSinConteo1", "innerHTML", $salida);
    return $objResponse;
}

/* * ************************
 * *FUNCION SIN CONTEO
 * ************************ */

function InfoSinConteo($toma, $Formulario, $offset) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $pghtml = AutoCarga::factory('ClaseHTML');
    $vector = $consulta->SacarSinConteo($toma, $Formulario['buscador'], $offset);
    //    var_dump($vector);
    $salida = "";
    if (!empty($vector)) {
        $action['paginador'] = "SacarSinConteo('" . $toma . "',xajax.getFormValues('FormaBuscarSinConteo')";
        $salida .= $pghtml->ObtenerPaginadoXajax($consulta->conteo, $consulta->paginaActual, $action['paginador'], null, 100);
        $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
        $salida .= "                    <tr  class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td align=\"center\" width=\"5%\">\n";
        $salida .= "                       <a title='ETIQUETA'>";
        $salida .= "                        ET";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n"; /*
          $salida .= "                      <td align=\"center\"width=\"10%\">\n";
          $salida .= "                       <a title='CODIGO DEL PRODUCTO'>";
          $salida .= "                        CODIGO";
          $salida .= "                       </a>";
          $salida .= "                      </td>\n"; */
        $salida .= "                      <td  align=\"center\" width=\"60%\">\n";
        $salida .= "                        <a title='DESCRIPCION PRODUCTO'>PRODUCTO</a>";
        $salida .= "                      </td>\n"; /*
          $salida .= "                      <td  align=\"center\" width=\"10%\">\n";
          $salida .= "                        <a title='UNIDAD DEL PRODUCTO'>UNIDAD</a>";
          $salida .= "                      </td>\n"; */
        $salida .= "                      <td  align=\"center\" width=\"5%\">\n";
        $salida .= "                        <a title='FECHA VENCIMIENTO'>FECHA VENCIMIENTO</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td  align=\"center\" width=\"5%\">\n";
        $salida .= "                        <a title='LOTE'>LOTE</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td  align=\"center\" width=\"5%\">\n";
        $salida .= "                        COSTO";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"5%\">\n";
        $salida .= "                        <a title='EXISTENCIA DEL PRODUCTO'>EXISTENCIA</a>";
        $salida .= "                      </td>\n";
        $salida .= "                     </tr>\n";


        for ($i = 0; $i < count($vector); $i++) {
            $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
            $salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"ETIQUETA\" >";
            $salida .= "                     " . $vector[$i]['etiqueta'] . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            /* $salida .= "                      <td align=\"left\">\n";
              $salida .= "                       <a title=\"CODIGO DEL PRODUCTO\">";
              $salida .= "                        ".$vector[$i]['codigo_producto'];
              $salida .= "                       </a>";
              $salida .= "                      </td>\n"; */
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                        <a title=\"" . $vector[$i]['descripcion'] . "\">";
            $salida .= "                        " . $vector[$i]['codigo_producto'] . " - " . substr($vector[$i]['descripcion'], 0, 60);
            $salida .= "                        </a>";
            $salida .= "                      </td>\n"; /*
              $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
              $salida .= "                       <a title=\"UNIDAD\">";
              $salida .= "                        ".$vector[$i]['descripcion_unidad'];
              $salida .= "                       </a>";
              $salida .= "                      </td>\n"; */
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"FECHA VENCIMIENTO\">";
            $salida .= "                        " . $vector[$i]['fecha_vencimiento'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"LOTE\">";
            $salida .= "                        " . $vector[$i]['lote'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"COSTO\">";
            $salida .= "                         " . $vector[$i]['costo'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"EXISTENCIA\">";
            $salida .= "                      " . $vector[$i]['existencia'] . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
        }
        $salida .= "                </table>\n";

        //$Cont=$consulta->ContarSC();
        //$malo=$Cont[0]['count'];
        /* $salida .= "".ObtenerPaginadoPro($path,$Cont,'1',$toma_fisica,$tip_bus,$criterio,$offset); */
    } else {
        $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .="                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
    }

    //$salida = $objResponse->setTildes($salida);
    //$salida= ereg_replace("#", "&#35", $salida);


    $objResponse->assign("SinConteox", "innerHTML", $salida);
    return $objResponse;
}

/* * ****************************************
 * FUNCION PARA SACAR CONTEO 3
 * ***************************************** */

function InfoConteo3x($conteo3, $toma, $Formulario, $numero_conteos, $offset) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $pghtml = AutoCarga::factory('ClaseHTML');
    $vector = $consulta->SacarInfoConteo3($conteo3, $toma, $Formulario['buscador'], $numero_conteos, $offset);
    //var_dump($vector); 
    if (!empty($vector)) {
        $action['paginador'] = "SacarConteo3('3','" . $toma . "',xajax.getFormValues('FormaConteo3_'),'" . $numero_conteos . "'";
        $salida .= $pghtml->ObtenerPaginadoXajax($consulta->conteo, $consulta->paginaActual, $action['paginador'], null, 100);
        $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
        $salida .= "                    <tr  class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"2%\">\n";
        $salida .= "                       <a title='ETIQUETA'>";
        $salida .= "                        ET";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n"; /*
          $salida .= "                      <td rowspan='2' align=\"center\"width=\"8%\">\n";
          $salida .= "                       <a title='CODIGO DEL PRODUCTO'>";
          $salida .= "                        CODIGO";
          $salida .= "                       </a>";
          $salida .= "                      </td>\n"; */
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"35%\">\n";
        $salida .= "                        <a title='DESCRIPCION PRODUCTO'>PRODUCTO</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"12%\">\n";
        $salida .= "                        <a title='FECHA DE VENCIMIENTO'>FECHA VENCIMIENTO</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2'  align=\"center\" width=\"9%\">\n";
        $salida .= "                        <a title='LOTE'>LOTE</a>";
        $salida .= "                      </td>\n"; /*
          $salida .= "                      <td rowspan='2' align=\"center\" width=\"8%\">\n";
          $salida .= "                        <a title='UNIDAD DEL PRODUCTO'>UNIDAD</a>";
          $salida .= "                      </td>\n"; */
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"7%\">\n";
        $salida .= "                        COSTO";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"7%\">\n";
        $salida .= "                        <a title='EXISTENCIA DEL PRODUCTO'>EXISTENCIA</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"7%\">\n";
        $salida .= "                        CONTEO 1";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"7%\">\n";
        $salida .= "                        CONTEO 2";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"7%\">\n";
        $salida .= "                        CONTEO 3";
        $salida .= "                      </td>\n";
        $salida .= "                      <td colspan='3' align=\"center\" width=\"22%\">\n";
        $salida .= "                              DIFERENCIAS";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"7%\">\n";
        $salida .= "                        CUADRE";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"3%\">\n";
        $salida .= "                        <a title='TIPO DE CUADRE'>T</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"3%\">\n";
        $salida .= "                        <a title='ELIMINAR CUADRE'>X</a>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td  align=\"CENTER\">\n";
        $salida .= "                        <a title='EXISTENCIA - CONTEO3'>";
        $salida .= "                        (E-C3)";
        $salida .= "                        </a>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td  align=\"CENTER\">\n";
        $salida .= "                        <a title='CONTEO1 - CONTEO3'>";
        $salida .= "                       (C1-C3)";
        $salida .= "                        </a>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td  align=\"CENTER\">\n";
        $salida .= "                        <a title='CONTEO2 - CONTEO3'>";
        $salida .= "                        (C2-C3)";
        $salida .= "                        </a>";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        for ($i = 0; $i < count($vector); $i++) {
            $tr = "tr_borrar" . $i;
            $salida .= "                    <tr id='" . $tr . "' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"ETIQUETA\">";
            $salida .= "                     " . $vector[$i]['etiqueta_x_producto'] . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n"; /*
              $salida .= "                      <td align=\"left\">\n";
              $salida .= "                       <a title=\"CODIGO DEL PRODUCTO\">";
              $salida .= "                        ".$vector[$i]['codigo_producto'];
              $salida .= "                       </a>";
              $salida .= "                      </td>\n"; */
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                        <a title='" . $vector[$i]['descripcion'] . "'>";
            $salida .= "                        " . $vector[$i]['codigo_producto'] . "-" . substr($vector[$i]['descripcion'], 0, 22);
            $salida .= "                        </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"FECHA_VENCI\">";
            $salida .= "                        " . $vector[$i]['fecha_vencimiento'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"LOTE\">";
            $salida .= "                        " . $vector[$i]['lote'];
            $salida .= "                       </a>"; /*
              $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
              $salida .= "                       <a title=\"UNIDAD\">";
              $salida .= "                        ".$vector[$i]['descripcion_unidad'];
              $salida .= "                       </a>";
              $salida .= "                      </td>\n"; */
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"COSTO\">";
            $salida .= "                         " . $vector[$i]['costo'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"EXISTENCIA\">";
            $salida .= "                     " . $vector[$i]['existencia'] . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"CONTEO 1\">";
            $salida .= "                        " . $vector[$i]['conteo_1'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"CONTEO 2\">";
            $salida .= "                        " . $vector[$i]['conteo_2'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"CONTEO 3\">";
            $salida .= "                        " . $vector[$i]['conteo_3'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"DIFERENCIA ENTRE (EXISTENCIA) Y (CONTEO 3)\">";
            if ($vector[$i]['diferencia_3'] >= 0) {
                $salida .= "                        " . $vector[$i]['diferencia_3'];
            } else {
                $salida .= "                      <label class='label_error'>" . str_replace("-", "", $vector[$i]['diferencia_3']) . "</label>";
            }
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"DIFERENCIA ENTRE (CONTEO 1) Y (CONTEO 3)\">";
            if ($vector[$i]['diferencia_1con3'] >= 0) {
                $salida .= "                        " . $vector[$i]['diferencia_1con3'];
            } else {
                $salida .= "                      <label class='label_error'>" . str_replace("-", "", $vector[$i]['diferencia_1con3']) . "</label>";
            }
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"DIFERENCIA ENTRE (CONTEO 2) Y (CONTEO 3)\">";
            if ($vector[$i]['diferencia_2con3'] >= 0) {
                $salida .= "                        " . $vector[$i]['diferencia_2con3'];
            } else {
                $salida .= "                      <label class='label_error'>" . str_replace("-", "", $vector[$i]['diferencia_2con3']) . "</label>";
            }
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"CUADRE\">";
            $salida .= "                         " . $vector[$i]['nueva_existencia'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
            if ($vector[$i]['sw_manual'] == 0) {
                $salida .= "<a title='TIPO DE CUADRE AUTOMATICO'>\n";
                $salida .= "<sub><img src=\"" . $path . "/images/pc.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
            } elseif ($vector[$i]['sw_manual'] == 1) {
                $salida .= "<a title='TIPO DE CUADRE MANUAL'>\n";
                $salida .= "<sub><img src=\"" . $path . "/images/pparacar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "</a>\n";
            }
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" onclick=\"\">\n";
            if ($vector[$i]['sw_manual'] == '1') {
                $jaxx = "javascript:MostrarCapa('ContenedorB3');BorrarAjustes('" . $tr . "','" . $vector[$i]['toma_fisica_id'] . "','" . $vector[$i]['etiqueta'] . "','ContenidoB3');IniciarB3('ELIMINAR REGISTRO');";
                $salida .= "<a title='ELIMINAR REGISTRO' href=\"" . $jaxx . "\">\n";
                $salida .= "<sub><img src=\"" . $path . "/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "</a>\n";
            } else {
                $salida .= "                         &nbsp;";
            }
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
        }
        $salida .= "                </table>\n";

        //$Cont=$consulta->ContarCuentasStip($toma_fisica,$aumento);
        //$malo=$Cont[0]['count'];
        //$salida .= "".ObtenerPaginadoPro($path,$Cont,'1',$toma_fisica,$tip_bus,$criterio,$offset);
    } else {
        $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .="                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
    }
    $objResponse->assign("InfoConteo3", "innerHTML", $salida);
    return $objResponse;
}

/* * ***************************************
 * funcion para sacar info de conteos2
 * ****************************************** */

function InfoConteo2x($conteo2, $toma, $Formulario, $numero_conteos, $offset) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $pghtml = AutoCarga::factory('ClaseHTML');
    $vector = $consulta->SacarInfoConteo($conteo2, $toma, $Formulario['buscador'], $numero_conteos, $offset);
    //var_dump($vector); 
    if (!empty($vector)) {


        //$salida .= "                 <form name=\"adicionar\">\n";         
        $action['paginador'] = "SacarConteo2('2','" . $toma . "',xajax.getFormValues('FormaConteo2_'),'" . $numero_conteos . "'";
        $salida .= $pghtml->ObtenerPaginadoXajax($consulta->conteo, $consulta->paginaActual, $action['paginador'], null, 100);
        $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
        $salida .= "                    <tr  class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"2%\">\n";
        $salida .= "                       <a title='ETIQUETA'>";
        $salida .= "                        ET";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\"width=\"37%\">\n";
        $salida .= "                       <a title='PRODUCTO'>";
        $salida .= "                        PRODUCTO";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n";
        /* $salida .= "                      <td rowspan='2' align=\"center\" width=\"21%\">\n";
          $salida .= "                        <a title='DESCRIPCION PRODUCTO'>DESCRIPCION</a>";
          $salida .= "                      </td>\n"; */
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"15%\">\n";
        $salida .= "                        <a title='FECHA DE VENCIMIENTO'>FECHA VENCIMIENTO</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2'  align=\"center\" width=\"10%\">\n";
        $salida .= "                        <a title='LOTE'>LOTE</a>";
        $salida .= "                      </td>\n"; /*
          $salida .= "                      <td rowspan='2' align=\"center\" width=\"8%\">\n";
          $salida .= "                        <a title='UNIDAD DEL PRODUCTO'>UNIDAD</a>";
          $salida .= "                      </td>\n"; */
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"7%\">\n";
        $salida .= "                        COSTO";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"7%\">\n";
        $salida .= "                        <a title='EXISTENCIA DEL PRODUCTO'>EXISTENCIA</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"7%\">\n";
        $salida .= "                        CONTEO 1";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"7%\">\n";
        $salida .= "                        CONTEO 2";
        $salida .= "                      </td>\n";
        $salida .= "                      <td colspan='3' align=\"center\" width=\"20%\">\n";
        $salida .= "                              DIFERENCIAS";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"7%\">\n";
        $salida .= "                        CUADRE";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"3%\">\n";
        $salida .= "                        <a title='TIPO DE CUADRE'>T</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td rowspan='2' align=\"center\" width=\"3%\">\n";
        $salida .= "                        <a title='ELIMINAR CUADRE'>X</a>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td  align=\"CENTER\">\n";
        $salida .= "                        <a title='EXISTENCIA - CONTEO1'>";
        $salida .= "                        (E-C1)";
        $salida .= "                        </a>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td  align=\"CENTER\">\n";
        $salida .= "                        <a title='EXISTENCIA - CONTEO2'>";
        $salida .= "                       (E-C2)";
        $salida .= "                        </a>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td  align=\"CENTER\">\n";
        $salida .= "                        <a title='CONTEO1 - CONTEO2'>";
        $salida .= "                        (C1-C2)";
        $salida .= "                        </a>";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        for ($i = 0; $i < count($vector); $i++) {
            $tr = "conte2b" . $i;
            $salida .= "                    <tr id='" . $tr . "' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"ETIQUETA\">";
            $salida .= "                     " . $vector[$i]['etiqueta_x_producto'] . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            /* $salida .= "                      <td align=\"left\">\n";
              $salida .= "                       <a title=\"CODIGO DEL PRODUCTO\">";
              $salida .= "                        ".$vector[$i]['codigo_producto'];
              $salida .= "                       </a>";
              $salida .= "                      </td>\n"; */
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                        <a title=\"" . $vector[$i]['descripcion'] . "\">";
            $salida .= "                        " . $vector[$i]['codigo_producto'] . "-" . substr($vector[$i]['descripcion'], 0, 22);
            /* $salida .= "                        ".substr($vector[$i]['descripcion'],0,25); */
            $salida .= "                        </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"FECHA_VENCI\">";
            $salida .= "                        " . $vector[$i]['fecha_vencimiento'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"LOTE\">";
            $salida .= "                        " . $vector[$i]['lote'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n"; /*
              $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
              $salida .= "                       <a title=\"UNIDAD\">";
              $salida .= "                        ".$vector[$i]['descripcion_unidad'];
              $salida .= "                       </a>";
              $salida .= "                      </td>\n"; */
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"COSTO\">";
            $salida .= "                         " . $vector[$i]['costo'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"EXISTENCIA\">";
            $salida .= "                     " . $vector[$i]['existencia'] . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"CONTEO 1\">";
            $salida .= "                        " . FormatoValor($vector[$i]['conteo_1']);
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"CONTEO 2\">";
            $salida .= "                        " . FormatoValor($vector[$i]['conteo_2']);
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"DIFERENCIA ENTRE (EXISTENCIA) Y (CONTEO 1)\">";
            if ($vector[$i]['diferencia_1'] > 0 || $vector[$i]['diferencia_1'] < 0) {
                $salida .= "                      <label class='label_error'>" . FormatoValor(str_replace("-", "", $vector[$i]['diferencia_1'])) . "</label>";
            } else {
                $salida .= "                      <label class='normal_10AN'>" . FormatoValor($vector[$i]['diferencia_1']) . "</label>";
            }
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"DIFERENCIA ENTRE (EXISTENCIA) Y (CONTEO 2)\">";
            if ($vector[$i]['diferencia_2'] > 0 || $vector[$i]['diferencia_2'] < 0) {
                $salida .= "                      <label class='label_error'>" . FormatoValor(str_replace("-", "", $vector[$i]['diferencia_2'])) . "</label>";
            } else {
                $salida .= "                      <label class='normal_10AN'>" . FormatoValor($vector[$i]['diferencia_2']) . "</label>";
            }
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"DIFERENCIA ENTRE (CONTEO 1) Y (CONTEO 2)\">";
            /* if($vector[$i]['diferencia_1con2']>=0) */
            if ($vector[$i]['diferencia_1con2'] > 0 || $vector[$i]['diferencia_1con2'] < 0) {
                $salida .= "                      <label class='label_error'>" . FormatoValor(str_replace("-", "", $vector[$i]['diferencia_1con2'])) . "</label>";
            } else {
                $salida .= "                      <label class='normal_10AN'>" . FormatoValor($vector[$i]['diferencia_1con2']) . "</label>";
            }
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"CUADRE\">";
            $salida .= "                         " . FormatoValor($vector[$i]['nueva_existencia']);
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
            if ($vector[$i]['sw_manual'] == 0) {
                $salida .= "<a title='TIPO DE CUADRE AUTOMATICO'>\n";
                $salida .= "<sub><img src=\"" . $path . "/images/pc.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
            } elseif ($vector[$i]['sw_manual'] == 1) {
                $salida .= "<a title='TIPO DE CUADRE MANUAL'>\n";
                $salida .= "<sub><img src=\"" . $path . "/images/pparacar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "</a>\n";
            }
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\">\n";
            if ($vector[$i]['sw_manual'] == '1') {
                $jaxx = "javascript:MostrarCapa('ContenedorB2');BorrarAjustes('" . $tr . "','" . $vector[$i]['toma_fisica_id'] . "','" . $vector[$i]['etiqueta'] . "','ContenidoB2');IniciarB2('ELIMINAR REGISTRO');";
                $salida .= "<a title='ELIMINAR REGISTRO' href=\"" . $jaxx . "\">\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
            } else {
                $salida .= "                         &nbsp;";
            }
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
        }
        $salida .= "                </table>\n";

        $Cont = $consulta->ContarCuentasStip($toma_fisica, $aumento);
        $malo = $Cont[0]['count'];
        $salida .= "" . ObtenerPaginadoPro($path, $Cont, '1', $toma_fisica, $tip_bus, $criterio, $offset);
    } else {
        $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .="                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
    }
    $objResponse->assign("InfoConteo2", "innerHTML", $salida);
    return $objResponse;
}

/* * ***************************************
 * funcion para sacar info de conteos
 * ****************************************** */

function InfoConteo1x($conteo1, $toma, $Formulario, $numero_conteos, $offset) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");

    $consulta = new TomaFisicaSQL();
    $pghtml = AutoCarga::factory('ClaseHTML');
    $vector = $consulta->SacarInfoConteo($conteo1, $toma, $Formulario['buscador'], $numero_conteos, $offset);
    //$objResponse->alert($vector);
    //var_dump($vector);
    if (!empty($vector)) {
        //$salida .= "                 <form name=\"adicionar\">\n";    
        $action['paginador'] = "InfoConteo1('1','" . $toma . "',xajax.getFormValues('FormaConteo1_'),'" . $numero_conteos . "'";
        $salida .= $pghtml->ObtenerPaginadoXajax($consulta->conteo, $consulta->paginaActual, $action['paginador'], null, 100);
        $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td align=\"center\" width=\"3%\">\n";
        $salida .= "                       <a title='ETIQUETA'>";
        $salida .= "                        ETIQ";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\"width=\"41%\">\n";
        $salida .= "                       <a title='PRODUCTO'>";
        $salida .= "                        PRODUCTO";
        $salida .= "                       </a>";
        $salida .= "                      </td>\n";
        /* $salida .= "                      <td align=\"center\" width=\"20%\">\n";
          $salida .= "                        <a title='DESCRIPCION PRODUCTO'>DESCRIPCION</a>";
          $salida .= "                      </td>\n"; */
        $salida .= "                      <td align=\"center\" width=\"20%\">\n";
        $salida .= "                        <a title='FECHA DE VENCIMIENTO'>FECHA VENCIMIENTO</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"20%\">\n";
        $salida .= "                        <a title='LOTE'>LOTE</a>";
        $salida .= "                      </td>\n"; /*
          $salida .= "                      <td align=\"center\" width=\"11%\">\n";
          $salida .= "                        <a title='UNIDAD DEL PRODUCTO'>UNIDAD</a>";
          $salida .= "                      </td>\n"; */
        $salida .= "                      <td align=\"center\" width=\"10%\">\n";
        $salida .= "                        COSTO";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"10%\">\n";
        $salida .= "                        <a title='EXISTENCIA DEL PRODUCTO'>EXISTENCIA</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"10%\">\n";
        $salida .= "                        CONTEO";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"10%\">\n";
        $salida .= "                              DIFERENCIAS";
        $salida .= "                              (E-C1)";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"10%\">\n";
        $salida .= "                        CUADRE";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"3%\">\n";
        $salida .= "                        <a title='TIPO DE CUADRE'>T</a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"3%\">\n";
        $salida .= "                        <a title='ELIMINAR CUADRE'>X</a>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        for ($i = 0; $i < count($vector); $i++) {
            $tr = "borrartr" . $i;
            $salida .= "                    <tr id='" . $tr . "' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"ETIQUETA\">";
            $salida .= "                         " . $vector[$i]['etiqueta_x_producto'] . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                       <a title=\"" . $vector[$i]['descripcion'] . "\">";
            $salida .= "                        " . $vector[$i]['codigo_producto'] . "-" . substr($vector[$i]['descripcion'], 0, 22);
            /* $salida .= "                        ".substr($vector[$i]['descripcion'],0,27); */

            $salida .= "                        </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"FECHA_VENCI\">";
            $salida .= "                        " . $vector[$i]['fecha_vencimiento'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"LOTE\">";
            $salida .= "                        " . $vector[$i]['lote'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n"; /*
              $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
              $salida .= "                       <a title=\"UNIDAD\">";
              $salida .= "                        ".$vector[$i]['descripcion_unidad'];
              $salida .= "                       </a>";
              $salida .= "                      </td>\n"; */
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"COSTO\">";
            $salida .= "                         " . $vector[$i]['costo'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"EXISTENCIA\">";
            $salida .= "                     " . $vector[$i]['existencia'] . "";
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                       <a title=\"CONTEO 1\">";
            $salida .= "                        " . $vector[$i]['conteo_1'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            if ($vector[$i]['diferencia_1'] > 0 || $vector[$i]['diferencia_1'] < 0) {
                $salida .= "                      <label class='label_error'>" . FormatoValor(str_replace("-", "", $vector[$i]['diferencia_1'])) . "</label>";
            } else {
                $salida .= "                      <label class='normal_10AN'>" . FormatoValor($vector[$i]['diferencia_1']) . "</label>";
            }
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <a title=\"CUADRE\">";
            $salida .= "                         " . $vector[$i]['nueva_existencia'];
            $salida .= "                       </a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
            if ($vector[$i]['sw_manual'] == 0) {
                $salida .= "<a title='TIPO DE CUADRE AUTOMATICO'>\n";
                $salida .= "<sub><img src=\"" . $path . "/images/pc.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
            } elseif ($vector[$i]['sw_manual'] == 1) {
                $salida .= "<a title='TIPO DE CUADRE MANUAL'>\n";
                $salida .= "<sub><img src=\"" . $path . "/images/pparacar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "</a>\n";
            }
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" onclick=\"\">\n";
            if ($vector[$i]['sw_manual'] == '1') {
                $jaxx = "javascript:MostrarCapa('ContenedorB1');BorrarAjustes('" . $tr . "','" . $vector[$i]['toma_fisica_id'] . "','" . $vector[$i]['etiqueta'] . "','ContenidoB1');IniciarB1('ELIMINAR REGISTRO');";
                $salida .= "<a title='ELIMINAR REGISTRO' href=\"" . $jaxx . "\">\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
            } else {
                $salida .= "                         &nbsp;";
            }
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
        }
        $salida .= "                </table>\n";
    } else {
        $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .="                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
    }
    $objResponse->assign("InfoConteo1", "innerHTML", $salida);
    return $objResponse;
}

/* * **********************************************
 * funcion pra buscar productos
 * *********************************************** */

function BuscarProducto1($toma_fisica, $tip_bus, $criterio, $offset) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();

    //$objResponse->alert($tip_bus);
    //$objResponse->alert($criterio);

    if ($tip_bus == 8) {
        //$objResponse->alert($tip_bus);
        $aumento = "AND b.codigo_producto='" . $criterio . "'";
        $aumento1 = "";
    } elseif ($tip_bus == 2) {
        //$objResponse->alert($tip_bus);
        $aumento = "AND c.descripcion LIKE '%" . strtoupper($criterio) . "%'";
        $aumento1 = "";
    } elseif ($tip_bus == 3) {
        //$objResponse->alert($tip_bus);
        $aumento = "AND b.fecha_vencimiento ='" . $criterio . "' ";
        $aumento1 = " ,b.fecha_vencimiento,b.lote ";
    } elseif ($tip_bus == 4) {
        //$objResponse->alert($tip_bus);
        $aumento = "AND b.lote LIKE '%" . ($criterio) . "%' ";
        $aumento1 = " ,b.fecha_vencimiento,b.lote";
    } elseif ($tip_bus == 5) {
        //$objResponse->alert($tip_bus);
        //$objResponse->alert($criterio);
        $aumento = "AND c.codigo_barras LIKE '%" . ($criterio) . "%' ";
        $aumento1 = " ";
    } elseif ($tip_bus == 6) {
        $aumento = "AND f.descripcion LIKE '%" . strtoupper($criterio) . "%'";
        $aumento1 = " ";
    } elseif ($tip_bus == 7) {
        $aumento = "AND c.codigo_alterno LIKE '%" . ($criterio) . "%'";
        $aumento1 = " ";
    } else {
        $aumento = "";
    }

    if ($aumento != "")
        $busqueda = $consulta->SacarPro($toma_fisica, $aumento, $aumento1, $offset);
    //$salida .= "<pre>".print_r($busqueda,true)."</pre>";
    if (!empty($busqueda)) {
        $salida .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
        //$salida .=                     $busqueda;
        $salida .= "                 </div>\n";
        $salida .= "                 <form name=\"adicionar\">\n";
        $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td align=\"center\" width=\"15%\">\n";
        $salida .= "                        ETIQUETA";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\"width=\"20%\">\n";
        $salida .= "                        CODIGO PRODUCTO";
        $salida .= "                      </td>\n";
        //$salida .= "<pre>".print_r($busqueda,true)."</pre>";
        $salida .= "                      <td align=\"center\" width=\"40%\">\n";
        $salida .= "                        <a title='DESCRIPCION PRODUCTO'>DESCRIPCION<a> ";
        $salida .= "                      </td>\n";
        /* $salida .= "                      <td align=\"center\" width=\"20%\">\n";
          $salida .= "                        <a title='FECHA DE VENCIMIENTO'>FECHA VENCIMIENTO<a> ";
          $salida .= "                      </td>\n"; */
        /* $salida .= "                      <td align=\"center\" width=\"10%\">\n";
          $salida .= "                        <a title='LOTE'>LOTE<a> ";
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"center\" width=\"10%\">\n";
          $salida .= "                        <a title='EXISTENCIA'>EXISTENCIA<a> ";
          $salida .= "                      </td>\n";
          /* $salida .= "                      <td align=\"center\" width=\"20%\">\n";
          $salida .= "                        UNIDAD";
          $salida .= "                      </td>\n"; */
        $salida .= "                      <td align=\"center\" width=\"5%\">\n";
        $salida .= "                        <a title='SELECCIONAR PRODUCTO'>SL<a>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        for ($i = 0; $i < count($busqueda); $i++) {
            $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                     " . $busqueda[$i]['etiqueta_x_producto'] . "";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                        " . $busqueda[$i]['codigo_producto'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
            $salida .= "                        " . $busqueda[$i]['descripcion'];
            $salida .= "                      </td>\n";
            /* $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
              $salida .= "                        ".$busqueda[$i]['fecha_vencimiento'];
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
              $salida .= "                        ".$busqueda[$i]['lote'];
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
              $salida .= "                        ".$busqueda[$i]['existencia'];
              $salida .= "                      </td>\n";
              /*$salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
              $salida .= "                         ".$busqueda[$i]['descripcion_unidad'];
              $salida .= "                      </td>\n"; */
            $salida .= "                      <td align=\"center\" onclick=\"AsignarEtiqueta('" . $busqueda[$i]['etiqueta_x_producto'] . "','" . $busqueda[$i]['codigo_barras'] . "');\">\n";
            $salida .= "                         <a title='SELECCIONAR PRODUCTO'>\n";
            $salida .= "                          <sub><img src=\"" . $path . "/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
            $salida .= "                         </a>\n";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
        }
        $salida .= "                </table>\n";

        /* $Cont=$consulta->ContarCuentasStip($toma_fisica,$aumento);
          $malo=$Cont[0]['count']; */

        $action = "Bus_Pro('" . $toma_fisica . "','" . $tip_bus . "','" . $criterio . "'";
        $ctl = AutoCarga::factory("ClaseHTML");
        $salida .= $ctl->ObtenerPaginadoXajax($consulta->conteo, $consulta->paginaActual, $action, "0", 10);

        /* $Cont=$consulta->ContarCuentasStip($toma_fisica,$aumento);
          $malo=$Cont[0]['count'];
          $salida .= "".ObtenerPaginadoPro($path,$Cont,'1',$toma_fisica,$tip_bus,$criterio,$offset); */
    } else if ($aumento != "") {
        $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td align=\"center\">\n";
        $salida .="                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    </table>\n";
    } else {
        $salida .= "  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "    <tr>\n";
        $salida .= "      <td align=\"center\">\n";
        $salida .= "        <label class=\"normal_10AN\">FAVOR INDICAR UN PARAMETRO DE BUSQUEDA</label>";
        $salida .= "      </td>\n";
        $salida .= "    </tr>\n";
        $salida .= "  </table>\n";
    }

    $objResponse->assign("tabelos", "innerHTML", $salida);
    return $objResponse;
}

/* * *********************************************
 * funcion para eliminar captura
 * ********************************************** */

function EliminarCapturaTr($tr, $toma_fisica_id, $etiqueta, $num_conteo) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $elimin = $consulta->EliminarCaptura($toma_fisica_id, $etiqueta, $num_conteo);
    $objResponse->remove($tr);
    $objResponse->assign("error_canti", "innerHTML", $elimin);
    return $objResponse;
}

/* * *******************************************
 * funcion para actualizar el usuario validador
 * ******************************************* */

function ActualizarUsuValidacion($xalida, $toma_id, $lista) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $validacion = $consulta->ValidarActualizarConteo($xalida);
    $ListasProducto = $consulta->SacarProductosLista($toma_id, $lista);

    //////////////////////////
    if (!EMPTY($ListasProducto)) {

        $salida .= "		<center>";
        $salida .= "			<fieldset style=\"width:50%\">";
        $salida .= "				<legend class=\"normal_10AN\">INFORMACION</legend>";
        $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "						<tr>";
        $salida .= "							<td class=\"normal_10AN\">";
        $salida .= "								RESULTADO: ";
        $salida .= "							</td>";
        $salida .= "						</tr>";
        $salida .= "						<tr>";
        $salida .= "							<td class=\"normal_10AN\" align=\"center\">";
        $salida .= "								" . $validacion;
        $salida .= "							</td>";
        $salida .= "						</tr>";
        $salida .= "					</table>";
        $salida .= "			</fieldset>";
        $salida .= "		</center>";
        /* $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
          $salida .= "                       <td width=\"5%\" align=\"center\">\n";
          $salida .= "                          ETIQUETA";
          $salida .= "                       </td>\n";
          $salida .= "                       <td width=\"10%\" align=\"center\">\n";
          $salida .= "                         <a title='NUMERO DE CONTEO'>\n";
          $salida .= "                          NUM CONTEO";
          $salida .= "                         </a>\n";
          $salida .= "                       </td>\n";
          $salida .= "                       <td width=\"13%\" align=\"center\">\n";
          $salida .= "                         <a title='FECHA DE REGISTRO'>\n";
          $salida .= "                          FECHA REG";
          $salida .= "                         <a>";
          $salida .= "                       </td>\n";
          $salida .= "                       <td width=\"12%\" align=\"center\">\n";
          $salida .= "                         <a title='CODIGO DEL PRODUCTO'>\n";
          $salida .= "                          CODIGO";
          $salida .= "                         <a>";
          $salida .= "                       </td>\n";
          $salida .= "                       <td width=\"35%\" align=\"center\">\n";
          $salida .= "                          DESCRIPCION";
          $salida .= "                       </td>\n";
          $salida .= "                       <td width=\"7%\" align=\"center\">\n";
          $salida .= "                         UNIDAD";
          $salida .= "                       </td>\n";
          $salida .= "                       <td width=\"10%\" align=\"center\">\n";
          $salida .= "                        CANTIDAD\n";
          $salida .= "                       </td>\n";
          $salida .= "                       <td width=\"8%\" align=\"center\">\n";
          //$salida .= "                         <input type=\"checkbox\" name=\"checkall\" onclick=\"ValidaLista(this.checked);\">\n";
          $salida .= "                       </td>\n";
          $salida .= "                    </tr>\n";
          for($i=0;$i<count($ListasProducto);$i++)
          {


          $salida .= "                   <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
          $salida .= "                       <td align=\"left\">\n";
          $salida .= "                       ".$ListasProducto[$i]['etiqueta'];
          $salida .= "                       </td>\n";
          $salida .= "                       <td align=\"center\">\n";
          $salida .= "                       ".$ListasProducto[$i]['num_conteo'];
          $salida .= "                       </td>\n";
          $salida .= "                       <td align=\"left\">\n";
          $salida .= "                        ".substr($ListasProducto[$i]['fecha_registro'],0,16);
          $salida .= "                       </td>\n";
          $salida .= "                       <td align=\"left\">\n";
          $salida .= "                       ".$ListasProducto[$i]['codigo_producto'];
          $salida .= "                       </td>\n";
          $salida .= "                       <td align=\"left\">\n";
          $salida .= "                       ".$ListasProducto[$i]['descripcion'];
          $salida .= "                       </td>\n";
          $salida .= "                      <td align=\"left\">\n";
          $salida .= "                       ".$ListasProducto[$i]['descripcion_unidad'];
          $salida .= "                      </td>\n";
          $salida .= "                       <td align=\"center\">\n";
          list($entero,$decimal) = explode(".",$ListasProducto[$i]['conteo']);

          if($decimal>0)
          {
          $salida .= "                      <input type=\"text\" class=\"input-text\" id=\"aaaa\" name=\"conteo_v\" size=\"12\" onkeypress=\"return acceptNum(event);\" value=\"".$ListasProducto[$i]['conteo']."\" onclick=\"Activar(this);\">\n";//
          }
          else
          {
          $salida .= "                      <input type=\"text\" class=\"input-text\" id=\"aaaa\" name=\"conteo_v\" size=\"12\" onkeypress=\"return acceptNum(event);\" value=\"".$entero."\" onclick=\"Activar(this);\">\n";//
          }

          $salida .= "                       </td>\n";
          $salida .= "                       <td  align=\"center\">\n";
          $salida .= "                         <input type=\"checkbox\" name=\"validacher\" value=\"".$toma_id."@".$ListasProducto[$i]['etiqueta']."@".$ListasProducto[$i]['num_conteo']."\" onclick=\"\">\n";
          $salida .= "                       </td>\n";
          $salida .= "                    </tr>\n";
          }
          $salida .= "                   <tr class=\"modulo_list_claro\">\n";
          $salida .= "                       <td  colspan='8' align=\"right\">\n";
          $salida .= "                         <input type=\"button\" class=\"input-submit\" id=\"validar\" name=\"validar\" value=\"VALIDAR\" onclick=\"ActualizarValidacion('".$toma_id."','".$lista."');\">\n";
          $salida .= "                       </td>\n";
          $salida .= "                    </tr>\n";
          $salida .= "                 </table>"; */
    } else {
        $salida .= "		<center>";
        $salida .= "			<fieldset style=\"width:50%\">";
        $salida .= "				<legend class=\"normal_10AN\">INFORMACION</legend>";
        $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "						<tr>";
        $salida .= "							<td class=\"normal_10AN\" align=\"center\">";
        $salida .= "								TODOS LOS PRODUCTOS DE ESTA LISTA HAN SIDO VALIDADOS";
        $salida .= "							</td>";
        $salida .= "						</tr>";
        $salida .= "						<tr>";
        $salida .= "							<td class=\"normal_10AN\" align=\"center\">";
        $salida .= "								" . $validacion;
        $salida .= "							</td>";
        $salida .= "						</tr>";
        $salida .= "					</table>";
        $salida .= "			</fieldset>";
        $salida .= "		</center>";

        /* $salida = "                 <table width=\"100%\" align=\"center\">\n";
          $salida .= "                   <tr>\n";
          $salida .= "                    <td align=\"center\">\n";
          $salida .= "                      <label class='label_error' style=\"text-transform: uppercase; text-align:center;\">TODOS LOS PRODUCTOS DE ESTA LISTA HAN SIDO VALIDADOS</label>";
          $salida .= "                    </td>\n";
          $salida .= "                    </tr>\n";
          $salida .= "                 </table>"; */
    }

    /////////////////////////
    $objResponse->assign("lista_val", "innerHTML", $salida);

    return $objResponse;
}

/* * *******************************************
 * FUNCION PARA CREAR UNA NUEVA LISTA
 * ****************************************** */

function llamarLista($toma_id, $cuantos, $numero_lista) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $numero = $consulta->GetNumeroLista($toma_id);
    $objResponse->assign("numero_lista", "innerHTML", $numero);
    $objResponse->assign("cuantos", "value", 0);
    $objResponse->assign("n_lista_h", "value", $numero);
    $objResponse->assign("refresh_conteo", "innerHTML", "");
    if ($cuantos > 0) {
        $objResponse->assign("save_list", "innerHTML", "LISTA REGISTRADA CON TOMA FISICA '" . $toma_id . "' Y NUMERO DE LISTA '" . $numero_lista . "'");
        $objResponse->assign("error_canti", "innerHTML", "");
    } else {
        $objResponse->assign("save_list", "innerHTML", "");
        $objResponse->assign("error_canti", "innerHTML", "");
    }

    return $objResponse;
}

/* * ********************************************************************************
 * FUNCION PARA LA INSERCUON DE CONTEOS
 * ******************************************************************************** */

function Ins_conteo($toma_fisica_id, $etiqueta, $num_conteo, $conteo, $usuario_registro, $numero_lista, $max, $total) {

    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    /* $buscar_etiquetas = $consulta->BuscarEtiquetas($toma_fisica_id,$etiqueta); */

    $total = $total + 1;

    for ($i = 0; $i <= $conteo['datos']['registros']; $i++) {
        $conteo['datos']['registros'];
        if ($conteo['datos']['cantidad' . $i] != "")
            $rst = $consulta->InsertarConteo($toma_fisica_id, $conteo['datos'][$i], $num_conteo, $conteo['datos']['cantidad' . $i], $usuario_registro, $numero_lista);
    }

    if ($total <= $max) {
        /////////////////////
        $aqui = $total + 1;

        for ($i = 5; $i <= 100; $i = ($i - 5) + 10) {
            if ($i == $aqui) {
                $zalida .="                           <option value=\"" . $aqui . "\">" . $aqui . "</option> \n";
            } elseif ($i == $max) {
                $zalida .="                           <option value=\"" . $i . "\" selected >" . $i . "</option> \n";
            } elseif ($i > ($total + 1)) {
                $zalida .="                           <option value=\"" . $i . "\">" . $i . "</option> \n";
            }
        }
///////////////////////
        if ($total == 1) {
            $objResponse->assign("refresh_conteo", "innerHTML", "");
            $objResponse->assign("save_list", "innerHTML", "");
        }
        $k = 0;



        /*
          for($i=0;$i<=sizeof($buscar_etiquetas);$i++)
          {
          $etiquetas=$buscar_etiquetas[$i]['etiqueta'];
          $cantidad=$conteo['cantidad'.$etiquetas];
          //$cantidad=$conteo[cantidad$i];
          //echo $conteo['cantidad'.$i]." ".$i;
          //print_r($k." kasito ");
          //print_r($i." cual_sera_q_esta_falland            ");
          $rst = $consulta->InsertarConteo($toma_fisica_id,$buscar_etiquetas[$i]['etiqueta'],$num_conteo,$conteo['cantidad'.$etiquetas],$usuario_registro,$numero_lista);
          $k++;
          } */


        $Sconteo = $consulta->SeleccionarConteo($toma_fisica_id, $numero_lista);
        // print_r($Sconteo);
        if (!empty($Sconteo) OR $total <= $max) {
            $salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td width=\"5%\" align=\"center\">\n";
            $salida .= "                         <a title='NUMERO CONTEO'>\n";
            $salida .= "                          # CONTEO";
            $salida .= "                         <a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"7%\" align=\"center\">\n";
            $salida .= "                          ETIQUETA";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"14%\" align=\"center\">\n";
            $salida .= "                         <a title='CODIGO DEL PRODUCTO'>CODIGO\n";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"50%\" align=\"center\">\n";
            $salida .= "                          DESCRIPCION";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"10%\" align=\"center\">\n";
            $salida .= "                          FECHA VENCIMIENTO";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"10%\" align=\"center\">\n";
            $salida .= "                          LOTE";
            $salida .= "                       </td>\n"; /*
              $salida .= "                       <td width=\"8%\" align=\"center\">\n";
              $salida .= "                         <a title='UNIDAD DEL PRODUCTO'>\n";
              $salida .= "                          UNIDAD";
              $salida .= "                         <a>";
              $salida .= "                       </td>\n"; */
            $salida .= "                       <td width=\"10%\" align=\"center\">\n";
            $salida .= "                         <a title='CANTIDAD'>CANTIDAD\n";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"2%\" align=\"center\">\n";
            $salida .= "                         <a title='ELIMINAR PRODUCTO DE LA LISTA'>\n";
            $salida .= "                          X";
            $salida .= "                         </a>\n";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            for ($i = 0; $i <= count($Sconteo); $i++) {
                $producto = $consulta->SacarDatosProducto($Sconteo[$i]['etiqueta'], $toma_fisica_id);
                //var_dump($producto);
                $trx = "tr" . $i;
                $salida .= "                   <tr id=\"" . $trx . "\" class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $salida .= "                       <td  align=\"center\">\n";
                $salida .= "                       " . $Sconteo[$i]['num_conteo'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                       " . $Sconteo[$i]['etiqueta_x_producto'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"left\">\n";
                $salida .= "                       " . $producto[0]['codigo_producto'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"left\">\n";
                $salida .= "                       " . $producto[0]['descripcion'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"left\">\n";
                $salida .= "                       " . $producto[0]['fecha_vencimiento'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"left\">\n";
                $salida .= "                       " . $producto[0]['lote'];
                $salida .= "                       </td>\n"; /*
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       ".$producto[0]['descripcion_unidad'];
                  $salida .= "                      </td>\n"; */

                $salida .= "                       <td  align=\"center\">\n";
                $salida .= "                       " . $Sconteo[$i]['conteo'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td  align=\"center\">\n";
                $javita = "javascript:eliminarUnTr('" . $trx . "','" . $toma_fisica_id . "','" . $Sconteo[$i]['etiqueta'] . "','" . $Sconteo[$i]['num_conteo'] . "');";
                $salida .= "                         <a title='ELIMINAR CAPTURA' class=\"label_error\" href=\"" . $javita . "\">\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
                $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
            }
            $salida .= "                    <tr class=\"modulo_list_oscuro\">\n";
            $salida .= "                       <td  align=\"right\" colspan='7' class=\"normal_10AN\">\n";
            $salida .= "                          TOTAL PRODUCTOS";
            $salida .= "                       </td>\n";
            $salida .= "                       <td  align=\"right\">\n"; //class=\"normal_10AN\"
            $salida .= "                          " . count($Sconteo) . "";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                 </table>";
            $objResponse->assign("refresh_conteo", "innerHTML", $salida);
            $objResponse->assign("numero_lista", "innerHTML", $numero_lista);
            $objResponse->assign("cant_productos", "innerHTML", count($Sconteo) . " DE " . $max);
            $objResponse->assign("n_lista_h", "value", $numero_lista);
            $objResponse->assign("cuantos", "value", count($Sconteo));
            $objResponse->assign("cap_max", "innerHTML", $zalida);
            $objResponse->assign("error_canti", "innerHTML", "");
        } else {
            $objResponse->assign("refresh_conteo", "innerHTML", "");
        }
    } else {
        /* $conteo=$consulta->InsertarConteo($toma_fisica_id,$etiqueta,$num_conteo,$conteo,$usuario_registro,$numero_lista); */
        $objResponse->assign("refresh_conteo", "innerHTML", "");
        $objResponse->assign("save_list", "innerHTML", "LISTA REGISTRADA CON TOMA FISICA '" . $toma_fisica_id . "' Y NUMERO DE LISTA '" . $numero_lista . "'");
        $numero = $consulta->GetNumeroLista($toma_fisica_id);
        $objResponse->assign("numero_lista", "innerHTML", $numero);
        $objResponse->assign("error_canti", "innerHTML", "");

        $objResponse->assign("n_lista_h", "value", $numero);
        $objResponse->assign("cuantos", "value", 0);
    }
    $objResponse->script("xajax_ListaProductos('" . $toma_fisica_id . "','" . $producto[0]['codigo_producto'] . "','" . $etiqueta . "',document.getElementById('num_conteo').value);");
    $objResponse->script("document.getElementById('etiqueta').focus();");
    return $objResponse;
}

/* * ******************************************************************************
 * muestra prefijo segun documento
 * ******************************************************************************* */

function BuscarProducto($tomafisica_id, $etiqueta, $conteo_oficial, $codigo_barras) {

    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();

    if (!empty($codigo_barras)) {
        $empresa = SessionGetVar("EMPRESA");
        $etiqueta_codBar = $consulta->BuscarEtiquetasCodBar($empresa, $tomafisica_id, $conteo_oficial, trim($codigo_barras));
        $objResponse->assign("etiqueta", "value", trim($etiqueta_codBar['etiqueta_x_producto']));
        // $objResponse->assign("etiqueta_h","value",trim($etiqueta_codBar['etiqueta_x_producto']));
    }

    if (!empty($etiqueta))
        $etiquetas_producto = $consulta->BuscarEtiquetas($tomafisica_id, trim($etiqueta));


    $producto = $consulta->BuscarProducto($tomafisica_id, trim($etiqueta), $etiquetas_producto[0]['etiqueta_x_producto'], trim($codigo_barras));
    $producto1 = $consulta->BuscarProductoOtro($tomafisica_id, trim($etiqueta), $etiquetas_producto[0]['etiqueta_x_producto'], trim($codigo_barras), $conteo_oficial);

    $j = 0;
    $total_prod = sizeof($producto1);
    for ($i = 0; $i < sizeof($producto); $i++) {

        $cuadradot = $producto[$i]['cuadrado'];
        $cuadrado++;


        if (!empty($producto)) {
            $objResponse->assign("etiqueta_CodBar", "value", $etiqueta_codBar['etiqueta_x_producto']); // para pruebas
            //$objResponse->assign("prueba_etiqueta","value",$etiqueta); // para pruebas
            $objResponse->assign("des_producto", "innerHTML", "<label class=\"normal_10N\">" . $producto[$i]['descripcion'] . "</label>");
            $objResponse->assign("unidad", "innerHTML", "<label class=\"normal_10N\">" . $producto[$i]['descripcion_unidad'] . "</label>");
            $objResponse->assign("codigo", "innerHTML", "<label class=\"normal_10N\">" . $producto[$i]['codigo_producto'] . "</label>");
            $objResponse->assign("contenido", "innerHTML", "<label class=\"normal_10N\">" . $producto[$i]['contenido_unidad_venta'] . "</label>");
            $objResponse->assign("num_conteo_", "innerHTML", "<label class=\"normal_10N\">" . $producto[$i]['num_conteo'] . "</label>");
            if (empty($producto[$i]['farmacologica']))
                $objResponse->assign("farmacologico", "innerHTML", "<label class=\"normal_10N\">NO APLICA</label>");
            else
                $objResponse->assign("farmacologico", "innerHTML", "<label class=\"normal_10N\">" . $producto[$i]['farmacologica'] . "</label>");
            $objResponse->assign("conteo_cristian", "value", $producto[$i]['num_conteo']);
            $objResponse->script("xajax_ListaProductos('" . $tomafisica_id . "','" . $producto[$i]['codigo_producto'] . "','" . $etiqueta . "','" . $conteo_oficial . "');");

            if ($producto[$i]['cuadrado'] == '1') {
                //$objResponse->assign("error_canti","innerHTML","ESTE PRODUCTO YA ESTA CUADRADO");
            } elseif ($conteo_oficial > 1) {
                $validado = $consulta->GetValidacionCont($tomafisica_id, trim($etiqueta), $conteo_oficial - 1);
                if ($validado != '1') {
                    $objResponse->assign("error_canti", "innerHTML", "EL CONTEO [" . ($conteo_oficial - 1) . "] DE ESTE PRODUCTO NO ESTA VALIDADO.");
                } else {
                    $objResponse->call("BUSCARITO1");

                    if (!empty($codigo_barras)) {
                        $objResponse->assign("etiqueta_h", "value", trim($etiqueta_codBar['etiqueta_x_producto']));
                    } else {
                        $objResponse->assign("etiqueta_h", "value", trim($etiqueta));
                    }

                    $objResponse->assign("des_producto_h", "value", $producto[$i]['descripcion']);
                    $objResponse->assign("unidad_h", "value", $producto[$i]['descripcion_unidad']);
                    $objResponse->assign("codigo_h", "value", $producto[$i]['codigo_producto']);
                    $objResponse->assign("contenido_h", "value", $producto[$i]['contenido_unidad_venta']);
                    $objResponse->assign("num_conteo_h", "value", $producto[$i]['num_conteo']);
                    if (empty($producto[$i]['farmacologica']))
                        $objResponse->assign("farmacologico_h", "value", 'NO APLICA');
                    else
                        $objResponse->assign("farmacologico_h", "value", $producto[$i]['farmacologica']);
                    $objResponse->assign("error_canti", "innerHTML", "");
                    $objResponse->assign("num_conteo", "disabled", "true");
                }
            }
            else {
                $objResponse->call("BUSCARITO1");

                if (!empty($codigo_barras)) {
                    $objResponse->assign("etiqueta_h", "value", trim($etiqueta_codBar['etiqueta_x_producto']));
                } else {
                    $objResponse->assign("etiqueta_h", "value", trim($etiqueta));
                }

                $objResponse->assign("des_producto_h", "value", $producto[$i]['descripcion']);
                $objResponse->assign("unidad_h", "value", $producto[$i]['descripcion_unidad']);
                $objResponse->assign("codigo_h", "value", $producto[$i]['codigo_producto']);
                $objResponse->assign("contenido_h", "value", $producto[$i]['contenido_unidad_venta']);
                $objResponse->assign("num_conteo_h", "value", $producto[$i]['num_conteo']);
                if (empty($producto[$i]['farmacologica']))
                    $objResponse->assign("farmacologico_h", "value", 'NO APLICA');
                else
                    $objResponse->assign("farmacologico_h", "value", $producto[$i]['farmacologica']);
                $objResponse->assign("error_canti", "innerHTML", "");
                $objResponse->assign("num_conteozzz", "disabled", "true");
            }
        }
        else {
            if (empty($producto)) {
                $objResponse->assign("des_producto", "innerHTML", "<label class=\"label_error\">ESTE PRODUCTO NO EXISTE</label>");
                $objResponse->assign("producto_lista", "innerHTML", "");
                $objResponse->assign("unidad", "innerHTML", "");
                $objResponse->assign("codigo", "innerHTML", "");
                $objResponse->assign("contenido", "innerHTML", "");
                $objResponse->assign("conteo", "innerHTML", "");
                $objResponse->call("BUSCARITO2");
                $objResponse->assign("etiqueta_h", "value", "");
                $objResponse->assign("des_producto_h", "value", "");
                $objResponse->assign("unidad_h", "value", "");
                $objResponse->assign("codigo_h", "value", "");
                $objResponse->assign("contenido_h", "value", "");
                $objResponse->assign("conteo_h", "value", "");
                $objResponse->assign("error_canti", "innerHTML", "");
                /* $objResponse->assign("num_conteo_h","value",$producto1[0]['num_conteo']); */
            }
            //elseif($conteo_oficial < $producto[$i]['num_conteo'] AND $producto[$i]['cuadrado']=='1')
            /* elseif($total_prod=='0')
              {
              if($conteo_oficial < $producto[$i]['num_conteo'] AND $producto[$i]['cuadrado']=='1')
              {
              $objResponse->assign("des_producto","innerHTML","<label class=\"normal_10N\">".$producto[$i]['descripcion']."</label>");
              $objResponse->assign("unidad","innerHTML","<label class=\"normal_10N\">".$producto[$i]['descripcion_unidad']."</label>");
              $objResponse->assign("codigo","innerHTML","<label class=\"normal_10N\">".$producto[$i]['codigo_producto']."</label>");
              $objResponse->assign("contenido","innerHTML","<label class=\"normal_10N\">".$producto[$i]['contenido_unidad_venta']."</label>");
              $objResponse->assign("num_conteo","innerHTML","<label class=\"normal_10N\">".$producto[$i]['num_conteo']."</label>");
              if(empty($producto[$i]['farmacologica']))
              $objResponse->assign("farmacologico","innerHTML","<label class=\"normal_10N\">NO APLICA</label>");
              else
              $objResponse->assign("farmacologico","innerHTML","<label class=\"normal_10N\">".$producto[$i]['farmacologica']."</label>");
              $objResponse->assign("error_canti","innerHTML","ESTE NUMERO DE CONTEO YA ESTA REGISTRADO PARA ESTE NUMERO DE PRODUCTO");
              }
              } */

            /* elseif($total_prod>0)
              {
              $objResponse->assign("des_producto","innerHTML","<label class=\"normal_10N\">".$producto[$i]['descripcion']."</label>");
              $objResponse->assign("unidad","innerHTML","<label class=\"normal_10N\">".$producto[$i]['descripcion_unidad']."</label>");
              $objResponse->assign("codigo","innerHTML","<label class=\"normal_10N\">".$producto[$i]['codigo_producto']."</label>");
              $objResponse->assign("contenido","innerHTML","<label class=\"normal_10N\">".$producto[$i]['contenido_unidad_venta']."</label>");
              $objResponse->assign("num_conteo","innerHTML","<label class=\"normal_10N\">".$producto1[0]['num_conteo']."</label>");
              $objResponse->assign("etiqueta_h","value",$etiqueta);
              $objResponse->assign("des_producto_h","value",$producto[$i]['descripcion']);
              $objResponse->assign("unidad_h","value",$producto[$i]['descripcion_unidad']);
              $objResponse->assign("codigo_h","value",$producto[$i]['codigo_producto']);
              $objResponse->assign("contenido_h","value",$producto[$i]['contenido_unidad_venta']);
              $objResponse->assign("num_conteo_h","value",$producto1[0]['num_conteo']);

              if(empty($producto[$i]['farmacologica']))
              $objResponse->assign("farmacologico","innerHTML","<label class=\"normal_10N\">NO APLICA</label>");
              else
              $objResponse->assign("farmacologico","innerHTML","<label class=\"normal_10N\">".$producto[$i]['farmacologica']."</label>");
              $objResponse->script("xajax_ListaProductos('".$tomafisica_id."','".$producto[$i]['codigo_producto']."','".$etiqueta."','".$conteo_oficial."');");
              }
              elseif($conteo_oficial > $producto[$i]['num_conteo'] )
              {
              $objResponse->assign("des_producto","innerHTML","<label class=\"normal_10N\">".$producto[$i]['descripcion']."</label>");
              $objResponse->assign("unidad","innerHTML","<label class=\"normal_10N\">".$producto[$i]['descripcion_unidad']."</label>");
              $objResponse->assign("codigo","innerHTML","<label class=\"normal_10N\">".$producto[$i]['codigo_producto']."</label>");
              $objResponse->assign("contenido","innerHTML","<label class=\"normal_10N\">".$producto[$i]['contenido_unidad_venta']."</label>");
              $objResponse->assign("num_conteo","innerHTML","<label class=\"normal_10N\">".$producto[$i]['num_conteo']."</label>");
              if(empty($producto[$i]['farmacologica']))
              $objResponse->assign("farmacologico","innerHTML","<label class=\"normal_10N\">NO APLICA</label>");
              else
              $objResponse->assign("farmacologico","innerHTML","<label class=\"normal_10N\">".$producto[$i]['farmacologica']."</label>");
              $objResponse->assign("error_canti","innerHTML","PRIMERO DEBE REALIZARSE EL CONTEO #".$producto[$i]['num_conteo']." DE ESTE PRODUCTO");

              } */

            // $objResponse->assign("Contenido","innerHTML",$html);
        }
        $j++;
    }

    return $objResponse;
}

/* * ******************************************************************************
 * Obtener etiqueta para producto buscado por codigo de barras 
 * ******************************************************************************* */

// function BuscarEtCodBar($tomafisica_id,$conteo_oficial,$codigo_barras)
// {  
// $objResponse = new xajaxResponse();
// $path = SessionGetVar("rutaImagenes");
// $empresa = SessionGetVar("EMPRESA");
// $consulta = new TomaFisicaSQL();
// $etiqueta_codBar=$consulta->BuscarEtiquetasCodBar($empresa,$tomafisica_id,$conteo_oficial,trim($codigo_barras));
// $objResponse->assign("etiqueta","value",$etiqueta_codBar['etiqueta_x_producto']);
// return $objResponse;
// }

/**
 * Funcion donde se lista los productos para buscar las etiquetas por producto.
 */
function ListaProductos($tomafisica_id, $codigo_producto, $etiqueta, $conteo) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $etiqueta_x_product = $consulta->Etiquetaxproducto($tomafisica_id, $codigo_producto);
    $lista_prod = $consulta->ListarProductos($tomafisica_id, trim($codigo_producto), trim($etiqueta), $conteo, $etiqueta_x_product);

    $BuscarEmpresaCE = $consulta->BuscarEmpresaAndCentroUtilidad($tomafisica_id);
    $param_adicionar = $consulta->BuscarParametrizacion_IngresoLoteFV($BuscarEmpresaCE['empresa_id'], $BuscarEmpresaCE['centro_utilidad']);
    if ($param_adicionar['conteo_2'] == 1)
        $conteo_2 = 2;
    else
        $conteo_2 = 0;
    if ($param_adicionar['conteo_3'] == 1)
        $conteo_3 = 3;
    else
        $conteo_3 = 0;
    //$salida1 .= "<pre>".print_r($BuscarEmpresaCE[0]."DISQUE EL REQUEST",true)."</pre>";
    $salida1 .= "<br>";
    $salida1 .= " <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
    $salida1 .= "    <tr class=\"modulo_table_list_title\">\n";
    $salida1 .= "      <td align=\"center\" width=\"10%\">\n";
    $salida1 .= "           LOTE";
    $salida1 .= "      </td>\n";
    $salida1 .= "     <td align=\"center\" width=\"10%\">\n";
    $salida1 .= "           FECHA DE VENCIMIENTO";
    $salida1 .= "      </td>\n";
    $salida1 .= "     <td align=\"center\" width=\"15%\">\n";
    $salida1 .= "           UBICACION";
    $salida1 .= "      </td>\n";
    $salida1 .= "      <td align=\"center\" width=\"8%\">\n";
    $salida1 .= "           EXISTENCIA";
    $salida1 .= "      </td>\n";
    $salida1 .= "      <td align=\"center\" width=\"8%\">\n";
    $salida1 .= "           CANTIDAD";
    $salida1 .= "      </td>\n";
    $salida1 .= "      <td align=\"center\" width=\"30%\" class=\"modulo_list_oscuro\">\n";
    $salida1 .= "           <table class=\"modulo_table_list\" width=\"100%\">";
    $salida1 .= "				<tr class=\"modulo_list_claro\">";
    $salida1 .= "					<td colspan=\"3\" class=\"modulo_table_list_title\">";
    $salida1 .= "							OBSERVACIONES";
    $salida1 .= "					</td>";
    $salida1 .= "				</tr>";
    $salida1 .= "				<tr class=\"modulo_list_claro\">";
    $salida1 .= "					<td align=\"center\" >";
    $salida1 .= "							NO CONTADO";
    $salida1 .= "					</td>";
    $salida1 .= "					<td align=\"center\" style=\"background:#00FF40;\">";
    $salida1 .= "							CONTADO";
    $salida1 .= "					</td>";
    $salida1 .= "					<td align=\"center\" style=\"background:	#FF1000;\">";
    $salida1 .= "							CONTADO Y VALIDADO";
    $salida1 .= "					</td>";
    $salida1 .= "				</tr>";
    $salida1 .= "           </table>";
    $salida1 .= "      </td>\n";
    $salida1 .= "    </tr>\n";
    $i = 0;
    $m = 0;
    $k = 0;
    //for($i=0;$i<count($busqueda);$i++)
    //        { 
    //for($i=0;$i<count($lista_prod[$etiqueta]); $i++)
    //{
    $c = 0;
    foreach ($lista_prod as $key => $valor) {
        //$salida1 .="<pre>".print_r($lista_prod,true)."</pre>";
        if ($valor['cuadrado'] == '1')
            $c++;
        /* { */
        $salida1 .= "  <tr class=\"modulo_list_claro\">\n";
        $salida1 .= "    <td align=\"center\" >\n";
        $salida1 .= "      " . $valor['lote'] . "";
        $salida1 .= "     </td>\n";
        $salida1 .= "     <td align=\"center\" >\n";
        $salida1 .= "       " . $valor['fecha_vencimiento'] . "";
        $salida1 .= "     </td>\n";
        $salida1 .= "     <td align=\"center\">\n";
        $salida1 .= "       " . $valor['ubicacion'] . "";
        $salida1 .= "     </td>\n";
        $salida1 .= "     <td align=\"center\">\n";
        /* $salida1 .= "       ".$valor['existencia'].""; */
        $salida1 .= "     </td>\n";

        //$salida1 .="<pre>".print_r($param_adicionar,true)."</pre>";
        $mensaje = explode('@', $valor['producto_conteo']);
        $salida1 .= "     <td align=\"center\" >\n";
        $salida1 .= "         <input type=\"text\" class=\"input-text\" id=\"cantidad" . $k . "\" name=\"datos[cantidad" . $k . "]\" style=\"width:100%\" value=\"" . $valor['conteo'] . "\" " . $mensaje[2] . " onkeypress=\"return acceptNum(event)\">\n"; //
        $salida1 .= "         <input type=\"hidden\" id=\"etiqueta" . $k . "\" name=\"datos[" . $k . "]\" value=\"" . $key . "\" >\n"; //
        $salida1 .= "         <input type=\"hidden\" id=\"registros\" name=\"datos[registros]\" value=\"" . ($k++) . "\" >\n"; //
        $salida1 .= "     </td>\n";


        $salida1 .= "      <td align=\"center\" style=\"background:" . $mensaje[0] . ";\">\n";
        $salida1 .= "			" . $mensaje[1];
        $salida1 .= "		</td>";
        $salida1 .= "  </tr>\n";
        /* } */
        $i++;
        $m++;
        //}
    }
    //$salida1 .="<pre>".print_r($key,true)."</pre>";
    $salida1 .= "  <tr class=\"modulo_list_claro\">\n";
    if ($conteo == '1' && $c <= 0) {
        $salida1 .= "   <td  align=\"center\" colspan=\"1\">";
        $salida1 .= "     <a href=\"#\" onclick=\"xajax_AdicionarLoteFV('" . trim($codigo_producto) . "','" . $conteo . "','" . trim($BuscarEmpresaCE['empresa_id']) . "','" . trim($BuscarEmpresaCE['centro_utilidad']) . "','" . trim($BuscarEmpresaCE['bodega']) . "')\">ADICIONAR LOTE</a>\n";
        $salida1 .= "   </td>";
    } else {
        $salida1 .= "  <td class=\"modulo_list_claro\">\n";
        $salida1 .= "     </td>\n";
    }
    $salida1 .= "   <td " . $bck . " align=\"center\" colspan=\"4\">";
    $salida1 .= "     <a href=\"#\" onclick=\"GuardarCantidadPro('" . $i . "')\">GUARDAR</a>\n";
    $salida1 .= "   </td>";
    $salida1 .= "   <td>";
    $salida1 .= "   </td>";
    $salida1 .= "     </tr>\n";
    $salida1 .= " </table>\n";

    $salida1 .= " <br>\n";
    $salida1 .=" <div id=\"adicionar_lotefv\">";
    $salida1 .=" </div>\n";

    $objResponse->assign("producto_lista", "innerHTML", $salida1);
    $objResponse->script("document.getElementById('cantidad0').focus();");
    return $objResponse;
}

/**
 * Funcion donde se muestra para agregar un producto su fecha de vencimiento y lote.
 */
function AdicionarLoteFV($codigo_producto, $conteo, $empresa_id, $centro_utililidad, $bodega) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    global $VISTA;
    $imagen = "themes/$VISTA/" . GetTheme() . "/images/calendario/calendario.png";
    $consulta = new TomaFisicaSQL();
    $empresa = SessionGetVar("EMPRESA");

    $buscar_ubicacion = $consulta->BuscarUbicacion($empresa_id, $centro_utililidad, $bodega);
    // $salida1 .= "            <form id=\"add_movimiento1\" name=\"add_movimiento1\" method=\"post\">\n";

    $salida1 .= "<table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
    $salida1 .= " <input type=\"hidden\" id=\"codigo_producto\" name=\"codigo_producto\" value=\"" . $codigo_producto . "\">\n"; //
    $salida1 .= "  <tr class=\"modulo_list_claro\">\n";
    $salida1 .= "    <td align=\"center\" width=\"10%\">\n";
    $salida1 .= "       LOTE";
    $salida1 .= "    </td>\n";
    //$salida1 .="<pre>".print_r($conteo,true)."</pre>";
    $salida1 .= "    <td align=\"left\" width=\"5%\" colspan=\"4\">\n";
    $salida1 .= "       <input style=\"width:50%\" type=\"text\" class=\"input-text\" id=\"lote\" name=\"lote\">\n"; //
    $salida1 .= "    </td>\n";
    $salida1 .= "  </tr>\n";
    $salida1 .= "  <tr class=\"modulo_list_claro\">\n";
    $salida1 .= "    <td align=\"center\" width=\"10%\">\n";
    $salida1 .= "        FECHA VENCIMIENTO";
    $salida1 .= "    </td>\n";
    $salida1 .= "    <td align=\"left\" width=\"10%\" colspan=\"4\">\n";
    $salida1 .= "      <input style=\"width:50%\" type=\"text\" class=\"input-text\" id=\"fecha_venci\" name=\"fecha_venci\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\">\n";
    /* $salida1 .= "<a title=\"Ver Calendario\" href=\"javascript:Mostrar_FechaVen('fecha_venci','calendario_pxfecha_venci')\" class=\"label_error\">\n";
      $salida1 .= "  <img src=\"".GetThemePath(). "/images/calendario/calendario.png\" border=\"0\"  >\n";
      $salida1 .= "</a>\n"; */
    $salida1 .= "<label class=\"label\">[aaaa-mm-dd]</label>\n";
    $salida1 .= "<div id=\"calendario_pxfecha_venci\" class=\"calendario_px\"></div>\n";

    /*
      $Salida .= "<a title=\"Ver Calendario\" href=\"javascript:Mostrar_".$campo."()\" class=\"label_error\">\n";
      $Salida .= "  <img src=\"".$imagen."\" border=\"0\"  >\n";
      $Salida .= "</a>\n";
     */
    //$salida1 .= "                         <input type=\"text\" class=\"input-text\" name=\"fecha1\" id=\"fecha1\"  size=\"12\" onkeypress=\"return acceptNum(event)\">\n";
    //$salida1 .= "                                ".ReturnOpenCalendario('add_movimiento1','fecha_venci','-')."\n";
    // $salida1 .="<sub>".ReturnOpenCalendario("add_movimiento1","fecha_venci","-")."</sub>";

    $salida1 .= "    </td>\n";
    $salida1 .= "  </tr>\n";
    $salida1 .= "  <tr class=\"modulo_list_claro\">\n";
    $salida1 .= "    <td align=\"center\" width=\"5%\">\n";
    $salida1 .= "        UBICACION";
    $salida1 .= "    </td>\n";
    $salida1 .= "    <td align=\"left\" colspan=\"3\">\n";
    $salida1 .= "      <select align=\"left\" class=\"select\" name=\"ubicacion_id\" id=\"ubicacion_id\" style=\"width:100%\">\n";
    $salida1 .= "       <option value=\"\" >--SIN UBICACION--</option>\n";
    foreach ($buscar_ubicacion as $key => $valor) {
        $salida1 .= "       <option value=\"" . $valor['ubicacion_id'] . "\" >" . $valor['descripcion'] . "</option>\n";
    }

    $salida1 .= "     </select>\n";
    $salida1 .= "    </td>\n";
    /* $salida1 .= "    <td align=\"left\" width=\"5%\">\n";
      $salida1 .= "     <div id=\"ubicacion_n2\">";
      $salida1 .= "     </div>\n";
      $salida1 .= "    </td>\n";
      $salida1 .= "    <td align=\"left\" width=\"5%\">\n";
      $salida1 .= "     <div id=\"ubicacion_n3\">";
      $salida1 .= "     </div>\n";
      $salida1 .= "    </td>\n";
      $salida1 .= "    <td align=\"left\" width=\"5%\">\n";
      $salida1 .= "     <div id=\"ubicacion_n4\">";
      $salida1 .= "     </div>\n";
      $salida1 .= "    </td>\n"; */

    $salida1 .= "  </tr>\n";
    $salida1 .= "  <tr class=\"modulo_list_claro\">\n";
    $salida1 .= "   <td  align=\"right\" colspan=\"1\"></td>";
    $salida1 .= "   <td " . $bck . " align=\"center\" colspan=\"4\">";
    $salida1 .= "     <a href=\"#\" onclick=\"xajax_MoficarFechaLote(xajax.getFormValues('add_movimiento'),'" . trim($codigo_producto) . "','" . $conteo . "')\">GUARDAR</a>\n";
    $salida1 .= "   </td>\n";
    $salida1 .= "   </tr>\n";
    $salida1 .= "</table>\n";
    //$salida1 .= "</form>\n";
    $salida1 .= "<br>";
    $objResponse->assign("adicionar_lotefv", "innerHTML", $salida1);
    return $objResponse;
}

/**
 * Funcion donde se muestra la lista de ubicacion n2.
 */
function UbicacionN2($forma) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $buscar_ubicacion_n2 = $consulta->BuscarUbicacionN2($forma['ubicacion_n1']);

    $salida1 .= " <select align=\"left\"width=\"15%\" class=\"select\" name=\"ubicacion_n2\" id=\"ubicacion_n2\" onchange=\"xajax_UbicacionN3(xajax.getFormValues('add_movimiento'))\">\n";
    $salida1 .= "       <option value=\-1\" >--Seleccione--</option>\n";
    foreach ($buscar_ubicacion_n2 as $key => $valor) {
        if (!empty($valor['n2']))
            $salida1 .= "   <option value=\"" . $valor['n2'] . "\" >" . $valor['n2'] . "</option>\n";
    }
    $salida1 .= " </select>\n";
    $objResponse->assign("ubicacion_n2", "innerHTML", $salida1);
    return $objResponse;
}

/**
 * Funcion donde se muestra la lista de ubicacion n3.
 */
function UbicacionN3($forma) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $buscar_ubicacion_n3 = $consulta->BuscarUbicacionN3($forma['ubicacion_n1'], $forma['ubicacion_n2']);

    $salida1 .= " <select align=\"left\"width=\"15%\" class=\"select\" name=\"ubicacion_n3\" id=\"ubicacion_n3\" onchange=\"xajax_UbicacionN4(xajax.getFormValues('add_movimiento'))\">\n";
    $salida1 .= "       <option value=\-1\" >--Seleccione--</option>\n";
    foreach ($buscar_ubicacion_n3 as $key => $valor) {
        if (!empty($valor['n3']))
            $salida1 .= "   <option value=\"" . $valor['n3'] . "\" >" . $valor['n3'] . "</option>\n";
    }
    $salida1 .= "  </select>\n";
    $objResponse->assign("ubicacion_n3", "innerHTML", $salida1);
    return $objResponse;
}

/**
 * Funcion donde se muestra la lista de ubicacion n4.
 */
function UbicacionN4($forma) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $buscar_ubicacion_n4 = $consulta->BuscarUbicacionN4($forma['ubicacion_n1'], $forma['ubicacion_n2'], $forma['ubicacion_n3']);

    $salida1 .= "      <select align=\"left\"width=\"15%\" class=\"select\" name=\"ubicacion_n4\" id=\"ubicacion_n4\" onchange=\"\">\n";
    $salida1 .= "       <option value=\-1\" >--Seleccione--</option>\n";
    foreach ($buscar_ubicacion_n4 as $key => $valor) {
        if (!empty($valor['n4']))
            $salida1 .= "       <option value=\"" . $valor['n4'] . "\" >" . $valor['n4'] . "</option>\n";
    }
    $salida1 .= "     </select>\n";
    $objResponse->assign("ubicacion_n4", "innerHTML", $salida1);
    return $objResponse;
}

/**
 * Funcion donde se guarda la fecha de vencimiento y lote del producto.
 */
function MoficarFechaLote($forma, $codigo_producto, $conteo) {

    $codigo_producto = trim($codigo_producto);
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new TomaFisicaSQL();
    $todos_datos = $consulta->BuscarEtiquetas($forma['toma_idx'], trim($forma['etiqueta']));
    //print_r($forma);

    $busqueda_prod = $consulta->BuscarFechaLote($codigo_producto, $forma['fecha_venci'], $forma['lote'], $todos_datos[0]['empresa_id'], $todos_datos[0]['centro_utilidad'], $todos_datos[0]['bodega']);
    $busqueda_prod_tomafisica = $consulta->BuscarFechaLote_TomaFisica($forma['toma_idx'], $codigo_producto, $forma['fecha_venci'], $forma['lote']);
    /* $busqueda_ubicacion=$consulta->BuscarUbicacionTodos($forma['ubicacion_n1'],$forma['ubicacion_n2'],$forma['ubicacion_n3'],$forma['ubicacion_n4'],$todos_datos[0]['empresa_id'],$todos_datos[0]['centro_utilidad'],$todos_datos[0]['bodega']); */
    /* print_r($busqueda_prod_tomafisica); */
    // exit;
    $etiqueta_producto_s = $consulta->BuscarTodaTomas($forma['toma_idx']);
    $total_etiqueta_sig = $etiqueta_producto_s[0]['max'] + 1;

    if (!empty($busqueda_prod_tomafisica[0])) {
        $objResponse->script("alert('EL PRODUCTO YA HACE PARTE DE ESTA TOMA FISICA: ET.GRAL:" . $busqueda_prod_tomafisica[0]['etiqueta_x_producto'] . ", ET.LOTE: " . $busqueda_prod_tomafisica[0]['etiqueta'] . "');");
    } else {
        if (empty($busqueda_prod[0])) {
            $guardar_ex = $consulta->InsertarExistenciasFV($todos_datos[0]['empresa_id'], $todos_datos[0]['centro_utilidad'], $codigo_producto, $todos_datos[0]['bodega'], $forma['fecha_venci'], $forma['lote'], $forma['ubicacion_id']);
            $guarda_toma_fisica_d = $consulta->InsertarTomaFisica($forma['toma_idx'], $total_etiqueta_sig, $todos_datos[0]['empresa_id'], $todos_datos[0]['centro_utilidad'], $codigo_producto, $todos_datos[0]['bodega'], $forma['fecha_venci'], $forma['lote'], $todos_datos[0]['etiqueta_x_producto'], '1');
        } else {
            $guarda_toma_fisica_d = $consulta->InsertarTomaFisica($forma['toma_idx'], $total_etiqueta_sig, $todos_datos[0]['empresa_id'], $todos_datos[0]['centro_utilidad'], $codigo_producto, $todos_datos[0]['bodega'], $forma['fecha_venci'], $forma['lote'], $todos_datos[0]['etiqueta_x_producto'], '1');
        }
        $objResponse->alert($consulta->mensajeDeError);

        if ($guarda_toma_fisica_d) {
            $objResponse->script("xajax_ListaProductos('" . $forma['toma_idx'] . "','" . $codigo_producto . "','" . trim($forma['etiqueta']) . "','" . $conteo . "')");
        }
    }

    return $objResponse;
}

/* * ************************************************************************************
 * Separa la Fecha del formato timestamp  @access private @return string @param date fecha
 * ************************************************************************************ */

function FechaStamp($fecha) {
    if ($fecha) {
        $fech = strtok($fecha, "-");
        for ($l = 0; $l < 3; $l++) {
            $date[$l] = $fech;
            $fech = strtok("-");
        }

        return ceil($date[2]) . "-" . str_pad(ceil($date[1]), 2, 0, STR_PAD_LEFT) . "-" . str_pad(ceil($date[0]), 2, 0, STR_PAD_LEFT);
    }
}

/* * ******************************************************************************
 * para mostrar la tabla de vinculacion de cuentas con paginador incluido
 * ******************************************************************************* */

function ObtenerPaginadoPN($pagina, $path, $slc, $op, $prefijo, $numero) {
    $TotalRegistros = $slc[0]['count'];
    $TablaPaginado = "";

    if ($limite == null) {
        $uid = UserGetUID();
        $LimitRow = 10; //intval(GetLimitBrowser());
        //return $LimitRow;
    } else {
        $LimitRow = $limite;
    }


    if ($TotalRegistros > 0) {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros / $LimitRow);

        if ($TotalRegistros % $LimitRow > 0) {
            $NumeroPaginas++;
        }

        $Inicio = $pagina;
        if ($NumeroPaginas - $pagina < 9) {
            $Inicio = $NumeroPaginas - 9;
        } elseif ($pagina > 1) {
            $Inicio = $pagina - 1;
        }

        if ($Inicio <= 0) {
            $Inicio = 1;
        }

        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

        $TablaPaginado .= "<tr>\n";
        if ($NumeroPaginas > 1) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Paginas:</td>\n";
            if ($pagina > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";                //     $lapso,$tip_doc,$prefijo,$numero                       
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('1','" . $prefijo . "','" . $numero . "');\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('" . ($pagina - 1) . "','" . $prefijo . "','" . $numero . "');\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $Fin = $NumeroPaginas + 1;
            if ($NumeroPaginas > 10) {
                $Fin = 10 + $Inicio;
            }

            for ($i = $Inicio; $i < $Fin; $i++) {
                if ($i == $pagina) {
                    $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                } else {
                    $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:LlamarDocus('" . $i . "','" . $prefijo . "','" . $numero . "');\">" . $i . "</a></td>\n";
                }
                $columnas++;
            }
        }
        if ($pagina < $NumeroPaginas) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('" . ($pagina + 1) . "','" . $prefijo . "','" . $numero . "')\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:LlamarDocus('" . $NumeroPaginas . "','" . $prefijo . "','" . $numero . "')\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
        $aviso .= "     Pagina&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
        $aviso .= "   </tr>\n";

        if ($op == 2) {
            $TablaPaginado .= $aviso;
        } else {
            $TablaPaginado = $aviso . $TablaPaginado;
        }
    }

    $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
    $Tabla .= $TablaPaginado;
    $Tabla .= "</table>";

    return $Tabla;
}

/* * ******************************************************************************
 * para mostrar la tabla de vinculacion de cuentas con paginador incluido
 * ******************************************************************************* */

function ObtenerPaginado($pagina, $path, $slc, $op, $lapso, $dia1, $dia2, $tip_doc, $prefijo, $numero) {
    $TotalRegistros = $slc[0]['count'];
    $TablaPaginado = "";

    if ($limite == null) {
        $uid = UserGetUID();
        $LimitRow = 10; //intval(GetLimitBrowser());
        //return $LimitRow;
    } else {
        $LimitRow = $limite;
    }


    if ($TotalRegistros > 0) {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros / $LimitRow);

        if ($TotalRegistros % $LimitRow > 0) {
            $NumeroPaginas++;
        }

        $Inicio = $pagina;
        if ($NumeroPaginas - $pagina < 9) {
            $Inicio = $NumeroPaginas - 9;
        } elseif ($pagina > 1) {
            $Inicio = $pagina - 1;
        }

        if ($Inicio <= 0) {
            $Inicio = 1;
        }

        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

        $TablaPaginado .= "<tr>\n";
        if ($NumeroPaginas > 1) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Paginas:</td>\n";
            if ($pagina > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";                //     $lapso,$tip_doc,$prefijo,$numero                       
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('1','" . $lapso . "','" . $dia1 . "','" . $dia2 . "','" . $tip_doc . "','" . $prefijo . "','" . $numero . "');\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('" . ($pagina - 1) . "','" . $lapso . "','" . $dia1 . "','" . $dia2 . "','" . $tip_doc . "','" . $prefijo . "','" . $numero . "');\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $Fin = $NumeroPaginas + 1;
            if ($NumeroPaginas > 10) {
                $Fin = 10 + $Inicio;
            }

            for ($i = $Inicio; $i < $Fin; $i++) {
                if ($i == $pagina) {
                    $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                } else {
                    $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:LlamarDocus('" . $i . "','" . $lapso . "','" . $dia1 . "','" . $dia2 . "','" . $tip_doc . "','" . $prefijo . "','" . $numero . "');\">" . $i . "</a></td>\n";
                }
                $columnas++;
            }
        }
        if ($pagina < $NumeroPaginas) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('" . ($pagina + 1) . "','" . $lapso . "','" . $dia1 . "','" . $dia2 . "','" . $tip_doc . "','" . $prefijo . "','" . $numero . "')\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:LlamarDocus('" . $NumeroPaginas . "','" . $lapso . "','" . $dia1 . "','" . $dia2 . "','" . $tip_doc . "','" . $prefijo . "','" . $numero . "')\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
        $aviso .= "     Pagina&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
        $aviso .= "   </tr>\n";

        if ($op == 2) {
            $TablaPaginado .= $aviso;
        } else {
            $TablaPaginado = $aviso . $TablaPaginado;
        }
    }

    $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
    $Tabla .= $TablaPaginado;
    $Tabla .= "</table>";

    return $Tabla;
}

/* * ******************************************************************************
 * para mostrar la tabla de vinculacion de cuentas con paginador incluido
 * ******************************************************************************* */

function ObtenerPaginadoCuenta($pagina, $path, $slc, $op, $tip_bus) {
    $TotalRegistros = $slc[0]['count'];
    $TablaPaginado = "";

    if ($limite == null) {
        $uid = UserGetUID();
        $LimitRow = 10; //intval(GetLimitBrowser());
        //return $LimitRow;
    } else {
        $LimitRow = $limite;
    }


    if ($TotalRegistros > 0) {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros / $LimitRow);

        if ($TotalRegistros % $LimitRow > 0) {
            $NumeroPaginas++;
        }

        $Inicio = $pagina;
        if ($NumeroPaginas - $pagina < 9) {
            $Inicio = $NumeroPaginas - 9;
        } elseif ($pagina > 1) {
            $Inicio = $pagina - 1;
        }

        if ($Inicio <= 0) {
            $Inicio = 1;
        }

        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

        $TablaPaginado .= "<tr>\n";
        if ($NumeroPaginas > 1) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Paginas:</td>\n";
            if ($pagina > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:TablaCuentas('" . $tip_bus . "','" . $cuenta . "','1');\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:TablaCuentas('" . $tip_bus . "','" . $cuenta . "','" . ($pagina - 1) . "');\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $Fin = $NumeroPaginas + 1;
            if ($NumeroPaginas > 10) {
                $Fin = 10 + $Inicio;
            }

            for ($i = $Inicio; $i < $Fin; $i++) {
                if ($i == $pagina) {
                    $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                } else {
                    $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:TablaCuentas('" . $tip_bus . "','" . $cuenta . "','" . $i . "');\">" . $i . "</a></td>\n";
                }
                $columnas++;
            }
        }
        if ($pagina < $NumeroPaginas) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";                                  //        tip_bus,cuenta,offset 
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:TablaCuentas('" . $tip_bus . "','" . $cuenta . "','" . ($pagina + 1) . "')\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:TablaCuentas('" . $tip_bus . "','" . $cuenta . "','" . $NumeroPaginas . "')\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
        $aviso .= "     Pagina&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
        $aviso .= "   </tr>\n";

        if ($op == 2) {
            $TablaPaginado .= $aviso;
        } else {
            $TablaPaginado = $aviso . $TablaPaginado;
        }
    }

    $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
    $Tabla .= $TablaPaginado;
    $Tabla .= "</table>";

    return $Tabla;
}

/* * ******************************************************************************
 * para mostrar la tabla de vinculacion de cuentas con paginador incluido
 * ******************************************************************************* */

function ObtenerPaginado2($pagina, $path, $slc, $op, $lapso, $dia1, $dia2, $tip_doc, $prefijo, $numero) {
    $TotalRegistros = $slc[0]['count'];
    $TablaPaginado = "";

    if ($limite == null) {
        $uid = UserGetUID();
        $LimitRow = 50; //intval(GetLimitBrowser());
        //return $LimitRow;
    } else {
        $LimitRow = $limite;
    }


    if ($TotalRegistros > 0) {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros / $LimitRow);

        if ($TotalRegistros % $LimitRow > 0) {
            $NumeroPaginas++;
        }

        $Inicio = $pagina;
        if ($NumeroPaginas - $pagina < 9) {
            $Inicio = $NumeroPaginas - 9;
        } elseif ($pagina > 1) {
            $Inicio = $pagina - 1;
        }

        if ($Inicio <= 0) {
            $Inicio = 1;
        }

        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

        $TablaPaginado .= "<tr>\n";
        if ($NumeroPaginas > 1) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Paginas:</td>\n";
            if ($pagina > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";                //     $lapso,$tip_doc,$prefijo,$numero                       
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus1('1','" . $lapso . "','" . $dia1 . "','" . $dia2 . "','" . $tip_doc . "','" . $prefijo . "');\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus1('" . ($pagina - 1) . "','" . $lapso . "','" . $dia1 . "','" . $dia2 . "','" . $tip_doc . "','" . $prefijo . "');\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $Fin = $NumeroPaginas + 1;
            if ($NumeroPaginas > 10) {
                $Fin = 10 + $Inicio;
            }

            for ($i = $Inicio; $i < $Fin; $i++) {
                if ($i == $pagina) {
                    $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                } else {
                    $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:LlamarDocus1('" . $i . "','" . $lapso . "','" . $dia1 . "','" . $dia2 . "','" . $tip_doc . "','" . $prefijo . "');\">" . $i . "</a></td>\n";
                }
                $columnas++;
            }
        }
        if ($pagina < $NumeroPaginas) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus1('" . ($pagina + 1) . "','" . $lapso . "','" . $dia1 . "','" . $dia2 . "','" . $tip_doc . "','" . $prefijo . "')\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:LlamarDocus1('" . $NumeroPaginas . "','" . $lapso . "','" . $dia1 . "','" . $dia2 . "','" . $tip_doc . "','" . $prefijo . "')\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
        $aviso .= "     Pagina&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
        $aviso .= "   </tr>\n";

        if ($op == 2) {
            $TablaPaginado .= $aviso;
        } else {
            $TablaPaginado = $aviso . $TablaPaginado;
        }
    }

    $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
    $Tabla .= $TablaPaginado;
    $Tabla .= "</table>";

    return $Tabla;
}

/* * ******************************************************************************
 * para mostrar la tabla de clientes
 * ******************************************************************************* */

function ObtenerPaginadoPro($path, $slc, $op, $toma_fisica, $tip_bus, $criterio, $pagina) {

    //echo "io";
    $TotalRegistros = $slc[0]['count'];
    $TablaPaginado = "";

    if ($limite == null) {
        $uid = UserGetUID();
        $LimitRow = intval(GetLimitBrowser());
    } else {
        $LimitRow = $limite;
    }
    if ($TotalRegistros > 0) {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros / $LimitRow);

        if ($TotalRegistros % $LimitRow > 0) {
            $NumeroPaginas++;
        }

        $Inicio = $pagina;
        if ($NumeroPaginas - $pagina < 9) {
            $Inicio = $NumeroPaginas - 9;
        } elseif ($pagina > 1) {
            $Inicio = $pagina - 1;
        }

        if ($Inicio <= 0) {
            $Inicio = 1;
        }

        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

        $TablaPaginado .= "<tr>\n";
        if ($NumeroPaginas > 1) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P�inas:</td>\n";
            if ($pagina > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_Pro('" . $toma_fisica . "','" . $tip_bus . "','" . $criterio . "','1')\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_Pro('" . $toma_fisica . "','" . $tip_bus . "','" . $criterio . "','" . ($pagina - 1) . "')\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $Fin = $NumeroPaginas + 1;
            if ($NumeroPaginas > 10) {
                $Fin = 10 + $Inicio;
            }

            for ($i = $Inicio; $i < $Fin; $i++) {
                if ($i == $pagina) {
                    $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                } else {
                    $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:Bus_Pro('" . $toma_fisica . "','" . $tip_bus . "','" . $criterio . "','" . $i . "')\">" . $i . "</a></td>\n";
                }
                $columnas++;
            }
        }
        if ($pagina < $NumeroPaginas) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";      //$toma_fisica,$tip_bus,$criterio,$pagina
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_Pro('" . $toma_fisica . "','" . $tip_bus . "','" . $criterio . "','" . ($pagina + 1) . "')\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:Bus_Pro('" . $toma_fisica . "','" . $tip_bus . "','" . $criterio . "','" . $NumeroPaginas . "')\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
        $aviso .= "     P�ina&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
        $aviso .= "   </tr>\n";

        if ($op == 2) {
            $TablaPaginado .= $aviso;
        } else {
            $TablaPaginado = $aviso . $TablaPaginado;
        }
    }

    $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
    $Tabla .= $TablaPaginado;
    $Tabla .= "</table>";

    return $Tabla;
}

/* * **************************************************************
 * lapsos en el creardoc
 * *************************************************************** */

function ColocarDias($lapso, $div) {
    $objResponse = new xajaxResponse();
    //$objResponse->alert("Hay $lapso");
    $consulta = new TomaFisicaSQL();
    $anho = substr($lapso, 0, 4);
    $mes = substr($lapso, 4, 2);


    //$objResponse->alert("Hyy $anho");
    $dias = date("d", mktime(0, 0, 0, $mes + 1, 0, $anho));
    //$objResponse->alert("Hyy $dias");
    $salida = "                    <select name=\"mesito\" class=\"select\" onchange=\"limpiar()\">";
    $salida .="                      <option value=\"0\" selected>---</option> \n";
    for ($i = 1; $i <= $dias; $i++) {
        $salida .="                   <option value=\"" . $i . "\">" . $i . "</option> \n";
    }
    $salida .="                   </select>\n";
    $objResponse->assign($div, "innerHTML", $salida);
    return $objResponse;
}

function GuardarJefe($toma_fisica_id, $sw_jefebodega, $sw_jefecontroli, $empresa_id) {
    $objResponse = new xajaxResponse();
    //$objResponse->alert($torre);
    $mdl = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");
    $jefe = $mdl->Buscarparamprod($empresa_id, $toma_fisica_id);
    //print_r($jefe);
    $contar = count($jefe);
    //print_r($sw_jefebodega);
    // print_r($sw_jefecontroli);
    for ($i = 0; $i < $contar; $i++) {
        if ($jefe[$i]['toma_fisica_id'] == $toma_fisica_id) {
            $actualparam = $mdl->ActuParam($toma_fisica_id, $sw_jefebodega, $sw_jefecontroli, $empresa_id);
        }
    }
    if (empty($jefe)) {
        $GuardarParam = $mdl->GuardarParGrabar($toma_fisica_id, $sw_jefebodega, $sw_jefecontroli, $empresa_id);
    }

    return $objResponse;
}

function AjustesAutomaticos($formulario) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");
    $mensaje = $sql->AjustesAutomaticos($formulario);

    $objResponse->script("xajax_NoCuadraConteo" . trim($formulario['numero_conteos']) . "('" . trim($formulario['toma_fisica_id']) . "',xajax.getFormValues('FormaConteo" . trim($formulario['numero_conteos']) . "_NC'),'" . trim($formulario['numero_conteos']) . "','1');");
    $objResponse->alert($mensaje);
    return $objResponse;
}

  function bodega($empresa,$centro_utilidad){
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");
   
    $bodega = $sql->GetBodegas($empresa,$centro_utilidad);
     $html.= "BODEGA: <select id=\"bodega\" name=\"bodega\" class=\"select\" onchange=\"validar_cabecera_activa(this.value,0);\">";
     $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";    
     foreach($bodega as $key=>$value){
     $id=$value['bodega'];    
     $html.= "                           <option value=\"$id\">".utf8_encode($value['descripcion'])."</option> \n";
     }
     $html.= "                       </select>\n";
     $objResponse->assign("div_bodega","innerHTML", $html);
    return $objResponse;  
  }    
  
  function bodega_inventario($empresa,$centro_utilidad){
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");
    $bodega = $sql->GetBodegas($empresa,$centro_utilidad);
    $html.= "BODEGA: <select id=\"bodegas_select_lotes\" name=\"bodegas_select_lotes\" class=\"select\" onchange=\"verificar_cabecera('$centro_utilidad',this.value)\">";
    $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";    
    foreach($bodega as $key=>$value){
    $id=$value['bodega'];    
     $html.= "                           <option value=\"$id\">".$value['descripcion']."</option> \n";
     }
    $html.= "                       </select>\n";
    $objResponse->assign("div_bodega","innerHTML", $html);
    return $objResponse;  
  } 
  
  function verificar_cabecera_producto($centro_utilidad,$empresa){
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");
    $bodega = $sql->GetBodegas($empresa,$centro_utilidad);
    $html.= "BODEGA: <select id=\"bodegas_select_lotes\" name=\"bodegas_select_lotes\" class=\"select\" onchange=\"consultar_cabecera(this.value,'$empresa','$centro_utilidad')\">";
    $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";    
    foreach($bodega as $key=>$value){
    $id=$value['bodega'];    
     $html.= "                           <option value=\"$id\">".utf8_encode($value['descripcion'])."</option> \n";
     }
    $html.= "                       </select>\n";
    $objResponse->assign("div_bodega","innerHTML", $html);
    return $objResponse;  
  }   
  
       
  //trae la cabecera para la busqueda del producto
  function consultar_cabecera($bodega,$empresa,$centro_utilidad){
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");
    $consultarcabecera = $sql->ConsultarCabeceraActiva($empresa,$centro_utilidad,$bodega);
    if(sizeof($consultarcabecera)>0){
    $html1="<div>Nombre Cabecera: ".$consultarcabecera['descripcion']."</div>";
    $id=$consultarcabecera['id_conteo_toma_fisica'];
    $html.="<input type=\"hidden\" id=\"cabecera_id\" name=\"cabecera_id\" value='$id'>";
    $html.="<input type=\"hidden\" id=\"bodega_id\" name=\"bodega_id\" value='$bodega'>";
    $html.="<input type=\"hidden\" id=\"centro_id\" name=\"centro_id\" value='$centro_utilidad'>";
    $html.="<input type=\"hidden\" id=\"empresa_id\" name=\"empresa_id\" value='$empresa'>";
    $html.="<table>";
    $html.="<tr>";
    $html.="<td class=\"LABEL\">Codigo Producto:</td>";
    $html.="<td><input type=\"text\" size=\"45\" class=\"input-text\" name=\"codigo_producto\" id=\"codigo_producto\"></td>";
    $html.="</tr>";
    $html.="<tr>";
    $html.="<td class=\"LABEL\">Nombre Producto:</td>";
    $html.="<td><input type=\"text\" size=\"45\" class=\"input-text\" name=\"nombre_producto\" id=\"nombre_producto\"></td>";
    $html.="</tr>";    
    $html.="<tr>";  
//    $html.="<td class=\"LABEL\">Conteo:</td>";    
//    $html.="<td>";    
//    $html.= "<select id=\"conteo\" name=\"conteo\" class=\"select\" >";
//    $html.= "<option value=\"-1\" SELECTED>-seleccionar-</option> \n"; 
//    $html.= "<option value=\"1\">1</option> \n"; 
//    $html.= "<option value=\"2\">2</option> \n"; 
//    $html.= "<option value=\"3\">todos</option> \n"; 
//    $html.= "</select>\n";
//    $html.="</td>";
    $html.="</tr>";
    $html.="<tr>";
    $html.="<td align='center' colspan='2'></br><input type=\"button\" value=\"Buscar\" id='buscar' name='buscar' class=\"input-submit\" onclick=\"javascript:busqueda_producto();\"></br></br></td>";
    $html.="</tr>";
    $html.="</table>";
    $objResponse->assign("tabla","innerHTML",$html);
    }else{
        $html1="CABECERA INEXISTENTE";
    }
    $objResponse->assign("cabecera","innerHTML",$html1);
    return $objResponse;  
  }
  
  function bodega_informe($empresa,$centro_utilidad){
    $objResponse = new xajaxResponse();
    
    $sql = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");
    $bodega = $sql->GetBodegas($empresa,$centro_utilidad);
    $html.= "BODEGA: <select id=\"bodegas_select_lotes\" name=\"bodegas_select_lotes\" class=\"select\" onchange=\"consultar_cabecera_informe('$empresa','$centro_utilidad',this.value)\">";
    $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";    
    foreach($bodega as $key=>$value){
    $id=$value['bodega'];    
     $html.= "                           <option value=\"$id\">".utf8_encode($value['descripcion'])."</option> \n";
     }
    $html.= "                       </select>\n";
    $objResponse->assign("div_bodega","innerHTML", $html);
    return $objResponse;  
  }
  
  function consultar_cabecera_informe($empresa,$centro_utilidad,$bodega){
     $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");
     $consultarcabecera = $sql->ConsultarCabecera($empresa,$centro_utilidad,$bodega);
    
    $html.= "CABECERA: <select id=\"bobodegasgas\" name=\"bobodegasgas\" class=\"select\" onchange=\"consultar_informe('$empresa','$centro_utilidad','$bodega',this.value)\">";
    $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";    
    foreach($consultarcabecera as $key=>$value){
     $id=$value['id_conteo_toma_fisica'];    
     $html.= "                           <option value=\"$id\">".$value['descripcion']."</option> \n";
     }
    $html.= "                       </select>\n";
    $objResponse->assign("div_cabecera","innerHTML", $html);
    return $objResponse; 
  }
  
  function consultar_informe($empresa,$centro_utilidad,$bodega,$cabecera){
    $objResponse = new xajaxResponse();
    $buscar=ModuloGetURL('app','InvTomaFisica','user','Informe_Conteo',array("empresa"=>$empresa,"centro_utilidad"=>$centro_utilidad,"bodega"=>$bodega,"cabecera"=>$cabecera));
    $htmlc = "<a  title=\"Buscar\" class=\"label_error\" href=\"".$buscar."\">Buscar</a>\n";    
    $objResponse->assign("tabla_conteo","innerHTML",$htmlc);
    return $objResponse;  
  }
  
  function busqueda_producto($empresa,$centro_utilidad,$bodega,$cabecera_id,$nombre_producto,$codigo_producto,$conteo){
    $objResponse = new xajaxResponse();
    $buscar=ModuloGetURL('app','InvTomaFisica','user','Modificar_Producto',array("empresa"=>$empresa,"centro_utilidad"=>$centro_utilidad,"bodega"=>$bodega,"cabecera_id"=>$cabecera_id,"nombre_producto"=>$nombre_producto,"codigo_producto"=>$codigo_producto,"conteo"=>$conteo));
    $script="window.location = '$buscar';";
    $objResponse->script($script);
    return $objResponse;  
  }
  
  
  /*Funcion para actualizar el detallado de un conteo*/
  function modificarProducto($empresa,$centro_utilidad,$bodega,$cabecera_id,$codigo_producto,$conteo,$cantidad,$lote,$fecha,$key){
      
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");
    $insertocabecera = $sql->modificarProducto($empresa,$centro_utilidad,$bodega,$cabecera_id,$codigo_producto,$conteo,$cantidad,$lote,$fecha,$key);
     if($insertocabecera){
         $objResponse->alert('El Producto '.$codigo_producto.' se Modifico Correctamente');
     }
     return $objResponse;  
  }
  
  
  function Guardar_cabecera($empresa,$nombre,$centro_utilidad,$bodega){
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");
    $insertocabecera = $sql->InsertarCabecera($empresa,$nombre,$centro_utilidad,$bodega);
     $id=$insertocabecera->fields[0];
    if($id>0){
       $objResponse->script("document.getElementById('crear_cabecera').disabled=true");
       $html="Se Guardo Correctamente la Cabecera ".$nombre;
       $html_s="<div><b>Nombre Cabecera:</b> ".$nombre."</div>";
       $html_s.="<input type=\"hidden\" id=\"cabe_id\" name=\"cabe_id\" value='$id'>";
       $html_s.="<input type=\"hidden\" id=\"bodega_id\" name=\"bodega_id\" value='$bodega'>";
       $html_s.="<input type=\"hidden\" id=\"centro_id\" name=\"centro_id\" value='$centro_utilidad'>";
       $objResponse->assign("mensaje_cabecera","innerHTML", $html_s); 
    }else{
        $html="Error al guardar la cabecera ".$nombre;
    }
    $objResponse->assign("mensaje","innerHTML", $html);
    return $objResponse; 
  }
  function validar_cabecera_activa($bodega,$centro_utilidad,$bandera){
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");
    $empresa=SessionGetVar("EMPRESA");
    $consultarcabecera = $sql->ConsultarCabeceraActiva($empresa,$centro_utilidad,$bodega);
    if(sizeof($consultarcabecera)>0){
       
       $html="<div><b>Nombre Cabecera:</b> ".$consultarcabecera['descripcion']."</div>";
       $id=$consultarcabecera['id_conteo_toma_fisica'];
       $html.="<input type=\"hidden\" id=\"cabe_id\" name=\"cabe_id\" value='$id'>";
       $html.="<input type=\"hidden\" id=\"bodega_id\" name=\"bodega_id\" value='$bodega'>";
       $html.="<input type=\"hidden\" id=\"centro_id\" name=\"centro_id\" value='$centro_utilidad'>";
       if($bandera==0){
        $objResponse->script('document.getElementById("crear_cabecera").disabled = true;');
        $objResponse->assign("mensaje_cabecera","innerHTML", $html);
       }else{
          $objResponse->assign("mensaje_cabecera","innerHTML", $html); 
       }
    }else{
       $objResponse->script('document.getElementById("crear_cabecera").disabled = false;');
       $objResponse->assign("mensaje","innerHTML",'');
       $objResponse->assign("mensaje_cabecera","innerHTML", ''); 
    }
    return $objResponse; 
  }
  function VerificarCabecera($centro,$bodega){
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");
    $empresa=SessionGetVar("EMPRESA");
   // $centro_utilidad=$bodega;//mientras ejemplo
    $consultarcabecera = $sql->ConsultarCabeceraActiva($empresa,$centro,$bodega);
    if(sizeof($consultarcabecera)>0){
        $id=$consultarcabecera['id_conteo_toma_fisica'];
        $nombrecabe=$consultarcabecera['descripcion'];
        $html="<div><b>Nombre Cabecera:</b> ".$consultarcabecera['descripcion']."</div>"; 
        $html.="<input type=\"hidden\" id=\"cabe_id\" name=\"cabe_id\" value='$id'>";
        $html.="<input type=\"hidden\" id=\"nombrecabe\" name=\"nombrecabe\" value='$nombrecabe'>";
         $objResponse->script('document.getElementById("botonAjuste").disabled = false;');
    }else{
        $html="<div><b>NO TIENE CABECERA ACTIVA</b></div>";
        $objResponse->script('document.getElementById("botonAjuste").disabled = true;');
    }
    $objResponse->assign("mensaje","innerHTML",$html); 
    return $objResponse; 
  }
  
  function InsertarProducto($centro_utilidad,$bodega,$id_conteo_toma_fisica,$codigo_producto,$lote,$cantidad,$fecha_vencimiento,$conteo){
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");    
    $empresa=SessionGetVar("EMPRESA");;
    if($conteo==3){
        $conteos_creados=2;
    }else{
        $conteos_creados=1;
    }
    
       for($i=0;$i<$conteos_creados;$i++){
        $con=$conteos_creados==1?$conteo:($i+1);  
        $add=$sql->Guardar_Detalle_inventario($empresa,$centro_utilidad,$bodega,$id_conteo_toma_fisica,$codigo_producto,$cantidad,
                                         $lote,$fecha_vencimiento,UserGetUID(),"now()",$con);
       }   
   
        if(!$add){
            $objResponse->alert('ERROR AL INGRESAR EL PRODUCTO '.$codigo_producto);
        }else{
            $script="
            document.getElementById('codigo_producto_insert').value='';
            document.getElementById('lote_insert').value='';
            document.getElementById('cantidad_insert').value='';
            document.getElementById('fecha_insert').value='';";
            $objResponse->script($script);
            $objResponse->alert("SE GUARDO EL PRODUCTO ".$codigo_producto." CORRECTAMENTE");
        }
    
    return $objResponse; 
  }

?>