<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : diagnosticos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class diagnosticos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function diagnosticos()
    {
      $this->primarykey = array("area_evaluada_iddiagnostico_id");
	$this->foreignkey = array("tipo_sexo"=>
				array("sexo_id" => "sexo_id"));
		
	
        }
  }
?>