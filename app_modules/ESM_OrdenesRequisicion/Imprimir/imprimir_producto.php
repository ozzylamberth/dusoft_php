<?php
		$_ROOT='../../../';
		$VISTA='HTML';
		include $_ROOT.'includes/enviroment.inc.php';
		IncludeClass('Consultas_Impresion',null,'app','ESM_OrdenesRequisicion');
		
    //include "../../../app_modules/Cg_Movimientos/classes/MovimientosSQL.class.php";
    $fileName = "themes/$VISTA/" .GetTheme(). "/module_theme.php";
   
    //print_r($_REQUEST);
       
    //vaR_DUMP($limite);
    if($limite=='')
    {
      $limite=null;
    }
    $sql=new Consultas_Impresion();
    
    $Cabecera=$sql->Consultar_OrdenRequisicion($_REQUEST['orden_requisicion_id']); 
    $Detalle=$sql->Consultar_OrdenRequisicionDetalle($_REQUEST['orden_requisicion_id']); 
    $BodegaSatelite=$sql->Bodega($Cabecera['empresa_id'],$Cabecera['centro_utilidad'],$Cabecera['bodega']);
    echo $consulta->mensajeDeError;
    $TITLE="DETALLE DEL DOCUMENTO";
    print(ReturnHeader($TITLE));
		print(ReturnBody());
    $path = SessionGetVar("rutaImagenes");
         $salida .= "                  <br>";
         $salida .= "                  <br>";
         $salida .= "                  <br>";
         $salida .= "                 <table rules=\"all\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr >\n";
         $salida .= "                       <td colspan='6' align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                        ORDEN DE REQUISICION <u>#:".$Cabecera['orden_requisicion_id']."</u>";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr class=\"modulo_list_claro\">\n";
         $salida .= "                       <td  align=\"center\" class=\"modulo_table_list_title\">\n";
         $salida .= "                        FECHA DE CREACION: ";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                        ".$Cabecera['fecha_registro'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" class=\"modulo_table_list_title\">\n";
         $salida .= "                        LUGAR CREACION: ";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                        ".$Cabecera['razon_social'];
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\" class=\"modulo_table_list_title\">\n";
         $salida .= "                        <b>USUARIO CREADOR</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                        ".$Cabecera['nombre'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" class=\"modulo_table_list_title\">\n";
         $salida .= "                        <b>USUARIO AUTORIZADOR</b>";
         $salida .= "                       </td>\n";
         $UAutorizador=$sql->NombreUsu($Cabecera['usuario_id_autorizador']);
         $Usuario=$sql->NombreUsu(UserGetUID());
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                        ".$UAutorizador['nombre'];
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\" class=\"modulo_table_list_title\">\n";
         $salida .= "                        <b>ESM</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                        ".$Cabecera['tipo_id_tercero']." ".$Cabecera['tercero_id']."-".$Cabecera['nombre_tercero'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" class=\"modulo_table_list_title\">\n";
         $salida .= "                        <b>TIPO FUERZA</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                        ".$Cabecera['tipo_fuerza'];
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">\n";
         $salida .= "                        <b>TIPO DE REQUISICION :</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\" colspan=\"2\">\n";
         $salida .= "                        ".$Cabecera['descripcion_orden_requisicion'];
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\" class=\"modulo_table_list_title\" colspan=\"4\">\n";
         $salida .= "                        <b>OBSERVACION</b>";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
         $salida .= "                        ".$Cabecera['observacion'];
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         
         $salida .= "                 </table>\n";
         
         if(!empty($BodegaSatelite))
        {
        
        $salida .= "<br>";
        $salida .= "<center>";
        $salida .= "  <table border=\"1\" width=\"60%\" align=\"center\" rules=\"all\">\n";
        $salida .= "    <tr class=\"modulo_list_claro\">";
        $salida .= "          <td  class=\"formulacion_table_list\" colspan=\"4\">";
        $salida .= "              TRASLADO";
        $salida .= "          </td>";
        $salida .= "    </tr>";
         $salida .= "    <tr class=\"modulo_list_claro\">";
        $salida .= "          <td  class=\"modulo_table_list_title\">";
        $salida .= "              CENTRO UTILIDAD";
        $salida .= "          </td>";
        $salida .= "          <td >";
        $salida .= "              ".$BodegaSatelite['centro'];
        $salida .= "          </td>";
        $salida .= "          <td class=\"modulo_table_list_title\">";
        $salida .= "              BODEGA";
        $salida .= "          </td>";
        $salida .= "          <td >";
        $salida .= "              ".$BodegaSatelite['descripcion'];
        $salida .= "          </td>";
        $salida .= "    </tr>";
        $salida .= "  </table>";
        $salida .= "</center>";
      }
       $salida .= "<br>";
         
         $salida .= "                    <br>\n";
         
         
         
         $salida .= "                 <table rules=\"all\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr >\n";
         $salida .= "                       <td colspan='6' align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                        PRODUCTOS SOLICITADOS";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr class=\"modulo_list_claro\">\n";
         $salida .= "                       <td  align=\"center\" class=\"modulo_table_list_title\">\n";
         $salida .= "                        CODIGO PRODUCTO ";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" class=\"modulo_table_list_title\">\n";
         $salida .= "                       NOMBRE ";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" class=\"modulo_table_list_title\">\n";
         $salida .= "                       INFORMACION ";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" class=\"modulo_table_list_title\">\n";
         $salida .= "                       CANTIDAD SOLICITADA ";
         $salida .= "                       </td>\n";
		  $salida .= "                       <td align=\"center\" class=\"modulo_table_list_title\">\n";
         $salida .= "                       CANTIDAD AUTORIZADA ";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         foreach($Detalle as $key => $producto)
         {
         $salida .= "           <tr class=\"modulo_list_claro\">";
         $salida .= "             <td>";
         $salida .= "             ".$producto['mindefensa'];
         $salida .= "             </td>";
         $salida .= "             <td>";
         $salida .= "             ".$producto['descripcion'];
         $salida .= "             </td>";
         
         if($producto['sw_pactado']=='0')
        {
        $class=" class=\"label_error\"" ;
        $mensaje=" PRODUCTO NO PACTADO " ;
        }
        else
          {
          $class=" " ;
          $mensaje=" PRODUCTO PACTADO " ;
          }
         
         $salida .= "             <td ".$class.">";
         $salida .= "             ".$mensaje;
         $salida .= "             </td>";
		   $salida .= "             <td align=\"center\">";
         $salida .= "             <b>".$producto['cantidad_solicitada_inicial']."</b>";
         $salida .= "             </td>";
         $salida .= "             <td align=\"center\">";
         $salida .= "             <b>".$producto['cantidad_solicitada']."</b>";
         $salida .= "             </td>";
         $salida .= "           </tr>";
         }
         
         $salida .= "               </table>";
         $salida .= "               <br>";
         $salida .= "               <br>";
         $salida .= "               <br>";
         $salida .= "               <br>";
         
         $salida .= "                 <table rules=\"all\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                   <tr>";
         $salida .= "                       <td width=\"20%\" align=\"center\">\n";
         $salida .= "                         <b>FIRMA</b>";
         $salida .= "                       </td>\n";
         $salida .= "                   </tr>";
         $salida .= "                 </table>";
         $salida .= "               <br>";
         $salida .= "               <br>";
         $salida .= "               <br>";
         $salida .= "                 <table width=\"95%\" align=\"center\">\n";
         $salida .= "                   <tr>";
         $salida .= "                       <td align=\"center\">\n";
                           $salida .= "                   <table rules=\"all\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
                           $salida .= "                   <tr>";
                           $salida .= "                       <td align=\"center\">\n";
                           $salida .= "                          <b>USUARIO</b>";
                           $salida .= "                       </td>\n";
                           $salida .= "                       <td align=\"left\">\n";
                           $salida .= "                         <a>";
                           $salida .= "                           ".UserGetUID()."-".$Usuario['nombre'];
                           $salida .= "                         </a>";
                           $salida .= "                       </td>";
                           $salida .= "                   </tr>";
                           $salida .= "                   <tr>";
                           $salida .= "                       <td align=\"center\">\n";
                           $salida .= "                          <b>FECHA DE IMPRESION</b>";
                           $salida .= "                       </td>\n";
                           $salida .= "                       <td align=\"left\">\n";
                           $salida .= "                         <a>";
                           $salida .= "                           ".date("Y-m-d H:i:s");
                           $salida .= "                         </a>";
                           $salida .= "                         </td>";
                           $salida .= "                   </tr>";
                           $salida .= "                 </table>";
         $salida .= "               </td>";
         $salida .= "                 </tr>";
         $salida .= "                 </table>";
         $salida .= "                   <br>\n";         
    //$salida .=ThemeCerrarTabla();
    echo $salida; 
  
	
	print(ReturnFooter());
?>