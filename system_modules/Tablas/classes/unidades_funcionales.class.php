<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : unidades_funcionales
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class unidades_funcionales extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function unidades_funcionales()
    {
      $this->primarykey = array("unidad_funcional");
       $this->foreignkey = array("centros_utilidad"=>
                              array("empresa_id, centro_utilidad" => "empresa_id, centro_utilidad"),
                          );

    }
  }
?>