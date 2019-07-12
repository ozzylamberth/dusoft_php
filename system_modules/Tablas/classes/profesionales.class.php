<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : profesionales
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class profesionales extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function profesionales()
    {
      $this->primarykey = array("tipo_id_tercero","tercero_id");
       $this->foreignkey = array("system_usuarios"=>
                              array("usuario_id" => "usuario_id"),
					"tipo_sexo"=>
                              array("sexo_id" => "sexo_id"),
					"tipos_profesionales"=>
                              array("tipo_profesional" => "tipo_profesional"),
					"terceros"=>
                              array("tercero_id" => "tercero_id"),
                          );
    }
  }
?>