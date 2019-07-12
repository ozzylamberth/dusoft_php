<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : cajas_rapidas
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class cajas_rapidas extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function cajas_rapidas()
    {
      $this->primarykey = array("caja_id");
       $this->foreignkey = array("departamentos"=>
                              array("departamento" => "departamento"),
					"documentos"=>
                              array("documento_id" => "prefijo_fac_credito"),
					"documentos"=>
                              array("documento_id" => "prefijo_fac_contado"),
					"servicios"=>
                              array("servicio" => "servicio"),
                          );

    }
  }
?>