<?php
	/**************************************************************************************
	* $Id: definirBodegas.php,v 1.1 2009/07/17 19:08:23 johanna Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Jaime gomez
	**************************************************************************************/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	//include "../../../app_modules/InvTomaFisica/classes/TomaFisicaSQL.class.php";
	include "../../../app_modules/InvTomaFisica/RemoteXajax/definirToma.js";
  include "../../../classes/ClaseHTML/ClaseHTML.class.php";

function ObtenerDocumentosBodegaFinal($offset,$empresa_id, $centro_utilidad, $bodega, $usuario_id, $tipo_movimiento, $tipo_doc_bodega_id)
{
   //ECHO $offset.$empresa_id.$centro_utilidad.$bodega.$usuario_id.$tipo_movimiento."A".$tipo_doc_bodega_id;
   global $VISTA;
   $path = SessionGetVar("rutaImagenes");
   $consulta=new MovBodegasSQL();
   $objResponse = new xajaxResponse();
   $documentos=$consulta->ObtenerDocumentosFinal($offset,$empresa_id, $centro_utilidad, $bodega, $usuario_id, $tipo_movimiento, $tipo_doc_bodega_id);
   
   //var_dump($documentos['contador']);
     If(!EMPTY($documentos))
     {
         //tipo_movimiento  bodegas_doc_id  tipo_clase_documento  prefijo descripcion
         $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $salida .= "                       <td width=\"8%\" align=\"center\">\n";
         $salida .= "                       <a title='TIPO MOVIMIENTO'>";
         $salida .= "                        TM";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"8%\" align=\"center\">\n";
         $salida .= "                       <a title='DOCUMENTO BODEGA ID'>";
         $salida .= "                        DOC BOD ID";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"5%\" align=\"center\">\n";
         $salida .= "                       <a title='DOCUMENTO ID'>";
         $salida .= "                        DOC ID";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"8%\" align=\"center\">\n";
         $salida .= "                          NUMERO";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"37%\" align=\"center\">\n";
         $salida .= "                       <a title='DESCRIPCION DEL DOCUMENTO'>";
         $salida .= "                        DESCRIPCION";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"20%\" align=\"center\">\n";
         $salida .= "                          OBSERVACION";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"10%\" align=\"center\">\n";
         $salida .= "                          FECHA";
         $salida .= "                       </td>\n";
         $salida .= "                       <td colspan='3' width=\"4%\" align=\"center\">\n";
         $salida .= "                          ACCIONES";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         
         foreach($documentos as $datos=>$tipo_mov)
         {//var_dumP($tipo_mov);
           foreach($tipo_mov as $tipo_mov_desc=>$tips_docus)
            {  
             foreach($tips_docus as $tipo_documento=>$doc_id)
              {  //var_dumP($tipo_documento);
                   
                foreach($doc_id as $doc_val=>$valor)
                {   
                   $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                
                  $salida .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                  $salida .= "                       ".$tipo_mov_desc;
                  $salida .= "                       </td>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       <a title='".$valor['tipo_clase_documento']."'>";
                  $salida .= "                       ".$tipo_documento;
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       ".$valor['documento_id'];
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       ".$valor['prefijo']."-".$valor['numero'];
                  $salida .= "                      </td>\n";   
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       ".$valor['descripcion'];
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       ".$valor['observacion'];
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       ".substr($valor['fecha_registro'],0,10);
                  $salida .= "                      </td>\n";   
                  $salida .= "                      <td  align=\"center\">\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
                  $nuevousu = "javascript:MostarDatosDocumento('".$empresa_id."','".$valor['prefijo']."','".$valor['numero']."');MostrarCapa('ContenedorDet');IniciarDoc('DATOS DEL DOCUMENTO');";//
                  $salida .= "                         <a title='BODEGA DOCUMENTO' href=\"".$nuevousu."\">";
                  $salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                  $salida .= "                         </a>\n";
                  $salida .= "                       </td>\n";
                  $salida .= "                      <td  align=\"center\">\n";
                  switch($tipo_documento)
                  {
                    case 'E008':
                    case 'E001':
                    case 'E002':
                    case 'E004':
                    case 'E006':
                    case 'E007':
                    case 'I005':
                    case 'I006':
                    case 'I007':
                      $direccion="app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/".$tipo_documento."/imprimir/imprimir_doc".$tipo_documento.".php";
                    break;
                    case 'T001':
                      $direccion="app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/".$tipo_documento."/imprimir/imprimir_".$tipo_documento.".php";
                    break;
                    default:
                      $direccion="app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I001/imprimir/imprimir_docI001.php";
                    break;
                  }
                  $imagen = "themes/$VISTA/" . GetTheme() ."/images//imprimir.png";
                  $actualizar="false";
                  $alt="IMPRIMIR DOCUMENTO";
                  $x=RetornarImpresionDoc($direccion,$alt,$imagen,SessionGetVar("EMPRESA"),$valor['prefijo'],$valor['numero']);
                  $salida .= "                     ".$x."";
                  
                  /*
                  Para Imprimir las novedades de Ingreo por Farmacia
                  */
                  if($tipo_documento=="I011")
                  {
                  $direccion_= "app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I011/imprimir/imprimir_docI011.php";
                                                       $imagen_ = "themes/$VISTA/" . GetTheme() ."/images//pconsultar.png";
                                                       $alt_="IMPRIMIR DOCUMENTO DE NOVEDADES";
                                                       $y=RetornarImpresionDoc($direccion_,$alt_,$imagen_,SessionGetVar("EMPRESA"),$valor['prefijo'],$valor['numero']);
                    $salida .= "                     ".$y."";
                  }
                  /*
                  Fin Reporte
                  */
                  /*
                  Para Imprimir las Autorizaciones de Ingreso por Orden de Compra
                  */
                  if($tipo_documento=="I002")
                  {
           	  		$direccion__="app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I002/imprimir/imprimir_AdocI002.php";
                  $imagen__ = "themes/$VISTA/" . GetTheme() ."/images//autorizado.png";
                  $alt__="IMPRIMIR DOCUMENTO DE AUTORIZACIONES";
                  $z=RetornarImpresionDoc($direccion__,$alt__,$imagen__,SessionGetVar("EMPRESA"),$valor['prefijo'],$valor['numero']);
                  $salida .= "                     ".$z."";
                  }
                  /*
                  Fin Reporte
                  */
                  
                  
                  $salida .= "                       </td>\n";
                  $salida .= "                      <td  align=\"center\">\n";
                  switch($tipo_documento)
                  {
                    case 'E008':
                      $salida .= "                    <table width=\"100%\">\n";
                      $salida .= "                      <tr>\n";
                      $salida .= "                        <td width=\"50%\">\n";
                      $salida .= "                          <a title='PDF' href=javascript:ImprimirPDF('".SessionGetVar("EMPRESA")."','".$valor['prefijo']."','".$valor['numero']."','".$valor['documento_id']."','D')>\n";
                      $salida .= "                            <img src=\"".GetThemePath()."/images/pinactivo.png\" border=\"0\">\n";
                      $salida .= "                          </a>";
                      $salida .= "                        </td>";
                      $salida .= "                        <td width=\"50%\">\n";
                      $salida .= "                          <a title='ROTULO' href=javascript:ImprimirPDF('".SessionGetVar("EMPRESA")."','".$valor['prefijo']."','".$valor['numero']."','".$valor['documento_id']."','R')>\n";
                      $salida .= "                            <img src=\"".GetThemePath()."/images/panulado.png\" border=\"0\">\n";
                      $salida .= "                          </a>";
                      $salida .= "                        </td>";
                      $salida .= "                      </tr>";
                      $salida .= "                    </table>";
                    break;
                    default:
                      $salida .= "                        <a title='Documento Adjunto' href=javascript:Adjuntos('".SessionGetVar("EMPRESA")."','".$valor['prefijo']."','".$valor['numero']."','".$valor['documento_id']."')>\n";
                      $salida .= "                          <img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
                      $salida .= "                        </a>";
                    break;
                  }
                  $salida .= "                       </td>\n";
                  $salida .= "                    </tr>\n";
                } 
              }
            } 
        }
        $salida .= "                 </table>";
        $salida .= "                 <br>";
        $op="1";
        $slc=$documentos;             //$offset.              $empresa_id.$centro_utilidad.$bodega.$usuario_id.$tipo_movimiento$tipo_doc_bodega_id;
        $salida .= "".ObtenerPaginador($offset,$path,$slc,$op,$empresa_id,$centro_utilidad,$bodega,$usuario_id,$tipo_movimiento,$tipo_doc_bodega_id);
        $objResponse->assign("documentos_final","innerHTML",$salida);
     }
        
    return $objResponse; 
}  
/********************************************************************************
 *para mostrar la tabla de documentos
 *********************************************************************************/
    function ObtenerPaginador($pagina,$path,$slc,$op,$empresa_id,$centro_utilidad,$bodega,$usuario_id,$clas_documento,$tipos_documento)
    {

      
      //echo "io";
      $TotalRegistros = $slc['contador'];
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $LimitRow = 20;
      }
      else
      {
        $LimitRow = 20;
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
                                                                                         //  xajax_ObtenerDocumentosBodegaFinal(empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento);
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:MostrarDocusFinal('1','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:MostrarDocusFinal('".($pagina-1)."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:MostrarDocusFinal('".$i."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:MostrarDocusFinal('".($pagina+1)."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:MostrarDocusFinal('".($NumeroPaginas)."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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

  
function ObtenerDatosDocumento($empresa_id,$prefijo,$numero)
{
    $consulta=new MovBodegasSQL();
    $objResponse = new xajaxResponse();
    $resultado=$consulta->SacarDocumento($empresa_id,$prefijo,$numero);
    //var_dump($resultado);


         $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $salida .= "                       <td width=\"35%\" align=\"center\">\n";
         $salida .= "                       <a title='RAZON SOCIAL DE LA EMPRESA'>";
         $salida .= "                        EMPRESA";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $nombre=$consulta->ColocarEmpresa($resultado['empresa_id']); 
         $salida .= "                       <td width=\"65%\" class=\"modulo_list_claro\" align=\"left\">\n";
         $salida .= "                          ".$nombre[0]['razon_social'];
         $salida .= "                         </td>\n";
         $salida .= "                       </tr>\n";
         $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $salida .= "                       <td width=\"8%\" align=\"center\">\n";
         $salida .= "                       <a title='CENTRO DE UTULIDAD'>";
         $salida .= "                        CENTRO DE UTULIDAD";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $centro=$consulta->ColocarCentro($resultado['centro_utilidad']);
         $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
         $salida .= "                          ".$centro[0]['descripcion'];
         $salida .= "                         </td>\n";
         $salida .= "                       </tr>\n";
         $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $salida .= "                       <td width=\"5%\" align=\"center\">\n";
         $salida .= "                       <a title='BODEGA'>";
         $salida .= "                        BODEGA";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $bodega=$consulta->bodegasname($resultado['bodega']);
         $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
         $salida .= "                          ".$bodega[0]['descripcion'];
         $salida .= "                         </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                   </table>\n";
         $salida .= "                   <br>\n";   



         $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $salida .= "                       <td width=\"35%\" align=\"center\">\n";
         $salida .= "                       <a>";
         $salida .= "                        TIPO MOVIMIENTO";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td class=\"modulo_list_claro\" align=\"left\">\n";
         $salida .= "                          ".$resultado['tipo_movimiento'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"25%\"align=\"center\">\n";
         $salida .= "                       <a TITLE='TIPO DOCUMENTO BODEGA ID'>";
         $salida .= "                        DOC BOD ID";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
         $salida .= "                          ".$resultado['tipo_doc_bodega_id'];
         $salida .= "                        </td>\n";
         $salida .= "                       </tr>\n";
         $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $salida .= "                        <td width=\"35%\" align=\"center\">\n";
         $salida .= "                          <a>";
         $salida .= "                            DESCRIPCION";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                       <td COLSPAN='3' class=\"modulo_list_claro\" align=\"left\">\n";
         $salida .= "                          ".$resultado['descripcion'];
         $salida .= "                       </td>\n";
         $salida .= "                      </tr>\n";
         $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $salida .= "                       <td width=\"8%\" align=\"center\">\n";
         $salida .= "                       <a title='NUMERO'>";
         $salida .= "                        NUMERO";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
         $salida .= "                          ".$resultado['prefijo']."-".$resultado['numero'];
         $salida .= "                         </td>\n";
         $salida .= "                       <td width=\"8%\" align=\"center\">\n";
         $salida .= "                       <a title='FECHA DE REGISTRO'>";
         $salida .= "                        FECHA";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
         $salida .= "                          ".substr($resultado['fecha_registro'],0,10);
         $salida .= "                         </td>\n";
         $salida .= "                       </tr>\n";
         $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                        OBSERVACIONES";
         $salida .= "                       </td>\n";
         $salida .= "                        <td COLSPAN='3'class=\"modulo_list_claro\" align=\"left\">\n";
         $salida .= "                          ".$resultado['observacion'];
         $salida .= "                         </td>\n";
         $salida .= "                      </tr>\n";
         $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                       <a title='USUARIO QUE ELABORO EL RECIBO'>";
         $salida .= "                        USUARIO";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $USUARIO=$consulta->NombreUsu($resultado['usuario_id']);
         $salida .= "                        <td COLSPAN='3'class=\"modulo_list_claro\" align=\"left\">\n";
         $salida .= "                          ".$resultado['usuario_id']."-".$USUARIO[0]['nombre'];
         $salida .= "                         </td>\n";
         $salida .= "                      </tr>\n";
         $salida .= "                   </table>\n";

         $salida .= "                   <br>\n"; 
         if(!empty($resultado['DATOS_ADICIONALES']))
         {
              $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
              $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
              $salida .= "                        <td COLSPAN='7' align=\"center\">\n";
              $salida .= "                         <a>";
              $salida .= "                           DATOS ADICIONALES";
              $salida .= "                         </a>";
              $salida .= "                        </td>\n";
              $salida .= "                    </tr>\n";
            foreach($resultado['DATOS_ADICIONALES'] as $doc_val=>$valor)
            {
                //var_dump($resultado['DETALLE']);
                $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $salida .= "                      <td WIDTH='35%' class=\"modulo_table_list_title\" align=\"left\">\n";
                $salida .= "                       ".$doc_val;
                $salida .= "                      </td>\n";
                $salida .= "                      <td WIDTH='65%' align=\"left\">\n";
                $salida .= "                       <a>";
                $salida .= "                       ".$valor;
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
            }
              $salida .= "                   <br>\n";
          }
         $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $salida .= "                        <td COLSPAN='7' align=\"center\">\n";
         $salida .= "                         <a>";
         $salida .= "                           PRODUCTOS QUE CONTIENE ESTE DOCUMENTO";
         $salida .= "                         </a>";
         $salida .= "                        </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $salida .= "                        <td WIDTH='8%' align=\"center\">\n";
         $salida .= "                          <a TITLE='MOVIMIENTO ID'>";
         $salida .= "                            MOV ID";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                        <td WIDTH='15%' align=\"center\">\n";
         $salida .= "                          <a TITLE='CODIGO DEL PRODUCTO'>";
         $salida .= "                            CODIGO";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                        <td WIDTH='35%' align=\"center\">\n";
         $salida .= "                          <a TITLE='DESCRIPCION DEL PRODUCTO'>";
         $salida .= "                            DESCRIPCION";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                        <td WIDTH='15%' align=\"center\">\n";
         $salida .= "                          <a TITLE='UNIDAD DEL PRODUCTO'>";
         $salida .= "                            UNIDAD";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                        <td WIDTH='10%' align=\"center\">\n";
         $salida .= "                          <a TITLE='UNIDAD DEL PRODUCTO'>";
         $salida .= "                            CANTIDAD";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                        <td WIDTH='7%' align=\"center\">\n";
         $salida .= "                          <a TITLE='PORCENTAJE DEL GRAVAMEN'>";
         $salida .= "                           % GRAV";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                        <td WIDTH='10%' align=\"center\">\n";
         $salida .= "                          <a TITLE='TOTAL COSTO'>";
         $salida .= "                            TOTAL";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                       </tr>\n";
         $valorTotal=0;
      foreach($resultado['DETALLE'] as $doc_val=>$valor)
       {
            //var_dump($resultado['DETALLE']);
                 $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                 $salida .= "                      <td class=\"normal_10AN\" align=\"left\">\n";
                 $salida .= "                       ".$valor['movimiento_id'];
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       <a>";
                 $salida .= "                       ".$valor['codigo_producto'];
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       ".$valor['descripcion'];
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       ".$valor['descripcion_unidad'];
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 list($entero,$decimal) = explode(".",$valor['cantidad']);
                 if($decimal>0)
                  {
                   $salida .= "                       ".$valor['cantidad'];
                  }
                  else
                  {
                   $salida .= "                       ".$entero;
                  } 
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       ".$valor['porcentaje_gravamen'];
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"RIGHT\">\n";
                 $salida .= "                       ".FormatoValor($valor['total_costo']);
                 $salida .= "                      </td>\n";
                 $salida .= "                    </tr>\n";
                 $valorTotal=$valorTotal+$valor['total_costo'];
      }
                 $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                 $salida .= "                      <td colspan='5' align=\"right\">\n";
                 $salida .= "                       ";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"right\">\n";
                 $salida .= "                       <label class='label_error'>TOTAL</label>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"right\">\n";
                 $salida .= "                       ".FormatoValor($valorTotal);
                 $salida .= "                      </td>\n";
                 $salida .= "                    </tr>\n";
         $salida .= "                   </table>\n";

    
    $salida = $objResponse->setTildes($salida);
    $objResponse->assign("ContenidoDet","innerHTML",$salida);
    return $objResponse;

}
function ObtenerListaTiposDocumentos($empresa_id, $centro_utilidad, $bodega, $tipo_movimiento)
{ /*echo $empresa_id.$centro_utilidad.$bodega.$tipo_movimiento;*/
   $objResponse = new xajaxResponse();
   $consulta=new MovBodegasSQL();
   $tipos_doc=$consulta->ObtenerTiposDocumentos($empresa_id, $centro_utilidad, $bodega, $tipo_movimiento, UserGetUID());
  //var_dump($tipos_doc);
  if(!empty($tipos_doc))
  {
      $salida .="                           <option value=\"\" selected>SELECCIONAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
        foreach($tipos_doc as $key=>$valor)
        {
          $salida .="                           <option value=\"".$key."\">".$valor."</option> \n";
        }
      
//       $html  = "document.docus.tipos_doc.options.length = 0 ;\n";
//       $html .= "document.docus.tipos_doc.options[0] = new Option('--SELECCIONAR--','',false, false);\n";
//       $i =1;
//       //print_r($tipos_doc);
//       foreach($tipos_doc as $key=>$valor)
//         $html .= "document.docus.tipos_doc.options[".$i++."] = new Option('".$valor."','".$key."',false, false);\n";
// 
//       $html .= "document.docus.tipos_doc.disabled = false ;\n";
//       $objResponse = new xajaxResponse();
//       $objResponse->script($html);
     $objResponse->assign("tipos_doc","innerHTML",$salida);
     //$objResponse->assign("tipos_doc","disabled","false");
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
/******************************************************************************
*BORRAR UN DOC TMP
********************************************************************************/
// function BorrarTmpAfirmativo1($tmp,$bodega_doc_id)
// {
//       $consulta=new MovBodegasSQL();
//       $objResponse = new xajaxResponse();
//       $buscar=$consulta->EliminarDocTemporal($bodega_doc_id,$tmp,UserGetUID());
//       var_dump($buscar);
//        if($buscar==1)
//       {
//         $objResponse->alert("EL DOCUMENTO TEMPORAL $tmp FUE ELIMINADO EXITOSAMENTE");
//       }
//       else
//       { $objResponse->alert("NO SE PUEDE BORRAR");
//        } 
//       
//       return $objResponse;
// }


function BorrarTmpAfirmativo($tr,$tmp,$bodega_doc_id,$tipo_doc_bodega_id)
{
      $consulta=new MovBodegasSQL();
      $objResponse = new xajaxResponse();
      $buscar=$consulta->EliminarDocTemporal($bodega_doc_id,$tmp,UserGetUID());
      //$objResponse->alert($bodega_doc_id);
      //$objResponse->alert($tmp);
      //$objResponse->alert($tipo_doc_bodega_id);
      if($tipo_doc_bodega_id=='E008')  
      {
        $resulta=$consulta->BorrarTmpFarmacias($tmp);
        $resultad=$consulta->BorrarTmpClientes($tmp);
        //$rst =$consulta->BorrarTmpInv_Farmacias($doc_tmp_id,$pedido['solicitud_prod_a_bod_ppal_id']);
      }      
      
      if($buscar==1)
      {
        $objResponse->alert("EL DOCUMENTO TEMPORAL $tmp FUE ELIMINADO EXITOSAMENTE");
        $objResponse->remove($tr);
      }
      else
      { $objResponse->alert("NO SE PUEDE BORRAR");
       } 
      
      return $objResponse;
}

FUNCTION BorrarTMPX($tr,$item,$bodega_doc_id,$tipo_doc_bodega_id,$CONTENIDOR)
{
      $objResponse = new xajaxResponse();
      //$objResponse->alert($tipo_doc_bodega_id."jkdjskdj");
      $da .= "      <table width='100%' border='0'>\n";
      $da .= "       <tr>\n";
      $da .= "        <td colspan='2' class=\"label_error\">\n";
      $da .= "          ESTA SEGURO DE ELIMINAR ESTE DOCUMENTO TEMPORAL ?";
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
      $da .= "          <input type=\"button\" class=\"input-submit\" value=\"ELIMINAR\" name=\"ELIMINAR_MOV\" onclick=\"xajax_BorrarTmpAfirmativo('".$tr."','".$item."','".$bodega_doc_id."','".$tipo_doc_bodega_id."');Cerrar('Contenedor".$C."');\">\n";
      $da .= "        </td>\n";
      $da .= "        <td align='center'>\n";
      $da .= "          <input type=\"button\" class=\"input-submit\" value=\"CANCELAR\" name=\"CANCELAR\" onclick=\"Cerrar('Contenedor".$C."');\">\n";
      $da .= "        </td>\n";
      $da .= "       </tr>\n";
      $da .= "      </table>\n";
      $objResponse->assign($CONTENIDOR,"innerHTML",$da);
      return $objResponse;
}  

/*******************************************************************************
funcion para buscar tecero por id
*******************************************************************************/   
 function BusUnTer($tipo_id,$id)
 {  
    
    $objResponse = new xajaxResponse();
    
    $path = SessionGetVar("rutaImagenes");
    $consulta=new MovimientosSQL();
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
    $salida .= "                         <select name=\"tipox_id\" class=\"select\" onchange=\"\">";
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

      $objResponse->alert("PAGIMA $pagina");
      $objResponse->alert("CRITERIO 1 $criterio1");
      $objResponse->alert("CRITERIO 2 $criterio2");
      $objResponse->alert("CRITERIO 0 $criterio");
      $objResponse->alert($div);
      $objResponse->alert($Forma);
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
  /**
  *
  */
  function ImprimirPDF($empresa,$prefijo,$numero,$documento,$tipopdf)
  {
    $fnc = SessionGetVar("funcion_E008");
    $fnci = SessionGetVar("rotulo_E008");
    SessionSetVar("DocumentoDespacho_E008",array("empresa_id"=>$empresa,"prefijo"=>$prefijo,"numero"=>$numero));
    
    $objResponse = new xajaxResponse();
    if($tipopdf == "R")
      $objResponse->script("".$fnci.";");
    else
      $objResponse->script("".$fnc.";");
    return $objResponse;
  }
  /**
  *
  */
  function SeleccionarDocumentos($form)
  {
    $objResponse = new xajaxResponse();
    $mvs = new MovBodegasSQL();
    $tipos_doc = $mvs->ObtenerTiposDocumentos($form['empresa'], $form['centro_utilidad'], $form['bodega'], $form['clas_doc'], UserGetUID());

    $html = " <option value=\"-1\">---SELECCIONAR---</option> \n";
    if(!empty($tipos_doc))
    {
      foreach($tipos_doc as $key=>$valor)
        $html .= "  <option value=\"".$key."\" ".(($key == $form['doc_seleccionado'])? "selected":"").">".$valor."</option>\n";
    }
    $objResponse->assign("tipos_doc","innerHTML",$html);
    return $objResponse;
  }
?>