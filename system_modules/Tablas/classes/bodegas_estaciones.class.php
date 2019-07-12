<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : bodegas_estaciones
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class bodegas_estaciones extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function bodegas_estaciones()
    {
      $this->primarykey = array("empresa_id","centro_utilidad","bodega","estacion_id");
	$this->foreignkey = array("bodegas"=>
				array("empresa_id" => "empresa_id","centro_utilidad" => "centro_utilidad","bodega" => "bodega"),
				"estaciones_enfermeria"=>
                              array("estacion_id" => "estacion_id"));
	
	
	
        }
  }
?>