<?php
		$_ROOT='../../../';
		$VISTA='HTML';
		include $_ROOT.'includes/enviroment.inc.php';
		
      IncludeClass('ConexionBD');
      IncludeClass('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
      $est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
    
      $fileName = "themes/$VISTA/" .GetTheme(). "/module_theme.php";

  
       
    //vaR_DUMP($limite);
    if($limite=='')
    {
      $limite=null;
    }
		
    $obje=new Formulacion_ExternaESMSQL();
   
    $Cabecera=$obje->Consultar_OrdenSuministro_por_paciente($_REQUEST['bodegas_doc_id'],$_REQUEST['numeracion']); 
	  $datos =$obje->Listado_Pacientes_Reales($_REQUEST['bodegas_doc_id'],$_REQUEST['numeracion']);
    
	
    $TITLE="DETALLE DEL SUMINISTRO";
    print(ReturnHeader($TITLE));
		print(ReturnBody());
		$path = SessionGetVar("rutaImagenes");
         $salida .= "                  <br>";
         $salida .= "                  <br>";
         $salida .= "                  <br>";
         $salida .= "                 <table  width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr >\n";
         $salida .= "                       <td colspan='6' align=\"center\" class=\"formulacion_table_list\">\n";
         $salida .= "                        SUMINISTRO  <u>#:".$_REQUEST['numeracion']."</u>";
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
         
      
			$salida .= "<br>";
         
			$salida .= "                    <br>\n";

			$salida .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
			$salida .= "	<tr class=\"formulacion_table_list\" >\n";
			$salida .= "		<td width=\"10%\">IDENTIFICACION</td>\n";
			$salida .= "			<td width=\"30%\" >PACIENTE</td>\n";
			$salida .= "	</tr>\n";
				
			foreach($datos as $k1 => $dtl)
			{
				($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
				($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
				$salida .= "	<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
				$salida .= "			<td align=\"center\"><b>".$dtl['identificacion']."</b></td>\n";
				$salida .= "		<td align=\"left\">".$dtl['nombre_completo']."</td>\n";
				
				$salida .= "	<tr   colspan=\"10\">\n";
				$salida .= "    <td  colspan=\"10\" align=\"center\">";
				$salida .= "<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
				$salida .= "	<tr class=\"formulacion_table_list\" >\n";
				$salida .= "		<td width=\"10%\">CODIGO</td>\n";
				$salida .= "		<td width=\"40%\" >DESCRIPCION</td>\n";
				$salida .= "		<td width=\"10%\" >FECHA VENCIMIENTO</td>\n";
				$salida .= "		<td width=\"40%\" >LOTE</td>\n";
				$salida .= "		<td width=\"15%\" >CANTIDAD</td>\n";
				$salida .= "	</tr>\n";
				$datos_x =$obje->Listado_Productos_por_paciente($_REQUEST['bodegas_doc_id'],$_REQUEST['numeracion'],$dtl['identificacion']);
				foreach($datos_x as $k1 => $deta)
				{

					$salida .= "           <tr >";	
					$salida .= "		<td  width=\"10%\" align=\"center\"><b>".$deta['codigo_producto']." </b></td>\n";
					$salida .= "		<td  width=\"40%\" align=\"left\">".$deta['descripcion']."</td>\n";
					$salida .= "			<td width=\"15%\" align=\"center\">".$deta['fecha_vencimiento']."</td>\n";
					$salida .= "			<td width=\"15%\" align=\"center\">".$deta['lote']."</td>\n";
					$salida .= "			<td width=\"15%\" align=\"center\">".$deta['cantidad']."</td>\n";

					$salida .= "		</tr>\n";
				
				
				}

				$salida .= " 	    </table>";
				$salida .= "      </td>\n";
				$salida .= "	</tr>\n";
				$salida .= "	</tr>\n";
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
                           $salida .= "                           ".UserGetUID()."-".$Cabecera['nombre'];
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
    //$salida .=ThemeCerrarTabla();*/
    echo $salida; 
  
	
	print(ReturnFooter());
?>