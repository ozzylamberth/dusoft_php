<?php
	
/**
* Submodulo de Revisiï¿½ por Sistemas.
*
* Submodulo para manejar el examen por sistemas que debe realizarse a un paciente en una evoluciï¿½.
* @author Jaime Andres Gomez
* @version 1.0
* @package SIIS
* $Id: hc_ExamenFisico_HTML.php,v 1.13 2007/02/01 21:35:00 tizziano Exp $
*/



class ExamenFisico_HTML extends ExamenFisico
{

/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

     function GetVersion()
     {
          $informacion=array(
          'version'=>'1',
          'subversion'=>'0',
          'revision'=>'0',
          'fecha'=>'10/25/2006',
          'autor'=>'JAIME ANDRES GOMEZ',
          'descripcion_cambio' => '',
          'requiere_sql' => false,
          'requerimientos_adicionales' => '',
          'version_kernel' => '1.0'
          );
          return $informacion;
     }

	function ExamenFisico_HTML()
	{
		return true;
	}
// fin de la clase
}
?>