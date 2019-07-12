<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : terceros_clientes
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class terceros_clientes extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function terceros_clientes()
    {
	$this->primarykey = array("empresa_id","tipo_id_tercero","tercero_id");
      $this->foreignkey = array("terceros"=>
                              array("tercero_id" => "tercero_id"),
					"empresas"=>
                              array("empresa_id" => "empresa_id"),
					 );
    }
  }
?>