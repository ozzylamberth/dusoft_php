<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : niveles_triages
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class niveles_triages extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function niveles_triages()
    { 
      $this->primarykey = array("nivel_triage_id");
/*	
$this->foreignkey = array("puntos_triage"=>
				array("punto_triage_id" => "punto_triage_id"),
					"puntos_admisiones"=>
				array("punto_admision_id" => "punto_admision_id"));
*/
		
	
	
        }
  }
?>