<?php
	/**************************************************************************************
	* $Id: definirRec.php,v 1.2 2010/03/29 16:21:23 sandra Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Jaime gomez
	**************************************************************************************/	
	
$VISTA = "HTML";
$_ROOT = "../../../";
include "../app_modules/RecibosCaja/classes/RecaudoElectronico.class.php";
include "../../../classes/ClaseHTML/ClaseHTML.class.php";
/************************************************************************************
*funcion que sirvw para la creacion de centros de costo
************************************************************************************/  
 function CreateCent()
 {
   $objResponse = new xajaxResponse();
   $path = SessionGetVar("rutaImagenes");
   $registrar=new CentrosSQL(); 
   //$salida .= "            <form name=\"cre_cent\">\n";
   $salida = "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
   $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
   $salida .= "                      <td colspan='2' align=\"center\"width=\"80%\">\n";
   $salida .= "                        NUEVO CENTRO DE COSTO";
   $salida .= "                      </td>\n";
   $salida .= "                    </tr>\n";         
   $salida .= "                    <tr class=\"modulo_list_claro\">\n";
   $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
   $salida .= "                       CENTRO DE COSTO ID";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"left\">\n";
   $salida .= "                       <input type=\"text\" class=\"input-text\" name=\"cent_id\" id=\"cent_id\"  size=\"12\" >\n";
   $salida .= "                      </td>\n";
   $salida .= "                    </tr>\n";
   $salida .= "                    <tr class=\"modulo_list_claro\">\n";
   $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
   $salida .= "                       NOMBRE";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"left\">\n";
   $salida .= "                          <input type=\"text\" class=\"input-text\" name=\"nue_cent\" id=\"nue_cent\"  size=\"25\" >\n";
   $salida .= "                      </td>\n";
   $salida .= "                    </tr>\n";
   $salida .= "                    <tr class=\"modulo_list_claro\">\n";
   $salida .= "                      <td align=\"center\" colspan='2'>\n";
   $salida .= "                          <input type=\"button\" class=\"input-submit\" value=\"ACEPTAR\" onclick=\"xajax_Buscar_Cen('1','".SessionGetVar("EMPRESA")."','1','',document.getElementById('nue_cent').value,document.getElementById('cent_id').value);Cerrar('ContenedorCent');\">\n";
   $salida .= "                      </td>\n";                                                                        //Buscar_Cen($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)
   $salida .= "                    </tr>\n";
   $salida .= "                </table>\n";        
  //$salida .= "             </form>\n";                
   $objResponse->assign("ContenidoCent","innerHTML",$salida);  
   return $objResponse;
 }
 
function GuardarBD($empresa_id,$centro_utilidad,$recibo_caja,$datos)
{
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    //var_dump($datos);
    $registrar=new RecaudoElectronico(); 
    $resultado=$registrar->GuardarTmp($empresa_id,$centro_utilidad,$recibo_caja,$datos);
    $objResponse->assign("DIVIGO","innerHTML",$resultado);  
    return $objResponse;
}

function GuardarBD1($empresa_id,$centro_utilidad,$recibo_caja,$datos)
{
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    //var_dump($datos);
    $registrar=new RecaudoElectronico(); 
    $resultado=$registrar->GuardarTmp($empresa_id,$centro_utilidad,$recibo_caja,$datos);
    $objResponse->assign("DIVDIF","innerHTML",$resultado);  
    return $objResponse;
}

function BorrarBD($id)
{
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    //var_dump($datos);
    $registrar=new RecaudoElectronico(); 
    $resultado=$registrar->EliminarFacturaTmp($id);
    $objResponse->assign("DIVIGO","innerHTML",$resultado);  
    return $objResponse;
}

function BorrarBD1($id)
{
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    //var_dump($datos);
    $registrar=new RecaudoElectronico(); 
    $resultado=$registrar->EliminarFacturaTmp($id);
    $objResponse->assign("DIVDIF","innerHTML",$resultado);  
    return $objResponse;
}

function GuardarConceptBD($datos)
{
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    //var_dump($datos);
    $registrar=new RecaudoElectronico(); 
    $resultado=$registrar->GuardarConceptosTmp($datos);
    $objResponse->assign("resultado_conceptos","innerHTML",$resultado);
    return $objResponse;


}


