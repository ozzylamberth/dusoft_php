<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : pyp_cpn_seguimiento_consultas
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class pyp_cpn_seguimiento_consultas extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function pyp_cpn_seguimiento_consultas()
    {
      $this->primarykey = array("empresa_id","centro_utilidad","unidad_funcional","departamento","tipo_consulta_id");
       $this->foreignkey = array("departamentos"=>
                              array("empresa_id" => "empresa_id","centro_utilidad" => "centro_utilidad","unidad_funcional" => "unidad_funcional","departamento" => "departamento"),
					"tipos_consulta"=>
                              array("tipo_consulta_id" => "tipo_consulta_id"),
                          );
    }
  }
?>