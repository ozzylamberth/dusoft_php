<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: ciiu_r3_divisiones.class.php,v 1.3 2008/04/07 13:27:47 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : ciiu_r3_divisiones
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ciiu_r3_divisiones extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function ciiu_r3_divisiones()
    {
      $this->primarykey = array("ciiu_r3_division");
      $this->foreignkey = array("system_usuarios"=>
                              array("usuario_id" => "usuario_id"),
                              "empresas"=>
                              array("empresa_id"=>"empresa_id"),
                          );
    }
  }
?>