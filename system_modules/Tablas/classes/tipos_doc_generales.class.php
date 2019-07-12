<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: tipos_doc_generales.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : tipos_doc_generales
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class tipos_doc_generales extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function tipos_doc_generales()
    {
      $this->primarykey = array("tipo_doc_general_id");
    }
  }
?>