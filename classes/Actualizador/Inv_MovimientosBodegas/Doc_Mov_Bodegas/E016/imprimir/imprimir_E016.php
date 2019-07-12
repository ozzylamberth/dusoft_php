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
  $Contrato=$consulta->ObtenerContratoId($_REQUEST['empresa_id']);

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
      if($doc_val != "sw_bodegamindefensa" && $doc_val != "sw_entregado_off")
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
    }
    $salida .= "</table>\n";
    $salida .= "<br>\n"; 
  }
       
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
  $salida .= "     <td WIDTH='5%' align=\"center\">\n";
  $salida .= "        PRECIO/BASE";
  $salida .= "      </td>\n";
  $salida .= "     <td WIDTH='5%' align=\"center\">\n";
  $salida .= "        PRECIO/U";
  $salida .= "      </td>\n";
  $salida .= "     <td WIDTH='5%' align=\"center\">\n";
  $salida .= "        TOTAL";
  $salida .= "      </td>\n";
  

  
  $salida .= "    </tr>\n";
  $subtotal = 0;
  $valorTotal=0;
  $k=0;

  foreach($resultado['DETALLE'] as $doc_val=>$valor)
  {
     
      
      $salida .= "                    <tr>\n";
      $codigo_mindefensa=$consulta->codigo_mindefensa($valor['codigo_producto']);
      $salida .= "                      <td align=\"left\">\n";
     // $salida .= "                       ".$valor['codigo_producto']." (";
      $salida .= "                       ".$codigo_mindefensa['codigo_mindefensa']."";
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
      $Precio=$consulta->Precio($Contrato['plan_id'],$valor['codigo_producto'],$empresa_id,$resultado['DATOS_ADICIONALES']['sw_bodegamindefensa'],$resultado['DATOS_ADICIONALES']['sw_entregado_off']);
      $PrecioBase=$consulta->Precio($Contrato['plan_id'],$valor['codigo_producto'],$empresa_id,'0','1');
      $salida .= "                      </td>\n";
      $salida .= "                    <td align=\"left\">";
      $salida .= "                    $".FormatoValor($PrecioBase['precio'],2);
      $salida .= "                    </td>";
      $salida .= "                    <td align=\"left\">";
      $salida .= "                    $".FormatoValor($Precio['precio'],2);
      $salida .= "                    </td>";
      $salida .= "                    <td align=\"left\">";
      $salida .= "                    $".FormatoValor($Precio['precio']*$valor['cantidad'],2);
      $salida .= "                    </td>";
      $salida .= "                    </tr>\n";
      $k++;
      $acum += ($Precio['precio']*$valor['cantidad']);
  }
  $salida .= "                    <tr>\n";
  $salida .= "                      <td align=\"right\" colspan=\"7\"><b>TOTAL:</b></td>\n";
  $salida .= "                      <td class=\"label_error\">$".FormatoValor($acum,2)."</td>";
  $salida .= "                    </tr>\n";
//print_r($resultado['DATOS_ADICIONALES']);  
  $salida .= "</table>\n";
  $salida .= "<br>\n";
  
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