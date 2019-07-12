<?php
  $_ROOT='../../../../../';
  $VISTA='HTML';
  include $_ROOT.'includes/enviroment.inc.php';
  IncludeClass('MovBodegasSQL',null,'app','Inv_MovimientosBodegas');
  //include "../../../app_modules/Cg_Movimientos/classes/MovimientosSQL.class.php";
  $fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
  IncludeClass('ConexionBD');
  IncludeClass('ClaseUtil');
  IncludeClass('BodegasDocumentos');
  IncludeFile($fileName);
   	
  $empresa_id=$_REQUEST['empresa_id'];
  $prefijo=$_REQUEST['prefijo'];
  $numero=$_REQUEST['numero'];
  
  $consulta = new MovBodegasSQL();
  $resultado = $consulta->SacarDocumento($empresa_id,$prefijo,$numero);
  $documento = $consulta->GetDocumentoDespacho($empresa_id,$prefijo,$numero);
  $justificaciones_desp = $consulta->JustificacionesDespachos($empresa_id,$prefijo,$numero);
  
  //print_r($resultado);
  
  $TITLE="DETALLE DEL DOCUMENTO";
  
  print(ReturnHeader($TITLE));
  print(ReturnBody());
  $path = SessionGetVar("rutaImagenes");
    
  $nombre = $consulta->ColocarEmpresa($resultado['empresa_id']);
  $centro = $consulta->ColocarCentro($resultado['centro_utilidad']);
  $bodega = $consulta->bodegasname($resultado['bodega']);
  
  $ctl = new ClaseUtil();
  
  $salida .= "<table width=\"100%\" align=\"center\">\n";
  $salida .= "  <tr>\n";
  $salida .= "		<td align=\"right\" class=\"label\">";
  $salida .= "		<h2>".$resultado['prefijo']."-".$resultado['numero']."</h2>";
  $salida .= "		</td>";
  $salida .= "  </tr>\n";
  $salida .= "</table>";
  $salida .= "  <table width=\"100%\" border='1' align=\"center\" class=\"normal_10\" rules=\"all\">\n";
  //$salida .= "<pre>".print_r($documento,true)."</pre>";
  $salida .= "    <tr>\n";
  $salida .= "      <td width=\"35%\" class=\"label\">EMPRESA</td>\n";
  $salida .= "      <td width=\"65%\" align=\"left\">".$nombre[0]['razon_social']."</td>\n";
  $salida .= "    </tr>\n";
  $salida .= "    <tr>\n";
  $salida .= "      <td class=\"label\">CENTRO DE UTULIDAD</td>\n";
  $salida .= "      <td align=\"left\">".$centro[0]['descripcion']."</td>\n";
  $salida .= "    </tr>\n";
  $salida .= "    <tr>\n";
  $salida .= "      <td class=\"label\">BODEGA</td>\n";
  $salida .= "      <td align=\"left\">".$bodega[0]['descripcion']."</td>\n";
  $salida .= "    </tr>\n";
  $salida .= "  </table>\n";
  $salida .= "  <br>\n";   
  /*
  $salida .= "  <table width=\"90%\" border='1' align=\"center\" class=\"normal_10\" rules=\"all\">\n";
  $salida .= "    <tr>\n";
  $salida .= "      <td class=\"label\" colspan=\"2\" align=\"center\">".$documento['titulo']."</td>\n";
  $salida .= "    </tr>\n";
  $salida .= "    <tr>\n";
  $salida .= "      <td width=\"35%\" class=\"label\">".$documento['tipo']."</td>\n";
  $salida .= "      <td width=\"65%\" align=\"left\">".$documento['tipo_id_tercero'].":".$documento['tercero_id']." ".$documento['nombre_tercero']."</td>\n";
  $salida .= "    </tr>\n";
  $salida .= "    <tr>\n";
  $salida .= "      <td class=\"label\">DIRECCION</td>\n";
  $salida .= "      <td align=\"left\">".$documento['direccion']."</td>\n";
  $salida .= "    </tr>\n";
  $salida .= "  </table>\n";
  $salida .= "  <br>\n";*/
  $salida .= "  <table width=\"100%\" border='1' align=\"center\" class=\"normal_10\" rules=\"all\">\n";
  $salida .= "    <tr>\n";
  $salida .= "      <td class=\"label\" align=\"center\">TIPO MOVIMIENTO</td>\n";
  $salida .= "      <td align=\"left\">".$resultado['tipo_movimiento']."</td>\n";
  $salida .= "      <td class=\"label\" width=\"25%\">DOCUMENTO ID</td>\n";
  $salida .= "      <td align=\"left\">".$resultado['tipo_doc_bodega_id']."</td>\n";
  $salida .= "    </tr>\n";
  $salida .= "    <tr>\n";
  $salida .= "      <td class=\"label\" >DESCRIPCION</td>\n";
  $salida .= "      <td COLSPAN='3' align=\"left\">".$resultado['descripcion']."</td>\n";
  $salida .= "    </tr>\n";
  $salida .= "    <tr>\n";
  $salida .= "      <td class=\"label\" >NUMERO</td>\n";
  $salida .= "      <td align=\"left\">".$resultado['prefijo']."-".$resultado['numero']."</td>\n";
  $salida .= "      <td class=\"label\">FECHA</td>\n";
  $salida .= "      <td align=\"left\">".substr($resultado['fecha_registro'],0,10)."</td>\n";
  $salida .= "    </tr>\n";
  $salida .= "    <tr>\n";
  $salida .= "      <td class=\"label\">USUARIO</td>\n";
  $USUARIO=$consulta->NombreUsu($resultado['usuario_id']);
  $salida .= "      <td COLSPAN='3'>\n";
  $salida .= "        ".$resultado['usuario_id']."-".$USUARIO[0]['nombre'];
  $salida .= "      </td>\n";
  $salida .= "    </tr>\n";
  $salida .= "    <tr>\n";
  $salida .= "      <td class=\"label\"></B>OBSERVACION GENERAL<B></td>\n";
  $salida .= "      </td>\n";
  $salida .= "      <td COLSPAN='3'>\n";
  $salida .= "			".$resultado['observacion'];
  $salida .= "      </td>\n";
  $salida .= "    </tr>\n";
  
  $salida .= "    <tr>\n";
  $salida .= "      <td class=\"label\"></B>OBSERVACION PEDIDO<B></td>\n";
  $salida .= "      </td>\n";
  $salida .= "      <td COLSPAN='3'>\n";
  $salida .= "			".$resultado['obs_pedido'];
  $salida .= "      </td>\n";
  $salida .= "    </tr>\n";
  
  $salida .= "  </table>\n";
  $salida .= "  <br>\n"; 

  if(!empty($resultado['DATOS_ADICIONALES']))
  {
    $salida .= "<table BORDER='1' width=\"100%\" align=\"center\" class=\"normal_10\" rules=\"all\">\n";
    $salida .= "  <tr>\n";
    $salida .= "    <td COLSPAN='7' align=\"center\"><b>DATOS ADICIONALES</b></td>\n";
    $salida .= "  </tr>\n";
    foreach($resultado['DATOS_ADICIONALES'] as $doc_val=>$valor)
    {
      $salida .= "  <tr>\n";
      $salida .= "    <td WIDTH='35%' align=\"left\">".$doc_val."</td>\n";
      $salida .= "     <td WIDTH='65%' align=\"left\">".$valor."</td>\n";
      $salida .= "  </tr>\n";
    }
	$salida .= "</table>";
    $salida .= "<br>\n";
  }
         
  $salida .= "  <table width=\"100%\" border='1' align=\"center\" class=\"normal_10\" rules=\"all\">\n";
  $salida .= "    <tr class=\"label\" align=\"center\">\n";
  $salida .= "      <td colspan='9'>PRODUCTOS QUE CONTIENE ESTE DOCUMENTO</td>\n";
  $salida .= "    </tr>\n";
  $salida .= "    <tr class=\"label\" align=\"center\">\n";
  $salida .= "      <td >CODIGO</td>\n";
  $salida .= "      <td >DESCRIPCION</td>\n";
  $salida .= "      <td WIDTH='5%'>CANT</td>\n";
  $salida .= "      <td >LOTE</td>\n";
  $salida .= "      <td >FECHA VENC.</td>\n";
  $salida .= "      <td WIDTH='2%' >% GRAV</td>\n";
  $salida .= "      <td WIDTH='5%' >V/U</td>\n";
  $salida .= "      <td WIDTH='5%' >V/U+IVA</td>\n";
  $salida .= "      <td >TOTAL</td>\n";
  $salida .= "    </tr>\n";
  $valorTotal=0;
  $justificaciones = array();
  $codigo_p = "";
  $subtotal = $IvaTotal = 0;
  
  foreach($resultado['DETALLE'] as $doc_val=>$valor)
  {
   /* if(($resultado['DATOS_ADICIONALES']['TIPO DE DESPACHO :'])=='CLIENTES')
	{*/
	$IvaTotal += ($valor['iva']*$valor['cantidad']);
    $subtotal += ($valor['valor_unitario']*$valor['cantidad']);
    $valor_unitario = ($valor['valor_unitario']);
    $valor_unitario_iva = ($valor['valor_unitario_iva']);
	$total_producto=($valor['valor_unitario_iva']*$valor['cantidad']);
	/*}*/
    /*if($codigo_p != "" && $codigo_p != $valor['codigo_producto'] )*/
    if(($resultado['DATOS_ADICIONALES']['TIPO DE DESPACHO :'])!='CLIENTES')
    {
      $salida .= "    <tr class=\"label\" align=\"center\">\n";
      $salida .= "      <td >OBSERVACION</td>\n";
      $salida .= "      <td colspan=\"7\">".$valor['observacion_cambio']."</td>\n";
      $salida .= "    </tr>\n";
      $justificaciones[$codigo_p] = 1;
    }
     $salida .= "    <tr>\n";
     /*$salida .= "     <td class=\"normal_10AN\" align=\"left\">".$valor['movimiento_id']."</td>\n";*/
     $salida .= "     <td align=\"left\">".$valor['codigo_producto']."</td>\n";
     $salida .= "     <td align=\"left\">\n";
     $salida .= "       ".$valor['nombre']."";
     $salida .= "     </td>\n";
     /*$salida .= "     <td align=\"left\">".$valor['unidad_id']."</td>\n";*/
     $salida .= "     <td align=\"left\">\n";
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
     $salida .= "                       ".$valor['lote'];
     $salida .= "                      </td>\n";
     $salida .= "                      <td align=\"left\">\n";
     $salida .= "                       ".$valor['fecha_vencimiento'];
     $salida .= "                      </td>\n";
	 $salida .= "                      <td align=\"left\">\n";
     $salida .= "                       ".FormatoValor($valor['porcentaje_gravamen'],2);
     $salida .= "                      </td>\n";
	 $salida .= "                      <td align=\"left\">\n";
     $salida .= "                       $".FormatoValor($valor_unitario,2);
     $salida .= "                      </td>\n";
     $salida .= "                      <td align=\"left\">\n";
     $salida .= "                       $".FormatoValor($valor_unitario_iva,2);
     $salida .= "                      </td>\n";
     $salida .= "                      <td align=\"RIGHT\">\n";
     $salida .= "                       $".FormatoValor($total_producto,2);
     $salida .= "                      </td>\n";
     $salida .= "                    </tr>\n";
     $codigo_p = $valor['codigo_producto'];
  }
  if($justificaciones[$codigo_p] != '1')
  {
    $salida .= "    <tr class=\"label\" align=\"center\">\n";
    $salida .= "      <td >OBSERVACION</td>\n";
    $salida .= "      <td colspan=\"7\">".$valor['observacion_cambio']."</td>\n";
    $salida .= "    </tr>\n";
  }
  $salida .= "                    <tr>\n";
  $salida .= "                      <td colspan='7' align=\"right\">&nbsp;</td>\n";
  $salida .= "                      <td align=\"right\">\n";
  $salida .= "                       <label class='label_error'>SUBTOTAL</label>";
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"left\">\n";
  $salida .= "                       $".FormatoValor($subtotal);
  $salida .= "                      </td>\n";
  $salida .= "                    </tr>\n";  
  $salida .= "                    <tr>\n";
  $salida .= "                      <td colspan='7' align=\"right\">&nbsp;</td>\n";
  $salida .= "                      <td align=\"right\">\n";
  $salida .= "                       <label class='label_error'>IVA</label>";
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"left\">\n";
  $salida .= "                       $".FormatoValor($IvaTotal);
  $salida .= "                      </td>\n";
  $salida .= "                    </tr>\n";
  $salida .= "                    <tr>\n";
  $salida .= "                      <td colspan='7' align=\"right\">&nbsp;</td>\n";
  $salida .= "                      <td align=\"right\">\n";
  $salida .= "                       <label class='label_error'>TOTAL</label>";
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"left\">\n";
  $salida .= "                       $".FormatoValor(($IvaTotal+$subtotal),2);
  $salida .= "                      </td>\n";
  $salida .= "                    </tr>\n";
  $salida .= "                   </table>\n";
  /*print_r($justificaciones_desp);*/
  if(!empty($justificaciones_desp))
  {
  $salida .= "  <table width=\"100%\" border='1' align=\"center\" class=\"normal_10\" rules=\"all\">\n";
  $salida .= "    	<tr class=\"label\" align=\"center\">\n";
  $salida .= "			<td colspan=\"4\">";
  $salida .= "				<b>JUSTIFICACIONES PENDIENTES</b>";
  $salida .= "			</td>";
  $salida .= "		</tr>";
  $salida .= "    	<tr class=\"label\" align=\"center\">\n";
  $salida .= "			<td>";
  $salida .= "				<b>COD.PRODUCTO</b>";
  $salida .= "			</td>";
  $salida .= "			<td>";
  $salida .= "				<b>PRODUCTO</b>";
  $salida .= "			</td>";
  $salida .= "			<td>";
  $salida .= "				<b>PENDIENTE</b>";
  $salida .= "			</td>";
  $salida .= "			<td>";
  $salida .= "				<b>JUSTIFICACION</b>";
  $salida .= "			</td>";
  $salida .= "		</tr>";
	foreach($justificaciones_desp as $llave => $value)
	{
  $salida .= "    	<tr class=\"label\" align=\"center\">\n";
  $salida .= "			<td>";
  $salida .= "				".$value['codigo_producto'];
  $salida .= "			</td>";
  $salida .= "			<td>";
  $salida .= "				".$value['descripcion'];
  $salida .= "			</td>";
  $salida .= "			<td>";
  $salida .= "				".$value['cantidad_pendiente'];
  $salida .= "			</td>";
  $salida .= "			<td>";
  $salida .= "				".$value['observacion'];
  $salida .= "			</td>";
  $salida .= "		</tr>";
  	}
  $salida .= "	</table>";
  }	
  $salida .= "";
  $usuario = $consulta->NombreUsu(UserGetUID());
  $salida .= "	<br><table border='0' width=\"100%\">\n";
  $salida .= "		<tr>\n";
  $salida .= "			<td align=\"justify\" width=\"50%\">\n";
  $salida .= "				<font size='1' face='arial'>\n";
  $salida .= "					Imprimió:&nbsp;".$usuario[0]['nombre']."\n";
  $salida .= "				</font>\n";
  $salida .= "			</td>\n";
  $salida .= "			<td align=\"right\" width=\"50%\">\n";
  $salida .= "				<font size='1' face='arial'>\n";
  $salida .= "					Fecha Impresión :&nbsp;&nbsp;".date("d/m/Y - h:i a")."\n";
  $salida .= "				</font>\n";
  $salida .= "			</td>\n";
  $salida .= "		</tr>\n";
  $salida .= "	</table>\n";
  echo $salida;
  print(ReturnFooter());
?>