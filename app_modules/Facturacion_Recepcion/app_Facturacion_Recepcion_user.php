<?php
/**
 * $Id: app_Facturacion_Recepcion_user.php,v 1.4 2007/06/26 23:29:14 carlos Exp $
 * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de control de cierres.
 */

/**
* app_Facturacion_Recepcion_user.php
*
*/

IncludeClass('Facturacion_Recepcion','','app','Facturacion_Recepcion');
IncludeClass('Facturacion_RecepcionHTML','','app','Facturacion_Recepcion');
    
class app_Facturacion_Recepcion_user extends classModulo
{
	/**
	* Es el contructor de la clase Facturacion_Recepcion
	* @return boolean
	*/
	var $limit;
	var $conteo;//para saber cuantos registros encontró
	var $uno;

	function app_Facturacion_Recepcion_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}



	/**
	* La funcion main es la principal y donde se llama FormaPrincipal
	* que muestra los diferentes tipos de busqueda de una cuenta para hospitalización.
	* @access public
	* @return boolean
	*/
	function main()
	{
			SessionDelVar("VolverPermisos");
		if(!$this->LlamaBuscarPermisosUser()){
		return false;
	  }
				return true;
  }


	function LlamaBuscarPermisosUser()
	{
			SessionSetVar("VolverPermisos",ModuloGetURL('system','Menu'));
			$recep = new Facturacion_RecepcionHTML();
			$this->salida = $recep->BuscarPermisosUser();
			return true;
  }

  /**
	***
	* @return boolean
	*/


    function SetStyle($campo)//Mensaje de error en caso de no encontrar los datos
    {
        if ($this->frmError[$campo] || $campo=="MensajeError")
        {
            if ($campo=="MensajeError")
            {
                return ("<tr><td class='label_error' colspan='2' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
            }
            else
            {
                return ("label_error");
            }
        }
        return ("label");
    }


	function LlamaMenuRecepcion()
	{
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");
		$this->IncludeJS("CrossBrowser");

		$file = 'app_modules/Facturacion_Recepcion/RemoteXajax/FacturacionRecepcion.php';
		$this->SetXajax(array("AdicionarFacturaRecepcion","ActualizarRecepcionFacturaCredito"),$file);
		if($_REQUEST['control'])
		{
			$_SESSION['FACTURACION_RECEPCION']['EMP']=$_REQUEST['control']['empresa_id'];
			$_SESSION['FACTURACION_RECEPCION']['CENTRO']=$_REQUEST['control']['centro_utilidad'];
			$_SESSION['FACTURACION_RECEPCION']['NOM_CENTRO']=$_REQUEST['control']['cent'];
			$_SESSION['FACTURACION_RECEPCION']['NOM_EMP']=$_REQUEST['control']['emp'];
			//$_SESSION['FACTURACION_RECEPCION']['usuario_id']=$_REQUEST['control']['usuario_id'];
			$_SESSION['FACTURACION_RECEPCION']['fac_grupo_id']=$_REQUEST['control']['fac_grupo_id'];
		}
		$accion = ModuloGetURL('app','Facturacion_Recepcion','user','LlamaBuscarPermisosUser');
		SessionSetVar("VolverMenu",$accion);
		$fact = new Facturacion_RecepcionHTML();
		$this->salida = $fact->Menu($_SESSION['FACTURACION_RECEPCION']['EMP'],$_SESSION['FACTURACION_RECEPCION']['CENTRO'],$_SESSION['FACTURACION_RECEPCION']['NOM_EMP'],$_SESSION['FACTURACION_RECEPCION']['NOM_CENTRO']);
		return $this->salida;
	}

	function LlamaActualizarMovimientoFacturasCredito()
	{

		$fact = new Facturacion_Recepcion();
		$this->salida = $fact->ActualizarMovimientoFacturasCredito($_REQUEST);
		//$fact->ActualizarMovimientoFacturasCredito($_REQUEST);
		return true;
	}
//MenuOs_Atencion
}//fin clase user
?>