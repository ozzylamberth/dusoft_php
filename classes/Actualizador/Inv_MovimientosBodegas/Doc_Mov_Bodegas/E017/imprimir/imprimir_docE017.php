<?php
  $_ROOT='../../../../../';
  $VISTA='HTML';
  include $_ROOT.'includes/enviroment.inc.php';
  IncludeClass('MovBodegasSQL',null,'app','Inv_MovimientosBodegas');

  $fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
  if (!IncludeClass('BodegasDocumentos'))
  {
    die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
  }
  IncludeFile($fileName);
  $empresa_id=$_REQUEST['empresa_id'];
  SessionSetVar("EMPRESA",$_REQUEST['empresa_id']);
  $prefijo=$_REQUEST['prefijo'];
  $numero=$_REQUEST['numero'];
  $consulta=new MovBodegasSQL();
  $resultado=$consulta->SacarDocumento($empresa_id,$prefijo,$numero);
  $Tercero = $consulta->ObtenerTerceroDocumentoIngresoPrestamo($prefijo,$numero);
  $si_esfarmacia=$consulta->SiEsFarmacia($resultado['prefijo'],$resultado['numero']);

  $TITLE="DETALLE DEL DOCUMENTO";
  print(ReturnHeader($TITLE));
  print(ReturnBody());
  $path = SessionGetVar("rutaImagenes");

  $salida .= "<table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
  $salida .= "  <tr>\n";
  $salida .= "    <td width=\"35%\" align=\"center\">\n";
  $salida .= "      <a title='RAZON SOCIAL DE LA EMPRESA'>EMPRESA</a>";
  $salida .= "    </td>\n";
  $nombre=$consulta->ColocarEmpresa($resultado['empresa_id']); 
  $salida .= "    <td width=\"65%\" align=\"left\">\n";
  $salida .= "      ".$nombre[0]['razon_social'];
  $salida .= "    </td>\n";
  $salida .= "  </tr>\n";
  $salida .= "  <tr>\n";
  $salida .= "    <td width=\"8%\" align=\"center\">\n";
  $salida .= "      <a title='CENTRO DE UTILIDAD'>CENTRO DE UTILIDAD</a>";
  $salida .= "    </td>\n";
  $centro=$consulta->ColocarCentro($resultado['centro_utilidad']);
  $salida .= "    <td align=\"left\">\n";
  $salida .= "      ".$centro[0]['descripcion'];
  $salida .= "    </td>\n";
  $salida .= "  </tr>\n";
  $salida .= "  <tr>\n";
  $salida .= "    <td width=\"5%\" align=\"center\">\n";
  $salida .= "      <a title='BODEGA'>BODEGA</a>";
  $salida .= "    </td>\n";
  $bodega=$consulta->bodegasname($resultado['bodega']);
  $salida .= "    <td align=\"left\">\n";
  $salida .= "      ".$bodega[0]['descripcion'];
  $salida .= "    </td>\n";
  $salida .= "  </tr>\n";
  $salida .= "</table>\n";
  $salida .= "<br>\n";   
  $salida .= "<table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
  $salida .= "  <tr>\n";
  $salida .= "    <td width=\"35%\" align=\"center\">\n";
  $salida .= "      TIPO MOVIMIENTO";
  $salida .= "    </td>\n";
  $salida .= "    <td align=\"left\">\n";
  $salida .= "      ".$resultado['tipo_movimiento'];
  $salida .= "    </td>\n";
  $salida .= "    <td width=\"25%\"align=\"center\">\n";
  $salida .= "      <a TITLE='TIPO DOCUMENTO BODEGA ID'>";
  $salida .= "        DOC BOD ID";
  $salida .= "      </a>";
  $salida .= "    </td>\n";
  $salida .= "    <td align=\"left\">\n";
  $salida .= "      ".$resultado['tipo_doc_bodega_id'];
  $tipo_doc_general_id=$resultado['tipo_doc_bodega_id'];
  $salida .= "    </td>\n";
  $salida .= "  </tr>\n";
  $salida .= "  <tr>\n";
  $salida .= "    <td width=\"35%\" align=\"center\">\n";
  $salida .= "      DESCRIPCION";
  $salida .= "    </td>\n";
  $salida .= "    <td COLSPAN='3' align=\"left\">\n";
  $salida .= "      ".$resultado['descripcion'];
  $salida .= "    </td>\n";
  $salida .= "  </tr>\n";
  $salida .= "  <tr>\n";
  $salida .= "    <td width=\"8%\" align=\"center\">\n";
  $salida .= "      <a title='PREFIJO - NUMERO'>";
  $salida .= "        NUMERO";
  $salida .= "      </a>";
  $salida .= "     </td>\n";
  $salida .= "     <td align=\"left\">\n";
  $salida .= "        ".$resultado['prefijo']."-".$resultado['numero'];
  $salida .= "     </td>\n";
  $salida .= "     <td width=\"8%\" align=\"center\">\n";
  $salida .= "      <a title='FECHA DE REGISTRO'>";
  $salida .= "        FECHA";
  $salida .= "      </a>";
  $salida .= "     </td>\n";
  $salida .= "     <td align=\"left\">\n";
  $salida .= "      ".substr($resultado['fecha_registro'],0,10);
  $salida .= "     </td>\n";
  $salida .= "   </tr>\n";
  $salida .= "   <tr>\n";
  $salida .= "    <td align=\"center\">OBSERVACIONES</td>\n";
  $salida .= "    <td COLSPAN='3' align=\"left\">\n";
  $salida .= "      ".$resultado['observacion'];
  $salida .= "     </td>\n";
  $salida .= "   </tr>\n";
    
  if($resultado['sw_maneja_proyectos']=='1')
  {
    $proyecto=$consulta->SacarNombreProyecto($resultado['bodegas_doc_id'],$empresa_id,$prefijo,$numero);

    if($proyecto!=false)
    {
      $salida .= "   <tr>\n";
      $salida .= "    <td align=\"center\">\n";
      $salida .= "      <a title='NOMBRE DEL PROYECTO'>";
      $salida .= "        PROYECTO";
      $salida .= "      </a>";
      $salida .= "    </td>\n";
      $salida .= "    <td COLSPAN='3' align=\"left\">\n";
      $salida .= "      ".$proyecto['codigo_proyecto_cg']."-".$proyecto['descripcion'];
      $salida .= "    </td>\n";
      $salida .= "   </tr>\n";
    }
  }

  $salida .= "  <tr>\n";
  $salida .= "    <td align=\"center\">\n";
  $salida .= "      <a title='USUARIO QUE ELABORO EL RECIBO'>";
  $salida .= "       USUARIO";
  $salida .= "      </a>";
  $salida .= "    </td>\n";
  $USUARIO=$consulta->NombreUsu($resultado['usuario_id']);
  $salida .= "    <td COLSPAN='3' align=\"left\">\n";
  $salida .= "      ".$resultado['usuario_id']."-".$USUARIO[0]['nombre'];
  $salida .= "    </td>\n";
  $salida .= "  </tr>\n";
  $salida .= "</table>\n";
  $salida .= "<br>\n"; 
   
  if(!empty($resultado['DATOS_ADICIONALES']))
  {
    $salida .= "<table BORDER='1' width=\"90%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
    $salida .= "  <tr>\n";
    $salida .= "    <td COLSPAN='7' align=\"center\">\n";
    $salida .= "      DATOS ADICIONALES";
    $salida .= "    </td>\n";
    $salida .= "  </tr>\n";
    foreach($resultado['DATOS_ADICIONALES'] as $doc_val=>$valor)
    {
      $salida .= "  <tr>\n";
      $salida .= "    <td WIDTH='35%' align=\"left\">\n";
      $salida .= "      ".$doc_val;
      $salida .= "    </td>\n";
      $salida .= "    <td WIDTH='65%' align=\"left\">\n";
      $salida .= "      ".$valor;
      $salida .= "    </td>\n";
      $salida .= "  </tr>\n";
    }
    $salida .= "</table>\n";
    $salida .= "<br>\n"; 
  }
  /*INICIO*/ 
    if($tipo_doc_general_id=='E012')
      {
                  $salida .= "<table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
                  $salida .= "  <tr class=\"label\">\n";
                  $salida .= "    <td COLSPAN='9' align=\"center\">\n";
                  $salida .= "      PRODUCTOS QUE CONTIENE ESTE DOCUMENTO";
                  $salida .= "    </td>\n";
                  $salida .= "  </tr>\n";
                  $salida .= "  <tr  class=\"label\">\n";
                  $salida .= "    <td WIDTH='15%' align=\"center\">\n";
                  $salida .= "      <a TITLE='CODIGO DEL PRODUCTO'>";
                  $salida .= "        CODIGO";
                  $salida .= "      </a>";
                  $salida .= "    </td>\n";
                  $salida .= "    <td WIDTH='35%' align=\"center\">\n";
                  $salida .= "      <a TITLE='DESCRIPCION DEL PRODUCTO'>";
                  $salida .= "        DESCRIPCION";
                  $salida .= "      </a>";
                  $salida .= "    </td>\n";
                  $salida .= "    <td WIDTH='15%' align=\"center\">\n";
                  $salida .= "      <a TITLE='FECHA VENCIMIENTO'>";
                  $salida .= "        FECHA VENCIMIENTO";
                  $salida .= "      </a>";
                  $salida .= "     </td>\n";
                  $salida .= "     <td WIDTH='15%' align=\"center\">\n";
                  $salida .= "        LOTE";
                  $salida .= "     </td>\n";
                  $salida .= "     <td WIDTH='5%' align=\"center\">\n";
                  $salida .= "        CANTIDAD";
                  $salida .= "      </td>\n";

                  $valor_unitario = ModuloGetVar('app','Inv_MovimientosBodegas','documentos_valorunitario_'.$resultado['empresa_id']);
                  $valor_un =explode(",",$valor_unitario);
                  $contar =count($valor_un);

                  for($i=0;$i<$contar;$i++)
                  {
                  if($valor_un[$i]==$resultado['tipo_doc_bodega_id'])
                  {
                  $salida .= "      <td WIDTH='10%' align=\"center\">\n";
                  $salida .= "       VALOR UNITARIO";
                  $salida .= "      </td>\n";
                  }
                  }
                  if($tipo_doc_general_id!="I012" && $tipo_doc_general_id!="E017")
                  {
                    $salida .= "    <td WIDTH='7%' align=\"center\">\n";
                    $salida .= "      <a TITLE='VALOR UNITARIO'>";
                    $salida .= "        VL/U";
                    $salida .= "      </a>";
                    $salida .= "    </td>\n";
                    $salida .= "    <td WIDTH='7%' align=\"center\">\n";
                    $salida .= "      <a TITLE='PORCENTAJE DEL GRAVAMEN'>";
                    $salida .= "        % GRAV";
                    $salida .= "      </a>";
                    $salida .= "    </td>\n";
                    $salida .= "    <td WIDTH='7%' align=\"center\">\n";
                    $salida .= "      <a TITLE='IVA'>";
                    $salida .= "        $ IVA";
                    $salida .= "      </a>";
                    $salida .= "    </td>\n";
                    }

                  if(empty($si_esfarmacia))
                  {
                  $salida .= "    <td WIDTH='10%' align=\"center\">\n";
                  $salida .= "      <a TITLE='TOTAL COSTO'>";
                  $salida .= "        TOTAL";
                  $salida .= "      </a>";
                  $salida .= "    </td>\n";
                  }

                  $salida .= "    </tr>\n";
                  $subtotal = 0;
                  $valorTotal=0;
                  $k=0;

                  foreach($resultado['DETALLE'] as $doc_val=>$valor)
                  {
                  $valor_pactado=$consulta->valorunitario($valor['codigo_producto'],$empresa_id);
                  $porc_iva = ($valor['porcentaje_gravamen']/100);
                  
                  $ValorUnitario=($valor['valor_unitario']);
                  $iva_unitario = $valor['valor_unitario']-$ValorUnitario;
                  $ValorTotal_Producto = ($ValorUnitario*$valor['cantidad']);
                  $IvaProducto = ($valor['valor_unitario']*$porc_iva);
                                    
                  $IvaTotal=$IvaTotal+($IvaProducto);
                  
                  $valorTotal=$valorTotal+($ValorTotal_Producto+$IvaProducto);

                  $subtotal += $ValorTotal_Producto;

                  $salida .= "                    <tr>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       ".$valor['codigo_producto'];
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       ".$valor['nombre']."";
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       ".$valor['fecha_vencimiento'];
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       ".$valor['lote'];
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                      ".$valor['cantidad'];
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"RIGHT\">\n";
                  $salida .= "                       ".FormatoValor($ValorUnitario,2);
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"RIGHT\">\n";
                  $salida .= "                       ".FormatoValor($valor['porcentaje_gravamen'],2);
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"RIGHT\">\n";
                  $salida .= "                       ".FormatoValor($IvaProducto,2);
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"RIGHT\">\n";
                  $salida .= "                       ".FormatoValor($ValorTotal_Producto+$IvaProducto,2);
                  $salida .= "                      </td>\n";
                 
                  $salida .= "                    </tr>\n";

                  $k++;
                  }
      }
      else
          {
          $salida .= "<table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
                  $salida .= "  <tr class=\"label\">\n";
                  $salida .= "    <td COLSPAN='9' align=\"center\">\n";
                  $salida .= "      PRODUCTOS QUE CONTIENE ESTE DOCUMENTO";
                  $salida .= "    </td>\n";
                  $salida .= "  </tr>\n";
                  $salida .= "  <tr  class=\"label\">\n";
                  $salida .= "    <td WIDTH='15%' align=\"center\">\n";
                  $salida .= "      <a TITLE='CODIGO DEL PRODUCTO'>";
                  $salida .= "        CODIGO";
                  $salida .= "      </a>";
                  $salida .= "    </td>\n";
                  $salida .= "    <td WIDTH='35%' align=\"center\">\n";
                  $salida .= "      <a TITLE='DESCRIPCION DEL PRODUCTO'>";
                  $salida .= "        DESCRIPCION";
                  $salida .= "      </a>";
                  $salida .= "    </td>\n";
                  $salida .= "    <td WIDTH='15%' align=\"center\">\n";
                  $salida .= "      <a TITLE='FECHA VENCIMIENTO'>";
                  $salida .= "        FECHA VENCIMIENTO";
                  $salida .= "      </a>";
                  $salida .= "     </td>\n";
                  $salida .= "     <td WIDTH='15%' align=\"center\">\n";
                  $salida .= "        LOTE";
                  $salida .= "     </td>\n";
                  $salida .= "     <td WIDTH='5%' align=\"center\">\n";
                  $salida .= "        CANTIDAD";
                  $salida .= "      </td>\n";

                  $valor_unitario = ModuloGetVar('app','Inv_MovimientosBodegas','documentos_valorunitario_'.$resultado['empresa_id']);
                  $valor_un =explode(",",$valor_unitario);
                  $contar =count($valor_un);

                  $salida .= "    </tr>\n";
                  $subtotal = 0;
                  $valorTotal=0;
                  $k=0;

                  foreach($resultado['DETALLE'] as $doc_val=>$valor)
                  {
                  $valor_pactado=$consulta->valorunitario($valor['codigo_producto'],$empresa_id);
                  $porc_iva = ($valor['porcentaje_gravamen']/100)+1;
                  $ValorSubTotal = ($valor['total_costo']/$porc_iva);
                  $IvaProducto=$valor['total_costo']-$ValorSubTotal;
                  $IvaTotal=$IvaTotal+($IvaProducto);
                  $ValorUnitario = ($ValorSubTotal/$valor['cantidad']);
                  $valorTotal=$valorTotal+$valor['total_costo'];

                  $subtotal += $ValorSubTotal;

                  $salida .= "                    <tr>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       ".$valor['codigo_producto'];
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       ".$valor['nombre']."";
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       ".$valor['fecha_vencimiento'];
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       ".$valor['lote'];
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
                  $salida .= "                    </tr>\n";

                  $k++;
                  }
          
          }
