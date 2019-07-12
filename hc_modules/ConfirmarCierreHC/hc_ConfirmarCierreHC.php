<?php

// $Id: hc_ConfirmarCierreHC.php,v 1.2 2005/03/08 23:22:14 tizziano Exp $

class ConfirmarCierreHC extends hc_classModules
{

    function ConfirmarCierreHC()
    {
        return true;
    }
    
    function SubmoduloMensaje()
    {
        return true;
    }    

/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'01/27/2005',
		'autor'=>'ALEXANDER GIRALDO SALAS',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}


	function GetConsulta()
    {
        if($this->frmConsulta()==false)
        {
            return true;
        }
        return $this->salida;
    }


    function GetForma()
    {
        $actionSI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('OBLIGAR_CIERRE_HC'=>'SI'));
        $actionNO=ModuloHCGetURL($this->evolucion,-1,0);
        $this->frmForma($actionSI,$actionNO);
        return $this->salida;
    }

    function GetEstado()
    {
        if(empty($_REQUEST['OBLIGAR_CIERRE_HC']))
        {
            return false;
        }else{
            return true;
        }
    }

}
?>
