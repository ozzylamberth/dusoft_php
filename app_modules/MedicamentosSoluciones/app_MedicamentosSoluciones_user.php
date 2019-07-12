<?php
	/**************************************************************************************
	* $Id: app_MedicamentosSoluciones_user.php,v 1.2 2006/08/18 21:42:31 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.2 $
	*
	* @autor Hugo F  Manrique
	***************************************************************************************/
	IncludeClass('Soluciones','','app','MedicamentosSoluciones');
	class app_MedicamentosSoluciones_user extends classModulo
	{
		/**
		* @var $action Variable donde se guardan los action de las formsa
		**/
		var $action = array();
		/**
		* @var $Opcion Variable donde se guardan la opcion que viene por request del menu principal
		**/
		var $Opcion = array();
		/**
		* @var $GSoluciones Variable donde se guardan los valores de los grupos de las soluciones
		**/
		var $GSoluciones = array();
		/**
		*  @var $Plantillas Variable para las plantillas de la historia clinica
		**/
		var $Plantillas = array();
		/**
		*  @var $GMedicamentos Variable para los grupos de medicamentos
		**/
		var $GMedicamentos = array();
		/**
		* @var $GMSolucion Variable para los medicamentos de un grupo de soluciones 
		**/
		var $GMSolucion = array();

		function app_MedicamentosSoluciones_user(){}
		/**********************************************************************************
		* Funcion donde se crean las variables usadas en la funcion FormaMostrarDocumentos,
		* se averiguan los tipos de documentos
		***********************************************************************************/
		function CrearElementos()
		{
			SessionDelVar("Opcion");

			if(!SessionIsSetVar("Opcion"))
				SessionSetVar("Opcion",$_REQUEST['datos']['opcion']);

			SessionDelVar("CodigosAdd");
			SessionDelVar("PlantillaAdd");
			SessionDelVar("PlantillaAddM");
			SessionDelVar("MedicamentosSel");
			SessionDelVar("MedicamentosClasificar");
			SessionDelVar("MedicamentosAnteriores");
			SessionSetVar("rutaImagenes",GetThemePath());

			$slc = new Soluciones();
			$this->Plantillas = $slc->ObtenerPlantillas();
			$this->Opcion = SessionGetVar("Opcion");
			$this->action[0] = ModuloGetURL('app','MedicamentosSoluciones','user','FormaMenuPrincipal');
		}
		/**********************************************************************************
		* Funcion donde se crean las variables usadas en FormaCrearSoluciones
		***********************************************************************************/
		function CrearSoluciones()
		{
			$slc = new Soluciones();
			$this->GSoluciones = $slc->GruposSoluciones();
			$this->GMSolucion = $slc->GruposMedicamentosSoluciones();
		}
		/**********************************************************************************
		* Funcion donde se crean las variables usadas en FormaModificarGrupos
		***********************************************************************************/
		function ModificarGrupos()
		{
			$slc = new Soluciones();
			$this->GMedicamentos = $slc->ObtenerGruposMedicamentos();
			$this->GSoluciones = $slc->ObtenerGruposSoluciones();
			$this->GMSolucion = $slc->GruposMedicamentosSoluciones();
		}
	}
?>