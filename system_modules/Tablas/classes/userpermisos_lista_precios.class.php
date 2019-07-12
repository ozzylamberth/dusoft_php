<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : userpermisos_lista_precios
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class userpermisos_lista_precios extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function userpermisos_lista_precios()
    {
      $this->primarykey = array("empresa_id","usuario_id");
       $this->foreignkey = array("empresas"=>
                              array("empresa_id" => "empresa_id"),
					"system_usuarios"=>
                              array("usuario_id" => "usuario_id"),
                          );
    }
  }
?>