<?php
	
/**
* Submodulo de Revisi� por Sistemas.
*
* Submodulo para manejar el examen por sistemas que debe realizarse a un paciente en una evoluci�.
* @author Jaime Andres Gomez
* @version 1.0
* @package SIIS
* $Id: hc_InformacionInicialPaciente_HTML.php,v 1.1 2008/09/03 13:41:32 hugo Exp $
*/



class InformacionInicialPaciente_HTML extends InformacionInicialPaciente
{

/**
* Esta funci� retorna los datos de concernientes a la version del submodulo
* @access private
*/

     function GetVersion()
     {
          $informacion=array(
          'version'=>'1',
          'subversion'=>'0',
          'revision'=>'0',
          'fecha'=>'02/05/2008',
          'autor'=>'JAIME ANDRES GOMEZ',
          'descripcion_cambio' => '',
          'requiere_sql' => false,
          'requerimientos_adicionales' => '',
          'version_kernel' => '1.0'
          );
          return $informacion;
     }

	function InformacionInicialPaciente_HTML()
	{
		return true;
	}
// fin de la clase
}
?>