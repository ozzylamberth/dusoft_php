<?php
  /******************************************************************************
  * $Id: RecaudoElectronico.class.php,v 1.2 2010/03/29 16:21:02 sandra Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.2 $ 
  * 
  * @autor Jaime Gomez
  ********************************************************************************/
  class RecaudoElectronico
  {
    
    
              

/******************************************************************************
*funcion constructora 
*******************************************************************************/  
    
    function RecaudoElectronico(){}
    
    
/************************************************************************************
* funcion para sacar los pendientes de cada factura
*************************************************************************************/    
function Obtener_Recaudo($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,$dif,$tmp_recibo_id) 
    {   
  
  
//   $sql="SELECT 	cartera.num_consecutivo,
// 	cartera.fecha,
// 	cartera.tipo_id_tercero,
// 	cartera.tercero_id,
// 	con_ff.prefijo,
// 	con_ff.empresa_id,
// 	con_ff.factura_fiscal,
// 	cartera.valor_neto,
// 	con_ff.saldo,
// 	(con_ff.saldo - cartera.valor_neto) as pendiente,
// 	con_ff.total_factura,
// 	COALESCE(TMP.valor_abonado,0) as valor_cruzado,
// 	COALESCE(TMP.tmp_rc_id,-1) as tmp_id,
// 	(sum(con_ff.saldo)-sum(cartera.valor_neto)) as total_saldos,
// 	sum(cartera.valor_neto) as total_neto,
// 	sum(con_ff.total_factura) as total_facturas,
// 	sum(con_ff.saldo) as total_saldos
// 	
// 	FROM	view_fac_facturas as con_ff
// 	LEFT JOIN tmp_rc_detalle_tesoreria_facturas AS TMP
// 	ON (TMP.empresa_id = con_ff.empresa_id AND TMP.prefijo_factura = con_ff.prefijo 
// 	AND TMP.factura_fiscal=con_ff.factura_fiscal AND TMP.tmp_recibo_id =".$tmp_recibo_id."),
//   cartera_recaudo_electronico_tmp as cartera
//        
// 	WHERE con_ff.empresa_id = cartera.empresa_id
// 	".$dif." 
// 	AND con_ff.prefijo = cartera.prefijo 
// 	AND con_ff.factura_fiscal = cartera.numero
//   AND cartera.num_consecutivo=".$num_consecutivo."
// 	GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13";
  
  $sql="SELECT 	cartera.*,
				COALESCE(TMP.valor_abonado,0) as valor_cruzado,
				COALESCE(TMP.tmp_rc_id,-1) as tmp_id
FROM
		(
			SELECT 	cartera.num_consecutivo,
							cartera.fecha,
							cartera.tipo_id_tercero,
							cartera.tercero_id,
							con_ff.prefijo,
							con_ff.empresa_id,
							con_ff.factura_fiscal,
							cartera.valor_neto,
							con_ff.saldo,
							(con_ff.saldo - cartera.valor_neto) as pendiente,
							con_ff.total_factura,
							(con_ff.saldo-cartera.valor_neto) as total_saldos,
							cartera.valor_neto as total_neto,
							con_ff.total_factura as total_facturas,
							con_ff.saldo as total_saldos
			FROM  	cartera_recaudo_electronico_tmp cartera,
							fac_facturas as con_ff
			WHERE 	cartera.num_consecutivo=$num_consecutivo
			AND   cartera.mensaje_error IS NULL
      AND   	con_ff.empresa_id = cartera.empresa_id
			AND  		con_ff.prefijo = cartera.prefijo 
      AND     con_ff.factura_fiscal = cartera.numero
			$dif
			AND 		con_ff.empresa_id = '$empresa_id'
			AND			con_ff.estado = '0'::bpchar
			AND			con_ff.sw_clase_factura = '1'::bpchar
			UNION ALL
			SELECT 	cartera.num_consecutivo,
							cartera.fecha,
							cartera.tipo_id_tercero,
							cartera.tercero_id,
							con_ff.prefijo,
							con_ff.empresa_id,
							con_ff.factura_fiscal,
							cartera.valor_neto,
							con_ff.saldo,
							(con_ff.saldo - cartera.valor_neto) as pendiente,
							con_ff.total_factura,
							(con_ff.saldo-cartera.valor_neto) as total_saldos,
							cartera.valor_neto as total_neto,
							con_ff.total_factura as total_facturas,
							con_ff.saldo as total_saldos
			FROM  cartera_recaudo_electronico_tmp cartera,
						facturas_externas as con_ff
			WHERE cartera.num_consecutivo=$num_consecutivo
      AND   cartera.mensaje_error IS NULL 
			AND   con_ff.empresa_id = cartera.empresa_id
			AND   con_ff.prefijo = cartera.prefijo 
			AND   con_ff.factura_fiscal = cartera.numero
      $dif
			AND		con_ff.empresa_id = '$empresa_id'
			AND		con_ff.estado = '0'::bpchar
		) AS cartera
		LEFT JOIN tmp_rc_detalle_tesoreria_facturas AS TMP
		ON ( TMP.empresa_id = cartera.empresa_id 
       AND TMP.prefijo_factura = cartera.prefijo 
	     AND TMP.factura_fiscal = cartera.factura_fiscal 
       AND TMP.tmp_recibo_id = $tmp_recibo_id
       AND TMP.empresa_id = '$empresa_id')";
	//return $sql;
       if(!$resultado = $this->ConexionBaseDatos($sql))
  	return $sql;    
	//return false;    
  	$cuentas=array();
  	while(!$resultado->EOF)
        {
          $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
          $resultado->MoveNext();
        }
      
        $resultado->Close();
        //return $sql;
        return $cuentas;
}

 
/***************************************************************************************
*obtener recaudo total facturas
****************************************************************************************/
function Obtener_Recaudo_Total_facturas($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,$dif,$tmp_recibo_id) 
    {   
  
  
//   $sql="SELECT 	
// 	sum(con_ff.total_factura) as total_facturas
// 		
// 	FROM
// 	(
// 		SELECT 	FF.total_factura, 
// 			FF.saldo, 
// 			FF.empresa_id,
// 			FF.prefijo, 
// 			FF.factura_fiscal
// 		
// 		FROM 	
// 			view_fac_facturas FF,
// 			envios_detalle ED,
// 			envios EN 
// 			
// 		WHERE FF.empresa_id = '".$empresa_id."' 
// 		AND FF.estado = '0' 
// 		AND FF.saldo !=0 
// 		AND FF.tipo_id_tercero ='".$tipo_id_tercero."' 
// 		AND FF.tercero_id ='".$tercero_id."'
// 		AND ED.prefijo = FF.prefijo  
// 		AND ED.factura_fiscal = FF.factura_fiscal
// 		AND ED.empresa_id = FF.empresa_id
// 		AND ED.envio_id = EN.envio_id
// 		AND EN.fecha_radicacion IS NOT NULL
// 	) as con_ff
// 	LEFT JOIN tmp_rc_detalle_tesoreria_facturas AS TMP
// 	ON (TMP.empresa_id = con_ff.empresa_id AND TMP.prefijo_factura = con_ff.prefijo 
// 	AND TMP.factura_fiscal=con_ff.factura_fiscal AND TMP.tmp_recibo_id =".$tmp_recibo_id."),
//        cartera_recaudo_electronico_tmp as cartera
//        
// 	WHERE con_ff.empresa_id = cartera.empresa_id
// 	".$dif." 
// 	AND con_ff.prefijo = cartera.prefijo 
// 	AND con_ff.factura_fiscal = cartera.numero
//         AND cartera.num_consecutivo=".$num_consecutivo."
// 	";
	
  
  $sql="SELECT 	sum(cartera.total) as total_facturas
  FROM
		(
			SELECT 	sum(con_ff.total_factura) AS total,
							con_ff.prefijo,
							con_ff.empresa_id,
							con_ff.factura_fiscal
			FROM  	cartera_recaudo_electronico_tmp cartera,
							fac_facturas as con_ff,
              envios_detalle ED,
			        envios EN 
			WHERE 	cartera.num_consecutivo=$num_consecutivo
			AND   cartera.mensaje_error IS NULL
      AND   	con_ff.empresa_id = cartera.empresa_id
			AND  		con_ff.prefijo = cartera.prefijo 
			AND   	con_ff.factura_fiscal = cartera.numero
			AND 		con_ff.empresa_id = '$empresa_id'
			AND			con_ff.estado = '0'::bpchar
			AND			con_ff.sw_clase_factura = '1'::bpchar
      AND     con_ff.saldo !=0 
      AND     con_ff.tipo_id_tercero ='".$tipo_id_tercero."' 
      AND     con_ff.tercero_id ='".$tercero_id."'
      AND     ED.prefijo = con_ff.prefijo  
      AND     ED.factura_fiscal = con_ff.factura_fiscal
      AND     ED.empresa_id = con_ff.empresa_id
      AND     ED.envio_id = EN.envio_id
      AND     EN.fecha_radicacion IS NOT NULL
      $dif
      GROUP BY 2,3,4
			UNION ALL
			SELECT 	sum(con_ff.total_factura) AS total,
							con_ff.prefijo,
							con_ff.empresa_id,
							con_ff.factura_fiscal
			FROM    cartera_recaudo_electronico_tmp cartera,
						  facturas_externas as con_ff
			WHERE cartera.num_consecutivo=$num_consecutivo
			AND   cartera.mensaje_error IS NULL
      AND   con_ff.empresa_id = cartera.empresa_id
			AND   con_ff.prefijo = cartera.prefijo 
			AND   con_ff.factura_fiscal = cartera.numero
			AND		con_ff.empresa_id = '$empresa_id'
			AND		con_ff.estado = '0'::bpchar
      AND     con_ff.saldo !=0 
      AND     con_ff.tipo_id_tercero ='".$tipo_id_tercero."' 
      AND     con_ff.tercero_id ='".$tercero_id."'
      $dif
      GROUP BY 2,3,4
		) AS cartera
		LEFT JOIN tmp_rc_detalle_tesoreria_facturas AS TMP
		ON ( TMP.empresa_id = cartera.empresa_id 
       AND TMP.prefijo_factura = cartera.prefijo 
	     AND TMP.factura_fiscal = cartera.factura_fiscal 
       AND TMP.tmp_recibo_id = $tmp_recibo_id
       AND TMP.empresa_id = '$empresa_id')";
       if(!$resultado = $this->ConexionBaseDatos($sql))
  	return $sql;    
	//return false;    
  	$cuentas=array();
  	while(!$resultado->EOF)
        {
          $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
          $resultado->MoveNext();
        }
      
        $resultado->Close();
        //return $sql;
        return $cuentas;
}

/****************************************************************************************
* 
*****************************************************************************************/
function Obtener_Recaudo_Total_Saldo($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,$dif,$tmp_recibo_id) 
    {   
  
  
//   $sql="SELECT 	
// 	sum(con_ff.saldo) as total_saldos
// 		
// 	FROM
// 	(
// 		SELECT 	FF.total_factura, 
// 			FF.saldo, 
// 			FF.empresa_id,
// 			FF.prefijo, 
// 			FF.factura_fiscal
// 		
// 		FROM 	
// 			view_fac_facturas FF,
// 			envios_detalle ED,
// 			envios EN 
// 			
// 		WHERE FF.empresa_id = '".$empresa_id."' 
// 		AND FF.estado = '0' 
// 		AND FF.saldo !=0 
// 		AND FF.tipo_id_tercero ='".$tipo_id_tercero."' 
// 		AND FF.tercero_id ='".$tercero_id."'
// 		AND ED.prefijo = FF.prefijo  
// 		AND ED.factura_fiscal = FF.factura_fiscal
// 		AND ED.empresa_id = FF.empresa_id
// 		AND ED.envio_id = EN.envio_id
// 		AND EN.fecha_radicacion IS NOT NULL
// 	) as con_ff
// 	LEFT JOIN tmp_rc_detalle_tesoreria_facturas AS TMP
// 	ON (TMP.empresa_id = con_ff.empresa_id AND TMP.prefijo_factura = con_ff.prefijo 
// 	AND TMP.factura_fiscal=con_ff.factura_fiscal AND TMP.tmp_recibo_id =".$tmp_recibo_id."),
//        cartera_recaudo_electronico_tmp as cartera
//        
// 	WHERE con_ff.empresa_id = cartera.empresa_id
// 	".$dif." 
// 	AND con_ff.prefijo = cartera.prefijo 
// 	AND con_ff.factura_fiscal = cartera.numero
//         AND cartera.num_consecutivo=".$num_consecutivo."
// 	";
	
  $sql="SELECT 	sum(cartera.saldox) as total_saldos
  FROM
		(
			SELECT 	sum(con_ff.saldo) AS saldox,
							con_ff.prefijo,
							con_ff.empresa_id,
							con_ff.factura_fiscal
			FROM  	cartera_recaudo_electronico_tmp cartera,
							fac_facturas as con_ff,
              envios_detalle ED,
			        envios EN 
			WHERE 	cartera.num_consecutivo=$num_consecutivo
			AND   cartera.mensaje_error IS NULL
      AND   	con_ff.empresa_id = cartera.empresa_id
			AND  		con_ff.prefijo = cartera.prefijo 
			AND   	con_ff.factura_fiscal = cartera.numero
			AND 		con_ff.empresa_id = '$empresa_id'
			AND			con_ff.estado = '0'::bpchar
			AND			con_ff.sw_clase_factura = '1'::bpchar
      AND     con_ff.saldo !=0 
      AND     con_ff.tipo_id_tercero ='".$tipo_id_tercero."' 
      AND     con_ff.tercero_id ='".$tercero_id."'
      AND     ED.prefijo = con_ff.prefijo  
      AND     ED.factura_fiscal = con_ff.factura_fiscal
      AND     ED.empresa_id = con_ff.empresa_id
      AND     ED.envio_id = EN.envio_id
      AND     EN.fecha_radicacion IS NOT NULL
      $dif
      GROUP BY 2,3,4
			UNION ALL
			SELECT 	sum(con_ff.saldo) AS saldox,
							con_ff.prefijo,
							con_ff.empresa_id,
							con_ff.factura_fiscal
			FROM    cartera_recaudo_electronico_tmp cartera,
						  facturas_externas as con_ff
			WHERE cartera.num_consecutivo=$num_consecutivo
      AND   cartera.mensaje_error IS NULL
			AND   con_ff.empresa_id = cartera.empresa_id
			AND   con_ff.prefijo = cartera.prefijo 
			AND   con_ff.factura_fiscal = cartera.numero
			AND		con_ff.empresa_id = '$empresa_id'
			AND		con_ff.estado = '0'::bpchar
      AND     con_ff.saldo !=0 
      AND     con_ff.tipo_id_tercero ='".$tipo_id_tercero."' 
      AND     con_ff.tercero_id ='".$tercero_id."'
      $dif
      GROUP BY 2,3,4
		) AS cartera
		LEFT JOIN tmp_rc_detalle_tesoreria_facturas AS TMP
		ON ( TMP.empresa_id = cartera.empresa_id 
       AND TMP.prefijo_factura = cartera.prefijo 
	     AND TMP.factura_fiscal = cartera.factura_fiscal 
       AND TMP.tmp_recibo_id = $tmp_recibo_id
       AND TMP.empresa_id = '$empresa_id')";
       
       
       if(!$resultado = $this->ConexionBaseDatos($sql))
  	//return $sql;    
	  return false;    
  	$cuentas=array();
  	while(!$resultado->EOF)
        {
          $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
          $resultado->MoveNext();
        }
      
        $resultado->Close();
        //return $sql;
        return $cuentas;
}

/*****************************************************************************************
*Recaudo total Neto
********************************************************************************************/

function Obtener_Recaudo_Total_Neto($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,$dif,$tmp_recibo_id) 
    {   
  
  
//   $sql="SELECT 	
// 	sum(cartera.valor_neto) as total_neto
// 	
// 		
// 	FROM
// 	(
// 		SELECT 	FF.total_factura, 
// 			FF.saldo, 
// 			FF.empresa_id,
// 			FF.prefijo, 
// 			FF.factura_fiscal
// 		
// 		FROM 	
// 			view_fac_facturas FF,
// 			envios_detalle ED,
// 			envios EN 
// 			
// 		WHERE FF.empresa_id = '".$empresa_id."' 
// 		AND FF.estado = '0' 
// 		AND FF.saldo !=0 
// 		AND FF.tipo_id_tercero ='".$tipo_id_tercero."' 
// 		AND FF.tercero_id ='".$tercero_id."'
// 		AND ED.prefijo = FF.prefijo  
// 		AND ED.factura_fiscal = FF.factura_fiscal
// 		AND ED.empresa_id = FF.empresa_id
// 		AND ED.envio_id = EN.envio_id
// 		AND EN.fecha_radicacion IS NOT NULL
// 	) as con_ff
// 	LEFT JOIN tmp_rc_detalle_tesoreria_facturas AS TMP
// 	ON (TMP.empresa_id = con_ff.empresa_id AND TMP.prefijo_factura = con_ff.prefijo 
// 	AND TMP.factura_fiscal=con_ff.factura_fiscal AND TMP.tmp_recibo_id =".$tmp_recibo_id."),
//        cartera_recaudo_electronico_tmp as cartera
//        
// 	WHERE con_ff.empresa_id = cartera.empresa_id
// 	".$dif." 
// 	AND con_ff.prefijo = cartera.prefijo 
// 	AND con_ff.factura_fiscal = cartera.numero
//         AND cartera.num_consecutivo=".$num_consecutivo."
// 	";
  
  $sql="SELECT 	sum(cartera.valor_n) as total_neto
  FROM
		(
			SELECT 	sum(cartera.valor_neto) AS valor_n,
							con_ff.prefijo,
							con_ff.empresa_id,
							con_ff.factura_fiscal
			FROM  	cartera_recaudo_electronico_tmp cartera,
							fac_facturas as con_ff,
              envios_detalle ED,
			        envios EN 
			WHERE 	cartera.num_consecutivo=$num_consecutivo
      AND     cartera.mensaje_error IS NULL
			AND   	con_ff.empresa_id = cartera.empresa_id
			AND  		con_ff.prefijo = cartera.prefijo 
			AND   	con_ff.factura_fiscal = cartera.numero
			AND 		con_ff.empresa_id = '$empresa_id'
			AND			con_ff.estado = '0'::bpchar
			AND			con_ff.sw_clase_factura = '1'::bpchar
      AND     con_ff.saldo !=0 
      AND     con_ff.tipo_id_tercero ='".$tipo_id_tercero."' 
      AND     con_ff.tercero_id ='".$tercero_id."'
      AND     ED.prefijo = con_ff.prefijo  
      AND     ED.factura_fiscal = con_ff.factura_fiscal
      AND     ED.empresa_id = con_ff.empresa_id
      AND     ED.envio_id = EN.envio_id
      AND     EN.fecha_radicacion IS NOT NULL
      $dif
      GROUP BY 2,3,4
			UNION ALL
			SELECT 	sum(cartera.valor_neto) AS valor_n,
							con_ff.prefijo,
							con_ff.empresa_id,
							con_ff.factura_fiscal
			FROM    cartera_recaudo_electronico_tmp cartera,
						  facturas_externas as con_ff
			WHERE cartera.num_consecutivo=$num_consecutivo
			AND   con_ff.empresa_id = cartera.empresa_id
			AND   con_ff.prefijo = cartera.prefijo 
			AND   con_ff.factura_fiscal = cartera.numero
			AND		con_ff.empresa_id = '$empresa_id'
			AND		con_ff.estado = '0'::bpchar
      AND     con_ff.saldo !=0 
      AND     con_ff.tipo_id_tercero ='".$tipo_id_tercero."' 
      AND     con_ff.tercero_id ='".$tercero_id."'
      $dif
      GROUP BY 2,3,4
		) AS cartera
		LEFT JOIN tmp_rc_detalle_tesoreria_facturas AS TMP
		ON ( TMP.empresa_id = cartera.empresa_id 
       AND TMP.prefijo_factura = cartera.prefijo 
	     AND TMP.factura_fiscal = cartera.factura_fiscal 
       AND TMP.tmp_recibo_id = $tmp_recibo_id
       AND TMP.empresa_id = '$empresa_id')";
	
       if(!$resultado = $this->ConexionBaseDatos($sql))
  	return $sql;    
	//return false;    
  	$cuentas=array();
  	while(!$resultado->EOF)
        {
          $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
          $resultado->MoveNext();
        }
      
        $resultado->Close();
        //return $sql;
        return $cuentas;
}


/*****************************************************************************************
*Recaudo total PENDIENTE
********************************************************************************************/

function Obtener_Recaudo_Total_Pendiente($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo,$dif,$tmp_recibo_id) 
    {   
  
  
//   $sql="SELECT 	
// 	(sum(con_ff.saldo)-sum(cartera.valor_neto)) as total_pendientes
// 	
// 		
// 	FROM
// 	(
// 		SELECT 	FF.total_factura, 
// 			FF.saldo, 
// 			FF.empresa_id,
// 			FF.prefijo, 
// 			FF.factura_fiscal
// 		
// 		FROM 	
// 			view_fac_facturas FF,
// 			envios_detalle ED,
// 			envios EN 
// 			
// 		WHERE FF.empresa_id = '".$empresa_id."' 
// 		AND FF.estado = '0' 
// 		AND FF.saldo !=0 
// 		AND FF.tipo_id_tercero ='".$tipo_id_tercero."' 
// 		AND FF.tercero_id ='".$tercero_id."'
// 		AND ED.prefijo = FF.prefijo  
// 		AND ED.factura_fiscal = FF.factura_fiscal
// 		AND ED.empresa_id = FF.empresa_id
// 		AND ED.envio_id = EN.envio_id
// 		AND EN.fecha_radicacion IS NOT NULL
// 	) as con_ff
// 	LEFT JOIN tmp_rc_detalle_tesoreria_facturas AS TMP
// 	ON (TMP.empresa_id = con_ff.empresa_id AND TMP.prefijo_factura = con_ff.prefijo 
// 	AND TMP.factura_fiscal=con_ff.factura_fiscal AND TMP.tmp_recibo_id =".$tmp_recibo_id."),
//        cartera_recaudo_electronico_tmp as cartera
//        
// 	WHERE con_ff.empresa_id = cartera.empresa_id
// 	".$dif." 
// 	AND con_ff.prefijo = cartera.prefijo 
// 	AND con_ff.factura_fiscal = cartera.numero
//         AND cartera.num_consecutivo=".$num_consecutivo."
// 	";
// 	
  $sql="SELECT 	
  (sum(con_ff.saldo_f)-sum(cartera.valor_n)) as total_pendientes
  
  
  FROM
		(          
			SELECT 	sum(con_ff.saldo) AS saldo_f,
              sum(cartera.valor_neto) AS valor_n,
							con_ff.prefijo,
							con_ff.empresa_id,
							con_ff.factura_fiscal
			FROM  	cartera_recaudo_electronico_tmp cartera,
							fac_facturas as con_ff,
              envios_detalle ED,
			        envios EN 
			WHERE 	cartera.num_consecutivo=$num_consecutivo
			AND     cartera.mensaje_error IS NULL
      AND   	con_ff.empresa_id = cartera.empresa_id
			AND  		con_ff.prefijo = cartera.prefijo 
			AND   	con_ff.factura_fiscal = cartera.numero
			AND 		con_ff.empresa_id = '$empresa_id'
			AND			con_ff.estado = '0'::bpchar
			AND			con_ff.sw_clase_factura = '1'::bpchar
      AND     con_ff.saldo !=0 
      AND     con_ff.tipo_id_tercero ='".$tipo_id_tercero."' 
      AND     con_ff.tercero_id ='".$tercero_id."'
      AND     ED.prefijo = con_ff.prefijo  
      AND     ED.factura_fiscal = con_ff.factura_fiscal
      AND     ED.empresa_id = con_ff.empresa_id
      AND     ED.envio_id = EN.envio_id
      AND     EN.fecha_radicacion IS NOT NULL
      $dif
      GROUP BY 2,3,4
			UNION ALL
			SELECT 	sum(con_ff.saldo) AS saldo_f,
              sum(cartera.valor_neto) AS valor_n,
							con_ff.prefijo,
							con_ff.empresa_id,
							con_ff.factura_fiscal
			FROM    cartera_recaudo_electronico_tmp cartera,
						  facturas_externas as con_ff
			WHERE cartera.num_consecutivo=$num_consecutivo
			AND   cartera.mensaje_error IS NULL
      AND   con_ff.empresa_id = cartera.empresa_id
			AND   con_ff.prefijo = cartera.prefijo 
			AND   con_ff.factura_fiscal = cartera.numero
			AND		con_ff.empresa_id = '$empresa_id'
			AND		con_ff.estado = '0'::bpchar
      AND     con_ff.saldo !=0 
      AND     con_ff.tipo_id_tercero ='".$tipo_id_tercero."' 
      AND     con_ff.tercero_id ='".$tercero_id."'
      $dif
      GROUP BY 2,3,4
		) AS cartera
		LEFT JOIN tmp_rc_detalle_tesoreria_facturas AS TMP
		ON ( TMP.empresa_id = cartera.empresa_id 
       AND TMP.prefijo_factura = cartera.prefijo 
	     AND TMP.factura_fiscal = cartera.factura_fiscal 
       AND TMP.tmp_recibo_id = $tmp_recibo_id
       AND TMP.empresa_id = '$empresa_id')";
       
       if(!$resultado = $this->ConexionBaseDatos($sql))
  	return $sql;    
	//return false;    
  	$cuentas=array();
  	while(!$resultado->EOF)
        {
          $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
          $resultado->MoveNext();
        }
      
        $resultado->Close();
        //return $sql;
        return $cuentas;
}








/*****************************************************************************************
*obtener recaudos del archivo plano
*****************************************************************************************/    
function Obtener_Recaudo_archivo_plano($empresa_id,$tipo_id_tercero,$tercero_id,$num_consecutivo) 
    {   
  
  
  $sql="SELECT 	
	num_consecutivo,
	fecha,
	tipo_id_tercero,
	tercero_id,
	prefijo,
	numero,
	valor_neto
	FROM
	
        cartera_recaudo_electronico_tmp 
       
	WHERE empresa_id = '".$empresa_id."'
	AND num_consecutivo=".$num_consecutivo."
	AND tipo_id_tercero = '".$tipo_id_tercero."' 
	AND tercero_id = '".$tercero_id."' 
	";
        
       if(!$resultado = $this->ConexionBaseDatos($sql))
  	return false;    
  	$cuentas=array();
  	while(!$resultado->EOF)
        {
          $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
          $resultado->MoveNext();
        }
      
        $resultado->Close();
        //return $sql;
        return $cuentas;
}


function Obtener_Recaudo_archivo_plano_List($empresa_id,$tipo_id_tercero,$tercero_id) 
    {   
  
  
       $sql="SELECT 	
	num_consecutivo,
	fecha,
	tipo_id_tercero,
	tercero_id,
	count(*),
	sum(valor_neto)
	FROM
	
        cartera_recaudo_electronico_tmp 
       
	WHERE empresa_id = '".$empresa_id."'
	AND tipo_id_tercero = '".$tipo_id_tercero."' 
	AND tercero_id = '".$tercero_id."' 
	GROUP BY 1,2,3,4 ORDER BY 1";
        //return $sql;    
       if(!$resultado = $this->ConexionBaseDatos($sql))
  	return false;    
  	$cuentas=array();
  	while(!$resultado->EOF)
        {
          $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
          $resultado->MoveNext();
        }
      
        $resultado->Close();
        //return $sql;
        return $cuentas;
}

/**************************************************************************
*saca el nombre de un tercero
****************************************************************************/
function Nombres($tipo_id,$tercero_id)
{
 $sql=" select *
        from terceros
        where tercero_id='".trim($tercero_id)."'
        and tipo_id_tercero='".$tipo_id."'"; 
//           echo "<pre>";print_r($sql);  
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
}

/*******************************************************************************************
*saca la razon social de una empresa
****************************************************************************************/

    function  ColocarEmpresa($empresa)
    { 
       $sql=" select razon_social 
       from empresas
       where empresa_id = '".strtoupper($empresa)."'"; 
             
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
     }

     
/******************************************************************************************
*
*******************************************************************************************/     
function GuardarTmp($empresa_id,$centro_utilidad,$recibo_caja,$datos)
{

	for($i=0;$i<count($datos);$i++)
	{
		list($prefijo_factura,$factura_fiscal,$valor_abonado) = explode("@", $datos[$i]);  
		
		$sql .= "INSERT INTO tmp_rc_detalle_tesoreria_facturas( ";
		$sql .= "			tmp_rc_id,";
		$sql .= "			empresa_id ,";
		$sql .= "			centro_utilidad ,";	
		$sql .= "			tmp_recibo_id ,";
		$sql .= "			prefijo_factura ,";
		$sql .= "			factura_fiscal ,";	
		$sql .= "			valor_abonado ) ";
		$sql .= "VALUES(";
		$sql .= "	   		(SELECT COALESCE(MAX(tmp_rc_id),0)+1 FROM tmp_rc_detalle_tesoreria_facturas), ";				
		$sql .= "				'".$empresa_id."' , ";
		$sql .= "				'".$centro_utilidad."' ,";
		$sql .= "		 		 ".$recibo_caja." ,";
		$sql .= "				'".$prefijo_factura."' ,";
		$sql .= "		 		 ".$factura_fiscal." ,";
		$sql .= "		 		 ".$valor_abonado."); ";
	//return $sql;
	     if(!$resultado = $this->ConexionBaseDatos($sql))
		{
		$cad="Operacion Invalida al cruzar facturas";
		return $cad;
		} 
		
	$sql="";
	
	
	}
	if($i==count($datos))
        {
	   $cad="Facturas Cruzadas Correctamente";  
	   return $cad;
	}
}
 
/********************************************************************************************
* GUARDAR CONCEPTOS TMP
*****************************************************************************************/
function GuardarConceptosTmp($datos)
{


  for($i=0;$i<count($datos);$i++)
  {
 
    list($empresa_id,$centro_utilidad,
    $concepto_id,$naturaleza,$valor,$tmp_recibo_id) = explode("@", $datos[$i]);
    $sql .= "INSERT INTO tmp_rc_detalle_tesoreria_conceptos( ";
    $sql .= "     tmp_rc_id,";
    $sql .= "     empresa_id ,";
    $sql .= "     centro_utilidad ,"; 
    $sql .= "     concepto_id ,";
    $sql .= "     naturaleza ,";
    $sql .= "     valor ,";
    $sql .= "     tmp_recibo_id)";
    
    $sql .= "VALUES(";
    $sql .= "       (SELECT COALESCE(MAX(tmp_rc_id),0)+1 FROM tmp_rc_detalle_tesoreria_conceptos), ";        
    $sql .= "       '".$empresa_id."' , ";
    $sql .= "       '".$centro_utilidad."' ,";
    $sql .= "       '".$concepto_id."' ,";
    $sql .= "       '".$naturaleza."' ,";
    $sql .= "       ".$valor." ,";
    $sql .= "        ".$tmp_recibo_id."); ";

   }

     
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida";
          return $cad;
        } 
        else 
         {
           $cad="Conceptos Cruzados Correctamente";
           return $cad;
         }   

}
/************************************************************************************
*Funcion borrara facturas canceladas
*************************************************************************************/    
    function EliminarFacturaTmp($datos)
    { 
      for($i=0;$i<count($datos);$i++)
      {
          
	  $sql.="delete from tmp_rc_detalle_tesoreria_facturas
          where tmp_rc_id=".$datos[$i].";";
      
      } 
        //RETURN $sql; 
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida al borrar datos";
          return $cad;
        } 
        else 
         {
           $cad="Factura Eliminada Correctamente";  
           return $cad;
         }   
    }



