<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : camas
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class camas extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function camas()
    {
      $this->primarykey = array("cama");
	$this->foreignkey = array("piezas"=>
                              array("pieza" => "pieza"),
				"tipo_camas_estados"=>
                              array("tipo_cama_estado_id" => "estado"),
				"tipos_camas"=>
                              array("tipo_cama_id" => "tipo_cama_id"),
				"tipos_servicio_camas"=>
                              array("sw_virtual" => "sw_virtual"));
	
    }
  }
?>