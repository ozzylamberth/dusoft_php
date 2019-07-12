<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : userpermisos_repconsultaexterna
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class userpermisos_repconsultaexterna extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function userpermisos_repconsultaexterna()
    {
      $this->primarykey = array("empresa_id","centro_utilidad","unidad_funcional","departamento","usuario_id");
       $this->foreignkey = array("departamentos"=>
                              array("departamento" => "departamento"),
					"system_usuarios"=>
                              array("usuario_id" => "usuario_id"),
					"centros_utilidad"=>
                              array("centro_utilidad" => "centro_utilidad"),
					"unidades_funcionales"=>
                              array("unidad_funcional" => "unidad_funcional"),
                          );
    }
  }
?>