<?php
  /******************************************************************************
  * $Id: ListadoPacientesconSalidaHTML.class.php,v 1.4 2006/12/20 21:45:17 carlos Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.4 $ 
	* 
	* @autor Lorena Aragon Galindo
  ********************************************************************************/
  IncludeClass('ListadoPacientesconSalida','','app','Facturacion');
  
	class ListadoPacientesconSalidaHTML
	{
		function ListadoPacientesconSalidaHTML(){}
		/**********************************************************************************
		* Funcion donde se buscan las Notas De Ajuste temporales 
		* 
		* @return array 
		***********************************************************************************/
		function CrearFrmListaPacientesConSalida($EmpresaId,$accionSalir){	
    		
      $VISTA='HTML';
			$funcionesList = new ListadoPacientesconSalida;  
      $datosH = $funcionesList->ObtenerPacientesconSalidaHopitalizacion();            
      
      $html .= ThemeAbrirTabla('LISTADO DE PACIENTES CON SALIDA');          
      $html .= " <form name=\"formainicial\" action=\"$accionModificar\" method=\"post\">";      
      foreach($datosH as $dpto => $vector){  
        $html .= "  <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\">";
        foreach($vector as $nombre_dpto => $vector1){  
          $html .= "   <tr class=\"modulo_table_title\"><td colspan=\"7\"align=\"left\">DEPARTAMENTO:&nbsp;&nbsp;&nbsp;$nombre_dpto</td></tr>";
          foreach($vector1 as $estacion_id => $vector2){
            foreach($vector2 as $nom_estacion => $vector3){
              $html .= "   <tr class=\"modulo_table_list_title\"><td colspan=\"7\" align=\"left\">ESTACION:&nbsp;&nbsp;&nbsp;$nom_estacion</td></tr>";
              $html .= "   <tr class=\"modulo_table_list_title\">";              
              $html .= "    <td width=\"10%\">No. CUENTA</td>";               
              $html .= "    <td width=\"15%\">IDENTIFICACION</td>";               
              $html .= "    <td>PACIENTE</td>";               
              $html .= "    <td width=\"10%\">PIEZA</td>";               
              $html .= "    <td width=\"10%\">CAMA</td>";               
              $html .= "    <td width=\"25%\">PLAN</td>"; 
              $html .= "    <td width=\"5%\">&nbsp;</td>";                  
              $html .= "   </tr>";
              foreach($vector3 as $ingreso => $dat){
                $html .= "   <tr class=\"modulo_list_claro\">";         
                $html .= "    <td>".$dat[numerodecuenta]."</td>";                    
                $html .= "    <td>".$dat[tipo_id_paciente]." ".$dat[paciente_id]."</td>";               
                $html .= "    <td>".$dat[nombre]."</td>";               
                $html .= "    <td>".$dat[pieza]."</td>";               
                $html .= "    <td>".$dat[cama]."</td>";               
                $html .= "    <td>".$dat[plan_descripcion]."</td>";
                $accionHRef=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$dat[numerodecuenta],'TipoId'=>$dat[tipo_id_paciente],'PacienteId'=>$dat[paciente_id],'Nivel'=>$dat[rango],'PlanId'=>$dat[plan_id],'Pieza'=>$dat[pieza],'Cama'=>$dat[cama],'Fecha'=>$dat[fecha],'Ingreso'=>$dat[ingreso],'Estado'=>$dat[estado],'listado'=>1));    
                $html .= "    <td align=\"center\"><a href=\"$accionHRef\">VER</a></td>";             
                $html .= "   </tr>";
              }
            }
          }  
        }
        $html .= "</table>";
        $html .= "<BR>";  
      }
      
      $datosU = $funcionesList->ObtenerPacientesconSalidaUrgencias();         
      foreach($datosU as $dpto => $vector){  
        $html .= "  <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\">";
        foreach($vector as $nombre_dpto => $vector1){  
          $html .= "   <tr class=\"modulo_table_title\"><td colspan=\"7\"align=\"left\">DEPARTAMENTO:&nbsp;&nbsp;&nbsp;$nombre_dpto</td></tr>";
          foreach($vector1 as $estacion_id => $vector2){
            foreach($vector2 as $nom_estacion => $vector3){
              $html .= "   <tr class=\"modulo_table_list_title\"><td colspan=\"7\" align=\"left\">ESTACION:&nbsp;&nbsp;&nbsp;$nom_estacion</td></tr>";
              $html .= "   <tr class=\"modulo_table_list_title\">";              
              $html .= "    <td width=\"10%\">No. CUENTA</td>";               
              $html .= "    <td width=\"15%\">IDENTIFICACION</td>";               
              $html .= "    <td>PACIENTE</td>";               
              $html .= "    <td width=\"10%\">PIEZA</td>";               
              $html .= "    <td width=\"10%\">CAMA</td>";               
              $html .= "    <td width=\"25%\">PLAN</td>";  
              $html .= "    <td width=\"5%\">&nbsp;</td>";                               
              $html .= "   </tr>";
              foreach($vector3 as $ingreso => $dat){
                $html .= "   <tr class=\"modulo_list_claro\">";         
                $html .= "    <td>".$dat[numerodecuenta]."</td>";                    
                $html .= "    <td>".$dat[tipo_id_paciente]." ".$dat[paciente_id]."</td>";               
                $html .= "    <td>".$dat[nombre]."</td>";               
                $html .= "    <td>".$dat[pieza]."</td>";               
                $html .= "    <td>".$dat[cama]."</td>";               
                $html .= "    <td>".$dat[plan_descripcion]."</td>";    
                $accionHRef=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$dat[numerodecuenta],'TipoId'=>$dat[tipo_id_paciente],'PacienteId'=>$dat[paciente_id],'Nivel'=>$dat[rango],'PlanId'=>$dat[plan_id],'Pieza'=>$dat[pieza],'Cama'=>$dat[cama],'Fecha'=>$dat[fecha],'Ingreso'=>$dat[ingreso],'Estado'=>$dat[estado],'listado'=>1));    
                $html .= "    <td align=\"center\"><a href=\"$accionHRef\">VER</a></td>";            
                $html .= "   </tr>";
              }
            }
          }  
        }
      }
        
			$datosQX = $funcionesList->ObtenerPacientesconSalidaCirugia();         
			foreach($datosQX as $dpto => $vector){  
				$html .= "  <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\">";
				foreach($vector as $nombre_dpto => $vector1){  
					$html .= "   <tr class=\"modulo_table_title\"><td colspan=\"7\"align=\"left\">DEPARTAMENTO:&nbsp;&nbsp;&nbsp;$nombre_dpto</td></tr>";
					foreach($vector1 as $estacion_id => $vector2){
						foreach($vector2 as $nom_estacion => $vector3){
							$html .= "   <tr class=\"modulo_table_list_title\"><td colspan=\"7\" align=\"left\">ESTACION:&nbsp;&nbsp;&nbsp;$nom_estacion</td></tr>";
							$html .= "   <tr class=\"modulo_table_list_title\">";              
							$html .= "    <td width=\"10%\">No. CUENTA</td>";               
							$html .= "    <td width=\"15%\">IDENTIFICACION</td>";               
							$html .= "    <td>PACIENTE</td>";               
							$html .= "    <td width=\"10%\">PIEZA</td>";               
							$html .= "    <td width=\"10%\">CAMA</td>";               
							$html .= "    <td width=\"25%\">PLAN</td>";  
							$html .= "    <td width=\"5%\">&nbsp;</td>";                               
							$html .= "   </tr>";
							foreach($vector3 as $ingreso => $dat){
								$html .= "   <tr class=\"modulo_list_claro\">";         
								$html .= "    <td>".$dat[numerodecuenta]."</td>";                    
								$html .= "    <td>".$dat[tipo_id_paciente]." ".$dat[paciente_id]."</td>";               
								$html .= "    <td>".$dat[nombre]."</td>";               
								$html .= "    <td>".$dat[pieza]."</td>";               
								$html .= "    <td>".$dat[cama]."</td>";               
								$html .= "    <td>".$dat[plan_descripcion]."</td>";    
								$accionHRef=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$dat[numerodecuenta],'TipoId'=>$dat[tipo_id_paciente],'PacienteId'=>$dat[paciente_id],'Nivel'=>$dat[rango],'PlanId'=>$dat[plan_id],'Pieza'=>$dat[pieza],'Cama'=>$dat[cama],'Fecha'=>$dat[fecha],'Ingreso'=>$dat[ingreso],'Estado'=>$dat[estado],'listado'=>1));    
								$html .= "    <td align=\"center\"><a href=\"$accionHRef\">VER</a></td>";            
								$html .= "   </tr>";
							}
						}
					}  
				}
			}
        
			$html .= "</table>";
			$html .= "<BR>";  
         
           
            
      
      
      $html .= "</form>";             
      $html .= "<br><br><table border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"50%\" align=\"center\"  class=\"normal_10\">";
      $html .= "<tr align=\"center\">";      
      $html .= "<form name=\"forma\" action=\"$accionSalir\" method=\"post\">";
      $html .= "    <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td>";
      $html .= "</form>";
      $html .= "</tr>";
      $html .= "</table>";        
      $html .= ThemeCerrarTabla();      
			return $html;		
		}
    
    
    
    
	}
?>