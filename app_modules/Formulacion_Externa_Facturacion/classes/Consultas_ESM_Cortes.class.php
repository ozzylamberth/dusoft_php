<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Consultas_TipoEvento.class.php,
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
  
  
  
  class Consultas_ESM_Cortes extends ConexionBD
  {
    var $NumeroFormulas;
	
	/**
    * Contructor
    */
    
	function Consultas_ESM_Cortes(){}
 
   
    /*
    *	Funcion de Consulta SQL,que se encarga de Buscar los 
	*	parametros Generales de Cortes de una Farmacia
    */
		function ConsultarCorte_GeneralCentro($datos)
		{
        
		/*(substring(lapso from 0 for 5)||'-'||substring(lapso from 5 for 8)||'-'||'01')::date as fecha */
		$sql = " SELECT  
		a.corte_general_id, 	
		a.empresa_id, 	
		a.centro_utilidad, 	
		a.numeracion,
		a.lapso as lapso_inicial,
		(substring(b.lapso from 0 for 5)||'-'||substring(b.lapso from 5 for 8)||'-'||'01')::date as fecha_inicio,
		TO_CHAR(((((substring(b.lapso from 0 for 5)||'-'||substring(b.lapso from 5 for 8)||'-'||'01')::date)+ '1 months'::interval)- '1 day'::interval),'YYYY-MM-DD') as fecha_final,
		CASE 
		WHEN b.ultima_fecha_corte IS NOT NULL  
		THEN TO_CHAR((b.ultima_fecha_corte +'1 day'::interval)::date,'DD/MM/YYYY')
		ELSE TO_CHAR((substring(b.lapso from 0 for 5)||'-'||substring(b.lapso from 5 for 8)||'-'||'01')::date,'DD/MM/YYYY')
		END AS fecha_inicial_corte,
		TO_CHAR(((((substring(b.lapso from 0 for 5)||'-'||substring(b.lapso from 5 for 8)||'-'||'01')::date)+ '1 months'::interval)- '1 day'::interval),'DD-MM-YYYY') as fecha_finmax_tope,
		b.lapso,
		b.ultima_fecha_corte,
		CASE 
		WHEN c.corte_general_id IS NULL THEN '1'
		ELSE '0'
		END as operacion,
		d.ultimo_lapso_cerrado,
		TO_CHAR((((substring(d.ultimo_lapso_cerrado from 0 for 5)||'-'||substring(d.ultimo_lapso_cerrado from 5 for 8)||'-'||'01')::date)+ '1 months'::interval),'YYYYMM') as lapso_siguiente
		FROM
		ff_cortes_generales as a
		LEFT JOIN ff_cortes_mensual as b ON (a.corte_general_id = b.corte_general_id)
		AND (b.estado = '1')
		LEFT JOIN ff_cortes_mensual as c ON (a.corte_general_id = c.corte_general_id)
		AND (a.lapso = c.lapso)
		LEFT JOIN (
						SELECT
								corte_general_id,
								MAX(lapso) as ultimo_lapso_cerrado
								FROM
								ff_cortes_mensual
								WHERE TRUE
								AND estado = '0'
								AND empresa_id = '".trim($datos['empresa_id'])."'
								AND centro_utilidad = '".trim($datos['centro_utilidad'])."'
								group by corte_general_id
						) AS d ON (a.corte_general_id = d.corte_general_id)
		WHERE TRUE
		AND a.empresa_id = '".trim($datos['empresa_id'])."'
		AND a.centro_utilidad = '".trim($datos['centro_utilidad'])."' ";

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
  * FUNCION QUE PERMITE
  * GENERA EL LAPSO DE MANERA AUTOMATICA, 
  * PARA LOS CORTES MENSUALES
  */
  function Definir_Lapso($CortesCentro)
	{
	
	if($CortesCentro['operacion']==='1')
			{
				$sql  = "INSERT INTO ff_cortes_mensual
				(
				empresa_id, 	
				centro_utilidad, 	
				corte_general_id, 	
				lapso,  	
				usuario_id
				)
				VALUES
				(
				'".trim($CortesCentro['empresa_id'])."',
				'".trim($CortesCentro['centro_utilidad'])."',
				'".trim($CortesCentro['corte_general_id'])."',
				'".trim($CortesCentro['lapso_inicial'])."',
				'".UserGetUID()."');	";
			}
			else 
				if(empty($CortesCentro['lapso']))
				{
					$sql  = "INSERT INTO ff_cortes_mensual
					(
					empresa_id, 	
					centro_utilidad, 	
					corte_general_id, 	
					lapso,  	
					usuario_id
					)
					VALUES
					(
					'".trim($CortesCentro['empresa_id'])."',
					'".trim($CortesCentro['centro_utilidad'])."',
					'".trim($CortesCentro['corte_general_id'])."',
					'".trim($CortesCentro['lapso_siguiente'])."',
					'".UserGetUID()."');	";
				}
	if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
			$rst->Close();
			return true;
	}
		 
	
	/*
	* QUERY DE CONSULTA DE LOS CORTES MENSUALES 
	*
	*/	
	function ConsultaCortes_Mensuales($filtros,$offset=0,$session)
	{
		/*$this->debug=true;*/
		$sql = " SELECT
		a.empresa_id||'-'||a.centro_utilidad as codigo_farmacia,
		e.formula_id,
		e.formula_papel,
		e.fecha_formula,
		e.tipo_formula, 	
		e.tipo_id_tercero, 	
		e.tercero_id, 	
		f.nombre,
		e.tipo_id_paciente, 	
		e.paciente_id, 
		primer_apellido||' '||segundo_apellido||' '||primer_nombre||' '||segundo_nombre as paciente,
		e.plan_id,
		i.plan_descripcion,
		h.diagnostico_id,
		0 as total_venta,
		b.bodegas_doc_id,
		b.numeracion
		FROM
		esm_formula_externa as e 
		JOIN profesionales as f ON (e.tipo_id_tercero = f.tipo_id_tercero)
		AND (e.tercero_id = f.tercero_id)
		JOIN pacientes as g ON (e.tipo_id_paciente = g.tipo_id_paciente)
		AND (e.paciente_id = g.paciente_id)
		LEFT JOIN esm_formula_externa_diagnosticos AS h ON (e.formula_id = h.formula_id)
		JOIN planes as i ON(e.plan_id = i.plan_id)
		LEFT JOIN esm_formulacion_despachos_medicamentos as d ON (e.formula_id = d.formula_id)
		LEFT JOIN bodegas_documentos as b ON (d.bodegas_doc_id = b.bodegas_doc_id)
		AND (d.numeracion = b.numeracion)
		
		LEFT JOIN bodegas_doc_numeraciones AS a ON (b.bodegas_doc_id = a.bodegas_doc_id)
		WHERE TRUE
		AND b.fecha_registro >= '".$this->DividirFecha($filtros['fecha_inicio'])."'::date
		AND b.fecha_registro <= ('".$this->DividirFecha($filtros['fecha_final'])."'::date + '1 day'::interval)::date
		AND a.empresa_id = '".trim($session['empresa_id'])."'
		AND a.centro_utilidad = '".trim($session['centro_utilidad'])."'
		
		ORDER BY i.plan_descripcion ASC,b.fecha_registro ASC "; 
		/*
		LEFT JOIN	(
				SELECT
				x.bodegas_doc_id,
				x.numeracion,
				SUM(x.total_venta) as total_venta
				FROM
				bodegas_documentos_d as x
				JOIN esm_formulacion_despachos_medicamentos as y ON(x.bodegas_doc_id = y.bodegas_doc_id)
				AND (x.numeracion = y.numeracion)
				GROUP BY x.bodegas_doc_id,x.numeracion
				) as c ON (b.bodegas_doc_id = c.bodegas_doc_id)
				AND (b.numeracion = c.numeracion)
		*/
		if($offset>=0)
		$sql .= "LIMIT ".$this->NumeroFormulas." OFFSET ".(($offset=="")? 0:$offset)." "; 
		/*AND e.plan_id = '".$filtros['plan_id']."' */
	if(!$rst = $this->ConexionBaseDatos($sql)) 
	return false;

	$datos = array(); //Definiendo que va a ser un arreglo.
	while(!$rst->EOF) //Recorriendo el Vector;
		{
		$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
		$rst->MoveNext();
		}
	$rst->Close();

		$cantidad = count($datos);
		$i=1;
		foreach($datos as $key => $valor)
		{
			$bodegas_doc_id .= "'".$valor['bodegas_doc_id']."'   ";
			$numeracion .= "'".$valor['numeracion']."' " ;
			$formula_id .= "'".$valor['formula_id']."' " ;
				if($i<$cantidad)
				{
				$bodegas_doc_id .= ",";
				$numeracion .= ",";
				$formula_id .= ",";
				}
			$i++;
		}
	
	$sql = "	SELECT 
				a.bodegas_doc_id,
				a.numeracion,
				a.codigo_producto,
				fc_descripcion_producto(a.codigo_producto) as producto,
				b.codigo_mindefensa,
				b.codigo_alterno,
				round(a.cantidad) as cantidad,
				(a.total_venta/a.cantidad) as valor_unitario,
				a.total_venta
				FROM
				bodegas_documentos_d as a
				JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
				WHERE TRUE
				AND bodegas_doc_id IN (".$bodegas_doc_id.")
				AND numeracion IN (".$numeracion.") ";
	/*print_r($sql);*/
	if(!$rst = $this->ConexionBaseDatos($sql)) 
	return false;

	$detalle = array(); //Definiendo que va a ser un arreglo.
	while(!$rst->EOF) //Recorriendo el Vector;
		{
		$detalle[$rst->fields[0]][$rst->fields[1]][] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
		$rst->MoveNext();
		}
	$rst->Close();
	
	$datos['detalle']=$detalle;
	
	if($offset>=0)
	{
		$sql = " SELECT
			COUNT(*) AS registros
			FROM
			bodegas_doc_numeraciones AS a
			JOIN bodegas_documentos as b ON (a.bodegas_doc_id = b.bodegas_doc_id)
			JOIN	(
					SELECT
					x.bodegas_doc_id,
					x.numeracion,
					SUM(x.total_venta) as total_venta
					FROM
					bodegas_documentos_d as x
					JOIN esm_formulacion_despachos_medicamentos as y ON(x.bodegas_doc_id = y.bodegas_doc_id)
					AND (x.numeracion = y.numeracion)
					GROUP BY x.bodegas_doc_id,x.numeracion
					) as c ON (b.bodegas_doc_id = c.bodegas_doc_id)
					AND (b.numeracion = c.numeracion)
			JOIN esm_formulacion_despachos_medicamentos as d ON (b.bodegas_doc_id = d.bodegas_doc_id)
			AND (b.numeracion = d.numeracion)
			JOIN esm_formula_externa as e ON (d.formula_id = e.formula_id)
			JOIN profesionales as f ON (e.tipo_id_tercero = f.tipo_id_tercero)
			AND (e.tercero_id = f.tercero_id)
			JOIN pacientes as g ON (e.tipo_id_paciente = g.tipo_id_paciente)
			AND (e.paciente_id = g.paciente_id)
			JOIN esm_formula_externa_diagnosticos AS h ON (e.formula_id = h.formula_id)
			WHERE TRUE
			AND b.fecha_registro >= '".$this->DividirFecha($filtros['fecha_inicio'])."'::date
			AND b.fecha_registro <= ('".$this->DividirFecha($filtros['fecha_final'])."'::date + '1 day'::interval)::date
			AND a.empresa_id = '".trim($session['empresa_id'])."'
			AND a.centro_utilidad = '".trim($session['centro_utilidad'])."'
			 "; /*AND e.plan_id ='".$filtros['plan_id']."'*/
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;

		$total = array(); //Definiendo que va a ser un arreglo.
		while(!$rst->EOF) //Recorriendo el Vector;
			{
			$total = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
			$rst->MoveNext();
			}
		$rst->Close();
		
		
		
		$datos['registros'][0]=$total['registros'];
	}
	
		$sql = "SELECT 
		c.formula_id,
		a.bodegas_doc_id,
		a.numeracion,
		d.codigo_producto,
		fc_descripcion_producto(d.codigo_producto) as producto,
		e.codigo_mindefensa,
		e.codigo_alterno,
		round(d.cantidad) as cantidad,
		(d.total_venta/d.cantidad) as valor_unitario,
		d.total_venta
		FROM
		esm_formulacion_despachos_medicamentos_pendientes as a
		JOIN esm_formula_externa as c ON (a.formula_id = c.formula_id)
		JOIN bodegas_documentos as b ON (a.bodegas_doc_id = b.bodegas_doc_id)
		AND (a.numeracion = b.numeracion)
		JOIN bodegas_documentos_d as d ON (b.bodegas_doc_id = d.bodegas_doc_id)
		AND (b.numeracion = d.numeracion)
		JOIN inventarios_productos as e ON (d.codigo_producto = e.codigo_producto)
		JOIN bodegas_doc_numeraciones as f ON (a.bodegas_doc_id = f.bodegas_doc_id)
		WHERE TRUE
		AND c.formula_id IN (".$formula_id.")
		
		AND b.fecha_registro >= '".$this->DividirFecha($filtros['fecha_inicio'])."'::date
		AND b.fecha_registro <= ('".$this->DividirFecha($filtros['fecha_final'])."'::date + '1 day'::interval)::date
		AND f.empresa_id = '".trim($session['empresa_id'])."'
		AND f.centro_utilidad = '".trim($session['centro_utilidad'])."' "; /*AND c.plan_id = '".$filtros['plan_id']."' */
	if(!$rst = $this->ConexionBaseDatos($sql)) 
	return false;

	$detalle_pendientes = array(); //Definiendo que va a ser un arreglo.
	while(!$rst->EOF) //Recorriendo el Vector;
		{
		$detalle_pendientes[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false); // antes.
		$rst->MoveNext();
		}
	$rst->Close();

	$datos['detalle_pendientes']=$detalle_pendientes;
	return $datos;
	}
  
  
  function EjecutarConsultas($sql)
	{
	    	
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		
		$rst->Close();
		return true;
	}
		
		
		
	/*
	* QUERY DE CONSULTA DE LOS CORTES MENSUALES 
	*
	*/	
	function ConsultarCortes($filtros,$session)
	{
		if(!empty($filtros['plan_id']))
		{
		$filtro_1 = " AND x.plan_id = '".trim($filtros['plan_id'])."' ";
		}
		$sql = "
		SELECT 
		a.empresa_id, 	
		a.centro_utilidad, 	
		a.numero, 	
		a.lapso, 	
		a.corte_general_id,
		corte_auditado,
		usuario_audita, 	
		fecha_auditoria,
		c.cantidad,
		a.fecha_inicial,
		a.fecha_final
		FROM
		ff_cortes AS a
		JOIN ff_cortes_mensual as b ON (a.empresa_id = b.empresa_id)
		AND (a.centro_utilidad = b.centro_utilidad)
		AND (a.corte_general_id = b.corte_general_id)
		AND (a.lapso = b.lapso)
		JOIN (
				SELECT
				x.empresa_id,
				x.centro_utilidad,
				x.numero,
				x.lapso,
				count(formula_id) as cantidad
				FROM 
				ff_cortes_detalle AS x
				WHERE TRUE
				AND x.empresa_id = '".trim($session['empresa_id'])."'
				AND x.centro_utilidad = '".trim($session['centro_utilidad'])."'
				AND x.lapso = '".trim($filtros['lapso'])."'
				".$filtro_1."
				GROUP BY 1,2,3,4
				) as c ON (a.empresa_id = c.empresa_id)
				AND (a.centro_utilidad = c.centro_utilidad)
				AND (a.lapso = c.lapso)
				AND (a.numero = c.numero)
		WHERE TRUE
		AND a.empresa_id = '".trim($session['empresa_id'])."'
		AND a.centro_utilidad = '".trim($session['centro_utilidad'])."'
		AND a.lapso = '".trim($filtros['lapso'])."' ";
		
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

	
		
		
	function GenerarCortes ($direccion,$request,$datos_farmacia)
	{
	/*$this->debug=true;*/
	if(!empty($request['buscador']['plan_id']))
		{
		$filtro = " AND a.plan_id = '".trim($request['buscador']['plan_id'])."' ";
		}
	
	$sql = "
	SELECT 
	a.empresa_id, 	
	a.centro_utilidad, 	
	b.descripcion as farmacia,
	a.numero, 	
	a.lapso, 	
	a.corte_general_id, 	
	a.usuario_id, 	
	a.fecha_registro, 	
	a.corte_auditado, 	
	a.usuario_audita, 	
	a.fecha_auditoria, 	
	a.fecha_inicial, 	
	a.fecha_final
	FROM
	ff_cortes as a
	JOIN centros_utilidad as b ON (a.empresa_id = b.empresa_id)
	AND (a.centro_utilidad = b.centro_utilidad)
	WHERE TRUE
	AND a.empresa_id = '".trim($datos_farmacia['empresa_id'])."'
	AND a.centro_utilidad = '".trim($datos_farmacia['centro_utilidad'])."'
	AND a.lapso = '".trim($request['buscador']['lapso'])."'
	AND a.numero = '".trim($request['numero'])."'; ";
	
	if(!$rst = $this->ConexionBaseDatos($sql)) 
	return false;

	$cabecera = array(); //Definiendo que va a ser un arreglo.
	while(!$rst->EOF) //Recorriendo el Vector;
		{
		$cabecera = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
		$rst->MoveNext();
		}
	$rst->Close();
	
	$sql = "
	SELECT DISTINCT
	formula_id
	FROM
	ff_cortes_detalle as a
	WHERE TRUE
	AND a.empresa_id = '".trim($datos_farmacia['empresa_id'])."'
	AND a.centro_utilidad = '".trim($datos_farmacia['centro_utilidad'])."'
	AND a.lapso = '".trim($request['buscador']['lapso'])."'
	AND a.numero = '".trim($request['numero'])."'
	".$filtro."
	group by formula_id; ";
	
	if(!$rst = $this->ConexionBaseDatos($sql)) 
	return false;

	$datos = array(); //Definiendo que va a ser un arreglo.
	while(!$rst->EOF) //Recorriendo el Vector;
		{
		$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
		$rst->MoveNext();
		}
	$rst->Close();

	/*
	* Genero El Numero de paquetes para el Corte
	*/	
	$paquetes=ceil((count($datos)/$this->NumeroFormulas));
	 $sql = "";
	 $contador_paquetes =1;
	for($i=0;$i<$paquetes;$i++)
	{
		
		$sql = "
		SELECT DISTINCT
		'".$datos_farmacia['empresa_id']."-".$datos_farmacia['centro_utilidad']."' as codigo_farmacia,
		b.formula_id,
		b.formula_papel,
		b.fecha_formula,
		b.tipo_formula, 	
		b.tipo_id_tercero, 	
		b.tercero_id, 	
		e.nombre,
		b.tipo_id_paciente, 	
		b.paciente_id, 
		c.primer_apellido||' '||c.segundo_apellido||' '||c.primer_nombre||' '||c.segundo_nombre as paciente,
		d.diagnostico_id
		FROM
		        esm_formula_externa as b
		JOIN pacientes as c ON (b.tipo_id_paciente = c.tipo_id_paciente)
		AND (b.paciente_id = c.paciente_id)
		LEFT JOIN esm_formula_externa_diagnosticos AS d ON (b.formula_id = d.formula_id)
		JOIN profesionales as e ON (b.tipo_id_tercero = e.tipo_id_tercero)
		AND (b.tercero_id = e.tercero_id)
		JOIN (
				SELECT DISTINCT
				formula_id
				FROM
				ff_cortes_detalle
				WHERE TRUE
				AND empresa_id = '".trim($datos_farmacia['empresa_id'])."'
				AND centro_utilidad = '".trim($datos_farmacia['centro_utilidad'])."'
				AND lapso = '".trim($request['buscador']['lapso'])."'
				AND numero = '".trim($request['numero'])."'
				".$filtro."  ";
		$sql .= "LIMIT ".$this->NumeroFormulas." OFFSET ".($i*$this->NumeroFormulas)." ";
		
		$sql .= ") as a ON (b.formula_id = a.formula_id )
		WHERE TRUE ";
		$sql .= " ORDER BY b.fecha_formula  ";
		
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		$pack_formulas = array(); //Definiendo que va a ser un arreglo.
		while(!$rst->EOF) //Recorriendo el Vector;
		{
		$pack_formulas[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
		$rst->MoveNext();
		}
		$rst->Close();
		
		$cantidad = count($pack_formulas);
		$j=1;
		$formula_id = "";
		foreach($pack_formulas as $key => $valor)
		{
			$formula_id .= "'".$valor['formula_id']."' " ;
				if($j<$cantidad)
				{
				$formula_id .= ",";
				}
		$j++;
		}
		
		$sql = "	SELECT 
		a.formula_id,
		a.codigo_producto,
		fc_descripcion_producto(a.codigo_producto) as producto,
		b.codigo_mindefensa,
		b.codigo_alterno,
		round(a.cantidad) as cantidad,
		(a.total_venta/a.cantidad) as valor_unitario,
		a.total_venta,
		a.pendiente_dispensado
		FROM
		ff_cortes_detalle as a
		JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
		WHERE TRUE
		AND a.empresa_id = '".trim($datos_farmacia['empresa_id'])."'
		AND a.centro_utilidad = '".trim($datos_farmacia['centro_utilidad'])."'
		AND a.lapso = '".trim($request['buscador']['lapso'])."'
		AND a.numero = '".trim($request['numero'])."'
		AND a.formula_id IN (".$formula_id.");	";
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		$detalle_formulas = array(); //Definiendo que va a ser un arreglo.
		while(!$rst->EOF) //Recorriendo el Vector;
			{
		$detalle_formulas[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false); // antes.
		$rst->MoveNext();
			}
		$rst->Close();
		
		$pack_formulas['detalle']=$detalle_formulas;
		
		$ContenidoArchivo = $this->GenerarContenidoArchivo($pack_formulas,$cabecera,$contador_paquetes,$request['buscador']['separador']);
		/*echo(" <pre> FORMULAS ".print_r($pack_formulas, true)." </pre> ");*/
		
		$this->CrearArchivo($ContenidoArchivo,$direccion,$contador_paquetes);
		$contador_paquetes++;
	}

	return true;
	}
	
	
	
	function CrearArchivo($ContenidoArchivo,$direccion,$contador_paquetes)
	{
	$nombre = $direccion."".$contador_paquetes.".csv";
	$archivo = fopen($nombre,'w');
	fwrite($archivo, $ContenidoArchivo);
    fclose($archivo);
	return true;
	}
	
	
	/*
	* Funcion con la Cual Genero el 
	* Contenido del Archivo Plano
	*/
    function GenerarContenidoArchivo($PaqueteFormulas,$cabecera,$contador_paquetes,$separador)
		{
		$ctl=Autocarga::factory("ClaseUtil");
		$contenido = $separador;
		$contenido .= "UNION TEMPORAL MEDIPOL";
		$contenido .= $separador;
		$contenido .= $separador;
		$contenido .= chr(13);
		$contenido .= chr(10);
		$contenido .= $separador;
		$contenido .= "Señores : Direccion de Sanidad - Policia Nacional";
		$contenido .= chr(13);
		$contenido .= chr(10);
		$contenido .= $separador;
		$contenido .= "PAQUETE No 00000".$contador_paquetes;
		$contenido .= chr(13);
		$contenido .= chr(10);
		$contenido .= $separador;
		$contenido .= "Fecha Exp. ".date('Y-m-d');
		$contenido .= chr(13);
		$contenido .= chr(10);
		$contenido .= $separador;
		$contenido .= "Periodo del:. ".$cabecera['fecha_inicial'];
		$contenido .= chr(13);
		$contenido .= chr(10);
		$contenido .= $separador;
		$contenido .= "Periodo del:. ".$cabecera['fecha_final'];
		$contenido .= chr(13);
		$contenido .= chr(10);
		$contenido .= chr(13);
		$contenido .= chr(10);
		$contenido .= $separador;
		$contenido .= $cabecera['farmacia'];
		$contenido .= chr(13);
		$contenido .= chr(10);
		$contenido .= chr(13);
		$contenido .= chr(10);
		$total =0;
		for($i=0;$i<(count($PaqueteFormulas)-1);$i++)
			{
			$contenido .= "id:".$PaqueteFormulas[$i]['paciente_id'].$separador;
			$contenido .= $PaqueteFormulas[$i]['paciente'].$separador;
			$contenido .= "Formula:".$PaqueteFormulas[$i]['formula_papel'].$separador;
			$contenido .= "Fecha:".$PaqueteFormulas[$i]['fecha_formula'].$separador;
			$contenido .= chr(13);
			$contenido .= chr(10);
			$contenido .= "Medico :".$PaqueteFormulas[$i]['tercero_id'].$separador;
			$contenido .= "Cod. Diag :".$PaqueteFormulas[$i]['diagnostico_id'].$separador;
			$contenido .= "Sucursal :".$PaqueteFormulas[$i]['codigo_farmacia'].$separador;
			$contenido .= chr(13);
			$contenido .= chr(10);
			$total_formula = 0;
			for($j=0;$j<(count($PaqueteFormulas['detalle'][$PaqueteFormulas[$i]['formula_id']]));$j++)
				{
				$contenido .= "'".$PaqueteFormulas['detalle'][$PaqueteFormulas[$i]['formula_id']][$j]['codigo_alterno'].$separador;
				$contenido .= $PaqueteFormulas['detalle'][$PaqueteFormulas[$i]['formula_id']][$j]['producto'].$separador;
				$contenido .= FormatoValor($PaqueteFormulas['detalle'][$PaqueteFormulas[$i]['formula_id']][$j]['cantidad']).$separador;
				$contenido .= FormatoValor($PaqueteFormulas['detalle'][$PaqueteFormulas[$i]['formula_id']][$j]['valor_unitario'],2).$separador;
				$contenido .= FormatoValor($PaqueteFormulas['detalle'][$PaqueteFormulas[$i]['formula_id']][$j]['total_venta'],2).$separador;
				$contenido .= chr(13);
				$contenido .= chr(10);
				$total_formula +=$PaqueteFormulas['detalle'][$PaqueteFormulas[$i]['formula_id']][$j]['total_venta'];
				$total +=$PaqueteFormulas['detalle'][$PaqueteFormulas[$i]['formula_id']][$j]['total_venta'];
				}
			$contenido .= $separador;
			$contenido .= $separador;
			$contenido .= " Total Formula: ".FormatoValor($total_formula,2).$separador;
			$contenido .= chr(13);
			$contenido .= chr(10);
			$contenido .= chr(13);
			$contenido .= chr(10);
			$contenido .= chr(13);
			$contenido .= chr(10);
			}
			
			$contenido .= $separador;
			$contenido .= $separador;
			$contenido .= " TOTAL: ".FormatoValor($total,2).$separador;
			$contenido .= chr(13);
			$contenido .= chr(10);
			$contenido .= $separador;
			$arreglo=explode(".",$total);
			$contenido .= "SON: ".strtoupper($ctl->num2letras($arreglo[0],false,true))." PESOS ";
			if($arreglo[1]>0)
			$contenido .= " CON ".$arreglo[1]." CENTAVOS.";
			$contenido .= chr(13);
			$contenido .= chr(10);
			$contenido .= chr(13);
			$contenido .= chr(10);
			$contenido .= $separador;
			$contenido .= "Recibi ".(count($PaqueteFormulas)-1)." formulas arriba detalladas anexas a la presente relacion para su ".$separador;
			$contenido .= chr(13);
			$contenido .= chr(10);
			$contenido .= $separador;
			$contenido .= "previa verificacion, para constancia firmamos a satisfaccion.".$separador;
		return $contenido;
		}
 
	 /*
	 * Funcion Para Buscar Los Cortes 
	 * de Formulacion para la Auditorìa de ellos y se pueda generar factura
	 */
 
	function BuscarCortes_Auditoria($filtros,$offset)
	{
	
	$sql = "
		SELECT 
			a.empresa_id, 	
			a.centro_utilidad,
			c.razon_social||' '||b.descripcion as farmacia,
			a.numero, 	
			a.lapso,	
			a.corte_general_id, 	
			a.usuario_id, 	
			TO_CHAR(a.fecha_registro,'YYYY-MM-DD')as fecha_registro, 	
			a.corte_auditado, 	
			a.usuario_audita, 	
			a.fecha_auditoria, 	
			a.fecha_inicial, 	
			a.fecha_final,
			CASE 
			WHEN a.usuario_audita IS NULL	
			THEN 'auditoria_selec.png@NO AUDITADO@1'
			ELSE 'auditoria.png@OBSERVACION :'||a.observacion||' Usuario :'||d.nombre
			END as auditoria
		FROM
			ff_cortes AS a
			JOIN centros_utilidad as b ON (a.empresa_id = b.empresa_id)
			AND (a.centro_utilidad = b.centro_utilidad)
			JOIN empresas as c ON (b.empresa_id = c.empresa_id)
			LEFT JOIN system_usuarios as d ON (a.usuario_audita = d.usuario_id)
		WHERE TRUE	";
	if($filtros['farmacia'])
		$sql .= " AND b.descripcion||' '||c.razon_social ILIKE '%".trim($filtros['farmacia'])."%' ";
	if($filtros['lapso'])
		$sql .= " AND a.lapso = '".trim($filtros['lapso'])."' ";
	if($filtros['numero'])
		$sql .= " AND a.numero = '".trim($filtros['numero'])."' ";
	$cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
	$this->ProcesarSqlConteo($cont,$offset);
	$sql .= "ORDER BY a.empresa_id,a.centro_utilidad,a.lapso,a.numero ";
	$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
	
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
 * Auditar Cortes Formulacion
 */
    function Auditar_Corte($datos)
	{
			
			$sql  = "UPDATE ff_cortes
						SET
						corte_auditado = '1',
						usuario_audita = ".UserGetUID().",
						fecha_auditoria = NOW(),
						observacion = '".$datos['observacion']."'
						WHERE TRUE
						AND empresa_id ='".trim($datos['empresa_id'])."'
						AND centro_utilidad ='".trim($datos['centro_utilidad'])."'
						AND numero 	='".trim($datos['numero'])."'
						AND lapso ='".trim($datos['lapso'])."'
						AND corte_auditado = '0'; ";			
	
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
	}
 
 /* INSERTAR DESPACHOS 
 *	Dispensacion sin pendientes  
 */
 
	function CiudadesFacturan_Formulacion()
	{
		$sql  = "SELECT DISTINCT
		b.municipio||' - '||c.departamento ||' ('||d.pais||')' as localizacion,
		a.tipo_pais_id,
		a.tipo_dpto_id,	
		a.tipo_mpio_id
		FROM
		ff_cortes_generales as aa
		JOIN centros_utilidad as a ON (aa.empresa_id=a.empresa_id)
		AND (aa.centro_utilidad = a.centro_utilidad)
		JOIN tipo_mpios as b ON (a.tipo_pais_id = b.tipo_pais_id)
		AND (a.tipo_dpto_id = b.tipo_dpto_id)
		AND (a.tipo_mpio_id = b.tipo_mpio_id)
		JOIN tipo_dptos as c ON (b.tipo_pais_id = c.tipo_pais_id)
		AND (b.tipo_dpto_id = c.tipo_dpto_id)
		JOIN tipo_pais as d ON (c.tipo_pais_id = d.tipo_pais_id); ";			
	
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
	*	Consulta en la base de datos, los cortes por lapso y
	*	centro de utilidad q falta por facturar
	*/
 	function Consultar_PreFactura($filtros)
	{
		$arreglo=explode('@',$filtros['localizacion']);
		$sql  = "SELECT
		a.corte_general_id, 	
		a.empresa_id, 	
		a.centro_utilidad, 	
		a.lapso, 	
		a.descripcion, 	
		a.estado_corte_lapso, 	
		CASE
		WHEN a.estado_corte_lapso = '1'
		THEN 'LAPSO NO CERRADO'
		ELSE 'LAPSO CERRADO' END as estado,
		COALESCE(a.auditado,0) as auditado, 	
		COALESCE(a.no_auditado,0) as no_auditado, 	
		COALESCE(a.faltan_facturar,0) as faltan_facturar
		
		FROM
		cortes_sin_facturar as a
		WHERE TRUE
		AND a.lapso = '".$filtros['lapso']."'
		AND a.tipo_pais_id = '".trim($arreglo[0])."'
		AND a.tipo_dpto_id = '".trim($arreglo[1])."'
		AND a.tipo_mpio_id = '".trim($arreglo[2])."'; ";			
	
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
	
	
	function GenerarFacturaTemporal($buscador)
	{
	
	$arreglo=explode('@',$buscador['localizacion']);
	/*$this->debug=true;*/
	$sql = "
	SELECT
		a.lapso,
		a.codigo_producto,
		a.plan_id,
		c.tipo_tercero_id, 	
		c.tercero_id, 	
		c.plan_descripcion,
		d.nombre_tercero,
		fc_descripcion_producto(a.codigo_producto) as producto,
		SUM(a.cantidad) as cantidad,
		SUM(a.total_venta) as total_venta
	FROM
		ff_cortes_detalle AS a
		JOIN centros_utilidad as b ON (a.empresa_id = b.empresa_id)
		AND (a.centro_utilidad = b.centro_utilidad)
		JOIN planes as c ON (a.plan_id = c.plan_id)
		JOIN terceros as d ON (c.tipo_tercero_id = d.tipo_id_tercero)
		AND (c.tercero_id = d.tercero_id)
		JOIN inventarios_productos as e ON (a.codigo_producto = e.codigo_producto)
	WHERE TRUE
		AND a.lapso = '".trim($buscador['lapso'])."'
		AND a.plan_id = '".trim($buscador['plan_id'])."' 
		AND b.tipo_pais_id = '".trim($arreglo[0])."'
		AND b.tipo_dpto_id = '".trim($arreglo[1])."'
		AND b.tipo_mpio_id = '".trim($arreglo[2])."'
		AND a.empresa_factura IS NULL
	GROUP BY a.lapso,a.codigo_producto,a.plan_id,c.tipo_tercero_id, 	
		c.tercero_id, 	
		c.plan_descripcion,
		d.nombre_tercero
		ORDER BY producto;";
		
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		
	$datos = array(); 
	while(!$rst->EOF) 
		{
		$datos[] = $rst->GetRowAssoc($ToUpper = false); 
		$rst->MoveNext();
		}
	$rst->Close();
	
	global $ConfigAplication;
	$contenido=$this->GenerarContenidoArchivo_FacturaTmp($datos);
	
	$ctl = Autocarga::factory("ClaseUtil");
	
	$direccion = $ConfigAplication['DIR_SIIS']."tmp/facturas_tmp/";
	
	
	mkdir($direccion,0777);
	$url = $ConfigAplication['DIR_SIIS']."tmp/facturas_tmp/".$datos[0]['lapso']."".$datos[0]['plan_id']."".UserGetUID()."/";
	
	$ctl->BorrarDirectorio($url);
	mkdir($url,0777);
	
	$direccion = $ConfigAplication['DIR_SIIS']."tmp/facturas_tmp/".$datos[0]['lapso']."".$datos[0]['plan_id']."".UserGetUID()."/";
	$nombre_archivo = "facturaTmp".$datos[0]['lapso']."".$datos[0]['plan_id']."".UserGetUID()."";
	
	$this->CrearArchivo($contenido,$direccion,$nombre_archivo);
	
	return $datos;
	}
	
	
	function GenerarContenidoArchivo_FacturaTmp($datos)
	{
	
	$separador = ";";
	$ctl=Autocarga::factory("ClaseUtil");
	$contenido = $separador;
	$contenido .= "FACTURA TEMPORAL DE FORMULACION";
	$contenido .= $separador;
	$contenido .= $separador;
	$contenido .= chr(13);
	$contenido .= chr(10);
	$contenido .= $separador;
	$contenido .= "PLAN : ".$datos[0]['plan_descripcion'];
	$contenido .= chr(13);
	$contenido .= chr(10);
	$contenido .= $separador;
	$contenido .= "CLIENTE : ".$datos[0]['tipo_tercero_id']." - ".$datos[0]['tercero_id'].": ".$datos[0]['nombre_tercero'];
	$contenido .= chr(13);
	$contenido .= chr(10);
	$contenido .= $separador;
	$contenido .= "LAPSO :".$datos[0]['lapso'];
	$contenido .= chr(13);
	$contenido .= chr(10);
	$contenido .= $separador;
	$contenido .= "Fecha Exp. ".date('Y-m-d');
	$contenido .= chr(13);
	$contenido .= chr(10);
	$contenido .= chr(13);
	$contenido .= chr(10);
	$contenido .= "CODIGO".$separador;
	$contenido .= "PRODUCTO".$separador;
	$contenido .= "CANTIDAD".$separador;
	$contenido .= "TOTAL".$separador;
	$contenido .= chr(13);
	$contenido .= chr(10);
	$total =0;
		foreach($datos as $key => $valor)
		{
		$contenido .= "'".$valor['codigo_producto']."".$separador;
		$contenido .= $valor['producto']."".$separador;
		$contenido .= FormatoValor($valor['cantidad'])."".$separador;
		$contenido .= FormatoValor($valor['total_venta'],2)."".$separador;
		$contenido .= chr(13);
		$contenido .= chr(10);
		$total += $valor['total_venta'];
		}
	$contenido .= chr(13);
	$contenido .= chr(10);
	$contenido .= $separador;
	$contenido .= "TOTAL :$".FormatoValor($total,2);
	$contenido .= chr(13);
	$contenido .= chr(10);
	$contenido .= $separador;
	$arreglo = explode(".",$total);
	
	$contenido .= "SON: ".strtoupper($ctl->num2letras($arreglo[0],false,true))." PESOS ";
	if($arreglo[1]>0)
		$contenido .= " CON ".$arreglo[1]." CENTAVOS.";
	
	return $contenido;
	}
	
	function ConsultarDatos_Factura($datos)
	{
	$sql = "	SELECT
	a.documento_id,
	a.empresa_id,
	a.prefijo, 	 	
	a.numeracion
	FROM
	documentos as a
	WHERE TRUE
	AND a.empresa_id = '".trim($datos['empresa_id'])."'
	AND a.documento_id = '".trim($datos['ssiid'])."'; ";
	
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
	
	
	
	function ConsultaDatos_Facturacion($buscador,$datos)
	{
	$arreglo=explode('@',$buscador['localizacion']);
	$sql = "
	SELECT
		a.lapso,
		a.codigo_producto,
		round(AVG(e.porc_iva),2) as porc_iva,
		a.plan_id,
		c.tipo_tercero_id, 	
		c.tercero_id, 	
		c.plan_descripcion,
		d.nombre_tercero,
		round(SUM(a.cantidad)) as cantidad,
		SUM(a.total_venta) as total_venta
	FROM
		ff_cortes_detalle AS a
		JOIN centros_utilidad as b ON (a.empresa_id = b.empresa_id)
		AND (a.centro_utilidad = b.centro_utilidad)
		JOIN planes as c ON (a.plan_id = c.plan_id)
		JOIN terceros as d ON (c.tipo_tercero_id = d.tipo_id_tercero)
		AND (c.tercero_id = d.tercero_id)
		JOIN inventarios_productos as e ON (a.codigo_producto = e.codigo_producto)
	WHERE TRUE
		AND a.lapso = '".trim($buscador['lapso'])."'
		AND a.plan_id = '".trim($buscador['plan_id'])."' 
		AND b.tipo_pais_id = '".trim($arreglo[0])."'
		AND b.tipo_dpto_id = '".trim($arreglo[1])."'
		AND b.tipo_mpio_id = '".trim($arreglo[2])."'
		AND a.empresa_factura IS NULL
	GROUP BY a.lapso,a.codigo_producto,
					a.plan_id,c.tipo_tercero_id, 
					c.tercero_id, c.plan_descripcion, 
					d.nombre_tercero;";
		
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		
	$datos = array(); 
	$total =0;
	while(!$rst->EOF) 
		{
		$datos[] = $rst->GetRowAssoc($ToUpper = false); 
		$total +=$rst->fields[9];
		/*print_r($rst->fields[9]." - ");*/
		$rst->MoveNext();
		}
	$datos['total_venta']=$total;
	$rst->Close();
	return $datos;
	}
	
	function CrearFactura($datos_factura,$detalle_factura,$buscador)
	{
		$sql = "INSERT INTO fac_facturas 
					(
					empresa_id, 	
					documento_id, 	
					prefijo, 	
					factura_fiscal, 	
					estado, 	
					usuario_id, 	
					fecha_registro,
					plan_id, 	
					tipo_id_tercero, 	
					tercero_id, 	
					total_factura,
					saldo,
					tipo_factura
					)VALUES
					(
					'".trim($datos_factura['empresa_id'])."',
					'".trim($datos_factura['documento_id'])."',
					'".trim($datos_factura['prefijo'])."',
					'".trim($datos_factura['numeracion'])."',
					'0',
					".UserGetUID().",
					NOW(),
					'".$detalle_factura[0]['plan_id']."',
					'".$detalle_factura[0]['tipo_tercero_id']."',
					'".$detalle_factura[0]['tercero_id']."',
					'".$detalle_factura['total_venta']."',
					'".$detalle_factura['total_venta']."',
					'7'
					); \n";
	
		for($i=0;$i<(count($detalle_factura)-1);$i++)
			{
				$sql .=" 
							INSERT INTO fac_facturas_formulas
							(
							empresa_id, 	
							prefijo, 	
							factura_fiscal, 	
							codigo_producto, 	
							precio, 	
							valor_total, 	
							porcentaje_gravamen, 	
							cantidad
							)
							VALUES
							(
							'".trim($datos_factura['empresa_id'])."',
							'".trim($datos_factura['prefijo'])."',
							'".trim($datos_factura['numeracion'])."',
							'".trim($detalle_factura[$i]['codigo_producto'])."',
							".($detalle_factura[$i]['total_venta']/($detalle_factura[$i]['cantidad'])).",
							".($detalle_factura[$i]['total_venta']).",
							".($detalle_factura[$i]['porc_iva']).",
							".($detalle_factura[$i]['cantidad'])."
							);
							\n";
			}
			$arreglo=explode('@',$buscador['localizacion']);
			$sql .= " UPDATE ff_cortes_detalle as a
			SET 	
			empresa_factura = '".trim($datos_factura['empresa_id'])."',
			prefijo =	'".trim($datos_factura['prefijo'])."',
			factura_fiscal = '".trim($datos_factura['numeracion'])."'
			FROM
			centros_utilidad as b
			WHERE TRUE
			AND a.lapso = '".trim($buscador['lapso'])."'
			AND a.plan_id = '".trim($buscador['plan_id'])."' 
			AND a.empresa_id = b.empresa_id
			AND a.centro_utilidad = b.centro_utilidad
			AND b.tipo_pais_id = '".trim($arreglo[0])."'
			AND b.tipo_dpto_id = '".trim($arreglo[1])."'
			AND b.tipo_mpio_id = '".trim($arreglo[2])."'; \n ";
			
			$sql .= "UPDATE documentos 
			SET numeracion= numeracion + 1
			WHERE TRUE
			AND empresa_id = '".trim($datos_factura['empresa_id'])."'
			AND documento_id = '".trim($datos_factura['documento_id'])."';";
		/*print_r($sql);	*/
	if(!$rst = $this->ConexionBaseDatos($sql)) 
	return false;

	$rst->Close();
	return true;
	}
	
	/*
	* Funcion que Permite Listar Las Facturas de Formulacion Generadas en el sistema
	*/
	function FacturasFormulacion($buscador,$datos,$offset)
	{
	/*$this->debug=true;*/
	$sql = "	SELECT
	a.empresa_id, 	
	a.prefijo, 	
	a.factura_fiscal,	
	a.estado, 	
	a.usuario_id, 	
	d.nombre,
	TO_CHAR(a.fecha_registro,'YYYY-MM-DD') as fecha_registro, 	
	a.total_factura, 	
	a.gravamen, 	
	a.plan_id, 	
	c.plan_descripcion,
	a.tipo_id_tercero, 	
	a.tercero_id, 	
	b.nombre_tercero,
	b.direccion,
	b.dv,
	a.sw_clase_factura, 	
	a.concepto, 	
	a.documento_id, 	
	a.tipo_factura, 	
	a.saldo,	
	a.retencion_fuente,
	e.razon_social,
	e.digito_verificacion,
	e.tipo_id_tercero as tipo_id_tercero_empresa,
	e.id,
	f.texto1, 	
	f.texto2, 	
	f.texto3, 	
	f.mensaje, 	
	f.descripcion,
	g.fecha_inicial,
	h.fecha_final
	FROM
	fac_facturas AS a
	JOIN terceros as b ON (a.tipo_id_tercero = b.tipo_id_tercero)
	AND (a.tercero_id = b.tercero_id)
	JOIN planes as c ON (a.plan_id = c.plan_id)
	JOIN system_usuarios as d ON (a.usuario_id = d.usuario_id)
	JOIN empresas as e ON(a.empresa_id = e.empresa_id)
	JOIN documentos as f ON (a.documento_id = f.documento_id)
	JOIN (
			SELECT
			a.empresa_factura, 	
			a.prefijo, 	
			a.factura_fiscal,
			MIN(b.fecha_inicial) as fecha_inicial
			FROM
			ff_cortes_detalle as a
			JOIN ff_cortes as b ON (a.empresa_id = b.empresa_id)
			AND (a.centro_utilidad = b.centro_utilidad )
			AND (a.numero = b.numero )
			AND (a.lapso = b.lapso)
			and (a.empresa_factura IS NOT NULL)
			GROUP BY 1,2,3
		) as g ON (a.empresa_id = g.empresa_factura)
		AND (a.prefijo = g.prefijo)
		AND (a.factura_fiscal = g.factura_fiscal)
	JOIN (
			SELECT
			a.empresa_factura, 	
			a.prefijo, 	
			a.factura_fiscal,
			MAX(b.fecha_final) as fecha_final
			FROM
			ff_cortes_detalle as a
			JOIN ff_cortes as b ON (a.empresa_id = b.empresa_id)
			AND (a.centro_utilidad = b.centro_utilidad )
			AND (a.numero = b.numero )
			AND (a.lapso = b.lapso)
			and (a.empresa_factura IS NOT NULL)
			GROUP BY 1,2,3
		) as h ON (a.empresa_id = h.empresa_factura)
		AND (a.prefijo = h.prefijo)
		AND (a.factura_fiscal = h.factura_fiscal)
	WHERE TRUE
	AND a.tipo_factura = '7'
	AND a.empresa_id = '".trim($datos['empresa_id'])."'	";
	if(!empty($buscador['prefijo']))
	$sql .= " AND a.prefijo = '".trim($buscador['prefijo'])."' ";
	if(!empty($buscador['plan_id']))
	$sql .= " AND a.plan_id = '".trim($buscador['plan_id'])."' ";
	if(!empty($buscador['factura_fiscal']))
	$sql .= " AND a.factura_fiscal = '".trim($buscador['factura_fiscal'])."' ";
	if(!empty($buscador['nombre_tercero']))
	$sql .= " AND b.nombre_tercero ILIKE '%".trim($buscador['nombre_tercero'])."%' ";
	
	$cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
	$this->ProcesarSqlConteo($cont,$offset);
	$sql .= "ORDER BY a.fecha_registro,a.prefijo,a.factura_fiscal ";
	$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
	
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
	* Consulta de Prefijos Factura para la Busqueda
	* Con base a los registros por empresa en fac_facturas con tipo_factura = '7'
	*/
	function PrefijosFactura($datos)
	{
	$sql = "	SELECT DISTINCT
	a.prefijo
	FROM
	fac_facturas AS a
	WHERE TRUE
	AND a.empresa_id = '".trim($datos['empresa_id'])."'
	AND a.tipo_factura = '7';";
		
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
	* Funcion donde se verifica el permiso del usuario para el ingreso al modulo
	* @return array $datos vector que contiene la informacion de la consulta del codigo de
	* la empresa y la razon social
	*/
      
      function Detalle_Factura($empresa_id,$prefijo,$factura_fiscal)
      {
           /*$this->debug=true;*/
		   /*(a.cantidad-a.cantidad_devuelta) as cantidad,*/
		   /*(((a.cantidad-a.cantidad_devuelta))*(a.valor_unitario+(a.valor_unitario*(a.porc_iva/100)))) as valor_total_iva*/
        $sql  = "SELECT
				a.empresa_id,
				a.prefijo,
				a.factura_fiscal,
				a.codigo_producto, 	
				fc_descripcion_producto(a.codigo_producto) as descripcion,
				(a.valor_total-((a.valor_total)/((COALESCE(a.porcentaje_gravamen,0)/100)+1))) as iva,
				a.precio, 	
				a.valor_total, 	
				a.porcentaje_gravamen, 	
				a.valor_gravamen, 	
				a.cantidad
				FROM
				fac_facturas_formulas as a
				WHERE
				a.empresa_id ='".trim($empresa_id)."'
				AND a.prefijo ='".($prefijo)."'
				AND a.factura_fiscal ='".trim($factura_fiscal)."'
				ORDER BY a.porcentaje_gravamen;";
        
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
	
	
	
	/* INSERTAR DESPACHOS dispensacion  pendientes  */
 	function Insertar_DetalleTemporal_dispensacion_pendientes($request,$corte_tmp_id,$bodegas_doc_id,$numeracion)
	{
		
			$sql  = "INSERT INTO  esm_corte_despacho_medicamentos_pendientes_temporal(";
			$sql .= "       empresa_id, ";
			$sql .= "       corte_tmp_id, ";
			$sql .= "       bodegas_doc_id, ";
			$sql .= "       numeracion ";
			$sql .= "          ) ";
			$sql .= "VALUES ( ";
			$sql .= "        '".$request['datos']['empresa_id']."', ";
			$sql .= "        ".$corte_tmp_id.", ";
			$sql .= "        ".$bodegas_doc_id.", ";
			$sql .= "        ".$numeracion." ";
			
			$sql .= "       ); ";			
	
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		
		$rst->Close();
		return true;
	}
    

 function Insertar_Corte($request,$DATOS)
		{
	  
    $sql  = "INSERT INTO esm_corte (";
		$sql .= "       corte_id, ";
		$sql .= "       fecha_inicio, ";
		$sql .= "       fecha_final, ";
		$sql .= "       empresa_id, ";
		$sql .= "       usuario_id ";
		
    $sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        default, ";
		$sql .= "        '".$DATOS['fecha_inicio']."', ";
		$sql .= "        '".$DATOS['fecha_final']."', ";
    $sql .= "        '".$request['datos']['empresa_id']."', ";
		$sql .= "         ".UserGetUID()." ";
	  $sql .= "       )RETURNING(corte_id); ";	
	
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

		$rst->Close();
		}
    
   function Insertar_DetalleCorte($valor,$Token,$tabla,$campo)
		{
	
    $sql  = "INSERT INTO ".$tabla." (";
		$sql .= "       ".$campo.", ";
		$sql .= "       ems_corte_id, ";
		$sql .= "       formula_papel, ";
		$sql .= "       documento, ";
		$sql .= "       valor, ";
		$sql .= "       descripcion ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        default, ";
		$sql .= "        ".$Token['corte_id'].", ";
		$sql .= "        '".$valor['formula_papel']."', ";
    $sql .= "        '".$valor['prefijo']."-".$valor['numeracion']."', ";
		$sql .= "        COALESCE(".$valor['total_costo'].",0), ";
		$sql .= "        '".$valor['documento_descripcion']."' ";
		$sql .= "       ); ";			
	
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
		}
        

  
    function Estado_DESPACHOS_TRASLADOS($empresa_id,$fecha_inicio,$fecha_final,$documento,$tabla)
		{
        $filtro = "  AND       b.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
        $filtro .= " AND       b.fecha_registro <= '".$fecha_final." 24:00:00' ";
    		
    		$sql = "
                   UPDATE 
                       ".$tabla." a
                       SET
                       empresa_id_factura = '".$empresa_id."',
                       prefijo_factura = '".$documento['prefijo']."',
                       factura_fiscal = ".$documento['numeracion']."
                       FROM
                       inv_bodegas_movimiento as b
                       WHERE
                              a.empresa_id_factura IS NULL
                       and    a.empresa_id = b.empresa_id       
                       and    a.prefijo = b.prefijo       
                       and    a.numero = b.numero     
                             ".$filtro." ";
         
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
    } 
    
    function Estado_DISPENSADOS($empresa_id,$fecha_inicio,$fecha_final,$Token,$tabla)
		{
        //esm_formulacion_despachos_medicamentos
        $filtro = "  AND       b.fecha_registro >= '".$fecha_inicio." 00:00:00' ";
        $filtro .= " AND       b.fecha_registro <= '".$fecha_final." 24:00:00' ";
    	
    		$sql = "
                   UPDATE 
                       ".$tabla." a
                       SET
                       sw_corte = '1',
                       esm_corte_id = ".$Token['corte_id']."
                       FROM
                       bodegas_documentos as b
                       WHERE
                              a.sw_corte ='0'
                       and    a.bodegas_doc_id = b.bodegas_doc_id       
                       and    a.numeracion = b.numeracion       
                       ".$filtro." ";
         
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
    } 
    
    function Borrar_Temporal($empresa_id)
		{
	
      $sql = " delete from esm_corte_temporal ";
      $sql .= " where ";
      $sql .= "         empresa_id = '".$empresa_id."' ";
	  
	  
	  
         
		
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
    $rst->Close();
		return true;
		}
		
    


		function Informacion_cortes_Final($corte)
		{
			
				
				$sql = "      SELECT   	formula_papel,
								         documento,
										 valor,
										 descripcion
								FROM (

										SELECT  formula_papel,
												documento,
												valor,
												descripcion
										
										FROM    esm_corte_dispensacion
										WHERE   ems_corte_id = '".$corte."'

										UNION

										SELECT 	formula_papel,
												documento,
												valor,
												descripcion
												
										FROM    esm_corte_dispensacion_pendientes
										WHERE    ems_corte_id = '".$corte."'
									)AS A  ";
 
	        
                 
							if(!$rst = $this->ConexionBaseDatos($sql))	return false;
							$rst->Close();
              return $rst;
		}
    
	
		/*
		* FUNCION QUE PERMITE BUSCAR LAS FACTURAS QUE HARAN PARTE DE LOS RIPOS
		*/
		function BuscarFacturas($datos_empresa,$buscador)
			{
			
			$sql = "SELECT
			a.empresa_id, 	
			a.prefijo, 	
			a.factura_fiscal, 	
			a.plan_id,
			b.plan_descripcion,
			c.nombre_tercero,
			TO_CHAR(a.fecha_registro,'YYYY-MM-DD') as fecha_registro,
			a.total_factura,
			a.plan_id, 	
			a.tipo_id_tercero, 	
			a.tercero_id,
			a.saldo
			FROM
			fac_facturas as a
			JOIN planes as b ON (a.plan_id = b.plan_id)
			JOIN terceros as c ON (a.tipo_id_tercero = c.tipo_id_tercero)
			AND (a.tercero_id = c.tercero_id)
			WHERE TRUE
			AND 	a.empresa_id = '".trim($datos_empresa['empresa_id'])."'
			AND	a.plan_id = '".trim($buscador['plan_id'])."' 
			AND	a.empresa_id||a.prefijo||a.factura_fiscal NOT IN 
																				(SELECT 
																				x.empresa_id||x.prefijo||x.factura_fiscal as factura
																				FROM
																				ff_envios_rips_detalle AS x
																				WHERE TRUE
																				AND x.empresa_id = '".trim($datos_empresa['empresa_id'])."'
																				AND x.plan_id = '".trim($buscador['plan_id'])."' 
																				) ";
			/*if(!empty($buscador['fecha_inicio']))*/
			$sql .= "
			AND a.fecha_registro >= '".trim($this->DividirFecha($buscador['fecha_inicio']))."'::date ";
			/*if(!empty($buscador['fecha_final']))*/
			$sql .= "
			AND a.fecha_registro <= ('".trim($this->DividirFecha($buscador['fecha_final']))."'::date +'1 day'::interval)::date ";
			$sql .= "
			AND a.tipo_factura = '7'
			ORDER BY a.fecha_registro;";
			
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
			
			/*
			* Funcion que Permite Regitrar los envìos RIPS a las EPS
			* Para Despues Convertirlos en Informes RIPS
			*/
			function RegistrarEnvio($datos_empresa,$facturas,$buscador)
			{
			
			$sql = "
			SELECT 
			a.empresa_id,
			a.numeracion
			FROM
			ff_envios_parametros AS a
			WHERE TRUE
			AND a.empresa_id = '".trim($datos_empresa['empresa_id'])."';	";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); 
				while(!$rst->EOF) 
					{	
					$datos = $rst->GetRowAssoc($ToUpper = false); 
					$rst->MoveNext();
					}
			$rst->Close();
			
			$sql = "INSERT INTO ff_envios_rips
					(
					empresa_id,
					numeracion,
					usuario_id
					)
					VALUES
					(
					'".trim($datos_empresa['empresa_id'])."',
					".$datos['numeracion'].",
					".UserGetUID()."
					);";
					
					for($i=0;$i<=$facturas['facturas']['registros'];$i++)
						{
							$arreglo = explode("@",$facturas['facturas'][$i]);
							
							if(!empty($arreglo[0]))
								{
									$sql .= "INSERT INTO ff_envios_rips_detalle
									(
									empresa_id,
									numeracion,
									prefijo,
									factura_fiscal,
									plan_id,
									usuario_id
									)
									VALUES
									(
									'".trim($datos_empresa['empresa_id'])."',
									".$datos['numeracion'].",
									'".trim($arreglo[0])."',
									'".trim($arreglo[1])."',
									'".trim($facturas['buscador']['plan_id'])."',
									".UserGetUID()."
									);";
								}
						} 
			
			$sql .= "	UPDATE ff_envios_parametros 
							SET
							numeracion = numeracion+1
							WHERE TRUE
							AND empresa_id = '".trim($datos_empresa['empresa_id'])."'; ";
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
			
			return $datos;
			}
			
			
		function ConsultarEnvios($datos_empresa,$filtros)
		{
		
		$sql = "SELECT 
		a.empresa_id, 	
		a.numeracion, 	
		TO_CHAR(a.fecha_registro,'YYYY-MM-DD') as fecha_registro,
		b.total,
		c.plan_id,
		c.plan_descripcion
		FROM
		ff_envios_rips AS a
		JOIN (
				SELECT
				numeracion,
				plan_id,
				count(*) as total
				FROM
				ff_envios_rips_detalle
				WHERE TRUE
				AND	empresa_id = '".trim($datos_empresa['empresa_id'])."' ";
		if(!empty($filtros['plan_id']))
		$sql .= "
				AND	 plan_id = '".trim($filtros['plan_id'])."' ";
		$sql .="
				GROUP BY numeracion,plan_id
				) as b ON(a.numeracion = b.numeracion)
		JOIN planes as c ON (b.plan_id = c.plan_id)
		WHERE TRUE
		AND	a.empresa_id = '".trim($datos_empresa['empresa_id'])."' ";
		if(!empty($filtros['numeracion']))
		$sql .= "
		AND	a.numeracion = '".trim($filtros['numeracion'])."';";
		
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
	
		/**
		* Funcion donde se obtiene el nombre de un usuario
		*
		* @param int $usuario Identificacion del usuario
		*
		* @return mixed
		*/
		function ObtenerInformacionUsuario($usuario)
		{
		$sql .= "SELECT	nombre ";
		$sql .= "FROM		system_usuarios "; 
		$sql .= "WHERE	usuario_id = ".$usuario." ";		

		if(!$rst = $this->ConexionBaseDatos($sql))
		return false;

		$datos = array();
		if(!$rst->EOF)
		{
		$datos =  $rst->GetRowAssoc($ToUpper = false);
		$rst->MoveNext();
		}
		$rst->Close();
		return $datos;
		}
	
	
	
	}
	
?>