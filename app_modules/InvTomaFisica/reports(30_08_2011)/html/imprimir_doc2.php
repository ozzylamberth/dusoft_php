<?php
    /**
    * $Id: imprimir_doc2.php,v 1.1 2009/12/31 13:52:01 johanna Exp $
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @package IPSOFT-SIIS   
    * Archivo que obtiene los datos de un documento
    * @param string $bodegas_doc_id
    * @param string $numero
    * @return pop-up con toda la informacion
    **/

    $_ROOT='../../../../';
    $VISTA='HTML';
    include $_ROOT.'includes/enviroment.inc.php';
    IncludeClass('MovBodegasAdminSQL',null,'app','Inv_Movimientos_Admin');
    //include "../../../app_modules/Cg_Movimientos/classes/MovimientosSQL.class.php";
    $fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
    if (!IncludeClass('BodegasDocumentos'))
    {
        die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
    }
    IncludeFile($fileName);
    $bodega_doc_id=$_REQUEST['bodegas_doc_id'];
    $numero=$_REQUEST['numero'];
    $consulta=new MovBodegasAdminSQL();
    $resultado=$consulta->SacarDocumento1($bodega_doc_id,$numero);
//     var_dump($resultado);
//     echo $consulta->mensajeDeError;
//     echo $consulta->error;
    $TITLE="DETALLE DEL DOCUMENTO";
    print(ReturnHeader($TITLE));
    print(ReturnBody());
    $path = SessionGetVar("rutaImagenes");
    //$salida .=ThemeAbrirTabla('DETALLE DOCUMENTO');
    // var_dump($resultado);
    $salida .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td width=\"35%\" align=\"center\">\n";
         $salida .= "                       <a title='RAZON SOCIAL DE LA EMPRESA'>";
         $salida .= "                        EMPRESA";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $nombre=$consulta->ColocarEmpresa($resultado['empresa_id']); 
         $salida .= "                       <td width=\"65%\" align=\"left\">\n";
         $salida .= "                          ".$nombre[0]['razon_social'];
         $salida .= "                         </td>\n";
         $salida .= "                       </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td width=\"8%\" align=\"center\">\n";
         $salida .= "                       <a title='CENTRO DE UTULIDAD'>";
         $salida .= "                        CENTRO DE UTULIDAD";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $centro=$consulta->ColocarCentro($resultado['centro_utilidad']);
         $salida .= "                        <td align=\"left\">\n";
         $salida .= "                          ".$centro[0]['descripcion'];
         $salida .= "                         </td>\n";
         $salida .= "                       </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td width=\"5%\" align=\"center\">\n";
         $salida .= "                       <a title='BODEGA'>";
         $salida .= "                        BODEGA";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $bodega=$consulta->bodegasname($resultado['bodega']);
         $salida .= "                        <td align=\"left\">\n";
         $salida .= "                          ".$bodega[0]['descripcion'];
         $salida .= "                         </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                   </table>\n";
         $salida .= "                   <br>\n";   



         $salida .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td width=\"35%\" align=\"center\">\n";
         $salida .= "                       <a>";
         $salida .= "                        TIPO MOVIMIENTO";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                          ".$resultado['tipo_movimiento'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"25%\"align=\"center\">\n";
         $salida .= "                       <a TITLE='TIPO DOCUMENTO BODEGA ID'>";
         $salida .= "                        DOC BOD ID";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                        <td align=\"left\">\n";
         $salida .= "                          ".$resultado['tipo_doc_bodega_id'];
         $salida .= "                        </td>\n";
         $salida .= "                       </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                        <td width=\"35%\" align=\"center\">\n";
         $salida .= "                          <a>";
         $salida .= "                            DESCRIPCION";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                       <td COLSPAN='3' align=\"left\">\n";
         $salida .= "                          ".$resultado['descripcion'];
         $salida .= "                       </td>\n";
         $salida .= "                      </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td width=\"8%\" align=\"center\">\n";
         $salida .= "                       <a title='PREFIJO - NUMERO'>";
         $salida .= "                        NUMERO";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                        <td align=\"left\">\n";
         $salida .= "                          ".$resultado['prefijo']."-".$resultado['numero'];
         $salida .= "                         </td>\n";
         $salida .= "                       <td width=\"8%\" align=\"center\">\n";
         $salida .= "                       <a title='FECHA DE REGISTRO'>";
         $salida .= "                        FECHA";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                        <td align=\"left\">\n";
         $salida .= "                          ".substr($resultado['fecha_registro'],0,10);
         $salida .= "                         </td>\n";
         $salida .= "                       </tr>\n";
