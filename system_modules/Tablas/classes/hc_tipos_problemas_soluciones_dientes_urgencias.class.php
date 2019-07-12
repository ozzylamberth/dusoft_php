<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : hc_tipos_problemas_soluciones_dientes_urgencias
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class hc_tipos_problemas_soluciones_dientes_urgencias extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function hc_tipos_problemas_soluciones_dientes_urgencias()
    {
      $this->primarykey = array("hc_tipo_problema_diente_id","hc_tipo_producto_diente_id");
		$this->foreignkey = array("hc_tipos_problemas_dientes"=>
				array("hc_tipo_problema_diente_id" => "hc_tipo_problema_diente_id"),
				"hc_tipos_productos_dientes"=>
                              array("hc_tipo_producto_diente_id" => "hc_tipo_producto_diente_id")
				);
	
        }
  }
?>