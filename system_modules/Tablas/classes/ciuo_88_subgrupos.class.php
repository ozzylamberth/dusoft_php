<?php
  /*
  *
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS-FI
  *
  * $Revision: 1.1 $
  *
  * @autor Hugo F  Manrique
  */
  class ciuo_88_subgrupos extends Modelo
  {
    /**
    * Constructor de la clase
    */
    function ciuo_88_subgrupos()
    {
      	$this->primarykey = array("ciuo_88_gran_grupo", "ciuo_88_subgrupo_principal", "ciuo_88_subgrupo");
	$this->foreignkey = array("ciuo_88_subgrupos_principales"=>array("ciuo_88_gran_grupo"=>"ciuo_88_gran_grupo","ciuo_88_subgrupo_principal"=>"ciuo_88_subgrupo_principal"));
    }
  }
?>
