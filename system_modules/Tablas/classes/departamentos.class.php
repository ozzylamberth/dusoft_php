<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : departamentos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class departamentos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function departamentos()
    {
      $this->primarykey = array("departamento");
	  $this->foreignkey = array("unidades_funcionales"=>
                              array("empresa_id" => "empresa_id", "centro_utilidad" => "centro_utilidad", "unidad_funcional" => "unidad_funcional"),
                              "servicios"=>
                              array("servicio"=>"servicio")
                          );
    }
  }
?>