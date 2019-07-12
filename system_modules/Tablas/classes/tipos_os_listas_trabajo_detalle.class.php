<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : tipos_os_listas_trabajo_detalle
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class tipos_os_listas_trabajo_detalle extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function tipos_os_listas_trabajo_detalle()
    {
      $this->primarykey = array("tipo_os_lista_id","tipo_cargo","grupo_tipo_cargo");
       $this->foreignkey = array("tipos_os_listas_trabajo"=>
                              array("tipo_os_lista_id" => "tipo_os_lista_id"),
					"tipos_cargos"=>
                              array("tipo_cargo" => "tipo_cargo"),
					"grupos_tipos_cargo"=>
                              array("grupo_tipo_cargo" => "grupo_tipo_cargo"),
                          );
    }
  }
?>