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
  class administradoras_de_fondos_de_pensiones extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function administradoras_de_fondos_de_pensiones()
    {
      $this->primarykey = array("codigo_afp");
    }
  }
?>