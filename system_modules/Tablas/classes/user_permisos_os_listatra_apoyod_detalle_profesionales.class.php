<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: cxp_tipos.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : user_permisos_os_listatra_apoyod_detalle_profesionales
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class user_permisos_os_listatra_apoyod_detalle_profesionales extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function user_permisos_os_listatra_apoyod_detalle_profesionales()
    {
      $this->primarykey = array("usuario_id","tipo_id_tercero","tercero_id","departamento","tipo_os_lista_id");
       $this->foreignkey = array("departamentos"=>
                              array("departamento" => "departamento"),
					"profesionales"=>
                              array("tercero_id" => "tercero_id"),
					"tipos_os_listas_trabajo"=>
                              array("tipo_os_lista_id" => "tipo_os_lista_id"),
                          );
    }
  }
?>