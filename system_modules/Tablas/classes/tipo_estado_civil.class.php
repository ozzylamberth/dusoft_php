<?php
  /**
  * $Id: ciiu_r3_divisiones.class.php,v 1.1 2007/10/31 15:03:06 hugo Exp $
  *
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS-FI
  *
  * $Revision: 1.1 $
  *
  * @autor Hugo F  Manrique
  */
  IncludeClass("Modelo", "", "system","Tablas");
  class tipo_estado_civil extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function tipo_estado_civil()
    {
      $this->primarykey = array("tipo_estado_civil_id");
    }
  }
?>