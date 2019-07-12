<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: MensajesModuloHTML.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: MensajesModuloHTML
  * Clase encargada de crear las formas para mostrar el menu principal del modulo 
  * y los mensajes al usuario
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class MensajesModuloHTML	
	{
		/**
		* Constructor de la clase
		*/
		function MensajesModuloHTML(){}
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action Vector que continen los link de la aplicacion
    *
		* @return string
		*/
		function FormaMenuInicial($action,$consulta_eliminados)
		{
            $rpt  = new GetReports();
			$html  = ThemeAbrirTabla('REPORTES');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL</td>\n";
			$html .= "				</tr>\n";
		    $html .= "        <tr>\n";
		    $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
		    $html .= "            <a href=\"".$action['logauditoria']."\"><b>CONSULTAR PEDIDOS</b></a>\n";
		    $html .= "          </td>\n";
		    $html .= "        </tr>\n";
		    $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
		    $html .= "            <a href=\"".$action['eliminar_reservapedidos']."\"><b>ELIMINAR RESERVA PEDIDOS</b></a>\n";
		    $html .= "          </td>\n";
			if(!empty($consulta_eliminados))
			{
		     $html .= "        <tr>\n";
		     $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
		     $html .= "            <a href=\"".$action['consulta_eliminados']."\"><b>CONSULTAR RESERVA PEDIDOS ELIMINADOS</b></a>\n";
		     $html .= "          </td>\n";			  
		     $html .= "        </tr>\n";	
			}
		    $html .= "    </tr>\n";
      
      
			$html .= "</table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
		
    /**
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		*
		* @param array $action vector que continen los link de la aplicacion
    * @param string $mensaje Cadena con el texto del mensaje a mostrar 
    *         en pantalla
    *
		* @return string
		*/
		function FormaMensajeModulo($action,$mensaje,$imprimir)
		{
			$html  = ThemeAbrirTabla('MENSAJE');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$mensaje."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "		  <table width=\"100%\" align=\"center\">\n";
			$html .= "		    <tr>\n";
			$html .= "		      <td align=\"center\">\n";
			$html .= "			      <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				      <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
			$html .= "			      </form>";
			$html .= "		      </td>\n";
      
      if(!empty($imprimir))
      {
        $rpt  = new GetReports();
  			$html .= $rpt->GetJavaReport('app','NotasFacturasContado',$imprimir['nombre_reporte'],$imprimir,
  																	array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
  			$fnc  = $rpt->GetJavaFunction();
        $html .= "		      <td align=\"center\">\n";
  			$html .= "			      <form name=\"impresion\" action=\"javascript:".$fnc."\" method=\"post\">";
  			$html .= "				      <input class=\"input-submit\" type=\"submit\" name=\"imprimir\" value=\"Imprimir\">";
  			$html .= "			      </form>";
  			$html .= "		      </td>";
      }
      
			$html .= "		    </tr>";
			$html .= "		  </table>";
			$html .= "		</td>";
			$html .= "  </tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
		
		
	 /***************************************************************
	 *Funcion:salida para mostrar pedidos farmacia gral
	  ROMA. 												5-12-2012
	 ***************************************************************/
		function FormaPedGral($action,$pedidoid)
		{
          
		  $cls = AutoCarga::factory("ListaReportes","classes","app","Inv_ConsultaPedidos_Farmacia");
		  $cls2 = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalSQL","classes","app","PedidosFarmacia_A_BodegaPrincipal"); 
		  
		  $listado = $cls->Obtener_ReporteGral($pedidoid);
		  
		  $tproducto = $cls2->TipoProductos();
		  $nombre=$cls2->GetNombreUsuarioImprime();

		  $sty = " style=\"text-align:left;text-indent:8pt\" ";
		
		  $estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
		  $estilo2 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px; text-indent:6pt\""; 
		  $estilo3 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\""; 
		 
		 $html .= "	<table border=\"0\" align =\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"100%\">\n";

			 
			 foreach($listado as $k=>$v)
			 {
			 
			   $InfEmp = $cls2->ObtenerInformacion($v['farmacia_id']);
	           $ped=$cls2->ObtenerDetalleDeSolicitu2($v['solicitud_prod_a_bod_ppal_id']);
	           $cab=$cls2->ObtenerCabecera($v['solicitud_prod_a_bod_ppal_id']);
			   $ped2=$cls2->ConsultarUsuarioRealizaPedido($v['solicitud_prod_a_bod_ppal_id']);
			  
	    	    $est   = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
				$titulo = " <b $est>&nbsp;DOCUMENTO DE PEDIDO No ".$v['solicitud_prod_a_bod_ppal_id']." </b>";
				
				$html .= "<br><br>";
				$html .= "		<tr>\n";		  
				$html .= "		  <td>\n";

				$html .= "		  <table border=\"0\" align =\"center\" cellpading=\"0\" cellspacing=\"0\"  width=\"100%\" >\n";
				$html .= "		   <tr>\n";		  
				$html .= "		      <td>\n";
				$html .= "		      <img src=\"".GetThemePath()."/images/logopedido.png\" border='0' >\n";			
				$html .= "		      </td>\n";		  
				$html .= "		      <td>\n";				
				$html .= "		       ".$titulo."\n";
				$html .= "		     </td>\n";		  
				$html .= "		    </tr>\n";
				
	    		$html .= "		   <tr>\n";	
	    		$html .= "		     <td><br>\n";	
	    		$html .= "		     </td>\n";	
	    		$html .= "		   </tr>\n";	
				
				$html .= "		  <tr>\n";	
                $html .= "		   <table border=\"1\" align =\"center\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\" width=\"100%\" rules=\"all\">\n";
				$html .= "		    <tr $estilo2 height=\"21\">\n";
				$html .= "			  <td width=\"15%\" ><b>PAIS:</b></td>\n";
				$html .= "			  <td width=\"25%\"><b>".$InfEmp['0']['pais']."</b></td>\n";
				$html .= "			  <td width=\"15%\" ><b>DEPARTAMENTO - MUNICIPIO:</b></td>\n";
				$html .= "			  <td width=\"25%\"><b>".$InfEmp['0']['departamento']."- ".$InfEmp['0']['municipio']."</b></td>\n";
	    		$html .= "		    </tr>\n";
				$html .= "		    <tr $estilo2 height=\"21\">\n";
				$html .= "			  <td width=\"25%\" ><b>COD. FARMACIA:</b></td>\n";
				$html .= "			  <td width=\"25%\" ><b>".$InfEmp['0']['empresa_id']."-".$cab['centro_utilidad']."</b></td>\n";
				$html .= "			  <td width=\"25%\" ><b>FARMACIA:</b></td>\n";
				$html .= "			  <td width=\"55%\"><b>".$InfEmp['0']['razon_social']."-".$cab['nombre_bodega']."</b></td>\n";
				$html .= "		    </tr>\n";                
				$html .= "			<tr $estilo2 height=\"21\">\n";
				$html .= "			  <td width=\"25%\" ><b>DIRECCION:</b></td>\n";
				$html .= "			  <td><b>".$InfEmp['0']['direccion']."</b></td>\n";
	           	$html .= "			  <td width=\"25%\"><b>TELÉFONO:</b></td>\n";
				$html .= "			  <td><b>".$InfEmp['0']['telefonos']."</td>\n";
				$html .= "			</tr>\n";				
				$html .= "		    <tr $estilo2 height=\"21\">\n";
				$html .= "			  <td colspan=\"4\"><label><b>Observacion :</b></label> ".$cab['observacion']."</td>";
				$html .= "		    </tr>\n";				
				$html .= "		   </table>";
				$html .= "		  </tr>\n";
				
			    foreach($tproducto as $key => $tp)
                {	
				 $Productos = $cls2->ObtenerDetalleDeSolicitu($v['solicitud_prod_a_bod_ppal_id'],$v['farmacia_id'],$v['bodega'],$v['centro_utilidad'],$tp['tipo_producto_id'],"-1");
                 
                 if(!empty($Productos))
                 {
				    $html .= "	 <tr>\n";
			        $html .= "     <table width=\"95%\" align=\"center\" cellpading=\"0\" cellspacing=\"0\">\n";
			        $html .= "       <tr $estilo2 height=\"21\">\n";
					$html .= "         <td><b> - PEDIDO POR MEDICAMENTOS ".$tp['descripcion'].": ";
					$html .= "         </td>\n"; 
					$html .= "       </tr>\n"; 
					$html .= "     </table>\n"; 					
					$html .= "	   <table width=\"100%\" style=\"border:1px solid #000000;font-size:9.5px;\" rules=\"all\" align=\"center\">\n";
					$html .= "		 <tr $estilo2 height=\"21\" align=\"center\">\n";
					$html .= "			<td><b>CODIGO</b></td>\n";
					$html .= "			<td><b>PRODUCTO</b></td>\n";
					$html .= "			<td><b>CANTIDAD</td>\n";
					$html .= "			<td width=\"5%\"><b>RQ.AUT</td>\n";
					$html .= "			<td><b>OBS</td>\n";
					$html .= "		</tr>\n";
                    foreach($Productos as $key => $prod)
                    {
						$html .= "	<tr>\n";
						$html .= "		<td>".$prod['codigo_producto']."</td>\n";
						$html .= "		<td>".$prod['producto']."</td>\n";
						$html .= "		<td>".FormatoValor($prod['cantidad_solic'])."</td>\n";
						$html .= "		<td align=\"center\">";
						if($prod['sw_requiereautorizacion_despachospedidos']=='1')
						  $html .= "  <img title=\"EL PRODUCTO REQUIERE AUTORIZACION PARA SER DESPACHADO\" src=\"".GetThemePath()."/images/alarma.gif\" border='0' >	";
						$html .= "	   </td>";
						$html .= "	   <td> ";
						$html .= "				<i><u><b>";
						$html .= "					".$prod['observacion'];
						$html .= "				</b></u></i>";
						$html .= "	   </td>\n";
						$html .= "  </tr>\n";
                    }					
					$html .= "	   </table>";
				
				    $html .= "	 </tr>\n";					
				 }
				 
				} //endfor $tproducto		 		

				$html .= "	 <tr>\n";
				$html .= "     <table  width=\"95%\" valign=\"bottom\">\n";
	            $html .= "        <tr class=\"label\"  >\n";
	            $html .= "          <td align=\"LEFT\" height=\"50\">________________________________________</td>\n";
	            $html .= "        </tr>\n";        
	            $html .= "         <tr class=\"label\" >\n";
	            $html .= "           <td align=\"LEFT\">FIRMA Y SELLO AUTORIZADO</td>\n";
	            $html .= "         </tr>\n";
	            $html .= "	   </table>\n";
				$html .= "	 </tr>\n";

				$html .= "	 <tr>\n";
				$html .= "   <table align='right' border='0' width='99%' style=\"border:1px solid #000000;font-size:8.5px;\">";
				$html .= "       <tr align='right'>\n";
				$html .= "         <td width='50%' align=\"right\" >";
				$html .= "           USUARIO :";
				$html .= "            ".$nombre[0]['nombre']."&nbsp;";
				$html .= "         </td>\n";
				$html .= "       </tr>\n";
				$html .= "       <tr align='right'>\n";
				$html .= "         <td width='50%' align=\"right\" >";
				$html .= "            ".$nombre[0]['descripcion']."&nbsp;";
				$html .= "         </td>\n";
				$html .= "       </tr>\n";
				$html .= "       <tr align='right'>\n";
				$html .= "         <td width='50%' align=\"right\" >";
				$html .= "           FECHA DE IMPRESION :".date("Y-m-d (H:i:s a)")."&nbsp;";
				$html .= "         </td>\n";
				$html .= "      </tr>\n";
				$html .= "       <tr align='right'>\n";
				$html .= "         <td width='50%' align=\"right\" >";
				$html .= "           USUARIO (REALIZO EL PEDIDO) :";
				$html .= "           ".$ped2[0]['nombre']."&nbsp;";
				$html .= "         </td>\n";
				$html .= "       </tr>\n";
				$html .= "       <tr align='right'>\n";
				$html .= "         <td width='50%' align=\"right\" >";
				$html .= "          ".$ped2[0]['descripcion']."&nbsp;";
				$html .= "         </td>\n";
				$html .= "       </tr>\n";
				$html .= "       <tr align='right'>\n";
				$html .= "         <td width='50%' align=\"right\" >";
				$html .= "          FECHA DE REGISTRO :".$ped2[0]['fecha_registro']."&nbsp;";
				$html .= "         </td>\n";
				$html .= "       </tr>\n";
				
				// $html .= "       <tr>\n";
				// $html .= "         <td>\n";
			    // $html .= "	        <h1 class=\"SaltoDePagina\"></h1>\n";
				// $html .= "         </td>\n";
				// $html .= "       </tr>\n";
				
				$html .= "    </table>\n";	
                //$html .= "	   <h1 class=\"SaltoDePagina\"></h1>\n";				
				$html .= "	 </tr>\n";

				$html .= "	 <tr>\n";				
				$html .= "	   <td>\n";				
				$html .= "	   </td>\n";				
				$html .= "	 </tr>\n";				
				
				$html .= "		 </table><br>\n";
				$html .= "	   <h1 class=\"SaltoDePagina\"> </h1>\n";
				$html .= "		  </td>\n";		  
				$html .= "		</tr>\n";		  
				$html .= "		<br><br>\n";		  
	        		  
				$html .= "		<tr>\n";			  
				$html .= "		  <td>\n";			  
				$html .= "		  </td>\n";			  
				$html .= "		</tr>\n";
				
			 } //end main for

		 $html .= "	</table>";	 
		 
		 
		 
			
		 return $html;
		}		
		
		
		
		
		
  }
?>