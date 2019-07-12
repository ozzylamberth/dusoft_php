<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : eps_planes_parametros
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class eps_planes_parametros extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function eps_planes_parametros()
    {
      $this->primarykey = array("plan_id");
	$this->foreignkey = array("planes"=>
                              array("plan_id" => "plan_id"));
	
	
        }
  }
?>