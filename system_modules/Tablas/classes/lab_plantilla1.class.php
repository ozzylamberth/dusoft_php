<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : lab_plantilla1
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class lab_plantilla1 extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function lab_plantilla1()
    {
      $this->primarykey = array("cargo","tecnica_id","lab_examen_id","sexo_id","edad_min","edad_max");
	$this->foreignkey = array("tipo_sexo"=>
				array("sexo_id" => "sexo_id"),
				"lab_examenes"=>
				array("cargo" => "cargo", "tecnica_id" => "tecnica_id", "lab_examen_id" => "lab_examen_id"));
		
	
        }
  }
?>