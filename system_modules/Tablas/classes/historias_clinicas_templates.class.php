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
  class historias_clinicas_templates extends Modelo
  {
   /**
        * Constructor de la clase
        */
    function historias_clinicas_templates()
    {
      $this->primarykey = array("hc_modulo","submodulo");
      $this->foreignkey = array("system_hc_modulos"=>array("hc_modulo"=>"hc_modulo"),
                            "system_hc_submodulos"=>array("submodulo"=>"submodulo"),
                            "historia_clinica_secciones"=>array("hc_seccion_id"=>"hc_seccion_id"),
                            "tipo_sexo"=>array("sexo_id"=>"sexo_id"));
    }
  }
?>