<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: eps_afiliados_periodos_atencion.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : eps_afiliados_periodos_atencion
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class eps_afiliados_periodos_atencion extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function eps_afiliados_periodos_atencion()
    {
      $this->primarykey = array("eps_afiliados_periodo_atencion_id");
    }
  }
?>