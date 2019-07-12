<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : historias_clinicas_tipos_cierres
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class historias_clinicas_tipos_cierres extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function historias_clinicas_tipos_cierres()
    {
      $this->primarykey = array("historia_clinica_tipo_cierre_id");
	$this->foreignkey = array("hc_tipos_ordenes_medicas"=>
                              array("hc_tipo_orden_medica_id" => "hc_tipo_orden_medica_id"));
	
	
	
        }
  }
?>