<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: centros_utilidad.class.php,v 1.1 2009/10/26 13:35:32 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : ciiu_r3_divisiones
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class tipo_pais extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function tipo_pais()
    {
      $this->primarykey = array("tipo_pais_id");
      
    }
  }
?>