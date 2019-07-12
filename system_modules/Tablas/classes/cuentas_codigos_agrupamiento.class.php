<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : pacientes
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class cuentas_codigos_agrupamiento extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function cuentas_codigos_agrupamiento()
    {
      $this->primarykey = array("codigo_agrupamiento_id");
/*	$this->foreignkey = array("cuentas_liquidaciones_qx"=>
                              array("cuenta_liquidacion_qx_id" => "cuenta_liquidacion_qx_id"),
                              "bodegas_documentos"=>
                              array("bodegas_doc_id"=>"bodegas_doc_id","numeracion"=>"numeracion"),
						);*/
        }
  }
?>
