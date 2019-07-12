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
  class ciuo_88_subgrupos_principales extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function ciuo_88_subgrupos_principales()
    {
      $this->primarykey = array("ciuo_88_gran_grupo", "ciuo_88_subgrupo_principal");
    }
  }
?>