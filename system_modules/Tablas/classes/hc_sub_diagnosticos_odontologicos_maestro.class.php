<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : hc_sub_diagnosticos_odontologicos_maestro
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class hc_sub_diagnosticos_odontologicos_maestro extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function hc_sub_diagnosticos_odontologicos_maestro()
    {
      $this->primarykey = array("area_evaluada_id","diagnostico_id");
		$this->foreignkey = array("hc_sub_diagnosticos_odontologicos_areas"=>
				array("area_evaluada_id" => "area_evaluada_id"),
				"diagnosticos"=>
                              array("diagnostico_id" => "diagnostico_id")
				);
	
        }
  }
?>