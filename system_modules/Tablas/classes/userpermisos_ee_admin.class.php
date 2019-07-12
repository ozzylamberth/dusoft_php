<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : userpermisos_ee_admin
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class userpermisos_ee_admin extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function userpermisos_ee_admin()
    {
      $this->primarykey = array("usuario_id","estacion_id");
	$this->foreignkey = array("estaciones_enfermeria"=>
                              array("estacion_id" => "estacion_id"),
					"system_usuarios"=>
                              array("usuario_id" => "usuario_id"));
	
	
	
        }
  }
?>