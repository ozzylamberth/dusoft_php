<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: userpermisos_eps_solicitudes.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : userpermisos_eps_solicitudes
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class interface_datalab_tipos_id extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function interface_datalab_tipos_id()
    {
      $this->primarykey = array("indice_automatico");
      $this->foreignkey = array("tipos_id_pacientes"=>
                              array("tipo_id_paciente" => "tipo_id_paciente")
                          );
    }
  }
?>