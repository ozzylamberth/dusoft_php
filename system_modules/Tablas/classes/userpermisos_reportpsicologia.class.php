<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : userpermisos_reportpsicologia
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class userpermisos_reportpsicologia extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function userpermisos_reportpsicologia()
    {
      $this->primarykey = array("empresa_id","centro_utilidad","unidad_funcional","departamento","usuario_id");
       $this->foreignkey = array("departamentos"=>
                              array("departamento" => "departamento"),
					"unidades_funcionales"=>
                              array("empresa_id" => "empresa_id","centro_utilidad" => "centro_utilidad","unidad_funcional" => "unidad_funcional"),
					"system_usuarios"=>
                              array("usuario_id" => "usuario_id")
					
                          );
    }
  }
?>