<?php
	
/**
* Submodulo de Revisi� por Sistemas.
*
* Submodulo la estipular el ciclo vital y familiar de un paciente.
* @author Jaime Andres Gomez
* @version 1.0
* @package SIIS
* $Id: hc_UV_CicloFamiliar_HTML.php,v 1.1 2008/09/03 18:50:27 hugo Exp $
*/



class UV_CicloFamiliar_HTML extends UV_CicloFamiliar
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
          'fecha'=>'03/02/2008',
          'autor'=>'JAIME ANDRES GOMEZ',
          'descripcion_cambio' => '',
          'requiere_sql' => false,
          'requerimientos_adicionales' => '',
          'version_kernel' => '1.0'
          );
          return $informacion;
     }

	function UV_CicloFamiliar_HTML()
	{
		return true;
	}
// fin de la clase
}
?>