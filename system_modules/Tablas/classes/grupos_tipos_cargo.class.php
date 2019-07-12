<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : grupos_tipos_cargo
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class grupos_tipos_cargo extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function grupos_tipos_cargo()
    {
      $this->primarykey = array("grupo_tipo_cargo");
       $this->foreignkey = array("cuentas_codigos_agrupamiento"=>
                              array("codigo_agrupamiento_id" => "codigo_agrupamiento_id"),
                          );
    }
  }
?>