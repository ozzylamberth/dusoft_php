<?php

/**
 * $Id: ReporteCitasTratamientoOdontologico.report.php,v 1.1.1.1 2009/09/11 20:36:56 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteCitasTratamientoOdontologico_report
{
	function ReporteCitasTratamientoOdontologico_report($datos=array())
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
          $TI = $this->datos[TI];
          $TT = $this->datos[TT];
          $_REQUEST['centroutilidad'] = $this->datos[variables][centroutilidad];
          $_REQUEST['unidadfunc'] = $this->datos[variables][unidadfunc];
          $_REQUEST['departamento'] = $this->datos[variables][departamento];
          $_REQUEST['profesional_escojer'] = $this->datos[variables][profesional_escojer];
          $_REQUEST['tipocita'] = $this->datos[variables][tipocita];
          $_REQUEST['feinictra'] = $this->datos[variables][feinictra];
          $_REQUEST['fefinctra'] = $this->datos[variables][fefinctra];
          

		$HTML_WEB_PAGE ="<HTML><BODY>";
          
          $HTML_WEB_PAGE .= "<br><br><center>";
		$HTML_WEB_PAGE .= "<label><font size='6' face='arial'>REPORTE ESTADISTICO CITAS DE TRATAMIENTO ODONTOLOGICO</font></label>";
		$HTML_WEB_PAGE .= "</center><br><br>";
		
          if(!empty($_REQUEST['centroutilidad']) OR !empty($_REQUEST['unidadfunc']) OR !empty($_REQUEST['departamento']) OR ($_REQUEST['profesional_escojer']!='-1') OR !empty($_REQUEST['feinictra']) OR !empty($_REQUEST['fefinctra']))
          {
               $HTML_WEB_PAGE .= "<table border=\"0\" width=\"80%\" align=\"center\">";
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td align=\"center\" colspan=\"2\"><FONT SIZE='1'><b>DATOS DE LA BUSQUEDA</b></FONT></td>";
               $HTML_WEB_PAGE .= "</tr>";
               
               if(!empty($_REQUEST['centroutilidad']))
               {
	               $HTML_WEB_PAGE .= "<tr>";
               	$HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>CENTRO DE UTILIDAD</FONT></td>";
                    $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['centroutilidad']."</FONT></td>";
	               $HTML_WEB_PAGE .= "</tr>";
               }
               
               if(!empty($_REQUEST['unidadfunc']))
               {
	               $HTML_WEB_PAGE .= "<tr>";
	               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>UNIDAD FUNCIONAL</FONT></td>";
 				$HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['unidadfunc']."</FONT></td>";
               	$HTML_WEB_PAGE .= "</tr>";
               }
               
               if(!empty($_REQUEST['departamento']))
               { 
                    $HTML_WEB_PAGE .= "<tr>";
                    $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>DEPARTAMENTO</FONT></td>";
                    $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['departamento']."</FONT></td>";
	               $HTML_WEB_PAGE .= "</tr>";
               }
                    
               $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
     
               if(!empty($usuario_id[1]))
               { 
                    $HTML_WEB_PAGE .= "<tr>";
                    $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>PROFESIONAL</FONT></td>";
			     $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$usuario_id[1]."</FONT></td>";
	               $HTML_WEB_PAGE .= "</tr>";
               }
                    
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>FECHA INICIAL</FONT></td>";
               if(!empty($_REQUEST['feinictra']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['feinictra']."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>SIN DATOS</FONT></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
     
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>FECHA FINAL</FONT></td>";
               if(!empty($_REQUEST['fefinctra']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['fefinctra']."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>SIN DATOS</FONT></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
     
               $HTML_WEB_PAGE .= "</table><br>";
          }
          
          $HTML_WEB_PAGE .= "      <br><table border=\"0\" width=\"70%\" align=\"center\">";
          $HTML_WEB_PAGE .= "      <tr>";
          $HTML_WEB_PAGE .= "      <td width=\"70%\" align=\"center\" colspan=\"2\"><FONT SIZE='1'><b>TRATAMIENTOS ODONTOLOGICOS</b></FONT></td>";
          $HTML_WEB_PAGE .= "      </tr>";
          
          $HTML_WEB_PAGE .= "      <tr>";
          $HTML_WEB_PAGE .= "      <td width=\"35%\" align=\"center\"><FONT SIZE='1'>TRATAMIENTOS ODONTOLOGICOS INICIADOS</FONT></td>";
          $HTML_WEB_PAGE .= "      <td width=\"35%\" align=\"center\"><FONT SIZE='1'>TRATAMIENTOS ODONTOLOGICOS TERMINADOS</FONT></td>";
          $HTML_WEB_PAGE .= "      </tr>";

          $HTML_WEB_PAGE .= "      <tr>";
          $HTML_WEB_PAGE .= "      <td align=\"center\" width=\"35%\"><FONT SIZE='1'><b>".$TI."</b></FONT></td>";
          $HTML_WEB_PAGE .= "      <td align=\"center\" width=\"35%\"><FONT SIZE='1'><b>".$TT."</b></FONT></td>";
          $HTML_WEB_PAGE .= "      </tr>";
       
		$HTML_WEB_PAGE .= "</table>";
		return $HTML_WEB_PAGE;
	}

}

?>
