<?php
	/**************************************************************************************
	* $Id: RemoteX.php,v 1.1 2009/09/02 13:08:12 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Luis Alejandro Vargas	
	**************************************************************************************/
	function ListaProgramasPYP($cargo)
	{
		$salida="";
ob_start();
		$objResponse=new xajaxResponse();	
		$objClass=new app_AgendaMedica_user();
		$tipoconsulta=$objClass->TipoConsulta1($cargo);
		
		if($tipoconsulta[2][0])
		{
			$objResponse->call("Iniciar");
			$objResponse->assign("titulo","innerHTML","PROGRAMAS PYP");
			$objResponse->assign("d2Container","style.display","block");
		}
		else
		{
			$objResponse->assign("prog_pyp","style.display","none");
			SessionDelVar("Programa_id");
		}
		
		return $objResponse;
	}
	
	function Seleccion($programa,$descripcion)
	{
		$objResponse=new xajaxResponse();
ob_end_clean();
		SessionSetVar("Programa_id",$programa);
		$objResponse->assign("prog_pyp","innerHTML","PROGRAMA PYP : ".$descripcion);
		$objResponse->assign("prog_pyp","style.display","block");
		$objResponse->assign("d2Container","style.display","none");
		
		return $objResponse;
	}
?>