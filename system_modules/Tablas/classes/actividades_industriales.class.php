<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : actividades_industriales
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class actividades_industriales extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function actividades_industriales()
    {
      $this->primarykey = array("actividad_id","grupo_id");
       $this->foreignkey = array("actividades_industriales_grupos"=>
                              array("grupo_id" => "grupo_id"),
                          );
    }
  }
?>