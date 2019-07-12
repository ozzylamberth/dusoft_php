<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : userpermisos_puntos_triage
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class userpermisos_puntos_triage extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function userpermisos_puntos_triage()
    { 
      $this->primarykey = array("punto_triage_id","usuario_id");
	
$this->foreignkey = array("puntos_triage"=>
				array("punto_triage_id" => "punto_triage_id"),
					"system_usuarios"=>
				array("usuario_id" => "usuario_id"));

		
	
	
        }
  }
?>