<?php

/**
* $Id: RipsDuana.class.php,v 1.10
*/

/**
* Clase para la GENERACION DE RIPS DE FORMULACION EXTERNA
*
* @author Mauricio Adrian Medina Santacruz
* @version 1.0
* @package SIIS
*/
class RipsDuana extends  ConexionBD
{
	
	/*
	* Arreglo Donde se Guardaran la Informacion de los Archivos Para el Archivo de Control.
	*/
	var $archivos = array();
	var $saltolinea;
	var $separador = ",";
	var $DireccionClase = "";
  /*
    * Constructor de la clase
  */
    function RipsDuana()
    {
	return true;
    }
    /*
    * Funcion donde se crea un menu para la dispensacion normal.
    */
    function MenuOpcion($opcion,$datos,$request,$direccion_carpeta)
    {
	$ctl = Autocarga::factory("ClaseUtil");
	$this->saltolinea .=chr(13);
	$this->saltolinea .=chr(10);
	
		/*$archivos = Array();*/
        switch($opcion)
        {
			case '1':
				$DatosAF=$this->InformacionAF($datos,$request);
				$DatosUS=$this->InformacionUS($datos,$request);
				$DatosAD=$this->InformacionAD($datos,$request);
				$DatosAM=$this->InformacionAM($datos,$request);
				
				
				$archivos['AF']['cantidad']=count($DatosAF);
				$archivos['AF']['archivo']="AF".date('Y').$request['datos_envio']['numeracion'];
				$archivos['US']['cantidad']=count($DatosUS);
				$archivos['US']['archivo']="US".date('Y').$request['datos_envio']['numeracion'];
				$archivos['AD']['cantidad']=count($DatosAD);
				$archivos['AD']['archivo']="AD".date('Y').$request['datos_envio']['numeracion'];
				$archivos['AM']['cantidad']=count($DatosAM);
				$archivos['AM']['archivo']="AM".date('Y').$request['datos_envio']['numeracion'];
				$DatosCT=$this->InformacionCT($datos,$request,$archivos);
				
				$carpeta_rips = "Envio".date('Y').$request['datos_envio']['numeracion'].UserGetUID()."/";
				$carpeta_rips_retorno = "Envio".date('Y').$request['datos_envio']['numeracion'].UserGetUID()." ";
				
				
				/*GENERO LA INFORMACION PARA LOS ARCHIVOS RIPS*/
				$AF=$this->GenerarContenidoArchivos($DatosAF,'AF');
				$US=$this->GenerarContenidoArchivos($DatosUS,'US');
				$AD=$this->GenerarContenidoArchivos($DatosAD,'AD');
				$AM=$this->GenerarContenidoArchivos($DatosAM,'AM');
				$CT=$this->GenerarContenidoArchivos($DatosCT,'CT');
				$direccion_rips = $direccion_carpeta.$carpeta_rips;
				
				/*Limpio el Directorio donde puedan haber archivos viejos Rips*/
				$ctl->BorrarDirectorio($direccion_rips);
				
				/*Creo Directorio para Nuevos Archivos*/
				mkdir($direccion_rips,0777);
				
				$this->CrearArchivo($AF,$direccion_rips,"AF".date('Y').$request['datos_envio']['numeracion'].".txt");
				$this->CrearArchivo($US,$direccion_rips,"US".date('Y').$request['datos_envio']['numeracion'].".txt");
				$this->CrearArchivo($AD,$direccion_rips,"AD".date('Y').$request['datos_envio']['numeracion'].".txt");
				$this->CrearArchivo($AM,$direccion_rips,"AM".date('Y').$request['datos_envio']['numeracion'].".txt");
				$this->CrearArchivo($CT,$direccion_rips,"CT".date('Y').$request['datos_envio']['numeracion'].".txt");
				return trim($carpeta_rips_retorno);
			break;
			
			default:
			break;
		}

    }
	