/***************************************************************************************
* obtener los numros consecutivos
*****************************************************************************************/
function Consecutivos($tercero_id)
{
 $sql="SELECT DISTINCT num_consecutivo
       FROM cartera_recaudo_electronico_tmp
       WHERE tercero_id='".$tercero_id."'";

        if(!$resultado = $this->ConexionBaseDatos($sql))
        //return $sql;
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;




}

  function EliminarConceptosTmp($datos)
    { 
      for($i=0;$i<count($datos);$i++)
      {
          
    $sql.="delete from tmp_rc_detalle_tesoreria_conceptos
          where tmp_rc_id=".$datos[$i].";";
      
      } 
        //RETURN $sql; 
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida al borrar datos";
          return $cad;
        } 
        else 
         {
           $cad="Conceptos Eliminados Correctamente";
           return $cad;
         }   
    }

/******************************************************************************************
* Obtener listado de conceptos agrupados por facturas
*********************************************************************************************/
function ObtenerListConceptos($num_consecutivo,$tercero_id,$tmp_recibo_id)
{
 $k=0;
 for($i=0;$i<count($num_consecutivo);$i++)
 { if($i==0)
    {
       $consecutivos="num_consecutivo=".$num_consecutivo[$i]['num_consecutivo']." "; 
    }
    
    if($i>$k)
    {
     $consecutivos.="OR ";
     $consecutivos.="num_consecutivo=".$num_consecutivo[$i]['num_consecutivo']." ";
    }
    $k=$i;
 }
 


         $sql="SELECT
                X.num_consecutivo,
                X.concepto,sum(X.valor) as valor,
                COALESCE(TMP.tmp_rc_id,-1) as tmp_id
                FROM
                (
                (SELECT num_consecutivo,c1 as concepto, sum(v1)as valor
                FROM cartera_recaudo_electronico_tmp
                WHERE ".$consecutivos."
                AND tercero_id='".$tercero_id."'
                GROUP BY num_consecutivo,c1)
                
                UNION 
                
                (SELECT num_consecutivo,c2 as concepto, sum(v2)as valor
                FROM cartera_recaudo_electronico_tmp
                WHERE ".$consecutivos."
                AND tercero_id='".$tercero_id."'
                GROUP BY num_consecutivo,c2)
                
                UNION
                
                (SELECT num_consecutivo,c5 as concepto, sum(v5)as valor
                FROM cartera_recaudo_electronico_tmp
                WHERE ".$consecutivos."
                AND tercero_id='".$tercero_id."'
                GROUP BY num_consecutivo,c5)
                
                UNION
                
                (SELECT num_consecutivo,c3 as concepto, sum(v3)as valor
                FROM cartera_recaudo_electronico_tmp
                WHERE ".$consecutivos."
                AND tercero_id='".$tercero_id."'
                GROUP BY num_consecutivo,c3)
                
                UNION
                
                (SELECT num_consecutivo,c4 as concepto, sum(v4)as valor
                FROM cartera_recaudo_electronico_tmp
                WHERE ".$consecutivos."
                AND tercero_id='".$tercero_id."'
                GROUP BY num_consecutivo,c4)
                ) AS X

                LEFT JOIN tmp_rc_detalle_tesoreria_conceptos AS TMP
                ON (
                
                TMP.concepto_id=X.concepto
                AND TMP.valor=X.valor
                AND TMP.tmp_recibo_id =".$tmp_recibo_id."
                )
                WHERE X.concepto IS NOT NULL
                GROUP BY 1,2,4 ORDER BY 1;";

  
    
 //RETURN $sql;
 if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
}


