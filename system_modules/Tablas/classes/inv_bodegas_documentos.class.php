<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : inv_bodegas_documentos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class inv_bodegas_documentos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function inv_bodegas_documentos()
    {
      $this->primarykey = array("documento_id","empresa_id","centro_utilidad","bodega");
	$this->foreignkey = array("documentos"=>
				array("documento_id" => "documento_id", "empresa_id" => "empresa_id"),
				"bodegas"=>
                              array("empresa_id" => "empresa_id","centro_utilidad" => "centro_utilidad","bodega" => "bodega"));
	
	
	
        }
  }
?>