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
  class tipo_mpios extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function tipo_mpios()
    {
        $this->primarykey = array("tipo_pais_id","tipo_dpto_id","tipo_mpio_id");
	$this->foreignkey = array("tipo_dptos"=> 
			array("tipo_dpto_id"=>"tipo_dpto_id","tipo_pais_id"=>"tipo_pais_id"),
				);
    }
  }
?>
