<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_auditores_administrativos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : cxp_auditores_administrativos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class cxp_auditores_administrativos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function cxp_auditores_administrativos()
    {
      $this->primarykey = array("usuario_id");
      $this->foreignkey = array("system_usuarios"=>
                              array("usuario_id" => "usuario_id"),
                          );
    }
  }
?>