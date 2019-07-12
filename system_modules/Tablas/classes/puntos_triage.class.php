<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : puntos_triage
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class puntos_triage extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function puntos_triage()
    { 
      $this->primarykey = array("cargO","tecnica_id","lab_examen_id");
	$this->foreignkey = array("departamentos"=>
				array("departamento" => "departamento"));
		
	
	
        }
  }
?>