	/*
	* FUNCION QUE PERMITE GENERAR EL ARCHIVO AF
	*/
	function InformacionAF($datos,$request)
	{
		/*$this->debug=true;*/
		$sql = "SELECT
		'".$datos['codigo_sgsss']."' as codigo_sgsss,
		'".$datos['empresa']."' as nombre_tercero,
		'".$datos['tipo_id_tercero']."' as tipo_id_tercero,
		'".$datos['id']."' as tercero_id,
		b.prefijo||b.factura_fiscal as factura,
		TO_CHAR(b.fecha_registro,'DD/MM/YYYY') as fecha_expedicion,
		TO_CHAR((substring(f.lapso from 0 for 5)||'-'||substring(f.lapso from 5 for 8)||'-'||'01')::date,'DD/MM/YYYY') as fecha_inicio,
		TO_CHAR((((substring(f.lapso from 0 for 5)||'-'||substring(f.lapso from 5 for 8)||'-'||'01')::date +'1 month'::interval)::date -'1 day'::interval)::date,'DD/MM/YYYY') as fecha_final,
		e.codigo_sgsss as codigo_sgsss_plan,
		d.nombre_tercero as nombre_tercero_plan,
		c.num_contrato,
		c.plan_descripcion,
		'' as numero_poliza,
		'0'::integer as copago,
		'0'::integer as valor_comision,
		'0'::integer as valor_descuentos,
		b.total_factura
		FROM
		ff_envios_rips_detalle as a
		JOIN fac_facturas as b ON (a.empresa_id = b.empresa_id)
		AND (a.prefijo = b.prefijo)
		AND (a.factura_fiscal = b.factura_fiscal)
		JOIN planes as c ON (a.plan_id = c.plan_id)
		JOIN terceros as d ON (c.tipo_tercero_id = d.tipo_id_tercero)
		AND (c.tercero_id = d.tercero_id)
		LEFT JOIN terceros_sgsss as e ON (d.tipo_id_tercero = e.tipo_id_tercero)
		AND (d.tercero_id = e.tercero_id)
		JOIN (
				SELECT
					empresa_factura,
					prefijo,
					factura_fiscal,
					lapso
				FROM
					ff_cortes_detalle
				group by 	
					empresa_factura,
					prefijo,
					factura_fiscal,
					lapso
				) as f ON (a.empresa_id = f.empresa_factura)
				AND (a.prefijo = f.prefijo)
				AND (a.factura_fiscal = f.factura_fiscal)
		WHERE TRUE
		AND a.empresa_id = '".trim($datos['empresa_id'])."' 
		AND a.numeracion = '".trim($request['datos_envio']['numeracion'])."' ";
		
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
	* FUNCION QUE PERMITE GENERAR EL ARCHIVO US
	*/
	function InformacionUS($datos,$request)
	{
		$sql = "SELECT DISTINCT
		c.tipo_id_paciente,
		c.paciente_id,
		f.codigo_sgsss,
		g.regimen_id,
		h.primer_apellido,
		h.segundo_apellido,
		h.primer_nombre,
		h.segundo_nombre,
		edad(h.fecha_nacimiento) as edad,
		'1' as unidad_medida_edad,
		h.sexo_id,
		h.tipo_dpto_id as cod_departamento,
		h.tipo_mpio_id as cod_municipio,
		h.zona_residencia
		FROM
		ff_envios_rips_detalle as a
		JOIN ff_cortes_detalle as b ON (a.empresa_id = b.empresa_factura)
		AND (a.prefijo = b.prefijo)
		AND (a.factura_fiscal = b.factura_fiscal)
		JOIN esm_formula_externa as c ON (b.formula_id = c.formula_id)		
		JOIN planes as d ON (a.plan_id = d.plan_id)
		JOIN terceros as e ON (d.tipo_tercero_id = e.tipo_id_tercero)
		AND (d.tercero_id = e.tercero_id)
		LEFT JOIN terceros_sgsss as f ON (e.tipo_id_tercero = f.tipo_id_tercero)
		AND (e.tercero_id = f.tercero_id)
		LEFT JOIN tipos_cliente as g ON (d.tipo_cliente = g.tipo_cliente)
		JOIN pacientes as h ON (c.tipo_id_paciente = h.tipo_id_paciente)
		AND (c.paciente_id = h.paciente_id)
		WHERE TRUE
		AND a.empresa_id = '".trim($datos['empresa_id'])."' 
		AND a.numeracion = '".trim($request['datos_envio']['numeracion'])."' 
		ORDER BY c.tipo_id_paciente,c.paciente_id ;";
		
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
	* FUNCION QUE PERMITE GENERAR EL ARCHIVO AD
	*/
	function InformacionAD($datos,$request)
	{
		/*$this->debug=true;*/
		$sql = "SELECT 
				b.prefijo||b.factura_fiscal as factura,
				f.codigo_sgsss,
				CASE
				WHEN sw_pos = '1' 
				THEN '12'
				ELSE '13'
				END as codigo_concepto,
				c.cantidad,
				round(c.precio,2) as precio,
				round(c.valor_total,2) as valor_total
		FROM
				ff_envios_rips_detalle as a
				JOIN fac_facturas as b ON (a.empresa_id = b.empresa_id)
				AND (a.prefijo = b.prefijo)
				AND (a.factura_fiscal = b.factura_fiscal)
				JOIN fac_facturas_formulas as c ON (b.empresa_id = c.empresa_id)
				AND (b.prefijo = c.prefijo)
				AND (b.factura_fiscal = c.factura_fiscal)
				JOIN inventarios_productos as d ON (c.codigo_producto = d.codigo_producto)
				JOIN planes as e ON (b.plan_id = e.plan_id)
				LEFT JOIN terceros_sgsss as f ON (e.tipo_tercero_id = f.tipo_id_tercero)
				AND (e.tercero_id = f.tercero_id)
		WHERE TRUE
				AND a.empresa_id = '".trim($datos['empresa_id'])."' 
				AND a.numeracion = '".trim($request['datos_envio']['numeracion'])."' ;";
		
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
	* FUNCION QUE PERMITE GENERAR EL ARCHIVO AM
	*/
	function InformacionAM($datos,$request)
	{
		/*$this->debug=true;*/
		$sql = "SELECT 
				a.prefijo||a.factura_fiscal as factura,
				'".$datos['codigo_sgsss']."' as codigo_sgsss,
				c.tipo_id_paciente,
				c.paciente_id,
				'' as numero_autorizacion,
				CASE d.sw_pos when 0 then ''
				ELSE g.cod_anatomofarmacologico||''||g.cod_principio_activo||''||g.cod_forma_farmacologica||''||g.cod_concentracion
				END as codigo_producto,
				CASE
				WHEN d.sw_pos = '1' 
				THEN '1'
				ELSE '2'
				END as tipo_medicamento,
				d.descripcion,
				i.descripcion as forma_farmaceutica,
				g.concentracion_forma_farmacologica as concentracion,
				h.descripcion as unidad, 
				round(b.cantidad) as numero_unidades,
				round((b.total_venta/b.cantidad),2) as valor_unitario,
				round((b.total_venta),2) as valor_total,
				d.codigo_producto as codigo_anex
		FROM
				ff_envios_rips_detalle as a
				JOIN ff_cortes_detalle as b ON (a.empresa_id = b.empresa_factura)
				AND (a.prefijo = b.prefijo)
				AND (a.factura_fiscal = b.factura_fiscal)
				JOIN esm_formula_externa as c ON (b.formula_id = c.formula_id)
				JOIN inventarios_productos as d ON (b.codigo_producto = d.codigo_producto)
				JOIN planes as e ON (b.plan_id = e.plan_id)
				LEFT JOIN terceros_sgsss as f ON (e.tipo_tercero_id = f.tipo_id_tercero)
				AND (e.tercero_id = f.tercero_id)
				LEFT JOIN medicamentos as g ON(d.codigo_producto = g.codigo_medicamento)
				LEFT JOIN inv_unidades_medida_medicamentos as h ON (g.unidad_medida_medicamento_id = h.unidad_medida_medicamento_id)
				LEFT JOIN inv_med_cod_forma_farmacologica as i ON (g.cod_forma_farmacologica = i.cod_forma_farmacologica)
		WHERE TRUE
				AND a.empresa_id = '".trim($datos['empresa_id'])."' 
				AND a.numeracion = '".trim($request['datos_envio']['numeracion'])."' ;";
		
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
	* FUNCION QUE PERMITE GENERAR EL ARCHIVO AM
	*/
	function InformacionCT($datos,$request,$archivos)
	{
		/*$this->debug=true;*/
		$sql = "SELECT DISTINCT
			'".$datos['codigo_sgsss']."' as codigo_sgsss
			FROM
				ff_envios_rips_detalle as a
				JOIN planes as b ON (a.plan_id = b.plan_id)
				LEFT JOIN terceros_sgsss as c ON (b.tipo_tercero_id = c.tipo_id_tercero)
				AND (b.tercero_id = c.tercero_id)
			WHERE TRUE
				AND a.empresa_id = '".trim($datos['empresa_id'])."' 
				AND a.numeracion = '".trim($request['datos_envio']['numeracion'])."' ;";
		
		if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$consulta = array(); 
				while(!$rst->EOF) 
					{	
					$consulta[] = $rst->GetRowAssoc($ToUpper = false); 
					$rst->MoveNext();
					}
		$rst->Close();
		$i=0;
		$datos = array(); 
		foreach($archivos as $tipo_rips => $valor)
			{
				$datos[$i]['codigo_sgsss'] = $consulta[0]['codigo_sgsss'];
				$datos[$i]['fecha_remision'] = date('d/m/Y');
				$datos[$i]['codigo_archivo'] = $tipo_rips.date('Y').$request['datos_envio']['numeracion'];
				$datos[$i]['total_registros'] = $valor['cantidad'];
				$i++;
			}
		
		return $datos;
	}
	
	/*
	* Funcion que Permite Generar el Contenido que va a ir dentro de los Archivos Planos
	*/
	function GenerarContenidoArchivos($datos,$tipo_rips = null)
	{
	/*Obtengo Archivo Configuracion*/
	$parametros=parse_ini_file($this->DireccionClase.'parametros.ini', true);
	
	//OBTENGO LAS COLUMNAS
	$contenido = "";
	$i=0;
	  foreach($datos[0] as $k=>$row)
           {
		   $cabecera[]= $k;
		   $i++;
           }
	
     $j=0;
	for($j=0;$j<count($datos);$j++)
		{
		for($k=0;$k<$i;$k++)
			{
			if(!empty($parametros))
				{
				if(!empty($parametros[$tipo_rips]))
					{
					if((!empty($parametros[$tipo_rips][$cabecera[$k]])) && $parametros[$tipo_rips][$cabecera[$k]] != "default")
						$contenido .= substr($datos[$j][$cabecera[$k]],0,$parametros[$tipo_rips][$cabecera[$k]]);
						else
							$contenido .= $datos[$j][$cabecera[$k]];
					}
					else
							$contenido .= $datos[$j][$cabecera[$k]];
				}
					else
						$contenido .= $datos[$j][$cabecera[$k]];
					
				if($k<($i-1))
					$contenido .= $this->separador;
				else
					$contenido .= $this->saltolinea;
			}
		}
	return $contenido;
	}
    
	function CrearArchivo($ContenidoArchivo,$direccion,$nombre)
	{
		$ruta_archivo = $direccion.$nombre;
		$archivo = fopen($ruta_archivo,'w');
		fwrite($archivo, $ContenidoArchivo);
		fclose($archivo);
		return true;
	}
}
?>