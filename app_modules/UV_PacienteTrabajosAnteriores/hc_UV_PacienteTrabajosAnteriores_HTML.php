<?php
  /**
  * Submodulo de Revisi� por Sistemas.
  *
  * Submodulo la estipular el ciclo vital y familiar de un paciente.
  * @author Jaime Andres Gomez
  * @version 1.0
  * @package SIIS
  * $Id: hc_ExamenFisico_HTML.php,v 1.13 2007/02/01 21:35:00 tizziano Exp $
  */
  class UV_PacienteTrabajosAnteriores_HTML extends UV_PacienteTrabajosAnteriores
  {
    /**
    * Esta función retorna los datos de concernientes a la version 
    * del submodulo
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
    /**
    *
    */
    function UV_PacienteTrabajosAnteriores_HTML()
    {
      return true;
    }
  }
?>