//          $salida .= "                    <tr>\n";
//          $salida .= "                       <td align=\"center\">\n";
//          $salida .= "                        OBSERVACIONES";
//          $salida .= "                       </td>\n";
//          $salida .= "                        <td COLSPAN='3' align=\"left\">\n";
//          $salida .= "                          ".$resultado['observacion'];
//          $salida .= "                         </td>\n";
//          $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                       <a title='USUARIO QUE ELABORO EL RECIBO'>";
         $salida .= "                        USUARIO";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $USUARIO=$consulta->NombreUsu($resultado['usuario_id']);
         $salida .= "                        <td COLSPAN='3' align=\"left\">\n";
         $salida .= "                          ".$resultado['usuario_id']."-".$USUARIO[0]['nombre'];
         $salida .= "                         </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                   </table>\n";

         $salida .= "                   <br>\n"; 

         if(!empty($resultado['DATOS_ADICIONALES']))
         {
              $salida .= "                 <table BORDER='1' width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
              $salida .= "                    <tr>\n";
              $salida .= "                        <td COLSPAN='7' align=\"center\">\n";
              $salida .= "                         <a>";
              $salida .= "                           DATOS ADICIONALES";
              $salida .= "                         </a>";
              $salida .= "                        </td>\n";
              $salida .= "                    </tr>\n";
            foreach($resultado['DATOS_ADICIONALES'] as $doc_val=>$valor)
            {
                //var_dump($resultado['DETALLE']);
                $salida .= "                    <tr>\n";
                $salida .= "                      <td WIDTH='35%' align=\"left\">\n";
                $salida .= "                       ".$doc_val;
                $salida .= "                      </td>\n";
                $salida .= "                      <td WIDTH='65%' align=\"left\">\n";
                $salida .= "                       <a>";
                $salida .= "                       ".$valor;
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
            }
              $salida .= "                   </table>\n";
          }

         
         $salida .= "                   <br>\n"; 
         $salida .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                        <td COLSPAN='7' align=\"center\">\n";
         $salida .= "                         <a>";
         $salida .= "                           PRODUCTOS QUE CONTIENE ESTE DOCUMENTO";
         $salida .= "                         </a>";
         $salida .= "                        </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                        <td WIDTH='8%' align=\"center\">\n";
         $salida .= "                          <a TITLE='CONSECUTIVO'>";
         $salida .= "                            CONS";
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
         $salida .= "                        <td WIDTH='10%' align=\"center\">\n";
         $salida .= "                          <a TITLE='UNIDAD DEL PRODUCTO'>";
         $salida .= "                            UNIDAD";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                        <td WIDTH='10%' align=\"center\">\n";
         $salida .= "                          <a TITLE='UNIDAD DEL PRODUCTO'>";
         $salida .= "                            CANTIDAD";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                        <td WIDTH='12%' align=\"center\">\n";
         $salida .= "                          <a TITLE='PORCENTAJE DEL GRAVAMEN'>";
         $salida .= "                           COSTO";
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
            //var_dump($doc_val);     
                 $salida .= "                    <tr>\n";
                 $salida .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                 $salida .= "                       ".$valor['consecutivo'];
                 $salida .= "                       </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       <a>";
                 $salida .= "                       ".$valor['codigo_producto'];
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       ".$valor['descripcion']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       ".$valor['descripcion_unidad']."";
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
                 $salida .= "                       ".FormatoValor($valor['total_costo']);
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"RIGHT\">\n";
                 $TOTAL_COSTO_CANTIDAD=$valor['total_costo']*$valor['cantidad'];
                 $salida .= "                       ".FormatoValor($TOTAL_COSTO_CANTIDAD);
                 $salida .= "                      </td>\n";
                 $salida .= "                    </tr>\n";
                 $valorTotal=$valorTotal+$TOTAL_COSTO_CANTIDAD;
      }
        $salida .= "                    <tr>\n";
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
        $salida .= "                    <br>\n";
        $salida .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                        <td COLSPAN='1' align=\"center\">\n";
        $salida .= "                         <a>";
        $salida .= "                           FECHA DE IMPRESION";
        $salida .= "                         </a>";
        $salida .= "                        </td>\n";
        $salida .= "                        <td COLSPAN='1' align=\"center\">\n";
        $salida .= "                         <a>";
        $salida .= "                           USUARIO IMPRESION";
        $salida .= "                         </a>";
        $salida .= "                        </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr>\n";
        $salida .= "                        <td COLSPAN='1' align=\"center\">\n";
        $salida .= "                         <a>";
        $salida .= "                           ".date("Y-m-d");
        $salida .= "                         </a>";
        $salida .= "                        </td>\n";
        $salida .= "                        <td COLSPAN='1' align=\"center\">\n";
        $salida .= "                         <a>";
        $USUARIO=$consulta->NombreUsu(UserGetUID());
        $salida .= "                          ".UserGetUID()."-".$USUARIO[0]['nombre'];
        $salida .= "                         </a>";
        $salida .= "                        </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                  </table>\n";
    //$salida .=ThemeCerrarTabla();
    echo $salida; 
  
    
    print(ReturnFooter());
?>

