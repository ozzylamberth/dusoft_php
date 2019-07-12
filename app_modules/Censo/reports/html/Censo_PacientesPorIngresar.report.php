<?php
/**
 * $Id: Censo_PacientesPorIngresar.report.php,v 1.2 2007/01/30 14:07:07 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS-CENSO
 *
 * Listado de pacientes por ingresar
 */

include_once "./classes/modules/classmodules.class.php";
include_once "./classes/modules/classmodulo.class.php";
include_once "./app_modules/Censo/app_Censo_user.php";
include_once "./app_modules/Censo/userclasses/app_Censo_userclasses_HTML.php";
/**
 * Clase para generar el Listado de pacientes por ingresar
 * esta clase extiende de la clase app_Censo_userclasses_HTML para reutilizar
 * los metodos de esta clase
 *
 * @author    Ehudes García <efgarcia@ipsoft-sa.com>
 * @version   $Revision: 1.2 $
 * @package   IPSOFT-SIIS-CENSO
 */
class Censo_PacientesPorIngresar_report extends app_Censo_userclasses_HTML
{
	//var $datos;
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'letter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	/**
	 * Constructor
	 */
	function Censo_Pacientes_report($datos=array())
	{
		$this->app_Censo_userclasses_HTML();//Constructor del padre
		return true;
	}

	/**
	 * Membrete
	 */
	function GetMembrete()
	{
		$Membrete = array('file'=>false,
							'datos_membrete'=>array('titulo'=>$this->razon_social,
							'subtitulo'=>'CENSO <br><br>'.strtoupper(FormatoFecha(1)).' , '.date("g:i A").'',
							'logo'=>'logocliente.png',
							'align'=>'left'));
		return $Membrete;
	}//Fin GetMembrete

	/**
	 * CrearReporte es una funcion implementada
	 */
	function CrearReporte()
	{
		$this->salida .= "<table width=\"100%\"><tr align=\"center\"><td>LISTADO DE PACIENTES HOSPITALIZADOS</td></tr></table>";
		$this->salida .= "<br>";
		$this->FrmListadoPacientesPorIngresar();
		$this->salida .= "<script language=\"javascript\">\n";
		$this->salida .= "	window.print();\n";
		$this->salida .= "</script>\n";
		return $this->salida;
	}//Fin CrearReporte
}//Fin clase
?>

