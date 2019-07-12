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
  
  
  
  class Consultas_Impresion_Acta
  {
    /**
    * Contructor
    */
    
	function Consultas_Impresion_Acta(){}
	  	
  
    
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
    
    function BuscarResgistroActa($empresa_id,$prefijo,$numero,$acta_tecnica_id)
    {
            $sql="SELECT             
                   b.razon_social,
                   a.*
                  FROM
                      esm_acta_tecnica AS a
                      JOIN empresas as b ON (a.empresa_id = b.empresa_id)
                  WHERE
                          a.acta_tecnica_id = ".$acta_tecnica_id."
                    and   a.prefijo = '".$prefijo."'
                    and   a.numero = ".$numero."
                    and   a.empresa_id = '".$empresa_id."'; ";
        //print_r($sql);

            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }
                $resultado->Close();
                return $cuentas;   
    }
	
	function BuscarItem_EVisual($acta_tecnica_id,$evaluacion_visual_id)
    {
            $sql="SELECT
                        *
                  FROM
                      esm_acta_tecnica_evaluacion_visual
                  WHERE
                             acta_tecnica_id = ".$acta_tecnica_id."
                      and    evaluacion_visual_id = ".$evaluacion_visual_id."

					";
        // print_r($sql);

            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }
                $resultado->Close();
                return $cuentas;   
    }
    
      function BuscarItem($empresa_id,$prefijo,$numero,$acta_tecnica_id)
    {
            $sql="SELECT
                        fc_descripcion_producto_alterno(invp.codigo_producto) as descripcion_producto,
                        tmp.*,
                        invp.presentacioncomercial_id,
                        invp.cantidad as precantidad,
                        invp.codigo_invima,
                        fab.descripcion as fabricante
                  FROM
                      esm_acta_tecnica tmp,
                      inventarios_productos invp,
                      inv_fabricantes fab
                  WHERE
                          tmp.acta_tecnica_id = ".$acta_tecnica_id."
                    and   tmp.empresa_id = '".$empresa_id."'
                    and   tmp.prefijo = '".$prefijo."'
                    and   tmp.numero = ".$numero."
                    and   tmp.codigo_producto = invp.codigo_producto
                    and   invp.fabricante_id = fab.fabricante_id
                          ; ";
        // print_r($sql);

            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }
                $resultado->Close();
                return $cuentas;   
    }
    
    
	function Listar_EvaluacionesVisuales()
    {
            $sql="SELECT
                        *
                  FROM
                      esm_evaluacion_visual
                  WHERE
                          sw_estado = '1'; ";
         // print_r($sql);

            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }

                $resultado->Close();

                return $cuentas;   
    }

      function Buscar_ActasTecnicas($empresa_id,$prefijo,$numero)
    {
            $sql="select
                      a.acta_tecnica_id,
                      a.prefijo,
                      a.numero,
                      a.codigo_producto,
                      fc_descripcion_producto(a.codigo_producto) as producto,
                      a.lote,
                      a.fecha_vencimiento,
                      a.observacion,
                      a.fecha_registro,
                      a.responsable_realiza,
                      a.responsable_verifica,
                      b.nombre
                      from
                      esm_acta_tecnica as a
                      JOIN system_usuarios as b ON (a.usuario_id = b.usuario_id)
                      where
                          a.empresa_id = '".$empresa_id."'
                      and a.prefijo = '".$prefijo."'
                      and a.numero = ".$numero."
                      order by a.fecha_registro; ";
        //print_r($sql);

            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }
                $resultado->Close();
                return $cuentas;   
    }
	 
	}
	
?>