/*FIN*/
  $salida .= "</table>\n";
  $salida .= "<br>\n";

  /*if(empty($si_esfarmacia))
  {
    $salida .= "<table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
     if($tipo_doc_general_id!="I012" || $tipo_doc_general_id!="E017")
      {
      $salida .= "  <tr>\n";
      $salida .= "    <td width=\"60%\" >&nbsp;</td>\n";
      $salida .= "    <td width=\"20%\"  class=\"label_error\" >SUBTOTAL</td>\n";
      $salida .= "    <td align=\"right\">".FormatoValor($subtotal,2)."</td>\n";
      $salida .= "  </tr>\n";
    
      $salida .= "  <tr>\n";
      $salida .= "    <td >&nbsp;</td>\n";
      $salida .= "    <td class=\"label_error\" >IVA</td>\n";
      $salida .= "    <td align=\"right\">".FormatoValor($IvaTotal,2)."</td>\n";
      $salida .= "  </tr>\n";
      }
      
    $salida .= "  <tr>\n";
    $salida .= "    <td >&nbsp;</td>\n";
    $salida .= "    <td class=\"label_error\" >TOTAL</td>\n";
    $salida .= "    <td align=\"right\">".FormatoValor($valorTotal,2)."</td>\n";
    $salida .= "  </tr>\n";
    $salida .= "</table>\n";
    $salida .= "<br>\n";
  }*/
  
  $salida .= "<br>\n";
  $salida .= "<br>\n";
  $salida .= "<br>\n";
  $salida .= "<table align=\"center\" width=\"50%\" class=\"modulo_list_claro\">\n";
  $salida .= "  <tr>\n";
  $salida .= "    <td align=\"left\">\n";
  $salida .= "     Revisado Por :";
  $salida .= "    </td>\n";
  $salida .= "  </tr>\n";
  $salida .= "  <tr>\n";
  
  $salida .= "    <td align=\"center\">\n";
  $salida .= "      <hr width=\"100%\">";
  $salida .= "    </td>\n";
  $salida .= "  </tr>\n";
  $salida .= "</table>\n";
  
  
  $salida .= "<table width=\"90%\" border=\"1\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
  $salida .= "  <tr>\n";
  $salida .= "    <td align=\"center\">\n";
  $salida .= "      FECHA DE IMPRESION";
  $salida .= "    </td>\n";
  $salida .= "    <td align=\"center\">\n";
  $salida .= "      USUARIO IMPRESION";
  $salida .= "    </td>\n";
  $salida .= "  </tr>\n";
  $salida .= "  <tr>\n";
  $salida .= "    <td align=\"center\">\n";
  $salida .= "      ".date("Y-m-d");
  $salida .= "    </td>\n";
  $salida .= "    <td align=\"center\">\n";
  $USUARIO=$consulta->NombreUsu(UserGetUID());
  $salida .= "      ".UserGetUID()."-".$USUARIO[0]['nombre'];
  $salida .= "    </td>\n";
  $salida .= "  </tr>\n";
  $salida .= "</table>\n";
  echo $salida;
	print(ReturnFooter());
?>