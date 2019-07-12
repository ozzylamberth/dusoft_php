<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : terceros_proveedores_servicios_salud
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class terceros_proveedores_servicios_salud extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function terceros_proveedores_servicios_salud()
    {
      $this->primarykey = array("empresa_id","tipo_id_tercero","tercero_id");
       $this->foreignkey = array("empresas"=>
                              array("empresa_id" => "empresa_id"),
					"tipo_id_terceros"=>
                              array("tipo_id_tercero" => "tipo_id_tercero"),
					"terceros"=>
                              array("tercero_id" => "tercero_id"),
                          );
    }
  }
?>