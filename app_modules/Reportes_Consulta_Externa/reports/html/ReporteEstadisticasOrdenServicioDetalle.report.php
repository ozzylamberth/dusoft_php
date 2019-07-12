<?php

/**
 * $Id: ReporteEstadisticasOrdenServicioDetalle.report.php,v 1.1.1.1 2009/09/11 20:36:56 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteEstadisticasOrdenServicioDetalle_report
{
	function ReporteEstadisticasOrdenServicioDetalle_report($datos=array())
	{
		$this->datos=$datos;
		return true;
	}

	var $datos;
	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	function CrearReporte()
	{
          
          $total_apoyos = $this->datos[total_apoyos];          
          $total_Int = $this->datos[total_Int];
          $total_Inca = $this->datos[total_Inca];
          $_REQUEST['centroutilidad'] = $this->datos[variables][centroutilidad];
          $_REQUEST['unidadfunc'] = $this->datos[variables][unidadfunc];
          $_REQUEST['departamento'] = $this->datos[variables][departamento];
          $_REQUEST['profesional_escojer'] = $this->datos[variables][profesional_escojer];
          $_REQUEST['tipocita'] = $this->datos[variables][tipocita];
          $_REQUEST['feinictra'] = $this->datos[variables][feinictra];
          $_REQUEST['fefinctra'] = $this->datos[variables][fefinctra];
          

		$HTML_WEB_PAGE ="<HTML><BODY>";
          
          $HTML_WEB_PAGE .= "<br><br><center>";
		$HTML_WEB_PAGE .= "<label><FONT SIZE='1'>REPORTE ESTADISTICO DE ORDENES DE SERVICIO</FONT></label>";
		$HTML_WEB_PAGE .= "</center><br><br>";
		
          if(!empty($_REQUEST['centroutilidad']) OR !empty($_REQUEST['unidadfunc']) OR !empty($_REQUEST['departamento']) OR ($_REQUEST['profesional_escojer']!='-1') OR !empty($_REQUEST['feinictra']) OR !empty($_REQUEST['fefinctra']))
          {
               $HTML_WEB_PAGE .= "<table border=\"0\" width=\"80%\" align=\"center\">";
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td align=\"center\" colspan=\"2\"><FONT SIZE='1'><b>DATOS DE LA BUSQUEDA</b></FONT></td>";
               $HTML_WEB_PAGE .= "</tr>";
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>CENTRO DE UTILIDAD</FONT></td>";
               if(!empty($_REQUEST['centroutilidad']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['centroutilidad']."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><label class=\"label_mark\"><FONT SIZE='1'>SIN DATOS</FONT></label></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
               
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>UNIDAD FUNCIONAL</FONT></td>";
               if(!empty($_REQUEST['unidadfunc']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['unidadfunc']."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><label class=\"label_mark\"><FONT SIZE='1'>SIN DATOS</FONT></label></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
               
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>DEPARTAMENTO</FONT></td>";
               if(!empty($_REQUEST['departamento']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['departamento']."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><label class=\"label_mark\"><FONT SIZE='1'>SIN DATOS</FONT></label></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
                    
               $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
     
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>PROFESIONAL</FONT></td>";
               if(!empty($usuario_id[1]))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$usuario_id[1]."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><label class=\"label_mark\"><FONT SIZE='1'>SIN DATOS</FONT></label></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
                    
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>FECHA INICIAL</FONT></td>";
               if(!empty($_REQUEST['feinictra']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['feinictra']."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><label class=\"label_mark\"><FONT SIZE='1'>SIN DATOS</FONT></label></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
     
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>FECHA FINAL</FONT></td>";
               if(!empty($_REQUEST['fefinctra']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['fefinctra']."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><label class=\"label_mark\"><FONT SIZE='1'>SIN DATOS</FONT></label></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
     
               $HTML_WEB_PAGE .= "</table><br>";
          }
          
					$HTML_WEB_PAGE .= "<table border=\"1\" width=\"80%\" align=\"center\">";
					$HTML_WEB_PAGE .= "<tr>";
					$HTML_WEB_PAGE .= "<td align=\"center\" colspan=\"2\"><FONT SIZE='1'><b>ESTADISTICAS</b></FONT></td>";
          $HTML_WEB_PAGE .= "</tr>"; 	
          if($total_apoyos){         		
						$HTML_WEB_PAGE .= "<tr>";
						$HTML_WEB_PAGE .= "<td colspan=\"2\" class=\"modulo_table_title\" width=\"50%\" align=\"center\"><FONT SIZE='1'>APOYOS DIAGNOSTICOS</FONT></td>";			
						$HTML_WEB_PAGE .= "</tr>";
						$suma=0;
							foreach($total_apoyos as $id=>$datos){
								$HTML_WEB_PAGE .= "<tr>";
								$HTML_WEB_PAGE .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\"><FONT SIZE='1'>".$datos['descripcion']."</FONT></td>";			
								$HTML_WEB_PAGE .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\"><FONT SIZE='1'>".$datos['total_tipo']."</FONT></td>";			
								$HTML_WEB_PAGE .= "</tr>";	
								$suma+=$datos['total_tipo'];
							}
							$HTML_WEB_PAGE .= "<tr class=\"modulo_list_claro\" >";
							$HTML_WEB_PAGE .= "<td width=\"50%\" align=\"left\" class=\"label\"><FONT SIZE='1'>TOTAL</FONT></td>";			
							$HTML_WEB_PAGE .= "<td width=\"50%\" align=\"left\" class=\"label\"><FONT SIZE='1'>".$suma."</FONT></td>";			
							$HTML_WEB_PAGE .= "</tr>";	
						}
					$HTML_WEB_PAGE .= "</table><BR><BR>";
					
					$HTML_WEB_PAGE .= "<table border=\"1\" width=\"80%\" align=\"center\">";						
					if($total_Int){
					$HTML_WEB_PAGE .= "<tr>";
					$HTML_WEB_PAGE .= "<td colspan=\"2\" class=\"modulo_table_title\" width=\"50%\" align=\"center\"><FONT SIZE='1'>INTERCONSULTAS</FONT></td>";			
					$HTML_WEB_PAGE .= "</tr>";
					$suma=0;
						foreach($total_Int as $id=>$datos){
							$HTML_WEB_PAGE .= "<tr>";
							$HTML_WEB_PAGE .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\"><FONT SIZE='1'>".$datos['descripcion']."</FONT></td>";			
							$HTML_WEB_PAGE .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\"><FONT SIZE='1'>".$datos['total_tipo']."</FONT></td>";			
							$HTML_WEB_PAGE .= "</tr>";	
							$suma+=$datos['total_tipo'];
						}
						$HTML_WEB_PAGE .= "<tr class=\"modulo_list_claro\" >";
						$HTML_WEB_PAGE .= "<td width=\"50%\" align=\"left\" class=\"label\"><FONT SIZE='1'>TOTAL</FONT></td>";			
						$HTML_WEB_PAGE .= "<td width=\"50%\" align=\"left\" class=\"label\"><FONT SIZE='1'>".$suma."</FONT></td>";			
						$HTML_WEB_PAGE .= "</tr>";	
					}
					$HTML_WEB_PAGE .= "</table><BR><BR>";
					
					$HTML_WEB_PAGE .= "<table border=\"1\" width=\"80%\" align=\"center\">";						
					if($total_Inca){
					$HTML_WEB_PAGE .= "<tr>";
					$HTML_WEB_PAGE .= "<td colspan=\"2\" class=\"modulo_table_title\" width=\"50%\" align=\"center\"><FONT SIZE='1'>INCAPACIDADES</FONT></td>";			
					$HTML_WEB_PAGE .= "</tr>";
					$suma=0;
						foreach($total_Inca as $id=>$datos){
							$HTML_WEB_PAGE .= "<tr>";
							$HTML_WEB_PAGE .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\"><FONT SIZE='1'>".$datos['descripcion']."</FONT></td>";			
							$HTML_WEB_PAGE .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\"><FONT SIZE='1'>".$datos['total_tipo']."</FONT></td>";			
							$HTML_WEB_PAGE .= "</tr>";	
							$suma+=$datos['total_tipo'];
						}
						$HTML_WEB_PAGE .= "<tr class=\"modulo_list_claro\" >";
						$HTML_WEB_PAGE .= "<td width=\"50%\" align=\"left\" class=\"label\"><FONT SIZE='1'>TOTAL</FONT></td>";			
						$HTML_WEB_PAGE .= "<td width=\"50%\" align=\"left\" class=\"label\"><FONT SIZE='1'>".$suma."</FONT></td>";			
						$HTML_WEB_PAGE .= "</tr>";	
					}       
					$HTML_WEB_PAGE .= "</table>";
		return $HTML_WEB_PAGE;
	}

}

?>
