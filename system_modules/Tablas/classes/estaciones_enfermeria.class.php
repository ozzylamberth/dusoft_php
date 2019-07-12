<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : estaciones_enfermeria
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class estaciones_enfermeria extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function estaciones_enfermeria()
    {
      $this->primarykey = array("estacion_id");
	$this->foreignkey = array("departamentos"=>
                              array("departamento" => "departamento"),
					"system_hc_modulos"=>
                              array("hc_modulo" => "hc_modulo_medico"),
					"system_hc_modulos"=>
                              array("hc_modulo" => "hc_modulo_enfermera"),
					"estaciones_enfermeria_tipos_atencion"=>
                              array("tipo_atencion_estacion_id" => "tipo_atencion_estacion_id"));
    }
  }
?>