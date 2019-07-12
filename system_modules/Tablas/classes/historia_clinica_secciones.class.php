<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_estados.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : eps_novedades
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class historia_clinica_secciones extends Modelo
  {
   /**
        * Constructor de la clase
        */
    function historia_clinica_secciones()
    {
      $this->primarykey = array("hc_seccion_id");
    }
  }
?>