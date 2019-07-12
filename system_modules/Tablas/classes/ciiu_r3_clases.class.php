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
  class ciiu_r3_clases extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function ciiu_r3_clases()
    {
      $this->primarykey = array("ciiu_r3_division", "ciiu_r3_grupo", "ciiu_r3_clase");
    }
  }
?>