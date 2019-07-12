<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: documentos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : system_printers
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class system_printers extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function system_printers()
    {
      $this->primarykey = array("impresora");
    }
  }
?>