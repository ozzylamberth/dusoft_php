<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : hc_sub_diagnosticos_odontologicos_areas
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class hc_sub_diagnosticos_odontologicos_areas extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function hc_sub_diagnosticos_odontologicos_areas()
    {
      $this->primarykey = array("area_evaluada_id");
		
	
        }
  }
?>