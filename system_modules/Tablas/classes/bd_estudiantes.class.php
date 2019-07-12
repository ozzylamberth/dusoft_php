<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : bd_estudiantes
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class bd_estudiantes extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function bd_estudiantes()
    {
	$this-> esquema = 'interfaz_uv';
      $this->primarykey = array("paciente_id","tipo_id_paciente");
	$this->foreignkey = array("tipos_id_pacientes"=>
                              array("tipo_id_paciente" => "tipo_id_paciente"),
                              "tipo_sexo"=>
                              array("sexo_id"=>"sexo_id"),
				"tipo_estado_civil"=>
                              array("tipo_estado_civil_id"=>"tipo_estado_civil_id"),
				"tipo_mpios"=>
                              array("tipo_pais_id"=>"tipo_pais_id","tipo_dpto_id"=>"tipo_dpto_id","tipo_mpio_id"=>"tipo_mpio_id"),
				"zonas_residencia"=>
                              array("zona_residencia"=>"zona_residencia"),
					);
        }
  }
?>
