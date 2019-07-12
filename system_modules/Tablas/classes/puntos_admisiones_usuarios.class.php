<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : puntos_admisiones_usuarios
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class puntos_admisiones_usuarios extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function puntos_admisiones_usuarios()
    {
      $this->primarykey = array("punto_admision_id","usuario_id");
	$this->foreignkey = array("puntos_admisiones"=>
                              array("punto_admision_id" => "punto_admision_id"),
					"system_usuarios"=>
                              array("usuario_id" => "usuario_id"));
    }
  }
?>