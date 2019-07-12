<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: centros_utilidad.class.php,v 1.1 2009/10/26 13:35:32 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : ciiu_r3_divisiones
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class userpermisos_verificacionproductosdevueltos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function userpermisos_verificacionproductosdevueltos()
    {
      $this->primarykey = array("usuario_id");
      $this->foreignkey = array("empresas"=>
                              array("empresa_id"=>"empresa_id"),
						  "system_usuarios"=>array("usuario_id"=>"usuario_id")
                          );
    }
  }
?>