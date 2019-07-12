<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : hc_tipos_cuadrantes_dientes_oleary
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class hc_tipos_cuadrantes_dientes_oleary extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function hc_tipos_cuadrantes_dientes_oleary()
    {
      $this->primarykey = array("hc_tipo_cuadrante_diente_oleary_id");
	$this->foreignkey = array("hc_tipos_cuadrantes_dientes"=>
				array("hc_tipo_cuadrante_id" => "hc_tipo_cuadrante_diente_oleary_id"));
	
	
	
        }
  }
?>