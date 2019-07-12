<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : hc_tipos_antecedentes_ginecos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class hc_tipos_antecedentes_ginecos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function hc_tipos_antecedentes_ginecos()
    {
      $this->primarykey = array("hc_tipo_antecedente_gineco_id");
       $this->foreignkey = array("tipo_sexo"=>
                              array("sexo" => "sexo_id"),
                          );
    }
  }
?>