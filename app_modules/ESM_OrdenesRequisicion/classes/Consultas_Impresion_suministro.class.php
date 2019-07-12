<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Consultas_Impresion.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */ 
  
  
  
  class Consultas_Impresion_suministro
  {
    /**
    * Contructor
    */
    
	function Consultas_Impresion_suministro(){}
	  	
  /*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
		function Consultar_OrdenRequisicion($orden_requisicion_id)
		{
		
      $sql = "SELECT 
                     tr.descripcion_orden_requisicion,
                     tf.descripcion as tipo_fuerza,
                     esm.nombre_tercero,
                     tmp.*,
                     usu.nombre,
                     emp.razon_social,
					 esm.direccion,
					 MP.municipio || ' ' || TD.departamento || ' ' || TP.pais AS ubicacion
					 
            FROM        
                     esm_orden_requisicion tmp,
                     terceros esm,
                     esm_tipos_fuerzas tf,
                     esm_tipos_ordenes_requisicion tr,
                     system_usuarios usu,
                     empresas emp,
					tipo_mpios MP,
					tipo_dptos TD,
					tipo_pais TP
                    ";
      $sql .= " where ";
      $sql .= "           tmp.orden_requisicion_id = ".$orden_requisicion_id." ";
      $sql .= "     and   tmp.tipo_id_tercero = esm.tipo_id_tercero ";
      $sql .= "     and   tmp.tercero_id = esm.tercero_id ";
      $sql .= "     and   tmp.tipo_fuerza_id = tf.tipo_fuerza_id ";
      $sql .= "     and   tmp.tipo_orden_requisicion = tr.tipo_orden_requisicion ";
      $sql .= "     and   tmp.usuario_id_creador = usu.usuario_id ";
      $sql .= "     and   tmp.empresa_id_registro = emp.empresa_id 
					AND     esm.tipo_pais_id=MP.tipo_pais_id
					AND     esm.tipo_dpto_id=MP.tipo_dpto_id
					AND     esm.tipo_mpio_id=MP.tipo_mpio_id
					AND     MP.tipo_pais_id=TD.tipo_pais_id
					AND     MP.tipo_dpto_id=TD.tipo_dpto_id
					AND     TD.tipo_pais_id=TP.tipo_pais_id 
	  
	  
	  ";
    
    
		if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
 
    /*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function Consultar_OrdenRequisicionDetalle($orden_requisicion_id)
		{
    		//	$this->debug=true;
    		$sql = "SELECT 
              fc_descripcion_producto_alterno(codigo_producto) as descripcion,
              fc_codigo_mindefensa(codigo_producto) as mindefensa,
              *
    		FROM 
              esm_orden_requisicion_d ";
    		$sql .= " where ";
    		$sql .= "      orden_requisicion_id = ".$orden_requisicion_id."  ";
    		$sql .= " ORDER BY sw_pactado ";
    	

        
    		if(!$rst = $this->ConexionBaseDatos($sql)) 
    			return false;

    			$datos = array(); //Definiendo que va a ser un arreglo.
    			
    			while(!$rst->EOF) //Recorriendo el Vector;
    			{
    				$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
    				$rst->MoveNext();
    			}
    			$rst->Close();
    			return $datos;
    } 
    
    /**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param 	string  $sql	sentencia sql a ejecutar $empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_est,$sw_cc,$sw_dc
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
				$this->mensajeDeError = "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}
    
    function NombreUsu($usuario_id)
{
 $sql=" select nombre
        from system_usuarios
        where usuario_id='".trim($usuario_id)."'";
             
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
            
        $documentos=Array();
        while(!$resultado->EOF)
        {
          $documentos = $resultado->GetRowAssoc($ToUpper = false);
          $resultado->MoveNext();
        }
        
          $resultado->Close();
          return $documentos;
}

/*
    *	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
    *	Existentes en el sistema, segun el grupo.
    */
		function Bodega($empresa_id,$centro_utilidad,$bodega)
		{
    		//$this->debug=true;
    		$sql = "SELECT 
              bod.*,
              cent.descripcion as centro
    		FROM 
              bodegas bod, 
              centros_utilidad cent ";
    		$sql .= " where ";
    		$sql .= "          bod.empresa_id = '".$empresa_id."'  ";
    		$sql .= "      and bod.centro_utilidad = '".$centro_utilidad."'  ";
    		$sql .= "      and bod.bodega = '".$bodega."'  ";
    		$sql .= "      and bod.empresa_id = cent.empresa_id  ";
    		$sql .= "      and bod.centro_utilidad = cent.centro_utilidad  ";
    		
    		if(!$rst = $this->ConexionBaseDatos($sql)) 
    			return false;

    			$datos = array(); //Definiendo que va a ser un arreglo.
    			
    			while(!$rst->EOF) //Recorriendo el Vector;
    			{
    				$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
    				$rst->MoveNext();
    			}
    			$rst->Close();
    			return $datos;
    } 
     Function Consultar_Registros_tmp_suministro($orden_id,$codigo)
	 {
	 
	  
		$sql = " select     tmp.tipo_id_paciente,
							tmp.paciente_id,
							tmp.cantidad,
							dat.primer_nombre ||' ' || dat.segundo_nombre||' '|| dat.primer_apellido ||' '|| dat.segundo_apellido   AS nombre_completo
				from        esm_orden_requisicion_d_pacientes tmp,
				            eps_afiliados_datos dat
		
							
				where    tmp.orden_requisicion_id='".$orden_id."'
				and      tmp.codigo_producto='".$codigo."'
				and      tmp.tipo_id_paciente=dat.afiliado_tipo_id
				and      tmp.paciente_id=dat.afiliado_id 
				order  by  nombre_completo ";
			  
	       if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
	 
	 
	 
	 
	 }
	 
	}
	
?>