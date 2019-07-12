<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : userpermisos_creacion_agenda
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class userpermisos_creacion_agenda extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function userpermisos_creacion_agenda()
    {
      $this->primarykey = array("usuario_id","tipo_consulta_id");
       $this->foreignkey = array("tipos_consulta"=>
                              array("tipo_consulta_id" => "tipo_consulta_id"),
					"system_usuarios"=>
                              array("usuario_id" => "usuario_id"),
                          );
    }
  }
?>