function TablaConceptos($tipo_id_tercero,$empresa_id,$centro_de_utilidad,$vec_con,$tercero_id,$tmp_recibo_id)
{

    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    //var_dump($vec_con);
    $registrar=new RecaudoElectronico(); 
    $vec_con=$registrar->Consecutivos($tercero_id);
    if(!empty($vec_con))
    {
    $vector_concepto=$registrar->ObtenerListConceptos($vec_con,$tercero_id,$tmp_recibo_id);


    $salida .= "                  <table  width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
    $salida .= "                    <tr class=\"formulacion_table_list\">\n";
    $salida .= "                      <td colspan=2 align=\"center\">\n";
    $salida .= "                       LISTADO DE CONCEPTOS";
    $salida .= "                      </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                  </table>\n";
    $salida .= "               <table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
    $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
    $salida .= "                           <td width=\"10%\" align=\"center\">\n";
    $salida .= "                               CONSECUTIVO";
    $salida .= "                           </td>";
    $salida .= "                           <td width=\"20%\"  align=\"center\" >\n";
    $salida .= "                               TERCERO";
    $salida .= "                           </td>";
    $salida .= "                           <td width=\"30%\"  align=\"center\" >\n";
    $salida .= "                               CONCEPTO";
    $salida .= "                           </td>";
    $salida .= "                           <td width=\"20%\"  align=\"center\" >\n";
    $salida .= "                               VALOR";
    $salida .= "                           </td>";
    $salida .= "                           <td width=\"5%\" align=\"center\">\n";
    $salida .= "                              <input type=\"checkbox\" name=\"concept_mark\" value=\"\" onclick=\"javaScript:SeleccionarTodosConceptos(this.checked);\">";
    $salida .= "                           </td>";
    $salida .= "                           <td width=\"5%\" align=\"center\">\n";
    $salida .= "                              <input type=\"checkbox\" name=\"concept_erase\" value=\"\" onclick=\"javaScript:SeleccionarTodosConceptosDown(this.checked);\">";
    $salida .= "                           </td>";
    $salida .= "                       </tr>";

    
    for($i=0;$i<count($vector_concepto);$i++)
    {   //num_consecutivo tercero concepto  valor
        $salida .= "                     <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
        $salida .= "                       <td  align=\"center\">\n";
        $salida .= "                        ".$vector_concepto[$i]['num_consecutivo']."";
        $salida .= "                       </td>";
        $salida .= "                       <td align=\"left\">\n";
        $salida .= "                         ".$tipo_id_tercero."-".$tercero_id;
        $salida .= "                       </td>";
        $conceptosxxx=$registrar->SacarConceptos($vector_concepto[$i]['concepto']);
        $salida .= "                       <td align=\"left\">\n";
        $salida .= "                        ".$conceptosxxx['concepto_id']."-".$conceptosxxx['descripcion'];
        $salida .= "                       </td>";
        $salida .= "                       <td align=\"right\">\n";
        $salida .= "                        ".FormatoValor($vector_concepto[$i]['valor']);
        $salida .= "                       </td>";

       if($vector_concepto[$i]['tmp_id'] == '-1')
       {
          $check="conceptoup"; 
          $salida .= "                       <td align=\"center\">\n";
          $salida .= "                         <input type=\"checkbox\" name=\"".$check."\" value=\"".$empresa_id."@".$centro_de_utilidad."@".$conceptosxxx['concepto_id']."@".$conceptosxxx['sw_naturaleza']."@".$vector_concepto[$i]['valor']."@".$tmp_recibo_id."\" onclick=\"\">";
          $salida .= "                       </td>";
          $salida .= "                       <td align=\"center\">\n";
          $salida .= "                         &nbsp;";
          $salida .= "                       </td>";

          
       }
       elseif($vector_concepto[$i]['tmp_id'] != '-1')
       {
          $check="conceptodown"; 

          $salida .= "                       <td align=\"center\">\n";
          $salida .= "                         &nbsp;";
          $salida .= "                       </td>";
          $salida .= "                       <td align=\"center\">\n";
          $salida .= "                         <input type=\"checkbox\" name=\"".$check."\" value=\"".$vector_concepto[$i]['tmp_id']."\" onclick=\"\">";
          $salida .= "                       </td>";
       }

        $salida .= "                     </tr>";
    }

      $salida .= "                     <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
      $salida .= "                       <td COLSPAN='4' align=\"center\">\n";
      $salida .= "                         &nbsp;\n";
      $salida .= "                       </td>";//
      $salida .= "                       <td align=\"center\">\n";
      $salida .= "                           <input type=\"button\" class=\"input-submit\" name=\"concepto\" value=\"CRUZAR\" onclick=\"AgruparPendientesConcepto();\">\n";
      $salida .= "                       </td>";//
      $salida .= "                       <td align=\"center\">\n";
      $salida .= "                           <input type=\"button\" class=\"input-submit\" name=\"concepto\" value=\"BORRAR\" onclick=\"AgruparConceptoBorrar();\">\n";
      $salida .= "                       </td>";//
      $salida .= "                     </tr>";
      $salida .= "                  </table>";


  } 

    $objResponse->assign("ventana_de_conceptos","innerHTML",$salida);
    return $objResponse;
}






