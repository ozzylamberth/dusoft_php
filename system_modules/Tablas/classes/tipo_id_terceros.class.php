<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : terceros_sgsss
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class tipo_id_terceros extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function tipo_id_terceros()
    {
      $this->primarykey = array("tipo_id_tercero");
    }
  }
?>
