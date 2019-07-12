<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : tipos_consultas_cargos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class tipos_consultas_cargos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function tipos_consultas_cargos()
    {
      $this->primarykey = array("tipo_consulta_id","cargo_cita");
       $this->foreignkey = array("tipos_consulta"=>
                              array("tipo_consulta_id" => "tipo_consulta_id"),
					"cargos_citas"=>
                              array("cargo_cita" => "cargo_cita"),
					"system_hc_modulos"=>
                              array("hc_modulo" => "hc_modulo"),
                          );
    }
  }
?>