function EliminarConceptBD($datos)
{
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    //var_dump($datos);
    $registrar=new RecaudoElectronico(); 
    $resultado=$registrar->EliminarConceptosTmp($datos);
    $objResponse->assign("resultado_conceptos","innerHTML",$resultado);
    return $objResponse;
}
/**************************************************************************************************
*mayores que cero
***********************************************************************************************/

function RevisarFacturasM($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,$tmp_recibo_id,$centro_de_utilidad)
{
        $objResponse = new xajaxResponse();      
        $consulta=new RecaudoElectronico();
        $path = SessionGetVar("rutaImagenes");
       

$vector23=$consulta->Obtener_Recaudo($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo = cartera.valor_neto",$tmp_recibo_id);
var_dump($vector23);
if(!empty($vector23))
{
        
  
  $salida .= "                  <table  width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";         
  $salida .= "                    <tr class=\"formulacion_table_list\">\n";
  $salida .= "                      <td colspan=2 align=\"center\">\n";
  $salida .= "                       FACTURAS CON DIFERENCIA IGUAL A CERO(0)";
  $salida .= "                      </td>\n";
  $salida .= "                    </tr>\n";
  $salida .= "                  </table>\n";
  
  $salida .= "      <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";         
  $salida .= "                     <tr class=\"modulo_table_list_title\">\n";
  $salida .= "                           <td width=\"11%\" align=\"left\">\n";
  $salida .= "                               FACTURA";
  $salida .= "                           </td>";
  $salida .= "                          <td width=\"9%\" align=\"left\" >\n";
  $salida .= "                               FECHA";
  $salida .= "                           </td>";
  $salida .= "                           <td width=\"15%\"  align=\"right\" >\n";
  $salida .= "                               TOTAL FACTURA";
  $salida .= "                           </td>";
  $salida .= "                           <td width=\"15%\"  align=\"right\" >\n";
  $salida .= "                               SALDO";
  $salida .= "                           </td>";
  $salida .= "                           <td width=\"15%\"  align=\"right\" >\n";
  $salida .= "                               VALOR NETO";
  $salida .= "                           </td>";
  $salida .= "                           <td width=\"15%\" align=\"right\" >\n";
  $salida .= "                               PENDIENTE";
  $salida .= "                           </td>";
  $salida .= "                           <td width=\"10%\" align=\"center\">\n";
  $salida .= "                              <input type=\"checkbox\" name=\"iguales_g1\" value=\"\" onclick=\"javaScript:SeleccionarTodos(this.checked);\">";    
  $salida .= "                           </td>";
  $salida .= "                           <td width=\"10%\" align=\"center\">\n";
  $salida .= "                              <input type=\"checkbox\" name=\"iguales_b1\" value=\"\"onclick=\"javaScript:SeleccionarTodos10(this.checked);\">";    
  $salida .= "                           </td>";
  $salida .= "                       </tr>";
  
  
  for($i=0;$i<count($vector23);$i++)
  {
       $salida .= "                     <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
       $salida .= "                       <td  align=\"left\">\n";
       $salida .= "                        ".$vector23[$i]['prefijo']."-".$vector23[$i]['factura_fiscal'];
       $salida .= "                       </td>";
       $salida .= "                       <td align=\"left\">\n";
       $salida .= "                        ".substr($vector23[$i]['fecha'],0,10);
       $salida .= "                       </td>";
       $salida .= "                       <td align=\"right\">\n";
       $salida .= "                        ".FormatoValor($vector23[$i]['total_factura']);
       $salida .= "                       </td>";
       $salida .= "                       <td align=\"right\">\n";
       $salida .= "                        ".FormatoValor($vector23[$i]['saldo']);
       $salida .= "                       </td>";
       $salida .= "                       <td align=\"right\">\n";
       $salida .= "                        ".FormatoValor($vector23[$i]['valor_neto']);
       $salida .= "                       </td>";
       $salida .= "                       <td align=\"right\">\n";
       $salida .= "                        ".FormatoValor($vector23[$i]['pendiente']);
       $salida .= "                       </td>";
       
        if($vector23[$i]['valor_cruzado'] > 0)
       {
          $check="IGB"; 
    $salida .= "                       <td align=\"center\">\n";
        $salida .= "                         &nbsp;";    
        $salida .= "                       </td>";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                         <input type=\"checkbox\" name=\"".$check."\" value=\"".$vector23[$i]['tmp_id']."\" onclick=\"\">";
        $salida .= "                       </td>";
        $salida .= "                     </tr>";
       }
       elseif($vector23[$i]['valor_cruzado'] == 0)
       {
        $check="IGG";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                         <input type=\"checkbox\" name=\"".$check."\" value=\"".$vector23[$i]['prefijo']."@".$vector23[$i]['factura_fiscal']."@".$vector23[$i]['valor_neto']."\" onclick=\"\">";
        $salida .= "                       </td>";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                         &nbsp;";    
        $salida .= "                       </td>";
       
       }
             $salida .= "                     </tr>";
  }
        $vectorTF=$consulta->Obtener_Recaudo_Total_facturas($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo = cartera.valor_neto",$tmp_recibo_id); 
        $vectorsaldo=$consulta->Obtener_Recaudo_Total_Saldo($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo = cartera.valor_neto",$tmp_recibo_id); 
        $vectorNeto=$consulta->Obtener_Recaudo_Total_Neto($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo = cartera.valor_neto",$tmp_recibo_id); 
        $vectorpendiente=$consulta->Obtener_Recaudo_Total_Pendiente($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo = cartera.valor_neto",$tmp_recibo_id); 
       $salida .= "                     <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
       $salida .= "                       <td  COLSPAN='2' align=\"right\">\n";
       $salida .= "                       <label class='label_error'>TOTAL</label>";
       $salida .= "                       </td>";
       $salida .= "                       <td align=\"right\">\n";
       $salida .= "                        ".FormatoValor($vectorTF[0]['total_facturas']);
       $salida .= "                       </td>";
       $salida .= "                       <td align=\"right\">\n";
       $salida .= "                        ".FormatoValor($vectorsaldo[0]['total_saldos']);
       $salida .= "                       </td>";
       $salida .= "                       <td align=\"right\">\n";
       $salida .= "                        ".FormatoValor($vectorNeto[0]['total_neto']);
       $salida .= "                       </td>";
       $salida .= "                       <td align=\"right\">\n";
       if($vectorpendiente[0]['total_pendientes'] < 0)
       {
        $salida .= "                        <label class=\"label_error\">".FormatoValor($vectorpendiente[0]['total_pendientes'])."</label>";
       }
       else
       {
        $salida .= "                        <label>".FormatoValor($vectorpendiente[0]['total_pendientes'])."</label>";
       }
       
       $salida .= "                       </td>";
       $salida .= "                       <td align=\"center\">\n";
       $salida .= "                           <input type=\"button\" class=\"input-submit\" value=\"CRUZAR\" onclick=\"AgruparPendientesIgCero('".$empresa_id."','".$centro_de_utilidad."','".$tmp_recibo_id."');\">\n";
       $salida .= "                       </td>";
       $salida .= "                       <td align=\"center\">\n";
       $salida .= "                           <input type=\"button\" class=\"input-submit\" value=\"BORRAR\" onclick=\"AgruparPendientesIgCeroBorrar();\">\n";
       $salida .= "                       </td>";
       $salida .= "                     </tr>";
       $salida .= "                    </table>";
             $salida .= "                  <br>";
       
       $objResponse->assign("ventana_de_iguales","innerHTML",$salida);
       
       $objResponse->alert("TABLA ACTUALIZADA FACTURAS IGUALES DE CERO(0)");
    
    }    
    else
    { 
      $objResponse->assign("ventana_de_iguales","innerHTML",$vector23);
    }
    return $objResponse;
}        
/***********************************************************************************************
*
***************************************************************************************************/
function RevisarFacturas($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,$tmp_recibo_id,$centro_de_utilidad)
    { 
        $objResponse = new xajaxResponse();      
        $consulta=new RecaudoElectronico();
        $path = SessionGetVar("rutaImagenes");
       
/************************************************************************************
*iguales a cero
**************************************************************************************/
    
$vector23=$consulta->Obtener_Recaudo($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo = cartera.valor_neto",$tmp_recibo_id);
//var_dump($vector23);
if(!empty($vector23))
{
        
	
	$salida .= "                  <table  width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";         
	$salida .= "                    <tr class=\"formulacion_table_list\">\n";
	$salida .= "                      <td colspan=2 align=\"center\">\n";
	$salida .= "                       FACTURAS CON DIFERENCIA IGUAL A CERO(0)";
	$salida .= "                      </td>\n";
	$salida .= "                    </tr>\n";
	$salida .= "                  </table>\n";
	
	$salida .= "			<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";         
	$salida .= "          	         <tr class=\"modulo_table_list_title\">\n";
	$salida .= "                           <td width=\"11%\" align=\"left\">\n";
	$salida .= "                               FACTURA";
	$salida .= "                           </td>";
	$salida .= "                          <td width=\"9%\" align=\"left\" >\n";
	$salida .= "                               FECHA";
	$salida .= "                           </td>";
	$salida .= "                           <td width=\"15%\"  align=\"right\" >\n";
	$salida .= "                               TOTAL FACTURA";
	$salida .= "                           </td>";
	$salida .= "                           <td width=\"15%\"  align=\"right\" >\n";
	$salida .= "                               SALDO";
	$salida .= "                           </td>";
	$salida .= "                           <td width=\"15%\"  align=\"right\" >\n";
	$salida .= "                               VALOR NETO";
	$salida .= "                           </td>";
	$salida .= "                           <td width=\"15%\" align=\"right\" >\n";
	$salida .= "                               PENDIENTE";
	$salida .= "                           </td>";
	$salida .= "                           <td width=\"10%\" align=\"center\">\n";
	$salida .= "                              <input type=\"checkbox\" name=\"iguales_g1\" value=\"\" onclick=\"javaScript:SeleccionarTodos(this.checked);\">";    
	$salida .= "                           </td>";
	$salida .= "                           <td width=\"10%\" align=\"center\">\n";
	$salida .= "                              <input type=\"checkbox\" name=\"iguales_b1\" value=\"\"onclick=\"javaScript:SeleccionarTodos10(this.checked);\">";    
	$salida .= "                           </td>";
	$salida .= "                       </tr>";
	
	
	for($i=0;$i<count($vector23);$i++)
	{
	     $salida .= "                     <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
	     $salida .= "                       <td  align=\"left\">\n";
	     $salida .= "                        ".$vector23[$i]['prefijo']."-".$vector23[$i]['factura_fiscal'];
	     $salida .= "                       </td>";
	     $salida .= "                       <td align=\"left\">\n";
	     $salida .= "                        ".substr($vector23[$i]['fecha'],0,10);
	     $salida .= "                       </td>";
	     $salida .= "                       <td align=\"right\">\n";
	     $salida .= "                        ".FormatoValor($vector23[$i]['total_factura']);
	     $salida .= "                       </td>";
	     $salida .= "                       <td align=\"right\">\n";
	     $salida .= "                        ".FormatoValor($vector23[$i]['saldo']);
	     $salida .= "                       </td>";
	     $salida .= "                       <td align=\"right\">\n";
	     $salida .= "                        ".FormatoValor($vector23[$i]['valor_neto']);
	     $salida .= "                       </td>";
	     $salida .= "                       <td align=\"right\">\n";
	     $salida .= "                        ".FormatoValor($vector23[$i]['pendiente']);
	     $salida .= "                       </td>";
	     
	      if($vector23[$i]['valor_cruzado'] > 0)
	     {
	        $check="IGB"; 
		$salida .= "                       <td align=\"center\">\n";
	     	$salida .= "                         &nbsp;";    
	     	$salida .= "                       </td>";
	     	$salida .= "                       <td align=\"center\">\n";
	     	$salida .= "                         <input type=\"checkbox\" name=\"".$check."\" value=\"".$vector23[$i]['tmp_id']."\" onclick=\"\">";
	     	$salida .= "                       </td>";
	     	$salida .= "                     </tr>";
	     }
	     elseif($vector23[$i]['valor_cruzado'] == 0)
	     {
	     	$check="IGG";
		    $salida .= "                       <td align=\"center\">\n";
	     	$salida .= "                         <input type=\"checkbox\" name=\"".$check."\" value=\"".$vector23[$i]['prefijo']."@".$vector23[$i]['factura_fiscal']."@".$vector23[$i]['valor_neto']."\" onclick=\"\">";
	     	$salida .= "                       </td>";
	    	$salida .= "                       <td align=\"center\">\n";
	     	$salida .= "                         &nbsp;";    
	     	$salida .= "                       </td>";
	     
	     }
             $salida .= "                     </tr>";
	}
 	     $vectorTF=$consulta->Obtener_Recaudo_Total_facturas($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo = cartera.valor_neto",$tmp_recibo_id); 
 	     $vectorsaldo=$consulta->Obtener_Recaudo_Total_Saldo($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo = cartera.valor_neto",$tmp_recibo_id); 
 	     $vectorNeto=$consulta->Obtener_Recaudo_Total_Neto($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo = cartera.valor_neto",$tmp_recibo_id); 
 	     $vectorpendiente=$consulta->Obtener_Recaudo_Total_Pendiente($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo = cartera.valor_neto",$tmp_recibo_id); 
	     $salida .= "                     <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
	     $salida .= "                       <td  COLSPAN='2' align=\"right\">\n";
	     $salida .= "                       <label class='label_error'>TOTAL</label>";
	     $salida .= "                       </td>";
	     $salida .= "                       <td align=\"right\">\n";
	     $salida .= "                        ".FormatoValor($vectorTF[0]['total_facturas']);
	     $salida .= "                       </td>";
	     $salida .= "                       <td align=\"right\">\n";
	     $salida .= "                        ".FormatoValor($vectorsaldo[0]['total_saldos']);
	     $salida .= "                       </td>";
	     $salida .= "                       <td align=\"right\">\n";
	     $salida .= "                        ".FormatoValor($vectorNeto[0]['total_neto']);
	     $salida .= "                       </td>";
	     $salida .= "                       <td align=\"right\">\n";
	     if($vectorpendiente[0]['total_pendientes'] < 0)
	     {
	      $salida .= "                        <label class=\"label_error\">".FormatoValor($vectorpendiente[0]['total_pendientes'])."</label>";
	     }
	     else
	     {
	      $salida .= "                        <label>".FormatoValor($vectorpendiente[0]['total_pendientes'])."</label>";
	     }
	     
	     $salida .= "                       </td>";
	     $salida .= "                       <td align=\"center\">\n";
	     $salida .= "                           <input type=\"button\" class=\"input-submit\" value=\"CRUZAR\" onclick=\"AgruparPendientesIgCero('".$empresa_id."','".$centro_de_utilidad."','".$tmp_recibo_id."');\">\n";
	     $salida .= "                       </td>";
	     $salida .= "                       <td align=\"center\">\n";
	     $salida .= "                           <input type=\"button\" class=\"input-submit\" value=\"BORRAR\" onclick=\"AgruparPendientesIgCeroBorrar();\">\n";
	     $salida .= "                       </td>";
	     $salida .= "                     </tr>";
	     $salida .= "                    </table>";
             $salida .= "                  <br>";
	     
	     $objResponse->assign("ventana_de_iguales","innerHTML",$salida);
	     $objResponse->alert("TABLA ACTUALIZADA FACTURAS IGUALES DE CERO(0)");
    
    }
    else
    { 
      $objResponse->assign("ventana_de_iguales","innerHTML",$vector23);
    }    
    
    return $objResponse;
}        

function RevisarFacturas1($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,$tmp_recibo_id,$centro_de_utilidad)
{ 
        $objResponse = new xajaxResponse();      
        $consulta= new RecaudoElectronico();
        $path = SessionGetVar("rutaImagenes");

/*************************************************************************************
*diferentes a cero
*************************************************************************************/
     $vector=$consulta->Obtener_Recaudo($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo != cartera.valor_neto",$tmp_recibo_id); 
     //var_dump($vector);
     if(!empty($vector))
     {
 	
 	$salida .= "                  <table  width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";         
 	$salida .= "                    <tr class=\"formulacion_table_list \">\n";
 	$salida .= "                      <td colspan=2 align=\"center\">\n";
 	$salida .= "                       FACTURAS CON DIFERENCIA DISTINTA DE CERO(0)";
 	$salida .= "                      </td>\n";
 	$salida .= "                    </tr>\n";
 	$salida .= "                  </table>\n";
 	$salida .= "			<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";         
 	$salida .= "          	     <tr class=\"modulo_table_list_title\">\n";
 	$salida .= "                           <td width=\"11%\" align=\"left\">\n";
 	$salida .= "                               FACTURA";
 	$salida .= "                           </td>";
 	$salida .= "                    	 <td width=\"9%\" align=\"left\" >\n";
 	$salida .= "                               FECHA";
 	$salida .= "                           </td>";
 	$salida .= "                           <td width=\"15%\"  align=\"right\" >\n";
 	$salida .= "                               TOTAL FACTURA";
 	$salida .= "                           </td>";
	$salida .= "                           <td width=\"15%\"  align=\"right\" >\n";
	$salida .= "                               SALDO";
 	$salida .= "                           </td>";
 	$salida .= "                           <td width=\"15%\"  align=\"right\" >\n";
 	$salida .= "                               VALOR NETO";
 	$salida .= "                           </td>";
 	$salida .= "                           <td width=\"15%\" align=\"right\" >\n";
 	$salida .= "                               PENDIENTE";
 	$salida .= "                           </td>";
 	$salida .= "                           <td width=\"10%\" align=\"center\" >\n";
 	$salida .= "                              <input type=\"checkbox\" name=\"todos_g1\" value=\"\" onclick=\"javaScript:SeleccionarTodos30(this.checked);\">";    
 	$salida .= "                           </td>";
 	$salida .= "                           <td width=\"10%\" align=\"center\" >\n";
 	$salida .= "                              <input type=\"checkbox\" name=\"todos_b1\" value=\"\" onclick=\"javaScript:SeleccionarTodos40(this.checked);\">";    
 	$salida .= "                           </td>";
 	$salida .= "                       </tr>";
 	
 	for($i=0;$i<count($vector);$i++)
 	{
 	     $salida .= "                     <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
 	     $salida .= "                       <td  align=\"left\">\n";
 	     $salida .= "                        ".$vector[$i]['prefijo']."-".$vector[$i]['factura_fiscal'];
 	     $salida .= "                       </td>";
 	     $salida .= "                       <td align=\"left\">\n";
 	     $salida .= "                        ".substr($vector[$i]['fecha'],0,10);
 	     $salida .= "                       </td>";
 	     $salida .= "                       <td align=\"right\">\n";
 	     $salida .= "                        ".FormatoValor($vector[$i]['total_factura']);
 	     $salida .= "                       </td>";
 	     $salida .= "                       <td  align=\"right\">\n";
 	     $salida .= "                        ".FormatoValor($vector[$i]['saldo']);
 	     $salida .= "                       </td>";
 	     $salida .= "                       <td  align=\"right\">\n";
 	     $salida .= "                        ".FormatoValor($vector[$i]['valor_neto']);
 	     $salida .= "                       </td>";
 	     $salida .= "                       <td   align=\"right\">\n";
 	     if($vector[$i]['pendiente'] < 0)
 	     {
 	      $salida .= "                        <label class=\"label_error\">".FormatoValor($vector[$i]['pendiente'])."</label>";
 	     }
 	     else
 	     {
 	      $salida .= "                        <label>".FormatoValor($vector[$i]['pendiente'])."</label>";
 	     }
 	     
 	     $salida .= "                       </td>";
 	     
 	     
 	     if($vector[$i]['valor_cruzado'] > 0)
 	     {
 	        $check="DFB"; 
		$salida .= "                       <td align=\"center\">\n";
 	     	$salida .= "                         &nbsp;";    
	     	$salida .= "                       </td>";
 	     	$salida .= "                       <td align=\"center\">\n";
 	     	$salida .= "                         <input type=\"checkbox\" name=\"".$check."\" value=\"".$vector[$i]['tmp_id']."\">";    
 	     	$salida .= "                       </td>";
 	     	$salida .= "                     </tr>";
 	     }
 	     elseif($vector[$i]['valor_cruzado'] == 0)
 	     {
 	       	$check="DFG"; 
		    $salida .= "                       <td align=\"center\">\n";
 	     	$salida .= "                         <input type=\"checkbox\" name=\"".$check."\" value=\"".$vector[$i]['prefijo']."@".$vector[$i]['factura_fiscal']."@".$vector[$i]['valor_neto']."\">";    
 	     	$salida .= "                       </td>";
 	    	$salida .= "                       <td align=\"center\">\n";
 	     	$salida .= "                         &nbsp;";    
 	     	$salida .= "                       </td>";
 	     
 	     }
 	     
 	     $salida .= "                     </tr>";
 	}
  	     $vectorTF=$consulta->Obtener_Recaudo_Total_facturas($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo != cartera.valor_neto",$tmp_recibo_id); 
  	     $vectorsaldo=$consulta->Obtener_Recaudo_Total_Saldo($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo != cartera.valor_neto",$tmp_recibo_id); 
  	     $vectorNeto=$consulta->Obtener_Recaudo_Total_Neto($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo != cartera.valor_neto",$tmp_recibo_id); 
  	     $vectorpendiente=$consulta->Obtener_Recaudo_Total_Pendiente($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,"AND con_ff.saldo != cartera.valor_neto",$tmp_recibo_id); 
 	     $salida .= "                     <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
 	     $salida .= "                       <td  COLSPAN='2' align=\"right\">\n";
 	     $salida .= "                       <label class='label_error'>TOTAL</label>";
 	     $salida .= "                       </td>";
 	     $salida .= "                       <td align=\"right\">\n";
 	     $salida .= "                        ".FormatoValor($vectorTF[0]['total_facturas']);
 	     $salida .= "                       </td>";
	     $salida .= "                       <td align=\"right\">\n";
 	     $salida .= "                        ".FormatoValor($vectorsaldo[0]['total_saldos']);
 	     $salida .= "                       </td>";
 	     $salida .= "                       <td align=\"right\">\n";
 	     $salida .= "                        ".FormatoValor($vectorNeto[0]['total_neto']);
 	     $salida .= "                       </td>";
 	     $salida .= "                       <td align=\"right\">\n";
 	     if($vectorpendiente[0]['total_pendientes'] < 0)
 	     {
 	      $salida .= "                        <label class=\"label_error\">".FormatoValor($vectorpendiente[0]['total_pendientes'])."</label>";
 	     }
 	     else
 	     {
 	      $salida .= "                        <label>".FormatoValor($vectorpendiente[0]['total_pendientes'])."</label>";
 	     }
 	     
 	     $salida .= "                       </td>";
 	     $salida .= "                       <td align=\"center\">\n";
 	     $salida .= "                           <input type=\"button\" class=\"input-submit\" value=\"CRUZAR\" onclick=\"AgruparPendientesDifCero('".$empresa_id."','".$centro_de_utilidad."','".$tmp_recibo_id."');\">\n";
 	     $salida .= "                       </td>";
 	     $salida .= "                       <td align=\"center\">\n";
 	     $salida .= "                           <input type=\"button\" class=\"input-submit\" value=\"BORRAR\" onclick=\"AgruparPendientesDifCeroBorrar();\">\n";
 	     $salida .= "                       </td>";
 	     $salida .= "                     </tr>";
 	
 	$salida .= "                    </table>";
 	
 	$salida .= "                  <br>";
     }    
     
   
   
   $objResponse->assign("ventana_de_diferentes","innerHTML",$salida);  
   $objResponse->alert("TABLA ACTUALIZADA FACTURAS DIFERENTES DE CERO(0)");
   return $objResponse;
}

/***************************************************************************************
*para colocar de nuevo el div de volver
****************************************************************************************/
function BotonVolver()
{ 
  $objResponse = new xajaxResponse();
  
  $path = SessionGetVar("rutaImagenes");

  $salida = "                  <table width='80%' align=\"center\" >\n";
  $salida .= "                    <tr>\n";
  $salida .= "                      <td COLSPAN='7' align=\"center\">\n";
  $salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"Cerrar\" value=\"VOLVER\" onclick=\"Volver();\">\n";
  $salida .= "                      </td>\n";
  $salida .= "                    </tr>\n";
  $salida .= "                  </table>\n";
  $objResponse->assign("final_boton","innerHTML",$salida);
  return $objResponse;
}
function Cerrar()
{ 
  $objResponse = new xajaxResponse();
  
  $path = SessionGetVar("rutaImagenes");

  $salida = "                  <table width='80%' align=\"center\" >\n";
  $salida .= "                    <tr>\n";
  $salida .= "                      <td COLSPAN='7' align=\"center\">\n";
  $salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"Cerrar\" value=\"CERRAR\" onclick=\"window.close();\">\n";
  $salida .= "                      </td>\n";
  $salida .= "                    </tr>\n";
  $salida .= "                  </table>\n";
  $objResponse->assign("final_boton","innerHTML",$salida);
  return $objResponse;
}      


function sincronizar_recibos_pendientes_ws_fi($numero, $prefijo) {

    $objResponse = new xajaxResponse();

    $dusoft_fi = AutoCarga::factory("SincronizacionDusoftFI", "SincronizacionDusoftFI", "", "");


   $resultado_ws = $dusoft_fi->enviarRCWSFI(trim($numero), trim($prefijo));
   
  
    $url = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosSincronizados');
    $mensaje = " Mensaje ws : {$resultado_ws['crearInformacionContableResult']['descripcion']} ";

    $objResponse->alert("{$mensaje}");
    $objResponse->script("window.location='{$url}';");


    return $objResponse;
}



?>