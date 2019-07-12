<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: hc_Dietas.php,v 1.1 2009/02/02 16:32:31 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : Dietas
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class Dietas extends hc_classModules
  {
    /**
    * Constructor de la clase
    */
		function Dietas()
		{
			$this->frmError = array();
			$this->error='';
			return true;
		}
    /**
    * Esta función retorna los datos de concernientes a la version del submodulo
    * @access private
    */
    function GetVersion()
    {
      $datos = array (
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'01/27/2005',
        'autor'=>'Hugo F. Manrique',
        'descripcion_cambio' => '',
        'requiere_sql' => false,
        'requerimientos_adicionales' => '',
        'version_kernel' => '1.0'
      );
      return $datos;
    }
  }
?>