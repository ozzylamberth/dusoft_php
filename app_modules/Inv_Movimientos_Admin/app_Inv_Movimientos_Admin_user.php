<?php
	/**
	* $Id: app_Inv_Movimientos_Admin_user.php,v 1.2 2011/05/19 22:19:10 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.2 $
	*
	* @autor Jaime Gomez
  
	**/
	IncludeClass('MovBodegasAdminSQL','','app','Inv_Movimientos_Admin');
	class app_Inv_Movimientos_Admin_user extends classModulo
	{
       
        /**
        * @var $action Variable donde se guardan los action de las formsa
        **/
        var $action = array();
        /**   
        */
        
        /**
        *funcion constructora
        * @return $salida.
        **/
        function app_Inv_Movimientos_Admin_user(){}
    
        /**
        *funcion q crea la varible de sesion que lleva el id de la empresa
        * @return true
        **/
        function CrearElementos()
        {
            			
			if($_REQUEST["Empresas"])
            {
                SessionSetVar("EMPRESAS",$_REQUEST["Empresas"]);
                $this->Enterprice = SessionGetVar("EMPRESAS");
                SessionSetVar("EMPRESA",$this->Enterprice['empresa_id']);
            }
            SessionSetVar("rutaImagenes",GetThemePath());
        }
        
    /**
    * Funcion q crea un vector con las empresas a las que tiene permiso el usuario
    *
    * @return boolean
    */
    function MostrarEmpresas()
    {
      $consulta=new MovBodegasAdminSQL();
      $this->TodasEmpresas=$consulta->ListarEmpresas(UserGetUID());
    }
    /**
    *funcion q crea un vector  con todos los centro de costo de la empresa
    * @return true
    **/
    function MostrarCentros()
    {
      $empresa = SessionGetVar("EMPRESA");
      $usuario = UserGetUID();
      $mvs = new MovBodegasAdminSQL();
      $this->TodosCentros = $mvs->GetCentros_de_Utility1($empresa,$usuario);
    }
        
        /**
        *funcion q crea un vector  con todas las bodegas de la empresa
        * @return true
        **/
        function MostrarBodeguitas($centro)
        {
            $consulta=new MovBodegasAdminSQL();
            SessionSetVar("CENTROXITO",$centro);
            $this->TodasBodegas=$consulta->GetBodegas1(SessionGetVar("EMPRESA"),$centro['centro_utilidad']);
        }
         
        /**
        *funcion retorna los datose del paciente
        * @return true
        **/
        function MostrarDatosPaciente($numerodecuenta)
        {
            $consulta=new MovBodegasAdminSQL();
            return $consulta->GetDatosPaciente($numerodecuenta);
        }
   }
?>