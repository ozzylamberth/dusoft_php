<?php
  /******************************************************************************
  * $Id: Soluciones.class.php,v 1.1 2006/08/18 20:32:54 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.1 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	class Soluciones
	{
		function Soluciones(){}
		/**********************************************************************************
		* Funcion donde se buscan los medicamentos existentes
		* 
		* @return array 
		***********************************************************************************/
		function BuscarMedicamentos($producto,$principio_activo,$pagina)
		{	
			$where = "";
			
			$sql .= "SELECT CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item,";
			$sql .= "				IM.codigo_producto, ";
			$sql .= "				IM.descripcion as producto, ";
			$sql .= "				ME.concentracion_forma_farmacologica,	";
			$sql .= "				ME.unidad_medida_medicamento_id,";
			$sql .= "				ME.factor_conversion, ";
			$sql .= "				ME.factor_equivalente_mg,";
			$sql .= "				IA.descripcion AS principio_activo,";
			$sql .= "				IF.descripcion AS forma,";
			$sql .= "				IF.unidad_dosificacion,";
			$sql .= "				IU.descripcion AS umm, ";
			$sql .= "				IF.cod_forma_farmacologica ";
	
			$where .= "FROM 	inventarios_productos IM, ";
			$where .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IU ";
			$where .= "				ON(ME.unidad_medida_medicamento_id = IU.unidad_medida_medicamento_id), ";
			$where .= "				inv_med_cod_principios_activos IA,  ";
			$where .= "				inv_med_cod_forma_farmacologica IF  ";
			$where .= "WHERE	IM.codigo_producto = ME.codigo_medicamento ";
			$where .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$where .= "AND 		ME.cod_forma_farmacologica = IF.cod_forma_farmacologica ";
			$where .= "AND 		IM.estado = '1' ";
			
			if ($producto != '') $where .= "AND		IM.descripcion ILIKE '%".$producto."%'";
			if ($principio_activo != '') $where .= "AND 		IA.descripcion ILIKE '%".$principio_activo."%'";
			
			$this->ProcesarSqlConteo("SELECT COUNT(*) $where",null,$pagina);
			
			$sql .= $where;
			$sql .= "ORDER BY IM.codigo_producto ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/**********************************************************************************
		* Funcion donde se obtienen las plantillas activas registradas en el sistema
		**********************************************************************************/
		function ObtenerPlantillas()
		{
			$sql .= "SELECT hc_modulo,descripcion ";
			$sql .= "FROM		system_hc_modulos ";
			$sql .= "WHERE	activo = '1' ";
			$sql .= "ORDER BY descripcion ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/********************************************************************************
		* Funcion que permite hacer una busqueda especial de los medicamentos de acuerdo
		* al grupo que se pasa
		*********************************************************************************/
		function BuscarMedicamentosEspecial($producto,$principio_activo,$pagina,$grupo)
		{			
			$sql .= "SELECT CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item,";
			$sql .= "				IM.codigo_producto, ";
			$sql .= "				IM.descripcion as producto, ";
			$sql .= "				ME.concentracion_forma_farmacologica,	";
			$sql .= "				ME.unidad_medida_medicamento_id,";
			$sql .= "				ME.factor_conversion, ";
			$sql .= "				ME.factor_equivalente_mg,";
			$sql .= "				IA.descripcion AS principio_activo,";
			$sql .= "				IF.descripcion AS forma,";
			$sql .= "				IF.unidad_dosificacion,";
			$sql .= "				IU.descripcion AS umm, ";
			$sql .= "				IF.cod_forma_farmacologica, ";
			$sql .= "				SE.marca AS marca ";
			
			$where .= "FROM 	inventarios_productos IM, ";	
			$where .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IU ";
			$where .= "				ON(ME.unidad_medida_medicamento_id = IU.unidad_medida_medicamento_id) ";
			$where .= "				LEFT JOIN ";
			$where .= "				(	SELECT	'1' AS marca, ";
			$where .= "									HD.codigo_medicamento ";
			$where .= "					FROM		hc_formulacion_hospitalaria_grupos_medicamentos_mezclas HM,";
			$where .= "									hc_formulacion_hospitalaria_grupos_medicamentos_mezclas_d HD ";
			$where .= "					WHERE		HM.grupo_id = HD.grupo_id ";
			$where .= "				) AS SE ";
			$where .= "				ON(ME.codigo_medicamento = SE.codigo_medicamento), ";
			$where .= "				inv_med_cod_principios_activos IA,  ";
			$where .= "				inv_med_cod_forma_farmacologica IF  ";
			$where .= "WHERE	IM.codigo_producto = ME.codigo_medicamento ";
			$where .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$where .= "AND 		ME.cod_forma_farmacologica = IF.cod_forma_farmacologica ";
			$where .= "AND 		IM.estado = '1' ";
			
			if ($producto != '') $where .= "AND		IM.descripcion ILIKE '%".$producto."%'";
			if ($principio_activo != '') $where .= "AND 		IA.descripcion ILIKE '%".$principio_activo."%'";
			
			$this->ProcesarSqlConteo("SELECT COUNT(*) $where",null,$pagina);
			
			$sql .= $where;
			$sql .= "ORDER BY producto ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/**********************************************************************************
		* Funcion donde se crean los medicamentos
		* 
		* @return array informacion de los motivo de anulacion de las facturas
		***********************************************************************************/
		function IngresarGrupoMedicamentos($nombre,$medicamentos,$plantilla)
		{
			$sql .= "SELECT COALESCE(TO_NUMBER(MAX(grupo_id),99999999999999999999),0)+1 ";
			$sql .= "FROM hc_formulacion_hospitalaria_grupos_medicamentos ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$id = "01";
			
			if(!$rst->EOF)
				$id = $rst->fields[0];
			
			if(strlen($id."") == 1) $id = "0".$id;
			
			$sql  = "INSERT INTO hc_formulacion_hospitalaria_grupos_medicamentos ";
			$sql .= "				(";
			$sql .= "					grupo_id,";
			$sql .= "					descripcion ";
			$sql .= "				)";
			$sql .= "VALUES(";
			$sql .= "					'".$id."',";
			$sql .= "					'".strtoupper($nombre)."' ";
			$sql .= "				);";
			
			foreach($medicamentos as $key => $datos)
			{
				$sql .= "INSERT INTO hc_formulacion_hospitalaria_grupos_medicamentos_d ";
				$sql .= "				(";
				$sql .= "					grupo_id,";
				$sql .= "					codigo_medicamento ";
				$sql .= "				)";
				$sql .= "VALUES(";
				$sql .= "					'".$id."',";
				$sql .= "					'".$key."' ";
				$sql .= "				);";
			}
			
			foreach($plantilla as $key1 => $plantillas)
			{
				$sql .= "INSERT INTO hc_formulacion_hospitalaria_templates ";
				$sql .= "				(";
				$sql .= "					grupo_id,";
				$sql .= "					hc_modulo ";
				$sql .= "				)";
				$sql .= "VALUES(";
				$sql .= "					'".$id."',";
				$sql .= "					'".$key1."' ";
				$sql .= "				);";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se crean los grupos de soluciones
		* 
		* @return boolean
		***********************************************************************************/
		function IngresarGrupoSolucion($nombre,$plantillas)
		{
			$sql .= "SELECT COALESCE(MAX(grupo_mezcla_id),0)+1 ";
			$sql .= "FROM hc_formulacion_hospitalaria_mezclas_grupos ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$id = "1";
			
			if(!$rst->EOF)	$id = $rst->fields[0];
			
			$sql  = "INSERT INTO hc_formulacion_hospitalaria_mezclas_grupos ";
			$sql .= "				(";
			$sql .= "					grupo_mezcla_id,";
			$sql .= "					descripcion ";
			$sql .= "				)";
			$sql .= "VALUES(";
			$sql .= "					 ".$id.",";
			$sql .= "					'".strtoupper($nombre)."' ";
			$sql .= "				);";
			
			foreach($plantillas as $key => $datos)
			{
				$sql .= "INSERT INTO hc_formulacion_hospitalaria_mezclas_templates ";
				$sql .= "				(";
				$sql .= "					grupo_mezcla_id,";
				$sql .= "					hc_modulo ";
				$sql .= "				)";
				$sql .= "VALUES(";
				$sql .= "					 ".$id.",";
				$sql .= "					'".$key."' ";
				$sql .= "				);";
			}
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
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
		/********************************************************************
		* Funcion donde se buscan los grupos de soluciones existentes 
		*********************************************************************/  
		function GruposSoluciones()
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
		/********************************************************************
		* Funcion donde se traen los grupos de medicamentos que han sido
		* catalogados como soluciones
		*********************************************************************/
		function GruposMedicamentosSoluciones()
		{
			$sql .= "SELECT	grupo_id,";
			$sql .= " 			descripcion,";
			$sql .= " 			sw_soluciones ";
			$sql .= "FROM		hc_formulacion_hospitalaria_grupos_medicamentos_mezclas ";
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
		/********************************************************************
		* Funcion donde se ingresan los datos de la clasificacion de 
		* medicamentos, si son soluciones o no
		*********************************************************************/
		function IngresarGrupoClasificacion($nombre,$sw_solucion)
		{
			$sql .= "SELECT COALESCE(TO_NUMBER(MAX(grupo_id),99999999999999999999),0)+1 ";
			$sql .= "FROM hc_formulacion_hospitalaria_grupos_medicamentos_mezclas ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$id = "01";
			
			if(!$rst->EOF)
				$id = $rst->fields[0];
			
			if(strlen($id."") == 1) $id = "0".$id;
				
			$sql  = "INSERT INTO hc_formulacion_hospitalaria_grupos_medicamentos_mezclas ";
			$sql .= "				(";
			$sql .= "					grupo_id,";
			$sql .= "					descripcion, ";
			$sql .= "					sw_soluciones ";
			$sql .= "				)";
			$sql .= "VALUES(";
			$sql .= "					'".$id."',";
			$sql .= "					'".strtoupper($nombre)."', ";
			$sql .= "					'".$sw_solucion."' ";
			$sql .= "				);";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/********************************************************************
		* Funcion donde se crea la solucion y adiciona el detalle de la misma 
		*********************************************************************/  
		function CrearSolucion($medicamentos,$nombre,$grupo)
		{
			$solucion = "";
			$this->ConexionTransaccion();
			
			$sql  = "SELECT NEXTVAL('hc_formulacion_hospitalaria_mezclas_mezcla_id_seq') ";
			
			if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;
			
			if(!$rst->EOF) $solucion = $rst->fields[0];
      
			$sql  = "INSERT INTO hc_formulacion_hospitalaria_mezclas ";
			$sql .= "				(mezcla_id,";
			$sql .= "				 descripcion ) ";
			$sql .= "VALUES (";
			$sql .= "				 ".$solucion.",";
			$sql .= "				'".ucwords($nombre)."' ";
			$sql .= "				);";
			
			$sql .= "INSERT INTO hc_formulacion_hospitalaria_mezclas_grupos_d( ";
			$sql .= "					grupo_mezcla_id,";
			$sql .= "					mezcla_id) ";
			$sql .= "VALUES	(";
			$sql .= "				 ".$grupo.", ";
			$sql .= "				 ".$solucion." ";
			$sql .= "				);";
			
			if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
					
			$sql = "";
			foreach($medicamentos as $key => $datos)
			{
				$sql .= "INSERT INTO hc_formulacion_hospitalaria_mezclas_d( ";
				$sql .= "				mezcla_id,";
				$sql .= "				codigo_medicamento,";
				$sql .= " 			sw_solucion ) ";
				$sql .= "VALUES (";
				$sql .= "				 ".$solucion.", ";
				$sql .= "				'".$datos['codigo_producto']."', ";
				$sql .= "				'".$datos['sw_soluciones']."' ";
				$sql .= "				);";
			}
			
			if(!$rst = $this->ConexionTransaccion($sql,'3')) return false;
			
			$this->dbconn->CommitTrans();
			return true;
		}
		/**********************************************************************************
		* Funcion donde se buscan los medicamwntos asociados a un grupo en particular
		***********************************************************************************/
		function BuscarMedicamentosGrupo($grupo)
		{
			$sql .= "SELECT	HD.codigo_medicamento AS codigo_producto, ";
			$sql .= "				IM.descripcion as producto ";
			$sql .= "FROM		hc_formulacion_hospitalaria_grupos_medicamentos_mezclas HM,";
			$sql .= "				hc_formulacion_hospitalaria_grupos_medicamentos_mezclas_d HD, ";
			$sql .= "				inventarios_productos IM, ";	
			$sql .= "				medicamentos ME ";

			$sql .= "WHERE	HM.grupo_id = HD.grupo_id ";
			$sql .= "AND		IM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND		ME.codigo_medicamento = HD.codigo_medicamento ";
			$sql .= "AND		HM.grupo_id = HD.grupo_id ";
			$sql .= "AND		HD.grupo_id = '".$grupo."' ";
			$sql .= "ORDER BY producto ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			$medicamentos = array();
			while (!$rst->EOF)
			{
				$medicamentos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $medicamentos;
		}
		/**********************************************************************************
		* Funcion donde se asocian los medicanebtos a un grupo de soluciones determinada
		***********************************************************************************/
		function IngresarAsociacionGrupo($medica,$grupo)
		{
			if(strlen($grupo."") == 1) $grupo = "0".$grupo;
			
			foreach($medica as $key => $datos)
			{
				$sql .= "INSERT INTO hc_formulacion_hospitalaria_grupos_medicamentos_mezclas_d";
				$sql .= "			(	grupo_id,";
				$sql .= " 			codigo_medicamento) ";
				$sql .= "VALUES(";
				$sql .= "			'".$grupo."', ";
				$sql .= "			'".$key."' ";
				$sql .= "			); ";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se elimina la asociacion de los medicanebtos con un grupo de 
		* soluciones determinada
		***********************************************************************************/
		function EliminarAsociacionGrupo($medica,$grupo)
		{
			if(strlen($grupo."") == 1) $grupo = "0".$grupo ;

			foreach($medica as $key => $datos)
			{
				$sql .= "DELETE FROM hc_formulacion_hospitalaria_grupos_medicamentos_mezclas_d ";
				$sql .= "WHERE	grupo_id = '".$grupo."' ";
				$sql .= "AND		codigo_medicamento = '".$key."'; ";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se obtienen los grupos de medicamentos
		***********************************************************************************/
		function ObtenerGruposMedicamentos()
		{
			$sql .= "SELECT	grupo_id,";
			$sql .= "				descripcion ";
			$sql .= "FROM 	hc_formulacion_hospitalaria_grupos_medicamentos ";
			$sql .= "ORDER BY descripcion ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$grupos = array();
			while (!$rst->EOF)
			{
				$grupos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $grupos;
		}
		/**********************************************************************************
		* funcion donde se obtienen las plantillas asociadas al grupo de medicamentos
		***********************************************************************************/
		function ObtenerPlantillasAsociadas($grupo)
		{		
			$sql .= "SELECT	SH.hc_modulo, ";
			$sql .= "				SH.descripcion ";
			$sql .= "FROM		system_hc_modulos SH,";
			$sql .= "				hc_formulacion_hospitalaria_templates HT ";
			$sql .= "WHERE	HT.hc_modulo = SH.hc_modulo ";
			$sql .= "AND		HT.grupo_id = '".$grupo."' ";
			$sql .= "ORDER BY SH.descripcion ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$grupos = array();
			while (!$rst->EOF)
			{
				$grupos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $grupos;
		}
		/**********************************************************************************
		* Funcion donde se obtienen los medicamentos que pertenecen a un grupo
		***********************************************************************************/
		function ObtenerMedicamentosGrupo($grupo)
		{
			$sql .= "SELECT	HD.codigo_medicamento AS codigo, ";
			$sql .= "				IM.descripcion as nombre ";
			$sql .= "FROM		hc_formulacion_hospitalaria_grupos_medicamentos_d HD, ";
			$sql .= "				inventarios_productos IM, ";	
			$sql .= "				medicamentos ME ";
			$sql .= "WHERE	IM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND		ME.codigo_medicamento = HD.codigo_medicamento ";
			$sql .= "AND		HD.grupo_id = '".$grupo."' ";
			$sql .= "ORDER BY nombre ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			$medicamentos = array();
			while (!$rst->EOF)
			{
				$medicamentos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $medicamentos;
		}
		/**********************************************************************************
		* Funcion donde adiconan medicamentos a un grupo determinado
		* 
		* @return boolean
		***********************************************************************************/
		function IngresarMedicamentosGrupos($medicamentos,$grupo)
		{	
			if(strlen($grupo."") == 1) $grupo = "0".$grupo;
				
			foreach($medicamentos as $key => $datos)
			{
				$sql .= "INSERT INTO hc_formulacion_hospitalaria_grupos_medicamentos_d ";
				$sql .= "				(";
				$sql .= "					grupo_id,";
				$sql .= "					codigo_medicamento ";
				$sql .= "				)";
				$sql .= "VALUES(";
				$sql .= "					'".$grupo."',";
				$sql .= "					'".$key."' ";
				$sql .= "				);";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se adiconan plantillas a un grupo de medicamentos determinado
		* 
		* @return boolean
		***********************************************************************************/
		function IngresarPlantillasGrupo($plantillas,$grupo)
		{	
			if(strlen($grupo."") == 1) $grupo = "0".$grupo;
							
			foreach($plantillas as $key => $datos)
			{
				$sql .= "INSERT INTO hc_formulacion_hospitalaria_templates ";
				$sql .= "				(";
				$sql .= "					grupo_id,";
				$sql .= "					hc_modulo ";
				$sql .= "				)";
				$sql .= "VALUES(";
				$sql .= "					'".$grupo."',";
				$sql .= "					'".$key."' ";
				$sql .= "				);";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se eliminan los medicamentos de un grupo
		* 
		* @return boolean
		***********************************************************************************/
		function EliminarMedicamentosGrupos($medicamentos,$grupo)
		{	
			if(strlen($grupo."") == 1) $grupo = "0".$grupo;
				
			foreach($medicamentos as $key => $datos)
			{
				$sql .= "DELETE FROM hc_formulacion_hospitalaria_grupos_medicamentos_d ";
				$sql .= "WHERE	grupo_id = '".$grupo."' ";
				$sql .= "AND		codigo_medicamento = '".$key."';";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se eliminan las plantillas que pertenecen a un grupo de 
		* medicamentos
		* 
		* @return boolean
		***********************************************************************************/
		function EliminarPlantillasGrupo($plantillas,$grupo)
		{	
			if(strlen($grupo."") == 1) $grupo = "0".$grupo;
							
			foreach($plantillas as $key => $datos)
			{
				$sql .= "DELETE FROM hc_formulacion_hospitalaria_templates ";
				$sql .= "WHERE 	grupo_id = '".$grupo."' ";
				$sql .= "AND		hc_modulo	=	'".$key."' ;";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se obtienen los grupos de soluciones creados
		***********************************************************************************/
		function ObtenerGruposSoluciones()
		{
			$sql .= "SELECT	grupo_mezcla_id,";
			$sql .= "				descripcion ";
			$sql .= "FROM		hc_formulacion_hospitalaria_mezclas_grupos ";
			$sql .= "ORDER BY descripcion ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$grupo = array();
			while (!$rst->EOF)
			{
				$grupo[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $grupo;
		}
		/**********************************************************************************
		* Funcion donde se obtienen las plantillas asociadas al grupo de soluciones
		***********************************************************************************/
		function ObtenerPlantillasSoluciones($grupo)
		{				
			$sql .= "SELECT	SH.hc_modulo, ";
			$sql .= "				SH.descripcion ";
			$sql .= "FROM		system_hc_modulos SH,";
			$sql .= "				hc_formulacion_hospitalaria_mezclas_templates HT ";
			$sql .= "WHERE	HT.hc_modulo = SH.hc_modulo ";
			$sql .= "AND		HT.grupo_mezcla_id = ".$grupo." ";
			$sql .= "ORDER BY SH.descripcion ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$grupos = array();
			while (!$rst->EOF)
			{
				$grupos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $grupos;
		}
		/**********************************************************************************
		* Funcion donde se asocian las plantillas con los grupos de soluciones
		* 
		* @return boolean
		***********************************************************************************/
		function IngresarPlantillasSolucion($plantillas,$grupo)
		{			
			foreach($plantillas as $key => $datos)
			{
				$sql .= "INSERT INTO hc_formulacion_hospitalaria_mezclas_templates ";
				$sql .= "				(";
				$sql .= "					grupo_mezcla_id,";
				$sql .= "					hc_modulo ";
				$sql .= "				)";
				$sql .= "VALUES(";
				$sql .= "					 ".$grupo.",";
				$sql .= "					'".$key."' ";
				$sql .= "				);";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se elimina la asociacion de las plantillas y los grupos de 
		* soluciones
		* 
		* @return boolean
		***********************************************************************************/
		function EliminarPlantillasSolucion($plantillas,$grupo)
		{			
			foreach($plantillas as $key => $datos)
			{
				$sql .= "DELETE FROM hc_formulacion_hospitalaria_mezclas_templates ";
				$sql .= "WHERE	grupo_mezcla_id = ".$grupo." ";
				$sql .= "AND		hc_modulo = '".$key."'; ";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se elimina la informacion asociadas al grupo de medicamentos
		***********************************************************************************/
		function EliminarGrupoMedicamentos($grupo)
		{		
			$this->ConexionTransaccion();
			
			$sql  = "DELETE FROM hc_formulacion_hospitalaria_templates ";
			$sql .= "WHERE	grupo_id = '".$grupo."'; ";
			if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;
			
			$sql  = "DELETE FROM hc_formulacion_hospitalaria_grupos_medicamentos_d ";
			$sql .= "WHERE	grupo_id = '".$grupo."'; ";
			if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
			
			$sql  = "DELETE FROM hc_formulacion_hospitalaria_grupos_medicamentos ";
			$sql .= "WHERE	grupo_id = '".$grupo."'; ";
			if(!$rst = $this->ConexionTransaccion($sql,'3')) return false;
			
			$this->dbconn->CommitTrans();
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se elimina la informacion asociadas al grupo de medicamentos
		***********************************************************************************/
		function ObtenerInformacionSolucion($grupo)
		{
			$sql .= "SELECT	HD.codigo_medicamento,";
			$sql .= "				HM.descripcion AS mezcla, ";
			$sql .= "				ID.descripcion AS producto, ";
			$sql .= "				HM.mezcla_id, ";
			$sql .= "				SE.sw_soluciones ";
			$sql .= "FROM		hc_formulacion_hospitalaria_mezclas HM,";
			$sql .= "				hc_formulacion_hospitalaria_mezclas_d HD,";
			$sql .= "				hc_formulacion_hospitalaria_mezclas_grupos HH,";
			$sql .= "				hc_formulacion_hospitalaria_mezclas_grupos_d HG,";
			$sql .= "				inventarios_productos AS ID LEFT JOIN ";
			$sql .= "				(	SELECT	HM.sw_soluciones, ";
			$sql .= "									HD.codigo_medicamento ";
			$sql .= "					FROM		hc_formulacion_hospitalaria_grupos_medicamentos_mezclas HM,";
			$sql .= "									hc_formulacion_hospitalaria_grupos_medicamentos_mezclas_d HD ";
			$sql .= "					WHERE		HM.grupo_id = HD.grupo_id ";
			$sql .= "				) AS SE ";
			$sql .= "				ON(ID.codigo_producto = SE.codigo_medicamento) ";
			$sql .= "WHERE 	HD.codigo_medicamento = ID.codigo_producto ";
			$sql .= "AND		HM.mezcla_id = HD.mezcla_id ";
			$sql .= "AND		HG.grupo_mezcla_id = HH.grupo_mezcla_id ";
			$sql .= "AND		HM.mezcla_id = HG.mezcla_id ";
			$sql .= "AND		HH.grupo_mezcla_id = ".$grupo." ";
			$sql .= "ORDER BY mezcla ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$soluciones = array();
			
			while (!$rst->EOF)
			{
				$soluciones[$rst->fields[1]][$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $soluciones;
		}
		/**********************************************************************************
		* Funcion donde se elimina la informacion asociadas al grupo de soluciones
		***********************************************************************************/
		function EliminarGrupoSoluciones($grupo,$soluciones)
		{		
			$this->ConexionTransaccion();
			
			$sql  = "DELETE FROM hc_formulacion_hospitalaria_mezclas_templates ";
			$sql .= "WHERE	grupo_mezcla_id = ".$grupo."; ";
			if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;
			
			$sql  = "DELETE FROM hc_formulacion_hospitalaria_mezclas_grupos_d ";
			$sql .= "WHERE	grupo_mezcla_id = ".$grupo."; ";
			if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
			
			$sql  = "DELETE FROM hc_formulacion_hospitalaria_mezclas_grupos ";
			$sql .= "WHERE	grupo_mezcla_id = ".$grupo."; ";
			if(!$rst = $this->ConexionTransaccion($sql,'3')) return false;
			
			$i =4;
			foreach($soluciones as $key => $datos)
			{
				$sql  = "DELETE FROM hc_formulacion_hospitalaria_mezclas_d ";
				$sql .= "WHERE	mezcla_id = '".$key."'; ";
				$sql .= "DELETE FROM hc_formulacion_hospitalaria_mezclas ";
				$sql .= "WHERE	mezcla_id = '".$key."'; ";
				
				if(!$rst = $this->ConexionTransaccion($sql,$i++)) return false;
			}
			$this->dbconn->CommitTrans();
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se elimina la informacion de las soluciones
		***********************************************************************************/
		function EliminarSoluciones($soluciones,$grupo)
		{
			$this->ConexionTransaccion();
					
			$i=1;
			foreach($soluciones as $key => $datos)
			{
				$sql  = "DELETE FROM hc_formulacion_hospitalaria_mezclas_grupos_d ";
				$sql .= "WHERE	grupo_mezcla_id = ".$grupo." ";
				$sql .= "AND		mezcla_id = '".$key."'; ";

				if(!$rst = $this->ConexionTransaccion($sql,$i++)) return false;

				$sql  = "DELETE FROM hc_formulacion_hospitalaria_mezclas_d ";
				$sql .= "WHERE	mezcla_id = '".$key."'; ";
				$sql .= "DELETE FROM hc_formulacion_hospitalaria_mezclas ";
				$sql .= "WHERE	mezcla_id = '".$key."'; ";
				
				if(!$rst = $this->ConexionTransaccion($sql,$i++)) return false;
			}
			$this->dbconn->CommitTrans();		
			return true;
		}
		/**********************************************************************************
		* Funcion donde se elimina la asociacion de medicamwntos y soluciones, para un 
		* grupo de medicamentos soluciones en particular
		***********************************************************************************/
		function EliminarGruposMedicamentosSoluciones($grupo)
		{
			$sql .= "DELETE FROM hc_formulacion_hospitalaria_grupos_medicamentos_mezclas_d ";
			$sql .= "WHERE	grupo_id = '".$grupo."'; ";
			
			$sql .= "DELETE FROM hc_formulacion_hospitalaria_grupos_medicamentos_mezclas ";
			$sql .= "WHERE	grupo_id = '".$grupo."'; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			$rst->Close();
			
			return true;
		}
		/**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param 	string  $sql	sentencia sql a ejecutar 
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
		/**********************************************************************************
		* Funcion que permite crear una transaccion 
		* @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
		* @param char $num Numero correspondiente a la sentecia sql - por defect es 1
		*
		* @return object Objeto de la transaccion - Al momento de iniciar la transaccion no 
		*								 se devuelve nada
		***********************************************************************************/
		function ConexionTransaccion($sql,$num = '1')
		{
			if(!$sql)
			{
				list($this->dbconn) = GetDBconn();
				//$this->dbconn->debug=true;
				$this->dbconn->BeginTrans();
			}
			else
			{
				$rst = $this->dbconn->Execute($sql);
				if ($this->dbconn->ErrorNo() != 0)
				{
					$this->frmError['MensajeError'] = "ERROR DB : " . $this->dbconn->ErrorMsg();
					echo "<b class=\"label\">Trasaccion: $num - ".$this->frmError['MensajeError']."</b>";
					$this->dbconn->RollbackTrans();
					return false;
				}
				return $rst;
			}
		}
	}
?>