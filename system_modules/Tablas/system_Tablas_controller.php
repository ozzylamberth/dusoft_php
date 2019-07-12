<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: system_Tablas_controller.php,v 1.8 2008/04/07 13:27:47 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Control: Tablas
	* Modulo que permite el manejo de la informacion de tablas del sistema 
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.8 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  IncludeClass("Modelo", "", "system","Tablas");
  IncludeClass("IndexHTML", "views", "system","Tablas");
	class system_Tablas_controller extends classModulo
	{	
  	/**
    * Vector donde se almacenan los links de la aplicacion
		* @var array $action  
    * @access public
		*/
		var $action = array();
    /**
    * Vector de los datos del request
    *
    * @var array $request
    * @access public
    */
    var $request = array();
    /**
    * Constructor de la clase
    */
    function system_Tablas_controller(){}
    /**
    * Funcion para guardar en sesion el link al que se debe regresar cuando se salga 
    * del modulo
    *
    * @param String $link que se guardara
    */
		function SetActionVolver($link)
		{
			SessionDelVar("ActionVolver_TBL");
			SessionSetVar("ActionVolver_TBL",$link);
		}
    /** 
		* Funcion que crea el index de la clase, aqui se muestra el listado inicial
    *
    * @return boolean
    */    
    function Index()
    {
      $this->request = $_REQUEST;
      $afi = AutoCarga::factory($this->request['nombre_tabla'], "", "system","Tablas");
      $vst = AutoCarga::factory($this->request['nombre_tabla']."_HTML", "views", "system","Tablas");
      $datos = $afi->IsTableExist($this->request['nombre_tabla']);
      $this->action['volver'] = SessionGetVar("ActionVolver_TBL");
      
      if(empty($datos))
      {
        global $ConfigDB;
        $mensaje = "TABLA ".$this->request['nombre_tabla'].", NO EXISTE EN LA BASE DE DATOS ".$ConfigDB['dbhost']." - ".$ConfigDB['dbname'];
        
        $this->salida .= $vst->FormaMensajeModulo($this->action,$mensaje);
        return true;
      }
            
      $campos[$this->request['nombre_tabla']] = $afi->ObtenerCamposTabla($this->request['nombre_tabla']);
      $registros = $afi->ObtenerDatos($campos,$this->request['offset'],$this->request['buscador']);
      $comentario = $afi->ObtenerComentarioTabla($this->request['nombre_tabla']);
      
      $this->action['ver'] = ModuloGetURL('system','Tablas','controller','VerRegistro',array('nombre_tabla'=>$this->request['nombre_tabla']));
      $this->action['editar'] = ModuloGetURL('system','Tablas','controller','Editarregistro',array('nombre_tabla'=>$this->request['nombre_tabla']));
      $this->action['eliminar'] = ModuloGetURL('system','Tablas','controller','EliminarRegistro',array('nombre_tabla'=>$this->request['nombre_tabla']));
      $this->action['adicionar'] = ModuloGetURL('system','Tablas','controller','AdicionarRegistro',array('nombre_tabla'=>$this->request['nombre_tabla']));
      $this->action['paginador'] = ModuloGetURL('system','Tablas','controller','Index',array('nombre_tabla'=>$this->request['nombre_tabla'],'buscador'=>$this->request['buscador']));
      
      $this->salida = $vst->FormaIndex($this->action,$this->request,$campos,$registros,$afi->primarykey,$afi->pagina,$afi->conteo,$comentario['comment']);
      return true;
    }
    /** 
		* Funcion para mostrar los datos de un registro especifico
    *
    * @return boolean
    */  
    function VerRegistro()
    {
      $this->request = $_REQUEST;
      $vst = AutoCarga::factory($this->request['nombre_tabla']."_HTML", "views", "system","Tablas");
      
      if(!empty($vst->ajax))
      {
        $this->SetXajax($vst->ajax,"system_modules/Tablas/RemoteXajax/".$this->request['nombre_tabla'].".php");
      }
      
      $afi = AutoCarga::factory($this->request['nombre_tabla'], "", "system","Tablas");
      $datos = $afi->IsTableExist($this->request['nombre_tabla']);
      
      if(empty($datos))
      {
        global $ConfigDB;
        echo "tabla ".$this->request['nombre_tabla'].", no existe en la base de datos ".$ConfigDB['dbhost']." - ".$ConfigDB['dbname'];
        return true;
      }
      
      $this->action['volver'] = ModuloGetURL('system','Tablas','controller','Index',array('nombre_tabla'=>$this->request['nombre_tabla']));
      
      $campos[$this->request['nombre_tabla']] = $afi->ObtenerCamposTabla($this->request['nombre_tabla']);
      $registros = $afi->ObtenerRegistro($campos,$this->request['pkey']);
      
      $this->salida = $vst->FormaVerRegistro($this->action,$this->request,$campos,$registros,$afi->primarykey);
      return true;
    }
    /** 
		* Funcion que permite mostrar la forma para editar un registro
    *
    * @return boolean
    */  
    function EditarRegistro()
    {
      $this->request = $_REQUEST;
      $vst = AutoCarga::factory($this->request['nombre_tabla']."_HTML", "views", "system","Tablas");
      
      if(!empty($vst->ajax))
      {
        $this->SetXajax($vst->ajax,"system_modules/Tablas/RemoteXajax/".$this->request['nombre_tabla'].".php");
      }
      
      $afi = AutoCarga::factory($this->request['nombre_tabla'], "", "system","Tablas");
      $datos = $afi->IsTableExist($this->request['nombre_tabla']);

      if(empty($datos))
      {
        global $ConfigDB;
        echo "tabla ".$this->request['nombre_tabla'].", no existe en la base de datos ".$ConfigDB['dbhost']." - ".$ConfigDB['dbname'];
        return true;
      }
      
      $this->action['volver'] = ModuloGetURL('system','Tablas','controller','Index',array('nombre_tabla'=>$this->request['nombre_tabla']));
      $this->action['actualizar'] = ModuloGetURL('system','Tablas','controller','ActualizarRegistro',array('nombre_tabla'=>$this->request['nombre_tabla'],'pkey'=>$this->request['pkey']));
      $this->action['ventana'] = ModuloGetURL('system','Tablas','controller','DatosLlaveForanea');

      $campos[$this->request['nombre_tabla']] = $afi->ObtenerCamposTabla($this->request['nombre_tabla']);
      $registros = $afi->ObtenerRegistro($campos,$this->request['pkey']);
      
      $this->salida = $vst->FormaEditarRegistro($this->action,$this->request,$campos,$registros,$afi->primarykey,$afi->foreignkey);
      return true;
    }
    /** 
		* Funcion que permite hacer la actualizacion de un registro
    *
    * @return boolean
    */  
    function ActualizarRegistro()
    {
      $this->request = $_REQUEST;
      
      $afi = AutoCarga::factory($this->request['nombre_tabla'], "", "system","Tablas");
      $campos[$this->request['nombre_tabla']] = $afi->ObtenerCamposTabla($this->request['nombre_tabla']);

      $rst = $afi->ActualizarRegistro($campos,$this->request['pkey'],$this->request);
      if(!$rst)
      {
        $vst = AutoCarga::factory($this->request['nombre_tabla']."_HTML", "views", "system","Tablas");
        if(!empty($vst->ajax))
        {
          $this->SetXajax($vst->ajax,"system_modules/Tablas/RemoteXajax/".$this->request['nombre_tabla'].".php");
        }
        $error = $afi->ErrMsg();
        
        $this->action['volver'] = ModuloGetURL('system','Tablas','controller','Index',array('nombre_tabla'=>$this->request['nombre_tabla']));
        $this->action['actualizar'] = ModuloGetURL('system','Tablas','controller','ActualizarRegistro',array('nombre_tabla'=>$this->request['nombre_tabla'],'pkey'=>$this->request['pkey']));
        $this->action['ventana'] = ModuloGetURL('system','Tablas','controller','DatosLlaveForanea');
        
        $campos[$this->request['nombre_tabla']] = $afi->ObtenerCamposTabla($this->request['nombre_tabla']);
        $registros = $this->request;
        
        $this->salida = $vst->FormaEditarRegistro($this->action,$this->request,$campos,$registros,$afi->primarykey,$afi->foreignkey,$error);
      }
      else
      {
        $this->action['volver'] = ModuloGetURL('system','Tablas','controller','Index',array('nombre_tabla'=>$this->request['nombre_tabla']));
        $vst = AutoCarga::factory($this->request['nombre_tabla']."_HTML", "views", "system","Tablas");
        
        $mensaje = "SE HA ACTUALIZADO UN REGISTRO EN LA TABLA ".$this->request['nombre_tabla']." ";
        $this->salida .= $vst->FormaMensajeModulo($this->action,$mensaje);
      }
      return true;
    }
    /** 
		* Funcion que permite mostrar la forma para adicionar un registro
    *
    * @return boolean
    */  
    function AdicionarRegistro()
    {
      $this->request = $_REQUEST;      
      $vst = AutoCarga::factory($this->request['nombre_tabla']."_HTML", "views", "system","Tablas");
      
      if(!empty($vst->ajax))
      {
        $this->SetXajax($vst->ajax,"system_modules/Tablas/RemoteXajax/".$this->request['nombre_tabla'].".php");
      }
      
      $afi = AutoCarga::factory($this->request['nombre_tabla'], "", "system","Tablas");
      $datos = $afi->IsTableExist($this->request['nombre_tabla']);
      
      if(empty($datos))
      {
        global $ConfigDB;
        echo "tabla ".$this->request['nombre_tabla'].", no existe en la base de datos ".$ConfigDB['dbhost']." - ".$ConfigDB['dbname'];

        return true;
      }

      $this->action['volver'] = ModuloGetURL('system','Tablas','controller','Index',array('nombre_tabla'=>$this->request['nombre_tabla']));
      $this->action['actualizar'] = ModuloGetURL('system','Tablas','controller','IngresarRegistro',array('nombre_tabla'=>$this->request['nombre_tabla']));
      $this->action['ventana'] = ModuloGetURL('system','Tablas','controller','DatosLlaveForanea');

      $campos[$this->request['nombre_tabla']] = $afi->ObtenerCamposTabla($this->request['nombre_tabla']);
      
      $this->salida = $vst->FormaAdicionarRegistro($this->action,$this->request,$campos,$registros,$afi->primarykey,$afi->foreignkey);
      return true;
    }
    /** 
		* Funcion que permite hacer el ingreso de un registro
    *
    * @return boolean
    */  
    function IngresarRegistro()
    {
      $this->request = $_REQUEST;
      $afi = AutoCarga::factory($this->request['nombre_tabla'], "", "system","Tablas");
      $campos[$this->request['nombre_tabla']] = $afi->ObtenerCamposTabla($this->request['nombre_tabla']);

      $rst = $afi->IngresarRegistro($campos,$this->request);
      if(!$rst)
      {
        $vst = AutoCarga::factory($this->request['nombre_tabla']."_HTML", "views", "system","Tablas");
        if(!empty($vst->ajax))
        {
          $this->SetXajax($vst->ajax,"system_modules/Tablas/RemoteXajax/".$this->request['nombre_tabla'].".php");
        }
        
        $error = $afi->ErrMsg();
        
        $this->action['volver'] = ModuloGetURL('system','Tablas','controller','AdicionarRegistro',array('nombre_tabla'=>$this->request['nombre_tabla']));
        $this->action['actualizar'] = ModuloGetURL('system','Tablas','controller','IngresarRegistro',array('nombre_tabla'=>$this->request['nombre_tabla']));
        $this->action['ventana'] = ModuloGetURL('system','Tablas','controller','DatosLlaveForanea');

        $campos[$this->request['nombre_tabla']] = $afi->ObtenerCamposTabla($this->request['nombre_tabla']);
        $registros = $this->request;
        $this->salida = $vst->FormaAdicionarRegistro($this->action,$this->request,$campos,$registros,$afi->primarykey,$afi->foreignkey,$error);
      }
      else
      {
        $this->action['volver'] = ModuloGetURL('system','Tablas','controller','AdicionarRegistro',array('nombre_tabla'=>$this->request['nombre_tabla']));
        $vst = AutoCarga::factory($this->request['nombre_tabla']."_HTML", "views", "system","Tablas");
        
        $mensaje = "SE HA ADICIONADO UN REGISTRO A LA TABLA ".$this->request['nombre_tabla']." ";
        $this->salida .= $vst->FormaMensajeModulo($this->action,$mensaje);
      }
      return true;
    }
    /** 
		* Funcion que permite hacer el borrado de un registro
    *
    * @return boolean
    */  
    function EliminarRegistro()
    {
      $this->request = $_REQUEST;
      $afi = AutoCarga::factory($this->request['nombre_tabla'], "", "system","Tablas");
      $campos[$this->request['nombre_tabla']] = $afi->ObtenerCamposTabla($this->request['nombre_tabla']);
      $rst = $afi->Eliminarregistro($campos,$this->request);
      
      $this->action['volver'] = ModuloGetURL('system','Tablas','controller','Index',array('nombre_tabla'=>$this->request['nombre_tabla']));
      $vst = AutoCarga::factory($this->request['nombre_tabla']."_HTML", "views", "system","Tablas");
      
      if(!$rst)
        $mensaje = "HA OCURRIDO EL SIGUIENTE ERROR :<br><label class=\"label_error\">".strtoupper($afi->ErrMsg())."</label>";
      else
        $mensaje = "EL REGISTRO EN LA TABLA ".$this->request['nombre_tabla'].", HA SIDO ELIMINADO";
        
      $this->salida .= $vst->FormaMensajeModulo($this->action,$mensaje);
      return true;
    }
    /**
    * Funcion que genera la lista de de registros de la tabla para seleccionarlos
    *
    * @return boolean
    */
    function DatosLlaveForanea()
    {
      $this->request = $_REQUEST;
      
      $afi = AutoCarga::factory($this->request['nombre_tabla'], "", "system","Tablas");
      $vst = AutoCarga::factory($this->request['nombre_tabla']."_HTML", "views", "system","Tablas");
      $datos = $afi->IsTableExist($this->request['nombre_tabla']);
      $this->action['volver'] = "javascript:window.close();";
      
      if(empty($datos))
      {
        global $ConfigDB;
        $mensaje = "TABLA ".$this->request['nombre_tabla'].", NO EXISTE EN LA BASE DE DATOS ".$ConfigDB['dbhost']." - ".$ConfigDB['dbname'];
        
        $this->salida .= $vst->FormaMensajeModulo($this->action,$mensaje);
        return true;
      }
            
      $campos[$this->request['nombre_tabla']] = $afi->ObtenerCamposTabla($this->request['nombre_tabla']);
      $registros = $afi->ObtenerDatos($campos,$this->request['offset'],$this->request['buscador']);
      
      $this->action['paginador'] = ModuloGetURL('system','Tablas','controller','DatosLlaveForanea',array('nombre_tabla'=>$this->request['nombre_tabla'],'forma'=>$this->request['forma'],'eqivalencias' =>$this->request['eqivalencias'],'buscador'=>$this->request['buscador']));
      
      $this->salida = $vst->FormaListaTabla($this->action,$this->request,$campos,$registros,$afi->pagina,$afi->conteo);
      return true;
    }
	}
?>