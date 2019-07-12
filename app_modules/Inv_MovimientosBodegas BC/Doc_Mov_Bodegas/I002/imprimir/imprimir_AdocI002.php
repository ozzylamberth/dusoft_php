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
  $autorizaciones=$consulta->ConsultarAutorizacionesIngreso($empresa_id,$prefijo,$numero);

 // print_r($autorizaciones);
  
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
       
  $salida .= "<table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
  $salida .= "  <tr class=\"label\">\n";
  $salida .= "    <td COLSPAN='7' align=\"center\">\n";
  $salida .= "      PRODUCTOS QUE FUERON AUTORIZADOS";
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
  $salida .= "     <td WIDTH='5%' align=\"center\">\n";
  $salida .= "        V/U COMPRA";
  $salida .= "      </td>\n";
  $salida .= "     <td WIDTH='5%' align=\"center\">\n";
  $salida .= "        V/U FACTURA";
  $salida .= "      </td>\n";
  

 
  
  $salida .= "    </tr>\n";
 
  $k=0;

  foreach($autorizaciones as $doc_val=>$valor)
  {
     
      
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                       ".$valor['codigo_producto'];
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                       ".$valor['descripcion_producto']."";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                       ".$valor['fecha_vencimiento'];
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                       ".$valor['lote'];
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                       ".$valor['cantidad'];
      $salida .= "                      </td>\n";
      
      $salida .= "                      <td align=\"RIGHT\">\n";
      $salida .= "                       ".FormatoValor($valor['valor_unitario_compra'],2);
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"RIGHT\">\n";
      $salida .= "                       ".FormatoValor($valor['valor_unitario_factura'],2);
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      
      $salida .= "                    <tr>\n";
      $salida .= "                    <td >";
      $salida .= "                    <b>INFO</b>";
      $salida .= "                    </td>";
      $salida .= "                    <td colspan=\"6\">";
      $salida .= "                    ".$valor['justificacion_ingreso'];
      $salida .= "                    </td>";
      $salida .= "                    </tr>\n";
      
      $salida .= "                    <tr>\n";
      $salida .= "                    <td >";
      $salida .= "                    <b>Autorizador 1:</b>";
      $salida .= "                    </td>";
      $salida .= "                    <td colspan=\"6\">";
      $USUARIO=$consulta->NombreUsu($valor['usuario_id_autorizador']);
      $salida .= "".$USUARIO[0]['nombre'];
      $salida .= "                    </td>";
      $salida .= "                    </tr>\n";
      
      $salida .= "                    <tr>\n";
      $salida .= "                    <td >";
      $salida .= "                    <b>Autorizador 2:</b> ";
      $salida .= "                    </td>";
      $salida .= "                    <td colspan=\"6\">";
      $USUARIO=$consulta->NombreUsu($valor['usuario_id_autorizador_2']);
      $salida .= "".$USUARIO[0]['nombre'];
      $salida .= "                    </td>";
      $salida .= "                    </tr>\n";
      
      $salida .= "                    <tr>\n";
      $salida .= "                    <td colspan=\"7\">";
      $salida .= " <b>AUTORIZACIONES :</b>".$valor['observacion_autorizacion'];
      $salida .= "                    </td>\n";
      $salida .= "                    </tr >";
      
 

      $k++;
  }
  
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