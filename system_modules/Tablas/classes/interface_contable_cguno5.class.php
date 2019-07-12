<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : interface_contable_cguno5
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class interface_contable_cguno5 extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function interface_contable_cguno5()
    {
      $this-> esquema = "cg_conf";
	$this->primarykey = array("documento_id","empresa_id");
      $this->foreignkey = array("documentos"=>
                              array("documento_id" => "documento_id"),
					"empresas"=>
                              array("empresa_id" => "empresa_id"),
					 );
    }
  }
?>