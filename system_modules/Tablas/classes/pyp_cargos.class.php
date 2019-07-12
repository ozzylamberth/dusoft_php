<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: pyp_cargos.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : pyp_cargos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class pyp_cargos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function pyp_cargos()
    {
      $this->primarykey = array("programa_id","cargo_cups");
       $this->foreignkey = array("cups"=>
                              array("cargo" => "cargo_cups"),
					"pyp_programas"=>
                              array("programa_id" => "programa_id")
                          );

    }
  }
?>