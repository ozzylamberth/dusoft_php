<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : hc_evoluciones_submodulos_paso
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class hc_evoluciones_submodulos_paso extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function hc_evoluciones_submodulos_paso()
    {
      $this->primarykey = array("orden_id");
       $this->foreignkey = array("system_hc_submodulos"=>
                              array("submodulo" => "submodulo"),
                          );
    }
  }
?>