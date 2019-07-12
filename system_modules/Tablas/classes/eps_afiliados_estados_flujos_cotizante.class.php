<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: empresas.class.php,v 1.1 2008/03/28 16:12:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : eps_afiliados_estados_flujos_cotizante
	* Clase, por medio de la cual se hacen las consultas de la tabla que referencia
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class eps_afiliados_estados_flujos_cotizante extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function eps_afiliados_estados_flujos_cotizante()
    {
      $this->primarykey = array("estado_afiliado_id");
	$this->foreignkey = array("eps_afiliados_estados"=>
                              array("estado_afiliado_id" => "estado_afiliado_id"),
                              "eps_afiliados_subestados"=>
                              array("estado_afiliado_id"=>"estado_afiliado_id_beneficiario","subestado_afiliado_id"=>"subestado_afiliado_id_beneficiario"),
					);
        }
  }
?>