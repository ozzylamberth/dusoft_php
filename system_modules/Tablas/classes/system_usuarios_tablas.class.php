<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : system_usuarios_tablas
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class system_usuarios_tablas extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function system_usuarios_tablas()
    {
      $this->primarykey = array("usuario_id","tabla_descripcion");
       $this->foreignkey = array("system_usuarios"=>
                              array("usuario_id" => "usuario_id"),
                          );
    }
  }
?>