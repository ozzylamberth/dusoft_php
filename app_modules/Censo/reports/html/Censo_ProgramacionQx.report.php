<?php
/**
 * $Id: Censo_ProgramacionQx.report.php,v 1.3 2007/07/06 16:36:28 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS-CENSO
 *
 * Listado de programacion cirugia
 */

include_once "./classes/modules/classmodules.class.php";
include_once "./classes/modules/classmodulo.class.php";
include_once "./app_modules/Censo/app_Censo_user.php";
include_once "./app_modules/Censo/userclasses/app_Censo_userclasses_HTML.php";
/**
 * Clase para generar el listado programacion de cirugia
 *
 * @author    Ehudes García <efgarcia@ipsoft-sa.com>
 * @version   $Revision: 1.3 $
 * @package   IPSOFT-SIIS-CENSO
 */
class Censo_ProgramacionQx_report extends app_Censo_userclasses_HTML
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
	function Censo_ProgramacionQx_report($datos=array())
	{
		$this->app_Censo_userclasses_HTML();//Constructor del padre
          $this->datos = $datos;
		return true;
	}//

	/**
	 * Membrete D
	 */
	function GetMembrete()
	{
		$Membrete = array('file'=>false,
						'datos_membrete'=>array('titulo'=>$this->razon_social,
						'subtitulo'=>'CENSO - LISTADO DE CIRUGIA <br><br>'.strtoupper(FormatoFecha(1)).' , '.date("g:i A").'',
						'logo'=>'logocliente.png',
						'align'=>'left'));
		return $Membrete;
	}//Fin GetMembrete

	/**
	 * CrearReporte es una funcion implementada
	 */
	function CrearReporte()
	{
		$this->salida .= "<table width=\"100%\"><tr align=\"center\"><td>LISTADO PROGRAMACION CIRUGÍA</td></tr></table>";
		$this->salida .= "<br>";
		$this->FrmListaProgramacionQx($this->datos['fecha']);
		$this->salida .= "<script language=\"javascript\">\n";
		$this->salida .= "	window.print();\n";
		$this->salida .= "</script>\n";
		return $this->salida;
	}//Fin CrearReporte
}//Fin clase
?>

