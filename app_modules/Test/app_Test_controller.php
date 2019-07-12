<?php
	
	class app_Test_controller extends classModulo
	{
	/**
		* Constructor de la clase
	*/
	function app_Test_controller()
	{}
	/**
        *  Funcion principal del modulo
        *  @return boolean
    */
		function main()
		{
			$request = $_REQUEST;
			  
      //$contratacion = AutoCarga::factory('AdministracionFarmaciaSQL', '', 'app', 'AdministracionFarmacia');
			
      $form = AutoCarga::factory("HolaHTML", "views", "app", "Test");
      
      
      
      $this->salida .= $form->FormaMenu();
			
			return true;
		}    
	
	} 
?>