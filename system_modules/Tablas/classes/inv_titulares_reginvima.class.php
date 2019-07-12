<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: centros_utilidad.class.php,v 1.1 2009/10/26 13:35:32 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : ciiu_r3_divisiones
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class inv_titulares_reginvima extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function inv_titulares_reginvima()
    {
      $this->primarykey = array("titular_reginvima_id");
      $this->foreignkey = array("tipo_pais"=>
                              array("tipo_pais_id"=>"tipo_pais_id")
                          );
    }
  }
?>