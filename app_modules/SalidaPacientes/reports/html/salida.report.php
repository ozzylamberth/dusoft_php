<?php

/**
 * $Id: salida.report.php,v 1.2 2005/06/03 19:32:12 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class salida_report
{ 
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function salida_report($datos=array())
	{
			$this->datos=$datos;
			return true;
	}

	
	function GetMembrete()
	{
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
												'subtitulo'=>'',
												'logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
	}			
    //FUNCION CrearReporte()
	//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
	function CrearReporte()
	{
			$Salida .= "       <table border=\"0\" width=\"100%\" align=\"left\">";
			$Salida .= "          <tr align=\"center\"><td colspan=\"4\" class=\"titulo2\">SALIDA DE PACIENTE</td></tr>";
			$Salida .= "          <tr align=\"left\"><td colspan=\"4\">&nbsp;<br></td></tr>";
			$Salida .= "          <tr align=\"left\">";
			$Salida .= "          	<td class=\"normal_10\" width=\"4%\">IDENTIFICACION: </td><td class=\"normal_10\" width=\"10%\" align=\"left\">".$this->datos['tipo_id_paciente']." ".$this->datos['paciente_id']."</td>";
			$Salida .= "          	<td class=\"normal_10\" width=\"4%\">PACIENTE: </td><td class=\"normal_10\" width=\"10%\" align=\"left\">".$this->datos['nombre']."</td>";
			$Salida .= "           </tr>";
			$Salida.="<TR><TD colspan=\"4\">&nbsp;</TD></TR>";
			$Salida .= "          <tr align=\"justify\">";
			$Salida .= "          	<td colspan=\"4\" class=\"normal_10\">El paciente se encuentra a Paz y Salvo, se Autoriza la Salida al Paciente de la Institución.</td>";
			$Salida .= "           </tr>";
			$Salida.="<TR><TD colspan=\"4\">&nbsp;</TD></TR>";
			$Salida.="<TR><TD colspan=\"4\">&nbsp;</TD></TR>";
			$Salida .= "          <tr align=\"left\"><td colspan=\"4\" class=\"normal_10\">______________________</td></tr>";
			$Salida .= "          <tr align=\"left\"><td colspan=\"4\" class=\"normal_10\">   FIRMA AUTORIZADA</td></tr>";
			$Salida .= "		   	 </table>";
			return $Salida;
	}



    //AQUI TODOS LOS METODOS QUE USTED QUIERA
    //---------------------------------------
}

?>
