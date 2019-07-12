<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: CuentasXPagar.class.php,v 1.3 2008/10/23 22:09:23 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : CuentsXPagar
  * Clase en la que se maneja la logica de cuentas por pagar
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class CuentasXPagar extends ConexionBD
  {
    /**
    * Contructor de la clase
    */
    function CuentasXPagar(){}
    /**
    * Retorna el error generado en cualquiera de los proceso realizados por esta clase
    *
    * @return String
    */
    function ObtenerError()
    {
      return $this->error;
    }
    /**
    * Funcion donde se verifica el permiso del usuario para el ingreso
    * al modulo
    *
    * @return mixed
    */
    function ObtenerPermisos()
    {
      $sql  = "SELECT	EM.empresa_id AS empresa, ";
			$sql .= "				EM.razon_social AS razon_social ";
			$sql .= "FROM	  userpermisos_cxp CP,";
      $sql .= "       empresas EM ";
			$sql .= "WHERE	CP.usuario_id = ".UserGetUID()." ";
			$sql .= "AND    CP.empresa_id = EM.empresa_id ";
			
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
		}
    /**
    * Funcion donde se obtiene los tipos de cuentas
    *
    * @return mixed 
    */
    function ObtenerTiposCuentas()
    {
      $sql  = "SELECT tipo_cxp,";
      $sql .= "       tipo_cxp_descripcion ";
      $sql .= "FROM   cxp_tipos ";
      $sql .= "WHERE  sw_activo = '1' ";
      $sql .= "ORDER BY tipo_cxp_descripcion "; 
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
      $datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
    }
    /**
    * Funcion donde se obtiene los medios de pago
    *
    * @return mixed 
    */
    function ObtenerMediosDePago()
    {
      $sql  = "SELECT cxp_medio_pago_id,";
      $sql .= "       descripcion_medio_pago ";
      $sql .= "FROM   cxp_medios_pagos ";
      $sql .= "WHERE  sw_mostrar = '1' ";
      $sql .= "ORDER BY descripcion_medio_pago "; 
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
      $datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
    }
    /**
    * Funcion domde se seleccionan los tipos de idenrtificacion de los terceros
    *
    * @return mixed
    */
    function ObtenerTiposIdentificacion()
    {
      $sql  = "SELECT tipo_id_paciente,";
      $sql .= "       descripcion ";
      $sql .= "FROM   tipos_id_pacientes ";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();

      while (!$rst->EOF)
      {
          $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
    /**
    * Funcion en la que se obtienen datos del afiliado
    *
    * @param string $tipo_documento_id Tipo de documento del afiliado
    * @param string $documento_id Numero de documento del afiliado
    *
    * @return mixed
    */
    function ObtenerAfiliado($tipo_documento_id,$documento_id)
    {
      $sql  = "SELECT afiliado_tipo_id   , ";
      $sql .= "       afiliado_id ";
      $sql .= "FROM   eps_afiliados_datos ";
      $sql .= "WHERE  afiliado_tipo_id = '".$tipo_documento_id."' ";
      $sql .= "AND    afiliado_id = '".$documento_id."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      if (!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /** 
		* Funcion donde se obtienen los auditores medicos
		* 
		* @return mixed
		*/
		function ObtenerAuditoresMedicos()
		{
			$sql  = "SELECT U.usuario_id,";
      $sql .= "       U.nombre ";
			$sql .= "FROM 	system_usuarios U,";
      $sql .= "       cxp_auditores_medicos A ";
			$sql .= "WHERE 	U.usuario_id = A.usuario_id ";
			$sql .= "AND 		A.sw_estado = '1' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos;
		}
    /** 
		* Funcion donde se obtienen los auditores administrativos
		* 
		* @return mixed 
		*/
		function ObtenerAuditoresAdministrativos()
		{
			$sql  = "SELECT U.usuario_id,";
      $sql .= "       U.nombre ";
			$sql .= "FROM 	system_usuarios U,";
      $sql .= "       cxp_auditores_administrativos A ";
			$sql .= "WHERE 	U.usuario_id = A.usuario_id ";
			$sql .= "AND 		A.sw_estado = '1' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos;
		}
    /**
    * Funcion para obtener los proveedores
    *
    * @param integer $proveedor_id Identificador del proveedor
    *
    * @return array
    */
    function ObtenerProveedores($proveedor_id = null)
    {
      $sql .= "SELECT  TP.codigo_proveedor_id, ";
      $sql .= "        TE.nombre_tercero, ";
      $sql .= "        TE.telefono,  ";
      $sql .= "        TE.direccion,  ";
      $sql .= "        TE.tipo_id_tercero,";
      $sql .= "        TE.tercero_id ";
      $sql .= "FROM    terceros TE, ";
      $sql .= "        terceros_proveedores TP ";
      $sql .= "WHERE   TE.tercero_id = TP.tercero_id ";
      $sql .= "AND     TE.tipo_id_tercero = TP.tipo_id_tercero ";
      $sql .= "AND     TP.estado = '1' ";
      if($proveedor_id)
        $sql .= "AND    TP.codigo_proveedor_id = ".$proveedor_id." ";
      
      $sql .= "ORDER BY TE.nombre_tercero ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
      
      $datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos;
    }
    /**
    * Funcion donde se obtienen los planes contratados con los proveedores
    * 
    * @param array $datos Vector con los datos de los filtros
    *
    * @return mixed
    */
    function ObtenerPlanes($datos)
    {
      $sql  = "SELECT PP.num_contrato,";
      $sql .= "       PP.plan_descripcion ";
      $sql .= "FROM   planes_proveedores PP, ";
      $sql .= "       terceros_proveedores TP ";
      $sql .= "WHERE  TP.codigo_proveedor_id = ".$datos['proveedor']." ";
      $sql .= "AND    TP.tercero_id = PP.tercero_id ";
      $sql .= "AND    TP.tipo_id_tercero = PP.tipo_id_tercero ";
      $sql .= "ORDER BY PP.plan_descripcion ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
      
      $datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos;
    }
    /**
    * Funcion donde se obtiene el valor de la cuenta por pagar por tercero
    *
    * @param String $empresa Identificador de la empresa
    *
    * @return mixed
    */
    function ObtenerCxP($empresa)
    {
      $sql  = "SELECT SUM(CF.saldo) AS saldo,";
      $sql .= "       (CF.fecha_vencimiento::date - now()::date) / 30 AS intervalo,";
      $sql .= "       TE.nombre_tercero, ";
      $sql .= "       TE.tipo_id_tercero||' '||TE.tercero_id      AS identificacion ";
      $sql .= "FROM   cxp_facturas CF, ";
      $sql .= "       terceros TE ";
      $sql .= "WHERE  CF.tercero_id = TE.tercero_id ";
      $sql .= "AND    CF.tipo_id_tercero = TE.tipo_id_tercero ";
      $sql .= "AND    CF.empresa_id = '".$empresa."' ";
      $sql .= "GROUP BY TE.nombre_tercero,identificacion,intervalo ";
      $sql .= "ORDER BY TE.nombre_tercero,intervalo ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
      $intv = "";
      $nombre = "";
      $cliente = "";
      $saldototal = 0;
      $cxp_cliente = array();
      $intervalos = array();
        
      $indice = 0;
      $datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
        ($datos['intervalo'] < 0)? $intvl = intval($datos['intervalo']) * (-1): $intvl = 0;
        
        if($cliente != $datos['nombre_tercero']."<br>".$datos['identificacion'])
				{
					$nombre = $datos['nombre_tercero']."<br>".$datos['identificacion'];
					$saldo = 0;
					$periodos = array();
					$cxp_cliente[$nombre] = $datos;
				}
				
        $cliente = $datos['nombre_tercero']."<br>".$datos['identificacion'];
       
        switch($intvl)
        {
          case 0:
            $indice = 0;
            $intervalos[0] = "CORRIENTE";
          break;
          case 1:
            $indice = 1;
            $intervalos[1] = "0 A 30";
          break;
          case 2:
            $indice = 2;
            $intervalos[2] = "31 a 60"; 	
          break;
          case 3:
            $indice = 3;
            $intervalos[3] = "61 a 90";	
          break;
          case 4:
            $indice = 4;
            $intervalos[4] = "91 a 120"; 	
          break;
          case 5:
            $indice = 5;
            $intervalos[5] = "121 a 150"; 	
          break;
          case 6:
            $indice = 6;
            $intervalos[6] = "151 a 180";	
          break;
          case 7:
          case 8:
          case 9:
          case 10:
          case 11:
          case 12:
            $indice = 7;
            $intervalos[7] = "181 a 360";   	
          break;
          default:
            $indice = 8;
            $intervalos[8] = "Mas de 360";
          break;
        }
        
				$saldo += $datos['saldo'];
        $periodos[$indice]['saldo'] += $datos['saldo'];
        
        $rst->MoveNext();

 				$datos = $rst->GetRowAssoc($ToUpper = false);
        
        if($cliente != $datos['nombre_tercero']."<br>".$datos['identificacion'])
				{
					if($saldo == 0)
					{
						unset($cxp_cliente[$nombre]);
					}
					else
					{
						$cxp_cliente[$nombre]['saldo'] = $saldo;
						$cxp_cliente[$nombre]['periodos'] = $periodos;
						$saldototal +=  $saldo;
						$saldo = 0;
					}          
				}
		  }
			$rst->Close();
      
	 		ksort($intervalos);
      
      $datos = array();
      $datos['total'] = $saldototal;
      $datos['intervalos'] = $intervalos;
      $datos['cxp_cliente'] = $cxp_cliente;
     
	 		return $datos;
    }
    /**
    * Funcion donde se obtienen los terceros que poseen cuentas por pagar
    *
    * @param String $empresa Identificador de la empresa
    * @param array $filtro Arreglo con los datos de los filtros de busquedad
    * @param string $tipoauditor identificador del tipo de auditor
    * @param integer $op Indica si se debe hacer o no un conteo (1-> Si, 0-> No)
    *
    * @return mixed
    */
    function ObtenerTercerosCxP($empresa,$filtro,$tipoauditor,$op=1)
    { 
      $sql  = "SELECT DISTINCT TE.nombre_tercero, ";
      $sql .= "       TE.telefono,  ";
      $sql .= "       TE.direccion,  ";
      $sql .= "       TE.tipo_id_tercero,";
      $sql .= "       TE.tercero_id ";
      $sql .= "FROM   cxp_facturas CF, ";
      $sql .= "       cxp_auditores_facturas CA, ";
      $sql .= "       terceros TE ";
      $sql .= "WHERE  CF.tercero_id = TE.tercero_id ";
      $sql .= "AND    CF.tipo_id_tercero = TE.tipo_id_tercero ";
      $sql .= "AND    CF.empresa_id = '".$empresa."' ";
      $sql .= "AND    CA.cxp_radicacion_id = CF.cxp_radicacion_id ";
      /*$sql .= "AND    CA.prefijo = CF.prefijo ";
      $sql .= "AND    CA.numero = CF.numero ";*/

      if($tipoauditor == 1)
      {
        $sql .= "AND    CA.cxp_auditor_administrativo = ".UserGetUID()." ";
        $sql .= "AND    ((CF.cxp_estado = 'R' AND CA.cxp_auditor_medico IS NULL) OR CF.cxp_estado IN ('RA','RP')) ";
      }
      else if ($tipoauditor == 2)
      {
        $sql .= "AND    CA.cxp_auditor_medico = ".UserGetUID()." ";
        $sql .= "AND    CF.cxp_estado IN ('R','RM') ";
      }
      
      if($filtro['tipo_id_tercero'] && $filtro['tipo_id_tercero']!= '-1')
        $sql .= "AND     TE.tipo_id_tercero = '".$filtro['tipo_id_tercero']."'  ";
      
      if($filtro['tercero_id'])
        $sql .= "AND     TE.tercero_id = '".$filtro['tercero_id']."' ";
      
      if($filtro['nombre'] != "")
        $sql .= "AND     TE.nombre_tercero ILIKE '%".$filtro['nombre']."%' ";
      
      if($op == 1)
      {
        $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
        $this->ProcesarSqlConteo($cont,$filtro['offset']);
      
        $sql .= "ORDER BY TE.nombre_tercero ";
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      }
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos;
    }
    /**
		* Funcion domde se obtienen los tipos de identificacion de terceros 
		* 
		* @return mixed 
		*/
		function ObtenerTipoIdTerceros()
		{
			$sql  = "SELECT tipo_id_tercero, ";
      $sql .= "       descripcion ";
      $sql .= "FROM   tipo_id_terceros ";
      $sql .= "ORDER BY descripcion ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;

      $datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
    /**
    * Funcion donde se obtienen la facturas de los terceros que poseen 
    * cuentas por pagar
    *
    * @param String $empresa Identificador de la empresa
    * @param array $datos Arreglo con los datos de los filtros de busquedad
    * @param String $tipoauditor Identificador del tipo de auditor
    * @param integer $op Indica si se debe hacer o no un conteo (1-> Si, 0-> No)
    *
    * @return array
    */
    function ObtenerFacturasTercerosCxP($empresa,$datos,$tipoauditor,$op = 1)
    { 
      $sql  = "SELECT CF.prefijo_factura,";
      $sql .= "       CF.numero_factura, ";
      $sql .= "       CF.prefijo, ";
      $sql .= "       CF.numero, ";
      $sql .= "       CF.empresa_id, ";
      $sql .= "       CF.cxp_radicacion_id, ";
      $sql .= "       TO_CHAR(CF.fecha_documento,'DD/MM/YYYY') AS fecha_documento, ";
      $sql .= "       TO_CHAR(CF.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
      $sql .= "       TO_CHAR(CF.fecha_vencimiento,'DD/MM/YYYY') AS fecha_vencimiento, ";
      $sql .= "       TO_CHAR(CR.fecha_radicacion,'DD/MM/YYYY') AS fecha_radicacion, ";
      $sql .= "       CF.valor_total 	, ";
      $sql .= "       CF.valor_iva 	, ";
      $sql .= "       CF.sw_rips, ";
      $sql .= "       CF.numero_contrato, ";
      $sql .= "       TP.codigo_proveedor_id, ";
      $sql .= "       CT.tipo_cxp_descripcion ";
      $sql .= "FROM   cxp_facturas CF, ";
      $sql .= "       cxp_auditores_facturas CA, ";
      $sql .= "       cxp_tipos CT, ";
      $sql .= "       cxp_radicacion CR, ";
      $sql .= "       terceros_proveedores TP ";
      $sql .= "WHERE  CF.tipo_cxp = CT.tipo_cxp ";
      $sql .= "AND    CF.empresa_id = '".$empresa."' ";  
      $sql .= "AND    CF.tercero_id = TP.tercero_id ";
      $sql .= "AND    CF.tipo_id_tercero = TP.tipo_id_tercero ";
      $sql .= "AND    CA.cxp_radicacion_id = CF.cxp_radicacion_id ";
      $sql .= "AND    CR.cxp_radicacion_id = CF.cxp_radicacion_id ";
      /*$sql .= "AND    CA.prefijo = CF.prefijo ";
      $sql .= "AND    CA.numero = CF.numero ";
      */
      if($tipoauditor == 1)
      {
        $sql .= "AND    CA.cxp_auditor_administrativo = ".UserGetUID()." ";
        $sql .= "AND    ((CF.cxp_estado = 'R' AND CA.cxp_auditor_medico IS NULL) OR CF.cxp_estado IN ('RA','RP')) ";
      }
      else if ($tipoauditor == 2)
      {
        $sql .= "AND    CA.cxp_auditor_medico = ".UserGetUID()." ";
        $sql .= "AND    CF.cxp_estado IN ('R','RM') ";
      }
      if($datos['buscador']['prefijo'])
        $sql .= "AND   CF.prefijo_factura ILIKE '".$datos['buscador']['prefijo']."'";
      
      if($datos['buscador']['factura'])
        $sql .= "AND   CF.numero_factura = ".$datos['buscador']['factura']." ";
      
      if($datos['buscador']['radicacion'])
        $sql .= "AND   CF.cxp_radicacion_id = ".$datos['buscador']['radicacion']." ";
      
      if($datos['prefijo'] && $datos['numero'])
      {
        $sql .= "AND   CF.prefijo = '".$datos['prefijo']."'";
        $sql .= "AND   CF.numero = ".$datos['numero']." ";
      }
      
      if($datos['buscador']['fecha_inicio'])
        $sql .= "AND   CR.fecha_radicacion >= '".$this->DividirFecha($datos['buscador']['fecha_inicio'])."'::date ";
      
      if($datos['buscador']['fecha_fin'])
        $sql .= "AND   CR.fecha_radicacion <= '".$this->DividirFecha($datos['buscador']['fecha_fin'])."'::date ";

      
      if($datos['tercero_id'])
      {
        $sql .= "AND    CF.tercero_id = '".$datos['tercero_id']."' ";
        $sql .= "AND    CF.tipo_id_tercero = '".$datos['tipo_id_tercero']."' ";    
      }
      
      if($op == 1)
      {
        $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
        $this->ProcesarSqlConteo($cont,$off);
      
        $sql .= "ORDER BY CF.prefijo_factura, CF.numero_factura ";
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      }
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $retorno;
    }
    /**
		* Funcion donde se obtienen la forma de validacion del detalle de los
    * rips creados para un proveedor
		* 
    * @param integer $proveedor Codigo del proveedor
    * 
		* @return mixed 
		*/
		function ObtenerValidacionRips($proveedor)
		{
			$sql  = "SELECT sw_validacion ";
      $sql .= "FROM   cxp_validacion_rips ";
      $sql .= "WHERE  codigo_proveedor_id = ".$proveedor." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;

      $datos = array();
			if (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['sw_validacion'];
		}
    /**
		* Funcion donde se ingresa el tipo de validacion para el proveedor
		* 
    * @param integer $proveedor Codigo del proveedor
    * @param string $valor Nuevo valor del tipo de validacion
    * 
		* @return mixed 
		*/
		function IngresarValidacionRips($proveedor,$valor)
		{
      $sql  = "INSERT INTO cxp_validacion_rips (codigo_proveedor_id,sw_validacion) ";
      $sql .= "VALUES (".$proveedor.",'".$valor."'); ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
			return true;
		}    
    /**
		* Funcion donde se actualiza el tipo de validacion para el proveedor
		* 
    * @param int $proveedor Codigo del proveedor
    * @param string $valor Nuevo valor del tipo de validacion
    * 
		* @return mixed 
		*/
		function ActualizarValidacionRips($proveedor,$valor)
		{
      $sql  = "UPDATE cxp_validacion_rips ";
      $sql .= "SET    sw_validacion = '".$valor."' ";
      $sql .= "WHERE  codigo_proveedor_id = ".$proveedor." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
			return true;
		}    
    /**
		* Funcion donde se obtienen los detalles de otros servicios asociados 
    * a las facturas
		* 
    * @param array $datos Arreglo de datos con la informacion de la factura
    * @param string $empresa Identificador de la empresa
    *
		* @return mixed 
		*/
		function ObtenerOtrosServiciosFactura($datos,$empresa)
		{
      $sql  = "SELECT CD.cxp_detalle_factura_id , ";
      $sql .= " 	    CD.prefijo ,";
      $sql .= " 	    CD.numero ,";
      $sql .= " 	    CD.cx_tipo_cargo_id ,";
      $sql .= " 	    CD.referencia ,";
      $sql .= " 	    CD.descripcion ,";
      $sql .= " 	    CD.cantidad ,";
      $sql .= " 	    CD.porcentaje_gravamen ,";
      $sql .= " 	    CD.valor_gravamen ,";
      $sql .= " 	    CD.valor_unitario ,";
      $sql .= " 	    CD.valor_total ,";
      $sql .= " 	    CD.sw_objetado, ";
      $sql .= " 	    CD.autorizacion ";
      $sql .= "FROM   cxp_detalle_facturas CD ";
      $sql .= "WHERE  CD.empresa_id = '".$empresa."' ";
      $sql .= "AND 	  CD.prefijo = '".$datos['prefijo']."' ";
      $sql .= "AND 	  CD.numero = ".$datos['numero']." ";
			$sql .= "AND 	  CD.cx_tipo_cargo_id  = 'OT' ";
      
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
      
			return true;
		}
    /**
		* Funcion donde se obtienen los medicamentos del detalle de la factura
		* 
    * @param array $datos Arreglo de datos con la informacion de la factura
    * @param string $empresa Identificador de la empresa
    *
		* @return mixed 
		*/
		function ObtenerMedicamentosFactura($datos,$empresa)
		{
      $sql  = "SELECT CD.cxp_detalle_factura_id , ";
      $sql .= " 	    CD.prefijo ,";
      $sql .= " 	    CD.numero ,";
      $sql .= " 	    CD.cx_tipo_cargo_id ,";
      $sql .= " 	    CD.referencia ,";
      $sql .= " 	    CD.descripcion ,";
      $sql .= " 	    CD.cantidad ,";
      $sql .= " 	    CD.porcentaje_gravamen ,";
      $sql .= " 	    CD.valor_gravamen ,";
      $sql .= " 	    CD.valor_unitario ,";
      $sql .= " 	    CD.valor_total ,";
      $sql .= " 	    CO.valor ,";
      $sql .= " 	    CD.sw_objetado, ";
      $sql .= " 	    CD.autorizacion ";
      $sql .= "FROM   cxp_detalle_facturas CD ";
      $sql .= "       LEFT JOIN cxp_detalle_facturas_medicamentos_ordenes CO ";
      $sql .= "       ON( CO.cxp_detalle_factura_id = CD.cxp_detalle_factura_id )";
      $sql .= "WHERE  CD.empresa_id = '".$empresa."' ";
      $sql .= "AND 	  CD.prefijo = '".$datos['prefijo']."' ";
      $sql .= "AND 	  CD.numero = ".$datos['numero']." ";
			$sql .= "AND 	  CD.cx_tipo_cargo_id  = 'IM' ";
      
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
      
			return true;
		}
    /**
		* Funcion donde se obtienen los cargos del detalle de la factura
		* 
    * @param array $datos Arreglo de datos con la informacion de la factura
    * @param string $empresa Identificador de la empresa
    * @param string $validacion Tipo de validacion que se hara para obtener los cargos
    *
		* @return mixed 
		*/
		function ObtenerCargosFactura($datos,$empresa,$validacion)
		{
      $sql  = "SELECT CP.cxp_detalle_factura_id , ";
      $sql .= " 	    CP.prefijo ,";
      $sql .= " 	    CP.numero ,";
      $sql .= " 	    CP.referencia ,";
      $sql .= " 	    CP.cantidad ,";
      $sql .= " 	    CP.porcentaje_gravamen ,";
      $sql .= " 	    CP.valor_gravamen ,";
      $sql .= " 	    CP.valor_unitario ,";
      $sql .= " 	    CP.valor_total ,";
      $sql .= " 	    CP.valor AS valor_orden ,";
      $sql .= " 	    CP.autorizacion, ";
      $sql .= " 	    CA.descripcion, ";
      $sql .= " 	    CP.sw_objetado, ";
      $sql .= " 	    CA.valor ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  CD.cxp_detalle_factura_id , ";
      $sql .= " 	              CD.prefijo ,";
      $sql .= " 	              CD.numero ,";
      $sql .= " 	              CD.referencia ,";
      $sql .= " 	              CD.cantidad ,";
      $sql .= " 	              CD.porcentaje_gravamen ,";
      $sql .= " 	              CD.valor_gravamen ,";
      $sql .= " 	              CD.valor_unitario ,";
      $sql .= " 	              CD.valor_total ,";
      $sql .= " 	              CD.autorizacion, ";
      $sql .= " 	              CF.numero_contrato, ";
      $sql .= " 	              CD.sw_objetado, ";
      $sql .= " 	              CO.valor  ";
      $sql .= "         FROM    cxp_facturas CF ,  ";
      $sql .= "                 cxp_detalle_facturas CD ";
      $sql .= "                 LEFT JOIN cxp_detalle_facturas_cargos_ordenes CO ";
      $sql .= "                 ON( CO.cxp_detalle_factura_id = CD.cxp_detalle_factura_id )";
      $sql .= "         WHERE   CD.empresa_id = '".$empresa."' ";
      $sql .= "         AND 	  CD.prefijo = '".$datos['prefijo']."' ";
      $sql .= "         AND 	  CD.numero = ".$datos['numero']." ";
      $sql .= "         AND     CD.empresa_id = CF.empresa_id ";
      $sql .= "         AND 	  CD.prefijo = CF.prefijo ";
      $sql .= "         AND 	  CD.numero = CF.numero ";
      $sql .= "         AND 	  CD.cx_tipo_cargo_id  = 'CC' ";
			$sql .= "       ) AS CP ";
      $sql .= "       LEFT JOIN ";
      $sql .= "       ( SELECT LI.numero_contrato, ";
      $sql .= "                LD.valor, ";
      if($validacion == "T")
        $sql .= "               TD.cargo,";
      else if($validacion == "C")
        $sql .= "               CU.cargo,";
        
      $sql .= "                CU.descripcion ";
      $sql .= "         FROM   listas_precios_cargos_proveedores LI, ";
      $sql .= "                listas_precios_cargos_detalle LD, ";
      $sql .= "                tarifarios_detalle TD, ";
      $sql .= "                tarifarios_equivalencias TE, ";
      $sql .= "                cups CU ";
      $sql .= "         WHERE  LD.lista_codigo = LI.lista_codigo ";
      $sql .= "         AND    TD.tarifario_id = LD.tarifario_id ";
      $sql .= "         AND    TD.cargo = LD.cargo ";
      $sql .= "         AND    TD.tarifario_id = TE.tarifario_id ";
      $sql .= "         AND    TD.cargo = TE.cargo ";
      $sql .= "         AND    CU.cargo = TE.cargo_base ";
      $sql .= "         AND    LI.codigo_proveedor_id = ".$datos['codigo_proveedor']." 	 ";
      $sql .= "        ) AS CA ";
      $sql .= "        ON ( CA.cargo = CP.referencia ) ";
      
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
		}
    /**
    * Funcion donde se obtiene la informacion de los pacientes relacionados en la 
    * cuenta de cobro
    *
    * @param array $datos Arreglo de datos con la informacion de la factura
    *
    * @return mixed
    */
    function ObtenerPacientes($datos)
    {
      $sql  = "SELECT  CF.tipo_id_paciente ||' '|| CF.paciente_id AS identificacion, ";
      $sql .= "        PP.tipo_id_paciente, ";
      $sql .= "        PP.paciente_id, ";
      $sql .= "        PP.primer_apellido, ";
      $sql .= "        PP.segundo_apellido, ";
      $sql .= "        PP.primer_nombre, ";
      $sql .= "        PP.segundo_nombre,		 ";
      $sql .= "        PP.direccion_residencia, ";	 	
      $sql .= "        PP.telefono_residencia, ";
      $sql .= "        PP.tipo_pais_id , ";
      $sql .= "        PP.tipo_dpto_id , ";
      $sql .= "        PP.tipo_mpio_id	, ";
      $sql .= "        PP.departamento_municipio, ";
      $sql .= "        PP.tipo_persona ";
      $sql .= "FROM    cxp_pacientes_facturas CF "; 
      $sql .= "        LEFT JOIN  ";
      $sql .= "        ( SELECT  PA.tipo_id_paciente, ";
      $sql .= "                  PA.paciente_id, ";
      $sql .= "                  PA.primer_apellido, ";
      $sql .= "                  PA.segundo_apellido, ";
      $sql .= "                  PA.primer_nombre, ";
      $sql .= "                  PA.segundo_nombre, ";
      $sql .= "                  PA.direccion_residencia, ";	 	
      $sql .= "                  PA.telefono_residencia, ";
      $sql .= "                  PA.tipo_pais_id , ";
      $sql .= "                  PA.tipo_dpto_id , ";
      $sql .= "                  PA.tipo_mpio_id , ";
      $sql .= "                  PA.tipo_persona , ";
      $sql .= "                  TP.pais ||'-'||TD.departamento||'-'||TM.municipio AS departamento_municipio ";
      $sql .= "          FROM    ( ";
      $sql .= "                    SELECT DISTINCT afiliado_tipo_id  AS tipo_id_paciente ,  ";
      $sql .= "                            afiliado_id AS paciente_id,  ";
      $sql .= "                            primer_apellido ,  ";
      $sql .= "                            segundo_apellido , "; 
      $sql .= "                            primer_nombre  ,  ";
      $sql .= "                            segundo_nombre , "; 
      $sql .= "                            direccion_residencia , "; 
      $sql .= "                            telefono_residencia ,  ";
      $sql .= "                            tipo_pais_id , ";
      $sql .= "                            tipo_dpto_id , ";
      $sql .= "                            tipo_mpio_id , ";
      $sql .= "                            'A' as tipo_persona  ";
      $sql .= "                    FROM    eps_afiliados_datos   ";
      $sql .= "                    UNION ALL  ";
      $sql .= "                    SELECT  tipo_id_paciente , ";
      $sql .= "                            paciente_id , ";
      $sql .= "                            primer_apellido, ";
      $sql .= "                            segundo_apellido, ";
      $sql .= "                            primer_nombre, ";
      $sql .= "                            segundo_nombre, ";	
      $sql .= "                            residencia_direccion AS direccion_residencia ,	"; 	
      $sql .= "                            residencia_telefono	AS telefono_residencia, ";
      $sql .= "                            tipo_pais_id , ";
      $sql .= "                            tipo_dpto_id , ";
      $sql .= "                            tipo_mpio_id	, ";
      $sql .= "                            'E' as tipo_persona  ";
      $sql .= "                    FROM    interfaz_uv.bd_estudiantes  "; 
      $sql .= "                  ) AS PA, ";
      $sql .= "                  tipo_pais TP, ";
      $sql .= "                  tipo_dptos TD, ";
      $sql .= "                  tipo_mpios TM  ";
      $sql .= "          WHERE   PA.tipo_pais_id = TP.tipo_pais_id "; 
      $sql .= "          AND     PA.tipo_dpto_id = TD.tipo_dpto_id ";
      $sql .= "          AND     PA.tipo_pais_id = TD.tipo_pais_id ";
      $sql .= "          AND     PA.tipo_mpio_id = TM.tipo_mpio_id ";
      $sql .= "          AND     PA.tipo_pais_id = TM.tipo_pais_id "; 
      $sql .= "          AND     PA.tipo_dpto_id = TM.tipo_dpto_id "; 
      $sql .= "        ) AS PP  ";
      $sql .= "        ON ( CF.tipo_id_paciente = PP.tipo_id_paciente AND ";
      $sql .= "             CF.paciente_id = PP.paciente_id ) ";
      $sql .= "WHERE   CF.prefijo = '".$datos['prefijo']."' ";
      $sql .= "AND     CF.numero = ".$datos['numero']." ";
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
    }
    /**
    * Funcion donde segun el detalle de la factura se obtiene el 
    * numero de glosa relacionado con la factura a la que pertenece
    *
    * @param string $cxp_detalle_factura_id Identificador del detalle de la cuenta
    *
    * @return mixed
    */
    function ObtenerNumerosGlosa($cxp_detalle_factura_id)
    {
      $sql  = "SELECT  CF.prefijo, ";
      $sql .= "        CF.numero, ";
      $sql .= "        CF.empresa_id, ";
      $sql .= "        CG.cxp_glosa_id, ";
      $sql .= "        CL.cxp_detalle_factura_id ";
      $sql .= "FROM    cxp_detalle_facturas CF LEFT JOIN ";
      $sql .= "        cxp_glosas CG  ";
      $sql .= "        ON( CG.prefijo = CF.prefijo AND ";
      $sql .= "            CG.numero = CF.numero AND ";
      $sql .= "            CG.empresa_id = CF.empresa_id AND ";
      $sql .= "            CG.sw_estado = '1') ";
      $sql .= "        LEFT JOIN ";
      $sql .= "        cxp_glosas_detalles CL ";
      $sql .= "        ON( CL.cxp_glosa_id = CG.cxp_glosa_id AND ";
      $sql .= "            CL.cxp_detalle_factura_id = ".$cxp_detalle_factura_id." AND ";
      $sql .= "            CL.sw_estado = '1') ";
      $sql .= "WHERE  CF.cxp_detalle_factura_id = ".$cxp_detalle_factura_id."  ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			if (!$rst->EOF)
			{
				$retorno = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
    }    
    /**
    * Funcion donde se obtiene el numero de glosa relacionado con 
    * la factura a la que pertenece
    *
    * @param string $prefijo Prefijo del documento
    * @param string $numero Numero del documento
    * @param string $empresa Identificador de la empresa
    *
    * @return mixed
    */
    function ObtenerNumeroGlosa($prefijo,$numero,$empresa)
    {
      $sql  = "SELECT  CF.prefijo, ";
      $sql .= "        CF.numero, ";
      $sql .= "        CF.empresa_id, ";
      $sql .= "        CG.cxp_glosa_id ";
      $sql .= "FROM    cxp_facturas CF LEFT JOIN ";
      $sql .= "        cxp_glosas CG  ";
      $sql .= "        ON( CG.prefijo = CF.prefijo AND ";
      $sql .= "            CG.numero = CF.numero AND ";
      $sql .= "            CG.empresa_id = CF.empresa_id AND ";
      $sql .= "            CG.sw_estado = '1') ";
      $sql .= "WHERE  CF.empresa_id = '".$empresa."'  ";
      $sql .= "AND    CF.prefijo = '".$prefijo."'  ";
      $sql .= "AND    CF.numero = ".$numero."  ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			if (!$rst->EOF)
			{
				$retorno = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
    }
    /**
    * Funcion donde se hace el registro de la objeccion del detalle de la factura
    *
    * @param array $factura Arreglo con los datos de las facturas
    * @param array $datos Arreglo con la informacion de la objeccion
    *
    * @return boolean
    */
    function RegistrarObjeccion($factura,$datos,$tipoauditor)
    {
      $this->ConexionTransaccion();
      if(!$factura['cxp_glosa_id'])
      {
        $sql = "SELECT NEXTVAL('cxp_glosas_cxp_glosa_id_seq');";
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
			
        $indice = "";
  			if(!$rst->EOF)
        {
        	$indice = $rst->fields[0];				
        	$rst->MoveNext();
        }
        $nv = $indice;
        if($nv > 1) $nv = $nv -1;
        
        $sqle = "SELECT SETVAL('cxp_glosas_cxp_glosa_id_seq',".($nv)."); ";
        
        $sql  = "INSERT INTO cxp_glosas (";
        $sql .= "       cxp_glosa_id, ";
        $sql .= "       empresa_id,	";
        $sql .= "       prefijo,	";
        $sql .= "       numero,	";
        $sql .= "       sw_estado, ";
        $sql .= "       usuario_registro ";
        $sql .= ") ";
        $sql .= "VALUES  ";
        $sql .= "( ";
        $sql .= "   ".$indice.", ";
        $sql .= "   '".$factura['empresa_id']."', ";
        $sql .= "   '".$factura['prefijo']."', ";
        $sql .= "    ".$factura['numero'].", ";
        $sql .= "   '1', ";
        $sql .= "   ".UserGetUID()." ";
        $sql .= "); ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
        { 
          $this->ConexionBaseDatos($sqle);
          return false;
        }
        
        $sql  = "INSERT INTO cxp_glosas_detalles (";
        $sql .= "       cxp_detalle_factura_id,	";
        $sql .= "       cxp_glosa_id,	";
        $sql .= "       valor,";
        $sql .= "       sw_estado,";
        $sql .= "       usuario_registro ";
        $sql .= ") ";
        $sql .= "VALUES  ";
        $sql .= "( ";
        $sql .= "   ".$datos['cxp_detalle_factura_id'].", ";
        $sql .= "   ".$indice.", ";
        ($tipoauditor == '1')? $sql .= "   ".$datos['valor_total'].", ":$sql .= "  0, ";
        $sql .= "   '1', ";
        $sql .= "   ".UserGetUID()." ";
        $sql .= "); ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
        {
          $this->ConexionBaseDatos($sqle);
          return false;
        }
        $factura['cxp_glosa_id'] = $indice;
      }
      else
      {
        if(!$factura['cxp_detalle_factura_id'])
        {
          $sql  = "INSERT INTO cxp_glosas_detalles (";
          $sql .= "       cxp_detalle_factura_id,	";
          $sql .= "       cxp_glosa_id,	";
          $sql .= "       valor,";
          $sql .= "       sw_estado,";
          $sql .= "       usuario_registro ";
          $sql .= ") ";
          $sql .= "VALUES  ";
          $sql .= "( ";
          $sql .= "   ".$datos['cxp_detalle_factura_id'].", ";
          $sql .= "   ".$factura['cxp_glosa_id'].", ";
          ($tipoauditor == '1')? $sql .= "   ".$datos['valor_total'].", ":$sql .= "  0 ,";
          $sql .= "   '1', ";
          $sql .= "   ".UserGetUID()." ";
          $sql .= "); ";
        }
        else
        {
          $sql  = "UPDATE cxp_glosas_detalles ";
          $sql .= "SET    usuario_ultima_actualizacion 	= ".UserGetUID()." , ";
          $sql .= "       fecha_ultima_actualizacion = NOW(), ";
          ($tipoauditor == '1')? $sql .= "    valor = ".$datos['valor_total']."  ":$sql .= "   valor = 0 ";
          $sql .= "WHERE  cxp_detalle_factura_id = ".$datos['cxp_detalle_factura_id']."	";
          $sql .= "AND    cxp_glosa_id = ".$factura['cxp_glosa_id']."	";
          $sql .= "AND    sw_estado = '1';	";  
        }
        if(!$rst = $this->ConexionTransaccion($sql))  return false;
      }
      
      $sql  = "UPDATE cxp_facturas ";
      $sql .= "SET    sw_pendiente = '1', ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().", ";
      $sql .= "       fecha_ultima_actualizacion = NOW() ";     
      $sql .= "WHERE  prefijo = '".$factura['prefijo']."' ";
      $sql .= "AND    numero = ".$factura['numero']." ";
      $sql .= "AND    empresa_id = '".$factura['empresa_id']."'; ";
      $sql .= "UPDATE cxp_detalle_facturas ";
      $sql .= "SET    sw_objetado = '1', ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().", ";
      $sql .= "       fecha_ultima_actualizacion = NOW() ";
      $sql .= "WHERE  cxp_detalle_factura_id = ".$datos['cxp_detalle_factura_id'].";  ";
      if(!$factura['cxp_glosa_detalle_observacion_id'])
      {
        $sql .= "INSERT INTO cxp_glosas_detalles_observaciones (";
        $sql .= "       cxp_glosa_detalle_observacion_id, ";
        $sql .= "       cxp_detalle_factura_id,	";
        $sql .= "       cxp_glosa_id,	";
        $sql .= "       observacion, ";
        $sql .= "       valor, ";
        $sql .= "       usuario_registro ";
        $sql .= ") ";
        $sql .= "VALUES  ";
        $sql .= "( ";
        $sql .= "     DEFAULT,";
        $sql .= "     ".$datos['cxp_detalle_factura_id'].", ";
        $sql .= "     ".$factura['cxp_glosa_id'].", ";
        $sql .= "     '".$datos['observacion']."', ";
        $sql .= "     ".$datos['valor_total'].", ";
        $sql .= "     ".UserGetUID()." ";
        $sql .= "); ";
      }
      else
      {
        $sql .= "UPDATE cxp_glosas_detalles_observaciones ";
        $sql .= "SET    observacion = '".$datos['observacion']."', ";
        $sql .= "       valor = ".$datos['valor_total']." ";
        $sql .= "WHERE  cxp_glosa_detalle_observacion_id = ".$factura['cxp_glosa_detalle_observacion_id']." ";
        $sql .= "AND    cxp_detalle_factura_id = ".$datos['cxp_detalle_factura_id']."	";
        $sql .= "AND    cxp_glosa_id = ".$factura['cxp_glosa_id'].";	";
      }
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        if($sqle != "") $this->ConexionBaseDatos($sqle);
        return false;
      }
      $this->Commit();
      return true;
    }
    /**
    * Funcion donde se hace el registro de la objeccion sobre la factura
    *
    * @param array $factura Arreglo con los datos de las facturas
    * @param array $datos Arreglo con la informacion de la objeccion
    * @param string $tipoauditor Identificador del tipo de auditor
    *
    * @return boolean
    */
    function RegistrarObjeccionCuenta($factura,$datos,$tipoauditor)
    {
      $this->ConexionTransaccion();
      if(!$factura['cxp_glosa_id'])
      {
        $sql = "SELECT NEXTVAL('cxp_glosas_cxp_glosa_id_seq');";
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
			
        $indice = "";
  			if(!$rst->EOF)
        {
        	$indice = $rst->fields[0];				
        	$rst->MoveNext();
        }
        $nv = $indice;
        if($nv > 1) $nv = $nv -1;
        
        $sqle = "SELECT SETVAL('cxp_glosas_cxp_glosa_id_seq',".($nv)."); ";
        
        $sql  = "INSERT INTO cxp_glosas (";
        $sql .= "       cxp_glosa_id, ";
        $sql .= "       empresa_id,	";
        $sql .= "       prefijo,	";
        $sql .= "       numero,	";
        $sql .= "       valor,	";
        $sql .= "       sw_estado, ";
        $sql .= "       usuario_registro ";
        $sql .= ") ";
        $sql .= "VALUES  ";
        $sql .= "( ";
        $sql .= "   ".$indice.", ";
        $sql .= "   '".$factura['empresa_id']."', ";
        $sql .= "   '".$factura['prefijo']."', ";
        $sql .= "    ".$factura['numero'].", ";
        ($tipoauditor == '1')? $sql .= "   ".$datos['valor_total'].", ":$sql .= "  0, ";
        $sql .= "   '1', ";
        $sql .= "   ".UserGetUID()." ";
        $sql .= "); ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
        { 
          $this->ConexionBaseDatos($sqle);
          return false;
        }
        
        $factura['cxp_glosa_id'] = $indice;
      }
      else
      {
        $sql  = "UPDATE cxp_glosas ";
        $sql .= "SET    usuario_ultima_actualizacion 	= ".UserGetUID()." , ";
        $sql .= "       fecha_ultima_actualizacion = NOW(), ";
        ($tipoauditor == '1')? $sql .= "   valor = ".$datos['valor_total']." ":$sql .= "  valor = 0 ";
        $sql .= "WHERE  cxp_glosa_id = ".$factura['cxp_glosa_id']."	";
        $sql .= "AND    sw_estado = '1';	";  
      
        if(!$rst = $this->ConexionTransaccion($sql))  return false;
      }
      
      $sql  = "UPDATE cxp_facturas ";
      $sql .= "SET    sw_pendiente = '1', ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().", ";
      $sql .= "       fecha_ultima_actualizacion = NOW() ";
      $sql .= "WHERE  empresa_id = '".$factura['empresa_id']."'  ";
      $sql .= "AND    prefijo = '".$factura['prefijo']."'  ";
      $sql .= "AND    numero = ".$factura['numero'].";  ";
      
      if(!$datos['cxp_glosa_observacion_id'])
      {
        $sql .= "INSERT INTO cxp_glosas_observaciones (";
        $sql .= "       cxp_glosa_observacion_id, ";
        $sql .= "       cxp_glosa_id,	";
        $sql .= "       observacion, ";
        $sql .= "       valor, ";
        $sql .= "       usuario_registro ";
        $sql .= ") ";
        $sql .= "VALUES  ";
        $sql .= "( ";
        $sql .= "     DEFAULT,";
        $sql .= "     ".$factura['cxp_glosa_id'].", ";
        $sql .= "     '".$datos['observacion']."', ";
        $sql .= "     ".$datos['valor_total'].", ";
        $sql .= "     ".UserGetUID()." ";
        $sql .= "); ";
      }
      else
      {
        $sql .= "UPDATE cxp_glosas_observaciones ";
        $sql .= "SET    observacion = '".$datos['observacion']."', ";
        $sql .= "       valor = ".$datos['valor_total']." ";
        $sql .= "WHERE  cxp_glosa_observacion_id = ".$datos['cxp_glosa_observacion_id']." ";
        $sql .= "AND    cxp_glosa_id = ".$factura['cxp_glosa_id'].";	";
      }
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        if($sqle != "") $this->ConexionBaseDatos($sqle);
        return false;
      }
      $this->Commit();
      return true;
    }    
    /**
    * Funcion donde se actaliza el estado de los documentos
    *
    * @param array $factura Arreglo con los datos del documento
    * @param string $tipoauditor Identificador del tipo de documento
    *
    * @return boolean
    */
    function ActualizarEstadoDocumento($factura,$tipoauditor)
    {
      $estado = "";
      if($tipoauditor == 1)
        $estado = "RA";
      else if ($tipoauditor == 2)
        $estado = "RM";
        
      $sql  = "UPDATE cxp_facturas ";
      $sql .= "SET    cxp_estado = '".$estado."', ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().", ";
      $sql .= "       fecha_ultima_actualizacion = NOW() ";
      $sql .= "WHERE  empresa_id = '".$factura['empresa_id']."'  ";
      $sql .= "AND    prefijo = '".$factura['prefijo']."'  ";
      $sql .= "AND    numero = ".$factura['numero'].";  ";
      
      if(!$this->ConexionBaseDatos($sql)); return false;
      
      return true;
    }
    /**
    * Funcion donde se obtiene la informacion de la objeccion sobre el detalle 
    * del documento
    *
    * @param string $cxp_detalle_factura_id Identificador del detalle de la cuenta
    *
    * @return mixed
    */
    function ObtenerInformacionGlosaDetalle($cxp_detalle_factura_id)
    {
      $sql  = "SELECT CL.cxp_glosa_id, ";
      $sql .= "       CL.cxp_detalle_factura_id, ";
      $sql .= "       CO.cxp_glosa_detalle_observacion_id,";
      $sql .= "       CO.observacion, 	";
      $sql .= "       TO_CHAR(CO.fecha_registro,'DD/MM/YYYY') AS fecha_registro, 	";
      $sql .= "       CO.valor, 	";
      $sql .= "       SU.usuario_id, 	";
      $sql .= "       SU.nombre 	";
      $sql .= "FROM   cxp_glosas_detalles CL, ";
      $sql .= "       cxp_glosas_detalles_observaciones CO, ";
      $sql .= "       system_usuarios SU ";
      $sql .= "WHERE  CL.cxp_detalle_factura_id = ".$cxp_detalle_factura_id."  ";
      $sql .= "AND    CL.cxp_detalle_factura_id = CO.cxp_detalle_factura_id 	";
      $sql .= "AND    CL.cxp_glosa_id = CO.cxp_glosa_id ";
      $sql .= "AND    CL.sw_estado = '1'  ";
      $sql .= "AND    CO.usuario_registro =  SU.usuario_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[$rst->fields[6]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
    }      
    /**
    * Funcion donde se obtiene la informacion de la objeccion sobre la factura
    *
    * @param array $factura Arreglo con los datos del documento
    *
    * @return mixed
    */
    function ObtenerInformacionGlosa($factura)
    {
      $sql  = "SELECT CL.cxp_glosa_id, ";
      $sql .= "       CO.cxp_glosa_observacion_id,";
      $sql .= "       CO.observacion, 	";
      $sql .= "       CO.valor, 	";
      $sql .= "       TO_CHAR(CO.fecha_registro,'DD/MM/YYYY') AS fecha_registro, 	";
      $sql .= "       SU.usuario_id, 	";
      $sql .= "       SU.nombre 	";
      $sql .= "FROM   cxp_glosas CL, ";
      $sql .= "       cxp_glosas_observaciones CO, ";
      $sql .= "       system_usuarios SU ";
      $sql .= "WHERE  empresa_id = '".$factura['empresa_id']."'  ";
      $sql .= "AND    prefijo = '".$factura['prefijo']."'  ";
      $sql .= "AND    numero = ".$factura['numero']."  ";      
      $sql .= "AND    CL.cxp_glosa_id = CO.cxp_glosa_id ";
      $sql .= "AND    CL.sw_estado = '1'  ";
      $sql .= "AND    CO.usuario_registro =  SU.usuario_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[$rst->fields[5]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
    }
    /**
    * Funcion donde se actualiza el estado del documento
    *
    * @param string $prefijo Prefijo del documento
    * @param integer $numero Numero del documento
    * @param string $empresa Identificador de la empresa
    * @param string $estado Estado al cual pasara el documento
    *
    * @return mixed
    */
    function ActualizarDocumento($prefijo,$numero,$empresa,$estado)
    {   
      $sql  = "UPDATE cxp_facturas ";
      $sql .= "SET    cxp_estado = '".$estado."', ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().", ";
      $sql .= "       fecha_ultima_actualizacion = NOW() ";
      $sql .= "WHERE  empresa_id = '".$empresa."'  ";
      $sql .= "AND    prefijo = '".$prefijo."'  ";
      $sql .= "AND    numero = ".$numero.";  ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return true;
    }
    /**
    * Funcion donde se valida la glosa
    *
    * @param string $prefijo Prefijo del documento
    * @param integer $numero Numero del documento
    * @param string $empresa Identificador de la empresa
    *
    * @return mixed
    */
    function ValidarGlosas($prefijo,$numero,$empresa)
    {
      $sql  = "SELECT  COALESCE(SUM(CG.valor),-1) ";
      $sql .= "FROM    cxp_detalle_facturas CD, ";
      $sql .= "        cxp_glosas_detalles CG ";
      $sql .= "WHERE   CD.cxp_detalle_factura_id = CG.cxp_detalle_factura_id ";
      $sql .= "AND     CD.prefijo = '".$prefijo."' ";
      $sql .= "AND     CD.numero = ".$numero." ";
      $sql .= "AND     CD.empresa_id = '".$empresa."' ";
      $sql .= "UNION ALL ";
      $sql .= "SELECT  COALESCE(SUM(CG.valor),-1) ";
      $sql .= "FROM    cxp_glosas CG ";
      $sql .= "WHERE   CG.prefijo = '".$prefijo."' ";
      $sql .= "AND     CG.numero = ".$numero."  ";
      $sql .= "AND     CG.empresa_id = '".$empresa."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
    }
    /**
    * Funcion donde se obtiene una lista de precios cargos para hacer 
    * la comparacion de los mismos
    *
    * @param integer $codigo_proveedor Codigo del proveedor
    * @param string $numero_contrato Numero de contrato con el proveedor
    * @param date $fecha_factura Fecha de la gfactura
    *
    * @return mixed
    */
    function ObtenerListaPreciosComparacion($codigo_proveedor,$numero_contrato,$fecha_factura)
    {
      $datos = array();

      $sqla  = "SELECT LC.lista_codigo ";
      $sqla .= "FROM   listas_precios_cargos_proveedores LP, ";
      $sqla .= "       listas_precios_cargos LC ";
      $sqla .= "WHERE  LC.lista_codigo = LP.lista_codigo ";
      $sqla .= "AND    LP.codigo_proveedor_id = ".$codigo_proveedor." 	 ";
      
      if($numero_contrato)
      {
        $sql  = $sqla;
        $sql .= "AND    LP.numero_contrato = ".$numero_contrato." ";
        
        if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
        while (!$rst->EOF)
        {
          $datos = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        
        if(!empty($datos)) return $datos;
      }
      
      if($fecha_factura)
      {
        $sql  = $sqla;
        $sql .= "AND    LC.fecha_inicio_lista <= ".$fecha_factura." ";
        $sql .= "AND    LC.fecha_fin_lista >= ".$fecha_factura." ";
          
        if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
        while (!$rst->EOF)
        {
          $datos = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        
        if(!empty($datos)) return $datos;
      }
      
      $sqla .= "AND    LC.sw_estado = '1' ";
      if(!$rst = $this->ConexionBaseDatos($sqla))	return false;
      
      while (!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
    /**
    * Funcion donde se obtiene una lista de precios medicamentos para hacer 
    * la comparacion de los mismos
    *
    * @param integer $codigo_proveedor Codigo del proveedor
    * @param date $fecha_factura Fecha de la gfactura
    *
    * @return mixed
    */
    function ObtenerListaPreciosComparacionMedicamentos($codigo_proveedor,$fecha_factura)
    {
      $datos = array();

      $sqla  = "SELECT LC.codigo_lista ";
      $sqla .= "FROM   listas_precios_medicamentos_proveedores LP, ";
      $sqla .= "       listas_precios LC ";
      $sqla .= "WHERE  LC.codigo_lista = LP.codigo_lista ";
      $sqla .= "AND    LP.codigo_proveedor_id = ".$codigo_proveedor." 	 ";
      
      if($fecha_factura)
      {
        $sql  = $sqla;
        $sql .= "AND    LC.fecha_inicio_lista <= ".$fecha_factura." ";
        $sql .= "AND    LC.fecha_fin_lista >= ".$fecha_factura." ";
          
        if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
        while (!$rst->EOF)
        {
          $datos = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        
        if(!empty($datos)) return $datos;
      }
      
      $sqla .= "AND    LC.sw_estado = '1' ";
      if(!$rst = $this->ConexionBaseDatos($sqla))	return false;
      
      while (!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
    /**
    * Funcion donde se obtienen los servicios
    *
    * @param array $filtros Arreglo con los datos de filtro de la consulta
    *
    * @return mixed
    */
    function ObtenerServiciosCuenta($filtros)
    {
      $sql = "SELECT  CS.cxp_tipo_servicio_id,";
      $sql .= "       CS.descripcion_tipo_servicio ";
      $sql .= "FROM   cxp_tipos_servicios CS,";
      $sql .= "       cxp_servicios_x_tipo CT ";
      $sql .= "WHERE  CS.cxp_tipo_servicio_id = CT.cxp_tipo_servicio_id ";
      $sql .= "AND    CS.sw_mostrar = '1' ";
      $sql .= "AND    CT.tipo_cxp = '".$filtros['tipo_cuenta']."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }    
    /**
    * Funcion donde se obtienen las especialidades
    *
    * @param array $filtros Arreglo con los datos de filtro de la consulta
    *
    * @return mixed
    */
    function ObtenerEspecialidadesServicios($filtros)
    {
      $sql = "SELECT  CS.cxp_especialidad_id,";
      $sql .= "       CS.descripcion_especialidad ";
      $sql .= "FROM   cxp_especialidades CS,";
      $sql .= "       cxp_especialidad_x_servicio CT ";
      $sql .= "WHERE  CS.cxp_especialidad_id = CT.cxp_especialidad_id ";
      $sql .= "AND    CS.sw_mostrar = '1' ";
      $sql .= "AND    CT.cxp_tipo_servicio_id = '".$filtros['tipo_servicio']."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
    /**
    * Funcion donde se obtiene el historial de estados de las cuentas
    *
    * @param array $factura Arreglo con los datos de la factura
    *
    * @return mixed
    */
    function ObtenerHistoricoEstados($factura)
    {
      $sql  = "SELECT C1.cxp_estado_descripcion AS estado_actual, ";
      $sql .= "       C2.cxp_estado_descripcion AS estado_anterior, ";
      $sql .= "       SU.nombre, ";
      $sql .= "       TO_CHAR(CH.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
      $sql .= "FROM   cxp_historico_estados CH ";
      $sql .= "       LEFT JOIN cxp_estados C2";
      $sql .= "       ON (CH.cxp_estado_anterior = C2.cxp_estado ) ,  ";
      $sql .= "       cxp_estados C1,  ";
      $sql .= "       system_usuarios SU  ";
      $sql .= "WHERE  CH.empresa_id = '".$factura['empresa_id']."'  ";
      $sql .= "AND    CH.prefijo = '".$factura['prefijo']."'  ";
      $sql .= "AND    CH.numero = ".$factura['numero']."  ";  
      $sql .= "AND    CH.cxp_estado_actual = C1.cxp_estado ";
      $sql .= "AND    CH.usuario_registro = SU.usuario_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
    }    
  }
?>