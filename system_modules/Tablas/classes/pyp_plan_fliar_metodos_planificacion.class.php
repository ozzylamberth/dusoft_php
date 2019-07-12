<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id:  apoyod_cargos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : pyp_plan_fliar_metodos_planificacion
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class pyp_plan_fliar_metodos_planificacion extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function pyp_plan_fliar_metodos_planificacion()
    {
      $this->primarykey = array("metodo_id");
    }
  }
?>