<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : estaciones_enfermeria_tipos_atencion
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class estaciones_enfermeria_tipos_atencion extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function estaciones_enfermeria_tipos_atencion()
    {
      $this->primarykey = array("tipo_atencion_estacion_id");
	
    }
  }
?>