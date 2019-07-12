<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : userpermisos_pypadmin
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class userpermisos_pypadmin extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function userpermisos_pypadmin()
    {
      $this->primarykey = array("empresa_id","usuario_id");
	$this->foreignkey = array("empresas"=>
                              array("system_usuarios" => "empresa_id"),
					"estaciones_enfermeria"=>
                              array("usuario_id" => "usuario_id"));
	
    }
  }
?>