<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : pyp_cpn_grupos_antecedentes
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class pyp_cpn_grupos_antecedentes extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function pyp_cpn_grupos_antecedentes()
    {
      $this->primarykey = array("pyp_cpn_grupo_antecedente_id","pyp_cpn_antecedente_id","hc_tipo_antecedente_gineco_id");
      $this->foreignkey = array("pyp_cpn_antecedentes"=>
                              array("pyp_cpn_antecedente_id" => "pyp_cpn_antecedente_id"),
                          );
    }
  }
?>