function SacarConceptos($concepto)
{
  $sql="SELECT *
        from rc_conceptos_tesoreria
        where  concepto_id='".$concepto."'";

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
     
/**************************************************************************************************
*FUNCQION Q SIRVE PARA ACTUALIZAR PARAMETROS:CAPITACION_APROVECHAMIENTO  
***************************************************************************************************/
//     //(      $empresa,   $cuenta,$naturaleza,       $parametro,""); 
//     function UpParametros_TIPOC($empresa_id,$cuenta,$cuenta_naturaleza,$parametro,$comentario)
//     { 
//     
//       $sql="Update cg_conf.doc_parametros
//             SET cuenta=".$cuenta.",
//             cuenta_naturaleza='".$cuenta_naturaleza."',
//             argumentos='".$argumentos."',
//             comentario='".$comentario."'
//             where empresa_id='".$empresa_id."' and
//             tipo_doc_general_id='FV01' and
//             parametro='".$parametro."'";
//       
//       
//       if(!$rst = $this->ConexionBaseDatos($sql)) 
//        {  $cad="no se hizo la inserci�";
//           return $cad;
//        }
//       else
//        {      
//          $cad="Parametro Actualizado Satisfactoriamente";
//          $rst->Close();
//          return $cad;
//        }
//     
//     }    
//  
 
           

   /********************************************************************************
    * Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
    * importantes a la hora de referenciar al paginador
    * 
    * @param String Cadena que contiene la consulta sql del conteo 
    * @param int numero que define el limite de datos,cuando no se desa el del 
    *        usuario,si no se pasa se tomara por defecto el del usuario 
    * @return boolean 
    *********************************************************************************/
    function ProcesarSqlConteo($consulta,$limite=null,$offset=null)
    { 
      $this->offset = 0;
      $this->paginaActual = 1;
      if($limite == null)
      {
        $this->limit = GetLimitBrowser();
      }
      else
      {
        $this->limit = $limite;
      }
      
      if($offset)
      {
        $this->paginaActual = intval($offset);
        if($this->paginaActual > 1)
        {
          $this->offset = ($this->paginaActual - 1) * ($this->limit);
        }
      }   

      if(!$result = $this->ConexionBaseDatos($consulta))
        return false;

      if(!$result->EOF)
      {
        $this->conteo = $result->fields[0];
        $result->MoveNext();
      }
      $result->Close();
      
      
      return true;
    }

 
    /**********************************************************************************
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la 
    * consulta sql 
    * 
    * @param  string  $sql  sentencia sql a ejecutar $empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_est,$sw_cc,$sw_dc
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
         "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
        return false;
      }
      return $rst;
    }
    /**********************************************************************************
    * Funcion que permite crear una transaccion 
    * @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
    * @param char $num Numero correspondiente a la sentecia sql - por defect es 1
    *
    * @return object Objeto de la transaccion - Al momento de iniciar la transaccion no 
    *                se devuelve nada
    ***********************************************************************************/

  }
?>