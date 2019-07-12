<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : userpermisos_cuentas
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class userpermisos_cuentas extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function userpermisos_cuentas()
    {
      $this->primarykey = array("departamento","usuario_id");
       $this->foreignkey = array("documentos"=>
                              array("documento_id" => "documento_id"),
					"system_usuarios"=>
                              array("usuario_id" => "usuario_id"),
					"departamentos"=>
                              array("departamento" => "departamento"),
					"puntos_facturacion"=>
                              array("punto_facturacion_id" => "punto_facturacion_id"),
                          );
    }
  }
?>