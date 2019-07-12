<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : pyp_riesgos_biopsicosocial
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class pyp_riesgos_biopsicosocial extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function pyp_riesgos_biopsicosocial()
    {
      $this->primarykey = array("riesgo_id");
	$this->foreignkey = array("pyp_programas"=>
                              array("programa_id" => "programa_id"),
					"pyp_riesgos_grupos"=>
                              array("grupo_id" => "grupo_id"),
                          );

    }
  }
?>