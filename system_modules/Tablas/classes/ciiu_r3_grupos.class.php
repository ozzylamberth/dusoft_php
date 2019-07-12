<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: ciiu_r3_grupos.class.php,v 1.3 2007/11/09 15:53:58 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : ciiu_r3_grupos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ciiu_r3_grupos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function ciiu_r3_grupos()
    {
      $this->primarykey = array("ciiu_r3_division","ciiu_r3_grupo");
      $this->foreignkey = array("ciiu_r3_divisiones"=>
                              array("ciiu_r3_division" => "ciiu_r3_division"),
                          );
    }
  }
?>