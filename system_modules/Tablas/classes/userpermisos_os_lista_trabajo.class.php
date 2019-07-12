<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : userpermisos_os_lista_trabajo
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class userpermisos_os_lista_trabajo extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function userpermisos_os_lista_trabajo()
    {
      $this->primarykey = array("usuario_id","departamento");
       $this->foreignkey = array("departamentos"=>
                              array("departamento" => "departamento"),
					"system_usuarios"=>
                              array("usuario_id" => "usuario_id"),
                          );
    }
  }
?>