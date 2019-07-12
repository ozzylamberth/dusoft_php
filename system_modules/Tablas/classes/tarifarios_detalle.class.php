<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : tarifarios_detalle
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class tarifarios_detalle extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function tarifarios_detalle()
    {
      $this->primarykey = array("tarifario_id","cargo");
      $this->foreignkey = array("niveles_atencion"=>
                              array("nivel" => "nivel"),
					"tarifarios"=>
                              array("tarifario_id" => "tarifario_id"),
					"rips_conceptos"=>
                              array("concepto_rips" => "concepto_rips"),
					"cups_grupos_mapipos"=>
                              array("grupos_mapipos" => "grupos_mapipos"),
					"tipos_unidades_cargos"=>
                              array("tipo_unidad_id" => "tipo_unidad_id"),
					"grupos_tarifarios"=>
                              array("grupo_tarifario_id" => "grupo_tarifario_id"),
					"subgrupos_tarifarios"=>
                              array("subgrupo_tarifario_id" => "subgrupo_tarifario_id"),
					"grupos_tipos_cargo"=>
                              array("grupo_tipo_cargo" => "grupo_tipo_cargo"),
					"tipos_cargos"=>
                              array("tipo_cargo" => "tipo_cargo"),
                          );
    }
  }
?>