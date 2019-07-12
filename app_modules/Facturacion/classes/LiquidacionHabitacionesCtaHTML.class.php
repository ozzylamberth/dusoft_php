<?php
  /******************************************************************************
  * $Id: LiquidacionHabitacionesCtaHTML.class.php,v 1.2 2006/12/07 14:47:17 lorena Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.2 $ 
	* 
	* @autor Lorena Aragon Galindo
  ********************************************************************************/
  IncludeClass('LiquidacionHabitacionesCta','','app','Facturacion');
  
	class LiquidacionHabitacionesCtaHTML
	{
		function LiquidacionHabitacionesCtaHTML(){}
		/**********************************************************************************
		* Funcion donde se buscan las Notas De Ajuste temporales 
		* 
		* @return array 
		***********************************************************************************/
		function CrearFormaLiquidacionManualHabitaciones($EmpresaId,$accionEliminar,$accionModificar,$accionInsertar,$accionCargarCuenta,$accionCancelar,
    $Cuenta,$TipoId,$PacienteId,$PlanId,$Nivel,$Fecha,$Ingreso,$mensaje){	
    		
      $VISTA='HTML';
			$funcionesHab = new LiquidacionHabitacionesCta;
      $html .= "<script>\n";    
      $html .= "    function PasarValor(forma)\n";
      $html .= "    {\n";
      $html .= "        var v;\n";
      $html .= "        var vect;\n";     
      $html .= "        v=forma.tipocama.value;\n";
      $html .= "        a=v.split('||');\n";
      $html .= "        forma.excedenteN.value = a[0];\n"; 
      $html .= "        if(a[1] > 0){\n";
      $html .= "          forma.precioN.value = a[1]; \n"; 
      $html .= "        }\n"; 
      $html .= "        else{\n";
      $html .= "          forma.precioN.value = (parseInt(a[2]) + (a[2]*a[3]/100)); \n";            
      $html .= "        }\n";       
      $html .= "    }\n";  
      $html .="     function DetalleCamas(Ingreso)\n";
      $html .="     {\n";
      $html .="       var url='reports/$VISTA/movimientoscamas.php?ingreso='+Ingreso;\n";
      $html .="       window.open(url,'','width=900,height=550,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no')\n";
      $html .="     }\n";
      $html .= "    function ValidaCamposObligatorios(forma)\n";
      $html .= "    {\n";      
      $html .= "      if(forma.tipocama.value==-1){\n";
      $html .="         alert('DEBE ESPECIFICAR EL TIPO DE CAMA');\n"; 
      $html .="         return false;\n"; 
      $html .="       }\n"; 
      $html .= "      if(forma.dpto.value==-1){\n";
      $html .="         alert('DEBE ESPECIFICAR EL DEPARTAMENTO');\n"; 
      $html .="         return false;\n"; 
      $html .="       }\n";
      $html .="       return true;\n"; 
      $html .="     }\n"; 
      $html .= "</script>\n";            
      $html .= ThemeAbrirTabla('LIQUIDACION MANUAL DE HABITACIONES CUENTA No. '.$Cuenta);      
      if($mensaje){
        $html .= "            <table width=\"60%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
        $html .= "             <tr><td class='label_error' align='center'>".$mensaje."</td></tr>";
        $html .= "           </table>";   
      }
      if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php")) 
      {
        die(MsgOut("Error al incluir archivo","El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
      }
      //echo 'vector==>>'.'<pre>';
      //print_R($_SESSION['LIQUIDACION_HABITACIONES']);
      
      if(!is_array($_SESSION['LIQUIDACION_HABITACIONES'])){
        
        $liquidacionHab = new LiquidacionHabitaciones;
        $_SESSION['LIQUIDACION_HABITACIONES'] = $liquidacionHab->LiquidarCargosInternacion($Cuenta,false);                                   
        
      }
        
      $html .= " <form name=\"formainicial\" action=\"$accionModificar\" method=\"post\">";
      $html .= "  <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"95%\" align=\"center\"  class=\"modulo_table_list_title\">";
      $html .= "   <tr>";
      $html .= "    <td width=\"6%\">Tarif.</td>";
      $html .= "    <td width=\"6%\">Cargo</td>";               
      $html .= "    <td width=\"50%\" nowrap>Descripción</td>";         
      $html .= "    <td width=\"15%\">Precio Uni.</td>";                  
      $html .= "    <td width=\"6%\">Días</td>";                                      
      $html .= "    <td width=\"20%\">Val. No Cub. Uni.(Excedente)</td>"; 
      $html .= "    <td width=\"10%\">Total Val. Cub</td>";                         
      $html .= "    <td width=\"10%\">Total Val. No Cub</td>";
      $html .= "    <td width=\"2%\"></td>";  
      $html .= "   </tr>";
      $hab=$_SESSION['LIQUIDACION_HABITACIONES'];              
      for($i=0; $i<sizeof($hab); $i++){                                  
        $html .= "   <tr class=\"modulo_list_claro\">"; 
        $html .= "    <td align=\"center\">".$hab[$i]['tarifario_id']."</td>";      
        $html .= "    <td align=\"center\">".$hab[$i]['cargo']."</td>";           
        $html .= "    <td align=\"left\">".$hab[$i]['descripcion']."</td>";
        $html .= "    <td align=\"center\">$&nbsp;&nbsp;<input type=\"text\" class=\"input-text\" name=\"precio_plan[$i]\" size=\"10\" value=\"".$hab[$i]['precio_plan']."\" align=\"right\"></td>";          
        $html .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"dias[$i]\" size=\"3\" value=\"".$hab[$i]['cantidad']."\" align=\"center\"></td>";                     
        $html .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"excedente[$i]\" size=\"10\" value=\"".$hab[$i]['excedente']."\" align=\"right\"></td>";
        $html .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"cub[$i]\" readonly size=\"10\" value=\"".$hab[$i]['valor_cubierto']."\" align=\"right\"></td>";            
        $html .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"noCub[$i]\" readonly size=\"10\" value=\"".$hab[$i]['valor_no_cubierto']."\" align=\"right\"></td>";                      
        $accionE=$accionEliminar.UrlRequest(array('posicion'=>$i,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,
        'PlanId'=>$PlanId,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));            
        $html .= "    <td align=\"center\"><a href=\"$accionE\"><img src=\"".GetThemePath()."/images/elimina.png\" border='0' title=\"Eliminar Cargo\"></a></td>";  
        $html .= "   </tr>";                    
      }   
      $html .= "   <tr class=\"modulo_list_claro\">";       
      $html .= "     <td colspan=\"9\" align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Adicionar\" value=\"MODIFICAR CARGOS\"></td>";                
      $html .= "   </tr>";                                
      $html .= "  </table>";
      $html .= " </form>";                         
      
      $html .= "<form name=\"forma\" action=\"$accionInsertar\" method=\"post\" onsubmit=\"return ValidaCamposObligatorios(this);\">";
      $html .= "<br><br><table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\"  class=\"modulo_table_list_title\">";
      $html .= "   <tr>";
      $html .= "   <td colspan=\"7\" class=\"modulo_table_title\">ADICIONAR HABITACION</td>";       
      $html .= "   </tr>";        
      $html .= "   <tr>";       
      $html .= "    <td>Tipo Cama</td>";            
      $html .= "    <td width=\"10%\">Precio Uni.</td>";                          
      $html .= "    <td width=\"6%\">Días</td>";          
      $html .= "    <td width=\"12%\">Val. No Cub. Uni. (Excedente)</td>";        
      $html .= "    <td width=\"2%\">Copago</td>";                                          
      $html .= "   </tr>";                  
      $html .= "   <tr class=\"modulo_list_claro\">";
      $html .= "     <td align=\"center\"><select name=\"tipocama\" class=\"select\" onChange=\"PasarValor(document.forma)\">"; 
      $cons = $funcionesHab->ObtenerTiposCamasPlan($PlanId);
      $html .=" <option value=\"-1\">---Seleccione---</option>";
      for($i=0; $i<sizeof($cons); $i++){
        if($_REQUEST['tipocama']==$cons[$i][tipo_cama_id]){
          $html .=" <option value=\"".$cons[$i][valor_excedente]."||".$cons[$i][valor_lista]."||".$cons[$i][precio]."||".$cons[$i][porcentaje]."||".$cons[$i][tipo_cama_id]."||".$cons[$i][tarifario_id]."||".$cons[$i][cargo]."||".$cons[$i][cargo_cups]."||".$cons[$i][descar]."\" selected>".$cons[$i][descripcion]."</option>"; 
        }else{
          $html .=" <option value=\"".$cons[$i][valor_excedente]."||".$cons[$i][valor_lista]."||".$cons[$i][precio]."||".$cons[$i][porcentaje]."||".$cons[$i][tipo_cama_id]."||".$cons[$i][tarifario_id]."||".$cons[$i][cargo]."||".$cons[$i][cargo_cups]."||".$cons[$i][descar]."\">".$cons[$i][descripcion]."</option>";  
        }
      }
      $html .= "   </select></td>"; 
      $html .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"precioN\" size=\"10\" value=\"".$_REQUEST['precioN']."\" align=\"center\"></td>";     
      $html .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"diasN\" size=\"3\" value=\"".$_REQUEST['diasN']."\" align=\"center\"></td>";            
      $html .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"noCubN\" size=\"10\" value=\"".$_REQUEST['noCubN']."\" align=\"right\"></td>";            
      $html .= "    <td><input type=\"checkbox\" name=\"copago\" value=\"1\"></td>";
      $html .= "   </tr>";
      $html .= "   <tr class=\"modulo_list_claro\">";       
      $html .= "     <td align=\"left\">Departamento: &nbsp;&nbsp;<select name=\"dpto\" class=\"select\">"; 
      $cons = $funcionesHab->ObtenerDepartamentosHabitaciones($EmpresaId);
      $html .=" <option value=\"-1\">---Seleccione---</option>";
      for($i=0; $i<sizeof($cons); $i++){
        if($_REQUEST['dpto']==$cons[$i][departamento]){
          $html .=" <option value=\"".$cons[$i][departamento]."||".$cons[$i][servicio]."\" selected>".$cons[$i][descripcion]."</option>"; 
        }else{
          $html .=" <option value=\"".$cons[$i][departamento]."||".$cons[$i][servicio]."\">".$cons[$i][descripcion]."</option>";  
        }
      }
      $html .= "   </select></td>";                    
      $html .= "     <td colspan=\"6\" align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Adicionar\" value=\"ADICIONAR CARGO\"></td>";               
      $html .= "   </tr>";          
      $html .= "  </table>";
      $html .= " </form>";        
      $html .= "<br><br><table border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"50%\" align=\"center\"  class=\"normal_10\">";
      $html .= "    <tr align=\"center\">";
      $camasMov=RetornarWinOpenDetalleCamas($Ingreso,'DETALLE DE MOVIMIENTOS','label');
      $html .= "    <td colspan=\"7\" align=\"center\" class=\"label\">$camasMov</td>";      
      $html .= "            <form name=\"forma1\" action=\"$accionCargarCuenta\" method=\"post\">";
      $html .= "    <td colspan=\"2\" align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CARGAR A LA CUENTA\"></td>";
      $html .= "</form>";       
      $html .= "            <form name=\"forma2\" action=\"$accionCancelar\" method=\"post\">";
      $html .= "    <td colspan=\"2\" align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
      $html .= "</form>";
      $html .= "    </tr>";
      $html .= "  </table>";        
      $html .= ThemeCerrarTabla();      
			return $html;		
		}
    
    
    
    
	}
?>