<?php
	/**************************************************************************************
	* $Id: BuscadorMedicamentos.class.php,v 1.3 2006/08/16 15:37:09 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Hugo F. Manrique	
	**************************************************************************************/
	class BuscadorMedicamentos
	{
		function BuscadorMedicamentos()
		{
			return true;
		}
		/********************************************************************
		*
		*********************************************************************/  
		function Medicamentos_Frecuentes_Diagnostico($datos)
		{
			$sql .= "SELECT	CASE WHEN ME.sw_pos = 1 THEN 'POS' ";
			$sql .= "				ELSE 'NO POS' END AS item, ";
			$sql .= "				HD.codigo_medicamento AS codigo_producto,";
			$sql .= "				ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				ME.concentracion_forma_farmacologica AS cff, ";
			$sql .= "				ME.unidad_medida_medicamento_id AS ummi,";
			$sql .= "				IF.descripcion AS forma, ";
			$sql .= " 			IF.unidad_dosificacion, ";
			$sql .= "				IF.cod_forma_farmacologica,";
			$sql .= "				HM.descripcion AS categoria, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				HM.sw_soluciones ";
			$sql .= "FROM		hc_formulacion_hospitalaria_grupos_medicamentos_mezclas HM,";
			$sql .= "				hc_formulacion_hospitalaria_grupos_medicamentos_mezclas_d HD,";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				inv_med_cod_principios_activos AS IA, ";
			$sql .= "				inventarios_productos AS ID, ";
			$sql .= "				inv_med_cod_forma_farmacologica AS IF ";
			$sql .= "WHERE 	HD.codigo_medicamento = ID.codigo_producto ";
			$sql .= "AND 		ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		ID.estado = '1' ";
			$sql .= "AND		HM.grupo_id = HD.grupo_id ";
			$sql .= "AND		HM.sw_soluciones = '".$datos."' ";
			$sql .= "AND		HD.codigo_medicamento = ME.codigo_medicamento ";
			$sql .= "AND 		IF.cod_forma_farmacologica = ME.cod_forma_farmacologica ";
			$sql .= "ORDER BY categoria ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$i=0;
			$medicamentos = array();
			while (!$rst->EOF)
			{
				$medicamentos[$rst->fields[9]][$i] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				$i++;
			}

			$rst->Close();
			return $medicamentos;
		}
		/********************************************************************
		*
		*********************************************************************/  
		function GruposMezclas()
		{
			$sql .= "SELECT	grupo_mezcla_id, ";
			$sql .= "				descripcion ";
			$sql .= "FROM		hc_formulacion_hospitalaria_mezclas_grupos ";
			$sql .= "ORDER BY descripcion ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$mezclas = array();
			while (!$rst->EOF)
			{
				$mezclas[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}

			$rst->Close();
			return $mezclas;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function UnidadesSolucion()
		{
			$sql .= "SELECT	unidad_volumen,";
			$sql .= " 			indice_de_orden ";
			$sql .= "FROM		hc_formulacion_hospitalaria_mezclas_volumenes ";
			$sql .= "ORDER BY unidad_volumen ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$mezclas = array();
			while (!$rst->EOF)
			{
				$mezclas[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}

			$rst->Close();
			return $mezclas;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function BuscarMedicamentos($mezcla)
		{	
			$flag = true;
			$valores = "";
			$codigos = array();
			$datos = SessionGetVar("SolucionesPrevias");
			$solven = $datos[$mezcla];
			
			foreach($solven as $key => $datos1)
			{
				$codigos[$key] = $key;
				$flag ?  $valores .= " '$key'" : $valores .= ",'$key'";
				$flag = false;
			}
			
			SessionSetVar("CodigosRecetaSeleccionados",$codigos);
			
			$sql .= "SELECT CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item,";
			$sql .= "				IM.codigo_producto, ";
			$sql .= "				IM.descripcion as producto, ";
			$sql .= "				ME.concentracion_forma_farmacologica,	";
			$sql .= "				ME.unidad_medida_medicamento_id,";
			$sql .= "				ME.factor_conversion, ";
			$sql .= "				ME.factor_equivalente_mg,";
			$sql .= "				ME.sw_liquidos_electrolitos AS sw_soluciones,";
			$sql .= "				IA.descripcion AS principio_activo,";
			$sql .= "				IF.descripcion AS forma,";
			$sql .= "				IF.unidad_dosificacion,";
			$sql .= "				IU.descripcion AS umm, ";
			$sql .= "				IF.cod_forma_farmacologica ";
			$sql .= "FROM 	inventarios_productos IM,";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IU ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IU.unidad_medida_medicamento_id), ";
			$sql .= "				inv_med_cod_principios_activos IA,  ";
			$sql .= "				inv_med_cod_forma_farmacologica IF  ";
			$sql .= "WHERE	IM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		ME.cod_forma_farmacologica = IF.cod_forma_farmacologica ";
			$sql .= "AND		ME.codigo_medicamento IN ($valores) ";
			$sql .= "ORDER BY IM.codigo_producto ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			SessionSetVar("MedicamentosRecetaSeleccionados",$retorno);
			return true;
		}
		/********************************************************************************
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*********************************************************************************/
		function ProcesarSqlConteo($consulta,$limite=null)
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
			
			if($_REQUEST['offset'])
			{
				$this->paginaActual = intval($_REQUEST['offset']);
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
		/********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*********************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug = true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
?>
