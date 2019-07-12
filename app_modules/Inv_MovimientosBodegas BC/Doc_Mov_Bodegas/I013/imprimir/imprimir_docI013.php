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
    
    $DocCabeceraVerificacion=$consulta->ConsultarDocumentoVerificacion($empresa_id,$prefijo,$numero);
    $DocDetalleVerificacion=$consulta->ConsultarDocumentoVerificacionDetalle($empresa_id,$prefijo,$numero);
    $Farmacia = $consulta->ConsultaEmpresa($DocCabeceraVerificacion['farmacia_id']);
    //print_r($DocDetalleVerificacion);
    SessionSetVar("EMPRESA",$_REQUEST['empresa_id']);
    $TITLE="DETALLE DEL DOCUMENTO DE NOVEDADES";
    print(ReturnHeader($TITLE));
		print(ReturnBody());
    $path = SessionGetVar("rutaImagenes");

    $salida .= "                 <table width=\"95%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
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



         $salida .= "                 <table width=\"95%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                     <td align=\"center\" colspan=\"4\"><b>REPORTE DE NOVEDADES - INGRESO DE PRODUCTOS POR DEVOLUCION DE FARMACIA</b></td>";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td width=\"35%\" align=\"center\">\n";
         $salida .= "                       <a>";
         $salida .= "                        <b>FARMACIA :</b>";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                          ".$Farmacia['empresa_id']."-".$Farmacia['razon_social'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td><b>DOCUMENTO</b></td>";
         $salida .= "                       <td>".$DocCabeceraVerificacion['prefijo_doc_farmacia']."-".$DocCabeceraVerificacion['numero_doc_farmacia']."</td>";
         $salida .= "                     </tr>";
         $salida .= "                     <tr>";
         $salida .= "                         <td colspan=\"4\"><b>FECHA DE VERIFICACION :</b> ".$DocCabeceraVerificacion['fecha_registro']."</td>";
         $salida .= "                     </tr>";
         $salida .= "                        <td width=\"35%\" align=\"center\">\n";
         $salida .= "                          <a>";
         $salida .= "                            <B>DOCUMENTO RECEPCION</B>";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                          ".$resultado['descripcion'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"25%\"align=\"center\">\n";
         $salida .= "                       <a TITLE='TIPO DOCUMENTO BODEGA ID'>";
         $salida .= "                       <B>DOC BOD ID</B>";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                        <td align=\"left\">\n";
         $salida .= "                          ".$resultado['tipo_doc_bodega_id'];
         $salida .= "                        </td>\n";
         $salida .= "                      </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td width=\"8%\" align=\"center\">\n";
         $salida .= "                       <a title='PREFIJO - NUMERO'>";
         $salida .= "                        <b>NUMERO</b>";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                        <td align=\"left\">\n";
         $salida .= "                          ".$resultado['prefijo']."-".$resultado['numero'];
         $salida .= "                         </td>\n";
         $salida .= "                       <td width=\"8%\" align=\"center\">\n";
         $salida .= "                       <a title='FECHA DE REGISTRO'>";
         $salida .= "                        <b>FECHA</b>";
         $salida .= "                       </a>";
         $salida .= "                       </td>\n";
         $salida .= "                        <td align=\"left\">\n";
         $salida .= "                          ".substr($resultado['fecha_registro'],0,10);
         $salida .= "                         </td>\n";
         $salida .= "                       </tr>\n";
         $salida .= "                        <td align=\"left\">\n";
         $salida .= "                          ";
         $salida .= "                         </td>\n";
         $salida .= "                       </tr>\n";
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
         $salida .= "                   </table>\n";

         $salida .= "                   <br>\n"; 
         
         $salida .= "                 <table width=\"95%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                        <td COLSPAN='8' align=\"center\">\n";
         $salida .= "                         <a>";
         $salida .= "                           <b>PRODUCTOS QUE CONTIENE ESTE DOCUMENTO</b>";
         $salida .= "                         </a>";
         $salida .= "                        </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         
         $salida .= "                        <td WIDTH='15%' align=\"center\">\n";
         $salida .= "                          <a TITLE='CODIGO DEL PRODUCTO'>";
         $salida .= "                            <b>CODIGO</b>";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                        <td WIDTH='35%' align=\"center\">\n";
         $salida .= "                          <a TITLE='DESCRIPCION DEL PRODUCTO'>";
         $salida .= "                            <b>DESCRIPCION</b>";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                        <td WIDTH='10%' align=\"center\">\n";
         $salida .= "                          <a TITLE='FECHA VENCIMIENTO'>";
         $salida .= "                            <b>FECHA VENCIMIENTO</b>";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         $salida .= "                        <td WIDTH='10%' align=\"center\">\n";
         $salida .= "                          <a TITLE='LOTE'>";
         $salida .= "                            <b>LOTE</b>";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         
         $salida .= "                        <td WIDTH='5%' align=\"center\">\n";
         $salida .= "                          <a TITLE='CANTIDAD'>";
         $salida .= "                            <b>CANTIDAD</b>";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         
          $salida .= "                        <td WIDTH='30%' align=\"center\">\n";
         $salida .= "                          <a TITLE='NOVEDAD'>";
         $salida .= "                            <b>NOVEDADES</b>";
         $salida .= "                          </a>";
         $salida .= "                        </td>\n";
         
         $salida .= "                       </tr>\n";
         $valorTotal=0;
      foreach($DocDetalleVerificacion as $doc_val=>$valor)
       {
         
                 $salida .= "                    <tr>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       <a>";
                 $salida .= "                       ".$valor['codigo_producto'];
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       ".$valor['descripcion_producto'];
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       <a>";
                 $salida .= "                       ".$valor['fecha_vencimiento'];
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       <a>";
                 $salida .= "                       ".$valor['lote'];
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       ".$valor['cantidad'];
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\">\n";
                 $salida .= "                       <table width=\"100%\" class=\"modulo_table_list\" border=\"1\">";
                 $salida .= "                       <tr>";
                 $salida .= "                         <td>".$valor['descripcion'].".(Sel. Por Usuario)</td>";
                 $salida .= "                       </tr>";
                 $salida .= "                       <tr>";
                 $salida .= "                         <td>".$valor['novedad_anexa']."</td>";
                 $salida .= "                       </tr>";
                 $salida .= "                       </table>";
                 $salida .= "                      </td>\n";
                 $salida .= "                    </tr>\n";
         
      }
                 
         $salida .= "                   </table>\n";
    //$salida .=ThemeCerrarTabla();
    
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

