<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ContratacionProductosSQL.class.php,v 1.12 2010/01/26 22:40:55 sandra Exp $Revision: 1.12 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja Torres 
  */
   /**
  * Clase : ContratacionProductosSQL
  * 
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.12 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja Torres 
  */
 
  class ContratacionProductosSQL extends ConexionBD
  {
      /*
      * Constructor de la clase
      */
      function ContratacionProductosSQL(){}
      /**
      * Funcion donde se verifica el permiso del usuario para el ingreso al modulo
      * @return array $datos vector que contiene la informacion de la consulta del codigo de
      * la empresa y la razon social
      */
      function ObtenerPermisos()
      {
     
        $sql  = "SELECT   p.empresa_id AS empresa, ";
        $sql .= "         p.razon_social AS razon_social ";
        $sql .= "FROM     userpermisos_contratacion_productos cp, empresas p ";
        $sql .= "WHERE    cp.usuario_id = ".UserGetUID()." ";
        $sql .= "         AND cp.empresa_id = p.empresa_id  AND sw_activo='1' AND sw_tipo_empresa='0' ";
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
      * Funcion donde se Consultan los Tipos de identificacion 
      * @return array $datos vector que contiene la informacion de la consulta de los Tipos 
      * de Identificacion
      */

      function ConsultarTipoId()
      {
       
        $sql  = "SELECT    tipo_id_tercero, descripcion ";
        $sql .= "FROM      tipo_id_terceros ";
        $sql .= "ORDER BY  tipo_id_tercero, descripcion ";
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

      /**
      * Funcion donde se Consulta la Informacion del Proveedor y se realizan los 
      * filtros de busqueda teniendo en cuenta diferentes  parametros de busqueda
      * @param array $filtro vector con los datos del request donde se encuentra el 
      * parametos de busqueda
      * @param array $offset vector con los datos del request donde se encuentra el
      * parametos de busqueda
      * @return array $datos vector que contiene la informacion consultada del paciente
      */

      function ObtenerProveedores($filtros,$offset,$empresa)
      {
        $sql= "SELECT    tp.codigo_proveedor_id,tp.tipo_id_tercero, tp.tercero_id, t.nombre_tercero, t.telefono, t.direccion,tp.representante_ventas, tp.telefono_representante_ventas,tp.empresa_id ";
        $sql .= "FROM      terceros t, terceros_proveedores tp ";
        $sql .=" WHERE     tp.tipo_id_tercero= t.tipo_id_tercero and tp.tercero_id= t.tercero_id  and tp.empresa_id='".$empresa. "' ";
        if($filtros['tipo_id_tercero']!= "-1")
        $sql.=" and tp.tipo_id_tercero= '". $filtros['tipo_id_tercero']."' ";
        if($filtros['tercero_id'])
        {
        $sql.=" and tp.tercero_id= '".$filtros['tercero_id']."' ";
        }
        if($filtros['nombre_tercero'] != "")
        $sql .= "AND     t.nombre_tercero ILIKE '%".$filtros['nombre_tercero']."%' ";
        $cont="select COUNT(*) from (".$sql.") AS A";
        $this->ProcesarSqlConteo($cont,$offset);
        $sql .= "ORDER BY t.nombre_tercero ";
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
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
      /*
      * Funcion donde se consulta  informacion completa del  Proveedor.
      * @param string $noId cadena con el valor del numero de identificacion
      * @param string $tipoId cadena con el valor del tipo de identificacion
      * @return array $datos vector con la informacion de los Proveedor 
      */
      function ConsCodProveFiltro($noId, $tipoId)  
      {
   
        $sql  = "SELECT   tp.codigo_proveedor_id,   tp.tipo_id_tercero, tp.tercero_id, t.nombre_tercero,t.direccion, t.telefono, t.fax, t.email, t.celular, tp.representante_ventas, ";
        $sql .= "         tp.	telefono_representante_ventas, tp.nombre_gerente, tp.telefono_gerente ";
        $sql .= "FROM     terceros t, terceros_proveedores tp ";
        $sql .= " WHERE   tp.tercero_id=t.tercero_id and tp.tipo_id_tercero= t.tipo_id_tercero and tp.tercero_id = '".$noId. "' ";
        $sql .= "        and tp.tipo_id_tercero = '".$tipoId."' ";

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

      /*
      * Funcion donde se consulta la Informacion de la Empresa.
      * @return array $datos vector con la informacion de la Empresa. 
      */

      function ConsDatosEmpresa($empresa) 
      {
      
        $sql  = "SELECT ep.empresa_id, ep.tipo_id_tercero,  ep.id, ep.razon_social, ep.representante_legal, ep.direccion, ep.telefonos, ep.fax, ep.email, ep.codigo_sgsss, ep.tipo_pais_id, ep.tipo_dpto_id, ep.tipo_mpio_id, tm.municipio, td.departamento ";
        $sql .= "FROM   empresas ep, tipo_mpios tm, tipo_dptos td ";
        $sql .= "WHERE  ep.tipo_dpto_id = tm.tipo_dpto_id  ";
        $sql .= "and    ep.tipo_mpio_id = tm.tipo_mpio_id ";
        $sql .= "and    ep.tipo_dpto_id= td.tipo_dpto_id ";
        $sql .= "and    ep.empresa_id = '".$empresa['empresa']."' ";

        if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        $datos = array();
        if(!$rst->EOF)
        {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
      }
	     /*
      * Funcion donde se consulta  la existencia de un contrato activo para un determinado proveedor.
      * @return array $datos vector con la informacion de la Empresa. 
      */ 
		function ConsultarExistenciasContrato($empresa,$tipo,$id)
		{
		
			$sql = "SELECT  contratacion_prod_id,
                      empresa_id,
                      no_contrato,
                      tipo_id_tercero,
                      tercero_id,
                      estado
					FROM    contratacion_produc_proveedor
					WHERE   empresa_id = '".$empresa."' 
					AND     tipo_id_tercero= '".$tipo."' 
					AND     tercero_id = '".$id."' 
					AND    estado = '1' 
          AND     sw_cliente='0' ";
					
					if(!$rst = $this->ConexionBaseDatos($sql))
			        return false;
			        $datos = array();
			        if(!$rst->EOF)
			        {
			        $datos = $rst->GetRowAssoc($ToUpper);
			        $rst->MoveNext();
			        }
			        $rst->Close();
			        return $datos;
		}
	
      /**
      * Funcion donde se almacena la informacion del Contrato
      * @param array $request vector con la informacion del request
      * @return boolean
      */

      function IngresarDatosContrato($request,$codprov)
      {
      
        $indice = array();
        $datos = array();

        $datos['fecha_inicio'] = $request['fecha_inicio'];
        $fdatos=explode("-", $datos['fecha_inicio']);
        $fedatos= $fdatos[2]."/".$fdatos[1]."/".$fdatos[0];

        $dtos = array();
        $dtos['fecha_final'] = $request['fecha_final'];
        $fdtos=explode("-", $dtos['fecha_final']);
        $fecdtos= $fdtos[2]."/".$fdtos[1]."/".$fdtos[0];

        $sql = "SELECT NEXTVAL('contratacion_produc_proveedor_contratacion_prod_id_seq') AS sq ";

        $rst = $this->ConexionBaseDatos($sql);

        if(!$rst->EOF)
        {
        $indice = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();     
        }
        $rst->Close();
		    $sqlerror = "SELECT setval('contratacion_produc_proveedor_contratacion_prod_id_seq', ".($indice['sq']-1).") ";    
        $this->ConexionTransaccion();
        $sql  = "INSERT INTO contratacion_produc_proveedor( ";
        $sql .= "      contratacion_prod_id, ";
        $sql .= "       empresa_id, ";
        $sql .= "       No_Contrato, ";
        $sql .= "       Descripcion, ";
        $sql .= "       Fecha_Inicio, ";
        $sql .= "       Fecha_Vencimiento, ";
        $sql .= "       tipo_id_tercero, ";
        $sql .= "       tercero_id, ";
        $sql .= "       Condiciones_entrega, ";
        $sql .= "       usuario_id, ";
        $sql .= "       fecha_registro, ";
        $sql .= "      observaciones, codigo_proveedor_id ";
        $sql .= ")VALUES( ";
        $sql .= "       ".$indice['sq'].", ";
        $sql .= "       '".$request['empresa']."', ";
        $sql .= "       '".$request['txtncontrato']."', ";
        $sql .= "       '".$request['desc']."', ";
        $sql .= "       '".$fedatos. "', ";
        $sql .= "       '".$fecdtos. "', ";
        $sql .= "       '".$request['tipo_id_tercero']."', ";
        $sql .= "       '".$request['tercero_id']."', ";
        $sql .= "       '".$request['condtiemp']."', ";
        $sql .= "          ".UserGetUID().",";
        $sql .= "         NOW(), ";
        $sql .= "       '".$request['observar']."', ";
		$sql .= "       '".$codprov."' ";
        $sql .= "       ) ";

        if(!$rst = $this->ConexionTransaccion($sql))
        {
        if(!$rst = $this->ConexionTransaccion($sqlerror)) 
        return false;      
        }    
        else
        {
        $this->Commit();
        return true;
      }
      }
      /**
      * Funcion donde se Consulta  el Numero del Contrato
      * @param string $noId cadena con el valor del numero de identificacion
      * @param string $tipoId cadena con el valor del tipo de identificacion
      * @return array $datos vector con la informacion de la consulta. 
      */

    function ConsultarNoContratoEn($noId, $tipoId,$empresa)  
      {
     
      $sql  = "SELECT     d.no_contrato, c.tercero_id, c.tipo_id_tercero, c.estado,c.contratacion_prod_id, c.empresa_id ,c.descripcion ";
      $sql .= "FROM      contratacion_produc_proveedor c,contratacion_produc_prov_detalle d   ";
      $sql .= "WHERE     c.tipo_id_tercero='".$tipoId."' and c.tercero_id='".$noId. "' and c.empresa_id='".$empresa."' and c.contratacion_prod_id=d.contratacion_prod_id  " ;
      $sql .= "          and c.estado='1' and c.sw_cliente='0' ";
      $sql .= "         group by d.no_contrato,c.estado,c.contratacion_prod_id,c.tercero_id, c.tipo_id_tercero,c.empresa_id ,c.descripcion " ;
     
      
      
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
     /*
      * Funcion donde se consulta  los contratos del proveedor con la empresa.
      * @return array $datos vector con la informacion de la Empresa. 
      */
      
      function ConsultarNoContrato($noId, $tipoId,$empresa)  
      {
     
      $sql  = "SELECT     c.no_contrato, c.contratacion_prod_id,c.tercero_id, c.tipo_id_tercero, c.estado,  c.empresa_id ,c.descripcion  ";
      $sql .= "FROM     contratacion_produc_proveedor c   ";
      $sql .= "WHERE     c.tipo_id_tercero='".$tipoId."' and c.tercero_id='".$noId. "' and c.empresa_id='".$empresa."'   and c.estado='1' and c.sw_cliente='0'  ";
      $sql .= "        group by c.no_contrato,c.contratacion_prod_id,c.estado,c.empresa_id ,c.descripcion, c.tercero_id, c.tipo_id_tercero  " ;
     

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
      
      /**
      * Funcion donde se Consulta  la informacion  del Contrato
      * @param string $contrato cadena con el  numero del contrato
      * @return array $datos vector con la informacion del contrato. 
      */   

      function ConsultarInformacionContrato($contrato,$empresa)
      {

   
      $sql  = "SELECT    CONT.no_contrato, CONT.descripcion, CONT.contratacion_prod_id,";
      $sql  .= "          TO_CHAR(CONT.fecha_inicio,'dd-mm-yyyy') AS fecha_inicio,  ";
      $sql  .= "          TO_CHAR(CONT.fecha_vencimiento,'dd-mm-yyyy') as fecha_vencimiento ,   ";
      $sql  .= "          CONT.tipo_id_tercero,  ";
      $sql  .= "          CONT.tercero_id,  ";
      $sql  .= "          CONT.condiciones_entrega, ";
      $sql  .= "          CONT.observaciones , CONT.empresa_id, ";
      $sql  .= "          PROV.nombre_gerente, PROV.telefono_gerente, ";
      $sql  .= "          TERC.nombre_tercero, ";
      $sql  .= "          TERC.direccion ,  ";
      $sql  .= "          TERC.telefono,  ";
      $sql  .= "          TERC.fax,  ";
      $sql  .= "          TERC.email, ";
      $sql  .= "          TERC.celular ";
      $sql  .= "FROM      contratacion_produc_proveedor  CONT, ";
      $sql  .= "          terceros_proveedores PROV,  ";
      $sql  .= "          terceros  TERC  ";
      $sql  .= " WHERE    CONT.tipo_id_tercero=PROV.tipo_id_tercero and CONT.tercero_id= PROV.tercero_id  and PROV.tipo_id_tercero =TERC.tipo_id_tercero  and PROV.tercero_id =TERC.tercero_id  ";
      $sql  .= "          AND  CONT.contratacion_prod_id= ".$contrato." and CONT.empresa_id= '".$empresa."' ";

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
   
	  
          /*
      * Funcion donde se actualiza o modifica el estado del contrato
      * @return array $datos vector con la informacion de la Empresa. 
      */ 

    function AtualizarEstadoContrato($request,$contratacion_prod_id)
      {
    
      $indice = array();
      $datos = array();

      $sql = "SELECT NEXTVAL('estados_contrato_estado_contrato_id_seq') AS sq ";
      if(!$rst = $this->ConexionBaseDatos($sql)) 
      return false;
      if(!$rst->EOF)
      {
      $indice = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();     
      }
      $rst->Close(); 
      $sqlerror = "SELECT setval(''estados_contrato_estado_contrato_id_seq', ".($indice['sq']-1).") ";    
      $this->ConexionTransaccion();
      if($request['estadocam']=='1'){
      $sql  = "INSERT INTO  ESTADOS_CONTRATO( ";
      $sql .= "       Estado_contrato_id, ";
      $sql .= "       contratacion_prod_id, ";
      $sql .= "       empresa_id, ";
      $sql .= "       estado_actual, ";
      $sql .= "       usuario_id, ";
      $sql .= "       fecha_registro , ";
      $sql .= "      observaciones ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       ".$contratacion_prod_id.", ";
      $sql .= "       '".$request['empresa_id']."', ";
      $sql .= "       '0', ";
      $sql .= "          ".UserGetUID().",";
      $sql .= "         NOW(), ";
      $sql .= "       '".$request['observacion']."' ";
      $sql .= "       ); ";

      $sql .= "UPDATE   contratacion_produc_proveedor ";
      $sql .= "SET      estado= '0' ";
      $sql .= " Where              ";
      $sql .= "        estado= '".$request['estadocam']."' and  contratacion_prod_id =".$contratacion_prod_id."; ";
      }      
      else { 
      $sql  = "INSERT INTO  ESTADOS_CONTRATO( ";
      $sql .= "       Estado_contrato_id, ";
      $sql .= "       contratacion_prod_id, ";
      $sql .= "       empresa_id, ";
      $sql .= "       estado_actual, ";
      $sql .= "       usuario_id, ";
      $sql .= "       fecha_registro , ";
      $sql .= "      observaciones ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       ".$contratacion_prod_id.", ";
      $sql .= "       '".$request['empresa_id']."', ";
      $sql .= "       '1', ";
      $sql .= "          ".UserGetUID().",";
      $sql .= "         NOW(), ";
      $sql .= "       '".$request['observacion']."' ";
      $sql .= "       ) ;";
      $sql .= "UPDATE   contratacion_produc_proveedor ";
      $sql .= "SET      estado= '1'  ";
      $sql .= " Where              ";
      $sql .= "          estado= '".$request['estadocam']."' and  contratacion_prod_id =".$contratacion_prod_id."; ";
      
	  }
      if(!$rst1 = $this->ConexionTransaccion($sql))
      {
      return false;
      }

      $this->Commit();
		return $nucontrato;

    }
	/*
      * Funcion donde se consulta el grupo id de un producto.
      * @return array $datos vector con la informacion. 
      */

      function ConsultarGrupoId() 
      {

     
      $sql= "SELECT grupo_id,descripcion FROM inv_grupos_inventarios ";
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
	/*
      * Funcion donde se consulta la clase id de un producto.
      * @return array $datos vector con la informacion. 
      */

      function ConsultarClaseId($grupo) 
      {
     
      $sql = "SELECT l.laboratorio_id, l.descripcion,  c.clase_id, c.grupo_id  ";
      $sql .=" From   inv_clases_inventarios c, inv_laboratorios l ";
      $sql .=" WHERE  c.grupo_id='".$grupo."'    and  c.laboratorio_id = l.laboratorio_id ";
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
		/*
      * Funcion donde se consulta el subgrupo id de un producto.
      * @return array $datos vector con la informacion. 
      */
      function ConsultarSubClaseId($grupo,$clasePr) 
      {

    
      $sql = "SELECT  m.molecula_id, m.descripcion, s.clase_id, s.grupo_id  ";
      $sql .=" from   inv_moleculas m,  inv_subclases_inventarios s ";
      $sql .=" WHERE  s.grupo_id='".$grupo."'  AND s.clase_id= '".$clasePr."'  and  s.molecula_id=m.molecula_id  ";

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

/*
      * Funcion donde se consulta los productos para una determinada empresa.
      * @return array $datos vector con la informacion. 
      */
      function ObtenerProductos($filtros, $offset,$filtr,$empresa)
      {
    
      $sql ="  SELECT   c.descripcion as laboratorio,
                        s.descripcion as molecula,
                        p.codigo_producto,
                        p.descripcion as producto, 
                        p.codigo_alterno,
                        p.contenido_unidad_venta,
                        i.costo,u.descripcion as unidad
               FROM     inv_grupos_inventarios g,
                        inv_clases_inventarios c, 
                        inv_laboratorios l,
                        inv_subclases_inventarios s,
                        inv_moleculas m,
                        inventarios_productos p,
                        inventarios i ,
                        unidades u 
                 WHERE  g.grupo_id=c.grupo_id 
                 and    l.laboratorio_id=c.laboratorio_id 
                 and    g.grupo_id=s.grupo_id 
                 and    s.clase_id=c.clase_id 
                 and    m.molecula_id=s.molecula_id 
                 and    s.subclase_id=p.subclase_id 
                 and    p.grupo_id=g.grupo_id
                 and    p.clase_id=c.clase_id 
                 and    s.subclase_id=p.subclase_id 
                 and    i.codigo_producto=p.codigo_producto
                 and    u.unidad_id=p.unidad_id 
                 and    i.empresa_id='".$empresa."'
                 ";
 
      if($filtros['codigo_producto']!="")
      {
      $sql.=" and p.codigo_producto LIKE '%".$filtros['codigo_producto']."%'  ";
      }

      if($filtros['codigo_alterno'] != "")
      $sql .= "AND     p.codigo_alterno='".$filtros['codigo_alterno']."' ";

      if($filtros['descripcion'] != "")
      $sql .= "AND     p.descripcion ILIKE '%".$filtros['descripcion']."%' ";

      if($filtr['grupo']!= "-1" AND (!empty($filtr['grupo'])))
        $sql.=" and g.grupo_id= '". $filtr['grupo']."' ";
      
      if($filtr['laboratorio']!="-1" AND (!empty($filtr['laboratorio'])))

      $sql.=" and l.laboratorio_id ILIKE '%". $filtr['laboratorio']."%' ";

      if($filtr['molecula']!= "-1"  AND (!empty($filtr['molecula'])))

      $sql.=" and m.molecula_id ILIKE '%". $filtr['molecula']."%' "; 


      $cont="select COUNT(*) from (".$sql.") AS A";
      $this->ProcesarSqlConteo($cont,$offset);
      $sql .= "ORDER BY p.codigo_producto  ";

      $sql .= "LIMIT ".$this->limit." OFFSET  ".$this->offset;
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
	/*
      * Funcion permite ingresar los productos seleccionados a un tabla temporal
      * @return array $datos vector con la informacion. 
      */

      function IngresarProductoAlContrato($empresa,$noidcontrato,$producto,$costo,$vpesos,$vporc,$valortotal,$bandera)
      {
             
		
      $indice = array();
      $this->ConexionTransaccion();
       $sql  = " INSERT INTO tmp_contratacion_detalle(  
                                empresa_id, 
                                contratacion_prod_id, 
                                codigo_producto, 
                                Precio, 
                                Valor_pactado, 
                                Valor_porcentaje, 
                                Valor_total_pactado, 
                                usuario_id
                             )values(
                                '".$empresa."',
                                ".$noidcontrato.", 
	                              '".$producto. "', 
                                ".$costo. ", ";
                  if($bandera==0)
                  {
                      $sql .= "    ".$vpesos.", 
                                     0, 
                                   ".$vpesos.", 
                                   ".UserGetUID()."
                               
                                   );   ";             
                  }else
                  {
                      $sql .= "      0, 
                                    ".$vporc.", 
                                   ".$valortotal.", 
                                   ".UserGetUID()."
                                
                                   );   ";  
                  }
    
            if(!$rst = $this->ConexionTransaccion($sql))
            {

            if(!$rst = $this->ConexionTransaccion($sqlerror)) 

            return false;      
            }    
            else
            {
            $this->Commit();
            return true;
            }
      }
    	/*
      * Funcion permite ingresar los productos seleccionados a un contrato
      * @return  boolean de acuerdo a la ejecucion del sql.
      */
      function Insertar_Producto_detalle_Contrato($productos)
      {
      
        $this->ConexionTransaccion();
        foreach($productos as $item=>$fila)
			  {
            $sql .= "INSERT INTO contratacion_produc_prov_detalle( ";
            $sql .= "            contrato_produc_prov_det_id , ";
            $sql .= "            empresa_id, ";
            $sql .= "            contratacion_prod_id, ";
            $sql .= "            codigo_producto, ";
            $sql .= "            Precio, ";
            $sql .= "            Valor_pactado, ";
            $sql .= "            Valor_porcentaje, ";
            $sql .= "            Valor_total_pactado, ";
            $sql .= "            usuario_id, ";
            $sql .= "            fecha_registro ";
            $sql .= ")VALUES( ";
            $sql .= "        nextval('contratacion_produc_prov_detall_contrato_produc_prov_det_id_seq'),";
            $sql .= "     	'".$fila['empresa_id']."', ";
            $sql .= "      	'".$fila['contratacion_prod_id']."', ";
            $sql .= "      	'".$fila['codigo_producto']."', ";
            $sql .= "      	".$fila['precio'].", ";
            $sql .= "      	".$fila['valor_pactado'].", ";
            $sql .= "      	".$fila['valor_porcentaje'].", ";
            $sql .= "      	".$fila['valor_total_pactado'].", ";
            $sql .= "          ".UserGetUID().", ";
            $sql .= "         NOW() ";
            $sql .= "       ); ";
        
        }
    			 if(!$rst = $this->ConexionTransaccion($sql))
          {

          if(!$rst = $this->ConexionTransaccion($sqlerror)) 

          return false;      
          }    
          else
          {
          $this->Commit();
          return true;
          }
      }
    	/*
      * Funcion permite consultar los productos seleccionado
      * @return  boolean de acuerdo a la ejecucion del sql.
      */  
 
      function Productos_seleccionados($empresa,$nocontrado)
      {
       
      
          $sql = " SELECT t.codigo_producto,
                          t.precio,
                          t.valor_pactado,
                          t.valor_porcentaje,
                          t.valor_total_pactado,
                          i.descripcion AS producto,
                          i.contenido_unidad_venta,
                          u.descripcion as unidad,
                          s.descripcion as molecula,
                          c.descripcion as laboratorio,
                          t.empresa_id,
                          t.contratacion_prod_id
                   FROM   tmp_contratacion_detalle t, 
                          inventarios_productos  i,
                          unidades u,
                          inv_subclases_inventarios s,
                          inv_clases_inventarios c
                   WHERE   t.codigo_producto=i.codigo_producto
                   AND     i.unidad_id=u.unidad_id
                   AND     i.grupo_id=s.grupo_id
                   AND     i.clase_id=s.clase_id
                   AND     i.subclase_id=s.subclase_id
                   AND     s.grupo_id=c.grupo_id
                   AND     s.clase_id=c.clase_id
                   and    t.empresa_id = '".$empresa."'
                   AND    t.contratacion_prod_id =".$nocontrado."
                   AND    t.usuario_id=".UserGetUID()." ";
                    
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
 	/*
      * Funcion permit Eliminar un producto de la tabla temporal
      * @return  boolean de acuerdo a la ejecucion del sql.
      */
     function Eliminar_producto_seleccionados($codigo,$empresa,$contrato)
	   {
	 
      $sql = " DELETE   from  tmp_contratacion_detalle     
               WHERE    codigo_producto= '".$codigo."' 
               and      empresa_id='".$empresa."' 
               and      contratacion_prod_id='".$contrato."' ";
             

	   
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
   	/*
      * Funcion permit Eliminar un producto que  ya se a registrado en el contrato
      * @return  boolean de acuerdo a la ejecucion del sql.
      */ 
      function Eliminar_producto_contratos($empresa,$contrato)
	   {
	
      $sql = " DELETE   from  tmp_contratacion_detalle     
               WHERE    empresa_id='".$empresa."' 
               and      contratacion_prod_id='".$contrato."'
               and      usuario_id = ".UserGetUID()."   ";
             

	   
	    if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
		  else{
      return true;
      }
		}
   /*
      * Funcion donde se consultan los contratos con estado inactivos.
      * @return array $datos vector con la informacion. 
      */

       function ConsultarNoContratoE($noId, $tipoId,$empresa)  
      {
  
      $sql  = "SELECT    c.no_contrato, c.tercero_id, c.tipo_id_tercero, c.estado,c.contratacion_prod_id, c.empresa_id,c.descripcion ";
      $sql .= "FROM      contratacion_produc_proveedor c,contratacion_produc_prov_detalle d  ";
      $sql .= "WHERE     c.tipo_id_tercero='".$tipoId."' and c.tercero_id='".$noId. "' and c.empresa_id='".$empresa."' and c.contratacion_prod_id=d.contratacion_prod_id  "  ;
      $sql .= "          and c.estado='0' and c.sw_cliente='0'  ";
       $sql .= "        group by  c.no_contrato,c.estado,c.contratacion_prod_id,c.tercero_id, c.tipo_id_tercero,c.empresa_id,c.descripcion  " ;

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
	  	/*
      * Funcion donde se consulta la informacion del proveedor.
      * @return array $datos vector con la informacion. 
      */
	    function ConsultarProveedorID($noId,$tipoId,$empresa)  
		{
		
     
			$sql = " SELECT codigo_proveedor_id,
						  empresa_id,
						  tipo_id_tercero,
						  tercero_id,
						  empresa_id_centro,
						  centro_utilidad,
						  estado
				   FROM    terceros_proveedores
				   WHERE   tipo_id_tercero='".$tipoId."'
				   and     tercero_id='".$noId. "' 
				   and     empresa_id='".$empresa."'  "  ;

	  	   
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

 	/*
      * Funcion donde se consulta los productos del contrato.
      * @return array $datos vector con la informacion. 
      */
      function ConsultarProductosContrato($contrato)
      {

	
      $sql  = "SELECT     CONT.no_contrato, CONT.descripcion, CONT.contratacion_prod_id,";
      $sql  .= "          TO_CHAR(CONT.fecha_inicio,'dd-mm-yyyy') AS fecha_inicio,  ";
      $sql  .= "          TO_CHAR(CONT.fecha_vencimiento,'dd-mm-yyyy') as fecha_vencimiento ,   ";
      $sql  .= "          CONT.tipo_id_tercero,  ";
      $sql  .= "          CONT.tercero_id,  ";
      $sql  .= "          CONT.condiciones_entrega, ";
      $sql  .= "          CONT.observaciones , ";
      $sql  .= "          PROV.nombre_gerente, PROV.telefono_gerente, ";
      $sql  .= "          TERC.nombre_tercero, ";
      $sql  .= "          TERC.direccion ,  ";
      $sql  .= "          TERC.telefono,  ";
      $sql  .= "          TERC.fax,  ";
      $sql  .= "          TERC.email, ";
      $sql  .= "          TERC.celular, ";
      $sql .= "           PROD.codigo_producto, " ;
      $sql .= "           PROD.descripcion," ;
      $sql .= "           PROD.contenido_unidad_venta," ;
      $sql .= "           DET.precio, " ;
      $sql .= "           DET.valor_pactado, " ;
      $sql .= "           DET.valor_porcentaje, " ;	
      $sql .= "           DET.valor_total_pactado, " ;
      $sql .= "           TIPOP.tipo_producto_id, " ;
      $sql .= "           TIPOP.descripcion as tipodescripcion, " ;
      $sql .= "           u.descripcion as unidad ";
    
      $sql  .= "FROM      contratacion_produc_proveedor  CONT, ";
      $sql  .= "          terceros_proveedores PROV,  ";
      $sql  .= "          terceros  TERC,  ";
      $sql .= "           contratacion_produc_prov_detalle  DET, " ;
      $sql .= "           unidades u, " ;
      $sql .= "            inv_tipo_producto  TIPOP, " ;
      $sql .= "           inventarios_productos PROD " ;
      $sql  .= " WHERE    CONT.tipo_id_tercero=PROV.tipo_id_tercero and CONT.tercero_id= PROV.tercero_id  and PROV.tipo_id_tercero =TERC.tipo_id_tercero  and PROV.tercero_id =TERC.tercero_id  AND DET.contratacion_prod_id=CONT.contratacion_prod_id and PROD.codigo_producto=DET.codigo_producto ";
      $sql  .= "          AND TIPOP.tipo_producto_id=PROD.tipo_producto_id AND PROD.unidad_id=u.unidad_id and  DET.contratacion_prod_id= '".$contrato."' ";


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
		/*
      * Funcion donde se actualiza la informacion del contrato.
      * @return boolean
      */
      function ActualizarTodaInfoContrato($request, $nucontrato)
      {
    
      $indice = array();

      $datos = array();

      $datos['fecha_inicio'] = $request['fecha_inicio'];
      $fdatos=explode("-", $datos['fecha_inicio']);
      $fedatos= $fdatos[2]."/".$fdatos[1]."/".$fdatos[0];

      $dtos = array();
      $dtos['fecha_final'] = $request['fecha_final'];
      $fdtos=explode("-", $dtos['fecha_final']);
      $fecdtos= $fdtos[2]."/".$fdtos[1]."/".$fdtos[0];

      $sql = "SELECT NEXTVAL('contratacion_produc_proveedor_contratacion_prod_id_seq') AS sq ";

      if(!$rst = $this->ConexionBaseDatos($sql)) 
      return false;
      if(!$rst->EOF)
      {
      $indice = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();     
      }
      $rst->Close(); 

      $sqlerror = "SELECT setval('contratacion_produc_proveedor_contratacion_prod_id_seq', ".($indice['sq']-1).") ";   

      $this->ConexionTransaccion();
      $sql  = "INSERT INTO contratacion_produc_proveedor( ";
      $sql .= "       contratacion_prod_id, ";
      $sql .= "       empresa_id, ";
      $sql .= "       No_Contrato, ";
      $sql .= "       Descripcion, ";
      $sql .= "       Fecha_Inicio, ";
      $sql .= "       Fecha_Vencimiento, ";
      $sql .= "       tipo_id_tercero, ";
      $sql .= "       tercero_id, ";
      $sql .= "       Condiciones_entrega, ";
      $sql .= "       usuario_id, ";
      $sql .= "       fecha_registro, ";
      $sql .= "      observaciones, ";
      $sql .= "      estado ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       '".$request['empresa']."', ";
      $sql .= "       '".$request['txtncontrato']."', ";
      $sql .= "       '".$request['desc']."', ";
      $sql .= "       '".$fedatos. "', ";
      $sql .= "       '".$fecdtos. "', ";
      $sql .= "       '".$request['tipo_id_tercero']."', ";
      $sql .= "       '".$request['tercero_id']."', ";
      $sql .= "       '".$request['condtiemp']."', ";
      $sql .= "          ".UserGetUID().",";
      $sql .= "         NOW(), ";
      $sql .= "       '".$request['observar']."', ";
      $sql .= "       '1' ";
      $sql .= "       ); ";


      if(!$rst = $this->ConexionTransaccion($sql))
      {
      if(!$rst = $this->ConexionTransaccion($sqlerror)) 
      return false;      
      }    
      else
      {
      $this->Commit();
      return true;
      }
      }

     	/*
      * Funcion donde se ingresa la copia del contrato.
      * @return boolean 
      */
      function IngresarProductoAlContratoCopia($empresa,$noidcontrato,$contrato,$producto,$costo,$vpesos,$vporc,$valortotal)
      {
		
      $indice = array();
		$sql = "SELECT NEXTVAL('contratacion_produc_prov_detall_contrato_produc_prov_det_id_seq') AS sq ";

      if(!$rst = $this->ConexionBaseDatos($sql)) 
      return false;
      if(!$rst->EOF)
      {
      $indice = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();     
      }
      $rst->Close(); 

      $sqlerror = "SELECT setval('contratacion_produc_prov_detall_contrato_produc_prov_det_id_seq', ".($indice['sq']-1).") ";    
      $this->ConexionTransaccion();


      $sql  = "INSERT INTO contratacion_produc_prov_detalle( ";
      $sql .= "       contrato_produc_prov_det_id , ";
      $sql .= "       empresa_id, ";
      $sql .= "       contratacion_prod_id, ";
      $sql .= "       codigo_producto, ";
      $sql .= "       Precio, ";
      $sql .= "       Valor_pactado, ";
      $sql .= "       Valor_porcentaje, ";
      $sql .= "       Valor_total_pactado, ";
      $sql .= "       usuario_id, ";
      $sql .= "       fecha_registro ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       '".$empresa."', ";
      $sql .= "       ".$noidcontrato.", ";
      $sql .= "       '".$producto. "', ";
      $sql .= "       ".$costo. ", ";
      $sql .= "       ".$vpesos.", ";
      $sql .= "       ".$vporc.", ";
      $sql .= "       ".$valortotal.", ";
      $sql .= "          ".UserGetUID().", ";
      $sql .= "         NOW() ";

      $sql .= "       ) ";

      if(!$rst = $this->ConexionTransaccion($sql))
      {
      if(!$rst = $this->ConexionTransaccion($sqlerror)) 
      return false;      
      }    
      else
      {
      $this->Commit();
      return true;
      }
      }
	
		/*
      * Funcion donde se consulta la clasificacion de los productos Alta costo.
      * @return array $datos vector con la informacion. 
      */
    function ClasificacionProductosAltoCosto($ncontrato)  
      {
     
        $sql  = "SELECT    c.contratacion_prod_id,p.codigo_producto,p.descripcion,t.tipo_producto_id,t.descripcion as clasificacion ";
        $sql .= "FROM       contratacion_produc_prov_detalle c,inventarios_productos p,inv_tipo_producto t ";
        $sql .= " WHERE  c.codigo_producto=p.codigo_producto and p.tipo_producto_id=t.tipo_producto_id and c.contratacion_prod_id= '".$ncontrato. "' AND t.tipo_producto_id='1' ";
    
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
     /* Funcion donde se consulta si el contrato tiene productos asociados.
      * @return array $datos vector con la informacion. 
      */
    function ConsultarContratosTienenProductos($noId, $tipoId) 
      {
    
        $sql  = "SELECT     c.contratacion_prod_id, c.no_contrato,c.descripcion,c.tercero_id, c.tipo_id_tercero,c.estado ";
        $sql .= "FROM       contratacion_produc_proveedor c,contratacion_produc_prov_detalle d ";
        $sql .= " WHERE  c.contratacion_prod_id=d.contratacion_prod_id  and tipo_id_tercero='".$tipoId."' and tercero_id='".$noId. "' and estado='1' ";
        $sql .= " group by c.no_contrato, c.contratacion_prod_id,c.descripcion,c.tercero_id, c.tipo_id_tercero,c.estado ; ";

                 
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
    
	/* Funcion donde se modifican los dias de envio.
      * @return array $datos vector con la informacion. 
      */
    function ModificarEnvios($dia, $nucontrato,$tipo)
      {
   

      $this->ConexionTransaccion();

      $sql  = "UPDATE   contrato_prod_detalle_envios ";
      $sql .= " SET     dias_envio= ".$dia.",  ";
      $sql .= "        usuario_id= ".UserGetUID().", ";
      $sql .= "        fecha_registro=  NOW() ";
      $sql .= " Where ";
      $sql .= " No_Contrato = '".$nucontrato."' and tipo_producto_id='".$tipo."'; ";

      if(!$rst1 = $this->ConexionTransaccion($sql))
      {
      return false;
      }

      $this->Commit();

      return $nucontrato;
      }
      
       /* Funcion donde se consulta las  politica de vencimiento
      * @return array $datos vector con la informacion. 
      */ 
      function ConsultarInfoPolitica($nocontrato){
           
    
   
      $sql  = "SELECT    p.tipo_producto_id,v.politica_descripcion,p.descripcion ";
      $sql .= "FROM      contrato_proveed_politicas_vencimientos v,inv_tipo_producto p ";
      $sql .= "WHERE     v.tipo_producto_id=p.tipo_producto_id and v.contratacion_prod_id='".$nocontrato."' ";
     
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
	  /* Funcion donde se consulta la informacion de los dias de envio
      * @return array $datos vector con la informacion. 
      */ 
       
      function ConsultarInfoEv($nocontrato){
        
    
   
      $sql  = "SELECT   	p.tipo_producto_id,p.descripcion,d.dias_envio ";
      $sql .= "FROM      	inv_tipo_producto p,contrato_prod_detalle_envios d ";
      $sql .= "WHERE      	d.tipo_producto_id=p.tipo_producto_id and d.contratacion_prod_id='".$nocontrato."' ";
     
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
      /* Funcion donde se inserta la imagen cargada
      * @return boolean. 
      */ 
      
      function Insertar($nombre, $size, $type, $buffer,$tipood,$Noid,$empresa)
      {
   
        $indice = array();

   $sql = "SELECT NEXTVAL('archivo_codigo_archivo_seq') AS sq ";

      if(!$rst = $this->ConexionBaseDatos($sql)) 
      return false;
      if(!$rst->EOF)
      {
      $indice = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();     
      }
      $rst->Close(); 

      $sqlerror = "SELECT setval('archivo_codigo_archivo_seq', ".($indice['sq']-1).") ";  
          
      $this->ConexionTransaccion();


      $sql  = "INSERT INTO archivo(   ";
      $sql .= "       codigo_archivo, ";
      $sql .= "      empresa_id, ";
      $sql .= "      tipo_id_tercero, ";
      $sql .= "      tercero_id, ";
      $sql .= "      archivo_nombre, ";
      $sql .= "       archivo_peso, ";
      $sql .= "       archivo_tipo, ";
      $sql .= "        archivo_bytea, ";
      $sql .= "       usuario_id,   ";
      $sql .= "         fecha_registro ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       '".$empresa."', ";
      $sql .= "       '".$tipood."', ";
      $sql .= "       '".$Noid."', ";
      $sql .= "       '".$nombre."', ";
      $sql .= "       ".$size.", ";
      $sql .= "       '".$type."', ";
      $sql .= "       '".$buffer. "' , ";
      $sql .= "          ".UserGetUID().", ";
      $sql .= "         NOW() ); ";
      if(!$rst = $this->ConexionTransaccion($sql))
      {
      if(!$rst = $this->ConexionTransaccion($sqlerror)) 
      return false;      
      }    
      else
      {
      $this->Commit();
      return true;
      }
      }
    /* Funcion donde se consulta toda la informacion del contrato
      * @return array $datos vector con la informacion. 
      */ 
	function FiltroProductosCont($filtros,$offset,$contratacion_prod_id)
      {
       

            $sql = "SELECT     	CONT.no_contrato, CONT.descripcion, CONT.contratacion_prod_id,  ";
            $sql .= "          	PROD.codigo_producto, ";
            $sql .= "         	PROD.codigo_alterno, ";
            $sql .= "         	PROD.descripcion, ";
            $sql .= "         	PROD.contenido_unidad_venta, ";
            $sql .= "        	  DET.valor_total_pactado,  ";
            $sql .= "       	  TIPOP.tipo_producto_id, ";
            $sql .= "       	  u.descripcion as unidad, ";
            $sql .= "       	  TIPOP.descripcion as tipodescripcion , s.descripcion as molecula , c.descripcion as laboratorio ";
            $sql .= "FROM   	contratacion_produc_proveedor  CONT, ";
            $sql .= "       	contratacion_produc_prov_detalle  DET,  ";
            $sql .= "      		inv_tipo_producto  TIPOP, ";
            $sql .= "      		inventarios_productos PROD, ";
            $sql .= "         inv_subclases_inventarios s, ";
            $sql .= "         inv_clases_inventarios c, ";
            $sql .= "         unidades u";
            $sql .=" WHERE    DET.contratacion_prod_id=CONT.contratacion_prod_id and PROD.codigo_producto=DET.codigo_producto  ";
            $sql .= "AND 		  TIPOP.tipo_producto_id=PROD.tipo_producto_id AND 
                              PROD.grupo_id=s.grupo_id
                    and 		  PROD.clase_id=s.clase_id
                    and       PROD.subclase_id=s.subclase_id	
                    and       s.grupo_id=c.grupo_id 
                    and       s.clase_id=c.clase_id
                    and       u.unidad_id=PROD.unidad_id
                    and	      CONT.contratacion_prod_id= ".$contratacion_prod_id." ";



      if($filtros['codigo_producto']!="")
      {
      $sql.=" and  PROD.codigo_producto LIKE '%".$filtros['codigo_producto']."%'  ";
      }

      if($filtros['codigo_alterno'] != "")
      $sql .= "AND     PROD.codigo_alterno='".$filtros['codigo_alterno']."' ";

      if($filtros['descripcion'] != "")
      $sql .= "AND     PROD.descripcion ILIKE '%".$filtros['descripcion']."%' ";

        $cont="select COUNT(*) from (".$sql.") AS A";
        $this->ProcesarSqlConteo($cont,$offset);
        $sql .= "ORDER BY PROD.codigo_producto ";
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;

   
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
	  /* Funcion donde se elimina el  producto contratado
      * @return array $datos vector con la informacion. 
      */ 
      
		function eliminar($codigo_prodc,$contratacion_prod_id,$empresa)
		{
		

		     $this->ConexionTransaccion();

		    $sql  = " DELETE     from contratacion_produc_prov_detalle ";
		    $sql .= " Where        contratacion_prod_id = ".$contratacion_prod_id."   and codigo_producto='".$codigo_prodc."' ";
        $sql .= " and         empresa_id='".$empresa."' ";
			  if(!$rst1 = $this->ConexionTransaccion($sql))
		    {
		    return false;
		     }
			 $this->Commit();
			 return true;
      }
	 /* Funcion donde se consultan las farmacias
      * @return array $datos vector con la informacion. 
      */ 
				
	  function BuscarFarmacias($filtros,$offset)
		{
           
		    $FechaI=$filtros['fecha_inicio'];
			$FechaF=$filtros['fecha_final'];

			$fdatos=explode("-", $FechaI);
			$fedatos= $fdatos[2]."-".$fdatos[1]."-".$fdatos[0];
		
			$fdtos=explode("-", $FechaF);
			$fecdtos= $fdtos[2]."-".$fdtos[1]."-".$fdtos[0];
		
		
			$sql  = " SELECT    empresa_id,tipo_id_tercero,id,razon_social,representante_legal,codigo_sgsss,direccion,telefonos,fax,sw_tipo_empresa,nivel_atencion ";
			$sql .= " FROM  empresas WHERE sw_tipo_empresa='1'  ";

			if($filtros['tipo_id_tercero']!= "-1" )
			{
				$sql.="  and tipo_id_tercero= '". $filtros['tipo_id_tercero']."'  ";
			}
			if($filtros['codigo'])
			$sql.=" and empresa_id= '".$filtros['codigo']."' ";
			
			
			if($filtros['id'])
			$sql.=" and id= '".$filtros['id']."' ";
			if($filtros['razon_social'] != "")
			$sql .= " AND     razon_social ILIKE '%".$filtros['razon_social']."%' ";
			$cont="select COUNT(*) from (".$sql.") AS A";
			$this->ProcesarSqlConteo($cont,$offset);
			$sql .= "ORDER BY razon_social ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
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
	 /* Funcion donde se consulta las bodegas de la farmacia
      * @return array $datos vector con la informacion. 
      */ 
		
		
		function consultarBodegasAsociacion($empresa)
		{
	
		$sql ="  SELECT 	empresa_id,
							centro_utilidad,
							bodega,
							descripcion,
							estado
				FROM        bodegas
				WHERE      empresa_id= '".$empresa."'" ;
		
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
	 /* Funcion donde se consulta los planes asociados a las farmacias
      * @return array $datos vector con la informacion. 
      */ 
		
		function PlanesAsociadosAFarmacias($empresa,$centro,$bodega)
		{
		
		
			$sql = " select 	b.empresa_id,
								e.razon_social,
								b.centro_utilidad ,
								c.descripcion as centro ,
								b.bodega,
								b.descripcion

					from   bodegas b, 
						   centros_utilidad c,
						   empresas e

					where  e.empresa_id=c.empresa_id
					and    e.empresa_id=b.empresa_id
					and    c.centro_utilidad=b.centro_utilidad
					and    b.empresa_id='".$empresa."'
					and    b.centro_utilidad='".$centro."'
					and    b.bodega='".$bodega."' ";
                   
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
   
	 /* Funcion donde se consulta los planes 
      * @return array $datos vector con la informacion. 
      */ 		
		function consultarPlanes($empresa,$farmacia,$centro,$bodega,$empresa_plan)
		{
		  
			$sql = "   select  plan_id, plan_descripcion
								from  planes where empresa_id='".$empresa_plan."' and  	estado='1' and plan_id not in (Select  plan_id  from  bodegas_farmacia_asoc_formulas where farmacia_id='".$farmacia."' 
                                and centro_utilidad='".$centro."'  and bodega='".$bodega."') order by plan_descripcion" ;
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
	 /* Funcion donde se insertan los planes asociados alas bodegas de la farmacia
      * @return boolean. 
      */ 
		function InsertarPlanes($farmacia,$centro,$bodega,$plan)
		{
			$indice = array();
			$sql = "SELECT NEXTVAL('bodegas_farmacia_asoc_formulas_asociacion_id_seq') AS sq ";

			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
			if(!$rst->EOF)
			{
			$indice = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();     
			}
			$rst->Close(); 

			$sqlerror = "SELECT setval('bodegas_farmacia_asoc_formulas_asociacion_id_seq', ".($indice['sq']-1).") ";    
			$this->ConexionTransaccion();

			$sql  = "INSERT INTO    bodegas_farmacia_asoc_formulas(   ";
			$sql .= "      			asociacion_id, ";
			$sql .= "      			farmacia_id, ";
			$sql .= "      			centro_utilidad, ";
			$sql .= "      			bodega, ";
			$sql .= "     			plan_id, ";
			$sql .= "       		usuario_id,   ";
			$sql .= "         		fecha_registro ";
			$sql .= ")VALUES( ";
			$sql .= "       ".$indice['sq'].", ";
			$sql .= "       '".$farmacia."', ";
			$sql .= "       '".$centro."', ";
			$sql .= "       '".$bodega."', ";
			$sql .= "       ".$plan.", ";
			$sql .= "          ".UserGetUID().", ";
			$sql .= "         NOW() ); ";
			if(!$rst = $this->ConexionTransaccion($sql))
			{
			if(!$rst = $this->ConexionTransaccion($sqlerror)) 
			return false;      
			}    
			else
			{
			$this->Commit();
			return true;
      }
      }
	 /* Funcion donde se la informacion de las farmacias asociadas a los planes
      * @return array $datos vector con la informacion. 
      */ 
	  
	   function  ConsultarInformacionAsociacion($empresa,$centro,$bodega)
	   {
	    
			$sql = "  SELECT 	a.asociacion_id,
								a.farmacia_id,
								a.centro_utilidad,
								a.bodega,
								a.plan_id,
								p.plan_descripcion as plan
                               				
					   FROM     bodegas_farmacia_asoc_formulas a,
					            planes p
					   WHERE    a.plan_id=p.plan_id
					   and      a.farmacia_id = '".$empresa."' 
					   and      a.centro_utilidad='".$centro."' 
					   and      a.bodega= '".$bodega."'
                       order by  p.plan_descripcion 			   ";
					   
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
	 /* Funcion donde se la informacion de las farmacias asociadas a los planes
      * @return array $datos vector con la informacion. */
	   
	   function  ConsultarInformacionAsociacion2($empresa,$centro,$bodega,$planid)
	   {
	     
			$sql = "  SELECT 	a.asociacion_id,
								a.farmacia_id,
								a.centro_utilidad,
								a.bodega,
								a.plan_id,
								p.plan_descripcion as plan
                               				
					   FROM     bodegas_farmacia_asoc_formulas a,
					            planes p
					   WHERE    a.plan_id=p.plan_id
					   and      a.farmacia_id = '".$empresa."' 
					   and      a.centro_utilidad='".$centro."' 
					   and      a.bodega= '".$bodega."' 
                       and     p.plan_id=".$planid."   ";
					   
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
	   
	 /*
		*** Funcion que permite seleccionar el Maximo  Del ultimo contrato con el proveedor
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function SelecMaxcontratacion_prod_id($tipo_id_tercero,$tercero_id,$codigo_proveedor_id)
		{
	
			$sql = "SELECT (MAX(contratacion_prod_id)) AS numero FROM contratacion_produc_proveedor
					WHERE 	tipo_id_tercero='".$tipo_id_tercero."'
					AND     tercero_id='".$tercero_id."'
					AND     codigo_proveedor_id=".$codigo_proveedor_id.";	";
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
	/* SELECCIONAR LA INFORMACION DEL  ULTIMO CONTRATO CON EL PROVEEDOR*/
	
		function InformacionUltimoContratoProveedor($contratacion_prod_id)
		{
	
		  $sql = " SELECT 	contratacion_prod_id,
							empresa_id,
							no_contrato,
							descripcion,
							fecha_inicio,
							fecha_vencimiento,
							tipo_id_tercero,
							tercero_id,
							condiciones_entrega,
							usuario_id,
							codigo_proveedor_id
					FROM 	contratacion_produc_proveedor
					WHERE 	contratacion_prod_id = ".$contratacion_prod_id." ; ";
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

	
	  Function ConsultarTiposProductos()
      {
      
        $sql = " SELECT tipo_producto_id,
                        descripcion
                 FROM    inv_tipo_producto "; 
      
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
		Function ConsultarInformacionContratoPoliticas($empresa,$tipo,$tercero_id,$contratoid,$tipo_producto_id)
		{
	     
	      $sql = " SELECT 	contrato_proveed_politven_id,
							empresa_id,
							tipo_id_tercero,
							tercero_id,
							contratacion_prod_id,
							tipo_producto_id,
							politica_descripcion
					FROM    contrato_proveed_politicas_vencimientos
					WHERE 	empresa_id = '".$empresa."'
					AND 	tipo_id_tercero= '".$tipo."' 
					AND     tercero_id = '".$tercero_id."' 
					AND     contratacion_prod_id= ".$contratoid."
                    and     tipo_producto_id=".$tipo_producto_id."	";
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
	/*
      * Funcion permite modificar las politicas de vencimiento
      * @return array $datos vector con la informacion. 
      */
		
		function ActualizarPoliticasVencimiento($empresa,$tipo,$tercero_id,$contratoid,$informacion,$tipo_producto_id)
		{
			$this->ConexionTransaccion();
			$sql  = "UPDATE   contrato_proveed_politicas_vencimientos 
			         set      politica_descripcion = '".$informacion."'
			     	WHERE 	empresa_id = '".$empresa."'
					AND 	tipo_id_tercero= '".$tipo."' 
					AND     tercero_id = '".$tercero_id."' 
					AND     contratacion_prod_id= ".$contratoid."
                    and 	tipo_producto_id=".$tipo_producto_id."	"; 
				if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
				return $nucontrato;
		}
		
		  /* Funcion donde se ingresan las politicas de vencimiento a un contrato
      * @return boolean 
      */
    function IngresarPoliticaVencimiento($contratoid,$tipoter,$idterce,$textarea,$tipo_producto_id,$empresa)
      {
   
      $indice = array();
      $sql = "SELECT NEXTVAL('contrato_proveed_politicas_ven_contrato_proveed_politven_id_seq') AS sq ";
      if(!$rst = $this->ConexionBaseDatos($sql)) 
      return false;
      if(!$rst->EOF)
      {
      $indice = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();     
      }
      $rst->Close(); 
      $sqlerror = "SELECT setval('contrato_proveed_politicas_ven_contrato_proveed_politven_id_seq', ".($indice['sq']-1).") ";    
      $this->ConexionTransaccion();

      $sql  = "INSERT INTO contrato_proveed_politicas_vencimientos ( ";
      $sql .= "       contrato_proveed_politven_id, ";
      $sql .= "       empresa_id, ";
      $sql .= "       tipo_id_tercero, ";
      $sql .= "       tercero_id, ";
      $sql .= "       contratacion_prod_id, ";
      $sql .= "       tipo_producto_id, ";
      $sql .= "      politica_descripcion, ";
      $sql .= "       usuario_id, ";
      $sql .= "       fecha_registro ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       '".$empresa."', ";
      $sql .= "       '".$tipoter."', ";
      $sql .= "       '".$idterce. "', ";
      $sql .= "       ".$contratoid. ", ";
	  $sql .= "       ".$tipo_producto_id. ", ";
      $sql .= "       '".$textarea. "', ";
      $sql .= "          ".UserGetUID().", ";
      $sql .= "         NOW() ";
      $sql .= "       ) ";

      if(!$rst = $this->ConexionTransaccion($sql))
      {
      if(!$rst = $this->ConexionTransaccion($sqlerror)) 
      return false;      
      }    
      else
      {
      $this->Commit();
      return true;
      }
      }
	  /*
      * Funcion permite consultar la informadion sobre los dias de envio
      * @return array $datos vector con la informacion. 
      */
		function consultarInformacionevio($empresa,$contratoid,$tipo_producto_id)
		{
			$sql= " SELECT  contrato_prod_detalle_envio_id,
							empresa_id,
							contratacion_prod_id,
							tipo_producto_id,
							dias_envio
					FROM    contrato_prod_detalle_envios
					WHERE   empresa_id = '".$empresa."' 
					AND     contratacion_prod_id = '".$contratoid."' 
					AND     tipo_producto_id = '".$tipo_producto_id."' ";
					
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
		/*
      * Funcion permite modificar las informcion de los dias de envio  
      * @return array $datos vector con la informacion. 
      */
		function ActualizarDiasenvio($empresa,$contratoid,$dias,$tipo_producto_id)
		{
			$this->ConexionTransaccion();
			$sql  = "UPDATE   contrato_prod_detalle_envios 
			         set      dias_envio = ".$dias."
			     	WHERE 	empresa_id = '".$empresa."'
					AND     contratacion_prod_id = '".$contratoid."' 
					AND     tipo_producto_id = '".$tipo_producto_id."' "; 
				if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
				return $nucontrato;
		}
	/*
      * Funcion donde se ingresan los dias de envio del contrato.
      * @return boolean
      */
      function IngresarDiasEnvioContrato($empresa,$noidcontrato,$tipo,$inf)
      {
    
     
      $indice = array();
      $sql = "SELECT NEXTVAL('contrato_prod_detalle_envios_contrato_prod_detalle_envio_id_seq') AS sq ";

      if(!$rst = $this->ConexionBaseDatos($sql)) 
      return false;
      if(!$rst->EOF)
      {
      $indice = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();     
      }
      $rst->Close(); 

      $sqlerror = "SELECT setval('contrato_prod_detalle_envios_contrato_prod_detalle_envio_id_seq', ".($indice['sq']-1).") ";    
      $this->ConexionTransaccion();


      $sql  = "INSERT INTO contrato_prod_detalle_envios( ";
      $sql .= "      contrato_prod_detalle_envio_id, ";
      $sql .= "       empresa_id, ";
      $sql .= "       contratacion_prod_id, ";
      $sql .= "       tipo_producto_id, ";
      $sql .= "       dias_envio, ";
      $sql .= "       usuario_id, ";
      $sql .= "       fecha_registro ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       '".$empresa."', ";
      $sql .= "       ".$noidcontrato.", ";
      $sql .= "       '".$tipo. "', ";
      $sql .= "       ".$inf. ", ";
      $sql .= "          ".UserGetUID().", ";
      $sql .= "         NOW() ";

      $sql .= "       ) ";

      if(!$rst = $this->ConexionTransaccion($sql))
      {
      if(!$rst = $this->ConexionTransaccion($sqlerror)) 
      return false;      
      }    
      else
      {
      $this->Commit();
      return true;
      }
      }
	 /**
      * Funcion donde se alamcena la Actualizacion de  la informacion  del Contrato
      * @param array $request vector con la informacion del request
      * @param string $nucontrato cadena con el  numero del contrato
      * @return array $nucontrato vector con la informacion actualizada del contrato. 
      */   
    function ActualizarInformacionContrato($request, $contratacion_prod_id)
    {
  

      $datos = array();
      $datos['fecha_inicio'] = $request['fecha_inicio'];
      $fdatos=explode("-", $datos['fecha_inicio']);
      $fedatos= $fdatos[2]."/".$fdatos[1]."/".$fdatos[0];

      $dtos = array();
      $dtos['fecha_final'] = $request['fecha_final'];
      $fdtos=explode("-", $dtos['fecha_final']);
      $fecdtos= $fdtos[2]."/".$fdtos[1]."/".$fdtos[0];

      $this->ConexionTransaccion();

      $sql  = "UPDATE   contratacion_produc_proveedor ";
      $sql .= "SET     No_Contrato= '".$request['txtncontrato']."', ";
      $sql .= "       Descripcion ='".$request['desc']."', ";
      $sql .= "       Fecha_Inicio= '".$fedatos. "', ";
      $sql .= "       Fecha_Vencimiento='".$fecdtos. "', ";
      $sql .= "       Condiciones_entrega= '".$request['condtiemp']."', ";
      $sql .= "       usuario_id= ".UserGetUID().",";
      $sql .= "       fecha_registro=  NOW(), ";
      $sql .= "      observaciones='".$request['observar']."' ";
      $sql .= " Where ";
      $sql .= "	contratacion_prod_id =".$contratacion_prod_id.";";
      
      if(!$rst1 = $this->ConexionTransaccion($sql))
      {
      return false;
      }

      $this->Commit();

      return $nucontrato;
      }
	  
	  /*
      * Funcion permite  consultar la informacion completa de los productos asociados al contrato .
      * @return array $datos vector con la informacion. 
      */
	  
		function ConsultarInformacionDetalleProductos($contratacion_prod_id)
		{
	  
			$sql =   " SELECT  d.contrato_produc_prov_det_id,
								d.empresa_id,
								d.contratacion_prod_id,
								d.codigo_producto,
								d.precio,
								d.valor_pactado,
								d.valor_porcentaje,
								d.valor_total_pactado,
								p.descripcion,
								p.cantidad,
								u.descripcion
									
			FROM    contratacion_produc_prov_detalle d,
			        inventarios_productos p,
					unidades u
			where   d.codigo_producto=p.codigo_producto
			and     p.unidad_id=u.unidad_id
			and     d.contratacion_prod_id=".$contratacion_prod_id." ";
			
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
      /*
      * Funcion permite  consultar la informacion de las politicas de vencimiento del contrato 
      * @return array $datos vector con la informacion. 
      */
    function ConsultarPoliticas($terceroid)
    {
   
      $sql = " SELECT descripcion  
              FROM    inv_terceros_proveedores_politicasdevolucion
              WHERE   tercero_id = '".$terceroid."' ";
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
    /*Funcion que permite consultar las empresas que tienen planes 
	* @return array $datos vector que contiene la informacion*/
	
	function Consultar_Empresa_Planes()
	{
		$sql ="  SELECT DISTINCT P.empresa_id,
		                         E.razon_social
				 FROM            planes P,
				                 empresas E 
				 WHERE           P.empresa_id=E.empresa_id  order by E.razon_social ";
				 
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
	  /* Funcion que permite Eliminar los productos que ya no se desean devolver
          return array con la informacion */
      
      function Eliminar_producto_planes($plan_id,$farmacia,$centro,$bodega)
	   {
	  
      $sql = " DELETE   from  bodegas_farmacia_asoc_formulas     
               WHERE    plan_id= '".$plan_id."' 
               and      farmacia_id='".$farmacia."' 
               and      centro_utilidad='".$centro."'
               and      bodega='".$bodega."' ";
              

	   
	       if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return true;
		}
    

	}
?>