<?php
	
	  
  function reqInsertarVentanaOpciones($cadena,$cargo,$ProgramacionId){
    
    $objResponse = new xajaxResponse();  
    $valores=explode('||//',$cadena); 
    BorrarOpcionesProcedimientos($ProgramacionId,$cargo);       
    for($i=0;$i<(sizeof($valores)-1);$i++){       
      InsertarOpcionesProcedimientos($ProgramacionId,$cargo,$valores[$i]);  
    }
    $html=ObtenerForma($ProgramacionId,$cargo);
    $objResponse->assign("MostrarProcedimientoOpc","innerHTML",$html);
    $objResponse->call("Cerrar");              
    return $objResponse;
  }
  
  function reqEliminarOpcionesProc($ProgramacionId,$cargo,$procedimiento){
    
    $objResponse = new xajaxResponse();      
    BorrarOpcionProcedimiento($procedimiento,$cargo,$ProgramacionId);           
    $html=ObtenerForma($ProgramacionId,$cargo);
    $objResponse->assign("MostrarProcedimientoOpc","innerHTML",$html);    
    return $objResponse;
  }
  
   /**********************************************************************************
    * Trae de la base de datos el nombre del procedimiento.
    *
    * @access public         
    * @return boolean
    ***********************************************************************************/ 
    
    function ObtenerForma($programacion_qx,$cargo){          
        $procedimientos=BuscarProcedimientosInsertados($programacion_qx,$cargo);
        if($procedimientos){
          $html.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
          $html.="<tr class=\"modulo_table_list_title\">";
          $html.="<td width=\"10%\">CODIGO</td>";
          $html.="<td>PROCEDIMIENTO</td>";
          $html.="<td width=\"5%\">&nbsp;</td>";          
          $html.="</tr>";        
          for($i=0;$i<sizeof($procedimientos);$i++){
            $html.="<tr class=\"modulo_list_claro\">";
            $html.="<td width=\"20%\">".$procedimientos[$i]['procedimiento_opcion']."</td>";
            $html.="<td>".$procedimientos[$i]['descripcion']."</td>";
            $html.="<td align=\"center\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\" onclick=\"javascript:xajax_reqEliminarOpcionesProc('$programacion_qx','$cargo','".$procedimientos[$i]['procedimiento_opcion']."');\"></td>";                            
            $html.="</tr>";
          }        
          $html.="</table><BR>";
        }
        return $html;
    }
    
    /**********************************************************************************
    * Trae de la base de datos el nombre del procedimiento.
    *
    * @access public         
    * @return boolean
    ***********************************************************************************/ 
    
    function BuscarProcedimientosInsertados($programacion_qx,$cargo){          
        $query = "SELECT b.procedimiento_opcion,b.descripcion
                 FROM qx_cups_opc_procedimientos_programacion a,qx_cups_opciones_procedimientos b 
                 WHERE a.programacion_id='".$programacion_qx."' 
                 AND a.procedimiento_qx='".$cargo."' 
                 AND a.procedimiento_qx=b.cargo AND a.procedimiento_opcion=b.procedimiento_opcion";
        if(!$resultado = ConexionBaseDatos($query))
        return false;  
        while (!$resultado->EOF) {
          $vars[]=$resultado->GetRowAssoc($toUpper=false);
          $resultado->MoveNext();
        }               
        return $vars;
    }
    
    
    
    /**********************************************************************************
    * Trae de la base de datos el nombre del procedimiento.
    *
    * @access public         
    * @return boolean
    ***********************************************************************************/ 
    
    function InsertarOpcionesProcedimientos($programacion,$cargo,$procedimiento){                  
        
        $query = "INSERT INTO qx_cups_opc_procedimientos_programacion(
                    programacion_id,procedimiento_qx,procedimiento_opcion)
                    VALUES('".$programacion."','".$cargo."','".$procedimiento."')";
        if(!$resultado = ConexionBaseDatos($query))
        return false;                 
        $resultado->Close();
        return true;
    }
    
    /**********************************************************************************
    * Trae de la base de datos el nombre del procedimiento.
    *
    * @access public         
    * @return boolean
    ***********************************************************************************/ 
    
    function BorrarOpcionesProcedimientos($programacion,$cargo){                  
        
        $query = "DELETE FROM qx_cups_opc_procedimientos_programacion
                  WHERE programacion_id='".$programacion."' AND
                  procedimiento_qx='".$cargo."'";
        if(!$resultado = ConexionBaseDatos($query))
        return false;
    }
    
    /**********************************************************************************
    * Trae de la base de datos el nombre del procedimiento.
    *
    * @access public         
    * @return boolean
    ***********************************************************************************/ 
    
    function BorrarOpcionProcedimiento($procedimiento,$cargo,$programacion){                  
        
        $query = "DELETE FROM qx_cups_opc_procedimientos_programacion
                  WHERE programacion_id='".$programacion."' 
                  AND procedimiento_qx='".$cargo."'
                  AND procedimiento_opcion='".$procedimiento."'
                  ";
        if(!$resultado = ConexionBaseDatos($query))
        return false;
    }
    
    
		
	 /**********************************************************************************
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la 
    * consulta sql 
    *
    * @access public  
    * @param  string  $sql  sentencia sql a ejecutar 
    * @return rst 
    ************************************************************************************/
  function ConexionBaseDatos($sql)
  {
    list($dbconn)=GetDBConn();
    //$dbconn->debug=true;
    $rst = $dbconn->Execute($sql);
      
    if ($dbconn->ErrorNo() != 0)
    {
      $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
      echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
      return false;
    }
    return $rst;
  }    

?>