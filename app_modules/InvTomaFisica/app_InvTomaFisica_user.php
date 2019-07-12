<?php
	/**************************************************************************************
	* $Id: app_InvTomaFisica_user.php,v 1.2 2010/02/01 21:15:58 johanna Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.2 $
	*
	* @autor Jaime Gomez
  
	***************************************************************************************/
	IncludeClass('TomaFisicaSQL','','app','InvTomaFisica');
	class app_InvTomaFisica_user extends classModulo
	{
       
    /**
    * @var $action Variable donde se guardan los action de las formsa
    **/
    var $action = array();
    /**   
    */
    
    
  	function app_InvTomaFisica_user(){}
/**********************************************************************************
    * Funcion donde se crean las variables usadas en la funcion FormaMostrarDocumentos,
    * se averiguan los tipos de documentos
    ***********************************************************************/
    
    function CrearElementos()
    {  if($_REQUEST["Empresas"])
       {
          SessionSetVar("EMPRESAS",$_REQUEST["Empresas"]);
          
          $this->Enterprice = SessionGetVar("EMPRESAS");
          SessionSetVar("EMPRESA",$this->Enterprice['empresa_id']);
          
       }   
       SessionSetVar("rutaImagenes",GetThemePath());
       SessionDelVar("Creardoc");
       $VECTOR[-1]=1;
       SessionSetVar("Verctorcillo",$VECTOR);
    } 

     function MostrarEmpresas()
     {
       $consulta=new TomaFisicaSQL();
       $this->TodasEmpresas=$consulta->ListarEmpresas();
     }
     function SubMenu()
     { if(!SessionIsSetVar("Creardoc"))
         SessionSetVar("Creardoc",$_REQUEST["Docus"]);
         
         
     }

  
  function imagenesubi()
  {
   $request = $_REQUEST;
   
   $contratoca=$request['scontrato'];
    $tipood=$request['tipoid'];
    $Noid=$request['noid'];
    $empresa=$request['empresa'];

    // archivo temporal (ruta y nombre).
      $tmp_name = $_FILES["archivo"]["tmp_name"];
      
      // Obtener del array FILES (superglobal) los datos del binario .. nombre, tabamo y tipo.
       $numero = rand(0,1000);
     
      $type = $_FILES["archivo"]["type"];
      $size = $_FILES["archivo"]["size"];
      $nombre = basename($_FILES["archivo"]["name"]);
   
      $dataimg = explode(".",$nombre);
  
      $renombrada = $contratoca.$Noid.$numero.".".$dataimg[1];
      $fp = fopen($tmp_name, "rb");
      $buffer = fread($fp, filesize($tmp_name));
      fclose($fp);
      $buffer=addslashes($buffer);//pg_escape_bytea($buffer);
      $path_upload = 'cartas/';
      
      
      if (is_uploaded_file($_FILES['archivo']['tmp_name']))
        {
        
       ///  $nombre_archivo = $_FILES ['archivo'] [ 'name' ];
          $renombrada = $contratoca.$Noid.$numero.".".$dataimg[1];
          $ruta_archivo =  $_SERVER['DOCUMENT_ROOT']."/SIIS/app_modules/InvTomaFisica/cartas".$envio.'/'.$renombrada;
         
          $this->nombre_archivos[$envio][$key] = $renombrada;
         
          move_uploaded_file ( $_FILES['archivo']['tmp_name'], $ruta_archivo );
         
          $lines = file($ruta_archivo);
             
      } else {
 
           }
/*
    $mifcontra = AutoCarga::factory("ContratacionProductosSQL", "classes", "app", "ContratacionProductos");  
   $dat=$mifcontra->Insertar($renombrada, $size, $type, $buffer,$contratoca,$tipood,$Noid,$empresa); 

   
   if(!$dat)
          {
          $msg1 = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>".$ingc->mensajeDeError;
          } 
          else
          {
          $msg1 = "EL INGRESO SE HA REALIZADO SATISFACTORIAMENTE";
          }
        $frmcontra = AutoCarga::factory("ContratacionProductosCartasHTML", "views", "app", "ContratacionProductos");
        $action['volver'] = ModuloGetURL("app", "ContratacionProductos", "controller", "BusquedaProveedor");  
        $this->salida = $frmcontra->FormaMensajeIngresocartas($action, $msg0, $msg1);*/
  
  return true;
}
     
 }
?>