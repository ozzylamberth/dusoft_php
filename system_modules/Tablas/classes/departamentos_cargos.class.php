<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: documentos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : departamentos_cargos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class departamentos_cargos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function departamentos_cargos()
    {
      $this->primarykey = array("departamento","cargo");
      $this->foreignkey = array("departamentos"=>
                              array("departamento" => "departamento"),
                              "cups"=>
                              array("cargo"=>"cargo")
                          );
    }
  }
?>