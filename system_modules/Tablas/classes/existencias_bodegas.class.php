<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : existencias_bodegas
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class existencias_bodegas extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function existencias_bodegas()
    {
      $this->primarykey = array("empresa_id","centro_utilidad","bodega, codigo_producto");
	$this->foreignkey = array("bodegas"=>
                              array("empresa_id" => "empresa_id","centro_utilidad" => "centro_utilidad","bodega" => "bodega"),
				"system_usuarios"=>
                              array("usuario_id" => "usuario_id"));
	
	
	
        }
  }
?>