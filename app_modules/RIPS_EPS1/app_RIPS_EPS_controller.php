<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: app_RIPS_EPS_controller.php,v 1.4 2009/02/16 21:15:09 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  */
  /**
  * Clase Control: RIPS_EPS
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.4 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Giraldo
  */
  class app_RIPS_EPS_controller extends classModulo
  {
  	/**
  	* Constructor de la clase
  	*/
    function app_RIPS_EPS_controller(){}
    /**
    * Funcion de control main del modulo
    *
    * @return boolean
    */
	  function Main()
    {
  		$request = $_REQUEST;
  		$action['volver'] = ModuloGetURL('system','Menu');		
  		
  		$obj = AutoCarga::factory('RipsEPS','','app','RIPS_EPS');

  		$cantidad = $obj->ValidarPermisos();

  		if($cantidad===false)
  		{
  			$mensaje = "ERROR AL VALIDAR PERMISOS PARA ACCEDER A ESTE MODULO <BR />" . $obj->error . " <BR />" . $obj->mensajeDeError;
  			$html = AutoCarga::factory('MensajesModuloHTML','views','app','RIPS_EPS');
  			$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  			return true;			
  		}
  		elseif($cantidad != 1)
  		{
  			$mensaje = "SU USUARIO NO CUENTA CON PERMISOS PARA ACCEDER A ESTE MODULO";
  			$html = AutoCarga::factory('MensajesModuloHTML','views','app','RIPS_EPS');
  			$this->salida = $html->FormaMensajeModulo($action,$mensaje);
  			return true;	
  		}
  		
  		$request = $_REQUEST;
  		$ltd = AutoCarga::factory('Listados','','app','RIPS_EPS');
  		$mdl = AutoCarga::factory('ListadosHTML','views','app','RIPS_EPS');

  		$buscador = array();
  		$buscador['cxp_estado'] = $request['cxp_estado']; 
  		$buscador['fecha_inicial'] = $request['fecha_inicial']; 
  		$buscador['fecha_final'] = $request['fecha_final'];
  		$buscador['fac_sin_rips'] = $request['fac_sin_rips'];		
  		$buscador['buscar'] = $request['buscar'];

  		$empresa = SessionGetVar("EmpresasCuentas");

  		$msgError = "";
  		$lista = array();
  		$pagina = $conteo = 0;

  		if($request['buscar'])
  		{
  			$lista = $ltd->ObtenerListadoRadicacion($empresa['empresa'],$request);
  			$conteo = $ltd->conteo;
  			$pagina = $ltd->pagina;
  			$msgError = $ltd->ErrMsg();
  		}

  		$tiposdocumentos = $obj->ObtenerEstados_cxp();

  		$action['volver'] = ModuloGetURL('system','Menu');	
  		$action['buscar'] = ModuloGetURL('app','RIPS_EPS','controller','ListarRadicaciones');
  		$action['paginador'] = ModuloGetURL('app','RIPS_EPS','controller','ListarRadicaciones',$buscador);

  		$this->salida .= $mdl->FormaListadoRadicaciones($action,$tiposdocumentos,$buscador,$lista,$pagina,$conteo,$msgError);
  		return true;
    }	
    /**
    * Funcion de control, para el listado de radicaciones
    *
    * @return boolean
    */
	  function ListarRadicaciones()
    {
  		$request = $_REQUEST;
  		$ltd = AutoCarga::factory('Listados','','app','RIPS_EPS');
  		$mdl = AutoCarga::factory('ListadosHTML','views','app','RIPS_EPS');
  		$obj = AutoCarga::factory('RipsEPS','','app','RIPS_EPS');

  		$buscador = array();
  		$buscador['cxp_estado'] = $request['cxp_estado']; 
  		$buscador['fecha_inicial'] = $request['fecha_inicial']; 
  		$buscador['fecha_final'] = $request['fecha_final'];
  		$buscador['fac_sin_rips'] = $request['fac_sin_rips'];
  		$codigo_sgsss = 'RES004';
  		
  		if($request['crear'])
  		{     
  			$action['volver'] = ModuloGetURL('app','RIPS_EPS','controller','ListarRadicaciones');	
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','RIPS_EPS');
  			
        $id = $obj->GenerarEnvio($request);
  			if($id===false)
  			{
  				$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS <BR />" . $obj->error . " <BR />" . $obj->mensajeDeError;
  				$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  				return true;				
  			}
  			
  			$datos = $ltd->ObtenerAC($request);
  			
  			if($datos===false)
  			{
  				$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS <BR />";
          $this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  				return true;				
  			}
  			
  			if(!empty($datos))
  			{
  				if($obj->GenerarArchivoAC($id,$datos,$codigo_sgsss)===false)
  				{
  					$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (AC) <BR />" . $obj->error . " <BR />" . $obj->mensajeDeError;
  					$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  					return true;
  				}			
  			}	

  			$datos = $ltd->ObtenerAP($request);
  			
  			if($datos===false)
  			{
  				$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (AP)<BR />";
  				$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  				return true;				
  			}
  			
  			if(!empty($datos))
  			{
  				if($obj->GenerarArchivoAP($id,$datos,$codigo_sgsss)===false)
  				{
  					$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (AP) <BR />" . $obj->error . " <BR />" . $obj->mensajeDeError;
  					$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  					return true;
  				}			
  			}	

  			$datos = $ltd->ObtenerAU($request);
  			
  			if($datos===false)
  			{
  				$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (AU)<BR />";
  				$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  				return true;				
  			}
  			
  			if(!empty($datos))
  			{
  				if($obj->GenerarArchivoAU($id,$datos,$codigo_sgsss)===false)
  				{
  					$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (AU) <BR />" . $obj->error . " <BR />" . $obj->mensajeDeError;
  					$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  					return true;
  				}			
  			}	

  			$datos = $ltd->ObtenerAH($request);
  			
  			if($datos===false)
  			{
  				$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (AH-1)<BR />";
  				$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  				return true;				
  			}
  			
  			if(!empty($datos))
  			{
  				if($obj->GenerarArchivoAH($id,$datos,$codigo_sgsss)===false)
  				{
  					$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (AH-2) <BR />" . $obj->error . " <BR />" . $obj->mensajeDeError;
  					$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  					return true;
  				}			
  			}

  			$datos = $ltd->ObtenerAN($request);
  			
  			if($datos===false)
  			{
  				$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (AN)<BR />";
  				$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  				return true;				
  			}
  			
  			if(!empty($datos))
  			{
  				if($obj->GenerarArchivoAN($id,$datos,$codigo_sgsss)===false)
  				{
  					$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (AN) <BR />" . $obj->error . " <BR />" . $obj->mensajeDeError;
  					$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  					return true;
  				}			
  			}	

  			$datos = $ltd->ObtenerAM($request);
  			
  			if($datos===false)
  			{
  				$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (AM)<BR />";
  				$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  				return true;				
  			}
  			
  			if(!empty($datos))
  			{
  				if($obj->GenerarArchivoAM($id,$datos,$codigo_sgsss)===false)
  				{
  					$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (AM) <BR />" . $obj->error . " <BR />" . $obj->mensajeDeError;
  					$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  					return true;
  				}			
  			}				
        
        $datos = $ltd->ObtenerAD($request);
  			
  			if($datos===false)
  			{
  				$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (AD)<BR />";
  				$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  				return true;				
  			}
  			
  			if(!empty($datos))
  			{
  				if($obj->GenerarArchivoAD($id,$datos,$codigo_sgsss)===false)
  				{
  					$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (AM) <BR />" . $obj->error . " <BR />" . $obj->mensajeDeError;
  					$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  					return true;
  				}			
  			}	        
        
        $datos = $ltd->ObtenerUS($request);
  			
  			if($datos===false)
  			{
  				$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (US)<BR />";
  				$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  				return true;				
  			}
  			
  			if(!empty($datos))
  			{
  				if($obj->GenerarArchivoUS($id,$datos,$codigo_sgsss)===false)
  				{
  					$mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (AM) <BR />" . $obj->error . " <BR />" . $obj->mensajeDeError;
  					$this->salida = $html->FormaMensajeModulo($action,$mensaje);	
  					return true;
  				}			
  			}				
  			
        if($obj->GenerarArchivoCT($id)===false)
        {
          $mensaje = "ERROR EN LA CREACION DE ARCHIVOS RIPS (CT) <BR />" . $obj->error . " <BR />" . $obj->mensajeDeError;
          $this->salida = $html->FormaMensajeModulo($action,$mensaje);	
          return true;
        }			

  			$msgError = "";
  			$tiposdocumentos = $obj->ObtenerEstados_cxp();
  			
  			$action['volver'] = ModuloGetURL('app','RIPS_EPS','controller','ListarRadicaciones',$buscador);
        $mensaje = "LOS ARCHIVOS DE LOS RIPS HAN SIDO CREADOS SATISFACTORIAMENTE";
  			$this->salida .= $html->FormaDescargarArchivos($action,$mensaje,$id);
  		}
  		else
  		{
  			$empresa = SessionGetVar("EmpresasCuentas");

  			$msgError = "";
  			$lista = array();
  			$pagina = $conteo = 0;

  			if($request['buscar'])
  			{
  				$lista = $ltd->ObtenerListadoRadicacion($empresa['empresa'],$request);
  				$conteo = $ltd->conteo;
  				$pagina = $ltd->pagina;
  				$msgError = $ltd->ErrMsg();
  			}
  			$tiposdocumentos = $obj->ObtenerEstados_cxp();

  			$action['volver'] = ModuloGetURL('system','Menu');
  			$action['buscar'] = ModuloGetURL('app','RIPS_EPS','controller','ListarRadicaciones');
  			$action['paginador'] = ModuloGetURL('app','RIPS_EPS','controller','ListarRadicaciones',$buscador);

  			$this->salida .= $mdl->FormaListadoRadicaciones($action,$tiposdocumentos,$buscador,$lista,$pagina,$conteo,$msgError);
  		}
  		return true;
    }
    /**
    *
    */
		function ListadoDeArchivos()
    {
      return true;
    }
  }
?>