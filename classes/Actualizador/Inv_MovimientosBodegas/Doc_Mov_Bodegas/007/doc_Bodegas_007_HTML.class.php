<?php
	/**************************************************************************************  
	* $Id: doc_Bodegas_007_HTML.class.php,v 1.1 2009/07/17 19:08:17 johanna Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.1 $ 
	* 
	* @autor Jaime G�ez 
	***************************************************************************************/

include "app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/007/doc_Bodegas_007.class.php";
include "app_modules/Inv_MovimientosBodegas/RemoteXajax/definirBodegas.js";
include_once "app_modules/Inv_MovimientosBodegas/pruebaDocBodega.php";
//IncludeClass('RecaudoElectronico',null,'app','RecibosCaja');
class doc_bodegas_007_HTML 
{
 function doc_bodegas_007_HTML(){}

     
/*********************************************************************************
 Cabecera
*********************************************************************************/ 
function FormaDocumento($vector)
{
      $salida .= ThemeAbrirTabla("TIPO DE DICUMENTO");
      $salida .=$this->Cabecera();
      
      $salida .= ThemeCerrarTabla();
      return $salida;
}

 function Cabecera ()
 {
    $objeto=new Classmodules();
  $file ='app_modules/RecibosCaja/RemoteXajax/definirRec.php';
  $objeto->SetXajax(array(),$file);
  //$consulta=new RecaudoElectronico();
  $path = SessionGetVar("rutaImagenes");
  
  $salida .= "            <form name=\"recaudo\" action=\"javascript:LlamarRevi('".SessionGetVar("EMPRESA")."',document.revi.tip_lapi.value);\" method=\"post\">\n";
  $salida .= "               <div id=\"ventana1\">\n";
  $salida .= "                 <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
  $salida .= "                    <tr class=\"modulo_list_claro\">\n";
  $salida .= "                       <td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                          EMPRESA";
  $salida .= "                       </td>";
  //$nombreempresa=$consulta->ColocarEmpresa($empresa_id);
  $salida .= "                       <td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
  $salida .= "                          clinccccc.";//$nombreempresa[0]['razon_social']
  $salida .= "                       </td>";
  $salida .= "                    </tr>";
  $salida .= "                    <tr class=\"modulo_list_claro\">\n";
  $salida .= "                       <td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                          BODEGA";
  $salida .= "                       </td>";
  $salida .= "                       <td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
  $salida .= "                          10 -  ALMACEN GENERAL";
  $salida .= "                       </td>";
  $salida .= "                    </tr>";
  $salida .= "                    <tr class=\"modulo_list_claro\">\n";
  $salida .= "                       <td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                          TIPO CLASE DE DOCUMENTO";
  $salida .= "                       </td>";
  //$nombre=$consulta->Nombres($tipo_id_tercero,$tercero_id);
  $salida .= "                       <td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
  $salida .= "                         TRANSFERENCIAS ENTRE BODEGAS(EGRESO)";
  $salida .= "                       </td>";
  $salida .= "                    </tr>";
  $salida .= "                    <tr class=\"modulo_list_claro\">\n";
  $salida .= "                       <td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                          TIPO DE MOVIMIENTO";
  $salida .= "                       </td>";
  //$nombre=$consulta->Nombres($tipo_id_tercero,$tercero_id);
  $salida .= "                       <td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
  $salida .= "                         ENTRADA/SALIDA";
  $salida .= "                       </td>";
  $salida .= "                    </tr>";
  $salida .= "                       <td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
  $salida .= "                          DOCUMENTO TEMPORAL #";
  $salida .= "                       </td>";
  //$nombre=$consulta->Nombres($tipo_id_tercero,$tercero_id);
  $salida .= "                       <td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
  $salida .= "                         1";
  $salida .= "                       </td>";
  $salida .= "                 </table>";
  $salida .= "              </form>";

  return $salida;
 }
}
?>