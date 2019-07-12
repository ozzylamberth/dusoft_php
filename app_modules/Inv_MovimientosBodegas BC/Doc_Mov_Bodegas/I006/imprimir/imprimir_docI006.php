<?php
		$_ROOT='../../../../../';
		$VISTA='HTML';
		include $_ROOT.'includes/enviroment.inc.php';
		IncludeClass('MovBodegasSQL',null,'app','Inv_MovimientosBodegas');
    //include "../../../app_modules/Cg_Movimientos/classes/MovimientosSQL.class.php";
    $fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
    if (!IncludeClass('BodegasDocumentos'))
    {
        die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
    }
		IncludeFile($fileName);
   	$empresa_id=$_REQUEST['empresa_id'];
    $prefijo=$_REQUEST['prefijo'];
    $numero=$_REQUEST['numero'];
    $consulta=new MovBodegasSQL();
    $resultado=$consulta->SacarDocumento($empresa_id,$prefijo,$numero);
    $Tercero = $consulta->ObtenerTerceroDocumentoIngresoDevolucionPrestamo($prefijo,$numero);
    SessionSetVar("EMPRESA",$_REQUEST['empresa_id']);
    //print_r($Tercero);
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
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                        OBSERVACIONES";
         $salida .= "                       </td>\n";
         $salida .= "                        <td COLSPAN='3' align=\"left\">\n";
         $salida .= "                          ".$resultado['observacion'];
         $salida .= "                         </td>\n";
         $salida .= "                    </tr>\n";
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
         $salida .= "                        <td COLSPAN='8' align=\"center\">\n";
         $salida .= "                         <a>";
         $salida .= "                           PRODUCTOS QUE CONTIENE ESTE DOCUMENTO";
         $salida .= "                         </a>";
         $salida .= "                        </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         /*$salida .= "                        <td WIDTH='8%' align=\"center\">\n";
         $salida .= "                          <a TITLE='MOVIMIENTO ID'>";
         $salida .= "                            MOV ID";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";*/
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
         $salida .= "                          <a TITLE='FECHA VENCIMIENTO'>";
         $salida .= "                            FECHA VENCIMIENTO";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                        <td WIDTH='15%' align=\"center\">\n";
         $salida .= "                          <a TITLE='LOTE'>";
         $salida .= "                            LOTE";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         /*$salida .= "                        <td WIDTH='15%' align=\"center\">\n";
         $salida .= "                          <a TITLE='UNIDAD DEL PRODUCTO'>";
         $salida .= "                            UNIDAD";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";*/
         $salida .= "                        <td WIDTH='10%' align=\"center\">\n";
         $salida .= "                          <a TITLE='UNIDAD DEL PRODUCTO'>";
         $salida .= "                            CANTIDAD";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                        <td WIDTH='7%' align=\"center\">\n";
         $salida .= "                          <a TITLE='VALOR UNITARIO'>";
         $salida .= "                           VL/U";
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
        //print_r($resultado['DETALLE']);
      foreach($resultado['DETALLE'] as $doc_val=>$valor)
       {
           
                $porc_iva = ($valor['porcentaje_gravamen']/100)+1;
                 $ValorSubTotal = ($valor['total_costo']/$porc_iva);
                 $IvaTotal=$IvaTotal+($valor['total_costo']-$ValorSubTotal);
                 $ValorUnitario = ($ValorSubTotal/$valor['cantidad']);
                 $valorTotal=$valorTotal+$valor['total_costo'];

           //var_dump($doc_val);     
                 $salida .= "                    <tr>\n";
                 /*$salida .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                 $salida .= "                       ".$valor['movimiento_id'];
                 $salida .= "                       </td>\n";*/
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       <a>";
                 $salida .= "                       ".$valor['codigo_producto'];
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       ".$valor['nombre']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       <a>";
                 $salida .= "                       ".$valor['fecha_vencimiento'];
                 $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       <a>";
                 $salida .= "                       ".$valor['lote'];
                 $salida .= "                      </td>\n";
                 /*$salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       ".$valor['descripcion_unidad']."";
                 $salida .= "                      </td>\n";*/
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
                 $salida .= "                       ".FormatoValor($ValorUnitario);
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       ".$valor['porcentaje_gravamen'];
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"RIGHT\">\n";
                 $salida .= "                       ".FormatoValor($valor['total_costo']);
                 $salida .= "                      </td>\n";
                 $salida .= "                    </tr>\n";
                // $valorTotal=$valorTotal+$valor['total_costo'];
      }
                 $salida .= "                    <tr>\n";
                 $salida .= "                      <td colspan='6' align=\"right\">\n";
                 $salida .= "                       ";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"right\">\n";
                 $salida .= "                       <label class='label_error'>IVA</label>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"right\">\n";
                 $salida .= "                       ".FormatoValor($IvaTotal);
                 $salida .= "                      </td>\n";
                 $salida .= "                    </tr>\n";
                 $salida .= "                    <tr>\n";
                 $salida .= "                      <td colspan='6' align=\"right\">\n";
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

