<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : hc_tipos_sistemas_estomatologicos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class hc_tipos_sistemas_estomatologicos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function hc_tipos_sistemas_estomatologicos()
    {
      $this->primarykey = array("tipo_sistema_id");
	$this->foreignkey = array("hc_examen_estomatologico_maestro"=>
                              array("estomatologico_maestro_id" => "estomatologico_maestro_id"),
					);
	
    }
  }
?>
