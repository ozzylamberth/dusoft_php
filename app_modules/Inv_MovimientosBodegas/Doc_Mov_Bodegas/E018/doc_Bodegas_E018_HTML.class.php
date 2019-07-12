<?php
	/**************************************************************************************  
	* $Id: doc_Bodegas_E018_HTML.class.php,v 1.1 2009/07/17 19:08:17 mauricio Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.1 $ 
	* 
	* @autor Mauricio Medina 
	***************************************************************************************/

include "app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/E018/doc_Bodegas_E018.class.php";
//include "app_modules/Inv_MovimientosBodegas/classes/MovBodegasSQL.class.php";
//include_once "app_modules/Inv_MovimientosBodegas/pruebaDocBodega.php";
//IncludeClass('RecaudoElectronico',null,'app','RecibosCaja');
class doc_bodegas_E018_HTML
{
 function doc_bodegas_E018_HTML(){}

     
/*********************************************************************************
 Cabecera
*********************************************************************************/ 
function FormaDocumento($DATOS)
{    //var_dump($DATOS);
     $objeto=new Classmodules();
     $file ='app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/E018/RemoteXajax/definirBodegas_E018.php';
     $objeto->SetXajax(array("Subtimit","CrearDocumentoFinalx","BorrarTmpAfirmativo1","MostrarProductox","Borrar","BorrarAjuste","IngresoProductosTemporal",
							"BuscarProducto1","ObtenerPaginadoPro","GuardarTmpDoc","Cuadrar_ids_terceros",
							"CrearUSA","BuscadorFarmacia","BuscadorFarmacia_d","Listar_DocumentosFarmacia","Departamento2","Municipios",
							"Guardar_DYM","GuardarPersona","Actualizartmp","Devolver","GuardarDevolucion",
							"ListadoProductos_Farmacia","ListadoProductos_Farmacia_d","RegistrarFacturaProveedor"),$file,"ISO-8859-1");

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
     $consulta = new doc_bodegas_E018();
     $datos = $consulta->TraerDatos($DATOS['bodegas_doc_id']);
     $planes = $consulta->planes_parametrizados();
     $arreglo = array();
	 
	 $arreglo['empresa_id'] = SessionGetVar("EMPRESA");
	 $arreglo['centro_utilidad'] = $DATOS['utility'];
	 $TiposDispensacion = $consulta->ConsultarTiposDispensacion($arreglo);
	 
	 $salida .=$this->Cabecera($datos);
	 $salida .=$this->CrearDocumentosHtml($DATOS['bodegas_doc_id'],$DATOS['CTL'],$DATOS['tipo_doc_bodega_id'],$DATOS['nom_bodega'],$DATOS['utility'],$DATOS['bodega'],$planes,$TiposDispensacion);
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
      $consulta = new doc_bodegas_E018();
      $datox=$consulta->DatosParaEditar($DATOS['doc_tmp_id'],UserGetUID());
      $DocTemporal_Auxiliar=$consulta->GetDocTemporal($DATOS['bodegas_doc_id'],$DATOS['doc_tmp_id'],UserGetUID());
      
	  $salida .= $this->PintarTabla($datox);
      $datos = $consulta->TraerDatos($DATOS['bodegas_doc_id']);
      //print_r($datos);
      
      $salida .=$this->ColocarProductos($DATOS['bodegas_doc_id'],$datos,$DATOS['doc_tmp_id'],$datox['empresa_id'],$DocTemporal_Auxiliar,$DATOS);
      $BODEGA=ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('nom_bodegax'=>$DATOS['nom_bodegax'],'utility'=>$DATOS['utility'],'bodegax'=>$DATOS['bodegax']));
      $salida .= " <form name=\"volver1\" action=\"".$BODEGA."\" method=\"post\">\n";//".$this->action[0]."
      $salida .= "  <table align=\"center\" width=\"50%\">\n";
      $salida .= "    <tr>\n";
      
      
       $salida .= "       <td id='SUTANO' align=\"center\" colspan='7'>\n";
       $salida .= "          <input type=\"button\" id='ELI' class=\"input-submit\" value=\"ELIMINAR DOCUMENTO\" onclick=\"EliminarDocu('".$DATOS['doc_tmp_id']."','".$DATOS['bodegas_doc_id']."');\">\n";
       $salida .= "       </td>\n"; 
       //$salida .= "<pre>".print_r($contar,true)."</pre>";
       $salida .= "       <td align=\"center\" colspan='7'>\n";
       $salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
       $salida .= "       </td>\n";
           
      
          $salida .= "       <td id='MENGANO' align=\"center\"  colspan='7'>\n";
          $salida .= "          <input type=\"button\" id='enar' class=\"input-submit\" value=\"CREAR DOCUMENTO\" DISABLED onclick=\"CrearDocuFinal('".$DATOS['bodegas_doc_id']."','".$DATOS['doc_tmp_id']."','".$DATOS['tipo_doc_bodega_id']."','".$datox['empresa_id']."','".$datox['centro_utilidad']."','".$datox['bodega']."');\">\n";
          $salida .= "       </td>\n";
         
