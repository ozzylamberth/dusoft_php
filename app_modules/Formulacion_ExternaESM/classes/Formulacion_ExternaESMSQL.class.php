<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: 	Formulacion_ExternaESMSQL.class.php,v 1.24 
	* @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/

	class Formulacion_ExternaESMSQL extends ConexionBD
	{
	/*
	* Constructor de la clase
	*/
	function Formulacion_ExternaESMSQL(){}
	
	/**
	* Funcion donde se verifica el permiso del usuario 
	** @return array $datos vector que contiene la informacion de la consulta
	*/
		
		function ObtenerPermisos()
		{
		
          $sql  = "SELECT a.empresa_id, ";
          $sql .= "       b.razon_social AS razon, ";
          $sql .= "       a.centro_utilidad, ";
          $sql .= "       c.descripcion AS centro, ";
          $sql .= "       a.usuario_id ";
          $sql .= "FROM 	userpermisos_Formulacion_Externa a, ";
          $sql .= "       empresas b, ";
          $sql .= "       centros_utilidad c ";
          $sql .= "WHERE  a.usuario_id = ".UserGetUID()."  ";
          $sql .= "AND 	  a.empresa_id = b.empresa_id ";
          $sql .= "AND 	  a.empresa_id = c.empresa_id ";
          $sql .= "AND 	  a.centro_utilidad = c.centro_utilidad ";
          $sql .= "AND      b.sw_activa = '1' ";
          $sql .= "AND      a.sw_activo = '1' ";
        
          
          
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
    * Funcion donde se elimina los temporales de los suministros 
    ** @return array $datos vector que contiene la informacion de la consulta
    */
		function Eliminar_tmp_suministro($dx)
		{
	 
        $sql = " Delete     FROM  esm_formula_suministro_tmp ";
        $sql .= "where  	formula_suministro_id_tmp='".$dx."' 
                       and        usuario_id=".UserGetUID()."
              ";
            
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
    * Funcion donde se obtiene el permiso de formulacion 
    ** @return array $datos vector que contiene la informacion de la consulta
    */
    function ObtenerPermisos_FORMULACION($empresa)
		{
		
            $sql  = "SELECT   	empresa_id,
                                usuario_id,
                                sw_activo,
                                sw_privilegios,
                                bodega,
                                centro_utilidad
            FROM     userpermisos_digitalizacion
            WHERE    empresa_id = '".$empresa."'
            AND      usuario_id = ".UserGetUID()." 
            AND      sw_activo='1'				";
		
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
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
    * Funcion donde se obtiene el permiso de suministros 
    ** @return array $datos vector que contiene la informacion de la consulta
    */
    
    function ObtenerPermisos_SUMINISTROS($empresa)
		{
		
        $sql  = "SELECT   	U.empresa_id,
                            U.centro_utilidad,
                            U.bodega,
                            U.usuario_id,
                            B.descripcion as bodegas
                            
                  FROM     userpermisos_esm_suministros as U,
                           bodegas AS B
                  WHERE  U.empresa_id=B.empresa_id
                  AND    U.centro_utilidad=B.centro_utilidad
                  AND    U.bodega=B.bodega
                  AND  U.empresa_id = '".$empresa."'
                  AND      U.usuario_id = ".UserGetUID()." 
                  AND   B.sw_bodega_satelite='1'			";
          
          if(!$rst = $this->ConexionBaseDatos($sql))
          return false;
          $datos = array();
          while(!$rst->EOF)
          {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
          }

          $rst->Close();
          return $datos;
		}
      
	/*
		* Funcion donde se Consultan los diferentes tipos de identificacion.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ConsultarTipoId()
		{
          $sql  = "SELECT    tipo_id_tercero, descripcion ";
          $sql .= "FROM      tipo_id_terceros ";
          $sql .= "ORDER BY  tipo_id_tercero ";
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
		* Funcion donde se Consultan los Pacientes.
		* @return array $datos vector que contiene la informacion de la consulta.
    */
	
  	function Consultar_Datospacientes($filtros,$offset)
		{
	      
	    if($filtros)
      {
           $sql  = "SELECT  DISTINCT  PA.paciente_id,
                        PA.tipo_id_paciente,
                        PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos,
                        PA.primer_nombre||' '||PA.segundo_nombre AS nombres,
                        to_char(PA.fecha_nacimiento,'dd-mm-yyyy') as fecha_nacimiento,
                        PA.residencia_direccion,
                        PA.residencia_telefono,
                        PA.sexo_id,
                        edad(PA.fecha_nacimiento) as edad 
                FROM            pacientes PA, tipos_id_pacientes TIPOS
                WHERE  		    PA.tipo_id_paciente= TIPOS.tipo_id_paciente			";
			
          if($filtros)
			    {
              if($filtros['tipo_id_paciente']!= '-1' && $filtros['tipo_id_paciente']!= '')
              $sql .= " AND    PA.tipo_id_paciente = '".$filtros['tipo_id_paciente']."' ";
              if($filtros['paciente_id'])
              $sql .= " AND   PA.paciente_id = '".$filtros['paciente_id']."' ";

              if($filtros['nombres'] || $filtros['apellidos'])
              {
                $util = AutoCarga::factory('ClaseUtil');
                $sql .= "AND      ".$util->FiltrarNombres($filtros['nombres'],$filtros['apellidos'],"PA");
              }
				}			
          if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",$offset))
          return false;

          $sql .= "ORDER BY apellidos,nombres ";
          $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
            
          if(!$rst = $this->ConexionBaseDatos($sql.$whr,null)) return false;
          $datos = array();
          while (!$rst->EOF)
          {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
          }
          $rst->Close();
          return $datos;  
        }		
		}
    /*
		* Funcion donde se Consultan las instituciones.
		* @return array $datos vector que contiene la informacion de la consulta.
    */
		function consultar_Instituciones()
		{
		   
			$sql = " SELECT  ESM.tipo_id_tercero,
                      ESM.tercero_id,
                      nombre_tercero
								FROM  esm_empresas ESM,
                      Terceros TER
								WHERE   ESM.tipo_id_tercero=TER.tipo_id_tercero
								AND     ESM.tercero_id=TER.tercero_id
								ORDER BY  nombre_tercero ";
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
		* Funcion donde se Consultan el identificador de la formula  segun el numero de la formula en papel.
		* @return array $datos vector que contiene la informacion de la consulta.
    */
  
    function Consultar_Identificador_formula($formula_papel,$tipo,$paciente_id)
    {
      $sql = " 	SELECT  formula_id
                FROM 	  esm_formula_externa
                WHERE 	formula_papel = '".$formula_papel."' 
				       	AND 	  tipo_id_paciente = '".$tipo."'
                AND     paciente_id= '".$paciente_id."' ";
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
		* Funcion donde se Consultan todos los medicamentos dispensado con lote y fecha de vencimiento.
		* @return array $datos vector que contiene la informacion de la consulta.
    */
   
    function Medicamentos_Dispensados_Esm_x_lote_total($formula_id)
		{
		
			$sql = " 
                select SUM(a.numero_unidades) as numero_unidades,
                        a.codigo_producto,a.fecha_vencimiento,a.lote,
                        a.tiempo_tratamiento,a.descripcion_prod,
                        a.unidad_tiempo_tratamiento,
                        a.sw_pactado,
                        fc_codigo_mindefensa(a.codigo_producto) as codigo_producto_mini,
                        fc_descripcion_producto_molecula(a.codigo_producto) as molecula
                      from (
                            select
                                          dd.codigo_producto,
                                          SUM(dd.cantidad) as numero_unidades,
                                          dd.fecha_vencimiento ,
                                          dd.lote,
                                          fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
                                          med.tiempo_tratamiento,
                                          med.unidad_tiempo_tratamiento,
                                          dd.sw_pactado
                                FROM					
                                            esm_formulacion_despachos_medicamentos as dc,
                                            bodegas_documentos as d,
                                            bodegas_documentos_d AS dd  left join esm_formula_externa_medicamentos med ON(med.formula_id =".$formula_id." and dd.codigo_producto=med.codigo_producto )
                                WHERE	      dc.bodegas_doc_id = d.bodegas_doc_id
                                  and       dc.numeracion = d.numeracion
                                  and       dc.formula_id =".$formula_id."
                                  and       d.bodegas_doc_id = dd.bodegas_doc_id
                                  and       d.numeracion = dd.numeracion
                                  group by  dd.codigo_producto,
                                            dd.cantidad,
                                            dd.fecha_vencimiento ,
                                            dd.lote,
                                            med.tiempo_tratamiento,
                                            med.unidad_tiempo_tratamiento,
                                            dd.sw_pactado
            UNION
                  SELECT   dd.codigo_producto,
                            SUM(dd.cantidad) as numero_unidades,
                            dd.fecha_vencimiento , 
                            dd.lote,
                            fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
                            med.tiempo_tratamiento,
                            med.unidad_tiempo_tratamiento,
                            dd.sw_pactado
                  FROM    	esm_formulacion_despachos_medicamentos_pendientes tmp,
                            bodegas_documentos as d, 
                            bodegas_documentos_d AS dd  left join esm_formula_externa_medicamentos med ON(med.formula_id = ".$formula_id." and dd.codigo_producto=med.codigo_producto )

                WHERE 	tmp.bodegas_doc_id = d.bodegas_doc_id 
                and     tmp.numeracion = d.numeracion 
                and     d.bodegas_doc_id = dd.bodegas_doc_id 
                and     d.numeracion = dd.numeracion 
                and     tmp.formula_id =".$formula_id."  
                group by dd.codigo_producto,
                          dd.cantidad,
                          dd.fecha_vencimiento ,
                          dd.lote,
                          med.tiempo_tratamiento,
                          med.unidad_tiempo_tratamiento,
                          dd.sw_pactado
                )as a
                group by  a.codigo_producto,a.fecha_vencimiento,a.lote,
                          a.tiempo_tratamiento,a.descripcion_prod,
                          a.unidad_tiempo_tratamiento,
                          a.sw_pactado					  ";
                    
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
		* Funcion donde se ingresan los datos basicos del reporte de farmacovigilancia.
		* @return array $datos vector que contiene la informacion de la consulta.
    */
   
	  function ingreso_farmacovigilancia($request)
		{
	     
          $this->ConexionTransaccion();
                      
          $inst=$request['institucion'];
          $institucion = explode("#", $inst);
          $esm_tipo_id_tercero=$institucion[0];
          $esm_tercero_id=$institucion[1];
          
          $fecha_notifica=explode("-", $request['fecha_notifica']);
          $fecha_notifi= $fecha_notifica[2]."-".$fecha_notifica[1]."-".$fecha_notifica[0];
          
          $fecha_sospecha=explode("-", $request['fecha_sospecha']);
          $fecha_sospe= $fecha_sospecha[2]."-".$fecha_sospecha[1]."-".$fecha_sospecha[0];
									
        $sql  = "INSERT INTO esm_farmaco_vigilancia( 
                            esm_farmaco_id,		
                            esm_tipo_id_tercero,		
                            esm_tercero_id,		
                            fecha_notificacion,		
                            formula_papel,		
                            tipo_id_paciente,		
                            paciente_id,		
                            fecha_sospecha,	
                            observacion,		
                            diagnostico,		
                            usuario_id, 
                            reaccion_adversa
						)VALUES( 
						        nextval('esm_farmaco_vigilancia_esm_farmaco_id_seq'),
								'".$esm_tipo_id_tercero."', 
								'".$esm_tercero_id."', 
								'".$fecha_notifi."', 
								'".$request['formula']."',
								'".$request['tipo_id_paciente']."',
								'".$request['paciente_id']."',
								'".$fecha_sospe."',
								'".$request['observaciones']."',
								'".$request['diagnostico']."',
								".UserGetUID().",
								'".$request['reacciones']."'
								
							  ) ";
					if(!$rst = $this->ConexionTransaccion($sql))
					{
					return false;
					}
					$this->Commit();
										
				$sql2= " SELECT MAX(esm_farmaco_id) as esm_farmaco_id FROM  esm_farmaco_vigilancia
				         WHERE   formula_papel ='".$request['formula']."' 
                 AND     tipo_id_paciente = '".$request['tipo_id_paciente']."'
                 AND     paciente_id =  '".$request['paciente_id']."' 
                 AND     usuario_id =".UserGetUID()." ";
	
						if(!$rst = $this->ConexionBaseDatos($sql2))
						return false;
						$datos = array();
						while(!$rst->EOF)
						{
						$datos = $rst->GetRowAssoc($ToUpper);
						$rst->MoveNext();
						}
						$rst->Close();
						return $datos;
			
		}
   	/*
		* Funcion donde se ingresan los datos basicos del reporte de farmacovigilancia.
		* @return boolean.
    */
   
    function Ingreso_Farmacovigilancia_id($esm_farmaco_id,$observa,$fecha_in,$fecha_fin,$producto,$lote,$fecha,$dosis)
		{
			
         $this->ConexionTransaccion();
         if(!empty($observa)){
            $observas= " ,indicacion_motivo,";
            $observa=" ,'".$observa."', ";
          }else
          { 
              $observas= " ,";
             $observa=" ," ;
          }
          
           if(!empty($fecha_in)){
            $fecha_inici=explode("-", $fecha_in);
            $fecha_ini= $fecha_inici[2]."-".$fecha_inici[1]."-".$fecha_inici[0];
            
            $fechai=" fecha_inicio, ";
            $fecha_i=" '".$fecha_ini."', ";
          }else
          {
            $fechai=" , ";
             $fecha_i=" ," ;
          }
          
          if(!empty($fecha_fin)){
					$fecha_fina=explode("-", $fecha_fin);
					$fecha_f= $fecha_fina[2]."-".$fecha_fina[1]."-".$fecha_fina[0];
					
					$fechaf=" fecha_finalizacion ";
					$fecha_fin=" '".$fecha_f."' ";
          }else
          {
            $fechaf=" ";
					 $fecha_fin=" " ;
          }
					
             
            $sql = "INSERT INTO esm_farmaco_vigilancia_d
                (
                    esm_farmaco_d_id,		
                    esm_farmaco_id,		
                    codigo_medicamento,
                    frecuencia,
                    fecha_vencimiento,
                    lote						 
                    $observas
                    $fechai
                    $fechaf
                    
                )
                  VALUES
                  (
                    NEXTVAL('esm_farmaco_vigilancia_d_esm_farmaco_d_id_seq'),
                     '".$esm_farmaco_id."',
                    '".$producto."',
                    '".$dosis."',
                    '".$fecha."',
                    '".$lote."'
                    $observa
                    $fecha_i
                    $fecha_fin
                    );
                    ";
			
			  if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
				return true;
		}
   	/*
		* Funcion donde se consulta la informacion basica del reporte de farmacovigilancia.
		* @return boolean.
    */
  	function consulta_Informacion($filtros,$offset)
		{
        $sql = "SELECT 	 VIG.esm_farmaco_id,
                          VIG.esm_tipo_id_tercero,
                          VIG.esm_tercero_id,
                          To_char(VIG.fecha_notificacion,'DD-MM-YYYY') AS fecha_notificacion,
                          VIG.formula_papel,
                          VIG.tipo_id_paciente,
                          VIG.paciente_id,
                          To_char(VIG.fecha_sospecha,'DD-MM-YYYY') AS fecha_sospecha,
                          VIG.observacion,
                          VIG.diagnostico,
                          VIG.usuario_id,
                          PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos,
                          PA.primer_nombre||' '||PA.segundo_nombre AS nombres,
                          to_char(PA.fecha_nacimiento,'dd-mm-yyyy') as fecha_nacimiento,
                          PA.residencia_direccion,
                          PA.residencia_telefono,
                          PA.sexo_id,
                          edad(PA.fecha_nacimiento) as edad,
                          TER.nombre_tercero,
                          MP.municipio,
                          TD.departamento,
                          TP.pais,
                          USU.nombre,
                          USU.descripcion
                                  
              FROM 	   esm_farmaco_vigilancia  VIG,
                        pacientes PA,
                        esm_empresas ESME,
                        terceros TER,
                        tipo_mpios MP,
                        tipo_dptos TD,
                        tipo_pais TP,
                        system_usuarios USU
                  
                
            WHERE   VIG.tipo_id_paciente=PA.tipo_id_paciente
            AND     VIG.paciente_id=PA.paciente_id
            AND     VIG.esm_tipo_id_tercero=ESME.tipo_id_tercero
            and     VIG.esm_tercero_id=ESME.tercero_id
            AND     ESME.tipo_id_tercero=TER.tipo_id_tercero
            AND     ESME.tercero_id=TER.tercero_id
            AND     TER.tipo_pais_id=MP.tipo_pais_id
            AND     TER.tipo_dpto_id=MP.tipo_dpto_id
            AND     TER.tipo_mpio_id=MP.tipo_mpio_id
            AND     MP.tipo_pais_id=TD.tipo_pais_id
            AND     MP.tipo_dpto_id=TD.tipo_dpto_id
            AND     TD.tipo_pais_id=TP.tipo_pais_id 
            AND     VIG.usuario_id=USU.usuario_id ";
            $FechaI=$filtros['fecha_inicio'];
            $FechaF=$filtros['fecha_final'];
            
            
            $fdatos=explode("-", $FechaI);
            $fedatos= $fdatos[2]."-".$fdatos[1]."-".$fdatos[0];
          
            $fdtos=explode("-", $FechaF);
            $fecdtos= $fdtos[2]."-".$fdtos[1]."-".$fdtos[0];
          if(!empty($FechaI) && (empty($FechaF)))
          {
            $sql.=" AND VIG.fecha_registro = '".$fedatos." 00:00:00' ";

            
          }else
          {
            if($$filtros['fecha_inicio'] && $filtros['fecha_final'])
            {
                  $sql.=" AND VIG.fecha_registro >= '".$fedatos." 00:00:00'  AND   VIG.fecha_registro <= '".$fecdtos." 24:00:00'";
            }
            
             if($filtros['esm_farmaco_id'])
            {
                  $sql.=" AND VIG.esm_farmaco_id=".trim($filtros['esm_farmaco_id'])." ";
            }
            
            if($filtros['paciente_id'])
            {
                  $sql.=" AND VIG.paciente_id='".trim($filtros['paciente_id'])."' ";
            }
            if($filtros['tipo_id_paciente'])
            {
                  $sql.=" AND VIG.tipo_id_paciente='".trim($filtros['tipo_id_paciente'])."' ";
            }
            
          if($filtros['nombre'])
            {
                  $sql.=" AND PA.primer_nombre||' '||PA.segundo_nombre ILIKE '%".$filtros['nombre']."%' ";
            }
          
          
          }
            $cont= "   select COUNT(*) from (".$sql.") AS A";
            $sql .= "  ORDER by   VIG.esm_farmaco_id DESC ";
            $this->ProcesarSqlConteo($cont,$offset);
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
		* Funcion donde se consulta el plan parametrizado
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ConsultarPlan_Parametrizado()
		{
        $sql  = "SELECT PR.plan_id,
            P.plan_descripcion
            FROM  esm_parametros_contrato PR,
            Planes P

            WHERE   PR.plan_id=P.plan_id 
            AND     PR.sw_estado='1' ";
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
		* Funcion donde se consulta los productos dispensados spor fecha
		* @return array $datos vector que contiene la informacion de la consulta.
    */
		function Consultar_Productos_Dispensados_por_Fechas($tipo,$identificacion,$fecha_inicio,$fecha_fin)
		{
          $sql = "   select
		            *
		            from (  		
                          select
                                    fc_descripcion_producto_alterno(bdd.codigo_producto) as descripcion,
                                    efe.formula_papel,
                                    bdd.codigo_producto,
                                    bdd.total_costo,
                                    (bdd.cantidad-bdd.cantidad_devuelta) as cantidad,
                                    bdd.lote,
                                    bdd.fecha_vencimiento,
                                    bdd.sw_pactado,
                                    fc_descripcion_producto_molecula(bdd.codigo_producto) as molecula,
                                    fc_codigo_mindefensa(bdd.codigo_producto) as min_defensa
                          from
                                    esm_formula_externa efe 
                                    JOIN esm_formulacion_despachos_medicamentos efdm ON (efe.formula_id = efdm.formula_id)
                                    JOIN bodegas_documentos bd ON (efdm.bodegas_doc_id = bd.bodegas_doc_id and  efdm.numeracion = bd.numeracion)
                                    JOIN bodegas_documentos_d bdd ON (bd.bodegas_doc_id = bdd.bodegas_doc_id and   bd.numeracion = bdd.numeracion)
                                    JOIN bodegas_doc_numeraciones bdn ON (bd.bodegas_doc_id=bdn.bodegas_doc_id)
                                    JOIN inventarios_productos invp ON (bdd.codigo_producto = invp.codigo_producto)
		                where
                                      efe.tipo_id_paciente = '".$tipo."'
                                and   efe.paciente_id = '".$identificacion."'
                                and  (bdd.cantidad-bdd.cantidad_devuelta)>0
                                and   bd.fecha_registro >= '".$fecha_inicio." 00:00:00' 
                                and   bd.fecha_registro <= '".$fecha_fin." 24:00:00' 
						
						UNION
						select
                            fc_descripcion_producto_alterno(bdd.codigo_producto) as descripcion,
                            efe.formula_papel,
                            bdd.codigo_producto,
                            bdd.total_costo,
                            (bdd.cantidad-bdd.cantidad_devuelta) as cantidad,
                            bdd.lote,
                            bdd.fecha_vencimiento,
                            bdd.sw_pactado,
                            fc_descripcion_producto_molecula(bdd.codigo_producto) as molecula,
                            fc_codigo_mindefensa(bdd.codigo_producto) as min_defensa
            from
                    esm_formula_externa efe JOIN esm_formulacion_despachos_medicamentos_pendientes efdm ON (efe.formula_id = efdm.formula_id)
                    JOIN bodegas_documentos bd ON (efdm.bodegas_doc_id = bd.bodegas_doc_id and  efdm.numeracion = bd.numeracion)
                    JOIN bodegas_documentos_d bdd ON (bd.bodegas_doc_id = bdd.bodegas_doc_id and   bd.numeracion = bdd.numeracion)
                    JOIN bodegas_doc_numeraciones bdn ON (bd.bodegas_doc_id=bdn.bodegas_doc_id)
                    JOIN inventarios_productos invp ON (bdd.codigo_producto = invp.codigo_producto)
						where
                    efe.tipo_id_paciente = '".$tipo."'
                    and     efe.paciente_id = '".$identificacion."'

                    and     (bdd.cantidad-bdd.cantidad_devuelta)>0
                    and   bd.fecha_registro >= '".$fecha_inicio." 00:00:00' 
                    and   bd.fecha_registro <= '".$fecha_fin." 24:00:00'
						
						 ) AS A ";
						 
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
    * Obtiene la informacion de un afiliado determinado
    * @param array $datos Vector con la informacion del tipo e identificacion
    * del afiliado
    * @return array
    */
    function ObtenerDatosAfiliados($datos)
    {
	 
        $sql  = "SELECT AD.afiliado_tipo_id AS tipo_id_paciente , ";
        $sql .= "       AD.afiliado_id AS paciente_id, ";
        $sql .= "       AD.primer_apellido    , ";
        $sql .= "       AD.segundo_apellido   , ";
        $sql .= "       AD.primer_nombre  , ";
        $sql .= "       AD.segundo_nombre     , ";
        $sql .= "       AD.fecha_nacimiento, ";
        $sql .= "       AD.tipo_sexo_id   , ";
        $sql .= "       AD.tipo_pais_id   , ";
        $sql .= "       AD.tipo_dpto_id   , ";
        $sql .= "       AD.tipo_mpio_id   , ";
        $sql .= "       AD.zona_residencia    , ";
        $sql .= "       AD.direccion_residencia   , ";
        $sql .= "       AD.telefono_residencia, ";
        $sql .= "       TO_CHAR(AD.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
        $sql .= "        edad_completa(AD.fecha_nacimiento) as edad, ";
        $sql .= " 	edad(AD.fecha_nacimiento) as edad_s, ";
        $sql .= "       AF.plan_atencion,";
        $sql .= " 	    AF.tipo_afiliado_atencion,";
        $sql .= " 	    AF.rango_afiliado_atencion, ";
        $sql .= " 	    PL.plan_descripcion ";
        $sql .= "FROM   eps_afiliados_datos AD,";
        $sql .= "       eps_afiliados AF, ";
        $sql .= "       planes PL ";
        $sql .= "WHERE  AD.afiliado_tipo_id = '".$datos['tipo_id_paciente']."' ";
        $sql .= "AND    AD.afiliado_id = '".$datos['paciente_id']."' ";
        $sql .= "AND    AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
        $sql .= "AND    AD.afiliado_id = AF.afiliado_id ";
        $sql .= "AND    AF.estado_afiliado_id IN ('AC') ";
        $sql .= "AND    AF.plan_atencion = PL.plan_id ";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
	  /**
    * Funcion para obtener la informacion del plan
    * @param integer $plan Identificador del plan
    * @return array
    */
    function ObtenerInformacionPlan($plan)
    {
   
          $sql  = "SELECT plan_id,";
          $sql .= "       plan_descripcion,";
          $sql .= "       sw_afiliados, ";
          $sql .= "       sw_tipo_plan ";
          $sql .= "FROM   planes ";
          $sql .= "WHERE  plan_id = ".$plan." "; 
          $sql .= "ORDER BY plan_descripcion ";
          
      if(!$result = $this->ConexionBaseDatos($sql,__LINE__))
        return false;

      $datos = array();
      if (!$result->EOF) 
      {
        $datos = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
      }
      $result->Close();
      return $datos;
    }
     /**
    * Funcion para validar si exite el afiliado
    * @return array
    */
   function ObtenerDatosPlanAfiliado($tipo_id_afiliado,$afiliado)
    {
        $sql = " SELECT afiliado_tipo_id,afiliado_id
                 FROM    eps_afiliados
                WHERE   afiliado_tipo_id = '".$tipo_id_afiliado."' 
                AND afiliado_id ='".$afiliado."'  ";
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
		* Funcion donde se borra los medicamentos temporales de la formulacion
		* @return array $datos vector que contiene la informacion de la consulta.
    */
		 function Eliminar_POS_tmp($tipopaciente,$paciente_id)
		{
			
			$sql .= " Delete     FROM  esm_formula_externa_medicamentos_tmp 
			        where  	      usuario_id=".UserGetUID()."   and  tipo_id_paciente = '".$tipopaciente."'  and paciente_id='".$paciente_id."' ";
						
		    	
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
		* Funcion donde se borra los diagnosticos temporales de la formulacion
		* @return array $datos vector que contiene la informacion de la consulta.
    */
	
    function Eliminar_DXT_tmp($tipopaciente,$paciente_id)
		{
			
        $sql .= " Delete     FROM  esm_formula_externa_diagnosticos_tmp 
                  where  	     usuario_id=".UserGetUID()." 
                  and          tipo_id_paciente = '".$tipopaciente."'  and paciente_id='".$paciente_id."' ";

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
		* Funcion donde se borra la cabecera temporal de la formulacion
		* @return array $datos vector que contiene la informacion de la consulta.
    */
	  function Eliminar_cabec_tmp($tipopaciente,$paciente_id)
		{
			
			$sql .= " Delete     FROM  esm_formula_externa_tmp 
                where  	     usuario_id=".UserGetUID()." 
                and          tipo_id_paciente = '".$tipopaciente."'  and paciente_id='".$paciente_id."' ";
						
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
		* Funcion donde se verifica si el paciente se encuentra en proceso de digitalizacion o trancripcion de formula
		* @return array $datos vector que contiene la informacion de la consulta.
    */
	
    function Validar_Paciente_tmp($datos)
    {
      $sql = " SELECT     TMP.tmp_formula_id,
                          TMP.tmp_empresa_id,
                          TMP.tmp_formula_papel,
                          SYS.nombre,
                          SYS.usuario
                FROM      esm_formula_externa_tmp TMP,
                          system_usuarios SYS
                WHERE     TMP.tipo_id_paciente =  '".$datos['tipo_id_paciente']."'
                AND       TMP.paciente_id ='".$datos['paciente_id']."' 
                AND       TMP.usuario_id=SYS.usuario_id ";
            
				 if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			      $datos = array();
			      while(!$rst->EOF)
			      {
			        $datos = $rst->GetRowAssoc($ToUpper = false);
			        $rst->MoveNext();
			      }
			      $rst->Close();
			      return $datos;
				
	}
	 /*
		* Funcion donde se consultan los tipos de formulas
		* @return array $datos vector que contiene la informacion de la consulta.
    */
		function  Consultar_Tipos_Formulas()
    {
        $sql = " 	SELECT tipo_formula_id,
                          descripcion_tipo_formula 
                  FROM   esm_tipos_formulas
                  WHERE  sw_estado = '1'  order by tipo_formula_id ASC  ";
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
     /*
		* Funcion donde se consultan los tipos de eventos de la formulas
		* @return array $datos vector que contiene la informacion de la consulta.
    */
	  
    function  Consultar_Tipos_Eventos()
    {
        $sql = " 	SELECT  tipo_evento_id,
                          descripcion_tipo_evento 
                  FROM   esm_tipos_eventos
                  WHERE  sw_activo = '1' order by tipo_evento_id ASC   ";
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
	
   /*
		* Funcion donde se consultan la informacion del paciente
		* @return array $datos vector que contiene la informacion de la consulta.
    */
	  function ObtenerDatosAfiliado_($datos)
    {
	
      $sql  = "SELECT AD.afiliado_tipo_id AS tipo_id_paciente , ";
      $sql .= "       AD.afiliado_id AS paciente_id, ";
      $sql .= "       AD.primer_apellido    , ";
      $sql .= "       AD.segundo_apellido   , ";
      $sql .= "       AD.primer_nombre  , ";
      $sql .= "       AD.segundo_nombre     , ";
      $sql .= "       AD.fecha_nacimiento, ";
      $sql .= "       AD.tipo_sexo_id   , ";
      $sql .= "       AD.tipo_pais_id   , ";
      $sql .= "       AD.tipo_dpto_id   , ";
      $sql .= "       AD.tipo_mpio_id   , ";
      $sql .= "       AD.zona_residencia    , ";
      $sql .= "       AD.direccion_residencia   , ";
      $sql .= "       AD.telefono_residencia, ";
      $sql .= "       TO_CHAR(AD.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
      $sql .= "        edad_completa(AD.fecha_nacimiento) as edad, ";
      $sql .= " 	edad(AD.fecha_nacimiento) as edad_s, ";
      $sql .= "       AF.plan_atencion,";
      $sql .= " 	    AF.tipo_afiliado_atencion,";
      $sql .= " 	    AF.rango_afiliado_atencion, ";
      $sql .= " 	    PL.plan_descripcion ";
      $sql .= "FROM   eps_afiliados_datos AD,";
      $sql .= "       eps_afiliados AF, ";
      $sql .= "       planes PL ";
      $sql .= "WHERE  AD.afiliado_tipo_id = '".$datos['tipo_id_paciente']."' ";
      $sql .= "AND    AD.afiliado_id = '".$datos['paciente_id']."' ";
      $sql .= "AND    AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
      $sql .= "AND    AD.afiliado_id = AF.afiliado_id ";
      $sql .= "AND    AF.estado_afiliado_id IN ('AC') ";
      $sql .= "AND    AF.plan_atencion = PL.plan_id ";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /*
		* Funcion donde se consulta la fuerza  del paciente
		* @return array $datos vector que contiene la informacion de la consulta.
    */

    function ObtenerFuezaPaciente($datos)
    {
	 
      $sql  = "	SELECT  PA.tipo_fuerza_id,
                        PA.tipo_id_paciente,
                        PA.paciente_id,
                        FUE.descripcion
                FROM    esm_pacientes_fuerzas PA,
                        esm_tipos_fuerzas FUE
				   			
                WHERE    PA.tipo_fuerza_id=FUE.tipo_fuerza_id
                AND      FUE.sw_activo='1'
                AND      PA.tipo_id_paciente = '".$datos['tipo_id_paciente']."'
                AND      PA.paciente_id= '".$datos['paciente_id']."' ";

          if(!$rst = $this->ConexionBaseDatos($sql)) return false;
          $datos = array();
          while(!$rst->EOF)
          {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
          }
          $rst->Close();
          return $datos;
    }
	 /*
		* Funcion donde se consulta el tipo de vinculacion y el tipo de plan del paciente
		* @return array $datos vector que contiene la informacion de la consulta.
    */

		function Dato_Adionales_afiliacion($datos)
		{
			$sql = "	SELECT  EPS.eps_tipo_afiliado_id,
                        AFI.descripcion_eps_tipo_afiliado as vinculacion,
                        TIPOP.descripcion AS tipo_plan
               FROM 	eps_afiliados EPS,
                      eps_tipos_afiliados AFI,
                      planes_rangos  PLAR,
                      planes PLA,
                      tipos_planes TIPOP
						WHERE 	EPS.afiliado_id =  '".$datos['paciente_id']."' 
						AND     EPS.afiliado_tipo_id='".$datos['tipo_id_paciente']."'
						AND     EPS.plan_atencion= '".$datos['plan_id']."' 
						AND     EPS.eps_tipo_afiliado_id=AFI.eps_tipo_afiliado_id
						AND     EPS.plan_atencion=PLAR.plan_id
						AND     EPS.tipo_afiliado_atencion=PLAR.tipo_afiliado_id
						AND     EPS.rango_afiliado_atencion=PLAR.rango
						AND     PLAR.plan_id=PLA.plan_id
						AND     PLA.sw_tipo_plan=TIPOP.sw_tipo_plan ";
		
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

		      $datos = array();
		      while(!$rst->EOF)
		      {
		        $datos = $rst->GetRowAssoc($ToUpper = false);
		        $rst->MoveNext();
		      }
		      $rst->Close();
		      return $datos;
		
		}
    /*
		* Funcion donde se consulta las IPS activas
		* @return array $datos vector que contiene la informacion de la consulta.
    */

    function  Consultar_IPS_()
    {
	   
		$sql = " 	SELECT 	IPS.tipo_id_tercero,
                      IPS.tercero_id,
                      TER.nombre_tercero
             FROM 	  esm_ips_terceros IPS,
                      terceros TER
                WHERE   IPS.tipo_id_tercero=TER.tipo_id_tercero
                AND     IPS.tercero_id=TER.tercero_id 
                ";

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
    /*
		* Funcion donde se consulta la ESM del paciente
		* @return array $datos vector que contiene la informacion de la consulta.
    */

    function Consultar_ESM_P($datos)
    {
	
      $sql = " SELECT  	ESM.tipo_id_tercero,
                        ESM.tercero_id,
                        TER.nombre_tercero
					FROM          esm_pacientes ESM,
                        esm_empresas ESME,
                        terceros TER
					WHERE   ESM.tipo_id_paciente ='".$datos['tipo_id_paciente']."'
					AND     ESM.paciente_id ='".$datos['paciente_id']."' 
					AND     ESM.tipo_id_tercero=ESME.tipo_id_tercero
					AND     ESM.tercero_id=ESME.tercero_id
					AND     ESME.tipo_id_tercero=TER.tipo_id_tercero
					AND     ESME.tercero_id=TER.tercero_id ";
					if(!$rst = $this->ConexionBaseDatos($sql)) return false; 

					$datos = array();
					while(!$rst->EOF)
					{
					$datos = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
	
	
	}
     /*
		* Funcion donde se consulta las ESM 
		* @return array $datos vector que contiene la informacion de la consulta.
    */
    function  Consultar_ESM_($ESM_pac)
    {
	   
      $sql = " 	SELECT 	ESM.tipo_id_tercero,
                        ESM.tercero_id,
                        TER.nombre_tercero
					FROM 	esm_empresas ESM,
					        terceros TER
					WHERE   ESM.tipo_id_tercero=TER.tipo_id_tercero
					AND     ESM.tercero_id=TER.tercero_id 
					AND     ESM.tipo_id_tercero|| ESM.tercero_id not in (SELECT  ESM.tipo_id_tercero||ESM.tercero_id
                                                               FROM    esm_pacientes ESM
                                                                WHERE   ESM.tipo_id_tercero ='".$ESM_pac['tipo_id_tercero']."'
                                                                AND     ESM.tercero_id ='".$ESM_pac['tercero_id']."' )
                                                                order by TER.nombre_tercero";
				
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
  /*
		* Funcion donde se elimina el diagnostico de la formula
		* @return array $datos vector que contiene la informacion de la consulta.
    */
  
	    function Eliminar_DX_tm($tipo_id,$id_paciente,$dx)
      {
			
          $sql = " Delete     FROM  esm_formula_externa_diagnosticos_tmp  ";
          $sql .= "where  	diagnostico_id='".$dx."' 
          and        usuario_id=".UserGetUID()."
          and        tipo_id_paciente = '".$tipo_id."'
          AND         paciente_id = '".$id_paciente."'
          ;  ";
		    	
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
		* Funcion donde se consulta los diagnosticos temporales de la formula
		* @return array $datos vector que contiene la informacion de la consulta.
    */
    function Diagnostico_Temporal_S($tipo_id_paciente,$paciente_id)
		{
			
		    $sql ="SELECT   DXT.diagnostico_id,
                        DX.diagnostico_nombre
              FROM      esm_formula_externa_diagnosticos_tmp DXT,
                        diagnosticos DX
              WHERE  DXT.usuario_id = ".UserGetUID()."
              AND    DXT.tipo_id_paciente = '".$tipo_id_paciente."'
              AND    paciente_id = '".$paciente_id."'
              AND   DXT.diagnostico_id=DX.diagnostico_id 
               ";
				 if(!$rst = $this->ConexionBaseDatos($sql))	return false;
					$datos = array();
					while (!$rst->EOF)
					{
					$datos []= $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
		}
    /* Funcion que permite Ingresar los tipos de diagnostico relacionados con los antecedentes
     @ return boolean */
    function Insertar_DX_tipo_Diagnostico_TMP($dx_,$tipo_id_paciente,$paciente_id)
    {
        
          $this->ConexionTransaccion();
          $sql="INSERT INTO esm_formula_externa_diagnosticos_tmp
                              ( 
                                usuario_id,
                                tipo_id_paciente,
                                paciente_id,
                                diagnostico_id
                               )VALUES(
							   
                                ".UserGetUID().",
                                '".$tipo_id_paciente."',
                                '".$paciente_id."',
								'".$dx_."'
                              )";
            if(!$rst1 = $this->ConexionTransaccion($sql))
            {
            return false;
            }
            $this->Commit();
            return true;
    }
	 /*
		* Funcion donde se consulta los profesionales asociados a una ESM
		* @return array $datos vector que contiene la informacion de la consulta.
    */
    function Profesionales_Esm($tipo_id_esm,$id_esm)
    {
	
      $sql = "	 SELECT  PROF.tipo_id_tercero,
                        PROF.tercero_id,
                        PRO.nombre
             FROM     esm_profesionales_empresas PROF,
                      profesionales PRO
              WHERE   PROF.tipo_id_tercero_esm = '".$tipo_id_esm."'
              AND     PROF.tercero_id_esm = '".$id_esm."'
              AND    PROF.tipo_id_tercero=PRO.tipo_id_tercero
              AND    PROF.tercero_id=PRO.tercero_id  order by PRO.nombre ";
         
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
   /*
		* Funcion donde se consulta los profesionales asociados a una IPS
		* @return array $datos vector que contiene la informacion de la consulta.
    */
   function Profesionales_ips($tipo_id_ips,$id_ips)
    {
         
        $sql = "	 SELECT PROF.tipo_id_tercero,
                          PROF.tercero_id,
                          PRO.nombre
              FROM        esm_ips_profesionales PROF,
                          profesionales PRO
              WHERE   PROF.tipo_id_tercero_ips = '".$tipo_id_ips."'
              AND     PROF.tercero_id_ips = '".$id_ips."'
              AND   	PROF.tipo_id_tercero=PRO.tipo_id_tercero
              AND   	PROF.tercero_id=PRO.tercero_id  order by PRO.nombre ";
          
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
    /*
		* Funcion donde se consulta la ubicacion del tercero que es una ESM 
		* @return array $datos vector que contiene la informacion de la consulta.
    */
    function Ubicacion_ips($tipo_id_ips,$id_ips)
    {
    
        $sql = "	 SELECT 	TER.nombre_tercero,
                            MP.municipio || ' ' || TD.departamento || ' ' || TP.pais AS ubicacion
                  FROM 			terceros TER,
                          tipo_mpios MP,
                          tipo_dptos TD,
                          tipo_pais TP
                  WHERE   TER.tipo_pais_id=MP.tipo_pais_id
                  AND     TER.tipo_dpto_id=MP.tipo_dpto_id
                  AND     TER.tipo_mpio_id=MP.tipo_mpio_id
                  AND     MP.tipo_pais_id=TD.tipo_pais_id
                  AND     MP.tipo_dpto_id=TD.tipo_dpto_id
                  AND     TD.tipo_pais_id=TP.tipo_pais_id 
                  AND     TER.tipo_id_tercero='".$tipo_id_ips."'
                  AND     TER.tercero_id='".$id_ips."'
			";
		 	
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			      $datos = array();
			      while(!$rst->EOF)
			      {
			        $datos = $rst->GetRowAssoc($ToUpper = false);
			        $rst->MoveNext();
			      }
			      $rst->Close();
			      return $datos;
	}
    /*
		* Funcion donde se consulta las IPS asociadas a una ESM 
		* @return array $datos vector que contiene la informacion de la consulta.
    */
    function IPS_ESM($tipo_id_ips,$id_ips)
    {
          $sql = "	SELECT  ESM.tipo_id_tercero_esm,
                            ESM.tercero_id_esm,
                            TER.nombre_tercero
                    FROM    esm_ips_esm ESM,
                            terceros TER
							
                  WHERE    ESM.tipo_id_tercero ='".$tipo_id_ips."'
                  AND     ESM.tercero_id ='".$id_ips."'
                  AND     ESM.tipo_id_tercero=TER.tipo_id_tercero
                  AND     ESM.tercero_id=TER.tercero_id 		
                 order by TER.nombre_tercero ";
		 	
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
	 /*
		* Funcion donde se consulta si la formula digitada existe 
		* @return array $datos vector que contiene la informacion de la consulta.
    */
   function Consulta_Formula_Existente($formula_papel,$tipo_id_paciente,$paciente_id)
   {
	
        $sql = " SELECT formula_papel
                  FROM    esm_formula_externa
                  WHERE    formula_papel = '".trim($formula_papel)."' 
                  AND     tipo_id_paciente = '".$tipo_id_paciente."'
                  AND      paciente_id = '".$paciente_id."' 
                  and      sw_estado!='2' 
                  and      sw_corte='0'";
                
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[]= $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
	}
   /*
		* Funcion donde se guarda la cabecera de la formula 
		* @return boolean.
    */
   function Guardar_Tmp_Cabecera_formulacionI($request,$datos_empresa)
    {
          $this->ConexionTransaccion();
          $sql  = "SELECT nextval('esm_formula_externa_tmp_tmp_formula_id_seq') AS documento ";
        	if(!$rst = $this->ConexionTransaccion($sql))
          return false;

          $indice = $rst->GetRowAssoc($ToUpper = false);
          $documento = $indice['documento'];
        if(!empty($request['tipo_fuerza'])){
			    $fuerza= " ,tipo_fuerza_id,";
			    $fuerza_id=" ,'".$request['tipo_fuerza']."', ";
				}else
				{ 
				    $fuerza= " ,";
					 $fuerza_id=" ," ;
				}
			    $fecha_recepcion=explode("/", $request['fecha_recepcion']);
					$fecha_recepcion_f= $fecha_recepcion[2]."-".$fecha_recepcion[1]."-".$fecha_recepcion[0];
					list($tipo_id_tercero,$tercero_id) = explode("@",$request['profesional']);
          $hora_formula=$request['Horas'].":".$request['minuto'];
					list($ems_tipo_id_tercero,$esm_tercero_id) = explode("@",$request['esm']);
					
			$sql = "INSERT INTO esm_formula_externa_tmp
					(
							tmp_formula_id,
							tmp_empresa_id,		
							tmp_formula_papel,
                            fecha_formula,
							hora_formula,
							tipo_formula,
							tipo_evento_id
							$fuerza	
							tipo_id_tercero,	
							tercero_id,	
							tipo_id_paciente,	
							paciente_id,	
							plan_id,		
							rango,	
							tipo_afiliado_id,
							esm_tipo_id_tercero,		
							esm_tercero_id,	
							usuario_id,	
							tipo_formulacion	
					)
						VALUES
						(
							$documento,
							 '".$datos_empresa['empresa_id']."',
							'".$request['formula_papel']."',
							'".$fecha_recepcion_f."',
							'".$hora_formula."',
							".$request['tipo_formula'].",
							'".$request['tipo_evento']."'
							 $fuerza_id
							'".trim($tipo_id_tercero)."',
							'".$tercero_id."',
							'".$request['tipo_id_paciente']."',
							'".$request['paciente_id']."',
							'".$request['plan_id']."',
							'".$request['rango']."',
							'".$request['tipo_afiliado']."',
							'".$ems_tipo_id_tercero."',
							'".$esm_tercero_id."',
						    ".UserGetUID().",
							'0'

							);
							";
			
			  if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
		        }
         $sql = " update esm_formula_externa_diagnosticos_tmp 
                  set 	tmp_formula_id=$documento
              where   usuario_id = ".UserGetUID()."
              and    tipo_id_paciente = '".$request['tipo_id_paciente']."'
              AND       paciente_id = '".$request['paciente_id']."' ";
		      if(!$rst1 = $this->ConexionTransaccion($sql))
		      {
		      return false;
		      }
				$this->Commit();
				return true;
		}
     /*
		* Funcion donde se guarda la formula de trancripcion  en un temporal  
		* @return boolean.
    */
    function Guardar_Tmp_Cabecera_formulacionE($request,$datos_empresa)
    {
					$this->ConexionTransaccion();
          $sql  = "SELECT nextval('esm_formula_externa_tmp_tmp_formula_id_seq') AS documento ";
        	if(!$rst = $this->ConexionTransaccion($sql))
          return false;

          $indice = $rst->GetRowAssoc($ToUpper = false);
          $documento = $indice['documento'];
           if(!empty($request['tipo_fuerza'])){
              $fuerza= " ,tipo_fuerza_id,";
              $fuerza_id=" ,'".$request['tipo_fuerza']."', ";
            }else
            { 
                $fuerza= " ,";
               $fuerza_id=" ," ;
            }
            $fecha_recepcion=explode("/", $request['fecha_recepcion']);
            $fecha_recepcion_f= $fecha_recepcion[2]."-".$fecha_recepcion[1]."-".$fecha_recepcion[0];

            list($tipo_id_tercero,$tercero_id) = explode("@",$request['profesional_ips_esm']);
					$hora_formula=$request['Horas'].":".$request['minuto'];
					list($ems_tipo_id_tercero,$esm_tercero_id) = explode("@",$request['esm_ips']);
					list($esm_autoriza_tipo_id_tercero,$esm_autoriza_tercero_id) = explode("@",$request['profesional_aut_esm_ips']);
					list($ips_tipo_id_tercero,$ips_tercero_id) = explode("@",$request['ips']);
					list($ips_profesional_tipo_id_tercero,$ips_profesional_tercero_id) = explode("@",$request['profesional_ips']);
				
                
            $sql = "INSERT INTO esm_formula_externa_tmp
                (
                    tmp_formula_id,
                    tmp_empresa_id,		
                    tmp_formula_papel,
                                  fecha_formula,
                    hora_formula,
                    tipo_formula,
                    tipo_evento_id
                    $fuerza	
                    tipo_id_tercero,	
                    tercero_id,	
                    tipo_id_paciente,	
                    paciente_id,	
                    plan_id,		
                    rango,	
                    tipo_afiliado_id,
                    esm_tipo_id_tercero,		
                    esm_tercero_id,	
                    esm_autoriza_tipo_id_tercero,
                    esm_autoriza_tercero_id ,
                    ips_tipo_id_tercero,
                    ips_tercero_id,
                    ips_profesional_tipo_id_tercero,
                    ips_profesional_tercero_id,
                    costo_formula,
                    usuario_id,	
                    tipo_formulacion	
                )
                  VALUES
                  (
                    $documento,
                     '".$datos_empresa['empresa_id']."',
                    '".$request['formula_papel']."',
                    '".$fecha_recepcion_f."',
                    '".$hora_formula."',
                    ".$request['tipo_formula'].",
                    '".$request['tipo_evento']."'
                     $fuerza_id
                    '".trim($tipo_id_tercero)."',
                    '".$tercero_id."',
                    '".$request['tipo_id_paciente']."',
                    '".$request['paciente_id']."',
                    '".$request['plan_id']."',
                    '".$request['rango']."',
                    '".$request['tipo_afiliado']."',
                    '".$ems_tipo_id_tercero."',
                    '".$esm_tercero_id."',
                    '".$esm_autoriza_tipo_id_tercero."',
                    '".$esm_autoriza_tercero_id."',
                    '".$ips_tipo_id_tercero."',
                    '".$ips_tercero_id."',
                    '".$ips_profesional_tipo_id_tercero."',
                    '".$ips_profesional_tercero_id."',
                    ".$request['costo_formula'].",
                    ".UserGetUID().",
                    '1'

                    );
                    ";
			  if($rst1 = $this->ConexionTransaccion($sql))
				{
            $sql = " update esm_formula_externa_diagnosticos_tmp 
                              set 	tmp_formula_id=$documento
                        where   usuario_id = ".UserGetUID()."
                        and    tipo_id_paciente = '".$request['tipo_id_paciente']."'
                        AND       paciente_id = '".$request['paciente_id']."' ";
                    if(!$rst1 = $this->ConexionTransaccion($sql))
                    {
							      return false;
							      }
          }
          ELSE
          {
            return false;
          }	 
				$this->Commit();
				return true;
		}
    /*
		* Funcion donde se guarda el ultimo registro del paciente   
		* @return array $datos vector que contiene la informacion de la consulta.
    */
    function Consulta_Max_Formulacion_tmp($empresa,$tipo_paciente,$paciente)
		{
			
          $sql = "SELECT (COALESCE(MAX(esm.tmp_formula_id),0)) AS tmp_id,tipo.sw_ambulatoria FROM  esm_formula_externa_tmp as esm, esm_tipos_formulas tipo where   esm.tipo_formula=tipo.tipo_formula_id and esm.usuario_id=".UserGetUID()." and esm.tmp_empresa_id='".$empresa."'
                        and  esm.tipo_id_paciente='".$tipo_paciente."' and esm.paciente_id='".$paciente."' group by tipo.sw_ambulatoria	";
                if(!$rst = $this->ConexionBaseDatos($sql))
                return false;
                $datos = array();
                while(!$rst->EOF)
                {
                $datos= $rst->GetRowAssoc($ToUpper);
                $rst->MoveNext();
                }
                $rst->Close();
                return $datos;
	
		}
     /*
		* Funcion donde se elimina el producto temporal   
		* @return array $datos vector que contiene la informacion de la consulta.
    */
    function Eliminar_prod_tmp($dx,$tipo_id,$id_paciente,$fe_medicamento_id,$tmp_id)
		{
		
        $sql = " Delete     FROM  esm_formula_externa_posologia_tmp  ";
        $sql .= "where  		fe_medicamento_id='".$fe_medicamento_id."'; ";
        $sql .= " Delete     FROM  esm_formula_externa_medicamentos_tmp  ";
        $sql .= "where  		codigo_producto='".$dx."' 
                  and        usuario_id=".UserGetUID()."
                  and        tipo_id_paciente = '".$tipo_id."'
                  AND         paciente_id = '".$id_paciente."'
                  AND        fe_medicamento_id='".$fe_medicamento_id."'
                  and     	tmp_formula_id='".$tmp_id."'					
               ;  ";
		    	
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
		* Funcion donde se realiza la marca al producto   
		* @return boolean.
    */
    function Update_Marcar($fe_medicamento_id)
    {
		  
        $this->ConexionTransaccion();
				$sql = " update esm_formula_externa_medicamentos_tmp 
				set 	 	sw_marcado='1'
				where   	fe_medicamento_id = ".$fe_medicamento_id."
				 ";
          if(!$rst1 = $this->ConexionTransaccion($sql))
		      {
		      return false;
		      }
				$this->Commit();
				return true;
	}
  /*
		* Funcion donde se consulta los medicamentos formulados   
		* @return array $datos vector que contiene la informacion de la consulta.
    */
    
	  function Medicamentos_Formulados_tmp($tipo_id,$id_paciente,$tmp_id)
		{
			
		
           $sql = "SELECT  tmp.fe_medicamento_id,
                  tmp.codigo_producto,
                  tmp.cantidad,
                  tmp.observacion,
                  tmp.dosis,
                  tmp.unidad_dosificacion,
                  tmp.tiempo_tratamiento,
                  tmp.unidad_tiempo_tratamiento,
                  tmp.periodicidad_entrega,
                  tmp.unidad_periodicidad_entrega,
                  tmp.via_administracion_id,
                  fc_descripcion_producto_alterno(tmp.codigo_producto) as descripcion_prod,
                  A.descripcion as producto,
                  b.concentracion_forma_farmacologica,
                  b.unidad_medida_medicamento_id,
                  b.factor_conversion,
                  b.factor_equivalente_mg,
                  d.descripcion as forma,
                  c.descripcion as principio_activo,
                  fc_codigo_mindefensa(tmp.codigo_producto) as min_defensa,
                  tmp.sw_marcado
             FROM   esm_formula_externa_medicamentos_tmp tmp,
                    inventarios_productos A LEFT JOIN medicamentos b ON (A.codigo_producto = b.codigo_medicamento) LEFT JOIN inv_med_cod_principios_activos c on(b.cod_principio_activo = c.cod_principio_activo) LEFT JOIN inv_med_cod_forma_farmacologica  d ON(b.cod_forma_farmacologica = d.cod_forma_farmacologica)
                  
               WHERE  tmp.usuario_id = ".UserGetUID()."
               AND    tmp.tipo_id_paciente = '".$tipo_id."'
               AND    tmp.paciente_id = '".$id_paciente."'
               AND    tmp.tmp_formula_id='".$tmp_id."'
               AND    tmp.codigo_producto= A.codigo_producto
               
           ; 

           ";
		    	
				
				
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
		* Funcion donde se consulta la lista de precios segun el plan   
		* @return array $datos vector que contiene la informacion de la consulta.
    */
   function Lista_Plan_idConsul($plan)
    {
       $sql = " SELECT lista_precios
                FROM   planes WHERE plan_id = '".$plan."' ";
				  	
            
              if(!$rst = $this->ConexionBaseDatos($sql)) return false; 
              $datos = array();
              while(!$rst->EOF)
              {
              $datos = $rst->GetRowAssoc($ToUpper = false);
              $rst->MoveNext();
              }
              $rst->Close();
              return $datos;

    }
  
	  /*
      * Funcion donde se consulta  informacion completa del  producto
      * @return array $datos vector con la informacion de los productos
    */
     function ConsultarListaDetalle($filtros,$codigo_lista,$empresa_id,$tipo_id_paciente,$paciente_id,$Datos_empresa_dig,$offset)
      {
        
          if($filtros['principio_activo']!="" || $filtros['descripcion']!="" || $filtros['codigo']!="" )
          {
		      

            $sql .= " SELECT    fc_descripcion_producto_alterno(codigo_producto) as descripcion,
                                fc_descripcion_producto_molecula(codigo_producto) as molecula,
                                fc_codigo_mindefensa(codigo_producto) as codigo_producto_mini,
			                         codigo_producto,
			                         resultado,
			                         porcentaje,
			                         sw_porcentaje,
			                         existencia	 ";
		        $sql .= " from (";
		                      $sql .= "SELECT 
		                                        inv.codigo_producto,
		                                        '0' as resultado,
		                                        '0' as porcentaje,
		                                        '0' as precio,
		                                        '0' as sw_porcentaje,
												exis.existencia
		                                         ";
		                                        
		                      $sql .= " FROM     
		                                        inventarios inv  left join existencias_bodegas exis ON(inv.empresa_id=exis.empresa_id and inv.codigo_producto=exis.codigo_producto),
		                                        inventarios_productos invp left join medicamentos med ON(invp.codigo_producto=med.codigo_medicamento) left join  inv_med_cod_principios_activos ppa  ON(med.cod_principio_activo =ppa.cod_principio_activo)
											
												";
		                      $sql .= " WHERE     invp.codigo_producto = inv.codigo_producto 
												  and 	   exis.empresa_id = '".$Datos_empresa_dig[0]['empresa_id']."'  
												  and      exis.centro_utilidad='".$Datos_empresa_dig[0]['centro_utilidad']."'
												  and      exis.bodega = '".$Datos_empresa_dig[0]['bodega']."' ";
							                
											if(!empty($filtros['principio_activo']))
											{
		                                        
											  $sql .= "  AND 	ppa.descripcion ilike '%".$filtros['principio_activo']."%' ";
											}	 
											if(!empty($filtros['descripcion']))
											{
		                                        
											  $sql .= "  and  invp.descripcion ilike '%".$filtros['descripcion']."%' ";
											}	 
											if(!empty($filtros['codigo']))
											{
		                                        
											  $sql .= "  	and invp.codigo_mindefensa = '".$filtros['codigo']."'  ";
											}	 
												 
												$sql .= " and   inv.codigo_producto NOT IN (
		                                                                  select codigo_producto
		                                                                         from
		                                                                         listas_precios_detalle
		                                                                         where
		                                                                         codigo_lista = '".$codigo_lista."'
		                                                                         and empresa_id = '".$empresa_id."'
																				 and sw_habilitado_entregar='1'
		                                                                  ) 
		                                        ";
		                      $sql .= " UNION ";                      
		                      $sql .= " SELECT 
		                                        lpd.codigo_producto,
		                                        '1' as resultado,
		                                        lpd.porcentaje,
		                                        lpd.precio,
		                                        lpd.sw_porcentaje,
		                                        exis.existencia												";
		                      $sql .= " FROM   
		                                        listas_precios_detalle lpd,
		                                        inventarios_productos invp left join medicamentos med ON(invp.codigo_producto=med.codigo_medicamento) left join  inv_med_cod_principios_activos ppa  ON(med.cod_principio_activo =ppa.cod_principio_activo),
												inventarios inv  left join existencias_bodegas exis ON(inv.empresa_id=exis.empresa_id and inv.codigo_producto=exis.codigo_producto)
		                                      	 ";
		                      $sql .= " WHERE       lpd.codigo_lista = '".$codigo_lista."' 
												and 	   exis.empresa_id = '".$Datos_empresa_dig[0]['empresa_id']."'  
												and      exis.centro_utilidad='".$Datos_empresa_dig[0]['centro_utilidad']."'
												and      exis.bodega = '".$Datos_empresa_dig[0]['bodega']."' 
												and   lpd.sw_habilitado_entregar = '1'  
												and   lpd.codigo_producto = invp.codigo_producto
												and   invp.codigo_producto=inv.codigo_producto
												";

											if(!empty($filtros['principio_activo']))
											{
		                                        
											  $sql .= "   and   ppa.descripcion ilike '%".$filtros['principio_activo']."%'";
											}	 
											if(!empty($filtros['codigo']))
											{
		                                        
											   $sql .= " and  invp.codigo_mindefensa = '".$filtros['codigo']."'  " ;
											}	
											if(!empty($filtros['descripcion']))
											{
		                                        
											   $sql .= " and  invp.descripcion ilike '%".$filtros['descripcion']."%'  " ;
											}	

										
		        $sql .= "       ) as T  where   codigo_producto not in ( select     codigo_producto 
				                                                              from  esm_formula_externa_medicamentos_tmp
																			  where  	tipo_id_paciente='".$tipo_id_paciente."'
																			  and       paciente_id='".$paciente_id."'
																			  and       usuario_id=".UserGetUID()." )";
								
		        $sql .= " ORDER BY resultado DESC ";
        
        if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
    
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
 
       }
        
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
      * Funcion donde se consulta  informacion completa de los insumos
      * @return array $datos vector con la informacion de los productos
    */
	  
    function ConsultarListaDetalle_insumos($filtros,$codigo_lista,$empresa_id,$tipo_id_paciente,$paciente_id,$Datos_empresa_dig,$offset)
    {

       if($filtros['descripcion']!="" || $filtros['codigo']!="" )
      {
		        $sql .= " SELECT    fc_descripcion_producto_alterno(codigo_producto) as descripcion,
                                fc_codigo_mindefensa(codigo_producto) as codigo_producto_mini,
                                codigo_producto,
			                         resultado,
			                         porcentaje,
			                         precio,
			                         sw_porcentaje,
			                         valor_inicial,
                                     existencia									 ";
		        $sql .= " from (";
		                      $sql .= "SELECT 
		                                        inv.codigo_producto,
		                                        '0' as resultado,
		                                        '0' as porcentaje,
		                                        '0' as precio,
		                                        '0' as sw_porcentaje,
		                                        inv.costo as valor_inicial,
                                                exis.existencia												";
		                                        
		                      $sql .= " FROM     
		                                        inventarios_productos invp,
		 										inventarios inv  left join existencias_bodegas exis ON(inv.empresa_id=exis.empresa_id and inv.codigo_producto=exis.codigo_producto)
		                                     
												";
		                      $sql .= " WHERE     invp.codigo_producto = inv.codigo_producto 
												 and 	   exis.empresa_id = '".$Datos_empresa_dig[0]['empresa_id']."'  
												  and      exis.centro_utilidad='".$Datos_empresa_dig[0]['centro_utilidad']."'
												  and      exis.bodega = '".$Datos_empresa_dig[0]['bodega']."' ";
							              
											 
											if(!empty($filtros['descripcion']))
											{
		                                        
											  $sql .= "  and  invp.descripcion ilike '%".$filtros['descripcion']."%' ";
											}	 
											if(!empty($filtros['codigo']))
											{
		                                        
											  $sql .= "  	and invp.codigo_mindefensa = '".$filtros['codigo']."'  ";
											}	 
												 
												$sql .= " and   inv.codigo_producto NOT IN (
		                                                                  select codigo_producto
		                                                                         from
		                                                                         listas_precios_detalle
		                                                                         where
		                                                                         codigo_lista = '".$codigo_lista."'
		                                                                         and empresa_id = '".$empresa_id."'
																				 and sw_habilitado_entregar='1'
		                                                                  ) 
																		  
												       and inv.codigo_producto NOT IN ( select codigo_medicamento
													                        from medicamentos )
																			
		                                        ";
		                      $sql .= " UNION ";                      
		                      $sql .= " SELECT 
		                                        lpd.codigo_producto,
		                                        '1' as resultado,
		                                        lpd.porcentaje,
		                                        lpd.precio,
		                                        lpd.sw_porcentaje,
		                                        lpd.valor_inicial,
                                                exis.existencia												";
		                      $sql .= " FROM   
		                                        listas_precios_detalle lpd,
		                                        inventarios_productos invp,
												inventarios inv  left join existencias_bodegas exis ON(inv.empresa_id=exis.empresa_id and inv.codigo_producto=exis.codigo_producto)
		                                     
												";
		                      $sql .= " WHERE       lpd.codigo_lista = '".$codigo_lista."' 
											and 	   exis.empresa_id = '".$Datos_empresa_dig[0]['empresa_id']."'  
											and      exis.centro_utilidad='".$Datos_empresa_dig[0]['centro_utilidad']."'
											and      exis.bodega = '".$Datos_empresa_dig[0]['bodega']."' 
							                
		                                      and   lpd.sw_habilitado_entregar = '1'  
		                                      and   lpd.codigo_producto = invp.codigo_producto
											  and    invp.codigo_producto = inv.codigo_producto 
													";

											 
											if(!empty($filtros['codigo']))
											{
		                                        
											   $sql .= " and  invp.codigo_mindefensa = '".$filtros['codigo']."'  " ;
											}	
											if(!empty($filtros['descripcion']))
											{
		                                        
											   $sql .= " and  invp.descripcion ilike '%".$filtros['descripcion']."%'  " ;
											}	
											
									$sql .=" 		 and invp.codigo_producto NOT IN ( select codigo_medicamento
													                        from medicamentos ) ";

										
		        $sql .= "       ) as T  where   codigo_producto not in ( select     codigo_producto 
				                                                              from  esm_formula_externa_medicamentos_tmp
																			  where  	tipo_id_paciente='".$tipo_id_paciente."'
																			  and       paciente_id='".$paciente_id."'
																			  and       usuario_id=".UserGetUID()." )";
								
		        $sql .= " ORDER BY resultado DESC ";
        
        if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
    
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
 
       }
        
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
      * Funcion donde se consulta  la informacion completa de las formulas de digitalizacion temporales 
      * @return array $datos vector con la informacion de los productos
    */
	    
    function consultar_Formulacion_TITMP($request,$empresa,$tmp_id)
    {
	
          $sql = "SELECT  FR.*,
                          EMP.razon_social,
                          to_char(FR.fecha_formula,'dd-mm-yyyy') as fecha_formula,
                          to_char(FR.fecha_registro,'dd-mm-yyyy') as fecha_registro,
                          TIPOF.descripcion_tipo_formula,
                          TIPOV.descripcion_tipo_evento,
                          PROF.nombre AS profesional_esm,
                          TIPOPROF.descripcion as descripcion_profesional_esm,
                          TERC.nombre_tercero AS ESM_atendio,
                          PAC.primer_nombre || ' ' ||  PAC.segundo_nombre|| ' ' || PAC.primer_apellido|| ' ' ||PAC.segundo_apellido AS nombre_paciente,
                          PAC.sexo_id,
                          edad(PAC.fecha_nacimiento) as edad,
                          PLAN.plan_descripcion 
                            
            FROM    esm_formula_externa_tmp FR,
                    empresas EMP,
                    esm_tipos_formulas TIPOF,
                    esm_tipos_eventos  TIPOV,
                    esm_profesionales_empresas ESM_PROF,
                    profesionales PROF,
                    tipos_profesionales TIPOPROF,
                    esm_empresas ESM_EMPRESA,
                    terceros TERC,
                    pacientes  PAC,
                    planes_rangos PLANR,
                    planes PLAN
					
					WHERE   tmp_formula_id = ".$tmp_id." 
					AND     tmp_empresa_id = '".$empresa."' 
					AND     FR.tipo_id_paciente = '".$request['tipo_id_paciente']."'
					AND     FR.paciente_id = '".$request['paciente_id']."'
					AND     FR.usuario_id =".UserGetUID()." 
					AND     FR.tmp_empresa_id=EMP.empresa_id
					AND     FR.tipo_formula=TIPOF.tipo_formula_id
			        AND     FR.tipo_evento_id=TIPOV.tipo_evento_id
					AND     FR.tipo_id_tercero=ESM_PROF.tipo_id_tercero
					AND		FR.tercero_id=ESM_PROF.tercero_id
					AND     FR.esm_tipo_id_tercero=ESM_PROF.tipo_id_tercero_esm
					AND 	FR.esm_tercero_id=ESM_PROF.tercero_id_esm
					AND     FR.tipo_id_tercero=PROF.tipo_id_tercero
					AND		FR.tercero_id=PROF.tercero_id
					AND     PROF.tipo_profesional =TIPOPROF.tipo_profesional
					AND     ESM_PROF.tipo_id_tercero_esm=ESM_EMPRESA.tipo_id_tercero
					AND     ESM_PROF.tercero_id_esm=ESM_EMPRESA.tercero_id
					AND     ESM_EMPRESA.tipo_id_tercero=TERC.tipo_id_tercero
					AND     ESM_EMPRESA.tercero_id=TERC.tercero_id
					AND     FR.tipo_id_paciente=PAC.tipo_id_paciente
					AND		FR.paciente_id=PAC.paciente_id
					AND     FR.plan_id=PLANR.plan_id
					AND		FR.rango=PLANR.rango
					AND		FR.tipo_afiliado_id=PLANR.tipo_afiliado_id
					AND     PLANR.plan_id=PLAN.plan_id";
	
          if(!$rst = $this->ConexionBaseDatos($sql)) return false;
            $datos = array();
			      while(!$rst->EOF)
			      {
			         $datos= $rst->GetRowAssoc($ToUpper = false);
			        $rst->MoveNext();
			      }
			      $rst->Close();
			      return $datos;
	
    }
    /*
     * Funcion donde se consulta  la informacion adicional de las formulas de transcripcion  temporales 
     * @return array $datos vector con la informacion de los productos
    */
    function Consulta_Formulacion_TMPA($formula)
		{
			
			$sql = "SELECT  	FR.esm_tipo_id_tercero,
                        FR.esm_tercero_id,
                        FR.esm_autoriza_tipo_id_tercero,
                        FR.esm_autoriza_tercero_id,
                        PROF.nombre AS profesional_esm,
                        TIPOPROF.descripcion as descripcion_profesional_esm,
                        TERC.nombre_tercero AS ESM_atendio,
                        FR.costo_formula

					FROM        esm_formula_externa_tmp FR,
                      esm_profesionales_empresas ESM_PROF,
                      profesionales PROF,
                      esm_empresas ESM_EMPRESA,
                      terceros TERC,
                      tipos_profesionales TIPOPROF
						
						WHERE   FR.tmp_formula_id = '".$formula."'
						AND      	FR.esm_autoriza_tipo_id_tercero=ESM_PROF.tipo_id_tercero
						AND		    FR.esm_autoriza_tercero_id=ESM_PROF.tercero_id
						AND         FR.esm_tipo_id_tercero=ESM_PROF.tipo_id_tercero_esm
						AND 	    FR.esm_tercero_id=ESM_PROF.tercero_id_esm
						AND         ESM_PROF.tipo_id_tercero=PROF.tipo_id_tercero
						AND         ESM_PROF.tercero_id=PROF.tercero_id
						AND			ESM_PROF.tipo_id_tercero_esm=ESM_EMPRESA.tipo_id_tercero
						AND         ESM_PROF.tercero_id_esm=ESM_EMPRESA.tercero_id
						AND        ESM_EMPRESA.tipo_id_tercero=TERC.tipo_id_tercero
						AND        ESM_EMPRESA.tercero_id=TERC.tercero_id
						AND        PROF.tipo_profesional =TIPOPROF.tipo_profesional";
						
					
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos= $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		
		
		}
    /*
     * Funcion donde se consulta  la informacion de la IPS  de  las formulas de transcripcion  temporales 
     * @return array $datos vector con la informacion de los productos
    */
    function Consulta_FormulacionTMPE($formula)
		{
			
        $sql = "
            SELECT  	FR.ips_tipo_id_tercero,
                  FR.ips_tercero_id,
                  FR.ips_profesional_tipo_id_tercero,
                  FR.ips_profesional_tercero_id,
                  TERC.nombre_tercero AS IPS_ATENDIDO,
                  PROF.nombre AS profesional_ips,
                  TIPOPROF.descripcion as descripcion_profesional_ips,
                   MP.municipio || ' ' || TD.departamento || ' ' || TP.pais AS ubicacion
                  
            FROM        esm_formula_externa_tmp FR,
                        esm_ips_profesionales  IPS_P,
                  esm_ips_terceros  TERCIP,
                  terceros TERC,
                  profesionales PROF,
                  tipos_profesionales TIPOPROF,
                    tipo_mpios MP,
                  tipo_dptos TD,
                  tipo_pais TP
                
        
              WHERE   FR.tmp_formula_id = '".$formula."'
              and         FR.ips_tipo_id_tercero=IPS_P.tipo_id_tercero_ips
              and         FR.ips_tercero_id=IPS_P.tercero_id_ips
              and         FR.ips_profesional_tipo_id_tercero=IPS_P.tipo_id_tercero
              and         FR.ips_profesional_tercero_id=IPS_P.tercero_id
              and         IPS_P.tipo_id_tercero_ips=TERCIP.tipo_id_tercero
              and         IPS_P.tercero_id_ips=TERCIP.tercero_id
              and         TERCIP.tipo_id_tercero=TERC.tipo_id_tercero
              and         TERCIP.tercero_id=TERC.tercero_id
              AND         IPS_P.tipo_id_tercero=PROF.tipo_id_tercero
              AND         IPS_P.tercero_id=PROF.tercero_id
              and          PROF.tipo_profesional =TIPOPROF.tipo_profesional
              AND          TERC.tipo_pais_id=MP.tipo_pais_id
              AND     TERC.tipo_dpto_id=MP.tipo_dpto_id
              AND     TERC.tipo_mpio_id=MP.tipo_mpio_id
              AND     MP.tipo_pais_id=TD.tipo_pais_id
              AND     MP.tipo_dpto_id=TD.tipo_dpto_id
              AND     TD.tipo_pais_id=TP.tipo_pais_id 	 ; ";
              
      
        if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        $datos = array();
        while(!$rst->EOF)
        {
        $datos= $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
	
		}
    /*
     * Funcion donde se consulta  los diagnosticos de las formula temporal 
     * @return array $datos vector con la informacion de los productos
    */
    function Diagnostico_Temporal($tipo_id_paciente,$paciente_id,$tmp_id)
		{
			
            $sql ="SELECT  DXT.diagnostico_id,
                   DX.diagnostico_nombre
              FROM   esm_formula_externa_diagnosticos_tmp DXT,
                   diagnosticos DX
              WHERE  DXT.usuario_id = ".UserGetUID()."
              AND    DXT.tipo_id_paciente = '".$tipo_id_paciente."'
              AND    paciente_id = '".$paciente_id."'
              AND   DXT.diagnostico_id=DX.diagnostico_id 
              and   DXT.tmp_formula_id='".$tmp_id."' ";
             if(!$rst = $this->ConexionBaseDatos($sql))	return false;
              $datos = array();
              while (!$rst->EOF)
              {
              $datos []= $rst->GetRowAssoc($ToUpper = false);
              $rst->MoveNext();
              }
              $rst->Close();
              return $datos;
		}
    /*
     * Funcion donde se consulta  la informacion del medicamento formulado 
     * @return array $datos vector con la informacion de los productos
    */
    function Medicamento_Formular_Inform($codigo_medicamento)
    {
	 
          $sql = " 		SELECT 	A.codigo_producto,
                              fc_descripcion_producto_alterno(A.codigo_producto) as descripcion_prod,
                              A.descripcion as producto,
                              b.concentracion_forma_farmacologica,
                              b.unidad_medida_medicamento_id,
                              b.factor_conversion,
                              b.factor_equivalente_mg,
                              d.descripcion as forma,
                              d.unidad_dosificacion,
                              c.descripcion as principio_activo,
                              d.cod_forma_farmacologica
            FROM     inventarios_productos A,
                     medicamentos as b,
                     inv_med_cod_principios_activos as c,
                     inv_med_cod_forma_farmacologica as d
            WHERE    A.codigo_producto = '".$codigo_medicamento."'
            AND 	 A.codigo_producto = b.codigo_medicamento 
            AND   	 b.cod_principio_activo = c.cod_principio_activo
            AND   	 b.cod_forma_farmacologica = d.cod_forma_farmacologica 			
            AND   	 a.estado = '1'  ";
            
            
		if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        $datos = array();
        while(!$rst->EOF)
        {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
	
	}
   /*
     * Funcion donde se consulta  la via de administracion  del medicamento 
     * @return array $datos vector con la informacion de los productos
    */
   
	function tipo_via_administracion($codigo_producto)
	{
 	
		$sql = "select  b.via_administracion_id, 
                    b.nombre 
					from    inv_medicamentos_vias_administracion as a,
					hc_vias_administracion as b 
					where   a.codigo_medicamento = '".$codigo_producto."' 
					and		  a.via_administracion_id = b.via_administracion_id 
					order by b.via_administracion_id";

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
     * Funcion donde se consulta otras vias de administracion del medicamento 
     * @return array $datos vector con la informacion de los productos
    */
    function GetunidadesViaAdministracion($via_administracion)
    {
 		
        $sql = " SELECT unidad_dosificacion FROM hc_unidades_dosificacion_vias_administracion
				WHERE via_administracion_id = '".$via_administracion."'";

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
     * Funcion donde se guarda el medicamento formulado 
     * @return boolean
    */
		function Insertar_Medicamentos($request)
		{
		   
		   $this->ConexionTransaccion();
        $cont=0;
		
        if ($request['cantidad'] == '' OR $request['via_administracion'] == -1 OR
					$request['unidad_dosis'] == -1 OR empty($request['unidad_dosis']))
        {
            if($request['via_administracion'] == '-1')
          {
		            $mensaje .= "SELECCIONE LA VIA DE ADMINISTRACION ";
                $cont=$cont+1;
				 }
            if($request['cantidad'] == '')
          {
		            $mensaje .= " ERROR DILIGENCIA LOS CAMPOS OBLIGATORIOS";
                $cont=$cont+1;
				   
				 }

			
				 if(($request['unidad_dosis'] == '-1') OR (empty($request['unidad_dosis'])))
				 {
		             $mensaje .= " SELECCIONE LA UNIDAD DE DOSIFICACION ";
					$cont=$cont+1;
				 }

		}
    if ($request['opcion'] == '')
		{
					$mensaje .="SELECCIONE UNA OPCION DE FRECUENCIA PARA LA FORMULACION.";
          $cont=$cont+1; 
		 }
    if ($request['opcion'] == '1')
		{
						if (($request['periocidad']=='-1') OR ($request['tiempo']=='-1'))
						{
					   	$mensaje .="PARA OPCION 1 DE FRECUENCIA DEBE SELECIONAR UNA OPCION.<br>";
						 $cont=$cont+1;
							
						}	
      }						
		if ($request['opcion'] == '2')
		{
						if ($request['duracion']=='-1')
							{
								$mensaje .="PARA OPCION 2 DE FRECUENCIA DEBE SELECIONAR UNA OPCION.";
								$cont=$cont+1;
							}
		
		}
		
		if ($request['opcion']== '4')
		{	
					if (empty($request['opH']))
							{
								
									$mensaje .="PARA OPCION 4 DE FRECUENCIA DEBE SELECCIONAR UNA HORA ESPECIFICA.";
								  $cont=$cont+1; 
							}
		
	    }
		
		
		if($cont!=0)
		{
		  
		  return $mensaje;
		
		}else
		{
        $sql  = "SELECT NEXTVAL('esm_formula_externa_medicamentos_tmp_fe_medicamento_id_seq') AS documento ";
        
		
        if(!$rst = $this->ConexionTransaccion($sql))
        return false;

        $indice = $rst->GetRowAssoc($ToUpper = false);
        $documento = $indice['documento'];

        if(!$rst = $this->ConexionTransaccion($sql))
        return false;
        		
					$via = $request['via_administracion'];
			
			 	 $sql ="INSERT INTO esm_formula_externa_medicamentos_tmp
				                  	(fe_medicamento_id,
                             tmp_formula_id,
                             codigo_producto, 
                             cantidad,
                             observacion, 
                             via_administracion_id,
                             dosis,
                             unidad_dosificacion,
                             tiempo_tratamiento,
                             unidad_tiempo_tratamiento,
                             periodicidad_entrega,
                             unidad_periodicidad_entrega,
                             tipo_id_paciente,
                             paciente_id,
                             usuario_id )
									VALUES ($documento,
									".$request['tmp_id'].",
									'".$request['codigo_medicamento']."',
									".$request['cantidad'].", 
									'".$request['observacion']."',
									 ".$via.",
									 ".$request['dosis'].",
									 '".$request['unidad_dosis']."',
									  ".$request['tiempo_total'].",
									 '".$request['tiempo_total2']."',
									 ".$request['perioricidad_entrega'].",
									 '".$request['perioricidad_entrega2']."',
									 '".$request['tipo_id_paciente']."',
									 '".$request['paciente_id']."',
									 ".UserGetUID()."
									
				
				)";
				
			
		if(!$rst = $this->ConexionTransaccion($sql))
		return false;
     
		if ($request['opcion'] == '1')
		{
					$query="INSERT INTO esm_formula_externa_posologia_tmp
												(
                                fe_medicamento_id,
                                opcion,
                                periocidad_id, 	
                                tiempo
												)
												VALUES (".$documento.",
												'".$request['opcion']."',
						        				".$request['periocidad'].",
												'".$request['tiempo']."'
												)";
					if(!$rst = $this->ConexionTransaccion($query))
          return false;
		}
		
				if ($request['opcion'] == '2')
			    {
						
					$query="INSERT INTO esm_formula_externa_posologia_tmp
												(
                              fe_medicamento_id,
                              opcion,
                              duracion_id	

												)
												VALUES (".$documento.",
												'".$request['opcion']."',
						        				'".$request['duracion']."'
												)";
								
						           if(!$rst = $this->ConexionTransaccion($query))
									return false;
									
				}
				if ($request['opcion'] == '3')
			    {
				 	if ((!empty($request['desayuno'])) OR	(!empty($request['almuerzo'])) OR (!empty($request['cena'])))
                                                    {
					$query="INSERT INTO esm_formula_externa_posologia_tmp
												(
                                  fe_medicamento_id,
                                  opcion,
                                  sw_estado_momento,
                                  sw_estado_desayuno,
                                  sw_estado_almuerzo,
                                  sw_estado_cena

												    
												)
												VALUES (".$documento.",
												'".$request['opcion']."',
						        				'".$request['momento']."',
												'".$request['desayuno']."',
												'".$request['almuerzo']."',
												'".$request['cena']."'
												)";
								   if(!$rst = $this->ConexionTransaccion($query))
									return false;
								
					  }
					  
					  if ((!empty($request['durante_tratamiento_'])))
					  {
					      $query="INSERT INTO esm_formula_externa_posologia_tmp
												(
                                  fe_medicamento_id,
                                  opcion,
                                  sw_durante_tratamiento 	
											    
												)
												VALUES (".$documento.",
												'".$request['opcion']."',
						        				'1'
												
												)";
								   if(!$rst = $this->ConexionTransaccion($query))
									return false;
					  
					  }
					  
				}
					if ($request['opcion']== '4')
					{
					
									foreach($request['opH'] as $index=>$codigo)
									{
										$arreglo=explode(",",$codigo);
										
										$query="INSERT INTO esm_formula_externa_posologia_tmp
											(
                              fe_medicamento_id,
                              opcion,
                              hora_especifica
											    
											)
												VALUES (".$documento.",
												'".$request['opcion']."',
						        				 '".$arreglo[0]."'
											)";
											if(!$rst = $this->ConexionTransaccion($query))
									return false;
									}
						
					}
				
          
         $this->Commit();
         return true;
		}
	}
	   /* 
     * Funcion donde se elimina todo el temporal
     * @return array
    */

	  function Eliminar_DX_Ttmp($tipo_id,$id_paciente)
		{
			
        $sql = " Delete     FROM  esm_formula_externa_diagnosticos_tmp  ";
        $sql .= "where  	usuario_id=".UserGetUID()."
                and        tipo_id_paciente = '".$tipo_id."'
                AND         paciente_id = '".$id_paciente."'
			          ";
		    	
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
     * Funcion donde se guarda los insumos 
     * @return boolean
    */

     function Insertar_Insumos($formula_id,$codigo,$cantidad,$tiempo,$unidad,$tipo_id_paciente,$paciente_id)
		 {  
		 
        $this->ConexionTransaccion();
        $cont=0;
        $sql  = "SELECT NEXTVAL('esm_formula_externa_medicamentos_tmp_fe_medicamento_id_seq') AS documento ";

        if(!$rst = $this->ConexionTransaccion($sql))
        return false;
        
	      $indice = $rst->GetRowAssoc($ToUpper = false);
	      $documento = $indice['documento'];
	      if(!$rst = $this->ConexionTransaccion($sql))
	      return false;
        		
			$sql ="INSERT INTO esm_formula_externa_medicamentos_tmp
				                  	(fe_medicamento_id,
                             tmp_formula_id,
                             codigo_producto, 
                             cantidad,
                             tiempo_tratamiento,
                             unidad_tiempo_tratamiento,
                             tipo_id_paciente,
                             paciente_id,
                              usuario_id )
                            VALUES ($documento,
                            ".$formula_id.",
                            '".$codigo."',
                            ".$cantidad.", 
                             ".$tiempo.",
                             '".$unidad."',
                             '".$tipo_id_paciente."',
                             '".$paciente_id."',
                           ".UserGetUID()."
											
				)";
				
			
		if(!$rst = $this->ConexionTransaccion($sql))
		return false;
     
	
		 $query="INSERT INTO esm_formula_externa_posologia_tmp
												(
                              fe_medicamento_id,
                              opcion,
                              sw_durante_tratamiento 	
																								    
												)
												VALUES (".$documento.",
												'3',
						        				'1'
												
												)";
								   if(!$rst = $this->ConexionTransaccion($query))
									return false;
	
	     $this->Commit();
         return true;
		}
    /* 
     * Funcion donde se consulta el tope de la ESM 
     * @return array con la informacion de la consulta
    */

      function Consultar_saldo_tope_($fecha_actual,$tipo_id_tercero,$tercero_id)
        {
		 
          $sql = "  SELECT  saldo_tope, saldo_minimo
                        FROM    esm_empresas_topes
                        WHERE   fecha_inicio_tope <= '".$fecha_actual."'
                        AND     fecha_final_tope >= '".$fecha_actual."'
                        AND     tipo_id_tercero = '".$tipo_id_tercero."'
                        AND     tercero_id = '".$tercero_id."'
                     ";
        
				 if(!$rst = $this->ConexionBaseDatos($sql))
          				return false;
          				$datos = array();
          				while(!$rst->EOF)
          				{
          				$datos = $rst->GetRowAssoc($ToUpper);
          				$rst->MoveNext();
          				}
          				$rst->Close();
          				return $datos;
		
      }
  
    /* 
     * Funcion donde se consulta el detalle de los medicamentos ingresados a la formula temporal  
     * @return array con la informacion de la consulta
    */

     function Consultar_Medicamentos_Detalle($formula_id,$medicamento)
      {
             $sql = "  select      tmp.*,
                                    fc_descripcion_producto_alterno(tmp.codigo_producto) as descripcion_prod,
                                    POS.*,
                                    EXT.*
								
                     FROM       esm_formula_externa_medicamentos_tmp tmp,
                                esm_formula_externa_posologia_tmp POS,
                                esm_formula_externa_tmp EXT
                      where 
                      EXT.tmp_formula_id=tmp.tmp_formula_id
                      and  tmp.fe_medicamento_id=POS.fe_medicamento_id 
                      and  EXT.tmp_formula_id='".$formula_id."' and tmp.codigo_producto='".$medicamento."' ";
                    
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
	
      /* 
     * Funcion donde se consulta elfactor de conversion por medicamento
     * @return array con la informacion de la consulta
    */
		 function  ConsultarFactorConversion($medicamento)
		{
         
            $sql = "  SELECT  HF.codigo_producto,
                              HF.unidad_id,
                              HF.unidad_dosificacion,
                              HF.factor_conversion
                        FROM  hc_formulacion_factor_conversion HF,
                              unidades UN
                        WHERE HF.codigo_producto='".$medicamento."'
                        AND   HF.unidad_id = UN.unidad_id;";
    
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
     * Funcion donde se consulta la vias de administracion 
     * @return array con la informacion de la consulta
    */
    function Consultar_Via_Admin($via)
    {
			$sql = " 	SELECT nombre
						FROM   hc_vias_administracion
						WHERE  via_administracion_id = '".$via."'
			 ;  ";
                
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
     * Funcion donde se consulta las opciones de posologia asignada a la formulacion temporal
     * @return array con la informacion de la consulta
    */
    function Consulta_opc_Medicamentos_Posologia_tmp($formula_id)
     {
          $sql = " SELECT opcion
                  FROM    esm_formula_externa_posologia_tmp
                  WHERE   fe_medicamento_id = '".$formula_id."'  ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos= $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
				
       
	}
    /* 
     * Funcion donde se consulta la posologia del medicamento
     * @return array con la informacion de la consulta
    */
  
     function Consulta_Solicitud_Medicamentos_Posologia_tmp($opcion, $formulacion_id)
     {
        
          $sql == '';
          if ($opcion == 1)
          {
              $sql= "select periocidad_id, tiempo from esm_formula_externa_posologia_tmp where fe_medicamento_id = ".$formulacion_id." ";
          }
		  
		      if ($opcion == 2)
          {
               $sql= "select a.duracion_id, b.descripcion from esm_formula_externa_posologia_tmp as a , hc_horario as b where a.fe_medicamento_id = ".$formulacion_id."  and a.duracion_id = b.duracion_id";
          }
          if ($opcion == 3)
          {
	          $sql= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena, sw_durante_tratamiento from esm_formula_externa_posologia_tmp  where fe_medicamento_id = ".$formulacion_id." ";
           }
          if ($opcion == 4)
          {
     	     $sql= "select hora_especifica from esm_formula_externa_posologia_tmp where fe_medicamento_id = ".$formulacion_id."  ";
          }
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
     * Funcion donde se consulta las unidades de dosificacion
     * @return array con la informacion de la consulta
    */
  
    function Unidades_Dosificacion()
    {
        
          $sql = "select unidad_dosificacion from hc_unidades_dosificacion";

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
     * Funcion donde se consulta la periocidad
     * @return array con la informacion de la consulta
    */
  	function Cargar_Periocidad()
   {
 		
		$sql = "select periocidad_id from hc_periocidad order by periocidad_indice_orden";

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
     * Funcion donde se consulta el horario 
     * @return array con la informacion de la consulta
    */

    function horario()
    {
 	
		  $sql = "select duracion_id, descripcion from hc_horario order by duracion_id";

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
     * Funcion donde se consulta la presentacion que tiene el medicamento 
     * @return array con la informacion de la consulta
    */
    function Unidad_Venta($codigo_producto)
		{
			
		    $sql ="select a.codigo_producto, a.contenido_unidad_venta, b.descripcion, a.unidad_id  from
				inventarios_productos as a, unidades as b where a.codigo_producto = '".$codigo_producto."'
				and a.unidad_id = b.unidad_id";
				 if(!$rst = $this->ConexionBaseDatos($sql))	return false;
					$datos = array();
					while (!$rst->EOF)
					{
					$datos = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
		}

   /* 
     * Funcion donde se consulta el factor de conversion teniendo en cuenta el codigo del producto y la unidad 
     * @return array con la informacion de la consulta
    */
  	function ObtenerFactorConversion($codigo_producto,$unidad)
		{
		
		    $sql ="SELECT codigo_producto,
							unidad_id,
							unidad_dosificacion,
							factor_conversion
					FROM    hc_formulacion_factor_conversion
					WHERE   codigo_producto = '".$codigo_producto."'
					AND     unidad_id = '".$unidad."' ";
					
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
     * Funcion donde se consulta el tipo de formula creado en el temporal es decir si es digitalizacion o transcripcion 
     * @return array con la informacion de la consulta
    */
    function consultar_tipo_formula_tmp($request,$empresa)
    {
      
        $sql = "	SELECT  tipo_formulacion
              FROM    esm_formula_externa_tmp
              WHERE   tmp_formula_id = ".$request['tmp_id']." 
              AND     tmp_empresa_id = '".$empresa."' 
              AND     tipo_id_paciente = '".$request['tipo_id_paciente']."'
              AND     paciente_id = '".$request['paciente_id']."'
              AND     usuario_id =".UserGetUID()." ";

                if(!$rst = $this->ConexionBaseDatos($sql)) return false;

                $datos = array();
                while(!$rst->EOF)
                {
                $datos= $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
                }
                $rst->Close();
                return $datos;
	
	}
	  /* 
     * Funcion donde se consulta la informacion de la formula de digitalizacion
     * @return array con la informacion de la consulta
    */
    function consultar_Formulacion_ITMP($request,$empresa)
    {
      $sql = "SELECT tmp_formula_id,
                      tmp_empresa_id,
                      tmp_formula_papel,
                      to_char(fecha_formula,'dd-mm-yyyy') as fecha_formula,
                      hora_formula,
                      tipo_formula,
                      tipo_evento_id,
                      tipo_fuerza_id,
                      tipo_id_tercero,
                      tercero_id,
                      tipo_id_paciente,
                      paciente_id,
                      plan_id,
                      rango,
                      tipo_afiliado_id,
                      semanas_cotizadas,
                      esm_tipo_id_tercero,
                      esm_tercero_id
                          
					FROM    esm_formula_externa_tmp
					WHERE   tmp_formula_id = ".$request['tmp_id']." 
					AND     tmp_empresa_id = '".$empresa."' 
					AND     tipo_id_paciente = '".$request['tipo_id_paciente']."'
					AND     paciente_id = '".$request['paciente_id']."'
					AND     usuario_id =".UserGetUID()." ";
	
		 	
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			      $datos = array();
			      while(!$rst->EOF)
			      {
			         $datos[]= $rst->GetRowAssoc($ToUpper = false);
			        $rst->MoveNext();
			      }
			      $rst->Close();
			      return $datos;
	
	}
   /* 
     * Funcion donde se consulta la informacion de la formula de transcripcion
     * @return array con la informacion de la consulta
    */
   
    function consultar_Formulacion_ETMP($request,$empresa)
    {
	
        $sql = "SELECT  tmp_formula_id,
                tmp_empresa_id,
                tmp_formula_papel,
                to_char(fecha_formula,'dd-mm-yyyy') as fecha_formula,
                hora_formula,
                tipo_formula,
                tipo_evento_id,
                tipo_fuerza_id,
                tipo_id_tercero,
                tercero_id,
                tipo_id_paciente,
                paciente_id,
                plan_id,
                rango,
                tipo_afiliado_id,
                semanas_cotizadas,
                esm_tipo_id_tercero,
                esm_tercero_id,
                esm_autoriza_tipo_id_tercero,
                esm_autoriza_tercero_id,
                ips_tipo_id_tercero,
                ips_tercero_id,
                ips_profesional_tipo_id_tercero,
                ips_profesional_tercero_id,
                costo_formula
                      
              FROM    esm_formula_externa_tmp
              WHERE   tmp_formula_id = ".$request['tmp_id']." 
              AND     tmp_empresa_id = '".$empresa."' 
              AND     tipo_id_paciente = '".$request['tipo_id_paciente']."'
              AND     paciente_id = '".$request['paciente_id']."'
              AND     usuario_id =".UserGetUID()." ";
      
          
            if(!$rst = $this->ConexionBaseDatos($sql)) return false;

                $datos = array();
                while(!$rst->EOF)
                {
                   $datos[]= $rst->GetRowAssoc($ToUpper = false);
                  $rst->MoveNext();
                }
                $rst->Close();
                return $datos;
      
    } 
   /* 
     * Funcion donde se crea la formula real
     * @return boolean
    */
      
	 function FormulaReal_($Cabecera_Formulacion_,$DX_,$MEDIC_,$tipo_formula)
    {
		  
        $this->ConexionTransaccion();
        $sql  = "SELECT nextval('esm_formula_externa_formula_id_seq') AS formula_id ";
        if(!$rst = $this->ConexionTransaccion($sql))
        return false;

        $indice = $rst->GetRowAssoc($ToUpper = false);
        $formula_id = $indice['formula_id'];
		
				if(!empty($Cabecera_Formulacion_[0]['tipo_fuerza_id'])){
					$fuerza= " ,tipo_fuerza_id,";
					$fuerza_id=" ,'".$Cabecera_Formulacion_[0]['tipo_fuerza_id']."', ";
				}else
				{ 
						$fuerza= " ,";
						$fuerza_id=" ," ;
				}
				
	        if($tipo_formula==0)
	        {
			 
				$sql = " INSERT INTO esm_formula_externa
							(
								formula_id,
								empresa_id,		
								formula_papel,
	                            fecha_formula,
								hora_formula,
								tipo_formula,
								tipo_evento_id
								$fuerza	
								tipo_id_tercero,	
								tercero_id,	
								tipo_id_paciente,	
								paciente_id,	
								plan_id,		
								rango,	
								tipo_afiliado_id,
								esm_tipo_id_tercero,		
								esm_tercero_id,	
								usuario_id
									
					)
						VALUES
						(
                     $formula_id,
                    '".$Cabecera_Formulacion_[0]['tmp_empresa_id']."' ,
                    '".$Cabecera_Formulacion_[0]['tmp_formula_papel']."' ,
                    '".$Cabecera_Formulacion_[0]['fecha_formula']."' ,
                    '".$Cabecera_Formulacion_[0]['hora_formula']."' ,
                    ".$Cabecera_Formulacion_[0]['tipo_formula']." ,
                    ".$Cabecera_Formulacion_[0]['tipo_evento_id']."
                    $fuerza_id
                    '".$Cabecera_Formulacion_[0]['tipo_id_tercero']."',
                    '".$Cabecera_Formulacion_[0]['tercero_id']."',
                    '".$Cabecera_Formulacion_[0]['tipo_id_paciente']."',
                    '".$Cabecera_Formulacion_[0]['paciente_id']."',
                    ".$Cabecera_Formulacion_[0]['plan_id'].",
                    '".$Cabecera_Formulacion_[0]['rango']."',
                    '".$Cabecera_Formulacion_[0]['tipo_afiliado_id']."',
                    '".$Cabecera_Formulacion_[0]['esm_tipo_id_tercero']."',
                    '".$Cabecera_Formulacion_[0]['esm_tercero_id']."',
                    ".UserGetUID()."
							);
							";
			  if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				
		    }
			if($tipo_formula==1)
			{
			
				$sql = " INSERT INTO esm_formula_externa
							(
									formula_id,
									empresa_id,		
									formula_papel,
		                            fecha_formula,
									hora_formula,
									tipo_formula,
									tipo_evento_id
									$fuerza	
									tipo_id_tercero,	
									tercero_id,	
									tipo_id_paciente,	
									paciente_id,	
									plan_id,		
									rango,	
									tipo_afiliado_id,
									esm_tipo_id_tercero,		
									esm_tercero_id,	
									esm_autoriza_tipo_id_tercero,
									esm_autoriza_tercero_id ,
									ips_tipo_id_tercero,
									ips_tercero_id,
									ips_profesional_tipo_id_tercero,
									ips_profesional_tercero_id,
									costo_formula,
									usuario_id
							)
							VALUES
							(
                  $formula_id,
                  '".$Cabecera_Formulacion_[0]['tmp_empresa_id']."' ,
                  '".$Cabecera_Formulacion_[0]['tmp_formula_papel']."' ,
                  '".$Cabecera_Formulacion_[0]['fecha_formula']."' ,
                  '".$Cabecera_Formulacion_[0]['hora_formula']."' ,
                  ".$Cabecera_Formulacion_[0]['tipo_formula']." ,
                  ".$Cabecera_Formulacion_[0]['tipo_evento_id']."
                  $fuerza_id
                  '".$Cabecera_Formulacion_[0]['tipo_id_tercero']."',
                  '".$Cabecera_Formulacion_[0]['tercero_id']."',
                  '".$Cabecera_Formulacion_[0]['tipo_id_paciente']."',
                  '".$Cabecera_Formulacion_[0]['paciente_id']."',
                  ".$Cabecera_Formulacion_[0]['plan_id'].",
                  '".$Cabecera_Formulacion_[0]['rango']."',
                  '".$Cabecera_Formulacion_[0]['tipo_afiliado_id']."',
                  '".$Cabecera_Formulacion_[0]['esm_tipo_id_tercero']."',
                  '".$Cabecera_Formulacion_[0]['esm_tercero_id']."',
                  '".$Cabecera_Formulacion_[0]['esm_autoriza_tipo_id_tercero']."',
                  '".$Cabecera_Formulacion_[0]['esm_autoriza_tercero_id']."',
                  '".$Cabecera_Formulacion_[0]['ips_tipo_id_tercero']."',
                  '".$Cabecera_Formulacion_[0]['ips_tercero_id']."',
                  '".$Cabecera_Formulacion_[0]['ips_profesional_tipo_id_tercero']."',
                  '".$Cabecera_Formulacion_[0]['ips_profesional_tercero_id']."',
                  ".$Cabecera_Formulacion_[0]['costo_formula'].",
                  ".UserGetUID()."
								);
								";
				
				  if(!$rst1 = $this->ConexionTransaccion($sql))
					{
					return false;
					}
	
			}
			foreach($DX_ as $key => $dtl)
			{
				
				$sql=" INSERT INTO esm_formula_externa_diagnosticos
	                            ( 
                                  fe_diagnostico_id,
                                   formula_id,
	                                 diagnostico_id
	                              )VALUES(
								    nextval('esm_formula_externa_diagnosticos_fe_diagnostico_id_seq'),
	                                 $formula_id,
	                                '".$dtl['diagnostico_id']."'
	                            
	                              )";
          if(!$rst1 = $this->ConexionTransaccion($sql))
					{
					return false;
					}
					
			}
			
			foreach($MEDIC_ as $key => $dtl_m)
		    {
		      
			        $sql  = "SELECT nextval('esm_formula_externa_medicamentos_fe_medicamento_id_seq') AS documento ";
        
					if(!$rst = $this->ConexionTransaccion($sql))
					return false;
        
			        $indice = $rst->GetRowAssoc($ToUpper = false);
			        $documento = $indice['documento'];
					
					if(!$rst = $this->ConexionTransaccion($sql))
					return false;
					if($dtl_m['periodicidad_entrega']!="")
					{
										
		        		
						 	 $sql =" INSERT INTO esm_formula_externa_medicamentos
						                  	(
												 fe_medicamento_id,
												 formula_id,
												 codigo_producto, 
												 cantidad,
												 observacion, 
												 dosis,
												 unidad_dosificacion,
												 tiempo_tratamiento,
												 unidad_tiempo_tratamiento,
												 periodicidad_entrega,
												 unidad_periodicidad_entrega,
												 via_administracion_id
											 )
											VALUES (
                              $documento,
                              $formula_id,
                              '".$dtl_m['codigo_producto']."',
                              ".$dtl_m['cantidad'].", 
                              '".$dtl_m['observacion']."',
                              ".$dtl_m['dosis'].",
                              '".$dtl_m['unidad_dosificacion']."',
                              ".$dtl_m['tiempo_tratamiento'].",
                              '".$dtl_m['unidad_tiempo_tratamiento']."',
                              ".$dtl_m['periodicidad_entrega'].",
                              '".$dtl_m['unidad_periodicidad_entrega']."',
                              '".$dtl_m['via_administracion_id']."'
											)";
						
											if(!$rst = $this->ConexionTransaccion($sql))
											return false;
					  
								$opcion_posol=$this->Consulta_opc_Medicamentos_Posologia_tmp($dtl_m['fe_medicamento_id']);
								$informacion_posologia=$this->Consulta_Solicitud_Medicamentos_Posologia_tmp($opcion_posol['opcion'],$dtl_m['fe_medicamento_id']);
						     if ($opcion_posol['opcion'] == '1')
				         {
								       
                      foreach($informacion_posologia as $key => $dtl_p)
                      {
									
                        $query= " INSERT INTO esm_formula_externa_posologia
                                  (
                                        fe_medicamento_id,
                                        opcion,
                                        periocidad_id, 	
                                        tiempo
															)
															VALUES (
                                    ".$documento.",
                                    '".$opcion_posol['opcion']."',
                                    ".$dtl_p['periocidad_id'].",
                                    '".$dtl_p['tiempo']."'
															)";
										
												    if(!$rst = $this->ConexionTransaccion($query))
													return false;
										}
								}
				
								if ($opcion_posol['opcion'] == '2')
								{   
								    foreach($informacion_posologia as $key => $dtl_p)
									{
								
											$query="INSERT INTO esm_formula_externa_posologia
																	(
                                          fe_medicamento_id,
                                          opcion,
                                          duracion_id	
																	    
																	)
																	VALUES (
                                        ".$documento.",
                                        '".$opcion_posol['opcion']."',
                                        '".$dtl_p['duracion_id']."'
																	)";
										
								           if(!$rst = $this->ConexionTransaccion($query))
											return false;
									}	
						        }
								if($opcion_posol['opcion']== '3')
								{
									 foreach($informacion_posologia as $key => $dtl_p)
									{
									   if($dtl_p['sw_durante_tratamiento']=='0')
									   {
										$query="INSERT INTO esm_formula_externa_posologia
																			(
                                            fe_medicamento_id,
                                            opcion,
                                            sw_estado_momento,
                                            sw_estado_desayuno,
                                            sw_estado_almuerzo,
                                            sw_estado_cena,
                                            sw_durante_tratamiento


																			)
																			VALUES (".$documento.",
																			'".$opcion_posol['opcion']."',
													        				'".$dtl_p['sw_estado_momento']."',
																			'".$dtl_p['sw_estado_desayuno']."',
																			'".$dtl_p['sw_estado_almuerzo']."',
																			'".$dtl_p['sw_estado_cena']."',
																			'".$dtl_p['sw_durante_tratamiento']."'
																			)";
															   if(!$rst = $this->ConexionTransaccion($query))
																return false;
										}else
										{
										    $query="INSERT INTO esm_formula_externa_posologia
																			(
                                            fe_medicamento_id,
                                            opcion,
                                            sw_durante_tratamiento
																				 
																			    
																			)
																			VALUES (".$documento.",
																			'".$opcion_posol['opcion']."',
													        				'".$dtl_p['sw_durante_tratamiento']."'
																			)";
															   if(!$rst = $this->ConexionTransaccion($query))
																return false;
																
									    }			
										
							       }
						        }
							if ($opcion_posol['opcion']== '4')
							{
							    foreach($informacion_posologia as $key => $dtl_p)
								{
							     			foreach($dtl_p['hora_especifica'] as $index=>$codigo)
											{
												$arreglo=explode(",",$codigo);
												
												$query="INSERT INTO esm_formula_externa_posologia
													(
                                      fe_medicamento_id,
                                      opcion,
                                      hora_especifica

														    
													)
														VALUES (".$documento.",
														  '".$opcion_posol['opcion']."',
								        				 '".$arreglo[0]."'
													)";
											      if(!$rst = $this->ConexionTransaccion($query))
											     return false;
											}
								
							        }
						   }
						
						
						
					}else
					{
					
					 $sql =" INSERT INTO esm_formula_externa_medicamentos
						                  	(
												 fe_medicamento_id,
												 formula_id,
												 codigo_producto, 
												 cantidad,
												 tiempo_tratamiento,
												 unidad_tiempo_tratamiento
												
											 )
											VALUES (
											$documento,
											 $formula_id,
											'".$dtl_m['codigo_producto']."',
											".$dtl_m['cantidad'].", 
											".$dtl_m['tiempo_tratamiento'].",
											 '".$dtl_m['unidad_tiempo_tratamiento']."'
											 
											)";
						
											if(!$rst = $this->ConexionTransaccion($sql))
											return false;
					  
											$query= " INSERT INTO esm_formula_externa_posologia
														(
															   fe_medicamento_id,
																opcion,
															    sw_durante_tratamiento 	
															  
														)
															VALUES (
															".$documento.",
															'3',
									        				1
															)";
										
												    if(!$rst = $this->ConexionTransaccion($query))
													return false;
										
					}
			}
	   $this->Commit();
		return true; 
			
	}
  /* 
     * Funcion donde se borra todo lo del temporal de la formula 
     * @return array
    */
    function Eliminar_tmp($request,$empresa)
		{
	   
			$sql = " Delete    FROM  esm_formula_externa_medicamentos_tmp  
					WHERE   tmp_formula_id = ".$request['tmp_id']." 
					AND     tipo_id_paciente = '".$request['tipo_id_paciente']."'
					AND     paciente_id = '".$request['paciente_id']."'
					AND     usuario_id =".UserGetUID().";  ";
				
			$sql .= " Delete    FROM  esm_formula_externa_diagnosticos_tmp  
                WHERE   tmp_formula_id = ".$request['tmp_id']." 
					; ";
					
			$sql .= " Delete     FROM  esm_formula_externa_tmp  
					WHERE   tmp_formula_id = ".$request['tmp_id']." 
					AND     tmp_empresa_id = '".$empresa."' 
					AND     tipo_id_paciente = '".$request['tipo_id_paciente']."'
					AND     paciente_id = '".$request['paciente_id']."'
					AND     usuario_id =".UserGetUID()." ";
	
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
     * Funcion donde se consulta el ultimo registro del paciente  
     * @return array
    */
 
	   function Consulta_Max_Formulacion($empresa,$tipo_paciente,$paciente)
		{
			
			$sql = "SELECT (COALESCE(MAX(formula_id),0)) AS   tmp_id FROM  esm_formula_externa where 	empresa_id='".$empresa."'
							       and  tipo_id_paciente='".$tipo_paciente."' and paciente_id='".$paciente."' and sw_estado='1' and sw_corte='0'	";
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos= $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		
		
		}
   	/* 
     * Funcion donde se consulta el que tipo de formula es la formula real 
     * @return array
    */
	 function Consulta_Formulacion_Real_I($formula)
		{
		
			$sql = "SELECT  FR.empresa_id,
                      EMP.razon_social,
                      FR.formula_papel,
                      to_char(FR.fecha_formula,'dd-mm-yyyy') as fecha_formula,
                      FR.hora_formula,
                      FR.tipo_formula,
                      FR.tipo_evento_id,
                      FR.tipo_fuerza_id,
                      FR.tipo_id_tercero,
                      FR.tercero_id,
                      FR.tipo_id_paciente,
                      FR.paciente_id,
                      FR.plan_id,
                      FR.rango,
                      FR.tipo_afiliado_id,
                      FR.semanas_cotizadas,
                      FR.esm_tipo_id_tercero,
                      FR.esm_tercero_id,
                      FR.usuario_id,
                      to_char(FR.fecha_registro,'dd-mm-yyyy') as fecha_registro,
                      TIPOF.descripcion_tipo_formula,
                      TIPOV.descripcion_tipo_evento,
                      PROF.nombre AS profesional_esm,
                      TIPOPROF.descripcion as descripcion_profesional_esm,
                        TERC.nombre_tercero AS ESM_atendio,
                      PAC.primer_nombre || ' ' ||  PAC.segundo_nombre|| ' ' || PAC.primer_apellido|| ' ' ||PAC.segundo_apellido AS nombre_paciente,
                      PAC.sexo_id,
                      edad(PAC.fecha_nacimiento) as edad,
                      PLAN.plan_descripcion 
                      
							
							
					FROM    esm_formula_externa FR,
					        empresas EMP,
							esm_tipos_formulas TIPOF,
							esm_tipos_eventos  TIPOV,
							esm_profesionales_empresas ESM_PROF,
							profesionales PROF,
							tipos_profesionales TIPOPROF,
							esm_empresas ESM_EMPRESA,
							terceros TERC,
							pacientes  PAC,
							planes_rangos PLANR,
							planes PLAN
						
					WHERE   FR.formula_id = '".$formula."'
					AND     FR.empresa_id=EMP.empresa_id
					AND     FR.tipo_formula=TIPOF.tipo_formula_id
			        AND     FR.tipo_evento_id=TIPOV.tipo_evento_id
					AND     FR.tipo_id_tercero=ESM_PROF.tipo_id_tercero
					AND		FR.tercero_id=ESM_PROF.tercero_id
					AND     FR.esm_tipo_id_tercero=ESM_PROF.tipo_id_tercero_esm
					AND 	FR.esm_tercero_id=ESM_PROF.tercero_id_esm
					AND     FR.tipo_id_tercero=PROF.tipo_id_tercero
					AND		FR.tercero_id=PROF.tercero_id
					AND     PROF.tipo_profesional =TIPOPROF.tipo_profesional
					AND     ESM_PROF.tipo_id_tercero_esm=ESM_EMPRESA.tipo_id_tercero
					AND     ESM_PROF.tercero_id_esm=ESM_EMPRESA.tercero_id
					AND     ESM_EMPRESA.tipo_id_tercero=TERC.tipo_id_tercero
					AND     ESM_EMPRESA.tercero_id=TERC.tercero_id
					AND     FR.tipo_id_paciente=PAC.tipo_id_paciente
					AND		FR.paciente_id=PAC.paciente_id
					AND     FR.plan_id=PLANR.plan_id
					AND		FR.rango=PLANR.rango
					AND		FR.tipo_afiliado_id=PLANR.tipo_afiliado_id
					AND     PLANR.plan_id=PLAN.plan_id";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos= $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		
		
		}
     	/* 
     * Funcion donde se consulta la informacion adicional de la ESM para las formulas de transcripcion 
     * @return array
    */
    
    function Consulta_Formulacion_Real_A($formula)
		{
			
			$sql = "SELECT  	FR.esm_tipo_id_tercero,
								FR.esm_tercero_id,
								FR.esm_autoriza_tipo_id_tercero,
								FR.esm_autoriza_tercero_id,
								PROF.nombre AS profesional_esm,
								TIPOPROF.descripcion as descripcion_profesional_esm,
								 TERC.nombre_tercero AS ESM_atendio,
								 FR.costo_formula
								
					FROM        esm_formula_externa FR,
								esm_profesionales_empresas ESM_PROF,
								profesionales PROF,
								esm_empresas ESM_EMPRESA,
								terceros TERC,
								tipos_profesionales TIPOPROF
						
						WHERE   FR.formula_id = '".$formula."'
						AND      	FR.esm_autoriza_tipo_id_tercero=ESM_PROF.tipo_id_tercero
						AND		    FR.esm_autoriza_tercero_id=ESM_PROF.tercero_id
						AND         FR.esm_tipo_id_tercero=ESM_PROF.tipo_id_tercero_esm
						AND 	    FR.esm_tercero_id=ESM_PROF.tercero_id_esm
						AND         ESM_PROF.tipo_id_tercero=PROF.tipo_id_tercero
						AND         ESM_PROF.tercero_id=PROF.tercero_id
						AND			ESM_PROF.tipo_id_tercero_esm=ESM_EMPRESA.tipo_id_tercero
						AND         ESM_PROF.tercero_id_esm=ESM_EMPRESA.tercero_id
						AND        ESM_EMPRESA.tipo_id_tercero=TERC.tipo_id_tercero
						AND        ESM_EMPRESA.tercero_id=TERC.tercero_id
						AND        PROF.tipo_profesional =TIPOPROF.tipo_profesional";
						
					
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos= $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		
		
		}
/* 
     * Funcion donde se consulta la formulacion de la ips de las formulas externas o de transcripcion 
     * @return array
    */
	 function Consulta_Formulacion_Real_AE($formula)
		{
			
			$sql = "
					SELECT  	FR.ips_tipo_id_tercero,
								FR.ips_tercero_id,
								FR.ips_profesional_tipo_id_tercero,
								FR.ips_profesional_tercero_id,
								TERC.nombre_tercero AS IPS_ATENDIDO,
								PROF.nombre AS profesional_ips,
								TIPOPROF.descripcion as descripcion_profesional_ips,
							   MP.municipio || ' ' || TD.departamento || ' ' || TP.pais AS ubicacion
								
					FROM        esm_formula_externa FR,
					            esm_ips_profesionales  IPS_P,
								esm_ips_terceros  TERCIP,
								terceros TERC,
								profesionales PROF,
								tipos_profesionales TIPOPROF,
							    tipo_mpios MP,
								tipo_dptos TD,
								tipo_pais TP
							
			
						WHERE   FR.formula_id = '".$formula."'
						and         FR.ips_tipo_id_tercero=IPS_P.tipo_id_tercero_ips
						and         FR.ips_tercero_id=IPS_P.tercero_id_ips
						and         FR.ips_profesional_tipo_id_tercero=IPS_P.tipo_id_tercero
						and         FR.ips_profesional_tercero_id=IPS_P.tercero_id
						and         IPS_P.tipo_id_tercero_ips=TERCIP.tipo_id_tercero
						and         IPS_P.tercero_id_ips=TERCIP.tercero_id
						and         TERCIP.tipo_id_tercero=TERC.tipo_id_tercero
						and         TERCIP.tercero_id=TERC.tercero_id
						AND         IPS_P.tipo_id_tercero=PROF.tipo_id_tercero
						AND         IPS_P.tercero_id=PROF.tercero_id
						and          PROF.tipo_profesional =TIPOPROF.tipo_profesional
						AND          TERC.tipo_pais_id=MP.tipo_pais_id
						AND     TERC.tipo_dpto_id=MP.tipo_dpto_id
						AND     TERC.tipo_mpio_id=MP.tipo_mpio_id
						AND     MP.tipo_pais_id=TD.tipo_pais_id
						AND     MP.tipo_dpto_id=TD.tipo_dpto_id
						AND     TD.tipo_pais_id=TP.tipo_pais_id 	 ; ";
						
		
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos= $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		
		
		}
		/* 
     * Funcion donde se consulta el diagnostico real de la formula 
     * @return array
    */

    function Diagnostico_Real($tmp_id)
		{
		
		    $sql ="SELECT  DXT.diagnostico_id,
						   DX.diagnostico_nombre
					FROM   esm_formula_externa_diagnosticos DXT,
						   diagnosticos DX
					WHERE DXT.diagnostico_id=DX.diagnostico_id 
					and   DXT.formula_id='".$tmp_id."' ";
		
				if(!$rst = $this->ConexionBaseDatos($sql))	return false;
					$datos = array();
					while (!$rst->EOF)
					{
					$datos []= $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
		}
    
    /* 
     * Funcion donde se consulta los medicamentos formulados  
     * @return array
    */
    function Medicamentos_Formulados_R($tmp_id)
		{
			
		   
		    $sql = "SELECT  tmp.fe_medicamento_id,
							tmp.codigo_producto,
							tmp.cantidad,
							tmp.observacion,
							tmp.dosis,
							tmp.unidad_dosificacion,
							tmp.tiempo_tratamiento,
							tmp.unidad_tiempo_tratamiento,
							tmp.periodicidad_entrega,
							tmp.unidad_periodicidad_entrega,
							tmp.via_administracion_id,
						    fc_descripcion_producto_alterno(tmp.codigo_producto) as descripcion_prod,
							A.descripcion as producto,
							b.concentracion_forma_farmacologica,
							b.unidad_medida_medicamento_id,
							b.factor_conversion,
							b.factor_equivalente_mg,
							d.descripcion as forma,
							c.descripcion as principio_activo,
							d.cod_forma_farmacologica,
							tmp.sw_marcado,
							fc_codigo_mindefensa(tmp.codigo_producto) as min_defensa
					 FROM   esm_formula_externa_medicamentos tmp,
							inventarios_productos A LEFT JOIN medicamentos b ON (A.codigo_producto = b.codigo_medicamento) LEFT JOIN inv_med_cod_principios_activos c on(b.cod_principio_activo = c.cod_principio_activo) LEFT JOIN inv_med_cod_forma_farmacologica  d ON(b.cod_forma_farmacologica = d.cod_forma_farmacologica)
							
						
					 WHERE    tmp.formula_id='".$tmp_id."'
					 AND    tmp.codigo_producto= A.codigo_producto
					
			 ;  ";
	
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
     * Funcion donde se consulta el permiso para realizar la dispensacion
     * @return array
    */
    function ObtenerPermisos_dispensacion()
		{
		
		    $sql = " Select  a.empresa_id,
                        b.razon_social AS razon_social,
                        a.centro_utilidad,
                        c.descripcion AS centro_utilidad_des,
                        a.bodega,
                        e.descripcion as Bodega_des
               from     userpermisos_DispensacionESM a,
                        bodegas e,
                        centros_utilidad c,
                        empresas b
					where    a.empresa_id=e.empresa_id
					and      a.centro_utilidad=e.centro_utilidad
					and      a.bodega=e.bodega
					and      e.empresa_id=c.empresa_id
					and      e.centro_utilidad=c.centro_utilidad
					and      c.empresa_id=b.empresa_id
				    and      b.sw_activa = '1'
					               and      a.usuario_id = ".UserGetUID()." ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}

			$rst->Close();
			return $datos;
		}

	  /* 
     * Funcion donde seconsulta la opcion de posologia de la formula real
     * @return array
    */
	
	 function Consulta_opc_Medicamentos_PosologiaR($formula_id)
     {
        $sql = " SELECT opcion
         		FROM    esm_formula_externa_posologia
				WHERE   fe_medicamento_id = '".$formula_id."'  ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos= $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
				
       
	}
	  /* 
     * Funcion donde se consulta la posologia del medicamento
     * @return array
    */

      function Consulta_Solicitud_Medicamentos_Posologia($opcion, $formulacion_id)
     {
        
          $sql == '';
          if ($opcion == 1)
          {
          	$sql= "select periocidad_id, tiempo from esm_formula_externa_posologia where fe_medicamento_id = ".$formulacion_id." ";
          }
		  
		  
		  
          if ($opcion == 2)
          {
               $sql= "select a.duracion_id, b.descripcion from esm_formula_externa_posologia as a , hc_horario as b where a.fe_medicamento_id = ".$formulacion_id."  and a.duracion_id = b.duracion_id";
          
		 
		  }
          if ($opcion == 3)
          {
	          $sql= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from esm_formula_externa_posologia  where fe_medicamento_id = ".$formulacion_id." ";
          
	  
		  }
          if ($opcion == 4)
          {
     	     $sql= "select hora_especifica from esm_formula_externa_posologia where fe_medicamento_id = ".$formulacion_id."  ";
          }
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
     * Funcion donde se borra el medicamento ambulatorio
     * @return array
    */
    
    function Eliminar_prod_tmp_amb($dx,$tipo_id,$id_paciente,$fe_medicamento_id,$tmp_id)
		{
			
        
        $sql .= " Delete     FROM  esm_formula_externa_medicamentos_tmp  ";
        $sql .= "where  		codigo_producto='".$dx."' 
            and        usuario_id=".UserGetUID()."
            and        tipo_id_paciente = '".$tipo_id."'
            AND         paciente_id = '".$id_paciente."'
            AND        fe_medicamento_id='".$fe_medicamento_id."'
            and     	tmp_formula_id='".$tmp_id."'					
         ;  ";
            
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
     * Funcion donde se insertan los medicamentos ambulatorios
     * @return  boolean
    */
		
    function Medicamentos_ambulatorios_Ingreso($formula_id,$codigo,$cantidad,$tiempo,$unidad,$tipo_id_paciente,$paciente_id)
    {
	
        $this->ConexionTransaccion();
        $sql ="INSERT INTO esm_formula_externa_medicamentos_tmp
                          (fe_medicamento_id,
                          tmp_formula_id,
                          codigo_producto, 
                          cantidad,
                          tiempo_tratamiento,
                          unidad_tiempo_tratamiento,
                          tipo_id_paciente,
                          paciente_id,
                          usuario_id )
                        VALUES (DEFAULT,
                        ".$formula_id.",
                        '".$codigo."',
                        ".$cantidad.", 
                        ".$tiempo.",
                        '".$unidad."',
                        '".$tipo_id_paciente."',
                        '".$paciente_id."',
                        ".UserGetUID()."
				)";
          if(!$rst1 = $this->ConexionTransaccion($sql))
		      {
            return false;
		      }
				$this->Commit();
				return true;
	}
    /* 
     * Funcion donde se ingresa la formulacion ambulatoria real
     * @return  boolean
    */
	  
      function FormulaReal_AMB($Cabecera_Formulacion_,$DX_,$MEDIC_,$tipo_formula)
      {
		
          $this->ConexionTransaccion();

          $sql  = "SELECT nextval('esm_formula_externa_formula_id_seq') AS formula_id ";
          if(!$rst = $this->ConexionTransaccion($sql))
          return false;

          $indice = $rst->GetRowAssoc($ToUpper = false);
          $formula_id = $indice['formula_id'];
			
				if(!empty($Cabecera_Formulacion_[0]['tipo_fuerza_id'])){
					$fuerza= " ,tipo_fuerza_id,";
					$fuerza_id=" ,'".$Cabecera_Formulacion_[0]['tipo_fuerza_id']."', ";
				}else
				{ 
						$fuerza= " ,";
						$fuerza_id=" ," ;
				}
				
	        if($tipo_formula==0)
	        {
			 
				$sql = " INSERT INTO esm_formula_externa
							(
								formula_id,
								empresa_id,		
								formula_papel,
	                            fecha_formula,
								hora_formula,
								tipo_formula,
								tipo_evento_id
								$fuerza	
								tipo_id_tercero,	
								tercero_id,	
								tipo_id_paciente,	
								paciente_id,	
								plan_id,		
								rango,	
								tipo_afiliado_id,
								esm_tipo_id_tercero,		
								esm_tercero_id,	
								usuario_id
									
					)
						VALUES
						(
							$formula_id,
							 '".$Cabecera_Formulacion_[0]['tmp_empresa_id']."' ,
							'".$Cabecera_Formulacion_[0]['tmp_formula_papel']."' ,
							'".$Cabecera_Formulacion_[0]['fecha_formula']."' ,
							'".$Cabecera_Formulacion_[0]['hora_formula']."' ,
							".$Cabecera_Formulacion_[0]['tipo_formula']." ,
							".$Cabecera_Formulacion_[0]['tipo_evento_id']."
							 $fuerza_id
							'".$Cabecera_Formulacion_[0]['tipo_id_tercero']."',
							'".$Cabecera_Formulacion_[0]['tercero_id']."',
							'".$Cabecera_Formulacion_[0]['tipo_id_paciente']."',
							'".$Cabecera_Formulacion_[0]['paciente_id']."',
							".$Cabecera_Formulacion_[0]['plan_id'].",
							'".$Cabecera_Formulacion_[0]['rango']."',
							'".$Cabecera_Formulacion_[0]['tipo_afiliado_id']."',
							'".$Cabecera_Formulacion_[0]['esm_tipo_id_tercero']."',
							'".$Cabecera_Formulacion_[0]['esm_tercero_id']."',
						    ".UserGetUID()."
							

							);
							";
			
			  if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				
		    }
			if($tipo_formula==1)
			{
			
				$sql = " INSERT INTO esm_formula_externa
							(
									formula_id,
									empresa_id,		
									formula_papel,
		                            fecha_formula,
									hora_formula,
									tipo_formula,
									tipo_evento_id
									$fuerza	
									tipo_id_tercero,	
									tercero_id,	
									tipo_id_paciente,	
									paciente_id,	
									plan_id,		
									rango,	
									tipo_afiliado_id,
									esm_tipo_id_tercero,		
									esm_tercero_id,	
									esm_autoriza_tipo_id_tercero,
									esm_autoriza_tercero_id ,
									ips_tipo_id_tercero,
									ips_tercero_id,
									ips_profesional_tipo_id_tercero,
									ips_profesional_tercero_id,
									costo_formula,
									usuario_id
							)
							VALUES
							(
								$formula_id,
								 '".$Cabecera_Formulacion_[0]['tmp_empresa_id']."' ,
								'".$Cabecera_Formulacion_[0]['tmp_formula_papel']."' ,
								'".$Cabecera_Formulacion_[0]['fecha_formula']."' ,
								'".$Cabecera_Formulacion_[0]['hora_formula']."' ,
								".$Cabecera_Formulacion_[0]['tipo_formula']." ,
								".$Cabecera_Formulacion_[0]['tipo_evento_id']."
								 $fuerza_id
								'".$Cabecera_Formulacion_[0]['tipo_id_tercero']."',
								'".$Cabecera_Formulacion_[0]['tercero_id']."',
								'".$Cabecera_Formulacion_[0]['tipo_id_paciente']."',
								'".$Cabecera_Formulacion_[0]['paciente_id']."',
								".$Cabecera_Formulacion_[0]['plan_id'].",
								'".$Cabecera_Formulacion_[0]['rango']."',
								'".$Cabecera_Formulacion_[0]['tipo_afiliado_id']."',
								'".$Cabecera_Formulacion_[0]['esm_tipo_id_tercero']."',
								'".$Cabecera_Formulacion_[0]['esm_tercero_id']."',
								'".$Cabecera_Formulacion_[0]['esm_autoriza_tipo_id_tercero']."',
								'".$Cabecera_Formulacion_[0]['esm_autoriza_tercero_id']."',
								'".$Cabecera_Formulacion_[0]['ips_tipo_id_tercero']."',
								'".$Cabecera_Formulacion_[0]['ips_tercero_id']."',
								'".$Cabecera_Formulacion_[0]['ips_profesional_tipo_id_tercero']."',
								'".$Cabecera_Formulacion_[0]['ips_profesional_tercero_id']."',
								".$Cabecera_Formulacion_[0]['costo_formula'].",
							    ".UserGetUID()."
								

								);
								";
				
				  if(!$rst1 = $this->ConexionTransaccion($sql))
					{
					return false;
					}
					
					
			}
			foreach($DX_ as $key => $dtl)
			{
				
				$sql=" INSERT INTO esm_formula_externa_diagnosticos
	                            ( 
                              fe_diagnostico_id,
                              formula_id,
                              diagnostico_id
	                              )VALUES(
                              nextval('esm_formula_externa_diagnosticos_fe_diagnostico_id_seq'),
	                            $formula_id,
	                            '".$dtl['diagnostico_id']."'
	                            
	                              )";
	            if(!$rst1 = $this->ConexionTransaccion($sql))
					{
					return false;
					}
					
			}
			
			foreach($MEDIC_ as $key => $dtl_m)
		    {
		      
			      $sql  = "SELECT nextval('esm_formula_externa_medicamentos_fe_medicamento_id_seq') AS documento ";
        
					if(!$rst = $this->ConexionTransaccion($sql))
					return false;
        
			        $indice = $rst->GetRowAssoc($ToUpper = false);
			        $documento = $indice['documento'];
              
					if(!$rst = $this->ConexionTransaccion($sql))
					return false;
        		
				 	 $sql =" INSERT INTO esm_formula_externa_medicamentos
				                  	(
										 fe_medicamento_id,
										 formula_id,
										 codigo_producto, 
										 cantidad,
										 tiempo_tratamiento,
										 unidad_tiempo_tratamiento,
										 sw_marcado
										
									 )
									VALUES (
									$documento,
									 $formula_id,
									'".$dtl_m['codigo_producto']."',
									".$dtl_m['cantidad'].", 
								    ".$dtl_m['tiempo_tratamiento'].",
									 '".$dtl_m['unidad_tiempo_tratamiento']."',
									 '".$dtl_m['sw_marcado']."'
									)";
				
									if(!$rst = $this->ConexionTransaccion($sql))
									return false;
					        
			}
			
					
				   $this->Commit();
					return true; 
			
	}
		 /* 
     * Funcion donde se actualiza el estado de la formula a anulada
     * @return  boolean
    */
	 
	 function Actulizar_Estado_Formula_($formula)
    {
		  
        $this->ConexionTransaccion();
				$sql = " update esm_formula_externa 
				set 	 sw_estado='2'
				where   formula_id = ".$formula."
				 ";

          if(!$rst1 = $this->ConexionTransaccion($sql))
		      {
		      return false;
		      }
	
				$this->Commit();
				return true;
	}
  	 /* 
     * Funcion donde se  consulta el numero de la  formula de papele
     * @return  boolean
    */
		function consultar_Formulacion_Papel($tmp_id)
    {
	
      $sql = "SELECT  formula_papel
              FROM    esm_formula_externa 
              WHERE   formula_id = ".$tmp_id." ";
						if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			      $datos = array();
			      while(!$rst->EOF)
			      {
			         $datos= $rst->GetRowAssoc($ToUpper = false);
			        $rst->MoveNext();
			      }
			      $rst->Close();
			      return $datos;
	
	}
  	/* Funcion que permite consultar las ESM
     @ return array */
  	
    function Listar_ESM()
		{
		
      $sql = "SELECT 
                    t.tipo_id_tercero||' - '|| t.tercero_id as identificacion,
                    t.*,
                    tp.pais ||'-'||td.departamento ||'-'||tm.municipio as ubicacion
                    FROM 
                    terceros t,
                    tipo_mpios tm,
                    tipo_dptos td,
                    tipo_pais tp,
                    esm_empresas esm ";
      $sql .= " where ";
      $sql .= "        t.tipo_id_tercero = esm.tipo_id_tercero ";
      $sql .= " and    t.tercero_id = esm.tercero_id ";
      $sql .= " and    t.tipo_pais_id = tm.tipo_pais_id ";
      $sql .= " and    t.tipo_dpto_id = tm.tipo_dpto_id ";
      $sql .= " and    t.tipo_mpio_id = tm.tipo_mpio_id ";
      $sql .= " and    tm.tipo_dpto_id = td.tipo_dpto_id ";
      $sql .= " and    tm.tipo_pais_id = td.tipo_pais_id ";
      $sql .= " and    td.tipo_pais_id = tp.tipo_pais_id ";
      
 /*     
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
*/   
       
    $sql .= " ORDER BY t.nombre_tercero ASC ";
//      $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";

    
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
		/* Funcion que permite guardar la cabecera del suministro temporal
    @ return array  */
  
		function Insertar_OSuministroTemporal($Formulario)
		{
		
				list($tipo_orden_requisicion,$movimiento) = explode("@",$Formulario['tipo_orden_requisicion']);
				list($tipo_id_tercero,$tercero_id) = explode("@",$Formulario['esm']);
    
	      $campos  = " empresa_id, ";
	      $campos .= " centro_utilidad, ";
	      $campos .= " bodega, ";
	      $valores = "  '".trim($Formulario['datos']['empresa_id'])."' , ";
	      $valores .= " '".trim($Formulario['datos']['centro_utilidad'])."' , ";
	      $valores .= " '".trim($Formulario['bodega'])."' ,";

        $sql  = "INSERT INTO esm_Formula_suministro_tmp (";
        $sql .= "       formula_suministro_id_tmp, ";
        $sql .= "       tipo_id_tercero, ";
        $sql .= "       tercero_id, ";
        $sql .= "       observacion, ";
        $sql .= "       ".$campos;
        $sql .= "       usuario_id ";
        $sql .= "          ) ";
        $sql .= "VALUES ( ";
        $sql .= "        default, ";
        $sql .= "        '".$tipo_id_tercero."', ";
        $sql .= "        '".$tercero_id."', ";
        $sql .= "        '".$Formulario['observacion']."', ";
        +$sql .= "       ".$valores;
        $sql .= "        ".UserGetUID()." ";
        $sql .= "       )RETURNING(formula_suministro_id_tmp); ";			

        if(!$rst = $this->ConexionBaseDatos($sql)) 
        return false;
        else
        {
        $datos = array(); //Definiendo que va a ser un arreglo.

        while(!$rst->EOF) //Recorriendo el Vector;
        {
        $datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
        $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
        }
		}
		/* Funcion que permite consutlar los suministros temporales
    @ return array  */
  
		function Obtener_InfoDocTemporal($suministro_id)
		{
	
			  $sql = "SELECT  esm.nombre_tercero,
                        tmp.*,
                        usu.nombre
					FROM        
                    esm_Formula_suministro_tmp tmp,
                    terceros esm,
                    system_usuarios usu
							";
			  $sql .= " where ";
			  $sql .= "       tmp.formula_suministro_id_tmp = ".$suministro_id." ";
			  $sql .= "     and   tmp.tipo_id_tercero = esm.tipo_id_tercero ";
			  $sql .= "     and   tmp.tercero_id = esm.tercero_id ";
			  $sql .= "     and   tmp.usuario_id = usu.usuario_id ";
    
      
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
		/* Funcion que permite consutlar la bodega satelite 
    @ return array  */
 		function Bodega($empresa_id,$centro_utilidad,$bodega)
		{
    	
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
		/* Funcion que permite consultar los pacientes que pertenecen a una esm
    @ return array  */
  
  function Pacientes_esm($Formulario,$orden_id,$codigo_producto,$offset)
	{
	
          if($Formulario['tipo_id_paciente']=='-1')
          {
              $Formulario['tipo_id_paciente']="";

          }   
				$filtro="";	
        if($Formulario['identificacion']!='')
        {
            $filtro=" and     esm.paciente_id = '".$Formulario['identificacion']."' ";
        }

			$sql = "	SELECT  esm.tipo_id_paciente,
								esm.paciente_id,
								DA.primer_nombre ||' ' || DA.segundo_nombre||' '|| DA.primer_apellido ||' '|| DA.segundo_apellido   AS nombre_completo
								FROM    esm_pacientes as esm,
								eps_afiliados_datos DA
								WHERE   esm.tipo_id_paciente=DA.afiliado_tipo_id	
								AND     esm.paciente_id=DA.afiliado_id
                and     esm.tipo_id_paciente ILIKE '%".$Formulario['tipo_id_paciente']."%'
              ".$filtro."
              and     DA.primer_nombre ||' ' || DA.segundo_nombre||' '|| DA.primer_apellido ||' '|| DA.segundo_apellido  ILIKE '%".$Formulario['nombre']."%'
              and     esm.tipo_id_paciente||''||esm.paciente_id not in (  select   tipo_id_paciente||''||paciente_id
                    from      esm_Formula_suministro_pacientes_tmp
                    where    formula_suministro_id_tmp='".$orden_id."'
                    )

          ORDER BY  nombre_completo ";

			if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
			return false;

			$sql .= "LIMIT 15 OFFSET ".$this->offset." ";
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
    /* Funcion que permite borrar el temporal por paciente
    @ return array  */
	 	function Eliminar_tmp_por_paciente_suministro($orden,$identificacion)
		{
	 
      $this->ConexionTransaccion();
			$sql = " Delete     FROM  esm_Formula_suministro_pacientes_tmp ";
			$sql .= "where  	formula_suministro_id_tmp='".$orden."' 
			         and        tipo_id_paciente||''||paciente_id='".$identificacion."' 
    			  ";
		    	
				if(!$rst1 = $this->ConexionTransaccion($sql))
				{  
				return false;
				}
				$this->Commit();
				return true;
		}
    /* Funcion que  permite borrar el suminstro del paciente por producto
    @ return array  */
  	function Borrar_Item_suministro_producto_paciente($orden_id,$identificacion,$codigo_producto,$fecha_vencimiento,$lote)
		{
	
            $sql = " delete from esm_Formula_suministro_pacientes_tmp ";
            $sql .= " where ";
            $sql .= "        formula_suministro_id_tmp = ".$orden_id." ";
            $sql .= " and    codigo_producto = '".$codigo_producto."'
                and     tipo_id_paciente||''||paciente_id='".$identificacion."' 
                and    fecha_vencimiento='".$fecha_vencimiento."' and lote='".$lote."'		";
          
            if(!$rst = $this->ConexionBaseDatos($sql)) 
            return false;
            else
            return true;

            $rst->Close();
		}
	  /* Funcion que  permite consultar el contracto activo
    @ return array  */
     
     
  	function ObtenerContratoId($empresa_id)
		{
		
      $sql = "SELECT pc.plan_id, pl.*,
              lp.codigo_lista
              FROM esm_parametros_contrato as pc
              JOIN planes as pl ON (pc.plan_id = pl.plan_id) and (pl.estado = '1')
              JOIN listas_precios as lp ON (pl.lista_precios = lp.codigo_lista) and (lp.codigo_lista = 
              (
              select lpd.codigo_lista
              from
              listas_precios_detalle as lpd
              where
              empresa_id= '".$empresa_id."'
              group by(lpd.codigo_lista)
              ))
              where pc.empresa_id IS NULL
              and pc.sw_estado = '1'; ";
           
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
	 /* Funcion que  permite consultar la informacion del paciente
    @ return array  */
     
  
		function Consultar_Paciente($tipo,$id)
    {
		 
				$sql = "  SELECT primer_nombre ||''||segundo_nombre||''||primer_apellido ||''||segundo_apellido as nombre
                   FROM     eps_afiliados_datos
                  WHERE    afiliado_tipo_id = '".$tipo."'
                  AND     afiliado_id = '".$id."'
           
                   ";
			
                  if(!$rst = $this->ConexionBaseDatos($sql))
          				return false;
          				$datos = array();
          				while(!$rst->EOF)
          				{
          				$datos = $rst->GetRowAssoc($ToUpper);
          				$rst->MoveNext();
          				}
          				$rst->Close();
          				return $datos;
		
      }
      /* Funcion que  permite consultar si se ha ingresado algun producto al paciente
    @ return array  */
     function Consultar_Registros_tmp_suministro($orden_id,$tipo_paciente,$paciente_id)
      {
		
        $sql = " select codigo_producto, cantidad, fc_descripcion_producto_alterno(codigo_producto) as descripcion,fecha_vencimiento,lote
            from        esm_Formula_suministro_pacientes_tmp 
            where    formula_suministro_id_tmp='".$orden_id."'
            and      tipo_id_paciente='".$tipo_paciente."'
            and      paciente_id='".$paciente_id."' 
            order by  descripcion";
            
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
	 /* Funcion que  permite consultar si precios del producto
    @ return array  */
  
	 function ConsultarListaDetalle_Productos($Formulario,$lista,$DocTemporal,$paciente_id,$tipo_paciente,$offset)
   {
	
        if($Formulario['codigo']!="" || $Formulario['descripcion']!="" || $Formulario['codigo_barras']!="")
	
        $sql .= " SELECT    fc_descripcion_producto_alterno(ext.codigo_producto) as descripcion,
                            ext.codigo_producto, ext.existencia,lote.*
                                                                    ";
        $sql .= " FROM    inventarios_productos invp left join  medicamentos med ON  (invp.codigo_producto=med.codigo_medicamento) left join inv_med_cod_principios_activos pr ON (med.cod_principio_activo=pr.cod_principio_activo),
										existencias_bodegas as ext, inventarios inv, existencias_bodegas_lote_fv lote ";
        $sql .= " WHERE      ext.empresa_id = '".$DocTemporal['empresa_id']."'
									 and   ext.centro_utilidad= '".$DocTemporal['centro_utilidad']."'
									 and   ext.bodega='".trim($DocTemporal['bodega'])."'
									 and   inv.empresa_id=ext.empresa_id
									 and   inv.codigo_producto=ext.codigo_producto
                                     and   inv.codigo_producto = invp.codigo_producto
									 and   ext.empresa_id=lote.empresa_id
									 and   ext.centro_utilidad=lote.centro_utilidad
									 and   ext.bodega=lote.bodega
									 and   ext.codigo_producto=lote.codigo_producto
									 and   lote.existencia_actual > 0 ";
									  if($Formulario['codigo']!="")
									 {
										$sql .= " and   ext.codigo_producto ILIKE '%".$Formulario['codigo']."%'						 ";
									 }
                     
									  if($Formulario['descripcion']!="")
									 {
										$sql .= "  and   invp.descripcion ILIKE '%".$Formulario['descripcion']."%' ";
									 }
									 
									 
									 
									 if($Formulario['codigo_barras']!="")
									 {
										$sql .= "   and  invp.codigo_barras = '".$Formulario['codigo_barras']."'						 ";
									 }
                     $sql .= " AND  invp.codigo_producto||''||lote.fecha_vencimiento||''||lote.lote not in (select codigo_producto||''||fecha_vencimiento||''||lote  from esm_Formula_suministro_pacientes_tmp
					        								 and    paciente_id='".$paciente_id."' ) ";
                    $sql .= " ORDER BY descripcion , lote.fecha_vencimiento ASC ";
        
                  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
                  return false;
                  $sql .= "LIMIT 10 OFFSET ".$this->offset." ";
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

      /* Funcion que  permite consultar cantidad temporal solicitada por producto
    @ return array  */
  
      function Cantidad_producto_tmp_suministro($producto,$fcha_vencimiento,$lote)
      {
		
    		$sql = "SELECT   SUM(cantidad) AS cantidad
					FROM    esm_formula_suministro_pacientes_tmp
					WHERE   codigo_producto = '".$producto."' 
					AND     fecha_vencimiento = '".$fcha_vencimiento."' 
					AND     lote = '".$lote."'  ";
    	 
    		if(!$rst = $this->ConexionBaseDatos($sql)) 
    			return false;

    			$datos = array(); //Definiendo que va a ser un arreglo.
    			
    			while(!$rst->EOF) //Recorriendo el Vector;
    			{
    				$datos= $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
    				$rst->MoveNext();
    			}
    			$rst->Close();
    			return $datos;
		} 
    /* Funcion que  permite guardar el producto en el temporal de suminstro
    @ return boolean  */
		function GuardarTemporal($orden_id,$codigo_producto,$cantidad,$tipo_paciente,$id_paciente,$fecha,$lote)
		{
        $this->ConexionTransaccion();
		
				$sql = "INSERT INTO   	esm_Formula_suministro_pacientes_tmp
						(
										formula_suministro_paciente_id_tmp,
										formula_suministro_id_tmp,
										tipo_id_paciente, 
										paciente_id,
										codigo_producto,
										cantidad,
										fecha_vencimiento,
										lote
						)VALUES 
						(				DEFAULT,
										".$orden_id.", 
										'".$tipo_paciente."',
										'".$id_paciente."',
										'".$codigo_producto."',
										".$cantidad.",
										'".$fecha."',
										'".$lote."'
										
						); " ;
										
									
						if(!$rst = $this->ConexionTransaccion($sql))
						return false;

						$this->Commit();
						return true;
	     
     }
	 /* Funcion que  permite borrar un item del suministro realizado al paciente
    @ return boolean  */
	 	function Borrar_Item_suministro($orden_id,$codigo_producto,$tipo_paciente,$paciente_id,$fecha_vencimiento,$lote)
		{
	
			  $sql = " delete from esm_Formula_suministro_pacientes_tmp ";
			  $sql .= " where ";
			  $sql .= "        formula_suministro_id_tmp = ".$orden_id." ";
			  $sql .= " and    codigo_producto = '".$codigo_producto."'
						and    tipo_id_paciente='".$tipo_paciente."'
						and    paciente_id='".$paciente_id."'	and fecha_vencimiento='".$fecha_vencimiento."' and lote='".$lote."'		";
			  
        
          if(!$rst = $this->ConexionBaseDatos($sql)) 
          return false;
          else
          return true;

          $rst->Close();
		}
    /* Funcion que  permite listar los productos temporales 
    @ return array  */
    function Listado_ProductosTemporales($orden)
		{
    	
          $sql = "SELECT  	DISTINCT	    tmp.tipo_id_paciente ||''|| tmp.paciente_id as identificacion,
                                          tmp.tipo_id_paciente,
                                          tmp.paciente_id,
                                          pac.primer_nombre ||' ' || pac.segundo_nombre||' '|| pac.primer_apellido ||' '|| pac.segundo_apellido   AS nombre_completo
                  
                  FROM 
                                    esm_Formula_suministro_pacientes_tmp as tmp,
                                    pacientes as pac
                                    ";
              $sql .= " where   tmp.paciente_id=pac.paciente_id  and tmp.tipo_id_paciente=pac.tipo_id_paciente ";
              $sql .= " and      formula_suministro_id_tmp = ".$orden."  ";
              $sql .= "  ";
       
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
    /* Funcion que  permite listar los productos temporales por pacientes 
    @ return array  */
		function Listado_ProductosTemporales_por_paciente($orden,$identificacion)
		{
    		$sql = "SELECT  		fc_descripcion_producto_alterno(codigo_producto) as descripcion,
                            codigo_producto,
                            cantidad,fecha_vencimiento,lote
  
                FROM 
                      esm_Formula_suministro_pacientes_tmp 
					";
    		$sql .= " where  formula_suministro_id_tmp = ".$orden."  and tipo_id_paciente ||''|| paciente_id= '".$identificacion."' ";
    		$sql .= "  ";
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
    /* Funcion que  permite consultar la numeracion del documento 
    @ return array  */
	
      function AsignarNumeroDocumentoDespacho_d($bodegas_doc_id)
		{
		
	
       
            $sql="BEGIN WORK;  LOCK TABLE bodegas_doc_numeraciones IN ROW EXCLUSIVE MODE ;";
            $sql.="UPDATE bodegas_doc_numeraciones set numeracion=numeracion + 1
                        WHERE  bodegas_doc_id= ".$bodegas_doc_id." RETURNING numeracion";
                        
                                     if(!$rst = $this->ConexionBaseDatos($sql))
							        
                                      return false;
                                      $datos = array();
                                      while(!$rst->EOF)
                                      {
                                      $datos = $rst->GetRowAssoc($ToUpper);
                                      $rst->MoveNext();
                                      }
                                      $rst->Close();
                                      return $datos;
                                             
          
      
		}
	/* Funcion que  permite ingresar la informacion general del documento de suminstro 
    @ return boolean  */
	
    function IngresarInv_Bodegas_documentos($bodegas_doc_id,$observacion,$numero)
		{
          	
			    $this->ConexionTransaccion();
			    $sql = "INSERT INTO bodegas_documentos(bodegas_doc_id,numeracion,fecha,
                                                          total_costo,transaccion,observacion,
                                                          usuario_id,fecha_registro)
                                                           VALUES( 
                                                           ".$bodegas_doc_id.",
                                                           ".$numero.",
                                                            now(),
                                                            0,
                                                            null,
                                                            '".$observacion."',
                                                            ".UserGetUID().",
                                                            now() ); ";
				if(!$rst1 = $this->ConexionTransaccion($sql))
				{  
				return false;
				}
				$this->Commit();
				return true;
		}
    /* Funcion que  permite ingresar la cabecera del documento real 
    @ return boolean  */
		function IngresarCabecera_Suministro_documentos($bodegas_doc_id,$numero,$tipo_tercero,$tercero_id,$empresa,$centro,$bodega,$observacion)
		{	
          
			    $this->ConexionTransaccion();
			    $sql = "INSERT INTO esm_Formula_suministro(
															bodega_doc_id,
															numeracion,
															tipo_id_tercero,
															tercero_id,
															usuario_id,
															empresa_id,
															bodega,
															centro_utilidad,
															observacion
	                                )
                                  VALUES( 
														   ".$bodegas_doc_id.",
                                ".$numero.",
                                '".$tipo_tercero."',
                                '".$tercero_id."',
                                ".UserGetUID().",
                                '".$empresa."',
                                '".trim($bodega)."',
                                '".$centro."',
                                '".$observacion."'
																												 ); ";
				if(!$rst1 = $this->ConexionTransaccion($sql))
				{  
				return false;
				}
				$this->Commit();
				return true;
		}
	  /* Funcion que  permite consultar el plan del paciente 
    @ return array  */
	
    function Plan_Paciente($afiliado)
		{
    	 
    		$sql = "SELECT 	plan_atencion
					FROM 	eps_afiliados
					WHERE 	afiliado_tipo_id  ||''|| afiliado_id = '".$afiliado."' ";
			        
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
    /* Funcion que  permite consultar el precio  del producto segun la lista de precios 
    @ return array  */
	
    
	 function ConsultarListaDetalle_producto_lista($codigo_lista,$empresa_id,$codigo_producto)
      {
    		
       
             $sql = "  SELECT precio
						FROM   listas_precios_detalle
						WHERE  empresa_id = '".$empresa_id."'
						AND    codigo_producto = '".$codigo_producto."' 
						AND    codigo_lista = '".$codigo_lista."' ";
						
						
                     if(!$rst = $this->ConexionBaseDatos($sql)) return false; 

					$datos = array();
					while(!$rst->EOF)
					{
					$datos = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
      }
	    /* Funcion que  permite consultar el precio base  del producto segun la lista de precios base 
    @ return array  */
	
	 function ConsultarListaDetalle_BASE($codigo_lista,$empresa_id,$codigo_producto)
      {
    		
       
             $sql = "  SELECT precio
						FROM   listas_precios_base
						WHERE  empresa_id = '".$empresa_id."'
						AND    codigo_producto = '".$codigo_producto."' 
						AND    codigo_lista = '".$codigo_lista."' ";
						
						
                     if(!$rst = $this->ConexionBaseDatos($sql)) return false; 

					$datos = array();
					while(!$rst->EOF)
					{
					$datos = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
      }
     /* Funcion que  permite consultar el precio   del producto que no estan pactados
    @ return array  */
    function ConsultarListaDetalle_NO_PAC($empresa_id,$codigo_producto)
      {
      
             $sql = "  SELECT costo as precio
						FROM   inventarios
						WHERE  empresa_id = '".$empresa_id."'
						AND    codigo_producto = '".$codigo_producto."' 
					";
						
						
                     if(!$rst = $this->ConexionBaseDatos($sql)) return false; 

					$datos = array();
					while(!$rst->EOF)
					{
					$datos = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
      }
    /* Funcion que  permite guardar el detalle del movimiento del documento de suminstro
    @ return boolean  */
		function Guardar_Inv_bodegas_documento_d($codigo_producto,$cantidad,$costo,$bodegas_doc_id,$numeracion,$empresa,$pactado,$centro_utilidad,$bodega,$fecha_v,$lote)
		{   
         
			$this->ConexionTransaccion();
		  
			$sql ="  	update existencias_bodegas_lote_fv
		                 set  existencia_actual= existencia_actual - ".$cantidad."
						WHERE   empresa_id = '".$empresa."' AND 
								centro_utilidad = '".$centro_utilidad."'
						AND     codigo_producto = '".$codigo_producto."'
						AND     bodega = '".trim($bodega)."'
						AND     fecha_vencimiento = '".$fecha_v."'
						AND     lote = '".$lote."';   ";
		
		
			$sql .= "	 update existencias_bodegas
		                 set  existencia= existencia -".$cantidad."
						WHERE   empresa_id = '".$empresa."' AND 
								centro_utilidad = '".$centro_utilidad."'
						AND     codigo_producto = '".$codigo_producto."'
						AND     bodega = '".trim($bodega)."' ;  ";
				
			$sql .= "INSERT INTO bodegas_documentos_d
                  ( 
                    consecutivo,
                    codigo_producto,
                    cantidad,
                    total_costo,
                    bodegas_doc_id,
                    numeracion,
                    fecha_vencimiento,
                    lote,
					sw_pactado
                  )
                  VALUES
                  (
                     DEFAULT,
                    '".$codigo_producto."',
                    ".$cantidad.",
                    ".$costo.",
                    ".$bodegas_doc_id.",
                    ".$numeracion." ,
                    '".$fecha_v."' ,
                    '".$lote."',
                   	'".$pactado."'			
                  ); ";
				
				if(!$rst = $this->ConexionTransaccion($sql))
				
				return false;

				$this->Commit();
				return true;
		}             

    /* Funcion que  permite guardar el detalle del movimiento del documento de suminstro por paciente
    @ return boolean  */
 		function Guardar_Inv_bodegas_documento_dd($bodega_doc_id,$numeracion,$tipo_id_paciente,$paciente_id,$codigo_producto,$cantidad,$fecha_v,$lote)
		{						
			$this->ConexionTransaccion();
		
						
			$sql .= "INSERT INTO esm_Formula_suministro_pacientes
                  ( 
                        formula_suministro_paciente_id,
                        bodega_doc_id,
                        numeracion,
                        tipo_id_paciente,
                        paciente_id,
                        codigo_producto,
                        cantidad,
                        fecha_vencimiento,
                        lote

                  )
                  VALUES
                  (
                     DEFAULT,
                    ".$bodega_doc_id.",
                    ".$numeracion.",
                    '".$tipo_id_paciente."',
                    '".$paciente_id."',
                    '".$codigo_producto."',
                    ".$cantidad.",
                    '".$fecha_v."' ,
                    '".$lote."'                  	
                  ); ";
				
			  
				if(!$rst = $this->ConexionTransaccion($sql))
				
				return false;

				$this->Commit();
				return true;
		}             

		 /* Funcion que  permite actualizar el costo total del movimiento de suminstro
    @ return array  */
		function UpdateCostos($bodegas_doc_id,$numeracion,$totalcosto)
		{	
	    
		  $sql = "   	update bodegas_documentos
		                set    total_costo=".$totalcosto."
						WHERE  bodegas_doc_id='".$bodegas_doc_id."'
						AND    numeracion='".$numeracion."'  ";
					
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
		/* Funcion que  permite consultar las ordenes de suminstros por pacientes
    @ return array  */
		  
	  function Consultar_OrdenSuministro_por_paciente($bodega_doc_id,$numeracion)
		{
	
      $sql = "SELECT 
                    
                     esm.nombre_tercero,
                     tmp.*,
                     usu.nombre,
                     emp.razon_social,
					 bod.descripcion as bodega,
					 esm.direccion,
					 MP.municipio || ' ' || TD.departamento || ' ' || TP.pais AS ubicacion
					 
            FROM        
                     esm_Formula_suministro tmp,
                     terceros esm,
                    system_usuarios usu,
                     empresas emp,
					 bodegas bod,
					 centros_utilidad centro,
					tipo_mpios MP,
					tipo_dptos TD,
					tipo_pais TP
                    ";
          $sql .= " where ";
          $sql .= "           tmp.bodega_doc_id = ".$bodega_doc_id." ";
          $sql .= "      and     tmp.numeracion=".$numeracion." ";
          $sql .= "     and   tmp.tipo_id_tercero = esm.tipo_id_tercero ";
          $sql .= "     and   tmp.tercero_id = esm.tercero_id ";
          $sql .= "     and   tmp.usuario_id = usu.usuario_id ";
          $sql .= "     and   tmp.empresa_id = bod.empresa_id 
                        and   tmp.centro_utilidad=bod.centro_utilidad
                        and   tmp.bodega=bod.bodega
                        and   bod.centro_utilidad=centro.centro_utilidad
                        and  bod.empresa_id=centro.empresa_id
                        and  centro.empresa_id=emp.empresa_id
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
	  /* Funcion que  permite consultar los pacientes los cuales tienen un documento de suminstro asociado
    @ return array  */
		function Listado_Pacientes_Reales($documento,$numeracion)
		{
    	
    		$sql = "SELECT  	DISTINCT	   tmp.tipo_id_paciente ||''|| tmp.paciente_id as identificacion,
			                          tmp.tipo_id_paciente,
									   tmp.paciente_id,
							pac.primer_nombre ||' ' || pac.segundo_nombre||' '|| pac.primer_apellido ||' '|| pac.segundo_apellido   AS nombre_completo
								
    		FROM 
					esm_Formula_suministro_pacientes as tmp,
					pacientes as pac
					";
    		$sql .= " where   tmp.paciente_id=pac.paciente_id  and tmp.tipo_id_paciente=pac.tipo_id_paciente ";
    		$sql .= " and      tmp.bodega_doc_id = ".$documento."   and tmp.numeracion= ".$numeracion."";
    		$sql .= "  ";
    	

        
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
     /* Funcion que  permite conslistar los productos con los pacientes
    @ return array  */
     
		
 	  function Listado_Productos_por_paciente($bodega,$numeracion,$identificacion)
		{
    		$sql = "SELECT  		fc_descripcion_producto_alterno(codigo_producto) as descripcion,
			                      codigo_producto,
                            cantidad,fecha_vencimiento,lote
			
    		FROM 
                  esm_Formula_suministro_pacientes 
					";
    		$sql .= " where  bodega_doc_id = ".$bodega."  and  numeracion='".$numeracion."' and tipo_id_paciente ||''|| paciente_id= '".$identificacion."' ";
    		$sql .= "  ";
    	

        
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
 /* Funcion que  permite consultar la informacion general del reporte de famacovigilancia
    @ return array  */
     
		
	function Consultar_cabe_Faramaco($farmaco)
	{
		
			
			$sql = "SELECT 	VIG.esm_farmaco_id,
					VIG.esm_tipo_id_tercero,
					VIG.esm_tercero_id,
					To_char(VIG.fecha_notificacion,'DD-MM-YYYY') AS fecha_notificacion,
					VIG.formula_papel,
					VIG.tipo_id_paciente,
					VIG.paciente_id,
					To_char(VIG.fecha_sospecha,'DD-MM-YYYY') AS fecha_sospecha,
					VIG.observacion,
					VIG.diagnostico,
					VIG.usuario_id,
					PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos,
					PA.primer_nombre||' '||PA.segundo_nombre AS nombres,
					to_char(PA.fecha_nacimiento,'dd-mm-yyyy') as fecha_nacimiento,
					PA.residencia_direccion,
					PA.residencia_telefono,
					PA.sexo_id,
					edad(PA.fecha_nacimiento) as edad,
					TER.nombre_tercero,
					MP.municipio,
					TD.departamento,
					TP.pais,
					USU.nombre,
					USU.descripcion,
					VIG.reaccion_adversa
					
			FROM 	esm_farmaco_vigilancia  VIG,
					pacientes PA,
					esm_empresas ESME,
					terceros TER,
					tipo_mpios MP,
					tipo_dptos TD,
					tipo_pais TP,
					system_usuarios USU
					
					
			WHERE   VIG.tipo_id_paciente=PA.tipo_id_paciente
			AND     VIG.paciente_id=PA.paciente_id
			AND     VIG.esm_tipo_id_tercero=ESME.tipo_id_tercero
			and     VIG.esm_tercero_id=ESME.tercero_id
			AND     ESME.tipo_id_tercero=TER.tipo_id_tercero
			AND     ESME.tercero_id=TER.tercero_id
			AND     TER.tipo_pais_id=MP.tipo_pais_id
			AND     TER.tipo_dpto_id=MP.tipo_dpto_id
			AND     TER.tipo_mpio_id=MP.tipo_mpio_id
			AND     MP.tipo_pais_id=TD.tipo_pais_id
			AND     MP.tipo_dpto_id=TD.tipo_dpto_id
			AND     TD.tipo_pais_id=TP.tipo_pais_id 
			AND     VIG.usuario_id=USU.usuario_id 
			AND     VIG.esm_farmaco_id='".$farmaco."' ";
			
						 					   
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
    /* Funcion que  permite consultar la informacion general detalle del reporte de famacovigilancia
    @ return array  */
   
	function Farmaco_v_d_consulta($farmaco)
	{
		$sql = " SELECT   codigo_medicamento,
							indicacion_motivo,
							fecha_inicio,
							fc_descripcion_producto_alterno(codigo_medicamento)as producto,
							fc_codigo_mindefensa(codigo_medicamento) as codigo_producto_mini,
							
							fecha_finalizacion,
							lote,
							frecuencia,
							fecha_vencimiento
				FROM 		esm_farmaco_vigilancia_d
				WHERE 		esm_farmaco_id = '".$farmaco."' ";
	
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
   /* Funcion que  permite consultar la informacion del usuario que registro el reporte de farmacovigilancia
   @ return array  */
   	  function Consultar_cabe_Faramaco_Usuario($farmaco)
	{
		
			$sql = "SELECT 	VIG.usuario_id
            FROM 	  esm_farmaco_vigilancia  VIG,
                    system_usuarios USU
          WHERE    VIG.usuario_id=USU.usuario_id 
          AND     VIG.esm_farmaco_id=".$farmaco." ";
			
						 					   
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
  
  /* Funcion que  permite verificar si el usuario que registro el reporte de farmacovigilancia es un profesional
   @ return array  */
   
	function Verificar_Usuario_Profesional($usuario)
	{
	  
		$sql = " SELECT PROF.tipo_tercero_id,
						PROF.tercero_id,
						TIP.descripcion,
						TER.direccion,
						TER.telefono,
						PRO.nombre
					
				FROM 	profesionales_usuarios PROF,
				        profesionales PRO,
						tipos_profesionales TIP,
						TERCEROS TER
				WHERE 	PROF.usuario_id = ".$usuario."
				AND     PROF.tipo_tercero_id=PRO.tipo_id_tercero
				and     PROF.tercero_id=PRO.tercero_id
				and     PRO.tipo_id_tercero=TER.tipo_id_tercero
				AND     PRO.tercero_id=TER.tercero_id
				AND    PRO.tipo_profesional=TIP.tipo_profesional";
	
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
   /* Funcion que  permite consultar los datos del usuario en caso de que no sea un profesional
   @ return array  */
   	function Consultar_Usuario_NO_Profesional($usuario)
		{
			$sql = " SELECT  nombre,
								descripcion,
								telefono
						FROM 	system_usuarios
						WHERE 	usuario_id = '".$usuario."' ";
		
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
  	 /* Funcion que  permite consultar si el paciente pertenece a una esm
   @ return array  */
    function Validar_Paciente_ESM($datos)
    {
		$sql = " SELECT  tipo_id_paciente,
						 paciente_id,
						 tipo_id_tercero,
						 tercero_id
				 FROM    esm_pacientes
				 WHERE   tipo_id_paciente = '".$datos['tipo_id_paciente']."'
				 AND     paciente_id = '".$datos['paciente_id']."' ";
				 if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			      $datos = array();
			      while(!$rst->EOF)
			      {
			        $datos = $rst->GetRowAssoc($ToUpper = false);
			        $rst->MoveNext();
			      }
			      $rst->Close();
			      return $datos;
				
	}
	   /* Funcion que permite realizar la busqueda de los diagnosticos 
     @ return array con los datos de la informacion */
    
		function Busqueda_Avanzada_Diagnosticos($tipo_id_paciente,$paciente_id,$codigo,$diagnostico,$offset)
		{  	
  		//filtro por clasificacion de diagnosticos
  		$filtro='';
  		
  			/*$filtro = "AND (sexo_id='".$Datos_Paciente['tipo_sexo_id']."' OR sexo_id is null)
  					 AND (edad_max>=".$edad_paciente." OR edad_max is null)
  					 AND (edad_min<=".$edad_paciente." OR edad_min is null)";*/
  		

  		$sql = "SELECT diagnostico_id, diagnostico_nombre
                      FROM diagnosticos
					  WHERE diagnostico_id ='".$codigo."'
					  AND diagnostico_nombre LIKE '%".$diagnostico."%'
                       $filtro and  diagnostico_id NOT IN (select diagnostico_id
													
													FROM   esm_formula_externa_diagnosticos_tmp 
													WHERE  usuario_id = ".UserGetUID()."
													AND   tipo_id_paciente = '".$tipo_id_paciente."'
													AND    paciente_id = '".$paciente_id."'
													 ) ";
                   
                   
		 			  
  		  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",$offset))
			return false;

			$whr .= "ORDER BY diagnostico_nombre ";
			$whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";

			if(!$rst = $this->ConexionBaseDatos($sql.$whr,null)) return false;
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;      
    }
    
  /* Funcion que permite Ingresar los tipos de diagnostico relacionados con los antecedentes
     @ return boolean */
    function Insertar_DX_tipo_Diagnostico($dx_,$tipo_id_paciente,$paciente_id,$tmp_id)
	{
      
          
			$this->ConexionTransaccion();

               $sql="INSERT INTO esm_formula_externa_diagnosticos_tmp
                              ( 
							    tmp_formula_id,
							     usuario_id,
                                tipo_id_paciente,
                                paciente_id,
                                diagnostico_id
                               )VALUES(
							    ".$tmp_id.",
                                ".UserGetUID().",
                                '".$tipo_id_paciente."',
                                '".$paciente_id."',
								'".$dx_."'
                              )";
            if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
				return true;
	}
	
  }
?>