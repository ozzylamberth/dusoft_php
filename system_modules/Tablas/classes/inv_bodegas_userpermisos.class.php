<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: inv_bodegas_userpermisos.class.php,v 1.1 2009/10/26 13:35:32 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : eps_tipos_conceptos
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class inv_bodegas_userpermisos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function inv_bodegas_userpermisos()
    {
      $this->primarykey = array("documento_id", "empresa_id", "centro_utilidad", "bodega", "usuario_id");
      $this->foreignkey = array("system_usuarios"=>
                              array("usuario_id" => "usuario_id"),
                              "empresas"=>
                              array("empresa_id"=>"empresa_id"),                              
                              "centros_utilidad"=>
                              array("empresa_id"=>"empresa_id","centro_utilidad"=>"centro_utilidad"),
                          );
    }
  }
?>