         $salida .= "       <td id='MENGANO' align=\"center\"  colspan='7'>\n";
         $salida .= "          <input type=\"hidden\" id='devolver' class=\"input-submit\" value=\"DEVOLVER\" onclick=\"Devolver('".$DATOS['tipo_doc_bodega_id'] ."','".$DATOS['doc_tmp_id']."','".$_['EMPRESAS']['empresa_id']."');\">\n";
         $salida .= "       </td>\n";
          
       
      
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
   $salida .= "                        <a title='PREFIJO DEL DOCUMENTO'>PREFIJO<a>";
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

  $salida .= "                    </tr>";
  $salida .= "                 </table>";
  $salida .= "              </form>";

  return $salida;
 }



////////////////////////////////////////////////////////////////////////////////
function CrearDocumentosHtml($bodegas_doc_id,$dir,$tipo_doc_bodega_id,$nom_bodega,$utilidad,$bodega,$planes,$TiposDispensacion)
 {
  $consulta = new MovBodegasSQL();
  $path = SessionGetVar("rutaImagenes");
  $salida .= "          <br>\n";

	$option = "<option value=\"\">-- SELECCIONAR PLAN --</option>";
  foreach($planes as $key=>$valor)
	{
	$option .= "	<option value=\"".trim($valor['plan_id'])."\">";
	$option .= "		".$valor['plan_descripcion'];
	$option .= "	</option>";
	}

	$option_1 = "<option value=\"\">-- SELECCIONAR TIPO DISPENSACION --</option>";
  foreach($TiposDispensacion as $k=>$v)
	{
	$option_1 .= "	<option value=\"".trim($v['tipo_formula_id'])."\">";
	$option_1 .= "		".$v['descripcion_tipo_formula']." - Tope: $".FormatoValor($v['tope']);
	$option_1 .= "	</option>";
	}

	
  $salida .= "       <div id='errorcreartmp' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
  $salida .= "            <form name=\"unocreate\" action=\"".$accion1."\" method=\"post\">\n";
  $salida .= "               <div id=\"ventana1\">\n";
  $salida .= "                 <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  width='13%' align=\"left\" class=\"modulo_table_list_title\" colspan=\"2\">\n";
  $salida .= "                        PLANES:";
  $salida .= "                       </td>\n";
  $salida .= "                          <input type=\"hidden\" id=\"htmp_id\" name=\"htmp_id\" value=\"0\">\n";
  $salida .= "                          <input type=\"hidden\" id=\"farmacia_id\" name=\"farmacia_id\" value=\"\">\n";
  $salida .= "                       <td  width='40%'  align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "								<select class=\"select\" style=\"width:100%\" name=\"plan_id\" id=\"plan_id\">";
  $salida .= "									".$option;
  $salida .= "								</select>";
  $salida .= "                       </td>\n";
  $salida .= "                        <td width='20%' align=\"center\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
  $salida .= "                        </td>";
  $salida .= "                    </tr>\n";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  width='13%' align=\"left\" class=\"modulo_table_list_title\" colspan=\"2\">\n";
  $salida .= "                        TIPOS DISPENSACION:";
  $salida .= "                       </td>\n";
  $salida .= "                       <td  width='40%'  align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "								<select class=\"select\" style=\"width:100%\" name=\"tipo_formula_id\" id=\"tipo_formula_id\">";
  $salida .= "									".$option_1;
  $salida .= "								</select>";
  $salida .= "                       </td>\n";
  $salida .= "                        <td width='20%' align=\"center\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
  $salida .= "                        </td>";
  $salida .= "                    </tr>\n";
  $salida .= "                    <tr>\n";
  $salida .= "                       <td  width='13%' align=\"left\" class=\"modulo_table_list_title\" colspan=\"2\">\n";
  $salida .= "                        NUMERO DE SOLICITUD";
  $salida .= "                       </td>\n";
  $salida .= "                       <td  width='40%'  align=\"left\" class=\"modulo_list_claro\"> \n";
  $salida .= "								<input name=\"requisicion\" id=\"requisicion\" type=\"text\" class=\"input-text\" style=\"width:100%\">";
  $salida .= "                       </td>\n";
  $salida .= "                        <td width='20%' align=\"center\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
  $salida .= "                        </td>";
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
  $salida .="							<input type=\"button\" id=\"nuevo\" value=\"GRABAR DOCUMENTO\" class=\"input-bottom\" onClick=\"javascript:GrabarDocumento('".$bodegas_doc_id."',document.getElementById('obser').value,document.getElementById('plan_id').value,document.getElementById('tipo_formula_id').value,document.getElementById('requisicion').value);\">";
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

function ColocarProductos($bodegas_doc_id,$datos,$tmp_doc_id,$empresa_id,$DocTemporal_Auxiliar,$DATOS)
 {
    $consulta = new MovBodegasSQL();
    $usuariotmp=$consulta->Consultausuaritmp($tmp_doc_id,$bodegas_doc_id);
    $devolucion=$consulta->ConsultaDevolucion_doc($datos['tipo_doc_bodega_id'],$tmp_doc_id);
    //print_r($DocTemporal_Auxiliar);
    
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
    $salida .= "   <script>";
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
    $salida .= "   </script>";
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
    /*
    * Principal Buscador
    */
	$salida .= "                          <input type=\"hidden\" id=\"empresa_idz\" value=\"".$datos['empresa_id']."\">\n";
	$salida .= "                          <input type=\"hidden\" id=\"centro_utilidadz\" value=\"".$datos['centro_utilidad']."\">\n";
	$salida .= "                          <input type=\"hidden\" id=\"bodegaz\" value=\"".$datos['bodega']."\">\n";
     					
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
  
  $salida .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
  $salida .= "						<tr class=\"modulo_table_list_title\">";
  $salida .= "							<td colspan=\"2\">";
  $salida .= "								DATOS ADICIONALES";
  $salida .= "							</td>";
  $salida .= "						</tr>";
  $salida .= "						<tr class=\"modulo_list_oscuro\">";
  $salida .= "							<td class=\"normal_10AN\">";
  $salida .= "								PLAN";
  $salida .= "							</td>";
  $salida .= "							<td class=\"modulo_list_claro\">";
  $salida .= "								".$DocTemporal_Auxiliar['plan_descripcion'];
  $salida .= "							</td>";
  $salida .= "						</tr>";
  $salida .= "						<tr class=\"modulo_list_oscuro\">";
  $salida .= "							<td class=\"normal_10AN\">";
  $salida .= "								TIPO DISPENSACION";
  $salida .= "							</td>";
  $salida .= "							<td class=\"modulo_list_claro\">";
  $salida .= "								".$DocTemporal_Auxiliar['descripcion_tipo_formula']." <label class=\"normal_10AN\">TOPE</label>:<b class=\"label_error\">$".FormatoValor($DocTemporal_Auxiliar['tope'])."</b>";
  $salida .= "							</td>";
  $salida .= "						</tr>";
  $salida .= "						<tr class=\"modulo_list_oscuro\">";
  $salida .= "							<td class=\"normal_10AN\">";
  $salida .= "								NUMERO SOLICITUD";
  $salida .= "							</td>";
  $salida .= "							<td class=\"modulo_list_claro\">";
  $salida .= "								".$DocTemporal_Auxiliar['requisicion'];
  $salida .= "							</td>";
  $salida .= "						</tr>";
  $salida .= "                 </table>\n";
  
  $salida .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
  $salida .= "                  <tr class=\"modulo_table_list_title\">";
  $salida .= "                      <td>CODIGO BARRAS :</td><TD><input style=\"width:100%\" onkeydown=\"recogerTeclaBus(event);\" type=\"text\" class=\"input-text\" id=\"codigo_barras\" name=\"codigo_barras\"></TD><td>DESCRIPCION</td><TD><input style=\"width:100%\" type=\"text\" class=\"input-text\" id=\"descripcion\" name=\"descripcion\" onkeydown=\"recogerTeclaBus(event);\"></TD>";
  $salida .= "                  </tr>";
  $salida .= "                 </table>";
  $salida .= "                <br>";
  $salida .= "                <input type=\"hidden\" id=\"bodegas_doc_id\" value=\"".$DocTemporal_Auxiliar['bodegas_doc_id']."\">";
  $salida .= "                <input type=\"hidden\" id=\"tmp_doc_id\" value=\"".$DocTemporal_Auxiliar['doc_tmp_id']."\">";
  $salida .= "                <input type=\"hidden\" id=\"empresa_id\" value=\"".$DocTemporal_Auxiliar['empresa_id']."\">";
  $salida .= "                <input type=\"hidden\" id=\"centro_utilidad\" value=\"".$DocTemporal_Auxiliar['centro_utilidad']."\">";
  $salida .= "                <input type=\"hidden\" id=\"bodega\" value=\"".$DocTemporal_Auxiliar['bodega']."\">";
  $salida .= "                <div id=\"error\" class=\"label_error\"></div>";
  $salida .= "                <div id=\"ListadoProductos\"></div>";
  
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
  return $salida;
 }
}
?>