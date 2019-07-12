<?php
		$_ROOT='../../../';
		$VISTA='HTML';
		include $_ROOT.'includes/enviroment.inc.php';
		IncludeClass('Consultas_Impresion_suministro',null,'app','ESM_OrdenesRequisicion');
		
    //include "../../../app_modules/Cg_Movimientos/classes/MovimientosSQL.class.php";
    $fileName = "themes/$VISTA/" .GetTheme(). "/module_theme.php";
   
  
       
    //vaR_DUMP($limite);
    if($limite=='')
    {
      $limite=null;
    }
    $sql=new Consultas_Impresion_suministro();
    
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
         $salida .= "                 <table  width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr >\n";
         $salida .= "                       <td colspan='6' align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                        ORDEN DE SUMINISTRO <u>#:".$Cabecera['orden_requisicion_id']."</u>";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr >\n";
         $salida .= "                       <td  align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                        FECHA DE CREACION: ";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                        ".$Cabecera['fecha_registro'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                        LUGAR CREACION: ";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                        ".$Cabecera['razon_social'];
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                        <b>USUARIO CREADOR</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                        ".$Cabecera['nombre'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                        <b>USUARIO AUTORIZADOR</b>";
         $salida .= "                       </td>\n";
         $UAutorizador=$sql->NombreUsu($Cabecera['usuario_id_autorizador']);
         $Usuario=$sql->NombreUsu(UserGetUID());
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                        ".$UAutorizador['nombre'];
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                        <b>ESM</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                        ".$Cabecera['tipo_id_tercero']." ".$Cabecera['tercero_id']."-".$Cabecera['nombre_tercero'];
         $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" class=\"formulacion_table_list\">\n";
        $salida .= "                        <b>UBICACION</b>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"left\">\n";
        $salida .= "                        ".$Cabecera['ubicacion'];
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr>\n";

         $salida .= "                       <td align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                        <b>DIRECCION</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                        ".$Cabecera['direccion'];
         $salida .= "                       </td>\n";
		 
		 
         $salida .= "                       <td align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                        <b>TIPO FUERZA</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\">\n";
         $salida .= "                        ".$Cabecera['tipo_fuerza'];
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\" class=\"formulacion_table_list\" colspan=\"2\">\n";
         $salida .= "                        <b>TIPO DE REQUISICION :</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"left\" colspan=\"2\">\n";
         $salida .= "                        ".$Cabecera['descripcion_orden_requisicion'];
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\" class=\"formulacion_table_list\" colspan=\"4\">\n";
         $salida .= "                        <b>OBSERVACION</b>";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"left\"  colspan=\"4\">\n";
         $salida .= "                        ".$Cabecera['observacion'];
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         
         $salida .= "                 </table>\n";
         
         if(!empty($BodegaSatelite))
        {
        
        $salida .= "<br>";
        $salida .= "<center>";
        $salida .= "  <table class=\"modulo_table_list\"  width=\"60%\" align=\"center\" >\n";
        $salida .= "    <tr >";
        $salida .= "          <td  class=\"formulacion_table_list\" colspan=\"4\">";
        $salida .= "              TRASLADO";
        $salida .= "          </td>";
        $salida .= "    </tr>";
         $salida .= "    <tr >";
        $salida .= "          <td  class=\"formulacion_table_list\">";
        $salida .= "              CENTRO UTILIDAD";
        $salida .= "          </td>";
        $salida .= "          <td >";
        $salida .= "              ".$BodegaSatelite['centro'];
        $salida .= "          </td>";
        $salida .= "          <td class=\"formulacion_table_list\">";
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
         
         
         
         $salida .= "                 <table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr >\n";
         $salida .= "                       <td colspan='6' align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                        PRODUCTOS SOLICITADOS";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr >\n";
         $salida .= "                       <td  align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                        CODIGO PRODUCTO ";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                       NOMBRE ";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                       INFORMACION ";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                       CANTIDAD SOLICITADA ";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         foreach($Detalle as $key => $producto)
         {
         $salida .= "           <tr >";
         $salida .= "             <td>";
         $salida .= "             ".$producto['codigo_producto'];
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
         $salida .= "             <b>".$producto['cantidad_solicitada']."</b>";
         $salida .= "             </td>";
		 
		 
		$salida .= " 		<tr   colspan=\"10\">\n";
		$salida .= "      <td  colspan=\"10\" align=\"center\">";
		$salida .= " 	<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
		
		$datos_x =$sql->Consultar_Registros_tmp_suministro($_REQUEST['orden_requisicion_id'],$producto['codigo_producto']);
		foreach($datos_x as $k1 => $deta)
		{
		
		$salida .= "           <tr >";		$salida .= "			<td  colspan=\"1\"align=\"center\"><b>".$deta['tipo_id_paciente']." ".$deta['paciente_id']."</b></td>\n";
		$salida .= " 			<td  colspan=\"8\" align=\"left\">".$deta['nombre_completo']."</td>\n";
		$salida .= " 				<td colspan=\"1\" align=\"center\"><input type=\"text\" value=\"".$deta['cantidad']."\" name=\"".$dtl['codigo_producto']."@".$deta['tipo_id_paciente']."@".$deta['paciente_id']."\" id=\"".$dtl['codigo_producto']."@".$deta['tipo_id_paciente']."@".$deta['paciente_id']."\" class=\"input-text\" style=\"width:55%\" onkeypress=\"return acceptNum(event)\"></td>\n";
       
		$salida .= " 			</tr>\n";
		$c++;
		 // $html .= "      <input type=\"hidden\" name=\"subregistros\" id=\"subregistros\" value=\"".$c."\">";
		
		}
	
		$salida .= " 	    </table>";
		$salida .= " 	      </td>\n";
		$salida .= " 		</tr>\n";
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