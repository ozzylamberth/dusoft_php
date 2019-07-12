<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: documentos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : documentos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class documentos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function documentos()
    {
      $this->primarykey = array("documento_id","empresa_id");
      $this->foreignkey = array("tipos_doc_generales"=>
                              array("tipo_doc_general_id" => "tipo_doc_general_id"),
                              "empresas"=>
                              array("empresa_id"=>"empresa_id"),
                          );
    }
  }
?>